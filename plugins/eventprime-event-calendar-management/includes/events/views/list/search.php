<?php
/**
 * View: Events List - Search
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/eventprime/events/list/search.php
 *
 */
?>
<form id="ep_event_search_form" class="ep-box-search-form ep-box-bottom" name="ep_event_search_form" method="get" role="search">
    <div class="ep-bg-light ep-border ep-rounded ep-px-3 ep-box-search-form-wrap">
        <div class="ep-box-row">
            <div class="ep-box-col-8 ep-p-3 ep-position-relative ep-search-filter-bar">
                <div class="ep-input-group">
                    <span class="ep-input-group-text ep-bg-white ep-text-muted">
                        <span class="material-icons-outlined">search</span>
                    </span>
                    <input name="keyword" id="ep_event_keyword_search" type="search" class="ep-form-control ep-form-control-sm ep-border-start-0" placeholder="<?php esc_html_e( 'Search Keyword', 'eventprime-event-calendar-management' ); ?>" autocomplete="off">
                    <button class="ep-btn ep-btn-dark ep-btn-sm ep-z-index-1" type="button" id="ep_event_find_events_btn">
                        <?php esc_html_e( 'Find Events', 'eventprime-event-calendar-management' ); ?>
                    </button>
                </div>
                <?php
                // Load search filters template
                ep_get_template_part('events/list/search/search-filters', null, $args);
                ?>
                <div class="ep-search-filter-overlay" style="display: none;"></div>
            </div>
            <div class="ep-box-col-4 ep-p-3 ep-d-flex ep-align-items-center ep-justify-content-end ep-event-views-col">
                <?php
                // Load event views filters template
                ep_get_template_part('events/list/search/event-views', null, $args);
                ?>
            </div>
        </div>
    </div>
    <!-- Filters Applied Row -->
    <?php
    // Load event views filters template
    ep_get_template_part('events/list/search/applied-filters', null, $args);
    ?>
    <!-- Filters Applied Row End -->
</form>