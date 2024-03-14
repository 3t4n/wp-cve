<?php

declare(strict_types=1);

namespace CKPL\Pay\Definition\Payload;

use CKPL\Pay\Exception\PayloadException;
use function array_key_exists;
use function gettype;
use function is_null;
use function sprintf;

/**
 * Class Payload.
 *
 * @package CKPL\Pay\Definition\Payload
 */
class Payload implements PayloadInterface
{
    /**
     * @var array
     */
    protected $payload;

    /**
     * Payload constructor.
     *
     * @param array $payload
     */
    public function __construct(array $payload)
    {
        $this->payload = $payload;
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function hasElement(string $key): bool
    {
        return array_key_exists($key, $this->payload);
    }

    /**
     * @param string $key
     *
     * @throws PayloadException
     *
     * @return bool|null
     */
    public function expectBooleanOrNull(string $key): ?bool
    {
        return $this->returnExpectedOrFail($key, 'boolean');
    }

    /**
     * @param string $key
     *
     * @throws PayloadException
     *
     * @return string|null
     */
    public function expectStringOrNull(string $key): ?string
    {
        return $this->returnExpectedOrFail($key, 'string');
    }

    /**
     * @param string $key
     *
     * @throws PayloadException
     *
     * @return int|null
     */
    public function expectIntOrNull(string $key): ?int
    {
        return $this->returnExpectedOrFail($key, 'integer');
    }

    /**
     * @param string $key
     *
     * @throws PayloadException
     *
     * @return float|null
     */
    public function expectFloatOrNull(string $key): ?float
    {
        return $this->returnExpectedOrFail($key, 'double');
    }

    /**
     * @param string $key
     *
     * @throws PayloadException
     *
     * @return array|null
     */
    public function expectArrayOrNull(string $key): ?array
    {
        return $this->returnExpectedOrFail($key, 'array');
    }

    /**
     * @return array
     */
    public function raw(): array
    {
        return $this->payload;
    }

    /**
     * @param string $key
     * @param string $expected
     *
     * @throws PayloadException
     *
     * @return mixed
     */
    protected function returnExpectedOrFail(string $key, string $expected)
    {
        $this->failOnElementNotExist($key);

        $type = gettype($this->payload[$key]);

        if ($type !== $expected && !is_null($this->payload[$key])) {
            throw new PayloadException(
                sprintf(PayloadException::EXPECTED_TYPE, $expected, $type)
            );
        }

        return $this->payload[$key];
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function getArrayValueByKey(string $key)
    {
        if (!$this->hasElement($key)) {
            return null;
        }
        return $this->payload[$key];
    }

    /**
     * @param string $key
     *
     * @throws PayloadException
     *
     * @return void
     */
    protected function failOnElementNotExist(string $key): void
    {
        if (!$this->hasElement($key)) {
            throw new PayloadException(
                sprintf('Element with key %s does not exist.', $key)
            );
        }
    }
}
