<?php 


namespace MailBaby;


class MailbabyService 
{

	public $res;
	public $postdata;

	public function get_token()
	{
		$service_info = get_option('MAIL_BABY_SMTP_options');
		return $service_info['mail_baby_api_key'];
	}


	public function MAILBABY_send_mail($to, $subject, $message, $headers, $attachments = '')
	{	
		$api_key = $this->get_token();

		$options = get_option('MAIL_BABY_SMTP_options');

	 	$data = array(
		    "to" => $to ,
		    "from" =>  $options['from_email'] ,
		    "subject" => $subject,
		    "body" => $message,
	    );
		
		
	 	$payload = json_encode($data);

 	 	$url = 'https://api.mailbaby.net/mail/send';
		$url = 'https://relay.mailbaby.net/mail/send';

		//   $ch = curl_init();
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
	    //     'X-API-KEY:'.$api_key
	    // ));
 	 	

  	  	// $response = curl_exec($ch);

		// $err = curl_error($ch);

		// curl_close($ch);

		// if ($err) {
		// echo "cURL Error #:" . $err;
		// } else {
		// echo $response;
		// }
		$postdata = array(
			'timeout'  => 45,
			'blocking' => true, 
			'sslverify' => false,
			'httpversion' => '1.0',
			'redirection' => 5,
			'headers' => array(
				'Accept'=> 'application/json',
				'Content-Type' =>'application/json',
				'X-API-KEY' => $api_key,
			),
			'body'    => $payload,
		);
		//	echo '<pre>'; print_r($postdata); echo '<pre>'; exit;
		  $response = wp_remote_post( $url, $postdata );
		$res = wp_remote_retrieve_body($response);
		

		if(!is_wp_error( $res ) || wp_remote_retrieve_response_code( $res ) == 200){

			$parsed = json_decode($res, 1);
			if ( isset($parsed['status']) ){
				return true;
			}
		} else {
			return 'Some error was occured: ' . error_log( print_r( $res, true ) );
		}
		do_action( 'wp_mail_failed', new \WP_Error( 'wp_mail_failed', print_r( $response, true )) );
	}
}

?>