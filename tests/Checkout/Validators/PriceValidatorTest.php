<?php

namespace Checkout\Validators;

use App\Checkout\Validators\PriceValidator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class PriceValidatorTest extends TestCase
{
    public function testValidate(): void
    {
        $validator = new PriceValidator();

        $this->assertTrue($validator->validate(50));
    }

    #[DataProvider('invalidPriceProvider')]
    public function testValidateThrowsInvalidArgumentException(float $price): void
    {
        $this->expectException(\InvalidArgumentException::class);

        (new PriceValidator())->validate($price);
    }

    /**
     * @return array{array{0: float}}
     */
    public static function invalidPriceProvider(): array
    {
        return [
            [0],
            [-50],
        ];
    }
}
