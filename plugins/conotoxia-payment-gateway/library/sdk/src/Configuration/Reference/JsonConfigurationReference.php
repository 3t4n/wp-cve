<?php

declare(strict_types=1);

namespace CKPL\Pay\Configuration\Reference;

use CKPL\Pay\Configuration\ConfigurationInterface;
use CKPL\Pay\Exception\ConfigurationException;
use CKPL\Pay\Exception\ConfigurationFileNotExistsException;
use CKPL\Pay\Exception\ConfigurationFileReadFailureException;
use CKPL\Pay\Exception\IncompatibilityException;
use CKPL\Pay\Exception\JsonFunctionException;
use CKPL\Pay\Exception\StorageException;
use CKPL\Pay\Storage\FileStorage;
use function CKPL\Pay\json_decode_array;
use function file_exists;
use function file_get_contents;

/**
 * Class JsonConfigurationReference.
 *
 * @package CKPL\Pay\Configuration\Reference
 */
class JsonConfigurationReference extends AbstractConfigurationReference
{
    /**
     * JsonConfigurationReference constructor.
     *
     * @param string $path
     *
     * @throws ConfigurationException
     * @throws JsonFunctionException
     * @throws IncompatibilityException
     * @throws StorageException
     */
    public function __construct(string $path)
    {
        if (!file_exists($path)) {
            throw new ConfigurationFileNotExistsException($path);
        }

        $configuration = @file_get_contents($path);

        if (false === $configuration) {
            throw new ConfigurationFileReadFailureException($path);
        }

        $this->configuration = json_decode_array($configuration);

        $this->createStorage($this->configuration);
    }

    /**
     * @param array $configuration
     *
     * @throws IncompatibilityException
     * @throws StorageException
     *
     * @return void
     */
    protected function createStorage(array &$configuration): void
    {
        if (isset($configuration[ConfigurationInterface::STORAGE])) {
            $configuration[ConfigurationInterface::STORAGE] = new FileStorage($configuration[ConfigurationInterface::STORAGE]);
        }
    }
}
