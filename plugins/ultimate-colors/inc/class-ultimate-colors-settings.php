<?php
/**
 * Create settings page for colors configuration.
 */

/**
 * Settings page class.
 */
class Ultimate_Colors_Settings {
	/**
	 * Add hooks to create settings page and register settings.
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_menu' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
	}

	/**
	 * Add plugin settings menu.
	 */
	public function add_menu() {
		$page = add_menu_page(
			esc_html__( 'Ultimate Colors', 'ultimate-colors' ),
			esc_html__( 'Ultimate Colors', 'ultimate-colors' ),
			'manage_options',
			'ultimate-colors',
			array( $this, 'render_page' ),
			'dashicons-admin-customizer'
		);
		add_action( "admin_print_styles-$page", array( $this, 'enqueue' ) );
	}

	/**
	 * Render settings page.
	 */
	public function render_page() {
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Ultimate Colors', 'ultimate-colors' ); ?></h1>
			<p><?php esc_html_e( 'Please add and configure elements that you want to change colors for.', 'ultimate-colors' ); ?></p>
			<p><?php echo wp_kses_post( sprintf( __( 'After saving, please <a href="%s">go to the Customizer</a> to change the colors and preview them in real-time.', 'ultimate-colors' ), esc_url( admin_url( 'customize.php' ) ) ) ); ?></p>
			<form method="POST" action="options.php">
				<?php
				settings_fields( 'ultimate-colors' );
				do_settings_sections( 'ultimate-colors' );
				?>
				<p class="submit">
					<?php submit_button( esc_html__( 'Save Changes', 'ultimate-colors' ), 'primary', 'submit', false ); ?>
					<a href="<?php echo esc_url( admin_url( 'customize.php' ) ); ?>" class="button"><?php esc_html_e( 'Customize', 'ultimate-colors' ); ?></a>
				</p>
			</form>
		</div>
		<?php
	}

	/**
	 * Enqueue scripts and styles for the settings page.
	 */
	public function enqueue() {
		wp_enqueue_style( 'ultimate-colors-settings', Ultimate_Colors::instance()->url . 'css/settings.css' );
		wp_enqueue_script( 'ultimate-colors-settings', Ultimate_Colors::instance()->url . 'js/settings.js', array(
			'jquery',
			'wp-util',
			'backbone',
		), '1.0.0', true );
		wp_localize_script( 'ultimate-colors-settings', 'Ultimate_Colors', get_option( 'ultimate-colors' ) );
	}

	/**
	 * Register plugin settings.
	 */
	public function register_settings() {
		register_setting( 'ultimate-colors', 'ultimate-colors', array( $this, 'sanitize' ) );
		add_settings_section(
			'default',
			'',
			'',
			'ultimate-colors'
		);
		add_settings_field(
			'',
			esc_html__( 'Elements', 'ultimate-fonts' ),
			array( $this, 'render_elements' ),
			'ultimate-colors'
		);
	}

	/**
	 * Sanitize options. Save all elements as un no-associate array.
	 *
	 * @param array $options
	 *
	 * @return array
	 */
	public function sanitize( $options ) {
		$options['elements'] = isset( $options['elements'] ) && is_array( $options['elements'] ) ? array_values( $options['elements'] ) : array();

		return $options;
	}

	/**
	 * Render elements field.
	 */
	public function render_elements() {
		?>
		<div id="ultimate-colors-elements">
			<a href="javascript:;" id="ultimate-colors-add" class="button"><?php esc_html_e( '+ Add Element', 'ultimate-colors' ); ?></a>
		</div>
		<script type="text/template" id="tmpl-ultimate-colors-element">
			<label class="ultimate-colors-element__label">
				<span class="ultimate-colors-element__title"><?php esc_html_e( 'Label' ); ?></span>
				<input type="text" name="<?php echo esc_attr( "ultimate-colors[elements][{{ data.index }}][label]" ); ?>" value="{{ data.label }}">
				<small class="description"><?php esc_html_e( 'The element label displayed in the Customizer.', 'ultimate-colors' ); ?></small>
			</label>
			<label class="ultimate-colors-element__selector">
				<span class="ultimate-colors-element__title"><?php esc_html_e( 'Selector' ); ?></span>
				<input type="text" class="regular-text" name="<?php echo esc_attr( "ultimate-colors[elements][{{ data.index }}][selector]" ); ?>" value="{{ data.selector }}">
				<small class="description"><?php esc_html_e( 'Separate multiple selectors with commas.', 'ultimate-colors' ); ?></small>
			</label>
			<label class="ultimate-colors-element__property">
				<span class="ultimate-colors-element__title"><?php esc_html_e( 'Property' ); ?></span>
				<select name="<?php echo esc_attr( "ultimate-colors[elements][{{ data.index }}][property]" ); ?>">
					<option value="background-color" <# print( 'background-color' == data.property ? 'selected' : '' ); #>>
						<?php esc_html_e( 'Background Color' ); ?>
					</option>
					<option value="color" <# print( 'color' == data.property ? 'selected' : '' ); #>>
						<?php esc_html_e( 'Text Color' ); ?>
					</option>
					<option value="border-color" <# print( 'border-color' == data.property ? 'selected' : '' ); #>>
						<?php esc_html_e( 'Border Color' ); ?>
					</option>
				</select>
			</label>
			<a href="javascript:;" class="ultimate-colors-element__delete" title="<?php esc_attr_e( 'Remove element', 'ultimate-colors' ); ?>"><i class="dashicons dashicons-minus"></i></a>
		</script>
		<?php
	}
}
