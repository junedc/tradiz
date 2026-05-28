<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Material extends Model
{
    use HasFactory;

    protected $fillable = [
        'material_category_id',
        'name',
        'slug',
        'code',
        'fabric_group',
        'description',
        'is_active',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(MaterialCategory::class, 'material_category_id');
    }

    public function productOptionValues(): HasMany
    {
        return $this->hasMany(ProductOptionValue::class);
    }
}
