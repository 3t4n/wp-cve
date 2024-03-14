<?php

class ccew_elementor_register {

	public function __construct() {

		// Add a custom category for panel widgets

		add_action( 'elementor/init', array( $this, 'ccew_add_category' ) );
		add_action( 'elementor/editor/after_enqueue_styles', array( $this, 'ccew_editor_styles' ) );

		// Registered Elementor Widget
		add_action( 'elementor/widgets/register', array( $this, 'ccew_on_widgets_registered' ) );
	}

	public function ccew_add_category() {
		\Elementor\Plugin::$instance->elements_manager->add_category(
			'ccew',              // the name of the category
			array(
				'title' => esc_html__( 'Cryptocurrency Widgets', 'ccew' ),
				'icon'  => 'fa fa-header', // default icon
			),
			1 // position
		);
	}

	public function ccew_editor_styles() {
		wp_enqueue_style(
			'ccew-editor-styles',
			CCEW_URL . 'assets/css/ccew-editor-styles.css',
			array()
		);
	}

	public function ccew_on_widgets_registered() {
		$this->ccew_widget_includes();
	}
	/**
	 * Includes
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 */
	private function ccew_widget_includes() {
		require_once CCEW_DIR . 'includes/ccew-cryptocurrency-widgets.php';
		require_once CCEW_DIR . 'donation-box/ccew-donation-box-widget.php';

	}

}
new ccew_elementor_register();
