<?php

namespace Faire\Wc\Api\Client;

use Exception;

/**
 * The API client for Faire.
 */
class Product_Client extends Api_Client {

	/**
	 * Get Product from API
	 * This endpoint retrieves a single product given an ID, regardless of state (active or deleted).
	 *
	 * @param string $id The product ID.
	 *
	 * @return object The item information as returned by the remote API.
	 *
	 * @throws Exception
	 */
	public function get_product( string $id ): object {

		// Prepare the request route and data.
		$route = sprintf( 'products/%s', $id );

		// Send the request and get the response.
		$response = $this->get( $route );

		// Return the response body on success.
		return $this->get_response_body( $response, array( 200, 201 ) );
	}

	/**
	 * Get Products from API
	 *
	 * @param array $args Arguments to retrieve the products.
	 *
	 * @return object The item information as returned by the remote API.
	 *
	 * @throws Exception
	 */
	public function get_products( array $args ): object {

		// Get a page of products.
		$response = $this->get_page( 'products', $args );

		// Return the response body on success.
		return $this->get_response_body( $response, array( 200, 201 ) );
	}

	/**
	 * Creates a product.
	 *
	 * @param Faire_Product_Info $product_info The product creation info.
	 *
	 * @return object The item information as returned by the remote API.
	 *
	 * @throws Exception
	 */
	public function create_product( Faire_Product_Info $product_info ): object {

		// Prepare the request route.
		$route = 'products';

		// Build payload data.
		$data = $product_info->product;
		if ( $product_info->variant_option_sets ) {
			$data['variant_option_sets'] = $product_info->variant_option_sets;
		}
		if ( $product_info->variants ) {
			// Remove id if set on POST.
			foreach ( $product_info->variants as $k => $variant ) {
				if ( isset( $variant['id'] ) ) {
					unset( $product_info->variants[ $k ]['id'] );
				}
			}
			$data['variants'] = $product_info->variants;
		}
		if ( $product_info->preorder_details ) {
			$data['preorder_details'] = $product_info->preorder_details;
		}

		// Send the request and get the response.
		$response = $this->post( $route, $data );

		// Return the response body on success.
		return $this->get_response_body( $response, array( 200, 201 ) );
	}

	/**
	 * Updates a product on the remote API.
	 *
	 * @param string             $id The product ID.
	 * @param Faire_Product_Info $product_info The product info to update.
	 *
	 * @return object The item information as returned by the remote API.
	 *
	 * @throws Exception
	 */
	public function update_product( string $id, Faire_Product_Info $product_info ): object {

		// Prepare the request route.
		$route = sprintf( 'products/%s', $id );

		// Build payload data.
		$data = $product_info->product;
		if ( $product_info->variant_option_sets ) {
			$data['variant_option_sets'] = $product_info->variant_option_sets;
		}
		if ( $product_info->variants ) {
			$data['variants'] = $product_info->variants;
		}
		if ( $product_info->preorder_details ) {
			$data['preorder_details'] = $product_info->preorder_details;
		}

		// Send the request and get the response.
		$response = $this->patch( $route, $data );

		// Return the response body on success.
		return $this->get_response_body( $response, array( 200, 201 ) );
	}

	/**
	 * Delete Product from API
	 *
	 * This endpoint deletes a single product given an ID. This means that
	 * the product will have its lifecycle_state set to DELETED and will no
	 * longer be visible on Faire.
	 *
	 * @param string $id The product ID.
	 *
	 * @return object The item information as returned by the remote API.
	 *
	 * @throws Exception
	 */
	public function delete_product( string $id ): object {

		// Prepare the request route and data
		$route = sprintf( 'products/%s', $id );

		// Send the request and get the response
		$response = $this->delete( $route );

		// Return the response body on success
		return $this->get_response_body( $response, array( 204 ) );
	}

	/**
	 * Creates a product variant.
	 *
	 * @param string                     $product_id The product id.
	 *
	 * @param Faire_Product_Variant_Info $product_variant_info The product variant creation info.
	 *
	 * @return object The item information as returned by the remote API.
	 *
	 * @throws Exception
	 */
	public function create_product_variant( string $product_id, Faire_Product_Variant_Info $product_variant_info ): object {

		// Prepare the request route.
		$route = sprintf( 'products/%s/variants', $product_id );

		// Build payload data.
		$data = $product_variant_info->variant;

		// Send the request and get the response.
		$response = $this->post( $route, $data );

		// Return the response body on success.
		return $this->get_response_body( $response, array( 200, 201 ) );
	}

	/**
	 * Updates a product on the remote API.
	 *
	 * @param string                     $product_id The product id.
	 *
	 * @param string                     $variant_id The product variant id.
	 *
	 * @param Faire_Product_Variant_Info $product_variant_info The product variant update info.
	 *
	 * @return object The item information as returned by the remote API.
	 *
	 * @throws Exception
	 */
	public function update_product_variant( string $product_id, string $variant_id, Faire_Product_Variant_Info $product_variant_info ): object {

		// Prepare the request route.
		$route = sprintf( 'products/%s/variants/%s', $product_id, $variant_id );

		// Build payload data.
		$data = $product_variant_info->variant;

		// Send the request and get the response.
		$response = $this->patch( $route, $data );

		// Return the response body on success.
		return $this->get_response_body( $response, array( 200, 201 ) );
	}

	/**
	 * Delete Product Variant from API
	 *
	 * This endpoint deletes a single product given an ID. This means that
	 * the product will have its lifecycle_state set to DELETED and will no
	 * longer be visible on Faire.
	 *
	 * @param string $product_id The product id.
	 *
	 * @param string $variant_id The product variant id.
	 *
	 * @return object The item information as returned by the remote API.
	 *
	 * @throws Exception
	 */
	public function delete_product_variant( string $product_id, string $variant_id ): object {

		// Prepare the request route and data.
		$route = sprintf( 'products/%s/variants/%s', $product_id, $variant_id );

		// Send the request and get the response.
		$response = $this->delete( $route );

		// Return the response body on success.
		return $this->get_response_body( $response, array( 204 ) );
	}

	/**
	 * Updates variants inventories on the remote API.
	 *
	 * @param array $args Arguments to retrieve the products.
	 *
	 * @return object The item information as returned by the remote API.
	 *
	 * @throws Exception
	 */
	public function update_variants_inventories( array $args ): object {

		// Prepare the request route.
		$route = 'products/variants/inventory-levels-by-product-variant-ids';

		// Send the request and get the response.
		$response = $this->patch( $route, $args );

		// Return the response body on success.
		return $this->get_response_body( $response, array( 200, 201 ) );
	}

	/**
	 * Get Taxonomy types from API
	 *
	 * @return object The item information as returned by the remote API.
	 *
	 * @throws Exception
	 */
	public function get_taxonomy_types(): object {
		// Get all taxonomy types.
		$response = $this->get( 'products/types' );

		// Return the response body on success.
		return $this->get_response_body( $response, array( 200, 201 ) );
	}

}
