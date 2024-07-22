<?php

namespace App\Transaction;

use App\Contracts\CurrencyRateProviderInterface;
use App\Contracts\BinProviderInterface;

class TransactionFeeCalculator
{
    private $currencyRateProvider;
    private $binProvider;
    private $euChecker;

    public function __construct(
        CurrencyRateProviderInterface $currencyRateProvider,
        BinProviderInterface $binProvider,
        EuChecker $euChecker
    ) {
        $this->currencyRateProvider = $currencyRateProvider;
        $this->binProvider = $binProvider;
        $this->euChecker = $euChecker;
    }

    public function calculateFee(array $transaction): float
    {
        $bin = trim($transaction['bin'], '"');
        $amount = (float) trim($transaction['amount'], '"');
        $currency = trim($transaction['currency'], '"');
				
        $binData = $this->binProvider->getBinData($bin);
        if (empty($binData)) {
            throw new \Exception("Error fetching BIN data for BIN: $bin");
        }

        $countryCode = $binData['country']['alpha2'] ?? null;
        if ($countryCode === null) {
            throw new \Exception("Country code not found in BIN data for BIN: $bin");
        }

        $isEu = $this->euChecker->isEu($countryCode);

        $exchangeRates = $this->currencyRateProvider->getExchangeRates();
        $rate = $exchangeRates['rates'][$currency] ?? 0;

        if ($currency === 'EUR' || $rate == 0) {
            $amountFixed = $amount;
        } else {
            $amountFixed = $amount / $rate;
        }

        $fee = $amountFixed * ($isEu ? 0.01 : 0.02);
        return ceil($fee * 100) / 100;
    }
}
