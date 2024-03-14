<?php

declare(strict_types=1);

namespace CKPL\Pay\Exception\Api\ValidationCollection;

use ArrayIterator;
use IteratorAggregate;

/**
 * Class ValidationCollection.
 *
 * @package CKPL\Pay\Exception\Api\ValidationCollection
 */
class ValidationCollection implements ValidationCollectionInterface, IteratorAggregate
{
    /**
     * @var array
     */
    protected $errors = [];

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @param string $messageKey
     * @param string $contextKey
     * @param string $message
     * @param array $params
     *
     * @return void
     */
    public function addError(string $messageKey, string $contextKey, string $message, array $params = NULL): void
    {
        $this->errors[] = ['message-key' => $messageKey, 'context-key' => $contextKey, 'message' => $message, 'params' => $params];
    }

    /**
     * @return ArrayIterator
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->errors);
    }
}
