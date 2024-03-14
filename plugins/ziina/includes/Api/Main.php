<?php
/**
 * Api class
 *
 * @package ZiinaPayment
 */

namespace ZiinaPayment\Api;

use Exception;
use ZiinaPayment\Ajax\Payment;
use ZiinaPayment\Entities\ZiinaPayment;

defined( 'ABSPATH' ) || exit();

/**
 * Class Api
 *
 * @package ZiinaPayment
 * @since   1.0.0
 */
class Main {
	/**
	 * @var string
	 */
	private $api_url = 'https://api-v2.ziina.com/api/';

	/**
	 * @var array
	 */
	private $zero_decimals = [
		'bif',
		'clp',
		'djf',
		'gnf',
		'jpy',
		'kmf',
		'krw',
		'mga',
		'pyg',
		'rwf',
		'ugx',
		'vnd',
		'vuv',
		'xaf',
		'xof',
		'xpf',
	];

	/**
	 * @var array
	 */
	private $two_decimals = [
		'aed',
		'afn',
		'all',
		'amd',
		'ang',
		'aoa',
		'ars',
		'aud',
		'awg',
		'azn',
		'bam',
		'bbd',
		'bdt',
		'bgn',
		'bmd',
		'bnd',
		'bob',
		'brl',
		'bsd',
		'bwp',
		'byn',
		'bzd',
		'cad',
		'cdf',
		'chf',
		'cny',
		'cop',
		'crc',
		'cve',
		'czk',
		'dkk',
		'dop',
		'dzd',
		'egp',
		'etb',
		'eur',
		'fjd',
		'fkp',
		'gbp',
		'gel',
		'gip',
		'gmd',
		'gtq',
		'gyd',
		'hkd',
		'hnl',
		'htg',
		'huf',
		'idr',
		'ils',
		'inr',
		'isk',
		'jmd',
		'kes',
		'kgs',
		'khr',
		'kyd',
		'kzt',
		'lak',
		'lbp',
		'lkr',
		'lrd',
		'lsl',
		'mad',
		'mdl',
		'mkd',
		'mmk',
		'mnt',
		'mop',
		'mro',
		'mur',
		'mvr',
		'mwk',
		'mxn',
		'myr',
		'mzn',
		'nad',
		'ngn',
		'nio',
		'nok',
		'npr',
		'nzd',
		'pab',
		'pen',
		'pgk',
		'php',
		'pkr',
		'pln',
		'qar',
		'ron',
		'rsd',
		'rub',
		'sar',
		'sbd',
		'scr',
		'sek',
		'sgd',
		'shp',
		'sle',
		'sos',
		'srd',
		'std',
		'szl',
		'thb',
		'tjs',
		'top',
		'try',
		'ttd',
		'twd',
		'tzs',
		'uah',
		'usd',
		'uyu',
		'uzs',
		'wst',
		'xcd',
		'yer',
		'zar',
		'zmw',
	];

	/**
	 * @var array
	 */
	private $three_decimals = [
		'bhd',
		'jod',
		'kwd',
		'omr',
		'tnd',
	];

	/**
	 * @var bool
	 */
	private $is_test;

	/**
	 * @var string
	 */
	private $authorization_token;

	/**
	 * Api constructor.
	 */
	public function __construct() {
		$this->is_test             = ziina_payment()->get_setting( 'is_test' ) ?? true;
		$this->authorization_token = ziina_payment()->get_setting( 'authorization_token' ) ?? '';
	}

	/**
	 * @param string $endpoint ziina api endpoint.
	 * @param string $method   http method.
	 * @param array  $body     request body.
	 *
	 * @return array
	 * @throws Exception If request error.
	 */
	private function request( string $endpoint, $method = 'GET', $body = array() ): array {
		$url = $this->api_url . $endpoint;

		$params = array(
			'body'    => empty( $body ) ? null : wp_json_encode( $body ),
			'method'  => $method,
			'headers' => array(
				'Authorization' => "Bearer $this->authorization_token",
				'Content-Type'  => 'application/json',
				'Accept'        => 'application/json',
			),
		);

		ziina_payment()->log(
			array(
				'url'    => $url,
				'method' => $method,
				'body'   => $body,
				'params' => $params,
			)
		);

		$res = wp_remote_request( $url, $params );

		if ( is_wp_error( $res ) ) {
			ziina_payment()->log(
				array(
					'error' => $res->get_error_message(),
				)
			);

			throw new Exception( $res->get_error_message() );
		}

		ziina_payment()->log(
			array(
				'response' => $res,
			)
		);

		$res_body = json_decode( $res['body'], true );

		if ( ! empty( $res['body'] ) && is_null( $res_body ) ) {
			ziina_payment()->log(
				array(
					'error' => 'wrong body',
					'body'  => $res['body'],
				)
			);

			throw new Exception( __( 'Api request decoding error', 'ziina' ) );
		}

		return $res_body;
	}

	/**
	 * @param mixed $order order to create payment.
	 *
	 * @return string
	 * @throws Exception If request error.
	 */
	public function create_payment_intent( $order ): string {
		$order = wc_get_order( $order );

		ini_set("serialize_precision", -1);

		if(in_array(strtolower($order->get_currency()), $this->zero_decimals)){
			$total = round( $order->get_total() );
		}elseif(in_array(strtolower($order->get_currency()), $this->two_decimals)){
			$total = round( $order->get_total(), 2 ) * 100;
		}elseif(in_array(strtolower($order->get_currency()), $this->three_decimals)){
			$total = round( $order->get_total(), 2 ) * 1000;
		}else{
			throw new Exception( __( 'Not supported currency', 'ziina' ) );
		}

		$body = array(
			'amount'             => $total,
			'currency_code'      => $order->get_currency(),
			'transaction_source' => 'woocommerce',
			'success_url'        => Payment::get_action_url(
				'success_url',
				array(
					'order_id' => $order->get_id(),
				)
			),
			'cancel_url'         => Payment::get_action_url(
				'cancel_url',
				array(
					'order_id' => $order->get_id(),
				)
			),
			'test'               => $this->is_test,
		);

		$payment_intent = $this->request(
			'payment_intent',
			'POST',
			$body
		);

		if ( ! empty( $payment_intent ) && ! empty( $payment_intent['id'] ) ) {
			ZiinaPayment::by_order( $order )->set_payment_id( $payment_intent['id'] );
			return $payment_intent['redirect_url'];
		}

		throw new Exception( __( 'Api request error', 'ziina' ) );
	}

	/**
	 * @param mixed $order order to check payment.
	 *
	 * @return array
	 * @throws Exception If request error.
	 */
	public function get_payment_intent( $order ): array {
		$order = wc_get_order( $order );

		$payment_id = ZiinaPayment::by_order( $order )->payment_id();

		return $this->request( "payment_intent/$payment_id" );
	}
}
