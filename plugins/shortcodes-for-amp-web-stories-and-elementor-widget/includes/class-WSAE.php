<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Add a custom category for panel widgets
add_action( 'elementor/init', function() {
   \Elementor\Plugin::$instance->elements_manager->add_category( 
   	'wsae',				 // the name of the category
   	[
   		'title' => esc_html__( 'Webstory Addon Widget', 'wsae' ),
   		'icon' => 'fa fa-header', //default icon
   	],
   	1 // position
   );
} );

/**
 * Main Plugin Class
 *
 * Register new elementor widget.
 *
 * @since 1.0.0
 */
class WSAE_WidgetClass {

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function __construct() {
		$this->wsae_add_actions();
	}

	/**
	 * Add Actions
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 */
	private function wsae_add_actions() {
		add_action( 'elementor/widgets/widgets_registered', array($this, 'wsae_on_widgets_registered' ));		
	}
	
	/**
	 * On Widgets Registered
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function wsae_on_widgets_registered() {
		$this->wsae_widget_includes();
	}

	/**
	 * Includes
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 */
	private function wsae_widget_includes() {
		require_once WSAE_PATH . 'widgets/wsae-widget.php';
	}

}

new WSAE_WidgetClass();