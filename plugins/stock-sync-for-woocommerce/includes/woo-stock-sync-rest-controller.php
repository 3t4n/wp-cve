<?php

/**
 * Prevent direct access to the script.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WC_REST_Stock_Sync_Controller extends WC_REST_Products_V2_Controller {
	protected $namespace = 'wc/v2';

	protected $rest_base = 'stock-sync';

	public function register_routes() {
		// todo retired, remove at some point
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base . '/(?P<sku>.+)',
			array(
				array(
					'methods' => WP_REST_Server::READABLE,
					'callback' => array( $this, 'get_item' ),
					'permission_callback' => array( $this, 'get_item_permissions_check' ),
					'args' => array(
						'context' => $this->get_context_param(
							array(
								'default' => 'edit',
							)
						),
					),
				),
				array(
					'methods' => WP_REST_Server::EDITABLE,
					'callback' => array( $this, 'update_item' ),
					'permission_callback' => array( $this, 'update_item_permissions_check' ),
				),
				'schema' => array( $this, 'get_public_item_schema' ),
			)
		);

		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base . '-batch',
			array(
				array(
					'methods' => WP_REST_Server::EDITABLE,
					'callback' => array( $this, 'batch_items' ),
					'permission_callback' => array( $this, 'batch_items_permissions_check' ),
				),
				'schema' => array( $this, 'get_public_batch_schema' ),
			)
		);

		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base . '-exists',
			array(
				array(
					'methods' => WP_REST_Server::READABLE,
					'callback' => array( $this, 'exists' ),
					'permission_callback' => array( $this, 'get_items_permissions_check' ),
				),
				'schema' => array( $this, 'get_public_batch_schema' ),
			)
		);
	}

	/**
	 * Get request SKU
	 * 
	 * Some web servers don't accept URL encoded SKUs with special characters
	 * so from version 2.0.3 onwards SKU has been included in JSON params instead of GET params
	 */
	protected function get_request_sku( $request ) {
		if ( $request->has_param( 'sku_param' ) ) {
			return $request['sku_param'];
		} else {
			return urldecode( $request['sku'] );
		}
	}

	/**
	 * Provides way to confirm that Stock Sync is installed
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_Error|WP_REST_Response
	 */
	public function exists( $request ) {
		$response = rest_ensure_response( ['status' => 'ok'] );
		$response->set_status( 200 );

		return $response;
	}

	/**
	 * Get a single item.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_Error|WP_REST_Response
	 */
	public function get_item( $request ) {
		$object = $this->get_object( $this->get_request_sku( $request ) );

		if ( ! $object || 0 === $object->get_id() ) {
			return new WP_Error( "woocommerce_rest_{$this->post_type}_invalid_sku", __( 'Invalid SKU.', 'woocommerce' ), array( 'status' => 404 ) );
		}

		$data = $this->prepare_object_for_response( $object, $request );
		$response = rest_ensure_response( $data );

		if ( $this->public ) {
			$response->link_header( 'alternate', $this->get_permalink( $object ), array( 'type' => 'text/html' ) );
		}

		return $response;
	}

	/**
	 * Check if a given request has access to read an item.
	 *
	 * @param  WP_REST_Request $request Full details about the request.
	 * @return WP_Error|boolean
	 */
	public function get_item_permissions_check( $request ) {
		$object = $this->get_object( $this->get_request_sku( $request ) );

		if ( $object && 0 !== $object->get_id() && ! wc_rest_check_post_permissions( $this->post_type, 'read', $object->get_id() ) ) {
			return new WP_Error( 'woocommerce_rest_cannot_view', __( 'Sorry, you cannot view this resource.', 'woocommerce' ), array( 'status' => rest_authorization_required_code() ) );
		}

		return true;
	}

	/**
	 * Check if a given request has access to update an item.
	 *
	 * @param  WP_REST_Request $request Full details about the request.
	 * @return WP_Error|boolean
	 */
	public function update_item_permissions_check( $request ) {
		$object = $this->get_object( $this->get_request_sku( $request ) );

		if ( $object && 0 !== $object->get_id() && ! wc_rest_check_post_permissions( $this->post_type, 'edit', $object->get_id() ) ) {
			return new WP_Error( 'woocommerce_rest_cannot_edit', __( 'Sorry, you are not allowed to edit this resource.', 'woocommerce' ), array( 'status' => rest_authorization_required_code() ) );
		}

		return true;
	}

	/**
	 * Get object.
	 *
	 * @param int $sku Product SKU.
	 * @return WC_Product|null|false
	 */
	protected function get_object( $sku ) {
		if ( empty( $sku ) ) {
			return null;
		}

		$product_id = $this->get_product_id_by_sku($sku);

		if ( $product_id ) {
			return wc_get_product( $product_id );
		}

		return null;
	}

	/**
	 * Get product ID by SKU
	 */
	protected function get_product_id_by_sku( $sku ) {
		$product_ids = get_posts( [
			'post_type' => [ 'product', 'product_variation' ],
			'post_status' => [ 'any' ],
			'posts_per_page' => 1,
			'fields' => 'ids',
			'meta_query' => [
				[
					'key' => '_sku',
					'value' => $sku,
					'compare' => '='
				]
			]
		] );

		if ( ! empty( $product_ids ) ) {
			return reset( $product_ids );
		}

		return 0;
	}

	/**
	 * Update a single post.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_Error|WP_REST_Response
	 */
	public function update_item( $request ) {
		$object = $this->get_object( $this->get_request_sku( $request ) );

		if ( ! $object || 0 === $object->get_id() ) {
			return new WP_Error( "woocommerce_rest_{$this->post_type}_invalid_sku", __( 'Invalid SKU.', 'woocommerce' ), array( 'status' => 404 ) );
		}

		$object = $this->save_object( $request, false );

		if ( is_wp_error( $object ) ) {
			return $object;
		}

		try {
			//$this->update_additional_fields_for_object( $object, $request );

			/**
			 * Fires after a single object is created or updated via the REST API.
			 *
			 * @param WC_Data         $object    Inserted object.
			 * @param WP_REST_Request $request   Request object.
			 * @param boolean         $creating  True when creating object, false when updating.
			 */
			do_action( "woocommerce_rest_insert_{$this->post_type}_object", $object, $request, false );
		} catch ( WC_Data_Exception $e ) {
			return new WP_Error( $e->getErrorCode(), $e->getMessage(), $e->getErrorData() );
		} catch ( WC_REST_Exception $e ) {
			return new WP_Error( $e->getErrorCode(), $e->getMessage(), array( 'status' => $e->getCode() ) );
		}

		$request->set_param( 'context', 'edit' );
		$response = $this->prepare_object_for_response( $object, $request );
		return rest_ensure_response( $response );
	}

	/**
	 * Save an object data.
	 *
	 * @since  3.0.0
	 * @param  WP_REST_Request $request  Full details about the request.
	 * @param  bool            $creating If is creating a new object.
	 * @return WC_Data|WP_Error
	 */
	protected function save_object( $request, $creating = false ) {
		try {
			$object = $this->prepare_object_for_database( $request, $creating );
			$old_qty = wss_current_stock( $object->get_id() );

			if ( is_wp_error( $object ) ) {
				return $object;
			}

			// Log message
			if ( wss_is_primary() ) {
				$this->log( $request, $object, $old_qty );
			}

			$object->save();

			return wc_get_product( $object->get_id() );
		} catch ( WC_Data_Exception $e ) {
			return new WP_Error( $e->getErrorCode(), $e->getMessage(), $e->getErrorData() );
		} catch ( WC_REST_Exception $e ) {
			return new WP_Error( $e->getErrorCode(), $e->getMessage(), array( 'status' => $e->getCode() ) );
		}
	}

	/**
	 * Log message about change
	 */
	protected function log( $request, $product, $old_qty = false ) {
		$msg = false;

		if ( isset( $request['stock_quantity'] ) ) {
			$msg = sprintf( __( 'Set stock quantity: <a href="%s">%s (%s)</a> %d', 'woo-stock-sync' ), wss_product_url( $product->get_id() ), $product->get_name(), $product->get_sku( 'edit' ), $request['stock_quantity'] );
		} else if ( isset( $request['inventory_delta'] ) ) {
			if ( $request['inventory_delta'] > 0 ) {
				$msg = sprintf( __( 'Stock level increased: <a href="%s">%s (%s)</a> %d &rarr; %d', 'woo-stock-sync' ), wss_product_url( $product->get_id() ), $product->get_name(), $product->get_sku( 'edit' ), $old_qty, $product->get_stock_quantity( 'edit' ) );
			} else {
				$msg = sprintf( __( 'Stock level reduced: <a href="%s">%s (%s)</a> %d &rarr; %d', 'woo-stock-sync' ), wss_product_url( $product->get_id() ), $product->get_name(), $product->get_sku( 'edit' ), $old_qty, $product->get_stock_quantity( 'edit' ) );
			}
		}

		if ( $msg ) {
			$log_id = Woo_Stock_Sync_Logger::log( $msg, 'stock_change', $product->get_id(), [
				'source' => $request['woo_stock_sync_source'],
				'source_url' => isset( $request['source_url'] ) ? $request['source_url'] : null,
				'source_desc' => isset( $request['source_desc'] ) ? $request['source_desc'] : null,
			], 'queued' );

			$GLOBALS['wss_logged_changes'][] = [
				'product_id' => $product->get_id(),
				'qty' => $product->get_stock_quantity( 'edit' ),
				'log_id' => $log_id,
			];
		}
	}

	/**
	 * Prepare a single product for create or update.
	 *
	 * @param WP_REST_Request $request Request object.
	 * @param bool            $creating If is creating a new object.
	 *
	 * @return WP_Error|WC_Data
	 */
	protected function prepare_object_for_database( $request, $creating = false ) {
		$product = $this->get_object( $this->get_request_sku( $request ) );

		// Stock status.
		if ( isset( $request['in_stock'] ) ) {
			$stock_status = true === $request['in_stock'] ? 'instock' : 'outofstock';
		} else {
			$stock_status = $product->get_stock_status();
		}

		// Stock data.
		if ( 'yes' === get_option( 'woocommerce_manage_stock' ) ) {
			if ( $product->is_type( 'grouped' ) ) {
				// Do nothing
			} elseif ( $product->is_type( 'external' ) ) {
				// Do nothing
			} elseif ( $product->get_manage_stock() ) {
				// Stock status is always determined by children so sync later.
				if ( ! $product->is_type( 'variable' ) ) {
					$product->set_stock_status( $stock_status );
				}

				// Stock quantity.
				if ( isset( $request['stock_quantity'] ) ) {
					$product->set_stock_quantity( wc_stock_amount( $request['stock_quantity'] ) );
				} elseif ( isset( $request['inventory_delta'] ) ) {
					$stock_quantity  = wc_stock_amount( $product->get_stock_quantity() );
					$stock_quantity += wc_stock_amount( $request['inventory_delta'] );
					$product->set_stock_quantity( wc_stock_amount( $stock_quantity ) );
				}
			} else {
				// Do nothing
			}
		} elseif ( ! $product->is_type( 'variable' ) ) {
			$product->set_stock_status( $stock_status );
		}

		/**
		 * Filters an object before it is inserted via the REST API.
		 *
		 * The dynamic portion of the hook name, `$this->post_type`,
		 * refers to the object type slug.
		 *
		 * @param WC_Data         $product  Object object.
		 * @param WP_REST_Request $request  Request object.
		 * @param bool            $creating If is creating a new object.
		 */
		return apply_filters( "woocommerce_rest_pre_insert_{$this->post_type}_object", $product, $request, $creating );
	}

	/**
	 * Bulk create, update and delete items.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return array Of WP_Error or WP_REST_Response.
	 */
	public function batch_items( $request ) {
		update_option( 'wss_last_sync', time() );

		/**
		 * REST Server
		 *
		 * @var WP_REST_Server $wp_rest_server
		 */
		global $wp_rest_server;

		// Get the request params.
		$items    = array_filter( $request->get_params() );
		$query    = $request->get_query_params();
		$response = array();

		if ( ! empty( $items['update'] ) ) {
			foreach ( $items['update'] as $item ) {
				$_item = new WP_REST_Request( 'PUT' );
				$_item->set_body_params( $item );
				$_response = $this->update_item( $_item );

				if ( is_wp_error( $_response ) ) {
					$response['update'][] = array(
						'sku' => $item['sku_param'],
						'log_id' => isset( $item['log_id'] ) ? $item['log_id'] : null,
						'error' => array(
							'code' => $_response->get_error_code(),
							'message' => $_response->get_error_message(),
							'data' => $_response->get_error_data(),
						),
					);
				} else {
					$item_response = $wp_rest_server->response_to_data( $_response, '' );
					$item_response['log_id'] = isset( $item['log_id'] ) ? $item['log_id'] : null;
					$response['update'][] = $item_response;
				}
			}
		}

		return $response;
	}
}

add_filter( 'woocommerce_rest_api_get_rest_namespaces', function( $controllers ) {
	$controllers['wc/v2']['stock-sync'] = 'WC_REST_Stock_Sync_Controller';

	return $controllers;
} );
