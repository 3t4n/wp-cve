<?php
defined( 'ABSPATH' ) || exit;

class XLWCTY_Paybox {
	private static $ins = null;

	private $normal_thankyou = null;

	public function __construct() {
		add_filter( 'woocommerce_payment_successful_result', array( $this, 'redirect_to_thankyou' ), 10, 2 );
		add_filter( 'xlwcty_redirect_to_thankyou', array( $this, 'check_gateway_thankyou' ), 10, 3 );
	}

	public static function get_instance() {
		if ( self::$ins == null ) {
			self::$ins = new self;
		}

		return self::$ins;
	}

	public function check_gateway_thankyou( $status, $url, $order ) {
		$payment_method = XLWCTY_Compatibility::get_payment_gateway_from_order( $order );
		if ( in_array( $payment_method, array( 'paybox_3x', 'paybox_std' ) ) ) {
			$this->normal_thankyou = $url;
		}

		return $status;
	}

	public function redirect_to_thankyou( $result, $order_id ) {
		$order = wc_get_order( $order_id );
		if ( $order instanceof WC_Order ) {
			if ( ! is_null( $this->normal_thankyou ) && strpos( $result['redirect'], 'order-pay' ) !== false ) {
				parse_str( $result['redirect'], $output );
				if ( isset( $output['order-pay'] ) ) {
					$this->normal_thankyou = add_query_arg( array(
						'order-pay' => $output['order-pay'],
					), $this->normal_thankyou );
					$result['redirect']    = $this->normal_thankyou;
				}

				return $result;
			}
		}

		return $result;
	}
}

XLWCTY_Paybox::get_instance();
