<?php

/**
 * Vision6 Gravity Forms Exception.
 *
 * @since     1.0.0
 * @package   GravityForms
 * @copyright Copyright (c) 2018, Vision6
 */
class GF_Vision6_Exception extends Exception {

	protected $client;


	/**
	 * @return WP_HTTP_IXR_Client|null
	 */
	public function getClient() {
		return $this->client;
	}

	/**
	 * @param WP_HTTP_IXR_Client $client
	 */
	public function setClient( WP_HTTP_IXR_Client $client ) {
		$this->client = $client;
	}

}
