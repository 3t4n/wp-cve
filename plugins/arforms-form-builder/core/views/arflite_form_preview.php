<?php

if ( ! function_exists( 'arflite_display_form_preview' ) ) {

	function arflite_display_form_preview( $form_id, $form_key, $posted_data = array() ) {
		global $arflitesettingcontroller,$arflite_forms_loaded, $arflite_preview_form, $arflite_all_preview_fields;

		$arf_form = '';
		if ( ! isset( $posted_data ) || empty( $posted_data ) ) {
			echo esc_html__( 'Please select valid form', 'arforms-form-builder' );
			die();
		}
		@ini_set( 'max_execution_time', 0 );

		global $arfliteform, $user_ID, $arformsmain, $post, $wpdb, $arflitemainhelper, $arfliterecordcontroller, $arfliteformcontroller, $arflitefieldhelper, $arfliterecordhelper, $arflite_forms_loaded, $arflite_form_all_footer_js, $arflitecreatedentry, $ARFLiteMdlDb, $arfliteformhelper;

		$arf_preview_useragent = isset( $_SERVER['HTTP_USER_AGENT'] ) ? sanitize_text_field($_SERVER['HTTP_USER_AGENT']) : '';

		$browser_info = $arfliterecordcontroller->arflitegetBrowser( $arf_preview_useragent );

		$arflite_data_uniq_id = $arf_popup_data_uniq_id = rand( 1, 99999 );

		$form = new stdClass();

		$arf_current_token = $arflitemainhelper->arflite_generate_captcha_code( 10 );

		$form->id          = intval( $posted_data->id );
		$_REQUEST['arfmf'] = intval( $posted_data->id );
		$form->form_key    = $form_key;
		$form->name        = $posted_data->name;
		$form->description = $posted_data->description;
		$form->is_template = 0;
		$form->status      = 'published';
		$arf_temp_fields   = array();

		$options = $new_values = array();

		$new_values['name']        = $posted_data->name;
		$new_values['description'] = $posted_data->description;
		$new_values['status']      = 'published';

		$options = arflite_json_decode( json_encode( $posted_data->options ), true );

		$form_css = arflite_json_decode( json_encode( $posted_data->form_css ), true );

		$options = apply_filters( 'arflite_save_form_options_outside', $options, $posted_data, $form_id );

		$submitbtnid = 'arfsubmit';

		$use_saved = true;

		$form->form_css = maybe_serialize( $form_css );
		
		$submit_value = $arformsmain->arforms_get_settings('submit_value','general_settings');
		$submit_value = !empty( $submit_value ) ? $submit_value : esc_html__('submit','arforms-form-builder');

		if ( is_array( $form->form_css ) ) {
			if ( $form->form_css['arfsubmitbuttontext'] != '' ) {
				$submit = $form->form_css['arfsubmitbuttontext'];
			} else {
				$submit = $submit_value;
			}
		} else {
			$submit = $submit_value;
		}

		$fields = array();

		global $arflite_loaded_fields;
		$arflite_loaded_fields = $arflite_all_preview_fields = array();

		$form->temp_fields = maybe_serialize( $posted_data->temp_fields );

		$options['arf_field_order'] = json_encode( $options['arf_field_order'] );

		$options['arf_field_resize_width'] = json_encode( $options['arf_field_resize_width'] );

		$form->options = $options;

		foreach ( array_merge( $form_css ) as $k => $frm_css ) {
			$new_values[ $k ] = $frm_css;
		}

		if ( isset( $posted_data->fields ) ) {
			$fields = arflite_json_decode( json_encode( $posted_data->fields ) );
		}

		$is_prefix_suffix_enable = false;
		$is_checkbox_img_enable  = 0;
		$is_radio_img_enable     = 0;
		$checkbox_img_field_arr  = array();
		$radio_img_field_arr     = array();

		if ( is_array( $fields ) ) {
			foreach ( $fields as $key => $value ) {
				$value->field_options = arflite_json_decode( json_encode( $value->field_options ), true );
				if ( ! empty( $value->field_options['enable_arf_suffix'] ) || ! empty( $value->field_options['enable_arf_prefix'] ) || ( $value->type == 'phone' && $value->field_options['phonetype'] == 1 ) ) {
					$is_prefix_suffix_enable = true;
				}

				if ( $value->type == 'checkbox' && 1 == $value->field_options['use_image'] ) {
					$is_checkbox_img_enable   = true;
					$checkbox_img_field_arr[] = $value;
				} elseif ( $value->type == 'radio' && 1 == $value->field_options['use_image'] ) {
					$is_radio_img_enable   = true;
					$radio_img_field_arr[] = $value;
				}

				$fields[ $key ] = $value;
			}
		}

		$arflite_all_preview_fields = $fields;

		$values = $arfliterecordhelper->arflite_setup_new_vars( $fields, $form );

		$params = $arfliterecordcontroller->arflite_get_recordparams( $form );

		$arf_form .= $arfliteformcontroller->arflite_get_form_style_for_preview( $form, $posted_data->id, $fields, $arflite_data_uniq_id );

		$form_attr = '';

		$arfssl = ( is_ssl() ) ? 1 : 0;
		$saving = true;

		$arf_form .= "<style type='text/css'>";

		$arfssl          = ( is_ssl() ) ? 1 : 0;
		$arflite_preview = true;
		$inputStyle      = isset( $form->form_css['arfinputstyle'] ) ? $form->form_css['arfinputstyle'] : 'standard';

		$common_css_filename = ARFLITE_FORMPATH . '/core/arflite_css_create_common.php';
		$css_rtl_filename    = ARFLITE_FORMPATH . '/core/arflite_css_create_rtl.php';
		if ( $inputStyle == 'material' ) {
			$filename = ARFLITE_FORMPATH . '/core/arflite_css_create_materialize.php';

			ob_start();

			include $filename;

			include $common_css_filename;

			if ( is_rtl() ) {
				include $css_rtl_filename;
			}
			$css = ob_get_contents();

			$css = str_replace( '##', '#', $css );

			$arf_form .= $css;

			ob_end_clean();
		} else {
			$filename = ARFLITE_FORMPATH . '/core/arflite_css_create_main.php';

			ob_start();

			include $filename;

			include $common_css_filename;

			if ( is_rtl() ) {
				include $css_rtl_filename;
			}
			$css = ob_get_contents();

			$css = str_replace( '##', '#', $css );

			$arf_form .= $css;

				ob_end_clean();
		}

		$arf_form .= '</style>';
		if ( $inputStyle == 'material' ) {
			global $arfliteversion;

		}

		$formRandomID = $form->id . '_' . $arflitemainhelper->arflite_generate_captcha_code( '10' );

		$form_attr .= ' data-random-id="' . esc_attr( $formRandomID ) . '" ';

		$arf_form .= '<div class="arf_form arflite_main_div_' . esc_attr( $form->id ) . ' arf_form_outer_wrapper" id="arffrm_' . esc_attr( $form->id ) . '_container">';

		if ( $form->form_css['arfsuccessmsgposition'] == 'top' ) {
			$success_msg = $arformsmain->arforms_get_settings('success_msg','general_settings');
			$success_msg = !empty( $success_msg ) ? $success_msg : esc_html__('Form is successfully submitted. Thank you!','arforms-form-builder');

			$saved_message = isset( $form->options['success_msg'] ) ? '<div id="arf_message_success"><div class="msg-detail"><div class="msg-description-success">' . $form->options['success_msg'] . '</div></div></div>' : $success_msg;
			$arf_form     .= '<div id="form_success_' . esc_attr( $form->id ) . '" class="display-none-cls">' . $saved_message . '</div>';
		}

		$arf_form .= '<form enctype="' . apply_filters( 'arfliteformenctype', 'multipart/form-data', $form ) . '" method="post" class="arfliteshowmainform arfpreivewform ' . do_action( 'arfliteformclasses', $form ) . ' " data-form-id="form_' . esc_attr( $form->form_key ) . '" novalidate="" data-key-validate="false" data-id="' . esc_attr( $arflite_data_uniq_id ) . '" data-popup-id="' . esc_attr( $arf_popup_data_uniq_id ) . '" "' . $form_attr . '">';

		$loaded_field = isset( $form->options['arf_loaded_field'] ) ? $form->options['arf_loaded_field'] : array();

		$arf_form .= $arfliteformcontroller->arflite_get_form_hidden_field( $form, $fields, $values, true, false, $arflite_data_uniq_id, 'preview', $loaded_field, '', '', $arf_current_token );

		$arf_form .= '<div class="allfields">';
		$totalpass = 0;

		if ( count( array_intersect( array( 'email' ), $loaded_field ) ) ) {

			foreach ( $values['fields'] as $arrkey => $field ) {

				$field['id'] = $arflitefieldhelper->arfliteget_actual_id( $field['id'] );

				if ( $field['type'] == 'email' && $field['confirm_email'] ) {
					if ( isset( $field['confirm_email'] ) && $field['confirm_email'] == 1 && isset( $arf_load_confirm_email['confrim_email_field'] ) && $arf_load_confirm_email['confrim_email_field'] == $field['id'] ) {
						$values['confirm_email_arr'][ $field['id'] ] = isset( $field['confirm_email_field'] ) ? $field['confirm_email_field'] : '';
					} else {
						$arf_load_confirm_email['confrim_email_field'] = isset( $field['confirm_email_field'] ) ? $field['confirm_email_field'] : '';
					}
					$confirm_email_field = $arflitefieldhelper->arflite_get_confirm_email_field( $field );
					$values['fields']    = $arflitefieldhelper->arflitearray_push_after( $values['fields'], array( $confirm_email_field ), $arrkey + $totalpass );
					$totalpass++;
				}
			}
		}

		$inputStyle = isset( $form->form_css['arfinputstyle'] ) ? $form->form_css['arfinputstyle'] : 'standard';
		$form_class = ( $inputStyle == 'material' ) ? 'arf_materialize_form' : 'arf_' . $inputStyle . '_form';

		$arf_form .= '<div class="arf_fieldset ' . esc_attr( $form_class ) . ' " id="arf_fieldset_' . esc_attr( $arflite_data_uniq_id ) . '">';

		if ( isset( $form->options['display_title_form'] ) && $form->options['display_title_form'] == 1 ) {

			$arf_form .= '<div class="arftitlecontainer">';

			if ( isset( $form->name ) && $form->name != '' ) {
				$arf_form .= '<div class="formtitle_style">' . stripslashes( $form->name ) . '</div>';
			}
			if ( isset( $form->description ) && $form->description != '' ) {
				$arf_form .= '<div class="arf_field_description formdescription_style">' . stripslashes( $form->description ) . '</div>';
			}

			$arf_form .= '</div>';
		}

		$i = 1;

		$arf_form .= '<div id="page_0" class="page_break">';

		$arf_form .= $arfliteformcontroller->arflite_get_all_field_html( $form, $values, $arflite_data_uniq_id, $arflite_all_preview_fields, true, array(), $inputStyle );

		$arf_form  = apply_filters( 'arfliteentryform', $arf_form, $form, 'preview', array() );
		$arf_form .= '<div class="arflite-clear-float height1px">&nbsp;</div>';
		$arf_form .= '</div>';

		if ( ! $form->is_template && $form->id != '' ) {

			$arflite_preview = true;
			if ( isset( $arflite_preview ) && $arflite_preview ) {
				global $arflite_style_settings;

				$frm_css_arr = '';
				$frm_css_arr = $form->form_css;

				$arr = maybe_unserialize( $frm_css_arr );

				$newarr = array();
				foreach ( $arr as $k => $v ) {
					$newarr[ $k ] = $v;
				}

				$submit_height       = ( $newarr['arfsubmitbuttonheightsetting'] == '' ) ? '35' : $newarr['arfsubmitbuttonheightsetting'];
				$padding_loading_tmp = $submit_height - 24;
				$padding_loading     = $padding_loading_tmp / 2;

				$submit_width = isset( $newarr['arfsubmitbuttonwidthsetting'] ) ? $newarr['arfsubmitbuttonwidthsetting'] : '';

				$submit_width_loader = ( $submit_width == '' ) ? '1' : $submit_width;
				$width_loader        = ( $submit_width_loader / 2 );
				$width_to_add        = $submit_width_loader;
				$top_margin          = $submit_height + 5;
				$label_margin        = isset( $newarr['width'] ) ? $newarr['width'] : 0;
				$label_margin        = $label_margin + 15;
				$arf_form           .= '<div class="arfsubmitbutton ' . esc_html( $_SESSION['label_position'] ) . '_container" ';
				$arf_form           .= '>';
				$arf_form           .= '<div class="arf_submit_div ' . esc_html( $_SESSION['label_position'] ) . '_container">';

				$arf_form .= '<input type="hidden" value="1" name="is_submit_form_' . esc_attr( $form->id ) . '" data-id="is_submit_form_' . esc_attr( $form->id ) . '" />';
				$arf_form .= '<input type="hidden" value="0" data-val="0" data-max="0" name="submit_form_' . esc_attr( $form->id ) . '" data-id="submit_form_' . esc_attr( $form->id ) . '" />';

				global $arformsmain;
				$submit_value = $arformsmain->arforms_get_settings('submit_value','general_settings');
				$submit_value = !empty( $submit_value ) ? $submit_value : esc_html__('submit','arforms-form-builder');

				if ( is_array( $form->form_css ) ) {
					if ( $form->form_css['arfsubmitbuttontext'] != '' ) {
						$submit = $form->form_css['arfsubmitbuttontext'];
					} else {
						$submit = $submit_value;
					}

					if ( $form->form_css['arfsubmitbuttonstyle'] != '' ) {

						$arfsubmitbuttonstyle = isset( $form->form_css['arfsubmitbuttonstyle'] ) ? sanitize_text_field( $form->form_css['arfsubmitbuttonstyle'] ) : 'border';
					}
				} else {
					$submit = $submit_value;
				}

				$submit = apply_filters( 'arflitegetsubmitbutton', $submit, $form );

				$is_submit_hidden = false;
				$submitbtnstyle   = '';
				$submitbtnclass   = '';

				$arfbrowser_name     = strtolower( str_replace( ' ', '_', $browser_info['name'] ) );
				$submit_btn_content  = '<button class="arf_submit_btn arf_submit_btn_' . esc_attr( $arfsubmitbuttonstyle ) . '  btn btn-info arfstyle-button ' . esc_attr( $submitbtnclass ) . ' ' . esc_attr( $arfbrowser_name ) . '" id="arf_submit_btn_' . esc_attr( $arflite_data_uniq_id ) . '" name="arf_submit_btn_' . esc_attr( $arflite_data_uniq_id ) . '"';
				$submit_btn_content  = apply_filters( 'arflite_add_submit_btn_attributes_outside', $submit_btn_content, $form );
				$submit_btn_content .= ' data-style="zoom-in" ' . esc_attr( $submitbtnstyle ) . '><span class="arfsubmitloader"></span><span class="arfstyle-label">' . esc_attr( $submit ) . '</span>
                <span class="arf_ie_image display-none-cls">';
				if ( $browser_info['name'] == 'Opera' && $browser_info['version'] <= 30 ) {
					$submit_btn_content .= '<img src="' . ARFLITEURL . '/images/submit_btn_image.gif" style="width:24px; box-shadow:none;-webkit-box-shadow:none;-o-box-shadow:none;-moz-box-shadow:none; vertical-align:middle; height:24px; padding-top:' . esc_attr( $padding_loading ) . 'px" />';
				}
				$submit_btn_content .= '</span></button>';

				$arf_form .= $submit_btn_content;

				$arf_form .= '</div><input type="hidden" name="submit_btn_image" id="submit_btn_image" value="' . ARFLITEURL . '/images/submit_loading_img.gif" /></div><div class="arflite-clear-float"></div>';
			} else {

				$arf_form .= '<div class="arfsubmitbutton ' . esc_html( $_SESSION['label_position'] ) . '_container" ';

				$arf_form .= '>';
				$arf_form .= '<div class="arf_submit_div ' . esc_html( $_SESSION['label_position'] ) . '_container">';

				$arf_form .= '<input type="hidden" value="1" name="is_submit_form_' . esc_attr( $form->id ) . '" data-id="is_submit_form_' . esc_attr( $form->id ) . '" />';
				$arf_form .= '<input type="hidden" value="0" data-val="0" data-max="0" name="submit_form_' . esc_attr( $form->id ) . '" data-id="submit_form_' . esc_attr( $form->id ) . '" />';

				$submit           = apply_filters( 'arflitegetsubmitbutton', $submit, $form );
				$is_submit_hidden = false;
				$submitbtnstyle   = '';
				$submitbtnclass   = '';

				$submit_btn_content = '';

				$arfbrowser_name     = strtolower( str_replace( ' ', '_', $browser_info['name'] ) );
				$submit_btn_content .= '<button class="arf_submit_btn arf_submit_btn_' . esc_attr( $arfsubmitbuttonstyle ) . ' btn btn-info arfstyle-button  ' . esc_attr( $submitbtnclass ) . ' ' . esc_attr( $arfbrowser_name ) . '"  id="arf_submit_btn_' . esc_attr( $arflite_data_uniq_id ) . '" name="arf_submit_btn_' . esc_attr( $arflite_data_uniq_id ) . '"';

				$submit_btn_content = apply_filters( 'arflite_add_submit_btn_attributes_outside', $submit_btn_content, $form );

				$submit_btn_content .= ' data-style="zoom-in" ' . esc_attr( $submitbtnstyle ) . ' >';

				$submit_btn_content .= '<span class="arfsubmitloader"></span><span class="arfstyle-label">' . esc_attr( $submit ) . '</span>';
				if ( $browser_info['name'] == 'Opera' && $browser_info['version'] <= 30 ) {
					$padding_loading     = isset( $padding_loading ) ? $padding_loading : '';
					$submit_btn_content .= '<span class="arf_ie_image display-none-cls">';
					$submit_btn_content .= '<img src="' . ARFLITEURL . '/images/submit_btn_image.gif" style="width:24px; box-shadow:none;-webkit-box-shadow:none;-moz-box-shadow:none;-o-box-shadow:none; vertical-align:middle; height:24px; padding-top:' . esc_attr( $padding_loading ) . 'px;"/>';
					$submit_btn_content .= '</span>';
				}

				$submit_btn_content .= '</button>';

				$arf_form .= $submit_btn_content;

				$arf_form .= '</div></div><div class="arflite-clear-float"></div>';
			}
		} else {

			$arf_form .= '<p class="arfsubmitbutton ' . esc_attr( $_SESSION['label_position'] ) . '_container">';
			$submit    = apply_filters( 'arflitegetsubmitbutton', $submit, $form );
			$arf_form .= '<input type="submit" value="' . esc_attr( $submit ) . '" onclick="return false;" ';
			$arf_form  = apply_filters( 'arfliteactionsubmitbutton', $arf_form, $form, 'preview' );
			$arf_form .= '/>';
			$arf_form .= '<div id="submit_loader" class="submit_loader display-none-cls" ></div></p>';
		}

		$i = 1;

		$arf_form .= '</div>';

		$arf_form = apply_filters( 'arflite_additional_form_content_outside', $arf_form, $form, $arflite_data_uniq_id, $arfbrowser_name, $browser_info );

		$arf_form .= '</div>';

		$arf_form .= '</form>';
		$success_msg = $arformsmain->arforms_get_settings('success_msg','general_settings');
		$success_msg = !empty( $success_msg ) ? $success_msg : esc_html__('Form is successfully submitted. Thank you!','arforms-form-builder');
		if ( $form->form_css['arfsuccessmsgposition'] == 'bottom' ) {
			   $saved_message = isset( $form->options['success_msg'] ) ? '<div id="arf_message_success"><div class="msg-detail"><div class="msg-description-success">' . $form->options['success_msg'] . '</div></div></div>' : $success_msg;
			$arf_form        .= '<div id="form_success_' . esc_attr( $form->id ) . '" class="display-none-cls">' . $saved_message . '</div>';
		}
		do_action( 'arflite_afterdisplay_form', $form );
		do_action( 'arflite_afterdisplay_form' . $form->id, $form );
		$arf_form .= '</div>';

		$arf_form .= '<div class=""><input type="hidden" name="form_id" data-id="form_id" value="' . esc_attr( $form->id ) . '" /><input type="hidden" name="arfmainformurl" data-id="arfmainformurl" value="' . ARFLITEURL . '" /></div>';

		$recaptcha_details = $arformsmain->arforms_get_settings( ['pubkey', 're_theme', 're_lang'],'general_settings');

		extract( $recaptcha_details );

		$arf_form .= "<input type='hidden' id='arf_settings_recaptcha_v2_public_key' value='" . esc_attr( $pubkey ) . "' />";
		$arf_form .= "<input type='hidden' id='arf_settings_recaptcha_v2_public_theme' value='" . esc_attr( $re_theme ) . "' />";
		$arf_form .= "<input type='hidden' id='arf_settings_recaptcha_v2_public_lang' value='" . esc_attr( $re_lang ) . "' />";

		if ( $form->options['tooltip_loaded'] ) {
			$arf_tootip_width     = ( isset( $form->form_css['arf_tooltip_width'] ) && $form->form_css['arf_tooltip_width'] != '' ) ? $form->form_css['arf_tooltip_width'] : 'auto';
			$arf_tooltip_position = ( isset( $form->form_css['arf_tooltip_position'] ) && $form->form_css['arf_tooltip_position'] != '' ) ? $form->form_css['arf_tooltip_position'] : 'top';

			$arftooltipposition = ( isset( $form->form_css['arftooltipposition'] ) && $form->form_css['arftooltipposition'] != '' ) ? $form->form_css['arftooltipposition'] : 'top';

			$arflite_form_all_footer_js .= '
                if ( typeof jQuery().tipso == "function") {
                  jQuery(".arflite_main_div_' . $form->id . '").find(".arfhelptip").each(function () {
                        jQuery(this).tipso("destroy");
                        var arf_data_title = jQuery(this).attr("data-title");
                        jQuery(this).tipso({
                            position: "' . $arftooltipposition . '",
                            width: "' . $arf_tootip_width . '",
                            useTitle: false,
                            content: arf_data_title,
                            background: "' . str_replace( '##', '#', $form->form_css['arf_tooltip_bg_color'] ) . '",
                            color:"' . str_replace( '##', '#', $form->form_css['arf_tooltip_font_color'] ) . '",
                            tooltipHover: true
                        });
                    });

                    jQuery(".arflite_main_div_' . $form->id . ' .arf_materialize_form .arfhelptipfocus input,.arflite_main_div_' . $form->id . ' .arf_materialize_form .arfhelptipfocus textarea").on( "focus", function(e){
                        jQuery(this).parent().parent().each(function () {

                            var arf_data_title = jQuery(this).attr("data-title");
                            if(jQuery(this).find("input").hasClass("arf_phone_utils")){
                            	arf_data_title = jQuery(this).parent().attr("data-title");
                            }
                            if(arf_data_title!=null && arf_data_title!=undefined)
                            {
                            	jQuery(this).tipso("hide");
                                jQuery(this).tipso("destroy");
                                var arftooltip = jQuery(this).tipso({
                                    position: "' . $arftooltipposition . '",
                                    width: "' . $arf_tootip_width . '",
                                    useTitle: false,
                                    content: arf_data_title,
                                    background: "' . str_replace( '##', '#', $form->form_css['arf_tooltip_bg_color'] ) . '",
                                    color:"' . str_replace( '##', '#', $form->form_css['arf_tooltip_font_color'] ) . '",
                                    tooltipHover: true,
                                });
                                jQuery(this).tipso("show");
                                arftooltip.off("mouseover.tipso");
                                arftooltip.off("mouseout.tipso");
                            }
                        });
                    });

                    jQuery(document).on("focusout", ".arflite_main_div_' . $form->id . ' .arf_materialize_form .arfhelptipfocus input,.arflite_main_div_' . $form->id . ' .arf_materialize_form .arfhelptipfocus textarea", function(e){
                        jQuery(this).parent().parent().each(function () {
                            var arf_data_title = jQuery(this).attr("data-title");
                            if(jQuery(this).find("input").hasClass("arf_phone_utils")){
                            	arf_data_title = jQuery(this).parent().attr("data-title");
                            }
                            if(arf_data_title!=null && arf_data_title!=undefined)
                            {
                                jQuery(this).tipso("hide");
                                jQuery(this).tipso("destroy");
                            }
                        });

                    });
                }
                ';
		}

		/* if checkbox or radio field loaded start */

		if ( in_array( 'radio', $loaded_field ) || in_array( 'checkbox', $loaded_field ) ) {

			$form_css_submit     = $form->form_css;
			$checkradio_property = '';
			if ( $form_css_submit['arfcheckradiostyle'] != '' ) {

				if ( $form_css_submit['arfcheckradiostyle'] != 'none' ) {
					if ( $form_css_submit['arfcheckradiocolor'] != 'default' && $form_css_submit['arfcheckradiocolor'] != '' ) {
						if ( $form_css_submit['arfcheckradiostyle'] == 'custom' || $form_css_submit['arfcheckradiostyle'] == 'futurico' || $form_css_submit['arfcheckradiostyle'] == 'polaris' ) {
							$checkradio_property = $form_css_submit['arfcheckradiostyle'];
						} else {
							$checkradio_property = $form_css_submit['arfcheckradiostyle'] . '-' . $form_css_submit['arfcheckradiocolor'];
						}
					} else {
						$checkradio_property = $form_css_submit['arfcheckradiostyle'];
					}
				} else {
					$checkradio_property = '';
				}
			}

			$checked_checkbox_property = '';
			if ( $form_css_submit['arf_checked_checkbox_icon'] != '' ) {
				$checked_checkbox_property = ' arfalite ' . $form_css_submit['arf_checked_checkbox_icon'];
			} else {
				$checked_checkbox_property = '';
			}
			$checked_radio_property = '';
			if ( $form_css_submit['arf_checked_radio_icon'] != '' ) {
				$checked_radio_property = ' arfalite ' . $form_css_submit['arf_checked_radio_icon'];
			} else {
				$checked_radio_property = '';
			}
		}
		/* if checkbox or radio field loaded end */

		$arflite_form_all_footer_js .= "var __ARFMAINURL='" . ARFLITESCRIPTURL . "';\n";

		$arflite_form_all_footer_js .= "var __ARFERR='" . addslashes( __( 'Sorry, this file type is not permitted for security reasons.', 'arforms-form-builder' ) ) . "';\n";

		$arflite_form_all_footer_js .= "var __ARFAJAXURL='" . admin_url( 'admin-ajax.php' ) . "';\n";

		$arflite_form_all_footer_js .= "var __ARFSTRRNTH_INDICATOR='" . addslashes( __( 'Strength indicator', 'arforms-form-builder' ) ) . "';\n";

		$arflite_form_all_footer_js .= "var __ARFSTRRNTH_SHORT='" . addslashes( __( 'Short', 'arforms-form-builder' ) ) . "';\n";

		$arflite_form_all_footer_js .= "var __ARFSTRRNTH_BAD='" . addslashes( __( 'Bad', 'arforms-form-builder' ) ) . "';\n";

		$arflite_form_all_footer_js .= "var __ARFSTRRNTH_GOOD='" . addslashes( __( 'Good', 'arforms-form-builder' ) ) . "';\n";

		$arflite_form_all_footer_js .= "var __ARFSTRRNTH_STRONG='" . addslashes( __( 'Strong', 'arforms-form-builder' ) ) . "';\n";

		$arflite_form_all_footer_js .= 'jQuery("#arffrm_' . $form->id . '_container").find("form").find(".arfformfield").each(function () {
                    var data_view = jQuery(this).attr("data-view");
                    if (data_view == "arf_disable") {
                        var data_type = jQuery(this).attr("data-type");
                        arf_field_disable(jQuery(this), data_type);
                    }
                });';

		$arf_form              .= '</div>';
		$arflite_forms_loaded[] = $form;
		$arflite_preview_form   = $form;
		return $arf_form;
	}
}
