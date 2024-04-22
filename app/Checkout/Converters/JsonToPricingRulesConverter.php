<?php

namespace App\Checkout\Converters;

use App\Checkout\DiscountStrategies\DiscountStrategy;
use App\Checkout\DiscountStrategies\DiscountStrategyEnum;
use App\Checkout\DiscountStrategies\FixedDiscountStrategy;
use App\Checkout\DiscountStrategies\FreeItemDiscountStrategy;
use App\Checkout\DiscountStrategies\PercentageDiscountStrategy;
use App\Checkout\DTO\PricingRule;

class JsonToPricingRulesConverter
{
    /**
     * @return PricingRule[]
     */
    public function convert(string $json): array
    {
        $pricingRules = [];
        $data = json_decode($json, true);

        foreach ($data as $ruleData) {
            $item = $ruleData['item'];
            $price = $ruleData['price'];
            $discountStrategies = [];

            if (isset($ruleData['discounts'])) {
                foreach ($ruleData['discounts'] as $strategyData) {
                    $discountStrategies[] = $this->createDiscountStrategy($strategyData);
                }
            }

            $pricingRules[] = new PricingRule($item, $price, $discountStrategies);
        }

        return $pricingRules;
    }

    /**
     * @param array<string, mixed> $strategyData
     */
    private function createDiscountStrategy(array $strategyData): DiscountStrategy
    {
        return match ($strategyData['type']) {
            DiscountStrategyEnum::FIXED->value => new FixedDiscountStrategy($strategyData['quantity'], $strategyData['price']),
            DiscountStrategyEnum::PERCENTAGE->value => new PercentageDiscountStrategy($strategyData['percentage']),
            DiscountStrategyEnum::FREE_ITEM->value => new FreeItemDiscountStrategy($strategyData['quantity']),
            default => throw new \InvalidArgumentException(sprintf('Unknown discount strategy type: %s', $strategyData['type']))
        };
    }
}
