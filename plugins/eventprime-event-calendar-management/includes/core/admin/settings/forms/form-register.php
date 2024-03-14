<div class="ep-register-tab-content">
    <h2><?php esc_html_e( 'Registration Form Settings', 'eventprime-event-calendar-management' );?></h2>
    <input type="hidden" name="em_setting_type" value="register_form_settings">
    <p>
        <?php $back_url = remove_query_arg( 'section' ) ;?>
        <a href="<?php echo esc_url( $back_url );?>">
            <- <?php esc_html_e( 'Back', 'eventprime-event-calendar-management' );?>
        </a>
    </p>
</div>
<div class="ep-settings-form-list">
    <table class="form-table">
        <tbody>
            <tr id="login_registerform_setting"  <?php if( ! isset( $options['global']->login_show_registerlink ) || $options['global']->login_show_registerlink != 1 ){ echo 'style="display:none;"'; }?>>
                <th scope="row" class="titledesc">
                    <label for="login_registration_form">
                        <?php esc_html_e( 'Register Form', 'eventprime-event-calendar-management' );?>
                    </label>
                </th>
                <td class="forminp forminp-text">
                    <select name="login_registration_form" id="login_registration_form" class="ep-form-control" required>
                        <option value=""><?php esc_html_e( 'Select Form', 'eventprime-event-calendar-management' );?></option>
                        <?php foreach( $options['registration_forms_list'] as $key => $rform ) {?>
                            <option value="<?php echo esc_attr( $key );?>" <?php if( $options['global']->login_registration_form == $key ){ echo 'selected="selected"';}?>>
                                <?php echo $rform;?>
                            </option><?php
                        }?>
                    </select>
                </td>
            </tr>
            <tr id="login_rm_registerform_setting" <?php if( $options['global']->login_registration_form !== 'rm' ){ echo 'style="display:none;"'; }?>>
                <th scope="row" class="titledesc">
                    <label for="login_rm_registration_form">
                        <?php esc_html_e( 'Select Registration Magic Form', 'eventprime-event-calendar-management' );?>
                    </label>
                </th>
                <td class="forminp forminp-text">
                    <select name="login_rm_registration_form" id="login_rm_registration_form" class="ep-form-control">
                        <option value=""><?php esc_html_e( 'Select Form', 'eventprime-event-calendar-management' );?></option>
                        <?php foreach( $options['rm_forms'] as $rm_id => $rm_form ) {?>
                            <option value="<?php echo esc_attr( $rm_id );?>" <?php if( $options['global']->login_rm_registration_form == $rm_id ){ echo 'selected="selected"';}?>>
                                <?php echo $rm_form;?>
                            </option><?php
                        }?>
                    </select>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row" class="titledesc">
                    <label for="register_google_recaptcha">
                        <?php esc_html_e( "Enable reCAPTCHA ", 'eventprime-event-calendar-management' );?>
                    </label>
                </th>
                <td class="forminp forminp-text">
                    <label class="ep-toggle-btn">
                        <input type="checkbox" name="register_google_recaptcha" id="register_google_recaptcha" class="" <?php if( $options['global']->register_google_recaptcha == 1 ) { echo 'checked="checked"';} ?> <?php if(empty(ep_get_global_settings('google_recaptcha'))){echo 'readyonly disabled=""';}?>/>
                        <span class="ep-toogle-slider round"></span>
                    </label>
                    <?php if( empty( ep_get_global_settings( 'google_recaptcha' ) ) ) {?>
                        <div class="ep-help-tip-info ep-text-danger"><?php esc_html_e( 'reCAPTCHA not enabled. To enable reCAPTCHA go to Settings > General > Third-Party.', 'eventprime-event-calendar-management' );?></div><?php 
                    }?>
                    <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e( 'If enabled, users will have to verify reCAPTCHA before they are allowed to submit the form.', 'eventprime-event-calendar-management' );?></div>
                </td>
            </tr>
        </tbody>
    </table>
    
    <div class="ep-registration-form-field-list-wrap" id="ep_user_registration_form_settings" <?php if( ep_get_global_settings( 'login_registration_form' ) != 'ep' ) { echo 'style="display:none;"'; }?>>
        <table class="form-table">
            <tbody>
                <tr valign="top">
                    <td class="ep-form-table-wrapper" colspan="2">
                    <table class="ep-form-table-setting ep-reg-form-field-setting widefat" id="ep_settings_register_form_fields">
                        <thead>
                            <tr>
                                <th>
                                    <?php esc_html_e('Field', 'eventprime-event-calendar-management'); ?>
                                </th>
                                <th>
                                    <?php esc_html_e('Show', 'eventprime-event-calendar-management'); ?>
                                </th>
                                <th>
                                    <?php esc_html_e('Mandatory', 'eventprime-event-calendar-management'); ?>
                                </th>
                                <th>
                                    <?php esc_html_e('Label', 'eventprime-event-calendar-management'); ?>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                           <tr>
                               <td>
                                   <?php esc_html_e('Username', 'eventprime-event-calendar-management'); ?>
                               </td>
                               <td>
                                    <input type="checkbox" name="register_username[show]" value="1" id="register_username_show" 
                                        data-field="register_username" 
                                        data-property="show" 
                                        <?php if (isset($options['global']->register_username['show']) && $options['global']->register_username['show'] == 1) {
                                            echo 'checked="checked"';
                                        } ?>  
                                    />
                                </td>
                                <td>
                                    <input type="checkbox" name="register_username[mandatory]" value="1" id="register_username_mandatory" 
                                        data-field="register_username" 
                                        data-property="mandatory" 
                                        <?php if (isset($options['global']->register_username['mandatory']) && $options['global']->register_username['mandatory'] == 1) {
                                            echo 'checked="checked"';
                                        } ?> 
                                    />
                                </td>
                                <td>
                                    <input type="text" name="register_username[label]" id="register_username_label" 
                                        value="<?php if ( isset( $options['global']->register_username['label'] ) ) {
                                            echo esc_attr( stripslashes( $options['global']->register_username['label'] ) );
                                        } ?>"  
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <?php esc_html_e('Email', 'eventprime-event-calendar-management'); ?>
                                </td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>
                                    <input type="text" name="register_email[label]" id="register_email_label" value="<?php
                                        if (isset($options['global']->register_email['label'])) {
                                            echo esc_attr( stripslashes( $options['global']->register_email['label'] ) );
                                        }?>" 
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <?php esc_html_e( 'Password', 'eventprime-event-calendar-management' ); ?>
                                </td>
                                <td>
                                    <input type="checkbox" name="register_password[show]" value="1" id="register_password_show" data-field="register_password" data-property="show" <?php
                                        if (isset($options['global']->register_password['show']) && $options['global']->register_password['show'] == 1) {
                                            echo 'checked="checked"';
                                        }?> 
                                    />
                                </td>
                                <td>
                                    <input type="checkbox" name="register_password[mandatory]" value="1" id="register_password_mandatory" data-field="register_password" data-property="mandatory" <?php
                                        if (isset($options['global']->register_password['mandatory']) && $options['global']->register_password['mandatory'] == 1) {
                                            echo 'checked="checked"';
                                        }?> 
                                    />
                                </td>
                                <td>
                                    <input type="text" name="register_password[label]" id="register_password_label" value="<?php
                                        if (isset($options['global']->register_password['label'])) {
                                            echo esc_attr( stripslashes( $options['global']->register_password['label'] ) );
                                        }?>" 
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <?php esc_html_e( 'Repeat Password', 'eventprime-event-calendar-management' ); ?>
                                </td>
                                <td>
                                    <input type="checkbox" name="register_repeat_password[show]" value="1" id="register_repeat_password_show" data-field="register_repeat_password" data-property="show" <?php if (isset($options['global']->register_repeat_password['show']) && $options['global']->register_repeat_password['show'] == 1) {
                                        echo 'checked="checked"';
                                    } ?> 
                                />
                                </td>
                                <td>
                                   <input type="checkbox" name="register_repeat_password[mandatory]" value="1" id="register_repeat_password_mandatory" data-field="register_repeat_password" data-property="mandatory" <?php if (isset($options['global']->register_repeat_password['mandatory']) && $options['global']->register_repeat_password['mandatory'] == 1) {
                                       echo 'checked="checked"';
                                   } ?> />
                                </td>
                                <td>
                                    <input type="text" name="register_repeat_password[label]" id="register_repeat_password_label" value="<?php if (isset($options['global']->register_repeat_password['label'])) {
                                       echo esc_attr( stripslashes( $options['global']->register_repeat_password['label'] ) );
                                        } ?>" 
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <?php esc_html_e( 'Date of Birth', 'eventprime-event-calendar-management' ); ?>
                                </td>
                                <td>
                                    <input type="checkbox" name="register_dob[show]" value="1" id="register_dob_show" data-field="register_dob" data-property="show" <?php
                                        if (isset($options['global']->register_dob['show']) && $options['global']->register_dob['show'] == 1) {
                                            echo 'checked="checked"';
                                        }?> 
                                    />
                                </td>
                                <td>
                                    <input type="checkbox" name="register_dob[mandatory]" value="1" id="register_dob_mandatory" data-field="register_dob" data-property="mandatory" <?php
                                        if (isset($options['global']->register_dob['mandatory']) && $options['global']->register_dob['mandatory'] == 1) {
                                            echo 'checked="checked"';
                                        }?> 
                                    />
                                </td>
                                <td>
                                    <input type="text" name="register_dob[label]" id="register_dob_label" value="<?php
                                        if (isset($options['global']->register_dob['label'])) {
                                            echo esc_attr( stripslashes( $options['global']->register_dob['label'] ) );
                                        }?>" 
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <?php esc_html_e( 'Phone Number', 'eventprime-event-calendar-management' ); ?>
                                </td>
                                <td>
                                    <input type="checkbox" name="register_phone[show]" value="1" id="register_phone_show" data-field="register_phone" data-property="show" <?php
                                        if (isset($options['global']->register_phone['show']) && $options['global']->register_phone['show'] == 1) {
                                            echo 'checked="checked"';
                                        }?> 
                                    />
                                </td>
                                <td>
                                    <input type="checkbox" name="register_phone[mandatory]" value="1" id="register_phone_mandatory" data-field="register_phone" data-property="mandatory" <?php
                                        if (isset($options['global']->register_phone['mandatory']) && $options['global']->register_phone['mandatory'] == 1) {
                                            echo 'checked="checked"';
                                        }?>
                                    />
                                </td>
                                <td>
                                    <input type="text" name="register_phone[label]" id="register_phone_label" value="<?php
                                        if (isset($options['global']->register_phone['label'])) {
                                            echo esc_attr( stripslashes( $options['global']->register_phone['label'] ) );
                                        }?>" 
                                    />
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <?php esc_html_e( 'Timezone', 'eventprime-event-calendar-management' ); ?>
                                </td>
                                <td>
                                    <input type="checkbox" name="register_timezone[show]" value="1" id="register_timezone_show" data-field="register_timezone" data-property="show" <?php
                                        if (isset($options['global']->register_timezone['show']) && $options['global']->register_timezone['show'] == 1) {
                                            echo 'checked="checked"';
                                        }?> 
                                    />
                                </td>
                                <td>
                                    <input type="checkbox" name="register_timezone[mandatory]" value="1" id="register_timezone_mandatory" data-field="register_timezone" data-property="mandatory" <?php
                                        if (isset($options['global']->register_timezone['mandatory']) && $options['global']->register_timezone['mandatory'] == 1) {
                                            echo 'checked="checked"';
                                        }?> 
                                    />
                                </td>
                                <td>
                                    <input type="text" name="register_timezone[label]" id="register_timezone_label" value="<?php
                                        if (isset($options['global']->register_timezone['label'])) {
                                            echo esc_attr( stripslashes( $options['global']->register_timezone['label'] ) );
                                        }?>" 
                                    />
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
</div>