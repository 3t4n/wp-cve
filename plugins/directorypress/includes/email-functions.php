<?php
function directorypress_admin_email() {
	global $DIRECTORYPRESS_ADIMN_SETTINGS;
	if ($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_admin_notifications_email'])
		return $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_admin_notifications_email'];
	else 
		return get_option('admin_email');
}

function directorypress_phpmailerInit($phpmailer) {
	$phpmailer->AltBody = wp_specialchars_decode($phpmailer->Body, ENT_QUOTES);
}

function directorypress_mail($email, $subject, $body, $headers = null) {
	add_action('phpmailer_init', 'directorypress_phpmailerInit');

	if (!$headers) {
		$headers[] = "From: " . get_option('blogname') . " <" . directorypress_admin_email() . ">";
		$headers[] = "Reply-To: " . directorypress_admin_email();
		$headers[] = "Content-Type: text/html";
	}
		
	$subject = "[" . get_option('blogname') . "] " .$subject;

	$body = make_clickable(wpautop($body));
	
	$email = apply_filters('directorypress_mail_email', $email, $subject, $body, $headers);
	$subject = apply_filters('directorypress_mail_subject', $subject, $email, $body, $headers);
	$body = apply_filters('directorypress_mail_body', $body, $email, $subject, $headers);
	$headers = apply_filters('directorypress_mail_headers', $headers, $email, $subject, $body);
	
	add_action('wp_mail_failed', 'directorypress_email_log');

	return wp_mail($email, $subject, $body, $headers);
}

function directorypress_send_sms($to, $message) {
	$api_details = get_option('directorypress-twilio');
	if(is_array($api_details) AND count($api_details) != 0) {
        $TWILIO_SID = $api_details['api_sid'];
        $TWILIO_TOKEN = $api_details['api_auth_token'];
		$sender_id = $api_details['sender_id'];
    }

	//try{
       // $to = explode(',', $to);
		//if(class_exists('Twilio')){
        $client = new Twilio\Rest\Client($TWILIO_SID, $TWILIO_TOKEN);
        $client->messages->create($to, array('from' => $sender_id, 'body' => $message));
		//$return = $response;
		//}
 //   } 
	//catch(Exception $e){
       // $return = new WP_Error( 'api-error', $e->getMessage(), $e );
   // }           
}

function directorypress_set_html_mail_content_type() {
    return 'text/html';
}


if ( !function_exists('wp_new_user_notification') ) {

    function wp_new_user_notification( $user_id, $deprecated = null, $notify = 'both' ) {
			global $DIRECTORYPRESS_ADIMN_SETTINGS;

			if ( $deprecated !== null ) {
				_deprecated_argument( __FUNCTION__, '4.3.1' );
			}

			if( isset( $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_admin_notifications_email'] ) && $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_admin_notifications_email'] != '' ) {
					$sender_email_address = $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_admin_notifications_email'];
			}else {
				$sender_email_address = get_option( 'admin_email' );
			}

			$email_body = $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_newuser_notification'];

			global $wpdb, $wp_hasher;
			$user = get_userdata( $user_id );
			if ( empty ( $user ) )
				return;

			// The blogname option is escaped with esc_html on the way into the database in sanitize_option
			// we want to reverse this for the plain text arena of emails.
			$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);

			$message  = sprintf(__('New user registration on your site %s:', 'DIRECTORYPRESS'), $blogname) . "<br />";
			$message .= sprintf(__('Username: %s', 'DIRECTORYPRESS'), $user->user_login) . "<br />";
			$message .= sprintf(__('E-mail: %s', 'DIRECTORYPRESS'), $user->user_email) . "<br />";
			$message .= "<br /><br />";
			$message .= 'Thank you';
        
			$headers = 'Content-type: text/html' . "\r\n" . 'From:' . get_option( 'blogname' ) . ' <' . $sender_email_address . '>' . "\r\n" . 'X-Mailer: PHP/' . phpversion();
        
			add_filter('wp_mail_content_type', 'directorypress_set_html_mail_content_type');
			@wp_mail(get_option('admin_email'), sprintf(__('[%s] New User Registration', 'DIRECTORYPRESS'), $blogname), $message, $headers);

			if ( 'admin' === $notify || empty( $notify ) ) {
				return;
			}

			// Generate something random for a password reset key.
			$key = wp_generate_password( 20, false );

			/** This action is documented in wp-login.php */
			do_action( 'retrieve_password_key', $user->user_login, $key );

			// Now insert the key, hashed, into the DB.
			if ( empty( $wp_hasher ) ) {
				require_once ABSPATH . WPINC . '/class-phpass.php';
				$wp_hasher = new PasswordHash( 8, true );
			}
			$hashed = time() . ':' . $wp_hasher->HashPassword( $key );
			$wpdb->update( $wpdb->users, array( 'user_activation_key' => $hashed ), array( 'user_login' => $user->user_login ) );
			$password_set_link = network_site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($user->user_login), 'login');
			$patterns = array('/#blogname/', '/#username/', '/#password_set_link/');
			$replacements = array(get_option('blogname'), $user->user_login, $password_set_link);
			$message = preg_replace($patterns, $replacements, $email_body);
			$headers = "MIME-Version: 1.0\r\n" . "From: " . get_option( 'blogname' ) . " " . "<" . $sender_email_address . ">\n" . "Content-Type: text/HTML; charset=\"" . get_option('blog_charset') . "\"\r\n";
			
			add_filter('wp_mail_content_type', 'directorypress_set_html_mail_content_type');
			wp_mail($user->user_email, sprintf(__('[%s] Your username and password info', 'DIRECTORYPRESS'), $blogname), $message, $headers );
			//$phone = get_user_meta( $user->ID, 'user_phone', true );
			$to = get_user_meta( $user->ID, 'user_phone', true );
			if(directorypress_is_directorypress_twilio_active() && !empty($to)){
				directorypress_send_sms($to, $message);
			}
    }
}
function directorypress_email_log($wp_error) {
	directorypress_add_notification($wp_error->get_error_message(), 'error');
	error_log($wp_error->get_error_message());
}