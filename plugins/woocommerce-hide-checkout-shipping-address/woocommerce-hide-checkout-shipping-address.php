<?php
	/**
	 * Plugin Name: WooCommerce Hide Checkout Shipping Address
	 * Description: Hide the shipping address form fields for specific shipping methods during checkout
	 * Version: 1.3
	 * Author: Web Whales
	 * Author URI: https://webwhales.nl
	 * Contributors: ronald_edelschaap
	 * License: GPLv3
	 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
	 * Text Domain: wc-hcsa
	 * Domain Path: /languages
	 *
	 * Requires at least: 4.0
	 * Tested up to: 4.7
	 *
	 * @author   Web Whales
	 * @package  WooCommerce Hide Checkout Shipping Address
	 * @category WooCommerce
	 * @version  1.3
	 * @requires WooCommerce version 2.2.0
	 */

	if ( ! defined( 'ABSPATH' ) ) {
		exit; // Exit if accessed directly
	}

	/**
	 * WooCommerce Hide Checkout Shipping Address Class
	 *
	 * Class WC_HCSA
	 */
	final class WC_HCSA {

		const PLUGIN_NAME = 'WooCommerce Hide Checkout Shipping Address',
			PLUGIN_PREFIX = 'woocommerce_hcsa_',
			PLUGIN_VERSION = '1.3',
			TEXT_DOMAIN = 'wc-hcsa',
			WC_REQUIRED_VERSION = '2.2.0';

		/**
		 * @var array
		 */
		private $default_settings = array();

		/**
		 * @var array
		 */
		private $settings = array();

		/**
		 * @var WC_Shipping_Method[]
		 */
		private $shipping_methods;

		/**
		 * @var WC_HCSA|WC_HCSA_Old
		 */
		private static $instance;

		/**
		 * Class constructor
		 */
		private function __construct() {
			$this->init();

			add_action( 'init', array( $this, 'wc_init' ), 11 );

			do_action( 'wc_hcsa_loaded' );
		}

		/**
		 * Add a new option
		 *
		 * @see add_option()
		 */
		public function add_option( $option, $value = '', $autoload = 'yes' ) {
			return add_option( self::PLUGIN_PREFIX . $option, $value, '', $autoload );
		}

		/**
		 * Removes option by name. Prevents removal of protected WordPress options.
		 *
		 * @see delete_option()
		 */
		public function delete_option( $option ) {
			return delete_option( self::PLUGIN_PREFIX . $option );
		}

		/**
		 * Retrieve option value based on name of option.
		 *
		 * @see get_option()
		 */
		public function get_option( $option, $default = false ) {
			return get_option( self::PLUGIN_PREFIX . $option, $default );
		}

		/**
		 * Plugin activator
		 *
		 * @return void
		 */
		public function plugin_activate() {
			$this->load_plugin_default_settings();
			$this->plugin_install();
		}

		/**
		 * Update the value of an option that was already added.
		 *
		 * @see update_function()
		 */
		public function update_option( $option, $value ) {
			return update_option( self::PLUGIN_PREFIX . $option, $value );
		}

		/**
		 * Adjust the order review page if necessary
		 *
		 * @since 1.1
		 *
		 * @return void
		 */
		public function wc_adjust_order_review_page() {
			//Get saved settings from the admin pages
			$this->load_plugin_settings();

			add_filter( 'woocommerce_order_hide_shipping_address', array( 'WC_HCSA', 'filter_woocommerce_order_hide_shipping_address' ), 99 );
		}

		/**
		 * Remove shipping fields information from the order completely if necessary
		 *
		 * @since 1.1
		 *
		 * @return void
		 */
		public function wc_adjust_order_shipping_fields() {
			$this->load_plugin_settings();

			$checkout = WC()->checkout();
			$shipping = ! empty( $checkout->shipping_methods ) ? $checkout->shipping_methods : array();

			if ( ! empty( $shipping ) ) {
				if ( ! is_array( $shipping ) ) {
					$shipping = array( $shipping );
				}

				if ( array_key_exists( $shipping[0], $this->settings['methods'] ) && $this->settings['methods'][ $shipping[0] ] == 'yes' ) {
					$checkout->checkout_fields['shipping'] = array();
				}
			}
		}

		/**
		 * Add actions to WooCommerce shipping method settings pages and checkout pages
		 *
		 * @since 1.0
		 *
		 * @return void
		 */
		public function wc_init() {
			//Adjust the WooCommerce shipping methods settings pages
			$this->wc_adjust_method_settings_pages();

			//Adjust the WooCommerce shipping methods main settings page
			$this->wc_adjust_main_settings_page();

			//Get default plugin settings
			$this->load_plugin_default_settings();

			//Adjust the WooCommerce checkout page
			$this->wc_adjust_checkout_page();
		}

		/**
		 * @return WC_Shipping_Zone[]
		 */
		private function get_shipping_zones() {
			$zones = array( new WC_Shipping_Zone( 0 ) );

			foreach ( WC_Shipping_Zones::get_zones() as $zone ) {
				$zones[ $zone['zone_id'] ] = new WC_Shipping_Zone( $zone['zone_id'] );
			}

			return $zones;
		}

		/**
		 * Load some general stuff
		 *
		 * @since 1.0
		 *
		 * @return void
		 */
		private function init() {
			//Load text domain
			load_plugin_textdomain( self::TEXT_DOMAIN, false, basename( dirname( __FILE__ ) ) . '/languages/' );

			//Register activation hook
			register_activation_hook( __FILE__, array( $this, 'plugin_activate' ) );

			//Register uninstall hook
			register_uninstall_hook( __FILE__, array( 'WC_HCSA', 'plugin_uninstall' ) );
		}

		/**
		 * Load the plugin default settings
		 *
		 * @since 1.0
		 *
		 * @return void
		 */
		private function load_plugin_default_settings() {
			$default_settings = array(
				'effect'  => 'slide',
				'methods' => 'no',
			);

			$this->default_settings = apply_filters( self::PLUGIN_PREFIX . 'load_plugin_default_settings', $default_settings );
		}

		/**
		 * Load plugin settings saved at the shipping methods setting pages
		 *
		 * @since 1.0
		 *
		 * @return void
		 */
		private function load_plugin_settings() {
			$this->wc_load_shipping_methods();

			$settings = array(
				'effect'  => $this->get_option( 'effect', $this->default_settings['effect'] ),
				'methods' => array(),
			);

			foreach ( $this->shipping_methods as $key => $shipping_method ) {
				$settings['methods'][ $key ] = $shipping_method->supports('shipping-zones')
					? $shipping_method->get_instance_option( 'hcsa', $this->default_settings['methods'] )
					: $shipping_method->get_option( 'hcsa', $this->default_settings['methods'] );
			}

			$this->settings = apply_filters( self::PLUGIN_PREFIX . 'load_plugin_settings', $settings );
		}

		/**
		 * Plugin installer
		 *
		 * @return void
		 */
		private function plugin_install() {
			$previous_version = $this->get_option( 'current_version', '0' );

			if ( version_compare( $previous_version, self::PLUGIN_VERSION, '<' ) ) {
				switch ( $previous_version ) {
					case '0':
						$this->add_option( 'current_version', '0' );
						$this->add_option( 'effect', $this->default_settings['effect'] );

					case '1.0':
						break;
				}

				$this->update_option( 'current_version', self::PLUGIN_VERSION );
			}
		}

		/**
		 * Adjust the checkout page with the necessary scripts
		 *
		 * @since 1.0
		 *
		 * @return void
		 */
		private function wc_adjust_checkout_page() {
			//Get saved settings from the admin pages
			$this->load_plugin_settings();

			//Load some javascript
			add_action( 'woocommerce_before_checkout_form', array( 'WC_HCSA', 'action_woocommerce_before_checkout_form' ) );
		}

		/**
		 * Add settings to the main shipping settings page
		 *
		 * @since 1.0
		 *
		 * @return void
		 */
		private function wc_adjust_main_settings_page() {
			add_filter( 'woocommerce_shipping_settings', array( 'WC_HCSA', 'filter_woocommerce_shipping_settings' ), 80 );
		}

		/**
		 * Adjust all individual shipping method's setting pages
		 *
		 * @since 1.0
		 *
		 * @return void
		 */
		private function wc_adjust_method_settings_pages() {
			$this->wc_load_shipping_methods();

			//Add a setting field to all shipping method setting pages
			foreach ( $this->shipping_methods as $key => $shipping_method ) {
				add_filter( 'woocommerce_settings_api_form_fields_' . $shipping_method->id, array( 'WC_HCSA', 'filter_woocommerce_settings_api_form_fields' ), 80 );
				add_filter( 'woocommerce_shipping_instance_form_fields_' . $shipping_method->id, array( 'WC_HCSA', 'filter_woocommerce_settings_api_form_fields' ), 80 );
			}
		}

		/**
		 * Load all WooCommerce shipping methods into the $shipping_methods array
		 *
		 * @since 1.0
		 *
		 * @return void
		 */
		private function wc_load_shipping_methods() {
			if ( empty( $this->shipping_methods ) ) {
				$this->shipping_methods = array();

				foreach ( $this->get_shipping_zones() as $shipping_zone ) {
					foreach ( $shipping_zone->get_shipping_methods() as $shipping_method ) {
						/**
						 * @var WC_Shipping_Method $shipping_method
						 */
						$this->shipping_methods[ $shipping_method->id . ':' . $shipping_method->get_instance_id() ] = $shipping_method;
					}
				}

				foreach ( WC()->shipping()->load_shipping_methods() as $shipping_method ) {
					if ( ! $shipping_method->supports( 'shipping-zones' ) ) {
						$this->shipping_methods[ $shipping_method->id ] = $shipping_method;
					}
				}
			}
		}

		/**
		 * Method used in an action for adding scripts to the checkout page
		 *
		 * @since 1.2.3
		 *
		 * @return void
		 */
		public static function action_woocommerce_before_checkout_form() {
			wp_enqueue_script( 'hide-checkout-shipping-address', plugins_url( '/js/hide-checkout-shipping-address.js', __FILE__ ), array( 'jquery' ), '', true );
			wp_localize_script( 'hide-checkout-shipping-address', 'wc_hcsa_settings', WC_HCSA::get_settings() );
		}

		/**
		 * Method used in a filter for adjusting the order review page if necessary
		 *
		 * @since 1.2.3
		 *
		 * @return array
		 */
		public static function filter_woocommerce_order_hide_shipping_address() {
			$settings = self::get_settings();

			return array_filter( array_map( array( 'WC_HCSA', 'filter_woocommerce_order_hide_shipping_address_map' ), array_keys( $settings['methods'] ), $settings['methods'] ) );
		}

		/**
		 * @since 1.2.4
		 *
		 * @return array
		 */
		public static function filter_woocommerce_order_hide_shipping_address_map( $method, $state ) {
			return $state == 'yes' ? $method : '';
		}

		/**
		 * Method used in a filter for adjusting all individual shipping method's setting pages
		 *
		 * @param array $fields
		 *
		 * @since 1.2.3
		 *
		 * @return array
		 */
		public static function filter_woocommerce_settings_api_form_fields( $fields ) {
			$fields['hcsa'] = array(
				'title'       => __( 'Hide shipping address', self::TEXT_DOMAIN ),
				'type'        => 'checkbox',
				'label'       => __( 'Hide', self::TEXT_DOMAIN ),
				'default'     => 'no',
				'description' => __( 'Hide the shipping address form fields on the checkout page when this shipping method is selected', self::TEXT_DOMAIN ),
				'desc_tip'    => false
			);

			return $fields;
		}

		/**
		 * Method used in a filter for adding settings to the main shipping settings page
		 *
		 * @param array $fields
		 *
		 * @since 1.2.3
		 *
		 * @return array
		 */
		public static function filter_woocommerce_shipping_settings( $fields ) {
			array_splice( $fields, 4, 0, array(
				array(
					'title'   => __( 'Hide shipping address effect', self::TEXT_DOMAIN ),
					'id'      => self::PLUGIN_PREFIX . 'effect',
					'default' => 'slide',
					'type'    => 'select',
					'class'   => 'wc-enhanced-select-nostd',
					'options' => array(
						'slide' => __( 'Slide', self::TEXT_DOMAIN ),
						'fade'  => __( 'Fade', self::TEXT_DOMAIN ),
					),
				)
			) );

			return $fields;
		}

		/**
		 * Gets a class instance. Used to prevent this plugin from loading multiple times
		 *
		 * @return WC_HCSA
		 */
		public static function get_instance() {
			if ( empty( self::$instance ) ) {
				//Check if WooCommerce is active
				if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) && function_exists( 'WC' ) && ( defined( 'WC_VERSION' ) && version_compare( WC_VERSION, self::WC_REQUIRED_VERSION, '>=' ) ) ) {
					//Pick the right class for older WooCommerce versions
					if ( version_compare( WC_VERSION, '2.6', '<' ) ) {
						require_once( __DIR__ . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'WC_HCSA_Old.php' );

						self::$instance = WC_HCSA_Old::get_instance();
					} else {
						self::$instance = new self();
					}
				} else {
					add_action( 'admin_notices', array( 'WC_HCSA', 'wc_error_admin_notice' ) );
				}
			}

			return self::$instance;
		}

		/**
		 * Retrieve the class main settings
		 *
		 * @since 1.2.1
		 *
		 * @return array
		 */
		public static function get_settings() {
			$instance = self::get_instance();

			return $instance->settings;
		}

		/**
		 * Plugin uninstaller
		 *
		 * @return void
		 */
		public static function plugin_uninstall() {
			$instance = self::get_instance();

			$instance->delete_option( 'current_version' );
			$instance->delete_option( 'effect' );
		}

		/**
		 * Echo an error in the admin notices area when WooCommerce was not installed/activated/up-to-date
		 *
		 * @since 1.0
		 *
		 * @return void
		 */
		public static function wc_error_admin_notice() {
			if ( ! in_array( 'woocommerce/woocommerce.php', array_keys( get_plugins() ) ) ) {
				$message = sprintf( __( 'The %s plugin depends on the WooCommerce plugin, which is not installed. Please install WooCommerce before using this plugin.', self::TEXT_DOMAIN ), self::PLUGIN_NAME );
			} elseif ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
				$message = sprintf( __( 'The %s plugin depends on the WooCommerce plugin, which is not yet activated. Please activate WooCommerce before using this plugin.', self::TEXT_DOMAIN ), self::PLUGIN_NAME );
			} elseif ( defined( 'WC_VERSION' ) && version_compare( WC_VERSION, self::WC_REQUIRED_VERSION, '<' ) ) {
				$message = sprintf( __( 'The %s requires at least WooCommerce version %s. You are currently using version %s. Please update WooCommerce before using this plugin.', self::TEXT_DOMAIN ), self::PLUGIN_NAME, self::WC_REQUIRED_VERSION, WC_VERSION );
			} else {
				$message = sprintf( __( 'The %s plugin depends on the WooCommerce plugin, which could not be recognized. Please check your WooCommerce plugin status before using this plugin.', self::TEXT_DOMAIN ), self::PLUGIN_NAME );
			}

			echo '<div class="error"><p>' . $message . '</p></div>';
		}
	}

	/**
	 * Load this plugin through the static instance
	 */
	add_action( 'plugins_loaded', array( 'WC_HCSA', 'get_instance' ) );

	//Remove shipping address from order creation if necessary
	function wc_hcsa_adjust_order_shipping_fields() {
		WC_HCSA::get_instance()->wc_adjust_order_shipping_fields();
	}

	add_action( 'woocommerce_new_order', 'wc_hcsa_adjust_order_shipping_fields', 99 );

	//Adjust the WooCommerce order review page
	function wc_hcsa_adjust_order_review_page() {
		WC_HCSA::get_instance()->wc_adjust_order_review_page();
	}

	add_action( 'woocommerce_order_details_after_order_table', 'wc_hcsa_adjust_order_review_page' );