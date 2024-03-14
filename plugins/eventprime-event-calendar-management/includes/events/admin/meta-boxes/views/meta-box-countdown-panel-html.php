<?php
/**
 * Event countdown panel html
 */
defined( 'ABSPATH' ) || exit;
?>
<div id="ep_event_countdown_data" class="panel ep_event_options_panel">
    <!-- <div class="postbox-header ep-metabox-title">
        <h2><?php esc_html_e('New Countdown Timer', 'eventprime-event-calendar-management'); ?></h2>
    </div> -->
    <div class="ep-box-wrap">
        <div class="ep-box-row ep-p-3 ep-border ep-bg-light ep-rounded ep-m-3" id="ep-event-countdown-wrap">
            <div class="ep-box-col-12">
                <div class="ep-box-row ep-mb-3 ep-items-end">
                    <div class="ep-box-col-12 ep-meta-box-data">
                        <label class="ep-form-label"><?php esc_html_e( 'Countdown Name', 'eventprime-event-calendar-management' ); ?></label>
                        <input type="text" name="em_countdown_name" id="ep-countdown-name" class="ep-form-control" autocomplete="off" placeholder="<?php esc_html_e( 'Countdown Name', 'eventprime-event-calendar-management' ); ?>">
                        <div class="ep-error-message" id="ep_event_countdown_name_error"></div>
                    </div>
                </div>

                <div class="ep-box-row ep-mb-3 ep-items-start">
                    <!--Activates On-->
                    <div class="ep-box-col-4 ep-meta-box-data">
                        <div class="ep-box-row">
                            <div class="ep-box-col-12">
                                <label class="ep-form-label"><?php esc_html_e( 'Activates On', 'eventprime-event-calendar-management' ); ?></label>
                                <select class="ep-form-control" name="em_countdown_activate_on" id="ep-countdown-activates-on">
                                    <option value="right_away"><?php esc_html_e( 'Right Away', 'eventprime-event-calendar-management' );?></option>
                                    <option value="custom_date"><?php esc_html_e( 'Custom Date', 'eventprime-event-calendar-management' );?></option>
                                    <option value="event_date"><?php esc_html_e( 'Event Date', 'eventprime-event-calendar-management' );?></option>
                                    <option value="relative_date"><?php esc_html_e( 'Relative Date', 'eventprime-event-calendar-management' );?></option>
                                </select>
                            </div>

                            <div class="ep-box-col-6 ep-mt-3" id="ep-countdown-activates-date">
                                <input type="text" class="ep-form-control epDatePicker" name="em_countdown_activate_on_date" placeholder="<?php esc_html_e( 'Date', 'eventprime-event-calendar-management');?>">
                            </div> 

                            <div class="ep-box-col-6 ep-mt-3" id="ep-countdown-activates-time">
                                <input type="text" class="ep-form-control epTimePicker" placeholder="<?php esc_html_e( 'Time', 'eventprime-event-calendar-management');?>" name="em_countdown_activate_on_time">
                            </div> 

                            <div class="ep-box-col-6 ep-mt-3" id="ep-countdown-activates-days">
                                <input type="number" min="0" class="ep-form-control" name="em_countdown_activate_on_days">
                            </div> 

                            <div class="ep-box-col-6 ep-mt-3" id="ep-countdown-activates-relative-logic">
                                <select class="ep-form-control" name="em_countdown_activate_on_days_options">
                                    <option value="before"><?php esc_html_e( 'Days Before', 'eventprime-event-calendar-management');?></option>
                                    <option value="after"><?php esc_html_e( 'Days After', 'eventprime-event-calendar-management');?></option>
                                </select>
                            </div> 

                            <div class="ep-box-col-12 ep-mt-3">
                                <select class="ep-form-control" id="ep-countdown-activates-event-date" name="em_countdown_activate_on_event_options">
                                    <option value="event_start"><?php esc_html_e( 'Event Start', 'eventprime-event-calendar-management');?></option>
                                    <option value="event_ends"><?php esc_html_e( 'Event Ends', 'eventprime-event-calendar-management');?></option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <!--Activates On End-->

                    <!--Count to-->
                    <div class="ep-box-col-4 ep-meta-box-data">
                        <div class="ep-box-row">
                            <div class="ep-box-col-12">
                                <label class="ep-form-label"><?php esc_html_e( 'Count To', 'eventprime-event-calendar-management');?></label>
                                <select class="ep-form-control" name="em_countdown_count_to" id="ep-countdown-countto-on">
                                    <option value="custom_date"><?php esc_html_e( 'Custom Date', 'eventprime-event-calendar-management');?></option>
                                    <option value="event_date"><?php esc_html_e( 'Event Date', 'eventprime-event-calendar-management');?></option>
                                    <option value="relative_date"><?php esc_html_e( 'Relative Date', 'eventprime-event-calendar-management');?></option>
                                </select>
                            </div>

                            <div class="ep-box-col-6 ep-mt-3" id="ep-countdown-countto-date">
                                <input type="text" class="ep-form-control epDatePicker" name="em_countdown_count_to_date" placeholder="<?php esc_html_e( 'Date', 'eventprime-event-calendar-management');?>">
                            </div> 

                            <div class="ep-box-col-6 ep-mt-3" id="ep-countdown-countto-time">
                                <input type="text" class="ep-form-control epTimePicker" placeholder="<?php esc_html_e( 'Time', 'eventprime-event-calendar-management');?>" name="em_countdown_count_to_time">
                            </div> 

                            <div class="ep-box-col-6 ep-mt-3" id="ep-countdown-countto-days">
                                <input type="number" min="0" class="ep-form-control" name="em_countdown_count_to_days">
                            </div> 

                            <div class="ep-box-col-6 ep-mt-3" id="ep-countdown-countto-relative-logic">
                                <select class="ep-form-control" name="em_countdown_count_to_days_options">
                                    <option value="before"><?php esc_html_e( 'Days Before', 'eventprime-event-calendar-management');?></option>
                                    <option value="after"><?php esc_html_e( 'Days After', 'eventprime-event-calendar-management');?></option>
                                </select>
                            </div> 

                            <div class="ep-box-col-12 ep-mt-3">
                                <select class="ep-form-control" id="ep-countdown-countto-event-date" name="em_countdown_count_to_event_options">
                                    <option value="event_start"><?php esc_html_e( 'Event Start', 'eventprime-event-calendar-management');?></option>
                                    <option value="event_ends"><?php esc_html_e( 'Event Ends', 'eventprime-event-calendar-management');?></option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <!--Count to Ends-->
                    
                    <!--Messages-->
                    <div class="ep-box-col-4">
                        <div class="ep-box-row">
                            <div class="ep-box-col-12">
                                <div class="ep-form-check ep-mt-4">
                                    <input class="ep-form-check-input" type="checkbox" name="em_countdown_display_seconds" value="1" id="ep-show-seconds" checked>
                                    <label class="ep-form-label" for="ep-show-seconds">
                                        <?php esc_html_e( 'Display Seconds', 'eventprime-event-calendar-management');?>
                                    </label>
                                </div>        
                                <label class="ep-form-label ep-mt-3"><?php esc_html_e( 'Message After The Timer Ends', 'eventprime-event-calendar-management');?></label>
                                <textarea class="ep-form-control" rows="2" name="em_countdown_timer_message"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!--Button Area-->
                <div class="ep-box-row">
                    <div class="ep-box-col-12 ep-mt-3">
                        <input type="button" class="button button-primary button-large" id="ep-event-countdown-save" value="<?php esc_html_e( 'Save Countdown', 'eventprime-event-calendar-management');?>">
                    </div>                     
                </div>
                <!--Button Area-->
            </div>
        </div>
        <!-- Top-Box Row Ends -->
        <!--Existing timers-->
        <input type="hidden" name="em_event_countdown_timers" id="em_event_countdown_timers" value="" />
        <div class="ep-box-row ep-p-3" id="existing-countdown-wrapper" style="display: none;">
            <div class="ep-box-col-12 ep-mb-3">
                <strong><?php esc_html_e( 'Existing Coundown Timers', 'eventprime-event-calendar-management');?></strong>
            </div>
            <div id="ep-existing-countdown-timers"></div>
        </div>
        
    </div>
</div>