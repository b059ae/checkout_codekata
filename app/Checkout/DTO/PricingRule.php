<?php

namespace App\Checkout\DTO;

use App\Checkout\DiscountStrategies\DiscountStrategy;

readonly class PricingRule
{
    /**
     * @param DiscountStrategy[] $discounts
     */
    public function __construct(public string $item, public int $price, public array $discounts = [])
    {
    }
}
