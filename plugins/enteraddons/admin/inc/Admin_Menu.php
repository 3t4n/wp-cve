<?php
namespace Enteraddons\Admin;

/**
 * Enteraddons admin class
 *
 * @package     Enteraddons
 * @author      ThemeLooks
 * @copyright   2022 ThemeLooks
 * @license     GPL-2.0-or-later
 *
 *
 */

if( !class_exists('Admin_Menu') ) {
	class Admin_Menu{

		private static $instance = null;

		function __construct() {
			add_action( 'admin_menu', array( __CLASS__, 'admin_menu_page' ) );
			add_action( 'admin_init', array( __CLASS__, 'page_settings_init' ) );
		}

		public static function getInstance() {
			if( is_null( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		public static function admin_menu_page() {
			$is_active = get_option(ENTERADDONS_OPTION_KEY);
			// add top level menu page
			add_menu_page(
			 esc_html__( 'Enter Addons Settings', 'enteraddons' ),
			 esc_html__( 'Enter Addons', 'enteraddons' ),
			 'manage_options',
			 'enteraddons',
			 array( __CLASS__, 'admin_view' ),
			 ENTERADDONS_DIR_ASSETS_URL.'menu-icon.png'
			);
			add_submenu_page( 'enteraddons', esc_html__( 'Enter Addons', 'enteraddons' ), esc_html__( 'Enter Addons', 'enteraddons' ),'manage_options', 'enteraddons' );
			// Check Is Header Footer Active
			$extensions = !empty( $is_active['extensions'] ) ? $is_active['extensions'] : [];
			if( in_array( 'header-footer' , $extensions ) ) {
				add_submenu_page(
			        'enteraddons',
			        esc_html__( 'Header Footer Builder', 'enteraddons' ), //page title
			        esc_html__( 'Header Footer Builder', 'enteraddons' ), //menu title
			        'manage_options', //capability,
			        'edit.php?post_type=ea_builder_template',//menu slug  
			    );
		    }
		    // Do action hook "After admin menu"
		    do_action('ea_after_admin_menu');
		}
		public static function admin_view() {
			$view = new \Enteraddons\Admin\Admin_Templates_Map();
			$view->admin_page_init();
		}
		public static function page_settings_init() {
			register_setting(
	            'enteraddons_settings_option_group', // Option group
	            'enteraddons_options' // Option name
	        );  
		}

	}

}