<?php
//phpcs:ignoreFile

@ini_set( 'memory_limit', '512M' );

global $arfliterecordhelper,$arfliterecordcontroller,$arflitemaincontroller,$arflitefieldhelper,$arflitemainhelper,$ARFLiteMdlDb,$arfliterecordmeta, $tbl_arf_entries;

	//$arflitemaincontroller->arfliteafterinstall();
	global $arflite_style_settings;

	$form_id = $all_form_id;

	$form = $arfliteform->arflitegetOne( $form_id );

	$form_name = sanitize_title_with_dashes( $form->name );

	$form_cols = $arflitefield->arflitegetAll( "fi.type not in ('captcha', 'html') and fi.form_id=" . $form->id, 'id ASC' );

if ( ! isset( $_REQUEST['bulk_export'] ) || ( isset( $_REQUEST['bulk_export'] ) && sanitize_text_field( $_REQUEST['bulk_export'] ) != 'yes' ) ) {
	$entry_id = $arflitemainhelper->arflite_get_param( 'entry_id', false );
} elseif ( isset( $_REQUEST['bulk_export'] ) && sanitize_text_field( $_REQUEST['bulk_export'] ) == 'yes' ) {

	if ( ! empty( $_REQUEST['date_from'] ) || ! empty( $_REQUEST['date_to'] ) ) {

		$date_from = date( 'Y-m-d 00:00:00', strtotime( sanitize_text_field( $_REQUEST['date_from'] ) ) );
		$date_to   = date( 'Y-m-d 23:59:59', strtotime( sanitize_text_field( $_REQUEST['date_to'] ) ) );

		$form_entry_ids = $wpdb->get_results( $wpdb->prepare( 'SELECT id FROM `' . $tbl_arf_entries . '` WHERE form_id = %d AND created_date BETWEEN %s AND %s ', $form_id, $date_from, $date_to ) ); //phpcs:ignore
	} else {
		$form_entry_ids = $wpdb->get_results( $wpdb->prepare( 'SELECT id FROM `' . $tbl_arf_entries . '` WHERE form_id = %d', $form_id ) ); //phpcs:ignore
	}
	$entry_id = '';
	foreach ( $form_entry_ids as $frm_entry_id ) {
		$entry_id .= $frm_entry_id->id . ',';
	}
	$entry_id = rtrim( $entry_id, ',' );
}

	$where_clause = 'it.form_id=' . (int) $form_id;

if ( $entry_id ) {

	$where_clause .= ' and it.id in (';

	$entry_ids = explode( ',', $entry_id );

	foreach ( (array) $entry_ids as $k => $it ) {
		if ( $k ) {
			$where_clause .= ',';
		}

		$where_clause .= $it;

		unset( $k );

		unset( $it );
	}

	$where_clause .= ')';
} elseif ( ! empty( $search ) ) {
	$where_clause = $this->arflite_get_search_str( $where_clause, $search, $form_id, $fid );
}

	$where_clause = apply_filters( 'arflitecsvwhere', $where_clause, compact( 'form_id' ) );

	$entries = $arflite_db_record->arflitegetAll( $where_clause, '', '', true, false );

	$form_cols = apply_filters( 'arflitepredisplayformcols', $form_cols, $form->id );
	$entries   = apply_filters( 'arflitepredisplaycolsitems', $entries, $form->id );

	$max_cols = 0;

	$filename = 'ARForms_' . $form_name . '_' . time() . '_0.csv';

	$wp_date_format = apply_filters( 'arflitecsvdateformat', 'Y-m-d H:i:s' );

	$charset = get_option( 'blog_charset' );

	$to_encoding = 'UTF-8';

	$entry_separator_id = get_option( 'arf_form_entry_separator' );

	if ( $entry_separator_id == 'arf_comma' ) {
		$entry_separator = ',';
	} elseif ( $entry_separator_id == 'arf_semicolon' ) {
		$entry_separator = ';';
	} elseif ( $entry_separator_id == 'arf_pipe' ) {
		$entry_separator = '|';
	}

	header( 'Content-Description: File Transfer' );
	header( "Content-Disposition: attachment; filename=\"$filename\"" );
	header( 'Content-Type: text/csv; charset=' . $charset, true );
	header( 'Expires: ' . gmdate( 'D, d M Y H:i:s', mktime( date( 'H' ) + 2, date( 'i' ), date( 's' ), date( 'm' ), date( 'd' ), date( 'Y' ) ) ) . ' GMT' );
	header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
	header( 'Cache-Control: no-cache, must-revalidate' );
	header( 'Pragma: no-cache' );


		$field_order = ! empty( $form->options['arf_field_order'] ) ? arflite_json_decode( $form->options['arf_field_order'], true ) : array();
	$new_form_cols   = array();

	asort( $field_order );
	$hidden_fields    = array();
	$hidden_field_ids = array();
foreach ( $field_order as $field_id => $fields_order ) {
	if ( is_int( $field_id ) ) {
		foreach ( $form_cols as $field ) {
			if ( $field_id == $field->id ) {
				$new_form_cols[] = $field;

			} elseif ( $field->type == 'hidden' ) {
				if ( ! in_array( $field->id, $hidden_field_ids ) ) {
					$hidden_fields[]    = $field;
					$hidden_field_ids[] = $field->id;
				}
			}
		}
	}
}

if ( count( $hidden_fields ) > 0 ) {
	$new_form_cols = array_merge( $new_form_cols, $hidden_fields );
}


	$form_cols = $new_form_cols;


	echo '"ID"' . $entry_separator;
foreach ( $form_cols as $col ) {


	if ( ! empty( $col->name ) ) {

		echo '"' . $arfliterecordhelper->arflite_encode_value( strip_tags( $col->name ), $charset, $to_encoding ) . '"' . $entry_separator . ''; //phpcs:ignore
	} else {

		echo '"Field_id:' . intval($col->id) . '"' . $entry_separator . '';
	}
}

	echo '"' . esc_html__( 'Timestamp', 'arforms-form-builder' ) . '"' . $entry_separator . '"IP"' . $entry_separator . '"Key"' . $entry_separator . '"Country"' . $entry_separator . '"Browser"' . $entry_separator . '"Page URL"' . $entry_separator . '"Referrer URL"' . "\n";


foreach ( $entries as $entry ) {
	global $wpdb, $tbl_arf_entries, $tbl_arf_entry_values;
	echo "\"{$entry->id}\"$entry_separator";
	$res_data    = $wpdb->get_results( $wpdb->prepare( 'SELECT description,country, browser_info FROM ' . $tbl_arf_entries . ' WHERE id = %d', $entry->id ), 'ARRAY_A' ); //phpcs:ignore
	$description = maybe_unserialize( $res_data[0]['description'] );
	/* changes http_page_url start */
	$arflite_page_url = $wpdb->get_row( $wpdb->prepare( 'SELECT entry_value FROM ' . $tbl_arf_entry_values . " WHERE field_id='%d' AND entry_id='%d'", '-' . 0, $entry->id ) ); //phpcs:ignore
	if ( ! empty( $arflite_page_url ) ) {
		$http_referrer_url = $arflite_page_url->entry_value;
		if ( ! empty( $http_referrer_url ) ) {
			$http_page_url   = explode( '|', $http_referrer_url );
			$entry->page_url = isset( $http_page_url[1] ) ? esc_url( $http_page_url[1] ) : '';
		}
	} else {
		$entry->page_url = isset( $description['page_url'] ) ? esc_html( $description['page_url'] ) : '';
	}
	/*
	 changes http_page_url end */
	/* $entry->page_url   = isset( $description['page_url'] ) ? $description['page_url'] : ''; */
	$entry->referrer   = isset( $description['http_referrer'] ) ? esc_url( $description['http_referrer'] ) : '';
	$entry->country    = $res_data[0]['country'];
	$arfrecord_browser = $arfliterecordcontroller->arflitegetBrowser( $res_data[0]['browser_info'] );
	$entry->browser    = $arfrecord_browser['name'] . ' (Version: ' . $arfrecord_browser['version'] . ')';
	foreach ( $form_cols as $col ) {

		$field_value = isset( $entry->metas[ $col->id ] ) ? $entry->metas[ $col->id ] : '';
		if ( ! $field_value && $entry->attachment_id ) {
			$col->field_options = arflite_json_decode( $col->field_options, true );
		}

		if ( $col->type == 'date' ) {
			$field_value = $arflitefieldhelper->arfliteget_date( $field_value, $wp_date_format );
		} elseif ( 'checkbox' == $col->type ) {
			if ( isset( $col->field_options['separate_value'] ) && 1 == $col->field_options['separate_value'] ) {
				$temp_field_val = '';

				foreach ( $col->field_options['options'] as $fopt ) {
					if ( is_array( $field_value ) && in_array( $fopt['value'], $field_value ) ) {
						$temp_field_val .= $fopt['label'] . ' (' . $fopt['value'] . '),';
					} elseif ( $fopt['value'] == $field_value ) {
						$temp_field_val .= $fopt['label'] . ' (' . $field_value . '),';
					}
				}
				$field_value = rtrim( $temp_field_val, ',' );
			} elseif ( is_array( $field_value ) ) {
				$field_value = implode( ', ', $field_value );
			} else {
				$field_value = $field_value;
			}
		} elseif ( 'radio' == $col->type || 'select' == $col->type ) {
			if ( isset( $col->field_options['separate_value'] ) && 1 == $col->field_options['separate_value'] ) {
				$temp_field_val = '';

				foreach ( $col->field_options['options'] as $fopt ) {
					if ( $fopt['value'] == $field_value ) {
						$temp_field_val .= $fopt['label'] . ' (' . $field_value . ')';
					}
				}
				$field_value = $temp_field_val;
			}
		} else {
			$checked_values = arflite_json_decode( $field_value, true );
			$checked_values = apply_filters( 'arflitecsvvalue', $checked_values, array( 'field' => $col ) );
			if ( is_array( $checked_values ) ) {
					$field_value = implode( ', ', $checked_values );
			} else {
				$field_value = $checked_values;
			}
			$field_value = $arfliterecordhelper->arflite_encode_value( $field_value, $charset, $to_encoding );
			$field_value = str_replace( '"', '""', stripslashes( $field_value ) );
		}

		echo "\"$field_value\"$entry_separator"; //phpcs:ignore
		unset( $col );
		unset( $field_value );
	}
	$formatted_date = date( $wp_date_format, strtotime( $entry->created_date ) );
	echo "\"{$formatted_date}\"$entry_separator";
	echo "\"{$entry->ip_address}\"$entry_separator";
	echo "\"{$entry->entry_key}\"$entry_separator";
	echo "\"{$entry->country}\"$entry_separator";
	echo "\"{$entry->browser}\"$entry_separator";
	echo "\"{$entry->page_url}\"$entry_separator";
	echo "\"{$entry->referrer}\"$entry_separator\n";
	unset( $entry );
}
