<?php
if ( ! defined( 'WFACP_TEMPLATE_DIR' ) ) {
	return '';
}
$instance     = wfacp_template();
$data         = $instance->get_checkout_fields();
$field        = isset( $data['advanced']['order_summary'] ) ? $data['advanced']['order_summary'] : [];
$colspan_attr = '';
unset( $data );
if ( apply_filters( 'wfacp_cart_show_product_thumbnail', false ) ) {
	$colspan_attr1    = '';
	$colspan_attr     = apply_filters( 'wfacp_order_summary_cols_span', $colspan_attr1 );
	$cellpadding_attr = ' cellpadding="20"';
}
$field        = apply_filters( 'wfacp_before_order_summary_html', $field );
$allow_delete = isset( $field['allow_delete'] ) ? wc_string_to_bool( $field['allow_delete'] ) : false;
$total_col    = 2;
$wc_version   = wc()->version;
$args         = WC()->session->get( 'wfacp_order_summary_' . WFACP_Common::get_id(), $field );
$classes      = isset( $args['cssready'] ) ? implode( ' ', $args['cssready'] ) : '';
add_filter( 'wp_get_attachment_image_attributes', 'WFACP_Common::remove_src_set' );
$tax_enabled = "";
if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) {
	$tax_enabled = "wfacp_tax_enabled";
}
?>
    <div class="wfacp_order_summary wfacp_wrapper_start wfacp_order_sec wfacp_order_summary_sec <?php echo $classes . ' ' . $tax_enabled; ?>" id="order_summary_field" <?php echo WFACP_Common::get_fragments_attr() ?> >
		<?php do_action( 'wfacp_before_order_summary', $field, $instance ); ?>
        <div class="wfacp_order_summary_container">
            <label class="wfacp-order-summary-label  "><?php echo isset( $field['label'] ) ? $field['label'] : __( 'Order Summary', 'woofunnels-aero-checkout' ); ?></label>
            <table class="shop_table woocommerce-checkout-review-order-table <?php echo $instance->get_template_slug(); ?>">
                <thead>
                <tr>
                    <th class="product-name-area">
						<?php
						$hideImageCls = '';
						if ( apply_filters( 'wfacp_cart_show_product_thumbnail', false ) ) {
							$total_col ++;
							echo '<div class="product-img">';
							echo ' </div>';
							$hideImageCls = 'wfacp_summary_img_true';
						}
						?>
                        <div class="product-name <?php echo $hideImageCls; ?>">
							<?php echo apply_filters( 'wfacp_order_summary_column_item_heading', __( 'Product', 'woocommerce' ) ); ?>
                        </div>
                    </th>
                    <th class="product-total"><?php echo apply_filters( 'wfacp_order_summary_column_total_heading', __( 'Total', 'woocommerce' ) ); ?></th>
                </tr>
                </thead>
                <tbody>
				<?php
				do_action( 'woocommerce_review_order_before_cart_contents' );
				foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
					$_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
					if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
						?>
                        <tr class="<?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">
                            <td class="product-name-area">
								<?php
								$hideImageCls = '';
								if ( apply_filters( 'wfacp_cart_show_product_thumbnail', false ) ) {
									$hideImageCls = 'wfacp_summary_img_true';
									$thumbnail    = WFACP_Common::get_product_image( $_product, [ 100, 100 ], $cart_item, $cart_item_key );
									?>
                                    <div class="product-image">
                                        <div class="wfacp-pro-thumb">
                                            <div class="wfacp-qty-ball">
                                                <div class="wfacp-qty-count"><span class="wfacp-pro-count"><?php echo $cart_item['quantity']; ?></span></div>
                                            </div>
											<?php echo $thumbnail; ?>
                                        </div>
                                    </div>
								<?php } ?>
                                <div class="product-name  <?php echo $hideImageCls; ?> ">
                                    <span class="wfacp_order_summary_item_name">
                                    <?php echo apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ); ?>
                                </span>
									<?php
									echo apply_filters( 'woocommerce_checkout_cart_item_quantity', ' <strong class="product-quantity">' . sprintf( '&times; %s', $cart_item['quantity'] ) . '</strong>', $cart_item, $cart_item_key );
									if ( apply_filters( 'wfacp_allow_woocommerce_after_cart_item_name_order_summary', false, $cart_item, $cart_item_key ) ) {
										/**
										 * added in 2.0.0
										 */
										do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key );
									}
									if ( version_compare( $wc_version, '3.3.0', '>=' ) ) {
										echo wc_get_formatted_cart_item_data( $cart_item );
									} else {
										echo WC()->cart->get_item_data( $cart_item );
									}
									?>
                                </div>
                            </td>
                            <td class="product-total">
                                <div class="wfacp_order_summary_item_total">
									<?php
									if ( in_array( $_product->get_type(), WFACP_Common::get_subscription_product_type() ) ) {
										echo WFACP_Common::display_subscription_price( $_product, $cart_item, $cart_item_key );
									} else {
										if ( true == apply_filters( 'wfacp_woocommerce_cart_item_subtotal_except_subscription', true, $_product, $cart_item, $cart_item_key ) ) {
											echo apply_filters( 'woocommerce_cart_item_subtotal', WFACP_Common::get_product_subtotal( $_product, $cart_item ), $cart_item, $cart_item_key );
										} else {
											do_action( 'wfacp_woocommerce_cart_item_subtotal_except_subscription_placeholder', $_product, $cart_item, $cart_item_key );
										}
									}
									?>
                                </div>
								<?php
								WFACP_Common::delete_cart_item_link( $allow_delete, $cart_item_key, $cart_item );
								?>
                            </td>
                        </tr>
						<?php
					}
				}
				do_action( 'woocommerce_review_order_after_cart_contents', $total_col );
				?>
                </tbody>
                <tfoot>
                <tr class="cart-subtotal">
                    <th <?php echo $colspan_attr; ?>><span><?php _e( 'Subtotal', 'woocommerce' ); ?></span></th>
                    <td><?php wc_cart_totals_subtotal_html(); ?></td>
                </tr>
				<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
                    <tr class="cart-discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
                        <th <?php echo $colspan_attr; ?>><span><?php $instance->wc_cart_totals_coupon_label( $coupon ); ?></span></th>
                        <td><?php wc_cart_totals_coupon_html( $coupon );	do_action( 'wfacp_after_coupon_html', $coupon ); ?></td>
                    </tr>
				<?php endforeach; ?>
				<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
                    <tr class="fee">
                        <th <?php echo $colspan_attr; ?>><?php echo esc_html( $fee->name ); ?></th>
                        <td><?php wc_cart_totals_fee_html( $fee ); ?></td>
                    </tr>
				<?php endforeach; ?>
				<?php
				if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) {
					$instance = wfacp_template();
					$method   = $instance->have_shipping_method();
					if ( false === $method ) {
						do_action( 'woocommerce_review_order_before_shipping' );
					}
					// if Shipping method field not exist in form
					if ( false === $method ) {
						wc_cart_totals_shipping_html();
					} else {
						WFACP_Common::wc_cart_totals_shipping_html( $colspan_attr );
					}
					if ( false === $method ) {
						do_action( 'woocommerce_review_order_after_shipping' );
					}
				}
				?>
				<?php if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) : ?>
					<?php if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) : ?>
						<?php foreach ( WC()->cart->get_tax_totals() as $code => $tax ) : ?>
                            <tr class="tax-rate tax-rate-<?php echo sanitize_title( $code ); ?>">
                                <th <?php echo $colspan_attr; ?>><?php echo esc_html( $tax->label ); ?></th>
                                <td><?php echo wp_kses_post( $tax->formatted_amount ); ?></td>
                            </tr>
						<?php endforeach; ?>
					<?php else : ?>
                        <tr class="tax-total">
                            <th <?php echo $colspan_attr; ?>><?php echo esc_html( WC()->countries->tax_or_vat() ); ?></th>
                            <td><?php wc_cart_totals_taxes_total_html(); ?></td>
                        </tr>
					<?php endif; ?>
				<?php endif; ?>
				<?php do_action( 'woocommerce_review_order_before_order_total' ); ?>
                <tr class="order-total">
                    <th <?php echo $colspan_attr; ?>><span><?php _e( 'Total', 'woocommerce' ); ?></span></th>
                    <td><?php wc_cart_totals_order_total_html(); ?></td>
                </tr>
				<?php do_action( 'woocommerce_review_order_after_order_total' ); ?>
                </tfoot>
            </table>
        </div>
		<?php do_action( 'wfacp_after_order_summary', $field, $instance ); ?>
    </div>
<?php
remove_filter( 'wp_get_attachment_image_attributes', 'WFACP_Common::remove_src_set' );
?>