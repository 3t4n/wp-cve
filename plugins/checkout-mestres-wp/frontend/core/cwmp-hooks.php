<?php
if(esc_html(get_option('cwmp_activate_checkout'))=="S"){

	function cwmp_hooks() {
			if(get_option('cwmp_activate_checkout')=="S"){
				remove_all_actions('woocommerce_before_checkout_form');
				remove_all_actions('woocommerce_checkout_before_order_review_heading');
				remove_all_actions('woocommerce_checkout_before_customer_details');
				remove_all_actions('woocommerce_checkout_after_customer_details');
				remove_all_actions('woocommerce_checkout_after_order_review');
				remove_all_actions('woocommerce_checkout_order_review');
				add_action( 'woocommerce_checkout_after_customer_details', 'woocommerce_checkout_payment', 10 );
				add_action( 'woocommerce_checkout_before_order_review_heading', 'woocommerce_checkout_coupon_form', 10 );
				add_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_login_form', 10 );
				add_action( 'woocommerce_checkout_order_review', 'woocommerce_order_review', 10 );
				remove_filter( 'woocommerce_cart_item_name', 'shoptimizer_product_thumbnail_in_checkout', 20, 3 );
				remove_action( 'woocommerce_after_checkout_form', 'woocommerce_checkout_coupon_form' );
				remove_action( 'woocommerce_after_checkout_form', 'shoptimizer_coupon_wrapper_start', 5 );
				remove_action( 'woocommerce_after_checkout_form', 'shoptimizer_coupon_wrapper_end', 60 );
				global $avada_woocommerce;
				remove_action( 'wp', array( $avada_woocommerce, 'wp' ), 20 );
				$my_theme = wp_get_theme();
				if($my_theme->Name=="Customify"){
					remove_filter( 'woocommerce_get_script_data', array( Customify_WC::get_instance(), 'woocommerce_get_script_data' ), 15, 2 );
				}
			}
	}
	add_action( 'init', 'cwmp_hooks', 10 );

}