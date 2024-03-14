<?php
/**
 * Event recurrence panel html
 */
defined( 'ABSPATH' ) || exit;
if( empty( $post->post_parent ) ) {
    $em_start_date                 = get_post_meta( $post->ID, 'em_start_date', true );
    $today_date                    = ( ! empty( $em_start_date ) ) ? ep_get_day_with_position( date( 'j', $em_start_date ) ): ep_get_day_with_position ( date( 'j' ) );
    $em_enable_recurrence          = get_post_meta( $post->ID, 'em_enable_recurrence', true );
    $em_recurrence_step            = get_post_meta( $post->ID, 'em_recurrence_step', true );
    $em_recurrence_step            = ( ! empty( $em_recurrence_step ) ? $em_recurrence_step : 1);
    $em_recurrence_interval        = get_post_meta( $post->ID, 'em_recurrence_interval', true );
    $em_recurrence_ends            = get_post_meta( $post->ID, 'em_recurrence_ends', true );
    if( empty( $em_recurrence_ends ) ) {
        $em_recurrence_ends = 'on';
    }
    $em_recurrence_limit           = get_post_meta( $post->ID, 'em_recurrence_limit', true );
    $em_recurrence_occurrence_time = get_post_meta( $post->ID, 'em_recurrence_occurrence_time', true );
    $em_recurrence_occurrence_time = ( ! empty( $em_recurrence_occurrence_time ) ? $em_recurrence_occurrence_time : 12 );
    $em_selected_weekly_day        = get_post_meta( $post->ID, 'em_selected_weekly_day', true );
    $em_selected_weekly_day        = ( ! empty( $em_selected_weekly_day ) ? (array)$em_selected_weekly_day : array( date('w') ) );
    $em_recurrence_monthly_day     = get_post_meta( $post->ID, 'em_recurrence_monthly_day', true );
    $em_recurrence_monthly_weekno  = get_post_meta( $post->ID, 'em_recurrence_monthly_weekno', true );
    $em_recurrence_monthly_fullweekday  = get_post_meta( $post->ID, 'em_recurrence_monthly_fullweekday', true );
    $em_recurrence_yearly_day      = get_post_meta( $post->ID, 'em_recurrence_yearly_day', true );
    $em_recurrence_yearly_weekno   = get_post_meta( $post->ID, 'em_recurrence_yearly_weekno', true );
    $em_recurrence_yearly_fullweekday = get_post_meta( $post->ID, 'em_recurrence_yearly_fullweekday', true );
    $em_recurrence_yearly_monthday = get_post_meta( $post->ID, 'em_recurrence_yearly_monthday', true );
    $em_recurrence_advanced_dates  = get_post_meta( $post->ID, 'em_recurrence_advanced_dates', true );
    $em_recurrence_selected_custom_dates = get_post_meta( $post->ID, 'em_recurrence_selected_custom_dates', true );
    $em_add_slug_in_event_title    = get_post_meta( $post->ID, 'em_add_slug_in_event_title', true );
    $em_event_slug_type_options    = get_post_meta( $post->ID, 'em_event_slug_type_options', true );
    $em_recurring_events_slug_format = get_post_meta( $post->ID, 'em_recurring_events_slug_format', true );
    $child_events = EventM_Factory_Service::ep_get_child_events( $post->ID );
    $count_child_events = ( ! empty( $child_events ) ? count( $child_events ) : 0 );
    $ep_get_current_week_no = ep_get_current_week_no();?>

    <div id="ep_event_recurrence_data" class="panel ep_event_options_panel">
        <input type="hidden" name="ep_event_count_child_events" id="ep_event_count_child_events" value="<?php echo absint( $count_child_events );?>" />
        <input type="hidden" name="ep_event_child_events_update_confirm" id="ep_event_child_events_update_confirm" value="" />
        <div class="ep-box-wrap ep-my-3">
            <div class="ep-box-row">
                <div class="ep-box-col-4 ep-d-flex ep-items-center">
                    <div class="form-check form-check-inline">
                        <label class="form-check-label" for="ep_enable_recurrence">
                            <input type="checkbox" name="em_enable_recurrence" id="ep_enable_recurrence" value="1" <?php if( absint( $em_enable_recurrence ) == 1 ) { echo 'checked="checked"'; }?> >
                            <?php esc_html_e( 'Repeat this event', 'eventprime-event-calendar-management' ); ?>
                        </label>
                    </div>
                </div> 
            </div>
        
            <div id="ep_show_recurring_options" <?php if( empty( absint( $em_enable_recurrence ) ) ) { echo 'style="display: none;"'; }?> >
                <div class="ep-meta-box-section">
                    <div class="ep-box-row ep-mb-3 ep-mt-3 ep-items-end">
                        
                        <div class="ep-box-col-3 ep-event-repeats" id="ep_event_repeats_every_step" <?php if( $em_recurrence_interval === 'custom_dates' ) { echo 'style="display: none;"'; }?>>
                            <label class="ep-form-label ep-my-1"><?php esc_html_e( 'Repeat Every', 'eventprime-event-calendar-management' ); ?></label>
                            <input type="number" name="em_recurrence_step" class="ep-form-control" id="em_recurrence_step" min="1" step="1" value="<?php echo esc_attr( $em_recurrence_step );?>">
                        </div>
                        <div class="ep-box-col-3 ep-event-repeats">
                            <select name="em_recurrence_interval" id="em_recurrence_interval" class="ep-form-control">
                                <?php $repeats = self::ep_get_recurrence_interval();
                                foreach( $repeats as $key => $repeat ) {?>
                                    <option value="<?php echo esc_attr( $key );?>" <?php if( $em_recurrence_interval == $key ) { echo 'selected="selected"'; }?>>
                                        <?php echo esc_html( $repeat );?>
                                    </option><?php
                                }?>
                            </select>
                        </div>
                    
                        <!-- Weekly Options -->
                        <div class="ep-box-col-12 ep-mt-3 ep-d-flex ep-items-center" id="em_show_weekly_options" <?php if( $em_recurrence_interval != 'weekly' ) { echo 'style="display: none;"'; }?> >
                            <label class="form-check form-check-inline ep-mr-2">
                                <?php esc_html_e('Repeat On', 'eventprime-event-calendar-management'); ?>
                            </label>
                            <div class="ep-show-weekly-options">
                                <?php foreach (ep_get_week_day_short() as $key => $sw) { ?>
                                    <label class="form-check form-check-inline ep-mr-2">
                                        <input type="checkbox" name="em_selected_weekly_day[]" value="<?php echo esc_attr( $key );?>" <?php if( in_array( $key, $em_selected_weekly_day ) ) { echo 'checked="checked"'; } ?>><?php echo esc_html( $sw ); ?>
                                    </label><?php 
                                }?>
                            </div>
                        </div>
                            
                        <!-- Monthly Options -->
                        <div class="ep-box-col-12 ep-mt-3" id="em_show_monthly_options" <?php if( $em_recurrence_interval != 'monthly' ) { echo 'style="display: none;"'; }?>>
                            <label class="ep-form-label">
                                <?php esc_html_e('Repeat On', 'eventprime-event-calendar-management'); ?>
                            </label>
                            <div class="ep-box-row em-monthly-recurr-options">
                                <div class="ep-box-col-12 ep-mt-3 ep-meta-box-data">
                                    <label for="em_recurrence_monthly_today">
                                        <input type="radio" name="em_recurrence_monthly_day" id="em_recurrence_monthly_today" value="<?php echo esc_attr( 'date' ); ?>" <?php if( $em_recurrence_monthly_day == 'date' ) { echo 'checked="checked"'; } ?>>
                                        <?php esc_html_e( $today_date . ' day of the Monthly', 'eventprime-event-calendar-management'); ?>
                                    </label>
                                </div>
                                <div class="ep-box-col-12 ep-mt-3 ep-meta-box-data">
                                    <label for="em_recurrence_monthly_custom_day">
                                        <input type="radio" name="em_recurrence_monthly_day" id="em_recurrence_monthly_custom_day" value="<?php echo esc_attr( 'day' ); ?>" <?php if( $em_recurrence_monthly_day == 'day' ) { echo 'checked="checked"'; } ?>>
                                        <select name="em_recurrence_monthly_weekno"><?php
                                            $current_week_no = ( ! empty( $em_recurrence_monthly_weekno ) ? $em_recurrence_monthly_weekno : $ep_get_current_week_no );
                                            foreach ( ep_get_week_number() as $dnum => $dname ) {?>
                                                <option value="<?php echo esc_attr( $dnum ); ?>" 
                                                    <?php if ( $current_week_no == $dnum ) { echo 'selected="selected"';} ?> >
                                                    <?php echo esc_html( $dname ); ?>
                                                </option><?php 
                                            }?>
                                        </select>
                                    </label>

                                    <label for="em_recurrence_monthly_custom_weekname">
                                        <select name="em_recurrence_monthly_fullweekday" id="em_recurrence_monthly_weekname"><?php
                                            $current_day = ( ( is_null( $em_recurrence_monthly_fullweekday ) || $em_recurrence_monthly_fullweekday == '' ) ? date('w') : $em_recurrence_monthly_fullweekday );
                                            foreach ( ep_get_week_day_full() as $wnum => $wname ) {?>
                                                <option value="<?php echo esc_attr( $wnum ); ?>" 
                                                    <?php if ( $current_day == $wnum ) { echo 'selected="selected"'; } ?> >
                                                    <?php echo esc_html( $wname ); ?>
                                                </option><?php 
                                            }?>
                                        </select>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <!-- Monthly Options End -->
                    </div>
                </div>

                <!-- Yearly Options -->
                <div id="em_show_yearly_options" <?php if( $em_recurrence_interval != 'yearly' ) { echo 'style="display: none;"'; }?>>
                    <div class="ep-box-row ep-mb-3 ep-items-end em-yearly-recurr-options">
                        <div class="ep-box-col-12 ep-my-1">
                            <?php esc_html_e( 'Repeat On', 'eventprime-event-calendar-management' ); ?>
                        </div>
                        <div class="ep-box-col-12 ep-meta-box-data ep-mt-3">
                            <label for="em_recurrence_monthly_today">
                                <?php $curr_month = date( 'F' );?>
                                <input type="radio" name="em_recurrence_yearly_day" id="em_recurrence_yearly_today" value="<?php echo esc_attr( 'date' ); ?>" <?php if( $em_recurrence_yearly_day == 'date' ) { echo 'checked="checked"'; } ?>>
                                <?php esc_html_e('Yearly on '. $curr_month . ' ' .$today_date, 'eventprime-event-calendar-management'); ?>
                            </label>
                        </div>
                        <div class="ep-box-col-12 ep-meta-box-data ep-mt-3">
                            <label for="em_recurrence_monthly_custom_day">
                                <input type="radio" name="em_recurrence_yearly_day" id="em_recurrence_yearly_custom_day" value="<?php echo esc_attr( 'day' ); ?>" <?php if( $em_recurrence_yearly_day == 'day' ) { echo 'checked="checked"'; } ?>>
                                <select name="em_recurrence_yearly_weekno">
                                    <?php 
                                    $current_week_no = ( ( is_null( $em_recurrence_yearly_weekno ) || $em_recurrence_yearly_weekno == '' ) ? $ep_get_current_week_no : $em_recurrence_yearly_weekno );
                                    foreach( ep_get_week_number() as $dnum => $dname ) {?>
                                        <option value="<?php echo esc_attr( $dnum );?>" <?php if( $current_week_no == $dnum ) { echo 'selected="selected"'; }?>>
                                            <?php echo esc_html( $dname );?>
                                        </option><?php
                                    }?>
                                </select>
                            </label>

                            <label for="em_recurrence_monthly_custom_weekname">
                                <select name="em_recurrence_yearly_fullweekday" id="em_recurrence_yearly_weekname">
                                    <?php 
                                    $current_day = ( ( is_null( $em_recurrence_yearly_fullweekday ) || $em_recurrence_yearly_fullweekday == '' ) ? date( 'w' ) : $em_recurrence_yearly_fullweekday );
                                    foreach( ep_get_week_day_full() as $wnum => $wname ) {?>
                                        <option value="<?php echo esc_attr( $wnum );?>" <?php if( $current_day == $wnum ) {echo 'selected="selected"';}?>>
                                            <?php echo esc_html( $wname );?>
                                        </option><?php
                                    }?>
                                </select>
                            </label>

                            <label for="em_recurrence_monthly_custom_month">
                                <select name="em_recurrence_yearly_monthday" id="em_recurrence_yearly_month">
                                    <?php 
                                    $current_month = ( ! empty( $em_recurrence_yearly_monthday ) ? $em_recurrence_yearly_monthday : date( 'n' ) );
                                    foreach( ep_get_month_name() as $mnum => $mname ) {?>
                                        <option value="<?php echo esc_attr( $mnum );?>" <?php if( $current_month == $mnum ) {echo 'selected="selected"';}?>>
                                            <?php echo esc_html( $mname );?>
                                        </option><?php
                                    }?>
                                </select>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Advanced days options -->
                <div id="em_show_advanced_options" <?php if( $em_recurrence_interval != 'advanced' ) { echo 'style="display: none;"'; }?>>
                    <div class="ep-recurrence-advanced-wrapper">
                        <input type="hidden" name="em_recurrence_advanced_dates" id="ep_recurrence_advanced_dates" value="<?php echo json_encode( $em_recurrence_advanced_dates );?>">
                        <?php foreach( ep_get_week_number() as $wk => $wnum) { ?>
                            <ul>
                                <li class="ep-recurrence-week"><?php echo $wnum;?></li>
                                <?php foreach( ep_get_week_day_medium() as $wm => $wn) { 
                                    $active_class = ( ! empty( $em_recurrence_advanced_dates ) && in_array( $wm . '-' . $wk, $em_recurrence_advanced_dates) ? 'active' : '' );?>
                                    <li class="ep-recurrence-advanced-week-day <?php echo esc_html( $active_class );?>" id="<?php echo esc_attr( $wm . '-' . $wk );?>" data-week_num="<?php echo esc_attr( $wk );?>" data-day_num="<?php echo esc_attr( $wm );?>" >
                                        <?php echo $wn;?>
                                    </li><?php
                                }?>
                            </ul><?php
                        }?>
                    </div>
                </div>

                <!-- Custom Dates options -->
                <div class="ep-meta-box-section" id="em_show_custom_dates_options" <?php if( $em_recurrence_interval != 'custom_dates' ) { echo 'style="display: none;"'; }?>>
                    <div class="ep-recurrence-custom-dates ep-box-row ep-mb-3 ep-mt-3 ep-items-end">
                        <div class="ep-box-col-3">
                            <input type="text" name="em_recurrence_custom_dates" class="ep-form-control" id="ep_recurrence_custom_dates" placeholder="<?php esc_html_e( 'Select Date', 'eventprime-event-calendar-management' ); ?>" autocomplete="off">
                            <input type="hidden" name="em_recurrence_selected_custom_dates" class="ep-form-control" id="ep_recurrence_selected_custom_dates" value="<?php echo json_encode($em_recurrence_selected_custom_dates); ?>">
                        </div>
                    </div>
                    <div class="ep_selected_dates_data ep-d-flex ep-flex-wrap">
                        <?php if( ! empty( $em_recurrence_selected_custom_dates ) && count( $em_recurrence_selected_custom_dates ) > 0 ) {
                            foreach( $em_recurrence_selected_custom_dates as $custom_dates ) {?>
                                <span class="ep-event-recurring-custom-date ep-fw-bold ep-mr-2 ep-mb-2  ep-px-2 ep-py-1 ep-border ep-rounded">
                                    <span class="ep-cus-date-cont"><?php echo esc_html( $custom_dates );?></span>
                                    <span class="ep-remove-custom-date">&times;</span>
                                </span><?php
                            }
                        }?>
                    </div>
                </div>

                <div class="ep-meta-box-section" id="ep_event_recurrence_end_options" <?php if( $em_recurrence_interval === 'custom_dates' ) { echo 'style="display: none;"'; }?>>
                    <div class="ep-box-row">
                        <div class="ep-box-col-12 ep-meta-box-data">
                            <h4>
                                <?php esc_html_e('Ends', 'eventprime-event-calendar-management'); ?>
                            </h4> 
                        </div>
                    </div>
                    <div class="ep-box-row ep-items-center"> 
                        <div class="ep-box-col-12 ep-mb-3 ep-meta-box-data">
                            <label for="em_recurrence_ends_on" class="ep-di-flex  ep-items-center ep-form-label ep-event-booking-ends">
                                <input type="radio" name="em_recurrence_ends" class="ep-form-check-input" id="em_recurrence_ends_on" value="on" <?php if( $em_recurrence_ends == 'on' ) { echo 'checked="checked"'; }?> >
                                <?php esc_html_e('On', 'eventprime-event-calendar-management'); ?>
                                <div class="em_event_recurrence_end_date ep-ml-2 ep-box-w-75">
                                    <input type="text" name="em_recurrence_limit" id="em_recurrence_limit" class="ep-form-control epDatePicker" <?php if( $em_recurrence_ends != 'on' ) { echo 'disabled="disabled"'; }?> placeholder="<?php echo esc_attr( 'End Date', 'eventprime-event-calendar-management' ); ?>" value="<?php echo ep_timestamp_to_date( esc_attr( $em_recurrence_limit ) );?>" autocomplete="off" >
                                </div>
                            </label>
                        </div>
                        <div class="ep-box-col-12 ep-mb-3 ep-meta-box-data ep-mt-2">
                            <label for="em_recurrence_ends_after" class="ep-di-flex  ep-items-center ep-form-label  ep-event-booking-ends">
                                <input type="radio" name="em_recurrence_ends" class="ep-form-check-input" id="em_recurrence_ends_after" value="after" <?php if( $em_recurrence_ends == 'after' ) { echo 'checked="checked"'; }?>>
                                <?php esc_html_e('After', 'eventprime-event-calendar-management'); ?>
                                <div class="em_event_recurrence_occurrence_time ep-ml-2">
                                    <input type="number" name="em_recurrence_occurrence_time" class="" id="em_recurrence_occurrence_time" min="0" step="1" value="<?php echo absint( $em_recurrence_occurrence_time );?>" <?php if( $em_recurrence_ends != 'after' ) { echo 'disabled="disabled"'; }?>  placeholder="<?php echo esc_attr( 'Occurrences', 'eventprime-event-calendar-management' ); ?>" >
                                    <label> <?php esc_html_e( 'Occurrences', 'eventprime-event-calendar-management' ); ?></label>
                                </div>
                            </label>
                        </div>
                    </div>  
                </div>

                <!-- Other Options -->
                <div class="ep-meta-box-section ep-my-3" id="ep_recurrence_title_format_options">
                    <div class="ep-box-row">
                        <div class="ep-box-col-12 ep-meta-box-data">
                            <h4>
                                <?php esc_html_e('Event Titles for Repeating Events', 'eventprime-event-calendar-management'); ?>
                            </h4> 
                        </div>
                    </div>
                    <div class="ep-box-row ep-box-col-12">
                        <div class="form-check form-check-inline" id="ep_recurrence_add_slug_in_title">
                            <input type="checkbox" name="em_add_slug_in_event_title" id="ep_add_slug_in_event_title" value="1" <?php if( absint( $em_add_slug_in_event_title ) == 1 ) { echo 'checked="checked"'; }?>>
                            <label class="form-check-label" for="ep_add_slug_in_event_title"><?php esc_html_e( 'Auto Generate Titles', 'eventprime-event-calendar-management' ); ?></label>
                        </div>
                    </div>
                    <div class="ep-box-row ep-box-col-12 ep-mt-3 ep_recurrence_title_format_options" <?php if( empty( absint( $em_add_slug_in_event_title ) ) ) { echo 'style="display: none;"'; }?>>
                        <div class="form-check form-check-inline">
                            <label class="form-check-label" for="ep_event_slug_type_options"><?php esc_html_e( 'Generate Titles Using a', 'eventprime-event-calendar-management' ); ?></label>
                            <select name="em_event_slug_type_options" id="ep_event_slug_type_options">
                                <option value=""><?php esc_html_e('Select', 'eventprime-event-calendar-management' );?></option>
                                <option value="prefix" <?php if( $em_event_slug_type_options == 'prefix' ) { echo 'selected="selected"'; }?>><?php esc_html_e('Prefix Variable', 'eventprime-event-calendar-management' );?></option>
                                <option value="suffix" <?php if( $em_event_slug_type_options == 'suffix' ) { echo 'selected="selected"'; }?>><?php esc_html_e('Suffix Variable', 'eventprime-event-calendar-management' );?></option>
                            </select>
                        </div>
                    </div>
                    <div class="ep-box-row ep-box-col-12 ep-mt-3 ep_recurrence_title_modifier" <?php if( empty( $em_event_slug_type_options ) ) { echo 'style="display: none;"'; }?>>
                        <div class="form-check form-check-inline">
                            <label class="form-check-label" for="ep_event_slug_type_options"><?php esc_html_e( 'Select Variable', 'eventprime-event-calendar-management' ); ?></label>
                            <div class="ep-mt-3 ep-meta-box-data">
                                <label for="ep_recurring_events_slug_date_format" class="ep-di-flex  ep-items-center ep-form-label ep-event-booking-ends">
                                    <input type="radio" name="em_recurring_events_slug_format" class="ep-form-check-input" id="ep_recurring_events_slug_date_format" value="date" <?php if( $em_recurring_events_slug_format == 'date' ) { echo 'checked="checked"'; }?> >
                                    <?php esc_html_e('Date (Using WordPress Settings format).', 'eventprime-event-calendar-management'); ?><span class="ep-recurring-event-example-title-date ep-ml-2 ep-fw-bold"></span>
                                </label>
                            </div>
                            <div class="ep-mt-3 ep-meta-box-data">
                                <label for="ep_recurring_events_slug_number_format" class="ep-di-flex  ep-items-center ep-form-label  ep-event-booking-ends">
                                    <input type="radio" name="em_recurring_events_slug_format" class="ep-form-check-input" id="ep_recurring_events_slug_number_format" value="number" <?php if( $em_recurring_events_slug_format == 'number' ) { echo 'checked="checked"'; }?>>
                                    <?php esc_html_e('Number (Position of the event in the series.).', 'eventprime-event-calendar-management'); ?><span class="ep-recurring-event-example-title-number ep-ml-2 ep-fw-bold"></span>
                                </label>
                            </div>
                            <div class="ep-text-muted ep-text-small ep-mt-2 ep-px-3 ep-py-2">
                                <?php esc_html_e('Titles of the individual events in the series can also be manually edited later.', 'eventprime-event-calendar-management'); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><?php
} else{ ?>
    <div id="ep_event_recurrence_data" class="panel ep_event_options_panel">
        <div class="ep-box-wrap">
            <div class="ep-box-row ep-mt-3">
                <div class="ep-box-col-12">
                    <div class="ep-alert ep-alert-warning ep-mt-3 ep-py-2">
                        <?php esc_html_e('This is a recurring event. Any custom changes you make to this event will be overridden if you make changes to the main event later. Bookings of this event will remain unaffected.', 'eventprime-event-calendar-management'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div><?php
}?>

<div id="ep_event_recurring_update_children" class="ep-modal-view" style="display: none;">
    <div class="ep-modal-overlay ep-modal-overlay-fade-in close-popup" data-id="ep_event_recurring_update_children"></div>
    <div class="popup-content ep-modal-wrap ep-modal-sm ep-modal-out"> 
        <div class="ep-modal-body">
            <div class="ep-modal-titlebar ep-d-flex ep-items-center">
                <h3 class="ep-modal-title ep-px-3">
                    <?php esc_html_e( 'Update Event?', 'eventprime-event-calendar-management' ); ?>
                </h3>
                <a href="#" class="ep-modal-close close-popup" data-id="ep_event_recurring_update_children">&times;</a>
            </div> 
            <div class="ep-modal-content-wrap"> 
                <div class="ep-box-wrap">
                    <div class="ep-box-row ep-p-3 ep-box-w-100 ep-event-booking-field-manager">
                        <div class="ep-box-col-12 form-field">
                            <?php esc_html_e( 'You are making changes to an event which is part of a repeating events series. Do you wish to save changes:', 'eventprime-event-calendar-management' );?>
                        </div>
                        <div class="ep-box-col-12 form-field ep-mt-2">
                            <label for="ep_event_update_recurrence_action_no" class="ep-mr-3">
                                <input type="radio" class="form-control" name="ep_event_update_recurrence_action" id="ep_event_update_recurrence_action_no" value="no" checked>
                                <?php esc_html_e( 'Only for this occurrence', 'eventprime-event-calendar-management' );?>
                            </label>
                            <label for="ep_event_update_recurrence_action_yes">
                                <input type="radio" class="form-control" name="ep_event_update_recurrence_action" id="ep_event_update_recurrence_action_yes" value="yes">
                                <?php esc_html_e( 'For all recurrences in this series', 'eventprime-event-calendar-management' );?>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="ep-modal-footer ep-mt-3 ep-d-flex ep-items-end ep-content-right" id="ep_modal_buttonset">
                    <button type="button" class="button ep-mr-3 ep-modal-close close-popup" data-id="ep_event_recurring_update_children" id="ep_event_recurring_update_children_cancel" title="<?php echo esc_attr( 'Cancel', 'eventprime-event-calendar-management' ); ?>"><?php esc_html_e( 'Cancel', 'eventprime-event-calendar-management' ); ?></button>
                    <button type="button" class="button button-primary button-large" id="ep_event_recurring_update_children_confirm" title="<?php echo esc_attr( 'Submit', 'eventprime-event-calendar-management' ); ?>"><?php esc_html_e( 'Submit', 'eventprime-event-calendar-management' ); ?></button>
                </div>
            </div>
        </div>
    </div>
</div>