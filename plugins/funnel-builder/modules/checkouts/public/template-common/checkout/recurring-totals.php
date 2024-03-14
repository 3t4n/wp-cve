<?php
/**
 * Recurring totals
 *
 * @author  Prospress
 * @package WooCommerce Subscriptions/Templates
 * @version 2.0.0
 */

if ( ! defined( 'WFACP_TEMPLATE_DIR' ) ) {
	return '';
}
$display_th = true;

$total_title = __( 'Recurring Totals', 'woocommerce-subscriptions' );
if ( class_exists( 'WC_Subscriptions' ) && version_compare( WC_Subscriptions::$version, '2.6.1', '>=' ) ) {
	$total_title = __( 'Recurring totals', 'woocommerce-subscriptions' );
}
?>

<tr class="recurring-totals">
    <th colspan="2"><span><?php echo $total_title; ?></span></th>
</tr>

<?php foreach ( $recurring_carts as $recurring_cart_key => $recurring_cart ) : ?>
	<?php if ( 0 == $recurring_cart->next_payment_date ) : ?>
		<?php continue; ?>
	<?php endif; ?>
    <tr class="cart-subtotal recurring-total">
		<?php if ( $display_th ) : $display_th = false; ?>
            <th rowspan="<?php echo esc_attr( $carts_with_multiple_payments ); ?>"><span><?php esc_html_e( 'Subtotal', 'woocommerce-subscriptions' ); ?></span></th>
            <td data-title="<?php esc_attr_e( 'Subtotal', 'woocommerce-subscriptions' ); ?>"><?php wcs_cart_totals_subtotal_html( $recurring_cart ); ?></td>
		<?php else : ?>
            <td><?php wcs_cart_totals_subtotal_html( $recurring_cart ); ?></td>
		<?php endif; ?>
    </tr>
<?php endforeach; ?>
<?php $display_th = true; ?>

<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
	<?php foreach ( $recurring_carts as $recurring_cart_key => $recurring_cart ) : ?>
		<?php if ( 0 == $recurring_cart->next_payment_date ) : ?>
			<?php continue; ?>
		<?php endif; ?>
		<?php foreach ( $recurring_cart->get_coupons() as $recurring_code => $recurring_coupon ) : ?>
			<?php if ( $recurring_code !== $code ) {
				continue;
			} ?>
            <tr class="cart-discount coupon-<?php echo esc_attr( $code ); ?> recurring-total">
				<?php if ( $display_th ) : $display_th = false; ?>
                    <th rowspan="<?php echo esc_attr( $carts_with_multiple_payments ); ?>"><span><?php wc_cart_totals_coupon_label( $coupon ); ?></span></th>
                    <td data-title="<?php wc_cart_totals_coupon_label( $coupon ); ?>"><?php wcs_cart_totals_coupon_html( $recurring_coupon, $recurring_cart ); ?>
						<?php echo ' ';
						wcs_cart_coupon_remove_link_html( $recurring_coupon ); ?></td>
				<?php else : ?>
                    <td><?php wcs_cart_totals_coupon_html( $recurring_coupon, $recurring_cart ); ?></td>
				<?php endif; ?>
            </tr>
		<?php endforeach; ?>
	<?php endforeach; ?>
	<?php $display_th = true; ?>
<?php endforeach; ?>

<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>
	<?php

	$instance        = wfacp_template();
	$shipping_method = $instance->have_shipping_method();

	if ( $shipping_method ) {
		WFACP_Common::wcs_cart_totals_shipping_html();
	} else {
		wcs_cart_totals_shipping_html();
	}

	?>
<?php endif; ?>
<?php foreach ( $recurring_carts as $recurring_cart_key => $recurring_cart ) : ?>
	<?php if ( 0 == $recurring_cart->next_payment_date ) : ?>
		<?php continue; ?>
	<?php endif; ?>
	<?php foreach ( $recurring_cart->get_fees() as $recurring_fee ) : ?>
        <tr class="fee recurring-total">
            <th><span><?php echo esc_html( $recurring_fee->name ); ?></span></th>
            <td><?php wc_cart_totals_fee_html( $recurring_fee ); ?></td>
        </tr>
	<?php endforeach; ?>
<?php endforeach; ?>

<?php if (WFACP_Common::get_tax_display_mode() === 'excl' ) : ?>
	<?php if ( get_option( 'woocommerce_tax_total_display' ) === 'itemized' ) : ?>

		<?php foreach ( WC()->cart->get_taxes() as $tax_id => $tax_total ) : ?>
			<?php foreach ( $recurring_carts as $recurring_cart_key => $recurring_cart ) : ?>
				<?php if ( 0 == $recurring_cart->next_payment_date ) : ?>
					<?php continue; ?>
				<?php endif; ?>
				<?php foreach ( $recurring_cart->get_tax_totals() as $recurring_code => $recurring_tax ) : ?>
					<?php if ( ! isset( $recurring_tax->tax_rate_id ) || $recurring_tax->tax_rate_id !== $tax_id ) {
						continue;
					} ?>
                    <tr class="tax-rate tax-rate-<?php echo esc_attr( sanitize_title( $recurring_code ) ); ?> recurring-total">
						<?php if ( $display_th ) : $display_th = false; ?>
                            <th><span><?php echo esc_html( $recurring_tax->label ); ?></span></th>
                            <td data-title="<?php echo esc_attr( $recurring_tax->label ); ?>"><?php echo wp_kses_post( wcs_cart_price_string( $recurring_tax->formatted_amount, $recurring_cart ) ); ?></td>
						<?php else : ?>
                            <th></th>
                            <td><?php echo wp_kses_post( wcs_cart_price_string( $recurring_tax->formatted_amount, $recurring_cart ) ); ?></td>
						<?php endif; ?>
                    </tr>
				<?php endforeach; ?>
			<?php endforeach; ?>
			<?php $display_th = true; ?>
		<?php endforeach; ?>

	<?php else : ?>

		<?php foreach ( $recurring_carts as $recurring_cart_key => $recurring_cart ) : ?>
			<?php if ( 0 == $recurring_cart->next_payment_date ) : ?>
				<?php continue; ?>
			<?php endif; ?>
            <tr class="tax-total recurring-total">
				<?php if ( $display_th ) : $display_th = false; ?>
                    <th><span><?php echo esc_html( WC()->countries->tax_or_vat() ); ?></span></th>
                    <td data-title="<?php echo esc_attr( WC()->countries->tax_or_vat() ); ?>"><?php echo wp_kses_post( wcs_cart_price_string( $recurring_cart->get_taxes_total(), $recurring_cart ) ); ?></td>
				<?php else : ?>
                    <th></th>
                    <td><?php echo wp_kses_post( wcs_cart_price_string( $recurring_cart->get_taxes_total(), $recurring_cart ) ); ?></td>
				<?php endif; ?>
            </tr>
		<?php endforeach; ?>
		<?php $display_th = true; ?>
	<?php endif; ?>
<?php endif; ?>

<?php foreach ( $recurring_carts as $recurring_cart_key => $recurring_cart ) : ?>
	<?php if ( 0 == $recurring_cart->next_payment_date ) : ?>
		<?php continue; ?>
	<?php endif; ?>
    <tr class="order-total recurring-total">
		<?php if ( $display_th ) : $display_th = false; ?>
            <th rowspan="<?php echo esc_attr( $carts_with_multiple_payments ); ?>"><span><?php echo $total_title; ?></span></th>
            <td data-title="<?php echo $total_title; ?>"><?php wcs_cart_totals_order_total_html( $recurring_cart ); ?></td>
		<?php else : ?>
            <td><?php wcs_cart_totals_order_total_html( $recurring_cart ); ?></td>
		<?php endif; ?>
    </tr>
<?php endforeach; ?>
