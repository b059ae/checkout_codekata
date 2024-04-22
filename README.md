# Supermarket Checkout System
## Overview
This repository contains code for a supermarket checkout system that handles various pricing schemes such as:
- three for a dollar
- buy two, get one free
- 5% discount

## Objective

The objective of this project is to implement a flexible checkout system that calculates the total price of a number of items, accommodating different pricing rules.
It serves as a practice in decoupling, aiming to design a system where the checkout module doesn't have knowledge of specific items and their pricing strategies.

## Implementation Details

The checkout system accepts items identified by individual letters of the alphabet (A, B, C, etc.).
Items are priced individually, with some items having multi-priced offers.
Pricing rules are passed in as a set of parameters each time a checkout transaction is initiated, allowing for dynamic pricing changes.
The provided unit tests ensure the correctness of the implemented checkout system.
Current implementation is framework-agnostic, allowing for easy integration into any PHP project.

## Current and Future Improvements

The initial task involves implementing the simplest discount rule: "N items for M price". However, to demonstrate versatility, I've introduced more complex rules such as "buy N, get 1 free" and "% discount".
Additionally, I've enabled the system to accommodate multiple rules for a single item and determine the best discount rule for each item.
Currently, the checkout system selects only one discount rule per item. It would be beneficial to enable the system to apply multiple discount rules to one item. For instance, purchasing 5 items might trigger both "buy 2, get 1 free" and a 10% discount on the remaining items.
Furthermore, future enhancements could involve implementing rules like "buy 2 A items and get 1 C item for free". However, due to the complexity and time requirements, this is currently beyond the scope of the task.

## Getting Started

**Prerequisites**
PHP, Composer

**Install dependencies**
```
composer install 
```

## Testing
Run tests with PHPUnit.
```
./vendor/bin/phpunit tests
```

