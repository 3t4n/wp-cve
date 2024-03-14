<?php

declare(strict_types=1);

namespace CKPL\Pay\Definition\Payload;

use CKPL\Pay\Exception\PayloadException;

/**
 * Interface PayloadInterface.
 *
 * @package CKPL\Pay\Payload
 */
interface PayloadInterface
{
    /**
     * @param string $key
     *
     * @return bool
     */
    public function hasElement(string $key): bool;

    /**
     * @param string $key
     *
     * @throws PayloadException
     *
     * @return bool|null
     */
    public function expectBooleanOrNull(string $key): ?bool;

    /**
     * @param string $key
     *
     * @throws PayloadException
     *
     * @return string|null
     */
    public function expectStringOrNull(string $key): ?string;

    /**
     * @param string $key
     *
     * @throws PayloadException
     *
     * @return int|null
     */
    public function expectIntOrNull(string $key): ?int;

    /**
     * @param string $key
     *
     * @throws PayloadException
     *
     * @return float|null
     */
    public function expectFloatOrNull(string $key): ?float;

    /**
     * @param string $key
     *
     * @throws PayloadException
     *
     * @return array|null
     */
    public function expectArrayOrNull(string $key): ?array;

    /**
     * @return array
     */
    public function raw(): array;

    /**
     * @return mixed
     */
    public function getArrayValueByKey(string $key);
}
