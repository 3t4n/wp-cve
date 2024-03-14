<?php
/**
 * View: Upcoming Events - Card View 
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/eventprime/events/upcoming-events/views/card.php
 *
 */
defined( 'ABSPATH' ) || exit;

foreach( $args->events->posts as $event ) {
    $event_controller = EventM_Factory_Service::ep_get_instance( 'EventM_Event_Controller_List' );
    $event_data = $event_controller->get_single_event( $event->ID );
    $new_window = ( ! empty( ep_get_global_settings( 'open_detail_page_in_new_tab' ) ) ? 'target="_blank"' : '' );
    if( ! empty( $event_data ) ) {?>
        <div class="ep-event-single-card ep-event-card ep-mb-4 ep-card-col-<?php echo esc_attr($args->event_args['event_cols']);?>">
            <div class="ep-upcoming-box-card-item ep-border ep-rounded-1 ep-overflow-hidden ep-box-h-100 ep-mb-4 ep-position-relative">
                <div class="ep-upcoming-box-card-thumb ep-overflow-hidden ep-position-relative">
                    <a href="<?php echo esc_url( $event_data->event_url ); ?>" class="ep-img-link" <?php echo esc_attr( $new_window );?>>
                        <?php if( ! empty( $event_data->image_url ) ) {?>
                            <img src="<?php echo esc_url( $event_data->image_url ) ?>" alt="<?php echo esc_attr( $event_data->em_name ); ?>"><?php
                        } else{?>
                            <img class="ep-no-image" src="<?php echo esc_url( EP_BASE_URL . 'includes/assets/images/dummy_image.png' ) ?>"  alt="<?php echo esc_attr( $event_data->em_name ); ?>"><?php
                        }?>
                    </a>
                    <div class="ep-box-card-icon-group ep-position-absolute ep-bg-white ep-rounded-top ep-d-inline-flex">
                        <!--wishlist-->
                        <?php do_action( 'ep_event_view_wishlist_icon', $event_data, 'event_list' );?>
                        <!--social sharing-->
                        <?php do_action( 'ep_event_view_social_sharing_icon', $event_data, 'event_list' );?>

                        <?php do_action( 'ep_event_view_event_icons', $event );?>
                    </div>
                </div>

                <?php do_action( 'ep_event_view_before_event_title', $event_data );?>
                
                <div class="ep-box-card-content ep-text-small ep-p-3">
                    <div class="ep-box-title ep-box-card-title ep-text-truncate ep-mb-2">
                        <span class="ep-fw-bold ep-fs-6 ep-my-3 ep-text-dark">
                            <a href="<?php echo esc_url( $event_data->event_url ); ?>" <?php echo esc_attr( $new_window );?>>
                                <?php echo esc_html( $event_data->em_name ); ?>
                            </a>
                        </span> 
                    </div>
                    <?php
                    // venue
                    if( ! empty( $event_data->venue_details ) ) {
                        if( ! empty( $event_data->venue_details->name ) ) {?>
                            <div class="ep-box-card-venue ep-card-venue ep-text-muted ep-text-truncate"><?php 
                                echo esc_html( $event_data->venue_details->name );?>
                            </div><?php
                        }
                    }
                    // event dates
                    if( ! empty( $event_data->em_start_date ) ) {?>
                        <div class="ep-event-details ep-d-flex ep-justify-content-between ep-mb-2">
                            <div class="ep-card-event-date ep-d-flex ep-text-muted ">
                                <div class="ep-card-event-date-wrap ep-d-flex ep-fw-bold">
                                    <?php do_action( 'ep_event_view_event_dates', $event_data, 'card' );?>
                                </div>
                            </div>
                        </div><?php
                    }?>
                    <!-- Event Description -->
                    <div class="ep-box-card-desc ep-text-small ep-mb-2">
                        <?php if ( ! empty( $event_data->description ) ) {
                            echo wp_trim_words( wp_kses_post( $event_data->description ), 20 );
                        }?>
                    </div>

                    <!-- Hook after event description -->
                    <?php do_action( 'ep_event_view_after_event_description', $event_data );?>
                    
                    <!-- Event Price -->
                    <?php do_action( 'ep_event_view_event_price', $event_data, 'card' );?>

                    <!-- Booking Status -->
                    <?php do_action('ep_events_booking_count_slider', $event_data);?>
                </div>

                <?php do_action( 'ep_event_view_before_event_button', $event_data );?>

                <div class="ep-card-footer-wrap ep-text-center ep-box-w-100 ep-mt-5">
                    <div class="ep-card-footer ep-border-top ep-py-2 ep-position-absolute ep-px-3 ep-box-w-100">
                        <?php do_action('ep_event_view_event_booking_button', $event_data); ?>
                    </div>
                </div>

                <?php do_action( 'ep_event_view_after_event_button', $event_data );?>
            </div> 
        </div><?php
    }
}?>