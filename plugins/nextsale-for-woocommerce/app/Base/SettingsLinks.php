<?php

namespace App\Base;

use App\Utils\Helper;

class SettingsLinks extends Plugin
{
    /**
     * Register
     * @return void
     */
    public function register()
    {
        add_filter('plugin_action_links_' . self::$plugin, [$this, 'settingsLinks'], 30);
    }

    /**
     * Setting links
     * @return void
     */
    public static function settingsLinks($links)
    {

        if (Helper::isAuthGranted()) {
            $dashboard_link = '<a href="admin.php?page=nextsale" title="Open Nextsale dashboard" target="_blank">Go to dashboard</a>';
            $invoke_link = '<a href="admin.php?page=nextsale&action=revoke" title="Disable background synchronization with Nextsale">Revoke</a>';

            array_unshift($links, $invoke_link);
            array_unshift($links, $dashboard_link);
        } else {
            $auth_link = '<a href="admin.php?page=nextsale">Authorize</a>';
            array_unshift($links, $auth_link);
        }


        return $links;
    }
}
