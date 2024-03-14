<?php

declare(strict_types=1);

namespace CKPL\Pay\Definition\Payment\Builder;

use CKPL\Pay\Definition\Amount\AmountInterface;
use CKPL\Pay\Exception\Definition\AmountException;

/**
 * Interface AmountBuilderInterface.
 *
 * @package CKPL\Pay\Definition\Payment\Builder
 */
interface AmountBuilderInterface
{
    /**
     * Currency code in accordance with ISO 4217.
     *
     * This value is required!
     *
     * @param string $currency
     *
     * @return AmountBuilderInterface
     */
    public function setCurrency(string $currency): AmountBuilderInterface;

    /**
     * Amount. Max. 21 characters with support for 4 places after
     * the decimal separator (the dot is used as the decimal separator).
     *
     * This value is required!
     *
     * @param string $value
     *
     * @return AmountBuilderInterface
     */
    public function setValue(string $value): AmountBuilderInterface;

    /**
     * Returns Amount definition.
     *
     * @throws AmountException if one of required parameters is missing
     *
     * @return AmountInterface
     */
    public function getAmount(): AmountInterface;
}
