<?php
/*
Plugin Name: SoulMatch
Plugin URI: https://gingersoulrecords.com/soulmatch
Description: Equalize the heights of grouped elements.
Version: 0.1.2
Author: Dave Bloom
Author URI: http://gingersoulrecords.com
Text Domain: soulmatch
*/

if ( ! class_exists( 'SoulMatch' ) ) {
	add_action( 'plugins_loaded', array( 'SoulMatch', 'init' ) );

	class SoulMatch {
		public static $options = array(
			'a' => 'test',
			'b'	=> 'test2',
			'lists_check'	=> true,
		);
		public static $settings = false;
		public static $plugin_path = '';
		public static function init() {
			self::$plugin_path = plugin_dir_path( __FILE__ );

			add_action( 'wp_enqueue_scripts', 		array( 'SoulMatch', 'scripts' ) );
			add_action( 'admin_enqueue_scripts', 	array( 'SoulMatch', 'admin_scripts' ) );

			add_action( 'wp_enqueue_scripts', 		array( 'SoulMatch', 'styles' ) );
			add_action( 'admin_enqueue_scripts', 	array( 'SoulMatch', 'admin_styles' ) );

			// SoulRepeater v0.1.0
			add_action( 'plugins_loaded', array( 'SoulMatch', 'init_repeater' ), 9999 - 0010 );

		}

		public static function scripts() {
			wp_register_script( 'matchheight', plugins_url( 'js/jquery.matchHeight-min.js', __FILE__ ), array( 'jquery' ), false, true );
			wp_register_script( 'soulmatch', plugins_url( 'soulmatch.js', __FILE__ ), array( 'jquery','matchheight' ), false, true );
			$data = array(
				'options'	=> get_option('soulmatch_repeater'),
			);
			wp_localize_script( 'soulmatch', 'soulmatch_data', $data );
			wp_add_inline_script( 'soulmatch', 'var soulmatch_after = "";', 'after' );
			wp_enqueue_script( 'soulmatch' );
		}
		public static function admin_scripts() {
			$screen = get_current_screen();
			if ( 'settings_page_soulmatch-settings' != $screen->id) {
				return false;
			}

			wp_register_script( 'soulmatch-admin', plugins_url( 'soulmatch-admin.js', __FILE__ ), array( 'jquery' ), false, true );
			$data = array(
				'options'	=> self::$options,
			);
			wp_localize_script( 'soulmatch-admin', 'soulmatch_admin_data', $data );
			wp_add_inline_script( 'soulmatch-admin', 'var soulmatch_admin_after = "";', 'after' );
			wp_enqueue_script( 'soulmatch-admin' );
		}

		public static function styles() {
			// wp_register_style( 'somestyle', plugins_url( 'css/somestyle.css', __FILE__ ), array( 'dashicons' ) );
			//wp_register_style( 'soulmatch', plugins_url( 'soulmatch.css', __FILE__ ), array( 'dashicons' ) );
			//wp_add_inline_style( 'soulmatch', '.soulmatch { color:blue; }' );
			//wp_enqueue_style( 'soulmatch' );
		}
		public static function admin_styles() {
			$screen = get_current_screen();
			if ( 'settings_page_soulmatch-settings' != $screen->id) {
				return false;
			}
			// wp_register_style( 'somestyle', plugins_url( 'css/somestyle.css', __FILE__ ), array( 'dashicons' ) );
			wp_register_style( 'soulmatch-admin', plugins_url( 'soulmatch-admin.css', __FILE__ ), array( 'dashicons' ) );
			wp_add_inline_style( 'soulmatch-admin', '.soulmatch { color:blue; }' );
			wp_enqueue_style( 'soulmatch-admin' );
		}

		public static function init_repeater() {
			$settings = array(
				'links' => array(
					'file'	=> plugin_basename( __FILE__ ),
					'links' => array(
						array(
							'title'	=> __( 'Settings', 'soulmatch' ),
						),
					),
				),
				'page' => array(
					'title' 			=> __( 'SoulMatch Settings', 'soulmatch' ),
					'menu_title'	=> __( 'SoulMatch', 'soulmatch' ),
					'slug' 				=> 'soulmatch-settings',
					'option'			=> 'soulmatch_repeater',
					// optional.
					'description'	=> __( 'Some general information about the plugin', 'soulmatch' ),
				),
				'fields' => array(
					'selector' => array(
						'title'	=> __( 'Selector', 'soulmatch' ),
						'attributes'	=> array(
							'placeholder'	=> __( 'Selector', 'soulmatch' ),
						),
					),
					'byrow' => array(
						'title'	=> __( 'By Row', 'soulmatch' ),
						'label'	=> __( 'Check this to only equalize heights if the selected elements are in the same row.', 'soulmatch' ),
						'callback'	=> 'checkbox',
					),
				),
				'l10n' => array(
					'no_access'			=> __( 'You do not have sufficient permissions to access this page.', 'soulmatch' ),
					'save_changes'	=> esc_attr__( 'Save Changes', 'soulmatch' ),
					'add_repeater'	=> __( 'Add Repeater', 'soulmatch' ),
					'delete_repeater'	=> __( 'Delete Repeater', 'soulmatch' ),
					'repeater'			=> __( 'Repeater', 'soulmatch' ),
					'save_success'	=> __( 'Saved successfully.', 'soulmatch' ),
					'nonce_error'		=> __( 'Nonce verification failed.', 'soulmatch' ),
				),
			);
			require_once( self::$plugin_path . 'tiny/soul.repeater.php' );
			$settings = new SoulRepeater( $settings, __CLASS__ );
		}
	}
} // End if().
