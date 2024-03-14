<?php
if ( ! defined( 'WFACP_TEMPLATE_DIR' ) ) {
	return '';
}
$args = WC()->session->get( 'wfacp_order_total_' . WFACP_Common::get_id(), [] );

$colspan_attr = '';
if ( apply_filters( 'wfacp_cart_show_product_thumbnail', false ) ) {
	$colspan_attr1    = ' colspan="2"';
	$colspan_attr     = apply_filters( 'wfacp_order_summary_cols_span', $colspan_attr1 );
	$cellpadding_attr = ' cellpadding="20"';
}


$show_breakups = '';

if ( isset( $args['default'] ) ) {
	$show_breakups = wc_string_to_bool( $args['default'] );
}
do_action( 'wfacp_before_order_total_field' );


$classes = isset( $args['cssready'] ) ? implode( ' ', $args['cssready'] ) : '';

?>
<div class="wfacp_order_total wfacp_order_total_field wfacp_clear <?php echo $classes; ?>" id="order_total_field" <?php echo WFACP_Common::get_fragments_attr() ?> >
	<?php


	if ( true == $show_breakups ) {

		$colspan = 'colspan="2"';
		$colspan = apply_filters( 'wfacp_order_total_cols_span', $colspan )

		?>
        <table class="wfacp_subtotal_wrap">
            <tbody>

            <tr class="wfacp_order_subtotal">
                <td> <?php _e( 'Subtotal', 'woocommerce' ); ?></td>
                <td><?php echo wc_price( WC()->cart->get_subtotal() ); ?></td>
            </tr>

			<?php
			$have_coupon = WC()->cart->get_coupons();
			if ( ! empty( $have_coupon ) ) {
				foreach ( $have_coupon as $code => $coupon ) {
					?>
                    <tr class="wfacp_order_coupon cart-discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
                        <td class="wfacp_order_total_label"><span><?php wc_cart_totals_coupon_label( $coupon ); ?></span></td>
                        <td class="wfacp_order_total_value"><?php wc_cart_totals_coupon_html( $coupon ); ?></td>
                    </tr>
					<?php
				}

			}

			$have_fees = WC()->cart->get_fees();
			if ( ! empty( $have_fees ) ) {
				foreach ( $have_fees as $fee ) { ?>
                    <tr class="wfacp_order_fee fee">
                        <td class="wfacp_order_total_label" <?php echo $colspan_attr; ?>><?php echo esc_html( $fee->name ); ?></td>
                        <td class="wfacp_order_total_value"><?php wc_cart_totals_fee_html( $fee ); ?></td>
                    </tr>
					<?php
				}
			}
			?>
            </tbody>
        </table>
		<?php
		if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) {
			$instance = wfacp_template();
			$method   = $instance->have_shipping_method();
			if ( false === $method ) {
				do_action( 'woocommerce_review_order_before_shipping' );
			}
			echo "<table class='wfacp_shipping_wrap wfacp_order_total_shipping_table'>";
			WFACP_Common::wc_cart_totals_shipping_html( $colspan_attr );
			echo "</table>";
			if ( false === $method ) {
				do_action( 'woocommerce_review_order_after_shipping' );
			}
		}

		if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) { ?>

			<?php if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) {
				if ( count( WC()->cart->get_tax_totals() ) > 0 ) {
					?>
                    <table class="wfacp_tax_wrap yes">
                        <tbody>
						<?php foreach ( WC()->cart->get_tax_totals() as $code => $tax ) : ?>
                            <tr class="tax-rate tax-rate-<?php echo sanitize_title( $code ); ?>">
                                <td class="wfacp_order_total_label"<?php echo $colspan_attr; ?>><?php echo esc_html( $tax->label ); ?></td>
                                <td class="wfacp_order_total_value"><?php echo wp_kses_post( $tax->formatted_amount ); ?></td>
                            </tr>
						<?php endforeach; ?>
                        </tbody>
                    </table>
					<?php
				}
			} else { ?>
                <table class="wfacp_tax_wrap no">
                    <tbody>
                    <tr class="tax-total">
                        <td class="wfacp_order_total_label"<?php echo $colspan_attr; ?>><?php echo esc_html( WC()->countries->tax_or_vat() ); ?></td>
                        <td class="wfacp_order_total_value"><?php wc_cart_totals_taxes_total_html(); ?></td>
                    </tr>
                    </tbody>
                </table>
			<?php } ?>

			<?php
		}
	}
	?>
    <table class="wfacp_order_total_wrap">
        <tbody>
        <tr>
            <td><?php echo isset( $args['label'] ) ? $args['label'] : __( 'Order Total', 'funnel-builder' ); ?></td>
            <td class="wfacp_wc_cart_totals_order_total_html"><?php wc_cart_totals_order_total_html(); ?></td>
        </tr>
        </tbody>
    </table>
</div>
<?php
do_action( 'wfacp_after_order_total_field' );
?>
