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
	<table class="on_hold_shipment" ellspacing="0" cellpadding="6" width="100%" style="border: 1px solid #e0e0e0;border-collapse: collapse;">
		<tr>
			<th><?php esc_html_e( 'Order Number', 'trackship-for-woocommerce' ); ?></th>
			<th><?php esc_html_e( 'Shipping provider', 'trackship-for-woocommerce' ); ?></th>
			<th><?php esc_html_e( 'Tracking Number', 'trackship-for-woocommerce' ); ?></th>
			<th><?php esc_html_e( 'Shipping days', 'trackship-for-woocommerce' ); ?></th>
		</tr>
		<?php
		foreach ( $orders as $key => $val ) {
			
			$tracking_url = trackship_for_woocommerce()->actions->get_tracking_page_link( $val->order_id, $val->tracking_number );
			$shipping_provider = trackship_for_woocommerce()->actions->get_provider_name( $val->shipping_provider );
			$order_url = wc_get_order( $val->order_id )->get_edit_order_url();
			?>
			<tr>
				<td><a href="<?php echo esc_html( $order_url ); ?>"><?php echo esc_html( $val->order_number ); ?></a></td>
				<td><?php echo esc_html( $shipping_provider ); ?></td>
				<td><a href="<?php echo esc_url( $tracking_url ); ?>"><?php echo esc_html( $val->tracking_number ); ?></a></td>
				<td><?php echo esc_html( $val->shipping_length ) . ' days'; ?></td>
			</tr>
		<?php } ?>
	</table>
	<div>
		<a href="<?php echo esc_url( admin_url() ); ?>admin.php?page=trackship-shipments&status=on_hold"><button class="all_on_hold_ship">View all On Hold shipments</button></a>
	</div>	
	<style>
		table.on_hold_shipment tr td, table.on_hold_shipment tr th{border:1px solid #e0e0e0;}
		button.all_on_hold_ship {font-weight: normal;border-radius: 3px;text-decoration: none;color: #fff;background: #3c4758;margin-top: 15px;padding: 12px 20px;border: 0;}
	</style>
<?php
endif;
