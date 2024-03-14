<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** checking if woocommerce exists other wise return */
if ( ! function_exists( 'bwfan_is_woocommerce_active' ) || ! bwfan_is_woocommerce_active() ) {
	return;
}

add_action( 'bwfan_output_email_style', function () { ?>
    .bwfan-email-cart-table #template_header {
    width: 100%;
    }

    .bwfan-email-cart-table table {
    border: 2px solid #e5e5e5;
    border-collapse: collapse;
    max-width:700px;
    }

    .bwfan-email-cart-table table tr th, .bwfan-email-cart-table table tr td {
    border: 2px solid #e5e5e5;
    }
<?php } );

$subtotal     = 0;
$subtotal_tax = 0;
$total        = 0;
$text_align   = is_rtl() ? 'text-align:right;' : 'text-align:left;';

$disable_product_thumbnail = BWFAN_Common::disable_product_thumbnail();
$currency                  = is_array( $data ) & isset( $data['currency'] ) ? $data['currency'] : '';
$lang                      = is_array( $data ) & isset( $data['lang'] ) ? $data['lang'] : '';
$colspan                   = ' colspan="2"';
$colspan_foot              = ' colspan="3"';
if ( true === $disable_product_thumbnail ) {
	$colspan      = '';
	$colspan_foot = ' colspan="2"';
}
do_action( 'bwfan_email_setup_locale', $lang );

/** Tax settings */
$tax_string = '';
if ( wc_tax_enabled() ) {
	$tax_string = WC()->countries->tax_or_vat();
}
?>
<div class='bwfan-email-cart-table bwfan-email-table-wrap'>
    <table cellspacing="0" cellpadding="6" border="1" width="100%">
        <thead>
        <tr>
            <th class="td" scope="col" <?php echo $colspan; //phpcs:ignore WordPress.Security.EscapeOutput ?> style="<?php esc_html_e( $text_align ); ?> white-space: nowrap;"><?php esc_html_e( 'Product', 'woocommerce' ); ?></th>
            <th class="td" scope="col" style="width:90px;text-align:center;white-space: nowrap;"><?php esc_html_e( 'Quantity', 'woocommerce' ); ?></th>
            <th class="td" scope="col" style="width:90px;text-align:center;white-space: nowrap;"><?php esc_html_e( 'Price', 'woocommerce' ); ?></th>
        </tr>
        </thead>
        <tbody>

		<?php
		$tax_display = get_option( 'woocommerce_tax_display_cart' );
		if ( false !== $cart ) {
			foreach ( $cart as $item ) :
				$product = isset( $item['data'] ) ? $item['data'] : '';
				if ( empty( $product ) || ! $product instanceof WC_Product ) {
					continue; // don't show items if there is no product
				}
				if ( false === BWFAN_Merge_Tag_Loader::get_data( 'is_preview' ) ) {
					$subtotal     += BWFAN_Common::get_line_subtotal( $item );
					$subtotal_tax += BWFAN_Common::get_line_subtotal_tax( $item );
					$price        = isset( $products_price[ $product->get_id() ] ) ? $products_price[ $product->get_id() ] : null;
					$line_total   = is_null( $price ) ? BWFAN_Common::get_prices_with_tax( $product ) : $price;
				} else {
					$line_total = BWFAN_Common::get_prices_with_tax( $product );
				}
				$total += $line_total;
				?>
                <tr>
					<?php
					if ( false === $disable_product_thumbnail ) {
						?>
                        <td class="image" style="width: 15%;min-width: 40px;">
							<?php echo wp_kses_post( BWFAN_Common::get_product_image( $product, 'thumbnail', false, 100 ) ); ?>
                        </td>
						<?php
					}
					?>
                    <td style="width: 60% !important;">
                        <h4 style="vertical-align:middle; <?php esc_html_e( $text_align ); ?> word-wrap: break-word;">
							<?php echo wp_kses_post( BWFAN_Common::get_name( $product ) ); ?>
                        </h4>
                    </td>
                    <td style="vertical-align:middle; <?php esc_html_e( $text_align ); ?> white-space: nowrap;">
						<?php
						if ( false === BWFAN_Merge_Tag_Loader::get_data( 'is_preview' ) ) {
							esc_html_e( BWFAN_Common::get_quantity( $item ) );
						} else {
							esc_html_e( 1 );
						}
						?>
                    </td>
                    <td style="vertical-align:middle; <?php esc_html_e( $text_align ); ?> white-space: nowrap;">
						<?php echo wp_kses_post( BWFAN_Common::price( $line_total, $currency ) ); ?>
                    </td>
                </tr>

			<?php
			endforeach;
		} else {
			foreach ( $products as $product ) {
				?>
                <tr>
					<?php
					if ( false === $disable_product_thumbnail ) {
						?>
                        <td class="image" width="100">
							<?php echo wp_kses_post( BWFAN_Common::get_product_image( $product, 'thumbnail', false, 100 ) ); ?>
                        </td>
						<?php
					}
					?>
                    <td>
                        <h4 style="vertical-align:middle; <?php esc_html_e( $text_align ); ?> white-space: nowrap;"><?php echo wp_kses_post( 'Test Product' ); ?></h4>
                    </td>
                    <td style="vertical-align:middle; <?php esc_html_e( $text_align ); ?> white-space: nowrap;">1</td>
                    <td style="vertical-align:middle; white-space: nowrap;"><?php echo wp_kses_post( BWFAN_Common::price( 0, $currency ) ); ?></td>
                </tr>
				<?php
			}
		}
		?>
        </tbody>
        <tfoot>
		<?php if ( is_array( $data ) && isset( $data['shipping_total'] ) && ! empty( $data['shipping_total'] ) && '0.00' !== $data['shipping_total'] ) {
			$shipping_total = $data['shipping_total'];
			$subtotal_tax   += $data['shipping_tax_total'];
			$total          += $shipping_total;
			if ( wc_tax_enabled() && $tax_display !== 'excl' ) {
				$shipping_total += $data['shipping_tax_total'];
				$total          += $data['shipping_tax_total'];
			}
			?>
            <tr>
                <th scope="row" <?php echo $colspan_foot; //phpcs:ignore WordPress.Security.EscapeOutput ?> style=" <?php esc_html_e( $text_align ); ?>"><?php esc_html_e( 'Shipping', 'woocommerce' ); ?>
					<?php if ( wc_tax_enabled() && $tax_display !== 'excl' ):
						$string = sprintf( '%s %s', BWFAN_Common::price( esc_attr( $data['shipping_tax_total'] ), $currency ), $tax_string );
						?>
                        <small><?php echo wp_kses_post( sprintf( __( '(includes %s)', 'woocommerce' ), $string ) ) ?></small>
					<?php endif; ?>
                </th>
                <td><?php echo BWFAN_Common::price( esc_attr( $shipping_total ), $currency ); //phpcs:ignore WordPress.Security.EscapeOutput ?></td>
            </tr>
		<?php } ?>

		<?php if ( is_array( $data ) && isset( $data['coupons'] ) && ! empty( $data['coupons'] ) ): ?>
            <tr>
				<?php
				$discount     = 0;
				$coupon_names = array();
				foreach ( $data['coupons'] as $coupon_name => $coupon ) {
					$discount       += $coupon['discount_incl_tax'];
					$coupon_names[] = $coupon_name;
				}
				$coupon_names = implode( ', ', $coupon_names );
				$coupon_names = apply_filters( 'bwfan_modify_coupon_names', $coupon_names, $data['coupons'] );
				$total        = isset( $data['total'] ) ? $data['total'] : 0;
				?>
                <th scope="row" <?php echo $colspan_foot; //phpcs:ignore WordPress.Security.EscapeOutput ?> style="<?php esc_html_e( $text_align ); ?>">
					<?php esc_html_e( 'Discount:', 'woocommerce' ); ?>
					<?php if ( ! empty( $coupon_names ) ) { ?>
                        <small><?php echo wp_kses_post( $coupon_names ) ?></small>
					<?php } ?>
                </th>
                <td><?php echo '-' . BWFAN_Common::price( esc_attr( $discount ), $currency ); //phpcs:ignore WordPress.Security.EscapeOutput ?></td>
            </tr>
		<?php endif; ?>

		<?php if ( wc_tax_enabled() && $tax_display === 'excl' && $subtotal_tax ):
			$total += $subtotal_tax;
			?>
            <tr>
                <th scope="row" <?php echo $colspan_foot; //phpcs:ignore WordPress.Security.EscapeOutput
				?> style="<?php esc_html_e( $text_align ); ?>"><?php esc_html_e( $tax_string ); ?></th>
                <td><?php echo wp_kses_post( BWFAN_Common::price( $subtotal_tax, $currency ) ); ?></td>
            </tr>
		<?php endif; ?>

        <tr>
            <th scope="row" <?php echo $colspan_foot; //phpcs:ignore WordPress.Security.EscapeOutput ?> style="<?php esc_html_e( $text_align ); ?>">
				<?php esc_html_e( 'Total', 'woocommerce' ); ?>
				<?php if ( wc_tax_enabled() && $tax_display !== 'excl' ):
					$string = sprintf( '%s %s', BWFAN_Common::price( $subtotal_tax, $currency ), $tax_string );
					?>
                    <small><?php echo wp_kses_post( sprintf( __( '(includes %s)', 'woocommerce' ), $string ) ); ?></small>
				<?php endif; ?>
            </th>
            <td><?php echo wp_kses_post( BWFAN_Common::price( $total, $currency ) ); ?></td>
        </tr>
        </tfoot>

    </table>
</div>
