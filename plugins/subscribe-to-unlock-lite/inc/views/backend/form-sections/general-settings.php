<div class="stul-settings-each-section" data-tab="general">
    <div class="stul-field-wrap">
        <label><?php esc_html_e('Static Lock Content', 'subscribe-to-unlock-lite'); ?></label>
        <div class="stul-field">
            <?php
            $lock_content = (!empty($form_details['general']['lock_content'])) ? $this->sanitize_html($form_details['general']['lock_content']) : '';
            wp_editor($lock_content, 'stul_lock_content', array('textarea_name' => 'form_details[general][lock_content]'));
            ?>
            <p class="description"><?php esc_html_e('Please leave the lock content blank if you are willing to lock the content directly from your post or page editor. And you can also use shortcode from other plugin inside the lock content if required.', 'subscribe-to-unlock-lite'); ?></p>
        </div>
    </div>
    <div class="stul-field-wrap">
        <label><?php esc_html_e('Lock Mode', 'subscribe-to-unlock-lite'); ?></label>
        <div class="stul-field">
            <select name="form_details[general][lock_mode]">
                <?php
                $selected_lock_mode = (!empty($form_details['general']['lock_mode'])) ? $form_details['general']['lock_mode'] : 'soft';
                ?>
                <option value="soft" <?php selected($selected_lock_mode, 'soft'); ?>><?php esc_html_e('Soft Lock', 'subscribe-to-unlock-lite'); ?></option>
                <option value="hard" <?php selected($selected_lock_mode, 'hard'); ?>><?php esc_html_e('Hard Lock', 'subscribe-to-unlock-lite'); ?></option>
            </select>
            <p class="description"><?php esc_html_e('Soft lock will blur the content whereas hard lock will completely hide the content.', 'subscribe-to-unlock-lite'); ?></p>
        </div>
    </div>
    <div class="stul-field-wrap">
        <label><?php esc_html_e('Verification', 'subscribe-to-unlock-lite'); ?></label>
        <div class="stul-field">
            <input type="checkbox" name="form_details[general][verification]" value="1" <?php echo (!empty($form_details['general']['verification'])) ? 'checked="checked"' : ''; ?> class="stul-checkbox-toggle-trigger" data-toggle-class="stul-verification-ref" />
            <p class="description"><?php esc_html_e('Please check if you want to enable the email verification of the subscriber before unlocking the content.', 'subscribe-to-unlock-lite'); ?></p>
        </div>
    </div>
    <?php
    $verification = (!empty($form_details['general']['verification'])) ? 1 : 0;
    $verification_type = (!empty($form_details['general']['verification_type'])) ? $form_details['general']['verification_type'] : 'link';
    ?>
    <div class="stul-verification-ref" <?php $this->display_none($verification, 1); ?>>
        <div class="stul-field-wrap">
            <label><?php esc_html_e('Verification Type', 'subscribe-to-unlock-lite'); ?></label>
            <div class="stul-field">
                <select name="form_details[general][verification_type]" class="stul-toggle-trigger" data-toggle-class="stul-verification-type-ref">
                    <option value="link" <?php selected($verification_type, 'link'); ?>><?php esc_html_e('Verification through link', 'subscribe-to-unlock-lite'); ?></option>
                    <option value="unlock_code" <?php selected($verification_type, 'unlock_code'); ?>><?php esc_html_e('Verification through code', 'subscribe-to-unlock-lite'); ?></option>
                </select>
                <p class="description"><?php esc_html_e('If verification through link is selected, the verification link will be sent in the email.', 'subscribe-to-unlock-lite'); ?></p>
                <p class="description"><?php esc_html_e('If verification through code is selected, the verification code will be sent in the email which can be entered in the form for unlocking the content.', 'subscribe-to-unlock-lite'); ?></p>
            </div>
        </div>
        <div class="stul-verification-type-ref" <?php $this->display_none($verification_type, 'unlock_code'); ?> data-toggle-ref="unlock_code">
            <div class="stul-field-wrap">
                <label><?php esc_html_e('Unlock Message', 'subscribe-to-unlock-lite'); ?></label>
                <div class="stul-field">
                    <textarea name="form_details[general][unlock_message]"><?php echo (!empty($form_details['general']['unlock_message'])) ? $this->sanitize_html($form_details['general']['unlock_message']) : ''; ?></textarea>
                </div>
            </div>
            <div class="stul-field-wrap">
                <label><?php esc_html_e('Unlock Button Label', 'subscribe-to-unlock-lite'); ?></label>
                <div class="stul-field">
                    <input type="text" name="form_details[general][unlock_button_label]" value="<?php echo (!empty($form_details['general']['unlock_button_label'])) ? esc_attr($form_details['general']['unlock_button_label']) : ''; ?>" />
                </div>
            </div>
            <div class="stul-field-wrap">
                <label><?php esc_html_e('Unlock Error Message', 'subscribe-to-unlock-lite'); ?></label>
                <div class="stul-field">
                    <textarea name="form_details[general][unlock_error_message]"><?php echo (!empty($form_details['general']['unlock_error_message'])) ? $this->sanitize_html($form_details['general']['unlock_error_message']) : ''; ?></textarea>
                </div>
            </div>
        </div>
        <div class="stul-verification-type-ref" <?php $this->display_none($verification_type, 'link'); ?> data-toggle-ref="link">
            <div class="stul-field-wrap">
                <label><?php esc_html_e('Unlock Link Message', 'subscribe-to-unlock-lite'); ?></label>
                <div class="stul-field">
                    <textarea name="form_details[general][unlock_link_message]"><?php echo (!empty($form_details['general']['unlock_link_message'])) ? $this->output_converting_br($form_details['general']['unlock_link_message']) : $this->get_default_unlock_link_message(); ?></textarea>
                    <p class="description"><?php esc_html_e('Please enter the message to be displayed when the unlock link is clicked.', 'subscribe-to-unlock-lite'); ?></p>
                </div>
            </div>
        </div>
    </div>
    <div class="stul-field-wrap">
        <label><?php esc_html_e('Form Success Message', 'subscribe-to-unlock-lite'); ?></label>
        <div class="stul-field">
            <textarea name="form_details[general][success_message]"><?php echo (!empty($form_details['general']['success_message'])) ? esc_attr($form_details['general']['success_message']) : ''; ?></textarea>
        </div>
    </div>
    <div class="stul-field-wrap">
        <label><?php esc_html_e('Form Required Error Message', 'subscribe-to-unlock-lite'); ?></label>
        <div class="stul-field">
            <textarea name="form_details[general][required_error_message]"><?php echo (!empty($form_details['general']['required_error_message'])) ? esc_attr($form_details['general']['required_error_message']) : ''; ?></textarea>
            <p class="description"><?php esc_html_e('Please enter the message that needs to be shown when any validation error occurs in form submission.', 'subscribe-to-unlock-lite'); ?></p>
        </div>
    </div>
    <div class="stul-field-wrap">
        <label><?php esc_html_e('Form Error Message', 'subscribe-to-unlock-lite'); ?></label>
        <div class="stul-field">
            <textarea name="form_details[general][error_message]"><?php echo (!empty($form_details['general']['error_message'])) ? esc_attr($form_details['general']['error_message']) : ''; ?></textarea>
            <p class="description"><?php esc_html_e("Please enter the message that needs to be shown when email couldn't be sent.", 'subscribe-to-unlock-lite'); ?></p>
        </div>
    </div>
    <div class="stul-field-wrap">
        <label><?php esc_html_e('Test Mode', 'subscribe-to-unlock-lite'); ?></label>
        <div class="stul-field">
            <input type="checkbox" name="form_details[general][test_mode]" <?php echo (!empty($form_details['general']['test_mode'])) ? 'checked="checked"' : ''; ?>>
            <p class="description"><?php esc_html_e('Please check this if you want to display the unlock form for the administrator even if it has been already unlocked.', 'subscribe-to-unlock'); ?></p>
        </div>
    </div>


</div>