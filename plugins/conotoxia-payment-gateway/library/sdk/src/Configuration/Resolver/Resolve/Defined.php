<?php

declare(strict_types=1);

namespace CKPL\Pay\Configuration\Resolver\Resolve;

use CKPL\Pay\Configuration\ConfigurationInterface;
use ReflectionClass;
use ReflectionException;

/**
 * Class Defined.
 *
 * @package CKPL\Pay\Configuration\Resolver\Resolve
 */
final class Defined
{
    /**
     * @throws ReflectionException
     *
     * @return array
     */
    public static function getDefined(): ?array
    {
        return Defined::extractOptionsFromConfigurationInterface();
    }

    /**
     * @throws ReflectionException
     *
     * @return array
     */
    private static function extractOptionsFromConfigurationInterface(): array
    {
        $configurationInterfaceReflection = new ReflectionClass(ConfigurationInterface::class);

        $options = [];

        foreach ($configurationInterfaceReflection->getConstants() as $constant) {
            $options[] = $constant;
        }

        return $options;
    }
}
