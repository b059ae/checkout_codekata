<?php

namespace Checkout\Converters;

use App\Checkout\Converters\JsonToPricingRulesConverter;
use App\Checkout\DiscountStrategies\FixedDiscountStrategy;
use App\Checkout\DiscountStrategies\FreeItemDiscountStrategy;
use App\Checkout\DiscountStrategies\PercentageDiscountStrategy;
use PHPUnit\Framework\TestCase;

class JsonToPricingRulesConverterTest extends TestCase
{
    public function testConvert(): void
    {
        $json = '[
            {
                "item": "A",
                "price": 50,
                "discounts": [
                    {"type": "fixed", "quantity": 2, "price": 90},
                    {"type": "fixed", "quantity": 3, "price": 130},
                    {"type": "percentage", "percentage": 5}
                ]
            },
            {
                "item": "B",
                "price": 30,
                "discounts": [
                    {"type": "fixed", "quantity": 2, "price": 45}
                ]
            },
            {
                "item": "C",
                "price": 20,
                "discounts": [
                    {"type": "fixed", "quantity": 2, "price": 25},
                    {"type": "free_item", "quantity": 3}
                ]
            },
            {
                "item": "D",
                "price": 15
            }
        ]';
        $converter = new JsonToPricingRulesConverter();

        $rules = $converter->convert($json);

        $this->assertCount(4, $rules);
        $this->assertEquals('A', $rules[0]->item);
        $this->assertEquals(50, $rules[0]->price);
        $this->assertCount(3, $rules[0]->discounts);
        $this->assertInstanceOf(FixedDiscountStrategy::class, $rules[0]->discounts[0]);
        $this->assertInstanceOf(FixedDiscountStrategy::class, $rules[0]->discounts[1]);
        $this->assertInstanceOf(PercentageDiscountStrategy::class, $rules[0]->discounts[2]);
        $this->assertEquals('B', $rules[1]->item);
        $this->assertEquals(30, $rules[1]->price);
        $this->assertCount(1, $rules[1]->discounts);
        $this->assertInstanceOf(FixedDiscountStrategy::class, $rules[1]->discounts[0]);
        $this->assertEquals('C', $rules[2]->item);
        $this->assertEquals(20, $rules[2]->price);
        $this->assertCount(2, $rules[2]->discounts);
        $this->assertInstanceOf(FixedDiscountStrategy::class, $rules[2]->discounts[0]);
        $this->assertInstanceOf(FreeItemDiscountStrategy::class, $rules[2]->discounts[1]);
        $this->assertEquals('D', $rules[3]->item);
        $this->assertEquals(15, $rules[3]->price);
        $this->assertCount(0, $rules[3]->discounts);
    }
}
