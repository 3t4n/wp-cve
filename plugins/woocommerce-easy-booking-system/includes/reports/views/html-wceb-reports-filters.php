<?php

/**
*
* Admin: Filters in "Bookings" reports page.
* @version 3.0.4
*
**/

defined( 'ABSPATH' ) || exit;

?>

<div class="wceb-reports-filters">

	<form id="bookings-filter" method="get">

		<input type="hidden" name="page" value="easy-booking-reports">

		<?php do_action( 'easy_booking_before_reports_filters' ); ?>

		<div class="reports-filter filter-status">

			<select name="status" id="wceb_report_status">
				<option value=""><?php esc_html_e( 'Booking status', 'woocommerce-easy-booking-system' ); ?></option>
				<option value="pending" <?php selected( $filter_status, 'pending' ); ?>><?php apply_filters( 'easy_booking_display_status_pending', esc_html_e( 'Pending', 'woocommerce-easy-booking-system' ) ); ?></option>
				<option value="start" <?php selected( $filter_status, 'start' ); ?>><?php apply_filters( 'easy_booking_display_status_start', esc_html_e( 'Start', 'woocommerce-easy-booking-system' ) ); ?></option>
				<option value="processing" <?php selected( $filter_status, 'processing' ); ?>><?php apply_filters( 'easy_booking_display_status_processing', esc_html_e( 'Processing', 'woocommerce-easy-booking-system' ) ); ?></option>
				<option value="end" <?php selected( $filter_status, 'end' ); ?>><?php apply_filters( 'easy_booking_display_status_end', esc_html_e( 'End', 'woocommerce-easy-booking-system' ) ); ?></option>
				<option value="completed" <?php selected( $filter_status, 'completed' ); ?>><?php apply_filters( 'easy_booking_display_status_completed', esc_html_e( 'Completed', 'woocommerce-easy-booking-system' ) ); ?></option>
			</select>

		</div>

		<div class="reports-filter filter-id">

			<select class="wc-product-search"  style="width:203px;" id="reports_search" name="product_ids" data-placeholder="<?php esc_attr_e( 'Search for a product&hellip;', 'woocommerce' ); ?>" data-action="wceb_reports_product_search" data-allow_clear="true">
				
			<?php

			$product = wc_get_product( $filter_id );
			if ( is_object( $product ) ) {
				echo '<option value="' . esc_attr( $filter_id ) . '"' . selected( true, true, false ) . '>' . wp_kses_post( $product->get_formatted_name() ) . '</option>';
			}

			?>

			</select>

		</div>

		<div class="reports-filter filter-date">

			<input type="text" name="start_date" data-value="<?php esc_attr_e( $filter_start_date ); ?>" class="wceb_datepicker" placeholder="<?php echo isset( $start_date_text ) ? esc_attr( $start_date_text ) : esc_attr__( 'Start', 'woocommerce-easy-booking-system' ); ?>">

		</div>

		<div class="reports-filter filter-date">

			<input type="text" name="end_date" data-value="<?php esc_attr_e( $filter_end_date ); ?>" class="wceb_datepicker" placeholder="<?php echo isset( $end_date_text ) ? esc_attr( $end_date_text ) : esc_attr__( 'End', 'woocommerce-easy-booking-system' ); ?>">

		</div>

		<?php do_action( 'easy_booking_after_reports_filters' ); ?>
		
		<div class="reports-filter reports-filter-submit">

			<input type="submit" id="wceb-filter-bookings" class="button" value="<?php esc_attr_e( 'Filter', 'woocommerce-easy-booking-system' ); ?>">

		</div>

	</form>

	<?php do_action( 'easy_booking_after_reports_form' ); ?>

	<div class="clear"></div>

</div>