<?php

namespace WPCal\ComposerPackages\League\OAuth2\Client\Provider;

class GoToMeetingResourceOwner implements \WPCal\ComposerPackages\League\OAuth2\Client\Provider\ResourceOwnerInterface {
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
		return $this->response['key'] ?: null;
	}
	/**
	 * Get user imageurl
	 *
	 * @return string|null
	 */
	public function getImageurl() {
		if (empty($this->response['pic_url'])) {
			return null;
		}
		return $this->response['pic_url'];
	}
	/**
	 * Alias for getImageurl() for higher compatablility.
	 *
	 * @return string|null
	 */
	public function getAvatar() {
		return $this->getImageurl();
	}
	/**
	 * Alias for getImageurl() for higher compatablility.
	 *
	 * @return string|null
	 */
	public function getPictureUrl() {
		return $this->getImageurl();
	}
	/**
	 * Get resource first name.
	 *
	 * @return string|null
	 */
	public function getFirstName() {
		return $this->response['firstName'] ?: null;
	}
	/**
	 * Get resource last name.
	 *
	 * @return string|null
	 */
	public function getLastName() {
		return $this->response['lastName'] ?: null;
	}
	/**
	 * Get user nickname
	 *
	 * @return string|null
	 */
	public function getNickname() {
		return $this->response['account_id'] ?: null;
	}
	/**
	 * Alias for getNickname() for higher compatablility.
	 *
	 * @return string|null
	 */
	public function getUsername() {
		return $this->getNickname();
	}
	/**
	 * Get resource url.
	 *
	 * @return string|null
	 */
	public function getUrl() {
		return $this->response['url'] ?: null;
	}
	public function getEmail() {
		return $this->response['email'] ?: null;
	}
	/**
	 * Return all of the owner details available as an array.
	 *
	 * @return array
	 */
	public function toArray() {
		return $this->response;
	}
}
