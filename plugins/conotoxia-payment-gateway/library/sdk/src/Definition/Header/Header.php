<?php

declare(strict_types=1);

namespace CKPL\Pay\Definition\Header;

use CKPL\Pay\Exception\HeaderException;

/**
 * Class Header.
 *
 * @package CKPL\Pay\Definition\Header
 */
class Header implements HeaderInterface
{
    /**
     * @var array
     */
    protected $header;

    /**
     * Header constructor.
     *
     * @param array $header
     */
    public function __construct(array $header)
    {
        $this->header = $header;
    }

    /**
     * @throws HeaderException
     *
     * @return string
     */
    public function getAlgorithm(): string
    {
        if (!isset($this->header['alg'])) {
            throw new HeaderException('Missing "alg" parameter in header.');
        }

        return $this->header['alg'];
    }

    /**
     * @throws HeaderException
     *
     * @return string
     */
    public function getKeyId(): string
    {
        if (!isset($this->header['kid'])) {
            throw new HeaderException('Missing "kid" parameter in header.');
        }

        return $this->header['kid'];
    }

    /**
     * @return string|null
     */
    public function getContentType(): ?string
    {
        return $this->header['cty'] ?? null;
    }

    /**
     * @return string|null
     */
    public function getSignatureType(): ?string
    {
        return $this->header['typ'] ?? null;
    }

    /**
     * @return array
     */
    public function raw(): array
    {
        return $this->header;
    }
}
