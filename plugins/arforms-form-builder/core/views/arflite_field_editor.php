<?php

global $arflite_memory_limit, $arflitememorylimit, $arflitefieldcontroller, $arflitefield;


if ( isset( $arflite_memory_limit ) && isset( $arflitememorylimit ) && ( $arflite_memory_limit * 1024 * 1024 ) > $arflitememorylimit ) {
	@ini_set( 'memory_limit', $arflite_memory_limit . 'M' );
}

global $arflite_style_settings, $arflitemainhelper, $arflitefieldhelper, $arfliteformcontroller, $arflite_font_awesome_loaded, $ARFLiteMdlDb, $arflitemaincontroller;

$multicol_html                = '';
$arf_disply_multicolumn_field = true;

if ( is_array( $field ) ) {
	$arf_disply_multicolumn_field = apply_filters( 'arflite_disply_multicolumn_fieldolumn_field_outside', $arf_disply_multicolumn_field, $field );
}
if ( $arf_disply_multicolumn_field && ( isset( $field['type'] ) && $field['type'] != 'hidden' ) || ! is_array( $field ) ) {
	$multicol_html                   = '<div class="arf_multiiconbox">
        <div class="arf_field_option_multicolumn" id="arf_multicolumn_wrapper">
            <input type="hidden" name="multicolumn" />
            ' . $arf_multicolumn_one = $arflitefieldcontroller->arflite_get_field_multicolumn_icon( 1, $index_arf_fields ) . '
            ' . $arf_multicolumn_one = $arflitefieldcontroller->arflite_get_field_multicolumn_icon( 2, $index_arf_fields ) . '
            ' . $arf_multicolumn_one = $arflitefieldcontroller->arflite_get_field_multicolumn_icon( 3, $index_arf_fields ) . '
            ' . $arf_multicolumn_one = $arflitefieldcontroller->arflite_get_field_multicolumn_icon( 4, $index_arf_fields ) . '
            ' . $arf_multicolumn_one = $arflitefieldcontroller->arflite_get_field_multicolumn_icon( 5, $index_arf_fields ) . '
            ' . $arf_multicolumn_one = $arflitefieldcontroller->arflite_get_field_multicolumn_icon( 6, $index_arf_fields ) . '
        </div>
        ' . $arflitefieldcontroller->arflite_get_multicolumn_expand_icon() . '
    </div>';
}
$multicolclass         = 'single_column_wrapper';
$define_classes        = '';
$confirm_field_options = '';
$arf_main_label_cls    = '';
if ( $frm_css['arfinputstyle'] == 'material' ) {
	$arf_main_label_cls = $arfliteformcontroller->arflite_label_top_position( $frm_css['font_size'], $frm_css['field_font_size'] );
}



if ( is_array( $field ) ) {
	$define_classes = isset( $field['classes'] ) ? $field['classes'] : 'arf_1';
} else {
	if ( strpos( $field, '_confirm' ) !== false ) {
		$field_ext_extract      = explode( '_', $field );
		$field_id_values        = $arflitefield->arflitegetOne( $field_ext_extract[0] );
		$unsaved_fields_confirm = ( isset( $_REQUEST['extra_fields'] ) && $_REQUEST['extra_fields'] != '' ) ? json_decode( stripslashes_deep( sanitize_text_field( $_REQUEST['extra_fields'] ) ), true ) : array();

		if ( ( isset( $inside_repeatable_field ) && $inside_repeatable_field ) || ( isset( $inside_section_field ) && $inside_section_field ) ) {
			$unsaved_fields_confirm = ( isset( $_REQUEST['inner_extra_fields'] ) && $_REQUEST['inner_extra_fields'] != '' ) ? json_decode( stripslashes_deep( sanitize_text_field( $_REQUEST['inner_extra_fields'] ) ), true ) : array();
		}

		if ( is_array( $unsaved_fields_confirm ) && count( $unsaved_fields_confirm ) > 0 && isset( $unsaved_fields_confirm[ $field_ext_extract[0] ] ) ) {
			$confirm_field_options1 = $unsaved_fields_confirm[ $field_ext_extract[0] ];
			$confirm_field_options  = json_decode( $confirm_field_options1, true );
		} elseif ( $field_id_values != '' ) {
			$confirm_field_options = $field_id_values->field_options;
		} else {
			$key_val = '';
			foreach ( $arf_fields as $key => $val ) {
				if ( $val['id'] == $field_ext_extract[0] ) {
					$key_val = $key;
				}
			}
			$confirm_field_options = array();
			if ( $key_val != '' ) {
				$confirm_field_options = $arf_fields[ $key_val ];
			}
		}

		$is_confirm_field = false;

		if ( $confirm_field_options['type'] == 'email' ) {
			$define_classes   = isset( $confirm_field_options['confirm_email_inner_classes'] ) ? $confirm_field_options['confirm_email_inner_classes'] : 'arf_1';
			$is_confirm_field = true;
		}
		if ( $confirm_field_options['type'] == 'password' ) {
			$define_classes   = isset( $confirm_field_options['confirm_password_inner_classes'] ) ? $confirm_field_options['confirm_password_inner_classes'] : 'arf_1';
			$is_confirm_field = true;
		}

		$last_col_array_keys = array( 'arf_1col', 'arf_2col', 'arf_3col', 'arf_4col', 'arf_5col', 'arf_6col' );

		if ( ! $is_confirm_field && gettype( $field_key ) != 'string' && isset( $field_classes[ $field_key - 1 ] ) && in_array( $field_classes[ $field_key - 1 ], $last_col_array_keys ) && ! in_array( $field_classes[ $field_key ], $last_col_array_keys ) ) {
			$define_classes = 'arf_1';
		}
	} else {
		$field_ext_extract = explode( '|', $field );
		$define_classes    = $field_ext_extract[0];
	}
}

switch ( $define_classes ) {
	case 'arf_1':
		$multicolclass = 'single_column_wrapper';
		break;
	case 'arf_2':
		$multicolclass = 'two_column_wrapper';
		break;
	case 'arf_3':
		$multicolclass = 'three_column_wrapper';
		break;
	case 'arf_4':
		$multicolclass = 'four_column_wrapper';
		break;
	case 'arf_5':
		$multicolclass = 'five_column_wrapper';
		break;
	case 'arf_6':
		$multicolclass = 'six_column_wrapper';
		break;
	case 'arf_1col':
		$multicolclass = 'single_column_wrapper';
		break;
	case 'arf21colclass':
		$multicolclass = 'two_column_wrapper';
		break;
	case 'arf31colclass':
		$multicolclass = 'three_column_wrapper';
		break;
	case 'arf41colclass':
		$multicolclass = 'four_column_wrapper';
		break;
	case 'arf51colclass':
		$multicolclass = 'five_column_wrapper';
		break;
	case 'arf61colclass':
		$multicolclass = 'six_column_wrapper';
		break;
}

$sortable_inner_field_style = '';

if ( isset( $field_resize_width[ $arf_field_counter ] ) ) {
	$sortable_inner_field_style = "style='width:{$field_resize_width[ $arf_field_counter ]}%' data-width='" . esc_attr( $field_resize_width[ $arf_field_counter ] ) . "'";
}

if ( is_array( $field ) ) {

	$display = apply_filters(
		'arflitedisplayfieldoptions',
		array(
			'type'           => $field['type'],
			'field_data'     => $field,
			'required'       => true,
			'description'    => true,
			'options'        => true,
			'label_position' => true,
			'invalid'        => false,
			'size'           => false,
			'clear_on_focus' => false,
			'default_blank'  => true,
			'css'            => true,
		)
	);

	$fields_for_edit_options = apply_filters( 'arflite_field_values_options_outside', array( 'checkbox', 'radio', 'select' ) );

	$arf_form_css = '';
	$arf_form_css = $data['form_css'];

	$arr    = maybe_unserialize( $arf_form_css );
	$newarr = array();
	if ( isset( $arr ) && is_array( $arr ) && ! empty( $arr ) ) {
		foreach ( $arr as $k => $v ) {
			$newarr[ $k ] = $v;
		}
	}

	if ( isset( $_REQUEST['arf_rtl_switch_mode'] ) && sanitize_text_field( $_REQUEST['arf_rtl_switch_mode'] ) == 'yes' ) {
		$newarr['arfformtitlealign']     = 'right';
		$newarr['form_align']            = 'right';
		$newarr['arfdescalighsetting']   = 'right';
		$newarr['align']                 = 'right';
		$newarr['text_direction']        = '0';
		$newarr['arfsubmitalignsetting'] = 'right';
	}

	$newarr['arfinputstyle'] = $frm_css['arfinputstyle'] = ( isset( $_GET['templete_style'] ) && $_GET['templete_style'] != '' ) ? sanitize_text_field( $_GET['templete_style'] ) : ( ( isset( $newarr['arfinputstyle'] ) && $newarr['arfinputstyle'] != '' ) ? $newarr['arfinputstyle'] : 'material' );
	if ( isset( $_REQUEST['arfaction'] ) && ( sanitize_text_field( $_REQUEST['arfaction'] ) == 'duplicate' || sanitize_text_field( $_REQUEST['arfaction'] ) == 'new' ) ) {
		if ( $newarr['arfinputstyle'] != 'material' ) {
			if ( $newarr['arfinputstyle'] == 'rounded' ) {
				$newarr['border_radius'] = 50;
			} else {
				$newarr['border_radius'] = 4;
			}
			$newarr['arffieldinnermarginssetting_1'] = 7;
			$newarr['arffieldinnermarginssetting_2'] = 10;
			$newarr['arfcheckradiostyle']            = 'default';
			$newarr['arfsubmitborderwidthsetting']   = '0';
			$newarr['arfsubmitbuttonxoffsetsetting'] = 1;
			$newarr['arfsubmitbuttonyoffsetsetting'] = 2;
			$newarr['arfsubmitbuttonblursetting']    = 3;
			$newarr['arfsubmitbuttonshadowsetting']  = 0;
			$newarr['arfsubmitbuttonstyle']          = 'flat';
			$newarr['arfmainfield_opacity']          = 0;
			$newarr['arffieldinnermarginssetting']   = '7px 10px 7px 10px';

		} elseif ( $newarr['arfinputstyle'] == 'material' ) {
			$newarr['arffieldinnermarginssetting_1'] = 0;
			$newarr['arffieldinnermarginssetting_2'] = 0;
			$newarr['border_radius']                 = 0;
			$newarr['arfcheckradiostyle']            = 'material';
			$newarr['arfsubmitborderwidthsetting']   = '2';
			$newarr['arfsubmitbuttonstyle']          = 'border';
			$newarr['arfmainfield_opacity']          = 1;
			$newarr['arffieldinnermarginssetting']   = '0px 0px 0px 0px';
		}
	}
	$myliclass = '';
	if ( isset( $field['classes'] ) && $field['classes'] == 'arf_2' ) {
		$myliclass = 'width:45.5%;float:left;clear:none;height:130px;';
	} elseif ( isset( $field['classes'] ) && $field['classes'] == 'arf_3' ) {
		$myliclass = 'width:29%;float:left;clear:none;height:130px;';
	} else {
		$myliclass = 'float:none;clear:both;height:auto;';
	}
	global $arflite_column_classes;

	if ( $field['type'] == 'captcha' ) {
		if ( isset( $field['is_recaptcha'] ) && $field['is_recaptcha'] == 'custom-captcha' ) {
			$multicolclass .= ' arf-custom-captcha';
		} else {
			$multicolclass .= ' arf-recaptcha';
		}
	}

	if ( isset( $field['options'] ) && is_array( $field['options'] ) && ( $field['type'] == 'radio' || $field['type'] == 'checkbox' || $field['type'] == 'select' ) ) {
		$field['options'] = $arflitefieldhelper->arflitechangeoptionorder( $field );
	}


	$prefix_suffix_bg_color   = ( isset( $newarr['prefix_suffix_bg_color'] ) && $newarr['prefix_suffix_bg_color'] != '' ) ? $newarr['prefix_suffix_bg_color'] : '#e7e8ec';
	$prefix_suffix_icon_color = ( isset( $newarr['prefix_suffix_icon_color'] ) && $newarr['prefix_suffix_icon_color'] != '' ) ? $newarr['prefix_suffix_icon_color'] : '#808080';

	$prefix_suffix_wrapper_start = '';
	$prefix_suffix_wrapper_end   = '';
	$has_prefix_suffix           = false;
	$prefix_suffix_class         = '';
	$has_prefix                  = false;
	$has_suffix                  = false;
	$arf_prefix_icon             = '';
	$arf_suffix_icon             = '';
	$prefix_suffix_style_start   = "<style id='arf_field_prefix_suffix_style_'" . esc_attr( $field['id'] ) . "' type='text/css'>";
	$prefix_suffix_style         = '';
	$prefix_suffix_style_end     = '</style>';

	$arf_is_phone_with_flag = false;
	$default_country_code   = '';
	$default_country        = ( isset( $field['type'] ) == 'phone' && isset( $field['default_country'] ) && $field['default_country'] != '' ) ? $field['default_country'] : '';
	if ( $field['type'] == 'phone' && isset( $field['phonetype'] ) && $field['phonetype'] == 1 ) {
		$arf_is_phone_with_flag = true;


		$phtypes = array();
		foreach ( $field['phtypes'] as $key => $vphtype ) {
			if ( $vphtype != 0 ) {
				array_push( $phtypes, strtolower( str_replace( 'phtypes_', '', $key ) ) );
			}
		}

		$default_country_code = ' data-defaultCountryCode="' . esc_attr( $phtypes[0] ) . '" ';
	}

	if ( isset( $field['enable_arf_prefix'] ) && $field['enable_arf_prefix'] == 1 && $arf_is_phone_with_flag == false ) {
		$has_prefix_suffix = true;
		$has_prefix        = true;
		$arf_prefix_icon   = "<span class='arf_editor_prefix_icon'><i class='" . esc_attr( $field['arf_prefix_icon'] ) . "'></i></span>";
	}
	if ( isset( $field['enable_arf_suffix'] ) && $field['enable_arf_suffix'] == 1 ) {
		$has_prefix_suffix = true;
		$has_suffix        = true;
		$arf_suffix_icon   = "<span class='arf_editor_suffix_icon'><i class='" . esc_attr( $field['arf_suffix_icon'] ) . "'></i></span>";
	}

	if ( $has_prefix == true && $has_suffix == false ) {
		$prefix_suffix_class = ' arf_prefix_only ';
	} elseif ( $has_prefix == false && $has_suffix == true ) {
		$prefix_suffix_class = ' arf_suffix_only ';
	} elseif ( $has_prefix == true && $has_suffix == true ) {
		$prefix_suffix_class = ' arf_both_pre_suffix ';
	}


	if ( isset( $has_prefix_suffix ) && $has_prefix_suffix == true ) {
		$prefix_suffix_wrapper_start = "<div id='arf_editor_prefix_suffix_container_" . esc_attr( $field['id'] ) . "' class='arf_editor_prefix_suffix_wrapper " . trim( esc_attr( $prefix_suffix_class ) ) . "'>";
		$prefix_suffix_wrapper_end   = '</div>';
	}

	if ( $frm_css['arfinputstyle'] == 'material' ) {
		$prefix_suffix_wrapper_start = $prefix_suffix_wrapper_end = '';
	}

	if ( $index_arf_fields != 0 ) {
		$last_index = $index_arf_fields - 1;
		if ( $index_arf_fields > 2 ) {
			$seconud_last_index = $index_arf_fields - 2;
		} else {
			$seconud_last_index = $index_arf_fields;
		}
		if ( $index_arf_fields > 3 ) {
			$third_last_index = $index_arf_fields - 3;
		} else {
			$third_last_index = $index_arf_fields;
		}
		if ( $index_arf_fields > 4 ) {
			$fourth_last_index = $index_arf_fields - 4;
		} else {
			$fourth_last_index = $index_arf_fields;
		}
		if ( $index_arf_fields > 5 ) {
			$fifth_last_index = $index_arf_fields - 5;
		} else {
			$fifth_last_index = $index_arf_fields;
		}
	} else {
		$last_index         = 0;
		$seconud_last_index = 0;
		$third_last_index   = 0;
		$fourth_last_index  = 0;
		$fifth_last_index   = 0;
	}

	$arf_input_style_label_position = array( 'checkbox', 'radio', 'html', 'arfslider', 'slider', 'hidden', 'captcha' );
	$arf_input_style_label_position = apply_filters( 'arflite_input_style_label_position_outside', $arf_input_style_label_position, $frm_css['arfinputstyle'], $field['type'] );

	if ( $class == 'arf_1col' || $class == 'arf21colclass' || $class == 'arf31colclass' || $class == 'arf41colclass' || $class == 'arf51colclass' || $class == 'arf61colclass' ) {
		?>
		<div class="arf_inner_wrapper_sortable arfmainformfield edit_form_item arffieldbox ui-state-default arf1columns <?php echo esc_attr( $display['options'] ); ?>  <?php echo esc_attr( $multicolclass ); ?>" data-id="arf_editor_main_row_<?php echo esc_attr( $index_arf_fields ); ?>">
			<?php
				echo wp_kses(
					$multicol_html,
					array(
						'div'    => array(
							'class'      => array(),
							'id'         => array(),
							'data-value' => array(),
						),
						'input'  => array(
							'type'    => array(),
							'class'   => array(),
							'id'      => array(),
							'onclick' => array(),
							'data-id' => array(),
							'checked' => array(),
							'value'   => array(),
							'name'    => array(),
						),
						'label'  => array(
							'for' => array(),
						),
						'span'   => array(
							'class' => array(),
						),
						'svg'    => array(
							'viewbox'     => array(),
							'xmlns'       => array(),
							'width'       => array(),
							'height'      => array(),
							'fill'        => array(),
							'version'     => array(),
							'xmlns:xlink' => array(),
							'id'          => array(),
							'x'           => array(),
							'y'           => array(),
							'style'       => array(),
							'xml:space'   => array(),
						),
						'path'   => array(
							'd'            => array(),
							'fill'         => array(),
							'fill-rule'    => array(),
							'clip-rule'    => array(),
							'xmlns'        => array(),
							'stroke'       => array(),
							'stroke-width' => array(),
						),
						'g'      => array(
							'id'   => array(),
							'fill' => array(),
						),
						'rect'   => array(
							'x'            => array(),
							'y'            => array(),
							'width'        => array(),
							'height'       => array(),
							'rx'           => array(),
							'stroke'       => array(),
							'stroke-width' => array(),
							'fill'         => array(),
							'transform'    => array(),
						),
						'circle' => array(
							'cx'           => array(),
							'cy'           => array(),
							'fill'         => array(),
							'stroke'       => array(),
							'stroke-width' => array(),
							'r'            => array(),
						),
					)
				);
	}

	?>
		<div class="sortable_inner_wrapper edit_field_type_<?php echo esc_attr( $display['type'] ); ?>" id="arfmainfieldid_<?php echo esc_attr( $field['id'] ); ?>" inner_class="<?php echo isset( $field['inner_class'] ) ? esc_attr( $field['inner_class'] ) : 'arf_1col'; ?>" <?php echo $sortable_inner_field_style; //phpcs:ignore ?>>
			<div id="arf_field_<?php echo esc_attr( $field['id'] ); ?>" class="arfformfield control-group arfmainformfield   <?php echo isset( $newarr['position'] ) ? esc_attr( $newarr['position'] ) . '_container' : 'top_container'; ?> <?php echo ( isset( $newarr['hide_labels'] ) && $newarr['hide_labels'] == 1 ) ? 'none_container' : ''; ?> arf_field_<?php echo esc_attr( $field['id'] ); ?> ">
				<?php
				if ( ( $frm_css['arfinputstyle'] != 'material' && apply_filters( 'arflite_display_field_name_box', true, $field ) ) || ( $frm_css['arfinputstyle'] == 'material' && in_array( $field['type'], $arf_input_style_label_position ) ) ) {
					?>
				<div class="fieldname-row display-blck-cls" >
					<?php
					if ( isset( $arflite_column_classes['three'] ) && $arflite_column_classes['three'] == '(Third)' ) {
						unset( $arflite_column_classes['three'] );
					}
					if ( isset( $arflite_column_classes['two'] ) && $arflite_column_classes['two'] == '(Second)' ) {
						unset( $arflite_column_classes['two'] );
					}
					?>
					<?php do_action( 'arfliteextrafieldactions', $field['id'] ); ?>
					<div class="fieldname">
						<?php
						$arf_disply_required_field = true;
						$arf_disply_required_field = apply_filters( 'arflite_disply_required_field_outside', $arf_disply_required_field, $field );
						$is_required_field         = false;
						if ( $display['required'] && $field['type'] != 'arfslider' && $field['type'] != 'html' && $arf_disply_required_field ) {
							$is_required_field = true;
						}

						?>
						<label class="arf_main_label <?php echo esc_attr( $arf_main_label_cls ); ?>" id="field_<?php echo esc_attr( $field['id'] ); ?>">
							<span class="arfeditorfieldopt_label arf_edit_in_place">
								<input type="text" class="arf_edit_in_place_input inplace_field" data-ajax="false" data-field-opt-change="true" data-field-opt-key='name' value="<?php echo esc_attr( htmlspecialchars( $field['name'] ) ); ?>" data-field-id="<?php echo esc_attr( $field['id'] ); ?>" />
							</span>
							<?php if ( $is_required_field ) { ?>
							<span id="require_field_<?php echo esc_attr( $field['id'] ); ?>">
								<a href="javascript:void(0)" onclick="javascript:arflitemakerequiredfieldfunction(<?php echo esc_attr( $field['id'] ); ?>,<?php echo $field_required = ( isset( $field['required'] ) && $field['required'] == '0' ) ? '0' : '1'; ?>,'1')" class="arfaction_icon arfhelptip arffieldrequiredicon alignleft arfcheckrequiredfield<?php echo esc_attr( $field_required ); ?>" id="req_field_<?php echo esc_attr( $field['id'] ); ?>" title="<?php echo esc_html__( 'Click to mark as', 'arforms-form-builder' ) . ( $field['required'] == '0' ? ' ' : ' not ' ) . esc_html__( 'compulsory field.', 'arforms-form-builder' ); ?>"></a>
							</span>
							<?php } ?>
						</label>

						<?php if ( $field['type'] == 'hidden' ) { ?>
							<input type="hidden" name="field_options[name_<?php echo esc_attr( $field['id'] ); ?>]" id="arfname_<?php echo esc_attr( $field['id'] ); ?>" value="<?php echo esc_attr( $field['name'] ); ?>" />
						<?php } ?>

					</div>
				</div>
					<?php
				}
				$is_edit_option_icon = in_array( $display['type'], $fields_for_edit_options ) ? true : false;

				$display_opt_icons = true;
				$display_opt_icons = apply_filters( 'arflite_display_field_opt_icons', $display_opt_icons, $field );
				if ( $display_opt_icons ) {
					?>
				<div class="arf_fieldiconbox <?php echo ( $is_edit_option_icon ) ? 'arf_fieldiconbox_with_edit_option' : ''; ?>" data-field_id="<?php echo esc_attr( $field['id'] ); ?>">
					<?php if ( $field['type'] != 'hidden' ) { ?>
						<?php
						if ( in_array( $field['type'], $fields_for_edit_options ) ) {
							echo $arflitefieldcontroller->arflite_get_field_control_icons( 'edit_options', '', esc_attr($field['id']) ); //phpcs:ignore
						}
						if ( $field['type'] == 'html' ) {
							echo $arflitefieldcontroller->arflite_get_field_control_icons( 'running_total_icon' ); //phpcs:ignore
						}

						?>

						<?php
						if ( $field['type'] != 'html' && $field['type'] != 'arfslider' ) {
							$field_required            = ( $field['required'] == '0' ) ? '0' : '1';
							$field_required_cls        = ( $field['required'] == '0' ) ? '' : 'arf_active';
							$arf_disply_required_field = true;
							$arf_disply_required_field = apply_filters( 'arflite_disply_required_field_outside', $arf_disply_required_field, $field );
							if ( $display['required'] && $field['type'] != 'arfslider' && $arf_disply_required_field ) {
								echo $arf_field_require_option_icon = $arflitefieldcontroller->arflite_get_field_control_icons( 'require', $field_required_cls, $field['id'], $field_required ); //phpcs:ignore
							}
						}
						?>

						<?php
						if ( $field['type'] != 'hidden' ) {

						}
						?>
						<?php
					}
					$arflite_id = !empty( $arflite_id ) ? $arflite_id : '';
					echo $arf_field_require_option_icon = $arflitefieldcontroller->arflite_get_field_control_icons( 'duplicate', '', $field['id'], 0, $field['type'], $field['form_id'] ); //phpcs:ignore
					echo $arf_field_require_option_icon = $arflitefieldcontroller->arflite_get_field_control_icons( 'delete', '', $field['id'], 0, '', '' ); //phpcs:ignore
					if ( $field['type'] != 'hidden' ) {

						echo $arflitefieldcontroller->arflite_get_field_control_icons( 'options', '', $field['id'], 0, $field['type'] ); //phpcs:ignore

						echo $arflitefieldcontroller->arflite_get_field_control_icons( 'move' ); //phpcs:ignore
					}
					?>
				</div>
					<?php
				}
				$arf_control_append_class = '';
				if ( $field['type'] == 'checkbox' ) {
					$arf_control_append_class = 'setting_checkbox';
				} elseif ( $field['type'] == 'radio' ) {
					$arf_control_append_class = 'setting_radio';
				} elseif ( $field['type'] == 'select' ) {
					$arf_control_append_class = 'sltstandard_front';
				} elseif ( $field['type'] == 'date' ) {
					$arf_control_append_class = 'arf_date_main_controls';
				}
				$unserialize_field_optins = $arfliteformcontroller->arfliteHtmlEntities( json_decode( $field['field_options'], true ) );
				if ( json_last_error() != JSON_ERROR_NONE ) {
					$unserialize_field_optins = maybe_unserialize( $field['field_options'] );
				}
				$placeholder_text = isset( $unserialize_field_optins['placeholdertext'] ) ? $unserialize_field_optins['placeholdertext'] : ( isset( $unserialize_field_optins['placehodertext'] ) ? $unserialize_field_optins['placehodertext'] : '' );

				$placeholder_text = html_entity_decode( htmlentities( $placeholder_text ) );

				$arf_control_align_class = '';
				if ( isset( $field['align'] ) && $field['align'] != '' ) {
					switch ( $field['align'] ) {
						case 'inline':
							$arf_control_align_class = 'arf_single_row';
							break;
						case 'block':
							$arf_control_align_class = 'arf_multiple_row';
							break;
						case 'arf_col_2':
							$arf_control_align_class = 'arf_col_chk_radio_two';
							break;
						case 'arf_col_3':
							$arf_control_align_class = 'arf_col_chk_radio_three';
							break;
						case 'arf_col_4':
							$arf_control_align_class = 'arf_col_chk_radio_four';
							break;
						default:
							$arf_control_align_class = '';
							break;
					}
				}
				switch ( $field['type'] ) {
					case 'checkbox':
						if ( $frm_css['arfinputstyle'] == 'material' ) {
							$arf_control_append_class .= ' arf_material_checkbox ';
							if ( $newarr['arfcheckradiostyle'] == 'material' ) {
								if ( $newarr['arfcheckradiostyle'] != 'custom' ) {
									$arf_control_append_class .= ' arf_default_material ';
								} else {
									$arf_control_append_class .= ' arf_custom_checkbox ';
								}
							} else {
								if ( $newarr['arfcheckradiostyle'] != 'custom' ) {
									$arf_control_append_class .= ' arf_advanced_material ';
								} else {
									$arf_control_append_class .= ' arf_custom_checkbox ';
								}
							}
						} else {
							if ( $newarr['arfinputstyle'] == 'rounded' ) {
								if ( $newarr['arfcheckradiostyle'] != 'custom' ) {
									$arf_control_append_class .= ' arf_rounded_flat_checkbox ';
								} else {
									$arf_control_append_class .= ' arf_rounded_flat_checkbox arf_custom_checkbox ';
								}
							} elseif ( $newarr['arfinputstyle'] == 'standard' ) {
								if ( $newarr['arfcheckradiostyle'] != 'custom' ) {
									$arf_control_append_class .= ' arf_standard_checkbox ';
								} else {
									$arf_control_append_class .= ' arf_custom_checkbox ';
								}
							}
						}
						break;
					case 'radio':
						if ( $frm_css['arfinputstyle'] == 'material' ) {
							$arf_control_append_class .= ' arf_material_radio ';
							if ( $newarr['arfcheckradiostyle'] == 'material' ) {
								if ( $newarr['arfcheckradiostyle'] != 'custom' ) {
									$arf_control_append_class .= ' arf_default_material ';
								} else {
									$arf_control_append_class .= ' arf_custom_radio ';
								}
							} else {
								if ( $newarr['arfcheckradiostyle'] != 'custom' ) {
									$arf_control_append_class .= ' arf_advanced_material ';
								} else {
									$arf_control_append_class .= ' arf_custom_radio ';
								}
							}
						} else {
							if ( $newarr['arfinputstyle'] == 'rounded' ) {
								if ( $newarr['arfcheckradiostyle'] != 'custom' ) {
									$arf_control_append_class .= ' arf_rounded_flat_radio ';
								} else {
									$arf_control_append_class .= ' arf_custom_radio ';
								}
							} elseif ( $newarr['arfinputstyle'] == 'standard' ) {
								if ( $newarr['arfcheckradiostyle'] != 'custom' ) {
									$arf_control_append_class .= ' arf_standard_radio ';
								} else {
									$arf_control_append_class .= ' arf_custom_radio ';
								}
							}
						}

						break;
				}
				$arf_control_append_class = apply_filters( 'arflite_controls_added_class_outside_materialize', $arf_control_append_class, $field['type'] );
				$arf_field_wrapper_cls    = ( $frm_css['arfinputstyle'] == 'material' ) ? 'input-field' : '';
				?>

				<?php if ( isset( $field['tooltip_text'] ) && $field['tooltip_text'] != '' && $frm_css['arfinputstyle'] == 'material' ) { ?>

						<div data-style="<?php echo esc_attr( $frm_css['arfinputstyle'] ); ?>"   style="<?php echo ( isset( $field['field_width'] ) && $field['field_width'] != '' ) ? 'width:' . esc_attr( $field['field_width'] ) . 'px;' : ''; ?>" class="controls arfhelptipfocus tipso_style <?php echo esc_attr( $arf_control_append_class ) . ' ' . esc_attr( $arf_control_align_class ) . ' ' . esc_attr( $arf_field_wrapper_cls ); ?>" data-title="<?php echo esc_attr( $field['tooltip_text'] ); ?>">

				<?php } else { ?>
						<div data-style="<?php echo esc_attr( $frm_css['arfinputstyle'] ); ?>"   style="<?php echo ( isset( $field['field_width'] ) && $field['field_width'] != '' ) ? 'width:' . esc_attr( $field['field_width'] ) . 'px;' : ''; ?>"  class="controls <?php echo esc_attr( $arf_control_append_class ) . ' ' . esc_attr( $arf_control_align_class ) . ' ' . esc_attr( $arf_field_wrapper_cls ); ?>">
				<?php } ?>

					<?php

					if ( 'material' == $frm_css['arfinputstyle'] ) {
						$material_outlined_cls = '';
						$has_phone_with_utils  = false;
						$phone_with_utils_cls  = '';
						if ( isset( $field['phonetype'] ) ) {
							if ( $field['type'] == 'phone' && $field['phonetype'] == 1 ) {
								$has_phone_with_utils = true;
								$phone_with_utils_cls = 'arf_phone_with_flag';
							}
						}
						if ( ! empty( $field['enable_arf_prefix'] ) || ! empty( $field['enable_arf_suffix'] ) ) {
							$material_outlined_cls = 'arf_material_theme_container_with_icons ' . $phone_with_utils_cls;
						}
						if ( ! empty( $field['enable_arf_prefix'] ) && empty( $field['enable_arf_suffix'] ) ) {
							$material_outlined_cls .= ' arf_only_leading_icon ';
						}
						if ( ! empty( $field['enable_arf_prefix'] ) && ! empty( $field['enable_arf_suffix'] ) ) {
							$material_outlined_cls .= ' arf_both_icons ';
						}
						if ( empty( $field['enable_arf_prefix'] ) && ! empty( $field['enable_arf_suffix'] ) ) {
							$material_outlined_cls .= ' arf_only_trailing_icon ';
						}
						echo "<div class='arf_material_theme_container " . esc_attr( $material_outlined_cls ) . " '>";
						if ( ! empty( $field['enable_arf_prefix'] ) && $has_phone_with_utils == false ) {
							echo '<i class="arf_leading_icon ' . esc_attr( $field['arf_prefix_icon'] ) . '"></i>';
						}
						if ( ! empty( $field['enable_arf_suffix'] ) ) {
							echo '<i class="arf_trailing_icon ' . esc_attr( $field['arf_suffix_icon'] ) . '"></i>';
						}
					}

					switch ( $field['type'] ) {
						case 'text':
						case 'website':
						case 'phone':
						case 'date':
						case 'email':
						case 'confirm_email':
						case 'url':
						case 'number':
						case 'time':
						case 'image':
							$input_cls = '';
							$inp_cls   = '';
							if ( $field['type'] == 'date' ) {
								$input_cls .= ' arf_editor_datetimepicker ';
							} elseif ( $field['type'] == 'time' ) {
								$input_cls .= ' arf_timepicker ';
							} elseif ( $field['type'] == 'phone' ) {
								if ( isset( $field['phonetype'] ) && $field['phonetype'] == 1 ) {
									$input_cls = ' arf_phone_utils ';
								}
							}

							if ( $frm_css['arfinputstyle'] != 'material' ) {
								echo $prefix_suffix_style_start; //phpcs:ignore
								echo $prefix_suffix_style; //phpcs:ignore
								echo $prefix_suffix_style_end; //phpcs:ignore
							}



							if ( $frm_css['arfinputstyle'] != 'material' ) {
								echo wp_kses(
									$prefix_suffix_wrapper_start,
									array(
										'div' => array(
											'id'    => array(),
											'class' => array(),
										),
									)
								);
								echo wp_kses(
									$arf_prefix_icon,
									array(
										'span' => array( 'class' => array() ),
										'i'    => array( 'class' => array() ),
									)
								);
							}

							$field_opts = $arfliteformcontroller->arfliteHtmlEntities( json_decode( $field['field_options'], true ) );
							if ( json_last_error() != JSON_ERROR_NONE ) {
								$field_opts = maybe_unserialize( $field['field_options'] );
							}
							$field_opts['default_value'] = html_entity_decode( htmlentities( $field_opts['default_value'] ) );

							?>

								<input id="field_<?php echo esc_attr( $field['field_key'] ); ?>" name="item_meta[<?php echo esc_attr( $field['id'] ); ?>]" <?php echo isset( $field_opts['default_value'] ) && $field_opts['default_value'] != '' ? 'value="' . esc_attr( $field_opts['default_value'] ) . '"' : ''; ?> <?php echo ( $placeholder_text != '' ) ? 'placeholder="' . esc_attr( $placeholder_text ) . '"' : ''; ?> type="text" <?php echo esc_attr($default_country_code); ?> class="<?php echo esc_attr( $input_cls ) . ' ' . esc_attr( $inp_cls ); ?>" />
								<?php


								if ( $frm_css['arfinputstyle'] == 'material' ) {
									do_action( 'arflite_material_style_editor_content', $field, $frm_css, $display, $arf_main_label_cls, $arflite_column_classes );
								}
								if ( $frm_css['arfinputstyle'] != 'material' ) {
									echo wp_kses(
										$arf_suffix_icon,
										array(
											'span' => array( 'class' => array() ),
											'i'    => array( 'class' => array() ),
										)
									);
									echo wp_kses( $prefix_suffix_wrapper_end, array( 'div' => array() ) );
								}

							break;
						case 'textarea':
							?>
							<textarea name="<?php echo esc_attr( $field_name ); ?>" id="itemmeta_<?php echo esc_attr( $field['id'] ); ?>" onkeyup="arflitechangeitemmeta('<?php echo esc_attr( $field['id'] ); ?>');" rows="<?php echo esc_attr( $field['max_rows'] ); ?>" <?php echo ( $placeholder_text != '' ) ? 'placeholder="' . esc_attr( $placeholder_text ) . '"' : ''; ?> ><?php echo isset( $field['default_value'] ) && $field['default_value'] != '' ? $arflitemainhelper->arflite_esc_textarea( $field['default_value'] ) : ''; //phpcs:ignore ?></textarea>
													   <?php
														if ( $frm_css['arfinputstyle'] == 'material' ) {
															do_action( 'arflite_material_style_editor_content', $field, $frm_css, $display, $arf_main_label_cls, $arflite_column_classes );
														}
							break;
						case 'checkbox':
							if ( $frm_css['arfinputstyle'] == 'material' ) {
								$k               = 0;
								$arf_chk_counter = 1;
								$field_opts      = json_decode( $field['field_options'] );

								if ( isset( $field['options'] ) && ! empty( $field['options'] ) ) {
									if ( ! is_array( $field['options'] ) ) {
										$field['options'] = json_decode( $field['options'], true );
									}

									$chk_icon = '';
									if ( isset( $field['arflite_check_icon'] ) && $field['arflite_check_icon'] != '' ) {
										$chk_icon = $field['arflite_check_icon'];
									} else {
										$chk_icon = 'fas fa-check';
									}


									if ( isset( $field['image_width'] ) && $field['image_width'] != '' ) {
										$image_size = $field['image_width'];
									} else {
										$image_size = 120;
									}

									foreach ( $field['options'] as $opt_key => $opt ) {
										if ( is_admin() && $arf_chk_counter > 5 ) {
											continue;
										}

										$label_image = '';
										if ( isset( $atts ) && isset( $atts['opt'] ) && ( $atts['opt'] != $opt_key ) ) {
											continue;
										}

										$field_val = apply_filters( 'arflitedisplaysavedfieldvalue', $opt, $opt_key, $field );

										$opt = apply_filters( 'arflite_show_field_label', $opt, $opt_key, $field );



										if ( is_array( $opt ) ) {
											$label_image = isset( $opt['label_image'] ) ? $opt['label_image'] : '';
											$opt         = $opt['label'];
											$field_val   = isset( $field['separate_value'] ) && ( $field['separate_value'] ) ? $field_val['value'] : $opt;
										}

										$checked        = '';
										$checked_values = '';

										$checked_values = ( isset( $field['default_value'] ) && $field['default_value'] != '' ) ? $field['default_value'] : array();

										$is_checkbox_checked = false;
										if ( ! empty( $checked_values ) && in_array( $field_val, $checked_values ) ) {
											$is_checkbox_checked = true;
											$checked             = 'checked="checked"';
										}

										$arf_radio_box_hide          = '';
										$arf_custom_checkbox_wrapper = '';

										if ( $field_opts->use_image == 1 && $label_image != '' ) {
											$arf_custom_checkbox_wrapper = 'arffditor_checbox_wrap';
										}

										$label_image_wrapper_class = ( $label_image != '' ) ? 'arf_enable_checkbox_image_editor' : '';
										$material_chk_wrapper      = ( $label_image != '' ) ? 'arf_material_checkbox_image_wrapper' : '';
										echo '<div class="arf_checkbox_style ' . esc_attr( $material_chk_wrapper ) . ' ' . esc_attr( $arf_custom_checkbox_wrapper ) . ' ' . esc_attr( $label_image_wrapper_class ) . '" id="frm_checkbox_' . esc_attr( $field['id'] ) . '-' . esc_attr( $opt_key ) . '">';
										if ( ! isset( $atts ) or ! isset( $atts['label'] ) or $atts['label'] ) {
											$_REQUEST['arfaction'] = ( isset( $_REQUEST['arfaction'] ) ) ? sanitize_text_field( $_REQUEST['arfaction'] ) : '';
											echo "<div class='arf_checkbox_input_wrapper'>";
											echo '<input type="checkbox" name="' . esc_attr( $field_name ) . '[]" id="field_' . esc_attr( $field['id'] ) . '-' . esc_attr( $opt_key ) . '" value="' . esc_attr( $field_val ) . '" ' . esc_attr($checked) . ' style="' . esc_attr( $arf_radio_box_hide ) . '"';
											if ( $k == 0 ) {
												if ( isset( $field['required'] ) && $field['required'] ) {
													$field_blan_msg_val = ( isset( $field['blank'] ) ) ? esc_attr( $field['blank'] ) : '';
													echo 'data-validation-minchecked-minchecked="1" data-validation-minchecked-message="' . esc_attr( $field_blan_msg_val ) . '"';
												}
											} echo ' />';
											echo '<span>';
											if ( $newarr['arfcheckradiostyle'] == 'custom' ) {
												echo "<i class='" . esc_attr( $newarr['arf_checked_checkbox_icon'] ) . "'></i>";
											}
											echo '</span>';
											echo '</div>';
											$label_image_wrapper_class = ( $label_image != '' ) ? 'arf_enable_checkbox_image_editor' : '';
											echo '<label for="field_' . esc_attr( $field['id'] ) . '-' . esc_attr( $opt_key ) . '"  class="' . esc_attr( $label_image_wrapper_class ) . '">';

											if ( $field_opts->use_image == 1 && $label_image != '' ) {
												$temp_check = '';
												if ( $is_checkbox_checked ) {
													$temp_check = 'checked';
												}

												echo '<label for="field_' . esc_attr( $field['id'] ) . '-' . esc_attr( $opt_key ) . '" class="arf_checkbox_label_image_editor ' . esc_attr( $temp_check ) . ' ' . esc_attr( $chk_icon ) . '">';
															echo '<svg role"none" style="max-width:100%; width:' . esc_attr( $image_size ) . 'px; height:' . esc_attr( $image_size ) . 'px">';
															echo '<mask id="clip-cutoff_field_' . esc_attr( $field['id'] ) . '-' . esc_attr( $opt_key ) . '">';
																 echo '<rect fill="white" x="0" y="0" rx="8" ry="8" width="' . esc_attr( $image_size ) . 'px" height="' . esc_attr( $image_size ) . 'px"></rect>';
															   echo '<rect fill="black" rx="4" ry="4" width="27" height="27" class="rect-cutoff"></rect>';
															echo '</mask>';
															echo '<g mask="url(#clip-cutoff_field_' . esc_attr( $field['id'] ) . '-' . esc_attr( $opt_key ) . ')">';
																echo '<image x="0" y="0" height="' . esc_attr( $image_size ) . 'px" preserveAspectRatio="xMidYMid slice" width="' . esc_attr( $image_size ) . 'px" href="' . esc_url( $label_image ) . '"> </image>';
																echo '<rect fill="none" x="0" y="0" rx="8" width="' . esc_attr( $image_size ) . 'px" height="' . esc_attr( $image_size ) . 'px" class="img_stroke"></rect>';
															echo '</g>';
															echo '</svg>';
														echo '</label>';

												 echo '<span class="arf_checkbox_label" style="width:' . esc_attr( $image_size ) . 'px">';
											}
											echo html_entity_decode( $opt ); //phpcs:ignore

											if ( $field_opts->use_image == 1 && $label_image != '' ) {
												echo '</span>';
											}

											echo '</label>';
										}
										echo '</div>';

										$k++;
										$arf_chk_counter++;
									}
								}
							} else {
								$k               = 0;
								$arf_chk_counter = 1;

								$field_opts = json_decode( $field['field_options'] );

								if ( isset( $field['options'] ) && ! empty( $field['options'] ) ) {
									if ( ! is_array( $field['options'] ) ) {
										$field['options'] = json_decode( $field['options'], true );
									}

									$chk_icon = '';
									if ( isset( $field['arflite_check_icon'] ) && $field['arflite_check_icon'] != '' ) {
										$chk_icon = $field['arflite_check_icon'];
									} else {
										$chk_icon = 'fas fa-check';
									}

									if ( isset( $field['image_width'] ) && $field['image_width'] != '' ) {
										$image_size = $field['image_width'];
									} else {
										$image_size = 120;
									}

									foreach ( $field['options'] as $opt_key => $opt ) {
										if ( is_admin() && $arf_chk_counter > 5 ) {
											continue;
										}

										$label_image = '';
										if ( isset( $atts ) && isset( $atts['opt'] ) && ( $atts['opt'] != $opt_key ) ) {
											continue;
										}

										$field_val = apply_filters( 'arflitedisplaysavedfieldvalue', $opt, $opt_key, $field );

										$opt = apply_filters( 'arflite_show_field_label', $opt, $opt_key, $field );

										if ( is_array( $opt ) ) {
											$label_image = isset( $opt['label_image'] ) ? $opt['label_image'] : '';
											$opt         = $opt['label'];
											$field_val   = isset( $field['separate_value'] ) && $field['separate_value'] ? $field_val['value'] : $opt;
										}

										$checked        = '';
										$checked_values = '';

										$checked_values = ( isset( $field['default_value'] ) && $field['default_value'] != '' ) ? $field['default_value'] : array();

										$is_checkbox_checked = false;
										if ( ! empty( $checked_values ) && in_array( $field_val, $checked_values ) ) {
											$is_checkbox_checked = true;
											$checked             = 'checked="checked"';
										}

										$arf_radio_box_hide          = '';
										$arf_custom_checkbox_wrapper = '';

										if ( $field_opts->use_image == 1 && $label_image != '' ) {
											$arf_custom_checkbox_wrapper = 'arf_editor_checbox_wrap arf_enable_checkbox_image_editor';
										}

										echo '<div class="arf_checkbox_style ' . esc_attr( $arf_custom_checkbox_wrapper ) . '" id="frm_checkbox_' . esc_attr( $field['id'] ) . '-' . esc_attr( $opt_key ) . '">';
										echo "<div class='arf_checkbox_input_wrapper'>";
										echo '<input type="checkbox" name="' . esc_attr( $field_name ) . '[]" id="field_' . esc_attr( $field['id'] ) . '-' . esc_attr( $opt_key ) . '" value="' . esc_attr( $field_val ) . '" ' . esc_attr($checked) . ' style="' . esc_attr( $arf_radio_box_hide ) . '"';
										if ( $k == 0 ) {
											if ( isset( $field['required'] ) && $field['required'] ) {
												$field_require_msg_val = isset( $field['blank'] ) ? esc_attr( $field['blank'] ) : '';
												echo 'data-validation-minchecked-minchecked="1" data-validation-minchecked-message="' . esc_attr( $field_require_msg_val ) . '"';
											}
										} echo ' />';
										echo '<span>';
										if ( $newarr['arfcheckradiostyle'] == 'custom' ) {
											echo "<i class='" . esc_attr( $newarr['arf_checked_checkbox_icon'] ) . "'></i>";
										}
										echo '</span>';
										echo '</div>';
										if ( ! isset( $atts ) or ! isset( $atts['label'] ) or $atts['label'] ) {
											$_REQUEST['arfaction']     = ( isset( $_REQUEST['arfaction'] ) ) ? sanitize_text_field( $_REQUEST['arfaction'] ) : '';
											$label_image_wrapper_class = ( $label_image != '' ) ? 'arf_enable_checkbox_image_editor' : '';
											echo '<label for="field_' . esc_attr( $field['id'] ) . '-' . esc_attr( $opt_key ) . '"  class="' . esc_attr( $label_image_wrapper_class ) . '">';
											if ( $field_opts->use_image == 1 && $label_image != '' ) {
												$temp_check = '';
												if ( $is_checkbox_checked ) {
													$temp_check = 'checked';
												}

												echo '<span class="arf_checkbox_label_image_editor ' . esc_attr( $temp_check ) . ' ' . esc_attr( $chk_icon ) . '">';
												echo '<img src="' . esc_attr( $label_image ) . '" style="max-width:100%; width:' . esc_attr( $image_size ) . 'px; height:' . esc_attr( $image_size ) . 'px">';
												echo '</span>';


												echo '<span class="arf_checkbox_label" style="width:' . esc_attr( $image_size ) . 'px">';
											}
											echo html_entity_decode( $opt ); //phpcs:ignore

											if ( $field_opts->use_image == 1 && $label_image != '' ) {
												echo '</span>';
											}

											echo '</label>';
										}
										echo '</div>';
										$k++;
										$arf_chk_counter++;
									}
								}
							}

							break;
						case 'radio':
							if ( $frm_css['arfinputstyle'] == 'material' ) {
								$k                      = 0;
								$arf_chk_counter        = 1;
								$arf_radion_image_class = '';
								$field_opts             = json_decode( stripslashes_deep( $field['field_options'] ) );

								  $chk_icon = '';
								if ( isset( $field['arflite_check_icon'] ) && $field['arflite_check_icon'] != '' ) {
									$chk_icon = $field['arflite_check_icon'];
								} else {
									$chk_icon = 'fas fa-check';
								}

								if ( isset( $field['image_width'] ) && $field['image_width'] != '' ) {
									$image_size = $field['image_width'];
								} else {
									$image_size = 120;
								}

								if ( is_array( $field['options'] ) ) {
									foreach ( $field['options'] as $opt_key => $opt ) {
										if ( is_admin() && $arf_chk_counter > 5 ) {
											continue;
										}
										$label_image = '';
										if ( isset( $atts ) && isset( $atts['opt'] ) && ( $atts['opt'] != $opt_key ) ) {
											continue;
										}

										$field_val = apply_filters( 'arflitedisplaysavedfieldvalue', $opt, $opt_key, $field );

										$opt = apply_filters( 'arflite_show_field_label', $opt, $opt_key, $field );
										if ( is_array( $opt ) ) {
											$label_image = isset( $opt['label_image'] ) ? $opt['label_image'] : '';
											$opt         = $opt['label'];
											$field_val   = isset( $field['separate_value'] ) && ( $field['separate_value'] ) ? $field_val['value'] : $opt;
										}

										$arf_radio_box_hide          = '';
										$arf_custom_checkbox_wrapper = '';

										if ( (isset( $field_opts->use_image) && $field_opts->use_image == 1 ) && $label_image != '' ) {
											$arf_custom_checkbox_wrapper = 'arf_enable_radio_image_editor';
										}

										echo '<div class="arf_radiobutton ' . esc_attr( $arf_custom_checkbox_wrapper ) . '">';
										if ( ! isset( $atts ) or ! isset( $atts['label'] ) or $atts['label'] ) {
											echo "<div class='arf_radio_input_wrapper'>";
											echo '<input type="radio" name="' . esc_attr( $field_name ) . '" id="field_' . esc_attr( $field['id'] ) . '-' . esc_attr( $opt_key ) . '" data-unique-id="" value="' . esc_attr( $field_val ) . '" ';
											$is_radio_checked = false;
											if ( isset( $field['default_value'] ) && $field_val == $field['default_value'] ) {
												$is_radio_checked = true;
												echo 'checked="checked" ';
											}
											if ( $k == 0 ) {
												if ( isset( $field['required'] ) && $field['required'] ) {
													$field_require_msg_val = isset( $field['blank'] ) ? esc_attr( $field['blank'] ) : '';
													echo ' data-validation-minchecked-minchecked="1" data-validation-minchecked-message="' . esc_attr( $field_require_msg_val ) . '"';
												}
											}

											echo ' />';
											echo '<span>';
											if ( $newarr['arfcheckradiostyle'] == 'custom' ) {
												echo "<i class='" . esc_attr( $newarr['arf_checked_radio_icon'] ) . "'></i>";
											}
											echo '</span>';
											echo '</div>';
											if ( ( isset($field_opts->use_image) && $field_opts->use_image == 1) && $label_image != '' ) {
												$arf_radion_image_class = 'arf_enable_radio_image_editor';
											}
											echo '<label for="field_' . esc_attr( $field['id'] ) . '-' . esc_attr( $opt_key ) . '" class="' . esc_attr( $arf_radion_image_class ) . '">';
											if ( (isset($field_opts->use_image) && $field_opts->use_image == 1) && $label_image != '' ) {
												$checked = '';
												if ( $is_radio_checked ) {
													$checked = 'checked';
												}

												  echo '<label for="field_' . esc_attr( $field['id'] ) . '-' . esc_attr( $opt_key ) . '" class="arf_radio_label_image_editor ' . esc_attr($checked) . ' ' . esc_attr($chk_icon) . ' ">';

												echo '<svg role"none" style="max-width:100%; width:' . esc_attr( $image_size ) . 'px; height:' . esc_attr( $image_size ) . 'px">';
													echo '<mask id="clip-cutoff_field_' . esc_attr($field['id']) . '-' . esc_attr($opt_key) . '">';
														echo '<rect fill="white" x="0" y="0" rx="8" ry="8" width="' . esc_attr( $image_size ) . 'px" height="' . esc_attr( $image_size ) . 'px"></rect>';
														echo '<rect fill="black" rx="4" ry="4" width="27" height="27" class="rect-cutoff"></rect>';
													echo '</mask>';
														echo '<g mask="url(#clip-cutoff_field_' . esc_attr( $field['id'] ) . '-' . esc_attr( $opt_key ) . ')">';
															echo '<image x="0" y="0" height="' . esc_attr( $image_size ) . 'px" preserveAspectRatio="xMidYMid slice" width="' . esc_attr( $image_size ) . 'px" href="' . esc_url( $label_image ) . '"> </image>';
															echo '<rect fill="none" x="0" y="0" rx="8" width="' . esc_attr( $image_size ) . 'px" height="' . esc_attr( $image_size ) . 'px" class="img_stroke"></rect>';
														echo '</g>';
												echo '</svg>';
												echo '</label>';

												echo '<span class="arf_checkbox_label">';
											}
											echo html_entity_decode( $opt ); //phpcs:ignore
											if ( (isset($field_opts->use_image) && $field_opts->use_image == 1) && $label_image != '' ) {
												echo '</span>';
											}
											echo '</label>';
										}
										echo '</div>';
										$k++;
										$arf_chk_counter++;
									}
								}
							} else {
								$k                      = 0;
								$arf_chk_counter        = 1;
								$arf_radion_image_class = '';
								$field_opts             = json_decode( stripslashes_deep( $field['field_options'] ) );
								if ( json_last_error() != JSON_ERROR_NONE ) {
									$field_opts = json_decode( $field['field_options'] );
								}
								 $chk_icon = '';
								if ( isset( $field['arflite_check_icon'] ) && $field['arflite_check_icon'] != '' ) {
									$chk_icon = $field['arflite_check_icon'];
								} else {
									$chk_icon = 'fas fa-check';
								}

								if ( isset( $field['image_width'] ) && $field['image_width'] != '' ) {
									$image_size = $field['image_width'];
								} else {
									$image_size = 120;
								}
								if ( is_array( $field['options'] ) ) {
									foreach ( $field['options'] as $opt_key => $opt ) {
										if ( is_admin() && $arf_chk_counter > 5 ) {
											continue;
										}
										$label_image = '';
										if ( isset( $atts ) && isset( $atts['opt'] ) && ( $atts['opt'] != $opt_key ) ) {
											continue;
										}

										$field_val = apply_filters( 'arflitedisplaysavedfieldvalue', $opt, $opt_key, $field );

										$opt = apply_filters( 'arflite_show_field_label', $opt, $opt_key, $field );
										if ( is_array( $opt ) ) {
											$label_image = isset( $opt['label_image'] ) ? $opt['label_image'] : '';
											$opt         = $opt['label'];
											$field_val   = isset( $field['separate_value'] ) && ( $field['separate_value'] ) ? $field_val['value'] : $opt;
										}

										$arf_radio_box_hide          = '';
										$arf_custom_checkbox_wrapper = '';

										if ( $field_opts->use_image == 1 && $label_image != '' ) {
											$arf_custom_checkbox_wrapper = 'arf_enable_radio_image_editor';
										}

										echo '<div class="arf_radiobutton ' . esc_attr( $arf_custom_checkbox_wrapper ) . '">';

										if ( ! isset( $atts ) or ! isset( $atts['label'] ) or $atts['label'] ) {
											echo "<div class='arf_radio_input_wrapper'>";
											echo '<input type="radio" name="' . esc_attr( $field_name ) . '" id="field_' . esc_attr( $field['id'] ) . '-' . esc_attr( $opt_key ) . '" data-unique-id="" value="' . esc_attr( $field_val ) . '" ';
											$is_radio_checked = false;
											if ( isset( $field['default_value'] ) && $field_val == $field['default_value'] ) {
												$is_radio_checked = true;
												echo 'checked="checked" ';
											}
											if ( $k == 0 ) {
												if ( isset( $field['required'] ) && $field['required'] ) {
													$field_require_msg_val = isset( $field['blank'] ) ? esc_attr( $field['blank'] ) : '';
													echo ' data-validation-minchecked-minchecked="1" data-validation-minchecked-message="' . esc_attr( $field_require_msg_val ) . '"';
												}
											}

											echo ' />';
											echo '<span>';
											if ( $newarr['arfcheckradiostyle'] == 'custom' ) {
												echo "<i class='" . esc_attr( $newarr['arf_checked_radio_icon'] ) . "'></i>";
											}
											echo '</span>';
											echo '</div>';
											if ( $field_opts->use_image == 1 && $label_image != '' ) {
												$arf_radion_image_class = 'arf_enable_radio_image_editor';
											}
											echo '<label for="field_' . esc_attr( $field['id'] ) . '-' . esc_attr( $opt_key ) . '" class="' . esc_attr( $arf_radion_image_class ) . '">';
											if ( $field_opts->use_image == 1 && $label_image != '' ) {
												$checked = '';
												if ( $is_radio_checked ) {
													$checked = 'checked';
												}

												echo '<span class="arf_radio_label_image_editor ' . esc_attr( $checked ) . ' ' . esc_attr( $chk_icon ) . '">';
												echo '<img src="' . esc_attr( $label_image ) . '" style="width:' . esc_attr( $image_size ) . 'px; height:' . esc_attr( $image_size ) . 'px; max-width:100%;">';
												echo '</span>';

												echo '<span class="arf_checkbox_label" style="width:' . esc_attr( $image_size ) . 'px; display:block;">';

											}
											echo html_entity_decode( $opt ); //phpcs:ignore
											if ( $field_opts->use_image == 1 && $label_image != '' ) {
												echo '</span>';
											}
											echo '</label>';
										}
										echo '</div>';
										$k++;
										$arf_chk_counter++;
									}
								}
							}
							break;
						case 'select':
							$arf_main_label_cls = '';
							if ( 'material' == $frm_css['arfinputstyle'] ) {
								$arf_main_label_cls = ' active';
							}

							$select_field_opts = array();
							$select_attrs      = array();

							$count_i    = 0;
							$field_opts = json_decode( $field['field_options'] );

							$arf_set_label = false;

							$opt_cls = array();

							if ( is_array( $field['options'] ) && ! empty( $field['options'] ) ) {
								foreach ( $field['options'] as $opt_key => $opt ) {

									$field_val = apply_filters( 'arflitedisplaysavedfieldvalue', $opt, $opt_key, $field );

									$opt = apply_filters( 'arflite_show_field_label', $opt, $opt_key, $field );

									if ( is_array( $opt ) ) {
										$opt = $opt['label'];
										if ( $field_val['value'] == '(Blank)' ) {
											$field_val['value'] = '';
										}
										$field_val = ( $field['separate_value'] ) ? $field_val['value'] : $opt;
									}
									if ( $count_i == 0 and $opt == '' ) {
										$opt = addslashes( esc_html__( 'Please select', 'arforms-form-builder' ) );
									}

									$arfdefault_selected_val = isset( $field['default_value'] ) ? trim( $field['default_value'] ) : $field['value'];

									if ( isset( $field['set_field_value'] ) && ! empty( $field['set_field_value'] ) ) {
										$arfdefault_selected_val = $field['set_field_value'];
									}

									if ( ! empty( $arfdefault_selected_val ) ) {
										$arf_set_label = false;
									}

									$select_field_opts[ $field_val ] = $opt;

									$count_i++;
								}
							}

							$select_attrs['data-default-val'] = $arfdefault_selected_val;
							if ( isset( $field['required'] ) and $field['required'] ) {
								$select_attrs['data-validation-required-message'] = esc_attr( $field['blank'] );
							}

							echo $arflitemaincontroller->arflite_selectpicker_dom( $field_name, 'field_' . $field['field_key'], ' arf_form_field_picker ', '', $arfdefault_selected_val, $select_attrs, $select_field_opts, false, $opt_cls, false, array(), true, $field, false, '', '', $arf_set_label ); //phpcs:ignore

							if ( $frm_css['arfinputstyle'] == 'material' ) {
								do_action( 'arflite_material_style_editor_content', $field, $frm_css, $display, $arf_main_label_cls, $arflite_column_classes );
							}

							break;
						case 'captcha':
							global $arformsmain;
							$re_theme = $arformsmain->arforms_get_settings('re_theme','general_settings');
							$re_theme = !empty( $re_theme ) ? $re_theme : 'light';

							$pubkey = $arformsmain->arforms_get_settings('pubkey','general_settings');
							$pukey = !empty( $pubkey ) ? $pubkey : '';

							?>

							<img alt='' id="recaptcha_<?php echo esc_attr( $field['id'] ); ?>" src="<?php echo esc_url( ARFLITEURL ); ?>/images/<?php echo esc_attr( $re_theme ); ?>-captcha.png" alt="captcha" class="captcha_class" style="max-width:100%;"/>

							<div id="custom-captcha_<?php echo esc_attr( $field['id'] ); ?>" class="alignleft custom_captcha_div captcha_class"></div>

							<div class="arflite-clear-float"></div>
							<?php
							if ( empty( $pubkey ) && ( isset( $field['is_recaptcha'] ) && $field['is_recaptcha'] != 'custom-captcha' ) ) { ?>
								<div class="howto" id="setup_captcha_message" ><?php echo esc_html__( 'Please setup site key and private key in Global Settings otherwise recaptcha will not appear', 'arforms-form-builder' ); ?></div>    
							<?php } ?>

							<div class="howto" id="setup_general_message"></div>

							<input type="hidden" name="<?php echo esc_attr( $field_name ); ?>" value="1" id="field_<?php echo esc_attr( $field['field_key'] ); ?>"/>

							<?php
							break;
						case 'html':
							?>
							<p class="howto clear"><?php echo esc_html__( 'Note: Set your custom html content', 'arforms-form-builder' ); ?></p>
							<?php
							break;
						case 'hidden':
							?>
							<input type="text" name="<?php echo esc_attr( $field_name ); ?>" id="itemmeta_<?php echo esc_attr( $field['id'] ); ?>" onkeyup="arflitechangeitemmeta('<?php echo esc_attr( $field['id'] ); ?>');" value="<?php echo isset( $field['default_value'] ) ? esc_attr( $field['default_value'] ) : ''; ?>"/>

							<p class="howto clear"><?php echo esc_html__( 'Note: This field will not show in the form. Enter the value to be hidden.', 'arforms-form-builder' ); ?><br/>
								[ARF_current_user_id], [ARF_current_user_name], [ARF_current_user_email], [ARF_current_date]</p>
							<?php
							break;
						case 'arfslider':
							$field['slider_handle']      = isset( $field['slider_handle'] ) ? $field['slider_handle'] : 'round';
							$field['slider_value']       = isset( $field['slider_value'] ) ? $field['slider_value'] : '10';
							$field['arf_range_selector'] = isset( $field['arf_range_selector'] ) ? $field['arf_range_selector'] : 0;
							$field['minnum']             = isset( $field['minnum'] ) ? $field['minnum'] : '0';
							$field['maxnum']             = isset( $field['maxnum'] ) ? $field['maxnum'] : '50';
							$field['slider_step']        = isset( $field['slider_step'] ) ? $field['slider_step'] : '1';


							$slider_class = 'slider_class';
							if ( isset( $field['arf_range_selector'] ) && $field['arf_range_selector'] == '1' ) {
								$slider_class = 'slider_range_class';
								if ( $field['slider_handle'] == 'square' ) {
									$slider_class = 'slider_range_class2';
								} elseif ( $field['slider_handle'] == 'triangle' ) {
									$slider_class = 'slider_range_class3';
								}
							} else {
								$slider_class = 'slider_class';
								if ( $field['slider_handle'] == 'square' ) {
									$slider_class = 'slider_class2';
								} elseif ( $field['slider_handle'] == 'triangle' ) {
									$slider_class = 'slider_class3';
								}
							}
							$slider_value = $field['slider_value'];

							if ( $field['arf_range_selector'] == 1 ) {
								if ( is_array( $slider_value ) && count( $slider_value ) > 0 ) {
									$slider_value = json_encode( array( $slider_value[0], $slider_value[1] ) );
								} else {
									$slider_value = json_encode( array( $field['arf_range_minnum'], $field['arf_range_maxnum'] ) );
								}
							}
							$slider_bg_color        = isset( $field['slider_bg_color2'] ) ? $field['slider_bg_color2'] : '#f5f5f5';
							$slider_selection_color = isset( $field['slider_bg_color'] ) ? $field['slider_bg_color'] : '#f9f9f9';
							$slider_handle_color    = isset( $field['slider_handle_color'] ) ? $field['slider_handle_color'] : '#149bdf';
							?>
							<div id="slider_sample_<?php echo esc_attr( $field['id'] ); ?>" class="<?php echo esc_attr( $slider_class ); ?> arf_editor_slider_class">
								<input type='text' name='item_meta[<?php echo esc_attr( $field['id'] ); ?>]' id='arf_slider_<?php echo esc_attr( $field['id'] ); ?>' class='arf_editor_slider inplace_field' data-slider-min='<?php echo esc_attr( $field['minnum'] ); ?>' data-slider-max='<?php echo esc_attr( $field['maxnum'] ); ?>' data-slider-step='<?php echo esc_attr( $field['slider_step'] ); ?>' data-slider-value='<?php echo esc_attr( $slider_value ); ?>' />
							</div>
							<?php
							break;
						default:
							do_action( 'arflitedisplayaddedfields', $field, $frm_css['arfinputstyle'] );
							break;
					}
					if ( 'material' == $frm_css['arfinputstyle'] ) {
						echo '</div>';
					}
					$field_description = '';
					if ( isset( $field['description'] ) ) {
						$field_description = $field['description'];
					} elseif ( isset( $field['field_options']['description'] ) && is_array( $field['field_options'] ) ) {
						$field_description = $field['field_options']['description'];
					} elseif ( isset( $field['field_options']['description'] ) && ! is_array( $field['field_options'] ) ) {
						$tmp_field_options = json_decode( $field['field_options'], true );
						if ( json_last_error() != JSON_ERROR_NONE ) {
							$tmp_field_options = maybe_unserialize( $field['field_options'] );
						}
						$field_description = isset( $tmp_field_options['description'] ) ? $tmp_field_options['description'] : '';
					}
					?>
					<?php if ( isset( $field['tooltip_text'] ) && $field['tooltip_text'] != '' && $frm_css['arfinputstyle'] != 'material' ) { ?>
						<div class="arftootltip_position arfhelptip tipso_style" id="tooltip_field_<?php echo esc_attr( $field['id'] ); ?>" data-title="<?php echo esc_attr( $field['tooltip_text'] ); ?>">
							<span>
								<svg width="30px" height="30px" viewBox="0 0 30 30">
								<path xmlns="http://www.w3.org/2000/svg" fill="#BEC5D5" d="M9.609,0.33c-4.714,0-8.5,3.786-8.5,8.5s3.786,8.5,8.5,8.5s8.5-3.786,8.5-8.5S14.323,0.33,9.609,0.33z   M10.381,13.467c0,0.23-0.154,0.387-0.387,0.387H9.222c-0.231,0-0.387-0.156-0.387-0.387v-0.772c0-0.231,0.155-0.388,0.387-0.388  h0.772c0.232,0,0.387,0.156,0.387,0.388V13.467z M11.425,10.028c-0.541,0.463-0.929,0.772-1.044,1.197  c-0.039,0.193-0.193,0.309-0.387,0.309H9.222c-0.231,0-0.426-0.193-0.387-0.425c0.155-1.12,0.966-1.738,1.623-2.279  c0.697-0.541,1.082-0.889,1.082-1.546c0-1.082-0.85-1.932-1.932-1.932s-1.933,0.85-1.933,1.932c0,0.078,0,0.154,0,0.232  c0.04,0.192-0.077,0.386-0.27,0.425L6.672,8.173C6.44,8.25,6.208,8.096,6.169,7.864C6.131,7.67,6.131,7.478,6.131,7.284  c0-1.932,1.545-3.478,3.478-3.478c1.932,0,3.477,1.546,3.477,3.478C13.085,8.714,12.16,9.448,11.425,10.028L11.425,10.028z">
								</path>
								</svg>
							</span>
						</div>
					<?php } ?>

					<?php
						$display_description_block = apply_filters( 'arflite_hide_description_block', false, $field );
					if ( $field['type'] != 'html' && ! $display_description_block ) {
						?>
						<div class="arf_field_description" id="field_description_<?php echo esc_attr( $field['id'] ); ?>"><?php echo isset( $field['description'] ) ? esc_attr( $field['description'] ) : ( isset( $field['field_options']['description'] ) ? esc_attr( $field['field_options']['description'] ) : '' ); ?></div>
						<?php
					}
					?>

					<div class="help-block">

					</div>
					<?php
					if ( $field['type'] == 'phone' && isset( $field['phonetype'] ) && $field['phonetype'] == 1 ) {


						if ( isset( $phtypes ) && count( $phtypes ) > 0 ) {
							echo "<input type='hidden' id='field_" . esc_attr( $field['key'] ) . "_country_list' value='" . json_encode( $phtypes ) . "' />";
							echo "<input type='hidden' id='field_" . esc_attr( $field['key'] ) . "_default_country' value='" . esc_attr( $default_country ) . "' />";
						}
					}
					?>
				</div>

				<?php
				$field_opt_html    = '';
				$field_custom_html = '';

				if ( isset( $field['field_options'] ) ) {
					if ( ! is_array( $field['field_options'] ) ) {
						$field['field_options'] = json_decode( $field['field_options'], true );
						if ( json_last_error() != JSON_ERROR_NONE ) {
							$field['field_options'] = maybe_unserialize( $field['field_options'] );
						}
					}
					if ( isset( $field['field_options']['custom_html'] ) ) {
						$field_opt_html = htmlspecialchars( $field['field_options']['custom_html'] );
					}
				}
				$field_opt_html_set = false;
				if ( isset( $field['field_options'] ) ) {
					if ( ! is_array( $field['field_options'] ) ) {
						$field['field_options'] = json_decode( $field['field_options'], true );
						if ( json_last_error() != JSON_ERROR_NONE ) {
							$field['field_options'] = maybe_unserialize( $field['field_options'] );
						}
					}
					if ( isset( $field['field_options']['custom_html'] ) ) {
						unset( $field['field_options']['custom_html'] );
						$field_opt_html_set = true;
					}
				}

				if ( $field_opt_html_set ) {
					$field['field_options']['custom_html'] = htmlspecialchars( $field_opt_html );
				}

				$field_custom_html = isset( $field['custom_html'] ) ? htmlspecialchars( $field['custom_html'] ) : '';

				$field['custom_html'] = htmlspecialchars( $field_custom_html );

				$field_opt_arr = $arflitefieldhelper->arflite_getfields_basic_options_section();

				$field_order   = isset( $field_opt_arr[ $field['type'] ] ) ? $field_opt_arr[ $field['type'] ] : '';
				$new_field_obj = array();
				$field_type    = $field['type'];

				$field_data_obj_array = $arfliteformcontroller->arfliteObjtoArray( $field_data_obj );

				$field_data_obj_array = apply_filters( 'arflite_change_json_default_data_ouside', $field_data_obj_array );

				if ( isset( $inside_repeatable_field ) && true == $inside_repeatable_field ) {
					$field_data_obj_array = apply_filters( 'arflite_add_parent_data_to_field', $field_data_obj_array, $field['type'], $check_field['id'] );
				}

				$field_data_obj_array = json_encode( $field_data_obj_array );

				$field_data_obj = json_decode( $field_data_obj_array );

				foreach ( $field_data_obj->field_data->$field_type as $key => $val ) {
					$new_field_obj[ $key ] = ( isset( $field[ $key ] ) && $field[ $key ] != '' ) ? $field[ $key ] : ( isset( $unserialize_field_optins[ $key ] ) ? $unserialize_field_optins[ $key ] : '' );
					if ( $key == 'options' ) {
						$new_field_obj[ $key ] = $field[ $key ];
					}
					if ( isset( $_REQUEST['arfaction'] ) && sanitize_text_field( $_REQUEST['arfaction'] ) != 'edit' ) {
						if ( $key == 'placeholdertext' ) {
							$new_field_obj[ $key ] = $placeholder_text;
						}
					}
				}

				$new_field_obj['default_value'] = isset( $field['default_value'] ) ? $field['default_value'] : ( isset( $field['field_options']['default_value'] ) ? $field['field_options']['default_value'] : '' );

				if ( isset( $new_field_obj['page_no'] ) && ( $new_field_obj['page_no'] == '' || $new_field_obj['page_no'] < 1 ) ) {
					$new_field_obj['page_no'] = 1;
				}

				if ( isset( $new_field_obj['locale'] ) ) {
					$new_field_obj['locale'] = $new_field_obj['locale'] != '' ? $new_field_obj['locale'] : 'en';
				}
				$new_field_obj['image_position_from'] = ( isset( $new_field_obj['image_position_from'] ) && $new_field_obj['image_position_from'] != '' ) ? $new_field_obj['image_position_from'] : 'top_left';

				$new_field_obj = $arfliteformcontroller->arflite_html_entity_decode( $new_field_obj );

				?>
				<input type="hidden" name="arf_field_data_<?php echo esc_attr( $field['id'] ); ?>" id="arf_field_data_<?php echo esc_attr( $field['id'] ); ?>" class="arf_field_data_hidden" value="<?php echo esc_attr( htmlspecialchars( json_encode( $new_field_obj ) ) ); ?>" data-field_options='<?php echo json_encode( $field_order ); ?>' />
				<div class="arf_field_option_model arf_field_option_model_cloned" data-field_id="<?php echo esc_attr( $field['id'] ); ?>">
					<div class="arf_field_option_model_header"><?php echo esc_html__( 'Field Options', 'arforms-form-builder' ); ?>&nbsp;<span class="arf_pre_populated_field_type" id="{arf_field_type}">[Field Type : [arf_field_type]]</span>&nbsp;<span class="arf_pre_populated_field_id" id="{arf_field_id}">[Field ID:[arf_field_id]]</span></div>
					<div class="arf_field_option_model_container">
						<div class="arf_field_option_content_row">
						</div>
					</div>
					<div class="arf_field_option_model_footer">
						<button type="button" class="arf_field_option_close_button" onClick="arflite_close_field_option_popup(<?php echo esc_attr( $field['id'] ); ?>);"><?php echo esc_html__( 'Cancel', 'arforms-form-builder' ); ?></button>
						<button type="button" class="arf_field_option_submit_button" data-field_id="<?php echo esc_attr( $field['id'] ); ?>"><?php echo esc_html__( 'OK', 'arforms-form-builder' ); ?></button>
					</div>
				</div>
				<?php
				if ( in_array( $field['type'], $fields_for_edit_options ) ) {
					?>
					<div class="arf_field_values_model" id="arf_field_values_model_skeleton_<?php echo esc_attr( $field['id'] ); ?>">
						<div class="arf_field_values_model_header"><?php echo esc_html__( 'Edit Options', 'arforms-form-builder' ); ?></div>
						<div class="arf_field_values_model_container">
							<div class="arf_field_values_content_row">
								<div class="arf_field_values_content_loader">
									<svg version="1.1" id="arf_field_values_loader" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="48px" height="48px" viewBox="0 0 26.349 26.35" style="enable-background:new 0 0 26.349 26.35;" fill="#03A9F4" xml:space="preserve" ><g><g><circle cx="13.792" cy="3.082" r="3.082" /><circle cx="13.792" cy="24.501" r="1.849"/><circle cx="6.219" cy="6.218" r="2.774"/><circle cx="21.365" cy="21.363" r="1.541"/><circle cx="3.082" cy="13.792" r="2.465"/><circle cx="24.501" cy="13.791" r="1.232"/><path d="M4.694,19.84c-0.843,0.843-0.843,2.207,0,3.05c0.842,0.843,2.208,0.843,3.05,0c0.843-0.843,0.843-2.207,0-3.05 C6.902,18.996,5.537,18.988,4.694,19.84z"/><circle cx="21.364" cy="6.218" r="0.924"/></g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g></svg>
								</div>
							</div>
						</div>
						<div class="arf_field_values_model_footer">
							<button type="button" class="arf_field_values_close_button"><?php echo esc_html__( 'Cancel', 'arforms-form-builder' ); ?></button>
							<button type="button" class="arf_field_values_submit_button" data-field-id="<?php echo esc_attr( $field['id'] ); ?>"><?php echo esc_html__( 'OK', 'arforms-form-builder' ); ?></button>
						</div>
					</div>
					<?php
				}
				?>
			</div>
		</div>
		<?php if ( $class == 'arf_1col' || $class == 'arf_2col' || $class == 'arf_3col' || $class == 'arf_4col' || $class == 'arf_5col' || $class == 'arf_6col' ) { ?>
		</div>
			<?php
			$index_arf_fields++;
		}

		unset( $display );


} else {

	if ( ! empty( $confirm_field_options ) ) {
		if ( $define_classes == 'arf_1' || $define_classes == 'arf_1col' || $define_classes == 'arf21colclass' || $define_classes == 'arf31colclass' || $define_classes == 'arf41colclass' || $define_classes == 'arf51colclass' || $define_classes == 'arf61colclass' ) {
			?>
			<div class="arf_inner_wrapper_sortable arfmainformfield edit_form_item arffieldbox ui-state-default arf1columns <?php echo esc_attr( $multicolclass ); ?>" data-id="arf_editor_main_row_<?php echo esc_attr( $index_arf_fields ); ?>">
				<?php
				echo wp_kses(
					$multicol_html,
					array(
						'div'    => array(
							'class'      => array(),
							'id'         => array(),
							'data-value' => array(),
						),
						'input'  => array(
							'type'    => array(),
							'class'   => array(),
							'id'      => array(),
							'onclick' => array(),
							'data-id' => array(),
							'checked' => array(),
							'value'   => array(),
							'name'    => array(),
						),
						'label'  => array(
							'for' => array(),
						),
						'span'   => array(
							'class' => array(),
						),
						'svg'    => array(
							'viewbox'     => array(),
							'xmlns'       => array(),
							'width'       => array(),
							'height'      => array(),
							'fill'        => array(),
							'version'     => array(),
							'xmlns:xlink' => array(),
							'id'          => array(),
							'x'           => array(),
							'y'           => array(),
							'style'       => array(),
							'xml:space'   => array(),
						),
						'path'   => array(
							'd'            => array(),
							'fill'         => array(),
							'fill-rule'    => array(),
							'clip-rule'    => array(),
							'xmlns'        => array(),
							'stroke'       => array(),
							'stroke-width' => array(),
						),
						'g'      => array(
							'id'   => array(),
							'fill' => array(),
						),
						'rect'   => array(
							'x'            => array(),
							'y'            => array(),
							'width'        => array(),
							'height'       => array(),
							'rx'           => array(),
							'stroke'       => array(),
							'stroke-width' => array(),
							'fill'         => array(),
							'transform'    => array(),
						),
						'circle' => array(
							'cx'           => array(),
							'cy'           => array(),
							'fill'         => array(),
							'stroke'       => array(),
							'stroke-width' => array(),
							'r'            => array(),
						),
					)
				);
				?>
				<?php
		}

		$confirm_enable_arf_prefix   = $confirm_field_options['enable_arf_prefix'];
		$confirm_arf_prefix_icon     = $confirm_field_options['arf_prefix_icon'];
		$confirm_enable_arf_suffix   = $confirm_field_options['enable_arf_suffix'];
		$confirm_arf_suffix_icon     = $confirm_field_options['arf_suffix_icon'];
		$confirm_arflite_default_val = $confirm_field_options['default_value'];


		$prefix_suffix_wrapper_start = '';
		$prefix_suffix_wrapper_end   = '';
		$has_prefix_suffix           = false;
		$prefix_suffix_class         = '';
		$has_prefix                  = false;
		$has_suffix                  = false;
		$arf_prefix_icon             = '';
		$arf_suffix_icon             = '';
		$prefix_suffix_style_start   = "<style id='arf_field_prefix_suffix_style_" . esc_attr( $field ) . "' type='text/css'>";
		$prefix_suffix_style         = '';
		$prefix_suffix_style_end     = '</style>';

		$arf_is_phone_with_flag = false;

		if ( isset( $field['type'] ) && isset( $field['phonetype'] ) && $field['type'] == 'phone' && $field['phonetype'] == 1 ) {
			$arf_is_phone_with_flag = true;
		}

		if ( isset( $confirm_enable_arf_prefix ) && $confirm_enable_arf_prefix == 1 && $arf_is_phone_with_flag == false ) {
			$has_prefix_suffix = true;
			$has_prefix        = true;
			$arf_prefix_icon   = "<span class='arf_editor_prefix_icon'><i class='" . esc_attr( $confirm_arf_prefix_icon ) . "'></i></span>";
		}

		if ( isset( $confirm_enable_arf_suffix ) && $confirm_enable_arf_suffix == 1 ) {
			$has_prefix_suffix = true;
			$has_suffix        = true;
			$arf_suffix_icon   = "<span class='arf_editor_suffix_icon'><i class='" . esc_attr( $confirm_arf_suffix_icon ) . "'></i></span>";
		}


		if ( $has_prefix == true && $has_suffix == false ) {
			$prefix_suffix_class = ' arf_prefix_only ';
		} elseif ( $has_prefix == false && $has_suffix == true ) {
			$prefix_suffix_class = ' arf_suffix_only ';
		} elseif ( $has_prefix == true && $has_suffix == true ) {
			$prefix_suffix_class = ' arf_both_pre_suffix ';
		}

		if ( isset( $has_prefix_suffix ) && $has_prefix_suffix == true ) {
			$prefix_suffix_wrapper_start = "<div id='arf_editor_prefix_suffix_container_" . esc_attr( $field ) . "' class='arf_editor_prefix_suffix_wrapper " . trim( esc_attr( $prefix_suffix_class ) ) . "'>";
			$prefix_suffix_wrapper_end   = '</div>';
		}

		if ( $frm_css['arfinputstyle'] == 'material' ) {
			$prefix_suffix_wrapper_start = $prefix_suffix_wrapper_end = '';
		}

		if ( $confirm_field_options['type'] == 'email' ) {

			$confirm_email_label       = $confirm_field_options['confirm_email_label'];
			$confirm_email_placeholder = $confirm_field_options['confirm_email_placeholder'];

			if ( $frm_css['arfinputstyle'] != 'material' ) {
				?>
				<div id="arfmainfieldid_<?php echo esc_attr( $field ); ?>" class="sortable_inner_wrapper arf_confirm_field ui-droppable ui-sortable"  inner_class="<?php echo esc_attr( $define_classes ); ?>" <?php echo $sortable_inner_field_style; //phpcs:ignore ?>>
					<div id="arf_field_<?php echo esc_attr( $field ); ?>" class="arfformfield control-group arfmainformfield <?php echo isset( $newarr['position'] ) ? esc_attr( $newarr['position'] ) . '_container' : 'top_container'; ?> arf_field arf_confirm_field">
						<div class="fieldname-row" class="display-blck-cls">
							<div class="fieldname">
								<label class="arf_main_label <?php echo esc_attr( $arf_main_label_cls ); ?>" id="field_<?php echo esc_attr( $field ); ?>">
									<span class="arfeditorfieldopt_label arf_edit_in_place"><?php echo esc_attr($confirm_email_label); ?></span>
								</label>
							</div>
						</div>
						<div class="arf_fieldiconbox" data-field_id=<?php echo esc_attr( $field ); ?>>
							<div class='arf_field_option_icon'><a class='arf_field_option_input'><svg id='moveing' height='20' width='21'><g><?php echo ARFLITE_CUSTOM_MOVING_ICON; //phpcs:ignore ?></g></svg></a></div>
						</div>
						<?php
							$field_width = ( isset( $field['field_width'] ) && $field['field_width'] != '' ) ? 'width:' . $field['field_width'] . 'px;' : '';
						if ( ! empty( $confirm_field_options ) ) {
							$field_width = ( isset( $confirm_field_options['field_width'] ) && $confirm_field_options['field_width'] != '' ) ? 'width:' . $confirm_field_options['field_width'] . 'px;' : '';
						}
						?>
												
						<div class="controls" style="<?php echo esc_attr( $field_width ); ?>" >
						<?php
						if ( 'material' == $frm_css['arfinputstyle'] ) {

							$material_outlined_cls = '';
							if ( ! empty( $confirm_field_options['enable_arf_prefix'] ) || ! empty( $confirm_field_options['enable_arf_suffix'] ) ) {
								$material_outlined_cls = 'arf_material_theme_container_with_icons';
							}
							if ( ! empty( $confirm_field_options['enable_arf_prefix'] ) && empty( $confirm_field_options['enable_arf_suffix'] ) ) {
								$material_outlined_cls .= ' arf_only_leading_icon ';
							}

							if ( empty( $confirm_field_options['enable_arf_prefix'] ) && ! empty( $confirm_field_options['enable_arf_suffix'] ) ) {
								$material_outlined_cls .= ' arf_only_trailing_icon ';
							}
							echo "<div class='arf_material_theme_container " . esc_attr( $material_outlined_cls ) . " '>";
							if ( ! empty( $confirm_field_options['enable_arf_prefix'] ) ) {
								echo '<i class="arf_leading_icon ' . esc_attr( $confirm_field_options['arf_prefix_icon'] ) . '"></i>';
							}
							if ( ! empty( $confirm_field_options['enable_arf_suffix'] ) ) {
								echo '<i class="arf_trailing_icon ' . esc_attr( $confirm_field_options['arf_suffix_icon'] ) . '"></i>';
							}
						}
						?>

						<?php
						if ( $frm_css['arfinputstyle'] != 'material' ) {
								echo wp_kses(
									$prefix_suffix_wrapper_start,
									array(
										'div' => array(
											'id'    => array(),
											'class' => array(),
										),
									)
								);
								echo wp_kses(
									$arf_prefix_icon,
									array(
										'span' => array( 'class' => array() ),
										'i'    => array( 'class' => array() ),
									)
								);
						}
						?>
							<input id="field_confiorm_email" value="<?php echo esc_attr( $confirm_arflite_default_val ); ?>" name="confirm_email" <?php echo ( $confirm_email_placeholder != '' ) ? 'placeholder=' . esc_attr( $confirm_email_placeholder ) : ''; ?> type="text" class="arflite_float_left" />
						<?php
						if ( $frm_css['arfinputstyle'] != 'material' ) {
							echo wp_kses(
								$arf_suffix_icon,
								array(
									'span' => array( 'class' => array() ),
									'i'    => array( 'class' => array() ),
								)
							);
							echo wp_kses( $prefix_suffix_wrapper_end, array( 'div' => array() ) );
						}
						?>
						</div>
					</div>
				</div>
			<?php } else { ?>
				<div id="arfmainfieldid_<?php echo esc_attr( $field ); ?>" class="sortable_inner_wrapper arf_confirm_field ui-droppable ui-sortable"  inner_class="<?php echo esc_attr( $define_classes ); ?>" <?php echo $sortable_inner_field_style; //phpcs:ignore ?>>
					<div id="arf_field_<?php echo esc_attr( $field ); ?>" class="arfformfield control-group arfmainformfield top_container arf_field arf_confirm_field" style="">

						<div class="arf_fieldiconbox" data-field_id=<?php echo esc_attr( $field ); ?>>
							<div class='arf_field_option_icon'><a class='arf_field_option_input'><svg id='moveing' height='20' width='21'><g><?php echo ARFLITE_CUSTOM_MOVING_ICON; //phpcs:ignoreFile ?></g></svg></a></div>
						</div>
						<div class="controls input-field" data-style="material"   style="<?php echo ( isset( $field['field_width'] ) && $field['field_width'] != '' ) ? 'width:' . esc_attr( $field['field_width'] ) . 'px;' : ''; ?>" >
							<?php
							if ( 'material' == $frm_css['arfinputstyle'] ) {

								$material_outlined_cls = '';
								if ( ! empty( $confirm_field_options['enable_arf_prefix'] ) || ! empty( $confirm_field_options['enable_arf_suffix'] ) ) {
									$material_outlined_cls = 'arf_material_theme_container_with_icons';
								}
								if ( ! empty( $confirm_field_options['enable_arf_prefix'] ) && empty( $confirm_field_options['enable_arf_suffix'] ) ) {
									$material_outlined_cls .= ' arf_only_leading_icon ';
								}

								if ( empty( $confirm_field_options['enable_arf_prefix'] ) && ! empty( $confirm_field_options['enable_arf_suffix'] ) ) {
									$material_outlined_cls .= ' arf_only_trailing_icon ';
								}
								echo "<div class='arf_material_theme_container " . esc_attr( $material_outlined_cls ) . " '>";
								if ( ! empty( $confirm_field_options['enable_arf_prefix'] ) ) {
									echo '<i class="arf_leading_icon ' . esc_attr( $confirm_field_options['arf_prefix_icon'] ) . '"></i>';
								}
								if ( ! empty( $confirm_field_options['enable_arf_suffix'] ) ) {
									echo '<i class="arf_trailing_icon ' . esc_attr( $confirm_field_options['arf_suffix_icon'] ) . '"></i>';
								}
							}
							?>
							<input id="field_confiorm_email" value="<?php echo esc_attr( $confirm_arflite_default_val ); ?>" name="confirm_email" <?php echo ( $confirm_email_placeholder != '' ) ? 'placeholder=' . esc_attr( $confirm_email_placeholder ) : ''; ?> type="text" class="arflite_float_left" />
							<?php
							if ( 'material' == $frm_css['arfinputstyle'] ) {
								echo '<div class="arf_material_standard">';
									echo '<div class="arf_material_theme_prefix"></div>';
									echo '<div class="arf_material_theme_notch">';
										echo '<label class="arf_main_label ' . esc_attr( $arf_main_label_cls ) . '" id="field_' . esc_attr( $field ) . '">' . esc_attr($confirm_email_label) . '</label>';
									echo '</div>';
									echo '<div class="arf_material_theme_suffix"></div>';
									echo '</div>';
								echo '</div>';
							}
							?>
														
						</div>
					</div>
				</div>
				<?php
			}
		}

		if ( $define_classes == 'arf_1' || $define_classes == 'arf_1col' || $define_classes == 'arf_2col' || $define_classes == 'arf_3col' || $define_classes == 'arf_4col' || $define_classes == 'arf_5col' || $define_classes == 'arf_6col' ) {
			echo '</div>';
			$index_arf_fields++;
		}
	} else {

		if ( $define_classes == 'arf_1' || $define_classes == 'arf_1col' || $define_classes == 'arf21colclass' || $define_classes == 'arf31colclass' || $define_classes == 'arf41colclass' || $define_classes == 'arf51colclass' || $define_classes == 'arf61colclass' ) {
			?>
			<div class="arf_inner_wrapper_sortable arfmainformfield edit_form_item arffieldbox ui-state-default arf1columns <?php echo esc_attr( $multicolclass ); ?>" data-id="arf_editor_main_row_<?php echo esc_attr( $index_arf_fields ); ?>">
				<?php
				echo wp_kses(
					$multicol_html,
					array(
						'div'    => array(
							'class'      => array(),
							'id'         => array(),
							'data-value' => array(),
						),
						'input'  => array(
							'type'    => array(),
							'class'   => array(),
							'id'      => array(),
							'onclick' => array(),
							'data-id' => array(),
							'checked' => array(),
							'value'   => array(),
							'name'    => array(),
						),
						'label'  => array(
							'for' => array(),
						),
						'span'   => array(
							'class' => array(),
						),
						'svg'    => array(
							'viewbox'     => array(),
							'xmlns'       => array(),
							'width'       => array(),
							'height'      => array(),
							'fill'        => array(),
							'version'     => array(),
							'xmlns:xlink' => array(),
							'id'          => array(),
							'x'           => array(),
							'y'           => array(),
							'style'       => array(),
							'xml:space'   => array(),
						),
						'path'   => array(
							'd'            => array(),
							'fill'         => array(),
							'fill-rule'    => array(),
							'clip-rule'    => array(),
							'xmlns'        => array(),
							'stroke'       => array(),
							'stroke-width' => array(),
						),
						'g'      => array(
							'id'   => array(),
							'fill' => array(),
						),
						'rect'   => array(
							'x'            => array(),
							'y'            => array(),
							'width'        => array(),
							'height'       => array(),
							'rx'           => array(),
							'stroke'       => array(),
							'stroke-width' => array(),
							'fill'         => array(),
							'transform'    => array(),
						),
						'circle' => array(
							'cx'           => array(),
							'cy'           => array(),
							'fill'         => array(),
							'stroke'       => array(),
							'stroke-width' => array(),
							'r'            => array(),
						),
					)
				);
				?>
				<?php
		}
		?>
		<div class='sortable_inner_wrapper arf_sortable_receiver' inner_class='<?php echo esc_attr( $define_classes ); ?>' <?php echo $sortable_inner_field_style; //phpcs:ignore ?>></div>
		<?php

		if ( $define_classes == 'arf_1' || $define_classes == 'arf_1col' || $define_classes == 'arf_2col' || $define_classes == 'arf_3col' || $define_classes == 'arf_4col' || $define_classes == 'arf_5col' || $define_classes == 'arf_6col' ) {
			?>
		</div>
			<?php
			$index_arf_fields++;
		}
	}
}
?>
