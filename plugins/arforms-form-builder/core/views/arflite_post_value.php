<?php
global $arf_post_value_class;
$arf_post_value_class = new arflite_post_value();

class arflite_post_value {
	function __construct() {

		add_action( 'arflite_option_before_submit_conditional_logic', array( $this, 'arflite_post_values_after_redirect_to_url_html' ), 11, 2 );

	}

	function arflite_post_values_after_redirect_to_url_html( $arflite_id, $values ) {?>
		<div class="arf_submit_action_post_values_container">
			<div class="arf_submit_action_post_values_inner_container">
				<div class="arf_submit_action_post_values_enable">
					<div class="arf_popup_checkbox_wrapper arflite_post_value_checkbox_wrapper">
						<div class="arf_custom_checkbox_div">
							<div class="arf_custom_checkbox_wrapper">
								<input type="checkbox" class="arf_enable_disable_post_values arf_restricted_control" id="arf_show_post_value" value="1" />
								<svg width="18px" height="18px">
									<?php echo ARFLITE_CUSTOM_UNCHECKED_ICON; //phpcs:ignore ?>
									<?php echo ARFLITE_CUSTOM_CHECKED_ICON; //phpcs:ignore ?>
								</svg>
							</div>
							<span>
								<label for="arf_show_post_value"><?php echo esc_html__( 'Send Form Data internaly to Custom URL', 'arforms-form-builder' ); ?> (<?php echo esc_html__( 'Webhook', 'arforms-form-builder' ); ?>)<span class="arflite_pro_version_notice">(Premium)</span></label>
							</span>
						</div>
					</div>
					<span class="arf_submit_action_post_values_inner_block">
					<?php echo esc_html__( '(Upon successful submission form entry data will be sent to below mentioned url using POST method.)', 'arforms-form-builder' ); ?></span>
				</div>
			</div>
	  </div>
		<?php
	}
}
?>
