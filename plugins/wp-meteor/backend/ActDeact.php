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
class ActDeact extends Base
{

    /**
     * Initialize the class.
     *
     * @return void
     */
    public function initialize()
    {
        // Activate plugin when new blog is added
        \add_action('wpmu_new_blog', array($this, 'activate_new_site'));

        \register_activation_hook(WPMETEOR_TEXTDOMAIN . '/' . WPMETEOR_TEXTDOMAIN . '.php', array(self::class, 'activate'));
        \register_deactivation_hook(WPMETEOR_TEXTDOMAIN . '/' . WPMETEOR_TEXTDOMAIN . '.php', array(self::class, 'deactivate'));
    }

    /**
     * Fired when a new site is activated with a WPMU environment.
     *
     * @param int $blog_id ID of the new blog.
     * @since 1.0.0
     * @return void
     */
    public function activate_new_site(int $blog_id)
    {
        if (1 !== \did_action('wpmu_new_blog')) {
            return;
        }

        \switch_to_blog($blog_id);
        self::single_activate();
        \restore_current_blog();
    }

    /**
     * Fired when the plugin is activated.
     *
     * @param bool $network_wide True if active in a multiste, false if classic site.
     * @since 1.0.0
     * @return void
     */
    public static function activate(bool $network_wide)
    {
        if (\function_exists('is_multisite') && \is_multisite()) {
            if ($network_wide) {
                // Get all blog ids
                /** @var array<\WP_Site> $blogs */
                $blogs = \get_sites();

                foreach ($blogs as $blog) {
                    \switch_to_blog((int) $blog->blog_id);
                    self::single_activate();
                    \restore_current_blog();
                }

                return;
            }
        }

        self::single_activate();
    }

    /**
     * Fired when the plugin is deactivated.
     *
     * @param bool $network_wide True if WPMU superadmin uses
     * "Network Deactivate" action, false if
     * WPMU is disabled or plugin is
     * deactivated on an individual blog.
     * @since 1.0.0
     * @return void
     */
    public static function deactivate(bool $network_wide)
    {
        if (\function_exists('is_multisite') && \is_multisite()) {
            if ($network_wide) {
                // Get all blog ids
                /** @var array<\WP_Site> $blogs */
                $blogs = \get_sites();

                foreach ($blogs as $blog) {
                    \switch_to_blog((int) $blog->blog_id);
                    self::single_deactivate();
                    \restore_current_blog();
                }

                return;
            }
        }

        self::single_deactivate();
    }


    /**
     * Fired for each blog when the plugin is activated.
     *
     * @since 1.0.0
     * @return void
     */
    private static function single_activate()
    {
        // \flush_rewrite_rules();
    }

    /**
     * Fired for each blog when the plugin is deactivated.
     *
     * @since 1.0.0
     * @return void
     */
    private static function single_deactivate()
    {
        // \flush_rewrite_rules();
    }
}
