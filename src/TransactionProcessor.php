<?php

namespace App;

use App\Exceptions\TransactionProcessError;

class TransactionProcessor
{
    private static array $transactionRequiredFields = [
        'bin',
        'amount',
        'currency',
    ];

    private CurrencyConverter $converter;
    private BinChecker $binChecker;

    const EU_COMMISSION = 0.01;
    const NON_EU_COMMISSION = 0.02;

    public function __construct(CurrencyConverter $converter, BinChecker $binChecker)
    {
        $this->converter = $converter;
        $this->binChecker = $binChecker;
    }

    private function formatTransactions(string $rawTransactions): array
    {
        $result = [];
        $rawTransactions = file_get_contents($rawTransactions);

        foreach (explode(PHP_EOL, $rawTransactions) as $row) {
            if (empty($row)) {
                continue;
            }

            $result[] = json_decode($row, true);
        }

        return $result;
    }

    public function getCommissions(string $rawTransactions): void
    {
        foreach (self::formatTransactions($rawTransactions) as $transaction) {
            try {
                if (count(array_diff(static::$transactionRequiredFields, array_keys($transaction))) > 0) {
                    throw new TransactionProcessError('Transaction required fields missing');
                }

                $euroAmount = $this->converter->convertToEu($transaction['amount'], $transaction['currency']);
                $commissionRate = $this->binChecker::isEu($transaction['bin']) ? self::EU_COMMISSION : self::NON_EU_COMMISSION;
                echo ceil ($euroAmount * $commissionRate * 100) / 100 . PHP_EOL;
            } catch (TransactionProcessError $exception) {
                echo $exception->getMessage() . ' for row ' . json_encode($transaction) . PHP_EOL;
            } catch (\Throwable $exception) {
                echo $exception->getMessage();
                continue;
            }
        }
    }
}