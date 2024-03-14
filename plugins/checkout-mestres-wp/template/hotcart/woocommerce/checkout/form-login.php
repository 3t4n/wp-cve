<?php
defined( 'ABSPATH' ) || exit;

if ( is_user_logged_in() || 'no' === get_option( 'woocommerce_enable_checkout_login_reminder' ) ) {
	return;
}

?>
<p class="textLogin"><?php esc_attr_e( 'Already have an account?', 'checkout-mestres-wp' ); ?> <a href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>"><?php esc_attr_e( 'Login', 'checkout-mestres-wp' ); ?></a></p>
