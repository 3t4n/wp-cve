<?php
/**
* Contains class for the OAuth2 user authentication.
* @package API
* @subpackage OAuth2_API
*/

/**
* OAuth2 authentication user class.
* @package API
*/
#[\AllowDynamicProperties]
class flexmlsConnectPortalUser extends flexmlsAPI_OAuth {

	function __construct( $oauth_key, $oauth_secret ){
		global $fmc_version;

		$fmc_settings = get_option( 'fmc_settings' );
		$this->oauth_key = $oauth_key;
		$this->oauth_secret = $oauth_secret;
		$this->oauth_base = 'https://sparkplatform.com/openid';
		$this->oauth_redirect_uri = home_url( 'index.php/oauth/callback' );

		$this->api_base = FMC_API_BASE;
		$this->api_version = FMC_API_VERSION;
		$this->plugin_version = FMC_PLUGIN_VERSION;
		$this->api_headers = array(
			'Accept-Encoding' => 'gzip,deflate',
			'Content-Type' => 'application/json',
			'User-Agent' => 'FlexMLS WordPress Plugin/' . $this->api_version,
			'X-SparkApi-User-Agent' => 'flexmls-WordPress-Plugin/' . $this->plugin_version
		);

		$this->auth_mode = 'oauth';

		//$this->SetAccessToken( $access_token );

		//$this->SetCache( new flexmlsAPI_WordPressCache );
		//$this->SetApplicationName("flexmls-WordPress-Plugin/{$fmc_version}/VOW");
		//$this->SetCachePrefix("fmc_".get_option('fmc_cache_version')."_VOW");
		//$this->user_start_time();
		//parent::__construct( $oauth_key, $oauth_secret, $this->oauth_redirect_uri, null );

	}

	function get_all_results( $response = array() ){
		if( isset( $response[ 'success' ] ) && $response[ 'success' ] ){
			return $response[ 'results' ];
		}
		return false;
	}

	function get_from_api( $method, $service, $cache_time = 0, $params = array(), $post_data = null, $a_retry = false ){
		$json = $this->make_api_call( $method, $service, $cache_time, $params, $post_data, $a_retry );
		$return = array();
		// \FlexMLS_IDX::write_log( $json, 'From API' );

		if( array_key_exists( 'D', $json ) ){
			if( array_key_exists( 'Code', $json[ 'D' ] ) ){
				$this->last_error_code = $json[ 'D' ][ 'Code' ];
				$return[ 'api_code' ] = $json[ 'D' ][ 'Code' ];
			}
			if( array_key_exists( 'Message', $json[ 'D' ] ) ){
				$this->last_error_mess = $json[ 'D' ][ 'Message' ];
				$return[ 'api_message' ] = $json[ 'D' ][ 'Message' ];
			}
			if( array_key_exists( 'Pagination', $json[ 'D' ] ) ){
				$this->last_count = $json[ 'D' ][ 'Pagination' ][ 'TotalRows' ];
				$this->page_size = $json[ 'D' ][ 'Pagination' ][ 'PageSize' ];
				$this->total_pages = $json[ 'D' ][ 'Pagination' ][ 'TotalPages' ];
				$this->current_page = $json[ 'D' ][ 'Pagination' ][ 'CurrentPage' ];
			} else {
				$this->last_count = null;
				$this->page_size = null;
				$this->total_pages = null;
				$this->current_page = null;
			}
			if( true == $json[ 'D' ][ 'Success' ] ){
				$return[ 'success' ] = true;
				$return[ 'results' ] = $json[ 'D' ][ 'Results' ];
			} else {
				$return[ 'success' ] = false;
			}
		}
		return $return;
	}

	function make_sendable_body( $data ){
		return json_encode( array( 'D' => $data ) );
	}

  /**
  * This function tracks how long the visitor has been on the site while not logged in
  * @return time When the user visited the website, NULL if the user is logged in
  */
  function user_start_time(){
    if ($this->is_logged_in() and isset($_COOKIE['user_start_time'])){
        //mark cookie for deletion
        setcookie ("user_start_time", "", time() - 3600);
        return NULL;
    }
    else if (!isset($_COOKIE['user_start_time']) and (!headers_sent())){
      setcookie('user_start_time', time() ,time()+60*60*24*30);
    }
    return isset($_COOKIE['user_start_time']) ? $_COOKIE['user_start_time'] : time();
  }

  /**
  * Returns the URI which OAuth redirects to.
  * @static
  * @return string The URI.
  */
  static function redirect_uri(){
    return home_url( 'index.php/oauth/callback' );
  }

  /**
  * Returns if the user is logged in or not.
  * @return bool If user is logged in
  */
	public function is_logged_in(){
		global $spark_oauth_global;
		$spark_oauth = array();
		if( isset( $_COOKIE[ 'spark_oauth' ] ) ){
			$spark_oauth = $_COOKIE[ 'spark_oauth' ];
		}
		if( empty( $spark_oauth ) ){
			$spark_oauth = $spark_oauth_global;
		}
		if( !isset( $_COOKIE[ 'spark_oauth' ] ) && empty( $spark_oauth ) ){
			return false;
		}
		return true;
		//return ($this->get_info() ? true: false);
	}

  /**
  * Logs user out. Does NOT end the user's SESSION.
  * Does NOT delete any cookies which may exist.
  */
	public function log_out(){
		setcookie( 'spark_oauth', json_encode( array() ), time() - DAY_IN_SECONDS, '/' );
		$OAuth = new \SparkAPI\OAuth();
		//$OAuth->log_out();
		$request = $OAuth->sign_request( array(
			'cache_duration' => 0,
			'method' => 'GET',
			'params' => array( '_select' => 'DisplayName' ),
			'post_data' => array(),
			'service' => 'my/contact'
		) );

		delete_transient( 'flexmls_query_' . $request[ 'transient_name' ] );

		$request = $OAuth->sign_request( array(
			'cache_duration' => 0,
			'method' => 'GET',
			'params' => array(),
			'post_data' => array(),
			'service' => 'listingcarts'
		) );

		delete_transient( 'flexmls_query_' . $request[ 'transient_name' ] );

		$request = $OAuth->sign_request( array(
			'cache_duration' => 0,
			'method' => 'GET',
			'params' => array( '_select' => 'Name' ),
			'post_data' => array(),
			'service' => 'savedsearches'
		) );

		delete_transient( 'flexmls_query_' . $request[ 'transient_name' ] );

		return true;
	}

  /**
  * Gets the URI of the Site Owner's Portal Page
  * @param bool $signup If true, returns the portal signup page instead of login page.
  * @param string $page_override URI of current page/state (For Ajax Use)
  * @return string URI of the portal page
  */
  public function get_portal_page($signup=false, $additional_state_params=array(), $page_override=null){
    global $fmc_api;
    $options = get_option('fmc_settings');
    $Name = $fmc_api->GetPortal();
    //$raw_state = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $protocol = (is_ssl()) ? 'https' : 'http';
    $raw_state = parse_url("$protocol://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
    if ($page_override != null and count($additional_state_params)>0){
      $raw_state = parse_url($page_override);
    }

    if (isset($raw_state['query'])){
      parse_str($raw_state['query'], $query_params);
    } else {
      $query_params=array();
    }
    $raw_state['query'] = http_build_query(array_merge($query_params, $additional_state_params));

    if ($page_override != null and count($additional_state_params)>0){
      $page = explode('?',$page_override);
      $state = $page[0].'?'.$raw_state['query'];
    }
    else
      $state = "$protocol://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

    $page_conditions = array(
      'response_type' =>'code',
      'client_id' =>  $options['oauth_key'],
      'redirect_uri' => $this->redirect_uri(),
      'state' => $state,
    );
    $main_link = "https://portal.flexmls.com/r/login/".$Name[0]['Name'];
    if ($signup)
      $main_link.='/signup/';
    return $main_link . '?' . http_build_query($page_conditions);
  }

  /**
  * Wrapper for an API request to get User's information needed.
  *   @link is_logged_in
  * @return Array
  */
	public function get_info(){
		if( !isset( $this->user_info ) ){
			$me = $this->get_from_api( 'GET', 'my/contact', 10 * MINUTE_IN_SECONDS, array( '_select' => 'DisplayName' ) );
			if( 1 == $me[ 'success' ] ){
				$this->user_info = $me[ 'results' ][ 0 ];
			}
			//return $this->return_first_result( $this->MakeAPICall("GET", "my/contact", "10m", $params) );
			//$this->user_info = $this->MyContact( array( '_select' => 'DisplayName' ) );
		}
		return $this->user_info;
	}

	public function CreateSavedSearch( $data ){
		return $this->get_all_results( $this->get_from_api( 'POST', 'savedsearches', 0, [], $data ) );
	}

	public function GetMySavedSearches( $contact_id ){
		return $this->get_all_results( $this->get_from_api( 'GET', 'savedsearches', 0, array( '_select' => 'Name', '_limit' => 25 ) ) );
	}

  /**
  * Wrapper for an API request to get User's Listing Carts.
  * @return Array
  */
	public function GetListingCarts(){
		if( !isset( $this->carts ) ){
			$this->carts = $this->get_all_results( $this->get_from_api( 'GET', 'listingcarts' ) );
			//$this->carts = parent::GetListingCarts();
		}
		return $this->carts;
	}

  /*
  All of the Following functions were overloaded
  from the parent function to use SESSION vars for access tokens
  */
	function Grant( $code, $type = 'authorization_code' ){
		global $spark_oauth_global;
		$spark_oauth = array();
		if( isset( $_COOKIE[ 'spark_oauth' ] ) ){
			$spark_oauth = $_COOKIE[ 'spark_oauth' ];
			$spark_oauth = json_decode( stripslashes( $_COOKIE[ 'spark_oauth' ] ), true );
		}
		if( empty( $spark_oauth ) ){
			$spark_oauth = $spark_oauth_global;
		}
		$body = array(
			'client_id' => $this->oauth_key,
			'client_secret' => $this->oauth_secret,
			'grant_type' => $type,
			'redirect_uri' => $this->oauth_redirect_uri
		);

		if( 'authorization_code' == $type ){
			$body[ 'code' ] = $code;
		}
		if( 'refresh_token' == $type ){
			$body[ 'refresh_token' ] = $code;
		}

		$response = $this->make_api_call( 'POST', 'oauth2/grant', '1s', array(), json_encode( $body ) );

		if( isset( $response[ 'access_token' ] ) ){
			$spark_oauth_global = array(
				'access_token' => $response[ 'access_token' ],
				'last_token' => $response[ 'access_token' ],
				'refresh_token' => $response[ 'refresh_token' ]
			);
			setcookie( 'spark_oauth', json_encode( $spark_oauth_global ), time() + 30 * DAY_IN_SECONDS, '/' );
			//$this->SetAccessToken( $response[ 'access_token' ] );
			//$this->SetRefreshToken( $response[ 'refresh_token' ] );
			if( is_callable( $this->access_change_callback ) ){
				call_user_func( $this->access_change_callback, 'oauth', array( 'access_token' => $response[ 'access_token' ], 'refresh_token' => $response[ 'refresh_token' ] ));
			}
			return true;
		}
		return false;
	}

	function make_api_call( $method, $service, $cache_time = 0, $params = array(), $post_data = null, $a_retry = false ){
		global $spark_oauth_global;
		$spark_oauth = array();
		if( isset( $_COOKIE[ 'spark_oauth' ] ) ){
			$spark_oauth = $_COOKIE[ 'spark_oauth' ];
			$spark_oauth = json_decode( stripslashes( $_COOKIE[ 'spark_oauth' ] ), true );
		}
		if( empty( $spark_oauth ) ){
			$spark_oauth = $spark_oauth_global;
		}

		if( isset( $spark_oauth[ 'last_token' ] ) ){
			$this->api_headers[ 'Authorization' ] = 'OAuth ' . $spark_oauth[ 'last_token' ];
		}

		$seconds_to_cache = \FlexMLS\Admin\Formatter::parse_cache_time( $cache_time );

		$method = sanitize_text_field( $method );

		$request = array(
			'cache_duration' => $seconds_to_cache,
			'method' => $method,
			'params' => $params,
			'post_data' => $post_data,
			'service' => $service
		);

		$request = $this->sign_request( $request );

		$transient_name = 'flexmls_query_' . $request[ 'transient_name' ];

		$is_auth_request = ( 'session' == $request[ 'service' ] ? true : false );

		if( $is_auth_request ){
			return $this->generate_auth_token();
		}

		$return = array();

		if( false === ( $json = get_transient( $transient_name ) ) ){
			$url = 'https://' . $this->api_base . '/' . $this->api_version . '/' . $service . '?' . $request[ 'query_string' ];
			$json = array();
			$args = array(
				'method' => $method,
				'headers' => $this->api_headers,
				'body' => $post_data
			);
			$response = wp_remote_request( $url, $args );

			$return = array(
				'http_code' => wp_remote_retrieve_response_code( $response )
			);

			if( is_wp_error( $response ) ){
				add_action( 'admin_notices', array( $this, 'admin_notices_api_connection_error' ) );
				return $return;
			}
			$json = json_decode( wp_remote_retrieve_body( $response ), true );
			if( !is_array( $json ) ){
				// The response wasn't JSON as expected so bail out with the original, unparsed body
				$return[ 'body' ] = $json;
				return $return;
			}
			if( array_key_exists( 'D', $json ) ){
				if( true == $json[ 'D' ][ 'Success' ] && 'GET' == strtoupper( $method ) && $service != 'oauth2/grant' ){
					set_transient( 'flexmls_query_' . $request[ 'transient_name' ], $json, $seconds_to_cache );
				} elseif( isset( $json[ 'D' ][ 'Code' ] ) && 1020 == $json[ 'D' ][ 'Code' ] ){
					delete_transient( 'flexmls_auth_token' );
					if( method_exists( $this, 'generate_auth_token' ) && $this->generate_auth_token() ){
						$json = $this->make_api_call( $method, $service, 0, array(), null, false );
					}
				}
			}
		}
		return $json;
	}

	function SetAccessToken( $token ){
		global $spark_oauth_global;
		$spark_oauth = array();
		if( isset( $_COOKIE[ 'spark_oauth' ] ) ){
			$spark_oauth = $_COOKIE[ 'spark_oauth' ];
			$spark_oauth = json_decode( stripslashes( $_COOKIE[ 'spark_oauth' ] ), true );
		}
		if( empty( $spark_oauth ) ){
			$spark_oauth = $spark_oauth_global;
		}

		$spark_oauth[ 'access_token' ] = $token;
		$spark_oauth_global[ 'access_token' ] = $token;
		$spark_oauth[ 'last_token' ] = $token;
		$spark_oauth_global[ 'last_token' ] = $token;
		setcookie( 'spark_oauth', json_encode( $spark_oauth ), time() + 30 * DAY_IN_SECONDS, '/' );
	}

	function SetRefreshToken($token) {
		global $spark_oauth_global;
		$spark_oauth = array();
		if( isset( $_COOKIE[ 'spark_oauth' ] ) ){
			$spark_oauth = $_COOKIE[ 'spark_oauth' ];
			$spark_oauth = json_decode( stripslashes( $_COOKIE[ 'spark_oauth' ] ), true );
		}
		if( empty( $spark_oauth ) ){
			$spark_oauth = $spark_oauth_global;
		}

		$spark_oauth[ 'refresh_token' ] = $token;
		$spark_oauth_global[ 'refresh_token' ] = $token;
		setcookie( 'spark_oauth', json_encode( $spark_oauth ), time() + 30 * DAY_IN_SECONDS, '/' );
	}

	function sign_request( $request ){
		$options = get_option( 'fmc_settings' );
		$security_string = $options[ 'api_secret' ] . 'ApiKey' . $options[ 'api_key' ];

		$request[ 'cacheable_query_string' ] = build_query( $request[ 'params' ] );
		$params = $request[ 'params' ];

		$post_body = '';
		if( 'POST' == $request[ 'method' ] && !empty( $request[ 'post_data' ] ) ){
			// the request is to post some JSON data back to the API (like adding a contact)
			$post_body = $request[ 'post_data' ];
		}

		$is_auth_request = ( 'session' == $request[ 'service' ] ? true : false );

		if( $is_auth_request ){
			$params[ 'ApiKey' ] = $options[ 'api_key' ];
		} else {
			$params[ 'AuthToken' ] = '';
			$auth_token = get_transient( 'flexmls_auth_token' );
			if( $auth_token ){
				$params[ 'AuthToken' ] = $auth_token[ 'D' ][ 'Results' ][ 0 ][ 'AuthToken' ];
			}

			$security_string .= 'ServicePath' . rawurldecode( '/' . $this->api_version . '/' . $request[ 'service' ] );

			ksort( $params );

			$params_encoded = array();

			foreach( $params as $key => $value ){
				$security_string .= $key . $value;
				$params_encoded[ $key ] = urlencode( $value );
			}
		}
		if( !empty( $post_body ) ){
			// add the post data to the end of the security string if it exists
			$security_string .= $post_body;
		}

		$params_encoded[ 'ApiSig' ] = md5( $security_string );

		$request[ 'params' ] = $params_encoded;

		$request[ 'query_string' ] = build_query( $params_encoded );

		if( isset( $params_encoded[ 'AuthToken' ] ) ){
			unset( $params_encoded[ 'AuthToken' ] );
		}
		unset( $params_encoded[ 'ApiSig' ] );

		$params_encoded[ $request[ 'method' ] ] = $request[ 'service' ];

		$request[ 'transient_name' ] = sha1( build_query( $params_encoded ) );

		return $request;
	}

  function ReAuthenticate() {

  	global $spark_oauth_global;
	$spark_oauth = array();
	if( isset( $_COOKIE[ 'spark_oauth' ] ) ){
		$spark_oauth = $_COOKIE[ 'spark_oauth' ];
		$spark_oauth = json_decode( stripslashes( $_COOKIE[ 'spark_oauth' ] ), true );
	}
	if( empty( $spark_oauth ) ){
		$spark_oauth = $spark_oauth_global;
	}

	$spark_oauth[ 'refresh_token' ] = $token;
    if ( isset( $spark_oauth[ 'refresh_token' ] ) && !empty( $spark_oauth[ 'refresh_token' ] ) ) {
      return $this->Grant(unserialize( $spark_oauth[ 'refresh_token' ] ), 'refresh_token');
    }
    return false;
  }




	function AddListingsToCart( $id, $listings ){
		$data = array( 'ListingIds' => $listings );
		return $this->get_all_results( $this->get_from_api( 'POST', 'listingcarts/' . $id, 0, array(), $this->make_sendable_body( $data ) ) );
		//return $this->return_all_results( $this->MakeAPICall("POST", "listingcarts/".$id, 0, array(), $this->make_sendable_body($data) ) );
	}

	function DeleteListingsFromCart( $id, $listing ){
		return $this->get_all_results( $this->get_from_api( 'DELETE', 'listingcarts/' . $id . '/listings/' . $listing ) );
		//return $this->return_all_results( $this->MakeAPICall("DELETE", "listingcarts/".$id."/listings/".$listing) );
	}

  /*
  function sign_request($request) {
    //$last_token = isset($_SESSION['last_token']) ? unserialize($_SESSION['last_token']) : '';
    $this->SetHeader('Authorization', 'OAuth '. $last_token);
    // reload headers into request
    $request['headers'] = $this->headers;
    $request['query_string'] = http_build_query($request['params']);
    $request['cacheable_query_string'] = $request['query_string'];
    return $request;
  }
  */

}


?>
