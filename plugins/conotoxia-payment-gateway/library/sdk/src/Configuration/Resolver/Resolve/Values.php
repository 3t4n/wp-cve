<?php

declare(strict_types=1);

namespace CKPL\Pay\Configuration\Resolver\Resolve;

use CKPL\Pay\Configuration\ConfigurationInterface;
use CKPL\Pay\Exception\ConfigurationResolverException;
use function CKPL\Pay\validate_url;
use function in_array;
use function sprintf;

/**
 * Class Values.
 *
 * @package CKPL\Pay\Configuration\Resolver\Resolve
 */
final class Values
{
    /**
     * @type array
     */
    private const VALUES_WITH_URL = [
        ConfigurationInterface::HOST,
        ConfigurationInterface::OIDC,
        ConfigurationInterface::RETURN_URL,
        ConfigurationInterface::ERROR_URL,
        ConfigurationInterface::PAYMENTS_NOTIFICATION_URL,
        ConfigurationInterface::REFUNDS_NOTIFICATION_URL,
    ];

    /**
     * @param string $optionName
     * @param $optionValue
     *
     * @throws ConfigurationResolverException
     *
     * @return void
     */
    public static function validate(string $optionName, $optionValue): void
    {
        if (in_array($optionName, Values::VALUES_WITH_URL)) {
            Values::validateUrl($optionName, $optionValue);
        } else {
            Values::validateChoices($optionName, $optionValue);
        }
    }

    /**
     * @param string $optionName
     * @param string $optionValue
     *
     * @throws ConfigurationResolverException
     *
     * @return void
     */
    private static function validateUrl(string $optionName, string $optionValue): void
    {
        if (!validate_url($optionValue)) {
            throw new ConfigurationResolverException(
                sprintf(ConfigurationResolverException::INVALID_VALUE, $optionName)
            );
        }
    }

    /**
     * @param string $optionName
     * @param mixed  $optionValue
     *
     * @throws ConfigurationResolverException
     *
     * @return void
     */
    private static function validateChoices(string $optionName, $optionValue): void
    {
        if (ConfigurationInterface::SIGN_ALGORITHM === $optionName
            && !in_array($optionValue, ['SHA256', 'SHA384', 'SHA512'])) {
            throw new ConfigurationResolverException(
                sprintf(ConfigurationResolverException::INVALID_VALUE, $optionName)
            );
        }
    }
}
