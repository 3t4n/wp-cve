<?php
defined( 'ABSPATH' ) || exit;

class XLWCTY_Klarna_compatibility {
	private static $ins = null;

	public function __construct() {
		add_action( 'parse_request', array( $this, 'thankyou_support' ), 1 );
	}

	public static function get_instance() {
		if ( null == self::$ins ) {
			self::$ins = new self;
		}

		return self::$ins;
	}

	public function thankyou_support() {
		if ( isset( $_GET['klarna_order'] ) && $_GET['klarna_order'] != '' && isset( $_GET['key'] ) && $_GET['key'] != '' ) {
			$order_id = ( isset( $_GET['order-received'] ) && $_GET['order-received'] > 0 ) ? $_GET['order-received'] : ( ( isset( $_GET['sid'] ) && $_GET['sid'] > 0 ) ? $_GET['sid'] : 0 );

			$order = wc_get_order( $order_id );
			if ( $order instanceof WC_Order ) {
				$get_link = XLWCTY_Core()->data->setup_thankyou_post( XLWCTY_Compatibility::get_order_id( $order ), false )->get_page_link();
				if ( false !== $get_link ) {
					$link_url = XLWCTY_Common::prepare_single_post_url( $get_link, $order );
					wp_redirect( $link_url );
					exit;
				}
			}
		}
	}


}

XLWCTY_Klarna_compatibility::get_instance();
