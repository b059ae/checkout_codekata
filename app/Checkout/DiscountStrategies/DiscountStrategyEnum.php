<?php

namespace App\Checkout\DiscountStrategies;

enum DiscountStrategyEnum: string
{
    case FIXED = 'fixed';
    case PERCENTAGE = 'percentage';
    case FREE_ITEM = 'free_item';
}
