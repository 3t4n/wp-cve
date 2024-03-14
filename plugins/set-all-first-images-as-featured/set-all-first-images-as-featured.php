<?php
/**
Plugin Name: Set All First Images As Featured
Description: Sets the first image of your posts, pages or custom post types as the featured image.
Version: 	 1.2.2
Author: 	 Lucy Tomás
Author URI:  https://wordpress.org/support/profile/lucymtc
License: 	 GPLv2
*/
 
 /* Copyright 2014 Lucy Tomás (email: lucy@wptips.me)
  
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

 // If this file is called directly, exit.
if ( !defined( 'ABSPATH' ) ) exit;

if( !class_exists('SFIF') ) {
	
	/**
	 * Main class
	 * @since   1.0
	 */
	
final class SFIF {

		private static $instance = null;
	
		public $default_options = array();
		
		/**
		 * Instance
		 * This functions returns the only one true instance of the plugin main class
		 * 
		 * @return object instance
		 * @since  1.0
		 */
		
		public static function instance (){
			
			if( self::$instance == null ){
					
				self::$instance = new SFIF;
				self::$instance->constants();
				self::$instance->includes();
				self::$instance->load_textdomain();
			}
			
			return self::$instance;
		}
		
		/**
		 * Class Contructor
		 * 
		 * @since 1.0
		 */

		 private function __construct () {
		 
			$this->default_options = array(
				'run_for' => 'post',
				'overwrite' => false
			);
			
			ini_set('max_execution_time', 300);
			
			add_action( 'admin_init', array( 'Sfif_Core', 'register_plugin_settings' ));
			add_action( 'admin_menu', array( 'Sfif_Core', 'add_admin_menu_links' ));
			
			add_action('wp_ajax_sfif_request', array('Sfif_Core', 'search_and_update'));
			add_action('wp_ajax_nopriv_sfif_request', array('Sfif_Core', 'search_and_update'));
			
			add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array('Sfif_Core', 'add_action_links') );
		 }
		 
		
		 /**
		  * includes
		  * 
		  * @since 1.0
		  */
		  
		  private function includes () {
		  	
			require_once( SFIF_PLUGIN_DIR . '/includes/class-core.php');
			
			
		  }

		
	     /**
		  * constants
		  * @since 1.0
		  */
		  
		  private function constants () {
		  	
		  	if( !defined('SFIF_PLUGIN_DIR') )  { define('SFIF_PLUGIN_DIR', plugin_dir_path( __FILE__ )); }
			if( !defined('SFIF_PLUGIN_URL') )  { define('SFIF_PLUGIN_URL', plugin_dir_url( __FILE__ ));  }
			if( !defined('SFIF_PLUGIN_FILE') ) { define('SFIF_PLUGIN_FILE',  __FILE__ );  }
			if( !defined('SFIF_PLUGIN_VERSION') )  { define('SFIF_PLUGIN_VERSION', '1.2.2');  } 
			
		  }
		
		/**
		 * load_textdomain
		 * @since 1.0
		 */
		public function load_textdomain() {
			
			load_plugin_textdomain('sfif_domain', false,  dirname( plugin_basename( SFIF_PLUGIN_FILE ) ) . '/languages/' );	
	 	}
		
		
}// class
	
	
}// if !class_exists


SFIF::instance();
 

