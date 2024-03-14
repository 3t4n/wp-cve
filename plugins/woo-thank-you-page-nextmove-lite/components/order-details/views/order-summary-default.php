<?php
defined( 'ABSPATH' ) || exit;

$show_purchase_note    = $order_data->has_status( apply_filters( 'woocommerce_purchase_note_order_statuses', array( 'completed', 'processing' ) ) );
$show_customer_details = is_user_logged_in() && $order_data->get_user_id() === get_current_user_id();
?>
    <div class="xlwcty_Box xlwcty_order_details_default">
		<?php
		echo $this->data->heading ? '<div class="xlwcty_title">' . XLWCTY_Common::maype_parse_merge_tags( $this->data->heading ) . '</div>' : __( 'Order details', 'woocommerce' );
		echo $heading_desc;
		?>
        <table class="woocommerce-table woocommerce-table--order-details shop_table order_details">
            <thead>
            <tr>
                <th class="woocommerce-table__product-name product-name"><?php _e( 'Product', 'woocommerce' ); ?></th>
                <th class="woocommerce-table__product-table product-total"><?php _e( 'Total', 'woocommerce' ); ?></th>
            </tr>
            </thead>
            <tbody>
			<?php
			foreach ( $order_data->get_items() as $item_id => $item ) {
				if ( ! apply_filters( 'woocommerce_order_item_visible', true, $item ) ) {
					continue;
				}

				$product    = apply_filters( 'woocommerce_order_item_product', XLWCTY_Compatibility::get_product_from_item( $order_data, $item ), $item );
				$is_visible = $product && $product->is_visible();

				$purchase_note     = XLWCTY_Compatibility::get_purchase_note( $product );
				$product_permalink = apply_filters( 'woocommerce_order_item_permalink', $is_visible ? $product->get_permalink( $item ) : '', $item, $order_data );
				?>
                <tr class="<?php echo esc_attr( apply_filters( 'woocommerce_order_item_class', 'woocommerce-table__line-item order_item', $item, $order_data ) ); ?>">
                    <td class="woocommerce-table__product-name product-name">
                        <table class="xlwcty_innerTable">
                            <tbody>
                            <tr>
								<?php
								if ( 'yes' === $this->data->display_images ) {
									?>
                                    <td width="100">
										<?php
										$thumbnail = $product->get_image();
										if ( ! $product_permalink ) {
											echo $thumbnail;
										} else {
											printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail );
										}
										?>
                                    </td>
									<?php
								}
								?>
                                <td>
									<?php
									echo apply_filters( 'woocommerce_order_item_name', $product_permalink ? sprintf( '<a href="%s">%s</a>', $product_permalink, XLWCTY_Compatibility::get_productname_from_item( $order_data, $item ) ) : XLWCTY_Compatibility::get_productname_from_item( $order_data, $item ), $item, $is_visible );
									echo apply_filters( 'woocommerce_order_item_quantity_html', ' <strong class="product-quantity">' . sprintf( '&times; %s', XLWCTY_Compatibility::get_qty_from_item( $order_data, $item ) ) . '</strong>', $item );

									do_action( 'woocommerce_order_item_meta_start', $item_id, $item, $order_data, false );

									XLWCTY_Compatibility::get_display_item_meta( $order_data, $item );
									if ( 'inline' === $this->data->downloads ) {
										XLWCTY_Compatibility::get_display_item_downloads( $order_data, $item );
									}

									do_action( 'woocommerce_order_item_meta_end', $item_id, $item, $order_data, false );
									?>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                    <td class="woocommerce-table__product-total product-total">
						<?php echo $order_data->get_formatted_line_subtotal( $item ); ?>
                    </td>
                </tr>
				<?php if ( $show_purchase_note && $purchase_note ) : ?>
                    <tr class="woocommerce-table__product-purchase-note product-purchase-note">
                        <td colspan="3"><?php echo wpautop( do_shortcode( wp_kses_post( $purchase_note ) ) ); ?></td>
                    </tr>
				<?php
				endif;
			}
			do_action( 'woocommerce_order_items_table', $order_data );
			?>
            </tbody>
            <tfoot>
			<?php
			$item_total = $order_data->get_order_item_totals();
			if ( isset( $item_total['order_total'] ) ) {
				$total = $item_total['order_total'];
				unset( $item_total['order_total'] );
				$item_total['order_total'] = $total;
			}
			foreach ( $item_total as $key => $total ) {
				?>
                <tr>
                    <td scope="row"><?php echo $total['label']; ?></td>
                    <td><?php echo $total['value']; ?></td>
                </tr>
				<?php
			}
			?>
            </tfoot>
        </table>
		<?php
		do_action( 'woocommerce_before_order_items_below_desc', $order_data );

		echo $after_desc ? $after_desc : '';

		do_action( 'woocommerce_after_order_items_below_desc', $order_data );

		if ( 'section' === $this->data->downloads ) {
			$downloads      = $order_data->get_downloadable_items();
			$show_downloads = $order_data->has_downloadable_item() && $order_data->is_download_permitted();

			if ( $show_downloads ) {
				include __DIR__ . '/order-downloads-custom.php';
			}
		}
		?>
    </div>
<?php
do_action( 'woocommerce_order_details_after_order_table', $order_data );
