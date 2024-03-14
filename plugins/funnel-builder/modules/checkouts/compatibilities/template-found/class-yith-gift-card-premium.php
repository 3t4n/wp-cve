<?php

/**
 * YITH WooCommerce GIft Certificates Premium
 *  https://yithemes.com/themes/plugins/yith-woocommerce-gift-cards
 * #[AllowDynamicProperties] 

  class WFACP_Compatibility_With_Yith_Gift
 */
class  WFACP_Compatibility_With_Yith_Gift {

	public function __construct() {
		add_action( 'wfacp_after_checkout_page_found', [ $this, 'action' ] );

	}

	public function action() {
		add_filter( 'woocommerce_checkout_coupon_message', array( $this, 'yith_ywgc_rename_coupon_label' ), 15, 1 );
	}


	public function yith_ywgc_rename_coupon_label( $text ) {
		if ( get_option( 'ywgc_apply_gift_card_on_coupon_form', 'no' ) == 'yes' ) {
			$text_option = get_option( 'ywgc_apply_coupon_label_text', esc_html__( 'Have a coupon?', 'yith-woocommerce-gift-cards' ) );
			$text        = $text_option . ' <a href="#" class="showcoupon wfacp_showcoupon">' . esc_html__( 'Click here to enter your code', 'woocommerce' ) . '</a>';
		}

		return $text;
	}
}


WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_Yith_Gift(), 'yith-gift' );


