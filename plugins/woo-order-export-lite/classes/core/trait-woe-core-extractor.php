<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

trait WOE_Core_Extractor {
	//Common

	// to parse "item_type:meta_key" strings
	public static function extract_item_type_and_key( $meta_key, &$type, &$key ) {
		$t    = explode( ":", $meta_key );
		$type = array_shift( $t );
		$key  = join( ":", $t );
	}

	public static function get_user_custom_fields() {
		global $wpdb;
		$transient_key = 'woe_get_user_custom_fields_result';

		$fields = get_transient( $transient_key );
		if ( $fields === false ) {
			$total_users = $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->users}" );
			if ( $total_users < self::HUGE_SHOP_CUSTOMERS ) {
				$fields = $wpdb->get_col( "SELECT DISTINCT meta_key FROM {$wpdb->usermeta}" );
			} else { // we have a lot of users, so take last users, upto 1000
				$user_ids = $wpdb->get_col( "SELECT ID FROM {$wpdb->users} ORDER BY ID DESC LIMIT 1000" );
				$user_ids = join( ",", $user_ids );
				$fields   = $wpdb->get_col( "SELECT DISTINCT meta_key FROM {$wpdb->usermeta}  WHERE user_id IN ($user_ids)" );
			}
			sort( $fields );
			set_transient( $transient_key, $fields, 60 ); //valid for a minute
		}

		return apply_filters( 'woe_get_user_custom_fields', $fields );
	}

	public static function get_product_attributes() {
		global $wpdb;

		$attrs = array();

		// WC internal table , skip hidden and attributes
		$wc_fields = $wpdb->get_results( "SELECT attribute_name,attribute_label FROM {$wpdb->prefix}woocommerce_attribute_taxonomies" );
		foreach ( $wc_fields as $f ) {
			$attrs[ 'pa_' . $f->attribute_name ] = $f->attribute_label;
		}


		// WP internal table, take all attributes
		$wp_fields = $wpdb->get_col( "SELECT DISTINCT meta_key FROM {$wpdb->postmeta} INNER JOIN {$wpdb->posts} ON {$wpdb->postmeta}.post_id = {$wpdb->posts}.ID
                                            WHERE meta_key LIKE 'attribute\_%' AND post_type = 'product_variation'" );
		foreach ( $wp_fields as $attr ) {
			$attr = str_replace( "attribute_", "", $attr );
			if ( substr( $attr, 0, 3 ) == 'pa_' ) // skip attributes from WC table
			{
				continue;
			}
			$name           = str_replace( "-", " ", $attr );
			$name           = ucwords( $name );
			$attrs[ $attr ] = $name;
		}
		asort( $attrs );

		return apply_filters( 'woe_get_product_attributes', $attrs );
	}

	public static function get_product_taxonomies() {
		global $wpdb;

		$attrs = array();

		if ( function_exists( "wc_get_attribute_taxonomies" ) ) {
			$wc_attrs = wc_get_attribute_taxonomies();
			foreach ( $wc_attrs as $attr ) {
				$attrs[ "pa_" . $attr->attribute_name ] = "pa_" . $attr->attribute_name;
			}
		}

		// WP internal table, take all taxonomies for products
		$wp_fields = $wpdb->get_col( "SELECT DISTINCT taxonomy FROM {$wpdb->term_relationships}
					JOIN {$wpdb->term_taxonomy} ON {$wpdb->term_taxonomy}.term_taxonomy_id = {$wpdb->term_relationships}.term_taxonomy_id
					WHERE {$wpdb->term_relationships}.object_id IN  (SELECT DISTINCT ID FROM {$wpdb->posts} WHERE post_type = 'product' OR post_type='product_variation')" );
		foreach ( $wp_fields as $attr ) {
			$attrs[ $attr ] = $attr;
		}
		asort( $attrs );

		return apply_filters( 'woe_get_product_taxonomies', $attrs );
	}

	public static function get_product_custom_fields() {
		global $wpdb;
		$transient_key = 'woe_get_product_custom_fields_result';

		$fields = get_transient( $transient_key );
		if ( $fields === false ) {
			//rewrite for huge # of products
			$total_products = $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->posts}  WHERE  post_type = 'product' OR post_type='product_variation' " );
			//small shop , take all orders
			if ( $total_products < self::HUGE_SHOP_PRODUCTS ) {
				$fields = $wpdb->get_col( "SELECT DISTINCT meta_key FROM {$wpdb->posts} INNER JOIN {$wpdb->postmeta} ON {$wpdb->posts}.ID = {$wpdb->postmeta}.post_id WHERE post_type = 'product' OR post_type='product_variation' " );
			} else { // we have a lot of orders, take last good orders, upto 1000
				$product_ids   = $wpdb->get_col( "SELECT  ID FROM {$wpdb->posts} WHERE post_type IN('product','product_variation')  ORDER BY post_date DESC LIMIT 1000" );
				$product_ids[] = 0; // add fake zero
				$product_ids   = join( ",", $product_ids );
				$fields        = $wpdb->get_col( "SELECT DISTINCT meta_key FROM {$wpdb->postmeta}  WHERE post_id IN ($product_ids)" );
			}
			sort( $fields );
			set_transient( $transient_key, $fields, 60 ); //valid for a minute
		}

		return apply_filters( 'woe_get_product_custom_fields', $fields );
	}

	//For ENGINE
	private static function parse_pairs( $pairs, $valid_types, $mode = '' ) {
		$pair_types = array();
		foreach ( $pairs as $pair ) {
			list( $filter_type, $filter_value ) = array_map( 'trim', explode( "=", trim( $pair ) ) );
			if ( $mode == 'lower_filter_label' ) {
				$filter_type = strtolower( $filter_type );
			} // Country=>country for locations
			if ( ! in_array( $filter_type, $valid_types ) ) {
				continue;
			}
			if ( ! isset( $pair_types[ $filter_type ] ) ) {
				$pair_types[ $filter_type ] = array();
			}
			$pair_types[ $filter_type ][] = $filter_value;
		}

		return $pair_types;
	}

	public static function parse_complex_pairs( $pairs, $valid_types = false, $mode = '' ) {
		$pair_types = array();
		$delimiters = array(
			'NOT SET' => 'NOT SET',
			'IS SET'  => 'IS SET',
			'NOT LIKE'    => 'NOT LIKE',
			'LIKE'    => 'LIKE',
			'<>'      => 'NOT IN',
			'>='      => '>=',
			'<='      => '<=',
			'>'       => '>',
			'<'       => '<',
			'='       => 'IN',
		);
		$single_ops = array( 'NOT SET', 'IS SET' );

		foreach ( $pairs as $pair ) {
			$pair      = trim( $pair );
			$op        = '';
			$single_op = false;
			foreach ( $delimiters as $delim => $op_seek ) {
				$t         = explode( $delim, $pair );
				$single_op = in_array( $delim, $single_ops );
				if ( count( $t ) == 2 ) {
					$op = $op_seek;
					break;
				}
			}
			if ( ! $op ) {
				continue;
			}
			if ( $single_op ) {
				$t[1] = '';
			}

			list( $filter_type, $filter_value ) = array_map( "trim", $t );
			$empty = __( 'empty', 'woo-order-export-lite' );
			if ( $empty == $filter_value ) {
				$filter_value = '';
			}

			if ( $mode == 'lower_filter_label' ) {
				$filter_type = strtolower( $filter_type );
			} // Country=>country for locations

			if ( $valid_types AND ! in_array( $filter_type, $valid_types ) ) {
				continue;
			}

			$filter_type = addslashes( $filter_type );
			if ( ! isset( $pair_types[ $op ] ) ) {
				$pair_types[ $op ] = array();
			}
			if ( ! isset( $pair_types[ $op ] [ $filter_type ] ) ) {
				$pair_types[ $op ] [ $filter_type ] = array();
			}
			$pair_types[ $op ][ $filter_type ][] = addslashes( $filter_value );
		}

		return $pair_types;
	}

	private static function sql_subset( $arr_values ) {
		$values = array();
		foreach ( $arr_values as $s ) {
			$values[] = "'$s'";
		}

		return join( ",", $values );
	}


	public static function sql_get_order_ids( $settings ) {
		//$settings['product_categories'] = array(119);
		//$settings['products'] = array(4554);
		//$settings['shipping_locations'] = array("city=cityS","city=alex","postcode=12345");
		//$settings['product_attributes'] = array("pa_material=glass");
		return self::sql_get_order_ids_Ver1( $settings );
	}


	public static function sql_get_filtered_product_list( $settings ) {
		global $wpdb;

		// has exact products?
		if ( $settings['products'] ) {
			;// do nothing 
		} elseif ( empty( $settings['product_vendors'] ) AND empty( $settings['product_custom_fields'] ) ) {
			$settings['products'] = array();
		} else {
			$product_where = array( "1" );

			//by owners
			$settings['product_vendors'] = apply_filters( 'woe_sql_get_product_vendor_ids',
				$settings['product_vendors'], $settings );
			if ( $settings['product_vendors'] ) {
				$values          = self::sql_subset( $settings['product_vendors'] );
				$product_where[] = " products.post_author in ($values)";
			}

			//by custom fields in Product
			$product_meta_where     = "";
			$left_join_product_meta = "";
			if ( $settings['product_custom_fields'] ) {
				$left_join_product_meta = $product_meta_where = array();
				$filters                = self::parse_complex_pairs( $settings['product_custom_fields']);
				$pos                    = 1;
				foreach ( $filters as $operator => $fields ) {
					foreach ( $fields as $field => $values ) {
						if ( $values ) {
							$left_join_product_meta[] = "LEFT JOIN {$wpdb->postmeta} AS productmeta_cf_{$pos} ON productmeta_cf_{$pos}.post_id = products.ID AND productmeta_cf_{$pos}.meta_key='$field'";
							if ( $operator == 'IN' OR $operator == 'NOT IN' ) {
								$values               = self::sql_subset( $values );
								$product_meta_where[] = " productmeta_cf_{$pos}.meta_value $operator ($values) ";
							} elseif ( $operator == 'NOT SET' ) {
							    $product_meta_where [] = " productmeta_cf_{$pos}.meta_value IS NULL ";
							} elseif ( $operator == 'IS SET' ) {
							    $product_meta_where [] = " productmeta_cf_{$pos}.meta_value IS NOT NULL ";
							} elseif ( in_array( $operator, self::$operator_must_check_values ) ) {
								$pairs = array();
								foreach ( $values as $v ) {
									$pairs[] = self::operator_compare_field_and_value( "`productmeta_cf_{$pos}`.meta_value",
										$operator, $v, $field );
								}
								$pairs                = join( apply_filters("woe_product_custom_fields_operator", " OR "), $pairs );
								$product_meta_where[] = " ($pairs) ";
							}
							$pos ++;
						}//if values
					}
				}

				if ( $filters ) {
					$product_where[]        = join( " AND ", $product_meta_where );
					$left_join_product_meta = join( "  ", $left_join_product_meta );
				}
			}
			//done
			$product_where        = join( " AND ", $product_where );
			$sql                  = "SELECT DISTINCT ID FROM {$wpdb->posts} AS products $left_join_product_meta  WHERE products.post_type in ('product','product_variation') AND products.post_status<>'trash' AND $product_where ";
			$settings['products'] = $wpdb->get_col( $sql );
			if ( empty( $settings['products'] ) ) // failed condition!
			{
				$settings['products'] = array( 0 );
			}
		}

		//  we have to use variations , if user sets product attributes
		if ( $settings['products'] AND $settings['product_attributes'] ) {
			$values               = self::sql_subset( $settings['products'] );
			$sql                  = "SELECT DISTINCT ID FROM {$wpdb->posts} AS products WHERE products.post_type in ('product','product_variation') AND products.post_status<>'trash' AND post_parent<>0 AND post_parent IN ($values)";
			$settings['products'] = $wpdb->get_col( $sql );
			if ( empty( $settings['products'] ) ) // failed condition!
			{
				$settings['products'] = array( 0 );
			}
		}
		if ( ! empty( $sql ) AND self::$track_sql_queries ) {
			self::$sql_queries[] = $sql;
		}

		return apply_filters( 'woe_sql_adjust_products', $settings['products'], $settings );
	}

	public static function sql_build_product_filter( $settings ) {
		global $wpdb;

		//custom taxonomies
		$taxonomy_where = "";
		if ( $settings['product_taxonomies'] ) {
			$attrs        = self::get_product_taxonomies();
			$names2fields = array_flip( $attrs );
			$filters      = self::parse_complex_pairs( $settings['product_taxonomies']);
			$taxonomy_where = array();
			//print_r($filters );die();
			foreach ( $filters as $operator => $fields ) {
				foreach ( $fields as $label => $values ) {
                    if (isset($names2fields[ $label ])) {
                        $field  = $names2fields[ $label ];
                        $values = self::sql_subset( $values );
                        if ( $values ) {
                            $label = esc_sql( $label );

                            if ($operator == 'NOT SET') {
                                $taxonomy_where[] = "NOT ( orderitemmeta_product.meta_key IN('_product_id') AND orderitemmeta_product.meta_value IN (SELECT  object_id FROM {$wpdb->term_relationships} AS `{$field}_rel`
							    INNER JOIN {$wpdb->term_taxonomy} AS `{$field}_cat` ON `{$field}_cat`.term_taxonomy_id = `{$field}_rel`.term_taxonomy_id
							    WHERE `{$field}_cat`.taxonomy='$label' AND  `{$field}_cat`.term_id IN (SELECT term_id FROM {$wpdb->terms} ) ))";
                            } elseif ($operator == 'IS SET') {
                                $taxonomy_where[] = "( orderitemmeta_product.meta_key IN('_product_id') AND orderitemmeta_product.meta_value IN (SELECT  object_id FROM {$wpdb->term_relationships} AS `{$field}_rel`
							    INNER JOIN {$wpdb->term_taxonomy} AS `{$field}_cat` ON `{$field}_cat`.term_taxonomy_id = `{$field}_rel`.term_taxonomy_id
							    WHERE `{$field}_cat`.taxonomy='$label' AND  `{$field}_cat`.term_id IN (SELECT term_id FROM {$wpdb->terms} ) ))";
                            } else {
                                $taxonomy_where[] = "( orderitemmeta_product.meta_key IN('_product_id') AND orderitemmeta_product.meta_value  $operator (SELECT  object_id FROM {$wpdb->term_relationships} AS `{$field}_rel`
							    INNER JOIN {$wpdb->term_taxonomy} AS `{$field}_cat` ON `{$field}_cat`.term_taxonomy_id = `{$field}_rel`.term_taxonomy_id
							    WHERE `{$field}_cat`.taxonomy='$label' AND  `{$field}_cat`.term_id IN (SELECT term_id FROM {$wpdb->terms} WHERE name IN ($values) ) ))";
                            }
                        }
                    }
				}
			}
			if( $taxonomy_where )
				$taxonomy_where = "AND ( " . join( apply_filters( "woe_sql_taxonomies_where_operator", "AND" ), $taxonomy_where) ." )";
		}

		$product_category_where = "";
		if ( $settings['product_categories'] ) {
			$cat_ids = array( 0 );
			foreach ( $settings['product_categories'] as $cat_id ) {
				$cat_ids[] = $cat_id;
				foreach ( get_term_children( $cat_id, 'product_cat' ) as $child_id ) {
					$cat_ids[] = $child_id;
				}
			}
			$cat_ids                = join( ',', $cat_ids );
			$product_category_where = "SELECT  DISTINCT object_id FROM {$wpdb->term_relationships} AS product_in_cat
						LEFT JOIN {$wpdb->term_taxonomy} AS product_category ON product_category.term_taxonomy_id = product_in_cat.term_taxonomy_id
						WHERE product_category.term_id IN ($cat_ids) 
					";
			// get products and variations!
			$product_category_where = "AND orderitemmeta_product.meta_value IN
				(
					SELECT DISTINCT ID FROM {$wpdb->posts} AS product_category_variations WHERE post_parent<>0 AND post_parent IN ($product_category_where)
					UNION
					$product_category_where
				)
				";
		}

		$settings['products'] = self::sql_get_filtered_product_list( $settings );

		// deep level still
		$exact_product_where = '';
		if ( $settings['products'] ) {
			$values = self::sql_subset( $settings['products'] );
			if ( $values ) {
				$exact_product_where = "AND orderitemmeta_product.meta_value IN ($values)";
			}
		}

		$exclude_product_where = '';
		if ( $settings['exclude_products'] ) {
			$values = self::sql_subset( $settings['exclude_products'] );
			if ( $values ) {
				$exclude_product_where = "AND (orderitemmeta_product.meta_key = '_product_id' AND orderitemmeta_product.meta_value NOT IN ($values))";
			}
		}

		$product_where = join( " ",
			array_filter( array( $taxonomy_where, $product_category_where, $exact_product_where, $exclude_product_where ) ) );

		//skip empty values
		if ( $product_where ) {
			$product_where = "AND orderitemmeta_product.meta_value<>'0' " . $product_where;
		}

		return $product_where;
	}

	static function operator_compare_field_and_value( $field, $operator, $value, $public_fieldname='' ) {
		$value = esc_sql($value);
		if ( $operator == "LIKE" OR $operator == "NOT LIKE") {
			$value = (substr($value,0,1)=="^") ? substr($value,1)."%" : "%$value%";
		} else { // compare numbers!
			$type = apply_filters( "woe_compare_field_cast_to_type", "signed", $field, $operator, $value, $public_fieldname);
			$field = "cast($field as $type)";
		}
		return " $field $operator '$value' ";
	}
	
    private static function get_date_meta_for_subscription_filters( $field, $date_from, $date_to ) {
        $order_meta_where_parts[] = "ordermeta_{$field}.meta_key='_{$field}'";

        if ( ! empty( $date_from ) ) {
            $subsc_from = WC_Order_Export_Data_Extractor::format_date_to_day_start( $date_from );
            $order_meta_where_parts[] = " CAST(ordermeta_{$field}.meta_value AS DATETIME) >= '{$subsc_from}'";
        }

        if ( ! empty( $date_to ) ) {
            $subsc_to = WC_Order_Export_Data_Extractor::format_date_to_day_end( $date_to );
            $order_meta_where_parts[] = " CAST(ordermeta_{$field}.meta_value AS DATETIME) <= '{$subsc_to}'";
        }

        $order_meta_where_parts = join( " AND ", $order_meta_where_parts );

        return " ( $order_meta_where_parts ) ";
    }

	public static function is_datetime_timestamp( $ts ) {
		return $ts % ( 24 * 3600 ) > 0;
	}

    public static function format_date_to_day_start( $date ) {
        $ts = strtotime( $date );
        if ( self::is_datetime_timestamp( $ts ) ) {
            $from_date = date( 'Y-m-d H:i:s', $ts );
        } else {
            $from_date = date( 'Y-m-d', $ts ) . " 00:00:00";
        }
        return $from_date;
    }

    public static function format_date_to_day_end( $date ) {
        $ts = strtotime( $date );
        if ( self::is_datetime_timestamp( $ts ) ) {
            $to_date = date( 'Y-m-d H:i:s', $ts );
        } else {
            $to_date = date( 'Y-m-d', $ts ) . " 23:59:59";
        }

        return $to_date;
    }

	public static function get_date_range( $settings, $is_for_sql, $use_timestamps = false , $force_utc = false ) {
		$result = array();
		$diff_utc = current_time( "timestamp" ) - current_time( "timestamp", 1 );

		// fixed date range 
		if ( ! empty( $settings['from_date'] ) OR ! empty( $settings['to_date'] ) ) {
			if ( $settings['from_date'] ) {
		                $from_date = self::format_date_to_day_start( $settings['from_date'] );
				if ( $is_for_sql ) {
					if ( $use_timestamps ) {
						$from_date = mysql2date( 'G', $from_date );
						$from_date -= $diff_utc;
					} elseif( $force_utc )
						$from_date = self::convert_date_to_utc( $from_date, $diff_utc );
					$from_date = sprintf( ">='%s'", $from_date );
				}
				$result['from_date'] = $from_date;
			}

			if ( $settings['to_date'] ) {
                		$to_date = self::format_date_to_day_end( $settings['to_date'] );
				if ( $is_for_sql ) {
					if ( $use_timestamps ) {
						$to_date = mysql2date( 'G', $to_date );
						$to_date -= $diff_utc;
					} elseif( $force_utc )
						$to_date = self::convert_date_to_utc( $to_date, $diff_utc );
					$to_date = sprintf( "<='%s'", $to_date );
				}
				$result['to_date'] = $to_date;
			}

			return $result;
		}

		$_time = current_time( "timestamp", 0 );

		$export_rule = isset( $settings['export_rule'] ) ? $settings['export_rule'] : '';

		switch ( $export_rule ) {
			case "none":
				unset( $from_date );
				unset( $to_date );
				break;
			case "last_run":
				$last_run = isset( $settings['schedule']['last_run'] ) ? $settings['schedule']['last_run'] : '';
				if ( isset( $last_run ) AND $last_run ) {
					$from_date = date( 'Y-m-d H:i:s', $last_run );
				}
				break;
			case "today":
				$_date = date( 'Y-m-d', $_time );

				$from_date = sprintf( '%s %s', $_date, '00:00:00' );
				$to_date   = sprintf( '%s %s', $_date, '23:59:59' );
				break;
			case "this_week":
				$day        = ( date( 'w', $_time ) + 6 ) % 7;// 0 - Sun , must be Mon = 0
				$_date      = date( 'Y-m-d', $_time );
				$week_start = date( 'Y-m-d', strtotime( $_date . ' -' . $day . ' days' ) );
				$week_end   = date( 'Y-m-d', strtotime( $_date . ' +' . ( 6 - $day ) . ' days' ) );

				$from_date = sprintf( '%s %s', $week_start, '00:00:00' );
				$to_date   = sprintf( '%s %s', $week_end, '23:59:59' );
				break;
			case "this_month":
				$month_start = date( 'Y-m-01', $_time );
				$month_end   = date( 'Y-m-t', $_time );

				$from_date = sprintf( '%s %s', $month_start, '00:00:00' );
				$to_date   = sprintf( '%s %s', $month_end, '23:59:59' );
				break;
			case "last_day":
				$_date    = date( 'Y-m-d', $_time );
				$last_day = strtotime( $_date . " -1 day" );
				$_date    = date( 'Y-m-d', $last_day );

				$from_date = sprintf( '%s %s', $_date, '00:00:00' );
				$to_date   = sprintf( '%s %s', $_date, '23:59:59' );
				break;
			case "last_week":
				$day        = ( date( 'w', $_time ) + 6 ) % 7;// 0 - Sun , must be Mon = 0
				$_date      = date( 'Y-m-d', $_time );
				$last_week  = strtotime( $_date . " -1 week" );
				$week_start = date( 'Y-m-d', strtotime( date( 'Y-m-d', $last_week ) . ' -' . $day . ' days' ) );
				$week_end   = date( 'Y-m-d', strtotime( date( 'Y-m-d', $last_week ) . ' +' . ( 6 - $day ) . ' days' ) );

				$from_date = sprintf( '%s %s', $week_start, '00:00:00' );
				$to_date   = sprintf( '%s %s', $week_end, '23:59:59' );
				break;
			case "last_month":
				$_date       = date( 'Y-m-d', $_time );
				$last_month  = strtotime( $_date . " -1 month" );
				$month_start = date( 'Y-m-01', $last_month );
				$month_end   = date( 'Y-m-t', $last_month );

				$from_date = sprintf( '%s %s', $month_start, '00:00:00' );
				$to_date   = sprintf( '%s %s', $month_end, '23:59:59' );
				break;
			case "last_quarter":
				$_date         = date( 'Y-m-d', $_time );
				$last_month    = strtotime( $_date . " -3 month" );
				$quarter_first_month = self::get_quarter_month( $last_month );
				if( $quarter_first_month < 10 )
					$quarter_first_month = "0".$quarter_first_month;
				$quarter_last_month = self::get_quarter_month( $last_month ) + 2;
				if( $quarter_last_month < 10 )
					$quarter_last_month = "0".$quarter_last_month;
				$quarter_start = date( 'Y-' . $quarter_first_month . '-01', $last_month );
				$quarter_end   = date( 'Y-' . $quarter_last_month . '-t', strtotime("$quarter_start +2 month") );

				$from_date = sprintf( '%s %s', $quarter_start, '00:00:00' );
				$to_date   = sprintf( '%s %s', $quarter_end, '23:59:59' );
				break;
			case "this_year":
				$year_start = date( 'Y-01-01', $_time );

				$from_date = sprintf( '%s %s', $year_start, '00:00:00' );
				break;
			// =========== Modified By Hayato ==========
			case "last_year":
				$_date = date('Y-m-d',$_time);
				$last_year = strtotime($_date . " -1 year");
				$last_year_start = date('Y-01-01',$last_year);
				$last_year_end = date('Y-12-31',$last_year);
				$from_date = sprintf('%s %s',$last_year_start, '00:00:00');
				$to_date = sprintf('%s %s',$last_year_end, '23:59:59');
				break;
			// =========================================
			case "custom":
				$export_rule_custom = isset( $settings['export_rule_custom'] ) ? $settings['export_rule_custom'] : '';
				if ( isset( $export_rule_custom ) AND $export_rule_custom ) {
					$day_start = date( 'Y-m-d',
						strtotime( date( 'Y-m-d', $_time ) . ' -' . intval( $export_rule_custom ) . ' days' ) );
					$day_end   = date( 'Y-m-d', $_time );

					$from_date = sprintf( '%s %s', $day_start, '00:00:00' );
					$to_date   = sprintf( '%s %s', $day_end, '23:59:59' );
				}
				break;
			default:
				break;
		}

		if ( isset( $from_date ) AND $from_date ) {
			if ( $is_for_sql ) {
				if ( $use_timestamps ) {
					$from_date = mysql2date( 'G', $from_date );
					$from_date -= $diff_utc;
				} elseif( $force_utc )
					$from_date = self::convert_date_to_utc( $from_date, $diff_utc );
				$from_date = sprintf( ">='%s'", $from_date );
			}
			$result['from_date'] = $from_date;
		}

		if ( isset( $to_date ) AND $to_date ) {
			if ( $is_for_sql ) {
				if ( $use_timestamps ) {
					$to_date = mysql2date( 'G', $to_date );
					$to_date -= $diff_utc;
				} elseif( $force_utc )
					$to_date = self::convert_date_to_utc( $to_date, $diff_utc );
				$to_date = sprintf( "<='%s'", $to_date );
			}
			$result['to_date'] = $to_date;
		}

		return $result;
	}

	public static function convert_date_to_utc( $date, $diff_utc ) {
		return gmdate("Y-m-d H:i:s", strtotime($date) - $diff_utc);
	}	

	public static function get_quarter_month( $time ) {
		$month = date( "m", $time );
		if ( $month <= 3 ) {
			return 1;
		}
		if ( $month <= 6 ) {
			return 4;
		}
		if ( $month <= 9 ) {
			return 7;
		}

		return 10;
	}

	public static function prepare_for_export() {
		self::$statuses                         = wc_get_order_statuses();
		self::$countries                        = WC()->countries->countries;
		self::$prices_include_tax               = get_option( 'woocommerce_prices_include_tax' ) == 'yes' ? true : false;
		self::$export_subcategories_separator   = apply_filters( 'woe_export_subcategories_separator', ">" );
		self::$export_line_categories_separator = apply_filters( 'woe_export_line_categories_separator', ",\n" );
		self::$export_itemmeta_values_separator = apply_filters( 'woe_export_itemmeta_values_separator', ", " );
		self::$export_custom_fields_separator   = apply_filters( 'woe_export_custom_fields_separator', ", " );
		
		//detect if has order stats tables
		global $wpdb;
		$table_name = $wpdb->prefix.'wc_order_stats';
		$query = $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table_name ) );
		self::$has_order_stats = ($wpdb->get_var( $query ) == $table_name);
	}

	//for debug 
	public static function start_track_queries() {
		self::$track_sql_queries = true;
		self::$sql_queries       = array();
	}

	public static function get_sql_queries() {
		return self::$sql_queries;
	}

	//for csv/excel
	public static function get_max_order_items( $type, $ids ) {
		global $wpdb;

		$ids[] = 0; // for safe
		$ids   = join( ",", $ids );
		$sql = "SELECT COUNT( * ) AS t
				FROM  `{$wpdb->prefix}woocommerce_order_items`
				WHERE order_item_type =  '$type'
				AND order_id
				IN ( $ids)
				GROUP BY order_id
				ORDER BY t DESC
				LIMIT 1";

		$max = $wpdb->get_var( $sql );
		if ( ! $max ) {
			$max = 1;
		}

		return apply_filters( 'woe_get_max_order_items_'.$type, $max);
	}

	public static function fetch_order_coupons(
		$order,
		$labels,
		$static_vals,
		$options
	) {
		global $wpdb;
		$coupons = array();
		foreach ( $order->get_items( 'coupon' ) as $item ) {
			$row = array();
			$coupon = new WC_Order_Export_Order_Coupon_Fields( $item, $labels, $static_vals );
			foreach ( $labels->unique_keys() as $field ) {
				$row[ $field ] = $coupon->get($field);
				
				$row[ $field ] = apply_filters( "woe_get_order_coupon_value_{$field}", $row[ $field ], $order,
					$item );
			}
			$row = apply_filters( 'woe_fetch_order_coupon', $row, $item, $coupon->get_coupon_meta() );
			if ( $row ) {
				$coupons[] = $row;
			}
		}

		return apply_filters( "woe_fetch_order_coupons", $coupons, $order, $labels->get_legacy_labels(), $format = "",
			$static_vals );
	}


	/**
	 * @param WC_Order                     $order
	 * @param WC_Order_Export_Labels       $labels
	 * @param array                        $static_vals
	 * @param array                        $options
	 * @param WC_Order_Export_Order_Fields $woe_order
	 *
	 * @return array
	 */
	public static function fetch_order_products(
		$order,
		$labels,
		$static_vals,
		$options,
		$woe_order
	) {
		$export_only_products     = $options['include_products'];
		$export_matched_products  = $options['export_matched_items'];
		$products                 = array();
		$i                        = 0;

		foreach ( $order->get_items( 'line_item' ) as $item_id => $item ) {
			/** @var WC_Order_Item_Product $item */
			do_action( "woe_get_order_product_item", $item );
			if ( $options['export_refunds'] AND $item['qty'] == 0 AND !apply_filters("woe_allow_export_zero_qty", false) ) // skip zero items, when export refunds
			{
				continue;
			}
			// we export only matched products?
			if ( $export_only_products AND
			     ! in_array( $item['product_id'], $export_only_products ) AND // not  product
			     ( ! $item['variation_id'] OR ! in_array( $item['variation_id'],
					     $export_only_products ) )  // not variation
			) {
				continue;
			}

			if( $export_matched_products ) {
				foreach ( $export_matched_products['item_metadata'] as $operator => $fields ) {
					foreach ( $fields as $field => $values ) {
						if ( $values ) {
							self::extract_item_type_and_key( $field, $type, $key );
							if($type != 'line_item') {
								continue;
							}
							$meta = wc_get_order_item_meta( $item_id, $key );
							if(($operator == 'IN' AND !in_array($meta, $values)) OR
								($operator == 'NOT IN' AND in_array($meta, $values))) {
								continue 3;
							}
							else if($operator == 'LIKE') {
								$matched_like = false;
								foreach ($values as $value) {
									if(strpos($meta, $value) !== false) {
										$matched_like = true;
										continue;
									}
								}
								if(!$matched_like) {
									continue 3;
								}
							}
							else if($operator == 'IS SET' && $meta === '') {
								continue 3;
							}
							else if($operator == 'NOT SET' && $meta !== '') {
								continue 3;
							}
							else if(in_array($operator, self::$operator_must_check_values)) {
								if(empty($meta)) {
									continue 3;
								}
								$matched = false;
								foreach ($values as $value) {
									if(version_compare($meta, $value, $operator)) {
										$matched = true;
										continue;
									}
								}
								if(!$matched) {
									continue 3;
								}
							}
						}
					}
				}
				foreach ( $export_matched_products['item_names'] as $operator => $fields ) {
					foreach ( $fields as $field => $values ) {
						if ( $values ) {
							if($field != 'line_item') {
								continue;
							}
							$item_name = $item->get_name();
							if(($operator == 'IN' AND !in_array($item_name, $values)) OR
								($operator == 'NOT IN' AND in_array($item_name, $values))) {
								continue 3;
							}
							else if($operator == 'LIKE') {
								$matched_like = false;
								foreach ($values as $value) {
									if(strpos($item_name, $value) !== false) {
										$matched_like = true;
										continue;
									}
								}
								if(!$matched_like) {
									continue 3;
								}
							}
						}
					}
				}
			}
			
			$product   = $item->get_product();
			$product   = apply_filters( "woe_get_order_product", $product );
			
			$item_meta = get_metadata( 'order_item', $item_id );
			foreach ( $item_meta as $key => $value ) {
				$clear_key = wc_sanitize_taxonomy_name( $key );
				if ( taxonomy_exists( $clear_key ) ) {
					$term                 = get_term_by( 'slug', $value[0], $clear_key );
					$item_meta[ $key ][0] = isset( $term->name ) ? $term->name : $value[0];
					if ( strpos( $key, 'attribute_' ) === false ) {
						$item_meta[ 'attribute_' . $key ][0] = isset( $term->name ) ? $term->name : $value[0];
					}
				}
				
				//some plugins encode meta keys!
				$key2 = html_entity_decode ($key,ENT_QUOTES);
				if( !isset($item_meta[$key2]) )
					$item_meta[$key2] = $item_meta[$key];
			}
			
			$item_meta = apply_filters( "woe_get_order_product_item_meta", $item_meta );
			$product   = apply_filters( "woe_get_order_product_and_item_meta", $product, $item_meta );
			if ( $product ) {
				if ( method_exists( $product, 'get_id' ) ) {
					if ( $product->is_type( 'variation' ) ) {
						$product_id = method_exists( $product,
							'get_parent_id' ) ? $product->get_parent_id() : $product->parent->id;
					} else {
						$product_id = $product->get_id();
					}
					$post = get_post( $product_id );
				} else {    // legacy
					$product_id = $product->id;
					$post       = $product->post;
				}
			} else {
				$product_id = 0;
				$post       = false;
			}

			// skip based on products/items/meta
			if ( apply_filters( 'woe_skip_order_item', false, $product, $item, $item_meta, $post ) ) {
				continue;
			}

			if ( $options['skip_refunded_items'] ) {
				$qty_minus_refund = $item_meta["_qty"][0] + $order->get_qty_refunded_for_item( $item_id ); // Yes we add negative! qty
				if ( $qty_minus_refund <= 0 ) {
					continue;
				}
			}

			$i ++;
			$row = array();
			$woe_product = new WC_Order_Export_Order_Product_Fields( $item, $item_meta, $product, $order, $post, $i, $static_vals, $options, $woe_order );
			foreach ( $labels->unique_keys() as $field ) {
				$row[$field] = $woe_product->get($field);
			}
			$row = apply_filters( 'woe_fetch_order_product', $row, $order, $item, $product, $item_meta );
			if ( $row ) {
				$products[ $item_id ] = $row;
			}
		}

		return apply_filters( "woe_fetch_order_products", $products, $order, $labels->get_legacy_labels(), $format = "",
			$static_vals );
	}
	
	/**
	 * @param $product WC_Product
	 *
	 * @return string
	 */
	/**
	 * @param $order WC_Order
	 * @param $item_id
	 *
	 * @return int
	 */
	public static function get_order_item_taxes_refund( $order, $item_id ) {
		$tax_refund  = 0;
		$order_taxes = $order->get_taxes();
		foreach ( $order_taxes as $tax_item ) {
			$tax_item_id = $tax_item['rate_id'];
			$tax_refund  += $order->get_tax_refunded_for_item( $item_id, $tax_item_id );
		}

		return $tax_refund;
	}

	public static function fetch_order_data(
		$order_id,
		$labels,
		$export,
		$static_vals,
		$options
	) {
		global $wp_roles;
		global $wpdb;

//		$extra_rows = array();
		$row = array();
		// take order
		$class = apply_filters( 'woe_get_class_name', 'WC_Order' );
		$object = new $class( $order_id );
		self::$current_order = $order = apply_filters( "woe_get_order_by_object", $object, $order_id );

		$woe_order = new WC_Order_Export_Order_Fields( $order, $static_vals, $options, $export );

		// we know parent!
		if ( ( $export['products'] || $options['include_products'] ) && $labels['products']->is_not_empty() ||
		     isset( $labels['order']->count_unique_products ) || isset( $labels['order']->total_weight_items ) ) {
			//   no labels for products??
			$tmp_labels = $labels['products']->is_not_empty() ? clone $labels['products'] : new WC_Order_Export_Labels();
			//need qty?
			if ( isset( $labels['order']->total_weight_items ) || isset( $labels['order']->count_unique_products ) ) {
				if ( ! isset( $tmp_labels->qty ) ) {
					$tmp_labels->qty = "";
				}
			}
			// need weight too?
			if ( isset( $labels['order']->total_weight_items ) ) {
				if ( ! isset( $tmp_labels->weight ) ) {
					$tmp_labels->weight = "";
				}
			}

			$data['products'] = self::fetch_order_products(
				$order,
				$tmp_labels,
				isset( $static_vals['products'] ) ? $static_vals['products'] : array(),
				$options,
				$woe_order
			);
			if ( $options['include_products'] AND empty( $data['products'] ) AND apply_filters( "woe_skip_order_without_products", false ) ) {
				return array();
			}
		} else {
			$data['products'] = array();
		}
		if ( ( $export['coupons'] OR isset( $labels['order']->coupons_used ) ) && $labels['coupons']->is_not_empty() ) {
			// get coupons from main order
			$data['coupons'] = self::fetch_order_coupons(
				$woe_order->get_parent_order() ? $woe_order->get_parent_order() : $order,
				$labels['coupons'],
				isset( $static_vals['coupons'] ) ? $static_vals['coupons'] : array(),
				$options
			);
		} else {
			$data['coupons'] = array();
		}

		$woe_order->set_data($data);
		// fill as it must
		foreach ( $labels['order']->get_fetch_fields() as $field ) {
				$row = $woe_order->get($row, $field);
				//use empty value for missed field
			if ( $field != 'products' AND $field != 'coupons' ) {
				if ( ! isset( $row[ $field ] ) ) {
					$row[ $field ] = '';
				}
				if ( is_array( $row[ $field ] ) ) {
					$row[ $field ] = json_encode( $row[ $field ] );
				}

				if ( $options['convert_serialized_values'] ) {
					$arr = maybe_unserialize( $row[ $field ] );
					if ( is_array($arr) ) $row[$field] = join(",", $arr);
				}
			}
			if ( isset( $row[ $field ] ) ) {
				$row[ $field ] = apply_filters( "woe_get_order_value_{$field}", $row[ $field ], $order, $field, $object );
			} //if order field set
		}

		//no labels - no data !
		if (  $labels['products']->is_not_empty() == false ) {
			$row['products'] = array();
		}
		if ( $labels['coupons']->is_not_empty() == false ) {
			$row['coupons'] = array();
		}

		if ($options['strip_html_tags']) {
			array_walk_recursive($row, function (&$item, $key) {
				$item = strip_tags($item);
			});
		}
		$row = apply_filters( "woe_fetch_order", $row, $order, $object );

		return $row;
	}

	public static function get_city_state_postcode_field_value( $order, $type, $us_format = false ) {
		if ( $type != 'shipping' && $type != 'billing' ) {
			return null;
		}
		$citystatepostcode_fields_name = array(
			$type . '_city',
			$type . '_state',
			$type . '_postcode',
		);
		$citystatepostcode             = array();
		foreach ( $citystatepostcode_fields_name as $field_name ) {
			$citystatepostcode[ $field_name ] = method_exists( $order,
				'get_' . $field_name ) ? $order->{'get_' . $field_name}() : $order->{$field_name};
		}

		if ( $us_format ) {
			//reformat as "Austin, TX 95076"
			$parts[] = $citystatepostcode[ $type . '_city' ];
			$parts[] = trim( $citystatepostcode[ $type . '_state' ] . " " . $citystatepostcode[ $type . '_postcode' ] );
		} else {
			$parts = $citystatepostcode;
		}

		return join( ", ", $parts );
	}

	public static function get_order_subtotal_refunded( $order ) {
		$subtotal_refund = 0;
		foreach ( $order->get_refunds() as $refund ) {
			$subtotal_refund += $refund->get_subtotal();
		}

		return abs( $subtotal_refund );
	}

	/**
	 * @return string
	 */
	public static function get_product_variation( $item, $order, $item_id, $product ) {
		global $wpdb;
		$hidden_order_itemmeta = apply_filters( 'woocommerce_hidden_order_itemmeta', array(
			'_qty',
			'_tax_class',
			'_product_id',
			'_variation_id',
			'_line_subtotal',
			'_line_subtotal_tax',
			'_line_total',
			'_line_tax',
			'method_id',
			'cost',
			'_reduced_stock',
		) );

		$result = array();

		$value_delimiter = apply_filters( 'woe_fetch_item_meta_value_delimiter', ': ' );

		// pull meta directly
		$meta_data = $wpdb->get_results( $wpdb->prepare( "SELECT meta_key, meta_value, meta_id, order_item_id
			FROM {$wpdb->prefix}woocommerce_order_itemmeta WHERE order_item_id = %d
			ORDER BY meta_id", $item_id ), ARRAY_A );
		foreach ( $meta_data as $meta ) {
			if ( in_array( $meta['meta_key'], $hidden_order_itemmeta ) ) {
				continue;
			}
			if ( is_serialized( $meta['meta_value'] ) ) {
				continue;
			}

			//known attribute?
			if ( taxonomy_exists( wc_sanitize_taxonomy_name( $meta['meta_key'] ) ) ) {
				$term               = get_term_by( 'slug', $meta['meta_value'],
					wc_sanitize_taxonomy_name( $meta['meta_key'] ) );
				$meta['meta_key']   = wc_attribute_label( wc_sanitize_taxonomy_name( $meta['meta_key'] ), $product );
				$meta['meta_value'] = isset( $term->name ) ? $term->name : $meta['meta_value'];
			} else {
				$meta['meta_key'] = wc_attribute_label( $meta['meta_key'], $product );
			}

			$value    = wp_kses_post( $meta['meta_key'] ) . $value_delimiter . wp_kses_post( force_balance_tags( $meta['meta_value'] ) );
			$result[] = apply_filters( 'woe_fetch_item_meta', $value, $meta, $item, $product );
		}

		//list to string!
		return join( apply_filters( 'woe_fetch_item_meta_lines_delimiter', ' | ' ), array_filter( $result ) );
	}

	/**
	 * @return array
	 */
	public static function get_shipping_methods() {
		global $wpdb;

		$shipping_methods = array();

		// get raw names
		$raw_methods = self::get_order_shipping_items();
		foreach ( $raw_methods as $method ) {
			$shipping_methods[ 'order_item_name:' . $method ] = $method;
		}

		// try get  methods for zones
		if ( ! class_exists( "WC_Shipping_Zone" ) ) {
			return $shipping_methods;
		}

		if ( ! method_exists( "WC_Shipping_Zone", "get_shipping_methods" ) ) {
			return $shipping_methods;
		}

		$shipping_methods_zones = array();
		foreach ( WC_Shipping_Zones::get_zones() as $zone ) {
			$methods = $zone['shipping_methods'];
			/** @var WC_Shipping_Method $method */
			foreach ( $methods as $method ) {
				$shipping_methods[ 'order_item_name:' . $method->get_title()] = $method->get_title();
				$shipping_methods_zones[ $method->get_rate_id() ] = '[' . $zone['zone_name'] . '] ' . $method->get_title();
			}
		}

		$zone    = new WC_Shipping_Zone( 0 );
		$methods = $zone->get_shipping_methods();
		/** @var WC_Shipping_Method $method */
		foreach ( $methods as $method ) {
			$shipping_methods[ 'order_item_name:' . $method->get_title()] = $method->get_title();
			$shipping_methods_zones[ $method->get_rate_id() ] = __( '[Rest of the World]',
					'woo-order-export-lite' ) . ' ' . $method->get_title();
		}

		asort( $shipping_methods, SORT_STRING);
		return apply_filters("woe_get_shipping_methods", $shipping_methods + $shipping_methods_zones);
	}

	public static function get_shipping_zone( $order ) {
		$ship_methods = self::get_shipping_methods();
		$value = __( 'Rest of the World','woo-order-export-lite' );
		$methods = $order->get_items('shipping');
		$method = reset ($methods );
		if( $method ) {
			$key = $method['method_id'] . ":" . $method['instance_id'];
			// parse text "[Zone] Method name"
			if ( isset($ship_methods[$key]) AND preg_match('#\[(.+?)\]#',$ship_methods[$key],$m) ) {
				$value = $m[1];
			}
		}
		return $value;		
	}
}