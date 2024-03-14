<?php

declare(strict_types=1);

namespace CKPL\Pay\Configuration\Adjuster;

/**
 * Interface ConfigurationAdjusterInterface.
 *
 * @package CKPL\Pay\Configuration\Adjuster
 */
interface ConfigurationAdjusterInterface
{
    /**
     * @param array $configuration
     */
    public function adjust(array &$configuration): void;
}
