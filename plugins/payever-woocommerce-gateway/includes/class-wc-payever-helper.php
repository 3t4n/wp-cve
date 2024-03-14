<?php
if ( ! defined( 'ABSPATH' ) || class_exists( 'WC_Payever_Helper' ) ) {
	return;
}

use Payever\Sdk\Payments\Enum\PaymentMethod;
use Payever\Sdk\Payments\Enum\Status;

/**
 * WC_Payever_Helper Class.
 *
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 */
class WC_Payever_Helper {

	use WC_Payever_WP_Wrapper_Trait;

	const PLUGIN_CODE = 'payever';
	const SHOP_SYSTEM = 'woocommerce';

	const LOCALE_STORE_VALUE = 'store';

	const PAYEVER_PREFIX             = 'payever_';
	const SANTANDER_PREFIX           = 'santander_';
	const SESSION_HIDDEN_METHODS_KEY = 'payever_hidden_methods';
	const DEFAULT_SHIPPED_STATUS     = 'wc-completed';

	const SANDBOX_URL_CONFIG_KEY               = 'payeverSandboxUrl';
	const LIVE_URL_CONFIG_KEY                  = 'payeverLiveUrl';
	const SANDBOX_THIRD_PARTY_PLODUCTS_URL_KEY = 'payeverSandboxThirdPartyProductsUrl';
	const LIVE_THIRD_PARTY_PLODUCTS_URL_KEY    = 'payeverLiveThirdPartyProductsUrl';
	const KEY_PLUGIN_COMMAND_TIMESTAMP         = 'payeverCommandTimestamp';
	const KEY_PLUGIN_VERSION                   = 'payeverPluginVersion';
	const KEY_API_VERSION                      = 'payeverApiVersion';
	const DEFAULT_API_VERSION                  = 2;

	const PAYEVER_ENABLED                      = 'payever_enabled';
	const PAYEVER_ENVIRONMENT                  = 'payever_environment';
	const PAYEVER_CLIENT_SECRET                = 'payever_client_secrect';
	const PAYEVER_CLIENT_ID                    = 'payever_client_id';
	const PAYEVER_BUSINESS_ID                  = 'payever_slug';
	const PAYEVER_ACTIVE_FE_ON_SINGLE_PAGE     = 'payever_active_widget_on_single_page';
	const PAYEVER_ACTIVE_FE_ON_CART            = 'payever_active_widget_on_cart';
	const PAYEVER_FE_DEFAULT_SHIPPING_METHOD   = 'payever_fe_default_shipping_method';
	const PAYEVER_WIDGET_ID                    = 'payever_widget_id';
	const PAYEVER_WIDGET_THEME                 = 'payever_widget_theme';
	const PAYEVER_CHECKOUT_ID                  = 'payever_checkout_id';
	const PAYEVER_ACTIVE_EXPRESS_WIDGET        = 'payever_express_widget_type';
	const PAYEVER_DISPLAY_TITLE                = 'payever_display_payment_name';
	const PAYEVER_DISPLAY_ICON                 = 'payever_display_payment_icon';
	const PAYEVER_DISPLAY_DESCRIPTION          = 'payever_display_payment_description';
	const PAYEVER_SHIPPED_STATUS               = 'payever_shipped_status';
	const PAYEVER_LANGUAGES                    = 'payever_languages';
	const PAYEVER_REDIRECT_MODE                = 'payever_redirect_to_payever';
	const PAYEVER_LOG_LEVEL                    = 'payever_log_level';
	const PAYEVER_LOG_DIAGNOSTIC               = 'payever_log_diagnostic';
	const PAYEVER_APM_SECRET_SANDBOX           = 'payever_apm_secret_sandbox';
	const PAYEVER_APM_SECRET_LIVE              = 'payever_apm_secret_live';
	const PAYEVER_ACTIVE_PAYMENTS              = 'woocommerce_payever_active_payments';
	const PAYEVER_ACTIVE_WIDGETS               = 'woocommerce_payever_payment_widgets';
	const PAYEVER_ADDRESS_EQUALITY_METHODS     = 'payever_address_equality_payments';
	const PAYEVER_CHECK_VARIANT_FOR_ADDRESS_EQUALITY     = 'payever_check_Variant_for_address_equality';
	const PAYEVER_SHIPPING_NOT_ALLOWED_METHODS = 'payever_shipping_not_allowed_payments';
	const PAYEVER_ISSET_LIVE                   = 'payever_isset_live';
	const PAYEVER_LIVE_CLIENT_SECRET           = 'payever_live_client_secrect';
	const PAYEVER_LIVE_CLIENT_ID               = 'payever_live_client_id';
	const PAYEVER_LIVE_BUSINESS_ID             = 'payever_live_slug';
	const PAYEVER_OAUTH_TOKEN                  = 'payever_oauth_token';
	const PAYEVER_MIGRATION                    = 'payever_migration';
	const PAYEVER_LAST_MIGRATION_FAILED        = 'payever_last_migration_failed';
	const PAYEVER_PRODUCTS_SYNC_ENABLED        = 'payever_products_synchronization_enabled';
	const PAYEVER_PRODUCTS_SYNC_ENTITY         = 'payever_products_synchronization_entity';
	const PAYEVER_PRODUCTS_SYNC_TOKEN          = 'payever_products_synchronization_token';
	const PAYEVER_PRODUCTS_SYNC_MODE           = 'payever_products_synchronization_mode';
	const PAYEVER_FE_CALLBACK                  = 'payever_finance_express_%s';
	const PAYEVER_FE_REFERENCE_PLACEHOLDER     = '?reference=--PAYMENT-ID--';

	const WIDGET_THEME_DARK                    = 'dark';
	const WIDGET_THEME_LIGHT                   = 'light';

	private static $instance;

	/** @var WC_Payever_Order_Total */
	private $order_total_model;

	private function __construct() {
	}

	/**
	 *
	 * @return WC_Payever_Helper
	 * @codeCoverageIgnore
	 */
	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * @throws \RuntimeException
	 */
	public static function assert_wc_version_exists() {
		if ( ! defined( 'WOOCOMMERCE_VERSION' ) ) {
			$wcVersion = get_option( 'woocommerce_version' );
			if ( $wcVersion ) {
				define( 'WOOCOMMERCE_VERSION', $wcVersion );
				return;
			}
			throw new \RuntimeException( 'Unable to load woocommerce version' );
		}
	}

	/**
	 * Fetch the global configuration values from the database
	 *
	 * @return array
	 */
	public function get_payever_plugin_settings() {
		return array(
			self::PAYEVER_ENABLED             => 'yes' === $this->get_wp_wrapper()->get_option( self::PAYEVER_ENABLED ),
			self::PAYEVER_ENVIRONMENT         => $this->get_wp_wrapper()->get_option( self::PAYEVER_ENVIRONMENT ),
			self::PAYEVER_CLIENT_SECRET       => $this->get_wp_wrapper()->get_option( self::PAYEVER_CLIENT_SECRET ),
			self::PAYEVER_CLIENT_ID           => $this->get_wp_wrapper()->get_option( self::PAYEVER_CLIENT_ID ),
			self::PAYEVER_BUSINESS_ID         => $this->get_wp_wrapper()->get_option( self::PAYEVER_BUSINESS_ID ),
			self::PAYEVER_DISPLAY_TITLE       => $this->get_wp_wrapper()->get_option( self::PAYEVER_DISPLAY_TITLE ),
			self::PAYEVER_DISPLAY_ICON        => $this->get_wp_wrapper()->get_option( self::PAYEVER_DISPLAY_ICON ),
			self::PAYEVER_DISPLAY_DESCRIPTION => $this->get_wp_wrapper()->get_option( self::PAYEVER_DISPLAY_DESCRIPTION ),
			self::PAYEVER_SHIPPED_STATUS      => $this->get_wp_wrapper()->get_option( self::PAYEVER_SHIPPED_STATUS ),
			self::PAYEVER_LANGUAGES           => $this->get_wp_wrapper()->get_option( self::PAYEVER_LANGUAGES ),
			self::PAYEVER_REDIRECT_MODE       => $this->get_wp_wrapper()->get_option( self::PAYEVER_REDIRECT_MODE ),
			self::PAYEVER_LOG_LEVEL           => $this->get_wp_wrapper()->get_option( self::PAYEVER_LOG_LEVEL ),
			self::PAYEVER_LOG_DIAGNOSTIC      => $this->get_wp_wrapper()->get_option( self::PAYEVER_LOG_DIAGNOSTIC ),
		);
	}

	/**
	 * Fetch the widget configuration values from the database
	 *
	 * @return array
	 */
	public function get_payever_widget_settings() {
		$widget_id   = $this->get_wp_wrapper()->get_option( self::PAYEVER_WIDGET_ID );
		$widget_theme = $this->get_wp_wrapper()->get_option( self::PAYEVER_WIDGET_THEME );
		$checkout_id = $this->get_wp_wrapper()->get_option( self::PAYEVER_CHECKOUT_ID );
		$business_id = $this->get_wp_wrapper()->get_option( self::PAYEVER_BUSINESS_ID );

		$current_widget_option = $this->get_wp_wrapper()->get_option( self::PAYEVER_ACTIVE_EXPRESS_WIDGET );

		if ( $current_widget_option ) {
			$woo_payment_widgets_json = $this->get_wp_wrapper()->get_option( self::PAYEVER_ACTIVE_WIDGETS );

			if ( $woo_payment_widgets_json ) {
				$woo_payment_widgets = json_decode( $woo_payment_widgets_json, true );
				$widget_id           = $current_widget_option;
				$checkout_id         = $woo_payment_widgets[ $current_widget_option ]['checkout_id'];
				$business_id         = $woo_payment_widgets[ $current_widget_option ]['business_id'];
			}
		}

		return array(
			self::PAYEVER_ACTIVE_FE_ON_SINGLE_PAGE   => 'yes' === $this->get_wp_wrapper()->get_option( self::PAYEVER_ACTIVE_FE_ON_SINGLE_PAGE ),
			self::PAYEVER_ACTIVE_FE_ON_CART          => 'yes' === $this->get_wp_wrapper()->get_option( self::PAYEVER_ACTIVE_FE_ON_CART ),
			self::PAYEVER_FE_DEFAULT_SHIPPING_METHOD => $this->get_wp_wrapper()->get_option( self::PAYEVER_FE_DEFAULT_SHIPPING_METHOD ),
			self::PAYEVER_ENVIRONMENT                => $this->get_wp_wrapper()->get_option( self::PAYEVER_ENVIRONMENT ),
			self::PAYEVER_CHECKOUT_ID                => $checkout_id,
			self::PAYEVER_WIDGET_ID                  => $widget_id,
			self::PAYEVER_WIDGET_THEME               => $widget_theme,
			self::PAYEVER_BUSINESS_ID                => $business_id,
		);
	}

	/**
	 * Returns active payever widget options
	 *
	 * @return array
	 */
	public function get_active_payever_widget_options() {
		$payever_widget_options   = array( '' => __( '--- Choose the widget ---', 'payever-woocommerce-gateway' ) );
		$woo_payment_widgets_json = $this->get_wp_wrapper()->get_option( self::PAYEVER_ACTIVE_WIDGETS );

		if ( $woo_payment_widgets_json ) {
			$woo_payment_widgets = json_decode( $woo_payment_widgets_json, true );
			foreach ( $woo_payment_widgets as $widget_id => $woo_payment_widget ) {
				$payment_methods = array_unique( $woo_payment_widget['payments'] ?: array() );
				foreach ( $payment_methods as &$payment_method ) {
					$payment_method = __( $payment_method . '.description_offer', 'payever-woocommerce-gateway' );
				}

				$payever_widget_options[ $widget_id ]
					= __( $woo_payment_widget['type'], 'payever-woocommerce-gateway' ) . ' - ' . implode( ', ', $payment_methods );
			}
		}

		return $payever_widget_options;
	}

	/**
	 * @return array
	 */
	public function get_widget_themes() {
		return array(
			self::WIDGET_THEME_LIGHT => __( 'payeverAdminMenuConfigSettingsWidgetThemeLight', 'payever-woocommerce-gateway' ),
			self::WIDGET_THEME_DARK  => __( 'payeverAdminMenuConfigSettingsWidgetThemeDark', 'payever-woocommerce-gateway' ),
		);
	}

	/**
	 * @param $payment_option
	 *
	 * @return mixed
	 */
	public function remove_payever_prefix( $payment_option ) {
		return str_replace( self::PAYEVER_PREFIX, '', $payment_option );
	}

	/**
	 * @param $payment_option
	 *
	 * @return string
	 */
	public function add_payever_prefix( $payment_option ) {
		return self::PAYEVER_PREFIX . $payment_option;
	}

	/**
	 * Checks santanders
	 *
	 * @param $payment_method
	 *
	 * @return bool
	 */
	public function is_santander( $payment_method ) {
		if ( false !== strpos( $payment_method, self::SANTANDER_PREFIX ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Checks if payment methods is ivy
	 *
	 * @param $payment_method
	 *
	 * @return bool
	 */
	public function is_ivy( $payment_method ) {
		if ( false !== strpos( $payment_method, PaymentMethod::METHOD_IVY ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Checks if payment methods is openbank
	 *
	 * @param $payment_method
	 *
	 * @return bool
	 */
	public function is_openbank( $payment_method ) {
		if ( false !== strpos( $payment_method, PaymentMethod::METHOD_OPENBANK ) ) {
			return true;
		}

		return false;
	}

	/**
	 * @param $payment_method
	 *
	 * @return bool
	 */
	public function is_payever_method( $payment_method ) {
		if ( false !== strpos( $payment_method, self::PAYEVER_PREFIX ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Checks if v2 api version is activated
	 *
	 * @return bool
	 */
	public function is_v2_api_version() {
		if ( 2 === $this->get_api_version() ) {
			return true;
		}

		return false;
	}

	public function validate_order_payment_method( $order ) {
		$payment_method = $this->get_payment_method( $order );

		return $this->is_payever_method( $payment_method );
	}

	/**
	 * @param WC_Order $order
	 *
	 * @return string|null
	 */
	public function get_payment_method( $order ) {
		$payment_method = null;
		if ( is_object( $order ) ) {
			if ( $this->get_wp_wrapper()->version_compare( WOOCOMMERCE_VERSION, '3.0.0', '>=' ) ) {
				return $order->get_payment_method();
			}
			$payment_method = $order->payment_method;
		}

		return $payment_method;
	}

	/**
	 * @param $order_id
	 *
	 * @return bool
	 */
	public function is_allow_order_capture_by_qty( $order_id ) {
		$order_items = $this->get_order_total_model()->get_order_items( $order_id );
		foreach ( $order_items as $item ) {
			if ( array_key_exists( 'captured_amount', $item ) && $item['captured_amount'] > 0 ) {
				// customer was use shipping by qty before
				return false;
			}
		}

		return true;
	}

	/**
	 * @param $order_id
	 *
	 * @return bool
	 */
	public function is_allow_order_capture_by_amount( $order_id ) {
		$totals = $this->get_order_total_model()->get_totals( $order_id );

		/**
		 * order not captured yet or not allowed capture by qty
		 */
		return 0.001 <= $totals['captured'] || ! $this->is_allow_order_capture_by_qty( $order_id );
	}

	/**
	 * @param WC_Order $order
	 *
	 * @return string|null
	 */
	public function get_customer_note( $order ) {
		$note = null;
		if ( is_object( $order ) ) {
			if ( $this->get_wp_wrapper()->version_compare( WOOCOMMERCE_VERSION, '3.0.0', '>=' ) ) {
				return $order->get_customer_note();
			}
			$note = $order->customer_note;
		}

		return $note;
	}

	/**
	 * Hide payever methods
	 *
	 * @param $methods
	 */
	public function add_payever_hidden_method( $method ) {
		$method         = $this->add_payever_prefix( $method );
		$hidden_methods = $this->get_payever_hidden_methods();
		if ( in_array( $method, $this->get_allowed_to_hide_methods() ) && ! in_array( $method, $hidden_methods ) ) {
			$hidden_methods[] = $method;
			WC()->session->set( self::SESSION_HIDDEN_METHODS_KEY, $hidden_methods );
		}
	}

	/**
	 * Returns payever hidden methods from session
	 *
	 * @return array
	 */
	private function get_payever_hidden_methods() {
		return WC()->session->get( self::SESSION_HIDDEN_METHODS_KEY )
			? WC()->session->get( self::SESSION_HIDDEN_METHODS_KEY )
			: array();
	}

	/**
	 * Returns methods to hide
	 *
	 * @return array
	 */
	private function get_allowed_to_hide_methods() {
		return $this->add_payever_prefix_to_payment_methods( PaymentMethod::getShouldHideOnRejectMethods() );
	}

	/**
	 * @param $method
	 * @param $variantId
	 *
	 * @return bool
	 */
	public function validate_hidden_methods( $method, $variantId ) {
		$method         = $this->add_payever_prefix( $method );
		$hidden_methods = $this->get_payever_hidden_methods();

		if ( $this->payever_is_current_addresses_different() && $this->is_hidden_method_on_different_address( $method, $variantId ) ) {
			return false;
		}

		if ( in_array( $method, $hidden_methods ) ) {
			return false;
		}

		return true;
	}

	/**
	 * @param $method
	 * @param $variant_id
	 *
	 * @return bool
	 */
	public function is_hidden_method_on_different_address( $method, $variant_id ) {
		if ( $this->check_variant_for_address_equality() ) {
			return in_array( $variant_id, $this->get_payever_hide_on_different_address_methods( false ) );
		}

		return in_array( $this->add_payever_prefix( $method ), $this->get_payever_hide_on_different_address_methods( true ) );
	}


	/**
	 * Returns api version
	 *
	 * @return int
	 */
	private function get_api_version() {
		$api_version = $this->get_wp_wrapper()->get_option( self::KEY_API_VERSION );
		if ( $api_version ) {
			return (int) $api_version;
		}

		return self::DEFAULT_API_VERSION;
	}

	/**
	 *
	 * @return bool
	 */
	private function check_variant_for_address_equality() {

		return $this->get_wp_wrapper()->get_option( self::PAYEVER_CHECK_VARIANT_FOR_ADDRESS_EQUALITY ) ? true : false;
	}

	/**
	 * @return bool
	 */
	private function payever_is_current_addresses_different() {
		static $checkFields = array(
			'country',
			'postcode',
			'state',
			'city',
			'address_1',
			'address_2',
			'first_name',
			'last_name',
		);
		static $result = null;

		if ( ! isset( $_POST['_wooaddr_nonce'] ) ) {
			$_POST['_wooaddr_nonce'] = wp_create_nonce( '_wooaddr_nonce' );
		}

		if ( null === $result ) {
			/** @var WooCommerce $woocommerce */
			global $woocommerce;

			$checkout = $woocommerce->checkout();
			$posted_data = $this->get_posted_data( $checkout, array() );
			$is_shipping_empty = true;
			$result   = $this->is_difference_address( $checkout, $checkFields, $posted_data, $is_shipping_empty );

			if ( $is_shipping_empty ) {
				$result = false;
			}
		}

		return $result;
	}

	/**
	 * @param $checkout
	 * @param $check_fields
	 * @param $posted_data
	 * @param $is_shipping_empty
	 *
	 * @return bool
	 */
	private function is_difference_address( $checkout, $check_fields, $posted_data, &$is_shipping_empty ) {
		foreach ( $check_fields as $field ) {
			$shipping_val = $this->get_address_field_value( $posted_data, $checkout, $field, 'shipping_' );
			$billing_val  = $this->get_address_field_value( $posted_data, $checkout, $field, 'billing_' );

			if ( 'postcode' === $field ) {
				$shipping_val = str_replace( ' ', '', $shipping_val );
				$billing_val  = str_replace( ' ', '', $billing_val );
			}

			if ( 'country' !== $field && ! empty( $shipping_val ) ) {
				$is_shipping_empty = false;
			}

			if ( $shipping_val && $billing_val && $billing_val !== $shipping_val ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * @param $posted_data
	 * @param $checkout
	 * @param $field
	 * @param $prefix
	 *
	 * @return mixed
	 */
	private function get_address_field_value( $posted_data, $checkout, $field, $prefix ) {
		return ! empty( $posted_data[ $prefix . $field ] ) ? $posted_data[ $prefix . $field ] : $checkout->get_value( $prefix . $field );
	}

	/**
	 * @param $checkout
	 * @param $posted_data
	 *
	 * @return mixed|null
	 */
	private function get_posted_data( $checkout, $posted_data ) {
		if ( version_compare( WOOCOMMERCE_VERSION, '3.1.0', '>=' ) ) {
			return $checkout->get_posted_data();
		}

		// @todo Verify parse_str. It returns void
		return parse_str(
			isset( $_POST['post_data'] ) ? wc_clean( wp_unslash( $_POST['post_data'] ) ) : '',
			$posted_data
		);
	}

	/**
	 * Methods we should hide if shipping and billing addresses is different
	 * @param bool $addPrefix
	 *
	 * @return array
	 */
	private function get_payever_hide_on_different_address_methods( $addPrefix ) {
		$address_equality_methods = $this->get_wp_wrapper()->get_option( self::PAYEVER_ADDRESS_EQUALITY_METHODS )
			?: PaymentMethod::getShouldHideOnDifferentAddressMethods();

		return $addPrefix ? $this->add_payever_prefix_to_payment_methods( $address_equality_methods ) : $address_equality_methods;
	}

	/**
	 * @param array $payment_methods
	 *
	 * @return array
	 */
	private function add_payever_prefix_to_payment_methods( $payment_methods ) {
		foreach ( $payment_methods as &$payment_method ) {
			$payment_method = $this->add_payever_prefix( $payment_method );
		}

		return $payment_methods;
	}

	/**
	 * Clears the sessions on each page fragments
	 *
	 * @return void
	 */
	public function clear_session_fragments() {
		if ( isset( WC()->session->payever_thankyou_page ) ) {
			unset( WC()->session->payever_thankyou_page );
		}
		if ( isset( WC()->session->payever_receipt_page ) ) {
			unset( WC()->session->payever_receipt_page );
		}
	}

	/**
	 * Returns payever modes
	 *
	 * @return array
	 */
	public function get_payever_modes() {
		return array(
			'0' => __( 'Live', 'payever-woocommerce-gateway' ),
			'1' => __( 'Sandbox', 'payever-woocommerce-gateway' ),
		);
	}

	public function get_payever_status_mapping() {
		return array(
			Status::STATUS_IN_PROCESS => 'on-hold',
			Status::STATUS_ACCEPTED   => 'processing',
			Status::STATUS_PAID       => 'processing',
			Status::STATUS_DECLINED   => 'failed',
			Status::STATUS_CANCELLED  => 'cancelled',
			Status::STATUS_FAILED     => 'cancelled',
			Status::STATUS_REFUNDED   => 'refunded',
			Status::STATUS_NEW        => 'pending',
		);
	}

	/**
	 * @return bool
	 */
	public function is_products_sync_enabled() {
		return (bool) $this->get_wp_wrapper()->get_option( self::PAYEVER_PRODUCTS_SYNC_ENABLED );
	}

	/**
	 * @return string
	 */
	public function get_product_sync_token() {
		return $this->get_wp_wrapper()->get_option( self::PAYEVER_PRODUCTS_SYNC_TOKEN );
	}

	/**
	 * @return bool
	 */
	public function is_products_sync_cron_mode() {
		return 'cron' === $this->get_wp_wrapper()->get_option( self::PAYEVER_PRODUCTS_SYNC_MODE );
	}

	/**
	 * Gets product id by sku
	 *
	 * @param string $sku
	 *
	 * @return int The found product variation ID, or 0 on failure.
	 */
	public function get_product_variation_id_by_sku( $sku ) {
		global $wpdb;
		$post_id = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT posts.ID
				FROM $wpdb->posts AS posts
				WHERE posts.post_type IN ( 'product', 'product_variation' )
				AND posts.post_status != 'trash'
				AND posts.post_name = %s
				LIMIT 1",
				$sku
			)
		);

		return (int) $this->get_wp_wrapper()->apply_filters( 'get_product_id_by_sku', $post_id, $sku );
	}

	/**
	 * Gets the incoming request headers. Some servers are not using
	 * Apache and "getallheaders()" will not work so we may need to
	 * build our own headers.
	 */
	public function get_request_headers() {
		$headers = array();

		foreach ( $_SERVER as $name => $value ) {
			if ( 'HTTP_' === substr( $name, 0, 5 ) ) {
				$headers[ str_replace( ' ', '-', ucwords( strtolower( str_replace( '_', ' ', substr( $name, 5 ) ) ) ) ) ] = sanitize_text_field( wp_unslash( $value ) ); // WPCS: input var ok, CSRF ok.
			}
		}

		return $headers;
	}

	/**
	 * @param string|int $key
	 *
	 * @return false|string
	 * @throws Exception
	 */
	public function get_hash( $key ) {
		$client_config = WC_Payever_Api::get_instance()->get_plugins_api_client()->getConfiguration();

		return hash_hmac( 'sha256', $client_config->getClientId() . $key, $client_config->getClientSecret() );
	}

	/**
	 * @param WC_Payever_Checkout_Wrapper $checkout_wrapper
	 *
	 * @return $this
	 * @codeCoverageIgnore
	 * @internal
	 */
	public function set_checkout_wrapper( WC_Payever_Checkout_Wrapper $checkout_wrapper ) {
		$this->checkout_wrapper = $checkout_wrapper;

		return $this;
	}

	/**
	 * Returns callback url for express widget
	 *
	 * @param string $type
	 *
	 * @return string
	 */
	public function get_widget_callback_url( $type ) {
		return WC()->api_request_url( '' ) . sprintf( self::PAYEVER_FE_CALLBACK, $type );
	}

	/**
	 * Returns notice url for express widget
	 *
	 * @param string $type
	 *
	 * @return string
	 */
	public function get_widget_notice_url() {
		$notice_url = WC()->api_request_url( '' ) . sprintf( self::PAYEVER_FE_CALLBACK, 'notice' );

		return $notice_url . self::PAYEVER_FE_REFERENCE_PLACEHOLDER;
	}

	/**
	 * @return array
	 */
	public function get_available_shipping_methods() {
		$zones = WC_Shipping_Zones::get_zones();

		$shipping_methods = array_column( $zones, 'shipping_methods' );

		return reset( $shipping_methods );
	}

	/**
	 * @return array
	 */
	public function get_available_shipping_method_options() {
		$shipping_methods = $this->get_available_shipping_methods();

		$shipping_method_options = array();
		foreach ( $shipping_methods as $shipping_method ) {
			$shipping_method_options[ $shipping_method->id . ':' . $shipping_method->get_instance_id() ] = $shipping_method->get_method_title();
		}

		return $shipping_method_options;
	}

	/**
	 * @return WC_Payever_Order_Total
	 * @codeCoverageIgnore
	 */
	public function get_order_total_model() {
		return null === $this->order_total_model
			? $this->order_total_model = new WC_Payever_Order_Total()
			: $this->order_total_model;
	}
}
