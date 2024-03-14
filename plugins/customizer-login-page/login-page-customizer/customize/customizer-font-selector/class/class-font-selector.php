<?php
/**
 * Font selector.
 *
 * @package customizer-controls
 */

if ( ! class_exists( 'WP_Customize_Control' ) ) {
	return;
}

/**
 * Class Hestia_Font_Selector
 */
if ( ! class_exists( 'Font_Selector' ) ) {
	class Font_Selector extends WP_Customize_Control {

		/**
		 * The control type.
		 *
		 * @access public
		 * @var string
		 */
		public $type = 'selector-font';

		/**
		 * Enqueue control related scripts/styles.
		 *
		 * @access public
		 */
		public function enqueue() {
			wp_enqueue_script( 'select-script', LOGINPC_PLUGIN_URL . 'customize/customizer-font-selector/js/select.js', array( 'jquery' ), LPC_FONT_SELECTOR_VERSION, true );
			wp_enqueue_style( 'select-style', LOGINPC_PLUGIN_URL . 'customize/customizer-font-selector/css/select.css', null, LPC_FONT_SELECTOR_VERSION );
			wp_enqueue_script( 'typography-js', LOGINPC_PLUGIN_URL . 'customize/customizer-font-selector/js/typography.js', array( 'jquery', 'select-script' ), LPC_FONT_SELECTOR_VERSION, true );
			wp_enqueue_style( 'typography', LOGINPC_PLUGIN_URL . 'customize/customizer-font-selector/css/typography.css', null );
		}

		/**
		 * Render the control's content.
		 * Allows the content to be overriden without having to rewrite the wrapper in $this->render().
		 *
		 * @access protected
		 */
		protected function render_content() {
			$this_val = $this->value(); ?>
		<label>
				<?php if ( ! empty( $this->label ) ) : ?>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<?php endif; ?>
				<?php if ( ! empty( $this->description ) ) : ?>
				<span class="description customize-control-description"><?php echo wp_kses_post( $this->description ); ?></span>
			<?php endif; ?>

			<select class="typography-select" <?php $this->link(); ?>>
				<option value="<?php echo esc_attr( $this->settings['default']->default ); ?>" <?php if ( ! $this_val ) { echo 'selected="selected"';} ?>><?php esc_html_e( 'Default', 'customizer-login-page' ); ?></option>
				<?php
				// Get Standard font options
				$std_fonts = font_selector_get_standard_fonts();
				if ( ! empty( $std_fonts ) ) {
					?>
					<optgroup label="<?php esc_html_e( 'Standard Fonts', 'customizer-login-page' ); ?>">
						<?php
						// Loop through font options and add to select
						foreach ( $std_fonts as $font ) {
							?>
							<option value="<?php echo esc_html( $font ); ?>" <?php selected( $font, $this_val ); ?>><?php echo esc_html( $font ); ?></option>
						<?php } ?>
					</optgroup>
					<?php
				}

				// Google font options
				$google_fonts = font_selector_get_google_fonts_array();
				if ( ! empty( $google_fonts ) ) {
					?>
					<optgroup label="<?php esc_html_e( 'Google Fonts', 'customizer-login-page' ); ?>">
						<?php
						// Loop through font options and add to select
						foreach ( $google_fonts as $font ) {
							?>
							<option value="<?php echo esc_html( $font ); ?>" <?php selected( $font, $this_val ); ?>><?php echo esc_html( $font ); ?></option>
						<?php } ?>
					</optgroup>
				<?php } ?>
			</select>

		</label>

			<?php
		}
	}
}
