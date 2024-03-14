<?php
/**
 * Sync Brand.
 *
 * @package  FAIRE
 */

namespace Faire\Wc\Sync;

use Exception;
use Faire\Wc\Admin\Settings;
use Faire\Wc\Api\Faire_Api;
use Faire\Wc\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Sync Brand class.
 */
class Sync_Brand {

  /**
	 * The settings.
	 *
	 * @var Settings
	 */
	protected Settings $settings;

	/**
	 * Instance of Faire\Wc\Api\Faire_Api class.
	 *
	 * @var Faire_Api
	 */
	private Faire_Api $api;

	/**
	 * Class constructor.
	 *
	 * @param Faire_Api $api Faire_Api class instance.
	 * @param Settings  $settings  Settings class instance.
	 */
	public function __construct( Faire_Api $api, Settings $settings ) {
		$this->api      = $api;
		$this->settings = $settings;
	}

	/**
	 * Handles AJAX call to get brand.
	 */
	public function ajax_brand_manual_sync() {
		// Check for nonce security.
		$nonce = isset( $_POST['nonce'] ) ?
			sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) :
			'';

		if (
			empty( $nonce ) ||
			! wp_verify_nonce( $nonce, 'faire_brand_manual_sync' )
		) {
			wp_send_json_error(
				__( 'Brand Sync failed. Unauthorized request.', 'faire-for-woocommerce' ),
				401
			);
		}

		$result = $this->import_brand();
		wp_send_json_success( $result );
	}


	/**
	 * Imports brand profile from Faire.
	 *
	 * @return array Results of the brand import.
	 */
	public function import_brand(): array {

		$result = array();

		$response = $this->get_faire_brand();

		// Prepare results for response.
		if ( isset( $response->error ) ) {

			$result = $this->create_import_result_entry(
				false,
				sprintf(
					// translators: %s error.
					__( 'Failed to get brand profile. %s', 'faire-for-woocommerce' ),
					$response->error->code . ': ' . $response->error->message,
				),
				array()
			);

		} else {

			$brand_profile = array(
				'brand_id' => isset( $response->brand_id ) ? (string) $response->brand_id : '',
				'name'     => isset( $response->name ) ? (string) $response->name : '',
				'currency' => isset( $response->currency ) ? (string) $response->currency : '',
				'locale'   => isset( $response->locale ) ? (string) $response->locale : '',
			);

			$this->settings->save_brand_id( $brand_profile['brand_id'] );
			$this->settings->save_brand_name( $brand_profile['name'] );
			$this->settings->save_brand_currency( $brand_profile['currency'] );
			$this->settings->save_brand_locale( $brand_profile['locale'] );

			$result = $this->create_import_result_entry(
				true,
				__( 'Successful brand profile import.', 'faire-for-woocommerce' ),
				$brand_profile
			);
		}

		return $result;
	}

	/**
	 * Get faire Brand Profile
	 *
	 * @return object
	 */
	public function get_faire_brand() {

		try {

			$faire_brand = $this->api->get_brand_profile();
			return $faire_brand;

		} catch ( Exception $e ) {
			$faire_error = (object) array(
				'error' => (object) array(
					'code'    => $e->getCode(),
					'message' => $e->getMessage(),
				),
			);
			return $faire_error;
		}
	}

	/**
	 * Builds a success or error entry to be recorded as an import result.
	 *
	 * @param bool   $is_success True is result was successful.
	 * @param string $info Additional information about the result.
	 * @param array  $brand Brand returned with the result.
	 *
	 * @return array The result entry.
	 */
	public static function create_import_result_entry(
		bool $is_success,
		string $info,
		array $brand = array()
	): array {
		return array(
			'status' => $is_success ? 'success' : 'error',
			'info'   => $info,
			'brand'  => $brand,
		);
	}

}
