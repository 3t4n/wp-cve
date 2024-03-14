<?php
/**
 * @link              https://theme4press.com/widget-box/
 * @since             1.0.0
 * @package           Widget Box Lite
 * @author            Theme4Press
 *
 * @wordpress-plugin
 * Plugin Name:       Widget Box Lite
 * Plugin URI:        https://theme4press.com/widget-box/
 * Description:       A toolbox of great widgets for your daily blogging. Display recent posts, social links and much more. Designed for Theme4Press themes
 * Version:           1.0.0
 * Author:            Theme4Press
 * Author URI:        https://theme4press.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       widget-box-lite
 * Domain Path:       /languages
 */

// If this file is called directly, abort
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * @since    1.0.0
 */
define( 'WIDGET_BOX_LITE_VERSION', '1.0.0' );

/**
 * The class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks
 */
require plugin_dir_path( __FILE__ ) . 'inc/class-widget-box.php';

/**
 * @since    1.0.0
 */
if ( ! function_exists( 'widget_box_lite_run' ) ) {
	function widget_box_lite_run() {
		$plugin = new Widget_Box_Lite();
		$plugin->run();
	}
}

widget_box_lite_run();