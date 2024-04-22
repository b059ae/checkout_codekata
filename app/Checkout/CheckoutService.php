<?php

namespace App\Checkout;

use App\Checkout\Builders\PricingRulesMapBuilder;
use App\Checkout\DiscountStrategies\DiscountStrategy;
use App\Checkout\DTO\PricingRule;
use App\Checkout\Validators\PriceValidator;

class CheckoutService
{
    private array $cart = [];
    private array $pricingRulesMap;

    /**
     * @param PricingRule[] $pricingRules
     */
    public function __construct(array $pricingRules)
    {
        $this->pricingRulesMap = (new PricingRulesMapBuilder($pricingRules))->build();
    }

    public function scan($item): self
    {
        if (!isset($this->cart[$item])) {
            $this->cart[$item] = 0;
        }
        $this->cart[$item]++;

        return $this;
    }

    public function total(): float
    {
        $totalPrice = 0;

        foreach ($this->cart as $item => $quantity) {
            $totalPrice += $this->calculateItemTotal($item, $quantity);
        }

        return $totalPrice;
    }

    private function calculateItemTotal(string $item, int $quantity): float
    {
        if (!isset($this->pricingRulesMap[$item])) {
            throw new \InvalidArgumentException("Item $item is not available in the pricing rules");
        }
        $unitPrice = $this->pricingRulesMap[$item]->price;
        (new PriceValidator())->validate($unitPrice, $quantity);
        $defaultPrice = $unitPrice * $quantity;

        if ($discounts = $this->getItemDiscounts($item)) {
            return $this->applyDiscount($defaultPrice, $quantity, $unitPrice, $discounts);
        }

        return $defaultPrice;
    }

    private function getItemDiscounts(string $item): array
    {
        return $this->pricingRulesMap[$item]->discounts ?? [];
    }

    /**
     * @param DiscountStrategy[] $discounts
     */
    private function applyDiscount(float $bestDiscount, int $quantity, float $unitPrice, array $discounts): float
    {
        foreach ($discounts as $discount) {
            $bestDiscount = min($bestDiscount, $discount->calculateDiscount($quantity, $unitPrice));
        }

        return $bestDiscount;
    }
}
