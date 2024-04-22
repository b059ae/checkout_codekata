<?php

namespace Checkout\DiscountStrategies;

use App\Checkout\DiscountStrategies\PercentageDiscountStrategy;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class PercentageDiscountStrategyTest extends TestCase
{
    #[DataProvider('discountProvider')]
    public function testCalculateDiscount(float $percentage, int $unitQty, float $unitPrice, float $total): void
    {
        $strategy = new PercentageDiscountStrategy($percentage);

        $actual = $strategy->calculateDiscount($unitQty, $unitPrice);

        $this->assertEquals($total, $actual);
    }

    /**
     * @return array{array{0: float, 1: int, 2: float, 3: float}}
     */
    public static function discountProvider(): array
    {
        return [
            [50, 1, 50, 25],
            [50, 2, 50, 50],
            [50, 3, 50, 75],
            [50, 4, 50, 100],
        ];
    }
}
