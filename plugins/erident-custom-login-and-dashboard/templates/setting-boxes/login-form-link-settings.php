<?php
/**
 * Login form link settings template.
 *
 * @package Custom_Login_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Display login form link settings template.
 *
 * @param array $settings The plugin settings.
 */
return function ( $settings ) {

	$link_color    = isset( $settings['dashboard_link_color'] ) && ! empty( $settings['dashboard_link_color'] ) ? $settings['dashboard_link_color'] : '';
	$enable_shadow = isset( $settings['dashboard_check_shadow'] ) ? $settings['dashboard_check_shadow'] : 0;
	$enable_shadow = 'yes' === strtolower( $enable_shadow ) ? 1 : $enable_shadow;
	$enable_shadow = 'no' === strtolower( $enable_shadow ) ? 0 : $enable_shadow;
	$shadow_color  = isset( $settings['dashboard_link_shadow'] ) && ! empty( $settings['dashboard_link_shadow'] ) ? $settings['dashboard_link_shadow'] : '';
	?>

	<div class="heatbox dashboard-settings-box">
		<h2>
			<?php _e( 'Form Link Settings', 'erident-custom-login-and-dashboard' ); ?>
		</h2>
		<div class="setting-fields">

			<div class="field is-horizontal">
				<div class="field-label">
					<label for="dashboard_link_color" class="label">
						<?php _e( 'Color', 'erident-custom-login-and-dashboard' ); ?>
					</label>
				</div>
				<div class="field-body">
					<div class="field">
						<div class="control">
							<input type="text" name="dashboard_link_color" id="dashboard_link_color" value="<?php echo esc_attr( $link_color ); ?>" class="color-picker-field general-setting-field" data-alpha="true" data-default-color="<?php echo esc_attr( $link_color ); ?>">
						</div>
					</div>
				</div>
			</div>

			<div class="field is-horizontal">
				<div class="field-label">
					<label for="dashboard_check_shadow" class="label">
						<?php _e( 'Text Shadow', 'erident-custom-login-and-dashboard' ); ?>
					</label>
				</div>
				<div class="field-body">
					<div class="field">
						<label for="dashboard_check_shadow" class="label checkbox-label">
							<?php _e( 'Enable', 'erident-custom-login-and-dashboard' ); ?>
							<input type="checkbox" name="dashboard_check_shadow" id="dashboard_check_shadow" value="1" class="general-setting-field" <?php checked( $enable_shadow, 1 ); ?>>
							<div class="indicator"></div>
						</label>
					</div>
				</div>
			</div>

			<div class="field is-horizontal" data-show-if-field-checked="dashboard_check_shadow">
				<div class="field-label">
					<label for="dashboard_link_shadow" class="label">
						<?php _e( 'Text Shadow Color', 'erident-custom-login-and-dashboard' ); ?>
					</label>
				</div>
				<div class="field-body">
					<div class="field">
						<div class="control">
							<input type="text" name="dashboard_link_shadow" id="dashboard_link_shadow" value="<?php echo esc_attr( $shadow_color ); ?>" class="color-picker-field general-setting-field" data-alpha="true" data-default-color="<?php echo esc_attr( $shadow_color ); ?>">
						</div>
					</div>
				</div>
			</div>

		</div>
	</div>

	<?php
};
