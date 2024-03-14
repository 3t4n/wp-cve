<?php

declare(strict_types=1);

namespace CKPL\Pay\Cryptography\Component;

/**
 * Interface ComponentInterface.
 *
 * @package CKPL\Pay\Cryptography\Component
 */
interface ComponentInterface
{
    /**
     * @return string
     */
    public function getComponent(): string;

    /**
     * @return string
     */
    public function getDecodedComponent(): string;
}
