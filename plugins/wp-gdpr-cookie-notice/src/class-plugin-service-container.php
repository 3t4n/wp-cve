<?php
/**
 * Felix_Arntz\WP_GDPR_Cookie_Notice\Plugin_Service_Container class
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Felix_Arntz\WP_GDPR_Cookie_Notice;

use Felix_Arntz\WP_GDPR_Cookie_Notice\Contracts\Service_Container;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Contracts\Service;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Exceptions\Invalid_Identifier_Exception;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Exceptions\Duplicate_Identifier_Exception;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Exceptions\Unregistered_Identifier_Exception;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Util\ID_Validator;

/**
 * Class for registering, retrieving and initializing plugin services.
 *
 * @since 1.0.0
 */
class Plugin_Service_Container implements Service_Container {

	use ID_Validator;

	/**
	 * Registered services.
	 *
	 * @since 1.0.0
	 * @var array
	 */
	protected $services = [];

	/**
	 * Adds a service.
	 *
	 * @since 1.0.0
	 *
	 * @param string  $id      Unique identifier for the service.
	 * @param Service $service Service instance.
	 *
	 * @throws Invalid_Identifier_Exception Thrown when the identifier is invalid.
	 * @throws Duplicate_Identifier_Exception Thrown when the identifier is already in use.
	 */
	public function add( string $id, Service $service ) {
		if ( ! $this->is_valid_id( $id ) ) {
			throw Invalid_Identifier_Exception::from_id( $id );
		}

		if ( isset( $this->services[ $id ] ) ) {
			throw Duplicate_Identifier_Exception::from_id( $id );
		}

		$this->services[ $id ] = $service;
	}

	/**
	 * Retrieves an available service.
	 *
	 * @since 1.0.0
	 *
	 * @param string $id Unique identifier of the service.
	 * @return Service Service instance.
	 *
	 * @throws Unregistered_Identifier_Exception Thrown when the service for the identifier is not registered.
	 */
	public function get( string $id ) : Service {
		if ( ! isset( $this->services[ $id ] ) ) {
			throw Unregistered_Identifier_Exception::from_id( $id );
		}

		return $this->services[ $id ];
	}

	/**
	 * Checks if a service is available.
	 *
	 * @since 1.0.0
	 *
	 * @param string $id Unique identifier of the service.
	 * @return bool True if the service is available, false otherwise.
	 */
	public function has( string $id ) : bool {
		return isset( $this->services[ $id ] );
	}

	/**
	 * Gets the available services.
	 *
	 * @since 1.0.0
	 *
	 * @return array Map of $id => $service instance pairs.
	 */
	public function get_all() : array {
		return $this->services;
	}
}
