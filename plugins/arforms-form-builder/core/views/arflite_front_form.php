<?php

if ( ! function_exists( 'arflite_get_form_builder_string' ) ) {

	function arflite_get_form_builder_string( $id, $key = '', $arflite_preview = 0, $is_widget_or_modal = 0, $arflite_errors = array(), $arflite_data_uniq_id = '', $desc = '', $type = '', $modal_height = '', $modal_width = '', $position = '', $btn_angle = '', $bgcolor = '', $txtcolor = '', $open_inactivity = '', $open_scroll = '', $open_delay = '', $overlay = '', $is_close_link = '', $modal_bgcolor = '', $is_fullscrn = '', $inactive_min = '', $model_effect = '', $navigation = false, $arf_preset_data = '', $hide_popup_for_loggedin_user = 'no' ) {
		@ini_set( 'max_execution_time', 0 );

		$home_preview = false;
		if ( isset( $_REQUEST['arf_is_home'] ) ) {
			$home_preview = sanitize_text_field( $_REQUEST['arf_is_home'] );
		}

		global $arfliteform, $user_ID, $post, $wpdb, $arflitemainhelper, $arfliterecordcontroller, $arfliteformcontroller, $arflitefieldhelper, $arfliterecordhelper, $arflite_forms_loaded, $arflite_form_all_footer_js, $arflitecreatedentry, $ARFLiteMdlDb,$arflite_func_val,$arflite_decimal_separator,$arfmessage_rest,$arflitemaincontroller, $arflite_glb_preset_data, $is_gutenberg, $tbl_arf_forms;

		$arflitemaincontroller->arflite_start_session( true );

		$arf_current_token = $arflitemainhelper->arflite_generate_captcha_code( 10 );

		$arf_form = '';

		$arf_popup_data_uniq_id = $arflite_data_uniq_id;

		$arflite_front_form_useragent = isset( $_SERVER['HTTP_USER_AGENT'] ) ? sanitize_text_field($_SERVER['HTTP_USER_AGENT']) : '';

		$browser_info = $arfliterecordcontroller->arflitegetBrowser( $arflite_front_form_useragent );
		if ( $id ) {
			$form = $arfliteform->arflitegetOne( (int) $id );
		} elseif ( $key ) {
			$form = $arfliteform->arflitegetOne( $key );
		}

		$form = apply_filters( 'arflitepredisplayform', $form );

		if ( ( is_object( $form ) && ( isset( $form->is_template ) && ( ! isset( $form->status ) || $form->status == 'draft' ) ) ) && ! ( $arflite_preview ) ) {
			$arf_form .= __( 'Please select a valid form', 'arforms-form-builder' );
			return $arf_form;
		} elseif ( ! $form || ( ( $form->is_template || $form->status == 'draft' ) ) ) {
			$arf_form .= __( 'Please select a valid form', 'arforms-form-builder' );
			return $arf_form;
		} elseif ( 'yes' == $hide_popup_for_loggedin_user && is_user_logged_in() ) {
			return $arf_form;
		} elseif ( isset( $form->is_loggedin ) && ( ( $form->is_loggedin == 1 && ! $user_ID ) || ( $form->is_loggedin == 2 && $user_ID ) ) ) {
			//need to check this 
			global $arformsmain;
			$login_msg = $arformsmain->arforms_get_settings('login_msg','general_settings');
			$login_msg = !empty( $login_msg ) ? $login_msg : ''; 
			return do_shortcode( $login_msg );
		}
			$arflite_forms_loaded[] = $form;

			$arflite_func_val = apply_filters( 'arflite_hide_forms', $arfliteformcontroller->arflite_class_to_hide_form( $id ), $id );

		if ( $arflite_func_val != '' && isset( $_POST[ 'is_submit_form_' . $id ] ) ) { //phpcs:ignore
			if ( ! isset( $arflite_func_val['hide_forms'] ) ) {
				return $arflite_func_val;
			}
		} elseif ( $arflite_func_val != '' && ! $navigation ) {
			$error_restrict_entry = json_decode( $arflite_func_val );
			return $error_restrict_entry->message;
		}

		$form_css_submit = $form->form_css = maybe_unserialize( $form->form_css );

		global $arformsmain;
		$submit_value = $arformsmain->arforms_get_settings('submit_value','general_settings');
		$submit_value = !empty( $submit_value ) ? $submit_value : esc_html__('submit','arforms-form-builder');

		if ( is_array( $form->form_css ) ) {
			$form_css_submit = $form->form_css;
			if ( $form->form_css['arfsubmitbuttontext'] != '' ) {
				$submit = $form->form_css['arfsubmitbuttontext'];
			} else {
				$submit = $submit_value;
			}
		} else {
			$submit = $submit_value;
		}

		$fields = wp_cache_get( 'arflite_form_fields_' . $form->id );
		if ( false == $fields ) {
			$fields = $arflitefieldhelper->arflite_get_form_fields_tmp( false, $form->id, false, 0 );
			wp_cache_set( 'arflite_form_fields_' . $form->id, $fields );
		}

			$values = $arfliterecordhelper->arflite_setup_new_vars( $fields, $form );

			$params = $arfliterecordcontroller->arflite_get_recordparams( $form );

			$form_options = isset( $form->options ) ? maybe_unserialize( $form->options ) : '';

			$success_msg = $arformsmain->arforms_get_settings('success_msg','general_settings');
			$success_msg = !empty( $success_msg ) ? $success_msg : esc_html__('Form is successfully submitted. Thank you!','arforms-form-builder');

			$saved_message = isset( $form_options['success_msg'] ) ? '<div id="arf_message_success"><div class="msg-detail"><div class="msg-description-success">' . $form_options['success_msg'] . '</div></div></div>' : $success_msg;

			$saved_popup_message = isset( $form_options['success_msg'] ) ? '<div id="arf_message_success_popup" style="display: none;"><div class="msg-detail"><div class="msg-description-success">' . $form_options['success_msg'] . '</div></div></div>' : '<div id="arf_message_success_popup" style="display: none;" ><div class="msg-detail"><div class="msg-description-success">' . $success_msg . '</div></div></div>';

		if ( $params['action'] == 'create' && $params['posted_form_id'] == $form->id && isset( $_POST ) ) { //phpcs:ignore

			if ( isset( $_REQUEST['arfsubmiterrormsg'] ) ) {

				$failed_msg = $arformsmain->arforms_get_settings('failed_msg','general_settings');
				$failed_msg = !empty( $failed_msg ) ? $failed_msg : addcslashes(esc_html__('We\'re sorry. Form is not submitted successfully.','arforms-form-builder'));

				$arferror_message = ( $_REQUEST['arfsubmiterrormsg'] != '' ) ? sanitize_text_field( $_REQUEST['arfsubmiterrormsg'] ) : $failed_msg;

				$failed_message = '<div class="frm_error_style" id="arf_message_error"><div class="msg-detail"><div class="arf_res_front_msg_desc">' . $arferror_message . '</div></div></div>';

				$arf_display_error = '<div class="arf_form arflite_main_div_' . esc_attr( $form->id ) . '" id="arffrm_' . esc_attr( $form->id ) . '_container">' . $failed_message . '</div>';

				return $arf_display_error;
			}

			$arflite_errors = isset( $arflitecreatedentry[ $form->id ]['errors'] ) ? $arflitecreatedentry[ $form->id ]['errors'] : array();

			if ( ! empty( $arflite_errors ) ) {
				$created = isset( $arflitecreatedentry[ $form->id ]['entry_id'] ) ? $arflitecreatedentry[ $form->id ]['entry_id'] : '';
				$form_submit_type = $arformsmain->arforms_get_settings('form_submit_type','general_settings');
				$form_submit_type = isset( $form_submit_type ) ? $form_submit_type : 1;
				if ( $form_submit_type == 1 ) {

				} else {
					foreach ( $arflite_errors as $e ) {
						if ( ! empty( $e ) ) {

							foreach ( $e as $key => $val ) {
								$failed_msg = '<div class="frm_error_style" id="arf_message_error"><div class="msg-detail"><div class="arf_res_front_msg_desc">' . $val . '</div></div></div>';

								$message   = ( ( isset( $created ) && is_numeric( $created ) ) ? do_shortcode( $saved_message ) : $failed_msg );
								$arf_form .= '<div class="arf_form arflite_main_div_' . esc_attr( $form->id ) . '" id="arffrm_' . esc_attr( $form->id ) . '_container">' . $message . '</div>';
							}
						}
					}
					return $arf_form;
				}
			} else {

				if ( apply_filters( 'arflitecontinuetocreate', true, $form->id ) ) {

					$created = isset( $arflitecreatedentry[ $form->id ]['entry_id'] ) ? $arflitecreatedentry[ $form->id ]['entry_id'] : '';

					$saved_message = apply_filters( 'arflitecontent', $saved_message, $form, $created );

					$saved_popup_message = $saved_message;

					$conf_method = apply_filters( 'arfliteformsubmitsuccess', 'message', $form, $form_options );

					$form_submit_type = $arformsmain->arforms_get_settings('form_submit_type','general_settings');
					$form_submit_type = isset( $form_submit_type ) ? $form_submit_type : 1;

					$success_msg = $arformsmain->arforms_get_settings('success_msg','general_settings');
					$success_msg = !empty( $success_msg ) ? $success_msg : esc_html__('Form is successfully submitted. Thank you!','arforms-form-builder');

					if ( $form_submit_type != 1 && $conf_method == 'redirect' && $saved_message == false ) {
						$conf_method         = 'message';
						$saved_message       = '<div  id="arf_message_success"><div class="msg-detail"><div class="msg-description-success">' . $success_msg . '</div></div></div>';
						$saved_popup_message = '<div id="arf_message_success_popup" style="display: none;"><div class="msg-detail"><div class="msg-description-success">' . $success_msg . '</div>';
					}

					if ( ! $created || ! is_numeric( $created ) ) {
						$conf_method = 'message';
					}

					$return_script = '';

					$return['script'] = apply_filters( 'arflite_after_submit_sucess_outside', $return_script, $form );

					if ( ! $created || ! is_numeric( $created ) || $conf_method == 'message' ) {

						$form_submit_type = $arformsmain->arforms_get_settings('form_submit_type','general_settings');
						$form_submit_type = isset( $form_submit_type ) ? $form_submit_type : 1;

						if ( $form_submit_type == 1 ) {
							$return['conf_method'] = $conf_method;

							if ( isset( $arflite_func_val['hide_forms'] ) && $arflite_func_val['hide_forms'] == true ) {

								$return['hide_forms'] = $arflite_func_val['hide_forms'];
							}
						}

						$failed_msg = $arformsmain->arforms_get_settings('failed_msg','general_settings');
						$failed_msg = !empty( $failed_msg ) ? $failed_msg : addcslashes(esc_html__('We\'re sorry. Form is not submitted successfully.','arforms-form-builder'));

						$failed_msg = '<div class="frm_error_style" id="arf_message_error"><div class="msg-detail"><div class="arf_res_front_msg_desc">' . $failed_msg . '</div></div></div>';

						$message = ( ( $created && is_numeric( $created ) ) ? do_shortcode( $saved_message ) : $failed_msg );

						if ( ! isset( $form_options['show_form'] ) || $form_options['show_form'] ) {

						} else {

							$form_submit_type = $arformsmain->arforms_get_settings('form_submit_type','general_settings');
							$form_submit_type = isset( $form_submit_type ) ? $form_submit_type : 1;

							if ( isset( $values['custom_style'] ) && $values['custom_style'] ) {
								$arfliteloadcss = true;
							}

							if ( $form_submit_type != 1 ) {

								$custom_css_array_form = array(
									'arf_form_success_message' => '#message_success',
								);

								foreach ( $custom_css_array_form as $custom_css_block_form => $custom_css_classes_form ) {
									$form_options[ $custom_css_block_form ] = $arfliteformcontroller->arflitebr2nl( $form_options[ $custom_css_block_form ] );

									if ( isset( $form_options[ $custom_css_block_form ] ) && $form_options[ $custom_css_block_form ] != '' ) {
										echo '<style type="text/css">.arflite_main_div_' . esc_attr( $form->id ) . ' ' . esc_attr( $custom_css_classes_form ) . ' { ' . esc_attr( $form_options[ $custom_css_block_form ] ) . ' } </style>';
									}
								}
							}
							$return = apply_filters( 'arflite_reset_built_in_captcha', $return, $_POST ); //phpcs:ignore


							if ( $form_submit_type == 1 ) {
								$return['message'] = '<div class="arf_form arflite_main_div_' . esc_attr( $form->id ) . '" id="arffrm_' . esc_attr( $form->id ) . '_container">' . $message . '</div>';
								echo json_encode( $return );
								exit;
							} else {
								if ( $arfmessage_rest == '' ) {
									$arf_form       .= $return['script'];
									$arf_form       .= '<div class="arf_form arflite_main_div_' . esc_attr( $form->id ) . '" id="arffrm_' . esc_attr( $form->id ) . '_container">' . $message . '</div>';
									$arfmessage_rest = 1;
								}
								return $arf_form;
							}

							if ( $form_submit_type == 1 ) {
								exit;
							}
						}
					} else {
						$form_submit_type = $arformsmain->arforms_get_settings('form_submit_type','general_settings');
						$form_submit_type = isset( $form_submit_type ) ? $form_submit_type : 1;

						if ( $form_submit_type == 1 ) {
							$return['conf_method'] = $conf_method;
						}

						$form_options = $form->options;
						$entry_id     = $arflitecreatedentry[ $form->id ]['entry_id'];
						if ( $conf_method == 'page' && is_numeric( $form_options['success_page_id'] ) ) {
							global $post;
							if ( $form_options['success_page_id'] != $post->ID ) {
								$page                = get_post( $form_options['success_page_id'] );
								$content             = apply_filters( 'arflitecontent', $page->post_content, $form, $entry_id );
									$arf_old_content = get_post( $page->ID )->post_content;

									$pattern = '\[(\[?)(ARFormslite)(?![\w-])([^\]\/]*(?:\/(?!\])[^\]\/]*)*?)(?:(\/)\]|\](?:([^\[]*+(?:\[(?!\/\2\])[^\[]*+)*+)\[\/\2\])?)(\]?)';

									preg_match_all( '/' . $pattern . '/s', $arf_old_content, $matches );

								foreach ( $matches[0] as $key => $val ) {
									$new_val  = trim( str_replace( ']', '', $val ) );
									$new_val1 = explode( ' ', $new_val );

									$arf_form_id_extracted = isset( $new_val1[1] ) ? str_replace( 'id=', '', $new_val1[1] ) : $form->id;

									$var = 'id=' . $arf_form_id_extracted;

									$upload_main_url = ARFLITE_UPLOAD_URL . '/maincss';
									$is_material     = false;
									$materialize_css = '';

									$temp_form_opts = $wpdb->get_row( $wpdb->prepare( 'SELECT `form_css` FROM `' . $tbl_arf_forms . '` WHERE id = %d', $arf_form_id_extracted ) ); //phpcs:ignore

									if ( empty( $temp_form_opts ) || $temp_form_opts == null ) {
										continue;
									}

									$temp_opts = maybe_unserialize( $temp_form_opts->form_css );

									$inputStyle = isset( $temp_opts['arfinputstyle'] ) ? $temp_opts['arfinputstyle'] : 'material';

									if ( $inputStyle == 'material' ) {
										$materialize_css = 'materialize';
										$is_material     = true;
									}
									if ( is_ssl() ) {
										$fid = str_replace( 'http://', 'https://', $upload_main_url . '/maincss' . $materialize_css . '_' . $arf_form_id_extracted . '.css' );
									} else {
										$fid = $upload_main_url . '/maincss' . $materialize_css . '_' . $arf_form_id_extracted . '.css';
									}

									$fid = esc_url_raw( $fid );

									$return_link        = '';
									$stylesheet_handler = 'arfliteformscss_' . $materialize_css . $arf_form_id_extracted;

									$arf_form .= stripslashes( $return_link );

									if ( trim( $new_val1[1] ) == $var ) {
										$replace = $matches[0][ $key ];
									}
								}
									$arf_form .= $return['script'];
									$arf_form .= apply_filters( 'the_content', $content );

								$form_submit_type = $arformsmain->arforms_get_settings('form_submit_type','general_settings');
								$form_submit_type = isset( $form_submit_type ) ? $form_submit_type : 1;

								if ( $form_submit_type == 1 ) {
									$return['message'] = $arf_form;
								} else {
									return $arf_form;
								}
							}
						} elseif ( $conf_method == 'redirect' ) {
							$success_url = apply_filters( 'arflitecontent', $form_options['success_url'], $form, $entry_id );
							$success_msg = isset( $form_options['success_msg'] ) ? stripslashes( $form_options['success_msg'] ) : __( 'Please wait while you are redirected.', 'arforms-form-builder' );

							$arf_form .= '<input type="hidden" id="arflite_form_redirection_' . esc_attr( $arflite_data_uniq_id ) . '" class="arflite_form_redirect_to_url" value="' . esc_attr( $success_url ) . '" />';
						}

						$form_submit_type = $arformsmain->arforms_get_settings('form_submit_type','general_settings');
						$form_submit_type = isset( $form_submit_type ) ? $form_submit_type : 1;
						if ( $form_submit_type == 1 ) {
							echo json_encode( $return );
							exit;
						}
					}
				}
			}
		}
			$is_hide_form_after_submit = isset( $form->options['arf_form_hide_after_submit'] ) ? $form->options['arf_form_hide_after_submit'] : false;

		$temp_calss = '';

		$arf_form .= $arfliteformcontroller->arflite_get_form_style( $form->id, $arflite_data_uniq_id, $type, $position, $bgcolor, $txtcolor, $btn_angle, $modal_bgcolor, $overlay, $is_fullscrn, $inactive_min, $model_effect );

		$arf_form .= '<div class="arf_form arflite_main_div_' . esc_attr( $form->id ) . ' arf_form_outer_wrapper " id="arffrm_' . esc_attr( $form->id ) . '_container">';

		$arf_form = apply_filters( 'arflite_predisplay_form', $arf_form, $form );
		do_action( 'arflite_predisplay_form' . $form->id, $form );

		if ( isset( $arflite_preview ) && $arflite_preview ) {
			$arf_form .= '<div id="form_success_' . esc_attr( $form->id ) . '" class="display-none-cls">' . $saved_message . '</div>';
		}

		$form_attr    = '';
		$formRandomID = $form->id . '_' . $arflitemainhelper->arflite_generate_captcha_code( '10' );
		$hidden_captcha = $arformsmain->arforms_get_settings('hidden_captcha','general_settings');
		$hidden_captcha = !empty( $hidden_captcha ) ? $hidden_captcha : false;
		if ( 1 != $hidden_captcha ) {
			$captcha_code = $arflitemainhelper->arflite_generate_captcha_code( '8' );

			if ( ! isset( $_SESSION['ARFLITE_FILTER_INPUT'] ) ) {
				$_SESSION['ARFLITE_FILTER_INPUT'] = array();
			}

			$_SESSION['ARFLITE_VALIDATE_SCRIPT']               = true;
			$_SESSION['ARFLITE_FILTER_INPUT'][ $formRandomID ] = $captcha_code;

			$form_attr .= ' data-random-id="' . esc_attr( $formRandomID ) . '" ';
			$form_attr .= ' data-submission-key="' . esc_attr( $captcha_code ) . '" ';
			$form_attr .= ' data-key-validate="false" ';
		} else {
			$form_attr .= ' data-key-validate="true" ';
		}

		$arf_form .= $saved_popup_message;

		$is_hide_form = '';
		if ( $arflite_func_val != '' && $navigation ) {
			$is_hide_form         = 'display:none;';
			$error_restrict_entry = json_decode( $arflite_func_val );
			$arf_form            .= $error_restrict_entry->message;
		}

		if ( isset( $arflite_preview ) && $arflite_preview ) {
			$arf_form .= '<form enctype="' . apply_filters( 'arfliteformenctype', 'multipart/form-data', $form ) . '" method="post" class="arfliteshowmainform arfpreivewform ' . esc_attr( $temp_calss ) . ' ' . do_action( 'arfliteformclasses', $form ) . ' " data-form-id="form_' . esc_attr( $form->form_key ) . '" novalidate="" data-id="' . esc_attr( $arflite_data_uniq_id ) . '" data-popup-id="' . esc_attr( $arf_popup_data_uniq_id ) . '" ' . $form_attr . '>';
		} else {

			$use_html = $arformsmain->arforms_get_settings('use_html','general_settings');
			$use_html = !empty( $use_html ) ? $use_html : true;

			$action_html = ( $use_html ) ? '' : 'action=""';
			$arf_form   .= '<form enctype="' . apply_filters( 'arfliteformenctype', 'multipart/form-data', $form ) . '" method="post" class="arfliteshowmainform ' . esc_attr( $temp_calss ) . ' ' . do_action( 'arfliteformclasses', $form ) . '" style="' . esc_attr( $is_hide_form ) . '" data-form-id="form_' . esc_attr( $form->form_key ) . '" ' . esc_attr( $action_html ) . ' novalidate="" onsubmit="return arflite_validate_form_submit()" arflite_form_submit="0" data-id="' . esc_attr( $arflite_data_uniq_id ) . '" ' . $form_attr;
			if ( $type != '' ) {
				$arf_form .= ' data-popup-id="' . esc_attr( $arf_popup_data_uniq_id ) . '">';
			} else {
				$arf_form .= ' data-popup-id="">';
			}
		}

		$hidden_captcha = $arformsmain->arforms_get_settings('hidden_captcha','general_settings');
		$hidden_captcha = !empty( $hidden_captcha ) ? $hidden_captcha : false;

		if ( 1 != $hidden_captcha ) {
			$arf_form .= "<input type='text' name='arf_filter_input' data-jqvalidate='false' data-random-key='" . esc_attr( $formRandomID ) . "' value='' style='opacity:0 !important; display:none !important; visibility:hidden !important;' />";
			$arf_form .= "<input type='hidden' id='arf_ajax_url' value='" . admin_url( 'admin-ajax.php' ) . "' />";
			$arf_form .= do_shortcode( '[arflite_spam_filters]' );
		}

		$form_action  = 'create';
		$loaded_field = isset( $form->options['arf_loaded_field'] ) ? $form->options['arf_loaded_field'] : array();
		$arf_form    .= $arfliteformcontroller->arflite_get_form_hidden_field( $form, $fields, $values, $arflite_preview, $is_widget_or_modal, $arflite_data_uniq_id, $form_action, $loaded_field, $type, $is_close_link, $arf_current_token );

		global $is_beaverbuilder, $is_divibuilder,$is_fusionbuilder ;
		if( $is_gutenberg || $is_beaverbuilder || $is_divibuilder || $is_fusionbuilder){
			$arf_form .= '<div class="allfields">';
		} else {
			$arf_form .= '<div class="allfields"  style="visibility:hidden;height:0;">';
		}

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
		$arf_form  .= '<div class="arf_fieldset ' . $form_class . '" id="arf_fieldset_' . $arflite_data_uniq_id . '">';

		$arf_form .= $arfliteformcontroller->arflite_load_form_css( $form->id, $inputStyle );

		if ( isset( $form->options['display_title_form'] ) && $form->options['display_title_form'] == 1 ) {

			$arf_form .= '<div class="arftitlecontainer">';

			if ( isset( $form->name ) && $form->name != '' ) {
				$arf_form .= '<div class="formtitle_style">' . html_entity_decode( stripslashes( $form->name) )  . '</div>';
			}
			if ( isset( $form->description ) && $form->description != '' ) {
				$arf_form .= '<div class="arf_field_description formdescription_style">' . html_entity_decode( stripslashes( $form->description ) ) . '</div>';
			}

			$arf_form .= '</div>';
		}

		$is_recaptcha = 0;

		$i = 1;

		$arf_form .= '<div id="page_0" class="page_break">';

		if ( isset( $arf_preset_data ) ) {

			$arf_arr_preset_data = array();
			$arf_preset_data_new = explode( '~!~', $arf_preset_data );

			foreach ( $arf_preset_data_new as $key => $value ) {

				$arf_preset_data_final = explode( '||', $value );
				$arf_preset_data_id    = str_replace( 'item_meta_', '', $arf_preset_data_final[0] );

				if ( isset( $arf_preset_data_final[1] ) && preg_match( '^!^', $arf_preset_data_final[1] ) ) {

					$arf_preset_data_final[1] = explode( '^!^', $arf_preset_data_final[1] );
				}
				$arf_preset_data_value                      = isset( $arf_preset_data_final[1] ) ? $arf_preset_data_final[1] : '';
				$arf_arr_preset_data[ $arf_preset_data_id ] = $arf_preset_data_value;
			}
		}
		$arflite_glb_preset_data = $arf_arr_preset_data;

		$arf_form .= $arfliteformcontroller->arflite_get_all_field_html( $form, $values, $arflite_data_uniq_id, $fields, $arflite_preview, $arflite_errors, $inputStyle, $arf_arr_preset_data );

		$captcha_key = $arfliteformcontroller->arfliteSearchArray( 'captcha', 'type', $values['fields'] );

		if ( '' !== $captcha_key ) {
			$is_recaptcha = 1;
		}

		$arf_form = apply_filters( 'arfliteentryform', $arf_form, $form, $form_action, $arflite_errors );

		$arf_form .= '<div style="clear:both;height:1px;">&nbsp;</div>';
		$arf_form .= '</div>';

		if ( ! $form->is_template && $form->id != '' ) {

			$display_submit   = 'style="display:none;"';
			$display_previous = '';
			$is_submit_form   = 1;

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

				$arf_form .= '<div class="arfsubmitbutton ' . esc_html( $_SESSION['label_position'] ) . '_container" ';

				$arf_form .= '>';
				$arf_form .= '<div class="arf_submit_div ' . esc_html( $_SESSION['label_position'] ) . '_container">';

				$arf_form .= '<input type="hidden" data-jqvalidate="false" value="1" name="is_submit_form_' . esc_attr( $form->id ) . '" data-id="is_submit_form_' . esc_attr( $form->id ) . '" />';
				$arf_form .= '<input type="hidden" data-jqvalidate="false" value="0" data-val="0" data-max="0" name="submit_form_' . esc_attr( $form->id ) . '" data-id="submit_form_' . esc_attr( $form->id ) . '" />';

				$submit               = apply_filters( 'arflitegetsubmitbutton', $submit, $form );
				$is_submit_hidden     = false;
				$submitbtnstyle       = '';
				$submitbtnclass       = '';
				$arfsubmitbuttonstyle = isset( $form->form_css['arfsubmitbuttonstyle'] ) ? $form->form_css['arfsubmitbuttonstyle'] : 'border';

				$sbmt_class = '';
				if ( $inputStyle == 'material' ) {
					$sbmt_class = 'btn btn-flat';
				}
				$arfbrowser_name     = strtolower( str_replace( ' ', '_', $browser_info['name'] ) );
				$submit_btn_content  = '<button class="arf_submit_btn arf_submit_btn_' . esc_attr( str_replace( ' ', '_', $arfsubmitbuttonstyle ) ) . ' arfstyle-button  ' . esc_attr( $sbmt_class ) . ' ' . esc_attr( $submitbtnclass ) . ' ' . esc_attr( $arfbrowser_name ) . '" id="arf_submit_btn_' . esc_attr( $arflite_data_uniq_id ) . '" name="arf_submit_btn_' . esc_attr( $arflite_data_uniq_id ) . '" data-style="zoom-in" ' . esc_attr( $submitbtnstyle );
				$submit_btn_content  = apply_filters( 'arflite_add_submit_btn_attributes_outside', $submit_btn_content, $form );
				$submit_btn_content .= ' ><span class="arfsubmitloader"></span><span class="arfstyle-label">' . esc_attr( $submit ) . '</span><span class="arf_ie_image display-none-cls">';
				if ( $browser_info['name'] == 'Opera' && $browser_info['version'] <= 30 ) {
					$submit_btn_content .= '<img src="' . ARFLITEURL . '/images/submit_btn_image.gif" style="width:24px; box-shadow:none;-webkit-box-shadow:none;-o-box-shadow:none;-moz-box-shadow:none; vertical-align:middle; height:24px; padding-top:' . esc_attr( $padding_loading ) . 'px" />';
				}
				$submit_btn_content .= '</span></button>';

				$arf_form .= $submit_btn_content;

				$arf_form .= '</div><input type="hidden" name="submit_btn_image" id="submit_btn_image" value="' . ARFLITEURL . '/images/submit_loading_img.gif" /></div><div class="arflite-clear-float"></div>';
			} else {

				$arf_form          .= '<div class="arfsubmitbutton ' . esc_html( $_SESSION['label_position'] ) . '_container" >';
				$sbtm_wrapper_class = '';
				if ( $inputStyle == 'material' ) {
					$sbtm_wrapper_class = 'file-field ';
				}
				$arf_form .= '<div class="arf_submit_div ' . $sbtm_wrapper_class . ' ' . esc_html( $_SESSION['label_position'] ) . '_container">';

				$arf_form .= '<input type="hidden" data-jqvalidate="false" value="1" name="is_submit_form_' . esc_attr( $form->id ) . '" data-id="is_submit_form_' . esc_attr( $form->id ) . '" />';
				$arf_form .= '<input type="hidden" data-jqvalidate="false" value="0" data-val="0" data-max="0" name="submit_form_' . esc_attr( $form->id ) . '" data-id="submit_form_' . esc_attr( $form->id ) . '" />';

				$submit           = apply_filters( 'arflitegetsubmitbutton', $submit, $form );
				$is_submit_hidden = false;
				$submitbtnstyle   = '';
				$submitbtnclass   = '';

				$arfsubmitbuttonstyle = isset( $form->form_css['arfsubmitbuttonstyle'] ) ? $form->form_css['arfsubmitbuttonstyle'] : 'border';
				$submit_btn_content   = '';

				$sbmt_class = '';
				if ( $inputStyle == 'material' ) {
					$sbmt_class = 'btn btn-flat';
				}

				$arfbrowser_name     = strtolower( str_replace( ' ', '_', $browser_info['name'] ) );
				if($is_gutenberg)
				{
					$submit_btn_content .= '<button disabled  class="arf_submit_btn  arf_submit_btn_' . esc_attr( str_replace( ' ', '_', $arfsubmitbuttonstyle ) ) . ' ' . esc_attr( $sbmt_class ) . '  btn-info arfstyle-button ' . esc_attr( $submitbtnclass ) . ' ' . esc_attr( $arfbrowser_name ) . '"  id="arf_submit_btn_' . esc_attr( $arflite_data_uniq_id ) . '" name="arf_submit_btn_' . esc_attr( $arflite_data_uniq_id ) . '" data-style="zoom-in" ';
				}

				$submit_btn_content .= '<button   class="arf_submit_btn  arf_submit_btn_' . esc_attr( str_replace( ' ', '_', $arfsubmitbuttonstyle ) ) . ' ' . esc_attr( $sbmt_class ) . '  btn-info arfstyle-button ' . esc_attr( $submitbtnclass ) . ' ' . esc_attr( $arfbrowser_name ) . '"  id="arf_submit_btn_' . esc_attr( $arflite_data_uniq_id ) . '" name="arf_submit_btn_' . esc_attr( $arflite_data_uniq_id ) . '" data-style="zoom-in" ';

				$submit_btn_content = apply_filters( 'arflite_add_submit_btn_attributes_outside', $submit_btn_content, $form );

				$submit_btn_content .= esc_attr( $submitbtnstyle ) . ' >';

				$submit_btn_content .= '<span class="arfsubmitloader"></span><span class="arfstyle-label">' . esc_attr( $submit ) . '</span>';
				if ( $browser_info['name'] == 'Opera' && $browser_info['version'] <= 30 ) {
					$padding_loading     = isset( $padding_loading ) ? $padding_loading : '';
					$submit_btn_content .= '<span class="arf_ie_image display-none-cls">';
					$submit_btn_content .= '<img src="' . ARFLITEURL . '/images/submit_btn_image.gif" style="width:24px; box-shadow:none;-webkit-box-shadow:none;-o-box-shadow:none;-moz-box-shadow:none; vertical-align:middle; height:24px; padding-top:' . esc_attr( $padding_loading ) . 'px;"/>';
					$submit_btn_content .= '</span>';
				}

				$submit_btn_content .= '</button>';

				$arf_form .= $submit_btn_content;

				$arf_form .= '</div></div><div class="arflite-clear-float"></div>';
			}
		} else {

			$arf_form .= '<p class="arfsubmitbutton ' . esc_html( $_SESSION['label_position'] ) . '_container">';
			$submit    = apply_filters( 'arflitegetsubmitbutton', $submit, $form );
			$arf_form .= '<input type="submit" value="' . esc_attr( $submit ) . '" onclick="return false;" ';
			$arf_form  = apply_filters( 'arfliteactionsubmitbutton', $arf_form, $form, $form_action );
			$arf_form .= '/>';
			$arf_form .= '<div id="submit_loader" class="submit_loader display-none-cls"></div></p>';
		}

		$i = 1;

		$arf_form .= '</div>';
		$arf_form  = apply_filters( 'arflite_additional_form_content_outside', $arf_form, $form, $arflite_data_uniq_id, $arfbrowser_name, $browser_info );
		$arf_form .= '</div>';
		$arf_form .= '</form>';

		$form = apply_filters( 'arfliteafterdisplayform', $form );

		do_action( 'arflite_afterdisplay_form', $form );
		do_action( 'arflite_afterdisplay_form' . $form->id, $form );
		$arf_form .= '</div>';

		if ( $type != '' ) {
			$arf_form .= '</div>';
			$arf_form .= '</div>';
			if ( $type == 'sticky' ) {
				$arf_form .= '</div>';
				if ( $position == 'top' ) {
					$arf_form .= '<div class="arflite-clear-float"></div>';
					$arf_form .= '<div class="arform_bottom_fixed_block_top arflite-cursor-pointer arf_fly_sticky_btn arform_modal_stickytop_' . esc_attr( $form->id ) . '" onclick="open_modal_box_sitcky_top(\'' . esc_attr( $form->id ) . '\', \'' . esc_attr( $modal_height ) . '\', \'' . esc_attr( $modal_width ) . '\', \'' . esc_attr( $checkradio_property ) . '\', \'' . esc_attr( $checked_checkbox_property ) . '\', \'' . esc_attr( $checked_radio_property ) . '\', \'' . esc_attr( $arf_popup_data_uniq_id ) . '\');" ><span href="#" data-toggle="arfmodal" title="' . esc_attr( $form_name ) . '">' . $desc . '</span></div>';
				}
				$arf_form .= '</div>';
			} elseif ( $type == 'fly' ) {
				$arf_form .= '</div>';
				$arf_form .= '</div>';
			}

			if ( $type == 'sticky' && $position == 'left' ) {

				$arflite_form_all_footer_js .= 'var winodwHeight = jQuery(window).height();
                var modal_height_left = "' . $modal_height . '";


                jQuery("#arf-popup-form-' . $form->id . ' .arform_bottom_fixed_block_left").parents(".arform_bottom_fixed_main_block_left").find(".arform_bottom_fixed_form_block_left_main").css("margin-top", "-35px");
                jQuery("#arf-popup-form-' . $form->id . '.arform_bottom_fixed_main_block_left").css("display", "inline-block");
                jQuery(".arf_popup_' . $arf_popup_data_uniq_id . '").find(".arform_modal_stickybottom_' . $form->id . '").css("transform-origin", "left top");';
			}
			if ( $type == 'sticky' && $position == 'right' ) {

				$arflite_form_all_footer_js .= '  var winodwHeight = jQuery(window).height();
                var modal_height_right = "' . $modal_height . '";
                jQuery("#arf-popup-form-' . $form->id . ' .arform_bottom_fixed_block_right").parents(".arform_bottom_fixed_main_block_right").find(".arform_bottom_fixed_form_block_right_main").css("margin-top", "-35px");
                jQuery("#arf-popup-form-' . $form->id . '.arform_bottom_fixed_main_block_right").css("display", "inline-block");
                jQuery(".arf_popup_' . $arf_popup_data_uniq_id . '").find(".arform_modal_stickybottom_' . $form->id . '").css("transform-origin", "right top");';
			}
		}

		$arf_form .= '<div class=""><input type="hidden" data-jqvalidate="false" name="form_id" data-id="form_id" value="' . esc_attr( $form->id ) . '" /><input type="hidden" data-jqvalidate="false" name="arfmainformurl" data-id="arfmainformurl" value="' . ARFLITEURL . '" /></div>';

		if ( $is_recaptcha == 1 ) {

			$recaptcha_details = $arformsmain->arforms_get_settings( ['pubkey', 're_theme', 're_lang'],'general_settings');

			extract( $recaptcha_details );

			$arf_form .= "<input type='hidden' id='arf_settings_recaptcha_v2_public_key' value='" . esc_attr( $pubkey ) . "' />";
			$arf_form .= "<input type='hidden' id='arf_settings_recaptcha_v2_public_theme' value='" . esc_attr( $re_theme ) . "' />";
			$arf_form .= "<input type='hidden' id='arf_settings_recaptcha_v2_public_lang' value='" . esc_attr( $re_lang ) . "' />";
		}

		if ( $home_preview == true ) {

			global $arfliteversion;
			$dest_css_url = ARFLITE_UPLOAD_URL . '/maincss/';
			if ( $inputStyle == 'material' ) {
				if ( is_ssl() ) {
					$fid_material = str_replace( 'http://', 'https://', $dest_css_url . '/maincss_materialize_' . $form->id . '.css' );
				} else {
					$fid_material = $dest_css_url . '/maincss_materialize_' . $form->id . '.css';
				}

				$fid_material = esc_url_raw( $fid_material );

				wp_register_style( 'material-form-css-' . $form->id, $fid_material, array(), $arfliteversion );
				wp_print_styles( 'material-form-css-' . $form->id );
			} else {
				if ( is_ssl() ) {
					$fid = str_replace( 'http://', 'https://', $dest_css_url . '/maincss_' . $form->id . '.css' );
				} else {
					$fid = $dest_css_url . '/maincss_' . $form->id . '.css';
				}

				$fid = esc_url_raw( $fid );

				wp_register_style( 'main-form-css-' . $form->id, $fid, array(), $arfliteversion );
				wp_print_styles( 'main-form-css-' . $form->id );
			}
		}

		if ( isset( $form->options['tooltip_loaded'] ) && $form->options['tooltip_loaded'] ) {
			$arf_tootip_width            = ( isset( $form->form_css['arf_tooltip_width'] ) && $form->form_css['arf_tooltip_width'] != '' ) ? $form->form_css['arf_tooltip_width'] : 'auto';
			$arf_tooltip_position        = ( isset( $form->form_css['arftooltipposition'] ) && $form->form_css['arftooltipposition'] != '' ) ? $form->form_css['arftooltipposition'] : 'top';
			$arf_mobile_tooltip          = ( $arf_tooltip_position == 'bottom' ) ? 'bottom' : 'top';
			$arflite_form_all_footer_js .= '
			var sreenwidth = jQuery(window).width();
			if ( typeof jQuery().tipso == "function" ) {
                jQuery(".arflite_main_div_' . $form->id . '").find(".arfhelptip").each(function () {
                    jQuery(this).tipso("destroy");
                    var title = jQuery(this).attr("data-title");
                    jQuery(this).tipso({
                        position: "' . $arf_tooltip_position . '",
                        width: "' . $arf_tootip_width . '",
                        useTitle: false,
                        content: title,
                        background: "' . str_replace( '##', '#', $form->form_css['arf_tooltip_bg_color'] ) . '",
                        color:"' . str_replace( '##', '#', $form->form_css['arf_tooltip_font_color'] ) . '",
                        tooltipHover: true
                    });
                });
            }
            if (typeof jQuery().tipso == "function" && sreenwidth < 500) {
                jQuery(".arflite_main_div_' . $form->id . '").find(".arfhelptip").each(function () {
                    jQuery(this).tipso("destroy");
                    var title = jQuery(this).attr("data-title");
                    jQuery(this).tipso({
                        position: "' . $arf_mobile_tooltip . '",
                        width: "' . $arf_tootip_width . '",
                        useTitle: false,
                        content: title,
                        background: "' . str_replace( '##', '#', $form->form_css['arf_tooltip_bg_color'] ) . '",
                        color:"' . str_replace( '##', '#', $form->form_css['arf_tooltip_font_color'] ) . '",
                        tooltipHover: true
                    });
                });
            }';

			if ( $inputStyle == 'material' ) {
				$arflite_form_all_footer_js .= '
				var sreenwidth = jQuery(window).width();
	            if (typeof jQuery().tipso == "function") {
	                jQuery(".arflite_main_div_' . $form->id . ' .arfliteshowmainform[data-id=' . $arflite_data_uniq_id . '] .arf_materialize_form .arfhelptipfocus input,.arflite_main_div_' . $form->id . ' .arfliteshowmainform[data-id=' . $arflite_data_uniq_id . '] .arf_materialize_form .arfhelptipfocus textarea").on( "focus", function(e){
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
	                                position: "' . $arf_tooltip_position . '",
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

	                jQuery(document).on("focusout", ".arflite_main_div_' . $form->id . ' .arfliteshowmainform[data-id=' . $arflite_data_uniq_id . '] .arf_materialize_form .arfhelptipfocus input,.arflite_main_div_' . $form->id . ' .arfliteshowmainform[data-id=' . $arflite_data_uniq_id . '] .arf_materialize_form .arfhelptipfocus textarea", function(e){
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
	            if (typeof jQuery().tipso == "function" && sreenwidth < 500 ) {
                    jQuery(document).on( "focus", ".arflite_main_div_' . $form->id . ' .arfliteshowmainform[data-id=' . $arflite_data_uniq_id . '] .arf_materialize_form .arfhelptipfocus input,.arflite_main_div_' . $form->id . ' .arfliteshowmainform[data-id=' . $arflite_data_uniq_id . '] .arf_materialize_form .arfhelptipfocus textarea", function(e){
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
	                                position: "' . $arf_mobile_tooltip . '",
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

	                jQuery(document).on("focusout", ".arflite_main_div_' . $form->id . ' .arfliteshowmainform[data-id=' . $arflite_data_uniq_id . '] .arf_materialize_form .arfhelptipfocus input,.arflite_main_div_' . $form->id . ' .arfliteshowmainform[data-id=' . $arflite_data_uniq_id . '] .arf_materialize_form .arfhelptipfocus textarea", function(e){
	                    jQuery(this).parent().parent()
	                    .each(function () {
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
                }';
			}
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
			if ( isset( $form_css_submit['arf_checked_checkbox_icon'] ) && $form_css_submit['arf_checked_checkbox_icon'] != '' ) {
				$checked_checkbox_property = ' arfalite ' . $form_css_submit['arf_checked_checkbox_icon'];
				if ( $form->options['font_awesome_loaded'] == 0 || empty( $form->options['font_awesome_loaded'] ) ) {
					$form->options['font_awesome_loaded'] = 1;
				}
			} else {
				$checked_checkbox_property = '';
			}
			$checked_radio_property = '';
			if ( isset( $form_css_submit['arf_checked_radio_icon'] ) && $form_css_submit['arf_checked_radio_icon'] != '' ) {
				$checked_radio_property = ' arfalite ' . $form_css_submit['arf_checked_radio_icon'];
				if ( $form->options['font_awesome_loaded'] == 0 || empty( $form->options['font_awesome_loaded'] ) ) {
					$form->options['font_awesome_loaded'] = 1;
				}
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

		$parm_action = !empty( $_REQUEST['action'] ) ? sanitize_text_field( $_REQUEST['action'] ) : '';
		if ( (! empty( $_REQUEST['action'] ) && 'elementor' == $_REQUEST['action']) || $is_gutenberg == true ) {
			remove_action( 'arflite_footer_js', array( $arfliterecordcontroller, 'arflite_footer_js' ), 1 );
			do_action( 'arflite_load_assets_for_elementor', intval( $form->id ), sanitize_text_field( $parm_action ) );
			if( $is_gutenberg ){
				$arf_form .= $arfliterecordcontroller->arflite_footer_js( true, false, true, true );
			} else {
				$arfliterecordcontroller->arflite_footer_js( false, true, true );
			}
		}
		if ( ! empty( $_REQUEST['action'] ) && ( 'elementor' == $_REQUEST['action'] || 'elementor_ajax' == $_REQUEST['action'] ) ) {
			$arf_form .= '<input type="hidden" id="elementor_editor" value="yes" />';
		}

		return $arf_form;
	}
}
