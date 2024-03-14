<?php
/**
 * Watchful connection class.
 *
 * @version     2016-12-20 11:41 UTC+01
 * @package     Watchful WP Client
 * @author      Watchful
 * @authorUrl   https://watchful.net
 * @copyright   Copyright (c) 2020 watchful.net
 * @license     GNU/GPL
 */

namespace Watchful\Helpers;

use stdClass;
use Watchful\Controller\Core;
use Exception;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Watchful Connection class.
 */
class Connection {

	/**
	 * Get signatures for the connection.
	 *
	 * @return array
	 */
	public function get_signatures() {
		$config = array(
			'url'             => 'https://app.watchful.net/api/v1/signatures?limit=0',
			'timeout'         => 300,
			'follow_location' => false,
		);

		$response = $this->get_curl( $config );
		$response = json_decode( $response->data );

		return $response->msg->data;
	}

	/**
	 * Get connection passwords.
	 *
	 * @return stdClass
	 */
	public function get_passwords() {
		$config = array(
			'url'             => 'http://installer.watchful.net/audit-assets/passwords.txt',
			'timeout'         => 300,
			'follow_location' => false,
		);

        return $this->get_curl($config );
	}

	/**
	 * Get connection hash.
	 *
	 * @param string $version The hash version.
	 *
	 * @return array
	 *
	 * @throws Exception If bad response.
	 */
	public function get_hash( $version = null ) {

		if ( ! $version ) {
			$version = Core::get_wp_version();
		}

		$config = array(
			'url'             => 'https://downloads.watchful.net/hashes/w' . $version . '.csv',
			'timeout'         => 300,
			'follow_location' => false,
		);

		$response = $this->get_curl( $config );

		if ( 404 === $response->info['http_code'] || 403 === $response->info['http_code'] ) {
			throw new Exception( 'JMON_SCANNER_COREINTEGRITY_HASHFILE_NOT_FOUND' );
		}

		$data = str_getcsv( $response->data, "\n" ); // Parse the rows.
		foreach ( $data as &$row ) {
			$row = str_getcsv( $row, ',' ); // Parse the items in rows.
		}

		return $data;
	}

	/**
	 * Wrapper for curl so we can have a common set of parameters and possibly
	 * cache it in some parts of the system
	 *
	 * @param array $config A configuration array with the following properties:
	 *                      - string url : address to check.
	 *                      - int timeout (default 60) the connection timeout in seconds.
	 *                      - bool follow_location (default true) true to follow 30x redirects.
	 *                      - array post_data (default empty array) an array of key/values to
	 *                      pass as post data.
	 *
	 * @return false on error | a response object with the following properties
	 *      - data : raw response
	 *      - info : curl info
	 *      - error : curl error
	 */
	public function get_curl( $config ) {
		if ( ! isset( $config['url'] ) ) {
			return false;
		}

		$response = wp_remote_get(
			$config['url'],
			array(
				'timeout'     => empty( $config['timeout'] ) ? 60 : $config['timeout'],
				'redirection' => 20,
				'user-agent'  => 'Watchful/1.0 (+http://www.watchful.net)',
				'sslverify'   => false,
				'headers'     => array( 'Expect' => '' ),
			)
		);

		$result = new stdClass();
		if ( is_wp_error( $response ) ) {
			$result->data  = $response->get_error_data();
			$result->info  = $response->get_error_codes();
			$result->error = $response->get_error_message();
		} else {
			$result->data  = wp_remote_retrieve_body( $response );
			$result->info  = $this->get_info( $config['url'], $response );
			$result->error = wp_remote_retrieve_response_message( $response );
		}

		return $result;
	}

	/**
	 * Get curl info from response.
	 *
	 * @param string $url      The request URL.
	 * @param array  $response The response to wp_remote_get.
	 *
	 * @return array
	 */
	protected function get_info( $url, $response ) {
		$info              = wp_remote_retrieve_headers( $response );
		$info['url']       = $url;
		$info['http_code'] = wp_remote_retrieve_response_code( $response );

		return $info;
	}
}
