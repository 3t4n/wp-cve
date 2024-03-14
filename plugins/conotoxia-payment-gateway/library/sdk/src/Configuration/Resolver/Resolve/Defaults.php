<?php

declare(strict_types=1);

namespace CKPL\Pay\Configuration\Resolver\Resolve;

use CKPL\Pay\Configuration\ConfigurationInterface;
use CKPL\Pay\Service\Factory\DependencyFactory;

/**
 * Class Defaults.
 *
 * @package CKPL\Pay\Configuration\Resolver\Resolve
 */
final class Defaults
{
    /**
     * @type string
     */
    private const SIGN_ALGORITHM = 'SHA256';

    /**
     * @type string
     */
    private const CATEGORY = 'E_COMMERCE';

    /**
     * @return array
     */
    public static function getDefaults(): array
    {
        return [
            ConfigurationInterface::SIGN_ALGORITHM => Defaults::getDefaultForSignAlgorithm(),
            ConfigurationInterface::DEPENDENCY_FACTORY => Defaults::getDefaultForDependencyFactory(),
            ConfigurationInterface::CATEGORY => Defaults::getDefaultForCategory(),
            ConfigurationInterface::CURL_OPTIONS => Defaults::getDefaultForCurlOptions(),
        ];
    }

    /**
     * @return string
     */
    public static function getDefaultForSignAlgorithm(): string
    {
        return Defaults::SIGN_ALGORITHM;
    }

    /**
     * @return DependencyFactory
     */
    public static function getDefaultForDependencyFactory(): DependencyFactory
    {
        return new DependencyFactory();
    }

    /**
     * @return string
     */
    public static function getDefaultForCategory(): string
    {
        return Defaults::CATEGORY;
    }

    /**
     * @return array
     */
    public static function getDefaultForCurlOptions(): array
    {
        return [];
    }
}
