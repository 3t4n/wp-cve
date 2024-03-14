<table class="form-table frm-no-margin">
    <tr>
        <td class="frm_left_label">
            <label for="signer_name"><?php _e('Signer Name', 'formidable') ?></label>
            <span class="frm_help frm_icon_font frm_tooltip_icon" title="" data-original-title="Please select a field this will be used as signer legal name of your agreement contracts."></span>
        </td>
        <td>

            <select name="<?php echo esc_attr($this->get_field_name('signer_name')) ?>" id="signer_name" required>
                <?php


                $formfield = FrmFieldsHelper::get_form_fields($formId, '');
                $value = esc_attr($form_action->post_content['signer_name']);
                echo '<option value=""> ' . __('Select a name field', 'esig') . ' </option>';
                    foreach ($formfield as $field) {                                    
                    if(in_array($field->type, ["text","name","hidden"] )) {
                    echo '<option value="' . $field->id . '" ' . selected($value, $field->id) . '> ' . $field->name . ' </option>';
                    }
                }

                ?>
            </select>
        </td>
    </tr>

    <tr>
        <td class="frm_left_label">
            <label for="signer_email"><?php _e('Signer Email', 'formidable') ?></label>
            <span class="frm_help frm_icon_font frm_tooltip_icon" title="" data-original-title="select a field this will be used as signer E-mail address of your agreement contracts."></span>
        </td>
        <td>
            <select name="<?php echo esc_attr($this->get_field_name('signer_email')) ?>" id="signer_email" required>
                <?php


                $formfield = FrmFieldsHelper::get_form_fields($formId, '');
                $value = esc_attr($form_action->post_content['signer_email']);
                echo '<option value=""> ' . __('Select a email field', 'esig') . ' </option>';
                foreach ($formfield as $field) {
                    if(in_array($field->type, ["email","hidden"] )){;
                        echo '<option value="' . $field->id . '" ' . selected($value, $field->id) . '> ' . $field->name . ' </option>';
                    }
                }

                ?>
            </select>
        </td>
    </tr>



    <tr>
        <td class="frm_left_label"><label for="signing_logic"><?php _e('Signing Logic', 'formidable') ?></label></td>
        <td>
            <select name="<?php echo esc_attr($this->get_field_name('signing_logic')) ?>" id="signing_logic">
                <?php $value = esc_attr($form_action->post_content['signing_logic']); ?>
                <option value="redirect" <?php selected($value, 'redirect') ?>><?php _e('Redirect user to Contract/Agreement after Submission', 'formidable') ?></option>
                <option value="email" <?php selected($value, 'email') ?>><?php _e('Send User an Email Requesting their Signature after Submission', 'formidable') ?></option>
            </select>
        </td>
    </tr>
    <?php if (class_exists('FrmPaymentSettingsController')) :
        $redirect_after_payment = isset($form_action->post_content['redirect_after_payment']) ? $form_action->post_content['redirect_after_payment'] : false;
    ?>
        <tr>
            <td class="frm_left_label"></td>
            <td>
                <label for="forcing-redirect-after-paypal-payment">
                    <input type="checkbox" value="1" name="<?php echo esc_attr($this->get_field_name('redirect_after_payment')) ?>" <?php checked($redirect_after_payment, 1) ?> id="<?php echo esc_attr($this->get_field_id('redirect_after_payment')) ?>" />
                    <?php _e('Hold agreement redirection until payment is received.', 'formidable') ?>
                    <span class="frm_help frm_icon_font frm_tooltip_icon" title="<?php esc_attr_e('Redirect agreement when the successful payment notification is received from PayPal.If you enable this multiple agreement will not work.', 'frmpp') ?>"></span>
                </label>
            </td>
        </tr>
    <?php endif; ?>
    <td class="frm_left_label"><label for="select_sad"><?php _e('Select Sad', 'formidable') ?></label></td>
    <td>
        <select name="<?php echo esc_attr($this->get_field_name('select_sad')) ?>" id="select_sad" required>
            <?php
            if (class_exists('esig_sad_document')) {

                $sad = new esig_sad_document();
                $sad_pages = $sad->esig_get_sad_pages();
                $value = esc_attr($form_action->post_content['select_sad']);
                echo '<option value=""> ' . __('Select an agreement page', 'esig') . ' </option>';
                foreach ($sad_pages as $page) {

                    if (get_the_title($page->page_id)) {
                        echo '<option value="' . $page->page_id . '" ' . selected($value, $page->page_id) . '> ' . get_the_title($page->page_id) . ' </option>';
                    }
                }
            }
            ?>
        </select>
    </td>
    </tr>


    <tr>
        <td class="frm_left_label"><label for="underline_data"><?php _e('Display type', 'formidable') ?></label></td>
        <td>
            <select name="<?php echo esc_attr($this->get_field_name('underline_data')) ?>" id="signing_logic">
                <?php $value = esc_attr($form_action->post_content['underline_data']); ?>
                <option value="underline" <?php selected($value, 'underline') ?>><?php _e('Underline the data That was submitted from this Formidable form', 'formidable') ?></option>
                <option value="not_underline" <?php selected($value, 'not_underline') ?>><?php _e('Do not underline the data that was submitted from the Formidable Form', 'formidable') ?></option>
            </select>
        </td>
    </tr>

    <tr>
        <th scope="row">
            <label for="signing_reminder_email"><?php _e('Signing Reminder Email', 'esig'); ?></label>
        </th>
        <td>
            <?php $reminderChecked = (!empty(checked($form_action->post_content['enable_signing_reminder_email'], 1, false)))? true : false ; ?>
            <input name="signing_reminder_email" type="hidden" value="0" />
            <input onclick="prefill()" id="reminder_data" type="checkbox" name="<?php echo esc_attr($this->get_field_name('enable_signing_reminder_email')) ?>" id="<?php echo esc_attr($this->get_field_name('enable_signing_reminder_email')) ?>" value="1" <?php checked($form_action->post_content['enable_signing_reminder_email'], 1); ?> /><?php _e('Enabling signing reminder email. If/When user has not sign the document', 'esig'); ?><br><br>
            <div id="reminder_section" <?php if (empty(checked($form_action->post_content['enable_signing_reminder_email'], 1, false))) {
                                            echo  'style="visibility:hidden;"';
                                        } ?>>
                <?php _e('Send the first reminder to the signer ', 'esig'); ?><input <?php if($reminderChecked) { echo "required"; } ?> type="textbox" name="<?php echo esc_attr($this->get_field_name('reminder_email')) ?>" id="reminder_email" min="1" oninput="this.value = (!isNaN(Math.abs(this.value)) && this.value>0)?Math.abs(this.value):null" value="<?php echo esc_attr($form_action->post_content['reminder_email']); ?>" style="width:40px;height:30px;"> <?php _e('days after the initial signing request.', 'esig'); ?><br><br>
                <?php _e('Send the second reminder to the signer ', 'esig'); ?><input <?php if($reminderChecked) { echo "required"; } ?> type="textbox" name="<?php echo esc_attr($this->get_field_name('first_reminder_send')) ?>" id="first_reminder_send" min="1" oninput="this.value = (!isNaN(Math.abs(this.value)) && this.value>0)?Math.abs(this.value):null" value="<?php echo esc_attr($form_action->post_content['first_reminder_send']); ?>" style="width:40px;height:30px;"> <?php _e('days after the initial signing request.', 'esig'); ?><br><br>
                <?php _e('Send the last reminder to the signer ', 'esig'); ?><input <?php if($reminderChecked) { echo "required"; } ?> type="textbox" name="<?php echo esc_attr($this->get_field_name('expire_reminder')) ?>" id="expire_reminder" min="1" oninput="this.value = (!isNaN(Math.abs(this.value)) && this.value>0)?Math.abs(this.value):null" value="<?php echo esc_attr($form_action->post_content['expire_reminder']); ?>" value="" style="width:40px;height:30px;"> <?php _e('days after the initial signing request.', 'esig'); ?>

            </div>
        </td>
    </tr>


</table>