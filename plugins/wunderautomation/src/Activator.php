<?php

namespace WunderAuto;

/**
 * Fired during plugin activation
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 */
class Activator
{
    /**
     * Activate
     *
     * @return void
     */
    public static function activate()
    {
        $plugins = get_option('active_plugins');
        if (is_array($plugins)) {
            $proIsActive = false;
            foreach ($plugins as $plugin) {
                if (basename($plugin) === 'wunderautomation-pro.php') {
                    wp_die('Can\'t activate WunderAutomation while WunderAutomation Pro is active');
                }
            }
        }

        set_transient('wunderauto_welcome_redirect', true, 30);
        set_transient('wunderauto_welcome_wizard_autoshow', true, 30);
    }

    /**
     * Deactivate
     *
     * @return void
     */
    public static function deactivate()
    {
    }
}
