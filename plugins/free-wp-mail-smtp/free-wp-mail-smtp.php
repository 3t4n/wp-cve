<?php
/*
Plugin Name: Free WP Mail SMTP
Version: 1.0
Description: Reconfigures the wp_mail() function to use SMTP instead of mail() and creates an options page to manage the settings. It uses Mail250 email service instead of your default hosting provider's mail settings.
Author: mail250
*/

/**
 * @author mail250
 * @copyright mail250, 2019, All Rights Reserved
 * This code is released under the GPL licence version 3 or later, available here
 * http://www.gnu.org/licenses/gpl.txt
 */

/**
 * Setting options in wp-config.php
 */
 
// Array of options and their default values
global $wpms_options;
$wpms_options = array (
	'wpp_api_key' => '',
	'wpp_mail_from' => '',
	'wpp_mail_from_name' => '',
	'wpp_mailer' => 'smtp',
	'wpp_mail_set_return_path' => 'false',
	'wpp_smtp_host' => 'smtp.swipemail.in',
	'wpp_smtp_port' => '2525',
	'wpp_smtp_ssl' => 'none',
	'wpp_smtp_auth' => false,
	'wpp_smtp_user' => '',
	'wpp_smtp_pass' => ''
);

define('FWMS_SMTP_HOST', 'smtp.swipemail.in');
/**
 * Activation function. This function creates the required options and defaults.
 */
if (!function_exists('fwms_mail250_activate')) :
function fwms_mail250_activate() {
	
	global $wpms_options;
	
	// Create the required options...
	foreach ($wpms_options as $name => $val) {
		add_option($name,$val);
	}
	
}
endif;

// Whitelist the plugin options in wp
if (!function_exists('fwms_mail250_whitelist_options')) :
function fwms_mail250_whitelist_options($whitelist_options) {
	
	global $wpms_options;
	
	// Add our options to the array
	$whitelist_options['email'] = array_keys($wpms_options);
	
	return $whitelist_options;
	
}
endif;


// To avoid any (very unlikely) clashes, check if the function alredy exists
if (!function_exists('phpmailer_init_smtp')) :
    // This code is copied, from wp-includes/pluggable.php as at version 2.2.2
    function phpmailer_init_smtp($phpmailer) {
		
		// Check that mailer is not blank, and if mailer=smtp, host is not blank
		if ( ! get_option('wpp_mailer') || ( get_option('wpp_mailer') == 'smtp' && ! FWMS_SMTP_HOST ) ) {
			return;
		}
		
		/* Set the mailer type as per config above, this overrides the already called isMail method */
		// Set the mailer type as per config above, this overrides the already called isMail method
		$phpmailer->Mailer = get_option('wpp_mailer');
		$mail_from = get_option('wpp_mail_from');
		$mail_from_name = get_option('wpp_mail_from_name');
		if ( !empty($mail_from) )
		    $phpmailer->From = get_option('wpp_mail_from');
	    if ( !empty($mail_from_name) )
		    $phpmailer->FromName = get_option('wpp_mail_from_name');
				
		// Set the Sender (return-path) if required
		if (get_option('wpp_mail_set_return_path'))
			$phpmailer->Sender = $phpmailer->From;
		
		// Set the SMTPSecure value, if set to none, leave this blank
		$phpmailer->SMTPSecure = get_option('wpp_smtp_ssl') == 'none' ? '' : get_option('wpp_smtp_ssl');
		
		// If we're sending via SMTP, set the host
		if (get_option('wpp_mailer') == "smtp") {
		    $phpmailer->IsSMTP();
			
			// Set the SMTPSecure value, if set to none, leave this blank
			$phpmailer->SMTPSecure = get_option('wpp_smtp_ssl') == 'none' ? '' : get_option('wpp_smtp_ssl');
			
			// Set the other options
			$phpmailer->Host = FWMS_SMTP_HOST;
			$phpmailer->Port = get_option('wpp_smtp_port');
			
			// If we're using smtp auth, set the username & password
			if (get_option('wpp_smtp_auth') == "true") {
				$phpmailer->SMTPAuth = TRUE;
				$phpmailer->Username = get_option('wpp_smtp_user');
				$phpmailer->Password = get_option('wpp_smtp_pass');
			}
		}
		
		// You can add your own options here, see the phpmailer documentation for more info:
		// http://phpmailer.sourceforge.net/docs/
		$phpmailer = apply_filters('fwms_mail250_custom_options', $phpmailer);
		
		
		// STOP adding options here.
		
	    add_action('admin_enqueue_scripts', 'fwms_mail250_scripts_method');
	
    } // End of phpmailer_init_smtp() function definition
endif;


/**
 * This function used to enqueue the required script files
 */
function fwms_mail250_scripts_method() {
	if ( is_admin() ) {
		wp_register_style( 'free-wp-mail-smtp', esc_url( plugins_url( 'css/free-wp-mail-smtp-style.css', __FILE__ ) ), false, '1.00' );
		wp_enqueue_style( 'custom-style' );
	}
}
add_action('admin_enqueue_scripts', 'fwms_mail250_scripts_method');


/**
 * This function outputs the plugin options page.
 */
if (!function_exists('fwms_mail250_options_page')) :
// Define the function
function fwms_mail250_options_page() {
	
	// Load the options
	global $wpms_options, $phpmailer;
	
	// Make sure the PHPMailer class has been instantiated 
	// (copied verbatim from wp-includes/pluggable.php)
	// (Re)create it, if it's gone missing
	if ( !is_object( $phpmailer ) || !is_a( $phpmailer, 'PHPMailer' ) ) {
		require_once ABSPATH . WPINC . '/class-phpmailer.php';
		require_once ABSPATH . WPINC . '/class-smtp.php';
		$phpmailer = new PHPMailer( true );
	}

	// Send a test mail if necessary
	if (isset($_POST['wpms_action']) && $_POST['wpms_action'] == __('Send Test', 'fwms_mail250') && isset($_POST['to'])) {

    //validation
	if ( !filter_var($_POST['to'], FILTER_VALIDATE_EMAIL) ) { ?>
	     <div id="login_error" class="error fade"><p>Please enter valid to email address.</p></div>
		 <?php 
	} 
    else {
		
		    check_admin_referer('test-email');
		
		    // Set up the mail variables
		    $to = sanitize_text_field(trim($_POST['to']));//to is already checked for mail format
		    $subject = !empty($_POST['subject']) ? sanitize_text_field(trim($_POST['subject'])) : 'Mail250: ' . __('Test mail to ', 'fwms_mail250') . $to;
		    if ( !empty($_POST['message']) )
			    $message = sanitize_text_field(trim($_POST['message']));
		    else
			    $message = __('This is a test email generated by the Free WP SMTP Mail250 WordPress plugin.', 'fwms_mail250');
		
		    // Set SMTPDebug to true
		//    $phpmailer->SMTPDebug = 4;
		    //$phpmailer->debug = true;
		
		    // Start output buffering to grab smtp debugging output
		    //ob_start();
		    //$phpmailer->do_debug = true;
		    $error = '';
		    $phpmailer->Debugoutput = function($str, $level) { if (!isset($error)){$error = '';} $error .= $str;};
            try{
		        // Send the test mail
		        $result = wp_mail($to,$subject,$message);
		
		        // Strip out the language strings which confuse users
		        //unset($phpmailer->language);
		        // This property became protected in WP 3.2
		
		    } catch ( phpmailerException $e ) {
        		$error = new WP_Error( 'phpmailer-exception', $e->errorMessage() );
	        } catch ( Exception $e ) {
        		$error = new WP_Error( 'phpmailer-exception-unknown', $e->getMessage() );
	        }
		
		    // Grab the smtp debugging output
		    //$smtp_debug = ob_get_clean();
		
		    //if smtp settings form submitted
		
		
		    // Output the response
		?>
		<?php if ( $result ) { ?>
<div id="message" class="updated fade"><p><strong><?php _e('Test Message Sent', 'fwms_mail250'); ?></strong></p>
<?php //var_dump($result); 
?>
<?php 
/* uncomment for debugging purposes
<p><?php _e('The full debugging output is shown below:', 'fwms_mail250'); ?></p>
<pre><?php var_dump($phpmailer); ?></pre>
<p><?php _e('The SMTP debugging output is shown below:', 'fwms_mail250'); ?></p>
<pre><?php echo $smtp_debug ?></pre>
*/ ?>
</div>
<?php } else { 
        $error = $phpmailer->ErrorInfo;
?>
<div id="login_error" class="error fade"><p><strong><?php _e('Error while sending test message.', 'fwms_mail250'); ?></strong></p>
    <?php if ( !empty($error) ) {
        echo "<p><strong>$error</strong></p>";
    } ?>
</div>
<?php } ?>
	<?php
		
		    // Destroy $phpmailer so it doesn't cause issues later
		    unset($phpmailer);

	    }
	}//ends else part validations

    //validations and sanitizations added
	if( isset($_REQUEST['fwms_mail250_option']) && $_REQUEST['fwms_mail250_option'] == 1 ) {
		if ( !empty($_POST['mail_from']) )
			update_option( 'wpp_mail_from', sanitize_text_field(trim($_POST['mail_from'])));
		if ( !empty($_POST['mail_from_name']) )
			update_option( 'wpp_mail_from_name', sanitize_text_field(trim($_POST['mail_from_name'])));
		if ( !empty($_POST['mailer']) )
			update_option( 'wpp_mailer', sanitize_text_field(trim($_POST['mailer'])));
		if ( !empty($_POST['mail_set_return_path']) )
			update_option( 'wpp_mail_set_return_path', 1);
        else
			update_option( 'wpp_mail_set_return_path', 0);
		if ( !empty($_POST['smtp_host']) )
			update_option( 'wpp_smtp_host', FWMS_SMTP_HOST);
		if ( !empty($_POST['smtp_port']) )
			update_option( 'wpp_smtp_port', intval(trim($_POST['smtp_port'])));
		if ( !empty($_POST['smtp_ssl']) )
			update_option( 'wpp_smtp_ssl', 1);
        else
			update_option( 'wpp_smtp_ssl', 0);
		if ( !empty($_POST['smtp_auth']) )
			update_option( 'wpp_smtp_auth', 1);
        else
			update_option( 'wpp_smtp_auth', 0);
		if ( !empty($_POST['smtp_user']) )
			update_option( 'wpp_smtp_user', sanitize_text_field(trim($_POST['smtp_user'])));
		if ( !empty($_POST['smtp_pass']) )
			update_option( 'wpp_smtp_pass', sanitize_text_field(trim($_POST['smtp_pass'])));
		?>
		<div id="message" class="updated fade"><p><strong><?php _e('Settings saved successfully', 'fwms_mail250'); ?></strong></p></div>
<?php
	}
	
?>

<div class="wrap">
<div class="w-logo  with_transparent">
	<a class="w-logo-link" href="http://www.mail250.com/">
		<span class="w-logo-img">
			<img class="for_transparent" style="height:55px;width:200px;" src="<?php echo home_url(); ?>/wp-content/plugins/free-wp-mail-smtp/images/logo.png" alt="Mail250: An AI based bulk email marketing service">
		</span>
	</a>
	<span class="plugin-text"><h2>Mail250 SMTP Service</h2></span>
	<div id="smtp_error" class="dspln"></div>
</div> <!-- end w-logo -->

<form method="post" action="admin.php?page=fwms_mail250">
<?php wp_nonce_field('wpp_email-options', 'email_options_field'); ?>

<h3><?php _e('Email Settings', 'fwms_mail250'); ?></h3>
<table class="optiontable form-table">
<tr valign="top">
<th scope="row"><label for="mail_from"><?php _e('From Email', 'fwms_mail250'); ?></label></th>
<td><input name="mail_from" type="text" id="mail_from" value="<?php print(get_option('wpp_mail_from')); ?>" size="40" class="regular-text" />
<span class="description"><?php _e('Email address that emails should be sent from.', 'fwms_mail250'); if(get_option('db_version') < 6124) { print('<br /><span style="color: red;">'); _e('<strong>Please Note:</strong> You appear to be using a version of WordPress prior to 2.3. Please ignore the From Name field and instead enter Name&lt;email@domain.com&gt; in this field.', 'fwms_mail250'); print('</span>'); } ?></span></td>
</tr>
<tr valign="top">
<th scope="row"><label for="mail_from_name"><?php _e('From Name', 'fwms_mail250'); ?></label></th>
<td><input name="mail_from_name" type="text" id="mail_from_name" value="<?php print(get_option('wpp_mail_from_name')); ?>" size="40" class="regular-text" />
<span class="description"><?php _e('Name that emails should be sent from.', 'fwms_mail250'); ?></span></td>
</tr>
</table>


<table class="optiontable form-table">
<tr valign="top">
<th scope="row"><?php _e('Mailer', 'fwms_mail250'); ?> </th>
<td><fieldset><legend class="screen-reader-text"><span><?php _e('Mailer', 'fwms_mail250'); ?></span></legend>
<p><input id="mailer_smtp" type="radio" name="mailer" value="smtp" <?php checked('smtp', get_option('wpp_mailer')); ?> />
<label for="mailer_smtp"><?php _e('Send all WordPress emails via SMTP.', 'fwms_mail250'); ?></label></p>
<p><input id="mailer_mail" type="radio" name="mailer" value="mail" <?php checked('mail', get_option('wpp_mailer')); ?> />
<label for="mailer_mail"><?php _e('Use the PHP mail() function to send emails.', 'fwms_mail250'); ?></label></p>
</fieldset></td>
</tr>
</table>


<table class="optiontable form-table">
<tr valign="top">
<th scope="row"><?php _e('Return Path', 'fwms_mail250'); ?> </th>
<td><fieldset><legend class="screen-reader-text"><span><?php _e('Return Path', 'fwms_mail250'); ?></span></legend><label for="mail_set_return_path">
<input name="mail_set_return_path" type="checkbox" id="mail_set_return_path" value="true" <?php checked('true', get_option('wpp_mail_set_return_path')); ?> />
<?php _e('Set the return-path to match the From Email'); ?></label>
</fieldset></td>
</tr>
</table>

<h3><?php _e('SMTP Settings', 'fwms_mail250'); ?></h3>
<p><?php _e('These settings only apply if you have chosen to send mail by SMTP above.', 'fwms_mail250'); ?></p>

<table class="optiontable form-table">
<tr valign="top">
<th scope="row"><label for="smtp_host"><?php _e('SMTP Host', 'fwms_mail250'); ?></label></th>
<td class="smtp-text"><?php echo FWMS_SMTP_HOST; ?></td>
</tr>
<tr valign="top">
<th scope="row"><label for="smtp_port"><?php _e('SMTP Port', 'fwms_mail250'); ?></label></th>
<td><input name="smtp_port" type="text" id="smtp_port" value="<?php print(get_option('wpp_smtp_port')); ?>" size="6" class="regular-text" /></td>
</tr>
<tr valign="top">
<th scope="row"><?php _e('Encryption', 'fwms_mail250'); ?> </th>
<td><fieldset><legend class="screen-reader-text"><span><?php _e('Encryption', 'fwms_mail250'); ?></span></legend>
<input id="smtp_ssl_none" type="radio" name="smtp_ssl" value="none" <?php checked('none', get_option('wpp_smtp_ssl')); ?> />
<label for="smtp_ssl_none"><span><?php _e('No encryption.', 'fwms_mail250'); ?></span></label><br />
<input id="smtp_ssl_ssl" type="radio" name="smtp_ssl" value="ssl" <?php checked('ssl', get_option('wpp_smtp_ssl')); ?> />
<label for="smtp_ssl_ssl"><span><?php _e('Use SSL encryption.', 'fwms_mail250'); ?></span></label><br />
<input id="smtp_ssl_tls" type="radio" name="smtp_ssl" value="tls" <?php checked('tls', get_option('wpp_smtp_ssl')); ?> />
<label for="smtp_ssl_tls"><span><?php _e('Use TLS encryption. This is not the same as STARTTLS. For most servers SSL is the recommended option.', 'fwms_mail250'); ?></span></label>
</td>
</tr>
<tr valign="top">
<th scope="row"><?php _e('Authentication', 'fwms_mail250'); ?> </th>
<td>
<input id="smtp_auth_false" type="radio" name="smtp_auth" value="false" <?php checked('false', get_option('wpp_smtp_auth')); ?> />
<label for="smtp_auth_false"><span><?php _e('No: Do not use SMTP authentication.', 'fwms_mail250'); ?></span></label><br />
<input id="smtp_auth_true" type="radio" name="smtp_auth" value="true" <?php checked('true', get_option('wpp_smtp_auth')); ?> />
<label for="smtp_auth_true"><span><?php _e('Yes: Use SMTP authentication.', 'fwms_mail250'); ?></span></label><br />
<span class="description"><?php _e('If this is set to no, the values below are ignored.', 'fwms_mail250'); ?></span>
</td>
</tr>

<!-- Mail150 Account creation / sigup code starts -->
<tr valign="top">
<th scope="row"><?php _e('Mail250 Account', 'fwms_mail250'); ?> </th>
<td>

<!-- No -->
<input onchange="showMail250Account()" id="mail250_account_no_select" type="radio" name="mail250_account" value="true" <?php checked('true', get_option('wpp_mail250_account')); ?> />
<label for="mail250_account_no_select"><span><?php _e('No: I do not have Mail250 Account.', 'fwms_mail250'); ?></span></label><br />
<p id="mail250_account_no_show" class="" style="padding-left:25px;"><strong><a href="https://mail250.com/users/signup/?page=offer_code&code=WP_MAIL_OFFER&utm_campaign=wp_mail&utm_source=wordpress&utm_medium=plugin" target="_blank"><?php _e('Click here'); ?></a><?php _e(' to create your Mail250 Account & get 30% exclusive WordPress user discount.', 'fwms_mail250'); ?></strong></p>
</br>

<!-- Yes -->
<input onchange="showMail250Account()" id="mail250_account_yes_select" type="radio" name="mail250_account" value="false" <?php checked('false', get_option('wpp_mail250_account')); ?> />
<label for="mail250_account_yes_select"><span><?php _e('Yes: I have Mail250 Account.', 'fwms_mail250'); ?></span></label><br />
<p id="mail250_account_yes_show" style="display:none;padding-left:25px;" class=""> <strong><a href="https://mail250.com/users/login" target="_blank"><?php _e('Login'); ?></a> <?php _e(' to your Mail250 Account then go to Home > SMTP Details.', 'fwms_mail250'); ?> <a href="https://mail250.com/knowledge-base/where-can-i-find-my-smtp-credentials/" target="_blank">learn more</a></strong></p>
</br>

</td>
</td>
</tr>
<script>
function showMail250Account(){
    if(document.getElementById('mail250_account_yes_select').checked) {
      //yes account exists
      document.getElementById("mail250_account_yes_show").style.display = "block"; 
      document.getElementById("mail250_account_no_show").style.display = "none"; 
    }else if(document.getElementById('mail250_account_no_select').checked) {
      //No account NOT exists
      document.getElementById("mail250_account_yes_show").style.display = "none"; 
      document.getElementById("mail250_account_no_show").style.display = "block"; 
    }    
}
showMail250Account();
</script>
<!-- Mail150 Account creation / sigup code starts -->

<tr valign="top">
<th scope="row"><label for="smtp_user"><?php _e('Mail250 SMTP Username', 'fwms_mail250'); ?></label></th>
<td><input name="smtp_user" type="text" id="smtp_user" value="<?php print(get_option('wpp_smtp_user')); ?>" size="40" class="code" /></td>
</tr>
<tr valign="top">
<th scope="row"><label for="smtp_pass"><?php _e('Mail250 SMTP Password', 'fwms_mail250'); ?></label></th>
<td><input name="smtp_pass" type="text" id="smtp_pass" value="<?php print(get_option('wpp_smtp_pass')); ?>" size="40" class="code" /></td>
</tr>
</table>

<p class="submit"><input type="submit" name="submit" id="submit" class="button-primary" value="<?php _e('Save Changes'); ?>" /></p>
</p>
<input type="hidden" name="fwms_mail250_option" value="1">
</form>

<h3><?php _e('Send a Test Email', 'fwms_mail250'); ?></h3>
<div id="show_error" class="dspln"></div>
<form method="POST" action="admin.php?page=fwms_mail250<?php //echo plugin_basename(__FILE__); ?>">
<?php wp_nonce_field('test-email'); ?>

<table class="optiontable form-table">
<tr valign="top">
<th scope="row"><label for="to"><?php _e('To:', 'fwms_mail250'); ?></label></th>
<td><input name="to" type="text" id="to" value="" size="40" class="code" />
<span class="description"><?php _e('Type your email address here.', 'fwms_mail250'); ?></span></td>
</tr>
<tr valign="top">
<th scope="row"><label for="to">Subject</label></th>
<td><input name="subject" type="text" id="subject" value="" size="40" class="code" />
<span class="description">Type your email subject here.</span></td>
</tr
<tr valign="top">
<th scope="row"><label for="to">Message</label></th>
<td><textarea name="message" id="email_message" class="code"></textarea>
<span class="description">Type your message here.</span></td>
</tr>
</table>
<p class="submit"><input type="submit" name="wpms_action" id="wpms_action" class="button-primary" value="<?php _e('Send Test', 'fwms_mail250'); ?>" /></p>
</form>

<p>
Your emails will be sent via Mail250 - a third party AI based email delivery platform. For details please refer to Mail250   <a href="https://mail250.com/legal/privacy/?utm_campaign=wp_mail&utm_source=wordpress&utm_medium=plugin" target="_blank"> Privacy </a>and <a href="https://mail250.com/legal/terms/?utm_campaign=wp_mail&utm_source=wordpress&utm_medium=plugin" target="_blank">Terms</a>
</p>

</div> <!-- end wrap -->
	<?php
	
} // End of fwms_mail250_options_page() function definition
endif;


/**
 * This function adds the required page (only 1 at the moment).
 */
if (!function_exists('fwms_mail250_menus')) :
  function fwms_mail250_menus() {
	
	if (function_exists('add_submenu_page')) {
		add_menu_page('Free WP Mail Settings', 'Free WP Mail Settings', 'manage_options', 'fwms_mail250', 'fwms_mail250_options_page');
	}
	
  } // End of fwms_mail250_menus() function definition
endif;


/**
 * This function sets the from email value
 */
if (!function_exists('fwms_mail250_mail_from')) :
function fwms_mail250_mail_from ($orig) {
	
	// Get the site domain and get rid of www.
	$sitename = strtolower( $_SERVER['SERVER_NAME'] );
	if ( substr( $sitename, 0, 4 ) == 'www.' ) {
		$sitename = substr( $sitename, 4 );
	}

	$default_from = 'wordpress@' . $sitename;
	// End of copied code
	
	// If the from email is not the default, return it unchanged
	if ( $orig != $default_from ) {
		return $orig;
	}
	
	if (defined('WPMS_ON') && WPMS_ON) {
		if (defined('WPMS_MAIL_FROM') && WPMS_MAIL_FROM != false)
			return WPMS_MAIL_FROM;
	}
	elseif (is_email(get_option('wpp_mail_from'), false))
		return get_option('wpp_mail_from');
	
	// If in doubt, return the original value
	return $orig;
	
} // End of fwms_mail250_mail_from() function definition
endif;


/**
 * This function sets the from name value
 */
if (!function_exists('fwms_mail250_mail_from_name')) :
function fwms_mail250_mail_from_name ($orig) {
	
	// Only filter if the from name is the default
	if ($orig == 'WordPress') {
		if (defined('WPMS_ON') && WPMS_ON) {
			if (defined('WPMS_MAIL_FROM_NAME') && WPMS_MAIL_FROM_NAME != false)
				return WPMS_MAIL_FROM_NAME;
		}
		elseif ( get_option('wpp_mail_from_name') != "" && is_string(get_option('mail_from_name')) )
			return get_option('wpp_mail_from_name');
	}
	
	// If in doubt, return the original value
	return $orig;
	
} // End of fwms_mail250_mail_from_name() function definition
endif;

function fwms_mail250_mail_plugin_action_links( $links, $file ) {
	if ( $file != plugin_basename( __FILE__ ))
		return $links;

	$settings_link = '<a href="admin.php?page=fwms_mail250">' . __( 'Settings', 'fwms_mail250' ) . '</a>';

	array_unshift( $links, $settings_link );

	return $links;
}


if (!defined('WPMS_ON') || !WPMS_ON) {
	// Whitelist our options
	add_filter('whitelist_options', 'fwms_mail250_whitelist_options');
	// Add the create pages options
	add_action('admin_menu','fwms_mail250_menus');
	// Add an activation hook for this plugin
	register_activation_hook(__FILE__,'fwms_mail250_activate');
	// Adds "Settings" link to the plugin action page
	add_filter( 'plugin_action_links', 'fwms_mail250_mail_plugin_action_links',10,2);
}

// Add filters to replace the mail from name and emailaddress
add_filter('wp_mail_from','fwms_mail250_mail_from');
add_filter('wp_mail_from_name','fwms_mail250_mail_from_name');
load_plugin_textdomain('fwms_mail250', false, dirname(plugin_basename(__FILE__)) . '/langs');

// Add an action on phpmailer_init
add_action('phpmailer_init','phpmailer_init_smtp');
