<div class="ep-setting-tab-content">
    <h2><?php esc_html_e( 'Custom CSS', 'eventprime-event-calendar-management' );?></h2>
    <input type="hidden" name="em_setting_type" value="customcss_settings">

    <textarea id="em_custom_css" name="custom_css"><?php echo trim( $options['global']->custom_css );?></textarea>
    <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e( 'Custom CSS code which will be added to the pages serving EventPrime views. Useful for defining custom styles for EventPrime HTML tags.', 'eventprime-event-calendar-management' );?></div>
</div>