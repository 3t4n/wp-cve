<?php class EModal_License {
	protected $product = array(
		'slug' => EMCORE_SLUG,
		'version' => EMCORE_VERSION,
	);
	protected $valid_status_codes = array(2000,2001,3002,3003);
	protected $unactivated_status_codes = array(3004,3006);

	public function	__construct()
	{
		$license = emodal_get_license();
		if(!get_site_transient(EMCORE_SLUG.'-license-check'))
		{
			$license['status'] = $this->check_license($license['key']);
			set_site_transient(EMCORE_SLUG.'-license-check', $license, (365 * (60 * 60 * 24)) / 12);
			emodal_update_option(EMCORE_SLUG.'-license', $license);
		}
	}

	public static function available_addons()
	{
		if(($addons = get_site_transient(EMCORE_SLUG.'-addon-list')) === false)
		{
			$access_key = trim( emodal_get_license('key') );

			// data to send in our API request
			$api_params = array( 
				'edd_action'=> 'addon_list', 
				'access_key' 	=> $access_key, 
				'url'       => home_url()
			);
			// Call the custom API.
			$response = wp_remote_get( add_query_arg( $api_params, EMCORE_API_URL ), array( 'timeout' => 15, 'sslverify' => false ) );
		
			// make sure the response came back okay
			if ( is_wp_error( $response ) )
				return array();

			$addons = json_decode( wp_remote_retrieve_body( $response ) );
			set_site_transient(EMCORE_SLUG.'-addon-list', $addons, 86400);
		}
		return $addons;
	}

	public function check_license($new_license = false, $force_check = false)
	{
		$license = emodal_get_license();
		if($new_license == '')
		{
			emodal_delete_option( EMCORE_SLUG.'-license' );
			delete_site_transient( EMCORE_SLUG.'-license-check');
			delete_transient(EMCORE_SLUG.'-addon-list');
			return;
		}
		if($new_license && $new_license != $license['key'] && SHA1($new_license) != $license['key'])
		{
			$license = array(
				'valid' => false,
				'key' => $new_license != '' ? SHA1($new_license) : '',
				'status' => array(
					'code' => NULL,
					'message' => NULL,
					'expires' => NULL,
					'domains' => NULL
				)
			);
			if($new_license != '')
			{
				$force_check = true;
			}
			emodal_update_option(EMCORE_SLUG.'-license', $license);
		}
		if($force_check || ($license['valid'] && !get_site_transient( EMCORE_SLUG.'-license-check' )))
		{
			$status = $this->api_request('license_check');
			if(is_array($status) && in_array($status['code'], $this->unactivated_status_codes))
			{
				$status = $this->api_request('activate_domain');
				//$this->api_request('license_check');
			}
			if(is_array($status) && in_array($status['code'], $this->valid_status_codes))
			{
				$license['valid'] = true;
			}
			else if($status instanceof WP_Error)
			{
				$status = array(
					'code' => 0000,
					'message' => $status->error
				);
			}
			$license['status'] = $status;
			emodal_update_option(EMCORE_SLUG.'-license', $license);
			set_site_transient( EMCORE_SLUG.'-license-check', true, (365 * (60 * 60 * 24)) / 12 );
			delete_transient(EMCORE_SLUG.'-addon-list');
		}
		return $license;
	}
	public function prepare_request($action, $args = array())
	{
		global $wp_version;
		$array = array(
			'action' => $action,
			'args' => $args,
		);
		$license = emodal_get_license('key');
		if($action != 'addon_list' || !empty($license))
		{
			$array['slug'] = $this->product['slug'];
			$array['installed_version'] = $this->product['version'];
		}
		if(!empty($license))
		{
			$array['license_key'] = $license;
			$array['host'] = array(
				'domain'  => get_bloginfo('url'),
				'wp_version'=> $wp_version
			);
		}
		return $array;
	}
	public function api_request($action, $args = array())
	{
		$apiQuery = add_query_arg(
			$this->prepare_request($action, $args),
			//network_admin_url('update.php')
			EMCORE_API_URL
		);
		$request = wp_remote_get($apiQuery);
		if (is_wp_error($request))
		{
			$response = new WP_Error('plugins_api_failed', __('An Unexpected HTTP Error occurred during the API request.'), $request->get_error_message());
		}
		else
		{
			$response = (array) json_decode($request['body']);
			if ($response === false)
			{
				$response = new WP_Error('plugins_api_failed', __('An unknown error occurred'), $request['body']);
			}
		}
		return $response;
	}

}