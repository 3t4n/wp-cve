<?php

declare(strict_types=1);

namespace CKPL\Pay\Definition\Header\Factory;

use CKPL\Pay\Definition\Header\HeaderInterface;

/**
 * Interface HeaderFactoryInterface.
 *
 * @package CKPL\Pay\Definition\Header\Factory
 */
interface HeaderFactoryInterface
{
    /**
     * @param string $algorithm
     *
     * @return HeaderFactoryInterface
     */
    public function setAlgorithm(string $algorithm): HeaderFactoryInterface;

    /**
     * @param string $keyId
     *
     * @return HeaderFactoryInterface
     */
    public function setKeyId(string $keyId): HeaderFactoryInterface;

    /**
     * @param string|null $contentType
     *
     * @return HeaderFactoryInterface
     */
    public function setContentType(string $contentType = null): HeaderFactoryInterface;

    /**
     * @param string $signatureType
     *
     * @return HeaderFactoryInterface
     */
    public function setSignatureType(string $signatureType): HeaderFactoryInterface;

    /**
     * @return HeaderInterface
     */
    public function build(): HeaderInterface;
}
