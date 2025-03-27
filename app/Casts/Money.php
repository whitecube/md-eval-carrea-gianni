<?php

namespace App\Casts;

use Brick\Math\RoundingMode;
use Brick\Money\Context\DefaultContext;
use Brick\Money\Money as MoneyObject;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class Money implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): MoneyObject
    {
        return MoneyObject::ofMinor($value, 'EUR');
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): int
    {
        if (is_a($value, MoneyObject::class)) {
            $value = $value->to(new DefaultContext, RoundingMode::HALF_UP)->getMinorAmount()->toInt();
        }

        if (! is_int($value)) {
            $value = 0;
        }

        return $value;
    }
}
