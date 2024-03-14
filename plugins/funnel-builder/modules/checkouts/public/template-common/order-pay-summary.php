<?php


if ( ! defined( 'WFACP_TEMPLATE_DIR' ) ) {
	return '';
}
if ( ! $order instanceof WC_Order ) {
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
$field      = apply_filters( 'wfacp_before_order_summary_html', $field );
$total_col  = 2;
$wc_version = wc()->version;
$args       = WC()->session->get( 'wfacp_order_summary_' . WFACP_Common::get_id(), $field );
$classes    = isset( $args['cssready'] ) ? implode( ' ', $args['cssready'] ) : '';
add_filter( 'wp_get_attachment_image_attributes', 'WFACP_Common::remove_src_set' );

$totals = $order->get_order_item_totals();
?>

    <div class="wfacp_order_summary wfacp_wrapper_start wfacp_order_sec <?php echo $classes ?>" id="order_summary_field">
        <div class="wfacp_order_summary_container">
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
				if ( count( $order->get_items() ) > 0 ) {
					foreach ( $order->get_items() as $cart_item_key => $cart_item ) {
						if ( ! apply_filters( 'woocommerce_order_item_visible', true, $cart_item ) ) {
							continue;
						}
						$data = $cart_item->get_data();

						$object_id = $data['product_id'];
						if ( $data['variation_id'] > 0 ) {
							$object_id = $data['variation_id'];
						}
						$_product = WFACP_Common::wc_get_product( $object_id, $cart_item_key );
						?>
                        <tr class="<?php echo esc_attr( apply_filters( 'woocommerce_order_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">

                            <td class="product-name-area">
								<?php
								$hideImageCls = '';
								if ( $_product instanceof WC_Product && apply_filters( 'wfacp_cart_show_product_thumbnail', false ) ) {
									$hideImageCls = 'wfacp_summary_img_true';
									$thumbnail    = WFACP_Common::get_product_image( $_product, [ 100, 100 ], $cart_item, $cart_item_key );
									$thumbnail    = apply_filters( 'wfacp_cart_image', $thumbnail, $_product );
									?>
                                    <div class="product-image">
                                        <div class="wfacp-pro-thumb">
                                            <div class="wfacp-qty-ball">
                                                <div class="wfacp-qty-count"><span class="wfacp-pro-count"><?php echo $cart_item->get_quantity(); ?></span></div>
                                            </div>
											<?php echo $thumbnail; ?>
                                        </div>
                                    </div>
								<?php } ?>

                                <div class="product-name  <?php echo $hideImageCls; ?> ">
                                    <span class="wfacp_order_summary_item_name"><?php echo apply_filters( 'woocommerce_order_item_name', esc_html( $cart_item->get_name() ), $cart_item, false ); ?></span>
									<?php
									echo apply_filters( 'woocommerce_order_item_quantity_html', ' <strong class="product-quantity">' . sprintf( '&times; %s', $cart_item->get_quantity() ) . '</strong>', $cart_item, $cart_item_key );
									wc_display_item_meta( $cart_item );
									do_action( 'woocommerce_order_item_meta_end', $cart_item_key, $cart_item, $order, false );
									?>
                                </div>
                            </td>
                            <td class="product-total">
								<?php echo $order->get_formatted_line_subtotal( $cart_item ); ?>
                            </td>
                        </tr>
						<?php

					}
				}
				?>
                </tbody>
				<?php if ( $totals ) { ?>
                    <tfoot>
					<?php foreach ( $totals as $total ) {
						?>
                        <tr class="order-total">
                            <th <?php echo $colspan_attr; ?>><span><?php echo $total['label']; ?></span></th>
                            <td><?php echo $total['value']; ?></td>
                        </tr>
					<?php } ?>
                    </tfoot>
				<?php } ?>
            </table>
        </div>
    </div>
<?php
remove_filter( 'wp_get_attachment_image_attributes', 'WFACP_Common::remove_src_set' );
?>