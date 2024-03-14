<?php
/**
 * File to generate CSS for the each form for standard/rounded style
 *
 * @package ARFormslite
 */

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

$form_id = isset( $form_id ) ? $form_id : '';


if ( ! isset( $arflite_preview ) ) {
	$arflite_preview = false;
}

foreach ( $new_values as $k => $v ) {

	if ( ( preg_match( '/color/', $k ) || in_array( $k, array( 'arferrorbgsetting', 'arferrorbordersetting', 'arferrortextsetting' ) ) ) && ! in_array( $k, array( 'arfcheckradiocolor' ) ) ) {
		if ( strpos( $v, '#' ) === false ) {
			$new_values[ $k ] = '#' . $v;
		} else {
			$new_values[ $k ] = $v;
		}
	} else {
		$new_values[ $k ] = $v;
	}
}

global $arflitesettingcontroller,$arfliteformcontroller,$arformsmain, $arflitefieldhelper, $arfliteform, $ARFLiteMdlDb;

/**Basic Styling Options */

	/** color related variables */
	$arf_mainstyle = ! empty( $new_values['arfinputstyle'] ) ? $new_values['arfinputstyle'] : '';

if ( $is_form_save ) {
	$arf_mainstyle = 'standard rounded';
}

	$form_bg_color              = ! empty( $new_values['arfmainformbgcolorsetting'] ) ? sanitize_text_field( $new_values['arfmainformbgcolorsetting'] ) : '';
	$form_title_color           = isset( $new_values['arfmainformtitlecolorsetting'] ) ? sanitize_text_field( $new_values['arfmainformtitlecolorsetting'] ) : '';
	$form_border_color          = isset( $new_values['arfmainfieldsetcolor'] ) ? sanitize_text_field( $new_values['arfmainfieldsetcolor'] ) : '';
	$form_border_shadow_color   = isset( $new_values['arfmainformbordershadowcolorsetting'] ) ? sanitize_text_field( $new_values['arfmainformbordershadowcolorsetting'] ) : '';
	$base_color                 = isset( $new_values['arfmainbasecolor'] ) ? sanitize_text_field( $new_values['arfmainbasecolor'] ) : '';
	$field_text_color           = isset( $new_values['text_color'] ) ? sanitize_text_field( $new_values['text_color'] ) : '';
	$field_border_color         = isset( $new_values['border_color'] ) ? sanitize_text_field( $new_values['border_color'] ) : '';
	$field_bg_color             = isset( $new_values['bg_color'] ) ? sanitize_text_field( $new_values['bg_color'] ) : '';
	$field_focus_bg_color       = isset( $new_values['arfbgactivecolorsetting'] ) ? sanitize_text_field( $new_values['arfbgactivecolorsetting'] ) : '';
	$field_error_bg_color       = isset( $new_values['arferrorbgcolorsetting'] ) ? sanitize_text_field( $new_values['arferrorbgcolorsetting'] ) : '';
	$field_focus_border_color   = ! empty( $new_values['arfborderactivecolorsetting'] ) ? sanitize_text_field( $new_values['arfborderactivecolorsetting'] ) : '#fff';
	$field_label_txt_color      = isset( $new_values['label_color'] ) ? sanitize_text_field( $new_values['label_color'] ) : '';
	$prefix_suffix_bg_color     = isset( $new_values['prefix_suffix_bg_color'] ) ? str_replace( '##', '#', sanitize_text_field( $new_values['prefix_suffix_bg_color'] ) ) : '';
	$prefix_suffix_icon_color   = isset( $new_values['prefix_suffix_icon_color'] ) ? sanitize_text_field( $new_values['prefix_suffix_icon_color'] ) : '';
	$tooltip_bg_color           = isset( $new_values['arf_tooltip_bg_color'] ) ? sanitize_text_field( $new_values['arf_tooltip_bg_color'] ) : '';
	$tooltip_font_color         = isset( $new_values['arf_tooltip_font_color'] ) ? sanitize_text_field( $new_values['arf_tooltip_font_color'] ) : '';
	$arf_date_picker_text_color = isset( $new_values['arfdatepickertextcolorsetting'] ) ? sanitize_text_field( $new_values['arfdatepickertextcolorsetting'] ) : '#46484d';
	$submit_text_color          = isset( $new_values['arfsubmittextcolorsetting'] ) ? sanitize_text_field( $new_values['arfsubmittextcolorsetting'] ) : '';
	$submit_bg_color            = isset( $new_values['submit_bg_color'] ) ? sanitize_text_field( $new_values['submit_bg_color'] ) : '';
	$submit_bg_color_hover      = isset( $new_values['arfsubmitbuttonbgcolorhoversetting'] ) ? str_replace( '##', '#', sanitize_text_field( $new_values['arfsubmitbuttonbgcolorhoversetting'] ) ) : '';
	$submit_border_color        = isset( $new_values['arfsubmitbordercolorsetting'] ) ? sanitize_text_field( $new_values['arfsubmitbordercolorsetting'] ) : '';
	$submit_shadow_color        = isset( $new_values['arfsubmitshadowcolorsetting'] ) ? str_replace( '##', '#', sanitize_text_field( $new_values['arfsubmitshadowcolorsetting'] ) ) : '';
	$success_bg_color           = isset( $new_values['arfsucessbgcolorsetting'] ) ? sanitize_text_field( $new_values['arfsucessbgcolorsetting'] ) : '';
	$success_border_color       = isset( $new_values['arfsucessbordercolorsetting'] ) ? sanitize_text_field( $new_values['arfsucessbordercolorsetting'] ) : '';
	$success_text_color         = isset( $new_values['arfsucesstextcolorsetting'] ) ? sanitize_text_field( $new_values['arfsucesstextcolorsetting'] ) : '';
	$error_bg_color             = isset( $new_values['arfformerrorbgcolorsettings'] ) ? sanitize_text_field( $new_values['arfformerrorbgcolorsettings'] ) : '';
	$error_border_color         = isset( $new_values['arfformerrorbordercolorsettings'] ) ? sanitize_text_field( $new_values['arfformerrorbordercolorsettings'] ) : '';
	$error_txt_color            = isset( $new_values['arfformerrortextcolorsettings'] ) ? sanitize_text_field( $new_values['arfformerrortextcolorsettings'] ) : '';
	$arferrorstylecolor         = isset( $new_values['arfvalidationbgcolorsetting'] ) ? sanitize_text_field( $new_values['arfvalidationbgcolorsetting'] ) : '';
	$arferrorstylecolorfont     = isset( $new_values['arfvalidationtextcolorsetting'] ) ? sanitize_text_field( $new_values['arfvalidationtextcolorsetting'] ) : '';

	/** color related variables */

	/** Fonts Related variables */

	$arf_title_font_family = isset( $new_values['arftitlefontfamily'] ) ? sanitize_text_field( $new_values['arftitlefontfamily'] ) : '';

	$arf_title_font_size = isset( $new_values['form_title_font_size'] ) ? intval( $new_values['form_title_font_size'] ) . 'px' : '24px';

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

	$arf_label_font_family = isset( $new_values['font'] ) ? sanitize_text_field( $new_values['font'] ) : '';
	$arf_label_font_size   = isset( $new_values['font_size'] ) ? intval( $new_values['font_size'] ) : '';
	$arf_label_font_weight = isset( $new_values['weight'] ) ? sanitize_text_field( $new_values['weight'] ) : 'normal';

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
	$arf_input_font_weight = isset( $new_values['check_weight'] ) ? sanitize_text_field( $new_values['check_weight'] ) : 'normal';

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
	$arf_submit_btn_font_weight = isset( $new_values['arfsubmitweightsetting'] ) ? sanitize_text_field( $new_values['arfsubmitweightsetting'] ) : '';

	$arf_submit_font_style_arr = explode( ',', $arf_submit_btn_font_weight );
	$arf_submit_font_style_str = '';
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

	$arf_form_padding = ( '' == $new_values['arfmainfieldsetpadding'] ) ? '0px' : sanitize_text_field( $new_values['arfmainfieldsetpadding'] );
	$arf_form_padding_tablet = !empty( $new_values['arfmainfieldsetpadding_tablet'] ) ? $new_values['arfmainfieldsetpadding_tablet'] : '';
	$arf_form_padding_mobile = !empty( $new_values['arfmainfieldsetpadding_mobile'] ) ? $new_values['arfmainfieldsetpadding_mobile'] : '';


	/** Form Border Options */
	$arf_form_border_type   = isset( $new_values['form_border_shadow'] ) ? sanitize_text_field( $new_values['form_border_shadow'] ) : 'border';
	$arf_form_border_width  = ! empty( $new_values['fieldset'] ) ? sanitize_text_field( $new_values['fieldset'] ) . 'px' : '0';
	$arf_form_border_radius = ( '' == $new_values['arfmainfieldsetradius'] ) ? '0px' : intval( $new_values['arfmainfieldsetradius'] ) . 'px';
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
	$arfmainfield_opacity       = isset( $new_values['arfmainfield_opacity'] ) ? sanitize_text_field( $new_values['arfmainfield_opacity'] ) : '';
	$arf_required_indicator     = isset( $new_values['arf_req_indicator'] ) ? intval( $new_values['arf_req_indicator'] ) : '0';
	$field_margin               = empty( $new_values['arffieldmarginssetting'] ) ? '0' : intval( $new_values['arffieldmarginssetting'] ) . 'px';
	$placeholder_opacity        = isset( $new_values['arfplaceholder_opacity'] ) ? floatval( $new_values['arfplaceholder_opacity'] ) : '';
	$arf_field_inner_padding    = isset( $new_values['arffieldinnermarginssetting'] ) ? sanitize_text_field( $new_values['arffieldinnermarginssetting'] ) : 0;
	$field_border_width         = empty( $new_values['arffieldborderwidthsetting'] ) ? '0' : intval( $new_values['arffieldborderwidthsetting'] ) . 'px';
	$field_border_radius        = ( '' == $new_values['border_radius'] ) ? '0px' : intval( $new_values['border_radius'] ) . 'px';
	
	$field_border_radius_tablet = ( isset( $new_values['border_radius_tablet'] ) && $new_values['border_radius_tablet'] != '' ) ? $new_values['border_radius_tablet'] . 'px' : '0px';
	$field_border_radius_mobile = ( isset( $new_values['border_radius_mobile'] ) && $new_values['border_radius_mobile'] != '' ) ? $new_values['border_radius_mobile'] . 'px' : '0px'; 
	$field_border_style         = isset( $new_values['arffieldborderstylesetting'] ) ? sanitize_text_field( $new_values['arffieldborderstylesetting'] ) : '';

	$fieldpadding   = explode( ' ', $arf_field_inner_padding );
	$fieldpadding_1 = $fieldpadding[0];
	$fieldpadding_1 = str_replace( 'px', '', $fieldpadding_1 );
	$fieldpadding_2 = 0;
if ( count( $fieldpadding ) > 1 ) {
	$fieldpadding_2 = $fieldpadding[1];
	$fieldpadding_2 = str_replace( 'px', '', $fieldpadding_2 );
}

	/** Input Field Option */

	/** Checkbox/Radio Style */
	$arfcheck_style_name = isset( $new_values['arfcheckradiostyle'] ) ? sanitize_text_field( $new_values['arfcheckradiostyle'] ) : '';
	/** Checkbox/Radio Style */

	/** Input Field Options */

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

$arf_form_cls_prefix = "#arffrm_{$form_id}_container";

$arf_checkbox_not_admin        = '';
$arf_prefix_cls                = '.arf_prefix';
$arf_suffix_cls                = '.arf_suffix';
$arf_prefix_suffix_wrapper_cls = '.arf_prefix_suffix_wrapper';
$input_fields                  = $arflitefieldhelper->arflite_input_field_keys();
$other_fields                  = $arflitefieldhelper->arflite_other_fields_keys();
$loaded_field                  = array_merge( $input_fields, $other_fields );
if ( ! empty( $is_form_save ) && true == $is_form_save ) {

	$arf_form_cls_prefix           = ".arflite_main_div_{$form_id}";
	$arf_prefix_cls                = '.arf_editor_prefix_icon';
	$arf_suffix_cls                = '.arf_editor_suffix_icon';
	$arf_prefix_suffix_wrapper_cls = '.arf_editor_prefix_suffix_wrapper';

	$arf_hide_label          = true;
	$arf_checkbox_not_admin  = ':not(.arf_enable_checkbox_image_editor):not(.arf_enable_radio_image_editor)';
	$is_prefix_suffix_enable = true;
	$is_checkbox_img_enable  = true;
	$is_radio_img_enable     = true;
} else {

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
		$submit_btn_font_family_gurl = $googlefontbaseurl . urlencode( $arf_submit_btn_font_family ) . $subset . $swap_display;
		echo '@import url(' . esc_url( $submit_btn_font_family_gurl ) . ');';
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
echo esc_html( $arf_form_cls_prefix ) . '{ max-width:' . esc_html( $form_width ) . '' . esc_html( $form_width_unit ) . '; margin:0 auto;}';

if(!empty( $form_width_tablet )){
	echo '.arfdevicetablet '. esc_html( $arf_form_cls_prefix ) . '{ max-width:' . esc_html( $form_width_tablet ) . '' . esc_html( $form_width_unit_tablet ) . '; margin:0 auto;}';
	echo '@media all and (max-width:768px){';
		echo esc_html( $arf_form_cls_prefix ) . '{ max-width:' . esc_html( $form_width_tablet ) . '' . esc_html( $form_width_unit_tablet ) . '; margin:0 auto;}';
	echo '}';
}
if(!empty( $form_width_mobile )){
	echo '.arfdevicemobile '. esc_html( $arf_form_cls_prefix ) . '{ max-width:' . esc_html( $form_width_mobile ) . '' . esc_html( $form_width_unit_mobile ) . ' !important; margin:0 auto;}';
	echo '@media all and (max-width:576px){';
		echo esc_html( $arf_form_cls_prefix ) . '{ max-width:' . esc_html( $form_width_mobile ) . '' . esc_html( $form_width_unit_mobile ) . '!important; margin:0 auto;}';
	echo '}';
}

echo esc_html( $arf_form_cls_prefix ) . ' *{';
	echo 'box-sizing:border-box;';
	echo '-webkit-box-sizing:border-box;';
	echo '-o-box-sizing:border-box;';
	echo '-moz-box-sizing:border-box;';
echo '}';

if ( false == $is_form_save ) {
	echo esc_html( $arf_form_cls_prefix ) . ' form{ text-align:' . esc_html( $arf_form_alignment ) . '; }';
} else {
	echo esc_html( $arf_form_cls_prefix ) . '{ text-align:' . esc_html( $arf_form_alignment ) . '; }';
}

echo esc_html( $arf_form_cls_prefix ) . ' .arf_fieldset{';
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
    echo ".arfdevicetablet ".esc_html($arf_form_cls_prefix) . " .arf_fieldset{";
        echo "padding :".esc_html($arf_form_padding_tablet) . ";";
    echo "}";
    echo "@media all and (max-width:768px){";
        echo esc_html($arf_form_cls_prefix) ." .arf_fieldset{";
            echo "padding:".esc_html($arf_form_padding_tablet).";";
        echo "}";
    echo "}";
}
if( !empty($arf_form_padding_mobile) && $arf_form_padding_mobile != 0 ){
    echo ".arfdevicemobile ".esc_html($arf_form_cls_prefix) . " .arf_fieldset{";
        echo "padding :".esc_html($arf_form_padding_mobile) .";";
    echo "}";
    echo "@media all and (max-width:576px){";
        echo esc_html($arf_form_cls_prefix) ." .arf_fieldset{";
            echo "padding:".esc_html($arf_form_padding_mobile) . ";";
        echo "}";
    echo "}";
}

echo esc_html($arf_form_cls_prefix) . " .arftitlecontainer{margin:".esc_html($arf_form_title_margin)."; text-align:".esc_html($arf_form_title_alignment).";}";
echo esc_html($arf_form_cls_prefix) . ' .formtitle_style{';
	echo "color:".esc_html($form_title_color).";";
	echo 'font-family:' .esc_html( stripslashes($arf_title_font_family)) . ';';
	echo "font-size:".esc_html($arf_title_font_size).";";
	echo esc_html($arf_title_font_style_str);
echo '}';

if ( ! empty( $form->description ) ) {
	echo esc_html($arf_form_cls_prefix) . ' div.formdescription_style{';
		echo "text-align:".esc_html($arf_form_title_alignment).";";
		echo "color:".esc_html($form_title_color).";";
		echo "font-family:".esc_html($arf_title_font_family).";";
		echo "font-size:".esc_html($description_font_size)."px;";
	echo '}';
} elseif ( $is_form_save ) {
	echo esc_html($arf_form_cls_prefix) . ' .arfeditorformdescription{';
		echo "text-align:".esc_html($arf_form_title_alignment).";";
		echo "color:".esc_html($form_title_color).";";
		echo "font-family:".esc_html($arf_title_font_family).";";
		echo "font-size:".esc_html($description_font_size)."px;";
	echo '}';
}

/** Form Level Styling */

/** Success/Error Message Styling */
if ( empty( $is_form_save ) ) {
	echo esc_html( $arf_form_cls_prefix ) . ' #arf_message_success_popup,';
	echo esc_html( $arf_form_cls_prefix ) . ' #arf_message_success{';
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

	echo esc_html( $arf_form_cls_prefix ) . ' #arf_message_success_popup .msg-detail::before,';
	echo esc_html( $arf_form_cls_prefix ) . ' #arf_message_success .msg-detail::before{';
		echo 'background-image: url(data:image/svg+xml;base64,' . esc_html( base64_encode('<svg width="60" height="60" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0px" y="0px" viewBox="0 0 52 52" enable-background="new 0 0 52 52" xml:space="preserve"><g><path fill="' . esc_attr( $success_text_color ) . '" d="M26,0C11.66,0,0,11.66,0,26s11.66,26,26,26s26-11.66,26-26S40.34,0,26,0z M26,50C12.77,50,2,39.23,2,26   S12.77,2,26,2s24,10.77,24,24S39.23,50,26,50z"/><path fill="' . esc_attr( $success_text_color ) . '" d="M38.25,15.34L22.88,32.63l-9.26-7.41c-0.43-0.34-1.06-0.27-1.41,0.16c-0.35,0.43-0.28,1.06,0.16,1.41l10,8   C22.56,34.93,22.78,35,23,35c0.28,0,0.55-0.11,0.75-0.34l16-18c0.37-0.41,0.33-1.04-0.08-1.41C39.25,14.88,38.62,14.92,38.25,15.34   z"/></g></svg>' )) . ');';
		echo "content:'';width: 60px;height: 60px;display: block;margin: 0 auto;background-repeat: no-repeat;position:relative;";// phpcs:ignore
	echo '}';

	echo esc_html( $arf_form_cls_prefix ) . ' .frm_error_style{
        width:100%; 
        display: inline-block; 
        float:none; 
        min-height:35px; 
        margin: 10px 0 10px 0;
        border: 1px solid ' . esc_html( $error_border_color ) . ';
        background: ' . esc_html( $error_bg_color ) . '; 
        color:' . esc_html( $error_txt_color ) . ';
        font-family:' . esc_html( stripslashes( $arf_validation_font_family ) ) . '; 
        font-weight:normal; 
        -moz-border-radius:3px;  
        -webkit-border-radius:3px; 
        -o-border-radius:3px; 
        border-radius:3px;
        font-size:20px; 
        word-break:break-all;';
	echo '}';// phpcs:ignore

	echo esc_html( $arf_form_cls_prefix ) . ' .msg-detail { float:left; width: 100%; padding:20px 10px 20px 10px; min-height: 37px; line-height: 37px; text-shadow: none; }';

	echo esc_html( $arf_form_cls_prefix ) . ' .msg-detail p { padding:0 !important; margin:0 !important; }';

	$msg_font_size = '20px';

	echo esc_html( $arf_form_cls_prefix ) . ' .msg-title-success { padding:0px 0 0 10px; vertical-align:middle; display:inline-block; font-weight:bold;}';

	echo esc_html( $arf_form_cls_prefix ) . ' .msg-description-success { letter-spacing:0.1px; padding:10px 0 10px 0px; width:100%; vertical-align:middle; display:inline-block; }';

	echo esc_html( $arf_form_cls_prefix ) . ' .msg-title-error { padding:5px 0 0 10px; vertical-align:middle; display:inline-block; }';

	echo esc_html( $arf_form_cls_prefix ) . ' .msg-description-error { padding:7px 0 0 10px; letter-spacing:0.1px; vertical-align:middle; display:inline; }';

	echo esc_html( $arf_form_cls_prefix ) . ' .arf_res_front_msg_desc { padding:10px 0 10px 0px; letter-spacing:0.1px; width:100%; vertical-align:middle; display:inline-block; text-align:center; }';

	echo esc_html( $arf_form_cls_prefix ) . ' .frm_error_style .msg-detail::before{';
		echo 'background-image: url(data:image/svg+xml;base64,' . esc_html(base64_encode( '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0" y="0" viewBox="10 10 100 100" enable-background="new 10 10 100 100" xml:space="preserve" height="60" width="60"><g><circle fill="none" stroke="' . esc_attr( $error_txt_color ) . '" stroke-width="4" stroke-miterlimit="10" cx="60" cy="60" r="47"></circle><line fill="none" stroke="' . esc_attr( $error_txt_color ) . '" stroke-width="4" stroke-miterlimit="10" x1="81.214" y1="81.213" x2="38.787" y2="38.787"></line><line fill="none" stroke="' . esc_attr( $error_txt_color ) . '" stroke-width="4" stroke-miterlimit="10" x1="38.787" y1="81.213" x2="81.214" y2="38.787"></line></g></svg>' )) . ');';
		echo "content:'';width: 60px;height: 60px;display: block;margin: 0 auto;background-repeat: no-repeat;position:relative;";
	echo '}';

	echo esc_html( $arf_form_cls_prefix ) . ' .arf_res_front_msg_desc {';
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
echo esc_html( $arf_form_cls_prefix ) . ' .arf_submit_div{ margin :' . esc_html( $submit_margin ) . '}';
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
	echo esc_html($arf_submit_font_style_str);
	echo 'cursor:pointer;outline:none;line-height:1.3;padding:0;';
	echo 'box-shadow: ' . esc_html( $submit_xoffset_shadow ) . ' ' . esc_html( $submit_yoffset_shadow ) . ' ' . esc_html( $submit_blur_shadow ) . ' ' . esc_html( $submit_spread_shadow ) . ' ' . esc_html( $submit_shadow_color ) . ';';
	echo '-webkit-box-shadow: ' . esc_html( $submit_xoffset_shadow ) . ' ' . esc_html( $submit_yoffset_shadow ) . ' ' . esc_html( $submit_blur_shadow ) . ' ' . esc_html( $submit_spread_shadow ) . ' ' . esc_html( $submit_shadow_color ) . ';';
	echo '-o-box-shadow: ' . esc_html( $submit_xoffset_shadow ) . ' ' . esc_html( $submit_yoffset_shadow ) . ' ' . esc_html( $submit_blur_shadow ) . ' ' . esc_html( $submit_spread_shadow ) . ' ' . esc_html( $submit_shadow_color ) . ';';
	echo '-moz-box-shadow: ' . esc_html( $submit_xoffset_shadow ) . ' ' . esc_html( $submit_yoffset_shadow ) . ' ' . esc_html( $submit_blur_shadow ) . ' ' . esc_html( $submit_spread_shadow ) . ' ' . esc_html( $submit_shadow_color ) . ';';

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
    
    echo ".arfdevicetablet ".esc_html($arf_form_cls_prefix ). ".arfsubmitbutton .arf_submit_btn{";
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
    
    echo ".arfdevicemobile ".esc_html($arf_form_cls_prefix). ".arfsubmitbutton .arf_submit_btn{";
        if( '' == $submit_width_mobile ){
            echo "min-width:".esc_html($submit_auto_width)."px;";
        } else {
            echo "width:".esc_html($submit_width_mobile).";";
        }
    echo "}";
    echo "@media all and (max-width:576px){";
        echo esc_html($arf_form_cls_prefix). " .arfsubmitbutton .arf_submit_btn{";
            if( '' == esc_html($submit_width_mobile) ){
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
		echo 'height:' . esc_html( $arf_submit_btn_font_size ) . 'px;';
		echo 'border:' . ceil( esc_html( $arf_submit_btn_font_size ) / 8 ) . 'px solid ' . esc_html( $submit_text_color ) . ';';// phpcs:ignore
		echo 'border-bottom:' . ceil( esc_html( $arf_submit_btn_font_size ) / 8 ) . 'px solid transparent;';// phpcs:ignore
	echo '}';

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
	echo esc_html( $arf_form_cls_prefix ) . ' .arfsubmitbutton .arf_submit_btn.arf_submit_btn_border{';
		echo 'background:transparent' . ( ! empty( $submit_bg_img ) ? ' url(' . esc_url( $submit_bg_img ) . ') ' : '' ) . ' !important;';
	if ( ! empty( $submit_bg_img ) ) {
		echo 'color:transparent;';
	} else {
		echo 'color:' . esc_html( $submit_bg_color ) . ';';
	}
		echo 'border:' . ( ( esc_html($submit_border_width) > 0 ) ? esc_html($submit_border_width) : '2px' ) . ' solid ' . esc_html( $submit_bg_color ) . ';';
	echo '}';

	echo esc_html( $arf_form_cls_prefix ) . ' .arfsubmitbutton .arf_submit_btn.arf_submit_btn_border.arf_active_loader .arfsubmitloader{';
		echo 'width:' . esc_html( $arf_submit_btn_font_size ) . 'px;';
		echo 'height:' . esc_html( $arf_submit_btn_font_size ) . 'px;';
		echo 'border:' . esc_html( ceil( $arf_submit_btn_font_size ) / 8 ) . 'px solid ' . esc_html( $submit_text_color ) . ';';
		echo 'border-bottom:' . esc_html( ceil( $arf_submit_btn_font_size ) / 8 ) . 'px solid transparent;';
	echo '}';

	echo esc_html( $arf_form_cls_prefix ) . ' .arfsubmitbutton .arf_submit_btn.arf_submit_btn_border.arf_active_loader,';
	echo esc_html( $arf_form_cls_prefix ) . ' .arfsubmitbutton .arf_submit_btn.arf_submit_btn_border.arf_complete_loader,';
	echo esc_html( $arf_form_cls_prefix ) . ' .arfsubmitbutton .arf_submit_btn.arf_submit_btn_border:hover{';
	if ( ! empty( $submit_hover_bg_img ) ) {
		echo 'background-image:url(' . esc_url( $submit_hover_bg_img ) . ');';
	} else {
		echo 'background:' . esc_html( $submit_bg_color ),' !important;';
		echo 'border:' . ( ( esc_html($submit_border_width) > 0 ) ? esc_html($submit_border_width) : '2px' ) . ' solid ' . esc_html( $submit_bg_color ) . ';';
		echo 'color:' . esc_html( $submit_text_color ) . ';';
	}
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
		echo 'height:' . esc_html( $arf_submit_btn_font_size ) . 'px;';
		echo 'border:' . ceil( esc_html( $arf_submit_btn_font_size ) / 8 ) . 'px solid ' . esc_html( $submit_bg_color ) . ';';// phpcs:ignore
		echo 'border-bottom:' . ceil( esc_html( $arf_submit_btn_font_size ) / 8 ) . 'px solid transparent;';// phpcs:ignore
	echo '}';

	echo esc_html( $arf_form_cls_prefix ) . ' .arfsubmitbutton .arf_submit_btn.arf_submit_btn_reverse_border.arf_active_loader,';
	echo esc_html( $arf_form_cls_prefix ) . ' .arfsubmitbutton .arf_submit_btn.arf_submit_btn_reverse_border.arf_complete_loader,';
	echo esc_html( $arf_form_cls_prefix ) . ' .arfsubmitbutton .arf_submit_btn.arf_submit_btn_reverse_border:hover{';
	if ( ! empty( $submit_hover_bg_img ) ) {
		echo 'background-image:url(' . esc_url( $submit_hover_bg_img ) . ');';
	} else {
		echo 'background:transparent !important;';
		echo 'color:' . esc_html( $submit_bg_color ) . ';';
		echo 'border:' . ( ( esc_html($submit_border_width) > 0 ) ? esc_html($submit_border_width) : '2px' ) . ' solid ' . esc_html( $submit_bg_color ) . ';';
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
	echo 'border-top:' . ceil( esc_html( $arf_submit_btn_font_size ) / 8 ) . 'px solid ' . esc_html( $submit_text_color ) . ';';// phpcs:ignore
}
if ( preg_match( '/reverse border/', $arfsubmitbuttonstyle ) ) {
	echo 'border-right:' . ceil( esc_html( $arf_submit_btn_font_size ) / 8 ) . 'px solid ' . esc_html( $submit_bg_color ) . ';';// phpcs:ignore
	echo 'border-top:' . ceil( esc_html( $arf_submit_btn_font_size ) / 8 ) . 'px solid ' . esc_html( $submit_bg_color ) . ';';// phpcs:ignore
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
	/** Field Label Styling */

	echo esc_html( $arf_form_cls_prefix ) . ' label.arf_main_label{';
		echo 'display: block;';
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
	} elseif ( 'top' == $arf_label_position ) {
		echo 'width: 100%';
	}
	echo '}';

	if ( $is_form_save ) {
		echo esc_html( $arf_form_cls_prefix ) . ' .top_container label.arf_main_label{';
			echo 'width: 100% !important;';
		echo '}';
		echo esc_html( $arf_form_cls_prefix ) . ' .left_container label.arf_main_label{';
			echo 'display:inline-block;';
			echo 'float:left;';
			echo 'width:' . esc_html( $arf_label_width ) . 'px;';
		echo '}';

		echo esc_html( $arf_form_cls_prefix ) . ' .right_container label.arf_main_label{';
			echo 'display:inline-block;';
			echo 'float:right;';
			echo 'width:' . esc_html( $arf_label_width ) . 'px;';
		echo '}';
	}


	echo esc_html( $arf_form_cls_prefix ) . ' .arfformfield{';
		echo 'margin-bottom:' . esc_html( $field_margin ) . ';';
	echo '}';

	echo esc_html( $arf_form_cls_prefix ) . ' .arfcheckrequiredfield{ color:' . esc_html( $field_label_txt_color ) . '!important; }';

	echo esc_html( $arf_form_cls_prefix ) . ' .arfformfield .controls{ width:' . esc_html( $arf_input_field_width ) . '' . esc_html( $arf_input_field_width_unit ) . '}';


	if(!empty( $arf_input_field_width_tablet )){
        
        echo ".arfdevicetablet " .esc_html($arf_form_cls_prefix) . " .arfformfield .controls{ width: ".esc_html($arf_input_field_width_tablet).esc_html($arf_input_field_width_unit_tablet)." }";
        echo "@media all and (max-width:768px){";
            echo esc_html($arf_form_cls_prefix) . " .arfformfield .controls{ width: ".esc_html($arf_input_field_width_tablet).esc_html($arf_input_field_width_unit_tablet)." }";
        echo "}";
    }
    if( !empty($arf_input_field_width_mobile)){

        echo ".arfdevicemobile " .esc_html($arf_form_cls_prefix) . " .arfformfield .controls{ width: ".esc_html($arf_input_field_width_mobile).esc_html($arf_input_field_width_unit_mobile)." }";
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
			echo "border-right-color:".esc_html($arferrorstylecolor).";";
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
			echo 'background:' .esc_html($arferrorstylecolor). ';';
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
		if ( 'absolute' == $arf_standard_error_position ) {
			echo 'display: contents;';
		}

		echo '}';

		echo esc_html( $arf_form_cls_prefix ) . ' .help-block ul{ margin:0; }';

		echo esc_html( $arf_form_cls_prefix ) . ' .help-block ul li{';
			echo 'color:' . esc_html( $arferrorstylecolor ) . ';';
			echo 'font-family:' . esc_html( $arf_validation_font_family ) . ';';
			echo 'font-size:' . esc_html( $arf_validation_font_size ) . ';';
			echo 'height: 0;';
		echo '}';
	}
	/** Field Level Error styling */

	/** Form Fields Styling */
	if ( array_intersect( $loaded_field, $common_field_type_styling ) ) {
		echo esc_html( $arf_form_cls_prefix ) . ' .arfformfield .controls input:not(.inplace_field):not(.arf_field_option_input_text)' . ( ( in_array( 'phone', $loaded_field ) ) ? ",".esc_html($arf_form_cls_prefix)." .arfformfield .controls input[type=tel]:not(.inplace_field):not(.arf_field_option_input_text)" : '' ) . '{';
		if ( 1 == $arfmainfield_opacity ) {
			echo 'background:transparent;';
		} else {
			echo 'background:' . esc_html( $field_bg_color ) . ';';
		}
			echo 'width:100% !important;';
			echo 'font-family:' . esc_html( $arf_input_font_family ) . ';';
			echo 'font-size:' . esc_html( $arf_input_font_size ) . 'px;';
			echo esc_html( $arf_input_font_style_str );
			echo 'padding:' . esc_html( $arf_field_inner_padding ) . ' !important;';
			echo 'direction:' . esc_html( $arf_input_field_direction ) . ';';
			echo 'border:' . esc_html( $field_border_width ) . ' ' . esc_html( $field_border_style ) . ' ' . esc_html( $field_border_color ) . ';';
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
		echo '}';

		if( !empty( $field_border_radius_tablet )){

			echo '.arfdevicetablet ' .esc_html( $arf_form_cls_prefix ) . ' .arfformfield .controls input:not(.inplace_field):not(.arf_field_option_input_text)' . ( ( in_array( 'phone', $loaded_field ) ) ? ",".esc_html($arf_form_cls_prefix)." .arfformfield .controls input[type=tel]:not(.inplace_field):not(.arf_field_option_input_text)" : '' ) . '{';

				echo 'border-radius:' . esc_html( $field_border_radius_tablet ) . ';';
				echo '-webkit-border-radius:' . esc_html( $field_border_radius_tablet ) . ';';
				echo '-o-border-radius:' . esc_html( $field_border_radius_tablet ) . ';';
				echo '-moz-border-radius:' . esc_html( $field_border_radius_tablet ) . ';';
			echo "}";

			echo "@media all and (max-width:768px){";
				echo esc_html( $arf_form_cls_prefix ) . ' .arfformfield .controls input:not(.inplace_field):not(.arf_field_option_input_text)' . ( ( in_array( 'phone', $loaded_field ) ) ? ",".esc_html($arf_form_cls_prefix)." .arfformfield .controls input[type=tel]:not(.inplace_field):not(.arf_field_option_input_text)" : '' ) . '{';

					echo 'border-radius:' . esc_html( $field_border_radius_tablet ) . ';';
					echo '-webkit-border-radius:' . esc_html( $field_border_radius_tablet ) . ';';
					echo '-o-border-radius:' . esc_html( $field_border_radius_tablet ) . ';';
					echo '-moz-border-radius:' . esc_html( $field_border_radius_tablet ) . ';';
				echo "}";
			echo "}";
		}

		if( !empty( $field_border_radius_mobile )){

			echo '.arfdevicemobile ' .esc_html( $arf_form_cls_prefix ) . ' .arfformfield .controls input:not(.inplace_field):not(.arf_field_option_input_text)' . ( ( in_array( 'phone', $loaded_field ) ) ? ",".esc_html($arf_form_cls_prefix)." .arfformfield .controls input[type=tel]:not(.inplace_field):not(.arf_field_option_input_text)" : '' ) . '{';

				echo 'border-radius:' . esc_html( $field_border_radius_mobile ) . ';';
				echo '-webkit-border-radius:' . esc_html( $field_border_radius_mobile ) . ';';
				echo '-o-border-radius:' . esc_html( $field_border_radius_mobile ) . ';';
				echo '-moz-border-radius:' . esc_html( $field_border_radius_mobile ) . ';';
			echo "}";

			echo "@media all and (max-width:576px){";
				echo esc_html( $arf_form_cls_prefix ) . ' .arfformfield .controls input:not(.inplace_field):not(.arf_field_option_input_text)' . ( ( in_array( 'phone', $loaded_field ) ) ? ",".esc_html($arf_form_cls_prefix)." .arfformfield .controls input[type=tel]:not(.inplace_field):not(.arf_field_option_input_text)" : '' ) . '{';

					echo 'border-radius:' . esc_html( $field_border_radius_mobile ) . ';';
					echo '-webkit-border-radius:' . esc_html( $field_border_radius_mobile ) . ';';
					echo '-o-border-radius:' . esc_html( $field_border_radius_mobile ) . ';';
					echo '-moz-border-radius:' . esc_html( $field_border_radius_mobile ) . ';';
				echo "}";
			echo "}";
		}

		echo esc_html( $arf_form_cls_prefix ) . ' .arfformfield .controls input:not(.inplace_field):not(.arf_field_option_input_text):focus' . ( ( in_array( 'phone', $loaded_field ) ) ? ',' . esc_html( $arf_form_cls_prefix ) . ' .arfformfield .controls input[type=tel]:focus:not(.inplace_field):not(.arf_field_option_input_text)' : '' ) . '{';
			echo 'border:' . esc_html( $field_border_width ) . ' ' . esc_html( $field_border_style ) . ' ' . esc_html( $base_color ) . ';';
		if ( 1 == $arfmainfield_opacity ) {
			echo 'background:transparent !important;';
		} else {
			echo 'background:' . esc_html( $field_focus_bg_color ) . ' !important;';
		}
		echo '}';

		echo esc_html( $arf_form_cls_prefix ) . ' .arfformfield.arf_warning .controls input:not(.inplace_field):not(.arf_field_option_input_text)' . ( ( in_array( 'phone', $loaded_field ) ) ? ',' . esc_html( $arf_form_cls_prefix ) . ' .arfformfield.arf_warning .controls input[type=tel]:not(.inplace_field):not(.arf_field_option_input_text)' : '' ) . '{';
			echo 'background:' . esc_html( $field_error_bg_color ) . ' !important;';
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
		/** Prefix/Suffix Styling */

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
			if ( preg_match( '/rounded/', $arf_mainstyle ) ) {
				echo esc_html( $arf_form_cls_prefix ) . ' .arf_rounded_form .arfformfield ' . esc_html( $arf_prefix_cls ) . '{';
					echo 'border-left:' . esc_html( $field_border_width ) . '' . esc_html( $field_border_style ) . '' . esc_html( $field_border_color ) . ';';
					echo 'border-right:none;';
				echo '}';

				echo esc_html( $arf_form_cls_prefix ) . ' .arf_rounded_form .arfformfield ' . esc_html( $arf_prefix_cls ) . '.arf_prefix_focus{';
					echo 'border-left:' . esc_html( $field_border_width ) . '' . esc_html( $field_border_style ) . '' . esc_html( $base_color ) . ';';
					echo 'border-right:none;';
				echo '}';

				echo esc_html( $arf_form_cls_prefix ) . ' .arf_rounded_form .arfformfield ' . esc_html( $arf_suffix_cls ) . '{';
					echo 'border-right:' . esc_html( $field_border_width ) . '' . esc_html( $field_border_style ) . '' . esc_html( $field_border_color ) . ';';
					echo 'border-left:none;';
				echo '}';

				echo esc_html( $arf_form_cls_prefix ) . ' .arf_rounded_form .arfformfield ' . esc_html( $arf_suffix_cls ) . '.arf_suffix_focus{';
					echo 'border-right:' . esc_html( $field_border_width ) . '' . esc_html( $field_border_style ) . ' ' . esc_html( $base_color ) . ';';
					echo 'border-left:none;';
				echo '}';
			}

			echo esc_html( $arf_form_cls_prefix ) . ' .arfformfield ' . esc_html( $arf_prefix_cls ) . '{';
				echo 'display:table-cell;';
				echo 'width:' . esc_html( $arf_prefix_width ) . ';';
				echo 'padding:' . esc_html( $arf_prefix_padding ) . ';';
				echo 'vertical-align:middle;';
				echo 'color:' . esc_html( $prefix_suffix_icon_color ) . ';';
				echo 'text-align:center;';
				echo 'background:' . esc_html( $prefix_suffix_bg_color ) . ';';
				echo 'border:' . esc_html( $field_border_width ) . ' ' . esc_html( $field_border_style ) . ' ' . esc_html( $field_border_color ) . ';';
				echo 'border-top-left-radius:' . esc_html( $field_border_radius ) . ';';
				echo 'border-bottom-left-radius:' . esc_html( $field_border_radius ) . ';';
			echo '}';

			if( !empty( $field_border_radius_tablet )){

                echo ".arfdevicetablet " .esc_html($arf_form_cls_prefix)." .arfformfield ".esc_html($arf_prefix_cls)."{";
                    echo "border-top-left-radius:".esc_html($field_border_radius_tablet).";";
                    echo "border-bottom-left-radius:".esc_html($field_border_radius_tablet).";";
                echo "}";
                echo "@media all and (max-width:768px){";
                    echo esc_html($arf_form_cls_prefix)." .arfformfield ".esc_html($arf_prefix_cls)."{";
                        echo "border-top-left-radius:".esc_html($field_border_radius_tablet).";";
                        echo "border-bottom-left-radius:".esc_html($field_border_radius_tablet).";";
                    echo "}";
                echo "}";
            }

            if( !empty( $field_border_radius_mobile )){

                echo ".arfdevicemobile " .esc_html($arf_form_cls_prefix)." .arfformfield ".esc_html($arf_prefix_cls)."{";
                    echo "border-top-left-radius:".esc_html($field_border_radius_mobile).";";
                    echo "border-bottom-left-radius:".esc_html($field_border_radius_mobile).";";
                echo "}";
                echo "@media all and (max-width:576px){";
                    echo esc_html($arf_form_cls_prefix)." .arfformfield ".esc_html($arf_prefix_cls)."{";
                        echo "border-top-left-radius:".esc_html($field_border_radius_mobile).";";
                        echo "border-bottom-left-radius:".esc_html($field_border_radius_mobile).";";
                    echo "}";
                echo "}";
            }
            
			//phpcs:disable
			echo esc_html( $arf_form_cls_prefix ) . ' .arfformfield ' . esc_html( $arf_suffix_cls ) . '.arf_suffix_focus,';
			echo esc_html( $arf_form_cls_prefix ) . ' .arfformfield ' . esc_html( $arf_prefix_cls ) . '.arf_prefix_focus{';
				echo 'border-color:' . esc_html( $base_color ) . ';';
				echo 'transition:all 0.4s ease 0s;
                -webkit-transition:all 0.4s ease 0s;
                -moz-transition:all 0.4s ease 0s;
                -o-transition:all 0.4s ease 0s;';
				echo 'box-shadow:0 0 2px rgba(' . $arflitesettingcontroller->arflitehex2rgb( esc_html($base_color) ) . ',0.4);
                -moz-box-shadow:0 0 2px rgba(' . $arflitesettingcontroller->arflitehex2rgb( esc_html($base_color) ) . ',0.4);
                -webkit-box-shadow:0 0 2px rgba(' . $arflitesettingcontroller->arflitehex2rgb( esc_html($base_color) ) . ',0.4);
                -o-box-shadow:0 0 2px rgba(' . $arflitesettingcontroller->arflitehex2rgb( esc_html($base_color) ) . ',0.4);';
			echo '}';
			//phpcs:enable

			echo esc_html( $arf_form_cls_prefix ) . ' .arfformfield ' . esc_html( $arf_suffix_cls ) . ' i,';
			echo esc_html( $arf_form_cls_prefix ) . ' .arfformfield ' . esc_html( $arf_prefix_cls ) . ' i{';
				echo 'font-size:' . esc_html( $arf_input_font_size ) . 'px;';
			echo '}';

			echo esc_html( $arf_form_cls_prefix ) . ' .arfformfield ' . esc_html( $arf_suffix_cls ) . '{';
				echo 'display:table-cell;';
				echo 'width:' . esc_html( $arf_prefix_width ) . ';';
				echo 'padding:' . esc_html( $arf_prefix_padding ) . ';';
				echo 'vertical-align:middle;';
				echo 'color:' . esc_html( $prefix_suffix_icon_color ) . ';';
				echo 'text-align:center;';
				echo 'background:' . esc_html( $prefix_suffix_bg_color ) . ';';
				echo 'border:' . esc_html( $field_border_width ) . ' ' . esc_html( $field_border_style ) . ' ' . esc_html( $field_border_color ) . ';';
				echo 'border-top-right-radius:' . esc_html( $field_border_radius ) . ';';
				echo 'border-bottom-right-radius:' . esc_html( $field_border_radius ) . ';';
			echo '}';

			if( !empty( $field_border_radius_tablet )){

                echo ".arfdevicetablet " .esc_html($arf_form_cls_prefix)." .arfformfield ".esc_html($arf_suffix_cls)."{";
                    echo "border-top-right-radius:".esc_html($field_border_radius_tablet).";";
                    echo "border-bottom-right-radius:".esc_html($field_border_radius_tablet).";";
                echo "}";
                
                echo "@media all and (max-width:768px){";
                    echo esc_html($arf_form_cls_prefix)." .arfformfield ".esc_html($arf_suffix_cls)."{";
                        echo "border-top-right-radius:".esc_html($field_border_radius_tablet).";";
                        echo "border-bottom-right-radius:".esc_html($field_border_radius_tablet).";";
                    echo "}";
                echo "}";
            }

            if( !empty( $field_border_radius_mobile )){

                echo ".arfdevicemobile " .esc_html($arf_form_cls_prefix)." .arfformfield ".esc_html($arf_suffix_cls)."{";
                    echo "border-top-right-radius:".esc_html($field_border_radius_mobile).";";
                    echo "border-bottom-right-radius:".esc_html($field_border_radius_mobile).";";
                echo "}";
                
                echo "@media all and (max-width:576px){";
                    echo esc_html($arf_form_cls_prefix)." .arfformfield ".esc_html($arf_suffix_cls)."{";
                        echo "border-top-right-radius:".esc_html($field_border_radius_mobile).";";
                        echo "border-bottom-right-radius:".esc_html($field_border_radius_mobile).";";
                    echo "}";
                echo "}";
            }

			echo '@media (min-width:290px) and (max-width:480px){';
				echo esc_html( $arf_form_cls_prefix ) . ' .arfformfield ' . esc_html( $arf_suffix_cls ) . ',';
				echo esc_html( $arf_form_cls_prefix ) . ' .arfformfield ' . esc_html( $arf_prefix_cls ) . '{';
					echo 'width:40px !important;';
					echo 'padding:0 !important;';
				echo '}';
				echo esc_html( $arf_form_cls_prefix ) . ' .arfformfield ' . esc_html( $arf_suffix_cls ) . ' i,';
				echo esc_html( $arf_form_cls_prefix ) . ' .arfformfield ' . esc_html( $arf_prefix_cls ) . ' i{';
					echo 'font-size:20px;';
				echo '}';
			echo '}';

			echo esc_html( $arf_form_cls_prefix ) . ' ' . esc_html( $arf_prefix_suffix_wrapper_cls ) . '{';
				echo 'width:100%;';
			echo '}';

			echo esc_html( $arf_form_cls_prefix ) . ' .arfformfield .controls ' . esc_html( $arf_prefix_suffix_wrapper_cls ) . '.arf_prefix_only:not(.arf_phone_with_flag) input:not(.inplace_field):not(.arf_field_option_input_text)' . ( ( in_array( 'phone', $loaded_field ) ) ? ',' . esc_html( $arf_form_cls_prefix ) . ' .arfformfield .controls ' . esc_html( $arf_prefix_suffix_wrapper_cls ) . '.arf_prefix_only input[type=tel]:not(.inplace_field):not(.arf_field_option_input_text)' : '' ) . '{';
				echo 'border-left:none !important;';
				echo 'border-top-left-radius:0px !important;';
				echo 'border-bottom-left-radius:0px !important;';
				echo 'width:100%;';
				echo 'margin: 0;';
			echo '}';

			echo esc_html( $arf_form_cls_prefix ) . ' .arfformfield .controls ' . esc_html( $arf_prefix_suffix_wrapper_cls ) . '.arf_suffix_only input:not(.inplace_field):not(.arf_field_option_input_text)' . ( ( in_array( 'phone', $loaded_field ) ) ? ',' . esc_html( $arf_form_cls_prefix ) . ' .arfformfield .controls ' . esc_html( $arf_prefix_suffix_wrapper_cls ) . '.arf_suffix_only input[type=tel]:not(.inplace_field):not(.arf_field_option_input_text)' : '' ) . '{';
				echo 'border-right:none !important;';
				echo 'border-top-right-radius:0px !important;';
				echo 'border-bottom-right-radius:0px !important;';
				echo 'width:100%;';
			echo '}';

			echo esc_html( $arf_form_cls_prefix ) . ' .arf_fieldset .arfformfield .controls ' . esc_html( $arf_prefix_suffix_wrapper_cls ) . '.arf_both_pre_suffix input:not(.inplace_field):not(.arf_smiley_input):not(.arf_field_option_input_text)' . ( ( in_array( 'phone', $loaded_field ) ) ? ',' . esc_html( $arf_form_cls_prefix ) . ' .arfformfield .controls ' . esc_html( $arf_prefix_suffix_wrapper_cls ) . '.arf_both_pre_suffix input[type=tel]:not(.inplace_field):not(.arf_field_option_input_text)' : '' ) . '{';
				echo 'width:100%;';
				echo 'border-left:none !important;';
				echo 'border-right:none !important;';
				echo 'border-radius:0 !important;';
				echo '-webkit-border-radius:0 !important;';
				echo '-moz-border-radius:0 !important;';
				echo '-o-border-radius:0 !important;';
				echo 'margin: 0;';
			echo '}';

			echo ( ( in_array( 'phone', $loaded_field ) ) ? '' . esc_html( $arf_form_cls_prefix ) . ' .arfformfield .controls ' . esc_html( $arf_prefix_suffix_wrapper_cls ) . '.arf_both_pre_suffix input.arf_phone_utils[type=text]:not(.inplace_field):not(.arf_field_option_input_text)' : '' ) . '{';
				echo 'padding-left: 52px !important';
			echo '}';

			echo ( ( in_array( 'phone', $loaded_field ) ) ? '' . esc_html( $arf_form_cls_prefix ) . ' .arfformfield .controls input.arf_phone_utils[type=text]:not(.inplace_field):not(.arf_field_option_input_text)' : '' ) . '{';
				echo 'padding-left: 52px !important';
			echo '}';

			echo ( ( in_array( 'phone', $loaded_field ) ) ? '' . esc_html( $arf_form_cls_prefix ) . ' .arf_rounded_form .arfformfield .controls ' . esc_html( $arf_prefix_suffix_wrapper_cls ) . '.arf_both_pre_suffix input.arf_phone_utils:not(.inplace_field):not(.arf_field_option_input_text)' : '' ) . '{';
				echo 'border-left:' . esc_html( $field_border_width ) . ' ' . esc_html( $field_border_style ) . ' ' . esc_html( $field_border_color ) . ' !important;';
				echo 'border-top-left-radius:' . esc_html( $field_border_radius ) . ' !important;';
				echo 'border-top-right-radius: 0px !important;';
				echo 'border-bottom-left-radius:' . esc_html( $field_border_radius ) . ' !important;';
				echo 'border-bottom-right-radius:0px !important;';
				echo 'border-right:none !important;';
				echo 'margin: 0;';
			echo '}';


			echo ( ( in_array( 'phone', $loaded_field ) ) ? '' . esc_html( $arf_form_cls_prefix ) . ' .arf_standard_form .arfformfield .controls ' . esc_html( $arf_prefix_suffix_wrapper_cls ) . '.arf_both_pre_suffix input.arf_phone_utils:not(.inplace_field):not(.arf_field_option_input_text)' : '' ) . '{';
				echo 'border-left:' . esc_html( $field_border_width ) . ' ' . esc_html( $field_border_style ) . ' ' . esc_html( $field_border_color ) . ' !important;';
				echo 'border-top-left-radius:' . esc_html( $field_border_radius ) . ' !important;';
				echo 'border-top-right-radius: 0px !important;';
				echo 'border-bottom-left-radius:' . esc_html( $field_border_radius ) . ' !important;';
				echo 'border-bottom-right-radius:0px !important;';
				echo 'border-right:none !important;';
				echo 'margin: 0;';
			echo '}';
		}
		/** Prefix/Suffix Styling */
	}

	if ( in_array( 'textarea', $loaded_field ) ) {
		echo esc_html( $arf_form_cls_prefix ) . ' .allfields textarea{';
			echo 'width:100%;';
		if ( 1 == $arfmainfield_opacity ) {
			echo 'background:transparent;';
		} else {
			echo 'background:' . esc_html( $field_bg_color ) . ';';
		}
			echo 'font-family:' . esc_html( $arf_input_font_family ) . ';';
			echo 'font-size:' . esc_html( $arf_input_font_size ) . 'px;';
			echo esc_html( $arf_input_font_style_str );
			echo 'padding:' . esc_html( $arf_field_inner_padding ) . ';';
			echo 'direction:' . esc_html( $arf_input_field_direction ) . ';';
			echo 'color:' . esc_html( $field_text_color ) . ';';
			echo 'border:' . esc_html( $field_border_width ) . ' ' . esc_html( $field_border_style ) . ' ' . esc_html( $field_border_color ) . ';';
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
			echo 'height:auto;';
			echo 'min-height:auto;';
		echo '}';

		if( !empty($field_border_radius_tablet)){

            echo ".arfdevicetablet " .esc_html($arf_form_cls_prefix) . " .allfields textarea{";
                echo "border-radius:".esc_html($field_border_radius_tablet).";";
                echo "-webkit-border-radius:".esc_html($field_border_radius_tablet).";";
                echo "-o-border-radius:".esc_html($field_border_radius_tablet).";";
                echo "-moz-border-radius:".esc_html($field_border_radius_tablet).";";    
            echo "}";


            echo "@media all and (max-width:768px){";
                echo esc_html($arf_form_cls_prefix) . " .allfields textarea{";
                    echo "border-radius:".esc_html($field_border_radius_tablet).";";
                    echo "-webkit-border-radius:".esc_html($field_border_radius_tablet).";";
                    echo "-o-border-radius:".esc_html($field_border_radius_tablet).";";
                    echo "-moz-border-radius:".esc_html($field_border_radius_tablet).";";
                echo "}";
            echo "}";
        }

        if( !empty( $field_border_radius_mobile )){

            echo ".arfdevicemobile " .esc_html($arf_form_cls_prefix) . " .allfields textarea{";
                echo "border-radius:".esc_html($field_border_radius_tablet).";";
                echo "-webkit-border-radius:".esc_html($field_border_radius_tablet).";";
                echo "-o-border-radius:".esc_html($field_border_radius_tablet).";";
                echo "-moz-border-radius:".esc_html($field_border_radius_tablet).";";    
            echo "}";

            echo "@media all and (max-width:576px){";
                echo esc_html($arf_form_cls_prefix) . " .allfields textarea{";
                    echo "border-radius:".esc_html($field_border_radius_mobile).";";
                    echo "-webkit-border-radius:".esc_html($field_border_radius_mobile).";";
                    echo "-o-border-radius:".esc_html($field_border_radius_mobile).";";
                    echo "-moz-border-radius:".esc_html($field_border_radius_mobile).";";
                echo "}";
            echo "}";
        }
 
		echo esc_html( $arf_form_cls_prefix ) . ' .allfields textarea:focus{';
			echo 'border:' . esc_html( $field_border_width ) . ' ' . esc_html( $field_border_style ) . ' ' . esc_html( $base_color ) . ';';
		if ( 1 == $arfmainfield_opacity ) {
			echo 'background:transparent !important;';
		} else {
			echo 'background:' . esc_html( $field_focus_bg_color ) . ' !important;';
		}
		echo '}';
		echo esc_html( $arf_form_cls_prefix ) . ' .allfields .arfcount_text_char_div{';
			echo 'margin:2px 0px 0px 0px;padding:0;';
			echo 'font-family:' . esc_html( $arf_input_font_family ) . ';';
			echo 'font-size:' . esc_html( $description_font_size ) . 'px;';
			echo 'text-align:right;';
			echo 'color:' . esc_html( $field_label_txt_color ) . ';';
			echo 'max-width:100%;width:auto; line-height: 20px;';
		echo '}';
		echo esc_html( $arf_form_cls_prefix ) . ' .allfields .arf_textareachar_limit{float:left;width:95%;width:calc(100% - 50px) !important;}';
	}

	if ( in_array( 'checkbox', $loaded_field ) ) {
		echo esc_html( $arf_form_cls_prefix ) . ' .arf_checkbox_style:not(.arf_enable_checkbox_image)' . esc_html( $arf_checkbox_not_admin ) . ' input[type="checkbox"]{';
			echo 'width:auto;';
			echo 'border:none;';
			echo 'background:transparent;';
			echo 'padding:0;';
		echo '}';

		echo esc_html( $arf_form_cls_prefix ) . ' .arf_checkbox_style{';
			echo 'clear:none;';
			echo 'box-shadow:inherit;';
			echo '-moz-box-shadow:inherit;';
			echo '-webkit-box-shadow:inherit;';
			echo '-o-box-shadow:inherit;';

		echo '}';
		echo esc_html( $arf_form_cls_prefix ) . ' .arf_horizontal_radio .arf_checkbox_style{';
			echo 'display:inline-flex;';
			echo 'margin:0 20px 10px 0;';
		echo '}';
		echo esc_html( $arf_form_cls_prefix ) . ' .top_container .arf_checkbox_style:not(.arf_enable_checkbox_image)' . esc_html( $arf_checkbox_not_admin ) . '{';
			echo 'margin:0 2% 15px 0;';
			echo 'max-width:100%;';
			echo 'position:relative;';
			echo 'padding-left:' . ( ( esc_html( $arf_label_font_size ) + 12 ) < 30 ? 30 : ( esc_html( $arf_label_font_size ) + 12 ) ) . 'px';
		echo '}';
		echo esc_html( $arf_form_cls_prefix ) . ' .top_container .arf_checkbox_style:not(.arf_enable_checkbox_image)' . esc_html( $arf_checkbox_not_admin ) . ' .arf_checkbox_input_wrapper{';
			echo 'position:absolute !important;';
			echo 'top: 50%;';
			echo 'transform: translateY(-50%);';
			echo 'margin-left: 0;';
			echo 'left: 0;';
		echo '}';
		echo esc_html( $arf_form_cls_prefix ) . " .setting_checkbox.arf_standard_checkbox .arf_checkbox_input_wrapper input[type='checkbox'] + span{";
			echo 'border-color:' . esc_html( $field_border_color ) . ';';
		echo '}';
		echo esc_html( $arf_form_cls_prefix ) . " .arfformfield .setting_checkbox.arf_custom_checkbox .arf_checkbox_input_wrapper input[type='checkbox']:checked + span{";
			echo 'border-color:' . esc_html( $base_color ) . ';';
		echo '}';
		echo esc_html( $arf_form_cls_prefix ) . " .arfformfield .setting_checkbox.arf_custom_checkbox .arf_checkbox_input_wrapper input[type='checkbox']:checked + span i{";
			echo 'display:block;';
			echo 'height:auto;';
			echo 'width:auto;';
			echo 'color:' . esc_html( $base_color ) . ';';
			echo 'font-size:75%;';
		echo '}';

			echo esc_html( $arf_form_cls_prefix ) . ' .arf_rounded_form .arf_enable_checkbox_image span.arf_checkbox_label_image.checked::before,
            ' . esc_html( $arf_form_cls_prefix ) . ' .arf_rounded_form .arf_enable_checkbox_image_editor span.arf_checkbox_label_image_editor.checked::before{
                border-radius: 100%;    
                -webkit-border-radius: 100%;    
                -o-border-radius: 100%;    
                -moz-border-radius: 100%;
            }';

			echo esc_html( $arf_form_cls_prefix ) . " .arf_rounded_form .arf_enable_checkbox_image span.arf_checkbox_label_image,
                    ".esc_html($arf_form_cls_prefix)." .arf_rounded_form .arf_enable_checkbox_image_editor span.arf_checkbox_label_image_editor{
                        border-radius: 24px;
                }";
			echo esc_html( $arf_form_cls_prefix ) . " .arfformfield .setting_checkbox.arf_rounded_flat_checkbox:not(.arf_custom_checkbox) .arf_checkbox_input_wrapper input[type='checkbox']:checked + span{";
				echo 'background:' . esc_html( $base_color ) . ';';
				echo 'border-color:' . esc_html( $base_color ) . ';';
				echo 'border-radius: 4px;';
				echo '-webkit-border-radius: 4px;';
				echo '-o-border-radius: 4px;';
				echo '-moz-border-radius: 4px;';
			echo '}';

		echo esc_html( $arf_form_cls_prefix ) . " .arfformfield .setting_checkbox.arf_standard_checkbox:not(.arf_custom_checkbox) .arf_checkbox_input_wrapper input[type='checkbox']:checked + span{";
			echo 'background:' . esc_html( $base_color ) . ';';
			echo 'border-color:' . esc_html( $base_color ) . ';';
		echo '}';
		echo esc_html( $arf_form_cls_prefix ) . ' .arf_fieldset .setting_checkbox.arf_single_row .arf_checkbox_input_wrapper + label,';
		echo esc_html( $arf_form_cls_prefix ) . ' .arf_fieldset .arf_horizontal_radio .setting_checkbox .arf_checkbox_input_wrapper + label{';
			echo 'position:relative;';
			echo 'line-height:1;';
		echo '}';
		echo esc_html( $arf_form_cls_prefix ) . ' .setting_checkbox.controls.arf_standard_checkbox:not(.arf_multiple_row){';
			echo 'display:flex;';
			echo 'flex-wrap:wrap;';
		echo '}';
		echo esc_html( $arf_form_cls_prefix ) . ' .setting_checkbox.arf_standard_checkbox .arf_checkbox_input_wrapper{';
			echo 'float: none;';
			echo 'width: 20px;';
			echo 'height: 20px;';
			echo 'margin-right: 10px;';
			echo 'display:inline-block;';
			echo 'line-height: 32px;';
		echo '}';
		echo esc_html( $arf_form_cls_prefix ) . ' .setting_checkbox .arf_enable_checkbox_image .arf_checkbox_input_wrapper,';
		echo esc_html( $arf_form_cls_prefix ) . ' .setting_checkbox .arf_enable_checkbox_image_editor .arf_checkbox_input_wrapper{';
			echo 'position:absolute;';
		echo '}';
		echo esc_html( $arf_form_cls_prefix ) . " .setting_checkbox.arf_standard_checkbox .arf_checkbox_input_wrapper input[type='checkbox']{";
			echo 'position: absolute;';
			echo 'left: 0;';
			echo 'top: 0;';
			echo 'width: 20px;';
			echo 'height: 20px;';
			echo 'opacity: 0;';
			echo 'margin: 0;';
			echo 'z-index: 1;';
			echo 'cursor: pointer;';
		echo '}';
		echo esc_html( $arf_form_cls_prefix ) . " .setting_checkbox.arf_standard_checkbox .arf_checkbox_input_wrapper input[type='checkbox'] + span{";
			echo 'position: absolute;';
			echo 'width: 20px;';
			echo 'height: 20px;';
			echo 'border-width: 1px;';
			echo 'border-style: solid;';
			echo 'margin-right: 5px;';
			echo 'display:block;';
		echo '}';
		echo esc_html( $arf_form_cls_prefix ) . " .setting_checkbox.arf_standard_checkbox .arf_checkbox_input_wrapper input[type='checkbox']:checked + span::before{";
			echo 'top: 50%;';
			echo 'left: 50%;';
			echo "content: '';";
			echo 'position: absolute;';
			echo 'width: 30%;';
			echo 'height: 50%;';
			echo 'border-top: 2px solid transparent;';
			echo 'border-left: 2px solid transparent;';
			echo 'border-right: 2px solid #fff;';
			echo 'border-bottom: 2px solid #fff;';
			echo '-webkit-transform: rotateZ(40deg) translate(-50%, -50%);';
			echo '-o-transform: rotateZ(40deg) translate(-50%, -50%);';
			echo '-moz-transform: rotateZ(40deg) translate(-50%, -50%);';
			echo 'transform: rotateZ(40deg) translate(-50%, -50%);';
			echo '-ms-transform: rotate(40deg) translate(-50%, -50%);';
			echo '-webkit-transform-origin: 45% -10%;';
			echo '-o-transform-origin: 45% -10%;';
			echo '-moz-transform-origin: 45% -10%;';
			echo 'transform-origin: 45% -10%;';
			echo '-ms-transform-origin: 45% -10%;';
		echo '}';
		echo esc_html( $arf_form_cls_prefix ) . ' .setting_checkbox.arf_rounded_flat_checkbox .arf_checkbox_input_wrapper{';
			echo 'float: none;';
			echo 'width: 20px;';
			echo 'height: 20px;';
			echo 'margin-right: 10px;';
			echo 'display:inline-block;';
		echo '}';

		echo esc_html( $arf_form_cls_prefix ) . " .setting_checkbox.arf_rounded_flat_checkbox .arf_checkbox_input_wrapper input[type='checkbox'] {";
			echo 'position: absolute;';
			echo 'left: 0;';
			echo 'top: 0;';
			echo 'width: 20px;';
			echo 'height: 20px;';
			echo 'opacity: 0;';
			echo 'margin: 0;';
			echo 'z-index: 1;';
			echo 'cursor: pointer;';
		echo '}';

		echo esc_html( $arf_form_cls_prefix ) . " .setting_checkbox.arf_rounded_flat_checkbox .arf_checkbox_input_wrapper input[type='checkbox'] + span {";
			echo 'position: absolute;';
			echo 'left: 0;';
			echo 'top: 0;';
			echo 'width: 20px;';
			echo 'height: 20px;';
			echo 'border: none;';
			echo 'background-color: #d7dcde;';
			echo 'margin-right: 5px;';
			echo 'border-radius: 4px;';
			echo '-webkit-border-radius: 4px;';
			echo '-moz-border-radius: 4px;';
			echo '-o-border-radius: 4px;';
		echo '}';

		echo esc_html( $arf_form_cls_prefix ) . " .setting_checkbox.arf_rounded_flat_checkbox .arf_checkbox_input_wrapper input[type='checkbox']:checked + span::before {";
			echo 'top: 50%;';
			echo 'left: 50%;';
			echo "content: '';";
			echo 'position: absolute;';
			echo 'width: 30%;';
			echo 'height: 50%;';
			echo 'border-top: 2px solid transparent;';
			echo 'border-left: 2px solid transparent;';
			echo 'border-right: 2px solid #fff;';
			echo 'border-bottom: 2px solid #fff;';
			echo '-webkit-transform: rotateZ(40deg) translate(-50%, -50%);';
			echo '-o-transform: rotateZ(40deg) translate(-50%, -50%);';
			echo '-moz-transform: rotateZ(40deg) translate(-50%, -50%);';
			echo 'transform: rotateZ(40deg) translate(-50%, -50%);';
			echo '-ms-transform: rotate(40deg) translate(-50%, -50%);';
			echo '-webkit-transform-origin: 45% -10%;';
			echo '-o-transform-origin: 45% -10%;';
			echo '-moz-transform-origin: 45% -10%;';
			echo 'transform-origin: 45% -10%;';
			echo '-ms-transform-origin: 45% -10%;';
		echo '}';

		echo esc_html( $arf_form_cls_prefix ) . ' .setting_checkbox.arf_custom_checkbox .arf_checkbox_input_wrapper {';
			echo 'float: none;';
			echo 'width: 20px;';
			echo 'height: 24px;';
			echo 'position: relative;';
			echo 'margin-right: 10px;';
			echo 'display:inline-block;';
		echo '}';

		echo esc_html( $arf_form_cls_prefix ) . ' .setting_checkbox.arf_custom_checkbox .arf_checkbox_input_wrapper + span{';
			echo 'vertical-align: middle;';
		echo '}';

		echo esc_html( $arf_form_cls_prefix ) . " .setting_checkbox.arf_custom_checkbox .arf_checkbox_input_wrapper input[type='checkbox'] {";
			echo 'position: absolute;';
			echo 'left: 0;';
			echo 'top: 0;';
			echo 'width: 22px;';
			echo 'height: 22px;';
			echo 'opacity: 0;';
			echo 'margin: 0;';
			echo 'z-index: 1;';
			echo 'cursor: pointer;';
		echo '}';


		echo esc_html( $arf_form_cls_prefix ) . " .setting_checkbox.arf_custom_checkbox .arf_checkbox_input_wrapper input[type='checkbox'] + span {";
			echo 'float: none;';
			echo 'width: 20px;';
			echo 'height: 20px;';
			echo 'border: 1px solid #5a5a5a;';
			echo 'position:relative;';
			echo 'background: none;';
			echo 'vertical-align: middle;';
			echo 'display:inline-block;';
			echo 'text-align:center;';
		echo '}';

		echo esc_html( $arf_form_cls_prefix ) . " .setting_checkbox.arf_custom_checkbox .arf_checkbox_input_wrapper input[type='checkbox'] + span i {";
			echo 'position: absolute;';
			echo 'top: 50%;';
			echo 'left:50%;';
			echo 'transform: translate(-50%,-50%);';
			echo '-webkit-transform: translate(-50%,-50%);';
			echo '-o-transform: translate(-50%,-50%);';
			echo '-ms-transform: translate(-50%,-50%);';
			echo '-moz-transform: translate(-50%,-50%);';
			echo 'display: none;';
		echo '}';

		echo esc_html( $arf_form_cls_prefix ) . " .setting_checkbox.arf_custom_checkbox .arf_checkbox_input_wrapper input[type='checkbox']:checked + span i {";
			echo 'display:inline-block;';
		echo '}';

		echo esc_html( $arf_form_cls_prefix ) . ' .arf_enable_checkbox_image div:not(.arf_checkbox_label),';
		echo esc_html( $arf_form_cls_prefix ) . ' .arf_enable_checkbox_image_editor div:not(.arf_checkbox_label){';
		echo 'opacity: 0 !important;';
		echo '}';

		echo esc_html( $arf_form_cls_prefix ) . ' .arf_enable_checkbox_image,';
		echo esc_html( $arf_form_cls_prefix ) . ' .arf_enable_checkbox_image_editor{';
			echo 'cursor : pointer;';
			echo 'align-self:baseline;';
		echo '}';

		echo esc_html( $arf_form_cls_prefix ) . ' .left_container .setting_checkbox .help-block{';
			echo 'margin-left:0px;';
		echo '}';

		echo esc_html( $arf_form_cls_prefix ) . ' .arf_checkbox_style label,';
		echo esc_html( $arf_form_cls_prefix ) . ' .arf_checkbox_style span.arf_checkbox_label{';
			echo 'font-size:' . esc_html( $arf_label_font_size ) . 'px;';
			echo 'color:' . esc_html( $field_label_txt_color ) . ' !important;';
			echo 'font-family:' . esc_html( $arf_input_font_family ) . ';';
			echo esc_html( $arf_input_font_style_str );
			echo 'vertical-align: top;';
			echo 'word-wrap: break-word;';
			echo 'width: auto;';
			echo 'margin:unset;';
			echo 'padding:0;';
			echo 'line-height: 24px;';
			echo 'position:relative;';
			echo 'max-width:100%;';
			echo 'top:0;';
			echo 'cursor:pointer;';
			echo 'display:inline-flex;';
			echo 'word-break:break-all;';
			echo 'line-height: 1.1em;';
			echo 'align-self:center;';
			echo 'cursor:pointer;';
			echo 'width:auto;';
		echo '}';

		echo esc_html( $arf_form_cls_prefix ) . ' .arf_vertical_radio .arf_checkbox_style{display:flex; width:100%}';

		echo esc_html( $arf_form_cls_prefix ) . ' .arfformfield.arf_horizontal_radio .arf_chk_radio_col_two,';
		echo esc_html( $arf_form_cls_prefix ) . ' .arfformfield.arf_horizontal_radio .arf_chk_radio_col_thiree,';
		echo esc_html( $arf_form_cls_prefix ) . ' .arfformfield.arf_horizontal_radio .arf_chk_radio_col_four {';
			echo 'width: 100%;';
			echo 'display:flex;';
		echo '}';

		// checkbox media css
		echo '@media all and (max-width:480px){';
			echo esc_html( $arf_form_cls_prefix ) . ' .arfformfield.arf_horizontal_radio .arf_chk_radio_col_two,';
			echo esc_html( $arf_form_cls_prefix ) . ' .arfformfield.arf_horizontal_radio .arf_chk_radio_col_thiree,';
			echo esc_html( $arf_form_cls_prefix ) . ' .arfformfield.arf_horizontal_radio .arf_chk_radio_col_four {';
				echo 'flex-direction: column';
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
		/*if image enable*/
		if ( $is_checkbox_img_enable ) {
			echo esc_html( $arf_form_cls_prefix ) . ' .arf_enable_checkbox_image span.arf_checkbox_label_image,';
			echo esc_html( $arf_form_cls_prefix ) . ' .arf_enable_checkbox_image_editor span.arf_checkbox_label_image_editor{';
				echo 'display : block !important;';
				echo 'margin: 0;';
				echo 'padding: 0;';
			echo '}';

			echo esc_html( $arf_form_cls_prefix ) . ' .arf_checkbox_style.arf_enable_checkbox_image label{ width: auto !important; flex-direction: column; }';
			echo esc_html( $arf_form_cls_prefix ) . ' .arf_checkbox_style.arf_enable_checkbox_image_editor label{ width: auto !important; flex-direction: column; }';
			echo esc_html( $arf_form_cls_prefix ) . ' .arf_checkbox_style.arf_enable_checkbox_image_editor label .arf_checkbox_label_image_editor.checked{ border-color:' . esc_html( $base_color ) . '; }';

			echo esc_html( $arf_form_cls_prefix ) . ' .arf_checkbox_style.arf_enable_checkbox_image label .arf_checkbox_label_image.checked{ border-color:' . esc_html( $base_color ) . '; }';
			echo esc_html( $arf_form_cls_prefix ) . ' .arf_enable_checkbox_image span.arf_checkbox_label_image.checked::before,' . esc_html( $arf_form_cls_prefix ) . ' .arf_enable_checkbox_image_editor span.arf_checkbox_label_image_editor.checked::before{ background-color:' . esc_html( $base_color ) . '; border-color:' . esc_html( $base_color ) . '; }';

			echo esc_html( $arf_form_cls_prefix ) . '  .arf_enable_checkbox_image span.arf_checkbox_label_image,' . esc_html( $arf_form_cls_prefix ) . ' .arf_enable_checkbox_image_editor span.arf_checkbox_label_image_editor{';
				echo 'position: absolute;
                display : flex !important;
                justify-content: center;
                align-items: center;
                height: auto;
                width: 100%;
                background-size: cover;
                background-position: center center;
                position: relative;
                background-repeat: no-repeat;
                margin-bottom: 5px;
                border: 2px solid transparent;
                border-radius: 4px;
                overflow: hidden; 
                box-sizing: border-box;';
			echo '}';

			echo esc_html( $arf_form_cls_prefix ) . ' .arf_enable_checkbox_image span.arf_checkbox_label_image.checked,' . esc_html( $arf_form_cls_prefix ) . '  .arf_enable_checkbox_image_editor span.arf_checkbox_label_image_editor.checked{';
				echo 'border: 2px solid;z-index: 1;';
			echo '}';

			echo esc_html( $arf_form_cls_prefix ) . ' .arf_enable_checkbox_image span.arf_checkbox_label_image::before,' . esc_html( $arf_form_cls_prefix ) . ' .arf_enable_checkbox_image_editor span.arf_checkbox_label_image_editor::before{';
				echo 'display: flex;
                align-items: center;
                color: white;
                height: 100%;
                position: absolute;
                justify-content: center;
                width: 100%;
                width: 24px;
                height: 24px;
                right: 7px;
                top: 7px;  
                border: 2px solid transparent;
                font-size: 14px;';
			echo '}';

			echo esc_html( $arf_form_cls_prefix ) . ' .arf_checkbox_label_image:not(.checked)::before,' . esc_html( $arf_form_cls_prefix ) . ' .arf_checkbox_label_image_editor:not(.checked)::before{';
				echo 'display: flex;';
				echo 'top: 60% !important;';
				echo 'opacity: 0;';
			echo '}';
		}

		echo esc_html( $arf_form_cls_prefix ) . ' .arf_checkbox_style img{ border:none; }';

		echo esc_html( $arf_form_cls_prefix ) . ' .arf_fieldset .arf_checkbox_style{';
			echo 'box-sizing:border-box;';
			echo '-webkit-box-sizing:border-box;';
			echo '-o-box-sizing:border-box;';
			echo '-moz-box-sizing:border-box;';
		echo '}';

		echo '@media all and (max-width:480px) {';
			echo esc_html( $arf_form_cls_prefix ) . ' .arfformfield.arf_horizontal_radio .arf_chk_radio_col_thiree .arf_checkbox_style{';
				echo 'width: 100%;';
			echo '}';

			echo esc_html( $arf_form_cls_prefix ) . ' .arfformfield.arf_horizontal_radio .arf_chk_radio_col_four .arf_checkbox_style{';
				echo 'width: 48%;';
			echo '}';
		echo '}';

		if ( $arf_label_font_size > 20 ) {
			echo esc_html( $arf_form_cls_prefix ) . ' .setting_checkbox.arf_custom_checkbox .arf_checkbox_input_wrapper,';
			echo esc_html( $arf_form_cls_prefix ) . ' .setting_checkbox.arf_custom_checkbox .arf_checkbox_input_wrapper input[type="checkbox"],';
			echo esc_html( $arf_form_cls_prefix ) . ' .setting_checkbox.arf_custom_checkbox .arf_checkbox_input_wrapper input[type="checkbox"] + span,';
			echo esc_html( $arf_form_cls_prefix ) . ' .setting_checkbox.arf_rounded_flat_checkbox .arf_checkbox_input_wrapper,';
			echo esc_html( $arf_form_cls_prefix ) . ' .setting_checkbox.arf_rounded_flat_checkbox .arf_checkbox_input_wrapper input[type="checkbox"],';
			echo esc_html( $arf_form_cls_prefix ) . ' .setting_checkbox.arf_rounded_flat_checkbox .arf_checkbox_input_wrapper input[type="checkbox"] + span,';
			echo esc_html( $arf_form_cls_prefix ) . ' .setting_checkbox.arf_standard_checkbox .arf_checkbox_input_wrapper,';
			echo esc_html( $arf_form_cls_prefix ) . ' .setting_checkbox.arf_standard_checkbox .arf_checkbox_input_wrapper input[type="checkbox"],';
			echo esc_html( $arf_form_cls_prefix ) . ' .setting_checkbox.arf_standard_checkbox .arf_checkbox_input_wrapper input[type="checkbox"] + span{';
				echo 'width:' . ( esc_html( $arf_label_font_size ) + 2 ) . 'px;';
				echo 'height:' . ( esc_html( $arf_label_font_size ) + 2 ) . 'px;';
			echo '}';
		}
	}

	if ( in_array( 'radio', $loaded_field ) ) {
		echo esc_html( $arf_form_cls_prefix ) . ' input[type=radio]{width:auto;border:none;background:transparent;padding:0;}';
		echo esc_html( $arf_form_cls_prefix ) . ' .left_container .arf_radiobutton, ' . esc_html( $arf_form_cls_prefix ) . ' .none_container .arf_radiobutton{margin:0 20px 5px 0;}';
		echo esc_html( $arf_form_cls_prefix ) . ' .arf_vertical_radio .arf_radiobutton{display:flex;width:100%;}';
		echo esc_html( $arf_form_cls_prefix ) . ' .arf_horizontal_radio .arf_radiobutton {display:inline-flex;margin:0 20px 10px 0;}';
		echo esc_html( $arf_form_cls_prefix ) . ' .arf_horizontal_radio.left_container .arf_radiobutton, ' . esc_html( $arf_form_cls_prefix ) . ' .right_container .arf_radiobutton{margin:0 20px 10px 0;}';
		echo esc_html( $arf_form_cls_prefix ) . ' .arf_radiobutton{clear:none;box-shadow:inherit; -webkit-box-shadow:inherit;-moz-box-shadow:inherit;-o-box-shadow:inherit;}';
		echo esc_html( $arf_form_cls_prefix ) . ' .top_container .arf_radiobutton:not(.arf_enable_radio_image_editor):not(.arf_enable_radio_image) {
            margin:0 2% 15px 0;
            max-width:100%;
            position:relative;
            padding-left:' . ( ( esc_html( $arf_label_font_size ) + 12 ) < 30 ? 30 : ( esc_html( $arf_label_font_size ) + 12 ) ) . 'px;
        }';

		echo esc_html( $arf_form_cls_prefix ) . ' .top_container .arf_radiobutton:not(.arf_enable_radio_image_editor):not(.arf_enable_radio_image) .arf_radio_input_wrapper:not(.arf_matrix_radio_input_wrapper){
            position:absolute !important;
            top:50%;
            transform:translateY(-50%);
            margin-left:0px;
            left:0;
        }';
		if ( $is_radio_img_enable ) {
			echo esc_html( $arf_form_cls_prefix ) . ' .arf_radiobutton.arf_enable_radio_image label,
            ' . esc_html( $arf_form_cls_prefix ) . ' .arf_radiobutton.arf_enable_radio_image_editor label{
                width:auto !important;
            }';

			echo esc_html( $arf_form_cls_prefix ) . ' .arf_radiobutton.arf_enable_radio_image label .arf_radio_label_image.checked{
                border-color:' . esc_html( $base_color ) . ' !important;
            }';

			echo esc_html( $arf_form_cls_prefix ) . ' .arf_radiobutton.arf_enable_radio_image_editor label .arf_radio_label_image_editor.checked{
                border-color:' . esc_html( $base_color ) . ';
            }';

			echo esc_html( $arf_form_cls_prefix ) . ' .arf_enable_radio_image span.arf_radio_label_image.checked::before,' . esc_html( $arf_form_cls_prefix ) . ' .arf_enable_radio_image_editor span.arf_radio_label_image_editor.checked::before{
                background-color: ' . esc_html( $base_color ) . ' !important;
                border-color: ' . esc_html( $base_color ) . ' !important;
            }';

			echo esc_html( $arf_form_cls_prefix ) . ' .arf_enable_radio_image span.arf_radio_label_image,
            ' . esc_html( $arf_form_cls_prefix ) . ' .arf_enable_radio_image_editor span.arf_radio_label_image_editor{
                position: absolute;
                display : flex !important;
                justify-content: center;
                align-items: center;
                height: auto;
                width: 100%;
                background-size: cover;
                background-position: center center;
                position: relative;
                background-repeat: no-repeat;
                margin-bottom: 5px;
                border: 2px solid transparent;
                border-radius: 4px;
                overflow: hidden; 
                box-sizing: border-box;
            }';

			echo esc_html( $arf_form_cls_prefix ) . ' .arf_radio_label_image:not(.checked)::before,
            ' . esc_html( $arf_form_cls_prefix ) . ' .arf_radio_label_image_editor:not(.checked)::before{
                display: flex;
                top: 60% !important;
                opacity: 0;
            }';
		}

		echo esc_html( $arf_form_cls_prefix ) . ' ' . esc_html( $arf_form_cls_prefix ) . ' .arf_radiobutton img {
            border: none;
        }';

		echo esc_html( $arf_form_cls_prefix ) . ' .arf_radiobutton input[type=radio] {
            padding: 0; height: auto; width: auto; float: none; left: auto; position:inherit; opacity:1; margin-right:5px;
        }';

		echo esc_html( $arf_form_cls_prefix ) . ' .arf_radiobutton label {
            display:inline-block !important;
            margin-bottom:0px;
        }';

		echo '
        ' . esc_html( $arf_form_cls_prefix ) . ' .arfformfield.arf_horizontal_radio .arf_chk_radio_col_two .arf_radiobutton{
            width:100% !important;
        }
        
        ' . esc_html( $arf_form_cls_prefix ) . ' .arfformfield.arf_horizontal_radio .arf_chk_radio_col_two .arf_radiobutton{
            width: 48% !important;
            margin: 0 2% 10px 0;
        }
        
        ' . esc_html( $arf_form_cls_prefix ) . ' .arf_fieldset .arf_radiobutton{
            box-sizing: border-box;
            -webkit-box-sizing:border-box;
            -o-box-sizing:border-box;
            -moz-box-sizing:border-box;
            position:relative;
        }
        
        ' . esc_html( $arf_form_cls_prefix ) . " .arfformfield.arf_horizontal_radio .arf_chk_radio_col_thiree .arf_radiobutton {
            width: 31.33%;
            margin: 0 2% 10px 0;
        }
        
        " .esc_html($arf_form_cls_prefix)." .arfformfield.arf_horizontal_radio .arf_chk_radio_col_four .arf_radiobutton {
            width: 23%;
            margin: 0 2% 10px 0;
        }
        
        @media (max-width:480px) {
            " . esc_html( $arf_form_cls_prefix ) . ' .arfformfield.arf_horizontal_radio .arf_chk_radio_col_thiree .arf_radiobutton {
                width: 100%;
            }
            ' . esc_html( $arf_form_cls_prefix ) . ' .arfformfield.arf_horizontal_radio .arf_chk_radio_col_four .arf_radiobutton {
                width: 48%;
            }
        }
        
        ' . esc_html( $arf_form_cls_prefix ) . ' .arf_rounded_form .arf_enable_radio_image span.arf_radio_label_image,
        ' . esc_html( $arf_form_cls_prefix ) . ' .arf_rounded_form .arf_enable_radio_image_editor span.arf_radio_label_image_editor{
            border-radius: 24px;
        }
        
        ' . esc_html( $arf_form_cls_prefix ) . ' .arf_enable_radio_image span.arf_radio_label_image.checked,
        ' . esc_html( $arf_form_cls_prefix ) . ' .arf_enable_radio_image_editor span.arf_radio_label_image_editor.checked{
            border: 2px solid;
            z-index: 1;
        }
        
        ' . esc_html( $arf_form_cls_prefix ) . ' .arf_enable_radio_image span.arf_radio_label_image::before,
        ' . esc_html( $arf_form_cls_prefix ) . ' .arf_enable_radio_image_editor span.arf_radio_label_image_editor::before{
            display: flex;
            align-items: center;
            color: white;
            height: 100%;
            position: absolute;
            justify-content: center;
            width: 100%;
            width: 24px;
            height: 24px;
            right: 7px;
            top: 7px;  
            border: 2px solid transparent;
            font-size: 14px;
        }
        
        ' . esc_html( $arf_form_cls_prefix ) . ' .arf_rounded_form .arf_enable_radio_image span.arf_radio_label_image.checked::before,
        ' . esc_html( $arf_form_cls_prefix ) . ' .arf_rounded_form .arf_enable_radio_image_editor span.arf_radio_label_image_editor.checked::before{
              border-radius: 100%;    
            -webkit-border-radius: 100%;    
            -o-border-radius: 100%;    
            -moz-border-radius: 100%;
        }
        
        ' . esc_html( $arf_form_cls_prefix ) . ' .arf_radiobutton label{
            vertical-align: top !important;
            word-wrap: break-word;
            width: auto;
            margin:unset;
            padding:0 !important;
            line-height: 24px !important;
            position:relative;
            max-width:100% !important;
        }
        
        ' . esc_html( $arf_form_cls_prefix ) . ' .arfformfield.arf_horizontal_radio .arf_chk_radio_col_two,
        ' . esc_html( $arf_form_cls_prefix ) . ' .arfformfield.arf_horizontal_radio .arf_chk_radio_col_thiree,
        ' . esc_html( $arf_form_cls_prefix ) . ' .arfformfield.arf_horizontal_radio .arf_chk_radio_col_four {
            width: 100%;
            display:flex;
        }
        
        ' . esc_html( $arf_form_cls_prefix ) . ' .arf_radiobutton label,
        ' . esc_html( $arf_form_cls_prefix ) . ' .arf_radiobutton span.arf_radio_label{
            font-family:' . esc_html( $arf_input_font_family ) . ';
            font-size:' . esc_html( $arf_label_font_size ) . 'px !important;
            color:' . esc_html( $field_label_txt_color ) . ' !important;
            ' . esc_html( $arf_input_font_style_str ) . '
            display:inline-block;
            cursor:pointer;
            width:auto;
        }';

		echo esc_html( $arf_form_cls_prefix ) . " .setting_radio.arf_standard_radio .arf_radio_input_wrapper input[type='radio'] + span{";
			echo 'border-color:' . esc_html( $field_border_color ) . ';';
		echo '}';

		echo esc_html( $arf_form_cls_prefix ) . ' .arf_fieldset .setting_radio.arf_single_row .arf_radio_input_wrapper + label {';
		echo 'position:relative;';
		echo '}';

		echo esc_html( $arf_form_cls_prefix ) . ' .arf_fieldset .arf_horizontal_radio .setting_radio .arf_radio_input_wrapper + label:not(.arf_enable_radio_image) {';
		echo 'position:relative;';
		echo '}';

		echo esc_html( $arf_form_cls_prefix ) . ' .setting_radio .arf_enable_radio_image .arf_radio_input_wrapper,';
		echo esc_html( $arf_form_cls_prefix ) . ' .setting_radio .arf_enable_radio_image_editor .arf_radio_input_wrapper{';
		echo 'position: absolute !important;';
		echo '}';

		echo esc_html( $arf_form_cls_prefix ) . ' .setting_radio.arf_standard_radio .arf_radio_input_wrapper + label{';
		echo 'vertical-align: middle;';
		echo 'width: auto;';
		echo 'word-wrap: break-word;';
		echo 'display: inline-flex;';
		echo 'align-self: center;';
		echo 'margin:unset;';
		echo 'line-height:1.1em !important;';
		echo 'word-break: break-word;';
		echo '}';

		echo esc_html( $arf_form_cls_prefix ) . ' .setting_radio.controls.arf_standard_radio:not(.arf_multiple_row){';
			echo 'display:flex;flex-wrap:wrap;';
		echo '}';


		echo esc_html( $arf_form_cls_prefix ) . ' .setting_radio.arf_material_radio .arf_radio_input_wrapper + label,';
		echo esc_html( $arf_form_cls_prefix ) . ' .setting_radio.arf_custom_radio .arf_radio_input_wrapper + label{';
		echo 'margin:unset;';
		echo '}';

		echo esc_html( $arf_form_cls_prefix ) . ' .setting_radio.arf_rounded_flat_radio .arf_radio_input_wrapper {';
		echo 'display: inline-block;';
		echo 'width: 20px;';
		echo 'height: 20px;';
		echo 'position: relative;';
		echo 'margin-right: 10px;';
		echo 'vertical-align: middle;';
		echo 'float:none;';
		echo '}';

		echo esc_html( $arf_form_cls_prefix ) . ' .setting_radio.arf_rounded_flat_radio .arf_radio_input_wrapper + label{';
		echo 'vertical-align: middle;';
		echo 'margin:unset;';
		echo '}';

		echo esc_html( $arf_form_cls_prefix ) . " .setting_radio.arf_rounded_flat_radio .arf_radio_input_wrapper input[type='radio'] {";
		echo 'position: absolute;';
		echo 'left: 0;';
		echo 'top: 0;';
		echo 'width: 20px;';
		echo 'height: 20px;';
		echo 'opacity: 0;';
		echo 'margin: 0;';
		echo 'z-index: 1;';
		echo 'cursor: pointer;';
		echo '}';

		echo esc_html( $arf_form_cls_prefix ) . " .setting_radio.arf_rounded_flat_radio .arf_radio_input_wrapper input[type='radio'] + span::before, .arf_form_outer_wrapper .setting_radio.arf_rounded_flat_radio .arf_radio_input_wrapper input[type='radio'] + span::after {";
		echo "content: '';";
		echo 'position: absolute;';
		echo 'left: 0;';
		echo 'top: 0;';
		echo 'width: 20px;';
		echo 'height: 20px;';
		echo 'z-index: 0;';
		echo '-webkit-border-radius: 50%;';
		echo '-o-border-radius: 50%;';
		echo '-moz-border-radius: 50%;';
		echo 'border-radius: 50%;';
		echo '-ms-border-radius: 50%;';
		echo 'border: 4px solid #d7dcde;';
		echo '}';

		echo esc_html( $arf_form_cls_prefix ) . " .setting_radio.arf_rounded_flat_radio .arf_radio_input_wrapper input[type='radio']:checked + span::after {";
		echo '-webkit-transform:scale(0.3);';
		echo '-moz-transform:scale(0.3);';
		echo '-o-transform:scale(0.3);';
		echo 'transform:scale(0.3);';
		echo '}';

		echo esc_html( $arf_form_cls_prefix ) . ' .setting_radio.arf_custom_radio .arf_radio_input_wrapper {';
		echo 'float:none;';
		echo 'width:20px;';
		echo 'height:22px;';
		echo 'position:relative;';
		echo 'margin-right:10px;';
		echo 'display: inline-block;';
		echo 'vertical-align: middle;';
		echo 'text-align: center;';
		echo '}';

		echo esc_html( $arf_form_cls_prefix ) . ' .setting_radio.arf_custom_radio .arf_radio_input_wrapper + label{';
		echo 'vertical-align: middle;';
		echo '}';

		echo esc_html( $arf_form_cls_prefix ) . " .setting_radio.arf_custom_radio .arf_radio_input_wrapper input[type='radio'] {";
		echo 'position:absolute;';
		echo 'left:0;';
		echo 'top:0;';
		echo 'width:22px;';
		echo 'height:22px;';
		echo 'opacity:0;';
		echo 'margin:0;';
		echo 'z-index:1;';
		echo 'cursor: pointer;';
		echo '}';

		echo esc_html( $arf_form_cls_prefix ) . " .setting_radio.arf_custom_radio .arf_radio_input_wrapper input[type='radio'] + span {";
		echo 'float:none;';
		echo 'width:20px;';
		echo 'height:20px;';
		echo 'border:1px solid #5a5a5a;';
		echo 'border-radius:50%;';
		echo '-webkit-border-radius:50%;';
		echo '-o-border-radius:50%;';
		echo '-moz-border-radius:50%;';
		echo 'position:relative;';
		echo 'display:inline-block;';
		echo 'vertical-align: middle;';
		echo 'text-align: center;';
		echo 'top:-1px;';
		echo '}';

		echo esc_html( $arf_form_cls_prefix ) . " .setting_radio.arf_custom_radio .arf_radio_input_wrapper input[type='radio'] + span i {";
		echo 'position: absolute;';
		echo 'top: 50%;';
		echo 'left:50%;';
		echo 'transform: translate(-50%,-50%);';
		echo '-webkit-transform: translate(-50%,-50%);';
		echo '-o-transform: translate(-50%,-50%);';
		echo '-ms-transform: translate(-50%,-50%);';
		echo '-moz-transform: translate(-50%,-50%);';
		echo 'font-size: 14px;';
		echo 'display: none;';
		echo '}';

		echo esc_html( $arf_form_cls_prefix ) . " .setting_radio.arf_custom_radio .arf_radio_input_wrapper input[type='radio']:checked + span i {";
		echo 'display:inline-block;';
		echo '}';

		echo esc_html( $arf_form_cls_prefix ) . ' .arf_enable_radio_image div:not(.arf_radio_label),';
		echo esc_html( $arf_form_cls_prefix ) . ' .arf_enable_radio_image_editor div:not(.arf_radio_label){';
		echo 'opacity: 0 !important;';
		echo '}';

		echo esc_html( $arf_form_cls_prefix ) . ' .arf_enable_radio_image,';
		echo esc_html( $arf_form_cls_prefix ) . ' .arf_enable_radio_image_editor{';
		echo 'cursor : pointer;';
		echo 'align-self:baseline;';
		echo '}';

		echo '.arf_form_editor_content .arf_radio_input_wrapper{';
		echo 'line-height: normal !important;';
		echo '}';

		echo esc_html( $arf_form_cls_prefix ) . ' .left_container .setting_radio .help-block{';
		echo 'margin-left:0px;';
		echo '}';

		echo esc_html( $arf_form_cls_prefix ) . " .arfformfield .setting_radio.arf_custom_radio .arf_radio_input_wrapper input[type='radio']:checked + span {";
			echo 'border-color:' . esc_html( $base_color ) . ';';
		echo '}';
		echo esc_html( $arf_form_cls_prefix ) . " .arfformfield .setting_radio.arf_custom_radio .arf_radio_input_wrapper input[type='radio']:checked + span i{";
			echo 'display:block;';
			echo 'height:auto; width:auto;';
			echo 'color:' . esc_html( $base_color ) . '';
		echo '}';

		echo esc_html( $arf_form_cls_prefix ) . " .arfformfield .setting_radio.arf_rounded_flat_radio .arf_radio_input_wrapper input[type='radio']:checked + span::before{";
			echo 'border:4px solid ' . esc_html( $base_color ) . ';';
		echo '}';
		echo esc_html( $arf_form_cls_prefix ) . " .arfformfield .setting_radio.arf_rounded_flat_radio .arf_radio_input_wrapper input[type='radio']:checked + span::after{";
			echo '-webkit-transform: scale(0.3);-o-transform: scale(0.3);-moz-transform: scale(0.3);transform: scale(0.3);-ms-transform: scale(0.3);background: ' . esc_html( $base_color ) . ';border: 2px solid ' . esc_html( $base_color ) . ';';
		echo '}';
		echo esc_html( $arf_form_cls_prefix ) . " .arfformfield .setting_radio.arf_standard_radio .arf_radio_input_wrapper input[type='radio']:checked + span{";
			echo 'background:' . esc_html( $base_color ) . ';';
			echo 'border-color:' . esc_html( $base_color ) . ';';
		echo '}';

		echo esc_html( $arf_form_cls_prefix ) . " .setting_radio.arf_standard_radio .arf_radio_input_wrapper input[type='radio'] {";
			echo 'position: absolute;';
			echo 'left: 0;';
			echo 'top: 0;';
			echo 'width: 20px;';
			echo 'height: 20px;';
			echo 'opacity: 0;';
			echo 'margin: 0;';
			echo 'z-index: 1;';
			echo 'cursor: pointer;';
		echo '}';

		echo esc_html( $arf_form_cls_prefix ) . " .setting_radio.arf_standard_radio .arf_radio_input_wrapper input[type='radio'] + span {";
			echo 'position: absolute;';
			echo 'width: 20px;';
			echo 'height: 20px;';
			echo 'border-width: 1px;';
			echo 'border-style: solid;';
			echo 'margin-right: 5px;';
			echo '-webkit-border-radius: 50%;';
			echo '-o-border-radius: 50%;';
			echo '-moz-border-radius: 50%;';
			echo 'border-radius: 50%;';
			echo 'left:0;';
		echo '}';


		echo esc_html( $arf_form_cls_prefix ) . " .setting_radio.arf_standard_radio .arf_radio_input_wrapper input[type='radio']:checked + span::before {";
			echo 'top: 50%;';
			echo 'left: 50%;';
			echo "content: '';";
			echo 'position: absolute;';
			echo 'width: 30%;';
			echo 'height: 50%;';
			echo 'border-top: 2px solid transparent;';
			echo 'border-left: 2px solid transparent;';
			echo 'border-right: 2px solid #fff;';
			echo 'border-bottom: 2px solid #fff;';
			echo '-webkit-transform: rotateZ(40deg) translate(-50%,-50%);';
			echo '-o-transform: rotateZ(40deg) translate(-50%,-50%);';
			echo '-moz-transform: rotateZ(40deg) translate(-50%,-50%);';
			echo 'transform: rotateZ(40deg) translate(-50%,-50%);';
			echo '-ms-transform: rotate(40deg) translate(-50%,-50%);';
			echo '-webkit-transform-origin: 40% -15%;';
			echo '-o-transform-origin: 40% -15%;';
			echo '-moz-transform-origin: 40% -15%;';
			echo 'transform-origin: 40% -15%;';
			echo '-ms-transform-origin: 40% -15%;';
		echo '}';

		echo esc_html( $arf_form_cls_prefix ) . ' .setting_radio.arf_standard_radio .arf_radio_input_wrapper {';
			echo 'float:none;';
			echo 'width: 20px;';
			echo 'height: 20px;';
			echo 'position: relative;';
			echo 'margin-right: 10px;';
			echo 'display:inline-block;';
			echo 'vertical-align: middle;';
			echo 'line-height: 32px;';
		echo '}';

		if ( $arf_label_font_size > 20 ) {
			echo esc_html( $arf_form_cls_prefix ) . ' .setting_radio.arf_custom_radio .arf_radio_input_wrapper,';
			echo esc_html( $arf_form_cls_prefix ) . ' .setting_radio.arf_custom_radio .arf_radio_input_wrapper input[type="radio"],';
			echo esc_html( $arf_form_cls_prefix ) . ' .setting_radio.arf_custom_radio .arf_radio_input_wrapper input[type="radio"] + span,';
			echo esc_html( $arf_form_cls_prefix ) . ' .setting_radio.arf_custom_radio .arf_radio_input_wrapper input[type="radio"] + span:before,';
			echo esc_html( $arf_form_cls_prefix ) . ' .setting_radio.arf_custom_radio .arf_radio_input_wrapper input[type="radio"] + span:after,';
			echo esc_html( $arf_form_cls_prefix ) . ' .setting_radio.arf_rounded_flat_radio .arf_radio_input_wrapper,';
			echo esc_html( $arf_form_cls_prefix ) . ' .setting_radio.arf_rounded_flat_radio .arf_radio_input_wrapper input[type="radio"],';
			echo esc_html( $arf_form_cls_prefix ) . ' .setting_radio.arf_rounded_flat_radio .arf_radio_input_wrapper input[type="radio"] + span,';
			echo esc_html( $arf_form_cls_prefix ) . ' .setting_radio.arf_rounded_flat_radio .arf_radio_input_wrapper input[type="radio"] + span:before,';
			echo esc_html( $arf_form_cls_prefix ) . ' .setting_radio.arf_rounded_flat_radio .arf_radio_input_wrapper input[type="radio"] + span:after,';
			echo esc_html( $arf_form_cls_prefix ) . ' .setting_radio.arf_standard_radio .arf_radio_input_wrapper,';
			echo esc_html( $arf_form_cls_prefix ) . ' .setting_radio.arf_standard_radio .arf_radio_input_wrapper input[type="radio"],';
			echo esc_html( $arf_form_cls_prefix ) . ' .setting_radio.arf_standard_radio .arf_radio_input_wrapper input[type="radio"] + span:before,';
			echo esc_html( $arf_form_cls_prefix ) . ' .setting_radio.arf_standard_radio .arf_radio_input_wrapper input[type="radio"] + span:after,';
			echo esc_html( $arf_form_cls_prefix ) . ' .setting_radio.arf_standard_radio .arf_radio_input_wrapper input[type="radio"] + span{';
				echo 'width:' . ( esc_html( $arf_label_font_size ) + 2 ) . 'px;';
				echo 'height:' . ( esc_html( $arf_label_font_size ) + 2 ) . 'px;';
			echo '}';
		}
	}

	if ( in_array( 'select', $loaded_field ) ) {
		echo esc_html( $arf_form_cls_prefix ) . ' .sltstandard_front .arf-selectpicker-control.arf_form_field_picker dt{
            border:' . esc_html( $field_border_width ) . ' ' . esc_html( $field_border_style ) . ' ' . esc_html( $field_border_color ) . ';';
		if ( 1 == $arfmainfield_opacity ) {
			echo 'background:transparent;';
		} else {
			echo 'background-color:' . esc_html( $field_bg_color ) . ';';
		}
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
            padding: ' . esc_html( $arf_field_inner_padding ) . ' !important;
            line-height: normal;
            width:100%;
            margin-top:0px;
        }';

		if( !empty($field_border_radius_tablet)){

            echo ".arfdevicetablet " .esc_html($arf_form_cls_prefix). ".sltstandard_front .arf-selectpicker-control.arf_form_field_picker dt{
                border: ".esc_html($field_border_width). esc_html($field_border_style). esc_html($field_border_color).";";
                echo "border-radius:".esc_html($field_border_radius_tablet).";";
                echo "-webkit-border-radius:".esc_html($field_border_radius_tablet).";";
                echo "-o-border-radius:".esc_html($field_border_radius_tablet).";";
                echo "-moz-border-radius:".esc_html($field_border_radius_tablet).";";
            echo "}";


            echo "@media all and (max-width:768px){";
                echo esc_html($arf_form_cls_prefix)." .sltstandard_front .arf-selectpicker-control.arf_form_field_picker dt{
                    border: ".esc_html($field_border_width). esc_html($field_border_style). esc_html($field_border_color).";";
                    echo "border-radius:".esc_html($field_border_radius_tablet).";";
                    echo "-webkit-border-radius:".esc_html($field_border_radius_tablet).";";
                    echo "-o-border-radius:".esc_html($field_border_radius_tablet).";";
                    echo "-moz-border-radius:".esc_html($field_border_radius_tablet).";";
                echo "}";
            echo "}";
        }

        if( !empty( $field_border_radius_mobile )){

            echo ".arfdevicemobile " .esc_html($arf_form_cls_prefix). ".sltstandard_front .arf-selectpicker-control.arf_form_field_picker dt{
                border: ".esc_html($field_border_width). esc_html($field_border_style). esc_html($field_border_color).";";
                echo "border-radius:".esc_html($field_border_radius_tablet).";";
                echo "-webkit-border-radius:".esc_html($field_border_radius_tablet).";";
                echo "-o-border-radius:".esc_html($field_border_radius_tablet).";";
                echo "-moz-border-radius:".esc_html($field_border_radius_tablet).";";    
            echo "}";

            echo "@media all and (max-width:576px){";
                echo esc_html($arf_form_cls_prefix)." .sltstandard_front .arf-selectpicker-control.arf_form_field_picker dt{
                    border: ".esc_html($field_border_width). esc_html($field_border_style). esc_html($field_border_color).";";
                    echo "border-radius:".esc_html($field_border_radius_mobile).";";
                    echo "-webkit-border-radius:".esc_html($field_border_radius_mobile).";";
                    echo "-o-border-radius:".esc_html($field_border_radius_mobile).";";
                    echo "-moz-border-radius:".esc_html($field_border_radius_mobile).";";
                echo "}";
            echo "}";
        }

		if ( 'rtl' == $arf_input_field_direction ) {
			echo esc_html( $arf_form_cls_prefix ) . ' .sltstandard_front .arf-selectpicker-control.arf_form_field_picker dt i{';
				echo 'right:unset;';
				echo 'left:8px;';
			echo '}';

			echo esc_html( $arf_form_cls_prefix ) . ' .sltstandard_front .arf-selectpicker-control.arf_form_field_picker dt span{';
				echo 'text-align:right;';
				echo 'float:right !important;';
				echo 'right:0;';
				echo 'left:unset !important;';
			echo '}';

			echo esc_html( $arf_form_cls_prefix ) . ' .sltstandard_front .arf-selectpicker-control.arf_form_field_picker dd ul li{';
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

		echo esc_html( $arf_form_cls_prefix ) . ' .sltstandard_front .arf-selectpicker-control.arf_form_field_picker dd ul{
            -moz-border-radius:' . esc_html( $field_border_radius ) . ';
            -webkit-border-radius:' . esc_html( $field_border_radius ) . ';
            -o-border-radius:' . esc_html( $field_border_radius ) . ';
            border-radius:' . esc_html( $field_border_radius ) . ';
            border-style:' . esc_html( $field_border_style ) . ';
        }';

		if( !empty($field_border_radius_tablet)){

            echo ".arfdevicetablet " .esc_html($arf_form_cls_prefix). ".sltstandard_front .arf-selectpicker-control.arf_form_field_picker dd ul{";
                echo "border-radius:".esc_html($field_border_radius_tablet).";";
                echo "-webkit-border-radius:".esc_html($field_border_radius_tablet).";";
                echo "-o-border-radius:".esc_html($field_border_radius_tablet).";";
                echo "-moz-border-radius:".esc_html($field_border_radius_tablet).";";    
            echo "}";


            echo "@media all and (max-width:768px){";
                echo esc_html($arf_form_cls_prefix)." .sltstandard_front .arf-selectpicker-control.arf_form_field_picker dd ul{";
                    echo "border-radius:".esc_html($field_border_radius_tablet).";";
                    echo "-webkit-border-radius:".esc_html($field_border_radius_tablet).";";
                    echo "-o-border-radius:".esc_html($field_border_radius_tablet).";";
                    echo "-moz-border-radius:".esc_html($field_border_radius_tablet).";";
                echo "}";
            echo "}";
        }

        if( !empty( $field_border_radius_mobile )){

            echo ".arfdevicemobile " .esc_html($arf_form_cls_prefix). ".sltstandard_front .arf-selectpicker-control.arf_form_field_picker dd ul{";
                echo "border-radius:".esc_html($field_border_radius_tablet).";";
                echo "-webkit-border-radius:".esc_html($field_border_radius_tablet).";";
                echo "-o-border-radius:".esc_html($field_border_radius_tablet).";";
                echo "-moz-border-radius:".esc_html($field_border_radius_tablet).";";    
            echo "}";

            echo "@media all and (max-width:576px){";
                echo esc_html($arf_form_cls_prefix)." .sltstandard_front .arf-selectpicker-control.arf_form_field_picker dd ul{";
                    echo "border-radius:".esc_html($field_border_radius_mobile).";";
                    echo "-webkit-border-radius:".esc_html($field_border_radius_mobile).";";
                    echo "-o-border-radius:".esc_html($field_border_radius_mobile).";";
                    echo "-moz-border-radius:".esc_html($field_border_radius_mobile).";";
                echo "}";
            echo "}";
        }

		echo esc_html( $arf_form_cls_prefix ) . ' .sltstandard_front .arf-selectpicker-control.arf_form_field_picker dt span{
            font-size:' . esc_html( $arf_input_font_size ) . 'px;
            color:' . esc_html( $field_text_color ) . ' !important; 
            font-family:' . esc_html( $arf_input_font_family ) . ';
            ' . esc_html( $arf_input_font_style_str ) . '
            float:left;
            left:0;
            text-transform: none;
        }';

		echo esc_html( $arf_form_cls_prefix ) . ' .sltstandard_front .arf-selectpicker-control.arf_form_field_picker.open dt{
            border:' . esc_html( $field_border_width ) . ' ' . esc_html( $field_border_style ) . ' ' . esc_html( $base_color ) . ';';
		if ( 1 == $arfmainfield_opacity ) {
			echo 'background:transparent;';
		} else {
			echo 'background:' . esc_html( $field_focus_bg_color ) . ';';
		}
		echo 'background-image:none;
            box-shadow:none;
            -webkit-box-shadow:none;
            -o-box-shadow:none;
            -moz-box-shadow:none;
            outline:0 !important;
            width:100%;
            margin-top:0px;
            min-height:' . ( ( esc_html( $arf_input_font_size ) ) + ( 2 * (int) esc_html( $field_border_width ) ) ) . 'px;
        }';

		echo esc_html( $arf_form_cls_prefix ) . ' .sltstandard_front .arf-selectpicker-control.arf_form_field_picker.open:not(.open_from_top) dt{
            border-bottom-left-radius:0px !important;
            border-bottom-right-radius:0px !important;
        }';

		echo esc_html( $arf_form_cls_prefix ) . ' .arf_rounded_form .sltstandard_front .arf-selectpicker-control.arf_form_field_picker.open:not(.open_from_top) dt{
            border-top-left-radius:20px !important;
            border-top-right-radius:20px !important;
        }';

		echo esc_html( $arf_form_cls_prefix ) . ' .sltstandard_front .arf-selectpicker-control.arf_form_field_picker.open.open_from_top dt{
            border-top-left-radius:0px !important;
            border-top-right-radius:0px !important;
        }';

		echo esc_html( $arf_form_cls_prefix ) . ' .arf_rounded_form .sltstandard_front .arf-selectpicker-control.arf_form_field_picker.open.open_from_top dt{
            border-bottom-left-radius: 20px !important;
            border-bottom-right-radius: 20px !important;
        }';

		echo esc_html( $arf_form_cls_prefix ) . ' .sltstandard_front .arf-selectpicker-control.arf_form_field_picker dd{
            border:none;
        }';

		echo esc_html( $arf_form_cls_prefix ) . ' .sltstandard_front .arf-selectpicker-control.arf_form_field_picker.open dd{
            float:left;
            width: 100%;
            position:relative;
        }';

		echo esc_html( $arf_form_cls_prefix ) . ' .sltstandard_front .arf-selectpicker-control.arf_form_field_picker.open dd ul{
            border: ' . esc_html( $field_border_width ) . ' ' . esc_html( $field_border_style ) . ' ' . esc_html( $base_color ) . ';
            border-top:none;
            background-color: ' . esc_html( $field_bg_color ) . ';
            box-shadow:none;
            -webkit-box-shadow:none;
            -o-box-shadow:none;
            -moz-box-shadow:none;
            margin:0;
            margin-top:-' . esc_html( $field_border_width ) . ';
            width:100%;
        }';

		echo esc_html( $arf_form_cls_prefix ) . ' .sltstandard_front .arf-selectpicker-control.arf_form_field_picker.open:not(.open_from_top) dd ul{
            border-top-left-radius:0px !important;
            border-top-right-radius:0px !important;
        }';

		echo esc_html( $arf_form_cls_prefix ) . ' .sltstandard_front .arf-selectpicker-control.arf_form_field_picker.open:not(.open_from_top) dd ul{
            border-bottom-left-radius:3px !important;
            border-bottom-right-radius:3px !important;
        }';

		echo esc_html( $arf_form_cls_prefix ) . ' .sltstandard_front .arf-selectpicker-control.arf_form_field_picker.open.open_from_top dd ul{
            border:' . esc_html( $field_border_width ) . ' ' . esc_html( $field_border_style ) . ' ' . esc_html( $field_border_color ) . ';
            border-bottom:none;
            border-bottom-left-radius:0px !important;
            border-bottom-right-radius:0px !important;
            border-top-left-radius:3px !important;
            border-top-right-radius:3px !important;
        }';

		echo esc_html( $arf_form_cls_prefix ) . ' .sltstandard_front .arf-selectpicker-control.arf_form_field_picker dd ul > li {
            font-size:' . esc_html( $arf_input_font_size ) . 'px;
            color:' . esc_html( $field_text_color ) . ';
            font-family:' . esc_html( $arf_input_font_family ) . ';
            ' . esc_html( $arf_input_font_style_str ) . '';
		if ( $arf_input_font_size >= 36 ) {
			echo 'padding:14px 12px;';
		} elseif ( $arf_input_font_size >= 28 ) {
			echo 'padding:12px 12px;';
		} elseif ( $arf_input_font_size >= 24 ) {
			echo 'padding:10px 12px;';
		} elseif ( $arf_input_font_size >= 22 ) {
			echo 'padding:08px 12px;';
		} elseif ( $arf_input_font_size >= 20 ) {
			echo 'padding:06px 12px;';
		} elseif ( $arf_input_font_size >= 24 ) {
			echo 'padding:10px 12px;';
		} elseif ( $arf_input_font_size <= 18 ) {
			echo 'padding:3px 12px;';
		} else {
			echo 'padding:' . esc_html( $fieldpadding_1 ) . 'px ' . ( (int) esc_html( $fieldpadding_2 ) + 13 ) . 'px ' . esc_html( $fieldpadding_1 ) . 'px ' . ( esc_html( $fieldpadding_2 ) ) . 'px !important; ';
		}

			echo 'line-height: normal;
            text-align: ' . esc_html( $arf_input_field_text_align ) . ';
            text-transform: none;
        }';

		echo esc_html( $arf_form_cls_prefix ) . ' .sltstandard_front .arf-selectpicker-control.arf_form_field_picker dd ul > li.arm_sel_opt_checked::before,
        ' . esc_html( $arf_form_cls_prefix ) . ' .sltstandard_front .arf-selectpicker-control.arf_form_field_picker dd ul > li.arm_sel_opt_checked::after{
            background: ' . esc_html( $field_text_color ) . ' !important;
        }';

		echo esc_html( $arf_form_cls_prefix ) . ' .arf-selectpicker-control.arf_form_field_picker dd ul li.hovered,
        ' . esc_html( $arf_form_cls_prefix ) . ' .arf-selectpicker-control.arf_form_field_picker dd ul li:hover{
            color: #ffffff !important;    
            background-color: ' . esc_html( $base_color ) . ' !important;
        }';

		echo esc_html( $arf_form_cls_prefix ) . ' .sltstandard_front .arf-selectpicker-control.arf_form_field_picker dd ul > li.hovered.arm_sel_opt_checked::before,
        ' . esc_html( $arf_form_cls_prefix ) . ' .sltstandard_front .arf-selectpicker-control.arf_form_field_picker dd ul > li.hovered.arm_sel_opt_checked::after,
        ' . esc_html( $arf_form_cls_prefix ) . ' .sltstandard_front .arf-selectpicker-control.arf_form_field_picker dd ul > li:hover.arm_sel_opt_checked::before,
        ' . esc_html( $arf_form_cls_prefix ) . ' .sltstandard_front .arf-selectpicker-control.arf_form_field_picker dd ul > li:hover.arm_sel_opt_checked::after{
            background: #ffffff !important;
        }';
	}

	if ( in_array( 'date', $loaded_field ) || in_array( 'time', $loaded_field ) ) {
		echo esc_html( $arf_form_cls_prefix ) . ' .arf_cal_header, ' . esc_html( $arf_form_cls_prefix ) . ' .arf_cal_month{background-color:' . esc_html( $base_color ) . '; color: ' . esc_html( $arf_date_picker_text_color ) . ' !important;}';
		echo esc_html( $arf_form_cls_prefix ) . ' .bootstrap-datetimepicker-widget table tbody tr{background:#FFFFFF !important;}';
		echo esc_html( $arf_form_cls_prefix ) . ' .picker-switch td span:hover{background-color:' . esc_html( $base_color ) . ' !important;}';

		echo esc_html( $arf_form_cls_prefix ) . ' .bootstrap-datetimepicker-widget table td span.active { background-color:' . esc_html( $base_color ) . '; color: ' . esc_html( $arf_date_picker_text_color ) . '; }';
		echo esc_html( $arf_form_cls_prefix ) . ' .bootstrap-datetimepicker-widget table td span:hover{ border-color:' . esc_html( $base_color ) . ';}';

		echo esc_html( $arf_form_cls_prefix ) . ' .bootstrap-datetimepicker-widget table td.active, 
        ' . esc_html( $arf_form_cls_prefix ) . ' .bootstrap-datetimepicker-widget table td.active:hover{ 
            color: ' . esc_html( $base_color ) . ' !important; 
        }';

		echo '.bootstrap-datetimepicker-widget table td.old, .bootstrap-datetimepicker-widget table td.new{color: #96979a !important;}';

		echo esc_html( $arf_form_cls_prefix ) . ' .bootstrap-datetimepicker-widget table td.day,' . esc_html( $arf_form_cls_prefix ) . ' .bootstrap-datetimepicker-widget table span.month,' . esc_html( $arf_form_cls_prefix ) . ' .bootstrap-datetimepicker-widget table span.year:not(.disabled),' . esc_html( $arf_form_cls_prefix ) . ' .bootstrap-datetimepicker-widget table span.decade:not(.disabled){
        color :' . esc_html( $arf_date_picker_text_color ) . ';
        }';

		echo esc_html( $arf_form_cls_prefix ) . ' .bootstrap-datetimepicker-widget table td.day:not(.active):hover {
        background-color: #F5F5F5;border-radius: 50px;-webkit-border-radius: 50px;-o-border-radius: 50px;-moz-border-radius: 50px;display:block;
        }';

		echo esc_html( $arf_form_cls_prefix ) . ' .bootstrap-datetimepicker-widget table td.active:not(.disabled), 
        ' . esc_html( $arf_form_cls_prefix ) . ' .bootstrap-datetimepicker-widget table td.active:not(.disabled):hover{
        background-image : url("data:image/svg+xml;base64,' . esc_html( base64_encode("<svg width='35px' xmlns='http://www.w3.org/2000/svg' height='29px'><path fill='rgb(" . $arflitesettingcontroller->arflitehex2rgb( esc_html( $base_color ) ) . ")' d='M15.732,27.748c0,0-14.495,0.2-14.71-11.834c0,0,0.087-7.377,7.161-11.82 c0,0,0.733-0.993-1.294-0.259c0,0-1.855,0.431-3.538,2.2c0,0-1.078,0.216-0.388-1.381c0,0,2.416-3.019,8.585-2.76 c0,0,2.372-2.458,7.419-1.293c0,0,0.819,0.517-0.518,0.819c0,0-5.361,0.514-3.753,1.122c0,0,14.021,3.073,14.322,13.943 C29.019,16.484,29.573,27.32,15.732,27.748z M26.991,16.182C26.24,7.404,14.389,3.543,14.389,3.543 c-2.693-0.747-4.285,0.683-4.285,0.683C8.767,4.969,6.583,7.804,6.583,7.804C2.216,13.627,3.612,18.47,3.612,18.47 c2.168,7.635,12.505,7.097,12.505,7.097C27.376,25.418,26.991,16.182,26.991,16.182z'/></svg>" )) . '") !important;
        background-repeat:no-repeat;
        }';

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
            border-radius:4px 4px 4px 4px !important;
            -moz-border-radius:4px 4px 4px 4px !important;
            -webkit-border-radius:4px 4px 4px 4px !important;
            -o-border-radius:4px 4px 4px 4px !important;
            -webkit-transform: translateZ(0);
            -o-transform:translateZ(0);
            background: #ffffff !important;
            border: transparent !important;    
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



		echo esc_html( $arf_form_cls_prefix ) . ' .picker-switch td span.arf-glyphicon-time,
        ' . esc_html( $arf_form_cls_prefix ) . ' .picker-switch td span.arf-glyphicon-calendar{
            background-color: ' . esc_html( $base_color ) . ';
        }';


		echo esc_html( $arf_form_cls_prefix ) . ' .arf-glyphicon-time:before,
        ' . esc_html( $arf_form_cls_prefix ) . ' .arf-glyphicon-calendar:before{
            color:' . ( ( $arflitesettingcontroller->arfliteisColorDark( esc_html( $base_color ) ) == '1' ) ? '#ffffff' : '#1A1A1A' ) . ' !important;
        }';

		echo esc_html( $arf_form_cls_prefix ) . ' .arf_cal_header th, 
        ' . esc_html( $arf_form_cls_prefix ) . ' .arf_cal_month th{ 
            color :' . ( ( $arflitesettingcontroller->arfliteisColorDark( esc_html( $base_color ) ) == '1' ) ? '#ffffff' : '#1A1A1A' ) . '!important;
        }';

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

		echo esc_html($arf_form_cls_prefix)." .sltstandard_time .btn-group .arfbtn.dropdown-toggle{
            border:" . esc_html( $field_border_width ) . ' ' . esc_html( $field_border_style ) . ' ' . esc_html( $field_border_color ) . ' !important;
            background-color:' . esc_html( $field_bg_color ) . ' !important;
            background-image:none;
            box-shadow:none;
            -webkit-box-shadow:none;
            -o-box-shadow:none;
            -moz-box-shadow:none;
            outline:0 !important;
            -moz-border-radius:' . esc_html( $field_border_radius ) . ' !important;
            -webkit-border-radius:' . esc_html( $field_border_radius ) . ' !important;
            -o-border-radius:' . esc_html( $field_border_radius ) . ' !important;
            border-radius:' . esc_html( $field_border_radius ) . ';
            padding:' . esc_html( $arf_field_inner_padding ) . ' !important;
            line-height: normal;
            font-size:' . esc_html( $arf_input_font_size ) . 'px;
            color:' . esc_html( $field_text_color ) . ';; 
            font-family:' . esc_html( $arf_input_font_family ) . ';
            ' . esc_html( $arf_input_font_style_str ) . '
            width:100%;
            margin-top:0px;    
        }';

		if( !empty($field_border_radius_tablet)){

            echo ".arfdevicetablet " .esc_html($arf_form_cls_prefix)." .sltstandard_time .btn-group .arfbtn.dropdown-toggle{";
                echo "border-radius:".esc_html($field_border_radius_tablet)." !important;";
                echo "-webkit-border-radius:".esc_html($field_border_radius_tablet)." !important;";
                echo "-o-border-radius:".esc_html($field_border_radius_tablet)." !important;";
                echo "-moz-border-radius:".esc_html($field_border_radius_tablet).";";    
            echo "}";


            echo "@media all and (max-width:768px){";
                echo esc_html($arf_form_cls_prefix)." .sltstandard_time .btn-group .arfbtn.dropdown-toggle{
                    -moz-border-radius:".esc_html($field_border_radius_tablet)." !important;
                    -webkit-border-radius:".esc_html($field_border_radius_tablet)." !important;
                    -o-border-radius:".esc_html($field_border_radius_tablet)." !important;
                    border-radius:".esc_html($field_border_radius_tablet).";
                }";
            echo "}";
        }

        if( !empty( $field_border_radius_mobile )){

            echo ".arfdevicemobile " .esc_html($arf_form_cls_prefix)." .sltstandard_time .btn-group .arfbtn.dropdown-toggle{";
                echo "border-radius:".esc_html($field_border_radius_tablet)." !important;";
                echo "-webkit-border-radius:".esc_html($field_border_radius_tablet)." !important;";
                echo "-o-border-radius:".esc_html($field_border_radius_tablet)." !important;";
                echo "-moz-border-radius:".esc_html($field_border_radius_tablet).";";    
            echo "}";

            echo "@media all and (max-width:576px){";
                echo esc_html($arf_form_cls_prefix)." .sltstandard_time .btn-group .arfbtn.dropdown-toggle{
                    -moz-border-radius:".esc_html($field_border_radius_mobile)." !important;
                    -webkit-border-radius:".esc_html($field_border_radius_mobile)." !important;
                    -o-border-radius:".esc_html($field_border_radius_mobile)." !important;
                    border-radius:".esc_html($field_border_radius_mobile).";
                }";
            echo "}";
        }


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

		if( !empty($field_border_radius_tablet)){

            echo ".arfdevicetablet " .esc_html($arf_form_cls_prefix)." .sltstandard_time .btn-group .arfbtn.dropdown-toggle{";
                echo "border-radius:".esc_html($field_border_radius_tablet)." !important;";
                echo "-webkit-border-radius:".esc_html($field_border_radius_tablet)." !important;";
                echo "-o-border-radius:".esc_html($field_border_radius_tablet)." !important;";
                echo "-moz-border-radius:".esc_html($field_border_radius_tablet).";";    
            echo "}";


            echo "@media all and (max-width:768px){";
                echo esc_html($arf_form_cls_prefix)." .sltstandard_time .btn-group .arfbtn.dropdown-toggle{
                    -moz-border-radius:".esc_html($field_border_radius_tablet)." !important;
                    -webkit-border-radius:".esc_html($field_border_radius_tablet)." !important;
                    -o-border-radius:".esc_html($field_border_radius_tablet)." !important;
                    border-radius:".esc_html($field_border_radius_tablet).";
                }";
            echo "}";
        }

        if( !empty( $field_border_radius_mobile )){

            echo ".arfdevicemobile " .esc_html($arf_form_cls_prefix)." .sltstandard_time .btn-group .arfbtn.dropdown-toggle{";
                echo "border-radius:".esc_html($field_border_radius_tablet)." !important;";
                echo "-webkit-border-radius:".esc_html($field_border_radius_tablet)." !important;";
                echo "-o-border-radius:".esc_html($field_border_radius_tablet)." !important;";
                echo "-moz-border-radius:".esc_html($field_border_radius_tablet).";";    
            echo "}";

            echo "@media all and (max-width:576px){";
                echo esc_html($arf_form_cls_prefix)." .sltstandard_time .btn-group .arfbtn.dropdown-toggle{
                    -moz-border-radius:".esc_html($field_border_radius_mobile)." !important;
                    -webkit-border-radius:".esc_html($field_border_radius_mobile)." !important;
                    -o-border-radius:".esc_html($field_border_radius_mobile)." !important;
                    border-radius:".esc_html($field_border_radius_mobile).";
                }";
            echo "}";
        }


		// phpcs:disable
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
            font-family:' . esc_html( $arf_input_font_family ) . ';
            ' . esc_html( $arf_input_font_style_str ) . '
            width:100%;
            -moz-box-shadow:0px 0px 2px rgba(' . $arflitesettingcontroller->arflitehex2rgb( esc_html( $base_color ) ) . ', 0.4);
            -webkit-box-shadow:0px 0px 2px rgba(' . $arflitesettingcontroller->arflitehex2rgb( esc_html( $base_color ) ) . ', 0.4);
            -o-box-shadow:0px 0px 2px rgba(' . $arflitesettingcontroller->arflitehex2rgb( esc_html( $base_color ) ) . ', 0.4);
            box-shadow:0px 0px 2px rgba(' . $arflitesettingcontroller->arflitehex2rgb( esc_html( $base_color ) ) . ', 0.4);
            margin-top:0px;    
            min-height:' . ( ( esc_html( $arf_input_font_size ) ) + ( 2 * (int) esc_html( $field_border_width ) ) ) . 'px;
        }';// phpcs:enable

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

	}

	/** Form Fields Styling */

	/** Field Description Styling */
	echo esc_html( $arf_form_cls_prefix ) . ' .arfformfield .arf_field_description{';
		echo 'margin:2px 0px 0px 0px;padding:0;';
		echo 'font-family:' . esc_html( $arf_input_font_family ) . ';';
		echo 'font-size:' . esc_html( $description_font_size ) . 'px;';
		echo 'text-align:' . esc_html( $description_align ) . ';';
		echo 'color:' . esc_html( $field_label_txt_color ) . ';';
		echo 'max-width:100%;width:100%; line-height: 20px;';
	echo '}';
	/** Field Description Styling */

}
/** Field Level Styling */
$use_saved = isset( $use_saved ) ? $use_saved : '';
do_action( 'arflite_outsite_print_style', $new_values, $use_saved, $form_id );
