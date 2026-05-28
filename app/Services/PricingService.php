<?php

namespace App\Services;

class PricingService
{
    /**
     * @param  array<int, array{subtotal: float|int}>  $lineItems
     * @return array<string, float>
     */
    public function calculate(
        array $lineItems,
        float $discountAmount = 0.0,
        float $installationCost = 0.0,
        float $gstRate = 0.1,
    ): array {
        $basePrice = array_reduce(
            $lineItems,
            fn (float $carry, array $lineItem): float => $carry + (float) ($lineItem['subtotal'] ?? 0),
            0.0,
        );

        $subtotal = max($basePrice + $installationCost - $discountAmount, 0.0);
        $gst = round($subtotal * $gstRate, 2);
        $total = round($subtotal + $gst, 2);

        return [
            'base_price' => round($basePrice, 2),
            'discount_amount' => round($discountAmount, 2),
            'installation_cost' => round($installationCost, 2),
            'subtotal' => round($subtotal, 2),
            'gst' => $gst,
            'total' => $total,
        ];
    }
}
