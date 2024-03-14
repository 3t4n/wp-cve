<?php

/**
 * Get all old variations
 * @return array All old variations on these site
 */
function get_needless_childs() {
	global $wpdb;
	$wpdb->show_errors( true );

	$no_variable_term_ids = $wpdb->get_results( $wpdb->prepare( "SELECT term_id FROM $wpdb->terms WHERE slug IN ( %s, %s, %s )", 'simple', 'grouped', 'external' ) );
	$no_vars = array();
	foreach ( $no_variable_term_ids as $noterm ) {
		$no_vars[] = $noterm->term_id;
	}

	$all_no_vars = array();
	if ( $no_vars && count( $no_vars ) == 3 ) {
		$all_no_vars_request = $wpdb->get_results( $wpdb->prepare( "SELECT object_id FROM $wpdb->term_relationships WHERE term_taxonomy_id IN ( %s, %s, %s )", $no_vars[0], $no_vars[1], $no_vars[2] ) );

		if ( $all_no_vars_request && is_array( $all_no_vars_request ) ) {
			foreach ( $all_no_vars_request as $no_var_item ) {
				$all_no_vars[] = $no_var_item->object_id;
			}
		}
	}

	if ( $all_no_vars && is_array( $all_no_vars ) ) {
		foreach ( $all_no_vars as $no_var_id ) {
			$childs = $wpdb->get_results( $wpdb->prepare( "SELECT ID, post_title, post_parent FROM $wpdb->posts WHERE post_type='%s' AND post_parent='%s'", 'product_variation', $no_var_id ) );

			if ( $childs && is_array( $childs ) ) {
				foreach ( $childs as $child_item ) {
					if ( $sku = $wpdb->get_var( $wpdb->prepare( "SELECT meta_value FROM $wpdb->postmeta WHERE meta_key='%s' AND post_id='%s' LIMIT 1", '_sku', $child_item->ID ) ) ) {
						$needless_childs[$child_item->post_parent][$child_item->ID]['sku'] = $sku;
					}
					$needless_childs[$child_item->post_parent][$child_item->ID]['title'] = $child_item->post_title;
				}
			}
		}
	}

	$for_ghost_posts = array();
	$true_all_products = $wpdb->get_results( $wpdb->prepare( "SELECT ID, post_title, post_parent, post_name FROM $wpdb->posts WHERE post_type='%s'", 'product_variation' ) );
	if ( $true_all_products && is_array( $true_all_products ) ) {
		foreach ( $true_all_products as $var_item ) {
			if ( $var_item->post_parent ) {
				$for_ghost_posts[$var_item->post_parent][$var_item->ID]['title'] = $var_item->post_title;
				if ( $sku = $wpdb->get_var( $wpdb->prepare( "SELECT meta_value FROM $wpdb->postmeta WHERE meta_key='%s' AND post_id='%s' LIMIT 1", '_sku', $var_item->ID ) ) ) {
					$for_ghost_posts[$var_item->post_parent][$var_item->ID]['sku'] = $sku;
				}
			} else {
				if ( $var_item->post_name ) {
					preg_match_all( "/-([0-9]*)-/", $var_item->post_name, $return);
					$for_ghost_posts[$return[1][0]][$var_item->ID]['title'] = $var_item->post_title;
					if ( $sku = $wpdb->get_var( $wpdb->prepare( "SELECT meta_value FROM $wpdb->postmeta WHERE meta_key='%s' AND post_id='%s' LIMIT 1", '_sku', $var_item->ID ) ) ) {
						$for_ghost_posts[$return[1][0]][$var_item->ID]['sku'] = $sku;
					}
				}
			}
		}
	}

	$ghost_posts = array();
	if ( $for_ghost_posts && is_array( $for_ghost_posts ) ) {
		foreach ( $for_ghost_posts as $post_id => $data ) {
			if ( ! $post_title = $wpdb->get_var( $wpdb->prepare( "SELECT post_title FROM $wpdb->posts WHERE ID='%s' LIMIT 1", $post_id ) ) ) {
				$needless_childs[$post_id] = $data;
			}
		}
	}

	return $needless_childs;
}

/*
 * Auto clear to updating post.
 *
 * @link https://codex.wordpress.org/Plugin_API/Filter_Reference/wp_insert_post_data
 */
add_filter( 'wp_insert_post_data' , 'ser_auto_vars_cleaner' , '99', 2 );

function ser_auto_vars_cleaner( $data , $postarr ) {
	global $wpdb;
	$wpdb->show_errors( true );
	$auto_clean_option = get_option( 'sef_auto_clean', 'auto_del_sku' );
	if ( $auto_clean_option != 'default' ) {
		$post_id = $postarr['ID'];
		$product_sku = $postarr['_sku'];
		$variable_sku = $postarr['variable_sku'];
		$post_skus = array();
		$the_same = array();

		if ( $variable_sku && is_array( $variable_sku ) && !empty( $variable_sku ) ) {
			foreach ( $variable_sku as $var_sku ) {
				if ( $var_sku ) {
					$post_skus[$var_sku] = $var_sku;
				}
			}
		}
		if ( $product_sku ) {
			$post_skus[$product_sku] = $product_sku;
		}

		$needless_childs = get_needless_childs();

		if ( $needless_childs && is_array( $needless_childs ) ) {
			foreach ( $needless_childs as $parent_id => $childs_arr ) {
				if ( is_array( $childs_arr ) ) {
					foreach ( $childs_arr as $post_id => $info ) {
						if( $same_sku = array_search( $info['sku'], $post_skus ) ) {
							$the_same[] = $post_id;
						}
					}
				}
			}
		}
		if ( $the_same && is_array( $the_same ) ) {
			if ( $auto_clean_option == 'auto_del_sku' ) {
				foreach ( $the_same as $remove_id ) {
					$wpdb->delete( $wpdb->postmeta, array( 'meta_key' => '_sku', 'post_id' => $remove_id ) );
				}
			}
			if ( $auto_clean_option == 'auto_del_fully' ) {
				foreach ( $the_same as $remove_id ) {
					wp_delete_post( $remove_id, true );
				}
			}
		}
	}

	return $data;
}