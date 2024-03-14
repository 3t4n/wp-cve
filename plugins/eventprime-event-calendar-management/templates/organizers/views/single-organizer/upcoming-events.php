<?php
/**
 * View: Single Organizer - Upcoming Events
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/eventprime/organizers/single-organizer/upcoming-events.php
 *
 */
defined( 'ABSPATH' ) || exit;
?>
<div class="ep-box-col-12 event-<?php echo esc_attr($args->event_args['event_style']);?>-view">
    <div class="ep-row-heading ep-text-center ep-my-4">
        <div class="ep-upcoming-title ep-fw-bold ep-fs-5 ep-mt-5 ep-d-flex ep-justify-content-center ">
            <?php esc_html_e( 'Upcoming Events', 'eventprime-event-calendar-management' );?>
            <span class="em_events_count-wrap em_bg"></span>
        </div>
    </div>
    <div id="ep-upcoming-events" class="em_content_area ep-upcoming-events">
        <div class="event-details-upcoming-<?php echo esc_attr($args->event_args['event_style']);?>-view">
            <?php if( isset( $args->events->posts ) && ! empty( $args->events->posts ) && count( $args->events->posts ) > 0 ) {?>
                <div class="ep-box-row" id="ep-organizer-upcoming-events"><?php
                    switch ( $args->event_args['event_style'] ) {
                        case 'card': 
                        case 'grid': 
                            ep_get_template_part( 'events/upcoming-events/views/card', null, $args );
                            break;
                        case 'mini-list': 
                        case 'plain_list': 
                            ep_get_template_part( 'events/upcoming-events/views/mini-list', null, $args );
                            break;
                        case 'list':
                        case 'rows': 
                            ep_get_template_part( 'events/upcoming-events/views/list', null, $args );
                            break;
                        default: 
                            ep_get_template_part( 'events/upcoming-events/views/mini-list', null, $args );
                    }?>
                </div><?php
            } else{?>
                <div class="ep-alert ep-alert-warning ep-mt-3 ep-py-2">
                    <?php esc_html_e( 'No upcoming event found.', 'eventprime-event-calendar-management' ); ?>
                </div><?php
            }?>
            <?php
            // Load event load more template
            ep_get_template_part( 'organizers/single-organizer/load_more', null, $args );
            ?>
        </div>  
    </div>
</div>