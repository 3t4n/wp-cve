<?php
/**
 * Plugin Name: Shortcode for OpenTable
 * Plugin URI:  https://wordpress.org/plugins/shortcode-for-opentable/
 * Description: Embed the official OpenTable widget via configurable shortcode.
 * Author:      ThemeBright
 * Author URI:  https://themebright.com/
 * Version:     1.0.0
 * Text Domain: shortcode-for-opentable
 * Domain Path: /shortcode-for-opentable/languages/
 * License:     GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

defined( 'ABSPATH' ) || exit;

function sot_init() {

	if ( ! defined( 'SOT_VERSION' ) ) define( 'SOT_VERSION', '1.0.0' );
	if ( ! defined( 'SOT_PATH' ) )    define( 'SOT_PATH',    plugin_dir_path( __FILE__ ) );
	if ( ! defined( 'SOT_URL' ) )     define( 'SOT_URL',     esc_url( plugin_dir_url( __FILE__ ) ) );

	require_once SOT_PATH . 'shortcode-for-opentable/includes/admin.php';
	require_once SOT_PATH . 'shortcode-for-opentable/includes/shortcodes.php';

}
add_action( 'plugins_loaded', 'sot_init' );
