<?php
/**
 * View: Events Search - eventlist
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/eventprime/events/list/serch/eventlist.php
 *
 */
?>

<div class="ep-box-col-3 ep-event-views ep-items-center ep-d-flex">
    <button class="ep-event-view-selector-button ep-button-text ep-transparent-bg ep-w-100 ep-items-center ep-d-flex" id="ep-event-view-selector" type="button">
        <span class="ep-event-view-selector-button-text" id="ep-event-view-selector-button-text"><?php esc_html_e( 'List', 'eventprime-event-calendar-management' ); ?></span>
        <span class="ep-view-icon-down" ><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000"><path d="M0 0h24v24H0z" fill="none"/><path d="M16.59 8.59L12 13.17 7.41 8.59 6 10l6 6 6-6z"/></svg></span>
        <span class="ep-view-icon-up" style="display: none;" ><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000"><path d="M0 0h24v24H0z" fill="none"/><path d="M12 8l-6 6 1.41 1.41L12 10.83l4.59 4.58L18 14z"/></svg></span>
    </button>
    <div class="ep-event-views-content ep-box-dropdown" style="display: none;">
        <div class="ep-box-dropdown-overlay"></div>
        <ul class="ep-event-views-content-list ep-list-group" id="ep-event-views-content-list" >
            <?php
            foreach( $args->event_views as $key => $view ) {?>
                <li class="ep-event-views-content-list-item ep-list-group-item" data-view="<?php echo esc_attr( $key );?>">
                    <?php echo esc_html( $view );?>
                </li><?php
            }?>
        </ul>
    </div>
</div>