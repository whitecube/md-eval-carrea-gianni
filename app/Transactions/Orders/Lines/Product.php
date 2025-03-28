<?php

namespace App\Transactions\Orders\Lines;

use App\Models\OrderProduct;
use App\Services\PriceCalculator;
use App\Transactions\Clients\ClientType;
use App\Transactions\Orders\ReceiptLine;
use Brick\Money\Money;

class Product implements ReceiptLine
{
    protected OrderProduct $item;
    protected PriceCalculator $priceCalculator;
    protected ClientType $clientType;
    protected array $priceDetails;

    public function __construct(OrderProduct $item, ClientType $clientType)
    {
        $this->item = $item;
        $this->priceCalculator = new PriceCalculator();
        $this->clientType = $clientType;

        $productData = [
            'price_selling' => $this->item->product->price_selling,
            'price_acquisition' => $this->item->product->price_acquisition,
            'category' => $this->item->product->category,
            'price_final' => $this->item->price_final,
            'quantity' => $this->item->quantity,
        ];

        $this->priceDetails = $this->priceCalculator->calculateFinalPrice($productData, $this->clientType);
        
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
        return $this->priceDetails['basePrice'];
    }

    public function getDiscountedPrice(): Money
    {
        return $this->priceDetails['discountedPrice'];
    }

    public function getDiscountValue(): ?Money
    {
        return $this->priceDetails['discountValue'];
    }

    public function hasMargin(): bool
    {
        return $this->priceDetails['hasMargin'];
    }

    public function getDiscountLabel(): ?string
    {
        return $this->priceDetails['discountLabel'];
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
