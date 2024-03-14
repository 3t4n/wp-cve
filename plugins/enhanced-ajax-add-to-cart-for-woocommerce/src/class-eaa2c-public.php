<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       www.theritesites.com
 * @since      1.0.0
 * @package    Enhanced_Ajax_Add_To_Cart_Wc
 * @subpackage Enhanced_Ajax_Add_To_Cart_Wc/public
 * @author     TheRiteSites <contact@theritesites.com>
 */

namespace TRS\EAA2C;

if ( ! class_exists( 'TRS\EAA2C\Front' ) ) {
	class Front {

		/**
		 * The version of this plugin.
		 *
		 * @since    1.0.0
		 * @access   private
		 * @var      string    $version    The current version of this plugin.
		 */
		private $version;

		/**
		 * Initialize the class and set its properties.
		 *
		 * @since    1.0.0
		 * @param      string    $version    The version of this plugin.
		 */
		public function __construct() {
			$this->version = ENHANCED_AJAX_ADD_TO_CART;
		}

		/**
		 * Register the stylesheets for the public-facing side of the site.
		 *
		 * @since    2.0.0
		 */
		public function register_styles() {

			$path = realpath( dirname( __DIR__ ) );
			if ( file_exists( $path . '/assets/css/enhanced-ajax-add-to-cart-wc-public.min.css' ) && ! ( EAA2C_DEBUG || WP_DEBUG ) ) {
				$css_file =  plugin_dir_url( __DIR__ ) . 'assets/css/enhanced-ajax-add-to-cart-wc-public.min.css';
			}
			else
				$css_file =  plugin_dir_url( __DIR__ ) . 'assets/css/enhanced-ajax-add-to-cart-wc-public.css';

			if ( ! empty( $css_file ) && defined( 'EAA2C_NAME' ) ) {
				wp_register_style( EAA2C_NAME, $css_file, array(), $this->version, 'all' );
			}
		}

		/**
		 * Register the JavaScript for the public-facing side of the site.
		 *
		 * @since    2.0.0
		 */
		public function register_scripts() {

			$plugin = defined( 'EAA2C_NAME' ) ? EAA2C_NAME : '';
			$index_js = 'eaa2c-submit.js';
			$js_file =  plugin_dir_url( __DIR__ ) . 'assets/js/enhanced-ajax-add-to-cart-wc-public.js';

			$path = realpath( dirname( __DIR__ ) ) . '/dist/request/';
			if ( file_exists( $path . $index_js ) && ! ( EAA2C_DEBUG || WP_DEBUG ) ) {
				$dir = plugin_dir_path( dirname( __FILE__ ) ) . 'dist/request/';
				$js_file =  plugins_url( $index_js, $dir .'request/' );
			}

			if ( ! empty( $js_file ) && defined( 'EAA2C_NAME' ) ) {
				$blocking = get_option( 'a2cp_button_blocking', false );
				if ( strcmp( $blocking, 'on' ) === 0 || strcmp( $blocking, 'true' ) === 0 ) {
					$blocking = true;
				}

				$dom_check = get_option( 'a2cp_dom_check', false );
				if ( strcmp( $dom_check, 'on' ) === 0 || strcmp( $dom_check, 'true' ) === 0 ) {
					$dom_check = true;
				}

				$after_text = get_option( 'a2cp_after_add_text', '' );
				if ( ! empty( $after_text ) ) {
					$after_text = esc_html( $after_text );
				} /*else {
					$after_url = false;
				}*/

				$after_url = get_option( 'a2cp_after_add_url', '' );
				if ( ! empty( $after_url ) ) {
					$after_url = esc_url( $after_url );
				} /*else {
					$after_url = false;
				}*/

				$stop_rf = get_option( 'a2cp_stop_refresh_frags', false );
				if ( strcmp( $stop_rf, 'on' ) === 0 || strcmp( $stop_rf, 'true' ) === 0 ) {
					$stop_rf = true;
				}

				wp_register_script( EAA2C_NAME . '-js-bundle' , $js_file, array( 'jquery', 'wc-add-to-cart' ), $this->version, false );
				
				wp_localize_script( EAA2C_NAME . '-js-bundle', 'EAA2C', array(
					'ajax_url'	=> admin_url( 'admin-ajax.php', 'relative' ),
					'blocking'	=> $blocking,
					'debug'		=> EAA2C_DEBUG,
					'domCheck'	=> $dom_check,
					'afterAddText' => $after_text,
					'afterAddUrl' => $after_url,
					'stopRefreshFrags' => $stop_rf,
				));
			}
		}

		/**
		 * Register the JavaScript for the public-facing side of the site.
		 *
		 * @since      1.0.0
		 * @deprecated Since 2.0. Use register_scripts instead.
		 */
		public function enqueue_scripts() {
			_deprecated_function( __FUNCTION__, '2.0', 'register_scripts' );
			$this->register_scripts();
		}

		/**
		 * Register the stylesheets for the public-facing side of the site.
		 *
		 * @since      1.0.0
		 * @deprecated Since 2.0. Use register_styles instead.
		 */
		public function enqueue_styles() {
			_deprecated_function( __FUNCTION__, '2.0', 'register_styles' );
			$this->register_styles();
		}
	}
}