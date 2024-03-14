<?php
defined( 'ABSPATH' ) || exit;

class XLWCTY_Mollie {

	private static $ins = null;

	public function __construct() {
		add_action( 'plugins_loaded', array( $this, 'actions' ), 3 );
	}

	public static function get_instance() {
		if ( null === self::$ins ) {
			self::$ins = new self;
		}

		return self::$ins;
	}

	public function actions() {
		if ( ! class_exists( 'Mollie_WC_Plugin' ) ) {
			return;
		}
		add_filter( 'mollie-payments-for-woocommerce_return_url', array( $this, 'order_received_url' ), 99, 2 );
	}

	public function order_received_url( $return_url, WC_Order $order ) {

		$default_settings = XLWCTY_Core()->data->get_option();
		if ( isset( $default_settings['xlwcty_preview_mode'] ) && ( 'sandbox' === $default_settings['xlwcty_preview_mode'] ) ) {
			return $return_url;
		}

		$order_id = XLWCTY_Compatibility::get_order_id( $order );
		if ( 0 != $order_id ) {
			$get_link = XLWCTY_Core()->data->setup_thankyou_post( XLWCTY_Compatibility::get_order_id( $order ), $this->is_preview )->get_page_link();
			if ( false !== $get_link ) {
				$get_link = trim( $get_link );
				$get_link = wp_specialchars_decode( $get_link );

				return ( XLWCTY_Common::prepare_single_post_url( $get_link, $order ) );
			}
		}

		return $return_url;
	}
}

XLWCTY_Mollie::get_instance();
