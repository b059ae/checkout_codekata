<?php

namespace Unit\Checkout\Builders;

use App\Checkout\Builders\PricingRulesMapBuilder;
use App\Checkout\DTO\PricingRule;
use PHPUnit\Framework\TestCase;

class PricingRulesMapBuilderTest extends TestCase
{
    public function testBuild(): void
    {
        $pricingRules = [
            new PricingRule('A', 50),
            new PricingRule('B', 30),
            new PricingRule('C', 20),
            new PricingRule('D', 15),
        ];
        $builder = new PricingRulesMapBuilder($pricingRules);

        $map = $builder->build();

        $this->assertCount(4, $map);
        $this->assertArrayHasKey('A', $map);
        $this->assertArrayHasKey('B', $map);
        $this->assertArrayHasKey('C', $map);
        $this->assertArrayHasKey('D', $map);
    }
}
