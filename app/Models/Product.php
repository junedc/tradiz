<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'product_type',
        'description',
        'builder_steps',
        'builder_fields',
        'builder_options',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'builder_steps' => 'array',
            'builder_fields' => 'array',
            'builder_options' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function options(): HasMany
    {
        return $this->hasMany(ProductOption::class);
    }

    public function quoteItems(): HasMany
    {
        return $this->hasMany(QuoteItem::class);
    }

    public function priceTables(): HasMany
    {
        return $this->hasMany(PriceTable::class);
    }
}
