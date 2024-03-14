<?php
namespace Enteraddons\Admin;

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

class Admin_API {
	
	/**
	 * $changelog_endpoint 
	 * @var string
	 */
	private $changelog_endpoint;

	/**
	 * call_api 
	 * @return array/object
	 */
	public function call_api() {
		$url 	 = $this->get_remote_url();
		$data 	 =  wp_remote_request( $url, array( 'method' => 'GET' ) );
		$body 	 = wp_remote_retrieve_body( $data );
		$getData = json_decode( $body, true );
		return $getData;
	}

	/**
	 * get_data
	 * @param  string $endpoint 
	 * @return void
	 */
	public function get_data( $endpoint ) {
		$this->changelog_endpoint = $endpoint;
		return $this->call_api();
	}
	
	/**
	 * get_remote_url
	 * @param  $endpoint
	 * @return void
	 */
	public function get_remote_url() {
		$api = new \Enteraddons\Classes\API(); 
		return $api->get_api_url( esc_html( $this->changelog_endpoint ) );
	}

}
