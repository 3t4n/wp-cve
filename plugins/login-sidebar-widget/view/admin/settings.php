<h1 class="wp-heading-inline ap-header"><?php _e('Login Widget Settings', 'login-sidebar-widget');?></h1>
<table width="100%" class="ap-table">
  <tr>
    <td colspan="2">
    <div class="ap-tabs">
        <div class="ap-tab"><?php _e('General', 'login-sidebar-widget');?></div>
        <div class="ap-tab"><?php _e('Security', 'login-sidebar-widget');?></div>
        <div class="ap-tab"><?php _e('Recaptcha', 'login-sidebar-widget');?></div>
        <div class="ap-tab ap-tab-100"><?php _e('Error Message', 'login-sidebar-widget');?></div>
        <div class="ap-tab"><?php _e('Styling', 'login-sidebar-widget');?></div>
        <div class="ap-tab ap-tab-100"><?php _e('Email Settings', 'login-sidebar-widget');?></div>
        <div class="ap-tab"><?php _e('Shortcode', 'login-sidebar-widget');?></div>
        <?php do_action('lwws_custom_settings_tab');?>
    </div>

    <div class="ap-tabs-content">
        <div class="ap-tab-content">
        <table width="100%">
          <tr>
            <td colspan="2"><h3><?php _e('General', 'login-sidebar-widget');?></h3></td>
          </tr>
          <tr>
            <td width="300" valign="top"><strong><?php _e('Login Redirect Page', 'login-sidebar-widget');?></strong></td>
            <td><?php
$args = array(
    'depth' => 0,
    'selected' => $redirect_page,
    'echo' => 1,
    'show_option_none' => '-',
    'id' => 'redirect_page',
    'name' => 'redirect_page',
    'class' => 'widefat',
);
wp_dropdown_pages($args);
?>
				<br>
				<?php _e('Or', 'login-sidebar-widget');?>
                <br>
				<?php Form_Class::form_input('text', 'redirect_page_url', '', esc_url($redirect_page_url), 'widefat', '', '', '', '', '', false, 'URL');?>
                </td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td><strong><?php _e('Logout Redirect Page', 'login-sidebar-widget');?></strong></td>
             <td><?php
$args1 = array(
    'depth' => 0,
    'selected' => $logout_redirect_page,
    'echo' => 1,
    'show_option_none' => '-',
    'id' => 'logout_redirect_page',
    'name' => 'logout_redirect_page',
    'class' => 'widefat',
);
wp_dropdown_pages($args1);
?></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td><strong><?php _e('Link in Username', 'login-sidebar-widget');?></strong></td>
            <td><?php
$args2 = array(
    'depth' => 0,
    'selected' => $link_in_username,
    'echo' => 1,
    'show_option_none' => '-',
    'id' => 'link_in_username',
    'name' => 'link_in_username',
    'class' => 'widefat',
);
wp_dropdown_pages($args2);
?></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td><strong><?php _e('Add Remember Me', 'login-sidebar-widget');?></strong></td>
            <td>
            <?php
$login_ap_rem_status = ($login_ap_rem == 'Yes' ? true : false);
Form_Class::form_checkbox('login_ap_rem', '', "Yes", '', '', '', $login_ap_rem_status);
?><?php _e('Check to Enable', 'login-sidebar-widget');?>
            </td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td valign="top"><strong><?php _e('Forgot Password Link', 'login-sidebar-widget');?></strong></td>
            <td>
                <?php
$args3 = array(
    'depth' => 0,
    'selected' => $login_ap_forgot_pass_link,
    'echo' => 1,
    'show_option_none' => '-',
    'id' => 'login_ap_forgot_pass_link',
    'name' => 'login_ap_forgot_pass_link',
    'class' => 'widefat',
);
wp_dropdown_pages($args3);
?>
                <br>
                <?php _e('Or', 'login-sidebar-widget');?>
                <br>
                <?php Form_Class::form_input('text', 'login_ap_forgot_pass_page_url', '', esc_url($login_ap_forgot_pass_page_url), 'widefat', '', '', '', '', '', false, 'URL');?>
                <i><?php _e('Leave blank to not include the link', 'login-sidebar-widget');?></i>
                </td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td valign="top"><strong><?php _e('Register Link', 'login-sidebar-widget');?></strong></td>
            <td>
                <?php
$args4 = array(
    'depth' => 0,
    'selected' => $login_ap_register_link,
    'echo' => 1,
    'show_option_none' => '-',
    'id' => 'login_ap_register_link',
    'name' => 'login_ap_register_link',
    'class' => 'widefat',
);
wp_dropdown_pages($args4);
?>
                <br>
                <?php _e('Or', 'login-sidebar-widget');?>
                <br>
                <?php Form_Class::form_input('text', 'login_ap_register_page_url', '', esc_url($login_ap_register_page_url), 'widefat', '', '', '', '', '', false, 'URL');?>
                <i><?php _e('Leave blank to not include the link', 'login-sidebar-widget');?></i>
                </td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td><?php Form_Class::form_input('submit', 'submit', '', __('Save', 'login-sidebar-widget'), 'button button-primary button-large button-ap-large', '', '', '', '', '', false, '');?>
            </td>
          </tr>
          </table>
        </div>
        <div class="ap-tab-content">
        <table width="100%">
        <tr>
        <td colspan="2"><h3><?php _e('Security', 'login-sidebar-widget');?></h3></td>
        </tr>
        <tr>
        <td width="300"><strong><?php _e('Captcha on Admin Login', 'login-sidebar-widget');?></strong></td>
        <td>
        <label>
        <?php
$captcha_on_admin_login_status = ($captcha_on_admin_login == 'Yes' ? true : false);
Form_Class::form_checkbox('captcha_on_admin_login', '', "Yes", '', '', '', $captcha_on_admin_login_status);
?>
        <i><?php _e('Check to enable captcha on admin login form', 'login-sidebar-widget');?></i>
        </label>
        </td>
        </tr>
        <tr>
          <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
        <td><strong><?php _e('Captcha on User Login', 'login-sidebar-widget');?></strong></td>
        <td>
        <label>
        <?php
$captcha_on_user_login_status = ($captcha_on_user_login == 'Yes' ? true : false);
Form_Class::form_checkbox('captcha_on_user_login', '', "Yes", '', '', '', $captcha_on_user_login_status);
?>
        <i><?php _e('Check to enable captcha on user login form', 'login-sidebar-widget');?></i>
        </label>
        </td>
        </tr>
        <tr>
          <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
          <td><strong><?php _e('Captcha Type', 'login-sidebar-widget');?></strong></td>
          <td>
          <?php
$captcha_in_lsw_options = ($captcha_type_in_lsw == 'recaptcha' ? '<option value="default">Default</option><option value="recaptcha" selected>Recaptcha</option>' : '<option value="default" selected>Default</option><option value="recaptcha">Recaptcha</option>');
Form_Class::form_select('captcha_type_in_lsw', '', $captcha_in_lsw_options);
?><i><?php _e('If recaptcha is selected then set it up in Recaptcha tab.', 'login-sidebar-widget');?></i></td>
        </tr>
        <tr>
          <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
        <td><strong><?php _e('Enable Nonce Validation Login', 'login-sidebar-widget');?></strong></td>
        <td>
        <label>
        <?php
$nonce_check_on_login_status = ($nonce_check_on_login == 'Yes' ? true : false);
Form_Class::form_checkbox('nonce_check_on_login', '', "Yes", '', '', '', $nonce_check_on_login_status);
?>
        <i><?php _e('If enabled then login from submit will be restricted, if user stays on the login page for a long time and then submit the form. The error will occur because Nonce validation code will expire after a certain period of time.', 'login-sidebar-widget');?></i>
        </label>
        </td>
        </tr>
        <tr>
        <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
        <td colspan="2">
        <div style="border:1px solid #AEAE00; width:98%; background-color:#FFF; margin:0px auto; padding:10px;">
        Click <a href="admin.php?page=login_log_ap">here</a> to check the user <strong>Login Log</strong>. Use <strong><a href="https://www.aviplugins.com/fb-login-widget-pro/" target="_blank">PRO</a></strong> version that has added security with <strong>Blocking IP</strong> after 5 wrong login attempts. <strong>Blocked IPs</strong> can be <strong>Whitelisted</strong> from admin panel or the <strong>Block</strong> gets automatically removed after <strong>1 Day</strong>.
        </div>
        </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
        <tr>
            <td>&nbsp;</td>
            <td><?php Form_Class::form_input('submit', 'submit', '', __('Save', 'login-sidebar-widget'), 'button button-primary button-large button-ap-large', '', '', '', '', '', false, '');?>
            </td>
          </tr>
        </table>
        </div>
        <div class="ap-tab-content">
          <table width="100%">
            <tr>
              <td valign="top" colspan="2"><h3><?php _e('Google reCAPTCHA Setup', 'login-sidebar-widget');?></h3></td>
            </tr>
            <tr>
              <td valign="top" colspan="2">&nbsp;</td>
            </tr>
            <tr>
              <td width="300"><strong><?php _e('Public Key', 'login-sidebar-widget');?></strong></td>
              <td><input type="text" name="lsw_google_recaptcha_public_key" value="<?php echo $lsw_google_recaptcha_public_key; ?>" class="widefat"></td>
            </tr>
            <tr>
              <td valign="top" colspan="2">&nbsp;</td>
            </tr>
            <tr>
              <td><strong><?php _e('Private Key', 'login-sidebar-widget');?></strong></td>
              <td><input type="text" name="lsw_google_recaptcha_private_key" value="<?php echo $lsw_google_recaptcha_private_key; ?>" class="widefat"></td>
            </tr>
              <tr>
              <td>&nbsp;</td>
              <td><p>If you are using <strong>Google Recaptcha</strong> for security please enter <strong>Public and Private Keys</strong>. You can get the Keys from <a href="https://www.google.com/recaptcha/admin" target="_blank">https://www.google.com/recaptcha/admin</a></p></td>
            </tr>
              <tr>
              <td valign="top">&nbsp;</td>
              <td><input type="submit" name="submit" value="<?php _e('Save', 'login-sidebar-widget');?>" class="button button-primary button-large button-ap-large" /></td>
            </tr>
            </table>
          </div>
        <div class="ap-tab-content">
        <table width="100%">
          <tr>
            <td colspan="2"><h3><?php _e('Error Message', 'login-sidebar-widget');?></h3></td>
          </tr>
          <tr>
            <td valign="top" width="300"><strong><?php _e('Invalid Username Message', 'login-sidebar-widget');?></strong></td>
            <td><?php Form_Class::form_input('text', 'lap_invalid_username', '', $lap_invalid_username, 'widefat', '', '', '', '', '', false, __('Error: Invalid Username', 'login-sidebar-widget'));?>
            <i><?php _e('Error message for wrong Username', 'login-sidebar-widget');?></i></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td valign="top"><strong><?php _e('Invalid Email Message', 'login-sidebar-widget');?></strong></td>
            <td><?php Form_Class::form_input('text', 'lap_invalid_email', '', $lap_invalid_email, 'widefat', '', '', '', '', '', false, __('Error: Invalid email address', 'login-sidebar-widget'));?>
            <i><?php _e('Error message for wrong Email address', 'login-sidebar-widget');?></i></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td valign="top"><strong><?php _e('Invalid Password Message', 'login-sidebar-widget');?></strong></td>
            <td><?php Form_Class::form_input('text', 'lap_invalid_password', '', $lap_invalid_password, 'widefat', '', '', '', '', '', false, __('Error: Invalid Username & Password', 'login-sidebar-widget'));?>
            <i><?php _e('Error message for wrong Password', 'login-sidebar-widget');?></i></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td><?php Form_Class::form_input('submit', 'submit', '', __('Save', 'login-sidebar-widget'), 'button button-primary button-large button-ap-large', '', '', '', '', '', false, '');?>
            </td>
          </tr>
          </table>
        </div>
        <div class="ap-tab-content">
        <table width="100%">
           <tr>
                <td colspan="2"><h3><?php _e('Styling', 'login-sidebar-widget');?></h3></td>
              </tr>
           <tr>
                <td valign="top" width="300"><strong> <?php _e('Add Custom CSS Styles', 'login-sidebar-widget');?></strong></td>
                <td>
					<?php Form_Class::form_textarea('custom_style_ap', '', $custom_style_ap, 'widefat', '', '', '', '', '', '', '', 'height:200px;');?>

                </td>
              </tr>
              <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
              <tr>
            <td>&nbsp;</td>
            <td><?php Form_Class::form_input('submit', 'submit', '', __('Save', 'login-sidebar-widget'), 'button button-primary button-large button-ap-large', '', '', '', '', '', false, '');?>
            </td>
          </tr>
          </table>
        </div>
        <div class="ap-tab-content">
        <table width="100%">
        <tr>
        <td colspan="2"><h3><?php _e('Email Settings', 'login-sidebar-widget');?></h3></td>
        </tr>
        <tr>
        <td valign="top" width="300"><strong><?php _e('From Email', 'login-sidebar-widget');?></strong></td>
        <td><?php Form_Class::form_input('text', 'login_sidebar_widget_from_email', '', $login_sidebar_widget_from_email, 'widefat', '', '', '', '', '', false, 'no-reply@example.com');?>
        <i><?php _e('This will be the from email address in the emails. This will make sure that the emails do not go to a spam folder.', 'login-sidebar-widget');?></i>
        </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
        <tr>
        <td><strong><?php _e('Reset Password Link Email Subject', 'login-sidebar-widget');?></strong></td>
        <td>
        <?php Form_Class::form_input('text', 'forgot_password_link_mail_subject', '', $forgot_password_link_mail_subject, 'widefat', '', '', '', '', '', false, '');?>
        </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
        <tr>
        <td valign="top"><strong><?php _e('Reset Password Link Email Body', 'login-sidebar-widget');?></strong>
        <p><i><?php _e('This email will fire when a user request for a new password.', 'login-sidebar-widget');?></i></p>
        </td>
        <td><?php Form_Class::form_textarea('forgot_password_link_mail_body', '', $forgot_password_link_mail_body, '', 'widefat', '', '', '', '', '', '', 'height:200px; width:100%;');?>
        <p>Shortcodes: #site_url#, #user_name#, #resetlink#</p>
        </td>
        </tr>
        <tr>
        <td><strong><?php _e('New Password Email Subject', 'login-sidebar-widget');?></strong></td>
        <td>
        <?php Form_Class::form_input('text', 'new_password_mail_subject', '', $new_password_mail_subject, 'widefat', '', '', '', '', '', false, '');?>
        </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
        <tr>
        <td valign="top"><strong><?php _e('New Password Email Subject Body', 'login-sidebar-widget');?></strong>
        <p><i><?php _e('This email will fire when a user clicks on the password reset link provided in the above email.', 'login-sidebar-widget');?></i></p>
        </td>
        <td><?php Form_Class::form_textarea('new_password_mail_body', '', $new_password_mail_body, 'widefat', '', '', '', '', '', '', '', 'height:200px;');?>
        <p>Shortcodes: #site_url#, #user_name#, #user_password#</p>
        </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
        <tr>
            <td>&nbsp;</td>
            <td><?php Form_Class::form_input('submit', 'submit', '', __('Save', 'login-sidebar-widget'), 'button button-primary button-large button-ap-large', '', '', '', '', '', false, '');?>
            </td>
          </tr>
        </table>
        </div>
        <div class="ap-tab-content">
        <table width="100%">
         <tr>
    <td colspan="2"><h3><?php _e('Shortcode', 'login-sidebar-widget');?></h3></td>
  </tr>
          <tr>
            <td colspan="2">Use <span style="color:#000066;">[login_widget]</span> shortcode to display login form in post or page.<br />
             Example: <span style="color:#000066;">[login_widget title="Login Here"]</span></td>
          </tr>
          <tr>
            <td colspan="2">Use <span style="color:#000066;">[forgot_password]</span> shortcode to display forgot password form in post or page.<br />
             Example: <span style="color:#000066;">[forgot_password title="Forgot Password?"]</span></td>
          </tr>
        </table>
        </div>
        <?php do_action('lwws_custom_settings_tab_content');?>
    </div>
    </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>