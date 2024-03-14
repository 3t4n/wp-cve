<?php
namespace Login_With_AJAX\TwoFA\Method;
use WP_User;

class Email extends Method_Code {
	
	public static $method = 'email';
	public static $authentication_resend = 30;
	public static $needs_setup = false;
	public static $svg_icon = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><path stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12c0 2.2091-1.7909 4-4 4-2.20914 0-4-1.7909-4-4 0-2.20914 1.79086-4 4-4 2.2091 0 4 1.79086 4 4Zm0 0v1.5c0 1.3807 1.1193 2.5 2.5 2.5v0c1.3807 0 2.5-1.1193 2.5-2.5V12c0-4.97056-4.0294-9-9-9-4.97056 0-9 4.02944-9 9 0 4.9706 4.02944 9 9 9h4"/></svg>';
	/**
	 * @var \Login_With_AJAX\Transports\Email
	 */
	public static $transport = '\Login_With_AJAX\Transports\Email';
	
	public static function get_name () {
		return esc_html__('Email', 'login-with-ajax');
	}
	
	public static function send_code ( WP_User $user, $code ) {
		$subject = '['. get_bloginfo('title') .'] ' . esc_html__('Email Verification Code');
		$msg = __("To log into your account, please use the following code for the final verification step:<br><br>%2FACODE%<br><br>If you have not attempted to log into your account, it has not been accessed but your password was correctly entered so please log in and change your password immediately.",'login-with-ajax-pro');
		$msg = str_replace( array("<br>", '%2FACODE%'), array("\n\r", $code), $msg);
		$data = array(
			'subject' => $subject,
		);
		if( static::transport()::send( $user->user_email, $msg, $data ) ) {
			return true;
		}
		return false;
	}
	
	public static function mask( $str, $first = 3, $last = 2) {
		$mail_parts = explode("@", $str);
		$domain_parts = explode('.', $mail_parts[1]);
		
		$mail_parts[0] = parent::mask($mail_parts[0], 1, 1); // show first 2 letters and last 1 letter
		$domain_parts[0] = parent::mask($domain_parts[0], 1, 1); // same here
		$mail_parts[1] = implode('.', $domain_parts);
		
		return implode("@", $mail_parts);
	}
}
add_action('lwa_2FA_loaded', '\Login_With_AJAX\TwoFA\Method\Email::init');