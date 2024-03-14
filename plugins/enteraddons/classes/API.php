<?php
namespace Enteraddons\Classes;

/**
 * Enteraddons api config
 *
 * @package     Enteraddons
 * @author      ThemeLooks
 * @copyright   2022 ThemeLooks
 * @license     GPL-2.0-or-later
 *
 *
 */


class API {

	/**
	 * Template library data api url
	 * This API provides themelooks for ready templates, blocks import
	 * 
	 */
	private $hostUrl = 'https://api.enteraddons.com/';
	
	private $apiPath = "wp-json/wp";

	private $version = "v2";

	public function api_url_map() {
		$url = trailingslashit( $this->hostUrl ).trailingslashit( $this->apiPath ).trailingslashit( $this->version );
		return $url;
	}

	public function set_api_url() {
		return $this->api_url_map();
	}

	public function get_api_url( $endpoint = '' ) {

		$apiUrl = $this->set_api_url();
		if( !empty( $endpoint ) ) {
			return $apiUrl.$endpoint;
		} else {
			return $apiUrl;
		}

	}

	public function getRemote( $url ) {

		$response = wp_remote_get( $url,
					    array(
					        'timeout'     => 120,
					        'httpversion' => '1.1',
					    )
					);

		return $response;
	}
	

} // End Class
