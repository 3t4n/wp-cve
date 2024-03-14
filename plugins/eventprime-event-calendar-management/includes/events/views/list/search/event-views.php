<div class="ep-btn-group ep-overflow-hidden ep-event-views-filter-group" role="Event view Filters">
    <?php if( in_array( 'month', $args->event_views ) || in_array( 'week', $args->event_views ) || in_array( 'day', $args->event_views ) || in_array( 'listweek', $args->event_views ) ) {?>
        <button type="button" class="ep-btn ep-btn-outline-primary ep_event_view_filter <?php if($args->display_style == 'month'){echo 'ep-active-view';}?>" id="ep_event_view_calendar" title="<?php esc_html_e( 'Calendar View', 'eventprime-event-calendar-management' ); ?>" data-event_view="<?php echo esc_attr( 'month' );?>">
            <span class="material-icons-outlined ep-fs-6">calendar_month</span>
        </button>
    <?php } ?>
    
    <?php if( in_array( 'card', $args->event_views ) || in_array( 'square_grid', $args->event_views ) ) {
        $square_grid = ( in_array( 'card', $args->event_views ) ? 'card' : 'square_grid' );?>
        <button type="button" class="ep-btn ep-btn-outline-primary ep_event_view_filter <?php if( $args->display_style == 'card' || $args->display_style == 'square_grid' ){echo 'ep-active-view';}?>" id="ep_event_view_card" title="<?php esc_html_e( 'Square Grid View', 'eventprime-event-calendar-management' ); ?>" data-event_view="<?php echo esc_attr( $square_grid );?>">
            <span class="material-icons-outlined ep-fs-6">grid_view</span>
        </button>
    <?php } ?>
    
    <?php if( in_array( 'list', $args->event_views ) || in_array( 'rows', $args->event_views ) ) {
        $rows = ( in_array( 'list', $args->event_views ) ? 'list' : 'rows' );?>
        <button type="button" class="ep-btn ep-btn-outline-primary ep_event_view_filter <?php if( $args->display_style == 'list' || $args->display_style == 'rows' ){echo 'ep-active-view';}?>" id="ep_event_view_list" title="<?php esc_html_e( 'Stacked Rows View', 'eventprime-event-calendar-management' ); ?>" data-event_view="<?php echo esc_attr( $rows );?>">
            <span class="material-icons-outlined ep-fs-6">view_agenda</span>
        </button>
    <?php } ?>
    
    <?php if( in_array( 'masonry', $args->event_views ) || in_array( 'staggered_grid', $args->event_views ) ) {
        $staggered_grid = ( in_array( 'masonry', $args->event_views ) ? 'masonry' : 'staggered_grid' );?>
        <button type="button" class="ep-btn ep-btn-outline-primary ep_event_view_filter <?php if( $args->display_style == 'masonry' || $args->display_style == 'staggered_grid' ){echo 'ep-active-view';}?>" id="ep_event_view_masonry" title="<?php esc_html_e( 'Staggered Grid View', 'eventprime-event-calendar-management' ); ?>" data-event_view="<?php echo esc_attr( $staggered_grid );?>">
            <span class="material-icons-outlined ep-fs-6">dashboard</span>
        </button>
    <?php } ?>
    
    <?php if( in_array( 'slider', $args->event_views ) ) {?>
        <button type="button" class="ep-btn ep-btn-outline-primary ep_event_view_filter <?php if($args->display_style == 'slider'){echo 'ep-active-view';}?>" id="ep_event_view_slider" title="<?php esc_html_e( 'Slider View', 'eventprime-event-calendar-management' ); ?>" data-event_view="<?php echo esc_attr( 'slider' );?>">
            <span class="material-icons-outlined ep-fs-6">panorama_horizontal</span>
        </button>
    <?php } ?>
</div>