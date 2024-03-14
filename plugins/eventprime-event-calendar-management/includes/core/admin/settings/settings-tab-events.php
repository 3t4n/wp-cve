<?php $global_options = $options['global'];?>
<div class="ep-setting-tab-content">
    <h2><?php esc_html_e( 'General View Settings', 'eventprime-event-calendar-management' );?></h2>
    <input type="hidden" name="em_setting_type" value="front_events_settings">
</div>
<table class="form-table">
    <tbody>
        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="front_switch_view_option">
                    <?php esc_html_e( 'Available Views', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <label>
                    <select name="front_switch_view_option[]" id="front_switch_view_option" multiple="multiple" class="ep-form-control">
                        <?php 
                        foreach( $sub_options['default_cal_view'] as $key => $value ){?>
                            <option value="<?php echo esc_attr( $key );?>" <?php if( ! empty( $global_options->front_switch_view_option ) && in_array( $key, $global_options->front_switch_view_option ) ) { echo esc_attr( 'selected' ); } ?>>
                                <?php echo esc_html__( $value );?>
                            </option><?php
                        }?>
                    </select>
                </label>
                <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e( 'EventPrime offers multiple views for event listings to your website users. Users can switch to any available view using buttons at the top of the listings. Using this option you can control which views are available to them.', 'eventprime-event-calendar-management' );?></div>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="default_cal_view">
                    <?php esc_html_e( 'Default View', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <label>
                    <select name="default_cal_view" id="default_cal_view" class="ep-form-control">
                        <?php 
                        foreach( $sub_options['default_cal_view'] as $key => $value ){
                            if( $key == $global_options->default_cal_view ){?>
                                <option value="<?php echo esc_attr( $key );?>" selected><?php echo esc_html( $value );?></option><?php
                            }else{?>
                                <option value="<?php echo esc_attr( $key );?>"><?php echo esc_html( $value );?></option><?php
                            }
                        }?>
                    </select>
                </label>
                <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e( 'The selected view of the events list when the page first loads.', 'eventprime-event-calendar-management' );?></div>
            </td>
        </tr>

        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="hide_past_events">
                    <?php esc_html_e( 'Hide Past Events from Event Lists', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <label class="ep-toggle-btn">
                    <input name="hide_past_events" id="hide_past_events" type="checkbox" value="1" <?php echo isset($global_options->hide_past_events ) && $global_options->hide_past_events == 1 ? 'checked' : '';?>>
                    <span class="ep-toogle-slider round"></span>
                </label>
                 <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e( 'Enabling this will hide past events from different type of event lists everywhere. This is calculated based on end date of the event.', 'eventprime-event-calendar-management' );?></div>
            </td>
        </tr>
        
        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="open_detail_page_in_new_tab">
                    <?php esc_html_e( 'Open Events in a New Tab', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <label class="ep-toggle-btn">
                    <input name="open_detail_page_in_new_tab" id="open_detail_page_in_new_tab" type="checkbox" value="1" <?php echo isset($global_options->open_detail_page_in_new_tab ) && $global_options->open_detail_page_in_new_tab == 1 ? 'checked' : '';?>>
                    <span class="ep-toogle-slider round"></span>
                </label>
                <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e( 'If enabled, when a user clicks on an event in the events listing, the event page will open in a new tab instead of the same tab.', 'eventprime-event-calendar-management' );?></div>
            </td>
        </tr>

        <tr valign="top">
                <th scope="row" class="titledesc">
                    <label for="disable_filter_options">
                        <?php esc_html_e( 'Hide Event Filtering', 'eventprime-event-calendar-management' );?>
                    </label>
                </th>
                <td class="forminp forminp-text">
                    <label class="ep-toggle-btn">
                        <input name="disable_filter_options" id="disable_filter_options" type="checkbox" value="1" <?php echo isset($global_options->disable_filter_options ) && $global_options->disable_filter_options == 1 ? 'checked' : '';?>>
                        <span class="ep-toogle-slider round"></span>
                    </label>
                    <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e( 'EventPrime offers various filters for the users to narrow down event listings and search results. If enabled, filtering will no longer be available to the users.', 'eventprime-event-calendar-management' );?></div>
                </td>
        </tr>
        <tr valign="top">
                <th scope="row" class="titledesc">
                    <label for="hide_old_bookings">
                        <?php esc_html_e( 'Hide Bookings for Past Events', 'eventprime-event-calendar-management' );?>
                    </label>
                </th>
                <td class="forminp forminp-text">
                    <label class="ep-toggle-btn">
                        <input name="hide_old_bookings" id="hide_old_bookings" type="checkbox" value="1" <?php echo isset($global_options->hide_old_bookings ) && $global_options->hide_old_bookings == 1 ? 'checked' : '';?>>
                        <span class="ep-toogle-slider round"></span>
                    </label>
                    <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e( 'Hide Bookings for Past Events.', 'eventprime-event-calendar-management' );?></div>
                </td>
        </tr>
        
        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="shortcode_hide_upcoming_events">
                    <?php esc_html_e( 'Hide Upcoming Events', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <label class="ep-toggle-btn">
                    <input name="shortcode_hide_upcoming_events" id="shortcode_hide_upcoming_events" type="checkbox" value="1" <?php echo isset($global_options->shortcode_hide_upcoming_events ) && $global_options->shortcode_hide_upcoming_events == 1 ? 'checked' : '';?>>
                    <span class="ep-toogle-slider round"></span>
                </label>
                <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e( 'If enabled, all upcoming events will be hidden from the calendar.', 'eventprime-event-calendar-management' );?></div>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="redirect_third_party">
                    <?php esc_html_e( 'Redirect to Third Party URL', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <label class="ep-toggle-btn">
                    <input name="redirect_third_party" id="redirect_third_party" type="checkbox" value="1" <?php echo isset($global_options->redirect_third_party ) && $global_options->redirect_third_party == 1 ? 'checked' : '';?>>
                    <span class="ep-toogle-slider round"></span>
                </label>
                <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e( 'If enabled, you will be redirected to third-party url when you click on events on frontend.', 'eventprime-event-calendar-management' );?></div>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="hide_event_custom_link">
                    <?php esc_html_e( 'Hide Custom Link', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <label class="ep-toggle-btn">
                    <input name="hide_event_custom_link" id="hide_event_custom_link" type="checkbox" value="1" <?php echo isset($global_options->hide_event_custom_link ) && $global_options->hide_event_custom_link == 1 ? 'checked' : '';?>>
                    <span class="ep-toogle-slider round"></span>
                </label>
                <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e( 'If enabled, third-party booking links will not be visible to logged-out users.', 'eventprime-event-calendar-management' );?></div>
            </td>
        </tr>
    </tbody>
</table>
<h2><?php esc_html_e( 'Calendar View Settings', 'eventprime-event-calendar-management' );?></h2>
<h3><?php esc_html_e( 'For Month, Week and Day views', 'eventprime-event-calendar-management' );?></h3>
<table class="form-table">
    <tbody>
        <!--<tr valign="top">
            <th scope="row" class="titledesc">
                <label for="enable_default_calendar_date">
                    <?php esc_html_e( 'Enable Default Calendar Date', 'eventprime-event-calendar-management' );?>
                    <span class="ep-help-tip" tooltip="<?php esc_html_e( 'Enable or disable default date for frontend calendar views.', 'eventprime-event-calendar-management' );?>"></span>
                </label>
            </th>
            <td class="forminp forminp-text">
                <label class="ep-toggle-btn">
                    <input name="enable_default_calendar_date" id="enable_default_calendar_date" onclick="hide_show_default_date_setting(this,'ep_enable_default_calendar_date_child')" type="checkbox" value="1" <?php echo isset($global_options->enable_default_calendar_date ) && $global_options->enable_default_calendar_date == 1 ? 'checked' : '';?>>
                    <span class="ep-toogle-slider round"></span>
                </label>
            </td>
        </tr>
        <tr valign="top" id="ep_enable_default_calendar_date_child" style="<?php echo isset($global_options->enable_default_calendar_date ) && $global_options->enable_default_calendar_date == 1 ? '' : 'display:none;';?>">
            <th scope="row" class="titledesc">
                <label for="default_calendar_date">
                    <?php esc_html_e( 'Select Calendar Date', 'eventprime-event-calendar-management' );?>
                    <span class="ep-help-tip" tooltip="<?php esc_html_e( 'The calendar views on the frontend will use this date as the default date for display.', 'eventprime-event-calendar-management' );?>"></span>
                </label>
            </th>
            <td class="forminp forminp-text">
                <input name="default_calendar_date" id="default_calendar_date" type="text" class="regular-text" value="<?php echo esc_html( $global_options->default_calendar_date );?>" <?php echo isset($global_options->default_calendar_date ) && $global_options->default_calendar_date == 1 ? 'required' : '';?>>
            </td>
        </tr>-->
        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="calendar_title_format">
                    <?php esc_html_e( 'Title Date Format', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <select name="calendar_title_format" id="calendar_title_format" class="ep-form-control">
                    <?php 
                        foreach($sub_options['calendar_title_format'] as $key => $value){
                            if($key == $global_options->calendar_title_format){?>
                                <option value="<?php echo esc_attr($key);?>" selected><?php echo esc_html__($value);?></option>
                            <?php
                            }else{?>
                                <option value="<?php echo esc_attr($key);?>"><?php echo esc_html__($value);?></option>
                            <?php
                            }
                        }?>
                </select>
                <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e( 'Calendar views prominently display date on top. You can set the format of this date using this option.', 'eventprime-event-calendar-management' );?></div>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="calendar_column_header_format">
                    <?php esc_html_e( 'Column Heading Format', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <select name="calendar_column_header_format" id="calendar_column_header_format"class="ep-form-control">
                    <option value=""><?php esc_html_e( 'Select Option', 'eventprime-event-calendar-management' );?></option>
                    <?php 
                    foreach($sub_options['calendar_header_format'] as $key => $value){
                        if($key == $global_options->calendar_column_header_format){?>
                            <option value="<?php echo esc_attr($key);?>" selected><?php echo esc_html__($value);?></option><?php
                        }else{?>
                            <option value="<?php echo esc_attr($key);?>"><?php echo esc_html__($value);?></option><?php
                        }
                    }?>
                </select>
                <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e( 'Week and day columns in calendars display both day of the week and the date. You can set the format of this heading using this option.', 'eventprime-event-calendar-management' );?></div>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="hide_calendar_rows">
                    <?php esc_html_e( 'Hide Previous And Next Month Rows From Calendar', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <label class="ep-toggle-btn">
                    <input name="hide_calendar_rows" id="hide_calendar_rows" type="checkbox" value="1" <?php echo isset($global_options->hide_calendar_rows ) && $global_options->hide_calendar_rows == 1 ? 'checked' : '';?>>
                    <span class="ep-toogle-slider round"></span>
                </label>
                <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e( 'Hide Previous and Next Month Rows From Calendar.', 'eventprime-event-calendar-management' );?></div>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="show_max_event_on_calendar_date">
                    <?php esc_html_e( 'Maximum Events per Day', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <input min="1" name="show_max_event_on_calendar_date" id="show_max_event_on_calendar_date" class="regular-text" type="number" value="<?php echo ( isset( $global_options->show_max_event_on_calendar_date ) && ! empty( $global_options->show_max_event_on_calendar_date ) ?  esc_html( $global_options->show_max_event_on_calendar_date ) : 2 );?>">
                <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e( 'Limits the number of events to be displayed on a single day. If the number of events exceed this value, a \'More\' button will appear, clicking on which the user can see the complete list of events in a popover. This is especially helpful where a large number of events on a single day can cramp the view. This option affects all calendar views.', 'eventprime-event-calendar-management' );?></div>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="hide_time_on_front_calendar">
                    <?php esc_html_e( 'Hide Event Time From Calendar View', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <label class="ep-toggle-btn">
                    <input name="hide_time_on_front_calendar" id="hide_time_on_front_calendar" type="checkbox" value="1" <?php echo isset($global_options->hide_time_on_front_calendar ) && $global_options->hide_time_on_front_calendar == 1 ? 'checked' : '';?>>
                    <span class="ep-toogle-slider round"></span>
                </label>
                <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e( 'Enable to hide event time from the front end calendar view.', 'eventprime-event-calendar-management' );?></div>
            </td>
        </tr>
    </tbody>
</table>
<div class="ep-setting-tab-content">
    <h2><?php esc_html_e( 'Non-Calendar View Settings', 'eventprime-event-calendar-management' );?></h2>
    <h2><?php esc_html_e( 'For Grids, Rows and Slider views', 'eventprime-event-calendar-management' );?></h2>
</div>
<table class="form-table">
    <tbody>
        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="show_no_of_events_card">
                    <?php esc_html_e( 'No. of Events to Fetch', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <select name="show_no_of_events_card" id="show_no_of_events_card" class="ep-form-control">
                    <?php 
                    foreach($sub_options['events_per_page'] as $per_page){
                        if($per_page == $global_options->show_no_of_events_card){?>
                            <option value="<?php echo esc_attr( $per_page );?>" selected><?php echo esc_html__($per_page);?></option><?php
                        }else{?>
                            <option value="<?php echo esc_attr( $per_page );?>"><?php echo esc_html__($per_page);?></option><?php
                        }
                    }?>
                </select>
                <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e( 'The number of events to fetch when page loads for the first time, and each time on clicking the \'Load More\' button. The \'custom\' option is applicable only when the default view is set to square grid, staggered grid, or rows.', 'eventprime-event-calendar-management' );?></div>
            </td>
        </tr>
        <tr valign="top" class="ep_enable_card_view_custom_value_child" style="<?php echo ( ( $global_options->default_cal_view == 'card' || $global_options->default_cal_view == 'square_grid' || $global_options->default_cal_view == 'masonry' || $global_options->default_cal_view == 'staggered_grid' || $global_options->default_cal_view == 'list' || $global_options->default_cal_view == 'rows' ) &&  $global_options->show_no_of_events_card == 'custom' ) ? '' : 'display:none;';?>">
            <th scope="row" class="titledesc">
                <label for="card_view_custom_value">
                    <?php esc_html_e( 'Enter Value', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <input min="1" name="card_view_custom_value" id="card_view_custom_value" class="regular-text" type="number" value="<?php if( isset( $global_options->card_view_custom_value ) && ! empty( $global_options->card_view_custom_value ) ) { echo $global_options->card_view_custom_value; } ;?>">
            </td>
        </tr>
        
        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="event_booking_status_option">
                    <?php esc_html_e( 'Booking Status Style', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <select name="event_booking_status_option" id="event_booking_status_option"class="ep-form-control">
                    <option value=""><?php esc_html_e( 'Select Option', 'eventprime-event-calendar-management' );?></option>
                    <option value="bargraph" <?php if( $global_options->event_booking_status_option == 'bargraph' ) { echo esc_attr( 'selected' );}?>><?php esc_html_e( 'Progress Bar', 'eventprime-event-calendar-management' );?></option>
                    <option value="ticket_left" <?php if( $global_options->event_booking_status_option == 'ticket_left' ) { echo esc_attr( 'selected' );}?>><?php esc_html_e( 'Plain Text', 'eventprime-event-calendar-management' );?></option>
                </select>
                <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e( 'EventPrime displays current booking status, along with other event details, for each event on the event listings. You can display it as a progress bar or plain text.', 'eventprime-event-calendar-management' );?></div>
            </td>
        </tr>
    </tbody>
</table>
<div class="ep-setting-tab-content">
    <h2><?php esc_html_e( 'No. of Columns in Grid views', 'eventprime-event-calendar-management' );?></h2>
</div>
<table class="form-table">
    <tbody>
        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="show_qr_code_on_single_event">
                    <?php esc_html_e( 'Columns', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <select name="events_no_of_columns" id="events_no_of_columns" class="ep-form-control">
                    <option value=""><?php esc_html_e( 'Select Option', 'eventprime-event-calendar-management' );?></option>
                    <option value="1" <?php if( $global_options->events_no_of_columns == 1 ){ echo 'selected'; } ?> >1</option>
                    <option value="2" <?php if( $global_options->events_no_of_columns == 2 ){ echo 'selected'; } ?> >2</option>
                    <option value="3" <?php if( $global_options->events_no_of_columns == 3 ){ echo 'selected'; } ?> >3</option>
                    <option value="4" <?php if( $global_options->events_no_of_columns == 4 ){ echo 'selected'; } ?>>4</option>
                </select>     
                <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e( 'Define the number of columns in grid views. If your theme offers narrow content area, please choose 1 or 2, or the view may appear too cramped.', 'eventprime-event-calendar-management' );?></div>
            </td>
        </tr>
    </tbody>
</table>
<div class="ep-setting-tab-content">
    <h2><?php esc_html_e( 'Image Visibility Options in Square Grid View', 'eventprime-event-calendar-management' );?></h2>
</div>
<table class="form-table">
    <tbody>
        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="show_notice_on_event_detail_page">
                    <?php esc_html_e( "Image Visibility Options", 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <label>
                    <select name="events_image_visibility_options" id="events_image_visibility_options" class="ep-form-control">
                        <?php 
                        foreach( $sub_options['image_visibility_options'] as $key => $value ){?>
                            <option value="<?php echo esc_attr( $key );?>" <?php if( ! empty( $global_options->events_image_visibility_options ) && $key == $global_options->events_image_visibility_options ) { echo esc_attr( 'selected' ); } ?>>
                                <?php echo esc_html__( $value );?>
                            </option><?php
                        }?>
                    </select>
                </label>
                <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e( 'Select image visibility options in frontend views.', 'eventprime-event-calendar-management' );?></div>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="image_height_in_frontend_views">
                    <?php esc_html_e( "Image Height ( In Pixels )", 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <label>
                <input type="number" min="0" name="events_image_height" class="regular-text" id="events_image_height" value="<?php echo esc_attr( $global_options->events_image_height );?>">
                </label>
                <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e( 'Enter image height for frontend views.', 'eventprime-event-calendar-management' );?></div>
            </td>
        </tr>
    </tbody>
</table>

<!-- Trending Event Type -->
<div class="ep-setting-tab-content" style="display: none;">
    <h2><?php esc_html_e( 'Trending Event-Types', 'eventprime-event-calendar-management' );?></h2>
</div>
<table class="form-table" style="display: none;">
    <tbody>
        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="show_trending_event_types">
                    <?php esc_html_e( 'Show trending event-types', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <label class="ep-toggle-btn">
                    <input name="show_trending_event_types" id="ep_show_trending_event_types" type="checkbox" value="1" <?php echo isset( $global_options->show_trending_event_types ) && $global_options->show_trending_event_types == 1 ? 'checked' : '';?>>
                    <span class="ep-toogle-slider round"></span>
                </label>
                <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e( 'Enable to display trending event-types in the frontend.', 'eventprime-event-calendar-management' );?></div>
            </td>
        </tr>
        <tr class="ep-settings-trending-event-type-option" valign="top" style="<?php if( empty( $global_options->show_trending_event_types ) ) { echo 'display: none;'; }?>">
            <th scope="row" class="titledesc">
                <label for="no_of_event_types_displayed">
                    <?php esc_html_e( 'No. of trending event-types to be displayed', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <input type="number" min="0" name="no_of_event_types_displayed" class="regular-text" id="no_of_event_types_displayed" value="<?php echo (isset( $global_options->no_of_event_types_displayed ) && ! empty( $global_options->no_of_event_types_displayed )) ? $global_options->no_of_event_types_displayed : '5' ; ?>">
                <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e( 'The number of trending event-types to be displayed on the frontend.', 'eventprime-event-calendar-management' );?></div>
            </td>
        </tr>
        <tr class="ep-settings-trending-event-type-option" valign="top" style="<?php if( empty( $global_options->show_trending_event_types ) ) { echo 'display: none;'; }?>">
            <th scope="row" class="titledesc">
                <label for="show_events_per_event_type">
                    <?php esc_html_e( 'Show no. of events per event-type', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <label class="ep-toggle-btn">
                    <input name="show_events_per_event_type" id="show_events_per_event_type" type="checkbox" value="1" <?php echo isset($global_options->show_events_per_event_type ) && $global_options->show_events_per_event_type == 1 ? 'checked' : '';?>>
                    <span class="ep-toogle-slider round"></span>
                </label>
                <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e( 'Enable to display the number of events registered under a specific trending event-type.', 'eventprime-event-calendar-management' );?></div>
            </td>
        </tr>
        <tr class="ep-settings-trending-event-type-option" valign="top" style="<?php if( empty( $global_options->show_trending_event_types ) ) { echo 'display: none;'; }?>">
            <th scope="row" class="titledesc">
                <label for="sort_by_events_or_bookings">
                    <?php esc_html_e( 'Sort the list by', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <select name="sort_by_events_or_bookings" id="sort_by_events_or_bookings" class="ep-form-control">
                    <option value=""><?php esc_html_e( 'Select Option', 'eventprime-event-calendar-management' );?></option>
                    <option value="sort_by_events" <?php if( $global_options->sort_by_events_or_bookings == 'sort_by_events' ) { echo esc_attr( 'selected' );}?>><?php esc_html_e( 'Total no. Events', 'eventprime-event-calendar-management' );?></option>
                    <option value="sort_by_bookings" <?php if( $global_options->sort_by_events_or_bookings == 'sort_by_bookings' ) { echo esc_attr( 'selected' );}?>><?php esc_html_e( 'Total no. Bookings', 'eventprime-event-calendar-management' );?></option>
                </select>     
                <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e( '', 'eventprime-event-calendar-management' );?></div>     <!-- Add a description here. -->
            </td>
        </tr>
    </tbody>
</table>