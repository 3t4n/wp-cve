<?php
/**
 * Miscellaneus settings template.
 *
 * @package Custom_Login_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Display misc. settings template.
 *
 * @param array $settings The plugin settings.
 */
return function ( $settings ) {

	$clean_deactivation = isset( $settings['dashboard_delete_db'] ) ? $settings['dashboard_delete_db'] : 0;
	$clean_deactivation = 'yes' === strtolower( $clean_deactivation ) ? 1 : $clean_deactivation;
	$clean_deactivation = 'no' === strtolower( $clean_deactivation ) ? 0 : $clean_deactivation;
	?>

	<div class="heatbox misc-settings-box">
		<h2>
			<?php _e( 'Misc', 'erident-custom-login-and-dashboard' ); ?>
		</h2>
		<div class="setting-fields">

			<div class="field">
				<label for="dashboard_delete_db" class="label checkbox-label">
					<?php _e( 'Remove data on uninstall', 'erident-custom-login-and-dashboard' ); ?>
					<p class="description">
						<?php _e( 'If checked, all data will be removed on plugin deactivation.', 'erident-custom-login-and-dashboard' ); ?>
					</p>
					<input type="checkbox" name="dashboard_delete_db" id="dashboard_delete_db" value="1" class="general-setting-field" <?php checked( $clean_deactivation, 1 ); ?>>
					<div class="indicator"></div>
				</label>
			</div>

		</div>
	</div>

	<?php
};
