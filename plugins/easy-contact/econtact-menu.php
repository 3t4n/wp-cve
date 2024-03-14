<?php
/*
EASY CONTACT
by SCOTT ALLAN WALLICK, http://scottwallick.com/
from PLAINTXT.ORG, http://www.plaintxt.org/

This file is part of EASY CONTACT.

EASY CONTACT is free software: you can redistribute it and/or
modify it under the terms of the GNU General Public License as
published by the Free Software Foundation, either version 3 of
the License, or (at your option) any later version.

EASY CONTACT is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for details.

You should have received a copy of the GNU General Public License
along with EASY CONTACT. If not, see www.gnu.org/licenses/.
*/

// Allow localization, if applicable
load_plugin_textdomain('easy_contact');
// Let them donate, if they will
if ( !function_exists('plaintxt_plugin_donate') ) {
	// If another plaintxt.org plugin is initialized before this one, use its donate function instead
	function plaintxt_plugin_donate() {
		$button = '
			<form id="paypal" action="https://www.paypal.com/cgi-bin/webscr" method="post" style="float:left;margin:0.9em 0.5em 0 0;">
				<div id="donate">
					<input name="cmd" type="hidden" value="_s-xclick" />
					<input name="submit" src="https://www.paypal.com/en_US/i/btn/x-click-but04.gif" type="image" alt="' . __( 'PayPal: The safer, easier way to donate!', 'easy_contact' ) . '" />
					<img src="https://www.paypal.com/en_US/i/scr/pixel.gif" alt="' . __( 'Donate with PayPal', 'easy_contact' ) . '" width="1" height="1" border="0" />
					<input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----" />
				</div>
			</form>';
		// And let's spit out our button on command.
		echo $button . "\n";
	}
}
// Should you be doing this?
if ( !current_user_can('manage_options') ) {
	// Apparently not.
	die( __( 'ACCESS DENIED: Your don\'t have permission to do this.', 'easy_contact' ) );
} elseif ($_POST['action'] && $_POST['action'] == 'update' ) {
	// Did this $_POST come from our form?
	check_admin_referer('ec_save_options');
	// OK, now we can process data submitted from our form safely
	update_option( 'ec_challenge_a', strip_tags(stripslashes($_POST['challenge_a'])) );
	update_option( 'ec_challenge_q', stripslashes(wp_filter_post_kses($_POST['challenge_q'])) );
	update_option( 'ec_field_confirm', strip_tags(stripslashes($_POST['field_confirm'])) );
	update_option( 'ec_field_info', strip_tags(stripslashes($_POST['field_info'])) );
	update_option( 'ec_field_message', strip_tags(stripslashes($_POST['field_message'])) );
	update_option( 'ec_label_cc', stripslashes(wp_filter_post_kses($_POST['label_cc'])) );
	update_option( 'ec_label_email', stripslashes(wp_filter_post_kses($_POST['label_email'])) );
	update_option( 'ec_label_message', stripslashes(wp_filter_post_kses($_POST['label_message'])) );
	update_option( 'ec_label_name', stripslashes(wp_filter_post_kses($_POST['label_name'])) );
	update_option( 'ec_label_subject', stripslashes(wp_filter_post_kses($_POST['label_subject'])) );
	update_option( 'ec_label_website', stripslashes(wp_filter_post_kses($_POST['label_website'])) );
	update_option( 'ec_option_verf', strip_tags(stripslashes($_POST['option_verf'])) );
	update_option( 'ec_recipient_email', strip_tags(stripslashes($_POST['recipient_email'])) );
	update_option( 'ec_recipient_name', strip_tags(stripslashes($_POST['recipient_name'])) );
	update_option( 'ec_subject', strip_tags(stripslashes($_POST['subject'])) );
	update_option( 'ec_text_required', stripslashes(wp_filter_post_kses($_POST['text_required'])) );
	update_option( 'ec_text_submit', strip_tags(stripslashes($_POST['text_submit'])) );
	// Before we process certain data, we should know who we are dealing with
	if ( current_user_can('unfiltered_html') ) {
		// We'll allow unfiltered HTML from power users, whatever that means
		update_option( 'ec_msg_empty', stripslashes($_POST['msg_empty']) );
		update_option( 'ec_msg_incorrect', stripslashes($_POST['msg_incorrect']) );
		update_option( 'ec_msg_intro', stripslashes($_POST['msg_intro']) );
		update_option( 'ec_msg_invalid', stripslashes($_POST['msg_invalid']) );
		update_option( 'ec_msg_malicious', stripslashes($_POST['msg_malicious']) );
		update_option( 'ec_msg_success', stripslashes($_POST['msg_success']) );
	} else {
		// Otherwise, we're going to filter it more aggresively from powerless users
		update_option( 'ec_msg_empty', stripslashes(wp_filter_post_kses($_POST['msg_empty'])) );
		update_option( 'ec_msg_incorrect', stripslashes(wp_filter_post_kses($_POST['msg_incorrect'])) );
		update_option( 'ec_msg_intro', stripslashes(wp_filter_post_kses($_POST['msg_intro'])) );
		update_option( 'ec_msg_invalid', stripslashes(wp_filter_post_kses($_POST['msg_invalid'])) );
		update_option( 'ec_msg_malicious', stripslashes(wp_filter_post_kses($_POST['msg_malicious'])) );
		update_option( 'ec_msg_success', stripslashes(wp_filter_post_kses($_POST['msg_success'])) );
	}
	// Is our CC option ticked or unticked?
	if ( isset($_POST['option_cc'])) {
		// Ticked!
		update_option( 'ec_option_cc', true );
	} else {
		// Unticked!
		update_option( 'ec_option_cc', false );
	}
	// We'll give the option for uses of the old WPCF to delete those options
	if ( isset($_POST['remove_wpcf'])) {
		delete_option('wpcf_cc_option');
		delete_option('wpcf_email');
		delete_option('wpcf_error_msg');
		delete_option('wpcf_show_quicktag');
		delete_option('wpcf_show_spamcheck');
		delete_option('wpcf_subject');
		delete_option('wpcf_success_msg');
	}
	// If we've updated settings, show a message
	echo '<div id="message" class="updated fade"><p><strong>' . __( 'Settings saved.', 'easy_contact' ) . '</strong></p></div>';
}
// Let's get some variables for multiple instances
$option_verf = attribute_escape(get_option('ec_option_verf'));
$selected = ' selected="selected"';
// And we being the actual menu HTML
?>
<div class="wrap">
	<h2><?php _e( 'Easy Contact', 'easy_contact' ) ?></h2>
	<?php if ( function_exists('plaintxt_plugin_donate') ) plaintxt_plugin_donate(); // Let them donate. ?>
	<p><?php _e( 'Thank you for using this <a href="http://www.plaintxt.org/" title="plaintxt.org">plaintxt.org</a> plugin. When deactivating this plugin, its settings will be permanently deleted from you database.', 'exop' ) ?></p>
	<form name="ec_options_update" method="post" action="<?php echo $_SERVER['REQUEST_URI'] ?>">
		<?php wp_nonce_field('ec_save_options'); echo "\n"; // Very important. Makes sure form data is submitted from this page. Do not fool with this. ?>
		<h3><?php _e( 'Email Options', 'easy_contact' ) ?></h3>
		<p><?php _e( 'To recieve submitted emails, supply a valid address for email delivery. The email subject line is prefaced with any text supplied below.', 'easy_contact' ) ?></p>
		<table class="form-table" summary="<?php _e( 'Email Options', 'easy_contact' ) ?>">
			<tr valign="top">
				<th scope="row"><label for="recipient_email"><?php _e( 'Recipient Email', 'easy_contact' ) ?></label></th>
				<td>
					<fieldset>
						<legend class="hidden"><?php _e( 'Recipient Email Address', 'easy_contact' ) ?></legend>
						<input id="recipient_email" name="recipient_email" type="text" value="<?php echo attribute_escape(get_option('ec_recipient_email')) ?>" size="40" /><br />
						<?php _e( 'The email address that will receive submissions from this form.', 'easy_contact' ) ?>
					</fieldset>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="recipient_name"><?php _e( 'Recipient Name', 'easy_contact' ) ?></label></th>
				<td>
					<fieldset>
						<legend class="hidden"><?php _e( 'Recipient Name on Recipient Email', 'easy_contact' ) ?></legend>
						<input id="recipient_name" name="recipient_name" type="text" value="<?php echo attribute_escape(get_option('ec_recipient_name')) ?>" size="40" /><br />
						<?php _e( 'Your name as you would like it to appear in emails received from this form.', 'easy_contact' ) ?>
					</fieldset>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="subject"><?php _e( 'Subject Prefix', 'easy_contact' ) ?></label></th>
				<td>
					<fieldset>
						<legend class="hidden"><?php _e( 'Subject Prefix on Recipient Email', 'easy_contact' ) ?></legend>
						<input id="subject" name="subject" type="text" value="<?php echo attribute_escape(get_option('ec_subject')) ?>" size="40" /><br />
						<?php _e( 'This text appears before the subject as provided by the user on the form.', 'easy_contact' ) ?>
					</fieldset>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e( 'Carbon Copying', 'easy_contact' ) ?></th>
				<td>
					<fieldset>
						<legend class="hidden"><?php _e( 'Carbon Copying Option', 'easy_contact' ) ?></legend>
						<input id="option_cc" name="option_cc" type="checkbox" value="option_cc" <?php if ( get_option('ec_option_cc') == true ) echo 'checked="checked"'; ?> />
						<label for="option_cc"><?php _e( 'Allow users to carbon copy (<abbr title="Carbon Copy">CC</abbr>) emails to themselves', 'easy_contact' ) ?></label>
					</fieldset>
				</td>
			</tr>
		</table>
		<h3><?php _e( 'Spam Reduction Options', 'easy_contact' ) ?></h3>
		<p><?php _e( 'Two options are available to help reduced spam: a simple math question (e.g., What is the sum of 50 and 10?) and/or a challenge question you provide below.', 'easy_contact' ) ?></p>
		<table class="form-table" summary="<?php _e( 'Spam Reduction Options', 'easy_contact' ) ?>">
			<tr valign="top">
				<th scope="row"><label for="option_verf"><?php _e( 'Verfication Type', 'easy_contact' ) ?></label></th>
				<td>
					<fieldset>
						<legend class="hidden"><?php _e( 'Spam Reduction Verification Type', 'easy_contact' ) ?></legend>
						<select name="option_verf" id="option_verf">
							<option value="1"<?php if ( $option_verf == 1 ) echo $selected; ?>> <?php _e( 'Disabled', 'easy_contact' ) ?> </option>
							<option value="2"<?php if ( $option_verf == 2 ) echo $selected; ?>> <?php _e( 'Challenge Question', 'easy_contact' ) ?> </option>
							<option value="3"<?php if ( $option_verf == 3 ) echo $selected; ?>> <?php _e( 'Simple Math', 'easy_contact' ) ?> </option>
							<option value="4"<?php if ( $option_verf == 4 ) echo $selected; ?>> <?php _e( 'Math &amp; Challenge', 'easy_contact' ) ?> </option>
						</select>
					</fieldset>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e( 'Challenge Q&amp;A', 'easy_contact' ) ?></th>
				<td>
					<fieldset>
						<legend class="hidden"><?php _e( 'Challenge Question and Answer', 'easy_contact' ) ?></legend>
							<input id="challenge_q" name="challenge_q" type="text" value="<?php echo attribute_escape(get_option('ec_challenge_q')) ?>" size="40" /> <label for="challenge_q"><?php _e( 'Question', 'easy_contact' ) ?></label><br />
							<input id="challenge_a" name="challenge_a" type="text" value="<?php echo attribute_escape(get_option('ec_challenge_a')) ?>" size="40" maxlength="20" /> <label for="challenge_a"><?php _e( 'Answer', 'easy_contact' ) ?></label><br />
							<?php _e( 'Validation of the answer is not case sensitive.', 'easy_contact' ) ?>
					</fieldset>
				</td>
			</tr>
		</table>
		<h3><?php _e( 'Form Legends', 'easy_contact' ) ?></h3>
		<p><?php _e( 'Legends structure the form and are necessary for semantic <abbr title="eXtensible Hyptertext Markup Language">XHTML</abbr>.', 'easy_contact' ) ?></p>
		<table class="form-table" summary="<?php _e( 'Form Legends text options', 'easy_contact' ) ?>">
			<tr valign="top">
				<th scope="row"><label for="field_info"><?php _e( 'Info Legend', 'easy_contact' ) ?></label></th>
				<td>
					<fieldset>
						<legend class="hidden"><?php _e( 'Information Legend Text', 'easy_contact' ) ?></legend>
						<input id="field_info" name="field_info" type="text" value="<?php echo attribute_escape(get_option('ec_field_info')) ?>" size="40" />
					</fieldset>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="field_message"><?php _e( 'Message Legend', 'easy_contact' ) ?></label></th>
				<td>
					<fieldset>
						<legend class="hidden"><?php _e( 'Message Legend Text', 'easy_contact' ) ?></legend>
						<input id="field_message" name="field_message" type="text" value="<?php echo attribute_escape(get_option('ec_field_message')) ?>" size="40" />
					</fieldset>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="field_confirm"><?php _e( 'Confirmation Legend', 'easy_contact' ) ?></label></th>
				<td>
					<fieldset>
						<legend class="hidden"><?php _e( 'Cofirmation Legend Text', 'easy_contact' ) ?></legend>
						<input id="field_confirm" name="field_confirm" type="text" value="<?php echo attribute_escape(get_option('ec_field_confirm')) ?>" size="40" />
					</fieldset>
				</td>
			</tr>
		</table>
		<h3><?php _e( 'Form Labels', 'easy_contact' ) ?></h3>
		<p><?php _e( 'Labels correlate to specific input fields and are necessary for semantic <abbr title="eXtensible Hyptertext Markup Language">XHTML</abbr>.', 'easy_contact' ) ?></p>
		<table class="form-table" summary="<?php _e( 'Form Label text options', 'easy_contact' ) ?>">
			<tr valign="top">
				<th scope="row"><label for="label_name"><?php _e( 'Name Input Label', 'easy_contact' ) ?></label></th>
				<td>
					<fieldset>
						<legend class="hidden"><?php _e( 'Name Input Label Text', 'easy_contact' ) ?></legend>
						<input id="label_name" name="label_name" type="text" value="<?php echo attribute_escape(get_option('ec_label_name')) ?>" size="40" />
					</fieldset>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="label_email"><?php _e( 'Email Input Label', 'easy_contact' ) ?></label></th>
				<td>
					<fieldset>
						<legend class="hidden"><?php _e( 'Email Input Label Text', 'easy_contact' ) ?></legend>
						<input id="label_email" name="label_email" type="text" value="<?php echo attribute_escape(get_option('ec_label_email')) ?>" size="40" />
					</fieldset>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="label_website"><?php _e( 'Website Input Label', 'easy_contact' ) ?></label></th>
				<td>
					<fieldset>
						<legend class="hidden"><?php _e( 'Website Input Label Text', 'easy_contact' ) ?></legend>
						<input id="label_website" name="label_website" type="text" value="<?php echo attribute_escape(get_option('ec_label_website')) ?>" size="40" />
					</fieldset>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="label_subject"><?php _e( 'Subject Input Label', 'easy_contact' ) ?></label></th>
				<td>
					<fieldset>
						<legend class="hidden"><?php _e( 'Subject Input Label Text', 'easy_contact' ) ?></legend>
						<input id="label_subject" name="label_subject" type="text" value="<?php echo attribute_escape(get_option('ec_label_subject')) ?>" size="40" />
					</fieldset>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="label_message"><?php _e( 'Message Input Label', 'easy_contact' ) ?></label></th>
				<td>
					<fieldset>
						<legend class="hidden"><?php _e( 'Message Input Label Text', 'easy_contact' ) ?></legend>
						<input id="label_message" name="label_message" type="text" value="<?php echo attribute_escape(get_option('ec_label_message')) ?>" size="40" />
					</fieldset>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="label_cc"><?php _e( 'Carbon Copy Label', 'easy_contact' ) ?></label></th>
				<td>
					<fieldset>
						<legend class="hidden"><?php _e( 'Carbon Copy Label Text', 'easy_contact' ) ?></legend>
						<input id="label_cc" name="label_cc" type="text" value="<?php echo attribute_escape(get_option('ec_label_cc')) ?>" size="40" />
					</fieldset>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="text_submit"><?php _e( 'Submit Button Text', 'easy_contact' ) ?></label></th>
				<td>
					<fieldset>
						<legend class="hidden"><?php _e( 'Submit Button Text', 'easy_contact' ) ?></legend>
						<input id="text_submit" name="text_submit" type="text" value="<?php echo attribute_escape(get_option('ec_text_submit')) ?>" size="40" />
					</fieldset>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="text_required"><?php _e( 'Required Text', 'easy_contact' ) ?></label></th>
				<td>
					<fieldset>
						<legend class="hidden"><?php _e( 'Required Text', 'easy_contact' ) ?></legend>
						<input id="text_required" name="text_required" type="text" value="<?php echo attribute_escape(get_option('ec_text_required')) ?>" size="40" class="code" /><br />
						<?php _e( 'Appends each required required field label: name, email, and subject.', 'easy_contact' ) ?>
					</fieldset>
				</td>
			</tr>
		</table>
		<h3><?php _e( 'Response Messages', 'easy_contact' ) ?></h3>
		<p><?php _e( 'After form submission, a response message prompts to correct submitted date or informs that the message was sent successfully. <abbr title="eXtensible Hyptertext Markup Language">XHTML</abbr> elements are allowed.', 'easy_contact' ) ?></p>
		<table class="form-table" summary="<?php _e( 'Response Messages', 'easy_contact' ) ?>">
			<tr valign="top">
				<th scope="row"><?php _e( 'Intro', 'easy_contact' ) ?></th>
				<td>
					<fieldset>
						<legend class="hidden"><?php _e( 'Introductory Message', 'easy_contact' ) ?></legend>
						<p><label for="msg_intro"><?php _e( 'When the form is initially loaded, this introductory message initially prompts users.', 'easy_contact' ) ?></label></p>
						<p><textarea name="msg_intro" cols="60" rows="5" id="msg_intro" style="width:98%;font-size:12px;" class="code"><?php echo format_to_edit(get_option('ec_msg_intro')) ?></textarea></p>
					</fieldset>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e( 'Success', 'easy_contact' ) ?></th>
				<td>
					<fieldset>
						<legend class="hidden"><?php _e( 'Submission Success Response', 'easy_contact' ) ?></legend>
						<p><label for="msg_success"><?php _e( 'If the form has been submimtted successfully, users are informed with this message.', 'easy_contact' ) ?></label></p>
						<p><textarea name="msg_success" cols="60" rows="5" id="msg_success" style="width:98%;font-size:12px;" class="code"><?php echo format_to_edit(get_option('ec_msg_success')) ?></textarea></p>
					</fieldset>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e( 'Empty', 'easy_contact' ) ?></th>
				<td>
					<fieldset>
						<legend class="hidden"><?php _e( 'Empty Field Response', 'easy_contact' ) ?></legend>
						<p><label for="msg_empty"><?php _e( 'If a required field is left blank, users are prompted to complete all required fields.', 'easy_contact' ) ?></label></p>
						<p><textarea name="msg_empty" cols="60" rows="5" id="msg_empty" style="width:98%;font-size:12px;" class="code"><?php echo format_to_edit(get_option('ec_msg_empty')) ?></textarea></p>
					</fieldset>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e( 'Incorrect', 'easy_contact' ) ?></th>
				<td>
					<fieldset>
						<legend class="hidden"><?php _e( 'Incorrect Answer Response', 'easy_contact' ) ?></legend>
						<p><label for="msg_incorrect"><?php _e( 'If spam reduction option(s) are enabled and the user provides an incorrect answer to either, this message prompts them to try again and to make sure cookies are enabled.', 'easy_contact' ) ?></label></p>
						<p><textarea name="msg_incorrect" cols="60" rows="5" id="msg_incorrect" style="width:98%;font-size:12px;" class="code"><?php echo format_to_edit(get_option('ec_msg_incorrect')) ?></textarea></p>
					</fieldset>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e( 'Invalid Email', 'easy_contact' ) ?></th>
				<td>
					<fieldset>
						<legend class="hidden"><?php _e( 'Invalid Email Response', 'easy_contact' ) ?></legend>
						<p><label for="msg_invalid"><?php _e( 'The email address provided is validated using the WordPress <code>is_email()</code> function. If the email address does not validate, this message prompts users to correct it.', 'easy_contact' ) ?></label></p>
						<p><textarea name="msg_invalid" cols="60" rows="5" id="msg_invalid" style="width:98%;font-size:12px;" class="code"><?php echo format_to_edit(get_option('ec_msg_invalid')) ?></textarea></p>
					</fieldset>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e( 'Malicious', 'easy_contact' ) ?></th>
				<td>
					<fieldset>
						<legend class="hidden"><?php _e( 'Malicious Input Response', 'easy_contact' ) ?></legend>
						<p><label for="msg_malicious"><?php _e( 'If malicious code is submitted, this message prompts users that HTML, special characters, and other tags are prohibited.', 'easy_contact' ) ?></label></p>
						<p><textarea name="msg_malicious" cols="60" rows="5" id="msg_malicious" style="width:98%;font-size:12px;" class="code"><?php echo format_to_edit(get_option('ec_msg_malicious')) ?></textarea></p>
					</fieldset>
				</td>
			</tr>
		</table>
<?php $test = get_option('wpcf_email'); if ($test) { ?>
		<h3><?php _e( 'Remove <abbr title="WordPress Contact Form">WPCF</abbr> Options', 'easy_contact' ) ?></h3>
		<p><?php _e( 'If you used the old Boren <abbr title="WordPress Contact Form">WPCF</abbr> or other <abbr title="WordPress Contact Form">WPCF</abbr>-based contact forms, you might want to remove those entries from your database if you are not planning on using those older plugins.', 'easy_contact' ) ?></p>
		<table class="form-table" summary="<?php _e( 'Remove WPCF Options', 'easy_contact' ) ?>">
			<tr>
				<th scope="row"><?php _e( 'Delete WPCF options', 'easy_contact' ) ?></th>
				<td><input id="remove_wpcf" name="remove_wpcf" type="checkbox" value="remove_wpcf" /> <label for="option_cc"><?php _e( 'Check to delete <abbr title="WordPress Contact Form">WPCF</abbr> settings. <strong>Warning:</strong> This cannot be undone.', 'easy_contact' ) ?></label></td>
			</tr>
		</table>
<?php } ?>
		<p class="submit">
			<!-- You can use the access key S to save options -->
			<input id="update" name="update" type="submit" value="<?php _e( 'Save Changes', 'easy_contact' ) ?>" accesskey="S" />
			<input name="action" type="hidden" value="update" />
			<input name="page_options" type="hidden" value="challenge_a,challenge_q,field_info,field_message,field_confirm,label_cc,label_email,label_message,label_name,label_subject,label_website,msg_empty,msg_incorrect,msg_intro,msg_invalid,msg_malicious,msg_success,option_verf,recipient_email,recipient_name,subject,text_required,option_cc,remove_wpcf" />
		</p>
	</form>
</div>