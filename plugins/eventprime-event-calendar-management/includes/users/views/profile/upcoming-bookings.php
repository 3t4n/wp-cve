<div class="ep-tab-content" id="ep-coming-up-bookings" role="tabpanel" aria-labelledby="#ep-coming-up-bookings">
    <?php if( ! empty( $args->upcoming_bookings ) && count( $args->upcoming_bookings ) > 0 ) {?>
        <div class="ep-box-row">
            <div class="ep-box-col-12 ep-border-left ep-border-3 ep-ps-3 ep-border-warning ep-mb-4">
                <span class="text-uppercase fw-bold small">
                    <?php esc_html_e( 'Coming Up!', 'eventprime-event-calendar-management');?>
                </span>
            </div>
        </div>
        <div class="ep-box-row ep-box-row-cols-3 ep-text-small ep-g-3">
            <?php foreach( $args->upcoming_bookings as $booking ){
                $image_url = $booking->event_data->image_url;
                if( empty( $image_url ) ) {
                    $image_url = $booking->event_data->placeholder_image_url;
                }?>
                <div class="ep-box-col">
                    <div class="ep-event-card">
                        <div class="ep-box-card-body ep-border ep-rounded-1 ep-overflow-hidden ep-position-relative">
                            <?php if( ! empty( $booking->running_status ) && $booking->running_status == 'ongoing' ) {?>
                                <div class="ep-user-upcoming-event-status ep-bg-success ep-text-white ep-position-absolute ep-z-index ep-px-2 ep-mt-2 ep-rounded-1">
                                    <?php esc_html_e( $booking->running_status, 'eventprime-event-calendar-management' );?>
                                </div><?php
                            }?>
                            <img src="<?php echo esc_url( $image_url );?>" class="ep-event-card-img" alt="<?php echo esc_attr( $booking->event_data->name );?>">
                            <div class="ep-p-3">
                                <div class="ep-mb-2 ep-text-end ep-d-flex ep-content-right">
                                    <a href="<?php echo esc_url( $booking->event_data->event_url );?>" target="_blank" class="ep-button-text-color">
                                        <span class="material-icons-outlined ep-fs-6 ep-mr-1">launch</span>
                                    </a>
                                    <!--wishlist-->
                                    <?php do_action( 'ep_event_view_wishlist_icon', $booking->event_data, 'event_list' );?>
                                    <!--social sharing-->
                                    <?php do_action( 'ep_event_view_social_sharing_icon', $booking->event_data, 'event_list' );?>
                                </div>
                                <div class="ep-card-title ep-fw-bold ep-text-truncate ep-mb-2">
                                    <?php echo esc_attr( $booking->event_data->name );?>
                                </div>
                                <?php if( ! empty( $booking->event_data->venue_details ) && ! empty( $booking->event_data->venue_details->em_address ) ) {?>
                                    <div class="ep-card-subtitle ep-mb-2 ep-text-muted ep-text-small">
                                        <?php echo esc_html( $booking->event_data->venue_details->em_address );?>
                                    </div><?php
                                }?>
                                <div class="card-subtitle ep-mb-2 ep-text-dark">
                                    <?php 
                                    echo esc_html( $booking->event_data->fstart_date );
                                    if( ! empty( $booking->event_data->em_start_time ) ) {
                                        esc_html_e( ' at ', 'eventprime-event-calendar-management' );
                                        echo esc_html( $booking->event_data->em_start_time );
                                    }?>
                                </div>

                                <?php if( ! empty( $booking->event_data->venue_details ) && ! empty( $booking->event_data->venue_details->em_address ) ) {?>
                                    <a class="ep-btn ep-btn-outline-dark ep-box-w-100 ep-btn-sm ep-mb-2 ep-py-1" target="_blank" href="https://www.google.com/maps?saddr=My+Location&daddr=<?php echo urlencode( $booking->event_data->venue_details->em_address ); ?>" >
                                        <?php esc_html_e( 'Directions','eventprime-event-calendar-management' ); ?>
                                    </a><?php
                                }?>
                                <!-- <a href="booking-confirmation.php" class="ep-btn ep-btn-outline-dark ep-box-w-100 ep-btn-sm ep-mb-2 ">Tickets</a> -->
                                <a href="<?php echo esc_url( $booking->booking_detail_url );?>" target="_blank" class="ep-btn ep-btn-warning ep-box-w-100 ep-btn-sm ep-py-1">
                                    <?php esc_html_e( 'Booking Details', 'eventprime-event-calendar-management' ); ?>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
        }?>
        </div> <?php
    } else{?>
        <div class="ep-box-row">
            <div class="ep-box-col-12 ep-border-left ep-border-3 ep-ps-3 ep-border-warning ep-mb-4">
                <span class="text-uppercase fw-bold small">
                    <?php esc_html_e('You have no events coming up!', 'eventprime-event-calendar-management');?>
                </span>
            </div>
        </div><?php
    }?>
</div>