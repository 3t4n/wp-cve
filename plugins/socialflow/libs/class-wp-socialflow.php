<?php
/**
 * Holds main SocialFlow plugin class
 *
 * @package SocialFlow
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit( 'No direct script access allowed' );
}
/**
 * WP_SocialFlow - WordPress SocialFlow Library
 *
 * @author Pete Mall
 */

// Load the OAuth library.
if ( ! class_exists( 'OAuthConsumer' ) ) {
	require plugin_dir_path( __FILE__ ) . 'OAuth.php';
}

/**
 * Wp SocialFlow
 *
 * @package WP_SocialFlow
 */
class WP_SocialFlow {
	/**
	 * SocialFlow host url
	 *
	 * @since 1.0
	 * @access private
	 * @var string
	 */
	public $host = 'https://api.socialflow.com/';

	/**
	* Oauth request token url
	*
	* @since 2.0
	* @access private
	* @var string
	*/
	const REQUEST_TOKEN_URL = 'https://app.socialflow.com/oauth/request_token';

	/**
	* Oauth access token url
	*
	* @since 2.0
	* @access private
	* @var string
	*/
	const ACCESS_TOKEN_URL = 'https://app.socialflow.com/oauth/access_token';

	/**
	* Oauth authorize url
	*
	* @since 2.0
	* @access private
	* @var string
	*/
	const AUTHORIZE_URL = 'https://app.socialflow.com/oauth/authorize';

	/**
	 * Create new api object
	 *
	 * @since 1.0
	 * @access public
	 *
	 * @param string $consumer_key consumer key.
	 * @param string $consumer_secret consumer secret.
	 * @param string $oauth_token oauth token key.
	 * @param string $oauth_token_secret oauth token secret.
	 */
	public function __construct( $consumer_key, $consumer_secret, $oauth_token = null, $oauth_token_secret = null ) {
		$this->signature_method = new OAuthSignatureMethod_HMAC_SHA1();
		$this->consumer         = new OAuthConsumer( $consumer_key, $consumer_secret );

		if ( ! empty( $oauth_token ) && ! empty( $oauth_token_secret ) ) {
			$this->token = new OAuthConsumer( $oauth_token, $oauth_token_secret );
		} else {
			$this->token = null;
		}
	}

	/**
	 * Get oauth request token
	 *
	 * @since 1.0
	 * @access public
	 *
	 * @param string $oauth_callback consumer key.
	 * @return array|boolean
	 */
	public function get_request_token( $oauth_callback = null ) {


		$parameters = array();
		if ( ! empty( $oauth_callback ) ) {
			$parameters['oauth_callback'] = $oauth_callback;
		}

		$request = $this->oauth_request( self::REQUEST_TOKEN_URL, 'GET', $parameters );



		if ( 200 !== wp_remote_retrieve_response_code( $request ) ) {
			return false;
		}
		$token       = OAuthUtil::parse_parameters( wp_remote_retrieve_body( $request ) );
		$this->token = new OAuthConsumer( $token['oauth_token'], $token['oauth_token_secret'] );

		return $token;
	}

	/**
	 * Format and sign an OAuth / API request
	 *
	 * @since 1.0
	 * @access public
	 *
	 * @param string $url url for sending request.
	 * @param string $method .
	 * @param array  $parameters additional parameters.
	 * @return array
	 */
	private function oauth_request( $url, $method, $parameters ) {

		$request = OAuthRequest::from_consumer_and_token( $this->consumer, $this->token, $method, $url, $parameters );

		$request->sign_request( $this->signature_method, $this->consumer, $this->token );

		$args       = array(
			'sslverify' => false,
			'headers'   => array( 'Authorization' => 'Basic ' . base64_encode( 'sf_partner:our partners' ) ),
			'timeout'   => 20,
		);
		$parameters = array_merge( $request->get_parameters(), $args );

		if ( 'GET' === $method ) {
			$func = function_exists( 'vip_safe_wp_remote_get' ) ? 'vip_safe_wp_remote_get' : 'wp_remote_get';
			return call_user_func( $func, $request->to_url(), $parameters );
		} else {
			return wp_remote_post( $request->to_url(), $parameters );
		}
	}

	/**
	 * Get authorize url
	 *
	 * @since 1.0
	 * @access public
	 *
	 * @param string|array $token current oauth token.
	 * @return string
	 */
	public function get_authorize_url( $token ) {
		if ( is_array( $token ) ) {
			$token = $token['oauth_token'];
		}

		return self::AUTHORIZE_URL . "?oauth_token={$token}";
	}

	/**
	 * Exchange request token and secret for an access token and
	 * secret, to sign API calls.
	 *
	 * @since 1.0
	 * @access public
	 *
	 * @param string $oauth_verifier .
	 * @return array containing token and secret as values ( 'oauth_token' => 'the-access-token', 'oauth_token_secret' => 'the-access-secret' )
	 */
	public function get_access_token( $oauth_verifier = '' ) {
		$parameters = array();
		if ( ! empty( $oauth_verifier ) ) {
			$parameters['oauth_verifier'] = $oauth_verifier;
		}

		$request = $this->oauth_request( self::ACCESS_TOKEN_URL, 'GET', $parameters );
		$token   = OAuthUtil::parse_parameters( wp_remote_retrieve_body( $request ) );

		$this->token = new OAuthConsumer( $token['oauth_token'], $token['oauth_token_secret'] );

		return $token;
	}

	/**
	 * Send single message to socialflow
	 *
	 * @param string $service_user_id service user id.
	 * @param string $account_type account type.
	 * @param array  $args  additional parameters to send.
	 * @param string $account_id additional user id for errors return.
	 *
	 * @return mixed (object|bool) return true if message was successfully sent or WP_Error object
	 */
	public function add_message( $service_user_id = '', $account_type = '', $args = array(), $account_id = '' ) {
		$error = new WP_Error();

		// default args.
		$defaults = array(
			'message'        => '',
			'publish_option' => 'hold',
			'shorten_links'  => 0,
		);

		$account_id = empty( $account_id ) ? $service_user_id : $account_id;

		// Parse incomming $args into an array and merge it with $defaults.
		$args = wp_parse_args( $args, $defaults );

		// Check if required fields are not empty.
		if ( ! empty( $service_user_id ) && ! empty( $account_type ) ) {
			$args['service_user_id'] = $service_user_id;
			$args['account_type']    = $account_type;

			$response = $this->post( 'message/add', $args );

			if ( 200 === wp_remote_retrieve_response_code( $response ) ) {
				// Return posted message on success.
				$message = json_decode( wp_remote_retrieve_body( $response ), true );

				if ( $message && isset( $message['data']['content_item'] ) ) {
					return $message['data']['content_item'];
				} else {

					// No content present meaning that some error occurred.
					if ( SF_DEBUG ) {
						$this->parse_responce_errors( $response, $error, $account_id );
					} else {
						return new WP_Error( 'error', __( '<b>Error</b> occurred. Please contact plugin author.', 'socialflow' ) );
					}
				}
			} elseif ( is_wp_error( $response ) ) {
				return $response;
			} elseif ( SF_DEBUG ) {
				$this->parse_responce_errors( $response, $error, $account_id );
			} else {
				$error->add( 'error', __( '<b>Error:</b> Error occurred.', 'socialflow' ), $account_id );
			}
		} else {
			$error->add( 'required', __( '<b>Required:</b> Message, service user id and account type are required params.' ), $account_id );
		}

		return $error;
	}

	/**
	 * Send single media to SocialFlow
	 *
	 * @param string $media_url media_url Image url.
	 *
	 * @return mixed (object|bool) return true if message was successfully sent or WP_Error object
	 */
	public function add_media( $media_url = '' ) {
		$error      = new WP_Error();
		$account_id = null;
		$response   = $this->post( 'message/attach_media', array( 'media_url' => $media_url ) );

		if ( 200 === wp_remote_retrieve_response_code( $response ) ) {

			// Return posted message on success.
			$message = json_decode( wp_remote_retrieve_body( $response ), true );

			if ( $message && isset( $message['data']['media'] ) ) {
				return $message['data']['media'];
			} else {

				// No content present meaning that some error occurred.
				if ( SF_DEBUG ) {
					$this->parse_responce_errors( $response, $error, $account_id );
				} else {
					return new WP_Error( 'error', __( '<b>Error</b> occurred. Please contact plugin author.', 'socialflow' ) );
				}
			}
		} elseif ( is_wp_error( $response ) ) {
			return $response;
		} elseif ( SF_DEBUG ) {
			$this->parse_responce_errors( $response, $error, $account_id );
		} else {
			$error->add( 'error', __( '<b>Error:</b> Error occurred.', 'socialflow' ), $account_id );
		}

		return $error;
	}

	/**
	 * Parse errors in response
	 *
	 * @param string   $response .
	 * @param WP_Error $error .
	 * @param string   $service_user_id .
	 * @return string
	 */
	public function parse_responce_errors( $response, &$error, $service_user_id ) {
				$response = json_decode( wp_remote_retrieve_body( $response ), true );

		if ( ! $response ) {
			return $error;
		}

		// add errors from response.
		if ( isset( $response['data']['errors'] ) && is_array( $response['data']['errors'] ) ) {
			foreach ( $response['data']['errors'] as $resp_error ) {
				if ( is_array( $resp_error ) ) {
					if ( isset( $resp_error['msgid'] ) && isset( $resp_error['message'] ) ) {
						$error->add( 'api', $resp_error['msgid'] . ' ' . $resp_error['message'], $service_user_id );
					}
				} else {
					$error->add( 'api', $resp_error, $service_user_id );
				}
			}
		}

		// add message as errror.
		if ( isset( $response['data']['message'] ) ) {
			$error->add( 'api_message', $response['data']['message'], $service_user_id );
		}

		return $error;
	}

	/**
	 * All multiple messages
	 *
	 * @param string $message .
	 * @param string $service_user_ids .
	 * @param string $account_types .
	 * @param string $publish_option .
	 * @param int    $shorten_links .
	 * @param array  $args .
	 *
	 * @return array|mixed|WP_Error
	 */
	public function add_multiple( $message = '', $service_user_ids, $account_types = '', $publish_option = 'publish now', $shorten_links = 0, $args = array() ) {
		$error = new WP_Error();

		$account_id = is_array( $service_user_ids ) ? array_shift( $service_user_ids ) : $service_user_ids;

		if ( ! ( $message && $account_id && $account_types ) ) {
			$error->add( 'required', __( '<b>Required:</b> Message, service user id and account type are required params.' ), $account_id );
			return $error;
		}

		$parameters = array(
			'message'          => stripslashes( urldecode( $message ) ),
			'service_user_ids' => $service_user_ids,
			'account_types'    => $account_types,
			'publish_option'   => $publish_option,
			'shorten_links'    => $shorten_links,
		);

		$parameters = array_merge( $parameters, $args );

		$response = $this->post( 'message/add_multiple', $parameters );

		sf_log( 'RESPONSE - add_multiple', $response );

		if ( 200 === wp_remote_retrieve_response_code( $response ) ) {
			// Return posted message on success.
			$message = json_decode( wp_remote_retrieve_body( $response ), true );

			if ( $message && isset( $message['data']['content_items'] ) ) {
				return $message['data']['content_items'];
			} else {

				// No content present meaning that some error occurred.
				if ( SF_DEBUG ) {
					$this->parse_responce_errors( $response, $error, $account_id );
				} else {
					return new WP_Error( 'error', __( '<b>Error</b> occurred. Please contact plugin author.', 'socialflow' ) );
				}
			}
		} elseif ( is_wp_error( $response ) ) {
			return $response;
		} elseif ( SF_DEBUG ) {
			$this->parse_responce_errors( $response, $error, $account_id );
		} else {
			$error->add( 'error', __( '<b>Error:</b> Error occurred.', 'socialflow' ), $account_id );
		}

		return $error;
	}

	/**
	 * Get single message by message id
	 *
	 * @param int $content_item_id message id .
	 * @return mixed ( array | object ) array with message data or WP_Error object
	 */
	public function view_message( $content_item_id ) {
		$content_item_id = absint( $content_item_id );

		if ( empty( $content_item_id ) ) {
			return new WP_Error( __( 'Invalid message id passed', 'socialflow' ) );
		}

		$response = $this->get( 'message/view', array( 'content_item_id' => $content_item_id ) );

		if ( 200 === wp_remote_retrieve_response_code( $response ) ) {
			// Return posted message on success.
			$message = json_decode( wp_remote_retrieve_body( $response ), true );

			if ( $message ) {
				return $message['data']['content_item'];
			} else {
				return new WP_Error( 'error', __( '<b>Error:</b> Error occurred, please try again .', 'socialflow' ) );
			}
		} elseif ( is_wp_error( $response ) ) {
			return $response;
		} else {
			return new WP_Error( 'error', __( '<b>Error:</b> Error occurred, please try again .', 'socialflow' ) );
		}
	}

	/**
	 * Get facebook domain
	 */
	public function get_facebook_domain() {
		$response = $this->get( 'v1/facebook_domain/get_verified_facebook_domains' );
		if ( 200 !== wp_remote_retrieve_response_code( $response ) ) {
			return false;
		}
		$response = json_decode( wp_remote_retrieve_body( $response ), true );
		$domains  = [];
		foreach ( $response['data']['verified_domains'] as $domain ) {
			foreach ( $domain['valid_pages'] as $id ) {
				$domains[ $id ] = $domain['domain'];
			}
		}

		if ( ! $response ) {
			return false;
		}

		return $domains;

	}

	/**
	 * Get list of connected accounts
	 */
	public function get_account_list() {
				$response = $this->get( 'account/list' );

		if ( 200 !== wp_remote_retrieve_response_code( $response ) ) {
			return false;
		}

		$response = json_decode( wp_remote_retrieve_body( $response ), true );

		if ( ! $response ) {
			return false;
		}

		$accounts = array();
		foreach ( $response['data']['client_services'] as $account ) {
			$accounts[ $account['client_service_id'] ] = $account;
		}

		return $accounts;
	}

	/**
	 * Shorten links in passed message string
	 *
	 * @param  string $message         Message that may contain links.
	 * @param  string $service_user_id User account that will be used to shorten links.
	 * @param  string $account_type    User account type.
	 * @return array NOTE: may return false on invalid pass parameters or invalid server response
	 */
	public function shorten_links( $message, $service_user_id, $account_type ) {
		if ( ! $message || ! $service_user_id || ! $account_type ) {
			return false;
		}

		$response = $this->get(
			'link/shorten_message', array(
				'service_user_id' => $service_user_id,
				'account_type'    => $account_type,
				'message'         => stripslashes( $message ),
			)
		);

		if ( 200 !== wp_remote_retrieve_response_code( $response ) ) {
			return false;
		}

		// May return false on failure.
		return json_decode( wp_remote_retrieve_body( $response ) )->new_message;
	}
	/**
	 * Get Account link
	 *
	 * @param string $consumer_key .
	 * @return bool
	 */
	public function get_account_links( $consumer_key = '' ) {
		if ( ! $consumer_key ) {
			return false;
		}

		// Wordpress.com Vip recommend to .
		$func = function_exists( 'vip_safe_wp_remote_get' ) ? 'vip_safe_wp_remote_get' : 'wp_remote_get';

		$response = call_user_func(
			$func, "{$this->host}/account/links/?consumer_key={$consumer_key}", array(
				'headers'   => array( 'Authorization' => 'Basic ' . base64_encode( 'sf_partner:our partners' ) ),
				'sslverify' => false,
			)
		);

		if ( 200 === wp_remote_retrieve_response_code( $response ) ) {
			$response = json_decode( wp_remote_retrieve_body( $response ) );
			if ( $response && 200 === $response->status ) {
				return $response->data;
			}
		}

		return false;
	}

	/**
	 * Retrieve user queue
	 *
	 * @param string $service_user_id service user id.
	 * @param string $account_type account type.
	 * @param string $sort_by order queue messages by.
	 * @param int    $page page number.
	 * @param int    $limit limit messages per page.
	 * @return mixed ( json | WP_Error )
	 */
	public function get_queue( $service_user_id, $account_type, $sort_by = 'date', $page = 1, $limit = 5 ) {
							$error = new WP_Error();

		$response = $this->get(
			'contentqueue/list', array(
				'service_user_id' => $service_user_id,
				'account_type'    => $account_type,
				'sort_by'         => $sort_by,
				'page'            => $page,
				'limit'           => $limit,
			)
		);

		if ( 200 === wp_remote_retrieve_response_code( $response ) ) {
			$response = json_decode( wp_remote_retrieve_body( $response ) );
			if ( $response && 200 === $response->status ) {
				return $response->data->content_queue;
			} else {
				$error->add( 'error', __( '<b>Error</b> occured.', 'socialflow' ) );
			}
		} else {
			$error->add( 'error', __( '<b>Error</b> occured.', 'socialflow' ) );
		}

		return $error;
	}

	/**
	 * Delete message from user query
	 *
	 * @param mixed ( string | array ) $content_item_id content_item_id Unique identifier for message. Can be comma separated to delete multiple messages in a Queue.
	 * @param string                   $service_user_id service_user_id unique id from service.
	 * @param string                   $account_type account_type.
	 * @return mixed ( bool | WP_Error ) result of deleting
	 */
	public function delete_message( $content_item_id, $service_user_id, $account_type ) {
		$error = new WP_Error();

		$response = $this->post(
			'message/delete', array(
				'content_item_id' => $content_item_id,
				'service_user_id' => $service_user_id,
				'account_type'    => $account_type,
			)
		);

		if ( 200 === wp_remote_retrieve_response_code( $response ) ) {
			$response = json_decode( wp_remote_retrieve_body( $response ) );
			if ( $response && 200 === $response->status ) {
				return true;
			} else {
				$error->add( 'error', __( '<b>Error</b> occured.', 'socialflow' ) );
			}
		} else {
			$error->add( 'error', __( '<b>Error</b> occured.', 'socialflow' ) );
		}

		return $error;
	}

	/**
	 * GET wrapper for oAuthRequest.
	 *
	 * @param string $url service_user_id unique id from service.
	 * @param array  $parameters account_type.
	 * @return string
	 */
	public function get( $url, $parameters = array() ) {
							$url = $this->host . $url;
		return $this->oauth_request( $url, 'GET', $parameters );
	}

	/**
	 * POST wrapper for oAuthRequest.
	 *
	 * @param string $url service_user_id unique id from service.
	 * @param array  $parameters account_type.
	 * @return array
	 */
	public function post( $url, $parameters = array() ) {
		$url = $this->host . $url;

		sf_log( 'Send request:', compact( 'url', 'parameters' ) );

		$parameters = apply_filters( 'sf_oauth_post_request_params', $parameters );

		return $this->oauth_request( $url, 'POST', $parameters );
	}
}
