<?php

namespace WPCal\ComposerPackages\League\OAuth2\Client\Provider;

class ZoomMeetingResource {
	/**
	 * Raw response
	 *
	 * @var array
	 */
	protected $response;
	/**
	 * Creates new resource owner.
	 *
	 * @param array  $response
	 */
	public function __construct(array $response = array()) {
		$this->response = $response;
	}
	/**
	 * Get resource owner id
	 *
	 * @return string|null
	 */
	public function getId() {
		return $this->response['id'] ?: null;
	}
}
