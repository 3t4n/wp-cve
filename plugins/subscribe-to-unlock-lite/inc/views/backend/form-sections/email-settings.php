<div class="stul-settings-each-section stul-display-none" data-tab="email">

    <div class="stul-field-wrap">
        <label><?php esc_html_e('Email Subject', 'subscribe-to-unlock-lite'); ?></label>
        <div class="stul-field">
            <input type="text" name="form_details[email][subject]" value="<?php echo (!empty($form_details['email']['subject'])) ? esc_attr($form_details['email']['subject']) : ''; ?>"/>
            <p class="description"><?php esc_html_e('Please enter the from email which will be used to send the email to subscribers. Please enter any email which won\'t resembele the real person\'s email such as noreply@yoursiteurl.com ', 'subscribe-to-unlock-lite'); ?></p>
        </div>
    </div>
    <div class="stul-field-wrap">
        <label><?php esc_html_e('From Email', 'subscribe-to-unlock-lite'); ?></label>
        <div class="stul-field">
            <input type="text" name="form_details[email][from_email]" value="<?php echo (!empty($form_details['email']['from_email'])) ? esc_attr($form_details['email']['from_email']) : ''; ?>"/>
            <p class="description"><?php esc_html_e('Please enter the from email which will be used to send the email to subscribers. Please enter any email which won\'t resembele the real person\'s email such as noreply@yoursiteurl.com ', 'subscribe-to-unlock-lite'); ?></p>
        </div>
    </div>
    <div class="stul-field-wrap">
        <label><?php esc_html_e('From Name', 'subscribe-to-unlock-lite'); ?></label>
        <div class="stul-field">
            <input type="text" name="form_details[email][from_name]" value="<?php echo (!empty($form_details['email']['from_name'])) ? esc_attr($form_details['email']['from_name']) : ''; ?>"/>
            <p class="description"><?php esc_html_e('Please enter the from name which will be used to send the email to subscribers. Please enter any email which won\'t resembele the real person\'s email such as No Reply ', 'subscribe-to-unlock-lite'); ?></p>
        </div>
    </div>
    <div class="stul-field-wrap">
        <label><?php esc_html_e('Email Message', 'subscribe-to-unlock-lite'); ?></label>
        <div class="stul-field">
            <textarea name="form_details[email][email_message]"><?php echo (!empty($form_details['email']['email_message'])) ? $this->output_converting_br($form_details['email']['email_message']) : $this->get_default_email_message(); ?></textarea>
            <p class="description"><?php esc_html_e('Please use #unlock_link which will be replaced by unlock link in the email.', 'subscribe-to-unlock-lite'); ?></p>
            <p class="description"><?php esc_html_e('Please use #unlock_code which will be replaced by unlock code in the email.', 'subscribe-to-unlock-lite'); ?></p>
        </div>
    </div>
</div>