<?php
/**
 * Login form label settings template.
 *
 * @package Custom_Login_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Display login form label settings template.
 *
 * @param array $settings The plugin settings.
 */
return function ( $settings ) {

	$font_color = isset( $settings['dashboard_text_color'] ) && ! empty( $settings['dashboard_text_color'] ) ? $settings['dashboard_text_color'] : '';
	$font_size  = isset( $settings['dashboard_label_text_size'] ) && ! empty( $settings['dashboard_label_text_size'] ) ? $settings['dashboard_label_text_size'] : '';
	?>

	<div class="heatbox dashboard-settings-box">
		<h2>
			<?php _e( 'Form Label Settings', 'erident-custom-login-and-dashboard' ); ?>
		</h2>
		<div class="setting-fields">

			<div class="field is-horizontal">
				<div class="field-label">
					<label for="dashboard_text_color" class="label">
						<?php _e( 'Font Color', 'erident-custom-login-and-dashboard' ); ?>
					</label>
				</div>
				<div class="field-body">
					<div class="field">
						<div class="control">
							<input type="text" name="dashboard_text_color" id="dashboard_text_color" value="<?php echo esc_attr( $font_color ); ?>" class="color-picker-field general-setting-field" data-alpha="true" data-default-color="<?php echo esc_attr( $font_color ); ?>">
						</div>
					</div>
				</div>
			</div>

			<div class="field is-horizontal">
				<div class="field-label">
					<label for="dashboard_label_text_size" class="label">
						<?php _e( 'Font Size', 'erident-custom-login-and-dashboard' ); ?>
					</label>
				</div>
				<div class="field-body">
					<div class="field">
						<div class="control">
							<input type="number" min="0" step="1" name="dashboard_label_text_size" id="dashboard_label_text_size" value="<?php echo esc_attr( $font_size ); ?>" class="general-setting-field is-tiny">
							<code>px</code>
						</div>
					</div>
				</div>
			</div>

		</div>
	</div>

	<?php
};
