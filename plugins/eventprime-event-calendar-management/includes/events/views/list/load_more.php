<?php
/**
 * View: Event List - Load More
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/eventprime/performers/list/load_more.php
 *
 */
?>
<?php
if( isset( $args->events->max_num_pages ) && $args->events->max_num_pages > 1 && isset( $args->load_more ) && $args->load_more == 1 ) {
    $show_no_of_events_card = ep_get_global_settings( 'show_no_of_events_card' );
    if( 'custom' == $show_no_of_events_card ) {
        $show_no_of_events_card = ep_get_global_settings( 'card_view_custom_value' );
    }
    if( ! empty( $args->events->posts ) && count( $args->events->posts ) >= $show_no_of_events_card ) {?>
        <div class="ep-events-load-more ep-frontend-loadmore ep-box-w-100 ep-my-4 ep-text-center ep-events-load-more-<?php echo esc_attr( $args->section_id );?>">
            <input type="hidden" id="ep-events-limit-<?php echo esc_attr( $args->section_id );?>" value="<?php echo esc_attr( $args->limit );?>"/>
            <input type="hidden" id="ep-events-order-<?php echo esc_attr( $args->section_id );?>" value="<?php echo esc_attr( $args->order );?>"/>
            <input type="hidden" id="ep-events-paged-<?php echo esc_attr( $args->section_id );?>" value="<?php echo esc_attr( $args->paged );?>"/>
            <input type="hidden" id="ep-events-style-<?php echo esc_attr( $args->section_id );?>" value="<?php echo esc_attr( $args->display_style );?>"/>
            <input type="hidden" id="ep-events-types-ids-<?php echo esc_attr( $args->section_id );?>" value="<?php echo esc_attr( isset( $args->types_ids ) ? implode( ',', $args->types_ids ) : '' ); ?>"/>
            <input type="hidden" id="ep-events-venues-ids-<?php echo esc_attr( $args->section_id );?>" value="<?php echo esc_attr( isset( $args->venue_ids ) ? implode( ',', $args->venue_ids ) : '' ); ?>"/>
            <input type="hidden" id="ep-events-cols-<?php echo esc_attr( $args->section_id );?>" value="<?php echo esc_attr( $args->cols );?>"/>
            <input type="hidden" id="ep-events-i-events-<?php echo esc_attr( $args->section_id );?>" value="<?php echo esc_attr( $args->i_events );?>"/>
            <button data-max="<?php echo esc_attr( $args->events->max_num_pages );?>" id="ep-loadmore-events" class="ep-btn ep-btn-outline-primary ep-loadmore-events" data-section_id="<?php echo esc_attr( $args->section_id );?>">
                <span class="ep-spinner ep-spinner-border-sm ep-mr-1 ep-spinner-<?php echo esc_attr( $args->section_id );?>"></span>
                <?php echo wp_kses_post( $args->load_more_text );   ?>
            </button>
        </div><?php
    }
}?>

