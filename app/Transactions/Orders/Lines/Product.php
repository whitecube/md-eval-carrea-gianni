<?php

namespace App\Transactions\Orders\Lines;

use App\Models\OrderProduct;
use App\Transactions\Orders\ReceiptLine;
use Brick\Money\Money;

class Product implements ReceiptLine
{
    protected OrderProduct $item;

    public function __construct(OrderProduct $item)
    {
        $this->item = $item;
    }

    public function isDisplayable(): bool
    {
        return $this->item->quantity > 0;
    }

    public function isDeletable(): bool
    {
        return true;
    }

    public function getType(): string
    {
        return 'product';
    }

    public function getLabel(): ?string
    {
        return $this->item->product->name;
    }

    public function getQuantity(): ?int
    {
        return $this->item->quantity;
    }

    public function getPrice(): Money
    {
        return $this->item->price_final;
    }

    public function getDisplayablePrice(): ?string
    {
        return $this->item->formatPrice($this->getPrice());
    }

    public function getProductAttributes(): array
    {
        return [
            'line' => $this->item->id,
            'product' => $this->item->product->id,
        ];
    }
}
