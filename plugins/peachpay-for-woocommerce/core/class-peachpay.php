<?php
/**
 * Core PeachPay class.
 *
 * @package PeachPay
 */

defined( 'ABSPATH' ) || exit;

require_once PEACHPAY_ABSPATH . 'core/traits/trait-peachpay-singleton.php';

/**
 * .
 */
final class PeachPay {

	use PeachPay_Singleton;

	/**
	 * The options key prefix.
	 *
	 * @var string $option_prefix .
	 */
	private static $option_prefix = 'peachpay_';

	/**
	 * .
	 */
	private function __construct() {
		if ( ! file_exists( WP_PLUGIN_DIR . '/woocommerce/woocommerce.php' ) ) {
			add_action( 'admin_notices', array( $this, 'woocommerce_not_installed_error_notice' ) );
		} elseif ( ! is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
			add_action( 'admin_notices', array( $this, 'woocommerce_not_activated_error_notice' ) );
		} else {
			$this->hooks();
			$this->includes();
		}
	}

	/**
	 * PeachPay hooks.
	 */
	private function hooks() {
		add_action( 'activated_plugin', 'pp_activation_redirect' );
		add_action( 'before_woocommerce_init', 'pp_declare_wc_hpos_support' );

		add_filter( 'woocommerce_update_order_review_fragments', 'peachpay_native_checkout_data_fragment' );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'woocommerce_blocks_payment_method_type_registration', 'pp_register_core_checkout_blocks_scripts' );

		add_action( 'peachpay_plugin_capabilities_updated', 'peachpay_sync_service_fee_configuration', 0, 1 );
		add_action( 'woocommerce_admin_order_data_after_billing_address', 'peachpay_display_service_fee_tooltip' );
		add_action( 'woocommerce_cart_calculate_fees', 'peachpay_add_service_fee', 1 );
		add_action( 'woocommerce_cart_calculate_fees', 'peachpay_custom_payment_method_fee' );
		add_action( 'peachpay_dynamic_feature_metadata', 'peachpay_dynamic_feature_metadata', 10, 2 );

		add_action( 'wc_ajax_pp-create-transaction', 'pp_wc_ajax_create_transaction' );
		add_action( 'wc_ajax_pp-update-transaction', 'pp_wc_ajax_update_transaction' );
	}

	/**
	 * Includes all dependencies. Dependencies should be responsible for self initialization.
	 */
	private function includes() {
		if ( isset( $_GET['pp-express-checkout'] ) ) {//phpcs:ignore WordPress.Security.NonceVerification.Recommended
			define( 'PEACHPAY_CHECKOUT', 1 );
		}

		include_once PEACHPAY_ABSPATH . 'core/admin/class-peachpay-admin-section.php';
		include_once PEACHPAY_ABSPATH . 'core/abstract/class-peachpay-admin-tab.php';

		// Utilities
		include_once PEACHPAY_ABSPATH . 'core/util/util.php';
		include_once PEACHPAY_ABSPATH . 'core/functions.php';

		include_once PEACHPAY_ABSPATH . 'core/class-peachpay-capabilities.php';
		include_once PEACHPAY_ABSPATH . 'core/class-peachpay-lifecycle.php';
		include_once PEACHPAY_ABSPATH . 'core/class-peachpay-lifecycle-analytics.php';

		// Routes
		include_once PEACHPAY_ABSPATH . 'core/routes/wc-ajax-create-transaction.php';
		include_once PEACHPAY_ABSPATH . 'core/routes/wc-ajax-update-transaction.php';

		// Extensions / integrations
		include_once PEACHPAY_ABSPATH . 'core/traits/trait-peachpay-extension.php';
		include_once PEACHPAY_ABSPATH . 'core/traits/trait-peachpay-admin-extension.php';
		include_once PEACHPAY_ABSPATH . 'core/traits/trait-peachpay-payment-integration.php';

		include_once PEACHPAY_ABSPATH . '/core/payments/stripe/class-peachpay-stripe-integration.php';
		include_once PEACHPAY_ABSPATH . '/core/payments/square/class-peachpay-square-integration.php';
		include_once PEACHPAY_ABSPATH . '/core/payments/paypal/class-peachpay-paypal-integration.php';
		include_once PEACHPAY_ABSPATH . '/core/payments/poynt/class-peachpay-poynt-integration.php';
		include_once PEACHPAY_ABSPATH . '/core/payments/authnet/class-peachpay-authnet-integration.php';
		include_once PEACHPAY_ABSPATH . '/core/payments/peachpay/class-peachpay-payments-integration.php';

		include_once PEACHPAY_ABSPATH . 'core/modules/express-checkout/class-peachpay-express-checkout.php';
		include_once PEACHPAY_ABSPATH . 'core/modules/express-checkout/sections/product-recommendations.php';
		include_once PEACHPAY_ABSPATH . 'core/modules/address-autocomplete/class-peachpay-address-autocomplete.php';
		include_once PEACHPAY_ABSPATH . 'core/modules/bot-protection/class-peachpay-bot-protection.php';
		include_once PEACHPAY_ABSPATH . 'core/modules/analytics/class-peachpay-analytics-extension.php';

		if ( is_admin() ) {
			include_once PEACHPAY_ABSPATH . 'core/admin/class-peachpay-admin.php';
			include_once PEACHPAY_ABSPATH . 'core/admin/class-peachpay-admin-section.php';
		}
	}

	/**
	 * Enqueues only public scripts.
	 */
	public function enqueue_scripts() {
		// Native checkout scripts.
		if ( ! is_null( $this->native_checkout_data() ) && ( is_checkout() || is_add_payment_method_page() ) ) {
			self::enqueue_style( 'peachpay-native-checkout', 'public/dist/native-checkout.bundle.css' );
			self::enqueue_script( 'peachpay-native-checkout', 'public/dist/native-checkout.bundle.js' );
			self::register_script_data( 'peachpay-native-checkout', 'peachpay_checkout_data', $this->native_checkout_data() );
		}

		if ( is_add_payment_method_page() ) {
			self::enqueue_script( 'peachpay-add-payment-method', 'public/dist/add-payment-method.bundle.js' );
		}
	}

	/**
	 * Gets the script data for the PeachPay native checkout experience.
	 */
	public function native_checkout_data() {
		$default_customer_location = wc_get_customer_default_location();

		$native_checkout_data = array(
			'merchant'     => array(
				'defaults' => array(
					'state'   => isset( $default_customer_location['state'] ) ? $default_customer_location['state'] : WC()->countries->get_base_state(),
					'country' => isset( $default_customer_location['country'] ) ? $default_customer_location['country'] : WC()->countries->get_base_country(),
				),
			),

			'page'         => array(
				'is_checkout'       => is_checkout(),
				'is_order_received' => is_wc_endpoint_url( 'order-received' ),
				'is_order_pay'      => is_wc_endpoint_url( 'order-pay' ),
			),

			'plugin'       => array(
				'asset_url_base'  => self::get_asset_url( '' ),
				'feature_support' => apply_filters(
					'peachpay_register_feature',
					array(
						'native_checkout' => array(
							'enabled'  => true,
							'metadata' => array(
								'create_transaction_url' => WC_AJAX::get_endpoint( 'pp-create-transaction' ),
								'update_transaction_url' => WC_AJAX::get_endpoint( 'pp-update-transaction' ),
							),
						),
					)
				),
			),

			// These cart details structure may still change once subscriptions are considered.
			'cart_details' => peachpay_cart_details(),
		);

		if ( is_wc_endpoint_url( 'order-pay' ) ) {
			$order_id = absint( get_query_var( 'order-pay' ) );
			$order    = wc_get_order( $order_id );

			if ( $order instanceof WC_Order ) {
				$native_checkout_data['order_pay_details'] = peachpay_get_order_pay_details( $order );
			}
		}

		return apply_filters( 'peachpay_native_checkout_data', $native_checkout_data );
	}

	/**
	 * Gets the current version of the plugin.
	 */
	public static function get_plugin_version() {
		return PEACHPAY_VERSION;
	}

	/**
	 * Gets the plugin entry name.
	 * Likely always "peachpay-for-woocommerce/peachpay.php"
	 */
	public static function get_plugin_name() {
		return PEACHPAY_BASENAME;
	}

	/**
	 * Gets the current plugin path.
	 */
	public static function get_plugin_path() {
		return PEACHPAY_ABSPATH;
	}

	/**
	 * Gets a fully qualified URL of an asset file.
	 *
	 * @param string $asset_path The path to a asset file to create a URL for.
	 */
	public static function get_asset_url( $asset_path ) {
		return plugin_dir_url( PEACHPAY_ABSPATH . 'public/.' ) . $asset_path;
	}

	/**
	 * Enqueues a JS script to be loaded.
	 *
	 * @param string  $handle The handle to match the script with.
	 * @param string  $path The path to the script relative to the plugin root.
	 * @param array   $deps Any script dependencies to change the insertion order.
	 * @param boolean $in_footer If the script should be placed in the footer or not.
	 * @param boolean $path_is_url If the path is already a complete URL (Useful for external scripts).
	 */
	public static function enqueue_script( $handle, $path, $deps = array(), $in_footer = false, $path_is_url = false ) {
		$version = PEACHPAY_VERSION;
		if ( ! $path_is_url ) {
			$version = gmdate( 'ymd-Gis', filemtime( self::get_plugin_path() . $path ) );
			$path    = plugin_dir_url( self::get_plugin_path() . '/.' ) . $path;
		}

		wp_register_script(
			$handle,
			$path,
			$deps,
			$version,
			$in_footer
		);

		return wp_enqueue_script( $handle );
	}

	/**
	 * Registers a JS script to be loaded. The script must be located inside the public/dist folder.
	 *
	 * @param string  $script_handle The handle to match the script with.
	 * @param string  $script_name The script name to register. This should match the webpack entry name.
	 * @param array   $deps Any script dependencies to change the insertion order.
	 * @param boolean $in_footer If the script should be placed in the footer or not.
	 */
	public static function register_webpack_script( $script_handle, $script_name, $deps = array(), $in_footer = false ) {
		$script_asset_path = PEACHPAY_ABSPATH . "public/dist/$script_name.asset.php";
		if ( ! file_exists( $script_asset_path ) ) {
			return;
		}

		$script_asset = require $script_asset_path;

		$dependencies = array_merge( $script_asset['dependencies'], $deps );

		wp_register_script(
			$script_handle,
			plugin_dir_url( PEACHPAY_ABSPATH . '/.' ) . "public/dist/$script_name.js",
			$dependencies,
			$script_asset['version'],
			$in_footer
		);
	}

	/**
	 * Registers a external JS script to be loaded.
	 *
	 * @param string  $script_handle The handle to match the script with.
	 * @param string  $script_url The absolute URL to the script.
	 * @param boolean $in_footer If the script should be placed in the footer or not.
	 */
	public static function register_external_script( $script_handle, $script_url, $in_footer = false ) {
		wp_register_script(
			$script_handle,
			$script_url,
			array(),
			PEACHPAY_VERSION,
			$in_footer
		);
	}

	/**
	 * Enqueues JS object data to be loaded with a script.
	 *
	 * @param string  $script_handle The script handle to match the script with.
	 * @param string  $object_name The name of the JS object.
	 * @param array   $data The data to set the object equal too.
	 * @param boolean $once If the object should only be enqueued once.
	 */
	public static function register_script_data( $script_handle, $object_name, $data, $once = true ) {
		if ( $once ) {
			$existing_data = wp_scripts()->get_data( $script_handle, 'data' );
			if ( ! empty( $existing_data ) ) {
				return;
			}
		}

		wp_localize_script( $script_handle, $object_name, $data );
	}

	/**
	 * Registers a CSS stylesheet to be loaded. The stylesheet must be located inside the public/dist folder.
	 *
	 * @param string $style_handle The handle to match the stylesheet with.
	 * @param string $style_name The stylesheet name to register. This should match the webpack entry name.
	 * @param array  $dependencies Any style dependencies to change the insertion order.
	 */
	public static function register_webpack_style( $style_handle, $style_name, $dependencies = array() ) {
		wp_register_style(
			$style_handle,
			plugin_dir_url( PEACHPAY_ABSPATH . '/.' ) . "public/dist/$style_name.css",
			$dependencies,
			gmdate( 'ymd-Gis', filemtime( PEACHPAY_ABSPATH . "public/dist/$style_name.css" ) )
		);
	}

	/**
	 * Enqueues a CSS stylesheet to be loaded.
	 *
	 * @param string  $handle The handle to match the style with.
	 * @param string  $path The path to the style relative to the plugin root.
	 * @param array   $deps Any style dependencies to change the insertion order.
	 * @param boolean $in_footer If the style should be placed in the footer or not.
	 */
	public static function enqueue_style( $handle, $path, $deps = array(), $in_footer = false ) {
		wp_register_style(
			$handle,
			plugin_dir_url( self::get_plugin_path() . '/.' ) . $path,
			$deps,
			gmdate( 'ymd-Gis', filemtime( self::get_plugin_path() . $path ) ),
			$in_footer
		);
		wp_enqueue_style( $handle );
	}

	/**
	 * Gets a settings option.
	 *
	 * @param string  $key .
	 * @param boolean $default .
	 */
	public static function get_option( $key, $default = false ) {
		return get_option( self::$option_prefix . $key, $default );
	}

	/**
	 * Updates a settings option.
	 *
	 * @param string $key .
	 * @param mixed  $value .
	 */
	public static function update_option( $key, $value ) {
		return update_option( self::$option_prefix . $key, $value );
	}

	/**
	 * Deletes a settings option.
	 *
	 * @param string $key .
	 */
	public static function delete_option( $key ) {
		return delete_option( self::$option_prefix . $key );
	}

	/**
	 * Displays an error notice if woocommerce is not installed.
	 */
	public function woocommerce_not_installed_error_notice() {
		?>
		<div class="notice notice-error">
			<p>
				<?php
					printf(
						// translators: 1$-2$: opening and closing <strong> tags, 3$-4$: opening and closing link tags, leads to plugins.php in admin.
						esc_html__( 'PeachPay is a WooCommerce extension. Please %3$sinstall and activate WooCommerce%4$s to use PeachPay.', 'peachpay-for-woocommerce' ),
						'<strong>',
						'</strong>',
						'<a href="' . esc_url(
							wp_nonce_url(
								add_query_arg(
									array(
										'action' => 'install-plugin',
										'plugin' => 'woocommerce',
									),
									admin_url( 'update.php' )
								),
								'install-plugin_woocommerce'
							)
						) . '">',
						'</a>'
					);
				?>
			</p>
		</div>
		<?php
	}

	/**
	 * Displays an error notice if woocommerce is not activated.
	 */
	public function woocommerce_not_activated_error_notice() {
		?>
		<div class="notice notice-error">
			<p>
				<?php
					printf(
						// translators: 1$-2$: opening and closing <strong> tags, 3$-4$: opening and closing link tags, leads to plugins.php in admin.
						esc_html__( 'PeachPay is a WooCommerce extension. Please %3$sactivate WooCommerce%4$s to use PeachPay.', 'peachpay-for-woocommerce' ),
						'<strong>',
						'</strong>',
						'<a href="' . esc_url(
							wp_nonce_url(
								add_query_arg(
									array(
										'action' => 'activate',
										'plugin' => 'woocommerce/woocommerce.php',
									),
									admin_url( 'plugins.php' )
								),
								'activate-plugin_woocommerce/woocommerce.php'
							)
						) . '">',
						'</a>'
					);
				?>
			</p>
		</div>
		<?php
	}

	/**
	 * Gets if the PeachPay service fee is enabled for this store.
	 */
	public static function service_fee_enabled() {
		return get_option( 'peachpay_service_fee_enabled' ) === 'yes';
	}

	/**
	 * Gets the PeachPay service fee percentage for this store.
	 */
	public static function service_fee_percentage() {
		return get_option( 'peachpay_service_fee_percentage', 0.015 );
	}

	/**
	 * Gets the Premium status for the plugin.
	 */
	public static function has_premium() {
		return PeachPay_Capabilities::connected( 'woocommerce_premium' );
	}
}
