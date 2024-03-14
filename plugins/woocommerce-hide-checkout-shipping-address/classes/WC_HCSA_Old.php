<?php

	if ( ! defined( 'ABSPATH' ) ) {
		exit; // Exit if accessed directly
	}

	final class WC_HCSA_Old {

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
		private $shipping_methods = array();

		/**
		 * @var WC_HCSA_Old
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
			return add_option( WC_HCSA::PLUGIN_PREFIX . $option, $value, '', $autoload );
		}

		/**
		 * Removes option by name. Prevents removal of protected WordPress options.
		 *
		 * @see delete_option()
		 */
		public function delete_option( $option ) {
			return delete_option( WC_HCSA::PLUGIN_PREFIX . $option );
		}

		/**
		 * Retrieve option value based on name of option.
		 *
		 * @see get_option()
		 */
		public function get_option( $option, $default = false ) {
			return get_option( WC_HCSA::PLUGIN_PREFIX . $option, $default );
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
			return update_option( WC_HCSA::PLUGIN_PREFIX . $option, $value );
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

			add_filter( 'woocommerce_order_hide_shipping_address', array( 'WC_HCSA_Old', 'filter_woocommerce_order_hide_shipping_address' ), 99 );
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
		 * Load some general stuff
		 *
		 * @since 1.0
		 *
		 * @return void
		 */
		private function init() {
			//Load text domain
			load_plugin_textdomain( WC_HCSA::TEXT_DOMAIN, false, basename( dirname( __FILE__ ) ) . '/languages/' );

			//Register activation hook
			register_activation_hook( __FILE__, array( $this, 'plugin_activate' ) );

			//Register uninstall hook
			register_uninstall_hook( __FILE__, array( 'WC_HCSA_Old', 'plugin_uninstall' ) );
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

			$this->default_settings = apply_filters( WC_HCSA::PLUGIN_PREFIX . 'load_plugin_default_settings', $default_settings );
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
				$settings['methods'][ $key ] = $shipping_method->get_option( 'hcsa', $this->default_settings['methods'] );
			}

			$this->settings = apply_filters( WC_HCSA::PLUGIN_PREFIX . 'load_plugin_settings', $settings );
		}

		/**
		 * Plugin installer
		 *
		 * @return void
		 */
		private function plugin_install() {
			$previous_version = $this->get_option( 'current_version', '0' );

			if ( version_compare( $previous_version, WC_HCSA::PLUGIN_VERSION, '<' ) ) {
				switch ( $previous_version ) {
					case '0':
						$this->add_option( 'current_version', '0' );
						$this->add_option( 'effect', $this->default_settings['effect'] );

					case '1.0':
						break;
				}

				$this->update_option( 'current_version', WC_HCSA::PLUGIN_VERSION );
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
			add_action( 'woocommerce_before_checkout_form', array( 'WC_HCSA_Old', 'action_woocommerce_before_checkout_form' ) );
		}

		/**
		 * Add settings to the main shipping settings page
		 *
		 * @since 1.0
		 *
		 * @return void
		 */
		private function wc_adjust_main_settings_page() {
			add_filter( 'woocommerce_shipping_settings', array( 'WC_HCSA_Old', 'filter_woocommerce_shipping_settings' ), 80 );
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
				add_filter( 'woocommerce_settings_api_form_fields_' . $key, array( 'WC_HCSA_Old', 'filter_woocommerce_settings_api_form_fields' ), 80 );
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
				$all_shipping_methods   = WC()->shipping()->load_shipping_methods();

				foreach ( $all_shipping_methods as $shipping_method ) {
					$this->shipping_methods[ $shipping_method->id ] = $shipping_method;
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
			wp_localize_script( 'hide-checkout-shipping-address', 'wc_hcsa_settings', WC_HCSA_Old::get_settings() );
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

			return array_filter( array_map( array( 'WC_HCSA_Old', 'filter_woocommerce_order_hide_shipping_address_map' ), array_keys( $settings['methods'] ), $settings['methods'] ) );
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
				'title'       => __( 'Hide shipping address', WC_HCSA::TEXT_DOMAIN ),
				'type'        => 'checkbox',
				'label'       => __( 'Hide', WC_HCSA::TEXT_DOMAIN ),
				'default'     => 'no',
				'description' => __( 'Hide the shipping address form fields on the checkout page when this shipping method is selected', WC_HCSA::TEXT_DOMAIN ),
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
			array_splice( $fields, 7, 0, array(
				array(
					'title'   => __( 'Hide shipping address effect', WC_HCSA::TEXT_DOMAIN ),
					'id'      => 'woocommerce_hcsa_effect',
					'default' => '',
					'type'    => 'select',
					'class'   => 'chosen_select',
					'options' => array(
						'slide' => __( 'Slide', WC_HCSA::TEXT_DOMAIN ),
						'fade'  => __( 'Fade', WC_HCSA::TEXT_DOMAIN ),
					),
				)
			) );

			return $fields;
		}

		/**
		 * Gets a class instance. Used to prevent this plugin from loading multiple times
		 *
		 * @return WC_HCSA_Old
		 */
		public static function get_instance() {
			if ( empty( self::$instance ) ) {
				self::$instance = new self();
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
	}