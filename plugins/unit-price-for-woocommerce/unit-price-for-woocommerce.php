<?php

/**
 * Plugin Name: Unit Price for WooCommerce
 * Plugin URI: https://en.condless.com/unit-price-for-woocommerce/
 * Description: WooCommerce plugin for configuring products which are sold by units but priced by weight.
 * Version: 1.2
 * Author: Condless
 * Author URI: https://en.condless.com/
 * Developer: Condless
 * Developer URI: https://en.condless.com/
 * Contributors: condless
 * Text Domain: unit-price-for-woocommerce
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
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) || get_site_option( 'active_sitewide_plugins' ) && array_key_exists( 'woocommerce/woocommerce.php', get_site_option( 'active_sitewide_plugins' ) ) ) {

	/**
	 * Unit Price for WooCommerce Class.
	 */
	class WC_UPW {

		/**
		 * Construct class
		 */
		public function __construct() {
			add_action( 'before_woocommerce_init', function() {
				if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
					\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, false );
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
			$this->init_functions();
		}

		/**
		 * Loads text domain for internationalization
		 */
		public function init_textdomain() {
			load_plugin_textdomain( 'unit-price-for-woocommerce', false, dirname( plugin_basename( __FILE__ ) ) . '/i18n/languages' );
		}

		/**
		 * WC settings init
		 */
		public function init_settings() {
			add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), [ $this, 'wc_update_settings_link' ] );
			add_filter( 'plugin_row_meta', [ $this, 'wc_add_plugin_links' ], 10, 4 );
			add_action( 'admin_head', [ $this, 'wc_upw_product_tab_icon' ] );
			add_filter( 'woocommerce_settings_tabs_array', [ $this, 'wc_add_settings_tab' ], 50 );
			add_action( 'woocommerce_settings_tabs_upw', [ $this, 'wc_settings_tab' ] );
			add_action( 'woocommerce_update_options_upw', [ $this, 'wc_update_settings' ] );
			add_filter( 'woocommerce_product_data_tabs', [ $this, 'wc_product_unit_price_tab' ] );
			add_action( 'woocommerce_product_data_panels', [ $this, 'wc_product_unit_price_panel' ] );
			add_action( 'woocommerce_process_product_meta_simple', [ $this, 'wc_save_product_measurment_options' ] );
			add_action( 'woocommerce_product_after_variable_attributes', [ $this, 'wc_add_variation_measurment_options' ], 10, 3 );
			add_action( 'woocommerce_save_product_variation', [ $this, 'wc_save_variation_measurment_options' ], 10 ,2 );
			add_filter( 'woocommerce_admin_settings_sanitize_option_wc_upw_product_quantity_step', [ $this, 'wc_sanitize_option_wc_upw_product_quantity_step' ], 10, 2 );
			add_filter( 'woocommerce_admin_settings_sanitize_option_wc_upw_product_quantity_suffix', [ $this, 'wc_sanitize_option_wc_upw_product_quantity_suffix' ], 10, 2 );
			add_filter( 'woocommerce_admin_settings_sanitize_option_wc_upw_quantity_simple', [ $this, 'wc_sanitize_option_wc_upw_quantity_simple' ], 10, 2 );
			add_filter( 'woocommerce_order_item_display_meta_key', [ $this, 'wc_change_meta_title' ], 10, 3 );
		}

		/**
		 * WC functions init
		 */
		public function init_functions() {
			if ( in_array( 'yes', [ get_option( 'wc_upw_product_quantity_step', 'yes' ), get_option( 'wc_upw_product_measurement', 'yes' ) ] ) ) {
				remove_filter( 'woocommerce_stock_amount', 'intval' );
				add_filter( 'woocommerce_stock_amount', 'floatval' );
				if ( is_admin() ) {
					add_filter( 'woocommerce_quantity_input_step', [ $this, 'wc_admin_quantity_step' ] );
				}
			}
			if ( 'yes' === get_option( 'wc_upw_product_quantity_step', 'yes' ) ) {
				add_filter( 'woocommerce_quantity_input_args', [ $this, 'wc_quantity_input_args' ], 999, 2 );
				add_action( 'woocommerce_available_variation', [ $this, 'wc_variation_min_quantity' ], 999, 3 );
				add_action( 'woocommerce_after_variations_form', [ $this, 'wc_variation_step_quantity' ] );
				add_filter( 'wc_add_to_cart_message_html', [ $this, 'wc_adjust_add_to_cart_message_qty' ], 10, 2 );
				add_filter( 'woocommerce_cart_contents_count', [ $this, 'wc_adjust_cart_contents_count' ] );
			}
			if ( 'yes' === get_option( 'wc_upw_product_measurement', 'yes' ) ) {
				add_action( 'woocommerce_checkout_create_order_line_item', [ $this, 'wc_add_custom_data_to_order' ], 999, 4 );
				add_filter( 'woocommerce_hidden_order_itemmeta', [ $this, 'wc_hide_item_meta' ] );
				add_action( 'woocommerce_admin_order_item_headers', [ $this, 'wc_add_header_on_order_item_view' ] );
				add_action( 'woocommerce_admin_order_item_values', [ $this, 'wc_add_value_on_order_item_view' ], 10, 2 );
				add_filter( 'woocommerce_email_order_item_quantity', [ $this, 'wc_add_quantity_symbol' ], 10, 2 );
				add_filter( 'woocommerce_order_item_quantity_html', [ $this, 'wc_add_quantity_symbol' ], 10, 2 );
				add_filter( 'woocommerce_order_again_cart_item_data', [ $this, 'wc_retrieve_product_org_qty' ], 10, 2 );
				add_filter( 'woocommerce_add_order_again_cart_item', [ $this, 'wc_fix_order_again_product_qty' ] );
				if ( ! is_admin() && 'yes' === get_option( 'wc_upw_product_price_suffix' ) ) {
					add_filter( 'woocommerce_get_price_suffix', [ $this, 'wc_change_product_html' ], 10, 2 );
				}
			}
			if ( 'yes' === get_option( 'wc_upw_quantity_auto_update' ) ) {
				add_filter( 'woocommerce_loop_add_to_cart_args', [ $this, 'wc_add_class' ], 10, 2 );
				add_filter( 'woocommerce_quantity_input_args', [ $this, 'wc_modify_quantity_args' ], 9999, 2 );
				add_action( 'wp_ajax_wc_set_item_quantity', [ $this, 'wc_set_item_quantity' ] );
				add_action( 'wp_ajax_nopriv_wc_set_item_quantity', [ $this, 'wc_set_item_quantity' ] );
				add_action( 'woocommerce_after_shop_loop', [ $this, 'wc_set_item_quantity_js' ] );
			}
			if ( 'yes' === get_option( 'wc_upw_variable_quantity_auto_update' ) ) {
				add_action( 'woocommerce_available_variation', [ $this, 'wc_variation_quantity' ], 9999, 3 );
				add_action( 'wp_ajax_wc_variable_set_item_quantity', [ $this, 'wc_variable_set_item_quantity' ] );
				add_action( 'wp_ajax_nopriv_wc_variable_set_item_quantity', [ $this, 'wc_variable_set_item_quantity' ] );
				add_action( 'woocommerce_after_shop_loop', [ $this, 'wc_variable_set_item_quantity_js' ] );
			}
			if ( in_array( 'yes', [ get_option( 'wc_upw_archive_variations' ), get_option( 'wc_upw_variable_quantity_auto_update' ) ] ) ) {
				add_filter( 'woocommerce_product_get_default_attributes', [ $this, 'wc_set_default_attributes' ], 9999, 2 );
				add_action( 'wp_ajax_wc_ajax_add_to_cart', [ $this, 'wc_ajax_add_to_cart' ] );
				add_action( 'wp_ajax_nopriv_wc_ajax_add_to_cart', [ $this, 'wc_ajax_add_to_cart' ] );
			}
			if ( 'yes' === get_option( 'wc_upw_archive_variations' ) ) {
				add_action( 'init', [ $this, 'wc_change_loop_add_to_cart' ] );
				add_filter( 'woocommerce_after_shop_loop', [ $this, 'wc_ajax_variation' ] );
			}
			if ( 'yes' === get_option( 'wc_upw_quantity_variation' ) && 'yes' !== get_option( 'wc_upw_archive_variations' ) ) {
				add_filter( 'woocommerce_loop_add_to_cart_link', [ $this, 'wc_quantity_input' ], 10, 2 );
				add_action( 'woocommerce_after_shop_loop', [ $this, 'wc_archive_quantity_field' ] );
			}
			if ( 'yes' === get_option( 'wc_upw_product_quantity_suffix' ) ) {
				add_action( 'woocommerce_after_quantity_input_field', [ $this, 'wc_quantity_input_suffix' ] );
				add_action( 'woocommerce_available_variation', [ $this, 'wc_variation_quantity_suffix' ], 10, 3 );
				add_action( 'woocommerce_after_variations_form', [ $this, 'wc_change_variation_quantity_suffix' ] );
				add_filter( 'woocommerce_format_stock_quantity', [ $this, 'upw_stock_quantity_suffix' ], 10, 2 );
				add_filter( 'woocommerce_widget_cart_item_quantity', [ $this, 'wc_mini_cart_quantity' ], 10, 3 );
				add_filter( 'woocommerce_cart_item_quantity', [ $this, 'wc_quantity_suffix' ], 10, 3 );
				add_filter( 'woocommerce_checkout_cart_item_quantity', [ $this, 'wc_checkout_quantity_suffix' ], 10, 3 );
				add_action( 'woocommerce_checkout_create_order_line_item', [ $this, 'wc_add_suffix_to_order_item' ], 10, 4 );
				add_filter( 'woocommerce_email_order_item_quantity', [ $this, 'wc_add_quantity_suffix' ], 10, 2 );
				add_filter( 'woocommerce_order_item_quantity_html', [ $this, 'wc_add_quantity_suffix' ], 10, 2 );
			}
			if ( 'yes' === get_option( 'wc_upw_quantity_simple' ) ) {
				add_filter( 'woocommerce_loop_add_to_cart_link', [ $this, 'wc_quantity_inputs_for_loop_ajax_add_to_cart' ], 10, 2 );
				add_action( 'woocommerce_after_shop_loop', [ $this, 'wc_archives_quantity_fields_script' ] );
			}
			if ( 'yes' === get_option( 'wc_upw_product_price_adjust' ) ) {
				if ( ! is_admin() ) {
					add_filter( 'woocommerce_get_price_html', [ $this, 'wc_adjust_price_display' ], 10, 2 );
				}
				add_filter( 'woocommerce_cart_item_price', [ $this, 'wc_adjust_cart_price_display' ], 10, 2 );
			}
			if ( 'yes' === get_option( 'wc_upw_product_price_quantity' ) ) {
				add_action( 'woocommerce_after_add_to_cart_button', [ $this, 'wc_product_price_quantity' ] );
				add_action( 'woocommerce_after_shop_loop_item', [ $this, 'wc_archive_product_price_quantity' ] );
				add_filter( 'woocommerce_available_variation', [ $this, 'wc_variable_price_quantity' ], 10, 3 );
				add_action( 'wp_footer', [ $this, 'wc_product_price_quantity_js' ] );
			}
			if ( in_array( 'yes', [ get_option( 'wc_upw_quantity_auto_update' ), get_option( 'wc_upw_variable_quantity_auto_update' ), get_option( 'wc_upw_archive_variations' ), get_option( 'wc_upw_quantity_simple' ) ] ) ) {
				add_filter( 'pre_option_woocommerce_enable_ajax_add_to_cart', [ $this, 'wc_enable_ajax_button' ] );
			}
		}

		/**
		 * Add plugin links to the plugin menu
		 * @param mixed $links
		 * @return mixed
		 */
		public function wc_update_settings_link( $links ) {
			array_unshift( $links, '<a href=' . esc_url( add_query_arg( 'page', 'wc-settings&tab=upw', get_admin_url() . 'admin.php' ) ) . '>' . __( 'Settings' ) . '</a>' );
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
				$links_array[] = "<a href=https://$sub_domain.condless.com/unit-price-for-woocommerce/>" . __( 'Docs', 'woocommerce' ) . '</a>';
				$links_array[] = "<a href=https://$sub_domain.condless.com/contact/>" . _x( 'Contact', 'Theme starter content' ) . '</a>';
			}
			return $links_array;
		}

		/**
		 * Add icon to the Unit Price product settings tab
		 */
		public function wc_upw_product_tab_icon() {
			echo '<style>#woocommerce-product-data ul.wc-tabs li.unit_price_options a:before { font-family: WooCommerce; content: "\e006"; } #woocommerce-order-items .woocommerce_order_items_wrapper table.woocommerce_order_items .quantity input { min-width: 60px }</style>';
		}

		/**
		 * Add a new settings tab to the WooCommerce settings tabs array
		 * @param array $settings_tabs
		 * @return array
		 */
		public function wc_add_settings_tab( $settings_tabs ) {
			$settings_tabs['upw'] = __( 'Unit Price', 'unit-price-for-woocommerce' );
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
			$settings = [
				'product_section'	=> [
					'name'	=> __( 'Product', 'woocommerce' ). ' ' . __( 'Settings' ),
					'type'	=> 'title',
					'desc'	=> __( 'The per product settings are in the Unit Price tab of the Edit Product screen', 'unit-price-for-woocommerce' ) . '. <a href=https://' . ( 'he_IL' === get_locale() ? 'www' : 'en' ) . '.condless.com/contact/>' . __( 'Support' ) . '</a>',
					'id'	=> 'wc_upw_product_section'
				],
				'product_measurement'	=> [
					'name'		=> __( 'Quantity Units', 'unit-price-for-woocommerce' ),
					'desc'		=> __( 'Enable to define products which are sold by units but priced by weight', 'unit-price-for-woocommerce' ),
					'type'		=> 'checkbox',
					'default'	=> 'yes',
					'id'		=> 'wc_upw_product_measurement',
				],
				'product_price_suffix'	=> [
					'name'		=> __( 'Weight unit', 'woocommerce' ),
					'desc'		=> __( 'Display the price per kg for products which are sold by units but priced by weight', 'unit-price-for-woocommerce' ),
					'type'		=> 'checkbox',
					'default'	=> 'no',
					'id'		=> 'wc_upw_product_price_suffix',
				],
				'product_quantity_step'	=> [
					'name'		=> __( 'Quantity Step', 'unit-price-for-woocommerce' ),
					'desc'		=> __( 'Enable to define quantity step per product', 'unit-price-for-woocommerce' ),					
					'type'		=> 'checkbox',
					'default'	=> 'yes',
					'id'		=> 'wc_upw_product_quantity_step',
				],
				'product_quantity_suffix'	=> [
					'name'		=> __( 'Quantity Suffix', 'unit-price-for-woocommerce' ),
					'desc'		=> __( 'Enable to define quantity suffix per product', 'unit-price-for-woocommerce' ),
					'type'		=> 'checkbox',
					'default'	=> 'no',
					'id'		=> 'wc_upw_product_quantity_suffix',
				],
				'product_price_adjust'	=> [
					'name'		=> __( 'Price for display', 'unit-price-for-woocommerce' ),
					'desc'		=> __( 'Enable to manipulate the price for display per product', 'unit-price-for-woocommerce' ),
					'type'		=> 'checkbox',
					'default'	=> 'no',
					'id'		=> 'wc_upw_product_price_adjust',
				],
				'product_price_quantity'	=> [
					'name'		=> __( 'Subtotal', 'woocommerce' ),
					'desc'		=> __( 'Display the product price by the selected quantity in real-time', 'unit-price-for-woocommerce' ),
					'type'		=> 'checkbox',
					'default'	=> 'no',
					'id'		=> 'wc_upw_product_price_quantity',
				],
				'product_section_end'	=> [
					'type'	=> 'sectionend',
					'id'	=> 'wc_upw_product_section_end'
				],
				'general_section'	=> [
					'name'	=> __( 'Shop pages', 'woocommerce' ),
					'type'	=> 'title',
					'desc'	=> __( 'Control the product add to cart process', 'unit-price-for-woocommerce' ) . '. ' . __( 'Make sure it is compatible with your theme / plugins.', 'unit-price-for-woocommerce' ),
					'id'	=> 'wc_upw_general_section'
				],
				'quantity_auto_update'	=> [
					'name'		=> __( 'Quantity Auto Update', 'unit-price-for-woocommerce' ),
					'desc'		=> __( 'Auto update the cart by the selected quantity value on archive pages for simple products', 'unit-price-for-woocommerce' ),
					'desc_tip'	=> __( 'Suitable only if they have AJAX quantity input fields there.', 'unit-price-for-woocommerce' ),
					'type'		=> 'checkbox',
					'default'	=> 'no',
					'id'		=> 'wc_upw_quantity_auto_update',
				],
				'variable_quantity_auto_update'	=> [
					'name'		=> __( 'Quantity Auto Update', 'unit-price-for-woocommerce' ) . ' (' . __( 'Variable product', 'woocommerce' ) . ')',
					'desc'		=> __( 'Auto update the cart by the selected quantity value on archive pages for variable products', 'unit-price-for-woocommerce' ),
					'desc_tip'	=> __( 'Suitable only if the variations are selectable from there and they have AJAX quantity input fields.', 'unit-price-for-woocommerce' ),
					'type'		=> 'checkbox',
					'default'	=> 'no',
					'id'		=> 'wc_upw_variable_quantity_auto_update',
				],
				'archive_variations'	=> [
					'name'		=> __( 'Variable product', 'woocommerce' ) . ' (' . __( 'Shop pages', 'woocommerce' ) . ')',
					'desc'		=> __( 'Display the variations selection on archive pages', 'unit-price-for-woocommerce' ),
					'type'		=> 'checkbox',
					'default'	=> 'no',
					'id'		=> 'wc_upw_archive_variations',
				],
				'quantity_variation'	=> [
					'name'		=> __( 'Quantity', 'woocommerce' ) . ' ' . __( 'Variable product', 'woocommerce' ) . ' (' . __( 'Shop pages', 'woocommerce' ) . ')',
					'desc'		=> __( 'Add quantity input fields to variable products on archive pages', 'unit-price-for-woocommerce' ),
					'desc_tip'	=> __( 'Suitable only if the variations are selectable from there.', 'unit-price-for-woocommerce' ),
					'type'		=> 'checkbox',
					'default'	=> 'no',
					'id'		=> 'wc_upw_quantity_variation',
				],
				'quantity_simple'	=> [
					'name'		=> __( 'Quantity', 'woocommerce' ) . ' (' . __( 'Shop pages', 'woocommerce' ) . ')',
					'desc'		=> __( 'Add quantity input fields to simple products on archive pages', 'unit-price-for-woocommerce' ),
					'type'		=> 'checkbox',
					'default'	=> 'no',
					'id'		=> 'wc_upw_quantity_simple',
				],
				'general_section_end'	=> [
					'type'	=> 'sectionend',
					'id'	=> 'wc_upw_general_section_end'
				],
			];
			return apply_filters( 'wc_upw_settings', $settings );
		}

		/**
		 * Sanitize the quantity step option
		 * @param mixed $value
		 * @param mixed $option
		 * @return mixed
		 */
		public function wc_sanitize_option_wc_upw_product_quantity_step( $value, $option ) {
			if ( 'yes' !== get_option( $option['id'] ) && 'yes' === $value && function_exists( 'is_plugin_active' ) ) {
				$theme = wp_get_theme();
				$themes = [ $theme->name, $theme->parent_theme ];
				if ( in_array( 'Bridge', $themes ) ) {
					$integrations[] = __( 'Bridge' );
				} elseif ( in_array( 'Enfold', $themes ) ) {
					$integrations[] = __( 'Enfold' );
				} elseif ( in_array( 'Kapee', $themes ) ) {
					$integrations[] = __( 'Kapee' );
				}
				if ( ! empty( $integrations ) ) {
					WC_Admin_Settings::add_message( __( 'Quantity Step', 'unit-price-for-woocommerce' ) . ' : ' . __( 'Integrations are available for', 'unit-price-for-woocommerce' ) . ' ' . implode( ', ', $integrations ) );
				}
			}
			return $value;
		}

		/**
		 * Sanitize the quantity suffix option
		 * @param mixed $value
		 * @param mixed $option
		 * @return mixed
		 */
		public function wc_sanitize_option_wc_upw_product_quantity_suffix( $value, $option ) {
			if ( 'yes' !== get_option( $option['id'] ) && 'yes' === $value && function_exists( 'is_plugin_active' ) ) {
				$theme = wp_get_theme();
				$themes = [ $theme->name, $theme->parent_theme ];
				if ( in_array( 'Woodmart', $themes ) ) {
					$integrations[] = __( 'WoodMart' );
				} elseif ( in_array( 'Flatsome', $themes ) ) {
					$integrations[] = __( 'Flatsome' );
				}
				if ( is_plugin_active( 'yith-woocommerce-request-a-quote/yith-woocommerce-request-a-quote.php' ) && ! has_filter( 'ywraq_request_quote_view_item_data' ) ) {
					$integrations[] = __( 'Yith Request a Quote for WooCommerce' );
				}
				if ( is_plugin_active( 'qty-increment-buttons-for-woocommerce/qty-increment-buttons-for-woocommerce.php' ) ) {
					$integrations[] = __( 'Qty Increment Buttons for WooCommerce' );
				}
				if ( is_plugin_active( 'wc-quantity-plus-minus-button/init.php' ) ) {
					$integrations[] = __( 'Quantity Plus Minus Button for WooCommerce' );
				}
				if ( ! empty( $integrations ) ) {
					WC_Admin_Settings::add_message( __( 'Quantity Suffix', 'unit-price-for-woocommerce' ) . ' : ' . __( 'Integrations are available for', 'unit-price-for-woocommerce' ) . ' ' . implode( ', ', $integrations ) );
				}
			}
			return $value;
		}

		/**
		 * Sanitize the archive quantity option
		 * @param mixed $value
		 * @param mixed $option
		 * @return mixed
		 */
		public function wc_sanitize_option_wc_upw_quantity_simple( $value, $option ) {
			if ( 'yes' !== get_option( $option['id'] ) && 'yes' === $value && function_exists( 'is_plugin_active' ) ) {
				if ( is_plugin_active( 'ajax-search-for-woocommerce/ajax-search-for-woocommerce.php' ) && ! has_filter( 'upw_quantity_field_simple_enabled' ) ) {
					WC_Admin_Settings::add_message( __( 'Quantity', 'woocommerce' ) . ' (' . __( 'Shop pages', 'woocommerce' ) . ')' . ' : ' . __( 'Integrations are available for', 'unit-price-for-woocommerce' ) . ' ' . __( 'FiboSearch' ) );
				}
			}
			return $value;
		}

		/**
		 * Add custom unit price tab to the products
		 * @param mixed $tabs
		 * @return mixed
		 */
		public function wc_product_unit_price_tab( $tabs ) {
			$tabs['unit_price'] = [
				'label'		=> __( 'Unit Price', 'unit-price-for-woocommerce' ),
				'class'		=> [ 'show_if_simple' ],
				'target'	=> 'unit_price_product_data',
			];
			return $tabs;
		}

		/**
		 * Add the Unit Price settings to products
		 */
		public function wc_product_unit_price_panel() {
			echo '<div id="unit_price_product_data" class="panel woocommerce_options_panel hidden">';
			if ( 'yes' === get_option( 'wc_upw_product_measurement', 'yes' ) ) {
				echo '<div class="options_group"><h5>' . __( 'Suitable if the product is sold by units but priced by weight', 'unit-price-for-woocommerce' ) . '</h5>';
				woocommerce_wp_select( [
					'id'		=> '_upw_measurement',
					'label'		=> __( 'Quantity Units', 'unit-price-for-woocommerce' ),
					'description'	=> __( 'Select weight if the product is sold by units but priced by weight, the quantity will be recalculated based on the weight configured in the Shipping tab.', 'unit-price-for-woocommerce' ),
					'desc_tip'	=> true,
					'options'	=> [
						'none'		=> __( 'None', 'woocommerce' ),
						'weight'	=> __( 'Weight', 'woocommerce' ),
						'mpc'		=> __( 'Measurement Price Calculator', 'measurement-price-calculator' ),
					],
				] );
				echo '</div>';
				if ( in_array( 'yes', [ get_option( 'wc_upw_product_quantity_step' ), get_option( 'wc_upw_product_quantity_suffix' ), get_option( 'wc_upw_product_price_adjust' ) ] ) ) {
					echo '<h5>' . __( 'Suitable if the product is sold by weight', 'unit-price-for-woocommerce' ) . '</h5>';
				}
			}
			if ( 'yes' === get_option( 'wc_upw_product_quantity_step', 'yes' ) ) {
				woocommerce_wp_text_input( [
					'id'		=> '_upw_step',
					'label'		=> __( 'Quantity Step', 'unit-price-for-woocommerce' ),
					'description'	=> __( 'It defines the quantity step, default and the minimum', 'unit-price-for-woocommerce' ),
					'desc_tip'	=> true,
					'type'		=> 'number',
					'custom_attributes' => [ 'step' => 'any', 'min' => '0' ],
				] );
			}
			if ( 'yes' === get_option( 'wc_upw_product_quantity_suffix' ) ) {
				woocommerce_wp_text_input( [
					'id'		=> '_upw_quantity_suffix',
					'label'		=> __( 'Quantity Suffix', 'unit-price-for-woocommerce' ),
					'description'	=> __( 'It defines the quantity suffix', 'unit-price-for-woocommerce' ),
					'desc_tip'	=> true,
				] );
			}
			if ( 'yes' === get_option( 'wc_upw_product_price_adjust' ) ) {
				woocommerce_wp_text_input( [
					'id'		=> '_upw_price_quantity',
					'label'		=> __( 'Price Quantity', 'unit-price-for-woocommerce' ),
					'description'	=> __( 'It defines the quantity which the price will be displayed for', 'unit-price-for-woocommerce' ),
					'desc_tip'	=> true,
					'type'		=> 'number',
					'custom_attributes' => [ 'step' => 'any', 'min' => '0' ],
				] );
				woocommerce_wp_text_input( [
					'id'		=> '_upw_price_suffix',
					'label'		=> __( 'Price Suffix', 'unit-price-for-woocommerce' ),
					'description'	=> __( 'It defines the price suffix', 'unit-price-for-woocommerce' ),
					'desc_tip'	=> true,
				] );
			}
			do_action( 'woocommerce_product_options_unit_price_product_data' );
			echo '</div>';
		}

		/**
		 * Save the products settings
		 * @param mixed $post_id
		 */
		public function wc_save_product_measurment_options( $product ){
			update_post_meta( $product, '_upw_measurement', isset( $_POST['_upw_measurement'] ) ? wc_clean( wp_unslash( $_POST['_upw_measurement'] ) ) : null );
			update_post_meta( $product, '_upw_step', isset( $_POST['_upw_step'] ) && 0 < $_POST['_upw_step'] ? floatval( $_POST['_upw_step'] ) : null );
			update_post_meta( $product, '_upw_quantity_suffix', isset( $_POST['_upw_quantity_suffix'] ) ? wp_kses_post( wp_unslash( $_POST['_upw_quantity_suffix'] ) ) : null );
			update_post_meta( $product, '_upw_price_quantity', isset( $_POST['_upw_price_quantity'] ) && 0 < $_POST['_upw_price_quantity'] ? floatval( $_POST['_upw_price_quantity'] ) : null );
			update_post_meta( $product, '_upw_price_suffix', isset( $_POST['_upw_price_suffix'] ) ? wp_kses_post( wp_unslash( $_POST['_upw_price_suffix'] ) ) : null );
		}

		/**
		 * Add Unit Price settings to product variations
		 * @param mixed $loop
		 * @param mixed $variation_data
		 * @param mixed $variation
		 */
		public function wc_add_variation_measurment_options( $loop, $variation_data, $variation ) {
			if ( 'yes' === get_option( 'wc_upw_product_measurement', 'yes' ) ) {
				echo '<div class="options_group"><h5>' . __( 'Suitable if the product is sold by units but priced by weight', 'unit-price-for-woocommerce' ) . '</h5>';
				woocommerce_wp_select( [
					'id'		=> '_upw_measurement' . '_' . $loop,
					'label'		=> __( 'Quantity Units', 'unit-price-for-woocommerce' ),
					'description'	=> __( 'Select the unit that the quantity will be recalculated based on, which configured in the Shipping tab.', 'unit-price-for-woocommerce' ),
					'desc_tip'	=> true,
					'wrapper_class'	=> 'form-row form-row-full',
					'value'		=> get_post_meta( $variation->ID, '_upw_measurement', true ),
					'options'	=> [
						'none'		=> __( 'None', 'woocommerce' ),
						'weight'	=> __( 'Weight', 'woocommerce' ),
						'mpc'		=> __( 'Measurement Price Calculator', 'measurement-price-calculator' ),
					],
				] );
				echo '</div>';
				if ( in_array( 'yes', [ get_option( 'wc_upw_product_quantity_step' ), get_option( 'wc_upw_product_quantity_suffix' ), get_option( 'wc_upw_product_price_adjust' ) ] ) ) {
					echo '<h5>' . __( 'Suitable if the product is sold by weight', 'unit-price-for-woocommerce' ) . '</h5>';
				}
			}
			if ( 'yes' === get_option( 'wc_upw_product_quantity_step', 'yes' ) ) {
				woocommerce_wp_text_input( [
					'id'		=> '_upw_step' . '_' . $loop,
					'label'		=> __( 'Quantity Step', 'unit-price-for-woocommerce' ),
					'description'	=> __( 'It defines the quantity step, default and the minimum', 'unit-price-for-woocommerce' ),
					'desc_tip'	=> true,
					'type'		=> 'number',
					'wrapper_class' => 'form-row form-row-full',
					'value'		=> get_post_meta( $variation->ID, '_upw_step', true ),
					'custom_attributes' => [ 'step' => 'any', 'min' => '0' ],
				] );
			}
			if ( 'yes' === get_option( 'wc_upw_product_quantity_suffix' ) ) {
				woocommerce_wp_text_input( [
					'id'		=> '_upw_quantity_suffix' . '_' . $loop,
					'label'		=> __( 'Quantity Suffix', 'unit-price-for-woocommerce' ),
					'description'	=> __( 'It defines the quantity suffix', 'unit-price-for-woocommerce' ),
					'desc_tip'	=> true,
					'value'		=> get_post_meta( $variation->ID, '_upw_quantity_suffix', true ),
					'wrapper_class' => 'form-row form-row-full',
				] );
			}
			if ( 'yes' === get_option( 'wc_upw_product_price_adjust' ) ) {
				woocommerce_wp_text_input( [
					'id'		=> '_upw_price_quantity' . '_' . $loop,
					'label'		=> __( 'Price Quantity', 'unit-price-for-woocommerce' ),
					'description'	=> __( 'It defines the quantity which the price will be displayed for', 'unit-price-for-woocommerce' ),
					'desc_tip'	=> true,
					'type'		=> 'number',
					'custom_attributes' => [ 'step' => 'any', 'min' => '0' ],
					'value'		=> get_post_meta( $variation->ID, '_upw_price_quantity', true ),
					'wrapper_class' => 'form-row form-row-full',
				] );
				woocommerce_wp_text_input( [
					'id'		=> '_upw_price_suffix' . '_' . $loop,
					'label'		=> __( 'Price Suffix', 'unit-price-for-woocommerce' ),
					'description'	=> __( 'It defines the price suffix', 'unit-price-for-woocommerce' ),
					'desc_tip'	=> true,
					'value'		=> get_post_meta( $variation->ID, '_upw_price_suffix', true ),
					'wrapper_class' => 'form-row form-row-full',
				] );
			}
		}

		/**
		 * Save the Unit Price settings of product variations
		 * @param mixed $variation_id
		 * @param mixed $loop
		 */
		public function wc_save_variation_measurment_options( $variation_id, $loop ) {
			update_post_meta( $variation_id, '_upw_measurement', isset( $_POST["_upw_measurement_$loop"] ) ? wc_clean( wp_unslash( $_POST["_upw_measurement_$loop"] ) ) : null );
			update_post_meta( $variation_id, '_upw_step', isset( $_POST["_upw_step_$loop"] ) && 0 < $_POST["_upw_step_$loop"] ? floatval( $_POST["_upw_step_$loop"] ) : null );
			update_post_meta( $variation_id, '_upw_quantity_suffix', isset( $_POST["_upw_quantity_suffix_$loop"] ) ? wp_kses_post( wp_unslash( $_POST["_upw_quantity_suffix_$loop"] ) ) : null );
			update_post_meta( $variation_id, '_upw_price_quantity', isset( $_POST["_upw_price_quantity_$loop"] ) && 0 < $_POST["_upw_price_quantity_$loop"] ? floatval( $_POST["_upw_price_quantity_$loop"] ) : null );
			update_post_meta( $variation_id, '_upw_price_suffix', isset( $_POST["_upw_price_suffix_$loop"] ) ? wp_kses_post( wp_unslash( $_POST["_upw_price_suffix_$loop"] ) ) : null );
		}

		/**
		 * Set the display name of the quantity meta field
		 * @param mixed $key
		 * @param mixed $meta
		 * @param mixed $item
		 */
		public function wc_change_meta_title( $key, $meta, $item ) {
			switch ( $key ) {
				case 'unit_qty':		return apply_filters( 'upw_order_qty_label', __( 'QTY in units', 'unit-price-for-woocommerce' ) );
				case '_quantity_suffix':	return apply_filters( 'upw_order_unit_label', __( 'Measurements', 'woocommerce' ) );
			}
			return $key;
		}

		/**
		 * Set the quantity step in admin so the edit order screen will function when decimal quantity was purchased
		 */
		public function wc_admin_quantity_step() {
			return 'any';
		}

		/**
		 * Modify the product quantity args
		 * @param mixed $quantity
		 * @param mixed $item
		 * @return mixed
		 */
		public function wc_quantity_input_args( $args, $product ) {
			$step = $product->get_meta( '_upw_step' );
			if ( $step ) {
				$args['step'] = $step;
				if ( 'quantity' === $args['input_name'] ) {
					$min_value = apply_filters( 'upw_product_min_value', $step, $product );
					$args['min_value'] = 0 == fmod( $min_value, $step ) || apply_filters( 'upw_skip_args_validation', false ) ? $min_value : $step;
					if ( is_single( $product->get_id() ) && is_product() ) {
						$input_value = apply_filters( 'upw_product_input_value', $step, $product );
						$args['input_value'] = 0 == fmod( $input_value, $step ) || apply_filters( 'upw_skip_args_validation', false ) ? $input_value : $step;
					} elseif ( ( 'yes' === get_option( 'wc_upw_quantity_simple' ) || apply_filters( 'upw_force_archive_input_value_enabled', false ) ) && apply_filters( 'upw_step_input_value_archive_enabled', 0 != fmod( 1, $step ), $step ) && ( is_shop() || is_product_category() ) ) {
						$args['input_value'] = $step;
					}
				}
			}
			return $args;
		}

		/**
		 * Set the variation quantity input values
		 * @param mixed $args
		 * @param mixed $product
		 * @param mixed $variation
		 * @return mixed
		 */
		public function wc_variation_min_quantity( $args, $product, $variation ) {
			$step = $variation->get_meta( '_upw_step' );
			if ( $step ) {
				$args['step'] = $step;
				$min_qty = apply_filters( 'upw_variation_min_qty', $step, $product, $variation );
				$args['min_qty'] = 0 == fmod( $min_qty, $step ) || apply_filters( 'upw_skip_args_validation', false ) ? $min_qty : $step;
				$input_value = apply_filters( 'upw_variation_input_value', $step, $product, $variation );
				$args['input_value'] = ( 0 == fmod( $input_value, $step ) || apply_filters( 'upw_skip_args_validation', false ) ) && $args['min_qty'] <= $input_value ? $input_value : $args['min_qty'];
			}
			return $args;
		}

		/**
		 * Apply the variation quantity input values
		 */
		public function wc_variation_step_quantity() {
			if ( 2 > did_action( 'woocommerce_after_variations_form' ) ) {
			?>
				<script type="text/javascript">
				jQuery( function( $ ) {
					$( 'form.variations_form' ).on( 'show_variation', function( event, data ) {
						$( this ).parent().find( 'input.qty' ).attr( 'step', data.step || 1 ).val( 'input_value' in data ? data.input_value : 1 ).trigger( 'change' );
					} );
				} );
				</script>
			<?php
			}
		}

		/**
		 * Allow decimal quantity and add quantity suffix to the add to cart message
		 * @param mixed $message
		 * @param mixed $products
		 * @return mixed
		 */
		public function wc_adjust_add_to_cart_message_qty( $message, $products ) {
			if ( 1 === count( $products ) ) {
				$qty = reset( $products );
				return str_replace( absint( $qty ) . ' &times; ', $qty . get_post_meta( key( $products ), '_upw_quantity_suffix', true ) .  ' &times; ', $message );
			}
			return $message;
		}


		/**
		 * Count decimal quantity products as 1 item for the cart content counts
		 * @param mixed $count
		 * @return mixed
		 */
		public function wc_adjust_cart_contents_count( $count ) {
			if ( apply_filters( 'upw_adjust_cart_items_count_enabled', true ) ) {
				$increase = $decrease = 0;
				foreach ( WC()->cart->get_cart() as $item ) {
					if ( $item['data']->get_meta( '_upw_step' ) ) {
						$increase++;
						$decrease += $item['quantity'];
					}
				}
			}
			return $count + $increase - $decrease;
		}

		/**
		 * Modify the item quantity by the selected product Quantity Units field
		 * @param mixed $item
		 * @param mixed $cart_item_key
		 * @param mixed $values
		 * @param mixed $order
		 */
		public function wc_add_custom_data_to_order( $item, $cart_item_key, $values, $order ) {
			$product = $values['data'];
			$unit_size = $unit_symbol = '';
			$unit_price = $order->get_item_subtotal( $item, false, false );
			$measurement = $product->get_meta( '_upw_measurement' );
			switch ( $measurement ) {
				case 'weight':
					$unit_size = $product->get_weight();
					$unit_symbol = get_option( 'woocommerce_weight_unit' );
					switch ( $unit_symbol ) {
						case 'kg':	$unit_symbol = __( 'kg', 'woocommerce' ); break;
						case 'g':	$unit_symbol = __( 'g', 'woocommerce' ); break;
						case 'lbs':	$unit_symbol = __( 'lbs', 'woocommerce' ); break;
						case 'oz':	$unit_symbol = __( 'oz', 'woocommerce' ); break;
					}
					break;
				case 'mpc':
					$unit_price = wc_get_price_excluding_tax( $product );
					$unit_size = 1;
					$unit_symbol = function_exists( 'is_plugin_active' ) && is_plugin_active( 'woocommerce-measurement-price-calculator/woocommerce-measurement-price-calculator.php' ) ? $item->get_meta( '_measurement_data' )['_measurement_needed'] . $item->get_meta( '_measurement_data' )['_measurement_needed_unit'] : '';
					break;
			}
			if ( $unit_size && $unit_size > 0 ) {
				$item->add_meta_data( '_upw_measurement', $measurement, true );
				$item->add_meta_data( '_unit_size', $unit_size, true );
				$item->add_meta_data( '_unit_symbol', $unit_symbol, true );
				$item->add_meta_data( 'unit_qty', $item->get_quantity(), true );
				$item->add_meta_data( '_unit_total', $item->get_total(), true );
				$item->set_quantity( round( $item->get_subtotal() / $unit_price * $unit_size, 2 ) );
				$order->update_meta_data( '_has_unit_items', true );
			}
		}

		/**
		 * Hide the products meta fields in the backend
		 * @param mixed $hidden_meta
		 * @return mixed
		 */
		public function wc_hide_item_meta( $hidden_meta ) {
			array_push( $hidden_meta, '_upw_measurement', '_unit_symbol', '_unit_size', 'unit_qty', '_unit_total', '_product_price' );
			return $hidden_meta;
		}

		/**
		 * Add table columns for item meta info
		 * @param mixed $order
		 */
		public function wc_add_header_on_order_item_view( $order ) {
			if ( apply_filters( 'upw_admin_order_unit_headers_enbaled', true ) && ! wp_doing_ajax() && $order->get_meta( '_has_unit_items' ) ) {
			?>
				<th class="unit_size sortable" data-sort="float" style="color:purple"><?php echo __( 'Unit', 'unit-price-for-woocommerce' ) . ' (' . __( 'expected', 'unit-price-for-woocommerce' ) . ')'; ?></th>
				<th class="unit_item_cost sortable" data-sort="float" style="color:purple"><?php echo __( 'Unit Cost', 'unit-price-for-woocommerce' ) . ' (' . __( 'expected', 'unit-price-for-woocommerce' ) . ')'; ?></th>
				<th class="unit_quantity sortable" data-sort="float" style="color:purple"><?php echo __( 'Amount of units', 'unit-price-for-woocommerce' ); ?></th>
				<th class="unit_line_cost sortable" data-sort="float" style="color:purple"><?php echo __( 'Total', 'woocommerce' ). ' (' . __( 'expected', 'unit-price-for-woocommerce' ) . ')'; ?></th>
			<?php
			}
		}

		/**
		 * Fill the meta info of the items
		 * @param mixed $product
		 * @param mixed $item
		 */
		public function wc_add_value_on_order_item_view( $product, $item ) {
			global $post;
			$order = wc_get_order( $post );
			if ( apply_filters( 'upw_admin_order_unit_headers_enbaled', true ) && ! wp_doing_ajax() && $order && $order->get_meta( '_has_unit_items' ) ) {
				$unit_size = 'mpc' === $item->get_meta( '_upw_measurement' ) ? '' : $item->get_meta( '_unit_size' );
				?>
				<td class="unit_size" width="1%">
					<div class="view"><?php echo $item->get_meta( '_upw_measurement' ) ? $unit_size . $item->get_meta( '_unit_symbol' ) : ''; ?></div>
				</td>
				<td class="unit_item_cost" width="1%">
					<div class="view"><?php	echo $item->get_meta( '_upw_measurement' ) ? wc_price( $item->get_meta( '_unit_total' ) / $item->get_meta( 'unit_qty' ) ) : ''; ?></div>
				</td>
				<td class="unit_quantity" width="1%">
					<div class="view"><?php echo $item->get_meta( '_upw_measurement' ) ? '<small class="times">&times;</small> ' . $item->get_meta( 'unit_qty' ) : ''; ?></div>
				</td>
				<td class="unit_line_cost" width="1%">
					<div class="view"><?php echo $item->get_meta( '_upw_measurement' ) ? wc_price( $item->get_meta( '_unit_total' ) ) : ''; ?></div>
				</td>
				<?php
			}
		}

		/**
		 * Add the unit label to the product quantity
		 * @param mixed $quantity
		 * @param mixed $item
		 * @return mixed
		 */
		public function wc_add_quantity_symbol( $quantity, $item ) {
			return $item->get_meta( '_upw_measurement' ) && 'mpc' !== $item->get_meta( '_upw_measurement' ) ? $quantity . $item->get_meta( '_unit_symbol' ) : $quantity;
		}

		/**
		 * Retrieve the real previous ordered quantity of products which are sold by units but priced by weight
		 * @param mixed $cart_item_data
		 * @param mixed $item
		 * @param mixed $order
		 * @return mixed
		 */
		public function wc_retrieve_product_org_qty( $cart_item_data, $item ) {
			$unit_qty = $item->get_meta( 'unit_qty' );
			if ( $unit_qty ) {
				$cart_item_data['unit_qty'] = $unit_qty;
			}
			return $cart_item_data;
		}

		/**
		 * Set the real previous ordered quantity of products which are sold by units but priced by weight
		 * @param mixed $cart_item_data
		 * @return mixed
		 */
		public function wc_fix_order_again_product_qty( $cart_item_data ) {
			if ( isset( $cart_item_data['unit_qty'] ) ) {
				$cart_item_data['quantity'] = $cart_item_data['unit_qty'];
				unset( $cart_item_data['unit_qty'] );
			}
			return $cart_item_data;
		}

		/**
		 * Add the unit label to the product price for products which are sold by units but priced by weight
		 * @param mixed $price_html
		 * @param mixed $product
		 * @return mixed
		 */
		public function wc_change_product_html( $html, $product ) {
			$measurement = $product->get_meta( '_upw_measurement' );
			$product_weight = $product->get_weight();
			if ( $product_weight && $product_weight > 0 ) {
				switch ( $measurement ) {
					case 'weight':
						$unit_symbol = get_option( 'woocommerce_weight_unit' );
						switch ( $unit_symbol ) {
							case 'kg': $unit_symbol = __( 'kg', 'woocommerce' ); break;
							case 'g': $unit_symbol = __( 'g', 'woocommerce' ); break;
							case 'lbs': $unit_symbol = __( 'lbs', 'woocommerce' ); break;
							case 'oz': $unit_symbol = __( 'oz', 'woocommerce' ); break;
						}
						$suffix = '<br><small>' . wc_price( wc_get_price_to_display( $product, [ 'qty' => 1 / $product_weight ] ), apply_filters( 'upw_weight_unit_wc_price_args', [] ) ) . '/' . $unit_symbol . '</small>';
						break;
				}
			}
			return $html . apply_filters( 'upw_price_suffix', $suffix ?? '', $measurement, $product );
		}

		/**
		 * Add hidden class to add to cart button
		 * @param mixed $args
		 * @param mixed $product
		 * @return mixed
		 */
		public function wc_add_class( $args, $product ) {
			if ( $product->is_type( 'simple' ) && apply_filters( 'wc_enable_shop_quantity', true, $product ) ) {
				$args['class'] .= ' hidden-button';
			}
			return $args;
		}

		/**
		 * Modify the minimum and default quantity values
		 * @param mixed $args
		 * @param mixed $product
		 * @return mixed
		 */
		public function wc_modify_quantity_args( $args, $product ) {
			if ( ( is_shop() || is_product_category() ) && 'quantity' === $args['input_name'] && $product->is_type( 'simple' ) && apply_filters( 'wc_enable_shop_quantity', true, $product ) ) {
				$args['min_value'] = 0;
				$cart_item_key = WC()->cart->find_product_in_cart( WC()->cart->generate_cart_id( $product->get_id() ) );
				$args['input_value'] = ! empty( $cart_item_key ) ? WC()->cart->get_cart()[ $cart_item_key ]['quantity'] : 0;
			}
			return $args;
		}

		/**
		 * Set the quantity of cart item based on selected value, woocommerce_add_to_cart_validation and 'sold individualy' option not applied
		 * @return mixed
		 */
		public function wc_set_item_quantity() {
			if ( isset( $_POST['product_id'] ) ) {
				$product = wc_get_product( $_POST['product_id'] );
				if ( $product->is_type( 'simple' ) && apply_filters( 'wc_enable_shop_quantity', true, $product ) ) {
					$cart_item_key = WC()->cart->find_product_in_cart( WC()->cart->generate_cart_id( $_POST['product_id'] ) );
					if ( $cart_item_key ) {
						WC()->cart->set_quantity( $cart_item_key, $_POST['quantity'], true );
					} else {
						WC()->cart->add_to_cart( $_POST['product_id'], $_POST['quantity'] );
					}
					WC_AJAX::get_refreshed_fragments();
				}
			}
			wp_send_json( '' );
		}

		/**
		 * Activate the ajax action that updates cart item quantity for simple products
		 */
		public function wc_set_item_quantity_js() {
			?>
			<style>
			.hidden-button {
				display: none !important;
			}
			</style>
			<script type="text/javascript">
			jQuery( function( $ ) {
				var timeout;
				$( document.body ).on( 'change', 'input.qty', function() {
					if ( $( this ).attr( 'name' ) !== '<?php echo apply_filters( 'upw_auto_update_excluded_quantity_input_name', '' ); ?>' ) {
						if ( timeout !== undefined ) {
							clearTimeout( timeout );
						}
						if ( <?php echo wp_json_encode( apply_filters( 'upw_skip_quantity_validation', false ) ); ?> || this.reportValidity() ) {
							var $thisbutton = $( this ), base_selector = <?php echo apply_filters( 'upw_quantity_base_selector', function_exists( 'is_plugin_active' ) && ( is_plugin_active( 'qty-increment-buttons-for-woocommerce/qty-increment-buttons-for-woocommerce.php' ) || is_plugin_active( 'wc-quantity-plus-minus-button/init.php' ) ) ? '$thisbutton.parent().parent().parent()' : '$thisbutton.parent().parent()' ); ?>,
								data = {
									action:		'wc_set_item_quantity',
									product_id:	base_selector.find( '.add_to_cart_button' ).data( 'product_id' ),
									quantity:	parseFloat( $thisbutton.val() ),
								};
							timeout = setTimeout( function() {
								$.post( wc_add_to_cart_params.ajax_url, data, function( response ) {
									$( document.body ).trigger( 'added_to_cart', [ response.fragments, response.cart_hash, $thisbutton ] );
								} );
							}, <?php echo apply_filters( 'upw_auto_update_time', 500 ); ?> );
						}
					}
				} );
			} );
			</script>
			<?php if ( ! wp_is_mobile() && apply_filters( 'upw_auto_update_hide_field', false ) ) { ?>
				<script type="text/javascript">
				jQuery( function( $ ) {
					$( 'li.product-type-simple' ).find( 'input.qty' ).each( function() {
						if ( $( this ).val() == 0 ) {
							$( this ).css( 'visibility', 'hidden' );
							$( this ).parent().find( '.quantity-suffix' ).css( 'visibility', 'hidden' );
							$( this ).parent().parent().parent().find( '.qib-container, .qib-button' ).css( 'visibility', 'hidden' );
							$( document ).triggerHandler( 'upw_quantity_hide' );
						}
					} );
					$( 'li.product-type-simple' ).hover(
						function() {
							$( this ).find( 'input.qty, .quantity-suffix, .qib-container, .qib-button' ).css( 'visibility', 'visible' );
							$( document ).triggerHandler( 'upw_quantity_show' );
						}, function() {
							if ( $( this ).find( 'input.qty' ).val() == 0 ) {
								$( this ).find( 'input.qty, .quantity-suffix, .qib-container, .qib-button' ).css( 'visibility', 'hidden' );
								$( document ).triggerHandler( 'upw_quantity_hide' );
							}
						}
					);
				} );
				</script>
			<?php
			}
		}

		/**
		 * Set the default attributes of variable products on archive pages if the product is in cart
		 * @param mixed $default_attributes
		 * @param mixed $product
		 * @return mixed
		 */
		public function wc_set_default_attributes( $default_attributes, $product ) {
			if ( is_shop() || is_product_category() || apply_filters( 'upw_variation_custom_archive', false ) ) {
				foreach ( WC()->cart->get_cart() as $cart_item ) {
					if ( $product->get_id() === $cart_item['product_id'] ) {
						foreach ( wc_get_product( $cart_item['variation_id'] )->get_variation_attributes() as $key => $value ) {
							$default_attributes[ str_replace( 'attribute_', '', $key ) ] = $value;
						}
						return $default_attributes;
					}
				}
			}
			return $default_attributes;
		}

		/**
		 * Set the default quantity value of variable products on archive pages
		 * @param mixed $args
		 * @param mixed $product
		 * @param mixed $variation
		 * @return mixed
		 */
		public function wc_variation_quantity( $args, $product, $variation ) {
			if ( is_shop() || is_product_category() || apply_filters( 'upw_variation_custom_archive', false ) ) {
				$args['min_qty'] = 0;
				foreach ( WC()->cart->get_cart() as $cart_item ) {
					if ( $variation->get_id() === $cart_item['variation_id'] ) {
						$args['input_value'] = $cart_item['quantity'];
						return $args;
					}
				}
				$args['input_value'] = 0;
			}
			return $args;
		}

		/**
		 * Set the quantity of cart item based on selected value for variable products
		 */
		public function wc_variable_set_item_quantity() {
			if ( ! empty( $_POST['variation_id'] ) ) {
				$found_item = false;
				$item_removed = false;
				foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
					if ( $cart_item['variation_id'] == $_POST['variation_id'] ) {
						WC()->cart->set_quantity( $cart_item_key, $_POST['quantity'], true );
						$found_item = true;
					} elseif ( $cart_item['product_id'] == $_POST['product_id'] && apply_filters( 'upw_remove_other_variations', true ) ) {
						WC()->cart->remove_cart_item( $cart_item_key );
						$item_removed = true;
					}
				}
				if ( ! $found_item && '0' != $_POST['quantity'] ) {
					$this->wc_ajax_add_to_cart();
				} elseif ( $found_item || $item_removed ) {
					WC_AJAX::get_refreshed_fragments();
				}
			}
			wp_send_json( '' );
		}

		/**
		 * Activate the ajax action that updates cart item quantity for variable products
		 */
		public function wc_variable_set_item_quantity_js() {
			?>
			<style>
			.woocommerce-variation-add-to-cart {
				display: none !important;
			}
			</style>
			<script type="text/javascript">
			jQuery( function( $ ) {
				var timeout;
				$( document.body ).on( 'change', 'input.qty', function() {
					if ( $( this ).attr( 'name' ) !== '<?php echo apply_filters( 'upw_auto_update_excluded_quantity_input_name', '' ); ?>' ) {
						if ( timeout !== undefined ) {
							clearTimeout( timeout );
						}
						if ( <?php echo wp_json_encode( apply_filters( 'upw_skip_quantity_validation', false ) ); ?> || this.reportValidity() ) {
							var $thisbutton = $( this ), form = $thisbutton.closest( 'form.cart' ),
								data = {
									action:		'wc_variable_set_item_quantity',
									product_id:	form.find( 'input[name=product_id]' ).val() || $thisbutton.val(),
									variation_id:	form.find( 'input[name=variation_id]' ).val() || 0,
									quantity:		parseFloat( $thisbutton.val() ),
								};
							timeout = setTimeout( function() {
								$.post( wc_add_to_cart_params.ajax_url, data, function( response ) {
									$( document.body ).trigger( 'added_to_cart', [ response.fragments, response.cart_hash, $thisbutton ] );
								} );
							}, <?php echo apply_filters( 'upw_variation_auto_update_time', 500 ); ?> );
						}
					}
				} );
			} );
			</script>
		<?php
		}

		/**
		 * Add quantity input field to variable product on archive page
		 * @param mixed $html
		 * @param mixed $product
		 * @return mixed
		 */
		public function wc_quantity_input( $html, $product ) {
			return ( is_shop() || is_product_category() ) && $product && $product->is_type( 'variable' ) && $product->is_purchasable() && $product->is_in_stock() && ! $product->is_sold_individually() ? woocommerce_quantity_input( 'yes' === get_option( 'wc_upw_variable_quantity_auto_update' ) ? [ 'max_value' => $product->get_max_purchase_quantity(), 'input_value' => '0' ] : [ 'min_value' => $product->get_min_purchase_quantity(), 'max_value' => $product->get_max_purchase_quantity() ] ) . $html : $html;
		}

		/**
		 * Update the add to cart button quantity for variable products on archive pages
		 */
		public function wc_archive_quantity_field() {
			if ( function_exists( 'is_plugin_active' ) && ( is_plugin_active( 'qty-increment-buttons-for-woocommerce/qty-increment-buttons-for-woocommerce.php' ) || is_plugin_active( 'wc-quantity-plus-minus-button/init.php' ) ) ) {
				$base_selector = '$( this ).parent().parent().parent()';
				$base_trigger = $on_trigger = 'change';
			} else {
				$theme = wp_get_theme();
				$has_qty_buttons = ! empty( array_intersect( [ 'OceanWP', 'Flatsome' ], [ $theme->name, $theme->parent_theme ] ) );
				$base_selector = apply_filters( 'upw_quantity_base_selector', '$( this ).parent().parent()' );
				$base_trigger = apply_filters( 'upw_quantity_base_trigger', $has_qty_buttons ? 'change' : 'click input' );
				$on_trigger = apply_filters( 'upw_quantity_on_trigger', $has_qty_buttons ? 'change' : 'click' );
			}
			?>
			<script type="text/javascript">
			jQuery( function( $ ) {
				$( document.body ).on( '<?php echo $base_trigger; ?>', 'input.qty', function() {
					if ( <?php echo wp_json_encode( apply_filters( 'upw_skip_quantity_validation', false ) ); ?> || this.reportValidity() ) {
						<?php echo $base_selector; ?>.find( '.add_to_cart_button' ).data( 'quantity', $( this ).val() );
					}
				} );
				$( '.variations_form' ).on( 'show_variation', function( event, data ) {
					$( this ).parent().find( 'input.qty' ).attr( { min:data.min_qty, step:data.step } ).val( data.input_value ).trigger( '<?php echo $on_trigger; ?>' );
				} );
			} );
			</script>
		<?php
		}

		/**
		 * Remove default archive product variation template
		 */
		public function wc_change_loop_add_to_cart() {
			remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart' );
			add_action( 'woocommerce_after_shop_loop_item', [ $this, 'wc_template_loop_add_to_cart' ] );
		}

		/**
		 * Display the variations on archive pages
		 */
		public function wc_template_loop_add_to_cart() {
			global $product;
			if ( $product->is_type( 'variable' ) && apply_filters( 'upw_archive_variations', true, $product ) ) {
				remove_action( 'woocommerce_single_variation', 'woocommerce_single_variation_add_to_cart_button', 20 );
				add_action( 'woocommerce_single_variation', [ $this, 'wc_loop_variation_add_to_cart_button' ] );
				woocommerce_template_single_add_to_cart();
			} else {
				woocommerce_template_loop_add_to_cart();
			}
		}

		/**
		 * Add variation add to cart button on archive pages
		 */
		public function wc_loop_variation_add_to_cart_button() {
			global $product;
			woocommerce_quantity_input( 'yes' === get_option( 'wc_upw_variable_quantity_auto_update' ) ? [ 'max_value' => $product->get_max_purchase_quantity(), 'input_value' => '0' ] : [ 'min_value' => $product->get_min_purchase_quantity(), 'max_value' => $product->get_max_purchase_quantity() ] );
			?>
				<div class="woocommerce-variation-add-to-cart variations_button">
					<button type="submit" class="single_add_to_cart_button button"><?php echo esc_html( $product->single_add_to_cart_text() ); ?></button>
					<input type="hidden" name="add-to-cart" value="<?php echo absint( $product->get_id() ); ?>" />
					<input type="hidden" name="product_id" value="<?php echo absint( $product->get_id() ); ?>" />
					<input type="hidden" name="variation_id" class="variation_id" value="0" />
				</div>
			<?php
		}

		/**
		 * Add Ajax variation add to cart, the selected attributes terms are not added to the cart item meta so can't handle variations which have 'Any terms' attribute
		 */
		public function wc_ajax_add_to_cart() {
			$product_id = apply_filters( 'woocommerce_add_to_cart_product_id', absint( $_POST['product_id'] ) );
			$quantity = empty( $_POST['quantity'] ) ? 1 : wc_stock_amount( $_POST['quantity'] );
			$variation_id = absint( $_POST['variation_id'] );
			$passed_validation = apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $quantity );
			if ( $variation_id && $passed_validation && 'publish' === get_post_status( $product_id ) && WC()->cart->add_to_cart( $product_id, $quantity, $variation_id ) ) {
				do_action( 'woocommerce_ajax_added_to_cart', $product_id );
				if ( 'yes' === get_option( 'woocommerce_cart_redirect_after_add' ) ) {
					wc_add_to_cart_message( [ $product_id => $quantity ], true );
				}
				WC_AJAX::get_refreshed_fragments();
			} else {
				$data = [
					'error' => true,
					'product_url' => apply_filters( 'woocommerce_cart_redirect_after_error', get_permalink( $product_id ), $product_id )
				];
				wp_send_json( $data );
			}
			wp_send_json( '' );
		}

		/**
		 * Trigger ajax variation add to cart
		 */
		public function wc_ajax_variation() {
			?>
			<style>
			.archive .variations_form td {
				background: none;
			}
			</style>
			<script type="text/javascript">
			jQuery( function( $ ) {
				$( document ).on( 'click', '.single_add_to_cart_button', function( e ) {
					e.preventDefault();
					var $thisbutton = $( this ), form = $thisbutton.closest( 'form.cart' ), qty = form.find( 'input[name=quantity]' );
					if ( <?php echo wp_json_encode( apply_filters( 'upw_skip_quantity_validation', false ) ); ?> || qty[0].reportValidity() ) {
						var data = {
							action:		'wc_ajax_add_to_cart',
							product_id:	form.find( 'input[name=product_id]' ).val() || $thisbutton.val(),
							quantity:	qty.val() || 1,
							variation_id:	form.find( 'input[name=variation_id]' ).val() || 0,
						};
						$( document.body ).trigger( 'adding_to_cart', [ $thisbutton, data ] );
						$.post( {
							url: wc_add_to_cart_params.ajax_url,
							data: data,
							beforeSend: function( response ) {
								$thisbutton.removeClass( 'added' ).addClass( 'loading' );
							},
							complete: function( response ) {
								$thisbutton.addClass( 'added' ).removeClass( 'loading' );
							},
							success: function( response ) {
								if ( response.error && response.product_url ) {
									window.location = response.product_url;
									return;
								} else {
									$( document.body ).trigger( 'added_to_cart', [ response.fragments, response.cart_hash, $thisbutton ] );
								}
							},
						} );
						return false;
					}
				} );
			} );
			</script>
		<?php
		}

		/**
		 * Add quantity input field to simple product on archive page
		 * @param mixed $html
		 * @param mixed $product
		 * @return mixed
		 */
		public function wc_quantity_inputs_for_loop_ajax_add_to_cart( $html, $product ) {
			if ( $product && $product->is_type( 'simple' ) && $product->is_purchasable() && $product->is_in_stock() && ! $product->is_sold_individually() && apply_filters( 'upw_quantity_field_simple_enabled', true ) ) {
				$class = implode( ' ', array_filter( [
					'button',
					'product_type_' . $product->get_type(),
					$product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
					$product->supports( 'ajax_add_to_cart' ) ? 'ajax_add_to_cart' : '',
					'yes' === get_option( 'wc_upw_quantity_auto_update' ) && apply_filters( 'wc_enable_shop_quantity', true, $product ) ? 'hidden-button' : '',
				] ) );
				$html = sprintf( '%s<a rel="nofollow" href="%s" data-quantity="%s" data-product_id="%s" data-product_sku="%s" class="%s">%s</a>',
					woocommerce_quantity_input( 'yes' === get_option( 'wc_upw_quantity_auto_update' ) ? [ 'max_value' => $product->get_max_purchase_quantity(), 'input_value' => '0' ] : [ 'min_value' => $product->get_min_purchase_quantity(), 'max_value' => $product->get_max_purchase_quantity() ], $product, false ),
					esc_url( $product->add_to_cart_url() ),
					esc_attr( $quantity ?? 1 ),
					esc_attr( $product->get_id() ),
					esc_attr( $product->get_sku() ),
					esc_attr( $class ?? 'button' ),
					esc_html( $product->add_to_cart_text() )
				);
			}
			return $html;
		}

		/**
		 * Update the add to cart button quantity for simple products on archive pages
		 */
		public function wc_archives_quantity_fields_script() {
			if ( function_exists( 'is_plugin_active' ) && ( is_plugin_active( 'qty-increment-buttons-for-woocommerce/qty-increment-buttons-for-woocommerce.php' ) || is_plugin_active( 'wc-quantity-plus-minus-button/init.php' ) ) ) {
				$base_selector = '$( this ).parent().parent().parent()';
				$base_trigger = $on_trigger = 'change';
			} else {
				$theme = wp_get_theme();
				$has_qty_buttons = ! empty( array_intersect( [ 'OceanWP', 'Flatsome' ], [ $theme->name, $theme->parent_theme ] ) );
				$base_selector = apply_filters( 'upw_quantity_base_selector', '$( this ).parent().parent()' );
				$base_trigger = apply_filters( 'upw_quantity_base_trigger', $has_qty_buttons ? 'change' : 'click input' );
				$on_trigger = apply_filters( 'upw_quantity_on_trigger', $has_qty_buttons ? 'change' : 'click' );
			}
			?>
			<style>
			a.added_to_cart.wc-forward {
				display: none;
			}
			</style>
			<script type='text/javascript'>
				jQuery( function( $ ) {
					$( document ).ready( function() {
						$( 'li.product' ).find( 'input.qty' ).each( function() {
							<?php echo $base_selector; ?>.find( 'a.ajax_add_to_cart' ).attr( 'data-quantity', $( this ).val() );
						} );
					} );
					$( document.body ).on( '<?php echo $base_trigger; ?>', 'input.qty', function() {
						if ( <?php echo wp_json_encode( apply_filters( 'upw_skip_quantity_validation', false ) ); ?> || this.reportValidity() ) {
							<?php echo $base_selector; ?>.find( 'a.ajax_add_to_cart' ).attr( 'data-quantity', $( this ).val() );
						}
					} );
					$( document.body ).on( 'should_send_ajax_request.adding_to_cart', function( e, $button ) {
						qty = $button.parent().find( 'input.qty' );
						if ( qty.length && true != <?php echo wp_json_encode( apply_filters( 'upw_skip_quantity_validation', false ) ); ?> && ! qty[0].reportValidity() ) {
							return false;
						}
					} );
				} );
			</script>
			<?php
		}

		/**
		 * Add quantity suffix for variable and simple products
		 */
		public function wc_quantity_input_suffix() {
			global $product;
			if ( is_a( $product, 'WC_Product' ) ) {
				echo '<span class="quantity-suffix">' . $product->get_meta( '_upw_quantity_suffix' ) . '</span>';
			}
		}

		/**
		 * Add quantity suffix for variable products
		 * @param mixed $args
		 * @param mixed $product
		 * @param mixed $variation
		 * @return mixed
		 */
		public function wc_variation_quantity_suffix( $args, $product, $variation ) {
			$suffix = $variation->get_meta( '_upw_quantity_suffix' );
			if ( $suffix ) {
				$args['suffix'] = $suffix;
			}
			return $args;
		}

		/**
		 * Update the quantity suffix for variable products
		 */
		public function wc_change_variation_quantity_suffix() {
			if ( 2 > did_action( 'woocommerce_after_variations_form' ) ) {
				?>
				<script type="text/javascript">
				jQuery( function( $ ) {
					$( '.variations_form' ).on( 'show_variation', function( event, data ) {
						$( this ).parent().find( '.quantity-suffix' ).html( data.suffix || '' )
					} );
				} );
				</script>
				<?php
			}
		}

		/**
		 * Add quantity suffix in stock notices
		 * @param mixed $stock_quantity
		 * @param mixed $product
		 * @return mixed
		 */
		public function upw_stock_quantity_suffix( $stock_quantity, $product ) {
			return $stock_quantity . $product->get_meta( '_upw_quantity_suffix' );
		}

		/**
		 * Add quantity suffix in mini cart
		 * @param mixed $output
		 * @param mixed $cart_item
		 * @param mixed $cart_item_key
		 * @return mixed
		 */
		public function wc_mini_cart_quantity( $output, $cart_item, $cart_item_key ) {
			return str_replace( '&times;', $cart_item['data']->get_meta( '_upw_quantity_suffix' ) . ' &times;', $output );
		}

		/**
		 * Add quantity suffix on cart page
		 * @param mixed $output
		 * @param mixed $cart_item_key
		 * @param mixed $cart_item
		 * @return mixed
		 */
		public function wc_quantity_suffix( $output, $cart_item_key, $cart_item ) {
			return $output . $cart_item['data']->get_meta( '_upw_quantity_suffix' );
		}

		/**
		 * Add quantity suffix on checkout
		 * @param mixed $output
		 * @param mixed $cart_item
		 * @param mixed $cart_item_key
		 * @return mixed
		 */
		public function wc_checkout_quantity_suffix( $output, $cart_item, $cart_item_key ) {
			return ' <strong class="product-quantity">' . sprintf( '&times; %s', $cart_item['quantity'] ) . $cart_item['data']->get_meta( '_upw_quantity_suffix' ) . '</strong>';
		}

		/**
		 * Add quantity suffix meta to order items
		 * @param mixed $item
		 * @param mixed $cart_item_key
		 * @param mixed $values
		 * @param mixed $order
		 */
		public function wc_add_suffix_to_order_item( $item, $cart_item_key, $values, $order ) {
			$quantity_suffix = $values['data']->get_meta( '_upw_quantity_suffix' );
			if ( $quantity_suffix ) {
				$item->add_meta_data( '_quantity_suffix', $quantity_suffix, true );
			}
		}

		/**
		 * Add quantity suffix on order details in frontend and emails
		 * @param mixed $quantity
		 * @param mixed $item
		 * @return mixed
		 */
		public function wc_add_quantity_suffix( $quantity, $item ) {
			return $quantity . $item->get_meta( '_quantity_suffix' );
		}

		/**
		 * Add price suffix and modify price display
		 * @param mixed $price_html
		 * @param mixed $product
		 * @return mixed
		 */
		public function wc_adjust_price_display( $price_html, $product ) {
			$price_quantity = $product->get_meta( '_upw_price_quantity' );
			if ( $price_quantity ) {
				if ( '' === $product->get_price() ) {
					return $price_html;
				} elseif ( $product->is_on_sale() ) {
					$price_html = wc_format_sale_price( wc_get_price_to_display( $product, array( 'price' => $product->get_regular_price(), 'qty' => $price_quantity ) ), wc_get_price_to_display( $product, [ 'qty' => $price_quantity ] ) ) . $product->get_price_suffix();
				} else {
					$price_html = wc_price( wc_get_price_to_display( $product, [ 'qty' => $price_quantity ] ) ) . $product->get_price_suffix();
				}
			}
			return $price_html . $product->get_meta( '_upw_price_suffix' );
		}

		/**
		 * Add price suffix and modify price display on cart
		 * @param mixed $price_html
		 * @param mixed $cart_item
		 * @return mixed
		 */
		public function wc_adjust_cart_price_display( $price_html, $cart_item ) {
			$price_quantity = $cart_item['data']->get_meta( '_upw_price_quantity' );
			if ( $price_quantity ) {
				$price_html = wc_price( wc_get_price_to_display( $cart_item['data'], [ 'qty' => $price_quantity ] ) );
			}
			return $price_html . $cart_item['data']->get_meta( '_upw_price_suffix' );
		}

		/**
		 * Display the price by quantity for simple products on single pages, doesn't take into account the 'Thousand separator'
		 */
		public function wc_product_price_quantity() {
			global $product;
			if ( $product->is_type( 'simple' ) && apply_filters( 'upw_display_product_subtotal', true, $product ) ) {
				$base_selector = apply_filters( 'upw_single_price_quantity_base_selector', function_exists( 'is_plugin_active' ) && ( is_plugin_active( 'qty-increment-buttons-for-woocommerce/qty-increment-buttons-for-woocommerce.php' ) || is_plugin_active( 'wc-quantity-plus-minus-button/init.php' ) ) ? '$( this ).parent().parent().parent()' : '$( this ).parent().parent()' );
				$price_prefix = $price_suffix = '';
				$pos = get_option( 'woocommerce_currency_pos' );
				if ( 'left' === $pos ) {
					$price_prefix = get_woocommerce_currency_symbol();
				} elseif ( 'right' === $pos ) {
					$price_suffix = get_woocommerce_currency_symbol();
				} elseif ( 'left_space' === $pos && ! is_rtl() || 'right_space' === $pos && is_rtl() ) {
					$price_prefix = get_woocommerce_currency_symbol() . ' ' ;
				} else {
					$price_suffix = ' ' . get_woocommerce_currency_symbol();
				}
				$decimal_seperator = wc_get_price_decimal_separator();
				echo '<div id="subtotal" style="display: inline-block;">' . apply_filters( 'upw_product_subtotal_label', __( 'Total:', 'woocommerce' ), $product ) . ' <span></span></div>';
				?>
				<script type="text/javascript">
				jQuery( function( $ ) {
					$( document ).ready( function() {
						$( 'input[name=quantity]' ).each( function() {
							<?php echo $base_selector; ?>.find( '#subtotal > span' ).html( '<?php echo $price_prefix; ?>' + ( '<?php echo wc_get_price_to_display( $product ); ?>' * $( this ).val() ).toFixed( <?php echo wc_get_price_decimals(); ?> ).replace( '.', '<?php echo $decimal_seperator; ?>' ) + '<?php echo $price_suffix; ?>' );
						} );
					} );
					$( 'input[name=quantity]' ).on( 'change', function() {
						<?php echo $base_selector; ?>.find( '#subtotal > span' ).html( '<?php echo $price_prefix; ?>' + ( '<?php echo wc_get_price_to_display( $product ); ?>' * $( this ).val() ).toFixed( <?php echo wc_get_price_decimals(); ?> ).replace( '.', '<?php echo $decimal_seperator; ?>' ) + '<?php echo $price_suffix; ?>' );
					} );
				} );
				</script>
				<?php
			}
		}

		/**
		 * Display the price by quantity for simple products on archive pages
		 * @param mixed $price_html
		 * @param mixed $product
		 * @return mixed
		 */
		public function wc_archive_product_price_quantity() {
			global $product;
			if ( is_a( $product, 'WC_Product' ) && ( is_shop() || is_product_category() ) && $product->is_type( 'simple' ) && $product->is_purchasable() && apply_filters( 'upw_display_product_subtotal', true, $product ) && apply_filters( 'upw_simple_subtotal_enabled', 'yes' === get_option( 'wc_upw_quantity_simple' ) || function_exists( 'is_plugin_active' ) && is_plugin_active( 'qty-increment-buttons-for-woocommerce/qty-increment-buttons-for-woocommerce.php' ) ) ) {
				echo '<div id="subtotal" style="display: inline-block;">' . apply_filters( 'upw_product_subtotal_label', __( 'Total:', 'woocommerce' ), $product ) . '<span></span></div><div id=product_price style="display: none;"><span>' . wc_get_price_to_display( $product ) . '</span></div>';
			}
		}

		/**
		 * Display the price by quantity for variable products
		 * @param mixed $data
		 * @param mixed $product
		 * @param mixed $variation
		 * @return mixed
		 */
		public function wc_variable_price_quantity( $data, $product, $variation ) {
			if ( apply_filters( 'upw_display_product_subtotal', true, $product ) ) {
				$data['price_html'] = apply_filters( 'upw_product_origin_price', $data['price_html'], $product ) . '<div id="product_total_price" class="total-price-qty variable_products">' . apply_filters( 'upw_product_subtotal_label', __( 'Total:', 'woocommerce' ), $product ) . '<span class="price">' . $data['display_price'] . '</span></div>';
			}
			return $data;
		}

		/**
		 * Update the displayed price by the selected quantity for simple products on archive pages and variable products
		 */
		public function wc_product_price_quantity_js() {
			$qty_plugin_enabled = function_exists( 'is_plugin_active' ) && ( is_plugin_active( 'qty-increment-buttons-for-woocommerce/qty-increment-buttons-for-woocommerce.php' ) || is_plugin_active( 'wc-quantity-plus-minus-button/init.php' ) );
			$base_selector = apply_filters( 'upw_variation_price_quantity_base_selector', is_product() ? '$( this ).parent().parent().parent()' : '$( this ).parent().parent()' );
			$base_selector = $qty_plugin_enabled ? $base_selector . '.parent()' : $base_selector;
			$base_selector_simple = apply_filters( 'upw_simple_price_quantity_base_selector', $qty_plugin_enabled ? '$( this ).parent().parent().parent()' : '$( this ).parent().parent()' );
			$price_prefix = $price_suffix = '';
			$pos = get_option( 'woocommerce_currency_pos' );
			if ( 'left' === $pos ) {
				$price_prefix = get_woocommerce_currency_symbol();
			} elseif ( 'right' === $pos ) {
				$price_suffix = get_woocommerce_currency_symbol();
			} elseif ( 'left_space' === $pos && ! is_rtl() || 'right_space' === $pos && is_rtl() ) {
				$price_prefix = get_woocommerce_currency_symbol() . ' ' ;
			} else {
				$price_suffix = ' ' . get_woocommerce_currency_symbol();
			}
			$decimal_seperator = wc_get_price_decimal_separator();
			if ( is_shop() || is_product_category() || is_product() ) {
				?>
				<script type="text/javascript">
				jQuery( function( $ ) {
					$( '.variations_form' ).each( function() {
						$( this ).on( 'show_variation', function( event, variation ) {
							$( 'input[name=quantity]' ).change( function() {
								if ( $( this ).val() > 0 ) {
									<?php echo $base_selector; ?>.find( '#product_total_price' ).css( 'visibility', 'visible' );
									<?php echo $base_selector; ?>.find( '#product_total_price .price' ).html( '<?php echo $price_prefix; ?>' + parseFloat( variation.display_price * $( this ).val() ).toFixed( <?php echo wc_get_price_decimals(); ?> ).replace( '.', '<?php echo $decimal_seperator; ?>' ) + '<?php echo $price_suffix; ?>' );
								} else {
									<?php echo $base_selector; ?>.find( '#product_total_price' ).css( 'visibility', 'hidden' );
								}
							} );
						} );
					} );
					if ( ! <?php echo wp_json_encode( is_product() || ! apply_filters( 'upw_simple_subtotal_enabled', 'yes' === get_option( 'wc_upw_quantity_simple' ) || function_exists( 'is_plugin_active' ) && is_plugin_active( 'qty-increment-buttons-for-woocommerce/qty-increment-buttons-for-woocommerce.php' ) ) ); ?> ) {
						$( document ).ready( function() {
							$( 'li.product-type-simple' ).find( 'input.qty' ).each( function() {
								if ( $( this ).val() > 0 ) {
									<?php echo $base_selector_simple; ?>.find( '#subtotal > span' ).html( ' ' + '<?php echo $price_prefix; ?>' + ( <?php echo $base_selector_simple; ?>.find( '#product_price > span' ).html() * $( this ).val() ).toFixed( <?php echo wc_get_price_decimals(); ?> ).replace( '.', '<?php echo $decimal_seperator; ?>' ) + '<?php echo $price_suffix; ?>' );
								} else {
									<?php echo $base_selector_simple; ?>.find( '#subtotal' ).css( 'visibility', 'hidden' );
								}
							} );
						} );
						$( 'input[name=quantity]' ).on( 'change', function() {
							if ( $( this ).val() > 0 ) {
								<?php echo $base_selector_simple; ?>.find( '#subtotal' ).css( 'visibility', 'visible' );
								<?php echo $base_selector_simple; ?>.find( '#subtotal > span' ).html( ' ' + '<?php echo $price_prefix; ?>' + ( <?php echo $base_selector_simple; ?>.find( '#product_price > span' ).html() * $( this ).val() ).toFixed( <?php echo wc_get_price_decimals(); ?> ).replace( '.', '<?php echo $decimal_seperator; ?>' ) + '<?php echo $price_suffix; ?>' );
							} else {
								<?php echo $base_selector_simple; ?>.find( '#subtotal' ).css( 'visibility', 'hidden' );
							}
						} );
					}
				} );
				</script>
				<?php
			}
		}

		/**
		 * Make sure the ajax add to cart is enabled if certain plugin options are used
		 */
		public function wc_enable_ajax_button() {
			return 'yes';
		}
	}

	/**
	 * Instantiate class
	 */
	$unit_price_for_woocommerce = new WC_UPW();
};
