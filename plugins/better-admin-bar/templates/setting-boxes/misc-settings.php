<?php
/**
 * Miscellaneus settings template.
 *
 * @package Better_Admin_Bar
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

$misc_settings = swift_control_get_misc_settings();

$remove_font_awesome = isset( $misc_settings['remove_font_awesome'] ) ? absint( $misc_settings['remove_font_awesome'] ) : 0;
$delete_on_uninstall = isset( $misc_settings['delete_on_uninstall'] ) ? absint( $misc_settings['delete_on_uninstall'] ) : 0;
?>

<div class="heatbox misc-settings-box">
	<h2>
		<?php _e( 'Misc', 'better-admin-bar' ); ?>
	</h2>
	<div class="setting-fields">

		<div class="field">
			<label for="remove_font_awesome" class="label checkbox-label">
				<?php _e( "Don't load FontAwesome 5 (your theme or another plugin may already include it)", 'better-admin-bar' ); ?>
				<input type="checkbox" name="remove_font_awesome" id="remove_font_awesome" value="1" class="general-setting-field" <?php checked( $remove_font_awesome, 1 ); ?>>
				<div class="indicator"></div>
			</label>
		</div>

		<div class="field">
			<label for="delete_on_uninstall" class="label checkbox-label">
				<?php _e( 'Remove data on uninstall', 'better-admin-bar' ); ?>
				<input type="checkbox" name="delete_on_uninstall" id="delete_on_uninstall" value="1" class="general-setting-field" <?php checked( $delete_on_uninstall, 1 ); ?>>
				<div class="indicator"></div>
			</label>
		</div>

	</div>
</div>
