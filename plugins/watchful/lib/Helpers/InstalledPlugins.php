<?php
/**
 * Helper for managing installed plugins.
 *
 * @version     2016-12-20 11:41 UTC+01
 * @package     Watchful WP Client
 * @author      Watchful
 * @authorUrl   https://watchful.net
 * @copyright   Copyright (c) 2020 watchful.net
 * @license     GNU/GPL
 */

namespace Watchful\Helpers;

use WP_Error;

/**
 * Watchful Installed Plugins class.
 */
class InstalledPlugins
{

    /**
     * Check if a plugin is present whether it is enabled or not.
     * Contrary to is_plugin_inactive() WP function, this one returns false if the plugin isn't installed.
     * We can use this function as a helper but it's cleaner make our own function (see has_akeeba_backup()).
     *
     * @param string $name The name of the plugin to check (format [dir]/[file.php]).
     *
     * @return bool
     */
    public static function has($name)
    {
        require_once ABSPATH.'wp-admin/includes/plugin.php';
        $plugin_installed = validate_plugin($name);
        if ($plugin_installed instanceof WP_Error) {
            return false;
        }

        return true;
    }

    /**
     * Check if a plugin is present and active.
     * We can use this function as a helper but it's cleaner make our own function (see has_akeeba_backup()).
     *
     * @param string $name The name of the plugin.
     *
     * @return bool
     */
    public static function has_active($name)
    {
        return is_plugin_active($name);
    }

    /**
     * @param string $name Path to the plugin file relative to the plugins directory.
     * @return bool
     */
    public static function uninstall($name)
    {
        require_once ABSPATH.'wp-admin/includes/plugin.php';
        require_once ABSPATH.'wp-admin/includes/file.php';
        $plugin_installed = validate_plugin($name);
        if ($plugin_installed instanceof WP_Error) {
            return false;
        }
        deactivate_plugins($name);

        delete_plugins(array($name));
        return true;
    }
}
