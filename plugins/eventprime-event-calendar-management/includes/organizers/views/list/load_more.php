<?php
/**
 * View: Organizers List - Load More
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/eventprime/organizers/list/load_more.php
 *
 */
?>
<?php 
$max_num_pages = ceil( $args->organizers_count / $args->limit );
if( $max_num_pages > 1 && isset( $args->load_more ) && $args->load_more == 1 ) {?>
    <div class="ep-organizers-load-more ep-frontend-loadmore ep-box-w-100 ep-my-4 ep-text-center">
        <input type="hidden" id="ep-organizers-style" value="<?php echo $args->display_style;?>"/>
        <input type="hidden" id="ep-organizers-limit" value="<?php echo $args->limit;?>"/>
        <input type="hidden" id="ep-organizers-cols" value="<?php echo $args->column;?>"/>
        <input type="hidden" id="ep-organizers-featured" value="<?php echo $args->featured;?>"/>
        <input type="hidden" id="ep-organizers-popular" value="<?php echo $args->popular;?>"/>
        <input type="hidden" id="ep-organizers-search" value="<?php echo $args->enable_search;?>"/>
        <input type="hidden" id="ep-organizers-paged" value="<?php echo $args->paged;?>" />
        <input type="hidden" id="ep-organizers-box-color" value="<?php echo ( isset( $args->box_color ) && ! empty( $args->box_color ) ) ? implode( ',', $args->box_color ) : '';?>"/>
        <button data-max="<?php echo $max_num_pages;?>" id="ep-loadmore-event-organizers" class="ep-btn ep-btn-outline-primary"><span class="ep-spinner ep-spinner-border-sm ep-mr-1"></span><?php esc_html_e( 'Load more', 'eventprime-event-calendar-management' );?></button>
    </div><?php
}?>