<?php

declare(strict_types=1);

namespace CKPL\Pay\Configuration\Reference;

use CKPL\Pay\Exception\ConfigurationReferenceException;

/**
 * Interface ConfigurationReferenceInterface.
 *
 * @package CKPL\Pay\Configuration\Reference
 */
interface ConfigurationReferenceInterface
{
    /**
     * @throws ConfigurationReferenceException
     *
     * @return array
     */
    public function getConfiguration(): array;
}
