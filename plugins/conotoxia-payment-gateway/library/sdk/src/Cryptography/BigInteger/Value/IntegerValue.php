<?php

declare(strict_types=1);

namespace CKPL\Pay\Cryptography\BigInteger\Value;

use CKPL\Pay\Exception\BigIntegerValueException;
use function gettype;
use function is_int;
use function sprintf;

/**
 * Class IntegerValue.
 *
 * @package CKPL\Pay\Cryptography\BigInteger\Value
 */
class IntegerValue implements ValueInterface
{
    /**
     * @var int
     */
    protected $value;

    /**
     * IntegerValue constructor.
     *
     * @param int $value
     */
    public function __construct(int $value)
    {
        $this->value = $value;
    }

    /**
     * @return int
     */
    public function getValue(): int
    {
        return $this->value;
    }

    /**
     * @param int $value
     *
     * @throws BigIntegerValueException
     *
     * @return void
     */
    public function setValue($value): void
    {
        if (!is_int($value)) {
            throw new BigIntegerValueException(
                sprintf(BigIntegerValueException::INVALID_TYPE, 'integer', gettype($value))
            );
        }

        $this->value = $value;
    }
}
