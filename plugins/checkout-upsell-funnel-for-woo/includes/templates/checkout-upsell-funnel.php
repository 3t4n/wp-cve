<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! isset( $position ) ) {
	$setting        = new VICUFFW_CHECKOUT_UPSELL_FUNNEL_Data();
	$is_mobile      = VICUFFW_CHECKOUT_UPSELL_FUNNEL_Frontend_Upsell_Funnel::$is_mobile ?? wp_is_mobile();
	$position       = VICUFFW_CHECKOUT_UPSELL_FUNNEL_Frontend_Upsell_Funnel::$position;
	$rule           = VICUFFW_CHECKOUT_UPSELL_FUNNEL_Frontend_Upsell_Funnel::$rule;
	$display_type   = $is_mobile ? $setting->get_params( 'us_mobile_display_type' ) : $setting->get_params( 'us_desktop_display_type' );
	$product_ids    = WC()->session->get( 'viwcuf_us_recommend_pd_ids', '' );
	$shortcode_html = do_shortcode( '[viwcuf_checkout_upsell_funnel rule="' . $rule . '" position="' . $position . '" product_ids="' . esc_attr( $product_ids ) . '"]' );
	remove_action( 'woocommerce_checkout_after_order_review', array( 'VICUFFW_CHECKOUT_UPSELL_FUNNEL_Frontend_Us_Checkout', 'viwcuf_us_woocommerce_checkout_after_order_review' ) );
	?>
    <div class="viwcuf-checkout-funnel-checkout-form vi-wcuf-disable">
		<?php echo do_shortcode( '[' . apply_filters( 'woocommerce_checkout_shortcode_tag', 'woocommerce_checkout' ) . ']' ); ?>
    </div>
	<?php
}
$div_class   = array(
	'viwcuf-checkout-funnel-container',
	'viwcuf-checkout-funnel-container-' . $position,
	'viwcuf-checkout-funnel-container-' . $display_type,
);
$div_class[] = $rule ? 'viwcuf-checkout-funnel-container-' . $rule : 'viwcuf-checkout-funnel-container-not_rule';
$div_class[] = is_rtl() ? 'viwcuf-checkout-funnel-container-rtl' : '';
$div_class[] = $position === 'footer' ? 'vi-wcuf-disable' : '';
$div_class   = trim( implode( ' ', $div_class ) );
?>
    <div class="<?php echo esc_attr( $div_class ) ?>">
        <div class="vi-wcuf-loading-wrap vi-wcuf-disable">
            <div class="vi-wcuf-loading">
                <div></div>
                <div></div>
                <div></div>
                <div></div>
            </div>
        </div>
		<?php
		echo wp_kses( $shortcode_html, VICUFFW_CHECKOUT_UPSELL_FUNNEL_Data::extend_post_allowed_html() );
		if ( $position === 'footer' ) {
			echo wp_kses_post( '<div class="viwcuf-checkout-funnel-overlay"></div>' );
		}
		?>
    </div>