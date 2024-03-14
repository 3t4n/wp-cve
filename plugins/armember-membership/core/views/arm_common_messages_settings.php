<?php
global $wpdb, $ARMemberLite, $arm_members_class, $arm_member_forms, $arm_global_settings;
$common_messages         = $arm_global_settings->arm_get_all_common_message_settings();
$default_common_messages = $arm_global_settings->arm_default_common_messages();
if ( ! empty( $common_messages ) ) {
	foreach ( $common_messages as $key => $value ) {
		$common_messages[ $key ] = esc_html( stripslashes( $value ) );
	}
}
?>
<div class="arm_global_settings_main_wrapper">
	<div class="page_sub_content">
		<form  method="post" action="#" id="arm_common_message_settings" class="arm_common_message_settings arm_admin_form">
			<div class="page_sub_title"><?php esc_html_e( 'Login Related Messages', 'armember-membership' ); ?></div>
			<div class="armclear"></div>
			<table class="form-table">								
				<tr class="form-field">
					<th class="arm-form-table-label">
						<label for="arm_user_not_exist"><?php esc_html_e( 'Incorrect Username/Email', 'armember-membership' ); ?></label>
						
					</th>
					<td class="arm-form-table-content arm_vertical_align_top" >
						<input type="text" name="arm_common_message_settings[arm_user_not_exist]" id="arm_user_not_exist" value="<?php echo ( ! empty( $common_messages['arm_user_not_exist'] ) ) ? esc_attr($common_messages['arm_user_not_exist']) : ''; ?>"/>
					</td>
				</tr>
				<tr class="form-field">
					<th class="arm-form-table-label"><label for="arm_invalid_password_login"><?php esc_html_e( 'Incorrect Password', 'armember-membership' ); ?></label></th>
					<td class="arm-form-table-content">
						<input type="text" name="arm_common_message_settings[arm_invalid_password_login]" id="arm_invalid_password_login" value="<?php echo ( ! empty( $common_messages['arm_invalid_password_login'] ) ) ? esc_attr($common_messages['arm_invalid_password_login']) : ''; ?>"/>
					</td>
				</tr>
				<tr class="form-field">
					<th class="arm-form-table-label"><label for="arm_attempts_many_login_failed"><?php echo sprintf( esc_html__( 'Too Many Failed Login Attempts%1$sTemporary%2$s', 'armember-membership' ), '(', ')' ); //phpcs:ignore ?></label></th>
					<td class="arm-form-table-content">
						<input type="text" name="arm_common_message_settings[arm_attempts_many_login_failed]" id="arm_attempts_many_login_failed" value="<?php echo ( ! empty( $common_messages['arm_attempts_many_login_failed'] ) ) ? esc_attr($common_messages['arm_attempts_many_login_failed']) : ''; ?>"/>
						<br>
						<span class="remained_login_attempts_notice">
						<?php esc_html_e( 'To display the duration of locked account, use', 'armember-membership' ); ?><b> [LOCKDURATION] </b><?php esc_html_e( 'shortcode in a message.', 'armember-membership' ); ?>
						</span>
					</td>
				</tr>
				<?php

					$arm_permanent_locked_message = ( ! isset( $common_messages['arm_permanent_locked_message'] ) ) ? $default_common_messages['arm_permanent_locked_message'] : $common_messages['arm_permanent_locked_message'];

				?>
				<tr class="form-field">
					<th class="arm-form-table-label"><label for="arm_permanent_locked_message"><?php echo sprintf( esc_html__( 'Too Many Failed Login Attempts%1$sPermanent%2$s', 'armember-membership' ), '(', ')' ); //phpcs:ignore ?></label></th>
					<td class="arm-form-table-content">
						<input type="text" name="arm_common_message_settings[arm_permanent_locked_message]" id="arm_permanent_locked_message" value="<?php echo esc_attr($arm_permanent_locked_message); ?>"/>
						<br>
						<span class="remained_login_attempts_notice">
						<?php esc_html_e( 'To display the duration of locked account, use', 'armember-membership' ); ?><b> [LOCKDURATION] </b><?php esc_html_e( 'shortcode in a message.', 'armember-membership' ); ?>
						</span>
					</td>
				</tr>
								
								<tr class="form-field">
					<th class="arm-form-table-label"><label for="arm_attempts_login_failed"><?php esc_html_e( 'Remained Login Attempts Warning', 'armember-membership' ); ?></label></th>
					<td class="arm-form-table-content">
						<input type="text" name="arm_common_message_settings[arm_attempts_login_failed]" id="arm_attempts_login_failed" value="<?php echo ( ! empty( $common_messages['arm_attempts_login_failed'] ) ) ? esc_attr($common_messages['arm_attempts_login_failed']) : ''; ?>"/>
												<br>
												<span class="remained_login_attempts_notice">
												<?php esc_html_e( 'To display the number of remaining attempts use', 'armember-membership' ); ?>
												<b>[ATTEMPTS]</b>
												<?php esc_html_e( 'shortcode in a message.', 'armember-membership' ); ?>
												</span>
										</td>
				</tr>
								<tr class="form-field">
					<th class="arm-form-table-label"><label for="arm_armif_already_logged_in"><?php esc_html_e( 'User Already LoggedIn Message', 'armember-membership' ); ?></label></th>
					<td class="arm-form-table-content">
						<input type="text" name="arm_common_message_settings[arm_armif_already_logged_in]" id="arm_armif_already_logged_in" value="<?php echo ( ! empty( $common_messages['arm_armif_already_logged_in'] ) ) ? esc_attr($common_messages['arm_armif_already_logged_in']) : ''; ?>"/>
												<br/><span class="remained_login_attempts_notice"><?php esc_html_e( 'User already loggedIn message for modal forms ( Navigation Popup )', 'armember-membership' ); ?></span>
					</td>
				</tr>
								
				<tr class="form-field">
					<th class="arm-form-table-label"><label for="arm_spam_msg"><?php esc_html_e( 'System Detected Spam Robots', 'armember-membership' ); ?></label></th>
					<td class="arm-form-table-content">
						<input type="text" name="arm_common_message_settings[arm_spam_msg]" id="arm_spam_msg" value="<?php echo ( ! empty( $common_messages['arm_spam_msg'] ) ) ? esc_attr($common_messages['arm_spam_msg']) : ''; ?>"/>
					</td>
				</tr>				
				<?php if($ARMemberLite->is_arm_pro_active){
					echo apply_filters('arm_load_common_message_section','social_connect');	
				}  ?>
			</table>
			<div class="arm_solid_divider"></div>
			<div class="page_sub_title"><?php esc_html_e( 'Forgot Password Messages', 'armember-membership' ); ?></div>
			<table class="form-table">				
				<tr class="form-field">
					<th class="arm-form-table-label"><label for="arm_no_registered_email"><?php esc_html_e( 'Incorrect Username/Email', 'armember-membership' ); ?></label></th>
					<td class="arm-form-table-content">
						<input type="text" name="arm_common_message_settings[arm_no_registered_email]" id="arm_no_registered_email" value="<?php echo ( ! empty( $common_messages['arm_no_registered_email'] ) ) ? esc_attr($common_messages['arm_no_registered_email']) : ''; ?>"/>
					</td>
				</tr>
				<tr class="form-field">
					<th class="arm-form-table-label"><label for="arm_reset_pass_not_allow"><?php esc_html_e( 'Password Reset Not Allowed', 'armember-membership' ); ?></label></th>
					<td class="arm-form-table-content">
						<input type="text" name="arm_common_message_settings[arm_reset_pass_not_allow]" id="arm_reset_pass_not_allow" value="<?php echo ( ! empty( $common_messages['arm_reset_pass_not_allow'] ) ) ? esc_attr($common_messages['arm_reset_pass_not_allow']) : ''; ?>"/>
					</td>
				</tr>
				<tr class="form-field">
					<th class="arm-form-table-label"><label for="arm_email_not_sent"><?php esc_html_e( 'Email Not Sent', 'armember-membership' ); ?></label></th>
					<td class="arm-form-table-content">
						<input type="text" name="arm_common_message_settings[arm_email_not_sent]" id="arm_email_not_sent" value="<?php echo ( ! empty( $common_messages['arm_email_not_sent'] ) ) ? esc_attr($common_messages['arm_email_not_sent']) : ''; ?>"/>
					</td>
				</tr>				
			</table>
			<div class="armclear"></div>
			<div class="arm_solid_divider"></div>
			<div class="page_sub_title"><?php esc_html_e( 'Change Password Messages', 'armember-membership' ); ?></div>
			<table class="form-table">				                                
								<tr class="form-field">
					<th class="arm-form-table-label"><label for="arm_password_reset"><?php esc_html_e( 'Your password has been reset', 'armember-membership' ); ?></label></th>
					<td class="arm-form-table-content">
						<input type="text" name="arm_common_message_settings[arm_password_reset]" id="arm_password_reset" value="<?php echo ( ! empty( $common_messages['arm_password_reset'] ) ) ? esc_attr($common_messages['arm_password_reset']) : ''; ?>"/>
												<br>
												<span class="remained_login_attempts_notice">
												<?php esc_html_e( 'To display Login link use', 'armember-membership' ); ?>
												<b>[LOGINLINK]<?php esc_html_e( 'Login link label', 'armember-membership' ); ?>[/LOGINLINK]</b>
												<?php esc_html_e( 'shortcode in message.', 'armember-membership' ); ?>
												</span>
												<span class="arm_info_text">(<?php esc_html_e( 'This message will be used only when password is changed from password reset link sent in mail', 'armember-membership' ); ?>)</span>
										</td>
				</tr>
								<tr class="form-field">
					<th class="arm-form-table-label"><label for="arm_password_enter_new_pwd"><?php esc_html_e( 'Please Enter New Password', 'armember-membership' ); ?></label></th>
					<td class="arm-form-table-content">
						<input type="text" name="arm_common_message_settings[arm_password_enter_new_pwd]" id="arm_password_enter_new_pwd" value="<?php echo ( ! empty( $common_messages['arm_password_enter_new_pwd'] ) ) ? esc_attr($common_messages['arm_password_enter_new_pwd']) : ''; ?>"/>
												<span class="arm_info_text">(<?php esc_html_e( 'This message will be displayed in reset password form where user comes by clicking on reset password link', 'armember-membership' ); ?>)</span>
										</td>
				</tr>
								<tr class="form-field">
					<th class="arm-form-table-label"><label for="arm_password_reset_pwd_link_expired"><?php esc_html_e( 'Reset Password Link is invalid.', 'armember-membership' ); ?></label></th>
					<td class="arm-form-table-content">
						<input type="text" name="arm_common_message_settings[arm_password_reset_pwd_link_expired]" id="arm_password_reset_pwd_link_expired" value="<?php echo ( ! empty( $common_messages['arm_password_reset_pwd_link_expired'] ) ) ? esc_attr($common_messages['arm_password_reset_pwd_link_expired']) : ''; ?>"/>
												<span class="arm_info_text">(<?php esc_html_e( 'This message will be displayed on page where user comes by clicking expired reset password link', 'armember-membership' ); ?>)</span>
										</td>
				</tr>
			</table>
			<div class="armclear"></div>
			<div class="arm_solid_divider"></div>
			<div class="page_sub_title"><?php esc_html_e( 'Close Account Messages', 'armember-membership' ); ?></div>
			<table class="form-table">
				<tr class="form-field">
					<th class="arm-form-table-label"><label for="arm_form_title_close_account"><?php esc_html_e( 'Form Title', 'armember-membership' ); ?></label></th>
					<td class="arm-form-table-content">
						<input type="text" name="arm_common_message_settings[arm_form_title_close_account]" id="arm_form_title_close_account" value="<?php echo ( ! empty( $common_messages['arm_form_title_close_account'] ) ) ? esc_attr($common_messages['arm_form_title_close_account']) : ''; ?>"/>
					</td>
				</tr>
				<tr class="form-field">
					<th class="arm-form-table-label"><label for="arm_form_description_close_account"><?php esc_html_e( 'Form Description', 'armember-membership' ); ?></label></th>
					<td class="arm-form-table-content">
						<input type="text" name="arm_common_message_settings[arm_form_description_close_account]" id="arm_form_description_close_account" value="<?php echo ( ! empty( $common_messages['arm_form_description_close_account'] ) ) ? esc_attr($common_messages['arm_form_description_close_account']) : ''; ?>"/>
					</td>
				</tr>
				<tr class="form-field">
					<th class="arm-form-table-label"><label for="arm_password_label_close_account"><?php esc_html_e( 'Password Field Label', 'armember-membership' ); ?></label></th>
					<td class="arm-form-table-content">
						<input type="text" name="arm_common_message_settings[arm_password_label_close_account]" id="arm_password_label_close_account" value="<?php echo ( ! empty( $common_messages['arm_password_label_close_account'] ) ) ? esc_attr($common_messages['arm_password_label_close_account']) : ''; ?>"/>
					</td>
				</tr>
				<tr class="form-field">
					<th class="arm-form-table-label"><label for="arm_submit_btn_close_account"><?php esc_html_e( 'Submit Button Label', 'armember-membership' ); ?></label></th>
					<td class="arm-form-table-content">
						<input type="text" name="arm_common_message_settings[arm_submit_btn_close_account]" id="arm_submit_btn_close_account" value="<?php echo ( ! empty( $common_messages['arm_submit_btn_close_account'] ) ) ? esc_attr($common_messages['arm_submit_btn_close_account']) : ''; ?>"/>
					</td>
				</tr>
				<tr class="form-field">
					<th class="arm-form-table-label"><label for="arm_blank_password_close_account"><?php esc_html_e( 'Empty Password Message', 'armember-membership' ); ?></label></th>
					<td class="arm-form-table-content">
						<input type="text" name="arm_common_message_settings[arm_blank_password_close_account]" id="arm_blank_password_close_account" value="<?php echo ( ! empty( $common_messages['arm_blank_password_close_account'] ) ) ? esc_attr($common_messages['arm_blank_password_close_account']) : ''; ?>"/>
					</td>
				</tr>
				<tr class="form-field">
					<th class="arm-form-table-label"><label for="arm_invalid_password_close_account"><?php esc_html_e( 'Invalid Password Message', 'armember-membership' ); ?></label></th>
					<td class="arm-form-table-content">
						<input type="text" name="arm_common_message_settings[arm_invalid_password_close_account]" id="arm_invalid_password_close_account" value="<?php echo ( ! empty( $common_messages['arm_invalid_password_close_account'] ) ) ? esc_attr($common_messages['arm_invalid_password_close_account']) : ''; ?>"/>
					</td>
				</tr>
			</table>
			<div class="armclear"></div>
			<div class="arm_solid_divider"></div>
			<div class="page_sub_title"><?php esc_html_e( 'Registration / Edit Profile Labels', 'armember-membership' ); ?></div>
			<div class="armclear"></div>
			<table class="form-table">
				<tr class="form-field">
					<th class="arm-form-table-label"><label for="arm_user_not_created"><?php esc_html_e( 'User Not Created', 'armember-membership' ); ?></label></th>
					<td class="arm-form-table-content">
						<input type="text" name="arm_common_message_settings[arm_user_not_created]" id="arm_user_not_created" value="<?php echo ( ! empty( $common_messages['arm_user_not_created'] ) ) ? esc_attr($common_messages['arm_user_not_created']) : ''; ?>"/>
					</td>
				</tr>
				
				<tr class="form-field">
					<th class="arm-form-table-label"><label for="arm_username_exist"><?php esc_html_e( 'Username Already Exist', 'armember-membership' ); ?></label></th>
					<td class="arm-form-table-content">
						<input type="text" name="arm_common_message_settings[arm_username_exist]" id="arm_username_exist" value="<?php echo ( ! empty( $common_messages['arm_username_exist'] ) ) ? esc_attr($common_messages['arm_username_exist']) : ''; ?>"/>
					</td>
				</tr>

				<tr class="form-field">
					<th class="arm-form-table-label"><label for="arm_email_exist"><?php esc_html_e( 'Email Already Exist', 'armember-membership' ); ?></label></th>
					<td class="arm-form-table-content">
						<input type="text" name="arm_common_message_settings[arm_email_exist]" id="arm_email_exist" value="<?php echo ( ! empty( $common_messages['arm_email_exist'] ) ) ? esc_attr($common_messages['arm_email_exist']) : ''; ?>"/>
					</td>
				</tr>
								<tr class="form-field">
					<th class="arm-form-table-label"><label for="arm_avtar_label"><?php esc_html_e( 'Avatar Field Label( Edit Profile )', 'armember-membership' ); ?></label></th>
					<td class="arm-form-table-content">
						<input type="text" name="arm_common_message_settings[arm_avtar_label]" id="arm_email_exist" value="<?php echo ( isset( $common_messages['arm_avtar_label'] ) ) ? esc_attr($common_messages['arm_avtar_label']) : esc_html__( 'Avatar', 'armember-membership' ); //phpcs:ignore ?>"/>
					</td>
				</tr>
								<tr class="form-field">
					<th class="arm-form-table-label"><label for="arm_profile_cover_label"><?php esc_html_e( 'Profile Cover Field Label( Edit Profile )', 'armember-membership' ); ?></label></th>
					<td class="arm-form-table-content">
						<input type="text" name="arm_common_message_settings[arm_profile_cover_label]" id="arm_email_exist" value="<?php echo ( isset( $common_messages['arm_profile_cover_label'] ) ) ? esc_attr($common_messages['arm_profile_cover_label']) : esc_html__( 'Profile Cover', 'armember-membership' ); //phpcs:ignore ?>"/>
					</td>
				</tr>
							   
								<tr class="form-field">
					<th class="arm-form-table-label"><label for="arm_last_name_invalid"><?php esc_html_e( 'Minlength', 'armember-membership' ); ?></label></th>
					<td class="arm-form-table-content">
						<input type="text" name="arm_common_message_settings[arm_minlength_invalid]" id="arm_minlength_invalid" value="<?php echo ( ! empty( $common_messages['arm_minlength_invalid'] ) ) ? esc_attr($common_messages['arm_minlength_invalid']) : ''; ?>"/>
												<br>
												<span class="remained_login_attempts_notice">
												<?php esc_html_e( 'To display allowed minimum characters use', 'armember-membership' ); ?>
												<b>[MINVALUE]</b>
												<?php esc_html_e( 'shortcode in message.', 'armember-membership' ); ?>
												</span>
					</td>
				</tr>
								</tr>
								<tr class="form-field">
					<th class="arm-form-table-label"><label for="arm_last_name_invalid"><?php esc_html_e( 'Maxlength', 'armember-membership' ); ?></label></th>
					<td class="arm-form-table-content">
						<input type="text" name="arm_common_message_settings[arm_maxlength_invalid]" id="arm_maxlength_invalid" value="<?php echo ( ! empty( $common_messages['arm_maxlength_invalid'] ) ) ? esc_attr($common_messages['arm_maxlength_invalid']) : ''; ?>"/>
												<br>
												<span class="remained_login_attempts_notice">
												<?php esc_html_e( 'To display allowed maximum characters', 'armember-membership' ); ?>
												<b>[MAXVALUE]</b>
												<?php esc_html_e( 'shortcode in message.', 'armember-membership' ); ?>
												</span>
					</td>
				</tr>
			</table>
			<div class="arm_solid_divider"></div>
			<div class="page_sub_title"><?php esc_html_e( 'Account Related Messages', 'armember-membership' ); ?></div>
			<div class="armclear"></div>
			<table class="form-table">
				<tr class="form-field">
					<th class="arm-form-table-label"><label for="arm_expire_activation_link"><?php esc_html_e( 'Expire Activation Link', 'armember-membership' ); ?></label></th>
					<td class="arm-form-table-content">
						<input type="text" name="arm_common_message_settings[arm_expire_activation_link]" id="arm_expire_activation_link" value="<?php echo ( ! empty( $common_messages['arm_expire_activation_link'] ) ) ? esc_attr($common_messages['arm_expire_activation_link']) : ''; ?>"/>
					</td>
				</tr>
				
				<tr class="form-field">
					<th class="arm-form-table-label"><label for="arm_already_active_account"><?php esc_html_e( 'Account Activated', 'armember-membership' ); ?></label></th>
					<td class="arm-form-table-content">
						<input type="text" name="arm_common_message_settings[arm_already_active_account]" id="arm_already_active_account" value="<?php echo ( ! empty( $common_messages['arm_already_active_account'] ) ) ? esc_attr($common_messages['arm_already_active_account']) : ''; ?>"/>
					</td>
				</tr>

				<tr class="form-field">
					<th class="arm-form-table-label"><label for="arm_account_pending"><?php esc_html_e( 'Account Pending', 'armember-membership' ); ?></label></th>
					<td class="arm-form-table-content">
						<input type="text" name="arm_common_message_settings[arm_account_pending]" id="arm_account_pending" value="<?php echo ( ! empty( $common_messages['arm_account_pending'] ) ) ? esc_attr($common_messages['arm_account_pending']) : ''; ?>"/>
					</td>
				</tr>
								
				<tr class="form-field">
					<th class="arm-form-table-label"><label for="arm_already_inactive_account"><?php esc_html_e( 'Account Inactivated', 'armember-membership' ); ?></label></th>
					<td class="arm-form-table-content">
						<input type="text" name="arm_common_message_settings[arm_account_inactive]" id="arm_already_inactive_account" value="<?php echo ( ! empty( $common_messages['arm_account_inactive'] ) ) ? esc_attr($common_messages['arm_account_inactive']) : ''; ?>"/>
					</td>
				</tr>
				
			</table>
			<div class="arm_solid_divider"></div>
			<div class="page_sub_title"><?php esc_html_e( 'Payment Related Messages', 'armember-membership' ); ?></div>
			<div class="armclear"></div>
			<table class="form-table">
				
				<?php if($ARMemberLite->is_arm_pro_active){
					echo apply_filters('arm_load_common_message_section','payment_related');	
				}  ?>
				
				<tr class="form-field">
					<th class="arm-form-table-label"><label for="arm_invalid_plan_select"><?php esc_html_e( 'Invalid Plan Selected', 'armember-membership' ); ?></label></th>
					<td class="arm-form-table-content">
						<input type="text" name="arm_common_message_settings[arm_invalid_plan_select]" id="arm_invalid_plan_select" value="<?php echo ( ! empty( $common_messages['arm_invalid_plan_select'] ) ) ? esc_attr($common_messages['arm_invalid_plan_select']) : ''; ?>"/>
					</td>
				</tr>
				
				<tr class="form-field">
					<th class="arm-form-table-label armember_general_setting_lbl"><label for="arm_no_select_payment_geteway"><?php esc_html_e( 'No Gateway Selected For Paid Plan', 'armember-membership' ); ?></label></th>
					<td class="arm-form-table-content">
						<input type="text" name="arm_common_message_settings[arm_no_select_payment_geteway]" id="arm_no_select_payment_geteway" value="<?php echo ( ! empty( $common_messages['arm_no_select_payment_geteway'] ) ) ? esc_attr($common_messages['arm_no_select_payment_geteway']) : ''; ?>"/>
					</td>
				</tr>
				<tr class="form-field">
					<th class="arm-form-table-label"><label for="arm_inactive_payment_gateway"><?php esc_html_e( 'Payment Gateway Inactive', 'armember-membership' ); ?></label></th>
					<td class="arm-form-table-content">
						<input type="text" name="arm_common_message_settings[arm_inactive_payment_gateway]" id="arm_inactive_payment_gateway" value="<?php echo ( ! empty( $common_messages['arm_inactive_payment_gateway'] ) ) ? esc_attr($common_messages['arm_inactive_payment_gateway']) : ''; ?>"/>
					</td>
				</tr>
				<?php if($ARMemberLite->is_arm_pro_active){
					echo apply_filters('arm_load_common_message_section','bank_payment_gateway');	
				}  ?>
				<?php do_action( 'arm_payment_related_common_message', $common_messages ); ?>
			</table>
						 <div class="arm_solid_divider"></div>

						 <?php if($ARMemberLite->is_arm_pro_active){
							echo apply_filters('arm_load_common_message_section','coupon_related');	
						}  ?>
		
						<div class="arm_solid_divider"></div>
			<div class="page_sub_title"><?php esc_html_e( 'Profile/Directory Related Messages', 'armember-membership' ); ?></div>
			<div class="armclear"></div>
			<table class="form-table">
				 <tr class="form-field">
					<th class="arm-form-table-label"><label for="profile_directory_upload_cover_photo"><?php esc_html_e( 'Upload Cover Photo', 'armember-membership' ); ?></label></th>
					<td class="arm-form-table-content">
						<input type="text" name="arm_common_message_settings[profile_directory_upload_cover_photo]" id="profile_directory_upload_cover_photo" value="<?php echo ( ! empty( $common_messages['profile_directory_upload_cover_photo'] ) ) ? esc_attr($common_messages['profile_directory_upload_cover_photo']) : ''; ?>"/>
					</td>
				</tr>
								<tr class="form-field">
					<th class="arm-form-table-label"><label for="profile_directory_remove_cover_photo"><?php esc_html_e( 'Remove Cover Photo', 'armember-membership' ); ?></label></th>
					<td class="arm-form-table-content">
						<input type="text" name="arm_common_message_settings[profile_directory_remove_cover_photo]" id="profile_directory_remove_cover_photo" value="<?php echo ( ! empty( $common_messages['profile_directory_remove_cover_photo'] ) ) ? esc_attr($common_messages['profile_directory_remove_cover_photo']) : ''; ?>"/>
					</td>
				</tr>
								<tr class="form-field">
					<th class="arm-form-table-label"><label for="profile_template_upload_profile_photo"><?php esc_html_e( 'Upload Profile Photo', 'armember-membership' ); ?></label></th>
					<td class="arm-form-table-content">
						<input type="text" name="arm_common_message_settings[profile_template_upload_profile_photo]" id="profile_template_upload_profile_photo" value="<?php echo ( ! empty( $common_messages['profile_template_upload_profile_photo'] ) ) ? esc_attr($common_messages['profile_template_upload_profile_photo']) : ''; ?>"/>
					</td>
				</tr>
				<tr class="form-field">
					<th class="arm-form-table-label"><label for="profile_template_remove_profile_photo"><?php esc_html_e( 'Remove Profile Photo', 'armember-membership' ); ?></label></th>
					<td class="arm-form-table-content">
						<input type="text" name="arm_common_message_settings[profile_template_remove_profile_photo]" id="profile_template_remove_profile_photo" value="<?php echo ( ! empty( $common_messages['profile_template_remove_profile_photo'] ) ) ? esc_attr($common_messages['profile_template_remove_profile_photo']) : ''; ?>"/>
					</td>
				</tr>
				
				<?php if($ARMemberLite->is_arm_pro_active){
					echo apply_filters('arm_load_common_message_section','directory_search');	
				}  ?>

				<tr class="form-field">
					<th class="arm-form-table-label"><label for="directory_sort_by_alphabatically"><?php esc_html_e( 'Alphabatically (Directory Filter)', 'armember-membership' ); ?></label></th>
					<td class="arm-form-table-content">
						<input type="text" name="arm_common_message_settings[directory_sort_by_alphabatically]" id="directory_sort_by_alphabatically" value="<?php echo ( ! empty( $common_messages['directory_sort_by_alphabatically'] ) ) ? esc_attr($common_messages['directory_sort_by_alphabatically']) : ''; ?>"/>
					</td>
				</tr> <tr class="form-field">
					<th class="arm-form-table-label"><label for="directory_sort_by_recently_joined"><?php esc_html_e( 'Recently Joined (Directory Filter)', 'armember-membership' ); ?></label></th>
					<td class="arm-form-table-content">
						<input type="text" name="arm_common_message_settings[directory_sort_by_recently_joined]" id="directory_sort_by_recently_joined" value="<?php echo ( ! empty( $common_messages['directory_sort_by_recently_joined'] ) ) ? esc_attr($common_messages['directory_sort_by_recently_joined']) : ''; ?>"/>
					</td>
				</tr
								</tr> <tr class="form-field">
					<th class="arm-form-table-label"><label for="arm_profile_member_since"><?php esc_html_e( 'Member Since', 'armember-membership' ); ?></label></th>
					<td class="arm-form-table-content">
						<input type="text" name="arm_common_message_settings[arm_profile_member_since]" id="arm_profile_member_since" value="<?php echo ( isset( $common_messages['arm_profile_member_since'] ) ) ? esc_attr($common_messages['arm_profile_member_since']) : esc_html__( 'Member Since', 'armember-membership' ); //phpcs:ignore ?>"/>
					</td>
				</tr>
				<?php if($ARMemberLite->is_arm_pro_active){
					echo apply_filters('arm_load_common_message_section','personal_detail');	
				}  ?>
								</tr> <tr class="form-field">
					<th class="arm-form-table-label"><label for="arm_profile_view_profile"><?php esc_html_e( 'View Profile', 'armember-membership' ); ?></label></th>
					<td class="arm-form-table-content">
						<input type="text" name="arm_common_message_settings[arm_profile_view_profile]" id="arm_profile_view_profile" value="<?php echo ( isset( $common_messages['arm_profile_view_profile'] ) ) ? esc_attr($common_messages['arm_profile_view_profile']) : esc_html__( 'View Profile', 'armember-membership' ); //phpcs:ignore ?>"/>
					</td>
				</tr>
				<?php if($ARMemberLite->is_arm_pro_active){
					echo apply_filters('arm_load_common_message_section','directory_filter');	
				}  ?>
								
						</table>
			<div class="arm_solid_divider"></div>
			<div class="page_sub_title"><?php esc_html_e( 'Miscellaneous Messages', 'armember-membership' ); ?></div>
			<div class="armclear"></div>
			<table class="form-table">
				<tr class="form-field">
					<th class="arm-form-table-label"><label for="arm_general_msg"><?php esc_html_e( 'General Message', 'armember-membership' ); ?></label></th>
					<td class="arm-form-table-content">
						<input type="text" name="arm_common_message_settings[arm_general_msg]" id="arm_general_msg" value="<?php echo ( ! empty( $common_messages['arm_general_msg'] ) ) ? esc_attr($common_messages['arm_general_msg']) : ''; ?>"/>
					</td>
				</tr>
				<tr class="form-field">
					<th class="arm-form-table-label"><label for="arm_search_result_found"><?php esc_html_e( 'No Search Result Found', 'armember-membership' ); ?></label></th>
					<td class="arm-form-table-content">
						<input type="text" name="arm_common_message_settings[arm_search_result_found]" id="arm_search_result_found" value="<?php echo ( ! empty( $common_messages['arm_search_result_found'] ) ) ? esc_attr($common_messages['arm_search_result_found']) : ''; ?>"/>
					</td>
				</tr>
				
				<tr class="form-field">
					<th class="arm-form-table-label"><label for="arm_armif_invalid_argument"><?php esc_html_e( 'Invalid Arguments (ARM If Shortcode)', 'armember-membership' ); ?></label></th>
					<td class="arm-form-table-content">
						<input type="text" name="arm_common_message_settings[arm_armif_invalid_argument]" id="arm_armif_invalid_argument" value="<?php echo ( ! empty( $common_messages['arm_armif_invalid_argument'] ) ) ? esc_attr($common_messages['arm_armif_invalid_argument']) : ''; ?>"/>
					</td>
				</tr>
				<?php if($ARMemberLite->is_arm_pro_active){
					echo apply_filters('arm_load_common_message_section','miscellaneous');	
				}  ?>
								
						</table>
						
			<?php do_action( 'arm_after_common_messages_settings_html', $common_messages ); ?>
			<div class="arm_submit_btn_container">
				<img src="<?php echo MEMBERSHIPLITE_IMAGES_URL . '/arm_loader.gif'; //phpcs:ignore
				 ?>" class="arm_submit_btn_loader" id="arm_loader_img" style="display:none;" width="24" height="24" />&nbsp;<button class="arm_save_btn arm_common_message_settings_btn" type="submit" id="arm_common_message_settings_btn" name="arm_common_message_settings_btn"><?php esc_html_e( 'Save', 'armember-membership' ); ?></button>
			</div>
			<?php $wpnonce = wp_create_nonce( 'arm_wp_nonce' );?>
				<input type="hidden" name="arm_wp_nonce" value="<?php echo esc_attr($wpnonce);?>"/>
		</form>
		<div class="armclear"></div>
	</div>
</div>
