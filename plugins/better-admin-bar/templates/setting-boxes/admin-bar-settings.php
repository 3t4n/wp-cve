<?php
/**
 * Admin bar settings template.
 *
 * @package Better_Admin_Bar
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

$admin_bar_settings = swift_control_get_admin_bar_settings();

$roles_obj = new \WP_Roles();
$roles     = $roles_obj->role_names;
?>

<div class="heatbox admin-bar-settings-box">
	<h2>
		<?php _e( 'Remove Admin Bar', 'better-admin-bar' ); ?>
	</h2>
	<div class="setting-fields">

		<div class="field">
			<label for="remove_by_roles" class="label select2-label">
				<p>
					<?php _e( 'Remove Admin Bar from your website for:', 'better-admin-bar' ); ?>
				</p>
				<select name="remove_by_roles[]" id="remove_by_roles" class="general-setting-field multiselect remove-admin-bar use-select2 is-fullwidth" multiple>
					<option value="all" <?php echo esc_attr( in_array( 'all', $admin_bar_settings['remove_by_roles'], true ) ? 'selected' : '' ); ?>><?php _e( 'All', 'better-admin-bar' ); ?></option>

					<?php foreach ( $roles as $role_key => $role_name ) : ?>
						<?php
						$selected_attr = '';

						if ( in_array( $role_key, $admin_bar_settings['remove_by_roles'], true ) ) {
							$selected_attr = 'selected';
						}
						?>
						<option value="<?php echo esc_attr( $role_key ); ?>" <?php echo esc_attr( $selected_attr ); ?>><?php echo esc_attr( $role_name ); ?></option>
					<?php endforeach; ?>
				</select>
			</label>
		</div>
	</div>
</div>

<div class="heatbox admin-bar-settings-box" data-hide-if-field="remove_by_roles" data-hide-if-value='["all"]'>
	<h2>
		<?php _e( 'Auto Hide Admin Bar', 'better-admin-bar' ); ?>
	</h2>
	<div class="setting-fields">
		<div class="field">
			<label for="auto_hide" class="label checkbox-label">
				<?php _e( 'Auto Hide Admin Bar', 'better-admin-bar' ); ?>
				<p class="description"><?php _e( 'This feature will auto-hide the Admin Bar from your website.<br> It will reappear when hovering over the top part of the browser window.', 'better-admin-bar' ); ?></p>
				<input type="checkbox" name="auto_hide" id="auto_hide" value="1" class="general-setting-field" <?php checked( $admin_bar_settings['auto_hide'], 1 ); ?>>
				<div class="indicator"></div>
			</label>
		</div>

		<hr data-show-if-field-checked="auto_hide">

		<div class="field is-horizontal" data-show-if-field-checked="auto_hide">
			<div class="field-label">
				<label for="hiding_transition_delay" class="label">
					<?php _e( 'Delay:', 'better-admin-bar' ); ?>
				</label>
			</div>
			<div class="field-body">
				<div class="field">
					<div class="control">
						<input type="number" name="hiding_transition_delay" id="hiding_transition_delay" class="general-setting-field" placeholder="1500" value="<?php echo esc_attr( $admin_bar_settings['hiding_transition_delay'] ); ?>">
						<code>ms</code>
					</div>
				</div>
			</div>
		</div>
		<div class="field is-horizontal" data-show-if-field-checked="auto_hide">
			<div class="field-label">
				<label for="transition_duration" class="label">
					<?php _e( 'Animation speed:', 'better-admin-bar' ); ?>
				</label>
			</div>
			<div class="field-body">
				<div class="field">
					<div class="control">
						<input type="number" name="transition_duration" id="transition_duration" class="general-setting-field" placeholder="500" value="<?php echo esc_attr( $admin_bar_settings['transition_duration'] ); ?>">
						<code>ms</code>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="heatbox admin-bar-settings-box" data-hide-if-field="remove_by_roles" data-hide-if-value='["all"]'>
	<h2>
		<?php _e( 'Display Settings', 'better-admin-bar' ); ?>
	</h2>
	<div class="setting-fields">
		<div class="field">
			<label for="remove_top_gap" class="label checkbox-label">
				<?php _e( 'Remove Admin Bar Gap', 'better-admin-bar' ); ?>
				<p class="description"><?php _e( 'Removes the top gap (32px) the Admin Bar adds to your website.', 'better-admin-bar' ); ?></p>
				<input type="checkbox" name="remove_top_gap" id="remove_top_gap" value="1" class="general-setting-field" <?php checked( $admin_bar_settings['remove_top_gap'], 1 ); ?>>
				<div class="indicator"></div>
			</label>
		</div>

		<div class="field">
			<label for="fix_menu_item_overflow" class="label checkbox-label">
				<?php _e( 'Fix Menu Item Overflow', 'better-admin-bar' ); ?>
				<p class="description"><?php _e( 'With too many menu items in the admin bar the layout may break on smaller screens.<br> This setting prevents menu items from overflowing and breaking into the next line.', 'better-admin-bar' ); ?></p>
				<input type="checkbox" name="fix_menu_item_overflow" id="fix_menu_item_overflow" value="1" class="general-setting-field" <?php checked( $admin_bar_settings['fix_menu_item_overflow'], 1 ); ?>>
				<div class="indicator"></div>
			</label>
		</div>

		<hr>

		<div class="field is-horizontal">
			<div class="field-label">
				<label for="hide_below_screen_width" class="label">
					<?php _e( 'Hide Admin Bar on screens smaller than:', 'better-admin-bar' ); ?>
				</label>
			</div>
			<div class="field-body">
				<div class="field">
					<div class="control">
						<input type="number" name="hide_below_screen_width" id="hide_below_screen_width" class="general-setting-field" value="<?php echo esc_attr( $admin_bar_settings['hide_below_screen_width'] ); ?>">
						<code>px</code>
					</div>
				</div>
			</div>
		</div>

		<div class="field is-horizontal">
			<div class="field-label">
				<label for="inactive_opacity" class="label">
					<?php _e( 'Admin Bar opacity:', 'better-admin-bar' ); ?>
				</label>
			</div>
			<div class="field-body">
				<div class="field">
					<div class="control">
						<input type="number" name="inactive_opacity" id="inactive_opacity" class="general-setting-field" value="<?php echo esc_attr( $admin_bar_settings['inactive_opacity'] ); ?>">
						<code>%</code>
					</div>
				</div>
			</div>
		</div>

		<div class="field is-horizontal">
			<div class="field-label">
				<label for="active_opacity" class="label">
					<?php _e( 'Admin Bar opacity (on hover):', 'better-admin-bar' ); ?>
				</label>
			</div>
			<div class="field-body">
				<div class="field">
					<div class="control">
						<input type="number" name="active_opacity" id="active_opacity" class="general-setting-field" value="<?php echo esc_attr( $admin_bar_settings['active_opacity'] ); ?>">
						<code>%</code>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
