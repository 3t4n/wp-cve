<?php

class arfliteformhelper {
	function __construct() {
		add_filter( 'arflitesetupnewformvars', array( $this, 'arflite_setup_new_variables' ) );
	}

	function arflite_setup_new_variables( $values ) {
		global $arfliteformhelper, $arflitemainhelper;
		foreach ( $arfliteformhelper->arflite_get_default_options() as $var => $default ) {
			$values[ $var ] = $arflitemainhelper->arflite_get_param( $var, $default );
		}
		return $values;
	}

	function arflite_get_direct_link( $key ) {
		global $arflitesiteurl;
		$target_url = esc_url( site_url() . '/index.php?plugin=ARFormslite&controller=forms&arfaction=preview&form=' . $key );
		return $target_url;
	}

	function arflitereplaceshortcodes( $html, $form, $is_title = false, $description = false ) {
		foreach ( array(
			'form_name'        => $is_title,
			'form_description' => $description,
			'entry_key'        => true,
		) as $code => $show ) {
			if ( $code == 'form_name' ) {
				$replace_with = $form->name;
			} elseif ( $code == 'form_description' ) {
				$replace_with = $form->description;
			} elseif ( $code == 'entry_key' && isset( $_GET ) && isset( $_GET['entry'] ) ) {
				$replace_with = sanitize_text_field( $_GET['entry'] );
			}

			if ( ( $show == true || $show == 'true' ) && $replace_with != '' ) {
				$html = str_replace( '[if ' . $code . ']', '', $html );
				$html = str_replace( '[/if ' . $code . ']', '', $html );
			} else {
				$html = preg_replace( '/(\[if\s+' . $code . '\])(.*?)(\[\/if\s+' . $code . '\])/mis', '', $html );
			}
			$html = str_replace( '[' . $code . ']', $replace_with, $html );
		}
		$html = str_replace( '[form_key]', $form->form_key, $html );
		$html = trim( $html );
		return apply_filters( 'arfliteformreplaceshortcodes', stripslashes( $html ), $form );
	}

	function arflite_get_default_options() {
		global $arflite_style_settings, $arformsmain;

		$email_options_data = $arformsmain->arforms_get_settings(['reply_to','admin_nreplyto_email','ar_admin_from_email','ar_admin_from_name','ar_user_nreplyto_email','ar_user_from_email','ar_user_from_name','arf_pre_dup_msg'],'general_settings');

		extract( $email_options_data );
	
		return array(
			'edit_value'                 => ( !empty( $arflite_style_settings->update_value ) ? $arflite_style_settings->update_value : '' ),
			'edit_msg'                   => ( !empty( $arflite_style_settings->edit_msg ) ? $arflite_style_settings->edit_msg : '' ),
			'logged_in_role'             => '',
			'editable_role'              => '',
			'open_editable'              => 0,
			'open_editable_role'         => '',
			'copy'                       => 0,
			'single_entry'               => 0,
			'single_entry_type'          => 'user',
			'success_page_id'            => '',
			'success_url'                => '',
			'ajax_submit'                => 0,
			'create_post'                => 0,
			'cookie_expiration'          => 8000,
			'post_type'                  => 'post',
			'post_category'              => array(),
			'post_content'               => '',
			'post_excerpt'               => '',
			'post_title'                 => '',
			'post_name'                  => '',
			'post_date'                  => '',
			'post_status'                => '',
			'post_custom_fields'         => array(),
			'post_password'              => '',
			'plain_text'                 => 0,
			'also_email_to'              => array(),
			'update_email'               => 0,
			'email_subject'              => '',
			'email_message'              => '[default-message]',
			'inc_user_info'              => 1,
			'auto_responder'             => 0,
			'ar_plain_text'              => 0,
			'ar_email_to'                => '',
			'ar_reply_to'                => get_option( 'admin_email' ),
			'ar_reply_to_name'           => get_option( 'blogname' ),
			'ar_email_subject'           => '',
			'ar_email_message'           => __( 'Thank you for subscription with us. We will contact you soon.', 'arforms-form-builder' ),
			'ar_update_email'            => 0,
			'chk_admin_notification'     => 0,
			'form_custom_css'            => '',
			'label_position'             => ( !empty( $arflite_style_settings->position ) ? $arflite_style_settings->position : '' ),
			'is_custom_css'              => 0,
			'ar_admin_email_to'          => get_option( 'admin_email' ),
			'ar_admin_reply_to'          => get_option( 'admin_email' ),
			'ar_admin_email_message'     => '[ARFLite_form_all_values]',
			'arf_enable_double_optin'    => 1,
			'ar_admin_reply_to_name'     => get_option( 'blogname' ),
			'email_to'                   => !empty( $reply_to ) ? $reply_to : '',
			'reply_to'                   => !empty( $reply_to ) ? $reply_to : '',
			'reply_to_name'              => get_option( 'blogname' ),
			'ar_admin_reply_to_email'    => get_option( 'admin_email' ),
			'user_nreplyto_email'        => get_option( 'admin_email' ),
			'display_title_form'         => '1',
			'ar_user_from_name'          => $ar_user_from_name ? $ar_user_from_name : '',
			'ar_user_from_email'         => $ar_user_from_email ? $ar_user_from_email : '',
			'ar_user_nreplyto_email'     => $ar_user_nreplyto_email ? $ar_user_nreplyto_email : '',
			'ar_admin_from_name'         => $ar_admin_from_name ? $ar_admin_from_name : '',
			'ar_admin_from_email'        => $ar_admin_from_email ? $ar_admin_from_email : '',
			'admin_nreplyto_email'       => $admin_nreplyto_email ? $admin_nreplyto_email : '',
			'arf_form_outer_wrapper'     => '',
			'arf_form_inner_wrapper'     => '',
			'arf_form_title'             => '',
			'arf_form_description'       => '',
			'arf_form_element_wrapper'   => '',
			'arf_form_element_label'     => '',
			'arf_form_submit_button'     => '',
			'arf_form_success_message'   => '',
			'arf_form_elements'          => '',
			'arf_submit_outer_wrapper'   => '',
			'arf_form_error_message'     => '',
			'arf_form_other_css'         => '',
			'admin_email_subject'        => '[form_name] ' . __( 'Form submitted on', 'arforms-form-builder' ) . ' [site_name] ',
			'arf_form_hide_after_submit' => '',
			'arf_pre_dup_check'          => '',
			'arf_pre_dup_check_type'     => '',
			'arf_pre_dup_field'          => '',
			'arf_pre_dup_msg'            => !empty($arf_pre_dup_msg) ? $arf_pre_dup_msg : esc_html__( 'You have already submitted this form before. You are not allowed to submit this form again.', 'arforms-form-builder' ),
			'conditional_subscription'   => 0,
		);
	}

	function arflite_forms_dropdown_new( $field_name, $field_value = '', $blank = true, $field_id = false, $onchange = false, $multiple = false, $is_import_export = 0, $show_id = false, $selectClass = '' ) {
		global $arfliteform, $arflitemainhelper, $arflitefieldhelper, $arflitemaincontroller;
		$array = '';
		if ( ! $field_id ) {
			$field_id = $field_name;
		}
		$optionheight = '';
		if ( $multiple == 'mutliple' ) {
			$multiple = 'multiple';
			$array    = '[]';
		}

		$where = apply_filters( 'arfliteformsdropdowm', "is_template=0 AND (status is NULL OR status = '' OR status = 'published') AND arf_is_lite_form = 1", $field_name );

		$forms = $arfliteform->arflitegetAll( $where, ' ORDER BY name' );

		?>
		<?php

		if ( $field_name == 'arfaddformid' || $field_name == 'arfaddformid_vc_popup' ) {
			?>
			<div class="dt_dl">
				<?php

					$show_form_itd       = '';
					$selected_list_label = addslashes( esc_html__( 'Select Form', 'arforms-form-builder' ) );
					$selected_list_id    = '';

					$list = array( '0' => addslashes( esc_html__( 'Select Form', 'arforms-form-builder' ) ) );
				foreach ( $forms as $form ) {
					if ( $show_id ) {
						$show_form_itd = $form->id . ' - ';
					}
					$form->name = $arflitefieldhelper->arflite_execute_function( $form->name, 'strip_tags' );
					if ( $form->id == $field_value ) {
						$selected_list_id    = $form->id;
						$selected_list_label = $arflitemainhelper->arflitetruncate( $form->name, 33 );
					}

					$list[ $form->id ] = $show_form_itd . $arflitefieldhelper->arflite_execute_function( html_entity_decode( $arflitemainhelper->arflitetruncate( $form->name, 33 ) ), 'strip_tags' ) . ' (id: ' . $form->id . ')';
				}

					$arf_dropdown_attr      = array();
					$arf_dropdown_opts_attr = array();
					if ( $onchange ) {
						$arf_dropdown_attr['onchange'] = $onchange;
					}

					//$arf_dropdown_opts_attr['style'][ $form->id ] = $optionheight;
					echo $arflitemaincontroller->arflite_selectpicker_dom( $field_name, $field_id, '', '', '', $arf_dropdown_attr, $list, false, array(), false, $arf_dropdown_opts_attr, false, array(), true ); //phpcs:ignore
				?>
			</div>
		<?php } else { ?>

			<div class="multiple_select_box">
				<select name="<?php echo esc_attr( $field_name ) . esc_attr( $array ); ?>" id="<?php echo esc_attr( $field_id ); ?>" class="frm-dropdown arflite_select_frm_list <?php echo esc_attr( $selectClass ); ?>"
										 <?php
											if ( $onchange ) {
												echo 'onchange="' . esc_js( $onchange ) . '"';}
											?>
				 data-width="360px" data-size="10" <?php echo esc_html($multiple); ?>>

				 <?php if ( $blank ) { ?>

						<!-- add  class to hide - select form - option -->
						<option class="optionheight slct-formblack" value=""><?php echo ( $blank == 1 ) ? '' : '- ' . esc_attr( $blank ) . ' -'; ?></option>


			<?php } ?>

				<?php $show_form_itd = ''; ?>
			<?php foreach ( $forms as $form ) { ?>
				<?php
				if ( $show_id ) {
					$show_form_itd = $form->id . ' - ';
				}
				?>

						<option class="lblnotetitle optionheight" value="<?php echo esc_attr( $form->id ); ?>" <?php selected( $field_value, $form->id ); ?>><?php echo esc_html( $show_form_itd ) . html_entity_decode( $arflitemainhelper->arflitetruncate( $form->name, 33 ) ); //phpcs:ignore ?></option>


			<?php } ?>


				</select>
			</div>
		<?php } ?>
		<?php if ( $is_import_export == 1 ) { ?>
			<div class="arf_import_export_entries_dropdown dt_dl">
				<input type="hidden" name="is_single_form" value="0" id="is_single_form"/>
				<?php

					if ( $blank ) {
						
					}

					$show_form_itd       = '';
					$selected_list_label = addslashes( esc_html__( 'Select Form', 'arforms-form-builder' ) );
					$selected_list_id    = '';

					$list = array( '0' => addslashes( esc_html__( 'Select Form', 'arforms-form-builder' ) ) );
					foreach ( $forms as $form ) {
						if ( $show_id ) {
							$show_form_itd = $form->id . ' - ';
						}
						$form->name = $arflitefieldhelper->arflite_execute_function( $arflitemainhelper->arflitetruncate( html_entity_decode( stripslashes( $form->name ) ), 33 ), 'strip_tags' );
						if ( $form->id == $field_value ) {
							$selected_list_id    = $form->id;
							$selected_list_label = $arflitemainhelper->arflitetruncate( html_entity_decode( stripslashes( $form->name ) ), 33 );
						}

						$list[ $form->id ] = $show_form_itd . $arflitefieldhelper->arflite_execute_function( $arflitemainhelper->arflitetruncate( html_entity_decode( stripslashes( $form->name ) ), 33 ), 'strip_tags' );
					}

					$arf_field_dd_attr   = array();
					$arf_field_opts_attr = array();

					if ( $onchange ) {
						$arf_field_dd_attr['onchange'] = $onchange;
					}
					echo $arflitemaincontroller->arflite_selectpicker_dom( $field_name . '_name', $field_id . '_name', '', 'width:300px;', '', $arf_field_dd_attr, $list, false, array(), false, $arf_field_opts_attr, false, array(), true ); //phpcs:ignore
				?>
			</div>
				<?php
		}
	}

	function arflite_forms_dropdown_widget( $field_name, $field_value = '', $blank = true, $field_id = false, $onchange = false ) {

		global $arfliteform, $arflitemainhelper;

		if ( ! $field_id ) {
			$field_id = $field_name;
		}

		$where = apply_filters( 'arfliteformsdropdowm', "is_template=0 AND (status is NULL OR status = '' OR status = 'published')", $field_name );

		$forms = $arfliteform->arflitegetAll( $where, ' ORDER BY name' );
		?>

		<select name="<?php echo esc_attr( $field_name ); ?>" id="<?php echo esc_attr( $field_id ); ?>" class="frm-dropdown arflite_widget_frm_dpdn"
								 <?php
									if ( $onchange ) {
										echo 'onchange="' . esc_js( $onchange ) . '"';}
									?>
		 data-width="225px" data-size="15">


				<?php if ( $blank ) { ?>


				<option value=""><?php echo ( $blank == 1 ) ? '' : '- ' . esc_attr( $blank ) . ' -'; ?></option>


				<?php } ?>


				<?php foreach ( $forms as $form ) { ?>


				<option value="<?php echo esc_attr( $form->id ); ?>" <?php selected( $field_value, $form->id ); ?>><?php echo $arflitemainhelper->arflitetruncate( $form->name, 33 ); //phpcs:ignore ?></option>


		<?php } ?>


		</select>


		<?php
	}

	function arflite_setup_new_vars() {

		global $ARFLiteMdlDb, $arformsmain, $arfliteformhelper, $arflitemainhelper, $tbl_arf_forms;

		$values = array();

		foreach ( array(
			'name'        => __( 'Untitled Form', 'arforms-form-builder' ),
			'description' => '',
		) as $var => $default ) {
			$values[ $var ] = $arflitemainhelper->arflite_get_param( $var, $default );
		}

		foreach ( array(
			'form_id'     => '',
			'is_template' => 0,
		) as $var => $default ) {
			$values[ $var ] = $arflitemainhelper->arflite_get_param( $var, $default );
		}

		$values['form_key'] = ( $_POST && isset( $_POST['form_key'] ) ) ? sanitize_text_field( $_POST['form_key'] ) : ( $arflitemainhelper->arflite_get_unique_key( '', $tbl_arf_forms, 'form_key' ) ); //phpcs:ignore

		$defaults = $arfliteformhelper->arflite_get_default_opts();

		foreach ( $defaults as $var => $default ) {

			if ( $var == 'notification' ) {

				$values[ $var ] = array();

				foreach ( $default as $k => $v ) {

					$values[ $var ][ $k ] = ( isset( $_POST ) && $_POST && isset( $_POST['notification'][ $var ] ) ) ? wp_kses( $_POST['notification'][ $var ] ) : $v; //phpcs:ignore

					unset( $k );

					unset( $v );
				}
			} else {

				$values[ $var ] = ( isset( $_POST ) && $_POST && isset( $_POST['options'][ $var ] ) ) ? wp_kses( $_POST['options'][ $var ] ) : $default; //phpcs:ignore
			}

			unset( $var );

			unset( $default );
		}
		$load_style = $arformsmain->arforms_get_settings('load_style','general_settings');
		$load_style = !empty( $load_style ) ? $load_style : 'none';

		$values['custom_style'] = ( isset( $_POST ) && $_POST && isset( $_POST['options']['custom_style'] ) ) ? sanitize_text_field( $_POST['options']['custom_style'] ) : ( $load_style != 'none' ); //phpcs:ignore

		$values['before_html'] = $arfliteformhelper->arflite_get_default_html( 'before' );

		$values['after_html'] = $arfliteformhelper->arflite_get_default_html( 'after' );

		return apply_filters( 'arflitesetupnewformvars', $values );
	}

	function arflite_get_default_opts() {
		global $arformsmain;
		

		$reply_to = $arformsmain->arforms_get_settings('reply_to','general_settings');
		$submit_value = $arformsmain->arforms_get_settings('submit_value','general_settings');
		$success_msg = $arformsmain->arforms_get_settings('success_msg','general_settings');

		return array(
			'notification'           => array(
				array(
					'email_to'           => !empty( $reply_to ) ? $reply_to : '',
					'reply_to'           => !empty( $reply_to ) ? $reply_to : '',
					'reply_to_name'      => get_option( 'blogname' ),
					'cust_reply_to'      => '',
					'cust_reply_to_name' => '',
				),
			),
			'submit_value'           => !empty($submit_value) ? $submit_value : esc_html__('submit','arforms-form-builder'),
			'success_action'         => 'message',
			'success_msg'            => !empty($success_msg) ? $success_msg : esc_html__('Form is successfully submitted. Thank you!','arforms-form-builder'),
			'show_form'              => 0,
			'akismet'                => '',
			'ar_email_message'       => __( 'Thank you for subscription with us. We will contact you soon.', 'arforms-form-builder' ),
			'ar_admin_email_message' => '[ARFLite_form_all_values]',
			'no_save'                => 0,
			'admin_email_subject'    => '[form_name] ' . __( 'Form submitted on', 'arforms-form-builder' ) . ' [site_name] ',
		);
	}

	function arflite_get_default_html( $loc ) {

		if ( $loc == 'before' ) {

			$default_html = '[if form_name]<div class="formtitle_style">[form_name]</div>[/if form_name]<br/>[if form_description]<div class="arf_field_description formdescription_style">[form_description]</div>[/if form_description]';
		} else {

			$default_html = '';
		}
		return $default_html;
	}

	function arflite_forms_dropdown( $field_name, $field_value = '', $blank = true, $field_id = false, $onchange = false ) {

		global $arfliteform, $arflitemainhelper, $arflitefieldhelper, $arflitemaincontroller;

		if ( ! $field_id ) {
			$field_id = $field_name;
		}

		$where = apply_filters( 'arfliteformsdropdowm', "is_template=0 AND (status is NULL OR status = '' OR status = 'published') AND arf_is_lite_form = 1", $field_name );

		$forms = $arfliteform->arflitegetAll( $where, ' ORDER BY name' );

		global $wpdb, $ARFLiteMdlDb, $arflite_db_record, $tbl_arf_forms, $tbl_arf_entries;

		$list_options = array(
			'' => ' - ' . $blank . ' - ',
		);

		$record_count = wp_cache_get( 'arflite_record_count_' . $field_value );
		if ( false === $record_count ) {
			$record_count = $wpdb->get_results( "SELECT $tbl_arf_forms.id, COUNT($tbl_arf_entries.id) AS count_num FROM $tbl_arf_entries RIGHT JOIN $tbl_arf_forms ON $tbl_arf_entries.form_id=$tbl_arf_forms.id WHERE $tbl_arf_forms.is_template=0 AND $tbl_arf_forms.arf_is_lite_form = 1 AND ($tbl_arf_forms.status is NULL OR $tbl_arf_forms.status = '' OR $tbl_arf_forms.status = 'published') group by $tbl_arf_forms.id", OBJECT_K ); //phpcs:ignore

			wp_cache_set( 'arflite_record_count_' . $field_value, $record_count );
		}
		

		$selected_list_label   = '';
		$responder_list_option = '';
		$selected_list_id      = '';
		$list_class            = array();
		?>

		<?php
		foreach ( $forms as $form ) {
			$span_class = "<span class='arflite_total_entry_" . $form->id . "'>";
			$count_num  = isset( $record_count[ $form->id ]->count_num ) ? $record_count[ $form->id ]->count_num : 0;
			if ( $field_value == $form->id ) {
				$selected_list_id    = $form->id;
				$selected_list_label = $arflitemainhelper->arflitetruncate( html_entity_decode( stripslashes( $form->name ) ), 23 ) . ' [' . $form->id . ']' . ' (' . $span_class . $count_num . '</span> - ' . __( 'Entries', 'arforms-form-builder' ) . ')';
			}
			$arfform_display_option = $arflitefieldhelper->arflite_execute_function( $arflitemainhelper->arflitetruncate( html_entity_decode( stripslashes( $form->name ) ), 23 ), 'strip_tags' ) . ' [' . $form->id . ']' . ' (' . $span_class . $count_num . '</span> - ' . __( 'Entries', 'arforms-form-builder' ) . ')';

			$list_options[ $form->id ] = $arfform_display_option;
			$list_class[ $form->id ]   = 'arflite_total_entry_li_' . $form->id;

		}

			$list_attrs = array();

		if ( ! empty( $onchange ) ) {
			$list_attrs['onchange'] = $onchange;
		}

			echo $arflitemaincontroller->arflite_selectpicker_dom( $field_name, $field_id, 'frm-dropdown frm-pages-dropdown', '', $selected_list_id, $list_attrs, $list_options, false, $list_class, false, array(), false, array(), true ); //phpcs:ignore

	}

	function arflite_replace_field_shortcode( $content ) {
		global $wpdb, $arflitefield;

		$tagregexp = '';

		preg_match_all( "/\[(if )?($tagregexp)(.*?)(?:(\/))?\](?:(.+?)\[\/\2\])?/s", $content, $matches, PREG_PATTERN_ORDER );

		if ( $matches && $matches[3] ) {
			foreach ( $matches[3] as $shortcode ) {
				if ( $shortcode ) {
					global $arflitefield;
					$display     = false;
					$show        = 'one';
					$odd         = '';
					$optionvalue = '';

					$field_ids = explode( ':', $shortcode );

					if ( is_array( $field_ids ) ) {
						$field_id = end( $field_ids );

						if ( strpos( $field_id, '.' ) !== false ) {
							$is_checkbox = explode( '.', $field_id );
						} else {
							$is_checkbox = array();
						}

						if ( count( $is_checkbox ) > 0 ) {
							$field_id       = $is_checkbox[0];
							$is_checkbox[1] = isset( $is_checkbox[1] ) ? $is_checkbox[1] : '';
							$option_id      = $is_checkbox[1];
						} else {
							$option_id = '';
						}
					} else {
						$option_id = '';
					}

					$field = $arflitefield->arflitegetOne( $field_id );

					if ( ! isset( $field ) || ! $field->id ) {
						return $content;
					}

					if ( $field ) {
						$field_opts = ( ! is_array( $field->field_options ) ? json_decode( $field->field_options, true ) : $field->field_options );

						$is_sep_val = isset( $field_opts['separate_value'] ) ? $field_opts['separate_value'] : '';

						$fieldoptions = json_decode( $field->options );

						if ( isset( $option_id ) && $option_id != '' ) {
							$optionvalue = $fieldoptions[ $option_id ];
						}

						if ( $field->type == 'checkbox' ) {
							if ( $is_sep_val == 1 ) {
								$optionvalue1 = $optionvalue['value'];
								$optionlabel  = $optionvalue['label'];

								$replace_with = '[' . $optionvalue['label'] . ':' . $field_id . '.' . $option_id . ']';
							} else {

								if ( is_array( $optionvalue ) ) {
									$optionvalue  = $optionvalue['label'];
									$replace_with = '[' . $optionvalue . ':' . $field_id . '.' . $option_id . ']';
								} elseif ( $optionvalue == '' ) {
									$replace_with = '[' . $field->name . ':' . $field_id . ']';
								}
							}
						} else {
							$replace_with = '[' . $field->name . ':' . $field_id . ']';
						}
					}

					$content = str_replace( '[' . $shortcode . ']', $replace_with, $content );
				}
			}
		}

		return $content;
	}

	function arflite_replace_field_shortcode_import( $content, $res_field_id, $new_field_id ) {

		if ( ! $res_field_id || ! $new_field_id ) {
			return $content;
		}

		global $wpdb, $arflitefield;

		$tagregexp = '';

		preg_match_all( "/\[(if )?($tagregexp)(.*?)(?:(\/))?\](?:(.+?)\[\/\2\])?/s", $content, $matches, PREG_PATTERN_ORDER );

		if ( $matches && $matches[3] ) {
			foreach ( $matches[3] as $shortcode ) {
				if ( $shortcode ) {
					global $arflitefield;
					$display     = false;
					$show        = 'one';
					$odd         = '';
					$optionvalue = '';

					$field_ids = explode( ':', $shortcode );
					$field_id  = end( $field_ids );

					if ( is_array( $field_ids ) ) {
						$field_id = end( $field_ids );

						if ( strpos( $field_id, '.' ) !== false ) {
							$is_checkbox = explode( '.', $field_id );
						} else {
							$is_checkbox = array();
						}

						if ( count( $is_checkbox ) > 0 ) {
							$field_id       = $is_checkbox[0];
							$is_checkbox[1] = isset( $is_checkbox[1] ) ? $is_checkbox[1] : '';
							$option_id      = $is_checkbox[1];
						} else {
							$option_id = '';
						}
					} else {
						$option_id = '';
					}

					$temp_field = $arflitefield->arflitegetOne( $field_id );

					if ( ! isset( $temp_field ) || ! $temp_field->id ) {
						return $content;
					}

					if ( $field_id == $res_field_id ) {
						$field = $arflitefield->arflitegetOne( $new_field_id );

						if ( $field ) {
							$field_opts = arflite_json_decode( $field->field_options, true );

							$is_sep_val = isset( $field_opts['separate_value'] ) ? $field_opts['separate_value'] : '';

							$fieldoptions = json_decode( $field->options, true );

							if ( isset( $option_id ) && $option_id != '' ) {
								$optionvalue = $fieldoptions[ $option_id ];
							}

							if ( $field->type == 'checkbox' ) {

								if ( $is_sep_val == 1 ) {
									$optionvalue1 = $optionvalue['value'];
									$optionlabel  = $optionvalue['label'];

									$replace_with = '[' . $optionvalue['label'] . ':' . $field_id . '.' . $option_id . ']';
								} else {
									if ( is_array( $optionvalue ) ) {
										$optionvalue = $optionvalue['label'];
									}
									$replace_with = '[' . $optionvalue . ':' . $field_id . '.' . $option_id . ']';
								}
							} else {
								$replace_with = '[' . $field->name . ':' . $field_id . ']';
							}

							$content = str_replace( '[' . $shortcode . ']', $replace_with, $content );
						}
					}
				}
			}
		}

		return $content;
	}
}
