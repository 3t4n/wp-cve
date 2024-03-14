<?php if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}
if ( is_multisite() ) {
	delete_blog_option( get_current_blog_id(), 'gupfw_keeplogs' );
	delete_blog_option( get_current_blog_id(), 'gupfw_disable_notices' );
	delete_blog_option( get_current_blog_id(), 'gupfw_errors' );
	delete_blog_option( get_current_blog_id(), 'gupfw_tgfp_in_cart_status' );
	delete_blog_option( get_current_blog_id(), 'gupfw_tgfp_in_cart_content' );
	delete_blog_option( get_current_blog_id(), 'gupfw_tgfp_in_cart_color' );
	delete_blog_option( get_current_blog_id(), 'gupfw_tgfp_in_cart_fsize' );
	delete_blog_option( get_current_blog_id(), 'gupfw_displaying_accept_remove_button' );
	delete_blog_option( get_current_blog_id(), 'gupfw_tgfp_remove_gift_in_cart' );
	delete_blog_option( get_current_blog_id(), 'gupfw_tgfp_accept_gift_in_cart' );
	delete_blog_option( get_current_blog_id(), 'gupfw_tgfp_in_product_status' );
	delete_blog_option( get_current_blog_id(), 'gupfw_tgfp_in_product_content' );
	delete_blog_option( get_current_blog_id(), 'gupfw_gift_for_any_product_in_cart_content' );
	delete_blog_option( get_current_blog_id(), 'gupfw_tgfp_in_product_color' );
	delete_blog_option( get_current_blog_id(), 'gupfw_tgfp_in_product_fsize' );
	delete_blog_option( get_current_blog_id(), 'gupfw_gift_for_any_product_arr' );
	delete_blog_option( get_current_blog_id(), 'gupfw_hide_or_remove' );
	delete_blog_option( get_current_blog_id(), 'gupfw_cart_total_price' );
	delete_blog_option( get_current_blog_id(), 'gupfw_rules_for_cart_price' );
	delete_blog_option( get_current_blog_id(), 'gupfw_whose_price_exceeds' );
	delete_blog_option( get_current_blog_id(), 'gupfw_days_of_the_week' );
	delete_blog_option( get_current_blog_id(), 'gupfw_days_of_the_hours' );
} else {
	delete_option( 'gupfw_keeplogs' );
	delete_option( 'gupfw_disable_notices' );
	delete_option( 'gupfw_errors' );
	delete_option( 'gupfw_tgfp_in_cart_status' );
	delete_option( 'gupfw_tgfp_in_cart_content' );
	delete_option( 'gupfw_tgfp_in_cart_color' );
	delete_option( 'gupfw_tgfp_in_cart_fsize' );
	delete_option( 'gupfw_displaying_accept_remove_button' );
	delete_option( 'gupfw_tgfp_remove_gift_in_cart' );
	delete_option( 'gupfw_tgfp_accept_gift_in_cart' );
	delete_option( 'gupfw_tgfp_in_product_status' );
	delete_option( 'gupfw_tgfp_in_product_content' );
	delete_option( 'gupfw_gift_for_any_product_in_cart_content' );
	delete_option( 'gupfw_tgfp_in_product_color' );
	delete_option( 'gupfw_tgfp_in_product_fsize' );
	delete_option( 'gupfw_gift_for_any_product_arr' );
	delete_option( 'gupfw_hide_or_remove' );
	delete_option( 'gupfw_cart_total_price' );
	delete_option( 'gupfw_rules_for_cart_price' );
	delete_option( 'gupfw_whose_price_exceeds' );
	delete_option( 'gupfw_days_of_the_week' );
	delete_option( 'gupfw_days_of_the_hours' );
}