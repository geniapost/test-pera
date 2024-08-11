# Currency Converter Application

This is a test task.

## Overview

This PHP application processes financial transactions by converting different currencies to EUR and calculating commissions based on whether the transaction's BIN (Bank Identification Number) originates from an EU country.

## Prerequisites

- PHP 8.2 or higher
- Composer

## Installation

To install the required dependencies, run the following command:

```bash
composer install
```

## Usage

To execute the code and process transactions from a file, use the following command:

```bash 
php app.php file.txt
```

Replace file.txt with the path to your input file containing transaction data.

## Testing

To run the unit tests for this application, use the following command:

```bash 
vendor/bin/phpunit
```