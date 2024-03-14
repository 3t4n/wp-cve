<?php
/**
 * Sync and link faire products to WC products by sku
 *
 * @package  FAIRE
 * @version  1.0.0
 */

namespace Faire\Wc\Sync;

use Exception;
use Faire\Wc\Admin\Settings;
use Faire\Wc\Api\Product_Api;
use Faire\Wc\Sync\Sync_Product;
use Faire\Wc\Sync\Sync_Product_Scheduler;
use Faire\Wc\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Sync Product Linking class.
 */
class Sync_Product_Linking {

	/**
	 * Max number of products per page.
	 */
	const PRODUCTS_PER_PAGE = 50;

	/**
	 * Instance of Faire\Wc\Api\Product class.
	 *
	 * @var Product_Api
	 */
	private Product_Api $product_api;

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
	 * Arguments to retrieve Faire linking products.
	 *
	 * @var array
	 */
	private array $faire_link_products_args;

	/**
	 * Setting to save the results of an linking products sync.
	 *
	 * @var string
	 */
	const SETTING_PRODUCTS_LINKING_SYNC_RESULTS = 'product_linking_sync_results';

	/**
	 * Date of when last sync was started.
	 *
	 * @var string
	 */
	const OPTION_PRODUCTS_LINKING_LAST_SYNC_DATE = 'faire_products_linking_last_sync_date';

	/**
	 * Option to save the time last sync finished.
	 *
	 * @var string
	 */
	const OPTION_PRODUCTS_LINKING_LAST_SYNC_FINISH_TIME = 'faire_products_linking_last_sync_finish_timestamp';

	/**
	 * Meta key for storing product unmatched faire variants
	 *
	 * @var string
	 */
	const META_FAIRE_PRODUCT_UNMATCHED_VARIANTS = '_faire_product_unmatched_variants';

	/**
	 * Meta key for storing product linking error code
	 *
	 * @var string
	 */
	const META_FAIRE_PRODUCT_LINKING_ERROR = '_faire_product_linking_error';

	/**
	 * Meta key for storing faire product id that was attempted to link with
	 *
	 * @var string
	 */
	const META_FAIRE_PRODUCT_LINKING_ERROR_FAIRE_ID = '_faire_product_linking_error_faire_id';

	/**
	 * Option key for storing product pending create
	 *
	 * @var string
	 */
	const OPTION_FAIRE_PRODUCT_LINKING_CREATE = 'faire_product_linking_create_products_csv';

	/**
	 * Option key for storing variant pending create
	 *
	 * @var string
	 */
	const OPTION_FAIRE_VARIATIONS_LINKING_CREATE = 'faire_product_linking_create_variations_csv';

	/**
	 * Signals a product match is invalid.
	 *
	 * @var int
	 */
	const PRODUCTS_LINKING_PRODUCT_MATCH_INVALID = -1;

	/**
	 * Signals a product sku is empty.
	 *
	 * @var int
	 */
	const PRODUCTS_LINKING_PRODUCT_EMPTY_SKU = -2;


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
	 * @param Product_Api $product_api Instance of Faire\Wc\Api\Product class.
	 * @param Settings    $settings  Instance of Faire\Wc\Admin\Settings class.
	 */
	public function __construct( Product_Api $product_api, Settings $settings ) {
		$this->product_api           = $product_api;
		$this->settings              = $settings;
		$this->scheduler             = new Sync_Product_Scheduler(
			array( $this, 'run_sync_event' ),
			$this->settings
		);
		$this->meta_faire_product_id = $this->settings->get_meta_faire_product_id();
		$this->meta_faire_variant_id = $this->settings->get_meta_faire_variant_id();
	}

	/**
	 * On event: run a single sync event
	 *
	 * @return void
	 */
	public function run_sync_event( $action_type, $id = null ) {
		// empty event
	}

	/**
	 * Checks if faire products exist.
	 *
	 * @return array Results of the products check;
	 */
	public function check_if_faire_products_exist(): array {

		$args         = array();
		$default_args = array(
			'page'  => 1,
			'limit' => 10,
		);

		$args = wp_parse_args( $args, $default_args );

		try {
			$response = $this->product_api->get_products( $args );
		} catch ( Exception $e ) {
			$response = (object) array(
				'error' => (object) array(
					'code'    => $e->getCode(),
					'message' => $e->getMessage(),
				),
			);
		}

		// Prepare results for response.
		if ( isset( $response->error ) ) {
			return $this->create_product_check_result_entry(
				false,
				sprintf(
					// translators: %s error.
					__( 'Failed to check for existing products. %s', 'faire-for-woocommerce' ),
					$response->error->code . ': ' . $response->error->message,
				),
				null
			);
		}

		if ( ! isset( $response->products ) || ! count( $response->products ) > 0 ) {
			return $this->create_product_check_result_entry(
				true,
				__( 'Existing products were not found.', 'faire-for-woocommerce' ),
				false
			);
		}

		return $this->create_product_check_result_entry(
			true,
			__( 'Existing products were found.', 'faire-for-woocommerce' ),
			true
		);
	}

	/**
	 * Handles Ajax requests to sync Faire products.
	 */
	public function ajax_product_linking_manual_sync() {
		// Check for nonce security.
		$nonce = isset( $_POST['nonce'] ) ?
			sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) :
			'';

		if (
			empty( $nonce ) ||
			! wp_verify_nonce( $nonce, 'faire_product_linking_manual_sync' )
		) {
			wp_send_json_error(
				__( 'Product linking sync failed. Unauthorized request.', 'faire-for-woocommerce' ),
				401
			);
		}

		$result = $this->link_products();

		$response = array(
			'message'        => $this->get_products_linking_sync_results(),
			'products_csv'   => (bool) $this->get_products_create_csv(),
			'variations_csv' => (bool) $this->get_variants_create_csv(),
		);
		wp_send_json_success( $response );
	}

	/**
	 * Runs linking product sync and saves results.
	 *
	 * @return array
	 */
	public function link_products(): array {
		$this->empty_product_create_csv();
		$this->empty_variant_create_csv();
		$this->settings->save_initial_setup_products_exist( false ); // Clear existing products found flag.
		$results = $this->import_products_linking();
		$this->save_last_sync_finish_timestamp();
		$this->save_products_linking_sync_results( $results );
		return $results;
	}

	/**
	 * Imports and links linking products from Faire into the shop.
	 *
	 * @return array Results of the linking products import.
	 */
	public function import_products_linking(): array {
		$total_number_of_linked_products = 0;

		$this->faire_link_products_args = array(
			'page' => 1,
		);

		$results = array();

		do {
			$products_linking = $this->get_products_linking_page( $this->faire_link_products_args );

			$errors = $this->get_products_linking_page_errors( $products_linking );
			if ( $errors ) {
				$results = array_merge( $results, $errors );
				return $results;
			}

			$products_linking_in_page = count( $products_linking );
			// No linking products in the first page, something might be wrong.
			if ( 0 === $products_linking_in_page && 1 === $this->faire_link_products_args['page'] ) {
				$errors = Utils::create_import_error_entry(
					sprintf(
						// translators: %s date of import.
						__( 'Could not find faire products to import. Date: %s', 'faire-for-wordpress' ),
						gmdate( 'Y-m-d\TH:i:s.v\Z' )
					)
				);

				$results[] = $errors;
				return $results;
			}

			// Process the retrieved linking products.
			foreach ( $products_linking as $faire_product ) {
				$result = $this->link_faire_to_wc_product( $faire_product );
				foreach ( $result as $r ) {
					if ( 'success' === $r['status'] ) {
						$total_number_of_linked_products++;
					}
					$results[] = $r;
				}
			}
			$this->faire_link_products_args['page']++;
		} while ( self::PRODUCTS_PER_PAGE === $products_linking_in_page );

		$this->save_products_linking_last_sync_date();

		$results[] = Utils::create_import_result_entry(
			true,
			sprintf(
				// translators: %1$d number of linking products imported.
				__( 'Successfully linked: %1$d. Date %2$s.', 'faire-for-woocommerce' ),
				$total_number_of_linked_products,
				$this->get_products_linking_last_sync_date()
			)
		);

		// Create new products and new variants?
		if ( $this->settings->get_create_new_variations_when_linking() ) {
			$result = $this->create_new_variations();
			foreach ( $result as $r ) {
				$results[] = $r;
			}
		}
		if ( $this->settings->get_create_new_products_when_linking() ) {
			$result = $this->create_new_products();
			foreach ( $result as $r ) {
				$results[] = $r;
			}
		}

		return $results;
	}

	/**
	 * Get faire product id for wc product
	 *
	 * @return string
	 */
	public function get_faire_product_id( $product_id ) {
		return (string) get_post_meta( $product_id, $this->meta_faire_product_id, true );
	}

	/**
	 * Get a single faire variant id for wc product variant by index key or
	 *
	 * @return string|array|null
	 */
	public function get_faire_variant_id( $product_id, $option_hash = '' ) {
		$faire_variant_ids = get_post_meta( $product_id, $this->meta_faire_variant_id, true );
		if ( is_array( $faire_variant_ids ) ) {
			if ( '' !== $option_hash ) {
				return ( isset( $faire_variant_ids[ $option_hash ] ) ) ? $faire_variant_ids[ $option_hash ] : null;
			} else {
				return reset( $faire_variant_ids ); // first item in array.
			}
		}
		return $faire_variant_ids;
	}


	/**
	 * Checks if retrieving a page of linking products failed with errors.
	 *
	 * @param array $products_linking Products retrieved or errors result.
	 *
	 * @return array Resulting errors.
	 */
	private function get_products_linking_page_errors( array $products_linking ): array {
		$errors = array();
		if ( isset( $products_linking['error'] ) ) {
			$errors[] = Utils::create_import_error_entry(
				sprintf(
					// translators: %1d products page, %2$s: date of import.
					__( 'Existing products import failed at page %1$d. Date: %2$s', 'faire-for-wordpress' ),
					$this->faire_link_products_args['page'],
					gmdate( 'Y-m-d\TH:i:s.v\Z' )
				)
			);
			$errors[] = Utils::create_import_error_entry(
				$products_linking['error']['code'] . ': ' . $products_linking['error']['message']
			);
		}

		return $errors;
	}

	/**
	 * Links a WooCommerce product from a given Faire product.
	 *
	 * @param Object $faire_product       A Faire product.
	 *
	 * @return array The results of the attempt to link linking products (can be more than one)
	 */
	private function link_faire_to_wc_product( $faire_product ) {

		$results           = array();
		$linked            = false;
		$parent_product_id = null;

		// Find wc parent.
		$parent_ids_found = $this->get_parent_product_for_faire_product( $faire_product );
		if ( is_array( $parent_ids_found ) && count( $parent_ids_found ) === 1 ) {

			// Get WC product and id.
			$parent_product_id = reset( $parent_ids_found );
			$parent_product    = wc_get_product( $parent_product_id );

			if ( ! $this->check_product_attributes_match_faire_options( $parent_product, $faire_product ) ) {

				// Set error on product linking.
				update_post_meta( $parent_product_id, self::META_FAIRE_PRODUCT_LINKING_ERROR, 'nonmatching_options' );
				update_post_meta( $parent_product_id, self::META_FAIRE_PRODUCT_LINKING_ERROR_FAIRE_ID, $faire_product->id );

				// option sets do not match.
				$results[] = Utils::create_import_error_entry(
					sprintf(
						__( 'Faire product %1$s option sets do not match product id %2$s.', 'faire-for-woocommerce' ),
						$faire_product->id,
						$parent_product_id
					)
				);
				return $results;
			}

			// Reset linking error.
			update_post_meta( $parent_product_id, self::META_FAIRE_PRODUCT_LINKING_ERROR, '' );
			update_post_meta( $parent_product_id, self::META_FAIRE_PRODUCT_LINKING_ERROR_FAIRE_ID, '' );

			// Link based on type.
			if ( 'simple' === $parent_product->get_type() ) {

				if ( ! $this->get_faire_product_id( $parent_product_id ) ) {
					// Link it - Save faire product id.
					update_post_meta( $parent_product_id, $this->meta_faire_product_id, $faire_product->id );

					$faire_variant = $faire_product->variants[0]; // get first variant, should be the only variant.

					// Link it - Save Faux faire variant id to product.
					$faire_variant_ids = array( $faire_variant->id );
					update_post_meta( $parent_product_id, $this->meta_faire_variant_id, $faire_variant_ids );

					$linked = true;

				} else {

					// already linked.
					$results[] = Utils::create_import_success_entry(
						sprintf(
							__( 'Faire product %1$s already linked with product id %2$s.', 'faire-for-woocommerce' ),
							$faire_product->id,
							$parent_product_id
						)
					);
				}
			} elseif ( 'variable' === $parent_product->get_type() ) {

				$missing_wc    = array();
				$missing_faire = array();
				$linked_faire  = array();
				$faire_no_sku  = array();

				// Maybe update parent faire id.
				if ( ! $this->get_faire_product_id( $parent_product_id ) ) {
					// Link it - Save faire product id on parent product.
					update_post_meta( $parent_product_id, $this->meta_faire_product_id, $faire_product->id );
				}

				// Create array map of variations skus to variation id.
				$variations      = $parent_product->get_children();
				$current_wc_skus = array();

				foreach ( $variations as $variation_id ) {
					$variation_product = wc_get_product( $variation_id );
					if ( $variation_product ) {
						$sku = $variation_product->get_sku();
						// Build a mapped array with sku as key to id.
						if ( $sku ) {
							$current_wc_skus[ $sku ] = $variation_id;
						}
						// Set linked with faire (if any).
						$linked_faire[ $variation_id ] = $this->get_faire_variant_id( $variation_id );
					}
				}

				// Loop faire variation skus, find any missing wc variations.
				foreach ( $faire_product->variants as $faire_variant ) {
					// First check if in variant ids.
					if ( in_array( $faire_variant->id, $linked_faire, true ) ) {
						continue;
					}

					// Next check by sku.
					if ( empty( $faire_variant->sku ) ) {
						$faire_no_sku[] = $faire_variant;
						continue;
					}

					$variation_id = isset( $current_wc_skus[ $faire_variant->sku ] ) ? $current_wc_skus[ $faire_variant->sku ] : false;
					if ( ! $variation_id ) {
						$missing_wc[] = $faire_variant;
						continue;
					}
					// Link it - Save faire variant id to product.
					if ( ! $this->get_faire_variant_id( $variation_id ) ) {
						$faire_variant_ids = array( $faire_variant->id );
						update_post_meta( $variation_id, $this->meta_faire_variant_id, $faire_variant_ids );
						$linked = true;
					}
					$linked_faire[ $variation_id ] = $faire_variant->id; // Update linked with faire.
				}

				// Loop wc variations to find variants missing at faire.
				foreach ( $variations as $variation_id ) {
					if ( empty( $linked_faire[ $variation_id ] ) ) {
						$missing_faire[] = $variation_id;
					}
				}

				// Handle scenario: WC has unlinked variations, and Faire variants has unlinked variants.
				if ( ! empty( $faire_no_sku ) || ( ! empty( $missing_faire ) && ! empty( $missing_wc ) ) ) {
					$unmatched = array_merge( $missing_wc, $faire_no_sku );
					// Save unlinked variants to product for manual selection.
					$unmatched_options = array();
					foreach ( $unmatched as $faire_variant ) {
						$unmatched_option_values = array();
						foreach ( $faire_variant->options as $option ) {
							$unmatched_option_values[] = $option->value;
						}
						$unmatched_option_label = $faire_variant->name . ': ' . $faire_variant->id;
						if ( $unmatched_option_values ) {
							$unmatched_option_label .= ' (' . implode( ', ', $unmatched_option_values ) . ')';
						}
						$unmatched_options[ $faire_variant->id ] = $unmatched_option_label;
					}
					update_post_meta( $parent_product_id, self::META_FAIRE_PRODUCT_UNMATCHED_VARIANTS, $unmatched_options );

					// Set error on product linking.
					update_post_meta( $parent_product_id, self::META_FAIRE_PRODUCT_LINKING_ERROR, 'manual_link_variants' );
					update_post_meta( $parent_product_id, self::META_FAIRE_PRODUCT_LINKING_ERROR_FAIRE_ID, $faire_product->id );
					$linked = true;

					$results[] = Utils::create_import_success_entry(
						sprintf(
							__( 'Manual linking required for faire variant %1$s on product id %2$s.', 'faire-for-woocommerce' ),
							$faire_variant->id,
							$parent_product_id
						)
					);

				} else {

					// Handle scenario: WC has unlinked variations, Faire variants all linked.
					if ( ! empty( $missing_faire ) && empty( $missing_wc ) ) {
						// Add product to sync queue.
						$this->scheduler->add_product_faire_pending_sync( $parent_product_id, 'update' );
						$linked = true;

						$results[] = Utils::create_import_success_entry(
							sprintf(
								__( 'Faire product %1$s link queued for next sync with product id %2$s.', 'faire-for-woocommerce' ),
								$faire_product->id,
								$parent_product_id
							)
						);
					}

					// Handle scenario: WC variations are all linked, Faire has unlinked.
					if ( empty( $missing_faire ) && ! empty( $missing_wc ) ) {
						// Create variations in wc.
						foreach ( $missing_wc as $faire_variant ) {
							$parent_col_match = 'id:' . $parent_product_id; // Use WC import csv format for matching to product id.
							$this->add_variant_create_csv( $faire_variant, $faire_product, $parent_col_match, $parent_product );

							$results[] = Utils::create_import_success_entry(
								sprintf(
									__( 'Added missing faire variant %1$s to import CSV for product id %2$s.', 'faire-for-woocommerce' ),
									$faire_variant->id,
									$parent_product_id
								)
							);

							$linked = true;
						}
					}
				}

				// If nothing to do.
				if ( ! $linked && ! empty( $linked_faire ) ) {

					$results[] = Utils::create_import_success_entry(
						sprintf(
							__( 'Faire product %1$s already linked with product id %2$s.', 'faire-for-woocommerce' ),
							$faire_product->id,
							$parent_product_id
						)
					);

				}
			}
		} elseif ( is_array( $parent_ids_found ) && count( $parent_ids_found ) > 1 ) {

			// Set error on product linking.
			foreach ( $parent_ids_found as $id ) {
				update_post_meta( $id, self::META_FAIRE_PRODUCT_LINKING_ERROR, 'multiple_matches' );
				update_post_meta( $parent_product_id, self::META_FAIRE_PRODUCT_LINKING_ERROR_FAIRE_ID, $faire_product->id );
			}

			// Multiple matches.
			$results[] = Utils::create_import_error_entry(
				sprintf(
					__( 'Multiple product matches. Faire product %1$s had variants skus on multiple different products: %2$s.', 'faire-for-woocommerce' ),
					$faire_product->id,
					implode( ', ', $parent_ids_found )
				)
			);

		} elseif ( is_array( $parent_ids_found ) && empty( $parent_ids_found ) ) {

			// Product not found for skus, so create?
			$this->add_product_create_csv( $faire_product ); // Add to CSV.

			$linked = true;

			$results[] = Utils::create_import_success_entry(
				sprintf(
					__( 'Added missing faire product %s to import CSV.', 'faire-for-woocommerce' ),
					$faire_product->id
				)
			);

			$parent_product_id = '[New Product]';

		} elseif ( $parent_ids_found === $this::PRODUCTS_LINKING_PRODUCT_MATCH_INVALID ) {

			// Invalid.
			$results[] = Utils::create_import_error_entry(
				sprintf(
					__( 'Match not found for Faire product %s.', 'faire-for-woocommerce' ),
					$faire_product->id,
				)
			);

		} elseif ( $parent_ids_found === $this::PRODUCTS_LINKING_PRODUCT_EMPTY_SKU ) {

			// Invalid.
			$results[] = Utils::create_import_error_entry(
				sprintf(
					__( 'Faire product %s is missing a sku.', 'faire-for-woocommerce' ),
					$faire_product->id,
				)
			);

		}

		// If successfully linked.
		if ( $linked ) {
			$results[] = Utils::create_import_success_entry(
				sprintf(
					__( 'Faire product %1$s successfully linked with product id %2$s.', 'faire-for-woocommerce' ),
					$faire_product->id,
					$parent_product_id
				)
			);
		}

		return $results;
	}

	/**
	 * Gets a product linking error if any exists on a product
	 *
	 * @param int $product_id            A WooCommerce product id
	 *
	 * @return string                   An error code string or empty string
	 */
	public function get_product_linking_error( $product_id ) {
		return get_post_meta( $product_id, self::META_FAIRE_PRODUCT_LINKING_ERROR, true );
	}

	/**
	 * Validates if WooCommerce product attributes match a Faire products option sets
	 *
	 * @param Object $product            A WooCommerce product.
	 * @param Object $faire_product      A Faire product.
	 *
	 * @return boolean                  If its a perfect match or not
	 */
	private function check_product_attributes_match_faire_options( $product, $faire_product ) {

		$bln_wc_missing    = false;
		$bln_faire_missing = false;

		// Product attributes - format like faire option sets, build with all attributes.
		$product_attributes  = $this->get_all_wc_product_variation_attributes( $product );
		$product_option_sets = array();
		foreach ( $product_attributes as $attribute_key => $attribute_data ) {
			$option_name = $attribute_data['name'];
			// Build array of WC options.
			$product_option_sets[ $option_name ] = array(
				'name'   => $option_name,
				'values' => $attribute_data['values'], // empty = "all"
			);
		}
		// Build all Faire options.
		$faire_option_sets = array();
		foreach ( $faire_product->variant_option_sets as $option ) {
			$faire_option_sets[ $option->name ] = array(
				'name'   => $option->name,
				'values' => $option->values,
			);
		}

		// Check if WC has all faire options.
		foreach ( $product_option_sets as $option_key => $option ) {
			if ( isset( $faire_option_sets[ $option_key ] ) ) {
				unset( $faire_option_sets[ $option_key ] );
			} else {
				$bln_faire_missing = true;
			}
		}

		// Check if faire has options in.
		foreach ( $faire_product->variant_option_sets as $option ) {
			if ( isset( $product_option_sets[ $option->name ] ) ) {
				unset( $product_option_sets[ $option->name ] );
			} else {
				$bln_wc_missing = true;
			}
		}

		if ( $bln_faire_missing || $bln_wc_missing ) {
			return false;
		}
		return true;
	}

	/**
	 * Finds a WooCommerce product that matches a Faire product by its variant skus.
	 *
	 * @param Object $faire_product A Faire product.
	 *
	 * @return array|int     Array with product ids or flag.
	 */
	private function get_parent_product_for_faire_product( $faire_product ) {

		$found_products = array();
		$empty_sku      = false;
		foreach ( $faire_product->variants as $faire_variant ) {
			$sku = isset( $faire_variant->sku ) ? trim( $faire_variant->sku ) : '';
			if ( '' === $sku ) {
				$empty_sku = true;
				continue;
			}

			$product = $this->get_product_by_sku( $sku );

			if ( ! $product ) {
				continue;
			}

			if ( 'variable' === $product->get_type() ) {
				return $this::PRODUCTS_LINKING_PRODUCT_MATCH_INVALID;
			}

			if ( 'variation' === $product->get_type() && $product->get_parent_id() ) {
				$found_products[] = $product->get_parent_id();
			}

			if (
				'simple' === $product->get_type()
				// If simple product found, match it if we only have one variant, otherwise we cannot match.
				&& count( $faire_product->variants ) === 1
				&& empty( $faire_product->variant_option_sets )
			) {
				$found_products[] = $product->get_id();
			}
		}

		if ( empty( $found_products ) && $empty_sku ) {
			return $this::PRODUCTS_LINKING_PRODUCT_EMPTY_SKU;
		}

		return array_unique( $found_products );
	}

	// TODO: use product sync class method.
	// Return a products variation attributes including all values.
	public function get_all_wc_product_variation_attributes( $product ) {
		global $wpdb;

		$variation_attributes = array();
		$attributes           = $product->get_attributes();

		if ( ! empty( $attributes ) ) {
			foreach ( $attributes as $attribute_key => $attribute ) {
				if ( empty( $attribute['is_variation'] ) ) {
					continue;
				}

				// Set values.
				$values = $attribute['is_taxonomy'] ? wc_get_object_terms( $product->get_id(), $attribute['name'], 'name' ) : wc_get_text_attributes( $attribute['value'] );

				// Empty value indicates that all options for given attribute are available.
				if ( in_array( null, $values, true ) || in_array( '', $values, true ) || empty( $values ) ) {
					$values = $attribute['is_taxonomy'] ? wc_get_object_terms( $product->get_id(), $attribute['name'], 'name' ) : wc_get_text_attributes( $attribute['value'] );
					// Get custom attributes (non taxonomy) as defined.
				} elseif ( ! $attribute['is_taxonomy'] ) {
					$text_attributes          = wc_get_text_attributes( $attribute['value'] );
					$assigned_text_attributes = $values;
					$values                   = array();

					// Pre 2.4 handling where 'slugs' were saved instead of the full text attribute.
					if ( version_compare( get_post_meta( $product->get_id(), '_product_version', true ), '2.4.0', '<' ) ) {
						$assigned_text_attributes = array_map( 'sanitize_title', $assigned_text_attributes );
						foreach ( $text_attributes as $text_attribute ) {
							if ( in_array( sanitize_title( $text_attribute ), $assigned_text_attributes, true ) ) {
								$values[] = $text_attribute;
							}
						}
					} else {
						foreach ( $text_attributes as $text_attribute ) {
							if ( in_array( $text_attribute, $assigned_text_attributes, true ) ) {
								$values[] = $text_attribute;
							}
						}
					}
				}

				// Return attributes with key for accessing.
				$attribute_name_cleaned                 = wc_attribute_label( $attribute['name'] );
				$variation_attributes[ $attribute_key ] = array(
					'attribute_object' => $attribute,
					'attribute_key'    => $attribute_key,
					'name'             => $attribute_name_cleaned,
					'values'           => array_unique( $values ),
				);

			}
		}

		return $variation_attributes;
	}

	/**
	 * Return array with product wholesale and retail price.
	 *
	 * @param  object $faire_product Faire product object.
	 *
	 * @return array
	 */
	private function get_price( $faire_product ) {
		$price = array(
			'wholesale' => '',
			'retail'    => '',
		);

		if ( ! isset( $faire_product->variants[0]->prices ) && isset( $faire_product->variants[0]->wholesale_price_cents ) ) {
			$price['wholesale'] = $this->convert_cents_to_price( $faire_product->variants[0]->wholesale_price_cents );
			if ( isset( $faire_product->variants[0]->retail_price_cents ) ) {
				$price['retail'] = $this->convert_cents_to_price( $faire_product->variants[0]->retail_price_cents );
			}
		} elseif ( isset( $faire_product->variants[0]->prices ) && $faire_product->variants[0]->prices ) {
			$geo_constraint = $this->settings->get_faire_geo_constraint();
			foreach ( $faire_product->variants[0]->prices as $faire_price ) {
				if ( get_woocommerce_currency() !== $faire_price->wholesale_price->currency ) {
					continue;
				}

				if ( isset( $faire_price->geo_constraint->country ) && $faire_price->geo_constraint->country && isset( $geo_constraint['country'] ) && $geo_constraint['country'] === $faire_price->geo_constraint->country ) {
					$price['wholesale'] = $this->convert_cents_to_price( $faire_price->wholesale_price->amount_minor );
					$price['retail']    = $this->convert_cents_to_price( $faire_price->retail_price->amount_minor );
				} elseif ( isset( $faire_price->geo_constraint->country_group ) && $faire_price->geo_constraint->country_group && isset( $geo_constraint['country_group'] ) && $geo_constraint['country_group'] === $faire_price->geo_constraint->country_group ) {
					$price['wholesale'] = $this->convert_cents_to_price( $faire_price->wholesale_price->amount_minor );
					$price['retail']    = $this->convert_cents_to_price( $faire_price->retail_price->amount_minor );
				}
			}
		}

		return $price;
	}

	/**
	 * Build product import csv row for use with WC Import Products
	 * Defined: https://github.com/woocommerce/woocommerce/wiki/Product-CSV-Import-Schema#csv-columns-and-formatting
	 *
	 * @param Object $faire_product Faire Product Object.
	 *
	 * @return array
	 */
	public function create_product_csv_rows( $faire_product ) {

		// Default CSV row.
		$_default_row = $this->get_wc_product_import_default_cols();
		$row          = $_default_row;

		// Fill CSV row.
		$type = ( 1 === count( $faire_product->variants ) && empty( $faire_product->variant_option_sets ) ) ? 'simple' : 'variable';

		// Product parent row.
		$row['Type'] = $type;
		if ( $type === 'simple' ) {
			$row['SKU'] = ( isset( $faire_product->variants[0]->sku ) && $faire_product->variants[0]->sku ) ? $faire_product->variants[0]->sku : $faire_product->id;
		} else {
			$row['SKU'] = $faire_product->id;
		}

		$row['Name']                  = $faire_product->name;
		$row['Published']             = ( $faire_product->lifecycle_state === 'PUBLISHED' ) ? 1 : -1; // 1 for published, 0 for private, -1 for draft.
		$row['Is featured?']          = 0;
		$row['Visibility in catalog'] = 'visible';
		$row['Description']           = $faire_product->description;
		$row['Parent']                = '';
		if ( isset( $faire_product->sale_state ) ) {
			$row['In stock?'] = ( $faire_product->sale_state === 'FOR_SALE' ) ? 1 : 0;
		} else {
			$row['In stock?'] = 1;
		}

		// $row['Backorders allowed?'] = $faire_product->name;
		// $row['Sale price'] = $faire_product->name;
		$row['Regular price'] = '';

		if ( $type === 'variable' ) {

			$image_array = array();
			foreach ( $faire_product->images as $image ) {
				$image_array[] = $image->url;
			}
			$image_array   = array_unique( $image_array );
			$row['Images'] = ( $image_array ) ? implode( ', ', $image_array ) : '';

			// Define attribute option columns index ordering
			// .. get attributes from option sets
			// .. and add parent product attribute columns.
			$attribute_option_index = array(); // Map from option name to attribute index.
			$attr_i                 = 1;
			foreach ( $faire_product->variant_option_sets as $option ) {
				$attribute_option_index[ $option->name ]     = $attr_i;
				$row[ 'Attribute ' . $attr_i . ' name' ]     = $option->name;
				$row[ 'Attribute ' . $attr_i . ' value(s)' ] = implode( ', ', $option->values );
				$row[ 'Attribute ' . $attr_i . ' visible' ]  = '';
				$row[ 'Attribute ' . $attr_i . ' global' ]   = '';
				$attr_i++;
			}
		} else {
			$row['In stock?'] = isset( $faire_product->sale_state ) ? ( 'FOR_SALE' === $faire_product->sale_state ? 1 : 0 ) : 1;
			$row['Stock']     = isset( $faire_product->variants[0]->available_quantity ) ? $faire_product->variants[0]->available_quantity : '';

			$image_array = array();
			foreach ( $faire_product->images as $image ) {
				$image_array[] = $image->url;
			}
			foreach ( $faire_product->variants[0]->images as $image ) {
				$image_array[] = $image->url;
			}
			$image_array   = array_unique( $image_array );
			$row['Images'] = ( $image_array ) ? implode( ', ', $image_array ) : '';

			// Get price from single variant for simple product.
			$price = $this->get_price( $faire_product );
			if ( 'wholesale_multiplier' === $this->settings->get_product_pricing_policy() ) {
				$row['Regular price']                                = $price['wholesale'];
				$row['Meta: woocommerce_faire_product_retail_price'] = $price['retail'];
			} else {
				$row['Regular price']                                   = $price['retail'];
				$row['Meta: woocommerce_faire_product_wholesale_price'] = $price['wholesale'];
			}

			if ( '' === $price['wholesale'] && '' === $price['retail'] ) {
				error_log( 'product linking ' . $faire_product->id . ' failed to find any prices for geo constraint' );
			}

			$row['Meta: woocommerce_faire_product_tariff_code'] = isset( $faire_product->variants[0]->tariff_code ) ? $faire_product->variants[0]->tariff_code : '';
		}

		if ( 'simple' === $type ) {
			$row[ 'Meta: ' . $this->meta_faire_variant_id ] = $faire_product->variants[0]->id; // faire variant id.
		}

		// Measurements.
		$row = $this->add_measurements( $row, $faire_product );

		// Meta.
		$row[ 'Meta: ' . $this->meta_faire_product_id ]                          = $faire_product->id; // faire product id.
		$row['Meta: woocommerce_faire_product_lifecycle_state']                  = ( $faire_product->lifecycle_state ) ? $faire_product->lifecycle_state : 'UNPUBLISHED';
		$row['Meta: woocommerce_faire_product_unit_multiplier']                  = $faire_product->unit_multiplier;
		$row['Meta: woocommerce_faire_product_minimum_order_quantity']           = $faire_product->minimum_order_quantity;
		$row['Meta: woocommerce_faire_product_per_style_minimum_order_quantity'] = $faire_product->per_style_minimum_order_quantity;
		$row['Meta: woocommerce_faire_product_allow_preorder']                   = ( $faire_product->preorderable ) ? 'allow_preorder' : 'do_not_allow';
		if ( isset( $faire_product->preorder_details ) && $faire_product->preorder_details ) {
			$row['Meta: woocommerce_faire_product_order_by_date']                  = isset( $faire_product->preorder_details->order_by_date ) ? $faire_product->preorder_details->order_by_date : '';
			$row['Meta: woocommerce_faire_product_keep_active_past_order_by_date'] = isset( $faire_product->preorder_details->keep_active_past_order_by_date ) ? (int) $faire_product->preorder_details->keep_active_past_order_by_date : 0;
			$row['Meta: woocommerce_faire_product_expected_ship_date']             = isset( $faire_product->preorder_details->expected_ship_date ) ? $faire_product->preorder_details->expected_ship_date : '';
			$row['Meta: woocommerce_faire_product_expected_ship_window_date']      = isset( $faire_product->preorder_details->expected_ship_window_end_date ) ? $faire_product->preorder_details->expected_ship_window_end_date : '';
		}

		if ( 'variable' === $type ) {

			// Finished parent row.
			$rows[] = $row;

			// Add variations.
			$parent_col_match = $row['SKU'];
			foreach ( $faire_product->variants as $faire_variant ) {
				$rows[] = $this->create_variant_csv_rows( $faire_variant, $faire_product, $parent_col_match );
			}
		} else {

			// Add row.
			$rows[] = $row;
		}

		return apply_filters( 'faire_wc_linking_create_product_csv_rows', $rows, $faire_product );
	}

	/**
	 * Add measurements data to $row.
	 *
	 * @param  array  $row
	 * @param  object $faire_product
	 *
	 * @return array $row
	 */
	private function add_measurements( $row, $faire_product ) {

		$measurements = isset( $faire_product->variants ) && isset( $faire_product->variants[0]->measurements ) ? $faire_product->variants[0]->measurements : ( isset( $faire_product->measurements ) ? $faire_product->measurements : '' );

		if ( ! $measurements ) {
			return $row;
		}

		$wc_mass_unit     = get_option( 'woocommerce_weight_unit', 'kg' );
		$wc_distance_unit = get_option( 'woocommerce_dimension_unit', 'cm' );

		$mass_unit_map_faire_to_woocommerce     = array(
			'GRAMS'     => 'g',
			'KILOGRAMS' => 'kg',
			'OUNCES'    => 'oz',
			'POUNDS'    => 'lbs',
		);
		$distance_unit_map_faire_to_woocommerce = array(
			'CENTIMETERS' => 'cm',
			'INCHES'      => 'in',
			'FEET'        => 'ft',
			'MILLIMETERS' => 'mm',
			'METERS'      => 'm',
			'YARDS'       => 'yd',
		);

		if (
			isset( $measurements->mass_unit ) &&
			isset( $mass_unit_map_faire_to_woocommerce[ $measurements->mass_unit ] ) &&
			$wc_mass_unit === $mass_unit_map_faire_to_woocommerce[ $measurements->mass_unit ]
		) {
			if ( isset( $measurements->weight ) ) {
				$row[ "Weight ({$wc_mass_unit})" ] = $measurements->weight;
			}
		}

		if (
			isset( $measurements->distance_unit ) &&
			isset( $distance_unit_map_faire_to_woocommerce[ $measurements->distance_unit ] ) &&
			$wc_distance_unit === $distance_unit_map_faire_to_woocommerce[ $measurements->distance_unit ]
		) {
			if ( isset( $measurements->length ) ) {
				$row[ "Length ({$wc_distance_unit})" ] = $measurements->length;
			}
			if ( isset( $measurements->width ) ) {
				$row[ "Width ({$wc_distance_unit})" ] = $measurements->width;
			}
			if ( isset( $measurements->height ) ) {
				$row[ "Height ({$wc_distance_unit})" ] = $measurements->height;
			}
		}

		return $row;
	}


	public function create_variant_csv_rows( $faire_variant, $faire_product, $parent_col_match = '', $parent_product = null ) {

		// If parent product is given, define faire option set to attribute map.
		$product_option_sets = array();
		if ( $parent_product ) {
			// Define from parent product attributes.
			$product_attributes = $this->get_all_wc_product_variation_attributes( $parent_product );
			foreach ( $product_attributes as $attribute_key => $attribute_data ) {
				$option_name = $attribute_data['name'];
				// Build array of WC options.
				$product_option_sets[ $option_name ] = array(
					'attribute_key' => $attribute_key,
					'name'          => $option_name,
					'values'        => $attribute_data['values'], // empty = "all".
				);
			}
		}

		// Define attribute option columns index ordering
		// .. use faire option sets for order.
		$attribute_option_index = array(); // Map from option name to attribute index.
		$attr_i                 = 1;
		foreach ( $faire_product->variant_option_sets as $option ) {
			$attribute_option_index[ $option->name ] = $attr_i;
			$attr_i++;
		}

		// Default CSV row.
		$_default_row = $this->get_wc_product_import_default_cols();
		$row          = $_default_row;

		$row['Type']                  = 'variation';
		$row['SKU']                   = isset( $faire_variant->sku ) ? $faire_variant->sku : '';
		$row['Name']                  = $faire_variant->name;
		$row['Published']             = 1; // 1 published, variations are always published.
		$row['Is featured?']          = 0;
		$row['Visibility in catalog'] = 'visible';
		$row['Description']           = '';

		$row['In stock?'] = isset( $faire_product->sale_state ) ? ( 'FOR_SALE' === $faire_product->sale_state ? 1 : 0 ) : 1;

		$row['Stock']  = ( isset( $faire_variant->available_quantity ) && '' !== $faire_variant->available_quantity ) ? $faire_variant->available_quantity : ''; // Leave empty string for no stock management.
		$row['Parent'] = $parent_col_match;

		$image_array = array();
		foreach ( $faire_variant->images as $image ) {
			$image_array[] = $image->url;
		}
		$image_array   = array_unique( $image_array );
		$row['Images'] = ( $image_array ) ? implode( ', ', $image_array ) : '';

		$variant_wholesale_price = '';
		if ( ! isset( $faire_variant->prices ) && isset( $faire_variant->wholesale_price_cents ) ) {
			$variant_wholesale_price = $this->convert_cents_to_price( $faire_variant->wholesale_price_cents );
			if ( isset( $faire_variant->retail_price_cents ) ) {
				$row['Regular price'] = $this->convert_cents_to_price( $faire_variant->retail_price_cents );
			}
		} elseif ( isset( $faire_variant->prices ) && $faire_variant->prices ) {
			$geo_constraint = $this->settings->get_faire_geo_constraint();
			foreach ( $faire_variant->prices as $price ) {

				if ( get_woocommerce_currency() !== $price->wholesale_price->currency ) {
					continue;
				}

				if (
					(
						! empty( $price->geo_constraint->country ) &&
						! empty( $geo_constraint['country'] ) &&
						$geo_constraint['country'] === $price->geo_constraint->country
					) ||
					(
						! empty( $price->geo_constraint->country_group ) &&
						! empty( $geo_constraint['country_group'] ) &&
						$geo_constraint['country_group'] === $price->geo_constraint->country_group
					)
				) {
					if ( 'wholesale_multiplier' === $this->settings->get_product_pricing_policy() ) {
						$row['Regular price']                                          = $this->convert_cents_to_price( $price->wholesale_price->amount_minor );
						$row['Meta: woocommerce_faire_product_variation_retail_price'] = $this->convert_cents_to_price( $price->retail_price->amount_minor );
					} else {
						$row['Regular price']                                             = $this->convert_cents_to_price( $price->retail_price->amount_minor );
						$row['Meta: woocommerce_faire_product_variation_wholesale_price'] = $this->convert_cents_to_price( $price->wholesale_price->amount_minor );
					}
					$variant_wholesale_price = $this->convert_cents_to_price( $price->wholesale_price->amount_minor );
					$row['Regular price']    = $this->convert_cents_to_price( $price->retail_price->amount_minor );
				}
			}
		}

		if ( '' === $variant_wholesale_price && '' === $row['Regular price'] ) {
			error_log( 'product linking ' . $faire_variant->id . ' failed to find any prices for geo constraint' );
		}

		// Map option value to an attribute index number.
		$variant_attribute_option = array();
		foreach ( $faire_variant->options as $variant_option ) {
			$option_attr_i                              = $attribute_option_index[ $variant_option->name ];
			$option_attr_key                            = isset( $product_option_sets[ $variant_option->name ] ) ? $product_option_sets[ $variant_option->name ]['attribute_key'] : $variant_option->name;
			$variant_attribute_option[ $option_attr_i ] = array(
				'index' => $option_attr_i,
				'name'  => $option_attr_key,
				'value' => $variant_option->value,
			);
		}

		// For each option set option, add an attribute column.
		$attr_i = 1;
		foreach ( $faire_product->variant_option_sets as $option ) {
			// Get option attribute index number.
			$option_attr = isset( $variant_attribute_option[ $attr_i ] ) ? $variant_attribute_option[ $attr_i ] : array();
			if ( $option_attr ) {
				$row[ 'Attribute ' . $attr_i . ' name' ]     = $option_attr['name'];
				$row[ 'Attribute ' . $attr_i . ' value(s)' ] = $option_attr['value'];
				$row[ 'Attribute ' . $attr_i . ' visible' ]  = '';
				$row[ 'Attribute ' . $attr_i . ' global' ]   = '';
			} else {
				$row[ 'Attribute ' . $attr_i . ' name' ]     = '';
				$row[ 'Attribute ' . $attr_i . ' value(s)' ] = '';
				$row[ 'Attribute ' . $attr_i . ' visible' ]  = '';
				$row[ 'Attribute ' . $attr_i . ' global' ]   = '';
			}
			$attr_i++;
		}

		$row = $this->add_measurements( $row, $faire_variant );

		$row[ 'Meta: ' . $this->meta_faire_variant_id ]                   = $faire_variant->id; // faire variant id.
		$row['Meta: woocommerce_faire_product_variation_tariff_code']     = isset( $faire_variant->tariff_code ) ? $faire_variant->tariff_code : '';
		$row['Meta: woocommerce_faire_product_variation_lifecycle_state'] = ( $faire_variant->lifecycle_state ) ? $faire_variant->lifecycle_state : 'UNPUBLISHED';

		return apply_filters( 'faire_wc_linking_create_variant_csv_rows', $row, $faire_variant, $faire_product, $parent_col_match );
	}

	public function get_wc_product_import_default_cols() {
		// Default CSV row
		$_default_row = array(
			'ID'                    => '',
			'Type'                  => '',
			'SKU'                   => '',
			'Name'                  => '',
			'Published'             => '',
			'Is featured?'          => '',
			'Visibility in catalog' => '',
			'Short description'     => '',
			'Description'           => '',
			// 'Date sale price starts' => '', 'Date sale price ends' => '', 'Tax status' => '', 'Tax class' => '',
			'In stock?'             => '',
			'Stock'                 => '',
			// 'Low stock amount' => '', 'Backorders allowed?' => '', 'Sold individually?' => '',
			// 'Weight (kg)' => '', 'Length (cm)' => '', 'Width (cm)' => '', 'Height (cm)' => '',
			// 'Allow customer reviews?' => '', 'Purchase note' => '',
			'Sale price'            => '',
			'Regular price'         => '',
			// 'Categories' => '', 'Tags' => '', 'Shipping class' => '',
			'Images'                => '',
			// 'Download limit' => '', 'Download expiry days' => '',
			'Parent'                => '',
			// 'Grouped products' => '',
			// 'Upsells' => '', 'Cross-sells' => '', 'External URL' => '', 'Button text' => '', 'Position' => '',
			// 'Attribute 1 name' => '', 'Attribute 1 value(s)' => '', 'Attribute 1 visible' => '', 'Attribute 1 global' => '',
			// 'Attribute 2 name' => '', 'Attribute 2 value(s)' => '', 'Attribute 2 visible' => '', 'Attribute 2 global' => '',
			// 'Download 1 ID' => '', 'Download 1 name' => '', 'Download 1 URL' => '', 'Download 2 ID' => '', 'Download 2 name' => '', 'Download 2 URL'
		);
		return $_default_row;
	}


	/**
	 * Convert a cents to price
	 *
	 * @return float
	 */
	public function convert_cents_to_price( $price_cents ) {
		if ( $price_cents > 0 ) {
			$price = number_format( ( $price_cents / 100 ), 2, '.', '' );
		} else {
			$price = 0;
		}
		return $price;
	}

	/**
	 * Generate and download CSV from pending create
	 *
	 * @return void
	 */
	public function download_faire_create_csv( $download = 'products' ) {

		if ( 'variations' === $download ) {
			$list     = $this->get_variants_create_csv();
			$filename = 'wc-faire-variations.csv';
		} elseif ( 'products' === $download ) {
			$list     = $this->get_products_create_csv();
			$filename = 'wc-faire-products.csv';
		} else {
			wp_die( __( 'Invalid download attempt', 'faire-for-woocommerce' ) );
		}

		$csv_row_template = array();
		$csv_row_header   = array();
		$csv_rows         = array();
		// Define a template of rows
		foreach ( $list as $faire_id => $rows ) {
			foreach ( $rows as $row ) {
				$csv_row_template = array_merge( $csv_row_template, $row );
			}
		}
		foreach ( $csv_row_template as $k => $v ) {
			$csv_row_template[ $k ] = ''; // Set template values to empty string
		}
		// Build rows with template
		foreach ( $list as $faire_id => $rows ) {
			foreach ( $rows as $row ) {
				// Add rows
				$csv_rows[] = array_merge( $csv_row_template, $row );
			}
		}

		$csv_header = array_keys( $csv_row_template );

		// Headers
		if ( function_exists( 'gc_enable' ) ) {
			gc_enable(); // phpcs:ignore PHPCompatibility.FunctionUse.NewFunctions.gc_enableFound
		}
		if ( function_exists( 'apache_setenv' ) ) {
			@apache_setenv( 'no-gzip', 1 ); // @codingStandardsIgnoreLine
		}
		@ini_set( 'zlib.output_compression', 'Off' ); // @codingStandardsIgnoreLine
		@ini_set( 'output_buffering', 'Off' ); // @codingStandardsIgnoreLine
		@ini_set( 'output_handler', '' ); // @codingStandardsIgnoreLine
		ignore_user_abort( true );
		wc_set_time_limit( 0 );
		wc_nocache_headers();
		header( 'Content-Type: text/csv; charset=utf-8' );
		header( 'Content-Disposition: attachment; filename=' . $filename );
		header( 'Pragma: no-cache' );
		header( 'Expires: 0' );

		// Echo content
		$output = fopen( 'php://output', 'w' );
		fputs( $output, chr( 239 ) . chr( 187 ) . chr( 191 ) ); // Write utf-8 bom
		fputcsv( $output, $csv_header, ',', '"', "\0" ); // @codingStandardsIgnoreLine
		foreach ( $csv_rows as $row ) {
			fputcsv( $output, $row, ',', '"', "\0" ); // @codingStandardsIgnoreLine
		}
		fclose( $output );
		die();
	}

	/**
	 * Create new products from faire import pending csv
	 *
	 * @return array of results
	 */
	public function create_new_products() {
		$results = array();

		$product_rows = $this->get_products_create_csv();

		if ( empty( $product_rows ) ) {
			return array();
		}

		foreach ( $product_rows as $rows ) {
			$parent_product = null; // Reset for every row group.
			foreach ( $rows as $row ) {
				if ( 'simple' === $row['Type'] || 'variable' === $row['Type'] ) {
					$product       = null;
					$error_message = '';
					try {
							$product = $this->create_product( $row );
					} catch ( \WC_Data_Exception $e ) {
						error_log( 'create_product WC_Data_Exception: ' . $e->getMessage() );
						$error_message = $e->getMessage();
					}
					if ( ! $product ) {
						$results[] = Utils::create_import_error_entry(
							sprintf(
								__( 'Failed to create faire product %1$s.%2$s', 'faire-for-woocommerce' ),
								$row['Meta: _faire__product_id'] . ' [sku: ' . $row['SKU'] . '] "' . $row['Name'] . '" as ' . $row['Type'] . ' product',
								$error_message ? ' [' . $error_message . ']' : ''
							)
						);
						continue;
					}

					$parent_product = $product; // Set parent id for use on variations (if nay).
					$results[]      = Utils::create_import_success_entry(
						sprintf(
							__( 'Created faire product %1$s as %2$s product id %3$s.', 'faire-for-woocommerce' ),
							$row['Meta: _faire__product_id'] . ' [sku: ' . $row['SKU'] . '] "' . $row['Name'] . '"',
							$row['Type'],
							$product->get_id()
						)
					);
				} elseif ( 'variation' === $row['Type'] ) {

					// If we have a parent from a previous parent row.
					if ( $parent_product ) {
						$row['Parent'] = 'id:' . $parent_product->get_id();
					}

					$variation     = null;
					$error_message = false;
					try {
						$variation = $this->create_variation( $row, $parent_product );
					} catch ( \WC_Data_Exception $e ) {
						error_log( 'create_variation WC_Data_Exception: ' . $e->getMessage() );
						$error_message = $e->getMessage();
					}
					if ( ! $variation ) {
						$results[] = Utils::create_import_error_entry(
							sprintf(
								__( 'Failed to create faire variant %1$s on parent product %2$s.%3$s', 'faire-for-woocommerce' ),
								$row['Meta: _faire__variant_id'] . ' [sku: ' . $row['SKU'] . '] "' . $row['Name'] . '"',
								$row['Parent'],
								$error_message ? ' [' . $error_message . ']' : ''
							)
						);
						continue;
					}

					$results[] = Utils::create_import_success_entry(
						sprintf(
							__( 'Created faire variant %1$s as %2$s product id %3$s.', 'faire-for-woocommerce' ),
							$row['Meta: _faire__variant_id'] . ' [sku: ' . $row['SKU'] . '] "' . $row['Name'] . '"',
							$row['Type'],
							$variation->get_id()
						)
					);
				}
			}
		}

		return $results;
	}

	/**
	 * Create new products from faire import pending sv
	 *
	 * @return array of results
	 */
	public function create_new_variations() {
		$results      = array();
		$product_rows = $this->get_variants_create_csv();
		if ( $product_rows ) {
			foreach ( $product_rows as $rows ) {
				foreach ( $rows as $row ) {
					$variation = null;
					try {
						$variation = $this->create_variation( $row );
					} catch ( \WC_Data_Exception $e ) {
						error_log( 'create_variation WC_Data_Exception: ' . $e->getMessage() );
					}

					if ( $variation ) {
						$results[] = Utils::create_import_success_entry(
							sprintf(
								__( 'Created faire variant %1$s on product id %2$s.', 'faire-for-woocommerce' ),
								$row['Meta: _faire__variant_id'] . ' [sku: ' . $row['SKU'] . '] "' . $row['Name'] . '"',
								$variation->get_id()
							)
						);
					} else {
						$results[] = Utils::create_import_error_entry(
							sprintf(
								__( 'Failed to create faire variant %1$s on product %2$s.', 'faire-for-woocommerce' ),
								$row['Meta: _faire__variant_id'] . ' [sku: ' . $row['SKU'] . '] "' . $row['Name'] . '"',
								$row['Parent']
							)
						);
					}
				}
			}
		}
		return $results;
	}

	/**
	 * Create product from import csv row
	 *
	 * @return \WC_Product|false
	 */
	public function create_product( $row ) {

		global $faire_wc_prevent_dups_product_updated_id;

		if ( 'simple' === $row['Type'] ) {
			$product = new \WC_Product_Simple();
		} elseif ( 'variable' === $row['Type'] ) {
			$product = new \WC_Product_Variable();
		} else {
			return false;
		}

		$product->set_name( $row['Name'] );

		if ( 1 === $row['Published'] ) {
			$product->set_status( 'publish' );
		} elseif ( 0 === $row['Published'] ) {
			$product->set_status( 'private' );
		} elseif ( -1 === $row['Published'] ) {
			$product->set_status( 'draft' );
		}

		if ( $row['SKU'] !== '' ) {
			$product->set_sku( $row['SKU'] );
		}
		$product->set_description( ( $row['Description'] ) ? $row['Description'] : ' ' );
		// $product->set_short_description( $row['Description'] );
		// $product->set_slug( 'medium-size-wizard-hat-in-new-york' );

		if ( '' !== $row['Regular price'] ) {
			$product->set_regular_price( $row['Regular price'] ); // in current shop currency.
		}

		if ( $row['Images'] ) {
			$image_array                = explode( ', ', $row['Images'] );
			$product_gallery_image_list = array();
			foreach ( $image_array as $image_url ) {
				try {
					$image_id = $this->get_attachment_id_from_url( $image_url, $product->get_id() );
					if ( ! $image_id ) {
						continue;
					}

					$product_gallery_image_list[] = $image_id;
				} catch ( Exception $e ) {
					error_log( 'create_product product->set_image_id Exception: ' . $e->getMessage() );
				}
			}
			// If we have images, set first image as product main image and remove it from the list.
			if ( ! empty( $product_gallery_image_list ) ) {
				$product->set_image_id( $product_gallery_image_list[0] );
				unset( $product_gallery_image_list[0] );
			}
			// If we have remaining images on the list add them to product gallery.
			if ( ! empty( $product_gallery_image_list ) ) {
				$product->set_gallery_image_ids( $product_gallery_image_list );
			}
		}

		// Stock management.
		if ( 1 === $row['In stock?'] ) {
			$product->set_stock_status( 'instock' ); // 'instock', 'outofstock' or 'onbackorder'
		} else {
			$product->set_stock_status( 'outofstock' );
		}

		if ( '' !== $row['Stock'] ) {
			$product->set_manage_stock( true );
			$product->set_stock_quantity( $row['Stock'] );
		}

		// $product->set_backorders( 'no' ); // 'yes', 'no' or 'notify'
		// $product->set_sold_individually( true );
		// $product->set_category_ids( array( 1 ) );
		// you can also use $product->set_tag_ids() for tags, brands etc

		// Add measurements.
		$wc_mass_unit     = get_option( 'woocommerce_weight_unit', 'kg' );
		$wc_distance_unit = get_option( 'woocommerce_dimension_unit', 'cm' );

		if ( ! empty( $row[ "Weight ($wc_mass_unit)" ] ) ) {
			$product->set_weight( $row[ "Weight ($wc_mass_unit)" ] );
		}

		if ( ! empty( $row[ "Length ($wc_distance_unit)" ] ) ) {
			$product->set_length( $row[ "Length ($wc_distance_unit)" ] );
		}

		if ( ! empty( $row[ "Width ($wc_distance_unit)" ] ) ) {
			$product->set_width( $row[ "Width ($wc_distance_unit)" ] );
		}

		if ( ! empty( $row[ "Height ($wc_distance_unit)" ] ) ) {
			$product->set_height( $row[ "Height ($wc_distance_unit)" ] );
		}

		// Loop meta fields.
		foreach ( $row as $key => $value ) {
			if ( 0 === stripos( $key, 'Meta: ' ) ) {
				$meta_key = trim( str_replace( 'Meta: ', '', $key ) );
				$product->update_meta_data( $meta_key, $value );
			}
		}

		// Loop attribute fields.
		$attr_i      = 1;
		$next_attr_i = true;
		$attributes  = array();
		while ( $next_attr_i ) {
			if ( isset( $row[ 'Attribute ' . $attr_i . ' name' ] ) ) {
				if ( $row[ 'Attribute ' . $attr_i . ' name' ] ) {
					$options   = explode( ',', $row[ 'Attribute ' . $attr_i . ' value(s)' ] );
					$attribute = new \WC_Product_Attribute();
					$attribute->set_name( $row[ 'Attribute ' . $attr_i . ' name' ] );
					$attribute->set_options( $options );
					// $attribute->set_position( 0 );
					$attribute->set_visible( true );
					$attribute->set_variation( true ); // set for use on variations.
					$attributes[] = $attribute;
				}
				// Check next.
				$attr_i++;
			} else {
				$next_attr_i = false;
			}
		}

		$product->set_attributes( $attributes );
		$product->save();

		$faire_wc_prevent_dups_product_updated_id[] = $product->get_id(); // Ignore sync on change event updates.

		return $product;
	}

	/**
	 * Create variation from faire variant
	 *
	 * @return \WC_Product_Variation|false
	 */
	public function create_variation( $row, $parent_product = null ) {

		global $faire_wc_prevent_dups_product_updated_id;

		// Get parent for variation.
		$parent_product_id = null;
		if ( $parent_product && is_a( $parent_product, 'WC_Product' ) ) {
			$parent_product_id = $parent_product->get_id();
		} else {
			if ( 0 === stripos( $row['Parent'], 'id:' ) ) {
				$lookup_id = str_replace( 'id:', '', $row['Parent'] );
			} else {
				$lookup_id = $this->get_product_id_by_sku( $row['Parent'] );
			}
			if ( $lookup_id ) {
				$parent_product = wc_get_product( $lookup_id );
				if ( $parent_product ) {
					$parent_product_id = $parent_product->get_id();
				}
			}
		}

		if ( ! $parent_product_id ) {
			return false;
		}

		// Get existing parent product variation attributes.
		$product_attributes  = $this->get_all_wc_product_variation_attributes( $parent_product );
		$product_option_sets = array();
		foreach ( $product_attributes as $attribute_key => $attribute_data ) {
			$option_name = $attribute_data['name'];
			// Build array of WC options.
			$product_option_sets[ $option_name ] = array(
				'attribute_key' => $attribute_key,
				'name'          => $option_name,
				'values'        => $attribute_data['values'], // empty = "all"
			);
		}

		// Create variation product.
		$variation = new \WC_Product_Variation();
		$variation->set_name( $row['Name'] );
		$variation->set_sku( $row['SKU'] );
		$variation->set_parent_id( $parent_product_id );

		if ( '' !== $row['Regular price'] ) {
			$variation->set_regular_price( $row['Regular price'] ); // in current shop currency
		}

		// Stock management.
		if ( 1 === $row['In stock?'] ) {
			$variation->set_stock_status( 'instock' ); // 'instock', 'outofstock' or 'onbackorder'
		} else {
			$variation->set_stock_status( 'outofstock' );
		}
		if ( '' !== $row['Stock'] ) {
			$variation->set_manage_stock( true );
			$variation->set_stock_quantity( $row['Stock'] );
		}

		if ( $row['Images'] ) {
			$image_array = explode( ', ', $row['Images'] );
			foreach ( $image_array as $image_url ) {
				try {
					$variation->set_image_id( $this->get_attachment_id_from_url( $image_url, $variation->get_id() ) );
				} catch ( Exception $e ) {
					error_log( 'create_variation variation->set_image_id Exception: ' . $e->getMessage() );
				}
			}
		}

		// Add measurements.
		$wc_mass_unit     = get_option( 'woocommerce_weight_unit', 'kg' );
		$wc_distance_unit = get_option( 'woocommerce_dimension_unit', 'cm' );

		if ( ! empty( $row[ "Weight ($wc_mass_unit)" ] ) ) {
			$variation->set_weight( $row[ "Weight ($wc_mass_unit)" ] );
		}

		if ( ! empty( $row[ "Length ($wc_distance_unit)" ] ) ) {
			$variation->set_length( $row[ "Length ($wc_distance_unit)" ] );
		}

		if ( ! empty( $row[ "Width ($wc_distance_unit)" ] ) ) {
			$variation->set_width( $row[ "Width ($wc_distance_unit)" ] );
		}

		if ( ! empty( $row[ "Height ($wc_distance_unit)" ] ) ) {
			$variation->set_height( $row[ "Height ($wc_distance_unit)" ] );
		}

		// Loop meta fields.
		foreach ( $row as $key => $value ) {
			if ( 0 === stripos( $key, 'Meta: ' ) ) {
				$meta_key = trim( str_replace( 'Meta: ', '', $key ) );
				$variation->update_meta_data( $meta_key, $value );
			}
		}

		// Loop attribute fields.
		$attr_i      = 1;
		$next_attr_i = true;
		$attributes  = array();
		while ( $next_attr_i ) {
			if ( isset( $row[ 'Attribute ' . $attr_i . ' name' ] ) ) {
				if ( $row[ 'Attribute ' . $attr_i . ' name' ] && $row[ 'Attribute ' . $attr_i . ' value(s)' ] ) {
					$option_name  = $row[ 'Attribute ' . $attr_i . ' name' ];
					$option_value = $row[ 'Attribute ' . $attr_i . ' value(s)' ];

					// Lookup attribute key by option name (key contains WP taxonomy name).
					$attr_key = '';
					if ( isset( $product_option_sets[ $option_name ] ) ) {
						$attr_key = $product_option_sets[ $option_name ]['attribute_key'];
					} else {
						$attr_key = $option_name;
					}

					// Check if attribute is taxonomy term, and create if the term does not exist.
					if ( 0 === stripos( $attr_key, 'pa_' ) ) {

						$taxonomy = $attr_key;
						if ( ! term_exists( $option_value, $taxonomy ) ) {
							wp_insert_term( $option_value, $taxonomy );
						}

						$term_slug = get_term_by( 'name', $option_value, $taxonomy )->slug; // Use the term slug as option value.

						// Get the post Terms names from the parent variable product. Add if missing.
						$post_term_names = wp_get_post_terms( $parent_product_id, $taxonomy, array( 'fields' => 'names' ) );
						if ( ! in_array( $option_value, $post_term_names ) ) {
							wp_set_post_terms( $parent_product_id, $option_value, $taxonomy, true );
						}

						$option_value = $term_slug;
					}

					$attributes[ $attr_key ] = $option_value;
				}

				// Check next.
				$attr_i++;
			} else {
				$next_attr_i = false;
			}
		}

		$variation->set_attributes( $attributes );
		$res = $variation->save();

		$faire_wc_prevent_dups_product_updated_id[] = $variation->get_id(); // Ignore sync on change event updates

		return $variation;
	}

	/**
	 * Get attachment ID.
	 * Based on WC_Product_Importer Class
	 *
	 * @param  string $url        Attachment URL.
	 * @param  int    $product_id Product ID.
	 * @return int
	 * @throws Exception If attachment cannot be loaded.
	 */
	public function get_attachment_id_from_url( $url, $product_id ) {
		if ( empty( $url ) ) {
			return 0;
		}

		$id         = 0;
		$upload_dir = wp_upload_dir( null, false );
		$base_url   = $upload_dir['baseurl'] . '/';

		// Check first if attachment is inside the WordPress uploads directory, or we're given a filename only.
		if ( false !== strpos( $url, $base_url ) || false === strpos( $url, '://' ) ) {
			// Search for yyyy/mm/slug.extension or slug.extension - remove the base URL.
			$file = str_replace( $base_url, '', $url );
			$args = array(
				'post_type'   => 'attachment',
				'post_status' => 'any',
				'fields'      => 'ids',
				'meta_query'  => array( // @codingStandardsIgnoreLine.
					'relation' => 'OR',
					array(
						'key'     => '_wp_attached_file',
						'value'   => '^' . $file,
						'compare' => 'REGEXP',
					),
					array(
						'key'     => '_wp_attached_file',
						'value'   => '/' . $file,
						'compare' => 'LIKE',
					),
					array(
						'key'     => '_wc_attachment_source',
						'value'   => '/' . $file,
						'compare' => 'LIKE',
					),
				),
			);
		} else {
			// This is an external URL, so compare to source.
			$args = array(
				'post_type'   => 'attachment',
				'post_status' => 'any',
				'fields'      => 'ids',
				'meta_query'  => array( // @codingStandardsIgnoreLine.
					array(
						'value' => $url,
						'key'   => '_wc_attachment_source',
					),
				),
			);
		}

		$ids = get_posts( $args ); // @codingStandardsIgnoreLine.

		if ( $ids ) {
			$id = current( $ids );
		}

		// Upload if attachment does not exists.
		if ( ! $id && stristr( $url, '://' ) ) {
			$upload = wc_rest_upload_image_from_url( $url );

			if ( is_wp_error( $upload ) ) {
				throw new Exception( $upload->get_error_message(), 400 );
			}

			$id = wc_rest_set_uploaded_image_as_attachment( $upload, $product_id );

			if ( ! wp_attachment_is_image( $id ) ) {
				/*
				 translators: %s: image URL */
				throw new Exception( sprintf( __( 'Not able to attach "%s".', 'woocommerce' ), $url ), 400 );
			}

			// Save attachment source for future reference.
			update_post_meta( $id, '_wc_attachment_source', $url );
		}

		if ( ! $id ) {
			/* translators: %s: image URL */
			throw new Exception( sprintf( __( 'Unable to use image "%s".', 'woocommerce' ), $url ), 400 );
		}

		return $id;
	}


	/**
	 * Get products pending create csv
	 *
	 * @return array
	 */
	public function get_products_create_csv(): array {
		$products = get_option( self::OPTION_FAIRE_PRODUCT_LINKING_CREATE, array() );
		if ( ! is_array( $products ) ) {
			$products = array();
		}
		return $products;
	}

	/**
	 * Add to products pending create csv
	 *
	 * @return void
	 */
	public function add_product_create_csv( $faire_product ) {
		$products                       = $this->get_products_create_csv();
		$products[ $faire_product->id ] = $this->create_product_csv_rows( $faire_product );
		update_option( self::OPTION_FAIRE_PRODUCT_LINKING_CREATE, $products, false ); // Set autoload = false
	}

	/**
	 * Remove products pending create csv
	 *
	 * @return void
	 */
	public function empty_product_create_csv() {
		update_option( self::OPTION_FAIRE_PRODUCT_LINKING_CREATE, array(), false ); // Set autoload = false
	}

	/**
	 * Get variants pending create
	 *
	 * @return array
	 */
	public function get_variants_create_csv(): array {
		$variants = get_option( self::OPTION_FAIRE_VARIATIONS_LINKING_CREATE, array() );
		if ( ! is_array( $variants ) ) {
			$variants = array();
		}
		return $variants;
	}

	/**
	 * Add to variants pending create csv
	 *
	 * @return void
	 */
	public function add_variant_create_csv( $faire_variant, $faire_product, $parent_col_match, $parent_product = null ) {
		$variants                       = $this->get_variants_create_csv();
		$variants[ $faire_variant->id ] = array(
			$this->create_variant_csv_rows( $faire_variant, $faire_product, $parent_col_match, $parent_product ),
		);
		update_option( self::OPTION_FAIRE_VARIATIONS_LINKING_CREATE, $variants, false ); // Set autoload = false.
	}

	/**
	 * Remove variants pending create csv
	 *
	 * @return void
	 */
	public function empty_variant_create_csv() {
		update_option( self::OPTION_FAIRE_VARIATIONS_LINKING_CREATE, array(), false ); // Set autoload = false.
	}

	/**
	 * Retrieves a page of Faire products that were updated after a given date.
	 *
	 * @param array $args Arguments to retrieve products from the Faire API.
	 *
	 * @return object[] Page of products.
	 */
	private function get_products_linking_page( array $args ): array {
		$default_args = array(
			'page'  => 1,
			'limit' => self::PRODUCTS_PER_PAGE,
		);

		$args = wp_parse_args( $args, $default_args );

		try {
			$products_linking = $this->product_api->get_products( $args )->products;
		} catch ( Exception $e ) {
			$products_linking = array(
				'error' => array(
					'code'    => $e->getCode(),
					'message' => $e->getMessage(),
				),
			);
		}

		return $products_linking;
	}

	/**
	 * Retrieves the ISO 8601 timestamp of the last linking products syncing.
	 *
	 * If no timestamp exists, we assume it should be "now".
	 *
	 * @param string $default Default value;
	 *
	 * @return string
	 *   The ISO 8601 timestamp of the last linking products syncing.
	 */
	public function get_products_linking_last_sync_date( $default = '' ): string {
		return get_option(
			self::OPTION_PRODUCTS_LINKING_LAST_SYNC_DATE,
			$default ? $default : gmdate( 'Y-m-d\TH:i:s.v\Z' )
		);
	}

	/**
	 * Saves the ISO 8601 timestamp of the last linking products syncing.
	 *
	 * If no timestamp is given, we assume it should be "now".
	 *
	 * @param string $date ISO 8601 timestamp.
	 */
	public function save_products_linking_last_sync_date( string $date = '' ) {
		update_option(
			self::OPTION_PRODUCTS_LINKING_LAST_SYNC_DATE,
			$date ? $date : gmdate( 'Y-m-d\TH:i:s.v\Z' ),
			false
		);
	}

	/**
	 * Saves the last time an linking products sync finished.
	 */
	public function save_last_sync_finish_timestamp() {
		update_option( self::OPTION_PRODUCTS_LINKING_LAST_SYNC_FINISH_TIME, time() );
	}

	/**
	 * Retrieves the last time an linking products sync finished.
	 *
	 * @return int The time in seconds
	 */
	public function get_last_sync_finish_timestamp(): int {
		return (int) get_option( self::OPTION_PRODUCTS_LINKING_LAST_SYNC_FINISH_TIME, 0 );
	}

	/**
	 * Saves the results of an linking products sync as a string.
	 *
	 * @param array $results List of results of the linking products sync.
	 */
	public function save_products_linking_sync_results( array $results ) {
		$results_details = $this->get_products_linking_sync_results_details( $results );

		$summary_entries = array(
			'total_linking'                 => __( 'Linking processed for %d faire products and variants', 'faire-for-woocommerce' ),
			''                              => '',
			// 'success_linking'               => '', // Omitted, instead using more accurate representation of successes in success_linked_linking
			'success_linked_linking'        => __( 'Successfully linked count: %d', 'faire-for-woocommerce' ),
			'queued_sync_linking'           => '- ' . __( 'Queued sync for products: %d', 'faire-for-woocommerce' ),
			'manual_link_linking'           => '- ' . __( 'Manual linking required products: %d', 'faire-for-woocommerce' ),
			'created_product_linking'       => '- ' . __( 'Created products: %d', 'faire-for-woocommerce' ),
			'created_variant_linking'       => '- ' . __( 'Created variations: %d', 'faire-for-woocommerce' ),
			'import_product_linking'        => '- ' . __( 'Added rows to product import csv: %d', 'faire-for-woocommerce' ),
			'import_variant_linking'        => '- ' . __( 'Added rows to variations import csv: %d', 'faire-for-woocommerce' ),
			'failed_linking'                => __( 'Failed and skipped count: %d', 'faire-for-woocommerce' ),
			'already_linked_linking'        => '- ' . __( 'Skipped already linked: %d', 'faire-for-woocommerce' ),
			'nonmatching_options_linking'   => '- ' . __( 'Skipped with nonmatching option sets: %d', 'faire-for-woocommerce' ),
			'multiple_matches_linking'      => '- ' . __( 'Multiple matches found: %d', 'faire-for-woocommerce' ),
			'no_sku_linking'                => '- ' . __( 'Products or variants with no sku: %d', 'faire-for-woocommerce' ),
			'failed_create_product_linking' => '- ' . __( 'Failed to create products: %d', 'faire-for-woocommerce' ),
			'failed_create_variant_linking' => '- ' . __( 'Failed to create variations: %d', 'faire-for-woocommerce' ),
		);
		$summary         = sprintf(
			__( 'Last sync at %s', 'faire-for-woocommerce' ),
			gmdate( 'Y-m-d\TH:i:s.v\Z', $this->get_last_sync_finish_timestamp() )
		);

		if ( $results_details['total_linking'] ) {
			foreach ( $summary_entries as $entry_name => $entry_text ) {
				$entry = $this->get_summary_entry( $results_details, $entry_name, $entry_text );
				if ( $entry || '' === $entry_name ) {
					$summary .= PHP_EOL . ( $entry_name ? $entry : '' );
				}
			}
		} else {
			$summary .= PHP_EOL . __( 'Could not find faire products to import.', 'faire-for-wordpress' );
		}

		$this->settings->update_option( self::SETTING_PRODUCTS_LINKING_SYNC_RESULTS, $summary );
	}

	/**
	 * Builds an entry for the sync results summary.
	 *
	 * @param array  $results_details Detailed sync results.
	 * @param string $entry_name     Name of the details entry,
	 * @param string $entry_text     Text to add to the summary.
	 *
	 * @return string The summary entry.
	 */
	private function get_summary_entry(
		array $results_details,
		string $entry_name,
		string $entry_text
	): string {
		$summary_entry = '';
		if ( $entry_name && $results_details[ $entry_name ] ) {
			$summary_entry = sprintf( $entry_text, $results_details[ $entry_name ] );
		}
		return $summary_entry;
	}

	/**
	 * Extracts details from the linking product sync results.
	 *
	 * @param array $results Results of the linking product sync.
	 *
	 * @return array Details of the linking product sync results.
	 */
	private function get_products_linking_sync_results_details( array $results ): array {
		$already_linked        = 0;
		$multiple_matches      = 0;
		$nonmatching_options   = 0;
		$success_linked        = 0;
		$manual_link           = 0;
		$import_product        = 0;
		$import_variant        = 0;
		$queued_sync           = 0;
		$created_product       = 0;
		$created_variant       = 0;
		$failed_create_product = 0;
		$failed_create_variant = 0;
		$no_sku                = 0;
		$failed                = 0;
		$success               = 0;
		$total                 = 0;
		foreach ( $results as $result ) {
			if ( 'error' === $result['status'] ) {
				if ( false !== strpos( $result['info'], 'Could not find faire products' ) ) {
					continue;
				}

				$already_linked        +=
					( false !== strpos( $result['info'], 'already linked' ) ) ? 1 : 0;
				$multiple_matches      +=
					( false !== strpos( $result['info'], 'Multiple product matches' ) ) ? 1 : 0;
				$no_sku                +=
				( false !== strpos( $result['info'], 'is missing a sku' ) ) ? 1 : 0;
				$nonmatching_options   +=
				( false !== strpos( $result['info'], 'option sets do not match' ) ) ? 1 : 0;
				$failed_create_product +=
					( false !== strpos( $result['info'], 'Failed to create faire product' ) ) ? 1 : 0;
				$failed_create_variant +=
					( false !== strpos( $result['info'], 'Failed to create faire variant' ) ) ? 1 : 0;
				$total++;
				$failed++;
				continue;
			} elseif ( 'success' === $result['status'] ) {

				$success_linked  +=
					( false !== strpos( $result['info'], 'successfully linked with product' ) ) ? 1 : 0;
				$queued_sync     +=
					( false !== strpos( $result['info'], 'link queued for next sync' ) ) ? 1 : 0;
				$manual_link     +=
					( false !== strpos( $result['info'], 'Manual linking required' ) ) ? 1 : 0;
				$import_product  +=
					( false !== strpos( $result['info'], 'Added missing faire product' ) ) ? 1 : 0;
				$import_variant  +=
					( false !== strpos( $result['info'], 'Added missing faire variant' ) ) ? 1 : 0;
				$created_product +=
					( false !== strpos( $result['info'], 'Created faire product' ) ) ? 1 : 0;
				$created_variant +=
					( false !== strpos( $result['info'], 'Created faire variant' ) ) ? 1 : 0;

			}
			// Skip summary of successfully imported linking products.
			if ( false !== strpos( $result['info'], 'Successfully linked:' ) ) {
				continue;
			}
			$total++;
			$success++;
		}

		return array(
			'already_linked_linking'        => $already_linked,
			'multiple_matches_linking'      => $multiple_matches,
			'nonmatching_options_linking'   => $nonmatching_options,
			'success_linked_linking'        => $success_linked,
			'queued_sync_linking'           => $queued_sync,
			'manual_link_linking'           => $manual_link,
			'import_product_linking'        => $import_product,
			'import_variant_linking'        => $import_variant,
			'created_product_linking'       => $created_product,
			'created_variant_linking'       => $created_variant,
			'failed_create_product_linking' => $failed_create_product,
			'failed_create_variant_linking' => $failed_create_variant,
			'no_sku_linking'                => $no_sku,
			'failed_linking'                => $failed,
			'success_linking'               => $success,
			'total_linking'                 => $total,
		);
	}

	/**
	 * Retrieves the results of last linking products sync.
	 *
	 * @return string Results of the linking products sync.
	 */
	public function get_products_linking_sync_results(): string {
		return $this->settings->get_option( self::SETTING_PRODUCTS_LINKING_SYNC_RESULTS, '' );
	}

	/**
	 * Builds a success or error entry to be recorded as an product check result.
	 *
	 * @param bool      $is_success True is result was successful.
	 * @param string    $info Additional information about the result.
	 * @param null|bool $products_exist If products exist.
	 *
	 * @return array The result entry.
	 */
	public static function create_product_check_result_entry(
		bool $is_success,
		string $info,
		$products_exist = null
	): array {
		return array(
			'status'         => $is_success ? 'success' : 'error',
			'info'           => $info,
			'products_exist' => $products_exist,
		);
	}

	/**
	 * Return all product IDs based on SKU.
	 *
	 * Based loosely on WC_Product_Data_Store_CPT::get_product_id_by_sku()
	 * Allows multiple products to be returned.
	 *
	 * Allows results to be filtered so specific products can be selected externally.
	 *
	 * @param string $sku Product SKU.
	 * @return array
	 */
	public function get_product_ids_by_sku( $sku ) {
		global $wpdb;

		// phpcs:ignore WordPress.VIP.DirectDatabaseQuery.DirectQuery
		$results = $wpdb->get_results(
			$wpdb->prepare(
				"
				SELECT posts.ID
				FROM {$wpdb->posts} as posts
				INNER JOIN {$wpdb->wc_product_meta_lookup} AS lookup ON posts.ID = lookup.product_id
				WHERE
				posts.post_type IN ( 'product', 'product_variation' )
				AND posts.post_status != 'trash'
				AND lookup.sku = %s
				",
				$sku
			)
		);

		$ids = array();
		foreach ( $results as $r ) {
			$ids[] = $r->ID;
		}

		return (array) apply_filters( 'faire_wc_get_product_ids_by_sku', $ids, $sku );
	}

	/**
	 * Return a single product ID from an array of multiple
	 *
	 * @param string $sku Product SKU.
	 * @return int|false
	 */
	public function get_product_id_by_sku( $sku ) {
		$ids = $this->get_product_ids_by_sku( $sku );
		return ( $ids ) ? (int) array_shift( $ids ) : false;
	}

	/**
	 * Return a single product ID from an array of multiple
	 *
	 * @param string $sku Product SKU.
	 * @return \WC_Product|false
	 */
	public function get_product_by_sku( $sku ) {
		$ids = $this->get_product_ids_by_sku( $sku );
		foreach ( $ids as $id ) {
			$product = wc_get_product( $id );
			if ( $product && ( $product->get_type() === 'simple' || $product->get_type() === 'variation' ) ) {
				return $product;
			}
		}
		return false;
	}

}
