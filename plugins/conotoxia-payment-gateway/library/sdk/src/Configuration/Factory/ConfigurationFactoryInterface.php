<?php

declare(strict_types=1);

namespace CKPL\Pay\Configuration\Factory;

use CKPL\Pay\Configuration\ConfigurationInterface;
use CKPL\Pay\Exception\ConfigurationException;
use CKPL\Pay\Exception\JsonFunctionException;

/**
 * Interface ConfigurationFactoryInterface.
 *
 * @package CKPL\Pay\Configuration\Factory
 */
interface ConfigurationFactoryInterface
{
    /**
     * @param array $array
     *
     * @throws ConfigurationException
     *
     * @return ConfigurationInterface
     */
    public static function fromArray(array $array): ConfigurationInterface;

    /**
     * @param string $path
     *
     * @throws ConfigurationException
     * @throws JsonFunctionException
     *
     * @return ConfigurationInterface
     */
    public static function fromFile(string $path): ConfigurationInterface;
}
