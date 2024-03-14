<?php
namespace ElementorDevices;

use ElementorDevices\Widgets\Widget_Devices;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Main Plugin Class
 *
 * Register elementor widget.
 *
 * @since 0.0.1
 */
class ElementorDevicesPlugin {

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

		// Editor Scripts
		// add_action( 'elementor/editor/before_enqueue_scripts', [$this, 'enqueue_editor_scripts'] );

		// Front-end Scripts
		add_action( 'elementor/frontend/before_enqueue_scripts', [$this, 'enqueue_frontend_scripts'] );

		// Editor Styles
		add_action( 'elementor/editor/after_enqueue_styles', [$this, 'enqueue_editor_styles'] );

		// Front-end Styles
		add_action( 'elementor/frontend/after_enqueue_styles', [$this, 'enqueue_frontend_styles'] );
	}

	/**
	 * Register scripts for the editor
	 *
	 * @since 0.0.1
	 *
	 * @access public
	 */
	public function register_scripts() {
		
	}

	/**
	 * Enqueue scripts
	 *
	 * @since 0.0.1
	 *
	 * @access public
	 */
	public function enqueue_editor_scripts() {

		// Register scripts
		wp_register_script(
			'devices-elementor-editor',
			plugins_url( '/assets/js/editor.min.js', DEVICES_ELEMENTOR__FILE__ ),
			[],
			DEVICES_ELEMENTOR_VERSION,
			true );

		// Enqueue scripts
		wp_enqueue_script( 'devices-elementor-editor' );

		// GSAP Library
		wp_enqueue_script('gsap-js', '//cdnjs.cloudflare.com/ajax/libs/gsap/1.19.0/TweenMax.min.js', array(), false, true);
	}

	/**
	 * Enqueue scripts for frontend
	 *
	 * @since 0.0.1
	 *
	 * @access public
	 */
	public function enqueue_frontend_scripts() {

		$devices_elementor_frontend_config = [
			'urls' => [
				'assets' => DEVICES_ELEMENTOR_ASSETS_URL,
			],
		];

		// Register scripts
		wp_register_script(
			'devices-elementor-frontend',
			plugins_url( '/assets/js/frontend.min.js', DEVICES_ELEMENTOR__FILE__ ),
			[
				'jquery'
			],
			DEVICES_ELEMENTOR_VERSION,
			true );

		wp_localize_script( 'devices-elementor-frontend', 'elementorDevicesFrontendConfig', $devices_elementor_frontend_config );

		// Enqueue scripts
		// wp_enqueue_script( 'devices-elementor-frontend' );
	}

	/**
	 * Enqueue styles
	 *
	 * @since 0.0.1
	 *
	 * @access public
	 */
	public function enqueue_frontend_styles() {

		// Register styles
		wp_register_style(
			'devices-elementor-frontend',
			plugins_url( '/assets/css/frontend.min.css', DEVICES_ELEMENTOR__FILE__ ),
			[],
			DEVICES_ELEMENTOR_VERSION
		);

		wp_register_style(
			'namogo-icons',
			DEVICES_ELEMENTOR_ASSETS_URL . 'lib/nicons/css/nicons.css',
			[],
			DEVICES_ELEMENTOR_VERSION
		);

		// Enqueue styles
		wp_enqueue_style( 'devices-elementor-frontend' );
		wp_enqueue_style( 'namogo-icons' );
	}

	/**
	 * Enqueue styles
	 *
	 * @since 0.0.1
	 *
	 * @access public
	 */
	public function enqueue_editor_styles() {
		
		// Register styles
		wp_register_style(
			'namogo-icons',
			DEVICES_ELEMENTOR_ASSETS_URL . 'lib/nicons/css/nicons.css',
			[],
			DEVICES_ELEMENTOR_VERSION
		);

		// Enqueue style
		wp_enqueue_style( 'namogo-icons' );
	}

	/**
	 * On Widgets Registered
	 *
	 * @since 0.0.1
	 *
	 * @access public
	 */
	public function on_widgets_registered() {
		$this->includes();
		$this->register_widget();
	}

	/**
	 * Includes
	 *
	 * @since 0.0.1
	 *
	 * @access private
	 */
	private function includes() {
		require __DIR__ . '/widgets/devices.php';
	}

	/**
	 * Register Widget
	 *
	 * @since 0.0.1
	 *
	 * @access private
	 */
	private function register_widget() {
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widget_Devices() );
	}
}

new ElementorDevicesPlugin();
