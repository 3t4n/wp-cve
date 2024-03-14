<?php
/**
 * Plugin Name:       Weather Widget WP
 * Plugin URI:        https://ajdethemes.com/weather-widget-wp/
 * Description:       Display weather information for a specific location.
 * Version:           1.0.0
 * Stable tag:        1.0.0
 * Requires at least: 5.0
 * Tested up to:      6.1
 * Requires PHP:      5.6 or higher
 * Author:            Ajdethemes
 * Author URI:        https://ajdethemes.com/
 * License:           GPL v3
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       weather-widget-wp
 * Domain Path:       /languages
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly.


/**
 * Constants & Globals
 *
 * @since 1.0.0
 */
define( 'WEATHER_WIDGET_WP_VERSION', '1.0.0');
define( 'WEATHER_WIDGET_WP_PATH', plugin_dir_path( __FILE__ ));
define( 'WEATHER_WIDGET_WP_URL', plugin_dir_url( __FILE__ ));


/**
 * Load Textdomain
 *
 * Load plugin localization files.
 *
 * @since 1.0.0
 **/
function weather_widget_wp_load_plugin_textdomain() {
    load_plugin_textdomain( 'weather-widget-wp', false, WEATHER_WIDGET_WP_PATH . '/languages/' );
}
add_action( 'plugins_loaded', 'weather_widget_wp_load_plugin_textdomain' );


/**
 * Imports
 *
 * @since 1.0.0
 **/
require_once WEATHER_WIDGET_WP_PATH . 'includes/enqueued.php';
require_once WEATHER_WIDGET_WP_PATH . 'includes/shortcodes.php';
require_once WEATHER_WIDGET_WP_PATH . 'includes/helpers.php';
require_once WEATHER_WIDGET_WP_PATH . 'admin/settings-page.php';
