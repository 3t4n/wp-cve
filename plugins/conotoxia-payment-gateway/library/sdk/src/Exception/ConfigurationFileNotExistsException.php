<?php

declare(strict_types=1);

namespace CKPL\Pay\Exception;

use Throwable;
use function sprintf;

/**
 * Class ConfigurationFileNotExistsException.
 *
 * @package CKPL\Pay\Exception
 */
class ConfigurationFileNotExistsException extends ConfigurationReferenceException
{
    /**
     * ConfigurationFileNotExistsException constructor.
     *
     * @param string         $path
     * @param int            $code
     * @param Throwable|null $previous
     */
    public function __construct(string $path, int $code = 0, Throwable $previous = null)
    {
        parent::__construct(
            sprintf('Configuration file %s does not exist.', $path),
            $code,
            $previous
        );
    }
}
