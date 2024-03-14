<?php

declare(strict_types=1);

namespace CKPL\Pay\Storage\KeyCollector;

/**
 * Interface KeyCollectorInterface.
 *
 * @package CKPL\Pay\Storage\KeyCollector
 */
interface KeyCollectorInterface
{
    /**
     * @return bool
     */
    public function hasAny(): bool;

    /**
     * @param string $id
     *
     * @return bool
     */
    public function hasKey(string $id): bool;

    /**
     * @param string $id
     *
     * @return string
     */
    public function getKey(string $id): string;

    /**
     * @param string $id
     * @param string $key
     */
    public function addKey(string $id, string $key): void;
}
