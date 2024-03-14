<?php

/**
*
* Display bookable order items meta data on order pages.
* @version 3.0.0
*
**/

defined( 'ABSPATH' ) || exit;

?>

<div class="view">

	<table cellspacing="0" class="display_meta">
		
		<tbody>

			<tr>
			    <th><?php esc_html_e( $start_date_text ); ?>:</th>
			    <td><p><?php esc_html_e( $start_date_i18n ); ?></p></td>
			</tr>
			
			<?php if ( ! empty( $end_date ) ) : ?>

				<tr>
				    <th><?php esc_html_e( $end_date_text ); ?>: </th>
				    <td><p><?php esc_html_e( $end_date_i18n ); ?></p></td>
				</tr>

			<?php endif; ?>

			<?php if ( ! empty( $booking_status ) ) : ?>
				
				<tr>
				    <th><?php esc_html_e( 'Booking status', 'woocommerce-easy-booking-system' ); ?>: </th>
				    <?php $status = str_replace('wceb-', '', $booking_status ); ?>
				    <td><p><?php echo apply_filters( 'easy_booking_display_status_' . $status, esc_html( ucfirst( $status ) ) ); ?></p></td>
				</tr>

        	<?php endif; ?>

		</tbody>

	</table>

</div>