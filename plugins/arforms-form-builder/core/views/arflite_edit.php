<?php

global $wpdb, $arflite_memory_limit, $arflitememorylimit, $arfliteversion, $mailchimpkey, $mailchimpid, $infusionsoftkey, $aweberkey, $aweberid, $getresponsekey, $getresponseid, $gvokey, $gvoid, $ebizackey, $ebizacid, $style_settings, $arformsmain, $arfliteformhelper, $arfliterecordcontroller, $arflitemainhelper, $arfliteformcontroller, $arflitefieldhelper, $arflitemaincontroller, $arfliteadvanceerrcolor, $ARFLiteMdlDb, $arflitefield, $arfliteform, $arfliteajaxurl, $arflite_date_check_arr, $tbl_arf_forms, $tbl_arf_fields;

if ( isset( $arflite_memory_limit ) && isset( $arflitememorylimit ) && ( $arflite_memory_limit * 1024 * 1024 ) > $arflitememorylimit ) {
	@ini_set( 'memory_limit', $arflite_memory_limit . 'M' );
}

echo "<style type='text/css'>.qm-js#qm{position:relative;z-index:999;}.notice.arf-notice-update-warning{display:none !important;}</style>";

$arflite_id = ( isset( $_REQUEST['id'] ) && $_REQUEST['id'] != '' ) ? intval( $_REQUEST['id'] ) : 0;

if ( $action == 'duplicate' || $action == 'edit' ) {
	$record = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM `' . $tbl_arf_forms . '` WHERE id = %d', $arflite_id ) ); //phpcs:ignore
}

if ( isset( $record ) && $record->is_template && ( !empty(sanitize_text_field( $_REQUEST['arfaction'])) && sanitize_text_field( $_REQUEST['arfaction']) ) != 'duplicate' ) {
	wp_die( esc_html__( 'That template cannot be edited', 'arforms-form-builder' ) );
}
if ( ! isset( $record ) ) {
	$record = new stdClass();
}

$values           = array();
$values['fields'] = array();
$arf_all_fields   = array();
$record_arr       = (array) $record;

$db_max_field_id	  = 0;

if ( ! empty( $record_arr ) ) {
	$values['id']          = $form_id = $record->id;
	$values['form_key']    = $record->form_key;
	$values['description'] = $record->description;
	$values['name']        = $record->name;
	$values['form_name']   = $record->name;
	$all_fields            = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM `' . $tbl_arf_fields . '` WHERE form_id = %d ORDER BY ID ASC', $form_id ) ); //phpcs:ignore
	$db_max_field_id 		   = $wpdb->get_var( $wpdb->prepare( "SELECT MAX(id) as max_id FROM `{$tbl_arf_fields}` WHERE form_id = %d", $form_id ) ); //phpcs:ignore
}

$field_list            = array();
$include_fields        = array();
$exclude               = array( 'captcha' );
$all_hidden_fields     = array();
$responder_list_option = '';
if ( ! empty( $all_fields ) ) {
	foreach ( $all_fields as $key => $field_ ) {
		if ( ! in_array( $field_->id, $exclude ) && $field_->type == 'hidden' ) {
			$all_hidden_fields[] = $field_;
			$include_fields[]    = $field_->id;
			continue;
		}
		foreach ( $field_ as $k => $field_val ) {
			if ( $k == 'type' && ! in_array( $field_val, $exclude ) ) {
				$include_fields[] = $field_->id;
			}
			if ( $k == 'options' ) {
				$arf_all_fields[ $key ][ $k ] = json_decode( $field_val, true );
				if ( json_last_error() != JSON_ERROR_NONE ) {
					$arf_all_fields[ $key ][ $k ] = maybe_unserialize( $field_val );
				}
			} elseif ( $k == 'field_options' ) {
				$field_opts = json_decode( $field_val, true );
				if ( json_last_error() != JSON_ERROR_NONE ) {
					$field_opts = maybe_unserialize( $field_val );
				}
				if ( isset( $field_opts ) && is_array( $field_opts ) ) {

					foreach ( $field_opts as $ki => $val_ ) {
						$arf_all_fields[ $key ][ $ki ] = $val_;
					}
				}
			} else {
				$arf_all_fields[ $key ][ $k ] = $field_val;
			}
		}
	}
	foreach ( $all_fields as $key => $field_ ) {
		foreach ( $field_ as $k => $field_val ) {
			if ( in_array( $field_->id, $include_fields ) ) {
				if ( ! isset( $field_list[ $key ] ) ) {
					$field_list[ $key ] = new stdClass();
				}
				if ( $k == 'options' ) {
					$fOpt = json_decode( $field_val, true );
					if ( json_last_error() != JSON_ERROR_NONE ) {
						$fOpt = maybe_unserialize( $field_val );
					}
					$field_list[ $key ]->$k = $fOpt;
				} elseif ( $k == 'field_options' ) {
					$field_opts = json_decode( $field_val, true );
					if ( json_last_error() != JSON_ERROR_NONE ) {
						$field_opts = maybe_unserialize( $field_val );
					}
					$field_list[ $key ]->$k = $field_opts;
				} else {
					$field_list[ $key ]->$k = $field_val;
				}
			}
		}
	}
	$values['fields'] = $arf_all_fields;
}

$field_data = file_get_contents( ARFLITE_VIEWS_PATH . '/arflite_editor_data.json' );

$field_data_obj = json_decode( $field_data );
$form_opts      = ( isset( $record->options ) && $record->options != '' ) ? maybe_unserialize( $record->options ) : array();
$form_opts      = $arfliteformcontroller->arflite_html_entity_decode( $form_opts );

if ( is_array( $form_opts ) && ! empty( $form_opts ) ) {

	foreach ( $form_opts as $opt => $value ) {

		if ( in_array( $opt, array( 'email_to', 'reply_to', 'reply_to_name', 'admin_cc_email', 'admin_bcc_email' ) ) ) {

			$values['notification'][0][ $opt ] = $arflitemainhelper->arflite_get_param( 'notification[0][' . $opt . ']', $value );

		}

		$values[ $opt ] = $arflitemainhelper->arflite_get_param( $opt, $value );
	}
}


$form_defaults = $arfliteformhelper->arflite_get_default_opts();

foreach ( $form_defaults as $opt => $default ) {


	if ( ! isset( $values[ $opt ] ) || $values[ $opt ] == '' ) {
		if ( $opt == 'notification' ) {

			$values[ $opt ] = ( $_POST && isset( $_POST[ $opt ] ) ) ? array_map( 'sanitize_text_field', $_POST[ $opt ] ) : $default; //phpcs:ignore
			foreach ( $default as $o => $d ) {
				if ( $o == 'email_to' ) {
					$d = '';
				}
				$values[ $opt ][0][ $o ] = ( $_POST && isset( $_POST[ $opt ][0][ $o ] ) ) ? sanitize_text_field( $_POST[ $opt ][0][ $o ] ) : $d; //phpcs:ignore
				unset( $o );
				unset( $d );
			}
		} else {
			$values[ $opt ] = ( $_POST && isset( $_POST['options'][ $opt ] ) ) ? array_map( 'sanitize_text_field', $_POST['options'][ $opt ] ) : $default; //phpcs:ignore
		}
	}

	unset( $opt );
	unset( $defaut );
}

$arffield_selection = $arflitefieldhelper->arflite_field_selection();

$display = apply_filters( 'arflitedisplayfieldoptions', array( 'label_position' => true ) );



wp_enqueue_script( 'sack' );
$key = isset( $record->form_key ) ? $record->form_key : '';

$form_temp_key = '';
if ( ! isset( $record->form_key ) ) {
	global $arflitemainhelper;
	$possible_letters = '23456789bcdfghjkmnpqrstvwxyz';
	$random_dots      = 0;
	$random_lines     = 20;

	$form_temp_key = '';
	$i             = 0;
	while ( $i < 8 ) {
		$form_temp_key .= substr( $possible_letters, mt_rand( 0, strlen( $possible_letters ) - 1 ), 1 );
		$i++;
	}
}

$pre_link = ( isset( $record->form_key ) ) ? $arfliteformhelper->arflite_get_direct_link( $record->form_key ) : $arfliteformhelper->arflite_get_direct_link( $form_temp_key );

$wp_format_date = get_option( 'date_format' );


$data = '';

$data = isset( $record ) ? $record : '';

$data       = $arfliteformcontroller->arfliteObjtoArray( $data );
$aweber_arr = '';
$aweber_arr = isset( $data['form_css'] ) ? $data['form_css'] : '';

$values_nw = ( isset( $data['options'] ) && $data['options'] != '' ) ? maybe_unserialize( $data['options'] ) : array();


$arr = maybe_unserialize( $aweber_arr );

$newarr = array();
if ( isset( $arr ) && ! empty( $arr ) && is_array( $arr ) ) {
	foreach ( $arr as $k => $v ) {
		$newarr[ $k ] = $v;
	}
}
$arfinputstyle_template = ( isset( $_GET['templete_style'] ) && $_GET['templete_style'] != '' ) ? sanitize_text_field( $_GET['templete_style'] ) : ( ( isset( $newarr['arfinputstyle'] ) && $newarr['arfinputstyle'] != '' ) ? $newarr['arfinputstyle'] : 'material' );


$skinJsonFile = file_get_contents( ARFLITE_VIEWS_PATH . '/arflite_editor_data.json' );

$skinJson = json_decode( stripslashes( $skinJsonFile ) );

$skinJson = apply_filters( 'arflite_form_fields_outside', $skinJson, $arfinputstyle_template );

if ( empty( $newarr ) ) {
	$default_data_varible = 'default_data_' . $arfinputstyle_template;

	$custom_css_data = $arfliteformcontroller->arfliteObjtoArray( $skinJson->$default_data_varible );
	foreach ( $custom_css_data as $k => $v ) {
		$newarr[ $k ] = $v;
	}
}
$newarr['arfinputstyle'] = ( isset( $_GET['templete_style'] ) && $_GET['templete_style'] != '' ) ? sanitize_text_field( $_GET['templete_style'] ) : ( ( isset( $newarr['arfinputstyle'] ) && $newarr['arfinputstyle'] != '' ) ? $newarr['arfinputstyle'] : 'material' );


if ( isset( $_REQUEST['arf_rtl_switch_mode'] ) && sanitize_text_field( $_REQUEST['arf_rtl_switch_mode'] ) == 'yes' ) {
	$newarr['arfformtitlealign']     = 'right';
	$newarr['form_align']            = 'right';
	$newarr['arfdescalighsetting']   = 'right';
	$newarr['align']                 = 'right';
	$newarr['text_direction']        = '0';
	$newarr['arfsubmitalignsetting'] = 'right';
}

$values_nw['display_title_form'] = isset( $values_nw['display_title_form'] ) ? $values_nw['display_title_form'] : ( isset( $newarr['display_title_form'] ) ? $newarr['display_title_form'] : 1 );


$arfformtitlepaddingsetting_value = '';

if ( esc_attr( $newarr['arfmainformtitlepaddingsetting_1'] ) != '' ) {
	$arfformtitlepaddingsetting_value .= $newarr['arfmainformtitlepaddingsetting_1'] . 'px ';
} else {
	$arfformtitlepaddingsetting_value .= '0px ';
}
if ( esc_attr( $newarr['arfmainformtitlepaddingsetting_2'] ) != '' ) {
	$arfformtitlepaddingsetting_value .= $newarr['arfmainformtitlepaddingsetting_2'] . 'px ';
} else {
	$arfformtitlepaddingsetting_value .= '0px ';
}
if ( esc_attr( $newarr['arfmainformtitlepaddingsetting_3'] ) != '' ) {
	$arfformtitlepaddingsetting_value .= $newarr['arfmainformtitlepaddingsetting_3'] . 'px ';
} else {
	$arfformtitlepaddingsetting_value .= '0px ';
}
if ( esc_attr( $newarr['arfmainformtitlepaddingsetting_4'] ) != '' ) {
	$arfformtitlepaddingsetting_value .= $newarr['arfmainformtitlepaddingsetting_4'] . 'px';
} else {
	$arfformtitlepaddingsetting_value .= '0px';
}

$newarr['arfmainformtitlepaddingsetting'] = $arfformtitlepaddingsetting_value;


$active_skin = ( isset( $newarr['arfmainform_color_skin'] ) && $newarr['arfmainform_color_skin'] != '' ) ? $newarr['arfmainform_color_skin'] : 'cyan';

foreach ( $newarr as $k => $v ) {
	if ( strpos( $v, '#' ) === false ) {
		if ( ( preg_match( '/color/', $k ) || in_array( $k, array( 'arferrorbgsetting', 'arferrorbordersetting', 'arferrortextsetting' ) ) ) && ! in_array( $k, array( 'arfcheckradiocolor' ) ) ) {
			$newarr[ $k ] = '#' . $v;
		} else {
			$newarr[ $k ] = $v;
		}
	}
}




	$skinJson->skins->custom->form->title = ( isset( $newarr['arfmainformtitlecolorsetting'] ) && $newarr['arfmainformtitlecolorsetting'] != '' ) ? esc_attr( $newarr['arfmainformtitlecolorsetting'] ) : $skinJson->skins->cyan->form->title;

	$skinJson->skins->custom->form->description = ( isset( $newarr['arfmainformtitlecolorsetting'] ) && $newarr['arfmainformtitlecolorsetting'] != '' ) ? esc_attr( $newarr['arfmainformtitlecolorsetting'] ) : $skinJson->skins->cyan->form->description;

	$skinJson->skins->custom->form->border = ( isset( $newarr['arfmainfieldsetcolor'] ) && $newarr['arfmainfieldsetcolor'] != '' ) ? esc_attr( $newarr['arfmainfieldsetcolor'] ) : $skinJson->skins->cyan->form->border;

	$skinJson->skins->custom->form->background = ( isset( $newarr['arfmainformbgcolorsetting'] ) && $newarr['arfmainformbgcolorsetting'] != '' ) ? esc_attr( $newarr['arfmainformbgcolorsetting'] ) : $skinJson->skins->cyan->form->background;

	$skinJson->skins->custom->form->shadow = ( isset( $newarr['arfmainformbordershadowcolorsetting'] ) && $newarr['arfmainformbordershadowcolorsetting'] != '' ) ? esc_attr( $newarr['arfmainformbordershadowcolorsetting'] ) : $skinJson->skins->cyan->form->shadow;



	$skinJson->skins->custom->tooltip->background = ( isset( $newarr['arf_tooltip_bg_color'] ) && $newarr['arf_tooltip_bg_color'] != '' ) ? esc_attr( $newarr['arf_tooltip_bg_color'] ) : $skinJson->skins->cyan->tooltip->background;

	$skinJson->skins->custom->tooltip->text = ( isset( $newarr['arf_tooltip_font_color'] ) && $newarr['arf_tooltip_font_color'] != '' ) ? esc_attr( $newarr['arf_tooltip_font_color'] ) : $skinJson->skins->cyan->tooltip->text;


	$skinJson->skins->custom->label->text = ( isset( $newarr['label_color'] ) && $newarr['label_color'] != '' ) ? esc_attr( $newarr['label_color'] ) : $skinJson->skins->cyan->label->text;

	$skinJson->skins->custom->label->description = ( isset( $newarr['label_color'] ) && $newarr['label_color'] != '' ) ? esc_attr( $newarr['label_color'] ) : $skinJson->skins->cyan->label->text;


	$skinJson->skins->custom->input->main = ( isset( $newarr['arfmainbasecolor'] ) && $newarr['arfmainbasecolor'] != '' ) ? esc_attr( $newarr['arfmainbasecolor'] ) : $skinJson->skins->cyan->input->main;


	$skinJson->skins->custom->input->text = ( isset( $newarr['text_color'] ) && $newarr['text_color'] != '' ) ? esc_attr( $newarr['text_color'] ) : $skinJson->skins->cyan->input->text;

	$skinJson->skins->custom->input->background = ( isset( $newarr['bg_color'] ) && $newarr['bg_color'] != '' ) ? esc_attr( $newarr['bg_color'] ) : $skinJson->skins->cyan->input->background;

	$skinJson->skins->custom->input->background_active = ( isset( $newarr['arfbgactivecolorsetting'] ) && $newarr['arfbgactivecolorsetting'] != '' ) ? esc_attr( $newarr['arfbgactivecolorsetting'] ) : $skinJson->skins->cyan->input->background_active;

	$skinJson->skins->custom->input->background_error = ( isset( $newarr['arferrorbgcolorsetting'] ) && $newarr['arferrorbgcolorsetting'] != '' ) ? esc_attr( $newarr['arferrorbgcolorsetting'] ) : $skinJson->skins->cyan->input->background_error;

	$skinJson->skins->custom->input->border = ( isset( $newarr['border_color'] ) && $newarr['border_color'] != '' ) ? esc_attr( $newarr['border_color'] ) : $skinJson->skins->cyan->input->border;

	$skinJson->skins->custom->input->border_active = ( isset( $newarr['arfborderactivecolorsetting'] ) && $newarr['arfborderactivecolorsetting'] != '' ) ? esc_attr( $newarr['arfborderactivecolorsetting'] ) : $skinJson->skins->cyan->input->border_active;

	$skinJson->skins->custom->input->border_error = ( isset( $newarr['arferrorbordercolorsetting'] ) && $newarr['arferrorbordercolorsetting'] != '' ) ? esc_attr( $newarr['arferrorbordercolorsetting'] ) : $skinJson->skins->cyan->input->border_error;


	$skinJson->skins->custom->input->prefix_suffix_background = ( isset( $newarr['prefix_suffix_bg_color'] ) && $newarr['prefix_suffix_bg_color'] != '' ) ? esc_attr( $newarr['prefix_suffix_bg_color'] ) : $skinJson->skins->cyan->input->prefix_suffix_background;

	$skinJson->skins->custom->input->prefix_suffix_icon_color = ( isset( $newarr['prefix_suffix_icon_color'] ) && $newarr['prefix_suffix_icon_color'] != '' ) ? esc_attr( $newarr['prefix_suffix_icon_color'] ) : $skinJson->skins->cyan->input->prefix_suffix_icon_color;

	$skinJson->skins->custom->input->checkbox_icon_color = ( isset( $newarr['checked_checkbox_icon_color'] ) && $newarr['checked_checkbox_icon_color'] != '' ) ? esc_attr( $newarr['checked_checkbox_icon_color'] ) : $skinJson->skins->cyan->input->checkbox_icon_color;

	$skinJson->skins->custom->input->radio_icon_color = ( isset( $newarr['checked_radio_icon_color'] ) && $newarr['checked_radio_icon_color'] != '' ) ? esc_attr( $newarr['checked_radio_icon_color'] ) : $skinJson->skins->cyan->input->radio_icon_color;

	$skinJson->skins->custom->input->slider_selection_color = ( isset( $newarr['arfsliderselectioncolor'] ) && $newarr['arfsliderselectioncolor'] != '' ) ? esc_attr( $newarr['arfsliderselectioncolor'] ) : $skinJson->skins->cyan->input->slider_selection_color;

	$skinJson->skins->custom->input->slider_track_color = ( isset( $newarr['arfslidertrackcolor'] ) && $newarr['arfslidertrackcolor'] != '' ) ? esc_attr( $newarr['arfslidertrackcolor'] ) : $skinJson->skins->cyan->input->slider_track_color;


	$skinJson->skins->custom->submit->text = ( isset( $newarr['arfsubmittextcolorsetting'] ) && $newarr['arfsubmittextcolorsetting'] != '' ) ? esc_attr( $newarr['arfsubmittextcolorsetting'] ) : $skinJson->skins->cyan->submit->text;

	$skinJson->skins->custom->submit->background = ( isset( $newarr['submit_bg_color'] ) && $newarr['submit_bg_color'] != '' ) ? esc_attr( $newarr['submit_bg_color'] ) : $skinJson->skins->cyan->submit->background;

	$skinJson->skins->custom->submit->background_hover = ( isset( $newarr['arfsubmitbuttonbgcolorhoversetting'] ) && $newarr['arfsubmitbuttonbgcolorhoversetting'] != '' ) ? esc_attr( $newarr['arfsubmitbuttonbgcolorhoversetting'] ) : $skinJson->skins->cyan->submit->background_hover;

	$skinJson->skins->custom->submit->border = isset( $newarr['arfsubmitbordercolorsetting'] ) ? esc_attr( $newarr['arfsubmitbordercolorsetting'] ) : $skinJson->skins->cyan->submit->border;

	$skinJson->skins->custom->submit->shadow = ( isset( $newarr['arfsubmitshadowcolorsetting'] ) && $newarr['arfsubmitshadowcolorsetting'] != '' ) ? esc_attr( $newarr['arfsubmitshadowcolorsetting'] ) : $skinJson->skins->cyan->submit->shadow;


	$skinJson->skins->custom->success_msg->background = ( isset( $newarr['arfsucessbgcolorsetting'] ) && $newarr['arfsucessbgcolorsetting'] != '' ) ? esc_attr( $newarr['arfsucessbgcolorsetting'] ) : $skinJson->skins->cyan->success_msg->background;

	$skinJson->skins->custom->success_msg->border = ( isset( $newarr['arfsucessbordercolorsetting'] ) && $newarr['arfsucessbordercolorsetting'] != '' ) ? esc_attr( $newarr['arfsucessbordercolorsetting'] ) : $skinJson->skins->cyan->success_msg->border;

	$skinJson->skins->custom->success_msg->text = ( isset( $newarr['arfsucesstextcolorsetting'] ) && $newarr['arfsucesstextcolorsetting'] != '' ) ? esc_attr( $newarr['arfsucesstextcolorsetting'] ) : $skinJson->skins->cyan->success_msg->text;


	$skinJson->skins->custom->success_msg_material->background = ( isset( $newarr['arfsucessbgcolorsetting'] ) && $newarr['arfsucessbgcolorsetting'] != '' ) ? esc_attr( $newarr['arfsucessbgcolorsetting'] ) : $skinJson->skins->cyan->success_msg_material->background;

	$skinJson->skins->custom->success_msg_material->border = ( isset( $newarr['arfsucessbordercolorsetting'] ) && $newarr['arfsucessbordercolorsetting'] != '' ) ? esc_attr( $newarr['arfsucessbordercolorsetting'] ) : $skinJson->skins->cyan->success_msg_material->border;

	$skinJson->skins->custom->success_msg_material->text = ( isset( $newarr['arfsucesstextcolorsetting'] ) && $newarr['arfsucesstextcolorsetting'] != '' ) ? esc_attr( $newarr['arfsucesstextcolorsetting'] ) : $skinJson->skins->cyan->success_msg_material->text;


	$skinJson->skins->custom->error_msg->background = ( isset( $newarr['arfformerrorbgcolorsettings'] ) && $newarr['arfformerrorbgcolorsettings'] != '' ) ? esc_attr( $newarr['arfformerrorbgcolorsettings'] ) : $skinJson->skins->custom->error_msg->background;

	$skinJson->skins->custom->error_msg->text = ( isset( $newarr['arfformerrortextcolorsettings'] ) && $newarr['arfformerrortextcolorsettings'] != '' ) ? esc_attr( $newarr['arfformerrortextcolorsettings'] ) : $skinJson->skins->custom->error_msg->text;

	$skinJson->skins->custom->error_msg->border = ( isset( $newarr['arfformerrorbordercolorsettings'] ) && $newarr['arfformerrorbordercolorsettings'] != '' ) ? esc_attr( $newarr['arfformerrorbordercolorsettings'] ) : $skinJson->skins->custom->error_msg->border;


	$skinJson->skins->custom->error_msg_material->background = ( isset( $newarr['arfformerrorbgcolorsettings'] ) && $newarr['arfformerrorbgcolorsettings'] != '' ) ? esc_attr( $newarr['arfformerrorbgcolorsettings'] ) : $skinJson->skins->custom->error_msg_material->background;

	$skinJson->skins->custom->error_msg_material->text = ( isset( $newarr['arfformerrortextcolorsettings'] ) && $newarr['arfformerrortextcolorsettings'] != '' ) ? esc_attr( $newarr['arfformerrortextcolorsettings'] ) : $skinJson->skins->custom->error_msg_material->text;

	$skinJson->skins->custom->error_msg_material->border = ( isset( $newarr['arfformerrorbordercolorsettings'] ) && $newarr['arfformerrorbordercolorsettings'] != '' ) ? esc_attr( $newarr['arfformerrorbordercolorsettings'] ) : $skinJson->skins->custom->error_msg_material->border;



	$skinJson->skins->custom->validation_msg->background = ( isset( $newarr['arfvalidationbgcolorsetting'] ) && $newarr['arfvalidationbgcolorsetting'] != '' ) ? esc_attr( $newarr['arfvalidationbgcolorsetting'] ) : ( ( $active_skin != 'custom' ) ? $skinJson->skins->cyan->validation_msg->background : '' );

	$skinJson->skins->custom->validation_msg->text = ( isset( $newarr['arfvalidationtextcolorsetting'] ) && $newarr['arfvalidationtextcolorsetting'] != '' ) ? esc_attr( $newarr['arfvalidationtextcolorsetting'] ) : ( ( $active_skin != 'custom' ) ? $skinJson->skins->cyan->validation_msg->text : '' );


	$skinJson->skins->custom->datepicker->background = ( isset( $newarr['arfdatepickerbgcolorsetting'] ) && $newarr['arfdatepickerbgcolorsetting'] != '' ) ? esc_attr( $newarr['arfdatepickerbgcolorsetting'] ) : $skinJson->skins->cyan->datepicker->background;

	$skinJson->skins->custom->datepicker->text = ( isset( $newarr['arfdatepickertextcolorsetting'] ) && $newarr['arfdatepickertextcolorsetting'] != '' ) ? esc_attr( $newarr['arfdatepickertextcolorsetting'] ) : $skinJson->skins->cyan->datepicker->text;


	$skinJson->skins->custom->uploadbutton->text = ( isset( $newarr['arfuploadbtntxtcolorsetting'] ) && $newarr['arfuploadbtntxtcolorsetting'] != '' ) ? esc_attr( $newarr['arfuploadbtntxtcolorsetting'] ) : $skinJson->skins->cyan->uploadbutton->text;

	$skinJson->skins->custom->uploadbutton->background = ( isset( $newarr['arfuploadbtnbgcolorsetting'] ) && $newarr['arfuploadbtnbgcolorsetting'] != '' ) ? esc_attr( $newarr['arfuploadbtnbgcolorsetting'] ) : $skinJson->skins->cyan->uploadbutton->background;

$arfhttp_user_agent = isset( $_SERVER['HTTP_USER_AGENT'] ) ? sanitize_text_field($_SERVER['HTTP_USER_AGENT']) : '';
$browser_info = $arfliterecordcontroller->arflitegetBrowser( $arfhttp_user_agent );
$translated_text_filedrag = "
    var __ARF_UPLOAD_CSV_MSG  = '" . __( 'Please upload csv files only', 'arforms-form-builder' ) . "';
    var __ARF_UPLOAD_IMG_MSG  = '" . __( 'Please upload image files only', 'arforms-form-builder' ) . "';
";
wp_register_script( 'filedrag', ARFLITEURL . '/js/filedrag/filedrag.js', array(), $arfliteversion );
wp_add_inline_script( 'filedrag', $translated_text_filedrag );

$arflitemainhelper->arflite_load_scripts( array( 'filedrag' ) );
global $arfliteformcontroller, $arflite_get_googlefonts_data;
$arflite_get_googlefonts_data = $arfliteformcontroller->get_arflite_google_fonts();
$google_font_array            = array_chunk( $arflite_get_googlefonts_data, 150 );

foreach ( $google_font_array as $key => $font_values ) {
	$google_fonts_string = implode( '|', $font_values );
	$google_font_url_one = '';
	if ( is_ssl() ) {
		$google_font_url_one = 'https://fonts.googleapis.com/css?family=' . $google_fonts_string;
	} else {
		$google_font_url_one = 'http://fonts.googleapis.com/css?family=' . $google_fonts_string;
	}

	$google_font_url_one = esc_url_raw( $google_font_url_one );

	wp_enqueue_style( 'arflite-editor-google-font' . $key, $google_font_url_one, array(), $arfliteversion );
}

function arflite_google_font_listing() {
	global $arflite_get_googlefonts_data;

	if ( count( $arflite_get_googlefonts_data ) > 0 ) {
		foreach ( $arflite_get_googlefonts_data as $goglefontsfamily ) {
			$arflite_google_fonts[ $goglefontsfamily ] = $goglefontsfamily;
		}
	}
	return $arflite_google_fonts;
}

$display   = apply_filters( 'arflitedisplayfieldoptions', array( 'label_position' => true ) );
$arfaction = !empty(sanitize_text_field( $_REQUEST['arfaction'] ) ) ? sanitize_text_field( $_REQUEST['arfaction'] ) : '';

if ( $arfaction == 'duplicate' ) {
	if ( $arflite_id < 100 ) {
		$template_id = 1;
	} else {
		$template_id = 0;
	}
}

$arf_template_id = isset( $template_id ) ? $template_id : 0;

$upload_main_url = ARFLITE_UPLOAD_URL . '/maincss';

	$arf_form_style = "<style type='text/css' class='added_new_style_css'>";
if ( $arf_template_id == 1 ) {
	$define_template = $arflite_id;
} else {
	$define_template = $arflite_id;
}

if ( $arfaction != 'edit' ) {
	$arflite_id = rand();
}


	$form_id    = $arflite_id;
	$saving     = true;
	$use_saved  = true;
	$new_values = array();

foreach ( $newarr as $key => $value ) {
	$new_values[ $key ] = $value;
}

	$arfssl = false;
if ( is_ssl() ) {
	$arfssl = true;
}

	$is_form_save = true;

	$common_css_filename = ARFLITE_FORMPATH . '/core/arflite_css_create_common.php';
	$css_rtl_filename    = ARFLITE_FORMPATH . '/core/arflite_css_create_rtl.php';
if ( $new_values['arfinputstyle'] == 'standard' || $new_values['arfinputstyle'] == 'rounded' ) {
	if ( $arfaction == 'new' || $arfaction == 'edit' ) {
		$filename = ARFLITE_FORMPATH . '/core/arflite_css_create_main.php';
		ob_start();
		include $filename;
		include $common_css_filename;
		if ( is_rtl() ) {
			include $css_rtl_filename;
		}
		$css             = ob_get_contents();
		$css             = str_replace( '##', '#', $css );
		$arf_form_style .= $css;
		ob_end_clean();
	} elseif ( $arfaction == 'duplicate' ) {

		if ( $record->is_template ) {
			$form_css    = maybe_unserialize( $record->form_css );
			$input_style = isset( $form_css['arfinputstyle'] ) ? $form_css['arfinputstyle'] : 'material';
			if ( $input_style == 'material' ) {
				if ( $new_values['arfinputstyle'] == 'rounded' ) {
					$new_values['border_radius'] = 50;
				} else {
					$new_values['border_radius'] = 4;
				}
				$new_values['arffieldinnermarginssetting_1'] = 7;
				$new_values['arffieldinnermarginssetting_2'] = 10;
				$new_values['arfcheckradiostyle']            = 'default';
				$new_values['arfsubmitborderwidthsetting']   = '0';
				$new_values['arfsubmitbuttonstyle']          = 'flat';
				$new_values['arfmainfield_opacity']          = 0;
				$new_values['arffieldinnermarginssetting']   = '7px 10px 7px 10px';
			}
		}

		$filename = ARFLITE_FORMPATH . '/core/arflite_css_create_main.php';

		ob_start();
		include $filename;
		include $common_css_filename;
		if ( is_rtl() ) {
			include $css_rtl_filename;
		}
		$css             = ob_get_contents();
		$css             = str_replace( '##', '#', $css );
		$arf_form_style .= $css;
		ob_end_clean();
	}
} elseif ( $new_values['arfinputstyle'] == 'material' ) {

	if ( $arfaction == 'duplicate' && isset( $record ) && isset( $record->is_template ) && $record->is_template ) {
		$form_css    = maybe_unserialize( $record->form_css );
		$input_style = isset( $form_css['arfinputstyle'] ) ? $form_css['arfinputstyle'] : 'material';
		if ( $input_style != 'material' ) {
			$new_values['arffieldinnermarginssetting_1'] = 0;
			$new_values['arffieldinnermarginssetting_2'] = 0;
			$new_values['border_radius']                 = 0;
			$new_values['arfcheckradiostyle']            = 'material';
			$new_values['arfsubmitborderwidthsetting']   = '2';
			$new_values['arfsubmitbuttonstyle']          = 'border';
			$new_values['arfmainfield_opacity']          = 1;
			$new_values['arfsubmitbuttonxoffsetsetting'] = '1';
			$new_values['arfsubmitbuttonyoffsetsetting'] = '2';
			$new_values['arfsubmitbuttonblursetting']    = '3';
			$new_values['arfsubmitbuttonshadowsetting']  = '0';
			$new_values['arffieldinnermarginssetting']   = '0px 0px 0px 0px';
		}
	}

	$filename = ARFLITE_FORMPATH . '/core/arflite_css_create_materialize.php';

	ob_start();

	include $filename;
	include $common_css_filename;
	if ( is_rtl() ) {
		include $css_rtl_filename;
	}
	$css = ob_get_contents();

	$css = str_replace( '##', '#', $css );

	$arf_form_style .= $css;

	ob_end_clean();
}
	$arf_form_style .= '</style>';
	echo wp_kses(
		$arf_form_style,
		array(
			'style' => array(
				'type'  => array(),
				'class' => array(),
			),
		)
	);


	$form_options = isset( $record->options ) ? maybe_unserialize( $record->options ) : array();

	$arf_field_order = ( isset( $form_options['arf_field_order'] ) && $form_options['arf_field_order'] != '' ) ? $form_options['arf_field_order'] : '[]';

	$arf_inner_field_order = ( isset( $form_options['arf_inner_field_order'] ) && '' != $form_options['arf_inner_field_order'] ) ? $form_options['arf_inner_field_order'] : '[]';

	$arf_field_resize_width = ( isset( $form_options['arf_field_resize_width'] ) && $form_options['arf_field_resize_width'] != '' ) ? $form_options['arf_field_resize_width'] : '';

	$arf_inner_field_resize_width = ( isset( $form_options['arf_inner_field_resize_width'] ) && $form_options['arf_inner_field_resize_width'] != '' ) ? $form_options['arf_inner_field_resize_width'] : '';

	if ( $arf_field_order != '' ) {
		$arf_field_order = json_decode( $arf_field_order, true );
		$arf_field_order = json_encode( array_filter( $arf_field_order ) );
	}

	if ( '' != $arf_inner_field_order ) {
		$arf_inner_field_order = json_decode( $arf_inner_field_order, true );
		$arf_inner_field_order = json_encode( array_filter( $arf_inner_field_order ) );
	}

	if ( $arf_field_resize_width != '' ) {
		$arf_field_resize_width = json_decode( $arf_field_resize_width, true );
		$arf_field_resize_width = json_encode( array_filter( $arf_field_resize_width ) );
	}

	if ( '' != $arf_inner_field_resize_width ) {
		$arf_inner_field_resize_width = json_decode( $arf_inner_field_resize_width, true );
		$arf_inner_field_resize_width = json_encode( array_filter( $arf_inner_field_resize_width ) );
	}

	if ( is_ssl() ) {
		$upload_css_url = str_replace( 'http://', 'https://', ARFLITE_UPLOAD_URL );
	} else {
		$upload_css_url = ARFLITE_UPLOAD_URL;
	}

	$upload_css_url = esc_url_raw( $upload_css_url );

	$form_opts['arf_form_other_css'] = ( isset( $form_opts['arf_form_other_css'] ) && $form_opts['arf_form_other_css'] != '' ) ? $arfliteformcontroller->arflitebr2nl( $form_opts['arf_form_other_css'] ) : '';

	?>
<input type="hidden" id="arflite_upload_css_url" value="<?php echo esc_attr( $upload_css_url ) . '/'; ?>" />
<input type="hidden" id="arflite_browser_info" value='<?php echo esc_attr( json_encode( $browser_info ) ); ?>' />
<input type="hidden" id="arflite_skin_json" value="<?php echo esc_attr( base64_encode( json_encode( $skinJson ) ) ); ?>" />
<input type="hidden" id="arflite_max_field_id" value="<?php echo esc_attr( $db_max_field_id ); ?>" />

<style type="text/css" id="arf_form_other_css_<?php echo esc_attr( $arflite_id ); ?>">
	<?php
	if ( isset( $form_opts['arf_form_other_css'] ) ) {
		if ( $arfaction == 'new' || $arfaction == 'duplicate' ) {
			echo $temp_arf_form_other_css    = preg_replace( '/(-|_)(' . $define_template . ')/', '${1}' . esc_attr($arflite_id), esc_attr($form_opts['arf_form_other_css']), -1 ); //phpcs:ignore
			$form_opts['arf_form_other_css'] = $temp_arf_form_other_css;
		} else {
			echo esc_html( $form_opts['arf_form_other_css'] );
		}
	}
	?>
</style>
<?php do_action( 'arflite_display_additional_css_in_editor' ); ?>
<input type="hidden" id="arf_db_json_object" value='<?php echo esc_attr( json_encode( $skinJson->skins->custom ) ); ?>' />
<style type="text/css" id='arf_form_<?php echo esc_attr( $arflite_id ); ?>'>
<?php
$custom_css_array_form = array(
	'arf_form_outer_wrapper'   => '.arf_form_outer_wrapper|.arfmodal',
	'arf_form_inner_wrapper'   => '.arf_fieldset|.arfmodal',
	'arf_form_title'           => '.formtitle_style',
	'arf_form_description'     => 'div.formdescription_style',
	'arf_form_element_wrapper' => '.arfformfield',
	'arf_form_element_label'   => 'label.arf_main_label',
	'arf_form_elements'        => '.controls',
	'arf_submit_outer_wrapper' => 'div.arfsubmitbutton',
	'arf_form_submit_button'   => '.arfsubmitbutton button.arf_submit_btn',
	'arf_form_success_message' => '#arf_message_success',
	'arf_form_error_message'   => '.control-group.arf_error .help-block|.control-group.arf_warning .help-block|.control-group.arf_warning .help-inline|.control-group.arf_warning .control-label|.control-group.arf_error .popover|.control-group.arf_warning .popover',
);

foreach ( $custom_css_array_form as $custom_css_block_form => $custom_css_classes_form ) {

	if ( isset( $form->options[ $custom_css_block_form ] ) and $form->options[ $custom_css_block_form ] != '' ) {

		$form->options[ $custom_css_block_form ] = $arfliteformcontroller->arflitebr2nl( $form->options[ $custom_css_block_form ] );

		if ( $custom_css_block_form == 'arf_form_outer_wrapper' ) {
			$arf_form_outer_wrapper_array = explode( '|', $custom_css_classes_form );

			foreach ( $arf_form_outer_wrapper_array as $arf_form_outer_wrapper1 ) {
				if ( $arf_form_outer_wrapper1 == '.arf_form_outer_wrapper' ) {
					echo '.arflite_main_div_' . esc_attr($form->id) . '.arf_form_outer_wrapper { ' . esc_attr($form->options[ $custom_css_block_form ]) . ' } ';
				}
				if ( $arf_form_outer_wrapper1 == '.arfmodal' ) {
					echo '#popup-form-' . esc_attr($form->id) . '.arfmodal{ ' . esc_attr($form->options[ $custom_css_block_form ]) . ' } ';
				}
			}
		} elseif ( $custom_css_block_form == 'arf_form_inner_wrapper' ) {
			$arf_form_inner_wrapper_array = explode( '|', $custom_css_classes_form );
			foreach ( $arf_form_inner_wrapper_array as $arf_form_inner_wrapper1 ) {
				if ( $arf_form_inner_wrapper1 == '.arf_fieldset' ) {
					echo '.arflite_main_div_' . esc_attr($form->id) . ' ' . esc_attr($arf_form_inner_wrapper1) . ' { ' . esc_attr($form->options[ $custom_css_block_form ]) . ' } ';
				}
				if ( $arf_form_inner_wrapper1 == '.arfmodal' ) {
					echo '.arfmodal .arfmodal-body .arflite_main_div_' . esc_attr($form->id) . ' .arf_fieldset { ' . esc_attr($form->options[ $custom_css_block_form ]) . ' } ';
				}
			}
		} elseif ( $custom_css_block_form == 'arf_form_error_message' ) {
			$arf_form_error_message_array = explode( '|', $custom_css_classes_form );

			foreach ( $arf_form_error_message_array as $arf_form_error_message1 ) {
				echo '.arflite_main_div_' . esc_attr($form->id) . ' ' . esc_attr($arf_form_error_message1) . ' { ' . esc_attr($form->options[ $custom_css_block_form ]) . ' } ';
			}
		} else {
			echo '.arflite_main_div_' . esc_attr($form->id) . ' ' . esc_attr($custom_css_classes_form) . ' { ' . esc_attr($form->options[ $custom_css_block_form ]) . ' } ';
		}
	}
}

$arfdefine_date_formate_array = $arfliteformcontroller->arflitereturndateformate();
$template_style = ( !empty( $_GET['templete_style'] ) && sanitize_text_field($_GET['templete_style']) != '' ) ? esc_attr( sanitize_text_field($_GET['templete_style']) ) : '';
$arfliteaction = !empty( sanitize_text_field($_GET['arfaction'] )) ? esc_attr( sanitize_text_field($_GET['arfaction']) ) : '';

?>
</style>

<div class="arf_editor_wrapper">
	<div id="arfsaveformloader"><?php echo ARFLITE_LOADER_ICON; //phpcs:ignore ?></div>
	<input type="hidden" id="arf_control_labels" value="" data-field-id="" />
	<input type="hidden" id="arf_reset_styling" value="false" />
	<input type="hidden" name="arflite_validation_nonce" id="arflite_validation_nonce" value="<?php echo esc_attr( wp_create_nonce( 'arflite_wp_nonce' ) ); ?>" />
	<input type="hidden" id="arflite_preset_nonce" value="<?php echo esc_attr( wp_create_nonce( 'arflite_wp_preset_nonce' ) ); ?>" />
	<input type="hidden" id="arf_copying_fields" value="false" />
	<input type="hidden" id="arf_single_column_field_ids" value="" />
	<div id="arf_hidden_fields_html" style="display:none !important;height:0px !important;width:0px !important;visibility: hidden !important;"></div>
	<input type="hidden" name="arfwpversion" id="arfwpversion" value="<?php echo esc_attr( $GLOBALS['wp_version'] ); ?>" />
	<input type="hidden" name="arfchange_field" id="arfchange_field" />
	<input type="hidden" name="arfchange_inner_field" id="arfchange_inner_field" />
	<input type="hidden" name="arfdateformate" id="arfdateformate" data-wp-formate = "<?php echo esc_attr( $arfdefine_date_formate_array['arfwp_dateformate'] ); ?>"  data-js-formate = "<?php echo esc_attr( $arfdefine_date_formate_array['arfjs_dateformate'] ); ?>" />
	<input type="hidden" name="arfgettemplate_style" id="arfgettemplate_style" value="<?php echo esc_attr($template_style); ?>" />

	<form action="" method="POST" id="arflite_current_form_export" name="arflite_current_form_export">
		<input type="hidden" name="s_action" value="arf_opt_export_form" />
		<input type="hidden" name="arf_opt_export" value="" />
		<input type="hidden" name="export_button" value="export_button" />
		<input type="hidden" name="is_single_form" value="1" />
		<input type="hidden" name="_wpnonce_arforms" value="<?php echo wp_create_nonce( 'arforms_wp_nonce' ); ?>" />
		<input type="hidden" name="frm_add_form_id_name" id="frm_add_form_id_name" value="<?php echo esc_attr( $form_id ); ?>" />
	</form>

	<form name="arf_form" id="frm_main_form" method="post" onSubmit='return arflitemainformedit(0);'>
		<input type="hidden" name="arfmainformurl" data-id="arfmainformurl" value="<?php echo esc_url( ARFLITEURL ); ?>" />
		<input type="hidden" name="arfmainformversion" id="arfmainformversion" value="<?php echo esc_attr( $arfliteversion ); ?>" />
		<input type="hidden" name="arfuploadurl" id="arfuploadurl" value="<?php echo esc_attr( $upload_css_url ) . '/'; ?>"/>
		<input type="hidden" name="arfaction" id="arfaction" value="<?php echo esc_attr($arfliteaction); ?>" /> 
		<input type="hidden" name="arfajaxurl" id="arfajaxurl" class="arf_ajax_url" value="<?php echo esc_attr( $arfliteajaxurl ); ?>" />
		<input type="hidden" name="arffiledragurl" data-id="arffiledragurl" value="<?php echo esc_url( ARFLITE_FILEDRAG_SCRIPT_URL ); ?>" />

		<input type="hidden" name="prev_arfaction" value="<?php !empty( $_GET['arfsction']) ? sanitize_text_field( $_GET['arfaction']  ) : ''; ?>" />

		<input type="hidden" name="frm_autoresponder_no" id="frm_autoresponder_no" value="" />

		<input type="hidden" name="id" id="id" value="<?php echo esc_attr( $arflite_id ); ?>" />
		<input type="hidden" name="define_template" id="define_template" value="<?php echo isset( $define_template ) ? esc_attr( $define_template ) : 0; ?>" />
		<input type="hidden" id="arf_isformchange" name="arf_isformchange" data-value="1" value="1" />

		<input type ="hidden" id="changed_style_attr" value="" />

		<input type ="hidden" id="default_style_attr" value='<?php echo esc_attr( json_encode( $newarr ) ); ?>' />

		<?php $arflite_chk_edit_action = isset($_GET['arfaction']) && ( sanitize_text_field( $_GET['arfaction'] ) == 'edit' ); ?>

		<input type="hidden" id="arf_field_order" name="arf_field_order" value='<?php echo esc_attr( $arf_field_order ); ?>' data-db-field-order='<?php echo esc_attr($arflite_chk_edit_action) ? esc_attr( $arf_field_order ) : ''; ?>' />

		<input type="hidden" id="arf_inner_field_order" name="arf_inner_field_order" value='<?php echo esc_attr($arflite_chk_edit_action) ? esc_attr( $arf_inner_field_order ) : ''; ?>' />
		
		<input type="hidden" id="arf_field_resize_width" name="arf_field_resize_width" value='<?php echo esc_attr( $arf_field_resize_width ); ?>' data-db-field-resize='<?php echo esc_attr($arflite_chk_edit_action) ? esc_attr( $arf_field_resize_width ) : ''; ?>' />

		<input type="hidden" id="arf_inner_field_resize_width" name="arf_inner_field_resize_width" value='<?php echo esc_attr( $arf_inner_field_resize_width ); ?>' data-db-field-resize='<?php echo esc_attr( $arflite_chk_edit_action ) ? esc_attr( $arf_inner_field_resize_width ) : ''; ?>' />

		<input type="hidden" id="arf_input_radius" name="arf_input_radius" value='<?php echo esc_attr( $newarr['border_radius'] ); ?>' />
		<?php 
			$arflitehttp_user_agent = isset( $_SERVER['HTTP_USER_AGENT'] ) ? sanitize_text_field($_SERVER['HTTP_USER_AGENT']) : '';
			$browser_info = $arfliterecordcontroller->arflitegetBrowser( $arflitehttp_user_agent ); 
		?>
		<input type="hidden" data-id="arf_browser_name" value="<?php echo esc_attr( $browser_info['name'] ); ?>" />
		<div class="arf_editor_header_belt">
			<div class="arf_editor_header_inner_belt">
				<div class="arf_editor_top_menu_wrapper">
					<ul class="arf_editor_top_menu">
						<li class="arf_editor_top_menu_item" id="mail_notification">
							<span class="arf_editor_top_menu_item_icon">
								<svg viewBox="0 -4 32 32">
								<g id="email"><path fill="#ffffff" fill-rule="evenodd" clip-rule="evenodd" d="M27.321,22.868H3.661c-1.199,0-2.172-0.973-2.172-2.172V3.053c0-1.2,0.973-2.203,2.172-2.203h23.66c1.199,0,2.171,1.003,2.171,2.203v17.643C29.492,21.895,28.52,22.868,27.321,22.868zM27.501,20.894V3.69l-12.28,9.268v0.008l-0.005-0.004l-0.005,0.004v-0.008L3.484,3.676v17.218H27.501z M24.994,2.844H5.95l9.267,7.377L24.994,2.844z"/></g>
								</svg>
							</span>
							<label class="arf_editor_top_menu_label">
								<?php echo esc_html__( 'Email Notifications', 'arforms-form-builder' ); ?>
							</label>
						</li>
						<li class="arf_editor_top_menu_item" id="conditional_law">
							<span class="arf_editor_top_menu_item_icon">
								<svg viewBox="0 -5 32 32">
								<g id="conditional_law"><path fill="#ffffff" fill-rule="evenodd" clip-rule="evenodd" d="M1.489,22.819V20.85H23.5v1.969H1.489z M10.213,13.263l2.246,2.246l5.246-5.246l1.392,1.392l-5.246,5.246l0.013,0.013l-1.392,1.392l-0.013-0.013l-0.013,0.013l-1.392-1.392l0.013-0.013l-2.246-2.246L10.213,13.263z M1.489,5.85H23.5v1.969H1.489V5.85z M1.489,0.85H23.5v1.969H1.489V0.85z"/></g>
								</svg>
							</span>
							<label class="arf_editor_top_menu_label">
								<?php echo esc_html__( 'Conditional Rule', 'arforms-form-builder' ); ?>
							</label>
						</li>
						<li class="arf_editor_top_menu_item" id="submit_action">
							<span class="arf_editor_top_menu_item_icon">
								<svg viewBox="0 -5 32 32">
								<g id="submit_action"><path fill="none" stroke="#ffffff" fill-rule="evenodd" clip-rule="evenodd" stroke-width="1.7" d="M23.362,0.85v10.293c0,3.138-2.544,5.683-5.683,5.683h-7.33v3.283l-8.86-6.007l8.86-6.319v4.05h6.686c0.738,0,1.336-0.598,1.336-1.336V0.85H23.362z"/></g>
								</svg>
							</span>
							<label class="arf_editor_top_menu_label">
								<?php echo esc_html__( 'Submit Action', 'arforms-form-builder' ); ?>
							</label>
						</li>
						<li class="arf_editor_top_menu_item" id="email_marketers">
							<span class="arf_editor_top_menu_item_icon">
								<svg viewBox="0 -3 32 32">
								<g id="email_marketers"><path  stroke="#ffffff" fill="#ffffff" fill-rule="evenodd" clip-rule="evenodd" stroke-width="0.5" d="M23.287,23.217c-0.409,0.46-0.84,0.866-0.932,0.934c-0.092,0.068-0.568,0.417-1.088,0.745c-0.387,0.244-0.789,0.468-1.204,0.669c-5.41,2.64-11.02,1.559-12.981-4.493c-0.291-0.896-0.125-1.162-0.658-1.273c-0.998-0.209-2.2-0.696-2.647-1.711c-0.528-1.2-0.571-2.338-0.003-3.193c0.341-0.513,0.323-0.929-0.217-1.223c-3.604-1.958-1.974-5.485,0.918-8.376c2.536-2.537,6.438-5.428,9.759-3.627c0.54,0.293,1.352,0.39,1.911,0.135c0.513-0.235,1.032-0.436,1.555-0.597c1.414-0.435,4.297-0.813,4.985,1.057c0.509,1.382,0.654,3.366-0.127,4.745c-0.305,0.536-0.203,1.047,0.103,1.582c0.589,1.031,0.529,2.774,0.514,3.681c-0.019,1.043,0.299,1.927,0.67,2.809c0.239,0.568,0.521,1.013,0.623,1.038c0.069,0.017,0.119,0.054,0.134,0.119c0.048,0.209,0.081,0.413,0.101,0.613c0.035,0.341,0.105,0.926,0.164,1.311c0.034,0.226,0.056,0.459,0.061,0.704C24.961,20.623,24.314,22.061,23.287,23.217z M20.125,23.994c0.614,0.016,1.48-0.411,1.869-0.889c0.415-0.511,0.764-1.068,1.024-1.661c0.249-0.564-0.004-0.708-0.534-0.397c-2.286,1.34-5.727,1.179-7.432-0.95c-0.385-0.481-0.52-0.737-0.421-0.483c0.099,0.254,0.036,0.629-0.172,0.854c-0.209,0.224-0.23,0.61-0.025,0.843s0.537,0.25,0.72,0.055c0.184-0.194,0.351-0.326,0.374-0.297c0.022,0.029-0.106,0.204-0.29,0.39c-0.185,0.187-0.205,0.459-0.038,0.6c0.167,0.141,0.444,0.108,0.614-0.062c0.168-0.172,0.486-0.141,0.723,0.049c0.238,0.191,0.322,0.453,0.176,0.605c-0.147,0.152,0.136,0.512,0.666,0.732c0.529,0.22,1.025,0.291,1.082,0.233s0.167-0.068,0.246-0.024c0.081,0.044,0.116,0.11,0.077,0.149c-0.038,0.04,0.417,0.193,1.03,0.237C19.917,23.986,20.022,23.991,20.125,23.994zM22.358,20.167c-0.141,0.143-0.28,0.285-0.421,0.426l-0.128,0.126c-0.071,0.07,0.188-0.045,0.493-0.354C22.61,20.056,22.59,19.931,22.358,20.167z M4.795,16.74c0.122,0.274,0.447,0.299,0.684,0.079c0.236-0.221,0.504-0.19,0.634,0.05c0.131,0.24,0.098,0.572-0.105,0.76c-0.204,0.188-0.032,0.718,0.482,1.056c0.459,0.302,0.945,0.495,1.389,0.515c0.079,0.003,0.241,0.035,0.264,0.136c0.045,0.203,0.097,0.41,0.153,0.621c0.093,0.34,0.354,0.451,0.569,0.251c0.216-0.199,0.446-0.339,0.516-0.313c0.068,0.026,0.149,0.136,0.185,0.247c0.034,0.111-0.144,0.408-0.397,0.664c-0.253,0.255-0.292,0.935-0.03,1.493c0.027,0.059,0.056,0.117,0.084,0.174c0.271,0.553,0.725,0.794,0.944,0.574c0.221-0.22,0.544-0.116,0.752,0.215c0.209,0.332,0.251,0.745,0.064,0.946c-0.188,0.201-0.233,0.475-0.096,0.604c0.083,0.079,0.168,0.154,0.257,0.224c0.052,0.041,0.105,0.081,0.159,0.118c0.09,0.062,0.296-0.027,0.459-0.199s0.299-0.306,0.306-0.299c0.007,0.006-0.122,0.147-0.288,0.315c-0.165,0.168-0.152,0.408,0.038,0.524c0.189,0.117,0.468,0.078,0.614-0.07c0.146-0.147,0.485-0.114,0.777,0.044c0.291,0.157,0.45,0.352,0.34,0.467c-0.111,0.116,0.28,0.348,0.892,0.41c1.708,0.172,3.512-0.274,5.061-1.156c0.534-0.305,0.435-0.575-0.179-0.621c-4.634-0.335-10.049-4.076-6.684-8.961c0.198-0.287-1.173-1.688-1.188-2.397c-0.038-1.685,0.779-2.368,2.145-3.229c0.763-0.481,1.711-0.692,2.656-0.677c0.613,0.011,1.134,0.093,1.171,0.056c0.038-0.036,0.095-0.077,0.126-0.092c0.023-0.01,0.021,0.003,0.005,0.029c-0.016,0.023,0.005,0.007,0.052-0.031c0.037-0.028,0.071-0.051,0.092-0.061c0.037-0.015,0.1-0.025,0.14-0.024c0.04,0.002,0.002,0.072-0.085,0.154c-0.086,0.083-0.107,0.162-0.047,0.175c0.061,0.014,0.214-0.074,0.351-0.192c0.137-0.12-0.172-0.489-0.76-0.67c-0.111-0.035-0.225-0.064-0.338-0.09c-0.6-0.133-1.115-0.09-1.13-0.078c-0.014,0.013-0.509,0.147-1.072,0.394c-0.395,0.173-0.784,0.379-1.166,0.612c-0.524,0.321-0.615,0.336-0.234-0.018c0.38-0.354,0.217-0.474-0.328-0.189c-2.063,1.079-3.949,3.012-5.192,4.528c-0.098,0.12-0.251,0.198-0.421,0.239c-0.263,0.064-0.495,0.026-0.505,0.036c-0.011,0.01-0.342,0.127-0.646,0.396c-0.305,0.27-0.69,0.857-1.028,1.174C4.896,15.969,4.673,16.466,4.795,16.74z M13.062,2.367c-0.99-0.478-2.052-0.443-3.087-0.101C9.392,2.458,8.606,3.06,8.177,3.502C7.292,4.417,6.353,5.387,5.34,6.434C4.709,7.081,4.212,7.589,3.828,7.983c-0.43,0.44-0.777,0.788-0.772,0.779c0.004-0.009,0.352-0.376,0.779-0.82c1.123-1.165,2.877-2.98,4.211-4.366c0.427-0.444,0.737-0.784,0.691-0.761C8.693,2.838,8.302,3.211,7.869,3.648C6.887,4.636,5.564,5.986,4.004,7.587c-0.429,0.441-0.64,0.513-0.437,0.18c0.204-0.333,0.054-0.217-0.28,0.301C2.964,8.567,2.731,9.077,2.669,9.577c-0.172,1.4,0.531,2.441,1.545,3.169c0.499,0.359,1.162,0.104,1.445-0.444c1.648-3.197,4.321-6.447,7.404-8.688C13.562,3.254,13.617,2.634,13.062,2.367z M18.808,1.454c-0.61,0.061-1.088,0.308-1.111,0.332c-0.022,0.023-0.054,0.037-0.069,0.032c-0.015-0.006-0.082,0.015-0.15,0.047c-0.039,0.019-0.079,0.039-0.12,0.061c-0.28,0.148-0.556,0.303-0.829,0.464c-0.451,0.266-0.877,0.668-1.068,0.775c-0.192,0.106-0.638,0.338-0.969,0.573c-0.2,0.142-0.398,0.287-0.59,0.44c-0.455,0.361-0.897,0.735-1.33,1.116c-1.043,1.074-2.101,2.163-3.173,3.271C8.11,10.15,7.034,11.902,6.26,13.861c-0.003,0.01-0.01,0.018-0.017,0.026C6.234,13.9,6.183,14,6.086,14.062c-0.048,0.031-0.108,0.063-0.185,0.094c-0.021,0.009-0.041,0.017-0.063,0.026c-0.012,0.005-0.02,0.008-0.031,0.013c-0.526,0.196-0.864,0.478-1.054,0.809c-0.304,0.536,0.189,0.728,0.624,0.291c0.177-0.178,0.349-0.351,0.516-0.52c0.435-0.438,0.596-0.87,0.594-1.065c-0.002-0.09,0.04-0.196,0.14-0.316c1.955-2.384,5.12-5.258,8.391-5.892c0.262-0.051,0.546-0.09,0.808-0.122c0.448-0.055,0.915-0.111,1.044-0.113c0.149-0.002,0.23,0.022,0.194,0.055c-0.052,0.048,0.407,0.131,0.994,0.315c0.15,0.048,0.301,0.102,0.449,0.162c0.57,0.232,1.245,0.367,1.585,0.232c0.341-0.134,1.063-0.489,1.348-1.037C22.479,4.995,21.533,1.183,18.808,1.454z M22.605,15.494c-0.452-0.864-0.868-1.535-0.877-2.836c-0.006-1.052,0.049-2.333-0.383-3.319c-0.349-0.798-0.817-0.735-1.315-0.426c-0.522,0.325-0.952,0.779-1.067,0.877c-0.114,0.099-0.315,0.316-0.519,0.43c-0.171,0.096-0.359,0.171-0.383,0.179c-0.087,0.027-0.176,0.045-0.267,0.056c-0.08,0.009-0.205,0.028-0.322,0.021c-0.178-0.01-0.719-0.381-1.319-0.51c-1.802-0.385-2.773,0.865-2.898,2.311c-0.053,0.615,0.316,0.868,0.621,0.568c0.307-0.3,0.551-0.494,0.548-0.433c-0.003,0.062-0.241,0.338-0.535,0.618c-0.293,0.28-0.447,0.892-0.221,1.313c0.137,0.254,0.306,0.49,0.509,0.695c0.079,0.08,0.044,0.151-0.017,0.23c-0.031,0.039-0.06,0.079-0.086,0.118c-0.046,0.066,0.154-0.096,0.449-0.365c0.295-0.268,0.56-0.451,0.595-0.411c0.035,0.041-0.285,0.43-0.714,0.873c-0.057,0.057-0.113,0.114-0.17,0.173c-0.43,0.441-0.993,1.259-1.083,1.87c-0.057,0.385-0.056,0.765-0.005,1.137c0.084,0.611,0.494,0.871,0.741,0.621c0.247-0.251,0.442-0.471,0.433-0.492c-0.006-0.012-0.012-0.025-0.017-0.038c-0.101-0.292,0.885-0.485,1.035-0.49c1.515-0.053,3.036-0.205,4.515-0.551c0.968-0.329,1.938-0.657,2.883-1.05c0.021-0.009,0.087-0.039,0.17-0.078C22.999,16.541,22.89,16.04,22.605,15.494z M22.397,17.352c-0.464,0.17-1.026,0.484-1.252,0.716c-0.225,0.231-0.757,0.452-1.188,0.48c-0.432,0.029-0.712-0.03-0.625-0.118c0.086-0.088-0.146-0.093-0.522-0.022c-0.376,0.071-0.921,0.36-1.216,0.659c-0.296,0.3-0.548,0.497-0.564,0.44c-0.017-0.056,0.146-0.288,0.362-0.516c0.215-0.229-0.021-0.353-0.531-0.297c-0.509,0.058-0.714,0.55-0.311,1.016c0.013,0.013,0.024,0.026,0.036,0.041c0.41,0.46,0.825,0.719,0.813,0.698c-0.013-0.021,0.179-0.24,0.424-0.489c0.246-0.25,0.545-0.223,0.704,0.037c0.158,0.26,0.2,0.57,0.057,0.718c-0.144,0.149,0.178,0.46,0.752,0.543c0.344,0.05,0.696,0.056,1.046,0.013c0.189-0.023,0.369-0.059,0.539-0.107c0.292-0.081,0.458-0.225,0.389-0.271c-0.068-0.046,0.225-0.442,0.653-0.884c0.142-0.146,0.282-0.292,0.425-0.438c0.428-0.442,0.875-1.183,0.893-1.667C23.297,17.419,22.862,17.184,22.397,17.352z M20.224,13.986c-0.675,0.086-0.916-0.718-0.896-1.272c0.018-0.495,0.16-1.292,0.775-1.37c0.698-0.09,0.877,0.721,0.896,1.272C20.982,13.111,20.84,13.907,20.224,13.986z M20.25,11.584c-0.436-0.287-0.567,0.841-0.56,1.032c0.012,0.35,0.059,0.913,0.388,1.13c0.443,0.293,0.554-0.848,0.56-1.032C20.626,12.364,20.58,11.802,20.25,11.584z M16.527,15.25c-0.631,0.081-0.824-0.869-0.808-1.313c0.02-0.579,0.198-1.278,0.86-1.364c0.639-0.081,0.794,0.85,0.81,1.314C17.369,14.465,17.19,15.165,16.527,15.25z M16.64,12.832c-0.478-0.316-0.571,1.04-0.555,1.2c0.033,0.307,0.098,0.771,0.382,0.959c0.434,0.287,0.549-0.828,0.56-1.038C17.014,13.603,16.966,13.047,16.64,12.832z M19.212,7.655c-0.071,0.071-0.145,0.131-0.162,0.134c-0.018,0.003,0.031-0.057,0.109-0.134c0.077-0.077,0.15-0.137,0.161-0.133C19.333,7.524,19.284,7.584,19.212,7.655z M16.305,3.161c-0.017,0.008-0.294,0.19-0.611,0.416s-0.256,0.101,0.13-0.292c0.385-0.393,0.762-0.69,0.84-0.659c0.077,0.03,0.035,0.163-0.094,0.291C16.442,3.044,16.324,3.153,16.305,3.161z M8.963,13.61c-0.011,0.014-0.023,0.021-0.03,0.017c-0.009-0.005-0.005-0.019,0.015-0.032C8.967,13.582,8.977,13.594,8.963,13.61z"/></g></svg>
							</span>
							<label class="arf_editor_top_menu_label">
								<?php echo esc_html__( 'Opt-ins', 'arforms-form-builder' ); ?>
							</label>
						</li>
						<li class="arf_editor_top_menu_item arf_editor_top_menu_dropdown">
							<span class="arf_editor_top_menu_item_icon">
								<svg viewBox="0 -3 32 32">
								<g id="general_options"><path fill="#ffffff" fill-rule="evenodd" clip-rule="evenodd" d="M12.501,20.85v2.002H7.474V20.85H1.489v-2h5.985v-2.002h5.027v2.002h16.953v2H12.501z M18.473,14.853v-2.002H1.489v-2h16.984V8.849H23.5v2.002h5.954v2H23.5v2.002H18.473z M12.501,6.854H7.474V4.852H1.489v-2h5.985V0.85h5.027v2.002h16.953v2H12.501V6.854z"/></g></svg>
							</span>
							<label class="arf_editor_top_menu_label">
								<?php echo esc_html__( 'Other Options', 'arforms-form-builder' ); ?>
								<span class="arf_editor_top_menu_item_icon_drop_icon">
									<svg viewBox="1 1 12 10" width="12px" height="10px">
										<g id="arf_top_menu_arrow">
											<path fill-rule="evenodd" clip-rule="evenodd" fill="#FFFFFF" d="M13.041,3.751L7.733,9.03c-0.169,0.167-0.39,0.251-0.611,0.251
												c-0.221,0-0.442-0.084-0.611-0.251L1.203,3.751C0.897,3.447,0.882,2.979,1.13,2.644C0.882,2.307,0.897,1.839,1.203,1.536
												c0.338-0.336,0.885-0.336,1.223,0l4.696,4.67l4.697-4.67c0.337-0.335,0.885-0.335,1.222,0c0.307,0.304,0.32,0.771,0.072,1.108
												C13.361,2.98,13.347,3.447,13.041,3.751z"/>
										</g>
									</svg>
								</span>
							</label>
							<div class="arf_editor_top_dropdown_submenu_container">
								<ul class="arf_editor_top_dropdown">
									<li class="arf_editor_top_dropdown_option" id="general_options"><?php echo esc_html__( 'General Options', 'arforms-form-builder' ); ?></li>
									<li class="arf_editor_top_dropdown_option" id="arf_hidden_fields_options"><?php echo esc_html__( 'Hidden Input Fields', 'arforms-form-builder' ); ?></li>
									<li class="arf_editor_top_dropdown_option" id="arf_tracking_code"><?php echo esc_html__( 'Submit Tracking Script', 'arforms-form-builder' ); ?></li>
									<?php $arflite_export_action_chk = isset( $_GET['arfaction'] ) && ( sanitize_text_field( $_GET['arfaction'] ) == 'new' || sanitize_text_field( $_GET['arfaction'] ) == 'duplicate' ); ?>
									<li class="arf_editor_top_dropdown_option <?php echo esc_attr( $arflite_export_action_chk ) ? 'arf_export_form_editor_note' : ''; ?>" id="arflite_export_current_form_link"><?php echo esc_html__( 'Export Form', 'arforms-form-builder' ); ?></li>
									<?php do_action( 'arflite_editor_general_options_menu' ); ?>
								</ul>
							</div>
						</li>
					</ul>
				</div>
				<div class="arf_editor_top_menu_button_wrapper">
					<div class="arf_editor_shortcode_wrapper">
						<div class="arf_editor_shortcode_icon_wrapper arfbelttooltip" id="arf_shortcodes_info" data-title="<?php echo esc_html__( 'Shortcodes', 'arforms-form-builder' ); ?>"></div>
						<div class="arf_editor_form_shortcode_list_popup">
							<div class="arf_editor_form_shortcode_list_content">
								<?php
									$arf_saved_form_shortcode   = 'display-none-cls';
									$arf_unsaved_form_shortcode = '';
								if ( isset( $_GET['arfaction'] ) && sanitize_text_field( $_GET['arfaction'] ) == 'edit' ) {
									$arf_saved_form_shortcode   = '';
									$arf_unsaved_form_shortcode = 'display-none-cls';
								}
									$shortcode_form_id = ( isset( $_GET['arfaction'] ) && sanitize_text_field( $_GET['arfaction'] ) == 'edit' ) ? $form_id : '{arf_form_id}';
								?>
								<ul id="arf_editor_saved_form_shortcodes" class="arf_editor_form_shortcode_list <?php echo esc_attr( $arf_saved_form_shortcode ); ?>">
									<li class="arf_editor_form_shortcode_header"><span><?php echo esc_html__( 'Shortcodes', 'arforms-form-builder' ); ?></span></li>
									<li class="arf_editor_form_shortcode">
										<span class="arf_shortcode_label"><?php echo esc_html__( 'Embed Inline Form', 'arforms-form-builder' ); ?></span>
										<span class="arf_shortcode_content">[ARForms id=<?php echo esc_html( $shortcode_form_id ); ?>]</span>
									</li>
									<li class="arf_editor_form_shortcode">
										<span class="arf_shortcode_label"><?php echo esc_html__( 'PHP Function', 'arforms-form-builder' ); ?></span>
										<span class="arf_shortcode_content">&lt;?php global $arflitemaincontroller; echo $arflitemaincontroller->arflite_get_form_shortcode(array('id'=>'<?php echo esc_html( $shortcode_form_id ); ?>')); ?&gt;</span>
									</li>
								</ul>

								<ul id="arf_editor_unsaved_form_shortcodes" class="arf_editor_form_shortcode_list <?php echo esc_attr( $arf_unsaved_form_shortcode ); ?>">
									<li class="arf_editor_form_shortcode_header"><span><?php echo esc_html__( 'Shortcodes', 'arforms-form-builder' ); ?></span></li>
									<li class="arf_editor_form_shortcode">
										<span class="arf_shortcode_content"><?php echo esc_html__( 'Please save form to generate shortcode.', 'arforms-form-builder' ); ?></span>
									</li>
								</ul>

							</div>
						</div>
					</div>
					<button type="submit" name="arf_save" class="arf_top_menu_save_button rounded_button btn_green">
						<?php echo esc_html__( 'Save', 'arforms-form-builder' ); ?>
					</button>
					<button type="button" name="arf_preview" class="arf_top_menu_preview_button arfbelttooltip" data-url="<?php echo ( esc_attr($action) == 'new' ) ? esc_attr( $pre_link ) . '&form_id=' . esc_attr( $arflite_id ) : esc_attr( $pre_link ); ?>" data-default-url="<?php echo ( esc_attr($action) == 'new' ) ? esc_attr( $pre_link ) : ''; ?>" onclick="arflitegetformpreview();" data-title="<?php echo esc_html__( 'Preview', 'arforms-form-builder' ); ?>" >
						<span class="arf_top_menu_preview_button_icon">
							<svg viewBox="0 0 30 30" width="40px" height="35px">
							<g id="preview"><path fill="#ffffff" fill-rule="evenodd" clip-rule="evenodd" d="M12.993,15.23c-7.191,0-11.504-7.234-11.504-7.234S5.801,0.85,12.993,0.85c7.189,0,11.504,7.19,11.504,7.19S20.182,15.23,12.993,15.23z M12.993,2.827c-5.703,0-8.799,5.214-8.799,5.214s3.096,5.213,8.799,5.213c5.701,0,8.797-5.213,8.797-5.213S18.694,2.827,12.993,2.827zM12.993,11.572c-1.951,0-3.531-1.581-3.531-3.531s1.58-3.531,3.531-3.531c1.949,0,3.531,1.581,3.531,3.531S14.942,11.572,12.993,11.572z"/></g>
							</svg>
						</span>
					</button>
					<button type="button" name="arf_reset" class="arf_top_menu_reset_button arfbelttooltip" data-title="<?php echo esc_html__( 'Reset Style', 'arforms-form-builder' ); ?>" onclick="arflite_reset_style_functionality();" >
						<span class="arf_top_menu_reset_button_icon">
							<svg viewBox="-4 -1 30 30" width="40px" height="35px">
							<g id="preview"><path xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd" fill="#ffffff" d="M16.07,0.293c-0.26-0.107-0.482-0.063-0.666,0.134l-2.037,1.827c-0.679-0.641-1.455-1.138-2.328-1.49  c-0.872-0.352-1.775-0.528-2.708-0.528c-0.99,0-1.937,0.194-2.838,0.581C4.591,1.204,3.814,1.724,3.16,2.378  C2.506,3.032,1.986,3.81,1.598,4.711c-0.387,0.901-0.58,1.847-0.58,2.837s0.193,1.937,0.58,2.838  c0.388,0.901,0.908,1.679,1.562,2.332c0.654,0.654,1.432,1.175,2.333,1.562c0.901,0.388,1.848,0.581,2.838,0.581  c1.092,0,2.13-0.23,3.113-0.69s1.821-1.109,2.514-1.947c0.051-0.063,0.075-0.135,0.071-0.214c-0.003-0.079-0.033-0.145-0.091-0.195  L12.634,10.5c-0.07-0.058-0.149-0.086-0.238-0.086c-0.102,0.013-0.175,0.051-0.219,0.114c-0.464,0.604-1.031,1.069-1.705,1.4  c-0.672,0.33-1.387,0.494-2.142,0.494c-0.66,0-1.29-0.128-1.89-0.386c-0.601-0.257-1.119-0.604-1.558-1.042  c-0.438-0.438-0.785-0.957-1.042-1.557s-0.386-1.23-0.386-1.891c0-0.659,0.129-1.29,0.386-1.89s0.604-1.119,1.042-1.557  C5.322,3.664,5.84,3.316,6.441,3.059c0.6-0.257,1.229-0.386,1.89-0.386c1.275,0,2.384,0.436,3.323,1.305L9.882,6.062  c-0.196,0.19-0.24,0.41-0.133,0.657C9.858,6.973,10.044,7.1,10.311,7.1h5.521c0.165,0,0.308-0.061,0.429-0.181  c0.12-0.121,0.181-0.264,0.181-0.429V0.855C16.442,0.589,16.318,0.401,16.07,0.293z"></path></g>
							</svg>
						</span>
					</button>
					<button type="button" name="arf_cancel" class="arf_top_menu_cancel_button arfbelttooltip" onClick="window.location = '<?php echo esc_url( admin_url( 'admin.php?page=ARForms' ) ); ?>'" data-title="<?php echo esc_html__( 'Cancel', 'arforms-form-builder' ); ?>">
						<span class="arf_top_menu_cancel_button_icon">
							<svg viewBox="-5 -1 30 30" width="45px" height="45px">
							<g id="preview"><path fill-rule="evenodd" clip-rule="evenodd" fill="#ffffff" d="M10.702,10.909L6.453,6.66l-4.249,4.249L1.143,9.848l4.249-4.249L1.154,1.361l1.062-1.061l4.237,4.237l4.238-4.237l1.061,1.061L7.513,5.599l4.249,4.249L10.702,10.909z"></path></g>
							</svg>
						</span>
					</button>
				</div>
			</div>
		</div>
		<div class="arf_editor_header_shortcode_belt">
			<div class="arf_editor_header_form_title"></div>
			<div class="arf_editor_header_form_width">
				<div class="arf_editor_form_width_wrapper">
					<span class="arf_editor_form_width_label"><?php echo esc_html__( 'Width', 'arforms-form-builder' ); ?></span>
					<span class="arfform_width_header_span" >
						<?php
							$form_width_unit_opts = array(
								'px' => 'px',
								'%'  => '%',
							);

							echo $arflitemaincontroller->arflite_selectpicker_dom( 'arf_editor_form_width_unit', 'arf_editor_form_width_unit', 'arfform_width_header_dl', 'width:40px;', $newarr['form_width_unit'], array(), $form_width_unit_opts ); //phpcs:ignore
							?>
					</span>
					<span class="arf_editor_form_width_input_wrapper">
						<input type="text" name="arf_editor_form_width" id="arf_editor_form_width" class="arf_editor_form_width_input" value="<?php echo esc_attr( $newarr['arfmainformwidth'] ); ?>" />
					</span>
					<div class="arf_display_form_id_editor <?php echo ( $arfaction == 'edit' ) ? '' : 'arf_save_form_id_note'; ?>">(Form ID: <?php echo ( $arfaction == 'edit' ) ? intval($form_id) : '{arf_form_id}'; ?>)</div>
				</div>
			</div>
		</div>

		<div class="arf_form_editor_wrapper">
			<div class="arf_form_element_wrapper">

				<ul class="arf_form_style_tabs">
					<li class="arf_form_element_type_tab_item active" data-id="arf_form_input_fields_container"><?php echo esc_html__( 'Input Fields', 'arforms-form-builder' ); ?></li>
					<li class="arf_form_element_type_tab_item" data-id="arf_form_other_fields_container"><?php echo esc_html__( 'Other Fields', 'arforms-form-builder' ); ?></li>
				</ul>
				<ul class="arf_form_elements_container active" id="arf_form_input_fields_container">
					<?php
					$advancedFields = $arflitefieldhelper->arflite_pro_field_selection();

					$allFields    = array_merge( $arffield_selection, $advancedFields );
					$input_fields = $arflitefieldhelper->arflite_input_field_keys();

					$other_fields         = $arflitefieldhelper->arflite_other_fields_keys();
					$sortedFields         = $arflitefieldhelper->arflite_field_element_orders();
					$full_width_elm_array = $arflitefieldhelper->arflite_full_width_field_element();

					$arflite_pro_fields = $arflitefieldhelper->arflite_pro_fields();

					foreach ( $sortedFields as $key ) {
						if ( in_array( $key, $input_fields ) ) {
							$icon      = $allFields[ $key ]['icon'];
							$pro_class = '';
							if ( in_array( $key, $arflite_pro_fields ) ) {
								$pro_class = 'arflite_pro_form_field arflite_prevent_sorting arf_restricted_control ';
							}
							?>
							<li class="arf_form_element_item frmbutton <?php echo esc_attr( $pro_class ); ?> frm_t<?php echo esc_attr( $key ); ?>" id="<?php echo esc_attr( $key ); ?>" data-field-id="<?php echo esc_attr( $arflite_id ); ?>" data-type="<?php echo esc_attr( $key ); ?>">
								<div class="arf_form_element_item_inner_container" <?php echo ( in_array( $key, $arflite_pro_fields ) ) ? ' data-title="' . esc_html__( 'Pro', 'arforms-form-builder' ) . '" ' : ''; ?>>
									<span class="arf_form_element_item_icon">
										<?php
										echo wp_kses(
											$icon,
											array(
												'svg'    => array(
													'viewbox' => array(),
													'xmlns' => array(),
													'width' => array(),
													'height' => array(),
													'fill' => array(),
													'version' => array(),
													'xmlns:xlink' => array(),
													'x'    => array(),
													'y'    => array(),
													'style' => array(),
													'xml:space' => array(),
												),
												'path'   => array(
													'd'    => array(),
													'fill' => array(),
													'fill-rule' => array(),
													'clip-rule' => array(),
													'xmlns' => array(),
													'stroke' => array(),
													'stroke-width' => array(),
												),
												'g'      => array(
													'id'   => array(),
													'fill' => array(),
												),
												'rect'   => array(
													'x'    => array(),
													'y'    => array(),
													'width' => array(),
													'height' => array(),
													'rx'   => array(),
													'stroke' => array(),
													'stroke-width' => array(),
													'fill' => array(),
													'transform' => array(),
												),
												'circle' => array(
													'cx'   => array(),
													'cy'   => array(),
													'fill' => array(),
													'stroke' => array(),
													'stroke-width' => array(),
													'r'    => array(),
												),
											)
										);
										?>
									</span>
									<label class="arf_form_element_item_text"><?php echo esc_html( $allFields[ $key ]['label'] ); ?></label>
								</div>
							</li>
							<?php
						}
					}
					?>
				</ul>

				<ul class="arf_form_elements_container" id="arf_form_other_fields_container">
					<?php
					foreach ( $sortedFields as $key ) {
						if ( in_array( $key, $other_fields ) ) {
							$icon           = $allFields[ $key ]['icon'];
							$full_width_cls = '';
							if ( in_array( $key, $full_width_elm_array ) ) {
								$full_width_cls = ' arf_full_width_field_element ';
							}
							if ( in_array( $key, $arflite_pro_fields ) ) {
								$full_width_cls .= ' arflite_pro_form_field arflite_prevent_sorting arf_restricted_control ';
							}
							?>
								<li class="arf_form_element_item <?php echo esc_attr( $full_width_cls ); ?> frmbutton frm_t<?php echo esc_attr( $key ); ?>" id="<?php echo esc_attr( $key ); ?>" data-field-id="<?php echo esc_attr( $arflite_id ); ?>" data-type="<?php echo esc_attr( $key ); ?>" >
									<div class="arf_form_element_item_inner_container" <?php echo ( in_array( $key, $arflite_pro_fields ) ) ? ' data-title="' . esc_html__( 'Pro', 'arforms-form-builder' ) . '" ' : ''; ?>>
										<span class="arf_form_element_item_icon">
										<?php
											echo wp_kses(
												$icon,
												array(
													'svg'  => array(
														'viewbox' => array(),
														'xmlns' => array(),
														'width' => array(),
														'height' => array(),
														'fill' => array(),
														'version' => array(),
														'xmlns:xlink' => array(),
														'x' => array(),
														'y' => array(),
														'style' => array(),
														'xml:space' => array(),
													),
													'path' => array(
														'd' => array(),
														'fill' => array(),
														'fill-rule' => array(),
														'clip-rule' => array(),
														'xmlns' => array(),
														'stroke' => array(),
														'stroke-width' => array(),
													),
													'g'    => array(
														'id' => array(),
														'fill' => array(),
													),
													'rect' => array(
														'x' => array(),
														'y' => array(),
														'width' => array(),
														'height' => array(),
														'rx' => array(),
														'stroke' => array(),
														'stroke-width' => array(),
														'fill' => array(),
														'transform' => array(),
													),
													'circle' => array(
														'cx' => array(),
														'cy' => array(),
														'fill' => array(),
														'stroke' => array(),
														'stroke-width' => array(),
														'r' => array(),
													),
												)
											);
										?>
										</span>
										<label class="arf_form_element_item_text"><?php echo esc_html( $allFields[ $key ]['label'] ); ?></label>
									</div>
								</li>
								<?php
						}
					}
					?>
				</ul>
		<div class="arf_form_element_resize"></div>
		<?php
			$viewBox = '0 -6 30 30';
		if ( is_rtl() ) {
			$viewBox = '-13 -6 30 30';
		}
		?>
		<button type="button" class="arf_hide_form_element_wrapper"><svg viewBox="<?php echo esc_attr( $viewBox ); ?>" width="25px" height="45px"><path xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd" fill="#4E5462" d="M3.845,6.872l4.816,4.908l-1.634,1.604L0.615,6.849L0.625,6.84  L0.617,6.832L7.152,0.42l1.603,1.634L3.845,6.872z"/></svg></button>
			</div>
			<?php echo str_replace( 'id="{arf_id}"', 'id="arfeditor_loader" ', ARFLITE_LOADER_ICON ); //phpcs:ignore ?>
			<div class="arf_form_editor_content display-none-cls">
				<div class="arf_form_editor_inner_container" id="maineditcontentview">
					<?php require ARFLITE_VIEWS_PATH . '/arflite_edit_form.php'; ?>
				</div>
			</div>
			<div class="arf_form_styling_tools">
				<ul class="arf_form_style_tabs">
					<li class="arf_form_style_tab_item active" data-id="arf_form_styling_tools"><?php echo esc_html__( 'Style Options', 'arforms-form-builder' ); ?></li>
					<li class="arf_form_style_tab_item" data-id="arf_form_custom_css"><?php echo esc_html__( 'Custom CSS', 'arforms-form-builder' ); ?></li>
				</ul>
				<input type="hidden" name="arf_styling_height" id="arf_styling_height"/>
				<input type="hidden" name="arf_styling_content_height" id="arf_styling_content_height"/>
				<div class="arf_form_style_tab_container active" id="arf_form_styling_tools">
					<input type="hidden" name="arfmf" value="<?php echo esc_attr( $arflite_id ); ?>" id="arfmainformid" />
					<div class="arf_form_style_tab_accordion">
						<div class="arf_form_accordion_tabs">
							<dl class="arf_accordion_tab_color_options active">
								<dd>
									<a href="javascript:void(0)" data-target="arf_accordion_tab_color_options"><?php echo esc_html__( 'Basic Styling Options', 'arforms-form-builder' ); ?></a>
									<div class="arf_accordion_container active">
										<div class="arf_input_style_container">
											<div class="arf_accordion_container_row arf_padding">
												<div class='arf_accordion_outer_title'><?php echo esc_html__( 'Select Theme', 'arforms-form-builder' ); ?></div>
											</div>
											<div class="arf_accordion_container_row_container">
												<div class="arf_accordion_inner_container"><?php echo esc_html__( 'Input Style', 'arforms-form-builder' ); ?></div>
												<div class="arf_accordion_inner_container">

														<?php
															$inputStyle = array();

															$newarr['arfinputstyle'] = ( isset( $newarr['arfinputstyle'] ) && $newarr['arfinputstyle'] != '' ) ? $newarr['arfinputstyle'] : 'material';
															$inputStyle              = array(
																'standard' => addslashes( esc_html__( 'Standard Style', 'arforms-form-builder' ) ),
																'rounded' => addslashes( esc_html__( 'Rounded Style', 'arforms-form-builder' ) ),
																'material' => addslashes( esc_html__( 'Material Style', 'arforms-form-builder' ) ),
																'material_outlined' => addslashes( esc_html__( 'Material Outlined', 'arforms-form-builder' ) ) . '<span class="arflite_pro_version_notice">(Premium)</span>',
															);

															$arfmainforminputstyle_options_cls = array();

															foreach ( $inputStyle as $style => $value ) {
																if ( $style == 'material_outlined' ) {
																	$arfmainforminputstyle_options_cls['material_outlined'] = 'arf_restricted_control';
																}
															}

															echo $arflitemaincontroller->arflite_selectpicker_dom( 'arfinpst', 'arfmainforminputstyle', '', '', $newarr['arfinputstyle'], array(), $inputStyle, false, $arfmainforminputstyle_options_cls, false, array(), false, array(), false, 'arf_input_style_dpdn_ul' ); //phpcs:ignore
															?>
												</div>
											</div>
											<div class="arf_input_style_loader_div">
												<div class="arf_imageloader arf_form_style_input_style_loader" id="arf_input_style_loader"></div>
											</div>
										</div>

										<div class="arf_accordion_container_row_separator"></div>

										<div class="arf_color_scheme_container">
											<div class="arf_accordion_container_row arf_padding">
												<div class='arf_accordion_outer_title'><?php echo esc_html__( 'Color Scheme', 'arforms-form-builder' ); ?></div>
											</div>
											<div class="arf_accordion_container_row_container">
												<div class='arf_accordion_inner_container'><?php echo esc_html__( 'Choose Color', 'arforms-form-builder' ); ?></div>
												<div class="arf_accordion_inner_container arf_frm_custom_css_block">
													<input type="hidden" name="arfmcs" data-db-skin="<?php echo esc_attr( $active_skin ); ?>" id="arf_color_skin" value="<?php echo esc_attr( $active_skin ); ?>" data-default-skin="<?php echo esc_attr( $active_skin ); ?>" />
													<?php
													if ( isset( $skinJson->skins ) && ! empty( $skinJson->skins ) ) {
														foreach ( $skinJson->skins as $skin => $val ) {
															if ( $skin == 'custom' ) {
																continue;
															}
															?>
															<div class="arf_skin_container <?php echo ( $active_skin == $skin ) ? 'active_skin' : ''; ?>" data-skin="<?php echo esc_attr( $skin ); ?>" style="background:<?php echo esc_attr( $val->main ); ?>;" id="arf_skin_<?php echo esc_attr( $skin ); ?>">
															</div>
															<?php
														}
													}
													?>
												</div>
											</div>
											<div class="arf_accordion_container_row_container">
												<div class='arf_accordion_inner_container'><?php echo esc_html__( 'Custom Color', 'arforms-form-builder' ); ?></div>
												<div class="arf_accordion_inner_container">
													<?php $custom_bg_color = ( isset( $newarr['arfmainbasecolor'] ) && $newarr['arfmainbasecolor'] != '' ) ? esc_attr( $newarr['arfmainbasecolor'] ) : $skinJson->skins->$active_skin->main; ?>
													<div class="arf_skin_container <?php echo ( $active_skin == 'custom' ) ? 'active_skin' : ''; ?>" data-skin="custom" style="background:<?php echo esc_attr( $custom_bg_color ); ?>;"></div>
													<div class="arf_custom_color">
														<div class="arf_custom_color_icon">
															<svg viewBox="-6 -10 35 35">
															<g id="paint_brush"><path fill="#ffffff" fill-rule="evenodd" clip-rule="evenodd" d="M15.948,7.303L15.875,7.23l0.049-0.049l-2.459-2.459l3.944-3.872l2.313,0.024v2.654L15.948,7.303z M12.631,6.545c0.058,0.039,0.111,0.081,0.167,0.122c0.036,0.005,0.066,0.011,0.066,0.011c0.022,0.008,0.034,0.023,0.056,0.032l1.643,1.643c0.58,5.877-7.619,6.453-7.619,6.453c-5.389,0.366-5.455-1.907-5.455-1.907c3.559,1.164,6.985-5.223,6.985-5.223C11.001,4.915,12.631,6.545,12.631,6.545z"/></g>
															</svg>
														</div>
														<div class="arf_custom_color_label" id="arf_custom_color_label"><?php echo esc_html__( 'Custom', 'arforms-form-builder' ); ?>
														</div>
													</div>
												</div>
											</div>
											<div class="arf_color_scheme_loader_div">
												<div class="arf_imageloader arf_form_style_color_scheme_loader" id="arf_color_scheme_loader"></div>
											</div>
										</div>

										<div class="arf_accordion_container_row_separator"></div>
										<div class="arf_accordion_container_row arf_padding">
											<div class='arf_accordion_outer_title'><?php echo esc_html__( 'Font Options', 'arforms-form-builder' ); ?></div>
										</div>
										<div class="arf_accordion_container_row_container">
											<div class='arf_accordion_inner_container'><?php echo esc_html__( 'Font Family', 'arforms-form-builder' ); ?></div>
											<div class="arf_accordion_inner_container arfinputstyledrpdwn_container">
												<?php
													$fontsarr = array(
														'' => array(
															'inherit' => addslashes( esc_html__( 'Inherit from theme', 'arforms-form-builder' ) ),
														),
														'default||' . addslashes( esc_html__( 'Default Fonts', 'arforms-form-builder' ) ) => $arfliteformcontroller->get_arflite_default_fonts(),
														'google||' . addslashes( esc_html__( 'Google Fonts', 'arforms-form-builder' ) ) => arflite_google_font_listing(),
													);

													$newarr['arfcommonfont'] = ( isset( $newarr['arfcommonfont'] ) && $newarr['arfcommonfont'] != '' ) ? $newarr['arfcommonfont'] : 'Helvetica';

													echo $arflitemaincontroller->arflite_selectpicker_dom( 'arfcommonfont', 'arfcommonfontfamily', '', '', $newarr['arfcommonfont'], array(), $fontsarr, true, array(), false, array(), false, array(), true ); //phpcs:ignore

													?>
											</div>
										</div>
										<div class="arf_accordion_container_row_container arf_accordion_container_row_input_size" >
											<div class='arf_accordion_inner_container'><?php echo esc_html__( 'Input Field size', 'arforms-form-builder' ); ?></div>
											<div class="arf_accordion_inner_container arf_accordion_container_mar">
												<div class="arf_slider_wrapper">
													<div id="arflite_mainfieldcommonsize" class="noUi-target noUi-ltr noUi-horizontal noUi-txt-dir-ltr slider-track"></div>
													<input id="arfmainfieldcommonsize_exs" class="arf_slider arf_slider_input" data-slider-id='arfmainfieldcommonsize_exsSlider' type="text" data-slider-min="1" data-slider-max="10" data-slider-step="1" data-slider-value="<?php echo isset( $newarr['arfmainfieldcommonsize'] ) ? esc_attr( $newarr['arfmainfieldcommonsize'] ) : '3'; ?>" />
													<div class="arf_slider_unit_data">
														<div class="input-size-slider-start"><?php echo esc_html__( '1', 'arforms-form-builder' ); ?></div>
														<div class="input-size-slider-end"><?php echo esc_html__( '10', 'arforms-form-builder' ); ?></div>
													</div>
													<input type="hidden" name="arfmainfieldcommonsize" class="txtxbox_widget "  id="arfmainfieldcommonsize" value="<?php echo isset( $newarr['arfmainfieldcommonsize'] ) ? esc_attr( $newarr['arfmainfieldcommonsize'] ) : '3'; ?>" size="4" />
												</div>
											</div>
											<!-- <div class="arf_right arfmarginright arf_custom_font_sec">
												<div class="arf_custom_font arf_basic_cutom_font_all">
													<div class="arf_custom_font_icon">
														<svg viewBox="-10 -10 35 35">
														<g id="paint_brush">
														<path fill="#ffffff" fill-rule="evenodd" clip-rule="evenodd" d="M7.423,14.117c1.076,0,2.093,0.022,3.052,0.068v-0.82c-0.942-0.078-1.457-0.146-1.542-0.205  c-0.124-0.092-0.203-0.354-0.235-0.787s-0.049-1.601-0.049-3.504l0.059-6.568c0-0.299,0.013-0.472,0.039-0.518  C8.772,1.744,8.85,1.725,8.981,1.725c1.549,0,2.584,0.043,3.105,0.128c0.162,0.026,0.267,0.076,0.313,0.148  c0.059,0.092,0.117,0.687,0.176,1.784h0.811c0.052-1.201,0.14-2.249,0.264-3.145l-0.107-0.156c-2.396,0.098-4.561,0.146-6.494,0.146  c-1.94,0-3.936-0.049-5.986-0.146L0.954,0.563c0.078,0.901,0.11,1.976,0.098,3.223h0.84c0.085-1.062,0.141-1.633,0.166-1.714  C2.083,1.99,2.121,1.933,2.17,1.9c0.049-0.032,0.262-0.065,0.641-0.098c0.652-0.052,1.433-0.078,2.34-0.078  c0.443,0,0.674,0.024,0.69,0.073c0.016,0.049,0.024,1.364,0.024,3.947c0,1.313-0.01,2.602-0.029,3.863  c-0.033,1.776-0.072,2.804-0.117,3.084c-0.039,0.201-0.098,0.34-0.176,0.414c-0.078,0.075-0.212,0.129-0.4,0.161  c-0.404,0.065-0.791,0.098-1.162,0.098v0.82C4.861,14.14,6.008,14.117,7.423,14.117L7.423,14.117z"/>
														</svg>
													</div>

													<div class="arf_custom_font_label"><?php echo esc_html__( 'Advanced font options', 'arforms-form-builder' ); ?>
													</div>
												</div>
											</div> -->
										</div>
										<div class="arf_accordion_container_row_container">
											<div class="arf_custom_font arf_basic_cutom_font_all">
												<div class="arf_custom_font_icon">
													<svg viewBox="-10 -10 35 35">
													<g id="paint_brush">
													<path fill="#ffffff" fill-rule="evenodd" clip-rule="evenodd" d="M7.423,14.117c1.076,0,2.093,0.022,3.052,0.068v-0.82c-0.942-0.078-1.457-0.146-1.542-0.205  c-0.124-0.092-0.203-0.354-0.235-0.787s-0.049-1.601-0.049-3.504l0.059-6.568c0-0.299,0.013-0.472,0.039-0.518  C8.772,1.744,8.85,1.725,8.981,1.725c1.549,0,2.584,0.043,3.105,0.128c0.162,0.026,0.267,0.076,0.313,0.148  c0.059,0.092,0.117,0.687,0.176,1.784h0.811c0.052-1.201,0.14-2.249,0.264-3.145l-0.107-0.156c-2.396,0.098-4.561,0.146-6.494,0.146  c-1.94,0-3.936-0.049-5.986-0.146L0.954,0.563c0.078,0.901,0.11,1.976,0.098,3.223h0.84c0.085-1.062,0.141-1.633,0.166-1.714  C2.083,1.99,2.121,1.933,2.17,1.9c0.049-0.032,0.262-0.065,0.641-0.098c0.652-0.052,1.433-0.078,2.34-0.078  c0.443,0,0.674,0.024,0.69,0.073c0.016,0.049,0.024,1.364,0.024,3.947c0,1.313-0.01,2.602-0.029,3.863  c-0.033,1.776-0.072,2.804-0.117,3.084c-0.039,0.201-0.098,0.34-0.176,0.414c-0.078,0.075-0.212,0.129-0.4,0.161  c-0.404,0.065-0.791,0.098-1.162,0.098v0.82C4.861,14.14,6.008,14.117,7.423,14.117L7.423,14.117z"/>
													</svg>
												</div>

												<div class="arf_custom_font_label"><?php echo esc_html__( 'Advanced font options', 'arforms-form-builder' ); ?>
												</div>
											</div>
										</div>
										<div class="arf_accordion_container_row_separator"></div>
										<div class="arf_accordion_container_row arf_padding">
											<div class='arf_accordion_outer_title'><?php echo esc_html__( 'Form Width Settings', 'arforms-form-builder' ); ?></div>
										</div>
										<div class="arf_accordion_container_row_container">
											<div class="arf_accordion_inner_container"><?php echo esc_html__( 'Form Width', 'arforms-form-builder' ); ?></div>
											<div class="arf_dropdown_wrapper arf_accordion_inner_container">
												<?php  $newarr['arfwidthbtn'] = !empty($newarr['arfwidthbtn']) ? $newarr['arfwidthbtn'] : 'Desktop'; ?>
												<?php
													$form_width_unit_style = array(
														"Desktop" => "<i class='fas fa-desktop'></i>",
														"Tablet" => "<i class='fas fa-tablet-alt'></i>",
														"Mobile" => "<i class='fas fa-mobile-alt'></i>",
													);
													$arflite_width_attr = array(
														'onchange' => 'arflite_frm_width(this.value);'
													);
													echo $arflitemaincontroller->arflite_selectpicker_dom( 'arf_form_width_select', 'arf_form_width_select', '', 'width:32%;', $newarr['arfwidthbtn'], $arflite_width_attr, $form_width_unit_style, false, array(), false, array(), false, array(), false, 'arf_frm_width_icon_cls'  ); //phpcs:ignore
												?>
											</div>
										</div>
										<div class="arf_accordion_content_container_cls arf_frm_width_input">

												<input type="text" name="arffw" class="arf_small_width_txtbox arfcolor arf_frm_width_input_cls" data-arfstyle="true" data-arfstyledata='{"standard":".arflite_main_div_{arf_form_id}.arf_form_outer_wrapper~|~max-width{arf_form_width_unit}","material":".arflite_main_div_{arf_form_id}.arf_form_outer_wrapper~|~max-width{arf_form_width_unit}"}' data-arfstyleappend="true" data-arfstyleappendid="arf_{arf_form_id}_form_outer_wrapper" value="<?php echo esc_attr( $newarr['arfmainformwidth'] ); ?>" id="arf_form_width" onkeypress="return arflite_check_numeric_input(event,this)" onpaste="arflite_validatepaste(this, event)" style="<?php echo (($newarr['arfwidthbtn'] == 'Desktop')) ? 'display:block;' : 'display:none;';?>" />
												
												<input type="text" name="arffw_tablet" onkeypress="return arflite_check_numeric_input(event,this)" onpaste="arflite_validatepaste(this, event)" class="arf_small_width_txtbox arfcolor arf_frm_width_input_cls"  value="<?php echo esc_attr($newarr['arfmainformwidth_tablet']) ?>" id="arf_form_width_tablet" style="<?php echo (($newarr['arfwidthbtn'] == 'Tablet')) ? 'display:block;' : 'display:none;'; ?>"/>

                                                <input type="text" name="arffw_mobile" onkeypress="return arflite_check_numeric_input(event,this)" onpaste="arflite_validatepaste(this, event)" class="arf_small_width_txtbox arfcolor arf_frm_width_input_cls" value="<?php echo esc_attr($newarr['arfmainformwidth_mobile']) ?>" id="arf_form_width_mobile" style="<?php echo (($newarr['arfwidthbtn'] == 'Mobile')) ? 'display:block;' : 'display:none;'; ?>"/>

												<div class="arf_dropdown_wrapper">
													<?php
														$newarr['form_width_unit_tablet'] = !empty( $newarr['form_width_unit_tablet']) ? $newarr['form_width_unit_tablet'] : 'px';
														$newarr['arf_width_unit_mobile'] = !empty( $newarr['arf_width_unit_mobile']) ? $newarr['arf_width_unit_mobile'] : 'px';
														$newarr['arfmainformwidth_tablet'] = !empty( $newarr['arfmainformwidth_tablet'] ) ? $newarr['arfmainformwidth_tablet'] : '';
														$newarr['arfmainformwidth_mobile'] = !empty( $newarr['arfmainformwidth_mobile'] ) ? $newarr['arfmainformwidth_mobile'] : '';
	
														$form_width_unit_opts = array(
															'px' => 'px',
															'%' => '%',
														);
	
														$form_width_unit_attr = array(
															'data-arfstyle' => 'true',
															'data-arfstyledata' => '{"standard":".arflite_main_div_{arf_form_id}.arf_form_outer_wrapper~|~arf_form_width_unit","material":".arflite_main_div_{arf_form_id}.arf_form_outer_wrapper~|~arf_form_width_unit"}',
															'data-arfstyleappend' => 'true',
															'data-arfstyleappendid' => 'arf_{arf_form_id}_form_outer_wrapper',
														);
	
														echo $arflitemaincontroller->arflite_selectpicker_dom( 'arffu', 'arffu', 'arf_form_width', 'Desktop' == $newarr['arfwidthbtn'] ? ' display:block;width:50px; ' : 'display:none;width:50px;', $newarr['form_width_unit'], $form_width_unit_attr, $form_width_unit_opts ); //phpcs:ignore
	
														echo $arflitemaincontroller->arflite_selectpicker_dom( 'arffu_tablet', 'arffu_tablet', 'form_width_unit_tablet', 'Tablet' == $newarr['arfwidthbtn'] ? ' display:block;width:50px;' : 'display:none;width:50px;', $newarr['form_width_unit_tablet'], array(), $form_width_unit_opts ); //phpcs:ignore
	
														echo $arflitemaincontroller->arflite_selectpicker_dom( 'arffu_mobile', 'arffu_mobile', 'arf_width_unit_mobile', 'Mobile' == $newarr['arfwidthbtn'] ? ' display:block;width:50px; ' : 'display:none;width:50px;', $newarr['arf_width_unit_mobile'], array(), $form_width_unit_opts ); //phpcs:ignore
	
														?>
												</div>
											</div>

										<div class="arf_accordion_container_row_separator"></div>
											<div class="arf_accordion_container_row arf_padding">
												<div class='arf_accordion_outer_title'><?php echo addslashes( esc_html__( 'Success/Error Message Position', 'arforms-form-builder' ) ); //phpcs:ignore ?></div>
											</div>
												<div class="arf_accordion_container_row_container">
													<div class="arf_accordion_inner_container"><?php echo addslashes( esc_html__( 'Position', 'arforms-form-builder' ) ); //phpcs:ignore ?></div>
													<div class="arf_accordion_inner_container">                                                    
														<div class="arf_toggle_button_group arf_two_button_group">
															<?php $newarr['arfsuccessmsgposition'] = isset( $newarr['arfsuccessmsgposition'] ) ? $newarr['arfsuccessmsgposition'] : 'top'; ?>

															<label class="arf_toggle_btn <?php echo ( $newarr['arfsuccessmsgposition'] == 'bottom' ) ? 'arf_success' : ''; ?>">
																<input type="radio" name="arfsuccessmsgposition" class="visuallyhidden" id="success_msg_position_bottom" value="bottom" <?Php checked( $newarr['arfsuccessmsgposition'], 'bottom' ); ?> /><?php echo addslashes( esc_html__( 'Bottom', 'arforms-form-builder' ) ); //phpcs:ignore ?>
															</label>
															<label class="arf_toggle_btn <?php echo ( $newarr['arfsuccessmsgposition'] == 'top' ) ? 'arf_success' : ''; ?>">
																<input type="radio" name="arfsuccessmsgposition" class="visuallyhidden" id="success_msg_position_top" value="top" <?Php checked( $newarr['arfsuccessmsgposition'], 'top' ); ?> /><?php echo addslashes( esc_html__( 'Top', 'arforms-form-builder' ) ); //phpcs:ignore ?>
															</label>
														</div>
													</div>
												</div>
											<!-- </div> -->
										<div class="arf_accordion_container_row_separator"></div>
										<div class="arf_accordion_container_row arf_padding">
											<div class='arf_accordion_outer_title'><?php echo esc_html__( 'Validation Message Style', 'arforms-form-builder' ); ?></div>
											<div class="arf_accordion_container_row_container">
												<div class="arf_accordion_inner_title"><?php echo esc_html__( 'Type', 'arforms-form-builder' ); ?></div>
											</div>
										    <div class='arf_accordion_container_row_container'>
												<div class="arf_accordion_content_container arf_align_right arf_right arfinputstyledrpdwn_container">
													<div class="arf_toggle_button_group arf_two_button_group">
														<?php $newarr['arferrorstyle'] = isset( $newarr['arferrorstyle'] ) ? $newarr['arferrorstyle'] : 'normal'; ?>
														<label class="arf_toggle_btn <?php echo ( $newarr['arferrorstyle'] == 'normal' ) ? 'arf_success' : ''; ?>"><input type="radio" name="arfest" class="visuallyhidden" id="arfest1" value="normal" <?php checked( $newarr['arferrorstyle'], 'normal' ); ?> /><?php echo esc_html__( 'Standard', 'arforms-form-builder' ); ?></label>
														<label class="arf_toggle_btn <?php echo ( $newarr['arferrorstyle'] == 'advance' ) ? 'arf_success' : ''; ?>"><input type="radio" name="arfest" class="visuallyhidden" id="arfest2" value="advance" <?php checked( $newarr['arferrorstyle'], 'advance' ); ?> /><?php echo esc_html__( 'Modern', 'arforms-form-builder' ); ?></label>
													</div>
												</div>
											</div>
											<div class="arf_accordion_container_row_container">
												<div class="arf_accordion_inner_title" ><?php echo esc_html__( 'Position', 'arforms-form-builder' ); ?></div>
											</div>
											<div class="arf_accordion_container_row_container_left" id="arf_validation_message_style_position" style="<?php echo ( $newarr['arferrorstyle'] == 'normal' ) ? 'display: none;' : ''; ?>">
												<div class="arf_toggle_button_group arf_four_button_group">
													<?php $newarr['arferrorstyleposition'] = isset( $newarr['arferrorstyleposition'] ) ? $newarr['arferrorstyleposition'] : 'right'; ?>
													<label class="arf_toggle_btn <?php echo ( $newarr['arferrorstyleposition'] == 'right' ) ? 'arf_success' : ''; ?>"><input type="radio" name="arfestbc" class="visuallyhidden" data-id="arfestbc2" value="right" <?php checked( $newarr['arferrorstyleposition'], 'right' ); ?> /><?php echo esc_html__( 'Right', 'arforms-form-builder' ); ?></label>
													<label class="arf_toggle_btn <?php echo ( $newarr['arferrorstyleposition'] == 'left' ) ? 'arf_success' : ''; ?>"><input type="radio" name="arfestbc" class="visuallyhidden" data-id="arfestbc2" value="left" <?php checked( $newarr['arferrorstyleposition'], 'left' ); ?> /><?php echo esc_html__( 'Left', 'arforms-form-builder' ); ?></label>
													<label class="arf_toggle_btn <?php echo ( $newarr['arferrorstyleposition'] == 'bottom' ) ? 'arf_success' : ''; ?>"><input type="radio" name="arfestbc" class="visuallyhidden" data-id="arfestbc2" value="bottom" <?php checked( $newarr['arferrorstyleposition'], 'bottom' ); ?> /><?php echo esc_html__( 'Bottom', 'arforms-form-builder' ); ?></label>
													<label class="arf_toggle_btn <?php echo ( $newarr['arferrorstyleposition'] == 'top' ) ? 'arf_success' : ''; ?>"><input type='radio' name='arfestbc' class='visuallyhidden' id='arfestbc1' value='top' <?php checked( $newarr['arferrorstyleposition'], 'top' ); ?> /><?php echo esc_html__( 'Top', 'arforms-form-builder' ); ?></label>
												</div>
											</div>

											<!-- <div class="arf_accordion_container_row_container" id="arf_standard_validation_message_style_position" style="<?php echo ( $newarr['arferrorstyle'] == 'advance' ) ? 'display: none;' : ''; ?>">
												<div class="arf_accordion_inner_title"><?php echo esc_html__( 'Position', 'arforms-form-builder' ); ?></div>
											</div> -->
											<div id="arf_standard_validation_message_style_position" class="arf_accordion_container_row_container_left arf_msg_style_position" style="<?php echo ( $newarr['arferrorstyle'] == 'advance' ) ? 'display: none;' : ''; ?>">
												<div class="arf_toggle_button_group arf_four_button_group">

													<?php $newarr['arfstandarderrposition'] = isset( $newarr['arfstandarderrposition'] ) ? $newarr['arfstandarderrposition'] : 'relative'; ?>

													<label class="arf_toggle_btn <?php echo ( $newarr['arfstandarderrposition'] == 'absolute' ) ? 'arf_success' : ''; ?>">
														<input type="radio" name="arfstndrerr" class="visuallyhidden" data-id="arfstndrerr2" value="absolute" <?php checked( $newarr['arfstandarderrposition'], 'absolute' ); ?> /><?php echo esc_html__( 'Absolute', 'arforms-form-builder' ); ?>
													</label>

													<label class="arf_toggle_btn <?php echo ( $newarr['arfstandarderrposition'] == 'relative' ) ? 'arf_success' : ''; ?>">
														<input type="radio" name="arfstndrerr" class="visuallyhidden" data-id="arfstndrerr2" value="relative" <?php checked( $newarr['arfstandarderrposition'], 'relative' ); ?> /><?php echo esc_html__( 'Relative', 'arforms-form-builder' ); ?>
													</label>
												</div>
											</div>
										</div>
									</div>
								</dd>
							</dl>

							<dl class="arf_accordion_tab_form_settings">
								<dd>
									<a href="javascript:void(0)" data-target="arf_accordion_tab_form_settings"><?php echo esc_html__( 'Advanced Form Options', 'arforms-form-builder' ); ?></a>
									<div class="arf_accordion_container">
										<div class="arf_accordion_container_row arf_padding">
											<div class="arf_accordion_outer_title"><?php echo esc_html__( 'Form Title options', 'arforms-form-builder' ); ?></div>
										</div>
										<div class="arf_accordion_container_row_container">
											<div class="arf_accordion_inner_container"><?php echo esc_html__( 'Display Title and Description', 'arforms-form-builder' ); ?></div>
											<div class="arf_accordion_inner_container">
												<div class="arf_float_right arfmarginright4">
													<label class="arf_js_switch_label">
														<span><?php echo esc_html__( 'No', 'arforms-form-builder' ); ?>&nbsp;</span>
													</label>
													<span class="arf_js_switch_wrapper">
														<input type="checkbox" class="js-switch" name="options[display_title_form]" id="display_title_form" <?php echo ( isset( $values_nw['display_title_form'] ) && $values_nw['display_title_form'] == '1' ) ? 'checked="checked"' : ''; ?> onchange="arflite_change_form_title();" value="<?php echo isset( $values_nw['display_title_form'] ) ? esc_attr( $values_nw['display_title_form'] ) : ''; ?>" />
														<span class="arf_js_switch"></span>
													</span>
													<label class="arf_js_switch_label">
														<span>&nbsp;<?php echo esc_html__( 'Yes', 'arforms-form-builder' ); ?></span>
													</label>
												</div>
												<input type="hidden" id="temp_display_title_form" value="1" />
											</div>
										</div>
										<div class="arf_accordion_container_row_container">
											<div class="arf_accordion_inner_title"><?php echo esc_html__( 'Title Alignment', 'arforms-form-builder' ); ?></div>
										</div>
										<div class="arf_accordion_container_row_container_left" style="margin-left: -4px;">
											<div class="arfinputstyledrpdwn_container">
												<div class="arf_toggle_button_group arf_three_button_group margin-right_5px">
													<?php

													$newarr['arfformtitlealign'] = isset( $newarr['arfformtitlealign'] ) ? $newarr['arfformtitlealign'] : 'center';
													?>
													<label class="arf_toggle_btn <?php echo ( $newarr['arfformtitlealign'] == 'right' ) ? 'arf_success' : ''; ?>"><input  class="visuallyhidden" type="radio" name="arffta" value="right" data-arfstyle="true" data-arfstyledata='{"standard":".arflite_main_div_{arf_form_id} .arf_fieldset .formtitle_style~|~text-align||.arflite_main_div_{arf_form_id} .arf_fieldset .formdescription_style~|~text-align","material":".arflite_main_div_{arf_form_id}  .arf_fieldset .formtitle_style~|~text-align||.arflite_main_div_{arf_form_id} .arf_fieldset .formdescription_style~|~text-align"}' data-arfstyleappend="true" data-arfstyleappendid="arf_{arf_form_id}_text_align" <?php checked( $newarr['arfformtitlealign'], 'right' ); ?> /><?php echo esc_html__( 'Right', 'arforms-form-builder' ); ?></label>
													<label class="arf_toggle_btn <?php echo ( $newarr['arfformtitlealign'] == 'center' ) ? 'arf_success' : ''; ?>"><input  class="visuallyhidden" type="radio" name="arffta" value="center" data-arfstyle="true" data-arfstyledata='{"standard":".arflite_main_div_{arf_form_id} .arf_fieldset .formtitle_style~|~text-align||.arflite_main_div_{arf_form_id} .arf_fieldset .formdescription_style~|~text-align","material":".arflite_main_div_{arf_form_id} .arf_fieldset .formtitle_style~|~text-align||.arflite_main_div_{arf_form_id} .arf_fieldset .formdescription_style~|~text-align"}' data-arfstyleappend="true" data-arfstyleappendid="arf_{arf_form_id}_text_align" <?php checked( $newarr['arfformtitlealign'], 'center' ); ?> /><?php echo esc_html__( 'Center', 'arforms-form-builder' ); ?></label>
													<label class="arf_toggle_btn <?php echo ( $newarr['arfformtitlealign'] == 'left' ) ? 'arf_success' : ''; ?>"><input  class="visuallyhidden" type="radio" name="arffta" value="left" data-arfstyle="true" data-arfstyledata='{"standard":".arflite_main_div_{arf_form_id} .arf_fieldset .formtitle_style~|~text-align||.arflite_main_div_{arf_form_id} .arf_fieldset .formdescription_style~|~text-align","material":".arflite_main_div_{arf_form_id} .arf_fieldset .formtitle_style~|~text-align||.arflite_main_div_{arf_form_id} .arf_fieldset .formdescription_style~|~text-align"}' data-arfstyleappend="true" data-arfstyleappendid="arf_{arf_form_id}_text_align" <?php checked( $newarr['arfformtitlealign'], 'left' ); ?> /><?php echo esc_html__( 'Left', 'arforms-form-builder' ); ?></label>
												</div>
											</div>
										</div>
										<div class="arf_accordion_container_row_container">
											<div class="arf_accordion_inner_title arf_two_row_text arf_form_padding"><?php echo esc_html__( 'Margin', 'arforms-form-builder' ); ?></div>
										</div>
										<div class="arf_accordion_container_row_container_left">
											<div class="arf_accordion_content_container arf_align_center arf_form_container arf_right arfformmarginvals arfinputstyledrpdwn_container margin-right_5px">
												<span class="arfpxspan arfformarginvalpx">px</span>
												<div class="arf_form_margin_box_wrapper"><input type="text" name="arfformtitlepaddingsetting_1" id="arfformtitlepaddingsetting_1" onkeypress="return arflite_check_numeric_input(event,this)" onpaste="arflite_validatepaste(this, event)" value="<?php echo esc_attr( $newarr['arfmainformtitlepaddingsetting_1'] ); ?>" data-arfstyle="true" data-arfstyledata='{"standard":".arflite_main_div_{arf_form_id} .allfields .arftitlediv~|~margin-top||.arflite_main_div_{arf_form_id} .arftitlecontainer~|~margin-top","material":".arflite_main_div_{arf_form_id} .allfields .arftitlediv~|~margin-top"}' class="arf_form_margin_box" data-arfstyleappend="true" data-arfstyleappendid="arf_{arf_form_id}_form_margin" /><br /><span class="arf_px arf_font_size arfformmarginleft"><?php echo esc_html__( 'Top', 'arforms-form-builder' ); ?></span></div>
												<div class="arf_form_margin_box_wrapper"><input type="text" name="arfformtitlepaddingsetting_2" id="arfformtitlepaddingsetting_2" onkeypress="return arflite_check_numeric_input(event,this)" onpaste="arflite_validatepaste(this, event)" value="<?php echo esc_attr( $newarr['arfmainformtitlepaddingsetting_2'] ); ?>" data-arfstyle="true" data-arfstyledata='{"standard":".arflite_main_div_{arf_form_id} .allfields .arftitlediv~|~margin-right","material":".arflite_main_div_{arf_form_id} .allfields .arftitlediv~|~margin-right"}' class="arf_form_margin_box" data-arfstyleappend="true" data-arfstyleappendid="arf_{arf_form_id}_form_margin" /><br /><span class="arf_px arf_font_size arfformmarginleft"><?php echo esc_html__( 'Right', 'arforms-form-builder' ); ?></span></div>
												<div class="arf_form_margin_box_wrapper"><input type="text" name="arfformtitlepaddingsetting_3" id="arfformtitlepaddingsetting_3" onkeypress="return arflite_check_numeric_input(event,this)" onpaste="arflite_validatepaste(this, event)" value="<?php echo esc_attr( $newarr['arfmainformtitlepaddingsetting_3'] ); ?>" data-arfstyle="true" data-arfstyledata='{"standard":".arflite_main_div_{arf_form_id} .allfields .arftitlediv~|~margin-bottom||.arflite_main_div_{arf_form_id} .arftitlecontainer~|~margin-bottom","material":".arflite_main_div_{arf_form_id} .allfields .arftitlediv~|~margin-bottom"}' class="arf_form_margin_box" data-arfstyleappend="true" data-arfstyleappendid="arf_{arf_form_id}_form_margin" /><br /><span class="arf_px arf_font_size arfformmarginleft"><?php echo esc_html__( 'Bottom', 'arforms-form-builder' ); ?></span></div>
												<div class="arf_form_margin_box_wrapper"><input type="text" name="arfformtitlepaddingsetting_4" id="arfformtitlepaddingsetting_4" onkeypress="return arflite_check_numeric_input(event,this)" onpaste="arflite_validatepaste(this, event)" value="<?php echo esc_attr( $newarr['arfmainformtitlepaddingsetting_4'] ); ?>" data-arfstyle="true" data-arfstyledata='{"standard":".arflite_main_div_{arf_form_id} .allfields .arftitlediv~|~margin-left","material":".arflite_main_div_{arf_form_id} .allfields .arftitlediv~|~margin-left"}' class="arf_form_margin_box" data-arfstyleappend="true" data-arfstyleappendid="arf_{arf_form_id}_form_margin" /><br /><span class="arf_px arf_font_size arfformmarginleft"><?php echo esc_html__( 'Left', 'arforms-form-builder' ); ?></span></div>
											</div>
											<?php
											$arfformtitlepaddingsetting_value = '';

											if ( esc_attr( $newarr['arfmainformtitlepaddingsetting_1'] ) != '' ) {
												$arfformtitlepaddingsetting_value .= $newarr['arfmainformtitlepaddingsetting_1'] . 'px ';
											} else {
												$arfformtitlepaddingsetting_value .= '0px ';
											}
											if ( esc_attr( $newarr['arfmainformtitlepaddingsetting_2'] ) != '' ) {
												$arfformtitlepaddingsetting_value .= $newarr['arfmainformtitlepaddingsetting_2'] . 'px ';
											} else {
												$arfformtitlepaddingsetting_value .= '0px ';
											}
											if ( esc_attr( $newarr['arfmainformtitlepaddingsetting_3'] ) != '' ) {
												$arfformtitlepaddingsetting_value .= $newarr['arfmainformtitlepaddingsetting_3'] . 'px ';
											} else {
												$arfformtitlepaddingsetting_value .= '0px ';
											}
											if ( esc_attr( $newarr['arfmainformtitlepaddingsetting_4'] ) != '' ) {
												$arfformtitlepaddingsetting_value .= $newarr['arfmainformtitlepaddingsetting_4'] . 'px';
											} else {
												$arfformtitlepaddingsetting_value .= '0px';
											}
											?>
											<input type="hidden" name="arfftps" id="arfformtitlepaddingsetting" class="txtxbox_widget " value="<?php echo esc_attr( $arfformtitlepaddingsetting_value ); ?>" />
										</div>
										<div class="arf_accordion_container_row_separator"></div>
										<div class="arf_accordion_container_row arf_padding">
											<div class='arf_accordion_outer_title'><?php echo esc_html__( 'Form Settings', 'arforms-form-builder' ); ?></div>
										</div>
										<div class="arf_accordion_container_row_container">
											<div class="arf_accordion_inner_title arf_width_50"><?php echo esc_html__( 'Form Alignment', 'arforms-form-builder' ); ?></div>
										</div>
										<div class="arf_accordion_container_row_container_left">
											<div class="arf_toggle_button_group arf_three_button_group">
												<?php
												$newarr['form_align'] = isset( $newarr['form_align'] ) ? $newarr['form_align'] : 'center';

												?>
												<label class="arf_toggle_btn <?php echo ( $newarr['form_align'] == 'right' ) ? 'arf_success' : ''; ?>"><input type="radio" name="arffa" class="visuallyhidden" data-id="arfestbc2" value="right" <?php checked( $newarr['form_align'], 'right' ); ?> data-arfstyle="true" data-arfstyledata='{"standard":".arf_form.arflite_main_div_{arf_form_id}~|~text-align||.arf_form.arflite_main_div_{arf_form_id} form~|~text-align","material":".arf_form.arflite_main_div_{arf_form_id}~|~text-align||.arf_form.arflite_main_div_{arf_form_id} form~|~text-align"}' data-arfstyleappend="true" data-arfstyleappendid="arf_{arf_form_id}_form_align"
												/><?php echo esc_html__( 'Right', 'arforms-form-builder' ); ?></label>
												<label class="arf_toggle_btn <?php echo ( $newarr['form_align'] == 'center' ) ? 'arf_success' : ''; ?>"><input type="radio" name="arffa" class="visuallyhidden" data-id="arfestbc2" value="center" <?php checked( $newarr['form_align'], 'center' ); ?> data-arfstyle="true" data-arfstyledata='{"standard":".arf_form.arflite_main_div_{arf_form_id}~|~text-align||.arf_form.arflite_main_div_{arf_form_id} form~|~text-align","material":".arf_form.arflite_main_div_{arf_form_id}~|~text-align||.arf_form.arflite_main_div_{arf_form_id} form~|~text-align"}' data-arfstyleappend="true" data-arfstyleappendid="arf_{arf_form_id}_form_align"/><?php echo esc_html__( 'Center', 'arforms-form-builder' ); ?></label>
												<label class="arf_toggle_btn <?php echo ( $newarr['form_align'] == 'left' ) ? 'arf_success' : ''; ?>"><input type="radio" name="arffa" class="visuallyhidden" data-id="arfestbc2" value="left" <?php checked( $newarr['form_align'], 'left' ); ?> data-arfstyle="true" data-arfstyledata='{"standard":".arf_form.arflite_main_div_{arf_form_id}~|~text-align||.arf_form.arflite_main_div_{arf_form_id} form~|~text-align","material":".arf_form.arflite_main_div_{arf_form_id}~|~text-align||.arf_form.arflite_main_div_{arf_form_id} form~|~text-align"}' data-arfstyleappend="true" data-arfstyleappendid="arf_{arf_form_id}_form_align"/><?php echo esc_html__( 'Left', 'arforms-form-builder' ); ?></label>
											</div>
										</div>
										<!-- <div class="arf_accordion_container_row arf_half_width arf_height_auto"> -->
										<div class="arf_accordion_container_row_container_left">
											<div class="arf_accordion_inner_container"><?php echo esc_html__( 'Background Image', 'arforms-form-builder' ); ?></div>
											<div class="arf_accordion_inner_container">
												<div class="arf_imageloader arf_form_style_file_upload_loader" id="ajax_form_loader"></div>
												<div id="form_bg_img_div" style="margin-left:0px;">
													<input type="hidden" name="arfmfbi" onclick="arflite_clear_file_submit();" value="<?php echo esc_attr( $newarr['arfmainform_bg_img'] ); ?>" data-id="arfmainform_bg_img" />
														<?php if ( $newarr['arfmainform_bg_img'] != '' ) { ?>
															<img src="<?php echo esc_url( $newarr['arfmainform_bg_img'] ); ?>" height="35" width="35" style="margin-left:5px;border:1px solid #D5E3FF !important;" />&nbsp;<span onclick="arflite_delete_image('form_image');" style="width:35px;height: 35px;display:inline-block;cursor: pointer;"><svg width="23px" height="27px" viewBox="0 0 30 30"><path xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd" fill="#4786FF" d="M19.002,4.351l0.007,16.986L3.997,21.348L3.992,4.351H1.016V2.38  h1.858h4.131V0.357h8.986V2.38h4.146h1.859l0,0v1.971H19.002z M16.268,4.351H6.745H5.993l0.006,15.003h10.997L17,4.351H16.268z   M12.01,7.346h1.988v9.999H12.01V7.346z M9.013,7.346h1.989v9.999H9.013V7.346z"/></svg></span>
														<?php } else { ?>
															<div class="arfajaxfileupload" style="position: relative; overflow: hidden; cursor: pointer;">
																<div class="arf_form_style_file_upload_icon arf_editor_fileupload_icon">
																	<svg width="16" height="18" viewBox="0 0 18 20" fill="#ffffff"><path xmlns="http://www.w3.org/2000/svg" d="M15.906,18.599h-1h-12h-1h-1v-7h2v5h12v-5h2v7H15.906z M13.157,7.279L9.906,4.028v8.571c0,0.552-0.448,1-1,1c-0.553,0-1-0.448-1-1v-8.54l-3.22,3.22c-0.403,0.403-1.058,0.403-1.46,0 c-0.403-0.403-0.403-1.057,0-1.46l4.932-4.932c0.211-0.211,0.488-0.306,0.764-0.296c0.275-0.01,0.553,0.085,0.764,0.296 l4.932,4.932c0.403,0.403,0.403,1.057,0,1.46S13.561,7.682,13.157,7.279z"/></svg>
																</div>
																<input type="file" name="form_bg_img" id="form_bg_img" data-val="form_bg" class="original" style="position: absolute; cursor: pointer; top: 0px; padding:0; margin:0; height:100%; width:100%; right:0; z-index: 100; opacity: 0; filter:alpha(opacity=0);" />
															</div>


															<input type="hidden" name="imagename_form" id="imagename_form" value="" />
															<input type="hidden" name="arfmfbi" onclick="arflite_clear_file_submit();" value="" data-id="arfmainform_bg_img" />

														<?php } ?>
												</div>
											</div>
										</div>

										<?php
											$arf_bg_position_style_x        = '';
											$arf_bg_position_height_style_x = '';
											$arf_bg_position_style_y        = '';
											$arf_bg_position_height_style_y = '';

										if ( ( isset( $newarr['arf_bg_position_x'] ) && $newarr['arf_bg_position_x'] == 'px' ) && ( isset( $newarr['arf_bg_position_input_x'] ) && $newarr['arf_bg_position_input_x'] != '' ) ) {
											$arf_bg_position_style_x        = 'display: block;';
											$arf_bg_position_height_style_x = 'arf_bg_position_active_height';
										} else {
											$arf_bg_position_style_x        = 'display: none;';
											$arf_bg_position_height_style_x = 'arf_bg_position_inactive_height';
										}

										if ( ( isset( $newarr['arf_bg_position_y'] ) && $newarr['arf_bg_position_y'] == 'px' ) && ( isset( $newarr['arf_bg_position_input_y'] ) && $newarr['arf_bg_position_input_y'] != '' ) ) {
											$arf_bg_position_style_y        = 'display: block;';
											$arf_bg_position_height_style_y = 'arf_bg_position_active_height';
										} else {
											$arf_bg_position_style_y        = 'display: none;';
											$arf_bg_position_height_style_y = 'arf_bg_position_inactive_height';
										}
										?>

										<div class="arf_accordion_container_row_container <?php echo esc_attr( $arf_bg_position_height_style_x ); ?>" id="arf_bg_img_position">

											<div class="arf_accordion_inner_container"><?php echo esc_html__( 'Background Image Position-X', 'arforms-form-builder' ); ?></div>
											<div class="arf_accordion_inner_container">
												<?php
													$bg_position_selected_x = '';
												if ( isset( $newarr['arf_bg_position_x'] ) && $newarr['arf_bg_position_x'] != '' ) {
														$bg_position_selected_x = $newarr['arf_bg_position_x'];
												} else {
													$bg_position_selected_x = 'left';
												}
												?>
												<div class="arf_dropdown_wrapper" style="width: 85%;">
													<?php

														$bg_position_opt = array(
															'center' => 'center',
															'left' => 'left',
															'right' => 'right',
															'px' => 'px',
														);

														$bg_position_attr = array(
															'onchange' => 'arflite_update_form_bg_position(this,"x", "arf_form_bg_position_input_div_x", "arf_fieldset_' . esc_js( $arflite_id ) . '")',
														);

														echo $arflitemaincontroller->arflite_selectpicker_dom( 'arf_bg_position_x', 'arf_bg_position_x', 'arf_bg_position_selectpicker','', $bg_position_selected_x, $bg_position_attr, $bg_position_opt ); //phpcs:ignore
														?>
												</div>
											</div>
											<span class="arf_px arf_font_size arfpxspan arf_bg_image_position"><?php echo addslashes( esc_html__( 'X-axis', 'arforms-form-builder' ) ); //phpcs:ignore ?></span>
										</div>

										<!-- <div class="arf_form_bg_position_input_container"> -->
										<div class="arf_accordion_container_row_container">
											<div class="arf_form_bg_position_input_div" id="arf_form_bg_position_input_div_x" style="<?php echo esc_attr( $arf_bg_position_style_x ); ?>">
												<span class="arf_px arf_font_size arfpxspan arf_margin-right0 arf_bg_x_input_txt"><?php echo esc_html__( 'X-axis', 'arforms-form-builder' ); ?></span>
												<input type="text" name="arf_bg_position_input_x" id="arf_form_bg_position_input_x" value="<?php echo ( isset( $newarr['arf_bg_position_input_x'] ) && $newarr['arf_bg_position_input_x'] != '' ) ? esc_attr( $newarr['arf_bg_position_input_x'] ) : ''; ?>" class="arf_form_bg_position_input arf_frm_editor_input_cls" onfocusout="arflite_set_form_bg_position(this, 'x', 'arf_fieldset_<?php echo esc_js( $arflite_id ); ?>')">
											</div>
										</div>

										<div class="arf_accordion_container_row_container <?php echo esc_attr( $arf_bg_position_height_style_y ); ?>">
											<div class="arf_accordion_inner_container"><?php echo esc_html__( 'Background Image Position-Y', 'arforms-form-builder' ); ?></div>

											<div class="arf_accordion_inner_container">
												<div class="arf_dropdown_wrapper" style="width: 85%;">
													<?php
														$bg_position_selected_y = '';
													if ( isset( $newarr['arf_bg_position_y'] ) && $newarr['arf_bg_position_y'] != '' ) {
															$bg_position_selected_y = $newarr['arf_bg_position_y'];
													} else {
														$bg_position_selected_y = 'top';
													}

														$bg_position_opt = array(
															'center' => 'center',
															'top' => 'top',
															'bottom' => 'bottom',
															'px' => 'px',
														);

														$bg_position_attr = array(
															'onchange' => 'arflite_update_form_bg_position(this,"y", "arf_form_bg_position_input_div_y", "arf_fieldset_' . esc_js( $arflite_id ) . '");',
														);

														echo $arflitemaincontroller->arflite_selectpicker_dom( 'arf_bg_position_y', 'arf_bg_position_y', 'arf_bg_position_selectpicker', '', $bg_position_selected_y, $bg_position_attr, $bg_position_opt ); //phpcs:ignore
														?>
												</div>
											</div>
											<span class="arf_px arf_font_size arfpxspan arf_bg_image_position" style="margin-right:0;position: relative;"><?php echo addslashes( esc_html__( 'Y-axis', 'arforms-form-builder' ) ); //phpcs:ignore ?></span>
										</div>
										<div class="arf_accordion_container_row_container">
											<div class="arf_form_bg_position_input_div" id="arf_form_bg_position_input_div_y" style="<?php echo esc_attr( $arf_bg_position_style_y ); ?>">
												<span class="arf_px arf_font_size arfpxspan arf_margin-right0 arf_bg_x_input_txt"><?php echo esc_html__( 'Y-axis', 'arforms-form-builder' ); ?></span>
												<input type="text" name="arf_bg_position_input_y" id="arf_form_bg_position_input_y" value="<?php echo ( isset( $newarr['arf_bg_position_input_y'] ) && $newarr['arf_bg_position_input_y'] != '' ) ? esc_attr( $newarr['arf_bg_position_input_y'] ) : ''; ?>" class="arf_form_bg_position_input arf_frm_editor_input_cls" onfocusout="arflite_set_form_bg_position(this, 'y', 'arf_fieldset_<?php echo esc_js( $arflite_id ); ?>')">
											</div>
										</div>


										<div class="arf_accordion_container_row_container">
											<div class="arf_accordion_inner_container arf_form_padding arf_two_row_text" style="width:100% !important; line-height: 30px;"><?php echo esc_html__( 'Form Padding', 'arforms-form-builder' ); ?> </div>
											<div class="arf_accordion_inner_container">
												<div class="arf_dropdown_wrapper">
                                                    <?php  $newarr['arfwidthpadding'] = !empty($newarr['arfwidthpadding']) ? $newarr['arfwidthpadding'] : 'Desktop'; ?>
                                                    <?php
                                                        $form_padding_unit_style = array(
                                                            "Desktop" => "<i class='fas fa-desktop'></i>",
                                                            "Tablet" => "<i class='fas fa-tablet-alt'></i>",
                                                            "Mobile" => "<i class='fas fa-mobile-alt'></i>",
                                                        );
														
														$arflite_padding_attr = array(
                                                            'onchange' => 'arflite_change_form_padding(this.value);'
                                                        );
														 

                                                        echo $arflitemaincontroller->arflite_selectpicker_dom( 'arf_frm_padding', 'arf_frm_padding', 'arf_fm_padding_dropdown_cls', 'width: 70px;', $newarr["arfwidthpadding"],$arflite_padding_attr, $form_padding_unit_style, false, array(), false, array(), false, array(), false, 'arf_frm_width_icon_cls' ); //phpcs:ignore
                                                    ?>
                                                </div>
											</div>
										</div>
										<?php 
											$newarr['arfmainfieldsetpadding_1_tablet'] = !empty( $newarr['arfmainfieldsetpadding_1_tablet'] ) ? $newarr['arfmainfieldsetpadding_1_tablet'] : '';
                                            $newarr['arfmainfieldsetpadding_2_tablet'] = !empty( $newarr['arfmainfieldsetpadding_2_tablet'] ) ? $newarr['arfmainfieldsetpadding_2_tablet'] : '';
                                            $newarr['arfmainfieldsetpadding_3_tablet'] = !empty( $newarr['arfmainfieldsetpadding_3_tablet'] ) ? $newarr['arfmainfieldsetpadding_3_tablet'] : '';
                                            $newarr['arfmainfieldsetpadding_4_tablet'] = !empty( $newarr['arfmainfieldsetpadding_4_tablet'] ) ? $newarr['arfmainfieldsetpadding_4_tablet'] : '';
                                            $newarr['arfmainfieldsetpadding_1_mobile'] = !empty( $newarr['arfmainfieldsetpadding_1_mobile'] ) ? $newarr['arfmainfieldsetpadding_1_mobile'] : '';
                                            $newarr['arfmainfieldsetpadding_2_mobile'] = !empty( $newarr['arfmainfieldsetpadding_2_mobile'] ) ? $newarr['arfmainfieldsetpadding_2_mobile'] : '';
                                            $newarr['arfmainfieldsetpadding_3_mobile'] = !empty( $newarr['arfmainfieldsetpadding_3_mobile'] ) ? $newarr['arfmainfieldsetpadding_3_mobile'] : '';
                                            $newarr['arfmainfieldsetpadding_4_mobile'] = !empty( $newarr['arfmainfieldsetpadding_4_mobile'] ) ? $newarr['arfmainfieldsetpadding_4_mobile'] : '';
										?>
											<div class="arf_accordion_content_container arf_frm_padding_options arf_align_center arf_form_container arflite_form_padding_cls" id="arf_padding_desktop" style="<?php echo (($newarr['arfwidthbtn'] == 'Desktop')) ? 'display:block;' : 'display:none;';?>">

												<div class="arf_form_padding_box_wrapper"><input type="text" name="arfmainfieldsetpadding_1" id="arfmainfieldsetpadding_1" onkeypress="return arflite_check_numeric_input(event,this)" onpaste="arflite_validatepaste(this, event)" data-arfstyle="true" data-arfstyledata='{"standard":".arflite_main_div_{arf_form_id} .arf_fieldset~|~padding-top","material":".arflite_main_div_{arf_form_id} .arf_fieldset~|~padding-top"}' value="<?php echo esc_attr( $newarr['arfmainfieldsetpadding_1'] ); ?>" class="arf_form_padding_box" data-arfstyleappend="true" data-arfstyleappendid="arf_{arf_form_id}_form_padding" /><br /><span class="arf_px arf_font_size margin-left-10px"><?php echo esc_html__( 'Top', 'arforms-form-builder' ); ?></span></div>

												<div class="arf_form_padding_box_wrapper"><input type="text" name="arfmainfieldsetpadding_2" id="arfmainfieldsetpadding_2" onkeypress="return arflite_check_numeric_input(event,this)" onpaste="arflite_validatepaste(this, event)" value="<?php echo esc_attr( $newarr['arfmainfieldsetpadding_2'] ); ?>" data-arfstyle="true" data-arfstyledata='{"standard":".arflite_main_div_{arf_form_id} .arf_fieldset~|~padding-right","material":".arflite_main_div_{arf_form_id} .arf_fieldset~|~padding-right"}' class="arf_form_padding_box" data-arfstyleappend="true" data-arfstyleappendid="arf_{arf_form_id}_form_padding" /><br /><span class="arf_px arf_font_size margin-left-10px"><?php echo esc_html__( 'Right', 'arforms-form-builder' ); ?></span></div>
												<div class="arf_form_padding_box_wrapper"><input type="text" name="arfmainfieldsetpadding_3" id="arfmainfieldsetpadding_3" onkeypress="return arflite_check_numeric_input(event,this)" onpaste="arflite_validatepaste(this, event)" value="<?php echo esc_attr( $newarr['arfmainfieldsetpadding_3'] ); ?>" data-arfstyle="true" data-arfstyledata='{"standard":".arflite_main_div_{arf_form_id} .arf_fieldset~|~padding-bottom","material":".arflite_main_div_{arf_form_id} .arf_fieldset~|~padding-bottom"}' class="arf_form_padding_box" data-arfstyleappend="true" data-arfstyleappendid="arf_{arf_form_id}_form_padding" /><br /><span class="arf_px arf_font_size margin-left-10px"><?php echo esc_html__( 'Bottom', 'arforms-form-builder' ); ?></span></div>
												<div class="arf_form_padding_box_wrapper"><input type="text" name="arfmainfieldsetpadding_4" id="arfmainfieldsetpadding_4" onkeypress="return arflite_check_numeric_input(event,this)" onpaste="arflite_validatepaste(this, event)" value="<?php echo esc_attr( $newarr['arfmainfieldsetpadding_4'] ); ?>" data-arfstyle="true" data-arfstyledata='{"standard":".arflite_main_div_{arf_form_id} .arf_fieldset~|~padding-left||.arflite_main_div_{arf_form_id} .arf_inner_wrapper_sortable.arfmainformfield.ui-sortable-helper~|~left","material":".arflite_main_div_{arf_form_id} .arf_fieldset~|~padding-left||.arflite_main_div_{arf_form_id} .arf_inner_wrapper_sortable.arfmainformfield.ui-sortable-helper~|~left"}' class="arf_form_padding_box" data-arfstyleappend="true" data-arfstyleappendid="arf_{arf_form_id}_form_padding"data-arfstyleappend="true" data-arfstyleappendid="arf_{arf_form_id}_form_padding" /><br /><span class="arf_px arf_font_size margin-left-10px"><?php echo esc_html__( 'Left', 'arforms-form-builder' ); ?></span></div>

												<?php
												$arfmainfieldsetpadding_value = '';

												if ( esc_attr( $newarr['arfmainfieldsetpadding_1'] ) != '' ) {
													$arfmainfieldsetpadding_value .= $newarr['arfmainfieldsetpadding_1'] . 'px ';
												} else {
													$arfmainfieldsetpadding_value .= '0px ';
												}
												if ( esc_attr( $newarr['arfmainfieldsetpadding_2'] ) != '' ) {
													$arfmainfieldsetpadding_value .= $newarr['arfmainfieldsetpadding_2'] . 'px ';
												} else {
													$arfmainfieldsetpadding_value .= '0px ';
												}
												if ( esc_attr( $newarr['arfmainfieldsetpadding_3'] ) != '' ) {
													$arfmainfieldsetpadding_value .= $newarr['arfmainfieldsetpadding_3'] . 'px ';
												} else {
													$arfmainfieldsetpadding_value .= '0px ';
												}
												if ( esc_attr( $newarr['arfmainfieldsetpadding_4'] ) != '' ) {
													$arfmainfieldsetpadding_value .= $newarr['arfmainfieldsetpadding_4'] . 'px';
												} else {
													$arfmainfieldsetpadding_value .= '0px';
												}
												?>
												<input type="hidden" name="arfmfsp" id="arfmainfieldsetpadding" class="txtxbox_widget arf_float_right" value="<?php echo esc_attr( $arfmainfieldsetpadding_value ); ?>" size="4" />
											</div>
											<!-- Tablet padding style -->
                                            <div class="arf_accordion_content_container arf_frm_padding_options arf_align_center arf_form_container arflite_form_padding_cls" id="arf_padding_tablet" style="<?php echo (($newarr['arfwidthbtn'] == 'Tablet')) ? 'display:block;' : 'display:none;';?>">

                                                <div class="arf_form_padding_box_wrapper"><input type="text" name="arfmainfieldsetpadding_1_tablet" id="arfmainfieldsetpadding_1_tablet" onkeypress="return arflite_check_numeric_input(event,this)" onpaste="arflite_validatepaste(this, event)" value="<?php echo esc_attr($newarr['arfmainfieldsetpadding_1_tablet']); ?>" class="arf_form_padding_box_tablet" /><br /><span class="arf_px arf_font_size" style="margin-left:-10px;"><?php echo addslashes(esc_html__('Top', 'arforms-form-builder')); //phpcs:ignore ?></span></div>

                                                <div class="arf_form_padding_box_wrapper"><input type="text" name="arfmainfieldsetpadding_2_tablet" id="arfmainfieldsetpadding_2_tablet" onkeypress="return arflite_check_numeric_input(event,this)" onpaste="arflite_validatepaste(this, event)" value="<?php echo esc_attr($newarr['arfmainfieldsetpadding_2_tablet']); ?>"  class="arf_form_padding_box_tablet" /><br /><span class="arf_px arf_font_size" style="margin-left:-10px;"><?php echo addslashes(esc_html__('Right', 'arforms-form-builder')); //phpcs:ignore ?></span></div>

                                                <div class="arf_form_padding_box_wrapper"><input type="text" name="arfmainfieldsetpadding_3_tablet" id="arfmainfieldsetpadding_3_tablet" onkeypress="return arflite_check_numeric_input(event,this)" onpaste="arflite_validatepaste(this, event)" value="<?php echo esc_attr($newarr['arfmainfieldsetpadding_3_tablet']); ?>" class="arf_form_padding_box_tablet" /><br /><span class="arf_px arf_font_size" style="margin-left:-10px;"><?php echo addslashes(esc_html__('Bottom', 'arforms-form-builder')); //phpcs:ignore ?></span></div>

                                                <div class="arf_form_padding_box_wrapper"><input type="text" name="arfmainfieldsetpadding_4_tablet" id="arfmainfieldsetpadding_4_tablet" onkeypress="return arflite_check_numeric_input(event,this)" onpaste="arflite_validatepaste(this, event)" value="<?php echo esc_attr($newarr['arfmainfieldsetpadding_4_tablet']); ?>" class="arf_form_padding_box_tablet"/><br /><span class="arf_px arf_font_size" style="margin-left:-10px;"><?php echo addslashes(esc_html__('Left', 'arforms-form-builder')); //phpcs:ignore ?></span></div>

                                                <?php
                                                $arfmainfieldsetpadding_value_tablet = '';

                                                if (esc_attr($newarr['arfmainfieldsetpadding_1_tablet']) != '') {
                                                    $arfmainfieldsetpadding_value_tablet .= $newarr['arfmainfieldsetpadding_1_tablet'] . 'px ';
                                                } 

                                                if (esc_attr($newarr['arfmainfieldsetpadding_2_tablet']) != '') {
                                                    $arfmainfieldsetpadding_value_tablet .= $newarr['arfmainfieldsetpadding_2_tablet'] . 'px ';
                                                } 

                                                if (esc_attr($newarr['arfmainfieldsetpadding_3_tablet']) != '') {
                                                    $arfmainfieldsetpadding_value_tablet .= $newarr['arfmainfieldsetpadding_3_tablet'] . 'px ';
                                                } 

                                                if (esc_attr($newarr['arfmainfieldsetpadding_4_tablet']) != '') {
                                                    $arfmainfieldsetpadding_value_tablet .= $newarr['arfmainfieldsetpadding_4_tablet'] . 'px';
                                                }
                                                ?>
                                                <input type="hidden" name="arfmfsp_tablet" style="width:160px;" id="arfmainfieldsetpadding_tablet" class="txtxbox_widget arf_float_right" value="<?php echo esc_html($arfmainfieldsetpadding_value_tablet);  ?>" size="4" />
                                            </div>
                                            <!-- mobile padiing style -->
                                            <div class="arf_accordion_content_container arf_frm_padding_options arf_align_center arf_form_container arflite_form_padding_cls" id="arf_padding_mobile" style="<?php echo (($newarr['arfwidthbtn'] == 'Mobile')) ? 'display:block;' : 'display:none;';?>">
                                                <div class="arf_form_padding_box_wrapper"><input type="text" name="arfmainfieldsetpadding_1_mobile" id="arfmainfieldsetpadding_1_mobile" onkeypress="return arflite_check_numeric_input(event,this)" onpaste="arflite_validatepaste(this, event)" value="<?php echo esc_attr($newarr['arfmainfieldsetpadding_1_mobile']); ?>" class="arf_form_padding_box_mobile" /><br /><span class="arf_px arf_font_size" style="margin-left:-10px;"><?php echo addslashes(esc_html__('Top', 'arforms-form-builder')); //phpcs:ignore ?></span></div>

                                                <div class="arf_form_padding_box_wrapper"><input type="text" name="arfmainfieldsetpadding_2_mobile" id="arfmainfieldsetpadding_2_mobile" onkeypress="return arflite_check_numeric_input(event,this)" onpaste="arflite_validatepaste(this, event)" value="<?php echo esc_attr($newarr['arfmainfieldsetpadding_2_mobile']); ?>"  class="arf_form_padding_box_mobile" /><br /><span class="arf_px arf_font_size" style="margin-left:-10px;"><?php echo addslashes(esc_html__('Right', 'arforms-form-builder')); //phpcs:ignore ?></span></div>

                                                <div class="arf_form_padding_box_wrapper"><input type="text" name="arfmainfieldsetpadding_3_mobile" id="arfmainfieldsetpadding_3_mobile" onkeypress="return arflite_check_numeric_input(event,this)" onpaste="arflite_validatepaste(this, event)" value="<?php echo esc_attr($newarr['arfmainfieldsetpadding_3_mobile']); ?>" class="arf_form_padding_box_mobile" /><br /><span class="arf_px arf_font_size" style="margin-left:-10px;"><?php echo addslashes(esc_html__('Bottom', 'arforms-form-builder')); //phpcs:ignore ?></span></div>

                                                <div class="arf_form_padding_box_wrapper"><input type="text" name="arfmainfieldsetpadding_4_mobile" id="arfmainfieldsetpadding_4_mobile" onkeypress="return arflite_check_numeric_input(event,this)" onpaste="arflite_validatepaste(this, event)" value="<?php echo esc_attr($newarr['arfmainfieldsetpadding_4_mobile']); ?>" class="arf_form_padding_box_mobile"/><br /><span class="arf_px arf_font_size" style="margin-left:-10px;"><?php echo addslashes(esc_html__('Left', 'arforms-form-builder')); //phpcs:ignore ?></span></div>

                                                <?php
                                                $arfmainfieldsetpadding_value_mobile = '';

                                                if (esc_attr($newarr['arfmainfieldsetpadding_1_mobile']) != '') {
                                                    $arfmainfieldsetpadding_value_mobile .= $newarr['arfmainfieldsetpadding_1_mobile'] . 'px ';
                                                } 

                                                if (esc_attr($newarr['arfmainfieldsetpadding_2_mobile']) != '') {
                                                    $arfmainfieldsetpadding_value_mobile .= $newarr['arfmainfieldsetpadding_2_mobile'] . 'px ';
                                                }

                                                if (esc_attr($newarr['arfmainfieldsetpadding_3_mobile']) != '') {
                                                    $arfmainfieldsetpadding_value_mobile .= $newarr['arfmainfieldsetpadding_3_mobile'] . 'px ';
                                                }

                                                if (esc_attr($newarr['arfmainfieldsetpadding_4_mobile']) != '') {
                                                    $arfmainfieldsetpadding_value_mobile .= $newarr['arfmainfieldsetpadding_4_mobile'] . 'px';
                                                }
                                                ?>
                                                <input type="hidden" name="arfmfsp_mobile" style="width:160px;" id="arfmainfieldsetpadding_mobile" class="txtxbox_widget arf_float_right" value="<?php echo esc_html($arfmainfieldsetpadding_value_mobile); ?>" size="4" />
                                            </div>
										
										<div class="arf_accordion_container_row arf_half_width arfdisablediv">
											<div class="arf_accordion_inner_title arf_form_padding arf_two_row_text"><?php echo esc_html__( 'Section Padding', 'arforms-form-builder' ); ?></div>
											<div class="arf_accordion_content_container arf_align_center arf_form_container arflite_section_field_padding">
												
												<div class="arf_section_padding_box_wrapper arf_restricted_control"><input type="text" name="arfsectionpaddingsetting_1" id="arfsectionpaddingsetting_1" onchange="arflite_change_field_padding('arfsectionpaddingsetting');" value="20" class="arf_section_padding_box" disabled="disabled" /><br /><span class="arf_px arf_font_size margin-left-10px"><?php echo esc_html__( 'Top', 'arforms-form-builder' ); ?></span></div>
												<div class="arf_section_padding_box_wrapper arf_restricted_control"><input type="text" name="arfsectionpaddingsetting_2" id="arfsectionpaddingsetting_2" value="0" onchange="arflite_change_field_padding('arfsectionpaddingsetting');" class="arf_section_padding_box" disabled="disabled"/><br /><span class="arf_px arf_font_size margin-left-10px"><?php echo esc_html__( 'Right', 'arforms-form-builder' ); ?></span></div>
												<div class="arf_section_padding_box_wrapper arf_restricted_control"><input type="text" name="arfsectionpaddingsetting_3" id="arfsectionpaddingsetting_3" value="20" onchange="arflite_change_field_padding('arfsectionpaddingsetting');" class="arf_section_padding_box" disabled="disabled"/><br /><span class="arf_px arf_font_size margin-left-10px" ><?php echo esc_html__( 'Bottom', 'arforms-form-builder' ); ?></span></div>
												<div class="arf_section_padding_box_wrapper arf_restricted_control"><input type="text" name="arfsectionpaddingsetting_4" id="arfsectionpaddingsetting_4" value="20" onchange="arflite_change_field_padding('arfsectionpaddingsetting');" class="arf_section_padding_box" disabled="disabled"/><br /><span class="arf_px arf_font_size margin-left-10px"><?php echo esc_html__( 'Left', 'arforms-form-builder' ); ?></span></div>
												<?php
												$arfsectionpaddingsetting_value = '';

												$arfsectionpaddingsetting_value .= '20px ';

												$arfsectionpaddingsetting_value .= '0px ';

												$arfsectionpaddingsetting_value .= '20px ';

												$arfsectionpaddingsetting_value .= '20px';
												?>
												<input type="hidden" name="arfscps" id="arfsectionpaddingsetting" class="txtxbox_widget " value="<?php echo esc_attr( $arfsectionpaddingsetting_value ); ?>" />
											</div>
										</div>
										<div class="arf_accordion_container_row_separator"></div>
										<div class="arf_accordion_container_row arf_padding">
											<div class="arf_accordion_outer_title"><?php echo esc_html__( 'Form Border', 'arforms-form-builder' ); ?></div>
										</div>
										<div class="arf_accordion_container_row_container">
											<div class="arf_accordion_inner_container"><?php echo esc_html__( 'Border Type', 'arforms-form-builder' ); ?></div>
											<div class="arf_accordion_inner_container">
												<div class="arf_toggle_button_group arf_two_button_group margin-right_5px">
													<?php $newarr['form_border_shadow'] = isset( $newarr['form_border_shadow'] ) ? $newarr['form_border_shadow'] : 'shadow'; ?>
													<label class="arf_flat_border_btn arf_flat_border_btn_pdding arf_toggle_btn <?php echo ( $newarr['form_border_shadow'] == 'flat' ) ? 'arf_success' : ''; ?>"><input type="radio" name="arffbs" class="visuallyhidden" value="flat" data-arfstyle="true" data-arfstyledata='{"standard":".arflite_main_div_{arf_form_id} .arf_fieldset~|~box-shadow-none","material":".arflite_main_div_{arf_form_id} .arf_fieldset~|~box-shadow-none"}'  id="arfmainformbordershadow2" data-arfstyleappend="true" data-arfstyleappendid="arf_{arf_form_id}_form_border_type" <?php checked( $newarr['form_border_shadow'], 'flat' ); ?> /><?php echo esc_html__( 'Flat', 'arforms-form-builder' ); ?></label>
													<label class="arf_toggle_btn <?php echo ( $newarr['form_border_shadow'] == 'shadow' ) ? 'arf_success' : ''; ?>"><input type="radio" name="arffbs" class="visuallyhidden" id="arfmainformbordershadow1" value="shadow" data-arfstyle="true" data-arfstyledata='{"standard":".arflite_main_div_{arf_form_id} .arf_fieldset~|~box-shadow","material":".arflite_main_div_{arf_form_id} .arf_fieldset~|~box-shadow"}' <?php checked( $newarr['form_border_shadow'], 'shadow' ); ?> data-arfstyleappend="true" data-arfstyleappendid="arf_{arf_form_id}_form_border_type" /><?php echo esc_html__( 'Shadow', 'arforms-form-builder' ); ?></label>
												</div>
											</div>
										</div>
										<div class="arf_accordion_container_row_container">
											<div class="arf_accordion_inner_containers"><?php echo esc_html__( 'Border Size', 'arforms-form-builder' ); ?></div>
											<div class="arf_accordion_inner_container">
												<div class="arf_slider_wrapper">
													<div id="arflite_bordersize" class="noUi-target noUi-ltr noUi-horizontal noUi-txt-dir-ltr slider-track"></div>
													<input id="arfmainfieldset_exs" class="arf_slider arf_slider_input" data-slider-id='arfmainfieldset_exsSlider' type="text" data-slider-min="0" data-slider-max="50" data-slider-step="1" data-slider-value="<?php echo esc_attr( $newarr['fieldset'] ); ?>" />
													<div class="arf_slider_unit_data">
														<div class="arflite_float_left">0 px</div>
														<div class="arflite_float_right">50 px</div>
													</div>

													<input type="hidden" name="arfmfis" class="txtxbox_widget "  id="arfmainfieldset" data-arfstyle="true" data-arfstyledata='{"standard":".arflite_main_div_{arf_form_id} .arf_fieldset~|~border-width","material":".arflite_main_div_{arf_form_id} .arf_fieldset~|~border-width"}' value="<?php echo esc_attr( $newarr['fieldset'] ); ?>" data-arfstyleappend="true" data-arfstyleappendid="arf_{arf_form_id}_form_border_width" size="4" />
												</div>
											</div>
										</div>
										<div class="arf_accordion_container_row_container">
											<div class="arf_accordion_inner_container"><?php echo esc_html__( 'Border Radius', 'arforms-form-builder' ); ?></div>
											<div class="arf_accordion_inner_container">
												<div class="arf_slider_wrapper">
													 <div id="arflite_borderradius" class="noUi-target noUi-ltr noUi-horizontal noUi-txt-dir-ltr slider-track"></div>
													<input id="arfmainfieldsetradius_exs" class="arf_slider arf_slider_input" data-slider-id='arfmainfieldsetradius_exsSlider' type="text" data-slider-min="0" data-slider-max="100" data-slider-step="1" data-slider-value="<?php echo esc_attr( $newarr['arfmainfieldsetradius'] ); ?>" />
													<div class="arf_slider_unit_data">
														<div class="arflite_float_left">0 px</div>
														<div class="arflite_float_right">100 px</div>
													</div>

													<input type="hidden" name="arfmfsr" class="txtxbox_widget "  id="arfmainfieldsetradius" data-arfstyle="true" data-arfstyledata='{"standard":".arflite_main_div_{arf_form_id} .arf_fieldset~|~border-radius","material":".arflite_main_div_{arf_form_id} .arf_fieldset~|~border-radius"}' value="<?php echo esc_attr( $newarr['arfmainfieldsetradius'] ); ?>" size="4" data-arfstyleappend="true" data-arfstyleappendid="arf_{arf_form_id}_form_border_radius" />
												</div>
											</div>
										</div>
										<div class="arf_accordion_container_row_separator"></div>
										<div class="arf_accordion_container_row arf_padding">
											<div class="arf_accordion_outer_title"><?php echo esc_html__( 'Window Opacity', 'arforms-form-builder' ); ?></div>
										</div>
										<div class="arf_accordion_container_row_container">
											<div class="arf_accordion_inner_container"><?php echo esc_html__( 'Window Opacity', 'arforms-form-builder' ); ?></div>
											<div class="arf_accordion_inner_container arf_accordion_container_mar">
												<div class="arf_slider_wrapper">
													<div id="arflite_window_opacity" class="noUi-target noUi-ltr noUi-horizontal noUi-txt-dir-ltr slider-track"></div>
													<input id="arfmainform_opacity_exs" class="arf_slider arf_slider_input" data-slider-id='arfmainform_opacity_exsSlider' type="text" data-slider-min="0" data-slider-max="10" data-slider-step="1" data-slider-value="<?php echo ( esc_attr( $newarr['arfmainform_opacity'] ) * 10 ); ?>"  />
													<div class="arf_slider_unit_data">
														<div class="arflite_float_left"><?php echo esc_html__( '0', 'arforms-form-builder' ); ?></div>
														<div class="arflite_float_right"><?php echo esc_html__( '1', 'arforms-form-builder' ); ?></div>
													</div>
													<input type="hidden" name="arfmainform_opacity" id="arfmainform_opacity" class="txtxbox_widget " value="<?php echo esc_attr( $newarr['arfmainform_opacity'] ); ?>" />
												</div>
											</div>
										</div>

									</div>
								</dd>
							</dl>
							<dl class="arf_accordion_tab_input_settings">
								<dd>
									<a href="javascript:void(0)" data-target="arf_accordion_tab_input_settings"><?php echo esc_html__( 'Input field Options', 'arforms-form-builder' ); ?></a>
									<div class="arf_accordion_container">
										<div class="arf_accordion_container_row arf_padding">
											<div class="arf_accordion_outer_title"><?php echo esc_html__( 'Label Options', 'arforms-form-builder' ); ?></div>
										</div>
										<div class="arf_accordion_container_row_container">
											<div class="arf_accordion_inner_title"><?php echo esc_html__( 'Label Position', 'arforms-form-builder' ); ?></div>
										</div>
											<div class="arf_accordion_container_row_container_left">
												<?php
													$newarr['position'] = isset( $newarr['position'] ) ? $newarr['position'] : 'top';
													$checked_right      = checked( $newarr['position'], 'right', false );
													$checked_left       = checked( $newarr['position'], 'left', false );
													$checked_top        = checked( $newarr['position'], 'top', false );
													$disabled_right     = $disabled_left = '';
												if ( $newarr['arfinputstyle'] == 'material' ) {
													$disabled_right = $disabled_left = 'arf_disabled_toggle_button';
												} else {
													$disabled_right = $disabled_left = '';
												}
												?>
												<div class="arf_toggle_button_group arf_three_button_group margin-right_5px">
													<label class="arf_toggle_btn arf_label_position arf_right_position 
													<?php
													echo ( $checked_right != '' ) ? 'arf_success' : '';
													echo esc_attr( $disabled_right );
													?>
													" class="padding-7-10"><input type="radio" name="arfmps" class="visuallyhidden" onchange="arflitefrmSetPosClass('right');" <?php echo ( 'material' == $newarr['arfinputstyle'] ) ? ' disabled="disabled" ' : ''; ?> value="right" <?php echo esc_attr( $checked_right ); ?> /><?php echo esc_html__( 'Right', 'arforms-form-builder' ); ?></label>
													<label class="arf_toggle_btn padding-7-10 arf_label_position arf_left_position
													<?php
													echo ( $checked_left != '' ) ? 'arf_success' : '';
													echo esc_attr( $disabled_left );
													?>
													"><input type="radio" name="arfmps" class="visuallyhidden" onchange="arflitefrmSetPosClass('left');" <?php echo ( 'material' == $newarr['arfinputstyle'] ) ? ' disabled="disabled" ' : ''; ?> value="left" <?php checked( esc_html( $newarr['position'] ), 'left' ); ?> /><?php echo esc_html__( 'Left', 'arforms-form-builder' ); ?></label>
													<label class="arf_toggle_btn arf_label_position padding-7-10 arf_top_position <?php echo ( $checked_top != '' ) ? 'arf_success' : ''; ?>"><input type="radio" name="arfmps" class="visuallyhidden" onchange="arflitefrmSetPosClass('top');" value="top" <?php echo esc_attr( $checked_top ); ?> /><?php echo esc_html__( 'Top', 'arforms-form-builder' ); ?></label>
												</div>
											</div>
										<!-- </div> -->
										<div class="arf_accordion_container_row_container">
											<div class="arf_accordion_inner_title"><?php echo esc_html__( 'Label Align', 'arforms-form-builder' ); ?></div>
										</div>
										<div class="arf_accordion_container_row_container_left">
											<div class="arf_toggle_button_group arf_two_button_group margin-right_5px">
												<?php $newarr['align'] = isset( $newarr['align'] ) ? $newarr['align'] : 'right'; ?>
												<label class="arf_toggle_btn label-align-lbl1 <?php echo ( $newarr['align'] == 'right' ) ? 'arf_success' : ''; ?>"><input type="radio" name="arffrma" id="frm_align" class="visuallyhidden" value="right" <?php checked( $newarr['align'], 'right' ); ?> data-arfstyle="true" data-arfstyledata='{"standard":".arflite_main_div_{arf_form_id} label.arf_main_label~|~text-align||.arflite_main_div_{arf_form_id} .sortable_inner_wrapper .arfformfield .fieldname~|~text-align","material":".arflite_main_div_{arf_form_id} .arf_materialize_form  label.arf_main_label~|~text-align||.arflite_main_div_{arf_form_id} .sortable_inner_wrapper .arfformfield .fieldname~|~text-align||.arf_materialize_form .input-field label.arf_main_label:not(.arf_field_option_content_cell_label):not(.arf_js_switch_label)~|~arf_set_right_position||.arf_materialize_form .input-field label.arf_main_label:not(.arf_field_option_content_cell_label):not(.arf_js_switch_label)~|~arf_set_right_position_inherit||.arflite_main_div_{arf_form_id} .arf_materialize_form .arf_material_theme_container_with_icons  label.arf_main_label~|~text-align||.arf_materialize_form .input-field .arf_material_theme_container_with_icons label.arf_main_label:not(.arf_field_option_content_cell_label):not(.arf_js_switch_label)~|~arf_set_right_position||.arf_materialize_form .input-field .arf_material_theme_container_with_icons label.arf_main_label:not(.arf_field_option_content_cell_label):not(.arf_js_switch_label)~|~arf_set_right_position_inherit"}' data-arfstyleappend="true" data-arfstyleappendid="arf_{arf_form_id}_label_text_align"  /><?php echo esc_html__( 'Right', 'arforms-form-builder' ); ?></label>
												<label class="arf_toggle_btn label-align-lbl2 <?php echo ( $newarr['align'] == 'left' ) ? 'arf_success' : ''; ?>"><input type="radio" name="arffrma" id="frm_align_2" class="visuallyhidden" value="left" <?php checked( $newarr['align'], 'left' ); ?> data-arfstyle="true" data-arfstyledata='{"standard":".arflite_main_div_{arf_form_id} label.arf_main_label~|~text-align||.arflite_main_div_{arf_form_id} .sortable_inner_wrapper .arfformfield .fieldname~|~text-align","material":".arflite_main_div_{arf_form_id} .arf_materialize_form label.arf_main_label~|~text-align||.arflite_main_div_{arf_form_id} .sortable_inner_wrapper .arfformfield .fieldname~|~text-align||.arf_materialize_form .input-field label.arf_main_label:not(.arf_field_option_content_cell_label):not(.arf_js_switch_label)~|~arf_set_left_position||.arf_materialize_form .input-field label.arf_main_label:not(.arf_field_option_content_cell_label):not(.arf_js_switch_label)~|~arf_set_left_position_inherit||.arflite_main_div_{arf_form_id} .arf_materialize_form .arf_material_theme_container_with_icons label.arf_main_label~|~text-align||.arf_materialize_form .input-field .arf_material_theme_container_with_icons label.arf_main_label:not(.arf_field_option_content_cell_label):not(.arf_js_switch_label)~|~arf_set_left_position||.arf_materialize_form .input-field .arf_material_theme_container_with_icons label.arf_main_label:not(.arf_field_option_content_cell_label):not(.arf_js_switch_label)~|~arf_set_left_position_inherit"}' data-arfstyleappend="true" data-arfstyleappendid="arf_{arf_form_id}_label_text_align" /><?php echo esc_html__( 'Left', 'arforms-form-builder' ); ?></label>
											</div>
											
										</div>
										<div class="arf_accordion_container_row_container">
											<div class="arf_accordion_inner_container"><?php echo esc_html__( 'Label Width', 'arforms-form-builder' ); ?></div>
											<div class="arf_accordion_inner_container">
												<input type="text" name="arfmws" class="arf_small_width_txtbox arfcolor arffieldwidthinput" id="arfmainformwidthsetting" data-arfstyle="true" data-arfstyledata='{"standard":".arflite_main_div_{arf_form_id} .arf_fieldset label.arf_main_label~|~width","material":".arflite_main_div_{arf_form_id} .arf_fieldset label.arf_main_label~|~width"}' data-arfstyleappend="true" data-arfstyleappendid="arf_{arf_form_id}_label_width" value="<?php echo esc_attr( $newarr['width'] ); ?>" size="5" />
												<input type="hidden" name="arfmwu" id="arfmainwidthunit" value="px"  <?php echo( $newarr['position'] == 'top' ) ? 'disabled="disabled"' : ''; ?>/>
												<span class="arfpxspan arffieldwidthpx arf_frm_px_span">px</span>
											</div>
										</div>
										<div class="arf_accordion_container_row_container">
											<div class="arf_accordion_inner_container"><?php echo esc_html__( 'Hide Label', 'arforms-form-builder' ); ?></div>
											<div class="arf_accordion_inner_container">
												<div class="arf_float_right margin-right_5px">
													<label class="arf_js_switch_label">
														<span class=""><?php echo esc_html__( 'No', 'arforms-form-builder' ); ?>&nbsp;</span>
													</label>
													<span class="arf_js_switch_wrapper">
														<input type="checkbox" class="js-switch" name="arfhl" id="arfhidelabels" value="<?php echo $newarr['hide_labels'] != '' ? esc_attr( $newarr['hide_labels'] ) : 0; ?>" onchange="arflitefrmSetPosClassHide()"  <?php echo ( $newarr['hide_labels'] == '1' ) ? 'checked="checked"' : ''; ?> />
														<span class="arf_js_switch"></span>
													</span>
													<label class="arf_js_switch_label">
														<span class="">&nbsp;<?php echo esc_html__( 'Yes', 'arforms-form-builder' ); ?></span>
													</label>
												</div>
											</div>
										</div>
										<div class="arf_accordion_container_row_separator"></div>
										<div class="arf_accordion_container_row arf_padding">
											<div class="arf_accordion_outer_title"><?php echo esc_html__( 'Input Field Description Options', 'arforms-form-builder' ); ?></div>
										</div>
										<div class="arf_accordion_container_row_container">
											<div class="arf_accordion_inner_container"><?php echo esc_html__( 'Font Size', 'arforms-form-builder' ); ?></div>
											<div class="arf_accordion_inner_container">
												<div class="arf_dropdown_wrapper arflite_margin_right5" style="margin-right: 5px;width: 60px;">
													<?php
														$font_size_opts = array();
													for ( $i = 8; $i <= 20; $i++ ) {
														$font_size_opts[ $i ] = $i;
													}
													for ( $i = 22; $i <= 28; $i = $i + 2 ) {
														$font_size_opts[ $i ] = $i;
													}
													for ( $i = 32; $i <= 40; $i = $i + 4 ) {
														$font_size_opts[ $i ] = $i;
													}

														$font_size_attr = array(
															'data-arfstyle' => 'true',
															'data-arfstyledata' => '{"standard":".arflite_main_div_{arf_form_id} .arf_fieldset .controls .arf_field_description~|~font-size||.arflite_main_div_{arf_form_id} .arftitlediv .arfeditorformdescription input~|~font-size","material":".arflite_main_div_{arf_form_id} .arf_fieldset .controls .arf_field_description~|~font-size||.arflite_main_div_{arf_form_id} .arftitlediv .arfeditorformdescription input~|~font-size"}',
															'data-arfstyleappend' => 'true',
															'data-arfstyleappendid' => 'arf_{arf_form_id}_field_description_font_size',
														);

														echo $arflitemaincontroller->arflite_selectpicker_dom( 'arfdfss', 'arfdescfontsizesetting', '', '', $newarr['arfdescfontsizesetting'], $font_size_attr, $font_size_opts ); //phpcs:ignore
														?>
												</div>
											</div>
										</div>
										<div class="arf_accordion_container_row_container">
											<div class="arf_accordion_inner_container"><?php echo esc_html__( 'Text Alignment', 'arforms-form-builder' ); ?></div>
											<div class="arf_accordion_inner_container">
												<div class="toggle-btn-grp joint-toggle arffieldtextalignment">
													<label onclick="" id="arftextalign" class="toggle-btn arftextalign arf_three_button right
													<?php
													if ( $newarr['arfdescalighsetting'] == 'right' ) {
														echo 'success';
													}
													?>
													"><input type="radio" name="arfdas" data-arfstyle="true" data-arfstyledata='{"standard":".arflite_main_div_{arf_form_id} .arf_fieldset .controls .arf_field_description~|~text-align","material":".arflite_main_div_{arf_form_id} .arf_fieldset .controls .arf_field_description~|~text-align"}' data-arfstyleappend="true" data-arfstyleappendid="arf_{arf_form_id}_field_description_align" class="visuallyhidden" value="right" <?php checked( $newarr['arfdescalighsetting'], 'right' ); ?> /><svg width="24px" height="29px" viewBox="3 0 23 27"><path xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd" fill="#BCC9E0" d="M12.089,24.783v-3h14.125v3H12.089z M12.089,7.783h14.063v3H12.089  V7.783z M1.089,0.784h24.938v2.999H1.089V0.784z M26.027,17.783H1.089v-2.999h24.938V17.783z"/></svg></label>
													<label onclick="" class="toggle-btn arflite_float_right arf_three_button center
													<?php
													if ( $newarr['arfdescalighsetting'] == 'center' ) {
														echo 'success';
													}
													?>
													"><input type="radio" name="arfdas"  class="visuallyhidden" data-arfstyle="true" data-arfstyledata='{"standard":".arflite_main_div_{arf_form_id} .arf_fieldset .controls .arf_field_description~|~text-align","material":".arflite_main_div_{arf_form_id} .arf_fieldset .controls .arf_field_description~|~text-align"}' data-arfstyleappend="true" data-arfstyleappendid="arf_{arf_form_id}_field_description_align" value="center" <?php checked( $newarr['arfdescalighsetting'], 'center' ); ?> /><svg width="24px" height="29px" viewBox="3 0 23 27"><path xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd" fill="#BCC9E0" d="M1.089,17.783v-2.999h24.938v2.999H1.089z M6.089,10.783v-3h14.063  v3H6.089z M1.089,0.784h24.938v2.999H1.089V0.784z M20.214,24.783H6.089v-3h14.125V24.783z"/></svg></label>
													<label onclick="" class="toggle-btn arflite_float_right arf_three_button left
													<?php
													if ( $newarr['arfdescalighsetting'] == 'left' ) {
														echo 'success';
													}
													?>
													" ><input type="radio" name="arfdas" class="visuallyhidden" data-arfstyle="true" data-arfstyledata='{"standard":".arflite_main_div_{arf_form_id} .arf_fieldset .controls .arf_field_description~|~text-align","material":".arflite_main_div_{arf_form_id} .arf_fieldset .controls .arf_field_description~|~text-align"}' data-arfstyleappend="true" data-arfstyleappendid="arf_{arf_form_id}_field_description_align" value="left" <?php checked( $newarr['arfdescalighsetting'], 'left' ); ?> /><svg width="24px" height="29px" viewBox="3 0 23 27"><path xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd" fill="#BCC9E0" d="M1.089,17.783v-2.999h24.938v2.999H1.089z M1.089,0.784h24.938  v2.999H1.089V0.784z M15.152,10.783H1.089v-3h14.063V10.783z M15.214,24.783H1.089v-3h14.125V24.783z"/></svg></label>
												</div>
											</div>
										</div>
										<div class="arf_accordion_container_row_separator"></div>
										<div class="arf_accordion_container_row arf_padding">
											<div class="arf_accordion_outer_title"><?php echo esc_html__( 'Input Field Options', 'arforms-form-builder' ); ?></div>
										</div>
										<div class="arf_accordion_container_row_container">
											<div class="arf_accordion_inner_container" style="width:50% !important; line-height:35px;"><?php echo esc_html__( 'Field Width', 'arforms-form-builder' ); ?>
												<div class="arf_dropdown_wrapper" style="margin-left:10px;">
													<?php  $newarr['arf_field_width'] = !empty($newarr['arf_field_width']) ? $newarr['arf_field_width'] : 'Desktop'; ?>     
													<?php
														$field_width_unit_style = array(
															"Desktop" => "<i class='fas fa-desktop'></i>",
															"Tablet" => "<i class='fas fa-tablet-alt'></i>",
															"Mobile" => "<i class='fas fa-mobile-alt'></i>",
														);

														$arflite_field_width_attr = array(
                                                            'onchange' => 'arflite_field_width_func(this.value);'
                                                        );

														echo $arflitemaincontroller->arflite_selectpicker_dom( 'arf_field_width', 'arf_field_width', '', 'margin-right:17px;', $newarr['arf_field_width'],$arflite_field_width_attr, $field_width_unit_style, false, array(), false, array(), false, array(), false, 'arf_frm_width_icon_cls' ); //phpcs:ignore
													?>
												</div>
											</div>
											<div class="arf_accordion_inner_container arflite-field-width-con"  style="margin-left: -6px; width:50% !important;">
												<input type="text" name="arfmfiws" id="arfmainfieldwidthsetting" onkeypress="return arflite_check_numeric_input(event,this)" onpaste="arflite_validatepaste(this, event)" class="arf_small_width_txtbox arfcolor" data-arfstyle="true" data-arfstyledata='{"standard": ".arflite_main_div_{arf_form_id} .controls~|~width{arf_field_width_unit}","material": ".arflite_main_div_{arf_form_id} .controls,.arflite_main_div_{arf_form_id} .edit_field_type_checkbox .fieldname-row,.arflite_main_div_{arf_form_id} .edit_field_type_radio .fieldname-row~|~width{arf_field_width_unit}"}' data-arfstyleappend="true" data-arfstyleappendid="arf_{arf_form_id}_field_width" value="<?php echo esc_attr( $newarr['field_width'] ); ?>"  size="5"  style="<?php echo (($newarr['arf_field_width'] == 'Desktop')) ? 'display:block;' : 'display:none;';?>"/>

												<input type="text" name="arfmfiws_tablet" onkeypress="return arflite_check_numeric_input(event,this)" onpaste="arflite_validatepaste(this, event)" id="arfmainfieldwidthsetting_tablet" class="arf_small_width_txtbox arfcolor" value="<?php echo esc_attr($newarr['field_width_tablet']) ?>"  size="5" style="<?php echo (($newarr['arf_field_width'] == 'Tablet')) ? 'display:block;' : 'display:none;';?>" />

                                                <input type="text" name="arfmfiws_mobile" onkeypress="return arflite_check_numeric_input(event,this)" onpaste="arflite_validatepaste(this, event)" id="arfmainfieldwidthsetting_mobile" class="arf_small_width_txtbox arfcolor" value="<?php echo esc_attr($newarr['field_width_mobile']) ?>"  size="5" style="<?php echo (($newarr['arf_field_width'] == 'Mobile')) ? 'display:block;' : 'display:none;';?>" />
												<div class="arf_dropdown_wrapper">
													<?php

														$newarr['field_width_unit_tablet'] = !empty( $newarr['field_width_unit_tablet'] ) ? $newarr['field_width_unit_tablet'] : '%';
														$newarr['field_width_unit_mobile'] = !empty( $newarr['field_width_unit_mobile'] ) ? $newarr['field_width_unit_mobile'] : '%';
														$newarr['field_width_tablet'] = !empty( $newarr['field_width_tablet'] ) ? $newarr['field_width_tablet'] : '';
														$newarr['field_width_mobile'] = !empty( $newarr['field_width_mobile'] ) ? $newarr['field_width_mobile'] : '';

														$field_width_unit_opts = array(
															'px' => 'px',
															'%' => '%',
														);

														$field_width_unit_attr = array(
															'data-arfstyle' => 'true',
															'data-arfstyledata' => '{"standard":".arflite_main_div_{arf_form_id} .controls~|~arf_field_width_unit","material":".arflite_main_div_{arf_form_id} .controls~|~arf_field_width_unit"}',
															'data-arfstyleappend' => 'true',
															'data-arfstyleappendid' => 'arf_{arf_form_id}_field_width',
														);

														echo $arflitemaincontroller->arflite_selectpicker_dom( 'arffiu', 'arffieldunit', 'arf_field_cls', 'Desktop' == $newarr['arf_field_width'] ? ' display:block;width:50px; ' : 'display:none;width:50px;', $newarr['field_width_unit'], $field_width_unit_attr, $field_width_unit_opts ); //phpcs:ignore

														echo $arflitemaincontroller->arflite_selectpicker_dom( 'arffiu_tablet', 'arffieldunit_tablet', 'arf_field_tablet_cls', 'Tablet' == $newarr['arf_field_width'] ? ' display:block;width:50px; ' : 'display:none;width:50px;', $newarr['field_width_unit_tablet'], array(), $field_width_unit_opts ); //phpcs:ignore

                                                        echo $arflitemaincontroller->arflite_selectpicker_dom( 'arffiu_mobile', 'arffieldunit_mobile', 'arf_field_mobile_cls', 'Mobile' == $newarr['arf_field_width'] ? ' display:block;width:50px; ' : 'display:none;width:50px;', $newarr['field_width_unit_mobile'], array(), $field_width_unit_opts ); //phpcs:ignore
														?>
												</div>
											</div>
										</div>
										<div class="arf_accordion_container_row_container">
											<div class="arf_accordion_inner_container"><?php echo esc_html__( 'Text Direction', 'arforms-form-builder' ); ?></div>
											<div class="arf_accordion_inner_container">
												<div class="toggle-btn-grp joint-toggle" >
													
													<label onclick="" class="toggle-btn arflitetextdirectionlbl arf_four_button left text_direction <?php if ( $newarr['text_direction'] == '1' ) { echo 'success';} ?>">
													<input type="radio" name="arftds" data-arfstyle="true" data-arfstyledata='{"standard":".arflite_main_div_{arf_form_id} input[type=text]:not(.inplace_field):not(.arf_field_option_input_text):not(.arf-select-dropdown):not(.arfslider):not(.arfhiddencolor):not(.arf_autocomplete)~|~direction||.arflite_main_div_{arf_form_id} input[type=text]:not(.inplace_field):not(.arf_field_option_input_text):not(.arfslider):not(.arfhiddencolor)~|~direction||.arflite_main_div_{arf_form_id} .arfdropdown-menu > li > a~|~text-align||.arflite_main_div_{arf_form_id} .bootstrap-select.btn-group .arfbtn .filter-option~|~text-align||.arflite_main_div_{arf_form_id} .autocomplete-content li span, .arflite_main_div_{arf_form_id} .autocomplete-content li~|~text-align||.arflite_main_div_{arf_form_id} .arf_fieldset .controls input[type=email]~|~direction||.arflite_main_div_{arf_form_id} .arf_fieldset .controls input[type=number]~|~direction||.arflite_main_div_{arf_form_id} .arf_fieldset .controls input[type=url]~|~direction||.arflite_main_div_{arf_form_id} .arf_fieldset .controls input[type=tel]~|~direction||.arflite_main_div_{arf_form_id} .arf_fieldset .controls textarea~|~direction||.arflite_main_div_{arf_form_id} .sltstandard_front .arf-selectpicker-control.arf_form_field_picker dt span~|~text-align||.arflite_main_div_{arf_form_id} .sltstandard_front .arf-selectpicker-control.arf_form_field_picker dd ul li~|~text-align","material":".arflite_main_div_{arf_form_id} .arf_materialize_form input[type=text]:not(.inplace_field):not(.arf_field_option_input_text):not(.arf-select-dropdown):not(.arfslider):not(.arfhiddencolor):not(.arf_autocomplete)~|~direction||.arflite_main_div_{arf_form_id} .arf_materialize_form input[type=text]:not(.inplace_field):not(.arf_field_option_input_text):not(.arfslider):not(.arfhiddencolor)~|~direction||.arflite_main_div_{arf_form_id} .arf_materialize_form .arf-select-dropdown li span~|~text-align||.arflite_main_div_{arf_form_id} .arf_materialize_form .autocomplete-content li span, .arflite_main_div_{arf_form_id} .arf_materialize_form .autocomplete-content li~|~text-align||.arflite_main_div_{arf_form_id}  .arf_materialize_form .arf_fieldset .controls input[type=email]~|~direction||.arflite_main_div_{arf_form_id}  .arf_materialize_form .arf_fieldset .controls input[type=number]~|~direction||.arflite_main_div_{arf_form_id}  .arf_materialize_form .arf_fieldset .controls input[type=url]~|~direction||.arflite_main_div_{arf_form_id} .arf_materialize_form .arf_fieldset .controls input[type=tel]~|~direction||.arflite_main_div_{arf_form_id} .arf_materialize_form textarea:not(.html_field_description):not(.g-recaptcha-response):not(.wp-editor-area)~|~direction||.arflite_main_div_{arf_form_id} .sltstandard_front .arf-selectpicker-control.arf_form_field_picker dt span~|~text-align||.arflite_main_div_{arf_form_id} .sltstandard_front .arf-selectpicker-control.arf_form_field_picker dd ul li~|~text-align"}' class="visuallyhidden" id="txt_dir_1" data-arfstyleappend="true" data-arfstyleappendid="arf_{arf_form_id}_input_text_direction" value="1" <?php checked( $newarr['text_direction'], 1 ); ?> /><svg width="25px" height="29px" viewBox="0 0 30 30"><path xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd" fill="#bcc9e0" d="M1.131,19.305h2V0.43h-2V19.305z M26.631,9.867l-7.5-5v3.5H5.06v3h14.071v3.5    L26.631,9.867z" /></svg></label><label onclick="" class="toggle-btn arflitetextdirectionlbl2 arf_four_button right text_direction <?php if ( $newarr['text_direction'] == '0' ) { echo 'success';} ?>">

													<input type="radio" name="arftds" data-arfstyle="true" data-arfstyledata='{"standard":".arflite_main_div_{arf_form_id} input[type=text]:not(.inplace_field):not(.arf_field_option_input_text):not(.arf-select-dropdown):not(.arfslider):not(.arfhiddencolor):not(.arf_autocomplete)~|~direction||.arflite_main_div_{arf_form_id} input[type=text]:not(.inplace_field):not(.arf_field_option_input_text):not(.arfslider):not(.arfhiddencolor)~|~direction||.arflite_main_div_{arf_form_id} .arfdropdown-menu > li > a~|~text-align||.arflite_main_div_{arf_form_id} .bootstrap-select.btn-group .arfbtn .filter-option~|~text-align||.arflite_main_div_{arf_form_id} .autocomplete-content li span, .arflite_main_div_{arf_form_id} .autocomplete-content li~|~text-align||.arflite_main_div_{arf_form_id} .arf_fieldset .controls input[type=email]~|~direction||.arflite_main_div_{arf_form_id} .arf_fieldset .controls input[type=number]~|~direction||.arflite_main_div_{arf_form_id} .arf_fieldset .controls input[type=url]~|~direction||.arflite_main_div_{arf_form_id} .arf_fieldset .controls input[type=tel]~|~direction||~|~direction||.arflite_main_div_{arf_form_id} .arf_fieldset .controls textarea~|~direction||.arflite_main_div_{arf_form_id} .sltstandard_front .arf-selectpicker-control.arf_form_field_picker dt span~|~text-align||.arflite_main_div_{arf_form_id} .sltstandard_front .arf-selectpicker-control.arf_form_field_picker dd ul li~|~text-align","material":".arflite_main_div_{arf_form_id} .arf_materialize_form input[type=text]:not(.inplace_field):not(.arf_field_option_input_text):not(.arf-select-dropdown):not(.arfslider):not(.arfhiddencolor):not(.arf_autocomplete)~|~direction||.arflite_main_div_{arf_form_id} .arf_materialize_form input[type=text]:not(.inplace_field):not(.arf_field_option_input_text):not(.arfslider):not(.arfhiddencolor)~|~direction||.arflite_main_div_{arf_form_id} .arf_materialize_form .arf-select-dropdown li span~|~text-align||.arflite_main_div_{arf_form_id} .arf_materialize_form .autocomplete-content li span, .arflite_main_div_{arf_form_id} .arf_materialize_form .autocomplete-content li~|~text-align||.arflite_main_div_{arf_form_id} .arf_materialize_form .arf_fieldset .controls input[type=email]~|~direction||.arflite_main_div_{arf_form_id} .arf_materialize_form .arf_fieldset .controls input[type=number]~|~direction||.arflite_main_div_{arf_form_id} .arf_materialize_form .arf_fieldset .controls input[type=url]~|~direction||.arflite_main_div_{arf_form_id} .arf_materialize_form .arf_fieldset .controls input[type=tel]~|~direction||.arflite_main_div_{arf_form_id} .arf_materialize_form textarea:not(.html_field_description):not(.g-recaptcha-response):not(.wp-editor-area)~|~direction||.arflite_main_div_{arf_form_id} .sltstandard_front .arf-selectpicker-control.arf_form_field_picker dt span~|~text-align||.arflite_main_div_{arf_form_id} .sltstandard_front .arf-selectpicker-control.arf_form_field_picker dd ul li~|~text-align"}' data-arfstyleappend="true" data-arfstyleappendid="arf_{arf_form_id}_input_text_direction" class="visuallyhidden" value="0"  id="txt_dir_2" <?php checked( $newarr['text_direction'], 0 ); ?> /><svg width="25px" height="29px" viewBox="0 0 30 30"><path xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" fill="#bcc9e0" clip-rule="evenodd" d="M23.881,0.43v18.875h2V0.43H23.881z M8.819,4.867l-7.938,5l7.938,5v-3.5H21.89    v-3H8.819V4.867z"/></svg></label><br>

													<span class="arf_px arf_font_size arfinputfielddirectionltr"><?php echo esc_html__( 'LTR', 'arforms-form-builder' ); ?></span>
													<span class="arf_px arf_font_size arfinputfielddirectionrtl"><?php echo esc_html__( 'RTL', 'arforms-form-builder' ); ?></span>
												</div>
											</div>
										</div>

										<div class="arf_accordion_container_row_container">
											<div class="arf_accordion_inner_container"><?php echo esc_html__( 'Field Transparency', 'arforms-form-builder' ); ?></div>
											<div class="arf_accordion_inner_container">
												<div class="arf_float_right arflite_field-trans-div">
													<label class="arf_js_switch_label">
														<span><?php echo esc_html__( 'No', 'arforms-form-builder' ); ?>&nbsp;</span>
													</label>
													<span class="arf_js_switch_wrapper">
														<input type="checkbox" class="js-switch chkstanard <?php echo ( $newarr['arfinputstyle'] == 'material' ) ? 'arfcursornotallow' : ''; ?>" name="arfmfo" id="arfmainfield_opacity" data-arfstyle="true" data-arfstyledata='{"standard":".arflite_main_div_{arf_form_id} .controls input[type=text]:not(.inplace_field):not(.arf_field_option_input_text):not(.arf_autocomplete):not(.arfslider)~|~field_transparency||.arflite_main_div_{arf_form_id} .controls input[type=email]~|~field_transparency||.arflite_main_div_{arf_form_id} .controls input[type=number]~|~field_transparency||.arflite_main_div_{arf_form_id} .controls input[type=url]~|~field_transparency||.arflite_main_div_{arf_form_id} .controls input[type=tel]~|~field_transparency||.arflite_main_div_{arf_form_id} .controls textarea~|~field_transparency||.arflite_main_div_{arf_form_id} .controls select~|~field_transparency||.arflite_main_div_{arf_form_id} .sltstandard_front .btn-group .arfbtn.dropdown-toggle~|~field_transparency||.arflite_main_div_{arf_form_id} .sltstandard_front .btn-group.open .arfdropdown-menu~|~field_transparency||.arflite_main_div_{arf_form_id} .sltstandard_front .btn-group.open .arfbtn.dropdown-toggle~|~field_transparency||.arflite_main_div_{arf_form_id} .controls input[type=text]:focus:not(.inplace_field):not(.arf_field_option_input_text):not(.arf_autocomplete):not(.arfslider)~|~field_transparency_focus||.arflite_main_div_{arf_form_id} .controls input[type=email]:focus~|~field_transparency_focus||.arflite_main_div_{arf_form_id} .controls input[type=number]:focus~|~field_transparency_focus||.arflite_main_div_{arf_form_id} .controls input[type=url]:focus~|~field_transparency_focus||.arflite_main_div_{arf_form_id} .controls input[type=tel]:focus~|~field_transparency_focus||.arflite_main_div_{arf_form_id} .arfmainformfield .controls textarea:focus~|~field_transparency_focus||.arflite_main_div_{arf_form_id} .controls select:focus~|~field_transparency_focus||.arflite_main_div_{arf_form_id} .sltstandard_front .btn-group .arfbtn.dropdown-toggle:focus~|~field_transparency_focus||.arflite_main_div_{arf_form_id} .sltstandard_front .btn-group.open .arfdropdown-menu:focus~|~field_transparency_focus||.arflite_main_div_{arf_form_id} .sltstandard_front .btn-group.open .arfbtn.dropdown-toggle:focus~|~field_transparency_focus","material":".arflite_main_div_{arf_form_id} .controls input[type=text]:not(.inplace_field):not(.arf_field_option_input_text):not(.arf_autocomplete):not(.arfslider)~|~field_transparency||.arflite_main_div_{arf_form_id} .controls input[type=email]~|~field_transparency||.arflite_main_div_{arf_form_id} .controls input[type=number]~|~field_transparency||.arflite_main_div_{arf_form_id} .controls input[type=url]~|~field_transparency||.arflite_main_div_{arf_form_id} .controls input[type=tel]~|~field_transparency||.arflite_main_div_{arf_form_id} .controls textarea~|~field_transparency||.arflite_main_div_{arf_form_id} .controls select~|~field_transparency"}' data-arfstyleappend="true" data-arfstyleappendid="arf_{arf_form_id}_field_transparency" value="1" <?php echo ( $newarr['arfmainfield_opacity'] == 1 ) ? 'checked="checked"' : ''; ?> <?php echo ( $newarr['arfinputstyle'] == 'material' ) ? 'disabled="disabled"' : ''; ?> />
														<span class="arf_js_switch"></span>
													</span>
													<label class="arf_js_switch_label">
														<span>&nbsp;<?php echo esc_html__( 'Yes', 'arforms-form-builder' ); ?></span>
													</label>
												</div>
											</div>
										</div>

										<div class="arf_accordion_container_row_container">
											<div class="arf_accordion_inner_container"><?php echo esc_html__( 'Hide Required Indicator', 'arforms-form-builder' ); ?></div>
												<div class="arf_accordion_inner_container">
													<div class="arf_float_right margin-right_5px">
														<label class="arf_js_switch_label">
															<span class=""><?php echo esc_html__( 'No', 'arforms-form-builder' ); ?>&nbsp;</span>
														</label>
														<span class="arf_js_switch_wrapper">
														   <input type="checkbox" class="js-switch chkstanard" name="arfrinc" id="arfreq_inc" data-arfstyle="true" data-arfstyledata='{"standard":".arf_main_label span.arf_edit_in_place+span~|~req_indicator","material":".arf_main_label span.arf_edit_in_place+span~|~req_indicator"}' data-arfstyleappend="true" data-arfstyleappendid="arf_{arf_form_id}_arfreq_inc" value="1" <?php echo ( isset( $newarr['arf_req_indicator'] ) && $newarr['arf_req_indicator'] == 1 ) ? 'checked="checked"' : ''; ?> />
															<span class="arf_js_switch"></span>
														</span>
														<label class="arf_js_switch_label">
															<span class="">&nbsp;<?php echo esc_html__( 'Yes', 'arforms-form-builder' ); ?></span>
														</label>
													</div>
												</div>
										</div>

										<div class="arf_accordion_container_row_container">
											<div class="arf_accordion_inner_container"><?php echo esc_html__( 'Space Between Two Fields', 'arforms-form-builder' ); ?></div>
											<div class="arf_accordion_inner_container">
												<input type="text" name="arffms" id="arffieldmarginsetting" onkeypress="return arflite_check_numeric_input(event,this)" onpaste="arflite_validatepaste(this, event)" class="arf_small_width_txtbox arfcolor" data-arfstyle="true" data-arfstyledata='{"standard":".arflite_main_div_{arf_form_id} #new_fields .arfmainformfield.edit_form_item~|~field-margin-bottom","material":".arflite_main_div_{arf_form_id} #new_fields .arfmainformfield.edit_form_item~|~field-margin-bottom"}' data-arfstyleappend="true" data-arfstyleappendid="arf_{arf_form_id}_space_between_fields" value="<?php echo esc_attr( $newarr['arffieldmarginssetting'] ); ?>"  size="5" />
											</div>
										</div>

										<div class="arf_accordion_container_row_container">
											<div class="arf_accordion_inner_container">
												<?php echo esc_html__( 'Placeholder Opacity', 'arforms-form-builder' ); ?>
											</div>
											<div class="arf_accordion_inner_container arf_accordion_container_mar">
												<div class="arf_slider_wrapper">
													<div id="arflite_placeholder_opacity_slider" class="noUi-target noUi-ltr noUi-horizontal noUi-txt-dir-ltr slider-track"></div>
													<input id="arfplaceholder_opacity_exs" class="arf_slider arf_slider_input" data-slider-id='arfplaceholder_opacity_exsSlider' type="text" data-slider-min="0" data-slider-max="10" data-slider-step="1" data-slider-value="<?php echo isset( $newarr['arfplaceholder_opacity'] ) ? ( esc_attr( $newarr['arfplaceholder_opacity'] ) * 10 ) : ( 0.5 * 10 ); ?>"  />
													<div class="arf_slider_unit_data">
														<div class="arflite_float_left"><?php echo esc_html__( '0', 'arforms-form-builder' ); ?></div>
														<div class="arflite_float_right"><?php echo esc_html__( '1', 'arforms-form-builder' ); ?></div>
													</div>
													<input type="hidden" name="arfplaceholder_opacity" id="arfplaceholder_opacity" class="txtxbox_widget " value="<?php echo isset( $newarr['arfplaceholder_opacity'] ) ? esc_attr( $newarr['arfplaceholder_opacity'] ) : 0.5; ?>" data-arfstyle="true" data-arfstyleappend="true" data-arfstyleappendid="arf_{arf_form_id}_arfplaceholder_opacity" data-arfstyledata='{"standard":".arflite_main_div_{arf_form_id} input[type=text]:not(.arfslider):not(.arf_autocomplete):not(.arf_field_option_input_text):not(.inplace_field)::-webkit-input-placeholder~|~opacity||.wp-admin .allfields .controls .smaple-textarea::-webkit-input-placeholder~|~opacity||.arflite_main_div_{arf_form_id} .controls textarea::-webkit-input-placeholder~|~opacity||.arflite_main_div_{arf_form_id} input[type=number]::-webkit-input-placeholder~|~opacity||.arflite_main_div_{arf_form_id} input[type=url]::-webkit-input-placeholder~|~opacity||.arflite_main_div_{arf_form_id} input[type=tel]::-webkit-input-placeholder~|~opacity||.arflite_main_div_{arf_form_id} select::-webkit-input-placeholder~|~opacity||.arflite_main_div_{arf_form_id} input[type=text]:not(.arfslider):not(.arf_autocomplete):not(.arf_field_option_input_text):not(.inplace_field):-moz-placeholder~|~opacity||.wp-admin .allfields .controls .smaple-textarea:-moz-placeholder~|~opacity||.arflite_main_div_{arf_form_id} .controls textarea:-moz-placeholder~|~opacity||.arflite_main_div_{arf_form_id} input[type=number]:-moz-placeholder~|~opacity||.arflite_main_div_{arf_form_id} input[type=url]:-moz-placeholder~|~opacity||.arflite_main_div_{arf_form_id} input[type=tel]:-moz-placeholder~|~opacity||.arflite_main_div_{arf_form_id} select:-moz-placeholder~|~opacity||.arflite_main_div_{arf_form_id} input[type=text]:not(.arfslider):not(.arf_autocomplete):not(.arf_field_option_input_text):not(.inplace_field)::-moz-placeholder~|~opacity||.wp-admin .allfields .controls .smaple-textarea::-moz-placeholder~|~opacity||.arflite_main_div_{arf_form_id} .controls textarea::-moz-placeholder~|~opacity||.arflite_main_div_{arf_form_id} input[type=number]::-moz-placeholder~|~opacity||.arflite_main_div_{arf_form_id} input[type=url]::-moz-placeholder~|~opacity||.arflite_main_div_{arf_form_id} input[type=tel]::-moz-placeholder~|~opacity||.arflite_main_div_{arf_form_id} select::-moz-placeholder~|~opacity||.arflite_main_div_{arf_form_id} input[type=text]:not(.arfslider):not(.arf_autocomplete):not(.arf_field_option_input_text):not(.inplace_field):-ms-input-placeholder~|~opacity||.wp-admin .allfields .controls .smaple-textarea:-ms-input-placeholder~|~opacity||.arflite_main_div_{arf_form_id} .controls textarea:-ms-input-placeholder~|~opacity||.arflite_main_div_{arf_form_id} input[type=number]:-ms-input-placeholder~|~opacity||.arflite_main_div_{arf_form_id} input[type=url]:-ms-input-placeholder~|~opacity||.arflite_main_div_{arf_form_id} input[type=tel]:-ms-input-placeholder~|~opacity||.arflite_main_div_{arf_form_id} select:-ms-input-placeholder~|~opacity||.arflite_main_div_{arf_form_id} input[type=text]:not(.arfslider):not(.arf_autocomplete):not(.arf_field_option_input_text):not(.inplace_field)::-ms-input-placeholder~|~opacity||.wp-admin .allfields .controls .smaple-textarea::-ms-input-placeholder~|~opacity||.arflite_main_div_{arf_form_id} .controls textarea::-ms-input-placeholder~|~opacity||.arflite_main_div_{arf_form_id} input[type=number]::-ms-input-placeholder~|~opacity||.arflite_main_div_{arf_form_id} input[type=url]::-ms-input-placeholder~|~opacity||.arflite_main_div_{arf_form_id} input[type=tel]::-ms-input-placeholder~|~opacity||.arflite_main_div_{arf_form_id} select::-ms-input-placeholder~|~opacity","material":".arflite_main_div_{arf_form_id} .arf_materialize_form input[type=text]:not(.inplace_field):not(.arf_field_option_input_text):not(.arfslider):not(.arfhiddencolor):not(.arf_autocomplete)::-webkit-input-placeholder~|~opacity||.arflite_main_div_{arf_form_id} .arf_materialize_form textarea:not(.html_field_description)::-webkit-input-placeholder~|~opacity||.arflite_main_div_{arf_form_id} .arf_materialize_form input[type=email]::-webkit-input-placeholder~|~opacity||.arflite_main_div_{arf_form_id} .arf_materialize_form input[type=number]::-webkit-input-placeholder~|~opacity||.arflite_main_div_{arf_form_id} .arf_materialize_form input[type=url]::-webkit-input-placeholder~|~opacity||.arflite_main_div_{arf_form_id} .arf_materialize_form input[type=tel]::-webkit-input-placeholder~|~opacity||.arflite_main_div_{arf_form_id} .arf_materialize_form select::-webkit-input-placeholder~|~opacity||.arflite_main_div_{arf_form_id} .arf_materialize_form input[type=text]:not(.inplace_field):not(.arf_field_option_input_text):not(.arfslider):not(.arfhiddencolor):not(.arf_autocomplete):-moz-placeholder~|~opacity||.arflite_main_div_{arf_form_id} .arf_materialize_form textarea:not(.html_field_description):-moz-placeholder~|~opacity||.arflite_main_div_{arf_form_id} .arf_materialize_form input[type=email]:-moz-placeholder~|~opacity||.arflite_main_div_{arf_form_id} .arf_materialize_form input[type=number]:-moz-placeholder~|~opacity||.arflite_main_div_{arf_form_id} .arf_materialize_form input[type=url]:-moz-placeholder~|~opacity||.arflite_main_div_{arf_form_id} .arf_materialize_form input[type=tel]:-moz-placeholder~|~opacity||.arflite_main_div_{arf_form_id} .arf_materialize_form select:-moz-placeholder~|~opacity||.arflite_main_div_{arf_form_id} .arf_materialize_form input[type=text]:not(.inplace_field):not(.arf_field_option_input_text):not(.arfslider):not(.arfhiddencolor):not(.arf_autocomplete)::-moz-placeholder~|~opacity||.arflite_main_div_{arf_form_id} .arf_materialize_form textarea:not(.html_field_description)::-moz-placeholder~|~opacity||.arflite_main_div_{arf_form_id} .arf_materialize_form input[type=email]::-moz-placeholder~|~opacity||.arflite_main_div_{arf_form_id} .arf_materialize_form input[type=number]::-moz-placeholder~|~opacity||.arflite_main_div_{arf_form_id} .arf_materialize_form input[type=url]::-moz-placeholder~|~opacity||.arflite_main_div_{arf_form_id} .arf_materialize_form input[type=tel]::-moz-placeholder~|~opacity||.arflite_main_div_{arf_form_id} .arf_materialize_form select::-moz-placeholder~|~opacity||.arflite_main_div_{arf_form_id} .arf_materialize_form input[type=text]:not(.inplace_field):not(.arf_field_option_input_text):not(.arfslider):not(.arfhiddencolor):not(.arf_autocomplete):-ms-input-placeholder~|~opacity||.arflite_main_div_{arf_form_id} .arf_materialize_form textarea:not(.html_field_description):-ms-input-placeholder~|~opacity||.arflite_main_div_{arf_form_id} .arf_materialize_form input[type=email]:-ms-input-placeholder~|~opacity||.arflite_main_div_{arf_form_id} .arf_materialize_form input[type=number]:-ms-input-placeholder~|~opacity||.arflite_main_div_{arf_form_id} .arf_materialize_form input[type=url]:-ms-input-placeholder~|~opacity||.arflite_main_div_{arf_form_id} .arf_materialize_form input[type=tel]:-ms-input-placeholder~|~opacity||.arflite_main_div_{arf_form_id} .arf_materialize_form select:-ms-input-placeholder~|~opacity||.arflite_main_div_{arf_form_id} .arf_materialize_form input[type=text]:not(.inplace_field):not(.arf_field_option_input_text):not(.arfslider):not(.arfhiddencolor):not(.arf_autocomplete)::-ms-input-placeholder~|~opacity||.arflite_main_div_{arf_form_id} .arf_materialize_form textarea:not(.html_field_description)::-ms-input-placeholder~|~opacity||.arflite_main_div_{arf_form_id} .arf_materialize_form input[type=email]::-ms-input-placeholder~|~opacity||.arflite_main_div_{arf_form_id} .arf_materialize_form input[type=number]::-ms-input-placeholder~|~opacity||.arflite_main_div_{arf_form_id} .arf_materialize_form input[type=url]::-ms-input-placeholder~|~opacity||.arflite_main_div_{arf_form_id} .arf_materialize_form input[type=tel]::-ms-input-placeholder~|~opacity||.arflite_main_div_{arf_form_id} .arf_materialize_form select::-ms-input-placeholder~|~opacity"}' />
												</div>
											</div>
										</div>

										<div class="arf_accordion_container_row arf_half_width arflitefont-setting-wrap">
											<div class="arf_accordion_container_inner_div">
												<div class="arf_accordion_inner_title arf_width_50"><?php echo esc_html__( 'Font Settings', 'arforms-form-builder' ); ?></div>
											</div>

											<div class="arf_accordion_container_inner_div">
												<div class="arf_custom_font" data-id="arf_input_font_settings">
													<div class="arf_custom_font_icon">
														<svg viewBox="-10 -10 35 35">
														<g id="paint_brush">
														<path fill="#ffffff" fill-rule="evenodd" clip-rule="evenodd" d="M7.423,14.117c1.076,0,2.093,0.022,3.052,0.068v-0.82c-0.942-0.078-1.457-0.146-1.542-0.205  c-0.124-0.092-0.203-0.354-0.235-0.787s-0.049-1.601-0.049-3.504l0.059-6.568c0-0.299,0.013-0.472,0.039-0.518  C8.772,1.744,8.85,1.725,8.981,1.725c1.549,0,2.584,0.043,3.105,0.128c0.162,0.026,0.267,0.076,0.313,0.148  c0.059,0.092,0.117,0.687,0.176,1.784h0.811c0.052-1.201,0.14-2.249,0.264-3.145l-0.107-0.156c-2.396,0.098-4.561,0.146-6.494,0.146  c-1.94,0-3.936-0.049-5.986-0.146L0.954,0.563c0.078,0.901,0.11,1.976,0.098,3.223h0.84c0.085-1.062,0.141-1.633,0.166-1.714  C2.083,1.99,2.121,1.933,2.17,1.9c0.049-0.032,0.262-0.065,0.641-0.098c0.652-0.052,1.433-0.078,2.34-0.078  c0.443,0,0.674,0.024,0.69,0.073c0.016,0.049,0.024,1.364,0.024,3.947c0,1.313-0.01,2.602-0.029,3.863  c-0.033,1.776-0.072,2.804-0.117,3.084c-0.039,0.201-0.098,0.34-0.176,0.414c-0.078,0.075-0.212,0.129-0.4,0.161  c-0.404,0.065-0.791,0.098-1.162,0.098v0.82C4.861,14.14,6.008,14.117,7.423,14.117L7.423,14.117z"></path>
														</g></svg>
													</div>
													<div class="arf_custom_font_label"><?php echo esc_html__( 'Advanced font options', 'arforms-form-builder' ); ?></div>
												</div>
											</div>
										</div>

										<div class="arf_accordion_container_row_separator"></div>
										<div class="arf_accordion_container_row arf_padding">
											<div class="arf_accordion_outer_title"><?php echo esc_html__( 'Tooltip position', 'arforms-form-builder' ); ?></div>

										</div>

										<div class="arf_accordion_container_row_container">
											<div class="arf_accordion_inner_title"><?php echo esc_html__( 'Position', 'arforms-form-builder' ); ?></div>
										</div>
										<div class="arf_accordion_container_row_container_left">      
											<div class="arf_toggle_button_group arf_four_button_group">
												<?php $newarr['arftooltipposition'] = isset( $newarr['arftooltipposition'] ) ? $newarr['arftooltipposition'] : 'top'; ?>
												<label id="rightpos" class="arf_toggle_btn righttoolpos <?php echo ( $newarr['arftooltipposition'] == 'right' ) ? 'arf_success' : ''; ?>"><input type="radio" name="arflitetippos" class="visuallyhidden arftooltipposition" id='right_pos' data-id="arfestbc2" value="right" <?php checked( $newarr['arftooltipposition'], 'right' ); ?> /><?php echo esc_html__( 'Right', 'arforms-form-builder' ); ?></label>
												<label id="leftpos" class="arf_toggle_btn lefttoolpos <?php echo ( $newarr['arftooltipposition'] == 'left' ) ? 'arf_success' : ''; ?>"><input type="radio" name="arflitetippos" class="visuallyhidden arftooltipposition" id='left_pos' data-id="arfestbc2" value="left" <?php checked( $newarr['arftooltipposition'], 'left' ); ?> /><?php echo esc_html__( 'Left', 'arforms-form-builder' ); ?></label>
												<label id="bottompos" class="arf_toggle_btn bottomtoolpos <?php echo ( $newarr['arftooltipposition'] == 'bottom' ) ? 'arf_success' : ''; ?>"><input type="radio" name="arflitetippos" class="visuallyhidden arftooltipposition" id='bottom_pos'  data-id="arfestbc2" value="bottom" <?php checked( $newarr['arftooltipposition'], 'bottom' ); ?> /><?php echo esc_html__( 'Bottom', 'arforms-form-builder' ); ?></label>
												<label id="toppos" class="arf_toggle_btn toptoolpos <?php echo ( $newarr['arftooltipposition'] == 'top' ) ? 'arf_success' : ''; ?>"><input type='radio' name='arflitetippos' class='visuallyhidden arftooltipposition' id='top_pos' value='top' <?php checked( $newarr['arftooltipposition'], 'top' ); ?> /><?php echo esc_html__( 'Top', 'arforms-form-builder' ); ?></label>
											</div>
										</div>
										<div class="arf_accordion_container_row_separator"></div>
										<div class="arf_accordion_container_row arf_padding">
											<div class="arf_accordion_outer_title"><?php echo esc_html__( 'Field inner spacing', 'arforms-form-builder' ); ?></div>
										</div>
										<div class="arf_accordion_container_row_container">
											<div class="arf_accordion_inner_container"><?php echo esc_html__( 'Vertical', 'arforms-form-builder' ); ?></div>
											<div class="arf_accordion_inner_container arf_accordion_container_mar">
												<div class="arf_slider_wrapper">
													<div id="arflite_vertical_slider" class="noUi-target noUi-ltr noUi-horizontal noUi-txt-dir-ltr slider-track"></div>
													<input id="arffieldinnermarginssetting_1_exs" class="arf_slider arf_slider_input" data-slider-id='arffieldinnermarginssetting_1_exsSlider' type="text" data-slider-min="0" data-slider-max="25" data-slider-step="1" data-dvalue="<?php echo floatval( $newarr['arffieldinnermarginssetting_1'] ); ?>" data-slider-value="<?php echo floatval( $newarr['arffieldinnermarginssetting_1'] ); ?>" />
													<input type="hidden" name="arffieldinnermarginsetting_1" id="arffieldinnermarginsetting_1" value="<?php echo floatval( esc_attr( $newarr['arffieldinnermarginssetting_1'] ) ); ?>" />
													<div class="arf_slider_unit_data">
														<div class="arf_px arflite_float_left">0 px</div>
														<div class="arf_px arflite_float_right">25 px</div>
													</div>
												</div>
											</div>
										</div>
										<div class="arf_accordion_container_row_container">
											<div class="arf_accordion_inner_container"><?php echo esc_html__( 'Horizontal', 'arforms-form-builder' ); ?></div>
											<div class="arf_accordion_inner_container arf_accordion_container_mar">
												<div class="arf_slider_wrapper">
													<div id="arflite_horizontal_slider" class="noUi-target noUi-ltr noUi-horizontal noUi-txt-dir-ltr slider-track"></div>
													<input id="arffieldinnermarginssetting_2_exs" class="arf_slider arf_slider_input" data-slider-id='arffieldinnermarginssetting_2_exsSlider' type="text" data-slider-min="0" data-slider-max="25" data-slider-step="1" data-dvalue="<?php echo floatval( esc_attr( $newarr['arffieldinnermarginssetting_2'] ) ); ?>" data-slider-value="<?php echo floatval( esc_attr( $newarr['arffieldinnermarginssetting_2'] ) ); ?>" />
													<input type="hidden" name="arffieldinnermarginsetting_2" data-arfstyle="true" data-arfstyledata='{"standard":".arflite_main_div_{arf_form_id} .sltstandard_front .arfbtn.dropdown-toggle .filter-option~|~left||.arflite_main_div_{arf_form_id} .sltstandard_front .arfbtn.dropdown-toggle .filter-option~|~right||.arflite_main_div_{arf_form_id} .arf-selectpicker-control.arf_form_field_picker dt span~|~padding-left||.arflite_main_div_{arf_form_id} .arf-selectpicker-control.arf_form_field_picker dt span~|~padding-right||.arflite_main_div_{arf_form_id} .arf-selectpicker-control.arf_form_field_picker dd ul li~|~padding-left||.arflite_main_div_{arf_form_id} .arf-selectpicker-control.arf_form_field_picker dd ul li~|~padding-right","material":".arflite_main_div_{arf_form_id} .arf_materialize_form.arf_fieldset .controls input[type=\"text\"]:not(.inplace_field):not(.arf_field_option_input_text):not(.arf-select-dropdown):not(.arf_autocomplete):not(.arfslider)~|~padding-left||.arflite_main_div_{arf_form_id} .arf_materialize_form.arf_fieldset .controls input[type=\"text\"]:not(.inplace_field):not(.arf_field_option_input_text):not(.arf-select-dropdown):not(.arf_autocomplete):not(.arfslider)~|~padding-right||.arflite_main_div_{arf_form_id} .arf_materialize_form.arf_fieldset .controls input[type=\"email\"]~|~padding-left||.arflite_main_div_{arf_form_id} .arf_materialize_form.arf_fieldset .controls input[type=\"email\"]~|~padding-right||.arflite_main_div_{arf_form_id} .arf_materialize_form.arf_fieldset .controls input[type=\"text\"]:not(.inplace_field):not(.arf_field_option_input_text):not(.arf-select-dropdown):not(.arf_autocomplete):not(.arfslider)~|~padding-left||.arflite_main_div_{arf_form_id} .arf_materialize_form.arf_fieldset .controls input[type=\"text\"]:not(.inplace_field):not(.arf_field_option_input_text):not(.arf-select-dropdown):not(.arf_autocomplete):not(.arfslider)~|~padding-right||.arflite_main_div_{arf_form_id} .arf_materialize_form.arf_fieldset .controls input[type=\"phone\"]~|~padding-left||.arflite_main_div_{arf_form_id} .arf_materialize_form.arf_fieldset .controls input[type=\"phone\"]~|~padding-right||.arflite_main_div_{arf_form_id} .arf_materialize_form.arf_fieldset .controls input[type=\"tel\"]~|~padding-left||.arflite_main_div_{arf_form_id} .arf_materialize_form.arf_fieldset .controls input[type=\"tel\"]~|~padding-right||.arflite_main_div_{arf_form_id} .arf_materialize_form.arf_fieldset .controls input[type=\"hidden\"]~|~padding-left||.arflite_main_div_{arf_form_id} .arf_materialize_form.arf_fieldset .controls input[type=\"hidden\"]~|~padding-right||.arflite_main_div_{arf_form_id} .arf_materialize_form.arf_fieldset .controls input[type=\"number\"]~|~padding-left||.arflite_main_div_{arf_form_id} .arf_materialize_form.arf_fieldset .controls input[type=\"number\"]~|~padding-right||.arflite_main_div_{arf_form_id} .arf_materialize_form.arf_fieldset .controls input[type=\"url\"]~|~padding-left||.arflite_main_div_{arf_form_id} .arf_materialize_form.arf_fieldset .controls input[type=\"url\"]~|~padding-right||.arflite_main_div_{arf_form_id} .arf_materialize_form.arf_fieldset .controls .arf_material_theme_container_with_icons input[type=\"text\"]:not(.inplace_field):not(.arf_field_option_input_text):not(.arf-select-dropdown):not(.arf_autocomplete):not(.arfslider)~|~padding-right||.arflite_main_div_{arf_form_id} .arf_materialize_form.arf_fieldset .controls .arf_material_theme_container_with_icons input[type=\"phone\"]~|~padding-left||.arflite_main_div_{arf_form_id} .arf_materialize_form.arf_fieldset .controls .arf_material_theme_container_with_icons input[type=\"phone\"]~|~padding-right||.arflite_main_div_{arf_form_id} .arf_materialize_form.arf_fieldset .controls .arf_material_theme_container_with_icons input[type=\"tel\"]~|~padding-left||.arflite_main_div_{arf_form_id} .arf_materialize_form.arf_fieldset .controls .arf_material_theme_container_with_icons input[type=\"tel\"]~|~padding-right||.arflite_main_div_{arf_form_id} .arf_materialize_form.arf_fieldset .controls .arf_material_theme_container_with_icons input[type=\"hidden\"]~|~padding-left||.arflite_main_div_{arf_form_id} .arf_materialize_form.arf_fieldset .controls .arf_material_theme_container_with_icons input[type=\"hidden\"]~|~padding-right||.arflite_main_div_{arf_form_id} .arf_materialize_form.arf_fieldset .controls .arf_material_theme_container_with_icons input[type=\"number\"]~|~padding-left||.arflite_main_div_{arf_form_id} .arf_materialize_form.arf_fieldset .controls .arf_material_theme_container_with_icons input[type=\"number\"]~|~padding-right||.arflite_main_div_{arf_form_id} .arf_materialize_form.arf_fieldset .controls .arf_material_theme_container_with_icons input[type=\"url\"]~|~padding-left||.arflite_main_div_{arf_form_id} .arf_materialize_form.arf_fieldset .controls .arf_material_theme_container_with_icons input[type=\"url\"]~|~padding-right||.arflite_main_div_{arf_form_id} .arf_materialize_form.arf_fieldset .controls textarea~|~padding-left||.arflite_main_div_{arf_form_id} .arf_materialize_form.arf_fieldset .controls textarea~|~padding-right||.arflite_main_div_{arf_form_id} .arf_materialize_form input[type=text]:not(.inplace_field):not(.arf_field_option_input_text):not(.arfslider):not(.arfhiddencolor)~|~padding-left||.arflite_main_div_{arf_form_id} .arf_materialize_form input[type=text]:not(.inplace_field):not(.arf_field_option_input_text):not(.arfslider):not(.arfhiddencolor)~|~padding-right||.arflite_main_div_{arf_form_id} .arf_materialize_form .arf-select-dropdown li span~|~padding-left||.arflite_main_div_{arf_form_id} .arf_materialize_form .arf-select-dropdown li span~|~padding-right||.arflite_main_div_{arf_form_id} .arf_materialize_form .arf-selectpicker-control.arf_form_field_picker dt span~|~padding-left||.arflite_main_div_{arf_form_id} .arf_materialize_form .arf-selectpicker-control.arf_form_field_picker dt span~|~padding-right||.arflite_main_div_{arf_form_id} .arf_materialize_form .arf-selectpicker-control.arf_form_field_picker dd ul li~|~padding-left||.arflite_main_div_{arf_form_id} .arf_materialize_form .arf-selectpicker-control.arf_form_field_picker dd ul li~|~padding-right"}' data-arfstyleappend="true" data-arfstyleappendid="arf_{arf_form_id}_field_inner_spacing_for_dropdown" id="arffieldinnermarginsetting_2" value="<?php echo floatval( esc_attr( $newarr['arffieldinnermarginssetting_2'] ) ); ?>" />
													<div class="arf_slider_unit_data">
														<div class="arf_px arflite_float_left" >0 px</div>
														<div class="arf_px arflite_float_right" >25 px</div>
													</div>
												</div>
												<?php
													$arffieldinnermarginssetting_value = $newarr['arffieldinnermarginssetting_1'] . 'px ' . $newarr['arffieldinnermarginssetting_2'] . 'px ' . $newarr['arffieldinnermarginssetting_1'] . 'px ' . $newarr['arffieldinnermarginssetting_2'] . 'px';
												?>
												<input type="hidden" name="arffims" id="arffieldinnermarginsetting" class="txtxbox_widget " data-arfstyle="true" data-arfstyledata='{"standard":".arflite_main_div_{arf_form_id} .arf_fieldset .controls input[type=\"text\"]:not(.inplace_field):not(.arf_field_option_input_text):not(.arf-select-dropdown):not(.arf_autocomplete):not(.arfslider)~|~padding||.arflite_main_div_{arf_form_id} .arf_fieldset .controls input[type=\"email\"]~|~padding||.arflite_main_div_{arf_form_id} .arf_fieldset .controls input[type=\"phone\"]~|~padding||.arflite_main_div_{arf_form_id} .arf_fieldset .controls input[type=\"tel\"]~|~padding||.arflite_main_div_{arf_form_id} .arf_fieldset .controls input[type=\"hidden\"]~|~padding||.arflite_main_div_{arf_form_id} .arf_fieldset .controls input[type=\"number\"]~|~padding||.arflite_main_div_{arf_form_id} .arf_fieldset .controls input[type=\"url\"]~|~padding||.arflite_main_div_{arf_form_id} .arf_fieldset .controls textarea~|~padding||.arflite_main_div_{arf_form_id} .sltstandard_front .btn-group .arfbtn.dropdown-toggle~|~padding||.arflite_main_div_{arf_form_id} .arfdropdown-menu > li > a~|~padding||.arflite_main_div_{arf_form_id} .sltstandard_front .arf-selectpicker-control.arf_form_field_picker dt~|~padding||.arflite_main_div_{arf_form_id} .sltstandard_front .arf-selectpicker-control.arf_form_field_picker dd ul li~|~padding","material":".arflite_main_div_{arf_form_id} .arf_materialize_form.arf_fieldset .controls .arf_phone_with_flag input[type=\"phone\"]~|~padding"}' data-arfstyleappend="true" data-arfstyleappendid="arf_{arf_form_id}_field_padding" value="<?php echo esc_attr( $arffieldinnermarginssetting_value ); ?>"  size="5" />
											</div>
										</div>
										<div class="arf_accordion_container_row_separator"></div>
										<div class="arf_accordion_container_row arf_padding">
											<div class="arf_accordion_outer_title"><?php echo esc_html__( 'Field Border Settings', 'arforms-form-builder' ); ?></div>
										</div>
										<div class="arf_accordion_container_row_container">
											<div class="arf_accordion_inner_container"><?php echo esc_html__( 'Border Size', 'arforms-form-builder' ); ?></div>
											<div class="arf_accordion_inner_container arf_accordion_container_mar">
												<div class="arf_slider_wrapper">
													<div id="arflite_input_border_size" class="noUi-target noUi-ltr noUi-horizontal noUi-txt-dir-ltr slider-track"></div>
													<input id="arffieldborderwidthsetting_exs" class="arf_slider arf_slider_input" data-slider-id='arffieldborderwidthsetting_exsSlider' type="text" data-slider-min="0" data-slider-max="20" data-slider-step="1" data-slider-value="<?php echo esc_attr( $newarr['arffieldborderwidthsetting'] ); ?>" />
													<div class="arf_slider_unit_data">
														<div class="arf_px arflite_float_left">0 px</div>
														<div class="arf_px arflite_float_right">20 px</div>
													</div>

													<input type="hidden" name="arffbws" id="arffieldborderwidthsetting" data-arfstyle="true" data-arfstyledata='{"standard":".arflite_main_div_{arf_form_id} .arf_fieldset .controls input[type=\"text\"]:not(.inplace_field):not(.arf_field_option_input_text):not(.arf-select-dropdown):not(.arf_autocomplete):not(.arfslider)~|~border-width||.arflite_main_div_{arf_form_id} .arf_fieldset .controls input[type=\"email\"]~|~border-width||.arflite_main_div_{arf_form_id} .arf_fieldset .controls input[type=\"phone\"]~|~border-width||.arflite_main_div_{arf_form_id} .arf_fieldset .controls input[type=\"tel\"]~|~border-width||.arflite_main_div_{arf_form_id} .arf_fieldset .controls input[type=\"hidden\"]~|~border-width||.arflite_main_div_{arf_form_id} .arf_fieldset .controls input[type=\"number\"]~|~border-width||.arflite_main_div_{arf_form_id} .arf_fieldset .controls input[type=\"url\"]~|~border-width||.arflite_main_div_{arf_form_id} .arf_fieldset .controls textarea~|~border-width||.arflite_main_div_{arf_form_id} .arf_fieldset .controls select~|~border-width||.arflite_main_div_{arf_form_id} .arf_fieldset .arf_editor_prefix_suffix_wrapper .arf_editor_prefix_icon~|~border-width||.arflite_main_div_{arf_form_id} .arf_fieldset .arf_editor_prefix_suffix_wrapper .arf_editor_suffix_icon~|~border-width||.arflite_main_div_{arf_form_id} .sltstandard_front .btn-group .arfbtn.dropdown-toggle~|~border-width||.arflite_main_div_{arf_form_id} .sltstandard_front .btn-group .arfbtn.dropdown-toggle:focus~|~border-width||.arflite_main_div_{arf_form_id} .sltstandard_front .btn-group.open .arfdropdown-menu.open~|~border-width||.arflite_main_div_{arf_form_id} .sltstandard_front .btn-group.open .arfdropdown-menu~|~border-width||.arflite_main_div_{arf_form_id} .sltstandard_front .arf-selectpicker-control.arf_form_field_picker dt~|~border-width||.arflite_main_div_{arf_form_id} .sltstandard_front .arf-selectpicker-control.arf_form_field_picker.open dt~|~border-width||.arflite_main_div_{arf_form_id} .sltstandard_front .arf-selectpicker-control.arf_form_field_picker dd ul~|~border-width","material":".arflite_main_div_{arf_form_id} .arf_materialize_form .controls input[type=\"text\"]:not(.inplace_field):not(.arf_field_option_input_text):not(.arf-select-dropdown):not(.arf_autocomplete):not(.arfslider):not(.arf_autocomplete)~|~border-bottom-width||.arflite_main_div_{arf_form_id} .arf_fieldset .controls input[type=\"email\"]~|~border-width||.arflite_main_div_{arf_form_id} .arf_fieldset .controls input[type=\"phone\"]~|~border-width||.arflite_main_div_{arf_form_id} .arf_fieldset .controls input[type=\"tel\"]~|~border-width||.arflite_main_div_{arf_form_id} .arf_fieldset .controls input[type=\"hidden\"]~|~border-width||.arflite_main_div_{arf_form_id} .arf_fieldset .controls input[type=\"number\"]~|~border-width||.arflite_main_div_{arf_form_id} .arf_fieldset .controls input[type=\"url\"]~|~border-width||.arflite_main_div_{arf_form_id} .arf_materialize_form textarea:not(.html_field_description):not(.g-recaptcha-response):not(.wp-editor-area)~|~border-width ||.arflite_main_div_{arf_form_id} .arf_fieldset .controls select~|~border-width||.arflite_main_div_{arf_form_id} .arf_fieldset .arf_editor_prefix_suffix_wrapper .arf_editor_prefix_icon~|~border-width||.arflite_main_div_{arf_form_id} .arf_fieldset .arf_editor_prefix_suffix_wrapper .arf_editor_suffix_icon~|~border-width||.arflite_main_div_{arf_form_id} .arf_materialize_form input[type=text]:not(.inplace_field):not(.arf_field_option_input_text):not(.arfslider):not(.arfhiddencolor)~|~border-bottom-width||.arflite_main_div_{arf_form_id} .arf_materialize_form .sltstandard_front .arf-selectpicker-control.arf_form_field_picker dt~|~border-width||.arflite_main_div_{arf_form_id} .arf_materialize_form .sltstandard_front .arf-selectpicker-control.arf_form_field_picker.open dt~|~border-width||.arflite_main_div_{arf_form_id} .arf_materialize_form .sltstandard_front .arf-selectpicker-control.arf_form_field_picker dd ul~|~border-width"}' data-arfstyleappend="true" data-arfstyleappendid="arf_{arf_form_id}_input_border_width" class="txtxbox_widget " value="<?php echo esc_attr( $newarr['arffieldborderwidthsetting'] ); ?>" size="4" />
												</div>
											</div>
										</div>
										<div class="arf_accordion_container_row_container">
											<div class="arf_accordion_inner_container" style="width:100% !important;"><?php echo esc_html__( 'Border Radius', 'arforms-form-builder' ); ?> </div>
											<div class="arf_dropdown_wrapper arf_accordion_inner_container">

												<?php if( !empty($newarr['arfinputstyle'] )){
														if( ($newarr['arfinputstyle'] == 'material') || ($newarr['arfinputstyle'] == 'rounded') || ($newarr['arfinputstyle'] == 'material_outlined')  ) {
															$arf_field_border_radius_disabled = true;
														}else {
															$arf_field_border_radius_disabled = false;
														}
												} ?>
												<?php $newarr['arf_field_radius'] = !empty($newarr['arf_field_radius']) ? $newarr['arf_field_radius'] : 'Desktop'; ?>     
												<?php
													$field_border_radius_style = array(
														"Desktop" => "<i class='fas fa-desktop'></i>",
														"Tablet" => "<i class='fas fa-tablet-alt'></i>",
														"Mobile" => "<i class='fas fa-mobile-alt'></i>",
													);

													$arflite_field_radius_attr = array(
														'onchange' => 'arflite_field_border_radius_func(this.value);'
													);

													echo $arflitemaincontroller->arflite_selectpicker_dom( 'arf_field_border_radius', 'arf_field_border_radius', 'arf_field_border_radius', 'margin-right:17px; width:50%', $newarr['arf_field_radius'],$arflite_field_radius_attr, $field_border_radius_style, false, array(), $arf_field_border_radius_disabled, array(), false, array(), false, 'arf_frm_width_icon_cls' ); //phpcs:ignore

												?>
											</div>
										</div>
												<?php 
                                                    if( $newarr['arfinputstyle'] == 'rounded') {
                                                        $newarr['border_radius_tablet'] = !empty( $newarr['border_radius_tablet']) ? $newarr['border_radius_tablet'] : '50';
                                                        $newarr['border_radius_mobile'] = !empty( $newarr['border_radius_mobile']) ? $newarr['border_radius_mobile'] : '50';
                                                    } else {
                                                        $newarr['border_radius_tablet'] = !empty( $newarr['border_radius_tablet']) ? $newarr['border_radius_tablet'] : '3';
                                                        $newarr['border_radius_mobile'] = !empty( $newarr['border_radius_mobile']) ? $newarr['border_radius_mobile'] : '3';
                                                    }
                                                ?>
											<div class="arf_accordion_content_container arf_align_center arf_accordion_container_mar" style="width:80%; margin:0px 25px 10px 25px;">
												<div class="arf_slider_wrapper arf_slider_desktop " style="<?php echo (($newarr['arf_field_radius'] == 'Desktop')) ? 'display:block;' : 'display:none;'; ?>">
													<div id="arflite_border_field_radius" class="noUi-target noUi-ltr noUi-horizontal noUi-txt-dir-ltr slider-track"></div>
													<input id="arfmainbordersetting_exs" class="arf_slider arf_slider_input" data-slider-id='arfmainbordersetting_exsSlider' type="text" data-slider-min="0" data-slider-max="50" data-slider-step="1" data-slider-value="<?php echo esc_attr( $newarr['border_radius'] ); ?>" />
													<div class="arf_slider_unit_data">
														<div class="arf_px arflite_float_left">0 px</div>
														<div class="arf_px arflite_float_right">50 px</div>
													</div>

													<input type="hidden" name="arfmbs" class="txtxbox_widget "  id="arfmainbordersetting" data-arfstyle="true" data-arfstyledata='{"standard":".arflite_main_div_{arf_form_id} .controls input[type=text]:not(.inplace_field):not(.arf_field_option_input_text):not(.arf_autocomplete):not(.arfslider)~|~border-radius||body:not(.rtl) .arflite_main_div_{arf_form_id} .arf_editor_prefix_suffix_wrapper .arf_editor_prefix_icon~|~border-top-left-radius||body.rtl .arflite_main_div_{arf_form_id} .arf_editor_prefix_suffix_wrapper .arf_editor_prefix_icon~|~border-top-right-radius||body:not(.rtl) .arflite_main_div_{arf_form_id} .arf_editor_prefix_suffix_wrapper .arf_editor_suffix_icon ~|~border-top-right-radius||body.rtl .arflite_main_div_{arf_form_id} .arf_editor_prefix_suffix_wrapper .arf_editor_suffix_icon ~|~border-top-left-radius||body:not(.rtl) .arflite_main_div_{arf_form_id} .arf_editor_prefix_suffix_wrapper .arf_editor_prefix_icon~|~border-bottom-left-radius||body.rtl .arflite_main_div_{arf_form_id} .arf_editor_prefix_suffix_wrapper .arf_editor_prefix_icon~|~border-bottom-right-radius||body:not(.rtl) .arflite_main_div_{arf_form_id} .arf_editor_prefix_suffix_wrapper .arf_editor_suffix_icon ~|~border-bottom-right-radius||body.rtl .arflite_main_div_{arf_form_id} .arf_editor_prefix_suffix_wrapper .arf_editor_suffix_icon ~|~border-bottom-left-radius||.arflite_main_div_{arf_form_id} .arf_fieldset .controls input[type=\"email\"]~|~border-radius||.arflite_main_div_{arf_form_id} .arf_fieldset .controls input[type=\"phone\"]~|~border-radius||.arflite_main_div_{arf_form_id} .arf_fieldset .controls input[type=\"tel\"]~|~border-radius||.arflite_main_div_{arf_form_id} .arf_fieldset .controls input[type=\"hidden\"]~|~border-radius||.arflite_main_div_{arf_form_id} .arf_fieldset .controls input[type=\"number\"]~|~border-radius||.arflite_main_div_{arf_form_id} .arf_fieldset .controls input[type=\"url\"]~|~border-radius||.arflite_main_div_{arf_form_id} .arf_fieldset .controls textarea~|~border-radius||.arflite_main_div_{arf_form_id} .arf_fieldset .controls select~|~border-radius||.arflite_main_div_{arf_form_id} .sltstandard_front .btn-group .arfbtn.dropdown-toggle~|~border-radius||.arflite_main_div_{arf_form_id} .sltstandard_front .btn-group.open .arfbtn.dropdown-toggle~|~border-top-left-radius-custom||.arflite_main_div_{arf_form_id} .sltstandard_front .btn-group.open .arfbtn.dropdown-toggle~|~border-top-right-radius-custom||.arflite_main_div_{arf_form_id} .sltstandard_front .arf-selectpicker-control.arf_form_field_picker:not(.open) dt~|~border-radius||.arflite_main_div_{arf_form_id} .sltstandard_front .arf-selectpicker-control.arf_form_field_picker.open:not(.open_from_top) dt~|~border-top-left-radius||.arflite_main_div_{arf_form_id} .sltstandard_front .arf-selectpicker-control.arf_form_field_picker.open:not(.open_from_top) dt~|~border-top-right-radius||.arflite_main_div_{arf_form_id} .sltstandard_front .arf-selectpicker-control.arf_form_field_picker.open.open_from_top dt~|~border-bottom-left-radius||.arflite_main_div_{arf_form_id} .sltstandard_front .arf-selectpicker-control.arf_form_field_picker.open.open_from_top dt~|~border-bottom-right-radius","material":".arflite_main_div_{arf_form_id} .arf_fieldset .controls input[type=\"text\"]:not(.inplace_field):not(.arf_field_option_input_text):not(.arf-select-dropdown)~|~border-radius||.arflite_main_div_{arf_form_id} .arf_fieldset .controls input[type=\"email\"]~|~border-radius||.arflite_main_div_{arf_form_id} .arf_fieldset .controls input[type=\"phone\"]~|~border-radius||.arflite_main_div_{arf_form_id} .arf_fieldset .controls input[type=\"tel\"]~|~border-radius||.arflite_main_div_{arf_form_id} .arf_fieldset .controls input[type=\"hidden\"]~|~border-radius||.arflite_main_div_{arf_form_id} .arf_fieldset .controls input[type=\"number\"]~|~border-radius||.arflite_main_div_{arf_form_id} .arf_fieldset .controls input[type=\"url\"]~|~border-radius||.arflite_main_div_{arf_form_id} .arf_fieldset .controls textarea~|~border-radius||.arflite_main_div_{arf_form_id} .arf_fieldset .controls select~|~border-radius"}' data-arfstyleappend="true" data-arfstyleappendid="arf_{arf_form_id}_input_border_radius" value="<?php echo esc_attr( $newarr['border_radius'] ); ?>" size="4" />
												</div>

												<div class="arf_slider_wrapper arf_slider_tablet" style="<?php echo (($newarr['arf_field_radius'] == 'Tablet')) ? 'display:block;' : 'display:none;'; ?>">
                                                        <div id="arf_arfmainbordersetting_tablet" class="noUi-target noUi-ltr noUi-horizontal noUi-txt-dir-ltr slider-track"></div>
                                                        <input id="arfmainbordersetting_exs_tablet" class="arf_slider_input" data-slider-id='arfmainbordersetting_exsSlider_tablet' type="text" data-slider-min="0" data-slider-max="50" data-slider-step="1" data-slider-value="<?php echo esc_attr($newarr['border_radius_tablet']) ?>" />
                                                        <div class="arf_slider_unit_data">
                                                            <div class="arf_px" style="float:left;">0 px</div>
                                                            <div class="arf_px" style="float:right;">50 px</div>
                                                        </div>
                                                        <input type="hidden" name="arfmbs_tablet" style="width:100px;" class="txtxbox_widget" id="arfmainbordersetting_tablet" value="<?php echo esc_attr($newarr['border_radius_tablet']) ?>" size="4" />
                                                    </div>
													<div class="arf_slider_wrapper arf_slider_mobile" style="<?php echo (($newarr['arf_field_radius'] == 'Mobile')) ? 'display:block;' : 'display:none;'; ?>">
                                                        <div id="arf_arfmainbordersetting_mobile" class="noUi-target noUi-ltr noUi-horizontal noUi-txt-dir-ltr slider-track"></div>
                                                        <input id="arfmainbordersetting_exs_mobile" class="arf_slider_input" data-slider-id='arfmainbordersetting_exsSlider_mobile' type="text" data-slider-min="0" data-slider-max="50" data-slider-step="1" data-slider-value="<?php echo esc_attr($newarr['border_radius_mobile']) ?>" />
                                                        <div class="arf_slider_unit_data">
                                                            <div class="arf_px" style="float:left;">0 px</div>
                                                            <div class="arf_px" style="float:right;">50 px</div>
                                                        </div>
                                                        <input type="hidden" name="arfmbs_mobile" style="width:100px;" class="txtxbox_widget" id="arfmainbordersetting_mobile" value="<?php echo esc_attr($newarr['border_radius_mobile']) ?>" size="4" />
                                                    </div>
										</div>
										<div class="arf_accordion_container_row_container">
											<div class="arf_accordion_inner_title"><?php echo esc_html__( 'Border Style', 'arforms-form-builder' ); ?></div>
										</div>
										<div class="arf_accordion_container_row_container_left">
											<div class="arf_toggle_button_group arf_three_button_group margin-right_5px">
												<?php $newarr['arffieldborderstylesetting'] = isset( $newarr['arffieldborderstylesetting'] ) ? $newarr['arffieldborderstylesetting'] : 'solid'; ?>
												<label class="arf_toggle_btn <?php echo ( $newarr['arffieldborderstylesetting'] == 'dashed' ) ? 'arf_success' : ''; ?>"><input type="radio" name="arffbss" id="arf_input_border_style_dashed" data-arfstyle="true" data-arfstyledata='{"standard":".arflite_main_div_{arf_form_id} input[type=\"text\"]:not(.inplace_field):not(.arf_field_option_input_text):not(.arf_autocomplete):not(.arfslider)~|~border-style||.arflite_main_div_{arf_form_id}  input[type=\"email\"]~|~border-style||.arflite_main_div_{arf_form_id}  input[type=\"phone\"]~|~border-style||.arflite_main_div_{arf_form_id}  input[type=\"tel\"]~|~border-style||.arflite_main_div_{arf_form_id}  input[type=\"hidden\"]~|~border-style||.arflite_main_div_{arf_form_id}  input[type=\"number\"]~|~border-style||.arflite_main_div_{arf_form_id}  input[type=\"url\"]~|~border-style||.arflite_main_div_{arf_form_id} .arf_fieldset .controls textarea~|~border-style||.arflite_main_div_{arf_form_id} .arf_fieldset .controls select~|~border-style||.arflite_main_div_{arf_form_id} .arf_fieldset .arf_editor_prefix_suffix_wrapper .arf_editor_prefix_icon~|~border-style||.arflite_main_div_{arf_form_id} .arf_fieldset .arf_editor_prefix_suffix_wrapper .arf_editor_suffix_icon~|~border-style||.arflite_main_div_{arf_form_id} .sltstandard_front .btn-group .arfbtn.dropdown-toggle~|~border-style||.arflite_main_div_{arf_form_id} .sltstandard_front .btn-group .arfbtn.dropdown-toggle:focus~|~border-style||.arflite_main_div_{arf_form_id} .sltstandard_front .btn-group.open .arfdropdown-menu.open~|~border-style||.arflite_main_div_{arf_form_id} .sltstandard_front .arf-selectpicker-control.arf_form_field_picker dt~|~border-style||.arflite_main_div_{arf_form_id} .sltstandard_front .arf-selectpicker-control.arf_form_field_picker.open dt~|~border-style||.arflite_main_div_{arf_form_id} .sltstandard_front .arf-selectpicker-control.arf_form_field_picker dd ul~|~border-style","material":".arflite_main_div_{arf_form_id} .arf_materialize_form.arf_fieldset .controls input[type=\"text\"]:not(.inplace_field):not(.arf_field_option_input_text):not(.arfslider)~|~border-bottom-style||.arflite_main_div_{arf_form_id} .arf_fieldset .controls input[type=\"email\"]~|~border-bottom-style||.arflite_main_div_{arf_form_id} .arf_fieldset .controls input[type=\"phone\"]~|~border-bottom-style||.arflite_main_div_{arf_form_id} .arf_fieldset .controls input[type=\"tel\"]~|~border-bottom-style||.arflite_main_div_{arf_form_id} .arf_fieldset .controls input[type=\"hidden\"]~|~border-bottom-style||.arflite_main_div_{arf_form_id} .arf_fieldset .controls input[type=\"number\"]~|~border-bottom-style||.arflite_main_div_{arf_form_id} .arf_fieldset .controls input[type=\"url\"]~|~border-bottom-style||.arflite_main_div_{arf_form_id} .arf_materialize_form textarea:not(.html_field_description):not(.g-recaptcha-response):not(.wp-editor-area)~|~border-bottom-style ||.arflite_main_div_{arf_form_id} .arf_fieldset .controls select~|~border-bottom-style||.arflite_main_div_{arf_form_id} .arf_fieldset .arf_editor_prefix_suffix_wrapper .arf_editor_prefix_icon~|~border-bottom-style||.arflite_main_div_{arf_form_id} .arf_fieldset .arf_editor_prefix_suffix_wrapper .arf_editor_suffix_icon~|~border-bottom-style||.arflite_main_div_{arf_form_id} .arf_materialize_form .sltstandard_front .arf-selectpicker-control.arf_form_field_picker dt~|~border-bottom-style||.arflite_main_div_{arf_form_id} .arf_materialize_form .sltstandard_front .arf-selectpicker-control.arf_form_field_picker.open dt~|~border-bottom-style||.arflite_main_div_{arf_form_id} .arf_materialize_form .sltstandard_front .arf-selectpicker-control.arf_form_field_picker dd ul~|~border-style"}' data-arfstyleappend="true" data-arfstyleappendid="arf_{arf_form_id}_input_border_style" class="visuallyhidden" value="dashed" <?php checked( $newarr['arffieldborderstylesetting'], 'dashed' ); ?> /><?php echo esc_html__( 'Dashed', 'arforms-form-builder' ); ?></label>
												<label class="arf_toggle_btn <?php echo ( $newarr['arffieldborderstylesetting'] == 'dotted' ) ? 'arf_success' : ''; ?>"><input type="radio" name="arffbss" id="arf_input_border_style_dotted" data-arfstyle="true" data-arfstyledata='{"standard":".arflite_main_div_{arf_form_id} input[type=\"text\"]:not(.inplace_field):not(.arf_field_option_input_text):not(.arf_autocomplete):not(.arfslider)~|~border-style||.arflite_main_div_{arf_form_id} input[type=\"email\"]~|~border-style||.arflite_main_div_{arf_form_id} input[type=\"phone\"]~|~border-style||.arflite_main_div_{arf_form_id} input[type=\"tel\"]~|~border-style||.arflite_main_div_{arf_form_id} input[type=\"hidden\"]~|~border-style||.arflite_main_div_{arf_form_id} input[type=\"number\"]~|~border-style||.arflite_main_div_{arf_form_id} input[type=\"url\"]~|~border-style||.arflite_main_div_{arf_form_id} .arf_fieldset .controls textarea~|~border-style||.arflite_main_div_{arf_form_id} .arf_fieldset .controls select~|~border-style||.arflite_main_div_{arf_form_id} .arf_fieldset .arf_editor_prefix_suffix_wrapper .arf_editor_prefix_icon~|~border-style||.arflite_main_div_{arf_form_id} .arf_fieldset .arf_editor_prefix_suffix_wrapper .arf_editor_suffix_icon~|~border-style||.arflite_main_div_{arf_form_id} .sltstandard_front .btn-group .arfbtn.dropdown-toggle~|~border-style||.arflite_main_div_{arf_form_id} .sltstandard_front .btn-group .arfbtn.dropdown-toggle:focus~|~border-style||.arflite_main_div_{arf_form_id} .sltstandard_front .btn-group.open .arfdropdown-menu.open~|~border-style||.arflite_main_div_{arf_form_id} .sltstandard_front .arf-selectpicker-control.arf_form_field_picker dt~|~border-style||.arflite_main_div_{arf_form_id} .sltstandard_front .arf-selectpicker-control.arf_form_field_picker.open dt~|~border-style||.arflite_main_div_{arf_form_id} .sltstandard_front .arf-selectpicker-control.arf_form_field_picker dd ul~|~border-style","material":".arflite_main_div_{arf_form_id} .arf_materialize_form.arf_fieldset .controls input[type=\"text\"]:not(.inplace_field):not(.arf_field_option_input_text):not(.arfslider)~|~border-bottom-style||.arflite_main_div_{arf_form_id} .arf_fieldset .controls input[type=\"email\"]~|~border-bottom-style||.arflite_main_div_{arf_form_id} .arf_fieldset .controls input[type=\"phone\"]~|~border-bottom-style||.arflite_main_div_{arf_form_id} .arf_fieldset .controls input[type=\"tel\"]~|~border-bottom-style||.arflite_main_div_{arf_form_id} .arf_fieldset .controls input[type=\"hidden\"]~|~border-bottom-style||.arflite_main_div_{arf_form_id} .arf_fieldset .controls input[type=\"number\"]~|~border-bottom-style||.arflite_main_div_{arf_form_id} .arf_fieldset .controls input[type=\"url\"]~|~border-bottom-style||.arflite_main_div_{arf_form_id} .arf_materialize_form textarea:not(.html_field_description):not(.g-recaptcha-response):not(.wp-editor-area)~|~border-bottom-style  ||.arflite_main_div_{arf_form_id} .arf_fieldset .controls select~|~border-style||.arflite_main_div_{arf_form_id} .arf_fieldset .arf_editor_prefix_suffix_wrapper .arf_editor_prefix_icon~|~border-bottom-style||.arflite_main_div_{arf_form_id} .arf_fieldset .arf_editor_prefix_suffix_wrapper .arf_editor_suffix_icon~|~border-bottom-style||.arflite_main_div_{arf_form_id} .arf_materialize_form .sltstandard_front .arf-selectpicker-control.arf_form_field_picker dt~|~border-bottom-style||.arflite_main_div_{arf_form_id} .arf_materialize_form .sltstandard_front .arf-selectpicker-control.arf_form_field_picker.open dt~|~border-bottom-style||.arflite_main_div_{arf_form_id} .arf_materialize_form .sltstandard_front .arf-selectpicker-control.arf_form_field_picker dd ul~|~border-style"}' data-arfstyleappend="true" data-arfstyleappendid="arf_{arf_form_id}_input_border_style" class="visuallyhidden" value="dotted" <?php checked( $newarr['arffieldborderstylesetting'], 'dotted' ); ?> /><?php echo esc_html__( 'Dotted', 'arforms-form-builder' ); ?></label>
												<label class="arf_toggle_btn <?php echo ( $newarr['arffieldborderstylesetting'] == 'solid' ) ? 'arf_success' : ''; ?>"><input type="radio" name="arffbss" id="arf_input_border_style_solid" data-arfstyle="true" data-arfstyledata='{"standard":".arflite_main_div_{arf_form_id} input[type=\"text\"]:not(.inplace_field):not(.arf_field_option_input_text):not(.arf_autocomplete):not(.arfslider)~|~border-style||.arflite_main_div_{arf_form_id} input[type=\"email\"]~|~border-style||.arflite_main_div_{arf_form_id} input[type=\"phone\"]~|~border-style||.arflite_main_div_{arf_form_id} input[type=\"tel\"]~|~border-style||.arflite_main_div_{arf_form_id} input[type=\"hidden\"]~|~border-style||.arflite_main_div_{arf_form_id} input[type=\"number\"]~|~border-style||.arflite_main_div_{arf_form_id} input[type=\"url\"]~|~border-style||.arflite_main_div_{arf_form_id} .arf_fieldset .controls textarea~|~border-style||.arflite_main_div_{arf_form_id} .arf_fieldset .controls select~|~border-style||.arflite_main_div_{arf_form_id} .arf_fieldset .arf_editor_prefix_suffix_wrapper .arf_editor_prefix_icon~|~border-style||.arflite_main_div_{arf_form_id} .arf_fieldset .arf_editor_prefix_suffix_wrapper .arf_editor_suffix_icon~|~border-style||.arflite_main_div_{arf_form_id} .sltstandard_front .btn-group .arfbtn.dropdown-toggle~|~border-style||.arflite_main_div_{arf_form_id} .sltstandard_front .btn-group .arfbtn.dropdown-toggle:focus~|~border-style||.arflite_main_div_{arf_form_id} .sltstandard_front .btn-group.open .arfdropdown-menu.open~|~border-style||.arflite_main_div_{arf_form_id} .sltstandard_front .arf-selectpicker-control.arf_form_field_picker dt~|~border-style||.arflite_main_div_{arf_form_id} .sltstandard_front .arf-selectpicker-control.arf_form_field_picker.open dt~|~border-style||.arflite_main_div_{arf_form_id} .sltstandard_front .arf-selectpicker-control.arf_form_field_picker dd ul~|~border-style","material":".arflite_main_div_{arf_form_id} .arf_materialize_form.arf_fieldset .controls input[type=\"text\"]:not(.inplace_field):not(.arf_field_option_input_text):not(.arfslider)~|~border-bottom-style||.arflite_main_div_{arf_form_id} .arf_fieldset .controls input[type=\"email\"]~|~border-bottom-style||.arflite_main_div_{arf_form_id} .arf_fieldset .controls input[type=\"phone\"]~|~border-bottom-style||.arflite_main_div_{arf_form_id} .arf_fieldset .controls input[type=\"tel\"]~|~border-bottom-style||.arflite_main_div_{arf_form_id} .arf_fieldset .controls input[type=\"hidden\"]~|~border-bottom-style||.arflite_main_div_{arf_form_id} .arf_fieldset .controls input[type=\"number\"]~|~border-bottom-style||.arflite_main_div_{arf_form_id} .arf_fieldset .controls input[type=\"url\"]~|~border-bottom-style||.arflite_main_div_{arf_form_id} .arf_materialize_form textarea:not(.html_field_description):not(.g-recaptcha-response):not(.wp-editor-area)~|~border-bottom-style ||.arflite_main_div_{arf_form_id} .arf_fieldset .controls select~|~border-style||.arflite_main_div_{arf_form_id} .arf_fieldset .arf_editor_prefix_suffix_wrapper .arf_editor_prefix_icon~|~border-bottom-style||.arflite_main_div_{arf_form_id} .arf_fieldset .arf_editor_prefix_suffix_wrapper .arf_editor_suffix_icon~|~border-bottom-style||.arflite_main_div_{arf_form_id} .arf_materialize_form .sltstandard_front .arf-selectpicker-control.arf_form_field_picker dt~|~border-bottom-style||.arflite_main_div_{arf_form_id} .arf_materialize_form .sltstandard_front .arf-selectpicker-control.arf_form_field_picker.open dt~|~border-bottom-style||.arflite_main_div_{arf_form_id} .arf_materialize_form .sltstandard_front .arf-selectpicker-control.arf_form_field_picker dd ul~|~border-style"}' data-arfstyleappend="true" data-arfstyleappendid="arf_{arf_form_id}_input_border_style" class="visuallyhidden" value="solid" <?php checked( $newarr['arffieldborderstylesetting'], 'solid' ); ?> /><?php echo esc_html__( 'Solid', 'arforms-form-builder' ); ?></label>
											</div>
										</div>
										<div class="arf_accordion_container_row_separator"></div>
										<div class="arf_accordion_container_row arf_padding">
											<div class="arf_accordion_outer_title"><?php echo esc_html__( 'Calendar Date Format', 'arforms-form-builder' ); ?></div>
										</div>
										<div class="arf_accordion_container_row_container">
											<div class="arf_accordion_inner_container"><?php echo esc_html__( 'Date Format', 'arforms-form-builder' ); ?></div>
											<div class="arf_accordion_inner_container">
												<div class="arf_dropdown_wrapper" style="margin-right: 5px;float:right;">
													<?php
													$wp_format_date = get_option( 'date_format' );

													if ( $wp_format_date == 'F j, Y' || $wp_format_date == 'm/d/Y' ) {
														?>
														<div class="sltstandard1" style="float:left;">


															<?php
															$arf_selbx_dt_format = '';
															if ( $newarr['date_format'] == 'MMMM D, YYYY' ) {
																$arf_selbx_dt_format = date( 'F d, Y', current_time( 'timestamp' ) );
															} elseif ( $newarr['date_format'] == 'MMM D, YYYY' ) {
																$arf_selbx_dt_format = date( 'M d, Y', current_time( 'timestamp' ) );
															} else {
																$arf_selbx_dt_format = date( 'm/d/Y', current_time( 'timestamp' ) );
															}

															$wp_format_date_opts = array(
																'MM/DD/YYYY' => date( 'm/d/Y', current_time( 'timestamp' ) ),
																'MMM D, YYYY' => date( 'M d, Y', current_time( 'timestamp' ) ),
																'MMMM D, YYYY' => date( 'F d, Y', current_time( 'timestamp' ) ),
															);

															if ( ! ( array_key_exists( $newarr['date_format'], $wp_format_date_opts ) ) ) {

																$wp_format_date_opts[ $newarr['date_format'] ] = date( $arflite_date_check_arr[ $newarr['date_format'] ], current_time( 'timestamp' ) );
															}

															$wp_format_date_attr = array(
																'onchange' => 'arflite_change_date_format_new()',
															);

															echo $arflitemaincontroller->arflite_selectpicker_dom( 'arffdaf', 'frm_date_format', '', 'width:155px', $newarr['date_format'], $wp_format_date_attr, $wp_format_date_opts ); //phpcs:ignore

															?>

														</div>
														<?php

													} elseif ( $wp_format_date == 'd/m/Y' ) {

														?>

														<div class="sltstandard1" style="float:left;">

															<?php
															$arf_selbx_dt_format = '';
															if ( $newarr['date_format'] == 'D MMMM, YYYY' ) {
																$arf_selbx_dt_format = date( 'd F, Y', current_time( 'timestamp' ) );
															} elseif ( $newarr['date_format'] == 'D MMM, YYYY' ) {
																$arf_selbx_dt_format = date( 'd M, Y', current_time( 'timestamp' ) );
															} else {
																 $arf_selbx_dt_format = date( 'd/m/Y', current_time( 'timestamp' ) );
															}

															$wp_format_date_opts = array(
																'DD/MM/YYYY' => date( 'd/m/Y', current_time( 'timestamp' ) ),
																'D MMM, YYYY' => date( 'd M, Y', current_time( 'timestamp' ) ),
																'D MMMM, YYYY' => date( 'd F, Y', current_time( 'timestamp' ) ),
															);

															if ( ! ( array_key_exists( $newarr['date_format'], $wp_format_date_opts ) ) ) {

																$wp_format_date_opts[ $newarr['date_format'] ] = date( $arflite_date_check_arr[ $newarr['date_format'] ], current_time( 'timestamp' ) );
															}

															$wp_format_date_attr = array(
																'onchange' => 'arflite_change_date_format_new()',
															);

															echo $arflitemaincontroller->arflite_selectpicker_dom( 'arffdaf', 'frm_date_format', '', 'width:122px', $newarr['date_format'],  $wp_format_date_attr, $wp_format_date_opts ); //phpcs:ignore

															?>

														</div>



													<?php } elseif ( $wp_format_date == 'Y/m/d' ) { ?>

														<div class="sltstandard1" style="float:left;">

															<?php
															$arf_selbx_dt_format = '';
															if ( $newarr['date_format'] == 'YYYY, MMMM D' ) {
																$arf_selbx_dt_format = date( 'Y, F d', current_time( 'timestamp' ) );
															} elseif ( $newarr['date_format'] == 'YYYY, MMM D' ) {
																$arf_selbx_dt_format = date( 'Y, M d', current_time( 'timestamp' ) );
															} else {
																$arf_selbx_dt_format = date( 'Y/m/d', current_time( 'timestamp' ) );
															}

															$wp_format_date_opts = array(
																'YYYY/MM/DD' => date( 'Y/m/d', current_time( 'timestamp' ) ),
																'YYYY, MMM DD' => date( 'Y, M d', current_time( 'timestamp' ) ),
																'YYYY, MMMM D' => date( 'Y, F d', current_time( 'timestamp' ) ),
															);

															if ( ! ( array_key_exists( $newarr['date_format'], $wp_format_date_opts ) ) ) {

																$wp_format_date_opts[ $newarr['date_format'] ] = date( $arflite_date_check_arr[ $newarr['date_format'] ], current_time( 'timestamp' ) );
															}

															$wp_format_date_attr = array(
																'onchange' => 'arflite_change_date_format_new()',
															);

															echo $arflitemaincontroller->arflite_selectpicker_dom( 'arffdaf', 'frm_date_format', '', 'width:122px', $newarr['date_format'], $wp_format_date_attr, $wp_format_date_opts ); //phpcs:ignore
															?>
														</div>



													<?php } elseif ( $wp_format_date == 'd.F.y' || $wp_format_date == 'd.m.Y' || $wp_format_date == 'Y.m.d' || $wp_format_date == 'd. F Y' || $wp_format_date == 'm.d.Y' ) { ?>
															
														<div class="sltstandard1" style="float:left;">

															<?php
															$arf_selbx_dt_format = '';

															if ( $newarr['date_format'] == 'D.MM.YYYY' ) {
																$arf_selbx_dt_format = date( 'd.m.Y', current_time( 'timestamp' ) );
															} elseif ( $newarr['date_format'] == 'YYYY.MM.D' ) {
																$arf_selbx_dt_format = date( 'Y.m.d', current_time( 'timestamp' ) );
															} elseif ( $newarr['date_format'] == 'D. MMMM YYYY' ) {
																$arf_selbx_dt_format = date( 'd. F Y', current_time( 'timestamp' ) );
															} elseif ( $newarr['date_format'] == 'DD/MM/YYYY' ) {
																$arf_selbx_dt_format = date( 'd.m.Y', current_time( 'timestamp' ) );
															} else {
																$arf_selbx_dt_format = date( 'd.F.y', current_time( 'timestamp' ) );

															}

															$wp_format_date_opts = array(
																'D.MMMM.YY' => date( 'd.F.y', current_time( 'timestamp' ) ),
																'D.MM.YYYY' => date( 'd.m.Y', current_time( 'timestamp' ) ),
																'YYYY.MM.D' => date( 'Y.m.d', current_time( 'timestamp' ) ),
																'D. MMMM YYYY' => date( 'd. F Y', current_time( 'timestamp' ) ),
																'DD/MM/YYYY' => date( 'd.m.Y', current_time( 'timestamp' ) ),
																'MM.D.YYYY' => date( 'm.d.Y', current_time( 'timestamp' ) ),
															);

															if ( ! ( array_key_exists( $newarr['date_format'], $wp_format_date_opts ) ) ) {

																$wp_format_date_opts[ $newarr['date_format'] ] = date( $arflite_date_check_arr[ $newarr['date_format'] ], current_time( 'timestamp' ) );
															}

															$wp_format_date_attr = array(
																'onchange' => 'arflite_change_date_format_new()',
															);

																echo $arflitemaincontroller->arflite_selectpicker_dom( 'arffdaf', 'frm_date_format', '', 'width:122px', $newarr['date_format'], $wp_format_date_attr, $wp_format_date_opts ); //phpcs:ignore
															?>
														</div>
													
													<?php } else { ?>

														<div class="sltstandard1" style="float:left;">

															<?php
															$arf_selbx_dt_format = '';
															if ( $newarr['date_format'] == 'MMMM D, YYYY' ) {
																$arf_selbx_dt_format = date( 'F d, Y', current_time( 'timestamp' ) );
															} elseif ( $newarr['date_format'] == 'MMM D, YYYY' ) {
																$arf_selbx_dt_format = date( 'M d, Y', current_time( 'timestamp' ) );
															} elseif ( $newarr['date_format'] == 'YYYY/MM/DD' ) {
																$arf_selbx_dt_format = date( 'Y/m/d', current_time( 'timestamp' ) );
															} elseif ( $newarr['date_format'] == 'MM/DD/YYYY' ) {
																$arf_selbx_dt_format = date( 'm/d/Y', current_time( 'timestamp' ) );
															} else {
																$arf_selbx_dt_format = date( 'd/m/Y', current_time( 'timestamp' ) );
															}

															$wp_format_date_opts = array(
																'DD/MM/YYYY' => date( 'd/m/y', current_time( 'timestamp' ) ),
																'MM/DD/YYYY' => date( 'm/d/Y', current_time( 'timestamp' ) ),
																'YYYY/MM/DD' => date( 'Y/m/d', current_time( 'timestamp' ) ),
																'MMM D, YYYY' => date( 'M d, Y', current_time( 'timestamp' ) ),
																'MMMM D, YYYY' => date( 'F d, Y', current_time( 'timestamp' ) ),
															);

															if ( ! ( array_key_exists( $newarr['date_format'], $wp_format_date_opts ) ) ) {

																$wp_format_date_opts[ $newarr['date_format'] ] = date( $arflite_date_check_arr[ $newarr['date_format'] ], current_time( 'timestamp' ) );
															}

															$wp_format_date_attr = array(
																'onchange' => 'arflite_change_date_format_new()',
															);

															echo $arflitemaincontroller->arflite_selectpicker_dom( 'arffdaf', 'frm_date_format', '', 'width:122px', $newarr['date_format'], $wp_format_date_attr, $wp_format_date_opts ); //phpcs:ignore

															?>
														</div>
														<?php
													}
													?>
												</div>
											</div>
										</div>

										 <div class="arf_accordion_container_row_separator"></div>
										<div class="arf_accordion_container_row arf_padding">
											<div class="arf_accordion_outer_title"><?php echo addslashes( esc_html__( 'PageBreak Timer Settings', 'arforms-form-builder' ) ); //phpcs:ignore ?><span class="arflite_pro_version_notice">(Premium)</span></div>
										</div>
										 <div class="arf_help_div">
											<?php echo esc_html__( '(When this settings is enabled, previous page clickable functionality will not work.)', 'arforms-form-builder' ); ?></div>
										
										<div class="arf_accordion_container_row_container">
											<div class="arf_accordion_inner_container"><?php echo addslashes( esc_html__( 'Add Timer', 'arforms-form-builder' ) ); //phpcs:ignore ?> </div>
											<div class="arf_accordion_inner_container arf_float_right arfmarginright4">
												<label class="arf_js_switch_label">
													<span><?php echo addslashes( esc_html__( 'No', 'arforms-form-builder' ) ); //phpcs:ignore ?>&nbsp;</span>
												</label>
												<span class="arf_js_switch_wrapper arf_no_transition">
													<input type="checkbox" class="js-switch arf_restricted_control " name="arfsettimer" id="arfsettimer" value="1" <?php echo ( isset( $newarr['arfsettimer'] ) && $newarr['arfsettimer'] == 1 ) ? 'checked="checked"' : ''; ?>/>
													<span class="arf_js_switch"></span>
												</span>
												<label class="arf_js_switch_label">
													<span>&nbsp;<?php echo addslashes( esc_html__( 'Yes', 'arforms-form-builder' ) ); //phpcs:ignore ?></span>
												</label>
											</div>
										</div>

										<div class="arf_accordion_container_row_container" id="arfpagebreak_settimeron" <?php if ( isset( $newarr['arfsettimer'] ) && $newarr['arfsettimer'] == 0 ) { ?> style="display: none;" <?php } ?> >
											<div class="arf_accordion_inner_title"><?php echo addslashes( esc_html__('Set Timer On', 'arforms-form-builder' ) ); //phpcs:ignore ?></div>
										</div>
										<div class="arf_accordion_container_row_container_left" style="margin-left: 3px;">   
											<div class="arf_toggle_button_group arf_three_button_group">
												<?php $newarr['arfpagebreaksettimeron'] = isset( $newarr['arfpagebreaksettimeron'] ) ? $newarr['arfpagebreaksettimeron'] : 'overallform'; ?>
												<label class="arf_toggle_btn <?php echo ( $newarr['arfpagebreaksettimeron'] == 'individualstep' ) ? 'arf_success' : ''; ?>">
													<input type="radio" name="arfpagebreaksettimeron" class="visuallyhidden arf_restricted_control " id="arfpagebreaksettimeron_individualstep" value="individualstep" <?Php checked( $newarr['arfpagebreaksettimeron'], 'individualstep' ); ?> /><?php echo addslashes( esc_html__( 'Individual Steps', 'arforms-form-builder' ) ); //phpcs:ignore ?>
												</label>
												<label class="arf_toggle_btn <?php echo ( $newarr['arfpagebreaksettimeron'] == 'overallform' ) ? 'arf_success' : ''; ?>">
													<input type="radio" name="arfpagebreaksettimeron" class="visuallyhidden arf_restricted_control " id="arfpagebreaksettimeron_overallform" value="overallform" <?Php checked( $newarr['arfpagebreaksettimeron'], 'overallform' ); ?> /><?php echo addslashes( esc_html__( 'Overall Steps', 'arforms-form-builder' ) ); //phpcs:ignore ?>
												</label>
											</div>
										</div>
											<?php
												$newarr['showunits_breakfield'] = isset( $newarr['showunits_breakfield'] ) ? $newarr['showunits_breakfield'] : 'normal';
												$total_showunits                = '';
												if ( $newarr['showunits_breakfield'] != 'normal' ) {
													$total_showunits = ', ' . $newarr['showunits_breakfield'];
												}
											?>
										<div class="arf_accordion_container_row_container" id="showunits_breakfield" <?php if ( isset( $newarr['arfsettimer'] ) && $newarr['arfsettimer'] == 0 ) { ?> style="display: none;" <?php } ?> >
											<div class="arf_accordion_inner_title"><?php echo addslashes( esc_html__( 'Show Units', 'arforms-form-builder' ) ); //phpcs:ignore ?></div>
										</div>
										<div class="arf_accordion_container_row_container_left">
											<div class="arf_toggle_button_group arf_three_button_group" style="margin-right: 5px;">
												<input id="arfunitbreak" name="showunits_breakfield" value="<?php echo esc_attr( $newarr['showunits_breakfield'] ); ?>" type="hidden" data-default-font="<?php echo esc_attr( $newarr['showunits_breakfield'] ); ?>" />

												<?php $arf_show_unit_arr = explode( ',', $newarr['showunits_breakfield'] ); ?>

												<span class="arf_unit_btn arf_unit_sec arf_restricted_control <?php echo ( in_array( 'sec', $arf_show_unit_arr ) ) ? 'active' : ''; ?>" day_val="2"  data-id="arfunitbreak" data-val='sec'>Sec</span>

												<span class="arf_unit_btn arf_unit_min arf_restricted_control <?php echo ( in_array( 'min', $arf_show_unit_arr ) ) ? 'active' : ''; ?>" day_val="1"  data-id="arfunitbreak" data-val='min'>Min</span>

												<span class="arf_unit_btn arf_unit_hrs arf_restricted_control<?php echo ( in_array( 'hrs', $arf_show_unit_arr ) ) ? 'active' : ''; ?>" day_val="0"  data-id="arfunitbreak" data-val="hrs">Hrs</span>

											</div>
										</div>
									

										<div class="arf_accordion_container_row_container" id="settimerval"  <?php if ( isset( $newarr['arfsettimer'] ) && $newarr['arfsettimer'] == 0 ) { ?> style="display: none; <?php } ?>"> 
											<div class="arf_accordion_inner_title"><?php echo addslashes( esc_html__( 'Set Timer', 'arforms-form-builder' ) ); //phpcs:ignore ?></div>
										</div>
										<div class="arf_accordion_container_row_container_left" style="margin-left: 8px;">
											<?php $check_timer_attr = explode( ',', $newarr['showunits_breakfield'] ); ?>

											<div class="arf_form_timer_val_wrapper"><input type="text" name="arfaddtimerbreakfieldhrs" id="arfaddtimerbreakfieldhrs" value="<?php echo isset( $newarr['arfaddtimerbreakfieldhrs'] ) ? esc_attr( $newarr['arfaddtimerbreakfieldhrs'] ) : ''; ?>" class="arf_form_padding_box arf_input_unithrs arf_total_unit_check arf_unit_reset_field arf_restricted_control" <?php if ( in_array( 'hrs', $check_timer_attr ) ) { ?> enabled="enable" <?php } else { ?> disabled="disabled" <?php } ?> /><br />
											<span class="arf_px arf_font_size" style="margin:0 14px 0 17px;"><?php echo addslashes( esc_html__( 'Hrs', 'arforms-form-builder' ) ); //phpcs:ignore ?></span></div>

											<div class="arf_form_timer_val_wrapper"><input type="text" name="arfaddtimerbreakfieldmin" id="arfaddtimerbreakfieldmin" value="<?php echo isset( $newarr['arfaddtimerbreakfieldhrs'] ) ? esc_attr( $newarr['arfaddtimerbreakfieldmin'] ) : ''; ?>"  class="arf_form_padding_box arf_input_unitmin arf_total_unit_check arf_unit_reset_field arf_restricted_control" <?php if ( in_array( 'min', $check_timer_attr ) ) { ?> enabled="enable" <?php } else { ?> disabled="disabled" <?php } ?> /><br />
											<span class="arf_px arf_font_size" style="margin:0 14px 0 17px;"><?php echo addslashes( esc_html__( 'Min', 'arforms-form-builder' ) ); //phpcs:ignore ?></span></div>

												<div class="arf_form_timer_val_wrapper"><input type="text" name="arfaddtimerbreakfieldsec" id="arfaddtimerbreakfieldsec" value="<?php echo isset( $newarr['arfaddtimerbreakfieldsec'] ) ? esc_attr( $newarr['arfaddtimerbreakfieldsec'] ) : ''; ?>"  class="arf_form_padding_box arf_input_unitsec arf_total_unit_check arf_unit_reset_field arf_restricted_control" <?php if ( in_array( 'sec', $check_timer_attr ) ) { ?> enabled="enable" <?php } else { ?> disabled="disabled" <?php } ?>/><br />
											<span class="arf_px arf_font_size" style="margin:0 14px 0 17px;"><?php echo addslashes( esc_html__( 'Sec', 'arforms-form-builder' ) ); //phpcs:ignore ?></span></div>
										</div>

										<div class="arf_accordion_container_row_container" id="arfstarttimerpg_no" <?php if ( isset( $newarr['arfsettimer'] ) && $newarr['arfsettimer'] == 0 ) { ?> style="display: none; <?php } ?>"> 
											<div class="arf_accordion_inner_container"><?php echo esc_html__( 'Start timer on page no.', 'arforms-form-builder' ); ?></div>
											<div class="arf_accordion_inner_container arf_restricted_control" id="arf_startpgno"> <?php $newarr['arfsettimestartpageno'] = ( isset( $newarr['arfsettimestartpageno'] ) && $newarr['arfsettimestartpageno'] != '' ) ? $newarr['arfsettimestartpageno'] : '1'; ?>
											<input type="text" name="arfstarttimerpgno" class="arf_small_width_txtbox arfcolor arf_total_unit_check" id="arfstarttimerpgno" value="<?php echo esc_attr( $newarr['arfsettimestartpageno'] ); ?>" size="5" disabled="disabled"/>
											</div>
										</div>

										<div class="arf_accordion_container_row_container" id="arfendtimerpg_no" <?php if ( isset( $newarr['arfsettimer'] ) && $newarr['arfsettimer'] == 0 ) { ?> style="display: none; <?php } ?>"> 
											<div class="arf_accordion_inner_container"><?php echo esc_html__( 'End timer on page no.', 'arforms-form-builder' ); ?> </div>
											<div class="arf_accordion_inner_container arf_restricted_control" id="arfsettimeendpageno">
												<?php $newarr['arfsettimeendpageno'] = ( isset( $newarr['arfsettimeendpageno'] ) && $newarr['arfsettimeendpageno'] != '' ) ? $newarr['arfsettimeendpageno'] : ''; ?>
												<input type="text" name="arfendtimerpgno" class="arf_small_width_txtbox arfcolor arf_total_unit_check" id="arfendtimerpgno" onchange="arfvalidatelastpagno();"  value="<?php echo esc_attr( $newarr['arfsettimeendpageno'] ); ?>" size="5" disabled="disabled"/>
											</div>
										</div>

										<div class="arf_accordion_container_row_container arf_restricted_control" id="timerstyle" <?php if ( isset( $newarr['arfsettimer'] ) && $newarr['arfsettimer'] == 0 ) { ?> style="display: none; <?php } ?>"> 
											<div class="arf_accordion_inner_container"><?php echo addslashes( esc_html__( 'Timer Style', 'arforms-form-builder' ) ); //phpcs:ignore ?></div>
											<div class="arf_accordion_inner_container">
												<div class="arf_dropdown_wrapper" style="margin-right: 5px;">
													<?php
														$pagebreak_selected_style = '';
													if ( isset( $newarr['arftimerstyle'] ) && $newarr['arftimerstyle'] != '' ) {
															$pagebreak_selected_style = $newarr['arftimerstyle'];
													} else {
														$pagebreak_selected_style = 'number';
													}
													?>
												<div class="arf_dropdown_wrapper" style="width: 100%;">
													<?php
														$pagebreak_style_opt = array(
															'number' => 'Number',
															'circle' => 'Circle',
															'circle_with_text' => 'Circle With Text',
														);

														echo $arflitemaincontroller->arflite_selectpicker_dom( 'arf_page_break_style', 'arf_page_break_style', 'arf_selectbox_option arf_restricted_control arf_disabled', 'width:122px', $pagebreak_selected_style, array(), $pagebreak_style_opt ); //phpcs:ignore
														?>
												</div>
												</div>
											</div>
										</div>

										<div class="arf_accordion_container_row_separator"></div>
										<div class="arf_accordion_container_row arf_padding">
											<div class="arf_accordion_outer_title"><?php echo esc_html__( 'Checkbox & Radio Style', 'arforms-form-builder' ); ?></div>
										</div>
										<div class="arf_accordion_container_row_container">
											<div class="arf_accordion_inner_container"><?php echo esc_html__( 'Style', 'arforms-form-builder' ); ?></div>
											<div class="arf_accordion_inner_container">
												<div class="arf_dropdown_wrapper" style="margin-right: 5px;">
													<?php
														$arf_checkbox_options = array(
															'custom' => addslashes( esc_html__( 'Custom', 'arforms-form-builder' ) ),
															'default' => addslashes( esc_html__( 'Default', 'arforms-form-builder' ) ),
															'material' => addslashes( esc_html__( 'Material 1', 'arforms-form-builder' ) ),
															'material_tick' => addslashes( esc_html__( 'Material 2', 'arforms-form-builder' ) ),
														);

														if ( $newarr['arfcheckradiostyle'] != 'custom' && $newarr['arfcheckradiostyle'] == '' ) {
															$newarr['arfcheckradiostyle'] = 'default';
														}

														$arf_checkbox_attrs = array(
															'onchange' => 'arfliteShowColorSelect(this.value);',
														);

														$options_class_arr = array(
															'custom' => '',
															'default' => ( $newarr['arfinputstyle'] == 'standard' || $newarr['arfinputstyle'] == 'rounded' ) ? 'arfvisible' : 'arfhidden',
															'material' => ( $newarr['arfinputstyle'] == 'standard' || $newarr['arfinputstyle'] == 'rounded' ) ? 'arfhidden' : 'arfvisible',
															'material_tick' => ( $newarr['arfinputstyle'] == 'standard' || $newarr['arfinputstyle'] == 'rounded' ) ? 'arfhidden' : 'arfvisible',
														);

														echo $arflitemaincontroller->arflite_selectpicker_dom( 'arfcksn', 'frm_check_radio_style', '', 'width:122px', $newarr['arfcheckradiostyle'], $arf_checkbox_attrs, $arf_checkbox_options, false, $options_class_arr ); //phpcs:ignore

													?>
												</div>
											</div>
										</div>
										<div class="arf_accordion_container_row arf_half_width" id="check_radio_main_icon" style="<?php echo ( $newarr['arfcheckradiostyle'] == 'custom' ) ? 'display:block;margin-bottom: 20px;height: auto;' : 'display:none;margin-bottom: 20px;height: auto;'; ?>">
											<div class="arf_accordion_inner_title arf_width_50"><?php echo esc_html__( 'Icon', 'arforms-form-builder' ); ?></div>
											<div class="arf_accordion_content_container arf_width_50 " style="margin-right: -1px;">
												<div class="arf_field_check_radio_wrapper" id="arf_field_check_radio_wrapper arf_right arf_accordion_container_mar">
													<div class="custom_checkbox_wrapper">
														<div class="arf_prefix_suffix_container_wrapper" data-action='edit' data-field='checkbox' id="arf_edit_check" data-toggle="arfmodal" href="#arf_fontawesome_modal" data-field_type='checkbox'>
															<div class="arf_prefix_container" id="arf_select_checkbox">
																<?php
																if ( isset( $newarr['arf_checked_checkbox_icon'] ) && $newarr['arf_checked_checkbox_icon'] != '' ) {
																	echo "<i id='arf_prefix_suffix_icon' class='arf_prefix_suffix_icon '".esc_attr($newarr['arf_checked_checkbox_icon'])."'></i>";
																} else {
																	echo "<i id='arf_prefix_suffix_icon' class='arf_prefix_suffix_icon fas fa-check'></i>";
																}
																?>
															</div>
															<div class="arf_prefix_suffix_action_container">
																<div class="arf_prefix_suffix_action margin-left15" title="Change Icon">
																	<i class="fas fa-caret-down fa-lg"></i>
																</div>
															</div>
														</div>
														<div class="howto"> <?php echo esc_html__( 'CheckBoxes', 'arforms-form-builder' ); ?> </div>
													</div>
													<br>
													<br>
													<div class="custom_checkbox_wrapper">
														<div class="arf_prefix_suffix_container_wrapper" data-action='edit' data-field='radio' id="arf_edit_radio" data-field_type='radio'>
															<div class="arf_suffix_container" id="arf_select_radio">
																<?php
																if ( isset( $newarr['arf_checked_radio_icon'] ) && $newarr['arf_checked_radio_icon'] != '' ) {
																	echo "<i id='arf_prefix_suffix_icon' class='arf_prefix_suffix_icon '".esc_attr($newarr['arf_checked_radio_icon'])."'></i>";
																} else {
																	echo "<i id='arf_prefix_suffix_icon' class='arf_prefix_suffix_icon fas fa-circle'></i>";
																}
																?>
															</div>
															<div class="arf_prefix_suffix_action_container">
																<div class="arf_prefix_suffix_action margin-left15" title="Change Icon">
																	<i class="fas fa-caret-down fa-lg"></i>
																</div>
															</div>
														</div>
														<div class="howto"> <?php echo esc_html__( 'Radio Buttons', 'arforms-form-builder' ); ?> </div>
													</div>
												</div>
											</div>
										</div>
										<div class="arf_accordion_container_row arf_half_width arfradio-btn-setseprater"></div>
										<input type="hidden" name="enable_arf_checkbox" id="enable_arf_checkbox" value="<?php echo isset( $newarr['enable_arf_checkbox'] ) ? esc_attr( $newarr['enable_arf_checkbox'] ) : ''; ?>" />
										<input type="hidden" name="arf_checkbox_icon" id="arf_checkbox_icon" value="<?php echo ( isset( $newarr['arf_checked_checkbox_icon'] ) && $newarr['arf_checked_checkbox_icon'] != '' ) ? esc_attr( $newarr['arf_checked_checkbox_icon'] ) : 'fa fa-check'; ?>" />
										<input type="hidden" name="enable_arf_radio" id="enable_arf_radio" value="<?php echo isset( $newarr['enable_arf_radio'] ) ? esc_attr( $newarr['enable_arf_radio'] ) : ''; ?>" />
										<input type="hidden" name="arf_radio_icon" id="arf_radio_icon" value="<?php echo ( isset( $newarr['arf_checked_radio_icon'] ) && $newarr['arf_checked_radio_icon'] != '' ) ? esc_attr( $newarr['arf_checked_radio_icon'] ) : 'fa fa-circle'; ?>" />
									<?php do_action( 'arflite_add_form_additional_input_settings', $arflite_id, $values ); ?> 
									</div>
								</dd>
							</dl>

							<dl class="arf_accordion_tab_field_animation_settings arf_field_animation_main_container">
								<dd>
									<a href="javascript:void(0)" data-target="arf_accordion_tab_field_animation_settings"><?php echo esc_html__( 'Field Animation Options', 'arforms-form-builder' ); ?><span class="arflite_pro_version_notice">(Premium)</span></a>
									<div class="arf_accordion_container">
										<div class="arf_accordion_container_row arf_padding">
											<div class="arf_accordion_outer_title"><?php echo esc_html__( 'On Load Animation', 'arforms-form-builder' ); ?></div>
										</div>
										<div class="arf_accordion_container_row_container">
											<div class="arf_accordion_inner_container"><?php echo addslashes( esc_html__( 'Animation', 'arforms-form-builder' ) ); //phpcs:ignore ?></div>
											<div class="arf_accordion_inner_container">
												<?php
													$ar_disable_animation             = array();
													$ar_disable_animation['disabled'] = 'disabled';

													$no_animation = array(
														'No Animation' => 'No Animation',
													);

													echo $arflitemaincontroller->arflite_selectpicker_dom( 'arf_editor_form_width_unit', 'arf_editor_form_width_unit', 'arf_restricted_control arf_disabled arf_animation_selectpicker', '', '', $ar_disable_animation, $no_animation ); //phpcs:ignore
												?>
											</div>
										</div>

										<div class="arf_accordion_container_row_container">
											<div class="arf_accordion_inner_container"><?php echo esc_html__( 'Animation Duration', 'arforms-form-builder' ); ?></div>
											<div class="arf_accordion_inner_container arf_restricted_control">
												<input type="text" name="arfandus" class="arf_small_width_txtbox arfcolor arffieldanimationdurationinput" id="arffieldanimationdurationsetting" value="0" size="5" disabled="disabled"/>
												<span class="arfsecondspan arf_animation_cls arfanimationdurationsecond">S</span>
											</div>
										</div>

										<div class="arf_accordion_container_row_container">
											<div class="arf_accordion_inner_container"><?php echo esc_html__( 'Animation Delay', 'arforms-form-builder' ); ?></div>
											<div class="arf_accordion_inner_container arf_restricted_control">
												<input type="text" name="arfandls" class="arf_small_width_txtbox arfcolor arffieldanimationdelayinput" id="arffieldanimationdelaysetting" value="0" size="5" disabled="disabled"/>
												<span class="arfsecondspan arf_animation_cls arfanimationdelaysecond">S</span>
											</div>
										</div>

										<div class="arf_accordion_container_row arf_half_width" style="height: auto;">
											<div class="arf_accordion_container_inner_div">
												<div class="arf_accordion_inner_title arf_width_50"><?php echo addslashes( esc_html__( 'Preview', 'arforms-form-builder' ) ); //phpcs:ignore ?></div>
											</div>
											<div class="arf_accordion_container_inner_div arf_field_animation_preview_main_container">
												<div class="arf_field_animation_preview_inner_container"><div class="" id="arf_field_animation_preview_text_container">Animation</div></div>
											</div>
										</div>

										<div class="arf_accordion_container_row_separator"></div>
										<div class="arf_accordion_container_row arf_padding">
											<div class="arf_accordion_outer_title"><?php echo esc_html__( 'Page Break Animation', 'arforms-form-builder' ); ?></div>
										</div>

										<div class="arf_accordion_container_row_container">
											<div class="arf_accordion_inner_container"><?php echo esc_html__( 'Inherit', 'arforms-form-builder' ); ?></div>
											<div class="arf_accordion_inner_container">
												<div class="arf_float_right" style="margin-right:5px;">
													<label class="arf_js_switch_label">
														<span class=""><?php echo addslashes( esc_html__( 'No', 'arforms-form-builder' ) ); //phpcs:ignore ?>&nbsp;</span>
													</label>
													<span class="arf_js_switch_wrapper arf_restricted_control">
														<input type="checkbox" class="js-switch" name="arfpbian" id="arfpagebreakinheritanimation" value="no" />
														<span class="arf_js_switch"></span>
													</span>
													<label class="arf_js_switch_label">
														<span class="">&nbsp;<?php echo addslashes( esc_html__( 'Yes', 'arforms-form-builder' ) ); //phpcs:ignore ?></span>
													</label>
												</div>
											</div>
										</div>

										<div class="arf_accordion_container_row_container">
											<div class="arf_accordion_inner_container"><?php echo addslashes( esc_html__( 'Animation', 'arforms-form-builder' ) ); //phpcs:ignore ?></div>
											<div class="arf_accordion_inner_container"> 
												<?php 
													$animation = array(
														'SlideInLeft' => 'SlideInLeft',
													);
													echo $arflitemaincontroller->arflite_selectpicker_dom( 'arf_editor_form_width_unit', 'arf_editor_form_width_unit', 'arf_restricted_control arf_disabled arf_animation_selectpicker', '', '', '', $animation ); //phpcs:ignore
												?>
											</div>
										</div>

										<div class="arf_accordion_container_row_container">
											<div class="arf_accordion_inner_container"><?php echo esc_html__( 'Animation Duration', 'arforms-form-builder' ); ?></div>
											<div class="arf_accordion_inner_container arf_restricted_control">
												<input type="text" name="arfandus" class="arf_small_width_txtbox arfcolor arffieldanimationdurationinput" id="arffieldanimationdurationsetting" value="0" size="5" disabled="disabled"/>
												<span class="arfsecondspan arf_animation_cls arfanimationdurationsecond">S</span>
												
											</div>
										</div>

										<div class="arf_accordion_container_row_container">
											<div class="arf_accordion_inner_container"><?php echo esc_html__( 'Animation Delay', 'arforms-form-builder' ); ?></div>
											<div class="arf_accordion_inner_container arf_restricted_control">
												<input type="text" name="arfandls" class="arf_small_width_txtbox arfcolor arffieldanimationdelayinput" id="arffieldanimationdelaysetting" value="0" size="5" disabled="disabled"/>
												<span class="arfsecondspan arf_animation_cls arfanimationdelaysecond">S</span>
											</div>
										</div>

										<div class="arf_accordion_container_row arf_half_width" style="height: auto;">

											<div class="arf_accordion_container_inner_div">
												<div class="arf_accordion_inner_title arf_width_50"><?php echo addslashes( esc_html__( 'Preview', 'arforms-form-builder' ) ); //phpcs:ignore ?></div>
											</div>
											<div class="arf_accordion_container_inner_div arf_field_animation_preview_main_container">
												<div class="arf_field_animation_preview_inner_container"><div class="" id="arf_field_animation_preview_text_container">Animation</div></div>
											</div>
										</div>

									</div>
									
								</dd>
							</dl>

							<dl class="arf_accordion_tab_submit_settings">
								<dd>
									<a href="javascript:void(0)" data-target="arf_accordion_tab_submit_settings"><?php echo esc_html__( 'Submit Button Options', 'arforms-form-builder' ); ?></a>
									<div class="arf_accordion_container">
										<div class="arf_accordion_container_row arf_padding">
											<div class="arf_accordion_outer_title"><?php echo esc_html__( 'General Options', 'arforms-form-builder' ); ?></div>
										</div>
										<div class="arf_accordion_container_row_container">
											<div class="arf_accordion_inner_title arf_two_row_text"><?php echo esc_html__( 'Button Alignment', 'arforms-form-builder' ); ?></div>
										</div>
										<div class="arf_accordion_container_row_container_left">
											<div class="arf_toggle_button_group arf_three_button_group arf-btn-align-opt">
												<?php
												$newarr['arfsubmitalignsetting'] = isset( $newarr['arfsubmitalignsetting'] ) ? $newarr['arfsubmitalignsetting'] : 'center';
												?>
												<label class="arf_toggle_btn <?php echo ( $newarr['arfsubmitalignsetting'] == 'right' ) ? 'arf_success' : ''; ?>"><input type="radio" name="arfmsas"  id="frm_submit_align_3"  class="visuallyhidden" data-arfstyle="true" data-arfstyledata='{"standard":".arflite_main_div_{arf_form_id} .arf_submit_div~|~button_auto","material":".arflite_main_div_{arf_form_id} .arf_submit_div~|~button_auto"}' data-arfstyleappend="true" data-arfstyleappendid="arf_{arf_form_id}_form_submit_button_position" value="right" <?php checked( $newarr['arfsubmitalignsetting'], 'right' ); ?> /><?php echo esc_html__( 'Right', 'arforms-form-builder' ); ?></label>
												<label class="arf_toggle_btn <?php echo ( $newarr['arfsubmitalignsetting'] == 'center' ) ? 'arf_success' : ''; ?>"><input type="radio" name="arfmsas" class="visuallyhidden" id="frm_submit_align_2" data-arfstyle="true" data-arfstyledata='{"standard":".arflite_main_div_{arf_form_id} .arf_submit_div~|~button_auto","material":".arflite_main_div_{arf_form_id} .arf_submit_div~|~button_auto"}' value="center" data-arfstyleappend="true" data-arfstyleappendid="arf_{arf_form_id}_form_submit_button_position" <?php checked( $newarr['arfsubmitalignsetting'], 'center' ); ?> /><?php echo esc_html__( 'Center', 'arforms-form-builder' ); ?></label>
												<label class="arf_toggle_btn <?php echo ( $newarr['arfsubmitalignsetting'] == 'left' ) ? 'arf_success' : ''; ?>"><input type="radio" name="arfmsas" class="visuallyhidden" id="frm_submit_align_1" data-arfstyle="true" data-arfstyledata='{"standard":".arflite_main_div_{arf_form_id} .arf_submit_div~|~button_auto","material":".arflite_main_div_{arf_form_id} .arf_submit_div~|~button_auto"}' value="left" data-arfstyleappend="true" data-arfstyleappendid="arf_{arf_form_id}_form_submit_button_position" <?php checked( $newarr['arfsubmitalignsetting'], 'left' ); ?> /><?php echo esc_html__( 'Left', 'arforms-form-builder' ); ?></label>
											</div>
										</div>

										<div class="arf_accordion_container_row_container">
											<div class="arf_accordion_inner_container"><?php echo esc_html__( 'Button Width (optional)', 'arforms-form-builder' ); ?> </div>
												<div class="arf_dropdown_wrapper arf_accordion_inner_container">
                                                    <?php  $newarr['arfbtnwidth'] = !empty($newarr['arfbtnwidth']) ? $newarr['arfbtnwidth'] : 'Desktop'; ?>
                                                    <?php
                                                        $form_button_width_unit_style = array(
                                                            "Desktop" => "<i class='fas fa-desktop'></i>",
                                                            "Tablet" => "<i class='fas fa-tablet-alt'></i>",
                                                            "Mobile" => "<i class='fas fa-mobile-alt'></i>",
														);

														$arflite_button_width_attr = array(
                                                            'onchange' => 'arflite_frm_button_width(this.value);'
                                                        );

                                                        echo $arflitemaincontroller->arflite_selectpicker_dom( 'arf_button_width', 'arf_button_width', '', 'margin-right:7px; width:40%', $newarr['arfbtnwidth'],$arflite_button_width_attr, $form_button_width_unit_style, false, array(), false, array(), false, array(), false, 'arf_frm_width_icon_cls' ); //phpcs:ignore
                                                    ?>
                                                </div>
										</div>
										<div class="arf_accordion_container_row_container_left">

											<input type="text" name="arfsbws" id="arfsubmitbuttonwidthsetting" class="arf_small_width_txtbox arfcolor arf_submit_width_cls" onkeypress="return arflite_check_numeric_input(event,this)" onpaste="arflite_validatepaste(this, event)" value="<?php echo esc_attr( $newarr['arfsubmitbuttonwidthsetting'] ); ?>"  onchange="arflitesetsubmitwidth();" size="5" />
											<input type="hidden" name="arfsbaw" id="arfsubmitautowidth" value="<?php echo esc_attr( $newarr['arfsubmitautowidth'] ); ?>" />

											<!-- Tablet css -->
											<?php 
												$newarr['arfsubmitbuttonwidthsetting_tablet'] = !empty($newarr['arfsubmitbuttonwidthsetting_tablet'] ) ? $newarr['arfsubmitbuttonwidthsetting_tablet'] : '';
												$newarr['arfsubmitautowidth_tablet'] = !empty( $newarr['arfsubmitautowidth_tablet'] ) ? $newarr['arfsubmitautowidth_tablet'] : '125';
											?>

											<input type="text" name="arfsbws_tablet" onkeypress="return arflite_check_numeric_input(event,this)" onpaste="arflite_validatepaste(this, event)" id="arfsubmitbuttonwidthsetting_tablet" class="arf_small_width_txtbox arfcolor arf_submit_width_cls" value="<?php echo esc_attr($newarr['arfsubmitbuttonwidthsetting_tablet']) ?>"  onchange="arflitesetsubmitwidth();" size="5" style="<?php echo (($newarr['arfbtnwidth'] == 'Tablet')) ? 'display:block;' : 'display:none;';?>" />

											<input type="hidden" name="arfsbaw_tablet" id="arfsubmitautowidth_tablet" value="<?php echo esc_html($newarr['arfsubmitautowidth_tablet']); ?>" />
											<!-- Mobile css -->
											<?php 
												$newarr['arfsubmitbuttonwidthsetting_mobile'] = !empty($newarr['arfsubmitbuttonwidthsetting_mobile'] ) ? $newarr['arfsubmitbuttonwidthsetting_mobile'] : ''; 
												$newarr['arfsubmitautowidth_mobile'] = !empty( $newarr['arfsubmitautowidth_mobile'] ) ? $newarr['arfsubmitautowidth_mobile'] : '125';
											?>
											<input type="text" name="arfsbws_mobile" onkeypress="return arflite_check_numeric_input(event,this)" id="arfsubmitbuttonwidthsetting_mobile" onpaste="arflite_validatepaste(this, event)"  class="arf_small_width_txtbox arfcolor arf_submit_width_cls" value="<?php echo esc_attr($newarr['arfsubmitbuttonwidthsetting_mobile']) ?>"  onchange="arflitesetsubmitwidth();" size="5" style="<?php echo (($newarr['arfbtnwidth'] == 'Mobile')) ? 'display:block;' : 'display:none;';?>" />
											<input type="hidden" name="arfsbaw_mobile" id="arfsubmitautowidth_mobile" value="<?php echo esc_html($newarr['arfsubmitautowidth_mobile']); ?>" />
											<span class="arfpxspan">px</span>
										</div>

										<div class="arf_accordion_container_row_container">
											<div class="arf_accordion_inner_container" ><?php echo esc_html__( 'Button Height (optional)', 'arforms-form-builder' ); ?></div>
											<div class="arf_accordion_inner_container arf_frm_btn_height">
												<input type="text" name="arfsbhs" id="arfsubmitbuttonheightsetting" class="arf_small_width_txtbox arfcolor" data-arfstyle="true" data-arfstyledata='{"standard":".arflite_main_div_{arf_form_id} .arfsubmitbutton .arf_submit_btn~|~height","material":".arflite_main_div_{arf_form_id} .arfsubmitbutton .arf_submit_btn~|~height"}' data-arfstyleappend="true" data-arfstyleappendid="arf_{arf_form_id}_form_submit_button_height" onkeypress="return arflite_check_numeric_input(event,this)" onpaste="arflite_validatepaste(this, event)" value="<?php echo esc_attr( $newarr['arfsubmitbuttonheightsetting'] ); ?>"  size="5" />
												<span class="arfpxspan">px</span>
											</div>
										</div>

										<div class="arf_accordion_container_row_container">
											<div class="arf_accordion_inner_container"><?php echo esc_html__( 'Button Text', 'arforms-form-builder' ); ?></div>
											<div class="arf_accordion_inner_container">
												<?php 
												$newarr['arfsubmitbuttontext'] = isset( $newarr['arfsubmitbuttontext'] ) ? $newarr['arfsubmitbuttontext'] : '';
												if ( $newarr['arfsubmitbuttontext'] == '' ) {
													$arf_option   = get_option( 'arflite_options' );
													$submit_value = $arf_option->submit_value;
												} else {
													$submit_value = esc_attr( $newarr['arfsubmitbuttontext'] );
												}
												?>
												<input type="text" name="arfsubmitbuttontext" id="arfsubmitbuttontext" class="arf_large_input_box arfwidth108 arfcolor" value="<?php echo esc_attr( $submit_value ); ?>" size="5" />
											</div>
										</div>

										<div class="arf_accordion_container_row_container">
											<div class="arf_accordion_inner_container"><?php echo esc_html__( 'Button Style', 'arforms-form-builder' ); ?></div>
											<div class="arf_accordion_inner_container">
												<?php
													$newarr['arfsubmitbuttonstyle'] = isset( $newarr['arfsubmitbuttonstyle'] ) ? $newarr['arfsubmitbuttonstyle'] : 'border';

													$submit_button_style_opts = array(
														'flat' => 'Flat',
														'border' => 'Border',
														'reverse border' => 'Reverse Border',
													);

													$submit_button_style_attr = array(
														'onchange' => 'arflitechangebuttonstyle(this.value)',
													);

													echo $arflitemaincontroller->arflite_selectpicker_dom( 'arfsubmitbuttonstyle', 'arfsubmitbuttonstyle', 'margin-right:12px;', '', $newarr['arfsubmitbuttonstyle'], $submit_button_style_attr, $submit_button_style_opts ); //phpcs:ignore
													?>
											</div>
										</div>
										<div class="arf_accordion_container_row_container">
											<div class="arf_accordion_inner_title" id="arf_sub_btn_margin"><?php echo esc_html__( 'Margin', 'arforms-form-builder' ); ?></div>
										</div>
											<div class="arf_accordion_container_row_container_left" style="margin-left:10px;">
												<div class="arf_submit_margin_box_wrapper"><input type="text" name="arfsubmitbuttonmarginsetting_1" id="arfsubmitbuttonmarginsetting_1" onchange="arflite_change_field_padding('arfsubmitbuttonmarginsetting');" value="<?php echo esc_attr( $newarr['arfsubmitbuttonmarginsetting_1'] ); ?>" onpaste="arflite_validatepaste(this, event)" class="arf_submit_margin_box" /><br /><span class="arf_px arf_font_size" ><?php echo esc_html__( 'Top', 'arforms-form-builder' ); ?></span></div>
												<div class="arf_submit_margin_box_wrapper"><input type="text" name="arfsubmitbuttonmarginsetting_2" id="arfsubmitbuttonmarginsetting_2" value="<?php echo esc_attr( $newarr['arfsubmitbuttonmarginsetting_2'] ); ?>" onchange="arflite_change_field_padding('arfsubmitbuttonmarginsetting');" onpaste="arflite_validatepaste(this, event)" class="arf_submit_margin_box" /><br /><span class="arf_px arf_font_size" ><?php echo esc_html__( 'Right', 'arforms-form-builder' ); ?></span></div>
												<div class="arf_submit_margin_box_wrapper"><input type="text" name="arfsubmitbuttonmarginsetting_3" id="arfsubmitbuttonmarginsetting_3" value="<?php echo esc_attr( $newarr['arfsubmitbuttonmarginsetting_3'] ); ?>" onpaste="arflite_validatepaste(this, event)" onchange="arflite_change_field_padding('arfsubmitbuttonmarginsetting');" class="arf_submit_margin_box" /><br /><span class="arf_px arf_font_size" style="    margin-left: 5px;"><?php echo esc_html__( 'Bottom', 'arforms-form-builder' ); ?></span></div>
												<div class="arf_submit_margin_box_wrapper"><input type="text" name="arfsubmitbuttonmarginsetting_4" id="arfsubmitbuttonmarginsetting_4" value="<?php echo esc_attr( $newarr['arfsubmitbuttonmarginsetting_4'] ); ?>" onpaste="arflite_validatepaste(this, event)" onchange="arflite_change_field_padding('arfsubmitbuttonmarginsetting');" class="arf_submit_margin_box" /><br /><span class="arf_px arf_font_size" style="margin-left: 10px;"><?php echo esc_html__( 'Left', 'arforms-form-builder' ); ?></span></div>
										
											<?php
												$arfsubmitbuttonmarginsetting_value = '';

												if ( esc_attr( $newarr['arfsubmitbuttonmarginsetting_1'] ) != '' ) {
													$arfsubmitbuttonmarginsetting_value .= $newarr['arfsubmitbuttonmarginsetting_1'] . 'px ';
												} else {
													$arfsubmitbuttonmarginsetting_value .= '0px ';
												}
												if ( esc_attr( $newarr['arfsubmitbuttonmarginsetting_2'] ) != '' ) {
													$arfsubmitbuttonmarginsetting_value .= $newarr['arfsubmitbuttonmarginsetting_2'] . 'px ';
												} else {
													$arfsubmitbuttonmarginsetting_value .= '0px ';
												}
												if ( esc_attr( $newarr['arfsubmitbuttonmarginsetting_3'] ) != '' ) {
													$arfsubmitbuttonmarginsetting_value .= $newarr['arfsubmitbuttonmarginsetting_3'] . 'px ';
												} else {
													$arfsubmitbuttonmarginsetting_value .= '0px ';
												}
												if ( esc_attr( $newarr['arfsubmitbuttonmarginsetting_4'] ) != '' ) {
													$arfsubmitbuttonmarginsetting_value .= $newarr['arfsubmitbuttonmarginsetting_4'] . 'px';
												} else {
													$arfsubmitbuttonmarginsetting_value .= '0px';
												}
											?>
											<input type="hidden" name="arfsbms" id="arfsubmitbuttonmarginsetting" class="txtxbox_widget "  data-arfstyle="true" data-arfstyledata='{"standard":".arflite_main_div_{arf_form_id} .arfsubmitbutton.arf_submit_div~|~margin","material":".arflite_main_div_{arf_form_id} .arfsubmitbutton.arf_submit_div~|~margin"}'  data-arfstyleappend="true" data-arfstyleappendid="arf_{arf_form_id}_form_submit_button_margin" value="<?php echo esc_attr( $arfsubmitbuttonmarginsetting_value ); ?>" size="6" />
										</div>

										<input type="hidden" name="arfsbcs" id="arfsubmitbuttoncolorsetting" class="hex txtxbox_widget" value="<?php echo esc_attr( $newarr['arfsubmitbgcolor2setting'] ); ?>" style="width:80px;" />
										<div class="arf_accordion_container_row arf_accordion_container_row_container arf_half_width">
											<div class="arf_accordion_inner_container arf_two_row_text " ><?php echo esc_html__( 'Background Image', 'arforms-form-builder' ); ?></div>
											<div class="arf_accordion_inner_container arf_align_right  arf_right">
												<div class="arf_imageloader arf_form_style_file_upload_loader" id="ajax_submit_loader"></div>
												<div id="submit_btn_img_div" style="margin-left:0px;">
												<?php
												if ( $newarr['submit_bg_img'] != '' ) {
													?>
														<img src="<?php echo esc_url( $newarr['submit_bg_img'] ); ?>" height="35" width="35" style="margin-left:5px;border:1px solid #D5E3FF !important;" />&nbsp;<span onclick="arflite_delete_image('button_image');" style="width:35px;height: 35px;display:inline-block;cursor: pointer;"><svg width="23px" height="27px" viewBox="0 0 30 30"><path xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd" fill="#4786FF" d="M19.002,4.351l0.007,16.986L3.997,21.348L3.992,4.351H1.016V2.38  h1.858h4.131V0.357h8.986V2.38h4.146h1.859l0,0v1.971H19.002z M16.268,4.351H6.745H5.993l0.006,15.003h10.997L17,4.351H16.268z   M12.01,7.346h1.988v9.999H12.01V7.346z M9.013,7.346h1.989v9.999H9.013V7.346z"/></svg></span>
														<input type="hidden" name="arfsbis" onclick="arflite_clear_file_submit();" value="<?php echo esc_attr( $newarr['submit_bg_img'] ); ?>" id="arfsubmitbuttonimagesetting" />
													<?php } else { ?>
														<div class="arfajaxfileupload">
															<div class="arf_form_style_file_upload_icon">
																<svg width="16" height="18" viewBox="0 0 18 20" fill="#ffffff"><path xmlns="http://www.w3.org/2000/svg" d="M15.906,18.599h-1h-12h-1h-1v-7h2v5h12v-5h2v7H15.906z M13.157,7.279L9.906,4.028v8.571c0,0.552-0.448,1-1,1c-0.553,0-1-0.448-1-1v-8.54l-3.22,3.22c-0.403,0.403-1.058,0.403-1.46,0 c-0.403-0.403-0.403-1.057,0-1.46l4.932-4.932c0.211-0.211,0.488-0.306,0.764-0.296c0.275-0.01,0.553,0.085,0.764,0.296 l4.932,4.932c0.403,0.403,0.403,1.057,0,1.46S13.561,7.682,13.157,7.279z"/></svg>
															</div>
															<input type="file" data-val="submit_btn_img" name="submit_btn_img" id="submit_btn_img" class="original" style="position: absolute; cursor: pointer; top: 0px; padding:0; margin:0; height:100%; width:100%; right:0; z-index: 100; opacity: 0; filter:alpha(opacity=0);" />
														</div>

														<input type="hidden" name="imagename" id="imagename" value="" />
														<input type="hidden" name="arfsbis" onclick="arflite_clear_file_submit();" value="" id="arfsubmitbuttonimagesetting" />
														<?php
													}
													?>
												</div>
											</div>
										</div>
										<div class="arf_accordion_container_row arf_accordion_container_row_container arf_half_width">
											<div class="arf_accordion_inner_container"><?php echo esc_html__( 'Background Hover Image', 'arforms-form-builder' ); ?></div>
											<div class="arf_accordion_inner_container">
												<div class="arf_imageloader arf_form_style_file_upload_loader" id="ajax_submit_hover_loader"></div>
												<div id="submit_hover_btn_img_div" style="margin-left:0px;">
													<?php if ( $newarr['submit_hover_bg_img'] != '' ) { ?>
														<img src="<?php echo esc_url( $newarr['submit_hover_bg_img'] ); ?>" height="35" width="35" style="margin-left:5px;border:1px solid #D5E3FF !important;" />&nbsp;<span onclick="arflite_delete_image('button_hover_image');" style="width:35px;height: 35px;display:inline-block;cursor: pointer;"><svg width="23px" height="27px" viewBox="0 0 30 30"><path xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd" fill="#4786FF" d="M19.002,4.351l0.007,16.986L3.997,21.348L3.992,4.351H1.016V2.38  h1.858h4.131V0.357h8.986V2.38h4.146h1.859l0,0v1.971H19.002z M16.268,4.351H6.745H5.993l0.006,15.003h10.997L17,4.351H16.268z   M12.01,7.346h1.988v9.999H12.01V7.346z M9.013,7.346h1.989v9.999H9.013V7.346z"/></svg></span>
														<input type="hidden" name="arfsbhis" onclick="arflite_clear_file_submit_hover();" value="<?php echo esc_attr( $newarr['submit_hover_bg_img'] ); ?>" id="arfsubmithoverbuttonimagesetting" />
													<?php } else { ?>
														<div class="arfajaxfileupload arf_frm_submit_btn_hover_img">
															<div class="arf_form_style_file_upload_icon">
																<svg width="16" height="18" viewBox="0 0 18 20" fill="#ffffff"><path xmlns="http://www.w3.org/2000/svg" d="M15.906,18.599h-1h-12h-1h-1v-7h2v5h12v-5h2v7H15.906z M13.157,7.279L9.906,4.028v8.571c0,0.552-0.448,1-1,1c-0.553,0-1-0.448-1-1v-8.54l-3.22,3.22c-0.403,0.403-1.058,0.403-1.46,0 c-0.403-0.403-0.403-1.057,0-1.46l4.932-4.932c0.211-0.211,0.488-0.306,0.764-0.296c0.275-0.01,0.553,0.085,0.764,0.296 l4.932,4.932c0.403,0.403,0.403,1.057,0,1.46S13.561,7.682,13.157,7.279z"/></svg>
															</div>
															<input type="file" name="submit_hover_btn_img" data-val="submit_hover_bg" id="submit_hover_btn_img" class="original" style="position: absolute; cursor: pointer; top: 0px; padding:0; margin:0; height:100%; width:100%; right:0; z-index: 100; opacity: 0; filter:alpha(opacity=0);" />
														</div>

														<input type="hidden" name="imagename_submit_hover" id="imagename_submit_hover" value="" />
														<input type="hidden" name="arfsbhis" onclick="arflite_clear_file_submit_hover();" value="" id="arfsubmithoverbuttonimagesetting" />
														<?php
													}
													?>

												</div>
											</div>
										</div>

										<div class="arf_accordion_container_row arf_half_width arflitefont-setting-wrap">
											<div class="arf_accordion_container_inner_div">
												<div class="arf_accordion_inner_title arf_width_50"><?php echo esc_html__( 'Font Settings', 'arforms-form-builder' ); ?></div>
											</div>
											<div class="arf_accordion_container_inner_div">
												<div class="arf_custom_font" data-id="arf_submit_font_settings">
													<div class="arf_custom_font_icon">
														<svg viewBox="-10 -10 35 35">
														<g id="paint_brush">
														<path fill="#ffffff" fill-rule="evenodd" clip-rule="evenodd" d="M7.423,14.117c1.076,0,2.093,0.022,3.052,0.068v-0.82c-0.942-0.078-1.457-0.146-1.542-0.205  c-0.124-0.092-0.203-0.354-0.235-0.787s-0.049-1.601-0.049-3.504l0.059-6.568c0-0.299,0.013-0.472,0.039-0.518  C8.772,1.744,8.85,1.725,8.981,1.725c1.549,0,2.584,0.043,3.105,0.128c0.162,0.026,0.267,0.076,0.313,0.148  c0.059,0.092,0.117,0.687,0.176,1.784h0.811c0.052-1.201,0.14-2.249,0.264-3.145l-0.107-0.156c-2.396,0.098-4.561,0.146-6.494,0.146  c-1.94,0-3.936-0.049-5.986-0.146L0.954,0.563c0.078,0.901,0.11,1.976,0.098,3.223h0.84c0.085-1.062,0.141-1.633,0.166-1.714  C2.083,1.99,2.121,1.933,2.17,1.9c0.049-0.032,0.262-0.065,0.641-0.098c0.652-0.052,1.433-0.078,2.34-0.078  c0.443,0,0.674,0.024,0.69,0.073c0.016,0.049,0.024,1.364,0.024,3.947c0,1.313-0.01,2.602-0.029,3.863  c-0.033,1.776-0.072,2.804-0.117,3.084c-0.039,0.201-0.098,0.34-0.176,0.414c-0.078,0.075-0.212,0.129-0.4,0.161  c-0.404,0.065-0.791,0.098-1.162,0.098v0.82C4.861,14.14,6.008,14.117,7.423,14.117L7.423,14.117z"></path>
														</g></svg>
													</div>
													<div class="arf_custom_font_label"><?php echo esc_html__( 'Advanced font options', 'arforms-form-builder' ); ?></div>
												</div>
											</div>
										</div>

										<div class="arf_accordion_container_row_separator"></div>
										<div class="arf_accordion_container_row arf_padding">
											<div class="arf_accordion_outer_title"><?php echo esc_html__( 'Border Options', 'arforms-form-builder' ); ?></div>
										</div>
										<div class="arf_accordion_container_row_container arf_accordion_container_mar">
											<div class="arf_accordion_inner_container"><?php echo esc_html__( 'Size', 'arforms-form-builder' ); ?></div>
											<div class="arf_accordion_inner_container">
												<div class="arf_slider_wrapper">
													<div id="arflite_btn_border_size" class="noUi-target noUi-ltr noUi-horizontal noUi-txt-dir-ltr slider-track"></div>
													<input id="arfsubmitbuttonborderwidhtsetting_exs" class="arf_slider arf_slider_input" data-slider-id='arfsubmitbuttonborderwidhtsetting_exsSlider' type="text" data-slider-min="0" data-slider-max="20" data-slider-step="1" data-slider-value="<?php echo esc_attr( $newarr['arfsubmitborderwidthsetting'] ); ?>" />
													<div class="arf_slider_unit_data">
														<div class="arf_px arflite_float_left">0 px</div>
														<div class="arf_px arflite_float_right">20 px</div>
													</div>

													<input type="hidden" name="arfsbbws" id="arfsubmitbuttonborderwidhtsetting" data-arfstyle="true" data-arfstyledata='{"standard":".arflite_main_div_{arf_form_id} .arfsubmitbutton .arf_submit_btn~|~border-width","material":".arflite_main_div_{arf_form_id} .arfsubmitbutton .arf_submit_btn~|~border-width"}' data-arfstyleappend="true" data-arfstyleappendid="arf_{arf_form_id}_form_submit_button_border_width" value="<?php echo esc_attr( $newarr['arfsubmitborderwidthsetting'] ); ?>" class="txtxbox_widget " size="4" />
												</div>
											</div>
										</div>
										<div class="arf_accordion_container_row_container arf_accordion_container_mar">
											<div class="arf_accordion_inner_container"><?php echo esc_html__( 'Radius', 'arforms-form-builder' ); ?></div>
											<div class="arf_accordion_inner_container">
												<div class="arf_slider_wrapper">
													<div id="arflite_btn_border_radius" class="noUi-target noUi-ltr noUi-horizontal noUi-txt-dir-ltr slider-track"></div>
													<input id="arfsubmitbuttonborderradiussetting_exs" class="arf_slider arf_slider_input" data-slider-id='arfsubmitbuttonborderradiussetting_exsSlider' type="text" data-slider-min="0" data-slider-max="50" data-slider-step="1" data-slider-value="<?php echo esc_attr( $newarr['arfsubmitborderradiussetting'] ); ?>" />
													<div class="arf_slider_unit_data">
														<div class="arf_px arflite_float_left">0 px</div>
														<div class="arf_px arflite_float_right">50 px</div>
													</div>
													<input type="hidden" value="<?php echo esc_attr( $newarr['arfsubmitborderradiussetting'] ); ?>" data-arfstyle="true" data-arfstyledata='{"standard":".arflite_main_div_{arf_form_id} .arfsubmitbutton .arf_submit_btn~|~border-radius","material":".arflite_main_div_{arf_form_id} .arfsubmitbutton .arf_submit_btn~|~border-radius"}' name="arfsbbrs" id="arfsubmitbuttonborderradiussetting" class="txtxbox_widget "  data-arfstyleappend="true" data-arfstyleappendid="arf_{arf_form_id}_form_submit_button_border_radius" size="4" />
												</div>
											</div>
										</div>
										<?php
											$formbuttonxoffset = ( isset( $newarr['arfsubmitboxxoffsetsetting'] ) && $newarr['arfsubmitboxxoffsetsetting'] != '' ) ? esc_attr( $newarr['arfsubmitboxxoffsetsetting'] ) : 1;
											$formbuttonyoffset = ( isset( $newarr['arfsubmitboxyoffsetsetting'] ) && $newarr['arfsubmitboxyoffsetsetting'] != '' ) ? esc_attr( $newarr['arfsubmitboxyoffsetsetting'] ) : 2;
											$formbuttonblur    = ( isset( $newarr['arfsubmitboxblursetting'] ) && $newarr['arfsubmitboxblursetting'] != '' ) ? esc_attr( $newarr['arfsubmitboxblursetting'] ) : 3;
											$formbuttonspread  = ( isset( $newarr['arfsubmitboxshadowsetting'] ) && $newarr['arfsubmitboxshadowsetting'] != '' ) ? esc_attr( $newarr['arfsubmitboxshadowsetting'] ) : 0;
										?>

								<div class="arf_accordion_container_row_separator"></div>
									<div class="arf_accordion_container_row arf_padding">
										<div class="arf_accordion_outer_title"><?php echo esc_html__( 'Shadow Options', 'arforms-form-builder' ); ?></div>
									</div>
									<div class="arf_accordion_container_row_container arf_accordion_container_mar">
										<div class="arf_accordion_inner_container"><?php echo esc_html__( 'X-offset', 'arforms-form-builder' ); ?></div>
										<div class="arf_accordion_inner_container">
											<div class="arf_slider_wrapper">
												<div id="arflite_btn_xoffset_slider" class="noUi-target noUi-ltr noUi-horizontal noUi-txt-dir-ltr slider-track"></div>
												<input id="arfsubmitbuttonxoffsetsetting_exs" class="arf_slider arf_slider_input" data-slider-id='arfsubmitbuttonxoffsetsetting_exsSlider' type="text" data-slider-min="-50" data-slider-max="50" data-slider-step="1" data-slider-value="<?php echo esc_attr( $formbuttonxoffset ); ?>" />
												<div class="arf_slider_unit_data">
													<div class="arf_px arflite_float_left" >-50 px</div>
													<div class="arf_px arflite_float_right">50 px</div>
												</div>

												<input type="hidden" name="arfsbxos" id="arfsubmitbuttonxoffsetsetting" style="width:100px;" data-arfstyle="true" data-arfstyledata='{"standard":".arflite_main_div_{arf_form_id} .arfsubmitbutton .arf_greensave_button_wrapper .arf_submit_btn.arf_submit_btn_flat~|~box-shadow"}' data-arfstyleappend="true" data-arfstyleappendid="arf_{arf_form_id}_submit_box_shadow" value="<?php echo esc_attr( $formbuttonxoffset ); ?>" class="txtxbox_widget " size="4" />
											</div>
										</div>
									</div>

									<div class="arf_accordion_container_row_container arf_accordion_container_mar">
										<div class="arf_accordion_inner_container"><?php echo esc_html__( 'Y-offset', 'arforms-form-builder' ); ?></div>
										<div class="arf_accordion_inner_container">
											<div class="arf_slider_wrapper">
												<div id="arflite_btn_yoffset_slider" class="noUi-target noUi-ltr noUi-horizontal noUi-txt-dir-ltr slider-track"></div>
												<input id="arfsubmitbuttonyoffsetsetting_exs" class="arf_slider arf_slider_input" data-slider-id='arfsubmitbuttonyoffsetsetting_exsSlider' type="text" data-slider-min="-50" data-slider-max="50" data-slider-step="1" data-slider-value="<?php echo esc_attr( $formbuttonyoffset ); ?>" />
												<div class="arf_slider_unit_data">
													<div class="arf_px arflite_float_left">-50 px</div>
													<div class="arf_px arflite_float_right">50 px</div>
												</div>

												<input type="hidden" value="<?php echo esc_attr( $formbuttonyoffset ); ?>"
												name="arfsbyos" data-arfstyle="true" data-arfstyledata='{"standard":".arflite_main_div_{arf_form_id} .arfsubmitbutton .arf_greensave_button_wrapper .arf_submit_btn.arf_submit_btn_flat~|~box-shadow"}' data-arfstyleappend="true" data-arfstyleappendid="arf_{arf_form_id}_submit_box_shadow" id="arfsubmitbuttonyoffsetsetting" class="txtxbox_widget "   size="4" style="width:100px;" />
											</div>
										</div>
									</div>

									<div class="arf_accordion_container_row_container arf_accordion_container_mar">
										<div class="arf_accordion_inner_container"><?php echo esc_html__( 'Blur', 'arforms-form-builder' ); ?></div>
										<div class="arf_accordion_inner_container">
											<div class="arf_slider_wrapper">
											<div id="arflite_btn_blur_slider" class="noUi-target noUi-ltr noUi-horizontal noUi-txt-dir-ltr slider-track"></div>
												<input id="arfsubmitbuttonblursetting_exs" class="arf_slider arf_slider_input" data-slider-id='arfsubmitbuttonblursetting_exsSlider' type="text" data-slider-min="0" data-slider-max="50" data-slider-step="1" data-slider-value="<?php echo esc_attr( $formbuttonblur ); ?>" />
												<div class="arf_slider_unit_data">
													<div class="arf_px arflite_float_left">0 px</div>
													<div class="arf_px arflite_float_right">50 px</div>
												</div>

												<input type="hidden" data-arfstyle="true" data-arfstyledata='{"standard":".arflite_main_div_{arf_form_id} .arfsubmitbutton .arf_greensave_button_wrapper .arf_submit_btn.arf_submit_btn_flat~|~box-shadow"}' data-arfstyleappend="true" data-arfstyleappendid="arf_{arf_form_id}_submit_box_shadow" value="<?php echo esc_attr( $formbuttonblur ); ?>" name="arfsbbs" id="arfsubmitbuttonblursetting" class="txtxbox_widget "  size="4" style="width:100px;" />
											</div>
										</div>
									</div>

									<div class="arf_accordion_container_row_container arf_accordion_container_mar">
										<div class="arf_accordion_inner_container"><?php echo esc_html__( 'Spread', 'arforms-form-builder' ); ?></div>
										<div class="arf_accordion_inner_container">
											<div class="arf_slider_wrapper">
												<div id="arflite_spread_slider" class="noUi-target noUi-ltr noUi-horizontal noUi-txt-dir-ltr slider-track"></div>
												<input id="arfsubmitbuttonshadowsetting_exs" class="arf_slider arf_slider_input" data-slider-id='arfsubmitbuttonshadowsetting_exsSlider' type="text" data-slider-min="-50" data-slider-max="50" data-slider-step="1" data-slider-value="<?php echo esc_attr( $formbuttonspread ); ?>" />
												<div class="arf_slider_unit_data">
													<div class="arf_px arflite_float_left">-50 px</div>
													<div class="arf_px arflite_float_right">50 px</div>
												</div>

												<input type="hidden" data-arfstyle="true" data-arfstyledata='{"standard":".arflite_main_div_{arf_form_id} .arfsubmitbutton .arf_greensave_button_wrapper .arf_submit_btn.arf_submit_btn_flat~|~box-shadow"}' data-arfstyleappend="true" data-arfstyleappendid="arf_{arf_form_id}_submit_box_shadow" name="arfsbsps" id="arfsubmitbuttonshadowsetting" style="width:100px;" value="<?php echo esc_attr( $formbuttonspread ); ?>" class="txtxbox_widget" size="4" />
											</div>
										</div>
									</div>
									</div>
								</dd>
							</dl>
						</div>
					</div>
				</div>
				<div class="arf_form_style_tab_container" id="arf_form_custom_css">
					<div class="arf_form_custom_css_tab">
						<?php
							global $arflite_custom_css_array;
						?>
						<div class="arf_custom_css_cloud_wrapper">
							<span><?php echo esc_html__( 'Add CSS Elements', 'arforms-form-builder' ); ?></span>
							<i class="fas fa-caret-down fa-lg"></i>
							<ul class="arf_custom_css_cloud_list_wrapper">
							<?php
							foreach ( $arflite_custom_css_array as $key => $value ) { ?>
								<li class="arf_custom_css_cloud_list_item <?php echo ( isset( $values[ $key ] ) && $values[ $key ] != '' ) ? 'arfactive' : ''; ?>" id="<?php echo esc_attr( $value['onclick_1'] ); ?>"><span><?php echo esc_html( $value['label_title'] ); ?></span></li>
								<?php } ?>
							</ul>
						</div>
						<div id="arf_expand_css_code" class="arf_expand_css_code_button">
							<svg width="40px" height="40px" viewBox="-10 -12 39 39">
								<path fill="#ffffff" d="M18.08,6.598l-1.29,1.289l-0.009-0.009l-4.719,4.72l-1.289-1.29  l4.719-4.719L10.773,1.87l1.289-1.29l4.719,4.719l0.009-0.008l1.29,1.289l-0.009,0.009L18.08,6.598z M7.035,12.598l-4.72-4.72  L2.306,7.887L1.017,6.598l0.009-0.009L1.017,6.58l1.289-1.289l0.009,0.008l4.72-4.719l1.289,1.29L3.605,6.589l4.719,4.719  L7.035,12.598z">
							</svg>
						</div>
						<div class="arf_form_other_css_wrapper">
							<textarea id="arf_form_other_css" name="options[arf_form_other_css]" cols="50" rows="4" class="arf_other_css_textarea"><?php echo isset( $form_opts['arf_form_other_css'] ) ? stripslashes_deep( esc_attr($form_opts['arf_form_other_css']) ) : ''; //phpcs:ignore ?></textarea>
						</div>
					</div>
				</div>
				<div class="arf_custom_color_popup">
					<?php
					$bgColor = ( isset( $newarr['arfmainformbgcolorsetting'] ) && $newarr['arfmainformbgcolorsetting'] != '' ) ? esc_attr( $newarr['arfmainformbgcolorsetting'] ) : $skinJson->skins->$active_skin->form->background;
					$bgColor = ( substr( $bgColor, 0, 1 ) != '#' ) ? '#' . $bgColor : $bgColor;

					$frmTitleColor = ( isset( $newarr['arfmainformtitlecolorsetting'] ) && $newarr['arfmainformtitlecolorsetting'] != '' ) ? esc_attr( $newarr['arfmainformtitlecolorsetting'] ) : $skinJson->skins->$active_skin->form->title;
					$frmTitleColor = ( substr( $frmTitleColor, 0, 1 ) != '#' ) ? '#' . $frmTitleColor : $frmTitleColor;

					$formBrdColor = ( isset( $newarr['arfmainfieldsetcolor'] ) && $newarr['arfmainfieldsetcolor'] != '' ) ? esc_attr( $newarr['arfmainfieldsetcolor'] ) : $skinJson->skins->$active_skin->form->border;
					$formBrdColor = ( substr( $formBrdColor, 0, 1 ) != '#' ) ? '#' . $formBrdColor : $formBrdColor;

					$inputBaseColor = ( isset( $newarr['arfmainbasecolor'] ) && $newarr['arfmainbasecolor'] != '' ) ? esc_attr( $newarr['arfmainbasecolor'] ) : $skinJson->skins->$active_skin->main;

					$inputBaseColor = ( substr( $inputBaseColor, 0, 1 ) != '#' ) ? '#' . $inputBaseColor : $inputBaseColor;


					$formShadowColor = ( isset( $newarr['arfmainformbordershadowcolorsetting'] ) && $newarr['arfmainformbordershadowcolorsetting'] != '' ) ? esc_attr( $newarr['arfmainformbordershadowcolorsetting'] ) : $skinJson->skins->$active_skin->form->shadow;
					$formShadowColor = ( substr( $formShadowColor, 0, 1 ) != '#' ) ? '#' . $formShadowColor : $formShadowColor;

					$labelColor = ( isset( $newarr['label_color'] ) && $newarr['label_color'] != '' ) ? esc_attr( $newarr['label_color'] ) : $skinJson->skins->$active_skin->label->text;
					$labelColor = ( substr( $labelColor, 0, 1 ) != '#' ) ? '#' . $labelColor : $labelColor;

					$inputTxtColor = ( isset( $newarr['text_color'] ) && $newarr['text_color'] != '' ) ? esc_attr( $newarr['text_color'] ) : $skinJson->skins->$active_skin->input->text;
					$inputTxtColor = ( substr( $inputTxtColor, 0, 1 ) != '#' ) ? '#' . $inputTxtColor : $inputTxtColor;

					$iconBgColor = ( isset( $newarr['prefix_suffix_bg_color'] ) && $newarr['prefix_suffix_bg_color'] != '' ) ? esc_attr( $newarr['prefix_suffix_bg_color'] ) : $skinJson->skins->$active_skin->input->prefix_suffix_background;
					$iconBgColor = ( substr( $iconBgColor, 0, 1 ) != '#' ) ? '#' . $iconBgColor : $iconBgColor;

					$iconColor = ( isset( $newarr['prefix_suffix_icon_color'] ) && $newarr['prefix_suffix_icon_color'] != '' ) ? esc_attr( $newarr['prefix_suffix_icon_color'] ) : $skinJson->skins->$active_skin->input->prefix_suffix_icon_color;
					$iconColor = ( substr( $iconColor, 0, 1 ) != '#' ) ? '#' . $iconColor : $iconColor;

					$inputBg = ( isset( $newarr['bg_color'] ) && $newarr['bg_color'] != '' ) ? esc_attr( $newarr['bg_color'] ) : $skinJson->skins->$active_skin->input->background;
					$inputBg = ( substr( $inputBg, 0, 1 ) != '#' ) ? '#' . $inputBg : $inputBg;

					$inputActiveBg = ( isset( $newarr['arfbgactivecolorsetting'] ) && $newarr['arfbgactivecolorsetting'] != '' ) ? esc_attr( $newarr['arfbgactivecolorsetting'] ) : $skinJson->skins->$active_skin->input->background_active;
					$inputActiveBg = ( substr( $inputActiveBg, 0, 1 ) != '#' ) ? '#' . $inputActiveBg : $inputActiveBg;

					$inputErrorBg = ( isset( $newarr['arferrorbgcolorsetting'] ) && $newarr['arferrorbgcolorsetting'] != '' ) ? esc_attr( $newarr['arferrorbgcolorsetting'] ) : $skinJson->skins->$active_skin->input->background_error;
					$inputErrorBg = ( substr( $inputErrorBg, 0, 1 ) != '#' ) ? '#' . $inputErrorBg : $inputErrorBg;

					$inputBrdColor = ( isset( $newarr['border_color'] ) && $newarr['border_color'] != '' ) ? esc_attr( $newarr['border_color'] ) : $skinJson->skins->$active_skin->input->border;
					$inputBrdColor = ( substr( $inputBrdColor, 0, 1 ) != '#' ) ? '#' . $inputBrdColor : $inputBrdColor;

					$inputActiveBrd = ( isset( $newarr['arfborderactivecolorsetting'] ) && $newarr['arfborderactivecolorsetting'] != '' ) ? esc_attr( $newarr['arfborderactivecolorsetting'] ) : $skinJson->skins->$active_skin->input->border_active;
					$inputActiveBrd = ( substr( $inputActiveBrd, 0, 1 ) != '#' ) ? '#' . $inputActiveBrd : $inputActiveBrd;

					$inputErrorBrd = ( isset( $newarr['arferrorbordercolorsetting'] ) && $newarr['arferrorbordercolorsetting'] != '' ) ? esc_attr( $newarr['arferrorbordercolorsetting'] ) : $skinJson->skins->$active_skin->input->border_error;
					$inputErrorBrd = ( substr( $inputErrorBrd, 0, 1 ) != '#' ) ? '#' . $inputErrorBrd : $inputErrorBrd;

					$submitTxtColor = ( isset( $newarr['arfsubmittextcolorsetting'] ) && $newarr['arfsubmittextcolorsetting'] != '' ) ? esc_attr( $newarr['arfsubmittextcolorsetting'] ) : $skinJson->skins->$active_skin->input->text;
					$submitTxtColor = ( substr( $submitTxtColor, 0, 1 ) != '#' ) ? '#' . $submitTxtColor : $submitTxtColor;

					$submitBgColor = ( isset( $newarr['submit_bg_color'] ) && $newarr['submit_bg_color'] != '' ) ? esc_attr( $newarr['submit_bg_color'] ) : $skinJson->skins->$active_skin->submit->background;
					$submitBgColor = ( substr( $submitBgColor, 0, 1 ) != '#' ) ? '#' . $submitBgColor : $submitBgColor;

					$submitHoverBg = ( isset( $newarr['arfsubmitbuttonbgcolorhoversetting'] ) && $newarr['arfsubmitbuttonbgcolorhoversetting'] != '' ) ? esc_attr( $newarr['arfsubmitbuttonbgcolorhoversetting'] ) : $skinJson->skins->$active_skin->submit->background_hover;
					$submitHoverBg = ( substr( $submitHoverBg, 0, 1 ) != '#' ) ? '#' . $submitHoverBg : $submitHoverBg;

					$submitBrdColor = isset( $newarr['arfsubmitbordercolorsetting'] ) ? esc_attr( $newarr['arfsubmitbordercolorsetting'] ) : $skinJson->skins->$active_skin->submit->border;
					$submitBrdColor = ( substr( $submitBrdColor, 0, 1 ) != '#' ) ? '#' . $submitBrdColor : $submitBrdColor;

					$submitShadowColor = ( isset( $newarr['arfsubmitshadowcolorsetting'] ) && $newarr['arfsubmitshadowcolorsetting'] != '' ) ? esc_attr( $newarr['arfsubmitshadowcolorsetting'] ) : $skinJson->skins->$active_skin->submit->shadow;
					$submitShadowColor = ( substr( $submitShadowColor, 0, 1 ) != '#' ) ? '#' . $submitShadowColor : $submitShadowColor;

					$successBgColor = ( isset( $newarr['arfsucessbgcolorsetting'] ) && $newarr['arfsucessbgcolorsetting'] != '' ) ? esc_attr( $newarr['arfsucessbgcolorsetting'] ) : $skinJson->skins->$active_skin->success_msg->background;
					$successBgColor = ( substr( $successBgColor, 0, 1 ) != '#' ) ? '#' . $successBgColor : $successBgColor;

					$successBrdColor = ( isset( $newarr['arfsucessbordercolorsetting'] ) && $newarr['arfsucessbordercolorsetting'] != '' ) ? esc_attr( $newarr['arfsucessbordercolorsetting'] ) : $skinJson->skins->$active_skin->success_msg->border;
					$successBrdColor = ( substr( $successBrdColor, 0, 1 ) != '#' ) ? '#' . $successBrdColor : $successBrdColor;

					$successTxtColor = ( isset( $newarr['arfsucesstextcolorsetting'] ) && $newarr['arfsucesstextcolorsetting'] != '' ) ? esc_attr( $newarr['arfsucesstextcolorsetting'] ) : $skinJson->skins->$active_skin->success_msg->text;
					$successTxtColor = ( substr( $successTxtColor, 0, 1 ) != '#' ) ? '#' . $successTxtColor : $successTxtColor;

					$errorBgColor = ( isset( $newarr['arfformerrorbgcolorsettings'] ) && $newarr['arfformerrorbgcolorsettings'] != '' ) ? esc_attr( $newarr['arfformerrorbgcolorsettings'] ) : $skinJson->skins->$active_skin->error_msg->background;
					$errorBgColor = ( substr( $errorBgColor, 0, 1 ) != '#' ) ? '#' . $errorBgColor : $errorBgColor;

					$errorBrdColor = ( isset( $newarr['arfformerrorbordercolorsettings'] ) && $newarr['arfformerrorbordercolorsettings'] != '' ) ? esc_attr( $newarr['arfformerrorbordercolorsettings'] ) : $skinJson->skins->$active_skin->error_msg->border;
					$errorBrdColor = ( substr( $errorBrdColor, 0, 1 ) != '#' ) ? '#' . $errorBrdColor : $errorBrdColor;

					$errorTxtColor = ( isset( $newarr['arfformerrortextcolorsettings'] ) && $newarr['arfformerrortextcolorsettings'] != '' ) ? esc_attr( $newarr['arfformerrortextcolorsettings'] ) : $skinJson->skins->$active_skin->error_msg->text;
					$errorTxtColor = ( substr( $errorTxtColor, 0, 1 ) != '#' ) ? '#' . $errorTxtColor : $errorTxtColor;


					$checkboxColor = ( isset( $newarr['checked_checkbox_icon_color'] ) && $newarr['checked_checkbox_icon_color'] != '' ) ? esc_attr( $newarr['checked_checkbox_icon_color'] ) : $skinJson->skins->$active_skin->input->checkbox_icon_color;
					$checkboxColor = ( substr( $checkboxColor, 0, 1 ) != '#' ) ? '#' . $checkboxColor : $checkboxColor;

					$radioColor = ( isset( $newarr['checked_radio_icon_color'] ) && $newarr['checked_radio_icon_color'] != '' ) ? esc_attr( $newarr['checked_radio_icon_color'] ) : $skinJson->skins->$active_skin->input->radio_icon_color;
					$radioColor = ( substr( $radioColor, 0, 1 ) != '#' ) ? '#' . $radioColor : $radioColor;

					$validationBgColor = ( isset( $newarr['arfvalidationbgcolorsetting'] ) && $newarr['arfvalidationbgcolorsetting'] != '' ) ? esc_attr( $newarr['arfvalidationbgcolorsetting'] ) : ( ( $active_skin != 'custom' ) ? $skinJson->skins->$active_skin->validation_msg->background : '' );
					$validationBgColor = ( substr( $validationBgColor, 0, 1 ) != '#' ) ? '#' . $validationBgColor : $validationBgColor;

					$validationTxtColor = ( isset( $newarr['arfvalidationtextcolorsetting'] ) && $newarr['arfvalidationtextcolorsetting'] != '' ) ? esc_attr( $newarr['arfvalidationtextcolorsetting'] ) : ( ( $active_skin != 'custom' ) ? $skinJson->skins->$active_skin->validation_msg->text : '' );
					$validationTxtColor = ( substr( $validationTxtColor, 0, 1 ) != '#' ) ? '#' . $validationTxtColor : $validationTxtColor;

					$datepickerBgColor = ( isset( $newarr['arfdatepickerbgcolorsetting'] ) && $newarr['arfdatepickerbgcolorsetting'] != '' ) ? esc_attr( $newarr['arfdatepickerbgcolorsetting'] ) : $skinJson->skins->$active_skin->datepicker->background;
					$datepickerBgColor = ( substr( $datepickerBgColor, 0, 1 ) != '#' ) ? '#' . $datepickerBgColor : $datepickerBgColor;

					$datepickerTxtColor = ( isset( $newarr['arfdatepickertextcolorsetting'] ) && $newarr['arfdatepickertextcolorsetting'] != '' ) ? esc_attr( $newarr['arfdatepickertextcolorsetting'] ) : $skinJson->skins->$active_skin->datepicker->text;
					$datepickerTxtColor = ( substr( $datepickerTxtColor, 0, 1 ) != '#' ) ? '#' . $datepickerTxtColor : $datepickerTxtColor;

					$uploadBtnTxtColor = ( isset( $newarr['arfuploadbtntxtcolorsetting'] ) && $newarr['arfuploadbtntxtcolorsetting'] != '' ) ? esc_attr( $newarr['arfuploadbtntxtcolorsetting'] ) : $skinJson->skins->$active_skin->uploadbutton->text;
					$uploadBtnTxtColor = ( substr( $uploadBtnTxtColor, 0, 1 ) != '#' ) ? '#' . $uploadBtnTxtColor : $uploadBtnTxtColor;

					$uploadBtnBgColor = ( isset( $newarr['arfuploadbtnbgcolorsetting'] ) && $newarr['arfuploadbtnbgcolorsetting'] != '' ) ? esc_attr( $newarr['arfuploadbtnbgcolorsetting'] ) : $skinJson->skins->$active_skin->uploadbutton->background;
					$uploadBtnBgColor = ( substr( $uploadBtnBgColor, 0, 1 ) != '#' ) ? '#' . $uploadBtnBgColor : $uploadBtnBgColor;

					$sliderLeftColor = ( isset( $newarr['arfsliderselectioncolor'] ) && $newarr['arfsliderselectioncolor'] != '' ) ? esc_attr( $newarr['arfsliderselectioncolor'] ) : $skinJson->skins->$active_skin->input->slider_selection_color;
					$sliderLeftColor = ( substr( $sliderLeftColor, 0, 1 ) != '#' ) ? '#' . $sliderLeftColor : $sliderLeftColor;

					$sliderRightColor = ( isset( $newarr['arfslidertrackcolor'] ) && $newarr['arfslidertrackcolor'] != '' ) ? esc_attr( $newarr['arfslidertrackcolor'] ) : $skinJson->skins->$active_skin->input->slider_track_color;
					$sliderRightColor = ( substr( $sliderRightColor, 0, 1 ) != '#' ) ? '#' . $sliderRightColor : $sliderRightColor;

					?>
					<div class="arf_custom_color_popup_header"><?php echo esc_html__( 'Custom Color', 'arforms-form-builder' ); ?></div>
					<div class="arf_custom_color_popup_container">
						<div class="arf_custom_color_popup_table">
							<div class="arf_custom_color_popup_table_row">
								<div class="arf_custom_color_popup_left_item" id="form_level_colors"><span><?php echo esc_html__( 'Form', 'arforms-form-builder' ); ?></span></div>
								<div class="arf_custom_color_popup_right_item_wrapper">
									<div class="arf_custom_color_popup_right_item">

										<div class="arf_custom_color_popup_picker jscolor" data-fid="arfformbgcolorsetting" style="background:<?php echo str_replace( '##', '#', esc_attr($bgColor) ); //phpcs:ignore ?>;" data-skin="form.background" data-default-color="<?php echo str_replace( '##', '#', esc_attr($bgColor) ); //phpcs:ignore ?>" data-jscolor='{"hash":true,"valueElement":"arfformbgcolorsetting","onfinechange":"arflite_update_color(this,\"arfformbgcolorsetting\")"}'></div>
										<input type="hidden" name="arffbcs" id="arfformbgcolorsetting" class="txtxbox_widget  hex" value="<?php echo str_replace( '##', '#', esc_attr($bgColor) ); //phpcs:ignore ?>" data-arfstyle="true" data-arfstyledata='{"standard":".arflite_main_div_{arf_form_id} .arf_fieldset~|~background-color","material":".arflite_main_div_{arf_form_id} .arf_fieldset~|~background-color"}'  data-arfstyleappend="true" data-arfstyleappendid="arf_{arf_form_id}_form_background_color" />
										<?php echo esc_html__( 'Background', 'arforms-form-builder' ); ?>
									</div>

									<div class="arf_custom_color_popup_right_item">
										<div class="arf_custom_color_popup_picker jscolor" data-fid="arfformtitlecolor" style="background:<?php echo str_replace( '##', '#', esc_attr($frmTitleColor) ); //phpcs:ignore ?>;" data-skin="form.title" data-default-color="<?php echo str_replace( '##', '#', esc_attr($frmTitleColor) ); //phpcs:ignore ?>" data-jscolor='{"hash":true,"valueElement":"arfformtitlecolor","onfinechange":"arflite_update_color(this,\"arfformtitlecolor\")"}'></div>
										<input type="hidden" name="arfftc" id="arfformtitlecolor" class="hex  txtxbox_widget" data-arfstyle="true" data-arfstyle="true" data-arfstyledata='{"standard":".arflite_main_div_{arf_form_id} .arf_fieldset .formtitle_style~|~color||.arflite_main_div_{arf_form_id} .arf_fieldset .formdescription_style~|~color","material":".arflite_main_div_{arf_form_id} .arf_fieldset .formtitle_style~|~color||.arflite_main_div_{arf_form_id} .arf_fieldset .formdescription_style~|~color"}' data-arfstyleappend="true" data-arfstyleappendid="arf_{arf_form_id}_form_title_color" value="<?php echo str_replace( '##', '#', esc_attr($frmTitleColor) ); //phpcs:ignore ?>" /><?php echo esc_html__( 'Form Title', 'arforms-form-builder' ); ?>
									</div>

									<div class="arf_custom_color_popup_right_item">
										<div class="arf_custom_color_popup_picker jscolor" data-fid="arfmainfieldsetcolor" style="background:<?php echo str_replace( '##', '#', esc_attr($formBrdColor) ); //phpcs:ignore ?>;" data-skin="form.border" data-default-color="<?php echo str_replace( '##', '#', esc_attr($formBrdColor) ); //phpcs:ignore ?>" data-jscolor='{"hash":true,"valueElement":"arfmainfieldsetcolor","onfinechange":"arflite_update_color(this,\"arfmainfieldsetcolor\")"}'></div>
										<input type="hidden" name="arfmfsc" id="arfmainfieldsetcolor" class="hex  txtxbox_widget" data-arfstyle="true" data-arfstyledata='{"standard":".arflite_main_div_{arf_form_id} .arf_fieldset~|~border-color","material":".arflite_main_div_{arf_form_id} .arf_fieldset~|~border-color"}' data-arfstyleappend="true" data-arfstyleappendid="arf_{arf_form_id}_form_border_color" value="<?php echo str_replace( '##', '#', esc_attr($formBrdColor) ); //phpcs:ignore ?>" /><?php echo esc_html__( 'Border', 'arforms-form-builder' ); //phpcs:ignore ?>
									</div>

									<div class="arf_popup_clear"></div>

									<div class="arf_custom_color_popup_right_item">
										<div class="arf_custom_color_popup_picker jscolor" data-fid="arfformbordershadowsetting" data-skin="form.shadow" style="background:<?php echo str_replace( '##', '#', esc_attr($formShadowColor) ); //phpcs:ignore ?>;" data-default-color="<?php echo str_replace( '##', '#', esc_attr($formShadowColor) ); //phpcs:ignore ?>" data-jscolor='{"hash":true,"valueElement":"arfformbordershadowsetting","onfinechange":"arflite_update_color(this,\"arfformbordershadowsetting\")"}'></div>
										<input type="hidden" name="arffboss" id="arfformbordershadowsetting" data-arfstyle="true" data-arfstyledata='{"standard":".arflite_main_div_{arf_form_id} .arf_fieldset~|~box-shadow","property":".arflite_main_div_{arf_form_id} .arf_fieldset~|~box-shadow","material":".arflite_main_div_{arf_form_id} .arf_fieldset~|~box-shadow","property":".arflite_main_div_{arf_form_id} .arf_fieldset~|~box-shadow"}' data-arfstyleappend="true" data-arfstyleappendid="arf_{arf_form_id}_form_border_type" class="hex txtxbox_widget " value="<?php echo str_replace( '##', '#', esc_attr($formShadowColor) ); //phpcs:ignore ?>" /> <?php echo esc_html__( 'Shadow', 'arforms-form-builder' ); //phpcs:ignore ?>
									</div>

									<div class="arf_popup_clear"></div>
									
									<div class="arf_custom_color_popup_right_item arfsectionbgwrap arfdisablediv">
										<div class="arf_custom_checkbox_div">
											<div class="arf_custom_checkbox_wrapper arfdisablediv">
												<input type="checkbox" value="1" id="arf_section_inherit_bg" class="arf_restricted_control" name="arf_section_inherit_bg"/>
												<svg width="18px" height="18px">
													<?php echo ARFLITE_CUSTOM_UNCHECKED_ICON; //phpcs:ignore ?>
													<?php echo ARFLITE_CUSTOM_CHECKED_ICON; //phpcs:ignore ?>
												</svg>
											</div>
										</div>
										<label for="arf_section_inherit_bg" class="arfsection-bgcolor-lbl"><?php echo esc_html__( 'Section Background', 'arforms-form-builder' ); ?></label>
									</div>
									<div id="arf_allow_section_bg" class="arf_custom_color_popup_right_item arfdisablediv arf_restricted_control" style="width:15%;">
										<div class="arf_custom_color_popup_picker jscolor arfdisablediv arf_restricted_control" data-fid="arfformsectionbackgroundcolor" data-skin="" data-default-color="#ffffff" id="arf_allow_section_bg_inner" data-jscolor='{"hash":true,"valueElement":"arfformsectionbackgroundcolor","onfinechange":"arflite_update_color(this,\"arfformsectionbackgroundcolor\")"}'></div>
										<input type="hidden" name="arfsecbg" id="arfformsectionbackgroundcolor" class="hex  txtxbox_widget" value="#ffffff" />
									</div>

									<div class="arf_popup_clear"></div>
								</div>
							</div>
							<div class="arf_custom_color_popup_table_row">
								<div class="arf_custom_color_popup_left_item" id="input_colors"><span><?php echo esc_html__( 'Main Input Colors', 'arforms-form-builder' ); ?></span></div>
								<div class="arf_custom_color_popup_right_item_wrapper">

									<div class="arf_custom_color_popup_right_item">
										<div class="arf_custom_color_popup_picker jscolor" data-fid="arfmainbasecolor" style="background:<?php echo str_replace( '##', '#', esc_attr($inputBaseColor) ); //phpcs:ignore ?>;" data-default-color="<?php echo str_replace( '##', '#', esc_attr($inputBaseColor) ); //phpcs:ignore ?>" data-skin="input.main" data-jscolor='{"hash":true,"valueElement":"arfmainbasecolor","onfinechange":"arflite_update_color(this,\"arfmainbasecolor\")"}'></div>
										<input type="hidden" name="arfmbsc" data-arfstyle="true" data-arfstyledata='<?php echo esc_attr( json_encode( $skinJson->css_main_classes ) ); //phpcs:ignore ?>' data-arfstyleappend="true" data-arfstyleappendid="arf_{arf_form_id}_form_main_style" value="<?php echo esc_attr( $inputBaseColor ); //phpcs:ignore ?>" id="arfmainbasecolor" class="txtxbox_widget hex" style="width:100%;" />
										<?php echo esc_html__( 'Base/Active Color', 'arforms-form-builder' ); ?>
									</div>

									<div class="arf_custom_color_popup_right_item">
										<div class="arf_custom_color_popup_picker jscolor" data-fid="arftextcolorsetting" style="background:<?php echo str_replace( '##', '#', esc_attr($inputTxtColor) ); //phpcs:ignore ?>;" data-skin="input.text" data-default-color="<?php echo str_replace( '##', '#', esc_attr($inputTxtColor) );//phpcs:ignore ?>" data-jscolor='{"hash":true,"valueElement":"arftextcolorsetting","onfinechange":"arflite_update_color(this,\"arftextcolorsetting\")"}'></div>
										<input type="hidden" name="arftcs" id="arftextcolorsetting" class="txtxbox_widget  hex" data-arfstyle="true" data-arfstyledata='{"standard":".arflite_main_div_{arf_form_id} .arf_fieldset .controls input[type=text]:not(.inplace_field):not(.arf_field_option_input_text)~|~color||.arflite_main_div_{arf_form_id} .arf_fieldset .controls input[type=email]~|~color||.arflite_main_div_{arf_form_id} .arf_fieldset .controls input[type=number]~|~color||.arflite_main_div_{arf_form_id} .arf_fieldset .controls input[type=url]~|~color||.arflite_main_div_{arf_form_id} .arf_fieldset .controls input[type=tel]~|~color||.arflite_main_div_{arf_form_id} .arf_fieldset .controls textarea~|~color||.arflite_main_div_{arf_form_id} .arf_fieldset .controls .bootstrap-select .dropdown-toggle~|~color||.arflite_main_div_{arf_form_id} .arf_fieldset .controls .bootstrap-select .dropdown-toggle:focus~|~color||.arflite_main_div_{arf_form_id} .arf_fieldset .controls .bootstrap-select ul li a~|~color||.arflite_main_div_{arf_form_id} input[type=text]:not(.arfslider):not(.arf_autocomplete):not(.arf_field_option_input_text):not(.inplace_field)::-webkit-input-placeholder~|~color||.wp-admin .allfields .controls .smaple-textarea::-webkit-input-placeholder~|~color||.arflite_main_div_{arf_form_id} .controls textarea::-webkit-input-placeholder~|~color||.arflite_main_div_{arf_form_id} input[type=number]::-webkit-input-placeholder~|~color||.arflite_main_div_{arf_form_id} input[type=url]::-webkit-input-placeholder~|~color||.arflite_main_div_{arf_form_id} input[type=tel]::-webkit-input-placeholder~|~color||.arflite_main_div_{arf_form_id} select::-webkit-input-placeholder~|~color||.arflite_main_div_{arf_form_id} input[type=text]:not(.arfslider):not(.arf_autocomplete):not(.arf_field_option_input_text):not(.inplace_field):-moz-placeholder~|~color||.wp-admin .allfields .controls .smaple-textarea:-moz-placeholder~|~color||.arflite_main_div_{arf_form_id} .controls textarea:-moz-placeholder~|~color||.arflite_main_div_{arf_form_id} input[type=number]:-moz-placeholder~|~color||.arflite_main_div_{arf_form_id} input[type=url]:-moz-placeholder~|~color||.arflite_main_div_{arf_form_id} input[type=tel]:-moz-placeholder~|~color||.arflite_main_div_{arf_form_id} select:-moz-placeholder~|~color||.arflite_main_div_{arf_form_id} input[type=text]:not(.arfslider):not(.arf_autocomplete):not(.arf_field_option_input_text):not(.inplace_field)::-moz-placeholder~|~color||.wp-admin .allfields .controls .smaple-textarea::-moz-placeholder~|~color||.arflite_main_div_{arf_form_id} .controls textarea::-moz-placeholder~|~color||.arflite_main_div_{arf_form_id} input[type=number]::-moz-placeholder~|~color||.arflite_main_div_{arf_form_id} input[type=url]::-moz-placeholder~|~color||.arflite_main_div_{arf_form_id} input[type=tel]::-moz-placeholder~|~color||.arflite_main_div_{arf_form_id} select::-moz-placeholder~|~color||.arflite_main_div_{arf_form_id} input[type=text]:not(.arfslider):not(.arf_autocomplete):not(.arf_field_option_input_text):not(.inplace_field):-ms-input-placeholder~|~color||.wp-admin .allfields .controls .smaple-textarea:-ms-input-placeholder~|~color||.arflite_main_div_{arf_form_id} .controls textarea:-ms-input-placeholder~|~color||.arflite_main_div_{arf_form_id} input[type=number]:-ms-input-placeholder~|~color||.arflite_main_div_{arf_form_id} input[type=url]:-ms-input-placeholder~|~color||.arflite_main_div_{arf_form_id} input[type=tel]:-ms-input-placeholder~|~color||.arflite_main_div_{arf_form_id} select:-ms-input-placeholder~|~color||.arflite_main_div_{arf_form_id} input[type=text]:not(.arfslider):not(.arf_autocomplete):not(.arf_field_option_input_text):not(.inplace_field)::-ms-input-placeholder~|~color||.wp-admin .allfields .controls .smaple-textarea::-ms-input-placeholder~|~color||.arflite_main_div_{arf_form_id} .controls textarea::-ms-input-placeholder~|~color||.arflite_main_div_{arf_form_id} input[type=number]::-ms-input-placeholder~|~color||.arflite_main_div_{arf_form_id} input[type=url]::-ms-input-placeholder~|~color||.arflite_main_div_{arf_form_id} input[type=tel]::-ms-input-placeholder~|~color||.arflite_main_div_{arf_form_id} select::-ms-input-placeholder~|~color||.arflite_main_div_{arf_form_id} .sltstandard_front .arf-selectpicker-control.arf_form_field_picker dt span~|~color||.arflite_main_div_{arf_form_id} .sltstandard_front .arf-selectpicker-control.arf_form_field_picker dd ul li~|~color||.arflite_main_div_{arf_form_id} .sltstandard_front .arf-selectpicker-control.arf_form_field_picker dd ul li.arm_sel_opt_checked::before~|~background||.arflite_main_div_{arf_form_id} .sltstandard_front .arf-selectpicker-control.arf_form_field_picker dd ul li.arm_sel_opt_checked::after~|~background","material":".arflite_main_div_{arf_form_id}  .arf_materialize_form .controls input[type=text]:not(.inplace_field):not(.arf_field_option_input_text):not(.arfslider):not(.arf_autocomplete)~|~color||.arflite_main_div_{arf_form_id}  .arf_materialize_form .controls input[type=email]~|~color||.arflite_main_div_{arf_form_id}  .arf_materialize_form .controls input[type=number]~|~color||.arflite_main_div_{arf_form_id}  .arf_materialize_form .controls input[type=url]~|~color||.arflite_main_div_{arf_form_id}  .arf_materialize_form .controls input[type=tel]~|~color||.arflite_main_div_{arf_form_id}  .arf_materialize_form .controls input[type=text].arf-select-dropdown~|~color||.arflite_main_div_{arf_form_id}  .arf_materialize_form .controls textarea~|~color||.arflite_main_div_{arf_form_id} .arf_materialize_form input[type=text]:not(.inplace_field):not(.arf_field_option_input_text):not(.arfslider):not(.arfhiddencolor):not(.arf_autocomplete)~|~color||.arflite_main_div_{arf_form_id} .arf_materialize_form .arf-select-dropdown~|~color||.arflite_main_div_{arf_form_id} .arf_materialize_form ul.arf-select-dropdown li~|~color||.arflite_main_div_{arf_form_id} .arf_materialize_form input[type=text]:not(.inplace_field):not(.arf_field_option_input_text):not(.arfslider):not(.arfhiddencolor):not(.arf_autocomplete)::-webkit-input-placeholder~|~color||.arflite_main_div_{arf_form_id} .arf_materialize_form textarea:not(.html_field_description)::-webkit-input-placeholder~|~color||.arflite_main_div_{arf_form_id} .arf_materialize_form input[type=email]::-webkit-input-placeholder~|~color||.arflite_main_div_{arf_form_id} .arf_materialize_form input[type=number]::-webkit-input-placeholder~|~color||.arflite_main_div_{arf_form_id} .arf_materialize_form input[type=url]::-webkit-input-placeholder~|~color||.arflite_main_div_{arf_form_id} .arf_materialize_form input[type=tel]::-webkit-input-placeholder~|~color||.arflite_main_div_{arf_form_id} .arf_materialize_form select::-webkit-input-placeholder~|~color||.arflite_main_div_{arf_form_id} .arf_materialize_form input[type=text]:not(.inplace_field):not(.arf_field_option_input_text):not(.arfslider):not(.arfhiddencolor):not(.arf_autocomplete):-moz-placeholder~|~color||.arflite_main_div_{arf_form_id} .arf_materialize_form textarea:not(.html_field_description):-moz-placeholder~|~color||.arflite_main_div_{arf_form_id} .arf_materialize_form input[type=email]:-moz-placeholder~|~color||.arflite_main_div_{arf_form_id} .arf_materialize_form input[type=number]:-moz-placeholder~|~color||.arflite_main_div_{arf_form_id} .arf_materialize_form input[type=url]:-moz-placeholder~|~color||.arflite_main_div_{arf_form_id} .arf_materialize_form input[type=tel]:-moz-placeholder~|~color||.arflite_main_div_{arf_form_id} .arf_materialize_form select:-moz-placeholder~|~color||.arflite_main_div_{arf_form_id} .arf_materialize_form input[type=text]:not(.inplace_field):not(.arf_field_option_input_text):not(.arfslider):not(.arfhiddencolor):not(.arf_autocomplete)::-moz-placeholder~|~color||.arflite_main_div_{arf_form_id} .arf_materialize_form textarea:not(.html_field_description)::-moz-placeholder~|~color||.arflite_main_div_{arf_form_id} .arf_materialize_form input[type=email]::-moz-placeholder~|~color||.arflite_main_div_{arf_form_id} .arf_materialize_form input[type=number]::-moz-placeholder~|~color||.arflite_main_div_{arf_form_id} .arf_materialize_form input[type=url]::-moz-placeholder~|~color||.arflite_main_div_{arf_form_id} .arf_materialize_form input[type=tel]::-moz-placeholder~|~color||.arflite_main_div_{arf_form_id} .arf_materialize_form select::-moz-placeholder~|~color||.arflite_main_div_{arf_form_id} .arf_materialize_form input[type=text]:not(.inplace_field):not(.arf_field_option_input_text):not(.arfslider):not(.arfhiddencolor):not(.arf_autocomplete):-ms-input-placeholder~|~color||.arflite_main_div_{arf_form_id} .arf_materialize_form textarea:not(.html_field_description):-ms-input-placeholder~|~color||.arflite_main_div_{arf_form_id} .arf_materialize_form input[type=email]:-ms-input-placeholder~|~color||.arflite_main_div_{arf_form_id} .arf_materialize_form input[type=number]:-ms-input-placeholder~|~color||.arflite_main_div_{arf_form_id} .arf_materialize_form input[type=url]:-ms-input-placeholder~|~color||.arflite_main_div_{arf_form_id} .arf_materialize_form input[type=tel]:-ms-input-placeholder~|~color||.arflite_main_div_{arf_form_id} .arf_materialize_form select:-ms-input-placeholder~|~color||.arflite_main_div_{arf_form_id} .arf_materialize_form input[type=text]:not(.inplace_field):not(.arf_field_option_input_text):not(.arfslider):not(.arfhiddencolor):not(.arf_autocomplete)::-ms-input-placeholder~|~color||.arflite_main_div_{arf_form_id} .arf_materialize_form textarea:not(.html_field_description)::-ms-input-placeholder~|~color||.arflite_main_div_{arf_form_id} .arf_materialize_form input[type=email]::-ms-input-placeholder~|~color||.arflite_main_div_{arf_form_id} .arf_materialize_form input[type=number]::-ms-input-placeholder~|~color||.arflite_main_div_{arf_form_id} .arf_materialize_form input[type=url]::-ms-input-placeholder~|~color||.arflite_main_div_{arf_form_id} .arf_materialize_form input[type=tel]::-ms-input-placeholder~|~color||.arflite_main_div_{arf_form_id} .arf_materialize_form select::-ms-input-placeholder~|~color||.arflite_main_div_{arf_form_id} .arf_materialize_form .sltstandard_front .arf-selectpicker-control.arf_form_field_picker dt span~|~color||.arflite_main_div_{arf_form_id} .arf_materialize_form .sltstandard_front .arf-selectpicker-control.arf_form_field_picker dd ul li~|~color||.arflite_main_div_{arf_form_id} .arf_materialize_form .sltstandard_front .arf-selectpicker-control.arf_form_field_picker dd ul li.arm_sel_opt_checked::before~|~background||.arflite_main_div_{arf_form_id} .arf_materialize_form .sltstandard_front .arf-selectpicker-control.arf_form_field_picker dd ul li.arm_sel_opt_checked::after~|~background"}' data-arfstyleappend="true" data-arfstyleappendid="arf_{arf_form_id}_input_text_color" value="<?php echo str_replace( '##', '#', $inputTxtColor );//phpcs:ignore ?>" />
										<?php echo esc_html__( 'Text Color', 'arforms-form-builder' ); ?>
									</div>

									<div class="arf_custom_color_popup_right_item">
										<div class="arf_custom_color_popup_picker jscolor" data-fid="frm_border_color" style="background:<?php echo str_replace( '##', '#', esc_attr($inputBrdColor) ); //phpcs:ignore ?>;" data-skin="input.border" data-default-color="<?php echo str_replace( '##', '#', esc_attr($inputBrdColor) ); //phpcs:ignore ?>" data-jscolor='{"hash":true,"valueElement":"frm_border_color","onfinechange":"arflite_update_color(this,\"frm_border_color\")"}'></div>
										<input type="hidden" name="arffmboc" id="frm_border_color" class="txtxbox_widget  hex" value="<?php echo str_replace( '##', '#', esc_attr($inputBrdColor) ); //phpcs:ignore ?>" data-arfstyle="true" data-arfstyledata='{"standard":".arflite_main_div_{arf_form_id} input[type=text]:not(.inplace_field):not(.arf_field_option_input_text):not(.arf_autocomplete):not(.arfslider)~|~border-color||.arflite_main_div_{arf_form_id} .arf_editor_prefix_suffix_wrapper .arf_editor_suffix_icon~|~border-color||.arflite_main_div_{arf_form_id} .arf_editor_prefix_suffix_wrapper .arf_editor_prefix_icon~|~border-color||.arflite_main_div_{arf_form_id} .controls input[type=email]~|~border-color||.arflite_main_div_{arf_form_id} .controls input[type=number]~|~border-color||.arflite_main_div_{arf_form_id} .controls input[type=url]~|~border-color||.arflite_main_div_{arf_form_id} .controls input[type=tel]~|~border-color||.arflite_main_div_{arf_form_id} .controls textarea~|~border-color||.arflite_main_div_{arf_form_id} .controls select~|~border-color||.arflite_main_div_{arf_form_id} .sltstandard_front .btn-group .arfbtn.dropdown-toggle~|~border-color||.arflite_main_div_{arf_form_id} .setting_checkbox.arf_standard_checkbox .arf_checkbox_input_wrapper input[type=checkbox]:not(:checked) + span~|~border-color||.arflite_main_div_{arf_form_id} .setting_radio.arf_standard_radio .arf_radio_input_wrapper input[type=radio] + span~|~border-color||.arflite_main_div_{arf_form_id} .controls .dropdown-toggle .arf_caret~|~border-top-color||.arflite_main_div_{arf_form_id} .sltstandard_front .arf-selectpicker-control.arf_form_field_picker dt~|~border-color||.arflite_main_div_{arf_form_id} .sltstandard_front .arf-selectpicker-control.arf_form_field_picker dt i.arf-selectpicker-caret~|~border-top-color","material":".arflite_main_div_{arf_form_id} .arf_materialize_form .controls input[type=text]:not(.inplace_field):not(.arf_field_option_input_text):not(.arfslider):not(.arfhiddencolor)~|~border-bottom-color||.arflite_main_div_{arf_form_id} .controls input[type=email]~|~border-bottom-color||.arflite_main_div_{arf_form_id} .controls input[type=number]~|~border-bottom-color||.arflite_main_div_{arf_form_id} .controls input[type=url]~|~border-bottom-color||.arflite_main_div_{arf_form_id} .controls input[type=tel]~|~border-bottom-color||.arflite_main_div_{arf_form_id} .arf_materialize_form .controls textarea:not(.html_field_description):not(.g-recaptcha-response):not(.wp-editor-area)~|~border-bottom-color||.arflite_main_div_{arf_form_id} .controls select~|~border-color||.arflite_main_div_{arf_form_id} .controls .arfdropdown-menu.open~|~border-color||.arflite_main_div_{arf_form_id} .arf_materialize_form .controls textarea:not(.html_field_description):not(.g-recaptcha-response):not(.wp-editor-area)~|~border-bottom-color||.arf_form_outer_wrapper .setting_checkbox.arf_material_checkbox.arf_default_material .arf_checkbox_input_wrapper input[type=checkbox] + span::after~|~border-color||.arf_form_outer_wrapper .setting_checkbox.arf_material_checkbox.arf_advanced_material .arf_checkbox_input_wrapper input[type=checkbox] + span::before~|~border-color||.arf_form_outer_wrapper .setting_radio.arf_material_radio.arf_default_material .arf_radio_input_wrapper input[type=radio] + span::before~|~border-color||.arf_form_outer_wrapper .setting_radio.arf_material_radio.arf_advanced_material .arf_radio_input_wrapper input[type=radio] + span::before~|~border-color||.arflite_main_div_{arf_form_id} .arf_materialize_form .select-wrapper .caret~|~color||.arflite_main_div_{arf_form_id} .arf_materialize_form .sltstandard_front .arf-selectpicker-control.arf_form_field_picker dt~|~border-color||.arflite_main_div_{arf_form_id} .arf_materialize_form .sltstandard_front .arf-selectpicker-control.arf_form_field_picker dt i.arf-selectpicker-caret~|~border-top-color"}' data-arfstyleappend="true" data-arfstyleappendid="arf_{arf_form_id}_input_border_color" />
										<?php echo esc_html__( 'Border Color', 'arforms-form-builder' ); ?>
									</div>

									<div class="arf_popup_clear"></div>

									<div class="arf_custom_color_popup_right_item <?php echo ( $newarr['arfinputstyle'] == 'material' ) ? 'arfdisablediv' : ''; ?>">
										<div class="arf_custom_color_popup_picker jscolor <?php echo ( $newarr['arfinputstyle'] == 'material' ) ? 'arfdisablediv' : ''; ?>" data-fid="frm_bg_color" style="background:<?php echo str_replace( '##', '#', esc_attr($inputBg)); //phpcs:ignore ?>;" data-skin="input.background" data-default-color="<?php echo str_replace( '##', '#', esc_attr($inputBg) ); //phpcs:ignore ?>" data-jscolor='{"hash":true,"valueElement":"frm_bg_color","onfinechange":"arflite_update_color(this,\"frm_bg_color\")"}'></div>
										<input type="hidden" name="arffmbc" id="frm_bg_color" class="txtxbox_widget  hex" value="<?php echo str_replace( '##', '#', esc_attr($inputBg) ); //phpcs:ignore ?>" data-arfstyle="true" data-arfstyledata='{"standard":".arflite_main_div_{arf_form_id} .controls input[type=text]:not(.inplace_field):not(.arf_field_option_input_text):not(.arf_autocomplete):not(.arfslider)~|~check_field_transparency||.arflite_main_div_{arf_form_id} .controls input[type=email]~|~check_field_transparency||.arflite_main_div_{arf_form_id} .controls input[type=number]~|~check_field_transparency||.arflite_main_div_{arf_form_id} .controls input[type=url]~|~check_field_transparency||.arflite_main_div_{arf_form_id} .controls input[type=tel]~|~check_field_transparency||.arflite_main_div_{arf_form_id} .controls textarea~|~check_field_transparency||.arflite_main_div_{arf_form_id} .controls select~|~check_field_transparency||.arflite_main_div_{arf_form_id} .sltstandard_front .btn-group .arfbtn.dropdown-toggle~|~check_field_transparency||.arflite_main_div_{arf_form_id} .sltstandard_front .btn-group.open .arfdropdown-menu~|~check_field_transparency||.arflite_main_div_{arf_form_id} .sltstandard_front .btn-group.open .arfbtn.dropdown-toggle~|~check_field_transparency||.arflite_main_div_{arf_form_id} .sltstandard_front .arf-selectpicker-control.arf_form_field_picker dt~|~background||.arflite_main_div_{arf_form_id} .sltstandard_front .arf-selectpicker-control.arf_form_field_picker.open dd ul~|~background","material":".arflite_main_div_{arf_form_id} .controls input[type=text]:not(.inplace_field):not(.arf_field_option_input_text)~|~background-color||.arflite_main_div_{arf_form_id} .controls input[type=email]~|~background-color||.arflite_main_div_{arf_form_id} .controls input[type=number]~|~background-color||.arflite_main_div_{arf_form_id} .controls input[type=url]~|~background-color||.arflite_main_div_{arf_form_id} .controls input[type=tel]~|~background-color||.arflite_main_div_{arf_form_id} .controls textarea~|~background-color||.arflite_main_div_{arf_form_id} .controls select~|~background-color"}' data-arfstyleappend="true" data-arfstyleappendid="arf_{arf_form_id}_input_bg_color" />
										<?php echo esc_html__( 'Background', 'arforms-form-builder' ); ?>
									</div>

									<div class="arf_custom_color_popup_right_item  <?php echo ( $newarr['arfinputstyle'] == 'material' ) ? 'arfdisablediv' : ''; ?>">
										<div class="arf_custom_color_popup_picker jscolor  <?php echo ( $newarr['arfinputstyle'] == 'material' ) ? 'arfdisablediv' : ''; ?>" data-fid="arfbgcoloractivesetting" style="background:<?php echo str_replace( '##', '#', esc_attr($inputActiveBg) ); //phpcs:ignore ?>;" data-skin="input.background_active" data-default-color="<?php echo str_replace( '##', '#', esc_attr($inputActiveBg) ); //phpcs:ignore ?>" data-jscolor='{"hash":true,"valueElement":"arfbgcoloractivesetting","onfinechange":"arflite_update_color(this,\"arfbgcoloractivesetting\")"}'></div>
										<input type="hidden" name="arfbcas" id="arfbgcoloractivesetting" class="txtxbox_widget  hex" data-arfstyle="true" data-arfstyledata='{"standard":".arflite_main_div_{arf_form_id} .arfmainformfield .controls input:focus~|~check_field_focus_transparency||.arflite_main_div_{arf_form_id} textarea:focus:not(.arf_field_option_input_textarea)~|~check_field_focus_transparency||.arflite_main_div_{arf_form_id} input:focus:not(.inplace_field):not(.arf_autocomplete):not(.arfslider):not(.arf_field_option_input_text)~|~check_field_focus_transparency||.arflite_main_div_{arf_form_id} .controls input[type=text]:focus~|~check_field_focus_transparency||.arflite_main_div_{arf_form_id} .controls input[type=text]:focus:not(.inplace_field):not(.arf_field_option_input_text):not(.arf_autocomplete):not(.arfslider)~|~check_field_focus_transparency||.arflite_main_div_{arf_form_id} .controls input[type=text]:focus:not(.inplace_field):not(.arf_autocomplete):not(.arfslider)~|~check_field_focus_transparency||.arflite_main_div_{arf_form_id} .controls input[type=email]:focus~|~check_field_focus_transparency||.arflite_main_div_{arf_form_id} .controls input[type=number]:focus~|~check_field_focus_transparency||.arflite_main_div_{arf_form_id} .controls input[type=url]:focus~|~check_field_focus_transparency||.arflite_main_div_{arf_form_id} .controls input[type=tel]:focus~|~check_field_focus_transparency||.arflite_main_div_{arf_form_id} .arfmainformfield .controls textarea:focus~|~check_field_focus_transparency||.arflite_main_div_{arf_form_id} .arfmainformfield .controls select:focus~|~check_field_focus_transparency||.arflite_main_div_{arf_form_id} .sltstandard_front .btn-group .arfbtn.dropdown-toggle~|~check_field_focus_transparency||.arflite_main_div_{arf_form_id} .sltstandard_front .btn-group.open .arfdropdown-menu~|~check_field_focus_transparency||.arflite_main_div_{arf_form_id} .sltstandard_front .btn-group.open .arfbtn.dropdown-toggle~|~check_field_focus_transparency||.arflite_main_div_{arf_form_id} .sltstandard_front .arf-selectpicker-control.arf_form_field_picker dt:focus~|~background-color||.arflite_main_div_{arf_form_id} .sltstandard_front .arf-selectpicker-control.arf_form_field_picker.open dd ul~|~background-color","material":".arflite_main_div_{arf_form_id} .controls input[type=text]:not(.inplace_field):not(.arf_field_option_input_text):focus~|~background-color||.arflite_main_div_{arf_form_id} .controls input[type=email]:focus~|~background-color||.arflite_main_div_{arf_form_id} .controls input[type=number]:focus~|~background-color||.arflite_main_div_{arf_form_id} .controls input[type=url]:focus~|~background-color||.arflite_main_div_{arf_form_id} .controls input[type=tel]:focus~|~background-color||.arflite_main_div_{arf_form_id} .arfmainformfield .controls textarea:focus:not(.arf_field_option_input_textarea)~|~background-color||.arflite_main_div_{arf_form_id} .arfmainformfield .controls select:focus~|~background-color"}' data-arfstyleappend="true" data-arfstyleappendid="arf_{arf_form_id}_input_text_focus_bg_color" value="<?php echo str_replace( '##', '#', $inputActiveBg ); //phpcs:ignore ?>" />
										<?php echo esc_html__( 'Active State Background', 'arforms-form-builder' ); ?>
									</div>

									<div class="arf_custom_color_popup_right_item <?php echo ( $newarr['arfinputstyle'] == 'material' ) ? 'arfdisablediv' : ''; ?>">
										<div class="arf_custom_color_popup_picker jscolor <?php echo ( $newarr['arfinputstyle'] == 'material' ) ? 'arfdisablediv' : ''; ?>" data-fid="arfbgerrorcolorsetting" style="background:<?php echo str_replace( '##', '#', esc_attr($inputErrorBg) ); //phpcs:ignore ?>;" data-skin="input.background_error" data-default-color="<?php echo str_replace( '##', '#', esc_attr($inputErrorBg) ); //phpcs:ignore ?>" data-jscolor='{"hash":true,"valueElement":"arfbgerrorcolorsetting","onfinechange":"arflite_update_color(this,\"arfbgerrorcolorsetting\")"}'></div>
										<input type="hidden" name="arfbecs" id="arfbgerrorcolorsetting" class="txtxbox_widget  hex" value="<?php echo str_replace( '##', '#', esc_attr($inputErrorBg) ); //phpcs:ignore ?>" />
										<?php echo esc_html__( 'Error State Background', 'arforms-form-builder' ); ?>
									</div>

									<div class="arf_popup_clear"></div>

									<div class="arf_custom_color_popup_right_item">
										<div class="arf_custom_color_popup_picker jscolor" data-fid="arflabelcolorsetting" style="background:<?php echo str_replace( '##', '#', esc_attr($labelColor) ); //phpcs:ignore ?>;" data-skin="label.text" data-default-color="<?php echo str_replace( '##', '#', esc_attr($labelColor) ); //phpcs:ignore ?>" data-jscolor='{"hash":true,"valueElement":"arflabelcolorsetting","onfinechange":"arflite_update_color(this,\"arflabelcolorsetting\")"}'></div>
										<input type="hidden" name="arflcs" id="arflabelcolorsetting" class="txtxbox_widget  hex" data-arfstyle="true" data-arfstyledata='{"standard":".arflite_main_div_{arf_form_id} .arf_fieldset label.arf_main_label~|~color||.arflite_main_div_{arf_form_id} .arf_fieldset .arf_field_description~|~color||.arflite_main_div_{arf_form_id} .arf_checkbox_style label~|~color||.arflite_main_div_{arf_form_id} .arf_radiobutton label~|~color||.arflite_main_div_{arf_form_id} .bootstrap-datetimepicker-widget table span.month~|~color||.arflite_main_div_{arf_form_id} .bootstrap-datetimepicker-widget table span.year:not(.disabled)~|~color||.arflite_main_div_{arf_form_id} .bootstrap-datetimepicker-widget table span.decade:not(.disabled)~|~color||.arflite_main_div_{arf_form_id} .arf_cal_body span.year~|~color||.arflite_main_div_{arf_form_id} .arf_cal_body span.decade:not(.disabled)~|~color||.arflite_main_div_{arf_form_id} .arf_cal_body td span.month~|~color||.arflite_main_div_{arf_form_id} .datepicker .arf_cal_body .day:not(.old):not(.new)~|~color||.arflite_main_div_{arf_form_id} .timepicker .timepicker-hour~|~color||.arflite_main_div_{arf_form_id} .timepicker .timepicker-minute~|~color||.arflite_main_div_{arf_form_id} .timepicker .arf_cal_hour~|~color||.arflite_main_div_{arf_form_id} .timepicker .arf_cal_minute~|~color","material":".arflite_main_div_{arf_form_id} .arf_materialize_form.arf_fieldset label.arf_main_label~|~color||.arflite_main_div_{arf_form_id} .arf_materialize_form.arf_fieldset .arf_field_description~|~color||.arflite_main_div_{arf_form_id} .arf_materialize_form .arf_checkbox_style label~|~color||.arflite_main_div_{arf_form_id} .arf_materialize_form .arf_radiobutton label~|~color||.arflite_main_div_{arf_form_id} .arf_materialize_form .arf_checkbox_style label~|~color||.arflite_main_div_{arf_form_id} .arf_materialize_form .arf_radiobutton label~|~color||.arflite_main_div_{arf_form_id} .arf_materialize_form .arf_cal_body td span.month~|~color||.arflite_main_div_{arf_form_id} .arf_materialize_form .arf_cal_body td span.month:hover~|~border-color||.arflite_main_div_{arf_form_id} .datepicker .arf_cal_body .day:not(.old):not(.new)~|~color||.arflite_main_div_{arf_form_id} .timepicker .arf_cal_hour~|~color||.arflite_main_div_{arf_form_id} .timepicker .arf_cal_minute~|~color||..arflite_main_div_{arf_form_id} .arf_materialize_form .arf_cal_body span.month~|~color||.arflite_main_div_{arf_form_id} .arf_materialize_form .arf_cal_body span.year~|~color||.arflite_main_div_{arf_form_id} .arf_materialize_form .arf_cal_body span.decade:not(.disabled)~|~color||.arflite_main_div_{arf_form_id} .arf_materialize_form .arf_cal_body span.decade:not(.disabled):hover~|~border-color||.arflite_main_div_{arf_form_id} .arf_materialize_form .arf_cal_body span.year:hover~|~border-color"}' data-arfstyleappend="true" data-arfstyleappendid="arf_{arf_form_id}_label_color" value="<?php echo str_replace( '##', '#', $labelColor ); //phpcs:ignore ?>" />
										<?php echo esc_html__( 'Label Text Color', 'arforms-form-builder' ); ?>
									</div>

									<div class="arf_custom_color_popup_right_item <?php echo ( $newarr['arfinputstyle'] == 'material' || $newarr['arfinputstyle'] == 'rounded' ) ? 'arfdisablediv' : ''; ?>">
										<div class="arf_custom_color_popup_picker jscolor <?php echo ( $newarr['arfinputstyle'] == 'material' || $newarr['arfinputstyle'] == 'rounded' ) ? 'arfdisablediv' : ''; ?>" data-fid="prefix_suffix_bg_color" style="background:<?php echo str_replace( '##', '#', esc_attr($iconBgColor) ); //phpcs:ignore ?>;" data-skin="input.prefix_suffix_background" data-default-color="<?php echo str_replace( '##', '#', esc_attr($iconBgColor) ); //phpcs:ignore ?>" data-jscolor='{"hash":true,"valueElement":"prefix_suffix_bg_color","onfinechange":"arflite_update_color(this,\"prefix_suffix_bg_color\")"}'></div>
										<input type="hidden" name="pfsfsbg" id="prefix_suffix_bg_color" class="txtxbox_widget  hex" data-arfstyle="true" data-arfstyledata='{"standard":".arflite_main_div_{arf_form_id} .arf_standard_form .controls .arf_editor_prefix_suffix_wrapper .arf_editor_prefix_icon~|~background-color||.arflite_main_div_{arf_form_id} .arf_standard_form .controls .arf_editor_prefix_suffix_wrapper .arf_editor_suffix_icon~|~background-color||","material":".arflite_main_div_{arf_form_id} .controls .arf_editor_prefix_suffix_wrapper .arf_editor_prefix_icon~|~background-color||.arflite_main_div_{arf_form_id} .controls .arf_editor_prefix_suffix_wrapper .arf_editor_suffix_icon~|~background-color"}' data-arfstyleappend="true" data-arfstyleappendid="arf_{arf_form_id}_icon_bg_color" value="<?php echo str_replace( '##', '#', esc_attr($iconBgColor) ); //phpcs:ignore ?>" />
										<?php echo esc_html__( 'Icon Background', 'arforms-form-builder' ); ?>
									</div>

									<div class="arf_custom_color_popup_right_item <?php echo ( $newarr['arfinputstyle'] == 'material' ) ? 'arfdisablediv' : ''; ?>">
										<div class="arf_custom_color_popup_picker jscolor <?php echo ( $newarr['arfinputstyle'] == 'material' ) ? 'arfdisablediv' : ''; ?>" data-fid="prefix_suffix_icon_color" style="background:<?php echo str_replace( '##', '#', esc_attr($iconColor) );  //phpcs:ignore?>;" data-skin="input.prefix_suffix_icon_color" data-default-color="<?php echo str_replace( '##', '#', esc_attr($iconColor) ); //phpcs:ignore ?>" data-jscolor='{"hash":true,"valueElement":"prefix_suffix_icon_color","onfinechange":"arflite_update_color(this,\"prefix_suffix_icon_color\")"}'></div>
										<input type="hidden" name="pfsfscol" id="prefix_suffix_icon_color" class="txtxbox_widget  hex" data-arfstyle="true" data-arfstyledata='{"standard":".arflite_main_div_{arf_form_id} .controls .arf_editor_prefix_suffix_wrapper .arf_editor_prefix_icon~|~color||.arflite_main_div_{arf_form_id} .controls .arf_editor_prefix_suffix_wrapper .arf_editor_suffix_icon~|~color","material":".arflite_main_div_{arf_form_id} .controls .arf_editor_prefix_suffix_wrapper .arf_editor_prefix_icon~|~color||.arflite_main_div_{arf_form_id} .controls .arf_editor_prefix_suffix_wrapper .arf_editor_suffix_icon~|~color"}' data-arfstyleappend="true" data-arfstyleappendid="arf_{arf_form_id}_icon_color" value="<?php echo str_replace( '##', '#', esc_attr($iconColor) ); //phpcs:ignore ?>" />
										<?php echo esc_html__( 'Icon Color', 'arforms-form-builder' ); ?>
									</div>


									<input type="hidden" name="cbscol" id="checked_checkbox_icon_color" class="txtxbox_widget hex" value="<?php echo isset( $newarr['checked_checkbox_icon_color'] ) ? str_replace( '##', '#', esc_attr( $newarr['checked_checkbox_icon_color'] ) ) : ''; //phpcs:ignore ?>" />
									<input type="hidden" name="rbscol" id="checked_radio_icon_color" class="txtxbox_widget hex" value="<?php echo isset( $newarr['checked_radio_icon_color'] ) ? ( str_replace( '##', '#', esc_attr( $newarr['checked_radio_icon_color'] ) ) ) : ''; //phpcs:ignore ?>" />
									<div class="arf_popup_clear"></div>


									<span class="arf_custom_color_popup_subtitle"><?php echo esc_html__( 'Like Button', 'arforms-form-builder' ); ?></span>
									<div class="arf_custom_color_popup_right_item arfdisablediv arf_restricted_control">
										<div class="arf_custom_color_popup_picker jscolor arfdisablediv arf_restricted_control" data-fid="editor_like_button_color" style="background:#4786ff;" data-skin="" data-jscolor='{"hash":true,"valueElement":"editor_like_button_color","onfinechange":"arflite_update_color(this,\"editor_like_button_color\")"}'></div>
										<input type="hidden" name="albclr" id="editor_like_button_color" class="txtxbox_widget" value="#4786ff" data-arfstyle="true" data-arfstyledata='{"standard":".arflite_main_div_{arf_form_id} .arf_like_btn.active~|~background","material":".arflite_main_div_{arf_form_id} .arf_like_btn.active~|~background"}' data-arfstyleappend="true" data-arfstyleappendid="arf_{arf_form_id}_like_button_color" /><?php echo esc_html__( 'Like Button Color', 'arforms-form-builder' ); ?>
									</div>
									<div class="arf_custom_color_popup_right_item arfdisablediv arf_restricted_control">
										<div class="arf_custom_color_popup_picker jscolor arfdisablediv arf_restricted_control" data-fid="editor_dislike_button_color" style="background:#ec3838;" data-skin="" data-jscolor='{"hash":true,"valueElement":"editor_dislike_button_color","onfinechange":"arflite_update_color(this,\"editor_dislike_button_color\")"}'></div>
										<input type="hidden" name="adlbclr" id="editor_dislike_button_color" class="txtxbox_widget " value="#ec3838" data-arfstyle="true" data-arfstyledata='{"standard":".arflite_main_div_{arf_form_id} .arf_dislike_btn.active~|~background","material":".arflite_main_div_{arf_form_id} .arf_dislike_btn.active~|~background"}' data-arfstyleappend="true" data-arfstyleappendid="arf_{arf_form_id}_dislike_button_color" /><?php echo esc_html__( 'Dislike Button Color', 'arforms-form-builder' ); ?>
									</div>

									<div class="arf_popup_clear"></div>
									<span class="arf_custom_color_popup_subtitle"><?php echo esc_html__( 'Slider Color', 'arforms-form-builder' ); ?></span>

									<div class="arf_custom_color_popup_right_item arfdisablediv arf_restricted_control">
										<div class="arf_custom_color_popup_picker jscolor arfdisablediv arf_restricted_control" data-fid="editor_slider_left_side" style="background:<?php echo str_replace( '##', '#', esc_attr($sliderLeftColor) ); //phpcs:ignore ?>" data-skin="input.slider_selection_color" data-jscolor='{"hash":true,"valueElement":"editor_slider_left_side","onfinechange":"arflite_update_color(this,\"editor_slider_left_side\")"}'></div>
										<input type="hidden" name="asldrsl" data-arfstyle="true" data-arfstyledata='{"standard":".arflite_main_div_{arf_form_id} .slider.slider-horizontal .slider-selection~|~background","material":".arflite_main_div_{arf_form_id} .slider.slider-horizontal .slider-selection~|~background"}' data-arfstyleappend="true" data-arfstyleappendid="arf_{arf_form_id}_slider_selection_color" id="editor_slider_left_side" class="txtxbox_widget " value="<?php echo str_replace( '##', '#', esc_attr($sliderLeftColor) ); //phpcs:ignore ?>" /><?php echo esc_html__( 'Slider selected', 'arforms-form-builder' ); ?>
									</div>
									<div class="arf_custom_color_popup_right_item arfdisablediv arf_restricted_control">
										<div class="arf_custom_color_popup_picker jscolor arfdisablediv arf_restricted_control" data-fid="editor_slider_right_side" style="background:<?php echo str_replace( '##', '#', esc_attr($sliderRightColor) ); //phpcs:ignore ?>" data-skin="input.slider_track_color" data-jscolor='{"hash":true,"valueElement":"editor_slider_right_side","onfinechange":"arflite_update_color(this,\"editor_slider_right_side\")"}'></div>
										<input type="hidden" name="asltrcl" id="editor_slider_right_side" class="txtxbox_widget " value="<?php echo str_replace( '##', '#', esc_attr($sliderRightColor) ); //phpcs:ignore ?>" data-arfstyle="true" data-arfstyledata='{"standard":".arflite_main_div_{arf_form_id} .slider.slider-horizontal .arf-slider-track~|~background","material":".arflite_main_div_{arf_form_id} .slider.slider-horizontal .arf-slider-track~|~background"}' data-arfstyleappend="true" data-arfstyleappendid="arf_{arf_form_id}_slider_selection_color" /><?php echo esc_html__( 'Slider Track', 'arforms-form-builder' ); ?>
									</div>

									<div class='arf_popup_clear'></div>

									<span class="arf_custom_color_popup_subtitle"><?php echo esc_html__( 'Star Rating color', 'arforms-form-builder' ); ?></span>
									<div class="arf_custom_color_popup_right_item arfdisablediv arf_restricted_control">
										<div class="arf_custom_color_popup_picker jscolor arfdisablediv arf_restricted_control" data-fid="editor_rating_color" style="background:#FCBB1D;" data-skin="input.rating_color" data-jscolor='{"hash":true,"valueElement":"editor_rating_color","onfinechange":"arflite_update_color(this,\"editor_rating_color\")"}'></div>
										<input type="hidden" name="asclcl" id="editor_rating_color" class="txtxbox_widget " value="#FCBB1D" /><?php echo esc_html__( 'Star Rating Color', 'arforms-form-builder' ); ?>
									</div>


									<div class='arf_popup_clear'></div>

									<span class="arf_custom_color_popup_subtitle"><?php echo esc_html__( 'Field Tooltip', 'arforms-form-builder' ); ?></span>

									<div class="arf_custom_color_popup_right_item">
										<div class="arf_custom_color_popup_picker jscolor" data-fid="arf_tooltip_bg_color" style="background:<?php echo str_replace( '##', '#', esc_attr($newarr['arf_tooltip_bg_color']) ); //phpcs:ignore ?>;" data-skin="tooltip.background" data-default-color="<?php echo str_replace( '##', '#', esc_attr($newarr['arf_tooltip_bg_color']) ); //phpcs:ignore ?>;" data-jscolor='{"hash":true,"valueElement":"arf_tooltip_bg_color","onfinechange":"arflite_update_color(this,\"arf_tooltip_bg_color\")"}'></div>
										<input type="hidden" name="arf_tooltip_bg_color" id="arf_tooltip_bg_color" class="txtxbox_widget  hex" data-arfstyle="true" data-arfstyledata='{"standard":".arflite_main_div_{arf_form_id} .arf_fieldset .arf_tooltip_main~|~background-color","material":".arflite_main_div_{arf_form_id} .arf_fieldset .arf_tooltip_main~|~background-color"}'  data-arfstyleappend="true" data-arfstyleappendid="arf_{arf_form_id}_tooltip_bg_color" value="<?php echo str_replace( '##', '#', esc_attr($newarr['arf_tooltip_bg_color']) ); //phpcs:ignore ?>" onchange="arflitetooltipinitialization();"/>
										<?php echo esc_html__( 'Background', 'arforms-form-builder' ); ?>
									</div>
									<div class="arf_custom_color_popup_right_item">
										<div class="arf_custom_color_popup_picker jscolor" data-fid="arf_tooltip_font_color" style="background:<?php echo str_replace( '##', '#', esc_attr($newarr['arf_tooltip_font_color'] )); //phpcs:ignore ?>;" data-skin="tooltip.text" data-default-color="<?php echo str_replace( '##', '#', esc_attr($newarr['arf_tooltip_font_color']) ); //phpcs:ignore ?>;" data-jscolor='{"hash":true,"valueElement":"arf_tooltip_font_color","onfinechange":"arflite_update_color(this,\"arf_tooltip_font_color\")"}'></div>
										<input type="hidden" name="arf_tooltip_font_color" id="arf_tooltip_font_color" class="txtxbox_widget  hex" data-arfstyle="true" data-arfstyledata='{"standard":".arflite_main_div_{arf_form_id} .arf_fieldset .arf_tooltip_main~|~color","material":".arflite_main_div_{arf_form_id} .arf_fieldset .arf_tooltip_main~|~color"}' data-arfstyleappend="true" data-arfstyleappendid="arf_{arf_form_id}_tooltip_txt_color" value="<?php echo str_replace( '##', '#', esc_attr($newarr['arf_tooltip_font_color']) ); //phpcs:ignore ?>" onchange="arflitetooltipinitialization();"/>
										<?php echo esc_html__( 'Text Color', 'arforms-form-builder' ); ?>
									</div>

									<div class='arf_popup_clear'></div>

									
									<span class="arf_custom_color_popup_subtitle"><?php echo esc_html__( 'Matrix Field Background Color', 'arforms-form-builder' ); ?></span>
									  <div class="arf_custom_color_popup_right_item" id="arf_matrix_odd_bgcolor_wrapper">
										<?php
										if ( empty( $newarr['arf_matrix_odd_bgcolor'] ) ) {
											$newarr['arf_matrix_odd_bgcolor'] = '#f4f4f4';
										}
										?>
										<div class="arf_custom_color_popup_picker jscolor arfdisablediv arf_restricted_control" data-fid="arf_matrix_odd_bgcolor" style="background:<?php echo str_replace( '##', '#', esc_attr($newarr['arf_matrix_odd_bgcolor']) ); //phpcs:ignore ?>;" data-skin="matrix.odd_row" data-default-color="<?php echo str_replace( '##', '#', esc_attr($newarr['arf_matrix_odd_bgcolor']) ); //phpcs:ignore ?>;" data-jscolor='{"hash":true,"valueElement":"arf_matrix_odd_bgcolor","onFineChange":"arflite_update_color(this,\"arf_matrix_odd_bgcolor\")"}'></div>
										<input type="hidden" name="arf_matrix_odd_bgcolor" id="arf_matrix_odd_bgcolor" class="txtxbox_widget hex" data-arfstyle="true" data-arfstyledata='{"standard":".ar_main_div_{arf_form_id} .arf_matrix_field_control_wrapper table tbody tr:nth-child(odd) td~|~background-color","material":".ar_main_div_{arf_form_id} .arf_matrix_field_control_wrapper table tbody tr:nth-child(odd) td~|~background-color","material_outlined":".ar_main_div_{arf_form_id} .arf_matrix_field_control_wrapper table tbody tr:nth-child(odd) td~|~background-color"}'  data-arfstyleappend="true" data-arfstyleappendid="arf_{arf_form_id}_matrix_odd_bgcolor" value="<?php echo str_replace( '##', '#', esc_attr($newarr['arf_matrix_odd_bgcolor']) ); //phpcs:ignore ?>" style="width:100px;"/>
										<?php echo esc_html__( 'Odd Row', 'arforms-form-builder' ); ?>
									</div>
									<div class="arf_custom_color_popup_right_item" id="arf_matrix_even_bgcolor_wrapper">
										<?php
										if ( empty( $newarr['arf_matrix_even_bgcolor'] ) ) {
											$newarr['arf_matrix_even_bgcolor'] = '#ffffff';
										}
										?>
										<div class="arf_custom_color_popup_picker jscolor arfdisablediv arf_restricted_control" data-fid="arf_matrix_even_bgcolor" style="background:<?php echo str_replace( '##', '#', esc_attr($newarr['arf_matrix_even_bgcolor']) ); //phpcs:ignore ?>;" data-skin="matrix.even_row" data-default-color="<?php echo str_replace( '##', '#', esc_attr($newarr['arf_matrix_even_bgcolor']) ); //phpcs:ignore ?>;" data-jscolor='{"hash":true,"valueElement":"arf_matrix_even_bgcolor","onFineChange":"arflite_update_color(this,\"arf_matrix_even_bgcolor\")"}'></div>
										<input type="hidden" name="arf_matrix_even_bgcolor" id="arf_matrix_even_bgcolor" class="txtxbox_widget hex" data-arfstyle="true" data-arfstyledata='{"standard":".ar_main_div_{arf_form_id} .arf_matrix_field_control_wrapper table tbody tr:nth-child(even) td~|~background-color","material":".ar_main_div_{arf_form_id} .arf_matrix_field_control_wrapper table tbody tr:nth-child(even) td~|~background-color","material_outlined":".ar_main_div_{arf_form_id} .arf_matrix_field_control_wrapper table tbody tr:nth-child(even) td~|~background-color"}' data-arfstyleappend="true" data-arfstyleappendid="arf_{arf_form_id}_matrix_even_bgcolor" value="<?php echo str_replace( '##', '#', esc_attr($newarr['arf_matrix_even_bgcolor']) ); //phpcs:ignore ?>" style="width:100px;"/>
										<?php echo esc_html__( 'Even Row', 'arforms-form-builder' ); ?>
									</div>

									<div class="arf_custom_color_popup_right_item" style="width: auto;margin-top: 20px;<?php echo ( is_rtl() ) ? 'margin-right:20px;margin-left:0px;' : 'margin-left:20px;margin-right:0;'; ?>">
										<div class="arf_custom_checkbox_div">
											<div class="arf_custom_checkbox_wrapper arfdisablediv">
												<input type="checkbox" value="1" id="arf_matrix_inherit_bg" name="arf_matrix_inherit_bg" class="arf_restricted_control"/>
												<svg width="18px" height="18px">
													<?php echo ARFLITE_CUSTOM_UNCHECKED_ICON; //phpcs:ignore ?>
													<?php echo ARFLITE_CUSTOM_CHECKED_ICON; //phpcs:ignore ?>
												</svg>
											</div>
										</div> 
										<label for="arf_matrix_inherit_bg" style="<?php echo ( is_rtl() ) ? 'float: right;text-align: right;margin-right: -3px;position: relative;' : 'float: left;text-align: left;margin-left: -3px;'; ?>margin-top: 3px;"><?php echo esc_html__( 'Set Transparent Background', 'arforms-form-builder' ); ?></label>
									</div>
									<span class="arf_custom_color_popup_subtitle"><?php echo esc_html__( 'Other color', 'arforms-form-builder' ); ?></span>

										<div class="arf_custom_color_popup_right_item">
											<div class="arf_custom_color_popup_picker jscolor" data-fid="arfdatepickertextcolorsetting" style="background:<?php echo str_replace( '##', '#', esc_attr($datepickerTxtColor) ); //phpcs:ignore ?>;" data-skin="datepicker.text" data-default-color="<?php echo str_replace( '##', '#', esc_attr($datepickerTxtColor) ); //phpcs:ignore ?>" data-jscolor='{"hash":true,"valueElement":"arfdatepickertextcolorsetting","onfinechange":"arflite_update_color(this,\"arfdatepickertextcolorsetting\")"}'></div>
											<input type="hidden" name="arfdtcs" id="arfdatepickertextcolorsetting" class="txtxbox_widget  hex" data-arfstyle="true" data-arfstyledata='{"standard":".arflite_main_div_{arf_form_id} .bootstrap-datetimepicker-widget table span.month~|~color||.arflite_main_div_{arf_form_id} .bootstrap-datetimepicker-widget table span.year:not(.disabled)~|~color||.arflite_main_div_{arf_form_id} .bootstrap-datetimepicker-widget table span.decade:not(.disabled)~|~color||.arflite_main_div_{arf_form_id} .arf_cal_body span.year~|~color||.arflite_main_div_{arf_form_id} .arf_cal_body span.decade:not(.disabled)~|~color||.arflite_main_div_{arf_form_id} .arf_cal_body td span.month~|~color||.arflite_main_div_{arf_form_id} .datepicker .arf_cal_body .day:not(.old):not(.new)~|~color||.arflite_main_div_{arf_form_id} .timepicker .timepicker-hour~|~color||.arflite_main_div_{arf_form_id} .timepicker .timepicker-minute~|~color||.arflite_main_div_{arf_form_id} .timepicker .arf_cal_hour~|~color||.arflite_main_div_{arf_form_id} .timepicker .arf_cal_minute~|~color","material":".arflite_main_div_{arf_form_id} .arf_materialize_form .arf_cal_body td span.month~|~color||.arflite_main_div_{arf_form_id} .arf_materialize_form .arf_cal_body td span.month:hover~|~border-color||.arflite_main_div_{arf_form_id} .datepicker .arf_cal_body .day:not(.old):not(.new)~|~color||.arflite_main_div_{arf_form_id} .timepicker .arf_cal_hour~|~color||.arflite_main_div_{arf_form_id} .timepicker .arf_cal_minute~|~color||..arflite_main_div_{arf_form_id} .arf_materialize_form .arf_cal_body span.month~|~color||.arflite_main_div_{arf_form_id} .arf_materialize_form .arf_cal_body span.year~|~color||.arflite_main_div_{arf_form_id} .arf_materialize_form .arf_cal_body span.decade:not(.disabled)~|~color||.arflite_main_div_{arf_form_id} .arf_materialize_form .arf_cal_body span.decade:not(.disabled):hover~|~border-color||.arflite_main_div_{arf_form_id} .arf_materialize_form .arf_cal_body span.year:hover~|~border-color||.arflite_main_div_{arf_form_id} .arf_materialize_form .bootstrap-datetimepicker-widget table td.day:not(.old):not(.new):not(.active)~|~color"}' data-arfstyleappend="true" data-arfstyleappendid="arf_{arf_form_id}_datepicker_bgcolor" value="<?php echo str_replace( '##', '#', esc_attr($datepickerTxtColor) ); //phpcs:ignore ?>"  />
											<?php echo esc_html__( 'Datepicker Text Color', 'arforms-form-builder' ); ?>
										</div>

								</div>
							</div>

							<div class="arf_custom_color_popup_table_row">
								<div class="arf_custom_color_popup_left_item" id="submit_button_colors"><span><?php echo esc_html__( 'Submit Button Colors', 'arforms-form-builder' ); ?></span></div>
								<div class="arf_custom_color_popup_right_item_wrapper">
									<div class="arf_custom_color_popup_right_item">
										<div class="arf_custom_color_popup_picker jscolor" data-fid="arfsubmitbuttontextcolorsetting" style="background:<?php echo str_replace( '##', '#', esc_attr($submitTxtColor) ); //phpcs:ignore ?>;" data-skin="submit.text" data-default-color="<?php echo str_replace( '##', '#', esc_attr($submitTxtColor) ); //phpcs:ignore ?>" data-jscolor='{"hash":true,"valueElement":"arfsubmitbuttontextcolorsetting","onfinechange":"arflite_update_color(this,\"arfsubmitbuttontextcolorsetting\")"}'></div>
										<input type="hidden" name="arfsbtcs" id="arfsubmitbuttontextcolorsetting" class="txtxbox_widget  hex" data-arfstyle="true" data-arfstyledata='{"standard":".arflite_main_div_{arf_form_id} .arfsubmitbutton .arf_submit_btn~|~color||.arflite_main_div_{arf_form_id} .arfsubmitbutton .arf_greensave_button_wrapper .arf_submit_btn:hover.arf_submit_btn_border~|~color||.arflite_main_div_{arf_form_id} .arfsubmitbutton .arf_greensave_button_wrapper .arf_submit_btn.arf_submit_btn_reverse_border~|~color||.arflite_main_div_{arf_form_id} .arfsubmitbutton .arf_greensave_button_wrapper .arf_submit_btn.arf_submit_btn_flat~|~color","material":".arflite_main_div_{arf_form_id} .arfsubmitbutton .arf_submit_btn~|~color||.arflite_main_div_{arf_form_id} .arf_materialize_form .arfsubmitbutton .arf_greensave_button_wrapper .arf_submit_btn:hover.arf_submit_btn_border~|~color||.arflite_main_div_{arf_form_id} .arf_materialize_form .arfsubmitbutton .arf_greensave_button_wrapper .arf_submit_btn.arf_submit_btn_reverse_border~|~color||.arflite_main_div_{arf_form_id} .arf_materialize_form .arfsubmitbutton .arf_greensave_button_wrapper .arf_submit_btn.arf_submit_btn_flat~|~color"}' data-arfstyleappend="true" data-arfstyleappendid="arf_{arf_form_id}_form_submit_button_color" value="<?php echo str_replace( '##', '#', esc_attr($submitTxtColor) ); //phpcs:ignore ?>" />
										<?php echo esc_html__( 'Text Color', 'arforms-form-builder' ); ?>
									</div>
									<div class="arf_custom_color_popup_right_item">
										<div class="arf_custom_color_popup_picker jscolor" data-fid="arfsubmitbuttonbgcolorsetting" style="background:<?php echo str_replace( '##', '#', esc_attr($submitBgColor) ); //phpcs:ignore ?>;" data-skin="submit.background" data-default-color="<?php echo str_replace( '##', '#', esc_attr($submitBgColor) ); //phpcs:ignore ?>" data-jscolor='{"hash":true,"valueElement":"arfsubmitbuttonbgcolorsetting","onfinechange":"arflite_update_color(this,\"arfsubmitbuttonbgcolorsetting\")"}'></div>
										<input type="hidden" name="arfsbbcs" id="arfsubmitbuttonbgcolorsetting" class="txtxbox_widget  hex" data-arfstyle="true" data-arfstyledata='{"standard":".arflite_main_div_{arf_form_id} .arfsubmitbutton .arf_submit_btn~|~background-color||.arflite_main_div_{arf_form_id} .arfsubmitbutton .arf_greensave_button_wrapper .arf_submit_btn.arf_submit_btn_border~|~border-color||.arflite_main_div_{arf_form_id} .arfsubmitbutton .arf_greensave_button_wrapper .arf_submit_btn.arf_submit_btn_border~|~color||.arflite_main_div_{arf_form_id} .arfsubmitbutton .arf_greensave_button_wrapper .arf_submit_btn.arf_submit_btn_reverse_border~|~border-color","material":".arflite_main_div_{arf_form_id} .arfsubmitbutton .arf_submit_btn~|~background-color||.arflite_main_div_{arf_form_id} .arf_materialize_form .arfsubmitbutton .arf_greensave_button_wrapper .arf_submit_btn.arf_submit_btn_border~|~border-color||.arflite_main_div_{arf_form_id} .arf_materialize_form .arfsubmitbutton .arf_greensave_button_wrapper .arf_submit_btn.arf_submit_btn_border~|~color||.arflite_main_div_{arf_form_id} .arf_materialize_form .arfsubmitbutton .arf_greensave_button_wrapper .arf_submit_btn.arf_submit_btn_reverse_border~|~border-color"}' data-arfstyleappend="true" data-arfstyleappendid="arflite_main_div_{arf_form_id}_submit_button_background_color" value="<?php echo str_replace( '##', '#', esc_attr($submitBgColor) ); //phpcs:ignore ?>" />
										<?php echo esc_html__( 'Background', 'arforms-form-builder' ); ?>
									</div>
									<div class="arf_custom_color_popup_right_item">
										<div class="arf_custom_color_popup_picker jscolor" data-fid="arfsubmitbuttoncolorhoversetting" style="background:<?php echo str_replace( '##', '#', esc_attr($submitHoverBg) ); //phpcs:ignore ?>;" data-skin="submit.background_hover" data-default-color="<?php echo str_replace( '##', '#', esc_attr($submitHoverBg) );  ?>" data-jscolor='{"hash":true,"valueElement":"arfsubmitbuttoncolorhoversetting","onfinechange":"arflite_update_color(this,\"arfsubmitbuttoncolorhoversetting\")"}'></div>
										<input type="hidden" name="arfsbchs" id="arfsubmitbuttoncolorhoversetting" class="txtxbox_widget  hex" data-arfstyle="true" data-arfstyledata='{"standard":".arflite_main_div_{arf_form_id} .arfsubmitbutton .arf_submit_btn:hover~|~background-color||.arflite_main_div_{arf_form_id} .arfsubmitbutton .arf_greensave_button_wrapper .arf_submit_btn:hover.arf_submit_btn_border~|~border-color||.arflite_main_div_{arf_form_id} .arfsubmitbutton .arf_greensave_button_wrapper .arf_submit_btn:hover.arf_submit_btn_border~|~background-color||.arflite_main_div_{arf_form_id} .arfsubmitbutton .arf_greensave_button_wrapper .arf_submit_btn:hover.arf_submit_btn_reverse_border~|~border-color||.arflite_main_div_{arf_form_id} .arfsubmitbutton .arf_greensave_button_wrapper .arf_submit_btn:hover.arf_submit_btn_reverse_border~|~color||.arflite_main_div_{arf_form_id} .arfsubmitbutton .arf_greensave_button_wrapper .arf_submit_btn:hover.arf_submit_btn_flat~|~background-color","material":".arflite_main_div_{arf_form_id} .arfsubmitbutton .arf_submit_btn:hover~|~background-color||.arflite_main_div_{arf_form_id} .arf_materialize_form .arfsubmitbutton .arf_greensave_button_wrapper .arf_submit_btn:hover.arf_submit_btn_border~|~border-color||.arflite_main_div_{arf_form_id} .arf_materialize_form .arfsubmitbutton .arf_greensave_button_wrapper .arf_submit_btn:hover.arf_submit_btn_border~|~background-color||.arflite_main_div_{arf_form_id} .arf_materialize_form .arfsubmitbutton .arf_greensave_button_wrapper .arf_submit_btn:hover.arf_submit_btn_reverse_border~|~border-color||.arflite_main_div_{arf_form_id} .arf_materialize_form .arfsubmitbutton .arf_greensave_button_wrapper .arf_submit_btn:hover.arf_submit_btn_reverse_border~|~color||.arflite_main_div_{arf_form_id} .arf_materialize_form .arfsubmitbutton .arf_greensave_button_wrapper .arf_submit_btn:hover.arf_submit_btn_flat~|~background-color"}' data-arfstyleappend="true" data-arfstyleappendid="arf_{arf_form_id}_submit_btn_hover" value="<?php echo str_replace( '##', '#', esc_attr($submitHoverBg) ); //phpcs:ignore ?>" />
										<?php echo esc_html__( 'Hover Background', 'arforms-form-builder' ); ?>
									</div>
									<div class="arf_popup_clear"></div>
									<div class="arf_custom_color_popup_right_item">
										<div class="arf_custom_color_popup_picker jscolor" data-fid="arfsubmitbuttonbordercolorsetting" style="background:<?php echo str_replace( '##', '#', esc_attr($submitBrdColor) ); //phpcs:ignore ?>;" data-skin="submit.border" data-default-color="<?php echo str_replace( '##', '#', esc_attr($submitBrdColor) ); //phpcs:ignore ?>" data-jscolor='{"hash":true,"valueElement":"arfsubmitbuttonbordercolorsetting","onfinechange":"arflite_update_color(this,\"arfsubmitbuttonbordercolorsetting\")"}'></div>
										<input type="hidden" name="arfsbobcs" id="arfsubmitbuttonbordercolorsetting" class="txtxbox_widget hex " data-arfstyle="true" data-arfstyledata='{"standard":".arflite_main_div_{arf_form_id} .arfsubmitbutton .arf_submit_btn:not(.arf_submit_btn_border):not(.arf_submit_btn_reverse_border)~|~border-color","material":".arflite_main_div_{arf_form_id} .arfsubmitbutton .arf_submit_btn:not(.arf_submit_btn_border):not(.arf_submit_btn_reverse_border)~|~border-color"}' data-arfstyleappend="true" data-arfstyleappendid="arf_{arf_form_id}_submit_btn_border_color" value="<?php echo str_replace( '##', '#', esc_attr($submitBrdColor) ); //phpcs:ignore ?>" />
										<?php echo esc_html__( 'Border Color', 'arforms-form-builder' ); ?>
									</div>
									<div class="arf_custom_color_popup_right_item">
										<div class="arf_custom_color_popup_picker jscolor" data-fid="arfsubmitbuttonshadowcolorsetting" style="background:<?php echo str_replace( '##', '#', esc_attr($submitShadowColor) ); //phpcs:ignore ?>;" data-skin="submit.shadow" data-default-color="<?php echo str_replace( '##', '#', esc_attr($submitShadowColor) ); //phpcs:ignore ?>" data-jscolor='{"hash":true,"valueElement":"arfsubmitbuttonshadowcolorsetting","onfinechange":"arflite_update_color(this,\"arfsubmitbuttonshadowcolorsetting\")"}'></div>
										<input type="hidden" name="arfsbscs" id="arfsubmitbuttonshadowcolorsetting" class="txtxbox_widget hex " data-arfstyle="true" data-arfstyledata='{"standard":".arflite_main_div_{arf_form_id} .arfsubmitbutton .arf_submit_btn:not(.arf_submit_btn_border):not(.arf_submit_btn_reverse_border)~|~box-shadow","material":".arflite_main_div_{arf_form_id} .arfsubmitbutton .arf_submit_btn:not(.arf_submit_btn_border):not(.arf_submit_btn_reverse_border)~|~box-shadow"}' data-arfstyleappend="true" data-arfstyleappendid="arf_{arf_form_id}_submit_btn_box_shadow" value="<?php echo str_replace( '##', '#', esc_attr($submitShadowColor) ); //phpcs:ignore ?>" />
										<?php echo esc_html__( 'Shadow Color', 'arforms-form-builder' ); ?>
									</div>
									<div class="arf_custom_color_popup_right_item">&nbsp;</div>
									<div class="arf_popup_clear"></div>
								</div>
							</div>

							<div class="arf_custom_color_popup_table_row">
								<div class="arf_custom_color_popup_left_item" id="success_message_colors"><span><?php echo esc_html__( 'Success message Colors', 'arforms-form-builder' ); ?></span></div>
								<div class="arf_custom_color_popup_right_item_wrapper">
									<input type="hidden" name="arfmebs" id="arfmainerrorbgsetting" class="txtxbox_widget  hex" value="<?php echo esc_attr( $newarr['arferrorbgsetting'] ); ?>" />
									<input type="hidden" name="arfmebos" id="arfmainerrotbordersetting" class="txtxbox_widget  hex" value="<?php echo esc_attr( $newarr['arferrorbordersetting'] ); ?>" />
									<input type="hidden" name="arfmets" id="arfmainerrortextsetting" class="txtxbox_widget  hex" value="<?php echo esc_attr( $newarr['arferrortextsetting'] ); ?>" />
									<div class="arf_custom_color_popup_right_item">
										<div class="arf_custom_color_popup_picker jscolor" data-fid="arfmainsucessbgcolorsetting" style="background:<?php echo str_replace( '##', '#', esc_attr($successBgColor) ); //phpcs:ignore ?>;" data-skin="success_msg.background" data-default-color="<?php echo str_replace( '##', '#', esc_attr($successBgColor) ); //phpcs:ignore ?>" data-checkskin="true" data-jscolor='{"hash":true,"valueElement":"arfmainsucessbgcolorsetting","onfinechange":"arflite_update_color(this,\"arfmainsucessbgcolorsetting\")"}'></div>
										<input name="arfmsbcs" id="arfmainsucessbgcolorsetting" class="txtxbox_widget  hex" value="<?php echo str_replace( '##', '#', esc_attr($successBgColor) ); //phpcs:ignore ?>" type="hidden" />
										<?php echo esc_html__( 'Background', 'arforms-form-builder' ); ?>
									</div>
									<div class="arf_custom_color_popup_right_item">
										<div class="arf_custom_color_popup_picker jscolor" data-fid="arfmainsucessbordercolorsetting" style="background:<?php echo str_replace( '##', '#', esc_attr($successBrdColor) ); //phpcs:ignore ?>;" data-skin="success_msg.border" data-default-color="<?php echo str_replace( '##', '#', esc_attr($successBrdColor) ); //phpcs:ignore ?>" data-checkskin="true" data-jscolor='{"hash":true,"valueElement":"arfmainsucessbordercolorsetting","onfinechange":"arflite_update_color(this,\"arfmainsucessbordercolorsetting\")"}'></div>
										<input type="hidden" name="arfmsbocs" id="arfmainsucessbordercolorsetting" class="txtxbox_widget  hex" value="<?php echo str_replace( '##', '#', esc_attr($successBrdColor) ); //phpcs:ignore ?>" />
										<?php echo esc_html__( 'Border', 'arforms-form-builder' ); ?>
									</div>
									<div class="arf_custom_color_popup_right_item">
										<div class="arf_custom_color_popup_picker jscolor" data-fid="arfmainsucesstextcolorsetting" style="background:<?php echo str_replace( '##', '#', esc_attr($successTxtColor) ); //phpcs:ignore ?>;" data-skin="success_msg.text" data-default-color="<?php echo str_replace( '##', '#', esc_attr($successTxtColor) ); //phpcs:ignore ?>" data-checkskin="true" data-jscolor='{"hash":true,"valueElement":"arfmainsucesstextcolorsetting","onfinechange":"arflite_update_color(this,\"arfmainsucesstextcolorsetting\")"}'></div>
										<input name="arfmstcs" id="arfmainsucesstextcolorsetting" class="txtxbox_widget  hex" value="<?php echo str_replace( '##', '#', esc_attr($successTxtColor) ); //phpcs:ignore ?>" type="hidden" />
										<?php echo esc_html__( 'Text', 'arforms-form-builder' ); ?>
									</div>
									<div class="arf_popup_clear"></div>
								</div>
							</div>
							<div class="arf_custom_color_popup_table_row">
								<div class="arf_custom_color_popup_left_item" id="error_message_colors"><span><?php echo esc_html__( 'Error Message Colors', 'arforms-form-builder' ); ?></span></div>
								<div class="arf_custom_color_popup_right_item_wrapper">
									<div class="arf_custom_color_popup_right_item">
										<div class="arf_custom_color_popup_picker jscolor" data-fid="arfformerrorbgcolorsetting" style="background:<?php echo str_replace( '##', '#', esc_attr($errorBgColor) ); //phpcs:ignore ?>" data-skin="error_msg.background" data-default-color="<?php echo str_replace( '##', '#', esc_attr($errorBgColor) ); //phpcs:ignore ?>" data-checkskin="true" data-jscolor='{"hash":true,"valueElement":"arfformerrorbgcolorsetting","onfinechange":"arflite_update_color(this,\"arfformerrorbgcolorsetting\")"}'></div>
										<input name="arffebgc" id="arfformerrorbgcolorsetting" class="txtxbox_widget  hex" value="<?php echo str_replace( '##', '#', esc_attr($errorBgColor) ); //phpcs:ignore ?>" type="hidden" />
										<?php echo esc_html__( 'Background', 'arforms-form-builder' ); ?>
									</div>

									<div class="arf_custom_color_popup_right_item">
										<div class="arf_custom_color_popup_picker jscolor" data-fid="arfformerrorbordercolorsetting" style="background:<?php echo str_replace( '##', '#', esc_attr($errorBrdColor) ); //phpcs:ignore ?>" data-skin="error_msg.border" data-default-color="<?php echo str_replace( '##', '#', esc_attr($errorBrdColor) ); //phpcs:ignore ?>" data-checkskin="true" data-jscolor='{"hash":true,"valueElement":"arfformerrorbordercolorsetting","onfinechange":"arflite_update_color(this,\"arfformerrorbordercolorsetting\")"}'></div>
										<input name="arffebrdc" id="arfformerrorbordercolorsetting" class="txtxbox_widget  hex" value="<?php echo str_replace( '##', '#', esc_attr($errorBrdColor) ); //phpcs:ignore ?>" type="hidden" />
										<?php echo esc_html__( 'Border', 'arforms-form-builder' ); ?>
									</div>

									<div class="arf_custom_color_popup_right_item">
										<div class="arf_custom_color_popup_picker jscolor" data-fid="arfformerrortextcolorsetting" style="background:<?php echo str_replace( '##', '#', esc_attr($errorTxtColor) ); //phpcs:ignore ?>" data-skin="error_msg.text" data-default-color="<?php echo str_replace( '##', '#', esc_attr($errorTxtColor) ); //phpcs:ignore ?>" data-checkskin="true" data-jscolor='{"hash":true,"valueElement":"arfformerrortextcolorsetting","onfinechange":"arflite_update_color(this,\"arfformerrortextcolorsetting\")"}'></div>
										<input name="arffetxtc" id="arfformerrortextcolorsetting" class="txtxbox_widget  hex" value="<?php echo str_replace( '##', '#', esc_attr($errorTxtColor) ); //phpcs:ignore ?>" type="hidden" />
										<?php echo esc_html__( 'Text', 'arforms-form-builder' ); ?>
									</div>
									<div class="arf_popup_clear"></div>
								</div>
							</div>
							<div class="arf_custom_color_popup_table_row">
								<div class="arf_custom_color_popup_left_item" id="validation_message_colors"><span><?php echo esc_html__( 'Validation Message Colors', 'arforms-form-builder' ); ?></span></div>
								<div class="arf_custom_color_popup_right_item_wrapper">
									<div class="arf_custom_color_popup_right_item" id="arf_validation_background_color">
										<div class="arf_custom_color_popup_picker jscolor" data-fid="arfmainvalidationbgcolorsetting" style="background:<?php echo str_replace( '##', '#', esc_attr($validationBgColor) ); //phpcs:ignore ?>;" data-skin="validation_msg.background" data-default-color="<?php echo str_replace( '##', '#', esc_attr($validationBgColor) ); //phpcs:ignore ?>" data-jscolor='{"hash":true,"valueElement":"arfmainvalidationbgcolorsetting","onfinechange":"arflite_update_color(this,\"arfmainvalidationbgcolorsetting\")"}'></div>
										<input name="arfmvbcs" id="arfmainvalidationbgcolorsetting" class="txtxbox_widget  hex" value="<?php echo str_replace( '##', '#', esc_attr($validationBgColor) ); //phpcs:ignore ?>" type="hidden"  />
										<span><?php echo ( $newarr['arferrorstyle'] == 'normal' ) ? esc_html__( 'Color', 'arforms-form-builder' ) : esc_html__( 'Background', 'arforms-form-builder' ); //phpcs:ignore ?></span>
									</div>
									<div class="arf_custom_color_popup_right_item" id="arf_validation_text_color" style="<?php echo ( $newarr['arferrorstyle'] == 'normal' ) ? 'display:none;' : 'display:block;'; ?>">
										<div class="arf_custom_color_popup_picker jscolor" data-fid="arfmainvalidationtextcolorsetting" style="background:<?php echo str_replace( '##', '#', esc_attr($validationTxtColor) ); //phpcs:ignore ?>;" data-skin="validation_msg.text" data-default-color="<?php echo str_replace( '##', '#', esc_attr($validationTxtColor) ); //phpcs:ignore ?>" data-jscolor='{"hash":true,"valueElement":"arfmainvalidationtextcolorsetting","onfinechange":"arflite_update_color(this,\"arfmainvalidationtextcolorsetting\")"}'></div>
										<input name="arfmvtcs" id="arfmainvalidationtextcolorsetting" class="txtxbox_widget  hex" value="<?php echo str_replace( '##', '#', esc_attr($validationTxtColor) ); //phpcs:ignore ?>" type="hidden"  />
										<?php echo esc_html__( 'Text', 'arforms-form-builder' ); ?>
									</div>
									<div class="arf_popup_clear"></div>
								</div>
							</div>
						</div>
					</div>
					<div class="arf_custom_color_popup_footer">
						<div class="arf_custom_color_button_position">
							<div class="arf_custom_color_button" id="arf_custom_color_save_btn"><div class="arf_imageloader arf_form_style_custom_color_loader" id="arf_custom_color_loader"></div><?php echo esc_html__( 'Apply', 'arforms-form-builder' ); ?></div>
							<div class="arf_custom_color_button arf_custom_color_cancel" id="arf_custom_color_cancel_btn"><?php echo esc_html__( 'Cancel', 'arforms-form-builder' ); ?></div>
						</div>
					</div>
				</div>
				
				<div class="arf_custom_font_popup">
					<div class="arf_custom_color_popup_header"><?php echo esc_html__( 'Custom Font Options', 'arforms-form-builder' ); ?></div>
					<div class="arf_custom_font_popup_container">
						<div class="arf_accordion_container_row arf_margin">
							<div class="arf_accordion_outer_title"><?php echo esc_html__( 'Form Title Font Settings', 'arforms-form-builder' ); ?></div>
						</div>
						<?php
						$newarr['check_weight_form_title'] = isset( $newarr['check_weight_form_title'] ) ? $newarr['check_weight_form_title'] : 'normal';
						$label_font_weight                 = '';
						if ( $newarr['check_weight_form_title'] != 'normal' ) {
							$label_font_weight = ', ' . $newarr['check_weight_form_title'];
						}
						?>
						<div class="arf_font_setting_class">
							<div class="arf_font_style_popup_row">
								<div class="arf_font_style_popup_left"><?php echo esc_html__( 'Family', 'arforms-form-builder' ); ?></div>
								<div class="arf_font_style_popup_right">
									<div class="arf_dropdown_wrapper">
										<?php
											$newarr['arftitlefontfamily'] = ( isset( $newarr['arftitlefontfamily'] ) && $newarr['arftitlefontfamily'] != '' ) ? $newarr['arftitlefontfamily'] : 'Helvetica';

											$fontsarr = array(
												'' => array(
													'inherit' => addslashes( esc_html__( 'Inherit from theme', 'arforms-form-builder' ) ),
												),
												'default||' . addslashes( esc_html__( 'Default Fonts', 'arforms-form-builder' ) ) => $arfliteformcontroller->get_arflite_default_fonts(),
												'google||' . addslashes( esc_html__( 'Google Fonts', 'arforms-form-builder' ) ) => arflite_google_font_listing(),
											);

											$fontsattr = array(
												'data-arfstyle' => 'true',
												'data-arfstyledata' => '{"standard":".arflite_main_div_{arf_form_id} .arf_fieldset .formtitle_style~|~font-family||.arflite_main_div_{arf_form_id} .arf_fieldset .arfeditorformdescription~|~font-family","material":".arflite_main_div_{arf_form_id} .arf_fieldset .formtitle_style~|~font-family||.arflite_main_div_{arf_form_id} .arf_fieldset .arfeditorformdescription~|~font-family"}',
												'data-arfstyleappend' => 'true',
												'data-arfstyleappendid' => 'arf_{arf_form_id}_form_title_family',
												'data-default-font' => $newarr['arftitlefontfamily'],
											);

											echo $arflitemaincontroller->arflite_selectpicker_dom( 'arftff', 'arftitlefontsetting', '', '', $newarr['arftitlefontfamily'], $fontsattr, $fontsarr, true, array(), false, array(), false, array(), true ); //phpcs:ignore

											?>
									</div>
								</div>
							</div>
							<div class="arf_font_style_popup_row">
								<div class="arf_font_style_popup_left"><?php echo esc_html__( 'Size', 'arforms-form-builder' ); ?></div>
								<div class="arf_font_style_popup_right arfwidth63">
									<div class="arf_dropdown_wrapper arfmarginleft">
										<?php
											$font_size_opts = array();
										for ( $i = 8; $i <= 20; $i++ ) {
											$font_size_opts[ $i ] = $i;
										}
										for ( $i = 22; $i <= 28; $i = $i + 2 ) {
											$font_size_opts[ $i ] = $i;
										}
										for ( $i = 32; $i <= 40; $i = $i + 4 ) {
											$font_size_opts[ $i ] = $i;
										}

											$font_size_attr = array(
												'data-arfstyle' => 'true',
												'data-arfstyledata' => '{"standard":".arflite_main_div_{arf_form_id} .arf_fieldset .formtitle_style~|~font-size","material":".arflite_main_div_{arf_form_id} .arf_fieldset .formtitle_style~|~font-size"}',
												'data-arfstyleappend' => 'true',
												'data-arfstyleappendid' => 'arf_{arf_form_id}_form_title_size',
												'class' => 'arf_custom_font_options',
											);

											echo $arflitemaincontroller->arflite_selectpicker_dom( 'arfftfss', 'arfformtitlefontsizesetting', 'arflite_font_size_dropdown', '', $newarr['form_title_font_size'], $font_size_attr, $font_size_opts ); //phpcs:ignore
											?>
									</div>
									<div class="arfwidthpx" style="<?php echo ( is_rtl() ) ? 'margin-right: 25px;margin-left: 0px;position:relative;' : 'margin-left: 25px;'; ?>">px</div>
								</div>
							</div>
							<div class="arf_font_style_popup_row">
								<div class="arf_font_style_popup_left"><?php echo esc_html__( 'Style', 'arforms-form-builder' ); ?></div>
								<div class="arf_font_style_popup_right">
									<input id="arfformtitleweightsetting" name="arfftws" value="<?php echo esc_html( $newarr['check_weight_form_title'] ); ?>" data-arfstyle="true" data-arfstyledata='{"standard":".arflite_main_div_{arf_form_id} .arf_fieldset .formtitle_style~|~font-style","material":".arflite_main_div_{arf_form_id} .arf_fieldset .formtitle_style~|~font-style"}' data-arfstyleappend="true" data-arfstyleappendid="arf_{arf_form_id}_form_title_style" type="hidden" class="arf_custom_font_options arf_custom_font_style" data-default-font="<?php echo esc_attr( $newarr['check_weight_form_title'] ); ?>" />
									<?php $arf_form_title_font_style_arr = explode( ',', $newarr['check_weight_form_title'] ); ?>
									<span class="arf_font_style_button <?php echo ( in_array( 'strikethrough', $arf_form_title_font_style_arr ) ) ? 'active' : ''; ?>" data-style="strikethrough" data-id="arfformtitleweightsetting"><i class="fas fa-strikethrough"></i></span>
									<span class="arf_font_style_button <?php echo ( in_array( 'underline', $arf_form_title_font_style_arr ) ) ? 'active' : ''; ?>" data-style="underline" data-id="arfformtitleweightsetting"><i class="fas fa-underline"></i></span>
									<span class="arf_font_style_button <?php echo ( in_array( 'italic', $arf_form_title_font_style_arr ) ) ? 'active' : ''; ?>" data-style="italic" data-id="arfformtitleweightsetting"><i class="fas fa-italic"></i></span>
									<span class="arf_font_style_button <?php echo ( in_array( 'bold', $arf_form_title_font_style_arr ) ) ? 'active' : ''; ?>" data-style="bold" data-id="arfformtitleweightsetting"><i class="fas fa-bold"></i></span>
								</div>
							</div>
						</div>
						<div class="arf_accordion_container_row_separator"></div>
						<div class="arf_accordion_container_row arf_margin">
							<div class="arf_accordion_outer_title"><?php echo esc_html__( 'Label Font Settings', 'arforms-form-builder' ); ?></div>
						</div>
						<?php
						$label_font_weight = '';
						if ( $newarr['weight'] != 'normal' ) {
							$label_font_weight = ', ' . $newarr['weight'];
						}
						?>
						<div class="arf_font_setting_class">
							<div class="arf_font_style_popup_row">
								<div class="arf_font_style_popup_left"><?php echo esc_html__( 'Family', 'arforms-form-builder' ); ?></div>
								<div class="arf_font_style_popup_right">
									<div class="arf_dropdown_wrapper">
										<?php
											$newarr['font'] = ( isset( $newarr['font'] ) && $newarr['font'] != '' ) ? $newarr['font'] : 'Helvetica';


											$fontsarr = array(
												'' => array(
													'inherit' => addslashes( esc_html__( 'Inherit from theme', 'arforms-form-builder' ) ),
												),
												'default||' . addslashes( esc_html__( 'Default Fonts', 'arforms-form-builder' ) ) => $arfliteformcontroller->get_arflite_default_fonts(),
												'google||' . addslashes( esc_html__( 'Google Fonts', 'arforms-form-builder' ) ) => arflite_google_font_listing(),
											);

											$fontsattr = array(
												'data-arfstyle' => 'true',
												'data-arfstyledata' => '{"standard":".arflite_main_div_{arf_form_id} .arf_fieldset label.arf_main_label~|~font-family||.arflite_main_div_{arf_form_id} .arf_fieldset .arf_checkbox_input_wrapper + label~|~font-family||.arflite_main_div_{arf_form_id} .arf_fieldset .arf_radio_input_wrapper + label~|~font-family","material":".arflite_main_div_{arf_form_id} .arf_fieldset label.arf_main_label~|~font-family||.arflite_main_div_{arf_form_id} .arf_fieldset .arf_checkbox_input_wrapper + label~|~font-family||.arflite_main_div_{arf_form_id} .arf_fieldset .arf_radio_input_wrapper + label~|~font-family"}',
												'data-arfstyleappend' => 'true',
												'data-arfstyleappendid' => 'arf_{arf_form_id}_label_font_family',
												'data-default-font' => $newarr['font'],
											);

											echo $arflitemaincontroller->arflite_selectpicker_dom( 'arfmfs', 'arfmainfontsetting', '', '', $newarr['font'], $fontsattr, $fontsarr, true, array(), false, array(), false, array(), true ); //phpcs:ignore
											?>
									</div>
								</div>
							</div>
							<div class="arf_font_style_popup_row">
								<div class="arf_font_style_popup_left"><?php echo esc_html__( 'Size', 'arforms-form-builder' ); ?></div>
								<div class="arf_font_style_popup_right arfwidth63">
									<div class="arf_dropdown_wrapper arfmarginleft">
										<?php
											$font_size_opts = array();
										for ( $i = 8; $i <= 20; $i++ ) {
											$font_size_opts[ $i ] = $i;
										}
										for ( $i = 22; $i <= 28; $i = $i + 2 ) {
											$font_size_opts[ $i ] = $i;
										}
										for ( $i = 32; $i <= 40; $i = $i + 4 ) {
											$font_size_opts[ $i ] = $i;
										}

											$font_size_attr = array(
												'data-arfstyle' => 'true',
												'data-arfstyledata' => '{"standard":".arflite_main_div_{arf_form_id} .arf_fieldset label.arf_main_label~|~font-size||.arflite_main_div_{arf_form_id} .arf_fieldset .arf_checkbox_input_wrapper + label~|~font-size||.arflite_main_div_{arf_form_id} .arf_fieldset .arf_radio_input_wrapper + label~|~font-size||.arflite_main_div_{arf_form_id} .arf_fieldset .arf_radiobutton:not(.arf_enable_radio_image_editor):not(.arf_enable_radio_image)~|~padding-left||.arflite_main_div_{arf_form_id} .arf_fieldset .arf_checkbox_style:not(.arf_enable_checkbox_image_editor):not(.arf_enable_checkbox_image)~|~padding-left||.arflite_main_div_{arf_form_id} .arf_fieldset .arf_checkbox_style:not(.arf_enable_checkbox_image_editor):not(.arf_enable_checkbox_image) .arf_checkbox_input_wrapper~|~margin-left||.arflite_main_div_{arf_form_id} .arf_fieldset .arf_radiobutton:not(.arf_enable_radio_image_editor):not(.arf_enable_radio_image) .arf_radio_input_wrapper~|~margin-left","material":".arflite_main_div_{arf_form_id} .arf_fieldset label.arf_main_label~|~font-size||.arflite_main_div_{arf_form_id} .arf_fieldset .arf_checkbox_input_wrapper + label~|~font-size||.arflite_main_div_{arf_form_id} .arf_fieldset .arf_radio_input_wrapper + label~|~font-size||.arflite_main_div_{arf_form_id} .arf_fieldset .arf_checkbox_style:not(.arf_enable_checkbox_image_editor):not(.arf_enable_checkbox_image)~|~padding-left||.arflite_main_div_{arf_form_id} .arf_fieldset .arf_radiobutton:not(.arf_enable_radio_image_editor):not(.arf_enable_radio_image)~|~padding-left||.arflite_main_div_{arf_form_id} .arf_materialize_form .arf_fieldset .arf_checkbox_style:not(.arf_enable_checkbox_image_editor):not(.arf_enable_checkbox_image) .arf_checkbox_input_wrapper~|~margin-left||.arflite_main_div_{arf_form_id} .arf_materialize_form .arf_fieldset .arf_radiobutton:not(.arf_enable_radio_image_editor):not(.arf_enable_radio_image) .arf_radio_input_wrapper~|~margin-left"}',
												'data-arfstyleappend' => 'true',
												'data-arfstyleappendid' => 'arf_{arf_form_id}_label_font_size',
												'class' => 'arf_custom_font_options',
											);

											echo $arflitemaincontroller->arflite_selectpicker_dom( 'arffss', 'arffontsizesetting', 'arflite_font_size_dropdown', '', $newarr['font_size'], $font_size_attr, $font_size_opts ); //phpcs:ignore
											?>
									</div>
									<div class="arfwidthpx arf" style="<?php echo ( is_rtl() ) ? 'margin-right: 25px;margin-left: 0px;position:relative;' : 'margin-left: 25px;'; ?>">px</div>
								</div>
							</div>
							<div class="arf_font_style_popup_row">
								<div class="arf_font_style_popup_left"><?php echo esc_html__( 'Style', 'arforms-form-builder' ); ?></div>
								<div class="arf_font_style_popup_right">
									<input id="arfmainfontweightsetting" name="arfmfws" value="<?php echo esc_attr( $newarr['weight'] ); ?>" type="hidden" data-arfstyle="true" data-arfstyledata='{"standard":".arflite_main_div_{arf_form_id} .arf_fieldset label.arf_main_label~|~font-style||.arflite_main_div_{arf_form_id} .arf_fieldset .arf_checkbox_input_wrapper + label~|~font-style||.arflite_main_div_{arf_form_id} .arf_fieldset .arf_radio_input_wrapper + label~|~font-style","material":".arflite_main_div_{arf_form_id} .arf_fieldset label.arf_main_label~|~font-style||.arflite_main_div_{arf_form_id} .arf_fieldset .arf_checkbox_input_wrapper + label~|~font-style||.arflite_main_div_{arf_form_id} .arf_fieldset .arf_radio_input_wrapper + label~|~font-style"}' data-arfstyleappend="true" data-arfstyleappendid="arf_{arf_form_id}_label_font_style" class="arf_custom_font_options arf_custom_font_style" data-default-font="<?php echo esc_attr( $newarr['weight'] ); ?>">
									<?php $arf_label_font_style_arr = explode( ',', $newarr['weight'] ); ?>
									<span class="arf_font_style_button <?php echo ( in_array( 'strikethrough', $arf_label_font_style_arr ) ) ? 'active' : ''; ?>" data-style="strikethrough" data-id="arfmainfontweightsetting"><i class="fas fa-strikethrough"></i></span>
									<span class="arf_font_style_button <?php echo ( in_array( 'underline', $arf_label_font_style_arr ) ) ? 'active' : ''; ?>" data-style="underline" data-id="arfmainfontweightsetting"><i class="fas fa-underline"></i></span>
									<span class="arf_font_style_button <?php echo ( in_array( 'italic', $arf_label_font_style_arr ) ) ? 'active' : ''; ?>" data-style="italic" data-id="arfmainfontweightsetting"><i class="fas fa-italic"></i></span>
									<span class="arf_font_style_button <?php echo ( in_array( 'bold', $arf_label_font_style_arr ) ) ? 'active' : ''; ?>" data-style="bold" data-id="arfmainfontweightsetting"><i class="fas fa-bold"></i></span>
								</div>
							</div>
						</div>
						<div class="arf_accordion_container_row_separator"></div>
						<div class="arf_accordion_container_row arf_margin" id="arf_input_font_settings_container">
							<div class="arf_accordion_outer_title"><?php echo esc_html__( 'Input Font Settings', 'arforms-form-builder' ); ?></div>
						</div>
						<?php
						$input_font_weight_html = '';
						if ( $newarr['check_weight'] != 'normal' ) {
							$input_font_weight_html = ', ' . $newarr['check_weight'];
						}
						?>
						<div class="arf_font_setting_class">
							<div class="arf_font_style_popup_row">
								<div class="arf_font_style_popup_left"><?php echo esc_html__( 'Family', 'arforms-form-builder' ); ?></div>
								<div class="arf_font_style_popup_right">
									<div class="arf_dropdown_wrapper">
										<?php
											$newarr['check_font'] = ( isset( $newarr['check_font'] ) && $newarr['check_font'] != '' ) ? $newarr['check_font'] : 'Helvetica';

											$fontsarr = array(
												'' => array(
													'inherit' => addslashes( esc_html__( 'Inherit from theme', 'arforms-form-builder' ) ),
												),
												'default||' . addslashes( esc_html__( 'Default Fonts', 'arforms-form-builder' ) ) => $arfliteformcontroller->get_arflite_default_fonts(),
												'google||' . addslashes( esc_html__( 'Google Fonts', 'arforms-form-builder' ) ) => arflite_google_font_listing(),
											);

											$fontsattr = array(
												'data-arfstyle' => 'true',
												'data-arfstyledata' => '{"standard":".arflite_main_div_{arf_form_id} .arf_fieldset .controls input[type=text]:not(.inplace_field):not(.arf_field_option_input_text):not(.arf_autocomplete):not(.arfslider):not(.arfhiddencolor)~|~font-family||.arflite_main_div_{arf_form_id} .arf_fieldset .controls input[type=email]~|~font-family||.arflite_main_div_{arf_form_id} .arf_fieldset .controls input[type=number]~|~font-family||.arflite_main_div_{arf_form_id} .arf_fieldset .controls input[type=url]~|~font-family||.arflite_main_div_{arf_form_id} .arf_fieldset .controls input[type=tel]~|~font-family||.arflite_main_div_{arf_form_id} .arf_fieldset .controls textarea~|~font-family||.arflite_main_div_{arf_form_id} .arf_fieldset .controls select~|~font-family||.arflite_main_div_101 .sltstandard_front .btn-group .arfbtn.dropdown-toggle~|~font-family||.arflite_main_div_{arf_form_id} .arf_fieldset .controls .arf_field_description~|~font-family||.arflite_main_div_{arf_form_id} .arfdropdown-menu > li > a~|~font-family||.arflite_main_div_{arf_form_id} .sltstandard_front .btn-group .arfbtn.dropdown-toggle~|~font-family||.arflite_main_div_{arf_form_id} .intl-tel-input .country-list~|~font-family","material":".arflite_main_div_{arf_form_id} .arf_fieldset .controls input[type=text]:not(.inplace_field):not(.arf_field_option_input_text)~|~font-family||.arflite_main_div_{arf_form_id} .arf_fieldset .controls input[type=email]~|~font-family||.arflite_main_div_{arf_form_id} .arf_fieldset .controls input[type=number]~|~font-family||.arflite_main_div_{arf_form_id} .arf_fieldset .controls input[type=url]~|~font-family||.arflite_main_div_{arf_form_id} .arf_fieldset .controls input[type=tel]~|~font-family||.arflite_main_div_{arf_form_id} .arf_fieldset .controls input[type=text].arf-select-dropdown~|~font-family||.arflite_main_div_{arf_form_id} .arf_fieldset .controls .arf-select-dropdown li span~|~font-family||.arflite_main_div_{arf_form_id} .arf_fieldset .controls textarea~|~font-family||.arflite_main_div_{arf_form_id} .arf_fieldset .controls select~|~font-family||.arflite_main_div_{arf_form_id} .arf_fieldset .controls .arf_field_description~|~font-family.arflite_main_div_{arf_form_id} .arf_materialize_form input[type=text]:not(.inplace_field):not(.arf_field_option_input_text):not(.arf-select-dropdown):not(.arfslider):not(.arfhiddencolor):not(.arf_autocomplete)~|~font-family||.arflite_main_div_{arf_form_id} .intl-tel-input .country-list~|~font-family"}',
												'data-arfstyleappend' => 'true',
												'data-arfstyleappendid' => 'arf_{arf_form_id}_input_font_family',
												'data-default-font' => $newarr['check_font'],
												'class' => 'arf_custom_font_options',
											);

											echo $arflitemaincontroller->arflite_selectpicker_dom( 'arfcbfs', 'arfcheckboxfontsetting', '', '', $newarr['check_font'], $fontsattr, $fontsarr, true, array(), false, array(), false, array(), true ); //phpcs:ignore

											?>
									</div>
								</div>
							</div>
							<div class="arf_font_style_popup_row">
								<div class="arf_font_style_popup_left"><?php echo esc_html__( 'Size', 'arforms-form-builder' ); ?></div>
								<div class="arf_font_style_popup_right arfwidth63">
									<div class="arf_dropdown_wrapper arfmarginleft">
										<?php
											$font_size_opts = array();
										for ( $i = 8; $i <= 20; $i++ ) {
											$font_size_opts[ $i ] = $i;
										}
										for ( $i = 22; $i <= 28; $i = $i + 2 ) {
											$font_size_opts[ $i ] = $i;
										}
										for ( $i = 32; $i <= 40; $i = $i + 4 ) {
											$font_size_opts[ $i ] = $i;
										}

											$font_size_attr = array(
												'data-arfstyle' => 'true',
												'data-arfstyledata' => '{"standard":".arflite_main_div_{arf_form_id} .arf_fieldset .controls input[type=text]:not(.inplace_field):not(.arf_field_option_input_text):not(.arf_autocomplete):not(.arfslider):not(.arfhiddencolor)~|~font-size||.arflite_main_div_{arf_form_id} .arf_editor_prefix_suffix_wrapper .arf_editor_prefix_icon~|~font-size||.arflite_main_div_{arf_form_id} .arf_editor_prefix_suffix_wrapper .arf_editor_suffix_icon~|~font-size||.arflite_main_div_{arf_form_id} .sltstandard_front .btn-group .arfbtn.dropdown-toggle~|~font-size||.arflite_main_div_{arf_form_id} .arf_fieldset .controls input[type=email]~|~font-size||.arflite_main_div_{arf_form_id} .arf_fieldset .controls input[type=number]~|~font-size||.arflite_main_div_{arf_form_id} .arf_fieldset .controls input[type=url]~|~font-size||.arflite_main_div_{arf_form_id} .arf_fieldset .controls input[type=tel]~|~font-size||.arflite_main_div_{arf_form_id} .arf_fieldset .controls textarea~|~font-size||.arflite_main_div_{arf_form_id} .arf_fieldset .controls select~|~font-size||.arflite_main_div_{arf_form_id} .arfdropdown-menu > li > a~|~font-size||.arflite_main_div_{arf_form_id} .sltstandard_front .btn-group .arfbtn.dropdown-toggle~|~font-size|| .arflite_main_div_{arf_form_id} .intl-tel-input .country-list~|~font-size","material":".arflite_main_div_{arf_form_id} .arf_fieldset .controls input[type=text]:not(.inplace_field):not(.arf_field_option_input_text):not(.arf-select-dropdown):not(.arfslider):not(.arfhiddencolor):not(.arf_autocomplete)~|~font-size||.arflite_main_div_{arf_form_id} .arf_material_theme_container_with_icons .arf_leading_icon~|~font-size||.arflite_main_div_{arf_form_id} .arf_material_theme_container_with_icons .arf_trailing_icon~|~font-size||.arflite_main_div_{arf_form_id} .arf_material_theme_container_with_icons .arf_main_label~|~font-size||.arflite_main_div_{arf_form_id} .arf_fieldset .controls input[type=email]~|~font-size||.arflite_main_div_{arf_form_id} .arf_fieldset .controls input[type=number]~|~font-size||.arflite_main_div_{arf_form_id} .arf_fieldset .controls input[type=url]~|~font-size||.arflite_main_div_{arf_form_id} .arf_fieldset .controls input[type=tel]~|~font-size||.arflite_main_div_{arf_form_id} .arf_fieldset .controls input[type=text].arf-select-dropdown~|~font-size||.arflite_main_div_{arf_form_id} .arf_fieldset .controls .arf-select-dropdown li span~|~font-size||.arflite_main_div_{arf_form_id} .arf_fieldset .controls textarea~|~font-size||.arflite_main_div_{arf_form_id} .arf_fieldset .controls select~|~font-size||.arflite_main_div_{arf_form_id} .intl-tel-input .country-list~|~font-size"}',
												'data-arfstyleappend' => 'true',
												'data-arfstyleappendid' => 'arf_{arf_form_id}_label_font_size',
												'class' => 'arf_custom_font_options',
											);

											echo $arflitemaincontroller->arflite_selectpicker_dom( 'arfffss', 'arffieldfontsizesetting', 'arflite_font_size_dropdown', '', $newarr['field_font_size'], $font_size_attr, $font_size_opts ); //phpcs:ignore
											?>
									</div>
									<div class="arfwidthpx" style="<?php echo ( is_rtl() ) ? 'margin-right: 25px;margin-left: 0px;position:relative;' : 'margin-left: 25px;'; ?>">px</div>
								</div>
							</div>
							<div class="arf_font_style_popup_row">
								<div class="arf_font_style_popup_left"><?php echo esc_html__( 'Style', 'arforms-form-builder' ); ?></div>
								<div class="arf_font_style_popup_right">
									<input id="arfcheckboxweightsetting" name="arfcbws" value="<?php echo esc_attr( $newarr['check_weight'] ); ?>" type="hidden" data-arfstyle="true" data-arfstyledata='{"standard":".arflite_main_div_{arf_form_id} .arf_fieldset .controls input[type=text]:not(.inplace_field):not(.arf_field_option_input_text):not(.arf_autocomplete):not(.arfslider):not(.arfhiddencolor)~|~font-style||.arflite_main_div_{arf_form_id} .arf_fieldset .controls textarea~|~font-style||.arflite_main_div_{arf_form_id} .arf_fieldset .controls select~|~font-style||.arflite_main_div_101 .sltstandard_front .btn-group .arfbtn.dropdown-toggle~|~font-style||.arflite_main_div_{arf_form_id} .arfdropdown-menu > li > a~|~font-style||.arflite_main_div_{arf_form_id} .sltstandard_front .btn-group .arfbtn.dropdown-toggle~|~font-style||.arflite_main_div_{arf_form_id} .intl-tel-input .country-list~|~font-style||.arflite_main_div_{arf_form_id} .arf_fieldset .controls .arf-select-dropdown li span~|~font-style","material":".arflite_main_div_{arf_form_id} .arf_fieldset .controls input:not(.inplace_field):not(.arf_field_option_input_text):not(.arf_autocomplete):not(.arfslider):not(.arfhiddencolor)~|~font-style||.arflite_main_div_{arf_form_id} .arf_fieldset .controls textarea~|~font-style||.arflite_main_div_{arf_form_id} .arf_fieldset .controls select~|~font-style||.arflite_main_div_{arf_form_id} .intl-tel-input .country-list~|~font-style||.arflite_main_div_{arf_form_id} .arf_fieldset .controls .arf-select-dropdown li span~|~font-style"}' data-arfstyleappend="true" data-arfstyleappendid="arf_{arf_form_id}_input_font_style" class="arf_custom_font_options arf_custom_font_style" data-default-font="<?php echo esc_attr( $newarr['check_weight'] ); ?>" >
									<?php $arf_input_font_style_arr = explode( ',', $newarr['check_weight'] ); ?>
									<span class="arf_font_style_button <?php echo ( in_array( 'strikethrough', $arf_input_font_style_arr ) ) ? 'active' : ''; ?>" data-style="strikethrough" data-id="arfcheckboxweightsetting"><i class="fas fa-strikethrough"></i></span>
									<span class="arf_font_style_button <?php echo ( in_array( 'underline', $arf_input_font_style_arr ) ) ? 'active' : ''; ?>" data-style="underline" data-id="arfcheckboxweightsetting"><i class="fas fa-underline"></i></span>
									<span class="arf_font_style_button <?php echo ( in_array( 'italic', $arf_input_font_style_arr ) ) ? 'active' : ''; ?>" data-style="italic" data-id="arfcheckboxweightsetting"><i class="fas fa-italic"></i></span>
									<span class="arf_font_style_button <?php echo ( in_array( 'bold', $arf_input_font_style_arr ) ) ? 'active' : ''; ?>" data-style="bold" data-id="arfcheckboxweightsetting"><i class="fas fa-bold"></i></span>
								</div>
							</div>
						</div>
						<div class="arf_accordion_container_row_separator"></div>
						
						<div class="arf_accordion_container_row arf_margin arfdisablediv arf_restricted_control">
							<div class="arf_accordion_outer_title"><?php echo esc_html__( 'Section Font Settings', 'arforms-form-builder' ); ?></div>
						</div>
						<div class="arf_font_setting_class arf_section_font_setting arfdisablediv">
							<div class="arf_font_style_popup_row arfdisablediv">
								<div class="arf_font_style_popup_left"><?php echo esc_html__( 'Family', 'arforms-form-builder' ); ?></div>
								<div class="arf_font_style_popup_right arfdisablediv">
									<div class="arf_dropdown_wrapper">
										<?php
											$newarr['arfsectiontitlefamily'] = ( isset( $newarr['arfsectiontitlefamily'] ) && $newarr['arfsectiontitlefamily'] != '' ) ? $newarr['arfsectiontitlefamily'] : 'Helvetica';

											$fontsarr = array(
												'' => array(
													'inherit' => addslashes( esc_html__( 'Inherit from theme', 'arforms-form-builder' ) ),
												),
												'default||' . addslashes( esc_html__( 'Default Fonts', 'arforms-form-builder' ) ) => $arfliteformcontroller->get_arflite_default_fonts(),
												'google||' . addslashes( esc_html__( 'Google Fonts', 'arforms-form-builder' ) ) => arflite_google_font_listing(),
											);

											echo $arflitemaincontroller->arflite_selectpicker_dom( 'arfsectiontitlefamily', 'arfsectiontitlefamily', 'arf_restricted_control', '', $newarr['arfsectiontitlefamily'], array(), $fontsarr, true, array(), true, array(), false, array(), true ); //phpcs:ignore
											?>
									</div>
								</div>
							</div>
							 <div class="arf_font_style_popup_row arfdisablediv">
								 
								<div class="arf_font_style_popup_left"><?php echo esc_html__( 'Size', 'arforms-form-builder' ); ?></div>
								<div class="arf_font_style_popup_right arfwidth63">
									<div class="arf_dropdown_wrapper arfmarginleft arfdisablediv">
										<?php
											$font_size_opts = array();
										for ( $i = 8; $i <= 20; $i++ ) {
											$font_size_opts[ $i ] = $i;
										}
										for ( $i = 22; $i <= 28; $i = $i + 2 ) {
											$font_size_opts[ $i ] = $i;
										}
										for ( $i = 32; $i <= 40; $i = $i + 4 ) {
											$font_size_opts[ $i ] = $i;
										}

											$font_size_attr = array(
												'class' => 'arf_custom_font_options',
												'data-default-font' => '19',
											);

											$section_title_font_size = isset( $newarr['arfsectiontitlefontsizesetting'] ) ? $newarr['arfsectiontitlefontsizesetting'] : '19';


											echo $arflitemaincontroller->arflite_selectpicker_dom( 'arfsectiontitlefontsizesetting', 'arfsectiontitlefontsizesetting', 'arf_restricted_control arflite_font_size_dropdown', '', $section_title_font_size, $font_size_attr, $font_size_opts, false, array(), true ); //phpcs:ignore
											?>
									</div>
									<div class="arfwidthpx" style="<?php echo ( is_rtl() ) ? 'margin-right: 25px;margin-left: 0px;position:relative;' : 'margin-left: 25px;'; ?>">px</div>
								</div>
							</div>
							<div class="arf_font_style_popup_row arfdisablediv">
								<div class="arf_font_style_popup_left"><?php echo esc_html__( 'Style', 'arforms-form-builder' ); ?></div>
								
								<div class="arf_font_style_popup_right arfsectionfontstyleiconswrap arfdisablediv">
									<input id="arfsectiontitleweightsetting" name="arfsectiontitleweightsetting" value="" data-arfstyleappendid="arf_{arf_form_id}_section_title_style" type="hidden" class="arf_custom_font_options arf_custom_font_style" data-default-font="" />
									
									<span class="arf_font_style_button arfdisablediv arf_restricted_control" data-style="strikethrough" data-id="arfsectiontitleweightsetting"><i class="fas fa-strikethrough"></i></span>
									<span class="arf_font_style_button arfdisablediv arf_restricted_control" data-style="underline" data-id="arfsectiontitleweightsetting"><i class="fas fa-underline"></i></span>
									<span class="arf_font_style_button arfdisablediv arf_restricted_control" data-style="italic" data-id="arfsectiontitleweightsetting"><i class="fas fa-italic"></i></span>
									<span class="arf_font_style_button arfdisablediv arf_restricted_control" data-style="bold" data-id="arfsectiontitleweightsetting"><i class="fas fa-bold"></i></span>                    
								</div>
							</div>
						</div>
						<div class="arf_accordion_container_row_separator"></div>
						<div class="arf_accordion_container_row arf_margin" id="arf_submit_font_settings_container">
							<div class="arf_accordion_outer_title"><?php echo esc_html__( 'Submit Font Settings', 'arforms-form-builder' ); ?></div>
						</div>
						<?php
						$submit_font_weight_html = '';
						if ( $newarr['arfsubmitweightsetting'] != 'normal' ) {
							$submit_font_weight_html = ', ' . $newarr['arfsubmitweightsetting'];
						}
						?>
						<div class="arf_font_setting_class">
							<div class="arf_font_style_popup_row">
								<div class="arf_font_style_popup_left"><?php echo esc_html__( 'Family', 'arforms-form-builder' ); ?></div>
								<div class="arf_font_style_popup_right">
									<div class="arf_dropdown_wrapper">
										<?php
											$newarr['arfsubmitfontfamily'] = ( isset( $newarr['arfsubmitfontfamily'] ) && $newarr['arfsubmitfontfamily'] != '' ) ? $newarr['arfsubmitfontfamily'] : 'Helvetica';

											$fontsarr = array(
												'' => array(
													'inherit' => addslashes( esc_html__( 'Inherit from theme', 'arforms-form-builder' ) ),
												),
												'default||' . addslashes( esc_html__( 'Default Fonts', 'arforms-form-builder' ) ) => $arfliteformcontroller->get_arflite_default_fonts(),
												'google||' . addslashes( esc_html__( 'Google Fonts', 'arforms-form-builder' ) ) => arflite_google_font_listing(),
											);

											$fontsattr = array(
												'data-arfstyle' => 'true',
												'data-arfstyledata' => '{"standard":".arflite_main_div_{arf_form_id} .arfsubmitbutton .arf_submit_btn~|~font-family","material":".arflite_main_div_{arf_form_id}  .arfsubmitbutton .arf_submit_btn~|~font-family"}',
												'data-arfstyleappend' => 'true',
												'data-arfstyleappendid' => 'arf_{arf_form_id}_submit_btn_font_family',
												'data-default-font' => $newarr['arfsubmitfontfamily'],
												'class' => 'arf_custom_font_options',
											);

											echo $arflitemaincontroller->arflite_selectpicker_dom( 'arfsff', 'arfsubmitfontfamily', '', '', $newarr['arfsubmitfontfamily'], $fontsattr, $fontsarr, true, array(), false, array(), false, array(), true ); //phpcs:ignore
											?>
									</div>
								</div>
							</div>
							<div class="arf_font_style_popup_row">
								<div class="arf_font_style_popup_left"><?php echo esc_html__( 'Size', 'arforms-form-builder' ); ?></div>
								<div class="arf_font_style_popup_right arfwidth63">
									<div class="arf_dropdown_wrapper arfmarginleft">
										<?php
											$font_size_opts = array();
										for ( $i = 8; $i <= 20; $i++ ) {
											$font_size_opts[ $i ] = $i;
										}
										for ( $i = 22; $i <= 28; $i = $i + 2 ) {
											$font_size_opts[ $i ] = $i;
										}
										for ( $i = 32; $i <= 40; $i = $i + 4 ) {
											$font_size_opts[ $i ] = $i;
										}

											$font_size_attr = array(
												'data-arfstyle' => 'true',
												'data-arfstyledata' => '{"standard":".arflite_main_div_{arf_form_id}  .arfsubmitbutton .arf_submit_btn~|~font-size","material":".arflite_main_div_{arf_form_id} .arf_materialize_form .arfsubmitbutton .arf_submit_btn~|~font-size"}',
												'data-arfstyleappend' => 'true',
												'data-arfstyleappendid' => 'arf_{arf_form_id}_submit_btn_font_size',
												'class' => 'arf_custom_font_options',
												'data-default-font' => esc_attr( $newarr['arfsubmitbuttonfontsizesetting'] ),
											);

											echo $arflitemaincontroller->arflite_selectpicker_dom( 'arfsbfss', 'arfsubmitbuttonfontsizesetting', 'arflite_font_size_dropdown', '', $newarr['arfsubmitbuttonfontsizesetting'], $font_size_attr, $font_size_opts ); //phpcs:ignore
											?>
									</div>
									<div class="arfwidthpx" style="<?php echo ( is_rtl() ) ? 'margin-right: 25px;margin-left: 0px;position:relative;' : 'margin-left: 25px;'; ?>">px</div>
								</div>
							</div>
							<div class="arf_font_style_popup_row">
								<div class="arf_font_style_popup_left"><?php echo esc_html__( 'Style', 'arforms-form-builder' ); ?></div>
								<div class="arf_font_style_popup_right">
									<input id="arfsubmitbuttonweightsetting" name="arfsbwes" value="<?php echo esc_attr( $newarr['arfsubmitweightsetting'] ); ?>" data-arfstyle="true" data-arfstyledata='{"standard":".arflite_main_div_{arf_form_id}  .arfsubmitbutton .arf_submit_btn~|~font-style","material":".arflite_main_div_{arf_form_id}  .arfsubmitbutton .arf_submit_btn~|~font-style"}'  data-arfstyleappend="true" data-arfstyleappendid="arf_{arf_form_id}_submit_btn_font_style" type="hidden" class="arf_custom_font_options arf_custom_font_style" data-default-font="<?php echo esc_attr( $newarr['arfsubmitweightsetting'] ); ?>">
									<?php $arf_submit_button_font_style_arr = explode( ',', $newarr['arfsubmitweightsetting'] ); ?>
									<span class="arf_font_style_button <?php echo ( in_array( 'strikethrough', $arf_submit_button_font_style_arr ) ) ? 'active' : ''; ?>" data-style="strikethrough" data-id="arfsubmitbuttonweightsetting"><i class="fas fa-strikethrough"></i></span>
									<span class="arf_font_style_button <?php echo ( in_array( 'underline', $arf_submit_button_font_style_arr ) ) ? 'active' : ''; ?>" data-style="underline" data-id="arfsubmitbuttonweightsetting"><i class="fas fa-underline"></i></span>
									<span class="arf_font_style_button <?php echo ( in_array( 'italic', $arf_submit_button_font_style_arr ) ) ? 'active' : ''; ?>" data-style="italic" data-id="arfsubmitbuttonweightsetting"><i class="fas fa-italic"></i></span>
									<span class="arf_font_style_button <?php echo ( in_array( 'bold', $arf_submit_button_font_style_arr ) ) ? 'active' : ''; ?>" data-style="bold" data-id="arfsubmitbuttonweightsetting"><i class="fas fa-bold"></i></span>
								</div>
							</div>
						</div>
						<div class="arf_accordion_container_row_separator"></div>
						<div class="arf_accordion_container_row arf_margin">
							<div class="arf_accordion_outer_title"><?php echo esc_html__( 'Validation Font Settings', 'arforms-form-builder' ); ?></div>
						</div>

						<div class="arf_font_setting_class">
							<div class="arf_font_style_popup_row">
								<div class="arf_font_style_popup_left"><?php echo esc_html__( 'Family', 'arforms-form-builder' ); ?></div>
								<div class="arf_font_style_popup_right">
									<div class="arf_dropdown_wrapper">
										<?php
											$newarr['error_font'] = ( isset( $newarr['error_font'] ) && $newarr['error_font'] != '' ) ? $newarr['error_font'] : 'Helvetica';

											$fontsarr = array(
												'' => array(
													'inherit' => addslashes( esc_html__( 'Inherit from theme', 'arforms-form-builder' ) ),
												),
												'default||' . addslashes( esc_html__( 'Default Fonts', 'arforms-form-builder' ) ) => $arfliteformcontroller->get_arflite_default_fonts(),
												'google||' . addslashes( esc_html__( 'Google Fonts', 'arforms-form-builder' ) ) => arflite_google_font_listing(),
											);

											$fontsattr = array(
												'data-default-font' => $newarr['error_font'],
											);

											echo $arflitemaincontroller->arflite_selectpicker_dom( 'arfmefs', 'arfmainerrorfontsetting', '', '', $newarr['error_font'], $fontsattr, $fontsarr, true, array(), false, array(), false, array(), true ); //phpcs:ignore
											?>
									</div>
								</div>
							</div>
							<div class="arf_font_style_popup_row">
								<div class="arf_font_style_popup_left"><?php echo esc_html__( 'Size', 'arforms-form-builder' ); ?></div>
								<div class="arf_font_style_popup_right arfwidth63">
									<div class="arf_dropdown_wrapper arfmarginleft">
										<?php
											$font_size_opts = array();
										for ( $i = 8; $i <= 20; $i++ ) {
											$font_size_opts[ $i ] = $i;
										}
										for ( $i = 22; $i <= 28; $i = $i + 2 ) {
											$font_size_opts[ $i ] = $i;
										}
										for ( $i = 32; $i <= 40; $i = $i + 4 ) {
											$font_size_opts[ $i ] = $i;
										}

											echo $arflitemaincontroller->arflite_selectpicker_dom( 'arfmefss', 'arfmainerrorfontsizesetting', 'arflite_font_size_dropdown', '', $newarr['arffontsizesetting'], array(), $font_size_opts ); //phpcs:ignore
										?>
									</div>
									<div class="arfwidthpx" style="<?php echo ( is_rtl() ) ? 'margin-right: 25px;margin-left: 0px;position:relative;' : 'margin-left: 25px;'; ?>">px</div>
								</div>
							</div>
						</div>

					</div>
					<div class="arf_custom_font_popup_footer">
						<div class="arf_custom_font_button_position">
							<div class="arf_custom_font_button arf_custom_font_save_close" id="arf_custom_font_save_btn"><?php echo esc_html__( 'Apply', 'arforms-form-builder' ); ?></div>
							<div class="arf_custom_font_button arf_custom_font_cancel arf_custom_font_close" id="arf_custom_font_cancel_btn"><?php echo esc_html__( 'Cancel', 'arforms-form-builder' ); ?></div>
						</div>
					</div>
				</div>

			</div>
		</div>
		
		<div class="arf_modal_overlay">
			<div id="arf_mail_notification_model" class="arf_popup_container arf_popup_container_mail_notification_model">
				<div class="arf_popup_container_header"><?php echo esc_html__( 'Email Notifications', 'arforms-form-builder' ); ?>
				<div class="arfpopupclosebutton arfmodalclosebutton" data-dismiss="arfmodal" data-id="arf_mail_notification_popup_button"><svg width="30px" height="30px" viewbox="1 0 20 20"><g id="preview"><path fill-rule="evenodd" clip-rule="evenodd" fill="#262944" d="M10.702,10.909L6.453,6.66l-4.249,4.249L1.143,9.848l4.249-4.249L1.154,1.361l1.062-1.061l4.237,4.237l4.238-4.237l1.061,1.061L7.513,5.599l4.249,4.249L10.702,10.909z"></path></g></svg></div>
				</div>
				<div class="arf_popup_content_container arf_mail_notification_container">
					<div class="arf_popup_container_loader">
						<i class="fas fa-spinner fa-spin"></i>
					</div>
					<div class="arf_popup_checkbox_wrapper arfemail-notify-wrap">
						<?php $values['auto_responder'] = isset( $values['auto_responder'] ) ? $values['auto_responder'] : ''; ?>
						<div class="arf_custom_checkbox_div">
							<div class="arf_custom_checkbox_wrapper arfemail-notify-checkboxwrap" onclick="arfliteCheckUserAutomaticResponseEnableDisable();">
								<?php $arf_checked = isset( $values['auto_responder'] ) ? $values['auto_responder'] : 0; ?>
								<input type="checkbox" name="options[auto_responder]" id="auto_responder" value="1" <?php checked( $arf_checked, 1 ); ?> />
								<svg width="18px" height="18px">
								<?php echo ARFLITE_CUSTOM_UNCHECKED_ICON; //phpcs:ignore ?>
								<?php echo ARFLITE_CUSTOM_CHECKED_ICON; //phpcs:ignore ?>
								</svg>
								<?php unset( $arf_checked ); ?>
							</div>
							<span><label id="arf_auto_responder" for="auto_responder" class="arffont16"><?php echo esc_html__( 'Send an automatic response to users after form submission.', 'arforms-form-builder' ); ?></label></span>
						</div>

						 <div style="<?php echo ( is_rtl() ) ? 'float: left;' : 'float: right;'; ?>">
							<a  target="_blank" title="Help" class="fas fa-life-ring arf_adminhelp_icon arfhelptip tipso_style" data-tipso="Help" onclick="arf_help_doc_fun('arf_email_notification');"></a>
						</div>
					</div>

					<div class="arf_auto_responder_content arfmarginl10" >
						<div class="arf_auto_responder_row">
							<div class="arf_auto_responder_column">
								<label class="arf_auto_responder_label arf_send_mail_to_label"><?php echo esc_html__( 'Select field to send E-mail', 'arforms-form-builder' ); ?></label>
								<?php
								$auto_responder_disabled = '';
								if ( isset( $values['auto_responder'] ) && $values['auto_responder'] < 1 ) {
									$auto_responder_disabled = "disabled='disabled'";
								}

								$selectbox_field_options     = array( '' => addslashes( esc_html__( 'Select Field', 'arforms-form-builder' ) ) );
								$selectbox_field_value_label = '';
								$user_responder_email        = '';
								if ( ! empty( $values['fields'] ) ) {
									if ( ! empty( $all_hidden_fields ) ) {
										$hidden_fields    = $arfliteformcontroller->arfliteObjtoArray( $all_hidden_fields );
										$values['fields'] = array_merge( $hidden_fields, $values['fields'] );
									}
									foreach ( $values['fields'] as $val_key => $fo ) {
										if ( in_array( $fo['type'], array( 'email', 'text', 'hidden', 'radio', 'select' ) ) ) {

											if ( isset( $fo['has_parent'] ) && $fo['has_parent'] == 1 && isset( $fo['parent_field_type'] ) && $fo['parent_field_type'] == 'arf_repeater' ) {
												continue;
											}
											if ( ( $fo['id'] == $values['ar_email_to'] ) ) {
												$selectbox_field_value_label = $fo['name'];
												$user_responder_email        = $values['ar_email_to'];
											}

											$current_field_id = $fo['id'];
											if ( $current_field_id != '' && $arflitefieldhelper->arflite_execute_function( $fo['name'], 'strip_tags' ) == '' ) {
												$selectbox_field_options[ $current_field_id ] = '[Field id : ' . $current_field_id . ']';
											} else {
												$selectbox_field_options[ $current_field_id ] = $arflitefieldhelper->arflite_execute_function( $fo['name'], 'strip_tags' );
											}
										}
									}
								}

								$user_responder_email        = apply_filters( 'arflite_change_autoresponse_selected_email_value_in_outside', $user_responder_email, $arflite_id, $values );
								$selectbox_field_value_label = apply_filters( 'arflite_change_autoresponse_selected_email_label_in_outside', $selectbox_field_value_label, $arflite_id, $values );

								$ar_email_to_val = ( isset( $responder_email ) && $responder_email != '' && $responder_email != '0' ) ? $responder_email : $user_responder_email;

								$ar_email_to_attr = array();
								if ( isset( $values['arf_conditional_enable_mail'] ) && $values['arf_conditional_enable_mail'] == 1 ) {
									$ar_email_to_attr['disabled'] = 'disabled';
								}

								if ( $values['auto_responder'] == 0 && $auto_responder_disabled != '' || ( isset( $values['arf_conditional_enable_mail'] ) && $values['arf_conditional_enable_mail'] == 1 ) ) {
									$ar_email_to_opt_disable = true;
									$arf_autoresponder_cls   = 'arf_options_ar_user_email_to arf_email_field_dropdown arf_auto_responder_disabled';
								} else {
									$ar_email_to_opt_disable = false;
									$arf_autoresponder_cls   = 'arf_options_ar_user_email_to arf_email_field_dropdown';
								}

								echo $arflitemaincontroller->arflite_selectpicker_dom( 'options[ar_email_to]', 'options_ar_user_email_to', $arf_autoresponder_cls, 'width:80%;margin-top: 7px;', $user_responder_email, $ar_email_to_attr, $selectbox_field_options, false, array(), $ar_email_to_opt_disable, array(), false, array(), true, 'arf_email_field_dropdown' ); //phpcs:ignore

								?>
								<div class="arf_popup_tooltip_main arfemail-tooltip"><img src="<?php echo esc_url( ARFLITEIMAGESURL ); ?>/tooltips-icon.png" alt="?" class="arfhelptip" title="<?php echo esc_html__( 'Please map desired email field from the list of fields used in your form. And system will send response email to this address.', 'arforms-form-builder' ); ?>"/></div>
							</div>
							<div class="arf_auto_responder_column">
								<label class="arf_auto_responder_label_full"><?php echo esc_html__( 'Subject E-mail', 'arforms-form-builder' ); ?></label>
								<?php

								$ar_email_subject = isset( $values['ar_email_subject'] ) ? $values['ar_email_subject'] : '';
								$ar_email_subject = $arfliteformhelper->arflite_replace_field_shortcode( $ar_email_subject );
								
								?>

								<input type="text" name="options[ar_email_subject]" class="arf_advanceemailfield arfheight34" id="ar_email_subject" value="<?php echo esc_attr( $ar_email_subject ); ?>" <?php echo ( isset( $values['auto_responder'] ) && $values['auto_responder'] < 1 ) ? ' disabled="disabled" ' : ''; ?> />
								<button type="button" class="arf_add_field_button" onclick="arflite_add_field_fun('add_field_subject')" id="add_field_email_subject_but" <?php echo ( isset( $values['auto_responder'] ) && $values['auto_responder'] < 1 ) ? ' disabled="disabled" ' : ''; ?>><?php echo esc_html__( 'Add Field', 'arforms-form-builder' ); ?>&nbsp;&nbsp;<img src="<?php echo esc_url( ARFLITEIMAGESURL ); ?>/down-arrow.png" align="absmiddle" /></button>
								<div class="arf_main_field_modal">
									<div class="arf_add_fieldmodal" id="add_field_subject">
										<div class="arf_modal_header">
											<div class="arf_add_field_title">
												<?php echo esc_html__( 'Fields', 'arforms-form-builder' ); ?>
												<div data-dismiss="arfmodal" onclick="arflite_close_add_field_subject('add_field_subject')" class="arf_field_model_close">
													<svg viewBox="0 -4 32 32"><g id="email"><path fill-rule="evenodd" clip-rule="evenodd" fill="#333333" d="M10.702,10.909L6.453,6.66l-4.249,4.249L1.143,9.848l4.249-4.249L1.154,1.361l1.062-1.061l4.237,4.237l4.238-4.237l1.061,1.061L7.513,5.599l4.249,4.249L10.702,10.909z"></path></g></svg>
												</div>
											</div>
										</div>
										<div class="arfmodal-body_p">
											<?php
											if ( isset( $values['id'] ) ) {

												$arflitefieldhelper->arflite_get_shortcode_modal( $values['id'], 'ar_email_subject', 'no_email', 'style="width:330px;"', false, $field_list );
											}
											?>
										</div>
									</div>
								</div>
								<div style="margin-top: 5px;">
								<div><label><code>[form_name]</code> - <?php echo esc_html__( 'This will be replaced with form name.', 'arforms-form-builder' ); ?></label></div>
								<div><label><code>[site_name]</code> - <?php echo esc_html__( 'This will be replaced with name of site.', 'arforms-form-builder' ); ?></label></div>
							</div>
							</div>
							
						</div>
					
						<div class="arf_auto_responder_row arfliteautoresor">
							<div class="arf_or_option"><?php echo esc_html__( 'Or', 'arforms-form-builder' ); ?></div>
						</div>
						<div class="arf_auto_responder_row" style="margin-bottom:20px;">
							<div class="arf_popup_checkbox_wrapper" >
								<div class="arf_custom_checkbox_div">
									<div class="arf_custom_checkbox_wrapper arfemail-notify-checkboxwrap">
										<input type="checkbox" value="1" id="arf_conditional_enable_disable_mail_id_chkbox" class="arf_restricted_control">
										<svg width="18px" height="18px">
										<?php echo ARFLITE_CUSTOM_UNCHECKED_ICON; //phpcs:ignore ?>
										<?php echo ARFLITE_CUSTOM_CHECKED_ICON; //phpcs:ignore ?>
										</svg>
									</div>
								<span><label for="arf_conditional_enable_disable_mail_id_chkbox" class="arf_auto_responder_label arfhelptip" title="<?php echo esc_html__( 'Please select options to send an automatic response to user.', 'arforms-form-builder' ); ?>"><?php echo esc_html__( 'Configure Conditional Email Notification', 'arforms-form-builder' ); ?><span class="arflite_pro_version_notice">(Premium)</span></label></span>
								</div>
							</div>

						</div>

						<div class="arf_auto_responder_row">
							<div class="arf_auto_responder_column">
								<?php 
									$reply_to_name = $arformsmain->arforms_get_settings('reply_to_name','general_settings');
									$reply_to_name = !empty( $reply_to_name ) ? $reply_to_name : get_option( 'blogname' );
								?> 
								<label class="arf_auto_responder_label_full"><?php echo esc_html__( 'From/Replyto Name', 'arforms-form-builder' ); ?></label>
								<input type="text" id="options_ar_user_from_name" name="options[ar_user_from_name]" value="<?php echo ( !empty( $values['ar_user_from_name'] ) && esc_attr($values['ar_user_from_name']) != '' ) ? esc_attr( $values['ar_user_from_name'] ) : esc_attr( $reply_to_name ); ?>" <?php echo ( isset( $values['auto_responder'] ) && $values['auto_responder'] < 1 ) ? ' disabled="disabled" ' : ''; //phpcs:ignore ?> >
							</div>

							<div class="arf_auto_responder_column">
								<label class="arf_auto_responder_label_full"><?php echo esc_html__( 'From E-mail', 'arforms-form-builder' ); ?></label>
								<?php
								$ar_user_from_email = isset( $values['ar_user_from_email'] ) ? $values['ar_user_from_email'] : '';
								if ( $ar_user_from_email == '' ) {
									$reply_to = $arformsmain->arforms_get_settings('reply_to','general_settings');
									$reply_to = !empty( $reply_to ) ? $reply_to : get_option( 'admin_email' );
									$ar_user_from_email = $reply_to;
								} else {
									$ar_user_from_email = $values['ar_user_from_email'];
								}

								$ar_user_from_email = $arfliteformhelper->arflite_replace_field_shortcode( $ar_user_from_email );
								?>
								<input type="text" value="<?php echo esc_html( $ar_user_from_email ); ?>" id="ar_user_from_email" name="options[ar_user_from_email]" <?php echo ( isset( $values['auto_responder'] ) && $values['auto_responder'] < 1 ) ? ' disabled="disabled" ' : ''; ?> class="arf_advanceemailfield" />
								<button type="button" class="arf_add_field_button" onclick="arflite_add_field_fun('add_field_user_email')" id="add_field_user_email_but" <?php echo ( isset( $values['auto_responder'] ) && $values['auto_responder'] < 1 ) ? ' disabled="disabled" ' : ''; ?>> <?php echo esc_html__( 'Add Field', 'arforms-form-builder' ); ?>&nbsp;&nbsp;
									<img src="<?php echo esc_url( ARFLITEIMAGESURL ); ?>/down-arrow.png" align="absmiddle" />
								</button>
								<div class="arf_main_field_modal <?php echo isset( $auto_res_email_cls ) ? esc_attr( $auto_res_email_cls ) : ''; ?>">
									<div class="arf_add_fieldmodal" id="add_field_user_email">
										<div class="arf_modal_header">
											<div class="arf_add_field_title">
												<?php echo esc_html__( 'Fields', 'arforms-form-builder' ); ?>
												<div data-dismiss="arfmodal" class="arf_field_model_close" onclick="arflite_close_add_field_subject('add_field_user_email')">
													<svg viewBox="0 -4 32 32"><g id="email"><path fill-rule="evenodd" clip-rule="evenodd" fill="#333333" d="M10.702,10.909L6.453,6.66l-4.249,4.249L1.143,9.848l4.249-4.249L1.154,1.361l1.062-1.061l4.237,4.237l4.238-4.237l1.061,1.061L7.513,5.599l4.249,4.249L10.702,10.909z"></path></g></svg>
												</div>
											</div>
										</div>

										<div class="arfmodal-body_email arfmodal-body_p">
											<?php
											if ( isset( $values['id'] ) ) {
												$arflitefieldhelper->arflite_get_shortcode_modal( $values['id'], 'ar_user_from_email', 'email', 'style="width:330px;"', false, $field_list );
											}
											?>
										</div>
									</div>
								</div>
							</div>
						</div>


						<div class="arf_auto_responder_row">
							<div class="arf_auto_responder_column">
								<label class="arf_auto_responder_label_full"><?php echo esc_html__( 'Reply to E-mail', 'arforms-form-builder' ); ?></label>
								<?php
								$ar_user_nreplyto_email = isset( $values['ar_user_nreplyto_email'] ) ? $values['ar_user_nreplyto_email'] : '';

								if ( $ar_user_nreplyto_email == '' ) {

									$reply_to_email = $arformsmain->arforms_get_settings('reply_to_email','general_settings');
									$reply_to_email = !empty( $reply_to_email ) ? $reply_to_email : get_option( 'admin_email' );
									$ar_user_nreplyto_email = $reply_to_email;
								} else {
									$ar_user_nreplyto_email = $values['ar_user_nreplyto_email'];
								}

								$ar_user_nreplyto_email = $arfliteformhelper->arflite_replace_field_shortcode( $ar_user_nreplyto_email );
								?>

								<input type="text" value="<?php echo esc_html( $ar_user_nreplyto_email ); ?>" id="ar_user_nreplyto_email" name="options[ar_user_nreplyto_email]" <?php echo ( isset( $values['auto_responder'] ) && $values['auto_responder'] < 1 ) ? ' disabled="disabled" ' : ''; ?> class="arf_advanceemailfield" />

								<button type="button" class="arf_add_field_button" onclick="arflite_add_field_fun('add_field_user_nreplyto_email')" id="add_field_user_nreplyto_email_but" <?php echo ( isset( $values['auto_responder'] ) && $values['auto_responder'] < 1 ) ? ' disabled="disabled" ' : ''; ?>><?php echo esc_html__( 'Add Field', 'arforms-form-builder' ); ?>&nbsp;&nbsp;
									<img src="<?php echo esc_url( ARFLITEIMAGESURL ); ?>/down-arrow.png" align="absmiddle" />
								</button>

								<div class="arf_main_field_modal <?php echo isset( $auto_res_email_cls ) ? esc_attr( $auto_res_email_cls ) : ''; ?>">
									<div class="arf_add_fieldmodal" id="add_field_user_nreplyto_email">
										<div class="arf_modal_header">
											<div class="arf_add_field_title">
												<?php echo esc_html__( 'Fields', 'arforms-form-builder' ); ?>
												<div data-dismiss="arfmodal" class="arf_field_model_close" onclick="arflite_close_add_field_subject('add_field_user_nreplyto_email')">
													<svg viewBox="0 -4 32 32"><g id="email"><path fill-rule="evenodd" clip-rule="evenodd" fill="#333333" d="M10.702,10.909L6.453,6.66l-4.249,4.249L1.143,9.848l4.249-4.249L1.154,1.361l1.062-1.061l4.237,4.237l4.238-4.237l1.061,1.061L7.513,5.599l4.249,4.249L10.702,10.909z"></path></g></svg>
												</div>
											</div>
										</div>
										<div class="arfmodal-body_email arfmodal-body_p">
										<?php
										if ( isset( $values['id'] ) ) {
											$arflitefieldhelper->arflite_get_shortcode_modal( $values['id'], 'ar_user_nreplyto_email', 'email', 'style="width:330px;"', false, $field_list );
										}
										?>
										</div>
									</div>
								</div>

							</div>
						</div>

						 <!-- hide empty fields code start for users -->
						 <div class="arf_popup_checkbox_wrapper arf_hide_form_sub" style="margin-top:10px;">
							<div class="arf_custom_checkbox_div" style="margin-top: 4px;">
								<div class="arf_custom_checkbox_wrapper">
									<input type="checkbox" class="arf_restricted_control" name="options[arf_hide_empty_fields]" id="arf_hide_empty_fields_options" value="1">
									<svg width="18px" height="18px">
									<?php echo ARFLITE_CUSTOM_UNCHECKED_ICON; //phpcs:ignore ?>
									<?php echo ARFLITE_CUSTOM_UNCHECKED_ICON; //phpcs:ignore ?>
									</svg>
								</div>
								
								<span><label for="arf_hide_empty_fields_options" style="margin-left: 4px;"><?php echo addslashes( esc_html__( 'Hide Empty Fields From Email Content', 'arforms-form-builder' ) ); //phpcs:ignore ?> </label> <img style="position: relative;top: 5px;left: 2px;" src="<?php echo esc_url( ARFLITEIMAGESURL ); ?>/tooltips-icon.png" alt="?" class="arfhelptip arf_restricted_control" title="<?php echo addslashes( esc_html__( 'Hide Empty Fields From Email Content.', 'arforms-form-builder' ) ); ?>"/><span class="arflite_pro_version_notice arf_restricted_control">(Premium)</span> </span>
							</div>
						</div>
						<!-- hide empty fields code end for users -->			


						<div class="arf_auto_responder_row">
							<div class="arf_width_80">
								<label class="arf_auto_responder_label_full"><?php echo esc_html__( 'Message', 'arforms-form-builder' ); ?></label>
								<?php
								$ar_email_message = ( isset( $values['ar_email_message'] ) && ! empty( $values['ar_email_message'] ) ) ? esc_attr( $arfliteformcontroller->arflitebr2nl( $values['ar_email_message'] ) ) : '';
								$ar_email_message = $arfliteformhelper->arflite_replace_field_shortcode( $ar_email_message );

								$email_editor_settings = array(
									'wpautop'       => true,
									'media_buttons' => false,
									'textarea_name' => 'options[ar_email_message]',
									'textarea_rows' => '4',
									'tinymce'       => false,
									'editor_class'  => 'txtmultimodal1 arf_advanceemailfield ar_email_message_content',
								);

								wp_editor( $ar_email_message, 'ar_email_message', $email_editor_settings );
								?>
								<span class="arferrmessage arferrmessageselectpage" id="ar_email_message_error"><?php echo esc_html__( 'This field cannot be blank', 'arforms-form-builder' ); ?></span>
								<textarea class="arf_email_message_text" name="options[ar_email_message]" id="ar_email_message_text"><?php echo esc_attr($ar_email_message); ?></textarea>
							</div>
							<div class="arf_width_20">
								<button type="button" class="arf_add_field_button" onclick="arflite_add_field_fun('add_field_message')" id="add_field_message_but" <?php echo ( isset( $values['auto_responder'] ) && $values['auto_responder'] < 1 ) ? ' disabled="disabled" ' : ''; ?> ><?php echo esc_html__( 'Add Field', 'arforms-form-builder' ); ?>&nbsp;&nbsp;
									<img src="<?php echo esc_url( ARFLITEIMAGESURL ); ?>/down-arrow.png" align="absmiddle" />
								</button>
								<div class="arf_main_field_modal arf-sel-sub-wrap">
									<div class="arf_add_fieldmodal" id="add_field_message">
										<div class="arf_modal_header">
											<div class="arf_add_field_title">
												<?php echo esc_html__( 'Fields', 'arforms-form-builder' ); ?>
												<div data-dismiss="arfmodal" class="arf_field_model_close" onclick="arflite_close_add_field_subject('add_field_message')">
													<svg viewBox="0 -4 32 32"><g id="email"><path fill-rule="evenodd" clip-rule="evenodd" fill="#333333" d="M10.702,10.909L6.453,6.66l-4.249,4.249L1.143,9.848l4.249-4.249L1.154,1.361l1.062-1.061l4.237,4.237l4.238-4.237l1.061,1.061L7.513,5.599l4.249,4.249L10.702,10.909z"></path></g></svg>
												</div>
											</div>
										</div>

										<div class="arfmodal-body_p">
											<?php
											if ( isset( $values['id'] ) ) {
												$arflitefieldhelper->arflite_get_shortcode_modal( $values['id'], 'ar_email_message', 'no_email', 'style="width:330px;"', false, $field_list );
											}
											?>
										</div>
									</div>
								</div>
							</div>
							<div class="arflite-clear-float"></div>
							<div class="email-msg-shortcode">
								<div><label><code>[ARFLite_form_all_values]</code> - <?php echo esc_html__( 'This will be replaced with form\'s all fields & labels.', 'arforms-form-builder' ); ?></label></div>
								<div><label><code>[ARFLite_form_referer]</code> - <?php echo esc_html__( 'This will be replaced with entry referer.', 'arforms-form-builder' ); ?></label></div>
								<div><label><code>[ARFLite_form_added_date_time]</code> - <?php echo esc_html__( 'This will be replaced with entry added time.', 'arforms-form-builder' ); ?></label></div>
								<div><label><code>[ARFLite_form_ipaddress]</code> - <?php echo esc_html__( 'This will be replaced with IP Address.', 'arforms-form-builder' ); ?></label></div>
								<div><label><code>[ARFLite_form_browsername]</code> - <?php echo esc_html__( 'This will be replaced with user browser name.', 'arforms-form-builder' ); ?></label></div>
								<div><label><code>[ARFLite_form_entryid]</code> - <?php echo esc_html__( 'This will be replaced with Entry ID.', 'arforms-form-builder' ); ?></label></div>
								<div><label><code>[ARFLite_current_userid]</code> - <?php echo esc_html__( 'This will be replaced with current login ID.', 'arforms-form-builder' ); ?></label></div>
								<div><label><code>[ARFLite_current_username]</code> - <?php echo esc_html__( 'This will be replaced with current login user name.', 'arforms-form-builder' ); ?></label></div>
								<div><label><code>[ARFLite_current_useremail]</code> - <?php echo esc_html__( 'This will be replaced with current login user email.', 'arforms-form-builder' ); ?></label></div>
								<div><label><code>[ARFLite_page_url]</code> - <?php echo esc_html__( 'This will be replaced with current form\'s page URL.', 'arforms-form-builder' ); ?></label></div>

								<?php do_action( 'arflite_add_auto_response_mail_shortcode_in_out_side', $arflite_id, $values ); ?>
								<!-- add email attachment -->
									<div class="arflite_user_email_notification_file_attachment arf_restricted_control">
									<!-- <div style="float:left">	 -->
									<div class="arf_email_attchment_div">
										<span class="arflite_user_email_attachment_uploader_label"> <?php echo esc_html__( 'Email Attachment:', 'arforms-form-builder' ); ?> </span>
										<span class="arflite_user_email_attachment_input_note_text">Maximum file size for attachment is 20MB.</span>
									</div>
										<?php
										$auto_responder_disabled_cls = '';
										if ( isset( $values['auto_responder'] ) && $values['auto_responder'] < 1 ) {
											$auto_responder_disabled_cls = 'disabled';
										}
										?>
										<span class="arf_user_email_attachment_uploader"> </span>
											<div class="arf_user_email_attachment_uploader_div <?php echo esc_html( $auto_responder_disabled_cls ); ?>" >
												<div class="arf_email_attachment_file">
														<svg width="14" height="14" viewBox="0 0 100 100"><path xmlns="http://www.w3.org/2000/svg" d="M77.656,56.25c2.396,0,4.531-0.625,6.406-1.875c1.822-1.303,3.385-2.865,4.688-4.688c1.25-1.875,1.875-4.037,1.875-6.484  s-1.275-5-3.828-7.656l-6.328-6.484c-6.719-6.927-13.49-13.646-20.312-20.156L50-0.781L39.844,8.906  c-6.823,6.51-13.594,13.229-20.312,20.156l-6.719,6.953c-2.292,2.344-3.438,4.609-3.438,6.797v0.781  c0,1.667,0.208,3.021,0.625,4.062s1.042,2.083,1.875,3.125c0.885,1.094,1.875,2.084,2.969,2.969  c1.042,0.834,2.083,1.459,3.125,1.875s2.344,0.625,3.906,0.625s2.865-0.209,3.906-0.625c1.094-0.469,2.24-1.197,3.438-2.188  c1.25-0.99,2.682-2.344,4.297-4.062l3.984-4.141v41.562c0,2.553,0.417,4.557,1.25,6.016c0.885,1.459,1.719,2.604,2.5,3.438  c0.833,0.781,1.979,1.615,3.438,2.5C46.146,99.584,47.917,100,50,100c2.084,0,3.854-0.416,5.312-1.25  c1.459-0.885,2.604-1.719,3.438-2.5c0.781-0.834,1.615-1.979,2.5-3.438c0.834-1.459,1.25-3.463,1.25-6.016V45.859l7.422,6.719  C72.631,55.025,75.209,56.25,77.656,56.25"/></svg>&nbsp;
														<span> <?php echo addslashes( esc_html__( 'Choose File', 'arforms-form-builder' ) ); //phpcs:ignore ?>  </span> 
												</div>
											<input type="file" id="arf_user_email_upload_file_attachment" class="arf_email_attachment arf_editor_email_attchment"  name="arf_user_email_upload_file_attachment" data-val="user_email_attachment" <?php echo esc_html( $auto_responder_disabled ); ?> />
										</div>
										<div class="arflite_email_attchemnt_premium">
											<span class="arflite_pro_version_notice">(Premium)</span>
										</div>
									</div>
							</div>
						</div>
					</div>

					<div class="arf_separater"></div>

					<div class="arf_popup_checkbox_wrapper">
						<div class="arf_custom_checkbox_div">
							<div class="arf_custom_checkbox_wrapper" onclick="arfliteCheckAdminAutomaticResponseEnableDisable();" style="margin-right: 9px;">
								<?php $arf_checked = isset( $values['chk_admin_notification'] ) ? $values['chk_admin_notification'] : 0; ?>
								<input type="checkbox" name="options[chk_admin_notification]" id="chk_admin_notification" value="1" <?php checked( $arf_checked, 1 ); ?>  />
								<svg width="18px" height="18px">
								<?php echo ARFLITE_CUSTOM_UNCHECKED_ICON; //phpcs:ignore ?>
								<?php echo ARFLITE_CUSTOM_CHECKED_ICON; //phpcs:ignore ?>
								<?php unset( $arf_checked ); ?>
								</svg>
							</div>
						<span><label id="arf_admin_notification" for="chk_admin_notification" class="arffont16"><?php echo esc_html__( 'Send an automatic response to admin user after form submission.', 'arforms-form-builder' ); ?></label></span>
						</div>
					</div>

					<div class="arf_admin_notification_content arfmarginl10" style="width:100%;">
						<div class="arf_auto_responder_row ">
							<div class="arf_auto_responder_column">
								<?php
								$chk_admin_notification_disabled = "disabled='disabled'";
								if ( isset( $values['chk_admin_notification'] ) && $values['chk_admin_notification'] > 0 ) {
									$chk_admin_notification_disabled = '';

								}
								$ar_admin_to_email = isset( $values['notification'][0]['reply_to'] ) ? esc_attr( $values['notification'][0]['reply_to'] ) : '';
								if ( $ar_admin_to_email == '' ) {
									$reply_to = $arformsmain->arforms_get_settings('reply_to','general_settings');
									$reply_to = !empty( $reply_to ) ? $reply_to : get_option( 'admin_email' );
									$ar_admin_to_email = $reply_to;
								} else {
									$ar_admin_to_email = $values['notification'][0]['reply_to'];
								}
								$ar_admin_to_email = $arfliteformhelper->arflite_replace_field_shortcode( $ar_admin_to_email );
								?>
								<label class="arf_auto_responder_label_full"><?php echo esc_html__( 'Admin E-mail', 'arforms-form-builder' ); ?></label>
								<input type="text" name="options[reply_to]" id="options_admin_reply_to_notification" value="<?php echo esc_html( $ar_admin_to_email ); ?>" <?php echo esc_attr($chk_admin_notification_disabled); ?> class="arf_advanceemailfield" />
								<button type="button" class="arf_add_field_button" onclick="arflite_add_field_fun('add_field_admin_email_to')" id="add_field_admin_email_but_to"  <?php echo esc_attr($chk_admin_notification_disabled); ?> ><?php echo esc_html__( 'Add Field', 'arforms-form-builder' ); ?>&nbsp;&nbsp;<img src="<?php echo esc_url( ARFLITEIMAGESURL ); ?>/down-arrow.png" align="absmiddle" /></button>
								<div class="arf_main_field_modal">
									<div class="arf_add_fieldmodal" id="add_field_admin_email_to">
										<div class="arf_modal_header">
											<div class="arf_add_field_title">
												<?php echo esc_html__( 'Fields', 'arforms-form-builder' ); ?>
												<div data-dismiss="arfmodal" class="arf_field_model_close" onclick="arflite_close_add_field_subject('add_field_admin_email_to')">
													<svg viewBox="0 -4 32 32"><g id="email"><path fill-rule="evenodd" clip-rule="evenodd" fill="#333333" d="M10.702,10.909L6.453,6.66l-4.249,4.249L1.143,9.848l4.249-4.249L1.154,1.361l1.062-1.061l4.237,4.237l4.238-4.237l1.061,1.061L7.513,5.599l4.249,4.249L10.702,10.909z"></path></g></svg>
												</div>
											</div>
										</div>
										<div class="arfmodal-body_email arfmodal-body_p">
											<?php isset( $values['id'] ) ? $arflitefieldhelper->arflite_get_shortcode_modal( $values['id'], 'options_admin_reply_to_notification', 'email', 'style="width:330px;"', false, $field_list ) : ''; ?>
										</div>
									</div>
								</div>
							</div>
							<div class="arf_auto_responder_column">
								<label class="arf_auto_responder_label_full"><?php echo esc_html__( 'Subject E-mail', 'arforms-form-builder' ); ?></label>
								<?php
								$admin_email_subject_value = ( isset( $values['admin_email_subject'] ) ) ? esc_attr( $values['admin_email_subject'] ) : '';
								if ( $admin_email_subject_value == '' ) {
									$admin_email_subject_value = '[form_name] Form submitted on [site_name]';
								} else {
									$admin_email_subject_value = $values['admin_email_subject'];
								}
								?>
								<input type="text" name="options[admin_email_subject]" id="admin_email_subject" value="<?php echo esc_attr( $admin_email_subject_value ); ?>" <?php echo esc_attr($chk_admin_notification_disabled); ?> class="arf_advanceemailfield" />
								<button type="button" class="arf_add_field_button" onclick="arflite_add_field_fun('add_field_admin_email_subject')" id="add_field_admin_email_but_subject"  <?php echo esc_attr($chk_admin_notification_disabled); ?>><?php echo esc_html__( 'Add Field', 'arforms-form-builder' ); ?>&nbsp;&nbsp;<img src="<?php echo esc_url( ARFLITEIMAGESURL ); ?>/down-arrow.png" align="absmiddle" /></button>
								<div class="arf_main_field_modal">
									<div class="arf_add_fieldmodal" id="add_field_admin_email_subject">
										<div class="arf_modal_header">
											<div class="arf_add_field_title">
												<?php echo esc_html__( 'Fields', 'arforms-form-builder' ); ?>
												<div data-dismiss="arfmodal" class="arf_field_model_close" onclick="arflite_close_add_field_subject('add_field_admin_email_subject')">
													<svg viewBox="0 -4 32 32"><g id="email"><path fill-rule="evenodd" clip-rule="evenodd" fill="#333333" d="M10.702,10.909L6.453,6.66l-4.249,4.249L1.143,9.848l4.249-4.249L1.154,1.361l1.062-1.061l4.237,4.237l4.238-4.237l1.061,1.061L7.513,5.599l4.249,4.249L10.702,10.909z"></path></g></svg>
												</div>
											</div>
										</div>
										<div class="arfmodal-body_email arfmodal-body_p">
											<?php isset( $values['id'] ) ? $arflitefieldhelper->arflite_get_shortcode_modal( $values['id'], 'admin_email_subject', 'email', 'style="width:330px;"', false, $field_list ) : ''; ?>
										</div>
									</div>
								</div>
								<div style="margin-top: 5px;">
									<div><label><code>[form_name]</code> - <?php echo esc_html__( 'This will be replaced with form name.', 'arforms-form-builder' ); ?></label></div>
									<div><label><code>[site_name]</code> - <?php echo esc_html__( 'This will be replaced with name of site.', 'arforms-form-builder' ); ?></label></div>
								</div>
							</div>
						</div>
						<div class="arf_auto_responder_row">
						   <div class="arf_auto_responder_column">
								<?php
								$chk_admin_notification_disabled = "disabled='disabled'";
								if ( isset( $values['chk_admin_notification'] ) && $values['chk_admin_notification'] > 0 ) {
									$chk_admin_notification_disabled = '';

								}

								$ar_admin_cc_email = isset( $values['admin_cc_email'] ) ? esc_attr( $values['admin_cc_email'] ) : '';
								if ( $ar_admin_cc_email == '' ) {
									$ar_admin_cc_email = '';
								} else {
									$ar_admin_cc_email = $values['admin_cc_email'];
								}
								$ar_admin_cc_email = $arfliteformhelper->arflite_replace_field_shortcode( $ar_admin_cc_email );
								?>
								<label class="arf_auto_responder_label_full"><?php echo esc_html__( 'Admin CC Email', 'arforms-form-builder' ); ?></label>
								<input type="text" name="options[admin_cc_email]" id="options_admin_cc_email_notification" value="<?php echo esc_html( $ar_admin_cc_email ); ?>" <?php echo esc_attr($chk_admin_notification_disabled); ?> class="arf_advanceemailfield" />
								<button type="button" class="arf_add_field_button" onclick="arflite_add_field_fun('add_field_admin_cc_email')" id="add_field_admin_cc_email_but_to"  <?php echo esc_attr($chk_admin_notification_disabled); ?> ><?php echo esc_html__( 'Add Field', 'arforms-form-builder' ); ?>&nbsp;&nbsp;<img src="<?php echo esc_url( ARFLITEIMAGESURL ); ?>/down-arrow.png" align="absmiddle" /></button>
								<div class="arf_main_field_modal">
									<div class="arf_add_fieldmodal" id="add_field_admin_cc_email">
										<div class="arf_modal_header">
											<div class="arf_add_field_title">
												<?php echo esc_html__( 'Fields', 'arforms-form-builder' ); ?>
												<div data-dismiss="arfmodal" class="arf_field_model_close" onclick="arflite_close_add_field_subject('add_field_admin_cc_email')">
													<svg viewBox="0 -4 32 32"><g id="email"><path fill-rule="evenodd" clip-rule="evenodd" fill="#333333" d="M10.702,10.909L6.453,6.66l-4.249,4.249L1.143,9.848l4.249-4.249L1.154,1.361l1.062-1.061l4.237,4.237l4.238-4.237l1.061,1.061L7.513,5.599l4.249,4.249L10.702,10.909z"></path></g></svg>
												</div>
											</div>
										</div>
										<div class="arfmodal-body_email arfmodal-body_p">
											<?php isset( $values['id'] ) ? $arflitefieldhelper->arflite_get_shortcode_modal( $values['id'], 'options_admin_cc_email_notification', 'email', 'style="width:330px;"', false, $field_list ) : ''; ?>
										</div>
									</div>
								</div>
							</div>
						   <div class="arf_auto_responder_column">
								<?php
								$chk_admin_notification_disabled = "disabled='disabled'";
								if ( isset( $values['chk_admin_notification'] ) && $values['chk_admin_notification'] > 0 ) {
									$chk_admin_notification_disabled = '';

								}
								$ar_admin_bcc_email = isset( $values['admin_bcc_email'] ) ? esc_attr( $values['admin_bcc_email'] ) : '';
								if ( $ar_admin_bcc_email == '' ) {
									$ar_admin_bcc_email = '';
								} else {
									$ar_admin_bcc_email = $values['admin_bcc_email'];
								}
								$ar_admin_bcc_email = $arfliteformhelper->arflite_replace_field_shortcode( $ar_admin_bcc_email );
								?>
								<label class="arf_auto_responder_label_full"><?php echo esc_html__( 'Admin BCC Email', 'arforms-form-builder' ); ?></label>
								<input type="text" name="options[admin_bcc_email]" id="options_admin_bcc_email_notification" value="<?php echo esc_html( $ar_admin_bcc_email ); ?>" <?php echo esc_attr($chk_admin_notification_disabled); ?> class="arf_advanceemailfield" />
								<button type="button" class="arf_add_field_button" onclick="arflite_add_field_fun('add_field_admin_bcc_email')" id="add_field_admin_bcc_email_but_to"  <?php echo esc_attr($chk_admin_notification_disabled); ?> ><?php echo esc_html__( 'Add Field', 'arforms-form-builder' ); ?>&nbsp;&nbsp;<img src="<?php echo esc_url( ARFLITEIMAGESURL ); ?>/down-arrow.png" align="absmiddle" /></button>
								<div class="arf_main_field_modal">
									<div class="arf_add_fieldmodal" id="add_field_admin_bcc_email">
										<div class="arf_modal_header">
											<div class="arf_add_field_title">
												<?php echo esc_html__( 'Fields', 'arforms-form-builder' ); ?>
												<div data-dismiss="arfmodal" class="arf_field_model_close" onclick="arflite_close_add_field_subject('add_field_admin_bcc_email')">
													<svg viewBox="0 -4 32 32"><g id="email"><path fill-rule="evenodd" clip-rule="evenodd" fill="#333333" d="M10.702,10.909L6.453,6.66l-4.249,4.249L1.143,9.848l4.249-4.249L1.154,1.361l1.062-1.061l4.237,4.237l4.238-4.237l1.061,1.061L7.513,5.599l4.249,4.249L10.702,10.909z"></path></g></svg>
												</div>
											</div>
										</div>
										<div class="arfmodal-body_email arfmodal-body_p">
											<?php isset( $values['id'] ) ? $arflitefieldhelper->arflite_get_shortcode_modal( $values['id'], 'options_admin_bcc_email_notification', 'email', 'style="width:330px;"', false, $field_list ) : ''; ?>
										</div>
									</div>
								</div>
							</div>


						</div>
						<div class="arf_auto_responder_row">
							<div class="arf_auto_responder_column">
								<label class="arf_auto_responder_label_full"><?php echo esc_html__( 'From/Replyto Name', 'arforms-form-builder' ); ?></label>
								<?php
									$reply_to_name = $arformsmain->arforms_get_settings('reply_to_name','general_settings');
									$reply_to_name = !empty( $reply_to_email ) ? $reply_to_email : get_option( 'blogname' );
								?>
								<input type="text" id="options_ar_admin_from_name" name="options[ar_admin_from_name]" value="<?php echo ( !empty( $values['ar_admin_from_name'] ) && esc_attr($values['ar_admin_from_name']) != '' ) ? esc_attr( $values['ar_admin_from_name'] ) : esc_attr( $reply_to_name ); ?>" <?php echo esc_attr($chk_admin_notification_disabled); //phpcs:ignore ?> class="arf_advanceemailfield" >
								<button type="button" class="arf_add_field_button" onclick="arflite_add_field_fun('add_field_admin_from_name')" id="add_field_admin_from_but_name"  <?php echo esc_attr($chk_admin_notification_disabled); ?>><?php echo esc_html__( 'Add Field', 'arforms-form-builder' ); ?>&nbsp;&nbsp;<img src="<?php echo esc_url( ARFLITEIMAGESURL ); ?>/down-arrow.png" align="absmiddle" /></button>
								<div class="arf_main_field_modal">
									<div class="arf_add_fieldmodal" id="add_field_admin_from_name">
										<div class="arf_modal_header">
											<div class="arf_add_field_title">
												<?php echo esc_html__( 'Fields', 'arforms-form-builder' ); ?>
												<div data-dismiss="arfmodal" class="arf_field_model_close" onclick="arflite_close_add_field_subject('add_field_admin_from_name')">
													<svg viewBox="0 -4 32 32"><g id="email"><path fill-rule="evenodd" clip-rule="evenodd" fill="#333333" d="M10.702,10.909L6.453,6.66l-4.249,4.249L1.143,9.848l4.249-4.249L1.154,1.361l1.062-1.061l4.237,4.237l4.238-4.237l1.061,1.061L7.513,5.599l4.249,4.249L10.702,10.909z"></path></g></svg>
												</div>
											</div>
										</div>
										<div class="arfmodal-body_email arfmodal-body_p">
											<?php isset( $values['id'] ) ? $arflitefieldhelper->arflite_get_shortcode_modal( $values['id'], 'options_ar_admin_from_name', 'email', 'style="width:330px;"', false, $field_list ) : ''; ?>
										</div>
									</div>
								</div>
							</div>
							<div class="arf_auto_responder_column">
								<label class="arf_auto_responder_label_full"><?php echo esc_html__( 'From E-mail', 'arforms-form-builder' ); ?></label>
								<?php
								$ar_admin_from_email = isset( $values['ar_admin_from_email'] ) ? $values['ar_admin_from_email'] : '';
								if ( $ar_admin_from_email == '' ) {
									$reply_to = $arformsmain->arforms_get_settings('reply_to','general_settings');
									$reply_to = !empty( $reply_to ) ? $reply_to : get_option( 'admin_email' );
									$ar_admin_from_email = $reply_to;
								} else {
									$ar_admin_from_email = $values['ar_admin_from_email'];
								}
								$ar_admin_from_email = $arfliteformhelper->arflite_replace_field_shortcode( $ar_admin_from_email );
								?>
								<input type="text" value="<?php echo esc_html( $ar_admin_from_email ); ?>" id="ar_admin_from_email" name="options[ar_admin_from_email]" <?php echo esc_attr($chk_admin_notification_disabled); ?> class="arf_advanceemailfield" />
								<button type="button" class="arf_add_field_button" onclick="arflite_add_field_fun('add_field_admin_email')" id="add_field_admin_email_but"  <?php echo esc_attr($chk_admin_notification_disabled); ?>><?php echo esc_html__( 'Add Field', 'arforms-form-builder' ); ?>&nbsp;&nbsp;<img src="<?php echo esc_url( ARFLITEIMAGESURL ); ?>/down-arrow.png" align="absmiddle" /></button>
								<div class="arf_main_field_modal">
									<div class="arf_add_fieldmodal" id="add_field_admin_email">
										<div class="arf_modal_header">
											<div class="arf_add_field_title">
												<?php echo esc_html__( 'Fields', 'arforms-form-builder' ); ?>
												<div data-dismiss="arfmodal" class="arf_field_model_close" onclick="arflite_close_add_field_subject('add_field_admin_email')">
													<svg viewBox="0 -4 32 32"><g id="email"><path fill-rule="evenodd" clip-rule="evenodd" fill="#333333" d="M10.702,10.909L6.453,6.66l-4.249,4.249L1.143,9.848l4.249-4.249L1.154,1.361l1.062-1.061l4.237,4.237l4.238-4.237l1.061,1.061L7.513,5.599l4.249,4.249L10.702,10.909z"></path></g></svg>
												</div>
											</div>
										</div>
										<div class="arfmodal-body_email arfmodal-body_p">
											<?php isset( $values['id'] ) ? $arflitefieldhelper->arflite_get_shortcode_modal( $values['id'], 'ar_admin_from_email', 'email', 'style="width:330px;"', false, $field_list ) : ''; ?>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="arf_auto_responder_row">
							<div class="arf_auto_responder_column">
								<label class="arf_auto_responder_label_full"><?php echo esc_html__( 'Reply to E-mail', 'arforms-form-builder' ); ?></label>
								<?php
								$ar_admin_reply_to_email = isset( $values['ar_admin_reply_to_email'] ) ? $values['ar_admin_reply_to_email'] : '';
								if ( $ar_admin_reply_to_email == '' ) {
									$reply_to_email = $arformsmain->arforms_get_settings('reply_to_email','general_settings');
									$reply_to_email = !empty( $reply_to_email ) ? $reply_to_email : get_option( 'admin_email' );
									$ar_admin_reply_to_email = $reply_to_email;
								} else {
									$ar_admin_reply_to_email = $values['ar_admin_reply_to_email'];
								}

								$ar_admin_reply_to_email = $arfliteformhelper->arflite_replace_field_shortcode( $ar_admin_reply_to_email );
								?>

								<input type="text" value="<?php echo esc_html( $ar_admin_reply_to_email ); ?>" id="ar_admin_reply_to_email" name="options[ar_admin_reply_to_email]" <?php echo esc_attr($chk_admin_notification_disabled); ?> class="arf_advanceemailfield" />

								<button type="button" class="arf_add_field_button" onclick="arflite_add_field_fun('add_field_admin_nreplyto_email')" id="add_field_admin_nreplyto_email_but" <?php echo esc_attr($chk_admin_notification_disabled); ?>><?php echo esc_html__( 'Add Field', 'arforms-form-builder' ); ?>&nbsp;&nbsp;
									<img src="<?php echo esc_url( ARFLITEIMAGESURL ); ?>/down-arrow.png" align="absmiddle" />
								</button>

								<div class="arf_main_field_modal <?php echo isset( $auto_res_email_cls ) ? esc_attr( $auto_res_email_cls ) : ''; ?>">
									<div class="arf_add_fieldmodal" id="add_field_admin_nreplyto_email">
										<div class="arf_modal_header">
											<div class="arf_add_field_title">
												<?php echo esc_html__( 'Fields', 'arforms-form-builder' ); ?>
												<div data-dismiss="arfmodal" class="arf_field_model_close" onclick="arflite_close_add_field_subject('add_field_admin_nreplyto_email')">
													<svg viewBox="0 -4 32 32"><g id="email"><path fill-rule="evenodd" clip-rule="evenodd" fill="#333333" d="M10.702,10.909L6.453,6.66l-4.249,4.249L1.143,9.848l4.249-4.249L1.154,1.361l1.062-1.061l4.237,4.237l4.238-4.237l1.061,1.061L7.513,5.599l4.249,4.249L10.702,10.909z"></path></g></svg>
												</div>
											</div>
										</div>
										<div class="arfmodal-body_email arfmodal-body_p">
										<?php
										if ( isset( $values['id'] ) ) {
											$arflitefieldhelper->arflite_get_shortcode_modal( $values['id'], 'ar_admin_reply_to_email', 'email', 'style="width:330px;"', false, $field_list );
										}
										?>
										</div>
									</div>
								</div>

							</div>
						</div>

						<!-- hide empty fields code start for admin -->
						<div class="arf_popup_checkbox_wrapper arf_hide_form_sub" style="margin-top:10px;">
								<div class="arf_custom_checkbox_div" style="margin-top: 4px;">
									<div class="arf_custom_checkbox_wrapper">
										<input type="checkbox" name="options[arf_hide_empty_fields_admin]" id="arf_hide_empty_fields_options_for_admin" value="1" class="arf_restricted_control"/>
										<svg width="18px" height="18px">
										<?php echo ARFLITE_CUSTOM_UNCHECKED_ICON; //phpcs:ignore ?>
										<?php echo ARFLITE_CUSTOM_UNCHECKED_ICON; //phpcs:ignore ?>
										</svg>
									</div>
									<span><label for="arf_hide_empty_fields_options_for_admin" style="margin-left: 4px;"><?php echo addslashes( esc_html__( 'Hide Empty Fields From Email Content', 'arforms-form-builder' ) ); //phpcs:ignore ?> </label> <img style="position: relative;top: 5px;left: 2px;" src="<?php echo esc_url( ARFLITEIMAGESURL ); ?>/tooltips-icon.png" alt="?" class="arfhelptip arf_restricted_control" title="<?php echo addslashes( esc_html__( 'Hide Empty Fields From Email Content.', 'arforms-form-builder' ) ); //phpcs:ignore ?>"/><span class="arflite_pro_version_notice arf_restricted_control">(Premium)</span> </span>
								</div>
							</div>
						<!-- hide empty fields code end for admin -->

						<div class="arf_auto_responder_row">
							<div class="arf_width_80">
								<label class="arf_auto_responder_label_full"><?php echo esc_html__( 'Admin Message', 'arforms-form-builder' ); ?></label>
								<div>
								<?php
								$ar_admin_email_message = ( isset( $values['ar_admin_email_message'] ) && ! empty( $values['ar_admin_email_message'] ) ) ? esc_attr( $arfliteformcontroller->arflitebr2nl( $values['ar_admin_email_message'] ) ) : '';
								$ar_admin_email_message = $arfliteformhelper->arflite_replace_field_shortcode( $ar_admin_email_message );
								$email_editor_settings  = array(
									'wpautop'       => true,
									'media_buttons' => false,
									'textarea_name' => 'options[ar_admin_email_message]',
									'textarea_rows' => '4',
									'tinymce'       => false,
									'editor_class'  => 'txtmultimodal1 arf_advanceemailfield ar_admin_email_message_content',
								);
								wp_editor( $ar_admin_email_message, 'ar_admin_email_message', $email_editor_settings );
								?>
								<textarea style="display:none;opacity: 0; width:0; height: 0" name="options[ar_admin_email_message]" id="ar_admin_email_message_text"><?php echo esc_html( $ar_admin_email_message ); ?></textarea>
								</div>
							</div>
							<div class="arf_width_20">
								<button type="button" class="arf_add_field_button" onclick="arflite_add_field_fun('add_field_admin_message')" id="add_field_admin_message_but"  <?php echo esc_attr($chk_admin_notification_disabled); ?> ><?php echo esc_html__( 'Add Field', 'arforms-form-builder' ); ?>&nbsp;&nbsp;<img src="<?php echo esc_url( ARFLITEIMAGESURL ); ?>/down-arrow.png" align="absmiddle" /></button>
								<div class="arf_main_field_modal" style="margin-top:-21px;">
									<div class="arf_add_fieldmodal" id="add_field_admin_message">
										<div class="arf_modal_header">
											<div class="arf_add_field_title">
												<?php echo esc_html__( 'Fields', 'arforms-form-builder' ); ?>
												<div data-dismiss="arfmodal" class="arf_field_model_close" onclick="arflite_close_add_field_subject('add_field_admin_message')">
													<svg viewBox="0 -4 32 32"><g id="email"><path fill-rule="evenodd" clip-rule="evenodd" fill="#333333" d="M10.702,10.909L6.453,6.66l-4.249,4.249L1.143,9.848l4.249-4.249L1.154,1.361l1.062-1.061l4.237,4.237l4.238-4.237l1.061,1.061L7.513,5.599l4.249,4.249L10.702,10.909z"></path></g></svg>
												</div>
											</div>
										</div>
										<div class="arfmodal-body_email arfmodal-body_p">
											<?php isset( $values['id'] ) ? $arflitefieldhelper->arflite_get_shortcode_modal( $values['id'], 'ar_admin_email_message', 'no_email', 'style="width:330px;"', false, $field_list ) : ''; ?>
										</div>
									</div>
								</div>
							</div>
							<span class="arferrmessage" id="ar_admin_email_message_error" style="top:0px;"><?php echo esc_html__( 'This field cannot be blank', 'arforms-form-builder' ); ?></span>
							<div class="arflite-clear-float email-msg-shortcode">
								<div><label><code>[ARFLite_form_all_values]</code> - <?php echo esc_html__( 'This will be replaced with form\'s all fields & labels.', 'arforms-form-builder' ); ?></label></div>
								<div><label><code>[ARFLite_form_referer]</code> - <?php echo esc_html__( 'This will be replaced with entry referer.', 'arforms-form-builder' ); ?></label></div>
								<div><label><code>[ARFLite_form_added_date_time]</code> - <?php echo esc_html__( 'This will be replaced with entry added time.', 'arforms-form-builder' ); ?></label></div>
								<div><label><code>[ARFLite_form_ipaddress]</code> - <?php echo esc_html__( 'This will be replaced with IP Address.', 'arforms-form-builder' ); ?></label></div>
								<div><label><code>[ARFLite_form_browsername]</code> - <?php echo esc_html__( 'This will be replaced with user browser name.', 'arforms-form-builder' ); ?></label></div>
								<div><label><code>[ARFLite_form_entryid]</code> - <?php echo esc_html__( 'This will be replaced with Entry ID.', 'arforms-form-builder' ); ?></label></div>
								<div><label><code>[ARFLite_current_userid]</code> - <?php echo esc_html__( 'This will be replaced with current login ID.', 'arforms-form-builder' ); ?></label></div>
								<div><label><code>[ARFLite_current_username]</code> - <?php echo esc_html__( 'This will be replaced with current login user name.', 'arforms-form-builder' ); ?></label></div>
								<div><label><code>[ARFLite_current_useremail]</code> - <?php echo esc_html__( 'This will be replaced with current login user email.', 'arforms-form-builder' ); ?></label></div>
								<div><label><code>[ARFLite_page_url]</code> - <?php echo esc_html__( 'This will be replaced with current form\'s page URL.', 'arforms-form-builder' ); ?></label></div>
								<?php do_action( 'arflite_add_admin_mail_shortcode_in_outside', $arflite_id, $values ); ?>
							</div>
						</div>
					</div>

					<?php do_action( 'arflite_additional_autoresponder_settings', $arflite_id, $values ); ?>
					<?php do_action( 'arflite_after_autoresponder_settings_container', $arflite_id, $values ); ?>
				</div>


				<div class="arf_popup_container_footer">
					<button type="button" class="arf_popup_close_button" data-id="arf_mail_notification_popup_button" ><?php echo esc_html__( 'OK', 'arforms-form-builder' ); ?></button>
				</div>
			</div>
		</div>
		

		
		<div class="arf_modal_overlay">
			<div id="arf_conditional_logic_model" class="arf_popup_container arf_popup_container_conditional_logic_model" style="">
				<div class="arf_popup_container_header"><?php echo esc_html__( 'Conditional Rule', 'arforms-form-builder' ); ?>
					<div class="arfpopupclosebutton arfmodalclosebutton" data-dismiss="arfmodal" data-id="arf_optin_popup_button">
						<svg width="30px" height="30px" viewBox="1 0 20 20"><g id="preview"><path fill-rule="evenodd" clip-rule="evenodd" fill="#262944" d="M10.702,10.909L6.453,6.66l-4.249,4.249L1.143,9.848l4.249-4.249L1.154,1.361l1.062-1.061l4.237,4.237l4.238-4.237l1.061,1.061L7.513,5.599l4.249,4.249L10.702,10.909z"></path></g></svg>
					</div>
				</div>
				<div class="arf_popup_content_container arf_submit_popup_container arflite_pro_version_conditional_logic">
					<div class="arf_popup_container_loader">
						<i class="fas fa-spinner fa-spin"></i>
					</div>
				</div>
				<div class="arf_popup_container_footer">
					<button type="button" class="arf_popup_close_button" data-id="arf_optin_popup_button" ><?php echo esc_html__( 'OK', 'arforms-form-builder' ); ?></button>
				</div>

			</div>
		</div>
		
		
		<div class="arf_modal_overlay">
			<div id="arf_submit_action_model" class="arf_popup_container arf_popup_container_submit_action_model">
				<div class="arf_popup_container_header"><?php echo esc_html__( 'Submit Action', 'arforms-form-builder' ); ?>
					<div class="arfpopupclosebutton arfmodalclosebutton" data-dismiss="arfmodal" data-id="arf_submit_popup_button">
						<svg width="30px" height="30px" viewBox="1 0 20 20"><g id="preview"><path fill-rule="evenodd" clip-rule="evenodd" fill="#262944" d="M10.702,10.909L6.453,6.66l-4.249,4.249L1.143,9.848l4.249-4.249L1.154,1.361l1.062-1.061l4.237,4.237l4.238-4.237l1.061,1.061L7.513,5.599l4.249,4.249L10.702,10.909z"></path></g></svg>
					</div>
				</div>
				<div class="arf_popup_content_container arf_submit_action_container">
					<div class="arf_popup_container_loader">
						<i class="fas fa-spinner fa-spin"></i>
					</div>
					<p class="arftitle_p">
						<label for="conditional_logic_arfsubmit"><?php echo esc_html__( 'Form submission action', 'arforms-form-builder' ); ?></label>
						<label class="arfsub-action-msg">
						<div style="<?php echo ( is_rtl() ) ? 'float: left;' : 'float: right;'; ?>">
							<a  target="_blank" title="Help" class="fas fa-life-ring arf_adminhelp_icon arfhelptip tipso_style" data-tipso="Help" onclick="arf_help_doc_fun('arf_submition_action');"></a>
						</div>
						</label>
					</p>

					<div class="arf_submit_action_options">
						<div class="arf_radio_wrapper">
							<div class="arf_custom_radio_div">
								<div class="arf_custom_radio_wrapper">
									<input type="radio" class="arf_custom_radio arf_submit_action" name="options[success_action]" id="success_action_message" value="message" <?php checked( $values['success_action'], 'message' ); ?> />
									<svg width="18px" height="18px">
									<?php echo ARFLITE_CUSTOM_UNCHECKEDRADIO_ICON; //phpcs:ignore ?>
									<?php echo ARFLITE_CUSTOM_CHECKEDRADIO_ICON; //phpcs:ignore ?>
									</svg>
								</div>
							</div>
							<span>
								<label id="success_action_message" for="success_action_message"><?php echo esc_html__( 'Display a Message', 'arforms-form-builder' ); ?></label>
							</span>
						</div>
						<div class="arf_radio_wrapper">
							<div class="arf_custom_radio_div">
								<div class="arf_custom_radio_wrapper">
									<input type="radio" name="options[success_action]" id="success_action_redirect" class="arf_submit_action arf_custom_radio" value="redirect" <?php checked( $values['success_action'], 'redirect' ); ?> />
									<svg width="18px" height="18px">
									<?php echo ARFLITE_CUSTOM_UNCHECKEDRADIO_ICON; //phpcs:ignore ?>
									<?php echo ARFLITE_CUSTOM_CHECKEDRADIO_ICON; //phpcs:ignore ?>
									</svg>
								</div>
							</div>
							<span>
								<label id="success_action_redirect" for="success_action_redirect"><?php echo esc_html__( 'Redirect to URL', 'arforms-form-builder' ); ?></label>
							</span>
						</div>
						<div class="arf_radio_wrapper">
							<div class="arf_custom_radio_div" >
								<div class="arf_custom_radio_wrapper">
									<input type="radio" name="options[success_action]" id="success_action_page" class="arf_submit_action arf_custom_radio" value="page" <?php checked( $values['success_action'], 'page' ); ?> />
									<svg width="18px" height="18px">
									<?php echo ARFLITE_CUSTOM_UNCHECKEDRADIO_ICON; //phpcs:ignore ?>
									<?php echo ARFLITE_CUSTOM_CHECKEDRADIO_ICON; //phpcs:ignore ?>
									</svg>
								</div>
							</div>
							<span>
								<label id="success_action_page" for="success_action_page"><?php echo esc_html__( 'Display content from another page', 'arforms-form-builder' ); ?></label>
							</span>
						</div>
					</div>

					<div id="arf_success_action_message" class="arf_optin_tab_inner_container arfmarginl15 arf_submit_action_inner_container <?php echo ( $values['success_action'] == 'message' ) ? 'arfactive' : ''; ?>">
						<div class="arfcolumnleft arfsettingsubtitle"><label for="success_msg" class="arf_dropdown_autoresponder_label"><?php echo esc_html__( 'Confirmation Message', 'arforms-form-builder' ); ?></label></div>
						<div class="arfcolumnright fix_height">
							<textarea id="success_msg" class="auto_responder_webform_code_area txtmultimodal1" name="options[success_msg]" cols="2" rows="4"><?php echo esc_html( $values['success_msg'] ); ?></textarea>
							<span class="arferrmessage" id="success_msg_error"><?php echo esc_html__( 'This field cannot be blank', 'arforms-form-builder' ); ?></span>
						</div>
					</div>


					<div id="arf_success_action_redirect" class="arf_optin_tab_inner_container arfmarginl15 arf_submit_action_inner_container <?php echo ( $values['success_action'] == 'redirect' ) ? 'arfactive' : ''; ?>">
						<label for="success_url" class="arf_dropdown_autoresponder_label"><?php echo esc_html__( 'Set Static Redirect URL', 'arforms-form-builder' ); ?></label>
						<input type="text" id="success_url" class="arf_large_input_box arf_redirect_to_url success_url_width" name="options[success_url]" value="<?php echo isset( $values['success_url'] ) ? esc_url( $values['success_url'] ) : ''; ?>" />
						<span class="arferrmessage" id="success_url_error" style='top:0;'><?php echo esc_html__( 'This field cannot be blank', 'arforms-form-builder' ); ?></span>
						<br/><i class="arf_notes redirect-urlnot"><?php echo esc_html__( 'Please insert url with http:// or https://.', 'arforms-form-builder' ); ?></i>
						<?php do_action( 'arflite_form_submit_after_redirect_to_url', $arflite_id, $values ); ?>

						<div class="arfcolumnleft arf_custom_margin_redirect arfsetcondtionalredirect">
							<div class="arf_custom_checkbox_div">
								<div class="arf_custom_checkbox_wrapper">
									<input type="checkbox" value="1" class="chkstanard arf_restricted_control" id="arf_sa_data_with_url">
									<svg width="18px" height="18px"><path id="arfcheckbox_unchecked" d="M15.643,17.617H3.499c-1.34,0-2.427-1.087-2.427-2.429V3.045  c0-1.341,1.087-2.428,2.427-2.428h12.144c1.342,0,2.429,1.087,2.429,2.428v12.143C18.072,16.53,16.984,17.617,15.643,17.617z   M16.182,2.477H2.961v13.221h13.221V2.477z"></path><path id="arfcheckbox_checked" d="M15.645,17.62H3.501c-1.34,0-2.427-1.087-2.427-2.429V3.048  c0-1.341,1.087-2.428,2.427-2.428h12.144c1.342,0,2.429,1.087,2.429,2.428v12.143C18.074,16.533,16.986,17.62,15.645,17.62z   M16.184,2.48H2.963v13.221h13.221V2.48z M5.851,7.15l2.716,2.717l5.145-5.145l1.718,1.717l-5.146,5.145l0.007,0.007l-1.717,1.717  l-0.007-0.008l-0.006,0.008l-1.718-1.717l0.007-0.007L4.134,8.868L5.851,7.15z"></path></svg>
								</div>
								<span>
									<label for="arf_sa_data_with_url" style="margin-left: 4px;"><?php echo esc_html__( 'Send form data to redirected page/post', 'arforms-form-builder' ); ?><span class="arflite_pro_version_notice">(Premium)</span></label>
								</span>
							</div>
						</div>
					</div>

					<div id="arf_success_action_page" class="arf_optin_tab_inner_container arfmarginl15 arf_submit_action_inner_container <?php echo ( $values['success_action'] == 'page' ) ? 'arfactive' : ''; ?>">
						<div class="arf_ar_dropdown_wrapper">
							<label class="arf_dropdown_autoresponder_label" id="arf_use_content_from_page"><?php echo esc_html__( 'Select Page', 'arforms-form-builder' ); ?></label>
							<?php $arflitemainhelper->wp_arflite_pages_dropdown( 'options[success_page_id]', isset( $values['success_page_id'] ) ? $values['success_page_id'] : '', '', 'option_success_page_id' ); ?>
							<span class="arferrmessage arferrmessageselectpage" id="option_success_page_id_error"><?php echo esc_html__( 'This field cannot be blank', 'arforms-form-builder' ); ?></span>
						</div>
					</div>

					<div class="arf_popup_checkbox_wrapper arf_hide_form_sub arflite-hideaftersave-wrap">
						<div class="arf_custom_checkbox_div">
							<div class="arf_custom_checkbox_wrapper">
								<input type="checkbox" name="options[arf_form_hide_after_submit]" id="arf_hide_form_after_submitted" value="1" <?php isset( $values['arf_form_hide_after_submit'] ) ? checked( $values['arf_form_hide_after_submit'], 1 ) : ''; ?> />
								<svg width="18px" height="18px">
								<?php echo ARFLITE_CUSTOM_UNCHECKED_ICON; //phpcs:ignore ?>
								<?php echo ARFLITE_CUSTOM_CHECKED_ICON; //phpcs:ignore ?>
								</svg>
							</div>
							<span><label id="arf_hide_form_after_submitted" for="arf_hide_form_after_submitted"><?php echo esc_html__( 'Hide Form after submission', 'arforms-form-builder' ); ?></label></span>
						</div>
					</div>

					<?php do_action( 'arflite_option_before_submit_conditional_logic', $arflite_id, $values ); ?>

					<div class="arf_separater arflite-sub-action-seprater"></div>

					<div class="submit_action_conditonal_law" style="margin-top: -15px;margin-left: 6px;">
						<div class="field_conditional_law field_basic_option arf_fieldoptiontab" style="display:block;">
							<div class="arf_enable_conditional_submit_div" <?php echo( is_rtl() ) ? 'style="margin-right:1px;"' : ''; ?>>
								<div class="arf_custom_checkbox_div">
									<div class="arf_custom_checkbox_wrapper">
										<input type="checkbox" class="arf_restricted_control" name="conditional_logic_arfsubmit" id="conditional_logic_arfsubmit"/>
										<svg width="18px" height="18px">
											<?php echo ARFLITE_CUSTOM_UNCHECKED_ICON; //phpcs:ignore ?>
											<?php echo ARFLITE_CUSTOM_CHECKED_ICON; //phpcs:ignore ?>
										</svg>
									</div>
									<span>
										<label for="conditional_logic_arfsubmit" class="arftitle_p" style="margin-left: 4px;font-size: 16px !important; margin-top: 3px;"><?php echo esc_html__( 'Configure conditional submission', 'arforms-form-builder' ); ?><span class="arflite_pro_version_notice">(Premium)</span></label>
									</span>
								</div>
							</div>
						</div>
					</div>

					<?php do_action( 'arflite_after_onsubmit_settings_container', $arflite_id, $values ); ?>
				</div>
				<div class="arf_popup_container_footer">
					<button type="button" class="arf_popup_close_button" data-id="arf_submit_popup_button" ><?php echo esc_html__( 'OK', 'arforms-form-builder' ); ?></button>
				</div>
			</div>
		</div>
		
		<div class="arf_modal_overlay">
			<div id="arf_optin_model" class="arf_popup_container arf_popup_container_option_model">
				<div class="arf_popup_container_header"><?php echo esc_html__( 'Opt-ins (email marketing) configuration', 'arforms-form-builder' ); ?>
					<div class="arfpopupclosebutton arfmodalclosebutton" data-dismiss="arfmodal" data-id="arf_optin_popup_button">
						<svg width="30px" height="30px" viewBox="1 0 20 20"><g id="preview"><path fill-rule="evenodd" clip-rule="evenodd" fill="#262944" d="M10.702,10.909L6.453,6.66l-4.249,4.249L1.143,9.848l4.249-4.249L1.154,1.361l1.062-1.061l4.237,4.237l4.238-4.237l1.061,1.061L7.513,5.599l4.249,4.249L10.702,10.909z"></path></g></svg>
					</div>
				</div>
				<div class="arf_option_model_popup_container arf_optins_container arflite_pro_version_email_marketers">

				</div>
				<div class="arf_popup_container_footer">
					<button type="button" class="arf_popup_close_button" data-id="arf_optin_popup_button" ><?php echo esc_html__( 'OK', 'arforms-form-builder' ); ?></button>
				</div>
			</div>
		</div>
		
		<div class="arf_modal_overlay">
			<div id="arf_other_options_model" class="arf_popup_container arf_popup_container_other_option_model">
				<div class="arf_popup_container_header"><?php echo esc_html__( 'General Options', 'arforms-form-builder' ); ?>
					<div class="arfpopupclosebutton arfmodalclosebutton" data-dismiss="arfmodal" data-id="arf_general_popup_button">
						<svg width="30px" height="30px" viewBox="1 0 20 20"><g id="preview"><path fill-rule="evenodd" clip-rule="evenodd" fill="#262944" d="M10.702,10.909L6.453,6.66l-4.249,4.249L1.143,9.848l4.249-4.249L1.154,1.361l1.062-1.061l4.237,4.237l4.238-4.237l1.061,1.061L7.513,5.599l4.249,4.249L10.702,10.909z"></path></g></svg>
					</div>
				</div>
				<div class="arf_popup_content_container arf_other_options_container arflite_pro_version_other_options">

				</div>
				<div class="arf_popup_container_footer">
					<button type="button" class="arf_popup_close_button" data-id="arf_optin_popup_button" ><?php echo esc_html__( 'OK', 'arforms-form-builder' ); ?></button>
				</div>
			</div>
		</div>
		
		<div class="arf_modal_overlay">

			<div id="arf_hidden_fields_options_model" class="arf_popup_container arf_popup_container_other_option_model">
				<div class="arf_popup_container_header"><?php echo esc_html__( 'Hidden Input Fields Options', 'arforms-form-builder' ); ?>
					<div class="arfpopupclosebutton arfmodalclosebutton" data-dismiss="arfmodal" data-id="arf_optin_popup_button">
						<svg width="30px" height="30px" viewBox="1 0 20 20"><g id="preview"><path fill-rule="evenodd" clip-rule="evenodd" fill="#262944" d="M10.702,10.909L6.453,6.66l-4.249,4.249L1.143,9.848l4.249-4.249L1.154,1.361l1.062-1.061l4.237,4.237l4.238-4.237l1.061,1.061L7.513,5.599l4.249,4.249L10.702,10.909z"></path></g></svg>
					</div>
				</div>

				<div class="arf_popup_content_container arf_other_options_container">

					<div class="arf_hidden_fields_wrapper">
					<div style="<?php echo ( is_rtl() ) ? 'float: left;' : 'float: right;'; ?>">
							<a  target="_blank" title="Help" class="fas fa-life-ring arf_adminhelp_icon arfhelptip tipso_style" data-tipso="Help" onclick="arf_help_doc_fun('arf_hidden_input_fields');"></a>
						</div>
						<span class="arf_hidden_field_title"><?php echo esc_html__( 'Hidden Input Fields Setup', 'arforms-form-builder' ); ?></span>
						<div class="arf_hidden_field_note">
							<div><?php echo esc_html__( 'Note', 'arforms-form-builder' ) . ': ' . esc_html__( 'These fields will not shown in the form. Enter the value to be hidden', 'arforms-form-builder' ); ?></div>

							<div>[ARF_current_user_id] : <?php echo esc_html__( 'This shortcode replace the value with currently logged-in User ID.', 'arforms-form-builder' ); ?></div>
							<div>[ARF_current_user_name] : <?php echo esc_html__( 'This shortcode replace the value with currently logged-in User Name.', 'arforms-form-builder' ); ?></div>
							<div>[ARF_current_user_email] : <?php echo esc_html__( 'This shortcode replace the value with currently logged-in User Email.', 'arforms-form-builder' ); ?></div>
							<div>[ARF_current_date] : <?php echo esc_html__( 'This shortcode replace the value with current Date.', 'arforms-form-builder' ); ?></div>

						</div>
						<button type="button" id="arf_add_new_hidden_field" class="rounded_button arf_btn_dark_blue add_new_hidden_field_button" style="<?php echo ( count( $all_hidden_fields ) > 0 ) ? 'display:none;' : ''; ?>"><?php echo esc_html__( 'Add new hidden field', 'arforms-form-builder' ); ?></button>
						<div class="arf_hidden_field_input_wrapper_header <?php echo ( count( $all_hidden_fields ) > 0 ) ? 'arfactive' : ''; ?>">
							<span class="arf_hidden_field_input_wrapper_header_label"><?php echo esc_html__( 'Label', 'arforms-form-builder' ); ?></span>
							<span class="arf_hidden_field_input_wrapper_header_value"><?php echo esc_html__( 'Value', 'arforms-form-builder' ); ?></span>
							<span class="arf_hidden_field_input_wrapper_header_action"><?php echo esc_html__( 'Action', 'arforms-form-builder' ); ?></span>
						</div>
						<div class="arf_hidden_fields_input_wrapper">
						<?php
						if ( count( $all_hidden_fields ) > 0 ) {
							$counter               = 1;
							$hidden_fields_content = '';
							foreach ( $all_hidden_fields as $hkey => $hd_field ) {
								$field_opts = json_decode( $hd_field->field_options );
								if ( json_last_error() != JSON_ERROR_NONE ) {
									$field_opts = maybe_unserialize( $hd_field->field_options );
								}
								echo "<div class='arf_hidden_field_input_container' id='arf_hidden_field_input_container_'" . esc_attr( $counter ) . '>';
								echo "<label class='arf_hidden_field_input_label' for='arf_hidden_field_input_'" . esc_attr( $counter ) . '>';
								echo "<input type='text' class='arf_large_input_box arf_hidden_field_label_input' value='" . esc_attr( $hd_field->name ) . "' data-field-id='" . esc_attr( $hd_field->id ) . "' id='arf_hidden_field_input_label_'" . esc_attr( $counter ) . "' />";
								echo '</label>';
								echo "<input type='text' name='item_meta['" . esc_attr( $hd_field->id ) . "]' class='arf_large_input_box' id='arf_hidden_field_input_'" . esc_attr( $counter ) . "' value='" . esc_attr( $field_opts->default_value ) . "' />";
								echo "<input type='hidden' name='arf_field_data_'" . esc_attr( $hd_field->id ) . "' id='arf_field_data_'" . esc_attr( $hd_field->id ) . "' value='" . esc_attr( $hd_field->field_options ) . "' data-field-option='[]' />";
								echo "<div class='arf_hidden_field_input_action_button'>";
								echo '<span class="arf_hidden_field_add"><svg viewBox="0 -4 32 32"><g id="email"><path fill-rule="evenodd" clip-rule="evenodd" fill="#3f74e7" d="M11.134,20.362c-5.521,0-9.996-4.476-9.996-9.996 c0-5.521,4.476-9.997,9.996-9.997s9.996,4.476,9.996,9.997C21.13,15.887,16.654,20.362,11.134,20.362z M11.133,2.314c-4.446,0-8.051,3.604-8.051,8.051c0,4.447,3.604,8.052,8.051,8.052s8.052-3.604,8.052-8.052C19.185,5.919,15.579,2.314,11.133,2.314z M12.146,14.341h-2v-3h-3v-2h3V6.372h2v2.969h3v2h-3V14.341z"/></g></svg></span>';
								echo '<span class="arf_hidden_field_remove" data-id="' . esc_attr( $counter ) . '"><svg viewBox="0 -4 32 32"><g id="email"><path fill-rule="evenodd" clip-rule="evenodd" fill="#3f74e7" d="M11.12,20.389c-5.521,0-9.996-4.476-9.996-9.996c0-5.521,4.476-9.997,9.996-9.997s9.996,4.476,9.996,9.997C21.116,15.913,16.64,20.389,11.12,20.389zM11.119,2.341c-4.446,0-8.051,3.604-8.051,8.051c0,4.447,3.604,8.052,8.051,8.052s8.052-3.604,8.052-8.052C19.17,5.945,15.565,2.341,11.119,2.341z M12.131,11.367h3v-2h-3h-2h-3v2h3H12.131z"/></g></svg></span>';
								echo '</div>';
								echo '</div>';
								$counter++;
							}
						}
						?>
						</div>
					</div>
				</div>
				<div class="arf_popup_container_footer">
					<button type="button" class="arf_popup_close_button" data-id="arf_optin_popup_button" ><?php echo esc_html__( 'OK', 'arforms-form-builder' ); ?></button>
				</div>
			</div>
		</div>
		
		<div class="arf_modal_overlay">

			<div id="arf_tracking_code_options_model" class="arf_popup_container arf_popup_container_other_option_model ">
				<div class="arf_popup_container_header"><?php echo esc_html__( 'Submit Tracking Script', 'arforms-form-builder' ); ?>
					<div class="arfpopupclosebutton arfmodalclosebutton" data-dismiss="arfmodal" data-id="arf_optin_popup_button">
						<svg width="30px" height="30px" viewBox="1 0 20 20"><g id="preview"><path fill-rule="evenodd" clip-rule="evenodd" fill="#262944" d="M10.702,10.909L6.453,6.66l-4.249,4.249L1.143,9.848l4.249-4.249L1.154,1.361l1.062-1.061l4.237,4.237l4.238-4.237l1.061,1.061L7.513,5.599l4.249,4.249L10.702,10.909z"></path></g></svg>
					</div>
				</div>

				<div class="arf_popup_content_container arf_other_options_container">

					<div class="arf_submit_action_tab_wrapper">

						<div class="arf_after_submission_tracking_code">
						<div style="<?php echo ( is_rtl() ) ? 'float: left;' : 'float: right;'; ?>">
							<a  target="_blank" title="Help" class="fas fa-life-ring arf_adminhelp_icon arfhelptip tipso_style" data-tipso="Help" onclick="arf_help_doc_fun('arf_submit_tracking_options');"></a>
						</div>
							 <span class="arf_hidden_field_title arf_submission-tracking-title"><?php echo esc_html__( 'After Submission Tracking Script', 'arforms-form-builder' ); ?></span>
							<div class="arftablerow prevent_duplicate_message_box prevent_duplicate_box arfsubmissiontrackingcontainer">
								<div class="arfcolumnleft arfsettingsubtitle"><?php echo esc_html__( 'Enter After submission tracking script', 'arforms-form-builder' ); ?>&nbsp;(<?php echo esc_html__( 'Example: Google Tracking Code', 'arforms-form-builder' ); ?>)</div>
								<div class="arfcolumnright arf_pre_dup_msg_width">
									<div class="arflite-tracking-script">&lt;script type="text/javascript"&gt;</div>
									<textarea rows="10" id="arf_submission_tracking_code" name="options[arf_sub_track_code]" class="txtmodal1 auto_responder_webform_code_area arf_submission_tracking_code"><?php echo( isset( $values['arf_sub_track_code'] ) && $values['arf_sub_track_code'] != '' ) ? esc_attr( rawurldecode( stripslashes_deep( $values['arf_sub_track_code'] ) ) ) : ''; ?></textarea><br />
									<div class="arferrmessage display-none-cls" id="arf_submission_tracking_code"><?php echo esc_html__( 'This field cannot be blank', 'arforms-form-builder' ); ?></div>
									<div class="arflite-tracking-script">&lt;/script&gt;</div>
								</div>
								<div class="arfsub_track-ins"><?php echo esc_html__( '(Do not insert script tag', 'arforms-form-builder' ) . '(&lt;script&gt;)' . esc_html__( ' inside code.)', 'arforms-form-builder' ); ?></div>
							</div>
						</div>

					</div>

				</div>
				<div class="arf_popup_container_footer">
					<button type="button" class="arf_popup_close_button" data-id="arf_optin_popup_button" ><?php echo esc_html__( 'OK', 'arforms-form-builder' ); ?></button>
				</div>
			</div>
		</div>

		<?php do_action( 'arflite_add_modal_in_editor', $values ); ?>

	</form>
</div>


<div class="arf_modal_overlay">
	<div id="arf_fontawesome_model" class="arf_popup_container arf_popup_container_fontawesome_model">
		<div class="arf_popup_container_header"><?php echo esc_html__( 'Font Awesome', 'arforms-form-builder' ); ?></div>
		<div class="arf_popup_content_container">
			<?php $is_rtl = ''; ?>
			<?php require ARFLITE_VIEWS_PATH . '/arflite_font_awesome.php'; ?>
		</div>
		<div class="arf_popup_container_footer arf_popup_container_footer_height_auto">
			<input type="hidden" id="icon_field_id">
			<input type="hidden" id="icon_field_type">
			<input type="hidden" id="icon_no_icon">
			<input type="hidden" id="icon_icon">
			<button type="button" class="arf_popup_close_button arf_popup_close_button_cancel"><?php echo esc_html__( 'Cancel', 'arforms-form-builder' ); ?></button>&nbsp;&nbsp;
			<button type="button" class="arf_popup_close_button arf_fainsideimge_ok_button" id="" ><?php echo esc_html__( 'OK', 'arforms-form-builder' ); ?></button>
		</div>
	</div>
</div>

<?php
if ( isset( $_REQUEST['isp'] ) && $_REQUEST['isp'] == 1 ) {
	?>

<div class="arf_modal_overlay">
	<?php $arflite_isp_chk = isset( $_REQUEST['isp'] ) ? intval( $_REQUEST['isp'] ) : 0; ?>
	<input type="hidden" id="open_new_form_div" value="<?php echo esc_attr($arflite_isp_chk); ?>" />
	<div id="new_form_model" class="arf_popup_container arf_popup_container_new_form">
		<?php require ARFLITE_VIEWS_PATH . '/arflite_new-selection-modal.php'; ?>
	</div>
</div>
	<?php
}
?>

<div>
</div>


<div class="arf_modal_overlay arf_whole_screen">
	<div id="form_previewmodal" class="arf_popup_container arf_hide_overflow">
		<div class="arf_preview_model_header">
			<div class="arf_preview_model_header_icons">
				<div onclick="arflitechangedevice('computer');" title="<?php echo esc_html__( 'Computer View', 'arforms-form-builder' ); ?>" class="arfdevicesbg arfhelptip arf_preview_model_device_icon"><div id="arfcomputer" class="arfdevices arfactive"><svg width="75px" height="60px" viewBox="-16 -14 75 60"><path xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd" fill="#ffffff" d="M40.561,28.591H24.996v2.996h8.107c0.779,0,1.434,0.28,1.434,1.059  c0,0.779-0.655,0.935-1.434,0.935H9.951c-0.779,0-1.435-0.156-1.435-0.935c0-0.778,0.656-1.059,1.435-1.059h8.045v-2.996H2.452  c-0.779,0-1.435-0.656-1.435-1.435V2.086c0-0.779,0.656-1.434,1.435-1.434h38.109c0.778,0,1.434,0.655,1.434,1.434v25.071  C41.995,27.936,41.339,28.591,40.561,28.591z M22.996,31.587v-2.996h-3v2.996H22.996z M39.995,2.642H3.017v23.895h36.978V2.642z"/></svg></div></div>
				<div onclick="arflitechangedevice('tablet');" title="<?php echo esc_html__( 'Tablet View', 'arforms-form-builder' ); ?>" class="arfdevicesbg arfhelptip arf_preview_model_device_icon"><div id="arftablet" class="arfdevices"><svg width="40px" height="60px" viewBox="-6 -15 40 60"><path xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd" fill="#ffffff" d="M23.091,33.642H4.088c-1.657,0-3-1.021-3-2.28V2.816  c0-1.259,1.343-2.28,3-2.28h19.003c1.657,0,3,1.021,3,2.28v28.546C26.091,32.622,24.749,33.642,23.091,33.642z M4.955,31.685h17.262  c1.035,0,1.875-0.638,1.875-1.425v-4.694H3.08v4.694C3.08,31.047,3.92,31.685,4.955,31.685z M24.092,4.002  c0-0.787-0.84-1.425-1.875-1.425H4.955c-1.035,0-1.875,0.638-1.875,1.425v1.563h21.012V4.002z M3.08,7.566v16h21.012v-16H3.08z   M13.618,26.551c1.09,0,1.974,0.896,1.974,2s-0.884,2-1.974,2c-1.09,0-1.974-0.896-1.974-2S12.527,26.551,13.618,26.551zz"/></svg></div></div>
				<div onclick="arflitechangedevice('mobile');" title="<?php echo esc_html__( 'Mobile View', 'arforms-form-builder' ); ?>" class="arfdevicesbg arfhelptip arf_preview_model_device_icon"><div id="arfmobile" class="arfdevices"><svg width="45px" height="60px" viewBox="-12 -15 45 60"><path xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd" fill="#ffffff" d="M17.894,33.726H3.452c-1.259,0-2.28-1.021-2.28-2.28V2.899  c0-1.259,1.021-2.28,2.28-2.28h14.442c1.259,0,2.28,1.021,2.28,2.28v28.546C20.174,32.705,19.153,33.726,17.894,33.726z   M18.18,4.086c0-0.787-0.638-1.425-1.425-1.425H4.585c-0.787,0-1.425,0.638-1.425,1.425v26.258c0,0.787,0.638,1.425,1.425,1.425  h12.169c0.787,0,1.425-0.638,1.425-1.425V4.086z M13.787,6.656H7.568c-0.252,0-0.456-0.43-0.456-0.959s0.204-0.959,0.456-0.959  h6.218c0.251,0,0.456,0.429,0.456,0.959S14.038,6.656,13.787,6.656z M10.693,25.635c1.104,0,2,0.896,2,2c0,1.105-0.895,2-2,2  c-1.105,0-2-0.895-2-2C8.693,26.53,9.588,25.635,10.693,25.635z"/></svg></div></div>
			</div>
			<div class="arf_popup_header_close_button" data-dismiss="arfmodal"><svg width="16px" height="16px" viewBox="0 0 12 12"><path fill-rule="evenodd" clip-rule="evenodd" fill="#ffffff" d="M10.702,10.909L6.453,6.66l-4.249,4.249L1.143,9.848l4.249-4.249L1.154,1.361l1.062-1.061l4.237,4.237l4.238-4.237l1.061,1.061L7.513,5.599l4.249,4.249L10.702,10.909z"></path></svg></div>
		</div>
		<div class="arfmodal-body arf_hide_overflow arflite-clear-float arflite_padding0">
			<div class="iframe_loader arf_editor_preview_loader" align="center"><?php echo ARFLITE_LOADER_ICON; //phpcs:ignore ?></div>
			<iframe id="arfdevicepreview" name="arf_preview_frame" src="" frameborder="0" height="100%" width="100%"></iframe>
		</div>
	</div>
</div>

<div class="arf_modal_overlay">
	<div id="arf_other_css_expanded_model"  class="arf_popup_container arf_popup_container_other_css_expanded_model arf_hide_overflow">
		<div class="arf_other_css_expanded_model_header">
			<span><?php echo esc_html__( 'Custom CSS', 'arforms-form-builder' ); ?></span>
			<div class="arf_other_css_expanded_add_element_btn" id="arf_expand_css_code_element_button">
				<span><?php echo esc_html__( 'Add CSS Elements', 'arforms-form-builder' ); ?></span>
				<i class="fas fa-caret-down"></i>
				<ul class="arf_custom_css_cloud_list_wrapper">
				<?php
				global $arflite_custom_css_array;
				foreach ( $arflite_custom_css_array as $key => $value ) {
					?>
						<li data-target="expanded" class="arf_custom_css_cloud_list_item <?php echo ( isset( $values[ $key ] ) && $values[ $key ] != '' ) ? 'arfactive' : ''; ?>" id="<?php echo esc_attr( $value['onclick_1'] ); ?>"><span><?php echo esc_html( $value['label_title'] ); ?></span></li>
						<?php
				}
				?>
				</ul>
			</div>
		</div>
		<div class="arf_other_css_expanded_model_container">
		<textarea id="arf_other_css_expanded_textarea"></textarea>
		</div>
		<div class="arf_popup_container_footer">
			<button type="button" class="arf_popup_close_button" id="arf_css_expanded_model_btn"><?php echo esc_html__( 'OK', 'arforms-form-builder' ); ?></button>
		</div>
	</div>
</div>
<?php require_once ARFLITE_VIEWS_PATH . '/arflite_field_option_popup.php'; ?>

<?php require_once ARFLITE_VIEWS_PATH . '/arflite_field_values_popup.php'; ?>

<?php require ARFLITE_VIEWS_PATH . '/arflite_new_field_array.php'; ?>

<?php do_action( 'arforms_quick_help_links' ); ?>