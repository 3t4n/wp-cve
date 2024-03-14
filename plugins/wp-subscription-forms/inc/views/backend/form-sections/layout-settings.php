<?php $selected_layout = (!empty($form_details['layout']['template'])) ? esc_attr($form_details['layout']['template']) : 'template-1'; ?>
<div class="wpsf-settings-each-section" data-tab="layout" style="display:none;">

    <div class="wpsf-field-wrap">
        <label><?php esc_html_e('Form Layout', 'wp-subscription-forms'); ?></label>
        <div class="wpsf-field">
            <select name="form_details[layout][template]" class="wpsf-toggle-trigger" data-toggle-class="wpsf-form-layout">
                <?php
                for ($i = 1; $i <= WPSF_TOTAL_TEMPLATES; $i++) {
                    ?>
                    <option value="template-<?php echo intval($i); ?>" <?php echo selected($selected_layout, 'template-' . $i); ?>>Template <?php echo intval($i); ?></option>
                    <?php
                }
                ?>
            </select>
            <div class="wpsf-preview-img-wrapper">
                <?php
                for ($i = 1; $i <= WPSF_TOTAL_TEMPLATES; $i++) {
                    ?>
                    <div class="wpsf-form-layout" <?php $this->display_none($selected_layout, 'template-' . $i); ?> data-toggle-ref="template-<?php echo intval($i); ?>">
                        <img src="<?php echo esc_url(WPSF_IMG_DIR . '/template-previews/template-' . $i . '.jpg'); ?>"/>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
    <div class="wpsf-field-wrap">
        <label><?php esc_html_e('Form Width', 'wp-subscription-forms'); ?></label>
        <div class="wpsf-field">
            <input type="text" name="form_details[layout][form_width]" value="<?php echo (!empty($form_details['layout']['form_width'])) ? esc_attr($form_details['layout']['form_width']) : ''; ?>" placeholder="<?php esc_html_e('500px or 100%', 'wp-subscription-forms'); ?>"/>
            <p class="description"><?php esc_html_e('Please enter the width of the form either in px or %', 'wp-subscription-forms'); ?></p>
        </div>
    </div>
    <div class="wpsf-field-wrap">
        <label><?php esc_html_e('Display Type', 'wp-subscription-forms'); ?></label>
        <div class="wpsf-field">
            <select name="form_details[layout][display_type]" class="wpsf-toggle-trigger" data-toggle-class='wpsf-display-type'>
                <?php $selected_display_type = (!empty($form_details['layout']['display_type'])) ? $form_details['layout']['display_type'] : 'direct'; ?>
                <option value="direct" <?php selected($selected_display_type, 'direct'); ?>><?php esc_html_e('Direct Display', 'wp-subscription-forms'); ?></option>
                <option value="popup" <?php selected($selected_display_type, 'popup'); ?>><?php esc_html_e('Popup Display', 'wp-subscription-forms'); ?></option>
            </select>
        </div>
    </div>
    <div class="wpsf-field-wrap wpsf-display-type" data-toggle-ref='popup' <?php $this->display_none($selected_display_type, 'popup') ?>>
        <label><?php esc_html_e('Popup Trigger Text', 'wp-subscription-forms'); ?></label>
        <div class="wpsf-field">
            <input type="text" name="form_details[layout][popup_trigger_text]" value="<?php echo (!empty($form_details['layout']['popup_trigger_text'])) ? esc_attr($form_details['layout']['popup_trigger_text']) : ''; ?>"/>
        </div>
    </div>
</div>