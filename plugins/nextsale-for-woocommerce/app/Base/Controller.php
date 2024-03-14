<?php

namespace App\Base;

class Controller
{
    /**
     * Verify access token
     * @return void
     */
    public static function verifyToken()
    {
        if (
            !isset($_SERVER['HTTP_X_WORDPRESS_ACCESS_TOKEN'])
            || get_option('nextsale_access_token') != $_SERVER['HTTP_X_WORDPRESS_ACCESS_TOKEN']
        ) {
            return false;
        }

        return true;
    }
}
