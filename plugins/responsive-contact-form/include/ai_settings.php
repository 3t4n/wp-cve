<style>
.icon32 { display: block; float: left; margin-right: 10px; vertical-align: middle; }
.wrap h3 { margin-bottom:0; color: #222; border:0; }
#form-settings { border-top: 5px solid #dfdfdf; }
#form-settings .field-name { font-weight: bold; width: 60px; text-align:right; }
#form-settings .field-status { width: 60px; text-align:center; }
#email-settings { border-top: 5px solid #dfdfdf; }
#email-settings th { width: 162px; }
</style>

<div class="wrap"> 
	<img src="<?php echo plugins_url();?>/responsive-contact-form/images/august-infotech.png" class="icon32" />
	<h2><?php _e('Responsive Contact Form Settings','aicontactform'); ?></h2>
	<form method="post" action="options.php" name="AIGolbalSiteOptions">
		<?php settings_fields( 'ai-fields' ); ?>
		<h3 class="title"><?php _e('Form Settings','aicontactform'); ?></h3>
		<table class="form-table" id="form-settings">
			<tbody>
				<tr>
					<th class="field-name" scope="row"><?php _e('<strong>Field Name</strong>','aicontactform');?></th>
					<th class="field-status"><?php _e('<strong>Select to Show / Hide Fields on form</strong>','aicontactform');?></th>
					<th><?php _e('<strong>Required / Not Required Fields</strong>','aicontactform');?></th>
				</tr>
				<tr>
					<th class="field-name" scope="row"><?php _e('Name:','aicontactform');?></th>
					<td class="field-status"><input type="checkbox" name="ai_visible_name" onclick="validate_name()" id="ai_visible_name" <?php if(esc_attr(get_option('ai_visible_name'))=="on"){echo "checked";} ?>  /></td>
					<td><input type="checkbox" name="ai_enable_require_name" <?php if(esc_attr(get_option('ai_visible_name'))==""){ echo 'disabled="disabled"';} ?> id="ai_enable_require_name" <?php if(esc_attr(get_option('ai_enable_require_name'))=="on"){echo "checked";} ?>  /></td>
				</tr>
				<tr>
					<th class="field-name" scope="row"><?php _e('Phone:','aicontactform');?></th>
					<td class="field-status"><input type="checkbox" name="ai_visible_phone" onclick="validate_phone()" id="ai_visible_phone" <?php if(esc_attr(get_option('ai_visible_phone'))=="on"){echo "checked";} ?>  /></td>
					<td><input type="checkbox" name="ai_enable_require_phone" <?php if(esc_attr(get_option('ai_visible_phone'))==""){ echo 'disabled="disabled"';} ?> id="ai_enable_require_phone" <?php if(esc_attr(get_option('ai_enable_require_phone'))=="on"){echo "checked";} ?>  /></td>
				</tr>
				<tr>
					<th class="field-name" scope="row"><?php _e('Website:','aicontactform');?></th>
					<td class="field-status"><input type="checkbox" name="ai_visible_website" onclick="validate_website()" id="ai_visible_website" <?php if(esc_attr(get_option('ai_visible_website'))=="on"){echo "checked";} ?>  /></td>
					<td><input type="checkbox" name="ai_enable_require_website" <?php if(esc_attr(get_option('ai_visible_website'))==""){ echo 'disabled="disabled"';} ?> id="ai_enable_require_website" <?php if(esc_attr(get_option('ai_enable_require_website'))=="on"){echo "checked";} ?>  /></td>
				</tr>
				<tr>
					<th class="field-name" scope="row"><?php _e('Subject:','aicontactform');?></th>
					<td class="field-status"><input type="checkbox" name="ai_visible_subject" onclick="validate_subject()" id="ai_visible_subject" <?php if(esc_attr(get_option('ai_visible_subject'))=="on"){echo "checked";} ?>  /></td>
					<td><input type="checkbox" name="ai_enable_require_subject" <?php if(esc_attr(get_option('ai_visible_subject'))==""){ echo 'disabled="disabled"';} ?> id="ai_enable_require_subject" <?php if(esc_attr(get_option('ai_enable_require_subject'))=="on"){echo "checked";} ?>  /></td>
				</tr>
				<tr>
					<th class="field-name" scope="row"><?php _e('Comment:','aicontactform');?></th>
					<td class="field-status"><input type="checkbox" name="ai_visible_comment" onclick="validate_comment()" id="ai_visible_comment" <?php if(esc_attr(get_option('ai_visible_comment'))=="on"){echo "checked";} ?>  /></td>
					<td><input type="checkbox" name="ai_enable_require_comment" <?php if(esc_attr(get_option('ai_visible_comment'))==""){ echo 'disabled="disabled"';} ?> id="ai_enable_require_comment" <?php if(esc_attr(get_option('ai_enable_require_comment'))=="on"){echo "checked";} ?>  /></td>
				</tr>
				<tr>
					<th class="field-name" scope="row"><?php _e('Captcha:','aicontactform');?></th>
					<td class="field-status"><input type="checkbox" name="ai_enable_captcha" onclick="visible_site_secret()" id="ai_enable_captcha" <?php if(esc_attr(get_option('ai_enable_captcha'))=="on"){echo "checked";} ?>  /></td>
					<td><?php _e('<strong>Note: </strong>Enable captcha sets by default it to required field.','aicontactform');?></td>
				</tr>
				<tr>
					<td></td>
					<td colspan="2"><?php _e('<a target="_blank" href="https://www.google.com/recaptcha/intro/v3.html">Google reCaptcha V3</a>','aicontactform'); ?></td>
				</tr>
				<tr id="visible_site" <?php if(esc_attr(get_option('ai_enable_captcha'))==""){ echo 'style="display: none;"';} ?>>
					<th class="field-name" scope="row"><?php _e('Site Key:','aicontactform');?></th>
					<td class="" colspan="2"><input type="text" class="regular-text" name="ai_captcha_site_key" id="ai_captcha_site_key" value="<?php echo esc_attr(get_option('ai_captcha_site_key'));?>"  /></td>
				</tr>
				<tr id="visible_secret" <?php if(esc_attr(get_option('ai_enable_captcha'))==""){ echo 'style="display: none;"';} ?>>
					<th class="field-name" scope="row"><?php _e('Secret  Key:','aicontactform');?></th>
					<td class="" colspan="2"><input type="text" class="regular-text" name="ai_captcha_secret_key" id="ai_captcha_secret_key" value="<?php echo esc_attr(get_option('ai_captcha_secret_key'));?>"  /></td>
				</tr>
				<tr>
					<th class="field-name" scope="row"><?php _e('Email:','aicontactform');?></th>
					<td class="field-status"><input type="checkbox" name="ai_visible_email" onclick="validate_email()" id="ai_visible_email" checked="checked" disabled="disabled" /></td>
					<td><?php _e('<strong>Note: </strong>Email field is mandatory.','aicontactform');?></td>
				</tr>
				<tr>
					<th class="field-name" scope="row"><?php _e('Send me a copy:','aicontactform');?></th>
					<td class="field-status"><input type="checkbox" align="left" name="ai_visible_sendcopy" id="ai_visible_sendcopy" <?php if(esc_attr(get_option('ai_visible_sendcopy'))=="on"){echo "checked";} ?>  /></td>
					<td><?php _e('<strong>Note: </strong>Select to show checkbox on form.','aicontactform');?></td>
				</tr>
				<tr>
					<th class="field-name" scope="row"><?php _e('Remove user list:','aicontactform');?></th>
					<td class="field-status"><input type="checkbox" align="left" name="ai_rm_user_list" id="ai_rm_user_list" <?php if(esc_attr(get_option('ai_rm_user_list'))=="on"){echo "checked";} ?>  /></td>
					<td><?php _e('<strong>Note: </strong>Select checkbox to remove user list which connected to your website.','aicontactform');?></td>
				</tr>
				<tr>
					<th class="field-name" scope="row"><?php _e('Success Message Display:','aicontactform');?></th>
					<td class="field-status"><input type="checkbox" align="left" name="ai_success_message" id="ai_success_message" <?php if(esc_attr(get_option('ai_success_message'))=="on"){echo "checked";} ?>  /></td>
					<td><?php _e('<strong>Note: </strong>Select checkbox to show the message displayed after successful submission below.','aicontactform');?></td>
				</tr>
				<tr>
					<td colspan="3"></td>
				</tr>
			</tbody>
		</table>
		<h3 class="title"><?php _e('Mail Settings','aicontactform'); ?></h3>
		<table class="form-table" id="email-settings">
			<tbody>
				<tr>
					<th scope="row"><label for="ai_email_address_setting">
					<?php _e('Email Address:','aicontactform');?>
					</label></th>
					<td><input type="text" name="ai_email_address_setting" class="regular-text" value="<?php echo esc_attr(get_option('ai_email_address_setting'));?>"></td>
					<td><?php _e('<strong>Note:</strong> You can add multiple email addresses seperated by comma, to send email to multiple users.','aicontactform');?></td>
				</tr>
				<tr>
					<th scope="row"><label for="ai_subject_text">
					<?php _e('Subject Text:','aicontactform');?>
					</label></th>
					<td><input type="text" name="ai_subject_text" class="regular-text" value="<?php echo esc_attr(get_option('ai_subject_text'));?>"></td>
					<td><?php _e('<strong>Note:</strong> Default subject text " August Infotech " will be used.','aicontactform');?></td>
				</tr>
				<tr>
					<th scope="row"><label for="ai_reply_user_message">
					<?php _e('Reply Message for User:','aicontactform');?>
					</label></th>
					<td colspan="2">
						<?php 
						_e('Please enter the content of notification email that will be sent to the user.<br>You can use following strings:<br><strong>{name} {phone} {website} {comment}</strong>','aicontactform');
						$content = get_option('ai_reply_user_message');
						$settings = array( 'media_buttons' => false, 'textarea_name' => 'ai_reply_user_message' );
						wp_editor( $content, "ai_reply_user_message", $settings );

					 	_e('<strong>Note:</strong> Default Reply Message " Thank you for contacting us...We will get back to you soon... " will be used.','aicontactform');
					 	?>					 	
					 </td>
				</tr>
				<tr>
					<th scope="row"><label for="ai_custom_css">
					<?php _e('Custom Css:','aicontactform');?>
					</label></th>
					<td><textarea name="ai_custom_css" rows="5" cols="49" class="regular-text"><?php echo esc_attr(get_option('ai_custom_css'));?></textarea></td>
					<td><?php _e('<strong>Note:</strong> this CSS overrite to our contact plugin CSS And Also please use <strong>!important</strong> with your CSS property.','aicontactform');?></td>
				</tr>
				<tr>
					<td colspan="3"></td>
				</tr>
				<tr>
					<td colspan="3"><input class="button-primary" type="submit" value="<?php _e('Save All Changes','aicontactform');?>"></td>
				</tr>
				<tr>
					<td colspan="3" align="center"><p><?php _e('<strong>Note:</strong> You can add <strong> [ai_contact_form] </strong> shortcode where you want to display contact form in pages.','aicontactform');?>
					<?php  _e(' <br/> OR  You can add <strong> &lt;&#63;php do_shortcode("[ai_contact_form]"); &#63;&gt;</strong> shortcode in any template.','aicontactform');?>
					<?php  _e(' <br/> OR  You can add <strong> &lt;&#63;php echo do_shortcode("[ai_contact_form]"); &#63;&gt;</strong> shortcode in any template.','aicontactform');?></p></td>
				</tr>
				<tr>
					<td colspan="3"></td>
				</tr>
			</tbody>
		</table>
	</form>
</div>
