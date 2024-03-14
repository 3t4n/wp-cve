<?php 

/*Compatibily hooks for Checkout*/
/*Readjust hooks for external themes and plugins*/
/*redefine own hooks to modify checkout design*/

$current_theme = get_template(); // gets the current theme

/*Remove Berberry coupon Form*/
if($current_theme == 'barberry' )
{
	remove_action( 'woocommerce_checkout_links', 'woocommerce_checkout_coupon_form' );	
}

/*Remove default avada theme checkout design*/
if($current_theme == 'Avada' )
{
	function remove_avada_woocommerce_hooks() {
	global $avada_woocommerce;
	remove_action( 'woocommerce_before_checkout_form', array( $avada_woocommerce, 'avada_top_user_container' ), 1 );
	remove_action( 'woocommerce_before_checkout_form',array( $avada_woocommerce, 'checkout_coupon_form' ), 10 );
	remove_action( 'woocommerce_before_checkout_form', array( $avada_woocommerce, 'before_checkout_form' ) );
	remove_action( 'woocommerce_after_checkout_form', array( $avada_woocommerce,'after_checkout_form' ) );
	remove_action( 'woocommerce_checkout_before_customer_details', array( $avada_woocommerce, 'checkout_before_customer_details' ) );
	remove_action( 'woocommerce_checkout_after_customer_details', array( $avada_woocommerce, 'checkout_after_customer_details' ) );
	remove_action( 'woocommerce_checkout_billing', array( $avada_woocommerce, 'checkout_billing' ), 20 );
	remove_action( 'woocommerce_checkout_shipping', array( $avada_woocommerce, 'checkout_shipping' ), 20 );
	
	}
	add_action( 'wp', 'remove_avada_woocommerce_hooks', 100 );
}
/* If woocommerce germanized is active remove blocking filters*/
if ( class_exists( 'WooCommerce_Germanized' ) )
{
	function remove_germanized_woocommerce_hooks() {
	remove_action( 'woocommerce_review_order_before_cart_contents', 'woocommerce_gzd_template_checkout_table_content_replacement' );
	remove_action( 'woocommerce_review_order_after_cart_contents', 'woocommerce_gzd_template_checkout_table_product_hide_filter_removal' );
	remove_action( 'woocommerce_review_order_before_payment', 'woocommerce_gzd_template_checkout_payment_title' );
	
	}
	add_action( 'wp', 'remove_germanized_woocommerce_hooks', 100 );
}


/*Recall review order and  payments sections*/
add_action( 'cclw_review_order_section', 'woocommerce_order_review');
add_action( 'cclw_payment_section', 'woocommerce_checkout_payment');


/*remove default Coupon from checkout Top */
//remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );

/*Add default coupon at bottom of checkout to show in popup */
//add_action( 'woocommerce_after_checkout_form', 'woocommerce_checkout_coupon_form' );
/*Create Coupon link action to open the coupon popup*/














