<?php
/**
 * Event schedule panel html.
 */
defined( 'ABSPATH' ) || exit;
?>
<div id="ep_event_schedule_data" class="panel ep_event_options_panel">
    <h4>
        <label class="ep-event-booking-enable">
            <input type="checkbox" name="em_enable_schedule" id="em_enable_schedule">
            <?php esc_html_e( 'Enable Event Schedule', 'eventprime-event-calendar-management' ); ?>
        </label>
    </h4>
    <div id="ep_show_scheduling_options" style="display: none;">
        <div class="ep-meta-box-section">
            <div class="ep-meta-box-title">
                <?php esc_html_e('Choose date', 'eventprime-event-calendar-management'); ?>
            </div>
            <div class="ep-meta-box-data">
                <div class="ep-event-schedule-date">
                    <input type="text" name="em_schedule_date[]" id="em_schedule_date" class="hasScheduleDatePicker" autocomplete="off" placeholder="<?php esc_html_e('Choose Date', 'eventprime-event-calendar-management');?>">
                    <button type="button" class="em-add-date-schedule" style="display: none;">
                        <?php esc_html_e('Add', 'eventprime-event-calendar-management'); ?>
                    </button>
                </div>
                <div class="ep-event-hourly-schedule-wrapper"></div>
            </div>
        </div>
    </div>
</div>