<?php
if( isset( $args->events ) && !empty( $args->events ) ) {?>
        <div class="ep-events ep-box-row ep-event-list-<?php echo $args->display_style;?>-container <?php if( $args->display_style == 'masonry' ) { echo 'masonry-entry'; } ?>" id="ep_events_front_views_<?php echo $args->display_style;?>">
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
        </div>
    <?php
} else{?>
    <div class="ep-alert ep-alert-warning ep-mt-3">
        <?php ( isset( $_GET['ep_search'] ) ) ? esc_html_e( 'No event found related to your search.', 'eventprime-event-calendar-management' ) : esc_html_e( 'Currently, there are no events planned. Please check back later.', 'eventprime-event-calendar-management' ); ?>
    </div><?php
}?>

<?php
// Load event load more template
ep_get_template_part( 'events/list/load_more', null, $args );
?>