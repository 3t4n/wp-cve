<?php

declare(strict_types=1);

namespace CKPL\Pay\Cryptography\BigInteger\Value;

/**
 * Interface ValueInterface.
 *
 * @package CKPL\Pay\Cryptography\BigInteger\Value
 */
interface ValueInterface
{
    /**
     * @return mixed
     */
    public function getValue();

    /**
     * @param mixed $value
     *
     * @return void
     */
    public function setValue($value): void;
}
