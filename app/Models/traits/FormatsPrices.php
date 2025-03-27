<?php

namespace App\Models\Traits;

use Brick\Math\RoundingMode;
use Brick\Money\Context\DefaultContext;
use Brick\Money\Money;
use NumberFormatter;

trait FormatsPrices
{
    public function formatPrice(Money $price): string
    {
        $formatter = new NumberFormatter('fr', NumberFormatter::CURRENCY);

        return $formatter->formatCurrency(
            $price->to(new DefaultContext, RoundingMode::HALF_UP)->getAmount()->toFloat(),
            'EUR'
        );
    }
}
