<?php
namespace Login_With_AJAX\Transports;

class Email extends Transport {
	
	static $method = 'email';
	
	public static function get_recipient_key() {
		return 'telegram_username';
	}
	
	public static function get_recipient( $user ) {
		return $user->user_email;
	}
	
	/**
	 * Gets user by email
	 * @param $recipient
	 *
	 * @return \WP_User|false
	 */
	public static function get_recipient_user( $recipient ) {
		return get_user_by( 'email', $recipient );
	}
	
	public static function send( $recipient, $message, $data = array() ){
		$subject = is_string( $data ) ? $data : '';
		$subject = !empty($data['subject']) ? $data['subject'] : $subject;
		$headers = !empty($data['headers']) ? $data['headers'] : array();
		$attachments = !empty($data['attachments']) ? $data['attachments'] : '';
		$sent = wp_mail( $recipient, $subject, $message, $headers, $attachments );
		if( $sent ) {
			return array(true); // no message IDs for email
		} else {
			return new \WP_Error('lwa-transport-email', esc_html__('Could not send email.', 'login-with-ajax') );
		}
	}
}