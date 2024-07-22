<?php

use PHPUnit\Framework\TestCase;
use App\Transaction\TransactionFeeCalculator;
use App\Transaction\TransactionProcessor;
use App\Contracts\CurrencyRateProviderInterface;
use App\Contracts\BinProviderInterface;
use App\Transaction\EuChecker;

class TransactionProcessorTest extends TestCase
{
    private $processor;
    private $currencyRateProviderMock;
    private $binProviderMock;
    private $euChecker;

    protected function setUp(): void
    {
        $this->currencyRateProviderMock = $this->createMock(CurrencyRateProviderInterface::class);
        $this->binProviderMock = $this->createMock(BinProviderInterface::class);
        $this->euChecker = new EuChecker();

        $feeCalculator = new TransactionFeeCalculator($this->currencyRateProviderMock, $this->binProviderMock, $this->euChecker);
        $this->processor = new TransactionProcessor($feeCalculator);
    }

    public function testIsEu()
    {
        $this->assertTrue($this->euChecker->isEu('DE'));
        $this->assertFalse($this->euChecker->isEu('US'));
    }

    public function testProcessTransaction()
    {
        $transaction = [
            'bin' => '45717360',
            'amount' => '100.00',
            'currency' => 'USD'
        ];

        $binData = [
            'country' => ['alpha2' => 'DE']
        ];

        $exchangeRates = [
            'rates' => ['USD' => 1.1]
        ];

        $this->binProviderMock->expects($this->once())
            ->method('getBinData')
            ->with('45717360')
            ->willReturn($binData);

        $this->currencyRateProviderMock->expects($this->once())
            ->method('getExchangeRates')
            ->willReturn($exchangeRates);

        $feeCalculator = new TransactionFeeCalculator($this->currencyRateProviderMock, $this->binProviderMock, $this->euChecker);
        $processor = new TransactionProcessor($feeCalculator);

        $fee = $feeCalculator->calculateFee($transaction);
        $this->assertEquals(0.91, $fee);
    }

    public function testProcessTransactionNonEu()
    {
        $transaction = [
            'bin' => '45717360',
            'amount' => '100.00',
            'currency' => 'USD'
        ];

        $binData = [
            'country' => ['alpha2' => 'US']
        ];

        $exchangeRates = [
            'rates' => ['USD' => 1.1]
        ];

        $this->binProviderMock->expects($this->once())
            ->method('getBinData')
            ->with('45717360')
            ->willReturn($binData);

        $this->currencyRateProviderMock->expects($this->once())
            ->method('getExchangeRates')
            ->willReturn($exchangeRates);

        $feeCalculator = new TransactionFeeCalculator($this->currencyRateProviderMock, $this->binProviderMock, $this->euChecker);
        $processor = new TransactionProcessor($feeCalculator);

        $fee = $feeCalculator->calculateFee($transaction);
        $this->assertEquals(1.82, $fee);
    }
}
