<?php

namespace App\Checkout\Builders;

use App\Checkout\DTO\PricingRule;

readonly class PricingRulesMapBuilder
{
    /**
     * @param PricingRule[] $pricingRules
     */
    public function __construct(private array $pricingRules)
    {
    }

    /**
     * @return array<string, PricingRule>
     */
    public function build(): array
    {
        $map = [];
        foreach ($this->pricingRules as $rule) {
            $map[$rule->item] = $rule;
        }

        return $map;
    }
}
