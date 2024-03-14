<?php
class arflitefieldmodel {

	function __construct() {
	}

	function arflitecreate( $values, $return = true, $template = false, $res_field_id = '' ) {
		global $wpdb, $ARFLiteMdlDb, $arflitemainhelper,$arfliteformcontroller, $tbl_arf_fields;
		$new_values                  = array();
		$key                         = isset( $values['field_key'] ) ? $values['field_key'] : $values['name'];
		$new_values['field_options'] = ( ! is_array( $values['field_options'] ) ) ? json_decode( $values['field_options'], true ) : $values['field_options'];
		$new_values['field_key']     = $arflitemainhelper->arflite_get_unique_key( '', $tbl_arf_fields, 'field_key' );
		foreach ( array( 'name', 'type' ) as $col ) {
			$new_values[ $col ] = stripslashes( $values[ $col ] );
		}
		$new_values['options']      = isset( $values['options'] ) ? $values['options'] : null;
		$new_values['required']     = isset( $values['required'] ) ? (int) $values['required'] : null;
		$new_values['form_id']      = isset( $values['form_id'] ) ? (int) $values['form_id'] : null;
		$new_values['option_order'] = isset( $values['option_order'] ) ? maybe_serialize( $values['option_order'] ) : 0;
		if ( isset( $new_values['field_options']['classes'] ) && $new_values['field_options']['classes'] == '' ) {
			$new_values['field_options']['classes'] = 'arf_1';
		}
		if ( isset( $new_values['field_options']['key'] ) ) {
			$new_values['field_options']['key'] = $new_values['field_key'];
		}
		if ( isset( $new_values['field_options']['required_indicator'] ) && $new_values['field_options']['required_indicator'] == '' ) {
			$new_values['field_options']['required_indicator'] = '*';
		}
		$new_values['field_options'] = is_array( $new_values['field_options'] ) ? json_encode( $new_values['field_options'] ) : $new_values['field_options'];
		$new_values['created_date']  = current_time( 'mysql' );

		$query_results = $wpdb->insert( $tbl_arf_fields, $new_values );
		if ( $return ) {
			if ( $query_results ) {
				$return_insert_id = $wpdb->insert_id;
				if ( $template ) {
					if ( $res_field_id != '' ) {
						 $_SESSION['arf_fields'][ intval( $res_field_id ) ] = intval( $return_insert_id );
					}
				}
				return $return_insert_id;
			} else {
				return false;
			}
		} else {
			if ( $query_results ) {
				$return_insert_id = $wpdb->insert_id;
				if ( $template ) {
					if ( $res_field_id != '' ) {
						 $_SESSION['arf_fields'][ intval( $res_field_id ) ] = intval( $return_insert_id );
					}
				}
			}
		}
	}

	function arfliteduplicate( $old_form_id, $form_id, $copy_keys = false, $blog_id = false, $template = false ) {
		global $wpdb, $ARFLiteMdlDb, $arflitemainhelper, $tbl_arf_forms;
		$form_options    = $wpdb->get_results( $wpdb->prepare( 'SELECT `options` FROM `' . $tbl_arf_forms . '` WHERE `id` = %d', $old_form_id ) ); //phpcs:ignore
		$form_opts       = maybe_unserialize( $form_options[0]->options );
		$field_order     = isset( $form_opts['arf_field_order'] ) ? json_decode( $form_opts['arf_field_order'] ) : array();
		$form_fields     = $this->arflitegetAll( "fi.form_id = $old_form_id", '', '', $blog_id );
		$new_form_fields = array();
		if ( ! empty( $field_order ) && count( $field_order ) > 0 ) {
			foreach ( $field_order as $field_id => $field_ord ) {
				foreach ( $form_fields as $field ) {
					if ( $field->id == $field_id ) {
						$new_form_fields[] = $field;
					}
				}
			}
		}
		$new_field_order = array();
		$n               = 1;
		if ( ! empty( $new_field_order ) ) {
			$form_fields = $new_form_fields;
		}
		foreach ( $form_fields as $field ) {
			$values              = array();
			$new_key             = ( $copy_keys ) ? $field->field_key : '';
			$values['field_key'] = $new_key;
			$values['options']   = maybe_serialize( $field->options );

			$values['form_id'] = $form_id;
			$res_field_id      = $field->id;

			foreach ( array( 'name', 'description', 'type', 'default_value', 'required', 'field_options', 'option_order' ) as $col ) {
				if ( $col == 'default_value' ) {
					$values[ $col ] = maybe_serialize( $field->$col );
				} else {
					$values[ $col ] = $field->{$col};
				}
			}
			$new_field_id                     = $this->arflitecreate( $values, true, $template, $res_field_id );
			$new_field_order[ $new_field_id ] = $n;
			$n++;
			unset( $field );
		}
	}

	function arfliteupdate( $id, $values ) {
		global $wpdb, $ARFLiteMdlDb, $arflitefieldhelper, $arflitemainhelper, $tbl_arf_fields;
		if ( isset( $values['field_key'] ) ) {
			$values['field_key'] = $arflitemainhelper->arflite_get_unique_key( $values['field_key'], $tbl_arf_fields, 'field_key', $id );
		}
		if ( empty( $values['field_options']['required_indicator'] ) ) {
			$values['field_options']['required_indicator'] = '*';
		}
		if ( isset( $values['field_options'] ) && is_array( $values['field_options'] ) ) {
			$values['field_options'] = maybe_serialize( $values['field_options'] );
		}

		$query_results = $wpdb->update( $tbl_arf_fields, $values, array( 'id' => $id ) );
		unset( $values );
		if ( $query_results ) {
			wp_cache_delete( $id, 'arf_field' );
		}
		return $query_results;
	}

	function arflitedestroy( $id ) {
		global $wpdb, $ARFLiteMdlDb, $tbl_arf_entry_values, $tbl_arf_fields;
		do_action( 'arflitebeforedestroyfield', $id );
		do_action( 'arflitebeforedestroyfield_' . $id );
		$wpdb->query( $wpdb->prepare( "DELETE FROM $tbl_arf_entry_values WHERE field_id=%d", $id ) ); //phpcs:ignore
		return $wpdb->query( $wpdb->prepare( "DELETE FROM $tbl_arf_fields WHERE id=%d", $id ) ); //phpcs:ignore
	}

	function arflitegetOne( $id ) {
		global $wpdb, $ARFLiteMdlDb, $tbl_arf_fields;
		$results = wp_cache_get( $id, 'arf_field' );
		if ( ! $results ) {
			if ( is_numeric( $id ) ) {
				$where = array( 'id' => $id );
			} else {
				$where = array( 'field_key' => $id );
			}
			$results = $ARFLiteMdlDb->arflite_get_one_record( $tbl_arf_fields, $where );
			if ( $results ) {
				wp_cache_set( $results->id, $results, 'arf_field' );
			}
		}
		if ( $results ) {
			if ( is_array( $results->field_options ) ) {
				$results->field_options = $results->field_options;
			} else {
				$results->field_options = json_decode( $results->field_options, true );
				if ( json_last_error() != JSON_ERROR_NONE ) {
					$results->field_options = maybe_unserialize( $results->field_options );
				}
			}
			$results->options       = maybe_unserialize( $results->options );
			$results->default_value = isset( $results->field_options['default_value'] ) ? maybe_unserialize( $results->field_options['default_value'] ) : '';
			$results->option_order  = maybe_unserialize( $results->option_order );
		}
		return stripslashes_deep( $results );
	}

	function arflitegetAll( $where = array(), $order_by = '', $limit = '', $blog_id = false, $is_ref_form = 0 ) {
		global $wpdb, $ARFLiteMdlDb, $arflitemainhelper, $tbl_arf_fields, $tbl_arf_forms;
		$table_name      = $tbl_arf_fields;
		$form_table_name = $tbl_arf_forms;
		if ( ! empty( $order_by ) && ! preg_match( '/ORDER BY/', $order_by ) ) {
			$order_by = " ORDER BY {$order_by}";
		}
		if ( is_numeric( $limit ) ) {
			$limit = " LIMIT {$limit}";
		}

		$query = 'SELECT fi.*, ' .
				'fr.name as form_name ' .
				'FROM ' . $table_name . ' fi ' .
				'LEFT OUTER JOIN ' . $form_table_name . ' fr ON fi.form_id=fr.id';
		if ( is_array( $where ) ) {
			extract( $ARFLiteMdlDb->arflite_get_where_clause_and_values( $where ) );
			$query .= "{$where}{$order_by}{$limit}";
			$query  = $wpdb->prepare( $query, $values ); //phpcs:ignore
		} else {
			$query .= $arflitemainhelper->arfliteprepend_and_or_where( ' WHERE ', $where );
			$query .= ' ' . $order_by . ' ' . $limit;
		}

		if ( $table_name == $tbl_arf_fields ) {
			$form_id = preg_replace( '/(.*?)\=(\d+)/', '$2', $where );
			$form_id = (int) $form_id;
			if ( $limit == 'LIMIT 1' || $limit == 1 ) {
				$results = $wpdb->get_row( $query ); //phpcs:ignore
			} else {
				$results = $wpdb->get_results( $query ); //phpcs:ignore
			}
		} else {
			if ( $limit == ' LIMIT 1' || $limit == 1 ) {
				$results = $wpdb->get_row( $query ); //phpcs:ignore
			} else {
				$results = $wpdb->get_results( $query ); //phpcs:ignore
			}
		}
		$pattern = '/(fi.form_id=(\d+))/';
		preg_match_all( $pattern, $where, $matches );
		if ( isset( $matches[2] ) && isset( $matches[2][0] ) ) {
			$form_id = $matches[2][0];
		}
		if ( $results ) {
			if ( is_array( $results ) ) {
				foreach ( $results as $r_key => $result ) {
					wp_cache_set( $result->id, $result, 'arf_field' );
					wp_cache_set( $result->field_key, $result, 'arf_field' );
					if ( is_array( $result->field_options ) ) {
						$results[ $r_key ]->field_options = $result->field_options;
					} else {
						$results[ $r_key ]->field_options = json_decode( $result->field_options, true );
						if ( json_last_error() != JSON_ERROR_NONE ) {
							$results[ $r_key ]->field_options = maybe_unserialize( $result->field_options );
						}
					}
					$results[ $r_key ]->field_options['arf_regular_expression'] = isset( $results[ $r_key ]->field_options['arf_regular_expression'] ) ? addslashes( $results[ $r_key ]->field_options['arf_regular_expression'] ) : '';
					
					if ( is_array( $result->options ) ) {
						$results[ $r_key ]->options = $result->options;
					} else {
						$results[ $r_key ]->options = json_decode( $result->options, true );
						if ( json_last_error() != JSON_ERROR_NONE ) {
							$results[ $r_key ]->options = maybe_unserialize( $result->options );
						}
					}
					$results[ $r_key ]->default_value = isset( $result->field_options['default_value'] ) ? maybe_unserialize( $result->field_options['default_value'] ) : '';
					$results[ $r_key ]->option_order  = maybe_unserialize( $result->option_order );
				}
			} else {
				wp_cache_set( $results->id, $results, 'arf_field' );
				wp_cache_set( $results->field_key, $results, 'arf_field' );
				$results->field_options = maybe_unserialize( $results->field_options );
				$results->options       = maybe_unserialize( $results->options );
				$results->default_value = $results->default_value;
				$results->option_order  = maybe_unserialize( $results->option_order );
			}
		}
		return stripslashes_deep( $results );
	}
}
