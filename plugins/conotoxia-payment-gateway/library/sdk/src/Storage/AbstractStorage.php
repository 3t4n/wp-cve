<?php

declare(strict_types=1);

namespace CKPL\Pay\Storage;

use CKPL\Pay\Exception\TypeMismatchException;
use CKPL\Pay\Exception\ValueNotFoundException;
use function gettype;
use function is_null;
use function sprintf;

/**
 * Class AbstractStorage.
 *
 * This abstract class can be use to create storage for information
 * received from Payment Service such as authorization token,
 * service public key and merchant public key id etc.
 *
 * List of storage items:
 *  * token `(array)`
 *  * payment_service_public_keys `(array)`
 *  * public_key_id `(string)`
 *  * public_key_checksum `(string)`
 *
 * Each parameter must be saved as specified type
 * otherwise storage will throw an exception!
 *
 * @package CKPL\Pay\Storage
 */
abstract class AbstractStorage implements StorageInterface
{
    /**
     * Array with items loaded from storage.
     *
     * @var array
     */
    protected $items;

    /**
     * Returns value from storage.
     * Type of value is `boolean` or `NULL`.
     *
     * @param string $key value key
     *
     * @throws TypeMismatchException  if stored value type is not `boolean` or `NULL`
     * @throws ValueNotFoundException if value with given key not exist in storage
     *
     * @return bool|null
     */
    public function expectBooleanOrNull(string $key): ?bool
    {
        return $this->returnExpectedOrFail($key, 'boolean');
    }

    /**
     * Returns value from storage.
     * Type of value is `string` or `NULL`.
     *
     * @param string $key value key
     *
     * @throws TypeMismatchException  if stored value type is not `string` or `NULL`
     * @throws ValueNotFoundException if value with given key not exist in storage
     *
     * @return string|null
     */
    public function expectStringOrNull(string $key): ?string
    {
        return $this->returnExpectedOrFail($key, 'string');
    }

    /**
     * Returns value from storage.
     * Type of value is `integer` or `NULL`.
     *
     * @param string $key value key
     *
     * @throws TypeMismatchException  if stored value type is not `integer` or `NULL`
     * @throws ValueNotFoundException if value with given key not exist in storage
     *
     * @return int|null
     */
    public function expectIntOrNull(string $key): ?int
    {
        return $this->returnExpectedOrFail($key, 'integer');
    }

    /**
     * Returns value from storage.
     * Type of value is `float` or `NULL`.
     *
     * @param string $key value key
     *
     * @throws TypeMismatchException  if stored value type is not `float` or `NULL`
     * @throws ValueNotFoundException if value with given key not exist in storage
     *
     * @return float|null
     */
    public function expectFloatOrNull(string $key): ?float
    {
        return $this->returnExpectedOrFail($key, 'double');
    }

    /**
     * Returns value from storage.
     * Type of value is `array` or `NULL`.
     *
     * @param string $key value key
     *
     * @throws TypeMismatchException  if stored value type is not `array` or `NULL`
     * @throws ValueNotFoundException if value with given key not exist in storage
     *
     * @return array|null
     */
    public function expectArrayOrNull(string $key): ?array
    {
        return $this->returnExpectedOrFail($key, 'array');
    }

    /**
     * @param string $key
     * @param string $expected
     *
     * @throws TypeMismatchException
     * @throws ValueNotFoundException
     *
     * @return mixed
     */
    protected function returnExpectedOrFail(string $key, string $expected)
    {
        $this->failOnElementNotExist($key);

        $type = gettype($this->items[$key]);

        if ($type !== $expected && !is_null($this->items[$key])) {
            throw new TypeMismatchException(
                sprintf(TypeMismatchException::EXPECTED_TYPE, $expected, $type)
            );
        }

        return $this->items[$key];
    }

    /**
     * @param string $key
     *
     * @throws ValueNotFoundException
     *
     * @return void
     */
    protected function failOnElementNotExist(string $key): void
    {
        if (!$this->hasItem($key)) {
            throw new ValueNotFoundException(
                sprintf('Element with key %s does not exist.', $key)
            );
        }
    }
}
