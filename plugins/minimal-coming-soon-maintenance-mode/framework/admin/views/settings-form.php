<?php

/**
 * Form settings view for the plugin
 *
 * @link       http://www.webfactoryltd.com
 * @since      1.0
 */

if (!defined('WPINC')) {
	die;
}

?>

<div class="signals-tile" id="form">
	<div class="signals-tile-body">
		<div class="signals-tile-title"><?php esc_attr_e( 'FORM', 'minimal-coming-soon-maintenance-mode' ); ?></div>
		<p><?php esc_attr_e( 'Leads are the lifeline of any business. Make sure your form looks trustworthy. Configure technical details on the <a href="#email" class="csmm-change-tab">email tab</a>.', 'minimal-coming-soon-maintenance-mode' ); ?></p>


    <div id="csmm-setting-form-mc" style="<?php if ($signals_csmm_options['mail_system_to_use'] != 'mc') echo 'display: none;' ?>">
		<div class="signals-section-content">
			<div class="signals-double-group signals-clearfix">
				<div class="signals-form-group">
					<label for="signals_csmm_input_text" class="signals-strong"><?php esc_attr_e( 'Input Text', 'minimal-coming-soon-maintenance-mode' ); ?></label>
					<input type="text" name="signals_csmm_input_text" id="signals_csmm_input_text" value="<?php esc_attr_e( stripslashes( $signals_csmm_options['input_text'] ) ); ?>" placeholder="<?php esc_attr_e( 'Text for the Input field', 'minimal-coming-soon-maintenance-mode' ); ?>" class="signals-form-control">

					<p class="signals-form-help-block"><?php esc_attr_e( 'Enter the text which you would like to use as a placeholder text for the text input field.', 'minimal-coming-soon-maintenance-mode' ); ?></p>
				</div>

				<div class="signals-form-group">
					<label for="signals_csmm_button_text" class="signals-strong"><?php esc_attr_e( 'Button Text', 'minimal-coming-soon-maintenance-mode' ); ?></label>
					<input type="text" name="signals_csmm_button_text" id="signals_csmm_button_text" value="<?php esc_attr_e( stripslashes( $signals_csmm_options['button_text'] ) ); ?>" placeholder="<?php esc_attr_e( 'Text for the Button', 'minimal-coming-soon-maintenance-mode' ); ?>" class="signals-form-control">

					<p class="signals-form-help-block"><?php esc_attr_e( 'Enter the text for the button. Usually, it will be "Subscribe" or something like that.', 'minimal-coming-soon-maintenance-mode' ); ?></p>
				</div>
			</div>

      <div class="signals-double-group signals-clearfix">
        <div class="signals-form-group">
          <label for="signals_csmm_gdpr_text" class="signals-strong"><?php esc_attr_e( 'GDPR Consent Checkbox Text', 'minimal-coming-soon-maintenance-mode' ); ?></label>
          <textarea name="signals_csmm_gdpr_text" id="signals_csmm_gdpr_text" placeholder="" class="signals-form-control" rows="3"><?php echo esc_textarea( stripslashes( $signals_csmm_options['gdpr_text'] ) ); ?></textarea>

          <p class="signals-form-help-block"><?php esc_attr_e( 'Checkbox and the text above are displayed below the form email field. User has to check the checkbox in order to subscribe. Leave the field empty if you don\'t want to display the checkbox.', 'minimal-coming-soon-maintenance-mode' ); ?></p>
        </div>

        <div class="signals-form-group">
          <label for="signals_csmm_gdpr_fail" class="signals-strong"><?php esc_attr_e( 'GDPR Consent Fail Notice', 'minimal-coming-soon-maintenance-mode' ); ?></label>
          <textarea name="signals_csmm_gdpr_fail" id="signals_csmm_gdpr_fail" placeholder="" class="signals-form-control" rows="3"><?php echo esc_textarea( stripslashes( $signals_csmm_options['gdpr_fail'] ) ); ?></textarea>

          <p class="signals-form-help-block"><?php esc_attr_e( 'Alert text shown when user does not comply with the GPDR consent checkbox.', 'minimal-coming-soon-maintenance-mode' ); ?></p>
        </div>

      </div>

      <div class="signals-double-group signals-clearfix">
			<div class="signals-form-group">
				<label for="signals_csmm_ignore_styles" class="signals-strong"><?php esc_attr_e( 'Ignore Default Form Styles?', 'minimal-coming-soon-maintenance-mode' ); ?></label>
				<input type="checkbox" class="signals-form-ios" name="signals_csmm_ignore_styles" id="signals_csmm_ignore_styles" value="1"<?php checked( '1', $signals_csmm_options['ignore_form_styles'] ); ?>>

				<p class="signals-form-help-block"><?php esc_attr_e( 'Enable this option if you would like to use your custom form styles. The settings below will only be applicable when this option is turned on.', 'minimal-coming-soon-maintenance-mode' ); ?></p>
      </div>

      <div class="signals-form-group">
          <label for="signals_show_name" class="signals-strong pro-option">Show Name Field<sup>PRO</sup></label>
          <input disabled="disabled" type="checkbox" class="signals-form-ios skip-save pro-option" name="signals_show_name" id="signals_show_name" value="1">
          <p class="signals-form-help-block">It's preferable to ask for a name as it gives you the option to personalize communication later on. This is a <a href="#pro" class="csmm-change-tab">PRO feature</a>.</p>
        </div>
			</div>

			<div class="signals-double-group signals-clearfix">
				<div class="signals-form-group">
					<label for="signals_csmm_input_size" class="signals-strong"><?php esc_attr_e( 'Input Text Size', 'minimal-coming-soon-maintenance-mode' ); ?></label>

					<select name="signals_csmm_input_size" id="signals_csmm_input_size">
						<?php

							// Loading font sizes with the help of a loop
							for ( $i = 11; $i < 41; $i++ ) {
								echo '<option value="' . esc_attr($i) . '"' . selected( $signals_csmm_options['input_font_size'], $i ) . '>' . esc_attr($i) . __( 'px', 'minimal-coming-soon-maintenance-mode' ) . '</option>';
							}

						?>
					</select>

					<p class="signals-form-help-block"><?php esc_attr_e( 'Font size for the text input field.', 'minimal-coming-soon-maintenance-mode' ); ?></p>
				</div>

				<div class="signals-form-group">
					<label for="signals_csmm_button_size" class="signals-strong"><?php esc_attr_e( 'Button Text Size', 'minimal-coming-soon-maintenance-mode' ); ?></label>

					<select name="signals_csmm_button_size" id="signals_csmm_button_size">
						<?php

							// Loading font sizes with the help of a loop
							for ( $i = 11; $i < 41; $i++ ) {
								echo '<option value="' . esc_attr($i) . '"' . selected( $signals_csmm_options['button_font_size'], $i ) . '>' . esc_attr($i) . __( 'px', 'minimal-coming-soon-maintenance-mode' ) . '</option>';
							}

						?>
					</select>

					<p class="signals-form-help-block"><?php esc_attr_e( 'Font size for the button text.', 'minimal-coming-soon-maintenance-mode' ); ?></p>
				</div>
			</div>

			<div class="signals-double-group signals-clearfix">
				<div class="signals-form-group">
					<label for="signals_csmm_input_color" class="signals-strong"><?php esc_attr_e( 'Input Text Color', 'minimal-coming-soon-maintenance-mode' ); ?></label>
					<input type="text" name="signals_csmm_input_color" id="signals_csmm_input_color" value="<?php esc_attr_e( $signals_csmm_options['input_font_color'] ); ?>" placeholder="<?php esc_attr_e( 'Font color for the Input text', 'minimal-coming-soon-maintenance-mode' ); ?>" class="signals-form-control color jscolor {required:false}">

					<p class="signals-form-help-block"><?php esc_attr_e( 'Select font color for the input text field.', 'minimal-coming-soon-maintenance-mode' ); ?></p>
				</div>

				<div class="signals-form-group">
					<label for="signals_csmm_button_color" class="signals-strong"><?php esc_attr_e( 'Button Text Color', 'minimal-coming-soon-maintenance-mode' ); ?></label>
					<input type="text" name="signals_csmm_button_color" id="signals_csmm_button_color" value="<?php esc_attr_e( $signals_csmm_options['button_font_color'] ); ?>" placeholder="<?php esc_attr_e( 'Font color for the Button text', 'minimal-coming-soon-maintenance-mode' ); ?>" class="signals-form-control color jscolor {required:false}">

					<p class="signals-form-help-block"><?php esc_attr_e( 'Select font color for the button text.', 'minimal-coming-soon-maintenance-mode' ); ?></p>
				</div>
			</div>

			<div class="signals-double-group signals-clearfix">
				<div class="signals-form-group">
					<label for="signals_csmm_input_bg" class="signals-strong"><?php esc_attr_e( 'Input Background Color', 'minimal-coming-soon-maintenance-mode' ); ?></label>
					<input type="text" name="signals_csmm_input_bg" id="signals_csmm_input_bg" value="<?php esc_attr_e( $signals_csmm_options['input_bg'] ); ?>" placeholder="<?php esc_attr_e( 'Background color for the Input field', 'minimal-coming-soon-maintenance-mode' ); ?>" class="signals-form-control color jscolor {required:false}">

					<p class="signals-form-help-block"><?php esc_attr_e( 'Select background color for the input text field.', 'minimal-coming-soon-maintenance-mode' ); ?></p>
				</div>

				<div class="signals-form-group">
					<label for="signals_csmm_button_bg" class="signals-strong"><?php esc_attr_e( 'Button Background Color', 'minimal-coming-soon-maintenance-mode' ); ?></label>
					<input type="text" name="signals_csmm_button_bg" id="signals_csmm_button_bg" value="<?php esc_attr_e( $signals_csmm_options['button_bg'] ); ?>" placeholder="<?php esc_attr_e( 'Background color for the Button', 'minimal-coming-soon-maintenance-mode' ); ?>" class="signals-form-control color jscolor {required:false}">

					<p class="signals-form-help-block"><?php esc_attr_e( 'Select background color for the button.', 'minimal-coming-soon-maintenance-mode' ); ?></p>
				</div>
			</div>

			<div class="signals-double-group signals-clearfix">
				<div class="signals-form-group">
					<label for="signals_csmm_input_bg_hover" class="signals-strong"><?php esc_attr_e( 'Input Focus Background Color', 'minimal-coming-soon-maintenance-mode' ); ?></label>
					<input type="text" name="signals_csmm_input_bg_hover" id="signals_csmm_input_bg_hover" value="<?php esc_attr_e( $signals_csmm_options['input_bg_hover'] ); ?>" placeholder="<?php esc_attr_e( 'Background color for the Input field when it gets clicked', 'minimal-coming-soon-maintenance-mode' ); ?>" class="signals-form-control color jscolor {required:false}">

					<p class="signals-form-help-block"><?php esc_attr_e( 'Select background color for the input text field when it gets clicked.', 'minimal-coming-soon-maintenance-mode' ); ?></p>
				</div>

				<div class="signals-form-group">
					<label for="signals_csmm_button_bg_hover" class="signals-strong"><?php esc_attr_e( 'Button Hover Background Color', 'minimal-coming-soon-maintenance-mode' ); ?></label>
					<input type="text" name="signals_csmm_button_bg_hover" id="signals_csmm_button_bg_hover" value="<?php esc_attr_e( $signals_csmm_options['button_bg_hover'] ); ?>" placeholder="<?php esc_attr_e( 'Background color for the Button on hover', 'minimal-coming-soon-maintenance-mode' ); ?>" class="signals-form-control color jscolor {required:false}">

					<p class="signals-form-help-block"><?php esc_attr_e( 'Select background color for the button on mouse hover.', 'minimal-coming-soon-maintenance-mode' ); ?></p>
				</div>
			</div>

			<div class="signals-double-group signals-clearfix">
				<div class="signals-form-group">
					<label for="signals_csmm_input_border" class="signals-strong"><?php esc_attr_e( 'Input Border Color', 'minimal-coming-soon-maintenance-mode' ); ?></label>
					<input type="text" name="signals_csmm_input_border" id="signals_csmm_input_border" value="<?php esc_attr_e( $signals_csmm_options['input_border'] ); ?>" placeholder="<?php esc_attr_e( 'Border color for the Input field', 'minimal-coming-soon-maintenance-mode' ); ?>" class="signals-form-control color jscolor {required:false}">

					<p class="signals-form-help-block"><?php esc_attr_e( 'Select border color for the input field.', 'minimal-coming-soon-maintenance-mode' ); ?></p>
				</div>

				<div class="signals-form-group">
					<label for="signals_csmm_button_border" class="signals-strong"><?php esc_attr_e( 'Button Border Color', 'minimal-coming-soon-maintenance-mode' ); ?></label>
					<input type="text" name="signals_csmm_button_border" id="signals_csmm_button_border" value="<?php esc_attr_e( $signals_csmm_options['button_border'] ); ?>" placeholder="<?php esc_attr_e( 'Border color for the Button', 'minimal-coming-soon-maintenance-mode' ); ?>" class="signals-form-control color jscolor {required:false}">

					<p class="signals-form-help-block"><?php esc_attr_e( 'Select border color for the button.', 'minimal-coming-soon-maintenance-mode' ); ?></p>
				</div>
			</div>

			<div class="signals-double-group signals-clearfix">
				<div class="signals-form-group">
					<label for="signals_csmm_input_border_hover" class="signals-strong"><?php esc_attr_e( 'Input Focus Border Color', 'minimal-coming-soon-maintenance-mode' ); ?> </label>
					<input type="text" name="signals_csmm_input_border_hover" id="signals_csmm_input_border_hover" value="<?php esc_attr_e( $signals_csmm_options['input_border_hover'] ); ?>" placeholder="<?php esc_attr_e( 'Border color for the Input field when it gets clicked', 'minimal-coming-soon-maintenance-mode' ); ?>" class="signals-form-control color jscolor {required:false}">

					<p class="signals-form-help-block"><?php esc_attr_e( 'Select border color for the input field when it gets clicked.', 'minimal-coming-soon-maintenance-mode' ); ?></p>
				</div>

				<div class="signals-form-group">
					<label for="signals_csmm_button_border_hover" class="signals-strong"><?php esc_attr_e( 'Button Hover Border Color', 'minimal-coming-soon-maintenance-mode' ); ?> </label>
					<input type="text" name="signals_csmm_button_border_hover" id="signals_csmm_button_border_hover" value="<?php esc_attr_e( $signals_csmm_options['button_border_hover'] ); ?>" placeholder="<?php esc_attr_e( 'Border color for the Button on hover', 'minimal-coming-soon-maintenance-mode' ); ?>" class="signals-form-control color jscolor {required:false}">

					<p class="signals-form-help-block"><?php esc_attr_e( 'Select border color for the button on mouse hover.', 'minimal-coming-soon-maintenance-mode' ); ?></p>
				</div>
			</div>

      <div class="signals-double-group signals-clearfix">
        <div class="signals-form-group">
          <label for="form_placeholder_color" class="signals-strong">Input Fields Placeholder Color</label>
          <input type="text" name="form_placeholder_color" id="form_placeholder_color" value="<?php esc_attr_e( $signals_csmm_options['form_placeholder_color'] ); ?>" class="signals-form-control color jscolor {required:false}">
          <p class="signals-form-help-block"><?php esc_attr_e( 'Placeholder (default text) color in input fields.', 'minimal-coming-soon-maintenance-mode' ); ?></p>
        </div>
      </div>

			<div class="signals-double-group signals-clearfix">
				<div class="signals-form-group">
					<label for="signals_csmm_success_bg" class="signals-strong"><?php esc_attr_e( 'Success Message Background Color', 'minimal-coming-soon-maintenance-mode' ); ?></span></label>
					<input type="text" name="signals_csmm_success_bg" id="signals_csmm_success_bg" value="<?php esc_attr_e( $signals_csmm_options['success_background'] ); ?>" placeholder="<?php esc_attr_e( 'Background color for the success message', 'minimal-coming-soon-maintenance-mode' ); ?>" class="signals-form-control color jscolor {required:false}">

					<p class="signals-form-help-block"><?php esc_attr_e( 'Select background color for the success message.', 'minimal-coming-soon-maintenance-mode' ); ?></p>
				</div>

				<div class="signals-form-group">
					<label for="signals_csmm_success_color" class="signals-strong"><?php esc_attr_e( 'Success Message Text Color', 'minimal-coming-soon-maintenance-mode' ); ?> </label>
					<input type="text" name="signals_csmm_success_color" id="signals_csmm_success_color" value="<?php esc_attr_e( $signals_csmm_options['success_color'] ); ?>" placeholder="<?php esc_attr_e( 'Text color for the success message', 'minimal-coming-soon-maintenance-mode' ); ?>" class="signals-form-control color jscolor {required:false}">

					<p class="signals-form-help-block"><?php esc_attr_e( 'Select text color for the success message.', 'minimal-coming-soon-maintenance-mode' ); ?></p>
				</div>
			</div>

			<div class="signals-double-group signals-clearfix">
				<div class="signals-form-group">
					<label for="signals_csmm_error_bg" class="signals-strong"><?php esc_attr_e( 'Error Message Background Color', 'minimal-coming-soon-maintenance-mode' ); ?></span></label>
					<input type="text" name="signals_csmm_error_bg" id="signals_csmm_error_bg" value="<?php esc_attr_e( $signals_csmm_options['error_background'] ); ?>" placeholder="<?php esc_attr_e( 'Background color for the error message', 'minimal-coming-soon-maintenance-mode' ); ?>" class="signals-form-control color jscolor {required:false}">

					<p class="signals-form-help-block"><?php esc_attr_e( 'Select background color for the error message.', 'minimal-coming-soon-maintenance-mode' ); ?></p>
				</div>

				<div class="signals-form-group">
					<label for="signals_csmm_error_color" class="signals-strong"><?php esc_attr_e( 'Error Message Text Color', 'minimal-coming-soon-maintenance-mode' ); ?></label>
					<input type="text" name="signals_csmm_error_color" id="signals_csmm_error_color" value="<?php esc_attr_e( $signals_csmm_options['error_color'] ); ?>" placeholder="<?php esc_attr_e( 'Text color for the error message', 'minimal-coming-soon-maintenance-mode' ); ?>" class="signals-form-control color jscolor {required:false}">

					<p class="signals-form-help-block"><?php esc_attr_e( 'Select text color for the error message.', 'minimal-coming-soon-maintenance-mode' ); ?></p>
				</div>
			</div>
		</div>
		</div>

	</div>
</div><!-- #form -->
