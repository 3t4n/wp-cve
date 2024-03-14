<?php if( isset( $bookings_data->posts_details->posts ) && ! empty( $bookings_data->posts_details->posts ) ) {
    $booking_controller = EventM_Factory_Service::ep_get_instance( 'EventM_Booking_Controller_List' );
    foreach( $bookings_data->posts_details->posts as $booking ) {
        $event_title = $booking->post_title;
        $booking = $booking_controller->load_booking_detail( $booking->ID );?>
        <tr>
            <td><?php echo esc_html( $booking->em_id );?></td>
            <td><?php echo esc_html( $event_title );?></td>
            <td>
                <?php if( isset( $booking->event_data->em_start_date ) ) {?>
                    <span>
                        <?php echo esc_html( ep_timestamp_to_date( $booking->event_data->em_start_date, 'dS M Y', 1 ) );
                            if( ! empty( $booking->event_data->em_start_time ) ) {
                            echo ', ' . esc_html( $booking->event_data->em_start_time );
                        }?>
                    </span><?php
                }else{
                    echo '--';
                }?>
            </td>
            <td><?php
                if( ! empty( $booking->em_status ) ) {
                    if( $booking->em_status == 'publish' || $booking->em_status == 'completed' ) {?>
                        <span class="ep-booking-status ep-status-confirmed">
                            <?php esc_html_e( 'Completed', 'eventprime-event-calendar-management' );?>
                        </span><?php
                    }
                    if( $booking->em_status == 'pending' ) {?>
                        <span class="ep-booking-status ep-status-pending">
                            <?php esc_html_e( 'Pending', 'eventprime-event-calendar-management' );?>
                        </span> <?php
                    }
                    if( $booking->em_status == 'cancelled' ) {?>
                        <span class="ep-booking-status ep-status-cancelled">
                            <?php esc_html_e( 'Cancelled', 'eventprime-event-calendar-management' );?>
                        </span><?php
                    }
                    if( $booking->em_status == 'refunded' ) {?>
                        <span class="ep-booking-status ep-status-refunded">
                            <?php esc_html_e( 'Refunded', 'eventprime-event-calendar-management' );?>
                        </span><?php
                    }
                    if( $booking->em_status == 'draft' ) {?>
                        <span class="ep-booking-status ep-status-draft">
                            <?php esc_html_e( 'Draft', 'eventprime-event-calendar-management' );?>
                        </span><?php
                    }
                } else{
                    $booking_status = $booking->post_data->post_status;
                    if( ! empty( $booking_status ) ) {?>
                        <span class="ep-booking-status ep-status-<?php echo esc_attr( $booking_status );?>">
                            <?php esc_html_e( EventM_Constants::$status[$booking_status], 'eventprime-event-calendar-management' );?>
                        </span><?php
                    } else{
                        echo '--';
                    }
                }?>
            </td>
            <td><?php
                if( ! empty( $booking->em_payment_method ) ) {
                    echo esc_html( ucfirst( $booking->em_payment_method ) );
                } else{
                    if( ! empty( $booking->em_order_info['payment_gateway'] ) ) {
                        echo esc_html( ucfirst( $booking->em_order_info['payment_gateway'] ) );
                    } else{
                        echo '--';
                    }
                }?>
            </td>
        </tr><?php 
    }
}