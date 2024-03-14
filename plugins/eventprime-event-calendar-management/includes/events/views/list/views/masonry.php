<?php
/**
 * View: Card View
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/eventprime/events/list/views/masonry.php
 *
 */
?>

<?php foreach ( $args->events->posts as $event ) {
    $options = array();
    $settings          = EventM_Factory_Service::ep_get_instance( 'EventM_Admin_Model_Settings' );
    $options['global'] = $settings->ep_get_settings();
    $global_options = $options['global'];
    $em_custom_link   = get_post_meta( $event->id, 'em_custom_link', true );
    if ($global_options->redirect_third_party == 1 && $event->em_enable_booking == 'external_bookings') {
        $url = esc_url($em_custom_link);
    } else {
        $url = esc_url($event->event_url);
    }
    $new_window = ( ! empty( ep_get_global_settings( 'open_detail_page_in_new_tab' ) ) ? 'target="_blank"' : '' );?>
    <div class="ep-event-card ep-mb-4 ep-card-col-<?php echo esc_attr( $args->cols ) ;?>">
        <div class="ep-box-card-item ep-border ep-rounded ep-overflow-hidden ep-bg-white ep-bg-opacity-6">
            <div class="ep-box-card-thumb ep-overflow-hidden ep-position-relative ">
                <?php if ( ! empty( $event->_thumbnail_id ) ) { ?>
                    <a href="<?php echo $url; ?>" <?php echo esc_attr( $new_window );?> class="ep-img-link">
                        <?php echo get_the_post_thumbnail( $event->id, 'large', array('class' => 'img-fluid ep-rounded-1 ep-box-w-100 ep-event-cover') ); ?>
                    </a><?php
                    } else { ?>
                    <a href="<?php echo $url; ?>" <?php echo esc_attr( $new_window );?> class="ep-img-link">
                        <img src="<?php echo esc_url( EP_BASE_URL . 'includes/assets/images/dummy_image.png' ); ?>" alt="<?php esc_html_e( 'Dummy Image', 'eventprime-event-calendar-management' ); ?>" class="em-no-image ep-event-cover">
                    </a><?php
                } ?>
                <div class="ep-box-card-icon-group ep-position-absolute ep-bg-white ep-rounded-top ep-d-inline-flex">
                    <!--wishlist-->
                    <?php do_action( 'ep_event_view_wishlist_icon', $event, 'event_list' );?>
                    <!--social sharing-->
                    <?php do_action( 'ep_event_view_social_sharing_icon', $event, 'event_list' );?>
                    
                    <?php do_action( 'ep_event_view_event_icons', $event );?>
                </div>
            </div>
            
            <?php do_action( 'ep_event_view_before_event_title', $event );?>

            <div class="ep-box-card-content ep-text-small ep-p-3">
                <!-- Event Title -->
                <div class="ep-box-title ep-box-card-title ep-mb-2">
                    <a href="<?php echo $url;?>" <?php echo esc_attr( $new_window );?> class="ep-fw-bold ep-fs-6 ep-my-3 ep-text-dark">
                        <?php echo esc_html( $event->em_name ); ?>
                    </a>
                </div><?php
                // venue
                if( ! empty( get_term_by( 'id', $event->em_venue, 'em_venue' ) ) ) {
                    $event_venue_details = $args->venues[$event->em_venue];
                    if( ! empty( $event_venue_details['name'] ) ) {?>
                        <div class="ep-box-card-venue ep-card-venue ep-text-muted ep-text-truncate"><?php
                            echo esc_html( $event_venue_details['name'] );?>
                        </div><?php
                    }
                }
                // event dates
                if( ! empty( $event->em_start_date ) ) {?>
                    <div class="ep-event-details ep-d-flex ep-justify-content-between ep-mb-2">
                        <div class="ep-card-event-date ep-d-flex ep-text-muted ">
                            <div class="ep-card-event-date-wrap ep-d-flex ep-fw-bold">
                                <?php do_action( 'ep_event_view_event_dates', $event, 'card' );?>
                            </div>
                        </div>
                    </div><?php
                }?>
                <!-- Event Description -->
                <div class="ep-box-card-desc-masonry ep-box-card-desc ep-text-small ep-mb-2">
                    <?php if ( ! empty( $event->description ) ) {
                        echo wp_trim_words( wp_kses_post( $event->description ), 100 );
                    }?>
                </div>

                <!-- Hook after event description -->
                <?php do_action( 'ep_event_view_after_event_description', $event );?>
                
                <!-- Event Price -->
                <?php do_action( 'ep_event_view_event_price', $event );?>

                <!-- Booking Status -->
                <?php do_action('ep_events_booking_count_slider', $event);?>
            </div>
            
            <?php do_action( 'ep_event_view_before_event_button', $event );?>

            <div class="ep-text-center ep-card-footer ep-border-top">
                <?php do_action( 'ep_event_view_event_booking_button', $event );?>
            </div>

            <?php do_action( 'ep_event_view_after_event_button', $event );?>

        </div>
    </div>
<?php } ?>