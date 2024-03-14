<?php
class WPFingerprint_API{
	private $apikey;
	private $url;
	private $webhook_uri;

	function __construct( )
	{
		$this->url = 'https://api.wpfingerprint.com/wp-json/wpfingerprint/v2/check';
	}

	function fire($plugins)
	{
		$checkstamp = hash( 'md5', site_url().time());
		$this->store_checkstamp($checkstamp);
		$payload = array(
			'site' => site_url(),
			'type' => 'wpfingerprint',
			'plugins' => json_encode($plugins),
			'checkstamp' => $checkstamp
		);
		var_dump($payload);
		$request = new WP_HTTP;
		$result = $request->request( $this->url, array( 'method' => 'POST', 'body' => $payload));
		var_dump($result);
		die;
		if(isset($result['body']))
		{
			$body = json_decode($result['body']);
			if(is_array($body))
			{
				if($this->validate_checkstamp($body['checkstamp']))
				{
					$this->update_plugins( $this->validate_payload( $body['payload'] ) );
				}
			}
		}
	}

	function webhook( $request )
	{
		if(!$this->validate_checkstamp($request->get_param( 'checkstamp')))
		{
		 $data = array('Invalid Checkstamp');
 		 return new WP_REST_Response( $data, 400 );
		}
		$response = $request->get_param( 'payload' );
		if(!empty($response)){
			$data = array('ok');
  		 return new WP_REST_Response( $data, 200 );
		}else{
			$data = array('No Payload');
  		 return new WP_REST_Response( $data, 400 );
		}
	}

	function validate_checkstamp($checkstamp = null)
	{
		if( get_option( 'wpfingerprint_checkstamp' ) == $checkstamp ) return true;
		return false;
	}

	function store_checkstamp($checkstamp)
	{
		if ( get_option( 'wpfingerprint_checkstamp' ) !== false ) {
    	update_option( 'wpfingerprint_checkstamp', $checkstamp );
		} else {
		  add_option( 'wpfingerprint_checkstamp', $checkstamp, null, 'no' );
		}
		return;
	}
	function update_plugins($payload)
	{
		$plugins = json_decode($payload);
		//validate and store it
		$plugins = $this->validate_payload($plugins);

		delete_transient('wpfingerprint-first-run');
		return update_option( 'wpfingerprint_invalid', $plugin );
	}

	function validate_payload($payload){
		$return = array();
		foreach($payload as $plugin => $files)
		{
			if(is_array($files)){
				$return[$plugins] = $files;
			}
		}
		return $return;
	}

}
