<?php
/**
 * Order details
 * 
 */

	defined( 'ABSPATH' ) || exit;

	$order = wc_get_order( $order_id );

	if ( ! $order ) {
		return;
	}

	$order_items           = $order->get_items( apply_filters( 'woocommerce_purchase_order_item_types', 'line_item' ) );
	$show_purchase_note    = $order->has_status( apply_filters( 'woocommerce_purchase_note_order_statuses', array( 'completed', 'processing' ) ) );
	$show_customer_details = is_user_logged_in() && $order->get_user_id() === get_current_user_id() && shop_ready_gl_get_setting('woo_ready_enable_thankyou_billing_address','yes') == 'yes';


	$downloads             = $order->get_downloadable_items();
	$show_downloads        = $order->has_downloadable_item() && $order->is_download_permitted();
	$woo_ready_dl_show 	   = shop_ready_gl_get_setting('woo_ready_enable_thankyou_order_download','yes') == 'yes' ? true :false;
	$woo_ready_dl_title    = shop_ready_gl_get_setting('woo_ready_enable_thankyou_order_download_title','yes') == 'yes' ? true :false;

	if ( $show_downloads && $woo_ready_dl_show ) {
		wc_get_template(
			'order/order-downloads.php',
			array(
				'downloads'  => $downloads,
				'show_title' => $woo_ready_dl_title,
			)
		);
	}

?>
<section class="woocommerce-order-details woo-ready-order-details">
    <?php do_action( 'woocommerce_order_details_before_order_table', $order ); ?>

    <?php if(shop_ready_gl_get_setting('woo_ready_enable_thankyou_order_details_heading','yes') =='yes'): ?>
    <h2 class="woocommerce-order-details__title">
        <?php echo esc_html(shop_ready_gl_get_setting('woo_ready_thankyou_order_details_heading','Order Details')); ?>
    </h2>
    <?php endif; ?>

    <table class="woocommerce-table woocommerce-table--order-details shop_table order_details">

        <thead>
            <tr>
                <th class="woocommerce-table__product-name product-name">
                    <?php esc_html_e( 'Product', 'shopready-elementor-addon' ); ?></th>
                <th class="woocommerce-table__product-table product-total">
                    <?php esc_html_e( 'Total', 'shopready-elementor-addon' ); ?></th>
            </tr>
        </thead>

        <tbody>

            <?php
			
			do_action( 'woocommerce_order_details_before_order_table_items', $order );

			foreach ( $order_items as $item_id => $item ) {

				$product = $item->get_product();
				wc_get_template(
					'order/order-details-item.php',
					array(
						'order'              => $order,
						'item_id'            => $item_id,
						'item'               => $item,
						'show_purchase_note' => $show_purchase_note,
						'purchase_note'      => $product ? $product->get_purchase_note() : '',
						'product'            => $product,
					)
				);
			}

			do_action( 'woocommerce_order_details_after_order_table_items', $order );

			?>
        </tbody>

        <tfoot>
            <?php
			foreach ( $order->get_order_item_totals() as $key => $total ) {
				?>
            <tr>
                <th scope="row"><?php echo esc_html( $total['label'] ); ?></th>
                <td> <?php echo wp_kses_post( 'payment_method' === $key  ? esc_html( $total['value'] ) :  $total['value'] ); ?>
                </td>
            </tr>
            <?php
			}
			?>
            <?php if ( $order->get_customer_note() ) : ?>
            <tr>
                <th><?php esc_html_e( 'Note:', 'shopready-elementor-addon' ); ?></th>
                <td><?php echo wp_kses_post( nl2br( wptexturize( $order->get_customer_note() ) ) ); ?></td>
            </tr>
            <?php endif; ?>
        </tfoot>
    </table>

    <?php do_action( 'woocommerce_order_details_after_order_table', $order ); ?>
</section>

<?php
/**
 * Action hook fired after the order details.
 *
 * @since 4.4.0
 * @param WC_Order $order Order data.
 */
do_action( 'woocommerce_after_order_details', $order );

if ( $show_customer_details ) {
	wc_get_template( 'order/order-details-customer.php', array( 'order' => $order ) );
}