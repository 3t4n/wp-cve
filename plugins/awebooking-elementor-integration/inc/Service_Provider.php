<?php

namespace AweBooking\Elementor;

use AweBooking\Support\Service_Provider as AweBooking_Service_Provider;
use Elementor\Plugin as Elementor_Plugin;

class Service_Provider extends AweBooking_Service_Provider {
	/**
	 * Registers services on the plugin.
	 *
	 * @return void
	 */
	public function register() {
		load_plugin_textdomain( 'awebooking-elementor', false, basename( dirname( __DIR__ ) ) . '/languages' );
	}

	/**
	 * Init service provider.
	 *
	 * @return void
	 */
	public function init() {
		// Check if Elementor installed and activated.
		if ( ! did_action( 'elementor/loaded' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_missing_elementor_plugin' ] );

			return;
		}

		add_action( 'elementor/elements/categories_registered', [ $this, 'add_widget_categories' ] );

		add_action( 'elementor/frontend/before_enqueue_scripts', [ $this, 'before_enqueue_scripts' ] );

		// Add Plugin actions.
		add_action( 'elementor/widgets/widgets_registered', [ $this, 'register_widgets' ] );
	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have Elementor installed or activated.
	 *
	 * @access public
	 */
	public function admin_notice_missing_elementor_plugin() {
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

		$message = sprintf(
		/* translators: 1: Plugin name 2: Elementor */
			esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'awebooking-elementor' ),
			'<strong>' . esc_html__( 'AweBooking & Elementor Integration', 'awebooking-elementor' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'awebooking-elementor' ) . '</strong>'
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
	}

	/**
	 * Before enqueue scripts.
	 */
	public function before_enqueue_scripts() {
		$is_preview_mode = Elementor_Plugin::$instance->preview->is_preview_mode();

		if ( $is_preview_mode ) {
			wp_enqueue_script( 'awebooking-elementor-editor-scripts', ABRS_ELEMENTOR_PLUGIN_URL . '/assets/js/elementor.js', [ 'elementor-frontend' ], '1.0.0', true );
		}
	}

	/**
	 * Add widget categories.
	 *
	 * @param object $elements_manager elements manager.
	 */
	public function add_widget_categories( $elements_manager ) {
		$elements_manager->add_category(
			'awebooking',
			[
				'title' => esc_html__( 'AweBooking', 'awebooking-elementor' ),
				'icon'  => 'fa fa-plug',
			]
		);
	}

	/**
	 * Init Widgets
	 *
	 * Include widgets files and register them
	 *
	 * @access public
	 */
	public function register_widgets() {
		$widgets = [
			\AweBooking\Elementor\Widgets\Search_Availability_Form::class,
			\AweBooking\Elementor\Widgets\Services::class,
			\AweBooking\Elementor\Widgets\Rooms::class,
			\AweBooking\Elementor\Widgets\Single_Room::class,
		];

		if ( $widgets ) {
			foreach ( $widgets as $widget ) {
				\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new $widget() );
			}
		}
	}
}
