<?php

class arflite_file_type_conversion {

	function __construct() {

		add_action( 'arflite_editor_general_options_menu', array( $this, 'arflite_add_general_option_menu' ) );

		add_action( 'arflite_add_modal_in_editor', array( $this, 'arflite_add_field_conversion_modal' ), 10 );
	}

	function arflite_add_general_option_menu() {
		$show_convert_field_menu = 'display-none-cls';
		if ( isset( $_GET['arfaction'] ) && sanitize_text_field( $_GET['arfaction'] ) == 'edit' ) {
			$show_convert_field_menu = '';
		}
		echo '<li class="arf_editor_top_dropdown_option ' . esc_attr( $show_convert_field_menu ) . '" id="arf_field_type_converter">' . esc_html__( 'Convert Field Type', 'arforms-form-builder' ) . '</li>';

		echo '<input type="hidden" id="arflite_field_type_conversion_array" value="' . esc_attr( base64_encode( json_encode( $this->arflite_migrate_field_type() ) ) ) . '" />';

	}

	function arflite_add_field_conversion_modal( $values ) {
		global $arflitefieldhelper;
		?>
		<div class="arf_modal_overlay">
			<div id="arf_field_type_converter_model" class="arf_popup_container arf_popup_container_field_typle_converter_model">
				
				<div class="arf_popup_container_header">
					<?php echo esc_html__( 'Convert Field Type', 'arforms-form-builder' ); ?>
					<div class="arfpopupclosebutton arfmodalclosebutton" data-dismiss="arfmodal" data-id="arf_optin_popup_button">
						<svg width="30px" height="30px" viewBox="1 0 20 20"><g id="preview"><path fill-rule="evenodd" clip-rule="evenodd" fill="#262944" d="M10.702,10.909L6.453,6.66l-4.249,4.249L1.143,9.848l4.249-4.249L1.154,1.361l1.062-1.061l4.237,4.237l4.238-4.237l1.061,1.061L7.513,5.599l4.249,4.249L10.702,10.909z"></path></g></svg>
					</div>
				</div>

				<div class="arf_popup_content_container arf_field_converter_option_container">
					<div class="arf_field_type_conversion_container">
					<div style="<?php echo ( is_rtl() ) ? 'float: left;' : 'float: right;'; ?>">
							<a  target="_blank" title="Help" class="fas fa-life-ring arf_adminhelp_icon arfhelptip tipso_style" data-tipso="Help" onclick="arf_help_doc_fun('arf_convert_option');"></a>
						</div>
						<p class="arf_feature_recommendation_note">
							<?php echo '<strong>' . esc_html__( 'Note', 'arforms-form-builder' ) . ':</strong> ' . esc_html__( 'This feature is only recommended when you have big amount of entries in the form and you want to change the particular field type without losing the entry data for that field.', 'arforms-form-builder' ); ?>
						</p>
						<div class="select-field-convrt-wrap">
							<div class="arf_ar_dropdown_wrapper">
								<label class="arf_dropdown_autoresponder_label"> <?php echo esc_html__( 'Select Field To Convert', 'arforms-form-builder' ); ?> </label> 
								<input type="hidden" id="arf_current_field_type" />
								<input type="hidden" id="field_type_converter" />
								<dl class="arf_selectbox field_type_converter_dl" data-name="field_type_converter" data-id="field_type_converter">
									<dt>
										<span><?php echo esc_html__( 'Select Field', 'arforms-form-builder' ); ?></span>
										<input class="arf_autocomplete calender-local" type="text" autocomplete="off" />
										<i class="fas fa-caret-down fa-lg"></i>
									</dt>
									<dd>
										<ul class="arf_change_type_conversion_dropdown field_type_converter_ul" data-id="field_type_converter" >
											<li class="arf_selectbox_option" data-value="" data-label="<?php echo esc_html__( 'Select Field', 'arforms-form-builder' ); ?>"><?php echo esc_html__( 'Select Field', 'arforms-form-builder' ); ?></li>
											<?php
												$supported_field_types = $this->arflite_migrate_field_type();

											if ( isset( $values['fields'] ) && count( $values['fields'] ) > 0 ) {
												foreach ( $values['fields'] as $k => $fields ) {
													if ( array_key_exists( $fields['type'], $supported_field_types ) ) {
														echo "<li class='arf_selectbox_option' data-label='" . $arflitefieldhelper->arflite_execute_function( $fields['name'], 'strip_tags' ) . "' data-value='{$fields['id']}' data-type='{$fields['type']}'>" . $arflitefieldhelper->arflite_execute_function( $fields['name'], 'strip_tags' ) . ' </li>'; //phpcs:ignore
													}
												}
											}
											?>
										</ul>
									</dd>
								</dl>
							</div>

							<div class="arf_ar_dropdown_wrapper">
								<label class="arf_dropdown_autoresponder_label"><?php echo esc_html__( 'Current Field Type', 'arforms-form-builder' ); ?>:</label>
								<span class="arf_current_field_type"></span>
							</div>

							<div class="arf_ar_dropdown_wrapper">
								<input type="hidden" id="field_type_to_convert"  />
								<label class="arf_dropdown_autoresponder_label"> <?php echo esc_html__( 'Convert To Field Type', 'arforms-form-builder' ); ?> </label> 
								<dl class="arf_selectbox field_type_converter_dl" data-name="field_type_to_convert" data-id="field_type_to_convert">
									<dt>
										<span><?php echo esc_html__( 'Select Field Type', 'arforms-form-builder' ); ?></span>
										<input class="arf_autocomplete calender-local" type="text" autocomplete="off" />
										<i class="fas fa-caret-down fa-lg"></i>
									</dt>
									<dd>
										<ul class="field_type_converter_ul" data-id="field_type_to_convert">
											<li class="arf_selectbox_option" data-value="" data-label="<?php echo esc_html__( 'Select Field', 'arforms-form-builder' ); ?>"><?php echo esc_html__( 'Select Field', 'arforms-form-builder' ); ?></li>
											<?php
												$all_fields_type = $this->arflite_migrate_field_type();

											foreach ( $all_fields_type as $type => $label ) {
												echo "<li class='arf_selectbox_option' data-value='" . esc_attr( $type ) . "' data-label='" . esc_attr( $label ) . "' data-type='" . esc_attr( $type ) . "'>'".esc_attr($label)."'</li>";
											}
											?>
										</ul>
									</dd>
								</dl>
							</div>

							<ul class="arf_ar_dropdown_wrapper_note_changing_type">
							</ul>
						</div>
					</div>
				</div>

				<div class="arf_popup_container_footer">
					<button type="button" class="arf_popup_close_button_field_converter" data-id="arf_optin_popup_button"><?php echo esc_html__( 'Confirm', 'arforms-form-builder' ); ?></button>
					<div class="arf_imageloader" id="arf_field_converter_loader"></div>
				</div>

			</div>
		</div>
		<?php
	}

	function arflite_migrate_field_type() {

		$field_types = array(
			'text'     => __( 'Single Line Text', 'arforms-form-builder' ),
			'textarea' => __( 'Multiline Text', 'arforms-form-builder' ),
			'checkbox' => __( 'Checkbox', 'arforms-form-builder' ),
			'radio'    => __( 'Radio Buttons', 'arforms-form-builder' ),
			'select'   => __( 'Dropdown', 'arforms-form-builder' ),
			'email'    => __( 'Email', 'arforms-form-builder' ),
			'number'   => __( 'Number', 'arforms-form-builder' ),
			'phone'    => __( 'Phone', 'arforms-form-builder' ),
			'url'      => __( 'Website/URL', 'arforms-form-builder' ),
		);

		$field_types = apply_filters( 'arflite_migrate_field_type_from_outside', $field_types );

		return $field_types;
	}
}

global $arf_file_type_conversion;
$arf_file_type_conversion = new arflite_file_type_conversion();
