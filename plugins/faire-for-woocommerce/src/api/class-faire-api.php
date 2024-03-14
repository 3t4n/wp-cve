<?php
/**
 * Faire API functionality.
 *
 * @package  FAIRE
 */

namespace Faire\Wc\Api;

use Exception;
use Faire\Wc\Api\Client\Auth;
use Faire\Wc\Api\Client\Api_Client;
use Faire\Wc\Api\Client\Product_Client;
use Faire\Wc\Api\Client\Order_Client;
use Faire\Wc\Api\Drivers\Json_Api_Driver;
use Faire\Wc\Api\Drivers\Logging_Driver;
use Faire\Wc\Api\Drivers\Wp_Api_Driver;
use Faire\Wc\Api\Interfaces\Api_Auth_Interface;
use Faire\Wc\Api\Interfaces\Api_Driver_Interface;
use Faire\Wc\Admin\Settings;
use WC_Logger;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Faire_Api {

	/**
	 * The URL to the API.
	 */
	const API_URL_PRODUCTION = 'https://faire.com/external-api/v2/';

	/**
	 * The URL to the staging API.
	 */
	const API_URL_STAGING = 'https://faire-stage.com/external-api/v2/';

	/**
	 * The API driver instance.
	 *
	 * @var Api_Driver_Interface
	 */
	protected $api_driver;

	/**
	 * The API authorization instance.
	 *
	 * @var Api_Auth_Interface
	 */
	protected $api_auth;

	/**
	 * The API client instance.
	 *
	 * @var Api_Client|Order_Client|Product_Client
	 */
	protected $api_client;

	/**
	 * The settings.
	 *
	 * @var Settings
	 */
	protected Settings $settings;

	/**
	 * The logger instance.
	 *
	 * @var WC_Logger
	 */
	protected WC_Logger $logger;

	/**
	 * Constructor.
	 *
	 * @param string $api_client Fully qualified name of the client class.
	 *
	 * @throws Exception If an error occurred while creating the API driver, auth or client.
	 */
	public function __construct( string $api_client = '' ) {
		$this->logger     = new WC_Logger();
		$this->settings   = new Settings();
		$this->api_driver = $this->create_api_driver();
		$this->api_auth   = $this->create_api_auth();
		$this->api_client = $this->create_api_client( $api_client );
	}

	/**
	 * Initializes the API client instance.
	 *
	 * @param string $api_client Fully qualified name of the API client class.
	 *
	 * @return Api_Client|Order_Client|Product_Client
	 *
	 * @throws Exception If failed to create the API client.
	 */
	protected function create_api_client( string $api_client = '' ): Api_Client {
		if ( ! $api_client ) {
			$api_client = __NAMESPACE__ . '\Client\Api_Client';
		}

		// Create the API client, using this instance's driver and auth objects.
		return new $api_client(
			$this->get_api_url(),
			$this->api_driver,
			$this->api_auth
		);
	}

	/**
	 * Initializes the API driver instance.
	 *
	 * @since [*next-version*]
	 *
	 * @return API_Driver_Interface
	 *
	 * @throws Exception If failed to create the API driver.
	 */
	protected function create_api_driver() {
		// Use a standard WordPress-driven API driver to send requests using WordPress' functions.
		$driver = new Wp_Api_Driver();

		// This will log requests given to the original driver and log responses returned from it.
		if ( $this->enable_debug_log() ) {
			$driver = new Logging_Driver( $this->logger, $driver );
		}

		// This will prepare requests given to the previous driver for JSON content.
		// and parse responses returned from it as JSON.
		$driver = new Json_Api_Driver( $driver );

		// The driver decorated using the JSON driver decorator class.
		return $driver;
	}

	/**
	 * Initializes the API auth instance.
	 *
	 * @since [*next-version*]
	 *
	 * @return Api_Auth_Interface
	 *
	 * @throws Exception If failed to create the API auth.
	 */
	protected function create_api_auth() {

		// Get the saved token.
		$token = $this->get_access_token();

		// Create the auth object.
		return new Auth(
			$token
		);
	}

	/**
	 * Retrieves the API URL for the current environment.
	 *
	 * @return string
	 */
	public function get_api_url(): string {
		if ( $this->api_mode_staging() ) {
			return self::API_URL_STAGING;
		} else {
			return self::API_URL_PRODUCTION;
		}
	}

	/**
	 * Retrieves the API access token.
	 *
	 * @return string
	 */
	public function get_access_token(): string {
		return $this->settings->get_api_key();
	}

	/**
	 * Debug log turn off / on
	 *
	 * @return boolean
	 */
	public function enable_debug_log(): bool {
		return $this->settings->get_debug_log();
	}

	/**
	 * Api mode is staging
	 *
	 * @return boolean
	 */
	public function api_mode_staging(): bool {
		if ( $this->settings->get_api_mode() === 'staging' ) {
			return true;
		}
		return false;
	}

	/**
	 * Api sync is enabled
	 *
	 * @return boolean
	 */
	public function api_sync_enabled(): bool {
		return $this->settings->is_sync_enabled();
	}

	/**
	 * Get brand profile
	 *
	 * @return object
	 * @throws Exception
	 */
	public function get_brand_profile(): object {
		return $this->api_client->get_brand_profile();
	}

	/**
	 * Test API connection
	 *
	 * @return boolean
	 * @throws Exception
	 */
	public function test_connection(): bool {
		return $this->api_client->test_connection();
	}

}
