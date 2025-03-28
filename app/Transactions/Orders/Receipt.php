<?php

namespace App\Transactions\Orders;

use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Traits\FormatsPrices;
use Brick\Money\Money;
use Illuminate\Support\Collection;
use JsonSerializable;
use Illuminate\Support\Facades\Log;

class Receipt implements JsonSerializable
{
    use FormatsPrices;

    protected ?Order $order;

    public Collection $products;

    public Collection $detail;

    public function __construct(?Order $order = null)
    {
        $this->order = $order;
        $this->products = $this->getProducts();
        $this->detail = $this->getDetail();
    }

    protected function getProducts(): Collection
    {
        if (! $this->order) {
            return collect();
        }
        
        $client = $this->order->client;

        Log::channel('custom')->debug('Receipt - getProducts | Client type:'. $client->type->label());
        
        return $this->order->products()
            ->with('product')
            ->ordered()
            ->get()
            ->map(fn (OrderProduct $product) => new Lines\Product($product, $client->type));
    }

    protected function getDetail(): Collection
    {
        if ($this->products->isEmpty()) {
            return collect();
        }

        return collect([
            new Lines\Subtotal($this->products),
            new Lines\DeliveryFee,
        ]);
    }

    public function getDisplayableProducts(): array
    {
        return $this->toDisplayableLines($this->products);
    }

    public function getDisplayableDetail(): array
    {
        return $this->toDisplayableLines($this->detail);
    }

    protected function toDisplayableLines(Collection $collection): array
    {
        return $collection
            ->filter(fn (ReceiptLine $line) => $line->isDisplayable())
            ->map(fn (ReceiptLine $line) => array_merge($line->getProductAttributes(), [
                'type' => $line->getType(),
                'deletable' => $line->isDeletable(),
                'label' => $line->getLabel(),
                'quantity' => $line->getQuantity(),
                'price' => $line->getDisplayablePrice(),
            ]))
            ->values()
            ->all();
    }

    public function getTotal(): Money
    {
        return $this->detail
            ->reduce(function (Money $carry, ReceiptLine $line) {
                return $carry->plus($line->getPrice());
            }, Money::zero('EUR'));
    }

    public function getDiscountedTotal(): Money
    {
        return $this->detail
            ->reduce(function (Money $carry, ReceiptLine $line) {
                return $carry->plus($line->getDiscountedPrice());
            }, Money::zero('EUR'));
    }

    public function getDiscountValue() : Money
    {
        return $this->products
            ->reduce(function (Money $carry, ReceiptLine $line) {
                return $carry->plus($line->getDiscountValue());
            }, Money::zero('EUR'));
    }

    public function getDisplayableDiscountValue() : string
    {
        return $this->formatPrice($this->getDiscountValue());
    }

    public function getDisplayableTotal(): string
    {
        return $this->formatPrice($this->getTotal());
    }

    public function getDisplayableDiscountedTotal(): string
    {
        return $this->formatPrice($this->getDiscountedTotal());
    }

    public function hasDiscount(): bool
    {
        return $this->products->contains(fn (ReceiptLine $line) => ! is_null($line->getDiscountValue()) && $line->getDiscountValue()->isPositive());
    }

    public function hasMargin(): bool
    {  
        return $this->products->contains(fn (ReceiptLine $line) => $line->hasMargin());
    }

    public function getDiscountLabels(): ?string
    {
        return $this->products
            ->filter(fn (ReceiptLine $line) => ! is_null($line->getDiscountLabel()))
            ->map(fn (ReceiptLine $line) => $line->getDiscountLabel())
            ->unique()
            ->implode(', ');
    }

    public function jsonSerialize(): array
    {

        try {
          
            log::channel('custom')->info('Receipt - jsonSerialize | hasMargin:'. 
            $this->hasMargin() . ' | hasDiscount:'. $this->hasDiscount());

            Log::channel('custom')->info('Receipt - jsonSerialize | Discount Labels: ' . $this->getDiscountLabels());

            return [
            'route' => is_null($this->order)
                ? route('order.create')
                : route('order.update', ['order' => $this->order]),
            'items' => $this->getDisplayableProducts(),
            'detail' => $this->getDisplayableDetail(),
            'total' => $this->getDisplayableTotal(),
            'discountedTotal' => $this->getDisplayableDiscountedTotal(),
            'discountValue' => $this->getDisplayableDiscountValue(),
            'hasMargin' => $this->hasMargin(),
            'hasDiscount' => $this->hasDiscount(),
            'discountLabels' => $this->getDiscountLabels(),
            ];
        } catch (\Throwable $e) {
            Log::error('Error serializing Receipt: ' . $e->getMessage(), ['exception' => $e]);

            return [
            'route' => null,
            'items' => [],
            'detail' => [],
            'total' => '0.00',
            ];
        }

    }
}
