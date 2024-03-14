<?php

if ( ! defined( 'ABSPATH' ) || ! function_exists( 'current_user_can' ) || ! current_user_can( 'arfeditforms' ) || ! isset( $_GET['arflite_page_nonce'] ) || ( isset( $_GET['arflite_page_nonce'] ) && ! wp_verify_nonce( sanitize_text_field( $_GET['arflite_page_nonce'] ), 'arflite_page_nonce' ) ) ) {
	exit;
}

global $arflitefieldhelper, $arfliteformhelper, $ARFLiteMdlDb, $arflite_fields_with_external_js, $arflite_bootstraped_fields_array,$arfliteformcontroller, $tbl_arf_fields, $arformsmain;
$frm_class = 'arf_standard_form';
if ( $newarr['arfinputstyle'] == 'rounded' ) {
	$frm_class = 'arf_rounded_form';
} elseif ( $newarr['arfinputstyle'] == 'material' ) {
	$frm_class = 'arf_materialize_form';
}

if ( isset( $_GET['arfaction']) && (sanitize_text_field( $_GET['arfaction'] ) == 'new' || sanitize_text_field( $_GET['arfaction'] ) == 'duplicate') ) {
	if ( $define_template < 100 ) {
		$values['name']        = isset( $_GET['form_name'] ) ? stripslashes_deep( $arfliteformcontroller->arfliteHtmlEntities( sanitize_text_field( $_GET['form_name'] ), true ) ) : '';
		$values['description'] = isset( $_GET['form_desc'] ) ? stripslashes_deep( $arfliteformcontroller->arfliteHtmlEntities( sanitize_text_field( $_GET['form_desc'] ), true ) ) : '';
	}
}
?>
<div id="arfmainformeditorcontainer" class="arf_form arf_form_outer_wrapper arf_main_tabs active_tabs arf_form arflite_main_div_<?php echo esc_attr( $arflite_id ); ?>">
	<div class="allfields">
		<div id="arf_fieldset_<?php echo esc_html( $arflite_id ); ?>" class="arf_fieldset <?php echo esc_attr( $frm_class ); ?>">
			<div id="success_message" class="arf_success_message">
				<div class="message_descripiton">
					<div class="arffloatmargin"><?php echo esc_html__( 'Form is successfully updated', 'arforms-form-builder' ); ?></div>
					<div class="message_svg_icon">
						<svg class="arfheightwidth14" ><path fill-rule="evenodd" clip-rule="evenodd" fill="#FFFFFF" d="M6.075,14.407l-5.852-5.84l1.616-1.613l4.394,4.385L17.181,0.411 l1.616,1.613L6.392,14.407H6.075z"></path></svg>
					</div>
				</div>
			</div>
			<div id="error_message" class="arf_error_message">
				<div class="message_descripiton">
					<div class="arffloatmargin"><?php echo esc_html__( 'Form is not successfully updated', 'arforms-form-builder' ); ?></div>
					<div class="message_svg_icon">
							<svg class="arfheightwidth14"><path fill-rule="evenodd" clip-rule="evenodd" fill="#ffffff" d="M10.702,10.909L6.453,6.66l-4.249,4.249L1.143,9.848l4.249-4.249L1.154,1.361l1.062-1.061l4.237,4.237l4.238-4.237l1.061,1.061L7.513,5.599l4.249,4.249L10.702,10.909z"></path></svg>
					</div>
				</div>
			</div>
			<div id="titlediv" class="arftitlediv" <?php echo ( isset( $newarr['display_title_form'] ) && $newarr['display_title_form'] == 0 ) ? 'style="display:none;"' : ''; ?>>
				<input type="hidden" value="<?php echo esc_url(ARFLITEURL) . '/images'; ?>" id="plugin_image_path" />

				<div id="form_desc" class="edit_form_item arffieldbox frm_head_box">

					<div class="arfformnamediv">
						<div class="arfformedit arftitlecontainer">
							<span class="arfeditorformname formtitle_style arf_edit_in_place" id="frmform_<?php echo esc_html( $arflite_id ); ?>">
								<input type="text" name="name" id="form_name" class="arf_edit_in_place_input inplace_field" value="<?php echo stripslashes_deep( esc_attr( $values['name'] ) ); //phpcs:ignore ?>" data-default-value="<?php echo stripslashes_deep( esc_attr( $values['name'] ) ); //phpcs:ignore ?>" data-ajax="false" data-action="arfupdateformname" placeholder="<?php echo __( 'Click here to enter form title', 'arforms-form-builder' ); ?>"/>
							</span>
						</div>
						<div class="arfformeditpencil" id="arfformeditpencil"></div>
					</div>
					<div class="arflite-clear-float"></div>
					<div class="arfformdescriptiondiv">
						<div class="arfdescriptionedit">

							<div class="arfeditorformdescription arf_edit_in_place formdescription_style"><input type="text" data-default-value="<?php echo ( $values['description'] != '' ) ? stripslashes_deep( esc_attr( $values['description'] ) ) : esc_html__( 'Click here to enter form description', 'arforms-form-builder' ); ?>" class="arf_edit_in_place_input inplace_field" data-ajax="false" name="description" data-action="arfupdateformdescription" value="<?php echo ( $values['description'] != '' ) ? stripslashes_deep( esc_attr( $values['description'] ) ) : ''; ?>" placeholder="<?php echo esc_html__( 'Click here to enter form description', 'arforms-form-builder' ); //phpcs:ignore ?>"/></div>
						</div>
						<div class="arfdescriptioneditpencil"></div>
					</div>
					<div class="arflite-clear-float"></div>
				</div>
				<div class="arflite-clear-float"></div>

			</div>




			<div id="new_fields" data-flag="1" class="newfield_div">


				<?php
				$index_arf_fields = 0;

				if ( isset( $values['fields'] ) && ! empty( $values['fields'] ) ) {
					$arf_load_confirm_email = array();
					$totalpass              = 0;
					foreach ( $values['fields'] as $arrkey => $field ) {

						if ( $field['type'] == 'email' ) {
							$field['id'] = $arflitefieldhelper->arfliteget_actual_id( $field['id'] );

							if ( isset( $field['confirm_email'] ) && $field['confirm_email'] == 1 && isset( $arf_load_confirm_email['confrim_email_field'] ) && $arf_load_confirm_email['confrim_email_field'] == $field['id'] ) {
								$values['confirm_email_arr'][ $field['id'] ] = isset( $field['confirm_email_field'] ) ? $field['confirm_email_field'] : '';
							} else {
								$arf_load_confirm_email['confrim_email_field'] = isset( $field['confirm_email_field'] ) ? $field['confirm_email_field'] : '';
							}
						}



						if ( $field['type'] == 'email' && isset( $field['confirm_email'] ) && $field['confirm_email'] == 1 ) {
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
					$arf_fields = array();

					if ( $arfaction == 'duplicate' ) {
						$arf_fields = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM `' . $tbl_arf_fields . '` WHERE `form_id` = %d', $define_template ), ARRAY_A ); //phpcs:ignore
					} elseif ( $arfaction == 'edit' ) { 
						$arf_fields = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM `' . $tbl_arf_fields . '` WHERE `form_id` = %d', $arflite_id ), ARRAY_A ); //phpcs:ignore
					}

					$frm_opts = ( isset( $data['options'] ) && $data['options'] != '' ) ? maybe_unserialize( $data['options'] ) : array();

					$frm_css = maybe_unserialize( $data['form_css'] );

					$field_order = isset( $frm_opts['arf_field_order'] ) ? $frm_opts['arf_field_order'] : '';

					$inner_field_order = isset( $frm_opts['arf_inner_field_order'] ) ? $frm_opts['arf_inner_field_order'] : json_encode( array() );

					$field_resize_width = isset( $frm_opts['arf_field_resize_width'] ) ? $frm_opts['arf_field_resize_width'] : '';

					$inner_field_resize_width = isset( $frm_opts['arf_inner_field_resize_width'] ) ? $frm_opts['arf_inner_field_resize_width'] : '';

					$field_temp_fields = maybe_unserialize( $data['temp_fields'] );

					$arf_field_counter = 1;
					if ( $field_resize_width != '' ) {
						$field_resize_width = json_decode( $field_resize_width, true );
					}

					if ( $inner_field_resize_width != '' ) {
						$inner_field_resize_width = json_decode( $inner_field_resize_width, true );
					}

					
					$arf_sorted_fields      = array();
					$arf_temp_sorted_fields = array();
					$arf_inner_class        = array();
					$new_arf_fields			= array();
					if ( $field_order != '' ) {
						$field_order = json_decode( $field_order, true );
						
						asort( $field_order );
						$sorted_counter = 0;
						foreach ( $field_order as $field_id => $fields_order ) {
							if ( is_int( $field_id ) ) {
								foreach ( $arf_fields as $field ) {
									if ( $field_id == $field['id'] ) {
										$arf_sorted_fields[]                       = $field;
										$temp_field_opts                           = json_decode( $field['field_options'], true );
										$arf_temp_sorted_fields[ $sorted_counter ] = ! empty( $temp_field_opts['inner_class'] ) ? $temp_field_opts['inner_class'] : 'arf_1col';
										$arf_inner_class[ $field_id ]              = $temp_field_opts['inner_class'];
									}
								}
							} else {
								$exploded_fid                 = explode( '|', $field_id );
								$temp_fid                     = $exploded_fid[0];
								$prev_sorted_counter          = $sorted_counter - 1;
								$arf_inner_class[ $field_id ] = $temp_fid;

								if ( ! empty( $arf_temp_sorted_fields[ $prev_sorted_counter ] ) ) {
									$prev_inner_class = $arf_temp_sorted_fields[ $prev_sorted_counter ];
									if ( $temp_fid == 'arf_2col' && ! preg_match( '/(arf21colclass)/', $prev_inner_class ) ) {
										continue;
									} elseif ( $temp_fid == 'arf_3col' && ! preg_match( '/(arf_23col)/', $prev_inner_class ) ) {
										continue;
									} elseif ( $temp_fid == 'arf_4col' && ! preg_match( '/(arf43colclass)/', $prev_inner_class ) ) {
										continue;
									} elseif ( $temp_fid == 'arf_5col' && ! preg_match( '/(arf54colclass)/', $prev_inner_class ) ) {
										continue;
									} elseif ( $temp_fid == 'arf_6col' && ! preg_match( '/(arf65colclass)/', $prev_inner_class ) ) {
										continue;
									}
								}
								$arf_sorted_fields[]                       = $field_id;
								$arf_temp_sorted_fields[ $sorted_counter ] = $field_id;
							}
							$sorted_counter++;
						}
					}

					if ( isset( $arf_sorted_fields ) && ! empty( $arf_sorted_fields ) ) {
						$arf_fields = $arf_sorted_fields;
					}
					$field_orders = array();
					$ord          = 0;
					$ord_         = 1;

					foreach ( $arf_inner_class as $fid => $inner_cls ) {
						$current     = current( $arf_inner_class );
						$current_key = key( $arf_inner_class );

						$next     = next( $arf_inner_class );
						$next_key = key( $arf_inner_class );

						if ( ( $current == 'arf_2col' && ! empty( $next ) && 'arf_2col' == $next && is_int( $next_key ) ) || ( $current == 'arf_3col' && ! empty( $next ) && 'arf_3col' == $next && is_int( $next_key ) ) || ( $current == 'arf_4col' && ! empty( $next ) && 'arf_4col' == $next && is_int( $next_key ) ) || ( $current == 'arf_5col' && ! empty( $next ) && 'arf_5col' == $next && is_int( $next_key ) ) || ( $current == 'arf_6col' && ! empty( $next ) && 'arf_6col' == $next && is_int( $next_key ) ) ) {
							$getKey = $arfliteformcontroller->arfliteSearchArray( $next_key, 'id', $arf_fields );

							if ( '' !== $getKey ) {
								$fopts                = json_decode( $arf_fields[ $getKey ]['field_options'], true );
								$fopts['classes']     = 'arf_1';
								$fopts['inner_class'] = 'arf_1col';

								$arf_fields[ $getKey ]['field_options'] = json_encode( $fopts );
							}
						}

						if ( ( $current == 'arf21colclass' && ! empty( $next ) && 'arf_2col' != $next ) && ! preg_match( '/(_confirm)/', $next ) ) {
							$field_orders[ $fid ] = $ord;
							$ord_++;
							$field_orders[ 'arf_2col|' . $ord_ ] = ++$ord;
						} elseif ( ( 'arf_23col' == $current ) && ! empty( $next ) && 'arf_3col' != $next && ! preg_match( '/(_confirm)/', $next ) ) {
							$field_orders[ $fid ] = $ord;
							$ord_++;
							$field_orders[ 'arf_3col|' . $ord_ ] = ++$ord;
						} elseif ( ( 'arf43colclass' == $current ) && ! empty( $next ) && 'arf_4col' != $next && ! preg_match( '/(_confirm)/', $next ) ) {
							$field_orders[ $fid ] = $ord;
							$ord_++;
							$field_orders[ 'arf_4col|' . $ord_ ] = ++$ord;
						} elseif ( ( 'arf54colclass' == $current ) && ! empty( $next ) && 'arf_5col' != $next && ! preg_match( '/(_confirm)/', $next ) ) {
							$field_orders[ $fid ] = $ord;
							$ord_++;
							$field_orders[ 'arf_5col|' . $ord_ ] = ++$ord;
						} elseif ( ( 'arf65colclass' == $current ) && ! empty( $next ) && 'arf_6col' != $next && ! preg_match( '/(_confirm)/', $next ) ) {
							$field_orders[ $fid ] = $ord;
							$ord_++;
							$field_orders[ 'arf_6col|' . $ord_ ] = ++$ord;
						} else {
							$field_orders[ $fid ] = $ord;
						}
						$ord++;
						$ord_++;
					}

					$updated_sorted_fields = array();
					if ( ! empty( $field_orders ) ) {
						foreach ( $field_orders as $field_id => $fields_order ) {
							if ( is_int( $field_id ) ) {
								foreach ( $arf_fields as $field ) {
									if ( isset( $field['id'] ) && $field_id == $field['id'] ) {
										$updated_sorted_fields[] = $field;
									}
								}
							} else {
								$updated_sorted_fields[] = $field_id;
							}
						}
					}

					if ( ! empty( $updated_sorted_fields ) ) {
						$total_sorted_fields = count( $updated_sorted_fields );
						$new_sorted_fields   = $updated_sorted_fields;
						foreach ( $updated_sorted_fields as $k => $v ) {
							if ( empty( $v ) ) {
								$next_val = $new_sorted_fields[ $k + 1 ];

								if ( !is_array( $next_val ) && preg_match( '/(arf_2col\|)/', $next_val ) ) {
									$new_sorted_fields[ $k ] = 'arf21colclass|' . ( $k + 1 );
								}
							}

							if ( $total_sorted_fields == ( $k + 1 ) ) {
								if ( is_array( $v ) ) {
									$current_fopts = json_decode( $v['field_options'], true );
									if ( preg_match( '/arf21colclass/', $current_fopts['inner_class'] ) ) {
										$new_sorted_fields[ $k + 1 ] = 'arf_2col|' . ( $k + 1 );
									}
								}
							}
						}
						$new_sorted_fields     = array_values( $new_sorted_fields );
						$updated_sorted_fields = $new_sorted_fields;
					}

					if ( ! empty( $updated_sorted_fields ) ) {
						$arf_fields = $updated_sorted_fields;
					}
					$class_array      = array();
					$conut_arf_fields = count( $arf_fields );

					$inner_field_count = 0;
					$arflite_classes   = array();
					foreach ( $arf_fields as $field_key => $field ) {
						$display_field_in_editor_from_outside = apply_filters( 'arflite_display_field_in_editor_outside', false, $field );

						if ( is_array( $field ) ) {
							if ( $field['type'] == 'hidden' ) {
								continue;
							}

							$field_name    = 'item_meta[' . $field['id'] . ']';
							$has_field_opt = false;
							if ( isset( $field['options'] ) && $field['options'] != '' && ! empty( $field['options'] ) ) {
								$has_field_opt    = true;
								$field_options_db = json_decode( $field['options'], true );
								if ( json_last_error() != JSON_ERROR_NONE ) {
									$field_options_db = maybe_unserialize( $field['options'], true );
								}
							}

							$field_opt = json_decode( $field['field_options'], true );

							if ( json_last_error() != JSON_ERROR_NONE ) {
								$field_opt = maybe_unserialize( $field['field_options'] );
							}

							$class             = ( isset( $field_opt['inner_class'] ) && $field_opt['inner_class'] ) ? $field_opt['inner_class'] : 'arf_1col';
							$arflite_classes[] = $class;
							array_push( $class_array, $class );

							if ( isset( $field_opt ) && ! empty( $field_opt ) && is_array( $field_opt ) ) {
								foreach ( $field_opt as $k => $field_opt_val ) {
									if ( $k != 'options' ) {
										$field[ $k ] = $arfliteformcontroller->arflite_html_entity_decode( $field_opt_val );
									} else {
										if ( $has_field_opt == true && $k == 'options' ) {
											$field[ $k ] = $field_options_db;
										}
									}
								}
							}
							if ( in_array( $field['type'], $arflite_bootstraped_fields_array ) ) {
								array_push( $arflite_fields_with_external_js, $field['type'] );
							}
						} else {
							$arflite_classes[] = $field;
						}

						if ( ! $display_field_in_editor_from_outside ) {
							require ARFLITE_VIEWS_PATH . '/arflite_field_editor.php';
						} else {
							do_action( 'arflite_render_field_in_editor_outside', $field, $field_data_obj, $field_order, $inner_field_order, $index_arf_fields, $frm_css, $data, $arflite_id, $inner_field_resize_width, array(), false, $newarr );
						}

						unset( $field );


						unset( $field_name );

						$arf_field_counter++;
					}
				}

				?>

			</div>

			<?php
			echo "<label class='arf_main_label arf_width_counter_label'></label>";
			echo "<label class='arf_main_label arf_width_counter_label_section'></label>";
			$newarr['arfsubmitbuttontext'] = isset( $newarr['arfsubmitbuttontext'] ) ? $newarr['arfsubmitbuttontext'] : '';
			if ( $newarr['arfsubmitbuttontext'] == '' ) {
				$arf_option   = get_option( 'arflite_options' );
				$submit_value = $arf_option->submit_value;
			} else {
				$submit_value = $newarr['arfsubmitbuttontext'];
			}

			$submit_buttonwidth = $newarr['arfsubmitbuttonwidthsetting'] ? $newarr['arfsubmitbuttonwidthsetting'] : '';
			?>
			<div class="arflite-clear-float"></div>
			<div class="arfeditorsubmitdiv arf_submit_div top_container">
				<div class="arfsubmitedit arfsubmitbutton">
					<div class="arf_greensave_button_wrapper">
						<?php
						$arfsubmitbuttonstyleclass = '';

						if ( isset( $newarr['arfsubmitbuttonstyle'] ) && $newarr['arfsubmitbuttonstyle'] == 'flat' ) {
							$arfsubmitbuttonstyleclass = 'arf_submit_btn_flat';
						} elseif ( isset( $newarr['arfsubmitbuttonstyle'] ) && $newarr['arfsubmitbuttonstyle'] == 'border' ) {
							$arfsubmitbuttonstyleclass = 'arf_submit_btn_border';
						} elseif ( isset( $newarr['arfsubmitbuttonstyle'] ) && $newarr['arfsubmitbuttonstyle'] == 'reverse border' ) {
							$arfsubmitbuttonstyleclass = 'arf_submit_btn_reverse_border';
						}
						?>
						<div class="greensavebtn arf_submit_btn btn btn-info arfstyle-button waves-effect waves-light <?php echo esc_attr( $arfsubmitbuttonstyleclass ); ?>" data-auto="
																																 <?php
																																	if ( $submit_buttonwidth != '' ) {
																																		echo '1';
																																	} else {
																																		echo '0';
																																	}
																																	?>
						"
						<?php
						if ( $submit_buttonwidth != '' ) {
							echo 'style="width:' . esc_attr( $submit_buttonwidth ) . 'px;"';
						}
						?>
								 data-style="zoom-in" data-width="<?php echo esc_attr( $submit_buttonwidth ); ?>">
							<div class="arfsubmitbtn arf_edit_in_place" id="arfeditorsubmit">
								<?php
								if ( ! empty( $newarr['submit_bg_img'] ) ) {
									$submit_bg_img = 'arflite_hide_btn_text';
								} else {
									$submit_bg_img = '';
								}
								?>
								<input type='text' class='arf_edit_in_place_input inplace_field arf_submit_button_textbox <?php echo esc_attr( $submit_bg_img ); ?>' data-id="arf_form_submit_button" data-ajax='false' value="<?php echo esc_attr( $submit_value ); ?>" />
							</div>
						</div>
						<span class="arf_submit_button_edit_icon"><svg width='18' height='18' fill='rgb(255, 255, 255)' xmlns='http://www.w3.org/2000/svg' data-name='Layer 1' viewBox='0 0 512 512' x='0px' y='0px'><title>Edit</title><path d='M318.37,85.45L422.53,190.11,158.89,455,54.79,350.38ZM501.56,60.2L455.11,13.53a45.93,45.93,0,0,0-65.11,0L345.51,58.24,449.66,162.9l51.9-52.15A35.8,35.8,0,0,0,501.56,60.2ZM0.29,497.49a11.88,11.88,0,0,0,14.34,14.17l116.06-28.28L26.59,378.72Z'/></svg></span>
					</div>
				</div>
				<div class="arfsubmiteditpencil arfhelptip" title="<?php echo esc_html__( 'Edit Text', 'arforms-form-builder' ); ?>"></div>
				<div class="arfsubmitsettingpencil arfhelptip" title="<?php echo esc_html__( 'Settings', 'arforms-form-builder' ); ?>" id="field-setting-button-arfsubmit" onclick="arfliteshowfieldoptions('arfsubmit')" data-lower="false"></div>
			</div>
		</div>
	</div>

	<div class="arflite-clear-float"></div>

</div>

<div class="edit_form_save_btn_container">
	<div class="greensavebtn save-form-btn-val" id="arfsubmitbuttontext2"><?php echo esc_html( $submit_value ); ?></div>
</div>
<input type="hidden" name="arf_editor_total_rows" id="arf_editor_total_rows" value="<?php echo esc_html( $index_arf_fields ); ?>" />
