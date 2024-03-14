<?php

use Automattic\WooCommerce\Internal\DataStores\Orders\CustomOrdersTableController;

/**
 * WC_FreePay_Helper class
 */
class WC_FreePay_Helper {


	/**
	 * price_normalize function.
	 *
	 * Returns the price with decimals. 1010 returns as 10.10.
	 *
	 * @access public static
	 *
	 * @param $price
	 *
	 * @return float
	 */
	public static function price_normalize( $price ) {
		return number_format( $price / 100, 2, wc_get_price_decimal_separator(), '' );
	}

	/**
	 * @param $price
	 *
	 * @return string
	 */
	public static function price_multiplied_to_float( $price ) {
		return number_format( $price / 100, 2, '.', '' );
	}

	/**
	 * Multiplies a custom formatted price based on the WooCommerce decimal- and thousand separators
	 *
	 * @param $price
	 *
	 * @return int
	 */
	public static function price_custom_to_multiplied( $price ) {
		$decimal_separator  = get_option( 'woocommerce_price_decimal_sep' );
		$thousand_separator = get_option( 'woocommerce_price_thousand_sep' );

		$price = str_replace( [ $thousand_separator, $decimal_separator ], [ '', '.' ], $price );

		return self::price_multiply( $price );
	}

	/**
	 * price_multiply function.
	 *
	 * Returns the price with no decimals. 10.10 returns as 1010.
	 *
	 * @access public static
	 *
	 * @param $price
	 *
	 * @return integer
	 */
	public static function price_multiply( $price ) {
		return number_format( $price * 100, 0, '', '' );
	}

	/**
	 * enqueue_javascript_backend function.
	 *
	 * @access public static
	 * @return void
	 */
	public static function enqueue_javascript_backend() {
		if ( self::maybe_enqueue_admin_statics() ) {
			wp_enqueue_script( 'freepay-backend', plugins_url( '/assets/javascript/backend.js', __DIR__ ), [ 'jquery' ], self::static_version() );
			wp_localize_script( 'freepay-backend', 'ajax_object', [ 'ajax_url' => admin_url( 'admin-ajax.php' ) ] );
		}

		wp_enqueue_script( 'freepay-backend-notices', plugins_url( '/assets/javascript/backend-notices.js', __DIR__ ), [ 'jquery' ], self::static_version() );
		wp_localize_script( 'freepay-backend-notices', 'wcfpBackendNotices', [ 'flush' => admin_url( 'admin-ajax.php?action=woo_freepay_flush_runtime_errors' ) ] );
	}

	/**
	 * @return bool
	 */
	protected static function maybe_enqueue_admin_statics() {
		/**
		 * Enqueue on the settings page for the gateways
		 */
		if ( isset( $_GET['page'], $_GET['tab'], $_GET['section'] ) ) {
			if ( $_GET['page'] === 'wc-settings' && $_GET['tab'] === 'checkout' && array_key_exists( $_GET['section'], [ 'freepay' => null ] ) ) {
				return true;
			}
		} /**
		 * Enqueue on the shop order page
		 */
		else if ( is_current_admin_screen( [ get_edit_order_screen_id(), get_edit_subscription_screen_id() ] ) ) {
			return true;
		}

		return false;
	}

	public static function static_version() {
		return 'wcfp-' . WCFP_VERSION;
	}


	/**
	 * enqueue_stylesheet function.
	 *
	 * @access public static
	 * @return void
	 */
	public static function enqueue_stylesheet() {
		wp_enqueue_style( 'woocommere-freepay-style', plugins_url( '/assets/stylesheets/woocommerce-freepay.css', __DIR__ ), [], self::static_version() );
	}


	/**
	 * load_i18n function.
	 *
	 * @access public static
	 * @return void
	 */
	public static function load_i18n() {
		load_plugin_textdomain( 'freepay-for-woocommerce', false, dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/' );
	}


	/**
	 * option_is_enabled function.
	 *
	 * Checks if a setting options is enabled by checking on yes/no data.
	 *
	 * @access public static
	 *
	 * @param mixed $value
	 *
	 * @return int
	 */
	public static function option_is_enabled( $value ) {
		return ( $value === 'yes' ) ? 1 : 0;
	}


	/**
	 * get_callback_url function
	 *
	 * Returns the order's main callback url
	 *
	 * @access public
	 *
	 * @param null $post_id
	 *
	 * @return string
	 */
	public static function get_callback_url( $post_id = null ) {
		$args = [ 'wc-api' => 'WC_FreePay' ];

		if ( $post_id !== null ) {
			$args['order_post_id'] = $post_id;
		}

		$args = apply_filters( 'woo_freepay_callback_args', $args, $post_id );

		return apply_filters( 'woo_freepay_callback_url', add_query_arg( $args, home_url( '/' ) ), $args, $post_id );
	}


	/**
	 * is_url function
	 *
	 * Checks if a string is a URL
	 *
	 * @access public
	 *
	 * @param $url
	 *
	 * @return string
	 */
	public static function is_url( $url ) {
		return ! filter_var( $url, FILTER_VALIDATE_URL ) === false;
	}

	/**
	 * @param $payment_type
	 *
	 * @return null
	 * @since 4.5.0
	 *
	 */
	public static function get_payment_type_logo( $payment_type ) {
		$logos = [
			'dankort'          => 'dankort.svg',
			'maestro'          => 'maestro.svg',
			'mastercard'       => 'mastercard.svg',
			'mobilepay'        => 'mobilepay.svg',
			'visa'             => 'visa.svg',
			'visa-electron'    => 'visaelectron.png',
		];

		if ( array_key_exists( trim( $payment_type ), $logos ) ) {
			return WC_FP_MAIN()->plugin_url( 'assets/images/cards/' . $logos[ $payment_type ] );
		}

		return null;
	}

	/**
	 * @param      $value
	 * @param null $default
	 *
	 * @return null
	 */
	public static function value( $value, $default = null ) {
		if ( empty( $value ) ) {
			return $default;
		}

		return $value;
	}

	/**
	 * Prevents qTranslate to make browser redirects resulting in missing callback data.
	 *
	 * @param $url_lang
	 * @param $url_orig
	 * @param $url_info
	 *
	 * @return bool
	 */
	public static function qtranslate_prevent_redirect( $url_lang, $url_orig, $url_info ) {
		// Prevent only on wc-api for this specific gateway
		if ( isset( $url_info['query'] ) && stripos( $url_info['query'], 'wc-api=wc_freepay' ) !== false ) {
			return false;
		}

		return $url_lang;
	}

	/**
	 * @param $bypass
	 *
	 * @return bool
	 */
	public static function spamshield_bypass_security_check( $bypass ) {
		return isset( $_GET['wc-api'] ) && strtolower( $_GET['wc-api'] ) === 'wc_freepay';
	}

	/**
	 * Inserts a new key/value after the key in the array.
	 *
	 * @param string $needle The array key to insert the element after
	 * @param array $haystack An array to insert the element into
	 * @param string $new_key The key to insert
	 * @param mixed $new_value An value to insert
	 *
	 * @return array The new array if the $needle key exists, otherwise an unmodified $haystack
	 */
	public static function array_insert_after( $needle, $haystack, $new_key, $new_value ) {

		if ( array_key_exists( $needle, $haystack ) ) {

			$new_array = [];

			foreach ( $haystack as $key => $value ) {

				$new_array[ $key ] = $value;

				if ( $key === $needle ) {
					$new_array[ $new_key ] = $new_value;
				}
			}

			return $new_array;
		}

		return $haystack;
	}

	public static function can_user_empty_logs() {
		return apply_filters( 'woo_freepay_can_user_empty_logs', current_user_can( 'administrator' ) );
	}

	public static function can_user_flush_cache() {
		return apply_filters( 'woo_freepay_can_user_flush_cache', current_user_can( 'administrator' ) );
	}

	/**
	 * Checks if High Performance Order Storage is enabled
	 * @return bool
	 */
	public static function is_HPOS_enabled() {
		return wc_get_container()->get( CustomOrdersTableController::class )->custom_orders_table_usage_is_enabled();
	}
}
