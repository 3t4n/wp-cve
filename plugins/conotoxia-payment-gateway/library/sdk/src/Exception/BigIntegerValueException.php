<?php

declare(strict_types=1);

namespace CKPL\Pay\Exception;

/**
 * Class BigIntegerValueException.
 *
 * @package CKPL\Pay\Exception
 */
class BigIntegerValueException extends BigIntegerException
{
    /**
     * @type string
     */
    const INVALID_TYPE = 'Invalid value type for BigInteger. Expected %s, got %s.';
}
