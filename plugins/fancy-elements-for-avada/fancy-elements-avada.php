<?php
/**
 * Plugin Name:	Fancy Elements for Avada
 * Plugin URI:	https://wordpress.org/plugins/fancy-elements-for-avada/
 * Description:	This plugin is built for avada theme. It adds new fancy elements to the list of Avada builder elements.
 * Version:		1.0.2
 * Author: 		WP Square
 * Author URI: 	https://www.wp-sqr.com/
 * License:     GPL v2 or later
 * License URI:	https://www.gnu.org/licenses/gpl-2.0.txt
 *
 * @package fancy-elements-avada
 */

// Plugin Folder Path.
if ( ! defined( 'FEA_ADDON_PLUGIN_DIR' ) ) {
	define( 'FEA_ADDON_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}


require_once wp_normalize_path( FEA_ADDON_PLUGIN_DIR . '/inc/shortcodes/class-fancy-elements-tabs.php' );
require_once wp_normalize_path( FEA_ADDON_PLUGIN_DIR . '/inc/shortcodes/class-fancy-elements-timeline-v1.php' );
require_once wp_normalize_path( FEA_ADDON_PLUGIN_DIR . '/inc/shortcodes/class-fancy-elements-timeline-v2.php' );
require_once wp_normalize_path( FEA_ADDON_PLUGIN_DIR . '/inc/shortcodes/class-fancy-elements-testimonial.php' );

add_action( 'activate_plugin', 'fea_addon_activation', 10 );

/**
 * Check FussionBuilder Class exist or not.
 */
function fea_addon_activation() {
	if ( ! class_exists( 'FusionBuilder' ) ) {
		$message = '<span>'. __('Avada Builder could not be activated. ') .'</span>';
		$message .= '<span>'. __('Fancy Elements Avada can only be activated if Avada Builder is activated.') .'</span>';
		wp_die( wp_kses_post( $message ) );
	}

}


add_action( 'wp_loaded', 'fea_addon_activate' );

/**
 * Instantiate fancy_elements_avada_Addon_FB class.
 */
function fea_addon_activate() {
	Fancy_Elements_Tabs::get_instance();
	Fancy_Elements_Timeline_V1::get_instance();
	Fancy_Elements_Timeline_V2::get_instance();
	Fancy_Elements_Testimonial::get_instance();
}


/**
 * Include options from options folder.
 *
 * @access public
 * @since 1.1
 * @return void
 */
function fea_init_loader() {

	require_once 'elements/fea-tabs-maping.php';
	require_once 'elements/fea-timeline-maping.php';
	require_once 'elements/fea-timeline2-maping.php';
	require_once 'elements/fea-testimonial-maping.php';

	require_once 'inc/functions.php';

}

add_action( 'fusion_builder_before_init', 'fea_init_loader', 1 );

add_action( 'init', 'fea_load_textdomain' );

/**
 * Load plugin textdomain.
 */
function fea_load_textdomain() {
	load_plugin_textdomain( 'fancy-elements-avada', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}
