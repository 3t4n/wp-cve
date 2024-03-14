<?php

declare(strict_types=1);

namespace CKPL\Pay\Security\JWT\Part;

use CKPL\Pay\Exception\IncompatibilityException;
use CKPL\Pay\Exception\JsonFunctionException;
use function CKPL\Pay\base64url_decode;
use function CKPL\Pay\base64url_encode;
use function CKPL\Pay\json_decode_array;
use function CKPL\Pay\json_encode_array;

/**
 * Class Part.
 *
 * @package CKPL\Pay\Security\JWT\Part
 */
class Part implements PartInterface
{
    /**
     * @var array
     */
    protected $part;

    /**
     * @var string|null
     */
    protected $encoded;

    /**
     * @param string $encodedPart
     *
     * @throws IncompatibilityException
     * @throws JsonFunctionException
     *
     * @return PartInterface
     */
    public static function fromEncoded(string $encodedPart): PartInterface
    {
        return new self(json_decode_array(base64url_decode($encodedPart), true), $encodedPart);
    }

    /**
     * Part constructor.
     *
     * @param array       $part
     * @param string|null $encoded
     */
    public function __construct(array $part, string $encoded = null)
    {
        $this->part = $part;
        $this->encoded = $encoded;
    }

    /**
     * @return array
     */
    public function raw(): array
    {
        return $this->part;
    }

    /**
     * @throws IncompatibilityException
     *
     * @return string
     */
    public function encoded(): string
    {
        return null === $this->encoded ? base64url_encode(json_encode_array($this->part)) : $this->encoded;
    }
}
