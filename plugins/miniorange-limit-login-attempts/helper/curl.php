<?php
class Mo_lla_MocURL
{

	public static function create_customer($email, $company, $password, $phone = '', $first_name = '', $last_name = '')
	{
		$url = Mo_lla_MoWpnsConstants::HOST_NAME . '/moas/rest/customer/add';
		$fields = array (
			'companyName' 	 => $company,
			'areaOfInterest' => 'Limit Login Attempts',
			'firstname' 	 => $first_name,
			'lastname' 		 => $last_name,
			'email' 		 => $email,
			'phone' 		 => $phone,
			'password' 		 => $password
		);
		$json = json_encode($fields);
		$response = self::callAPI($url, $json);
		return $response;
	}
	
	public static function get_customer_key($email, $password) 
	{
		$url 	= Mo_lla_MoWpnsConstants::HOST_NAME. "/moas/rest/customer/key";
		$fields = array (
                    'email'     => $email,
                    'password'  => $password
                );
        $json       = json_encode($fields);
        $response   = self::make_curl_call($url, $json);
        return $response;
	}
	
	function submit_contact_us( $q_email, $q_phone, $query, $subject )
	{
		$current_user = wp_get_current_user();
		$url    = Mo_lla_MoWpnsConstants::HOST_NAME . "/moas/rest/customer/contact-us";
		$query  = '[WordPress Limit Login Attempts Plugin: V:'.LIMITLOGIN_VERSION.'] ' . $query;
		$fields = array(
					'firstName'	=> $current_user->user_firstname,
					'lastName'	=> $current_user->user_lastname,
					'company' 	=> sanitize_text_field($_SERVER['SERVER_NAME']),
					'email' 	=> $q_email,
					'ccEmail'   => 'securityteam@xecurify.com',
					'phone'		=> $q_phone,
					'query'		=> $query
				);
		$field_string = json_encode( $fields );
		$response = self::callAPI($url, $field_string);
		return true;
	}

	function lookupIP($ip)
	{
	
		$url 	= Mo_lla_MoWpnsConstants::HOST_NAME. "/moas/rest/security/iplookup";
		$fields = array (
					'ip' => $ip
				);
		$json = json_encode($fields);
        return self::callAPI($url, $json);
	}
	
	function send_otp_token($auth_type, $phone, $email)
	{
		
		$url 		 = Mo_lla_MoWpnsConstants::HOST_NAME . '/moas/api/auth/challenge';
		$customerKey = Mo_lla_MoWpnsConstants::DEFAULT_CUSTOMER_KEY;
		$apiKey 	 = Mo_lla_MoWpnsConstants::DEFAULT_API_KEY;

		$fields  	 = array(
							'customerKey' 	  => $customerKey,
							'email' 	  	  => $email,
							'phone' 	  	  => $phone,
							'authType' 	  	  => $auth_type,
							'transactionName' => 'Limit Login Attempts'
						);
		$json 		 = json_encode($fields);
		$authHeader  = $this->createAuthHeader($customerKey,$apiKey);
        return self::callAPI($url, $json, $authHeader);
	}

	function mo_lla_validate_recaptcha($ip,$response)
	{

		$url 		 = Mo_lla_MoWpnsConstants::RECAPTCHA_VERIFY;
		$json		 = "";
		$fields 	 = array(
							'response' => $response,
							'secret'   => get_option('mo_lla_recaptcha_secret_key'),
							'remoteip' => $ip
						);
		foreach($fields as $key=>$value) { 
			$json .= $key.'='.$value.'&'; 
		}
		rtrim($json, '&');
		$response 	 = self::mollm_callAPI($url, $json,null);
		return $response;
	}
	private static function mollm_callAPI($url, $json_string, $headers = array("Content-Type: application/json")) {

		$results = wp_remote_post( $url, array(
                'method' => 'POST',
                'timeout' => 45,
                'redirection' => 5,
                'httpversion' => '1.0',
                'blocking' => true,
                'headers' => $headers,
                'body' => $json_string,
                'cookies' => array()));  
		return $results['body'];
	}

	function mo_lla_get_Captcha_v3($Secretkey)
    {

        $json		 = "";
        $url         = "https://www.google.com/recaptcha/api/siteverify";
        $fields 	 = array(
                        'response' => $Secretkey,
                        'secret'   => get_option('mo_lla_recaptcha_secret_key_v3'),
                        'remoteip' => sanitize_text_field($_SERVER['REMOTE_ADDR'])
                    );
        foreach($fields as $key=>$value) {
            $json .= $key.'='.$value.'&';
        }
        json_encode($json);
        $result 	 = $this->mollm_callAPI($url, $json, null);

        return $result;
    }

	function validate_otp_token($transactionId,$otpToken)
	{
		$url 		 = Mo_lla_MoWpnsConstants::HOST_NAME . '/moas/api/auth/validate';
		$customerKey = Mo_lla_MoWpnsConstants::DEFAULT_CUSTOMER_KEY;
		$apiKey 	 = Mo_lla_MoWpnsConstants::DEFAULT_API_KEY;

		$fields 	 = array(
						'txId'  => $transactionId,
						'token' => $otpToken,
					 );

		$json 		 = json_encode($fields);
		$authHeader  = $this->createAuthHeader($customerKey,$apiKey);
        return self::callAPI($url, $json, $authHeader);
	}
	
	function check_customer($email)
	{
		$url 	= Mo_lla_MoWpnsConstants::HOST_NAME . "/moas/rest/customer/check-if-exists";
		$fields = array(
					'email' 	=> $email,
				);
		$json     = json_encode($fields);
        return self::callAPI($url, $json);
	}
	
	function mo_lla_forgot_password()
	{
	
		$url 		 = Mo_lla_MoWpnsConstants::HOST_NAME . '/moas/rest/customer/password-reset';
		$email       = get_option('mo_lla_admin_email');
		$customerKey = get_option('mo_lla_admin_customer_key');
		$apiKey 	 = get_option('mo_lla_admin_api_key');
		$fields 	 = array(
						'email' => $email
					 );
		$json 		 = json_encode($fields);
		$authHeader  = $this->createAuthHeader($customerKey,$apiKey);
        return self::callAPI($url, $json, $authHeader);
	}

	function send_notification($toEmail,$subject,$content,$fromEmail,$fromName,$toName)
	{
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
		$headers .= 'From: '.$fromName.'<'.$fromEmail.'>' . "\r\n";
		mail($toEmail,$subject,$content,$headers);
		return json_encode(array("status"=>'SUCCESS','statusMessage'=>'SUCCESS'));
	}

	//added for feedback

    function send_email_alert($email,$phone,$message, $feedback_option){
    	global $user;
        $url = Mo_lla_MoWpnsConstants::HOST_NAME . '/moas/api/notify/send';
        $customerKey = Mo_lla_MoWpnsConstants::DEFAULT_CUSTOMER_KEY;
        $apiKey 	 = Mo_lla_MoWpnsConstants::DEFAULT_API_KEY;
        $fromEmail	 = 'no-reply@xecurify.com';  
        $user         = wp_get_current_user();
        $query        = '[WordPress Limit Login Attempts Plugin]: ' . $message;
        
		if ($feedback_option == 'molla_skip_feedback') {
            $subject = "Deactivate [Feedback Skipped]: miniOrange Limit login attempts";
        } elseif ($feedback_option == 'molla_feedback') {
            $subject = "Feedback: miniOrange Limit login attempts - ". sanitize_email($email);
        }

        $content='<div >Hello, <br><br>First Name :'.sanitize_text_field($user->user_firstname).'<br><br>Last  Name :'.sanitize_text_field($user->user_lastname).'   <br><br>Company :<a href="'.sanitize_text_field($_SERVER['SERVER_NAME']).'" target="_blank" >'.sanitize_text_field($_SERVER['SERVER_NAME']).'</a><br><br>Phone Number :'.sanitize_text_field($phone).'<br><br>Email :<a href="mailto:'.sanitize_email($email).'" target="_blank">'.sanitize_email($email).'</a><br><br>Query :'.sanitize_text_field($query).'</div>';

        $fields = array(
            'customerKey'	=> $customerKey,
            'sendEmail' 	=> true,
            'email' 		=> array(
            'customerKey' 	=> $customerKey,
            'fromEmail' 	=> $fromEmail,
            'bccEmail' 		=> $fromEmail,
            'fromName' 		=> 'Xecurify',
            'toEmail' 		=> 'securityteam@xecurify.com',
            'toName' 		=> 'securityteam@xecurify.com',
            'subject' 		=> $subject,
            'content' 		=> $content),);
		$field_string = json_encode($fields);
        $authHeader   = self::createAuthHeader($customerKey, $apiKey);
        $response     = self::make_curl_call($url, $field_string, $authHeader);
        return $response;
       

    }
    public static function make_curl_call($url, $fields, $http_header_array = array("Content-Type"=>"application/json","charset"=>"UTF-8","Authorization"=>"Basic"))
    {
        if (gettype($fields) !== 'string') {
            $fields = json_encode($fields);
        }
        $args = array(
            'method' => 'POST',
            'body' => $fields,
            'timeout' => '5',
            'redirection' => '5',
            'httpversion' => '1.0',
            'blocking' => true,
            'headers' => $http_header_array
        );
        $response = self::wp_remote_post($url, $args);
        return $response;
    }
     public static function wp_remote_post($url, $args = array())
    {

        $response = wp_remote_post($url, $args);

        if (!is_wp_error($response)) {
            return $response['body'];
        } else {
            $message = 'Something went wrong';

            return json_encode(array( "status" => 'ERROR', "message" => $message ));
        }
    }
	public static function createAuthHeader($customerKey, $apiKey)
    {
        $currentTimestampInMillis = round(microtime(true) * 1000);
        $currentTimestampInMillis = number_format($currentTimestampInMillis, 0, '', '');
        $stringToHash             = $customerKey . $currentTimestampInMillis . $apiKey;
        $hashValue                = hash("sha512", $stringToHash);
        $headers = array(
            "Content-Type"  => "application/json",
            "Customer-Key"  => $customerKey,
            "Timestamp"     => $currentTimestampInMillis,
            "Authorization" => $hashValue
        );
        
        return $headers;
    }
	private static function callAPI($url, $json_string, $headers = array("Content-Type"=>"application/json","charset"=>"UTF-8","Authorization"=>"Basic")) {
        $response = null;
        $results = wp_remote_post( $url, array(
                'method' => 'POST',
                'timeout' => 45,
                'redirection' => 5,
                'httpversion' => '1.0',
                'blocking' => true,
                'headers' => $headers,
                'body' => $json_string,
                'cookies' => array()));     
       if( isset($results) && $results['body'] == 'Query submitted.') {
        
          return true;
            
        }else{
         $result = json_decode($results['body'],true);
            if(isset($result['status'])){
                if ($result['status'] == 'SUCCESS') {
                    return $results['body'];
                }
            }
        }
        return $response;
    }
}