<?php

use Bpost\BpostApiClient\BpostException;
use WC_BPost_Shipping\Api\WC_BPost_Shipping_Api_Connector;
use WC_BPost_Shipping\Api\WC_BPost_Shipping_Api_Product_Configuration;


/**
 * Class WC_BPost_Shipping_Configuration_Checker checks the configuration for SHM webservice
 */
class WC_BPost_Shipping_Configuration_Checker {
	/**
	 * @var WC_BPost_Shipping_Api_Product_Configuration
	 */
	private $product_configuration;

	/** @var bool */
	private $has_errors = false;
	/**
	 * @var WC_BPost_Shipping_Api_Connector
	 */
	private $connector;

	/**
	 * WC_BPost_Shipping_Configuration_Checker constructor.
	 *
	 * @param WC_BPost_Shipping_Api_Product_Configuration $product_configuration
	 * @param WC_BPost_Shipping_Api_Connector $connector
	 */
	public function __construct(
		WC_BPost_Shipping_Api_Product_Configuration $product_configuration,
		WC_BPost_Shipping_Api_Connector $connector
	) {
		$this->product_configuration = $product_configuration;
		$this->connector             = $connector;
	}


	/**
	 * Check admin form fields
	 */
	public function environment_check() {
		try {

			if ( ! $this->product_configuration->has_configured_products() ) {
				$this->add_error(
					sprintf(
						bpost__( '%s is enabled, but there is not configured product. Please verify the API URL, and your Shipping Manager (%s)' ),
						'bpost',
						'https://www.bpost.be/ShmBackEnd/private'
					)
				);
			}
		} catch ( BpostException $exception ) {
			$this->handle_exception( $exception );
		} catch ( Exception $exception ) {
			$this->add_error(
				sprintf(
					bpost__( 'An error appears (error %s: %s)' ),
					$exception->getCode(),
					$exception->getMessage()
				)
			);
		}
	}

	/**
	 * @return bool
	 */
	public function is_valid_product_configuration() {
		return ! $this->has_errors && $this->connector->is_online();
	}

	/**
	 * @param BpostException $exception
	 */
	private function handle_exception( BpostException $exception ) {
		$shm_url = 'https://www.bpost.be/ShmBackEnd/private';

		switch ( $exception->getCode() ) {
			case 401: // Invalid response
				$this->add_error(
					sprintf(
						bpost__( 'The credentials seem incorrect. Please verify them on your Shipping Manager (%s)' ),
						$shm_url
					)
				);
				break;

			case 404: // Page not found
			case 6: // Could not resolve host
				$this->add_error(
					sprintf(
						bpost__( 'The URL seems incorrect (error %s). Please verify it in your Shipping Manager (%s)' ),
						$exception->getCode(),
						$shm_url
					)
				);
				break;

			case 60: // SSL certificate problem
				$this->add_error(
					sprintf(
						bpost__( 'An SSL error appears (error %s: %s). Please verify your curl version (%s) and OpenSSL version (%s) are valid with bpost API' ),
						$exception->getCode(),
						$exception->getMessage(),
						curl_version()['version'],
						OPENSSL_VERSION_TEXT
					)
				);
				break;

			default:
				$this->add_error(
					sprintf(
						bpost__( 'An error appears (error %s: %s). Please verify it in your Shipping Manager (%s)' ),
						$exception->getCode(),
						$exception->getMessage(),
						$shm_url
					)
				);
				break;
		}
	}

	/**
	 * @param string $error
	 */
	public function add_error( $error ) {
		$this->has_errors = true;
		\WC_Admin_Settings::add_error( $error );
	}
}
