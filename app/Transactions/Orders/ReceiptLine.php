<?php

namespace App\Transactions\Orders;

use Brick\Money\Money;

interface ReceiptLine
{
    public function isDisplayable(): bool;

    public function isDeletable(): bool;

    public function getType(): string;

    public function getLabel(): ?string;

    public function getQuantity(): ?int;

    public function getPrice(): Money;

    public function getDisplayablePrice(): ?string;

    public function getProductAttributes(): array;

    public function getDiscountedPrice(): Money;

    public function getDiscountValue(): ?Money;

    public function hasMargin(): bool;

    public function getDiscountLabel(): ?string;
}
