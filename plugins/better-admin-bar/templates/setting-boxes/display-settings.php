<?php
/**
 * Display settings template.
 *
 * @package Better_Admin_Bar
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

$display_settings      = swift_control_get_display_settings();
$disable_swift_control = $display_settings['disable_swift_control'];
$remove_indicator      = $display_settings['remove_indicator'];
$expanded              = $display_settings['expanded'];
?>

<div class="heatbox display-settings-box">
	<h2>
		<?php _e( 'Display Settings', 'better-admin-bar' ); ?>
	</h2>
	<div class="setting-fields">

		<div class="field">
			<label for="disable_swift_control" class="label checkbox-label">
				<?php _e( 'Disable Quick Access Panel', 'better-admin-bar' ); ?>
				<input type="checkbox" name="disable_swift_control" id="disable_swift_control" value="1" class="general-setting-field" <?php checked( $disable_swift_control, 1 ); ?>>
				<div class="indicator"></div>
			</label>
		</div>

		<div class="field" data-show-if-field-unchecked="disable_swift_control">
			<label for="remove_indicator" class="label checkbox-label">
				<?php _e( 'Remove Click Indicator (Arrow)', 'better-admin-bar' ); ?>
				<input type="checkbox" name="remove_indicator" id="remove_indicator" value="1" class="general-setting-field" <?php checked( $remove_indicator, 1 ); ?>>
				<div class="indicator"></div>
			</label>
		</div>

		<div class="field" data-show-if-field-unchecked="disable_swift_control">
			<label for="expanded" class="label checkbox-label">
				<?php _e( 'Expand Quick Access Panel by default', 'better-admin-bar' ); ?>
				<input type="checkbox" name="expanded" id="expanded" value="1" class="general-setting-field" <?php checked( $expanded, 1 ); ?>>
				<div class="indicator"></div>
			</label>
		</div>

	</div>
</div>
