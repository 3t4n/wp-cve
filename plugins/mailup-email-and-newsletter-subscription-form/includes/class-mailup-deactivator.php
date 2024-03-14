<?php

declare(strict_types=1);

/**
 * Fired during plugin deactivation.
 *
 * @see  https://mailup.it
 * @since 1.2.6
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.2.6
 *
 * @author     Your Name <email@example.com>
 */
class Mailup_Deactivator
{
    /**
     * Short Description. (use period).
     *
     * Long Description.
     *
     * @since 1.2.6
     */
    public static function deactivate(): void
    {
        if (!current_user_can('activate_plugins')) {
            return;
        }
    }
}
