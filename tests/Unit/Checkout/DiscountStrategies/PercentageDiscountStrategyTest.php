<?php

namespace Unit\Checkout\DiscountStrategies;

use App\Checkout\DiscountStrategies\PercentageDiscountStrategy;
use PHPUnit\Framework\TestCase;

class PercentageDiscountStrategyTest extends TestCase
{
    public function testCalculateDiscount(): void
    {
        $strategy = new PercentageDiscountStrategy(50);

        $this->assertEquals(25, $strategy->calculateDiscount(1, 50));
        $this->assertEquals(50, $strategy->calculateDiscount(2, 50));
        $this->assertEquals(75, $strategy->calculateDiscount(3, 50));
        $this->assertEquals(100, $strategy->calculateDiscount(4, 50));
    }
}
