<?php

namespace App\Contracts;

interface BinProviderInterface
{
  public function getBinData(string $bin): array;
}
