<?php

declare(strict_types=1);

namespace CKPL\Pay\Cryptography\BigInteger\Calculator;

/**
 * Interface CalculatorInterface.
 *
 * @package CKPL\Pay\Cryptography\BigInteger\Calculator
 */
interface CalculatorInterface
{
    /**
     * @return array
     */
    public function getCalculated(): array;
}
