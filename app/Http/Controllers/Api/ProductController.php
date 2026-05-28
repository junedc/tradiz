<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\ProductResource;
use App\Services\ProductCatalogService;

class ProductController extends ApiController
{
    public function __construct(
        protected ProductCatalogService $productCatalogService,
    ) {
    }

    public function index()
    {
        $products = $this->productCatalogService->listActiveProducts();

        return $this->success(
            ProductResource::collection($products)->resolve(),
            'Products retrieved successfully.',
        );
    }
}
