<?php

declare(strict_types=1);

namespace CKPL\Pay\Exception;

/**
 * Class ConfigurationResolverException.
 *
 * @package CKPL\Pay\Exception
 */
class ConfigurationResolverException extends ConfigurationException
{
    /**
     * @type string
     */
    const EXPECTED_VALUE = 'Value for %s is expected to be %s, got %s.';

    /**
     * @type string
     */
    const INVALID_VALUE = 'Invalid value for %s.';
}
