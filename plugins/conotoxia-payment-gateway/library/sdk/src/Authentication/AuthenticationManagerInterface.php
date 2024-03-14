<?php

declare(strict_types=1);

namespace CKPL\Pay\Authentication;

use CKPL\Pay\Security\Token\TokenInterface;

/**
 * Interface AuthenticationManagerInterface.
 *
 * @package CKPL\Pay\Authentication
 */
interface AuthenticationManagerInterface
{
    /**
     * @return bool
     */
    public function isAuthenticated(): bool;

    /**
     * @param bool $forceAuthentication
     *
     * @return TokenInterface|null
     */
    public function authenticate(bool $forceAuthentication = false): ?TokenInterface;
}
