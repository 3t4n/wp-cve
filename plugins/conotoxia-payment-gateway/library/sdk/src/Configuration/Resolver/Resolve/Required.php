<?php

declare(strict_types=1);

namespace CKPL\Pay\Configuration\Resolver\Resolve;

use CKPL\Pay\Configuration\ConfigurationInterface;

/**
 * Class Required.
 *
 * @package CKPL\Pay\Configuration\Resolver\Resolve
 */
final class Required
{
    /**
     * @return array
     */
    public static function getRequired(): array
    {
        return [
            ConfigurationInterface::OIDC,
            ConfigurationInterface::HOST,
            ConfigurationInterface::CLIENT_ID,
            ConfigurationInterface::CLIENT_SECRET,
            ConfigurationInterface::STORAGE,
            ConfigurationInterface::PUBLIC_KEY,
            ConfigurationInterface::PRIVATE_KEY,
            ConfigurationInterface::POINT_OF_SALE,
        ];
    }
}
