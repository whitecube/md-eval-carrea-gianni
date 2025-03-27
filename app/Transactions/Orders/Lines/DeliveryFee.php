<?php

namespace App\Transactions\Orders\Lines;

use App\Models\Traits\FormatsPrices;
use App\Transactions\Orders\ReceiptLine;
use Brick\Money\Money;

class DeliveryFee implements ReceiptLine
{
    use FormatsPrices;

    public function isDisplayable(): bool
    {
        return true;
    }

    public function isDeletable(): bool
    {
        return false;
    }

    public function getType(): string
    {
        return 'detail';
    }

    public function getLabel(): ?string
    {
        return 'Livraison';
    }

    public function getQuantity(): ?int
    {
        return null;
    }

    public function getPrice(): Money
    {
        return Money::of(10, 'EUR');
    }

    public function getDisplayablePrice(): ?string
    {
        return $this->formatPrice($this->getPrice());
    }

    public function getProductAttributes(): array
    {
        return [];
    }
}
