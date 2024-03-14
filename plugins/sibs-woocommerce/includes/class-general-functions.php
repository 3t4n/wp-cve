<?php


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Prevent direct access
}


class Sibs_General_Functions {

	public static $percent = 100;


	public static function sibs_get_plugin_url() {

		return untrailingslashit( plugins_url( '/', SIBS_PLUGIN_FILE ) );
	}

	
	public static function sibs_get_shop_language() {

		return ( substr( get_bloginfo( 'language' ), 0, 2 ) === 'de' ) ? 'de' : 'en';
	}

	public static function sibs_get_customer_ip() {

		$server_remote_addr = WC_Geolocation::get_ip_address();

		if ( '::1' === $server_remote_addr ) {
			$customer_ip = '127.0.0.1';
		} else {
			$customer_ip = $server_remote_addr;
		}

		//truncate
		$customer_ip = substr($customer_ip, 0, 35);

		return $customer_ip;      
	}


	public static function sibs_get_payment_gateway_variable( $payment_id ) {

		$payment_gateway                  = array();
		$payment_gateway['payment_type']  = self::sibs_get_payment_type( $payment_id );
		$payment_gateway['payment_brand'] = self::sibs_get_payment_brand( $payment_id );
		$payment_gateway['payment_group'] = self::sibs_get_payment_group( $payment_id );
		$payment_gateway['is_recurring']  = self::sibs_is_recurring( $payment_id );
		$payment_gateway['language']      = self::sibs_get_shop_language();

		return $payment_gateway;
	}


	public static function sibs_get_credentials( $payment_id, $is_testmode_available = true, $is_multichannel_available = false ) {
		$payment_settings = get_option( 'woocommerce_' . $payment_id . '_settings' );

		$credentials                = array();
		//$credentials['login']       = get_option( 'sibs_general_login' );
		//$credentials['password']    = get_option( 'sibs_general_password' );
		$credentials['server_mode'] = $payment_settings['server_mode'];

		
		// override general credentials if set in paymentMethod
		/*if(!empty($payment_settings['dpgUser'])) {
			$credentials['login'] = $payment_settings['dpgUser'];
		}*/

		/*if(!empty($payment_settings['dpgPassword'])) {
			$credentials['password'] = $payment_settings['dpgPassword'];
		}*/

		
		if ( ! empty( $payment_settings['multichannel'] ) && $is_multichannel_available ) {
			$credentials['channel_id'] = $payment_settings['channel_moto'];
		} else {
			$credentials['channel_id'] = $payment_settings['channel_id'];
		}


		if ( $is_testmode_available ) {
			$credentials['test_mode'] = self::sibs_get_test_mode( $payment_id, $credentials['server_mode'] );
		}

		return $credentials;
	}


	public static function sibs_get_test_mode( $payment_id, $server_mode ) {
		if ( 'LIVE' === $server_mode ) {
			$test_mode = false;
		} else {
			if ( 'sibs_giropay' === $payment_id ) {
				$test_mode = 'INTERNAL';
			} else {
				$test_mode = 'EXTERNAL';
			}
		}
		return $test_mode;
	}

	
	public static function sibs_get_recurring_payment() {
		$payments = array( 'sibs_ccsaved', 'sibs_ddsaved', 'sibs_paypalsaved' );

		return $payments;
	}

	
	public static function sibs_get_payment_brand( $payment_id ) {
		switch ( $payment_id ) {
			case 'sibs_cc':
			case 'sibs_ccsaved':
				$payment_settings = get_option( 'woocommerce_' . $payment_id . '_settings' );
				$cards            = $payment_settings['card_types'];
				$payment_brand    = '';
				if ( isset( $cards ) && '' !== $cards ) {
					foreach ( $cards as $card ) {
						$payment_brand .= strtoupper( $card ) . ' ';
					}
				}
				break;
		
			case 'sibs_paypal':
			case 'sibs_paypalsaved':
				$payment_brand = 'PAYPAL';
				break;
			case 'sibs_mbway':
				$payment_brand = 'MBWAY';
				break;
			case 'sibs_multibanco':
				$payment_brand = 'SIBS_MULTIBANCO';
				break;
			default:
				$payment_brand = false;
				break;
		}// End switch().

		return $payment_brand;
	}

	/**
	 * Get Payment Form
	 *
	 * @param string $payment_id payment id.
	 * @return string
	 */
	public static function sibs_get_payment_form( $payment_id ) {

		switch ( $payment_id ) {
			case 'sibs_multibanco':
				$payment_form = 'redirect';
				break;
			case 'sibs_easycredit':
				$payment_form = 'servertoserver';
				break;
			case 'sibs_paypalsaved':
				$payment_form = 'form_paypalsaved';
				break;
			default:
				$payment_form = 'form';
		}

		return $payment_form;
	}

	public static function sibs_get_payment_type( $payment_id ) {

		switch ( $payment_id ) {
			case 'sibs_mbway':
			case 'sibs_ccsaved':
			case 'sibs_dc':
			case 'sibs_dd':
			case 'sibs_ddsaved':
			case 'sibs_paydirekt':
				$payment_settings = get_option( 'woocommerce_' . $payment_id . '_settings' );
				$payment_type     = $payment_settings['trans_mode'];
				break;
			case 'sibs_klarnains':
			case 'sibs_klarnainv':
			case 'sibs_easycredit':
			case 'sibs_cc':
				$payment_settings = get_option( 'woocommerce_' . $payment_id . '_settings' );
				$payment_type     = $payment_settings['trans_mode'];
				break;
			case 'sibs_multibanco':
				$payment_type = 'PA';
				break;
			default:
				$payment_type = 'DB';
		}

		return $payment_type;
	}

	public static function sibs_get_payment_group( $payment_id ) {

		switch ( $payment_id ) {
			case 'sibs_ccsaved':
				$payment_group = 'CC';
				break;
			case 'sibs_ddsaved':
				$payment_group = 'DD';
				break;
			case 'sibs_paypalsaved':
				$payment_group = 'PAYPAL';
				break;
			default:
				$payment_group = false;
		}

		return $payment_group;
	}

	public static function sibs_get_payment_id_by_group( $payment_group ) {

		switch ( $payment_group ) {
			case 'CC':
				$payment_id = 'sibs_ccsaved';
				break;
			case 'DD':
				$payment_id = 'sibs_ddsaved';
				break;
			case 'PAYPAL':
				$payment_id = 'sibs_paypalsaved';
				break;
			default:
				$payment_id = false;
		}

		return $payment_id;
	}


	public static function sibs_is_recurring( $payment_id ) {

		return 
			$payment_id === 'sibs_ccsaved' ||
			$payment_id === 'sibs_ddsaved' ||
			$payment_id === 'sibs_paypalsaved';
	}

	public static function sibs_get_account_by_result( $payment_id, $payment_result ) {

		switch ( $payment_id ) {
			case 'sibs_ccsaved':
				$account_card['holder']        = $payment_result['card']['holder'];
				$account_card['last_4_digits'] = $payment_result['card']['last4Digits'];
				$account_card['expiry_month']  = $payment_result['card']['expiryMonth'];
				$account_card['expiry_year']   = $payment_result['card']['expiryYear'];
				$account_card['email']         = '';
				break;
			case 'sibs_ddsaved':
				$account_card['holder']        = $payment_result['bankAccount']['holder'];
				$account_card['last_4_digits'] = substr( $payment_result['bankAccount']['iban'], -4 );
				$account_card['expiry_month']  = '';
				$account_card['expiry_year']   = '';
				$account_card['email']         = '';
				break;
			case 'sibs_paypalsaved':
				$account_card['holder']        = $payment_result['virtualAccount']['holder'];
				$account_card['last_4_digits'] = '';
				$account_card['expiry_month']  = '';
				$account_card['expiry_year']   = '';
				$account_card['email']         = $payment_result['virtualAccount']['accountId'];
				break;
		}

		return $account_card;

	}

	/**
	 * Get Payment Price with Tax and Discount.
	 *
	 * @param array $cart cart.
	 * @return float
	 */
	public static function sibs_get_payment_price_with_tax_and_discount( $cart ) {
		$is_prices_include_tax = get_option( 'woocommerce_prices_include_tax' );

		$price_with_discount = $cart['data']->price;
		if ( 'no' === $is_prices_include_tax ) {
			$tax                         = $cart['line_tax'] / $cart['quantity'];
			$price_with_discount_and_tax = $cart['data']->price + $tax;
		} else {
			$price_with_discount_and_tax = $cart['data']->price;
		}

		return SibsPaymentCore::sibs_set_number_format( $price_with_discount_and_tax );
	}

	/**
	 * Get Payment Discount from Cart Order.
	 *
	 * @param array $cart cart.
	 * @return float
	 */
	public static function sibs_get_payment_discount_in_percent( $cart ) {
		$product_detail = Sibs_General_Models::sibs_get_db_product_detail( $cart['data']->id );

		$regular_price = $product_detail['_regular_price'];
		$sale_price    = $cart['data']->price;

		if ( $regular_price !== $sale_price ) {
			$discount         = $regular_price - $sale_price;
			$discount_percent = ( $discount / $regular_price ) * self::$percent;

			return SibsPaymentCore::sibs_set_number_format( $discount_percent );
		}

		return false;
	}

	/**
	 * Get Payment Tax from Cart Order.
	 *
	 * @param array $cart cart.
	 * @return float
	 */
	public static function sibs_get_payment_tax_in_percent( $cart ) {
		$is_enable_tax         = get_option( 'woocommerce_calc_taxes' );
		$product_detail        = Sibs_General_Models::sibs_get_db_product_detail( $cart['data']->id );
		$is_enable_tax_product = $product_detail['_tax_status'];

		if ( 'yes' === $is_enable_tax && 'taxable' === $is_enable_tax_product ) {
			$tax_precent = ( $cart['line_tax'] / $cart['line_total'] ) * self::$percent;
			return SibsPaymentCore::sibs_set_number_format( $tax_precent );
		}

		return false;
	}

	/**
	 * Get Payment Tax from Cart Order.
	 *
	 * @param string $gender gender.
	 * @return string
	 */
	public static function sibs_get_initial_gender( $gender ) {
		switch ( $gender ) {
			case 'Male':
				$initial_gender = 'M';
				break;
			case 'Female':
				$initial_gender = 'F';
				break;
			default:
				$initial_gender = '';
				break;
		}

		return $initial_gender;
	}

	/**
	 * Translate Frontend Payment Name.
	 *
	 * @param string $payment_id payment id.
	 * @return string
	 */
	public static function sibs_translate_frontend_payment( $payment_id ) {
		$payment_locale = '';
		switch ( $payment_id ) {
			case 'sibs_cc':
				$payment_locale = __( 'FRONTEND_PM_CC', 'wc-sibs' );
				break;
			case 'sibs_ccsaved':
				$payment_locale = __( 'FRONTEND_PM_CCSAVED', 'wc-sibs' );
				break;
			case 'sibs_dc':
				$payment_locale = __( 'FRONTEND_PM_DC', 'wc-sibs' );
				break;
			case 'sibs_dd':
				$payment_locale = __( 'FRONTEND_PM_DD', 'wc-sibs' );
				break;
			case 'sibs_ddsaved':
				$payment_locale = __( 'FRONTEND_PM_DDSAVED', 'wc-sibs' );
				break;
			case 'sibs_giropay':
				$payment_locale = __( 'FRONTEND_PM_GIROPAY', 'wc-sibs' );
				break;
			case 'sibs_ideal':
				$payment_locale = __( 'FRONTEND_PM_IDEAL', 'wc-sibs' );
				break;
			case 'sibs_klarnains':
				$payment_locale = __( 'FRONTEND_PM_KLARNAINS', 'wc-sibs' );
				break;
			case 'sibs_klarnainv':
				$payment_locale = __( 'FRONTEND_PM_KLARNAINV', 'wc-sibs' );
				break;
			case 'sibs_masterpass':
				$payment_locale = __( 'FRONTEND_PM_MASTERPASS', 'wc-sibs' );
				break;
			case 'sibs_paydirekt':
				$payment_locale = __( 'FRONTEND_PM_PAYDIREKT', 'wc-sibs' );
				break;
			case 'sibs_paypal':
				$payment_locale = __( 'FRONTEND_PM_PAYPAL', 'wc-sibs' );
				break;
			case 'sibs_paypalsaved':
				$payment_locale = __( 'FRONTEND_PM_PAYPALSAVED', 'wc-sibs' );
				break;
			case 'sibs_sofort':
				$payment_locale = __( 'FRONTEND_PM_SOFORT', 'wc-sibs' );
				break;
			case 'sibs_easycredit':
				$payment_locale = __( 'FRONTEND_PM_EASYCREDIT', 'wc-sibs' );
				break;
			case 'sibs_swisspostfinance':
				$payment_locale = __( 'FRONTEND_PM_SWISSPOSTFINANCE', 'wc-sibs' );
				break;
			default:
				$payment_locale = '';
				break;
		}// End switch().

		return $payment_locale;
	}

	/**
	 * Translate Backend Payment Name.
	 *
	 * @param string $payment_id payment id.
	 * @return string
	 */
	public static function sibs_translate_backend_payment( $payment_id ) {

		switch ( $payment_id ) {
			case 'sibs_cc':
				$payment_locale = __( 'BACKEND_PM_CC', 'wc-sibs' );
				break;
			case 'sibs_ccsaved':
				$payment_locale = __( 'BACKEND_PM_CCSAVED', 'wc-sibs' );
				break;
			case 'sibs_dc':
				$payment_locale = __( 'BACKEND_PM_DC', 'wc-sibs' );
				break;
			case 'sibs_dd':
				$payment_locale = __( 'BACKEND_PM_DD', 'wc-sibs' );
				break;
			case 'sibs_ddsaved':
				$payment_locale = __( 'BACKEND_PM_DDSAVED', 'wc-sibs' );
				break;
			case 'sibs_giropay':
				$payment_locale = __( 'BACKEND_PM_GIROPAY', 'wc-sibs' );
				break;
			case 'sibs_ideal':
				$payment_locale = __( 'BACKEND_PM_IDEAL', 'wc-sibs' );
				break;
			case 'sibs_klarnains':
				$payment_locale = __( 'BACKEND_PM_KLARNAINS', 'wc-sibs' );
				break;
			case 'sibs_klarnainv':
				$payment_locale = __( 'BACKEND_PM_KLARNAINV', 'wc-sibs' );
				break;
			case 'sibs_masterpass':
				$payment_locale = __( 'BACKEND_PM_MASTERPASS', 'wc-sibs' );
				break;
			case 'sibs_paydirekt':
				$payment_locale = __( 'BACKEND_PM_PAYDIREKT', 'wc-sibs' );
				break;
			case 'sibs_paypal':
				$payment_locale = __( 'BACKEND_PM_PAYPAL', 'wc-sibs' );
				break;
			case 'sibs_paypalsaved':
				$payment_locale = __( 'BACKEND_PM_PAYPALSAVED', 'wc-sibs' );
				break;
			case 'sibs_sofort':
				$payment_locale = __( 'BACKEND_PM_SOFORT', 'wc-sibs' );
				break;
			case 'sibs_swisspostfinance':
				$payment_locale = __( 'BACKEND_PM_SWISSPOSTFINANCE', 'wc-sibs' );
				break;
			case 'sibs_easycredit':
				$payment_locale = __( 'BACKEND_PM_EASYCREDIT', 'wc-sibs' );
				break;
			case 'sibs_mbway':
				$payment_locale = __( 'BACKEND_PM_MBWAY', 'wc-sibs' );
				break;
			case 'sibs_multibanco':
				$payment_locale = __( 'BACKEND_PM_MULTIBANCO', 'wc-sibs' );
				break;
		}// End switch().

		return $payment_locale;
	}

	/**
	 * Translate Error Identifier.
	 *
	 * @param string $error_identifier error identifier.
	 * @return string
	 */
	public static function sibs_translate_error_identifier( $error_identifier ) {
		switch ( $error_identifier ) {
			case 'ERROR_CC_ACCOUNT':
				$error_translate = __( 'ERROR_CC_ACCOUNT', 'wc-sibs' );
				break;
			case 'ERROR_CC_INVALIDDATA':
				$error_translate = __( 'ERROR_CC_INVALIDDATA', 'wc-sibs' );
				break;
			case 'ERROR_CC_BLACKLIST':
				$error_translate = __( 'ERROR_CC_BLACKLIST', 'wc-sibs' );
				break;
			case 'ERROR_CC_DECLINED_CARD':
				$error_translate = __( 'ERROR_CC_DECLINED_CARD', 'wc-sibs' );
				break;
			case 'ERROR_CC_EXPIRED':
				$error_translate = __( 'ERROR_CC_EXPIRED', 'wc-sibs' );
				break;
			case 'ERROR_CC_INVALIDCVV':
				$error_translate = __( 'ERROR_CC_INVALIDCVV', 'wc-sibs' );
				break;
			case 'ERROR_CC_EXPIRY':
				$error_translate = __( 'ERROR_CC_EXPIRY', 'wc-sibs' );
				break;
			case 'ERROR_CC_LIMIT_EXCEED':
				$error_translate = __( 'ERROR_CC_LIMIT_EXCEED', 'wc-sibs' );
				break;
			case 'ERROR_CC_3DAUTH':
				$error_translate = __( 'ERROR_CC_3DAUTH', 'wc-sibs' );
				break;
			case 'ERROR_CC_3DERROR':
				$error_translate = __( 'ERROR_CC_3DERROR', 'wc-sibs' );
				break;
			case 'ERROR_CC_NOBRAND':
				$error_translate = __( 'ERROR_CC_NOBRAND', 'wc-sibs' );
				break;
			case 'ERROR_GENERAL_LIMIT_AMOUNT':
				$error_translate = __( 'ERROR_GENERAL_LIMIT_AMOUNT', 'wc-sibs' );
				break;
			case 'ERROR_GENERAL_LIMIT_TRANSACTIONS':
				$error_translate = __( 'ERROR_GENERAL_LIMIT_TRANSACTIONS', 'wc-sibs' );
				break;
			case 'ERROR_CC_DECLINED_AUTH':
				$error_translate = __( 'ERROR_CC_DECLINED_AUTH', 'wc-sibs' );
				break;
			case 'ERROR_GENERAL_DECLINED_RISK':
				$error_translate = __( 'ERROR_GENERAL_DECLINED_RISK', 'wc-sibs' );
				break;
			case 'ERROR_CC_ADDRESS':
				$error_translate = __( 'ERROR_CC_ADDRESS', 'wc-sibs' );
				break;
			case 'ERROR_GENERAL_CANCEL':
				$error_translate = __( 'ERROR_GENERAL_CANCEL', 'wc-sibs' );
				break;
			case 'ERROR_CC_RECURRING':
				$error_translate = __( 'ERROR_CC_RECURRING', 'wc-sibs' );
				break;
			case 'ERROR_CC_REPEATED':
				$error_translate = __( 'ERROR_CC_REPEATED', 'wc-sibs' );
				break;
			case 'ERROR_GENERAL_ADDRESS':
				$error_translate = __( 'ERROR_GENERAL_ADDRESS', 'wc-sibs' );
				break;
			case 'ERROR_GENERAL_BLACKLIST':
				$error_translate = __( 'ERROR_GENERAL_BLACKLIST', 'wc-sibs' );
				break;
			case 'ERROR_GENERAL_GENERAL':
				$error_translate = __( 'ERROR_GENERAL_GENERAL', 'wc-sibs' );
				break;
			case 'ERROR_GENERAL_TIMEOUT':
				$error_translate = __( 'ERROR_GENERAL_TIMEOUT', 'wc-sibs' );
				break;
			case 'ERROR_GIRO_NOSUPPORT':
				$error_translate = __( 'ERROR_GIRO_NOSUPPORT', 'wc-sibs' );
				break;
			case 'ERROR_CAPTURE_BACKEND':
				$error_translate = __( 'ERROR_CAPTURE_BACKEND', 'wc-sibs' );
				break;
			case 'ERROR_REORDER_BACKEND':
				$error_translate = __( 'ERROR_REORDER_BACKEND', 'wc-sibs' );
				break;
			case 'ERROR_REFUND_BACKEND':
				$error_translate = __( 'ERROR_REFUND_BACKEND', 'wc-sibs' );
				break;
			case 'ERROR_RECEIPT_BACKEND':
				$error_translate = __( 'ERROR_RECEIPT_BACKEND', 'wc-sibs' );
				break;
			case 'ERROR_ADDRESS_PHONE':
				$error_translate = __( 'ERROR_ADDRESS_PHONE', 'wc-sibs' );
				break;
			case 'ERROR_CAPTURE_BACKEND':
				$error_translate = __( 'ERROR_CAPTURE_BACKEND', 'wc-sibs' );
				break;
			case 'ERROR_REORDER_BACKEND':
				$error_translate = __( 'ERROR_REORDER_BACKEND', 'wc-sibs' );
				break;
			case 'ERROR_REFUND_BACKEND':
				$error_translate = __( 'ERROR_REFUND_BACKEND', 'wc-sibs' );
				break;
			case 'ERROR_RECEIPT_BACKEND':
				$error_translate = __( 'ERROR_RECEIPT_BACKEND', 'wc-sibs' );
				break;
			case 'ERROR_GENERAL_NORESPONSE':
				$error_translate = __( 'ERROR_GENERAL_NORESPONSE', 'wc-sibs' );
				break;
			case 'ERROR_WRONG_DOB':
				$error_translate = __( 'ERROR_WRONG_DOB', 'wc-sibs' );
				break;
			case 'ERROR_EASYCREDIT_PARAMETER_DOB':
				$error_translate = __( 'ERROR_EASYCREDIT_PARAMETER_DOB', 'wc-sibs' );
				break;
			case 'ERROR_MESSAGE_EASYCREDIT_AMOUNT_NOTALLOWED':
				$error_translate = __( 'ERROR_MESSAGE_EASYCREDIT_AMOUNT_NOTALLOWED', 'wc-sibs' );
				break;
			case 'ERROR_EASYCREDIT_FUTURE_DOB':
				$error_translate = __( 'ERROR_EASYCREDIT_FUTURE_DOB', 'wc-sibs' );
				break;
			case 'ERROR_EASYCREDIT_BILLING_NOTEQUAL_SHIPPING':
				$error_translate = __( 'ERROR_EASYCREDIT_BILLING_NOTEQUAL_SHIPPING', 'wc-sibs' );
				break;
			case 'ERROR_PARAMETER_GENDER':
				$error_translate = __( 'ERROR_PARAMETER_GENDER', 'wc-sibs' );
				break;
			case 'ERROR_MESSAGE_EASYCREDIT_PARAMETER_GENDER':
				$error_translate = __( 'ERROR_MESSAGE_EASYCREDIT_PARAMETER_GENDER', 'wc-sibs' );
				break;
			case 'ERROR_GENERAL_REDIRECT':
				$error_translate = __( 'ERROR_GENERAL_REDIRECT', 'wc-sibs' );
				break;
			case 'ERROR_UNKNOWN':
				$error_translate = __( 'ERROR_UNKNOWN', 'wc-sibs' );
				break;
			default:
				$error_translate = __( 'ERROR_UNKNOWN', 'wc-sibs' );
				break;
		}// End switch().

		return $error_translate;
	}

	/**
	 * Translate sibs_term
	 *
	 * @param string $sibs_term term.
	 * @return string
	 */
	public static function sibs_translate_sibs_term( $sibs_term ) {
		switch ( $sibs_term ) {
			case 'SIBS_TT_VERSIONTRACKER':
				$sibs_term_translate = __( 'SIBS_TT_VERSIONTRACKER', 'wc-sibs' );
				break;
			case 'SIBS_BACKEND_BT_ADMIN':
				$sibs_term_translate = __( 'SIBS_BACKEND_BT_ADMIN', 'wc-sibs' );
				break;
		}
		return $sibs_term_translate;
	}

	/**
	 * Translate Additional Information Name
	 *
	 * @param string $info_name info name.
	 * @return string
	 */
	public static function sibs_translate_additional_information( $info_name ) {
		switch ( $info_name ) {
			case 'FRONTEND_EASYCREDIT_INTEREST':
				$additional_info_name = __( 'FRONTEND_EASYCREDIT_INTEREST', 'wc-sibs' );
				break;
			case 'FRONTEND_EASYCREDIT_TOTAL':
				$additional_info_name = __( 'FRONTEND_EASYCREDIT_TOTAL', 'wc-sibs' );
				break;
			default:
				$additional_info_name = $info_name;
				break;
		}

		return $additional_info_name;
	}

	/**
	 * Get _REQUEST value
	 *
	 * @param string $key key.
	 * @param string $default default.
	 * @return value
	 */
	public static function sibs_get_request_value( $key, $default = false ) {
		if ( isset( $_REQUEST[ $key ] ) ) {// input var okay.
			return sanitize_text_field( wp_unslash( $_REQUEST[ $key ] ) ); // input var okay.
		}
		return $default;
	}

	/**
	 * Include template
	 *
	 * @param string $template_file_path template file path (templates/template.php).
	 * @param array  $args variable to include in template.
	 */
	public static function sibs_include_template( $template_file_path, $args = array() ) {
		if ( function_exists( 'wc_get_template' ) ) {
			$template      = pathinfo( $template_file_path );
			$template_path = $template['dirname'] . '/';
			$template_file = $template['basename'];
			wc_get_template(
				$template_file,
				$args,
				$template_path,
				$template_path
			);
		} else {
			foreach ( $args as $key => $value ) {
				$$key = $value;
			}
			include $template_file_path;
		}
	}

	/**
	 * Validate birth of date
	 *
	 * @param  string $dob birth of date.
	 * @return string|boolean
	 */
	public static function sibs_validate_dob( $dob ) {
		$ddob = DateTime::createFromFormat( 'd-m-Y', $dob );
		if ( $ddob && $ddob->format( 'd-m-Y' ) === $dob ) {
			return $dob;
		}
		return false;
	}

	/**
	 * Is woocommerce version greater than
	 *
	 * @param string $version woocommerce version.
	 * @return boolean
	 */
	public static function sibs_is_version_greater_than( $version ) {
		if ( class_exists( 'WooCommerce' ) ) {
			global $woocommerce;
			if ( version_compare( $woocommerce->version, $version, '>=' ) ) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Check if billing address and shipping addres are equal
	 *
	 * @return boolean
	 */
	public static function sibs_is_address_billing_equal_shipping() {
		global $woocommerce;
		$customer                = $woocommerce->customer;
		$billing['street']       = $customer->address_1 . ', ' . $customer->address_2;
		$billing['city']         = $customer->city;
		$billing['zip']          = $customer->postcode;
		$billing['country_code'] = $customer->country;

		$shipping['street']       = $customer->shipping_address_1 . ', ' . $customer->shipping_address_2;
		$shipping['city']         = $customer->shipping_city;
		$shipping['zip']          = $customer->shipping_postcode;
		$shipping['country_code'] = $customer->shipping_country;

		foreach ( $billing as $i => $bill ) {
			if ( $bill !== $shipping[ $i ] ) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Check if billing address country is portugal
	 *
	 * @return boolean
	 */
	public static function sibs_is_billing_country_portugal() {
		global $woocommerce;
		$customer = $woocommerce->customer;


		$obj_array = (Array)$customer ; 
		$changes = $obj_array["\0*\0" . 'changes'];
		$country = $changes['shipping']['country'];
		
		if ( $country == null ){
			$country = $customer->country;
		}
		
		if ( 'PT' === $country ) {
			return true;
		}

		return false;
	}


	/**
	 * Get customer order count
	 *
	 * @return int count
	 */
	public static function sibs_get_order_count() {
		if ( wc_get_customer_order_count( get_current_user_id() ) > 0 ) {
			return wc_get_customer_order_count( get_current_user_id() );
		}
		return 0;
	}

	/**
	 * Get risk kunden status for easyCredit
	 *
	 * @return string risk kunden
	 */
	public static function sibs_get_risk_kunden_status() {
		if ( self::sibs_get_order_count() > 0 ) {
			return 'BESTANDSKUNDE';
		}
		return 'NEUKUNDE';
	}

	/**
	 * Get customer created date
	 *
	 * @return string|boolean created date
	 */
	public static function sibs_get_customer_created_date() {
		$user_data    = get_userdata( get_current_user_id() );
		$created_date = strtotime( $user_data->user_registered );

		if ( isset( $user_data->user_registered ) && $created_date > 0 ) {
			return date( 'Y-m-d', $created_date );
		}
		return date( 'Y-m-d' );
	}
}
