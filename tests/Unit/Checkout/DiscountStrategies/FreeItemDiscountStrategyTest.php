<?php

namespace Unit\Checkout\DiscountStrategies;

use App\Checkout\DiscountStrategies\FreeItemDiscountStrategy;
use PHPUnit\Framework\TestCase;

class FreeItemDiscountStrategyTest extends TestCase
{
    public function testCalculateDiscount(): void
    {
        $strategy = new FreeItemDiscountStrategy(3);

        $this->assertEquals(50, $strategy->calculateDiscount(1, 50));
        $this->assertEquals(100, $strategy->calculateDiscount(2, 50));
        $this->assertEquals(100, $strategy->calculateDiscount(3, 50));
        $this->assertEquals(150, $strategy->calculateDiscount(4, 50));
    }
}
