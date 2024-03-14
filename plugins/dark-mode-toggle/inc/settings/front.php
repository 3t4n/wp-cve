<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Front settings.
 *
 * @package Dark_Mode_Toggle
 */

defined( 'ABSPATH' ) || die();

$default_front = darkmodetg( 'options' )->get_default_main( 'front' );

$front = darkmodetg( 'options' )->get_main( 'front' );
?>

<form x-data="form" x-bind="form">

	<?php wp_nonce_field( 'front-settings', 'nonce' ); ?>

	<input name="action" type="hidden" value="darkmodetg-front-settings">

	<div class="md:dmt-grid md:dmt-grid-cols-2 xl:dmt-grid-cols-3 md:dmt-gap-x-10 lg:dmt-gap-x-12 dmt-px-1" x-data="enable( <?php echo esc_attr( $front['enable'] ? 'true' : 'false' ); ?> )">
		<div class="dmt-max-w-sm">

			<div class="dmt-form-control dmt-mb-3">
				<label class="dmt-cursor-pointer dmt-label dmt-pr-1">
					<span class="dmt-label-text dmt-pr-3"><?php esc_html_e( 'Enable Toggle Button on Front', 'dark-mode-toggle' ); ?></span>
					<input name="enable" type="checkbox" class="dmt-checkbox dmt-checkbox-primary" x-model="enable">
				</label>
			</div>

			<div x-bind="fields">

				<div x-data="position( '<?php echo esc_attr( $front['pos'] ); ?>' )">
					<div class="dmt-mb-3">
						<label class="dmt-label"><span class="dmt-label-text"><?php esc_html_e( 'Position for Toggle Button:', 'dark-mode-toggle' ); ?></span></label>

						<ul class="dmt-list-square dmt-pl-6 dmt-pr-1">
							<li>
								<div class="dmt-form-control">
									<label class="dmt-cursor-pointer dmt-label">
										<span class="dmt-label-text dmt-pr-3"><?php esc_html_e( 'Bottom - Left', 'dark-mode-toggle' ); ?></span>
										<input name="pos" type="radio" class="dmt-radio dmt-radio-primary" value="bl" x-model="pos">
									</label>
								</div>
							</li>

							<li>
								<div class="dmt-form-control">
									<label class="dmt-cursor-pointer dmt-label">
										<span class="dmt-label-text dmt-pr-3"><?php esc_html_e( 'Bottom - Right', 'dark-mode-toggle' ); ?></span>
										<input name="pos" type="radio" class="dmt-radio dmt-radio-primary" value="br" x-model="pos">
									</label>
								</div>
							</li>

							<li>
								<div class="dmt-form-control">
									<label class="dmt-cursor-pointer dmt-label">
										<span class="dmt-label-text dmt-pr-3"><?php esc_html_e( 'Top - Left', 'dark-mode-toggle' ); ?></span>
										<input name="pos" type="radio" class="dmt-radio dmt-radio-primary" value="tl" x-model="pos">
									</label>
								</div>
							</li>

							<li>
								<div class="dmt-form-control">
									<label class="dmt-cursor-pointer dmt-label">
										<span class="dmt-label-text dmt-pr-3"><?php esc_html_e( 'Top - Right', 'dark-mode-toggle' ); ?></span>
										<input name="pos" type="radio" class="dmt-radio dmt-radio-primary" value="tr" x-model="pos">
									</label>
								</div>
							</li>
						</ul>

						<label class="dmt-label">
							<span class="dmt-label-text-alt"><?php esc_html_e( 'Default: Bottom - Left', 'dark-mode-toggle' ); ?></span>
							<span class="dmt-label-text-alt"><?php esc_html_e( 'Select position for toggle button.', 'dark-mode-toggle' ); ?></span>
						</label>
					</div>

					<div class="dmt-form-control dmt-mb-3" x-bind="posInput( 'b' )">
						<label for="dmt-pos_b" class="dmt-label"><span class="dmt-label-text"><?php esc_html_e( 'Position from Bottom:', 'dark-mode-toggle' ); ?></span></label>
						<label class="dmt-input-group">
							<input name="pos_b" type="number" step="1" placeholder="<?php esc_attr_e( 'Example: 32', 'dark-mode-toggle' ); ?>" id="dmt-pos_b" class="dmt-input dmt-input-bordered dmt-w-full" value="<?php echo esc_attr( $front['pos_b'] ); ?>">
							<span><?php esc_html_e( 'pixels', 'dark-mode-toggle' ); ?></span>
						</label>
						<label class="dmt-label">
							<span class="dmt-label-text-alt"><?php esc_html_e( 'Set position from bottom in pixels.', 'dark-mode-toggle' ); ?></span>
							<span class="dmt-label-text-alt"><?php /* translators: %s: Position from Bottom */ printf( esc_html_x( 'Default: %s', 'Position from Bottom', 'dark-mode-toggle' ), esc_html( $default_front['pos_b'] ) ); ?></span>
						</label>
					</div>

					<div class="dmt-form-control dmt-mb-3" x-bind="posInput( 't' )">
						<label for="dmt-pos_t" class="dmt-label"><span class="dmt-label-text"><?php esc_html_e( 'Position from Top:', 'dark-mode-toggle' ); ?></span></label>
						<label class="dmt-input-group">
							<input name="pos_t" type="number" step="1" placeholder="<?php esc_attr_e( 'Example: 32', 'dark-mode-toggle' ); ?>" id="dmt-pos_t" class="dmt-input dmt-input-bordered dmt-w-full" value="<?php echo esc_attr( $front['pos_t'] ); ?>">
							<span><?php esc_html_e( 'pixels', 'dark-mode-toggle' ); ?></span>
						</label>
						<label class="dmt-label">
							<span class="dmt-label-text-alt"><?php esc_html_e( 'Set position from top in pixels.', 'dark-mode-toggle' ); ?></span>
							<span class="dmt-label-text-alt"><?php /* translators: %s: Position from Top */ printf( esc_html_x( 'Default: %s', 'Position from Top', 'dark-mode-toggle' ), esc_html( $default_front['pos_t'] ) ); ?></span>
						</label>
					</div>

					<div class="dmt-form-control dmt-mb-3" x-bind="posInput( 'l' )">
						<label for="dmt-pos_l" class="dmt-label"><span class="dmt-label-text"><?php esc_html_e( 'Position from Left:', 'dark-mode-toggle' ); ?></span></label>
						<label class="dmt-input-group">
							<input name="pos_l" type="number" step="1" placeholder="<?php esc_attr_e( 'Example: 32', 'dark-mode-toggle' ); ?>" id="dmt-pos_l" class="dmt-input dmt-input-bordered dmt-w-full" value="<?php echo esc_attr( $front['pos_l'] ); ?>">
							<span><?php esc_html_e( 'pixels', 'dark-mode-toggle' ); ?></span>
						</label>
						<label class="dmt-label">
							<span class="dmt-label-text-alt"><?php esc_html_e( 'Set position from left in pixels.', 'dark-mode-toggle' ); ?></span>
							<span class="dmt-label-text-alt"><?php /* translators: %s: Position from Left */ printf( esc_html_x( 'Default: %s', 'Position from Left', 'dark-mode-toggle' ), esc_html( $default_front['pos_l'] ) ); ?></span>
						</label>
					</div>

					<div class="dmt-form-control dmt-mb-3" x-bind="posInput( 'r' )">
						<label for="dmt-pos_r" class="dmt-label"><span class="dmt-label-text"><?php esc_html_e( 'Position from Right:', 'dark-mode-toggle' ); ?></span></label>
						<label class="dmt-input-group">
							<input name="pos_r" type="number" step="1" placeholder="<?php esc_attr_e( 'Example: 32', 'dark-mode-toggle' ); ?>" id="dmt-pos_r" class="dmt-input dmt-input-bordered dmt-w-full" value="<?php echo esc_attr( $front['pos_r'] ); ?>">
							<span><?php esc_html_e( 'pixels', 'dark-mode-toggle' ); ?></span>
						</label>
						<label class="dmt-label">
							<span class="dmt-label-text-alt"><?php esc_html_e( 'Set position from right in pixels.', 'dark-mode-toggle' ); ?></span>
							<span class="dmt-label-text-alt"><?php /* translators: %s: Position from Right */ printf( esc_html_x( 'Default: %s', 'Position from Right', 'dark-mode-toggle' ), esc_html( $default_front['pos_r'] ) ); ?></span>
						</label>
					</div>
				</div>

			</div>

		</div>

		<div class="dmt-max-w-sm">

			<div x-bind="fields">

				<div class="dmt-form-control dmt-mb-3">
					<label class="dmt-cursor-pointer dmt-label dmt-pr-1">
						<span class="dmt-label-text dmt-pr-3"><?php esc_html_e( 'Enqueue Scripts in the Head', 'dark-mode-toggle' ); ?></span>
						<input name="in_head" type="checkbox" class="dmt-checkbox dmt-checkbox-primary" <?php checked( $front['in_head'], true, true ); ?> value="1">
					</label>
					<label class="dmt-label">
						<span class="dmt-label-text-alt"><?php esc_html_e( 'This is performance related option. Whether to enqueue scripts in the <head> tag instead of before </body> tag.', 'dark-mode-toggle' ); ?></span>
					</label>
				</div>

				<div class="dmt-form-control dmt-mb-3">
					<label for="dmt-width" class="dmt-label"><span class="dmt-label-text"><?php esc_html_e( 'Width for Toggle Button:', 'dark-mode-toggle' ); ?></span></label>
					<label class="dmt-input-group">
						<input name="width" type="number" min="0" step="1" placeholder="<?php esc_attr_e( 'Example: 48', 'dark-mode-toggle' ); ?>" id="dmt-width" class="dmt-input dmt-input-bordered dmt-w-full" value="<?php echo esc_attr( $front['width'] ); ?>">
						<span><?php esc_html_e( 'pixels', 'dark-mode-toggle' ); ?></span>
					</label>
					<label class="dmt-label">
						<span class="dmt-label-text-alt"><?php esc_html_e( 'Set width for toggle button in pixels.', 'dark-mode-toggle' ); ?></span>
						<span class="dmt-label-text-alt"><?php /* translators: %s: Width for Toggle Button */ printf( esc_html_x( 'Default: %s', 'Width for Toggle Button', 'dark-mode-toggle' ), esc_html( $default_front['width'] ) ); ?></span>
					</label>
				</div>

				<div class="dmt-form-control dmt-mb-3">
					<label for="dmt-height" class="dmt-label"><span class="dmt-label-text"><?php esc_html_e( 'Height for Toggle Button:', 'dark-mode-toggle' ); ?></span></label>
					<label class="dmt-input-group">
						<input name="height" type="number" min="0" step="1" placeholder="<?php esc_attr_e( 'Example: 48', 'dark-mode-toggle' ); ?>" id="dmt-height" class="dmt-input dmt-input-bordered dmt-w-full" value="<?php echo esc_attr( $front['height'] ); ?>">
						<span><?php esc_html_e( 'pixels', 'dark-mode-toggle' ); ?></span>
					</label>
					<label class="dmt-label">
						<span class="dmt-label-text-alt"><?php esc_html_e( 'Set height for toggle button in pixels.', 'dark-mode-toggle' ); ?></span>
						<span class="dmt-label-text-alt"><?php /* translators: %s: Height for Toggle Button */ printf( esc_html_x( 'Default: %s', 'Height for Toggle Button', 'dark-mode-toggle' ), esc_html( $default_front['height'] ) ); ?></span>
					</label>
				</div>

				<div class="dmt-form-control dmt-mb-3">
					<label for="dmt-border_r" class="dmt-label"><span class="dmt-label-text"><?php esc_html_e( 'Border Radius for Toggle Button:', 'dark-mode-toggle' ); ?></span></label>
					<label class="dmt-input-group">
						<input name="border_r" type="number" min="0" step="1" placeholder="<?php esc_attr_e( 'Example: 48', 'dark-mode-toggle' ); ?>" id="dmt-border_r" class="dmt-input dmt-input-bordered dmt-w-full" value="<?php echo esc_attr( $front['border_r'] ); ?>">
						<span><?php esc_html_e( 'pixels', 'dark-mode-toggle' ); ?></span>
					</label>
					<label class="dmt-label">
						<span class="dmt-label-text-alt"><?php esc_html_e( 'Set border radius for toggle button in pixels.', 'dark-mode-toggle' ); ?></span>
						<span class="dmt-label-text-alt"><?php /* translators: %s: Border Radius for Toggle Button */ printf( esc_html_x( 'Default: %s', 'Border Radius for Toggle Button', 'dark-mode-toggle' ), esc_html( $default_front['border_r'] ) ); ?></span>
					</label>
				</div>

			</div>

		</div>

		<div class="dmt-max-w-sm">

			<div x-bind="fields">

				<div class="dmt-form-control dmt-mb-3">
					<label class="dmt-cursor-pointer dmt-label dmt-pr-1">
						<span class="dmt-label-text dmt-pr-3"><?php esc_html_e( 'Skip Body Background Image', 'dark-mode-toggle' ); ?></span>
						<input name="skip_bg_img" type="checkbox" class="dmt-checkbox dmt-checkbox-primary" <?php checked( $front['skip_bg_img'], true, true ); ?> value="1">
					</label>
					<label class="dmt-label">
						<span class="dmt-label-text-alt"><?php esc_html_e( 'If there is background image on the body tag, then you can check this option to not apply dark mode on the body background image.', 'dark-mode-toggle' ); ?></span>
					</label>
				</div>

				<div x-data="fixFlick( <?php echo esc_attr( $front['fix_flick'] ? 'true' : 'false' ); ?> )">
					<div class="dmt-form-control dmt-mb-3">
						<label class="dmt-cursor-pointer dmt-label dmt-pr-1">
							<span class="dmt-label-text dmt-pr-3"><?php esc_html_e( 'Attempt to Reduce Flickering Effect', 'dark-mode-toggle' ); ?></span>
							<input name="fix_flick" type="checkbox" class="dmt-checkbox dmt-checkbox-primary" <?php checked( $front['fix_flick'], true, true ); ?> value="1" x-model="fixFlick">
						</label>
						<label class="dmt-label">
							<span class="dmt-label-text-alt"><?php esc_html_e( 'You can check this option to reduce flickering on initial page load when the user\'s choice is saved. Also, you can uncheck this option in case you face any unexpected outcome.', 'dark-mode-toggle' ); ?></span>
						</label>
					</div>

					<div class="dmt-form-control dmt-mb-3" x-bind="fields">
						<label for="dmt-flick_bg_color" class="dmt-label"><span class="dmt-label-text"><?php esc_html_e( 'Background Color to Reduce Flickering Effect:', 'dark-mode-toggle' ); ?></span></label>
						<input name="flick_bg_color" type="text" id="dmt-flick_bg_color" class="dmt-color-picker" value="<?php echo esc_attr( $front['flick_bg_color'] ); ?>" data-default-color="<?php echo esc_attr( $default_front['flick_bg_color'] ); ?>">
						<label class="dmt-label dmt-select-text">
							<span class="dmt-label-text-alt dmt-cursor-text"><?php esc_html_e( 'Set temporary background color to reduce flickering effect. It is recommended to set it to #000000 if your theme is light by default and #ffffff if your theme is dark by default.', 'dark-mode-toggle' ); ?></span>
						</label>
					</div>
				</div>

			</div>

			<?php do_action( 'darkmodetg_fields_settings_front', $front ); ?>

		</div>
	</div>

	<?php do_action( 'darkmodetg_fields_settings_front_before_btn', $front ); ?>

	<div class="md:dmt-grid md:dmt-grid-cols-2 xl:dmt-grid-cols-3 md:dmt-gap-x-10 lg:dmt-gap-x-12 dmt-px-1">
		<div class="dmt-max-w-sm">

			<button class="dmt-btn dmt-btn-primary dmt-btn-block dmt-mt-3" x-bind="btn"><?php esc_html_e( 'Save Settings', 'dark-mode-toggle' ); ?></button>

			<?php darkmodetg( 'settings' )->feedback_html(); ?>

		</div>
	</div>

</form>
