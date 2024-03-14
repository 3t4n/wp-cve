<?php

/**
 * Plugin Name: Cities Shipping Zones for WooCommerce
 * Plugin URI: https://en.condless.com/cities-shipping-zones-for-woocommerce/
 * Description: WooCommerce plugin for turning the state field into a dropdown city field. To be used as shipping zones.
 * Version: 1.2.6
 * Author: Condless
 * Author URI: https://en.condless.com/
 * Developer: Condless
 * Developer URI: https://en.condless.com/
 * Contributors: condless
 * Text Domain: cities-shipping-zones-for-woocommerce
 * Domain Path: /i18n/languages
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Requires at least: 5.2
 * Tested up to: 6.5
 * Requires PHP: 7.0
 * WC requires at least: 3.4
 * WC tested up to: 8.7
 */

/**
 * Exit if accessed directly
 */
defined( 'ABSPATH' ) || exit;

/**
 * Check if WooCommerce is active
 */
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) || get_site_option( 'active_sitewide_plugins') && array_key_exists( 'woocommerce/woocommerce.php', get_site_option( 'active_sitewide_plugins' ) ) ) {

	/**
	 * Cities Shipping Zones for WooCommerce class.
	 */
	class WC_CSZ {

		/**
		 * Construct class
		 */
		public function __construct() {
			add_action( 'before_woocommerce_init', function() {
				if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
					\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
				}
			} );
			add_action( 'plugins_loaded', [ $this, 'init' ] );
		}

		/**
		 * WC init
		 */
		public function init() {
			$this->init_textdomain();
			$this->init_settings();
			if ( ! empty( get_option( 'wc_csz_countries_codes' ) ) ) {
				$this->init_places();
				$this->init_fields_values();
				$this->init_fields_titles();
				$this->init_reports();
				if ( 'yes' === get_option( 'wc_csz_shipping_distance_fee' ) ) {
					$this->init_shipping_distance_fee();
				}
			}
		}

		/**
		 * Load text domain for internationalization
		 */
		public function init_textdomain() {
			load_plugin_textdomain( 'cities-shipping-zones-for-woocommerce', false, dirname( plugin_basename( __FILE__ ) ) . '/i18n/languages' );
		}

		/**
		 * WC settings init
		 */
		public function init_settings() {
			add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), [ $this, 'wc_update_settings_link' ] );
			add_filter( 'plugin_row_meta', [ $this, 'wc_add_plugin_links' ], 10, 4 );
			add_filter( 'woocommerce_settings_tabs_array', [ $this, 'wc_add_settings_tab' ], 50 );
			add_action( 'woocommerce_settings_tabs_csz', [ $this, 'wc_settings_tab' ] );
			add_action( 'woocommerce_update_options_csz', [ $this, 'wc_update_settings' ] );
			add_filter( 'woocommerce_admin_settings_sanitize_option_wc_csz_countries_codes', [ $this, 'wc_sanitize_option_wc_csz_countries_codes' ], 10, 2 );
			add_filter( 'woocommerce_admin_settings_sanitize_option_wc_csz_populate_state', [ $this, 'wc_sanitize_option_wc_csz_populate_state' ], 10, 2 );
			add_filter( 'woocommerce_admin_settings_sanitize_option_wc_csz_new_state_field', [ $this, 'wc_sanitize_option_wc_csz_new_state_field' ], 10, 2 );
			add_filter( 'woocommerce_admin_settings_sanitize_option_wc_csz_shipping_distance_fee', [ $this, 'wc_sanitize_option_wc_csz_shipping_distance_fee' ], 10, 2 );
			add_filter( 'woocommerce_admin_settings_sanitize_option_wc_csz_set_zone_locations', [ $this, 'wc_sanitize_option_wc_csz_set_zone_locations' ], 10, 2 );
			add_shortcode( 'csz_cities', [ $this, 'wc_csz_cities_shortcode' ] );
			add_action( 'wp_ajax_csz_match_shipping_zone', [ $this, 'csz_match_shipping_zone' ] );
			add_action( 'wp_ajax_nopriv_csz_match_shipping_zone', [ $this, 'csz_match_shipping_zone' ] );
			add_filter( 'woocommerce_should_load_paypal_standard', '__return_true' );
		}

		/**
		 * WC places init
		 */
		public function init_places() {
			add_filter( 'woocommerce_states', [ $this, 'wc_cities' ], 999 );
		}

		/**
		 * WC fields values init
		 */
		public function init_fields_values() {
			add_action( 'woocommerce_checkout_create_order', [ $this, 'wc_checkout_copy_state_city' ], 10, 2 );
			add_action( 'woocommerce_customer_save_address', [ $this, 'wc_customer_copy_state_city' ], 10, 2 );
		}

		/**
		 * WC fields titles init
		 */
		public function init_fields_titles() {
			add_filter( 'woocommerce_customer_meta_fields', [ $this, 'wc_admin_modify_state_label' ] );
			add_filter( 'woocommerce_customer_taxable_address', [ $this, 'wc_change_tax_address' ] );
			add_filter( 'woocommerce_localisation_address_formats', [ $this, 'wc_modify_address_formats' ] );
			add_filter( 'woocommerce_shipping_calculator_enable_city', '__return_false' );
			add_filter( 'woocommerce_shipping_calculator_enable_postcode', '__return_false' );
			add_filter( 'woocommerce_customer_default_location', [ $this, 'wc_remove_default_state_city' ] );
			add_filter( 'woocommerce_get_country_locale', [ $this, 'wc_locale_state_city' ], 999 );
			if ( 'yes' === get_option( 'wc_csz_new_state_field' ) ) {
				add_filter( 'woocommerce_shipping_calculator_enable_state', [ $this, 'wc_cart_state_filter' ], 999 );
				add_filter( 'woocommerce_default_address_fields', [ $this, 'wc_state_filter_field' ] );
				add_action( 'woocommerce_after_checkout_form', [ $this, 'wc_new_state_dropdown' ] );
				add_action( 'woocommerce_account_navigation', [ $this, 'wc_new_state_dropdown' ] );
				add_filter( 'wooccm_billing_fields', [ $this, 'wc_wooccm_update_billing_fields' ] );
				add_filter( 'wooccm_shipping_fields', [ $this, 'wc_wooccm_update_shipping_fields' ] );
			}
			if ( 'yes' !== get_option( 'wc_csz_populate_state' ) ) {
				add_filter( 'woocommerce_shipping_calculator_enable_state', [ $this, 'wc_shipping_calculator_custom_state' ], 999 );
				add_filter( 'woocommerce_checkout_fields', [ $this, 'wc_enable_custom_state' ] );
				add_action( 'woocommerce_after_checkout_validation', [ $this, 'wc_disable_state_validation' ], 10, 2 );
			}
		}

		/**
		 * WC reports init
		 */
		public function init_reports() {
			add_filter( 'woocommerce_admin_reports', [ $this, 'wc_admin_cities_report_orders_tab' ] );
			add_filter( 'manage_edit-shop_order_columns', [ $this, 'wc_add_custom_shop_order_column' ] );
			add_action( 'manage_shop_order_posts_custom_column', [ $this, 'wc_shop_order_column_meta_field_value' ] );
			add_filter( 'manage_edit-shop_order_sortable_columns', [ $this, 'wc_shop_order_column_meta_field_sortable' ] );
			add_action( 'pre_get_posts', [ $this, 'wc_shop_order_column_meta_field_sortable_orderby' ] );
			add_filter( 'woocommerce_shop_order_search_fields', [ $this, 'wc_shipping_city_searchable_field' ] );
		}

		/**
		 * WC shipping distance fee init
		 */
		public function init_shipping_distance_fee() {
			add_filter( 'woocommerce_shipping_instance_form_fields_flat_rate', [ $this, 'wc_flat_rate_distance_fee_field' ] );
			add_filter( 'woocommerce_package_rates', [ $this, 'wc_distance_fee_calc' ], 999, 2 );
		}

		/**
		 * Add plugin links to the plugin menu
		 * @param mixed $links
		 * @return mixed
		 */
		public function wc_update_settings_link( $links ) {
			array_unshift( $links, '<a href=' . esc_url( add_query_arg( 'page', 'wc-settings&tab=csz', get_admin_url() . 'admin.php' ) ) . '>' . __( 'Settings' ) . '</a>' );
			return $links;
		}

		/**
		 * Add plugin meta links to the plugin menu
		 * @param mixed $links_array
		 * @param mixed $plugin_file_name
		 * @param mixed $plugin_data
		 * @param mixed $status
		 * @return mixed
		 */
		public function wc_add_plugin_links( $links_array, $plugin_file_name, $plugin_data, $status ) {
			if ( strpos( $plugin_file_name, basename( __FILE__ ) ) ) {
				$sub_domain = 'he_IL' === get_locale() ? 'www' : 'en';
				$links_array[] = "<a href=https://$sub_domain.condless.com/cities-shipping-zones-for-woocommerce/>" . __( 'Docs', 'woocommerce' ) . '</a>';
				$links_array[] = "<a href=https://$sub_domain.condless.com/contact/>" . _x( 'Contact', 'Theme starter content' ) . '</a>';
			}
			return $links_array;
		}

		/**
		 * Add a new settings tab to the WooCommerce settings tabs array
		 * @param array $settings_tabs
		 * @return array
		 */
		public function wc_add_settings_tab( $settings_tabs ) {
			$settings_tabs['csz'] = _x( 'Cities Shipping Zones', 'plugin', 'cities-shipping-zones-for-woocommerce' );
			return $settings_tabs;
		}

		/**
		 * Use the WooCommerce admin fields API to output settings via the @see woocommerce_admin_fields() function
		 * @uses woocommerce_admin_fields()
		 * @uses self::wc_get_settings()
		 */
		public function wc_settings_tab() {
			woocommerce_admin_fields( self::wc_get_settings() );
		}

		/**
		 * Use the WooCommerce options API to save settings via the @see woocommerce_update_options() function
		 * @uses woocommerce_update_options()
		 * @uses self::wc_get_settings()
		 */
		public function wc_update_settings() {
			woocommerce_update_options( self::wc_get_settings() );
		}

		/**
		 * Get all the settings for this plugin for @see woocommerce_admin_fields() function
		 * @return array Array of settings for @see woocommerce_admin_fields() function
		 */
		public function wc_get_settings() {
			if ( get_option( 'wc_csz_countries_codes' ) ) {
				foreach ( get_option( 'wc_csz_countries_codes' ) as $country ) {
					$selected_countries[ $country ] = WC()->countries->countries[ $country ];
				}
			}
			$settings = [
				'location_section'	=> [
					'name'	=> _x( 'Countries to apply on', 'plugin', 'cities-shipping-zones-for-woocommerce' ),
					'type'	=> 'title',
					'id'	=> 'wc_csz_location_section'
				],
				'countries_codes'	=> [
					'name'		=> __( 'Country / Region', 'woocommerce' ),
					'type'		=> 'multi_select_countries',
					'default'	=> WC()->countries->get_base_country(),
					'desc_tip'	=> _x( 'Select supported countries only.', 'plugin', 'cities-shipping-zones-for-woocommerce' ) . ' ' . _x( 'To apply the plugin on the selected countries press:', 'plugin', 'cities-shipping-zones-for-woocommerce' ) . ' ' . __( 'Save changes', 'woocommerce' ),
					'id'		=> 'wc_csz_countries_codes'
				],
				'location_section_end'	=> [
					'type'	=> 'sectionend',
					'id'	=> 'wc_csz_location_section_end'
				],
				'options_section'	=> [
					'name'	=> __( 'Options', 'woocommerce' ),
					'type'	=> 'title',
					'id'	=> 'wc_csz_options_section'
				],
				'populate_state'	=> [
					'name'		=> __( 'State / County', 'woocommerce' ) . ' ' . _x( 'Autofill', 'plugin', 'cities-shipping-zones-for-woocommerce' ),
					'desc'		=> _x( 'Autofill the state in the order details based on the selected city', 'plugin', 'cities-shipping-zones-for-woocommerce' ),
					'desc_tip'	=> _x( 'Recommended if your shipping/payment provider require the state of the customer', 'plugin', 'cities-shipping-zones-for-woocommerce' ),
					'type'		=> 'checkbox',
					'default'	=> ! empty( ( include WC()->plugin_path() . '/i18n/states.php' )[ WC()->countries->get_base_country() ] ) ? 'yes' : 'no',
					'id'		=> 'wc_csz_populate_state'
				],
				'new_state_field'	=> [
					'name'		=> __( 'State / County', 'woocommerce' ) . ' ' . __( 'Filters', 'woocommerce' ),
					'desc'		=> _x( 'Display a State/County filter for the cities of the shop country', 'plugin', 'cities-shipping-zones-for-woocommerce' ),
					'desc_tip'	=> _x( 'The selected state will not be saved in the order details', 'plugin', 'cities-shipping-zones-for-woocommerce' ),
					'type'		=> 'checkbox',
					'default'	=> 'no',
					'id'		=> 'wc_csz_new_state_field'
				],
				'checkout_restrict_states'	=> [
					'name'		=> __( 'Selling location(s)', 'woocommerce' ),
					'desc'		=> _x( 'Sell only to customers from locations that was explicitly selected in shipping zone', 'plugin', 'cities-shipping-zones-for-woocommerce' ),
					'desc_tip'	=> _x( 'Not recommended if the store offers local pickup', 'plugin', 'cities-shipping-zones-for-woocommerce' ),
					'type'		=> 'checkbox',
					'default'	=> 'no',
					'id'		=> 'wc_csz_checkout_restrict_states'
				],
				'shipping_distance_fee'	=> [
					'name'		=> _x( 'Distance Fee', 'plugin', 'cities-shipping-zones-for-woocommerce' ),
					'desc'		=> _x( 'Enable to be able to apply distance fee in flat rate shipping methods', 'plugin', 'cities-shipping-zones-for-woocommerce' ),
					'type'		=> 'checkbox',
					'default'	=> 'no',
					'id'		=> 'wc_csz_shipping_distance_fee'
				],
				'options_section_end'	=> [
					'type'	=> 'sectionend',
					'id'	=> 'wc_csz_options_section_end'
				],
				'insert_section'	=> [
					'name'	=> __( 'Zone regions', 'woocommerce' ) . ' ' . __( 'Bulk Select' ),
					'type'	=> 'title',
					'desc'	=> _x( 'A tool for bulk insert locations into shipping zone', 'plugin', 'cities-shipping-zones-for-woocommerce' ) . '. <a href=https://' . ( 'he_IL' === get_locale() ? 'www' : 'en' ) . '.condless.com/contact/>' . __( 'Support' ) . '</a>',
					'id'	=> 'wc_csz_insert_section'
				],
				'set_zone_locations'	=> [
					'name'		=> __( 'Zone regions', 'woocommerce' ),
					'type'		=> 'text',
					'desc_tip'	=> _x( 'States/Cities names (as they appear in the dashboard) or codes (as they appear in the plugin folder /i18n/cities/ path) sepreated by semi-colon (;).', 'plugin', 'cities-shipping-zones-for-woocommerce' ),
					'id'		=> 'wc_csz_set_zone_locations'
				],
				'set_zone_country'	=> [
					'name'		=> __( 'Country / Region', 'woocommerce' ),
					'type'		=> 'select',
					'options'	=> $selected_countries ?? [],
					'desc_tip'	=> _x( 'Select the country that the locations belong to.', 'plugin', 'cities-shipping-zones-for-woocommerce' ),
					'id'		=> 'wc_csz_set_zone_country'
				],
				'set_zone_id'	=> [
					'name'		=> __( 'Shipping zone name.', 'woocommerce' ),
					'type'		=> 'select',
					'options'	=> array_column( WC_Shipping_Zones::get_zones(), 'zone_name', 'zone_id' ),
					'desc_tip'	=> _x( 'The locations of this shipping zone will be overridden.', 'plugin', 'cities-shipping-zones-for-woocommerce' ),
					'id'		=> 'wc_csz_set_zone_id'
				],
				'insert_section_end'	=> [
					'type'	=> 'sectionend',
					'id'	=> 'wc_csz_insert_section_end'
				]
			];
			return apply_filters( 'wc_csz_settings', $settings );
		}

		/**
		 * Sanitize the countries codes option
		 * @param mixed $value
		 * @param mixed $option
		 * @return mixed
		 */
		public function wc_sanitize_option_wc_csz_countries_codes( $value, $option ) {
			$old_option = get_option( $option['id'] );
			foreach ( $value as $country_code ) {
				if ( file_exists( plugin_dir_path( __FILE__ ) . 'i18n/cities/' . $country_code . '.php' ) ) {
					if ( ! $old_option || ! in_array( $country_code, $old_option ) ) {
						$added_countries[] = WC()->countries->countries[ $country_code ];
						if ( in_array( $country_code, [ 'AE', 'AL', 'BD', 'CA', 'DK', 'EE', 'EG', 'FI', 'GA', 'GB', 'GE', 'ID', 'IE', 'IN', 'IR', 'JO', 'LT', 'NG', 'NL', 'PK', 'PR', 'PY', 'QA', 'SA', 'SE', 'SL', 'SN', 'SV', 'TR', 'UY', 'XK', ] ) ) {
							WC_Admin_Settings::add_message( _x( 'Keep in mind that the list of the country you have selected contains municipalities and not cities.', 'plugin', 'cities-shipping-zones-for-woocommerce' ) . ' ' . WC()->countries->countries[ $country_code ] );
						}
					}
					if ( in_array( $country_code, [ 'FR', 'IT', 'US' ] ) && ! has_filter( 'csz_states' ) && ! has_filter( 'csz_cities' ) ) {
						WC_Admin_Settings::add_message( _x( 'You have selected a country with too long cities list, see the instructions in the docs about how to minimize it.', 'plugin', 'cities-shipping-zones-for-woocommerce' ) . ' ' . WC()->countries->countries[ $country_code ] );
					}
				} else {
					$value = array_diff( $value, [ $country_code ] );
					$unsupported_countries[] = WC()->countries->countries[ $country_code ];
				}
			}
			if ( ! empty( $added_countries ) ) {
				WC_Admin_Settings::add_message( implode( ', ', $added_countries ) . ': ' . _x( 'locations were added.', 'plugin', 'cities-shipping-zones-for-woocommerce' ) . ' '. __( 'Update', 'woocommerce' ) . ' ' . __( 'Shipping settings', 'woocommerce' ) . ': ' . __( 'Shipping zones', 'woocommerce' ) . ': ' . __( 'Zone regions', 'woocommerce' ) . '. ' . _x( 'Drag the relevant shipping zone to the top of the shiping zones list.', 'plugin', 'cities-shipping-zones-for-woocommerce' ) );
			}
			if ( ! empty( $unsupported_countries ) ) {
				WC_Admin_Settings::add_message( _x( 'Contact us for adding unsupported country', 'plugin', 'cities-shipping-zones-for-woocommerce' ) . '. ' . __( 'Unsupported' ) . ': ' . implode( ', ', $unsupported_countries ) );
			}
			if ( $old_option ) {
				foreach ( array_diff( $old_option, $value ) as $country_code ) {
					$removed_countries[] = WC()->countries->countries[ $country_code ];
				}
			}
			if ( ! empty( $removed_countries ) ) {
				WC_Admin_Settings::add_message( implode( ', ', $removed_countries ) . ': ' . _x( 'locations were removed.', 'plugin', 'cities-shipping-zones-for-woocommerce' ) . ' '. __( 'Update', 'woocommerce' ) . ' ' . __( 'Shipping settings', 'woocommerce' ) . ': ' . __( 'Shipping zones', 'woocommerce' ) . ': ' . __( 'Zone regions', 'woocommerce' ) );
			}
			$store_country = WC()->countries->get_base_country();
			if ( in_array( $store_country, $value ) && ( ! $old_option || $old_option && ! in_array( $store_country, $old_option ) ) ) {
				$cities = [];
				$country_states = '';
				include( 'i18n/cities/' . $store_country . '.php' );
				if ( $country_states ) {
					foreach ( array_keys( apply_filters( 'csz_states', $country_states ) ) as $state_code ) {
						if ( isset( $country_cities[ $state_code ] ) ) {
							$cities += $country_cities[ $state_code ];
						}
					}
				} else {
					$cities = $country_cities;
				}
				$new_store_city = array_search( WC()->countries->get_base_city(), $cities );
				if ( ! $new_store_city ) {
					$new_store_city = key( $cities );
					WC_Admin_Settings::add_message( __( 'Update', 'woocommerce' ) . ' ' . __( 'WooCommerce settings', 'woocommerce' ) . ': ' . __( 'Store Address', 'woocommerce' ) . ': ' . __( 'Country / State', 'woocommerce' ) );
				}
				update_option( 'woocommerce_default_country', $store_country . ':' . $new_store_city );
			} elseif ( $old_option && in_array( $store_country, $old_option ) && ! in_array( $store_country, $value ) ) {
				$org_states = include WC()->plugin_path() . '/i18n/states.php';
				if ( ! empty( $org_states[ $store_country ] ) ) {
					$first_state = key( $org_states[ $store_country ] );
					update_option( 'woocommerce_default_country', $store_country . ':' . $first_state );
					WC_Admin_Settings::add_message( __( 'Update', 'woocommerce' ) . ' ' . __( 'WooCommerce settings', 'woocommerce' ) . ': ' . __( 'Store Address', 'woocommerce' ) . ': ' . __( 'Country / State', 'woocommerce' ) );
				} else {
					update_option( 'woocommerce_default_country', $store_country );
				}
			}
			if ( $value && function_exists( 'is_plugin_active' ) ) {
				if ( is_plugin_active( 'woocommerce-gateway-paypal-express-checkout/woocommerce-gateway-paypal-express-checkout.php' ) || is_plugin_active( 'express-checkout-paypal-payment-gateway-for-woocommerce/express-checkout-paypal-payment-gateway-for-woocommerce.php' ) || is_plugin_active( 'yith-paypal-express-checkout-for-woocommerce/init.php' ) || is_plugin_active( 'woocommerce-paypal-payments/woocommerce-paypal-payments.php' ) ) {
					WC_Admin_Settings::add_message( _x( 'The plugin is not compatible with PayPal Express Checkout, use the WooCommerce PayPal Standard instead.', 'plugin', 'cities-shipping-zones-for-woocommerce' ) );
				}
				if ( is_plugin_active( 'woo-checkout-field-editor-pro/checkout-form-designer.php' ) ) {
					WC_Admin_Settings::add_message( _x( 'Checkout Field Editor: Enable the fields billing_country, billing_state, shipping_country, shipping_state. Modify the label of the billing_state and shipping_state fields to City. Set the billing_city and shipping_city fields to be non-required. For international stores disable the option- Enable label override for address fields (Plugin => Advanced Settings).', 'plugin', 'cities-shipping-zones-for-woocommerce' ) );
				}
				if ( is_plugin_active( 'yith-woocommerce-checkout-manager/init.php' ) || is_plugin_active( 'woocommerce-jetpack/woocommerce-jetpack.php' ) || is_plugin_active( 'woocommerce-checkout-manager/woocommerce-checkout-manager.php' ) || is_plugin_active( 'flexible-checkout-fields/flexible-checkout-fields.php' ) || is_plugin_active( 'add-fields-to-checkout-page-woocommerce/checkout-form-editor.php' ) || is_plugin_active( 'checkout-field-editor-and-manager-for-woocommerce/start.php' ) ) {
					WC_Admin_Settings::add_message( _x( 'Checkout Fields Manager: Enable the fields billing_country, billing_state, shipping_country, shipping_state. Modify the label of the billing_state and shipping_state fields to City. Set the billing_city and shipping_city fields to be non-required.', 'plugin', 'cities-shipping-zones-for-woocommerce' ) );
				}
				if ( is_plugin_active( 'wc-multivendor-marketplace/wc-multivendor-marketplace.php' ) ) {
					$integrations[] = 'WCFM Marketplace';
				}
				if ( ! empty( $integrations ) ) {
					WC_Admin_Settings::add_message( _x( 'Integrations are available for', 'plugin', 'cities-shipping-zones-for-woocommerce' ) . ' ' . implode( ', ', $integrations ) );
				}
			}
			return $value;
		}

		/**
		 * Sanitize the populate state option
		 * @param mixed $value
		 * @param mixed $option
		 * @return mixed
		 */
		public function wc_sanitize_option_wc_csz_populate_state( $value, $option ) {
			if ( 'yes' !== $value && isset( $_POST['wc_csz_countries_codes'] ) ) {
				if ( array_intersect( $_POST['wc_csz_countries_codes'], [ 'AR', 'BR', 'CA', 'CN', 'IN', 'ID', 'IT', 'JP', 'MX', 'TH', 'US' ] ) ) {
					WC_Admin_Settings::add_message( __( 'State / County', 'woocommerce' ) . ' ' . _x( 'Autofill', 'plugin', 'cities-shipping-zones-for-woocommerce' ) . ': ' . _x( 'must be enabled for the countries you applied the plugin on.', 'plugin', 'cities-shipping-zones-for-woocommerce' ) );
					return 'yes';
				} elseif ( 'yes' === get_option( $option['id'] ) ) {
					WC_Admin_Settings::add_message( __( 'State / County', 'woocommerce' ) . ' ' . _x( 'Autofill', 'plugin', 'cities-shipping-zones-for-woocommerce' ) . ': ' . _x( 'verify that your integrated payment/shipping/invoicing/ERP software do not require a valid state field for the countries you apply the plugin on.', 'plugin', 'cities-shipping-zones-for-woocommerce' ) );
				}
			}
			return $value;
		}

		/**
		 * Sanitize the filters option
		 * @param mixed $value
		 * @param mixed $option
		 * @return mixed
		 */
		public function wc_sanitize_option_wc_csz_new_state_field( $value, $option ) {
			if ( 'yes' === $value ) {
				if ( empty( $_POST['wc_csz_countries_codes'] ) || ! in_array( WC()->countries->get_base_country(), $_POST['wc_csz_countries_codes'] ) ) {
					WC_Admin_Settings::add_message( __( 'State / County', 'woocommerce' ) . ' ' . __( 'Filters', 'woocommerce' ) . ': ' . _x( 'The shop country was not selected', 'plugin', 'cities-shipping-zones-for-woocommerce' ) . '. ' . WC()->countries->countries[ WC()->countries->get_base_country() ] );
					return 'no';
				} else {
					$country_states = '';
					include( 'i18n/cities/' . WC()->countries->get_base_country() . '.php' );
					if ( empty( $country_states ) ) {
						WC_Admin_Settings::add_message( __( 'State / County', 'woocommerce' ) . ' ' . __( 'Filters', 'woocommerce' ) . ': ' . _x( 'The shop country does not support states, contact us to add it.', 'plugin', 'cities-shipping-zones-for-woocommerce' ) );
						return 'no';
					}
					if ( 'yes' !== get_option( $option['id'] ) && function_exists( 'is_plugin_active' ) ) {
						if ( is_plugin_active( 'woo-checkout-field-editor-pro/checkout-form-designer.php' ) ) {
							WC_Admin_Settings::add_message( __( 'State / County', 'woocommerce' ) . ' ' . __( 'Filters', 'woocommerce' ) . ': ' . _x( 'Add new_state field to billing and shipping sections via the Checkout Field Editor plugin.', 'plugin', 'cities-shipping-zones-for-woocommerce' ) );
						}
					}
				}
			}
			return $value;
		}

		/**
		 * Sanitize the shipping distance fee option
		 * @param mixed $value
		 * @param mixed $option
		 * @return mixed
		 */
		public function wc_sanitize_option_wc_csz_shipping_distance_fee( $value, $option ) {
			if ( 'yes' === $value ) {
				$value = 'no';
				$country_code = WC()->countries->get_base_country();
				if ( isset( $_POST['wc_csz_countries_codes'] ) && in_array( $country_code, $_POST['wc_csz_countries_codes'] ) && glob( plugin_dir_path( __FILE__ ) . "i18n/distances/{$country_code}*.php" ) ) {
					if ( 'yes' !== get_option( $option['id'] ) ) {
						WC_Admin_Settings::add_message( __( 'Update', 'woocommerce' ) . ' ' . __( 'Shipping settings', 'woocommerce' ) . ': ' . __( 'Shipping zones', 'woocommerce' ) . ': ' . __( 'Shipping methods', 'woocommerce' ) . ': ' . __( 'Flat rate', 'woocommerce' ) . ': ' . _x( 'Distance Fee', 'plugin', 'cities-shipping-zones-for-woocommerce' ) );
					}
					return 'yes';
				}
				WC_Admin_Settings::add_message( _x( 'Distance Fee', 'plugin', 'cities-shipping-zones-for-woocommerce' ) . ': ' . _x( 'your country is unsupported.', 'plugin', 'cities-shipping-zones-for-woocommerce' ) );
			}
			return $value;
		}

		/**
		 * Sanitize the locations bulk edit option
		 * @param mixed $value
		 * @param mixed $option
		 * @return mixed
		 */
		public function wc_sanitize_option_wc_csz_set_zone_locations( $value, $option ) {
			if ( ! empty( $value ) && ! empty( $_POST['wc_csz_countries_codes'] ) && ! empty( $_POST['wc_csz_set_zone_country'] ) && ! empty( $_POST['wc_csz_set_zone_id'] ) ) {
				include( 'i18n/cities/' . $_POST['wc_csz_set_zone_country'] . '.php' );
				$locations_codes = $unsupported_locations = $locations = [];
				$cities = WC()->countries->get_states( $_POST['wc_csz_set_zone_country'] );
				if ( '!' === substr( $value, 0, 1 ) ) {
					$limits = preg_split( '/\s*;\s*/', substr( $value, 1 ) );
					if ( file_exists( plugin_dir_path( __FILE__ ) . 'i18n/distances/' . $limits[0] . '.php' ) ) {
						include( 'i18n/distances/' . $limits[0] . '.php' );
						if ( isset( $limits[1] ) && isset( $limits[2] ) ) {
							foreach ( $cities_distance[ $limits[0] ] as $city => $distance ) {
								if ( $distance > $limits[1] && $distance < $limits[2] ) {
									$locations[] = $city;
								}
							}
						} else {
							$locations = array_keys( $cities_distance[ $limits[0] ] );
						}
					} else {
						$unsupported_locations[] = $value;
					}
				} else {
					$locations = preg_split( '/\s*;\s*/', $value, -1, PREG_SPLIT_NO_EMPTY );
				}
				foreach ( array_unique( $locations ) as $location ) {
					$city_code = isset( $cities[ $location ] ) ? $location : array_search( $location, $cities );
					if ( $city_code ) {
						$locations_codes[] = [ 'code' => $_POST['wc_csz_set_zone_country'] . ':' . $city_code, 'type' => 'state' ];
					} elseif ( null !== apply_filters( 'csz_states', $country_states ) ) {
						$state_code = isset( apply_filters( 'csz_states', $country_states )[ $location ] ) ? $location : array_search( $location, apply_filters( 'csz_states', $country_states ) );
						if ( $state_code ) {
							foreach ( array_keys( $country_cities[ $state_code ] ) as $city_code ) {
								$locations_codes[] = [
									'code'	=> $_POST['wc_csz_set_zone_country'] . ':' . $city_code,
									'type'	=> 'state',
								];
							}
						} else {
							$unsupported_locations[] = $location;
						}
					} else {
						$unsupported_locations[] = $location;
					}
				}
				if ( ! empty( $locations_codes ) ) {
					$zone = WC_Shipping_Zones::get_zone( $_POST['wc_csz_set_zone_id'] );
					if ( apply_filters( 'csz_set_locations_keep_old_locations_enabled', false ) ) {
						foreach ( $locations_codes as $location ) {
							$zone->add_location( $location['code'], $location['type'] );
						}						
					} else {
						$zone->set_locations( $locations_codes );
					}
					$zone->save();
					WC_Admin_Settings::add_message( __( 'Shipping settings', 'woocommerce' ) . ': ' . __( 'Shipping zones', 'woocommerce' ) . ': ' . __( 'Zone regions', 'woocommerce' ) . '. ' . _x( 'locations were added', 'plugin', 'cities-shipping-zones-for-woocommerce' ) );
				}
				if ( ! empty( $unsupported_locations ) ) {
					WC_Admin_Settings::add_message( __( 'Unsupported' ) . ': ' . implode( ', ', $unsupported_locations ) );
				}
				$value = '';
			}
			return $value;
		}

		/**
		 * Add cities shipping calculator shortcode
		 * @param mixed $atts
		 * @return mixed
		 */
		public function wc_csz_cities_shortcode( $atts ) {
			if ( apply_filters( 'csz_cities_shortcode_enabled', true ) && ! is_checkout() && ! is_cart() ) {
				$atts = shortcode_atts( [
					'international'	=> array_keys( WC()->countries->get_shipping_countries() ) === [ WC()->countries->get_base_country() ] ? 'no' : 'yes',
					'country'	=> isset( WC()->customer ) && WC()->customer->get_shipping_country() ? WC()->customer->get_shipping_country() : WC()->countries->get_base_country(),
					'description'	=> __( 'Enter your address to view shipping options.', 'woocommerce' ),
					'class'		=> [ 'form-row', 'address-field', 'form-row-first' ],
					'label'		=> __( 'City', 'woocommerce' ),
					'value'		=> isset( WC()->customer ) && WC()->customer->get_shipping_state() ? WC()->customer->get_shipping_state() : '',
					'country_description'	=> '',
					'country_class'		=> '',
					'country_label'		=> __( 'Country / Region', 'woocommerce' ),
					'template'	=> '',
				], $atts, 'csz_cities' );
				if ( 'popup' === $atts['template'] ) {
					if ( is_product() ) {
						return;
					}
					wp_enqueue_style( 'dashicons' );
					add_action( 'wp_footer', [ $this, 'csz_modal_javascript' ], 20 );
					$prefix = '<span class="dashicons dashicons-location"></span><a href="#" id="cities-update">' . __( 'Select an option&hellip;', 'woocommerce' ) . '</a><div class="cities-modal"><div class="cities-modal-container"><div class="cities-modal-content"><a href="#" class="cities-modal-close">&times;</a><h4 class="cities-modal-title">' . __( 'shipping address', 'woocommerce' ) . '</h4>';
					$suffix = '</div></div></div>';
				}
				wp_enqueue_script( 'selectWoo' );
				wp_enqueue_style( 'select2' );
				wp_enqueue_script( 'wc-country-select' );
				add_action( 'wp_footer', [ $this, 'csz_match_javascript' ], 20 );
				$state_args = apply_filters( 'csz_cities_shortcode_state_args', [
					'type'		=> 'state',
					'required'	=> true,
					'label'		=> $atts['label'],
					'class'		=> $atts['class'],
					'description'	=> ! empty( $atts['description'] ) ? $atts['description'] : ' ',
					'country'	=> 'yes' === $atts['international'] ? $atts['country'] : WC()->countries->get_base_country(),
				] );
				ob_start();
				if ( 'yes' === $atts['international'] ) {
					woocommerce_form_field( 'shipping_country', apply_filters( 'csz_cities_shortcode_country_args', [
						'type'		=> 'country',
						'required'	=> true,
						'label'		=> $atts['country_label'],
						'class'		=> ! empty( $atts['country_class'] ) ? $atts['country_class'] : $atts['class'],
						'description'	=> ! empty( $atts['country_description'] ) ? $atts['country_description'] : ' ',
					] ), $atts['country'] );
				}
				if ( 'yes' === get_option( 'wc_csz_new_state_field' ) && in_array( WC()->countries->get_base_country(), get_option( 'wc_csz_countries_codes' ) ) ) {
					$country_states = [];
					include( 'i18n/cities/' . WC()->countries->get_base_country() . '.php' );
					$country_states = apply_filters( 'csz_states', $country_states );
					if ( apply_filters( 'csz_sort_states', true ) ) {
						asort( $country_states );
					}
					woocommerce_form_field( 'shipping_new_state', apply_filters( 'csz_cities_shortcode_state_filter_args', [
						'type'		=> 'select',
						'options'	=> [ '' => '' ] + $country_states,
						'class'		=> $atts['class'],
						'input_class'	=> [ 'state_select' ],
						'label'		=> __( 'State / County', 'woocommerce' ),
					] ) );
				}
				woocommerce_form_field( 'shipping_state', $state_args, $atts['value'] );
				return ( isset( $prefix ) ? $prefix : '' ) . ob_get_clean() . ( isset( $suffix ) ? $suffix : '' );
			}
		}

		/**
		 * Match shipping zone by location
		 * @return mixed
		 */
		public function csz_match_shipping_zone() {
			$country = wc_clean( $_POST['country'] );
			$state = wc_clean( $_POST['state'] );
			$shipping_zone = wc_get_shipping_zone( [
				'destination' => [
					'country'	=> $country,
					'state'		=> $state,
					'postcode'	=> '',
				]
			] );
			$shipping_methods = $shipping_zone->get_shipping_methods( true );
			if ( ! empty( $shipping_methods ) ) {
				if ( apply_filters( 'csz_shortcode_force_customer_session_enabled', false ) && isset( WC()->session ) && ! WC()->session->has_session() ) {
					WC()->session->set_customer_session_cookie( true );
				}
				WC()->customer->set_billing_country( $country );
				WC()->customer->set_billing_state( $state );
				WC()->customer->set_shipping_country( $country );
				WC()->customer->set_shipping_state( $state );
				$display_prices_including_tax = WC()->cart->display_prices_including_tax();
				foreach ( $shipping_methods as $shipping_method ) {
					$shipping_method_tax = $shipping_method_cost = '';
					if ( ! empty( $shipping_method->cost ) ) {
						if ( $display_prices_including_tax && $shipping_method->is_taxable() ) {
							$shipping_method_tax = array_sum( WC_Tax::calc_shipping_tax( $shipping_method->cost, WC_Tax::get_shipping_tax_rates() ) );
						}
						$shipping_method_cost = ': ' . wc_price( ! empty( $shipping_method_tax ) ? $shipping_method->cost + $shipping_method_tax : $shipping_method->cost );
					}
					$methods_formatted[] = apply_filters( 'csz_cities_shortcode_shipping_method_title', $shipping_method->title, $shipping_method ) . ( $shipping_method_cost ?? '' );
				}
			}
			wp_send_json( apply_filters( 'csz_cities_shortcode_match_zone', ! empty( $methods_formatted ) ? implode( ', ', $methods_formatted ) : __( 'No shipping methods offered to this zone.', 'woocommerce' ), $shipping_methods, $shipping_zone ) );
		}

		/**
		 * Call the shipping zone match ajax and display results
		 */
		public function csz_match_javascript() {
			do_action( 'csz_shortcode_before_js' );
			foreach ( WC()->countries->get_states() as $country => $states ) {
				if ( ! empty( $states ) ) {
					$countries[] = $country;
				}
			}
			?>
			<script type="text/javascript">
			jQuery( function( $ ) {
				var stl = $( '#shipping_state_field' ).find( 'label' ).html(), req = '<abbr class="required" title="<?php _e( 'required', 'woocommerce' ); ?>"> *</abbr>';
				$( document ).ready( function() {
					if ( $( '#shipping_country_field' ).length ) {
						if ( $( '#shipping_country' ).val() != '' ) {
							show_hide_state();
							$( document.body ).trigger( 'match_shipping_zone' );
						}
					} else if ( $( '#shipping_state' ).val() != '' ) {
						$( document.body ).trigger( 'match_shipping_zone' );
					}
				} );
				$( '#shipping_country' ).on( 'select2:select', function() {
					show_hide_state();
					$( document.body ).trigger( 'match_shipping_zone' );
				} );
				$( '#shipping_state' ).on( 'select2:select', function() {
					$( document.body ).trigger( 'match_shipping_zone' );
				} );
				function show_hide_state() {
					if ( $.inArray( $( '#shipping_country' ).val(), <?php echo wp_json_encode( apply_filters( 'csz_cities_shortcode_states_restricted_zones_countries', $countries ?? [] ) ); ?> ) > -1 ) {
						if ( $( '#shipping_country' ).val() == '<?php echo WC()->countries->get_base_country(); ?>' ) {
							label = stl;
						} else if ( $.inArray( $( '#shipping_country' ).val(), <?php echo wp_json_encode( get_option( 'wc_csz_countries_codes' ) ) ; ?> ) > -1 ) {
							label = '<?php _e( 'City', 'woocommerce' ); ?>' + req;
						} else {
							label = '<?php _e( 'State / County', 'woocommerce' ); ?>' + req;
						}
						$( '#shipping_state_field' ).find( 'label' ).html( label );
						$( '#shipping_state_field' ).show();
						$( '#shipping_state' ).on( 'select2:select', function() {
							$( document.body ).trigger( 'match_shipping_zone' );
						} );
					} else {
						$( '#shipping_state_field' ).hide();
					}
				}
				$( document.body ).on( 'match_shipping_zone', function() {
					var data = {
						action:		'csz_match_shipping_zone',
						country:	$( '#shipping_country_field' ).is( ':visible' ) ? $( '#shipping_country' ).val() : '<?php echo WC()->countries->get_base_country(); ?>',
						state:		$( '#shipping_state_field' ).is( ':visible' ) ? $( '#shipping_state' ).val() : '',
					};
					$.post( '<?php echo admin_url( 'admin-ajax.php' ); ?>', data, function( response ) {
						$( document ).triggerHandler( 'zone_matched', response );
					} );
					$( document ).on( 'zone_matched', function( event, response ) {
						if ( $( '#shipping_state_field' ).is( ':visible' ) ) {
							$( '#shipping_country-description' ).html( '' );
							$( '#shipping_state-description' ).html( response ).show();
						} else {
							$( '#shipping_country-description' ).html( response ).show();
						}
					} );
				} );
				<?php if ( 'yes' === get_option( 'wc_csz_new_state_field' ) ) : ?>
					var store_country = '<?php echo WC()->countries->get_base_country(); ?>';
					$( document ).ready( function() {
						new_state_visibility();
					} );
					$( '#shipping_country' ).on( 'select2:select', function() {
						new_state_visibility();
					} );
					$( '#shipping_state' ).on( 'select2:open', function() { filter_states() } ).on( 'select2:select', function() { state_update(); } );
					function new_state_visibility() {
						if ( ! $( '#shipping_country' ).length || $( '#shipping_country' ).val() == store_country ) {
							$( '#shipping_new_state_field' ).show();
							$( '#shipping_state' ).on( 'select2:open', function() { filter_states() } ).on( 'select2:select', function() { state_update() } );
						} else {
							$( '#shipping_new_state_field' ).hide();
						}
					}
					function filter_states() {
						if ( ! $( '#shipping_country' ).length || $( '#shipping_country' ).val() == store_country ) {
							$( '#shipping_state' ).data( 'select2' ).dropdown.$search.val( $( '#shipping_new_state option:selected' ).text() + ' - ' ).trigger( 'input' ).trigger( 'query', { term } );
						}
					}
					function state_update() {
						$( '#shipping_new_state option' ).filter( function() { return $( this ).text() == $( '#shipping_state option:selected' ).text().split( ' - ' )[0]; } ).prop( 'selected', true ).trigger( 'change' );
					}
				<?php endif; ?>
			} );
			</script>
		<?php
		}

		/**
		 * Show or hide cities modal
		 */
		public function csz_modal_javascript() {
			?>
			<style>
			.site-header {
				z-index: unset;
			}
			.cities-modal {
				visibility: hidden;
				position: fixed;
				z-index: 1;
				left: 0;
				top: 0;
				width: 100%;
				height: 100%;
				background-color: rgba(0, 0, 0, 0.4);
				overflow: auto;
			}
			.cities-modal-container {
				background-color: lightblue;
				margin: 8% auto;
				padding: 10px 8px;
				border: 1px solid #888;
				width: 30%;
				height: 65%;
				min-width: 200px;
				box-shadow: 1px 1px 1px 1px;
				border-radius: 10px;
			}
			.cities-modal-content {
				height: 99%;
				background-color: white;
				padding: 15px;
			}
			.cities-modal-close {
				color: #aaa;
				float: right;
				font-size: 28px;
				font-weight: bold;
			}
			.cities-modal-close:hover,
			.cities-modal-close:focus {
				color: black;
				text-decoration: none;
				cursor: pointer;
			}
			.cities-modal-title {
				text-align: center;
			}
			</style>
			<script type="text/javascript">
			jQuery( function( $ ) {
				$( document ).on( 'cities_modal_show', function() {
					$( '.cities-modal' ).show();
					$( document.body ).trigger( 'match_shipping_zone' );			
				} );
				$( document ).on( 'cities_modal_hide', function() {
					$( '.cities-modal' ).hide();
				} );
				$( document ).ready( function() {
					$( '.cities-modal' ).css( 'visibility', 'visible' ).hide();
				} );
				$( document ).on( 'click', function( event ) {
					if ( $( event.target ).closest( '#cities-update' ).length ) {
						event.preventDefault();
						$( document ).trigger( 'cities_modal_show' );
					} else if ( $( event.target ).closest( '.cities-modal-close' ).length ) {
						event.preventDefault();
						$( document ).trigger( 'cities_modal_hide' );
					} else if ( ! $( event.target ).closest( '.cities-modal-container, .select2-container' ).length ) {
						$( document ).trigger( 'cities_modal_hide' );
					}
				} );
				$( document ).on( 'keydown', function( event ) {
					if ( event.key === 'Escape' ) {
						$( document ).trigger( 'cities_modal_hide' );
					}
				} );
				$( document ).on( 'zone_matched', function( event, response ) {
					$( '#cities-update' ).html( $( '#shipping_state option:selected' ).text() || $( '#shipping_country option:selected' ).text() || '<?php _e( 'Select an option&hellip;', 'woocommerce' ) ?>' );
				} );
			} );
			</script>
		<?php
		}

		/**
		 * Load countries cities
		 * @param mixed $states
		 * @return mixed
		 */
		public function wc_cities( $states ) {
			if ( ! is_wc_endpoint_url( 'order-received' ) && ! ( is_admin() && function_exists( 'get_current_screen' ) && isset( get_current_screen()->post_type ) && 'shop_order' === get_current_screen()->post_type ) && apply_filters( 'csz_enable_cities', true ) ) {
				foreach ( get_option( 'wc_csz_countries_codes' ) as $country_code ) {
					$states[ $country_code ] = [];
					$country_cities = $country_states = '';
					include( 'i18n/cities/' . $country_code . '.php' );
					if ( $country_states ) {
						foreach ( apply_filters( 'csz_states', $country_states ) as $state_code => $state ) {
							if ( isset( $country_cities[ $state_code ] ) ) {
								if ( ( ! is_admin() || apply_filters( 'csz_admin_city_state_prefix', false ) ) && 'yes' === get_option( 'wc_csz_new_state_field' ) && $country_code === WC()->countries->get_base_country() ) {
									foreach ( $country_cities[ $state_code ] as $city_code => $city_name ) {
										$country_cities[ $state_code ][ $city_code ] = $state . ' - ' . $city_name;
									}
								}
								$states[ $country_code ] += $country_cities[ $state_code ];
							}
						}
					} else {
						$states[ $country_code ] = $country_cities;
					}
				}
				$states = apply_filters( 'csz_cities', $states );
				if ( ! is_admin() && 'yes' === get_option( 'wc_csz_checkout_restrict_states' ) ) {
					for ( $i = 1; $i < apply_filters( 'csz_max_shipping_zone_id', 100 ); $i++ ) {
						$shipping_zone = WC_Shipping_Zones::get_zone( $i );
						if ( $shipping_zone && apply_filters( 'csz_restricted_selling_locations_shipping_zone_enabled', true, $shipping_zone ) ) {
							foreach ( $shipping_zone->get_zone_locations() as $zone_location ) {
								switch ( $zone_location->type ) {
									case 'country':
										if ( isset( $states[ $zone_location->code ] ) ) {
											$selected_states[ $zone_location->code ] = $states[ $zone_location->code ];
										}
										break;
									case 'state':
										$country = substr( $zone_location->code, 0, 2 );
										$city = substr( $zone_location->code, 3 );
										$selected_states[ $country ][ $city ] = $states[ $country ][ $city ];
										break;
								}
							}
						}
					}
					foreach ( get_option( 'wc_csz_countries_codes' ) as $country_code ) {
						if ( isset( $selected_states[ $country_code ] ) ) {
							$states[ $country_code ] = $selected_states[ $country_code ];
						}
					}
				}
				foreach ( get_option( 'wc_csz_countries_codes' ) as $country_code ) {
					if ( isset( $states[ $country_code ] ) && apply_filters( 'csz_sort_cities', true, $country_code ) ) {
						asort( $states[ $country_code ] );
					}
				}
			}
			return $states;
		}

		/**
		 * Set the order city and state fields based on selection on checkout
		 * @param mixed $order
		 * @param mixed $data
		 */
		public function wc_checkout_copy_state_city( $order, $data ) {
			if ( isset( $data['billing_country'], $data['billing_state'] ) && in_array( $data['billing_country'], get_option( 'wc_csz_countries_codes' ) ) ) {
				$city_value = WC()->countries->get_states( $data['billing_country'] )[ $data['billing_state'] ];
				$city_full_name = $city_value ?? $data['billing_state'];
				$billing_city = 'yes' !== get_option( 'wc_csz_new_state_field' ) || $data['billing_country'] !== WC()->countries->get_base_country() || false === strpos( $city_full_name, ' - ' ) ? $city_full_name : explode( ' - ', $city_full_name, 2 )[1];
				$order->set_billing_city( $billing_city );
				$order->set_billing_state( '' );
				if ( apply_filters( 'csz_customer_account_city_update_enabled', true ) ) {
					update_user_meta( $order->get_user_id(), 'billing_city', $billing_city );
				}
				if ( 'yes' === apply_filters( 'csz_populate_state', get_option( 'wc_csz_populate_state' ), $data['billing_country'] ) ) {
					$billing_state_name = $country_states = '';
					include( 'i18n/cities/' . $data['billing_country'] . '.php' );
					if ( $country_states ) {
						foreach ( apply_filters( 'csz_states', $country_states ) as $state_code => $state ) {
							if ( isset( $country_cities[ $state_code ][ $data['billing_state'] ] ) ) {
								if ( isset( ( include WC()->plugin_path() . '/i18n/states.php' )[ $data['billing_country'] ][ $state_code ] ) ) {
									$order->set_billing_state( $state_code );
								} else {
									$order->set_billing_state( $state );
								}
								break;
							}
						}
					}
				}
			}
			if ( isset( $data['shipping_country'], $data['shipping_state'] ) && in_array( $data['shipping_country'], get_option( 'wc_csz_countries_codes' ) ) ) {
				$city_value = WC()->countries->get_states( $data['shipping_country'] )[ $data['shipping_state'] ];
				$city_full_name = $city_value ?? $data['shipping_state'];
				$shipping_city = 'yes' !== get_option( 'wc_csz_new_state_field' ) || $data['shipping_country'] !== WC()->countries->get_base_country() || false === strpos( $city_full_name, ' - ' ) ? $city_full_name : explode( ' - ', $city_full_name, 2 )[1];
				$order->set_shipping_city( $shipping_city );
				$order->set_shipping_state( '' );
				if ( apply_filters( 'csz_customer_account_city_update_enabled', true ) ) {
					update_user_meta( $order->get_user_id(), 'shipping_city', $shipping_city );
				}
				if ( 'yes' === apply_filters( 'csz_populate_state', get_option( 'wc_csz_populate_state' ), $data['shipping_country'] ) ) {
					$shipping_state_name = $country_states = '';
					include( 'i18n/cities/' . $data['shipping_country'] . '.php' );
					if ( $country_states ) {
						foreach ( apply_filters( 'csz_states', $country_states ) as $state_code => $state ) {
							if ( isset( $country_cities[ $state_code ][ $data['shipping_state'] ] ) ) {
								if ( isset( ( include WC()->plugin_path() . '/i18n/states.php' )[ $data['shipping_country'] ][ $state_code ] ) ) {
									$order->set_shipping_state( $state_code );
								} else {
									$order->set_shipping_state( $state );
								}
								break;
							}
						}
					}
				}
			}
			if ( 'yes' === get_option( 'wc_csz_new_state_field' ) ) {
				if ( isset( $data['billing_new_state'] ) ) {
					$order->delete_meta_data( '_billing_new_state' );
				}
				if ( isset( $data['shipping_new_state'] ) ) {
					$order->delete_meta_data( '_shipping_new_state' );
				}
			}
		}

		/**
		 * Copy from the state field to the city field on account page
		 * @param mixed $user_id
		 * @param mixed $load_address
		 */
		public function wc_customer_copy_state_city( $user_id, $load_address ) {
			if ( apply_filters( 'csz_customer_account_city_update_enabled', true ) ) {
				$customer_country_code = get_user_meta( $user_id, $load_address . '_country', true );
				if ( in_array( $customer_country_code, get_option( 'wc_csz_countries_codes' ) ) ) {
					if ( 'yes' !== get_option( 'wc_csz_new_state_field' ) || $customer_country_code !== WC()->countries->get_base_country() ) {
						update_user_meta( $user_id, $load_address . '_city', WC()->countries->get_states( $customer_country_code )[ get_user_meta( $user_id, $load_address . '_state', true ) ] );
					} else {
						update_user_meta( $user_id, $load_address . '_city', explode( ' - ', WC()->countries->get_states( $customer_country_code )[ get_user_meta( $user_id, $load_address . '_state', true ) ], 2 )[1] );
					}
				}
			}
		}

		/**
		 * Add city label to the placeholder of the state field in admin edit profile
		 * @param mixed $fields
		 * @return fields
		 */
		public function wc_admin_modify_state_label( $fields ) {
			$fields['billing']['fields']['billing_state']['description'] .= ' / ' . __( 'City', 'woocommerce' );
			$fields['shipping']['fields']['shipping_state']['description'] .= ' / ' . __( 'City', 'woocommerce' );
			return $fields;
		}

		/**
		 * Allow to apply tax per state for the countries the plugin apply on
		 * @param mixed $address
		 * @return mixed
		 */
		public function wc_change_tax_address( $address ) {
			if ( apply_filters( 'csz_enable_tax_per_state', false ) && in_array( $address[0], get_option( 'wc_csz_countries_codes' ) ) ) {
				$address[3] = $address[1];
				include( 'i18n/cities/' . $address[0] . '.php' );
				if ( isset( $country_states ) ) {
					foreach ( array_keys( apply_filters( 'csz_states', $country_states ) ) as $state_code ) {
						if ( isset( $country_cities[ $state_code ][ $address[1] ] ) ) {
							$address[1] = $state_code;
							break;
						}
					}
				}
			}
			return $address;
		}

		/**
		 * Fix the display of the address for the countries the plugin apply on
		 * @param mixed $address_formats
		 * @return mixed
		 */
		public function wc_modify_address_formats( $address_formats ) {
			if ( is_cart() || is_wc_endpoint_url( 'edit-address' ) ) {
				foreach ( get_option( 'wc_csz_countries_codes' ) as $country_code ) {
					$address_formats[ $country_code ] = str_replace( [ '{city}', '{city_upper}' ], '{state}', str_replace( [ '{state}', '{state_code}', '{state_upper}' ], '', $address_formats[ isset( $address_formats[ $country_code ] ) ? $country_code : 'default' ] ) );
				}
			}
			return $address_formats;
		}

		/**
		 * Prevent the city of the store from beeing the default customer city
		 * @param mixed $default_location
		 * @return mixed
		 */
		function wc_remove_default_state_city( $default_location ) {
			$country = explode( ':', $default_location )[0];
			return apply_filters( 'csz_customer_default_location_city_disabled', true ) && in_array( $country, get_option( 'wc_csz_countries_codes' ) ) ? $country : $default_location;
		}

		/**
		 * Change fields variables by locale
		 * @param mixed $locale
		 * @return mixed
		 */
		public function wc_locale_state_city( $locale ) {
			foreach ( get_option( 'wc_csz_countries_codes' ) as $country_code ) {
				$locale[ $country_code ]['state']['required'] = $locale[ $country_code ]['city']['hidden'] = true;
				$locale[ $country_code ]['state']['label'] = __( 'City', 'woocommerce' );
				$locale[ $country_code ]['state']['priority'] = 45;
				$locale[ $country_code ]['state']['class'][] = 'update_totals_on_change';
				$locale[ $country_code ]['city']['required'] = false;
			}
			return $locale;
		}

		/**
		 * Add state filter into the shipping calculator
		 * @param bool $state_enabled
		 * @return bool
		 */
		public function wc_cart_state_filter( $state_enabled ) {
			if ( did_action( 'woocommerce_before_shipping_calculator' ) && ! did_action( 'woocommerce_after_shipping_calculator' ) && $state_enabled && in_array( WC()->countries->get_base_country(), get_option( 'wc_csz_countries_codes' ) ) ) {
				$country_states = [];
				include( 'i18n/cities/' . WC()->countries->get_base_country() . '.php' );
				$country_states = apply_filters( 'csz_states', $country_states );
				if ( apply_filters( 'csz_sort_states', true ) ) {
					asort( $country_states );
				}
				woocommerce_form_field( 'calc_shipping_new_state', apply_filters( 'csz_shipping_calculator_state_filter_args', [
					'type'		=> 'select',
					'options'	=> [ '' => '' ] + $country_states,
					'class'		=> [ 'form-row-wide', 'address-field' ],
					'input_class'	=> [ 'state_select' ],
					'placeholder'	=> __( 'State / County', 'woocommerce' ),
				] ) );
				?>
				<script type="text/javascript">
				jQuery( function( $ ) {
					var country = '<?php echo WC()->countries->get_base_country(); ?>';
					$( document ).ready( function() {
						new_state_visibility();
					} );
					$( document ).on( 'click', '.shipping-calculator-button', function() {
						$( '#calc_shipping_country' ).on( 'select2:select', function() {
							new_state_visibility();
						} );
						$( '#calc_shipping_state' ).on( 'select2:open', function() { filter_states() } ).on( 'select2:select', function() { state_update() } );
					} );
					function new_state_visibility() {
						if ( $( '#calc_shipping_country' ).val() == country ) {
							$( '#calc_shipping_new_state_field' ).show();
							$( '#calc_shipping_state' ).on( 'select2:open', function() { filter_states() } ).on( 'select2:select', function() { state_update() } );
						} else {
							$( '#calc_shipping_new_state_field' ).hide();
						}
					}
					function filter_states() {
						if ( $( '#calc_shipping_country' ).val() == country ) {
							$( '#calc_shipping_state' ).data( 'select2' ).dropdown.$search.val( $( '#calc_shipping_new_state option:selected' ).text() + ' - ' ).trigger( 'input' ).trigger( 'query', { term } );
						}
					}
					function state_update() {
						$( '#calc_shipping_new_state option' ).filter( function() { return $( this ).text() == $( '#calc_shipping_state option:selected' ).text().split( ' - ' )[0]; } ).prop( 'selected', true ).trigger( 'change' );
					}
				} );
				</script>
			<?php
			}
			return $state_enabled;
		}

		/**
		 * Add state filter field on checkout and my account
		 * @param mixed $fields
		 * @return mixed
		 */
		function wc_state_filter_field( $fields ) {
			if ( ( is_checkout() || is_wc_endpoint_url( 'edit-address' ) ) && in_array( WC()->countries->get_base_country(), get_option( 'wc_csz_countries_codes' ) ) ) {
				$country_states = [];
				include( 'i18n/cities/' . WC()->countries->get_base_country() . '.php' );
				$country_states = apply_filters( 'csz_states', $country_states );
				if ( apply_filters( 'csz_sort_states', true ) ) {
					asort( $country_states );
				}
				$country_states = [ '' => '' ] + $country_states;
				$fields['new_state'] = [
					'label'		=> __( 'State / County', 'woocommerce' ),
					'type'		=> 'select',
					'options'	=> $country_states,
					'priority'	=> $fields['country']['priority'] + 2,
					'input_class'	=> [ 'state_select' ],
				];
				if ( function_exists( 'is_plugin_active' ) && is_plugin_active( 'woocommerce-checkout-manager/woocommerce-checkout-manager.php' ) ) {
					$_GET['new_state'] = $fields['new_state'];
				}
			}
			return $fields;
		}

		/**
		 * Filter the cities by the selected state
		 */
		public function wc_new_state_dropdown( ) {
			if ( in_array( WC()->countries->get_base_country(), get_option( 'wc_csz_countries_codes' ) ) ) {
				?>
				<script type="text/javascript">
				jQuery( function( $ ) {
					var country = '<?php echo WC()->countries->get_base_country(); ?>';
					$( '#billing_state' ).on( 'select2:open', function() { billing_filter_states() } ).on( 'select2:select', function() { billing_state_update() } );
					$( '#shipping_state' ).on( 'select2:open', function() { shipping_filter_states() } ).on( 'select2:select', function() { shipping_state_update() } );
					if ( <?php echo wp_json_encode( is_checkout() ); ?> ) {
						$( 'body' ).on( 'updated_checkout', function() { check_country() } );
					} else {
						check_country();
						$( '#billing_country, #shipping_country' ).on( 'select2:select', function() { check_country() } );
					}
					function check_country() {
						if ( $( '#billing_country' ).val() == country ) {
							$( '#billing_new_state_field' ).show();
							$( '#billing_state' ).on( 'select2:open', function() { billing_filter_states() } ).on( 'select2:select', function() { billing_state_update() } );
						} else {
							$( '#billing_new_state_field' ).hide();
						}
						if ( $( '#shipping_country' ).val() == country ) {
							$( '#shipping_new_state_field' ).show();
							$( '#shipping_state' ).on( 'select2:open', function() { shipping_filter_states() } ).on( 'select2:select', function() { shipping_state_update() } );
						} else {
							$( '#shipping_new_state_field' ).hide();
						}
					}
					function billing_filter_states() {
						if ( $( '#billing_country' ).val() == country ) {
							$( '#billing_state' ).data( 'select2' ).dropdown.$search.val( $( '#billing_new_state option:selected' ).text() + ' - ' ).trigger( 'input' ).trigger( 'query', { term } );
						}
					}
					function shipping_filter_states() {
						if ( $( '#shipping_country' ).val() == country ) {
							$( '#shipping_state' ).data( 'select2' ).dropdown.$search.val( $( '#shipping_new_state option:selected' ).text() + ' - ' ).trigger( 'input' ).trigger( 'query', { term } );
						}
					}
					function billing_state_update() {
						$( '#billing_new_state option' ).filter( function() { return $( this ).text() == $( '#billing_state option:selected' ).text().split( ' - ' )[0]; } ).prop( 'selected', true ).trigger( 'change' );
					}
					function shipping_state_update() {
						$( '#shipping_new_state option' ).filter( function() { return $( this ).text() == $( '#shipping_state option:selected' ).text().split( ' - ' )[0]; } ).prop( 'selected', true ).trigger( 'change' );
					}
				} );
				</script>
			<?php
			}
		}

		/**
		 * Add the State Filter billing field when wooccm plugin is installed
		 * @param mixed $fields
		 * @return mixed
		 */
		public function wc_wooccm_update_billing_fields( $fields ) {
			if ( ! is_admin() && isset( $_GET['new_state'] ) ) {
				$_GET['new_state']['key'] = 'billing_new_state';
				$fields[ array_search( 'billing_city', array_column( $fields, 'key' ) ) ] = wc_clean( $_GET['new_state'] );
			}
			return $fields;
		}

		/**
		 * Add the State Filter shipping field when wooccm plugin is installed
		 * @param mixed $fields
		 * @return mixed
		 */
		public function wc_wooccm_update_shipping_fields( $fields ) {
			if ( ! is_admin() && isset( $_GET['new_state'] ) ) {
				$_GET['new_state']['key'] = 'shipping_new_state';
				$fields[ array_search( 'shipping_city', array_column( $fields, 'key' ) ) ] = wc_clean( $_GET['new_state'] );
			}
			return $fields;
		}

		/**
		 * Allow custom city in the cart shipping calculator
		 * @param bool $state_enabled
		 * @return bool
		 */
		public function wc_shipping_calculator_custom_state( $state_enabled ) {
			if ( apply_filters( 'csz_enable_custom_city', false ) && apply_filters( 'csz_custom_shipping_city_enabled', true ) && $state_enabled ) {
				?>
				<script type="text/javascript">
				jQuery( function( $ ) {
					var countries = <?php echo wp_json_encode( apply_filters( 'csz_custom_cities_countries', get_option( 'wc_csz_countries_codes' ) ) ); ?>;
					if ( $.inArray( $( '#calc_shipping_country' ).val(), countries ) > -1 ) {
						$( '#calc_shipping_country' ).val( 'default' ).trigger( 'change' );
					}
					$( '#calc_shipping_country' ).on( 'select2:select', function() {
						if ( $.inArray( $( '#calc_shipping_country' ).val(), countries ) > -1 ) {
							$( '#calc_shipping_state' ).select2( { tags: true } );
						} else if ( $( '#calc_shipping_state' ).hasClass( 'select2-hidden-accessible' ) ) {
							$( '#calc_shipping_state' ).select2( { tags: false } );
						}
					} );
				} );
				</script>
			<?php
			}
			return $state_enabled;
		}

		/**
		 * Allow custom city in the checkout
		 * @param mixed $fields
		 * @return mixed
		 */
		public function wc_enable_custom_state( $fields ) {
			if ( apply_filters( 'csz_enable_custom_city', false ) ) {
				$state_desc = _x( 'If your city is not present write its name and select it', 'plugin' ,'cities-shipping-zones-for-woocommerce' );
				$fields['billing']['billing_state']['description'] = $state_desc;
				if ( apply_filters( 'csz_custom_shipping_city_enabled', true ) ) {
					$fields['shipping']['shipping_state']['description'] = $state_desc;
				}
				?>
				<script type="text/javascript">
				jQuery( function( $ ) {
					var city_added = false, countries = <?php echo wp_json_encode( apply_filters( 'csz_custom_cities_countries', get_option( 'wc_csz_countries_codes' ) ) ); ?>;
					$( 'body' ).on( 'updated_checkout', function() {
						if ( $.inArray( $( '#billing_country' ).val(), countries ) > -1 ) {
							$( '#billing_state' ).select2( { tags: true } );
							if ( ! city_added ) {
								city_added = true;
								<?php $shipping_country = WC()->checkout->get_value( 'shipping_country' );
								$shipping_state = WC()->checkout->get_value( 'shipping_state' );
								if ( in_array( $shipping_country, apply_filters( 'csz_custom_cities_countries', get_option( 'wc_csz_countries_codes' ) ) ) && ! empty( $shipping_state ) && ! isset( WC()->countries->get_states( $shipping_country )[ $shipping_state ] ) ) : ?>
								var sc = '<?php echo $shipping_country; ?>', ss = '<?php echo $shipping_state; ?>';
								if ( $( '#billing_country' ).val() == sc ) {
									$( '#billing_state' ).append( new Option( ss, ss, false, false ) );
									if ( $( '#billing_state' ).val() == '' ) {
										$( '#billing_state' ).val( ss ).trigger( 'change' );
									}
								}
								<?php endif; ?>
							}
						} else if ( $( '#billing_state' ).hasClass( 'select2-hidden-accessible' ) ) {
							$( '#billing_state' ).select2( { tags: false } );
						}
						if ( <?php echo wp_json_encode( apply_filters( 'csz_custom_shipping_city_enabled', true ) ); ?> ) {
							if ( $.inArray( $( '#shipping_country' ).val(), countries ) > -1 ) {
								$( '#shipping_state' ).select2( { tags: true } );
							} else if ( $( '#shipping_state' ).hasClass( 'select2-hidden-accessible' ) ) {
								$( '#shipping_state' ).select2( { tags: false } );
							}
						}
					} );
				} );
				</script>
			<?php
			}
			return $fields;
		}

		/**
		 * Disable State validation to allow custom city
		 * @param mixed $fields
		 * @param mixed $errors
		 */
		public function wc_disable_state_validation( $fields, $errors ) {
			if ( apply_filters( 'csz_enable_custom_city', false ) ) {
				if ( in_array( $fields['billing_country'], apply_filters( 'csz_custom_cities_countries', get_option( 'wc_csz_countries_codes' ) ) ) ) {
					$errors->remove( 'billing_state_validation' );
				}
				if ( apply_filters( 'csz_custom_shipping_city_enabled', true ) && in_array( $fields['shipping_country'], apply_filters( 'csz_custom_cities_countries', get_option( 'wc_csz_countries_codes' ) ) ) ) {
					$errors->remove( 'shipping_state_validation' );
				}
			}
		}

		/**
		 * Add cities total sales report tab
		 * @param mixed $reports
		 * @return mixed
		 */
		public function wc_admin_cities_report_orders_tab( $reports ) {
			if ( isset( $reports['orders']['reports'] ) ) {
				$city_tab = [
					'sales_by_city' => [
						'title'		=> _x( 'Sales by city', 'plugin', 'cities-shipping-zones-for-woocommerce' ),
						'description'	=> date( 'Y' ) . ': ' . __( 'Total sales', 'woocommerce' ),
						'hide_title'	=> true,
						'callback'	=> [ $this, 'wc_sales_by_city' ],
					]
				];
				$reports['orders']['reports'] = array_merge( $reports['orders']['reports'], $city_tab );
			}
			return $reports;
		}

		/**
		 * Calculate cities total sales
		 */
		public function wc_sales_by_city() {
			$orders = wc_get_orders( apply_filters( 'csz_sales_by_city_args',  [
				'limit' => -1,
				'type' => 'shop_order',
				'status' => 'completed',
				'date_completed' => '>' . date( 'Y' ) . '-01-01',
			] ) );
			foreach ( $orders as $order ) {
				if ( ! empty( $order->get_billing_country() ) && 0 < $order->get_total() ) {
					$order_country = $order->get_billing_country();
					$order_city = ! empty( $order->get_billing_city() ) ? $order->get_billing_city() : 'None';
					if ( isset( $totals[ $order_country ][ $order_city ] ) ) {
						$totals[ $order_country ][ $order_city ] += $order->get_total();
					} else {
						$totals[ $order_country ][ $order_city ] = $order->get_total();
					}
				}
			}
			if ( ! empty( $totals ) ) {
				foreach ( $totals as $country => $country_total ) {
					echo '<table><h3>' . esc_html__( 'Country / Region', 'woocommerce' ) . ': ' . WC()->countries->countries[ $country ] . '</h3>';
					echo '<tr><th>' . esc_html__( 'City', 'woocommerce' ) . '</th><th>' . esc_html__( 'Total', 'woocommerce' ) . '</th></tr>';
					arsort( $country_total );
					foreach ( $country_total as $city => $city_total ) {
						echo '<tr><td>' . esc_html( $city ) . '</td><td>' . wc_price( $city_total ) . '</td></tr>';
					}
					echo '</table><br>';
				}
			}
		}

		/**
		 * Add column of shipping city
		 * @param mixed $columns
		 * @return mixed
		 */
		public function wc_add_custom_shop_order_column( $columns ) {
			$order_total = $columns['order_total'];
			$wc_actions = $columns['wc_actions'];
			unset( $columns['wc_actions'] );
			unset( $columns['order_total'] );
			$columns['shipping_city'] = __( 'Shipping City', 'woocommerce' );
			$columns['order_total'] = $order_total;
			$columns['wc_actions'] = $wc_actions;
			return $columns;
		}

		/**
		 * Set the shipping city column value
		 * @param mixed $column
		 */
		public function wc_shop_order_column_meta_field_value( $column ) {
			if ( 'shipping_city' === $column ) {
				global $post, $the_order;
				if ( ! is_a( $the_order, 'WC_Order' ) ) {
					$the_order = wc_get_order( $post->ID );
				}
				foreach ( $the_order->get_shipping_methods() as $shipping_method ) {
					if ( ! in_array( $shipping_method->get_method_id(), apply_filters( 'woocommerce_local_pickup_methods', [ 'local_pickup' ] ) ) ) {
						echo $the_order->get_shipping_city();
						break;
					}
				}
			}
		}

		/**
		 * Make shipping city column sortable
		 * @param mixed $columns
		 * @return mixed
		 */
		public function wc_shop_order_column_meta_field_sortable( $columns ) {
			return wp_parse_args( [ 'shipping_city' => '_shipping_city' ], $columns );
		}

		/**
		 * Define the shipping city sort values
		 * @param mixed $query
		 */
		public function wc_shop_order_column_meta_field_sortable_orderby( $query ) {
			if ( function_exists( 'get_current_screen' ) ) {
				$screen = get_current_screen();
				if ( isset( $screen, $screen->id ) && 'shop_order' === $screen->id && 'edit.php' === $screen->parent_file ) {
					$orderby = $query->get( 'orderby' );
					$meta_key = '_shipping_city';
					if ( '_shipping_city' === $orderby ) {
						$query->set( 'meta_key', $meta_key );
						$query->set( 'orderby', 'meta_value' );
					}
				}
			}
		}

		/**
		 * Add Distance Fee option to the shipping method instances
		 * @param mixed $settings
		 * @return mixed
		 */
		public function wc_shipping_city_searchable_field( $meta_keys ){
			$meta_keys[] = '_shipping_city';
			return $meta_keys;
		}

		/**
		 * Add Distance Fee option to the shipping method instances
		 * @param mixed $settings
		 * @return mixed
		 */
		public function wc_flat_rate_distance_fee_field( $settings ) {
			foreach ( get_option( 'wc_csz_countries_codes' ) as $country_code ) {
				$src_cities = [];
				$default_src_city = $country_states = '';
				include( 'i18n/cities/' . $country_code . '.php' );
				if ( $country_code === WC()->countries->get_base_country() ) {
					foreach ( glob( plugin_dir_path( __FILE__ ) . "i18n/distances/{$country_code}*.php" ) as $file ) {
						$city_code = basename( $file, '.php' );
						if ( isset( WC()->countries->get_states( $country_code )[ $city_code ] ) ) {
							include $file;
							if ( ! isset( $key_format[ $city_code ] ) || substr( $key_format[ $city_code ], 0, 2 ) === substr( get_locale(), 0, 2 ) ) {
								$src_cities[ $city_code ] = WC()->countries->get_states( $country_code )[ $city_code ];
							}
						}
					}
				}
			}
			if ( isset( $cities_distance ) ) {
				if ( isset( $cities_distance[ WC()->countries->get_base_state() ] ) ) {
					$default_src_city = WC()->countries->get_base_state();
				} else {
					$store_city_code = WC()->countries->get_base_state();
					foreach ( array_keys( $cities_distance ) as $key ) {
						if ( ! isset( $default_src_city ) ) {
							$default_src_city = $key;
						}
						$store_city = ! isset( $key_format[ $key ] ) ? $store_city_code : WC()->countries->get_states( WC()->countries->get_base_country() )[ $store_city_code ];
						if ( isset( $cities_distance[ $key ][ $store_city ] ) && ( ! isset( $cities_distance[ $default_src_city ][ $store_city ] ) || $cities_distance[ $key ][ $store_city ] < $cities_distance[ $default_src_city ][ $store_city ] ) ) {
							$default_src_city = $key;
						}
					}
				}
			}
			foreach ( $settings as $key => $value ) {
				if ( 'cost' === $key ) {
					$arr[ $key ] = $value;
					$arr['distance_fee'] = [
						'title'		=> _x( 'Distance Fee', 'plugin', 'cities-shipping-zones-for-woocommerce' ),
						'type'		=> 'number',
						'default'	=> '',
						'desc_tip'	=> _x( 'Price per KM for domestic shipping.', 'plugin', 'cities-shipping-zones-for-woocommerce' ) . ' ' . _x( 'When distance is not available this shipping method will be disabled.', 'plugin', 'cities-shipping-zones-for-woocommerce' ),
					];
					$arr['src_city'] = [
						'title'		=> _x( 'Calculate from', 'plugin', 'cities-shipping-zones-for-woocommerce' ),
						'desc_tip'	=> _x( 'Closest location to where the products are shipped from.', 'plugin', 'cities-shipping-zones-for-woocommerce' ),
						'type'		=> 'select',
						'options'	=> $src_cities,
						'default'	=> $default_src_city,
					];
				} else {
					$arr[ $key ] = $value;
				}
			}
			return $arr ?? [];
		}

		/**
		 * Calculate distance fee for shipping
		 * @param mixed $rates
		 * @param mixed $package
		 * @return mixed
		 */
		function wc_distance_fee_calc( $rates, $package ) {
			if ( ! empty( $package['destination']['country'] ) && ! empty( $package['destination']['state'] ) ) {
				foreach ( $rates as $rate_id => $rate ) {
					if ( ! empty( get_option( str_replace( ':', '_', "woocommerce_{$rate_id}_settings" ) )['distance_fee'] ) && ! empty( get_option( str_replace( ':', '_', "woocommerce_{$rate_id}_settings" ) )['src_city'] ) ) {
						$per_km = get_option( str_replace( ':', '_', "woocommerce_{$rate_id}_settings" ) )['distance_fee'];
						if ( 0 < $per_km ) {
							$src_city_code = get_option( str_replace( ':', '_', "woocommerce_{$rate_id}_settings" ) )['src_city'];
							include( 'i18n/distances/' . $src_city_code . '.php' );
							$dest_city = ! isset( $key_format[ $src_city_code ] ) ? $package['destination']['state'] : WC()->countries->get_states( $package['destination']['country'] )[ $package['destination']['state'] ];
							if ( isset( $cities_distance[ $src_city_code ][ $dest_city ] ) ) {
								$distance_fee = $cities_distance[ $src_city_code ][ $dest_city ] * $per_km;
								$rates[ $rate_id ]->cost = round( $rate->cost + $distance_fee );
								$shipping_method = WC_Shipping_Zones::get_shipping_method( $rate->get_instance_id() );
								$rate->set_taxes( $shipping_method && $shipping_method->is_taxable() ? WC_Tax::calc_shipping_tax( $rates[ $rate_id ]->cost, WC_Tax::get_shipping_tax_rates() ) : [] );
							} else {
								unset( $rates[ $rate_id ] );
							}
						}
					}
				}
			}
			return $rates;
		}
	}

	/**
	 * Instantiate classes
	 */
	$cities_shipping_zones_for_woocommerce = new WC_CSZ();
};
