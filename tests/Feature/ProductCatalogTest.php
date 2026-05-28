<?php

namespace Tests\Feature;

use Database\Seeders\ProductCatalogSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductCatalogTest extends TestCase
{
    use RefreshDatabase;

    public function test_products_endpoint_returns_the_expected_response_envelope(): void
    {
        $this->seed(ProductCatalogSeeder::class);

        $response = $this->getJson('/api/products');

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('message', 'Products retrieved successfully.')
            ->assertJsonCount(4, 'data')
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'slug',
                        'product_type',
                        'description',
                        'is_active',
                        'sort_order',
                        'builder' => [
                            'steps',
                            'fields',
                            'options',
                        ],
                    ],
                ],
            ]);
    }

    public function test_spa_entry_page_is_rendered_from_the_root_route(): void
    {
        $response = $this->get('/');

        $response
            ->assertOk()
            ->assertSee('id="app"', false);
    }
}
