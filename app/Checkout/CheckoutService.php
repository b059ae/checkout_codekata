<?php

namespace App\Checkout;

use App\Checkout\Builders\PricingRulesMapBuilder;
use App\Checkout\DiscountStrategies\DiscountStrategy;
use App\Checkout\DTO\PricingRule;
use App\Checkout\Validators\PriceValidator;

class CheckoutService
{
    /**
     * @var array<string, int>
     */
    private array $cart = [];
    /**
     * @var array<string, PricingRule>
     */
    private array $pricingRulesMap = [];

    public function __construct(
        private readonly PricingRulesMapBuilder $pricingRulesMapBuilder,
        private readonly PriceValidator $priceValidator,
    ) {
    }

    /**
     * @param PricingRule[] $pricingRules
     */
    public function setPriceRules(array $pricingRules): self
    {
        $this->pricingRulesMap = $this->pricingRulesMapBuilder->build($pricingRules);

        return $this;
    }

    public function scan(string $item): self
    {
        if (!isset($this->cart[$item])) {
            $this->cart[$item] = 0;
        }
        ++$this->cart[$item];

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
        $this->priceValidator->validate($unitPrice);
        $defaultPrice = $unitPrice * $quantity;

        if ($discounts = $this->getItemDiscounts($item)) {
            return $this->applyDiscount($defaultPrice, $quantity, $unitPrice, $discounts);
        }

        return $defaultPrice;
    }

    /**
     * @return DiscountStrategy[]
     */
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
