<?php

namespace Unit\Checkout\Validators;

use App\Checkout\Validators\PriceValidator;
use PHPUnit\Framework\TestCase;

class PriceValidatorTest extends TestCase
{
    public function testValidate(): void
    {
        $validator = new PriceValidator();

        $this->assertTrue($validator->validate(50));
    }

    public function testValidateThrowsInvalidArgumentExceptionOnNegativeValue(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $validator = new PriceValidator();
        $validator->validate(-50);
    }

    public function testValidateThrowsInvalidArgumentExceptionOnZeroValue(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $validator = new PriceValidator();
        $validator->validate(0);
    }
}
