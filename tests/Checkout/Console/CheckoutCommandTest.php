<?php

namespace Checkout\Console;

use App\Checkout\CheckoutService;
use App\Checkout\Console\CheckoutCommand;
use App\Checkout\Converters\JsonToPricingRulesConverter;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

class CheckoutCommandTest extends TestCase
{
    private string $rulesFile = __DIR__.'/../../fixtures/pricing_rules.json';

    public function testCheckoutCommand(): void
    {
        $total = (float) rand(1, 100);
        $items = 'ABCD';
        $checkoutService = $this->createMock(CheckoutService::class);
        $checkoutService->expects($this->exactly(4))->method('scan');
        $checkoutService->expects($this->once())->method('total')->willReturn($total);
        $jsonToPricingRulesConverter = $this->createMock(JsonToPricingRulesConverter::class);
        $jsonToPricingRulesConverter->expects($this->once())->method('convert')->willReturn([]);
        $command = new CheckoutCommand($checkoutService, $jsonToPricingRulesConverter);
        $command->setApplication(new Application());

        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'pricing_rules' => $this->rulesFile,
            'items' => $items,
        ]);
        $output = $commandTester->getDisplay();

        $this->assertStringContainsString(sprintf('Total: %s', $total), $output);
        $this->assertEquals(Command::SUCCESS, $commandTester->getStatusCode());
    }

    public function testFileNotFound(): void
    {
        $checkoutService = $this->createMock(CheckoutService::class);
        $jsonToPricingRulesConverter = $this->createMock(JsonToPricingRulesConverter::class);
        $command = new CheckoutCommand($checkoutService, $jsonToPricingRulesConverter);
        $command->setApplication(new Application());

        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'pricing_rules' => 'non_existent_file.json',
            'items' => 'ABCD',
        ]);
        $output = $commandTester->getDisplay();

        $this->assertStringContainsString('not found', $output);
        $this->assertEquals(Command::FAILURE, $commandTester->getStatusCode());
    }

    public function testErrorInService(): void
    {
        $checkoutService = $this->createMock(CheckoutService::class);
        $checkoutService->method('scan')->willThrowException(new \Exception('Error in service'));
        $jsonToPricingRulesConverter = $this->createMock(JsonToPricingRulesConverter::class);
        $command = new CheckoutCommand($checkoutService, $jsonToPricingRulesConverter);
        $command->setApplication(new Application());

        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'pricing_rules' => $this->rulesFile,
            'items' => 'ABCD',
        ]);
        $output = $commandTester->getDisplay();

        $this->assertStringContainsString('An error occurred', $output);
        $this->assertEquals(Command::FAILURE, $commandTester->getStatusCode());
    }
}
