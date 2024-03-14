<?php


class arfliterecordmeta {

	function __construct() {

		add_filter( 'arfliteaddentrymeta', array( $this, 'arflite_before_create' ) );
	}
	function arflite_before_create( $values ) {

		global $arflitefield;

		$field = $arflitefield->arflitegetOne( $values['field_id'] );

		if ( ! $field ) {

			return $values;
		}

		return $values;

	}
	function arflitewpversioninfo() {
		return get_bloginfo( 'version' );
	}

	function arflitegetlanguage() {
		return get_bloginfo( 'language' );
	}

	function arflite_add_entry_meta( $entry_id, $field_id, $meta_key, $entry_value ) {

		global $wpdb, $arflite_fid, $check_itemid, $form_responder_fname, $form_responder_lname, $form_responderemail, $email, $fname, $lname, $tbl_arf_entry_values, $tbl_arf_forms;

		$allowed_html = arflite_retrieve_attrs_for_wp_kses( true );

		$new_values = array();

		$new_values['entry_value'] = trim( wp_kses($entry_value, $allowed_html) );

		$new_values['entry_id'] = intval( $entry_id );

		$new_values['field_id'] = intval( $field_id );

		$new_values['created_date'] = current_time( 'mysql', 1 );

		$new_values = apply_filters( 'arfliteaddentrymeta', $new_values );

		$wpdb->insert( $tbl_arf_entry_values, $new_values );

		if ( $check_itemid == '' ) {
			$result = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ' . $tbl_arf_forms . ' WHERE id=%d', $arflite_fid ) ); //phpcs:ignore

			if ( ! empty( $result ) ) {

				$result = $result[0];

				$form_options = maybe_unserialize( $result->options );
			}
		}
	}
	function arflite_update_entry_meta( $entry_id, $field_id, $meta_key, $entry_value, $date_format ) {

		global $arfliterecordmeta, $wpdb,$ARFLiteMdlDb, $tbl_arf_fields;

		$new_entry_value_data = '';

		if ( ! empty( $entry_value ) || $entry_value == '0' ) {

			$fielddata = $wpdb->get_row( $wpdb->prepare( 'SELECT type, options, field_options,form_id FROM ' . $tbl_arf_fields . " WHERE id='%d'", $field_id ) ); //phpcs:ignore

			$arfliterecordmeta->arflite_add_entry_meta( $entry_id, $field_id, $meta_key, $entry_value );

			if ( $fielddata && ( $fielddata->type == 'select' || $fielddata->type == 'checkbox' || $fielddata->type == 'radio' ) ) {

				$options_arr = json_decode( $fielddata->options, true );
				if ( json_last_error() != JSON_ERROR_NONE ) {
					  $options_arr = maybe_unserialize( $fielddata->options );
				}

				$field_options = json_decode( $fielddata->field_options, true );
				if ( json_last_error() != JSON_ERROR_NONE ) {
						$field_options = maybe_unserialize( $fielddata->field_options );
				}

				if ( isset( $field_options['separate_value'] ) && $field_options['separate_value'] == 1 ) {
					$new_entry_value = array();

					$entry_value = maybe_unserialize( $entry_value );
					if ( $fielddata->type == 'checkbox' ) {
						if ( is_array( $entry_value ) ) {
							foreach ( $entry_value as $k => $field_value ) {
								$new_entry_value[] = $this->arflite_find_value_in_options_with_separate_value( $field_value, $options_arr, $k );
							}
						} else {
							$new_entry_value[] = $this->arflite_find_value_in_options_with_separate_value( $entry_value, $options_arr, '' );
						}
					} else {
						$new_entry_value = $this->arflite_find_value_in_options( $entry_value, $options_arr );
					}

					$new_entry_value = maybe_serialize( $new_entry_value );

					$arfliterecordmeta->arflite_add_entry_meta( $entry_id, '-' . $field_id, $meta_key, $new_entry_value );
				}
			}
		}

	}

	function arflite_find_value_in_options_with_separate_value( $value, $options, $key = '' ) {
		if ( isset( $options ) && is_array( $options ) && $options != '' ) {
			foreach ( $options as $k => $fieldoption ) {
				if ( isset( $fieldoption ) && is_array( $fieldoption ) && array_key_exists( 'value', $fieldoption ) ) {
					if ( trim( $fieldoption['value'] ) === trim( $value ) ) {
							return $options[ $k ];
							break;
					}
				}
			}
		}

		return array(
			'value' => $value,
			'label' => $value,
		);
	}



	function arflite_find_value_in_options( $value, $options ) {
		if ( isset( $options ) && is_array( $options ) && $options != '' ) {
			foreach ( $options as $k => $fieldoption ) {
				if ( isset( $fieldoption ) && is_array( $fieldoption ) && array_key_exists( 'value', $fieldoption ) ) {
					if ( $fieldoption['value'] == $value ) {
						return $fieldoption;
						break;
					}
				}
			}
		}

		return array(
			'value' => $value,
			'label' => $value,
		);
	}

	function arflite_update_entry_metas( $entry_id, $values, $date_format = '' ) {

		global $arflitefield;

		$this->arflite_delete_entry_metas( $entry_id, " AND field_id != '0'" );

		foreach ( $values as $field_id => $entry_value ) {

			if ( is_array( $values[ $field_id ] ) && count( $values[ $field_id ] ) === 1 ) {
				$values[ $field_id ] = reset( $values[ $field_id ] ); }

			if ( is_array( $values[ $field_id ] ) ) {
				$values[ $field_id ] = ( empty( $values[ $field_id ] ) ) ? false : maybe_serialize( $values[ $field_id ] );}

			$this->arflite_update_entry_meta( $entry_id, $field_id, '', $values[ $field_id ], $date_format );
		}

	}

	function arflite_delete_entry_meta( $entry_id, $field_id ) {

		global $wpdb, $tbl_arf_entry_values;

		$entry_id = (int) $entry_id;

		$field_id = (int) $field_id;

		return $wpdb->query( $wpdb->prepare( "DELETE FROM $tbl_arf_entry_values WHERE field_id=%s AND entry_id=%s", $field_id, $entry_id ) ); //phpcs:ignore

	}
	function arflite_delete_entry_metas( $entry_id, $where = '' ) {

		global $wpdb, $tbl_arf_entry_values;

		$entry_id = (int) $entry_id;

		$where = $wpdb->prepare( 'entry_id=%s', $entry_id ) . $where;

		return $wpdb->query( "DELETE FROM $tbl_arf_entry_values WHERE $where" ); //phpcs:ignore

	}
	function arflite_get_entry_meta_by_field( $entry_id, $field_id, $return_var = true, $is_for_mail = false ) {

		global $wpdb, $tbl_arf_fields, $tbl_arf_entry_values;

		$entry_id = (int) $entry_id;

		$field_id = (int) $field_id;

		$fields = $wpdb->get_results( $wpdb->prepare( 'SELECT type, options, field_options FROM ' . $tbl_arf_fields . ' WHERE id = %d', $field_id ) ); //phpcs:ignore

		if ( is_numeric( $field_id ) ) {
			$query = $wpdb->prepare( "SELECT entry_value FROM $tbl_arf_entry_values WHERE field_id=%s and entry_id=%s", $field_id, $entry_id ); //phpcs:ignore
		} else {
			$query = $wpdb->prepare( "SELECT entry_value FROM $tbl_arf_entry_values it LEFT OUTER JOIN $tbl_arf_fields fi ON it.field_id=fi.id WHERE fi.field_key=%s and entry_id=%s", $field_id, $entry_id ); //phpcs:ignore
		}

		if ( $return_var ) {

			$result = maybe_unserialize( $wpdb->get_var( "{$query} LIMIT 1" ) ); //phpcs:ignore

			$result = stripslashes_deep( $result );

		} else {

			$result = $wpdb->get_col( $query, 0 ); //phpcs:ignore

		}

		if ( $is_for_mail == true ) {

			if ( $fields[0]->type == 'checkbox' || $fields[0]->type == 'radio' || $fields[0]->type == 'select' ) {

				$field_options = arflite_json_decode( $fields[0]->field_options, true );

				if ( isset( $field_options['separate_value'] ) && $field_options['separate_value'] == 1 ) {

					global $wpdb,$tbl_arf_entry_values;
					$field_opts = $wpdb->get_row( $wpdb->prepare( 'SELECT entry_value FROM ' . $tbl_arf_entry_values . " WHERE field_id='%d' AND entry_id='%d'", '-' . $field_id, $entry_id ) ); //phpcs:ignore

					if ( $field_opts ) {
						$field_opts = maybe_unserialize( $field_opts->entry_value );

						if ( $fields[0]->type == 'checkbox' ) {
							if ( $field_opts && count( $field_opts ) > 0 ) {
								$temp_value = '';
								foreach ( $field_opts as $new_field_opt ) {
									$temp_value .= $new_field_opt['label'] . ' (' . $new_field_opt['value'] . '), ';
								}
								$temp_value = trim( $temp_value );
								$result     = rtrim( $temp_value, ',' );
							}
						} else {
							if ( $fields[0]->type == 'select' && $field_options['separate_value'] == 1 ) {
								$label_field_id  = ( $field_id * 100 );
								$get_field_label = $wpdb->get_row( $wpdb->prepare( 'SELECT entry_value FROM ' . $tbl_arf_entry_values . ' WHERE field_id = "-%d" and entry_id="%d"', $label_field_id, $entry_id ) ); //phpcs:ignore
								$field_label     = isset( $get_field_label->entry_value ) ? $get_field_label->entry_value : '';
								if ( $field_label != '' ) {
									$result = stripslashes( $get_field_label->entry_value ) . ' (' . stripslashes( $field_opts['value'] ) . ')';
								} else {
									$result = $field_opts['label'] . ' (' . $field_opts['value'] . ')';
								}
							} else {
								$result = $field_opts['label'] . ' (' . $field_opts['value'] . ')';
							}
						}
					}
				} else {

					if ( $return_var ) {

						$result = maybe_unserialize( $wpdb->get_var( "{$query} LIMIT 1" ) ); //phpcs:ignore

						$result = stripslashes_deep( $result );

					} else {

						$result = $wpdb->get_col( $query, 0 ); //phpcs:ignore

					}
				}
			}
		}

		return $result;

	}

	function arflitegetAll( $where = '', $order_by = '', $limit = '', $stripslashes = false ) {

		global $wpdb, $ARFLiteMdlDb, $arflitefield, $arflitemainhelper, $tbl_arf_entry_values, $tbl_arf_fields;

		/* $table = $ARFLiteMdlDb->entry_metas; */

		$query = "SELECT it.*, fi.type as field_type, fi.field_key as field_key,


              fi.required as required, fi.form_id as field_form_id, fi.name as field_name, fi.options as fi_options


              FROM $tbl_arf_entry_values it LEFT OUTER JOIN $tbl_arf_fields fi ON it.field_id=fi.id" .

			  $arflitemainhelper->arfliteprepend_and_or_where( ' WHERE ', $where ) . $order_by . $limit;

		if ( $limit == ' LIMIT 1' ) {

			$results = $wpdb->get_row( $query ); //phpcs:ignore

		} else {
			$results = $wpdb->get_results( $query ); //phpcs:ignore
		}

		if ( $results && $stripslashes ) {

			foreach ( $results as $k => $result ) {

				$results[ $k ]->entry_value = maybe_unserialize( $result->entry_value );

				unset( $k );

				unset( $result );

			}
		}

		return $results;

	}
	function arflitegetEntryIds( $where = '', $order_by = '', $limit = '', $unique = true ) {

		global $wpdb, $tbl_arf_fields, $tbl_arf_entry_values, $arflitemainhelper;

		$query = 'SELECT ';

		$query .= ( $unique ) ? 'DISTINCT(it.entry_id)' : 'it.entry_id';

		$query .= " FROM $tbl_arf_entry_values it LEFT OUTER JOIN $tbl_arf_fields fi ON it.field_id=fi.id" . $arflitemainhelper->arfliteprepend_and_or_where( ' WHERE ', $where ) . $order_by . $limit;

		if ( $limit == ' LIMIT 1' ) {

			$results = $wpdb->get_var( $query ); //phpcs:ignore

		} else {
			$results = $wpdb->get_col( $query ); //phpcs:ignore
		}

		return $results;

	}

	function &arflitegetmax( $field ) {

		global $wpdb, $tbl_arf_entry_values;

		if ( ! is_object( $field ) ) {

			global $arflitefield;

			$field = $arflitefield->arflitegetOne( $field );

		}

		if ( ! $field ) {

			return;
		}

		$query = $wpdb->prepare( "SELECT entry_value +0 as odr FROM $tbl_arf_entry_values WHERE field_id=%d ORDER BY odr DESC LIMIT 1", $field->id ); //phpcs:ignore

		$max = $wpdb->get_var( $query ); //phpcs:ignore

		return $max;

	}
}
