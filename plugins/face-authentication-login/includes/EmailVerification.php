<?php


namespace DataPeen\FaceAuth;
use DataPeen\FaceAuth\Option_Names;
use DataPeen\FaceAuth\Flog;

/**
 * Class EmailVerification
 * @package DataPeen\FaceAuth
 */
class EmailVerification {

	public static function sendMail($user, $code)
	{

		$user_option = UserOptions::get_option($user);

		$recipient_email = $user_option->get_string(Option_Names::EMAIL_TO_RECEIVE_TOKEN);

		//if the user hasn't set an email but enable the mail sending method, get user's email instead
		if ($recipient_email == '')
			$recipient_email = $user->user_email;

		Flog::write('send email to : ' . $recipient_email);

		$subject = __('Your verification code on ' . get_bloginfo('name'));
		$message = __(sprintf('<p>Someone is trying to login with your account on %1$s . </p>', get_bloginfo('wpurl')));
		$message .= __('<p>If that is not you, simply ignore this message. Otherwise, Please enter the following code to login: </p>');

		$message .= '<p><strong>'.$code . '</strong></p>';
		$message .= sprintf('<p>Thannks for using <a href="%1$s">Face Authentication</a> from <a href="%2$s">Datapeen</a>. We work days and nights to help you secure your sites.</p>', 'https://datapeen.com/products/face-auth/?src=in-confirm-email', 'https://datapeen.com/?src=in-confirm-email');

		return wp_mail($recipient_email, $subject, $message, array('Content-Type: text/html; charset=UTF-8'));
	}



	public static function generateCode()
	{
		return substr(strval(rand(10000000, 10000000000)), 0, 8);
	}

}
