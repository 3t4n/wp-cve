<?php
/**
 * Customize Font Control Class
 */
class FontSq_Customize_Font_Control extends WP_Customize_Control {
	/**
	 * @access public
	 * @var string
	 */
	public $type = 'font';

	/**
	 * Enqueue scripts/styles for the color picker.
	 *
	 * @since 3.4.0
	 */
	public function enqueue() {
		wp_enqueue_script( 'jquery-ui-selectmenu', plugins_url('/js/libs/jquery-ui-selectmenu.js', __FILE__), array('jquery'), '1.11.1', true );
		wp_enqueue_script( 'fontsq-font-control', plugins_url('/js/font-customize-control.js', __FILE__), array( 'jquery-ui-selectmenu' ), false, true );
		wp_enqueue_style( 'fontsq-font-control', plugins_url('/font-customize-control.css', __FILE__) );
	}

	/**
	 * Render the control's content.
	 *
	 * @since 3.4.0
	 */
	public function render_content() {
		require_once sprintf( '%s/../model/class.font.php', dirname( __FILE__ ) );
		$this_default = $this->setting->default;
		?>
		<label>
			<?php if ( ! empty( $this->label ) ) : ?>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<?php endif;
			if ( ! empty( $this->description ) ) : ?>
				<span class="description customize-control-description"><?php echo $this->description; ?></span>
			<?php endif; ?>

			<div class="customize-control-content font-customize-control">
				<select <?php $this->link(); ?>>
					<?php foreach ( FontSq_Font::get_installed_fonts() as $font ) {
						echo '<option value="' . esc_attr( $font->name ) . '"' . selected( $this->value(), $font->name, false ) . '>' . $font->family . '</option>';
					} ?>
				</select>
			</div>
		</label>
		<?php
	}
}

