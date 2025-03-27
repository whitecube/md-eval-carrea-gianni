<?php

namespace App\Models;

use App\Casts\Money;
use App\Models\Traits\FormatsPrices;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

class OrderProduct extends Model implements Sortable
{
    use FormatsPrices, SortableTrait;

    public array $sortable = [
        'order_column_name' => 'order',
        'sort_when_creating' => true,
    ];

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'price_unit' => Money::class,
            'price_final' => Money::class,
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
