<?php
/**
 * Color settings template.
 *
 * @package Better_Admin_Bar
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

$default_color_settings = swift_control_get_default_color_settings();
$color_settings         = swift_control_get_color_settings();
?>

<div class="heatbox color-settings-box">
	<h2>
		<?php _e( 'Color Settings', 'better-admin-bar' ); ?>
	</h2>

	<div class="setting-fields">

		<div class="field is-horizontal">
			<div class="field-label">
				<label for="setting_button_bg_color" class="label">
					<?php _e( 'Accent Color' ); ?>
				</label>
			</div>
			<div class="field-body">
				<div class="field">
					<div class="control">
						<input type="text" name="setting_button_bg_color" id="setting_button_bg_color" value="<?php echo esc_attr( $color_settings['setting_button_bg_color'] ); ?>" class="color-picker-field general-setting-field has-instant-preview" data-alpha="true" data-default-color="<?php echo esc_attr( $default_color_settings['setting_button_bg_color'] ); ?>">
					</div>
				</div>
			</div>
		</div>

		<div class="field is-horizontal">
			<div class="field-label">
				<label for="setting_button_icon_color" class="label">
					<?php _e( 'Icon Color' ); ?>
				</label>
			</div>
			<div class="field-body">
				<div class="field">
					<div class="control">
						<input type="text" name="setting_button_icon_color" id="setting_button_icon_color" value="<?php echo esc_attr( $color_settings['setting_button_icon_color'] ); ?>" class="color-picker-field general-setting-field has-instant-preview" data-alpha="true" data-default-color="<?php echo esc_attr( $default_color_settings['setting_button_icon_color'] ); ?>">
					</div>
				</div>
			</div>
		</div>

		<hr>

		<div class="field is-horizontal">
			<div class="field-label">
				<label for="widget_bg_color" class="label">
					<?php _e( 'Background Color' ); ?>
				</label>
			</div>
			<div class="field-body">
				<div class="field">
					<div class="control">
						<input type="text" name="widget_bg_color" id="widget_bg_color" value="<?php echo esc_attr( $color_settings['widget_bg_color'] ); ?>" class="color-picker-field general-setting-field has-instant-preview" data-alpha="true" data-default-color="<?php echo esc_attr( $default_color_settings['widget_bg_color'] ); ?>">
					</div>
				</div>
			</div>
		</div>

		<div class="field is-horizontal">
			<div class="field-label">
				<label for="widget_bg_color_hover" class="label">
					<?php _e( 'Hover' ); ?>
				</label>
			</div>
			<div class="field-body">
				<div class="field">
					<div class="control">
						<input type="text" name="widget_bg_color_hover" id="widget_bg_color_hover" value="<?php echo esc_attr( $color_settings['widget_bg_color_hover'] ); ?>" class="color-picker-field general-setting-field has-instant-preview" data-alpha="true" data-default-color="<?php echo esc_attr( $default_color_settings['widget_bg_color_hover'] ); ?>">
					</div>
				</div>
			</div>
		</div>

		<div class="field is-horizontal">
			<div class="field-label">
				<label for="widget_icon_color" class="label">
					<?php _e( 'Icon Color' ); ?>
				</label>
			</div>
			<div class="field-body">
				<div class="field">
					<div class="control">
						<input type="text" name="widget_icon_color" id="widget_icon_color" value="<?php echo esc_attr( $color_settings['widget_icon_color'] ); ?>" class="color-picker-field general-setting-field has-instant-preview" data-alpha="true" data-default-color="<?php echo esc_attr( $default_color_settings['widget_icon_color'] ); ?>">
					</div>
				</div>
			</div>
		</div>

	</div><!-- .setting-fields -->
</div><!-- .color-settings-box -->
