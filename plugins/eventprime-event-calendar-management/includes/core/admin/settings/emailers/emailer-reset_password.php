    <table class="form-table">
        <tbody>
            
            <tr valign="top">
                <th scope="row" class="titledesc">
                    <label for="registration_email_subject">
                        <?php esc_html_e( 'Subject', 'eventprime-event-calendar-management' );?><span class="ep-required">*</span>
                    </label>
                </th>
                <td class="forminp forminp-text">
                    <input name="reset_password_mail_subject" class="regular-text" id="reset_password_mail_subject" type="text" value="<?php echo isset($global_options->reset_password_mail_subject) ? $global_options->reset_password_mail_subject : __('Reset Your Password', 'eventprime-event-calendar-management');?>" required>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row" class="titledesc">
                    <label for="em_payment_test_mode">
                        <?php esc_html_e( 'Contents', 'eventprime-event-calendar-management' );?><span class="ep-required">*</span>
                    </label>
                </th>
                <td class="forminp forminp-text">
                    <?php 
                    $content = isset($global_options->reset_password_mail) ? $global_options->reset_password_mail : '';
                    wp_editor( $content, 'reset_password_mail' );?>
                </td>
            </tr>
        </tbody>
    </table>