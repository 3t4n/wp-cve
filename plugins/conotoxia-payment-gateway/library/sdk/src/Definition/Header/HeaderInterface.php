<?php

declare(strict_types=1);

namespace CKPL\Pay\Definition\Header;

/**
 * Interface HeaderInterface.
 *
 * @package CKPL\Pay\Definition\Header
 */
interface HeaderInterface
{
    /**
     * @return string
     */
    public function getAlgorithm(): string;

    /**
     * @return string
     */
    public function getKeyId(): string;

    /**
     * @return string|null
     */
    public function getContentType(): ?string;

    /**
     * @return string|null
     */
    public function getSignatureType(): ?string;

    /**
     * @return array
     */
    public function raw(): array;
}
