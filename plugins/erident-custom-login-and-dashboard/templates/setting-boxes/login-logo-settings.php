<?php
/**
 * Login logo settings template.
 *
 * @package Custom_Login_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Display login logo settings template.
 *
 * @param array $settings The plugin settings.
 */
return function ( $settings ) {

	$logo_image_url = isset( $settings['dashboard_image_logo'] ) && ! empty( $settings['dashboard_image_logo'] ) ? $settings['dashboard_image_logo'] : '';
	$logo_width     = isset( $settings['dashboard_image_logo_width'] ) && ! empty( $settings['dashboard_image_logo_width'] ) ? $settings['dashboard_image_logo_width'] : '';
	$logo_height    = isset( $settings['dashboard_image_logo_height'] ) && ! empty( $settings['dashboard_image_logo_height'] ) ? $settings['dashboard_image_logo_height'] : '';
	$logo_hint_text = isset( $settings['dashboard_power_text'] ) && ! empty( $settings['dashboard_power_text'] ) ? $settings['dashboard_power_text'] : '';
	?>

  <div class="heatbox login-bg-settings-box">
		<h2>
			<?php _e( 'Logo Settings', 'erident-custom-login-and-dashboard' ); ?>
		</h2>
		<div class="setting-fields">

			<div class="field is-horizontal">
				<div class="field-label">
					<label for="dashboard_image_logo" class="label">
						<?php _e( 'Logo URL', 'erident-custom-login-and-dashboard' ); ?>
					</label>
				</div>
				<div class="field-body">
					<div class="field">
						<div class="control">
							<input type="text" id="dashboard_image_logo" name="dashboard_image_logo" value="<?php echo esc_url( $logo_image_url ); ?>" class="general-setting-field is-small cldashboard-logo-image-field">
							<button type="button" class="button-secondary cldashboard-upload-button">
								<?php _e( 'Add Logo', 'erident-custom-login-and-dashboard' ); ?>
							</button>
							<button type="button" class="button-secondary cldashboard-clear-button">x</button>
						</div>
					</div>
				</div>
			</div>

			<div class="field is-horizontal">
				<div class="field-label">
					<label for="dashboard_image_logo_width" class="label">
						<?php _e( 'Logo Width', 'erident-custom-login-and-dashboard' ); ?>
					</label>
				</div>
				<div class="field-body">
					<div class="field">
						<div class="control">
							<input type="number" name="dashboard_image_logo_width" id="dashboard_image_logo_width" value="<?php echo esc_attr( $logo_width ); ?>" class="general-setting-field is-tiny">
							<code>px</code>
						</div>
					</div>
				</div>
			</div>

			<div class="field is-horizontal">
				<div class="field-label">
					<label for="dashboard_image_logo_height" class="label">
						<?php _e( 'Logo Height', 'erident-custom-login-and-dashboard' ); ?>
					</label>
				</div>
				<div class="field-body">
					<div class="field">
						<div class="control">
							<input type="number" name="dashboard_image_logo_height" id="dashboard_image_logo_height" value="<?php echo esc_attr( $logo_height ); ?>" class="general-setting-field is-tiny">
							<code>px</code>
						</div>
					</div>
				</div>
			</div>

			<div class="field is-horizontal">
				<div class="field-label">
					<label for="dashboard_power_text" class="label">
						<?php _e( 'Logo Title', 'erident-custom-login-and-dashboard' ); ?>
					</label>
				</div>
				<div class="field-body">
					<div class="field">
						<div class="control">
							<input type="text" name="dashboard_power_text" id="dashboard_power_text" value="<?php echo esc_attr( $logo_hint_text ); ?>" class="general-setting-field">
						</div>
					</div>
				</div>
			</div>

		</div>
	</div>

	<?php
};
