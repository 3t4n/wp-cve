<?php
if ( ! class_exists( 'WP_Customize_Control' ) ) {
	return;
}

class PL_Customizer_Control_Separator_Section extends WP_Customize_Control {

	public $type          = 'separator';
	public $separator_txt = 'Next';

	/**
	 * Enqueue styles
	 */
	public function enqueue() {
	/**
	 * @todo
	 */
		wp_enqueue_style( 'pluglab-separator', PL_PLUGIN_INC_URL . 'customizer/css/custom.css', array(), false, 'all' );
	}

	// Render the control's content.
	public function render_content() {
		?>

		<h4 class="divider line sharp"><?php echo $this->separator_txt; ?></h4>

		<?php
	}

}
