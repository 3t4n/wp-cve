<?php
/**
 * Skrill Payment Core
 *
 * This Class for Proccess Skrill Payment Gateways
 * Copyright (c) Skrill
 *
 * @class   Skrill_Payment
 * @package Skrill/Classes
 * @located at  /includes/core/
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Skrill_Payment
 */
class Skrill_Payment {

	/**
	 * Prepare url
	 *
	 * @var string
	 */
	protected static $prepare_url = 'https://pay.skrill.com';

	/**
	 * Query url
	 *
	 * @var string
	 */
	protected static $query_url = 'https://www.skrill.com/app/query.pl';

	/**
	 * Refund url
	 *
	 * @var string
	 */
	protected static $refund_url = 'https://www.skrill.com/app/refund.pl';


	/**
	 * Version control api url
	 *
	 * @var string
	 */
	protected static $skrill_version_controll_api_url = 'http://81.169.251.23:9097/sendVersion';

	/**
	 * Status url parameters
	 *
	 * @var array
	 */
	protected static $status_url_parameters = array(
		'pay_to_email',
		'pay_from_email',
		'merchant_id',
		'customer_id',
		'transaction_id',
		'mb_transaction_id',
		'mb_amount',
		'mb_currency',
		'status',
		'failed_reason_code',
		'md5sig',
		'sha2sig',
		'amount',
		'currency',
		'neteller_id',
		'payment_type',
		'payment_instrument_country',
		'country',
		'IP_country',
		'platform',
		'order_id',
		'secure_payment',
		'rec_payment_type',
		'rec_payment_id',
		'payment_method_id',
	);

	/**
	 * Refund status url parameters
	 *
	 * @var array
	 */
	protected static $refund_status_url_parameters = array(
		'transaction_id',
		'mb_transaction_id',
		'status',
		'mb_amount',
		'mb_currency',
		'md5sig',
		'sha2sig',
		'order_id',
	);

	/**
	 * Skrill Payment Url
	 *
	 * @param  array $checkout_parameters - checkout parameters.
	 * @return string|boolean
	 */
	public static function get_payment_url_by_checkout_parameters( $checkout_parameters ) {
		$url         = self::$prepare_url;
		$post_result = self::post( $url, $checkout_parameters );

		if ( ! empty( $post_result ) ) {
			return $url . '?sid=' . $post_result;
		}

		return false;
	}


	/**
	 * Send shop version to version controll api
	 *
	 * @param  array $version_controll_parameters - version controll parameters.
	 * @return string|boolean
	 */
	public static function send_to_version_controll( $version_controll_parameters ) {
		$url         = self::$skrill_version_controll_api_url;
		$post_result = self::post_version_controll( $url, $version_controll_parameters );

		if ( ! empty( $post_result ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Skrill Query Check
	 *
	 * @param  array $payment_parameters - payment parameters.
	 * @param  array $payment_result     - payment result.
	 * @return string|array
	 */
	public static function get_status_trn( $payment_parameters, &$payment_result ) {
		// check status_trn 3 times if no response.
		for ( $i = 0; $i < 3; $i++ ) {
			$response = true;
			try {
				$payment_result = self::get_payment_result( $payment_parameters );
			} catch ( Exception $e ) {
				$response = false;
			}
			if ( $response && $payment_result ) {
				$result_code = (int) substr( $payment_result, 0, 3 );
				if ( 401 === $result_code ) {
					if ( strpos( $payment_result, 'Cannot login' ) !== false ) {
						return 'CANNOT_LOGIN';
					} elseif ( strpos( $payment_result, 'Your account is currently locked' ) !== false ) {
						return 'ACCOUNT_LOCKED';
					}
					return 'GENERAL_ERROR';
				}
				$payment_result = self::set_payment_result_to_array( $payment_result );
				return 'SUCCESS';
			}
		}
		return 'GENERAL_ERROR';
	}


	/**
	 * Cancel recurring payment
	 *
	 * @param  array $payment_parameters - payment parameters.
	 * @param  array $payment_result     - payment result.
	 * @return string|array
	 */
	public static function cancel_recurring_payment( $payment_parameters, &$payment_result ) {
		// check status_trn 3 times if no response.
		for ( $i = 0; $i < 3; $i++ ) {
			$response = true;
			try {
				$payment_result = self::get_payment_result( $payment_parameters );
			} catch ( Exception $e ) {
				$response = false;
			}
			if ( $response && $payment_result ) {
				$result_code = (int) substr( $payment_result, 0, 3 );
				if ( 401 === $result_code ) {
					if ( strpos( $payment_result, 'Cannot login' ) !== false ) {
						return 'CANNOT_LOGIN';
					} elseif ( strpos( $payment_result, 'Your account is currently locked' ) !== false ) {
						return 'ACCOUNT_LOCKED';
					}
					return 'GENERAL_ERROR';
				}
				$payment_result = self::set_payment_result_to_array( $payment_result );
				return 'SUCCESS';
			}
		}
		return 'GENERAL_ERROR';
	}

	/**
	 * Skrill Query
	 *
	 * @param  array $payment_parameters - payment parameters.
	 * @return string
	 */
	public static function get_payment_result( $payment_parameters ) {
		$query_url   = self::$query_url;
		$post_result = self::post( $query_url, $payment_parameters );

		return $post_result;
	}

	/**
	 * Skrill Refund
	 *
	 * @param  array $transaction_parameters - transaction parameters.
	 * @return xml|string
	 */
	public static function do_refund( $transaction_parameters ) {
		$refund_url = self::$refund_url;

		$prepare_parameters           = $transaction_parameters;
		$prepare_parameters['action'] = 'prepare';
		$post_result                  = self::post( $refund_url, $prepare_parameters );
		$prepare_result               = simplexml_load_string( $post_result );

		$error = (string) $prepare_result->error->error_msg;

		if ( ! empty( $error ) ) {
			if ( 'CANNOT_LOGIN' === $error ) {
				return $error;
			}
			if ( false !== strpos( $error, 'LOCK' ) ) {
				return 'ACCOUNT_LOCKED';
			}
			return 'GENERAL_ERROR';
		}

		$sid = (string) $prepare_result->sid;

		if ( ! empty( $sid ) ) {
			$refund_parameters['action'] = 'refund';
			$refund_parameters['sid']    = $sid;
			$post_result                 = self::post( $refund_url, $refund_parameters );
			return simplexml_load_string( $post_result );
		}

		return 'GENERAL_ERROR';
	}

	/**
	 * Post to Skrill Gateway
	 *
	 * @param  string $url             - url.
	 * @param  array  $post_parameters - post parameters.
	 * @return boolean|string
	 */
	protected static function post( $url, $post_parameters ) {
		$response = wp_remote_post(
			$url,
			array(
				'headers'    => array(
					'content-type' => 'application/x-www-form-urlencoded',
					'charset'      => 'UTF-8',
				),
				'user-agent' => false,
				'body'       => $post_parameters,
				'timeout'    => 100,
			)
		);

		if ( is_wp_error( $response ) ) {
			return false;
		}

		$response = wp_remote_retrieve_body( $response );

		return $response;
	}

	/**
	 * Post to Skrill Gateway
	 *
	 * @param  string $url             - url.
	 * @param  array  $post_parameters - post parameters.
	 * @return boolean|string
	 */
	protected static function post_version_controll( $url, $post_parameters ) {
		$response = wp_remote_post(
			$url,
			array(
				'headers'    => array(
					'content-type' => 'application/json',
					'charset'      => 'UTF-8',
				),
				'user-agent' => false,
				'body'       => $post_parameters,
				'timeout'    => 100,
			)
		);

		if ( is_wp_error( $response ) ) {
			return false;
		}

		$response = wp_remote_retrieve_body( $response );

		return $response;
	}

	/**
	 * Skrill Result in Array
	 *
	 * @param  string $payment_result - payment result.
	 * @return array
	 */
	public static function set_payment_result_to_array( $payment_result ) {
		$payment_result_in_array                    = array();
		$result_string                              = explode( "\n", $payment_result );
		$payment_result_in_array['response_header'] = $result_string[0];
		$results                                    = explode( '&', $result_string[1] );
		foreach ( $results as $result ) {
			$result_value = explode( '=', $result );
			$payment_result_in_array[ urldecode( $result_value[0] ) ] = urldecode( $result_value[1] );
		}
		return $payment_result_in_array;
	}

	/**
	 * Check Payment Fraud
	 *
	 * @param  array $currency        - currency.
	 * @param  array $order_id        - order id.
	 * @param  array $transaction_id  - transaction id.
	 * @param  array $anti_fraud_hash - anti fraud hash.
	 * @return boolean
	 */
	public static function is_fraud( $currency, $order_id, $transaction_id, $anti_fraud_hash ) {
		return ! ( self::generate_anti_fraud_hash( $currency, $order_id, $transaction_id ) === $anti_fraud_hash );
	}

	/**
	 * Generate anti fraud hash
	 *
	 * @param  array $currency         - currency.
	 * @param  array $order_id       - order id.
	 * @param  array $transaction_id - transaction id.
	 * @return boolean
	 */
	public static function generate_anti_fraud_hash( $currency, $order_id, $transaction_id ) {
		return md5( $currency . $order_id . $transaction_id . 'skrill' );
	}

	/**
	 * Check if payment signature equals to generated signature
	 *
	 * @param  string $payment_signature   md5 signature from payment response.
	 * @param  string $generated_signature generated md5 signature from payment response and credential.
	 * @return boolean
	 */
	public static function is_payment_signature_equals_generated_signature( $payment_signature, $generated_signature ) {
		return $payment_signature === $generated_signature;
	}

	/**
	 * Generate Md5sig
	 *
	 * @param  array $skrill_settings - skrill settings.
	 * @param  array $payment_result  - payment result.
	 * @return string
	 */
	public static function generate_md5_sig( $skrill_settings, $payment_result ) {
		$merchant_id    = $skrill_settings['merchant_id'];
		$secret_word    = strtoupper( $skrill_settings['secret_word'] );
		$transaction_id = $payment_result['transaction_id'];
		$mb_amount      = $payment_result['mb_amount'];
		$mb_currency    = $payment_result['mb_currency'];
		$status         = $payment_result['status'];
		$string         = $merchant_id . $transaction_id . $secret_word . $mb_amount . $mb_currency . $status;

		return strtoupper( md5( $string ) );
	}

	/**
	 * Set number Format
	 *
	 * @param  string|float $number - number.
	 * @return string
	 */
	public static function set_number_format( $number ) {
		$number = (float) str_replace( ',', '.', $number );
		return number_format( $number, 2, '.', '' );
	}

	/**
	 * Get current date time (Y-m-d H:i:s)
	 *
	 * @return string
	 */
	public static function get_current_datetime() {
		$t     = microtime( true );
		$micro = sprintf( '%06d', ( $t - floor( $t ) ) * 1000000 );
		$d     = new DateTime( gmdate( 'Y-m-d H:i:s.' . $micro, $t ) );

		return $d->format( 'ymdhiu' );
	}

	/**
	 * Get Random Number
	 *
	 * @param  int $length - length.
	 * @return string
	 */
	public static function get_random_number( $length ) {
		$result = '';

		for ( $i = 0; $i < $length; $i++ ) {
			$result .= wp_rand( 0, 9 );
		}

		return $result;
	}

	/**
	 * Get Locale Identifier Transaction Status
	 *
	 * @param  string $code - code.
	 * @return string
	 */
	public static function get_transaction_status( $code ) {
		switch ( $code ) {
			case '2':
				$status = 'Processed';
				break;
			case '0':
				$status = 'Pending';
				break;
			case '-1':
				$status = 'Cancelled';
				break;
			case '-2':
				$status = 'Failed';
				break;
			case '-3':
				$status = 'Chargeback';
				break;
			case '-4':
				$status = 'Refund successfull';
				break;
			case '-5':
				$status = 'Refund failed';
				break;
			case '-6':
				$status = 'Refund pending';
				break;
			case '-7':
				$status = 'Not Verified';
				break;
			case '-8':
				$status = 'was considered fraudulent';
				break;
			default:
				$status = 'Abandoned by user';
				break;
		}
		return $status;
	}

	/**
	 * Get Locale Identifier Skrill Error
	 *
	 * @param  string $code - code.
	 * @return string
	 */
	public static function get_skrill_error_mapping( $code ) {
		$error_messages = array(
			'01' => 'Referred by card issuer',
			'02' => 'Invalid Merchant',
			'03' => 'Stolen card',
			'04' => "Declined by customer's Card Issuer",
			'05' => 'Insufficient funds',
			'08' => 'PIN tries exceeded - card blocked',
			'09' => 'Invalid Transaction',
			'10' => 'Transaction frequency limit exceeded',
			'12' => 'Invalid credit card or bank account',
			'15' => 'Duplicate transaction',
			'19' => 'Unknown failure reason. Try again',
			'24' => 'Card expired',
			'28' => 'Lost/Stolen card',
			'32' => 'Card Security Code check failed',
			'37' => 'Card restricted by card issuer',
			'38' => 'Security violation',
			'42' => 'Card blocked by card issuer',
			'44' => "Customer's issuing bank not available",
			'51' => 'Processing system error',
			'63' => 'Transaction not permitted to cardholder',
			'70' => 'Customer failed to complete 3DS',
			'71' => 'Customer failed SMS verification',
			'80' => 'Fraud engine declined',
			'98' => 'Error in communication with provider',
			'99' => 'Failure reason not specified',
		);

		if ( $code ) {
			return array_key_exists( $code, $error_messages ) ? $error_messages[ $code ] : 'Failure reason not specified';
		}
		return 'Failure reason not specified';
	}

	/**
	 * Get Country ISO-3 by ISO-2
	 *
	 * @param  string $iso2 - ISO-2.
	 * @return string
	 */
	public static function get_country_iso3_by_iso2( $iso2 ) {
		$iso3 = array(
			'AF' => 'AFG',
			'AL' => 'ALB',
			'DZ' => 'DZA',
			'AS' => 'ASM',
			'AD' => 'AND',
			'AO' => 'AGO',
			'AI' => 'AIA',
			'AQ' => 'ATA',
			'AG' => 'ATG',
			'AR' => 'ARG',
			'AM' => 'ARM',
			'AW' => 'ABW',
			'AU' => 'AUS',
			'AT' => 'AUT',
			'AZ' => 'AZE',
			'BS' => 'BHS',
			'BH' => 'BHR',
			'BD' => 'BGD',
			'BB' => 'BRB',
			'BY' => 'BLR',
			'BE' => 'BEL',
			'BZ' => 'BLZ',
			'BJ' => 'BEN',
			'BM' => 'BMU',
			'BT' => 'BTN',
			'BO' => 'BOL',
			'BA' => 'BIH',
			'BW' => 'BWA',
			'BV' => 'BVT',
			'BR' => 'BRA',
			'IO' => 'IOT',
			'VG' => 'VGB',
			'BN' => 'BRN',
			'BG' => 'BGR',
			'BF' => 'BFA',
			'BI' => 'BDI',
			'KH' => 'KHM',
			'CM' => 'CMR',
			'CA' => 'CAN',
			'CV' => 'CPV',
			'KY' => 'CYM',
			'CF' => 'CAF',
			'TD' => 'TCD',
			'CL' => 'CHL',
			'CN' => 'CHN',
			'CX' => 'CXR',
			'CC' => 'CCK',
			'CO' => 'COL',
			'KM' => 'COM',
			'CG' => 'COG',
			'CD' => 'COD',
			'CK' => 'COK',
			'CR' => 'CRI',
			'HR' => 'HRV',
			'CU' => 'CUB',
			'CY' => 'CYP',
			'CZ' => 'CZE',
			'CI' => 'CIV',
			'DK' => 'DNK',
			'DJ' => 'DJI',
			'DM' => 'DMA',
			'DO' => 'DOM',
			'EC' => 'ECU',
			'EG' => 'EGY',
			'SV' => 'SLV',
			'GQ' => 'GNQ',
			'ER' => 'ERI',
			'EE' => 'EST',
			'ET' => 'ETH',
			'FK' => 'FLK',
			'FO' => 'FRO',
			'FJ' => 'FJI',
			'FI' => 'FIN',
			'FR' => 'FRA',
			'GF' => 'GUF',
			'PF' => 'PYF',
			'TF' => 'ATF',
			'GA' => 'GAB',
			'GM' => 'GMB',
			'GE' => 'GEO',
			'DE' => 'DEU',
			'GH' => 'GHA',
			'GI' => 'GIB',
			'GR' => 'GRC',
			'GL' => 'GRL',
			'GD' => 'GRD',
			'GP' => 'GLD',
			'GU' => 'GUM',
			'GT' => 'GTM',
			'GG' => 'GGY',
			'GN' => 'HTI',
			'GW' => 'HMD',
			'GY' => 'VAT',
			'HT' => 'GIN',
			'HM' => 'GNB',
			'HN' => 'HND',
			'HK' => 'HKG',
			'HU' => 'HUN',
			'IS' => 'ISL',
			'IN' => 'IND',
			'ID' => 'IDN',
			'IR' => 'IRN',
			'IQ' => 'IRQ',
			'IE' => 'IRL',
			'IM' => 'IMN',
			'IL' => 'ISR',
			'IT' => 'ITA',
			'JM' => 'JAM',
			'JP' => 'JPN',
			'JE' => 'JEY',
			'JO' => 'JOR',
			'KZ' => 'KAZ',
			'KE' => 'KEN',
			'KI' => 'KIR',
			'KW' => 'KWT',
			'KG' => 'KGZ',
			'LA' => 'LAO',
			'LV' => 'LVA',
			'LB' => 'LBN',
			'LS' => 'LSO',
			'LR' => 'LBR',
			'LY' => 'LBY',
			'LI' => 'LIE',
			'LT' => 'LTU',
			'LU' => 'LUX',
			'MO' => 'MAC',
			'MK' => 'MKD',
			'MG' => 'MDG',
			'MW' => 'MWI',
			'MY' => 'MYS',
			'MV' => 'MDV',
			'ML' => 'MLI',
			'MT' => 'MLT',
			'MH' => 'MHL',
			'MQ' => 'MTQ',
			'MR' => 'MRT',
			'MU' => 'MUS',
			'YT' => 'MYT',
			'MX' => 'MEX',
			'FM' => 'FSM',
			'MD' => 'MDA',
			'MC' => 'MCO',
			'MN' => 'MNG',
			'ME' => 'MNE',
			'MS' => 'MSR',
			'MA' => 'MAR',
			'MZ' => 'MOZ',
			'MM' => 'MMR',
			'NA' => 'NAM',
			'NR' => 'NRU',
			'NP' => 'NPL',
			'NL' => 'NLD',
			'AN' => 'ANT',
			'NC' => 'NCL',
			'NZ' => 'NZL',
			'NI' => 'NIC',
			'NE' => 'NER',
			'NG' => 'NGA',
			'NU' => 'NIU',
			'NF' => 'NFK',
			'KP' => 'PRK',
			'MP' => 'MNP',
			'NO' => 'NOR',
			'OM' => 'OMN',
			'PK' => 'PAK',
			'PW' => 'PLW',
			'PS' => 'PSE',
			'PA' => 'PAN',
			'PG' => 'PNG',
			'PY' => 'PRY',
			'PE' => 'PER',
			'PH' => 'PHL',
			'PN' => 'PCN',
			'PL' => 'POL',
			'PT' => 'PRT',
			'PR' => 'PRI',
			'QA' => 'QAT',
			'RO' => 'ROU',
			'RU' => 'RUS',
			'RW' => 'RWA',
			'RE' => 'REU',
			'BL' => 'BLM',
			'SH' => 'SHN',
			'KN' => 'KNA',
			'LC' => 'LCA',
			'MF' => 'MAF',
			'PM' => 'SPM',
			'WS' => 'WSM',
			'SM' => 'SMR',
			'SA' => 'SAU',
			'SN' => 'SEN',
			'RS' => 'SRB',
			'SC' => 'SYC',
			'SL' => 'SLE',
			'SG' => 'SGP',
			'SK' => 'SVK',
			'SI' => 'SVN',
			'SB' => 'SLB',
			'SO' => 'SOM',
			'ZA' => 'ZAF',
			'GS' => 'SGS',
			'KR' => 'KOR',
			'ES' => 'ESP',
			'LK' => 'LKA',
			'VC' => 'VCT',
			'SD' => 'SDN',
			'SR' => 'SUR',
			'SJ' => 'SJM',
			'SZ' => 'SWZ',
			'SE' => 'SWE',
			'CH' => 'CHE',
			'SY' => 'SYR',
			'ST' => 'STP',
			'TW' => 'TWN',
			'TJ' => 'TJK',
			'TZ' => 'TZA',
			'TH' => 'THA',
			'TL' => 'TLS',
			'TG' => 'TGO',
			'TK' => 'TKL',
			'TO' => 'TON',
			'TT' => 'TTO',
			'TN' => 'TUN',
			'TR' => 'TUR',
			'TM' => 'TKM',
			'TC' => 'TCA',
			'TV' => 'TUV',
			'UM' => 'UMI',
			'VI' => 'VIR',
			'UG' => 'UGA',
			'UA' => 'UKR',
			'AE' => 'ARE',
			'GB' => 'GBR',
			'US' => 'USA',
			'UY' => 'URY',
			'UZ' => 'UZB',
			'VU' => 'VUT',
			'VA' => 'GUY',
			'VE' => 'VEN',
			'VN' => 'VNM',
			'WF' => 'WLF',
			'EH' => 'ESH',
			'YE' => 'YEM',
			'ZM' => 'ZMB',
			'ZW' => 'ZWE',
			'AX' => 'ALA',
		);
		if ( $iso2 ) {
			return array_key_exists( $iso2, $iso3 ) ? $iso3[ $iso2 ] : '';
		}
		return '';
	}

	/**
	 * Get Country ISO-2 by ISO-3
	 *
	 * @param  string $iso3 - ISO-3.
	 * @return string
	 */
	public static function get_country_iso2_by_iso3( $iso3 ) {
		$iso2 = array(
			'AFG' => 'AF',
			'ALB' => 'AL',
			'DZA' => 'DZ',
			'ASM' => 'AS',
			'AND' => 'AD',
			'AGO' => 'AO',
			'AIA' => 'AI',
			'ATA' => 'AQ',
			'ATG' => 'AG',
			'ARG' => 'AR',
			'ARM' => 'AM',
			'ABW' => 'AW',
			'AUS' => 'AU',
			'AUT' => 'AT',
			'AZE' => 'AZ',
			'BHS' => 'BS',
			'BHR' => 'BH',
			'BGD' => 'BD',
			'BRB' => 'BB',
			'BLR' => 'BY',
			'BEL' => 'BE',
			'BLZ' => 'BZ',
			'BEN' => 'BJ',
			'BMU' => 'BM',
			'BTN' => 'BT',
			'BOL' => 'BO',
			'BIH' => 'BA',
			'BWA' => 'BW',
			'BVT' => 'BV',
			'BRA' => 'BR',
			'IOT' => 'IO',
			'VGB' => 'VG',
			'BRN' => 'BN',
			'BGR' => 'BG',
			'BFA' => 'BF',
			'BDI' => 'BI',
			'KHM' => 'KH',
			'CMR' => 'CM',
			'CAN' => 'CA',
			'CPV' => 'CV',
			'CYM' => 'KY',
			'CAF' => 'CF',
			'TCD' => 'TD',
			'CHL' => 'CL',
			'CHN' => 'CN',
			'CXR' => 'CX',
			'CCK' => 'CC',
			'COL' => 'CO',
			'COM' => 'KM',
			'COG' => 'CG',
			'COD' => 'CD',
			'COK' => 'CK',
			'CRI' => 'CR',
			'HRV' => 'HR',
			'CUB' => 'CU',
			'CYP' => 'CY',
			'CZE' => 'CZ',
			'CIV' => 'CI',
			'DNK' => 'DK',
			'DJI' => 'DJ',
			'DMA' => 'DM',
			'DOM' => 'DO',
			'ECU' => 'EC',
			'EGY' => 'EG',
			'SLV' => 'SV',
			'GNQ' => 'GQ',
			'ERI' => 'ER',
			'EST' => 'EE',
			'ETH' => 'ET',
			'FLK' => 'FK',
			'FRO' => 'FO',
			'FJI' => 'FJ',
			'FIN' => 'FI',
			'FRA' => 'FR',
			'GUF' => 'GF',
			'PYF' => 'PF',
			'ATF' => 'TF',
			'GAB' => 'GA',
			'GMB' => 'GM',
			'GEO' => 'GE',
			'DEU' => 'DE',
			'GHA' => 'GH',
			'GIB' => 'GI',
			'GRC' => 'GR',
			'GRL' => 'GL',
			'GRD' => 'GD',
			'GLD' => 'GP',
			'GUM' => 'GU',
			'GTM' => 'GT',
			'GGY' => 'GG',
			'HTI' => 'GN',
			'HMD' => 'GW',
			'VAT' => 'GY',
			'GIN' => 'HT',
			'GNB' => 'HM',
			'HND' => 'HN',
			'HKG' => 'HK',
			'HUN' => 'HU',
			'ISL' => 'IS',
			'IND' => 'IN',
			'IDN' => 'ID',
			'IRN' => 'IR',
			'IRQ' => 'IQ',
			'IRL' => 'IE',
			'IMN' => 'IM',
			'ISR' => 'IL',
			'ITA' => 'IT',
			'JAM' => 'JM',
			'JPN' => 'JP',
			'JEY' => 'JE',
			'JOR' => 'JO',
			'KAZ' => 'KZ',
			'KEN' => 'KE',
			'KIR' => 'KI',
			'KWT' => 'KW',
			'KGZ' => 'KG',
			'LAO' => 'LA',
			'LVA' => 'LV',
			'LBN' => 'LB',
			'LSO' => 'LS',
			'LBR' => 'LR',
			'LBY' => 'LY',
			'LIE' => 'LI',
			'LTU' => 'LT',
			'LUX' => 'LU',
			'MAC' => 'MO',
			'MKD' => 'MK',
			'MDG' => 'MG',
			'MWI' => 'MW',
			'MYS' => 'MY',
			'MDV' => 'MV',
			'MLI' => 'ML',
			'MLT' => 'MT',
			'MHL' => 'MH',
			'MTQ' => 'MQ',
			'MRT' => 'MR',
			'MUS' => 'MU',
			'MYT' => 'YT',
			'MEX' => 'MX',
			'FSM' => 'FM',
			'MDA' => 'MD',
			'MCO' => 'MC',
			'MNG' => 'MN',
			'MNE' => 'ME',
			'MSR' => 'MS',
			'MAR' => 'MA',
			'MOZ' => 'MZ',
			'MMR' => 'MM',
			'NAM' => 'NA',
			'NRU' => 'NR',
			'NPL' => 'NP',
			'NLD' => 'NL',
			'ANT' => 'AN',
			'NCL' => 'NC',
			'NZL' => 'NZ',
			'NIC' => 'NI',
			'NER' => 'NE',
			'NGA' => 'NG',
			'NIU' => 'NU',
			'NFK' => 'NF',
			'PRK' => 'KP',
			'MNP' => 'MP',
			'NOR' => 'NO',
			'OMN' => 'OM',
			'PAK' => 'PK',
			'PLW' => 'PW',
			'PSE' => 'PS',
			'PAN' => 'PA',
			'PNG' => 'PG',
			'PRY' => 'PY',
			'PER' => 'PE',
			'PHL' => 'PH',
			'PCN' => 'PN',
			'POL' => 'PL',
			'PRT' => 'PT',
			'PRI' => 'PR',
			'QAT' => 'QA',
			'ROU' => 'RO',
			'RUS' => 'RU',
			'RWA' => 'RW',
			'REU' => 'RE',
			'BLM' => 'BL',
			'SHN' => 'SH',
			'KNA' => 'KN',
			'LCA' => 'LC',
			'MAF' => 'MF',
			'SPM' => 'PM',
			'WSM' => 'WS',
			'SMR' => 'SM',
			'SAU' => 'SA',
			'SEN' => 'SN',
			'SRB' => 'RS',
			'SYC' => 'SC',
			'SLE' => 'SL',
			'SGP' => 'SG',
			'SVK' => 'SK',
			'SVN' => 'SI',
			'SLB' => 'SB',
			'SOM' => 'SO',
			'ZAF' => 'ZA',
			'SGS' => 'GS',
			'KOR' => 'KR',
			'ESP' => 'ES',
			'LKA' => 'LK',
			'VCT' => 'VC',
			'SDN' => 'SD',
			'SUR' => 'SR',
			'SJM' => 'SJ',
			'SWZ' => 'SZ',
			'SWE' => 'SE',
			'CHE' => 'CH',
			'SYR' => 'SY',
			'STP' => 'ST',
			'TWN' => 'TW',
			'TJK' => 'TJ',
			'TZA' => 'TZ',
			'THA' => 'TH',
			'TLS' => 'TL',
			'TGO' => 'TG',
			'TKL' => 'TK',
			'TON' => 'TO',
			'TTO' => 'TT',
			'TUN' => 'TN',
			'TUR' => 'TR',
			'TKM' => 'TM',
			'TCA' => 'TC',
			'TUV' => 'TV',
			'UMI' => 'UM',
			'VIR' => 'VI',
			'UGA' => 'UG',
			'UKR' => 'UA',
			'ARE' => 'AE',
			'GBR' => 'GB',
			'USA' => 'US',
			'URY' => 'UY',
			'UZB' => 'UZ',
			'VUT' => 'VU',
			'GUY' => 'VA',
			'VEN' => 'VE',
			'VNM' => 'VN',
			'WLF' => 'WF',
			'ESH' => 'EH',
			'YEM' => 'YE',
			'ZMB' => 'ZM',
			'ZWE' => 'ZW',
			'ALA' => 'AX',
		);
		if ( $iso3 ) {
			return array_key_exists( $iso3, $iso2 ) ? $iso2[ $iso3 ] : '';
		}
		return '';
	}

	/**
	 * Get request value
	 *
	 * @param  string         $key     - key.
	 * @param  boolean|string $default - default.
	 * @return boolean|string
	 */
	public static function get_request_value( $key, $default = false ) {
		if ( isset( $_POST['save'] ) && isset( $_POST['_wpnonce'] ) && isset( $_GET['page'] ) ) { // input var okay.
			if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['_wpnonce'] ) ), 'woocommerce-settings' ) ) { // input var okay.
				return $default;
			}
		}
		if ( isset( $_REQUEST[ $key ] ) ) { // input var okay.
			return sanitize_text_field( wp_unslash( $_REQUEST[ $key ] ) ); // input var okay.
		}
		return $default;
	}

	/**
	 * Get status response from 'status_url' or 'refund_status_url'
	 *
	 * @param  string $action action.
	 * @return array
	 */
	public static function get_status_response( $action = 'payment' ) {
		$parameters      = array();
		$status_response = array();

		if ( 'payment' === $action ) {
			$parameters = self::$status_url_parameters;
		} elseif ( 'refund' === $action ) {
			$parameters = self::$refund_status_url_parameters;
		}
		foreach ( $parameters as $value ) {
			$parameter_value = self::get_request_value( $value );
			if ( $parameter_value ) {
				$status_response[ strtolower( $value ) ] = $parameter_value;
			}
		}
		return $status_response;
	}
}
