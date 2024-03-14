<?php
/**
 * My Pre-Orders table
 *
 * @package YITH\PreOrder\Templates\MyAccount
 * @since   1.0.0
 * @author  YITH <plugins@yithemes.com>
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Vars used on this template.
 *
 * @var array $orders Customer's orders that include pre-order products.
 */

$columns = array(
	'image' => '',
	'title' => __( 'Product', 'yith-pre-order-for-woocommerce' ),
	'order' => __( 'Order', 'yith-pre-order-for-woocommerce' ),
	'price' => __( 'Price', 'yith-pre-order-for-woocommerce' ),
	'date'  => __( 'Availability date', 'yith-pre-order-for-woocommerce' ),
);

?>
<?php if ( $orders ) : ?>
	<table class="shop_table shop_table_responsive my_account_orders my_account_my_pre_orders">
		<thead>
		<tr>
			<?php foreach ( $columns as $column_id => $column ) : ?>
				<th id="ywpo-column-<?php echo esc_attr( $column_id ); ?>" class="my_pre_orders-<?php echo esc_attr( $column_id ); ?>"><?php echo esc_html( $column ); ?></th>
			<?php endforeach; ?>
		</tr>
		</thead>
		<tbody>
		<?php
		foreach ( $orders as $order_id ) {
			$_order = wc_get_order( $order_id );
			$items  = $_order->get_items();
			foreach ( $items as $item_id => $item ) {
				$product = $item instanceof WC_Order_Item_Product ? $item->get_product() : false;
				if ( ! $product ) {
					continue;
				}
				$item_is_pre_order = ! empty( $item['ywpo_item_preorder'] ) ? $item['ywpo_item_preorder'] : '';
				if ( apply_filters( 'ywpo_my_pre_orders_show_row', 'yes' === $item_is_pre_order, $item ) ) {
					$is_visible        = $product->is_visible();
					$product_permalink = $is_visible ? $product->get_permalink() : '';
					?>
					<tr>
						<td data-title="<?php esc_html_e( 'Image', 'yith-pre-order-for-woocommerce' ); ?>">
							<?php echo wp_kses_post( $product->get_image( array( 120, 120 ) ) ); ?>
						</td>
						<td data-title="<?php esc_html_e( 'Product', 'yith-pre-order-for-woocommerce' ); ?>">
							<a href="<?php echo esc_attr( $product_permalink ); ?>"><?php echo esc_html( $product->get_title() ); ?></a>
							<?php
							wc_display_item_meta( $item );
							wc_display_item_downloads( $item );
							?>
						</td>
						<td data-title="<?php esc_html_e( 'Order', 'yith-pre-order-for-woocommerce' ); ?>">
							<?php
							$url       = $_order->get_view_order_url();
							$link_text = _x( '#', 'hash before order number', 'yith-pre-order-for-woocommerce' ) . $_order->get_order_number();
							?>
							<a href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( $link_text ); ?></a>
						</td>
						<td data-title="<?php esc_html_e( 'Price', 'yith-pre-order-for-woocommerce' ); ?>">
							<?php echo wp_kses_post( $_order->get_formatted_line_subtotal( $item ) ); ?>
						</td>
						<td data-title="<?php esc_attr_e( 'Availability date', 'yith-pre-order-for-woocommerce' ); ?>">
							<?php
							$timestamp = ! empty( $item['ywpo_item_for_sale_date'] ) ? $item['ywpo_item_for_sale_date'] : '';

							if ( $timestamp ) {
								$date_output = apply_filters(
									'ywpo_order_item_date_output',
									'<span class="preorder-date">' . ywpo_print_date( $timestamp ) . '</span>',
									$timestamp,
									$item,
									$_order
								);

								$time_output = apply_filters(
									'ywpo_order_item_time_output',
									'<span class="preorder-time">' . ywpo_print_time( $timestamp ) . '</span>',
									$timestamp,
									$item,
									$_order
								);

								$datetime_output = apply_filters(
									'ywpo_order_item_datetime_output',
									$date_output . '<span>&nbsp;&mdash;&nbsp;</span>' . $time_output,
									$timestamp,
									$date_output,
									$time_output,
									$item,
									$_order
								);

								$class = 'yes' === get_option( 'yith_wcpo_enable_automatic_date_formatting', 'yes' ) ?
									'preorder-my-account' :
									'preorder-my-account-no-auto';

								$release_date_output = apply_filters(
									'ywpo_order_item_release_date_output',
									'<div class="' . $class . '" data-time="' . $timestamp . '">' . $datetime_output . '</div>',
									$timestamp,
									$date_output,
									$time_output,
									$datetime_output,
									$item,
									$_order
								);
								echo wp_kses_post( $release_date_output );
							} else {
								echo esc_html__( 'N/A', 'yith-pre-order-for-woocommerce' );
							}
							?>
						</td>
					</tr>
					<?php
				}
			}
		}
		?>
		</tbody>
	</table>
	<style>
		.my_account_my_pre_orders .yith-wcbm-badge {
			display: none;
		}
	</style>
<?php else : ?>
	<div><?php esc_html_e( 'No pre-orders found.', 'yith-pre-order-for-woocommerce' ); ?></div>
<?php endif; ?>

<?php
