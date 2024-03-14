<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WooCommerce Coupon Messages by itthinx
 * http://xootix.com/side-cart-woocommerce
 * #[AllowDynamicProperties] 

  class WFACP_Compatibility_WC_Coupon_Messages
 */
#[AllowDynamicProperties] 

  class WFACP_Compatibility_WC_Coupon_Messages {

	public function __construct() {
		add_action( 'wfacp_template_load', [ $this, 'action' ] );
		add_filter( 'wfacp_remove_coupon_message', [ $this, 'change_coupon_text' ], 9999 );
	}


	public function action() {

		add_action( 'woocommerce_update_order_review_fragments', [ $this, 'add_fragment' ], 100 );

	}

	public function add_fragment( $fragments ) {

		$options = get_option( 'woocommerce-coupon-messages', null );

		if ( ! is_array( $options ) || count( $options ) == 0 ) {
			return $fragments;
		}
		$success_message = '';
		$sidebar_messages = '';
		$messages        = '';
		if ( isset( $options['_cmsg200'] ) && ! empty( $options['_cmsg200'] ) ) {

			$success_message = $options['_cmsg200'];
		}

		ob_start();
		foreach ( WFACP_Common::get_coupons() as $code => $coupon ) {

			$remove_link = sprintf( "<a href='javascript:void(0)' class='wfacp_remove_coupon' data-coupon='%s'>%s</a>", $code, __( 'Remove', 'funnel-builder' ) );
			$messages    .= sprintf( '<div class="wfacp_single_coupon_msg">%s %s</div>', $success_message, $remove_link );


			$remove_link      = sprintf( "<a href='%s' class='woocommerce-remove-coupon' data-coupon='%s'>%s</a>", add_query_arg( [
				'remove_coupon' => $code,
			], wc_get_checkout_url() ), $code, __( 'Remove', 'funnel-builder' ) );
			$sidebar_messages .= sprintf( '<div class="woocommerce-message1 wfacp_coupon_success">%s %s</div>', $success_message, $remove_link );

		}
		$fragments['.wfacp_coupon_msg .woocommerce-message'] = '<div class="woocommerce-message wfacp_sucuss">' . $sidebar_messages . '</div>';

		$fragments['.wfacp_coupon_field_msg'] = '<div class="wfacp_coupon_field_msg"><div class="wfacp_single_coupon_msg">' . $messages . '</div></div>';


		return $fragments;
	}

	public function change_coupon_text( $coupon_message ) {
		$options = get_option( 'woocommerce-coupon-messages', null );


		if ( ! is_array( $options ) || count( $options ) == 0 ) {
			return $coupon_message;
		}

		if ( isset( $options['_cmsg201'] ) && ! empty( $options['_cmsg201'] ) ) {

			$coupon_message = $options['_cmsg201'];
		}


		return $coupon_message;


	}
}
WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_WC_Coupon_Messages(), 'woocommerce-coupon-messages' );
