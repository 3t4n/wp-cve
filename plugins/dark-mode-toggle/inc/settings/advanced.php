<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Advanced settings.
 *
 * @package Dark_Mode_Toggle
 */

defined( 'ABSPATH' ) || die();

$default_advanced = darkmodetg( 'options' )->get_default_main( 'advanced' );

$advanced = darkmodetg( 'options' )->get_main( 'advanced' );
?>

<form x-data="form" x-bind="form">

	<?php wp_nonce_field( 'advanced-settings', 'nonce' ); ?>

	<input name="action" type="hidden" value="darkmodetg-advanced-settings">

	<div class="md:dmt-grid md:dmt-grid-cols-2 xl:dmt-grid-cols-3 md:dmt-gap-x-10 lg:dmt-gap-x-12 dmt-px-1">
		<div class="dmt-max-w-sm">

			<div class="dmt-form-control dmt-mb-3">
				<label for="dmt-time" class="dmt-label"><span class="dmt-label-text"><?php esc_html_e( 'Transition time:', 'dark-mode-toggle' ); ?></span></label>
				<label class="dmt-input-group">
					<input name="time" type="number" min="0" step="0.1" placeholder="<?php esc_attr_e( 'Example: 0.5', 'dark-mode-toggle' ); ?>" id="dmt-time" class="dmt-input dmt-input-bordered dmt-w-full" value="<?php echo esc_attr( $advanced['time'] ); ?>">
					<span><?php esc_html_e( 'seconds', 'dark-mode-toggle' ); ?></span>
				</label>
				<label class="dmt-label">
					<span class="dmt-label-text-alt"><?php echo wp_kses( __( 'Set transition time in seconds.<br>Set it to 0 to disable transition effect.<br>Set it to a positive value like 0.5 to create transition effect.', 'dark-mode-toggle' ), array( 'br' => array() ) ); ?></span>
					<span class="dmt-label-text-alt"><?php /* translators: %s: Transition time */ printf( esc_html_x( 'Default: %s', 'Transition time', 'dark-mode-toggle' ), esc_html( $default_advanced['time'] ) ); ?></span>
				</label>
			</div>

			<div class="dmt-form-control dmt-mb-3">
				<label class="dmt-cursor-pointer dmt-label dmt-pr-1">
					<span class="dmt-label-text dmt-pr-3"><?php esc_html_e( 'Hide Toggle Button on Mobile', 'dark-mode-toggle' ); ?></span>
					<input name="hide_m" type="checkbox" class="dmt-checkbox dmt-checkbox-primary" <?php checked( $advanced['hide_m'], true, true ); ?> value="1">
				</label>
				<label class="dmt-label">
					<span class="dmt-label-text-alt"><?php esc_html_e( 'Whether to hide toggle button on small-screen mobile devices.', 'dark-mode-toggle' ); ?></span>
				</label>
			</div>

			<div class="dmt-form-control dmt-mb-3">
				<label class="dmt-cursor-pointer dmt-label dmt-pr-1">
					<span class="dmt-label-text dmt-pr-3"><?php esc_html_e( 'Save User\'s Choice', 'dark-mode-toggle' ); ?></span>
					<input name="save" type="checkbox" class="dmt-checkbox dmt-checkbox-primary" <?php checked( $advanced['save'], true, true ); ?> value="1">
				</label>
				<label class="dmt-label">
					<span class="dmt-label-text-alt"><?php esc_html_e( 'Whether to save user\'s choice on next page refresh.', 'dark-mode-toggle' ); ?></span>
				</label>
			</div>

		</div>

		<div class="dmt-max-w-sm">

			<div class="dmt-form-control dmt-mb-3" x-data="override( <?php echo esc_attr( $advanced['front']['override'] ? 'true' : 'false' ); ?> )">
				<label class="dmt-cursor-pointer dmt-label dmt-pr-1">
					<span class="dmt-label-text dmt-pr-3"><?php esc_html_e( 'Allow CSS Overrides on Front Pages', 'dark-mode-toggle' ); ?></span>
					<input name="front_override" type="checkbox" class="dmt-checkbox dmt-checkbox-primary" x-model="override">
				</label>
				<div class="dmt-label-text-alt dmt-px-1 dmt-py-2" x-bind="fields">
					<div class="dmt-mb-3"><?php esc_html_e( 'For advanced users only. A CSS class "darkmode--activated" is added to the <body> tag. If you check this option, then you will have to manually override some styles using CSS rules based on your theme.', 'dark-mode-toggle' ); ?></div>
					<?php
					echo wp_kses(
						__( '<code class="dmt-p-0"><strong>For example:</strong><br> .darkmode--activated p, .darkmode--activated li { color: #000; } .darkmode--activated .logo { mix-blend-mode: difference; }</code>', 'dark-mode-toggle' ),
						array(
							'code'   => array( 'class' => array() ),
							'strong' => array(),
							'br'     => array(),
						)
					);
					?>
					<div class="dmt-mt-3"><?php esc_html_e( 'Also, you can apply the class "darkmode-ignore" to any HTML tag where you don\'t want to apply darkmode.', 'dark-mode-toggle' ); ?></div>
				</div>
			</div>

			<div class="dmt-form-control dmt-mb-3">
				<label class="dmt-label"><span class="dmt-label-text"><?php esc_html_e( 'Inverting Effect on Any Element Manually', 'dark-mode-toggle' ); ?></span></label>
				<div class="dmt-label-text-alt dmt-px-1 dmt-py-2">
					<?php
					printf(
						wp_kses(
							/* translators: %s: URL on how to apply additional CSS class to a block */
							__( 'You can add a CSS class: <code class="dmt-p-0">dmt-filter-1</code> or <code class="dmt-p-0">dmt-filter-0</code> to any element like images or blocks. This will invert the effect on those elements. This is useful when you want to skip or avoid dark mode on any elements or to remove inverted effects from certain images or blocks by simply adding an additional CSS.<br><br> You can <a target="_blank" href="%s" class="dmt-underline">read more</a> on how to apply additional CSS class to a block.', 'dark-mode-toggle' ),
							array(
								'code' => array( 'class' => array() ),
								'br'   => array( 'class' => array() ),
								'a'    => array(
									'href'   => array(),
									'target' => array(),
									'class'  => array(),
								),
							)
						),
						'https://scriptstown.com/dark-mode-toggle-switch-plugin-to-add-light-or-night-mode-in-wordpress/#additional-filter-class'
					);
					?>
				</div>
			</div>

			<?php do_action( 'darkmodetg_fields_settings_advanced', $advanced ); ?>

		</div>
	</div>

	<?php do_action( 'darkmodetg_fields_settings_advanced_before_btn', $advanced ); ?>

	<div class="md:dmt-grid md:dmt-grid-cols-2 xl:dmt-grid-cols-3 md:dmt-gap-x-10 lg:dmt-gap-x-12 dmt-px-1">
		<div class="dmt-max-w-sm">

			<button class="dmt-btn dmt-btn-primary dmt-btn-block dmt-mt-3" x-bind="btn"><?php esc_html_e( 'Save Settings', 'dark-mode-toggle' ); ?></button>

			<?php darkmodetg( 'settings' )->feedback_html(); ?>

		</div>
	</div>

</form>
