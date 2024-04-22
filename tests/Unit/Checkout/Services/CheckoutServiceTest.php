<?php

namespace Unit\Checkout\Services;

use App\Checkout\CheckoutService;
use App\Checkout\DiscountStrategies\FixedDiscountStrategy;
use App\Checkout\DiscountStrategies\FreeItemDiscountStrategy;
use App\Checkout\DiscountStrategies\PercentageDiscountStrategy;
use App\Checkout\DTO\PricingRule;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class CheckoutServiceTest extends TestCase
{
    #[DataProvider('totalProvider')]
    public function testTotal(string $items, float $total): void
    {
        $rules = [
            new PricingRule('A', 50, [
                new FixedDiscountStrategy(2, 90),
                new FixedDiscountStrategy(3, 130),
            ]),
            new PricingRule('B', 30, [
                new FixedDiscountStrategy(2, 45),
            ]),
            new PricingRule('C', 20, [
                new FixedDiscountStrategy(2, 30),
                new FreeItemDiscountStrategy(3),
            ]),
            new PricingRule('D', 15),
            new PricingRule('E', 100, [
                new FixedDiscountStrategy(3, 250),
                new PercentageDiscountStrategy(5),
            ]),
        ];
        $service = (new CheckoutService($rules));

        foreach (str_split($items) as $item) {
            $service->scan($item);
        }

        $this->assertEquals($total, $service->total());
    }

    public static function totalProvider(): array
    {
        return [
            ['', 0],
            ['A', 50],
            ['B', 30],
            ['C', 20],
            ['D', 15],
            ['E', 95], // 100-5%
            ['AB', 80],
            ['ECDBA', 210],
            ['AA', 90],
            ['AAA', 130],
            ['AAAA', 180], // 130(AAA) + 50(A)
            ['AAAAA', 230], // 130(AAA) + 50(A) + 50(A)
            ['AAAAAA', 260], // 130(AAA) + 130(AAA)
            ['BB', 45],
            ['BBB', 75], // 45(BB) + 30(B)
            ['CC', 30],
            ['CCC', 40],
            ['CCCC', 60], // 30 (CC) + 30 (CC)
            ['EE', 190],
            ['EEE', 250],
            ['EEEE', 350], // 250 (EEE) + 100(E)
            ['AAAB', 160], // 130(AAA) + 30(B)
            ['AAABB', 175], // 130(AAA) + 45(B)
            ['AAABBD', 190], // 130(AAA) + 45(BB) + 15(D)
            ['DABABA', 190], // 15(D) + 45(BB) + 130(AAA)
            ['EDEEDCBCAACBC', 475], // 250(EEE) + 30(D) + 60(CC) + 90(AAA) + 45(BB)
        ];
    }

    #[DataProvider('invalidArgumentExceptionProvider')]
    public function testInvalidArgumentException(array $rules, string $item): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $service = (new CheckoutService($rules));
        $service->scan($item)->total();
    }

    public static function invalidArgumentExceptionProvider(): array
    {
        return [
            [[], 'A'],
            [[new PricingRule('A', 0)], 'A'],
            [[new PricingRule('A', -50)], 'A'],
        ];
    }
}
