<?php
global $arflitemainhelper, $arfliteformhelper, $arfliteversion, $arflitemaincontroller;

wp_register_style( 'arflite-font-awesome', ARFLITEURL . '/css/font-awesome.min.css', array(), $arfliteversion );
wp_register_style( 'arflite-insert-form-css', ARFLITEURL . '/css/arflite_insert_form_style.css', array(), $arfliteversion );
wp_register_script( 'arflite-selectpicker-js', ARFLITEURL . '/js/arflite_selectpicker.js', array(), $arfliteversion );
wp_register_style( 'arflite-selectpicker-css', ARFLITEURL . '/css/arflite_selectpicker.css', array(), $arfliteversion );


$arflitemainhelper->arflite_load_styles( array( 'arflite-font-awesome' ) );
$arflitemainhelper->arflite_load_styles( array( 'arflite-insert-form-css' ) );
$arflitemainhelper->arflite_load_styles( array( 'arflite-selectpicker-css' ) );
$arflitemainhelper->arflite_load_scripts( array( 'arflite-selectpicker-js' ) );

?>

<div class='arf_modal_overlay'>
	 <?php
		$arf_element_show = false;
		if ( defined( 'WPB_VC_VERSION' ) ) {
			if ( version_compare( WPB_VC_VERSION, '4.6', '>=' ) ) {
				$arf_element_show = true;
			}
		}
		?>
	<input type="hidden" id="arf_element_trigger_event" value="<?php echo esc_attr( $arf_element_show ); ?>" />
	<div class='arf_popup_container arf_insert_popup_modal' id="arflite_insert_popup_modal">
		<div class='arf_popup_container_header'><?php echo esc_html__( 'ADD ARFORMS LITE FORM', 'arforms-form-builder' ); ?>        
		</div>
		<div class='arfinsertform_modal_container arf_popup_content_container'>
			<div class="main_div_container">
				<div class="select_form arfmarginb20">
					<label><?php echo esc_html__( 'Select a form to insert into page', 'arforms-form-builder' ); ?>&nbsp;<span class="newmodal_required">*</span></label>
					<div class="selectbox">
						<?php $arfliteformhelper->arflite_forms_dropdown_new( 'arfaddformid', '', 'Select form' ); ?>
					</div>
				</div>
				<input type="hidden" id="form_title_i" value="" />
			</div>
		</div>
		<div class="arf_popup_container_footer">
			<button type="button" class="arf_field_option_close_button" onclick="arflite_close_field_option_popup();"><?php echo esc_html__( 'Cancel', 'arforms-form-builder' ); ?></button>
			<button type="button" class="arf_field_option_submit_button" id="arfcontinuebtn" onclick="arfliteinsertform();"><?php echo esc_html__( 'Add to page', 'arforms-form-builder' ); ?></button>            
		</div>
	</div>
</div>
