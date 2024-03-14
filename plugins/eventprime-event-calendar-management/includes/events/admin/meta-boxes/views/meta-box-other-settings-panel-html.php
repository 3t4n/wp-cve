<?php
/**
 * Event other settings panel html.
 */
defined( 'ABSPATH' ) || exit;
$em_event_text_color = get_post_meta( $post->ID, 'em_event_text_color', true );
$em_hide_event_from_events = get_post_meta( $post->ID, 'em_hide_event_from_events', true );
$em_hide_event_from_calendar = get_post_meta( $post->ID, 'em_hide_event_from_calendar', true );
$em_audience_notice = get_post_meta( $post->ID, 'em_audience_notice', true );?>
<div id="ep_event_other_settings_data" class="panel ep_event_options_panel">
    <div class="ep-box-wrap ep-my-3">
        <div class="ep-box-row ep-mb-3 ep-items-end">
            <div class="ep-box-col-12 ep-meta-box-data">
                <label for="em_event_text_color" class="ep-other-setting-text-color-label"> <?php esc_html_e( 'Event Text Color', 'eventprime-event-calendar-management'); ?></label>
                <div class="ep-other-setting-text-color">
                    <input data-jscolor="{}" value="<?php echo ( ! empty( $em_event_text_color ) ? esc_html( $em_event_text_color ) : '' ); ?>" type="text" id="em_event_text_color_field" name="em_event_text_color_field" />
                    <input type="hidden" id="em_event_text_color" name="em_event_text_color" value="<?php echo ( ! empty( $em_event_text_color ) ? esc_html( $em_event_text_color ) : '' ); ?>" />
                </div>
                <div class="ep-text-muted ep-text-small"><?php esc_html_e( 'Color of this event\'s title text when it appears on the calendar on the frontend. Please note, this will override the color inherited through parent Event Type.', 'eventprime-event-calendar-management' ); ?></div>
            </div> 
        </div>
        
        <div class="ep-box-row ep-mb-3 ep-items-end">
            <div class="ep-box-col-12 ep-meta-box-data">
                <label for="em_audience_notice" class="ep-other-setting-customer-note-label"> <?php esc_html_e( 'Attendee Note', 'eventprime-event-calendar-management' ); ?></label>
                <div class="ep-other-setting-customer-note">
                    <textarea id="em_audience_notice" rows="5" cols="50" name="em_audience_notice" > <?php echo esc_html( $em_audience_notice );?> </textarea>
                </div>
                <div class="ep-text-muted ep-text-small"><?php esc_html_e( 'A custom message to show to the visitors on the event page. Can be used for important instructions like restrictions etc.', 'eventprime-event-calendar-management' ); ?></div>
            </div> 
        </div>  
    </div>
</div>