<?php

add_action( 'woocommerce_coupon_options', 'pmcs_woocommerce_coupon_options' );
function pmcs_woocommerce_coupon_options() {
	$currencies = pmcs()->switcher->get_currencies();
	$default_code = pmcs()->switcher->get_woocommerce_currency();
	global $post;
	$id = $post->ID;

	foreach ( $currencies as $code => $currency ) {
		// Amount.
		woocommerce_wp_text_input(
			array(
				'id'          => 'coupon_amount_' . $code,
				'label'       => sprintf( __( 'Coupon amount(%1$s)', 'woocommerce' ), $code ),
				'placeholder' => sprintf( __( 'Coupon amount in %1$s', 'woocommerce' ), $currencies[  $code ]['display_text'] ),
				'description' => sprintf( __( 'Value of the coupon in %1$s. Leave it empty to use default coupon amount.', 'woocommerce' ), $currency['display_text'] ),
				'data_type'   => 'price',
				'desc_tip'    => true,
				'value'       => get_post_meta( $id, '_amount_' . $code, true ),
				'wrapper_class'       => 'pmcs_coupon_field',
			)
		);
	}

}

add_action( 'woocommerce_process_shop_coupon_meta', 'pmcs_save_coupon_fields', 10, 2 );
function pmcs_save_coupon_fields( $id, $post ) {
	$currencies = pmcs()->switcher->get_currencies();
	$default_code = pmcs()->switcher->get_woocommerce_currency();

	foreach ( $currencies as $code => $currency ) {
		$title = __( 'Price(%1$s)', 'pmcs' );
		$tip = $currency['display_text'];

		$amount = isset( $_POST[ 'coupon_amount_' . $code ] ) ? sanitize_text_field( wp_unslash( $_POST[ 'coupon_amount_' . $code ] ) ) : '';
		update_post_meta( $id, '_amount_' . $code, $amount );
	}
}







