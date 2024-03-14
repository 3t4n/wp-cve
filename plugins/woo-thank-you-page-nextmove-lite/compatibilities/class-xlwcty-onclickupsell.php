<?php
defined( 'ABSPATH' ) || exit;

class XLWCTY_One_Click_Upsell {
	private static $ins = null;

	public function __construct() {
		add_action( 'plugins_loaded', array( $this, 'actions' ), 3 );
	}

	public static function get_instance() {
		if ( null == self::$ins ) {
			self::$ins = new self;
		}

		return self::$ins;
	}

	public function actions() {
		if ( defined( 'GB_OCU_ID' ) ) {
			add_filter( 'xlwcty_redirect_to_thankyou', array( $this, 'order_received_url' ), 99, 3 );
		}
	}


	public function order_received_url( $status, $link, $order ) {
		$parse_link = wp_parse_url( $link );
		// checking if upsell plugin has modified the thank you url of order
		if ( isset( $parse_link['query'] ) && strpos( $parse_link['query'], '1cu' ) !== false ) {
			return $link;
		}

		return $status;

	}

}

XLWCTY_One_Click_Upsell::get_instance();
