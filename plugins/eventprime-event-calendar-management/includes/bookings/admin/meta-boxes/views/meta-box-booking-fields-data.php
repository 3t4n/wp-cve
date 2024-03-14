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
}

if( ! empty( $booking->em_booking_fields_data ) && count( $booking->em_booking_fields_data ) > 0 ) {
    foreach( $booking->em_booking_fields_data as $booking_fields ) {
        $formated_val = ep_get_slug_from_string( $booking_fields['label'] );?>
        <div class="panel-wrap ep_event_metabox ep-border-bottom ep-pb-2">
            <div class="ep-border-bottom">
                <div class="ep-py-3 ep-ps-3 ep-fw-bold ep-text-small">
                    <?php echo esc_html( $booking_fields['label'] );?>
                </div>
            </div>
            <div class="ep-p-3"><?php 
                if( is_array( $booking_fields[$formated_val] ) ) {
                    echo implode( ', ', $booking_fields[$formated_val] );
                } else{
                    echo esc_html( $booking_fields[$formated_val] );
                }?>
            </div>
        </div><?php
    }
}?>