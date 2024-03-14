<?php 


namespace SMTPCom;


class SmtpcomService 
{

	public function get_token()
	{
		$service_info = get_option('MAIL_BABY_SMTP_options');
		return $service_info['mail_baby_smtp_smtpcom_api_key'];
	}


	public function SMTP_send_mail($to, $subject, $message, $headers, $attachments = '')
	{	
		$api_key = $this->get_token();
		$options = get_option('MAIL_BABY_SMTP_options');

	 	$data = array(
		      "channel" => $options['mail_baby_smtp_smtpcom_sender_name'],
		      "recipients" => array(
		          "to" => array(
		              array(
		                  "name" => 'Mike',
		                  "address" => $to
		              )
		          )
		      ),
		      "originator" => array(
		          "from" => array(
		              "name" => $options['from_name'],
		              "address" => $options['from_email']
		          )
		      ),
		      "subject" => $options['mail_baby_smtp_smtpcom_sender_name'],
		      "body" => array(
		          "parts" => array(
		              array(
		                  "version" => "v1.0",
		                  "type" => "text/html",
		                  "charset" => "UTF-8",
		                  "encoding" => "base64",
		                  "content" => $message
		              )
		          )
		      )
	    );
		
	 	$payload = json_encode($data);

 	 	$api_url = 'https://api.smtp.com/v4/';
	  	$url = $api_url . 'messages?api_key=' . $api_key;

		// $ch = curl_init();
		// curl_setopt($ch, CURLOPT_URL, $url);
	    // curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	    //  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, TRUE);
	    // curl_setopt($ch, CURLOPT_POST, TRUE);
	    // curl_setopt($ch, CURLOPT_HEADER, TRUE);
	    // curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	    // curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
	    // curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	    //     'Accept: application/json',
	    //     'Content-Type: application/json',
	    // ));

		$response = wp_remote_post( $url, array(
			'body'    => $payload,
			'headers' => array(
				 'Accept' => 'application/json',
	        	 'Content-Type' => 'application/json'
			)
		) );
		$res = wp_remote_retrieve_body($response);

		// $res = curl_exec($ch);
		if(!is_wp_error( $res ) || wp_remote_retrieve_response_code( $res ) == 200){
  	  	// if ( !curl_errno($ch) && $res ) {
	      $parsed = json_decode($res, 1);
	      if ( $parsed['status'] ){
	        return $parsed['status'];
	      } 
	    } else {
	      echo esc_attr('Some error was occured: ' . error_log( print_r( $res, true ) ));
	    }
		do_action( 'wp_mail_failed', new \WP_Error( 'wp_mail_failed', $res) );
	}
}

?>