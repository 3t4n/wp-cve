<?php
defined( 'ABSPATH' ) || exit;

class XLWCTY_JILT {
	private static $ins = null;

	public function __construct() {
		add_shortcode( 'xlwcty_jilt_post_registration_html', array( $this, 'xlwcty_jilt_post_registration' ) );
	}

	public static function get_instance() {
		if ( self::$ins == null ) {
			self::$ins = new self;
		}

		return self::$ins;
	}


	public function xlwcty_jilt_post_registration() {
		$order = XLWCTY_Core()->data->get_order();
		if ( ! $order instanceof WC_Order ) {
			echo __( 'WooCommerce order not found', 'thank-you-page-for-woocommerce-nextmove' );
		}
		if ( ! class_exists( 'WC_Jilt_Frontend' ) ) {
			echo __( 'Jilt Frontend class doesn\'t exist.', 'thank-you-page-for-woocommerce-nextmove' );
		}

		$jilt_frontend_object = new WC_Jilt_Frontend();
		echo $jilt_registration_message = $jilt_frontend_object->maybe_render_account_prompt( '', $order );

	}

}

XLWCTY_JILT::get_instance();