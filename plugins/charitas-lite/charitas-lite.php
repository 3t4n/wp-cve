<?php
/**
 * Plugin Name: Charitas Lite
 * Plugin URI: https://www.wplook.com/?utm_source=wordpress_org&utm_medium=plugin&utm_campaign=Charitas_lite_plugin
 * Description: Charitas Lite WPLook will Extend the Charitas Lite WordPress Theme.
 * Author: Victor Tihai
 * Author URI: https://www.wplook.com/?utm_source=plugin_author_uri&utm_medium=link&utm_campaign=Charitas_lite_plugin
 * Version: 1.0.1
 * Text Domain: charitas-lite
 * License: GPL2+
 * License URI: https://www.gnu.org/licenses/gpl-2.0.txt
 *
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

	class Charitas_Lite {

	/**
	* Constructor
	*
	* Add actions for methods that define constants, load translation and load includes.
	*
	* @since 1.0.0
	* @access public
	*/
	public function __construct() {

		// Load common functions
		require_once( 'inc/customizer.php' );
		require_once( 'inc/library.php' );
		require_once( 'inc/widgets/widget-projects.php' );
		require_once( 'inc/widgets/widget-staff.php' );
		require_once( 'inc/widgets/widget-causes.php' );

		// Define constants
		add_action( 'plugins_loaded', array( $this, 'Charitas_Lite_define_constants' ), 1 );

		// Load language file
		add_action( 'plugins_loaded', array( $this, 'Charitas_Lite_load_textdomain' ), 2 );

		add_action( 'after_setup_theme', array( $this, 'Charitas_Lite_load_post_types'), 3 );

		// Add setings link
		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'Charitas_Lite_page_links' ), 1 );

	}

	/**
	* Defines constants used by the plugin.
	*
	* @since 1.0.0
	* @access public
	*/
	public function Charitas_Lite_define_constants() {
		define( 'CHARITAS_LITE_NAME', __( 'Charitas Lite Plugin by WPlook', 'charitas-lite' ) );
		define( 'CHARITAS_LITE_VERSION', "1.0.0" );
		define( 'CHARITAS_LITE_URI', trailingslashit( plugin_dir_url( __FILE__ ) ) );
		define( 'CHARITAS_LITE_DIR', trailingslashit( plugin_dir_path( __FILE__ ) ) );
		define( 'CHARITAS_LITE_PROJECTS', true );
		define( 'CHARITAS_LITE_CAUSES', true );
		define( 'CHARITAS_LITE_STAFF', true );
	}


	/**
	* Include all files
	*
 	* @since 1.0.0
	* @access public
	*/
	public function Charitas_Lite_load_post_types() {
		if ( defined( 'CHARITAS_LITE_PROJECTS' ) && CHARITAS_LITE_PROJECTS ) {
			require_once( CHARITAS_LITE_DIR . 'custom-post-type/projects.php' );
			new Charitas_Lite_Projects_CPT();
		}

		if ( defined( 'CHARITAS_LITE_CAUSES' ) && CHARITAS_LITE_CAUSES ) {
			require_once( CHARITAS_LITE_DIR . 'custom-post-type/causes.php' );
			new Charitas_Lite_Causes_CPT();
		}

		if ( defined( 'CHARITAS_LITE_STAFF' ) && CHARITAS_LITE_STAFF ) {
			require_once( CHARITAS_LITE_DIR . 'custom-post-type/staff.php' );
			new Charitas_Lite_Staff_CPT();
		}
	}


	/**
	* Load language file from /languages/
	*
	* @since 1.0.0
	* @access public
	*/
	public function Charitas_Lite_load_textdomain() {
		load_plugin_textdomain( 'charitas-lite', false, dirname( plugin_basename(__FILE__) ) . '/languages/' );
	}

	/**
	* Add settings links to plugin page
	*
	* @since 1.0.0
	* @access public
	*/
	public function Charitas_Lite_page_links( $links ) {

		$links[] = '<a href="https://wplook.com/help/?utm_source=Plugins&utm_term='.str_replace(" ", "", CHARITAS_LITE_VERSION).'&utm_medium=Support_wp-admin&utm_campaign=Charitas_lite_plugin" target="_blank">' . __( 'Support', 'charitas-lite' ) . '</a>';
		return $links;

	}
}

new Charitas_Lite();
