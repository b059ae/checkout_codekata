<?php

namespace App\Checkout\Builders;

use App\Checkout\DTO\PricingRule;

class PricingRulesMapBuilder
{
    /**
     * @param PricingRule[] $pricingRules
     *
     * @return array<string, PricingRule>
     */
    public function build(array $pricingRules): array
    {
        $map = [];
        foreach ($pricingRules as $rule) {
            $map[$rule->item] = $rule;
        }

        return $map;
    }
}
