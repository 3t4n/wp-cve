<?php

/**
 * Prevent direct access to the script.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Woo_Stock_Sync_Api_Request {
	public $errors = [];
	public $response = false;

	/**
	 * Constructor
	 */
	public function __construct() {
	}

	/**
	 * Push multiple changes in one request
	 */
	public function push_multiple( $data, $site ) {
		update_option( 'wss_last_sync', time() );

		$client = $this->get_api_client( $site );

		// We need to pass these params for each row due to REST controller
		// batch update logic
		$params_defaults = [
			'woo_stock_sync' => '1',
			'woo_stock_sync_source' => get_site_url(),
			'woo_stock_sync_source_role' => wss_get_role(),
			'context' => 'edit',
		];

		$params = ['update' => []];
		$skus = [];
		$changes = [];
		foreach ( $data as $row ) {
			$product = $row['product'];
			$sku = (string) $product->get_sku( 'edit' );

			if ( strlen( $sku ) > 0 ) {
				$row_params = array_merge( $params_defaults, [
					'sku_param' => $sku,
					'source_desc' => isset( $row['source_desc'] ) ? $row['source_desc'] : null,
					'source_url' => isset( $row['source_url'] ) ? $row['source_url'] : null,
					'log_id' => isset( $row['log_id'] ) ? $row['log_id'] : null,
				] );

				switch ( $row['operation'] ) {
					case 'set':
						$row_params['stock_quantity'] = $row['value'];
						$changes[] = sprintf( '%s set %d', $product->get_formatted_name(), $row['value'] );
						break;
					case 'increase':
						$row_params['inventory_delta'] = absint( $row['value'] );
						$changes[] = sprintf( '%s increase by %d', $product->get_formatted_name(), absint( $row['value'] ) );
						break;
					case 'decrease':
						$row_params['inventory_delta'] = -1 * absint( $row['value'] );
						$changes[] = sprintf( '%s decrease by %d', $product->get_formatted_name(), absint( $row['value'] ) );
						break;
				}

				$params['update'][] = $row_params;
				$skus[$sku] = $product->get_id();
			} else {
				if ( isset( $row['log_id'] ) && ! empty( $row['log_id'] ) ) {
					$errors['sku_missing'] = __( "SKU is not set", 'woo-stock-sync' );

					Woo_Stock_Sync_Logger::log_update( $row['log_id'], $site['key'], false, $errors, 'warning' );
				}
			}
		}

		// No products with SKUs, skip
		if ( empty( $params['update'] ) ) {
			return true;
		}

		$params['woo_stock_sync'] = '1';
		$params['woo_stock_sync_source'] = get_site_url();
		$params['woo_stock_sync_source_role'] = wss_get_role();
		$params['context'] = 'edit';

		$params = apply_filters( 'wss_params_push_multiple', $params, $data, $site );

		try {
			$client->post( "stock-sync-batch", $params );
		} catch ( \Exception $e ) {
			$this->errors['exception_update'] = sprintf( __( "Exception while trying to push multiple changes. Message: %s", 'woo-stock-sync' ), $e->getMessage() );

			if ( is_a( $e, 'Automattic\WooCommerce\HttpClient\HttpClientException' ) ) {
				$this->response = $e->getResponse();
			}

			if ( wss_is_primary() ) {
				foreach ( $params['update'] as $row ) {
					Woo_Stock_Sync_Logger::log_update( $row['log_id'], $site['key'], false, $this->errors, 'error' );
				}
			} else {
				// Log complete failure
				Woo_Stock_Sync_Logger::log(
					sprintf( __( 'Failed to sync multiple changes. Changes:<br>%s<br>Errors: %s', 'woo-stock-sync' ), implode( '<br>', $changes ), $e->getMessage() ),
					'error',
					null,
					[
						'source' => get_site_url(),
						'source_desc' => isset( $row['source_desc'] ) ? $row['source_desc'] : null,
						'source_url' => isset( $row['source_url'] ) ? $row['source_url'] : null,
					]
				);
			}

			return false;
		}

		$response = $client->http->getResponse();
		if ( $response->getCode() === 200 ) {
			$body = json_decode( $response->getBody() );

			foreach ( $body->update as $ext_product ) {
				$product_errors = [];
				$level = '';

				if ( ! isset( $ext_product->error ) && isset( $ext_product->id, $skus[$ext_product->sku] ) ) {
					wss_update_ext_qty(
						$skus[$ext_product->sku],
						$site['key'],
						$ext_product->stock_quantity,
						$ext_product->id,
						$ext_product->name,
						$ext_product->parent_id
					);

					$level = 'ok';
				} else if ( isset( $ext_product->error, $ext_product->error->code, $skus[$ext_product->sku] ) && $ext_product->error->code === 'woocommerce_rest_product_invalid_sku' ) {
					wss_reset_sync_status( $skus[$ext_product->sku], $site['key'], true );

					$product_errors['not_found'] = __( "Product not found by SKU", 'woo-stock-sync' );
					$level = 'warning';
				} else if ( isset( $ext_product->error ) ) {
					$product_errors[$ext_product->error->code] = $ext_product->error->message;
					$level = 'error';
				} else {
					$product_errors['unknown'] = __( 'Unknown error', 'woo-stock-sync' );
					$level = 'error';
				}

				if ( wss_is_primary() && isset( $ext_product->log_id ) ) {
					Woo_Stock_Sync_Logger::log_update( $ext_product->log_id, $site['key'], empty( $product_errors ), $product_errors, $level );
				}
			}

			return true;
		}

		$error = sprintf( __( "Invalid response code %s", 'woo-stock-sync' ), $response->getCode() );

		// Update logs
		if ( wss_is_primary() ) {
			foreach ( $params['update'] as $row ) {
				if ( isset( $row['log_id'] ) && ! empty( $row['log_id'] ) ) {
					Woo_Stock_Sync_Logger::log_update( $row['log_id'], $site['key'], false, [ $error ], 'error' );
				}
			}
		} else {
			Woo_Stock_Sync_Logger::log(
				sprintf( __( 'Failed to sync multiple changes. Changes:<br>%s<br>Errors: %s', 'woo-stock-sync' ), implode( '<br>', $changes ), $error ),
				'error',
				null,
				[
					'source' => get_site_url(),
					'source_desc' => $row['source_desc'],
					'source_url' => $row['source_url'],
				]
			);
		}

		$this->errors['invalid_response'] = $error;

		return false;
	}

	/**
	 * Update sync status for products
	 */
	public function update( $site, $products ) {
		update_option( 'wss_last_sync', time() );

		$client = $this->get_api_client( $site );

		// Create an array of SKUs
		$skus = [];
		foreach ( $products as $product ) {
			$sku = (string) $product->get_sku( 'edit' );

			if ( strlen( $sku ) > 0 ) {
				$skus[] = $sku;
			}
		}

		$ext_products = [];

		// Fetch external products by SKU
		try {
			$client->get( 'products', array(
				'sku' => implode( ',', $skus ),
				'per_page' => 100,
				'context' => 'edit',
			) );
		} catch ( \Exception $e ) {
			$this->errors['exception_update'] = sprintf( __( "Exception while trying to update sync status. Message: %s", 'woo-stock-sync' ), $e->getMessage() );

			return false;
		}

		$response = $client->http->getResponse();
		if ( $response->getCode() === 200 ) {
			$results = json_decode( $response->getBody() );

			foreach ( $results as $result ) {
				$ext_products[$result->sku] = $result;
			}
		} else {
			$this->errors['invalid_response'] = sprintf( __( "Invalid response by API. HTTP status code: %s", 'woo-stock-sync' ), $response->getCode() );

			return false;
		}

		// Check which products have corresponding external product
		foreach ( $products as $product ) {
			$sku = (string) $product->get_sku( 'edit' );

			if ( strlen( $sku ) > 0 && isset( $ext_products[$sku] ) ) {
				$ext_product = $ext_products[$sku];

				wss_update_ext_qty(
					$product,
					$site['key'],
					$ext_product->stock_quantity,
					$ext_product->id,
					$ext_product->name,
					$ext_product->parent_id
				);
			} else {
				// Reset current data
				wss_reset_sync_status( $product->get_id(), $site['key'] );
			}
		}

		return true;
	}

	/**
	 * Get API client
	 */
	private function get_api_client( $site ) {
		return Woo_Stock_Sync_Api_Client::create( $site['url'], $site['api_key'], $site['api_secret'] );
	}
}
