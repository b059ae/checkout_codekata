<?php

namespace App\Checkout\Console;

use App\Checkout\CheckoutService;
use App\Checkout\Converters\JsonToPricingRulesConverter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Exception\RuntimeException;

class CheckoutCommand extends Command
{
    public function __construct(
        private readonly CheckoutService $checkoutService,
        private readonly JsonToPricingRulesConverter $jsonToPricingRulesConverter,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('app:checkout')
            ->setDescription('Checkout command for the store')
            ->addArgument('pricing_rules', InputArgument::REQUIRED, 'JSON filepath of pricing rules')
            ->addArgument('items', InputArgument::REQUIRED, 'Items to scan');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $json = $this->openFile($input->getArgument('pricing_rules'));
            $rules = $this->jsonToPricingRulesConverter->convert($json);
            $this->checkoutService->setPriceRules($rules);
            foreach (str_split((string) $input->getArgument('items')) as $item) {
                $this->checkoutService->scan($item);
            }
            $output->writeln(sprintf('Total: %s', $this->checkoutService->total()));

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $output->writeln('An error occurred: '.$e->getMessage());

            return Command::FAILURE;
        }
    }

    private function openFile(string $pricingRulesFilePath): string
    {
        if (!file_exists($pricingRulesFilePath)) {
            throw new RuntimeException(sprintf('Error: Pricing rules file "%s" not found', $pricingRulesFilePath));
        }
        $json = file_get_contents($pricingRulesFilePath);
        if (false === $json) {
            throw new RuntimeException('Error: Failed to read pricing rules file');
        }

        return $json;
    }
}
