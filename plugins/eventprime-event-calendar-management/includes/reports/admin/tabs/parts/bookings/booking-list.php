<div class="wrap ep-box-wrap">
    <div id="poststuff" class="ep-box-row">
        <div class="ep-box-col-12"><?php 
            if( isset( $bookings_data->posts_details->posts ) && ! empty( $bookings_data->posts_details->posts ) ) {
                $booking_controller = EventM_Factory_Service::ep_get_instance( 'EventM_Booking_Controller_List' );?>
                <div class="ep-d-flex ep-justify-content-between ep-align-items-center"><?php
                    echo sprintf( esc_html__( '%d booking found', 'eventprime-event-calendar-management' ), $bookings_data->posts_details->found_posts );?>
                    <?php if( class_exists( 'EM_Advanced_Reports' ) ) { ?>
                        <button type="button" id="ep_booking_export" class="button-primary ep-btn ep-ar-btn-primary"><?php echo esc_html__( 'Export All', 'eventprime-event-calendar-management' );?></button><?php 
                    }?>
                </div>
                <table class="form-table ep-table ep-table-striped">
                    <thead>
                        <tr>
                            <th><?php esc_html_e( 'Booking ID', 'eventprime-event-calendar-management' );?></th>
                            <th><?php esc_html_e( 'Event', 'eventprime-event-calendar-management' );?></th>
                            <th><?php esc_html_e( 'Event Date', 'eventprime-event-calendar-management' );?></th>
                            <th><?php esc_html_e( 'Status', 'eventprime-event-calendar-management' );?></th>
                            <th><?php esc_html_e( 'Payment Gateway', 'eventprime-event-calendar-management' );?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($bookings_data->posts_details->posts as $booking){
                            $event_title = $booking->post_title;
                            $booking = $booking_controller->load_booking_detail( $booking->ID );?>
                            <tr>
                                <td><?php echo esc_attr($booking->em_id);?></td>
                                <td><?php echo esc_attr($event_title);?></td>
                                <td>
                                    <?php if(isset($booking->event_data->em_start_date)){?>
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
                        }?>
                    </tbody>
                </table>
                <div class="ep-reports-boooking-load-more">
                    <?php
                    if( isset($bookings_data->posts_details->max_num_pages) && $bookings_data->posts_details->max_num_pages > 1) {?>
                        <div class="ep-report-load-more ep-frontend-loadmore ep-box-w-100 ep-my-4 ep-text-center">
                            <input type="hidden" id="ep-report-booking-paged" value="1"/>
                            <button type="button" data-max="<?php echo esc_attr( $bookings_data->posts_details->max_num_pages );?>" id="ep-loadmore-report-bookings" class="button-primary ep-btn ep-ar-btn-primary"><span class="ep-spinner ep-spinner-border-sm ep-mr-1"></span><?php esc_html_e( 'Load more', 'eventprime-event-calendar-management' );?></button>
                        </div><?php
                    }?>
                </div><?php 
            } else{ 
                echo esc_html( 'No Booking Found.', 'eventprime-event-calendar-management' );
            }?>
        </div>
    </div>
</div>