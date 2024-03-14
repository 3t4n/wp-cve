<?php
/**
 * View: Upcoming Events - Mini List View 
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/eventprime/events/upcoming-events/views/mini-list.php
 *
 */
defined( 'ABSPATH' ) || exit;

foreach( $args->events->posts as $event ) {
    $event_controller = EventM_Factory_Service::ep_get_instance( 'EventM_Event_Controller_List' );
    $single_event_data = $event_controller->get_single_event( $event->ID );
    $event_data = $event_controller->get_event_data_to_views( $single_event_data );
    $new_window = ( ! empty( ep_get_global_settings( 'open_detail_page_in_new_tab' ) ) ? 'target="_blank"' : '' );
    if( ! empty( $event_data ) ) {?>
        <div class="ep-box-col-12 ep-mini-list">
            <div class="ep-box-row">
                <div class="ep-event-single-wrapper ep-box-col-12">
                    <div class="ep-event-list-item ep-upcoming-mini-list ep-box-row ep-border-bottom ep-mb-4 ep-pb-3 ep-text-small ep-align-items-center ep-overflow-hidden">
                        <div class="ep-box-col-3 ep-p-0 ep-border-end ep-position-relative">
                            <div class="ep-event-image">
                                <a href="<?php echo esc_url($event_data['event_url']); ?>" class="ep-img-link" <?php echo esc_attr( $new_window );?>>
                                    <?php if ( ! empty( $event_data['image'] ) ) { ?>
                                        <img src="<?php echo esc_url( $event_data['image'] ) ?>" alt="<?php echo esc_attr( $event_data['title'] ); ?>" class="ep-rounded-circle"><?php 
                                    } else {?>
                                        <img src="<?php echo esc_url( EP_BASE_URL . 'includes/assets/images/dummy_image.png' ) ?>" alt="<?php echo esc_attr( $event_data['title'] ); ?>" class="ep-no-image ep-rounded-circle"><?php 
                                    }?>
                                </a>
                            </div>
                        </div>

                        <div class="ep-box-col-6 ep-p-4 ep-text-small">
                            <div class="ep-box-list-item">
                                <div class="ep-box-title ep-box-list-title">
                                    <div class="ep-fs-5 ep-fw-bold ep-text-dark">
                                        <a href="<?php echo esc_url( $event_data['event_url'] ); ?>" class="ep-img-link" <?php echo esc_attr( $new_window );?>>
                                            <?php echo esc_html( $event_data['title'] ); ?>
                                        </a>
                                    </div>
                                </div>
                                <div class="ep-mb-2 ep-text-small ep-text-muted ep-text-truncate ep-address">
                                    <span><?php echo esc_html( $event_data['address'] ); ?></span>
                                </div>
                            </div>
                        </div>

                        <div class="ep-box-col-3 ep-px-0 ep-position-relative">
                            <div class="ep-px-3 ep-text-end">
                                <div class="ep-mini-list-date ep-d-flex ep-content-right ep-flex-wrap">
                                    <?php do_action( 'ep_event_view_event_dates', $single_event_data, 'list' );?>
                                </div>
                                
                                <!-- Event Price -->
                                <div class="ep-mini-list-price">
                                    <?php do_action( 'ep_event_view_event_price', $single_event_data, 'list' );?>
                                </div>
                            </div>

                            <div class="ep-align-self-end ep-p-2 ep-box-w-100">
                                <?php do_action( 'ep_event_view_event_booking_button', $single_event_data );?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><?php
    }
}?>