<?php
/**
 * View: Upcoming Events - List View 
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/eventprime/events/upcoming-events/views/list.php
 *
 */
defined( 'ABSPATH' ) || exit;

foreach( $args->events->posts as $event ) {
    $event_controller = EventM_Factory_Service::ep_get_instance( 'EventM_Event_Controller_List' );
    $single_event_data = $event_controller->get_single_event( $event->ID );
    $event_data = $event_controller->get_event_data_to_views( $single_event_data );
    $new_window = ( ! empty( ep_get_global_settings( 'open_detail_page_in_new_tab' ) ) ? 'target="_blank"' : '' );
    if( !empty( $event_data ) ) {
        $month_id = date( 'Ym', strtotime( $event_data['start'] ) );
        if( empty( $last_month_id ) || $last_month_id != $month_id ) {
            $last_month_id = $month_id;?>
            <div class="ep-box-col-12 ep-month-divider ep-text-center ep-my-3">
                <span class="ep-listed-event-month ep-fw-bold ep-fs-5">
                    <?php echo esc_html( date_i18n( 'F Y', strtotime( $event_data['start'] ) ) ); ?>
                    <span class="ep-listed-event-month-tag"></span>
                </span>
            </div><?php
        }?>

        <div class="ep-box-col-12">
            <div class="ep-event-single-wrapper ep-event-list-item ep-bg-white ep-border ep-rounded ep-mb-4 ep-text-small">
                <div class="ep-box-row ep-m-0">
                    <div class="ep-box-col-3 ep-p-0 ep-bg-light ep-border-right ep-position-relative ep-rounded-tbl-right ep-list-view-image ep-upcoming-list-view">
                        <a href="<?php echo esc_url($event_data['event_url']); ?>" class="ep-img-link" <?php echo esc_attr( $new_window );?>>
                            <?php if (!empty($event_data['image'] ) ) { ?>
                            <img src="<?php echo esc_url( $event_data['image'] ) ?>" alt="<?php echo esc_attr( $event_data['title'] ); ?>" class="ep-img-fluid ep-box-w-100 ep-list-img-fluid ep-rounded-tbl-right"><?php 
                            } else {?>
                            <img src="<?php echo esc_url( EP_BASE_URL . 'includes/assets/images/dummy_image.png' ) ?>" alt="<?php echo esc_attr( $event_data['title'] ); ?>" class="ep-img-fluid ep-no-image ep-box-w-100 ep-list-img-fluid"><?php 
                            }?>
                        </a>
                        <div class="ep-list-icon-group ep-position-absolute ep-bg-white ep-rounded ep-d-inline-flex">
                            <!--wishlist-->
                            <?php do_action( 'ep_event_view_wishlist_icon', $single_event_data, 'event_list' );?>
                            <!--social sharing-->
                            <?php do_action( 'ep_event_view_social_sharing_icon', $single_event_data, 'event_list' );?>

                            <?php do_action( 'ep_event_view_event_icons', $event );?>
                        </div>
                    </div>
                    
                    <?php do_action( 'ep_event_view_before_event_title', $single_event_data );?>

                    <div class="ep-box-col-6 ep-p-4 ep-text-small">
                        <div class="ep-box-list-item">
                            <div class="ep-list-box-title ep-box-list-title">
                                <!-- Event Type -->
                                <?php if( ! empty( $single_event_data->event_type_details ) ) {
                                    if( ! empty( $single_event_data->event_type_details->name ) ) {?>
                                        <div class="ep-text-small ep-text-uppercase ep-text-warning ep-fw-bold"><?php
                                            echo esc_html( '/ '.$single_event_data->event_type_details->name );?>
                                        </div><?php
                                    }
                                }?>
                                <!-- Event Title -->
                                <a class="ep-fs-5 ep-fw-bold ep-text-dark" data-event-id="<?php echo esc_attr( $single_event_data->id ); ?>" href="<?php echo esc_url( $single_event_data->event_url ); ?>" <?php echo esc_attr( $new_window );?> rel="noopener">
                                    <?php echo esc_html( $single_event_data->em_name ); ?>
                                </a>
                            </div>
                            <!-- Venue -->
                            <?php if( ! empty( $single_event_data->venue_details ) ) {
                                if( ! empty( $single_event_data->venue_details->name ) ) {?>
                                    <div class="ep-mb-2 ep-text-small ep-text-muted ep-text-truncate"><?php
                                        echo esc_html( $single_event_data->venue_details->name );?>
                                    </div><?php
                                }
                            }?>
                            <!-- Event Description -->
                            <div class="ep-box-list-desc ep-text-small ep-mt-3 ep-content-truncate ep-content-truncate-line-4">
                                <?php if ( ! empty( $single_event_data->description ) ) {
                                    echo wp_trim_words( wp_kses_post( $single_event_data->description ), 35 );
                                }?>
                            </div>

                            <!-- Hook after event description -->
                            <?php do_action( 'ep_event_view_after_event_description', $single_event_data );?>

                        </div>
                    </div>
                    
                    <div class="ep-box-col-3 ep-box-list-right-col ep-px-0 ep-pt-4 ep-rounded-tbr-right ep-overflow-hidden ep-border-left ep-position-relative">
                        <div class="ep-px-3 ep-text-end">
                            <div class="ep-event-list-view-action ep-d-flex ep-flex-wrap ep-content-right">
                                <?php do_action( 'ep_event_view_event_dates', $single_event_data, 'list' );?>
                            </div>
                            
                            <!-- Event Price -->
                            <?php do_action( 'ep_event_view_event_price', $single_event_data, 'list' );?>
                            
                            <?php $available_offers = EventM_Factory_Service::get_event_available_offers( $single_event_data );
                            if( ! empty( $available_offers ) ) {?>
                                <div class="ep-text-small ep-mb-1">
                                    <div class="ep-offer-tag ep-overflow-hidden ep-text-small ep-text-white ep-rounded-1 ep-px-2 ep-py-1 ep-position-relative ep-d-inline-flex">
                                        <span class=""><?php echo absint( $available_offers );?> <?php esc_html_e( 'Offers Available', 'eventprime-event-calendar-management' ); ?></span>
                                        <div class="ep-offer-spark ep-bg-white ep-position-absolute ep-border ep-border-white ep-border-3">wqdwqd</div>
                                    </div>
                                </div><?php
                            }?>

                            <!-- Booking Status -->
                            <?php do_action('ep_events_booking_count_slider', $single_event_data);?>
                        </div>

                        <?php do_action( 'ep_event_view_before_event_button', $single_event_data );?>

                        <div class="ep-align-self-end ep-position-absolute ep-p-2 ep-bg-white ep-box-w-100" style="bottom:0">
                            <?php do_action( 'ep_event_view_event_booking_button', $single_event_data );?>
                        </div>
                    </div>
                </div>
            </div>
        </div><?php
    }
}?>