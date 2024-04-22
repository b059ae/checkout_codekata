<?php

namespace App\Checkout\Validators;

class PriceValidator
{
    public function validate(float $unitPrice): bool
    {
        if ($unitPrice <= 0) {
            throw new \InvalidArgumentException('Unit price must be greater than 0');
        }

        return true;
    }
}