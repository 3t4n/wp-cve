<?php

declare(strict_types=1);

namespace CKPL\Pay\Configuration\Reference;

/**
 * Class ArrayConfigurationReference.
 *
 * @package CKPL\Pay\Configuration\Reference
 */
class ArrayConfigurationReference extends AbstractConfigurationReference
{
    /**
     * ArrayConfigurationReference constructor.
     *
     * @param array $configuration
     */
    public function __construct(array $configuration)
    {
        $this->configuration = $configuration;
    }
}
