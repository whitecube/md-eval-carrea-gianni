<?php

namespace App\Models;

use App\Casts\Money;
use App\Models\Traits\FormatsPrices;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Whitecube\Sluggable\HasSlug;

class Product extends Model
{
    use FormatsPrices, HasSlug, SoftDeletes;

    public string $sluggable = 'name';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'price_acquisition' => Money::class,
            'price_selling' => Money::class,
        ];
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'main_category_id');
    }
}
