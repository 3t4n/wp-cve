<?php

class arfliterecordhelper {

	function __construct() {

		add_filter( 'arfliteemailvalue', array( $this, 'arflite_email_value' ), 10, 3 );
	}

	function arflite_email_value( $value, $meta, $entry ) {
		global $arflitefield, $arflite_db_record, $arflitefieldhelper;
		if ( $entry->id != $meta->entry_id ) {
			$entry = $arflite_db_record->arflitegetOne( $meta->entry_id );
		}
		$field = $arflitefield->arflitegetOne( $meta->field_id );
		if ( ! $field ) {
			return $value;
		}
		$field->field_options = maybe_unserialize( $field->field_options );
		switch ( $field->type ) {
			case 'date':
				$value = $arflitefieldhelper->arfliteget_date_entry( $value, $field->form_id, $field->field_options['show_time_calendar'], $field->field_options['clock'], $field->field_options['locale'] );
		}
		if ( is_array( $value ) ) {
			$new_value = '';
			foreach ( $value as $val ) {
				if ( is_array( $val ) ) {
					$new_value .= implode( ', ', $val ) . "\n";
				}
			}
			if ( $new_value != '' ) {
				$value = rtrim( $new_value, ',' );
			}
		}
		return $value;
	}

	function arflite_allow_delete( $entry ) {

		global $user_ID;

		$allowed = false;

		if ( current_user_can( 'arfdeleteentries' ) ) {
			$allowed = true;
		}

		if ( $user_ID && ! $allowed ) {

			if ( is_numeric( $entry ) ) {

				global $ARFLiteMdlDb, $tbl_arf_entries;

				$allowed = $ARFLiteMdlDb->arfliteget_var(
					$tbl_arf_entries,
					array(
						'id'      => $entry,
						'user_id' => $user_ID,
					)
				);
			} else {

				$allowed = ( $entry->user_id == $user_ID );
			}
		}

		return apply_filters( 'arfliteallowdelete', $allowed, $entry );
	}

	function arflite_setup_new_vars( $fields, $form = '', $reset = false ) {

		global $arfliteform, $arformsmain, $arflitesidebar_width, $arflitefieldhelper, $arflitemainhelper, $arfliteformhelper;

		$values = array();

		foreach ( array(
			'name'        => '',
			'description' => '',
			'entry_key'   => '',
		) as $var => $default ) {
			$values[ $var ] = $arflitemainhelper->arflite_get_post_param( $var, $default );
		}

		$values['fields'] = array();

		if ( $fields ) {

			foreach ( $fields as $field ) {

				$field_options = $field->field_options;

				$default = isset( $field->field_options['default_value'] ) ? sanitize_text_field( $field->field_options['default_value'] ) : '';

				if ( $reset ) {
					$new_value = $default;
				} else {
					$new_value = ( $_POST && isset( $_POST['item_meta'][ intval($field->id) ] ) && $_POST['item_meta'][ intval($field->id) ] != '' ) ? $_POST['item_meta'][ intval( $field->id ) ] : $default; //phpcs:ignore
				}

				$is_default = ( $new_value == $default ) ? true : false;

				if ( ! is_array( $new_value ) ) {
					$new_value = apply_filters( 'arflitegetdefaultvalue', $new_value, $field );
				}

				$new_value = str_replace( '"', '&quot;', $new_value );

				if ( $is_default ) {
					$field->default_value = $new_value;
				} else {
					$field->default_value = apply_filters( 'arflitegetdefaultvalue', $field->default_value, $field );
				}

				$field_array = array(
					'id'           => $field->id,
					'value'        => $new_value,
					'name'         => $field->name,
					'type'         => apply_filters( 'arflitefieldtype', $field->type, $field, $new_value ),
					'options'      => $field->options,
					'required'     => $field->required,
					'field_key'    => $field->field_key,
					'form_id'      => $field->form_id,
					'option_order' => maybe_unserialize( $field->option_order ),
				);

				$opt_defaults = $arflitefieldhelper->arflite_get_default_field_options( $field_array['type'], $field, true );

				$opt_defaults['required_indicator'] = '';

				foreach ( $opt_defaults as $opt => $default_opt ) {

					$field_array[ $opt ] = ( isset( $field->field_options[ $opt ] ) && $field->field_options[ $opt ] != '' ) ? $field->field_options[ $opt ] : $default_opt;
					unset( $opt );

					unset( $default_opt );
				}

				unset( $opt_defaults );

				if ( $field_array['size'] == '' ) {
					$field_array['size'] = $arflitesidebar_width;
				}

				if ( $field_array['custom_html'] == '' ) {
					$field_array['custom_html'] = $arflitefieldhelper->arflite_get_basic_default_html( $field->type );
				}

				$field_array = apply_filters( 'arflitesetupnewfieldsvars', $field_array, $field );

				foreach ( (array) $field->field_options as $k => $v ) {

					if ( ! isset( $field_array[ $k ] ) ) {
						$field_array[ $k ] = $v;
					}

					unset( $k );

					unset( $v );
				}

				$values['fields'][] = $field_array;

				if ( ! $form || ! isset( $form->id ) ) {
					$form = $arfliteform->arflitegetOne( $field->form_id );
				}
			}

			$form_options = isset( $form->options ) ? maybe_unserialize( $form->options ) : '';

			if ( is_array( $form_options ) ) {

				foreach ( $form_options as $opt => $value ) {
					$values[ $opt ] = $arflitemainhelper->arflite_get_post_param( $opt, $value );
				}
			}

			if ( ! isset( $values['custom_style'] ) ) {
				$load_style = $arformsmain->arforms_get_settings('load_style','general_settings');
				$load_style = !empty( $load_style ) ? $load_style : 'none';

				$values['custom_style'] = ( $load_style != 'none' );
			}

			if ( ! isset( $values['email_to'] ) ) {
				$values['email_to'] = '';
			}

			if ( ! isset( $values['submit_value'] ) ) {

				$submit_value = $arformsmain->arforms_get_settings('submit_value','general_settings');
				$submit_value = !empty( $submit_value ) ? $submit_value : esc_html__('Form is successfully submitted. Thank you!','arforms-form-builder');
				$values['submit_value'] = $submit_value;
			}

			if ( ! isset( $values['success_msg'] ) ) {

				$success_msg = $arformsmain->arforms_get_settings('submit_value','general_settings');
				$success_msg = !empty( $success_msg ) ? $success_msg : esc_html__('Form is successfully submitted. Thank you!','arforms-form-builder');
				$values['success_msg'] = $success_msg;
			}

			if ( ! isset( $values['akismet'] ) ) {
				$values['akismet'] = '';
			}

			if ( ! isset( $values['before_html'] ) ) {
				$values['before_html'] = $arfliteformhelper->arflite_get_default_html( 'before' );
			}

			if ( ! isset( $values['after_html'] ) ) {
				$values['after_html'] = $arfliteformhelper->arflite_get_default_html( 'after' );
			}
		}

		return apply_filters( 'arflitesetupnewentry', $values );
	}

	function arflite_encode_value( $line, $from_encoding, $to_encoding ) {

		$convmap = false;

		switch ( $to_encoding ) {
			case 'macintosh':
				$convmap = array(
					256,
					304,
					0,
					0xffff,
					306,
					337,
					0,
					0xffff,
					340,
					375,
					0,
					0xffff,
					377,
					401,
					0,
					0xffff,
					403,
					709,
					0,
					0xffff,
					712,
					727,
					0,
					0xffff,
					734,
					936,
					0,
					0xffff,
					938,
					959,
					0,
					0xffff,
					961,
					8210,
					0,
					0xffff,
					8213,
					8215,
					0,
					0xffff,
					8219,
					8219,
					0,
					0xffff,
					8227,
					8229,
					0,
					0xffff,
					8231,
					8239,
					0,
					0xffff,
					8241,
					8248,
					0,
					0xffff,
					8251,
					8259,
					0,
					0xffff,
					8261,
					8363,
					0,
					0xffff,
					8365,
					8481,
					0,
					0xffff,
					8483,
					8705,
					0,
					0xffff,
					8707,
					8709,
					0,
					0xffff,
					8711,
					8718,
					0,
					0xffff,
					8720,
					8720,
					0,
					0xffff,
					8722,
					8729,
					0,
					0xffff,
					8731,
					8733,
					0,
					0xffff,
					8735,
					8746,
					0,
					0xffff,
					8748,
					8775,
					0,
					0xffff,
					8777,
					8799,
					0,
					0xffff,
					8801,
					8803,
					0,
					0xffff,
					8806,
					9673,
					0,
					0xffff,
					9675,
					63742,
					0,
					0xffff,
					63744,
					64256,
					0,
					0xffff,
				);
				break;
			case 'ISO-8859-1':
				$convmap = array( 256, 10000, 0, 0xffff );
				break;
		}

		if ( is_array( $convmap ) ) {
			$line = mb_encode_numericentity( $line, $convmap, $from_encoding );
		}

		if ( $to_encoding != $from_encoding ) {
			return iconv( $from_encoding, $to_encoding . '//IGNORE', $line );
		} else {
			return $line;
		}
	}

	function arflitedisplay_value( $value, $field, $atts = array(), $form_css = array(), $incomplete_entry = false, $form_name = '' ) {

		global $wpdb, $arflitefieldhelper, $arflitemainhelper, $ARFLiteMdlDb, $tbl_arf_entries, $tbl_arf_entry_values;

		$entry_table      = $tbl_arf_entries;
		$entry_meta_table = $tbl_arf_entry_values;
		$defaults = array(
			'type'          => '',
			'show_icon'     => true,
			'show_filename' => true,
			'truncate'      => false,
			'sep'           => ', ',
			'attachment_id' => 0,
			'form_id'       => $field->form_id,
			'field'         => $field,
		);

		$atts = wp_parse_args( $atts, $defaults );

		$field->field_options = maybe_unserialize( $field->field_options );

		if ( ! isset( $field->field_options['post_field'] ) ) {
			$field->field_options['post_field'] = '';
		}

		if ( ! isset( $field->field_options['custom_field'] ) ) {
			$field->field_options['custom_field'] = '';
		}

		if ( $value == '' ) {
			$value = '-';
			return $value;
		}

		$value = maybe_unserialize( $value );

		if ( is_array( $value ) ) {
			$value = stripslashes_deep( $value );
		}

		$value = apply_filters( 'arflitedisplayvaluecustom', $value, $field, $atts );

		$new_value = '';

		if ( is_array( $value ) ) {

			foreach ( $value as $val ) {

				if ( is_array( $val ) ) {

					$new_value .= implode( $atts['sep'], $val );

					if ( $atts['type'] != 'data' ) {
						$new_value .= '<br/>';
					}
				}

				unset( $val );
			}
		}

		if ( ! empty( $new_value ) ) {
			$value = $new_value;
		} elseif ( is_array( $value ) ) {
			$value = implode( $atts['sep'], $value );
		}

		if ( $atts['truncate'] && $atts['type'] != 'image' && $atts['type'] != 'select' ) {
			$value = $arflitemainhelper->arflitetruncate( $value, 50 );
		}

		if ( $atts['type'] == 'image' ) {
			$value = '<img src="' . $value . '" height="50px" alt="" />';
		} elseif ( $atts['type'] == 'date' ) {

			$value = $arflitefieldhelper->arfliteget_date_entry( $value, $field->form_id, $field->field_options['show_time_calendar'], $field->field_options['clock'], $field->field_options['locale'] );
		} elseif ( $atts['type'] == 'time' ) {
			$value = date_i18n( get_option( 'time_format' ), strtotime( $value ) );
		} elseif ( $atts['type'] == 'textarea' ) {
			$value = nl2br( $value );
		}

		if ( $field->type == 'select' || $field->type == 'checkbox' || $field->type == 'radio' ) {
			$field_opts = '';
			$field_opts = $wpdb->get_row( $wpdb->prepare( 'SELECT entry_value FROM ' . $entry_meta_table . " WHERE field_id='%d' AND entry_id='%d'", '-' . $field->id, $atts['entry_id'] ) ); //phpcs:ignore
			if ( ! empty( $field_opts ) ) {
				$field_opts = maybe_unserialize( $field_opts->entry_value );

				if ( $field->type == 'checkbox' ) {
					if ( $field_opts && count( $field_opts ) > 0 ) {
						$temp_value = '';
						foreach ( $field_opts as $new_field_opt ) {
							$temp_value .= $new_field_opt['label'] . ' (' . $new_field_opt['value'] . '), ';
						}
						$temp_value = trim( $temp_value );
						$value      = rtrim( $temp_value, ',' );
					}
				} else {
					global $wpdb,$MdlDb;
					$value = $field_opts['label'] . ' (' . $field_opts['value'] . ')';
				}
			}
		}

		return apply_filters( 'arflitedisplayvalue', $value, $field, $atts );
	}

	function arflite_get_post_or_entry_value( $entry, $field, $atts = array(), $is_for_mail = false ) {

		global $arfliterecordmeta;

		if ( ! is_object( $entry ) ) {

			global $arflite_db_record;

			$entry = $arflite_db_record->arflitegetOne( $entry );
		}

		$field->field_options = maybe_unserialize( $field->field_options );

		if ( $entry->attachment_id ) {

			if ( ! isset( $field->field_options['custom_field'] ) ) {
				$field->field_options['custom_field'] = '';
			}

			if ( ! isset( $field->field_options['post_field'] ) ) {
				$field->field_options['post_field'] = '';
			}

			$links = true;

			if ( isset( $atts['links'] ) ) {
				$links = $atts['links'];
			}

			$value = $arfliterecordmeta->arflite_get_entry_meta_by_field( $entry->id, $field->id, true, $is_for_mail );
		} else {

			$value = $arfliterecordmeta->arflite_get_entry_meta_by_field( $entry->id, $field->id, true, $is_for_mail );
		}

		return $value;
	}

	function arflite_get_date_field_format( $field, $form_css ) {

		$newarr         = $form_css;
		$date_format    = $form_css['date_format'];
		$wp_format_date = get_option( 'date_format' );

		foreach ( $field->field_options as $k => $value ) {
			$field->$k = $value;
		}

		if ( $wp_format_date == 'F j, Y' || $wp_format_date == 'm/d/Y' ) {
			if ( $field->arfnewdateformat == 'MMMM D, YYYY' ) {
				$defaultdate_format = 'F d, Y';
			} elseif ( $field->arfnewdateformat == 'MMM D, YYYY' ) {
				$defaultdate_format = 'M d, Y';
			} else {
				$defaultdate_format = 'm/d/Y';
			}
		} elseif ( $wp_format_date == 'd/m/Y' ) {
			if ( $field->arfnewdateformat == 'D MMMM, YYYY' ) {
				$defaultdate_format = 'd F, Y';
			} elseif ( $field->arfnewdateformat == 'D MMM, YYYY' ) {
				$defaultdate_format = 'd M, Y';
			} else {
				$defaultdate_format = 'd/m/Y';
			}
		} elseif ( $wp_format_date == 'Y/m/d' ) {
			if ( $field->arfnewdateformat == 'YYYY, MMMM D' ) {
				$defaultdate_format = 'Y, F d';
			} elseif ( $field->arfnewdateformat == 'YYYY, MMM D' ) {
				$defaultdate_format = 'Y, M d';
			} else {
				$defaultdate_format = 'Y/m/d';
			}
		} elseif ( $wp_format_date == 'd.F.y' || $wp_format_date == 'd.m.Y' || $wp_format_date == 'Y.m.d' || $wp_format_date == 'd. F Y' ) {

			if ( $field->arfnewdateformat == 'D.MM.YYYY' ) {
				$defaultdate_format = 'd.m.Y';
			} elseif ( $field->arfnewdateformat == 'D.MMMM.YY' ) {
				$defaultdate_format = 'd.F.y';
			} elseif ( $field->arfnewdateformat == 'YYYY.MM.D' ) {
				$defaultdate_format = 'Y.m.d';
			} elseif ( $field->arfnewdateformat == 'D. MMMM YYYY' ) {
				$defaultdate_format = 'd. F Y';
			}
		} else {
			if ( $field->arfnewdateformat == 'MMMM D, YYYY' ) {
				$defaultdate_format = 'F d, Y';
			} elseif ( $field->arfnewdateformat == 'MMM D, YYYY' ) {
				$defaultdate_format = 'M d, Y';
			} elseif ( $field->arfnewdateformat == 'YYYY/MM/DD' ) {
				$defaultdate_format = 'Y/m/d';
			} elseif ( $field->arfnewdateformat == 'MM/DD/YYYY' ) {
				$defaultdate_format = 'm/d/Y';
			} else {
				$defaultdate_format = 'd/m/Y';
			}
		}

		$show_year_month_calendar = 'true';

		if ( isset( $field->show_year_month_calendar ) && $field->show_year_month_calendar < 1 ) {
			$show_year_month_calendar = 'false';
		}

		$show_time_calendar = 'true';

		if ( ! isset( $field->show_time_calendar ) || $field->show_time_calendar < 1 ) {
			$show_time_calendar = 'false';
		}

		$arf_show_min_current_date = 'true';
		if ( ! isset( $field->arf_show_min_current_date ) || $field->arf_show_min_current_date < 1 ) {
			$arf_show_min_current_date = 'false';
		}

		if ( $arf_show_min_current_date == 'true' ) {
			$field->start_date = current_time( 'd/m/Y' );
		} else {
			$field->start_date = $field->start_date;
		}

		$arf_show_max_current_date = 'true';
		if ( ! isset( $field->arf_show_max_current_date ) || $field->arf_show_max_current_date < 1 ) {
			$arf_show_max_current_date = 'false';
		}

		if ( $arf_show_max_current_date == 'true' ) {
			$field->end_date = current_time( 'd/m/Y' );
		} else {
			$field->end_date = $field->end_date;
		}

		$date = new DateTime();

		if ( $field->end_date == '' ) {
			$field->end_date = '31/12/2050';
		}

		if ( $field->start_date == '' ) {
			$field->start_date = '01/01/1950';
		}

		$end_date_temp = explode( '/', $field->end_date );
		$date->setDate( $end_date_temp[2], $end_date_temp[1], $end_date_temp[0] );
		$date1           = new DateTime();
		$start_date_temp = explode( '/', $field->start_date );
		$date1->setDate( $start_date_temp[2], $start_date_temp[1], $start_date_temp[0] );

		if ( $newarr['date_format'] == 'MM/DD/YYYY' || $newarr['date_format'] == 'MMMM D, YYYY' || $newarr['date_format'] == 'MMM D, YYYY' ) {
			$start_date      = $date1->format( 'm/d/Y' );
			$end_date        = $date->format( 'm/d/Y' );
			$date_new_format = 'MM/DD/YYYY';
		} elseif ( $newarr['date_format'] == 'DD/MM/YYYY' || $newarr['date_format'] == 'D MMMM, YYYY' || $newarr['date_format'] == 'D MMM, YYYY' ) {
			$start_date      = $date1->format( 'd/m/Y' );
			$end_date        = $date->format( 'd/m/Y' );
			$date_new_format = 'DD-MM-YYYY';
		} elseif ( $newarr['date_format'] == 'YYYY/MM/DD' || $newarr['date_format'] == 'YYYY, MMMM D' || $newarr['date_format'] == 'YYYY, MMM D' ) {
			$start_date      = $date1->format( 'Y/m/d' );
			$end_date        = $date->format( 'Y/m/d' );
			$date_new_format = 'YYYY-MM-DD';
		} else {
			$start_date         = $date1->format( 'm/d/Y' );
			$end_date           = $date->format( 'm/d/Y' );
			$date_new_format    = 'MM/DD/YYYY';
			$field->date_format = 'MMM D, YYYY';
		}

		if ( $newarr['date_format'] == 'MM/DD/YYYY' ) {
			$date_new_format_main = 'MM/DD/YYYY';
		} elseif ( $newarr['date_format'] == 'DD/MM/YYYY' ) {
			$date_new_format_main = 'DD/MM/YYYY';
		} elseif ( $newarr['date_format'] == 'YYYY/MM/DD' ) {
			$date_new_format_main = 'YYYY/MM/DD';
		} elseif ( $newarr['date_format'] == 'MMM D, YYYY' ) {
			$date_new_format_main = 'MMM D, YYYY';
		} elseif ( $newarr['date_format'] == 'D.MM.YYYY' ) {
			$date_new_format_main = 'd.m.Y';
		} elseif ( $newarr['date_format'] == 'D.MMMM.YY' ) {
			$date_new_format_main = 'd.F.y';
		} elseif ( $newarr['date_format'] == 'YYYY.MM.D' ) {
			$date_new_format_main = 'Y.m.d';
		} elseif ( $field->arfnewdateformat == 'D. MMMM YYYY' ) {
			$date_new_format_main = 'd. F Y';
		} else {
			$date_new_format_main = 'MMMM D, YYYY';
		}

		if ( isset( $field->clock ) && $field->clock == '24' ) {
			$format = 'H:mm';
		} else {
			$format = 'h:mm A';
		}

		$date_formate = $newarr['date_format'];
		if ( $show_time_calendar == 'true' ) {
			$field->clock         = ( isset( $field->clock ) && $field->clock ) ? $field->clock : 'h:mm A';
			$date_new_format_main = $date_new_format_main . ' ' . $format;
			$date_formate        .= ' ' . $format;
		}

		$off_days = array();

		if ( $field->off_days != '' ) {
			$off_days = explode( ',', $field->off_days );
		}

		return json_encode(
			array(
				'date_new_format_main' => $date_new_format_main,
				'final_date_format'    => $date_formate,
				'date_new_format'      => $date_new_format,
				'start_date'           => $start_date,
				'end_date'             => $end_date,
				'time_format'          => $format,
				'off_days'             => $off_days,
			)
		);

	}

	function arflite_convert_date_to_en( $date, $from_lang ) {

		$json_file = ARFLITE_VIEWS_PATH . '/arflite_editor_data.json';
		$json_data = file_get_contents( $json_file );

		$json_data = json_decode( $json_data );

		$locale_data = $json_data->date_locale->$from_lang;
	}
}
