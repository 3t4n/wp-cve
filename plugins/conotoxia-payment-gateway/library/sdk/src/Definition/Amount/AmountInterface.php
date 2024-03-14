<?php

declare(strict_types=1);

namespace CKPL\Pay\Definition\Amount;

/**
 * Interface AmountInterface.
 *
 * @package CKPL\Pay\Definition\Amount
 */
interface AmountInterface
{
    /**
     * @return string|null
     */
    public function getCurrency(): ?string;

    /**
     * @return string|null
     */
    public function getValue(): ?string;
}
