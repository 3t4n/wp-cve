<?php

declare(strict_types=1);

namespace CKPL\Pay\Exception;

/**
 * Class PayloadException.
 *
 * @package CKPL\Pay\Exception
 */
class PayloadException extends DefinitionException
{
    /**
     * @type string
     */
    const EXPECTED_TYPE = 'Expected %s or NULL, got %s.';
}
