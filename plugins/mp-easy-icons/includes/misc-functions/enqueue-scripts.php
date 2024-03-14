<?php
/**
 * This file contains the enqueue scripts function for the icons plugin
 *
 * @since 1.0.0
 *
 * @package    MP Easy Icons
 * @subpackage Functions
 *
 * @copyright  Copyright (c) 2015, Mint Plugins
 * @license    http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @author     Philip Johnston
 */
 
/**
 * Enqueue JS and CSS for icons 
 *
 * @access   public
 * @since    1.0.0
 * @return   void
 */

/**
 * Enqueue css and js
 *
 * Filter: mp_easy_icons_css_location
 */
function mp_easy_icons_enqueue_scripts(){
	
	//Enqueue Font Awesome CSS
	wp_enqueue_style( 'fontawesome', plugins_url( '/fonts/font-awesome/css/font-awesome.css', dirname( __FILE__ ) ) );

}
add_action( 'wp_enqueue_scripts', 'mp_easy_icons_enqueue_scripts' );


/**
 * Enqueue css and js
 *
 * Filter: mp_buttons_css_location
 */
function mp_easy_icons_admin_enqueue_scripts(){
	
	//Enqueue Font Awesome CSS
	wp_enqueue_style( 'fontawesome', plugins_url( '/fonts/font-awesome/css/font-awesome.css', dirname( __FILE__ ) ) );
	
	//mp_core_metabox_css
	wp_enqueue_style( 'mp_core_metabox_css', MP_CORE_PLUGIN_URL . 'includes/css/core/mp-core-metabox.css', MP_CORE_VERSION );
	
}
add_action( 'admin_enqueue_scripts', 'mp_easy_icons_admin_enqueue_scripts' );