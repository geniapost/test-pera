<?php

namespace App;

use App\Exceptions\TransactionProcessError;

class CurrencyConverter
{
    protected array $rates;
    private string $apiKey;

    public function __construct()
    {
        $this->apiKey = $_ENV['EXCHANGE_RATES_API_KEY'];;
        $this->rates =  $this->formatRates();
    }

    protected function getRates(): string
    {
        try {
            return file_get_contents('https://api.exchangeratesapi.io/latest?access_key='.$this->apiKey);
        } catch (\Exception $e)
        {
            throw new TransactionProcessError('Can not get currency rates');
        }
    }

    protected function formatRates(): array
    {
        $rates = json_decode($this->getRates(), true);

        if (!$rates['success']) {
            throw new TransactionProcessError('Error in getting rate from API: ' . $rates['error']['info']);
        }

        if (!isset($rates['rates'])) {
            throw new TransactionProcessError('No rates object in API');
        }

        return $rates['rates'];
    }

    public function convertToEu(float $amount, string $currency): float
    {
        if (!isset($this->rates[$currency])) {
            throw new TransactionProcessError('No currency rate for currency ' . $currency);
        }

        if ($currency === 'EUR') {
            return $amount;
        }

        $rate = $this->rates[$currency];

        return $rate == 0 ? 0 : $amount / $rate;
    }

    public function setRates(array $rates): void
    {
        $this->rates = $rates;
    }
}