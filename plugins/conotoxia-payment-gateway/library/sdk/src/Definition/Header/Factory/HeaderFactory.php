<?php

declare(strict_types=1);

namespace CKPL\Pay\Definition\Header\Factory;

use CKPL\Pay\Definition\Header\Header;
use CKPL\Pay\Definition\Header\HeaderInterface;
use CKPL\Pay\Exception\HeaderException;

/**
 * Class HeaderFactory.
 *
 * @package CKPL\Pay\Definition\Header\Factory
 */
class HeaderFactory implements HeaderFactoryInterface
{
    /**
     * @var string|null
     */
    protected $algorithm;

    /**
     * @var string|null
     */
    protected $keyId;

    /**
     * @var string|null
     */
    protected $contentType;

    /**
     * @var string
     */
    protected $signatureType;

    /**
     * @param string $algorithm
     *
     * @return HeaderFactoryInterface
     */
    public function setAlgorithm(string $algorithm): HeaderFactoryInterface
    {
        $this->algorithm = $algorithm;

        return $this;
    }

    /**
     * @param string|null $keyId
     *
     * @return HeaderFactoryInterface
     */
    public function setKeyId(string $keyId): HeaderFactoryInterface
    {
        $this->keyId = $keyId;

        return $this;
    }

    /**
     * @param string|null $contentType
     *
     * @return HeaderFactoryInterface
     */
    public function setContentType(string $contentType = null): HeaderFactoryInterface
    {
        $this->contentType = $contentType;

        return $this;
    }

    /**
     * @param string $signatureType
     *
     * @return HeaderFactoryInterface
     */
    public function setSignatureType(string $signatureType): HeaderFactoryInterface
    {
        $this->signatureType = $signatureType;

        return $this;
    }

    /**
     * @throws HeaderException
     *
     * @return HeaderInterface
     */
    public function build(): HeaderInterface
    {
        if (empty($this->algorithm)) {
            throw new HeaderException('Algorithm is required in header.');
        }

        if (empty($this->keyId)) {
            throw new HeaderException('Key ID is required in header.');
        }

        $header = [
            'alg' => $this->algorithm,
            'kid' => $this->keyId,
        ];

        if (!empty($this->contentType)) {
            $header['cty'] = $this->contentType;
        }

        if (!empty($this->signatureType)) {
            $header['typ'] = $this->signatureType;
        }

        return new Header($header);
    }
}
