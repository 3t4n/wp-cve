<?php

declare(strict_types=1);

namespace CKPL\Pay\Service\Factory;

/**
 * Interface DependencyFactoryInterface.
 *
 * @package CKPL\Pay\Service\Factory
 */
interface DependencyFactoryInterface
{
    /**
     * @param string $class
     *
     * @return bool
     */
    public function hasDependency(string $class): bool;
}
