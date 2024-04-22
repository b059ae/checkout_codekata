<?php

namespace App\Checkout\DiscountStrategies;

interface DiscountStrategy
{
    public function calculateDiscount(int $quantity, float $unitPrice): float;
}