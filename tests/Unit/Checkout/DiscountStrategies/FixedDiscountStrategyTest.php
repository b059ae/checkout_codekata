<?php

namespace Unit\Checkout\DiscountStrategies;

use App\Checkout\DiscountStrategies\FixedDiscountStrategy;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class FixedDiscountStrategyTest extends TestCase
{
    #[DataProvider('discountProvider')]
    public function testCalculateDiscount(int $discountQty, float $discountPrice, int $unitQty, float $unitPrice, float $total): void
    {
        $strategy = new FixedDiscountStrategy($discountQty, $discountPrice);

        $actual = $strategy->calculateDiscount($unitQty, $unitPrice);

        $this->assertEquals($total, $actual);
    }

    /**
     * @return array{array{0: int, 1: float, 2: int, 3: float, 4: float}}
     */
    public static function discountProvider(): array
    {
        return [
            [2, 90, 1, 50, 50],
            [2, 90, 2, 50, 90],
            [2, 90, 3, 50, 140],
            [2, 90, 4, 50, 180],
        ];
    }
}
