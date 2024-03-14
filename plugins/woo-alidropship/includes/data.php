<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class VI_WOO_ALIDROPSHIP_DATA {
	private static $prefix;
	private $params;
	private $default;
	private static $countries;
	private static $states;
	private static $ali_states = array();
	protected static $instance = null;
	protected static $allow_html = null;
	protected static $is_ald_table = null;

	/**
	 * VI_WOO_ALIDROPSHIP_DATA constructor.
	 */
	public function __construct() {
		self::$prefix = 'vi-wad-';
		global $wooaliexpressdropship_settings;
		if ( ! $wooaliexpressdropship_settings ) {
			$wooaliexpressdropship_settings = get_option( 'wooaliexpressdropship_params', array() );
		}
		$this->default = array(
			'enable'                                => '1',
			'secret_key'                            => '',
			'product_status'                        => 'publish',
			'catalog_visibility'                    => 'visible',
			'product_gallery'                       => '1',
			'product_categories'                    => array(),
			'product_tags'                          => array(),
			'product_shipping_class'                => '',
			'product_description'                   => 'item_specifics_and_description',
			'variation_visible'                     => '',
			'manage_stock'                          => '1',
			'ignore_ship_from'                      => '',
			'price_from'                            => array( 0 ),
			'price_to'                              => array( '' ),
			'plus_value'                            => array( 200 ),
			'plus_sale_value'                       => array( - 1 ),
			'plus_value_type'                       => array( 'percent' ),
			'price_default'                         => array(
				'plus_value'      => 2,
				'plus_sale_value' => 1,
				'plus_value_type' => 'multiply',
			),
			'import_product_currency'               => 'USD',
			'import_currency_rate'                  => '1',
			'import_currency_rate_RUB'              => '',
			'fulfill_default_carrier'               => 'EMS_ZX_ZX_US',
			'fulfill_default_phone_number'          => '',
			'fulfill_default_phone_number_override' => '',
			'fulfill_default_phone_country'         => '',
			'fulfill_order_note'                    => 'I\'m dropshipping. Please DO NOT put any invoices, QR codes, promotions or your brand name logo in the shipments. Please ship as soon as possible for repeat business. Thank you!',
			'order_status_for_fulfill'              => array( 'wc-completed', 'wc-on-hold', 'wc-processing' ),
			'order_status_after_sync'               => 'wc-completed',
			'string_replace'                        => array(),
			'carrier_name_replaces'                 => array(
				'from_string' => array(),
				'to_string'   => array(),
				'sensitive'   => array(),
			),
			'carrier_url_replaces'                  => array(
				'from_string' => array(),
				'to_string'   => array(),
			),
			'disable_background_process'            => '',
			'simple_if_one_variation'               => '',
			'download_description_images'           => '',
			'show_shipping_option'                  => '1',
			'shipping_cost_after_price_rules'       => '1',
			'use_global_attributes'                 => '1',
			'format_price_rules_enable'             => '',
			'format_price_rules_test'               => 0,
			'format_price_rules'                    => array(),
			'override_hide'                         => 0,
			'override_keep_product'                 => 1,
			'override_title'                        => 0,
			'override_images'                       => 0,
			'override_description'                  => 0,
			'override_find_in_orders'               => 1,
			'delete_woo_product'                    => 1,
			'cpf_custom_meta_key'                   => '',
			'billing_number_meta_key'               => '',
			'shipping_number_meta_key'              => '',
			'billing_neighborhood_meta_key'         => '',
			'shipping_neighborhood_meta_key'        => '',
			'rut_meta_key'                          => '',
			'use_external_image'                    => '',
			'fulfill_billing_fields_in_latin'       => '',
			'ald_table'                             => '',
		);

		$this->params = apply_filters( 'wooaliexpressdropship_params', wp_parse_args( $wooaliexpressdropship_settings, $this->default ) );
	}

	public function get_params( $name = "" ) {
		if ( ! $name ) {
			return $this->params;
		} elseif ( isset( $this->params[ $name ] ) ) {
			return apply_filters( 'wooaliexpressdropship_params_' . $name, $this->params[ $name ] );
		} else {
			return false;
		}
	}

	public static function get_instance( $new = false ) {
		if ( $new || null === self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	public static function get_attribute_name_by_slug( $slug ) {
		return ucwords( str_replace( '-', ' ', $slug ) );
	}

	/**
	 * @param $url
	 *
	 * @return mixed
	 */
	public static function get_domain_from_url( $url ) {
		$url     = strtolower( $url );
		$url_arr = explode( '//', $url );
		if ( count( $url_arr ) > 1 ) {
			$url = str_replace( 'www.', '', $url_arr[1] );

		} else {
			$url = str_replace( 'www.', '', $url_arr[0] );
		}
		$url_arr = explode( '/', $url );
		$url     = $url_arr[0];

		return $url;
	}

	/**
	 * @param array $args
	 * @param bool $return_sku
	 *
	 * @return array
	 */
	public static function get_imported_products( $args = array(), $return_sku = false ) {
		$imported_products = array();
		$args              = wp_parse_args( $args, array(
			'post_type'      => 'vi_wad_draft_product',
			'posts_per_page' => - 1,
			'meta_key'       => '_vi_wad_sku',
			'post_status'    => array(
				'publish',
				'draft',
				'override'
			),
			'fields'         => 'ids'
		) );

//		$the_query = new WP_Query( $args );
		$the_query = VI_WOO_ALIDROPSHIP_DATA::is_ald_table() ? new Ali_Product_Query( $args ) : new WP_Query( $args );

		if ( $the_query->have_posts() ) {
			if ( $return_sku ) {
				foreach ( $the_query->posts as $product_id ) {
					$product_sku = Ali_Product_Table::get_post_meta( $product_id, '_vi_wad_sku', true );
					if ( $product_sku ) {
						$imported_products[] = $product_sku;
					}
				}
			} else {
				$imported_products = $the_query->posts;
			}
		}
		wp_reset_postdata();

		return $imported_products;
	}

	public static function product_get_woo_id_by_aliexpress_id( $aliexpress_id, $is_variation = false, $count = false, $multiple = false ) {
		global $wpdb;
		if ( $aliexpress_id ) {
			$table_posts    = "{$wpdb->prefix}posts";
			$table_postmeta = "{$wpdb->prefix}postmeta";
			if ( $is_variation ) {
				$post_type = 'product_variation';
				$meta_key  = '_vi_wad_aliexpress_variation_id';
			} else {
				$post_type = 'product';
				$meta_key  = '_vi_wad_aliexpress_product_id';
			}
			if ( $count ) {
				$query   = "SELECT count(*) from {$table_postmeta} join {$table_posts} on {$table_postmeta}.post_id={$table_posts}.ID where {$table_posts}.post_type = '{$post_type}' and {$table_posts}.post_status != 'trash' and {$table_postmeta}.meta_key = '{$meta_key}' and {$table_postmeta}.meta_value = %s";
				$results = $wpdb->get_var( $wpdb->prepare( $query, $aliexpress_id ) );
			} else {
				$query = "SELECT {$table_postmeta}.* from {$table_postmeta} join {$table_posts} on {$table_postmeta}.post_id={$table_posts}.ID where {$table_posts}.post_type = '{$post_type}' and {$table_posts}.post_status != 'trash' and {$table_postmeta}.meta_key = '{$meta_key}' and {$table_postmeta}.meta_value = %s";
				if ( $multiple ) {
					$results = $wpdb->get_results( $wpdb->prepare( $query, $aliexpress_id ), ARRAY_A );
				} else {
					$query   .= ' LIMIT 1';
					$results = $wpdb->get_var( $wpdb->prepare( $query, $aliexpress_id ), 1 );
				}
			}

			return $results;
		} else {
			return false;
		}
	}

	/**
	 * @param $product_id
	 * @param bool $count
	 * @param bool $multiple
	 * @param array $status
	 *
	 * @return array|bool|object|string|null
	 */
	public static function product_get_id_by_woo_id(
		$product_id, $count = false, $multiple = false, $status = array(
		'publish',
		'draft',
		'override'
	)
	) {
		global $wpdb;
		if ( $product_id ) {
			$table_posts    = "{$wpdb->prefix}posts";
			$table_postmeta = "{$wpdb->prefix}postmeta";
			$post_type      = 'vi_wad_draft_product';
			$meta_key       = '_vi_wad_woo_id';
			$post_status    = '';
			if ( $status ) {
				if ( is_array( $status ) ) {
					$status_count = count( $status );
					if ( $status_count === 1 ) {
						$post_status = " AND {$table_posts}.post_status='{$status[0]}' ";
					} elseif ( $status_count > 1 ) {
						$post_status = " AND {$table_posts}.post_status IN ('" . implode( "','", $status ) . "') ";
					}
				} else {
					$post_status = " AND {$table_posts}.post_status='{$status}' ";
				}
			}

			if ( $count ) {
				$query   = "SELECT count(*) from {$table_postmeta} join {$table_posts} on {$table_postmeta}.post_id={$table_posts}.ID where {$table_posts}.post_type = '{$post_type}'{$post_status}and {$table_postmeta}.meta_key = '{$meta_key}' and {$table_postmeta}.meta_value = %s";
				$results = $wpdb->get_var( $wpdb->prepare( $query, $product_id ) );
			} else {
				$query = "SELECT {$table_postmeta}.* from {$table_postmeta} join {$table_posts} on {$table_postmeta}.post_id={$table_posts}.ID where {$table_posts}.post_type = '{$post_type}'{$post_status}and {$table_postmeta}.meta_key = '{$meta_key}' and {$table_postmeta}.meta_value = %s";
				if ( $multiple ) {
					$results = $wpdb->get_results( $wpdb->prepare( $query, $product_id ), ARRAY_A );
				} else {
					$query   .= ' LIMIT 1';
					$results = $wpdb->get_var( $wpdb->prepare( $query, $product_id ), 1 );
				}
			}

			return $results;
		} else {
			return false;
		}
	}

	/**Get vi_wad_draft_product ID that will override $product_id
	 *
	 * @param $product_id
	 *
	 * @return bool|string|null
	 */
	public static function get_overriding_product( $product_id ) {
		global $wpdb;
		if ( $product_id ) {
			$table_posts = "{$wpdb->prefix}posts";
			$query       = "SELECT ID from {$table_posts} where {$table_posts}.post_type = 'vi_wad_draft_product' and {$table_posts}.post_status = 'override' and {$table_posts}.post_parent = %s LIMIT 1";

			return $wpdb->get_var( $wpdb->prepare( $query, $product_id ), 0 );
		} else {
			return false;
		}
	}

	/**
	 * @param $aliexpress_id
	 * @param array $post_status
	 * @param bool $count
	 * @param bool $multiple
	 *
	 * @return array|string|null
	 */
	public static function product_get_id_by_aliexpress_id( $aliexpress_id, $post_status = [ 'publish', 'draft', 'override' ], $count = false, $multiple = false ) {
		global $wpdb;
		$table_posts    = self::is_ald_table() ? $wpdb->ald_posts : "{$wpdb->prefix}posts";
		$table_postmeta = self::is_ald_table() ? $wpdb->ald_postmeta : "{$wpdb->prefix}postmeta";
		$post_id_column = self::is_ald_table() ? 'ald_post_id' : 'post_id';
		$post_type      = 'vi_wad_draft_product';
		$meta_key       = '_vi_wad_sku';
		$args           = array();
		$where          = array();
		if ( $post_status ) {
			if ( is_array( $post_status ) ) {
				if ( count( $post_status ) === 1 ) {
					$where[] = "{$table_posts}.post_status=%s";
					$args[]  = $post_status[0];
				} else {
					$where[] = "{$table_posts}.post_status IN (" . implode( ', ', array_fill( 0, count( $post_status ), '%s' ) ) . ")";
					foreach ( $post_status as $v ) {
						$args[] = $v;
					}
				}
			} else {
				$where[] = "{$table_posts}.post_status=%s";
				$args[]  = $post_status;
			}
		}
		if ( $aliexpress_id ) {
			$where[] = "{$table_postmeta}.meta_key = '{$meta_key}'";
			$where[] = "{$table_postmeta}.meta_value = %s";
			$args[]  = $aliexpress_id;
			if ( $count ) {
				$query   = "SELECT count(*) from {$table_postmeta} join {$table_posts} on {$table_postmeta}.{$post_id_column}={$table_posts}.ID where {$table_posts}.post_type = '{$post_type}'";
				$query   .= ' AND ' . implode( ' AND ', $where );
				$results = $wpdb->get_var( $wpdb->prepare( $query, $args ) );
			} else {
				$query = "SELECT {$table_postmeta}.* from {$table_postmeta} join {$table_posts} on {$table_postmeta}.{$post_id_column}={$table_posts}.ID where {$table_posts}.post_type = '{$post_type}'";
				$query .= ' AND ' . implode( ' AND ', $where );

				if ( $multiple ) {
					$results = $wpdb->get_col( $wpdb->prepare( $query, $args ), 1 );
				} else {
					$query   .= ' LIMIT 1';
					$results = $wpdb->get_var( $wpdb->prepare( $query, $args ), 1 );
				}
			}

		} else {
			$where[] = "{$table_postmeta}.meta_key = '{$meta_key}'";
			if ( $count ) {
				$query   = "SELECT count(*) from {$table_postmeta} join {$table_posts} on {$table_postmeta}.{$post_id_column}={$table_posts}.ID where {$table_posts}.post_type = '{$post_type}'";
				$query   .= ' AND ' . implode( ' AND ', $where );
				$results = $wpdb->get_var( count( $args ) ? $wpdb->prepare( $query, $args ) : $query );
			} else {
				$query   = "SELECT {$table_postmeta}.* from {$table_postmeta} join {$table_posts} on {$table_postmeta}.{$post_id_column}={$table_posts}.ID where {$table_posts}.post_type = '{$post_type}'";
				$query   .= ' AND ' . implode( ' AND ', $where );
				$results = $wpdb->get_col( count( $args ) ? $wpdb->prepare( $query, $args ) : $query, 1 );
			}
		}

		return $results;
	}

	/**
	 * @param $url
	 * @param array $args
	 * @param string $html
	 * @param bool $skip_ship_from_check
	 *
	 * @return array
	 */
	public static function get_data( $url, $args = array(), $html = '', $skip_ship_from_check = false ) {
		$response   = array(
			'status'  => 'success',
			'message' => '',
			'code'    => '',
			'data'    => array(),
		);
		$attributes = array(
			'sku' => '',
		);
		if ( ! $html ) {
			$args             = wp_parse_args( $args, array(
				'user-agent' => self::get_user_agent(),
				'timeout'    => 10,
			) );
			$request          = wp_remote_get( $url, $args );
			$response['code'] = wp_remote_retrieve_response_code( $request );
			if ( ! is_wp_error( $request ) ) {
				$html = $request['body'];
			} else {
				$response['status']  = 'error';
				$response['message'] = $request->get_error_messages();

				return $response;
			}
		}
		$productVariationMaps       = array();
		$listAttributes             = array();
		$listAttributesDisplayNames = array();
		$propertyValueNames         = array();
		$listAttributesNames        = array();
		$listAttributesSlug         = array();
		$listAttributesIds          = array();
		$variationImages            = array();
		$variations                 = array();
		$instance                   = self::get_instance();
		$ignore_ship_from           = $skip_ship_from_check ? false : $instance->get_params( 'ignore_ship_from' );
		$ignore_ship_from_default   = $instance->get_params( 'ignore_ship_from_default' );

		if ( is_array( $html ) ) {
			if ( ! empty( $html['ae_item_base_info_dto'] ) ) {
				/*Rebuild data from the new product API aliexpress.ds.product.get - since 1.0.10*/
				if ( ! empty( $html['ae_item_base_info_dto']['product_status_type'] ) && $html['ae_item_base_info_dto']['product_status_type'] === 'offline' ) {
					$response['status']  = 'error';
					$response['message'] = esc_html__( 'This product is no longer available', 'woo-alidropship' );

					return $response;
				}
				if ( ! empty( $html['ae_item_base_info_dto']['product_id'] ) ) {
					$attributes['sku'] = $html['ae_item_base_info_dto']['product_id'];
				}
				$attributes['gallery'] = $html['ae_multimedia_info_dto']['image_urls'] ? explode( ';', $html['ae_multimedia_info_dto']['image_urls'] ) : array();
				if ( isset( $html['ae_multimedia_info_dto']['ae_video_dtos'], $html['ae_multimedia_info_dto']['ae_video_dtos']['ae_video_d_t_o'] ) && $html['ae_multimedia_info_dto']['ae_video_dtos']['ae_video_d_t_o'] ) {
					$attributes['video'] = $html['ae_multimedia_info_dto']['ae_video_dtos']['ae_video_d_t_o'][0];
				}

				$skuModule = isset( $html['ae_item_sku_info_dtos'] ['ae_item_sku_info_d_t_o'] ) ? $html['ae_item_sku_info_dtos'] ['ae_item_sku_info_d_t_o'] : array();
				if ( count( $skuModule ) ) {
					$productSKUPropertyList = array();
					if ( ! empty( $skuModule[0]['ae_sku_property_dtos']['ae_sku_property_d_t_o'] ) ) {
						for ( $i = 0; $i < count( $skuModule[0]['ae_sku_property_dtos']['ae_sku_property_d_t_o'] ); $i ++ ) {
							$productSKUPropertyList[] = array(
								'id'     => $skuModule[0]['ae_sku_property_dtos']['ae_sku_property_d_t_o'][ $i ]['sku_property_id'],
								'values' => array(),
								'name'   => $skuModule[0]['ae_sku_property_dtos']['ae_sku_property_d_t_o'][ $i ]['sku_property_name'],
							);
						}
						for ( $i = 0; $i < count( $skuModule ); $i ++ ) {
							for ( $j = 0; $j < count( $productSKUPropertyList ); $j ++ ) {
								if ( ! in_array( $skuModule[ $i ]['ae_sku_property_dtos']['ae_sku_property_d_t_o'][ $j ]['property_value_id'], array_column( $productSKUPropertyList[ $j ]['values'], 'id' ) ) ) {
									$property_value = array(
										'id'        => isset( $skuModule[ $i ]['ae_sku_property_dtos']['ae_sku_property_d_t_o'][ $j ]['property_value_id'] ) ? $skuModule[ $i ]['ae_sku_property_dtos']['ae_sku_property_d_t_o'][ $j ]['property_value_id'] : '',
										'image'     => isset( $skuModule[ $i ]['ae_sku_property_dtos']['ae_sku_property_d_t_o'][ $j ]['sku_image'] ) ? str_replace( array(
											'ae02.alicdn.com',
											'ae03.alicdn.com',
											'ae04.alicdn.com',
											'ae05.alicdn.com',
										), 'ae01.alicdn.com', $skuModule[ $i ]['ae_sku_property_dtos']['ae_sku_property_d_t_o'][ $j ]['sku_image'] ) : '',
										'name'      => isset( $skuModule[ $i ]['ae_sku_property_dtos']['ae_sku_property_d_t_o'][ $j ]['sku_property_value'] ) ? $skuModule[ $i ]['ae_sku_property_dtos']['ae_sku_property_d_t_o'][ $j ]['sku_property_value'] : '',
										'ship_from' => '',
									);
									if ( ! empty( $skuModule[ $i ]['ae_sku_property_dtos']['ae_sku_property_d_t_o'][ $j ]['property_value_definition_name'] ) ) {
										$property_value['name'] = $skuModule[ $i ]['ae_sku_property_dtos']['ae_sku_property_d_t_o'][ $j ]['property_value_definition_name'];
									}
									$ship_from = self::property_value_id_to_ship_from( $skuModule[ $i ]['ae_sku_property_dtos']['ae_sku_property_d_t_o'][ $j ]['sku_property_id'], $property_value['id'] );
									if ( $ship_from ) {
										$property_value['ship_from'] = $ship_from;
									}
									$productSKUPropertyList[ $j ]['values'][] = $property_value;
								}
							}
						}
					}
					$ignore_ship_from_default_id = '';
					if ( count( $productSKUPropertyList ) ) {
						for ( $i = 0; $i < count( $productSKUPropertyList ); $i ++ ) {
							$images            = array();
							$skuPropertyValues = $productSKUPropertyList[ $i ]['values'];
							$attr_parent_id    = $productSKUPropertyList[ $i ]['id'];
							$skuPropertyName   = wc_sanitize_taxonomy_name( $productSKUPropertyList[ $i ]['name'] );
							if ( strtolower( $skuPropertyName ) === 'ships-from' && $ignore_ship_from ) {
								foreach ( $skuPropertyValues as $value ) {
									if ( isset( $value['ship_from'] ) && $value['ship_from'] === $ignore_ship_from_default ) {
										$ignore_ship_from_default_id = $value['id'];
									}
								}
								if ( $ignore_ship_from_default_id ) {
									continue;
								}
							} //point 1
							$attr = array(
								'values'   => array(),
								'slug'     => $skuPropertyName,
								'name'     => $productSKUPropertyList[ $i ]['name'],
								'position' => $i,
							);
							for ( $j = 0; $j < count( $skuPropertyValues ); $j ++ ) {
								$skuPropertyValue         = $skuPropertyValues[ $j ];
								$org_propertyValueId      = $skuPropertyValue['id'];
								$propertyValueId          = "{$attr_parent_id}:{$org_propertyValueId}";
								$propertyValueDisplayName = $skuPropertyValue['name'];
								if ( in_array( $propertyValueDisplayName, $listAttributesDisplayNames ) ) {
									$propertyValueDisplayName = "{$propertyValueDisplayName}-{$org_propertyValueId}";
								}
								$listAttributesNames[ $propertyValueId ]        = $skuPropertyName;
								$listAttributesDisplayNames[ $propertyValueId ] = $propertyValueDisplayName;
								$listAttributesIds[ $propertyValueId ]          = $attr_parent_id;
								$listAttributesSlug[ $propertyValueId ]         = $skuPropertyName;
								$attr['values'][ $propertyValueId ]             = $propertyValueDisplayName;
								$listAttributes[ $propertyValueId ]             = array(
									'name'      => $propertyValueDisplayName,
									'color'     => '',
									'image'     => '',
									'ship_from' => isset( $skuPropertyValue['ship_from'] ) ? $skuPropertyValue['ship_from'] : ''
								);
								if ( isset( $skuPropertyValue['image'] ) && $skuPropertyValue['image'] ) {
									$images[ $propertyValueId ]                  = $skuPropertyValue['image'];
									$variationImages[ $propertyValueId ]         = $skuPropertyValue['image'];
									$listAttributes[ $propertyValueId ]['image'] = $skuPropertyValue['image'];
								}
							}

							$attributes['list_attributes']               = $listAttributes;
							$attributes['list_attributes_names']         = $listAttributesNames;
							$attributes['list_attributes_ids']           = $listAttributesIds;
							$attributes['list_attributes_slugs']         = $listAttributesSlug;
							$attributes['variation_images']              = $variationImages;
							$attributes['attributes'][ $attr_parent_id ] = $attr;
							$attributes['images'][ $attr_parent_id ]     = $images;

							$attributes['parent'][ $attr_parent_id ] = $skuPropertyName;
						}
					}
					for ( $j = 0; $j < count( $skuModule ); $j ++ ) {
						$temp                  = array(
							'skuId'              => '',
							'skuAttr'            => ( isset( $skuModule[ $j ]['id'] ) && $skuModule[ $j ]['id'] !== '<none>' ) ? $skuModule[ $j ]['id'] : '',
							'skuPropIds'         => isset( $skuModule[ $j ]['ae_sku_property_dtos']['ae_sku_property_d_t_o'] ) ? array_column( $skuModule[ $j ]['ae_sku_property_dtos']['ae_sku_property_d_t_o'], 'property_value_id' ) : array(),
							'skuVal'             => array(
								'availQuantity'  => isset( $skuModule[ $j ]['sku_available_stock'] ) ? $skuModule[ $j ]['sku_available_stock'] : ( isset( $skuModule[ $j ]['ipm_sku_stock'] ) ? $skuModule[ $j ]['ipm_sku_stock'] : 0 ),
								'skuCalPrice'    => isset( $skuModule[ $j ]['sku_price'] ) ? $skuModule[ $j ]['sku_price'] : '',
								'actSkuCalPrice' => 0,
							),
							'image'              => '',
							'variation_ids'      => array(),
							'variation_ids_sub'  => array(),
							'variation_ids_slug' => array(),
							'ship_from'          => '',
							'currency_code'      => isset( $skuModule[ $j ]['currency_code'] ) ? $skuModule[ $j ]['currency_code'] : '',
						);
						$s_price               = isset( $skuModule[ $j ]['offer_sale_price'] ) ? self::string_to_float( $skuModule[ $j ]['offer_sale_price'] ) : 0;
						$offer_bulk_sale_price = isset( $skuModule[ $j ]['offer_bulk_sale_price'] ) ? self::string_to_float( $skuModule[ $j ]['offer_bulk_sale_price'] ) : 0;
						if ( $s_price > 0 && $offer_bulk_sale_price > $s_price ) {
							$s_price = $offer_bulk_sale_price;
						}
						$temp['skuVal']['actSkuCalPrice'] = $s_price;

						if ( $temp['skuPropIds'] ) {
							$temAttr        = array();
							$attrIds        = $temp['skuPropIds'];
							$parent_attrIds = explode( ';', $temp['skuAttr'] );

							if ( $ignore_ship_from_default_id && ! in_array( $ignore_ship_from_default_id, $attrIds ) && $ignore_ship_from ) {
								continue;
							}

							for ( $k = 0; $k < count( $attrIds ); $k ++ ) {
								$propertyValueId = explode( ':', $parent_attrIds[ $k ] )[0] . ':' . $attrIds[ $k ];
								if ( isset( $listAttributesDisplayNames[ $propertyValueId ] ) ) {
									$temAttr[ $attributes['list_attributes_slugs'][ $propertyValueId ] ] = $listAttributesDisplayNames[ $propertyValueId ];
									if ( ! empty( $attributes['variation_images'][ $propertyValueId ] ) ) {
										$temp['image'] = $attributes['variation_images'][ $propertyValueId ];
									}
								}
								if ( ! empty( $listAttributes[ $propertyValueId ]['ship_from'] ) ) {
									$temp['ship_from'] = $listAttributes[ $propertyValueId ]['ship_from'];
								}
							}
							$temp['variation_ids'] = $temAttr;
						}
						$variations [] = $temp;
					}
					$attributes['variations'] = $variations;
				}

				$attributes['description_url'] = '';
				$attributes['description']     = $html['ae_item_base_info_dto']['detail'];
				$attributes['specsModule']     = array();
				if ( isset( $html['ae_item_properties']['logistics_info_d_t_o'] ) && count( $html['ae_item_properties']['logistics_info_d_t_o'] ) ) {
					foreach ( $html['ae_item_properties']['logistics_info_d_t_o'] as $aeop_ae_product_property ) {
						if ( isset( $aeop_ae_product_property['attr_name'], $aeop_ae_product_property['attr_value'] ) ) {
							$attributes['specsModule'][] = array(
								'attrName'  => $aeop_ae_product_property['attr_name'],
								'attrValue' => $aeop_ae_product_property['attr_value'],
							);
						}
					}
				}
				$attributes['store_info']    = array(
					'name' => isset( $html['ae_store_info']['store_name'] ) ? $html['ae_store_info']['store_name'] : '',
					'url'  => '',
					'num'  => isset( $html['ae_store_info']['store_id'] ) ? $html['ae_store_info']['store_id'] : '',
				);
				$attributes['name']          = $html['ae_item_base_info_dto']['subject'];
				$attributes['currency_code'] = $html['ae_item_base_info_dto']['currency_code'];
			} elseif ( ! empty( $html['aeop_ae_product_s_k_us'] ) ) {
				/*Rebuild data from the old product API aliexpress.postproduct.redefining.findaeproductbyidfordropshipper*/
				if ( ( ! empty( $html['ws_offline_date'] ) && strtotime( $html['ws_offline_date'] ) < time() ) || ( ! empty( $html['product_status_type'] ) && $html['product_status_type'] === 'offline' ) ) {
					$response['status']  = 'error';
					$response['message'] = esc_html__( 'This product is no longer available', 'woo-alidropship' );

					return $response;
				}
				if ( ! empty( $html['product_id'] ) ) {
					$attributes['sku'] = $html['product_id'];
				}
				$attributes['gallery'] = $html['image_u_r_ls'] ? explode( ';', $html['image_u_r_ls'] ) : array();
				if ( isset( $html['aeop_a_e_multimedia'], $html['aeop_a_e_multimedia']['aeop_a_e_videos'], $html['aeop_a_e_multimedia']['aeop_a_e_videos']['aeop_ae_video'] ) && $html['aeop_a_e_multimedia']['aeop_a_e_videos']['aeop_ae_video'] ) {
					$attributes['video'] = $html['aeop_a_e_multimedia']['aeop_a_e_videos']['aeop_ae_video'][0];
				}
				$skuModule = isset( $html['aeop_ae_product_s_k_us'] ['aeop_ae_product_sku'] ) ? $html['aeop_ae_product_s_k_us'] ['aeop_ae_product_sku'] : array();
				if ( count( $skuModule ) ) {
					$productSKUPropertyList = array();
					if ( ! empty( $skuModule[0]['aeop_s_k_u_propertys']['aeop_sku_property'] ) ) {
						for ( $i = 0; $i < count( $skuModule[0]['aeop_s_k_u_propertys']['aeop_sku_property'] ); $i ++ ) {
							$productSKUPropertyList[] = array(
								'id'     => $skuModule[0]['aeop_s_k_u_propertys']['aeop_sku_property'][ $i ]['sku_property_id'],
								'values' => array(),
								'name'   => $skuModule[0]['aeop_s_k_u_propertys']['aeop_sku_property'][ $i ]['sku_property_name'],
							);
						}
						for ( $i = 0; $i < count( $skuModule ); $i ++ ) {
							for ( $j = 0; $j < count( $productSKUPropertyList ); $j ++ ) {
								if ( ! in_array( $skuModule[ $i ]['aeop_s_k_u_propertys']['aeop_sku_property'][ $j ]['property_value_id_long'], array_column( $productSKUPropertyList[ $j ]['values'], 'id' ) ) ) {
									$property_value = array(
										'id'        => isset( $skuModule[ $i ]['aeop_s_k_u_propertys']['aeop_sku_property'][ $j ]['property_value_id_long'] ) ? $skuModule[ $i ]['aeop_s_k_u_propertys']['aeop_sku_property'][ $j ]['property_value_id_long'] : '',
										'image'     => isset( $skuModule[ $i ]['aeop_s_k_u_propertys']['aeop_sku_property'][ $j ]['sku_image'] ) ? str_replace( array(
											'ae02.alicdn.com',
											'ae03.alicdn.com',
											'ae04.alicdn.com',
											'ae05.alicdn.com',
										), 'ae01.alicdn.com', $skuModule[ $i ]['aeop_s_k_u_propertys']['aeop_sku_property'][ $j ]['sku_image'] ) : '',
										'name'      => isset( $skuModule[ $i ]['aeop_s_k_u_propertys']['aeop_sku_property'][ $j ]['sku_property_value'] ) ? $skuModule[ $i ]['aeop_s_k_u_propertys']['aeop_sku_property'][ $j ]['sku_property_value'] : '',
										'ship_from' => '',
									);
									if ( ! empty( $skuModule[ $i ]['aeop_s_k_u_propertys']['aeop_sku_property'][ $j ]['property_value_definition_name'] ) ) {
										$property_value['name'] = $skuModule[ $i ]['aeop_s_k_u_propertys']['aeop_sku_property'][ $j ]['property_value_definition_name'];
									}
									$ship_from = self::property_value_id_to_ship_from( $skuModule[ $i ]['aeop_s_k_u_propertys']['aeop_sku_property'][ $j ]['sku_property_id'], $property_value['id'] );
									if ( $ship_from ) {
										$property_value['ship_from'] = $ship_from;
									}
									$productSKUPropertyList[ $j ]['values'][] = $property_value;
								}
							}
						}
					}
					$ignore_ship_from_default_id = '';
					if ( count( $productSKUPropertyList ) ) {
						for ( $i = 0; $i < count( $productSKUPropertyList ); $i ++ ) {
							$images            = array();
							$skuPropertyValues = $productSKUPropertyList[ $i ]['values'];
							$attr_parent_id    = $productSKUPropertyList[ $i ]['id'];
							$skuPropertyName   = wc_sanitize_taxonomy_name( $productSKUPropertyList[ $i ]['name'] );
							if ( strtolower( $skuPropertyName ) === 'ships-from' && $ignore_ship_from ) {
								foreach ( $skuPropertyValues as $value ) {
									if ( isset( $value['ship_from'] ) && $value['ship_from'] === $ignore_ship_from_default ) {
										$ignore_ship_from_default_id = $value['id'];
									}
								}
								if ( $ignore_ship_from_default_id ) {
									continue;
								}
							} //point 1
							$attr = array(
								'values'   => array(),
								'slug'     => $skuPropertyName,
								'name'     => $productSKUPropertyList[ $i ]['name'],
								'position' => $i,
							);
							for ( $j = 0; $j < count( $skuPropertyValues ); $j ++ ) {
								$skuPropertyValue         = $skuPropertyValues[ $j ];
								$org_propertyValueId      = $skuPropertyValue['id'];
								$propertyValueId          = "{$attr_parent_id}:{$org_propertyValueId}";
								$propertyValueDisplayName = $skuPropertyValue['name'];
								if ( in_array( $propertyValueDisplayName, $listAttributesDisplayNames ) ) {
									$propertyValueDisplayName = "{$propertyValueDisplayName}-{$org_propertyValueId}";
								}
								$listAttributesNames[ $propertyValueId ]        = $skuPropertyName;
								$listAttributesDisplayNames[ $propertyValueId ] = $propertyValueDisplayName;
								$listAttributesIds[ $propertyValueId ]          = $attr_parent_id;
								$listAttributesSlug[ $propertyValueId ]         = $skuPropertyName;
								$attr['values'][ $propertyValueId ]             = $propertyValueDisplayName;
								$listAttributes[ $propertyValueId ]             = array(
									'name'      => $propertyValueDisplayName,
									'color'     => '',
									'image'     => '',
									'ship_from' => isset( $skuPropertyValue['ship_from'] ) ? $skuPropertyValue['ship_from'] : ''
								);
								if ( isset( $skuPropertyValue['image'] ) && $skuPropertyValue['image'] ) {
									$images[ $propertyValueId ]                  = $skuPropertyValue['image'];
									$variationImages[ $propertyValueId ]         = $skuPropertyValue['image'];
									$listAttributes[ $propertyValueId ]['image'] = $skuPropertyValue['image'];
								}
							}

							$attributes['list_attributes']               = $listAttributes;
							$attributes['list_attributes_names']         = $listAttributesNames;
							$attributes['list_attributes_ids']           = $listAttributesIds;
							$attributes['list_attributes_slugs']         = $listAttributesSlug;
							$attributes['variation_images']              = $variationImages;
							$attributes['attributes'][ $attr_parent_id ] = $attr;
							$attributes['images'][ $attr_parent_id ]     = $images;

							$attributes['parent'][ $attr_parent_id ] = $skuPropertyName;
						}
					}
					for ( $j = 0; $j < count( $skuModule ); $j ++ ) {
						$temp                  = array(
							'skuId'              => '',
							'skuAttr'            => ( isset( $skuModule[ $j ]['id'] ) && $skuModule[ $j ]['id'] !== '<none>' ) ? $skuModule[ $j ]['id'] : '',
							'skuPropIds'         => isset( $skuModule[ $j ]['aeop_s_k_u_propertys']['aeop_sku_property'] ) ? array_column( $skuModule[ $j ]['aeop_s_k_u_propertys']['aeop_sku_property'], 'property_value_id_long' ) : array(),
							'skuVal'             => array(
								'availQuantity'  => isset( $skuModule[ $j ]['s_k_u_available_stock'] ) ? $skuModule[ $j ]['s_k_u_available_stock'] : ( isset( $skuModule[ $j ]['ipm_sku_stock'] ) ? $skuModule[ $j ]['ipm_sku_stock'] : 0 ),
								'skuCalPrice'    => isset( $skuModule[ $j ]['sku_price'] ) ? $skuModule[ $j ]['sku_price'] : '',
								'actSkuCalPrice' => 0,
							),
							'image'              => '',
							'variation_ids'      => array(),
							'variation_ids_sub'  => array(),
							'variation_ids_slug' => array(),
							'ship_from'          => '',
							'currency_code'      => isset( $skuModule[ $j ]['currency_code'] ) ? $skuModule[ $j ]['currency_code'] : '',
						);
						$s_price               = isset( $skuModule[ $j ]['offer_sale_price'] ) ? self::string_to_float( $skuModule[ $j ]['offer_sale_price'] ) : 0;
						$offer_bulk_sale_price = isset( $skuModule[ $j ]['offer_bulk_sale_price'] ) ? self::string_to_float( $skuModule[ $j ]['offer_bulk_sale_price'] ) : 0;
						if ( $s_price > 0 && $offer_bulk_sale_price > $s_price ) {
							$s_price = $offer_bulk_sale_price;
						}
						$temp['skuVal']['actSkuCalPrice'] = $s_price;

						if ( $temp['skuPropIds'] ) {
							$temAttr        = array();
							$attrIds        = $temp['skuPropIds'];
							$parent_attrIds = explode( ';', $temp['skuAttr'] );

							if ( $ignore_ship_from_default_id && ! in_array( $ignore_ship_from_default_id, $attrIds ) && $ignore_ship_from ) {
								continue;
							}

							for ( $k = 0; $k < count( $attrIds ); $k ++ ) {
								$propertyValueId = explode( ':', $parent_attrIds[ $k ] )[0] . ':' . $attrIds[ $k ];
								if ( isset( $listAttributesDisplayNames[ $propertyValueId ] ) ) {
									$temAttr[ $attributes['list_attributes_slugs'][ $propertyValueId ] ] = $listAttributesDisplayNames[ $propertyValueId ];
									if ( ! empty( $attributes['variation_images'][ $propertyValueId ] ) ) {
										$temp['image'] = $attributes['variation_images'][ $propertyValueId ];
									}
								}
								if ( ! empty( $listAttributes[ $propertyValueId ]['ship_from'] ) ) {
									$temp['ship_from'] = $listAttributes[ $propertyValueId ]['ship_from'];
								}
							}
							$temp['variation_ids'] = $temAttr;
						}
						$variations [] = $temp;
					}
					$attributes['variations'] = $variations;
				}

				$attributes['description_url'] = '';
				$attributes['description']     = $html['detail'];
				$attributes['specsModule']     = array();
				if ( isset( $html['aeop_ae_product_propertys']['aeop_ae_product_property'] ) && count( $html['aeop_ae_product_propertys']['aeop_ae_product_property'] ) ) {
					foreach ( $html['aeop_ae_product_propertys']['aeop_ae_product_property'] as $aeop_ae_product_property ) {
						if ( isset( $aeop_ae_product_property['attr_name'], $aeop_ae_product_property['attr_value'] ) ) {
							$attributes['specsModule'][] = array(
								'attrName'  => $aeop_ae_product_property['attr_name'],
								'attrValue' => $aeop_ae_product_property['attr_value'],
							);
						}
					}
				}
				$attributes['store_info']    = array(
					'name' => isset( $html['store_info']['store_name'] ) ? $html['store_info']['store_name'] : '',
					'url'  => '',
					'num'  => isset( $html['store_info']['store_id'] ) ? $html['store_info']['store_id'] : '',
				);
				$attributes['name']          = $html['subject'];
				$attributes['currency_code'] = $html['currency_code'];
			}
		} else {
			/*Data passed from chrome extension in JSON format*/
			$ali_product_data = vi_wad_json_decode( $html );

			if ( json_last_error() ) {
				/*Data crawled directly with PHP is string. Find needed data in JSON then convert to array*/
				preg_match( '/{"actionModule".+}}/im', $html, $match_html );
				if ( count( $match_html ) === 1 && $match_html[0] ) {
					$html             = $match_html[0];
					$ali_product_data = vi_wad_json_decode( $html );
				} else {
					preg_match( '/{"widgets".+}}/im', $html, $match_html );
					if ( count( $match_html ) === 1 && $match_html[0] ) {
						if ( class_exists( 'DOMDocument' ) ) {
							$document = new DOMDocument();
							$document->loadHTML( $html );
							$ae_data = $document->getElementById( '__AER_DATA__' );
							if ( $ae_data ) {
								$ali_product_data = $ae_data->textContent;
							}
						}
						if ( ! $ali_product_data ) {
							$html             = preg_replace( '/<\/script>.+}}/im', '', $match_html[0] );
							$ali_product_data = vi_wad_json_decode( $html );
						}
					} else {
						preg_match( '/_init_data_= { data: .+}/im', $html, $match_html );
						if ( count( $match_html ) === 1 && $match_html[0] ) {
							$html             = '{ "data"' . substr( $match_html[0], 19 );
							$html             = preg_replace( '/<\/script>.+}}/im', '', $html );
							$ali_product_data = vi_wad_json_decode( $html );
						} else {
							preg_match( '/{"tradeComponent".+}}/im', $html, $match_html );

							if ( ! empty( $match_html[0] ) ) {
								$html             = $match_html[0];
								$ali_product_data = vi_wad_json_decode( $html );
							}
						}
					}
				}
			}
			if ( is_array( $ali_product_data ) && count( $ali_product_data ) ) {

				if ( isset( $ali_product_data['actionModule'] ) ) {
					$actionModule                      = isset( $ali_product_data['actionModule'] ) ? $ali_product_data['actionModule'] : array();
					$descriptionModule                 = isset( $ali_product_data['descriptionModule'] ) ? $ali_product_data['descriptionModule'] : array();
					$storeModule                       = isset( $ali_product_data['storeModule'] ) ? $ali_product_data['storeModule'] : array();
					$imageModule                       = isset( $ali_product_data['imageModule'] ) ? $ali_product_data['imageModule'] : array();
					$skuModule                         = isset( $ali_product_data['skuModule'] ) ? $ali_product_data['skuModule'] : array();
					$titleModule                       = isset( $ali_product_data['titleModule'] ) ? $ali_product_data['titleModule'] : array();
					$webEnv                            = isset( $ali_product_data['webEnv'] ) ? $ali_product_data['webEnv'] : array();
					$commonModule                      = isset( $ali_product_data['commonModule'] ) ? $ali_product_data['commonModule'] : array();
					$specsModule                       = isset( $ali_product_data['specsModule'] ) ? $ali_product_data['specsModule'] : array();
					$priceModule                       = isset( $ali_product_data['priceModule'] ) ? $ali_product_data['priceModule'] : array();
					$shippingModule                    = isset( $ali_product_data['shippingModule'] ) ? $ali_product_data['shippingModule'] : array();
					$attributes['currency_code']       = isset( $webEnv['currency'] ) ? $webEnv['currency'] : '';
					$attributes['trade_currency_code'] = isset( $commonModule['tradeCurrencyCode'] ) ? $commonModule['tradeCurrencyCode'] : '';
					if ( ! self::is_currency_supported( $attributes['currency_code'] ) && $attributes['trade_currency_code'] && ! self::is_currency_supported( $attributes['trade_currency_code'] ) ) {
						$response['status'] = 'error';
						$response['code']   = 'currency_not_supported';
						if ( 'RUB' === $attributes['currency_code'] ) {
							$response['message'] = esc_html__( 'Please configure RUB/USD rate in the plugin settings/Product price', 'woo-alidropship' );
						} else {
							$response['message'] = esc_html__( 'Please switch AliExpress currency to USD', 'woo-alidropship' );
						}

						return $response;
					}
					if ( ! empty( $actionModule['productId'] ) ) {
						$attributes['sku'] = $actionModule['productId'];
					} elseif ( ! empty( $descriptionModule['productId'] ) ) {
						$attributes['sku'] = $descriptionModule['productId'];
					}
					if ( isset( $actionModule['itemStatus'] ) && intval( $actionModule['itemStatus'] ) > 0 ) {
						$response['status']  = 'error';
						$response['message'] = esc_html__( 'This product is no longer available', 'woo-alidropship' );

						return $response;
					}
					$attributes['description_url'] = isset( $descriptionModule['descriptionUrl'] ) ? $descriptionModule['descriptionUrl'] : '';
					$attributes['specsModule']     = isset( $specsModule['props'] ) ? $specsModule['props'] : array();
					$attributes['store_info']      = array(
						'name' => $storeModule['storeName'],
						'url'  => $storeModule['storeURL'],
						'num'  => $storeModule['storeNum'],
					);
					$attributes['gallery']         = isset( $imageModule['imagePathList'] ) ? $imageModule['imagePathList'] : array();
					if ( ! empty( $imageModule['videoId'] ) && ! empty( $imageModule['videoUid'] ) ) {
						$attributes['video'] = array(
							'ali_member_id' => $imageModule['videoUid'],
							'media_id'      => $imageModule['videoId'],
							'media_type'    => '',
							'poster_url'    => '',
						);
					}
					self::handle_sku_module( $skuModule, $ignore_ship_from, $attributes );
					$attributes['name'] = isset( $titleModule['subject'] ) ? $titleModule['subject'] : '';

				} elseif ( isset( $ali_product_data['widgets'] ) ) {
					$widgets = $ali_product_data['widgets'];

					if ( is_array( $widgets ) && count( $widgets ) ) {
						$props = array();
						$is_ru = false;

						foreach ( $widgets as $widget ) {
							if ( ! empty( $widget['props'] ) && ! empty( $widget['props']['id'] ) ) {
								if ( isset( $widget['props']['quantity']['activity'] ) ) {
									$attributes['currency_code'] = self::aliexpress_ru_get_currency( $widgets );
									if ( isset( $widget['props']['itemStatus'] ) && $widget['props']['itemStatus'] == 2 ) {
										$response['status']  = 'error';
										$response['message'] = esc_html__( 'This product is no longer available', 'woo-alidropship' );

										return $response;
									} else {
										$props                     = $widget['props'];
										$attributes['description'] = self::aliexpress_ru_get_description( $widgets );
										$attributes['specsModule'] = self::aliexpress_ru_get_specs_module( $widgets );
										$attributes['store_info']  = array( 'name' => '', 'url' => '', 'num' => '', );
										$store_info                = self::aliexpress_ru_get_store_info( $widgets );

										if ( $store_info ) {
											$attributes['store_info']['name'] = isset( $store_info['name'] ) ? $store_info['name'] : '';
											$attributes['store_info']['url']  = isset( $store_info['url'] ) ? $store_info['url'] : '';
											$attributes['store_info']['num']  = isset( $store_info['storeNum'] ) ? $store_info['storeNum'] : '';
										}
									}
								} else {
									$attributes['currency_code'] = isset( $widget['children'][3]['props']['localization']['currencyProps']['selected']['currencyType'] ) ? $widget['children'][3]['props']['localization']['currencyProps']['selected']['currencyType'] : '';
									if ( isset( $widget['children'] ) && is_array( $widget['children'] ) ) {
										if ( count( $widget['children'] ) > 7 ) {
											if ( isset( $widget['children'][7]['children'] ) && is_array( $widget['children'][7]['children'] ) && count( $widget['children'][7]['children'] ) ) {
												$children = $widget['children'][7]['children'];
												if ( isset( $children[0]['props'] ) && is_array( $children[0]['props'] ) && count( $children[0]['props'] ) ) {
													$props = $children[0]['props'];
												}
												$attributes['description'] = isset( $widget['children'][10]['children'][1]['children'][1]['children'][0]['children'][0]['props']['html'] ) ? $widget['children'][10]['children'][1]['children'][1]['children'][0]['children'][0]['props']['html'] : '';
												$attributes['specsModule'] = isset( $widget['children'][10]['children'][1]['children'][1]['children'][2]['children'][0]['props']['char'] ) ? $widget['children'][10]['children'][1]['children'][1]['children'][2]['children'][0]['props']['char'] : array();
												$attributes['store_info']  = array(
													'name' => isset( $widget['children'][4]['props']['shop']['name'] ) ? $widget['children'][4]['props']['shop']['name'] : '',
													'url'  => isset( $widget['children'][4]['props']['shop']['url'] ) ? $widget['children'][4]['props']['shop']['url'] : '',
													'num'  => isset( $widget['children'][4]['props']['shop']['storeNum'] ) ? $widget['children'][4]['props']['shop']['storeNum'] : '',
												);
											}
										} else {
											$response['status']  = 'error';
											$response['message'] = esc_html__( 'This product is no longer available', 'woo-alidropship' );
										}
									}
								}
								break;
							}
						}

						if ( ! isset( $attributes['currency_code'] ) ) {
							$props = self::aliexpress_ru_get_data( $widgets );
							if ( $props ) {
								$attributes['currency_code'] = 'RUB';
								$attributes['description']   = self::aliexpress_ru_get_description( $widgets );
								$attributes['specsModule']   = array();
								$attributes['store_info']    = array(
									'name' => '',
									'url'  => isset( $props['storeUrl'] ) ? $props['storeUrl'] : '',
									'num'  => isset( $props['sellerId'] ) ? $props['sellerId'] : '',
								);
								if ( $attributes['store_info']['num'] ) {
									$attributes['store_info']['name'] = self::aliexpress_ru_get_store_name( $widgets, $attributes['store_info']['num'] );
								}
								$is_ru = true;
							}
						}

						if ( ! self::is_currency_supported( $attributes['currency_code'] ) ) {
							$response['status'] = 'error';
							$response['code']   = 'currency_not_supported';
							if ( 'RUB' === $attributes['currency_code'] ) {
								$response['message'] = esc_html__( 'Please configure RUB/USD rate in the plugin settings/Product price', 'woo-alidropship' );
							} else {
								$response['message'] = esc_html__( 'Please switch AliExpress currency to USD', 'woo-alidropship' );
							}

							return $response;
						}

						if ( count( $props ) ) {
							if ( ! empty( $props['id'] ) ) {
								$attributes['sku'] = $props['id'];
							}
							$attributes['gallery'] = array();
							if ( isset( $props['gallery'] ) && is_array( $props['gallery'] ) && count( $props['gallery'] ) ) {
								foreach ( $props['gallery'] as $gallery ) {
									if ( empty( $gallery['videoUrl'] ) ) {
										if ( ! empty( $gallery['imageUrl'] ) ) {
											$attributes['gallery'][] = $gallery['imageUrl'];
										}
									} else {
										preg_match( '/cloud.video.taobao.com\/play\/u\/(.*)\/p\/1\/e\/6\/t\/10301\//', $gallery['videoUrl'], $member_id_match );
										preg_match( '/\/p\/1\/e\/6\/t\/10301\/(.*).mp4/', $gallery['videoUrl'], $media_id_match );
										if ( $member_id_match && $media_id_match ) {
											$attributes['video'] = array(
												'ali_member_id' => $member_id_match[1],
												'media_id'      => $media_id_match[1],
												'media_type'    => '',
												'poster_url'    => empty( $gallery['imageUrl'] ) ? '' : $gallery['imageUrl'],
											);
										}
									}
								}
							}
							$skuModule = isset( $props['skuInfo'] ) ? $props['skuInfo'] : array();
							if ( count( $skuModule ) ) {
								$productSKUPropertyList      = isset( $skuModule['propertyList'] ) ? $skuModule['propertyList'] : array();
								$ignore_ship_from_default_id = '';
								if ( is_array( $productSKUPropertyList ) && count( $productSKUPropertyList ) ) {
									for ( $i = 0; $i < count( $productSKUPropertyList ); $i ++ ) {
										$images            = array();
										$skuPropertyValues = $productSKUPropertyList[ $i ]['values'];
										$attr_parent_id    = $productSKUPropertyList[ $i ]['id'];
										$skuPropertyName   = wc_sanitize_taxonomy_name( $productSKUPropertyList[ $i ]['name'] );
										if ( strtolower( $skuPropertyName ) === 'ships-from' && $ignore_ship_from ) {
											foreach ( $skuPropertyValues as $value ) {
												if ( isset( $value['skuPropertySendGoodsCountryCode'] ) && $value['skuPropertySendGoodsCountryCode'] === $ignore_ship_from_default ) {
													$ignore_ship_from_default_id = $value['id'];
												}
											}
											if ( $ignore_ship_from_default_id ) {
												continue;
											}
										} //point 1
										$attr = array(
											'values'   => array(),
											'slug'     => $skuPropertyName,
											'name'     => $productSKUPropertyList[ $i ]['name'],
											'position' => $i,
										);

										if ( $is_ru ) {
											for ( $j = 0; $j < count( $skuPropertyValues ); $j ++ ) {
												$skuPropertyValue = $skuPropertyValues[ $j ];
												$propertyValueId  = $skuPropertyValue['id'];
//												$propertyValueId          = "{$attr_parent_id}:{$org_propertyValueId}";
												$propertyValueName        = $skuPropertyValue['name'];
												$propertyValueDisplayName = $skuPropertyValue['displayName'];
												if ( in_array( $propertyValueDisplayName, $listAttributesDisplayNames ) ) {
//													$propertyValueDisplayName = "{$propertyValueDisplayName}-{$org_propertyValueId}";
												}
												if ( in_array( $propertyValueName, $propertyValueNames ) ) {
//													$propertyValueName = "{$propertyValueName}-{$org_propertyValueId}";
												}
												$listAttributesNames[ $propertyValueId ]        = $skuPropertyName;
												$listAttributesDisplayNames[ $propertyValueId ] = $propertyValueDisplayName;
												$propertyValueNames[ $propertyValueId ]         = $propertyValueName;
												$listAttributesIds[ $propertyValueId ]          = $attr_parent_id;
												$listAttributesSlug[ $propertyValueId ]         = $skuPropertyName;
												$attr['values'][ $propertyValueId ]             = $propertyValueDisplayName;
												$attr['values_sub'][ $propertyValueId ]         = $propertyValueName;
												$listAttributes[ $propertyValueId ]             = array(
													'name'      => $propertyValueDisplayName,
													'name_sub'  => $propertyValueName,
													'color'     => isset( $skuPropertyValue['colorValue'] ) ? $skuPropertyValue['colorValue'] : '',
													'image'     => '',
													'ship_from' => isset( $skuPropertyValue['skuPropertySendGoodsCountryCode'] ) ? $skuPropertyValue['skuPropertySendGoodsCountryCode'] : ''
												);
												if ( isset( $skuPropertyValue['imageMainUrl'] ) && $skuPropertyValue['imageMainUrl'] ) {
													$images[ $propertyValueId ]                  = $skuPropertyValue['imageMainUrl'];
													$variationImages[ $propertyValueId ]         = $skuPropertyValue['imageMainUrl'];
													$listAttributes[ $propertyValueId ]['image'] = $skuPropertyValue['imageMainUrl'];
												}
											}
										} else {
											for ( $j = 0; $j < count( $skuPropertyValues ); $j ++ ) {
												$skuPropertyValue         = $skuPropertyValues[ $j ];
												$org_propertyValueId      = $skuPropertyValue['id'];
												$propertyValueId          = "{$attr_parent_id}:{$org_propertyValueId}";
												$propertyValueName        = $skuPropertyValue['name'];
												$propertyValueDisplayName = $skuPropertyValue['displayName'];
												if ( in_array( $propertyValueDisplayName, $listAttributesDisplayNames ) ) {
													$propertyValueDisplayName = "{$propertyValueDisplayName}-{$org_propertyValueId}";
												}
												if ( in_array( $propertyValueName, $propertyValueNames ) ) {
													$propertyValueName = "{$propertyValueName}-{$org_propertyValueId}";
												}
												$listAttributesNames[ $propertyValueId ]        = $skuPropertyName;
												$listAttributesDisplayNames[ $propertyValueId ] = $propertyValueDisplayName;
												$propertyValueNames[ $propertyValueId ]         = $propertyValueName;
												$listAttributesIds[ $propertyValueId ]          = $attr_parent_id;
												$listAttributesSlug[ $propertyValueId ]         = $skuPropertyName;
												$attr['values'][ $propertyValueId ]             = $propertyValueDisplayName;
												$attr['values_sub'][ $propertyValueId ]         = $propertyValueName;
												$listAttributes[ $propertyValueId ]             = array(
													'name'      => $propertyValueDisplayName,
													'name_sub'  => $propertyValueName,
													'color'     => isset( $skuPropertyValue['colorValue'] ) ? $skuPropertyValue['colorValue'] : '',
													'image'     => '',
													'ship_from' => isset( $skuPropertyValue['skuPropertySendGoodsCountryCode'] ) ? $skuPropertyValue['skuPropertySendGoodsCountryCode'] : ''
												);
												if ( isset( $skuPropertyValue['imageMainUrl'] ) && $skuPropertyValue['imageMainUrl'] ) {
													$images[ $propertyValueId ]                  = $skuPropertyValue['imageMainUrl'];
													$variationImages[ $propertyValueId ]         = $skuPropertyValue['imageMainUrl'];
													$listAttributes[ $propertyValueId ]['image'] = $skuPropertyValue['imageMainUrl'];
												}
											}
										}


										$attributes['list_attributes']               = $listAttributes;
										$attributes['list_attributes_names']         = $listAttributesNames;
										$attributes['list_attributes_ids']           = $listAttributesIds;
										$attributes['list_attributes_slugs']         = $listAttributesSlug;
										$attributes['variation_images']              = $variationImages;
										$attributes['attributes'][ $attr_parent_id ] = $attr;
										$attributes['images'][ $attr_parent_id ]     = $images;

										$attributes['parent'][ $attr_parent_id ] = $skuPropertyName;
									}
								}

								$skuPriceList = isset( $skuModule['priceList'] ) ? $skuModule['priceList'] : array();
								for ( $j = 0; $j < count( $skuPriceList ); $j ++ ) {
									$temp = array(
										'skuId'              => isset( $skuPriceList[ $j ]['skuIdStr'] ) ? strval( $skuPriceList[ $j ]['skuIdStr'] ) : strval( $skuPriceList[ $j ]['skuId'] ),
										'skuAttr'            => isset( $skuPriceList[ $j ]['skuAttr'] ) ? $skuPriceList[ $j ]['skuAttr'] : '',
										'skuPropIds'         => isset( $skuPriceList[ $j ]['skuPropIds'] ) ? $skuPriceList[ $j ]['skuPropIds'] : '',
										'skuVal'             => array(
											'availQuantity'  => isset( $skuPriceList[ $j ]['availQuantity'] ) ? $skuPriceList[ $j ]['availQuantity'] : 0,
											'actSkuCalPrice' => isset( $skuPriceList[ $j ]['activityAmount']['value'] ) ? $skuPriceList[ $j ]['activityAmount']['value'] : '',
											'skuCalPrice'    => isset( $skuPriceList[ $j ]['amount']['value'] ) ? $skuPriceList[ $j ]['amount']['value'] : '',
										),
										'image'              => '',
										'variation_ids'      => array(),
										'variation_ids_sub'  => array(),
										'variation_ids_slug' => array(),
										'ship_from'          => '',
									);
									if ( $temp['skuPropIds'] ) {
										$temAttr    = array();
										$temAttrSub = array();
										$attrIds    = explode( ',', $temp['skuPropIds'] );

										if ( $ignore_ship_from_default_id && ! in_array( $ignore_ship_from_default_id, $attrIds ) && $ignore_ship_from ) {
											continue;
										}

										if ( $is_ru ) {
											for ( $k = 0; $k < count( $attrIds ); $k ++ ) {
												$propertyValueId = $attrIds[ $k ];

												if ( isset( $listAttributesDisplayNames[ $propertyValueId ] ) ) {
													$temAttr[ $attributes['list_attributes_slugs'][ $propertyValueId ] ]    = $listAttributesDisplayNames[ $propertyValueId ];
													$temAttrSub[ $attributes['list_attributes_slugs'][ $propertyValueId ] ] = $propertyValueNames[ $propertyValueId ];
													if ( ! empty( $attributes['variation_images'][ $propertyValueId ] ) ) {
														$temp['image'] = $attributes['variation_images'][ $propertyValueId ];
													}
												}
												if ( ! empty( $listAttributes[ $propertyValueId ]['ship_from'] ) ) {
													$temp['ship_from'] = $listAttributes[ $propertyValueId ]['ship_from'];
												}
											}

										} else {
											$parent_attrIds = explode( ';', $temp['skuAttr'] );
											for ( $k = 0; $k < count( $attrIds ); $k ++ ) {
												$propertyValueId = explode( ':', $parent_attrIds[ $k ] )[0] . ':' . $attrIds[ $k ];

												if ( isset( $listAttributesDisplayNames[ $propertyValueId ] ) ) {
													$temAttr[ $attributes['list_attributes_slugs'][ $propertyValueId ] ]    = $listAttributesDisplayNames[ $propertyValueId ];
													$temAttrSub[ $attributes['list_attributes_slugs'][ $propertyValueId ] ] = $propertyValueNames[ $propertyValueId ];
													if ( ! empty( $attributes['variation_images'][ $propertyValueId ] ) ) {
														$temp['image'] = $attributes['variation_images'][ $propertyValueId ];
													}
												}
												if ( ! empty( $listAttributes[ $propertyValueId ]['ship_from'] ) ) {
													$temp['ship_from'] = $listAttributes[ $propertyValueId ]['ship_from'];
												}
											}

										}

										$temp['variation_ids']     = $temAttr;
										$temp['variation_ids_sub'] = $temAttrSub;
									}

									$variations [] = $temp;
								}
								$attributes['variations'] = $variations;
							}
							$attributes['name'] = isset( $props['name'] ) ? $props['name'] : '';
						}
						$attributes['description_url'] = '';
					}
				} elseif ( isset( $ali_product_data['data']['data'] ) ) {
					$attributes['currency_code'] = self::aliexpress_pt_get_trade_currency( $ali_product_data['data']['data'] );
					if ( ! self::is_currency_supported( $attributes['currency_code'] ) ) {
						$response['status'] = 'error';
						$response['code']   = 'currency_not_supported';
						if ( 'RUB' === $attributes['currency_code'] ) {
							$response['message'] = esc_html__( 'Please configure RUB/USD rate in the plugin settings/Product price', 'woo-alidropship' );
						} else {
							$response['message'] = esc_html__( 'Please switch AliExpress currency to USD', 'woo-alidropship' );
						}

						return $response;
					}
					$actionModule = self::aliexpress_pt_get_action_module( $ali_product_data['data']['data'] );
					if ( $actionModule ) {
						$attributes['sku'] = isset( $actionModule['productId'] ) ? $actionModule['productId'] : '';
						if ( isset( $actionModule['itemStatus'] ) && intval( $actionModule['itemStatus'] ) > 0 ) {
							$response['status']  = 'error';
							$response['message'] = esc_html__( 'This product is no longer available', 'woo-alidropship' );

							return $response;
						}
					}
					$attributes['description_url'] = self::aliexpress_pt_get_description( $ali_product_data['data']['data'] );
					$attributes['specsModule']     = self::aliexpress_pt_get_specs_module( $ali_product_data['data']['data'] );
					$attributes['store_info']      = array(
						'name' => '',
						'url'  => '',
						'num'  => '',
					);
					$store_info                    = self::aliexpress_pt_get_store_info( $ali_product_data['data']['data'] );
					if ( $store_info ) {
						$attributes['store_info']['name'] = isset( $store_info['storeName'] ) ? $store_info['storeName'] : '';
						$attributes['store_info']['url']  = isset( $store_info['storeURL'] ) ? $store_info['storeURL'] : '';
						$attributes['store_info']['num']  = isset( $store_info['storeNum'] ) ? $store_info['storeNum'] : '';
					}
					$image_view = self::aliexpress_pt_get_image_view( $ali_product_data['data']['data'] );
					if ( $image_view ) {
						if ( isset( $image_view['videoInfo'] ) ) {
							$attributes['video'] = array(
								'ali_member_id' => isset( $image_view['videoInfo']['videoUid'] ) ? $image_view['videoInfo']['videoUid'] : '',
								'media_id'      => isset( $image_view['videoInfo']['videoId'] ) ? $image_view['videoInfo']['videoId'] : '',
								'media_type'    => '',
								'poster_url'    => '',
							);
						}
						$attributes['gallery'] = isset( $image_view['imagePathList'] ) ? $image_view['imagePathList'] : array();
					}
					$skuModule = self::aliexpress_pt_get_sku_module( $ali_product_data['data']['data'] );
					if ( $skuModule ) {
						self::handle_sku_module( $skuModule, $ignore_ship_from, $attributes );
					}
					$titleModule = self::aliexpress_pt_get_title_module( $ali_product_data['data']['data'] );
					if ( $titleModule ) {
						$attributes['name'] = isset( $titleModule['subject'] ) ? $titleModule['subject'] : '';
					}
				} elseif ( isset( $ali_product_data['tradeComponent'] ) ) {
					$attributes = self::parse_data_from_AU( $ali_product_data, $ignore_ship_from, $ignore_ship_from_default );
					if ( ! empty( $attributes['status'] ) && $attributes['status'] == 'error' ) {
						return $attributes;
					}
				}
			} else {
				$descriptionModuleReg = '/"descriptionModule":(.*?),"features":{},"feedbackModule"/';
				preg_match( $descriptionModuleReg, $html, $descriptionModule );
				if ( $descriptionModule ) {
					$descriptionModule             = vi_wad_json_decode( $descriptionModule[1] );
					$attributes['sku']             = $descriptionModule['productId'];
					$attributes['description_url'] = $descriptionModule['descriptionUrl'];
				}

				$specsModuleReg = '/"specsModule":(.*?),"storeModule"/';
				preg_match( $specsModuleReg, $html, $specsModule );
				if ( $specsModule ) {
					$specsModule = vi_wad_json_decode( $specsModule[1] );
					if ( isset( $specsModule['props'] ) ) {
						$attributes['specsModule'] = $specsModule['props'];
					}
				}
				$storeModuleReg = '/"storeModule":(.*?),"titleModule"/';
				preg_match( $storeModuleReg, $html, $storeModule );
				if ( $storeModule ) {
					$storeModule              = vi_wad_json_decode( $storeModule[1] );
					$attributes['store_info'] = array(
						'name' => $storeModule['storeName'],
						'url'  => $storeModule['storeURL'],
						'num'  => $storeModule['storeNum'],
					);
				}
				$imagePathListReg = '/"imagePathList":(.*?),"name":"ImageModule"/';
				preg_match( $imagePathListReg, $html, $imagePathList );
				if ( $imagePathList ) {
					$imagePathList         = vi_wad_json_decode( $imagePathList[1] );
					$attributes['gallery'] = $imagePathList;
				}
				$videoIdReg = '/"videoId":(.+?),/';
				preg_match( $videoIdReg, $html, $videoId );
				$videoUidReg = '/"videoUid":(.+?)}/';
				preg_match( $videoUidReg, $html, $videoUid );
				if ( $videoId && $videoUid ) {
					$attributes['video'] = array(
						'ali_member_id' => $videoUid,
						'media_id'      => $videoId,
						'media_type'    => '',
						'poster_url'    => '',
					);
				}
				$skuModuleReg = '/"skuModule":(.*?),"specsModule"/';
				preg_match( $skuModuleReg, $html, $skuModule );
				if ( count( $skuModule ) == 2 ) {
					$skuModule                   = vi_wad_json_decode( $skuModule[1] );
					$productSKUPropertyList      = isset( $skuModule['productSKUPropertyList'] ) ? $skuModule['productSKUPropertyList'] : array();
					$ignore_ship_from_default_id = '';
					if ( is_array( $productSKUPropertyList ) && count( $productSKUPropertyList ) ) {
						for ( $i = 0; $i < count( $productSKUPropertyList ); $i ++ ) {
							$images            = array();
							$skuPropertyValues = $productSKUPropertyList[ $i ]['skuPropertyValues'];
							$attr_parent_id    = $productSKUPropertyList[ $i ]['skuPropertyId'];
							$skuPropertyName   = wc_sanitize_taxonomy_name( $productSKUPropertyList[ $i ]['skuPropertyName'] );
							if ( strtolower( $skuPropertyName ) === 'ships-from' && $ignore_ship_from ) {
								foreach ( $skuPropertyValues as $value ) {
									if ( $value['skuPropertySendGoodsCountryCode'] === $ignore_ship_from_default ) {
										$ignore_ship_from_default_id = $value['propertyValueId'] ? $value['propertyValueId'] : $value['propertyValueIdLong'];
									}
								}
								if ( $ignore_ship_from_default_id ) {
									continue;
								}
							} //point 1
							$attr = array(
								'values'   => array(),
								'slug'     => $skuPropertyName,
								'name'     => $productSKUPropertyList[ $i ]['skuPropertyName'],
								'position' => $i,
							);
							for ( $j = 0; $j < count( $skuPropertyValues ); $j ++ ) {
								$skuPropertyValue                               = $skuPropertyValues[ $j ];
								$org_propertyValueId                            = $skuPropertyValue['propertyValueId'] ? $skuPropertyValue['propertyValueId'] : $skuPropertyValue['propertyValueIdLong'];
								$propertyValueId                                = "{$attr_parent_id}:{$org_propertyValueId}";
								$propertyValueName                              = $skuPropertyValue['propertyValueName'];
								$propertyValueDisplayName                       = $skuPropertyValue['propertyValueDisplayName'];
								$listAttributesNames[ $propertyValueId ]        = $skuPropertyName;
								$listAttributesDisplayNames[ $propertyValueId ] = $propertyValueDisplayName;
								$propertyValueNames[ $propertyValueId ]         = $propertyValueName;
								$listAttributesIds[ $propertyValueId ]          = $attr_parent_id;
								$listAttributesSlug[ $propertyValueId ]         = $skuPropertyName;
								$attr['values'][ $propertyValueId ]             = $propertyValueDisplayName;
								$attr['values_sub'][ $propertyValueId ]         = $propertyValueName;
								$listAttributes[ $propertyValueId ]             = array(
									'name'      => $propertyValueDisplayName,
									'name_sub'  => $propertyValueName,
									'color'     => isset( $skuPropertyValue['skuColorValue'] ) ? $skuPropertyValue['skuColorValue'] : '',
									'image'     => '',
									'ship_from' => isset( $skuPropertyValue['skuPropertySendGoodsCountryCode'] ) ? $skuPropertyValue['skuPropertySendGoodsCountryCode'] : ''
								);
								if ( isset( $skuPropertyValue['skuPropertyImagePath'] ) && $skuPropertyValue['skuPropertyImagePath'] ) {
									$images[ $propertyValueId ]                  = $skuPropertyValue['skuPropertyImagePath'];
									$variationImages[ $propertyValueId ]         = $skuPropertyValue['skuPropertyImagePath'];
									$listAttributes[ $propertyValueId ]['image'] = $skuPropertyValue['skuPropertyImagePath'];
								}
							}

							$attributes['list_attributes']               = $listAttributes;
							$attributes['list_attributes_names']         = $listAttributesNames;
							$attributes['list_attributes_ids']           = $listAttributesIds;
							$attributes['list_attributes_slugs']         = $listAttributesSlug;
							$attributes['variation_images']              = $variationImages;
							$attributes['attributes'][ $attr_parent_id ] = $attr;
							$attributes['images'][ $attr_parent_id ]     = $images;

							$attributes['parent'][ $attr_parent_id ] = $skuPropertyName;
						}
					}

					$skuPriceList = $skuModule['skuPriceList'];
					for ( $j = 0; $j < count( $skuPriceList ); $j ++ ) {
						$temp = array(
							'skuId'              => isset( $skuPriceList[ $j ]['skuIdStr'] ) ? strval( $skuPriceList[ $j ]['skuIdStr'] ) : strval( $skuPriceList[ $j ]['skuId'] ),
							'skuAttr'            => isset( $skuPriceList[ $j ]['skuAttr'] ) ? $skuPriceList[ $j ]['skuAttr'] : '',
							'skuPropIds'         => isset( $skuPriceList[ $j ]['skuPropIds'] ) ? $skuPriceList[ $j ]['skuPropIds'] : '',
							'skuVal'             => $skuPriceList[ $j ]['skuVal'],
							'image'              => '',
							'variation_ids'      => array(),
							'variation_ids_sub'  => array(),
							'variation_ids_slug' => array(),
							'ship_from'          => '',
						);
						if ( $temp['skuPropIds'] ) {
							$temAttr        = array();
							$temAttrSub     = array();
							$attrIds        = explode( ',', $temp['skuPropIds'] );
							$parent_attrIds = explode( ';', $temp['skuAttr'] );

							if ( $ignore_ship_from_default_id && ! in_array( $ignore_ship_from_default_id, $attrIds ) && $ignore_ship_from ) {
								continue;
							}

							for ( $k = 0; $k < count( $attrIds ); $k ++ ) {
								$propertyValueId = explode( ':', $parent_attrIds[ $k ] )[0] . ':' . $attrIds[ $k ];
								if ( isset( $listAttributesDisplayNames[ $propertyValueId ] ) ) {
									$temAttr[ $attributes['list_attributes_slugs'][ $propertyValueId ] ]    = $listAttributesDisplayNames[ $propertyValueId ];
									$temAttrSub[ $attributes['list_attributes_slugs'][ $propertyValueId ] ] = $propertyValueNames[ $propertyValueId ];
									if ( ! empty( $attributes['variation_images'][ $propertyValueId ] ) ) {
										$temp['image'] = $attributes['variation_images'][ $propertyValueId ];
									}
								}
								if ( ! empty( $listAttributes[ $propertyValueId ]['ship_from'] ) ) {
									$temp['ship_from'] = $listAttributes[ $propertyValueId ]['ship_from'];
								}
							}
							$temp['variation_ids']     = $temAttr;
							$temp['variation_ids_sub'] = $temAttrSub;
						}
						$variations [] = $temp;
					}
					$attributes['variations'] = $variations;
				}
				$titleModuleReg = '/"titleModule":(.*?),"webEnv"/';
				preg_match( $titleModuleReg, $html, $titleModule );
				if ( count( $titleModule ) == 2 ) {
					$titleModule        = vi_wad_json_decode( $titleModule[1] );
					$attributes['name'] = $titleModule['subject'];
				}

				$webEnvReg = '/"webEnv":(.*?)}}/';
				preg_match( $webEnvReg, $html, $webEnv );
				if ( count( $webEnv ) == 2 ) {
					$webEnv                      = vi_wad_json_decode( $webEnv[1] . '}' );
					$attributes['currency_code'] = $webEnv['currency'];
				}
			}

			if ( ! $attributes['sku'] ) {
				$search  = array( "\n", "\r", "\t" );
				$replace = array( "", "", "" );
				$html    = str_replace( $search, $replace, $html );
				$regSku  = '/window\.runParams\.productId="([\s\S]*?)";/im';
				preg_match( $regSku, $html, $match_product_sku );
				if ( count( $match_product_sku ) === 2 && $match_product_sku[1] ) {
					$attributes['sku'] = $match_product_sku[1];
					$reg               = '/var skuProducts=(\[[\s\S]*?]);/im';
					$regId             = '/<a[\s\S]*?data-sku-id="(\d*?)"[\s\S]*?>(.*?)<\/a>/im';
					$regTitle          = '/<dt class="p-item-title">(.*?)<\/dt>[\s\S]*?data-sku-prop-id="(.*?)"/im';
					$regGallery        = '/imageBigViewURL=(\[[\s\S]*?]);/im';
					$regCurrencyCode   = '/window\.runParams\.currencyCode="([\s\S]*?)";/im';
					$regDetailDesc     = '/window\.runParams\.detailDesc="([\s\S]*?)";/im';
					$regOffline        = '/window\.runParams\.offline=([\s\S]*?);/im';
					$regName           = '/class="product-name" itemprop="name">([\s\S]*?)<\/h1>/im';
					$regDescription    = '/<ul class="product-property-list util-clearfix">([\s\S]*?)<\/ul>/im';
					preg_match( $regOffline, $html, $offlineMatches );
					if ( count( $offlineMatches ) == 2 ) {
						$offline = $offlineMatches[1];
					}

					preg_match( $reg, $html, $matches );
					if ( $matches ) {
						$productVariationMaps = vi_wad_json_decode( $matches[1] );
					}

					preg_match( $regDetailDesc, $html, $detailDescMatches );
					if ( $detailDescMatches ) {
						$attributes['description_url'] = $detailDescMatches[1];
					}

					preg_match( $regDescription, $html, $regDescriptionMatches );
					if ( $regDescriptionMatches ) {
						$attributes['short_description'] = $regDescriptionMatches[0];
					}

					$reg = '/<dl class="p-property-item">([\s\S]*?)<\/dl>/im';
					preg_match_all( $reg, $html, $matches );

					if ( count( $matches[0] ) ) {
						$match_variations = $matches[0];
						$title            = '';
						$titleSlug        = '';
						$reTitle1         = '/title="(.*?)"/mi';
						$reImage          = '/bigpic="(.*?)"/mi';
						$attr_parent_id   = '';
						for ( $i = 0; $i < count( $match_variations ); $i ++ ) {
							preg_match( $regTitle, $match_variations[ $i ], $matchTitle );

							if ( count( $matchTitle ) == 3 ) {
								$title          = $matchTitle[1];
								$title          = substr( $title, 0, strlen( $title ) - 1 );
								$titleSlug      = strtolower( trim( preg_replace( '/[^\w]+/i', '-', $title ) ) );
								$attr_parent_id = $matchTitle[2];
							}

							$attr   = array();
							$images = array();
							preg_match_all( $regId, $match_variations[ $i ], $matchId );

							if ( count( $matchId ) == 3 ) {
								foreach ( $matchId[1] as $matchID_k => $matchID_v ) {
									$listAttributesNames[ $matchID_v ] = $title;
									$listAttributesIds[ $matchID_v ]   = $attr_parent_id;
									$listAttributesSlug[ $matchID_v ]  = $titleSlug;
									preg_match( $reTitle1, $matchId[2][ $matchID_k ], $title1 );

									if ( count( $title1 ) == 2 ) {
										$attr[ $matchID_v ]           = $title1[1];
										$listAttributes[ $matchID_v ] = $title1[1];
									} else {
										$end                          = strlen( $matchId[2][ $matchID_k ] ) - 13;
										$attr[ $matchID_v ]           = substr( $matchId[2][ $matchID_k ], 6, $end );
										$listAttributes[ $matchID_v ] = $attr[ $matchID_v ];
									}

									preg_match( $reImage, $matchId[2][ $matchID_k ], $image );

									if ( count( $image ) == 2 ) {
										$images[ $matchID_v ]          = $image[1];
										$variationImages[ $matchID_v ] = $image[1];
									}
								}

							}
							$attributes['list_attributes']               = $listAttributes;
							$attributes['list_attributes_names']         = $listAttributesNames;
							$attributes['list_attributes_ids']           = $listAttributesIds;
							$attributes['list_attributes_slugs']         = $listAttributesSlug;
							$attributes['variation_images']              = $variationImages;
							$attributes['attributes'][ $attr_parent_id ] = $attr;
							if ( count( $images ) > 0 ) {
								$attributes['images'][ $attr_parent_id ] = $images;
							}
							$attributes['parent'][ $attr_parent_id ]             = $title;
							$attributes['attribute_position'][ $attr_parent_id ] = $i;
							$attributes['parent_slug'][ $attr_parent_id ]        = $titleSlug;
						}
					}

					preg_match( $regGallery, $html, $matchGallery );
					if ( count( $matchGallery ) == 2 ) {
						$attributes['gallery'] = vi_wad_json_decode( $matchGallery[1] );
					}

					for ( $j = 0; $j < count( $productVariationMaps ); $j ++ ) {
						$temp = array(
							'skuId'         => isset( $productVariationMaps[ $j ]['skuIdStr'] ) ? strval( $productVariationMaps[ $j ]['skuIdStr'] ) : strval( $productVariationMaps[ $j ]['skuId'] ),
							'skuPropIds'    => isset( $productVariationMaps[ $j ]['skuPropIds'] ) ? $productVariationMaps[ $j ]['skuPropIds'] : '',
							'skuAttr'       => isset( $productVariationMaps[ $j ]['skuAttr'] ) ? $productVariationMaps[ $j ]['skuAttr'] : '',
							'skuVal'        => $productVariationMaps[ $j ]['skuVal'],
							'image'         => '',
							'variation_ids' => array(),
						);

						if ( $temp['skuPropIds'] ) {
							$temAttr = array();
							$attrIds = explode( ',', $temp['skuPropIds'] );
							for ( $k = 0; $k < count( $attrIds ); $k ++ ) {
								$temAttr[ $attributes['list_attributes_slugs'][ $attrIds[ $k ] ] ] = $attributes['list_attributes'][ $attrIds[ $k ] ];
							}
							$temp['variation_ids'] = $temAttr;
							$temp['image']         = $attributes['variation_images'][ $attrIds[0] ];
						}
						array_push( $variations, $temp );
					}
					$attributes['variations'] = $variations;
					preg_match( $regName, $html, $matchName );
					if ( count( $matchName ) == 2 ) {
						$attributes['name'] = $matchName[1];
					}
					preg_match( $regCurrencyCode, $html, $matchCurrency );
					if ( count( $matchCurrency ) == 2 ) {
						$attributes['currency_code'] = $matchCurrency[1];
					}
				}
			}
		}

		if ( $attributes['sku'] ) {
			$response['data'] = $attributes;
		} else {
			$response['status'] = 'error';
		}

		return $response;
	}

	private static function parse_data_from_AU( $data, $ignore_ship_from ) {
		$result = [];

		$currency_component      = $data['currencyComponent'] ?? [];
		$result['currency_code'] = $currency_component['currencyCode'] ?? '';

		if ( ! self::is_currency_supported( $result['currency_code'] ) ) {
			$result['status'] = 'error';
			$result['code']   = 'currency_not_supported';
			if ( 'RUB' === $result['currency_code'] ) {
				$result['message'] = esc_html__( 'Please configure RUB/USD rate in the plugin settings/Product price', 'woo-alidropship' );
			} else {
				$result['message'] = esc_html__( 'Please switch AliExpress currency to USD', 'woo-alidropship' );
			}

			return $result;
		}

		$result['sku']             = $data['productInfoComponent']['id'] ?? '';
		$result['name']            = $data['productInfoComponent']['subject'] ?? '';
		$result['specsModule']     = $data['productPropComponent']['props'] ?? [];
		$result['description_url'] = $data['productDescComponent']['descriptionUrl'] ?? '';
		$result['gallery']         = $data['imageComponent']['imagePathList'] ?? [];

		if ( ! empty( $data['sellerComponent'] ) ) {
			$result['store_info'] = [
				'name' => $data['sellerComponent']['storeName'] ?? '',
				'url'  => $data['sellerComponent']['storeURL'] ?? '',
				'num'  => $data['sellerComponent']['storeNum'] ?? '',
			];
		}

		$sku_module                 = $data['skuComponent'] ?? [];
		$sku_module['skuPriceList'] = $data['priceComponent']['skuPriceList'] ?? [];
		self::handle_sku_module( $sku_module, $ignore_ship_from, $result );

		return $result;
	}


	/**
	 * By default, only support USD
	 *
	 * Since July of 2022, need support RUB as AliExpress does not allow to change currency to USD if language is Russian
	 *
	 * @param $currency
	 *
	 * @return bool
	 */
	private static function is_currency_supported( $currency ) {
		$instance = self::get_instance();
		$support  = false;
		if ( $currency === 'USD' ) {
			$support = true;
		} else if ( $currency === get_option( 'woocommerce_currency' ) ) {
			if ( $currency === 'RUB' && $instance->get_params( 'import_currency_rate' ) ) {
				$support = true;
			}
		} else if ( in_array( $currency, array(
				'RUB',
//				'CNY'
			), true ) && $instance->get_params( "import_currency_rate_{$currency}" ) ) {
			$support = true;
		}

		return $support;
	}

	private static function handle_sku_module( $skuModule, $ignore_ship_from, &$attributes ) {
		if ( is_array( $skuModule ) && count( $skuModule ) ) {
			$listAttributes             = array();
			$listAttributesDisplayNames = array();
			$propertyValueNames         = array();
			$listAttributesNames        = array();
			$listAttributesSlug         = array();
			$listAttributesIds          = array();
			$variationImages            = array();
			$variations                 = array();
			$productSKUPropertyList     = array();
			if ( isset( $skuModule['productSKUPropertyList'] ) ) {
				$productSKUPropertyList = $skuModule['productSKUPropertyList'];
			} elseif ( isset( $skuModule['propertyList'] ) ) {
				$productSKUPropertyList = $skuModule['propertyList'];
			}
			$china_id = '';
			if ( is_array( $productSKUPropertyList ) && count( $productSKUPropertyList ) ) {
				for ( $i = 0; $i < count( $productSKUPropertyList ); $i ++ ) {
					$images            = array();
					$skuPropertyValues = $productSKUPropertyList[ $i ]['skuPropertyValues'];
					$attr_parent_id    = $productSKUPropertyList[ $i ]['skuPropertyId'];
					$skuPropertyName   = wc_sanitize_taxonomy_name( $productSKUPropertyList[ $i ]['skuPropertyName'] );
					if ( strtolower( $skuPropertyName ) == 'ships-from' && $ignore_ship_from ) {
						foreach ( $skuPropertyValues as $value ) {
							if ( $value['skuPropertySendGoodsCountryCode'] == 'CN' ) {
								$china_id = $value['propertyValueId'] ? $value['propertyValueId'] : $value['propertyValueIdLong'];
							}
						}
						continue;
					} //point 1
					$attr = array(
						'values'   => array(),
						'slug'     => $skuPropertyName,
						'name'     => $productSKUPropertyList[ $i ]['skuPropertyName'],
						'position' => $i,
					);
					for ( $j = 0; $j < count( $skuPropertyValues ); $j ++ ) {
						$skuPropertyValue         = $skuPropertyValues[ $j ];
						$org_propertyValueId      = $skuPropertyValue['propertyValueId'] ? $skuPropertyValue['propertyValueId'] : $skuPropertyValue['propertyValueIdLong'];
						$propertyValueId          = "{$attr_parent_id}:{$org_propertyValueId}";
						$propertyValueName        = $skuPropertyValue['propertyValueName'];
						$propertyValueDisplayName = $skuPropertyValue['propertyValueDisplayName'];
						if ( in_array( $propertyValueDisplayName, $listAttributesDisplayNames ) ) {
							$propertyValueDisplayName = "{$propertyValueDisplayName}-{$org_propertyValueId}";
						}
						if ( in_array( $propertyValueName, $propertyValueNames ) ) {
							$propertyValueName = "{$propertyValueName}-{$org_propertyValueId}";
						}
						$listAttributesNames[ $propertyValueId ]        = $skuPropertyName;
						$listAttributesDisplayNames[ $propertyValueId ] = $propertyValueDisplayName;
						$propertyValueNames[ $propertyValueId ]         = $propertyValueName;
						$listAttributesIds[ $propertyValueId ]          = $attr_parent_id;
						$listAttributesSlug[ $propertyValueId ]         = $skuPropertyName;
						$attr['values'][ $propertyValueId ]             = $propertyValueDisplayName;
						$attr['values_sub'][ $propertyValueId ]         = $propertyValueName;
						$listAttributes[ $propertyValueId ]             = array(
							'name'      => $propertyValueDisplayName,
							'name_sub'  => $propertyValueName,
							'color'     => isset( $skuPropertyValue['skuColorValue'] ) ? $skuPropertyValue['skuColorValue'] : '',
							'image'     => '',
							'ship_from' => isset( $skuPropertyValue['skuPropertySendGoodsCountryCode'] ) ? $skuPropertyValue['skuPropertySendGoodsCountryCode'] : ''
						);
						if ( isset( $skuPropertyValue['skuPropertyImagePath'] ) && $skuPropertyValue['skuPropertyImagePath'] ) {
							$images[ $propertyValueId ]                  = $skuPropertyValue['skuPropertyImagePath'];
							$variationImages[ $propertyValueId ]         = $skuPropertyValue['skuPropertyImagePath'];
							$listAttributes[ $propertyValueId ]['image'] = $skuPropertyValue['skuPropertyImagePath'];
						}
					}

					$attributes['list_attributes']               = $listAttributes;
					$attributes['list_attributes_names']         = $listAttributesNames;
					$attributes['list_attributes_ids']           = $listAttributesIds;
					$attributes['list_attributes_slugs']         = $listAttributesSlug;
					$attributes['variation_images']              = $variationImages;
					$attributes['attributes'][ $attr_parent_id ] = $attr;
					$attributes['images'][ $attr_parent_id ]     = $images;

					$attributes['parent'][ $attr_parent_id ] = $skuPropertyName;
				}
			}

			$skuPriceList = array();
			if ( isset( $skuModule['skuPriceList'] ) ) {
				$skuPriceList = $skuModule['skuPriceList'];
			} elseif ( isset( $skuModule['skuList'] ) ) {
				$skuPriceList = $skuModule['skuList'];
			}
			for ( $j = 0; $j < count( $skuPriceList ); $j ++ ) {
				$temp = array(
					'skuId'              => isset( $skuPriceList[ $j ]['skuIdStr'] ) ? strval( $skuPriceList[ $j ]['skuIdStr'] ) : strval( $skuPriceList[ $j ]['skuId'] ),
					'skuAttr'            => isset( $skuPriceList[ $j ]['skuAttr'] ) ? $skuPriceList[ $j ]['skuAttr'] : '',
					'skuPropIds'         => isset( $skuPriceList[ $j ]['skuPropIds'] ) ? $skuPriceList[ $j ]['skuPropIds'] : '',
					'skuVal'             => $skuPriceList[ $j ]['skuVal'],
					'image'              => '',
					'variation_ids'      => array(),
					'variation_ids_sub'  => array(),
					'variation_ids_slug' => array(),
					'ship_from'          => '',
				);
				if ( $temp['skuPropIds'] ) {
					$temAttr        = array();
					$temAttrSub     = array();
					$attrIds        = explode( ',', $temp['skuPropIds'] );
					$parent_attrIds = explode( ';', $temp['skuAttr'] );

					if ( $china_id && ! in_array( $china_id, $attrIds ) && $ignore_ship_from ) {
						continue;
					}

					for ( $k = 0; $k < count( $attrIds ); $k ++ ) {
						$propertyValueId = explode( ':', $parent_attrIds[ $k ] )[0] . ':' . $attrIds[ $k ];
						if ( isset( $listAttributesDisplayNames[ $propertyValueId ] ) ) {
							$temAttr[ $attributes['list_attributes_slugs'][ $propertyValueId ] ]    = $listAttributesDisplayNames[ $propertyValueId ];
							$temAttrSub[ $attributes['list_attributes_slugs'][ $propertyValueId ] ] = $propertyValueNames[ $propertyValueId ];
							if ( ! empty( $attributes['variation_images'][ $propertyValueId ] ) ) {
								$temp['image'] = $attributes['variation_images'][ $propertyValueId ];
							}
						}
						if ( ! empty( $listAttributes[ $propertyValueId ]['ship_from'] ) ) {
							$temp['ship_from'] = $listAttributes[ $propertyValueId ]['ship_from'];
						}
					}
					$temp['variation_ids']     = $temAttr;
					$temp['variation_ids_sub'] = $temAttrSub;
				}
				$variations [] = $temp;
			}
			$attributes['variations'] = $variations;
		}
	}

	public static function get_user_agent() {
		$user_agent_list = get_option( 'vi_wad_user_agent_list' );
		if ( ! $user_agent_list ) {
			$user_agent_list = '["Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36","Mozilla\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/74.0.3729.169 Safari\/537.36","Mozilla\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/75.0.3770.100 Safari\/537.36","Mozilla\/5.0 (Windows NT 10.0; Win64; x64; rv:67.0) Gecko\/20100101 Firefox\/67.0","Mozilla\/5.0 (Macintosh; Intel Mac OS X 10_14_5) AppleWebKit\/605.1.15 (KHTML, like Gecko) Version\/12.1.1 Safari\/605.1.15","Mozilla\/5.0 (Macintosh; Intel Mac OS X 10_14_5) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/74.0.3729.169 Safari\/537.36","Mozilla\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/75.0.3770.80 Safari\/537.36","Mozilla\/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/74.0.3729.169 Safari\/537.36","Mozilla\/5.0 (X11; Ubuntu; Linux x86_64; rv:67.0) Gecko\/20100101 Firefox\/67.0","Mozilla\/5.0 (Macintosh; Intel Mac OS X 10.14; rv:67.0) Gecko\/20100101 Firefox\/67.0","Mozilla\/5.0 (Macintosh; Intel Mac OS X 10_14_5) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/75.0.3770.100 Safari\/537.36","Mozilla\/5.0 (Windows NT 10.0; WOW64) AppleWebKit\/537.36 (KHTML, like Gecko) HeadlessChrome\/60.0.3112.78 Safari\/537.36","Mozilla\/5.0 (Windows NT 6.1; rv:60.0) Gecko\/20100101 Firefox\/60.0","Mozilla\/5.0 (Windows NT 6.1; Win64; x64; rv:67.0) Gecko\/20100101 Firefox\/67.0","Mozilla\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/75.0.3770.90 Safari\/537.36","Mozilla\/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/75.0.3770.100 Safari\/537.36","Mozilla\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/64.0.3282.140 Safari\/537.36 Edge\/17.17134","Mozilla\/5.0 (Macintosh; Intel Mac OS X 10_13_6) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/74.0.3729.169 Safari\/537.36","Mozilla\/5.0 (X11; Linux x86_64; rv:67.0) Gecko\/20100101 Firefox\/67.0","Mozilla\/5.0 (Macintosh; Intel Mac OS X 10_14_4) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/74.0.3729.169 Safari\/537.36","Mozilla\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/74.0.3729.131 Safari\/537.36","Mozilla\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/64.0.3282.140 Safari\/537.36 Edge\/18.17763","Mozilla\/5.0 (X11; Linux x86_64) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/75.0.3770.80 Safari\/537.36","Mozilla\/5.0 (Macintosh; Intel Mac OS X 10_14_4) AppleWebKit\/605.1.15 (KHTML, like Gecko) Version\/12.1 Safari\/605.1.15","Mozilla\/5.0 (Windows NT 10.0; WOW64) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/74.0.3729.169 Safari\/537.36","Mozilla\/5.0 (Macintosh; Intel Mac OS X 10_13_6) AppleWebKit\/605.1.15 (KHTML, like Gecko) Version\/12.1.1 Safari\/605.1.15","Mozilla\/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/74.0.3729.169 Safari\/537.36","Mozilla\/5.0 (X11; Linux x86_64) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/75.0.3770.100 Safari\/537.36","Mozilla\/5.0 (Windows NT 10.0; WOW64; Trident\/7.0; rv:11.0) like Gecko","Mozilla\/5.0 (X11; Linux x86_64; rv:60.0) Gecko\/20100101 Firefox\/60.0","Mozilla\/5.0 (X11; Linux x86_64) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/74.0.3729.169 Safari\/537.36","Mozilla\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/73.0.3683.103 Safari\/537.36 OPR\/60.0.3255.151","Mozilla\/5.0 (Windows NT 6.1; WOW64; Trident\/7.0; rv:11.0) like Gecko","Mozilla\/5.0 (Macintosh; Intel Mac OS X 10_14_5) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/75.0.3770.80 Safari\/537.36","Mozilla\/5.0 (Macintosh; Intel Mac OS X 10_12_6) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/74.0.3729.169 Safari\/537.36","Mozilla\/5.0 (Macintosh; Intel Mac OS X 10.13; rv:67.0) Gecko\/20100101 Firefox\/67.0","Mozilla\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/73.0.3683.103 Safari\/537.36","Mozilla\/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/75.0.3770.80 Safari\/537.36","Mozilla\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/62.0.3202.94 Safari\/537.36","Mozilla\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/74.0.3729.157 Safari\/537.36","Mozilla\/5.0 (Windows NT 10.0; Win64; x64; rv:66.0) Gecko\/20100101 Firefox\/66.0","Mozilla\/5.0 (Windows NT 10.0; Win64; x64; rv:68.0) Gecko\/20100101 Firefox\/68.0","Mozilla\/5.0 (X11; Linux x86_64) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/72.0.3626.109 Safari\/537.36","Mozilla\/5.0 (Macintosh; Intel Mac OS X 10_14_3) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/74.0.3729.169 Safari\/537.36","Mozilla\/5.0 (Macintosh; Intel Mac OS X 10_14_5) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/75.0.3770.90 Safari\/537.36","Mozilla\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/73.0.3683.103 Safari\/537.36 OPR\/60.0.3255.109","Mozilla\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/73.0.3683.103 Safari\/537.36 OPR\/60.0.3255.170","Mozilla\/5.0 (Windows NT 6.3; Win64; x64; rv:67.0) Gecko\/20100101 Firefox\/67.0","Mozilla\/5.0 (Windows NT 10.0; WOW64; rv:67.0) Gecko\/20100101 Firefox\/67.0","Mozilla\/5.0 (iPad; CPU OS 12_3_1 like Mac OS X) AppleWebKit\/605.1.15 (KHTML, like Gecko) Version\/12.1.1 Mobile\/15E148 Safari\/604.1","Mozilla\/5.0 (Windows NT 6.1; WOW64) AppleWebKit\/537.36 (KHTML, like Gecko) HeadlessChrome\/60.0.3112.78 Safari\/537.36","Mozilla\/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/75.0.3770.100 Safari\/537.36","Mozilla\/5.0 (Macintosh; Intel Mac OS X 10_13_6) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/75.0.3770.100 Safari\/537.36","Mozilla\/5.0 (Windows NT 10.0; WOW64) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/74.0.3729.169 YaBrowser\/19.6.1.153 Yowser\/2.5 Safari\/537.36","Mozilla\/5.0 (X11; Linux x86_64) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/70.0.3538.77 Safari\/537.36","Mozilla\/5.0 (Windows NT 10.0; WOW64) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/73.0.3683.103 YaBrowser\/19.4.3.370 Yowser\/2.5 Safari\/537.36","Mozilla\/5.0 (Windows NT 10.0; WOW64) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/74.0.3729.169 YaBrowser\/19.6.0.1574 Yowser\/2.5 Safari\/537.36","Mozilla\/5.0 (X11; Linux x86_64) AppleWebKit\/537.36 (KHTML, like Gecko) Ubuntu Chromium\/74.0.3729.169 Chrome\/74.0.3729.169 Safari\/537.36","Mozilla\/5.0 (Windows NT 6.1) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/74.0.3729.169 Safari\/537.36","Mozilla\/5.0 (X11; Linux x86_64) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/74.0.3729.131 Safari\/537.36","Mozilla\/5.0 (Macintosh; Intel Mac OS X 10_14) AppleWebKit\/605.1.15 (KHTML, like Gecko) Version\/12.0 Safari\/605.1.15","Mozilla\/5.0 (Macintosh; Intel Mac OS X 10_14_0) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/74.0.3729.169 Safari\/537.36","Mozilla\/5.0 (X11; Linux x86_64) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/73.0.3683.86 Safari\/537.36","Mozilla\/5.0 (Linux; U; Android 4.3; en-us; SM-N900T Build\/JSS15J) AppleWebKit\/534.30 (KHTML, like Gecko) Version\/4.0 Mobile Safari\/534.30","Mozilla\/5.0 (Macintosh; Intel Mac OS X 10_14_3) AppleWebKit\/605.1.15 (KHTML, like Gecko) Version\/12.0.3 Safari\/605.1.15","Mozilla\/5.0 (Windows NT 6.1) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/75.0.3770.100 Safari\/537.36","Mozilla\/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/74.0.3729.169 Safari\/537.36","Mozilla\/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit\/605.1.15 (KHTML, like Gecko) Version\/11.1.2 Safari\/605.1.15","Mozilla\/5.0 (Macintosh; Intel Mac OS X 10_14_4) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/75.0.3770.80 Safari\/537.36","Mozilla\/5.0 (Windows NT 6.1; WOW64; rv:67.0) Gecko\/20100101 Firefox\/67.0","Mozilla\/5.0 (Macintosh; Intel Mac OS X 10_14_2) AppleWebKit\/605.1.15 (KHTML, like Gecko) Version\/12.0.2 Safari\/605.1.15","Mozilla\/5.0 (Macintosh; Intel Mac OS X 10_14_4) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/75.0.3770.100 Safari\/537.36","Mozilla\/5.0 (Windows NT 10.0; WOW64; rv:45.0) Gecko\/20100101 Firefox\/45.0","Mozilla\/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/75.0.3770.90 Safari\/537.36","Mozilla\/5.0 (X11; Linux x86_64) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/74.0.3729.157 Safari\/537.36","Mozilla\/5.0 (X11; Linux x86_64) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/75.0.3770.90 Safari\/537.36","Mozilla\/5.0 (Macintosh; Intel Mac OS X 10_14_2) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/74.0.3729.169 Safari\/537.36","Mozilla\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/72.0.3626.121 Safari\/537.36","Mozilla\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/73.0.3683.86 Safari\/537.36","Mozilla\/5.0 (Macintosh; Intel Mac OS X 10_12_6) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/75.0.3770.100 Safari\/537.36","Mozilla\/5.0 (Windows NT 10.0; Win64; x64; rv:60.0) Gecko\/20100101 Firefox\/60.0","Mozilla\/5.0 (Macintosh; Intel Mac OS X 10.12; rv:67.0) Gecko\/20100101 Firefox\/67.0","Mozilla\/5.0 (Macintosh; Intel Mac OS X 10_15) AppleWebKit\/605.1.15 (KHTML, like Gecko) Version\/13.0 Safari\/605.1.15","Mozilla\/5.0 (Windows NT 6.1; rv:67.0) Gecko\/20100101 Firefox\/67.0","Mozilla\/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/73.0.3683.103 Safari\/537.36 OPR\/60.0.3255.151","Mozilla\/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/73.0.3683.103 Safari\/537.36 OPR\/60.0.3255.170","Mozilla\/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/74.0.3729.131 Safari\/537.36","Mozilla\/5.0 (Windows NT 6.1; WOW64) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/73.0.3683.103 YaBrowser\/19.4.3.370 Yowser\/2.5 Safari\/537.36","Mozilla\/5.0 (Windows NT 6.1; WOW64; rv:56.0) Gecko\/20100101 Firefox\/56.0","Mozilla\/5.0 (Windows NT 6.1; WOW64; rv:56.0) Gecko\/20100101 Firefox\/56.0"]';
			update_option( 'vi_wad_user_agent_list', $user_agent_list );
		}
		$user_agent_list_array = vi_wad_json_decode( $user_agent_list );
		$return_agent          = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36';
		$last_used             = get_option( 'vi_wad_last_used_user_agent', 0 );
		if ( $last_used == count( $user_agent_list_array ) - 1 ) {
			$last_used = 0;
			shuffle( $user_agent_list_array );
			update_option( 'vi_wad_user_agent_list', json_encode( $user_agent_list_array ) );
		} else {
			$last_used ++;
		}
		update_option( 'vi_wad_last_used_user_agent', $last_used );
		if ( isset( $user_agent_list_array[ $last_used ] ) && $user_agent_list_array[ $last_used ] ) {
			$return_agent = $user_agent_list_array[ $last_used ];
		}

		return $return_agent;
	}

	public static function sku_exists( $sku = '' ) {
		$sku_exists = false;
		if ( $sku ) {
			$id_from_sku = wc_get_product_id_by_sku( $sku );
			$product     = $id_from_sku ? wc_get_product( $id_from_sku ) : false;
			$sku_exists  = $product && 'importing' !== $product->get_status();
		}

		return $sku_exists;
	}

	public static function set( $name, $set_name = false ) {
		if ( is_array( $name ) ) {
			return implode( ' ', array_map( array( 'VI_WOO_ALIDROPSHIP_DATA', 'set' ), $name ) );
		} else {
			if ( $set_name ) {
				return str_replace( '-', '_', self::$prefix . $name );
			} else {
				return self::$prefix . $name;
			}
		}
	}

	public function get_default( $name = "" ) {
		if ( ! $name ) {
			return $this->default;
		} elseif ( isset( $this->default[ $name ] ) ) {
			return apply_filters( 'wooaliexpressdropship_params_default_' . $name, $this->default[ $name ] );
		} else {
			return false;
		}
	}

	/**
	 * @param $string_number
	 *
	 * @return float
	 */
	public static function string_to_float( $string_number ) {
		return floatval( str_replace( ',', '', $string_number ) );
	}

	public function process_exchange_price( $price ) {
		if ( ! $price ) {
			return $price;
		}
		$rate = floatval( $this->get_params( 'import_currency_rate' ) );
		if ( $rate ) {
			$price = $price * $rate;
		}
		if ( $this->get_params( 'format_price_rules_enable' ) ) {
			self::format_price( $price );
		}

		return round( $price, wc_get_price_decimals() );
	}

	protected static function calculate_price_base_on_type( $price, $value, $type ) {
		$match_value = floatval( $value );
		switch ( $type ) {
			case 'fixed':
				$price = $price + $match_value;
				break;
			case 'percent':
				$price = $price * ( 1 + $match_value / 100 );
				break;
			case 'multiply':
				$price = $price * $match_value;
				break;
			default:
				$price = $match_value;
		}

		return $price;
	}

	/**
	 * @param $price
	 * @param bool $is_sale_price
	 *
	 * @return float|int
	 */
	public function process_price( $price, $is_sale_price = false ) {
		if ( ! $price ) {
			return $price;
		}
		$original_price  = $price;
		$price_default   = $this->get_params( 'price_default' );
		$price_from      = $this->get_params( 'price_from' );
		$price_to        = $this->get_params( 'price_to' );
		$plus_value_type = $this->get_params( 'plus_value_type' );

		if ( $is_sale_price ) {
			$plus_sale_value = $this->get_params( 'plus_sale_value' );
			$level_count     = count( $price_from );
			if ( $level_count > 0 ) {
				/*adjust price rules since version 1.0.1.1*/
				if ( ! is_array( $price_to ) || count( $price_to ) !== $level_count ) {
					if ( $level_count > 1 ) {
						$price_to   = array_values( array_slice( $price_from, 1 ) );
						$price_to[] = '';
					} else {
						$price_to = array( '' );
					}
				}
				$match = false;
				for ( $i = 0; $i < $level_count; $i ++ ) {
					if ( $price >= $price_from[ $i ] && ( $price_to[ $i ] === '' || $price <= $price_to[ $i ] ) ) {
						$match = $i;
						break;
					}
				}
				if ( $match !== false ) {
					if ( $plus_sale_value[ $match ] < 0 ) {
						$price = 0;
					} else {
						$price = self::calculate_price_base_on_type( $price, $plus_sale_value[ $match ], $plus_value_type[ $match ] );
					}
				} else {
					$plus_sale_value_default = isset( $price_default['plus_sale_value'] ) ? $price_default['plus_sale_value'] : 1;
					if ( $plus_sale_value_default < 0 ) {
						$price = 0;
					} else {
						$price = self::calculate_price_base_on_type( $price, $plus_sale_value_default, isset( $price_default['plus_value_type'] ) ? $price_default['plus_value_type'] : 'multiply' );
					}
				}
			}
		} else {
			$plus_value  = $this->get_params( 'plus_value' );
			$level_count = count( $price_from );
			if ( $level_count > 0 ) {
				/*adjust price rules since version 1.0.1.1*/
				if ( ! is_array( $price_to ) || count( $price_to ) !== $level_count ) {
					if ( $level_count > 1 ) {
						$price_to   = array_values( array_slice( $price_from, 1 ) );
						$price_to[] = '';
					} else {
						$price_to = array( '' );
					}
				}
				$match = false;
				for ( $i = 0; $i < $level_count; $i ++ ) {
					if ( $price >= $price_from[ $i ] && ( $price_to[ $i ] === '' || $price <= $price_to[ $i ] ) ) {
						$match = $i;
						break;
					}
				}
				if ( $match !== false ) {
					$price = self::calculate_price_base_on_type( $price, $plus_value[ $match ], $plus_value_type[ $match ] );
				} else {
					$price = self::calculate_price_base_on_type( $price, isset( $price_default['plus_value'] ) ? $price_default['plus_value'] : 2, isset( $price_default['plus_value_type'] ) ? $price_default['plus_value_type'] : 'multiply' );
				}
			}
		}

		return apply_filters( 'vi_wad_processed_price', $price, $is_sale_price, $original_price );
	}

	public static function format_price( &$price ) {
		$applied = array();
		if ( $price ) {
			$instance = self::get_instance();
			$rules    = $instance->get_params( 'format_price_rules' );
			if ( is_array( $rules ) && count( $rules ) ) {
				$decimals        = wc_get_price_decimals();
				$price           = self::string_to_float( $price );
				$int_part        = intval( $price );
				$decimal_part    = number_format( $price - $int_part, $decimals );
				$int_part_length = strlen( $int_part );
				if ( $decimals > 0 ) {
					foreach ( $rules as $key => $rule ) {
						if ( $rule['part'] === 'fraction' ) {
							if ( ( ! $rule['from'] && ! $rule['to'] ) || ( $price >= $rule['from'] && $price <= $rule['to'] ) || ( ! $rule['from'] && $price <= $rule['to'] ) || ( ! $rule['to'] && $price >= $rule['from'] ) ) {
								$compare_value = $decimal_part;
								$string        = substr( strval( $decimal_part ), 2 );
								if ( ( $rule['value_from'] === '' && $rule['value_to'] === '' ) || ( $compare_value >= self::string_to_float( ".{$rule['value_from']}" ) && $compare_value <= self::string_to_float( ".{$rule['value_to']}" ) ) || ( $rule['value_from'] === '' && $compare_value <= self::string_to_float( ".{$rule['value_to']}" ) ) || ( $rule['value_to'] === '' && $compare_value >= self::string_to_float( ".{$rule['value_from']}" ) ) ) {
									while ( ( $pos = strpos( $rule['value'], 'x' ) ) !== false ) {
										$replace = 'y';
										if ( $pos < strlen( $string ) ) {
											$replace = substr( $string, $pos, 1 );
										}
										$rule['value'] = substr_replace( $rule['value'], $replace, $pos, 1 );
									}
									$price        = $int_part + self::string_to_float( ".{$rule['value']}" );
									$decimal_part = $price - $int_part;
									$applied[]    = $key;
									break;
								}
							}
						}
					}
				}
				foreach ( $rules as $key => $rule ) {
					if ( $rule['part'] === 'integer' ) {
						if ( $price >= $rule['from'] && $price <= $rule['to'] ) {
							if ( $rule['value_from'] === '' && $rule['value_to'] === '' ) {
								$max = min( $int_part_length - 1, strlen( $rule['value'] ) );
								if ( $max > 0 ) {
									$compare_value = intval( substr( $int_part, $int_part_length - $max ) );
									$string        = strval( zeroise( $compare_value, $max ) );
									$rule['value'] = zeroise( $rule['value'], $max );
									while ( ( $pos = strpos( $rule['value'], 'x' ) ) !== false ) {
										$replace = 'y';
										if ( $pos < strlen( $string ) ) {
											$replace = substr( $string, $pos, 1 );
										}
										$rule['value'] = substr_replace( $rule['value'], $replace, $pos, 1 );
									}
									$price     = $int_part - $compare_value + intval( $rule['value'] ) + $decimal_part;
									$applied[] = $key;
									break;
								}
							} else {
								$max = min( $int_part_length, max( strlen( $rule['value_from'] ), strlen( $rule['value_to'] ), strlen( $rule['value'] ) ) );
								if ( $max > 0 ) {
									$compare_value = intval( substr( $int_part, $int_part_length - $max ) );
									$string        = strval( zeroise( $compare_value, $max ) );
									$rule['value'] = zeroise( $rule['value'], $max );
									if ( ( $compare_value >= intval( $rule['value_from'] ) && $compare_value <= intval( $rule['value_to'] ) ) ) {
										while ( ( $pos = strpos( $rule['value'], 'x' ) ) !== false ) {
											$replace = 'y';
											if ( $pos < strlen( $string ) ) {
												$replace = substr( $string, $pos, 1 );
											}
											$rule['value'] = substr_replace( $rule['value'], $replace, $pos, 1 );
										}
										$price     = $int_part - $compare_value + intval( $rule['value'] ) + $decimal_part;
										$applied[] = $key;
										break;
									}
								}
							}
						}
					}
				}
			}
		}

		return $applied;
	}

	public static function process_variation_sku( $sku, $variation_ids ) {
		$return = '';
		if ( is_array( $variation_ids ) && count( $variation_ids ) ) {
			foreach ( $variation_ids as $key => $value ) {
				$variation_ids[ $key ] = wc_sanitize_taxonomy_name( $value );
			}
			$return = $sku . '-' . implode( '-', $variation_ids );
		}

		return $return;
	}

	public static function download_description( $product_id, $description_url, $description, $product_description ) {
		if ( $description_url && $product_id ) {
			$request = wp_remote_get(
				$description_url,
				array(
					'user-agent' => self::get_user_agent(),
					'timeout'    => 3,
				)
			);
			if ( ! is_wp_error( $request ) && get_post_type( $product_id ) === 'vi_wad_draft_product' ) {
				if ( isset( $request['body'] ) && $request['body'] ) {
					$body = preg_replace( '/<script\>[\s\S]*?<\/script>/im', '', $request['body'] );
					preg_match_all( '/src="([\s\S]*?)"/im', $body, $matches );
					if ( isset( $matches[1] ) && is_array( $matches[1] ) && count( $matches[1] ) ) {
						Ali_Product_Table::update_post_meta( $product_id, '_vi_wad_description_images', array_values( array_unique( $matches[1] ) ) );
					}
					$instance    = self::get_instance();
					$str_replace = $instance->get_params( 'string_replace' );
					if ( isset( $str_replace['to_string'] ) && is_array( $str_replace['to_string'] ) && $str_replace_count = count( $str_replace['to_string'] ) ) {
						for ( $i = 0; $i < $str_replace_count; $i ++ ) {
							if ( $str_replace['sensitive'][ $i ] ) {
								$body = str_replace( $str_replace['from_string'][ $i ], $str_replace['to_string'][ $i ], $body );
							} else {
								$body = str_ireplace( $str_replace['from_string'][ $i ], $str_replace['to_string'][ $i ], $body );
							}
						}

					}
					if ( $product_description === 'item_specifics_and_description' || $product_description === 'description' ) {
						$description .= $body;
						Ali_Product_Table::wp_update_post( array( 'ID' => $product_id, 'post_content' => $description ) );
					}
				}
			}
		}
	}

	/**
	 * @return bool
	 */
	public static function get_disable_wp_cron() {
		return defined( 'DISABLE_WP_CRON' ) && DISABLE_WP_CRON === true;
	}

	/**Download image from url
	 *
	 * @param $image_id
	 * @param $url
	 * @param int $post_parent
	 * @param array $exclude
	 * @param string $post_title
	 * @param null $desc
	 *
	 * @return array|bool|int|object|string|WP_Error|null
	 */
	public static function download_image( &$image_id, $url, $post_parent = 0, $exclude = array(), $post_title = '', $desc = null ) {
		global $wpdb;
		$instance = self::get_instance();
		if ( $instance->get_params( 'use_external_image' ) && class_exists( 'EXMAGE_WP_IMAGE_LINKS' ) ) {
			$external_image = EXMAGE_WP_IMAGE_LINKS::add_image( $url, $image_id, $post_parent );
			$thumb_id       = $external_image['id'] ? $external_image['id'] : new WP_Error( 'exmage_image_error', $external_image['message'] );
		} else {
			$new_url   = $url;
			$parse_url = wp_parse_url( $new_url );
			$scheme    = empty( $parse_url['scheme'] ) ? 'http' : $parse_url['scheme'];
			$image_id  = "{$parse_url['host']}{$parse_url['path']}";
			$new_url   = "{$scheme}://{$image_id}";
			preg_match( '/[^\?]+\.(jpg|JPG|jpeg|JPEG|jpe|JPE|gif|GIF|png|PNG|webp|WEBP)/', $new_url, $matches );
			if ( ! is_array( $matches ) || ! count( $matches ) ) {
				preg_match( '/[^\?]+\.(jpg|JPG|jpeg|JPEG|jpe|JPE|gif|GIF|png|PNG|webp|WEBP)/', $url, $matches );
				if ( is_array( $matches ) && count( $matches ) ) {
					$new_url  .= "?{$matches[0]}";
					$image_id .= "?{$matches[0]}";
				} elseif ( ! empty( $parse_url['query'] ) ) {
					$new_url .= '?' . $parse_url['query'];
				}
			} elseif ( ! empty( $parse_url['query'] ) ) {
				$new_url .= '?' . $parse_url['query'];
			}

			$thumb_id = self::get_id_by_image_id( $image_id );
			if ( ! $thumb_id ) {
				$thumb_id = vi_wad_upload_image( $new_url, $post_parent, $exclude, $post_title, $desc );
				if ( ! is_wp_error( $thumb_id ) ) {
					update_post_meta( $thumb_id, '_vi_wad_image_id', $image_id );
				}
			} elseif ( $post_parent ) {
				$table_postmeta = "{$wpdb->prefix}posts";
				$wpdb->query( $wpdb->prepare( "UPDATE {$table_postmeta} set post_parent=%s WHERE ID=%s AND post_parent = 0 LIMIT 1", array(
					$post_parent,
					$thumb_id
				) ) );
			}
		}

		return $thumb_id;
	}

	/**
	 * @param $image_id
	 * @param bool $count
	 * @param bool $multiple
	 *
	 * @return array|bool|object|string|null
	 */
	public static function get_id_by_image_id( $image_id, $count = false, $multiple = false ) {
		global $wpdb;
		if ( $image_id ) {
			$table_posts    = "{$wpdb->prefix}posts";
			$table_postmeta = "{$wpdb->prefix}postmeta";
			$post_type      = 'attachment';
			$meta_key       = "_vi_wad_image_id";
			if ( $count ) {
				$query   = "SELECT count(*) from {$table_postmeta} join {$table_posts} on {$table_postmeta}.post_id={$table_posts}.ID where {$table_posts}.post_type = '{$post_type}' and {$table_posts}.post_status != 'trash' and {$table_postmeta}.meta_key = '{$meta_key}' and {$table_postmeta}.meta_value = %s";
				$results = $wpdb->get_var( $wpdb->prepare( $query, $image_id ) );
			} else {
				$query = "SELECT {$table_postmeta}.* from {$table_postmeta} join {$table_posts} on {$table_postmeta}.post_id={$table_posts}.ID where {$table_posts}.post_type = '{$post_type}' and {$table_posts}.post_status != 'trash' and {$table_postmeta}.meta_key = '{$meta_key}' and {$table_postmeta}.meta_value = %s";
				if ( $multiple ) {
					$results = $wpdb->get_results( $wpdb->prepare( $query, $image_id ), ARRAY_A );
				} else {
					$query   .= ' LIMIT 1';
					$results = $wpdb->get_var( $wpdb->prepare( $query, $image_id ), 1 );
				}
			}

			return $results;
		} else {
			return false;
		}
	}

	public static function count_posts( $status ) {
		$args_publish = array(
			'post_type'      => 'vi_wad_draft_product',
			'post_status'    => $status,
			'order'          => 'DESC',
			'meta_key'       => '_vi_wad_woo_id',
			'orderby'        => 'meta_value_num',
			'posts_per_page' => - 1,
		);
//		$the_query    = new WP_Query( $args_publish );
		$the_query = VI_WOO_ALIDROPSHIP_DATA::is_ald_table() ? new Ali_Product_Query( $args_publish ) : new WP_Query( $args_publish );

		$total = isset( $the_query->post_count ) ? $the_query->post_count : 0;
		wp_reset_postdata();

		return $total;
	}

	/**Get available shipping company
	 *
	 * @param string $slug
	 *
	 * @return array|mixed|string
	 */
	public static function get_shipping_companies( $slug = '' ) {
		$shipping_companies = apply_filters( 'vi_wad_aliexpress_shipping_companies', array(
			'AE_CAINIAO_STANDARD'      => "Cainiao Expedited Standard",
			'AE_CN_SUPER_ECONOMY_G'    => "Cainiao Super Economy Global",
			'ARAMEX'                   => "ARAMEX",
			'CAINIAO_CONSOLIDATION_SA' => "AliExpress Direct(SA)",
			'CAINIAO_CONSOLIDATION_AE' => "AliExpress Direct(AE)",
			'CAINIAO_ECONOMY'          => "AliExpress Saver Shipping",
			'CAINIAO_PREMIUM'          => "AliExpress Premium Shipping",
			'CAINIAO_STANDARD'         => "AliExpress Standard Shipping",
			'CHP'                      => "Swiss Post",
			'CPAM'                     => "China Post Registered Air Mail",
			'DHL'                      => "DHL",
			'DHLECOM'                  => "DHL e-commerce",
			'EMS'                      => "EMS",
			'EMS_ZX_ZX_US'             => "ePacket",
			'E_EMS'                    => "e-EMS",
			'FEDEX'                    => "Fedex IP",
			'FEDEX_IE'                 => "Fedex IE",
			'GATI'                     => "GATI",
			'POST_NL'                  => "PostNL",
			'PTT'                      => "Turkey Post",
			'SF'                       => "SF Express",
			'SF_EPARCEL'               => "SF eParcel",
			'SGP'                      => "Singapore Post",
			'SUNYOU_ECONOMY'           => "SunYou Economic Air Mail",
			'TNT'                      => "TNT",
			'TOLL'                     => "DPEX",
			'UBI'                      => "UBI",
			'UPS'                      => "UPS Express Saver",
			'UPSE'                     => "UPS Expedited",
			'USPS'                     => "USPS",
			'YANWEN_AM'                => "Yanwen Special Line-YW",
			'YANWEN_ECONOMY'           => "Yanwen Economic Air Mail",
			'YANWEN_JYT'               => "China Post Ordinary Small Packet Plus",
			'POLANDPOST_PL'            => "Poland Post",
			'Other'                    => "Seller's Shipping Method",
		) );
		if ( $slug ) {
			return isset( $shipping_companies[ $slug ] ) ? $shipping_companies[ $slug ] : '';
		} else {
			return $shipping_companies;
		}
	}

	public static function wp_remote_get( $url, $args = array() ) {
		$return  = array(
			'status' => 'error',
			'data'   => '',
			'code'   => '',
		);
		$args    = array_merge( array(
				'user-agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36',
				'timeout'    => 3,
			)
			, $args );
		$request = wp_remote_get(
			$url, $args
		);
		if ( is_wp_error( $request ) ) {
			$return['data'] = $request->get_error_message();
			$return['code'] = $request->get_error_code();
		} else {
			$return['code'] = wp_remote_retrieve_response_code( $request );
			if ( $return['code'] === 200 ) {
				$return['status'] = 'success';
				$return['data']   = json_decode( $request['body'], true );
			}
		}

		return $return;
	}

	public static function sanitize_taxonomy_name( $name ) {
		return urldecode( function_exists( 'mb_strtolower' ) ? mb_strtolower( urlencode( wc_sanitize_taxonomy_name( $name ) ) ) : strtolower( urlencode( wc_sanitize_taxonomy_name( $name ) ) ) );
	}

	public static function get_aliexpress_product_url( $sku ) {
		return "https://www.aliexpress.com/item/{$sku}.html";
	}

	/**Get WooCommerce countries in English
	 * @return mixed
	 */
	public static function get_countries() {
		if ( self::$countries === null ) {
			unload_textdomain( 'woocommerce' );
			self::$countries = apply_filters( 'woocommerce_countries', include WC()->plugin_path() . '/i18n/countries.php' );
			if ( apply_filters( 'woocommerce_sort_countries', true ) ) {
				wc_asort_by_locale( self::$countries );
			}
			$locale = determine_locale();
			$locale = apply_filters( 'plugin_locale', $locale, 'woocommerce' );
			load_textdomain( 'woocommerce', WP_LANG_DIR . '/woocommerce/woocommerce-' . $locale . '.mo' );
			load_plugin_textdomain( 'woocommerce', false, plugin_basename( dirname( WC_PLUGIN_FILE ) ) . '/i18n/languages' );
		}

		return self::$countries;
	}

	/**Get WooCommerce states in English
	 *
	 * @param $cc
	 *
	 * @return bool|mixed
	 */
	public static function get_states( $cc ) {
		if ( self::$states === null ) {
			unload_textdomain( 'woocommerce' );
			self::$states = apply_filters( 'woocommerce_states', include WC()->plugin_path() . '/i18n/states.php' );
			$locale       = determine_locale();
			$locale       = apply_filters( 'plugin_locale', $locale, 'woocommerce' );
			load_textdomain( 'woocommerce', WP_LANG_DIR . '/woocommerce/woocommerce-' . $locale . '.mo' );
			load_plugin_textdomain( 'woocommerce', false, plugin_basename( dirname( WC_PLUGIN_FILE ) ) . '/i18n/languages' );
		}

		if ( ! is_null( $cc ) ) {
			return isset( self::$states[ $cc ] ) ? self::$states[ $cc ] : false;
		} else {
			return self::$states;
		}
	}

	/**Allows only numbers
	 *
	 * @param $phone
	 *
	 * @return string
	 */
	public static function sanitize_phone_number( $phone ) {
		return preg_replace( '/[^\d]/', '', $phone );
	}

	/**
	 * Get list of states/cities of a country to use when fulfilling AliExpress orders
	 *
	 * @param $cc
	 *
	 * @return mixed
	 */
	public static function get_state( $cc ) {
		if ( ! isset( self::$ali_states[ $cc ] ) ) {
			$states      = array();
			$states_file = VI_WOO_ALIDROPSHIP_PACKAGES . 'ali-states' . DIRECTORY_SEPARATOR . "$cc-states.json";
			if ( is_file( $states_file ) ) {
				ini_set( 'memory_limit', - 1 );
				$states = vi_wad_json_decode( file_get_contents( $states_file ) );
			}
			self::$ali_states[ $cc ] = $states;
		}

		return self::$ali_states[ $cc ];
	}

	/**
	 * @param $content
	 *
	 * @return mixed
	 */
	private function find_and_replace_strings( $content ) {
		$str_replace = $this->get_params( 'string_replace' );
		if ( isset( $str_replace['to_string'] ) && is_array( $str_replace['to_string'] ) && $str_replace_count = count( $str_replace['to_string'] ) ) {
			for ( $i = 0; $i < $str_replace_count; $i ++ ) {
				if ( $str_replace['sensitive'][ $i ] ) {
					$content = str_replace( $str_replace['from_string'][ $i ], $str_replace['to_string'][ $i ], $content );
				} else {
					$content = str_ireplace( $str_replace['from_string'][ $i ], $str_replace['to_string'][ $i ], $content );
				}
			}
		}

		return $content;
	}

	/**
	 * Create ALD products(added to import list): Import via chrome extension, reimport, override
	 *
	 * @param $data
	 * @param $shipping_info
	 * @param array $post_data
	 *
	 * @return int|WP_Error
	 */
	public function create_product( $data, $shipping_info, $post_data = array() ) {
		$sku                 = isset( $data['sku'] ) ? sanitize_text_field( $data['sku'] ) : '';
		$title               = isset( $data['name'] ) ? sanitize_text_field( $data['name'] ) : '';
		$description_url     = isset( $data['description_url'] ) ? stripslashes( $data['description_url'] ) : '';
		$short_description   = isset( $data['short_description'] ) ? wp_kses_post( stripslashes( $data['short_description'] ) ) : '';
		$description         = isset( $data['description'] ) ? wp_kses_post( stripslashes( $data['description'] ) ) : '';
		$specsModule         = isset( $data['specsModule'] ) ? stripslashes_deep( $data['specsModule'] ) : array();
		$gallery             = isset( $data['gallery'] ) ? stripslashes_deep( $data['gallery'] ) : array();
		$variation_images    = isset( $data['variation_images'] ) ? stripslashes_deep( $data['variation_images'] ) : array();
		$variations          = isset( $data['variations'] ) ? stripslashes_deep( $data['variations'] ) : array();
		$attributes          = isset( $data['attributes'] ) ? stripslashes_deep( $data['attributes'] ) : array();
		$list_attributes     = isset( $data['list_attributes'] ) ? stripslashes_deep( $data['list_attributes'] ) : array();
		$store_info          = isset( $data['store_info'] ) ? stripslashes_deep( $data['store_info'] ) : array();
		$currency_code       = isset( $data['currency_code'] ) ? strtoupper( stripslashes_deep( $data['currency_code'] ) ) : '';
		$description_setting = $this->get_params( 'product_description' );
		$specsModule         = apply_filters( 'vi_wad_import_product_specifications', $specsModule, $data );

		if ( count( $specsModule ) ) {
			ob_start();
			?>
			<div class="product-specs-list-container">
				<ul class="product-specs-list util-clearfix">
					<?php
					foreach ( $specsModule as $specs ) {
						?>
						<li class="product-prop line-limit-length"><span
									class="property-title"><?php echo esc_html( isset( $specs['attrName'] ) ? $specs['attrName'] : $specs['title'] ) ?>:&nbsp;</span><span
									class="property-desc line-limit-length"><?php echo esc_html( isset( $specs['attrValue'] ) ? $specs['attrValue'] : $specs['value'] ) ?></span>
						</li>
						<?php
					}
					?>
				</ul>
			</div>
			<?php
			$short_description .= ob_get_clean();
			$short_description = apply_filters( 'vi_wad_import_product_short_description', $short_description, $data );
		}

		switch ( $description_setting ) {
			case 'none':
				$description = '';
				break;
			case 'item_specifics':
				$description = $short_description;
				break;
			case 'description':
				if ( $description_url ) {
					$description .= self::get_product_description_from_url( $description_url );
				}
				break;
			case 'item_specifics_and_description':
			default:
				if ( $description_url ) {
					$description .= self::get_product_description_from_url( $description_url );
				}
				$description = $short_description . $description;
		}

		$original_desc_images = array();
		if ( $description ) {
			/*Search for images before applying find and replace rules to remember original image urls*/
			preg_match_all( '/src="([\s\S]*?)"/im', $description, $matches );
			if ( isset( $matches[1] ) && is_array( $matches[1] ) && count( $matches[1] ) ) {
				$original_desc_images = array_values( array_unique( $matches[1] ) );
			}
		}

		$description = $this->find_and_replace_strings( $description );
		if ( $description ) {
			/*In case image urls(in description) are affected, replace affected urls with their original ones*/
			preg_match_all( '/src="([\s\S]*?)"/im', $description, $matches );
			if ( isset( $matches[1] ) && is_array( $matches[1] ) && count( $matches[1] ) ) {
				$desc_images       = array_values( array_unique( $matches[1] ) );
				$desc_images_count = count( $desc_images );
				if ( $desc_images_count === count( $original_desc_images ) && $desc_images_count !== count( array_intersect( $desc_images, $original_desc_images ) ) ) {
					$description = str_replace( $desc_images, $original_desc_images, $description );
				}
			}
		}

		$description = apply_filters( 'vi_wad_import_product_description', $description, $data );

		$title   = $this->find_and_replace_strings( $title );
		$post_id = Ali_Product_Table::wp_insert_post( array_merge( array(
			'post_title'   => $title,
			'post_type'    => 'vi_wad_draft_product',
			'post_status'  => 'draft',
			'post_excerpt' => '',
			'post_content' => $description,
		), $post_data ), true );
		if ( $post_id && ! is_wp_error( $post_id ) ) {
			if ( count( $original_desc_images ) ) {
				Ali_Product_Table::update_post_meta( $post_id, '_vi_wad_description_images', $original_desc_images );
			}
			Ali_Product_Table::update_post_meta( $post_id, '_vi_wad_sku', $sku );
			Ali_Product_Table::update_post_meta( $post_id, '_vi_wad_attributes', $attributes );
			Ali_Product_Table::update_post_meta( $post_id, '_vi_wad_list_attributes', $list_attributes );
			if ( $shipping_info['freight'] ) {
				Ali_Product_Table::update_post_meta( $post_id, '_vi_wad_shipping_info', $shipping_info );
			}
			if ( isset( $shipping_info['freight_ext'] ) ) {
				$freight_ext = json_decode( $shipping_info['freight_ext'], true );
				Ali_Product_Table::update_post_meta( $post_id, '_vi_wad_shipping_freight_ext', $freight_ext );
			}
			$gallery = array_unique( array_filter( $gallery ) );
			if ( count( $gallery ) ) {
				Ali_Product_Table::update_post_meta( $post_id, '_vi_wad_gallery', $gallery );
			}
			Ali_Product_Table::update_post_meta( $post_id, '_vi_wad_variation_images', $variation_images );
			if ( is_array( $store_info ) && count( $store_info ) ) {
				Ali_Product_Table::update_post_meta( $post_id, '_vi_wad_store_info', $store_info );
			}
			if ( count( $variations ) ) {
				$variations_news      = array();
				$woocommerce_currency = get_option( 'woocommerce_currency' );
				$rate                 = 0;
				if ( $woocommerce_currency === $currency_code ) {
					if ( $woocommerce_currency === 'RUB' ) {//temporarily restrict to RUB
						$import_currency_rate = $this->get_params( 'import_currency_rate' );
						if ( $import_currency_rate ) {
							$rate = 1 / $import_currency_rate;
						}
					}
				} elseif ( in_array( $currency_code, array( 'RUB', ), true ) ) { //'CNY'
					$rate = $this->get_params( "import_currency_rate_{$currency_code}" );
				}

				foreach ( $variations as $key => $variation ) {
					$variations_new            = array();
					$variations_new['image']   = $variation['image'];
					$variations_new['sku']     = self::process_variation_sku( $sku, $variation['variation_ids'] );
					$variations_new['sku_sub'] = self::process_variation_sku( $sku, $variation['variation_ids_sub'] );
					$variations_new['skuId']   = $variation['skuId'];
					$variations_new['skuAttr'] = $variation['skuAttr'];
					$skuVal                    = isset( $variation['skuVal'] ) ? $variation['skuVal'] : array();
					if ( $currency_code === 'USD' && isset( $skuVal['skuMultiCurrencyCalPrice'] ) ) {
						$variations_new['regular_price'] = $skuVal['skuMultiCurrencyCalPrice'];
						$variations_new['sale_price']    = isset( $skuVal['actSkuMultiCurrencyCalPrice'] ) ? $skuVal['actSkuMultiCurrencyCalPrice'] : '';
						if ( isset( $skuVal['actSkuMultiCurrencyBulkPrice'] ) && self::string_to_float( $skuVal['actSkuMultiCurrencyBulkPrice'] ) > self::string_to_float( $variations_new['sale_price'] ) ) {
							$variations_new['sale_price'] = $skuVal['actSkuMultiCurrencyBulkPrice'];
						}
					} else {
						/*maybe convert to USD if data currency is not USD but the store currency*/
						$variations_new['regular_price'] = isset( $skuVal['skuCalPrice'] ) ? $skuVal['skuCalPrice'] : '';
						$variations_new['sale_price']    = ( isset( $skuVal['actSkuCalPrice'], $skuVal['actSkuBulkCalPrice'] ) && self::string_to_float( $skuVal['actSkuBulkCalPrice'] ) > self::string_to_float( $skuVal['actSkuCalPrice'] ) ) ? $skuVal['actSkuBulkCalPrice'] : ( isset( $skuVal['actSkuCalPrice'] ) ? $skuVal['actSkuCalPrice'] : '' );
						if ( ( $currency_code === $woocommerce_currency || in_array( $currency_code, array( 'RUB', 'CNY' ), true ) ) && $rate ) {
							if ( $variations_new['regular_price'] ) {
								$variations_new['regular_price'] = $rate * $variations_new['regular_price'];
							}
							if ( $variations_new['sale_price'] ) {
								$variations_new['sale_price'] = $rate * $variations_new['sale_price'];
							}
						}
						if ( isset( $skuVal['skuAmount']['currency'], $skuVal['skuAmount']['value'] ) && $skuVal['skuAmount']['value'] ) {
							if ( $skuVal['skuAmount']['currency'] === 'USD' ) {
								$variations_new['regular_price'] = $skuVal['skuAmount']['value'];
								if ( isset( $skuVal['skuActivityAmount']['currency'], $skuVal['skuActivityAmount']['value'] ) && $skuVal['skuActivityAmount']['currency'] === 'USD' && $skuVal['skuActivityAmount']['value'] ) {
									$variations_new['sale_price'] = $skuVal['skuActivityAmount']['value'];
								}
							} elseif ( ( $skuVal['skuAmount']['currency'] === $woocommerce_currency || in_array( $skuVal['skuAmount']['currency'], array( 'RUB', 'CNY' ), true ) ) && $rate ) {
								$variations_new['regular_price'] = $rate * $skuVal['skuAmount']['value'];
								if ( isset( $skuVal['skuActivityAmount']['currency'], $skuVal['skuActivityAmount']['value'] ) && $skuVal['skuActivityAmount']['currency'] === $woocommerce_currency && $skuVal['skuActivityAmount']['value'] ) {
									$variations_new['sale_price'] = $rate * $skuVal['skuActivityAmount']['value'];
								}
							}
						}
					}
					$variations_new['stock']          = isset( $skuVal['availQuantity'] ) ? absint( $skuVal['availQuantity'] ) : 0;
					$variations_new['attributes']     = isset( $variation['variation_ids'] ) ? $variation['variation_ids'] : array();
					$variations_new['attributes_sub'] = isset( $variation['variation_ids_sub'] ) ? $variation['variation_ids_sub'] : array();
					$variations_new['ship_from']      = isset( $variation['ship_from'] ) ? $variation['ship_from'] : '';
					$variations_news[]                = $variations_new;
				}
				Ali_Product_Table::update_post_meta( $post_id, '_vi_wad_variations', $variations_news );
			}
		}

		return $post_id;
	}

	private static function get_product_description_from_url( $description_url ) {
		$request     = wp_remote_get(
			$description_url,
			array(
				'user-agent' => self::get_user_agent(),
				'timeout'    => 10,
			)
		);
		$description = '';

		$response_code = wp_remote_retrieve_response_code( $request );

		if ( ! is_wp_error( $request ) && $response_code !== 404 ) {
			if ( isset( $request['body'] ) && $request['body'] ) {
				$body        = preg_replace( '/<script\>[\s\S]*?<\/script>/im', '', $request['body'] );
				$description = $body;
			}
		}

		return $description;
	}

	public static function get_get_tracking_url( $aliexpress_order_id = '' ) {
		return add_query_arg( array(
			'fromDomain'          => urlencode( site_url() ),
			'tradeId'             => $aliexpress_order_id,
			'getTracking'         => 'manual',
			'redirectOrderStatus' => 'all',
		), 'https://www.aliexpress.com/p/order/index.html' );
	}

	public static function wp_kses_post( $content ) {
		if ( self::$allow_html === null ) {
			self::$allow_html = wp_kses_allowed_html( 'post' );
			self::$allow_html = array_merge_recursive( self::$allow_html, array(
					'input'  => array(
						'type'         => 1,
						'id'           => 1,
						'name'         => 1,
						'class'        => 1,
						'placeholder'  => 1,
						'autocomplete' => 1,
						'style'        => 1,
						'value'        => 1,
						'size'         => 1,
						'checked'      => 1,
						'disabled'     => 1,
						'readonly'     => 1,
						'data-*'       => 1,
					),
					'form'   => array(
						'method' => 1,
						'id'     => 1,
						'class'  => 1,
						'action' => 1,
						'data-*' => 1,
					),
					'select' => array(
						'id'       => 1,
						'name'     => 1,
						'class'    => 1,
						'multiple' => 1,
						'data-*'   => 1,
					),
					'option' => array(
						'value'    => 1,
						'selected' => 1,
						'data-*'   => 1,
					),
				)
			);
			foreach ( self::$allow_html as $key => $value ) {
				if ( $key === 'input' ) {
					self::$allow_html[ $key ]['data-*']   = 1;
					self::$allow_html[ $key ]['checked']  = 1;
					self::$allow_html[ $key ]['disabled'] = 1;
					self::$allow_html[ $key ]['readonly'] = 1;
				} elseif ( in_array( $key, array( 'div', 'span', 'a', 'form', 'select', 'option', 'tr', 'td' ) ) ) {
					self::$allow_html[ $key ]['data-*'] = 1;
				}
			}
		}

		return wp_kses( $content, self::$allow_html );
	}

	public static function new_get_freight( $args, $freight_ext ) {
		$response = array(
			'status'  => 'error',
			'freight' => array(),
			'code'    => '',
			'from'    => '',
		);

		try {
			$_m_h5_tk     = get_option( 'ald_token_m_h5_tk', 'dcf8911d299cf23fa634d1f32bd8a7ce_1694578302862' );
			$_m_h5_tk_enc = get_option( 'ald_token_m_h5_tk_enc', '095d6f54493287499394c48b0060a23d' );

			$args['ext']        = $freight_ext;
			$args['quantity']   = $args['count'];
			$args['clientType'] = 'pc';
//			$args['userScene']  = 'PC_DETAIL_SHIPPING_PANEL';
			$args['userScene'] = 'PC_DETAIL';

			$freight = [];

			$token = explode( '_', $_m_h5_tk )[0] ?? '';
			$data  = wp_json_encode( $args );

			$sign_response = wp_remote_post( 'https://ald.villatheme.com/villatheme-ald-get-signature', [
				'body' => [
					'data'  => $data,
					'token' => $token,
				]
			] );

			$sign_response = json_decode( $sign_response['body'], true );
			$sign          = $sign_response['sign'];
			$time          = $sign_response['time'];

			$url = "https://acs.aliexpress.com/h5/mtop.aliexpress.itemdetail.queryexpression/1.0/?jsv=2.5.1&appKey=12574478&t={$time}&sign={$sign}&api=mtop.aliexpress.itemdetail.queryExpression&v=1.0&type=originaljson&dataType=jsonp";

			$cookies[] = new WP_Http_Cookie( array( 'name' => '_m_h5_tk', 'value' => $_m_h5_tk, ) );
			$cookies[] = new WP_Http_Cookie( array( 'name' => '_m_h5_tk_enc', 'value' => $_m_h5_tk_enc ) );

			$request = wp_remote_post( $url, [
				'user-agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36',
				'headers'    => array(
					'Content-Type' => 'application/x-www-form-urlencoded',
					"Accept"       => 'application/json',
				),
				'body'       => [ 'data' => $data ],
				'cookies'    => $cookies,
			] );

			if ( ! empty( $request['cookies'] ) ) {
				$cookies = $request['cookies'];
				/** @var \WP_Http_Cookie $cookie */
				foreach ( $cookies as $cookie ) {
					$cname  = $cookie->name;
					$cvalue = $cookie->value;
					switch ( $cname ) {
						case '_m_h5_tk':
							update_option( 'ald_token_m_h5_tk', $cvalue );
							break;
						case '_m_h5_tk_enc':
							update_option( 'ald_token_m_h5_tk_enc', $cvalue );
							break;
					}
				}
			}

			$body = wp_remote_retrieve_body( $request );

			if ( $body ) {
				$body = json_decode( $body, true );
				$data = $body['data'] ?? [];

				if ( isset( $data['code'] ) && $data['code'] == 200 ) {
					$list = $data['data']['deliveryExpressionResponse']['originalLayoutResultList'] ?? [];

					if ( ! empty( $list ) && is_array( $list ) ) {
						foreach ( $list as $f ) {
							if ( empty( $f['bizData'] ) ) {
								continue;
							}

							$bizdata = $f['bizData'];

							if ( ! empty( $bizdata['unreachable'] ) ) {
								continue;
							}

							$delivery_time = [];
							if ( isset( $bizdata['deliveryDayMin'] ) ) {
								$delivery_time[] = $bizdata['deliveryDayMin'];
							}

							if ( isset( $bizdata['deliveryDayMax'] ) ) {
								$delivery_time[] = $bizdata['deliveryDayMax'];
							}

							$freight[] = [
								'serviceName'      => $bizdata['deliveryOptionCode'] ?? '',
								'time'             => implode( '-', $delivery_time ),
								'company'          => $bizdata['company'] ?? $bizdata['deliveryOptionCode'] ?? '',
								'freightAmount'    => [
									'formatedAmount' => '',
									'currency'       => $bizdata['displayCurrency'] ?? $bizdata['currency'],
									'value'          => $bizdata['displayAmount'] ?? 0,
								],
								'sendGoodsCountry' => $bizdata['shipFromCode'] ?? 'CN'
							];

						}
					}
				} elseif ( ! empty( $body['ret'][0] ) && strpos( $body['ret'][0], 'FAIL_SYS_TOKEN_EXOIRED' ) !== false ) {
					return self::new_get_freight( $args, $freight_ext );
				}
			}

			if ( ! empty( $freight ) ) {
				$response['status']  = 'success';
				$response['freight'] = $freight;
				$response['code']    = 200;
				$response['from']    = 'mtop.aliexpress.itemdetail.queryexpression';
			}

		} catch ( \Exception $e ) {
			error_log( print_r( $e->getMessage(), true ) );
		}

		return $response;
	}

	/**
	 * @param $ali_product_id
	 * @param $country
	 * @param string $from_country two-letters country code: CN, US, ...
	 * @param int $quantity
	 * @param string $currency
	 *
	 * @return array
	 */
	public static function get_freight( $ali_product_id, $country, $from_country = '', $quantity = 1, $currency = 'USD' ) {

		$response = array(
			'status'  => 'error',
			'freight' => array(),
			'code'    => '',
		);
		$args     = array(
			'productId'     => (int) $ali_product_id,
			'country'       => VI_WOO_ALIDROPSHIP_Admin_API::filter_country( $country ),
			'tradeCurrency' => $currency,
			'count'         => $quantity,
			'provinceCode'  => '',
			'cityCode'      => '',
//			'minPrice'      => '1',
//			'maxPrice'      => '1',
		);
//		if ( 'BR' === $args['country'] ) {
		$ald_id      = self::product_get_id_by_aliexpress_id( $ali_product_id );
		$freight_ext = '';
		if ( $ald_id ) {
			$freight_ext = self::get_freight_ext( $ald_id, $currency, $country );

			if ( $freight_ext ) {
				$args['ext'] = 'RU' === $country ? json_decode( $freight_ext, true ) : ( $freight_ext );
			}
		}

//		if ( $ald_id ) {
//
//			$variations = get_post_meta( $ald_id, '_vi_wad_variations', true );
//			if ( $variations ) {
//				$price_array = array_filter( array_merge( array_column( $variations, 'sale_price' ), array_column( $variations, 'regular_price' ) ) );
//				if ( count( $price_array ) ) {
//					$min_price = min( $price_array );
//					if ( $min_price ) {
//						$min_price   = self::string_to_float( $min_price );
//						$args['ext'] = '{"p1":"' . number_format( $min_price, 2 ) . '","p3":"' . $currency . '","disCurrency":"' . $currency . '","p6":""}';
//					}
//				}
//			}
//		}
//		}

		if ( $from_country ) {
			$args['sendGoodsCountry'] = $from_country;
		}

		if ( $country == 'RU' ) {
//		$request          = self::wp_remote_get( add_query_arg( $args, 'https://www.aliexpress.com/aeglodetailweb/api/logistics/freight?provinceCode=&cityCode=&sellerAdminSeq=239419167&userScene=PC_DETAIL_SHIPPING_PANEL&displayMultipleFreight=false&ext={"disCurrency":"USD","p3":"USD","p6":"' . self::get_ali_tax( $country ) . '"}' ) );
			$url     = add_query_arg( $args, 'https://www.aliexpress.com/aeglodetailweb/api/logistics/freight' );
			$request = self::wp_remote_get( $url, [ 'headers' => [ 'Referer' => $url ] ] );
		} else {
			$response = self::new_get_freight( $args, $freight_ext );

			return apply_filters( 'ald_get_freight', $response, [] );
		}

		$response['code'] = $request['code'];
		if ( $request['status'] === 'success' ) {
			$data = $request['data'];
			if ( isset( $data['body'] ) && isset( $data['body']['freightResult'] ) && is_array( $data['body']['freightResult'] ) ) {
				$response['status']  = 'success';
				$response['freight'] = $data['body']['freightResult'];
			} else {
				$response['code'] = 404;
			}
		}

		return apply_filters( 'ald_get_freight', $response, $request );
	}

	/**
	 * @param $ald_id
	 * @param string $currency
	 *
	 * @return string
	 */
	private static function get_freight_ext( $ald_id, $currency = 'USD', $country = '' ) {
		$variations      = Ali_Product_Table::get_post_meta( $ald_id, '_vi_wad_variations', true );
		$ald_freight_ext = Ali_Product_Table::get_post_meta( $ald_id, '_vi_wad_shipping_freight_ext', true );

		$p0 = '';
		if ( ! empty( $ald_freight_ext ) && is_array( $ald_freight_ext ) ) {
//			return wp_json_encode( $freight_ext );
			$ald_freight_ext = current( $ald_freight_ext );
			$p0              = $ald_freight_ext['p0'] ?? '';
		}

		$freight_ext = '';
		if ( ! empty( $variations ) ) {

			$price_array = array_filter( array_merge( array_column( $variations, 'sale_price' ), array_column( $variations, 'regular_price' ) ) );
			if ( count( $price_array ) ) {
				$min_price = min( $price_array );
				if ( $min_price ) {
					$min_price = self::string_to_float( $min_price );
					$p6        = self::get_ali_tax( $country );
					$p6        = $p6 ? $p6 : '';
					$p0        = $p0 ? '"p0":"' . $p0 . '",' : '';
					if ( in_array( $country, [ 'RU' ] ) ) {
						$freight_ext = '{' . $p0 . '"p1":"' . number_format( $min_price, 2 ) . '","p3":"' . $currency . '","disCurrency":"' . $currency . '","p6":"' . $p6 . '"}';
					} else {
						$freight_ext = '[{' . $p0 . '"p1":"' . number_format( $min_price, 2 ) . '","p3":"' . $currency . '","disCurrency":"' . $currency . '","p6":"' . $p6 . '"}]';
					}
				}
			}
		}

		return $freight_ext;
	}

	/**
	 * @param $time
	 *
	 * @return int
	 */
	public static function get_shipping_cache_time( $time ) {
		return $time + rand( 0, 600 );
	}

	/**
	 * Check if shipping cost is available in USD
	 *
	 * @param $freight_v
	 *
	 * @return mixed|string
	 */
	public static function get_freight_amount( $freight_v ) {
		global $wooaliexpressdropship_settings;
		$freight_amount = '';
		if ( isset( $freight_v['standardFreightAmount']['value'], $freight_v['standardFreightAmount']['currency'] ) && $freight_v['standardFreightAmount']['currency'] === 'USD' ) {
			$freight_amount = $freight_v['standardFreightAmount']['value'];
		} elseif ( isset( $freight_v['freightAmount']['value'], $freight_v['freightAmount']['currency'] ) && $freight_v['freightAmount']['currency'] === 'USD' ) {
			$freight_amount = $freight_v['freightAmount']['value'];
		} elseif ( isset( $freight_v['previewFreightAmount']['value'], $freight_v['previewFreightAmount']['currency'] ) && $freight_v['previewFreightAmount']['currency'] === 'USD' ) {
			$freight_amount = $freight_v['previewFreightAmount']['value'];
		}
		if ( $freight_amount === '' ) {
			$currency = '';
			if ( isset( $freight_v['standardFreightAmount']['value'], $freight_v['standardFreightAmount']['currency'] ) ) {
				$freight_amount = $freight_v['standardFreightAmount']['value'];
				$currency       = $freight_v['standardFreightAmount']['currency'];
			} elseif ( isset( $freight_v['freightAmount']['value'], $freight_v['freightAmount']['currency'] ) ) {
				$freight_amount = $freight_v['freightAmount']['value'];
				$currency       = $freight_v['freightAmount']['currency'];
			} elseif ( isset( $freight_v['previewFreightAmount']['value'], $freight_v['previewFreightAmount']['currency'] ) ) {
				$freight_amount = $freight_v['previewFreightAmount']['value'];
				$currency       = $freight_v['previewFreightAmount']['currency'];
			}
		}

		return $freight_amount;
	}

	public static function aliexpress_ru_get_currency( $widgets ) {
		global $wad_count;
		$wad_count ++;
		$currency = '';
		foreach ( $widgets as $key => $value ) {
			if ( ! is_array( $value ) ) {
				continue;
			}
			if ( $key === 'currencyProps' ) {
				$currency = isset( $value['selected']['currencyType'] ) ? $value['selected']['currencyType'] : '';
				break;
			}
			$currency = self::aliexpress_ru_get_currency( $value );
			if ( $currency ) {
				break;
			}
		}

		return $currency;
	}

	public static function aliexpress_ru_get_data( $widgets ) {
		$data = '';
		foreach ( $widgets as $key => $value ) {
			if ( ! is_array( $value ) ) {
				continue;
			}
			if ( $key === 'props' ) {
				if ( isset( $value['id'], $value['skuInfo'], $value['itemStatus'], $value['sellerId'] ) ) {
					$data = $value;
					break;
				}
			}
			$data = self::aliexpress_ru_get_data( $value );
			if ( $data ) {
				break;
			}
		}

		return $data;
	}

	private static function aliexpress_ru_get_store_name( $widgets, $id ) {
		$store_name = null;
		foreach ( $widgets as $key => $value ) {
			if ( ! is_array( $value ) ) {
				continue;
			}
			if ( $key === 'props' ) {
				if ( isset( $value['id'] ) && $id == $value['id'] ) {
					$store_name = $value['name'];
					break;
				}
			}
			$store_name = self::aliexpress_ru_get_store_name( $value, $id );
			if ( $store_name ) {
				break;
			}
		}

		return $store_name;
	}

	public static function aliexpress_ru_get_description( $widgets ) {
		$description = null;
		foreach ( $widgets as $key => $value ) {
			if ( $key === 'html' ) {
				$description = $value ? $value : '';
				break;
			}
			if ( is_array( $value ) ) {
				$description = self::aliexpress_ru_get_description( $value );
			}
			if ( isset( $description ) ) {
				break;
			}
		}

		return $description;
	}

	public static function aliexpress_ru_get_specs_module( $widgets ) {
		$specs_module = null;
		foreach ( $widgets as $key => $value ) {
			if ( $key === 'char' ) {
				$specs_module = $value ? $value : array();
				break;
			}
			if ( is_array( $value ) ) {
				$specs_module = self::aliexpress_ru_get_specs_module( $value );
			}
			if ( isset( $specs_module ) ) {
				break;
			}
		}

		return $specs_module;
	}

	public static function aliexpress_ru_get_store_info( $widgets ) {
		$store_info = null;
		foreach ( $widgets as $key => $value ) {
			if ( $key === 'shop' ) {
				$store_info = $value;
				break;
			}
			if ( is_array( $value ) ) {
				$store_info = self::aliexpress_ru_get_store_info( $value );
			}
			if ( isset( $store_info ) ) {
				break;
			}
		}

		return $store_info;
	}

	public static function aliexpress_pt_get_trade_currency( $data ) {
		$currency = '';
		foreach ( $data as $key => $value ) {
			if ( ! is_array( $value ) ) {
				continue;
			}
			if ( substr( $key, 0, 9 ) === 'shipping_' ) {
				if ( isset( $value['type'] ) && $value['type'] === 'shipping' ) {
					if ( isset( $value['fields'], $value['fields']['tradeCurrency'] ) && $value['fields']['tradeCurrency'] ) {
						$currency = $value['fields']['tradeCurrency'];
						break;
					}
				}
			}
			$currency = self::aliexpress_pt_get_trade_currency( $value );
			if ( $currency ) {
				break;
			}
		}

		return $currency;
	}

	public static function aliexpress_pt_get_specs_module( $data ) {
		$specs = null;
		foreach ( $data as $key => $value ) {
			if ( ! is_array( $value ) ) {
				continue;
			}
			if ( substr( $key, 0, 10 ) === 'specsInfo_' ) {
				if ( isset( $value['type'] ) && $value['type'] === 'specsInfo' ) {
					if ( isset( $value['fields'], $value['fields']['specs'] ) ) {
						$specs = $value['fields']['specs'];
						break;
					}
				}
			}
			$specs = self::aliexpress_pt_get_specs_module( $value );
			if ( isset( $specs ) ) {
				break;
			}
		}

		return $specs;
	}

	public static function aliexpress_pt_get_description( $data ) {
		$desc = null;
		foreach ( $data as $key => $value ) {
			if ( ! is_array( $value ) ) {
				continue;
			}
			if ( substr( $key, 0, 12 ) === 'description_' ) {
				if ( isset( $value['type'] ) && $value['type'] === 'description' ) {
					if ( isset( $value['fields'], $value['fields']['detailDesc'] ) ) {
						$desc = $value['fields']['detailDesc'];
						break;
					}
				}
			}
			$desc = self::aliexpress_pt_get_description( $value );
			if ( isset( $desc ) ) {
				break;
			}
		}

		return $desc;
	}

	public static function aliexpress_pt_get_store_info( $data ) {
		$store_info = null;
		foreach ( $data as $key => $value ) {
			if ( ! is_array( $value ) ) {
				continue;
			}
			if ( substr( $key, 0, 15 ) === 'storeRecommend_' ) {
				if ( isset( $value['type'] ) && $value['type'] === 'storeRecommend' ) {
					if ( isset( $value['fields'] ) ) {
						$store_info = $value['fields'];
						break;
					}
				}
			}
			$store_info = self::aliexpress_pt_get_store_info( $value );
			if ( isset( $store_info ) ) {
				break;
			}
		}

		return $store_info;
	}

	public static function aliexpress_pt_get_image_view( $data ) {
		$image_view = null;
		foreach ( $data as $key => $value ) {
			if ( ! is_array( $value ) ) {
				continue;
			}
			if ( substr( $key, 0, 10 ) === 'imageView_' ) {
				if ( isset( $value['type'] ) && $value['type'] === 'imageView' ) {
					if ( isset( $value['fields'] ) ) {
						$image_view = $value['fields'];
						break;
					}
				}
			}
			$image_view = self::aliexpress_pt_get_image_view( $value );
			if ( isset( $image_view ) ) {
				break;
			}
		}

		return $image_view;
	}

	public static function aliexpress_pt_get_sku_module( $data ) {
		$sku_module = null;
		foreach ( $data as $key => $value ) {
			if ( ! is_array( $value ) ) {
				continue;
			}
			if ( substr( $key, 0, 4 ) === 'sku_' ) {
				if ( isset( $value['type'] ) && $value['type'] === 'sku' ) {
					if ( isset( $value['fields'] ) ) {
						$sku_module = $value['fields'];
						break;
					}
				}
			}
			$sku_module = self::aliexpress_pt_get_sku_module( $value );
			if ( isset( $sku_module ) ) {
				break;
			}
		}

		return $sku_module;
	}

	public static function aliexpress_pt_get_title_module( $data ) {
		$title_module = null;
		foreach ( $data as $key => $value ) {
			if ( ! is_array( $value ) ) {
				continue;
			}
			if ( substr( $key, 0, 12 ) === 'titleBanner_' ) {
				if ( isset( $value['type'] ) && $value['type'] === 'titleBanner' ) {
					if ( isset( $value['fields'] ) ) {
						$title_module = $value['fields'];
						break;
					}
				}
			}
			$title_module = self::aliexpress_pt_get_title_module( $value );
			if ( isset( $title_module ) ) {
				break;
			}
		}

		return $title_module;
	}

	public static function aliexpress_pt_get_action_module( $data ) {
		$action_module = null;
		foreach ( $data as $key => $value ) {
			if ( ! is_array( $value ) ) {
				continue;
			}
			if ( substr( $key, 0, 14 ) === 'actionButtons_' ) {
				if ( isset( $value['type'] ) && $value['type'] === 'actionButtons' ) {
					if ( isset( $value['fields'] ) ) {
						$action_module = $value['fields'];
						break;
					}
				}
			}
			$action_module = self::aliexpress_pt_get_action_module( $value );
			if ( isset( $action_module ) ) {
				break;
			}
		}

		return $action_module;
	}

	public static function chrome_extension_buttons() {
		?>
		<span class="vi-ui positive button labeled icon <?php echo esc_attr( self::set( array( 'connect-chrome-extension', 'hidden' ) ) ) ?>"
		      data-site_url="<?php echo esc_url( site_url() ) ?>">
            <i class="linkify icon"> </i><?php esc_html_e( 'Connect the Extension', 'woo-alidropship' ) ?>
        </span>
		<a target="_blank" href="https://downloads.villatheme.com/?download=alidropship-extension"
		   class="vi-ui positive button labeled icon <?php echo esc_attr( self::set( 'download-chrome-extension' ) ) ?>">
			<i class="external icon"> </i><?php esc_html_e( 'Install Chrome Extension', 'woo-alidropship' ) ?>
		</a>
		<?php
	}

	public static function strtolower( $string ) {
		return function_exists( 'mb_strtolower' ) ? mb_strtolower( $string ) : strtolower( $string );
	}

	public static function get_domain_name() {
		if ( ! empty( $_SERVER['HTTP_HOST'] ) ) {
			$name = $_SERVER['HTTP_HOST'];
		} elseif ( ! empty( $_SERVER['SERVER_NAME'] ) ) {
			$name = $_SERVER['SERVER_NAME'];
		} else {
			$name = self::get_domain_from_url( get_bloginfo( 'url' ) );
		}

		return $name;
	}

	public static function ali_ds_get_sign( $args, $type = 'place_order' ) {
		$return = array(
			'status' => 'error',
			'data'   => '',
			'code'   => '',
		);
		switch ( $type ) {
			case 'get_shipping':
				$url = VI_WOOCOMMERCE_ALIDROPSHIP_GET_SIGNATURE_GET_SHIPPING_URL;
				break;

			case 'get_order':
				$url = VI_WOOCOMMERCE_ALIDROPSHIP_GET_SIGNATURE_GET_ORDER_URL;
				break;

			case 'get_product':
				$url = VI_WOOCOMMERCE_ALIDROPSHIP_GET_SIGNATURE_GET_PRODUCT_URL;
				break;

			case 'get_product_v2':
				$url = VI_WOOCOMMERCE_ALIDROPSHIP_GET_SIGNATURE_GET_PRODUCT_URL_V2;
				break;

			case 'place_order_batch':
				$url = VI_WOOCOMMERCE_ALIDROPSHIP_GET_SIGNATURE_PLACE_ORDER_BATCH_URL;
				break;

			case 'search_product':
				$url = VI_WOOCOMMERCE_ALIDROPSHIP_GET_SIGNATURE_SEARCH_PRODUCT;

				break;
			case 'place_order':

			default:
				$url = VI_WOOCOMMERCE_ALIDROPSHIP_GET_SIGNATURE_PLACE_ORDER_URL;
		}

		$url = apply_filters( 'ald_villatheme_api_url', $url, $type );

		$request = wp_remote_post( $url, array(
			'body'       => $args,
			'user-agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36',
			'timeout'    => 30,
		) );

		if ( ! is_wp_error( $request ) || wp_remote_retrieve_response_code( $request ) === 200 ) {
			$body           = vi_wad_json_decode( $request['body'] );
			$return['code'] = $body['code'];
			$return['data'] = $body['msg'];
			if ( $body['code'] == 200 ) {
				$return['status'] = 'success';
			}
		} else {
			$return['code'] = $request->get_error_code();
			$return['data'] = $request->get_error_message();
		}

		return $return;
	}

	public static function get_ali_orders( $count = true, $status = 'to_order', $limit = 0, $offset = 0 ) {
		$instance = self::get_instance();
		global $wpdb;
		$woocommerce_order_items    = $wpdb->prefix . "woocommerce_order_items";
		$woocommerce_order_itemmeta = $wpdb->prefix . "woocommerce_order_itemmeta";
		$order_status_for_fulfill   = $instance->get_params( 'order_status_for_fulfill' );

		if ( class_exists( '\Automattic\WooCommerce\Utilities\OrderUtil' ) && \Automattic\WooCommerce\Utilities\OrderUtil::custom_orders_table_usage_is_enabled() ) {
			$posts  = $wpdb->prefix . "wc_orders";
			$select = "DISTINCT {$posts}.id";
			$query  = "FROM {$posts} LEFT JOIN {$woocommerce_order_items} ON {$posts}.id={$woocommerce_order_items}.order_id";
			$query  .= " LEFT JOIN {$woocommerce_order_itemmeta} ON {$woocommerce_order_items}.order_item_id={$woocommerce_order_itemmeta}.order_item_id";
			$query  .= " WHERE {$posts}.type='shop_order' AND {$woocommerce_order_itemmeta}.meta_key='_vi_wad_aliexpress_order_id'";
			if ( $order_status_for_fulfill ) {
				$query .= " AND {$posts}.status IN ( '" . implode( "','", $order_status_for_fulfill ) . "' )";
			}
		} else {
			$posts  = $wpdb->prefix . "posts";
			$select = "DISTINCT {$posts}.ID";
			$query  = "FROM {$posts} LEFT JOIN {$woocommerce_order_items} ON {$posts}.ID={$woocommerce_order_items}.order_id";
			$query  .= " LEFT JOIN {$woocommerce_order_itemmeta} ON {$woocommerce_order_items}.order_item_id={$woocommerce_order_itemmeta}.order_item_id";
			$query  .= " WHERE {$posts}.post_type='shop_order' AND {$woocommerce_order_itemmeta}.meta_key='_vi_wad_aliexpress_order_id'";
			if ( $order_status_for_fulfill ) {
				$query .= " AND {$posts}.post_status IN ( '" . implode( "','", $order_status_for_fulfill ) . "' )";
			}
		}


		if ( $status === 'to_order' ) {
			$query .= " AND {$woocommerce_order_itemmeta}.meta_value=''";
		}
//		else {
//			$query = "FROM {$posts} LEFT JOIN {$woocommerce_order_items} ON {$posts}.ID={$woocommerce_order_items}.order_id LEFT JOIN {$woocommerce_order_itemmeta} ON {$woocommerce_order_items}.order_item_id={$woocommerce_order_itemmeta}.order_item_id left JOIN `{$postmeta}` on `{$woocommerce_order_itemmeta}`.`meta_value`=`{$postmeta}`.`post_id` WHERE `{$woocommerce_order_itemmeta}`.`meta_key`='_product_id' and `{$postmeta}`.`meta_key`='_vi_wad_aliexpress_product_id' ";
//		}

		if ( $count ) {
			$query = "SELECT COUNT({$select}) {$query}";

			return $wpdb->get_var( $query );
		} else {
			$query = "SELECT {$select} {$query}";
			if ( $limit ) {
				$query .= " LIMIT {$offset},{$limit}";
			}

			return $wpdb->get_col( $query, 0 );
		}
	}

	/**
	 * @param $property_id
	 * @param $property_value_id
	 *
	 * @return string
	 */
	private static function property_value_id_to_ship_from( $property_id, $property_value_id ) {
		$ship_from = '';
		if ( $property_id == 200007763 ) {
			switch ( $property_value_id ) {
				case 203372089:
					$ship_from = 'PL';
					break;
				case 201336100:
				case 201441035:
					$ship_from = 'CN';
					break;
				case 201336103:
					$ship_from = 'RU';
					break;
				case 100015076:
					$ship_from = 'BE';
					break;
				case 201336104:
					$ship_from = 'ES';
					break;
				case 201336342:
					$ship_from = 'FR';
					break;
				case 201336106:
					$ship_from = 'US';
					break;
				case 201336101:
					$ship_from = 'DE';
					break;
				case 203124901:
					$ship_from = 'UA';
					break;
				case 201336105:
					$ship_from = 'UK';
					break;
				case 201336099:
					$ship_from = 'AU';
					break;
				case 203287806:
					$ship_from = 'CZ';
					break;
				case 201336343:
					$ship_from = 'IT';
					break;
				case 203054831:
					$ship_from = 'TR';
					break;
				case 203124902:
					$ship_from = 'AE';
					break;
				case 100015009:
					$ship_from = 'ZA';
					break;
				case 201336102:
					$ship_from = 'ID';
					break;
				case 202724806:
					$ship_from = 'CL';
					break;
				case 203054829:
					$ship_from = 'BR';
					break;
				case 203124900:
					$ship_from = 'VN';
					break;
				case 203124903:
					$ship_from = 'IL';
					break;
				case 100015000:
					$ship_from = 'SA';
					break;
				case 5581:
					$ship_from = 'KR';
					break;
				default:
			}
		}

		return $ship_from;
	}


	/**
	 * @param $country_code
	 *
	 * @return float|int|string
	 */
	public static function get_ali_tax( $country_code ) {
		$country_code = strtolower( $country_code );
		$rates        = array(
			/*US*/
//			'us' => 10,
			/*New Zealand*/
//			'nz' => 15,
			/*Australia*/
//			'au' => 10,
			/*EU*/
			'at' => 20,
			'be' => 21,
			'cz' => 21,
			'dk' => 25,
			'ee' => 20,
			'fi' => 24,
			'fr' => 20,
			'de' => 19,
			'gr' => 24,
			'hu' => 27,
			'is' => 24,
			'ie' => 23,
			'it' => 22,
			'lv' => 21,
			'lu' => 17,
			'nl' => 21,
			'no' => 25,
			'pl' => 23,
			'pt' => 23,
			'sk' => 20,
			'si' => 22,
			'es' => 21,
			'se' => 25,
			'ch' => 7.7,
			'cy' => 19,
			/*United Kingdom*/
//			'uk' => 20,
		);

		return isset( $rates[ $country_code ] ) ? $rates[ $country_code ] / 100 : '';
	}

	/**
	 * Get exchange rate based on selected API
	 *
	 * @param string $api
	 * @param string $target_currency
	 * @param bool $decimals
	 * @param string $source_currency
	 *
	 * @return bool|int|mixed|void
	 */
	public static function get_exchange_rate( $api = 'google', $target_currency = '', $decimals = false, $source_currency = 'USD' ) {
		if ( $decimals === false ) {
			$decimals = self::get_instance()->get_params( 'exchange_rate_decimals' );
		}
		$rate = false;
		if ( ! $target_currency ) {
			$target_currency = get_option( 'woocommerce_currency' );
		}
		if ( self::strtolower( $target_currency ) === self::strtolower( $source_currency ) ) {
			$rate = 1;
		} else {
			switch ( $api ) {
				case 'google':
					$get_rate = self::get_google_exchange_rate( $target_currency, $source_currency );
					break;
				default:
					$get_rate = array(
						'status' => 'error',
						'data'   => false,
					);
			}
			if ( $get_rate['status'] === 'success' && $get_rate['data'] ) {
				$rate = $get_rate['data'];
			}
			$rate = apply_filters( 'vi_wad_get_exchange_rate', round( $rate, $decimals ), $api );
		}

		return $rate;
	}

	/**
	 * @param $target_currency
	 * @param string $source_currency
	 *
	 * @return array
	 */
	private static function get_google_exchange_rate( $target_currency, $source_currency = 'USD' ) {
		$response = array(
			'status' => 'error',
			'data'   => false,
		);
		$url      = 'https://www.google.com/async/currency_v2_update?vet=12ahUKEwjfsduxqYXfAhWYOnAKHdr6BnIQ_sIDMAB6BAgFEAE..i&ei=kgAGXN-gDJj1wAPa9ZuQBw&yv=3&async=source_amount:1,source_currency:' . self::get_country_freebase( $source_currency ) . ',target_currency:' . self::get_country_freebase( $target_currency ) . ',lang:en,country:us,disclaimer_url:https%3A%2F%2Fwww.google.com%2Fintl%2Fen%2Fgooglefinance%2Fdisclaimer%2F,period:5d,interval:1800,_id:knowledge-currency__currency-v2-updatable,_pms:s,_fmt:pc';
		$request  = wp_remote_get(
			$url, array(
				'user-agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36',
				'timeout'    => 10
			)
		);
		if ( ! is_wp_error( $request ) || wp_remote_retrieve_response_code( $request ) === 200 ) {
			preg_match( '/data-exchange-rate=\"(.+?)\"/', $request['body'], $match );
			if ( is_array( $match ) && count( $match ) > 1 ) {
				$response['status'] = 'success';
				$response['data']   = $match[1];
			} else {
				$response['data'] = esc_html__( 'Preg_match fails', 'woocommerce-alidropship' );
			}
		} else {
			$response['data'] = $request->get_error_message();
		}

		return $response;
	}

	private static function get_country_freebase( $country_code = '' ) {
		$countries = array(
			"AED" => "/m/02zl8q",
			"AFN" => "/m/019vxc",
			"ALL" => "/m/01n64b",
			"AMD" => "/m/033xr3",
			"ANG" => "/m/08njbf",
			"AOA" => "/m/03c7mb",
			"ARS" => "/m/024nzm",
			"AUD" => "/m/0kz1h",
			"AWG" => "/m/08s1k3",
			"AZN" => "/m/04bq4y",
			"BAM" => "/m/02lnq3",
			"BBD" => "/m/05hy7p",
			"BDT" => "/m/02gsv3",
			"BGN" => "/m/01nmfw",
			"BHD" => "/m/04wd20",
			"BIF" => "/m/05jc3y",
			"BMD" => "/m/04xb8t",
			"BND" => "/m/021x2r",
			"BOB" => "/m/04tkg7",
			"BRL" => "/m/03385m",
			"BSD" => "/m/01l6dm",
			"BTC" => "/m/05p0rrx",
			"BWP" => "/m/02nksv",
			"BYN" => "/m/05c9_x",
			"BZD" => "/m/02bwg4",
			"CAD" => "/m/0ptk_",
			"CDF" => "/m/04h1d6",
			"CHF" => "/m/01_h4b",
			"CLP" => "/m/0172zs",
			"CNY" => "/m/0hn4_",
			"COP" => "/m/034sw6",
			"CRC" => "/m/04wccn",
			"CUC" => "/m/049p2z",
			"CUP" => "/m/049p2z",
			"CVE" => "/m/06plyy",
			"CZK" => "/m/04rpc3",
			"DJF" => "/m/05yxn7",
			"DKK" => "/m/01j9nc",
			"DOP" => "/m/04lt7_",
			"DZD" => "/m/04wcz0",
			"EGP" => "/m/04phzg",
			"ETB" => "/m/02_mbk",
			"EUR" => "/m/02l6h",
			"FJD" => "/m/04xbp1",
			"GBP" => "/m/01nv4h",
			"GEL" => "/m/03nh77",
			"GHS" => "/m/01s733",
			"GMD" => "/m/04wctd",
			"GNF" => "/m/05yxld",
			"GTQ" => "/m/01crby",
			"GYD" => "/m/059mfk",
			"HKD" => "/m/02nb4kq",
			"HNL" => "/m/04krzv",
			"HRK" => "/m/02z8jt",
			"HTG" => "/m/04xrp0",
			"HUF" => "/m/01hfll",
			"IDR" => "/m/0203sy",
			"ILS" => "/m/01jcw8",
			"INR" => "/m/02gsvk",
			"IQD" => "/m/01kpb3",
			"IRR" => "/m/034n11",
			"ISK" => "/m/012nk9",
			"JMD" => "/m/04xc2m",
			"JOD" => "/m/028qvh",
			"JPY" => "/m/088n7",
			"KES" => "/m/05yxpb",
			"KGS" => "/m/04k5c6",
			"KHR" => "/m/03_m0v",
			"KMF" => "/m/05yxq3",
			"KRW" => "/m/01rn1k",
			"KWD" => "/m/01j2v3",
			"KYD" => "/m/04xbgl",
			"KZT" => "/m/01km4c",
			"LAK" => "/m/04k4j1",
			"LBP" => "/m/025tsrc",
			"LKR" => "/m/02gsxw",
			"LRD" => "/m/05g359",
			"LSL" => "/m/04xm1m",
			"LYD" => "/m/024xpm",
			"MAD" => "/m/06qsj1",
			"MDL" => "/m/02z6sq",
			"MGA" => "/m/04hx_7",
			"MKD" => "/m/022dkb",
			"MMK" => "/m/04r7gc",
			"MOP" => "/m/02fbly",
			"MRO" => "/m/023c2n",
			"MUR" => "/m/02scxb",
			"MVR" => "/m/02gsxf",
			"MWK" => "/m/0fr4w",
			"MXN" => "/m/012ts8",
			"MYR" => "/m/01_c9q",
			"MZN" => "/m/05yxqw",
			"NAD" => "/m/01y8jz",
			"NGN" => "/m/018cg3",
			"NIO" => "/m/02fvtk",
			"NOK" => "/m/0h5dw",
			"NPR" => "/m/02f4f4",
			"NZD" => "/m/015f1d",
			"OMR" => "/m/04_66x",
			"PAB" => "/m/0200cp",
			"PEN" => "/m/0b423v",
			"PGK" => "/m/04xblj",
			"PHP" => "/m/01h5bw",
			"PKR" => "/m/02svsf",
			"PLN" => "/m/0glfp",
			"PYG" => "/m/04w7dd",
			"QAR" => "/m/05lf7w",
			"RON" => "/m/02zsyq",
			"RSD" => "/m/02kz6b",
			"RUB" => "/m/01hy_q",
			"RWF" => "/m/05yxkm",
			"SAR" => "/m/02d1cm",
			"SBD" => "/m/05jpx1",
			"SCR" => "/m/01lvjz",
			"SDG" => "/m/08d4zw",
			"SEK" => "/m/0485n",
			"SGD" => "/m/02f32g",
			"SLL" => "/m/02vqvn",
			"SOS" => "/m/05yxgz",
			"SRD" => "/m/02dl9v",
			"SSP" => "/m/08d4zw",
			"STD" => "/m/06xywz",
			"SZL" => "/m/02pmxj",
			"THB" => "/m/0mcb5",
			"TJS" => "/m/0370bp",
			"TMT" => "/m/0425kx",
			"TND" => "/m/04z4ml",
			"TOP" => "/m/040qbv",
			"TRY" => "/m/04dq0w",
			"TTD" => "/m/04xcgz",
			"TWD" => "/m/01t0lt",
			"TZS" => "/m/04s1qh",
			"UAH" => "/m/035qkb",
			"UGX" => "/m/04b6vh",
			"USD" => "/m/09nqf",
			"UYU" => "/m/04wblx",
			"UZS" => "/m/04l7bl",
			"VEF" => "/m/021y_m",
			"VND" => "/m/03ksl6",
			"XAF" => "/m/025sw2b",
			"XCD" => "/m/02r4k",
			"XOF" => "/m/025sw2q",
			"XPF" => "/m/01qyjx",
			"YER" => "/m/05yxwz",
			"ZAR" => "/m/01rmbs",
			"ZMW" => "/m/0fr4f"
		);
		if ( $country_code ) {
			return isset( $countries[ $country_code ] ) ? $countries[ $country_code ] : '';
		} else {
			return $countries;
		}
	}

	public static function is_ald_table() {
		if ( self::$is_ald_table !== null ) {
			return self::$is_ald_table;
		}

		$deleted_old_data = get_option( 'ald_deleted_old_posts_data' );
		if ( $deleted_old_data ) {
			self::$is_ald_table = true;
		} else {
			self::$is_ald_table = self::get_instance()->get_params( 'ald_table' );
		}

		return self::$is_ald_table;
	}
}