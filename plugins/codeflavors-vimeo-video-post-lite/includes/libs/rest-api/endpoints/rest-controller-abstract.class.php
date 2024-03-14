<?php

namespace Vimeotheque\Rest_Api\Endpoints;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * @ignore
 */
abstract class Rest_Controller_Abstract {
	private $namespace;
	private $rest_base;

	/**
	 * @return mixed
	 */
	public function get_namespace() {
		return $this->namespace;
	}

	/**
	 * @param mixed $namespace
	 */
	public function set_namespace( $namespace ) {
		$this->namespace = $namespace;
	}

	/**
	 * @return mixed
	 */
	public function get_rest_base() {
		return $this->rest_base;
	}

	/**
	 * @param mixed $rest_base
	 */
	public function set_rest_base( $rest_base ) {
		$this->rest_base = $rest_base;
	}

	/**
	 * Returns controller complete Rest API route
	 *
	 * @return string
	 */
	public function get_route(){
		return $this->get_namespace() . $this->get_rest_base();
	}
}