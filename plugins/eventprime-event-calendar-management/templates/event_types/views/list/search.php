<?php
/**
 * View: Event Types List - Search
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/eventprime/event_types/list/search.php
 *
 */
?>
<?php if( isset( $args->enable_search ) && $args->enable_search == 1 ) {
    $search_keyword = '';
    if( isset( $_GET['keyword'] ) && ! empty( $_GET['keyword'] ) ) {
        $search_keyword = sanitize_text_field( $_GET['keyword'] );
    }?>
    <form id="ep_event_type_search_form" class="ep-box-wrapz ep-box-search-form ep-box-bottom ep-mb-4" name="ep_performer_search_form" action="">
        <div class="ep-bg-light ep-border ep-rounded ep-px-3">
            <div class="ep-box-row">
                <div class="ep-box-col-8 ep-p-3 ep-position-relative">
                    <div class="ep-input-group">
                        <span class="ep-input-group-text ep-bg-white ep-text-muted">
                            <span class="material-icons-outlined">search</span>
                        </span>
                        <input type="hidden" name="ep_search" value="1" />
                        <input placeholder="<?php esc_attr_e( 'Keyword', 'eventprime-event-calendar-management' ); ?>" class="ep-form-control ep-form-control-sm ep-border-start-0" type="text" name="keyword" id="ep_keyword" value="<?php echo esc_attr( $search_keyword ); ?>" />  
                        <input class="ep-btn ep-btn-dark ep-btn-sm" type="submit" value="<?php esc_attr_e( 'Search', 'eventprime-event-calendar-management' ); ?>"/>
                    </div>
                </div>
                <div class="ep-box-col-4 ep-event-filter-block ep-text-item-right ep-d-inline-flex ep-align-items-center">
                    <?php if ( isset( $_GET['ep_search'] ) && ! empty( $_GET['ep_search'] ) && 1 == absint( $_GET['ep_search'] ) ) {
                        $event_types_page_url = get_permalink( ep_get_global_settings( 'event_types' ) );?>
                        <div class="ep-box-filter-search-buttons">
                            <a href="<?php echo esc_url( $event_types_page_url );?>">
                                <?php esc_html_e( 'Clear', 'eventprime-event-calendar-management' ); ?>
                            </a>   
                        </div><?php
                    }?>
                </div>
            </div>
        </div>
    </form><?php 
} ?>