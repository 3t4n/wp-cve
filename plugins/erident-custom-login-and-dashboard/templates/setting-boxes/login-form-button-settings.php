<?php
/**
 * Login form button settings template.
 *
 * @package Custom_Login_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Display login form button settings template.
 *
 * @param array $settings The plugin settings.
 */
return function ( $settings ) {

	$button_bg_color   = isset( $settings['dashboard_button_color'] ) && ! empty( $settings['dashboard_button_color'] ) ? $settings['dashboard_button_color'] : '';
	$button_text_color = isset( $settings['dashboard_button_text_color'] ) && ! empty( $settings['dashboard_button_text_color'] ) ? $settings['dashboard_button_text_color'] : '';
	?>

	<div class="heatbox dashboard-settings-box">
		<h2>
			<?php _e( 'Form Button Settings', 'erident-custom-login-and-dashboard' ); ?>
		</h2>
		<div class="setting-fields">

			<div class="field is-horizontal">
				<div class="field-label">
					<label for="dashboard_button_color" class="label">
						<?php _e( 'Background Color', 'erident-custom-login-and-dashboard' ); ?>
					</label>
				</div>
				<div class="field-body">
					<div class="field">
						<div class="control">
							<input type="text" name="dashboard_button_color" id="dashboard_button_color" value="<?php echo esc_attr( $button_bg_color ); ?>" class="color-picker-field general-setting-field" data-alpha="true" data-default-color="<?php echo esc_attr( $button_bg_color ); ?>">
						</div>
					</div>
				</div>
			</div>

			<div class="field is-horizontal">
				<div class="field-label">
					<label for="dashboard_button_text_color" class="label">
						<?php _e( 'Font Color', 'erident-custom-login-and-dashboard' ); ?>
					</label>
				</div>
				<div class="field-body">
					<div class="field">
						<div class="control">
							<input type="text" name="dashboard_button_text_color" id="dashboard_button_text_color" value="<?php echo esc_attr( $button_text_color ); ?>" class="color-picker-field general-setting-field" data-alpha="true" data-default-color="<?php echo esc_attr( $button_text_color ); ?>">
						</div>
					</div>
				</div>
			</div>

		</div>
	</div>

	<?php
};
