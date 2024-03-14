<?php
/**
 * REST Controller
 *
 * This class extend `WC_REST_Controller`
 *
 * It's required to follow "Controller Classes" guide before extending this class:
 * <https://developer.wordpress.org/rest-api/extending-the-rest-api/controller-classes/>
 *
 * @class   WC_REST_Bulkproducts_Controller
 * @package NovaModule\RestApi
 * @see     https://developer.wordpress.org/rest-api/extending-the-rest-api/controller-classes/
 */

defined( 'ABSPATH' ) || exit;
if ( ! function_exists( 'wc_rest_check_post_permissions' ) ) {
	require_once ABSPATH . 'wp-content/plugins/woocommerce/includes/wc-rest-functions.php';
}


/**
 * REST API Bulk Products controller class.
 *
 * @package NovaModule\RestApi
 * @extends WC_REST_Controller
 */
class WC_REST_Bulkproducts_Controller  extends WC_REST_Controller {

	/**
	 * Endpoint namespace.
	 *
	 * @var string
	 */
	protected $namespace = 'wc/v3';

	/**
	 * Route base.
	 *
	 * @var string
	 */
	protected $rest_base = 'bulkproducts';

	/**
	 * Register routes.
	 *
	 * @since 3.5.0
	 */
	public function register_routes() {
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base,
			array(
				'methods'             => WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'edit_bulkproducts' ),
				'permission_callback' => array( $this, 'create_item_permissions_check' ),
				'args'                => $this->get_endpoint_args_for_item_schema( WP_REST_Server::CREATABLE ),
			)
		);
	}

	/**
	 * Check if a given request has access to create an item.
	 *
	 * @param  WP_REST_Request $request Full details about the request.
	 * @return WP_Error|boolean
	 */
	public function create_item_permissions_check( $request ) {
		if ( ! wc_rest_check_post_permissions( 'product', 'create' ) ) {
			return new WP_Error( 'woocommerce_rest_cannot_create', __( 'Sorry, you are not allowed to create resources.', 'woocommerce' ), array( 'status' => rest_authorization_required_code() ) );
		}

		return true;
	}

	/**
	 * Add or Edit the Item based on the requests.
	 *
	 * @param WP_REST_Request $request full details about the request.
	 * @return WP_Error|Array
	 */
	public function edit_bulkproducts( WP_REST_Request $request ) {

		$wp_rest_server = rest_get_server();
		$data           = $request->get_params();
		$item_count     = count( $data );
		$response       = array();
		for ( $i = 0; isset( $data[ $i ] ) && $i < $item_count; $i++ ) {
			$each_row                  = $data[ $i ];
			$sku                       = '';
			$post_id                   = '';
			$custom_variable_typelist  = array();
			$custom_variation_typelist = array();

			if ( isset( $each_row['id'] ) && $each_row['id'] ) {
				$post_id = $each_row['id'];
			}
			if ( isset( $each_row['sku'] ) && $each_row['sku'] ) {
				$sku = $each_row['sku'];
			}
			if ( ! $post_id && $sku ) {
				$post_id = $this->getIdBySku( $sku );
			} elseif ( $post_id && ! $this->checkIfProductExists( $post_id ) && $sku ) {
				$post_id = $this->getIdBySku( $sku );
			}

			if ( isset( $each_row['cust_variable_types'] ) && ! ! $each_row['cust_variable_types'] ) {
				$custom_variable_typelist = array_map( 'trim', explode( ',', $each_row['cust_variable_types'] ) );
			}

			if ( isset( $each_row['cust_variation_types'] ) && ! ! $each_row['cust_variation_types'] ) {
				$custom_variation_typelist = array_map( 'trim', explode( ',', $each_row['cust_variation_types'] ) );
			}

			if ( ! ( $post_id || $sku ) ) {
				$response[] = array(
					'id'    => null,
					'error' => array(
						'code'    => 422,
						'message' => "Invalid Data format, either id or sku doesn't exists " . wp_json_encode( $each_row ),
						'data'    => wp_json_encode( $each_row ),
					),
				);
				continue;
			}

			if ( isset( $each_row['meta_data'] ) && ! ! $each_row['meta_data'] ) {
				$each_row['meta_data'] = $this->reArrangeMetaData( $each_row['meta_data'] );
			}

			if ( $post_id && '' !== $post_id ) {
				$product        = wc_get_product( (int) $post_id );
				$each_row['id'] = (int) $post_id;

				if ( ! $product ) {
					$response[] = array(
						'id'    => null,
						'error' => array(
							'code'    => 422,
							'message' => "Invalid Data, either id or sku doesn't exists " . wp_json_encode( $each_row ),
							'data'    => wp_json_encode( $each_row ),
						),
					);
					continue;
				}

				if ( 'variation' === $product->get_type() || in_array( $product->get_type(), $custom_variation_typelist, true ) ) {
					if ( $product->get_parent_id() && ! ( isset( $each_row['product_id'] ) && '' !== $each_row['product_id'] && $each_row['product_id'] ) ) {
						$each_row['product_id'] = $product->get_parent_id();
					}

					if ( ! ( isset( $each_row['product_id'] ) && '' !== $each_row['product_id'] && $each_row['product_id'] ) && isset( $each_row['variantParentSku'] ) && '' !== $each_row['variantParentSku'] ) {
						$parent_id_details = $this->getParentIdBySku( $each_row['variantParentSku'] );
						if ( isset( $parent_id_details['success'] ) && true === $parent_id_details['success'] && $parent_id_details['post_id'] && '' !== $parent_id_details['post_id'] ) {
							$each_row['product_id'] = $parent_id_details['post_id'];
						}
					}

					if ( '' === $each_row['product_id'] || is_null( $each_row['product_id'] ) || ! $each_row['product_id'] ) {
						$response[] = array(
							'id'    => $each_row['id'],
							'error' => array(
								'code'    => 422,
								'message' => 'Invalid Data format, variant parent is missing (' . $each_row['id'] . ')' . wp_json_encode( $each_row ),
								'data'    => wp_json_encode( $each_row ),
							),
						);
						continue;
					}
					$parent = wc_get_product( $each_row['product_id'] );
					if ( ! $parent ) {
						$response[] = array(
							'id'    => $each_row['id'],
							'error' => array(
								'code'    => 422,
								'message' => 'Variation cannot be imported: Missing parent ID or parent does not exist yet (' . $each_row['id'] . ')' . wp_json_encode( $each_row ),
								'data'    => wp_json_encode( $each_row ),
							),
						);
						continue;
					}
					if ( $parent->is_type( 'variation' ) || in_array( $parent->get_type(), $custom_variation_typelist, true ) ) {
						$response[] = array(
							'id'    => $each_row['id'],
							'error' => array(
								'code'    => 422,
								'message' => 'Variation cannot be imported: Parent product cannot be a product variation (' . $each_row['id'] . ')' . wp_json_encode( $each_row ),
								'data'    => wp_json_encode( $each_row ),
							),
						);
						continue;
					}
					if ( $parent ) {
						if ( isset( $each_row['attributes'] ) ) {
							$each_row['attributes'] = $this->reArrangeVariationAttributeData( $parent, $each_row['attributes'] );
						}
					}
					if (isset($each_row['replaceSkusWithIds']) && !!$each_row['replaceSkusWithIds']) {
						$this->replaceItemSkusWithIds($each_row);
						unset( $each_row['replaceSkusWithIds'] );
					}
					$_item = new WP_REST_Request( 'PUT' );
					$_item->set_body_params( $each_row );
					$variations_controler = new WC_REST_Product_Variations_Controller();
					$_response            = $variations_controler->update_item( $_item );
				} else {
					if ( 'grouped' === $product->get_type() ) {
						if ( isset( $each_row['kit_line_item'] ) && $each_row['kit_line_item'] ) {
							$grouped_item_ids             = $this->getTheKitChildItemIds( $each_row['kit_line_item'] );
							$each_row['grouped_products'] = $grouped_item_ids;
						}
					}

					if ( isset( $each_row['attributes'] ) ) {
						if ( 'variable' === $product->get_type() || in_array( $product->get_type(), $custom_variable_typelist, true ) ) {
							$each_row['attributes'] = $this->reArrangeParentAttributeData( $each_row['attributes'] );
						}
						$each_row['attributes'] = $this->reArrangeGlobalAttributeData( $each_row['attributes'] );
					}
					if ( ! ( isset( $each_row['replaceAllCustomAttributes'] ) && ( true === $each_row['replaceAllCustomAttributes'] || 'true' === $each_row['replaceAllCustomAttributes'] ) ) && isset( $each_row['attributes'] ) ) {
						$each_row['attributes'] = $this->appendNonVariationParentAttributeData( $product, $each_row['attributes'] );
					}
					unset( $each_row['replaceAllCustomAttributes'] );
					if (isset($each_row['replaceSkusWithIds']) && !!$each_row['replaceSkusWithIds']) {
						$this->replaceItemSkusWithIds($each_row);
						unset( $each_row['replaceSkusWithIds'] );
					}
					$_item = new WP_REST_Request( 'PUT' );
					$_item->set_body_params( $each_row );
					$products_controller = new WC_REST_Products_Controller();
					$_response           = $products_controller->update_item( $_item );
				}
				if ( is_wp_error( $_response ) ) {
					$response[] = array(
						'id'    => $_item['id'],
						'error' => array(
							'code'    => $_response->get_error_code(),
							'message' => $_response->get_error_message(),
							'data'    => $_response->get_error_data(),
						),
					);
				} else {
					$_response_data = $wp_rest_server->response_to_data( $_response, '' );
					if ( isset( $each_row['__taxonomy'] ) && !!$each_row['__taxonomy'] ) {
						$create_if_not_exists = false;
						if(isset($each_row['__taxonomy_create_if_not_exists'])) { $create_if_not_exists = $each_row['__taxonomy_create_if_not_exists'];}
						$this->setCustomTaxonomyInfo( $_response_data['id'], $each_row['__taxonomy'], $create_if_not_exists);
					}
					if(isset($each_row['__custom_field_key'],$each_row['__custom_field_rows']) && !!$each_row['__custom_field_key']) {
						$this->setAdvancedCustomFieldValues( $_response_data['id'], $each_row['__custom_field_key'], $each_row['__custom_field_rows']);
					}
					$response[] = $_response_data;
				}
			} else {
				unset( $each_row['id'] );
				if ( isset( $each_row['type'] ) && '' !== $each_row['type'] ) {
					if ( 'variation' === $each_row['type'] || in_array( $each_row['type'], $custom_variation_typelist, true ) ) {
						if ( '' !== $sku && ( ! $each_row['product_id'] || '' === $each_row['product_id'] || is_null( $each_row['product_id'] ) ) && isset( $each_row['variantParentSku'] ) && '' !== $each_row['variantParentSku'] ) {
							$parent_id_details = $this->getParentIdBySku( $each_row['variantParentSku'] );
							if ( isset( $parent_id_details['success'] ) && true === $parent_id_details['success'] && $parent_id_details['post_id'] && '' !== $parent_id_details['post_id'] ) {
								$each_row['product_id'] = $parent_id_details['post_id'];
							} elseif ( isset( $parent_id_details['success'] ) && false === $parent_id_details['success'] && $parent_id_details['error'] && '' !== $parent_id_details['error'] ) {
								$response[] = array(
									'id'    => null,
									'error' => array(
										'code'    => 422,
										'message' => $parent_id_details['error'],
										'data'    => wp_json_encode( $each_row ),
									),
								);
								continue;
							}
						}
						if ( isset( $each_row['product_id'] ) && '' !== $each_row['product_id'] && $each_row['product_id'] ) {
							$parent = wc_get_product( $each_row['product_id'] );
							if ( ! $parent ) {
								$response[] = array(
									'id'    => null,
									'error' => array(
										'code'    => 422,
										'message' => 'Variation cannot be imported: Missing parent ID or parent does not exist yet.' . wp_json_encode( $each_row ),
										'data'    => wp_json_encode( $each_row ),
									),
								);
								continue;
							}
							if ( $parent->is_type( 'variation' ) || in_array( $parent->get_type(), $custom_variation_typelist, true ) ) {
								$response[] = array(
									'id'    => null,
									'error' => array(
										'code'    => 422,
										'message' => 'Variation cannot be imported: Parent product cannot be a product variation.' . wp_json_encode( $each_row ),
										'data'    => wp_json_encode( $each_row ),
									),
								);
								continue;
							}
							if ( $parent ) {
								if ( isset( $each_row['attributes'] ) ) {
									$each_row['attributes'] = $this->reArrangeVariationAttributeData( $parent, $each_row['attributes'] );
								}
							}
							if (isset($each_row['replaceSkusWithIds']) && !!$each_row['replaceSkusWithIds']) {
								$this->replaceItemSkusWithIds($each_row);
								unset( $each_row['replaceSkusWithIds'] );
							}
							$_item = new WP_REST_Request( 'PUT' );
							$_item->set_body_params( $each_row );
							$variations_controler = new WC_REST_Product_Variations_Controller();
							$_response            = $variations_controler->create_item( $_item );
						} else {
							$response[] = array(
								'id'    => null,
								'error' => array(
									'code'    => 422,
									'message' => 'Invalid Data format, variant parent is missing ' . wp_json_encode( $each_row ),
									'data'    => wp_json_encode( $each_row ),
								),
							);
							continue;
						}
					} else {
						if ( 'grouped' === $each_row['type'] ) {
							if ( isset( $each_row['kit_line_item'] ) && $each_row['kit_line_item'] ) {
								$grouped_item_ids             = $this->getTheKitChildItemIds( $each_row['kit_line_item'] );
								$each_row['grouped_products'] = $grouped_item_ids;
							}
						}

						if ( isset( $each_row['attributes'] ) ) {
							if ( 'variable' === $each_row['type'] || in_array( $each_row['type'], $custom_variable_typelist, true ) ) {
								$each_row['attributes'] = $this->reArrangeParentAttributeData( $each_row['attributes'] );
							}
							$each_row['attributes'] = $this->reArrangeGlobalAttributeData( $each_row['attributes'] );
						}
						if (isset($each_row['replaceSkusWithIds']) && !!$each_row['replaceSkusWithIds']) {
						$this->replaceItemSkusWithIds($each_row);
						unset( $each_row['replaceSkusWithIds'] );
					}
						$_item = new WP_REST_Request( 'PUT' );
						$_item->set_body_params( $each_row );
						$products_controller = new WC_REST_Products_Controller();
						$_response           = $products_controller->create_item( $_item );
					}
				} else {
					$response[] = array(
						'id'    => null,
						'error' => array(
							'code'    => 422,
							'message' => 'Invalid Data format, product type is wrong/missing ' . wp_json_encode( $each_row ),
							'data'    => wp_json_encode( $each_row ),
						),
					);
					continue;
				}
				if ( is_wp_error( $_response ) ) {
					$response[] = array(
						'id'    => $_item['id'],
						'error' => array(
							'code'    => $_response->get_error_code(),
							'message' => $_response->get_error_message(),
							'data'    => $_response->get_error_data(),
						),
					);
				} else {
					$_response_data = $wp_rest_server->response_to_data( $_response, '' );
					if ( isset( $each_row['__taxonomy'] ) && !!$each_row['__taxonomy'] ) {
						$create_if_not_exists = false;
						if(isset($each_row['__taxonomy_create_if_not_exists'])) { $create_if_not_exists = $each_row['__taxonomy_create_if_not_exists'];}
						$this->setCustomTaxonomyInfo( $_response_data['id'], $each_row['__taxonomy'], $create_if_not_exists);
					}
					if(isset($each_row['__custom_field_key'],$each_row['__custom_field_rows']) && !!$each_row['__custom_field_key']) {
						$this->setAdvancedCustomFieldValues( $_response_data['id'], $each_row['__custom_field_key'], $each_row['__custom_field_rows']);
					}
					$response[] = $_response_data;
				}
			}
		}
		return $response;
	}
	
	/**
	 * Get the product Id based on the sku.
	 *
	 * @param String $sku passed to function to get the Id.
	 * @return post Id
	 */
	private function replaceItemSkusWithIds($each_row){
		if (isset($each_row['replaceSkusWithIds']) && !!$each_row['replaceSkusWithIds']) {
			$replaceSkusList = $each_row['replaceSkusWithIds'];
			$replaceSkusListArray = explode("," , $replaceSkusList);
			for($i=0;$i<count($replaceSkusListArray); $i++) {
				if(isset($each_row[$replaceSkusListArray[$i]]) && !!$each_row[$replaceSkusListArray[$i]]) {
					if(is_array($each_row[$replaceSkusListArray[$i]])) {
					   $skuArrayList = $each_row[$replaceSkusListArray[$i]];
					} else {
					   $skuArrayList = explode(",",$each_row[$replaceSkusListArray[$i]]);
					}
					$temp_ids = [];
					if(isset($skuArrayList) && count($skuArrayList)>0) {
						for($j=0;$j<count($skuArrayList); $j++) {
							$sku = $skuArrayList[$j];
							$productid = $this->getIdBySku($sku);
							$temp_ids[] = $productid;
						}
					}
					$each_row[$replaceSkusListArray[$i]] = $temp_ids;
				}
			}
		}
	}
	/**
	 * Get the product Id based on the sku.
	 *
	 * @param String $sku passed to function to get the Id.
	 * @return post Id
	 */
	private function getIdBySku( $sku ) {
		global $wpdb;
		$post_id       = '';
		$postmetatable = $wpdb->prefix . 'postmeta';
		$posttable     = $wpdb->prefix . 'posts';
		$results       = $wpdb->get_results( $wpdb->prepare( 'SELECT post_id from ' . $wpdb->prefix . 'postmeta as a INNER JOIN ' . $wpdb->prefix . 'posts as b ON a.post_id = b.id WHERE meta_key = %s AND meta_value=%s ORDER BY a.post_id DESC', array( '_sku', $sku ) ) );
		foreach ( $results as $key => $value ) {
			$post_id = $value->post_id;
			return $post_id;
		}
		return $post_id;
	}
	/**
	 * Check if product exists based on the id
	 *
	 * @param Integer $post_ID passed to check the Item with that product Exists.
	 * @return post Id|boolean
	 */
	private function checkIfProductExists( $post_ID = '' ) {
		if ( ! $post_ID ) {
			return false;
		}
		global $wpdb;
		$post_id   = false;
		$posttable = $wpdb->prefix . 'posts';
		$post_ID   = (int) $post_ID;
		$results   = $wpdb->get_results( $wpdb->prepare( 'SELECT ID FROM ' . $wpdb->prefix . "posts WHERE post_type IN ('product','product_variation') AND ID =%d", array( $post_ID ) ) );
		foreach ( $results as $key => $value ) {
			$post_id = $value->ID;
			return $post_id;
		}
		return $post_id;
	}
	/**
	 * Get the parent Id based on the parent Sku
	 *
	 * @param String $sku to get the variant parent Item based on Sku.
	 * @return parentId|boolean
	 */
	private function getParentIdBySku( $sku ) {
		global $wpdb;
		$post_id           = '';
		$postmetatable     = $wpdb->prefix . 'postmeta';
		$posttable         = $wpdb->prefix . 'posts';
		$results           = $wpdb->get_results( $wpdb->prepare( 'SELECT post_id from ' . $wpdb->prefix . 'postmeta as a INNER JOIN ' . $wpdb->prefix . 'posts' . " as b ON a.post_id = b.id WHERE meta_key = '_sku' AND meta_value=%s AND b.post_type IN ('product') ORDER BY a.post_id DESC", array( $sku ) ) );
		$result            = array();
		$result['success'] = false;
		if ( 1 === count( $results ) ) {
			$result['success'] = true;
			foreach ( $results as $key => $value ) {
				$post_id           = $value->post_id;
				$result['post_id'] = $post_id;
				return $result;
			}
		} elseif ( count( $results ) > 1 ) {
			$message = 'Multiple Items has same sku, ID List with same skus - ';
			foreach ( $results as $key => $value ) {
				$post_id  = $value->post_id;
				$message .= $post_id . ', ';
			}
			$message         = trim( $message, ',' );
			$result['error'] = $message;
		} elseif ( 0 === count( $results ) ) {
			$message         = 'No item with sku ' . $sku . ' exists in woo-commerce';
			$result['error'] = $message;
		}
		return $result;
	}
	/**
	 * Get variation parent attributes and set "is_variation".
	 *
	 * @param  array      $attributes Attributes list.
	 * @param  WC_Product $parent     Parent product data.
	 * @return array
	 */
	protected function get_variation_parent_attributes( $attributes, $parent ) {
		$parent_attributes = $parent->get_attributes();
		$require_save      = false;
		foreach ( $attributes as $attribute ) {
			$attribute_id = 0;
			// Get ID if is a global attribute.
			$attribute_id = $this->get_attribute_taxonomy_id( $attribute['name'] );
			if ( $attribute_id ) {
				$attribute_name = wc_attribute_taxonomy_name_by_id( $attribute_id );
			} else {
				$attribute_name = sanitize_title( $attribute['name'] );
			}
			// Check if attribute handle variations.
			if ( isset( $parent_attributes[ $attribute_name ] ) && ! $parent_attributes[ $attribute_name ]->get_variation() ) {
				// Re-create the attribute to CRUD save and generate again.
				$parent_attributes[ $attribute_name ] = clone $parent_attributes[ $attribute_name ];
				$parent_attributes[ $attribute_name ]->set_variation( 1 );
				$require_save = true;
			}
		}
		// Save variation attributes.
		if ( $require_save ) {
			$parent->set_attributes( array_values( $parent_attributes ) );
			$parent->save();
		}
		return $parent_attributes;
	}
	/**
	 * Get attribute taxonomy ID from the imported data.
	 * If does not exists register a new attribute.
	 *
	 * @param  string $raw_name Attribute name.
	 * @return int
	 * @throws Exception If taxonomy cannot be loaded.
	 */
	private function get_attribute_taxonomy_id( $raw_name ) {
		global $wpdb, $wc_product_attributes;
		// These are exported as labels, so convert the label to a name if possible first.
		$attribute_labels = wp_list_pluck( wc_get_attribute_taxonomies(), 'attribute_label', 'attribute_name' );
		$attribute_name   = array_search( $raw_name, $attribute_labels, true );

		if ( ! $attribute_name ) {
			$attribute_name = wc_sanitize_taxonomy_name( $raw_name );
		}

		$attribute_id = wc_attribute_taxonomy_id_by_name( $attribute_name );
		// Get the ID from the name.
		if ( $attribute_id ) {
			return $attribute_id;
		}
		// If the attribute does not exist, create it.
		$attribute_id = wc_create_attribute(
			array(
				'name'         => $raw_name,
				'slug'         => $attribute_name,
				'type'         => 'select',
				'order_by'     => 'menu_order',
				'has_archives' => false,
			)
		);
		if ( is_wp_error( $attribute_id ) ) {
			throw new Exception( $attribute_id->get_error_message(), 400 );
		}
		// Register as taxonomy while importing.
		$taxonomy_name = wc_attribute_taxonomy_name( $attribute_name );
		register_taxonomy(
			$taxonomy_name,
			apply_filters( 'woocommerce_taxonomy_objects_' . $taxonomy_name, array( 'product' ) ),
			apply_filters(
				'woocommerce_taxonomy_args_' . $taxonomy_name,
				array(
					'labels'       => array(
						'name' => $raw_name,
					),
					'hierarchical' => true,
					'show_ui'      => false,
					'query_var'    => true,
					'rewrite'      => false,
				)
			)
		);
		// Set product attributes global.
		$wc_product_attributes = array();
		foreach ( wc_get_attribute_taxonomies() as $taxonomy ) {
			$wc_product_attributes[ wc_attribute_taxonomy_name( $taxonomy->attribute_name ) ] = $taxonomy;
		}
		return $attribute_id;
	}
	/**
	 * Adjust the variant data.
	 *
	 * @param  Object $parent variable item.
	 * @param  Object $attribute_info variant attribute data.
	 * @return Object $attribute_info.
	 */
	private function reArrangeVariationAttributeData( $parent, $attribute_info ) {
		$attributes = array();
		foreach ( $attribute_info as $key => $attribute ) {
			if ( isset($attribute_info[ $key ]['id']) && "" !== $attribute_info[ $key ]['id'] ) {
				continue;
			} elseif ( $attribute_info[ $key ]['name'] ) {
				$attribute_id = 0;
				$attribute_id = $this->get_attribute_taxonomy_id( $attribute['name'] );
				if ( $attribute_id ) {
					$attribute_name               = wc_attribute_taxonomy_name_by_id( $attribute_id );
					$attribute_info[ $key ]['id'] = $attribute_id;
				} else {
					$attribute_name = sanitize_title( $attribute['name'] );
				}
				$term_list = array();
				foreach ( wc_get_product_terms( $parent->id, $attribute_name ) as $attribute_value ) {
					$term_list[] = $attribute_value->name;
				}
				$term_name = $attribute['option'];
				if ( ! in_array( $term_name, $term_list, true ) ) {
					wp_set_post_terms( $parent->id, $term_name, $attribute_name, true );
				}
			}
		}
		return $attribute_info;
	}
	/**
	 * Adjust the parent attribute data.
	 *
	 * @param  Object $attribute_info variant attribute data.
	 * @return Object $attribute_info.
	 */
	private function reArrangeParentAttributeData( $attribute_info ) {
		$attributes = array();
		foreach ( $attribute_info as $key => $attribute ) {
			if ( isset( $attribute_info[ $key ]['id'] ) && "" !== $attribute_info[ $key ]['id'] ) {
				continue;
			} elseif ( isset( $attribute_info[ $key ]['name'], $attribute_info[ $key ]['variation'] ) && $attribute_info[ $key ]['name'] && 1 === (int) $attribute_info[ $key ]['variation'] ) {
				$attribute_id = 0;
				$attribute_id = $this->get_attribute_taxonomy_id( $attribute['name'] );
				if ( $attribute_id ) {
					$attribute_info[ $key ]['id'] = $attribute_id;
				}
			}
		}
		return $attribute_info;
	}
	/**
	 * Adjust the global attribute data.
	 *
	 * @param  Object $attribute_info variant attribute data.
	 * @return Object $attribute_info.
	 */
	private function reArrangeGlobalAttributeData( $attribute_info ) {
		$attributes = array();
		foreach ( $attribute_info as $key => $attribute ) {
			if ( isset( $attribute_info[ $key ]['id'] ) && "" !== $attribute_info[ $key ]['id'] ) {
				continue;
			} elseif ( isset( $attribute_info[ $key ]['name'], $attribute_info[ $key ]['is_attribute'] ) && $attribute_info[ $key ]['name'] && 1 === (int) $attribute_info[ $key ]['is_attribute'] ) {
				unset( $attribute_info[ $key ]['is_attribute'] );
				$attribute_id = 0;
				$attribute_id = $this->get_attribute_taxonomy_id( $attribute['name'] );
				if ( $attribute_id ) {
					$attribute_info[ $key ]['id'] = $attribute_id;
				}
			}
		}
		return $attribute_info;
	}
	/**
	 * Update parent attributes with non-variant attribute data.
	 *
	 * @param  Object $product variable attribute data.
	 * @param  Object $attributes variable attribute data.
	 * @return Object $attributes.
	 */
	private function appendNonVariationParentAttributeData( $product, $attributes ) {
		$attribute_name_hash = array();
		$attribute_ids       = array();
		if ( $attributes && is_array( $attributes ) && count( $attributes ) > 0 ) {
			foreach ( $attributes as $key => $attribute ) {
				if ( ! ( isset( $attribute['variation'] ) && 1 === (int) $attribute['variation'] ) ) {
					if ( $attribute['name'] ) {
						$attribute_name_hash[ $attribute['name'] ] = true;
					}
				}
				if ( isset( $attribute['id'] ) && ! empty( $attribute['id'] ) ) {
					$attribute_id = (int) $attribute['id'];
					if ( 0 !== $attribute_id && ! ! $attribute_id && ! in_array( $attribute_id, $attribute_ids, true ) ) {
						$attribute_ids[] = $attribute_id;
					}
				}
			}
		}
		foreach ( $product->get_attributes() as $val ) {
			$data = $val->get_data();
			if ( 0 === (int) $data['is_variation'] && ! isset( $attribute_name_hash[ $data['name'] ] ) ) {
				$each_attribute_id = (int) $data['id'];
				if ( ! in_array( $each_attribute_id, $attribute_ids, true ) ) {
					if ( isset( $data['is_taxonomy'] ) && $data['is_taxonomy'] ) {
						$data['options'] = wc_get_product_terms( $product->id, $data['name'], array( 'fields' => 'names' ) );
					}
					$attributes[] = $data;
				}
			}
		}
		return $attributes;
	}
	/**
	 * Get the list of Unique Items for the group.
	 *
	 * @param  Object $line_items list of group items.
	 * @return Object $group_item_ids.
	 */
	private function getTheKitChildItemIds( $line_items ) {
		$group_item_ids = array();
		foreach ( $line_items as $key => $value ) {
			$sku        = '';
			$product_id = '';
			$sku        = $key;
			if ( isset( $value['wooitemid'] ) && $value['wooitemid'] ) {
				if ( $this->checkIfProductExists( $value['wooitemid'] ) ) {
					$product_id = $value['wooitemid'];
				}
			}
			if ( ! $product_id && $sku ) {
				$product_id = $this->getIdBySku( $sku );
			}
			if ( $product_id ) {
				$group_item_ids[] = $product_id;
			}
			$group_item_ids = array_unique( $group_item_ids );
		}
		return $group_item_ids;
	}
	/**
	 * Arrange the meta data format.
	 *
	 * @param  Object $meta_data meta data format.
	 * @return Object $finalResponse.
	 */
	private function reArrangeMetaData( $meta_data ) {
		$final_response = array();

		foreach ( $meta_data as $key => $value ) {
			if ( ! ! $value ) {
				if ( isset( $value[0] ) ) {
					$value_count = count( $value );
					for ( $i = 0; isset( $value[ $i ] ) && $i < $value_count; $i++ ) {
						$final_response[] = $value[ $i ];
					}
				} else {
					$final_response[] = $value;
				}
			}
		}

		$finalresponse_count = count( $final_response );
		for ( $i = 0; isset( $final_response[ $i ] ) && $i < $finalresponse_count; $i++ ) {
			if ( isset( $final_response[ $i ], $final_response[ $i ]['nova_process'] ) ) {
				if ( 'serialize' === $final_response[ $i ]['nova_process'] ) {
					unset( $final_response[ $i ]['nova_process'] );
					if ( isset( $final_response[ $i ]['value'] ) && !!$final_response[ $i ]['value'] ) {
						$final_response[ $i ]['value'] = serialize( $final_response[ $i ]['value'] );
					}
				}
			}
		}
		return $final_response;
	}
	/**
	 * Set Custom taxonomy Info to the product.
	 *
	 * @param  int   $product_id product Id.
	 * @param  array $taxonomy_data taxonomy data.
	 * @return boolean
	 */
	private function setCustomTaxonomyInfo( $product_id, $taxonomy_data, $create_if_not_exists = false) {
		// Can add this additional check if needed $product = wc_get_product( (int) $product_id ).
		if ( ! $product_id ) {
			return false;
		}
		try {
			foreach ( $taxonomy_data as $taxonomy => $options ) {
				if ( ! taxonomy_exists( $taxonomy ) ) {
					continue;
				}
				$option_id_list = array();
				if ( !!$options && ! is_array( $options ) ) {
					$options = array_map( 'trim', explode( ',', $options ) );
				}
				if ( !!$options && is_array( $options ) ) {
					$option_count = count( $options );
					for ( $i = 0; isset( $options[ $i ] ) && $i < $option_count; $i++ ) {
						$each_option = $options[ $i ];
						$term        = term_exists( $each_option, $taxonomy, $parent = null );
						if (($create_if_not_exists === "true" || $create_if_not_exists === true) && !!$each_option) {
							$option_id_list[] = $each_option;
						} elseif ( 0 !== $term && null !== $term && ! ! $term['term_id'] ) {
							$option_id_list[] = (int) $term['term_id'];
						} elseif ( is_numeric( $options[ $i ] ) ) {
							$term = term_exists( (int) $options[ $i ], $taxonomy, $parent = null );
							if ( 0 !== $term && null !== $term && ! ! $term['term_id'] ) {
								$option_id_list[] = (int) $term['term_id'];
							}
						}
					}
				}
				// not checking for the $option_id_list count,because no values found it should unset options on product.
				wp_set_post_terms( (int) $product_id, $option_id_list, $taxonomy );
			}
		} catch ( Exception $e ) {
			// incase of any error other actions will perform without any issue.
			return false;
		}
		return true;
	}
	/**
	 * Set Custom field values Info to the product.
	 *
	 * @param  int   $product_id product Id.
	 * @param  string  $field_key group field key data.
	 * @return array $rows list of rows
	 */
	private function setAdvancedCustomFieldValues( $product_id, $field_key, $rows=[]) {
		if(!!$field_key && !!$product_id && function_exists('update_field')) {
			update_field( $field_key, $rows, $product_id );
		}
	}
}
