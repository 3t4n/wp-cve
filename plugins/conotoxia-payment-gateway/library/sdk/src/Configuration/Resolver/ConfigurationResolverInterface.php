<?php

declare(strict_types=1);

namespace CKPL\Pay\Configuration\Resolver;

use CKPL\Pay\Configuration\Reference\ConfigurationReferenceInterface;

/**
 * Interface ConfigurationResolverInterface.
 *
 * @package CKPL\Pay\Configuration\Resolver
 */
interface ConfigurationResolverInterface
{
    /**
     * @param ConfigurationReferenceInterface $configurationReference
     *
     * @return array
     */
    public function resolveReference(ConfigurationReferenceInterface $configurationReference): array;

    /**
     * @param array $configuration
     *
     * @return array
     */
    public function resolve(array $configuration): array;
}
