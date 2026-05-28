<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductOptionValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_option_id',
        'material_id',
        'label',
        'value',
        'price_adjustment',
        'metadata',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
            'is_active' => 'boolean',
            'price_adjustment' => 'decimal:2',
        ];
    }

    public function option(): BelongsTo
    {
        return $this->belongsTo(ProductOption::class, 'product_option_id');
    }

    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class);
    }
}
