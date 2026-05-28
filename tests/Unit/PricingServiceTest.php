<?php

namespace Tests\Unit;

use App\Services\PricingService;
use PHPUnit\Framework\TestCase;

class PricingServiceTest extends TestCase
{
    public function test_it_calculates_a_pricing_breakdown(): void
    {
        $service = new PricingService();

        $result = $service->calculate(
            lineItems: [
                ['subtotal' => 150],
                ['subtotal' => 225],
            ],
            discountAmount: 25,
            installationCost: 40,
        );

        $this->assertSame([
            'base_price' => 375.0,
            'discount_amount' => 25.0,
            'installation_cost' => 40.0,
            'subtotal' => 390.0,
            'gst' => 39.0,
            'total' => 429.0,
        ], $result);
    }
}
