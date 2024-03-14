<?php
/**
 * View: List View
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/eventprime/events/list/views/list.php
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
    <div class="ep-box-col-12">
        <div class="ep-event-list-item ep-bg-white ep-border ep-rounded ep-mb-4 ep-text-small">
            <div class="ep-box-row ep-m-0">
                <div class="ep-box-col-3 ep-p-0 ep-bg-light ep-border-right ep-position-relative ep-rounded-tbl-right">
                    <?php if ( ! empty( $event->_thumbnail_id ) ) { ?>
                    <a href="<?php echo $url; ?>" <?php echo esc_attr( $new_window );?> class="ep-img-link">
                            <?php echo get_the_post_thumbnail( $event->id, 'post-thumbnail size', array( 'class' => 'ep-img-fluid ep-box-w-100 ep-list-img-fluid ep-rounded-tbl-right' ) ); ?>
                        </a><?php 
                    } else {?>
                        <a href="<?php echo $url; ?>" <?php echo esc_attr( $new_window );?> class="ep-img-link ep-image-default">
                            <img src="<?php echo esc_url( EP_BASE_URL . 'includes/assets/images/dummy_image.png' ); ?>" alt="<?php esc_html_e( 'Dummy Image', 'eventprime-event-calendar-management' ); ?>" class="em-no-image ep-box-w-100 ep-rounded-tbl-right ep-list-img-fluid">
                        </a><?php 
                    }?>
                    <div class="ep-list-icon-group ep-position-absolute ep-bg-white ep-rounded ep-d-inline-flex">
                        <!--wishlist-->
                        <?php do_action( 'ep_event_view_wishlist_icon', $event, 'event_list' );?>
                        <!--social sharing-->
                        <?php do_action( 'ep_event_view_social_sharing_icon', $event, 'event_list' );?>

                        <?php do_action( 'ep_event_view_event_icons', $event );?>
                    </div>
                </div>
                <?php do_action( 'ep_event_view_before_event_title', $event );?>
            
                <div class="ep-box-col-6 ep-p-4 ep-text-small">
                    <div class="ep-box-list-item">
                        <div class="ep-box-title ep-box-list-title ep-text-truncate">
                            <!-- Event Type -->
                            <?php if( ! empty( $event->em_event_type ) ) {
                                $event_type_details = $args->event_types[$event->em_event_type];
                                if( ! empty( $event_type_details['name'] ) ) {?>
                                    <div class="ep-text-small ep-text-uppercase ep-text-warning ep-fw-bold"><?php
                                        echo '/ ' . esc_html( $event_type_details['name'] );?>
                                    </div><?php
                                }
                            }?>
                            <!-- Event Title -->
                            <a class="ep-fs-5 ep-fw-bold ep-text-dark" data-event-id="<?php echo esc_attr( $event->id ); ?>" href="<?php echo $url; ?>" <?php echo esc_attr( $new_window );?> rel="noopener">
                                <?php echo esc_html( $event->em_name ); ?>
                            </a>
                        </div>
                        <!-- Venue -->
                        <?php if( ! empty( get_term_by( 'id', $event->em_venue, 'em_venue' ) ) ) {
                            $event_venue_details = ( ! empty( $args->venues[$event->em_venue] ) ? $args->venues[$event->em_venue] : array() );
                            if( ! empty( $event_venue_details['name'] ) ) {?>
                                <div class="ep-mb-2 ep-text-small ep-text-muted ep-text-truncate"><?php
                                    echo esc_html( $event_venue_details['name'] );?>
                                </div><?php
                            }
                        }?>
                        <!-- Event Description -->
                        <div class="ep-box-list-desc ep-text-small ep-mt-3 ep-content-truncate ep-content-truncate-line-4">
                            <?php if ( ! empty( $event->description ) ) {
                                echo wp_trim_words( wp_kses_post( $event->description ), 35 );
                            }?>
                        </div>

                        <!-- Hook after event description -->
                        <?php do_action( 'ep_event_view_after_event_description', $event );?>
                    </div>
                </div>

                <div class="ep-box-col-3 ep-box-list-right-col ep-px-0 ep-pt-4 ep-rounded-tbr-right ep-overflow-hidden ep-border-left ep-position-relative ep-d-flex ep-box-direction">
                    <div class="ep-px-2 ep-text-end ep-list-view-pricing-area ep-d-flex ep-box-direction ep-flex-1">
                        <div class="ep-event-list-view-action ep-flex-wrap ep-content-right">
                            <?php do_action( 'ep_event_view_event_dates', $event, 'list' );?>
                        </div>
                        
                        <!-- Event Price -->
                        <?php do_action( 'ep_event_view_event_price', $event );?>
                        
                        <?php $available_offers = EventM_Factory_Service::get_event_available_offers( $event );
                        if( ! empty( $available_offers ) ) {?>
                            <div class="ep-text-small ep-mb-1">
                                <div class="ep-offer-tag ep-overflow-hidden ep-text-small ep-text-white ep-rounded-1 ep-px-2 ep-py-1 ep-position-relative ep-d-inline-flex">
                                    <span class="ep-event-ticket-offer-number"><?php 
                                        echo absint( $available_offers );
                                        if( absint( $available_offers ) > 1 ) {
                                            esc_html_e( 'Offers Available', 'eventprime-event-calendar-management' );
                                        } else{
                                            esc_html_e( 'Offer Available', 'eventprime-event-calendar-management' );
                                        }?>
                                    </span>
                                    <div class="ep-offer-spark ep-bg-white ep-position-absolute ep-border ep-border-white ep-border-3">wqdwqd</div>
                                </div>
                            </div><?php
                        }?>

                        <!-- Booking Status -->
                        <?php do_action('ep_events_booking_count_slider', $event);?>
                    </div>

                    <?php do_action( 'ep_event_view_before_event_button', $event );?>

                    <div class="ep-align-self-end ep-list-view-btn-area ep-p-2 ep-bg-white ep-box-w-100 ep-mt-auto">
                        <?php //echo EventM_Factory_Service::render_event_booking_btn( $event );?>
                        <?php do_action( 'ep_event_view_event_booking_button', $event );?>
                    </div>

                </div>
            
            </div>
        </div>
    </div><?php 
}?>