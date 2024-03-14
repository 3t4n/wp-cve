<div class="wpsf-settings-each-section" data-tab="form" style="display:none;">

    <div class="wpsf-form-each-component">
        <div class="wpsf-component-head wpsf-clearfix">
            <h4><?php esc_html_e( 'Heading', 'wp-subscription-forms' ); ?></h4>
            <span class="dashicons dashicons-arrow-down"></span>
        </div>
        <div class="wpsf-component-body">
            <div class="wpsf-field-wrap">
                <label><?php esc_html_e( 'Show', 'wp-subscription-forms' ); ?></label>
                <div class="wpsf-field">
                    <input type="checkbox" name="form_details[form][heading][show]" value="1" <?php echo (!empty( $form_details['form']['heading']['show'] )) ? 'checked="checked"' : ''; ?>/>
                </div>
            </div>
            <div class="wpsf-field-wrap">
                <label><?php esc_html_e( 'Heading Text', 'wp-subscription-forms' ); ?></label>
                <div class="wpsf-field">
                    <textarea  name="form_details[form][heading][text]"><?php echo (!empty( $form_details['form']['heading']['text'] )) ? $this->sanitize_html( $form_details['form']['heading']['text'] ) : ''; ?></textarea>
                </div>
            </div>
        </div>
    </div>
    <div class="wpsf-form-each-component">
        <div class="wpsf-component-head wpsf-clearfix">
            <h4><?php esc_html_e( 'Sub Heading', 'wp-subscription-forms' ); ?></h4>
            <span class="dashicons dashicons-arrow-down"></span>
        </div>
        <div class="wpsf-component-body">
            <div class="wpsf-field-wrap">
                <label><?php esc_html_e( 'Show', 'wp-subscription-forms' ); ?></label>
                <div class="wpsf-field">
                    <input type="checkbox" name="form_details[form][sub_heading][show]" value="1" <?php echo (!empty( $form_details['form']['sub_heading']['show'] )) ? 'checked="checked"' : ''; ?>/>
                </div>
            </div>
            <div class="wpsf-field-wrap">
                <label><?php esc_html_e( 'Sub Heading Text', 'wp-subscription-forms' ); ?></label>
                <div class="wpsf-field">
                    <textarea name="form_details[form][sub_heading][text]"><?php echo (!empty( $form_details['form']['sub_heading']['text'] )) ? $this->sanitize_html( $form_details['form']['sub_heading']['text'] ) : ''; ?></textarea>
                </div>
            </div>
        </div>
    </div>
    <div class="wpsf-form-each-component">
        <div class="wpsf-component-head wpsf-clearfix">
            <h4><?php esc_html_e( 'Name', 'wp-subscription-forms' ); ?></h4>
            <span class="dashicons dashicons-arrow-down"></span>
        </div>
        <div class="wpsf-component-body">
            <div class="wpsf-field-wrap">
                <label><?php esc_html_e( 'Show', 'wp-subscription-forms' ); ?></label>
                <div class="wpsf-field">
                    <input type="checkbox" name="form_details[form][name][show]" value="1" <?php echo (!empty( $form_details['form']['name']['show'] )) ? 'checked="checked"' : ''; ?>/>
                </div>
            </div>
            <div class="wpsf-field-wrap">
                <label><?php esc_html_e( 'Required', 'wp-subscription-forms' ); ?></label>
                <div class="wpsf-field">
                    <input type="checkbox" name="form_details[form][name][required]" value="1" <?php echo (!empty( $form_details['form']['name']['required'] )) ? 'checked="checked"' : ''; ?>/>
                </div>
            </div>

            <div class="wpsf-field-wrap">
                <label><?php esc_html_e( 'Label', 'wp-subscription-forms' ); ?></label>
                <div class="wpsf-field">
                    <input type="text" name="form_details[form][name][label]" value="<?php echo (!empty( $form_details['form']['name']['label'] )) ? esc_attr( $form_details['form']['name']['label'] ) : ''; ?>"/>
                </div>
            </div>
        </div>
    </div>
    <div class="wpsf-form-each-component">
        <div class="wpsf-component-head wpsf-clearfix">
            <h4><?php esc_html_e( 'Email', 'wp-subscription-forms' ); ?></h4>
            <span class="dashicons dashicons-arrow-down"></span>
        </div>
        <div class="wpsf-component-body">
            <div class="wpsf-field-wrap">
                <label><?php esc_html_e( 'Label', 'wp-subscription-forms' ); ?></label>
                <div class="wpsf-field">
                    <input type="text" name="form_details[form][email][label]" value="<?php echo (!empty( $form_details['form']['email']['label'] )) ? esc_attr( $form_details['form']['email']['label'] ) : ''; ?>"/>
                </div>
            </div>
        </div>
    </div>
    <div class="wpsf-form-each-component">
        <div class="wpsf-component-head wpsf-clearfix">
            <h4><?php esc_html_e( 'Terms and Agreement', 'wp-subscription-forms' ); ?></h4>
            <span class="dashicons dashicons-arrow-down"></span>
        </div>
        <div class="wpsf-component-body">
            <div class="wpsf-field-wrap">
                <label><?php esc_html_e( 'Show', 'wp-subscription-forms' ); ?></label>
                <div class="wpsf-field">
                    <input type="checkbox" name="form_details[form][terms_agreement][show]" <?php echo (!empty( $form_details['form']['terms_agreement']['show'] )) ? 'checked="checked"' : ''; ?> value="1"/>
                </div>
            </div>
            <div class="wpsf-field-wrap">
                <label><?php esc_html_e( 'Text', 'wp-subscription-forms' ); ?></label>
                <div class="wpsf-field">
                    <textarea name="form_details[form][terms_agreement][agreement_text]"><?php echo (!empty( $form_details['form']['terms_agreement']['agreement_text'] )) ? $this->sanitize_html( $form_details['form']['terms_agreement']['agreement_text'] ) : ''; ?></textarea>
                    <p class="description"><?php esc_html_e( 'You can enter basic html tags such as strong, a, ul, li etc.', 'wp-subscription-forms' ); ?></p>

                </div>
            </div>
        </div>
    </div>
    <div class="wpsf-form-each-component">
        <div class="wpsf-component-head wpsf-clearfix">
            <h4><?php esc_html_e( 'Subscribe Button', 'wp-subscription-forms' ); ?></h4>
            <span class="dashicons dashicons-arrow-down"></span>
        </div>
        <div class="wpsf-component-body">
            <div class="wpsf-field-wrap">
                <label><?php esc_html_e( 'Button Text', 'wp-subscription-forms' ); ?></label>
                <div class="wpsf-field">
                    <input type="text" name="form_details[form][subscribe_button][button_text]" placeholder="<?php esc_html_e( 'Subscribe', 'wp-subscription-forms' ); ?>" value="<?php echo (!empty( $form_details['form']['subscribe_button']['button_text'] )) ? esc_attr( $form_details['form']['subscribe_button']['button_text'] ) : ''; ?>">
                </div>
            </div>
        </div>
    </div>
    <div class="wpsf-form-each-component">
        <div class="wpsf-component-head wpsf-clearfix">
            <h4><?php esc_html_e( 'Footer', 'wp-subscription-forms' ); ?></h4>
            <span class="dashicons dashicons-arrow-down"></span>
        </div>
        <div class="wpsf-component-body">
            <div class="wpsf-field-wrap">
                <label><?php esc_html_e( 'Show', 'wp-subscription-forms' ); ?></label>
                <div class="wpsf-field">
                    <input type="checkbox" name="form_details[form][footer][show]" <?php echo (!empty( $form_details['form']['footer']['show'] )) ? 'checked="checked"' : ''; ?> value="1"/>
                </div>
            </div>
            <div class="wpsf-field-wrap">
                <label><?php esc_html_e( 'Footer Text', 'wp-subscription-forms' ); ?></label>
                <div class="wpsf-field">
                    <textarea name="form_details[form][footer][footer_text]"><?php echo (!empty( $form_details['form']['footer']['footer_text'] )) ? $this->sanitize_html( $form_details['form']['footer']['footer_text'] ) : ''; ?></textarea>
                </div>
            </div>
        </div>
    </div>



</div>