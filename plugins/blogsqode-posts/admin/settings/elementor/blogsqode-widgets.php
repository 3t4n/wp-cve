<?php

/**
 * Used Code :'blogsqode-widgets.php'.
 */


class Blogsqode_Shortcode_Widgets {

	protected static $instance = null;

	public static function get_instance() {
		if ( ! isset( static::$instance ) ) {
			static::$instance = new static;
		}

		return static::$instance;
	}

	protected function __construct() {
		require_once('blogsqode-shortcode-widget.php');		
		require_once('blogsqode-blockquote-widget.php');		
		add_action( 'elementor/widgets/widgets_registered', [ $this, 'register_widgets' ] );

	}


	public function register_widgets() {
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Elementor\Blogsqode_Shortcode_Widget() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Elementor\Blogsqode_Blockquote_Widget() );
	}
}

add_action( 'init', 'elementor_shortcode_widget_callback' );
function elementor_shortcode_widget_callback() {
	Blogsqode_Shortcode_Widgets::get_instance();
}