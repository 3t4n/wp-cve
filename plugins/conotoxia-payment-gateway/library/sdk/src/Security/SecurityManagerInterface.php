<?php

declare(strict_types=1);

namespace CKPL\Pay\Security;

use CKPL\Pay\Security\JWT\Collection\DecodedCollectionInterface;
use CKPL\Pay\Security\Token\TokenInterface;

/**
 * Interface SecurityManagerInterface.
 *
 * @package CKPL\Pay\Security
 */
interface SecurityManagerInterface
{
    /**
     * @param array $parameters
     *
     * @return string
     */
    public function encodeRequest(array $parameters): string;

    /**
     * @param string $response
     *
     * @return DecodedCollectionInterface
     */
    public function decodeResponse(string $response): DecodedCollectionInterface;

    /**
     * @return TokenInterface|null
     */
    public function getToken(): ?TokenInterface;

    /**
     * @return void
     */
    public function invalidateToken(): void;
}
