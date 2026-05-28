<?php

namespace Database\Seeders;

use App\Models\Material;
use App\Models\MaterialCategory;
use App\Models\PriceTable;
use App\Models\Product;
use App\Models\ProductOption;
use Illuminate\Database\Seeder;

class ProductCatalogSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $fabricCategory = MaterialCategory::query()->create([
            'name' => 'Fabric',
            'slug' => 'fabric',
            'description' => 'Core blind fabrics grouped for pricing and builder selection.',
            'sort_order' => 1,
        ]);

        $blockoutFabric = Material::query()->create([
            'material_category_id' => $fabricCategory->id,
            'name' => 'Blockout Linen',
            'slug' => 'blockout-linen',
            'code' => 'FAB-BLK-LIN',
            'fabric_group' => 'A',
            'description' => 'A durable blockout fabric for light control.',
        ]);

        $sunscreenFabric = Material::query()->create([
            'material_category_id' => $fabricCategory->id,
            'name' => 'Sunscreen Weave',
            'slug' => 'sunscreen-weave',
            'code' => 'FAB-SUN-WEA',
            'fabric_group' => 'B',
            'description' => 'A textured sunscreen fabric for filtered daylight.',
        ]);

        $products = [
            [
                'name' => 'Roller Blinds',
                'slug' => 'roller-blinds',
                'product_type' => 'roller',
                'description' => 'A versatile blind suited for modern living spaces.',
                'sort_order' => 1,
            ],
            [
                'name' => 'Venetian Blinds',
                'slug' => 'venetian-blinds',
                'product_type' => 'venetian',
                'description' => 'A classic slat-based blind for precise light control.',
                'sort_order' => 2,
            ],
            [
                'name' => 'Vertical Blinds',
                'slug' => 'vertical-blinds',
                'product_type' => 'vertical',
                'description' => 'A practical large-opening solution for sliding doors.',
                'sort_order' => 3,
            ],
            [
                'name' => 'Roman Blinds',
                'slug' => 'roman-blinds',
                'product_type' => 'roman',
                'description' => 'A soft folded blind that adds warmth to interiors.',
                'sort_order' => 4,
            ],
        ];

        foreach ($products as $productData) {
            $product = Product::query()->create([
                ...$productData,
                'builder_steps' => [
                    ['key' => 'dimensions', 'label' => 'Dimensions'],
                    ['key' => 'materials', 'label' => 'Materials'],
                    ['key' => 'review', 'label' => 'Review'],
                ],
                'builder_fields' => [
                    ['key' => 'width', 'label' => 'Width (mm)', 'type' => 'number'],
                    ['key' => 'drop', 'label' => 'Drop (mm)', 'type' => 'number'],
                ],
                'builder_options' => [
                    ['key' => 'fabric', 'label' => 'Fabric'],
                    ['key' => 'control_side', 'label' => 'Control Side'],
                ],
            ]);

            $fabricOption = ProductOption::query()->create([
                'product_id' => $product->id,
                'name' => 'Fabric',
                'code' => 'fabric',
                'input_type' => 'select',
                'is_required' => true,
                'sort_order' => 1,
                'validation_rules' => ['required'],
            ]);

            $fabricOption->values()->createMany([
                [
                    'material_id' => $blockoutFabric->id,
                    'label' => $blockoutFabric->name,
                    'value' => $blockoutFabric->slug,
                    'price_adjustment' => 0,
                    'sort_order' => 1,
                ],
                [
                    'material_id' => $sunscreenFabric->id,
                    'label' => $sunscreenFabric->name,
                    'value' => $sunscreenFabric->slug,
                    'price_adjustment' => 25,
                    'sort_order' => 2,
                ],
            ]);

            ProductOption::query()->create([
                'product_id' => $product->id,
                'name' => 'Control Side',
                'code' => 'control_side',
                'input_type' => 'select',
                'is_required' => true,
                'sort_order' => 2,
                'validation_rules' => ['required'],
            ])->values()->createMany([
                [
                    'label' => 'Left',
                    'value' => 'left',
                    'sort_order' => 1,
                ],
                [
                    'label' => 'Right',
                    'value' => 'right',
                    'sort_order' => 2,
                ],
            ]);

            PriceTable::query()->create([
                'product_id' => $product->id,
                'name' => "{$product->name} Base Pricing",
                'code' => 'base',
                'table_type' => 'base',
                'rules' => [
                    'base_price' => 150,
                    'fabric_groups' => [
                        'A' => 0,
                        'B' => 25,
                    ],
                ],
            ]);
        }
    }
}
