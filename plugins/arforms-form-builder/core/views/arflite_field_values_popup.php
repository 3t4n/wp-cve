<?php
global $arflitemainhelper;

$states = $arflitemainhelper->arflite_get_us_states();

$current_year = date( 'Y' );

$from_year = '1935';

$year_display = array();
for ( $yr_counter = $from_year; $yr_counter <= $current_year; $yr_counter++ ) {
	$year_display[] = (string) $yr_counter;
}

$country_codes = $arflitemainhelper->arflite_get_country_codes();

ksort( $country_codes );

$country_codes = array_keys( $country_codes );

$preset_options = array(
	__( 'Countries', 'arforms-form-builder' )              => $arflitemainhelper->arfliteget_countries(),
	__( 'U.S. States', 'arforms-form-builder' )            => array_values( $states ),
	__( 'U.S. State Abbreviations', 'arforms-form-builder' ) => array_keys( $states ),
	__( 'Age Group', 'arforms-form-builder' )              => array(
		__( 'Under 18', 'arforms-form-builder' ),
		__( '18-24', 'arforms-form-builder' ),
		__( '25-34', 'arforms-form-builder' ),
		__( '35-44', 'arforms-form-builder' ),
		__( '45-54', 'arforms-form-builder' ),
		__( '55-64', 'arforms-form-builder' ),
		__( '65 or Above', 'arforms-form-builder' ),
	),
	__( 'Satisfaction', 'arforms-form-builder' )           => array(
		__( 'Very Satisfied', 'arforms-form-builder' ),
		__( 'Satisfied', 'arforms-form-builder' ),
		__( 'Neutral', 'arforms-form-builder' ),
		__( 'Unsatisfied', 'arforms-form-builder' ),
		__( 'Very Unsatisfied', 'arforms-form-builder' ),
		__( 'N/A', 'arforms-form-builder' ),
	),
	__( 'Days', 'arforms-form-builder' )                   => array(

		__( '1', 'arforms-form-builder' ),
		__( '2', 'arforms-form-builder' ),
		__( '3', 'arforms-form-builder' ),
		__( '4', 'arforms-form-builder' ),
		__( '5', 'arforms-form-builder' ),
		__( '6', 'arforms-form-builder' ),
		__( '7', 'arforms-form-builder' ),
		__( '8', 'arforms-form-builder' ),
		__( '9', 'arforms-form-builder' ),
		__( '10', 'arforms-form-builder' ),
		__( '11', 'arforms-form-builder' ),
		__( '12', 'arforms-form-builder' ),
		__( '13', 'arforms-form-builder' ),
		__( '14', 'arforms-form-builder' ),
		__( '15', 'arforms-form-builder' ),
		__( '16', 'arforms-form-builder' ),
		__( '17', 'arforms-form-builder' ),
		__( '18', 'arforms-form-builder' ),
		__( '19', 'arforms-form-builder' ),
		__( '20', 'arforms-form-builder' ),
		__( '21', 'arforms-form-builder' ),
		__( '22', 'arforms-form-builder' ),
		__( '23', 'arforms-form-builder' ),
		__( '24', 'arforms-form-builder' ),
		__( '25', 'arforms-form-builder' ),
		__( '26', 'arforms-form-builder' ),
		__( '27', 'arforms-form-builder' ),
		__( '28', 'arforms-form-builder' ),
		__( '29', 'arforms-form-builder' ),
		__( '30', 'arforms-form-builder' ),
		__( '31', 'arforms-form-builder' ),
	),
	__( 'Week Days', 'arforms-form-builder' )              => array(
		__( 'Sunday', 'arforms-form-builder' ),
		__( 'Monday', 'arforms-form-builder' ),
		__( 'Tuesday', 'arforms-form-builder' ),
		__( 'Wednesday', 'arforms-form-builder' ),
		__( 'Thursday', 'arforms-form-builder' ),
		__( 'Friday', 'arforms-form-builder' ),
		__( 'Saturday', 'arforms-form-builder' ),
	),
	__( 'Months', 'arforms-form-builder' )                 => array(
		__( 'January', 'arforms-form-builder' ),
		__( 'February', 'arforms-form-builder' ),
		__( 'March', 'arforms-form-builder' ),
		__( 'April', 'arforms-form-builder' ),
		__( 'May', 'arforms-form-builder' ),
		__( 'June', 'arforms-form-builder' ),
		__( 'July', 'arforms-form-builder' ),
		__( 'August', 'arforms-form-builder' ),
		__( 'September', 'arforms-form-builder' ),
		__( 'October', 'arforms-form-builder' ),
		__( 'November', 'arforms-form-builder' ),
		__( 'December', 'arforms-form-builder' ),
	),
	__( 'Years', 'arforms-form-builder' )                  => $year_display,
	__( 'Prefix', 'arforms-form-builder' )                 => array(
		__( 'Mr', 'arforms-form-builder' ),
		__( 'Mrs', 'arforms-form-builder' ),
		__( 'Ms', 'arforms-form-builder' ),
		__( 'Miss', 'arforms-form-builder' ),
		__( 'Sr', 'arforms-form-builder' ),
	),
	__( 'Telephone Country Code', 'arforms-form-builder' ) => $country_codes,
);

array_unshift( $preset_options[ __( 'Countries', 'arforms-form-builder' ) ], '' );

$arf_preset_values = maybe_unserialize( get_option( 'arflite_preset_values' ) );

if ( ! empty( $arf_preset_values ) && is_array( $arf_preset_values ) ) {
	$file_preset_arr = '';
	foreach ( $arf_preset_values as $key => $value ) {
		$file_preset_arr = 'csv_preset_' . $key;

		$preset_options[ $arf_preset_values[ $key ]['title'] ] = array( $file_preset_arr );
	}
}

$arf_preset_fields = $preset_options;
?>
<div class="arf_field_values_model" id="arf_field_values_model_skeleton">
	<div class="arf_field_values_model_header"><?php echo esc_html__( 'Edit Options', 'arforms-form-builder' ); ?></div>
	<div class="arf_field_values_model_container">
		<div class="arf_field_values_content_row">
			<div class="arf_field_values_content_cell" id="use_image">
				<label class="arf_field_values_content_cell_label"><?php echo esc_html__( 'Use image over options', 'arforms-form-builder' ); ?>:</label>
				<div class="arf_field_values_content_cell_input">
					<label class="arf_js_switch_label">
						<span><?php echo esc_html__( 'No', 'arforms-form-builder' ); ?></span>
					</label>
					<div class="arf_js_switch_wrapper arf_no_transition">
						<input type="checkbox" class="js-switch" name="use_image" data-field-id="{arf_field_id}" value="1" id="arf_field_use_image" />
						<span class="arf_js_switch"></span>
					</div>
					<label class="arf_js_switch_label">
						<span><?php echo esc_html__( 'Yes', 'arforms-form-builder' ); ?></span>
					</label>
					<span class="arfhelptip" data-title="<?php echo sprintf( __( 'Use image over %s label', 'arforms-form-builder' ), '{arf_field_type}' ); //phpcs:ignore ?>"><svg width="18px" height="18px"><?php echo ARFLITE_TOOLTIP_ICON; //phpcs:ignore ?></svg></span>
				</div>
			</div>
			<div class="arf_field_values_content_cell" id="separate_value">
				<label class="arf_field_values_content_cell_label"><?php echo esc_html__( 'Use separate value', 'arforms-form-builder' ); ?></label>
				<div class="arf_field_values_content_cell_input">
					<label class="arf_js_switch_label">
						<span><?php echo esc_html__( 'No', 'arforms-form-builder' ); ?></span>
					</label>
					<div class="arf_js_switch_wrapper arf_no_transition">
						<input type="checkbox" class="js-switch arf_hide_opacity " name="separate_value" data-field-id="{arf_field_id}" id="arf_field_separate_value" value="1" />
						<span class="arf_js_switch"></span>
					</div>
					<label class="arf_js_switch_label">
						<span><?php echo esc_html__( 'Yes', 'arforms-form-builder' ); ?></span>
					</label>
					<?php $arflite_seprate_value_title = __( 'Add a separate value to use for calculations, email routing, saving to database and many other uses. The option values are saved while option labels are shown in the form', 'arforms-form-builder' ); ?>
					<span class="arfhelptip" data-title="<?php echo esc_attr( $arflite_seprate_value_title ); ?>"><svg width="18px" height="18px"><?php echo ARFLITE_TOOLTIP_ICON; //phpcs:ignore ?></svg></span>
				</div>
			</div>
			<div class="arf_field_values_content_cell arf_restricted_control" id="dynamic_option">
				<label class="arf_field_values_content_cell_label"><?php echo esc_html__( 'Dynamic Option', 'arforms-form-builder' ); ?><span class="arflite_pro_version_notice">(Premium)</span></label>
				<div class="arf_field_values_content_cell_input">
					<label class="arf_js_switch_label">
						<span><?php echo esc_html__( 'No', 'arforms-form-builder' ); ?></span>
					</label>
					<div class="arf_js_switch_wrapper arf_no_transition">
						<input type="checkbox" class="js-switch arf_hide_opacity arf_dynamic_option_{arf_field_id}" name="dynamic_option" data-field-id="{arf_field_id}" id="arf_field_dynamic_option" value="1" />
						<span class="arf_js_switch"></span>
					</div>
					<label class="arf_js_switch_label">
						<span><?php echo esc_html__( 'Yes', 'arforms-form-builder' ); ?></span>
					</label>
				</div>
			</div>
			<div class="arf_field_values_content_cell arf_full_width_cell" id="options">
				<div class="arf_field_values_content_cell_input">
					<div class="arf_field_value_grid_wrapper">
						<div class="arf_field_value_grid_container">
							<div class="arf_field_value_grid_header">
								<div class="arf_field_value_grid_header_cell_input">
									<div class='arf_field_radio_reset_wrapper' data-content="<?php echo esc_html__( 'Reset', 'arforms-form-builder' ); ?>">
										<i class="fas fa-redo"></i>
									</div>
								</div>
								<div class="arf_field_value_grid_header_cell_label"><?php echo esc_html__( 'Option label', 'arforms-form-builder' ); ?></div>
								<div class="arf_field_value_grid_header_cell_value"><?php echo esc_html__( 'Saved Value', 'arforms-form-builder' ); ?></div>
								<div class="arf_field_value_grid_header_cell_action"></div>
							</div>
							<div class="arf_field_value_grid_data_wrapper" id="arf_field_value_grid_data_wrapper_{arf_field_id}">
							</div>
							<input type="hidden" name="arf_radio_image_name" id="arf_radio_image_name" />
						</div>
					</div>
				</div>
			</div>
			<div class="arf_field_values_content_cell arf_full_width_cell" id="use_preset_fields">
				<label class="arf_field_values_content_cell_label"></label>
				<div class="arf_field_values_content_cell_input">
					<button type="button" onClick="arfliteshowbulkfieldoptions1('{arf_field_id}')" class="arf_preset_field_button" data-field-id="{arf_field_id}"><?php echo esc_html__( 'Preset Field Choices', 'arforms-form-builder' ); ?></button>
					<div class="arf_preset_field_dropdown_wrapper" id="arfshowfieldbulkoptions-{arf_field_id}">
						<?php
							$preset_field_attr = array(
								'onClick'   => 'arflitestorebulkoptionvalue("{arf_field_id}", this.value);',
								'data-skip' => 'true',
							);

							$preset_field_opts = array( '' => addslashes( esc_html__( 'Select', 'arforms-form-builder' ) ) );

							$preset_list_class = array();
							foreach ( $arf_preset_fields as $preset_label => $preset_values ) {
								$final_preset_values = $preset_values;
								if ( array_keys( $preset_values ) !== range( 0, count( $preset_values ) - 1 ) ) {
									$final_preset_values = array();
									foreach ( $preset_values as $new_preset_key => $new_preset_data ) {
										$new_preset_key_data = $new_preset_key;
										if ( $new_preset_key_data == '' ) {
											$new_preset_key_data = $new_preset_data;
										}
										$final_preset_values[] = htmlspecialchars( $new_preset_key_data, ENT_QUOTES, 'UTF-8' ) . '|' . htmlspecialchars( $new_preset_data, ENT_QUOTES, 'UTF-8' );
									}
								}

								$fields_val = json_encode( array_values( $final_preset_values ) );
								$val        = htmlspecialchars( $fields_val, ENT_QUOTES, 'UTF-8' );

								$preset_field_opts[ $val ] = htmlspecialchars( $preset_label, ENT_QUOTES, 'UTF-8' );

								if ( ! empty( $arf_preset_values ) && is_array( $arf_preset_values ) ) {
									foreach ( $arf_preset_values as $key => $value ) {
										if ( '["csv_preset_' . $key . '"]' == $fields_val ) {
											$preset_list_class[ $val ] = 'arflite_field_data_dynamic';
										}
									}
								}
							}

							echo $arflitemaincontroller->arflite_selectpicker_dom( '', 'frm_bulk_options-select-{arf_field_id}', '', 'width:225px;', '', $preset_field_attr, $preset_field_opts, false, $preset_list_class, false, array(), false, array(), false, '', '', false ); //phpcs:ignore
							?>
								

						<button type="button" class="arf_preset_apply_button" data-field-type="{arf_field_type}" data-field-id="{arf_field_id}" ><?php echo esc_html__( 'Apply', 'arforms-form-builder' ); ?></button>
						<button type="button" class="arf_preset_cancel_button arf_field_cancel_button" data-field-id="{arf_field_id}"><?php echo esc_html__( 'Cancel', 'arforms-form-builder' ); ?></button>
						<span class="arf_preset_apply_field_loader" id="arf_preset_apply_field_loader_{arf_field_id}"><?php echo esc_html__( 'Saving', 'arforms-form-builder' ) . '...'; ?></span>
					</div>

				</div>
			</div>
			<div class="arf_field_values_content_cell arf_full_width_cell" id="add_preset_fields">
				<label class="arf_field_values_content_cell_label display-none-cls"></label>
				<div class="arf_field_values_content_cell_input">
					<button type="button" onClick="arflite_preset_field_show('{arf_field_id}')" class="arf_preset_field_button" data-field-id="{arf_field_id}"><?php echo esc_html__( 'Add New Preset Choices', 'arforms-form-builder' ); ?></button>
					<div class='arf_new_preset_field_content_wrapper arf_preset_field_content_wrapper_{arf_field_id}'>
						<span class="arf_new_preset_field_data_uploader">
							<input type="file" id="arf_preset_data_{arf_field_id}" class="arf_preset_data" name="arf_preset_data" data-val="{arf_field_id}" />
							<span class="arf_field_option_input_note_text"><?php echo esc_html__( 'Upload only CSV file.', 'arforms-form-builder' ) . '<br/>' . esc_html__( 'Please upload tab separated CSV file.', 'arforms-form-builder' ); ?></span>
						</span>
						<span class="arf_custom_checkbox_wrapper">
							<input type="checkbox" class="arf_custom_checkbox arf_enable_new_preset_field_save" value="1" name="arf_preset_future_use" id="arf_preset_future_use_{arf_field_id}" data-field-id="{arf_field_id}" />
							<svg width="18px" height="18px">
							<path id='arfcheckbox_unchecked' d='M15.205,16.852H3.774c-1.262,0-2.285-1.023-2.285-2.286V3.136  c0-1.263,1.023-2.286,2.285-2.286h11.431c1.263,0,2.286,1.023,2.286,2.286v11.43C17.491,15.829,16.467,16.852,15.205,16.852z M15.49,2.851h-12v12h12V2.851z' />
							<path id='arfcheckbox_checked' d='M15.205,16.852H3.774c-1.262,0-2.285-1.023-2.285-2.286V3.136  c0-1.263,1.023-2.286,2.285-2.286h11.431c1.263,0,2.286,1.023,2.286,2.286v11.43C17.491,15.829,16.467,16.852,15.205,16.852z   M15.49,2.851h-12v12h12V2.851z M5.93,6.997l2.557,2.558l4.843-4.843l1.617,1.616l-4.844,4.843l0.007,0.007l-1.616,1.616  l-0.007-0.007l-0.006,0.007l-1.617-1.616l0.007-0.007L4.314,8.614L5.93,6.997z' />
							</svg>
							<label for="arf_preset_future_use_{arf_field_id}"><?php echo esc_html__( 'Save for future use', 'arforms-form-builder' ); ?></label>
						</span>
						<input type="text" class="arf_preset_field_title inplace_field" name="arf_preset_title" placeholder="<?php echo esc_html__( 'Preset Title', 'arforms-form-builder' ); ?>" id="arf_preset_field_title_{arf_field_id}" />
						<button type="button" class="arf_new_preset_apply_button" data-field-type="{arf_field_type}" data-field-id="{arf_field_id}" ><?php echo esc_html__( 'Apply', 'arforms-form-builder' ); ?></button>
						<button type="button" class="arf_new_preset_cancel_button arf_field_cancel_button" data-field-id="{arf_field_id}"><?php echo esc_html__( 'Cancel', 'arforms-form-builder' ); ?></button>
					</div>
				</div>
			</div>
			<-- new width related changes -->
				<div class="arf_field_values_content_cell" id="arflite_check_icon" style="width:98%">
				<div class="arf_field_values_content_cell arf_chk_styling">
					<label class="arf_field_values_content_cell_label"><?php echo esc_html__( 'Image Width', 'arforms-form-builder' ); ?>:</label>
					<div class="chekbox_image_width_popup" id="image_width_popup" >
						<div class="checkbox_width_text">
							<div class=" arfwidth108 arf_checkbox_border">
								<input type="text" class="arf_field_option_input_text" name="image_width" id="image_width" value="120">

							</div> 
						</div>
						<span class="arfpx_checkboxwidth">px</span>
					</div>
				</div>
					
					<div class="arf_field_values_content_cell arf_chk_styling">
						<label class="arf_field_values_content_cell_label"><?php echo esc_html__( 'Checked Icon Styling', 'arforms-form-builder' ); ?>:</label>
							<div class="arf_field_option_content_cell_input">
								<div class="arf_field_prefix_suffix_wrapper" id="arf_field_prefix_suffix_wrapper_{arf_field_id}">
									<div class="arf_prefix_wrapper" id="arf_check_icon">
									   <div class="arf_prefix_suffix_container_wrapper" data-action="edit" data-field="prefix" field-id="{arf_field_id}" id="arf_edit_prefix_{arf_field_id}" data-toggle="arfmodal" href="#arf_fontawesome_modal" data-field_type="checkbox">
											<div class="arf_prefix_container" id="arf_select_prefix_{arf_field_id}">
													<div class="arf_prefix_container" id="arf_select_prefix_{arf_field_id}">
														<?php echo "<i id='arf_select_prefix_{arf_field_id}' class='arf_prefix_suffix_icon fa fa-check'></i>"; ?>
													</div>
											</div>
										<div class="arf_prefix_suffix_action_container">
											<div class="arf_prefix_suffix_action" title="Change Icon" style="<?php echo ( is_rtl() ) ? 'margin-right:5px;' : 'margin-left:5px;'; ?>">
												<i class="fas fa-caret-down fa-lg"></i>
											</div>
										</div>
										</div>
										<input type="hidden" name="enable_arf_prefix" id="enable_arf_prefix_{arf_field_id}" />
										<input type="hidden" name="arf_prefix_icon" id="arf_prefix_icon_{arf_field_id}" value="fas fa-check" />
									</div>
								</div>
							</div>
					  </div>
					 <!-- <div class="arf_field_values_content_cell arf_chk_styling">
						<label class="arf_field_values_content_cell_label"><?php echo esc_html__( 'Checked Icon Styling', 'arforms-form-builder' ); ?>:</label>
							<div class="arf_field_option_content_cell_input">
								<div class="arf_field_prefix_suffix_wrapper" id="arf_field_prefix_suffix_wrapper_{arf_field_id}">
									<div class="arf_prefix_wrapper" id="arflite_check_icon">
									   <div class="arf_prefix_suffix_container_wrapper" data-action="edit" data-field="prefix" field-id="{arf_field_id}" id="arf_edit_prefix_{arf_field_id}" data-toggle="arfmodal" href="#arf_fontawesome_modal" data-field_type="checkbox">
											<div class="arf_prefix_container" id="arf_select_prefix_{arf_field_id}">
													<div class="arf_prefix_container" id="arf_select_prefix_{arf_field_id}">
														<?php echo "<i id='arf_select_prefix_{arf_field_id}' class='arf_prefix_suffix_icon fa fa-check'></i>"; ?>
													</div>
											</div>
										<div class="arf_prefix_suffix_action_container">
											<div class="arf_prefix_suffix_action" title="Change Icon" style="<?php echo ( is_rtl() ) ? 'margin-right:5px;' : 'margin-left:5px;'; ?>">
												<i class="fas fa-caret-down fa-lg"></i>
											</div>
										</div>
										</div>
										<input type="hidden" name="enable_arf_prefix" id="enable_arf_prefix_{arf_field_id}" />
										<input type="hidden" name="arf_prefix_icon" id="arf_prefix_icon_{arf_field_id}" value="fas fa-check" />
									</div>
								</div>
							</div>
					  </div> -->
				</div>
		</div>
	</div>
	<div class="arf_field_values_model_footer">
		<button type="button" class="arf_field_values_close_button"><?php echo esc_html__( 'Cancel', 'arforms-form-builder' ); ?></button>
		<button type="button" class="arf_field_values_submit_button" data-field_id=""><?php echo esc_html__( 'OK', 'arforms-form-builder' ); ?></button>
	</div>
</div>
