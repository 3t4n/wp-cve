<?php

declare(strict_types=1);

namespace CKPL\Pay\Exception;

use Throwable;
use function sprintf;

/**
 * Class ConfigurationFileReadFailureException.
 *
 * @package CKPL\Pay\Exception
 */
class ConfigurationFileReadFailureException extends ConfigurationException
{
    /**
     * ConfigurationFileReadFailureException constructor.
     *
     * @param string         $path
     * @param int            $code
     * @param Throwable|null $previous
     */
    public function __construct(string $path, int $code = 0, Throwable $previous = null)
    {
        parent::__construct(
            sprintf('Unable to read configuration file %s.', $path),
            $code,
            $previous
        );
    }
}
