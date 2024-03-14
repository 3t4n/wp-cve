<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Shipment Tracking
 *
 * Shows tracking information in the HTML order email
**/
if ( $orders ) : 
	$text_align = is_rtl() ? 'right' : 'left'; 
	?>
	<table class="late_shipment" ellspacing="0" cellpadding="6" width="100%" style="border: 1px solid #e0e0e0;border-collapse: collapse;">
		<tr>
			<th><?php esc_html_e( 'Order Number', 'trackship-for-woocommerce' ); ?></th>
			<th><?php esc_html_e( 'Shipment status', 'trackship-for-woocommerce' ); ?></th>
			<th><?php esc_html_e( 'Shipping provider', 'trackship-for-woocommerce' ); ?></th>
			<th><?php esc_html_e( 'Tracking Number', 'trackship-for-woocommerce' ); ?></th>
			<th><?php esc_html_e( 'Shipping days', 'trackship-for-woocommerce' ); ?></th>
		</tr>
		<?php
		foreach ( $orders as $key1 => $val1 ) {
			//Get tracking url and Formatted tracking provider
			$tracking_items = trackship_for_woocommerce()->get_tracking_items( $val1->order_id );
			foreach ( $tracking_items as $key2 => $val2 ) {
				if ( $val2['tracking_number'] == $val1->tracking_number ) {
					$tracking_url = $val2['tracking_page_link'] ?  $val2['tracking_page_link'] : $val2['formatted_tracking_link'];
					$shipping_provider = $val2['formatted_tracking_provider'] ? $val2['formatted_tracking_provider'] : $val2['tracking_provider'];
				}
			}
			$shipment_status = apply_filters( 'trackship_status_filter', $val1->shipment_status );
			$order_url = wc_get_order( $val1->order_id )->get_edit_order_url();
			?>
			<tr>
				<td><a href="<?php echo esc_html( $order_url ); ?>"><?php echo esc_html( $val1->order_number ); ?></a></td>
				<td><?php echo esc_html( $shipment_status ); ?></td>
				<td><?php echo esc_html( $shipping_provider ); ?></td>
				<td><a href="<?php echo esc_url( $tracking_url ); ?>"><?php echo esc_html( $val1->tracking_number ); ?></a></td>
				<td><?php echo esc_html( $val1->shipping_length ) . ' days'; ?></td>
			</tr>
		<?php } ?>
	</table>
	<div>
		<a href="<?php echo esc_url( admin_url() ); ?>admin.php?page=trackship-shipments&status=late_shipment"><button class="all_late_ship">View all late shipments</button></a>
	</div>	
	<style>
		table.late_shipment tr td, table.late_shipment tr th{border:1px solid #e0e0e0;}
		button.all_late_ship {font-weight: normal;border-radius: 3px;text-decoration: none;color: #fff;background: #3c4758;margin-top: 15px;padding: 12px 20px;border: 0;}
	</style>
<?php
endif;
