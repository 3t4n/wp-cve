<?php
namespace ElementorInlineSvg;

use ElementorInlineSvg\Widgets\Widget_Inline_Svg;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Main Plugin Class
 *
 * Register elementor widget.
 *
 * @since 0.0.1
 */
class Plugin {

	/**
	 * Constructor
	 *
	 * @since 0.0.1
	 *
	 * @access public
	 */
	public function __construct() {

		// Elementor hooks
		$this->add_actions();

		// Allow SVG upload
		add_filter( 'upload_mimes', array( $this, 'wp_allow_svg' ) );
	}

	/**
	 * Add Actions
	 *
	 * @since 0.0.1
	 *
	 * @access private
	 */
	private function add_actions() {

		add_action( 'elementor/widgets/widgets_registered', [ $this, 'on_widgets_registered' ] );

		// ——— SCRIPTS ——— //

			// Editor Scripts
			// add_action( 'elementor/editor/before_enqueue_scripts', [ $this, 'enqueue_editor_scripts' ] );

			// Front-end Scripts
			add_action( 'elementor/frontend/after_enqueue_scripts', [ $this, 'enqueue_frontend_scripts' ] );
			add_action( 'elementor/frontend/after_register_scripts', [ $this, 'register_scripts' ] );

			// Preview
			// add_action( 'elementor/preview/enqueue_styles', [ $this, 'enqueue_scripts' ] );
			// add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] ); 

		// ——— STYLES ——— //

			// add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_styles' ] );

			// Editor Styles
			// add_action( 'elementor/editor/after_enqueue_styles', [ $this, 'enqueue_editor_styles' ] );

			// Front-end Styles
			add_action( 'elementor/frontend/after_enqueue_styles', [ $this, 'enqueue_frontend_styles' ] );

	}

	/**
	 * Register scripts
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function register_scripts() {

		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		// Register scripts
		wp_register_script(
			'elementor-inline-svg-frontend',
			plugins_url( '/assets/js/frontend' . $suffix . '.js', ELEMENTOR_SVG__FILE__ ),
			[],
			ELEMENTOR_SVG_VERSION,
			true );
	}

	/**
	 * Enqueue frontend scripts
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function enqueue_frontend_scripts() {

		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		// Enqueue scripts
		wp_enqueue_script( 'elementor-inline-svg-frontend' );
	}

	/**
	 * Enqueue styles
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function enqueue_frontend_styles() {

		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		// Register styles
		wp_register_style(
			'elementor-inline-svg-frontend',
			plugins_url( '/assets/css/frontend' . $suffix . '.css', ELEMENTOR_SVG__FILE__ ),
			[],
			ELEMENTOR_SVG_VERSION
		);

		// Enqueue styles
		wp_enqueue_style( 'elementor-inline-svg-frontend' );
	}

	/**
	 * Includes widgets
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 */
	private function include_widgets() {
		// Widgets
		require __DIR__ . '/widgets/inline-svg.php';
	}

	/**
	 * Register Widgets
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 */
	private function register_widgets() {

		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widget_Inline_Svg() );
	}

	/**
	 * On Widgets Registered
	 *
	 * @since 0.0.1
	 *
	 * @access public
	 */
	public function on_widgets_registered() {
		$this->include_widgets();
		$this->register_widgets();
	}

	/**
	 * Allow files with .svg extension to be uploaded via
	 * the wordpress media uploader
	 *
	 * @since 0.0.1
	 * @param array $mime_types Existing allowed mime types
	 * @return array
	 * @access public
	 */
	public function wp_allow_svg( $mime_types ) {

		if ( ! in_array( 'svg', $mime_types ) ) { // Check if it hasn't been enabled already
			$mime_types['svg'] = 'image/svg+xml';
		}

		return $mime_types;
	}
}

new Plugin();
