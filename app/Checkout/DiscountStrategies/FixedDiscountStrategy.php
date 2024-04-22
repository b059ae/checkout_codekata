<?php

namespace App\Checkout\DiscountStrategies;

readonly class FixedDiscountStrategy implements DiscountStrategy
{
    public function __construct(private int $qty, private float $discountPrice)
    {
    }

    public function calculateDiscount(int $quantity, float $unitPrice): float {
        if ($quantity < $this->qty) {
            return $quantity * $unitPrice;
        }

        return (int)($quantity / $this->qty) * $this->discountPrice + ($quantity % $this->qty) * $unitPrice;
    }
}