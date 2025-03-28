<?php

namespace App\Services;

use App\Transactions\Clients\ClientType;
use Brick\Money\Money;
use Illuminate\Support\Facades\Log;
use Brick\Math\RoundingMode;

class PriceCalculator
{
    public function calculateFinalPrice(array $product, ClientType $clientType): array
    {
        try {
            $priceSelling = $product['price_selling'];
            $priceAcquisition = $product['price_acquisition'];
            $category = $product['category']->slug;
            $quantity = $product['quantity'];
            //$priceFinal = $product['price_final']; //Rem: final price = price_selling * quantity

            $adjustedPriceAcquisition = $priceAcquisition->multipliedBy($quantity);
            $adjustedPriceSelling = $priceSelling->multipliedBy($quantity);
            $basePrice = $adjustedPriceSelling;

            // Log the product array
            Log::channel('custom')->info('Product data', [
                'product' => $product,
            ]);

            Log::channel('custom')->info('Client type details', [
                'clientTypeName' => $clientType->name,
                'clientTypeLabel' => $clientType->label ?? 'No label provided',
            ]);

            $margin = false;
            $discountLabel = null;
            $maxDiscount = Money::of(0, $adjustedPriceSelling->getCurrency());

            if (strtolower($clientType->name) == 'wholesaler') {
                // Wholesalers get a 30% margin on the acquisition price
                $margin = true;
                $basePrice = $adjustedPriceAcquisition->multipliedBy(1.3, RoundingMode::UP);
            }
            else // Apply category-specific discounts if not a wholesaler
            {
                if ($category == 'promotions') {
                    $maxDiscount = $adjustedPriceSelling->multipliedBy(0.15, RoundingMode::UP);
                    $discountLabel = '15% Promotions Discount';
                } else if (strtolower($clientType->name) == 'vip') {
                    Log::channel("custom")->info("VIP Discount applicable");
                    // VIP clients get a 10% discount on the selling price
                    $maxDiscount = $adjustedPriceSelling->multipliedBy(0.10, RoundingMode::UP);
                    $discountLabel = "10% VIP Discount";
                } else if ($category == 'surgeles') {
                    $maxDiscount = $adjustedPriceSelling->multipliedBy(0.05, RoundingMode::UP);
                    $discountLabel = '5% Frozen Products Discount';
                } 
            }
                
            // Log all discounts
            Log::channel('custom')->info('Best discounts: '. $maxDiscount . " | label: ".$discountLabel);
            
            $discountedPrice = $basePrice->minus($maxDiscount);

            $result = [
            'basePrice' => $basePrice,
            'discountedPrice' => $discountedPrice,
            'hasMargin' => $margin,
            'discountLabel' => $discountLabel,
            //'discountValue' => Money::of($maxDiscount, $adjustedPriceSelling->getCurrency())
            'discountValue' => $maxDiscount,
            ]; 

            Log::channel('custom')->info('Calculation results', [
                'result' => $result,
            ]);

            return $result;

        } catch (\Exception $e) {
            // Log the exception
            Log::channel('custom')->error('Error in PriceCalculator', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            ]);

            // Re-throw the exception to handle it further up the stack if needed
            throw $e;
        }
    }
}