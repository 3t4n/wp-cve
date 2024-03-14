    <table class="form-table">
        <tbody>
            <tr valign="top">
                <th scope="row" class="titledesc">
                    <label for="send_event_approved_email">
                        <?php esc_html_e( 'Enable/Disable', 'eventprime-event-calendar-management' );?>
                    </label>
                </th>
                <td class="forminp forminp-text">
                    <label class="ep-toggle-btn">
                        <input name="send_admin_booking_confirm_email" id="send_admin_booking_confirm_email" type="checkbox" value="1" <?php echo isset($global_options->send_admin_booking_confirm_email ) && $global_options->send_admin_booking_confirm_email == 1 ? 'checked' : '';?>>
                        <span class="ep-toogle-slider round"></span>
                    </label>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row" class="titledesc">
                    <label for="admin_booking_confirmed_email_subject">
                        <?php esc_html_e( 'Subject', 'eventprime-event-calendar-management' );?><span class="ep-required">*</span>
                    </label>
                </th>
                <td class="forminp forminp-text">
                    <input name="admin_booking_confirmed_email_subject" class="regular-text" id="admin_booking_confirmed_email_subject" type="text" value="<?php echo isset($global_options->admin_booking_confirmed_email_subject) ? $global_options->admin_booking_confirmed_email_subject : __('New booking...', 'eventprime-event-calendar-management');?>" required>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row" class="titledesc">
                    <label for="admin_booking_confirmed_email">
                        <?php esc_html_e( 'Contents', 'eventprime-event-calendar-management' );?><span class="ep-required">*</span>
                    </label>
                </th>
                <td class="forminp forminp-text">
                    <?php 
                    $content = isset($global_options->admin_booking_confirmed_email) ? $global_options->admin_booking_confirmed_email : '';
                    wp_editor( $content, 'admin_booking_confirmed_email' );?>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row" class="titledesc">
                    <label for="admin_booking_confirmed_email_cc">
                        <?php esc_html_e( 'Email CC', 'eventprime-event-calendar-management' );?>
                    </label>
                </th>
                <td class="forminp forminp-text">
                    <input name="admin_booking_confirmed_email_cc" class="regular-text" id="admin_booking_confirmed_email_cc" type="text" value="<?php echo isset($global_options->admin_booking_confirmed_email_cc) ? $global_options->admin_booking_confirmed_email_cc : '';?>">
                </td>
            </tr>
            <tr valign="top">
                <th scope="row" class="titledesc">
                    <label for="admin_booking_confirm_email_attendees)">
                        <?php esc_html_e( 'Attach Attendee\'s', 'eventprime-event-calendar-management' );?>
                    </label>
                </th>
                <td class="forminp forminp-text">
                    <label class="ep-toggle-btn">
                        <input name="admin_booking_confirm_email_attendees" id="admin_booking_confirm_email_attendees" type="checkbox" value="1" <?php echo isset($global_options->admin_booking_confirm_email_attendees ) && $global_options->admin_booking_confirm_email_attendees == 1 ? 'checked' : '';?>>
                        <span class="ep-toogle-slider round"></span>
                    </label>
                </td>
            </tr>
        </tbody>
    </table>