<?php
/**
 * Event datetime panel html.
 */
defined('ABSPATH') || exit;
$date_format = 'Y-m-d';
if( ! empty( ep_get_global_settings( 'datepicker_format' ) ) ) {
    $datepicker_format = explode( '&', ep_get_global_settings( 'datepicker_format' ) );
    if( ! empty( $datepicker_format ) ) {
        $date_format = $datepicker_format[1];
    }
}
$em_start_date                         = get_post_meta( $post->ID, 'em_start_date', true );
$em_end_date                           = get_post_meta( $post->ID, 'em_end_date', true );
$em_start_time                         = get_post_meta( $post->ID, 'em_start_time', true );
$em_end_time                           = get_post_meta( $post->ID, 'em_end_time', true );
$em_hide_event_start_time              = get_post_meta( $post->ID, 'em_hide_event_start_time', true );
$em_hide_event_start_date              = get_post_meta( $post->ID, 'em_hide_event_start_date', true );
$em_all_day                            = get_post_meta( $post->ID, 'em_all_day', true );
$em_hide_event_end_time                = get_post_meta( $post->ID, 'em_hide_event_end_time', true );
$em_hide_end_date                      = get_post_meta( $post->ID, 'em_hide_end_date', true );
$em_event_date_placeholder             = get_post_meta( $post->ID, 'em_event_date_placeholder', true );
$em_event_date_placeholder_custom_note = get_post_meta( $post->ID, 'em_event_date_placeholder_custom_note', true );
$em_event_more_dates                   = get_post_meta( $post->ID, 'em_event_more_dates', true );
$em_event_add_more_dates               = get_post_meta( $post->ID, 'em_event_add_more_dates', true );
$disabled_fields_for_all_day = '';
if( ! empty( $em_all_day ) ) {
    $disabled_fields_for_all_day = 'disabled';
}
/* if( is_int( $em_start_date ) ) {
	$em_start_date = ep_timestamp_to_date( $em_start_date, $date_format );
    epd($em_start_date);
}
if( is_int( $em_end_date ) ) {
	$em_end_date = ep_timestamp_to_date( $em_end_date, $date_format );
} */

$em_start_date = ep_timestamp_to_date( $em_start_date, $date_format );
$em_end_date = ep_timestamp_to_date( $em_end_date, $date_format );?>
<div id="ep_event_datetime_data" class="panel ep_event_options_panel">
    <!-- <div class="postbox-header ep-metabox-title"><h2><?php esc_html_e('Date and Time', 'eventprime-event-calendar-management'); ?></h2></div> -->
    <div class="ep-box-wrap ep-my-3">
        <div class="ep-box-row ep-mb-3 ep-items-end">
            <div class="ep-box-col-3 ep-meta-box-data">
                <label class="ep-form-label"><?php esc_html_e('Start Date', 'eventprime-event-calendar-management'); ?>
                    <span class="ep-help-tip" tooltip="<?php esc_html_e( 'The day this event starts. The date will be visible on the event page and the event listing page unless you choose to hide it. You can set format for frontend date from Settings → Frontend Views', 'eventprime-event-calendar-management' );?>" tooltip-position="bottom"  ep-icon-position="relative"></span>
                    <span id="ep-start-date-hidden" class="material-icons ep-text-muted" style="display: none;">visibility_off</span>
                </label>
                <div class="ep-event-start-date">
                    <input type="text" name="em_start_date" id="em_start_date" class="ep-form-control epDatePicker" autocomplete="off" placeholder="<?php esc_html_e('Start Date', 'eventprime-event-calendar-management'); ?>" value="<?php echo esc_html( $em_start_date );?>">
                </div>
            </div>

            <div class="ep-box-col-3 ep-items-end ep-meta-box-data">
                <label class="ep-form-label"><?php esc_html_e('Start Time (optional)', 'eventprime-event-calendar-management'); ?>
                    <span class="ep-help-tip" tooltip="<?php esc_html_e( 'The time at which the event starts. This is optional.  If entered, the time will be visible on the event page and the event listing page unless you choose to hide it. You can set format for time from Settings. This option is disabled if you select It\'s an all day event.', 'eventprime-event-calendar-management' );?>" tooltip-position="bottom"  ep-icon-position="relative"></span>
                    <span id="ep-start-time-hidden" class="material-icons ep-text-muted" style="display: none;">visibility_off</span>
                </label>
                <div class="ep-event-start-time">
                    <input type="text" id="em_start_time" name="em_start_time" class="ep-form-control epTimePicker" value="<?php echo esc_html( $em_start_time );?>" <?php echo esc_attr( $disabled_fields_for_all_day );?>>
                </div>
            </div>
            
            <div class="ep-box-col-3 ep-d-flex ep-items-center">
                <div class="ep-form-check ep-form-check-inline ep-mb-0">
                    <input class="ep-form-check-input" type="checkbox" name="em_hide_event_start_time" id="ep_hide_event_time" value="1" <?php if( absint( $em_hide_event_start_time ) == 1 ) { echo 'checked="checked"'; }?> <?php echo esc_attr( $disabled_fields_for_all_day );?>>
                    <label class="ep-form-check-label" for="ep_hide_event_time">
                        <?php esc_html_e( 'Hide Start Time', 'eventprime-event-calendar-management' );?>
                        <span class="ep-help-tip" tooltip="<?php esc_html_e( 'Hide event\'s starting time from frontend users. This option is disabled if you select It\'s an all day event.', 'eventprime-event-calendar-management' );?>" tooltip-position="bottom"  ep-icon-position="relative"></span>
                    </label>
                </div>
            </div>

            <div class="ep-box-col-3 ep-d-flex ep-items-center">
                <div class="ep-form-check ep-form-check-inline ep-mb-0">
                    <input class="ep-form-check-input ep-event-date-check" type="checkbox" name="em_hide_event_start_date" id="ep-hide-start-date" value="1" <?php if( absint( $em_hide_event_start_date ) == 1 ) { echo 'checked="checked"'; }?> >
                    <label class="ep-form-check-label" for="ep-hide-start-date">
                        <?php esc_html_e( 'Hide Start Date', 'eventprime-event-calendar-management' );?>
                        <span class="ep-help-tip" tooltip="<?php esc_html_e( 'Hide event start date from frontend users.', 'eventprime-event-calendar-management' );?>" tooltip-position="bottom"  ep-icon-position="relative"></span>
                    </label>
                </div>
            </div> 
        </div>
        
        <!-- All day Event Row-->
        <div class="ep-box-row ep-mb-3">
            <div class="ep-box-col-12 ep-d-flex ep-items-center ep-meta-box-data">
                <div class="ep-form-check ep-form-check-inline">
                    <input class="ep-form-check-input" type="checkbox" name="em_all_day" id="em_all_day" value="1" <?php if( absint( $em_all_day ) == 1 ) { echo 'checked="checked"'; }?> >
                    <label class="ep-form-check-label" for="em_all_day">
                        <?php esc_html_e( 'It\'s an all day event', 'eventprime-event-calendar-management' );?>
                         <span class="ep-help-tip" tooltip="<?php esc_html_e( 'An all day event starts and ends on the same date and lasts between 12:00 AM and 11:59 PM.', 'eventprime-event-calendar-management' );?>" tooltip-position="bottom"  ep-icon-position="relative"></span>
                    </label>
                </div>
            </div>
        </div>

        <!-- All day Event Row Ends:-->
        <div class="ep-box-row ep-py-3 ep-mb-3 ep-items-end">
            <div class="ep-box-col-3 ep-meta-box-data">
                <label class="ep-form-label"> <?php esc_html_e( 'End Date', 'eventprime-event-calendar-management' ); ?>
                   <span class="ep-help-tip" tooltip="<?php esc_html_e( 'The day this event ends. The date will be visible on the event page and the event listing page unless you choose to hide it. You can set format for frontend date from Settings → Frontend Views. This option is disabled if you select It\'s an all day event.', 'eventprime-event-calendar-management' );?>" tooltip-position="bottom"  ep-icon-position="relative"></span>
                    <span id="ep-end-date-hidden" class="material-icons ep-text-muted" style="display: none;">visibility_off</span>
                </label>
                <div class="ep-event-end-date">
                    <input type="text" name="em_end_date" id="em_end_date" class="ep-form-control epDatePicker" autocomplete="off" placeholder="<?php esc_html_e('End Date', 'eventprime-event-calendar-management'); ?>" value="<?php echo esc_html( $em_end_date );?>" <?php echo esc_attr( $disabled_fields_for_all_day );?>>
                </div>
            </div>
            
            <div class="ep-box-col-3 ep-meta-box-data">
                <label class="ep-form-label"><?php esc_html_e( 'End Time (optional)', 'eventprime-event-calendar-management' ); ?>
                    <span class="ep-help-tip" tooltip="<?php esc_html_e( 'The time at which the event ends. This is optional.  If entered, the time will be visible on the event page and the event listing page unless you choose to hide it. The end time is also used to calculate and publish the duration of the event on the frontend event page. You can set format for time from Settings. This option is disabled if you select It\'s an all day event.', 'eventprime-event-calendar-management' );?>" tooltip-position="bottom"  ep-icon-position="relative"></span>
                    <span id="ep-end-time-hidden" class="material-icons ep-text-muted" style="display: none;">visibility_off</span>
                </label>
                <div class="ep-event-start-time">
                    <input type="text" id="em_end_time" name="em_end_time" class="ep-form-control epTimePicker" value="<?php echo esc_html( $em_end_time );?>" <?php echo esc_attr( $disabled_fields_for_all_day );?>>                
                </div>
            </div>
            
            <div class="ep-box-col-3 ep-d-flex ep-items-center">
                <div class="ep-form-check ep-form-check-inline ep-mb-0">
                    <input class="ep-form-check-input" type="checkbox" name="em_hide_event_end_time" id="ep_hide_event_end_time" value="1" <?php if( absint( $em_hide_event_end_time ) == 1 ) { echo 'checked="checked"'; }?> <?php echo esc_attr( $disabled_fields_for_all_day );?>>
                    <label class="ep-form-check-label" for="ep_hide_event_end_time"><?php esc_html_e( 'Hide End Time', 'eventprime-event-calendar-management' ); ?>
                      <span class="ep-help-tip" tooltip="<?php esc_html_e( 'Hide event\'s ending time from frontend users. This option is disabled if you select It\'s an all day event.', 'eventprime-event-calendar-management' );?>" tooltip-position="bottom"  ep-icon-position="relative"></span>
                    </label>
                </div>
            </div>

            <div class="ep-box-col-3 ep-d-flex ep-items-center">
                <div class="ep-form-check ep-form-check-inline ep-mb-0">
                    <input class="ep-form-check-input date-check" type="checkbox" name="em_hide_end_date" id="ep-hide-end-date" value="1" <?php if( absint( $em_hide_end_date ) == 1 ) { echo 'checked="checked"'; }?> <?php echo esc_attr( $disabled_fields_for_all_day );?>>
                    <label class="ep-form-check-label" for="ep-hide-end-date"><?php esc_html_e( 'Hide End Date', 'eventprime-event-calendar-management' ); ?>
                    <span class="ep-help-tip" tooltip="<?php esc_html_e( 'Hide event\'s ending date from frontend users. This option is disabled if you select It\'s an all day event.', 'eventprime-event-calendar-management' );?>" tooltip-position="bottom"  ep-icon-position="relative"></span></label>
                </div>
            </div> 
        </div>

        <!-- Date Placeholder Options -->
        <div class="ep-box-row ep-py-3" id="ep-date-note" <?php if( empty( $em_hide_event_start_date ) ) { echo 'style="display: none;"';} ?> >
            <div class="ep-box-12 ep-mb-3">
                <div class="ep-form-check ep-form-check-inline">
                    <input class="ep-form-check-input" type="radio" name="em_event_date_placeholder" id="tbd" value="tbd" <?php if( esc_attr( $em_event_date_placeholder ) == 'tbd' ) { echo 'checked="checked"'; }?> >
                    <label class="ep-form-check-label" for="tbd" style="width: 25px;">
                        <?php $tbd_icon_file = EP_BASE_URL .'/includes/assets/images/tbd-icon.png';?>
                        <img src="<?php echo esc_url( $tbd_icon_file );?>" width="25" />
                    </label>
                </div>
                <div class="ep-form-check ep-form-check-inline">
                    <input class="ep-form-check-input" type="radio" name="em_event_date_placeholder" id="ep-date-custom-note" value="custom_note" <?php if( esc_attr( $em_event_date_placeholder ) == 'custom_note' ) { echo 'checked="checked"'; }?> >
                    <label class="ep-form-check-label" for="ep-date-custom-note">
                        <?php esc_html_e( 'Custom Text', 'eventprime-event-calendar-management'); ?>
                    </label>
                </div>
            </div>
            <div class="col-md-12" id="ep-date-custom-note-content" <?php if( esc_attr( $em_event_date_placeholder ) != 'custom_note' ) { echo 'style="display:none;"'; }?> >
                <label class="ep-form-label"><?php esc_html_e( 'Date Placeholder Note', 'eventprime-event-calendar-management'); ?></label>
                <input type="text" class="ep-form-control" name="em_event_date_placeholder_custom_note" value="<?php echo esc_html( $em_event_date_placeholder_custom_note );?>">
                <span class="ep-text-muted ep-text-small">
                <?php printf( esc_html__( 'Since you chose to hide both the dates, this note will be displayed where date usually appears on the frontned. You can use text like %s etc. here.', 'eventprime-event-calendar-management' ), '<strong>To Be Decided</strong>' ); ?></span>
            </div>
        </div>

        <!-- Additional Dates Question Wrapper  --> 
        <div class="ep-box-row ep-py-3">
            <div class="ep-box-col-12 ep-d-fle ep-items-center">
                  <div class="ep-form-check ep-form-check-inline">
                    <input class="ep-form-check-input" type="checkbox" name="em_event_more_dates" id="ep-add-more-dates" value="1" <?php if( absint( $em_event_more_dates ) == 1 ) { echo 'checked="checked"'; }?> >
                    <label class="ep-form-check-label" for="ep-add-more-dates"><?php esc_html_e( 'Additional relevant dates', 'eventprime-event-calendar-management' ); ?>
                    <span class="ep-help-tip" tooltip="<?php esc_html_e( 'Additional dates allow you to label and save certain dates related to this event (apart from the start and the end dates). Labels of these dates appear inside dropdown with certain date based options, for example tickets sale start date, offer start date etc. You can save a date here, and reuse it without entering the date again for different options. If you modify a date here, it will be updated for all options automatically, wherever it is used.', 'eventprime-event-calendar-management' );?>" tooltip-position="bottom"  ep-icon-position="relative"></span>
                    </label>
                </div>  
            </div>
        </div>
        <!-- Additional Dates --->

        <div class="ep-additional-date-wrapper" id="ep-event-additional-date-wrapper" <?php if( absint( $em_event_more_dates ) == 0 ) { echo 'style="display: none;"'; }?> >
            <!-- Add Data Button -->
            <div class="ep-box-row ep-pb-3">
                <div class="ep-box-col-3 ">
                    <button type="button" class="button button-primary button-large" id="add_new_date_field">
                        <?php esc_html_e('Add', 'eventprime-event-calendar-management'); ?>
                    </button>
                </div>
            </div>
            
            <?php $count = 1;
            if( !empty( $em_event_add_more_dates ) && count( $em_event_add_more_dates ) > 0 ) {
                foreach( $em_event_add_more_dates as $more ) {?> 
                    <div class=" ep-box-row ep-pb-3 ep-items-end ep-additional-date-row" id="ep-additional-date-row<?php echo esc_attr( $count );?>">
                        <input type="hidden" name="em_event_add_more_dates[<?php echo esc_attr( $count );?>][uid]" value="<?php echo esc_html( $more['uid'] );?>">
                       
                        <div class="ep-box-col-3 ep-meta-box-data">
                            <label class="ep-form-label"><?php esc_html_e( 'Date', 'eventprime-event-calendar-management' ); ?></label>
                            <div class="ep-event-start-time">
                                <input type="text" name="em_event_add_more_dates[<?php echo esc_attr( $count );?>][date]" class="ep-form-control ep-ad-event-date epDatePicker" autocomplete="off" value="<?php echo esc_html( ep_timestamp_to_date( $more['date'], $date_format ) );?>">
                            </div>
                        </div>

                        <div class="ep-box-col-3 ep-meta-box-data">
                            <label class="ep-form-label"><?php esc_html_e( 'Time (Optional)', 'eventprime-event-calendar-management' ); ?>
                            </label>
                            <div class="ep-event-start-time">
                                <input type="text" name="em_event_add_more_dates[<?php echo esc_attr( $count );?>][time]" class="ep-form-control ep-ad-event-time epTimePicker" autocomplete="off" value="<?php echo esc_html( $more['time'] );?>">                
                            </div>
                        </div>

                        <div class="ep-box-col-3 ep-meta-box-data">
                            <label class="ep-form-label"><?php esc_html_e( 'Label', 'eventprime-event-calendar-management' ); ?>
                            </label>
                            <div class="ep-event-start-time">
                                <input type="text" name="em_event_add_more_dates[<?php echo esc_attr( $count );?>][label]" placeholder="<?php esc_html_e( 'Label', 'eventprime-event-calendar-management' ); ?>" class="ep-form-control ep-ad-event-label" autocomplete="off" value="<?php echo esc_html( $more['label'] );?>">                
                            </div>
                        </div>
                        
                        <div class="ep-box-col-3 ">
                            <a href="javascript:void(0)" data-parent_id="ep-additional-date-row<?php echo esc_attr( $count );?>" class="ep-remove-additional-date ep-item-delete"><?php esc_html_e( 'Delete', 'eventprime-event-calendar-management' ); ?></a>
                        </div>
                    </div><?php
                    $count++;
                }
            } else{?>
                <div class=" ep-box-row ep-pb-3 ep-items-end ep-additional-date-row" id="ep-additional-date-row1">
                    <input type="hidden" name="em_event_add_more_dates[1][uid]" value="<?php echo esc_html( time() );?>">
                    <div class="ep-box-col-3 ep-meta-box-data">
                        <label class="ep-form-label"><?php esc_html_e( 'Date', 'eventprime-event-calendar-management' ); ?></label>
                        <div class="ep-event-start-time">
                            <input type="text" name="em_event_add_more_dates[1][date]" class="ep-form-control ep-ad-event-date epDatePicker" autocomplete="off">                
                        </div>
                    </div>

                    <div class="ep-box-col-3 ep-meta-box-data">
                        <label class="ep-form-label"><?php esc_html_e( 'Time (Optional)', 'eventprime-event-calendar-management' ); ?>
                        </label>
                        <div class="ep-event-start-time">
                            <input type="text" name="em_event_add_more_dates[1][time]" class="ep-form-control ep-ad-event-time epTimePicker" autocomplete="off">                
                        </div>
                    </div>

                    <div class="ep-box-col-3 ep-meta-box-data">
                        <label class="ep-form-label"><?php esc_html_e( 'Label', 'eventprime-event-calendar-management' ); ?>
                        </label>
                        <div class="ep-event-start-time">
                            <input type="text" name="em_event_add_more_dates[1][label]" placeholder="<?php esc_html_e( 'Label', 'eventprime-event-calendar-management' ); ?>" class="ep-form-control ep-ad-event-label" autocomplete="off">                
                        </div>
                    </div>

                    <div class="ep-box-col-3 ">
                      <a href="javascript:void(0)" data-parent_id="ep-additional-date-row<?php echo esc_attr( $count );?>" class="ep-remove-additional-date ep-item-delete"><?php esc_html_e( 'Delete', 'eventprime-event-calendar-management' ); ?></a>
                    </div>
                </div><?php
            }?>
        </div>
    </div>
</div>