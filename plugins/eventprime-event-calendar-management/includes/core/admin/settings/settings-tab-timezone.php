<?php $global_options = $options['global'];?>
<div class="ep-setting-tab-content">
    <h2><?php esc_html_e( 'Timezone', 'eventprime-event-calendar-management' );?></h2>
    <input type="hidden" name="em_setting_type" value="timezone_settings">
</div>
<table class="form-table">
    <tbody>
        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="enable_event_time_to_user_timezone">
                    <?php esc_html_e( 'Allow Users to Select Timezone', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <label class="ep-toggle-btn">
                    <input name="enable_event_time_to_user_timezone" id="enable_event_time_to_user_timezone" type="checkbox" value="1" <?php echo isset($global_options->enable_event_time_to_user_timezone ) && $global_options->enable_event_time_to_user_timezone == 1 ? 'checked' : '';?>>
                    <span class="ep-toogle-slider round"></span>
                </label>
                <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e( 'If enabled, users will see a dropdown above event lists to select their timezone. Once a timezone is selected, all event times will be displayed based on the selected timezone. Please note, this will save a cookie on user\'s device to remember his or her timezone while browsing other event sections.', 'eventprime-event-calendar-management' );?></div>
            </td>
        </tr>
        <tr valign="top" id="show_timezone_message_on_event_page_wrap" <?php if( empty( $global_options->enable_event_time_to_user_timezone ) ) { echo esc_attr( 'style=display:none;' ); }?>>
            <th scope="row" class="titledesc">
                <label for="show_timezone_message_on_event_page">
                    <?php esc_html_e( 'Display Timezone Message', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <label class="ep-toggle-btn">
                    <input name="show_timezone_message_on_event_page" id="show_timezone_message_on_event_page" type="checkbox" value="1" <?php echo isset($global_options->show_timezone_message_on_event_page ) && $global_options->show_timezone_message_on_event_page == 1 ? 'checked' : '';?>>
                    <span class="ep-toogle-slider round"></span>
                </label>
                <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e( 'If enabled, you can set a customizable message informing users that event times reflect their chosen timezone.', 'eventprime-event-calendar-management' );?></div>
            </td>
        </tr>
        <tr valign="top" id="timezone_related_message_wrap" <?php if( empty( $global_options->enable_event_time_to_user_timezone ) || empty( $global_options->show_timezone_message_on_event_page ) ) { echo esc_attr( 'style=display:none;' ); }?>>
            <th scope="row" class="titledesc">
                <label for="timezone_related_message">
                    <?php esc_html_e( 'Message Contents', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <label>
                    <textarea class="regular-text" id="timezone_related_message" name="timezone_related_message"><?php echo esc_html( $sub_options['timezone_related_message'] );?></textarea>
                </label>
                <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e( 'Contents of the message to be displayed when user selects a timezone different from the website\'s default timezone. Default message: Displaying event times based on your selected timezone - {{$timezone}}', 'eventprime-event-calendar-management' );?></div>
            </td>
        </tr>
    </tbody>
</table>