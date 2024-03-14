<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

include_once 'class-wc-order-export-order-fields.php';
include_once 'class-wc-order-export-order-product-fields.php';
include_once 'class-wc-order-export-order-coupon-fields.php';

class WC_Order_Export_Data_Extractor {
	use WOE_Core_Extractor;
	
	static $statuses;
	static $countries;
	static $prices_include_tax;
	static $current_order;
	static $object_type = 'shop_order';
	static $has_order_stats;
	static $export_subcategories_separator;
	static $export_line_categories_separator;
	static $export_itemmeta_values_separator;
	static $export_custom_fields_separator;
	static $track_sql_queries = false;
	static $sql_queries;
	static $operator_must_check_values = array( 'NOT LIKE', 'LIKE','>', '<', '>=', '<=' );
	const  HUGE_SHOP_ORDERS    = 1000;// more than 1000 orders
	const  HUGE_SHOP_PRODUCTS  = 1000;// more than 1000 products
	const  HUGE_SHOP_CUSTOMERS = 1000;// more than 1000 users
	const  HUGE_SHOP_COUPONS   = 1000;// more than 1000 coupons

	public static function get_order_custom_fields() {
		global $wpdb;
		$transient_key = 'woe_get_order_custom_fields_result';

		$fields = get_transient( $transient_key );
		if ( $fields === false ) {
			$total_orders = $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->posts}  WHERE post_type = '" . self::$object_type . "'" );
			//small shop , take all orders
			if ( $total_orders < self::HUGE_SHOP_ORDERS ) {
				$fields = $wpdb->get_col( "SELECT DISTINCT meta_key FROM {$wpdb->posts} INNER JOIN {$wpdb->postmeta} ON {$wpdb->posts}.ID = {$wpdb->postmeta}.post_id WHERE post_type = '" . self::$object_type . "'" );
			} else { // we have a lot of orders, take last good orders, upto 1000
				$limit = self::HUGE_SHOP_ORDERS;
				$order_ids   = $wpdb->get_col( "SELECT  ID FROM {$wpdb->posts} WHERE post_type = '" . self::$object_type . "' ORDER BY post_date DESC LIMIT {$limit}" );
				$order_ids[] = 0; // add fake zero
				$order_ids   = join( ",", $order_ids );
				$fields      = $wpdb->get_col( "SELECT DISTINCT meta_key FROM {$wpdb->postmeta}  WHERE post_id IN ($order_ids)" );
			}
			sort( $fields );
			set_transient( $transient_key, $fields, 60 ); //valid for a minute
		}

		return apply_filters( 'woe_get_order_custom_fields', $fields );
	}

	public static function get_product_itemmeta() {
		global $wpdb;
		$transient_key = 'woe_get_product_itemmeta_result';

		$metas = get_transient( $transient_key );
		if ( $metas === false ) {
			$total_orders = $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->posts}  WHERE post_type = '" . self::$object_type . "'" );
			if ( $total_orders < self::HUGE_SHOP_ORDERS ) {
				// WP internal table, take all metas
				$metas = $wpdb->get_col( "SELECT DISTINCT meta.meta_key FROM {$wpdb->prefix}woocommerce_order_itemmeta meta inner join {$wpdb->prefix}woocommerce_order_items item on item.order_item_id=meta.order_item_id and item.order_item_type = 'line_item' " );
				sort( $metas );
				set_transient( $transient_key, $metas, 60 ); //valid for a minute
			} else {
				$limit = self::HUGE_SHOP_ORDERS;
				$order_ids = $wpdb->get_col( "SELECT  ID FROM {$wpdb->posts} WHERE post_type = '" . self::$object_type . "' ORDER BY post_date DESC LIMIT {$limit}" );
				$order_ids   = join( ",", $order_ids );
				$metas = $wpdb->get_col( "SELECT DISTINCT meta.meta_key FROM {$wpdb->prefix}woocommerce_order_itemmeta meta inner join {$wpdb->prefix}woocommerce_order_items item on item.order_item_id=meta.order_item_id and item.order_item_type = 'line_item' WHERE item.order_id IN ($order_ids)" );
				sort( $metas );
				set_transient( $transient_key, $metas, 60 ); //valid for a minute
			}
		}

		return apply_filters( 'woe_get_product_itemmeta', $metas );
	}

	public static function get_order_shipping_items() {
		global $wpdb;
		$transient_key = 'woe_get_order_shipping_items_result';

		$metas = false; //get_transient( $transient_key );
		if ( $metas === false ) {
			$total_orders = $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->posts}  WHERE post_type = '" . self::$object_type . "'" );
			if ( $total_orders < self::HUGE_SHOP_ORDERS ) {
				// WP internal table, take all metas
				$metas = $wpdb->get_col( "SELECT DISTINCT order_item_name FROM {$wpdb->prefix}woocommerce_order_items WHERE order_item_type = 'shipping' AND order_item_name <> '' " );
				sort( $metas );
				set_transient( $transient_key, $metas, 60 ); //valid for a minute

			} else {
				$limit = self::HUGE_SHOP_ORDERS;
				$order_ids = $wpdb->get_col( "SELECT  ID FROM {$wpdb->posts} WHERE post_type = '" . self::$object_type . "' ORDER BY post_date DESC LIMIT {$limit}" );
				$order_ids   = join( ",", $order_ids );
				$metas = $wpdb->get_col( "SELECT DISTINCT order_item_name FROM {$wpdb->prefix}woocommerce_order_items WHERE order_item_type = 'shipping' AND order_id IN ($order_ids) AND order_item_name <> '' " );
				sort( $metas );
				set_transient( $transient_key, $metas, 60 ); //valid for a minute
			}
		}

		return apply_filters( 'woe_get_order_shipping_items', $metas );
	}

	public static function get_order_fee_items() {
		global $wpdb;
		$transient_key = 'woe_get_order_fee_items_result';

		$metas = get_transient( $transient_key );
		if ( $metas === false ) {
			$total_orders = $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->posts}  WHERE post_type = '" . self::$object_type . "'" );
			if ( $total_orders < self::HUGE_SHOP_ORDERS ) {
				// WP internal table, take all metas
				$metas = $wpdb->get_col( "SELECT DISTINCT order_item_name FROM {$wpdb->prefix}woocommerce_order_items WHERE order_item_type = 'fee' AND order_item_name <> '' " );
				sort( $metas );
				set_transient( $transient_key, $metas, 60 ); //valid for a minute
			} else {
				$limit = self::HUGE_SHOP_ORDERS;
				$order_ids = $wpdb->get_col( "SELECT  ID FROM {$wpdb->posts} WHERE post_type = '" . self::$object_type . "' ORDER BY post_date DESC LIMIT {$limit}" );
				$order_ids   = join( ",", $order_ids );
				$metas = $wpdb->get_col( "SELECT DISTINCT order_item_name FROM {$wpdb->prefix}woocommerce_order_items WHERE order_item_type = 'fee' AND order_id IN ($order_ids) AND order_item_name <> '' " );
				sort( $metas );
				set_transient( $transient_key, $metas, 60 ); //valid for a minute
			}
		}

		return apply_filters( 'woe_get_order_fee_items', $metas );
	}

	public static function get_order_tax_items() {
		global $wpdb;
		$transient_key = 'woe_get_order_tax_items_result';

		$metas = get_transient( $transient_key );
		if ( $metas === false ) {
			$total_orders = $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->posts}  WHERE post_type = '" . self::$object_type . "'" );
			if ( $total_orders < self::HUGE_SHOP_ORDERS ) {
				// WP internal table, take all metas
				$metas = $wpdb->get_col( "SELECT DISTINCT order_item_name FROM {$wpdb->prefix}woocommerce_order_items WHERE order_item_type = 'tax' AND order_item_name <> '' " );
				sort( $metas );
				set_transient( $transient_key, $metas, 60 ); //valid for a minute
			} else {
				$limit = self::HUGE_SHOP_ORDERS;
				$order_ids = $wpdb->get_col( "SELECT  ID FROM {$wpdb->posts} WHERE post_type = '" . self::$object_type . "' ORDER BY post_date DESC LIMIT {$limit}" );
				$order_ids   = join( ",", $order_ids );
				$metas = $wpdb->get_col( "SELECT DISTINCT order_item_name FROM {$wpdb->prefix}woocommerce_order_items WHERE order_item_type = 'tax' AND order_id IN ($order_ids) AND order_item_name <> '' " );
				sort( $metas );
				set_transient( $transient_key, $metas, 60 ); //valid for a minute
			}
		}

		return apply_filters( 'woe_get_order_tax_items', $metas );
	}

	public static function sql_get_product_ids( $settings ) {
		global $wpdb;

		$product_where = self::sql_build_product_filter( $settings );

		$wc_order_items_meta        = "{$wpdb->prefix}woocommerce_order_itemmeta";
		$left_join_order_items_meta = $order_items_meta_where = array();

		// filter by product
		if ( $product_where ) {
			$left_join_order_items_meta[] = "LEFT JOIN $wc_order_items_meta  AS orderitemmeta_product ON orderitemmeta_product.order_item_id = order_items.order_item_id";
			$order_items_meta_where[]     = " (orderitemmeta_product.meta_key IN ('_variation_id', '_product_id')   $product_where)";
		} else {
			$left_join_order_items_meta[] = "LEFT JOIN $wc_order_items_meta  AS orderitemmeta_product ON orderitemmeta_product.order_item_id = order_items.order_item_id";
			$order_items_meta_where[]     = " orderitemmeta_product.meta_key IN ('_variation_id', '_product_id')";
		}

		//by attrbutes in woocommerce_order_itemmeta
		if ( $settings['product_attributes'] ) {
			$attrs        = self::get_product_attributes();
			$names2fields = array_flip( $attrs );
			$filters      = self::parse_complex_pairs( $settings['product_attributes']);
			foreach ( $filters as $operator => $fields ) {
				foreach ( $fields as $field => $values ) {
					$field = $names2fields[ $field ];
					if ( $values ) {
						$left_join_order_items_meta[] = "LEFT JOIN $wc_order_items_meta  AS `orderitemmeta_{$field}` ON `orderitemmeta_{$field}`.order_item_id = order_items.order_item_id";
						if ( $operator == 'IN' OR $operator == 'NOT IN' ) {
							$values                   = self::sql_subset( $values );
							$order_items_meta_where[] = " (`orderitemmeta_{$field}`.meta_key='$field'  AND `orderitemmeta_{$field}`.meta_value $operator  ($values) ) ";
						} elseif ( in_array( $operator, self::$operator_must_check_values ) ) {
							$pairs = array();
							foreach ( $values as $v ) {
								$pairs[] = self::operator_compare_field_and_value( "`orderitemmeta_{$field}`.meta_value",
									$operator, $v );
							}
							$pairs                    = join( "OR", $pairs );
							$order_items_meta_where[] = " (`orderitemmeta_{$field}`.meta_key='$field'  AND  ($pairs) ) ";
						}
					}
				}// values
			}// operators
		}

		//by attrbutes in woocommerce_order_itemmeta
		if ( $settings['product_itemmeta'] ) {
			foreach ( $settings['product_itemmeta'] as $value ) {
				$settings['product_itemmeta'][] = esc_html( $value );
			}

			$filters  = self::parse_complex_pairs( $settings['product_itemmeta'] );
			foreach ( $filters as $operator => $fields ) {
				foreach ( $fields as $field => $values ) {
					;
					if ( $values ) {
						$left_join_order_items_meta[] = "LEFT JOIN $wc_order_items_meta  AS `orderitemmeta_{$field}` ON `orderitemmeta_{$field}`.order_item_id = order_items.order_item_id";
						if ( $operator == 'IN' OR $operator == 'NOT IN' ) {
							$values                   = self::sql_subset( $values );
							$order_items_meta_where[] = " (`orderitemmeta_{$field}`.meta_key='$field'  AND `orderitemmeta_{$field}`.meta_value $operator  ($values) ) ";
						} elseif ( in_array( $operator, self::$operator_must_check_values ) ) {
							$pairs = array();
							foreach ( $values as $v ) {
								$pairs[] = self::operator_compare_field_and_value( "`orderitemmeta_{$field}`.meta_value",
									$operator, $v );
							}
							$pairs                    = join( "OR", $pairs );
							$order_items_meta_where[] = " (`orderitemmeta_{$field}`.meta_key='$field'  AND  ($pairs) ) ";
						}
					}// values
				}
			}// operators
		}

		$orders_where = array();
		self::apply_order_filters_to_sql( $orders_where, $settings );
		if ( $orders_where ) {
			$left_join_order_items_meta[] = "LEFT JOIN {$wpdb->posts}  AS `orders` ON `orders`.ID  = order_items.order_id";
			$order_items_meta_where[]     = "( " . join( " AND ", $orders_where ) . " )";
		}

		$order_items_meta_where = join( apply_filters('woe_product_itemmeta_operator', " AND "), $order_items_meta_where );
		if ( $order_items_meta_where ) {
			$order_items_meta_where = " AND " . $order_items_meta_where;
		}
		$left_join_order_items_meta = join( "  ", $left_join_order_items_meta );

		$order_items_meta_where = apply_filters( "woe_sql_get_product_ids_where", $order_items_meta_where, $settings );
		
		// final sql from WC tables
		if ( ! $order_items_meta_where ) {
			return false;
		}

		$sql = apply_filters( "woe_sql_get_product_ids", "SELECT DISTINCT p_id FROM
						(SELECT order_items.order_item_id as order_item_id, MAX(CONVERT(orderitemmeta_product.meta_value ,UNSIGNED INTEGER)) as p_id FROM {$wpdb->prefix}woocommerce_order_items as order_items
							$left_join_order_items_meta
							WHERE order_item_type='line_item' $order_items_meta_where GROUP BY order_item_id
						) AS temp", $settings );
		if ( self::$track_sql_queries ) {
			self::$sql_queries[] = $sql;
		}

		return $sql;
	}

	public static function sql_get_order_ids_Ver1( $settings ) {
		global $wpdb;

		// deep level !
		$product_where = self::sql_build_product_filter( $settings );

		$wc_order_items_meta        = "{$wpdb->prefix}woocommerce_order_itemmeta";
		$left_join_order_items_meta = $order_items_meta_where = array();

		// filter by product
		if ( $product_where ) {
			$left_join_order_items_meta[] = "LEFT JOIN $wc_order_items_meta  AS orderitemmeta_product ON orderitemmeta_product.order_item_id = order_items.order_item_id";
			$order_items_meta_where[]     = " (orderitemmeta_product.meta_key IN ('_variation_id', '_product_id') $product_where)";
		}


		//by attrbutes in woocommerce_order_itemmeta
		if ( $settings['product_attributes'] ) {
			$attrs        = self::get_product_attributes();
			$names2fields = @array_flip( $attrs );
			$filters      = self::parse_complex_pairs( $settings['product_attributes']);
			foreach ( $filters as $operator => $fields ) {
				foreach ( $fields as $field => $values ) {
					$field = $names2fields[ $field ];
					if ( $values ) {
						$left_join_order_items_meta[] = "LEFT JOIN $wc_order_items_meta  AS `orderitemmeta_{$field}` ON `orderitemmeta_{$field}`.order_item_id = order_items.order_item_id";
						if ( $operator == 'IN' OR $operator == 'NOT IN' ) {
							$values                   = self::sql_subset( $values );
							$order_items_meta_where[] = " (`orderitemmeta_{$field}`.meta_key='$field'  AND `orderitemmeta_{$field}`.meta_value $operator  ($values) ) ";
						} elseif ( in_array( $operator, self::$operator_must_check_values ) ) {
							$pairs = array();
							foreach ( $values as $v ) {
								$pairs[] = self::operator_compare_field_and_value( "`orderitemmeta_{$field}`.meta_value",
									$operator, $v );
							}
							$pairs                    = join( "OR", $pairs );
							$order_items_meta_where[] = " (`orderitemmeta_{$field}`.meta_key='$field'  AND  ($pairs) ) ";
						}
					}// values
				}
			}// operators
		}

		//by attrbutes in woocommerce_order_itemmeta
		if ( $settings['product_itemmeta'] ) {
			foreach ( $settings['product_itemmeta'] as $value ) {
				$settings['product_itemmeta'][] = esc_html( $value );
			}

			$filters  = self::parse_complex_pairs( $settings['product_itemmeta']);
			foreach ( $filters as $operator => $fields ) {
				foreach ( $fields as $field => $values ) {
					;
					if ( $values ) {
						$left_join_order_items_meta[] = "LEFT JOIN $wc_order_items_meta  AS `orderitemmeta_{$field}` ON `orderitemmeta_{$field}`.order_item_id = order_items.order_item_id";
						if ( $operator == 'IN' OR $operator == 'NOT IN' ) {
							$values                   = self::sql_subset( $values );
							$order_items_meta_where[] = " (`orderitemmeta_{$field}`.meta_key='$field'  AND `orderitemmeta_{$field}`.meta_value $operator  ($values) ) ";
						} elseif ( in_array( $operator, self::$operator_must_check_values ) ) {
							$pairs = array();
							foreach ( $values as $v ) {
								$pairs[] = self::operator_compare_field_and_value( "`orderitemmeta_{$field}`.meta_value",
									$operator, $v, $field );
							}
							$pairs                    = join( "OR", $pairs );
							$order_items_meta_where[] = " (`orderitemmeta_{$field}`.meta_key='$field'  AND  ($pairs) ) ";
						}
					}// values
				}
			}// operators
		}

		$order_items_meta_where = join( " AND ", $order_items_meta_where );
		if ( $order_items_meta_where ) {
			$order_items_meta_where = " AND " . $order_items_meta_where;
		}
		$left_join_order_items_meta = join( "  ", $left_join_order_items_meta );


		// final sql from WC tables
		$order_items_where = "";
		if ( $order_items_meta_where ) {
			$order_items_where = " AND orders.ID IN (SELECT DISTINCT order_items.order_id FROM {$wpdb->prefix}woocommerce_order_items as order_items
				$left_join_order_items_meta
				WHERE order_item_type='line_item' $order_items_meta_where )";
		}

		// by coupons
		if ( ! empty( $settings['any_coupon_used'] ) ) {
			$order_items_where .= " AND orders.ID IN (SELECT DISTINCT order_coupons.order_id FROM {$wpdb->prefix}woocommerce_order_items as order_coupons
					WHERE order_coupons.order_item_type='coupon')";
		} elseif ( ! empty( $settings['coupons'] ) ) {
			$values            = self::sql_subset( $settings['coupons'] );
			$order_items_where .= " AND orders.ID IN (SELECT DISTINCT order_coupons.order_id FROM {$wpdb->prefix}woocommerce_order_items as order_coupons
					WHERE order_coupons.order_item_type='coupon'  AND order_coupons.order_item_name in ($values) )";
		}
		// shipping methods
		if ( ! empty( $settings['shipping_methods'] ) ) {
			$zone_values = $zone_instance_values = $itemname_values = array();
			foreach ( $settings['shipping_methods'] as $value ) {
				if ( preg_match( '#^order_item_name:(.+)#', $value, $m ) ) {
					$itemname_values[] = $m[1];
				} else {
					$zone_values[] = $value;
					// for zones -- take instance_id!
					$m = explode( ":", $value );
					if ( count( $m ) > 1 ) {
						$zone_instance_values[] = $m[1];
					}
				}
			}

			// where by type!
			$ship_where = array();
			if ( $zone_values ) {
				$zone_values  = self::sql_subset( $zone_values );
				$ship_where[] = " (shipping_itemmeta.meta_key='method_id' AND shipping_itemmeta.meta_value IN ($zone_values) ) ";
			}
			if ( $zone_instance_values ) { //since WooCommerce 3.4+  instead of $zone_values
				$zone_instance_values = self::sql_subset( $zone_instance_values );
				$ship_where[]         = " (shipping_itemmeta.meta_key='instance_id' AND shipping_itemmeta.meta_value IN ($zone_instance_values ) ) ";
			}
			if ( $itemname_values ) {
				$itemname_values = self::sql_subset( $itemname_values );
				$ship_where[]    = " (order_shippings.order_item_name IN ( $itemname_values ) ) ";
			}
			$ship_where = join( ' OR ', $ship_where );

			//done 
			$order_items_where .= " AND orders.ID IN (SELECT order_shippings.order_id FROM {$wpdb->prefix}woocommerce_order_items as order_shippings
						LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta AS shipping_itemmeta ON  shipping_itemmeta.order_item_id = order_shippings.order_item_id
						WHERE order_shippings.order_item_type='shipping' AND $ship_where )";
		}

		// check item names ?
		if ( ! empty( $settings['item_names'] ) ) {
			$order_items_name_where = array();

			$order_items_name_joins = array();

			$pos = 0;

			$filters = self::parse_complex_pairs( $settings['item_names'], array( 'coupon', 'fee', 'line_item', 'shipping', 'tax' ) );
			foreach ( $filters as $operator => $fields ) {
				foreach ( $fields as $field => $values ) {
					if ( $values ) {
						if ( $operator == 'IN' OR $operator == 'NOT IN' ) {

							$values = self::sql_subset( $values );

							if (!$pos) {
							    $order_items_name_where[]  = "items.order_item_type='$field' AND items.order_item_name $operator ($values)";
							} else {
							    $order_items_name_joins[]  = "JOIN {$wpdb->prefix}woocommerce_order_items as items_{$pos} ON items.order_id = items_{$pos}.order_id AND items_{$pos}.order_item_type='$field' AND items_{$pos}.order_item_name $operator ($values)";

							}
						} elseif ( in_array( $operator, self::$operator_must_check_values ) ) {

							$pairs = array();
							foreach ( $values as $v ) {
								if (!$pos) {
								    $pairs[] = self::operator_compare_field_and_value( "items.order_item_name", $operator, $v );
								} else {
								    $pairs[] = self::operator_compare_field_and_value( "items_{$pos}.order_item_name", $operator, $v );
								}
							}
							$pairs = join( "OR", $pairs );

							if (!$pos) {
							    $order_items_name_where[]  = "items.order_item_type='$field' AND ({$pairs})";
							} else {
							    $order_items_name_joins[]  = "JOIN {$wpdb->prefix}woocommerce_order_items as items_{$pos} ON items.order_id = items_{$pos}.order_id AND items_{$pos}.order_item_type='$field' AND ({$pairs})";
							}

						}

						$pos++;

					}//if values
				}
			}

			$order_items_name_where_sql = join( " OR ", $order_items_name_where );

			$order_items_name_joins_sql = implode(' ', $order_items_name_joins);

			$where_item_names = " SELECT items.order_id FROM {$wpdb->prefix}woocommerce_order_items as items {$order_items_name_joins_sql} WHERE {$order_items_name_where_sql}";

			$order_items_where .= " AND orders.ID IN ($where_item_names)";
		}

		// check item metadata
		if ( ! empty( $settings['item_metadata'] ) ) {

			$order_items_metadata_joins = array();
			$pos = 1;

			$filters = self::parse_complex_pairs( $settings['item_metadata'] );
			foreach ( $filters as $operator => $fields ) {
				foreach ( $fields as $field => $values ) {
					if ( $values ) {
						self::extract_item_type_and_key( $field, $type, $key );
						$order_items_metadata_joins[] = "LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta AS meta_{$pos} ON meta_{$pos}.order_item_id = items.order_item_id AND items.order_item_type='$type' AND meta_{$pos}.meta_key='$key'";
						$key = esc_sql( $key );
						if ( $operator == 'IN' OR $operator == 'NOT IN' ) {

							$values = self::sql_subset( $values );
							$order_item_metadata_where [] = " ( meta_{$pos}.meta_value $operator ($values) ) ";
						} elseif ( $operator == 'NOT SET' ) {
							$order_item_metadata_where [] = " ( meta_{$pos}.meta_value IS NULL ) ";
						} elseif ( $operator == 'IS SET' ) {
							$order_item_metadata_where [] = " ( meta_{$pos}.meta_value IS NOT NULL ) ";
						} elseif ( in_array( $operator, self::$operator_must_check_values ) ) {
							$pairs = array();
							foreach ( $values as $v ) {
								$pairs[] = self::operator_compare_field_and_value( "meta_{$pos}.meta_value", $operator, $v );
							}
							$pairs = join( "OR", $pairs );

							$order_item_metadata_where[] = " ( $pairs ) ";
						}

						$pos++;

					}//if values
				}
			}
			$order_item_metadata_where_sql = join( apply_filters("woe_item_metadata_operator", " AND "), $order_item_metadata_where );

			$order_items_metadata_joins_sql = implode(' ', $order_items_metadata_joins);

			$where_item_metadata = " SELECT order_id FROM {$wpdb->prefix}woocommerce_order_items AS items {$order_items_metadata_joins_sql} WHERE {$order_item_metadata_where_sql}";

			$order_items_where .= " AND orders.ID IN ($where_item_metadata)";
		}


		$left_join_order_meta_order_id = self::$object_type === 'shop_order' ? 'ID' : 'post_parent';

		// pre top
		$left_join_order_meta = $order_meta_where = $user_meta_where = $inner_join_user_meta = array();
		//add filter by custom fields in order

		if ( $settings['sort'] ) {
			$sort_field = $settings['sort'];

			if ( ! in_array( $settings['sort'], WC_Order_Export_Engine::get_wp_posts_fields() ) ) {
				$pos = "sort";
				$left_join_order_meta[] = "LEFT JOIN {$wpdb->postmeta} AS ordermeta_cf_{$pos} " .
				                          "ON ordermeta_cf_{$pos}.post_id = orders.ID AND ordermeta_cf_{$pos}.meta_key='{$sort_field}'";
			}
		}

		if ( $settings['export_unmarked_orders'] ) {
			$pos                    = "export_unmarked_orders";
			$field                  = "woe_order_exported" . apply_filters("woe_exported_postfix",'');
			$left_join_order_meta[] = "LEFT JOIN {$wpdb->postmeta} AS ordermeta_cf_{$pos} ON ordermeta_cf_{$pos}.post_id = orders.ID AND ordermeta_cf_{$pos}.meta_key='$field'";
			$order_meta_where []    = " ( ordermeta_cf_{$pos}.meta_value IS NULL ) ";
		}

		if ( $settings['order_custom_fields'] ) {
			$filters  = self::parse_complex_pairs( $settings['order_custom_fields'] );
			$pos      = 1;
			$order_custom_fields_where = array();
			foreach ( $filters as $operator => $fields ) {
				foreach ( $fields as $field => $values ) {
					if ( $values ) {
						$left_join_order_meta[] = "LEFT JOIN {$wpdb->postmeta} AS ordermeta_cf_{$pos} ON ordermeta_cf_{$pos}.post_id = orders.ID AND ordermeta_cf_{$pos}.meta_key='$field'";
						if ( $operator == 'IN' OR $operator == 'NOT IN' ) {
							$values              = self::sql_subset( $values );
							$order_custom_fields_where [] = " ( ordermeta_cf_{$pos}.meta_value $operator ($values) ) ";
						} elseif ( $operator == 'NOT SET' ) {
							$order_custom_fields_where [] = " ( ordermeta_cf_{$pos}.meta_value IS NULL ) ";
						} elseif ( $operator == 'IS SET' ) {
							$order_custom_fields_where [] = " ( ordermeta_cf_{$pos}.meta_value IS NOT NULL ) ";
						} elseif ( in_array( $operator, self::$operator_must_check_values ) ) {
							$pairs = array();
							foreach ( $values as $v ) {
								$pairs[] = self::operator_compare_field_and_value( "`ordermeta_cf_{$pos}`.meta_value",
									$operator, $v , $field );
							}
							$pairs              = join( "OR", $pairs );
							$order_custom_fields_where[] = " ( $pairs ) ";
						}
						$pos ++;
					}//if values
				}
			}
			if($order_custom_fields_where) {
				if( $custom_sql = apply_filters("woe_sql_get_order_ids_custom_order_fields_callback", "", $order_custom_fields_where) )
					$order_meta_where[] = $custom_sql;
				else
					$order_meta_where[] = "( " . join( apply_filters("woe_sql_get_order_ids_custom_order_fields_operator", " AND "), $order_custom_fields_where) . " )";
			}		
		}
		if ( ! empty( $settings['user_custom_fields'] ) ) {
			$filters  = self::parse_complex_pairs( $settings['user_custom_fields'] );
			$pos      = 1;
			foreach ( $filters as $operator => $fields ) {
				foreach ( $fields as $field => $values ) {
					$inner_join_user_meta[] = "LEFT JOIN {$wpdb->usermeta} AS usermeta_cf_{$pos} ON usermeta_cf_{$pos}.user_id = {$wpdb->users}.ID AND usermeta_cf_{$pos}.meta_key='$field'";
					if ( $values ) {
						if ( $operator == 'NOT SET' ) {
							$user_meta_where[] = " ( usermeta_cf_{$pos}.meta_value IS NULL ) ";
						} elseif ( $operator == 'IS SET' ) {
							$user_meta_where[] = " ( usermeta_cf_{$pos}.meta_value IS NOT NULL ) ";
						} elseif ( $operator == 'IN' OR $operator == 'NOT IN' ) {
							$values            = self::sql_subset( $values );
							$user_meta_where[] = " ( usermeta_cf_{$pos}.meta_value $operator ($values) ) ";
						} elseif ( in_array( $operator, self::$operator_must_check_values ) ) {
							$pairs = array();
							foreach ( $values as $v ) {
								$pairs[] = self::operator_compare_field_and_value( "`usermeta_cf_{$pos}`.meta_value",
									$operator, $v, $field );
							}
							$pairs             = join( "OR", $pairs );
							$user_meta_where[] = " ( $pairs ) ";
						}
						$pos ++;
					}//if values
				}
			}
		}
		if ( $settings['shipping_locations'] ) {
			$filters = self::parse_complex_pairs( $settings['shipping_locations'],
				array( 'city', 'state', 'postcode', 'country' ), 'lower_filter_label' );
			foreach ( $filters as $operator => $fields ) {
				foreach ( $fields as $field => $values ) {
					$values = self::sql_subset( $values );
					if ( $values ) {
						$left_join_order_meta[] = "LEFT JOIN {$wpdb->postmeta} AS ordermeta_{$field} ON ordermeta_{$field}.post_id = orders.{$left_join_order_meta_order_id}";
						$order_meta_where []    = " (ordermeta_{$field}.meta_key='_shipping_$field'  AND ordermeta_{$field}.meta_value $operator ($values)) ";
					}
				}
			}
		}
		if ( $settings['billing_locations'] ) {
			$filters = self::parse_complex_pairs( $settings['billing_locations'],
				array( 'city', 'state', 'postcode', 'country' ), 'lower_filter_label' );
			foreach ( $filters as $operator => $fields ) {
				foreach ( $fields as $field => $values ) {
					$values = self::sql_subset( $values );
					if ( $values ) {
						$left_join_order_meta[] = "LEFT JOIN {$wpdb->postmeta} AS ordermeta_{$field} ON ordermeta_{$field}.post_id = orders.{$left_join_order_meta_order_id}";
						$order_meta_where []    = " (ordermeta_{$field}.meta_key='_billing_$field'  AND ordermeta_{$field}.meta_value $operator ($values)) ";
					}
				}
			}
		}

		// users
		$user_ids                    = array();
		$user_ids_ui_filters_applied = false;
		if ( ! empty( $settings['user_names'] ) ) {
			$user_ids          = array_filter( array_map( "intval", $settings['user_names'] ) );
			$values            = self::sql_subset( $user_ids );
			$user_meta_where[] = "( {$wpdb->users}.ID IN ($values) )";
		}
		//roles
		if ( ! empty( $settings['user_roles'] ) ) {
			$metakey                = $wpdb->get_blog_prefix() . 'capabilities';
			$inner_join_user_meta[] = "INNER JOIN {$wpdb->usermeta} AS usermeta_cf_role ON usermeta_cf_role.user_id = {$wpdb->users}.ID AND usermeta_cf_role.meta_key='$metakey'";

			$roles_where = array();
			foreach ( $settings['user_roles'] as $role ) {
				$roles_where[] = "( usermeta_cf_role.meta_value LIKE '%\"$role\"%' )";
			}
			$user_meta_where[] = "(" . join( ' OR ', $roles_where ) . ")";
		}
		if ( ! empty( $user_meta_where ) AND ! empty( $inner_join_user_meta ) ) {
			$user_meta_where      = join( ' AND ', $user_meta_where );
			$inner_join_user_meta = join( ' ', $inner_join_user_meta );
			$sql                  = "SELECT DISTINCT ID FROM {$wpdb->users} $inner_join_user_meta WHERE $user_meta_where";
			if ( self::$track_sql_queries ) {
				self::$sql_queries[] = $sql;
			}
			$user_ids                    = $wpdb->get_col( $sql );
			$user_ids_ui_filters_applied = true;
		}
		$user_ids = apply_filters( "woe_sql_get_customer_ids", $user_ids, $settings );
		if ( empty( $user_ids ) AND $user_ids_ui_filters_applied ) {
			$order_meta_where [] = "0"; // user filters failed
		}

		//apply filter
		if ( $user_ids ) {
			$field  = 'customer_user';
			$values = self::sql_subset( $user_ids );
			if ( $values ) {
				$left_join_order_meta[] = "LEFT JOIN {$wpdb->postmeta} AS ordermeta_{$field} ON ordermeta_{$field}.post_id = orders.{$left_join_order_meta_order_id}";
				$order_meta_where []    = " (ordermeta_{$field}.meta_key='_customer_user'  AND ordermeta_{$field}.meta_value in ($values)) ";
			}
		}

		// payment methods
		if ( ! empty( $settings['payment_methods'] ) ) {
			$field  = 'payment_method';
			$values = self::sql_subset( $settings['payment_methods'] );

			$left_join_order_meta[] = "LEFT JOIN {$wpdb->postmeta} AS ordermeta_{$field} ON ordermeta_{$field}.post_id = orders.{$left_join_order_meta_order_id}";
			$order_meta_where []    = " (ordermeta_{$field}.meta_key='_{$field}'  AND ordermeta_{$field}.meta_value in ($values)) ";
		}

        if ( ! empty( $settings['sub_start_from_date'] ) || ! empty( $settings['sub_start_to_date'] ) ) {
            $field = 'schedule_start';
            $left_join_order_meta[] = "LEFT JOIN {$wpdb->postmeta} AS ordermeta_{$field} ON ordermeta_{$field}.post_id = orders.ID";
            $order_meta_where []    = self::get_date_meta_for_subscription_filters( $field, $settings['sub_start_from_date'], $settings['sub_start_to_date'] );
        }


        if ( ! empty( $settings['sub_end_from_date'] ) || ! empty( $settings['sub_end_to_date'] ) ) {
            $field = 'schedule_end';
            $left_join_order_meta[] = "LEFT JOIN {$wpdb->postmeta} AS ordermeta_{$field} ON ordermeta_{$field}.post_id = orders.ID";
            $order_meta_where []    = self::get_date_meta_for_subscription_filters( $field, $settings['sub_end_from_date'], $settings['sub_end_to_date'] );
        }

        if ( ! empty( $settings['sub_next_paym_from_date'] ) || ! empty( $settings['sub_next_paym_to_date'] ) ) {
            $field = 'schedule_next_payment';
            $left_join_order_meta[] = "LEFT JOIN {$wpdb->postmeta} AS ordermeta_{$field} ON ordermeta_{$field}.post_id = orders.ID";
            $order_meta_where []    = self::get_date_meta_for_subscription_filters( $field, $settings['sub_next_paym_from_date'], $settings['sub_next_paym_to_date'] );
        }

		$order_meta_where = join( " AND ",
			apply_filters( "woe_sql_get_order_ids_order_meta_where", $order_meta_where ) );

		if ( $order_meta_where !== '' ) {
			$order_meta_where = " AND " . $order_meta_where;
		}
		$left_join_order_meta = join( "  ",
			apply_filters( "woe_sql_get_order_ids_left_joins", $left_join_order_meta ) );


		//top_level
		$where = array( 1 );
		self::apply_order_filters_to_sql( $where, $settings );
		$where     = apply_filters( 'woe_sql_get_order_ids_where', $where, $settings );
		$order_sql = apply_filters( 'woe_sql_get_order_ids_where_AND', join( " AND ", $where ), $settings );

		//setup order types to work with
		$order_types = array( "'" . self::$object_type . "'" );
		if ( $settings['export_refunds'] ) {
			$order_types[] = "'shop_order_refund'";
		}
		$order_types = join( ",", apply_filters( "woe_sql_order_types", $order_types ) );

		$sql = apply_filters( "woe_sql_get_order_ids", "SELECT " . apply_filters( "woe_sql_get_order_ids_fields", "orders.ID AS order_id" ) . " FROM {$wpdb->posts} AS orders
			{$left_join_order_meta}
			WHERE orders.post_type in ( $order_types) AND $order_sql $order_meta_where $order_items_where", $settings );

		if ( self::$track_sql_queries ) {
			self::$sql_queries[] = $sql;
		}

		//die($sql);
		return $sql;
	}

	private static function add_date_filter( &$where, &$where_meta, $date_field, $value ) {
		if ( $date_field == 'date_paid' OR $date_field == 'date_completed' ) // 3.0+ uses timestamp
		{
			$where_meta[] = "(order_$date_field.meta_value>0 AND order_$date_field.meta_value $value )";
		} elseif ( $date_field == 'paid_date' OR $date_field == 'completed_date' ) // previous versions use mysql datetime
		{
			$where_meta[] = "(order_$date_field.meta_value<>'' AND order_$date_field.meta_value " . $value . ")";
		} else {
			$where[] = "orders.post_" . $date_field . $value;
		}
	}

	private static function apply_order_filters_to_sql( &$where, $settings ) {
		global $wpdb;

		if ( ! empty( $settings['order_ids'] ) ) {
			$order_ids = $settings['order_ids'];

			if ( is_array( $settings['order_ids'] ) && count( array_filter( array_map( 'is_numeric', $order_ids ) ) ) === count( $order_ids ) ) {
				$order_ids_str = self::sql_subset( $order_ids );
				if ( $order_ids_str ) {
					$where[] = "orders.ID IN ($order_ids_str)";
				}
			}
		} else {
            if ( trim( $settings['from_order_id'] ) ) {
                  $where[] = "orders.ID >= " . intval($settings['from_order_id']);
            }
            if ( trim( $settings['to_order_id'] ) ) {
                  $where[] = "orders.ID <= " . intval($settings['to_order_id']);
            }
		}

		//default filter by date
		if ( ! isset( $settings['export_rule_field'] ) ) {
			$settings['export_rule_field'] = 'modified';
		}

		$date_field     = $settings['export_rule_field'];
		$use_timestamps = ( $date_field == 'date_paid' OR $date_field == 'date_completed' );
		//rename this field for 2.6 and less
		if ( true /*! method_exists( 'WC_Order', "get_date_completed" ) */) {
			$use_timestamps = false;
			if ( $date_field == 'date_paid' ) {
				$date_field = 'paid_date';
			} elseif ( $date_field == 'date_completed' ) {
				$date_field = 'completed_date';
			}
		}
		$where_meta = array();

		// export and date rule

		foreach ( self::get_date_range( $settings, true, $use_timestamps ) as $date ) {
			self::add_date_filter( $where, $where_meta, $date_field, $date );
		}

		// end export and date rule

		if ( $settings['statuses'] ) {
			$values = self::sql_subset( $settings['statuses'] );
			if ( $values ) {
				$where[] = "orders.post_status in ($values)";
			}
		}

		//for date_paid or date_completed
		if ( $where_meta ) {
			$where_meta = join( " AND ", $where_meta );
			$where[]    = "orders.ID  IN ( SELECT post_id FROM {$wpdb->postmeta} AS order_$date_field WHERE order_$date_field.meta_key ='_$date_field' AND $where_meta)";
		}

		// skip child orders?
		if ( $settings['skip_suborders'] AND ! $settings['export_refunds'] ) {
			$where[] = "orders.post_parent=0";
		}

		// Skip drafts and deleted
		$where[] = "orders.post_status NOT in ('auto-draft','trash')";
	}


	public static function get_order_shipping_tax_refunded( $order_id ) {
		global $wpdb;
		$refund_ship_taxes = $wpdb->get_var( $wpdb->prepare( "
			SELECT SUM( order_itemmeta.meta_value )
			FROM {$wpdb->prefix}woocommerce_order_itemmeta AS order_itemmeta
			INNER JOIN $wpdb->posts AS posts ON ( posts.post_type = 'shop_order_refund' AND posts.post_parent = %d )
			INNER JOIN {$wpdb->prefix}woocommerce_order_items AS order_items ON ( order_items.order_id = posts.ID AND order_items.order_item_type = 'tax' )
			WHERE order_itemmeta.order_item_id = order_items.order_item_id
			AND order_itemmeta.meta_key IN ( 'shipping_tax_amount')
		", $order_id ) );

		return abs( $refund_ship_taxes );
	}

	public static function get_customer_order( $user, $order_meta, $first_or_last ) {
		global $wpdb;

		if( isset($user->ID)) {
			$meta_key = "_customer_user";
			$meta_value = $user->ID;
		} elseif( !empty($order_meta["_billing_email"]) ) {
			$meta_key = "_billing_email";
			$meta_value = $order_meta["_billing_email"];
		} else {
			return false;
		}
		
		if ( 'first' === $first_or_last ) {
			$direction = 'ASC';
		} else if ( 'last' === $first_or_last ) {
			$direction = 'DESC';
		} else {
			return false;
		}
		

		$order = $wpdb->get_var(
		// phpcs:disable WordPress.DB.PreparedSQL.NotPrepared
			"SELECT posts.ID
			FROM $wpdb->posts AS posts
			LEFT JOIN {$wpdb->postmeta} AS meta on posts.ID = meta.post_id
			WHERE meta.meta_key = '" . $meta_key ."'
			AND   meta.meta_value = '" . esc_sql( $meta_value ) . "'
			AND   posts.post_type = 'shop_order'
			AND   posts.post_status IN ( '" . implode( "','", array_map( 'esc_sql', array_keys( wc_get_order_statuses() ) ) ) . "' )
			ORDER BY posts.ID {$direction}"
		// phpcs:enable
		);

		if ( ! $order ) {
			return false;
		}

		return wc_get_order( absint( $order ) );
	}

	/**
	 * @param string $billing_email
	 *
	 * @return int
	 */
	public static function get_customer_order_count_by_email( $billing_email ) {
		global $wpdb;
		
		$statuses = "'" . implode( "','", array_map( 'esc_sql', array_keys( wc_get_order_statuses() ) ) ) . "'";
		
		if( self::$has_order_stats) 
			return self::get_customer_order_stats(self::$current_order, $statuses, "COUNT(*)");

		//SLOW way
		$count = $wpdb->get_var(
			// phpcs:disable WordPress.DB.PreparedSQL.NotPrepared
			"SELECT COUNT(*)
			FROM $wpdb->posts as posts
			LEFT JOIN {$wpdb->postmeta} AS meta ON posts.ID = meta.post_id
			LEFT JOIN {$wpdb->postmeta} AS meta2 ON posts.ID = meta2.post_id
			WHERE   meta.meta_key = '_billing_email'
			AND     meta2.meta_key = '_customer_user' AND meta2.meta_value = '0'
			AND     posts.post_type = 'shop_order'
			AND     posts.post_status IN ( $statuses )
			AND     meta.meta_value = '" . esc_sql( $billing_email ) . "'"
			// phpcs:enable
		);

		return is_numeric( $count ) ? intval( $count ) : 0;
	}

	/**
	 * @param string $billing_email
	 *
	 * @return float
	 */
	public static function get_customer_total_spent_by_email( $billing_email ) {
		global $wpdb;
		
		$statuses = implode( ',', array_map( function ( $status ) {
			return sprintf( "'wc-%s'", esc_sql( $status ) );
		}, wc_get_is_paid_statuses() ) );
		
		if( self::$has_order_stats) 
			return self::get_customer_order_stats(self::$current_order, $statuses, "SUM(total_sales)");

		//SLOW way
		$spent    = $wpdb->get_var(
			// phpcs:disable WordPress.DB.PreparedSQL.NotPrepared
			"SELECT SUM(meta2.meta_value)
			FROM $wpdb->posts as posts
			LEFT JOIN {$wpdb->postmeta} AS meta ON posts.ID = meta.post_id
			LEFT JOIN {$wpdb->postmeta} AS meta2 ON posts.ID = meta2.post_id
			LEFT JOIN {$wpdb->postmeta} AS meta3 ON posts.ID = meta3.post_id
			WHERE   meta.meta_key       = '_billing_email'
			AND     meta.meta_value     = '" . esc_sql( $billing_email ) . "'
			AND     meta3.meta_key = '_customer_user' AND meta3.meta_value = '0'
			AND     posts.post_type     = 'shop_order'
			AND     posts.post_status   IN ( $statuses )
			AND     meta2.meta_key      = '_order_total'"
			// phpcs:enable
		);

		return is_numeric( $spent ) ? floatval( $spent ) : 0;
	}	
	
	/**
	 * @param in $customer_id
	 * @param string $billing_email
	 *
	 * @return float
	 */
	public static function get_customer_paid_orders_count( $customer_id, $billing_email ) {
		global $wpdb;
		
		$statuses = implode( ',', array_map( function ( $status ) {
			return sprintf( "'wc-%s'", esc_sql( $status ) );
		}, wc_get_is_paid_statuses() ) );
		
		if( self::$has_order_stats) 
			return self::get_customer_order_stats(self::$current_order, $statuses, "COUNT(*)");
		
		//SLOW way
		if( $customer_id ) {
			$key = '_customer_user';
			$value = $customer_id;
			$guest_join = "";
			$guest_where = "";
		} else { 
			$key = '_billing_email';
			$value = $billing_email;
			$guest_join = "LEFT JOIN {$wpdb->postmeta} AS meta2 ON posts.ID = meta2.post_id";
			$guest_where = "AND meta2.meta_key = '_customer_user' AND meta2.meta_value = '0'";
		}

		return $wpdb->get_var(
				"SELECT COUNT(*)
				FROM $wpdb->posts as posts
				LEFT JOIN {$wpdb->postmeta} AS meta ON posts.ID = meta.post_id
				$guest_join
				WHERE   meta.meta_key = '$key'
				$guest_where
				AND     posts.post_type = 'shop_order'
				AND     posts.post_status IN ( $statuses )
				AND     meta.meta_value = '" . esc_sql( $value ) . "'"
		);
	}	
	
	/**
	 * @param string $billing_email
	 *
	 * @return float
	 */
	public static function get_customer_order_stats( $order, $statuses, $operation){
		global $wpdb;
		$customer_id = intval ( $wpdb->get_var( $wpdb->prepare("SELECT customer_id FROM {$wpdb->prefix}wc_order_stats WHERE order_id = %d", $order->get_id() ) ) );
		if( !$customer_id) return 0;
		$result = $wpdb->get_var("SELECT $operation FROM {$wpdb->prefix}wc_order_stats WHERE customer_id = $customer_id  AND status IN ( $statuses )");
		if(!$result) $result  = 0; // NULL for SUM!
		return $result;
	}
}
