<?php // add new user subscription via wp mail setting ON
if(isset($_POST['submit_subscriber'])) {
	global $wpdb;
	$table_name    = $table_name = $wpdb->prefix . "rcsm_subscribers";
	$f_name        = sanitize_text_field($_POST['f_name']);
	$l_name        = sanitize_text_field($_POST['l_name']);
	$visitor_email = sanitize_text_field($_POST['subscribe_email']);
	$email_check = $wpdb->get_results( "SELECT * FROM $table_name WHERE email = '$visitor_email'" );
	$num_email = $wpdb->num_rows;
	if($email_check)  {
	   $_SESSION['subscribe_msg'] = $wl_rcsm_options['sub_form_subscribe_invalid_message'];
	} else {
		if($wl_rcsm_options['confirm_email_subscribe'] == 'on'){
			$visitor_email = sanitize_email($_POST['subscribe_email']);
			$f_name = sanitize_text_field($_POST['f_name']);
			$l_name = sanitize_text_field($_POST['l_name']);									
			$current_time = current_time( 'Y-m-d h:i:s' );
			$act_code = rand(0,10000); // md5(date("d-m-y h:i:s"));

			$adminemail = $wl_rcsm_options['wp_mail_email_id'];						 
			$plugin_url = site_url();             
		
			$headers = 'Content-type: text/html'."\r\n"."From:$plugin_url <$adminemail>"."\r\n".'Reply-To: '.$adminemail . "\r\n".'X-Mailer: PHP/' . phpversion();			
			$subject = $wl_rcsm_options['page_meta_title'].': Confirmation Subscription';
			$message = 'Hi '.$f_name.' '.$l_name.', <br/>';
				global $current_user;
				wp_get_current_user();
				$plugin_site_url = site_url();  
				$message .= '<p>Thanks for subscribing!</p><br><p>Click the link below to get confirmed.<br><a href="'.$plugin_site_url.'?act_code='.$act_code.'&'.'email='.$visitor_email.'#newsletter">Confirm Subscriptions</a></p><br><p>Regards</p><p><a href="'.$plugin_site_url.'">'.$wl_rcsm_options['page_meta_title'].'</a></p>';
				$mail= wp_mail( $visitor_email, $subject, $message, $headers);

			if($mail){
				$_SESSION['mail_sent_msg'] = $wl_rcsm_options['sub_form_subscribe_seuccess_message'];
				global $wpdb;
				$table_name = $wpdb->prefix . 'rcsm_subscribers';
				$query= $wpdb->insert( $table_name, array( 'email' => $visitor_email ,'f_name' => $f_name ,'l_name' => $l_name , 'date' => $current_time, 'act_code' => $act_code, 'flag' => 0 ) );
			} else {
				$_SESSION['subscribe_msg'] = $wl_rcsm_options['sub_form_subscribe_invalid_message'];
			}

		} else {
			$visitor_email = sanitize_text_field($_POST['subscribe_email']);
			$current_time = current_time( 'Y-m-d h:i:s' );
			global $wpdb;
			$table_name = $wpdb->prefix . 'rcsm_subscribers';
			$query= $wpdb->insert( $table_name, array( 'email' => $visitor_email , 'f_name' => $f_name ,'l_name' => $l_name , 'date' => $current_time, 'flag' => 1 ) );
			if($query){
				$_SESSION['subscribe_msg'] = $wl_rcsm_options['sub_form_subscribe_seuccess_message'];
			} else {
				$_SESSION['subscribe_msg'] = $wl_rcsm_options['sub_form_subscribe_invalid_message'];
			}
		}
	}
}
?>