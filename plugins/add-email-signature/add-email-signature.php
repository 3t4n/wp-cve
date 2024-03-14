<?php
/*
Plugin Name: Add Email Signature
Plugin URI: https://wordpress.org/plugins/add-email-signature
Description: This plugin adds a configurable signature to every outgoing email that WordPress sends
Author: David Anderson
Version: 1.0.4
Author URI: https://david.dw-perspective.org.uk
License: MIT
*/

/* MIT License:

Permission is hereby granted, free of charge, to any person obtaining
a copy of this software and associated documentation files (the
"Software"), to deal in the Software without restriction, including
without limitation the rights to use, copy, modify, merge, publish,
distribute, sublicense, and/or sell copies of the Software, and to
permit persons to whom the Software is furnished to do so, subject to
the following conditions:

The above copyright notice and this permission notice shall be
included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

*/

if (!defined ('ABSPATH')) die ('No direct access allowed');

# Globals
define ('ADD_EMAIL_SIGNATURE_VERSION', '1.0.4');
define ('ADDEMAILSIG_SLUG', "add-email-signature");
define ('ADDEMAILSIG_DIR', WP_PLUGIN_DIR . '/' . ADDEMAILSIG_SLUG);
// define ('ADDEMAILSIG_URL', plugins_url()."/".ADDEMAILSIG_SLUG);

# Options admin interface
if (is_admin()) require_once( ADDEMAILSIG_DIR . "/options.php");

add_action( 'phpmailer_init', 'add_email_signature_hook_phpmailer_init' );

function add_email_signature_hook_phpmailer_init($mobj) {

	// We are given the phpMailer object. Unfortunately the To/CC/BCC are private, so we have to use a different method
	// This is called by WordPress just before calling Send() on the object. So everything should be set up.

	if (!is_object($mobj) || !is_a($mobj, 'PHPMailer')) return;

	# Do we have any options set up yet in the admin section?
	if ( ($options = get_option('add_email_signature_options')) == false ) return;

	// Get the signature
	$sig = $options['signature'];

	// You can add this, and an appropriate function, to add logging after the mail is sent
	//$mobj->action_function = 'add_email_signature_phpmailer_callbackaction';

	// Get the body
	$body = ($mobj->ContentType == "text/plain") ? $mobj->Body : $mobj->AltBody;

	// Add our signature
	if (!preg_match("/^-- /",$body)) $body .= "\n-- \n".$sig;

	// Store the new body
	if ($mobj->ContentType == "text/plain") {
		$mobj->Body = $body;
	} else {
		$mobj->AltBody = $body;
	}

}
