<?php

declare(strict_types=1);

namespace CKPL\Pay\Cryptography\BigInteger\Value;

use CKPL\Pay\Exception\BigIntegerValueException;
use function gettype;
use function is_string;
use function sprintf;

/**
 * Class StringValue.
 *
 * @package CKPL\Pay\Cryptography\BigInteger\Value
 */
class StringValue implements ValueInterface
{
    /**
     * @var string
     */
    protected $value;

    /**
     * StringValue constructor.
     *
     * @param string $value
     */
    public function __construct(string $value)
    {
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @param string $value
     *
     * @throws BigIntegerValueException
     *
     * @return void
     */
    public function setValue($value): void
    {
        if (!is_string($value)) {
            throw new BigIntegerValueException(
                sprintf(BigIntegerValueException::INVALID_TYPE, 'string', gettype($value))
            );
        }

        $this->value = $value;
    }
}
