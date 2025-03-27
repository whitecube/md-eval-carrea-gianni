<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Transactions\Orders\OrderStatus;
use App\Transactions\Orders\Receipt;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function create(CreateOrderRequest $request): JsonResponse|RedirectResponse
    {
        if (! $quantity = $request->quantity) {
            return $this->getResponse($request);
        }

        $client = $request->user()?->client;
        $product = Product::findOrFail($request->id);

        $order = $client->orders()->create([
            'status' => OrderStatus::Cart,
        ]);

        $item = $this->makeOrderProduct($order, $product);
        $item->quantity = $quantity;
        $item->price_final = $item->price_unit->multipliedBy($quantity);
        $item->save();

        return $this->getResponse($request, $order);
    }

    public function update(UpdateOrderRequest $request, Order $order): JsonResponse|RedirectResponse
    {
        if (! $item = $this->getOrderProduct($request, $order)) {
            return $this->getResponse($request, $order);
        }

        if (! $request->quantity) {
            $item->delete();
        } else {
            $item->quantity = $request->quantity;
            $item->price_final = $item->price_unit->multipliedBy($request->quantity);
            $item->save();
        }

        if (! $order->products()->count()) {
            $order->forceDelete();

            return $this->getResponse($request);
        }

        return $this->getResponse($request, $order);
    }

    protected function getOrderProduct(UpdateOrderRequest $request, Order $order): ?OrderProduct
    {
        if (! is_null($request->line)) {
            return $order->products->firstWhere('id', $request->line);
        }

        if ($request->quantity) {
            $product = Product::findOrFail($request->id);

            return $this->makeOrderProduct($order, $product);
        }

        return null;
    }

    protected function makeOrderProduct(Order $order, Product $product): OrderProduct
    {
        return $order->products()->make([
            'product_id' => $product->id,
            'price_unit' => $product->price_selling ?? 0,
        ]);
    }

    protected function getResponse(Request $request, ?Order $order = null): JsonResponse|RedirectResponse
    {
        $receipt = new Receipt($order);

        if ($request->ajax()) {
            return response()->json($receipt);
        }

        return redirect()->to('home');
    }
}
