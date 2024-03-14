<?php
/*
PLUGIN NAME: Easy Contact
PLUGIN URI: http://www.plaintxt.org/experiments/easy-contact/
DESCRIPTION: Easy Contact is a simple, semantic contact form that utilizes the <a href="http://www.plaintxt.org/themes/sandbox/">Sandbox</a> design patterns. Insert using <code>[easy-contact]</code>. A plaintxt.org experiment for WordPress.
AUTHOR: Scott Allan Wallick
AUTHOR URI: http://scottwallick.com/
VERSION: 0.1.2 &beta;
*/

/*
EASY CONTACT
by SCOTT ALLAN WALLICK, http://scottwallick.com/
from PLAINTXT.ORG, http://www.plaintxt.org/

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

// We begin by stating our initial form inputs, cleaning values if the form data is coming back at us
$ec_form_fields = array(
	'name'         =>  '<input id="ec_name" name="ec_name" class="text required" type="text" value="' . strip_tags(stripslashes($_POST['ec_name'])) . '" size="30" maxlength="50" />',
	'email'        =>  '<input id="ec_email" name="ec_email" class="text required" type="text" value="' . strip_tags(stripslashes($_POST['ec_email'])) . '" size="30" maxlength="50" />',
	'url'          =>  '<input id="ec_url" name="ec_url" class="text optional" type="text" value="' . strip_tags(stripslashes($_POST['ec_url'])) . '" size="30" maxlength="50" />',
	'subject'      =>  '<input id="ec_subject" name="ec_subject" class="text required" type="text" value="' . strip_tags(stripslashes($_POST['ec_subject'])) . '" size="30" maxlength="50" />',
	'message'      =>  '<textarea id="ec_message" name="ec_message" class="text required" cols="40" rows="8">' . strip_tags(stripslashes($_POST['ec_message'])) . '</textarea>',
	'math_a'       =>  '<input id="ec_math_a" name="ec_math_a" class="text required" type="text" value="" size="30" maxlength="50" />',
	'challenge_a'  =>  '<input id="ec_challenge_a" name="ec_challenge_a" class="text required" type="text" value="" size="30" maxlength="50" />',
	'option_cc'    =>  '<input id="ec_option_cc" name="ec_option_cc" class="check optional" type="checkbox" value="true" />',
	'error'        =>  ''
);
// Tracks and validates our challenge question
function ec_is_challenge($input) {
	$is_challenge = false;
	// Let's make the user response case insensitive
	$answer = strtolower(stripslashes(get_option('ec_challenge_a')));
	$input  = strtolower($input);
	if ( $input == $answer ) {
		// Do we have a winner?
		$is_challenge = true;
	}
	return $is_challenge;
}
// We should respond appropriately to malicious code in our form
function ec_is_malicious($input) {
	$is_malicious = false;
	// Content we will consider malicious to check for
	$bad_inputs = array( "\r", "\n", "mime-version", "content-type", "bcc:", "cc:", "to:", "<", ">", "&lt;", "&rt;", "a href", "/a", "http:", "/URL", "URL=" );
	// And if we have some nasty input . . .
	foreach ( $bad_inputs as $bad_input ) {
		if ( strpos(strtolower($input), strtolower($bad_input) ) !== false ) {
			$is_malicious = true;
			// Boom!
			break;
		}
	}
	// Houston, we have a problem.
	return $is_malicious;
}
// Get the user IP for tracking
function ec_get_ip() {
	if ( isset($_SERVER) ) {
		if ( isset( $_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} elseif ( isset( $_SERVER['HTTP_CLIENT_IP'])) {
			$ip_address = $_SERVER['HTTP_CLIENT_IP'];
		} else {
			$ip_address = $_SERVER['REMOTE_ADDR'];
		}
	} else {
		if ( getenv('HTTP_X_FORWARDED_FOR') ) {
			$ip_address = getenv('HTTP_X_FORWARDED_FOR');
		} elseif ( getenv('HTTP_CLIENT_IP') ) {
			$ip_address = getenv('HTTP_CLIENT_IP');
		} else {
			$ip_address = getenv('REMOTE_ADDR');
		}
	}
	// Return the IP address
	return $ip_address;
}
// If refered from a search query, we'd like to know
function ec_get_query($query) {
	if ( strpos( $query, "google." ) ) {
		$pattern = '/^.*\/search\?.*q=(.*)$/';
	} elseif ( strpos( $query, "msn." ) || strpos( $query, "live" ) ) {
		$pattern = '/^.*q=(.*)$/';
	} elseif ( strpos( $query, "yahoo." ) ) {
		$pattern = '/^.*[\?&]p=(.*)$/';
	} elseif ( strpos( $query, "ask." ) ) {
		$pattern = '/^.*[\?&]q=(.*)$/';
	} else {
		return false;
	}
	preg_match( $pattern, $query, $matches );
	$querystr = substr( $matches[1], 0, strpos( $matches[1], '&' ) );
	return urldecode($querystr);
}
// Tracks the session for (potential) form submission
function ec_session_referer($unused) {
	if ( !isset( $_SESSION ) ) {
		session_start();
	}
	if ( !isset( $_SESSION['orig_referer'])) {
		$_SESSION['orig_referer'] = $_SERVER['HTTP_REFERER'];
	}
}
// Process input from the form
function ec_check_input() {
	// Let's break the form if something dodgy is happening
	if ( !( isset( $_POST['ec_stage'])) ) {
		// Break if form data wasn't submitted from our page
		return false;
	} elseif ( !( isset( $_POST['ec_orig_referer'])) ) {
		// Break if form data wasn't submitted from our page
		return false;
	}
	// Keep our form data clean
	$_POST['ec_name']     =  stripslashes(trim($_POST['ec_name']));
	$_POST['ec_email']    =  stripslashes(trim($_POST['ec_email']));
	$_POST['ec_url']      =  stripslashes(trim($_POST['ec_url']));
	$_POST['ec_subject']  =  stripslashes(trim($_POST['ec_subject']));
	$_POST['ec_message']  =  stripslashes(trim($_POST['ec_message']));
	// Carry over the form data
	global $ec_form_fields;
	// Clear any prior errors
	$proceed = true;
	// Do not proceed reasons
	// 1: empty field
	// 2: invalid email
	// 3: incorrect answer
	// 4: malicious code
	// Check the name input for problems.
	if ( empty($_POST['ec_name'] )) {
		$proceed = false;
		$reason = 1;
		$ec_form_fields['name'] = '<input id="ec_name" name="ec_name" class="text required error" type="text" value="' . $_POST['ec_name'] . '" size="30" maxlength="20" />';
	}
	// Check the e-mail input for problems.
	if ( !is_email($_POST['ec_email'])) {
		$proceed = false;
		$reason = 2;
		$ec_form_fields['email'] = '<input id="ec_email" name="ec_email" class="text required error" type="text" value="' . $_POST['ec_email'] . '" size="30" maxlength="50" />';
	}
	// Check the subject input for problems.
	if ( empty($_POST['ec_subject'])) {
		$proceed = false;
		$reason = 1;
		$ec_form_fields['subject'] = '<input id="ec_subject" name="ec_subject" class="text required error" type="text" value="' . $_POST['ec_subject'] . '" size="30" maxlength="50" />';
	}
	// Check the message input for problems.
	if ( empty( $_POST['ec_message'])) {
		$proceed = false;
		$reason = 1;
		$ec_form_fields['message'] = '<textarea id="ec_message" name="ec_message" class="text required error" cols="40" rows="8">' . $_POST['ec_message'] . '</textarea>';
	}
	// Do we have a spam-reduction option enabled?
	$option_verf = get_option('ec_option_verf');
	// We're using a challenge question, so check its answer
	if ( $option_verf == 2 || $option_verf == 4 ) {
		if ( !ec_is_challenge($_POST['ec_challenge_a']) ) {
			$proceed = false;
			$reason = 3;
		}
	}
	// We're using a math-based question, so check its answer
	if ( $option_verf == 3 || $option_verf == 4 ) {
		if ( $_SESSION['check_math'] != $_POST['ec_math_a'] ) {
			$proceed = false;
			$reason = 3;
		}
	}
	// Check the input for any malicious code
	if ( ec_is_malicious( $_POST['ec_name'] ) || ec_is_malicious( $_POST['ec_email'] ) || ec_is_malicious( $_POST['ec_subject'])) {
		$proceed = false;
		$reason = 4;
	}
	// Now we see if we have a problem
	if ( $proceed == true ) {
		// No problem? Move along.
		return true;
	} else {
		// A problem? Give an appropriate response message.
		if ( $reason == 1 ) {
			$ec_form_fields['error'] = get_option('ec_msg_empty');
		} elseif ( $reason == 2 ) {
			$ec_form_fields['error'] = get_option('ec_msg_invalid');
		} elseif ( $reason == 3 ) {
			$ec_form_fields['error'] = get_option('ec_msg_incorrect');
		} elseif ( $reason == 4 ) {
			$ec_form_fields['error'] = get_option('ec_msg_malicious');
		}
		// If we have an error, but no corresponding message, something has gone wrong. Let's break the process
		return false;
	}
}
// Finds [easy-contact] shortcode and produces the form in the content
function ec_shortcode($attr) {
	global $ec_form_fields;
	// If we're processing $POST data without a problem, let's send an email
	if ( ec_check_input() ) {
		// Let's get some variables we're going to use multiple times
		$cc_option     =  get_option('ec_option_cc');
		$recipient     =  attribute_escape(get_option('ec_recipient_name')) . ' <' . attribute_escape(get_option('ec_recipient_email')) . '>';
		$user          =  strip_tags(trim($_POST['ec_name'])) . ' <' . strip_tags(trim($_POST['ec_email'])) . '>';
		$orig_referer  =  strip_tags(trim($_POST['ec_orig_referer']));
		$keywords      =  ec_get_query($orig_referer);
		// Start our email with its headers
		$headers       =  "MIME-Version: 1.0\r\n";
		// Our form has to match the encoding the user is typing it in, i.e., your blog charset
		$headers      .=  'Content-Type: text/plain; charset="' . get_option('blog_charset') . "\"\r\n";
		// Our generic mailer-daemon is just going to be WordPress@EXAMPLE.COM, where EXAMPLE.COM is your domain in lowercase
		$sitename      =  strtolower($_SERVER['SERVER_NAME']);
		// If we have the www., let's drop it safely
		if ( substr( $sitename, 0, 4 ) == 'www.' ) {
			$sitename  =  substr( $sitename, 4 );
		}
		// Our from email address
		$from_email    =  apply_filters( "wp_mail_from", "wordpress@$sitename" );
		// Our from email name
		$from_name     =  apply_filters( 'wp_mail_from_name', 'WordPress' );
		// And we begin the headers
		$headers      .=  "From: $from_name <$from_email>\r\n";
		$headers      .=  "Reply-To: $user\r\n";
		// Since we allow CC'ing, we can smartly only send one email
		if ( $cc_option && $_POST['ec_option_cc'] ) {
			// If CC'ing, we'll BCC: you and TO: the user.
			$to        =  $user;
			$headers  .=  "Bcc: $recipient\r\n";
		} else {
			// If no, just TO: you.
			$to        =  $recipient;
		}
		// We should include X data for the mailer version
		$headers      .=  'X-Mailer: PHP/' . phpversion() . "\r\n";
		// Build our subject line fo the email
		$subject       =  attribute_escape(get_option('ec_subject')) . ' ' . $_POST['ec_subject'];
		// And our actual message with extra stuff
		$message       =  strip_tags(trim($_POST['ec_message'])) . "\n\n---\n";
		$message      .=  __( 'Website: ', 'easy_contact' ) . strip_tags(trim($_POST['ec_url'])) . "\n\n---\n";
		$message      .=  __( 'IP address: ', 'easy_contact' ) . 'http://ws.arin.net/whois/?queryinput=' . ec_get_ip() . "\n";
		// Don't show keywords in the email unless we have some
		if ($keywords) {
			$message  .=  __( 'Keywords: ', 'easy_contact' ) . $keywords . "\n";
		}
		$message      .=  __( 'Form referrer: ', 'easy_contact' ) . strip_tags(trim($_POST['ec_referer'])) . "\n";
		$message      .=  __( 'Orig. referrer: ', 'easy_contact' ) . $orig_referer . "\n";
		$message      .=  __( 'User agent: ', 'easy_contact' ) . trim($_SERVER['HTTP_USER_AGENT']) . "\n";
		// Let's build our email and send it
		mail( $to, $subject, $message, $headers );
		// And then build the response message output for the user
		$output = "\n" . '<div class="formcontainer">' . "\n\t" . stripslashes(get_option('ec_msg_success')) . "\n</div>";
		// Returns the success message to the user
		return $output;
	// Otherwise, let's build us a form
	} else {
		// We begin our form.
		$form = '<div class="formcontainer">';
		// If we have an error, display it
		if ( $ec_form_fields['error'] != null ) {
			// Display an error message first. (We're applying our own easy_contact_text filter to prettify the message.)
			$form .= "\n\t" . apply_filters( 'easy_contact_text', stripslashes($ec_form_fields['error']) );
		} else {
			// Otherwise, display the intro message. (Also, filter this too.)
			$form .= "\n\t" . apply_filters( 'easy_contact_text', stripslashes(get_option('ec_msg_intro')) );
		}
		// Gather variables for multiple uses
		$required = stripslashes(get_option('ec_text_required'));
		$option_verf = get_option('ec_option_verf');
		// And continuing with our form
		$form .= '
	<form class="contact-form" action="' . get_permalink() . '" method="post">
		<fieldset>
			<legend>' . attribute_escape(get_option('ec_field_info')) . '</legend>
			<div class="form-label"><label for="ec_name">' . stripslashes(wp_filter_post_kses(get_option('ec_label_name'))) . ' ' . $required . '</label></div>
			<div class="form-input">' . $ec_form_fields['name'] . '</div>
			<div class="form-label"><label for="ec_email">' . stripslashes(wp_filter_post_kses(get_option('ec_label_email'))) . ' ' . $required . '</label></div>
			<div class="form-input">' . $ec_form_fields['email'] . '</div>
			<div class="form-label"><label for="ec_url">' . stripslashes(wp_filter_post_kses(get_option('ec_label_website'))) . '</label></div>
			<div class="form-input">' . $ec_form_fields['url'] . '</div>
		</fieldset>
		<fieldset>
			<legend>' . attribute_escape(get_option('ec_field_message')) . '</legend>
			<div class="form-label"><label for="ec_subject">' . stripslashes(wp_filter_post_kses(get_option('ec_label_subject'))) . ' ' . $required . '</label></div>
			<div class="form-input">' . $ec_form_fields['subject'] . '</div>
			<div class="form-label"><label for="ec_message">' . stripslashes(wp_filter_post_kses(get_option('ec_label_message'))) . ' ' . $required . '</label></div>
			<div class="form-textarea">' . $ec_form_fields['message'] . '</div>
		</fieldset>
		<fieldset>
			<legend>' . attribute_escape(get_option('ec_field_confirm')) . '</legend>';
		// If employing a spam-reduction question, insert it
		if ( $option_verf > 1 ) {
			if ( $option_verf == 2 || $option_verf == 4 ) {
				$form .= '
			<div class="form-label"><label for="ec_challenge_a">' . apply_filters( 'easy_contact_text', stripslashes(wp_filter_post_kses(get_option('ec_challenge_q'))) ) . ' ' . $required . '</label></div>
			<div class="form-input">' . $ec_form_fields['challenge_a'] . '</div>';
			}
			if ( $option_verf == 3 || $option_verf == 4 ) {
				// Big number is between 1 and 1,000.
				$big = rand( 1, 1000 );
				// Small number is between 1 and 10.
				$small = rand( 1, 10 );
				// We need to remember this session to validate the answer
				$_SESSION['check_math'] = $big+$small;
				$form .= '
			<div class="form-label"><label for="ec_math_a">' . __( "What is the sum of $big and $small?", "easy_contact" ) . ' ' . $required . '</label></div>
			<div class="form-input">' . $ec_form_fields['math_a'] . '</div>';
			}
		}
		// If the user can CC themselves, give them a box to tick
		if ( get_option('ec_option_cc') == true ) {
			$form .= '
			<div class="form-option">' . $ec_form_fields['option_cc'] . ' <label for="ec_option_cc">' . apply_filters( 'easy_contact_text', stripslashes(wp_filter_post_kses(get_option('ec_label_cc'))) ) . '</label></div>';
		}
		// Let's finish up with this form.
		$form .= '
			<div class="form-submit">
				<input type="submit" name="submit" class="button" value="' . attribute_escape(get_option('ec_text_submit')) . '" />
				<input type="hidden" name="ec_stage" value="process" />
				<input type="hidden" name="ec_referer" value="' . wp_specialchars( $_SERVER['HTTP_REFERER'], 1 ) . '" />
				<input type="hidden" name="ec_orig_referer" value="' . wp_specialchars( $_SESSION['orig_referer'], 1 ) . '" />
			</div>
		</fieldset>
	</form>
</div>';
		// The output is the form.
		$output = $form;
		// So output it.
		return $output;
	}
}
// Add default options upon activation
function ec_activation() {
	// A default setting, load the admin email
	$new_opt = get_bloginfo('admin_email');
	add_option( "ec_recipient_email", "$new_opt", "", "yes" );
	// A default setting, use the blog name for the subject line
	$new_opt = get_bloginfo('name');
	add_option( "ec_subject", "[$new_opt]", "", "yes" );
	add_option( 'ec_challenge_a', __( 'Paris', 'easy_contact' ), '', 'yes' );
	add_option( 'ec_challenge_q', __( 'What is the capital of France?', 'easy_contact' ), '', 'yes' );
	add_option( 'ec_field_confirm', __( 'Confirmation', 'easy_contact' ), '', 'yes' );
	add_option( 'ec_field_info', __( 'Your information', 'easy_contact' ), '', 'yes' );
	add_option( 'ec_field_message', __( 'Your message', 'easy_contact' ), '', 'yes' );
	add_option( 'ec_label_cc', __( 'Email yourself a copy?', 'easy_contact' ), '', 'yes' );
	add_option( 'ec_label_email', __( 'Email', 'easy_contact' ), '', 'yes' );
	add_option( 'ec_label_message', __( 'Message', 'easy_contact' ), '', 'yes' );
	add_option( 'ec_label_name', __( 'Name', 'easy_contact' ), '', 'yes' );
	add_option( 'ec_label_subject', __( 'Subject', 'easy_contact' ), '', 'yes' );
	add_option( 'ec_label_website', __( 'Website', 'easy_contact' ), '', 'yes' );
	add_option( 'ec_msg_empty', __( '<p class="error">Please complete all required fields.</p>', 'easy_contact' ), '', 'yes' );
	add_option( 'ec_msg_incorrect', __( '<p class="important">Your answer is incorrect. <em>Cookies must be enabled to validate your response.</em></p>', 'easy_contact' ), '', 'yes' );
	add_option( 'ec_msg_intro', __( '<p class="information">Required fields are marked <span class="required">*</span>.</p>', 'easy_contact' ), '', 'yes' );
	add_option( 'ec_msg_invalid', __( '<p class="error">Please provide a valid email address.</p>', 'easy_contact' ), '', 'yes' );
	add_option( 'ec_msg_malicious', __( '<p class="important">Potentially malicious content was detected. You may not use <abbr title="HyperText Markup Language">HTML</abbr> or other code, including <code>&lt;</code> and <code>&gt;</code>.</p>', 'easy_contact' ), '', 'yes' );
	add_option( 'ec_msg_success', __( '<p class="success">Your email was sent successfully.</p>',  'easy_contact' ), '', 'yes' );
	add_option( 'ec_option_cc', true, '', 'yes' );
	add_option( 'ec_option_verf', 1, '', 'yes' );
	add_option( 'ec_recipient_name', __( 'Administrator', 'easy_contact' ), '', 'yes' );
	add_option( 'ec_text_required', __( '<span class="required">*</span>', 'easy_contact' ), '', 'yes' );
	add_option( 'ec_text_submit', __( 'Submit', 'easy_contact' ), '', 'yes' );
}
// Delete options upon deactivation
function ec_deactivation() {
	delete_option('ec_challenge_a');
	delete_option('ec_challenge_q');
	delete_option('ec_field_confirm');
	delete_option('ec_field_info');
	delete_option('ec_field_message');
	delete_option('ec_label_cc');
	delete_option('ec_label_email');
	delete_option('ec_label_message');
	delete_option('ec_label_name');
	delete_option('ec_label_subject');
	delete_option('ec_label_website');
	delete_option('ec_msg_empty');
	delete_option('ec_msg_incorrect');
	delete_option('ec_msg_intro');
	delete_option('ec_msg_invalid');
	delete_option('ec_msg_malicious');
	delete_option('ec_msg_success');
	delete_option('ec_option_cc');
	delete_option('ec_option_stylesheet');
	delete_option('ec_option_stylesheet_page');
	delete_option('ec_option_verf');
	delete_option('ec_recipient_email');
	delete_option('ec_recipient_name');
	delete_option('ec_subject');
	delete_option('ec_text_required');
	delete_option('ec_text_submit');
	delete_option('ec_version');
}
// Adds our options submenu
function ec_initialize() {
	// But only if that function actually exists
	if ( function_exists('add_options_page') ) {
		// We'll use a longer title for the TITLE element and a shorter one for the options page link
		add_options_page( __( 'Easy Contact', 'easy_contact' ), __( 'Contact', 'easy_contact' ), 'manage_options', 'easy-contact/econtact-menu.php', '' );
	}
}
// Initialize the session referer to track users
add_action( 'init', 'ec_session_referer' );
// Register the shortcode to the function ec_shortcode()
add_shortcode( 'easy-contact', 'ec_shortcode' );
// Register hooks for activation/deactivation. (I'm tidy.)
register_activation_hook( __FILE__, 'ec_activation' );
register_deactivation_hook( __FILE__, 'ec_deactivation' );
// Allow localization, if applicable
load_plugin_textdomain( 'easy_contact', false, 'easy-contact' );
// Register the options menu
add_action( 'admin_menu', 'ec_initialize' );
// Let's filter certain text in the form to help make it pretty
add_filter( 'easy_contact_text', 'wptexturize' );
add_filter( 'easy_contact_text', 'convert_chars' );
?>