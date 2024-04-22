<?php

namespace App\Checkout\DiscountStrategies;

use App\Checkout\DTO\DiscountRule;

interface DiscountStrategy
{
    public function calculateDiscount(int $quantity, float $unitPrice): float;
}