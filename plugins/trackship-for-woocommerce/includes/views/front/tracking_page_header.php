<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="tracking-header">
	<?php
	// to be removed after 2-3 version - action has been added in 1.3.4 -- action trackship_tracking_header_before
	do_action( 'trackship_tracking_header_before', $order->get_id(), $tracker, $provider_name, $tracking_number );
	$row = trackship_for_woocommerce()->actions->get_shipment_row( $order->get_id(), $tracking_number );
	$tracking_page_link = trackship_for_woocommerce()->actions->get_tracking_page_link( $order->get_id(), $tracking_number );
	?>

	<div class="tracking_number_wrap">

		<div style="display: flex;">
			<?php if ( ! $hide_tracking_provider_image && $provider_image ) { ?>
				<div class="provider_image_div" >
					<img class="provider_image" src="<?php echo esc_url( $provider_image ); ?>">
				</div>
			<?php } ?>

			<div class="tracking_number_div">
				<ul>
					<li>
						<span class="tracking_page_provider_name"><?php echo esc_html( apply_filters( 'ast_provider_title', $provider_name ) ); ?></span>
						<?php if ( $ts_link_to_carrier && $tracking_link ) { ?>
							<a href="<?php echo esc_url( $tracking_link ); ?>" target="blank"><strong><?php esc_html_e( $tracking_number ); ?></strong></a>	
						<?php } else { ?>
							<strong><?php esc_html_e( $tracking_number ); ?></strong>
						<?php } ?>
					</li>
					<?php if ( !$hide_last_mile && isset($row->delivery_number) && $row->delivery_number ) { ?>
						<li class="last_mile_tracking_number">
							<span><?php esc_html_e( 'Delivery tracking Number', 'trackship-for-woocommerce' ); ?></span>
							<strong> <?php echo esc_html( $row->delivery_number ); ?></strong>
						</li>
					<?php } ?>
				</ul>
			</div>
		</div>

		<div style="display: flex;flex-direction: column;">
			<span class="wc_order_id">
				<?php esc_html_e( 'Order', 'trackship-for-woocommerce' ); ?> 
				<?php if ( $order->get_customer_id() ) { ?>
					<a href="<?php echo esc_url( $order->get_view_order_url() ); ?>" target="_blank"><?php echo esc_html( '#' . $order->get_order_number() ); ?></a>
				<?php } else { ?>
					<?php echo esc_html( '#' . $order->get_order_number() ); ?>
				<?php } ?>
			</span>
			<?php if ( $tracking_page_link && is_admin() && !isset( $_POST['order_tracking_number'] ) ) { ?>
				<span style="margin-top: 5px;">
					<span style="vertical-align: middle;" ><?php esc_html_e( 'Copy Tracking page link', 'trackship-for-woocommerce' ); ?></span>
					<span class="copy_tracking_page trackship-tip" title="Copy the secure link to the Tracking page" data-tracking_page_link=<?php echo esc_url( $tracking_page_link ); ?> >
						<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 20 20" style="enable-background:new 0 0 20 20;" xml:space="preserve"><path d="M19.6,2.8l-2.4-2.4C17,0.1,16.7,0,16.4,0H10C8.6,0,7.5,1.1,7.5,2.5l0,10c0,1.4,1.2,2.5,2.5,2.5h7.5c1.4,0,2.5-1.1,2.5-2.5  V3.6C20,3.3,19.9,3,19.6,2.8z M18.1,12.5c0,0.3-0.3,0.6-0.6,0.6H10c-0.3,0-0.6-0.3-0.6-0.6v-10c0-0.3,0.3-0.6,0.6-0.6h5l0,1.9  C15,4.4,15.6,5,16.3,5h1.8L18.1,12.5L18.1,12.5z M10.6,17.5c0,0.3-0.3,0.6-0.6,0.6H2.5c-0.3,0-0.6-0.3-0.6-0.6l0-10  c0-0.3,0.3-0.6,0.6-0.6h3.8V5H2.5C1.1,5,0,6.1,0,7.5l0,10C0,18.9,1.1,20,2.5,20H10c1.4,0,2.5-1.1,2.5-2.5v-1.2h-1.8L10.6,17.5z"/></svg>
					</span>
				</span>
			<?php } ?>
		</div>
	</div>
	
	<div class="shipment_status_heading <?php esc_html_e( $tracker->ep_status ); ?>">
		<?php
		if ( in_array( $tracker->ep_status, array( 'pending_trackship', 'pending', 'carrier_unsupported', 'unknown', 'insufficient_balance', 'invalid_tracking', 'unauthorized_store', 'unauthorized_api_key', 'unauthorized_api_key', 'missing_carrier', 'missing_tracking', 'missing_order_id', 'ssl_error', '' ) ) ) {
			esc_html_e( 'Shipped', 'trackship-for-woocommerce' );
		} else {
			$message = isset( $trackind_detail_by_status_rev[0]->message ) ? $trackind_detail_by_status_rev[0]->message : '';
			$tracker_status = str_contains( $message, 'Delivered, Parcel Locker') ? 'Delivered, Parcel Locker' : $tracker->ep_status;
			esc_html_e( apply_filters( 'trackship_status_filter', $tracker_status ) );
		}
		?>
	</div>
	<?php if ( !$hide_from_to && isset( $row->origin_country ) && $row->origin_country && $row->destination_country && $row->destination_country != $row->origin_country ) { ?>
		<div class="shipping_from_to">
			<span class="shipping_from"><?php echo esc_html( WC()->countries->countries[ $row->origin_country ] ); ?></span>
			<img class="shipping_to_img" src="<?php echo esc_url( trackship_for_woocommerce()->plugin_dir_url() ); ?>assets/images/arrow.png">
			<span class="shipping_to"><?php echo esc_html( WC()->countries->countries[ $row->destination_country ] ); ?></span>
		</div>
	<?php } ?>
	<?php $show_est_delivery_date = apply_filters( 'show_est_delivery_date', true, $provider_name ); ?>
	<?php if ( $tracker->est_delivery_date && $show_est_delivery_date ) { ?>
		<span class="est-delivery-date tracking-number">
			<?php echo 'delivered' != $tracker->ep_status ? esc_html_e( 'Est. Delivery Date', 'trackship-for-woocommerce' ) : esc_html_e( 'Delivery Date', 'trackship-for-woocommerce' ); ?> : 
			<strong><?php esc_html_e( date_i18n( 'l, M d', strtotime( $tracker->est_delivery_date ) ) ); ?></strong>
		</span>
	<?php } ?>
</div>
