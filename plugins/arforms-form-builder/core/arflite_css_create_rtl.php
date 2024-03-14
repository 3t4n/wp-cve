<?php
if ( in_array( 'checkbox', $loaded_field ) ) {
	echo esc_html($arf_form_cls_prefix) ." .top_container .arf_checkbox_style:not(.arf_enable_checkbox_image)".esc_html($arf_checkbox_not_admin)."{";
		echo 'padding-left: unset;';
		echo 'padding-right:' . ( ( esc_html($arf_label_font_size) + 12 ) < 30 ? 30 : ( esc_html($arf_label_font_size) + 12 ) ) . 'px;';
	echo '}';

	echo esc_html($arf_form_cls_prefix)." .top_container .arf_checkbox_style:not(.arf_enable_checkbox_image)".esc_html($arf_checkbox_not_admin)." .arf_checkbox_input_wrapper{";
		echo 'margin-right:0;';
		echo 'margin-left:unset;';
	echo '}';

	echo esc_html($arf_form_cls_prefix) ." .top_container .arf_checkbox_style:not(.arf_enable_checkbox_image)".esc_html($arf_checkbox_not_admin)." .arf_checkbox_input_wrapper{";
		echo 'right:0;';
		echo 'left:unset;';
	echo '}';

	echo esc_html($arf_form_cls_prefix) . " .setting_checkbox.arf_standard_checkbox .arf_checkbox_input_wrapper input[type='checkbox'] + span{";
	echo 'margin-right: unset;';
	echo 'margin-left: 5px;';
	echo '}';

	echo esc_html($arf_form_cls_prefix) . ' .setting_checkbox.arf_standard_checkbox .arf_checkbox_input_wrapper{';
		echo 'margin-right: unset;';
		echo 'margin-left: 10px;';
	echo '}';

}


if ( in_array( 'radio', $loaded_field ) ) {

	echo esc_html($arf_form_cls_prefix) ." .top_container .arf_radiobutton:not(.arf_enable_radio_image_editor):not(.arf_enable_radio_image){";
		echo 'padding-left: unset;';
		echo 'padding-right:' . ( ( esc_html($arf_label_font_size) + 12 ) < 30 ? 30 : ( esc_html($arf_label_font_size) + 12 ) ) . 'px;';
	echo '}';

	echo esc_html($arf_form_cls_prefix) ." .top_container .arf_radiobutton:not(.arf_enable_radio_image_editor):not(.arf_enable_radio_image) .arf_radio_input_wrapper:not(.arf_matrix_radio_input_wrapper){";
		echo 'margin-right:0;';
		echo 'margin-left:unset;';
	echo '}';

	echo esc_html($arf_form_cls_prefix) ." .top_container .arf_radiobutton:not(.arf_enable_radio_image_editor):not(.arf_enable_radio_image) .arf_radio_input_wrapper:not(.arf_matrix_radio_input_wrapper){";
		echo 'right:0;';
		echo 'left:unset;';
	echo '}';

	 echo esc_html($arf_form_cls_prefix)." .setting_radio.arf_standard_radio .arf_radio_input_wrapper,";
	echo "body.arf_preview_rtl ".esc_html($arf_form_cls_prefix)." .setting_radio.arf_standard_radio .arf_radio_input_wrapper {";
		echo 'margin-left: 30px;';
		echo 'margin-right: 0px;';
	echo '}';

	echo esc_html($arf_form_cls_prefix)." .setting_radio.arf_custom_radio .arf_radio_input_wrapper,";
	echo "body.arf_preview_rtl ".esc_html($arf_form_cls_prefix)." .setting_radio.arf_custom_radio .arf_radio_input_wrapper {";
		echo 'margin-left: 10px;';
		echo 'margin-right: 0px;';
	echo '}';
}


if ( in_array( 'textarea', $loaded_field ) ) {
	echo esc_html($arf_form_cls_prefix) ." .allfields .arfcount_text_char_div{";
		echo 'text-align:left;';
	echo '}';
}


if ( $is_prefix_suffix_enable ) {

	echo esc_html($arf_form_cls_prefix)." .arfformfield ".esc_html($arf_prefix_cls)."{";
		echo 'border-top-left-radius:0;';
		echo 'border-bottom-left-radius:0;';
		echo "border-top-right-radius:".esc_html($field_border_radius).";";
		echo "border-bottom-right-radius:".esc_html($field_border_radius).";";
	echo '}';

	if( !empty( $field_border_radius_tablet )){
		
		echo ".arfdevicetablet" .esc_html($arf_form_cls_prefix)." .arfformfield ".esc_html($arf_prefix_cls)."{";
			echo 'border-top-left-radius:0;';
			echo 'border-bottom-left-radius:0;';
			echo "border-top-right-radius:".esc_html($field_border_radius_tablet).";";
			echo "border-bottom-right-radius:".esc_html($field_border_radius_tablet).";";
		echo '}';

		echo "@media all and (max-width:768px){";
			echo esc_html($arf_form_cls_prefix)." .arfformfield ".esc_html($arf_prefix_cls)."{";
				echo 'border-top-left-radius:0;';
				echo 'border-bottom-left-radius:0;';
				echo "border-top-right-radius:".esc_html($field_border_radius_tablet).";";
				echo "border-bottom-right-radius:".esc_html($field_border_radius_tablet).";";
			echo '}';
		echo "}";
	} 

	if( !empty( $field_border_radius_mobile )){

		echo ".arfdevicemobile" .esc_html($arf_form_cls_prefix)." .arfformfield ".esc_html($arf_prefix_cls)."{";
			echo 'border-top-left-radius:0;';
			echo 'border-bottom-left-radius:0;';
			echo "border-top-right-radius:".esc_html($field_border_radius_mobile).";";
			echo "border-bottom-right-radius:".esc_html($field_border_radius_mobile).";";
		echo '}';

		echo "@media all and (max-width:768px){";
			echo esc_html($arf_form_cls_prefix)." .arfformfield ".esc_html($arf_prefix_cls)."{";
				echo 'border-top-left-radius:0;';
				echo 'border-bottom-left-radius:0;';
				echo "border-top-right-radius:".esc_html($field_border_radius_mobile).";";
				echo "border-bottom-right-radius:".esc_html($field_border_radius_mobile).";";
			echo '}';
		echo "}";
	} 

	if ( preg_match( '/rounded/', $arf_mainstyle ) ) {
		echo esc_html($arf_form_cls_prefix)." .arf_rounded_form .arfformfield ".esc_html($arf_prefix_cls)."{";
			echo "border-right:".esc_html($field_border_width) . esc_html($field_border_style) . esc_html($field_border_color).";";
			echo 'border-left:none;';
		echo '}';

		echo esc_html($arf_form_cls_prefix)." .arf_rounded_form .arfformfield ".esc_html($arf_prefix_cls)." .arf_prefix_focus{";
			echo "border-right:".esc_html($field_border_width) . esc_html($field_border_style) . esc_html($base_color).";";
			echo 'border-left:none;';
		echo '}';

		echo esc_html($arf_form_cls_prefix)." .arf_rounded_form .arfformfield ".esc_html($arf_suffix_cls)."{";
			echo "border-left:".esc_html($field_border_width) . esc_html($field_border_style) . esc_html($field_border_color).";";
			echo 'border-right:none;';
		echo '}';

		echo esc_html($arf_form_cls_prefix)." .arf_rounded_form .arfformfield ".esc_html($arf_suffix_cls)." .arf_suffix_focus{";
			echo "border-left:".esc_html($field_border_width) . esc_html($field_border_style) . esc_html($base_color).";";
			echo 'border-right:none;';
		echo '}';

		echo ( ( in_array( 'phone', $loaded_field ) ) ? " ".esc_html($arf_form_cls_prefix)." .arf_rounded_form .arfformfield .controls ".esc_html($arf_prefix_suffix_wrapper_cls)." .arf_both_pre_suffix input.arf_phone_utils:not(.inplace_field):not(.arf_field_option_input_text)" : '' ) . '{';
				echo "border-right:".esc_html($field_border_width) . esc_html($field_border_style) . esc_html($field_border_color) ." !important;";
				echo "border-top-right-radius:".esc_html($field_border_radius)." !important;";
				echo 'border-top-left-radius: 0px !important;';
				echo "border-bottom-right-radius:".esc_html($field_border_radius)." !important;";
				echo 'border-bottom-left-radius:0px !important;';
				echo 'border-left:none !important;';
				echo 'margin: 0;';
			echo '}';
		
		if( !empty( $field_border_radius_tablet )){

			echo "@media all and (max-width:768px){";
				echo ( ( in_array( 'phone', $loaded_field ) ) ? " ".esc_html($arf_form_cls_prefix)." .arf_rounded_form .arfformfield .controls ".esc_html($arf_prefix_suffix_wrapper_cls)." .arf_both_pre_suffix input.arf_phone_utils:not(.inplace_field):not(.arf_field_option_input_text)" : '' ) . '{';
					echo "border-top-right-radius:".esc_html($field_border_radius_tablet)." !important;";
					echo "border-bottom-right-radius:".esc_html($field_border_radius_tablet)." !important;";
				echo "}";
			echo "}";
		}

		if( !empty( $field_border_radius_mobile )){

			echo "@media all and (max-width:768px){";
				echo ( ( in_array( 'phone', $loaded_field ) ) ? " ".esc_html($arf_form_cls_prefix)." .arf_rounded_form .arfformfield .controls ".esc_html($arf_prefix_suffix_wrapper_cls)." .arf_both_pre_suffix input.arf_phone_utils:not(.inplace_field):not(.arf_field_option_input_text)" : '' ) . '{';
					echo "border-top-right-radius:".esc_html($field_border_radius_mobile)." !important;";
					echo "border-bottom-right-radius:".esc_html($field_border_radius_mobile)." !important;";
				echo "}";
			echo "}";
		}

	}

	if ( preg_match( '/standard/', $arf_mainstyle ) ) {

			echo ( ( in_array( 'phone', $loaded_field ) ) ? "".esc_html($arf_form_cls_prefix)." .arf_standard_form .arfformfield .controls ".esc_html($arf_prefix_suffix_wrapper_cls).".arf_both_pre_suffix input.arf_phone_utils:not(.inplace_field):not(.arf_field_option_input_text)" : '' ) . '{';
				echo "border-right:".esc_html($field_border_width) . esc_html($field_border_style) . esc_html($field_border_color)."!important;";
				echo "border-top-right-radius:".esc_html($field_border_radius)." !important;";
				echo 'border-top-left-radius: 0px !important;';
				echo "border-bottom-right-radius:".esc_html($field_border_radius)." !important;";
				echo 'border-bottom-left-radius:0px !important;';
				echo 'border-left:none !important;';
				echo 'margin: 0;';
			echo '}';
				
			if( !empty( $field_border_radius_tablet )){
				echo ( ( in_array( 'phone', $loaded_field ) ) ? "".esc_html($arf_form_cls_prefix)." .arf_standard_form .arfformfield .controls ".esc_html($arf_prefix_suffix_wrapper_cls).".arf_both_pre_suffix input.arf_phone_utils:not(.inplace_field):not(.arf_field_option_input_text)" : '' ) . '{';
					echo "border-top-right-radius:".esc_html($field_border_radius_tablet)." !important;";
					echo "border-bottom-right-radius:".esc_html($field_border_radius_tablet)." !important;";
				echo "}";
			}

			if( !empty( $field_border_radius_mobile )){
				echo ( ( in_array( 'phone', $loaded_field ) ) ? "".esc_html($arf_form_cls_prefix)." .arf_standard_form .arfformfield .controls ".esc_html($arf_prefix_suffix_wrapper_cls).".arf_both_pre_suffix input.arf_phone_utils:not(.inplace_field):not(.arf_field_option_input_text)" : '' ) . '{';
					echo "border-top-right-radius:".esc_html($field_border_radius_mobile)." !important;";
					echo "border-bottom-right-radius:".esc_html($field_border_radius_mobile)." !important;";
				echo "}";
			}

	}

	echo esc_html($arf_form_cls_prefix)." .arfformfield ".esc_html($arf_suffix_cls)."{";
		echo 'border-top-right-radius:0;';
		echo 'border-bottom-right-radius:0;';
		echo "border-top-left-radius:".esc_html($field_border_radius).";";
		echo "border-bottom-left-radius:".esc_html($field_border_radius).";";
	echo '}';

	if( !empty( $field_border_radius_tablet )){

		echo ".arfdevicetablet" .esc_html($arf_form_cls_prefix)." .arfformfield ".esc_html($arf_suffix_cls)."{";
			echo 'border-top-right-radius:0;';
			echo 'border-bottom-right-radius:0;';
			echo "border-top-left-radius:".esc_html($field_border_radius_tablet).";";
			echo "border-bottom-left-radius:".esc_html($field_border_radius_tablet).";";
		echo '}';

		echo "@media all and (max-width:768px){";
			echo esc_html($arf_form_cls_prefix)." .arfformfield ".esc_html($arf_suffix_cls)."{";
				echo 'border-top-right-radius:0;';
				echo 'border-bottom-right-radius:0;';
				echo "border-top-left-radius:".esc_html($field_border_radius_tablet).";";
				echo "border-bottom-left-radius:".esc_html($field_border_radius_tablet).";";
			echo '}';
		echo "}";
	}

	if( !empty( $field_border_radius_mobile )){

		echo ".arfdevicemobile" .esc_html($arf_form_cls_prefix)." .arfformfield ".esc_html($arf_suffix_cls)."{";
			echo 'border-top-right-radius:0;';
			echo 'border-bottom-right-radius:0;';
			echo "border-top-left-radius:".esc_html($field_border_radius_mobile).";";
			echo "border-bottom-left-radius:".esc_html($field_border_radius_mobile).";";
		echo '}';

		echo "@media all and (max-width:576px){";
			echo esc_html($arf_form_cls_prefix)." .arfformfield ".esc_html($arf_suffix_cls)."{";
				echo 'border-top-right-radius:0;';
				echo 'border-bottom-right-radius:0;';
				echo "border-top-left-radius:".esc_html($field_border_radius_mobile).";";
				echo "border-bottom-left-radius:".esc_html($field_border_radius_mobile).";";
			echo '}';
		echo "}";
	}

	echo esc_html($arf_form_cls_prefix) ." .arfformfield .controls ".esc_html($arf_prefix_suffix_wrapper_cls).".arf_prefix_only:not(.arf_phone_with_flag) input:not(.inplace_field):not(.arf_field_option_input_text)" . ( ( in_array( 'phone', $loaded_field ) ) ? ",".esc_html($arf_form_cls_prefix)." .arfformfield .controls ".esc_html($arf_prefix_suffix_wrapper_cls).".arf_prefix_only input[type=tel]:not(.inplace_field):not(.arf_field_option_input_text)" : '' ) . '{';
		echo 'border-right:none !important;';
		echo 'border-top-right-radius:0px !important;';
		echo 'border-bottom-right-radius:0px !important;';
		echo 'border-left:unset !important;';
		echo 'border-top-left-radius:unset !important;';
		echo 'border-bottom-left-radius:unset !important;';
	echo '}';

	echo esc_html($arf_form_cls_prefix) . " .arfformfield .controls ".esc_html($arf_prefix_suffix_wrapper_cls).".arf_suffix_only input:not(.inplace_field):not(.arf_field_option_input_text)" . ( ( in_array( 'phone', $loaded_field ) ) ? ",".esc_html($arf_form_cls_prefix).".arfformfield .controls ".esc_html($arf_prefix_suffix_wrapper_cls).".arf_suffix_only input[type=tel]:not(.inplace_field):not(.arf_field_option_input_text)" : '' ) . '{';
		echo 'border-left:none !important;';
		echo 'border-top-left-radius:0px !important;';
		echo 'border-bottom-left-radius:0px !important;';
		echo 'border-right:unset !important;';
		echo 'border-top-right-radius:unset !important;';
		echo 'border-bottom-right-radius:unset !important;';
	echo '}';

	if ( preg_match( '/material/', $arf_mainstyle ) ) {
		echo esc_html($arf_form_cls_prefix) . ' .arfformfield .controls .arf_material_theme_container_with_icons.arf_only_leading_icon input:not(.inplace_field):not(.arf_field_option_input_text)' . ( ( in_array( 'phone', $loaded_field ) ) ? ",".esc_html($arf_form_cls_prefix)." .arfformfield .controls .arf_material_theme_container_with_icons.arf_only_leading_icon input[type=tel]:not(.inplace_field):not(.arf_field_option_input_text)" : '' ) . '{';
			echo "padding-right:".esc_html($arf_paddingleft_field)." !important;";
			echo "padding-left:".esc_html($fieldpadding_2)."px !important;";
		echo '}';

		echo esc_html($arf_form_cls_prefix) . ' .arfformfield .controls .arf_material_theme_container_with_icons.arf_only_leading_icon input:not(.inplace_field):not(.arf_field_option_input_text) + .arf_material_standard label.arf_main_label' . ( ( in_array( 'phone', $loaded_field ) ) ? ",".esc_html($arf_form_cls_prefix)." .arfformfield .controls .arf_material_theme_container_with_icons.arf_only_leading_icon input[type=tel]:not(.inplace_field):not(.arf_field_option_input_text):not(.arf_autocomplete) + .arf_material_standard label.arf_main_label" : '' ) . '{';
			echo "padding-right:".esc_html($arf_paddingleft_field)." !important;";
			echo "padding-left:".esc_html($fieldpadding_2)."px !important;";
		echo '}';

		echo esc_html($arf_form_cls_prefix) . ' .arfformfield .controls .arf_material_theme_container_with_icons.arf_only_trailing_icon input:not(.inplace_field):not(.arf_field_option_input_text)' . ( ( in_array( 'phone', $loaded_field ) ) ? ",".esc_html($arf_form_cls_prefix)." .arfformfield .controls .arf_material_theme_container_with_icons.arf_only_trailing_icon input[type=tel]:not(.inplace_field):not(.arf_field_option_input_text)" : '' ) . '{';
			echo "padding-left:".esc_html($arf_paddingleft_field)." !important;";
			echo "padding-right:".esc_html($fieldpadding_2)."px !important;";
		echo '}';

		echo esc_html($arf_form_cls_prefix) . ' .arfformfield .controls .arf_material_theme_container_with_icons.arf_only_trailing_icon input:not(.inplace_field):not(.arf_field_option_input_text) + .arf_material_standard label.arf_main_label' . ( ( in_array( 'phone', $loaded_field ) ) ? ",".esc_html($arf_form_cls_prefix)." .arfformfield .controls .arf_material_theme_container_with_icons.arf_only_trailing_icon input[type=tel]:not(.inplace_field):not(.arf_field_option_input_text) + .arf_material_standard label.arf_main_label" : '' ) . '{';
			echo "padding-left:".esc_html($arf_paddingleft_field)." !important;";
			echo "padding-right:".esc_html($fieldpadding_2)."px !important;";
		echo '}';
	}
}
if ( array_intersect( $loaded_field, $common_field_type_styling ) ) {
	 echo esc_html($arf_form_cls_prefix) . ' label.arf_main_label:not(.arf_field_option_content_cell_label){';
		echo 'left:inherit;';
		echo 'right:0px;';
	echo '}';

	echo esc_html($arf_form_cls_prefix) . " .arfformfield .controls ".esc_html($arf_prefix_suffix_wrapper_cls).".arf_prefix_only:not(.arf_phone_with_flag) input:not(.inplace_field):not(.arf_field_option_input_text)" . ( ( in_array( 'phone', $loaded_field ) ) ? ",".esc_html($arf_form_cls_prefix)." .arfformfield .controls ".esc_html($arf_prefix_suffix_wrapper_cls).".arf_prefix_only input[type=tel]:not(.inplace_field):not(.arf_field_option_input_text)" : '' ) . '{';
		echo 'border-right:none !important;';
		echo 'border-top-right-radius:0px !important;';
		echo 'border-bottom-right-radius:0px !important;';
		echo "border-left:".esc_html($field_border_width) . esc_html($field_border_style) . esc_html($field_border_color) ." !important;";
		echo "border-top-left-radius:".esc_html($field_border_radius)." !important;";
		echo "border-bottom-left-radius:".esc_html($field_border_radius)." !important;";
		echo 'width:100%;';
	echo '}';

	if( !empty( $field_border_radius_tablet )){
		
		echo "arfdevicetablet " .esc_html($arf_form_cls_prefix) . " .arfformfield .controls ".esc_html($arf_prefix_suffix_wrapper_cls).".arf_prefix_only:not(.arf_phone_with_flag) input:not(.inplace_field):not(.arf_field_option_input_text)" . ( ( in_array( 'phone', $loaded_field ) ) ? ",".esc_html($arf_form_cls_prefix)." .arfformfield .controls ".esc_html($arf_prefix_suffix_wrapper_cls).".arf_prefix_only input[type=tel]:not(.inplace_field):not(.arf_field_option_input_text)" : '' ) . '{';

			echo "border-top-left-radius:".esc_html($field_border_radius_tablet)." !important;";
			echo "border-bottom-left-radius:".esc_html($field_border_radius_tablet)." !important;";

		echo "}";

		echo esc_html($arf_form_cls_prefix) . " .arfformfield .controls ".esc_html($arf_prefix_suffix_wrapper_cls).".arf_prefix_only:not(.arf_phone_with_flag) input:not(.inplace_field):not(.arf_field_option_input_text)" . ( ( in_array( 'phone', $loaded_field ) ) ? ",".esc_html($arf_form_cls_prefix)." .arfformfield .controls ".esc_html($arf_prefix_suffix_wrapper_cls).".arf_prefix_only input[type=tel]:not(.inplace_field):not(.arf_field_option_input_text)" : '' ) . '{';

			echo "border-top-left-radius:".esc_html($field_border_radius_tablet)." !important;";
			echo "border-bottom-left-radius:".esc_html($field_border_radius_tablet)." !important;";
			
		echo "}";
	}

	if( !empty( $field_border_radius_mobile )){
		
		echo "arfdevicemobile " .esc_html($arf_form_cls_prefix) . " .arfformfield .controls ".esc_html($arf_prefix_suffix_wrapper_cls).".arf_prefix_only:not(.arf_phone_with_flag) input:not(.inplace_field):not(.arf_field_option_input_text)" . ( ( in_array( 'phone', $loaded_field ) ) ? ",".esc_html($arf_form_cls_prefix)." .arfformfield .controls ".esc_html($arf_prefix_suffix_wrapper_cls).".arf_prefix_only input[type=tel]:not(.inplace_field):not(.arf_field_option_input_text)" : '' ) . '{';

			echo "border-top-left-radius:".esc_html($field_border_radius_mobile)." !important;";
			echo "border-bottom-left-radius:".esc_html($field_border_radius_mobile)." !important;";

		echo "}";

		echo esc_html($arf_form_cls_prefix) . " .arfformfield .controls ".esc_html($arf_prefix_suffix_wrapper_cls).".arf_prefix_only:not(.arf_phone_with_flag) input:not(.inplace_field):not(.arf_field_option_input_text)" . ( ( in_array( 'phone', $loaded_field ) ) ? ",".esc_html($arf_form_cls_prefix)." .arfformfield .controls ".esc_html($arf_prefix_suffix_wrapper_cls).".arf_prefix_only input[type=tel]:not(.inplace_field):not(.arf_field_option_input_text)" : '' ) . '{';

			echo "border-top-left-radius:".esc_html($field_border_radius_mobile)." !important;";
			echo "border-bottom-left-radius:".esc_html($field_border_radius_mobile)." !important;";
			
		echo "}";
	}

	echo esc_html($arf_form_cls_prefix) . " .arfformfield .controls ".esc_html($arf_prefix_suffix_wrapper_cls).".arf_suffix_only input:not(.inplace_field):not(.arf_field_option_input_text)" . ( ( in_array( 'phone', $loaded_field ) ) ? ",".esc_html($arf_form_cls_prefix)." .arfformfield .controls ".esc_html($arf_prefix_suffix_wrapper_cls).".arf_suffix_only input[type=tel]:not(.inplace_field):not(.arf_field_option_input_text)" : '' ) . '{';
		echo 'border-left:none !important;';
		echo 'border-top-left-radius:0px !important;';
		echo 'border-bottom-left-radius:0px !important;';
		echo "border-right:".esc_html($field_border_width) . esc_html($field_border_style) . esc_html($field_border_color)." !important;";
		echo "border-top-right-radius:".esc_html($field_border_radius)." !important;";
		echo "border-bottom-right-radius:".esc_html($field_border_radius)." !important;";
		echo 'width:100%;';
	echo '}';

	if( !empty( $field_border_radius_tablet )){

		echo 'arfdevicetablet ' .esc_html($arf_form_cls_prefix) . " .arfformfield .controls ".esc_html($arf_prefix_suffix_wrapper_cls).".arf_suffix_only input:not(.inplace_field):not(.arf_field_option_input_text)" . ( ( in_array( 'phone', $loaded_field ) ) ? ",".esc_html($arf_form_cls_prefix)." .arfformfield .controls ".esc_html($arf_prefix_suffix_wrapper_cls).".arf_suffix_only input[type=tel]:not(.inplace_field):not(.arf_field_option_input_text)" : '' ) . '{';

			echo "border-top-right-radius:".esc_html($field_border_radius_tablet)." !important;";
			echo "border-bottom-right-radius:".esc_html($field_border_radius_tablet)." !important;";
		echo "}";

		echo esc_html($arf_form_cls_prefix) . " .arfformfield .controls ".esc_html($arf_prefix_suffix_wrapper_cls).".arf_suffix_only input:not(.inplace_field):not(.arf_field_option_input_text)" . ( ( in_array( 'phone', $loaded_field ) ) ? ",".esc_html($arf_form_cls_prefix)." .arfformfield .controls ".esc_html($arf_prefix_suffix_wrapper_cls).".arf_suffix_only input[type=tel]:not(.inplace_field):not(.arf_field_option_input_text)" : '' ) . '{';

			echo "border-top-right-radius:".esc_html($field_border_radius_tablet)." !important;";
			echo "border-bottom-right-radius:".esc_html($field_border_radius_tablet)." !important;";
		echo "}";
	}

	if( !empty( $field_border_radius_mobile)){

		echo 'arfdevicemobile ' .esc_html($arf_form_cls_prefix) . " .arfformfield .controls ".esc_html($arf_prefix_suffix_wrapper_cls).".arf_suffix_only input:not(.inplace_field):not(.arf_field_option_input_text)" . ( ( in_array( 'phone', $loaded_field ) ) ? ",".esc_html($arf_form_cls_prefix)." .arfformfield .controls ".esc_html($arf_prefix_suffix_wrapper_cls).".arf_suffix_only input[type=tel]:not(.inplace_field):not(.arf_field_option_input_text)" : '' ) . '{';

			echo "border-top-right-radius:".esc_html($field_border_radius_tablet)." !important;";
			echo "border-bottom-right-radius:".esc_html($field_border_radius_tablet)." !important;";
		echo "}";

		echo esc_html($arf_form_cls_prefix) . " .arfformfield .controls ".esc_html($arf_prefix_suffix_wrapper_cls).".arf_suffix_only input:not(.inplace_field):not(.arf_field_option_input_text)" . ( ( in_array( 'phone', $loaded_field ) ) ? ",".esc_html($arf_form_cls_prefix)." .arfformfield .controls ".esc_html($arf_prefix_suffix_wrapper_cls).".arf_suffix_only input[type=tel]:not(.inplace_field):not(.arf_field_option_input_text)" : '' ) . '{';

			echo "border-top-right-radius:".esc_html($field_border_radius_mobile)." !important;";
			echo "border-bottom-right-radius:".esc_html($field_border_radius_mobile)." !important;";
		echo "}";
	}

	echo esc_html($arf_form_cls_prefix) . ' .arfformfield .arf_leading_icon{';
		echo 'right:15px;';
		echo 'left: unset;';
	echo '}';

	echo esc_html($arf_form_cls_prefix) . ' .arfformfield .arf_trailing_icon{';
		echo 'left:15px;';
		echo 'right: unset;';
	echo '}';
}

if ( in_array( 'phone', $loaded_field ) ) {
	echo esc_html($arf_form_cls_prefix) . ' .arf_field_type_phone ul#country-listbox {';
		echo 'text-align: right;';
	echo '}';

	echo esc_html($arf_form_cls_prefix) . ' .arf_field_type_phone .controls .arf_phone_utils{';
		echo 'padding-left: unset !important;';
		echo 'padding-right: 52px !important;';
	echo '}';

	echo esc_html($arf_form_cls_prefix) . ' .arfformfield .controls input.arf_phone_utils[type=text]:not(.inplace_field):not(.arf_field_option_input_text){';
		echo 'padding-right: 52px !important';
	echo '}';
}

if ( $is_form_save ) {

	if ( in_array( 'phone', $loaded_field ) ) {
		echo esc_html($arf_form_cls_prefix) . ' .edit_field_type_phone .arfformfield .controls ul#country-listbox{';
			echo 'text-align: right;';
		echo '}';

		echo esc_html($arf_form_cls_prefix) . ' .edit_field_type_phone .arfformfield .controls .arf_phone_utils{';
			echo 'padding-left: unset !important;';
			echo 'padding-right: 52px !important;';
		echo '}';
	}
}
