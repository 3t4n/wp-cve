<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$current_user          = wp_get_current_user();
$display_name          = ! empty( $current_user->first_name ) ? $current_user->first_name : $current_user->display_name;
$cart_contents_count   = is_object( WC()->cart ) ? WC()->cart->get_cart_contents_count() : 0;
$shop_page_url         = get_permalink( wc_get_page_id( 'shop' ) );
$cc_enable_sfl_options = get_option( 'cc_enable_sfl_options' );

$cc_sfl_tab_flag = true;
// Return if the premium license is valid and sfl option is not enabled
$caddy                         = new Caddy();
$cc_premium_license_activation = $caddy->cc_check_premium_license_activation();
if ( $cc_premium_license_activation ) {
	$cc_enable_sfl_options = get_option( 'cc_enable_sfl_options' );
	if ( 'disabled' === $cc_enable_sfl_options ) {
		$cc_sfl_tab_flag = false;
	}
}
?>
<div class="cc-header cc-text-left">
	<i class="ccicon-x"></i>
	<div class="cc-inner-container">
		<div class="cc-nav">
			<ul data-tabs>
				<li><a data-tabby-default href="#cc-cart" class="cc-cart-nav" data-id="cc-cart"><?php esc_html_e( 'Your Cart', 'caddy' ); ?></a></li>
				<?php if ( is_user_logged_in() && $cc_sfl_tab_flag ) { ?>
					<li><a href="#cc-saves" class="cc-save-nav" data-id="cc-saves"><?php esc_html_e( 'Saved Items', 'caddy' ); ?></a></li>
				<?php } ?>
				<?php do_action( 'caddy_after_nav_tabs' ); ?>
			</ul>
		</div>
	</div>
</div>

<!-- Cart Screen -->
<div id="cc-cart" class="cc-cart cc-screen-tab">
	<?php Caddy_Public::cc_cart_screen(); ?>
</div>

<!-- Save for later screen -->
<?php if ( is_user_logged_in() ) { ?>
	<div id="cc-saves" class="cc-saves cc-screen-tab">
		<?php Caddy_Public::cc_sfl_screen(); ?>
	</div>
<?php } ?>

<?php do_action( 'caddy_after_screen_tabs' ); ?>
