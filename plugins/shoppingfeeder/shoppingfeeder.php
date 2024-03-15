<?php

/**
 * Plugin Name: ShoppingFeeder
 * Plugin URI: http://www.shoppingfeeder.com/register?ref=woo_plugin
 * Description: This plugin will seamlessly allow you to integrate your WooCommerce store with ShoppingFeeder. ShoppingFeeder will then let you send your product data to price comparison engines and marketplaces.
 * Version: 1.5.3
 * Author: ShoppingFeeder
 * Author URI: http://www.shoppingfeeder.com
 * License: GPL2
 */
/*  Copyright 2015  ShoppingFeeder (Pty) Ltd  ( email : info@shoppingfeeder.com )

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

include dirname( __FILE__ ) . '/includes/class-sf-authentication.php';
include dirname( __FILE__ ) . '/includes/class-sf-order.php';
include dirname( __FILE__ ) . '/includes/class-sf-product.php';
include dirname( __FILE__ ) . '/includes/class-sf-attribute.php';

// Makes sure the plugin is defined before trying to use it
if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
	require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
}


if ( !function_exists( 'getallheaders' ) ) {
	function getallheaders() {
		$headers = array();
		foreach ( $_SERVER as $name => $value ) {
			if ( substr( $name, 0, 5 ) == 'HTTP_' ) {
				$headers[str_replace( ' ', '-', ucwords( strtolower( str_replace( '_', ' ', substr( $name, 5 ) )) ) )] = $value;
			}
		}
		return $headers;
	}
}

class ShoppingFeeder {
	/**
	 * @var string
	 */
	public $version = '1.5.3';
	const OPTION_GROUP = 'shoppingfeeder-option-group';
	const WEBHOOK_ORDER_URL = 'http://www.shoppingfeeder.com/webhook/woocommerce-orders';

	public function init() {
		add_action( "parse_request", array( &$this, "shoppingfeeder_parse_request" ) );
	}

	public function shoppingfeeder_parse_request( $wp_query ) {
		$type = isset( $wp_query->query_vars['type'] ) ? $wp_query->query_vars['type'] : 'products';
		if ( isset( $wp_query->query_vars['shoppingfeeder'] ) ) {
			//clear anything in the buffer
			@ob_end_clean();

			$page = ( isset( $wp_query->query_vars['page'] ) ) ? $wp_query->query_vars['page'] : null;
			$num_per_page = ( isset( $wp_query->query_vars['num_per_page'] ) ) ? $wp_query->query_vars['num_per_page'] : 1000;
			$order_status = ( isset( $wp_query->query_vars['order_status'] ) ) ? $wp_query->query_vars['order_status'] : 'completed';
			$allow_variants = ( isset( $wp_query->query_vars['allow_variants'] ) ) ? ((intval($wp_query->query_vars['allow_variants']) == 1) ? true : false) : true;
			$request_currency = ( isset( $wp_query->query_vars['currency'] ) ) ? strtoupper($wp_query->query_vars['currency']) : null;

			$base_currency = get_option( 'woocommerce_currency' );
			$price_currency = null;
			$price_currency_rate = null;

			$overrideCurrencyConversion = false;

			$test_plugin_country = 'woocommerce-product-price-based-on-countries/woocommerce-product-price-based-on-countries.php';
			$test_plugin_country_pro = 'woocommerce-product-price-based-on-countries-pro/woocommerce-product-price-based-on-countries-pro.php';

			//do some basic currency conversion with certain plugins
			if ( !is_null( $request_currency ) )
			{
				$price_currency = $request_currency;

				//currency switcher: https://wpcodefactory.com/item/currency-switcher-woocommerce-wordpress-plugin/
				$test_plugin = 'currency-switcher-woocommerce/currency-switcher-woocommerce.php';
				$test_plugin_pro = 'currency-switcher-woocommerce-pro/currency-switcher-woocommerce-pro.php';

				if (
					( in_array( $test_plugin, apply_filters( 'active_plugins', get_option( 'active_plugins', array() ) ) ) ||
						( is_multisite() && array_key_exists( $test_plugin, get_site_option( 'active_sitewide_plugins', array() ) ) ) ) ||
					( in_array( $test_plugin_pro, apply_filters( 'active_plugins', get_option( 'active_plugins', array() ) ) ) ||
						( is_multisite() && array_key_exists( $test_plugin_pro, get_site_option( 'active_sitewide_plugins', array() ) ) ) )
				)
				{
					//force this currency
					$_SESSION[ 'alg_currency' ] = $price_currency;
					/*
					//convert 1 to get rate
					$base_currency = alg_get_current_currency_code();
					$default_currency = get_option( 'woocommerce_currency' );
					//$price_currency_rate = alg_get_product_price_by_currency( 1, strtoupper( $price_currency ) );
					$price_currency_rate = get_option( 'alg_currency_switcher_exchange_rate_' . $base_currency . '_' . $price_currency, false );
					//if we don't have a rate, try it in reverse
					if ($price_currency_rate === false)
					{
						$reverse_price_currency_rate = get_option( 'alg_currency_switcher_exchange_rate_' . $price_currency . '_' . $base_currency, false );
						if ($reverse_price_currency_rate === false)
						{
							$price_currency = null;
							$price_currency_rate = null;
						}
						else
						{
							$price_currency_rate = 1 / $reverse_price_currency_rate;
						}
					}
					*/
				}  elseif (
					( in_array( $test_plugin_country, apply_filters( 'active_plugins', get_option( 'active_plugins', array() ) ) ) ||
						( is_multisite() && array_key_exists( $test_plugin_country, get_site_option( 'active_sitewide_plugins', array() ) ) ) ) ||
					( in_array( $test_plugin_country_pro, apply_filters( 'active_plugins', get_option( 'active_plugins', array() ) ) ) ||
						( is_multisite() && array_key_exists( $test_plugin_country_pro, get_site_option( 'active_sitewide_plugins', array() ) ) ) )
				) {

					if($price_currency == wcpbc_get_base_currency()) {
						$overrideCurrencyConversion = true;
					} else {
						//TODO: Get correct country for the currency
					}

					//if we don't have a currency switcher, just use the default
				} elseif ( $base_currency == $request_currency )
				{
					$price_currency = $base_currency;
					$price_currency_rate = 1;
				}
			} elseif (
				( in_array( $test_plugin_country, apply_filters( 'active_plugins', get_option( 'active_plugins', array() ) ) ) ||
					( is_multisite() && array_key_exists( $test_plugin_country, get_site_option( 'active_sitewide_plugins', array() ) ) ) ) ||
				( in_array( $test_plugin_country_pro, apply_filters( 'active_plugins', get_option( 'active_plugins', array() ) ) ) ||
					( is_multisite() && array_key_exists( $test_plugin_country_pro, get_site_option( 'active_sitewide_plugins', array() ) ) ) )
			) {
				$overrideCurrencyConversion = true;
			}

			if ( $type == 'products' ) {
				$product_id = isset( $wp_query->query_vars['product_id'] ) ? $wp_query->query_vars['product_id'] : null;
				$this->get_products( $page, $num_per_page, $product_id, $price_currency, $price_currency_rate, $base_currency, $allow_variants, $overrideCurrencyConversion );
			}
            elseif ( $type == 'orders' ) {
				$order_id = isset( $wp_query->query_vars['order_id'] ) ? $wp_query->query_vars['order_id'] : null;
				$this->get_orders( $page, $num_per_page, $order_id, $order_status );
			}
            elseif ( $type == 'debug' ) {
				$this->debug();
			}
            elseif ( $type == 'test' ) {
				$this->test();
			}
            elseif ( $type == 'version' ) {
				$this->version();
			}
            elseif ( $type == 'attributes' ) {
				$this->attributes();
			}
		}

		//save the shoppingfeeder cookie
		if ( isset( $wp_query->query_vars['SFDRREF'] ) ) {
			setcookie( 'SFDRREF', $wp_query->query_vars['SFDRREF'], time() + ( 60*60*24*30 ), '/' );
			$_COOKIE['SFDRREF']= $wp_query->query_vars['SFDRREF'];
		}
	}

	public function get_server_info() {
		$headers = getallheaders();

		if ( ! isset( $_SERVER['HTTPS'] ) && ! empty( $_SERVER['HTTP_HTTPS'] ) ) {
			$_SERVER['HTTPS'] = $_SERVER['HTTP_HTTPS'];
		}

		// Support for hosts which don't use HTTPS, and use HTTP_X_FORWARDED_PROTO
		if ( ! isset( $_SERVER['HTTPS'] ) && ! empty( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' ) {
			$_SERVER['HTTPS'] = '1';
		}

		$info = array(
			'protocol' => ( isset( $_SERVER['https'] ) ) ? 'https' : 'http',
			'headers' => $headers,
			'method' => ( isset( $_SERVER['REQUEST_METHOD'] ) ) ? $_SERVER['REQUEST_METHOD'] : null
		);

		return $info;
	}

	/**
	 * Format a unix timestamp or MySQL datetime into an RFC3339 datetime
	 *
	 * @since 2.1
	 * @param int|string $timestamp unix timestamp or MySQL datetime
	 * @param bool $convert_to_utc
	 * @return string RFC3339 datetime
	 */
	public static function format_datetime( $timestamp, $convert_to_utc = false ) {

		if ( $convert_to_utc ) {
			$timezone = new DateTimeZone( wc_timezone_string() );
		} else {
			$timezone = new DateTimeZone( 'UTC' );
		}

		try {

			if ( is_numeric( $timestamp ) ) {
				$date = new DateTime( "@{$timestamp}" );
			} else {
				$date = new DateTime( $timestamp, $timezone );
			}

			// convert to UTC by adjusting the time based on the offset of the site's timezone
			if ( $convert_to_utc ) {
				$date->modify( -1 * $date->getOffset() . ' seconds' );
			}

		} catch ( Exception $e ) {

			$date = new DateTime( '@0' );
		}

		return $date->format( 'Y-m-d\TH:i:s\Z' );
	}

	public function get_products( $page, $num_per_page, $product_id = null, $price_currency = null, $price_currency_rate = null, $base_currency = null, $allow_variants = true, $overrideCurrencyConversion = false )
	{
		$server_info = $this->get_server_info();

		$auth_result = SF_Authentication::auth(
			$server_info['headers'],
			$server_info['protocol'],
			$server_info['method']
		);

		if ( $auth_result === true ) {
			set_time_limit( 0 );

			$product_model = new SF_Product();

			if ( is_null( $product_id ) ) {
				$products = $product_model->get_products( $page, $num_per_page, $price_currency, $price_currency_rate, $allow_variants, $overrideCurrencyConversion );
			} else {
				$products = $product_model->get_products( $page, $num_per_page, $price_currency, $price_currency_rate, $allow_variants, $overrideCurrencyConversion, $product_id );
//                $products = array( $product_model->get_product( $product_id, null, $price_currency, $price_currency_rate, $allow_variants, $overrideCurrencyConversion ) );
			}

			$response_data = array(
				'status' => 'success',
				'data' => array(
					'page' => $page,
					'num_per_page' => $num_per_page,
					'products' => $products,
					'base_currency' => $base_currency,
					'price_currency' => $price_currency,
					'exchange_rate' => $price_currency_rate
				)
			);
		} else {
			$response_data = array(
				'status' => 'fail',
				'data' => array (
					'message' => $auth_result
				)
			);

		}

		header( 'Content-Type: application/json' );
		echo json_encode( $response_data );
		exit();
	}

	public function get_orders( $page, $num_per_page, $order_id = null, $order_status = 'completed' ) {
		$server_info = $this->get_server_info();

		$auth_result = SF_Authentication::auth(
			$server_info['headers'],
			$server_info['protocol'],
			$server_info['method']
		);

		if ( $auth_result === true ) {
			set_time_limit( 0 );

			$order_model = new SF_Order();

			if ( is_null( $order_id ) ) {
				$orders = $order_model->get_orders( $page, $num_per_page, $order_status );
			} else {
				$orders = array( $order_model->get_order( $order_id ) );
			}

			$response_data = array(
				'status' => 'success',
				'data' => array(
					'page' => $page,
					'num_per_page' => $num_per_page,
					'orders' => $orders
				)
			);
		} else {
			$response_data = array(
				'status' => 'fail',
				'data' => array (
					'message' => $auth_result
				)
			);

		}

		header( 'Content-Type: application/json' );
		echo json_encode( $response_data );
		exit();
	}

	public function debug() {
		echo '<b>Server Info:</b><br>'."\n";
		echo '<b>Time :</b>'.date("Y-m-d H:i:s").'<br>'."\n";
		echo '<b>WordPress Version :</b>'.$GLOBALS['wp_version'].'<br>'."\n";
		echo '<b>WooCommerce Version :</b>'.get_option( 'woocommerce_version', null ).'<br><br>'."\n";

		$api_keys = SF_Authentication::get_api_keys();
		echo '<b>API KEY :</b>'.$api_keys['api_key'].'<br>'."\n";

		if ( function_exists( 'getallheaders' ) ) {
			echo 'Function <b>getallheaders</b> <span style="color:green;">exists</span>'."<br>\n";
			try {
				$headers = getallheaders();
				echo 'Get headers succeeded: '.print_r( $headers,true )."<br>\n";
			} catch ( Exception $e ) {
				echo 'Get headers failed: ['.$e->getMessage().']'."<br>\n";
			}
		} else {
			try {
				function getallheaders() {
					$headers = array();
					foreach ( $_SERVER as $name => $value ) {
						if ( substr( $name, 0, 5 ) == 'HTTP_' ) {
							$headers[str_replace( ' ', '-', ucwords( strtolower( str_replace( '_', ' ', substr( $name, 5 ) ) ) ) )] = $value;
						}
					}
					return $headers;
				}
				echo 'Function <b>getallheaders</b> created'."<br>\n";

				try {
					$headers = getallheaders();
					echo 'Get headers succeeded: '.print_r( $headers,true )."<br>\n";
				} catch ( Exception $e ) {
					echo 'Get headers failed: ['.$e->getMessage().']'."<br>\n";
				}
			} catch ( Exception $e ) {
				echo 'Function <b>getallheaders</b> could not be created'."<br>\n";
			}
		}

		if ( function_exists( 'hash_hmac' ) ) {
			echo 'Function <b>hash_hmac</b> <span style="color:green;">exists</span>'."<br>\n";
		} else {
			echo 'Function <b>hash_hmac</b> <span style="color:red;">does not exist</span>'."<br>\n";
		}

		if ( function_exists( 'mhash' ) ) {
			echo 'Function <b>mhash</b> <span style="color:green;">exists</span>'."<br>\n";
		} else {
			echo 'Function <b>mhash</b> <span style="color:red;">does not exist</span>'."<br>\n";
		}

		$authModel = true;

		if ( isset( $authModel ) ) {
			try {
				$api_keys = SF_Authentication::get_api_keys();
				//hide from view
				unset( $api_keys['api_secret'] );
				echo 'API Keys successfully fetched: '.print_r( $api_keys, true )."<br>\n";

				try {
					$headers = getallheaders();

					$server_info = $this->get_server_info();

					$auth_result = SF_Authentication::auth(
						$server_info['headers'],
						$server_info['protocol'],
						$server_info['method']
					);

					echo '$auth_result successfully called: '.print_r( $auth_result,true )."<br>\n";
				} catch ( Exception $e ) {
					echo '$auth_result could not be called: ['.$e->getMessage().']'."<br>\n";
				}
			} catch ( Exception $e ) {
				echo 'API Keys could not be fetched: ['.$e->getMessage().']'."<br>\n";
			}
		}

		$product_model = false;
		try {
			$product_model = new SF_Product();
			echo '$product_model <span style="color:green;">successfully instantiated</span>'."<br>\n";
		} catch ( Exception $e ) {
			echo '$product_model <span style="color:red;">could not</span> be instantiated: ['.$e->getMessage().']'."<br>\n";

		}

		$page = 1;
		$num_per_page = 1;
		$products = false;
		if ( $product_model ) {
			try {
				$page = 1;
				$num_per_page = 1;

				$products = $product_model->get_products( $page, $num_per_page );

				echo 'Fetch 1 product successful'."<br>\n";
			} catch ( Exception $e ) {
				echo 'Fetch 1 product <span style="color:red;">NOT</span> successful: ['.$e->getMessage().']'."<br>\n";
			}
		}

		if ( $products ) {
			try {
				$response_data = array(
					'status' => 'success',
					'data' => array(
						'page' => $page,
						'num_per_page' => $num_per_page,
						'offers' => $products
					)
				);

				echo json_encode( $response_data );
			} catch ( Exception $e ) {
				echo 'Could not output JSON: ['.$e->getMessage().']'."<br>\n";
			}
		}

		exit();
	}

	public function test() {
		if ( !function_exists( 'getallheaders' ) ) {
			function getallheaders() {
				$headers = array();
				foreach ( $_SERVER as $name => $value ) {
					if ( substr( $name, 0, 5 ) == 'HTTP_' ) {
						$headers[str_replace( ' ', '-', ucwords( strtolower( str_replace( '_', ' ', substr( $name, 5 ) ) ) ) )] = $value;
					}
				}
				return $headers;
			}
		}

		$requiresSsl = false;

		try {
			$api_keys = SF_Authentication::get_api_keys();

			if ( !isset( $api_keys['api_key'] ) || empty( $api_keys['api_key'] ) ) {
				throw new Exception( 'API key not setup in store plugin. Please go to the previous screen, copy the key and add it to the ShoppingFeeder WordPress plugin.' );
			}

			if ( !isset( $api_keys['api_secret'] ) || empty( $api_keys['api_secret'] ) ) {
				throw new Exception( 'API secret not setup in store plugin. Please go to the previous screen, copy the key and add it to the ShoppingFeeder WordPress plugin.' );
			}

			$headers = getallheaders();

			$server_info = $this->get_server_info();

			$auth_result = SF_Authentication::auth(
				$server_info['headers'],
				$server_info['protocol'],
				$server_info['method']
			);

			if ( $auth_result === true ) {
				set_time_limit( 0 );

				$response_data = array(
					'status' => 'success',
					'data' => array(
						'message' => 'Authorization OK'
					)
				);
			} else {
				$response_data = array(
					'status' => 'fail',
					'data' => array (
						'message' => 'Authorization failed: ['.$auth_result.']'
					)
				);
			}
		}
		catch ( Exception $e ) {
			$response_data = array(
				'status' => 'fail',
				'data' => array (
					'message' => $e->getMessage()
				)
			);
		}

		header( 'Content-type: application/json; charset=UTF-8' );
		echo json_encode( $response_data );
		exit();
	}

	public function version() {
		$response_data = array(
			'status' => 'success',
			'data' => array(
				'version' => $this->version
			)
		);

		header( 'Content-type: application/json; charset=UTF-8' );
		echo json_encode( $response_data );
		exit();
	}

	public function attributes() {
		$server_info = $this->get_server_info();

		$auth_result = SF_Authentication::auth(
			$server_info['headers'],
			$server_info['protocol'],
			$server_info['method']
		);

		if ( $auth_result === true ) {
			set_time_limit( 0 );

			$attribute_model = new SF_Attribute();

			$attributes = $attribute_model->get_attributes();

			$response_data = array(
				'status' => 'success',
				'data' => array(
					'attributes' => $attributes
				)
			);
		} else {
			$response_data = array(
				'status' => 'fail',
				'data' => array (
					'message' => $auth_result
				)
			);

		}

		header( 'Content-type: application/json; charset=UTF-8' );
		echo json_encode( $response_data );
		exit();
	}
}


/**
 * Check if WooCommerce is active
 **/
if ( ( is_multisite() && is_plugin_active_for_network( plugin_basename( 'woocommerce/woocommerce.php' ) ) ) ||
	in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

	function shoppingfeeder_init() {
		$sf = new ShoppingFeeder();
		$sf->init();
	}

	add_action( 'init', 'shoppingfeeder_init' );

	function shoppingfeeder_query_vars( $vars ) {
		$vars[] = 'SFDRREF';
		$vars[] = 'page';
		$vars[] = 'num_per_page';
		$vars[] = 'type';
		$vars[] = 'product_id';
		$vars[] = 'order_id';
		$vars[] = 'order_status';
		$vars[] = 'allow_variants';
		$vars[] = 'currency';
		$vars[] = 'shoppingfeeder'; //to make sure we can call our relevant functions
		return $vars;
	}
	add_filter( 'query_vars', 'shoppingfeeder_query_vars' );



	/**** Admin ****/

	function register_shoppingfeeder_settings() {
		register_setting( ShoppingFeeder::OPTION_GROUP, 'shoppingfeeder_api_key', 'shoppingfeeder_validate_api_key' );
		register_setting( ShoppingFeeder::OPTION_GROUP, 'shoppingfeeder_api_secret', 'shoppingfeeder_validate_api_secret' );
	}
	add_action( 'admin_init', 'register_shoppingfeeder_settings' );

	add_action( 'admin_menu', 'shoppingfeeder_menu' );

	function shoppingfeeder_menu() {
		add_submenu_page( 'woocommerce', 'ShoppingFeeder Options', 'ShoppingFeeder', 'manage_options', 'shoppingfeeder', 'shoppingfeeder_options' );
	}

	function shoppingfeeder_options() {

		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}

		include_once 'options-head.php';

		//get API key value from admin settings
		$api_keys = SF_Authentication::get_api_keys();

		echo '<div class="wrap">';
		echo '<form method="post" action="options.php">';
		?>
        <table class="form-table">
            <tr valign="top">
                <th scope="row">ShoppingFeeder API Key</th>
                <td><input type="text" name="shoppingfeeder_api_key" value="<?php echo esc_attr( $api_keys['api_key'] ); ?>" /></td>
            </tr>

            <tr valign="top">
                <th scope="row">ShoppingFeeder API Secret</th>
                <td><input type="text" name="shoppingfeeder_api_secret" value="<?php echo esc_attr( $api_keys['api_secret'] ); ?>" /></td>
            </tr>
        </table>
		<?php
		settings_fields( ShoppingFeeder::OPTION_GROUP );
		do_settings_sections( ShoppingFeeder::OPTION_GROUP );
		submit_button();
		echo '</form>';
		echo '</div>';
	}

	function shoppingfeeder_validate_api_key( $input ) {
		//add_setting_error: $title, $id, $error_message, $class
		if ( empty( $input ) )
		{
			add_settings_error( 'shoppingfeeder_api_key', 'texterror', 'Incorrect value for API Key - please make sure you enter a valid API Key.', 'error' );
		}
		return $input;
	}

	function shoppingfeeder_validate_api_secret( $input ) {
		//add_setting_error: $title, $id, $error_message, $class
		if ( empty( $input ) ) {
			add_settings_error( 'shoppingfeeder_api_secret', 'texterror', 'Incorrect value for API Secret - please make sure you enter a valid API Secret.', 'error' );
		}
		return $input;
	}

	//payment complete hook

	function shoppingfeeder_woocommerce_checkout_order_processed( $order_id ) {

		try {
			$sfdrCookieName = '_sfdr_ref_ptcid';
			$gclidCookieName = '_sfdr_ref_gclid';

			//get API key value from admin settings
			$api_keys = SF_Authentication::get_api_keys();

			$order_model = new SF_Order();
			$data = array();
			$data['order'] = $order_model->get_order( $order_id );

			//get SFDRREF from DB
			$data['order']['landing_site_ref'] = isset( $_COOKIE['SFDRREF'] ) ? $_COOKIE['SFDRREF'] : '';
			//check for Parallel tracking ID
			if ( isset( $_COOKIE[$gclidCookieName] ) )
			{
				$data['order']['landing_site_ref'] = 'SFDR_PT__' . $_COOKIE[$gclidCookieName];
			}

			$response = wp_remote_post( ShoppingFeeder::WEBHOOK_ORDER_URL, array(
					'method' => 'POST',
					'timeout' => 5,
					'redirection' => 5,
					'httpversion' => '1.0',
					'blocking' => false,
					'headers' => array( 'X-SFApiKey' => $api_keys['api_key'] ),
					'body' => json_encode( $data ),
					'cookies' => array()
				)
			);
		}
		catch ( Exception $e ) {
			var_dump( $e->getMessage() );
		}
	}
	add_action( 'woocommerce_checkout_order_processed', 'shoppingfeeder_woocommerce_checkout_order_processed' );

	function shoppingfeeder_add_settings_link( $links ) {
		$settings_link = '<a href="'.admin_url( 'admin.php?page=shoppingfeeder' ).'">' . __( 'Settings' ) . '</a>';
		array_unshift( $links, $settings_link );
		return $links;
	}

	$plugin = plugin_basename( __FILE__ );

	add_filter( "plugin_action_links_$plugin", 'shoppingfeeder_add_settings_link' );

	add_action( 'wp_head', 'shoppingfeeder_inject_js' );

	function shoppingfeeder_inject_js() {
		if ( is_product() ) {
			//get API key value from admin settings
			$api_keys = SF_Authentication::get_api_keys();

			echo '<script src="https://sfdr.co/sfdr.js" data-api-key="' . $api_keys['api_key'] . '" async></script>';
		}
	};
}
