<?php $selected_layout = (!empty($form_details['layout']['template'])) ? esc_attr($form_details['layout']['template']) : 'template-1'; ?>
<div class="stul-settings-each-section stul-display-none" data-tab="layout">

    <div class="stul-field-wrap">
        <label><?php esc_html_e('Form Layout', 'subscribe-to-unlock-lite'); ?></label>
        <div class="stul-field">
            <select name="form_details[layout][template]" class="stul-toggle-trigger" data-toggle-class="stul-form-layout">
                <?php
                for ($i = 1; $i <= STUL_TOTAL_TEMPLATES; $i++) {
                    ?>
                    <option value="template-<?php echo intval($i); ?>" <?php echo selected($selected_layout, 'template-' . $i); ?>>Template <?php echo intval($i); ?></option>
                    <?php
                }
                ?>
            </select>
            <div class="stul-preview-img-wrapper">
                <?php
                for ($i = 1; $i <= STUL_TOTAL_TEMPLATES; $i++) {
                    ?>
                    <div class="stul-form-layout" <?php $this->display_none($selected_layout, 'template-' . $i); ?> data-toggle-ref="template-<?php echo intval($i); ?>">
                        <img src="<?php echo esc_url(STUL_IMG_DIR . '/template-previews/template-' . $i . '.jpg'); ?>"/>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
    <div class="stul-field-wrap">
        <label><?php esc_html_e('Form Width', 'subscribe-to-unlock-lite'); ?></label>
        <div class="stul-field">
            <input type="text" name="form_details[layout][form_width]" value="<?php echo (!empty($form_details['layout']['form_width'])) ? esc_attr($form_details['layout']['form_width']) : ''; ?>" placeholder="<?php esc_html_e('500px or 100%', 'subscribe-to-unlock-lite'); ?>"/>
            <p class="description"><?php esc_html_e('Please enter the width of the form either in px or %', 'subscribe-to-unlock-lite'); ?></p>
        </div>
    </div>
</div>