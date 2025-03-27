<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Transactions\Orders\Receipt;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function show(): View
    {
        $user = auth()->user();
        $order = $user->client?->orders()->cart()->latest()->first();
        $products = Product::select('products.*')
            ->join('categories', 'categories.id', 'products.main_category_id')
            ->orderBy('categories.order')
            ->orderBy('products.name')
            ->with('category')
            ->take(10)
            ->get();

        return view('home', [
            'user' => $user,
            'products' => $products->map(fn (Product $product) => [
                'id' => $product->id,
                'name' => $product->name,
                'category' => $product->category->name,
            ]),
            'receipt' => new Receipt($order),
        ]);
    }
}
