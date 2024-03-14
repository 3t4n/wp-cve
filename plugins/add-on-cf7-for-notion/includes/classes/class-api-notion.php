<?php
/**
 * Notion API class.
 *
 * @package add-on-cf7-for-notion
 */

namespace WPC_WPCF7_NTN;

defined( 'ABSPATH' ) || exit;

/**
 * This class is in charge of communicating with the Notion API.
 */
class API_Notion {
	/**
	 * API key.
	 *
	 * @var string
	 */
	protected $secret_token = null;

	/**
	 * API response, raw.
	 *
	 * @var mixed
	 */
	protected $response = null;

	/**
	 * Instanciate this class by passing the API key.
	 *
	 * @param string $secret_token The Notion secret token.
	 */
	public function __construct( $secret_token = null ) {
		$this->secret_token = $secret_token;
	}

	// =========================
	//
	// ###    #####   ##
	// ## ##   ##  ##  ##
	// ##   ##  #####   ##
	// #######  ##      ##
	// ##   ##  ##      ##
	//
	// =========================

	/**
	 * Send a manual request to the Notion API.
	 *
	 * @param string $route Notion API Endpoint.
	 * @param array  $body Request body.
	 * @param string $method Request method.
	 * @return object|\WP_Error
	 */
	protected function request( $route = '', $body = array(), $method = 'POST' ) {
		if ( 'GET' !== $method ) {
			$body = wp_json_encode( $body );
		}

		// Construct headers.
		$headers = array(
			'Content-Type'   => 'application/json',
			'Authorization'  => $this->secret_token,
			'Notion-Version' => '2021-08-16',
		);

		$args = apply_filters(
			'add-on-cf7-for-notion/notion-api/request-args',
			array(
				'timeout' => 15,
				'body'    => $body,
				'method'  => $method,
				'headers' => $headers,
			)
		);

		$url             = sprintf( '%1$s%2$s', 'https://api.notion.com/v1/', $route );
		$this->response  = wp_remote_request( $url, $args );
		$pretty_response = json_decode( wp_remote_retrieve_body( $this->response ) );

		$response_code = (int) wp_remote_retrieve_response_code( $this->response );

		if ($response_code  >= 400 ) {

			$message = __( 'The Notion API returned an error', 'add-on-cf7-for-notion' );
            $message_details = '';
            if ($pretty_response && in_array($pretty_response->code, array('missing_parameter', 'invalid_parameter'), true)) {
                $message_details = $pretty_response->message;
            }
            if ( !empty($message_details) ) {
                $message = $message . ': ' . $message_details;
            }

			$this->response = new \WP_Error(
                $response_code,
                $message,
				[ 'url' => $url, 'body' => $body, 'pretty_response' => $pretty_response, 'response' => $this->response, ]
			);

		}

		do_action( 'add-on-cf7-for-notion/notion-api/response', $route, $pretty_response, $this->response, $body );

		return is_wp_error( $this->response ) ? $this->response : $pretty_response;
	}

	// ============================
	//
	// ###     ####  ######
	// ## ##   ##       ##
	// ##   ##  ##       ##
	// #######  ##       ##
	// ##   ##   ####    ##
	//
	// ============================

	/**
	 * Get a list of users.
	 *
	 * @return object|\WP_Error
	 */
	public function get_users() {
		return $this->request( 'users', array(), 'GET' );
	}

	/**
	 * Get a list of databases.
	 *
	 * @param integer $page_size Number of results to return.
	 * @return object|\WP_Error
	 */
	public function get_databases( $page_size = 50 ) {
		$databases    = array();
		$start_cursor = null;

		do {
			$args = array(
				'page_size' => $page_size,
				'filter'    => array(
					'value'    => 'database',
					'property' => 'object',
				),
			);

			if ( ! is_null( $start_cursor ) ) {
				$args['start_cursor'] = $start_cursor;
			}

			$response = $this->request( 'search', $args, 'POST' );

			if ( is_wp_error( $response ) ) {
				return $response;
			}

			$databases    = array_merge( $databases, isset( $response->results ) ? $response->results : array() );
			$start_cursor = isset( $response->next_cursor ) ? $response->next_cursor : '';

			sleep( 0.25 );
		} while (
			! is_wp_error( $response )
			&& isset( $response->has_more )
			&& (bool) $response->has_more
		);

		return $databases;
	}

	/**
	 * Add a row to a database.
	 *
	 * @param string $database_id The database id.
	 * @param array  $fields The fields values.
	 * @return object|\WP_Error
	 */
	public function add_database_row( $database_id, $fields = array() ) {
		return $this->request(
			'pages',
			array(
				'parent'     => array( 'database_id' => $database_id ),
				'properties' => $fields,
			)
		);
	}
}
