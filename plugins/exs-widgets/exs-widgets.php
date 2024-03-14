<?php
/*
Plugin Name: ExS Widgets
Description: Additional extended custom widgets
Version:     0.3.1
Author:      ExS
Author URI:  https://exsthemewp.com/
License:     GPLv2 or later
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( defined( 'EXS_DEV_MODE' ) && EXS_DEV_MODE ) {
	define( 'EXS_WIDGETS_PLUGIN_ASSETS_URL', EXS_THEME_URI . '/dev/extensions/exs-widgets/assets/' );
} else {
	define( 'EXS_WIDGETS_PLUGIN_ASSETS_URL', plugin_dir_url( __FILE__ ) . 'assets/' );
}
define( 'EXS_WIDGETS_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'EXS_WIDGETS_PLUGIN_VERSION', '0.3.1' );

//check - is current theme is our theme.
if ( 'exs' !== get_template() && 'exs-pro' !== get_template() ) :
	//put functions and styles from theme here if our theme is not active
	require_once EXS_WIDGETS_PLUGIN_PATH . '/functions.php';
endif;

//custom widgets
require_once EXS_WIDGETS_PLUGIN_PATH . '/widgets/posts/class-exs-widget-theme-posts.php';
require_once EXS_WIDGETS_PLUGIN_PATH . '/widgets/meta/class-exs-widget-theme-meta.php';
require_once EXS_WIDGETS_PLUGIN_PATH . '/widgets/category/class-exs-widget-theme-category.php';
require_once EXS_WIDGETS_PLUGIN_PATH . '/widgets/spacer/class-exs-widget-theme-spacer.php';


add_action( 'plugins_loaded', 'exs_widgets_load_plugin_textdomain' );
if ( ! function_exists( 'exs_widgets_load_plugin_textdomain' ) ) :
	/**
	 * Load plugin textdomain.
	 */
	function exs_widgets_load_plugin_textdomain() {
		load_plugin_textdomain( 'exs', false, '/exs-widgets/languages' );
	}
endif;
