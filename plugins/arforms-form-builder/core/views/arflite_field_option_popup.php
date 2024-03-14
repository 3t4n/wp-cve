<div class="arf_field_option_model" id="arf_field_option_model_skeleton">
	<div class="arf_field_option_model_header"><?php echo esc_html__( 'Field Options', 'arforms-form-builder' ); ?></div>
	<div class="arf_field_option_model_container">
		<div class="arf_field_option_content_row">

			<div class="arf_field_option_content_cell" data-sort="-1" id="labelname">
				<input type="checkbox" class="display-none-cls field-option-required" name="required" id="frm_req_field_{arf_field_id}" onchange="arflitemakerequiredfieldfunction('{arf_field_id}', 0, 1);" value="1" />
				<label class="arf_field_option_content_cell_label" id="arf_label_for_general"><?php echo esc_html__( 'Label Name', 'arforms-form-builder' ); ?></label>
				<div class="arf_field_option_content_cell_input">
					<input class="arf_field_option_input_text" name="name" id="arfname_{arf_field_id}" value="" type="text">
				</div>
			</div>
			
			<div class="arf_field_option_content_cell" data-sort="-1" id="max_opt_selected">
				<label class="arf_field_option_content_cell_label"><?php echo esc_html__( 'Max Option Selected', 'arforms-form-builder' ); ?></label>
				<div class="arf_field_option_content_cell_input">
					<input type="text" class="arf_field_option_input_text" name="max_opt_sel" id="maxoptsel" value="" />
				</div>
			</div>

			<div class="arf_field_option_content_cell" data-sort="-1" id="max_opt_selected_msg">
				<label class="arf_field_option_content_cell_label"><?php echo esc_html__( 'Max Option Selected Message', 'arforms-form-builder' ); ?></label>
				<div class="arf_field_option_content_cell_input">
					<input type="text" class="arf_field_option_input_text" name="max_opt_sel_msg" id="maxoptselmsg" value="" />
				</div>
			</div>


			<div class="arf_field_option_content_cell" data-sort="-1" id="min_opt_selected">
				<label class="arf_field_option_content_cell_label"><?php echo esc_html__( 'Min Option Selected', 'arforms-form-builder' ); ?></label>
				<div class="arf_field_option_content_cell_input">
					<input type="text" class="arf_field_option_input_text" name="min_opt_sel" id="minoptsel" value="" />
				</div>
			</div>

			<div class="arf_field_option_content_cell" data-sort="-1" id="min_opt_selected_msg">
				<label class="arf_field_option_content_cell_label"><?php echo esc_html__( 'Min Option Selected Message', 'arforms-form-builder' ); ?></label>
				<div class="arf_field_option_content_cell_input">
					<input type="text" class="arf_field_option_input_text" name="min_opt_sel_msg" id="minoptselmsg" value="" />
				</div>
			</div>

			<div class="arf_field_option_content_cell" data-sort="-1" id="requiredmsg">
				<label class="arf_field_option_content_cell_label"><?php echo esc_html__( 'Message for blank field', 'arforms-form-builder' ); ?></label>
				<div class="arf_field_option_content_cell_input">
					<input type="text" class="arf_field_option_input_text" name="blank" id="arfrequiredfieldtext{arf_field_id}" value=" " />
				</div>
			</div>

			<div class="arf_field_option_content_cell" data-sort="-1" id="number_of_rows">
				<label class="arf_field_option_content_cell_label"><?php echo esc_html__( 'Number of Rows', 'arforms-form-builder' ); ?></label>
				<div class="arf_field_option_content_cell_input">
					<input type="text" class="arf_field_option_input_text" name="max_rows" id="maxrows_{arf_field_id}" />
				</div>
			</div>
			
			<div class="arf_field_option_content_cell" data-sort="-1" id="arf_enable_readonly">
				<label class="arf_field_option_content_cell_label"><?php echo esc_html__( 'Enable Readonly', 'arforms-form-builder' ); ?></label>
				<div class="arf_field_option_content_cell_input">
					<label class="arf_js_switch_label">
						<span><?php echo esc_html__( 'NO', 'arforms-form-builder' ); ?>&nbsp;</span>
					</label>
					<span class="arf_js_switch_wrapper">
						<input type="checkbox" class="js-switch arf_enable_readonly_{arf_field_id}" name="arf_enable_readonly" id="arf_enable_readonly_{arf_field_id}" value="1" />
						<span class="arf_js_switch"></span>
					</span>
					<label class="arf_js_switch_label">
						<span>&nbsp;<?php echo esc_html__( 'YES', 'arforms-form-builder' ); ?></span>
					</label>
				</div>
			</div>
		
		 <div class="arf_field_option_content_cell" data-sort="-1" id="fieldsize_phone">
			<label class="arf_field_option_content_cell_label"><?php echo esc_html__( 'Field Size (Characters)', 'arforms-form-builder' ); ?></label>
			<div class="arf_field_option_content_cell_input">
				<input type="text" class="arf_field_option_input_text" onkeypress="return arflite_check_numeric_input(event,this)" name="max" id="fieldsize_phone_{arf_field_id}"/>
				<span class="arf_field_option_input_note">
					<span class="arf_field_option_input_note_text"><?php echo esc_html__( 'Maximum', 'arforms-form-builder' ); ?></span>
				</span>
			</div>
		</div>	    
		<div class="arf_field_option_content_cell" data-sort="-1" id="fieldsize">
			<label class="arf_field_option_content_cell_label"><?php echo esc_html__( 'Field Size (Characters)', 'arforms-form-builder' ); ?></label>
			<div class="arf_field_option_content_cell_input">
				<input type="text" id="arf_input_min_width_{arf_field_id}" onkeypress="return arflite_check_numeric_input(event,this)" class="arf_field_option_input_text arf_half_width" name="minlength" />
				<input type="text" id="arf_input_max_width_{arf_field_id}" onkeypress="return arflite_check_numeric_input(event,this)" data-id="arf_input_max_width_{arf_field_id}" class="arf_field_option_input_text arf_half_width" name="max" />
				<span class="arf_field_option_input_note">
					<span class="arf_field_option_input_note_text arf_half_width"><?php echo esc_html__( 'Minimum', 'arforms-form-builder' ); ?></span>
					<span class="arf_field_option_input_note_text arf_half_width"><?php echo esc_html__( 'Maximum', 'arforms-form-builder' ); ?></span>
				</span>
				<span id="arflite_field_option_error_note"></span>
			</div>
		</div>
			<div class="arf_field_option_content_cell" data-sort="-1" id="customwidth">
				<label class="arf_field_option_content_cell_label"><?php echo esc_html__( 'Field Custom Width', 'arforms-form-builder' ); ?></label>
				<div class="arf_field_option_content_cell_input">
					<input id="frm_custom_width_field_{arf_field_id}_div" onkeypress="return arflite_check_numeric_input(event,this)" type="text" class="arf_field_option_input_text arfwidth80" name="field_width"  />
					<div class="arfwidthpx">px</div>
				</div>
			</div>
			<div class="arf_field_option_content_cell" data-sort="-1" id="minlength_message">
				<label class="arf_field_option_content_cell_label"><?php echo esc_html__( 'Message for minimum length', 'arforms-form-builder' ); ?></label>
				<div class="arf_field_option_content_cell_input">
					<input id="arf_min_length_message_{arf_field_id}" type="text" class="arf_field_option_input_text" name="minlength_message" />
				</div>
			</div>
			<div class="arf_field_option_content_cell" data-sort="-1" id="placeholdertext">
				<label class="arf_field_option_content_cell_label"><?php echo esc_html__( 'Placeholder Text', 'arforms-form-builder' ); ?></label>
				<div class="arf_field_option_content_cell_input">
					<input type="text" class="arf_field_option_input_text" id="arf_placeholder_text_{arf_field_id}" name="placeholdertext" />
				</div>
			</div>
			<div class="arf_field_option_content_cell" data-sort="-1" id="default_value">
				<label class="arf_field_option_content_cell_label"><?php echo esc_html__( 'Default Value', 'arforms-form-builder' ); ?></label>
				<div class="arf_field_option_content_cell_input">
					<input type="text" class="arf_field_option_input_text" id="arf_default_value_text_{arf_field_id}" name="default_value" />
				</div>
			</div>
			<div class="arf_field_option_content_cell" data-sort="-1" id="cleartextonfocus">
				<label class="arf_field_option_content_cell_label"><?php echo esc_html__( 'Clear default text on focus', 'arforms-form-builder' ); ?></label>
				<div class="arf_field_option_content_cell_input">
					<label class="arf_js_switch_label">
						<span><?php echo esc_html__( 'No', 'arforms-form-builder' ); ?>&nbsp;</span>
					</label>
					<span class="arf_js_switch_wrapper arf_no_transition">
						<input class="js-switch frm_clear_field_{arf_field_id}" name="frm_clear_field" id="frm_clear_field_{arf_field_id}" onchange='arflitecleardefaultvalueonfocus("{arf_field_id}", 0, 2)' value="1" type="checkbox" />
						<span class="arf_js_switch"></span>
					</span>
					<label class="arf_js_switch_label">
						<span><?php echo esc_html__( 'Yes', 'arforms-form-builder' ); ?>&nbsp;</span>
					</label>
				</div>
			</div>
			<div class="arf_field_option_content_cell" data-sort="-1" id="validatedefaultvalue">
				<label class="arf_field_option_content_cell_label"><?php echo esc_html__( 'Validate default value', 'arforms-form-builder' ); ?></label>
				<div class="arf_field_option_content_cell_input">
					<label class="arf_js_switch_label">
						<span><?php echo esc_html__( 'No', 'arforms-form-builder' ); ?>&nbsp;</span>
					</label>
					<span class="arf_js_switch_wrapper arf_no_transition">
						<input class="js-switch frm_default_blank_{arf_field_id}" name="frm_default_blank" id="frm_default_blank_{arf_field_id}" onchange='arflitedefaultblank("{arf_field_id}", 0, 2)' value="1" type="checkbox" />
						<span class="arf_js_switch"></span>
					</span>
					<label class="arf_js_switch_label"><span><?php echo esc_html__( 'Yes', 'arforms-form-builder' ); ?>&nbsp;</span></label>
				</div>
			</div>
			<div class="arf_field_option_content_cell" data-sort="-1" id="fielddescription">
				<label class="arf_field_option_content_cell_label"><?php echo esc_html__( 'Field description', 'arforms-form-builder' ); ?></label>
				<div class="arf_field_option_content_cell_input">
					<input type="text" id="arf_field_description_input_{arf_field_id}" class="arf_field_option_input_text" name="description" />
				</div>
			</div>

			<div class="arf_field_option_content_cell" data-sort="-1" id="arf_prefix">
				<label class="arf_field_option_content_cell_label"><?php echo esc_html__( 'Add icon (Bootstrap style)', 'arforms-form-builder' ); ?></label>
				<div class="arf_field_option_content_cell_input">
					<div class="arf_field_prefix_suffix_wrapper" id="arf_field_prefix_suffix_wrapper_{arf_field_id}">
						<div class="arf_prefix_wrapper">
							<div class="arf_prefix_suffix_container_wrapper" data-action="edit" data-field="prefix" field-id="{arf_field_id}" id="arf_edit_prefix_{arf_field_id}" data-toggle="arfmodal" href="#arf_fontawesome_modal" data-field_type="text">
								<div class="arf_prefix_container" id="arf_select_prefix_{arf_field_id}"><?php echo esc_html__( 'No icon', 'arforms-form-builder' ); ?></div>
								<div class="arf_prefix_suffix_action_container">
									<div class="arf_prefix_suffix_action arfprefix-suffix-icondiv arflite_icon_select" title="Change Icon">
										<i class="fas fa-caret-down fa-lg"></i>
									</div>
								</div>
							</div>
						</div>
						<div class="arf_suffix_wrapper">
							<div class="arf_prefix_suffix_container_wrapper" data-action="edit" data-field="suffix" field-id="{arf_field_id}" id="arf_edit_suffix_{arf_field_id}" data-toggle="arfmodal" href="#arf_fontawesome_modal" data-field_type="text">
								<div class="arf_suffix_container" id="arf_select_suffix_{arf_field_id}"><?php echo esc_html__( 'No icon', 'arforms-form-builder' ); ?></div>
								<div class="arf_prefix_suffix_action_container">
									<div class="arf_prefix_suffix_action prefix-suffix-icon arflite_icon_select" title="Change Icon" >
										<i class="fas fa-caret-down fa-lg"></i>
									</div>
								</div>  
							</div>
						</div>
						<input type="hidden" name="enable_arf_prefix" id="enable_arf_prefix_{arf_field_id}" />
						<input type="hidden" name="arf_prefix_icon" id="arf_prefix_icon_{arf_field_id}" />
						<input type="hidden" name="enable_arf_suffix" id="enable_arf_suffix_{arf_field_id}" />
						<input type="hidden" name="arf_suffix_icon" id="arf_suffix_icon_{arf_field_id}" />
					</div>
					<span class="arf_field_option_input_note">
						<span class="arf_field_option_input_note_text arf_half_width"><?php echo esc_html__( 'Prefix', 'arforms-form-builder' ); ?></span>
						<span class="arf_field_option_input_note_text arf_half_width "><?php echo esc_html__( 'Suffix', 'arforms-form-builder' ); ?></span>
					</span>
				</div>
			</div>
			<div class="arf_field_option_content_cell" data-sort="-1" id="alignment">
				<label class="arf_field_option_content_cell_label"><?php echo esc_html__( 'Alignment', 'arforms-form-builder' ); ?></label>
				<div class="arf_field_option_content_cell_input">
					<span class='arf_custom_radio_wrapper arf_field_option_radio'>
						<input type="radio" class="arf_custom_radio" name="align" id="arf_field_align_{arf_field_id}_1" value="inline" data-id="{arf_field_id}" />
						<svg width='18px' height='18px'>
							 <?php echo ARFLITE_CUSTOM_UNCHECKEDRADIO_ICON; //phpcs:ignoreFile ?>
							 <?php echo ARFLITE_CUSTOM_CHECKEDRADIO_ICON; //phpcs:ignoreFile ?>
						</svg>
						<label class="arf_custom_radio_label" for="arf_field_align_{arf_field_id}_1"><?php echo esc_html__( 'Inline', 'arforms-form-builder' ); ?></label>
					</span>
					<span class="arf_custom_radio_wrapper arf_field_option_radio">
						<input type="radio" class="arf_custom_radio" name="align" id="arf_field_align_{arf_field_id}_2" value="block" data-id="{arf_field_id}" checked="checked" />
						<svg width='18px' height='18px'>
							 <?php echo ARFLITE_CUSTOM_UNCHECKEDRADIO_ICON; //phpcs:ignoreFile ?>
							 <?php echo ARFLITE_CUSTOM_CHECKEDRADIO_ICON; //phpcs:ignoreFile ?>
						</svg>
						<label class="arf_custom_radio_label" for="arf_field_align_{arf_field_id}_2"><?php echo esc_html__( '1 Column', 'arforms-form-builder' ); ?></label>
					</span>
					<span class="arf_custom_radio_wrapper arf_field_option_radio">
						<input type="radio" class="arf_custom_radio" name="align" id="arf_field_align_{arf_field_id}_3" value="arf_col_2" data-id="{arf_field_id}" />
						<svg width='18px' height='18px'>
							 <?php echo ARFLITE_CUSTOM_UNCHECKEDRADIO_ICON; //phpcs:ignoreFile ?>
							 <?php echo ARFLITE_CUSTOM_CHECKEDRADIO_ICON; //phpcs:ignoreFile ?>
						</svg>
						<label class="arf_custom_radio_label" for="arf_field_align_{arf_field_id}_3"><?php echo esc_html__( '2 Column', 'arforms-form-builder' ); ?></label>
					</span>
					<span class="arf_custom_radio_wrapper arf_field_option_radio">
						<input type="radio" class="arf_custom_radio" name="align" id="arf_field_align_{arf_field_id}_4" value="arf_col_3" data-id="{arf_field_id}" />
						<svg width='18px' height='18px'>
							 <?php echo ARFLITE_CUSTOM_UNCHECKEDRADIO_ICON; //phpcs:ignoreFile ?>
							 <?php echo ARFLITE_CUSTOM_CHECKEDRADIO_ICON; //phpcs:ignoreFile ?>
						</svg>
						<label class="arf_custom_radio_label" for="arf_field_align_{arf_field_id}_4"><?php echo esc_html__( '3 Column', 'arforms-form-builder' ); ?></label>
					</span>
					<span class="arf_custom_radio_wrapper arf_field_option_radio">
						<input type="radio" class="arf_custom_radio" name="align" id="arf_field_align_{arf_field_id}_5" value="arf_col_4" data-id="{arf_field_id}" />
						<svg width='18px' height='18px'>
							 <?php echo ARFLITE_CUSTOM_UNCHECKEDRADIO_ICON; //phpcs:ignoreFile ?>
							 <?php echo ARFLITE_CUSTOM_CHECKEDRADIO_ICON; //phpcs:ignoreFile ?>
						</svg>
						<label class="arf_custom_radio_label" for="arf_field_align_{arf_field_id}_5"><?php echo esc_html__( '4 Column', 'arforms-form-builder' ); ?></label>
					</span>
				</div>
			</div>		
			
			<div class="arf_field_option_content_cell arf_full_width_cell" id="allowedphonetype">
				<label class="arf_field_option_content_cell_label"><?php echo esc_html__( 'Enable country flag dropdown', 'arforms-form-builder' ); ?></label>
				<div class="arf_field_option_content_cell_input">
					<label class="arf_js_switch_label">
						<span><?php echo esc_html__( 'NO', 'arforms-form-builder' ); ?>&nbsp;</span>
					</label>
					<span class="arf_js_switch_wrapper arf_no_transition">
						<input type="checkbox" class="js-switch phonetype_{arf_field_id} phone_type_switch" name="phonetype" id="phonetype_{arf_field_id}" value="1" onclick="arfliteshowphoneformatdiv('phoneformate_box_{arf_field_id}', this, 0, '#');" checked="checked" />
						<span class="arf_js_switch"></span>
					</span>
					<label class="arf_js_switch_label">
						<span>&nbsp;<?php echo esc_html__( 'YES', 'arforms-form-builder' ); ?></span>
					</label>
				</div>

				<div class="arf_field_option_content_cell_input" id="phoneformate_box_{arf_field_id}">
					

					<div class="arf_file_upload_restrict_box phonetype_box_{arf_field_id}" id="phonetype_box_{arf_field_id}">
						<div class="main_allowed_types" id="main_allowed_types">
							<div class="arffieldoptionslist arflite_width_100">
								<div class="alignleft">
								<?php
									$phonetype_arr = arflite_get_country_code();
									$c             = 0;
								foreach ( $phonetype_arr as $key => $value ) {
									?>
										<div class="arf_file_type_restriction_item arf_phone_type_item">
											<div class="arf_custom_checkbox_div">
												<div class="arf_custom_checkbox_wrapper">
													<input type="checkbox" id="field_options[phtypes_{arf_field_id}][<?php echo esc_attr( $value['code'] ); ?>]" name="phtypes_<?php echo esc_attr( $value['code'] ); ?>" value="<?php echo esc_attr( $value['dial_code'] ); ?>" class="phone_type_checkbox phtypes_<?php echo esc_attr( $value['code'] ); ?>_{arf_field_id}" />
													<svg width="18px" height="18px">
													<?php echo ARFLITE_CUSTOM_UNCHECKED_ICON; //phpcs:ignoreFile ?>
													<?php echo ARFLITE_CUSTOM_CHECKED_ICON; //phpcs:ignoreFile ?>
													</svg>
												</div>
											</div>
											<span><label for="field_options[phtypes_{arf_field_id}][<?php echo esc_attr( $value['code'] ); ?>]" class="howto"><span></span><?php echo str_replace( '|', ', ', esc_html($value['name']) ); ?></label></span>
										</div>
										<?php
										$c++;
										unset( $key );
										unset( $value );
								}
								?>
								</div>
							</div>
						</div>

					</div>
					<div class="arf_radio_wrapper arf_radio_wrapper_padding">
						<span class="arf_check_all_label">
							<a href="javascript:void(0)" onclick="arfliteselectphonetypediv('arf_phone_type_item', 1, 1, '.')"><?php echo esc_html__( 'Check All', 'arforms-form-builder' ); ?></a>
						</span>
					</div>
					<div class="arf_radio_wrapper arf_radio_wrapper_padding">
						<span class="arf_check_all_label">
							<a href="javascript:void(0)" onclick="arfliteselectphonetypediv('arf_phone_type_item', 0, 1, '.')"><?php echo esc_html__( 'Uncheck All', 'arforms-form-builder' ); ?></a>
						</span>
					</div>
				</div>
			</div>

			<div class="arf_field_option_content_cell" data-sort="-1" id="country_validation">
				<label class="arf_field_option_content_cell_label"><?php echo esc_html__( 'Country wise number validation', 'arforms-form-builder' ); ?></label>
				<div class="arf_field_option_content_cell_input">
					<label class="arf_js_switch_label">
						<span><?php echo esc_html__( 'NO', 'arforms-form-builder' ); ?>&nbsp;</span>
					</label>
					<span class="arf_js_switch_wrapper arf_no_transition">
						<input type="checkbox" class="js-switch country_validation_{arf_field_id} country_validation" name="country_validation" id="country_validation_{arf_field_id}" value="1" checked="checked" />
						<span class="arf_js_switch"></span>
					</span>
					<label class="arf_js_switch_label">
						<span>&nbsp;<?php echo esc_html__( 'YES', 'arforms-form-builder' ); ?></span>
					</label>
				</div>
				<input type='hidden' name='default_country' id='default_country_{arf_field_id}' value='' />
			</div>

			<div class="arf_field_option_content_cell" data-sort="-1" id="invalidmessage">
				<label class="arf_field_option_content_cell_label"><?php echo esc_html__( 'Message for invalid submission', 'arforms-form-builder' ); ?></label>
				<div class="arf_field_option_content_cell_input">
					<input type="text" name="invalid" class="arf_field_option_input_text" value="" id="invalid_message_{arf_field_id}" >
				</div>
			</div>
			<div class="arf_field_option_content_cell" data-sort="-1" id="emailfieldsize">
				<label class="arf_field_option_content_cell_label"><?php echo esc_html__( 'Field Size (Characters)', 'arforms-form-builder' ); ?></label>
				<div class="arf_field_option_content_cell_input">
					<input type="text" data-id="arf_input_max_width_{arf_field_id}" onkeypress="return arflite_check_numeric_input(event,this)" class="arf_field_option_input_text" name="max" />
					<span class="arf_field_option_input_note">
						<span class="arf_field_option_input_note_text"><?php echo esc_html__( 'Maximum', 'arforms-form-builder' ); ?></span>
					</span>
				</div>
			</div>
			<div class="arf_field_option_content_cell" data-sort="-1" id="confirm_email">
				<label class="arf_field_option_content_cell_label"><?php echo esc_html__( 'Confirm Email', 'arforms-form-builder' ); ?></label>
				<div class="arf_field_option_content_cell_input">
					<label class="arf_js_switch_label">
						<span><?php echo esc_html__( 'NO', 'arforms-form-builder' ); ?>&nbsp;</span>
					</label>
					<span class="arf_js_switch_wrapper arf_no_transition">
						<input type="checkbox" class="js-switch confirm_email_{arf_field_id}" name="confirm_email" onchange="arflitechangeconfirmemail('{arf_field_id}');" id="confirm_email_{arf_field_id}" data-field_id={arf_field_id} value="1" />
						<span class="arf_js_switch"></span>
					</span>
					<label class="arf_js_switch_label">
						<span>&nbsp;<?php echo esc_html__( 'YES', 'arforms-form-builder' ); ?></span>
					</label>
				</div>
			</div>
			<div class="arf_field_option_content_cell" data-sort="-1" id="confirm_email_label">
				<label class="arf_field_option_content_cell_label"><?php echo esc_html__( 'Confirm Email label', 'arforms-form-builder' ); ?></label>
				<div class="arf_field_option_content_cell_input">
					<input type="text" id="confirm_email_label_{arf_field_id}" name="confirm_email_label" class="arf_field_option_input_text" />
				</div>
			</div>
			<div class="arf_field_option_content_cell" data-sort="-1" id="invalid_confirm_email">
				<label class="arf_field_option_content_cell_label"><?php echo esc_html__( 'Message for invalid confirm email', 'arforms-form-builder' ); ?></label>
				<div class="arf_field_option_content_cell_input">
					<input type="text" id="invalid_confirm_email_{arf_field_id}" class="arf_field_option_input_text" name="invalid_confirm_email" />
				</div>
			</div>
			<div class="arf_field_option_content_cell" data-sort="-1" id="confirm_email_placeholder" >
				<label class="arf_field_option_content_cell_label"><?php echo esc_html__( 'Confirm email placeholder', 'arforms-form-builder' ); ?></label>
				<div class="arf_field_option_content_cell_input">
					<input type="text" id="confirm_email_placeholder_{arf_field_id}" class="arf_field_option_input_text" name="confirm_email_placeholder" />
				</div>
			</div>
			<div class="arf_field_option_content_cell" data-sort="-1" id="numberrange">
				<label class="arf_field_option_content_cell_label"><?php echo esc_html__( 'Number Range', 'arforms-form-builder' ); ?></label>
				<div class="arf_field_option_content_cell_input">
					<input type="text" name="minnum" id="arf_minnum_{arf_field_id}" onkeypress="return arflite_check_decimal_point(event,this)" class="arf_field_option_input_text arf_half_width" value="0" size="5">
					<input type="text" name="maxnum" id="arf_maxnum_{arf_field_id}" onkeypress="return arflite_check_decimal_point(event,this)"  class="arf_field_option_input_text arf_half_width" value="0" size="5">
					<span class="arf_field_option_input_note">
						<span class="arf_field_option_input_note_text arf_half_width"><?php echo esc_html__( 'Minimum', 'arforms-form-builder' ); ?></span>
						<span class="arf_field_option_input_note_text arf_half_width"><?php echo esc_html__( 'Maximum', 'arforms-form-builder' ); ?></span>
					</span>
					<span id="arflite_number_field_option_error_note"></span>
				</div>
			</div>
			<div class="arf_field_option_content_cell" data-sort="-1" id="phone_validation">
				<label class="arf_field_option_content_cell_label"><?php echo esc_html__( 'Default Number format', 'arforms-form-builder' ); ?></label>
				<div class="arf_field_option_content_cell_input">
					<?php
						global $arflitemaincontroller;

						$phone_options = array(
							'international'       => '1234567890',
							'custom_validation_1' => '(123)456 7890',
							'custom_validation_2' => '(123) 456 7890',
							'custom_validation_3' => '(123)456-7890',
							'custom_validation_4' => '(123) 456-7890',
							'custom_validation_5' => '123 456 7890',
							'custom_validation_6' => '123 456-7890',
							'custom_validation_7' => '123-456-7890',
							'custom_validation_8' => '01234 123 456',
							'custom_validation_9' => '01234 123456',
						);

						echo $arflitemaincontroller->arflite_selectpicker_dom( 'phone_validation', 'phone_validation_{arf_field_id}', '', '', 'international', array(), $phone_options ); //phpcs:ignore
						?>
				</div>
			</div>
			<div class="arf_field_option_content_cell" data-sort="-1" id="calendarlocalization">
				<?php
				$locales = array(
					'en'      => addslashes( esc_html__( 'English/Western', 'arforms-form-builder' ) ),
					'af'      => addslashes( esc_html__( 'Afrikaans', 'arforms-form-builder' ) ),
					'sq'      => addslashes( esc_html__( 'Albanian', 'arforms-form-builder' ) ),
					'ar'      => addslashes( esc_html__( 'Arabic', 'arforms-form-builder' ) ),
					'hy-am'   => addslashes( esc_html__( 'Armenian', 'arforms-form-builder' ) ),
					'az'      => addslashes( esc_html__( 'Azerbaijani', 'arforms-form-builder' ) ),
					'eu'      => addslashes( esc_html__( 'Basque', 'arforms-form-builder' ) ),
					'bs'      => addslashes( esc_html__( 'Bosnian', 'arforms-form-builder' ) ),
					'bg'      => addslashes( esc_html__( 'Bulgarian', 'arforms-form-builder' ) ),
					'ca'      => addslashes( esc_html__( 'Catalan', 'arforms-form-builder' ) ),
					'zh-CN'   => addslashes( esc_html__( 'Chinese Simplified', 'arforms-form-builder' ) ),
					'zh-TW'   => addslashes( esc_html__( 'Chinese Traditional', 'arforms-form-builder' ) ),
					'hr'      => addslashes( esc_html__( 'Croatian', 'arforms-form-builder' ) ),
					'cs'      => addslashes( esc_html__( 'Czech', 'arforms-form-builder' ) ),
					'da'      => addslashes( esc_html__( 'Danish', 'arforms-form-builder' ) ),
					'nl'      => addslashes( esc_html__( 'Dutch', 'arforms-form-builder' ) ),
					'en-GB'   => addslashes( esc_html__( 'English/UK', 'arforms-form-builder' ) ),
					'eo'      => addslashes( esc_html__( 'Esperanto', 'arforms-form-builder' ) ),
					'et'      => addslashes( esc_html__( 'Estonian', 'arforms-form-builder' ) ),
					'fo'      => addslashes( esc_html__( 'Faroese', 'arforms-form-builder' ) ),
					'fa'      => addslashes( esc_html__( 'Farsi/Persian', 'arforms-form-builder' ) ),
					'fi'      => addslashes( esc_html__( 'Finnish', 'arforms-form-builder' ) ),
					'fr'      => addslashes( esc_html__( 'French', 'arforms-form-builder' ) ),
					'fr-CH'   => addslashes( esc_html__( 'French/Swiss', 'arforms-form-builder' ) ),
					'de'      => addslashes( esc_html__( 'German', 'arforms-form-builder' ) ),
					'el'      => addslashes( esc_html__( 'Greek', 'arforms-form-builder' ) ),
					'he'      => addslashes( esc_html__( 'Hebrew', 'arforms-form-builder' ) ),
					'hu'      => addslashes( esc_html__( 'Hungarian', 'arforms-form-builder' ) ),
					'is'      => addslashes( esc_html__( 'Icelandic', 'arforms-form-builder' ) ),
					'it'      => addslashes( esc_html__( 'Italian', 'arforms-form-builder' ) ),
					'ja'      => addslashes( esc_html__( 'Japanese', 'arforms-form-builder' ) ),
					'ko'      => addslashes( esc_html__( 'Korean', 'arforms-form-builder' ) ),
					'lv'      => addslashes( esc_html__( 'Latvian', 'arforms-form-builder' ) ),
					'lt'      => addslashes( esc_html__( 'Lithuanian', 'arforms-form-builder' ) ),
					'nb'      => addslashes( esc_html__( 'Norwegian', 'arforms-form-builder' ) ),
					'pl'      => addslashes( esc_html__( 'Polish', 'arforms-form-builder' ) ),
					'pt-BR'   => addslashes( esc_html__( 'Portuguese/Brazilian', 'arforms-form-builder' ) ),
					'ro'      => addslashes( esc_html__( 'Romanian', 'arforms-form-builder' ) ),
					'ru'      => addslashes( esc_html__( 'Russian', 'arforms-form-builder' ) ),
					'sr'      => addslashes( esc_html__( 'Serbian', 'arforms-form-builder' ) ),
					'sr-cyrl' => addslashes( esc_html__( 'Serbian Cyrillic', 'arforms-form-builder' ) ),
					'sk'      => addslashes( esc_html__( 'Slovak', 'arforms-form-builder' ) ),
					'sl'      => addslashes( esc_html__( 'Slovenian', 'arforms-form-builder' ) ),
					'es'      => addslashes( esc_html__( 'Spanish', 'arforms-form-builder' ) ),
					'sv'      => addslashes( esc_html__( 'Swedish', 'arforms-form-builder' ) ),
					'ta'      => addslashes( esc_html__( 'Tamil', 'arforms-form-builder' ) ),
					'th'      => addslashes( esc_html__( 'Thai', 'arforms-form-builder' ) ),
					'tr'      => addslashes( esc_html__( 'Turkish', 'arforms-form-builder' ) ),
					'uk'      => addslashes( esc_html__( 'Ukrainian', 'arforms-form-builder' ) ),
					'vi'      => addslashes( esc_html__( 'Vietnamese', 'arforms-form-builder' ) ),
				);
				?>
				<label class="arf_field_option_content_cell_label"><?php echo esc_html__( 'Calendar localization', 'arforms-form-builder' ); ?></label>
				<div class="arf_field_option_content_cell_input">
					<?php
						echo $arflitemaincontroller->arflite_selectpicker_dom( 'locale', 'field_date_locale-{arf_field_id}', '', '', 'en', array(), $locales ); //phpcs:ignore
					?>
				</div>
			</div>
			<div class="arf_field_option_content_cell" data-sort="-1" id="calendartimehideshow">
				<label class="arf_field_option_content_cell_label"><?php echo esc_html__( 'Show time picker', 'arforms-form-builder' ); ?></label>
				<div class="arf_field_option_content_cell_input">
					<label class="arf_js_switch_label">
						<span><?php echo esc_html__( 'NO', 'arforms-form-builder' ); ?>&nbsp;</span>
					</label>
					<span class="arf_js_switch_wrapper arf_no_transition">
						<input type="checkbox" class="js-switch show_time_calendar_{arf_field_id}" name="show_time_calendar" id="frm_show_time_calendar_field_{arf_field_id}" value="1" onchange='arflite_hide_show_time_picker_option("{arf_field_id}");' />
						<span class="arf_js_switch"></span>
					</span>
					<label class="arf_js_switch_label">
						<span>&nbsp;<?php echo esc_html__( 'YES', 'arforms-form-builder' ); ?></span>
					</label>
					<input type="hidden" name="frm_show_time_calendar_field_indicator" value="" />
				</div>
			</div>
			<div class="arf_field_option_content_cell arf_time_settings_{arf_field_id}" id="clocksetting">
				<label class="arf_field_option_content_cell_label"><?php echo esc_html__( 'Clock Settings', 'arforms-form-builder' ); ?></label>
				<div class="arf_field_option_content_cell_input">
					<?php
						$time_options = array(
							'12' => '12',
							'24' => '24',
						);
						$time_attr    = array( 'onchange' => 'javascript:arflitechangeclockhours(this.value, "{arf_field_key}", "{arf_field_id}", "");' );
						echo $arflitemaincontroller->arflite_selectpicker_dom( 'clock', 'field_time_clock-{arf_field_id}', '', 'width: 43%;', '12', $time_attr, $time_options ); //phpcs:ignore

						$step_options = array(
							'1'  => '1',
							'2'  => '2',
							'3'  => '3',
							'4'  => '4',
							'5'  => '5',
							'10' => '10',
							'15' => '15',
							'20' => '20',
							'25' => '25',
							'30' => '30',
						);
						echo $arflitemaincontroller->arflite_selectpicker_dom( 'step', 'time_step-{arf_field_id}', '', 'width: 43%; margin-left: 10px; clear: none;', '30', array(), $step_options ); //phpcs:ignore
						?>
					<span class="arf_field_option_input_note arf_time_field_options_note">
						<span class="arf_field_option_input_note_text arf_half_width"><?php echo esc_html__( 'Hour', 'arforms-form-builder' ); ?></span>
						<span class="arf_field_option_input_note_text arf_half_width"><?php echo esc_html__( 'Minute', 'arforms-form-builder' ); ?></span>
					</span>
				</div>
			</div>
			<div class="arf_field_option_content_cell arf_full_width_cell" id="offdays">
				<label class="arf_field_option_content_cell_label"><?php echo esc_html__( 'Off days', 'arforms-form-builder' ); ?></label>
				<div class="arf_field_option_content_cell_input">
					<input type="hidden" name="off_days" id="arf_off_days_{arf_field_id}" class="txtstandardnew arf_date_days_val" value="" size="4"/>
					<div class="arf_date_days_btn" day_val="0"><?php echo esc_html__( 'Sunday', 'arforms-form-builder' ); ?></div>
					<div class="arf_date_days_btn" day_val="1"><?php echo esc_html__( 'Monday', 'arforms-form-builder' ); ?></div>
					<div class="arf_date_days_btn" day_val="2"><?php echo esc_html__( 'Tuesday', 'arforms-form-builder' ); ?></div>
					<div class="arf_date_days_btn" day_val="3"><?php echo esc_html__( 'Wednesday', 'arforms-form-builder' ); ?></div>
					<div class="arf_date_days_btn" day_val="4"><?php echo esc_html__( 'Thursday', 'arforms-form-builder' ); ?></div>
					<div class="arf_date_days_btn" day_val="5"><?php echo esc_html__( 'Friday', 'arforms-form-builder' ); ?></div>
					<div class="arf_date_days_btn" day_val="6"><?php echo esc_html__( 'Saturday', 'arforms-form-builder' ); ?></div>
				</div>
			</div>
			<div class="arf_field_option_content_cell" data-sort="-1" id="daterange">
				<label class="arf_field_option_content_cell_label"><?php echo esc_html__( 'Date range', 'arforms-form-builder' ); ?></label>
				<div class="arf_field_option_content_cell_input">
					<label class="arf_field_option_content_cell_label"><?php echo esc_html__( 'From', 'arforms-form-builder' ); ?> &nbsp;&nbsp;
					<span class="arf_popup_tooltip_main arf_restricted_control" data-title='<?php echo esc_html__( 'to set dynamic minimum date please use the below short-code', 'arforms-form-builder' ) . '<strong class="arf_restricted_control"> {arf_min_date value="10" unit="Y"} </strong>'; ?><br><br><?php echo esc_html__( 'the above shortcode will set minimum date to 10 year before current date. ( possible values for unit are Y - year, M - month, and D - days ).', 'arforms-form-builder' ); ?><span class="arflite_pro_version_notice arf_restricted_control">(Premium)</span>'>
							<img src="<?php echo esc_url( ARFLITEIMAGESURL ); ?>/tooltips-icon.png" alt="?" style="position: absolute;"/>
						</span>
					</label>
					<input type="text" id="arf_start_date_{arf_field_id}" name="start_date" class="arf_field_option_input_text" value="" size="4" />
					<span class="arf_field_option_input_note arf_date_range_option_note">
						<label class="arf_js_switch_label">
							<span class="arf_current_date_hide_show_label"><?php echo esc_html__( 'Set Current Date', 'arforms-form-builder' ); ?>:&nbsp;</span>
							<span><?php echo esc_html__( 'NO', 'arforms-form-builder' ); ?>&nbsp;</span>
						</label>
						<span class="arf_js_switch_wrapper arf_no_transition">
							<input type="checkbox" class="js-switch arf_show_min_current_date_{arf_field_id}" name="arf_show_min_current_date" id="frm_arf_show_min_current_date_field_{arf_field_id}" onchange='arflitemincurrentdatefieldfunction("{arf_field_id}", "", "2")' value="1" />
							<span class="arf_js_switch"></span>
						</span>
						<label class="arf_js_switch_label">
							<span>&nbsp;<?php echo esc_html__( 'YES', 'arforms-form-builder' ); ?></span>
						</label>
						<span class="arf_field_option_input_note arf_time_field_options_note">
						<span class="arf_field_option_input_note_text"><?php echo esc_html__( 'Min Date e.g. 20/01/2000', 'arforms-form-builder' ); ?></span>
						</span>
						<input type="hidden" name="frm_arf_show_min_current_date_field_indicator" value="" />
					</span>
				</div>
			</div>
			<div class="arf_field_option_content_cell" data-sort="-1" id="daterange">
				<label class="arf_field_option_content_cell_label">&nbsp;</label>
				<div class="arf_field_option_content_cell_input">
					<label class="arf_field_option_content_cell_label"><?php echo esc_html__( 'To', 'arforms-form-builder' ); ?> &nbsp;&nbsp;
					<span class="arf_popup_tooltip_main arf_restricted_control" data-title='<?php echo esc_html__( 'to set dynamic minimum date please use the below short-code', 'arforms-form-builder' ) . '<strong class="arf_restricted_control"> {arf_max_date value="10" unit="Y"} </strong>'; ?><br><br><?php echo esc_html__( 'the above shortcode will set minimum date to 10 year before current date. ( possible values for unit are Y - year, M - month, and D - days ).', 'arforms-form-builder' ); ?><span class="arflite_pro_version_notice arf_restricted_control">(Premium)</span>'>
							<img src="<?php echo esc_url( ARFLITEIMAGESURL ); ?>/tooltips-icon.png" alt="?" style="position: absolute;"/>
						</span>
					</label>
					<input type="text" id="arf_end_date_{arf_field_id}" name="end_date" class="arf_field_option_input_text" value="" size="4" />
					<span class="arf_field_option_input_note arf_date_range_option_note">
						<label class="arf_js_switch_label">
							<span class="arf_current_date_hide_show_label"><?php echo esc_html__( 'Set Current Date', 'arforms-form-builder' ); ?>:&nbsp;</span>
							<span><?php echo esc_html__( 'NO', 'arforms-form-builder' ); ?>&nbsp;</span>
						</label>
						<span class="arf_js_switch_wrapper arf_no_transition">
							<input type="checkbox" class="js-switch arf_show_max_current_date_{arf_field_id}" name="arf_show_max_current_date" id="frm_arf_show_max_current_date_field_{arf_field_id}" onchange='arflitemaxcurrentdatefieldfunction("{arf_field_id}", "", "2")' value="1" />
							<span class="arf_js_switch"></span>
						</span>
						<label class="arf_js_switch_label">
							<span>&nbsp;<?php echo esc_html__( 'YES', 'arforms-form-builder' ); ?></span>
						</label>
						<span class="arf_field_option_input_note arf_time_field_options_note">
						<span class="arf_field_option_input_note_text"><?php echo esc_html__( 'Max Date e.g. 31/12/2020', 'arforms-form-builder' ); ?></span>
						</span>
					</span>
				</div>
			</div>
			<div class="arf_field_option_content_cell" data-sort="-1" id="daterange"></div>
			<div class="arf_field_option_content_cell" data-sort="-1" id="set_default_selected_date">
				<label class="arf_field_option_content_cell_label"><?php echo esc_html__( 'Set default date', 'arforms-form-builder' ); ?></label>
				<div class="arf_field_option_content_cell_input">
					<input type="text" name="selectdefaultdate" id="set_current_date_field_{arf_field_id}" class="arf_field_option_input_text" value="" />
					<div class="arf_field_option_input_note arf_date_range_option_note">
						<label class="arf_js_switch_label">
							<span class="arf_current_date_hide_show_label"><?php echo esc_html__( 'Set Current Date:', 'arforms-form-builder' ); ?>&nbsp;</span>
							<span><?php echo esc_html__( 'NO', 'arforms-form-builder' ); ?>&nbsp;
						</label>
						<label class="arf_js_switch_wrapper arf_no_transition">
							<input type="checkbox" class="js-switch arf_set_current_date currentdefaultdate_{arf_field_id}" name="currentdefaultdate" id="currentdefaultdate_{arf_field_id}" value="1" />
							<span class="arf_js_switch"></span>
						</label>
						<label class="arf_js_switch_label">
							<span><?php echo esc_html__( 'Yes', 'arforms-form-builder' ); ?></span>
						</label>
						<span class="arf_field_option_input_note arf_time_field_options_note" >
							<?php
							if ( $newarr['date_format'] == 'MM/DD/YYYY' ) {
								$date = date( 'd/m/Y', current_time( 'timestamp' ) );
							} elseif ( $newarr['date_format'] == 'MMM D, YYYY' ) {
								$date = date( 'M d, Y', current_time( 'timestamp' ) );
							} elseif ( $newarr['date_format'] == 'MMMM D, YYYY' ) {
								$date = date( 'F d, Y', current_time( 'timestamp' ) );
							} elseif ( $newarr['date_format'] == 'D.MM.YYYY' ) {
								$date = date( 'd.m.Y', current_time( 'timestamp' ) );
							} elseif ( $newarr['date_format'] == 'D.MMMM.YY' ) {
								$date = date( 'd.F.y', current_time( 'timestamp' ) );
							} elseif ( $newarr['date_format'] == 'YYYY.MM.D' ) {
								$date = date( 'Y.m.d', current_time( 'timestamp' ) );
							} elseif ( $newarr['date_format'] == 'D. MMMM YYYY' ) {
								$date = date( 'd. F Y', current_time( 'timestamp' ) );
							} else {
								$date = date( 'd/m/Y', current_time( 'timestamp' ) );
							}
							$set_date_eg = esc_html__('Set Date e.g.','arforms-form-builder');

							$date_eg = $set_date_eg .$date;
							?>
						<span class="arf_field_option_input_note_text" id='arf_date_field_set_def_date'><?php echo esc_html( $date_eg ); ?></span>

						</span>
					</div>
					<input type="hidden" name="currentdefaultdate" class="arf_field_option_input_text" id="currentdefaultdatestatus_{arf_field_id}" value=""/>
				</div>
			</div>            
		
			<div class="arf_field_option_content_cell_htmlcontent arf_field_option_content_cell" data-sort="-1" id="htmlcontent">
				<div class="arf_field_option_content_cell arf_field_height20 arf_full_width_cell">
					<span class="arf_js_switch_wrapper">
						<input type="checkbox" class="js-switch arf_restricted_control" name="enable_total" id="arfenable_total_{arf_field_id}" value="1" />                        
						<span class="arf_js_switch"></span>
					</span>
					<label class="arf_js_switch_label" for="arfenable_total_{arf_field_id}">
						<span>&nbsp;<?php echo esc_html__( 'Enable Running Total', 'arforms-form-builder' ); ?></span>
						<span class="arflite_pro_version_notice arflite_pro_notice_with_title">(Availabel in Premium)</span>
					</label>
				</div>

				<label class="arf_field_option_content_cell_label"><?php echo esc_html__( 'Content', 'arforms-form-builder' ); ?></label>
				
				<div class="arf_field_option_content_cell_input">
					
					<textarea id="arf_field_description_{arf_field_id}" name="description" class="arf_field_option_input_textarea html_field_description"></textarea>
					<span class="arf_field_option_input_note arfwidth50">
						 <span class="arf_field_option_input_note_text arfliterunning-total-note">[ <?php echo esc_html__( 'Embedded tags for youtube, map etc are supported.', 'arforms-form-builder' ); ?> ]
						</span>
					</span>
				</div>
			</div>
		
			<div class="arf_field_option_content_cell" data-sort="-1" id="fontfamilyoption">
				<label class="arf_field_option_content_cell_label"><?php echo esc_html__( 'Font family', 'arforms-form-builder' ); ?></label>
				<div class="arf_field_option_content_cell_input">
					<?php $arflite_get_googlefonts_data = $arfliteformcontroller->get_arflite_google_fonts(); ?>
					<input id="field_arf_section_font_{arf_field_id}" name="arf_section_font" value="Helvetica" type="hidden" >
					<dl class="arf_selectbox" data-name="arf_section_font" data-field-id="{arf_field_id}" data-id="field_arf_section_font_{arf_field_id}">
						<dt>
						<span>Helvetica</span>
						<input value="Helvetica" class="arf_autocomplete" type="text">
						<i class="fas fa-caret-down fa-lg"></i>
						</dt>
						<dd>
							<ul class="display-none-cls" data-id="field_arf_section_font_{arf_field_id}">
								<ol class="arp_selectbox_group_label"><?php echo esc_html__( 'Default Fonts', 'arforms-form-builder' ); ?></ol>
								<li class="arf_selectbox_option" data-value="Arial" data-label="Arial">Arial</li>
								<li class="arf_selectbox_option" data-value="Helvetica" data-label="Helvetica">Helvetica</li>
								<li class="arf_selectbox_option" data-value="sans-serif" data-label="sans-serif">sans-serif</li>
								<li class="arf_selectbox_option" data-value="Lucida Grande" data-label="Lucida Grande">Lucida Grande</li>
								<li class="arf_selectbox_option" data-value="Lucida Sans Unicode" data-label="Lucida Sans Unicode">Lucida Sans Unicode</li>
								<li class="arf_selectbox_option" data-value="Tahoma" data-label="Tahoma">Tahoma</li>
								<li class="arf_selectbox_option" data-value="Times New Roman" data-label="Times New Roman">Times New Roman</li>
								<li class="arf_selectbox_option" data-value="Courier New" data-label="Courier New">Courier New</li>
								<li class="arf_selectbox_option" data-value="Verdana" data-label="Verdana">Verdana</li>
								<li class="arf_selectbox_option" data-value="Geneva" data-label="Geneva">Geneva</li>
								<li class="arf_selectbox_option" data-value="Courier" data-label="Courier">Courier</li>
								<li class="arf_selectbox_option" data-value="Monospace" data-label="Monospace">Monospace</li>
								<li class="arf_selectbox_option" data-value="Times" data-label="Times">Times</li>
								<ol class="arp_selectbox_group_label"><?php echo esc_html__( 'Google Fonts', 'arforms-form-builder' ); ?></ol>
								<?php
								if ( count( $arflite_get_googlefonts_data ) > 0 ) {
									foreach ( $arflite_get_googlefonts_data as $goglefontsfamily ) {
										echo "<li class='arf_selectbox_option' data-value='" . esc_attr( $goglefontsfamily ) . "' data-label='" . esc_attr( $goglefontsfamily ) . "'>" . esc_attr($goglefontsfamily) . '</li>';
									}
								}
								?>
							</ul>
						</dd>
					</dl>
				</div>
			</div>
			<div class="arf_field_option_content_cell" data-sort="-1" id="fontsizeoption">
				<label class="arf_field_option_content_cell_label"><?php echo esc_html__( 'Font size', 'arforms-form-builder' ); ?></label>
				<div class="arf_field_option_content_cell_input">
					<input id="field_arf_section_font_size_{arf_field_id}" name="arf_section_font_size" value="16" type="hidden">
					<dl class="arf_selectbox" data-name="arf_section_font_size" data-field-id="{arf_field_id}" data-id="field_arf_section_font_size_{arf_field_id}">
						<dt>
						<span>16</span>
						<input value="16"  class="arf_autocomplete display-none-cls" type="text">
						<i class="fas fa-caret-down fa-lg"></i>
						</dt>
						<dd>
							<ul class="display-none-cls" data-id="field_arf_section_font_size_{arf_field_id}">
								<?php for ( $i = 8; $i <= 20; $i ++ ) { ?>
									<li class="arf_selectbox_option" data-value="<?php echo esc_html( $i ); ?>" data-label="<?php echo esc_attr( htmlentities( $i ) ); ?>"><?php echo addslashes( esc_html($i) ); ?></li>
								<?php } ?>
								<?php for ( $i = 22; $i <= 28; $i = $i + 2 ) { ?>
									<li class="arf_selectbox_option" data-value="<?php echo esc_html( $i ); ?>" data-label="<?php echo esc_attr( htmlentities( $i ) ); ?>"><?php echo addslashes( esc_html($i) ); ?></li>
								<?php } ?>
								<?php for ( $i = 32; $i <= 40; $i = $i + 4 ) { ?>
									<li class="arf_selectbox_option" data-value="<?php echo esc_html( $i ); ?>" data-label="<?php echo esc_attr( htmlentities( $i ) ); ?>"><?php echo addslashes( esc_html($i) ); ?></li>
								<?php } ?>
							</ul>
						</dd>
					</dl>
				</div>
			</div>
			<div class="arf_field_option_content_cell" data-sort="-1" id="fontstyleoption">
				<label class="arf_field_option_content_cell_label"><?php echo esc_html__( 'Font style', 'arforms-form-builder' ); ?></label>
				<div class="arf_field_option_content_cell_input">
					<input id="field_arf_section_font_style_{arf_field_id}" name="arf_section_font_style" value="bold" type="hidden">
					<dl class="arf_selectbox" data-name="arf_section_font_style" data-field-id="{arf_field_id}" data-id="field_arf_section_font_style_{arf_field_id}">
						<dt>
						<span>bold</span>
						<input value="bold"  class="arf_autocomplete display-none-cls" type="text">
						<i class="fas fa-caret-down fa-lg"></i>
						</dt>
						<dd>
							<ul class="display-none-cls" data-id="field_arf_section_font_style_{arf_field_id}">
								<li class="arf_selectbox_option" data-value="normal" data-label="<?php echo esc_html__( 'normal', 'arforms-form-builder' ); ?>"><?php echo esc_html__( 'normal', 'arforms-form-builder' ); ?></li>
								<li class="arf_selectbox_option" data-value="bold" data-label="<?php echo esc_html__( 'bold', 'arforms-form-builder' ); ?>"><?php echo esc_html__( 'bold', 'arforms-form-builder' ); ?></li>
								<li class="arf_selectbox_option" data-value="italic" data-label="<?php echo esc_html__( 'italic', 'arforms-form-builder' ); ?>"><?php echo esc_html__( 'italic', 'arforms-form-builder' ); ?></li>
							</ul>
						</dd>
					</dl>
				</div>
			</div>
						
			<div class="arf_field_option_content_cell" data-sort="-1" id="arf_input_custom_validation">
				<label class="arf_field_option_content_cell_label"><?php echo esc_html__( 'Validation', 'arforms-form-builder' ); ?></label>
				<div class="arf_field_option_content_cell_input">
					<?php
						$validation_opts = array(
							'custom_validation_none'   => addslashes( esc_html__( 'None', 'arforms-form-builder' ) ),
							'custom_validation_alpha'  => addslashes( esc_html__( 'Only Alphabets', 'arforms-form-builder' ) ),
							'custom_validation_number' => addslashes( esc_html__( 'Only Numbers', 'arforms-form-builder' ) ),
							'custom_validation_alphanumber' => addslashes( esc_html__( 'Only Alphabets & Numbers', 'arforms-form-builder' ) ),
							'custom_validation_regex'  => addslashes( esc_html__( 'Regular Expression (custom)', 'arforms-form-builder' ) ),
						);

						$validation_attr = array(
							'onchange' => 'arfliteShowvalidationmessage("{arf_field_id}");',
						);

						echo $arflitemaincontroller->arflite_selectpicker_dom( 'single_custom_validation', 'single_custom_validation_{arf_field_id}', '', '', 'custom_validation_none', $validation_attr, $validation_opts ); //phpcs:ignore
						?>
				</div>
			</div>
			<div class="arf_field_option_content_cell" data-sort="-1" id="arf_regular_expression_msg">
				<label class="arf_field_option_content_cell_label"><?php echo esc_html__( 'Message for regular expression', 'arforms-form-builder' ); ?></label>
				<div class="arf_field_option_content_cell_input">
					<input id="arf_regular_expression_msg_{arf_field_id}" type="text" name="arf_regular_expression_msg" value="<?php echo esc_html__( 'Entered value is invalid', 'arforms-form-builder' ); ?>" class="arf_field_option_input_text txtstandardnew arfblank_txt" disabled="disabled"/>
				</div>
			</div>
			<div class="arf_field_option_content_cell" data-sort="-1" id="arf_regular_expression">
				<label class="arf_field_option_content_cell_label"><?php echo esc_html__( 'Regular expression', 'arforms-form-builder' ); ?>
					<span class="arf_popup_tooltip_main arfhelptip tipso_style " data-title="<strong><?php echo esc_html__( 'Sample RegExp', 'arforms-form-builder' ); ?></strong><br><div class='arflite_text_align_left'><strong><?php echo '[0-9]{6} '; ?></strong><?php echo esc_html__( 'Allow only digits upto 6 digits. e.g. : pincode', 'arforms-form-builder' ); ?><br><strong><?php echo '[a-zA-Z0-9]{8,16}'; ?></strong> <?php echo esc_html__( 'Allow alpha numeric characters and length must be between 8 to 16 characters', 'arforms-form-builder' ); ?><br><strong><?php echo '\([\d]{3}\)\-[\d]{7}'; ?> </strong> <?php echo esc_html__( 'Allow phone number like', 'arforms-form-builder' ) . ' (123)-1234567'; ?></div>">
					<img class="arflitephntypehelpicon" src="<?php echo esc_url( ARFLITEIMAGESURL ); ?>/tooltips-icon.png" alt="?"/></span>
				</label>
				<div class="arf_field_option_content_cell_input">
					<input id="arf_regular_expression_{arf_field_id}" type="text" name="arf_regular_expression" value="" class="arf_field_option_input_text txtstandardnew arfblank_txt" disabled="disabled" />
					<span class="arf_pre_regex arf_pre_regex_{arf_field_id} arf_pre_regex_disable" data-field-id="{arf_field_id}" data-pattern="(http(s)?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?"><?php echo esc_html__( 'URL', 'arforms-form-builder' ); ?></span>
					<span class="arf_pre_regex arf_pre_regex_{arf_field_id} arf_pre_regex_disable" data-field-id="{arf_field_id}" data-pattern="(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)"><?php echo esc_html__( 'IP Address', 'arforms-form-builder' ); ?></span>
					<span class="arf_pre_regex arf_pre_regex_{arf_field_id} arf_pre_regex_disable" data-field-id="{arf_field_id}" data-pattern="[a-z0-9_-]{3,16}"><?php echo esc_html__( 'User Name', 'arforms-form-builder' ); ?></span>
					<span class="arf_pre_regex arf_pre_regex_{arf_field_id} arf_pre_regex_disable" data-field-id="{arf_field_id}" data-pattern="[0-9]{3,4}"><?php echo esc_html__( 'CVC/CVV', 'arforms-form-builder' ); ?></span>
				</div>
			</div>
			<div class="arf_field_option_content_cell" data-sort="-1" id="istooltip">
				<label class="arf_field_option_content_cell_label"><?php echo esc_html__( 'Tooltip', 'arforms-form-builder' ); ?></label>
				<div class="arf_field_option_content_cell_input">
					<label class="arf_js_switch_label">
						<span><?php echo esc_html__( 'NO', 'arforms-form-builder' ); ?>&nbsp;</span>
					</label>
					<span class="arf_js_switch_wrapper arf_no_transition">
						<input type="checkbox" class="js-switch arf_tooltip_{arf_field_id}" name="arf_tooltip" id="frm_arf_tooltip_field_{arf_field_id}" onchange='arflitetooltipfieldfunction("{arf_field_id}", "0", "2")' value="1" />
						<span class="arf_js_switch"></span>
					</span>
					<label class="arf_js_switch_label">
						<span>&nbsp;<?php echo esc_html__( 'YES', 'arforms-form-builder' ); ?></span>
					</label>
					<input type="hidden" name="frm_arf_tooltip_field_indicator" value="" />
				</div>
			</div>
			<div class="arf_field_option_content_cell" data-sort="-1" id="tooltipmsg">
				<label class="arf_field_option_content_cell_label"><?php echo esc_html__( 'Message for tooltip', 'arforms-form-builder' ); ?></label>
				<div class="arf_field_option_content_cell_label">
					<input id="arftooltiptext{arf_field_id}" type="text" name="tooltip_text" value="" class="arf_field_option_input_text txtstandardnew arfblank_txt" />
				</div>
			</div>
			
			<?php do_action( 'arflite_field_option_model_outside' ); ?>
		</div>
	</div>
	<div class="arf_field_option_model_footer">
		<button type="button" class="arf_field_option_close_button" id="arf_field_option_close_button"><?php echo esc_html__( 'Cancel', 'arforms-form-builder' ); ?></button>
		<button type="button" class="arf_field_option_submit_button" data-field_id=""><?php echo esc_html__( 'OK', 'arforms-form-builder' ); ?></button>
	</div>
</div>
