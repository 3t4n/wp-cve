<?php

declare(strict_types=1);

namespace CKPL\Pay\Configuration\Resolver;

use CKPL\Pay\Configuration\Reference\ConfigurationReferenceInterface;
use CKPL\Pay\Configuration\Resolver\Resolve\Defaults;
use CKPL\Pay\Configuration\Resolver\Resolve\Defined;
use CKPL\Pay\Configuration\Resolver\Resolve\Required;
use CKPL\Pay\Configuration\Resolver\Resolve\Types;
use CKPL\Pay\Configuration\Resolver\Resolve\Values;
use CKPL\Pay\Exception\ConfigurationException;
use CKPL\Pay\Exception\ConfigurationResolverException;
use ReflectionException;
use ReflectionObject;
use function array_diff;
use function array_key_exists;
use function array_keys;
use function count;
use function get_class;
use function gettype;
use function in_array;
use function is_string;
use function join;
use function sprintf;

/**
 * Class ConfigurationResolver.
 *
 * @package CKPL\Pay\Configuration\Resolver
 */
class ConfigurationResolver implements ConfigurationResolverInterface
{
    /**
     * @type string
     */
    const TYPE_OBJECT = 'object';

    /**
     * @param ConfigurationReferenceInterface $configurationReference
     *
     * @throws ConfigurationException
     * @throws ReflectionException
     *
     * @return array
     */
    public function resolveReference(ConfigurationReferenceInterface $configurationReference): array
    {
        return $this->resolve($configurationReference->getConfiguration());
    }

    /**
     * @param array $configuration
     *
     * @throws ConfigurationException
     * @throws ReflectionException
     *
     * @return array
     */
    public function resolve(array $configuration): array
    {
        $this->assignDefaultValues($configuration);
        $this->verifyRequiredValues($configuration);
        $this->verifyOptions($configuration);
        $this->verifyOptionsTypes($configuration);
        $this->verifyValues($configuration);

        return $configuration;
    }

    /**
     * @param array $configuration
     *
     * @return void
     */
    protected function assignDefaultValues(array &$configuration): void
    {
        foreach (Defaults::getDefaults() as $optionName => $optionValue) {
            if (!array_key_exists($optionName, $configuration)) {
                $configuration[$optionName] = $optionValue;
            }
        }
    }

    /**
     * @param array $configuration
     *
     * @throws ConfigurationResolverException
     *
     * @return void
     */
    protected function verifyRequiredValues(array &$configuration): void
    {
        foreach (Required::getRequired() as $optionName) {
            if (!array_key_exists($optionName, $configuration)) {
                throw new ConfigurationResolverException(
                    sprintf('Missing value for %s in configuration. This option is required!', $optionName)
                );
            }
        }
    }

    /**
     * @param array $configuration
     *
     * @throws ConfigurationResolverException
     * @throws ReflectionException
     *
     * @return void
     */
    protected function verifyOptions(array &$configuration): void
    {
        $defined = Defined::getDefined();
        $compare = array_diff(array_keys($configuration), $defined);

        if (count($compare) > 0) {
            throw new ConfigurationResolverException(
                sprintf('Unknown option(s) %s in configuration.', join(', ', $compare))
            );
        }
    }

    /**
     * @param array $configuration
     *
     * @throws ConfigurationResolverException
     *
     * @return void
     */
    protected function verifyOptionsTypes(array &$configuration): void
    {
        $types = Types::getTypes();

        foreach ($configuration as $optionName => $optionValue) {
            $type = gettype($optionValue);

            if ($type !== static::TYPE_OBJECT && !in_array($optionName, ($types[$type] ?? []))) {
                throw new ConfigurationResolverException(
                    sprintf(
                        ConfigurationResolverException::EXPECTED_VALUE,
                        $optionName,
                        Types::findTypeForOption($optionName),
                        $type
                    )
                );
            } elseif ($type === static::TYPE_OBJECT) {//NOSONAR
                $this->verifyObjectType($types, $type, $optionName, $optionValue);
            }
        }
    }

    /**
     * @param array  $types
     * @param string $type
     * @param string $optionName
     * @param mixed  $optionValue
     *
     * @throws ConfigurationResolverException
     *
     * @return void
     */
    protected function verifyObjectType(array $types, string $type, string $optionName, $optionValue): void
    {
        $objects = $types[static::TYPE_OBJECT];

        $resultOptionName = null;
        $reflectionObject = new ReflectionObject($optionValue);

        foreach ($reflectionObject->getInterfaceNames() as $interfaceName) {
            $resultOptionName = $objects[$interfaceName] ?? null;

            if (null !== $resultOptionName) {
                break;
            }
        }

        if (!is_string($resultOptionName) || $resultOptionName !== $optionName) {
            throw new ConfigurationResolverException(
                sprintf(
                    ConfigurationResolverException::EXPECTED_VALUE,
                    $optionName,
                    Types::findTypeForOption($optionName),
                    ($type === static::TYPE_OBJECT ? get_class($optionValue) : $type)
                )
            );
        }
    }

    /**
     * @param array $configuration
     *
     * @throws ConfigurationResolverException
     *
     * @return void
     */
    protected function verifyValues(array &$configuration): void
    {
        foreach ($configuration as $optionName => $optionValue) {
            Values::validate($optionName, $optionValue);
        }
    }
}
