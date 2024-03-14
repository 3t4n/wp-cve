<?php
/**
 * View: Events List
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/eventprime/events/list.php
 *
 */
?>

<div class="emagic">
    <?php do_action( 'ep_events_list_before_render_content', $args ); ?>

    <div class="ep-events-container ep-box-wrap ep-<?php echo esc_attr($args->display_style );?>-view" id="ep-events-container">
        <?php
        if( $args->show_event_filter == 1 ) {
            // Load event search template
            ep_get_template_part( 'events/list/search', null, $args );
        }?>
        
        <?php do_action( 'ep_events_list_before_content', $args ); ?>

        <div id="ep-events-content-container" class="ep-mt-4"><?php
            if( isset( $args->events ) && !empty( $args->events ) ) {?>
                <div class="ep-events ep-box-row ep-event-list-<?php echo esc_attr( $args->display_style );?>-container <?php if( $args->display_style == 'masonry' ) { echo esc_attr( 'masonry-entry' ); } ?> ep_events_front_views_<?php echo esc_attr( $args->display_style);?>_<?php echo esc_attr( $args->section_id);?>" id="ep_events_front_views_<?php echo esc_attr( $args->display_style);?>" data-section_id="<?php echo esc_attr( $args->section_id);?>">
                    <?php
                    switch ( $args->display_style ) {
                        case 'card': 
                        case 'square_grid': 
                            ep_get_template_part( 'events/list/views/card', null, $args );
                            break;
                        case 'list':
                        case 'rows':    
                            ep_get_template_part( 'events/list/views/list', null, $args );
                            break;
                        case 'masonry': 
                        case 'staggered_grid': 
                            ep_get_template_part( 'events/list/views/masonry', null, $args );
                            break;
                        case 'slider': 
                            ep_get_template_part( 'events/list/views/slider', null, $args );
                            break;
                        default: 
                            ep_get_template_part( 'events/list/views/calendar', null, $args );
                    }?>
                </div><?php
            } else{?>
                <div class="ep-alert ep-alert-warning ep-mt-3">
                    <?php ( isset( $_GET['ep_search'] ) ) ? esc_html_e( 'No event found related to your search.', 'eventprime-event-calendar-management' ) : esc_html_e( 'Currently, there are no events planned. Please check back later.', 'eventprime-event-calendar-management' ); ?>
                </div><?php
            }?>
            <?php
            // Load event load more template
            ep_get_template_part( 'events/list/load_more', null, $args );?>
        </div>
        <?php do_action( 'ep_events_list_after_content', $args ); ?>
    </div>
</div>