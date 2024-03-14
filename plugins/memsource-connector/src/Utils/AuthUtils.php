<?php

namespace Memsource\Utils;

class AuthUtils
{
    private const TOKEN_LENGTH = 16;

    /**
     * Check that request contains valid token.
     *
     * @return bool
     */
    public static function validateTokenInRequest()
    {
        return self::getTokenFromDb() === self::getTokenFromRequest();
    }

    /**
     * Get $_GET['token'] if available.
     *
     * @return string|false
     */
    public static function getTokenFromRequest()
    {
        return isset($_GET['token']) ? $_GET['token'] : false;
    }

    /**
     * Get token stored in database.
     *
     * @return string
     */
    public static function getTokenFromDb()
    {
        global $appRegistry;
        return $appRegistry->getOptionsService()->getToken();
    }

    /**
     * Generate a new random token.
     *
     * @return string
     * @deprecated
     */
    public static function createNewToken(): string
    {
        $token = md5(uniqid());

        if (strlen($token) > self::TOKEN_LENGTH) {
            $token = substr($token, self::TOKEN_LENGTH);
        }

        return $token;
    }

    /**
     * Generate a new random token.
     *
     * @return string
     */
    public function generateRandomToken(): string
    {
        return AuthUtils::createNewToken();
    }
}
