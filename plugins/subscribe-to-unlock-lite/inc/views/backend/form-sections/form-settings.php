<div class="stul-settings-each-section stul-display-none" data-tab="form">
    <div class="stul-form-each-component">
        <div class="stul-component-head stul-clearfix">
            <h4><?php esc_html_e('Heading', 'subscribe-to-unlock-lite'); ?></h4>
            <span class="dashicons dashicons-arrow-down"></span>
        </div>
        <div class="stul-component-body">
            <div class="stul-field-wrap">
                <label><?php esc_html_e('Show', 'subscribe-to-unlock-lite'); ?></label>
                <div class="stul-field">
                    <input type="checkbox" name="form_details[form][heading][show]" value="1" <?php echo (!empty($form_details['form']['heading']['show'])) ? 'checked="checked"' : ''; ?>/>
                </div>
            </div>
            <div class="stul-field-wrap">
                <label><?php esc_html_e('Heading Text', 'subscribe-to-unlock-lite'); ?></label>
                <div class="stul-field">
                    <textarea  name="form_details[form][heading][text]"><?php echo (!empty($form_details['form']['heading']['text'])) ? $this->sanitize_html($form_details['form']['heading']['text']) : ''; ?></textarea>
                </div>
            </div>
        </div>
    </div>
    <div class="stul-form-each-component">
        <div class="stul-component-head stul-clearfix">
            <h4><?php esc_html_e('Sub Heading', 'subscribe-to-unlock-lite'); ?></h4>
            <span class="dashicons dashicons-arrow-down"></span>
        </div>
        <div class="stul-component-body">
            <div class="stul-field-wrap">
                <label><?php esc_html_e('Show', 'subscribe-to-unlock-lite'); ?></label>
                <div class="stul-field">
                    <input type="checkbox" name="form_details[form][sub_heading][show]" value="1" <?php echo (!empty($form_details['form']['sub_heading']['show'])) ? 'checked="checked"' : ''; ?>/>
                </div>
            </div>
            <div class="stul-field-wrap">
                <label><?php esc_html_e('Sub Heading Text', 'subscribe-to-unlock-lite'); ?></label>
                <div class="stul-field">
                    <textarea name="form_details[form][sub_heading][text]"><?php echo (!empty($form_details['form']['sub_heading']['text'])) ? $this->sanitize_html($form_details['form']['sub_heading']['text']) : ''; ?></textarea>
                </div>
            </div>
        </div>
    </div>
    <div class="stul-form-each-component">
        <div class="stul-component-head stul-clearfix">
            <h4><?php esc_html_e('Name', 'subscribe-to-unlock-lite'); ?></h4>
            <span class="dashicons dashicons-arrow-down"></span>
        </div>
        <div class="stul-component-body">
            <div class="stul-field-wrap">
                <label><?php esc_html_e('Show', 'subscribe-to-unlock-lite'); ?></label>
                <div class="stul-field">
                    <input type="checkbox" name="form_details[form][name][show]" value="1" <?php echo (!empty($form_details['form']['name']['show'])) ? 'checked="checked"' : ''; ?>/>
                </div>
            </div>
            <div class="stul-field-wrap">
                <label><?php esc_html_e('Required', 'subscribe-to-unlock-lite'); ?></label>
                <div class="stul-field">
                    <input type="checkbox" name="form_details[form][name][required]" value="1" <?php echo (!empty($form_details['form']['name']['required'])) ? 'checked="checked"' : ''; ?>/>
                </div>
            </div>

            <div class="stul-field-wrap">
                <label><?php esc_html_e('Label', 'subscribe-to-unlock-lite'); ?></label>
                <div class="stul-field">
                    <input type="text" name="form_details[form][name][label]" value="<?php echo (!empty($form_details['form']['name']['label'])) ? esc_attr($form_details['form']['name']['label']) : ''; ?>"/>
                </div>
            </div>
        </div>
    </div>
    <div class="stul-form-each-component">
        <div class="stul-component-head stul-clearfix">
            <h4><?php esc_html_e('Email', 'subscribe-to-unlock-lite'); ?></h4>
            <span class="dashicons dashicons-arrow-down"></span>
        </div>
        <div class="stul-component-body">
            <div class="stul-field-wrap">
                <label><?php esc_html_e('Label', 'subscribe-to-unlock-lite'); ?></label>
                <div class="stul-field">
                    <input type="text" name="form_details[form][email][label]" value="<?php echo (!empty($form_details['form']['email']['label'])) ? esc_attr($form_details['form']['email']['label']) : ''; ?>"/>
                </div>
            </div>
        </div>
    </div>
    <div class="stul-form-each-component">
        <div class="stul-component-head stul-clearfix">
            <h4><?php esc_html_e('Terms and Agreement', 'subscribe-to-unlock-lite'); ?></h4>
            <span class="dashicons dashicons-arrow-down"></span>
        </div>
        <div class="stul-component-body">
            <div class="stul-field-wrap">
                <label><?php esc_html_e('Show', 'subscribe-to-unlock-lite'); ?></label>
                <div class="stul-field">
                    <input type="checkbox" name="form_details[form][terms_agreement][show]" <?php echo (!empty($form_details['form']['terms_agreement']['show'])) ? 'checked="checked"' : ''; ?>/>
                </div>
            </div>
            <div class="stul-field-wrap">
                <label><?php esc_html_e('Text', 'subscribe-to-unlock-lite'); ?></label>
                <div class="stul-field">
                    <textarea name="form_details[form][terms_agreement][agreement_text]"><?php echo (!empty($form_details['form']['terms_agreement']['agreement_text'])) ? $this->sanitize_html($form_details['form']['terms_agreement']['agreement_text']) : ''; ?></textarea>
                    <p class="description"><?php esc_html_e('You can enter basic html tags such as strong, a, ul, li etc.', 'subscribe-to-unlock-lite'); ?></p>

                </div>
            </div>
        </div>
    </div>
    <div class="stul-form-each-component">
        <div class="stul-component-head stul-clearfix">
            <h4><?php esc_html_e('Subscribe Button', 'subscribe-to-unlock-lite'); ?></h4>
            <span class="dashicons dashicons-arrow-down"></span>
        </div>
        <div class="stul-component-body">
            <div class="stul-field-wrap">
                <label><?php esc_html_e('Button Text', 'subscribe-to-unlock-lite'); ?></label>
                <div class="stul-field">
                    <input type="text" name="form_details[form][subscribe_button][button_text]" placeholder="<?php esc_html_e('Subscribe', 'subscribe-to-unlock-lite'); ?>" value="<?php echo (!empty($form_details['form']['subscribe_button']['button_text'])) ? esc_attr($form_details['form']['subscribe_button']['button_text']) : ''; ?>">
                </div>
            </div>
        </div>
    </div>
    <div class="stul-form-each-component">
        <div class="stul-component-head stul-clearfix">
            <h4><?php esc_html_e('Footer', 'subscribe-to-unlock-lite'); ?></h4>
            <span class="dashicons dashicons-arrow-down"></span>
        </div>
        <div class="stul-component-body">
            <div class="stul-field-wrap">
                <label><?php esc_html_e('Show', 'subscribe-to-unlock-lite'); ?></label>
                <div class="stul-field">
                    <input type="checkbox" name="form_details[form][footer][show]" <?php echo (!empty($form_details['form']['footer']['show'])) ? 'checked="checked"' : ''; ?>/>
                </div>
            </div>
            <div class="stul-field-wrap">
                <label><?php esc_html_e('Footer Text', 'subscribe-to-unlock-lite'); ?></label>
                <div class="stul-field">
                    <textarea name="form_details[form][footer][footer_text]"><?php echo (!empty($form_details['form']['footer']['footer_text'])) ? $this->sanitize_html($form_details['form']['footer']['footer_text']) : ''; ?></textarea>
                </div>
            </div>
        </div>
    </div>



</div>