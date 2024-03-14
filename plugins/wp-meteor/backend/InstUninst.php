<?php

/**
 * WP_Meteor
 *
 * @package   WP_Meteor
 * @author    Aleksandr Guidrevitch <alex@excitingstartup.com>
 * @copyright 2020 wp-meteor.com
 * @license   GPL 2.0+
 * @link      https://wp-meteor.com
 */

namespace WP_Meteor\Backend;

use WP_Meteor\Engine\Base;

/**
 * Activate and deactive method of the plugin and relates.
 */
class InstUninst extends Base
{

    /**
     * Initialize the class.
     *
     * @return void
     */
    public function initialize()
    {
        // \register_activation_hook(WPMETEOR_TEXTDOMAIN . '/' . WPMETEOR_TEXTDOMAIN . '.php', array(self::class, 'install'));
        \register_uninstall_hook(WPMETEOR_TEXTDOMAIN . '/' . WPMETEOR_TEXTDOMAIN . '.php', array(self::class, 'uninstall'));
    }

    /**
     * Fired when the plugin is uninstalled.
     *
     * @param bool $network_wide True if WPMU superadmin uses
     * "Network Deactivate" action, false if
     * WPMU is disabled or plugin is
     * deactivated on an individual blog.
     * @since 1.0.0
     * @return void
     */
    public static function uninstall(bool $network_wide)
    {
        if (\function_exists('is_multisite') && \is_multisite()) {
            // Get all blog ids
            /** @var array<\WP_Site> $blogs */
            $blogs = \get_sites();

            foreach ($blogs as $blog) {
                \switch_to_blog((int) $blog->blog_id);
                self::single_uninstall();
                \restore_current_blog();
            }

            return;
        }

        self::single_uninstall();
    }

    /**
     * Fired for each blog when the plugin is deactivated.
     *
     * @since 1.0.0
     * @return void
     */
    private static function single_uninstall()
    {
        \delete_option(WPMETEOR_TEXTDOMAIN . '-settings');
    }
}
