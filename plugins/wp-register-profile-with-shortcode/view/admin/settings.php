<?php
Form_Class::form_open();
wp_nonce_field('register_widget_save_action', 'register_widget_save_action_field');
Form_Class::form_input('hidden', 'option', '', 'register_widget_save_settings');
wp_nonce_field('wprp_admin_action', 'wprp_58irt78');
?>
<table width="100%" border="0" class="ap-table">
<tr>
  <td><h3><?php _e('WP Register Profile With Shortcode Settings', 'wp-register-profile-with-shortcode');?></h3></td>
</tr>
<tr>
  <td><div class="ap-tabs">
      <div class="ap-tab">
        <?php _e('Form Fields', 'wp-register-profile-with-shortcode');?>
      </div>
      <div class="ap-tab">
        <?php _e('Other Settings', 'wp-register-profile-with-shortcode');?>
      </div>
      <div class="ap-tab">
        <?php _e('Message', 'wp-register-profile-with-shortcode');?>
      </div>
      <div class="ap-tab">
        <?php _e('Email Settings', 'wp-register-profile-with-shortcode');?>
      </div>
      <div class="ap-tab">
        <?php _e('Shortcodes', 'wp-register-profile-with-shortcode');?>
      </div>
      <?php do_action('wprp_custom_settings_tab');?>
    </div>
    <div class="ap-tabs-content">
      <div class="ap-tab-content">
        <table width="100%" border="0" class="field_form_table">
          <tr style="background-color:#FFFFFF;">
            <td colspan="4"><h3><?php _e('Enable / Disable Registration and Profile Form Fields', 'wp-register-profile-with-shortcode');?></h3></td>
          </tr>
          <tr style="background-color:#F1F1F1;">
            <td width="10%"><strong><?php _e('Field', 'wp-register-profile-with-shortcode');?></strong></td>
            <td width="10%"><strong><?php _e('Required', 'wp-register-profile-with-shortcode');?></strong></td>
            <td width="40%"><strong><?php _e('Show In Registration', 'wp-register-profile-with-shortcode');?></strong></td>
            <td width="40%"><strong><?php _e('Show In Profile', 'wp-register-profile-with-shortcode');?></strong></td>
          </tr>
          <tr style="background-color:#FFFFFF;">
            <td><strong><?php _e('Username', 'wp-register-profile-with-shortcode');?></strong></td>
            <td><?php Form_Class::form_checkbox('', '', '', '', '', '', true, true);?> <?php _e('Check to enable', 'wp-register-profile-with-shortcode');?></td>
            <td>
            <label>
            <?php
$username_in_registration_status = ($username_in_registration == 'Yes' ? true : false);
Form_Class::form_checkbox('username_in_registration', '', "Yes", '', '', '', $username_in_registration_status);
?><span><?php _e('If unchecked then <strong>User Email</strong> will be used as <strong>Username</strong>.', 'wp-register-profile-with-shortcode');?></span>
            </label>
            </td>
            <td><span><?php _e('This field cannot be updated.', 'wp-register-profile-with-shortcode');?></span></td>
          </tr>
          <tr style="background-color:#F1F1F1;">
            <td><strong><?php _e('User Email', 'wp-register-profile-with-shortcode');?></strong></td>
            <td><?php Form_Class::form_checkbox('', '', '', '', '', '', true, true);?> <?php _e('Check to enable', 'wp-register-profile-with-shortcode');?></td>
            <td><span><?php _e('This field is required and cannot be removed.', 'wp-register-profile-with-shortcode');?></span></td>
            <td><span><?php _e('This field can be updated.', 'wp-register-profile-with-shortcode');?></span></td>
          </tr>
          <tr style="background-color:#FFFFFF;">
            <td><strong><?php _e('Password Field', 'wp-register-profile-with-shortcode');?> </strong></td>
            <td><?php Form_Class::form_checkbox('', '', '', '', '', '', true, true);?> <?php _e('Check to enable', 'wp-register-profile-with-shortcode');?></td>
            <td>
			<label>
			<?php
$password_in_registration_status = ($password_in_registration == 'Yes' ? true : false);
Form_Class::form_checkbox('password_in_registration', '', "Yes", '', '', '', $password_in_registration_status);
?><span><?php _e('Check this to enable password field in registration form. Otherwise the password will be auto generated and Emailed to user.', 'wp-register-profile-with-shortcode');?></span>
            </label>
            </td>
            <td><span><?php _e('Password can be updated from update password page. Use this shortcode <strong>[rp_update_password]', 'wp-register-profile-with-shortcode');?></strong></span></td>
          </tr>
          <tr style="background-color:#F1F1F1;">
            <td><strong><?php _e('First Name', 'wp-register-profile-with-shortcode');?> </strong></td>
            <td>
			<label>
			<?php
$is_firstname_required_status = ($is_firstname_required == 'Yes' ? true : false);
Form_Class::form_checkbox('is_firstname_required', '', "Yes", '', '', '', $is_firstname_required_status);
?> <?php _e('Check to enable', 'wp-register-profile-with-shortcode');?>
            </label>
            </td>
            <td>
			<label>
			<?php
$firstname_in_registration_status = ($firstname_in_registration == 'Yes' ? true : false);
Form_Class::form_checkbox('firstname_in_registration', '', "Yes", '', '', '', $firstname_in_registration_status);
?><span><?php _e('Check this to enable first name in registration form.', 'wp-register-profile-with-shortcode');?></span>
            </label>
            </td>
            <td>
			<label>
			<?php
$firstname_in_profile_status = ($firstname_in_profile == 'Yes' ? true : false);
Form_Class::form_checkbox('firstname_in_profile', '', "Yes", '', '', '', $firstname_in_profile_status);
?><span>Check this to enable first name in profile form.</span>
            </label>
            </td>
          </tr>
          <tr style="background-color:#FFFFFF;">
            <td>
            <strong><?php _e('Last Name', 'wp-register-profile-with-shortcode');?> </strong></td>
            <td>
            <label>
			<?php
$is_lastname_required_status = ($is_lastname_required == 'Yes' ? true : false);
Form_Class::form_checkbox('is_lastname_required', '', "Yes", '', '', '', $is_lastname_required_status);
?>
            <?php _e('Check to enable', 'wp-register-profile-with-shortcode');?>
            </label>
            </td>
            <td>
			<label>
			<?php
$lastname_in_registration_status = ($lastname_in_registration == 'Yes' ? true : false);
Form_Class::form_checkbox('lastname_in_registration', '', "Yes", '', '', '', $lastname_in_registration_status);
?><span><?php _e('Check this to enable last name in registration form.', 'wp-register-profile-with-shortcode');?></span>
            </label>
            </td>
            <td>
			<label>
			<?php
$lastname_in_profile_status = ($lastname_in_profile == 'Yes' ? true : false);
Form_Class::form_checkbox('lastname_in_profile', '', "Yes", '', '', '', $lastname_in_profile_status);
?><span><?php _e('Check this to enable last name in profile form.', 'wp-register-profile-with-shortcode');?></span>
            </label>
            </td>
          </tr>
          <tr style="background-color:#F1F1F1;">
            <td><strong><?php _e('Display Name', 'wp-register-profile-with-shortcode');?> </strong></td>
            <td>
			<label>
			<?php
$is_displayname_required_status = ($is_displayname_required == 'Yes' ? true : false);
Form_Class::form_checkbox('is_displayname_required', '', "Yes", '', '', '', $is_displayname_required_status);
?> <?php _e('Check to enable', 'wp-register-profile-with-shortcode');?>
            </label>
            </td>
            <td>
			<label>
			<?php
$displayname_in_registration_status = ($displayname_in_registration == 'Yes' ? true : false);
Form_Class::form_checkbox('displayname_in_registration', '', "Yes", '', '', '', $displayname_in_registration_status);
?><span><?php _e('Check this to enable display name in registration form.', 'wp-register-profile-with-shortcode');?></span>
            </label>
            </td>
            <td>
			<label>
			<?php
$displayname_in_profile_status = ($displayname_in_profile == 'Yes' ? true : false);
Form_Class::form_checkbox('displayname_in_profile', '', "Yes", '', '', '', $displayname_in_profile_status);
?><span><?php _e('Check this to enable display name in profile form.', 'wp-register-profile-with-shortcode');?></span>
            </label>
            </td>
          </tr>
          <tr style="background-color:#FFFFFF;">
            <td><strong><?php _e('About User', 'wp-register-profile-with-shortcode');?> </strong></td>
            <td>
			<label>
			<?php
$is_userdescription_required_status = ($is_userdescription_required == 'Yes' ? true : false);
Form_Class::form_checkbox('is_userdescription_required', '', "Yes", '', '', '', $is_userdescription_required_status);
?> <?php _e('Check to enable', 'wp-register-profile-with-shortcode');?>
            </label>
            </td>
            <td>
			<label>
			<?php
$userdescription_in_registration_status = ($userdescription_in_registration == 'Yes' ? true : false);
Form_Class::form_checkbox('userdescription_in_registration', '', "Yes", '', '', '', $userdescription_in_registration_status);
?><span><?php _e('Check this to enable about user in registration form.', 'wp-register-profile-with-shortcode');?></span>
            </label>
            </td>
            <td>
			<label>
			<?php
$userdescription_in_profile_status = ($userdescription_in_profile == 'Yes' ? true : false);
Form_Class::form_checkbox('userdescription_in_profile', '', "Yes", '', '', '', $userdescription_in_profile_status);
?><span><?php _e('Check this to enable about user in profile form.', 'wp-register-profile-with-shortcode');?></span>
            </label>
            </td>
          </tr>
          <tr style="background-color:#F1F1F1;">
            <td><strong><?php _e('User Url', 'wp-register-profile-with-shortcode');?></strong></td>
            <td>
			<label>
			<?php
$is_userurl_required_status = ($is_userurl_required == 'Yes' ? true : false);
Form_Class::form_checkbox('is_userurl_required', '', "Yes", '', '', '', $is_userurl_required_status);
?> <?php _e('Check to enable', 'wp-register-profile-with-shortcode');?>
            </label>
            </td>
            <td>
			<label>
			<?php
$userurl_in_registration_status = ($userurl_in_registration == 'Yes' ? true : false);
Form_Class::form_checkbox('userurl_in_registration', '', "Yes", '', '', '', $userurl_in_registration_status);
?><span><?php _e('Check this to enable user url in registration form.', 'wp-register-profile-with-shortcode');?></span>
            </label>
            </td>
            <td>
			<label>
			<?php
$userurl_in_profile_status = ($userurl_in_profile == 'Yes' ? true : false);
Form_Class::form_checkbox('userurl_in_profile', '', "Yes", '', '', '', $userurl_in_profile_status);
?><span><?php _e('Check this to enable user url in profile form.', 'wp-register-profile-with-shortcode');?></span>
            </label>
            </td>
          </tr>
          <tr>
            <td colspan="4" align="center"><?php Form_Class::form_input('submit', 'submit', '', __('Save', 'wp-register-profile-with-shortcode'), 'button button-primary button-large button-ap-large', '', '', '', '', '', false, '');?></td>
          </tr>
          <tr style="background-color:#FFFFFF;">
            <td colspan="4">Use <a href="https://www.aviplugins.com/wp-register-profile-pro/" target="_blank">PRO</a> version to create additional custom fields with Sorting option using Drag & Drop</td>
          </tr>
        </table>
      </div>
      <div class="ap-tab-content">
        <table width="100%" border="0" class="ap-table">
          <tr>
            <td valign="top"><strong><?php _e('Thank You Page', 'wp-register-profile-with-shortcode');?></strong>
            <?php
$args = array(
    'depth' => 0,
    'selected' => $thank_you_page_after_registration_url,
    'echo' => 1,
    'show_option_none' => '--',
    'id' => 'thank_you_page_after_registration_url',
    'name' => 'thank_you_page_after_registration_url',
);
wp_dropdown_pages($args);
?>
              <i><?php _e('If selected user will be redirected to this page after successful registration', 'wp-register-profile-with-shortcode');?></i></td>
          </tr>
        </table>
        <table width="100%" border="0" class="ap-table">
          <tr>
            <td>
			<label>
			<?php _e('Make User Logged-In after successful registration', 'wp-register-profile-with-shortcode');?>
            <?php
$force_login_after_registration_status = ($force_login_after_registration == 'Yes' ? true : false);
Form_Class::form_checkbox('force_login_after_registration', '', "Yes", '', '', '', $force_login_after_registration_status);
?>
            </label>
              </td>
          </tr>
        </table>
        <table width="100%" border="0" class="ap-table">
          <tr>
            <td>
			<label>
			<?php _e('Use CAPTCHA in Registration Form', 'wp-register-profile-with-shortcode');?>
            <?php
$captcha_in_registration_status = ($captcha_in_registration == 'Yes' ? true : false);
Form_Class::form_checkbox('captcha_in_registration', '', "Yes", '', '', '', $captcha_in_registration_status);
?>
            </label>
              </td>
          </tr>
        </table>
        <table width="100%" border="0" class="ap-table">
          <tr>
            <td>
			<label>
			<?php _e('Use CAPTCHA in WordPress Default Registration Form', 'wp-register-profile-with-shortcode');?>
            <?php
$captcha_in_wordpress_default_registration_status = ($captcha_in_wordpress_default_registration == 'Yes' ? true : false);
Form_Class::form_checkbox('captcha_in_wordpress_default_registration', '', "Yes", '', '', '', $captcha_in_wordpress_default_registration_status);
?>
            </label>
              </td>
          </tr>
        </table>
        <table width="100%" border="0" class="ap-table">
          <tr>
            <td>
            <label>
            <strong><?php _e('Enable default WordPress registration form hooks', 'wp-register-profile-with-shortcode');?></strong>
            <?php
$default_registration_form_hooks_status = ($default_registration_form_hooks == 'Yes' ? true : false);
Form_Class::form_checkbox('default_registration_form_hooks', '', "Yes", '', '', '', $default_registration_form_hooks_status);
?>
            </label>
              <p>Check to <strong>Enable</strong> default WordPress registration form hooks. This will make the registration form compatible with other plugins. For example <strong>Enable</strong> this if you want to use CAPTCHA on registration, from another plugin. <strong>Disable</strong> this so that no other plugins can interfere with your registration process.</p></td>
          </tr>
        </table>
        <table width="100%" border="0" class="ap-table">
          <tr>
            <td>
            <label>
            <strong><?php _e('Enable Newsletter Subscription', 'wp-register-profile-with-shortcode');?></strong>
            <?php
$enable_cfws_newsletter_subscription_status = ($enable_cfws_newsletter_subscription == 'Yes' ? true : false);
Form_Class::form_checkbox('enable_cfws_newsletter_subscription', '', "Yes", '', '', '', $enable_cfws_newsletter_subscription_status);
?>
            </label>
              <p>Check to <strong>Enable</strong> Newsletter subscription at the time of Registration. To enable this feature you must Install <a href="https://wordpress.org/plugins/contact-form-with-shortcode/" target="_blank">Contact Form With Shortcode</a> plugin.</p></td>
          </tr>
        </table>
        <table width="100%" border="0">
        <tr>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td align="center"><?php Form_Class::form_input('submit', 'submit', '', __('Save', 'wp-register-profile-with-shortcode'), 'button button-primary button-large button-ap-large', '', '', '', '', '', false, '');?></td>
          </tr>
        </table>
      </div>
      <div class="ap-tab-content">
        <table width="100%" border="0">
          <tr>
            <td valign="top" width="300"><strong><?php _e('Success Message', 'wp-register-profile-with-shortcode');?></strong></td>
            <td>
            <?php Form_Class::form_input('text', 'wprw_success_msg', '', $wprw_success_msg, 'widefat', '', '', '', '', '', false, __('You are successfully registered', 'wp-register-profile-with-shortcode'));?>
              <br><i><?php _e('Message to display after successful registration.', 'wp-register-profile-with-shortcode');?></i>
              <br><br><strong>Default Message</strong> "<?php echo self::$wprw_success_msg; ?>"
              </td>
          </tr>
        </table>
        <table width="100%" border="0">
            <tr>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td align="center"><?php Form_Class::form_input('submit', 'submit', '', __('Save', 'wp-register-profile-with-shortcode'), 'button button-primary button-large button-ap-large', '', '', '', '', '', false, '');?></td>
          </tr>
        </table>
      </div>
      <div class="ap-tab-content">
        <table width="100%" border="0">
          <tr>
            <td width="300"><strong><?php _e('Admin Email', 'wp-register-profile-with-shortcode');?></strong></td>
            <td><?php Form_Class::form_input('text', 'wprw_admin_email', '', $wprw_admin_email, 'widefat', '', '', '', '', '', false, __('admin@example.com', 'wp-register-profile-with-shortcode'));?>
              <i><?php _e('Admin Email notification will be sent to this email address when new user do registration in the site', 'wp-register-profile-with-shortcode');?></i></td>
          </tr>
          <tr>
            <td colspan="2">&nbsp;</td>
          </tr>
          <tr>
            <td><strong><?php _e('From Email', 'wp-register-profile-with-shortcode');?></strong></td>
            <td><?php Form_Class::form_input('text', 'wprw_from_email', '', $wprw_from_email, 'widefat', '', '', '', '', '', false, __('no-reply@example.com', 'wp-register-profile-with-shortcode'));?>
              <i><?php _e('This will make sure the emails are not treated as SPAM', 'wp-register-profile-with-shortcode');?></i></td>
          </tr>
          <tr>
            <td colspan="2">&nbsp;</td>
          </tr>
          <tr>
            <td valign="top"><strong><?php _e('New User Registration Email Subject', 'wp-register-profile-with-shortcode');?></strong></td>
            <td><?php Form_Class::form_input('text', 'new_user_register_mail_subject', '', $new_user_register_mail_subject, 'widefat');?></td>
          </tr>
          <tr>
            <td colspan="2">&nbsp;</td>
          </tr>
          <tr>
            <td valign="top"><strong><?php _e('New User Registration Email Body', 'wp-register-profile-with-shortcode');?></strong>
              <p><i><?php _e('This mail will be sent to the user who make registration in the site. HTML tags are allowed.', 'wp-register-profile-with-shortcode');?></i></p></td>
            <td><?php Form_Class::form_textarea('new_user_register_mail_body', '', $new_user_register_mail_body, 'widefat', '', '', '', '', '', '', '', 'height:200px;');?>
              <p>Shortcodes: #site_name#, #user_name#, #user_password#, #site_url#</p></td>
          </tr>
          <tr>
            <td colspan="2"><strong>Note**</strong> When new user make registration in the site, Admin and User both will get a notification email.</td>
          </tr>
        </table>
        <table width="100%" border="0">
          <tr>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td align="center"><?php Form_Class::form_input('submit', 'submit', '', __('Save', 'wp-register-profile-with-shortcode'), 'button button-primary button-large button-ap-large', '', '', '', '', '', false, '');?></td>
          </tr>
        </table>
      </div>
      <div class="ap-tab-content">
        <table width="100%" border="0">
          <tr>
            <td><strong>1.</strong> Use this <span style="color:#000066;">[rp_register_widget]</span> shortcode to display registration form in post or page.<br />
          Example: <span style="color:#000066;">[rp_register_widget title="User Registration"]</span> <br />
          <br />
          <strong>2.</strong> Use this shortcode for user profile page <span style="color:#000066;">[rp_profile_edit]</span> Logged in users can edit profile data from this page. <br />
          <br />
          <strong>3.</strong> Use this shortcode to display Update Password form <span style="color:#000066;">[rp_update_password]</span> Logged in users can update password from this page. <br />
          <br />
          <strong>4.</strong> Use This shortcode to retrieve user data <span style="color:#000066;">[rp_user_data field="first_name" user_id="2"]</span> user_id can be blank. if blank then the data is retrieve from currently loged in user. Or else you can use this function in your template file. <span style="color:#000066;">&lt;?php rp_user_data_func("first_name","2"); ?&gt;</span> <br /></td>
          </tr>
        </table>
      </div>

      <?php do_action('wprp_custom_settings_tab_content');?>
    </div></td>
</tr>
</table>

<?php
Form_Class::form_close();