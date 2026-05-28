<?php

namespace Tests\Feature;

use App\Models\Product;
use Database\Seeders\ProductCatalogSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductRelationshipsTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeded_products_include_configurable_options_and_values(): void
    {
        $this->seed(ProductCatalogSeeder::class);

        $product = Product::query()
            ->with('options.values.material')
            ->where('slug', 'roller-blinds')
            ->firstOrFail();

        $this->assertCount(2, $product->options);
        $fabricOption = $product->options->firstWhere('code', 'fabric');

        $this->assertNotNull($fabricOption);
        $this->assertCount(2, $fabricOption->values);
        $this->assertSame('Blockout Linen', $fabricOption->values->first()?->material?->name);
    }
}
