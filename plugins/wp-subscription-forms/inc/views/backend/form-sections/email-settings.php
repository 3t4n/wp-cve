<div class="wpsf-settings-each-section" data-tab="email" style="display:none;">
    <div class="wpsf-field-wrap">
        <label><?php esc_html_e('From Email', 'wp-subscription-forms'); ?></label>
        <div class="wpsf-field">
            <input type="text" name="form_details[email][from_email]" value="<?php echo (!empty($form_details['email']['from_email'])) ? esc_attr($form_details['email']['from_email']) : ''; ?>"/>
            <p class="description"><?php esc_html_e('Please enter the from email which will be used to send the email to subscribers. Please enter any email which won\'t resembele the real person\'s email such as noreply@yoursiteurl.com ', 'wp-subscription-forms'); ?></p>
        </div>
    </div>
    <div class="wpsf-field-wrap">
        <label><?php esc_html_e('From Name', 'wp-subscription-forms'); ?></label>
        <div class="wpsf-field">
            <input type="text" name="form_details[email][from_name]" value="<?php echo (!empty($form_details['email']['from_name'])) ? esc_attr($form_details['email']['from_name']) : ''; ?>"/>
            <p class="description"><?php esc_html_e('Please enter the from name which will be used to send the email to subscribers. Please enter any email which won\'t resembele the real person\'s email such as No Reply ', 'wp-subscription-forms'); ?></p>
        </div>
    </div>
    <div class="wpsf-double-optin-ref" <?php echo (empty($form_details['general']['double_optin'])) ? 'style="display:none;"' : ''; ?>>
        <div class="wpsf-field-wrap">
            <label><?php esc_html_e('Confirmation Email Subject', 'wp-subscription-forms'); ?></label>
            <div class="wpsf-field">
                <input type="text" name="form_details[email][confirmation_email_subject]" value="<?php echo (!empty($form_details['email']['confirmation_email_subject'])) ? esc_attr($form_details['email']['confirmation_email_subject']) : ''; ?>"/>
                <p class="description"><?php esc_html_e('Please enter the from email which will be used to send the email to subscribers. Please enter any email which won\'t resembele the real person\'s email such as noreply@yoursiteurl.com ', 'wp-subscription-forms'); ?></p>
            </div>
        </div>
        <div class="wpsf-field-wrap">
            <label><?php esc_html_e('Confirmation Email Message', 'wp-subscription-forms'); ?></label>
            <div class="wpsf-field">
                <textarea name="form_details[email][confirmation_email_message]"><?php echo (!empty($form_details['email']['confirmation_email_message'])) ? $this->output_converting_br($form_details['email']['confirmation_email_message']) : $this->get_default_confirmation_email_message(); ?></textarea>
                <p class="description"><?php esc_html_e('Please use #confirmation_link which will be replaced with the confirmation link in the email.', 'wp-subscription-forms'); ?></p>
            </div>
        </div>
    </div>

</div>