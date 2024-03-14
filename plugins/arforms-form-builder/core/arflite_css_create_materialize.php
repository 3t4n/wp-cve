<?php

if ( ! isset( $saving ) ) {
	header( 'Content-type: text/css' );
}

if ( ! isset( $is_form_save ) ) {
	$is_form_save = false;
}

if ( ! isset( $is_prefix_suffix_enable ) ) {
	$is_prefix_suffix_enable = false;
}
if ( ! isset( $is_checkbox_img_enable ) ) {
	$is_checkbox_img_enable = false;
}
if ( ! isset( $is_radio_img_enable ) ) {
	$is_radio_img_enable = false;
}

if ( ! isset( $arflite_preview ) ) {
	$arflite_preview = false;
}

$form_id = isset( $form_id ) ? $form_id : '';

global $wpdb, $arflitesettingcontroller, $arfliteformcontroller, $ARFLiteMdlDb, $arflitefieldhelper, $tbl_arf_fields, $arformsmain;

foreach ( $new_values as $k => $v ) {

	if ( ( preg_match( '/color/', $k ) or in_array( $k, array( 'arferrorbgsetting', 'arferrorbordersetting', 'arferrortextsetting' ) ) ) && ! in_array( $k, array( 'arfcheckradiocolor' ) ) ) {
		if ( strpos( $v, '#' ) === false ) {
			$new_values[ $k ] = '#' . $v;
		} else {
			$new_values[ $k ] = $v;
		}
	} else {
		$new_values[ $k ] = $v;
	}
}

/**Basic Styling Options */
	$arf_mainstyle = ! empty( $new_values['arfinputstyle'] ) ? $new_values['arfinputstyle'] : '';

	/** color related variables */
	$form_bg_color            = ! empty( $new_values['arfmainformbgcolorsetting'] ) ? sanitize_text_field( $new_values['arfmainformbgcolorsetting'] ) : '';
	$form_title_color         = isset( $new_values['arfmainformtitlecolorsetting'] ) ? sanitize_text_field( $new_values['arfmainformtitlecolorsetting'] ) : '';
	$form_border_color        = isset( $new_values['arfmainfieldsetcolor'] ) ? sanitize_text_field( $new_values['arfmainfieldsetcolor'] ) : '';
	$form_border_shadow_color = isset( $new_values['arfmainformbordershadowcolorsetting'] ) ? sanitize_text_field( $new_values['arfmainformbordershadowcolorsetting'] ) : '';

	$base_color               = isset( $new_values['arfmainbasecolor'] ) ? sanitize_text_field( $new_values['arfmainbasecolor'] ) : '';
	$field_text_color         = isset( $new_values['text_color'] ) ? $new_values['text_color'] : '';
	$field_border_color       = isset( $new_values['border_color'] ) ? $new_values['border_color'] : '';
	$field_bg_color           = isset( $new_values['bg_color'] ) ? $new_values['bg_color'] : '';
	$field_focus_bg_color     = isset( $new_values['arfbgactivecolorsetting'] ) ? sanitize_text_field( $new_values['arfbgactivecolorsetting'] ) : '';
	$field_error_bg_color     = isset( $new_values['arferrorbgcolorsetting'] ) ? sanitize_text_field( $new_values['arferrorbgcolorsetting'] ) : '';
	$field_focus_border_color = ! empty( $new_values['arfborderactivecolorsetting'] ) ? sanitize_text_field( $new_values['arfborderactivecolorsetting'] ) : '#fff';
	$field_label_txt_color    = isset( $new_values['label_color'] ) ? sanitize_text_field( $new_values['label_color'] ) : '';
	$prefix_suffix_bg_color   = isset( $new_values['prefix_suffix_bg_color'] ) ? str_replace( '##', '#', $new_values['prefix_suffix_bg_color'] ) : '';
	$prefix_suffix_icon_color = isset( $new_values['prefix_suffix_icon_color'] ) ? sanitize_text_field( $new_values['prefix_suffix_icon_color'] ) : '';

	$arf_date_picker_text_color = isset( $new_values['arfdatepickertextcolorsetting'] ) ? sanitize_text_field( $new_values['arfdatepickertextcolorsetting'] ) : '#46484d';

	$submit_text_color     = isset( $new_values['arfsubmittextcolorsetting'] ) ? sanitize_text_field( $new_values['arfsubmittextcolorsetting'] ) : '';
	$submit_bg_color       = isset( $new_values['submit_bg_color'] ) ? sanitize_text_field( $new_values['submit_bg_color'] ) : '';
	$submit_bg_color_hover = isset( $new_values['arfsubmitbuttonbgcolorhoversetting'] ) ? str_replace( '##', '#', sanitize_text_field( $new_values['arfsubmitbuttonbgcolorhoversetting'] ) ) : '';
	$submit_border_color   = isset( $new_values['arfsubmitbordercolorsetting'] ) ? sanitize_text_field( $new_values['arfsubmitbordercolorsetting'] ) : '';
	$submit_shadow_color   = isset( $new_values['arfsubmitshadowcolorsetting'] ) ? str_replace( '##', '#', sanitize_text_field( $new_values['arfsubmitshadowcolorsetting'] ) ) : '';

	$success_bg_color     = isset( $new_values['arfsucessbgcolorsetting'] ) ? sanitize_text_field( $new_values['arfsucessbgcolorsetting'] ) : '';
	$success_border_color = isset( $new_values['arfsucessbordercolorsetting'] ) ? sanitize_text_field( $new_values['arfsucessbordercolorsetting'] ) : '';
	$success_text_color   = isset( $new_values['arfsucesstextcolorsetting'] ) ? sanitize_text_field( $new_values['arfsucesstextcolorsetting'] ) : '';

	$error_bg_color     = isset( $new_values['arfformerrorbgcolorsettings'] ) ? sanitize_text_field( $new_values['arfformerrorbgcolorsettings'] ) : '';
	$error_border_color = isset( $new_values['arfformerrorbordercolorsettings'] ) ? sanitize_text_field( $new_values['arfformerrorbordercolorsettings'] ) : '';
	$error_txt_color    = isset( $new_values['arfformerrortextcolorsettings'] ) ? sanitize_text_field( $new_values['arfformerrortextcolorsettings'] ) : '';

	$arferrorstylecolor     = isset( $new_values['arfvalidationbgcolorsetting'] ) ? sanitize_text_field( $new_values['arfvalidationbgcolorsetting'] ) : '';
	$arferrorstylecolorfont = isset( $new_values['arfvalidationtextcolorsetting'] ) ? sanitize_text_field( $new_values['arfvalidationtextcolorsetting'] ) : '';

	/** color related variables */

	/** Fonts Related variables */

	$arf_title_font_family = isset( $new_values['arftitlefontfamily'] ) ? sanitize_text_field( $new_values['arftitlefontfamily'] ) : '';
	$arf_title_font_size   = isset( $new_values['form_title_font_size'] ) ? intval( $new_values['form_title_font_size'] ) . 'px' : '24px';

	$arf_title_font_weight    = isset( $new_values['check_weight_form_title'] ) ? sanitize_text_field( $new_values['check_weight_form_title'] ) : 'bold';
	$arf_title_font_style_arr = explode( ',', $arf_title_font_weight );
	$arf_title_font_style_str = '';
if ( in_array( 'bold', $arf_title_font_style_arr ) ) {
	$arf_title_font_style_str .= 'font-weight:bold;';
} else {
	$arf_title_font_style_str .= 'font-weight:normal;';
}

if ( in_array( 'italic', $arf_title_font_style_arr ) ) {
	$arf_title_font_style_str .= 'font-style:bold;';
} else {
	$arf_title_font_style_str .= 'font-style:normal;';
}

if ( in_array( 'underline', $arf_title_font_style_arr ) ) {
	$arf_title_font_style_str .= 'text-decoration:underline;';
} elseif ( in_array( 'strikethrough', $arf_title_font_style_arr ) ) {
	$arf_title_font_style_str .= 'text-decoration:line-through;';
} else {
	$arf_title_font_style_str .= 'text-decoration:none;';
}

	$arf_label_font_family    = isset( $new_values['font'] ) ? sanitize_text_field( $new_values['font'] ) : '';
	$arf_label_font_size      = isset( $new_values['font_size'] ) ? intval( $new_values['font_size'] ) : '';
	$arf_label_font_weight    = isset( $new_values['weight'] ) ? sanitize_text_field( $new_values['weight'] ) : 'normal';
	$arf_label_font_style_arr = explode( ',', $arf_label_font_weight );
	$arf_label_font_style_str = '';
if ( in_array( 'bold', $arf_label_font_style_arr ) ) {
	$arf_label_font_style_str .= 'font-weight:bold;';
} else {
	$arf_label_font_style_str .= 'font-weight:normal;';
}

if ( in_array( 'italic', $arf_label_font_style_arr ) ) {
	$arf_label_font_style_str .= 'font-style:bold;';
} else {
	$arf_label_font_style_str .= 'font-style:normal;';
}

if ( in_array( 'underline', $arf_label_font_style_arr ) ) {
	$arf_label_font_style_str .= 'text-decoration:underline;';
} elseif ( in_array( 'strikethrough', $arf_label_font_style_arr ) ) {
	$arf_label_font_style_str .= 'text-decoration:line-through;';
} else {
	$arf_label_font_style_str .= 'text-decoration:none;';
}

	$arf_input_font_family = isset( $new_values['check_font'] ) ? sanitize_text_field( $new_values['check_font'] ) : '';
	$arf_input_font_size   = isset( $new_values['field_font_size'] ) ? intval( $new_values['field_font_size'] ) : '';




	$arf_input_font_weight    = isset( $new_values['check_weight'] ) ? sanitize_text_field( $new_values['check_weight'] ) : 'normal';
	$arf_input_font_style_arr = explode( ',', $arf_input_font_weight );
	$arf_input_font_style_str = '';
if ( in_array( 'bold', $arf_input_font_style_arr ) ) {
	$arf_input_font_style_str .= 'font-weight:bold;';
} else {
	$arf_input_font_style_str .= 'font-weight:normal;';
}

if ( in_array( 'italic', $arf_input_font_style_arr ) ) {
	$arf_input_font_style_str .= 'font-style:bold;';
} else {
	$arf_input_font_style_str .= 'font-style:normal;';
}

if ( in_array( 'underline', $arf_input_font_style_arr ) ) {
	$arf_input_font_style_str .= 'text-decoration:underline;';
} elseif ( in_array( 'strikethrough', $arf_input_font_style_arr ) ) {
	$arf_input_font_style_str .= 'text-decoration:line-through;';
} else {
	$arf_input_font_style_str .= 'text-decoration:none;';
}

	$arf_submit_btn_font_family = isset( $new_values['arfsubmitfontfamily'] ) ? sanitize_text_field( $new_values['arfsubmitfontfamily'] ) : '';
	$arf_submit_btn_font_size   = isset( $new_values['arfsubmitbuttonfontsizesetting'] ) ? intval( $new_values['arfsubmitbuttonfontsizesetting'] ) : '';
	$arf_submit_btn_font_weight = isset( $new_values['arfsubmitweightsetting'] ) ? sanitize_text_field( $new_values['arfsubmitweightsetting'] ) : 'normal';
	$arf_submit_font_style_arr  = explode( ',', $arf_submit_btn_font_weight );
	$arf_submit_font_style_str  = '';
if ( in_array( 'bold', $arf_submit_font_style_arr ) ) {
	$arf_submit_font_style_str .= 'font-weight:bold;';
} else {
	$arf_submit_font_style_str .= 'font-weight:normal;';
}

if ( in_array( 'italic', $arf_submit_font_style_arr ) ) {
	$arf_submit_font_style_str .= 'font-style:bold;';
} else {
	$arf_submit_font_style_str .= 'font-style:normal;';
}

if ( in_array( 'underline', $arf_submit_font_style_arr ) ) {
	$arf_submit_font_style_str .= 'text-decoration:underline;';
} elseif ( in_array( 'strikethrough', $arf_submit_font_style_arr ) ) {
	$arf_submit_font_style_str .= 'text-decoration:line-through;';
} else {
	$arf_submit_font_style_str .= 'text-decoration:none;';
}

	$arf_validation_font_family = isset( $new_values['error_font'] ) ? sanitize_text_field( $new_values['error_font'] ) : '';
	$arf_validation_font_size   = isset( $new_values['arffontsizesetting'] ) ? intval( $new_values['arffontsizesetting'] ) . 'px' : '20px;';

	/** Fonts Related variables */

	/** Form width Variables */

	$form_width      = isset( $new_values['arfmainformwidth'] ) ? intval( $new_values['arfmainformwidth'] ) : '';
	$form_width_tablet = isset($new_values['arfmainformwidth_tablet']) ? $new_values['arfmainformwidth_tablet'] : '';
    $form_width_mobile = isset($new_values['arfmainformwidth_mobile']) ? $new_values['arfmainformwidth_mobile'] : '';
	$form_width_unit = isset( $new_values['form_width_unit'] ) ? sanitize_text_field( $new_values['form_width_unit'] ) : '';
	$form_width_unit_tablet = isset( $new_values['form_width_unit_tablet'] ) ? $new_values['form_width_unit_tablet'] : '';
    $form_width_unit_mobile = isset( $new_values['arf_width_unit_mobile'] ) ? $new_values['arf_width_unit_mobile'] : '';

	/** Form width Variables */

	/** success message position */
	$success_message_position = isset( $new_values['arfsuccessmsgposition'] ) ? sanitize_text_field( $new_values['arfsuccessmsgposition'] ) : 'top';
	/** success message position */

	/**Validation Message Style */

	$arf_error_style             = isset( $new_values['arferrorstyle'] ) ? sanitize_text_field( $new_values['arferrorstyle'] ) : '';
	$arf_error_style_position    = isset( $new_values['arferrorstyleposition'] ) ? sanitize_text_field( $new_values['arferrorstyleposition'] ) : '';
	$arf_standard_error_position = isset( $new_values['arfstandarderrposition'] ) ? sanitize_text_field( $new_values['arfstandarderrposition'] ) : 'relative';

	/**Validation Message Style */

/**Basic Styling Options */

/** Advanced Form Options */

	/** Form title Options */

	$arf_form_title_alignment = isset( $new_values['arfformtitlealign'] ) ? sanitize_text_field( $new_values['arfformtitlealign'] ) : '';
	$arf_form_title_margin    = isset( $new_values['arfmainformtitlepaddingsetting'] ) ? sanitize_text_field( $new_values['arfmainformtitlepaddingsetting'] ) : '';

	/** Form title Options */

	/** Form Settings Options */
	$arf_form_alignment = isset( $new_values['form_align'] ) ? sanitize_text_field( $new_values['form_align'] ) : '';

	$arf_form_bg_image = isset( $new_values['arfmainform_bg_img'] ) ? esc_url_raw( $new_values['arfmainform_bg_img'] ) : '';

	$arf_form_bg_posx        = isset( $new_values['arf_bg_position_x'] ) ? sanitize_text_field( $new_values['arf_bg_position_x'] ) : 'center';
	$arf_form_bg_posy        = isset( $new_values['arf_bg_position_y'] ) ? sanitize_text_field( $new_values['arf_bg_position_y'] ) : 'center';
	$arf_form_bg_posx_custom = isset( $new_values['arf_bg_position_input_x'] ) ? intval( $new_values['arf_bg_position_input_x'] ) : '';
	$arf_form_bg_posy_custom = isset( $new_values['arf_bg_position_input_y'] ) ? intval( $new_values['arf_bg_position_input_y'] ) : '';

	$arf_form_padding = isset( $new_values['arfmainfieldsetpadding'] ) ? sanitize_text_field( $new_values['arfmainfieldsetpadding'] ) : '';
	$arf_form_padding_tablet = isset( $new_values['arfmainfieldsetpadding_tablet'] ) ? sanitize_text_field($new_values['arfmainfieldsetpadding_tablet']) : '';
    $arf_form_padding_mobile = isset( $new_values['arfmainfieldsetpadding_mobile'] ) ? sanitize_text_field($new_values['arfmainfieldsetpadding_mobile']) : '';

	/** Form Settings Options */

	/** Form Border Options */
	$arf_form_border_type   = isset( $new_values['form_border_shadow'] ) ? sanitize_text_field( $new_values['form_border_shadow'] ) : 'border';
	$arf_form_border_width  = ! empty( $new_values['fieldset'] ) ? sanitize_text_field( $new_values['fieldset'] ) . 'px' : '0';
	$arf_form_border_radius = ! empty( $new_values['arfmainfieldsetradius'] ) ? intval( $new_values['arfmainfieldsetradius'] ) . 'px' : '0';
	/** Form Border Options */

	/** Form Opacity Options */
	$arf_form_opacity = isset( $new_values['arfmainform_opacity'] ) ? floatval( $new_values['arfmainform_opacity'] ) : '';
	/** Form Opacity Options */

/** Advanced Form Options */

/** Input Field Options */

	/** Label Options */
	$arf_label_position = isset( $new_values['position'] ) ? sanitize_text_field( $new_values['position'] ) : '';
	$arf_label_align    = isset( $new_values['align'] ) ? sanitize_text_field( $new_values['align'] ) : '';
	$arf_label_width    = isset( $new_values['width'] ) ? sanitize_text_field( $new_values['width'] ) : '';
	$arf_hide_label     = isset( $new_values['hide_labels'] ) ? sanitize_text_field( $new_values['hide_labels'] ) : '';
	/** Label Options */

	/** Input Field Description Options */
	$description_font_size = isset( $new_values['arfdescfontsizesetting'] ) ? intval( $new_values['arfdescfontsizesetting'] ) : '';
	$description_align     = isset( $new_values['arfdescalighsetting'] ) ? sanitize_text_field( $new_values['arfdescalighsetting'] ) : '';
	/** Input Field Description Options */

	/** Input Field Option */

	$arf_input_field_width      = isset( $new_values['field_width'] ) ? sanitize_text_field( $new_values['field_width'] ) : '';
	$arf_input_field_width_unit = isset( $new_values['field_width_unit'] ) ? sanitize_text_field( $new_values['field_width_unit'] ) : '';
	$arf_input_field_width_tablet = isset( $new_values['field_width_tablet'] ) ? $new_values['field_width_tablet'] : '';
    $arf_input_field_width_unit_tablet = isset( $new_values['field_width_unit_tablet'] ) ? $new_values['field_width_unit_tablet'] : '';

    $arf_input_field_width_mobile = isset( $new_values['field_width_mobile'] ) ? $new_values['field_width_mobile'] : '';
    $arf_input_field_width_unit_mobile = isset( $new_values['field_width_unit_mobile'] ) ? $new_values['field_width_unit_mobile'] : '';
	$arflite_text_direction     = isset( $new_values['text_direction'] ) ? sanitize_text_field( $new_values['text_direction'] ) : '';
	$arf_input_field_direction  = ( $arflite_text_direction == 0 ) ? 'rtl' : 'ltr';
	$arf_input_field_text_align = ( $arflite_text_direction == 0 ) ? 'right' : 'left';
	$arfmainfield_opacity       = isset( $new_values['arfmainfield_opacity'] ) ? intval( $new_values['arfmainfield_opacity'] ) : '';
	$arf_required_indicator     = isset( $new_values['arf_req_indicator'] ) ? intval( $new_values['arf_req_indicator'] ) : '0';
	$field_margin               = empty( $new_values['arffieldmarginssetting'] ) ? '0' : intval( $new_values['arffieldmarginssetting'] ) . 'px';
	$placeholder_opacity        = isset( $new_values['arfplaceholder_opacity'] ) ? floatval( $new_values['arfplaceholder_opacity'] ) : '';
	$arf_field_inner_padding    = isset( $new_values['arffieldinnermarginssetting'] ) ? sanitize_text_field( $new_values['arffieldinnermarginssetting'] ) : 0;
	$field_border_width         = empty( $new_values['arffieldborderwidthsetting'] ) ? '0' : intval( $new_values['arffieldborderwidthsetting'] ) . 'px';
	$field_border_radius        = 0;
	$field_border_style         = isset( $new_values['arffieldborderstylesetting'] ) ? sanitize_text_field( $new_values['arffieldborderstylesetting'] ) : '';

	$fieldpadding   = explode( ' ', $arf_field_inner_padding );
	$fieldpadding_1 = $fieldpadding[0];
	$fieldpadding_1 = str_replace( 'px', '', $fieldpadding_1 );
	$fieldpadding_2 = 0;
if ( count( $fieldpadding ) > 1 ) {
	$fieldpadding_2 = $fieldpadding[1];
	$fieldpadding_2 = str_replace( 'px', '', $fieldpadding_2 );
}


	$field_ptop  = $fieldpadding_1;
	$field_pleft = $fieldpadding_2;

	/** Input Field Option */

	/** Checkbox/Radio Style */
	$arfcheck_style_name = isset( $new_values['arfcheckradiostyle'] ) ? sanitize_text_field( $new_values['arfcheckradiostyle'] ) : '';
	/** Checkbox/Radio Style */

	/** Submit Button Option */

	$submit_align         = isset( $new_values['arfsubmitalignsetting'] ) ? sanitize_text_field( $new_values['arfsubmitalignsetting'] ) : '';
	$submit_width         = empty( $new_values['arfsubmitbuttonwidthsetting'] ) ? '' : sanitize_text_field( $new_values['arfsubmitbuttonwidthsetting'] ) . 'px';
	$submit_width_tablet         = empty( $new_values['arfsubmitbuttonwidthsetting_tablet'] ) ? '' : sanitize_text_field( $new_values['arfsubmitbuttonwidthsetting_tablet'] ) . 'px';
	$submit_width_mobile         = empty( $new_values['arfsubmitbuttonwidthsetting_mobile'] ) ? '' : sanitize_text_field( $new_values['arfsubmitbuttonwidthsetting_mobile'] ) . 'px';
	$submit_auto_width    = ( empty( $new_values['arfsubmitautowidth'] ) || $new_values['arfsubmitautowidth'] < 100 ) ? '100' : intval( $new_values['arfsubmitautowidth'] );
	$submit_height        = ( $new_values['arfsubmitbuttonheightsetting'] == '' ) ? '36' : intval( $new_values['arfsubmitbuttonheightsetting'] );
	$arfsubmitbuttonstyle = isset( $new_values['arfsubmitbuttonstyle'] ) ? sanitize_text_field( $new_values['arfsubmitbuttonstyle'] ) : 'border';
if ( $is_form_save ) {
	$arfsubmitbuttonstyle = 'border reverse border flat';
}
	$submit_margin = empty( $new_values['arfsubmitbuttonmarginsetting'] ) ? '0' : sanitize_text_field( $new_values['arfsubmitbuttonmarginsetting'] );

	$submit_bg_img       = isset( $new_values['submit_bg_img'] ) ? esc_url_raw( $new_values['submit_bg_img'] ) : '';
	$submit_hover_bg_img = isset( $new_values['submit_hover_bg_img'] ) ? esc_url_raw( $new_values['submit_hover_bg_img'] ) : '';

	$submit_border_width   = ( $new_values['arfsubmitborderwidthsetting'] == '' ) ? '0px' : intval( $new_values['arfsubmitborderwidthsetting'] ) . 'px';
	$submit_border_radius  = ( $new_values['arfsubmitborderradiussetting'] == '' ) ? '0px' : intval( $new_values['arfsubmitborderradiussetting'] ) . 'px';
	$submit_xoffset_shadow = ( $new_values['arfsubmitboxxoffsetsetting'] == '' ) ? '0px' : intval( $new_values['arfsubmitboxxoffsetsetting'] ) . 'px';
	$submit_yoffset_shadow = ( $new_values['arfsubmitboxyoffsetsetting'] == '' ) ? '0px' : intval( $new_values['arfsubmitboxyoffsetsetting'] ) . 'px';
	$submit_blur_shadow    = ( $new_values['arfsubmitboxblursetting'] == '' ) ? '0px' : intval( $new_values['arfsubmitboxblursetting'] ) . 'px';
	$submit_spread_shadow  = ( $new_values['arfsubmitboxshadowsetting'] == '' ) ? '0px' : intval( $new_values['arfsubmitboxshadowsetting'] ) . 'px';

/** Submit Button Option */

$arf_css_character_set = $arformsmain->arforms_get_settings('arf_css_character_set','general_settings');
$character_set = !empty( $arf_css_character_set ) ? json_decode($arf_css_character_set,true) : array();

$subset       = count( $character_set ) > 0 ? '&subset=' . implode( ',', $character_set ) : '';
$swap_display = '&display=swap';

$loaded_gfonts = array(
	'Arial',
	'Helvetica',
	'sans-serif',
	'Lucida Grande',
	'Lucida Sans Unicode',
	'Tahoma',
	'Times New Roman',
	'Courier New',
	'Verdana',
	'Geneva',
	'Courier',
	'Monospace',
	'Times',
	'inherit',
);

if ( is_ssl() || $arfssl == 1 ) {
	$googlefontbaseurl = 'https://fonts.googleapis.com/css?family=';
} else {
	$googlefontbaseurl = 'http://fonts.googleapis.com/css?family=';
}

$arf_form_cls_prefix                            = "#arffrm_{$form_id}_container";
$arf_form_cls_prefix_without_material_container = "#arffrm_{$form_id}_container";
$arf_checkbox_not_admin                         = '';
$arf_prefix_cls                                 = '.arf_prefix';
$arf_suffix_cls                                 = '.arf_suffix';
$arf_prefix_suffix_wrapper_cls                  = '.arf_prefix_suffix_wrapper';
$input_fields                                   = $arflitefieldhelper->arflite_input_field_keys();
$other_fields                                   = $arflitefieldhelper->arflite_other_fields_keys();
$loaded_field                                   = array_merge( $input_fields, $other_fields );

if ( ! empty( $is_form_save ) && true == $arf_form_cls_prefix ) {
	$arf_form_cls_prefix           = ".arflite_main_div_{$form_id} ";
	$arf_prefix_cls                = '.arf_editor_prefix_icon';
	$arf_suffix_cls                = '.arf_editor_suffix_icon';
	$arf_prefix_suffix_wrapper_cls = '.arf_editor_prefix_suffix_wrapper';
	$is_prefix_suffix_enable       = true;
	$is_checkbox_img_enable        = true;
	$is_radio_img_enable           = true;
	$arf_hide_label                = true;
	$arf_checkbox_not_admin        = ':not(.arf_enable_radio_image_editor)';
} else {

	$arf_form_cls_prefix .= ' .arf_materialize_form';
	if ( ! empty( $arf_title_font_family ) && ! in_array( $arf_title_font_family, $loaded_gfonts ) ) {

		$title_font_family_gurl = $googlefontbaseurl . urlencode( $arf_title_font_family ) . $subset . $swap_display;
		echo '@import url(' . esc_url( $title_font_family_gurl ) . ');';

		array_push( $loaded_gfonts, $arf_title_font_family );
	}

	if ( ! empty( $arf_label_font_family ) && ! in_array( $arf_label_font_family, $loaded_gfonts ) ) {

		$label_font_family_gurl = $googlefontbaseurl . urlencode( $arf_label_font_family ) . $subset . $swap_display;
		echo '@import url(' . esc_url( $label_font_family_gurl ) . ');';

		array_push( $loaded_gfonts, $arf_label_font_family );
	}

	if ( ! empty( $arf_input_font_family ) && ! in_array( $arf_input_font_family, $loaded_gfonts ) ) {

		$input_font_family_gurl = $googlefontbaseurl . urlencode( $arf_input_font_family ) . $subset . $swap_display;
		echo '@import url(' . esc_url( $input_font_family_gurl ) . ');';

		array_push( $loaded_gfonts, $arf_input_font_family );
	}

	if ( ! empty( $arf_submit_btn_font_family ) && ! in_array( $arf_submit_btn_font_family, $loaded_gfonts ) ) {

		$submit_font_family_gurl = $googlefontbaseurl . urlencode( $arf_submit_btn_font_family ) . $subset . $swap_display;
		echo '@import url(' . esc_url( $submit_font_family_gurl ) . ');';

		array_push( $loaded_gfonts, $arf_submit_btn_font_family );
	}

	if ( ! empty( $arf_validation_font_family ) && ! in_array( $arf_validation_font_family, $loaded_gfonts ) ) {

		$validation_font_family_gurl = $googlefontbaseurl . urlencode( $arf_validation_font_family ) . $subset . $swap_display;
		echo '@import url(' . esc_url( $validation_font_family_gurl ) . ');';

		array_push( $loaded_gfonts, $arf_validation_font_family );
	}
}

$common_field_type_styling = array( 'text', 'email', 'phone', 'tel', 'number', 'date', 'time', 'url', 'image' );

/** Form Level Styling */

if ( false == $is_form_save ) {
	echo '#arffrm_' . esc_html( $form_id ) . '_container{ max-width:' . esc_html( $form_width ) . esc_html( $form_width_unit ) . '; margin:0 auto;}';

	if(!empty( $form_width_tablet )){
		echo '.arfdevicetablet #arffrm_' . esc_html( $form_id ) . '_container{ max-width:' . esc_html( $form_width_tablet ) . esc_html( $form_width_unit_tablet ) . '; margin:0 auto;}';
        echo '@media all and (max-width:768px){';
            echo '#arffrm_' . esc_html( $form_id ) . '_container{ max-width:'. esc_html($form_width_tablet) .esc_html($form_width_unit_tablet).'; margin:0 auto;}';
        echo '}';
    }
    if( !empty($form_width_mobile)){
        echo '.arfdevicemobile #arffrm_' . esc_html( $form_id ) . '_container{  max-width:' . esc_html( $form_width_mobile ) . esc_html( $form_width_unit_mobile ) . '!important; margin:0 auto;}';
        echo '@media all and (max-width:576px){';
            echo '#arffrm_' . esc_html( $form_id ) . '_container{  max-width:'. esc_html($form_width_mobile) .esc_html($form_width_unit_mobile). '!important; margin:0 auto;}';
        echo '}';
    }

} else {

	echo '.arflite_main_div_' . esc_html( $form_id ) . '{ max-width:' . esc_html( $form_width ) . esc_html($form_width_unit) . '; margin:0 auto;}';

	if(!empty( $form_width_tablet )){
		echo '.arfdevicetablet .arflite_main_div_' . esc_html( $form_id ) . '{ max-width:' . esc_html( $form_width_tablet ) . esc_html( $form_width_unit_tablet ) . '; margin:0 auto;}';
        echo '@media all and (max-width:768px){';
            echo '.arflite_main_div_' . esc_html( $form_id ) . '{ max-width:'.esc_html($form_width_tablet). esc_html($form_width_unit_tablet).'; margin:0 auto;}';
        echo '}';
    }
    if( !empty($form_width_mobile)){
        echo '.arfdevicemobile .arflite_main_div_' . esc_html( $form_id ) . '{   max-width:' . esc_html( $form_width_mobile ) . esc_html( $form_width_unit_mobile ) . '!important; margin:0 auto;}';
        echo '@media all and (max-width:576px){';
            echo '.arflite_main_div_' . esc_html( $form_id ) . '{  max-width:' .esc_html($form_width_mobile) .esc_html($form_width_unit_mobile). '!important; margin:0 auto;}';
        echo '}';
    }

}

echo esc_html( $arf_form_cls_prefix_without_material_container ) . ' *{';
	echo 'box-sizing:border-box;';
	echo '-webkit-box-sizing:border-box;';
	echo '-o-box-sizing:border-box;';
	echo '-moz-box-sizing:border-box;';
echo '}';

if ( false == $is_form_save ) {
	echo esc_html( $arf_form_cls_prefix_without_material_container ) . ' form{ text-align:' . esc_html( $arf_form_alignment ) . '; }';
} else {
	echo esc_html( $arf_form_cls_prefix ) . '{ text-align:' . esc_html( $arf_form_alignment ) . '; }';
}

echo esc_html( $arf_form_cls_prefix ) . '.arf_fieldset{';
	$frm_bg_color = ! empty( $form_bg_color ) ? $form_bg_color : '0,0,0';
	$frm_bg_color = $arflitesettingcontroller->arflitehex2rgb( $frm_bg_color );
if ( ! empty( $arf_form_bg_image ) ) {
	echo 'background:rgba(' . esc_html( $frm_bg_color ) . ',' . esc_html( $arf_form_opacity ) . ') url(' . esc_url( $arf_form_bg_image ) . ');';
	if ( 'px' == $arf_form_bg_posx ) {
		echo 'background-position-x:' . esc_html( $arf_form_bg_posx_custom ) . 'px;';
	} else {
		echo 'background-position-x:' . esc_html( $arf_form_bg_posx ) . ';';
	}
	if ( 'px' == $arf_form_bg_posy ) {
		echo 'background-position-y:' . esc_html( $arf_form_bg_posy_custom ) . 'px;';
	} else {
		echo 'background-position-y:' . esc_html( $arf_form_bg_posy ) . ';';
	}
	echo 'background-repeat: no-repeat;';
} else {
	echo 'background:rgba(' . esc_html( $frm_bg_color ) . ',' . esc_html( $arf_form_opacity ) . ');';
}
	echo 'border:' . esc_html( $arf_form_border_width ) . ' solid ' . esc_html( $form_border_color ) . ';';
	echo 'padding:' . esc_html( $arf_form_padding ) . ';';
	echo 'border-radius:' . esc_html( $arf_form_border_radius ) . ';';
	echo '-webkit-border-radius:' . esc_html( $arf_form_border_radius ) . ';';
	echo '-o-border-radius:' . esc_html( $arf_form_border_radius ) . ';';
	echo '-moz-border-radius:' . esc_html( $arf_form_border_radius ) . ';';
if ( 'shadow' == $arf_form_border_type ) {
	echo '-moz-box-shadow:0px 0px 7px 2px ' . esc_html( $form_border_shadow_color ) . ';
        -o-box-shadow:0px 0px 7px 2px ' . esc_html( $form_border_shadow_color ) . ';
        -webkit-box-shadow:0px 0px 7px 2px ' . esc_html( $form_border_shadow_color ) . ';
        box-shadow:0px 0px 7px 2px ' . esc_html( $form_border_shadow_color ) . ';';
} else {
	echo '-moz-box-shadow:none;-webkit-box-shadow:none;-o-box-shadow:none;box-shadow:none;';
}
echo '}';

if(!empty( $arf_form_padding_tablet ) && $arf_form_padding_tablet != 0 ){
    echo ".arfdevicetablet ".esc_html($arf_form_cls_prefix) . ".arf_fieldset{";
        echo "padding : ".esc_html($arf_form_padding_tablet).";";
    echo "}";
    echo "@media all and (max-width:768px){";
        echo esc_html($arf_form_cls_prefix) . ".arf_fieldset{";
            echo "padding: ".esc_html($arf_form_padding_tablet)."";
        echo "}";
    echo "}";
}
if( !empty($arf_form_padding_mobile) && $arf_form_padding_mobile != 0 ){

    echo ".arfdevicemobile ".esc_html($arf_form_cls_prefix) . ".arf_fieldset{";
        echo "padding : ".esc_html($arf_form_padding_mobile).";";
    echo "}";
    echo "@media all and (max-width:576px){";
        echo esc_html($arf_form_cls_prefix) . ".arf_fieldset{";
            echo "padding:".esc_html($arf_form_padding_mobile).";";
        echo "}";
    echo "}";
}

echo esc_html( $arf_form_cls_prefix ) . ' .arftitlecontainer{margin:' . esc_html( $arf_form_title_margin ) . '; text-align:' . esc_html( $arf_form_title_alignment ) . ';}';
echo esc_html( $arf_form_cls_prefix ) . ' .formtitle_style{';
	echo 'color:' . esc_html( $form_title_color ) . ';';
	echo 'font-family:' . stripslashes( esc_html( $arf_title_font_family ) ) . ';'; //phpcs:ignore
	echo 'font-size:' . esc_html( $arf_title_font_size ) . ';';
	echo esc_html( $arf_title_font_style_str );
echo '}';

if ( ! empty( $form->description ) ) {
	echo esc_html( $arf_form_cls_prefix ) . ' div.formdescription_style{';
		echo 'text-align:' . esc_html( $arf_form_title_alignment ) . ';';
		echo 'color:' . esc_html( $form_title_color ) . ';';
		echo 'font-family:' . esc_html( $arf_title_font_family ) . ';';
		echo 'font-size:' . esc_html( $description_font_size ) . 'px;';
	echo '}';
} elseif ( $is_form_save ) {
	echo esc_html( $arf_form_cls_prefix ) . ' .arfeditorformdescription{';
		echo 'text-align:' . esc_html( $arf_form_title_alignment ) . ';';
		echo 'color:' . esc_html( $form_title_color ) . ';';
		echo 'font-family:' . esc_html( $arf_title_font_family ) . ';';
		echo 'font-size:' . esc_html( $description_font_size ) . 'px;';
	echo '}';
}

/** Form Level Styling */

/** Success/Error Message Styling */
if ( empty( $is_form_save ) ) {
	echo esc_html( $arf_form_cls_prefix_without_material_container ) . ' #arf_message_success_popup,';
	echo esc_html( $arf_form_cls_prefix_without_material_container ) . ' #arf_message_success{';
		echo 'width:100%;display:inline-block;min-height:35px;margin:15px 0 15px 0;';
		echo 'border:1px solid ' . esc_html( $success_border_color ) . ';';
		echo 'border-radius:3px;';
		echo '-webkit-border-radius:3px;';
		echo '-o-border-radius:3px;';
		echo '-moz-border-radius:3px;';
		echo 'font-family:' . esc_html( $arf_validation_font_family ) . ';';
		echo 'font-size:20px;';
		echo 'background:' . esc_html( $success_bg_color ) . ';';
		echo 'color:' . esc_html( $success_text_color ) . ';';
	echo '}';

	echo esc_html( $arf_form_cls_prefix_without_material_container ) . ' #arf_message_success_popup .msg-detail::before,';
	echo esc_html( $arf_form_cls_prefix_without_material_container ) . ' #arf_message_success .msg-detail::before{';
		echo 'background-image: url(data:image/svg+xml;base64,' . base64_encode( '<svg width="60" height="60" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0px" y="0px" viewBox="0 0 52 52" enable-background="new 0 0 52 52" xml:space="preserve"><g><path fill="' . esc_attr( $success_text_color ) . '" d="M26,0C11.66,0,0,11.66,0,26s11.66,26,26,26s26-11.66,26-26S40.34,0,26,0z M26,50C12.77,50,2,39.23,2,26   S12.77,2,26,2s24,10.77,24,24S39.23,50,26,50z"/><path fill="' . esc_attr( $success_text_color ) . '" d="M38.25,15.34L22.88,32.63l-9.26-7.41c-0.43-0.34-1.06-0.27-1.41,0.16c-0.35,0.43-0.28,1.06,0.16,1.41l10,8   C22.56,34.93,22.78,35,23,35c0.28,0,0.55-0.11,0.75-0.34l16-18c0.37-0.41,0.33-1.04-0.08-1.41C39.25,14.88,38.62,14.92,38.25,15.34   z"/></g></svg>' ) . ');'; //phpcs:ignore
		echo "content:'';width: 60px;height: 60px;display: block;margin: 0 auto;background-repeat: no-repeat;position:relative;";
	echo '}';

	// phpcs:disable
	echo esc_html( $arf_form_cls_prefix_without_material_container ) . ' .frm_error_style{
        width:100%; 
        display: inline-block; 
        float:none; 
        min-height:35px; 
        margin: 10px 0 10px 0;
        border: 1px solid ' . esc_html( $error_border_color ) . ';
        background: ' . esc_html( $error_bg_color ) . '; 
        color:' . esc_html( $error_txt_color ) . ';
        font-family:' . esc_html( stripslashes($arf_validation_font_family )) . '; 
        font-weight:normal; 
        -moz-border-radius:3px;  
        -webkit-border-radius:3px; 
        -o-border-radius:3px; 
        border-radius:3px;
        font-size:20px; 
        word-break:break-all;';
	echo '}'; // phpcs:enable

	echo esc_html( $arf_form_cls_prefix_without_material_container ) . ' .frm_error_style .msg-detail::before{';
		echo 'background-image: url(data:image/svg+xml;base64,' . base64_encode( '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0" y="0" viewBox="10 10 100 100" enable-background="new 10 10 100 100" xml:space="preserve" height="60" width="60"><g><circle fill="none" stroke="' . esc_attr( $error_txt_color ) . '" stroke-width="4" stroke-miterlimit="10" cx="60" cy="60" r="47"></circle><line fill="none" stroke="' . esc_attr( $error_txt_color ) . '" stroke-width="4" stroke-miterlimit="10" x1="81.214" y1="81.213" x2="38.787" y2="38.787"></line><line fill="none" stroke="' . esc_attr( $error_txt_color ) . '" stroke-width="4" stroke-miterlimit="10" x1="38.787" y1="81.213" x2="81.214" y2="38.787"></line></g></svg>' ) . ');'; //phpcs:ignore
		echo "content:'';width: 60px;height: 60px;display: block;margin: 0 auto;background-repeat: no-repeat;position:relative;";
	echo '}';

	echo esc_html( $arf_form_cls_prefix_without_material_container ) . ' .arf_res_front_msg_desc {';
		echo 'padding:10px 0 10px 0px;';
		echo 'letter-spacing:0.1px;';
		echo 'width:100%;';
		echo 'vertical-align:middle;';
		echo 'display:inline-block;';
		echo 'text-align:center;';
	echo '}';
}
/** Success/Error Message Styling */

/** Submit Button Styling */
echo esc_html( $arf_form_cls_prefix ) . ' .arf_submit_div { clear:both; text-align:' . esc_html( $submit_align ) . '}';
echo esc_html( $arf_form_cls_prefix ) . ' .arfsubmitbutton .arf_submit_btn{';
	echo 'height:' . esc_html( $submit_height ) . 'px;';
if ( '' == $submit_width ) {
	echo 'min-width:' . esc_html( $submit_auto_width ) . 'px;';
} else {
	echo 'width:' . esc_html( $submit_width ) . ';';
}
	echo 'text-align:center;max-width:100%;display:inline-block;';
	echo 'font-family:' . esc_html( $arf_submit_btn_font_family ) . ';';
	echo 'font-size:' . esc_html( $arf_submit_btn_font_size ) . 'px;';
	echo esc_html( $arf_submit_font_style_str );
	echo 'cursor:pointer;outline:none;line-height:1.3;padding:0;';

	echo 'box-shadow:' . esc_html( $submit_xoffset_shadow ) . esc_html( $submit_yoffset_shadow ) . esc_html( $submit_blur_shadow ) . esc_html( $submit_spread_shadow ) . esc_html( $submit_shadow_color ) . ';';

	echo '-webkit-box-shadow:' . esc_html( $submit_xoffset_shadow ) . esc_html( $submit_yoffset_shadow ) . esc_html( $submit_blur_shadow ) . esc_html( $submit_spread_shadow ) . esc_html( $submit_shadow_color ) . ';';

	echo '-o-box-shadow:' . esc_html( $submit_xoffset_shadow ) . esc_html( $submit_yoffset_shadow ) . esc_html( $submit_blur_shadow ) . esc_html( $submit_spread_shadow ) . esc_html( $submit_shadow_color ) . ';';

	echo '-moz-box-shadow:' . esc_html( $submit_xoffset_shadow ) . esc_html( $submit_yoffset_shadow ) . esc_html( $submit_blur_shadow ) . esc_html( $submit_spread_shadow ) . esc_html( $submit_shadow_color ) . ';';

	echo 'background-position: left top;';
	echo 'border-radius:' . esc_html( $submit_border_radius ) . ';';
	echo '-webkit-border-radius:' . esc_html( $submit_border_radius ) . ';';
	echo '-o-border-radius:' . esc_html( $submit_border_radius ) . ';';
	echo '-moz-border-radius:' . esc_html( $submit_border_radius ) . ';';
	echo 'position:relative;';
	echo 'transition: .2s ease-out;';
	echo '-webkit-transition: .2s ease-out;';
	echo '-o-transition: .2s ease-out;';
	echo '-moz-transition: .2s ease-out;';
	echo 'box-sizing:content-box;';
	echo 'margin:0;';
	echo 'text-transform:none;';
echo '}';

if( !empty($submit_width_tablet )){
    
    echo ".arfdevicetablet ".esc_html($arf_form_cls_prefix) . ".arfsubmitbutton .arf_submit_btn{";
        if( '' == $submit_width_tablet ){
            echo "min-width:".esc_html($submit_auto_width)."px;";
        } else {
            echo "width:".esc_html($submit_width_tablet).";";
        }
    echo "}";
    echo "@media all and (max-width:768px){";
        echo esc_html($arf_form_cls_prefix) . " .arfsubmitbutton .arf_submit_btn{";
            if( '' == $submit_width_tablet ){
                echo "min-width:".esc_html($submit_auto_width)."px;";
            } else {
                echo "width:".esc_html($submit_width_tablet).";";
            }
        echo "}";
    echo "}";
}


if( !empty($submit_width_mobile )){
    
    echo ".arfdevicemobile ".esc_html($arf_form_cls_prefix) . ".arfsubmitbutton .arf_submit_btn{";
        if( '' == $submit_width_mobile ){
            echo "min-width:".esc_html($submit_auto_width)."px;";
        } else {
            echo "width:".esc_html($submit_width_mobile).";";
        }
    echo "}";
    echo "@media all and (max-width:576px){";
        echo esc_html($arf_form_cls_prefix) . " .arfsubmitbutton .arf_submit_btn{";
            if( '' == $submit_width_mobile ){
                echo "min-width:".esc_html($submit_auto_width)."px;";
            } else {
                echo "width:".esc_html($submit_width_mobile).";";
            }
        echo "}";
    echo "}";
}


if ( preg_match( '/flat/', $arfsubmitbuttonstyle ) ) {
	echo esc_html( $arf_form_cls_prefix ) . ' .arfsubmitbutton .arf_submit_btn.arf_submit_btn_flat{';
		echo 'background:' . esc_html( $submit_bg_color ) . '' . ( ! empty( $submit_bg_img ) ? ' url(' . esc_url( $submit_bg_img ) . ') ' : '' ) . ';';
	if ( ! empty( $submit_bg_img ) ) {
		echo 'color:transparent;';
	} else {
		echo 'color:' . esc_html( $submit_text_color ) . ';';
	}
		echo 'border:' . esc_html( $submit_border_width ) . ' solid ' . esc_html( $submit_bg_color ) . ';';
	echo '}';

	echo esc_html( $arf_form_cls_prefix ) . ' .arfsubmitbutton .arf_submit_btn.arf_submit_btn_flat.arf_active_loader .arfsubmitloader{';
		echo 'width:' . esc_html( $arf_submit_btn_font_size ) . 'px;';
		echo 'height:' . esc_html( $arf_submit_btn_font_size ) . 'px;';// phpcs:ignore
		echo 'border:' . ceil( esc_html( $arf_submit_btn_font_size ) / 8 ) . 'px solid ' . esc_html( $submit_text_color ) . ';';// phpcs:ignore
		echo 'border-bottom:' . ceil( esc_html( $arf_submit_btn_font_size ) / 8 ) . 'px solid transparent;'; //phpcs:ignore
	echo '}';
}

if ( preg_match( '/border/', $arfsubmitbuttonstyle ) ) {
	echo esc_html( $arf_form_cls_prefix ) . ' .arfsubmitbutton .arf_submit_btn.arf_submit_btn_border{';
		echo 'background:transparent' . ( ! empty( $submit_bg_img ) ? ' url(' . esc_url( $submit_bg_img ) . ') ' : '' ) . ' !important;';
	if ( ! empty( $submit_bg_img ) ) {
		echo 'color:transparent;';
	} else {
		echo 'color:' . esc_html( $submit_bg_color ) . ';';// phpcs:ignore
	}
		echo 'border:' . ( esc_html( $submit_border_width > 0 ) ? esc_html($submit_border_width) : '2px' ) . ' solid ' . esc_html( $submit_bg_color ) . ';';
	echo '}';

	echo esc_html( $arf_form_cls_prefix ) . ' .arfsubmitbutton .arf_submit_btn.arf_submit_btn_border.arf_active_loader .arfsubmitloader{';
		echo 'width:' . esc_html( $arf_submit_btn_font_size ) . 'px;';
		echo 'height:' . esc_html( $arf_submit_btn_font_size ) . 'px;';// phpcs:ignore
		echo 'border:' . ceil( esc_html( $arf_submit_btn_font_size ) / 8 ) . 'px solid ' . esc_html( $submit_text_color ) . ';';// phpcs:ignore
		echo 'border-bottom:' . ceil( esc_html( $arf_submit_btn_font_size ) / 8 ) . 'px solid transparent;'; // phpcs:ignore
	echo '}';
}

if ( preg_match( '/reverse border/', $arfsubmitbuttonstyle ) ) {
	echo esc_html( $arf_form_cls_prefix ) . ' .arfsubmitbutton .arf_submit_btn.arf_submit_btn_reverse_border{';
		echo 'background:' . esc_html( $submit_bg_color ) . '' . ( ! empty( $submit_bg_img ) ? ' url(' . esc_url( $submit_bg_img ) . ') ' : '' ) . ';';
	if ( ! empty( $submit_bg_img ) ) {
		echo 'color:transparent;';
	} else {
		echo 'color:' . esc_html( $submit_text_color ) . ';';
	}
		echo 'border:' . ( ( esc_html($submit_border_width) > 0 ) ? esc_html($submit_border_width) : '2px' ) . ' solid ' . esc_html( $submit_bg_color ) . ';';
	echo '}';

	echo esc_html( $arf_form_cls_prefix ) . ' .arfsubmitbutton .arf_submit_btn.arf_submit_btn_reverse_border.arf_active_loader .arfsubmitloader{';
		echo 'width:' . esc_html( $arf_submit_btn_font_size ) . 'px;';
		echo 'height:' . esc_html( $arf_submit_btn_font_size ) . 'px;';// phpcs:ignore
		echo 'border:' . ceil( esc_html( $arf_submit_btn_font_size ) / 8 ) . 'px solid ' . esc_html( $submit_bg_color ) . ';';// phpcs:ignore
		echo 'border-bottom:' . ceil( esc_html( $arf_submit_btn_font_size ) / 8 ) . 'px solid transparent;'; // phpcs:ignore
	echo '}';
}

if ( preg_match( '/flat/', $arfsubmitbuttonstyle ) ) {
	echo esc_html( $arf_form_cls_prefix ) . ' .arfsubmitbutton .arf_submit_btn.arf_submit_btn_flat.arf_active_loader,';
	echo esc_html( $arf_form_cls_prefix ) . ' .arfsubmitbutton .arf_submit_btn.arf_submit_btn_flat.arf_complete_loader,';
	echo esc_html( $arf_form_cls_prefix ) . ' .arfsubmitbutton .arf_submit_btn.arf_submit_btn_flat:hover{';
	if ( ! empty( $submit_hover_bg_img ) ) {
		echo 'background-image:url(' . esc_url( $submit_hover_bg_img ) . ');';
		echo 'color:transparent;';
	} else {
		echo 'color:' . esc_html( $submit_text_color ) . ';';
	}
		echo 'background-color:' . esc_html( $submit_bg_color_hover ) . ';';
	echo '}';
}

if ( preg_match( '/border/', $arfsubmitbuttonstyle ) ) {
	echo esc_html( $arf_form_cls_prefix ) . ' .arfsubmitbutton .arf_submit_btn.arf_submit_btn_border.arf_active_loader,';
	echo esc_html( $arf_form_cls_prefix ) . ' .arfsubmitbutton .arf_submit_btn.arf_submit_btn_border.arf_complete_loader,';
	echo esc_html( $arf_form_cls_prefix ) . ' .arfsubmitbutton .arf_submit_btn.arf_submit_btn_border:hover{';
	if ( ! empty( $submit_hover_bg_img ) ) {
		echo 'background-image:url(' . esc_url( $submit_hover_bg_img ) . ');';
	} else {
		echo 'background:' . esc_html( $submit_bg_color ) . ' !important;';// phpcs:ignore
		echo 'border:' . ( esc_html( $submit_border_width > 0 ) ? esc_html($submit_border_width) : '2px' ) . ' solid ' . esc_html( $submit_bg_color ) . ';';
		echo 'color:' . esc_html( $submit_text_color ) . ';';
	}
	echo '}';
}

if ( preg_match( '/reverse border/', $arfsubmitbuttonstyle ) ) {
	echo esc_html( $arf_form_cls_prefix ) . ' .arfsubmitbutton .arf_submit_btn.arf_submit_btn_reverse_border.arf_active_loader,';
	echo esc_html( $arf_form_cls_prefix ) . ' .arfsubmitbutton .arf_submit_btn.arf_submit_btn_reverse_border.arf_complete_loader,';
	echo esc_html( $arf_form_cls_prefix ) . ' .arfsubmitbutton .arf_submit_btn.arf_submit_btn_reverse_border:hover{';
	if ( ! empty( $submit_hover_bg_img ) ) {
		echo 'background-image:url(' . esc_url( $submit_hover_bg_img ) . ');';
	} else {
		echo 'background:transparent !important;';
		echo 'color:' . esc_html( $submit_bg_color ) . ';';// phpcs:ignore
		echo 'border:' . ( esc_html( $submit_border_width > 0 ) ? esc_html($submit_border_width) : '2px' ) . ' solid ' . esc_html( $submit_bg_color ) . ';';
	}
	echo '}';
}

echo esc_html( $arf_form_cls_prefix ) . ' .arf_submit_btn.arf_submit_after_confirm.arf_active_loader,';
echo esc_html( $arf_form_cls_prefix ) . ' .arf_submit_btn.arf_submit_after_confirm.arf_complete_loader{';
	echo 'top:-6px;';
echo '}';

echo esc_html( $arf_form_cls_prefix ) . ' .arf_submit_btn.arf_complete_loader .arfsubmitloader{';
	echo 'height:' . esc_html( $arf_submit_btn_font_size ) . 'px;';
	echo 'width:' . ( esc_html( $arf_submit_btn_font_size ) / 2 ) . 'px;';
if ( preg_match( '/flat/', $arfsubmitbuttonstyle ) || preg_match( '/border/', $arfsubmitbuttonstyle ) ) {
	echo 'border-right:' . ceil( esc_html( $arf_submit_btn_font_size ) / 8 ) . 'px solid ' . esc_html( $submit_text_color ) . ';';// phpcs:ignore
	echo 'border-top:' . ceil( esc_html( $arf_submit_btn_font_size ) / 8 ) . 'px solid ' . esc_html( $submit_text_color ) . ';'; //phpcs:ignore
}
if ( preg_match( '/reverse border/', $arfsubmitbuttonstyle ) ) {
	echo 'border-right:' . ceil( esc_html( $arf_submit_btn_font_size ) / 8 ) . 'px solid ' . esc_html( $submit_bg_color ) . ';';// phpcs:ignore
	echo 'border-top:' . ceil( esc_html( $arf_submit_btn_font_size ) / 8 ) . 'px solid ' . esc_html( $submit_bg_color ) . ';'; //phpcs:ignore
}
	echo 'animation-name:arf_loader_checkmark;';
	echo 'animation-duration:0.5s;';
	echo 'animation-timing-function:linear;';
	echo 'animation-fill-mode:initial;';
	echo 'animation-iteration-count:1;';
	echo '-webkit-animation-name:arf_loader_checkmark;';
	echo '-webkit-animation-duration:0.5s;';
	echo '-webkit-animation-timing-function:linear;';
	echo '-webkit-animation-fill-mode:initial;';
	echo '-webkit-animation-iteration-count:1;';
	echo '-o-animation-name:arf_loader_checkmark;';
	echo '-o-animation-duration:0.5s;';
	echo '-o-animation-timing-function:linear;';
	echo '-o-animation-fill-mode:initial;';
	echo '-o-animation-iteration-count:1;';
	echo '-moz-animation-name:arf_loader_checkmark;';
	echo '-moz-animation-duration:0.5s;';
	echo '-moz-animation-timing-function:linear;';
	echo '-moz-animation-fill-mode:initial;';
	echo '-moz-animation-iteration-count:1;';
	echo 'transform:scaleX(-1) rotate(140deg);';
	echo '-webkit-transform:scaleX(-1) rotate(140deg);';
	echo '-o-transform:scaleX(-1) rotate(140deg);';
	echo '-moz-transform:scaleX(-1) rotate(140deg);';
echo '}';

echo '@keyframes arf_loader_checkmark{';
	echo '0% {';
		echo 'height:0px;width:0px;opacity:1;';
	echo '}';
	echo '20% {';
		echo 'height:0px;width:' . ( esc_html( $arf_submit_btn_font_size ) / 2 ) . 'px;opacity:1;';
	echo '}';
	echo '40% {';
		echo 'height:' . esc_html( $arf_submit_btn_font_size ) . 'px;width:' . ( esc_html( $arf_submit_btn_font_size ) / 2 ) . 'px;opacity:1;';
	echo '}';
	echo '100% {';
		echo 'height:' . esc_html( $arf_submit_btn_font_size ) . 'px;width:' . ( esc_html( $arf_submit_btn_font_size ) / 2 ) . 'px;opacity:1;';
	echo '}';
echo '}';
echo '@-webkit-keyframes arf_loader_checkmark{';
	echo '0% {';
		echo 'height:0px;width:0px;opacity:1;';
	echo '}';
	echo '20% {';
		echo 'height:0px;width:' . ( esc_html( $arf_submit_btn_font_size ) / 2 ) . 'px;opacity:1;';
	echo '}';
	echo '40% {';
		echo 'height:' . esc_html( $arf_submit_btn_font_size ) . 'px;width:' . ( esc_html( $arf_submit_btn_font_size ) / 2 ) . 'px;opacity:1;';
	echo '}';
	echo '100% {';
		echo 'height:' . esc_html( $arf_submit_btn_font_size ) . 'px;width:' . ( esc_html( $arf_submit_btn_font_size ) / 2 ) . 'px;opacity:1;';
	echo '}';
echo '}';
/** Submit Button Styling */

/** Field Level Styling */
if ( ! empty( $loaded_field ) ) {

	echo esc_html( $arf_form_cls_prefix ) . ' .arf_material_theme_container{ position:relative; display:inline-block; width:100%; }';

	/** Field Label Styling */
	echo esc_html( $arf_form_cls_prefix ) . ' label.arf_main_label{';
		echo 'text-align:' . esc_html( $arf_label_align ) . ';';
		echo 'font-family:' . esc_html( $arf_label_font_family ) . ';';
		echo 'font-size:' . esc_html( $arf_label_font_size ) . 'px;';
		echo esc_html( $arf_label_font_style_str );
		echo 'color:' . esc_html( $field_label_txt_color ) . ';';
		echo 'text-transform:none;';
		echo 'padding:0;';
		echo 'margin:0;';
	if ( 'left' == $arf_label_position ) {
		echo 'display:inline-block;';
		echo 'float:left;';
		echo 'width:' . esc_html( $arf_label_width ) . 'px;';
	} elseif ( 'right' == $arf_label_position ) {
		echo 'display:inline-block;';
		echo 'float:right;';
		echo 'width:' . esc_html( $arf_label_width ) . 'px;';
	} else {
		echo 'width:100%;';
	}
	echo '}';

	if( (in_array('checkbox', $loaded_field)) || (in_array('radio', $loaded_field)) ){
        echo esc_html($arf_form_cls_prefix) . " label.arf_main_label{";
            echo "white-space: inherit;";
        echo "}";
    }

	echo esc_html( $arf_form_cls_prefix ) . ' .controls .arf_main_label.active{';
		echo 'font-size:12px;';
	echo '}';

	echo esc_html( $arf_form_cls_prefix ) . ' label.arf_main_label:not(.arf_field_option_content_cell_label){';
		echo 'right:inherit;';
		echo 'left:0px;'; 
	echo '}';


	echo esc_html( $arf_form_cls_prefix ) . ' .arfformfield{';
		echo 'margin-bottom:' . esc_html( $field_margin ) . ';';
	echo '}';

	echo esc_html( $arf_form_cls_prefix ) . ' .arfcheckrequiredfield{ color:' . esc_html( $field_label_txt_color ) . ' !important; }';

	echo esc_html( $arf_form_cls_prefix ) . ' .arfformfield .controls{ width: ' . esc_html( $arf_input_field_width ) . esc_html( $arf_input_field_width_unit ) . ' }';

	if( !empty( $arf_input_field_width_tablet )){

        echo ".arfdevicetablet ".esc_html($arf_form_cls_prefix) . " .arfformfield .controls{ width: ".esc_html($arf_input_field_width_tablet).esc_html($arf_input_field_width_unit_tablet)." }";
        echo "@media all and (max-width:768px){";
            echo esc_html($arf_form_cls_prefix) . " .arfformfield .controls{ width: ".esc_html($arf_input_field_width_tablet).esc_html($arf_input_field_width_unit_tablet)." }";
        echo "}";
    }

    if( !empty( $arf_input_field_width_mobile )){

        echo ".arfdevicetablet ".esc_html($arf_form_cls_prefix) . " .arfformfield .controls{ width: ".esc_html($arf_input_field_width_mobile).esc_html($arf_input_field_width_unit_mobile)." }";
        echo "@media all and (max-width:576px){";
            echo esc_html($arf_form_cls_prefix) . " .arfformfield .controls{ width: ".esc_html($arf_input_field_width_mobile).esc_html($arf_input_field_width_unit_mobile)." }";
        echo "}";
    }

	if ( $arf_hide_label ) {
		echo esc_html( $arf_form_cls_prefix ) . ' .none_container label.arf_main_label{';
			echo 'display:none;';
		echo '}';
	}
	/** Field Label Styling */

	/** Field Level Error styling */
	if ( $arf_error_style == 'advance' ) {
		echo esc_html( $arf_form_cls_prefix ) . ' .popover{ background-color:' . esc_html( $arferrorstylecolor ) . '; }';
		echo esc_html( $arf_form_cls_prefix ) . ' .popover.right .arrow:after, #cs_content ' . esc_html( $arf_form_cls_prefix ) . ' .popover.right .arrow{';
			echo 'border-right-color:' . esc_html( $arferrorstylecolor ) . ';';
		echo '}';
		echo esc_html( $arf_form_cls_prefix ) . ' .popover.left .arrow:after, #cs_content ' . esc_html( $arf_form_cls_prefix ) . ' .popover.left .arrow{';
			echo 'border-left-color:' . esc_html( $arferrorstylecolor ) . ';';
		echo '}';
		echo esc_html( $arf_form_cls_prefix ) . ' .popover.top .arrow:after, #cs_content ' . esc_html( $arf_form_cls_prefix ) . ' .popover.top .arrow{';
			echo 'border-top-color:' . esc_html( $arferrorstylecolor ) . ';';
		echo '}';
		echo esc_html( $arf_form_cls_prefix ) . ' .popover.bottom .arrow:after, #cs_content ' . esc_html( $arf_form_cls_prefix ) . ' .popover.bottom .arrow{';
			echo 'border-bottom-color:' . esc_html( $arferrorstylecolor ) . ';';
		echo '}';
		echo esc_html( $arf_form_cls_prefix ) . ' .popover-content{';
			echo 'color:' . esc_html( $arferrorstylecolorfont ) . ';';
			echo 'font-family:' . esc_html( $arf_validation_font_family ) . ';';
			echo 'font-size:' . esc_html( $arf_validation_font_size ) . ';';
			echo 'line-height:normal;';
		echo '}';
	} else {
		echo esc_html( $arf_form_cls_prefix ) . ' .help-block{';
			echo 'margin:4px 0px 0px 0px;';
			echo 'padding:0;';
			echo 'text-align:' . esc_html( $description_align ) . ';';
			echo 'max-width:100%;
            width:100%;
            line-height: 20px;';
			echo 'position: ' . esc_html( $arf_standard_error_position ) . ';';
		echo '}';

		echo esc_html( $arf_form_cls_prefix ) . ' .help-block ul{ margin:0; }';

		echo esc_html( $arf_form_cls_prefix ) . ' .help-block ul li{';
			echo 'color:' . esc_html( $arferrorstylecolor ) . ';';
			echo 'font-family:' . esc_html( $arf_validation_font_family ) . ';';
			echo 'font-size:' . esc_html( $arf_validation_font_size ) . ';';
		echo '}';
	}
	/** Field Level Error styling */

	/** Form Fields Styling */
	if ( array_intersect( $loaded_field, $common_field_type_styling ) ) {

		echo esc_html( $arf_form_cls_prefix ) . ' .arfformfield .controls input:not(.inplace_field):not(.arf_field_option_input_text)' . ( ( in_array( 'phone', $loaded_field ) ) ? ',' . esc_html( $arf_form_cls_prefix ) . ' .arfformfield .controls input[type=tel]:not(.inplace_field):not(.arf_field_option_input_text)' : '' ) . '{';
			echo 'background:transparent;';
			echo 'width:100%;';
			echo 'font-family:' . esc_html( $arf_input_font_family ) . ';';
			echo 'font-size:' . esc_html( $arf_input_font_size ) . 'px;';
			echo esc_html( $arf_input_font_style_str );
			echo 'padding:' . esc_html( $arf_field_inner_padding ) . ';';
			echo 'direction:' . esc_html( $arf_input_field_direction ) . ';';
			echo 'border:none;';
			echo 'border-bottom:' . esc_html( $field_border_width ) . ' ' . esc_html( $field_border_style ) . ' ' . esc_html( $field_border_color ) . ';';
			echo 'border-radius:' . esc_html( $field_border_radius ) . ';';
			echo '-webkit-border-radius:' . esc_html( $field_border_radius ) . ';';
			echo '-o-border-radius:' . esc_html( $field_border_radius ) . ';';
			echo '-moz-border-radius:' . esc_html( $field_border_radius ) . ';';
			echo 'color:' . esc_html( $field_text_color ) . ';';
			echo 'line-height:normal;';
			echo 'outline:none;';
			echo 'box-shadow:none;';
			echo '-webkit-box-shadow:none;';
			echo '-o-box-shadow:none;';
			echo '-moz-box-shadow:none;';
			echo 'padding-top:8px;';
			echo 'padding-bottom:8px;';
			echo 'background:transparent !important;';
			echo 'margin-bottom:0 !important;';
		echo '}';

		echo esc_html( $arf_form_cls_prefix ) . ' .arfformfield .controls input:not(.inplace_field):not(.arf_field_option_input_text):focus' . ( ( in_array( 'phone', $loaded_field ) ) ? ',' . esc_html( $arf_form_cls_prefix ) . ' .arfformfield .controls input[type=tel]:focus:not(.inplace_field):not(.arf_field_option_input_text)' : '' ) . '{';
			echo 'border-bottom:' . esc_html( $field_border_width ) . ' ' . esc_html( $field_border_style ) . ' ' . esc_html( $base_color ) . ';';
		echo '}';

		/** Placeholder - webkit browsers - chrome/edge/opera */
		echo esc_html( $arf_form_cls_prefix ) . ' .arfformfield .controls input:not(.inplace_field):not(.arf_field_option_input_text)::-webkit-input-placeholder' . ( ( in_array( 'phone', $loaded_field ) ) ? ',' . esc_html( $arf_form_cls_prefix ) . ' .arfformfield .controls input[type=tel]:not(.inplace_field):not(.arf_field_option_input_text)::-webkit-input-placeholder' : '' ) . '{';
			echo 'color:' . esc_html( $field_text_color ) . ';';
			echo 'opacity:' . esc_html( $placeholder_opacity ) . '';
		echo '}';

		/** Placeholder - mozilla firefox older versions */
		echo esc_html( $arf_form_cls_prefix ) . ' .arfformfield .controls input:not(.inplace_field):not(.arf_field_option_input_text):-moz-placeholder' . ( ( in_array( 'phone', $loaded_field ) ) ? ',' . esc_html( $arf_form_cls_prefix ) . ' .arfformfield .controls input[type=tel]:not(.inplace_field):not(.arf_field_option_input_text):-moz-placeholder' : '' ) . '{';
			echo 'color:' . esc_html( $field_text_color ) . ';';
			echo 'opacity:' . esc_html( $placeholder_opacity ) . '';
		echo '}';

		/** Placeholder - mozilla firefox */
		echo esc_html( $arf_form_cls_prefix ) . ' .arfformfield .controls input:not(.inplace_field):not(.arf_field_option_input_text)::-moz-placeholder' . ( ( in_array( 'phone', $loaded_field ) ) ? ',' . esc_html( $arf_form_cls_prefix ) . ' .arfformfield .controls input[type=tel]:not(.inplace_field):not(.arf_field_option_input_text)::-moz-placeholder' : '' ) . '{';
			echo 'color:' . esc_html( $field_text_color ) . ';';
			echo 'opacity:' . esc_html( $placeholder_opacity ) . '';
		echo '}';

		/** Placeholder - microsoft internet explorer */
		echo esc_html( $arf_form_cls_prefix ) . ' .arfformfield .controls input:not(.inplace_field):not(.arf_field_option_input_text):-ms-input-placeholder' . ( ( in_array( 'phone', $loaded_field ) ) ? ',' . esc_html( $arf_form_cls_prefix ) . ' .arfformfield .controls input[type=tel]:not(.inplace_field):not(.arf_field_option_input_text):-ms-input-placeholder' : '' ) . '{';
			echo 'color:' . esc_html( $field_text_color ) . ';';
			echo 'opacity:' . esc_html( $placeholder_opacity ) . '';
		echo '}';

		if ( $is_prefix_suffix_enable ) {

			$arf_prefix_padding = '';
			$arf_prefix_width   = '';
			$arf_prefix_padding = '0 0px';

			if ( $arf_input_font_size < 10 ) {
				$arf_prefix_width = '32px';
			} elseif ( $arf_input_font_size >= 10 && $arf_input_font_size < 12 ) {
				$arf_prefix_width = '34px';
			} elseif ( $arf_input_font_size >= 12 && $arf_input_font_size < 14 ) {
				$arf_prefix_width = '36px';
			} elseif ( $arf_input_font_size >= 14 && $arf_input_font_size < 16 ) {
				$arf_prefix_width = '38px';
			} elseif ( $arf_input_font_size >= 16 && $arf_input_font_size < 18 ) {
				$arf_prefix_width = '40px';
			} elseif ( $arf_input_font_size >= 18 && $arf_input_font_size < 20 ) {
				$arf_prefix_width = '42px';
			} elseif ( $arf_input_font_size >= 20 && $arf_input_font_size < 22 ) {
				$arf_prefix_width = '44px';
			} elseif ( $arf_input_font_size == 22 ) {
				$arf_prefix_width = '46px';
			} elseif ( $arf_input_font_size == 24 ) {
				$arf_prefix_width = '51px';
			} elseif ( $arf_input_font_size == 26 ) {
				$arf_prefix_width = '53px';
			} elseif ( $arf_input_font_size == 28 ) {
				$arf_prefix_width = '55px';
			} elseif ( $arf_input_font_size == 32 ) {
				$arf_prefix_width = '60px';
			} elseif ( $arf_input_font_size == 34 ) {
				$arf_prefix_width = '62px';
			} elseif ( $arf_input_font_size == 36 ) {
				$arf_prefix_width = '64px';
			} elseif ( $arf_input_font_size == 38 ) {
				$arf_prefix_width = '67px';
			} elseif ( $arf_input_font_size == 40 ) {
				$arf_prefix_width = '70px';
			}

			$arf_paddingleft_field = ( (int) $arf_prefix_width + $field_pleft ) . 'px';

			echo esc_html( $arf_form_cls_prefix ) . ' .arfformfield .controls .arf_material_theme_container_with_icons.arf_only_leading_icon input:not(.inplace_field):not(.arf_field_option_input_text)' . ( ( in_array( 'phone', $loaded_field ) ) ? ',' . esc_html( $arf_form_cls_prefix ) . ' .arfformfield .controls .arf_material_theme_container_with_icons.arf_only_leading_icon input[type=tel]:not(.inplace_field):not(.arf_field_option_input_text)' : '' ) . '{';
				echo 'padding-left:' . esc_html( $arf_paddingleft_field ) . ' !important;';
			echo '}';

			echo esc_html( $arf_form_cls_prefix ) . ' .arfformfield .controls .arf_material_theme_container_with_icons.arf_only_leading_icon input:not(.inplace_field):not(.arf_field_option_input_text) + .arf_material_standard label.arf_main_label' . ( ( in_array( 'phone', $loaded_field ) ) ? ',' . esc_html( $arf_form_cls_prefix ) . ' .arfformfield .controls .arf_material_theme_container_with_icons.arf_only_leading_icon input[type=tel]:not(.inplace_field):not(.arf_field_option_input_text) + .arf_material_standard label.arf_main_label' : '' ) . '{';
				echo 'padding-left:' . esc_html( $arf_paddingleft_field ) . ' !important;';
			echo '}';

			echo esc_html( $arf_form_cls_prefix ) . ' .arfformfield .controls .arf_material_theme_container_with_icons.arf_only_trailing_icon input:not(.inplace_field):not(.arf_field_option_input_text)' . ( ( in_array( 'phone', $loaded_field ) ) ? ',' . esc_html( $arf_form_cls_prefix ) . ' .arfformfield .controls .arf_material_theme_container_with_icons.arf_only_trailing_icon input[type=tel]:not(.inplace_field):not(.arf_field_option_input_text)' : '' ) . '{';
				echo 'padding-right:' . esc_html( $arf_paddingleft_field ) . ' !important;';
			echo '}';

			echo esc_html( $arf_form_cls_prefix ) . ' .arfformfield .controls .arf_material_theme_container_with_icons.arf_only_trailing_icon input:not(.inplace_field):not(.arf_field_option_input_text) + .arf_material_standard label.arf_main_label' . ( ( in_array( 'phone', $loaded_field ) ) ? ',' . esc_html( $arf_form_cls_prefix ) . ' .arfformfield .controls .arf_material_theme_container_with_icons.arf_only_trailing_icon input[type=tel]:not(.inplace_field):not(.arf_field_option_input_text) + .arf_material_standard label.arf_main_label' : '' ) . '{';
				echo 'padding-right:' . esc_html( $arf_paddingleft_field ) . ' !important;';
			echo '}';

			echo esc_html( $arf_form_cls_prefix ) . ' .arfformfield .controls .arf_material_theme_container_with_icons.arf_both_icons input:not(.inplace_field):not(.arf_field_option_input_text)' . ( ( in_array( 'phone', $loaded_field ) ) ? ',' . esc_html( $arf_form_cls_prefix ) . '.arfformfield .controls .arf_material_theme_container_with_icons.arf_both_icons input[type=tel]:not(.inplace_field):not(.arf_field_option_input_text)' : '' ) . '{';
				echo 'padding-left:' . esc_html( $arf_paddingleft_field ) . ' !important;';
				echo 'padding-right:' . esc_html( $arf_paddingleft_field ) . ' !important;';
			echo '}';

			echo esc_html( $arf_form_cls_prefix ) . ' .arfformfield .controls .arf_material_theme_container_with_icons.arf_both_icons input:not(.inplace_field):not(.arf_field_option_input_text) + .arf_material_standard label.arf_main_label' . ( ( in_array( 'phone', $loaded_field ) ) ? ',' . esc_html( $arf_form_cls_prefix ) . ' .arfformfield .controls .arf_material_theme_container_with_icons.arf_both_icons input[type=tel]:not(.inplace_field):not(.arf_field_option_input_text) + .arf_material_standard label.arf_main_label' : '' ) . '{';
				echo 'padding-left:' . esc_html( $arf_paddingleft_field ) . ' !important;';
				echo 'padding-right:' . esc_html( $arf_paddingleft_field ) . ' !important;';
			echo '}';

			if ( in_array( 'phone', $loaded_field ) ) {
				if ( $is_form_save ) {

					 echo esc_html( $arf_form_cls_prefix ) . '.arfformfield .controls .arf_material_theme_container_with_icons.arf_both_icons  input.arf_phone_utils[type=text]:not(.inplace_field):not(.arf_field_option_input_text){
                        padding-left : 52px !important;
                     }';

					echo esc_html( $arf_form_cls_prefix ) . '.arfformfield .controls input.arf_phone_utils[type=text]:not(.inplace_field):not(.arf_field_option_input_text){
                        padding-left : 52px !important;
                     }';

					echo esc_html( $arf_form_cls_prefix ) . ' .arfformfield .controls .arf_material_theme_container_with_icons.arf_phone_with_flag .iti + input + input + .arf_material_standard label.arf_main_label,';
					echo esc_html( $arf_form_cls_prefix ) . ' .arfformfield .controls .arf_material_theme_container_with_icons.arf_phone_with_flag .iti + input + .arf_material_standard label.arf_main_label,';
				}
				echo esc_html( $arf_form_cls_prefix ) . ' .arfformfield .controls .arf_material_theme_container_with_icons.arf_both_icons  input.arf_phone_utils[type=text]:not(.inplace_field):not(.arf_field_option_input_text){
                        padding-left : 52px !important;
                     }';

				echo esc_html( $arf_form_cls_prefix ) . ' .arfformfield .controls input.arf_phone_utils[type=text]:not(.inplace_field):not(.arf_field_option_input_text){
                        padding-left : 52px !important;
                     }';

				echo esc_html( $arf_form_cls_prefix ) . ' .arfformfield .controls .arf_material_theme_container_with_icons.arf_phone_with_flag .iti + .arf_material_standard label.arf_main_label{';
					echo 'padding-left:' . esc_html( $arf_paddingleft_field ) . ' !important;';
				echo '}';
			}

			if ( $is_form_save ) {

				echo esc_html( $arf_form_cls_prefix ) . ' .arfformfield .controls .arf_material_theme_container_with_icons.arf_only_leading_icon input:not(.inplace_field):not(.arf_field_option_input_text) + label.arf_main_label' . ( ( in_array( 'phone', $loaded_field ) ) ? ',' . esc_html( $arf_form_cls_prefix ) . ' .arfformfield .controls .arf_material_theme_container_with_icons.arf_only_leading_icon input[type=tel]:not(.inplace_field):not(.arf_field_option_input_text)' : '' ) . ' + label.arf_main_label{';
					echo 'left:' . esc_html( $arf_paddingleft_field ) . ';';
				echo '}';

				echo esc_html( $arf_form_cls_prefix ) . ' .arfformfield .controls .arf_material_theme_container_with_icons.arf_only_trailing_icon input:not(.inplace_field):not(.arf_field_option_input_text) + label.arf_main_label' . ( ( in_array( 'phone', $loaded_field ) ) ? ',' . esc_html( $arf_form_cls_prefix ) . ' .arfformfield .controls .arf_material_theme_container_with_icons.arf_only_trailing_icon input[type=tel]:not(.inplace_field):not(.arf_field_option_input_text)' : '' ) . ' + label.arf_main_label{';
					echo 'right:' . esc_html( $arf_paddingleft_field ) . ';';
				echo '}';

				echo esc_html($arf_form_cls_prefix) . ' .arfformfield .controls .arf_material_theme_container_with_icons.arf_both_icons input:not(.inplace_field):not(.arf_field_option_input_text) + label.arf_main_label' . ( ( in_array( 'phone', $loaded_field ) ) ? ",".esc_html($arf_form_cls_prefix)." .arfformfield .controls .arf_material_theme_container_with_icons.arf_both_icons input[type=tel]:not(.inplace_field):not(.arf_field_option_input_text)" : '' ) . ' + label.arf_main_label{';
					echo "left:".esc_html($arf_paddingleft_field).";";
					echo "right:".esc_html($arf_paddingleft_field).";";
				echo '}';
			}

			echo esc_html( $arf_form_cls_prefix ) . '.arfformfield .arf_leading_icon{
                position:absolute;
                top: 50%;
                left: 10px;
                transform:translateY(-50%);
                -webkit-transform:translateY(-50%);
                -o-transform:translateY(-50%);
                -moz-transform:translateY(-50%);
                font-size:' . esc_html( $arf_input_font_size ) . 'px;
                height:' . esc_html( $arf_input_font_size ) . 'px;
                width:' . esc_html( $arf_input_font_size ) . 'px;
                line-height: normal;
                color:' . esc_html( $prefix_suffix_icon_color ) . ";
            }
            
            ".esc_html($arf_form_cls_prefix)." .arfformfield .arf_trailing_icon{
                position: absolute;
                top: 50%;
                right: 10px;
                height:" . esc_html( $arf_input_font_size ) . 'px;
                width:' . esc_html( $arf_input_font_size ) . 'px;
                line-height: unset;
                transform:translateY(-50%);
                -webkit-transform:translateY(-50%);
                -o-transform:translateY(-50%);
                -moz-transform:translateY(-50%);
                font-size:' . esc_html( $arf_input_font_size ) . 'px;
                color:' . esc_html( $prefix_suffix_icon_color ) . ';
            }';
		}
	}

	if ( in_array( 'checkbox', $loaded_field ) ) {
		echo esc_html( $arf_form_cls_prefix ) . ' .arf_checkbox_style{';
			echo 'position: relative;';
			echo 'min-height: 20px;';
			echo 'max-width: 100%;';
			echo 'display: inline-flex;';
			$final_width_calc = ( $arf_label_font_size + 12 ) < 30 ? 30 : ( $arf_label_font_size + 12 );
			$checkbox_spacing = $arf_input_font_size;
			echo "padding-left:".esc_html($final_width_calc)."px;";
		if ( $checkbox_spacing >= 38 ) {
			echo 'margin:0 4% 25px 0;';
		} elseif ( $checkbox_spacing >= 36 ) {
			echo 'margin:0 3.5% 22px 0;';
		} elseif ( $checkbox_spacing >= 32 ) {
			echo 'margin:0 3% 20px 0;';
		} elseif ( $checkbox_spacing >= 30 ) {
			echo 'margin:0 2.5% 15px 0;';
		} elseif ( $checkbox_spacing >= 26 ) {
			echo 'margin:0 2% 15px 0;';
		} elseif ( $checkbox_spacing >= 22 ) {
			echo 'margin:0 2% 10px 0;';
		} else {
			echo 'margin:0 2% 10px 0;';
		}
		echo '}';

		echo esc_html( $arf_form_cls_prefix ) . ' .arf_checkbox_style .arf_checkbox_input_wrapper{';
			echo 'position: absolute;';
			echo 'left: 0px;';
			echo 'width:20px;';
			echo 'height: 20px;';
			echo 'margin-right: 10px;';
			echo 'vertical-align:middle;';
			echo 'display:inline-flex;';
			echo 'align-self:center;';
		echo '}';

		echo esc_html( $arf_form_cls_prefix ) . ' .setting_checkbox .arf_checkbox_input_wrapper input[type="checkbox"]{';
			echo 'position:absolute;';
			echo 'left: 0;';
			echo 'top: 0;';
			echo 'width: 20px;';
			echo 'height: 20px;';
			echo 'opacity:0;';
			echo 'z-index:2;';
			echo 'cursor:pointer;';
			echo 'margin:0;';
		echo '}';

		echo esc_html( $arf_form_cls_prefix ) . ' .setting_checkbox .arf_checkbox_input_wrapper input[type="checkbox"] + span::after{';
			echo 'position: absolute;';
			echo "content: '';";
			echo 'border:2px solid ' . esc_html( $field_border_color ) . ';';
			echo 'width: 20px;';
			echo 'height: 20px;';
			echo 'box-sizing: border-box;';
			echo '-webkit-box-sizing: border-box;';
			echo '-o-box-sizing: border-box;';
			echo '-moz-box-sizing: border-box;';
		echo '}';

		if ( $arf_label_font_size > 20 ) {
			$final_width_calc = ( $arf_label_font_size + 12 ) < 30 ? 30 : ( $arf_label_font_size + 12 );
			echo esc_html( $arf_form_cls_prefix ) . ' .arf_checkbox_style .arf_checkbox_input_wrapper,';
			echo esc_html( $arf_form_cls_prefix ) . ' .setting_checkbox .arf_checkbox_input_wrapper input[type="checkbox"],';
			echo esc_html( $arf_form_cls_prefix ) . ' .setting_checkbox .arf_checkbox_input_wrapper input[type="checkbox"] + span::after{';
				echo 'width:' . esc_html( $arf_label_font_size ) . 'px;';
				echo 'height:' . esc_html( $arf_label_font_size ) . 'px;';
			echo '}';
		}

		/** Default material checkbox ( Material 1 ) */
		echo esc_html( $arf_form_cls_prefix ) . ' .setting_checkbox.arf_defaulat_material .arf_checkbox_input_wrapper input[type="checkbox"] + span::after{';
			echo 'transition: border .25s, background-color .25s, width .20s .1s, height .20s .1s, top .20s .1s, left .20s .1s;';
			echo '-webkit-transition: border .25s, background-color .25s, width .20s .1s, height .20s .1s, top .20s .1s, left .20s .1s;';
			echo '-o-transition: border .25s, background-color .25s, width .20s .1s, height .20s .1s, top .20s .1s, left .20s .1s;';
			echo '-moz-transition: border .25s, background-color .25s, width .20s .1s, height .20s .1s, top .20s .1s, left .20s .1s;';
		echo '}';

		echo esc_html( $arf_form_cls_prefix ) . ' .setting_checkbox.arf_default_material .arf_checkbox_input_wrapper input[type="checkbox"] + span::before{';
			echo "content: '';";
			echo 'width:0;';
			echo 'height: 0;';
			echo 'position: absolute;';
			echo 'left:50%;';
			echo 'top: 65%;';
			echo 'border:3px solid transparent;';
			echo 'box-sizing: border-box;';
			echo '-webkit-box-sizing: border-box;';
			echo '-o-box-sizing: border-box;';
			echo '-moz-box-sizing: border-box;';
			echo 'transition: border .25s, background-color .25s, width .20s .1s, height .20s .1s, top .20s .1s, left .20s .1s;';
			echo '-webkit-transition: border .25s, background-color .25s, width .20s .1s, height .20s .1s, top .20s .1s, left .20s .1s;';
			echo '-o-transition: border .25s, background-color .25s, width .20s .1s, height .20s .1s, top .20s .1s, left .20s .1s;';
			echo '-moz-transition: border .25s, background-color .25s, width .20s .1s, height .20s .1s, top .20s .1s, left .20s .1s;';
			echo 'transform: rotateZ(40deg) translate(-50%, -50%);';
			echo '-webkit-transform: rotateZ(40deg) translate(-50%, -50%);';
			echo '-o-transform: rotateZ(40deg) translate(-50%, -50%);';
			echo '-moz-transform: rotateZ(40deg) translate(-50%, -50%);';
			echo 'transform-origin: 45% -10%;';
			echo '-webkit-transform-origin: 45% -10%;';
			echo '-o-transform-origin: 45% -10%;';
			echo '-moz-transform-origin: 45% -10%;';
			echo 'z-index:1;';
		echo '}';

		echo esc_html( $arf_form_cls_prefix ) . ' .setting_checkbox.arf_default_material .arf_checkbox_input_wrapper input[type="checkbox"]:checked + span::after{';
			echo 'background:' . esc_html( $base_color ) . ';';
			echo 'border-color:' . esc_html( $base_color ) . ';';
		echo '}';

		echo esc_html( $arf_form_cls_prefix ) . ' .setting_checkbox.arf_default_material .arf_checkbox_input_wrapper input[type="checkbox"]:checked + span::before{';
			echo 'top: 50%;';
			echo 'left: 50%;';
			echo 'width: 30%;';
			echo 'height: 50%;';
			echo 'border-top: 2px solid transparent;';
			echo 'border-left: 2px solid transparent;';
			echo 'border-right: 2px solid #fff;';
			echo 'border-bottom: 2px solid #fff;';
		echo '}';

		echo esc_html( $arf_form_cls_prefix ) . ' .setting_checkbox .arf_checkbox_style label{';
			echo 'font-size:' . esc_html( $arf_label_font_size ) . 'px;';
			echo 'color:' . esc_html( $field_label_txt_color ) . ';';
			echo 'font-family:' . esc_html( $arf_input_font_family ) . ';';
			echo esc_html( $arf_input_font_style_str );
			echo 'vertical-align: top;';
			echo 'word-wrap: break-word;';
			echo 'width: auto;';
			echo 'margin:unset;';
			echo 'padding:0;';
			echo 'position:relative;';
			echo 'max-width:100%;';
			echo 'top:0;';
			echo 'display:inline-flex;';
			echo 'word-break:break-all;';
			echo 'line-height: 1.1em;';
			echo 'align-self:center;';
			echo 'cursor:pointer;';
			echo 'width:auto;';
		echo '}';

		/** Advanced Material Design ( Material 2 ) */
		echo esc_html( $arf_form_cls_prefix ) . ' .setting_checkbox.arf_advanced_material .arf_checkbox_input_wrapper input[type="checkbox"] + span::after{';
			echo "content:'';";
			echo 'transition: .25s;';
			echo '-webkit-transition: .25s;';
			echo '-o-transition: .25s;';
			echo '-moz-transition: .25s;';
			echo 'top:0;';
			echo 'left:0;';
			echo 'z-index:0;';
		echo '}';

		echo esc_html( $arf_form_cls_prefix ) . ' .setting_checkbox.arf_advanced_material .arf_checkbox_input_wrapper input[type="checkbox"]:checked + span::after{';
			echo 'top: 40%;';
			echo 'left: 55%;';
			echo 'width: 50%;';
			echo 'height: 100%;';
			echo 'border-top: 2px solid transparent;';
			echo 'border-left: 2px solid transparent;';
			echo 'border-right: 2px solid ' . esc_html( $base_color ) . ';';
			echo 'border-bottom: 2px solid ' . esc_html( $base_color ) . ';';
			echo 'transform: rotate(40deg) translate(-50%,-50%);';
			echo '-webkit-transform: rotate(40deg) translate(-50%,-50%);';
			echo '-o-transform: rotate(40deg) translate(-50%,-50%);';
			echo '-moz-transform: rotate(40deg) translate(-50%,-50%);';
			echo 'transform-origin: 50% 20%;';
			echo '-webkit-transform-origin: 50% 20%;';
			echo '-o-transform-origin: 50% 20%;';
			echo '-moz-transform-origin: 50% 20%;';
		echo '}';

		echo esc_html( $arf_form_cls_prefix ) . ' .setting_checkbox.arf_custom_checkbox .arf_checkbox_input_wrapper input[type="checkbox"] + span:after{';
			echo 'border-width:1px;';
		echo '}';

		echo esc_html( $arf_form_cls_prefix ) . ' .setting_checkbox.arf_custom_checkbox .arf_checkbox_input_wrapper input[type="checkbox"] + span i{';
			echo 'position:absolute;';
			echo 'top:50%;';
			echo 'left:50%;';
			echo 'transform:translate(-50%,-50%);';
			echo '-webkit-transform:translate(-50%,-50%);';
			echo '-o-transform:translate(-50%,-50%);';
			echo '-moz-transform:translate(-50%,-50%);';
			echo 'display:none;';
			echo 'color:' . esc_html( $base_color ) . ';';
		if ( ( $arf_label_font_size - 14 ) > 16 ) {
			echo 'font-size:' . esc_html( $arf_label_font_size - 14 ) . 'px;';
		} else {
			echo 'font-size:13px;';
		}
		echo '}';

		echo esc_html( $arf_form_cls_prefix ) . ' .setting_checkbox.arf_custom_checkbox .arf_checkbox_input_wrapper input[type="checkbox"]:checked + span:after{';
			echo 'border-color:' . esc_html( $base_color ) . ';';
		echo '}';

		echo esc_html( $arf_form_cls_prefix ) . ' .setting_checkbox.arf_custom_checkbox .arf_checkbox_input_wrapper input[type="checkbox"]:checked + span i{';
			echo 'display:block;';
		echo '}';

		echo esc_html( $arf_form_cls_prefix ) . ' .arf_multiple_row .arf_checkbox_style,';
		echo esc_html( $arf_form_cls_prefix ) . ' .arf_vertical_radio .arf_checkbox_style{display:flex; width:100%}';

		echo esc_html( $arf_form_cls_prefix ) . ' .arfformfield.arf_horizontal_radio .arf_chk_radio_col_two,';
		echo esc_html( $arf_form_cls_prefix ) . ' .arfformfield.arf_horizontal_radio .arf_chk_radio_col_thiree,';
		echo esc_html( $arf_form_cls_prefix ) . ' .arfformfield.arf_horizontal_radio .arf_chk_radio_col_four {';
			echo 'width: 100%;';
			echo 'display:flex;';
		echo '}';

		/* checkbox  */
		echo '@media all and (max-width:480px){';
			echo esc_html( $arf_form_cls_prefix ) . ' .arfformfield.arf_horizontal_radio .arf_chk_radio_col_two,';
			echo esc_html( $arf_form_cls_prefix ) . ' .arfformfield.arf_horizontal_radio .arf_chk_radio_col_thiree,';
			echo esc_html( $arf_form_cls_prefix ) . ' .arfformfield.arf_horizontal_radio .arf_chk_radio_col_four {';
				echo 'flex-direction: column;';
			echo '}';

			echo esc_html( $arf_form_cls_prefix ) . ' .arfformfield.arf_horizontal_radio .arf_chk_radio_col_two .arf_checkbox_style,';
			echo esc_html( $arf_form_cls_prefix ) . ' .arfformfield.arf_horizontal_radio .arf_chk_radio_col_thiree .arf_checkbox_style,';
			echo esc_html( $arf_form_cls_prefix ) . ' .arfformfield.arf_horizontal_radio .arf_chk_radio_col_four .arf_checkbox_style{';
				echo 'width: 100% !important';
			echo '}';

			echo esc_html( $arf_form_cls_prefix ) . ' .setting_checkbox .arf_checkbox_style[class*="arf_enable_checkbox_i"] label{';
				echo 'width:100%';
			echo '}';
		echo '}';

		echo esc_html( $arf_form_cls_prefix ) . ' .arfformfield.arf_horizontal_radio .arf_chk_radio_col_two .arf_checkbox_style{';
			echo 'width: 48%;';
			echo 'margin: 0 2% 10px 0;';
		echo '}';

		echo esc_html( $arf_form_cls_prefix ) . ' .arfformfield.arf_horizontal_radio .arf_chk_radio_col_thiree .arf_checkbox_style{';
			echo 'width: 31.33%;';
			echo 'margin: 0 2% 10px 0;';
		echo '}';

		echo esc_html( $arf_form_cls_prefix ) . ' .arfformfield.arf_horizontal_radio .arf_chk_radio_col_four .arf_checkbox_style{';
			echo 'width: 23%;';
			echo 'margin: 0 2% 10px 0;';
		echo '}';

		if ( $is_checkbox_img_enable ) {
			echo esc_html( $arf_form_cls_prefix ) . ' .setting_checkbox .arf_checkbox_style[class*="arf_enable_checkbox_i"]{';
				echo 'padding-left:0;';
			echo '}';
			echo esc_html( $arf_form_cls_prefix ) . ' .setting_checkbox .arf_checkbox_style[class*="arf_enable_checkbox_i"] div{';
				echo 'opacity: 0;';
			echo '}';
			echo esc_html( $arf_form_cls_prefix ) . ' .setting_checkbox .arf_checkbox_style[class*="arf_enable_checkbox_i"] label{';
				echo 'width: auto;';
				echo 'display:block;';
			echo '}';
			echo esc_html( $arf_form_cls_prefix ) . ' .setting_checkbox .arf_checkbox_style[class*="arf_enable_checkbox_i"] label[class*="arf_checkbox_label_image"]{';
				echo 'display:block;';
			echo '}';
			echo esc_html( $arf_form_cls_prefix ) . ' .setting_checkbox .arf_checkbox_style[class*="arf_enable_checkbox_i"] label[class*="arf_checkbox_label_image"]::before{';
				echo 'background-color:' . esc_html( $base_color ) . ';';
				echo 'color:' . ( ( $arflitesettingcontroller->arfliteisColorDark( $base_color ) == '1' ) ? '#ffffff' : '#1A1A1A' ) . ';';
				echo 'display:flex;';
				echo 'align-items:center;';
				echo 'justify-content:center;';
				echo 'border-radius: 4px;';
				echo 'position: absolute;';
				echo 'right: -3px;';
				echo 'width: 24px;';
				echo 'height: 24px;';
				echo 'text-align: center;';
				echo 'line-height: 28px;';
				echo 'z-index: 2;';
				echo 'font-size: 12px;';
				echo 'font-weight: 900;';
				echo 'top:60%;';
				echo 'opacity:0;';
			echo '}';

			echo esc_html( $arf_form_cls_prefix ) . ' .setting_checkbox .arf_checkbox_style[class*="arf_enable_checkbox_i"] label[class*="arf_checkbox_label_image"].far::before,';
			echo esc_html( $arf_form_cls_prefix ) . ' .setting_checkbox .arf_checkbox_style[class*="arf_enable_checkbox_i"] label[class*="arf_checkbox_label_image"].fas::before{';
				echo "font-family:'Font Awesome 5 Free';";
			echo '}';

			echo esc_html( $arf_form_cls_prefix ) . ' .setting_checkbox .arf_checkbox_style[class*="arf_enable_checkbox_i"] label[class*="arf_checkbox_label_image"].fab::before{';
				echo "font-family:'Font Awesome 5 Brands';";
			echo '}';

			echo esc_html( $arf_form_cls_prefix ) . ' .setting_checkbox .arf_checkbox_style[class*="arf_enable_checkbox_i"] .img_stroke,';
			echo esc_html( $arf_form_cls_prefix ) . ' .setting_checkbox .arf_checkbox_style[class*="arf_enable_checkbox_i"] .rect-cutoff{';
				echo 'display:none;';
			echo '}';

			echo esc_html( $arf_form_cls_prefix ) . ' .setting_checkbox .arf_checkbox_style[class*="arf_enable_checkbox_i"] span.arf_checkbox_label{';
				echo 'display:inline-block;';
				echo 'margin-top:7px;';
				echo 'color:' . esc_html( $field_label_txt_color ) . ' !important;';
			echo '}';

			echo esc_html( $arf_form_cls_prefix ) . ' .setting_checkbox .arf_checkbox_style[class*="arf_enable_checkbox_i"] label[class*="arf_checkbox_label_image"].checked::before{';
				echo 'top:-8px;';
				echo 'opacity:1;';
			echo '}';

			echo esc_html( $arf_form_cls_prefix ) . ' .setting_checkbox .arf_checkbox_style[class*="arf_enable_checkbox_i"] label[class*="arf_checkbox_label_image"].checked .img_stroke,';
			echo esc_html( $arf_form_cls_prefix ) . ' .setting_checkbox .arf_checkbox_style[class*="arf_enable_checkbox_i"] label[class*="arf_checkbox_label_image"].checked .rect-cutoff{';
				echo 'display:block;';
			echo '}';

			echo esc_html( $arf_form_cls_prefix ) . ' .setting_checkbox .arf_checkbox_style[class*="arf_enable_checkbox_i"] label[class*="arf_checkbox_label_image"].checked .img_stroke{';
				echo 'stroke-width:5px;';
				echo 'stroke:' . esc_html( $base_color ) . ';';
			echo '}';

			if ( $arflite_preview ) {
				$all_checkbox_fields = $checkbox_img_field_arr;
			} else {
				$all_checkbox_fields = $wpdb->get_results( $wpdb->prepare( 'SELECT id,field_options FROM ' . $tbl_arf_fields . ' WHERE type = %s AND form_id = %s', 'checkbox', $form_id ) ); //phpcs:ignore
			}


			if ( ! empty( $all_checkbox_fields ) ) {

				foreach ( $all_checkbox_fields as $field ) {
					if ( is_array( $field->field_options ) ) {
						$field->field_options = json_encode( $field->field_options );
					}
					$fopts = json_decode( $field->field_options );
					if ( ! isset( $fopts->image_width ) || $fopts->image_width == '' ) {
						$fopts->image_width = 120;
					}

					echo ":root{--arf_field_".intval($field->id)." : " . esc_html($fopts->image_width) . 'px; }';
					echo ".arf_field_".intval($field->id)." .rect-cutoff{ transform: translateX( calc( var(--arf_field_".intval($field->id).") - 25px ) ) translateY(-6.5px); }";
				}
			}
		}
	}

	if ( in_array( 'radio', $loaded_field ) ) {

		echo esc_html( $arf_form_cls_prefix ) . ' .setting_radio .arf_radio_input_wrapper + label{';
			echo 'font-size:' . esc_html( $arf_label_font_size ) . 'px;';
			echo 'color:' . esc_html( $field_label_txt_color ) . ';';
			echo 'font-family:' . esc_html( $arf_input_font_family ) . ';';
			echo esc_html( $arf_input_font_style_str );
			echo 'vertical-align: top;';
			echo 'word-wrap: break-word;';
			echo 'width: auto;';
			echo 'margin:unset;';
			echo 'padding:0;';
			echo 'position:relative;';
			echo 'max-width:100%;';
			echo 'top:0;';
			echo 'display:inline-flex;';
			echo 'word-break:break-all;';
			echo 'line-height: 1.1em;';
			echo 'align-self:center;';
			echo 'cursor:pointer;';
			echo 'width:auto;';
		echo '}';

		if ( $is_radio_img_enable ) {
			echo esc_html( $arf_form_cls_prefix ) . ' .setting_radio .arf_radiobutton[class*="arf_enable_radio_i"]{';
				echo 'padding-left:0;';
			echo '}';
			echo esc_html( $arf_form_cls_prefix ) . ' .setting_radio .arf_radiobutton[class*="arf_enable_radio_i"] div{';
				echo 'opacity: 0;';
			echo '}';
			echo esc_html( $arf_form_cls_prefix ) . ' .setting_radio .arf_radiobutton[class*="arf_enable_radio_i"] label{';
				echo 'width: auto;';
				echo 'display:block;';
			echo '}';
			echo esc_html( $arf_form_cls_prefix ) . ' .setting_radio .arf_radiobutton[class*="arf_enable_radio_i"] label[class*="arf_radio_label_image"]{';
				echo 'display:block;';
				echo 'cursor:pointer;';
			echo '}';
			echo esc_html( $arf_form_cls_prefix ) . ' .setting_radio .arf_radiobutton[class*="arf_enable_radio_i"] label[class*="arf_radio_label_image"]::before{';
				echo 'background-color:' . esc_html( $base_color ) . ';';
				echo 'color:' . ( ( $arflitesettingcontroller->arfliteisColorDark( $base_color ) == '1' ) ? '#ffffff' : '#1A1A1A' ) . ';';
				echo 'display:flex;';
				echo 'align-items:center;';
				echo 'justify-content:center;';
				echo 'border-radius: 4px;';
				echo 'position: absolute;';
				echo 'right: -3px;';
				echo 'width: 24px;';
				echo 'height: 24px;';
				echo 'text-align: center;';
				echo 'line-height: 28px;';
				echo 'z-index: 2;';
				echo 'font-size: 12px;';
				echo 'font-weight: 900;';
				echo 'top:60%;';
				echo 'opacity:0;';
			echo '}';

			echo esc_html( $arf_form_cls_prefix ) . ' .setting_radio .arf_radiobutton[class*="arf_enable_radio_i"] label[class*="arf_radio_label_image"].far::before,';
			echo esc_html( $arf_form_cls_prefix ) . ' .setting_radio .arf_radiobutton[class*="arf_enable_radio_i"] label[class*="arf_radio_label_image"].fas::before{';
				echo "font-family:'Font Awesome 5 Free';";
			echo '}';

			echo esc_html( $arf_form_cls_prefix ) . ' .setting_radio .arf_radiobutton[class*="arf_enable_radio_i"] label[class*="arf_radio_label_image"].fab::before{';
				echo "font-family:'Font Awesome 5 Brands';";
			echo '}';

			echo esc_html( $arf_form_cls_prefix ) . ' .setting_radio .arf_radiobutton[class*="arf_enable_radio_i"] .img_stroke,';
			echo esc_html( $arf_form_cls_prefix ) . ' .setting_radio .arf_radiobutton[class*="arf_enable_radio_i"] .rect-cutoff{';
				echo 'display:none;';
			echo '}';

			echo esc_html( $arf_form_cls_prefix ) . ' .setting_radio .arf_radiobutton[class*="arf_enable_radio_i"] span.arf_radio_label{';
				echo 'display:inline-block;';
				echo 'margin-top:7px;';
				echo 'color:' . esc_html( $field_label_txt_color ) . ' !important;';
			echo '}';

			echo esc_html( $arf_form_cls_prefix ) . ' .setting_radio .arf_radiobutton[class*="arf_enable_radio_i"] label[class*="arf_radio_label_image"].checked::before{';
				echo 'top:-8px;';
				echo 'opacity:1;';
			echo '}';

			echo esc_html( $arf_form_cls_prefix ) . ' .setting_radio .arf_radiobutton[class*="arf_enable_radio_i"] label[class*="arf_radio_label_image"].checked .img_stroke,';
			echo esc_html( $arf_form_cls_prefix ) . ' .setting_radio .arf_radiobutton[class*="arf_enable_radio_i"] label[class*="arf_radio_label_image"].checked .rect-cutoff{';
				echo 'display:block;';
			echo '}';

			echo esc_html( $arf_form_cls_prefix ) . ' .setting_radio .arf_radiobutton[class*="arf_enable_radio_i"] label[class*="arf_radio_label_image"].checked .img_stroke{';
				echo 'stroke-width:5px;';
				echo "stroke:".esc_html($base_color).";";
			echo '}';

			if ( $arflite_preview ) {
				$all_radio_fields = $radio_img_field_arr;
			} else {
				$all_radio_fields = $wpdb->get_results( $wpdb->prepare( 'SELECT id,field_options FROM ' . $tbl_arf_fields . ' WHERE type = %s AND form_id = %s', 'radio', $form_id ) ); //phpcs:ignore
			}


			if ( ! empty( $all_radio_fields ) ) {
				foreach ( $all_radio_fields as $field ) {
					if ( is_array( $field->field_options ) ) {
						$field->field_options = json_encode( $field->field_options );
					}
					$fopts = json_decode( $field->field_options );
					if ( ! isset( $fopts->image_width ) || $fopts->image_width == '' ) {
						$fopts->image_width = 120;
					}

					echo ":root{--arf_field_".intval($field->id)." : " . esc_html($fopts->image_width) . 'px; }';
					echo ".arf_field_".intval($field->id)." .rect-cutoff{ transform: translateX( calc( var(--arf_field_".intval($field->id).") - 25px ) ) translateY(-6.5px); }";
				}
			}
		}
	}

	if ( in_array( 'radio', $loaded_field ) ) {

		echo esc_html( $arf_form_cls_prefix ) . ' .arf_radiobutton{';
			echo 'position: relative;';
			echo 'min-height: 20px;';
			echo 'max-width: 100%;';
			echo 'display: inline-flex;';
			$final_width_calc = ( $arf_label_font_size + 12 ) < 30 ? 30 : ( $arf_label_font_size + 12 );
			$checkbox_spacing = $arf_input_font_size;
			echo 'padding-left:' . esc_html( $final_width_calc ) . 'px;';
		if ( $checkbox_spacing >= 38 ) {
			echo 'margin:0 4% 25px 0;';
		} elseif ( $checkbox_spacing >= 36 ) {
			echo 'margin:0 3.5% 22px 0;';
		} elseif ( $checkbox_spacing >= 32 ) {
			echo 'margin:0 3% 20px 0;';
		} elseif ( $checkbox_spacing >= 30 ) {
			echo 'margin:0 2.5% 15px 0;';
		} elseif ( $checkbox_spacing >= 26 ) {
			echo 'margin:0 2% 15px 0;';
		} elseif ( $checkbox_spacing >= 22 ) {
			echo 'margin:0 2% 10px 0;';
		} else {
			echo 'margin:0 2% 10px 0;';
		}
		echo '}';

		echo esc_html( $arf_form_cls_prefix ) . ' .arf_radiobutton .arf_radio_input_wrapper{';
			echo 'position: absolute;';
			echo 'left: 0px;';
			echo 'width:20px;';
			echo 'height: 20px;';
			echo 'margin-right: 10px;';
			echo 'vertical-align:middle;';
			echo 'display:inline-flex;';
			echo 'align-self:center;';
		echo '}';

		echo esc_html( $arf_form_cls_prefix ) . ' .setting_radio .arf_radio_input_wrapper input[type="radio"]{';
			echo 'position:absolute;';
			echo 'left: 0;';
			echo 'top: 0;';
			echo 'width: 20px;';
			echo 'height: 20px;';
			echo 'opacity:0;';
			echo 'z-index:2;';
			echo 'cursor:pointer;';
			echo 'margin:0;';
		echo '}';

		echo esc_html( $arf_form_cls_prefix ) . ' .setting_radio:not(.arf_custom_radio) .arf_radio_input_wrapper input[type="radio"] + span::after,';
		echo esc_html( $arf_form_cls_prefix ) . ' .setting_radio:not(.arf_custom_radio) .arf_radio_input_wrapper input[type="radio"] + span::before{';
			echo 'position: absolute;';
			echo "content: '';";
			echo 'border:2px solid ' . esc_html( $field_border_color ) . ';';
			echo 'width: 20px;';
			echo 'height: 20px;';
			echo 'border-radius:100px;';
			echo 'box-sizing: border-box;';
			echo '-webkit-box-sizing: border-box;';
			echo '-o-box-sizing: border-box;';
			echo '-moz-box-sizing: border-box;';
		echo '}';

		if ( $arf_label_font_size > 20 ) {
			$final_width_calc = ( $arf_label_font_size + 12 ) < 30 ? 30 : ( $arf_label_font_size + 12 );
			echo esc_html( $arf_form_cls_prefix ) . ' .arf_radiobutton .arf_radio_input_wrapper,';
			echo esc_html( $arf_form_cls_prefix ) . ' .setting_radio .arf_radio_input_wrapper input[type="radio"],';
			echo esc_html( $arf_form_cls_prefix ) . ' .setting_radio .arf_radio_input_wrapper input[type="radio"] + span::before,';
			echo esc_html( $arf_form_cls_prefix ) . ' .setting_radio .arf_radio_input_wrapper input[type="radio"] + span::after{';
				echo 'width:' . esc_html( $arf_label_font_size ) . 'px;';
				echo 'height:' . esc_html( $arf_label_font_size ) . 'px;';
			echo '}';
		}

		echo esc_html( $arf_form_cls_prefix ) . ' .setting_radio:not(.arf_custom_radio) .arf_radio_input_wrapper input[type="radio"] + span::after{';
			echo 'transform:scale(0);';
			echo '-webkit-transform:scale(0);';
			echo '-o-transform:scale(0);';
			echo '-moz-transform:scale(0);';
			echo 'transition:.28s ease;';
			echo '-webkit-transition:.28s ease;';
			echo '-o-transition:.28s ease;';
			echo '-moz-transition:.28s ease;';
		echo '}';

		echo esc_html( $arf_form_cls_prefix ) . ' .setting_radio:not(.arf_custom_radio) .arf_radio_input_wrapper input[type="radio"]:checked + span::after{';
			echo 'transform:scale(1);';
			echo '-webkit-transform:scale(1);';
			echo '-o-transform:scale(1);';
			echo '-moz-transform:scale(1);';
			echo 'background:' . esc_html( $base_color ) . ';';
			echo 'border:2px solid ' . esc_html( $base_color ) . ';';
		echo '}';

		echo esc_html( $arf_form_cls_prefix ) . ' .setting_radio.arf_advanced_material .arf_radio_input_wrapper input[type="radio"]:checked + span::before{';
			echo 'border:2px solid ' . esc_html( $base_color ) . ';';
		echo '}';

		echo esc_html( $arf_form_cls_prefix ) . ' .setting_radio.arf_advanced_material .arf_radio_input_wrapper input[type="radio"]:checked + span::after{';
			echo 'transform:scale(0.5);';
			echo '-webkit-transform:scale(0.5);';
			echo '-o-transform:scale(0.5);';
			echo '-moz-transform:scale(0.5);';
		echo '}';

		echo esc_html( $arf_form_cls_prefix ) . ' .setting_radio.arf_custom_radio .arf_radio_input_wrapper input[type="radio"] + span:after{';
			echo 'border-width:1px;';
		echo '}';

		echo esc_html( $arf_form_cls_prefix ) . ' .setting_radio.arf_custom_radio .arf_radio_input_wrapper input[type="radio"] + span i{';
			echo 'position:absolute;';
			echo 'top:45%;';
			echo 'left:47%;';
			echo 'transform:translate(-50%,-50%);';
			echo '-webkit-transform:translate(-50%,-50%);';
			echo '-o-transform:translate(-50%,-50%);';
			echo '-moz-transform:translate(-50%,-50%);';
			echo 'display:none;';
			echo 'color:' . esc_html( $base_color ) . ';';
		if ( ( $arf_label_font_size - 14 ) > 16 ) {
			echo 'font-size:' . esc_html( $arf_label_font_size - 14 ) . 'px;';
		} else {
			echo 'font-size:11px;';
		}
		echo '}';

		echo esc_html( $arf_form_cls_prefix ) . ' .setting_radio.arf_custom_radio .arf_radio_input_wrapper input[type="radio"]:checked + span{';
			echo 'border-color:' . esc_html( $base_color ) . ';';
		echo '}';

		echo esc_html( $arf_form_cls_prefix ) . ' .setting_radio.arf_custom_radio .arf_radio_input_wrapper input[type="radio"] + span{';
			echo 'display:inline-block;';
			echo 'width: 100%;';
			echo 'height: 100%;';
			echo 'position:relative;';
			echo 'border:2px solid ' . esc_html( $field_border_color ) . ';';
			echo 'border-radius:100px;';
			echo 'box-sizing: border-box;';
			echo '-webkit-box-sizing: border-box;';
			echo '-o-box-sizing: border-box;';
			echo '-moz-box-sizing: border-box;';
		echo '}';

		echo esc_html( $arf_form_cls_prefix ) . ' .setting_radio.arf_custom_radio .arf_radio_input_wrapper input[type="radio"]:checked + span i{';
			echo 'display:block;';
		echo '}';

		echo esc_html( $arf_form_cls_prefix ) . ' .arf_multiple_row .arf_radiobutton,';
		echo esc_html( $arf_form_cls_prefix ) . ' .arf_vertical_radio .arf_radiobutton{display:flex; width:100%}';

		echo esc_html( $arf_form_cls_prefix ) . ' .arfformfield.arf_horizontal_radio .arf_chk_radio_col_two,';
		echo esc_html( $arf_form_cls_prefix ) . ' .arfformfield.arf_horizontal_radio .arf_chk_radio_col_thiree,';
		echo esc_html( $arf_form_cls_prefix ) . ' .arfformfield.arf_horizontal_radio .arf_chk_radio_col_four {';
			echo 'width: 100%;';
			echo 'display:flex;';
		echo '}';

		echo esc_html( $arf_form_cls_prefix ) . ' .arfformfield.arf_horizontal_radio .arf_chk_radio_col_two .arf_radiobutton{';
			echo 'width: 48%;';
			echo 'margin: 0 2% 10px 0;';
		echo '}';

		echo esc_html( $arf_form_cls_prefix ) . ' .arfformfield.arf_horizontal_radio .arf_chk_radio_col_thiree .arf_radiobutton{';
			echo 'width: 31.33%;';
			echo 'margin: 0 2% 10px 0;';
		echo '}';

		echo esc_html( $arf_form_cls_prefix ) . ' .arfformfield.arf_horizontal_radio .arf_chk_radio_col_four .arf_radiobutton{';
			echo 'width: 23%;';
			echo 'margin: 0 2% 10px 0;';
		echo '}';

		echo '@media all and (max-width:480px){';
			echo esc_html( $arf_form_cls_prefix ) . ' .arfformfield.arf_horizontal_radio .arf_chk_radio_col_two,';
			echo esc_html( $arf_form_cls_prefix ) . ' .arfformfield.arf_horizontal_radio .arf_chk_radio_col_thiree,';
			echo esc_html( $arf_form_cls_prefix ) . ' .arfformfield.arf_horizontal_radio .arf_chk_radio_col_four {';
				echo 'flex-direction: column;';
			echo '}';

			echo esc_html( $arf_form_cls_prefix ) . ' .arfformfield.arf_horizontal_radio .arf_chk_radio_col_two .arf_radiobutton,';
			echo esc_html( $arf_form_cls_prefix ) . ' .arfformfield.arf_horizontal_radio .arf_chk_radio_col_thiree .arf_radiobutton,';
			echo esc_html( $arf_form_cls_prefix ) . ' .arfformfield.arf_horizontal_radio .arf_chk_radio_col_four .arf_radiobutton{';
				echo 'width: 100% !important;';
			echo '}';

		echo '}';
	}

	if ( in_array( 'select', $loaded_field ) ) {
		echo esc_html( $arf_form_cls_prefix ) . '.sltstandard_front .arf-selectpicker-control.arf_form_field_picker dt{
            border:none;
            border-bottom:' . esc_html( $field_border_width ) . ' ' . esc_html( $field_border_style ) . ' ' . esc_html( $field_border_color ) . ';';
			echo 'background:transparent;';
			echo 'background-image:none;
            box-shadow:none;
            -webkit-box-shadow:none;
            -o-box-shadow:none;
            -moz-box-shadow:none;
            outline:0 !important;
            -moz-border-radius:' . esc_html( $field_border_radius ) . ';
            -webkit-border-radius:' . esc_html( $field_border_radius ) . ';
            -o-border-radius:' . esc_html( $field_border_radius ) . ';
            border-radius:' . esc_html( $field_border_radius ) . ';
            padding:' . esc_html( $arf_field_inner_padding ) . ' !important;
            line-height: normal;
            width:100%;
            margin-top:0px;
        }';

		echo esc_html( $arf_form_cls_prefix ) . '.arfformfield.arfcurrent_field_active .controls{';
			echo 'z-index:2 !important;';
		echo '}';

		echo esc_html( $arf_form_cls_prefix ) . '.sltstandard_front .arf-selectpicker-control.arf_form_field_picker dd ul{
            -moz-border-radius:' . esc_html( $field_border_radius ) . ';
            -webkit-border-radius:' . esc_html( $field_border_radius ) . ';
            -o-border-radius:' . esc_html( $field_border_radius ) . ';
            border-radius:' . esc_html( $field_border_radius ) . ';
        }';

		if ( 'rtl' == $arf_input_field_direction ) {
			echo esc_html( $arf_form_cls_prefix ) . '.sltstandard_front .arf-selectpicker-control.arf_form_field_picker dt i{';
				echo 'right:unset;';
				echo 'left:8px;';
			echo '}';

			echo esc_html( $arf_form_cls_prefix ) . '.sltstandard_front .arf-selectpicker-control.arf_form_field_picker dt span{';
				echo 'text-align:right;';
				echo 'float:right;';
				echo 'right:0;';
				echo 'left:unset !important;';
			echo '}';

			echo esc_html( $arf_form_cls_prefix ) . '.sltstandard_front .arf-selectpicker-control.arf_form_field_picker dd ul li{';
				echo 'text-align:right;';
			echo '}';
		}

		if ( 'ltr' == $arf_input_field_direction ) {
			echo 'body.rtl ' . esc_html( $arf_form_cls_prefix ) . ' .sltstandard_front .arf-selectpicker-control.arf_form_field_picker dt i{';
				echo 'left:unset;';
				echo 'right:8px;';
			echo '}';

			echo 'body.rtl ' . esc_html( $arf_form_cls_prefix ) . ' .sltstandard_front .arf-selectpicker-control.arf_form_field_picker dt span{';
				echo 'text-align:left;';
				echo 'float:left;';
				echo 'left:0;';
				echo 'right:unset !important;';
			echo '}';

			echo 'body.rtl ' . esc_html( $arf_form_cls_prefix ) . ' .sltstandard_front .arf-selectpicker-control.arf_form_field_picker dd ul li{';
				echo 'text-align:left;';
			echo '}';
		}

		echo esc_html( $arf_form_cls_prefix ) . '.sltstandard_front .arf-selectpicker-control.arf_form_field_picker dt span{
            font-size:' . esc_html( $arf_input_font_size ) . 'px;
            color:' . esc_html( $field_text_color ) . ' !important; 
            font-family:' . esc_html( $arf_input_font_family ) . ";
            ".esc_html($arf_input_font_style_str)."
            position:absolute;
            left:0;
            top:50%;
            transform:translateY(-50%);
            -webkit-transform:translateY(-50%);
            -o-transform:translateY(-50%);
            -moz-transform:translateY(-50%);
            text-transform: none;
        }";

		echo esc_html( $arf_form_cls_prefix ) . '.sltstandard_front .arf-selectpicker-control.arf_form_field_picker.open dt{
            border:none;
            border-bottom:' . esc_html( $field_border_width ) . ' ' . esc_html( $field_border_style ) . ' ' . esc_html( $base_color ) . ';
            background:transparent;
            background-image:none;
            box-shadow:none;
            -webkit-box-shadow:none;
            -o-box-shadow:none;
            -moz-box-shadow:none;
            outline:0 !important;
            width:100%;
            margin-top:0px;
        }';

		echo esc_html( $arf_form_cls_prefix ) . '.sltstandard_front .arf-selectpicker-control.arf_form_field_picker.open:not(.open_from_top) dt{
            border-bottom-left-radius:0px !important;
            border-bottom-right-radius:0px !important;
        }';

		echo esc_html( $arf_form_cls_prefix ) . '.arf_rounded_form .sltstandard_front .arf-selectpicker-control.arf_form_field_picker.open:not(.open_from_top) dt{
            border-top-left-radius:20px !important;
            border-top-right-radius:20px !important;
        }';

		echo esc_html( $arf_form_cls_prefix ) . '.sltstandard_front .arf-selectpicker-control.arf_form_field_picker.open.open_from_top dt{
            border-top-left-radius:0px !important;
            border-top-right-radius:0px !important;
        }';

		echo esc_html( $arf_form_cls_prefix ) . '.arf_rounded_form .sltstandard_front .arf-selectpicker-control.arf_form_field_picker.open.open_from_top dt{
            border-bottom-left-radius: 20px !important;
            border-bottom-right-radius: 20px !important;
        }';

		echo esc_html( $arf_form_cls_prefix ) . '.sltstandard_front .arf-selectpicker-control.arf_form_field_picker dd{
            border:none;
        }';

		echo esc_html( $arf_form_cls_prefix ) . '.sltstandard_front .arf-selectpicker-control.arf_form_field_picker.open dd{
            float:left;
            width: 100%;
            position:absolute;
            top:10px;
        }';

		echo esc_html( $arf_form_cls_prefix ) . '.sltstandard_front .arf-selectpicker-control.arf_form_field_picker dd ul{';
			echo 'display:block;';
			echo 'transform:scaleX(0) scaleY(0);';
			echo '-webkit-transform:scaleX(0) scaleY(0);';
			echo '-o-transform:scaleX(0) scaleY(0);';
			echo '-moz-transform:scaleX(0) scaleY(0);';
			echo 'transition:all .25s;';
			echo '-webkit-transition:all .25s;';
			echo '-o-transition:all .25s;';
			echo '-moz-transition:all .25s;';
			echo 'transform-origin: 0% 0%;';
			echo 'opacity:0;';
			echo '-webkit-transform-origin: 0% 0%;';
			echo '-o-transform-origin: 0% 0%;';
			echo '-moz-transform-origin: 0% 0%;';
		echo '}';

		echo esc_html( $arf_form_cls_prefix ) . '.sltstandard_front .arf-selectpicker-control.arf_form_field_picker.open dd ul{
			border:' . esc_html( $field_border_width ) . '' . esc_html( $field_border_style ) . ' ' . esc_html( $base_color ) . ';
            background-color:' . esc_html( $field_bg_color ) . ';
            box-shadow:none;
            -webkit-box-shadow:none;
            -o-box-shadow:none;
            -moz-box-shadow:none;
            margin:0;
            top:0;
            width:100%;';
			echo 'opacity:1;';
			echo 'transform:scaleX(1) scaleY(1);';
			echo '-webkit-transform:scaleX(1) scaleY(1);';
			echo '-o-transform:scaleX(1) scaleY(1);';
			echo '-moz-transform:scaleX(1) scaleY(1);';
		echo '}';

		echo esc_html( $arf_form_cls_prefix ) . '.sltstandard_front .arf-selectpicker-control.arf_form_field_picker.open:not(.open_from_top) dd ul{
            border-top-left-radius:0px !important;
            border-top-right-radius:0px !important;
        }';

		echo esc_html( $arf_form_cls_prefix ) . '.sltstandard_front .arf-selectpicker-control.arf_form_field_picker.open:not(.open_from_top) dd ul{
            border-bottom-left-radius:3px !important;
            border-bottom-right-radius:3px !important;
        }';

		echo esc_html( $arf_form_cls_prefix ) . '.sltstandard_front .arf-selectpicker-control.arf_form_field_picker.open.open_from_top dd ul{
			border: ' . esc_html( $field_border_width ) . ' ' . esc_html( $field_border_style ) . ' ' . esc_html( $field_border_color ) . ';
            border-bottom:none;
            border-bottom-left-radius:0px !important;
            border-bottom-right-radius:0px !important;
            border-top-left-radius:3px !important;
            border-top-right-radius:3px !important;
        }';

		echo esc_html( $arf_form_cls_prefix ) . '.sltstandard_front .arf-selectpicker-control.arf_form_field_picker dd ul > li {
            font-size:' . esc_html( $arf_input_font_size ) . 'px;
            color:' . esc_html( $field_text_color ) . ';
            font-family:' . esc_html( $arf_input_font_family ) . ';
            '.esc_html($arf_input_font_style_str).';
			padding:14px 12px !important;;
			line-height: normal;
            text-align: ' . esc_html( $arf_input_field_text_align ) . ';
            text-transform: none;
        }';

		echo esc_html( $arf_form_cls_prefix ) . ' .sltstandard_front .arf-selectpicker-control.arf_form_field_picker dd ul > li.arm_sel_opt_checked::before,
        ' . esc_html( $arf_form_cls_prefix ) . ' .sltstandard_front .arf-selectpicker-control.arf_form_field_picker dd ul > li.arm_sel_opt_checked::after{
            background:' . esc_html( $field_text_color ) . ' !important;
        }';

		echo esc_html( $arf_form_cls_prefix ) . " .arf-selectpicker-control.arf_form_field_picker dd ul li.hovered,
        ".esc_html($arf_form_cls_prefix)." .arf-selectpicker-control.arf_form_field_picker dd ul li:hover{
            color: #ffffff !important;    
            background-color:" . esc_html( $base_color ) . ' !important;
        }';

		echo esc_html( $arf_form_cls_prefix ) . '.sltstandard_front .arf-selectpicker-control.arf_form_field_picker dd ul > li.hovered.arm_sel_opt_checked::before,
        ' . esc_html( $arf_form_cls_prefix ) . ' .sltstandard_front .arf-selectpicker-control.arf_form_field_picker dd ul > li.hovered.arm_sel_opt_checked::after,
        ' . esc_html( $arf_form_cls_prefix ) . ' .sltstandard_front .arf-selectpicker-control.arf_form_field_picker dd ul > li:hover.arm_sel_opt_checked::before,
        ' . esc_html( $arf_form_cls_prefix ) . ' .sltstandard_front .arf-selectpicker-control.arf_form_field_picker dd ul > li:hover.arm_sel_opt_checked::after{
            background: #ffffff !important;
        }';
	}

	if ( in_array( 'textarea', $loaded_field ) ) {
		echo esc_html( $arf_form_cls_prefix ) . ' textarea{';
			echo 'width:100%;';
			echo 'background:transparent;';
			echo 'font-family:' . esc_html( $arf_input_font_family ) . ';';
			echo 'font-size:' . esc_html( $arf_input_font_size ) . 'px;';
			echo esc_html( $arf_input_font_style_str );
			echo 'padding:' . esc_html( $arf_field_inner_padding ) . ';';
			echo 'direction:' . esc_html( $arf_input_field_direction ) . ';';
			echo 'color:' . esc_html( $field_text_color ) . ';';
			echo 'border:none;';
			echo 'border-bottom:' . esc_html( $field_border_width ) . ' ' . esc_html( $field_border_style ) . ' ' . esc_html( $field_border_color ) . ';';
			echo 'border-radius:' . esc_html( $field_border_radius ) . ';';
			echo '-webkit-border-radius:' . esc_html( $field_border_radius ) . ';';
			echo '-o-border-radius:' . esc_html( $field_border_radius ) . ';';
			echo '-moz-border-radius:' . esc_html( $field_border_radius ) . ';';
			echo 'line-height:normal;';
			echo 'outline:none;';
			echo 'box-shadow:none;';
			echo '-webkit-box-shadow:none;';
			echo '-o-box-shadow:none;';
			echo '-moz-box-shadow:none;';
			echo 'max-width:100%;';
			echo 'padding-top:8px;';
			echo 'padding-bottom:8px;';
			echo 'height:auto;';
			echo 'min-height:auto;';
		echo '}';
		echo esc_html( $arf_form_cls_prefix ) . ' textarea:focus{';
			echo 'border:none;';
			echo 'border-bottom:' . esc_html( $field_border_width ) . ' ' . esc_html( $field_border_style ) . ' ' . esc_html( $base_color ) . ';';
			echo 'background:transparent;';
		echo '}';
		echo esc_html( $arf_form_cls_prefix ) . ' .arfcount_text_char_div{';
			echo 'margin:2px 0px 0px 0px;padding:0;';
			echo 'font-family:' . esc_html( $arf_input_font_family ) . ';';
			echo 'font-size:' . esc_html( $description_font_size ) . 'px;';
			echo 'text-align:right;';
			echo 'color:' . esc_html( $field_label_txt_color ) . ';';
			echo 'max-width:100%;width:auto; line-height: 20px;';
		echo '}';
		echo esc_html( $arf_form_cls_prefix ) . ' .arf_textareachar_limit{float:left;width:95%;width:calc(100% - 50px) !important;}';
		echo esc_html( $arf_form_cls_prefix ) . ' .arf_field_type_textarea label.arf_main_label:not(.active){';
			echo 'top:8px;';
			echo 'transform: translateY(0);';
		echo '}';
		if ( $is_form_save ) {
			echo esc_html( $arf_form_cls_prefix ) . ' .edit_field_type_textarea label.arf_main_label:not(.active){';
				echo 'top:8px;';
				echo 'transform: translateY(0);';
			echo '}';
		}
	}

	if ( in_array( 'date', $loaded_field ) || in_array( 'time', $loaded_field ) ) {
		echo esc_html( $arf_form_cls_prefix ) . '.bootstrap-datetimepicker-widget table tbody tr{background:#FFFFFF !important;}';
		echo esc_html( $arf_form_cls_prefix ) . " .picker-switch td span:hover{background-color:".esc_html($base_color)." !important;}"; 

		echo esc_html( $arf_form_cls_prefix ) . ' .bootstrap-datetimepicker-widget table td.active, 
        ' . esc_html( $arf_form_cls_prefix ) . " .bootstrap-datetimepicker-widget table td.active:hover{ 
            color: ".esc_html($base_color)." !important; 
        }";

		echo '.bootstrap-datetimepicker-widget table td.old, .bootstrap-datetimepicker-widget table td.new{color: #96979a !important;}';

		echo esc_html( $arf_form_cls_prefix ) . '.bootstrap-datetimepicker-widget table td.day,' . esc_html( $arf_form_cls_prefix ) . ' .bootstrap-datetimepicker-widget table span.month,' . esc_html( $arf_form_cls_prefix ) . ' .bootstrap-datetimepicker-widget table span.year:not(.disabled),' . esc_html( $arf_form_cls_prefix ) . ' .bootstrap-datetimepicker-widget table span.decade:not(.disabled){
        color :' . esc_html( $arf_date_picker_text_color ) . ';
        }';

		echo esc_html( $arf_form_cls_prefix ) . ' .bootstrap-datetimepicker-widget table td.day:not(.active):hover {
        background-color: #F5F5F5;border-radius: 50px;-webkit-border-radius: 50px;-o-border-radius: 50px;-moz-border-radius: 50px;
        }';

		echo esc_html( $arf_form_cls_prefix ) . '.bootstrap-datetimepicker-widget table td.active:not(.disabled), 
        ' . esc_html( $arf_form_cls_prefix ) . ' .bootstrap-datetimepicker-widget table td.active:not(.disabled):hover{
        background-image : url("data:image/svg+xml;base64,' . base64_encode( "<svg width='35px' xmlns='http://www.w3.org/2000/svg' height='29px'><path fill='rgb(" . $arflitesettingcontroller->arflitehex2rgb( $base_color ) . ")' d='M15.732,27.748c0,0-14.495,0.2-14.71-11.834c0,0,0.087-7.377,7.161-11.82 c0,0,0.733-0.993-1.294-0.259c0,0-1.855,0.431-3.538,2.2c0,0-1.078,0.216-0.388-1.381c0,0,2.416-3.019,8.585-2.76 c0,0,2.372-2.458,7.419-1.293c0,0,0.819,0.517-0.518,0.819c0,0-5.361,0.514-3.753,1.122c0,0,14.021,3.073,14.322,13.943 C29.019,16.484,29.573,27.32,15.732,27.748z M26.991,16.182C26.24,7.404,14.389,3.543,14.389,3.543 c-2.693-0.747-4.285,0.683-4.285,0.683C8.767,4.969,6.583,7.804,6.583,7.804C2.216,13.627,3.612,18.47,3.612,18.47 c2.168,7.635,12.505,7.097,12.505,7.097C27.376,25.418,26.991,16.182,26.991,16.182z'/></svg>" ) . '") !important; background-repeat:no-repeat;}'; //phpcs:ignore

		echo esc_html( $arf_form_cls_prefix ) . ' .bootstrap-datetimepicker-widget table td.today:before{ border-color: ' . esc_html( $base_color ) . '; }
        ' . esc_html( $arf_form_cls_prefix ) . ' .arfmainformfieldrepeater{
            margin-bottom:' . esc_html( $field_margin ) . ';
        }';
		echo esc_html( $arf_form_cls_prefix ) . ' .arf_cal_month{border-bottom : ' . esc_html( $base_color ) . ' !important;}';

		echo esc_html( $arf_form_cls_prefix ) . ' .widget-area .bootstrap-datetimepicker-widget {
            left: auto !important;
            right: 0 !important;
        }';
		echo esc_html( $arf_form_cls_prefix ) . ' .bootstrap-datetimepicker-widget {
            z-index:99999;
        }';
		echo esc_html( $arf_form_cls_prefix ) . ' .bootstrap-datetimepicker-widget .datepicker thead {
            box-shadow: none;
            -webkit-box-shadow:none;
            -moz-box-shadow:none;
            -o-box-shadow:none;
        }';
		echo esc_html( $arf_form_cls_prefix ) . ' .arf_cal_header th, 
        ' . esc_html( $arf_form_cls_prefix ) . ' .arf_cal_month th {
            border: none !important;
        }';

		echo esc_html( $arf_form_cls_prefix ) . ' .bootstrap-datetimepicker-widget a[data-action],
        ' . esc_html( $arf_form_cls_prefix ) . ' .bootstrap-datetimepicker-widget a[data-action]:hover{
            box-shadow: none !important;   
            -webkit-box-shadow: none !important;
        -o-box-shadow: none !important;
        -moz-box-shadow: none !important;
        }';

		echo esc_html( $arf_form_cls_prefix ) . ' .bootstrap-datetimepicker-widget .list-unstyled {
            padding: 0px;
        }';
		echo esc_html( $arf_form_cls_prefix ) . ' .bootstrap-datetimepicker-widget .list-unstyled li {
            list-style: none;
            padding: 0px;
            margin-bottom: 0px !important;
        }';

		echo esc_html( $arf_form_cls_prefix ) . ' .bootstrap-datetimepicker-widget .table-condensed { margin-bottom: 0 !important; }';

		echo esc_html( $arf_form_cls_prefix ) . ' .bootstrap-datetimepicker-widget div,
        ' . esc_html( $arf_form_cls_prefix ) . ' .bootstrap-datetimepicker-widget span,
        ' . esc_html( $arf_form_cls_prefix ) . ' .bootstrap-datetimepicker-widget ul,
        ' . esc_html( $arf_form_cls_prefix ) . ' .bootstrap-datetimepicker-widget li,
        ' . esc_html( $arf_form_cls_prefix ) . ' .bootstrap-datetimepicker-widget table,
        ' . esc_html( $arf_form_cls_prefix ) . ' .bootstrap-datetimepicker-widget tbody,
        ' . esc_html( $arf_form_cls_prefix ) . ' .bootstrap-datetimepicker-widget tfoot,
        ' . esc_html( $arf_form_cls_prefix ) . ' .bootstrap-datetimepicker-widget thead,
        ' . esc_html( $arf_form_cls_prefix ) . ' .bootstrap-datetimepicker-widget tr,
        ' . esc_html( $arf_form_cls_prefix ) . ' .bootstrap-datetimepicker-widget th,
        ' . esc_html( $arf_form_cls_prefix ) . ' .bootstrap-datetimepicker-widget td {
            vertical-align: baseline;
        }';

		echo esc_html( $arf_form_cls_prefix ) . ' .bootstrap-datetimepicker-widget .timepicker tr{ background:inherit; }';

		echo esc_html( $arf_form_cls_prefix ) . ' .bootstrap-datetimepicker-widget {
            font-family:' . esc_html( $arf_input_font_family ) . ';
            padding: 0px !important;
            font-size: 14px !important;
        }';

		echo esc_html( $arf_form_cls_prefix ) . ' .bootstrap-datetimepicker-widget table th {
            border: 0px none rgba(0,0,0,0) !important;
            letter-spacing: 0px;
            background: none;
        }';

		echo esc_html( $arf_form_cls_prefix ) . ' .bootstrap-datetimepicker-widget table td {
            border: 0px none !important;
        }';

		echo esc_html( $arf_form_cls_prefix ) . ' .bootstrap-datetimepicker-widget .list-unstyled {
            padding: 0px;
        }';

		echo esc_html( $arf_form_cls_prefix ) . ' .bootstrap-datetimepicker-widget .list-unstyled li {
            list-style: none;
            padding: 0px;
            margin-bottom: 0px !important;
        }';

		echo esc_html( $arf_form_cls_prefix ) . ' .bootstrap-datetimepicker-widget table tbody tr{
        border: 0px none !important;
        background: #ffffff !important;
        }';

		echo esc_html( $arf_form_cls_prefix ) . ' .bootstrap-datetimepicker-widget table thead tr.arf_cal_header{
            border-bottom: 1px solid #FFFFFF !important;
        }';

		echo esc_html( $arf_form_cls_prefix ) . ' .bootstrap-datetimepicker-widget table {
            border: 0px none !important;
            border-collapse: collapse !important;
        }';

		echo esc_html( $arf_form_cls_prefix ) . ' .arf_date_main_controls .bootstrap-datetimepicker-widget table{
            overflow: hidden !important;
            border-radius:0 0 0 0 !important;
            -moz-border-radius:0 0 0 0 !important;
            -webkit-border-radius:0 0 0 0 !important;
            -o-border-radius:0 0 0 0 !important;
            -webkit-transform: translateZ(0);
            -o-transform:translateZ(0);
            background: #ffffff !important;
            border: transparent !important;    
        }';

		echo esc_html( $arf_form_cls_prefix ) . ' .controls .arf_cal_header,';
		echo esc_html( $arf_form_cls_prefix ) . ' .controls .arf_cal_month,';
		echo esc_html( $arf_form_cls_prefix ) . ' .controls .arf_cal_month th,';
		echo esc_html( $arf_form_cls_prefix ) . ' .controls .arf_cal_header th{';
			echo 'background-color:transparent !important; color:#1A1A1A; font-weight:bold;';
		echo '}';

		echo esc_html( $arf_form_cls_prefix ) . ' .controls .timepicker .arf-glyphicon-chevron-down:hover, ' . esc_html( $arf_form_cls_prefix ) . ' .controls .timepicker .arf-glyphicon-chevron-up:hover, ' . esc_html( $arf_form_cls_prefix ) . ' .controls .timepicker-picker .timepicker-minute:hover, ' . esc_html( $arf_form_cls_prefix ) . ' .controls .timepicker-picker .timepicker-hour:hover, ' . esc_html( $arf_form_cls_prefix ) . ' .controls .timepicker-hours .arf_cal_hour:hover, ' . esc_html( $arf_form_cls_prefix ) . ' .controls .timepicker-minutes .arf_cal_minute:hover{
            background-color: #eeeeee;
            border:none;
            border-radius:50px;
            -webkit-border-radius:50px;
            -o-border-radius:50px;
            -moz-border-radius:50px;
        }';

		echo esc_html( $arf_form_cls_prefix ) . ' .arf_cal_header th,
        ' . esc_html( $arf_form_cls_prefix ) . ' .arf_cal_month th,
        ' . esc_html( $arf_form_cls_prefix ) . ' .arf_cal_body{
            font-size: 14px;
        }';

		echo esc_html( $arf_form_cls_prefix ) . ' .arf_cal_header th,
        ' . esc_html( $arf_form_cls_prefix ) . ' .arf_cal_month th {
            font-family: Arial, Helvetica, Verdana, sans-serif;
            text-transform: none;
            font-weight: bold;
            text-shadow: none;
        }';

		echo esc_html( $arf_form_cls_prefix ) . ' .controls .picker-switch td span.arf-glyphicon-time, ' . esc_html( $arf_form_cls_prefix ) . ' .controls .picker-switch td span.arf-glyphicon-calendar{';
			echo 'background:' . esc_html( $base_color ) . '';
		echo '}';

		echo esc_html( $arf_form_cls_prefix ) . ' .controls .arf-glyphicon-time:before, ' . esc_html( $arf_form_cls_prefix ) . ' .controls .arf-glyphicon-calendar:before{';
			echo 'color:' . ( ( $arflitesettingcontroller->arfliteisColorDark( $base_color ) == '1' ) ? '#ffffff' : '#1A1A1A' ) . ' !important;';
		echo '}';

		echo esc_html( $arf_form_cls_prefix ) . " .arf-glyphicon {
            position: relative;
            top: 0px !important;
            display: inline-block;
            font-family: 'Glyphicons Halflings';
            font-style: normal;
            font-weight: 400;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }";

		echo esc_html( $arf_form_cls_prefix ) . ' .arf_time_main_controls .arfdate-dropdown-menu {
            overflow: hidden !important;
            border-radius:4px 4px 4px 4px !important;
             -moz-border-radius:4px 4px 4px 4px !important;
             -webkit-border-radius:4px 4px 4px 4px !important;
             -o-border-radius:4px 4px 4px 4px !important;
           -webkit-transform: translateZ(0);
           -o-transform:translateZ(0);
           background: #ffffff !important;
            border: transparent !important;
        }';

		echo esc_html( $arf_form_cls_prefix ) . ' .arf_date_main_controls .arfdate-dropdown-menu{
            overflow: hidden !important;
           border-radius:4px 4px 4px 4px !important;
           -moz-border-radius:4px 4px 4px 4px !important;
           -webkit-border-radius:4px 4px 4px 4px !important;
           -o-border-radius:4px 4px 4px 4px !important;
           -webkit-transform: translateZ(0);
           -o-transform:translateZ(0);
           background: #ffffff !important;
           border: transparent !important;
        }';

		echo esc_html( $arf_form_cls_prefix ) . ' .arfdate-dropdown-menu {
          position: absolute;
          top: 100%;
          left: 0;
          z-index: 1000;
          display: none;
          float: left;
          min-width: 160px;
          margin: 2px 0 0;
          font-size: 14px;
          text-align: left;
          list-style: none;
          background-color: #fff;
          -webkit-background-clip: padding-box;
                  background-clip: padding-box;
          -webkit-box-shadow: 0 6px 12px rgba(0, 0, 0, .175);
          -o-box-shadow: 0 6px 12px rgba(0, 0, 0, .175);
          -moz-box-shadow: 0 6px 12px rgba(0, 0, 0, .175);
                  box-shadow: 0 6px 12px rgba(0, 0, 0, .175);
        }';

		echo '.arfdate-dropdown-menu ul li {
            margin-left: 0em !important;
        }';

		echo esc_html( $arf_form_cls_prefix ) . ' .timepicker-picker .timepicker-hour,' . esc_html( $arf_form_cls_prefix ) . ' .timepicker-picker .timepicker-minute,' . esc_html( $arf_form_cls_prefix ) . ' .timepicker-picker .arf-glyphicon,' . esc_html( $arf_form_cls_prefix ) . ' .timepicker .arf_cal_minute,' . esc_html( $arf_form_cls_prefix ) . ' .timepicker .arf_cal_hour {color:' . esc_html( $arf_date_picker_text_color ) . ' !important; border:none; }';

		echo esc_html( $arf_form_cls_prefix ) . ' .timepicker-picker .arf-glyphicon::before{
            color:' . esc_html( $base_color ) . ' !important;
        }';

		echo esc_html( $arf_form_cls_prefix ) . ' .timepicker-picker .btn.btn-primary{
            background-color:' . esc_html( $base_color ) . ' !important; 
            border-color:' . esc_html( $base_color ) . ' !important;   
        }';

		echo esc_html( $arf_form_cls_prefix ) . ' .timepicker .arf_cal_minute:hover,
        ' . esc_html( $arf_form_cls_prefix ) . ' .timepicker .arf_cal_hour:hover {border-color:' . esc_html( $base_color ) . ' !important;  }';

		echo esc_html( $arf_form_cls_prefix ) . ' .sltstandard_time .btn-group .arfbtn.dropdown-toggle{
            border:' . esc_html( $field_border_width ) . ' ' . esc_html( $field_border_style ) . ' ' . esc_html( $field_border_color ) . " !important;
            background-color:".esc_html($field_bg_color)." !important;
            background-image:none;
            box-shadow:none;
            -webkit-box-shadow:none;
            -o-box-shadow:none;
            -moz-box-shadow:none;
            outline:0 !important;
            -moz-border-radius:" . esc_html( $field_border_radius ) . ' !important;
            -webkit-border-radius:' . esc_html( $field_border_radius ) . ' !important;
            -o-border-radius:' . esc_html( $field_border_radius ) . ' !important;
            border-radius:' . esc_html( $field_border_radius ) . ';
            padding:' . esc_html( $arf_field_inner_padding ) . ' !important;
            line-height: normal;
            font-size:' . esc_html( $arf_input_font_size ) . 'px;
            color:' . esc_html( $field_text_color ) . ';; 
            font-family:' . esc_html( $arf_input_font_family ) . ";
            ".esc_html($arf_input_font_style_str)."
            width:100%;
            margin-top:0px;    
        }";

		echo esc_html( $arf_form_cls_prefix ) . ' .sltstandard_time .btn-group.open .arfbtn.dropdown-toggle{';
			$border_radius_open = '0px';
		if ( isset( $field_border_radius ) && ! empty( $field_border_radius ) ) {
			$border_radius_open = str_replace( 'px', '', $field_border_radius );
			if ( $border_radius_open > 19 ) {
				if ( $border_radius_open > $arf_input_font_size ) {
					if ( $arf_input_font_size >= 40 ) {
						$border_radius_open = '36px';
					} elseif ( $arf_input_font_size >= 36 ) {
						$border_radius_open = '34px';
					} elseif ( $arf_input_font_size > 20 ) {
						$border_radius_open = $arf_input_font_size . 'px';
					} else {
						$border_radius_open = '20px';
					}
				} elseif ( $border_radius_open > 36 && $arf_input_font_size == 40 ) {
					$border_radius_open = '36px';
				} elseif ( $arf_input_font_size > 14 ) {
					$border_radius_open = $field_border_radius;
				} else {
					$border_radius_open = '20px';
				}
			} else {
				$border_radius_open = $field_border_radius;
			}
		}

			echo 'border-radius:' . esc_html( $border_radius_open ) . ';
            -moz-border-radius:' . esc_html( $border_radius_open ) . ';
            -webkit-border-radius:' . esc_html( $border_radius_open ) . ';
            -o-border-radius:' . esc_html( $border_radius_open ) . ';
        }';

		//phpcs:disable
		echo esc_html( $arf_form_cls_prefix ) . ' .sltstandard_time .btn-group:focus{
            border:' . esc_html( $field_border_width ) . ' ' . esc_html( $field_border_style ) . ' ' . esc_html( $field_border_color ) . ' !important;
            background-color:' . esc_html( $base_color ) . ';
            background-image:none;
            box-shadow:none;
            -webkit-box-shadow:none;
            -o-box-shadow:none;
            -moz-box-shadow:none;
            outline:0 !important;
            font-size:' . esc_html( $arf_input_font_size ) . 'px;
            color:' . esc_html( $field_text_color ) . ';
            font-family:' . esc_html( $arf_input_font_family ) . ";
            {$arf_input_font_style_str}
            width:100%;
            -moz-box-shadow:0px 0px 2px rgba(" . $arflitesettingcontroller->arflitehex2rgb( $base_color ) . ', 0.4);
            -webkit-box-shadow:0px 0px 2px rgba(' . $arflitesettingcontroller->arflitehex2rgb( $base_color ) . ', 0.4);
            -o-box-shadow:0px 0px 2px rgba(' . $arflitesettingcontroller->arflitehex2rgb( $base_color ) . ', 0.4);
            box-shadow:0px 0px 2px rgba(' . $arflitesettingcontroller->arflitehex2rgb( $base_color ) . ', 0.4);
            margin-top:0px;    
            min-height:' . ( ( $arf_input_font_size ) + ( 2 * (int) $field_border_width ) ) . 'px;
        }';
		//phpcs:enable

		echo esc_html( $arf_form_cls_prefix ) . ' .bootstrap-datetimepicker-widget .timepicker-picker th,
        ' . esc_html( $arf_form_cls_prefix ) . ' .bootstrap-datetimepicker-widget .timepicker-picker td{
        padding: 0;
        }';

		echo esc_html( $arf_form_cls_prefix ) . ' .arf_time_main_controls .bootstrap-datetimepicker-widget table{
            overflow: hidden !important;
            border-radius:4px 4px 4px 4px !important;
            -moz-border-radius:4px 4px 4px 4px !important;
            -webkit-border-radius:4px 4px 4px 4px !important;
            -o-border-radius:4px 4px 4px 4px !important;
            -webkit-transform: translateZ(0);
            -o-transform:translateZ(0);
            background: #ffffff !important;
            border: transparent !important;
        }';

		echo esc_html( $arf_form_cls_prefix ) . ' .timepicker-picker .btn {
            display: inline-block;
            padding: 6px 12px;
            margin-bottom: 0;
            font-size: 14px;
            font-weight: 400;
            line-height: 1.42857143;
            text-align: center;
            white-space: nowrap;
            vertical-align: middle;
            -ms-touch-action: manipulation;
            touch-action: manipulation;
            cursor: pointer;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            background-image: none;
            border: 1px solid transparent;
            border-radius: 4px;
            -webkit-border-radius: 4px;
            -o-border-radius: 4px;
            -moz-border-radius: 4px;
            background: none;
            box-shadow: none;
            -webkit-box-shadow: none;
            -o-box-shadow: none;
            -moz-box-shadow: none;
        }';

		echo esc_html( $arf_form_cls_prefix ) . ' .timepicker-picker table,
        ' . esc_html( $arf_form_cls_prefix ) . ' .timepicker-hours table,
        ' . esc_html( $arf_form_cls_prefix ) . ' .timepicker-minutes table {
            font-size: 14px;
        }';

		echo esc_html( $arf_form_cls_prefix ) . ' .ardropdown-menu.bootstrap-timepicker-widget tr, 
        ' . esc_html( $arf_form_cls_prefix ) . ' .ardropdown-menu.bootstrap-timepicker-widget td, 
        ' . esc_html( $arf_form_cls_prefix ) . ' .ardropdown-menu.bootstrap-timepicker-widget table {
            border:none;
            vertical-align:middle;
            color:#333333;
            font-size:13px;
            box-shadow:none !important;
            -webkit-box-shadow:none !important;
            -moz-box-shadow:none !important;
            -o-box-shadow:none !important;
            background:none;
        }';

		echo esc_html( $arf_form_cls_prefix ) . ' .ardropdown-menu.bootstrap-timepicker-widget {
            z-index:99999;
            max-width:160px;
        }';

		echo esc_html( $arf_form_cls_prefix ) . ' .sltstandard_time .btn-group.open .arfdropdown-menu { background-color: ' . esc_html( $field_bg_color ) . ' !important; }';

		echo esc_html( $arf_form_cls_prefix ) . ' .sltstandard_time .btn-group.open .arfdropdown-menu:focus { background-color: ' . esc_html( $field_focus_bg_color ) . ' !important; }';

		echo esc_html( $arf_form_cls_prefix ) . ' .sltstandard_time .btn-group.open .arfdropdown-menu.open { 
            border-top:' . esc_html( $field_border_width ) . ' ' . esc_html( $field_border_style ) . ' ' . esc_html( $field_border_color ) . ';
        }';

		echo esc_html( $arf_form_cls_prefix ) . ' .datepicker .topdateinfo{';
			echo 'background:' . esc_html( $base_color ) . ';';
		echo '}';

	}

	/** Form Fields Styling */

	/** Field Description Styling */
	echo esc_html($arf_form_cls_prefix) . ' .arfformfield .arf_field_description{';
		echo 'margin:2px 0px 0px 0px;padding:0;';
		echo 'font-family:' . esc_html( $arf_input_font_family ) . ';';
		echo 'font-size:' . esc_html( $description_font_size ) . 'px;';
		echo 'text-align:' . esc_html( $description_align ) . ';';
		echo 'color:' . esc_html( $field_label_txt_color ) . ';';
		echo 'max-width:100%;width:auto; line-height: 20px;';
	echo '}';
	/** Field Description Styling */
}
/** Field Level Styling */

$use_saved = isset( $use_saved ) ? $use_saved : '';
do_action( 'arflite_outsite_print_style', $new_values, $use_saved, $form_id );
