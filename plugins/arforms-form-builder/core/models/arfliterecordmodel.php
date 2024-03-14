<?php
class arfliterecordmodel {

	function arflitecreate( $values, $return_values = false ) {
		global $wpdb, $ARFLiteMdlDb, $arfliterecordmeta, $arflite_fid, $arflitemainhelper, $arflite_db_record, $arflitefieldhelper, $arformsmain, $arfliteformhelper, $tbl_arf_forms, $arflitecreatedentry, $tbl_arf_entries;

		$checkfield_validation = $arflite_db_record->arflitevalidate( $values, false, 1 );

		if ( ! is_null( $checkfield_validation ) && count( $checkfield_validation ) > 0 ) {
			return false;
		}
		$form_id            = $values['form_id'];
		$fields             = $arflitefieldhelper->arflite_get_form_fields_tmp( false, $form_id, false, 0 );
		$posted_item_fields = isset( $values['item_meta'] ) ? $values['item_meta'] : array();
		$posted_item_fields = apply_filters( 'arflite_trim_values', $posted_item_fields );

		$form_options = wp_cache_get( 'arflite_select_form_to_add_entry_values_' . $form_id );

		if ( ! $form_options ) {
			$form_options = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM `' . $tbl_arf_forms . '` WHERE id = %d', $form_id ) ); //phpcs:disable
			wp_cache_set( 'arflite_select_form_to_add_entry_values_' . $form_id, $form_options );
		}

		$form    = $form_options[0];
		$options = maybe_unserialize( $form->options );

		$field_order = json_decode( $options['arf_field_order'], true );

		asort( $field_order );

		$tempfields = array();

		$arf_sorted_fields = array();
		if ( $field_order != '' ) {
			foreach ( $field_order as $field_id => $order ) {
				if ( is_int( $field_id ) ) {
					foreach ( $fields as $field ) {
						if ( $field_id == $field->id ) {
							$arf_sorted_fields[] = $field;
						}
					}
				}
			}
		}

		$fields = $arf_sorted_fields;

		$removed_field_ids = array();
		if ( ! empty( $values['item_meta'] ) ) {
			foreach ( $values['item_meta'] as $key => $value ) {
				if ( is_array( $tempfields ) ) {
					if ( in_array( $key, $tempfields ) ) {
						array_push( $removed_field_ids, $key );
						unset( $values['item_meta'][ $key ] );
					}
				}
			}
		}

		if ( ! empty( $removed_field_ids ) ) {
			foreach ( $fields as $k => $pst_field ) {
				if ( in_array( $pst_field->id, $removed_field_ids ) ) {
					unset( $fields[ $k ] );
				}
			}
			$fields = array_values( $fields );
		}

		$allfieldsarr  = array();
		$allfieldstype = array();
		foreach ( $fields as $key => $postfield ) {
			$allfieldsarr[]  = $postfield->id;
			$allfieldstype[] = $postfield->type;
		}

		$fieldsarray = array();

		$fieldsarray = array_values( array_unique( $fieldsarray ) );

		if ( isset( $fieldsarray ) && ! empty( $fieldsarray ) ) {
			foreach ( $fieldsarray as $key => $value ) {
				unset( $values['item_meta'][ $value ] );
			}
		}

		foreach ( $fields as $k => $f ) {
			if ( isset( $fieldsarray ) && ! empty( $fieldsarray ) && is_array( $fieldsarray ) ) {
				if ( in_array( $f->id, $fieldsarray ) ) {
					unset( $fields[ $k ] );
				}
			}
		}

		foreach ( $fields as $postfield ) {

			if ( $postfield->required ) {
				$form_submit_type = $arformsmain->arforms_get_settings('form_submit_type','general_settings');
				$form_submit_type = isset( $form_submit_type ) ? $form_submit_type : 1;
				if ( $form_submit_type != 1 ) {
					if ( $postfield->type == 'number' ) {
						if ( $posted_item_fields[ $postfield->id ] == '' ) {
							return false;
							break;
						}
					} else {
						if ( $posted_item_fields[ $postfield->id ] == '' ) {
							return false;
							break;
						}
					}
				} else {
					if ( $postfield->type == 'number' ) {
						if ( $posted_item_fields[ $postfield->id ] == '' ) {
							return false;
							break;
						}
					} else {
						if ( isset( $posted_item_fields[ $postfield->id ] ) && $posted_item_fields[ $postfield->id ] == '' ) {
							return false;
							break;
						}
					}
				}
			}
		}

		if ( isset( $return_values ) && $return_values == true ) {
			return isset( $values['item_meta'] ) ? $values['item_meta'] : array();
		}

		$values = apply_filters( 'arflite_before_create_formentry', $values );
		do_action( 'arflitebeforecreateentry', $values );
		$arflite_fid = isset( $values['form_id'] ) ? $values['form_id'] : '';

		$new_values              = array();
		$values['entry_key']     = isset( $values['entry_key'] ) ? $values['entry_key'] : '';
		$new_values['entry_key'] = $arflitemainhelper->arflite_get_unique_key( $values['entry_key'], $tbl_arf_entries, 'entry_key' );

		$new_values['name'] = isset( $values['name'] ) ? $values['name'] : $values['entry_key'];

		if ( is_array( $new_values['name'] ) ) {
			$new_values['name'] = reset( $new_values['name'] );
		}

		$new_values['ip_address'] = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field($_SERVER['REMOTE_ADDR']) : '';

		if ( isset( $values['description'] ) && ! empty( $values['description'] ) ) {
			$new_values['description'] = $values['description'];
		} else {
			$referrerinfo              = $arflitemainhelper->arflite_get_referer_info();
			$new_values['description'] = maybe_serialize(
				array(
					'browser'       => isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '',
					'referrer'      => $referrerinfo,
					'http_referrer' => isset( $values['arf_http_referrer_url'] ) ? esc_url( $values['arf_http_referrer_url'] ) : '',
					'page_url'      => !empty( $_SERVER['HTTP_REFERER'] ) ? esc_url_raw($_SERVER['HTTP_REFERER']) : '',
				)
			);
		}

		$new_values['browser_info'] = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
		$country_name               = arflite_get_country_from_ip( $new_values['ip_address'] );

		$new_values['country']      = $country_name;
		$new_values['form_id']      = isset( $values['form_id'] ) ? (int) $values['form_id'] : null;
		$new_values['created_date'] = isset( $values['created_date'] ) ? $values['created_date'] : current_time( 'mysql' );

		if ( isset( $values['arfuserid'] ) && is_numeric( $values['arfuserid'] ) ) {
			$new_values['user_id'] = $values['arfuserid'];
		} else {
			global $user_ID;
			if ( $user_ID ) {
				$new_values['user_id'] = $user_ID;
			}
		}

		if ( ! isset( $new_values['user_id'] ) || $new_values['user_id'] == null || $new_values['user_id'] == '' ) {
			$new_values['user_id'] = get_current_user_id();
		}

		$create_entry = true;

		if ( $create_entry ) {
			$query_results = $wpdb->insert( $tbl_arf_entries, $new_values );
		}

		if ( isset( $query_results ) && $query_results ) {
			$entry_id = $wpdb->insert_id;
			global $arflitesavedentries;
			$arflitesavedentries[] = (int) $entry_id;
			if ( isset( $_REQUEST['form_display_type'] ) && $_REQUEST['form_display_type'] != '' ) {
				global $wpdb,$tbl_arf_entry_values;
				$arf_meta_insert = array(
					'entry_value'  => sanitize_text_field( $_REQUEST['form_display_type'] ),
					'field_id'     => intval( 0 ),
					'entry_id'     => intval( $entry_id ),
					'created_date' => current_time( 'mysql' ),
				);
				$wpdb->insert( $tbl_arf_entry_values, $arf_meta_insert, array( '%s', '%d', '%d', '%s' ) );
			}

			if ( isset( $values['item_meta'] ) ) {
				if ( isset( $options['arf_twilio_to_number'] ) && '' != $options['arf_twilio_to_number'] ) {
					if ( isset( $values['item_meta'][ $options['arf_twilio_to_number'] ] ) && '' != trim( $values['item_meta'][ $options['arf_twilio_to_number'] ] ) ) {
						$values['item_meta'][ $options['arf_twilio_to_number'] ] = preg_replace( '/^0/', '', $values['item_meta'][ $options['arf_twilio_to_number'] ] );
					}
				}
				$tmp_key = array();
				foreach ( $values['item_meta'] as $key => $value ) {
					if ( strpos( $key, '_country_code' ) !== false ) {
						$key_id = str_replace( '_country_code', '', $key );
						if ( isset( $values['item_meta'][ $key_id ] ) && ! empty( $values['item_meta'][ $key_id ] ) ) {
							$tmp_key[ $key_id ] = $value;
						}
						unset( $values['item_meta'][ $key ] );
					}
				}

				foreach ( $tmp_key as $key => $value ) {
					if ( isset( $values['item_meta'][ $key ] ) ) {
						$value_explode  = explode( '[ARF_JOIN]', $value );
						$values_explode = explode( '[ARF_JOIN]', $values['item_meta'][ $key ] );
						$meta_val       = '';
						for ( $i = 0; $i < count( $value_explode ); $i++ ) {
							$meta_val .= $value_explode[ $i ] . ' ' . $values_explode[ $i ] . '[ARF_JOIN]';
						}
						$values['item_meta'][ $key ] = trim( $meta_val, '[ARF_JOIN]' );
					}
				}
				if ( isset( $_REQUEST[ 'arfform_date_formate_' . intval($_POST['form_id']) ] ) && '' != $_REQUEST[ 'arfform_date_formate_' . intval( $_POST['form_id'] ) ] ) {
					$arfliterecordmeta->arflite_update_entry_metas( $entry_id, $values['item_meta'], sanitize_text_field( $_REQUEST[ 'arfform_date_formate_' . intval( $_POST['form_id'] ) ] ) );
				} else {
					$arfliterecordmeta->arflite_update_entry_metas( $entry_id, $values['item_meta'], 'MMM D, YYYY' );
				}
			}

			$arflitecreatedentry[ intval( $_POST['form_id'] ) ]['entry_id'] = $entry_id;

			$entry_id = apply_filters( 'arflite_after_create_formentry', $entry_id, $new_values['form_id'] );
			if ( $entry_id == false || $entry_id == '' || ! isset( $entry_id ) ) {
				return false;
			}

			do_action( 'arfliteaftercreateentry', $entry_id, $new_values['form_id'] );
			return $entry_id;
		} else {
			return false;
		}
	}

	function &arflitedestroy( $id ) {

		global $wpdb, $tbl_arf_entry_values, $tbl_arf_entries;

		$id = (int) $id;

		$id = apply_filters( 'arflite_before_destroy_entry', $id );

		$wpdb->query( $wpdb->prepare( 'DELETE FROM ' . $tbl_arf_entry_values . ' WHERE entry_id=%d', $id ) ); //phpcs:ignore

		$result = $wpdb->query( $wpdb->prepare( 'DELETE FROM ' . $tbl_arf_entries . ' WHERE id=%d', $id ) ); //phpcs:ignore

		$result = apply_filters( 'arflite_after_destroy_entry', $result );

		return $result;
	}

	function arflitegetOne( $id, $meta = false ) {

		global $wpdb, $ARFLiteMdlDb, $tbl_arf_entries, $tbl_arf_forms;

		$query = "SELECT it.*, fr.name as form_name, fr.form_key as form_key FROM $tbl_arf_entries it


                  LEFT OUTER JOIN $tbl_arf_forms fr ON it.form_id=fr.id WHERE ";

		if ( is_numeric( $id ) ) {
			$query .= $wpdb->prepare( 'it.id=%d', $id );
		} else {
			$query .= $wpdb->prepare( 'it.entry_key=%s', $id );
		}

		$entry = $wpdb->get_row( $query ); //phpcs:ignore

		if ( $meta && $entry ) {

			global $arfliterecordmeta;

			$metas = $arfliterecordmeta->arflitegetAll( "entry_id=$entry->id and field_id != 0" );

			$entry_metas = array();

			foreach ( $metas as $meta_val ) {
				if ( preg_match( '/\[ARF_JOIN\]/', $meta_val->entry_value ) ) {
					$entry_metas_arr = explode( '[ARF_JOIN]', $meta_val->entry_value );
					$x               = 0;
					foreach ( $entry_metas_arr as $emeta_arr ) {
						$entry_metas[ $meta_val->field_id ][ $x ] = $entry_metas[ $meta_val->field_key ][ $x ] = maybe_unserialize( $emeta_arr );
						$x++;
					}
				} else {
					$entry_metas[ $meta_val->field_id ] = $entry_metas[ $meta_val->field_key ] = maybe_unserialize( $meta_val->entry_value );
				}
			}

			$entry->metas = $entry_metas;
		}

		return stripslashes_deep( $entry );
	}

	function arflitegetAll( $where = '', $order_by = '', $limit = '', $meta = false, $inc_form = true, $arfSearch = '', $arffieldorder = array() ) {

		global $wpdb, $ARFLiteMdlDb, $arflitemainhelper, $tbl_arf_entries, $tbl_arf_entry_values, $tbl_arf_forms, $tbl_arf_fields;

		if ( is_numeric( $limit ) ) {
			$limit = " LIMIT {$limit}";
		}

		$left_outer_join = '';

		$entry_table      = $tbl_arf_entries;
		$entry_meta_table = $tbl_arf_entry_values;

		$temp_cols   = 'it.id, it.entry_key, it.ip_address, it.created_date, it.browser_info, it.country, itmeta.entry_value';
		$temp_selcol = 'it.id, it.entry_key, it.name, it.ip_address, it.form_id, it.attachment_id, it.user_id, it.created_date';

		if ( $arfSearch != '' ) {
			$left_outer_join = " LEFT OUTER JOIN {$entry_meta_table} itmeta ON it.id=itmeta.entry_id ";
			$where          .= ' and Concat(' . $temp_cols . ") LIKE '%" . $arfSearch . "%'";
		}

		if ( $inc_form ) {

			$query = "SELECT it.*, fr.name as form_name,fr.form_key as form_key


                FROM $entry_table it LEFT OUTER JOIN $tbl_arf_forms fr ON it.form_id=fr.id" . $left_outer_join .
					$arflitemainhelper->arfliteprepend_and_or_where( ' WHERE ', $where ) . $order_by . $limit;
		} else {

			$query = 'SELECT ' . $temp_selcol . " FROM $entry_table it" . $left_outer_join . $arflitemainhelper->arfliteprepend_and_or_where( ' WHERE ', $where ) . $order_by . $limit;
		}

		$entries = $wpdb->get_results( $query, OBJECT_K );

		unset( $query );

		if ( $meta && $entries ) {

			if ( $limit == '' && ! is_array( $where ) && preg_match( '/^it\.form_id=\d+$/', $where ) ) {

				$meta_where = 'fi.form_id=' . substr( $where, 11 );
			} elseif ( $limit == '' && isset( $where['it.form_id'] ) && is_array( $where ) && count( $where ) == 1 ) {

				$meta_where = 'fi.form_id=' . $where['it.form_id'];
			} else {

				$meta_where = 'entry_id in (' . implode( ',', array_keys( $entries ) ) . ')';
			}

			$query = $wpdb->prepare( "SELECT entry_id, entry_value, field_id, fi.field_key as field_key FROM $entry_meta_table it LEFT OUTER JOIN $tbl_arf_fields fi ON it.field_id=fi.id WHERE $meta_where and field_id != %d", 0 ); //phpcs:ignore

			$metas = $wpdb->get_results( $query );

			unset( $query );

			if ( $metas ) {

				if ( count( $arffieldorder ) > 0 ) {

					$form_metas = array();
					foreach ( $arffieldorder as $fieldkey => $fieldorder ) {
						foreach ( $metas as $fieldmetakey => $fieldmetaval ) {
							if ( $fieldmetaval->field_id == $fieldkey ) {
								$form_metas[] = $fieldmetaval;
								unset( $metas[ $fieldmetakey ] );
							}
						}
					}

					if ( count( $form_metas ) > 0 ) {
						if ( count( $metas ) > 0 ) {
							$arfothermetas = $metas;
							$metas         = array_merge( $form_metas, $arfothermetas );
						} else {
							$metas = $form_metas;
						}
					}
				}

				foreach ( $metas as $m_key => $meta_val ) {

					if ( ! isset( $entries[ $meta_val->entry_id ] ) ) {
						continue;
					}

					if ( ! isset( $entries[ $meta_val->entry_id ]->metas ) ) {
						$entries[ $meta_val->entry_id ]->metas = array();
					}

					$entries[ $meta_val->entry_id ]->metas[ $meta_val->field_id ] = $entries[ $meta_val->entry_id ]->metas[ $meta_val->field_key ] = maybe_unserialize( $meta_val->entry_value );
				}
			}
		}

		return stripslashes_deep( $entries );
	}

	function arflitegetRecordCount( $where = '', $entry2 = false ) {

		global $wpdb, $ARFLiteMdlDb, $arflitemainhelper, $tbl_arf_entries, $tbl_arf_forms;

		/* $entry_table = $ARFLiteMdlDb->entries; */


		if ( is_numeric( $where ) ) {

			$query = "SELECT COUNT(*) FROM $tbl_arf_entries WHERE form_id=" . $where;
		} else {

			$query = "SELECT COUNT(*) FROM $tbl_arf_entries it LEFT OUTER JOIN $tbl_arf_forms fr ON it.form_id=fr.id" .
					$arflitemainhelper->arfliteprepend_and_or_where( ' WHERE ', $where );
		}

		return $wpdb->get_var( $query );
	}

	function arflitegetPageCount( $p_size, $where = '', $entry2 = false ) {

		if ( is_numeric( $where ) ) {
			return ceil( (int) $where / (int) $p_size );
		} else {
			return ceil( (int) $this->arflitegetRecordCount( $where, $entry2 ) / (int) $p_size );
		}
	}

	function arflitegetPage( $current_p, $p_size, $where = '', $order_by = '', $arfSearch = '', $arffieldorder = array() ) {

		global $wpdb, $ARFLiteMdlDb, $arflitemainhelper;

		$end_index = (int) $current_p * (int) $p_size;

		$start_index = (int) $end_index - (int) $p_size;

		if ( $current_p != '' && $p_size != '' ) {
			$results = $this->arflitegetAll( $where, $order_by, " LIMIT $start_index,$p_size;", true, true, $arfSearch, $arffieldorder );
		} else {
			$results = $this->arflitegetAll( $where, $order_by, '', true, true, $arfSearch, $arffieldorder );
		}

		return $results;
	}

	function arflitevalidate( $values, $exclude = false, $unset_custom_captcha = 0 ) {

	}

	function akismet( $values ) {

		global $akismet_api_host, $akismet_api_port, $arflitesiteurl;

		$content = '';

		foreach ( $values['item_meta'] as $val ) {

			if ( $content != '' ) {
				$content .= "\n\n";
			}

			if ( is_array( $val ) ) {
				$val = implode( ',', $val );
			}

			$content .= $val;
		}

		if ( $content == '' ) {
			return false;
		}

		$datas = array();

		$datas['blog'] = $arflitesiteurl;

		$arfremote_add = !empty( $_SERVER['REMOTE_ADDR'] ) ? $_SERVER['REMOTE_ADDR'] : '';

		$datas['user_ip'] = preg_replace( '/[^0-9., ]/', '', $arfremote_add );

		$datas['user_agent'] = !empty($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';

		$datas['referrer'] = !empty( $_SERVER['HTTP_REFERER']) ? esc_url_raw($_SERVER['HTTP_REFERER']) : false;

		$datas['comment_type'] = 'ARFormslite';

		if ( $permalink = get_permalink() ) {
			$datas['permalink'] = $permalink;
		}

		$datas['comment_content'] = $content;

		foreach ( $_SERVER as $key => $value ) {
			if ( ! in_array( $key, array( 'HTTP_COOKIE', 'argv' ) ) ) {
				$datas[ "$key" ] = $value;
			}
		}

		$query_string = '';

		foreach ( $datas as $key => $data ) {
			$query_string .= $key . '=' . urlencode( stripslashes( $data ) ) . '&';
		}

		$response = akismet_http_post( $query_string, $akismet_api_host, '/1.1/comment-check', $akismet_api_port );

		return ( is_array( $response ) && $response[1] == 'true' ) ? true : false;
	}

	function arflite_user_can_edit( $entry, $form ) {

		global $arflite_db_record;

		$allowed = $arflite_db_record->arflite_user_can_edit_check( $entry, $form );

		return apply_filters( 'arfliteusercanedit', $allowed, compact( 'entry', 'form' ) );
	}

	function arflite_user_can_edit_check( $entry, $form ) {

		global $user_ID, $arflitemainhelper, $arflite_db_record, $arfliteform;

		if ( ! $user_ID ) {
			return false;
		}

		if ( is_numeric( $form ) ) {
			$form = $arfliteform->arflitegetOne( $form );
		}

		$form->options = maybe_unserialize( $form->options );

		if ( is_object( $entry ) ) {

			if ( $entry->user_id == $user_ID ) {
				return true;
			} else {
				return false;
			}
		}

		$where = "user_id='$user_ID' and fr.id='$form->id'";

		if ( $entry && ! empty( $entry ) ) {

			if ( is_numeric( $entry ) ) {
				$where .= ' and it.id=' . $entry;
			} else {
				$where .= " and entry_key='" . $entry . "'";
			}
		}

		return $arflite_db_record->arflitegetAll( $where, '', ' LIMIT 1', true );
	}

}
