<?php
if ( ! defined( 'WFACP_TEMPLATE_DIR' ) ) {
	return '';
}
/**
 * @var $widget_id
 */
$instance = wfacp_template();

$colspan_first       = 1;
$colspan_second      = 1;
$show_product_image  = false;
$show_quantity_image = false;
$enable_delete_item  = false;
$settings            = WFACP_Common::get_session( $widget_id );
if ( 'yes' == $settings['enable_product_image'] ) {
	$show_product_image = true;
}
if ( 'yes' == $settings['enable_delete_item'] ) {
	$colspan_first ++;
	$enable_delete_item = true;
}

if ( 'yes' == $settings['enable_quantity_box'] ) {
	$colspan_second ++;
	$show_quantity_image = true;
}
$colspan_first  = 1;
$colspan_second = 1;

$colspan_attr_1 = $colspan_first;
$colspan_attr_2 = $colspan_second;
global $wfacp_colspan_attr_1, $wfacp_colspan_attr_2;
$wfacp_colspan_attr_1 = $colspan_attr_1;
$wfacp_colspan_attr_2 = $colspan_attr_2;

add_filter( 'wfacp_order_shipping_colspan', 'WFACP_Common_Helper::order_review_shipping_colspan' );
do_action( 'wfacp_mini_cart_before_order_total', $this, [] );

?>
    <table class="shop_table <?php echo $instance->get_template_slug(); ?> wfacp_mini_cart_reviews mini_cart_wrap_here" id="wfacp_mini_cart_reviews_<?php echo $widget_id ?>">

        <tr class="cart-subtotal">
            <th colspan="<?php echo $colspan_first ?>"><span><?php _e( 'Subtotal', 'woocommerce' ); ?></span></th>
            <td colspan="<?php echo $colspan_second ?>"><?php wc_cart_totals_subtotal_html(); ?></td>
        </tr>
		<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
            <tr class="cart-subtotal cart-discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
                <th colspan="<?php echo $colspan_first ?>"><span><?php $instance->wc_cart_totals_coupon_label( $coupon ) ?></span></th>
                <td colspan="<?php echo $colspan_second ?>"><?php wc_cart_totals_coupon_html( $coupon ); ?></td>
            </tr>
		<?php endforeach; ?>

		<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
            <tr class="cart-subtotal fee">
                <th colspan="<?php echo $colspan_first ?>"><span><?php echo esc_html( $fee->name ); ?></span></th>
                <td colspan="<?php echo $colspan_second ?>"><?php wc_cart_totals_fee_html( $fee ); ?></td>
            </tr>
		<?php endforeach; ?>

		<?php
		if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) {
			do_action( 'woocommerce_review_order_before_shipping' );


			WFACP_Common::wc_cart_totals_shipping_html( $colspan_attr_1, $colspan_attr_2 );
			do_action( 'woocommerce_review_order_after_shipping' );
		}
		?>

		<?php if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) : ?>
			<?php if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) : ?>
				<?php foreach ( WC()->cart->get_tax_totals() as $code => $tax ) : ?>
                    <tr class="cart-subtotal tax-rate tax-rate-<?php echo sanitize_title( $code ); ?>">
                        <th colspan="<?php echo $colspan_first ?>"><span><?php echo esc_html( $tax->label ); ?></span></th>
                        <td colspan="<?php echo $colspan_second ?>"><?php echo wp_kses_post( $tax->formatted_amount ); ?></td>
                    </tr>
				<?php endforeach; ?>
			<?php else : ?>
                <tr class="cart-subtotal tax-total">
                    <th colspan="<?php echo $colspan_first ?>"><span><?php echo esc_html( WC()->countries->tax_or_vat() ); ?></span></th>
                    <td colspan="<?php echo $colspan_second ?>"><?php wc_cart_totals_taxes_total_html(); ?></td>
                </tr>
			<?php endif; ?>
		<?php endif; ?>

		<?php do_action( 'woocommerce_review_order_before_order_total' ); ?>

        <tr class="order-total">
            <th colspan="<?php echo $colspan_first ?>"><span><?php _e( 'Total', 'woocommerce' ); ?></span></th>
            <td colspan="<?php echo $colspan_second ?>"><?php wc_cart_totals_order_total_html(); ?></td>
        </tr>
		<?php
		if ( apply_filters( 'wfacp_disable_subscriptions_sidebar_summary', true ) ) {
			if ( class_exists( 'WC_Subscriptions_Cart' ) ) {
				remove_action( 'woocommerce_review_order_after_order_total', 'WC_Subscriptions_Cart::display_recurring_totals' );
			}
		}
		do_action( 'woocommerce_review_order_after_order_total' );
		?>
    </table>
<?php
remove_filter( 'wfacp_order_shipping_colspan', 'WFACP_Common_Helper::order_review_shipping_colspan' );

do_action( 'wfacp_mini_cart_after_order_total', $this, [] );
