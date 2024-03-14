<?php
/**
 * Unlink faire products with WC products
 *
 * @package  FAIRE
 */

namespace Faire\Wc\Sync;

use Exception;
use Faire\Wc\Admin\Settings;
use Faire\Wc\Api\Product_Api;
use Faire\Wc\Utils;
use Faire\Wc\Sync\Sync_Product_Scheduler;
use WC_Product_Query;
use WP_Query;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Unlink product class
 */
class Sync_Product_Unlinking {

	/**
	 * Instance of Faire\Wc\Admin\Settings class.
	 *
	 * @var Settings
	 */
	private Settings $settings;

	/**
	 * The scheduler.
	 *
	 * @var Sync_Product_Scheduler
	 */
	private Sync_Product_Scheduler $scheduler;

	/**
	 * Name of the Faire product ID meta field.
	 *
	 * @var string
	 */
	private string $meta_faire_product_id;

	/**
	 * Name of the Faire variant ID meta field.
	 *
	 * @var string
	 */
	private string $meta_faire_variant_id;

	/**
	 * Class constructor.
	 *
	 * @param Settings $settings  Settings class instance.
	 */
	public function __construct( Settings $settings ) {
		$this->settings              = $settings;
		$this->scheduler             = new Sync_Product_Scheduler(
			array( $this, 'run_sync_event' ),
			$this->settings
		);
		$this->meta_faire_product_id = $this->settings->get_meta_faire_product_id();
		$this->meta_faire_variant_id = $this->settings->get_meta_faire_variant_id();
	}

	/**
	 * Empty sync event. Required for instantiation
	 *
	 * @return void
	 */
	public function run_sync_event( $action_type, $id = null ) {
		// empty event
	}

	/**
	 * Handles AJAX call to unlink products.
	 */
	public function ajax_product_unlinking_sync() {
		// Check for nonce security.
		$nonce = isset( $_POST['nonce'] ) ?
			sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) :
			'';

		if (
			empty( $nonce ) ||
			! wp_verify_nonce( $nonce, 'faire_product_unlinking_sync' )
		) {
			wp_send_json_error(
				__( 'Product Unlinking failed. Unauthorized request.', 'faire-for-woocommerce' ),
				401
			);
		}

		$result = $this->unlink_all_products_result();

		wp_send_json_success( $result );
	}

	/**
	 * Processes unlink request and returns result response.
	 *
	 * @return array Results of the unlinking.
	 */
	public function unlink_all_products_result(): array {

		$result = array();

		$unlinked_ids = $this->unlink_products( array(), true );
		$num_unlinked = count( $unlinked_ids );

		// Prepare results for response.
		if ( 0 === $num_unlinked ) {

			$result = Utils::create_import_result_entry(
				true,
				__( 'No products found to unlink. ', 'faire-for-woocommerce' )
			);

		} else {

			$result = Utils::create_import_result_entry(
				true,
				sprintf(
					// translators: %s products count.
					__( 'Successfully unlinked %s products.', 'faire-for-woocommerce' ),
					$num_unlinked,
				)
			);
		}

		return $result;
	}

	/**
	 * Unlinks WC products with Faire.
	 *
	 * @param array   $unlink_ids The product ids.
	 * @param boolean $unlink_all Unlink all products.
	 *
	 * @return array Array of unlinked product ids.
	 */
	public function unlink_products( $unlink_ids, $unlink_all = false ): array {

		$unlinked = array();

		$args = array(
			'limit'    => -1,
			'paginate' => false,
			'orderby'  => 'date',
			'order'    => 'DESC',
			'return'   => 'ids',
		// '_faire_id_exists'  => true, // Only unlink linked products
		);

		/**
		 * Array of WC_Product_ID.
		 *
		 * @var array<int> $products
		 */
		$products = wc_get_products( $args );

		foreach ( $products as $id ) {
			// Check if product is linked (or has linked error).
			if ( $this->is_linked_product( $id ) ) {
				// Unlink all or array of ids.
				if ( true === $unlink_all ) {
					if ( $this->unlink_product( $id ) ) {
						$unlinked[] = $id;
					}
				} elseif ( in_array( $id, $unlink_ids ) ) {
					if ( $this->unlink_product( $id ) ) {
						$unlinked[] = $id;
					}
				}
			}
		}

		return $unlinked;
	}

	/**
	 * Unlinks a WC product
	 *
	 * @param int $id The WC product ID.
	 *
	 * @return boolean Unlinked result.
	 */
	public function unlink_product( $id ) {

		$product = wc_get_product( $id );

		// Remove product faire id meta.
		$deleted = delete_post_meta( $id, $this->meta_faire_product_id );

		// Delete faux variant id if set.
		delete_post_meta( $id, $this->meta_faire_variant_id );

		// Delete product sync history, linking errors.
		$deleted_sync_history  = delete_post_meta( $id, '_faire_product_sync_result' );
		$deleted_linking_error = delete_post_meta( $id, '_faire_product_linking_error' );
		delete_post_meta( $id, '_faire_product_linking_error_faire_id' );
		delete_post_meta( $id, '_faire_product_unmatched_variants' );

		// Delete from pending queue.
		$this->scheduler->remove_product_faire_pending_sync( $id, 'create' );
		$this->scheduler->remove_product_faire_pending_sync( $id, 'update' );
		$this->scheduler->remove_product_faire_pending_sync( $id, 'delete' );

		if ( $product && 'variable' === $product->get_type() ) {
			$variations = $product->get_children();
			if ( $variations ) {
				foreach ( $variations as $variation_id ) {
					 // Remove variation faire id meta.
					delete_post_meta( $variation_id, $this->meta_faire_variant_id );
				}
			}
		}

		return ( $deleted || $deleted_sync_history || $deleted_linking_error ) ? true : false;
	}

	/**
	 * Is a WC product linked, look for faire id, also pending linking error and past linked sync result
	 *
	 * @param int $id The WC product ID.
	 *
	 * @return boolean Is linked result.
	 */
	public function is_linked_product( $id ) {
		if ( get_post_meta( $id, $this->meta_faire_product_id ) ) {
			return true;
		} elseif ( get_post_meta( $id, '_faire_product_sync_result' ) ) {
			return true;
		} elseif ( get_post_meta( $id, '_faire_product_linking_error' ) ) {
			return true;
		}
		return false;
	}

}
