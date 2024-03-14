<?php
/**
 * Booking meta box html
 */
defined( 'ABSPATH' ) || exit;
$booking_controller = EventM_Factory_Service::ep_get_instance( 'EventM_Booking_Controller_List' );
$booking_id = $post->ID;
$post_meta = get_post_meta( $booking_id );
$booking = $this->get_booking_cache( $booking_id );
if( empty( $booking ) ) {
    $booking = $booking_controller->load_booking_detail( $booking_id );
}?>

<div class="panel-wrap ep_event_metabox">
    <div class="ep-py-3 ep-ps-3 ep-fw-bold ep-text-uppercase ep-text-small">
        <button type="button" class="button" id="ep_show_booking_transaction_log"><?php esc_html_e( 'Transaction Log', 'eventprime-event-calendar-management' );?></button>
    </div>
    <span class="ep-booking-transaction-log" style="display: none;">
        <?php echo "<pre>";
            print_r( json_encode( $booking->em_payment_log, JSON_PRETTY_PRINT ) );
        echo "</pre>";?>
    </span>
</div>