<?php

namespace Unit\Checkout\DiscountStrategies;

use App\Checkout\DiscountStrategies\FreeItemDiscountStrategy;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class FreeItemDiscountStrategyTest extends TestCase
{
    #[DataProvider('discountProvider')]
    public function testCalculateDiscount(int $discountQty, int $unitQty, float $unitPrice, float $total): void
    {
        $strategy = new FreeItemDiscountStrategy($discountQty);

        $actual = $strategy->calculateDiscount($unitQty, $unitPrice);

        $this->assertEquals($total, $actual);
    }

    /**
     * @return array{array{0: int, 1: int, 2: float, 3: float}}
     */
    public static function discountProvider(): array
    {
        return [
            [3, 1, 50, 50],
            [3, 2, 50, 100],
            [3, 3, 50, 100],
            [3, 4, 50, 150],
        ];
    }
}
