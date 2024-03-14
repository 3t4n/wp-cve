<?php

/**
 * Ajax search, clean and remove old vars
 * 
 */
add_action( 'wp_ajax_nopriv_cleaning_old_vars', 'cleaning_old_vars' );
add_action( 'wp_ajax_cleaning_old_vars', 'cleaning_old_vars' );

function cleaning_old_vars() {
	$result = '';
	$needless_childs = get_needless_childs();
	$key = ( $_POST['key'] ) ? $_POST['key'] : false;

	global $wpdb;
	$wpdb->show_errors( true );

	if ( $needless_childs && !$key ) {
		$cnt = 0;
		if ( is_array( $needless_childs ) ) {
			foreach ( $needless_childs as $value ) {
				$cnt = $cnt + count( $value );
			}
		}
		$result = '<h2>Found ' . $cnt . ' old variations on your website.</h2>';
		$result .= '<span class="show-results">Show list<i></i></span>';
		$result .= '<ul class="needless-child-list">';
		foreach ( $needless_childs as $parent => $childs ) {
			foreach ( $childs as $child_id => $info ) {
				if ( $info['title'] ) {
					$sku = ( $info['sku'] ) ? 'SKU <strong>"' . $info['sku'] . '"</strong>' : 'empty SKU field';
					$result .= '<li>' . $info['title'] . ' (with ' . $sku . ')</li>';
				}
			}
		}
		$result .= '</ul>';
	} elseif ( $needless_childs && $key == 'clean' ) {
		$deleted = 0;
		$deleted_items = array();
		foreach ( $needless_childs as $parent_id => $child_arr ) {
			if ( is_array( $child_arr ) ) {
				foreach ( $child_arr as $post_id => $info ) {
					$sku = ( $info['sku'] ) ? 'SKU <strong>"' . $info['sku'] . '"</strong> of ' . $info['title'] : '<strong>Empty SKU field</strong> of ' . $info['title'];
					$del_sku = $wpdb->delete( $wpdb->postmeta, array( 'meta_key' => '_sku', 'post_id' => $post_id ) );
					if ( $del_sku == 1 ) {
						$deleted_items[] = $sku;
						$deleted++;
					} else {
						if ( ! $sku = $wpdb->get_var( $wpdb->prepare( "SELECT meta_value FROM $wpdb->postmeta WHERE meta_key='%s' AND post_id='%s' LIMIT 1", '_sku', $post_id ) ) ) {
							$deleted_items[] = '<strong>Empty SKU field</strong> of ' . $info['title'];
							$deleted--;
						}
					}
				}
			}
		}
		if ( $deleted <= count( $deleted_items ) && $deleted != 0 && $deleted != -(count( $deleted_items ) ) ) {
			$result = '<h2>' . count( $deleted_items ) . ' SKU fields have been successfully removed.</h2>';
			$result .= '<span class="show-results">Show list<i></i></span>';
			$result .= '<ul class="needless-child-list">';
			foreach ( $deleted_items as $deleted_item ) {
				if ( ! empty( $deleted_item ) ) {
					$result .= '<li>' . $deleted_item . '<span class="warning"> deleted</span></li>';
				}
			}
			$result .= '</ul>';
		} elseif ( $deleted == 0 ) {
			$result = '<h2>SKU fields of old variations not found.</h2>';
		} elseif( $deleted == -(count( $deleted_items ) ) ) {
			$result = '<h2>All the possible SKU fields were already deleted.</h2>';
		} else {
			$result = '<h2>Error. Please try again.</h2>';
		}
	} elseif ( $needless_childs && $key == 'removal' ) {
		$deleted = 0;
		$deleted_items = array();
		foreach ( $needless_childs as $parent_id => $child_arr ) {
			if ( is_array( $child_arr ) ) {
				foreach ($child_arr as $post_id => $info) {
					$del = wp_delete_post( $post_id, true );
					if ( $del->ID == $post_id ) {
						$deleted++;
						$deleted_items[] = $info['title'];
					} else {
						$deleted--;
						$deleted_items[] = $info['title'];
					}
				}
			}
		}
		if ( $deleted <= count( $deleted_items ) && $deleted != -(count( $deleted_items )) && $deleted != 0 ) {
			$result = '<h2>' . $deleted . ' Old variations have been successfully removed.</h2>';
			$result .= '<span class="show-results">Show list<i></i></span>';
			$result .= '<ul class="needless-child-list">';
			foreach ( $deleted_items as $deleted_item ) {
				$result .= '<li>' . $deleted_item . '<span class="warning"> deleted</span></li>';
			}
			$result .= '</ul>';
		} elseif ( $deleted == -(count( $deleted_items )) ) {
			$result = '<h2>Old variations not found.</h2>';
		} else {
			$result = '<h2>Error. Please try again.</h2>';
		}
	} else {
			$result = '<h2>Old variations not found.</h2>';
	}

	echo $result;

	wp_die();
}

/**
 * Ajax clean or removal old vars
 * 
 */
add_action( 'wp_ajax_nopriv_auto_change_cleaning', 'auto_change_cleaning' );
add_action( 'wp_ajax_auto_change_cleaning', 'auto_change_cleaning' );

function auto_change_cleaning() {
	$result = "Unique!";
	$needless_childs = get_needless_childs();
	$post_ID = ( $_POST['postID'] ) ? $_POST['postID'] : false;
	$sku = ( $_POST['sku'] ) ? $_POST['sku'] : false;
	$auto_clean_option = get_option( 'sef_auto_clean', 'auto_del_sku' );
	$deleting_counter = 0;
	$settings_link = ' <a href="admin.php?page=sku_error_fixer_settings" class="settings-lnk" terget="_blank"></a>';
	if ( $post_ID ) {
		global $wpdb;
		$wpdb->show_errors( true );

		$the_same = array();
		if ( $needless_childs && is_array( $needless_childs ) ) {
			foreach ( $needless_childs as $parent_id => $childs ) {
				if ( is_array( $childs ) ) {
					foreach ( $childs as $child_id => $info ) {
						if( array_search( $sku, $info ) ) {
							$the_same[] = $child_id;
						}
					}
				}
			}
		}
	}

	if ( $auto_clean_option == 'auto_del_sku' && $the_same ) {
		foreach ( $the_same as $remove_id ) {
			if ( $wpdb->delete( $wpdb->postmeta, array( 'meta_key' => '_sku', 'post_id' => $remove_id ) ) ) {
				$deleting_counter++;
			}
		}
		if ( $deleting_counter == count( $the_same ) ) {
			$result = "Unique!";
		}
	} elseif ( $auto_clean_option == 'auto_del_fully' && $the_same ) {
		foreach ( $the_same as $remove_id ) {
			$del = wp_delete_post( $remove_id, true );
			if ( $del->ID == $post_id ) {
				$deleting_counter++;
			}
		}
		if ( $deleting_counter == count( $the_same ) ) {
			$result = "Unique!";
		}
	} elseif ( $auto_clean_option == 'default' && $the_same ) {
		$result = 'Not unique SKU! The number coincides with the SKU of old variations.';
	}

	echo $result . $settings_link;

	wp_die();
}