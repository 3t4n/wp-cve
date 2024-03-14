<div class="wpsf-settings-each-section" data-tab="general">
    <div class="wpsf-field-wrap">
        <label><?php esc_html_e('Form Status', 'wp-subscription-forms'); ?></label>
        <div class="wpsf-field">
            <input type="checkbox" name="form_status" value="1" <?php echo (!empty($form_row->form_status)) ? 'checked="checked"' : ''; ?>/>
        </div>
    </div>
    <div class="wpsf-field-wrap">
        <label><?php esc_html_e('Form Title', 'wp-subscription-forms'); ?></label>
        <div class="wpsf-field">
            <input type="text" name="form_title" value="<?php echo (!empty($form_row->form_title)) ? esc_attr($form_row->form_title) : ''; ?>"/>
        </div>
    </div>

    <div class="wpsf-field-wrap">
        <label><?php esc_html_e('Form Alias', 'wp-subscription-forms'); ?></label>
        <div class="wpsf-field">
            <input type="text" name="form_alias" class="wpsf-alias-field" value="<?php echo (!empty($form_row->form_alias)) ? esc_attr($form_row->form_alias) : ''; ?>" <?php echo (!empty($_GET['form_id'])) ? 'readonly="readonly"' : ''; ?>/>
            <?php if (!empty($_GET['form_id'])) {
                ?>
                <input type="button" class="button-secondary wpsf-alias-force-edit" value="<?php esc_html_e('Edit Anyway', 'wp-subscription-forms'); ?>"/>
                <?php
            }
            ?>
            <p class="description">
                <?php
                if (!isset($_GET['form_id'])) {
                    esc_html_e('Alias should be unique and shouldn\'t contain any special characters and please use _ instead of space.', 'wp-subscription-forms');
                } else {
                    esc_html_e('Alias cannot be modified once added because it is used as the reference for fetching the subscribers.', 'wp-subscription-forms');
                }
                ?>
            </p>
        </div>
    </div>

    <div class="wpsf-field-wrap">
        <label><?php esc_html_e('Double Opt-In', 'wp-subscription-forms'); ?></label>
        <div class="wpsf-field">
            <input type="checkbox" name="form_details[general][double_optin]" value="1" class="wpsf-checkbox-toggle-trigger" data-toggle-class="wpsf-double-optin-ref" <?php echo (!empty($form_details['general']['double_optin'])) ? 'checked="checked"' : ''; ?>/>
            <p class="description"><?php esc_html_e('Please check this if you want to send the download link to subscriber only after clicking the subscription confirmation link which will be sent in the email as soon as the subscriber subscribe.', 'wp-subscription-forms'); ?></p>
        </div>
    </div>
    <div class="wpsf-field-wrap wpsf-double-optin-ref" <?php echo (empty($form_details['general']['double_optin'])) ? 'style="display:none";' : ''; ?>>
        <label><?php esc_html_e('Opt-In Confirmation Message', 'wp-subscription-forms'); ?></label>
        <div class="wpsf-field">
            <textarea name="form_details[general][optin_confirmation_message]"><?php echo (!empty($form_details['general']['optin_confirmation_message'])) ? $this->output_converting_br($form_details['general']['optin_confirmation_message']) : $this->get_default_optin_confirmation_message(); ?></textarea>
        </div>
    </div>

    <div class="wpsf-field-wrap">
        <label><?php esc_html_e('Form Success Message', 'wp-subscription-forms'); ?></label>
        <div class="wpsf-field">
            <textarea name="form_details[general][success_message]"><?php echo (!empty($form_details['general']['success_message'])) ? esc_attr($form_details['general']['success_message']) : ''; ?></textarea>
        </div>
    </div>
    <div class="wpsf-field-wrap">
        <label><?php esc_html_e('Form Required Error Message', 'wp-subscription-forms'); ?></label>
        <div class="wpsf-field">
            <textarea name="form_details[general][required_error_message]"><?php echo (!empty($form_details['general']['required_error_message'])) ? esc_attr($form_details['general']['required_error_message']) : ''; ?></textarea>
            <p class="description"><?php esc_html_e('Please enter the message that needs to be shown when any validation error occurs in form submission.', 'wp-subscription-forms'); ?></p>
        </div>
    </div>
    <div class="wpsf-field-wrap">
        <label><?php esc_html_e('Form Error Message', 'wp-subscription-forms'); ?></label>
        <div class="wpsf-field">
            <textarea name="form_details[general][error_message]"><?php echo (!empty($form_details['general']['error_message'])) ? esc_attr($form_details['general']['error_message']) : ''; ?></textarea>
            <p class="description"><?php esc_html_e("Please enter the message that needs to be shown when email couldn't be sent.", 'wp-subscription-forms'); ?></p>
        </div>
    </div>
</div>