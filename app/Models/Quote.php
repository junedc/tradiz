<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quote extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'quote_number',
        'customer_name',
        'customer_email',
        'status',
        'subtotal',
        'discount_amount',
        'installation_cost',
        'gst_amount',
        'total_amount',
        'configuration_snapshot',
    ];

    protected function casts(): array
    {
        return [
            'configuration_snapshot' => 'array',
            'subtotal' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'installation_cost' => 'decimal:2',
            'gst_amount' => 'decimal:2',
            'total_amount' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(QuoteItem::class);
    }
}
