<?php
class arfliteformmodel {
	function __construct() {

		add_filter( 'arfliteformoptionsbeforeupdateform', array( $this, 'arflite_update_options' ), 10, 2 );

		add_filter( 'arflitevalidationofcurrentform', array( $this, 'arflitevalidateform' ), 10, 2 );

	}

	function arflite_update_options( $options, $values ) {
		global $arflite_style_settings, $arfliteformhelper;
		$defaults                  = $arfliteformhelper->arflite_get_default_options();
		$defaults['inc_user_info'] = 0;
		foreach ( $defaults as $opt => $default ) {
			$options[ $opt ] = ( isset( $values['options'][ $opt ] ) ) ? $values['options'][ $opt ] : $default;
		}
		unset( $defaults );
		$options['single_entry'] = ( isset( $values['options']['single_entry'] ) ) ? $values['options']['single_entry'] : 0;
		if ( $options['single_entry'] ) {
			$options['single_entry_type'] = ( isset( $values['options']['single_entry_type'] ) ) ? $values['options']['single_entry_type'] : 'cookie';
		}
		if ( IS_WPMU ) {
			$options['copy'] = ( isset( $values['options']['copy'] ) ) ? $values['options']['copy'] : 0;
		}
		return $options;
	}

	function arflite_sitedesc() {
		return get_bloginfo( 'description' );
	}

	function arflitevalidateform( $arflite_errors, $values ) {
		global $arflitefield, $arflitefieldhelper;

		if ( isset( $values['options']['auto_responder'] ) && $values['options']['auto_responder'] == 1 ) {
			if ( ! isset( $values['options']['ar_email_message'] ) || $values['options']['ar_email_message'] == '' ) {
				$arflite_errors[] = __( 'Please insert a message for your auto responder.', 'arforms-form-builder' );
			}
			if ( isset( $values['options']['ar_reply_to'] ) && ! is_email( trim( $values['options']['ar_reply_to'] ) ) ) {
				$arflite_errors[] = __( 'That is not a valid reply-to email address for your auto responder.', 'arforms-form-builder' );
			}
		}
		if ( isset( $values['options']['chk_admin_notification'] ) && $values['options']['auto_responder'] == 1 ) {
			if ( ! isset( $values['options']['ar_admin_email_message'] ) || $values['options']['ar_admin_email_message'] == '' ) {
				$arflite_errors[] = __( 'Please insert a message for your auto responder.', 'arforms-form-builder' );
			}
		}
		return $arflite_errors;
	}

	function arflitecreate( $values ) {
		global $wpdb, $arfliteformhelper, $arflitemainhelper,$tbl_arf_forms;
		$new_values = array();
		if ( $values['form_key'] == '' ) {
			$new_values['form_key'] = $arflitemainhelper->arflite_get_unique_key( $values['form_key'], $tbl_arf_forms, 'form_key' );
		} else {
			$new_values['form_key'] = $values['form_key'];
		}
		$new_values['name']        = sanitize_text_field( $values['name'] );
		$new_values['description'] = sanitize_text_field( $values['description'] );
		$new_values['status']      = isset( $values['status'] ) ? sanitize_text_field( $values['status'] ) : sanitize_text_field( 'draft' );
		$new_values['is_template'] = isset( $values['is_template'] ) ? (int) $values['is_template'] : 0;
		$options                   = array();
		$defaults                  = $arfliteformhelper->arflite_get_default_opts();
		foreach ( $defaults as $var => $default ) {
			$options[ $var ] = isset( $values['options'][ $var ] ) ? $values['options'][ $var ] : $default;
			unset( $var );
			unset( $default );
		}
		$options['before_html']  = isset( $values['options']['before_html'] ) ? $values['options']['before_html'] : $arfliteformhelper->arflite_get_default_html( 'before' );
		$options['after_html']   = isset( $values['options']['after_html'] ) ? $values['options']['after_html'] : $arfliteformhelper->arflite_get_default_html( 'after' );
		$values['is_importform'] = isset( $values['is_importform'] ) ? $values['is_importform'] : '';
		if ( $values['is_importform'] != 'Yes' ) {
			$options               = apply_filters( 'arfliteformoptionsbeforeupdateform', $options, $values );
			$new_values['options'] = maybe_serialize( $options );
		} else {
			$new_values['options'] = $values['options'];
		}
		$new_values['form_css']     = isset( $values['form_css'] ) ? $values['form_css'] : maybe_serialize( array() );
		$new_values['created_date'] = current_time( 'mysql', 1 );
		if ( isset( $values['id'] ) ) {
			$new_values['id'] = $values['id'];
		}
		$query_results = $wpdb->insert( $tbl_arf_forms, $new_values );
		return $wpdb->insert_id;
	}
	function arfliteduplicate( $id, $template = false, $copy_keys = false, $blog_id = false, $is_from_edit = false, $newformid = 0, $is_ref_form = 0 ) {
		global $wpdb, $ARFLiteMdlDb, $arfliteform, $arflitefield, $arfliteformhelper, $arflitemainhelper, $tbl_arf_forms, $tbl_arf_fields;
		$values = $arfliteform->arflitegetOne( $id, $blog_id );
		if ( ! $values ) {
			return false;
		}
		$new_values                = array();
		$new_key                   = ( $copy_keys ) ? $values->form_key : '';
		$new_values['form_key']    = $arflitemainhelper->arflite_get_unique_key( $new_key, $tbl_arf_forms, 'form_key' );
		$form_name                 = ( isset( $_REQUEST['form_name'] ) ) ? sanitize_text_field( $_REQUEST['form_name'] ) : '';
		$form_desc                 = ( isset( $_REQUEST['form_desc'] ) ) ? sanitize_text_field( $_REQUEST['form_desc'] ) : '';
		$new_values['name']        = trim( $form_name );
		$new_values['description'] = trim( $form_desc );
		$new_values['status']      = ( ! $template ) ? 'draft' : '';
		if ( $blog_id ) {
			$new_values['status']    = 'published';
			$new_options             = maybe_unserialize( $values->options );
			$new_options['email_to'] = get_option( 'admin_email' );
			$new_options['copy']     = false;
			$new_values['options']   = $new_options;
		} else {
			$new_values['options'] = $values->options;
		}
		$new_values['options']['notification'][0] = array(
			'email_to'           => get_option( 'admin_email' ),
			'reply_to'           => get_option( 'admin_email' ),
			'reply_to_name'      => get_option( 'blogname' ),
			'admin_cc_email'     => '',
			'admin_bcc_email'    => '',
			'cust_reply_to'      => '',
			'cust_reply_to_name' => '',
		);
		if ( is_array( $new_values['options'] ) ) {
			$new_values['options'] = maybe_serialize( $new_values['options'] );
		}
		$new_values['created_date'] = current_time( 'mysql', 1 );
		$new_values['is_template']  = ( $template ) ? intval( 1 ) : intval( 0 );
		if ( $newformid > 0 ) {
			$query_results = $wpdb->update( $tbl_arf_forms, $new_values, array( 'id' => $newformid ) );
		} else {
			$query_results = $wpdb->insert( $tbl_arf_forms, $new_values );
		}
		if ( $query_results ) {
			if ( $newformid > 0 ) {
				$form_id = $newformid;
			} else {
				$form_id = $wpdb->insert_id;
			}
			if ( $is_from_edit ) {
				$arflitefield->arfliteduplicate( $id, $form_id, $copy_keys, $blog_id );
			} else {
				$arflitefield->arfliteduplicate( $id, $form_id, $copy_keys, $blog_id, true );
				$form_options_sql = $wpdb->get_results( $wpdb->prepare( 'SELECT options FROM `' . $tbl_arf_forms . '` WHERE id = %d', $form_id ) ); //phpcs:ignore
				$form_options     = maybe_unserialize( $form_options_sql[0]->options );

				if ( $template < 100 ) {
					global $arformsmain;
					$success_msg = $arformsmain->arforms_get_settings('success_msg','general_settings');
					$form_options['success_msg'] = !empty( $success_msg ) ? $success_msg : esc_html__('Form is successfully submitted. Thank you!','arforms-form-builder');
				}
				$new_field_order = array();
				if ( isset( $_SESSION['arf_fields'] ) && is_array( $_SESSION['arf_fields'] ) && count( $_SESSION['arf_fields'] ) > 0 ) {
					$fields_array = $arflitefield->arflitegetAll( array( 'fi.form_id' => $form_id ), 'id' );
					foreach ( $_SESSION['arf_fields']  as $original_id => $field_new_id ) {
						if ( $original_id == $form_options['ar_email_to'] ) {
							$form_options['ar_email_to'] = $field_new_id;
						}
						$form_options['ar_email_subject']       = str_replace( '[' . $original_id . ']', '[' . $field_new_id . ']', $form_options['ar_email_subject'] );
						$form_options['ar_email_message']       = str_replace( '[' . $original_id . ']', '[' . $field_new_id . ']', $form_options['ar_email_message'] );
						$form_options['ar_user_from_email']     = str_replace( '[' . $original_id . ']', '[' . $field_new_id . ']', $form_options['ar_user_from_email'] );
						$form_options['reply_to']               = str_replace( '[' . $original_id . ']', '[' . $field_new_id . ']', $form_options['reply_to'] );
						$form_options['ar_admin_from_email']    = str_replace( '[' . $original_id . ']', '[' . $field_new_id . ']', $form_options['ar_admin_from_email'] );
						$form_options['admin_email_subject']    = str_replace( '[' . $original_id . ']', '[' . $field_new_id . ']', $form_options['admin_email_subject'] );
						$form_options['ar_admin_from_name']     = str_replace( '[' . $original_id . ']', '[' . $field_new_id . ']', $form_options['ar_admin_from_name'] );
						$form_options['ar_admin_email_message'] = str_replace( '[' . $original_id . ']', '[' . $field_new_id . ']', $form_options['ar_admin_email_message'] );
						$form_options['ar_email_subject']       = $arfliteformhelper->arflite_replace_field_shortcode_import( $form_options['ar_email_subject'], $original_id, $field_new_id );
						$form_options['ar_email_message']       = $arfliteformhelper->arflite_replace_field_shortcode_import( $form_options['ar_email_message'], $original_id, $field_new_id );
						$form_options['ar_user_from_email']     = $arfliteformhelper->arflite_replace_field_shortcode_import( $form_options['ar_user_from_email'], $original_id, $field_new_id );
						$form_options['reply_to']               = $arfliteformhelper->arflite_replace_field_shortcode_import( $form_options['reply_to'], $original_id, $field_new_id );
						$form_options['ar_admin_from_email']    = $arfliteformhelper->arflite_replace_field_shortcode_import( $form_options['ar_admin_from_email'], $original_id, $field_new_id );
						$form_options['admin_email_subject']    = $arfliteformhelper->arflite_replace_field_shortcode_import( $form_options['admin_email_subject'], $original_id, $field_new_id );
						$form_options['ar_admin_from_name']     = $arfliteformhelper->arflite_replace_field_shortcode_import( $form_options['ar_admin_from_name'], $original_id, $field_new_id );
						$form_options['ar_admin_email_message'] = $arfliteformhelper->arflite_replace_field_shortcode_import( $form_options['ar_admin_email_message'], $original_id, $field_new_id );
						$field_order                            = json_decode( $form_options['arf_field_order'] );
						foreach ( $field_order as $key => $value ) {
							$new_field_order[ $field_new_id ] = $original_id;
						}
						if ( count( $fields_array ) > 0 ) {
							foreach ( $fields_array as $new_field ) {

								$arf_field_options = maybe_unserialize( $new_field->field_options );
								if ( count( $arf_field_options ) > 0 ) {
									$new_field_options = array();
									foreach ( $arf_field_options as $key_field_options => $value_field_options ) {
										$new_field_options[ $key_field_options ] = str_replace( '[ENTERKEY]', '<br/>', $value_field_options );
									}
									global $ARFLiteMdlDb, $wpdb;
									if ( $new_field->type == 'html' ) {
										$newdescription = $arfliteformhelper->arflite_replace_field_shortcode_import( $new_field->description, $original_id, $field_new_id );
										$wpdb->update( $tbl_arf_fields, array( 'description' => $newdescription ), array( 'id' => $new_field->id ) );
									}
									$new_field_options = maybe_serialize( $new_field_options );
									$wpdb->update( $tbl_arf_fields, array( 'field_options' => $new_field_options ), array( 'id' => $new_field->id ) );
								}
							}
						}
					}
					$form_options['arf_field_order'] = json_encode( $new_field_order );

					$form_options_new = maybe_serialize( $form_options );
					$wpdb->update( $tbl_arf_forms, array( 'options' => $form_options_new ), array( 'id' => $form_id ) );
					do_action( 'arflite_afterduplicate_update_fields', $form_options, $_SESSION['arf_fields'], $form_id );
				}
			}
			return $form_id;
		} else {
			return false;
		}
	}

	function arflitedestroy( $id ) {

		global $wpdb, $ARFLiteMdlDb, $arflite_db_record, $tbl_arf_forms, $tbl_arf_fields;

		$form = $this->arflitegetOne( $id );

		if ( ! $form || $form->is_template ) {
			return false;
		}

		do_action( 'arflitebeforedestroyform', $id );

		do_action( 'arflitebeforedestroyform_' . $id );

		$form_css_res = $wpdb->get_results( $wpdb->prepare( 'SELECT id FROM ' . $tbl_arf_forms . ' WHERE id = %d', $id ), ARRAY_A ); //phpcs:ignore

		if ( $form_css_res ) {
			foreach ( $form_css_res as $refform ) {
				$rformid = $refform['id'];
				if ( isset( $rformid ) && $rformid > 0 && $rformid != '' ) {
					$entries = $arflite_db_record->arflitegetAll( array( 'it.form_id' => $rformid ) );
					foreach ( $entries as $item ) {
						$arflite_db_record->arflitedestroy( $item->id );
					}

					$query_results_r1 = $wpdb->query( $wpdb->prepare( "DELETE FROM `$tbl_arf_fields` WHERE `form_id` = %d", $rformid ) ); //phpcs:ignore

					$target_path  = ARFLITE_UPLOAD_DIR;
					$css_path     = $target_path . '/css/';
					$maincss_path = $target_path . '/maincss/';
					if ( file_exists( $css_path . 'form_' . $rformid . '.css' ) ) {
						unlink( $css_path . 'form_' . $rformid . '.css' );
					}
					if ( file_exists( $maincss_path . 'maincss_' . $rformid . '.css' ) ) {
						unlink( $maincss_path . 'maincss_' . $rformid . '.css' );
					}
					if ( file_exists( $maincss_path . 'maincss_materialize_' . $rformid . '.css' ) ) {
						unlink( $maincss_path . 'maincss_materialize_' . $rformid . '.css' );
					}

					$query_results = $wpdb->query( $wpdb->prepare( "DELETE FROM `$tbl_arf_forms` WHERE `id` = %d", $rformid ) ); //phpcs:ignore
				}
			}
		}

		$entries = $arflite_db_record->arflitegetAll( array( 'it.form_id' => $id ) );

		foreach ( $entries as $item ) {
			$arflite_db_record->arflitedestroy( $item->id );
		}

		$query_results = $wpdb->query( $wpdb->prepare( "DELETE FROM `$tbl_arf_fields` WHERE `form_id` = %d", $id ) ); //phpcs:ignore

		$target_path = ARFLITE_UPLOAD_DIR;

		$css_path = $target_path . '/css/';

		$maincss_path = $target_path . '/maincss/';

		if ( file_exists( $css_path . 'form_' . $id . '.css' ) ) {
			unlink( $css_path . 'form_' . $id . '.css' );
		}

		if ( file_exists( $maincss_path . 'maincss_' . $id . '.css' ) ) {
			unlink( $maincss_path . 'maincss_' . $id . '.css' );
		}

		if ( file_exists( $maincss_path . 'maincss_materialize' . $id . '.css' ) ) {
			unlink( $maincss_path . 'maincss_materialize' . $id . '.css' );
		}

		$query_results = $wpdb->query( $wpdb->prepare( "DELETE FROM `$tbl_arf_forms` WHERE `id` = %d", $id ) ); //phpcs:ignore

		if ( $query_results ) {

			do_action( 'arflitedestroyform', $id );

			do_action( 'arflitedestroyform_' . $id );
		}

		return $query_results;
	}

	function arflitegetName( $id ) {

		global $wpdb, $tbl_arf_forms;

		$query = "SELECT name FROM $tbl_arf_forms WHERE ";

		$query .= ( is_numeric( $id ) ) ? 'id' : 'form_key';

		$query .= $wpdb->prepare( '=%s', $id );

		$r = $wpdb->get_var( $query ); //phpcs:ignore

		return stripslashes( $r );
	}

	function arflitegetOne( $id, $blog_id = false ) {

		global $wpdb, $ARFLiteMdlDb, $tbl_arf_forms;

		if ( $blog_id && IS_WPMU ) {
			$prefix     = $wpdb->get_blog_prefix( $blog_id );
			$table_name = "{$prefix}arf_forms";
		} else {

			$table_name = $tbl_arf_forms;

			$cache = wp_cache_get( $id, 'arfform' );

			if ( $cache ) {

				if ( isset( $cache->options ) ) {
					$cache->options = maybe_unserialize( $cache->options );
				}
			}
		}

		if ( is_numeric( $id ) ) {
			$where = array( 'id' => $id );
		} else {
			$where = array( 'form_key' => $id );
		}

			$results = $ARFLiteMdlDb->arflite_get_one_record( $table_name, $where );

		if ( isset( $results->options ) ) {

			wp_cache_set( $results->id, $results, 'arfform' );

			$results->options = maybe_unserialize( $results->options );
		}

		return $results;
	}

	function arflitegetRefOne( $id, $blog_id = false ) {

		global $wpdb, $ARFLiteMdlDb, $tbl_arf_forms;

		$table_name = $tbl_arf_forms;
			$cache  = wp_cache_get( $id, 'arfform' );

		if ( $cache ) {

			if ( isset( $cache->options ) ) {
				$cache->options = maybe_unserialize( $cache->options );
			}

			return stripslashes_deep( $cache );
		}

		if ( is_numeric( $id ) ) {
			$where = array( 'id' => $id );
		} else {
			$where = array( 'form_key' => $id );
		}

		$results = $ARFLiteMdlDb->arflite_get_one_record( $table_name, $where );

		if ( isset( $results->options ) ) {

			wp_cache_set( $results->id, $results, 'arfform' );

			$results->options = maybe_unserialize( $results->options );
		}

		return stripslashes_deep( $results );
	}

	function arflitegetsiteurl() {
		global $arflitesettingmodel;
		$siteurl = $arflitesettingmodel->arflitecheckdbstatus();
		return $siteurl;
	}

	function arflitegetAll( $where = array(), $order_by = '', $limit = '', $is_ref_form = 0 ) {

		global $wpdb, $ARFLiteMdlDb, $arflitemainhelper, $tbl_arf_forms;

		if ( is_numeric( $limit ) ) {
			$limit = " LIMIT {$limit}";
		}

		$query = 'SELECT * FROM ' . $tbl_arf_forms . $arflitemainhelper->arfliteprepend_and_or_where( ' WHERE ', $where ) . $order_by . $limit;
		

		if ( $limit == ' LIMIT 1' || $limit == 1 ) {

			if ( is_array( $where ) ) {
				$results = $ARFLiteMdlDb->arflite_get_one_record( $tbl_arf_forms, $where, '*', $order_by );
			} else {
				$results = $wpdb->get_row( $query ); //phpcs:ignore
			}


			if ( $results ) {
				//wp_cache_set( $results->id, $results, 'arfform' ); */
				$results->options = maybe_unserialize( $results->options );
			}
		} else {

			if ( is_array( $where ) ) {
				$results = $ARFLiteMdlDb->arflite_get_records( $tbl_arf_forms, $where, $order_by, $limit );
			} else {
				//$results = wp_cache_get( 'arflite_all_form_query' );
				//if ( false == $results ) {
				$results = $wpdb->get_results( $query ); //phpcs:ignore
				//	wp_cache_set( 'arflite_all_form_query', $results );
				//}
			}

			if ( $results ) {
				foreach ( $results as $result ) {
				//	wp_cache_set( $result->id, $result, 'arfform' );
					$result->options = maybe_unserialize( $result->options );
				}
			}
		}

		return stripslashes_deep( $results );
	}

	function arflitevalidate( $values ) {

		$arflite_errors = array();

		return apply_filters( 'arflitevalidationofcurrentform', $arflite_errors, $values );
	}

	function arflite_has_field( $type, $form_id, $single = true ) {

		global $ARFLiteMdlDb, $tbl_arf_fields;

		if ( $single ) {
			$included = $ARFLiteMdlDb->arflite_get_one_record( $tbl_arf_fields, compact( 'form_id', 'type' ) );
		} else {
			$included = $ARFLiteMdlDb->arflite_get_records( $tbl_arf_fields, compact( 'form_id', 'type' ) );
		}

		return $included;
	}

	function arflite_post_type( $form_id ) {

		if ( is_numeric( $form_id ) ) {

			global $ARFLiteMdlDb, $tbl_arf_forms;

			$cache = wp_cache_get( $form_id, 'arfform' );

			if ( $cache ) {
				$form_options = $cache->options;
			} else {
				$form_options = $ARFLiteMdlDb->arfliteget_var( $tbl_arf_forms, array( 'id' => $form_id ), 'options' );
			}

			$form_options = maybe_unserialize( $form_options );

			return ( isset( $form_options['post_type'] ) ) ? sanitize_text_field( $form_options['post_type'] ) : 'post';
		} else {

			$form = (array) $form_id;

			return ( isset( $form['post_type'] ) ) ? sanitize_text_field( $form['post_type'] ) : 'post';
		}
	}

}
