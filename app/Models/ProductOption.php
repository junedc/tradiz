<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'name',
        'code',
        'input_type',
        'is_required',
        'sort_order',
        'validation_rules',
    ];

    protected function casts(): array
    {
        return [
            'is_required' => 'boolean',
            'validation_rules' => 'array',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function values(): HasMany
    {
        return $this->hasMany(ProductOptionValue::class);
    }
}
