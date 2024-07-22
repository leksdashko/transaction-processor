<?php

namespace App\Transaction;

class TransactionProcessor
{
    private $feeCalculator;

    public function __construct(TransactionFeeCalculator $feeCalculator)
    {
        $this->feeCalculator = $feeCalculator;
    }

    public function processFile(string $filename): void
    {
        $fileContent = file_get_contents($filename);
        if ($fileContent === FALSE) {
            throw new \Exception("Error reading file: $filename");
        }

        $rows = explode("\n", $fileContent);
        foreach ($rows as $row) {
            if (empty($row)) continue;

            $transaction = json_decode($row, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                echo "Invalid JSON in row: $row\n";
                continue;
            }

            try {
                $fee = $this->feeCalculator->calculateFee($transaction);
                echo $fee . "\n";
            } catch (\Exception $e) {
                echo 'Error: ' . $e->getMessage() . "\n";
            }
        }
    }
}
