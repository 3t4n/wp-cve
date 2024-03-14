<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://wpruby.com
 * @since             1.0.0
 * @package           Controlled_Admin_Access
 *
 * @wordpress-plugin
 * Plugin Name:       Controlled Admin Access
 * Plugin URI:        https://wpruby.com/product/controlled-admin-access
 * Description:       Grant a temporary limited admin access to others.
 * Version:           2.0.15
 * Author:            WPRuby
 * Author URI:        https://wpruby.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       controlled-admin-access
 * Domain Path:       /languages
 */

namespace WPRuby_CAA;

// If this file is called directly, abort.

use WPRuby_CAA\Core\Core;

if ( ! defined( 'WPINC' ) ) {
    die;
}

class WPRuby_Controlled_Admin_Access {

    protected static $_instance = null;

    /**
     * @return WPRuby_Controlled_Admin_Access
     */
    public static function get_instance()
    {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * WPRuby_Controlled_Admin_Access constructor.
     */
    public function __construct()
    {
        Core::get_instance();
    }
}

register_activation_hook( __FILE__, function () {
    $active_plugins = apply_filters('active_plugins', get_option('active_plugins'));
    if (in_array('controlled-admin-access-pro/controlled-admin-access-pro.php', $active_plugins)) {
        deactivate_plugins('controlled-admin-access-pro/controlled-admin-access-pro.php');
    }
} );

require_once dirname(__FILE__ ) . '/includes/autoloader.php';

WPRuby_Controlled_Admin_Access::get_instance();
