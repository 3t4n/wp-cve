<?php

namespace App\Controllers;

use App\Base\Controller;
use WP_Error;

class Uninstall extends Controller
{
    /**
     * Self deactivate the plugin. 
     * 
     * Will be called via rest api on uninstall/remove the site 
     * from Nextsale dashboard.
     *
     * @return void
     */
    public static function invoke()
    {
        if (!self::verifyToken()) {
            return new WP_Error('unauthorized', 'Authentication failed.', [
                'status' => 401
            ]);
        }

        require_once(ABSPATH . 'wp-admin/includes/plugin.php');

        delete_option('nextsale_exchange_code');
        delete_option('nextsale_access_token');
        delete_option('nextsale_script_tags');
        delete_option('nextsale_webhooks');
        delete_option('nextsale_auth_granted');

        deactivate_plugins(plugin_basename(dirname(__FILE__, 3)) . '/nextsale.php');

        return [
            'success' => true
        ];
    }
}
