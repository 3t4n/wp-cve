    <table class="form-table">
        <tbody>
            <tr valign="top">
                <th scope="row" class="titledesc">
                    <label for="send_booking_cancellation_email">
                        <?php esc_html_e( 'Enable/Disable', 'eventprime-event-calendar-management' );?>
                    </label>
                </th>
                <td class="forminp forminp-text">
                    <label class="ep-toggle-btn">
                        <input name="send_booking_cancellation_email" id="send_booking_cancellation_email" type="checkbox" value="1" <?php echo isset($global_options->send_booking_cancellation_email ) && $global_options->send_booking_cancellation_email == 1 ? 'checked' : '';?>>
                        <span class="ep-toogle-slider round"></span>
                    </label>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row" class="titledesc">
                    <label for="booking_cancelation_email_subject">
                        <?php esc_html_e( 'Subject', 'eventprime-event-calendar-management' );?><span class="ep-required">*</span>
                    </label>
                </th>
                <td class="forminp forminp-text">
                    <input name="booking_cancelation_email_subject" class="regular-text" id="booking_cancelation_email_subject" type="text" value="<?php echo isset($global_options->booking_cancelation_email_subject) ? $global_options->booking_cancelation_email_subject : __('Your booking now canceled!!!', 'eventprime-event-calendar-management');?>" required>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row" class="titledesc">
                    <label for="booking_cancelation_email">
                        <?php esc_html_e( 'Contents', 'eventprime-event-calendar-management' );?><span class="ep-required">*</span>
                        <span class="ep-help-tip" tooltip="User will receive this message on requesting cancellation for a booking."></span>
                    </label>
                </th>
                <td class="forminp forminp-text">
                    <?php 
                    $content = isset($global_options->booking_cancelation_email) ? $global_options->booking_cancelation_email : '';
                    wp_editor( $content, 'booking_cancelation_email' );?>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row" class="titledesc">
                    <label for="booking_cancelation_email_cc">
                        <?php esc_html_e( 'Email CC', 'eventprime-event-calendar-management' );?>
                    </label>
                </th>
                <td class="forminp forminp-text">
                    <input name="booking_cancelation_email_cc" class="regular-text" id="booking_cancelation_email_cc" type="text" value="<?php echo isset($global_options->booking_cancelation_email_cc) ? $global_options->booking_cancelation_email_cc : '';?>">
                </td>
            </tr>
        </tbody>
    </table>