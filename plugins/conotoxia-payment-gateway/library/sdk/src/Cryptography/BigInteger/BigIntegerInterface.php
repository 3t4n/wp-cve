<?php

declare(strict_types=1);

namespace CKPL\Pay\Cryptography\BigInteger;

/**
 * Interface BigIntegerInterface.
 *
 * @package CKPL\Pay\Cryptography\BigInteger
 */
interface BigIntegerInterface
{
    /**
     * @type int
     */
    const BASE_256 = 256;

    /**
     * @type int
     */
    const BASE_10 = 10;

    /**
     * @return array
     */
    public function getValue(): array;

    /**
     * @param array $value
     *
     * @return BigIntegerInterface
     */
    public function setValue(array $value): BigIntegerInterface;

    /**
     * @param bool $moreThanOneCompliment
     *
     * @return string
     */
    public function toBytes(bool $moreThanOneCompliment = false): string;

    /**
     * @param BigIntegerInterface $component
     *
     * @return BigIntegerInterface
     */
    public function add(BigIntegerInterface $component): BigIntegerInterface;

    /**
     * @param BigIntegerInterface $bigInteger
     *
     * @return int
     */
    public function compare(BigIntegerInterface $bigInteger): int;

    /**
     * @param BigIntegerInterface $multiplier
     *
     * @return BigIntegerInterface
     */
    public function multiply(BigIntegerInterface $multiplier): BigIntegerInterface;
}
