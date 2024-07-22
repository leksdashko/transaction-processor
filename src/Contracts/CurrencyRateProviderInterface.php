<?php

namespace App\Contracts;

interface CurrencyRateProviderInterface
{
  public function getExchangeRates(): array;
}
