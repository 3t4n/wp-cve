<?php
global $arf_confirmation_summary;
$arf_confirmation_summary = new arflite_submit_confirmation_summary();

class arflite_submit_confirmation_summary {

	function __construct() {

		add_action( 'arflite_option_before_submit_conditional_logic', array( $this, 'arflite_submit_confirmation_summary_options' ), 12, 2 );

	}

	function arflite_submit_confirmation_summary_options( $arflite_id, $values ) {
		global $arflitemainhelper, $arfliteformcontroller;

		if ( ! isset( $values['arf_confirmation_summary_display'] ) || ( isset( $values['arf_confirmation_summary_display'] ) && $values['arf_confirmation_summary_display'] == '' ) ) {
			$values['arf_confirmation_summary_display'] = 'before';
		}
		?>
		<div class="arf_confirmation_summary_container">
			<div class="arf_confirmation_summary_inner_container">
				
				<div class="arf_confirmation_summary_enable">
					<div class="arf_popup_checkbox_wrapper arflite_confirmation_summary_checkbox_wrapper">
						<div class="arf_custom_checkbox_div">
							<div class="arf_custom_checkbox_wrapper">
								<input type="checkbox" class="arf_enable_confirmation_summary arf_restricted_control" id="arf_confirmation_summary" value="1" />
								<svg width="18px" height="18px">
									<?php echo ARFLITE_CUSTOM_UNCHECKED_ICON; //phpcs:ignore ?>
									<?php echo ARFLITE_CUSTOM_CHECKED_ICON; //phpcs:ignore ?>
								</svg>
							</div>
							<span>
								<label for="arf_confirmation_summary"><?php echo esc_html__( 'Show confirmation (Summary)', 'arforms-form-builder' ); ?><span class="arflite_pro_version_notice">(Premium)</span></label>
							</span>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php

	}
}
