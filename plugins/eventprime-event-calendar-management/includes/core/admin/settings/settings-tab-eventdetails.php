<?php $global_options = $options['global'];?>
<div class="ep-setting-tab-content">
    <h2><?php esc_html_e( 'Event Details View Settings', 'eventprime-event-calendar-management' );?></h2>
    <input type="hidden" name="em_setting_type" value="front_event_details_settings">
</div>
<table class="form-table">
    <tbody>
        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="show_qr_code_on_single_event">
                    <?php esc_html_e( 'Display QR Code', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <label class="ep-toggle-btn">
                    <input name="show_qr_code_on_single_event" id="show_qr_code_on_single_event" type="checkbox" value="1" <?php echo isset( $global_options->show_qr_code_on_single_event ) && $global_options->show_qr_code_on_single_event == 1 ? 'checked' : '';?>>
                    <span class="ep-toogle-slider round"></span>
                </label>
                <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e( 'If enabled, a QR code will be displayed on the right sidebar below the tickets section. Scanning this code takes user to the event page on mobile device.', 'eventprime-event-calendar-management' );?></div>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="hide_weather_tab">
                    <?php esc_html_e( 'Hide Weather Tab', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <label class="ep-toggle-btn">
                    <input name="hide_weather_tab" id="hide_weather_tab" type="checkbox" onclick="jQuery('#ep_weather_child').toggle(500);" value="1" <?php echo isset($global_options->hide_weather_tab ) && $global_options->hide_weather_tab == 1 ? 'checked' : '';?>>
                    <span class="ep-toogle-slider round"></span>
                </label>
                 <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e( 'Enabling it will hide weather tab in the venue section on the event page.', 'eventprime-event-calendar-management' );?></div>
            </td>
        </tr>
        <tr valign="top" id="ep_weather_child" style="<?php echo isset( $global_options->hide_weather_tab ) && $global_options->hide_weather_tab == 1 ? 'display:none' : ''; ?>" >
            <th scope="row" class="titledesc">
                <label for="weather_unit_fahrenheit">
                    <?php esc_html_e( 'Show Temperatures in Fahrenheit', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <label class="ep-toggle-btn">
                    <input name="weather_unit_fahrenheit" id="weather_unit_fahrenheit" type="checkbox" value="1" <?php echo isset( $global_options->weather_unit_fahrenheit ) && $global_options->weather_unit_fahrenheit == 1 ? 'checked' : '';?>>
                    <span class="ep-toogle-slider round"></span>
                </label>
                 <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e( 'Enable it to use Fahrenheit instead of Celsius for weather forecasts for the event venues.', 'eventprime-event-calendar-management' );?></div>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="hide_map_tab">
                    <?php esc_html_e( 'Hide Map Tab', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <label class="ep-toggle-btn">
                    <input name="hide_map_tab" id="hide_map_tab" type="checkbox" value="1" <?php echo isset( $global_options->hide_map_tab ) && $global_options->hide_map_tab == 1 ? 'checked' : '';?>>
                    <span class="ep-toogle-slider round"></span>
                </label>
                 <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e( 'Enabling it will hide map tab in the venue section on the event page.', 'eventprime-event-calendar-management' );?></div>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="hide_map_tab">
                    <?php esc_html_e( 'Hide Other Events Tab', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <label class="ep-toggle-btn">
                    <input name="hide_other_event_tab" id="hide_other_event_tab" type="checkbox" value="1" <?php echo isset( $global_options->hide_other_event_tab ) && $global_options->hide_other_event_tab == 1 ? 'checked' : '';?>>
                    <span class="ep-toogle-slider round"></span>
                </label>
                 <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e( 'Enabling it will hide Other Events tab in the venue section on the event page.', 'eventprime-event-calendar-management' );?></div>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="hide_age_group_section">
                    <?php esc_html_e( 'Hide Age Group Section', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <label class="ep-toggle-btn">
                    <input name="hide_age_group_section" id="hide_age_group_section" type="checkbox" value="1" <?php echo isset( $global_options->hide_age_group_section ) && $global_options->hide_age_group_section == 1 ? 'checked' : '';?>>
                    <span class="ep-toogle-slider round"></span>
                </label>
                 <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e( 'Enabling it will hide Age Group section in sidebar of the the event page. Event Age Groups are set through the parent event types.', 'eventprime-event-calendar-management' );?></div>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="hide_note_section">
                    <?php esc_html_e( 'Hide Attendee Note Section', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <label class="ep-toggle-btn">
                    <input name="hide_note_section" id="hide_note_section" type="checkbox" value="1" <?php echo isset( $global_options->hide_note_section ) && $global_options->hide_note_section == 1 ? 'checked' : '';?>>
                    <span class="ep-toogle-slider round"></span>
                </label>
                 <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e( 'Enabling it will hide custom note section in the sidebar of the event page.', 'eventprime-event-calendar-management' );?></div>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="hide_performers_section">
                    <?php esc_html_e( 'Hide Performers Section', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <label class="ep-toggle-btn">
                    <input name="hide_performers_section" id="hide_performers_section" type="checkbox" value="1" <?php echo isset( $global_options->hide_performers_section ) && $global_options->hide_performers_section == 1 ? 'checked' : '';?>>
                    <span class="ep-toogle-slider round"></span>
                </label>
                 <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e( 'Enabling it will hide performers section from the event detail page.', 'eventprime-event-calendar-management' );?></div>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="hide_organizers_section">
                    <?php esc_html_e( 'Hide Organizers Section', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <label class="ep-toggle-btn">
                    <input name="hide_organizers_section" id="hide_organizers_section" type="checkbox" value="1" <?php echo isset( $global_options->hide_organizers_section ) && $global_options->hide_organizers_section == 1 ? 'checked' : '';?>>
                    <span class="ep-toogle-slider round"></span>
                </label>
                 <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e( 'Enabling it will hide organizers section from the event detail page.', 'eventprime-event-calendar-management' );?></div>
            </td>
        </tr>
    </tbody>
</table>

<div class="ep-setting-tab-content">
    <h2><?php esc_html_e( 'Event Image Options', 'eventprime-event-calendar-management' );?></h2>
</div>
<table class="form-table">
    <tbody>
        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="event_detail_image_width">
                    <?php esc_html_e( 'Image Width', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">            
                <input type="number" min="0" name="event_detail_image_width" class="regular-text" id="event_detail_image_width" value="<?php echo esc_attr( $global_options->event_detail_image_width );?>">
                <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e( 'Select width of the event image in pixels.', 'eventprime-event-calendar-management' );?></div>
            </td>
        </tr>

        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="event_detail_image_height">
                    <?php esc_html_e( 'Image Height', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">            
                <select name="event_detail_image_height" id="event_detail_image_height" class="ep-form-control">
                    <option value="auto" <?php if ($global_options->event_detail_image_height == 'auto') { echo 'selected'; } ?>><?php esc_html_e( 'Auto', 'eventprime-event-calendar-management' );?></option>
                    <option value="custom" <?php if ($global_options->event_detail_image_height != 'auto') { echo 'selected'; } ?>><?php esc_html_e( 'Custom', 'eventprime-event-calendar-management' );?></option>
                </select>
                <div class="ep-mt-2" id="event_detail_image_height_custom_data" <?php if( $global_options->event_detail_image_height != 'custom' ) { echo 'style="display: none;"'; }?>>
                    <input type="number" min="0" name="event_detail_image_height_custom" class="regular-text" id="event_detail_image_height_custom" value="<?php echo esc_attr( $global_options->event_detail_image_height_custom );?>" placeholder="<?php esc_html_e( 'Enter Custom Height', 'eventprime-event-calendar-management' );?>">
                </div>
                <div class="ep-help-tip-info ep-my-2 ep-text-muted">
                    <?php esc_html_e( 'Select Auto for for automatically calculating image height based on original image\'s aspect ratio. Custom height will allow setting fixed height in pixels.', 'eventprime-event-calendar-management' );?>
                </div>
            </td>
        </tr>

        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="event_detail_image_align">
                    <?php esc_html_e( 'Alignment', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <select name="event_detail_image_align" id="event_detail_image_align" class="ep-form-control">
                    <option value=""><?php esc_html_e( 'Select Option', 'eventprime-event-calendar-management' );?></option>
                    <option value="start" <?php if( $global_options->event_detail_image_align == "start" ){ echo esc_attr( 'selected' ); } ?> ><?php esc_html_e( 'Left', 'eventprime-event-calendar-management' );?></option>
                    <option value="center" <?php if( $global_options->event_detail_image_align == "center" ){ echo esc_attr( 'selected' ); } ?> ><?php esc_html_e( 'Center', 'eventprime-event-calendar-management' );?></option>
                    <option value="end" <?php if( $global_options->event_detail_image_align == "end" ){ echo esc_attr( 'selected' ); } ?> ><?php esc_html_e( 'Right', 'eventprime-event-calendar-management' );?></option>
                </select>  
                <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e( 'Select image alignment.', 'eventprime-event-calendar-management' );?></div>
            </td>
        </tr>

        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="event_detail_image_auto_scroll">
                    <?php esc_html_e( 'Auto Slide', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <label class="ep-toggle-btn">
                    <input name="event_detail_image_auto_scroll" id="event_detail_image_auto_scroll" type="checkbox" value="1" <?php echo isset( $global_options->event_detail_image_auto_scroll ) && $global_options->event_detail_image_auto_scroll == 1 ? 'checked' : '';?>>
                    <span class="ep-toogle-slider round"></span>
                </label>
                 <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e( 'Enable to auto-slide images from the event gallery. Only works when you have uploaded images in the event gallery.', 'eventprime-event-calendar-management' );?></div>
            </td>
        </tr>

        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="event_detail_image_slider_duration">
                    <?php esc_html_e( 'Slide Duration', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text"> 
                <select name="event_detail_image_slider_duration" class="regular-text" id="event_detail_image_slider_duration"><?php
                    for( $i = 1; $i <= 10; $i++ ) {?>
                        <option value="<?php echo esc_attr( $i );?>" <?php if( $global_options->event_detail_image_slider_duration == $i ){ echo 'selected'; } ?> ><?php echo esc_html( $i );?></option><?php
                    }?>
                </select>
                <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e( 'Define number of seconds before the next image slides in. Only works when you have uploaded images in the event gallery.', 'eventprime-event-calendar-management' );?></div>
            </td>
        </tr>
    </tbody>
</table>

<div class="ep-setting-tab-content">
    <h2><?php esc_html_e( 'Event Results Options', 'eventprime-event-calendar-management' );?></h2>
</div>
<table class="form-table">
    <tbody>
        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="event_detail_result_heading">
                    <?php esc_html_e( 'Heading', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <input class="regular-text" type="text" id="event_detail_result_heading" name="event_detail_result_heading" value="<?php echo esc_attr( $global_options->event_detail_result_heading );?>">
                <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e( 'Heading of the results block on the event page.', 'eventprime-event-calendar-management' );?></div>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="event_detail_message_for_recap">
                    <?php esc_html_e( 'Text', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <textarea id="event_detail_message_for_recap" name="event_detail_message_for_recap" rows="4" cols="50"><?php echo esc_html( $global_options->event_detail_message_for_recap );?></textarea>     
                <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e( 'Text for the results block on the event page.', 'eventprime-event-calendar-management' );?></div>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="event_detail_result_button_label">
                    <?php esc_html_e( 'Button Label', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <input class="regular-text" type="text" id="event_detail_result_button_label" name="event_detail_result_button_label" value="<?php echo esc_attr( $global_options->event_detail_result_button_label );?>">
                <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e( 'Label for the button which takes the user to the results page for the event.', 'eventprime-event-calendar-management' );?></div>
            </td>
        </tr>
    </tbody>
</table>