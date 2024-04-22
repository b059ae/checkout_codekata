<?php

namespace App\Checkout\DiscountStrategies;

readonly class FreeItemDiscountStrategy implements DiscountStrategy
{
    public function __construct(private int $qty)
    {
    }

    public function calculateDiscount(int $quantity, float $unitPrice): float {
        $freeQty = (int)($quantity / $this->qty);

        return ($quantity - $freeQty) * $unitPrice;
    }
}