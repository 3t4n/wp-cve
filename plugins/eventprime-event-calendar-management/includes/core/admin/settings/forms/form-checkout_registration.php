<div class="ep-registration-tab-content">
    <h2><?php esc_html_e( 'Checkout Registration Form Settings', 'eventprime-event-calendar-management' );?></h2>
    <input type="hidden" name="em_setting_type" value="checkout_registration_form_settings">
    <p>
        <?php $back_url = remove_query_arg( 'section' ) ;?>
        <a href="<?php echo esc_url( $back_url );?>">
            <- <?php esc_html_e( 'Back', 'eventprime-event-calendar-management' );?>
        </a>
    </p>
</div>
<div class="ep-settings-form-list">
    <table class="ep-setting-table-main">
        <tbody>
            <tr>
                <td class="ep-setting-table-wrap" colspan="2">
                    <table class="ep-setting-table ep-setting-table-wide form-table widefat" cellspacing="0" id="ep_settings_register_form_fields">
                        <thead>
                            <tr>
                                <th>
                                    <?php esc_html_e( 'Field', 'eventprime-event-calendar-management' );?>
                                </th>
                                
                                <th>
                                    <?php esc_html_e( 'Label', 'eventprime-event-calendar-management' );?>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <?php esc_html_e( 'First Name', 'eventprime-event-calendar-management' );?>
                                </td>
                                
                                <td>
                                    <input type="text" name="checkout_register_fname[label]" id="checkout_register_fname_label" value="<?php if( isset( $options['global']->checkout_register_fname['label'] ) && !empty($options['global']->checkout_register_fname['label'])) { echo $options['global']->checkout_register_fname['label']; } else{ esc_html_e('First Name', 'eventprime-event-calendar-management' );} ?>"  />
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <?php esc_html_e( 'Last Name', 'eventprime-event-calendar-management' );?>
                                </td>
                                
                                <td>
                                    <input type="text" name="checkout_register_lname[label]" id="checkout_register_lname_label" value="<?php if( isset( $options['global']->checkout_register_lname['label'] ) && !empty($options['global']->checkout_register_lname['label'])) { echo $options['global']->checkout_register_lname['label']; } else{ esc_html_e('Last Name', 'eventprime-event-calendar-management' );}?>"  />
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <?php esc_html_e( 'Username', 'eventprime-event-calendar-management' );?>
                                </td>
                                
                                <td>
                                    <input type="text" name="checkout_register_username[label]" id="checkout_register_username_label" value="<?php if( isset( $options['global']->checkout_register_username['label'] ) && !empty($options['global']->checkout_register_username['label'])) { echo $options['global']->checkout_register_username['label']; } else{ esc_html_e('Username', 'eventprime-event-calendar-management' );}?>"  />
                                </td>
                            </tr>
                            <tr>
                                <td>
                                <?php esc_html_e( 'Email', 'eventprime-event-calendar-management' );?>
                                </td>
                                
                                <td>
                                    <input type="text" name="checkout_register_email[label]" id="checkout_register_email_label" value="<?php if( isset( $options['global']->checkout_register_email['label'] ) && !empty($options['global']->checkout_register_email['label'])) { echo $options['global']->checkout_register_email['label']; } else{ esc_html_e('Email', 'eventprime-event-calendar-management' );}?>" />
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <?php esc_html_e( 'Password', 'eventprime-event-calendar-management' );?>
                                </td>
                                
                                <td>
                                    <input type="text" name="checkout_register_password[label]" id="checkout_register_password_label" value="<?php if( isset( $options['global']->checkout_register_password['label'] ) && !empty($options['global']->checkout_register_password['label'])) { echo $options['global']->checkout_register_password['label']; } else{ esc_html_e('Password', 'eventprime-event-calendar-management' );}?>" />
                                </td>
                            </tr>
                        </tbody>
                    </table> 
                </td>
            </tr>
        </tbody>
    </table> 

    <table class='form-table'>
        <tbody>
            <tr valign="top">
                <th scope="row" class="titledesc">
                    <label for="checkout_reg_google_recaptcha">
                        <?php esc_html_e( "Enable reCAPTCHA ", 'eventprime-event-calendar-management' );?>
                    </label>
                </th>
                <td class="forminp forminp-text">
                    <label class="ep-toggle-btn">
                        <input type="checkbox" name="checkout_reg_google_recaptcha" id="checkout_reg_google_recaptcha" class="" <?php if( $options['global']->checkout_reg_google_recaptcha == 1 ) { echo 'checked="checked"';} ?> <?php if(empty(ep_get_global_settings('google_recaptcha'))){echo 'readyonly disabled=""';}?>/>
                        <span class="ep-toogle-slider round"></span>
                    </label>
                    <?php if( empty( ep_get_global_settings('google_recaptcha') ) ) {?>
                        <div class="ep-help-tip-info ep-text-danger"><?php esc_html_e( 'reCAPTCHA not enabled. To enable reCAPTCHA go to Settings > General > Third-Party.', 'eventprime-event-calendar-management' );?></div>
                    <?php }?>
                    <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e( 'If enabled, users will have to verify reCAPTCHA before they are allowed to submit the form.', 'eventprime-event-calendar-management' );?></div>
                </td>
            </tr>
        </tbody>
    </table>
</div>