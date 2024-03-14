<?php
namespace SparkAPI;

defined( 'ABSPATH' ) or die( 'This plugin requires WordPress' );

class OAuth extends Core {

	protected $oauth_base;
	protected $oauth_login_code;
	protected $oauth_login_state;
	protected $oauth_redirect_uri;

	function __construct( $data = array() ){
		parent::__construct();
		$this->oauth_base = 'https://sparkplatform.com/openid';
		$this->oauth_redirect_uri = home_url( 'index.php/oauth/callback' );
	}

	function add_listings_to_cart( $id, $listings ){
		$this->api_headers[ 'Authorization' ] = 'OAuth ' . $this->last_oauth_token();
		$data = array();
		$data[ 'ListingIds' ] = array( $listings );
		return $this->get_all_results( $this->get_from_api( 'POST', 'listingcarts/' . $id, 5, array(), $this->make_sendable_body( $data ) ) );
	}

	function delete_listings_from_cart( $id, $listing ){
		$this->api_headers[ 'Authorization' ] = 'OAuth ' . $this->last_oauth_token();
		return $this->get_all_results( $this->get_from_api( 'DELETE', 'listingcarts/' . $id . '/listings/' . $listing ) );
	}

	function do_login(){
		if( !$this->is_user_logged_in() ){
			$code = '';
			$state = '';
			if( isset( $_GET[ 'code' ] ) && isset( $_GET[ 'state' ] ) ){
				$code = $_GET[ 'code' ];
				$state = $_GET[ 'state' ];
			} else {
				// Portal url was redirected. This could have been another WordPress
				// or plugin rule interferring, or if the portal expects https but the
				// site is http (or vice versa). In any event, we need to pull the
				// GET parameters manually.
				parse_str( $_SERVER[ 'QUERY_STRING' ], $manual_get );
				if( array_key_exists( 'code', $manual_get ) ){
					$code = $manual_get[ 'code' ];
				}
				if( array_key_exists( 'state', $manual_get ) ){
					$state = $manual_get[ 'state' ];
				}
			}
			$code = sanitize_text_field( $code );
			$state = urldecode( $state );
			if( false === filter_var( $state, FILTER_VALIDATE_URL ) ){
				$state = '';
			}
			if( !empty( $code ) && !empty( $state ) ){
				$this->oauth_login_code = $code;
				$this->oauth_login_state = $state;
				if( $this->generate_oauth_token() ){
					exit( '<meta http-equiv="refresh" content="0; url=' . $state . '">' );
				}
			}
		}
	}

	function get_portal( $params = array() ){
		return $this->get_all_results( $this->get_from_api( 'GET', 'portal', 5 * HOUR_IN_SECONDS, $params ) );
	}

	function get_portal_page( $signup = false, $additional_state_params = array(), $page_override = null ){
		$fmc_settings = get_option( 'fmc_settings' );
		$current_uri = \FlexMLS\Admin\Utilities::get_current_page_url();
		$portal = $this->get_portal();
		$portal_uri = 'https://portal.flexmls.com/r/login/' . $portal[ 0 ][ 'Name' ] . '?';
		$query_params = array(
			'client_id' => $fmc_settings[ 'oauth_key' ],
			'redirect_uri' => urlencode( $this->oauth_redirect_uri ),
			'response_type' => 'code',
			'state' => urlencode( $current_uri )
		);

		return $portal_uri . build_query( $query_params );
	}

	function generate_oauth_token( $retry = true ){
		$this->log_out();
		global $oauth_token_failures, $spark_oauth_global;
		$fmc_settings = get_option( 'fmc_settings' );
		if( empty( $fmc_settings[ 'oauth_key' ] ) || empty( $fmc_settings[ 'oauth_secret' ] || 2 > $oauth_token_failures ) ){
			return false;
		}

		$body = array(
			'client_id' => $fmc_settings[ 'oauth_key' ],
			'client_secret' => $fmc_settings[ 'oauth_secret' ],
			'redirect_uri' => $this->oauth_redirect_uri
		);

		switch( true ){
			case isset( $_COOKIE[ 'spark_oauth' ] ):
				$cookie = json_decode( stripslashes( $_COOKIE[ 'spark_oauth' ] ) );
				$body[ 'grant_type' ] = 'refresh_token';
				$body[ 'refresh_token' ] = $cookie->refresh_token;
				break;
			case !empty( $spark_oauth_global ):
				$body[ 'grant_type' ] = 'refresh_token';
				$body[ 'refresh_token' ] = $spark_oauth_global[ 'refresh_token' ];
				break;
			case !empty( $this->oauth_login_code ):
				$body[ 'grant_type' ] = 'authorization_code';
				$body[ 'code' ] = $this->oauth_login_code;
				break;
			default:
				return false;
		}

		$url = 'https://' . $this->api_base . '/' . $this->api_version . '/oauth2/grant';
		$args = array(
			'body' => json_encode( $body ),
			'headers' => $this->api_headers
		);

		$response = wp_remote_post( $url, $args );

		if( is_wp_error( $response ) ){
			$oauth_token_failures++;
			if( false !== $retry ){
				$auth_token = $this->generate_oauth_token( false );
			}
		} else {
			$response_code = intval( wp_remote_retrieve_response_code( $response ) );
			if( 200 === $response_code ){
				$json = json_decode( wp_remote_retrieve_body( $response ), true );
				$spark_oauth_global = array(
					'access_token' => $json[ 'access_token' ],
					'refresh_token' => $json[ 'refresh_token' ],
					'last_token' => $body[ 'grant_type' ]
				);
				setcookie( 'spark_oauth', json_encode( $spark_oauth_global ), 30 * DAY_IN_SECONDS, '/' );
				$auth_token = $json[ 'access_token' ];
			} else {
				$auth_token_failures++;
				if( false !== $retry ){
					$auth_token = $this->generate_oauth_token( false );
				} else {
					return false;
				}
			}
		}
		return $auth_token;
	}

	function get_listing_carts(){
		return $this->get_all_results( $this->get_from_api( 'GET', 'listingcarts', 5 ) );
	}

	function get_listing_carts_for( $id ){
		return $this->get_all_results( $this->get_from_api( 'GET', 'listingcarts/for/' . $id, 5 ) );
	}

	function get_user( $params = array() ){
		return $this->get_first_result( $this->get_from_api( 'GET', 'my/contact', 10 * MINUTE_IN_SECONDS, $params ) );
	}

	function is_user_logged_in(){
		global $spark_oauth_global;
		if( isset( $spark_oauth_global ) || isset( $_COOKIE[ 'spark_oauth' ] ) ){
			$this->api_headers[ 'Authorization' ] = 'OAuth ' . $this->last_oauth_token();
			return true;
		}
		return false;
	}

	public static function log_out(){
		global $spark_oauth_global;
		$spark_oauth_global = array();
		if( isset( $_COOKIE[ 'spark_oauth' ] ) ){
			setcookie( 'spark_oauth', '', time() - MONTH_IN_SECONDS, '/' );
			unset( $_COOKIE[ 'spark_oauth' ] );
		}
		return true;
	}

	function last_oauth_token( $retry = true ){
		switch( true ){
			case isset( $_COOKIE[ 'spark_oauth' ] ):
				$cookie = json_decode( stripslashes( $_COOKIE[ 'spark_oauth' ] ) );
				return $cookie->access_token;
				break;
			case isset( $spark_oauth_global[ 'access_token' ] ):
				return $spark_oauth_global[ 'access_token' ];
				break;
		}
		if( true === $retry ){
			$this->generate_oauth_token();
			$this->last_oauth_token( false );
		}
		return false;
	}

}