<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="enhanced_shipment_details_section">
	<div data-label="enhanced_details" class="enhanced_heading ">
		<span><?php esc_html_e('Details', 'trackship-for-woocommerce' ); ?></span>
		<span class="accordian-arrow ts-right"></span>
	</div>
	<div class="enhanced_content enhanced_details">
		<?php if ( !$hide_last_mile && isset($row->delivery_number) && $row->delivery_number ) { ?>
			<div class="last_mile_tracking_number">
				<span><?php esc_html_e( 'Delivery tracking Number', 'trackship-for-woocommerce' ); ?></span>
				<strong> <?php echo esc_html( $row->delivery_number ); ?></strong>
			</div>
		<?php } ?>
		<?php if ( !$hide_from_to && $row->origin_country && $row->destination_country && $row->destination_country != $row->origin_country ) { ?>
			<div class="shipping_from_to">
				<span class="shipping_from"><?php echo esc_html( WC()->countries->countries[ $row->origin_country ] ); ?></span>
				<img class="shipping_to_img" src="<?php echo esc_url( trackship_for_woocommerce()->plugin_dir_url() ); ?>assets/images/arrow.png">
				<span class="shipping_to"><?php echo esc_html( WC()->countries->countries[ $row->destination_country ] ); ?></span>
			</div>
		<?php } ?>

		<div class="wc_order_id">
			<?php esc_html_e( 'View your order details', 'trackship-for-woocommerce' ); ?> 
			<?php if ( $order->get_customer_id() ) { ?>
				<a href="<?php echo esc_url( $order->get_view_order_url() ); ?>" target="_blank"><?php echo esc_html( '#' . $order->get_order_number() ); ?></a>
			<?php } else { ?>
				<?php echo esc_html( '#' . $order->get_order_number() ); ?>
			<?php } ?>
		</div>
		<?php $this->get_products_detail_in_shipment( $order_id, $row, $row->shipping_provider, $tracking_number ); ?>
	</div>
</div>
<?php if ( get_trackship_settings( 'enable_email_widget' ) ) { ?>
	<div class="enhanced_notifications_section">
		<div data-label="enhanced_notifications" class="enhanced_heading ">
			<span><?php esc_html_e('Notifications', 'trackship-for-woocommerce' ); ?></span>
			<span class="accordian-arrow ts-right"></span>
		</div>
		<div class="enhanced_content enhanced_notifications">
			<?php $this->get_notifications_option( $order_id ); ?>
		</div>
	</div>
<?php } ?>
