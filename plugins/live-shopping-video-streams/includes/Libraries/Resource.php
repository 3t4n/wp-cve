<?php namespace Includes\Libraries;

class Resource {

	protected $href;

	protected $client;

	public function __construct( $href = null, $client = null ) {
		$this->href   = $href;
		$this->client = is_null( $client ) ? new Client() : $client;
	}

	public function getHref() {
		return $this->href;
	}

	public function setHref( $href ) {
		$this->href = $href;
	}

	protected function request( $method, $uri, $data = null, $formData = false, $userId = null ) {
		$response = $this->client->request( $method, $uri, $data, $formData, $userId );
		$response->assertValidResponse();
		return $response;
	}
}
