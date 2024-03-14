<?php

declare(strict_types=1);

namespace CKPL\Pay\Exception;

/**
 * Class BigIntegerException.
 *
 * @package CKPL\Pay\Exception
 */
class BigIntegerException extends Exception
{
    /**
     * @type string
     */
    const UNSUPPORTED_BASE = 'Value %d for base in not supported.';
}
