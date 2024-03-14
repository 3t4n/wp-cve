<div class="ep-box-row ep-mt-3" id="ep_event_various_filters_section" style="display:none;">
    <div class="ep-box-col-12 ep-mb-3" id="ep_filter_count_clear" >
        <span class="ep-text-small ep-mr-2" id="ep_total_filters_applied"></span>
        <a class="ep-text-small" href="<?php echo esc_url( ep_get_custom_page_url( 'event_page' ) );?>"><?php esc_html_e( 'Clear All', 'eventprime-event-calendar-management' ); ?></a>
    </div>
    <div class="ep-box-col-12" id="ep_applied_filters_section" ></div>
</div>

<!-- Event Loader -->
<?php do_action( 'ep_add_loader_section', 'show' );?>
<!-- Event Loader End -->