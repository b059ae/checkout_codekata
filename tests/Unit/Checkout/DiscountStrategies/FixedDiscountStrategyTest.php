<?php

namespace Unit\Checkout\DiscountStrategies;

use App\Checkout\DiscountStrategies\FixedDiscountStrategy;
use PHPUnit\Framework\TestCase;

class FixedDiscountStrategyTest extends TestCase
{
    public function testCalculateDiscount(): void
    {
        $strategy = new FixedDiscountStrategy(2, 90);

        $this->assertEquals(50, $strategy->calculateDiscount(1, 50));
        $this->assertEquals(90, $strategy->calculateDiscount(2, 50));
        $this->assertEquals(140, $strategy->calculateDiscount(3, 50));
        $this->assertEquals(180, $strategy->calculateDiscount(4, 50));
    }
}
