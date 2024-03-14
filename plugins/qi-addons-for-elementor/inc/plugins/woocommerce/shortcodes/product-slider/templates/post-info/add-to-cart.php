<?php

if ( function_exists( 'woocommerce_template_loop_add_to_cart' ) && ! empty( $button_params ) ) {
//	woocommerce_template_loop_add_to_cart();
	echo QiAddonsForElementor_Button_Shortcode::call_shortcode( $button_params );
}
