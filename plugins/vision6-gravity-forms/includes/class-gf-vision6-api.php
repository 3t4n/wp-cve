<?php

/**
 * Vision6 Gravity Forms API Library.
 *
 * @since     1.0.0
 * @package   GravityForms
 * @copyright Copyright (c) 2018, Vision6
 */
class GF_Vision6_API {

	/**
	 * Vision6 account API key.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string $api_key Vision6 account API key.
	 */
	protected $api_key;

	/**
	 * Vision6 account host name.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string $hostname Vision6 account host name.
	 */
	protected $hostname;

	/**
	 * Vision6 api version number.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string $api_version_number Vision6 api version number.
	 */
	protected $api_version_number = '3.3';

	/**
	 * Are Vision6 terms accepted?
	 *
	 * @since  1.0.3
	 * @access protected
	 * @var    bool $accepted_terms_and_conditions Are Vision6 terms accepted?
	 */
	protected $accepted_terms_and_conditions = false;

	/**
	 * Should we log responses?
	 *0
	 * @since  1.0.11
	 * @access protected
	 * @var    bool $debug Should we log responses?
	 */
	protected $debug = false;

	/**
	 * Should we display development debug logs?
	 *
	 * @since  1.0.11
	 * @access protected
     * @var    bool $development_debug Should we display development debug logs?
     */
	protected $development_debug = false;

	/**
	 * Initialize API library.
	 *
	 * @param string $api_key (default: '') Vision6 API key.
	 * @param string $api_hostname (default: '') Vision6 API hostname.
     * @param bool $debug Should we log responses?
     * @param bool $development_debug Should we display development debug logs?
     *
     * @since  1.0.0
	 * @access public
	 */
	public function __construct( $api_key = '', $api_hostname = '', $debug = false, $development_debug = false ) {
		$this->api_key              = $api_key;
		$this->hostname             = $api_hostname;
		$this->debug                = $debug;
		$this->development_debug    = $debug && $development_debug;

        // Increase the XML-RPC element limit to allow for higher number of folders/lists.
        add_filter( 'xmlrpc_element_limit', function() { return 150000; } );
	}

	/**
	 * Validate that the API connection is valid
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return bool
	 * @throws Exception
	 */
	public function is_api_valid() {
		$method = 'getCreditBalance';


        try {
			$client = $this->get_ixr_client();
			$result = $client->query( $method, $this->api_key, 'sms' );

			if ( ! $result ) {
				$e = new GF_Vision6_Exception( 'Failed response: ' . print_r( $result, true ) );
				$e->setClient( $client );
				throw $e;
			}

            $this->manage_api_debug_response( $client, $method, [ 'sms' ] );

        } catch ( GF_Vision6_Exception $e ) {

            $client = $e->getClient();
			$this->manage_api_exception( $client, $e->getMessage(), $method, [ 'sms' ] );

			// If this is simply a terms and conditions flag, attempt to return true with an invalid terms flag
            $this->accepted_terms_and_conditions = false;

            if ( $client && $client->error && is_scalar($client->error->code) && (int)$client->error->code === 108 ) {
                return true;
            }

            return false;
		}

        $this->accepted_terms_and_conditions = true;
        return true;
	}

    /**
     * Return the flag that determines that the terms and conditions are accepted
     * NOTE: This value is set within {@see self::is_api_valid()}
     *
     * @since  1.0.3
     * @access public
     *
     * @return bool
     */
	public function is_terms_and_conditions_accepted()
    {
        return $this->accepted_terms_and_conditions;
    }

	/**
	 * Get a specific Vision6 folder.
	 *
	 * @since  1.0.8
	 * @access public
	 *
	 * @param string $folder_id Vision6 folder ID.
	 *
	 * @return array
	 * @throws Exception
	 */
	public function get_folder( $folder_id ) {
		$method = 'getFolderById';

		try {
			$client = $this->get_ixr_client();
			$result = $client->query( $method, $this->api_key, $folder_id );

			if ( ! $result ) {
                $e = new GF_Vision6_Exception( 'Failed response: ' . print_r( $result, true ) );
				$e->setClient( $client );
				throw $e;
			}

            $this->manage_api_debug_response( $client, $method, [ $folder_id ] );

		} catch ( GF_Vision6_Exception $e ) {
			$this->manage_api_exception( $e->getClient(), $e->getMessage(), $method, [ $folder_id ] );

			return [];
		}

		return isset( $client->message->params[0][0] ) ? (array) $client->message->params[0][0] : [];
	}

	/**
	 * Get all Vision6 folders.
	 *
	 * @since  1.0.8
	 * @access public
	 *
	 * @return array
	 * @throws Exception
	 */
	public function get_folders( $type = 'list' ) {
		$method = 'searchFolders';

		try {
			$client = $this->get_ixr_client();
			$result = $client->query( $method, $this->api_key, $type, [], 1000 );

			if ( ! $result ) {
                $e = new GF_Vision6_Exception( 'Failed response: ' . print_r( $result, true ) );
				$e->setClient( $client );
				throw $e;
			}

            $this->manage_api_debug_response( $client, $method );

		} catch ( GF_Vision6_Exception $e ) {
			$this->manage_api_exception( $e->getClient(), $e->getMessage(), $method );

			return [];
		}

		return isset( $client->message->params[0] ) ? (array) $client->message->params[0] : [];
	}

	/**
	 * Get a specific Vision6 list.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @param string $list_id Vision6 list ID.
	 *
	 * @return array
	 * @throws Exception
	 */
	public function get_list( $list_id ) {
		$method = 'getListById';

		try {
			$client = $this->get_ixr_client();
			$result = $client->query( $method, $this->api_key, $list_id );

			if ( ! $result ) {
                $e = new GF_Vision6_Exception( 'Failed response: ' . print_r( $result, true ) );
				$e->setClient( $client );
				throw $e;
			}

            $this->manage_api_debug_response( $client, $method, [ $list_id ] );

		} catch ( GF_Vision6_Exception $e ) {
			$this->manage_api_exception( $e->getClient(), $e->getMessage(), $method, [ $list_id ] );

			return [];
		}

		return isset( $client->message->params[0][0] ) ? (array) $client->message->params[0][0] : [];
	}

	/**
	 * Get all Vision6 lists.
	 *
	 * @since  1.0.0
	 * @access public
	 *
     * @param string $folder_id Vision6 folder ID.
     *
	 * @return array
	 * @throws Exception
	 */
	public function get_lists( $folder_id = 0 ) {
		$method = 'searchLists';

        $search_criteria = [];
        if ( $folder_id ) {
            $search_criteria = [
                [
                    "folder_id", "exactly", $folder_id
                ]
            ];
        }

        try {
			$client = $this->get_ixr_client();
            $result = $client->query( $method, $this->api_key, $search_criteria, 9999 );

			if ( ! $result ) {
                $e = new GF_Vision6_Exception( 'Failed response: ' . print_r( $result, true ) );
				$e->setClient( $client );
				throw $e;
			}

            $this->manage_api_debug_response( $client, $method, [ $search_criteria, 9999 ] );

		} catch ( GF_Vision6_Exception $e ) {
			$this->manage_api_exception( $e->getClient(), $e->getMessage(), $method, [ $search_criteria, 9999 ] );

			return [];
		}

		return isset( $client->message->params[0] ) ? (array) $client->message->params[0] : [];
	}

	/**
	 * Get all fields for a Vision6 list.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @param string $list_id Vision6 list ID.
	 *
	 * @return array
	 * @throws Exception
	 */
	public function get_list_fields( $list_id ) {
		$method = 'searchFields';

		try {
			$client = $this->get_ixr_client();
			$result = $client->query( $method, $this->api_key, $list_id, [], 9999 );

			if ( ! $result ) {
                $e = new GF_Vision6_Exception( 'Failed response: ' . print_r( $result, true ) );
				$e->setClient( $client );
				throw $e;
			}

            $this->manage_api_debug_response( $client, $method, [ $list_id, [], 9999 ] );

		} catch ( GF_Vision6_Exception $e ) {
			$this->manage_api_exception( $e->getClient(), $e->getMessage(), $method, [ $list_id, [], 9999 ] );

			return [];
		}

		return isset( $client->message->params[0] ) ? (array) $client->message->params[0] : [];
	}

	/**
	 * Add a Vision6 contact
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @param string $list_id Vision6 list ID.
	 * @param array $contact Contact details
	 *
	 * @return array
	 * @throws Exception
	 */
	public function add_contacts( $list_id, $contact ) {
		$method = 'addContacts';

		try {
			$client = $this->get_ixr_client();
			$result = $client->query( $method, $this->api_key, $list_id, [ $contact ], false, 0 );

			if ( ! $result ) {
                $e = new GF_Vision6_Exception( 'Failed response: ' . print_r( $result, true ) );
				$e->setClient( $client );
				throw $e;
			}

            $this->manage_api_debug_response( $client, $method, [ $list_id, [ $contact ], false, 0 ] );

		} catch ( GF_Vision6_Exception $e ) {
			$this->manage_api_exception( $e->getClient(), $e->getMessage(), $method, [ $list_id, [ $contact ], false, 0 ] );

			return [];
		}

		return isset( $client->message->params[0] ) ? (array) $client->message->params[0] : [];
	}

    /**
	 * Add a subscribe Vision6 list contact
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @param string $list_id         Vision6 list ID.
	 * @param array  $contact_details Contact details
     * @param string $consent_type    Consent Type
     * @param string $consent_text    Consent Text
     * @param string $ip_address      IP address
	 *
	 * @return array
	 * @throws Exception
	 */
    public function subscribe_contact( $list_id, $contact_details, $consent_type, $consent_text, $ip_address ) {
        $method = 'subscribeContact';
		try {
			$client = $this->get_ixr_client();
			$result = $client->query( $method, $this->api_key, $list_id, $contact_details, $consent_type, $consent_text, [], $ip_address );

			if ( ! $result ) {
                $e = new GF_Vision6_Exception( 'Failed response: ' . print_r( $result, true ) );
				$e->setClient( $client );
				throw $e;
			}

            $this->manage_api_debug_response( $client, $method, [ $list_id, $contact_details, $consent_type, $consent_text, [], $ip_address ] );

		} catch ( GF_Vision6_Exception $e ) {
			$this->manage_api_exception( $e->getClient(), $e->getMessage(), $method, [ $list_id, $contact_details, $consent_type, $consent_text, [], $ip_address ] );

			return [];
		}

        return isset( $client->message->params[0] ) ? (array) $client->message->params[0] : [];
    }

	/**
	 * Create am IXR Client to send requests
	 *
	 * @return WP_HTTP_IXR_Client
	 * @throws Exception
	 */
	private function get_ixr_client() {

		// If API key is not set, throw exception.
		if ( rgblank( $this->api_key ) || rgblank( $this->hostname ) ) {
			throw new GF_Vision6_Exception( 'API key must be defined to process an API request.' );
		}

		include_once( ABSPATH . WPINC . '/class-IXR.php' );
		include_once( ABSPATH . WPINC . '/class-wp-http-ixr-client.php' );


		// Build API endpoint URL.
		$api_endpoint_url = 'https://' . $this->hostname . '/api/xmlrpcserver?version=' . $this->api_version_number;

		// using a timeout of 3 seconds should be enough to cover slow servers
		$client            = new WP_HTTP_IXR_Client( $api_endpoint_url );
		$client->timeout   = 20;
		$client->useragent = $client->useragent . ' -- WordPress/' . get_bloginfo( 'version' );

		// This will output logs directly to the browser
		$client->debug = $this->development_debug;

		return $client;
	}


	/**
	 * Manage any exceptions that may occur when communicating to the API.
	 *
     * @param        $client
     * @param string $method
     * @param array  $parameters
     * @throws GF_Vision6_Exception
     */
	private function manage_api_exception( $client, $error_message = '', $method = '', $parameters = [] ) {
		if ( ! class_exists( 'GFLogging' ) ) {
			return;
		}

		$method      = !empty( $method ) ? $method : 'UnknownMethod';
        $code        = 0;
        $api_message = 'Unknown API error';


        // Search for XMLRPC responses
		if ( $client && $client instanceof WP_HTTP_IXR_Client ) {

			if ( ! empty( $client->error ) ) {
                $code           = isset( $client->error->code ) ? $client->error->code : $code;
                $api_message    = isset( $client->error->message ) ? $client->error->message : $api_message;

			} elseif ( ! empty( $client->message->faultString ) ) {
				$code           = isset( $client->message->faultCode ) ? $client->message->faultCode : $code;
				$api_message    = $client->message->faultString;

			} elseif ( ! empty( $client->message->message ) ) {
				$code           = isset( $client->message->faultCode ) ? $client->message->faultCode : $code;
				$api_message    = $client->message->message;
			}

		} else {
		    $client = null;
        }


		// Log the error
        $message = __METHOD__ . '(): Method: ' . $method . '; Code: ' . strval( $code ) . '; Message: ' . $error_message . '; API Message: ' . $api_message . '; Parameters: ' . print_r( $parameters, true );
        $exception = new GF_Vision6_Exception( $message );

		GFLogging::include_logger();
		GFLogging::log_message( 'gravityformsvision6', $message, KLogger::ERROR );


		// Throw an exception to log the error against the entry, but only on the frontend
        if ( !is_admin() && !GFCommon::is_preview() ) {
            if ( $client ) {
                $exception->setClient($client);
            }

            throw $exception;
        }
	}


	/**
	 * Manage any exceptions that may occur when communicating to the API.
	 *
     * @since  1.0.11
     * @param $client
     * @param string $method
     * @param array $parameters
     */
	private function manage_api_debug_response( $client, $method = '', $parameters = [] ) {
        if ( ! $this->debug || ! class_exists( 'GFLogging' ) ) {
            return;
        }

        // Search for XMLRPC responses
        $response = null;
        if ( $client && $client instanceof WP_HTTP_IXR_Client && ! empty( $client->message ) ) {
            $response = $client->message;
        }

        GFLogging::include_logger();
		GFLogging::log_message( 'gravityformsvision6', __METHOD__ . '(): Method: ' . $method . '; Parameters: ' . PHP_EOL . print_r( $parameters, true ), KLogger::DEBUG );
		GFLogging::log_message( 'gravityformsvision6', __METHOD__ . '(): Method: ' . $method . '; Response: ' . PHP_EOL . print_r( $response, true ), KLogger::DEBUG );
	}

}