<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="tracking_widget_tracking_events_section">
	<?php
	if ( $event_count > 1 && ( !$show_est_delivery_date || !$row->est_delivery_date ) ) {
		$this->enhanced_toogle_switch($num);
	}
	?>
	<div class="enhanced_overview enhanced_tracking_details enhanced_overview_<?php echo esc_html($num); ?>">
		<div class="heading_shipment_status <?php echo esc_html($row->shipment_status); ?>">
			<?php
			if ( in_array( $row->shipment_status, array( 'pending_trackship', 'pending', 'carrier_unsupported', 'unknown', 'insufficient_balance', 'invalid_tracking', 'unauthorized_store', 'unauthorized_api_key', 'unauthorized_store_api_key', 'missing_carrier', 'missing_tracking', 'missing_order_id', 'ssl_error', 'connection_issue', '' ) ) ) {
				esc_html_e( 'Shipped', 'trackship-for-woocommerce' );
			} else {
				$message = isset( $trackind_detail_by_status_rev[0]->message ) ? $trackind_detail_by_status_rev[0]->message : '';
				$tracker_status = str_contains( $message, 'Delivered, Parcel Locker') ? 'Delivered, Parcel Locker' : $row->shipment_status;
				esc_html_e( apply_filters( 'trackship_status_filter', $tracker_status ) );
			}
			?>
		</div>
		<div class="tracking_detail shipped <?php echo esc_html($row->shipment_status); ?>">
			<?php
			if ( isset( $trackind_detail_by_status_rev[0] ) && $trackind_detail_by_status_rev[0] ) {
				$date = $trackind_detail_by_status_rev[0]->datetime;
				?>
				<strong><?php echo esc_html( date_i18n( get_option( 'date_format' ), strtotime($date) ) ); ?></strong>
				<div>
					<?php echo wp_kses_post($trackind_detail_by_status_rev[0]->message); ?>
				</div>
				<?php
			} else {
				$date = $row->shipping_date;
				?>
				<strong><?php echo esc_html( date_i18n( get_option( 'date_format' ), strtotime($date) ) ); ?></strong>
				<div>
					<?php
					$pending_message = __( 'Tracking is still not yet available for this shipment, please try again later.', 'trackship-for-woocommerce' );
					esc_html_e( apply_filters( 'trackship_pending_status_message', $pending_message, $row->shipment_status ) );
				echo '</div>';
			}
			?>
		</div> 
	</div>
	<?php if ( $event_count > 1 ) { ?>
		<div class="enhanced_journey enhanced_tracking_details enhanced_journey_<?php echo esc_html($num); ?>" style="display:none;">
			<?php if ( !empty( $trackind_destination_detail_by_status_rev ) ) { ?>
				<?php foreach ( $trackind_destination_detail_by_status_rev as $key => $value ) { ?>
					<div class="tracking_detail">
						<strong><?php echo esc_html( date_i18n( get_option( 'date_format' ), strtotime($value->datetime) ) ); ?></strong>
						<div>
							<?php
							$tracking_description = apply_filters( 'trackship_tracking_event_description', $value->message );
							$tracking_location_city = apply_filters( 'trackship_tracking_event_location', $value->tracking_location->city );
							$tracking_location_city = null != $tracking_location_city ? ' - ' . $tracking_location_city : $tracking_location_city;
							
							$single_event = apply_filters( 'trackship_tracking_event', $tracking_description . $tracking_location_city );
							echo wp_kses_post( $single_event );
							?>
						</div>
					</div>
					<?php
				}
			}

			if ( !empty( $trackind_destination_detail_by_status_rev ) ) {
				?>
				<h4 class="heading_origin_details" style=""><?php esc_html_e( 'Origin Details', 'trackship-for-woocommerce' ); ?></h4>
				<?php
			}
			$a = 1;
			foreach ( $trackind_detail_by_status_rev as $key => $value ) {
				if ( 1 == $a && empty( $trackind_destination_detail_by_status_rev ) ) {
					$a++;
					continue;
				}
				?>
				<div class="tracking_detail">
					<strong><?php echo esc_html( date_i18n( get_option( 'date_format' ), strtotime($value->datetime) ) ); ?></strong>
					<div>
						<?php
						$tracking_description = apply_filters( 'trackship_tracking_event_description', $value->message );
						$tracking_location_city = apply_filters( 'trackship_tracking_event_location', $value->tracking_location->city );
						$tracking_location_city = null != $tracking_location_city ? ' - ' . $tracking_location_city : $tracking_location_city;
						
						$single_event = apply_filters( 'trackship_tracking_event', $tracking_description . $tracking_location_city );
						echo wp_kses_post( $single_event );
						?>
					</div>
				</div>
				<?php
				$a++;
			}
			?>
		</div>
	<?php } ?>
</div>
