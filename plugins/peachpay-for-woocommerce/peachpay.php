<?php
/**
 * Plugin Name: Payments Plugin and Checkout Plugin for WooCommerce: Stripe, PayPal, Square, Authorize.net
 * Plugin URI: https://woocommerce.com/products/peachpay
 * Description: Connect and manage all your payment methods, offer shoppers a beautiful Express Checkout, and reduce cart abandonment.
 * Version: 1.104.0
 * Text Domain: peachpay-for-woocommerce
 * Domain Path: /languages
 * Author: PeachPay, Inc.
 * Author URI: https://peachpay.app
 *
 * WC requires at least: 5.0
 * WC tested up to: 8.1.1
 *
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 *
 * @package PeachPay
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once ABSPATH . 'wp-admin/includes/plugin.php';

define( 'PEACHPAY_ABSPATH', plugin_dir_path( __FILE__ ) );
define( 'PEACHPAY_VERSION', get_plugin_data( __FILE__ )['Version'] );
define( 'PEACHPAY_BASENAME', plugin_basename( __FILE__ ) );
define( 'PEACHPAY_PLUGIN_FILE', __FILE__ );
define( 'PEACHPAY_ROUTE_BASE', 'peachpay/v1' );


define( 'PEACHPAY_DEFAULT_BACKGROUND_COLOR', '#21105d' );
define( 'PEACHPAY_DEFAULT_TEXT_COLOR', '#FFFFFF' );

require_once PEACHPAY_ABSPATH . 'core/error-reporting.php';
require_once PEACHPAY_ABSPATH . 'core/util/util.php';
require_once PEACHPAY_ABSPATH . 'core/migrations/migration.php';

require_once PEACHPAY_ABSPATH . 'core/class-peachpay.php';
require_once PEACHPAY_ABSPATH . 'core/peachpay-default-options.php';

/**
 * Returns an instance of PeachPay global instance.
 */
function peachpay() {
	return PeachPay::instance();
}

$GLOBALS['peachpay'] = peachpay();

if ( ! file_exists( WP_PLUGIN_DIR . '/woocommerce/woocommerce.php' ) ) {
	return;
}

if ( ! is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
	return;
}

//
// Followup(refactor): All following code needs put in proper locations
//

require_once PEACHPAY_ABSPATH . 'core/class-peachpay-initializer.php';
$initializer = new PeachPay_Initializer();
if ( ! $initializer::init() ) {
	// Peachpay should stop setup if init fails for any reason.
	return;
}

// Set default options.
peachpay_set_default_options();

// Load utilities.

// Load independent execution paths or hook initializations(Aka these have side effects of being loaded).
require_once PEACHPAY_ABSPATH . 'core/modules/module.php';
require_once PEACHPAY_ABSPATH . 'core/apple-pay.php';

/**
 * Notifies merchant if there store does not have an HTTPS connection.
 */
function unsecure_connection_notice() {
	?>
	<div class="notice notice-error is-dismissible">
		<p>Your site does not support secure connections (HTTPS). Without a secure connection, PeachPay payment methods may not work because payment providers require this security.</p>
	</div>
	<?php
}

/**
 * Initializes plugin compatibility and loads plugin files.
 */
function peachpay_init() {

	load_plugin_textdomain( 'peachpay-for-woocommerce', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

	if ( is_admin() ) {
		if ( ! isset( $_SERVER['HTTPS'] ) ) {
			add_action( 'admin_notices', 'unsecure_connection_notice' );
		}

		add_filter( 'bulk_actions-edit-shop_order', 'peachpay_add_bulk_export_csv' );
		add_filter( 'handle_bulk_actions-edit-shop_order', 'peachpay_handle_bulk_export_csv', 10, 3 );
		add_action( 'admin_notices', 'peachpay_bulk_export_csv_notice' );
	}

	// Load and initialize External plugin compatibility.
	load_plugin_compatibility(
		array(
			array(
				'plugin'        => 'woocommerce-subscriptions/woocommerce-subscriptions.php',
				'compatibility' => 'compatibility/wc-subscriptions.php',
			),
			array(
				'plugin'        => 'woocommerce-product-addons/woocommerce-product-addons.php',
				'compatibility' => 'compatibility/wc-product-addons.php',
			),
			array(
				'plugin'        => 'woo-product-country-base-restrictions/woocommerce-product-country-base-restrictions.php',
				'compatibility' => 'compatibility/wc-country-based-restrictions.php',
			),
			array(
				'plugin'        => 'booster-plus-for-woocommerce/booster-plus-for-woocommerce.php',
				'compatibility' => 'compatibility/booster-for-wc/booster-for-wc.php',
			),
			array(
				'plugin'        => 'woocommerce-product-bundles/woocommerce-product-bundles.php',
				'compatibility' => 'compatibility/wc-product-bundles.php',
			),
			array(
				'plugin'        => array( 'pw-woocommerce-gift-cards/pw-gift-cards.php', 'pw-gift-cards/pw-gift-cards.php' ),
				'compatibility' => 'compatibility/wc-pw-gift-cards.php',
			),
			array(
				'plugin'        => 'custom-product-boxes/custom-product-boxes.php',
				'compatibility' => 'compatibility/custom-product-boxes.php',
			),
			array(
				'plugin'        => 'woocommerce-all-products-for-subscriptions/woocommerce-all-products-for-subscriptions.php',
				'compatibility' => 'compatibility/wc-subscribe-all-things.php',
			),
			array(
				// Note: Not a typo. Plugin file is spelled "adons".
				'plugin'        => 'essential-addons-for-elementor-lite/essential_adons_elementor.php',
				'compatibility' => 'compatibility/essential-addons-elementor.php',
			),
			array(
				'compatibility' => 'compatibility/perfmatters.php',
			),
			array(
				'plugin'        => 'woocommerce-bookings/woocommerce-bookings.php',
				'compatibility' => 'compatibility/woocommerce-bookings.php',
			),
			array(
				'plugin'        => 'yith-woocommerce-product-bundles/init.php',
				'compatibility' => 'compatibility/yith-product-bundles.php',
			),
		)
	);

	do_action( 'peachpay_init_compatibility' );
}
add_action( 'init', 'peachpay_init' );

/**
 * Loads plugin compatibility
 *
 * @param array $plugin_compatibility The plugins and compatibility location.
 */
function load_plugin_compatibility( $plugin_compatibility ) {
	foreach ( $plugin_compatibility as $plugin_info ) {

		// If "plugin" field is not supplied then the compatibility is always loaded.
		if ( ! isset( $plugin_info['plugin'] ) && isset( $plugin_info['compatibility'] ) ) {
			try {
				include_once PEACHPAY_ABSPATH . $plugin_info['compatibility'];
				// phpcs:ignore
			} catch ( Error $error ) {
				// Do no harm.
			}

			continue;
		}

		// Convert plugin name to an array to make simpler.
		if ( ! is_array( $plugin_info['plugin'] ) ) {
			$plugin_info['plugin'] = array( $plugin_info['plugin'] );
		}

		foreach ( $plugin_info['plugin'] as $plugin ) {
			if ( is_plugin_active( $plugin ) && isset( $plugin_info['compatibility'] ) ) {
				try {
					include_once PEACHPAY_ABSPATH . $plugin_info['compatibility'];
                // phpcs:ignore
				} catch ( Error $error ) {
					// Do no harm.
				}
			}
		}
	}
}

/**
 * Loads Peachpay Elementor support.
 *
 * @param \Elementor\Widgets_Manager $widgets_manager Elementor widgets manager.
 */
function peachpay_load_elementor_widget( $widgets_manager ) {
	if ( ! PeachPay_Express_Checkout::enabled() ) {
		return;
	}

	try {
		require_once PEACHPAY_ABSPATH . 'compatibility/class-peachpay-elementor-widget.php';

		$widgets_manager->register( new \Elementor\PeachPay_Elementor_Widget() );
		// phpcs:ignore
	} catch ( \Exception $exception ) {
		// Prevent a fatal error if Elementor class could not be loaded for whatever reason.
	}
}
add_action( 'elementor/widgets/register', 'peachpay_load_elementor_widget' );

/**
 * Given the name of an old option and a set of keys for the new option,
 * migrates data from the given keys to a new array which is returned.
 *
 * @param string $from The name of the old option that we will call WP get_option on.
 * @param array  $keys The array of option keys that should be moved from the old
 *                     options to the new options.
 */
function peachpay_migrate_option_group( string $from, array $keys ) {
	$old_option = get_option( $from );
	$result     = array();
	foreach ( $keys as $key ) {
		if ( isset( $old_option[ $key ] ) ) {
			$result[ $key ] = $old_option[ $key ];
		}
	}
	return $result;
}

/**
 * We have to use this instead of the null coalescing operator (??) due to
 * compatibility requirements for WooCommerce Marketplace.
 *
 * @param  string     $setting_group The name of the option settings.
 * @param  string     $name          The name of the option in the PeachPay settings.
 * @param  mixed|bool $default       The default value to return if the option is not set.
 * @return mixed|false Returns false if the option does not exist or is empty; otherwise
 * returns the option.
 */
function peachpay_get_settings_option( $setting_group, $name, $default = false ) {
	$options = get_option( $setting_group );

	if ( isset( $options[ $name ] ) && '' !== $options[ $name ] ) {
		return $options[ $name ];
	}

	return $default;
}

/**
 * Easily set peachpay option group property values.
 *
 * @param string $setting_group The option group to set.
 * @param string $name          The name of the option in the group.
 * @param mixed  $value         The value to set the targeted option.
 */
function peachpay_set_settings_option( $setting_group, $name, $value ) {
	$options = get_option( $setting_group );

	if ( ! is_array( $options ) ) {
		$options = array();
	}

	$options[ $name ] = $value;

	update_option( $setting_group, $options );
}

/**
 * Indicates if a response is 2xx.
 *
 * @param array | WP_Error $response The response to check.
 */
function peachpay_response_ok( $response ) {
	$code = wp_remote_retrieve_response_code( $response );

	if ( ! is_int( $code ) ) {
		return false;
	}

	if ( $code < 200 || $code > 299 ) {
		return false;
	}

	return true;
}

/**
 * Gets the merchant logo id or null if not set.
 *
 * @returns int | null
 */
function peachpay_get_merchant_logo_id() {
	$image_id = peachpay_get_settings_option( 'peachpay_express_checkout_branding', 'merchant_logo', null );

	if ( null === $image_id || ! $image_id ) {
		return null;
	}

	return $image_id;
}

/**
 * Gets the merchant logo src url or null if not set.
 *
 * @returns string | null
 */
function peachpay_get_merchant_logo_src() {
	$image_id = peachpay_get_merchant_logo_id();

	if ( null === $image_id ) {
		return null;
	}

	$image_src = wp_get_attachment_image_src( $image_id, 'full' );
	if ( is_array( $image_src ) && array_key_exists( 0, $image_src ) ) {
		return $image_src[0];
	} else {
		return null;
	}
}

/**
 * Indicates if the "Test mode" box is checked in the plugin settings.
 */
function peachpay_is_test_mode() {
	return isset( get_option( 'peachpay_payment_options' )['test_mode'] ) && get_option( 'peachpay_payment_options' )['test_mode'];
}

/**
 * Sends a peachpay email.
 *
 * @param array  $body The body of the email.
 * @param string $endpoint The email endpoint to use.
 */
function peachpay_email( $body, $endpoint ) {
	$post_body = wp_json_encode( $body );
	$args      = array(
		'body'        => $post_body,
		'headers'     => array( 'Content-Type' => 'application/json' ),
		'httpversion' => '2.0',
		'blocking'    => false,
	);
	wp_remote_post( peachpay_api_url() . $endpoint, $args );
}

/**
 * Gathers location information for peachpay
 */
function peachpay_location_details() {
	return array(
		'store_country' => WC()->countries->get_base_country(),
	);
}

/**
 * Gets useful information about the merchant customer login so peachpay can adapt where needed.
 */
function peachpay_get_merchant_customer_account() {
	return array(
		'logged_in'                                       => is_user_logged_in(),
		'checkout_registration_enabled'                   => 'yes' === get_option( 'woocommerce_enable_signup_and_login_from_checkout' ),
		'checkout_registration_with_subscription_enabled' => 'yes' === get_option( 'woocommerce_enable_signup_from_checkout_for_subscriptions' ),
		'checkout_login_enabled'                          => 'yes' === get_option( 'woocommerce_enable_checkout_login_reminder' ),
		'auto_generate_username'                          => 'yes' === get_option( 'woocommerce_registration_generate_username' ),
		'auto_generate_password'                          => 'yes' === get_option( 'woocommerce_registration_generate_password' ),
		'allow_guest_checkout'                            => 'yes' === get_option( 'woocommerce_enable_guest_checkout' ),
	);
}

/**
 * Puts together settings for a WC gateway we're passing through.
 *
 * @param WC_Gateway $gateway Gateway.
 * @return array
 */
function peachpay_get_passthrough_gateway_settings( $gateway ) {
	if ( ! $gateway ) {
		return array(
			'enabled' => false,
		);
	}

	return array(
		'enabled'  => 'yes' === $gateway->enabled,
		'metadata' => array(
			'title'                       => $gateway->get_option( 'title', '' ),
			'description'                 => $gateway->get_option( 'description', '' ),
			'instructions'                => $gateway->get_option( 'instructions', '' ),
			'enabled_for_virtual'         => 'yes' === $gateway->get_option( 'enable_for_virtual', 'yes' ),
			'enable_for_shipping_methods' => $gateway->get_option( 'enable_for_methods', array() ),
		),
	);
}

/**
 * Creates a record of what features are enabled and what api version they are so the modal can easily handle different plugins.
 * "version": should only be incremented if a change is breaking. Starts at 1 because the modal uses 0 for backwards compatibility
 * versions before plugins that supply a feature support record.
 * "meta_data": should only be static information. It is also optional.
 */
function peachpay_feature_support_record() {
	$gateways = WC()->payment_gateways()->payment_gateways();

	$cod_gateway    = isset( $gateways['cod'] ) ? $gateways['cod'] : null;
	$cheque_gateway = isset( $gateways['cheque'] ) ? $gateways['cheque'] : null;
	$bacs_gateway   = isset( $gateways['bacs'] ) ? $gateways['bacs'] : null;

	$base_features = array(
		'cod_payment_method'         => peachpay_get_passthrough_gateway_settings( $cod_gateway ),
		'cheque_payment_method'      => peachpay_get_passthrough_gateway_settings( $cheque_gateway ),
		'bacs_payment_method'        => peachpay_get_passthrough_gateway_settings( $bacs_gateway ),
		'translated_modal_terms'     => array(
			'enabled'  => true,
			'metadata' => array(
				'selected_language' => peachpay_get_translated_modal_terms(),
			),
		),
		'coupon_input'               => array(
			'enabled'  => wc_coupons_enabled(),
			'metadata' => array(
				'apply_coupon_url'  => WC_AJAX::get_endpoint( 'apply_coupon' ),
				'remove_coupon_url' => WC_AJAX::get_endpoint( 'remove_coupon' ),
			),
		),
		'cart_item_quantity_changer' => array(
			'enabled'  => (bool) peachpay_get_settings_option( 'peachpay_express_checkout_window', 'enable_quantity_changer', false ),
			'metadata' => array(
				'quantity_changed_url' => WC_AJAX::get_endpoint( 'pp-change-quantity' ),
			),
		),
		'display_product_images'     => array(
			'enabled' => (bool) peachpay_get_settings_option( 'peachpay_express_checkout_window', 'display_product_images', false ),
		),
		'store_support_message'      => array(
			'enabled'  => (bool) peachpay_get_settings_option( 'peachpay_express_checkout_window', 'enable_store_support_message', false ),
			'metadata' => array(
				'text' => peachpay_get_settings_option( 'peachpay_express_checkout_window', 'support_message', '' ),
				'type' => peachpay_get_settings_option( 'peachpay_express_checkout_window', 'support_message_type', 'inline' ),
			),
		),
		'merchant_logo'              => array(
			'enabled'  => (bool) peachpay_get_merchant_logo_id(),
			'metadata' => array(
				'logo_src' => peachpay_get_merchant_logo_src(),
			),
		),
		'button_shadow'              => array(
			'enabled' => (bool) peachpay_get_settings_option( 'peachpay_express_checkout_window', 'button_shadow_enabled' ),
		),
		'express_checkout'           => array(
			'enabled'  => true,
			'metadata' => array(
				'admin_ajax_url'         => admin_url( 'admin-ajax.php' ),
				'checkout_url'           => WC_AJAX::get_endpoint( 'checkout' ),
				'add_to_cart_url'        => WC_AJAX::get_endpoint( 'add-to-cart' ),
				'calculation_url'        => WC_AJAX::get_endpoint( 'pp-calculate-checkout' ),
				'validate_url'           => WC_AJAX::get_endpoint( 'pp-validate-checkout' ),
				'create_transaction_url' => WC_AJAX::get_endpoint( 'pp-create-transaction' ),
				'update_transaction_url' => WC_AJAX::get_endpoint( 'pp-update-transaction' ),
				'lost_password_url'      => wp_lostpassword_url(),
			),
		),
	);

	return (array) apply_filters( 'peachpay_register_feature', $base_features );
}

/**
 * Checks if an item is a variation if so it will get the parent name so we can
 * use variations as subtitles if not returns the product's name.
 *
 * @param  int $id the product ID.
 * @return string the parent product name if exists, otherwise the product name.
 */
function peachpay_get_parent_name( $id ) {
	$product = wc_get_product( $id );

	if ( ! $product ) {
		return '';
	}

	if ( $product instanceof WC_Product_Variation ) {
		$id = $product->get_parent_id();
	}

	$product = wc_get_product( $id );

	return $product->get_name();
}

/**
 * Builds the array of cart product data for the peachpay checkout modal.
 *
 * @param array $wc_line_items List of cart wc product line items.
 */
function peachpay_make_cart_from_wc_cart( $wc_line_items ) {
	$pp_cart = array();

	foreach ( $wc_line_items as $wc_line_item ) {
		$wc_product   = peachpay_product_from_line_item( $wc_line_item );
		$pp_cart_item = array(
			'product_id'          => $wc_product->get_id(),
			'variation_id'        => $wc_product->get_id(), // Why? WC_Product::get_variation_id is deprecated since version 3.0. Use WC_Product::get_id(). It will always be the variation ID if this is a variation.
			'name'                => peachpay_get_parent_name( $wc_product->get_id() ),
			'price'               => peachpay_product_price( $wc_product ),
			'display_price'       => peachpay_product_display_price( $wc_product ),
			'quantity'            => $wc_line_item['quantity'],
			'stock_qty'           => $wc_product->get_stock_quantity(),
			'virtual'             => $wc_product->is_virtual(),
			'subtotal'            => strval( peachpay_product_price( $wc_product ) ), // subtotal and total are only relevant for what shows up in the order dashboard.
			'total'               => strval( peachpay_product_price( $wc_product ) ),
			'variation'           => $wc_line_item['variation'], // This is the actual selected variation attributes.
			'attributes'          => peachpay_product_variation_attributes( $wc_product->get_id() ),
			'image'               => peachpay_product_image( $wc_product ),
			'item_key'            => $wc_line_item['key'],

			// On the cart page only this replaces both including the variation
			// in the name (not in the above code anymore) and using the
			// attributes above because it takes care of variation value
			// formatting as well as plugins which add their own extra
			// variations, like Extra Product Options. This is not available on
			// the product page since the customer hasn't yet selected the options.
			'formatted_item_data' => wc_get_formatted_cart_item_data( $wc_line_item ),
			// If Extra Product Options is not configured to have the variation
			// inside it, then formatted_item_data won't include the variation,
			// so we need to include it in the product name.
			'name_with_variation' => peachpay_product_name_always_with_variation( $wc_product->get_id() ),
			'meta_data'           => array(),
		);

		// Apply meta data for compatibility. This filter can be hooked into anywhere to add needed meta data to cart items on the cart page.
		array_push( $pp_cart, apply_filters( 'peachpay_cart_page_line_item', $pp_cart_item, $wc_line_item ) );
	}

	return $pp_cart;
}

/**
 * Gets the full product name even if the filter
 * woocommerce_product_variation_title_include_attributes has been set to not
 * include the variation in the title.
 *
 * Example usage: add_filter( 'woocommerce_product_variation_title_include_attributes', '__return_false' );
 *
 * This is used for the cart page checkout window to display the variation as part of the title.
 *
 * This pretty much takes the code from the internal function generate_product_title
 * from woocommerce/includes/data-stores/class-wc-product-variation-data-store-cpt.php
 * and removes the filter part.
 *
 * If this is a simple product with no variations, it returns the base name.
 *
 * @param int $id The product id of a given product.
 */
function peachpay_product_name_always_with_variation( $id ) {
	$product = wc_get_product( $id );
	if ( ! $product ) {
		return '';
	}
	if ( $product instanceof WC_Product_Variation ) {
		$separator = apply_filters( 'woocommerce_product_variation_title_attributes_separator', ' - ', $product );
		return get_post_field( 'post_title', $product->get_parent_id() ) . $separator . wc_get_formatted_variation( $product, true, false );
	}
	return $product->get_name();
}

/**
 * Gets order delivery options got a Woocommerce Order Delivery plugin.
 */
function woocommerce_order_delivery_options() {
	if ( ! is_plugin_active( 'woocommerce-order-delivery/woocommerce-order-delivery.php' ) ) {
		return array();
	}
	$wc_od_delivery_days    = get_option( 'wc_od_delivery_days' );
	$delivery_unchecked_day = array();

	// default order delivery setting for delivery days.
	if ( ! get_option( 'wc_od_delivery_days' ) ) {
		array_push( $delivery_unchecked_day, 0 );
	} else {
		$days = array( 0, 1, 2, 3, 4, 5, 6 );
		foreach ( $days as $day ) {
			$wc_od_delivery_days_single = $wc_od_delivery_days[ $day ];
			if ( 'no' === $wc_od_delivery_days_single['enabled'] ) {
				array_push( $delivery_unchecked_day, $day );
			}
		}
	}

	$order_delivery_options = array(
		'wc_od_max_delivery_days' => ! get_option( 'wc_od_max_delivery_days' ) ? 9 : (int) get_option( 'wc_od_max_delivery_days' ),
		'delivery_unchecked_day'  => $delivery_unchecked_day,
	);
	return $order_delivery_options;
}

/**
 * Returns the terms and condition page of the merchant's store
 */
function peachpay_wc_terms_condition() {
	if ( ! function_exists( 'wc_terms_and_conditions_page_id' ) ) {
		return '';
	}

	$id   = wc_terms_and_conditions_page_id();
	$page = $id ? get_permalink( $id ) : null;

	return $page;
}
