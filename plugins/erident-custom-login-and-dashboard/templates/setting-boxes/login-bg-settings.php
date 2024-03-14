<?php
/**
 * Login background settings template.
 *
 * @package Custom_Login_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Display login background settings template.
 *
 * @param array $settings The plugin settings.
 */
return function ( $settings ) {

	$bg_color       = isset( $settings['top_bg_color'] ) && ! empty( $settings['top_bg_color'] ) ? $settings['top_bg_color'] : '';
	$bg_image_url   = isset( $settings['top_bg_image'] ) && ! empty( $settings['top_bg_image'] ) ? $settings['top_bg_image'] : '';
	$bg_repeat      = isset( $settings['top_bg_repeat'] ) && ! empty( $settings['top_bg_repeat'] ) ? $settings['top_bg_repeat'] : '';
	$horizontal_pos = isset( $settings['top_bg_xpos'] ) && ! empty( $settings['top_bg_xpos'] ) ? $settings['top_bg_xpos'] : '';
	$vertical_pos   = isset( $settings['top_bg_ypos'] ) && ! empty( $settings['top_bg_ypos'] ) ? $settings['top_bg_ypos'] : '';
	$bg_size        = isset( $settings['top_bg_size'] ) && ! empty( $settings['top_bg_size'] ) ? $settings['top_bg_size'] : '';
	?>

	<div class="heatbox login-bg-settings-box">
		<h2>
			<?php _e( 'Background Settings', 'erident-custom-login-and-dashboard' ); ?>
		</h2>
		<div class="setting-fields">

			<div class="field is-horizontal">
				<div class="field-label">
					<label for="top_bg_color" class="label">
						<?php _e( 'Background Color', 'erident-custom-login-and-dashboard' ); ?>
					</label>
				</div>
				<div class="field-body">
					<div class="field">
						<div class="control">
							<input type="text" name="top_bg_color" id="top_bg_color" value="<?php echo esc_attr( $bg_color ); ?>" class="color-picker-field general-setting-field" data-alpha="true" data-default-color="<?php echo esc_attr( $bg_color ); ?>">
						</div>
					</div>
				</div>
			</div>

			<hr>

			<div class="field is-horizontal">
				<div class="field-label">
					<label for="top_bg_image" class="label">
						<?php _e( 'Background Image URL', 'erident-custom-login-and-dashboard' ); ?>
					</label>
				</div>
				<div class="field-body">
					<div class="field">
						<div class="control">
							<input type="text" id="top_bg_image" name="top_bg_image" value="<?php echo esc_url( $bg_image_url ); ?>" class="general-setting-field is-small cldashboard-bg-image-field">
							<button type="button" class="button-secondary cldashboard-upload-button">
								<?php _e( 'Add Background Image', 'erident-custom-login-and-dashboard' ); ?>
							</button>
							<button type="button" class="button-secondary cldashboard-clear-button">x</button>
						</div>
					</div>
				</div>
			</div>

			<div class="field is-horizontal">
				<div class="field-label">
					<label for="top_bg_repeat" class="label">
						<?php _e( 'Background Repeat', 'erident-custom-login-and-dashboard' ); ?>
					</label>
				</div>
				<div class="field-body">
					<div class="field">
						<div class="control">
							<?php
							$bg_repeat_opts = [
								'no-repeat',
								'repeat',
								'repeat-x',
								'repeat-y',
							];
							?>
							<select name="top_bg_repeat" id="top_bg_repeat" class="general-setting-field is-tiny">
								<?php foreach ( $bg_repeat_opts as $bg_repeat_opt ) : ?>
									<option value="<?php echo esc_attr( $bg_repeat_opt ); ?>" <?php selected( $bg_repeat_opt, $bg_repeat ); ?>>
										<?php echo esc_attr( $bg_repeat_opt ); ?>
									</option>
								<?php endforeach; ?>
							</select>
						</div>
					</div>
				</div>
			</div>

			<div class="field is-horizontal" data-hide-if-field="top_bg_repeat" data-hide-if-value="repeat">
				<div class="field-label">
					<label for="top_bg_xpos" class="label">
						<?php _e( 'Background Horizontal Position', 'erident-custom-login-and-dashboard' ); ?>
						<p class="description">
							<?php _e( 'Possible values: <code>left</code>, <code>center</code>, <code>right</code> or numeric value. If a numeric value is provided, a unit (<code>px</code>, <code>%</code>, etc.) must be defined.', 'erident-custom-login-and-dashboard' ); ?>
						</p>
					</label>
				</div>
				<div class="field-body">
					<div class="field">
						<div class="control">
							<input type="text" name="top_bg_xpos" id="top_bg_xpos" value="<?php echo esc_attr( $horizontal_pos ); ?>" class="general-setting-field is-tiny">
						</div>
					</div>
				</div>
			</div>

			<div class="field is-horizontal" data-hide-if-field="top_bg_repeat" data-hide-if-value="repeat">
				<div class="field-label">
					<label for="top_bg_ypos" class="label">
						<?php _e( 'Background Vertical Position', 'erident-custom-login-and-dashboard' ); ?>
						<p class="description">
							<?php _e( 'Possible values: <code>left</code>, <code>center</code>, <code>right</code> or numeric value. If a numeric value is provided, a unit (<code>px</code>, <code>%</code>, etc.) must be defined.', 'erident-custom-login-and-dashboard' ); ?>
						</p>
					</label>
				</div>
				<div class="field-body">
					<div class="field">
						<div class="control">
							<input type="text" name="top_bg_ypos" id="top_bg_ypos" value="<?php echo esc_attr( $vertical_pos ); ?>" class="general-setting-field is-tiny">
						</div>
					</div>
				</div>
			</div>

			<div class="field is-horizontal">
				<div class="field-label">
					<label for="top_bg_size" class="label">
						<?php _e( 'Background Size', 'erident-custom-login-and-dashboard' ); ?>
						<p class="description">
							<?php _e( 'Possible values: <code>auto</code>, <code>cover</code>, <code>contain</code> or numeric value. If a numeric value is provided, a unit (<code>px</code>, <code>%</code>, etc.) must be defined.', 'erident-custom-login-and-dashboard' ); ?>
						</p>
					</label>
				</div>
				<div class="field-body">
					<div class="field">
						<div class="control">
							<input type="text" name="top_bg_size" id="top_bg_size" value="<?php echo esc_attr( $bg_size ); ?>" class="general-setting-field is-tiny">
						</div>
					</div>
				</div>
			</div>

		</div>
	</div>

	<?php
};
