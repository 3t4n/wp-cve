<?php

declare(strict_types=1);

namespace CKPL\Pay\Exception\Api\ValidationCollection;

/**
 * Interface ValidationCollectionInterface.
 *
 * @package CKPL\Pay\Exception\Api\ValidationCollection
 */
interface ValidationCollectionInterface
{
    /**
     * @return array
     */
    public function getErrors(): array;

    /**
     * @param string $messageKey
     * @param string $contextKey
     * @param string $message
     * @param array $params
     *
     * @return void
     */
    public function addError(string $messageKey, string $contextKey, string $message, array $params = NULL): void;
}
