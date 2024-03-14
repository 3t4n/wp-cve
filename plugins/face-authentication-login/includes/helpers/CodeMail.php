<?php
namespace DataPeen\FaceAuth;

use DataPeen\FaceAuth\GoogleAuthenticator;

class CodeMail {


	public static function generate_and_email_token( $user ) {

		$code = GoogleAuthenticator::get_code($user->ID);

		/* translators: %s: site name */
		$subject = wp_strip_all_tags( sprintf( __( 'Your login confirmation code for %s', 'two-factor' ), get_bloginfo( 'name' ) ) );
		/* translators: %s: token */
		$message = wp_strip_all_tags( sprintf( __( 'Enter <strong>%s</strong> to log in.', 'two-factor' ), $code ) );

		return wp_mail( $user->user_email, $subject, $message );
	}

}
