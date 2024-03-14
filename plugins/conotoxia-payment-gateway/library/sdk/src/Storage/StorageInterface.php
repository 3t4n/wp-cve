<?php

declare(strict_types=1);

namespace CKPL\Pay\Storage;

use CKPL\Pay\Exception\TypeMismatchException;
use CKPL\Pay\Exception\ValueNotFoundException;

/**
 * Interface StorageInterface.
 *
 * This interface can be use to create storage for information
 * received from Payment Service such as authorization token,
 * service public key and merchant public key id etc.
 *
 * However better way to do that is to extend `AbstractStorage`
 * class which includes basic functionality that in most
 * cases will be the same.
 *
 * List of storage items:
 *  * token `(array)`
 *  * public_key_id `(string)`
 *  * public_key_checksum `(string)`
 *
 * Each parameter must be saved as specified type
 * otherwise storage will throw an exception!
 *
 * @package CKPL\Pay\Storage
 */
interface StorageInterface
{
    /**
     * Key for item that stores merchant session token.
     * Value is expected to be an array.
     *
     *     array(
     *         'token' => 'authorization-token', // string
     *         'expires_in' => 900, // expiration time (in seconds); integer
     *         'type' => 'token-type', // string
     *         'requested_at' => 'exact-time-when-token-was-requested', // string; datetime format should be Y-m-d\TH:i:sP
     *     );
     *
     * @type string
     */
    const TOKEN = 'token';

    /**
     * Key for item that stores merchant public key ID.
     * Value is expected to be a string.
     *
     * @type string
     */
    const PUBLIC_KEY_ID = 'public_key_id';

    /**
     * Key for item that stores checksum for merchant public key.
     * Value is expected to be a string.
     *
     * @type string
     */
    const PUBLIC_KEY_CHECKSUM = 'public_key_checksum';

    /**
     * Key for item that stores Payment Service public keys.
     * Each key is saved in `key -> value` form.
     *
     *     array(
     *        'key-id' => 'public_key'
     *    );
     *
     * @type string
     */
    const PAYMENT_SERVICE_PUBLIC_KEYS = 'payment_service_public_keys';

    /**
     * Checks whether the item with specified key
     * exists in storage or does not exist.
     *
     * @param string $key item key
     *
     * @return bool `true` if item exist; `false` if item does not exist
     */
    public function hasItem(string $key): bool;

    /**
     * Returns value from storage.
     * Type of value is `boolean` or `NULL`.
     *
     * @param string $key item key
     *
     * @throws TypeMismatchException  if stored value type is not `boolean` or `NULL`
     * @throws ValueNotFoundException if value with given key not exist in storage
     *
     * @return bool|null
     */
    public function expectBooleanOrNull(string $key): ?bool;

    /**
     * Returns value from storage.
     * Type of value is `string` or `NULL`.
     *
     * @param string $key item key
     *
     * @throws TypeMismatchException  if stored value type is not `string` or `NULL`
     * @throws ValueNotFoundException if value with given key not exist in storage
     *
     * @return string|null
     */
    public function expectStringOrNull(string $key): ?string;

    /**
     * Returns value from storage.
     * Type of value is `integer` or `NULL`.
     *
     * @param string $key item key
     *
     * @throws TypeMismatchException  if stored value type is not `integer` or `NULL`
     * @throws ValueNotFoundException if value with given key not exist in storage
     *
     * @return int|null
     */
    public function expectIntOrNull(string $key): ?int;

    /**
     * Returns value from storage.
     * Type of value is `float` or `NULL`.
     *
     * @param string $key item key
     *
     * @throws TypeMismatchException  if stored value type is not `float` or `NULL`
     * @throws ValueNotFoundException if value with given key not exist in storage
     *
     * @return float|null
     */
    public function expectFloatOrNull(string $key): ?float;

    /**
     * Returns value from storage.
     * Type of value is `array` or `NULL`.
     *
     * @param string $key item key
     *
     * @throws TypeMismatchException  if stored value type is not `array` or `NULL`
     * @throws ValueNotFoundException if value with given key not exist in storage
     *
     * @return array|null
     */
    public function expectArrayOrNull(string $key): ?array;

    /**
     * Changes value of item with specified key.
     * Remember that item value must be saved using correct type.
     * If storage set `string` but value is expected to be `integer`
     * then `TypeMismatchException` will be thrown at the time when
     * library get this value.
     *
     * @param string $key   item key
     * @param mixed  $value item Value
     *
     * @return void
     */
    public function setItem(string $key, $value): void;

    /**
     * Clears entire storage.
     *
     * @return void
     */
    public function clear(): void;
}
