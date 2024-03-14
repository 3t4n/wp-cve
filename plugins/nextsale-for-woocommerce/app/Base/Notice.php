<?php

namespace App\Base;

use App\Utils\Helper;

class Notice extends Plugin
{
    /**
     * Register
     * @return void
     */
    public function register()
    {
        add_action('admin_notices', [$this, 'addNotice']);
    }

    /**
     * Enqueue scripts
     * @return void
     */
    public static function addNotice()
    {
        global $pagenow;

        if (!Helper::isAuthGranted() && $pagenow != 'admin.php') {
            return require_once(self::$plugin_path . '/templates/auth-notice.php');
        }
    }
}
