<?php

require __DIR__.'/vendor/autoload.php';

use App\Checkout\Builders\PricingRulesMapBuilder;
use App\Checkout\CheckoutService;
use App\Checkout\Console\CheckoutCommand;
use App\Checkout\Converters\JsonToPricingRulesConverter;
use App\Checkout\Validators\PriceValidator;
use Symfony\Component\Console\Application;

$application = new Application();

$application->add(new CheckoutCommand(
    new CheckoutService(new PricingRulesMapBuilder(), new PriceValidator()),
    new JsonToPricingRulesConverter(),
));

$application->run();
