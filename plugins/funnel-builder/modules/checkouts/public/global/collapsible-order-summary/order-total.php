<?php
if ( ! defined( 'WFACP_TEMPLATE_DIR' ) ) {
	return '';
}
$colspan_attr = '';
if ( apply_filters( 'wfacp_cart_show_product_thumbnail', false ) ) {
	$colspan_attr1    = ' colspan="2"';
	$colspan_attr     = apply_filters( 'wfacp_order_summary_cols_span', $colspan_attr1 );
	$cellpadding_attr = ' cellpadding="20"';
}
do_action( 'wfacp_mini_cart_before_order_total', $this, [] );

$tax_enabled = "";
if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) {
	$tax_enabled = "wfacp_tax_enabled";
}
$instance = wfacp_template();
?>
<table class="wfacp_mini_cart_reviews shop_table woocommerce-checkout-review-order-table_layout_9 wfacp_template_9_cart_total_details layout_9_order_summary <?php echo $tax_enabled; ?>" <?php echo WFACP_Common::get_fragments_attr() ?> >
    <tfoot>
    <tr class="cart-subtotal">
        <th <?php echo $colspan_attr; ?>><span><?php _e( 'Subtotal', 'woocommerce' ); ?></span></th>
        <td><?php wc_cart_totals_subtotal_html(); ?></td>
    </tr>

	<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
        <tr class="cart-discount sidebar_coupon coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
            <th <?php echo $colspan_attr; ?>><span><?php $instance->wc_cart_totals_coupon_label( $coupon ); ?></span></th>
            <td><?php wc_cart_totals_coupon_html( $coupon );	do_action( 'wfacp_after_coupon_html', $coupon ); ?></td>
        </tr>
	<?php endforeach; ?>

	<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
        <tr class="fee">
            <th <?php echo $colspan_attr; ?>><span><?php echo esc_html( $fee->name ); ?></span></th>
            <td><?php wc_cart_totals_fee_html( $fee ); ?></td>
        </tr>
	<?php endforeach; ?>

	<?php
	do_action( 'wfacp_mini_cart_before_shipping' );
	if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) {

		$method = $instance->have_shipping_method();
		if ( false === $method ) {
			do_action( 'woocommerce_review_order_before_shipping' );
		}


		WFACP_Common::wc_cart_totals_shipping_html( $colspan_attr );

		if ( false === $method ) {
			do_action( 'woocommerce_review_order_after_shipping' );
		}
	}
	do_action( 'wfacp_mini_cart_after_shipping' );
	?>

	<?php if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) : ?>
		<?php if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) : ?>
			<?php foreach ( WC()->cart->get_tax_totals() as $code => $tax ) : ?>
                <tr class="tax-rate tax-rate-<?php echo sanitize_title( $code ); ?>">
                    <th <?php echo $colspan_attr; ?>><span><?php echo esc_html( $tax->label ); ?></span></th>
                    <td><?php echo wp_kses_post( $tax->formatted_amount ); ?></td>
                </tr>
			<?php endforeach; ?>
		<?php else : ?>
            <tr class="tax-total">
                <th <?php echo $colspan_attr; ?>><span><?php echo esc_html( WC()->countries->tax_or_vat() ); ?></span></th>
                <td><?php wc_cart_totals_taxes_total_html(); ?></td>
            </tr>
		<?php endif; ?>
	<?php endif; ?>

	<?php
	if ( apply_filters( 'wfacp_disable_subscriptions_sidebar_summary', true ) ) {
		if ( class_exists( 'WC_Subscriptions_Cart' ) ) {
			remove_action( 'woocommerce_review_order_after_order_total', 'WC_Subscriptions_Cart::display_recurring_totals' );
		}
	}
	do_action( 'woocommerce_review_order_before_order_total' );


	?>

    <tr class="order-total">
        <th <?php echo $colspan_attr; ?>><span><?php _e( 'Total', 'woocommerce' ); ?></span></th>
        <td><?php wc_cart_totals_order_total_html(); ?></td>
    </tr>
	<?php do_action( 'woocommerce_review_order_after_order_total' ); ?>
    </tfoot>
</table>
<?php
do_action( 'wfacp_mini_cart_after_order_total', $this, [] );
?>
