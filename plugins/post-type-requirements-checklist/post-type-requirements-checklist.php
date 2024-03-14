<?php
/**
 * Post Type Requirements Checklist.
 *
 * Help Clients Help Themselves
 *
 * @package   Post_Type_Requirements_Checklist
 * @author    Dave Winter
 * @license   GPL-2.0+
 * @link      http://dauid.us
 * @copyright 2014-2015 dauid.us
 *
 * @wordpress-plugin
 * Plugin Name:       Requirements Checklist
 * Plugin URI:        http://dauid.us
 * Description:       Allows admins to set required content to be entered before a page/post or custom post type can be published.
 * Version:           2.4
 * Author:            Dave Winter
 * Author URI:        http://dauid.us
 * Text Domain: 	  aptrc
 * Domain Path:		  /languages
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * GitHub Plugin URI: https://github.com/dauidus/post-type-requirements-checklist
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/*----------------------------------------------------------------------------*
 * Public-Facing Functionality
 *----------------------------------------------------------------------------*/

require_once( plugin_dir_path( __FILE__ ) . 'public/class-post-type-requirements-checklist.php' );

/*
 * Register hooks that are fired when the plugin is activated or deactivated.
 * When the plugin is deleted, the uninstall.php file is loaded.
 *
 */
register_activation_hook( __FILE__, array( 'Post_Type_Requirements_Checklist', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Post_Type_Requirements_Checklist', 'deactivate' ) );

add_action( 'plugins_loaded', array( 'Post_Type_Requirements_Checklist', 'get_instance' ) );

/**
 * Load translations
 */
function aptrc_load_textdomain() {
	load_plugin_textdomain( 'aptrc', false, dirname(plugin_basename(__FILE__)) . '/languages/' );
}
add_action( 'init', 'aptrc_load_textdomain', 1 );



/*----------------------------------------------------------------------------*
 * Dashboard and Administrative Functionality
 *----------------------------------------------------------------------------*/

/*
 *
 * The code below is intended to to give the lightest footprint possible.
 */
if ( is_admin() ) {

	require_once( plugin_dir_path( __FILE__ ) . 'admin/class-post-type-requirements-checklist-admin.php' );
	add_action( 'plugins_loaded', array( 'Post_Type_Requirements_Checklist_Admin', 'get_instance' ) );

}


