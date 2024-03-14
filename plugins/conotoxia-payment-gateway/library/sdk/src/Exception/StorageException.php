<?php

declare(strict_types=1);

namespace CKPL\Pay\Exception;

/**
 * Class StorageException.
 *
 * @package CKPL\Pay\Exception
 */
class StorageException extends Exception
{
    /**
     * @type string
     */
    const EXPECTED_TYPE = 'Expected %s or NULL, got %s.';
}
