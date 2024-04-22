<?php

namespace App\Checkout\DiscountStrategies;

readonly class PercentageDiscountStrategy implements DiscountStrategy
{
    public function __construct(private float $discountPercentage)
    {
    }

    public function calculateDiscount(int $quantity, float $unitPrice): float
    {
        return ($quantity * $unitPrice) * (100 - $this->discountPercentage) / 100;
    }
}
