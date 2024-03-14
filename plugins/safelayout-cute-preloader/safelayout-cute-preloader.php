<?php
/*
Plugin Name: Safelayout Cute Preloader
Plugin URI: https://safelayout.com
Description: Easily add a pure CSS animated preloader to your WordPress website.
Version: 2.0.50
Author: Safelayout
Text Domain: safelayout-cute-preloader
Domain Path: /languages
License: GPLv2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/


defined( 'ABSPATH' ) || exit; // Exit if accessed directly.


if ( ! class_exists( 'Safelayout_Preloader' ) && ! class_exists( 'Safelayout_Preloader_Pro' ) ) {

	// Define the constant used in this plugin
	define( 'SAFELAYOUT_PRELOADER_VERSION', '2.0.50');
	define( 'SAFELAYOUT_PRELOADER_NAME', plugin_basename( __FILE__ ) );
	define( 'SAFELAYOUT_PRELOADER_PATH', plugin_dir_path( __FILE__ ) );
	define( 'SAFELAYOUT_PRELOADER_URL', plugin_dir_url( __FILE__ ) );

	// Return default options
	function safelayout_preloader_get_default_options() {
		$default = array(
			'version'					=> SAFELAYOUT_PRELOADER_VERSION,
			'id'						=> 13592211,
			'enable_preloader'			=> 'enable',
			'display_on'				=> 'full',
			'specific_IDs'				=> '',
			'specific_names'			=> '',
			'device'					=> 'all',
			'close_button'				=> 5,
			'minimum_time'				=> 0,
			'maximum_time'				=> 0,
			'background_anim'			=> 'linear-right',
			'background_color_type'		=> 'solid',
			'background_color_value'	=> '#101010',
			'background_gradient_value'	=> 6,
			'background_alpha'			=> 95,
			'icon'						=> 'triple-spinner',
			'custom_icon'				=> '',
			'custom_icon_alt'			=> '',
			'custom_icon_width'			=> 0,
			'custom_icon_height'		=> 0,
			'icon_size'					=> 50,
			'icon_color_type'			=> 'solid',
			'icon_color_value'			=> '#4285f4',
			'icon_gradient_value'		=> 13,
			'icon_effect'				=> 1,
			'text'						=> esc_html__( 'Loading ...', 'safelayout-cute-preloader' ),
			'text_anim'					=> 'zoom',
			'text_size'					=> 14,
			'text_color'				=> '#fff',
			'text_margin_top'			=> 0,
			'brand_url'					=> '',
			'brand_url_alt'				=> '',
			'brand_width'				=> 0,
			'brand_height'				=> 0,
			'brand_anim'				=> 'No',
			'brand_position'			=> 'top',
			'brand_margin_top'			=> 0,
			'brand_margin_bottom'		=> 0,
			'bar_shape'					=> 'anim-border-stripe-bar',
			'bar_light'					=> 'enable',
			'bar_position'				=> 'middle_under_text',
			'bar_width'					=> 220,
			'bar_width_unit'			=> 'px',
			'bar_height'				=> 10,
			'bar_border_radius'			=> 10,
			'bar_border_color'			=> '#0f0',
			'bar_margin_top'			=> 0,
			'bar_margin_bottom'			=> 0,
			'bar_margin_left'			=> 0,
			'bar_color_type'			=> 'solid',
			'bar_color_value'			=> '#4285f4',
			'bar_gradient_value'		=> 10,
			'counter'					=> 'enable',
			'counter_text'				=> '%',
			'counter_position'			=> 'center',
			'counter_size'				=> 12,
			'counter_margin_top'		=> 0,
			'counter_margin_bottom'		=> 0,
			'counter_margin_left'		=> 0,
			'counter_color'				=> '#fff',
			'special_meta'				=> '',
			'ui_tabs_activate'			=> 4,
		);
		return $default;
	}

	// Return preloader options
	function safelayout_preloader_get_options() {
		$options = wp_parse_args(
			get_option( 'safelayout_preloader_options', array() ),
			safelayout_preloader_get_default_options()
		);
		return $options;
	}

	class Safelayout_Preloader {
		public function __construct() {
			load_plugin_textdomain( 'safelayout-cute-preloader', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

			add_filter( 'plugin_action_links_' . SAFELAYOUT_PRELOADER_NAME, array( $this, 'plugin_action_links' ) );
			add_action( 'activated_plugin', array( $this, 'redirect_settings' ) );

			if ( is_admin() ){
				// Load the admin related functions
				require_once SAFELAYOUT_PRELOADER_PATH . 'inc/class-safelayout-preloader-admin.php';
			} else {
				// Load the front end related functions
				add_action( 'wp', array( $this, 'start_plugin_front' ) );
			}
		}

		// Redirect to settings page
		public function redirect_settings( $plugin ) {
			if( $plugin == plugin_basename( __FILE__ ) ) {
				$option = get_option( 'safelayout_preloader_options_rate' );
				if ( ! $option ) {
					exit( wp_redirect( admin_url( 'options-general.php?page=safelayout-cute-preloader' ) ) );
				}
			}
		}

		// Load the front end related functions
		public function start_plugin_front() {
			require_once SAFELAYOUT_PRELOADER_PATH . 'inc/class-safelayout-preloader-front.php';
		}

		// Add settings link on plugin page
		public function plugin_action_links( $links ) {
			$settings_link = array(
				'<a href="' . admin_url( 'options-general.php?page=safelayout-cute-preloader' ) . '">' . esc_html__( 'Settings', 'safelayout-cute-preloader' ) . '</a>',
			);
			$links = array_merge( $links, $settings_link );
			return $links;
		}
	}
	new Safelayout_Preloader();
}