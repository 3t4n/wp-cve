<?php
global $arflitemaincontroller, $arfliterecordcontroller, $arfliteversion, $arflite_is_arf_preview, $arflitesettingcontroller, $arfliteformcontroller, $wpdb,$arflitemainhelper,$arflitefieldhelper,$arfliterecordhelper;

$arflite_is_arf_preview = 1;

function arflite_my_function_admin_bar() {
	return false;
}
if ( ! isset( $form ) ) {
	$form = new stdClass();
}

if ( ! isset( $form->id ) ) {
	$form->id = $arflitemainhelper->arflite_get_param( 'form_id' );
}
$arflite_data_uniq_id = rand( 1, 99999 );
if ( empty( $arflite_data_uniq_id ) || $arflite_data_uniq_id == '' ) {
	$arflite_data_uniq_id = $form->id;
}

add_filter( 'show_admin_bar', 'arflite_my_function_admin_bar' );

remove_action( 'wp_head', 'wc_products_rss_feed' );
remove_action( 'wp_head', 'wc_generator_tag' );
remove_action( 'get_the_generator_html', 'wc_generator_tag' );
remove_action( 'get_the_generator_xhtml', 'wc_generator_tag' );
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
	<head>


		<meta charset="<?php bloginfo( 'charset' ); ?>" />

		<meta http-equiv="pragma" content="no-cache">
		<meta http-equiv="Cache-control" content="no-cache,no-store,must-revalidate">
		<meta http-equiv="expires" content="0">
		<title><?php bloginfo( 'name' ); ?></title>
		<?php
		global $wp_version,$arflite_version;

		do_action( 'wp_enqueue_scripts' );

		wp_print_scripts( 'jquery' );
		wp_print_scripts( 'jquery-ui-core' );
		wp_print_scripts( 'jquery-ui-draggable' );
		wp_print_scripts( 'wp-hooks' );

		wp_register_script( 'arflite-preview-js', ARFLITEURL . '/js/arflite_preview.js', array(), $arflite_version );
		wp_print_scripts( 'arflite-preview-js' );

		?>
		<?php $arflitemaincontroller->arflite_front_head(); ?>

		<style type="text/css" id='arf_form_<?php echo esc_attr( $form->id ); ?>'>
		<?php
		$form->form_css = isset( $form->form_css ) ? maybe_unserialize( $form->form_css ) : '';
		$loaded_field   = isset( $form->options['arf_loaded_field'] ) ? $form->options['arf_loaded_field'] : array();

		global $arformsmain;
        $arf_global_css = !empty( get_option('arf_global_css') ) ? stripslashes_deep(get_option('arf_global_css')) : $arformsmain->arforms_get_settings('arf_global_css','general_settings');
        echo $arf_global_css; //phpcs:ignore
		
		$fields                    = $arflitefieldhelper->arflite_get_form_fields_tmp( false, $form->id, false, 0 );
		$values                    = $arfliterecordhelper->arflite_setup_new_vars( $fields, $form );
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

				if ( isset( $form->options[ $custom_css_block_form ] ) && $form->options[ $custom_css_block_form ] != '' ) {

					$form->options[ $custom_css_block_form ] = $arfliteformcontroller->arflitebr2nl( $form->options[ $custom_css_block_form ] );

					if ( $custom_css_block_form == 'arf_form_outer_wrapper' ) {
						$arf_form_outer_wrapper_array = explode( '|', $custom_css_classes_form );

						foreach ( $arf_form_outer_wrapper_array as $arf_form_outer_wrapper1 ) {
							if ( $arf_form_outer_wrapper1 == '.arf_form_outer_wrapper' ) {
								echo '.arflite_main_div_' . intval($form->id) . '.arf_form_outer_wrapper { ' . $form->options[ $custom_css_block_form ] . ' } '; //phpcs:ignore
							}
							if ( $arf_form_outer_wrapper1 == '.arfmodal' ) {
								echo '#popup-form-' . intval($form->id) . '.arfmodal{ ' . $form->options[ $custom_css_block_form ] . ' } '; //phpcs:ignore
							}
						}
					} elseif ( $custom_css_block_form == 'arf_form_inner_wrapper' ) {
						$arf_form_inner_wrapper_array = explode( '|', $custom_css_classes_form );
						foreach ( $arf_form_inner_wrapper_array as $arf_form_inner_wrapper1 ) {
							if ( $arf_form_inner_wrapper1 == '.arf_fieldset' ) {
								echo '.arflite_main_div_' . intval($form->id) . ' ' . $arf_form_inner_wrapper1 . ' { ' . $form->options[ $custom_css_block_form ] . ' } '; //phpcs:ignore
							}
							if ( $arf_form_inner_wrapper1 == '.arfmodal' ) {
								echo '.arfmodal .arfmodal-body .arflite_main_div_' . intval($form->id) . ' .arf_fieldset { ' . $form->options[ $custom_css_block_form ] . ' } '; //phpcs:ignore
							}
						}
					} elseif ( $custom_css_block_form == 'arf_form_error_message' ) {
						$arf_form_error_message_array = explode( '|', $custom_css_classes_form );

						foreach ( $arf_form_error_message_array as $arf_form_error_message1 ) {
							echo '.arflite_main_div_' . intval($form->id) . ' ' . $arf_form_error_message1 . ' { ' . $form->options[ $custom_css_block_form ] . ' } '; //phpcs:ignore
						}
					} else {
						echo '.arflite_main_div_' . intval($form->id) . ' ' . $custom_css_classes_form . ' { ' . $form->options[ $custom_css_block_form ] . ' } '; //phpcs:ignore
					}
				}
			}
			foreach ( $values['fields'] as $field ) {

				$field['id'] = $arflitefieldhelper->arfliteget_actual_id( $field['id'] );

				if ( $field['type'] == 'select' ) {
					if ( $field['size'] != 1 ) {
						if ( isset( $newarr ) && $newarr['auto_width'] != '1' ) {

							if ( isset( $field['field_width'] ) && $field['field_width'] != '' ) {

								echo '.arflite_main_div_' . esc_attr($field['form_id']) . ' .select_controll_' . esc_attr($field['id']) . ':not([class*="span"]):not([class*="col-"]):not([class*="form-control"]){width:' . esc_attr($field['field_width']) . 'px !important;}';
							}
						}
					}
				} elseif ( $field['type'] == 'time' ) {
					if ( isset( $field['field_width'] ) && $field['field_width'] != '' ) {
						echo '.arflite_main_div_' . esc_attr($field['form_id']) . ' .time_controll_' . esc_attr($field['id']) . ':not([class*="span"]):not([class*="col-"]):not([class*="form-control"]){width:' . esc_attr($field['field_width']) . 'px !important;}';
					}
				}

				if ( isset( $field['field_width'] ) && $field['field_width'] != '' ) {
					echo ' .arflite_main_div_' . esc_attr($form->id) . ' #arf_field_' . esc_attr($field['id']) . '_' . esc_attr($arflite_data_uniq_id) . '_container .help-block { width: ' . esc_attr($field['field_width']) . 'px; } ';
				}

				$custom_css_array = array(
					'css_outer_wrapper' => '.arf_form_outer_wrapper',
					'css_label'         => '.css_label',
					'css_input_element' => '.css_input_element',
					'css_description'   => '.arf_field_description',
				);

				if ( in_array( $field['type'], array( 'text', 'email', 'date', 'time', 'number', 'image', 'url', 'phone', 'number' ) ) ) {
					$custom_css_array['css_add_icon'] = '.arf_prefix, .arf_suffix';
				}

				foreach ( $custom_css_array as $custom_css_block => $custom_css_classes ) {
					if ( isset( $field[ $custom_css_block ] ) && $field[ $custom_css_block ] != '' ) {

						$field[ $custom_css_block ] = $arfliteformcontroller->arflitebr2nl( $field[ $custom_css_block ] );

						if ( $custom_css_block == 'css_outer_wrapper' ) {
							echo ' .arflite_main_div_' . esc_attr($form->id) . ' #arf_field_' . esc_attr($field['id']) . '_' . esc_attr($arflite_data_uniq_id) . '_container { ' . esc_attr($field[ $custom_css_block ]) . ' } ';
						} elseif ( $custom_css_block == 'css_outer_wrapper' ) {
							echo ' .arflite_main_div_' . esc_attr($form->id) . ' #heading_' . esc_attr($field['id']) . ' { ' . esc_attr($field[ $custom_css_block ]) . ' } ';
						} elseif ( $custom_css_block == 'css_label' ) {
							echo ' .arflite_main_div_' . esc_attr($form->id) . ' #arf_field_' . esc_attr($field['id']) . '_' . esc_attr($arflite_data_uniq_id) . '_container label.arf_main_label { ' . esc_attr($field[ $custom_css_block ]) . ' } ';
						} elseif ( $custom_css_block == 'css_label' ) {
							echo ' .arflite_main_div_' . esc_attr($form->id) . ' #heading_' . esc_attr($field['id']) . ' h2.arf_sec_heading_field { ' . esc_attr($field[ $custom_css_block ]) . ' } ';
						} elseif ( $custom_css_block == 'css_input_element' ) {

							if ( $field['type'] == 'textarea' ) {
								echo ' .arflite_main_div_' . esc_attr($form->id) . '  #arf_field_' . esc_attr($field['id']) . '_' . esc_attr($arflite_data_uniq_id) . '_container .controls textarea { ' . esc_attr($field[ $custom_css_block ]) . ' } ';
							} elseif ( $field['type'] == 'select' ) {
								echo ' .arflite_main_div_' . esc_attr($form->id) . '  #arf_field_' . esc_attr($field['id']) . '_' . esc_attr($arflite_data_uniq_id) . '_container .controls select { ' . esc_attr($field[ $custom_css_block ]) . ' } ';
								echo ' .arflite_main_div_' . esc_attr($form->id) . '  #arf_field_' . esc_attr($field['id']) . '_' . esc_attr($arflite_data_uniq_id) . '_container .controls .arfbtn.dropdown-toggle { ' . esc_attr($field[ $custom_css_block ]) . ' } ';
							} elseif ( $field['type'] == 'radio' ) {
								echo ' .arflite_main_div_' . esc_attr($form->id) . '  #arf_field_' . esc_attr($field['id']) . '_' . esc_attr($arflite_data_uniq_id) . '_container .arf_radiobutton label { ' . esc_attr($field[ $custom_css_block ]) . ' } ';
							} elseif ( $field['type'] == 'checkbox' ) {
								echo ' .arflite_main_div_' . esc_attr($form->id) . '  #arf_field_' . esc_attr($field['id']) . '_' . esc_attr($arflite_data_uniq_id) . '_container .arf_checkbox_style label { ' . esc_attr($field[ $custom_css_block ]) . ' } ';
							} else {
								echo ' .arflite_main_div_' . esc_attr($form->id) . '  #arf_field_' . esc_attr($field['id']) . '_' . esc_attr($arflite_data_uniq_id) . '_container .controls input { ' . esc_attr($field[ $custom_css_block ]) . ' } ';
								if ( $field['type'] == 'email' ) {
									echo '.arflite_main_div_' . esc_attr($form->id) . ' #arf_field_' . esc_attr($field['id']) . '_' . esc_attr($arflite_data_uniq_id) . '_container + .confirm_email_container .controls input {' . esc_attr($field[ $custom_css_block ]) . '}';
									echo ' .arflite_main_div_' . esc_attr($form->id) . ' #arf_field_' . esc_attr($field['id']) . '_' . esc_attr($arflite_data_uniq_id) . '_container + .confirm_email_container .arf_prefix_suffix_wrapper{ ' . esc_attr($field[ $custom_css_block ]) . ' }';
								}

								echo ' .arflite_main_div_' . esc_attr($form->id) . '  #arf_field_' . esc_attr($field['id']) . '_' . esc_attr($arflite_data_uniq_id) . '_container .arf_prefix_suffix_wrapper { ' . esc_attr($field[ $custom_css_block ]) . ' } ';
							}
						} elseif ( $custom_css_block == 'css_description' ) {
							echo ' .arflite_main_div_' . esc_attr($form->id) . '  #arf_field_' . esc_attr($field['id']) . '_' . esc_attr($arflite_data_uniq_id) . '_container .arf_field_description { ' . esc_attr($field[ $custom_css_block ]) . ' } ';
						} elseif ( $custom_css_block == 'css_description' ) {
							echo ' .arflite_main_div_' . esc_attr($form->id) . '  #heading_' . esc_attr($field['id']) . ' .arf_heading_description { ' . esc_attr($field[ $custom_css_block ]) . ' } ';
						} elseif ( $custom_css_block == 'css_add_icon' ) {
							echo '.arflite_main_div_' . esc_attr($form->id) . ' #arf_field_' . esc_attr($field['id']) . '_' . esc_attr($arflite_data_uniq_id) . '_container .arf_prefix,
                            .arflite_main_div_' . esc_attr($form->id) . ' #arf_field_' . esc_attr($field['id']) . '_' . esc_attr($arflite_data_uniq_id) . '_container .arf_suffix { ' . esc_attr($field[ $custom_css_block ]) . ' } ';
							if ( $field['type'] == 'email' ) {
								echo '.arflite_main_div_' . esc_attr($form->id) . ' .arf_confirm_email_field_' . esc_attr($field['id']) . ' .arf_prefix,
                                .arflite_main_div_' . esc_attr($form->id) . ' .arf_confirm_email_field_' . esc_attr($field['id']) . ' .arf_suffix {' . esc_attr($field[ $custom_css_block ]) . ' } ';
							}
						}

						do_action( 'arflite_add_css_from_outside', $field, $custom_css_block, $arflite_data_uniq_id );
					}
				}
			}
			?>
		</style>
		<?php
		wp_print_styles( 'arformslite_selectpicker' );


		wp_print_styles( 'arflitedisplaycss' );

		wp_print_scripts( 'jqbootstrapvalidation' );
		wp_print_scripts( 'arformslite_selectpicker' );
		wp_print_scripts( 'jquery-animatenumber' );
		wp_print_scripts( 'bootstrap-inputmask' );
		wp_print_scripts( 'intltelinput' );
		wp_print_scripts( 'arformslite_phone_utils' );
		wp_print_scripts( 'jquery-maskedinput' );
		wp_print_styles( 'flag_icon' );

		wp_print_scripts( 'bootstrap' );

		wp_print_styles( 'tipso' );
		wp_print_scripts( 'tipso' );

		do_action( 'arflite_include_outside_js_css_for_preview_header' );

		?>
	</head>
	<body id="arf_preview_body" class="arf_preview_modal_body <?php echo ( is_rtl() ) ? 'arf_preview_rtl' : ''; ?>" >
		<?php
		global $wpdb, $ARFLiteMdlDb, $tbl_arf_forms;

		if ( isset( $form->id ) && ! isset( $_REQUEST['form_id'] ) ) {
			$res = $wpdb->get_results( $wpdb->prepare( 'SELECT options FROM ' . $tbl_arf_forms . ' WHERE id = %d', $form->id ), 'ARRAY_A' ); //phpcs:ignore
		}
		if ( isset( $res ) ) {
			$res = $res[0];
		}

		$res['options'] = isset( $res['options'] ) ? $res['options'] : '';

		$values = ( $res['options'] != '' ) ? maybe_unserialize( $res['options'] ) : array();

		$form_style_css = maybe_unserialize( $form->form_css );

		$form_style_css = $arfliteformcontroller->arfliteObjtoArray( $form_style_css );

		$loaded_field = isset( $form->options['arf_loaded_field'] ) ? $form->options['arf_loaded_field'] : array();

		$values['display_title_form'] = isset( $values['display_title_form'] ) ? $values['display_title_form'] : '';
		if ( $values['display_title_form'] == '0' && $new == 'list' ) {
			$is_title       = false;
			$description = false;
		} else {
			$is_title       = true;
			$description = true;
		}
		$checkradio_property = '';
		if ( isset( $_REQUEST['checkradiostyle'] ) && $_REQUEST['checkradiostyle'] != '' ) {
			if ( isset( $_REQUEST['checkradiostyle'] ) && $_REQUEST['checkradiostyle'] != 'none' ) {
				if ( isset( $_REQUEST['checkradiocolor'] ) && $_REQUEST['checkradiocolor'] != 'default' && $_REQUEST['checkradiocolor'] != '' ) {
					if ( isset( $_REQUEST['checkradiostyle'] ) && $_REQUEST['checkradiostyle'] == 'custom' || $_REQUEST['checkradiostyle'] == 'futurico' || $_REQUEST['checkradiostyle'] == 'polaris' ) {
						$checkradio_property = isset( $_REQUEST['checkradiostyle'] ) ? sanitize_text_field( $_REQUEST['checkradiostyle'] ) : '';
					} else {
						$arf_checkradio      = isset( $_REQUEST['checkradiostyle'] ) ? sanitize_text_field( $_REQUEST['checkradiostyle'] ) : '';
						$checkradio_property = $arf_checkradio . '-' . sanitize_text_field( $_REQUEST['checkradiocolor'] );
					}
				} else {
					$checkradio_property = isset( $_REQUEST['checkradiostyle'] ) ? sanitize_text_field( $_REQUEST['checkradiostyle'] ) : '';
				}
			} else {
				$checkradio_property = '';
			}
		} else {
			if ( isset( $form_style_css['arfcheckradiostyle'] ) && $form_style_css['arfcheckradiostyle'] != '' ) {
				if ( $form_style_css['arfcheckradiostyle'] != 'none' ) {
					if ( $form_style_css['arfcheckradiocolor'] != 'default' && $form_style_css['arfcheckradiocolor'] != '' ) {
						$form_css_submit['arfcheckradiostyle'] = isset( $form_css_submit['arfcheckradiostyle'] ) ? $form_css_submit['arfcheckradiostyle'] : array();
						if ( ( isset( $form_css_submit['arfcheckradiostyle'] ) && $form_css_submit['arfcheckradiostyle'] == 'custom' ) || $form_style_css['arfcheckradiostyle'] == 'futurico' || $form_style_css['arfcheckradiostyle'] == 'polaris' ) {
							$checkradio_property = $form_style_css['arfcheckradiostyle'];
						} else {
							$checkradio_property = $form_style_css['arfcheckradiostyle'] . '-' . $form_style_css['arfcheckradiocolor'];
						}
					} else {
						$checkradio_property = $form_style_css['arfcheckradiostyle'];
					}
				} else {
					$checkradio_property = '';
				}
			}
		}
		?>

		<div id="arfdevicebody" class="arfdevicecomputer arflite_preview_devicebody" align="center">
			<?php
			require_once ARFLITE_VIEWS_PATH . '/arflite_form_preview.php';
			$opt_id       = isset( $_REQUEST['arf_opt_id'] ) ? sanitize_text_field( $_REQUEST['arf_opt_id'] ) : '';
			$home_preview = isset( $_REQUEST['arf_is_home'] ) ? sanitize_text_field( $_REQUEST['arf_is_home'] ) : '';

			if ( $opt_id != '' ) {
				$saved_preview_data = get_option( $opt_id );

				$posted_data = json_decode( stripslashes_deep( $saved_preview_data ) );
				if ( $form->id != $posted_data->id ) {
					$form->id = $posted_data->id;
				}
				$contents = arflite_display_form_preview( $form->id, $key, $posted_data );
				$contents = apply_filters( 'arflite_pre_display_arfomrms', $contents, $form->id, $key );

				echo $contents; //phpcs:ignore
			} elseif ( $home_preview == true ) {
				$form_id = isset( $_REQUEST['form_id'] ) ? intval( $_REQUEST['form_id'] ) : '';
				require_once ARFLITE_VIEWS_PATH . '/arflite_front_form.php';

				$contents = arflite_get_form_builder_string( $form_id, $key, true, false, '', $arflite_data_uniq_id );

				echo $contents = apply_filters( 'arflite_pre_display_arfomrms', $contents, $form_id, $key ); //phpcs:ignore
			} else {
				echo esc_html__( 'Please select valid forms', 'arforms-form-builder' );
			}
			?>
		</div>
		<?php
		global $arflite_loaded_fields, $arflite_preview_form, $arfliteversion;
		if ( empty( $form->options ) && ! empty( $posted_data->options ) ) {
			$form->options = $arfliteformcontroller->arfliteObjtoArray( $posted_data->options );
		}
		$loaded_field = isset( $form->options['arf_loaded_field'] ) ? $form->options['arf_loaded_field'] : array();

		wp_print_scripts( 'jquery-effects-slide' );
		if ( ( isset( $form->options['arf_number_animation'] ) && $form->options['arf_number_animation'] ) || isset( $arflite_preview_form->options['arf_number_animation'] ) && $arflite_preview_form->options['arf_number_animation'] ) {
			wp_print_scripts( 'jquery-animatenumber' );
		}
		if ( ( isset( $form->options['font_awesome_loaded'] ) && $form->options['font_awesome_loaded'] ) || ( isset( $arflite_preview_form->options['font_awesome_loaded'] ) && $arflite_preview_form->options['font_awesome_loaded'] ) ) {
			wp_print_styles( 'arflite-font-awesome' );
		}

		if ( ( isset( $form->options['tooltip_loaded'] ) && $form->options['tooltip_loaded'] ) || isset( $arflite_preview_form->options['tooltip_loaded'] ) && $arflite_preview_form->options['tooltip_loaded'] ) {
			wp_print_styles( 'tipso' );
			wp_print_scripts( 'tipso' );
		}

		if ( ( isset( $form->options['arf_input_mask'] ) && $form->options['arf_input_mask'] ) || isset( $arflite_preview_form->options['arf_input_mask'] ) && $arflite_preview_form->options['arf_input_mask'] ) {
			wp_print_scripts( 'jquery-maskedinput' );
			wp_print_scripts( 'bootstrap-inputmask' );
		}


		if ( ( isset( $loaded_field ) && ( in_array( 'time', $loaded_field ) || in_array( 'date', $loaded_field ) ) ) || ( isset( $arflite_loaded_fields ) && ( in_array( 'time', $arflite_loaded_fields ) || in_array( 'date', $arflite_loaded_fields ) ) ) ) {

			if ( ! isset( $date_picker_theme ) || $date_picker_theme == '' ) {
				$date_picker_theme = 'default_theme';
			}

			wp_print_scripts( 'bootstrap-moment-with-locales' );
			wp_print_styles( 'bootstrap-datetimepicker' );
			wp_print_scripts( 'bootstrap-datetimepicker' );
		}


		wp_print_styles( 'form_custom_css-default_theme' );

		wp_print_scripts( 'recaptcha-ajax' );
		wp_print_styles( 'arfliterecaptchacss' );
		$arflite_preview = true;

		$arfliterecordcontroller->arflite_footer_js( true, false );
		do_action( 'arflite_include_outside_js_css_for_preview_footer' );

		$arflite_is_arf_preview = 0;
		?>
	</body>
</html>
