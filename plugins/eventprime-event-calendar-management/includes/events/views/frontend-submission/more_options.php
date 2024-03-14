<?php
/**
 * View: Frontend Event Submission
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/eventprime/events/frontend-submission/more_options.php
 *
 */
?>
<?php if(isset($args->fes_event_more_options) && !empty($args->fes_event_more_options)):?>
    <div class="ep-fes-section ep-mb-4 ep-border ep-p-4 ep-shadow-sm ep-rounded-1 ep-rounded-1">
        <div class="ep-fes-section-title ep-fs-5 ep-fw-bold ep-mb-3"><?php esc_html_e('More Options', 'eventprime-event-calendar-management');?></div>
        
        <div class="ep-form-row ep-box-row ep-mt-2">
             <div class="ep-box-col-12">
            <label for="ep_facebook_page" class="ep-form-label">
                <?php esc_html_e('Facebook Page', 'eventprime-event-calendar-management');?>
            </label>
            <input type="url" name="facebook_page" id="ep_facebook_page" class="ep-form-input ep-input-checkbox ep-form-control" value="" placeholder="https://www.facebook.com/XYZ/"/>
             </div>
        </div>
        <div class="ep-form-row ep-box-row ep-mt-2">
            <div class="ep-box-col-12">
            <label for="ep_hide_event_from_calendar" class="ep-form-label">
                <?php esc_html_e('Hide on Events Calendar Widget', 'eventprime-event-calendar-management');?>
            </label>
            <input type="checkbox" name="hide_event_from_calendar" id="ep_hide_event_from_calendar" class="ep-form-input ep-input-checkbox" value="1" />
            </div>
        </div>
        <div class="ep-form-row ep-box-row ep-mt-2">
        <div class="ep-box-col-12">
            <label for="ep_hide_event_from_events" class="ep-form-label">
                <?php esc_html_e('Hide from Events Directory', 'eventprime-event-calendar-management');?>
            </label>
            <input type="checkbox" name="hide_event_from_events" id="ep_hide_event_from_events" onclick="" class="ep-form-input ep-input-checkbox ep-form-control" value="1" />
        </div>
        </div>
        <div class="ep-form-row ep-box-row ep-mt-2">
            <div class="ep-box-col-12">
            <label for="ep_audience_notice" class="ep-form-label">
                <?php esc_html_e('Note for Attendees', 'eventprime-event-calendar-management');?>
            </label>
            <textarea name="audience_notice" id="ep_audience_notice" class="ep-form-input ep-input-textarea ep-form-control"></textarea>
        </div>
        </div>
    </div>
<?php endif;?>