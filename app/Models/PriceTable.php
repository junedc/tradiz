<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PriceTable extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'name',
        'code',
        'table_type',
        'rules',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'rules' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
