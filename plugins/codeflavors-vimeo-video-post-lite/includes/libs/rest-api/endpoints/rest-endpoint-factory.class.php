<?php

namespace Vimeotheque\Rest_Api\Endpoints;

use Vimeotheque\Rest_Api\Endpoints\Plugin\Rest_Store_Settings;
use Vimeotheque\Rest_Api\Endpoints\Vimeo_Api\Rest_Pictures_Controller;
use Vimeotheque\Rest_Api\Endpoints\Vimeo_Api\Rest_Search_Controller;
use Vimeotheque\Rest_Api\Endpoints\Vimeo_Api\Rest_Video_Controller;
use Vimeotheque\Rest_Api\Endpoints\Wp\Rest_Post_Create_Controller;
use Vimeotheque\Rest_Api\Endpoints\Wp\Rest_Posts_Controller;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * @ignore
 */
class Rest_Endpoint_Factory {
	/**
	 * @var Rest_Controller_Interface[]
	 */
	private $endpoints;

	/**
	 * Rest_Endpoint_Factory constructor.
	 */
	public function __construct() {
		add_action(
			'rest_api_init',
			[$this, 'init']
		);
	}

	/**
	 * Initialize endpoints
	 */
	public function init(){
		$this->store_endpoint( new Rest_Pictures_Controller() );
		$this->store_endpoint( new Rest_Video_Controller() );
		$this->store_endpoint( new Rest_Search_Controller() );
		$this->store_endpoint( new Rest_Post_Create_Controller() );
		$this->store_endpoint( new Rest_Posts_Controller() );
		$this->store_endpoint( new Rest_Store_Settings() );
	}

	/**
	 * @param Rest_Controller_Interface $controller
	 */
	public function store_endpoint( Rest_Controller_Interface $controller ){
		$this->endpoints[ $controller->get_rest_base() ] = $controller;
	}

	/**
	 * @return Rest_Controller_Interface[]
	 */
	public function get_endpoints(){
		return $this->endpoints;
	}

	/**
	 * @param $rest_base
	 *
	 * @return Rest_Controller_Interface|null
	 */
	public function get_endpoint( $rest_base ){
		return isset( $this->endpoints[ $rest_base ] ) ? $this->endpoints[ $rest_base ] : null;
	}

}