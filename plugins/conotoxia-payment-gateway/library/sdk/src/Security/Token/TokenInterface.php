<?php

declare(strict_types=1);

namespace CKPL\Pay\Security\Token;

use DateTime;

/**
 * Interface TokenInterface.
 *
 * @package CKPL\Pay\Security\Token
 */
interface TokenInterface
{
    /**
     * @return string
     */
    public function getToken(): string;

    /**
     * @return int
     */
    public function getExpiresIn(): int;

    /**
     * @return string
     */
    public function getType(): string;

    /**
     * @return DateTime|null
     */
    public function getRequestedAt(): ?DateTime;

    /**
     * @return bool
     */
    public function isExpired(): bool;
}
