<?php
/**
 * View: Events Search - Submit Button
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/eventprime/events/list/serch/submit.php
 *
 */
?>

<div class="ep-box-col-6">
    <div class="ep-box-filter-search-buttons">    
        <input class="ep-transparent-bg ep-rounded" type="submit" value="<?php esc_html_e( 'Find Events', 'eventprime-event-calendar-management' ); ?>"/>
    </div>
    <?php if( isset( $_GET['ep_search'] ) ) {
        $event_page_url = get_permalink( ep_get_global_settings( 'events_page' ) );?>
        <div class="ep-box-filter-search-buttons">
            <a href="<?php echo esc_url( $event_page_url );?>">
                <?php esc_html_e( 'Clear', 'eventprime-event-calendar-management' ); ?>
            </a>
        </div><?php
    }?>
</div>

<div class="ep-box-col-3"></div>