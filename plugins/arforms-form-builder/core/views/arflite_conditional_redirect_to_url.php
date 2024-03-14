<?php
global $arf_conditional_redirect_to_url;

$arf_conditional_redirect_to_url = new ARFLITE_conditional_redirect_to_url();

class ARFLITE_conditional_redirect_to_url {

	function __construct() {

		add_action( 'arflite_form_submit_after_redirect_to_url', array( $this, 'arflite_form_submit_after_redirect_to_url_html' ), 10, 2 );
	}

	function arflite_form_submit_after_redirect_to_url_html( $arflite_id, $values ) {
		$allowed_html_arr = arflite_retrieve_attrs_for_wp_kses();
		?>

		<div class="arflite_conditional_redirect_to_url_space">&nbsp;</div>

		<div class="arf_or_option arflite_conditional_redirect_to_url_or_label"><?php echo esc_html__( 'Or', 'arforms-form-builder' ); ?></div>

		<div class="arfcolumnleft arf_custom_margin_redirect arfsetcondtionalredirect">
			<div class="arf_custom_checkbox_div">
				<div class="arf_custom_checkbox_wrapper" >
					<input type="checkbox" value="1" id="arf_conditional_redirect_enable" class="chkstanard arf_restricted_control">
					<svg width="18px" height="18px">
					<?php echo wp_kses( ARFLITE_CUSTOM_UNCHECKED_ICON, $allowed_html_arr ); ?>
					<?php echo wp_kses( ARFLITE_CUSTOM_CHECKED_ICON, $allowed_html_arr ); ?>
					</svg>
				</div>
				<span>
					<label for="arf_conditional_redirect_enable"><?php echo esc_html__( 'Set conditional redirect URL', 'arforms-form-builder' ); ?> (<span class="howto"><?php echo esc_html__( 'Please insert url with http:// or https://.', 'arforms-form-builder' ); ?></span>)<span class="arflite_pro_version_notice">(Premium)</span></label>
				</span>
			</div>
		</div>
		<div class="arfcolumnleft">&nbsp;</div>

		<?php
	}

}
?>
