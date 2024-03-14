<?php

declare(strict_types=1);

namespace CKPL\Pay\Configuration\Reference;

use CKPL\Pay\Exception\ConfigurationReferenceException;
use function gettype;
use function is_array;
use function sprintf;

/**
 * Class AbstractConfigurationReference.
 *
 * @package CKPL\Pay\Configuration\Reference
 */
abstract class AbstractConfigurationReference implements ConfigurationReferenceInterface
{
    /**
     * @var array
     */
    protected $configuration = [];

    /**
     * @throws ConfigurationReferenceException
     *
     * @return array
     */
    public function getConfiguration(): array
    {
        if (!is_array($this->configuration)) {
            throw new ConfigurationReferenceException(
                sprintf('Configuration is expected to be type of array, %s given.', gettype($this->configuration))
            );
        }

        return $this->configuration;
    }
}
