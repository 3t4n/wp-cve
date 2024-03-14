<?php

declare(strict_types=1);

/**
 * Fired during plugin activation.
 *
 * @see  https://mailup.it
 * @since 1.2.6
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.2.6
 *
 * @author     Your Name <email@example.com>
 */
class Mailup_Activator
{
    /**
     * Short Description. (use period).
     *
     * Long Description.
     *
     * @since 1.2.6
     */
    public static function activate(): void
    {
        if (!current_user_can('activate_plugins')) {
            return;
        }

        if (!get_option('mailup_version') && !get_option('mailup')) {
            add_option('mailup_version', WPMUP_PLUGIN_VERSION);
        }
    }
}
