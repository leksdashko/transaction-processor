<?php

require 'vendor/autoload.php';

use App\Services\CurrencyRateProvider;
use App\Services\BinProvider;
use App\Transaction\EuChecker;
use App\Transaction\TransactionFeeCalculator;
use App\Transaction\TransactionProcessor;

try {
    if ($argc !== 2) {
        throw new Exception("Usage: php app.php <input_file>");
    }
    $inputFile = $argv[1];
    $currencyRateProvider = new CurrencyRateProvider('https://api.exchangeratesapi.io/latest');
    $binProvider = new BinProvider('https://lookup.binlist.net/');
    $euChecker = new EuChecker();
    $feeCalculator = new TransactionFeeCalculator($currencyRateProvider, $binProvider, $euChecker);
    $processor = new TransactionProcessor($feeCalculator);
    $processor->processFile($inputFile);
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage() . "\n";
    exit(1);
}
