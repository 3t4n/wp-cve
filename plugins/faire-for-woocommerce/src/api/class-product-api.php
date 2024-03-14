<?php
/**
 * Faire API Product functionality.
 *
 * @package  FAIRE
 */

namespace Faire\Wc\Api;

use Exception;
use Faire\Wc\Api\Client\Faire_Product_Info;
use Faire\Wc\Api\Client\Faire_Product_Variant_Info;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Product_Api extends Faire_Api {

  /**
	 * The API client instance.
	 *
	 * @var Client\Product_Client
	 */
	protected $api_client;

	/**
	 * Constructor.
	 *
	 * @since [*next-version*]
	 *
	 * @throws Exception If an error occurred while creating the API driver, auth or client.
	 */
	public function __construct() {
    parent::__construct( __NAMESPACE__ . '\Client\Product_Client' );
	}

	/**
	 * Get a single product
	 *
	 * @param string $id The product ID.
	 *
	 * @return object
	 * @throws Exception
	 */
	public function get_product( string $id ): object {
		return $this->api_client->get_product( $id );
	}

	/**
	 * Get products
	 *
	 * @param array $args The array of arguments.
	 *
	 * @return object
	 * @throws Exception
	 */
	public function get_products( $args = array() ): object {
		return $this->api_client->get_products( $args );
	}

	/**
	 * Create Product
	 *
	 * @param array $args The array of arguments.
	 *
	 * @return object
	 * @throws Exception
	 * @since [*next-version*]
	 */
	public function create_product( $args = array() ): object {
		$product_info = new Faire_Product_Info( $args );
		return $this->api_client->create_product( $product_info );
	}

	/**
	 * Update Product
	 *
	 * @param string $id The product ID.
	 * @param array  $args The array of arguments.
	 *
	 * @return object
	 * @throws Exception
	 * @since [*next-version*]
	 */
	public function update_product( string $id, array $args = array() ): object {
		$product_info = new Faire_Product_Info( $args );
		return $this->api_client->update_product( $id, $product_info );
	}

	/**
	 * Delete a single product
	 *
	 * @param string $id The product ID.
	 *
	 * @return object
	 * @throws Exception
	 */
	public function delete_product( string $id ): object {
		return $this->api_client->delete_product( $id );
	}

	/**
	 * Create Product Variant
	 *
	 * @param string $id The product id.
	 *
	 * @param array $args The array of arguments.
	 *
	 * @return object
	 * @throws Exception
	 * @since [*next-version*]
	 */
	public function create_product_variant( string $id, $args = array() ): object {
		$product_variant_info = new Faire_Product_Variant_Info( $args );
		return $this->api_client->create_product_variant( $id, $product_variant_info );
	}

	/**
	 * Update Product Variant
	 *
	 * @param string $id The product id.
	 *
	 * @param string $variant_id The product variant id.
	 *
	 * @param array  $args The array of arguments.
	 *
	 * @return object
	 * @throws Exception
	 * @since [*next-version*]
	 */
	public function update_product_variant( string $id, string $variant_id, array $args = array() ): object {
		$product_variant_info = new Faire_Product_Variant_Info( $args );
		return $this->api_client->update_product_variant( $id, $variant_id, $product_variant_info );
	}

	/**
	 * Delete a Product variant
	 *
	 * @param string $id The product id.
	 *
	 * @param string $variant_id The product variant id.
	 *
	 * @return object
	 * @throws Exception
	 */
	public function delete_product_variant( string $id, string $variant_id ): object {
		return $this->api_client->delete_product_variant( $id, $variant_id );
	}

	/**
	 * Update Variants Inventories
	 *
	 * @param array $args The array of arguments.
	 *
	 * @return object
	 * @throws Exception
	 * @since [*next-version*]
	 */
	public function update_variants_inventories( array $args = array() ): object {
		return $this->api_client->update_variants_inventories( $args );
	}

	/**
	 * Get Taxonomy Types
	 *
	 * @return object
	 * @throws Exception
	 * @since [*next-version*]
	 */
	public function get_taxonomy_types(): object {
		return $this->api_client->get_taxonomy_types();
	}

}
