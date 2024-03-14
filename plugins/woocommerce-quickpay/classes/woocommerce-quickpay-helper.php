<?php

use Automattic\WooCommerce\Internal\DataStores\Orders\CustomOrdersTableController;

/**
 * WC_QuickPay_Helper class
 *
 * @class          WC_QuickPay_Helper
 * @version        1.0.0
 * @package        Woocommerce_QuickPay/Classes
 * @category       Class
 * @author         PerfectSolution
 */
class WC_QuickPay_Helper {


	/**
	 * price_normalize function.
	 *
	 * Returns the price with decimals. 1010 returns as 10.10.
	 *
	 * @access public static
	 *
	 * @param mixed $price
	 * @param string $currency
	 *
	 * @return mixed
	 */
	public static function price_normalize( $price, $currency ) {
		if ( self::is_currency_using_decimals( $currency ) ) {
			return number_format( $price / 100, 2, wc_get_price_decimal_separator(), '' );
		}

		return $price;
	}

	/**
	 * @param $price
	 * @param $currency
	 *
	 * @return string
	 */
	public static function price_multiplied_to_float( $price, $currency ) {
		if ( self::is_currency_using_decimals( $currency ) ) {
			return number_format( $price / 100, 2, '.', '' );
		}

		return $price;
	}

	/**
	 * Multiplies a custom formatted price based on the WooCommerce decimal- and thousand separators
	 *
	 * @param $price
	 * @param $currency
	 *
	 * @return int
	 */
	public static function price_custom_to_multiplied( $price, $currency ) {
		$decimal_separator  = get_option( 'woocommerce_price_decimal_sep' );
		$thousand_separator = get_option( 'woocommerce_price_thousand_sep' );

		$price = str_replace( [ $thousand_separator, $decimal_separator ], [ '', '.' ], $price );

		return self::price_multiply( $price, $currency );
	}

	/**
	 * price_multiply function.
	 *
	 * Returns the price with no decimals. 10.10 returns as 1010.
	 *
	 * @access public static
	 *
	 * @param $price
	 * @param null $currency
	 *
	 * @return integer
	 */
	public static function price_multiply( $price, $currency = null ) {
		if ( $currency && self::is_currency_using_decimals( $currency ) ) {
			return number_format( $price * 100, 0, '', '' );
		}

		return $price;
	}

	/**
	 * @param $currency
	 *
	 * @return bool
	 */
	public static function is_currency_using_decimals( $currency ) {
		$non_decimal_currencies = [
			'BIF',
			'CLP',
			'DJF',
			'GNF',
			'JPY',
			'KMF',
			'KRW',
			'PYG',
			'RWF',
			'UGX',
			'UYI',
			'VND',
			'VUV',
			'XAF',
			'XOF',
			'XPF',
		];

		return ! in_array( strtoupper( $currency ), $non_decimal_currencies, true );
	}

	/**
	 * enqueue_javascript_backend function.
	 *
	 * @access public static
	 * @return void
	 */
	public static function enqueue_javascript_backend() {
		if ( self::maybe_enqueue_admin_statics() ) {
			wp_enqueue_script( 'quickpay-backend', plugins_url( '/assets/javascript/backend.js', __DIR__ ), [ 'jquery' ], self::static_version() );
			wp_localize_script( 'quickpay-backend', 'quickpayBackend', [
				'ajax_url' => WC_QuickPay_Admin_Ajax::get_instance()->get_base_url()
			] );
		}

		wp_enqueue_script( 'quickpay-backend-notices', plugins_url( '/assets/javascript/backend-notices.js', __DIR__ ), [ 'jquery' ], self::static_version() );
		wp_localize_script( 'quickpay-backend-notices', 'wcqpBackendNotices', [ 'flush' => admin_url( 'admin-ajax.php?action=woocommerce_quickpay_flush_runtime_errors' ) ] );
	}

	/**
	 * @return bool
	 */
	protected static function maybe_enqueue_admin_statics(): bool {
		global $post;
		/**
		 * Enqueue on the settings page for the gateways
		 */
		if ( isset( $_GET['page'], $_GET['tab'], $_GET['section'] ) ) {
			if ( $_GET['page'] === 'wc-settings' && $_GET['tab'] === 'checkout' && array_key_exists( $_GET['section'], array_merge( [ 'quickpay' => null ], WC_QuickPay::get_gateway_instances() ) ) ) {
				return true;
			}
		} /**
		 * Enqueue on the shop order page
		 */
		else if ( WC_QuickPay_Requests_Utils::is_current_admin_screen( WC_QuickPay_Requests_Utils::get_edit_order_screen_id(), WC_QuickPay_Requests_Utils::get_edit_subscription_screen_id() ) ) {
			return true;
		}

		return false;
	}

	public static function static_version(): string {
		return 'wcqp-' . WCQP_VERSION;
	}


	/**
	 * enqueue_stylesheet function.
	 *
	 * @access public static
	 * @return void
	 */
	public static function enqueue_stylesheet() {
		wp_enqueue_style( 'woocommere-quickpay-style', plugins_url( '/assets/stylesheets/woocommerce-quickpay.css', __DIR__ ), [], self::static_version() );
	}


	/**
	 * load_i18n function.
	 *
	 * @access public static
	 * @return void
	 */
	public static function load_i18n() {
		load_plugin_textdomain( 'woo-quickpay', false, dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/' );
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
		$args = [ 'wc-api' => 'WC_QuickPay' ];

		if ( $post_id !== null ) {
			$args['order_post_id'] = $post_id;
		}

		$args = apply_filters( 'woocommerce_quickpay_callback_args', $args, $post_id );

		return apply_filters( 'woocommerce_quickpay_callback_url', add_query_arg( $args, home_url( '/' ) ), $args, $post_id );
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
			'american-express'        => 'americanexpress.svg',
			'anyday'                  => 'anyday.svg',
			'apple-pay'               => 'apple-pay.svg',
			'dankort'                 => 'dankort.svg',
			'diners'                  => 'diners.svg',
			'edankort'                => 'edankort.png',
			'fbg1886'                 => 'forbrugsforeningen.svg',
			'google-pay'              => 'google-pay.svg',
			'jcb'                     => 'jcb.svg',
			'maestro'                 => 'maestro.svg',
			'mastercard'              => 'mastercard.svg',
			'mastercard-debet'        => 'mastercard.svg',
			'mobilepay'               => 'mobilepay.svg',
			'mobilepaysubscriptions'  => 'mobilepay.svg',
			'mobilepay-subscriptions' => 'mobilepay.svg',
			'visa'                    => 'visa.svg',
			'paypal'                  => 'paypal.svg',
			'sofort'                  => 'sofort.svg',
			'viabill'                 => 'viabill.svg',
			'klarna'                  => 'klarna.svg',
			'bank-axess'              => 'bankaxess.svg',
			'unionpay'                => 'unionpay.svg',
			'cirrus'                  => 'cirrus.svg',
			'ideal'                   => 'ideal.svg',
			'vipps'                   => 'vipps.png',
		];

		if ( $payment_type !== null && array_key_exists( trim( $payment_type ), $logos ) ) {
			return WC_QP()->plugin_url( 'assets/images/cards/' . $logos[ $payment_type ] );
		}

		return null;
	}

	/**
	 * Checks if WooCommerce Pre-Orders is active
	 */
	public static function has_preorder_plugin() {
		return class_exists( 'WC_Pre_Orders' );
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
		if ( isset( $url_info['query'] ) && stripos( $url_info['query'], 'wc-api=wc_quickpay' ) !== false ) {
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
		return isset( $_GET['wc-api'] ) && strtolower( $_GET['wc-api'] ) === 'wc_quickpay';
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

	/**
	 * @param $browser
	 *
	 * @return bool
	 */
	public static function is_browser( $browser ): bool {

		if ( ! isset( $_SERVER['HTTP_USER_AGENT'] ) ) {
			return false;
		}

		$u_agent = $_SERVER['HTTP_USER_AGENT'];
		$name    = 'Unknown';

		if ( false !== stripos( $u_agent, "MSIE" ) && false === stripos( $u_agent, "Opera" ) ) {
			$name = "MSIE";
		} elseif ( false !== stripos( $u_agent, "Firefox" ) ) {
			$name = "Firefox";
		} elseif ( false !== stripos( $u_agent, "Chrome" ) ) {
			$name = "Chrome";
		} elseif ( false !== stripos( $u_agent, "Safari" ) ) {
			$name = "Safari";
		} elseif ( false !== stripos( $u_agent, "Opera" ) ) {
			$name = "Opera";
		} elseif ( false !== stripos( $u_agent, "Netscape" ) ) {
			$name = "Netscape";
		}

		return strtolower( $name ) === strtolower( $browser );
	}

	/**
	 * @param $status
	 *
	 * @return bool
	 */
	public static function is_subscription_status( $status ) {
		if ( strpos( 'wc-', $status ) !== 0 ) {
			$status = 'wc-' . $status;
		}

		return array_key_exists( $status, wcs_get_subscription_statuses() );
	}

	/**
	 * Checks if High Performance Order Storage is enabled
	 * @return bool
	 */
	public static function is_HPOS_enabled(): bool {
		return wc_get_container()->get( CustomOrdersTableController::class )->custom_orders_table_usage_is_enabled();
	}

	/**
	 * @param $n - amount of chars
	 *
	 * @return string
	 */
	public static function create_random_string( $n ): string {
		$characters    = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$random_string = '';

		for ( $i = 0; $i < $n; $i ++ ) {
			try {
				$index         = random_int( 0, strlen( $characters ) - 1 );
				$random_string .= $characters[ $index ];
			} catch ( Exception $e ) {
				$random_string = substr( time(), - $n );
			}
		}

		return $random_string;
	}

	/**
	 * @param WC_Payment_Gateway $class
	 *
	 * @return bool
	 */
	public static function is_plugin_gateway( WC_Payment_Gateway $class ) {
		return $class instanceof WC_QuickPay;
	}

	/**
	 * @return array
	 */
	public static function get_plugin_gateways(): array {
		return array_filter( wc()->payment_gateways()->payment_gateways(), static function ( $gateway ) {
			return static::is_plugin_gateway( $gateway );
		} );
	}
}
