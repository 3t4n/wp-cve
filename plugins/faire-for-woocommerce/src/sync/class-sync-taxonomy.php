<?php
/**
 * Sync Taxonomy.
 *
 * @package  FAIRE
 */

namespace Faire\Wc\Sync;

use Exception;
use Faire\Wc\Admin\Settings;
use Faire\Wc\Api\Product_Api;
use Faire\Wc\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Sync Taxonomy class.
 */
class Sync_Taxonomy {

  /**
	 * The settings.
	 *
	 * @var Settings
	 */
	protected Settings $settings;

	/**
	 * Instance of Faire\Wc\Api\Product_Api class.
	 *
	 * @var Product_Api
	 */
	private Product_Api $product_api;

	/**
	 * Option key to store Faire taxonomy types.
	 *
	 * @var string
	 */
	public const OPTION_FAIRE_TAXONOMY_TYPES = 'faire_taxonomy_types';

	/**
	 * Option key to store Faire taxonomy last sync date.
	 *
	 * @var string
	 */
	public const OPTION_FAIRE_TAXONOMY_TYPES_LAST_SYNC = 'faire_taxonomy_type_last_sync_date';


	/**
	 * Class constructor.
	 *
	 * @param Product_Api $product_api Product_Api class instance.
	 * @param Settings    $settings  Settings class instance.
	 */
	public function __construct( Product_Api $product_api, Settings $settings ) {
		$this->product_api = $product_api;
		$this->settings    = $settings;
	}

	/**
	 * Handles AJAX call to get taxonomy types.
	 *
	 * @return void
	 */
	public function ajax_taxonomy_manual_sync() {
		// Check for nonce security.
		$nonce = isset( $_POST['nonce'] ) ?
			sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) :
			'';

		if (
			empty( $nonce ) ||
			! wp_verify_nonce( $nonce, 'faire_product_taxonomy_manual_sync' )
		) {
			wp_send_json_error(
				__( 'Taxonomy Sync failed. Unauthorized request.', 'faire-for-woocommerce' ),
				401
			);
		}

		$result = $this->import_taxonomy_types();
		wp_send_json_success( $result );
	}


	/**
	 * Imports taxonony types from Faire.
	 *
	 * @return array Results of the taxonomy import.
	 */
	public function import_taxonomy_types(): array {

		$num_tax_imported = 0;
		$date_updated     = gmdate( 'c' );
		$result           = array();

		$response = $this->get_faire_taxonomy_types();

		// If we have taxonomy types.
		if ( $response && isset( $response->taxonomy_types ) ) {
			$types = array();
			foreach ( $response->taxonomy_types as $type ) {
				$types[ $type->id ] = $type->name;
			}
			update_option( self::OPTION_FAIRE_TAXONOMY_TYPES, $types, false );
			$num_tax_imported = count( $types );

			update_option( self::OPTION_FAIRE_TAXONOMY_TYPES_LAST_SYNC, $date_updated );
		}

		// Prepare results for response.
		if ( isset( $response->error ) ) {

			$result = Utils::create_import_result_entry(
				false,
				sprintf(
					// translators: %s error.
					__( 'Failed taxonomy type import. %s', 'faire-for-woocommerce' ),
					$response->error->code . ': ' . $response->error->message,
				),
			);

		} else {

			$result = Utils::create_import_result_entry(
				true,
				sprintf(
					// translators: %s taxonomy count.
					__( 'Successful taxonomy type import. %s imported.', 'faire-for-woocommerce' ),
					$num_tax_imported,
				)
			);
		}

		return $result;
	}

	/**
	 * Get faire Taxonomy types
	 *
	 * @return object
	 */
	public function get_faire_taxonomy_types() {

		try {

			$faire_taxonomy_types = $this->product_api->get_taxonomy_types();
			return $faire_taxonomy_types;

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

}
