<?php
/**
 * Plugin Name:  VI Random Posts
 * Plugin URI:   https://wordpress.org/plugins/vi-random-posts-widget/
 * Description:  Display Your Post With Highly Customising Options Using Widget and Shortcode 
 * Version:      1.1
 * Author:       Team Startbit
 * Author URI:   https://www.startbitsolutions.com/
 */
/*
	
	Copyright 2014  Startbit IT Solutions Pvt. Ltd.  (email : support@startbitsolutions.com)
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

	 You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
	
	*/
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

require_once dirname( __FILE__ ) . '/includes/resizer.php';
require_once dirname( __FILE__ ) . '/includes/functions.php';
require_once dirname( __FILE__ ) . '/includes/shortcode.php';

class VIRP_Widget {
	/**
	 * Construct the primary class and auto-load all child classes
	 */
	public function __construct() {

		add_action( 'plugins_loaded', array( &$this, 'virp_constants' ), 1 );

		//init language translations
		add_action('init', array( &$this, 'virp_load_viva_trans' ), 1);

		// Load the admin style.
		add_action( 'admin_enqueue_scripts', array( &$this, 'virp_admin_style' ) );

		// Register widget.
		add_action( 'widgets_init', array( &$this, 'virp_register_widget' ) );

		// Register new image size.
		add_action( 'init', array( &$this, 'virp_default_image_size' ) );

		// Enqueue the front-end style.
		add_action( 'wp_enqueue_scripts', array( &$this, 'virp_css' ) );

	}

	/**
		Declaring Constants used in Plugin
	 */
	public function virp_constants() {

		// Set constant path to the plugin directory.
		define( 'VIRP_DIR', trailingslashit( plugin_dir_path( __FILE__ ) ) );

		// Set the constant path to the plugin directory URI.
		define( 'VIRP_URI', trailingslashit( plugin_dir_url( __FILE__ ) ) );

		// Set the constant path to the includes directory.
		define( 'VIRP_INC', VIRP_DIR . trailingslashit( 'includes' ) );

		// Set the constant path to the assets directory.
		define( 'VIRP_STYLE', VIRP_URI . trailingslashit( 'css' ) );

	}
	/**
	 * Init language translatoin support
	 */
   function virp_load_viva_trans()
   {
       load_plugin_textdomain('virp', FALSE, dirname(plugin_basename(__FILE__)).'/languages/');
   }

	function virp_admin_style() {

		// if Not Widget Page return.
		if ( 'widgets' != get_current_screen()->base ) {
			return;
		}

		// Loads the widget style.
		wp_enqueue_style( 'virp-admin-style', trailingslashit( VIRP_STYLE ) . 'virp-admin.css', array(), null );

	}

	/**
	 * Register Random Post Widget.
	 */
	function virp_register_widget() {
		require_once( VIRP_INC . 'widget.php' );
		register_widget( 'VI_Random_Posts' );
	}

	/**
	 * Register default image size.
	 */
	function virp_default_image_size() {
		add_image_size( 'virp-thumbnail', 50, 50, true );
	}

	/**
	 * Enqueue front-end style.
	 */
	function virp_css() {
		wp_enqueue_style( 'virp-style', trailingslashit( VIRP_STYLE ) . 'virp-frontend.css', array(), null );
		global $wp_styles;
  		$srcs = array_map('basename', (array) wp_list_pluck($wp_styles->registered, 'src') );
  		if( in_array('font-awesome.css', $srcs) || in_array('font-awesome.min.css', $srcs)  ) {
  	  		 		/* echo 'font-awesome.css registered'; */
 		} else {
   	 wp_enqueue_style('font-awesome', trailingslashit( VIRP_STYLE ) . 'font-awesome.css', array(), null );
  		}
	}

}
new VIRP_Widget;
