<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'product_type' => $this->product_type,
            'description' => $this->description,
            'is_active' => $this->is_active,
            'sort_order' => $this->sort_order,
            'builder' => [
                'steps' => $this->builder_steps ?? [],
                'fields' => $this->builder_fields ?? [],
                'options' => $this->builder_options ?? [],
            ],
        ];
    }
}
