<?php

declare(strict_types=1);

namespace CKPL\Pay\Configuration\Factory;

use CKPL\Pay\Configuration\Configuration;
use CKPL\Pay\Configuration\ConfigurationInterface;
use CKPL\Pay\Configuration\Reference\ArrayConfigurationReference;
use CKPL\Pay\Configuration\Reference\JsonConfigurationReference;
use CKPL\Pay\Exception\ConfigurationException;
use CKPL\Pay\Exception\IncompatibilityException;
use CKPL\Pay\Exception\JsonFunctionException;
use CKPL\Pay\Exception\StorageException;

/**
 * Class ConfigurationFactory.
 *
 * @package CKPL\Pay\Configuration\Factory
 */
class ConfigurationFactory implements ConfigurationFactoryInterface
{
    /**
     * @param array $array
     *
     * @throws ConfigurationException
     *
     * @return ConfigurationInterface
     */
    public static function fromArray(array $array): ConfigurationInterface
    {
        return new Configuration(new ArrayConfigurationReference($array));
    }

    /**
     * @param string $path
     *
     * @throws ConfigurationException
     * @throws JsonFunctionException
     * @throws IncompatibilityException
     * @throws StorageException
     *
     * @return ConfigurationInterface
     */
    public static function fromFile(string $path): ConfigurationInterface
    {
        return new Configuration(new JsonConfigurationReference($path));
    }
}
