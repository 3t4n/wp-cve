<?php
/**
 * View: Performers List - Load More
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/eventprime/performers/single-performer/load_more.php
 *
 */
?>
<?php
if( $args->events->max_num_pages > 1 && isset( $args->event_args['load_more'] ) && $args->event_args['load_more'] == 1 ) {?>
    <div class="ep-venue-upcoming-event-load-more ep-frontend-loadmore ep-box-w-100 ep-my-4 ep-text-center">
        <button 
            data-max="<?php echo $args->events->max_num_pages;?>" 
            id="ep-loadmore-upcoming-event-venue" 
            class="ep-btn ep-btn-outline-primary"
            data-id="<?php echo esc_attr( $args->venue_id );?>"
            data-style="<?php echo esc_attr( $args->event_args['event_style'] );?>"
            data-limit="<?php echo esc_attr( $args->event_args['event_limit'] );?>"
            data-cols="<?php echo esc_attr( $args->event_args['event_cols'] );?>"
            data-paged="<?php echo esc_attr( $args->event_args['paged'] );?>"
            data-pastevent="<?php echo esc_attr( $args->event_args['hide_past_events'] );?>"
        >
            <span class="ep-spinner ep-spinner-border-sm ep-mr-1"></span>
            <?php esc_html_e( 'Load more', 'eventprime-event-calendar-management' );?>
        </button>
    </div><?php
}?>