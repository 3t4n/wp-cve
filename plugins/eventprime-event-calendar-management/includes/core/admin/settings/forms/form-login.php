<div class="ep-login-tab-content">
    <h2><?php esc_html_e( 'Login Form Settings', 'eventprime-event-calendar-management' );?></h2>
    <input type="hidden" name="em_setting_type" value="login_form_settings">
    <p>
        <?php $back_url = remove_query_arg( 'section' ) ;?>
        <a href="<?php echo esc_url( $back_url );?>">
            <- <?php esc_html_e( 'Back', 'eventprime-event-calendar-management' );?>
        </a>
    </p>
</div>
<table class="form-table">
    <tbody>
        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="login_id_field">
                    <?php esc_html_e( 'Accept Login Using', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <select name="login_id_field" id="login_id_field" class="ep-form-control">
                    <option value="username" <?php if( $options['global']->login_id_field == 'username' ) { echo 'selected="selected"';} ?>>
                        <?php esc_html_e( 'Username Only', 'eventprime-event-calendar-management' );?>
                    </option>
                    <option value="email" <?php if( $options['global']->login_id_field == 'email' ) { echo 'selected="selected"';} ?>>
                        <?php esc_html_e( 'Email Only', 'eventprime-event-calendar-management' );?>
                    </option>
                    <option value="email_username" <?php if( $options['global']->login_id_field == 'email_username' ) { echo 'selected="selected"';} ?>>
                        <?php esc_html_e( 'Either Username or Email', 'eventprime-event-calendar-management' );?>
                    </option>
                </select>
                <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e( 'Choose which value you would like to accept as login identity.', 'eventprime-event-calendar-management' );?></div>
            </td>
        </tr>

        <tr id="login_id_field_label_setting" class="ep-login-id-field-option" <?php if( empty( $options['global']->login_id_field ) ){ echo 'style="display:none;"'; }?>>
            <th scope="row" class="titledesc">
                <label for="login_id_field_label_setting">
                    <?php esc_html_e( 'ID Field Label', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <input type="text" name="login_id_field_label_setting" id="login_id_field_label_setting" class="ep-form-control" placeholder="<?php echo esc_attr( 'ID Field Label' );?>" value="<?php echo esc_attr( stripslashes( $options['global']->login_id_field_label_setting ) );?>" />
            </td>
        </tr>

        <tr>
            <th scope="row" class="titledesc">
                <label for="login_password_label">
                    <?php esc_html_e( 'Password Field Label', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <input type="text" name="login_password_label" id="login_password_label" class="ep-form-control" placeholder="<?php echo esc_attr( 'Password Field Label' );?>" value="<?php echo esc_attr( stripslashes( $options['global']->login_password_label ) );?>"  />
                <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e( 'You can set the label of the password field here.', 'eventprime-event-calendar-management' );?></div>
            </td>
        </tr>

        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="login_show_rememberme">
                    <?php esc_html_e( "Show Remember Me", 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <label class="ep-toggle-btn">
                    <input type="checkbox" name="login_show_rememberme" id="login_show_rememberme" class="" <?php if( $options['global']->login_show_rememberme == 1 ) { echo 'checked="checked"';} ?> />
                    <span class="ep-toogle-slider round"></span>
                </label>
                <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e( 'If enabled, a \'Remember me\' checkbox will appear below the login fields. Clicking on it, will allow your website to directly login user when revisiting in the future. This feature relies on storing cookie on user\'s end.', 'eventprime-event-calendar-management' );?></div>
            </td>
        </tr>
        <tr id="login_rememberme_setting"  <?php if( ! isset( $options['global']->login_show_rememberme ) || $options['global']->login_show_rememberme != 1 ){ echo 'style="display:none;"'; }?>>
            <th scope="row" class="titledesc">
                <label for="login_show_rememberme_label">
                    <?php esc_html_e( 'Remember Me Label', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <input type="text" name="login_show_rememberme_label" id="login_show_rememberme_label" class="ep-form-control" placeholder="<?php echo esc_attr( 'Remember Me Label' );?>" value="<?php echo esc_attr( stripslashes( $options['global']->login_show_rememberme_label ) );?>" />
            </td>
        </tr>

        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="login_show_forgotpassword">
                    <?php esc_html_e( "Show Forgot Password", 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <label class="ep-toggle-btn">
                    <input type="checkbox" name="login_show_forgotpassword" id="login_show_forgotpassword" class="" <?php if( $options['global']->login_show_forgotpassword == 1 ) { echo 'checked="checked"';} ?> />
                    <span class="ep-toogle-slider round"></span>
                </label>
                <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e( 'If enabled, \'Forgot password?\' button will appear below the form, allowing users to reset their passwords.', 'eventprime-event-calendar-management' );?></div>
            </td>
        </tr>
        <tr id="login_forgotpassword_setting"  <?php if( ! isset( $options['global']->login_show_forgotpassword ) || $options['global']->login_show_forgotpassword != 1 ){ echo 'style="display:none;"'; }?>>
            <th scope="row" class="titledesc">
                <label for="login_show_forgotpassword_label">
                    <?php esc_html_e( 'Forgot Password Label', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <input type="text" name="login_show_forgotpassword_label" id="login_show_forgotpassword_label" class="ep-form-control" placeholder="<?php echo esc_attr( 'Forgot Password Label' );?>" value="<?php echo esc_attr( stripslashes( $options['global']->login_show_forgotpassword_label ) );?>" />
            </td>
        </tr>
        
        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="login_google_recaptcha">
                    <?php esc_html_e( "Enable reCAPTCHA", 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <label class="ep-toggle-btn">
                    <input type="checkbox" name="login_google_recaptcha" id="login_google_recaptcha" class="" <?php if( $options['global']->login_google_recaptcha == 1 ) { echo 'checked="checked"';} ?> <?php if(empty(ep_get_global_settings('google_recaptcha'))){echo 'readyonly disabled=""';}?>/>
                    <span class="ep-toogle-slider round"></span>
                </label>
                <?php if(empty(ep_get_global_settings('google_recaptcha'))){?>
                <div class="ep-help-tip-info ep-text-danger"><?php esc_html_e( 'reCAPTCHA not enabled. To enable reCAPTCHA go to Settings > General > Third-Party.', 'eventprime-event-calendar-management' );?></div>
                <?php }?>
                <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e( 'If enabled, users will have to verify reCAPTCHA before they are allowed to log in.', 'eventprime-event-calendar-management' );?></div>
            </td>
        </tr>

        <tr>
            <th scope="row" class="titledesc">
                <label for="login_heading_text">
                    <?php esc_html_e( 'Main Heading', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <input type="text" name="login_heading_text" id="login_heading_text" class="ep-form-control" placeholder="<?php echo esc_attr( 'Heading Text' );?>" value="<?php echo esc_attr( stripslashes( $options['global']->login_heading_text ) );?>" />
                <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e( 'Text for large heading above the login form.', 'eventprime-event-calendar-management' );?></div>
            </td>
        </tr>

        <tr>
            <th scope="row" class="titledesc">
                <label for="login_subheading_text">
                    <?php esc_html_e( 'Secondary Heading', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <input type="text" name="login_subheading_text" id="login_subheading_text" class="ep-form-control" placeholder="<?php echo esc_attr( 'Sub Heading Text' );?>" value="<?php echo esc_attr( stripslashes( $options['global']->login_subheading_text ) );?>" />
                <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e( 'Secondary heading appears just below the main heading.', 'eventprime-event-calendar-management' );?></div>
            </td>
        </tr>

        <tr>
            <th scope="row" class="titledesc">
                <label for="login_button_label">
                    <?php esc_html_e( 'Button Label', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <input type="text" name="login_button_label" id="login_button_label" class="ep-form-control" placeholder="<?php echo esc_attr( 'Login Button Label' );?>" value="<?php echo esc_attr( stripslashes( $options['global']->login_button_label ) );?>" />
                <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e( 'Label of the login button on the form.', 'eventprime-event-calendar-management' );?></div>
            </td>
        </tr>

        <tr>
            <th scope="row" class="titledesc">
                <label for="login_redirect_after_login">
                    <?php esc_html_e( 'Redirect After Login', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <select name="login_redirect_after_login" id="login_redirect_after_login" class="ep-form-control">
                    <option value=""><?php esc_html_e( 'Select Page', 'eventprime-event-calendar-management' );?></option>
                    <?php foreach( ep_get_all_pages_list() as $page_id => $page_title ){?>
                        <option value="<?php echo esc_attr( $page_id );?>" <?php if( $options['global']->login_redirect_after_login == $page_id ) { echo 'selected="selected"'; } ?>>
                            <?php echo $page_title;?>
                        </option><?php
                    }?>
                </select>
                <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e( 'Choose where to send the user after successful login.', 'eventprime-event-calendar-management' );?></div>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="login_show_registerlink">
                    <?php esc_html_e( "Show Registration Link", 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <label class="ep-toggle-btn">
                    <input type="checkbox" name="login_show_registerlink" id="login_show_registerlink" value="1" class="" <?php if( $options['global']->login_show_registerlink == 1 ) { echo 'checked="checked"';} ?> />
                    <span class="ep-toogle-slider round"></span>
                </label>
                <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e( 'If enabled, users will see a link on the login form, clicking on which will allow them to register as new users. This is helpful when unregistered users access the login form.', 'eventprime-event-calendar-management' );?></div>
            </td>
        </tr>
        <tr id="login_registerlink_setting"  <?php if( ! isset( $options['global']->login_show_registerlink ) || $options['global']->login_show_registerlink != 1 ){ echo 'style="display:none;"'; }?>>
            <th scope="row" class="titledesc">
                <label for="login_show_registerlink_label">
                    <?php esc_html_e( 'Register Link Text', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <input type="text" name="login_show_registerlink_label" id="login_show_registerlink_label" class="ep-form-control" placeholder="<?php echo esc_attr( 'Register Link Text' );?>" value="<?php echo esc_attr( stripslashes( $options['global']->login_show_registerlink_label ) );?>" />
            </td>
        </tr>
    </tbody>
</table>