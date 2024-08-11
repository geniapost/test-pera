<?php

namespace App;

use App\Exceptions\TransactionProcessError;

class BinChecker
{
    private static array $euCountries = [
        'AT', 'BE', 'BG', 'CY', 'CZ', 'DE', 'DK', 'EE', 'ES', 'FI', 'FR', 'GR', 'HR', 'HU', 'IE', 'IT',
        'LT', 'LU', 'LV', 'MT', 'NL', 'PO', 'PT', 'RO', 'SE', 'SI', 'SK'
    ];

    protected function getLookup(int $bin): string
    {
        try {
            return file_get_contents('https://lookup.binlist.net/' . $bin);
        } catch (\Exception $e) {
            throw new TransactionProcessError('Can not find lookup for BIN: ' . $bin);
        }
    }

    public function isEu(int $bin): bool
    {
        $binData = json_decode($this->getLookup($bin));
        return in_array($binData->country->alpha2, static::$euCountries);
    }
}