<?php
/**
 * Plugin Name: WP Widgets Shortcode
 * Author: Brajesh Singh
 * Plugin URI: http://buddydev.com/plugins/wp-widgets-shortcode/
 * Author URI: http://buddydev.com/members/sbrajesh/
 * Version: 1.0.3
 * License: GPL
 * Description: Embed any widget area(dynamic sidebar) to your WordPress pages/posts using the shortcode [widget-area id='The Name of Widget Area']
 */
//BD for BuddyDev
class BD_Widgets_Shortcode_Helper {


	private static $instance;

	private function __construct() {

		//register shortcodes
		$this->register_shortcodes();

	}

	/**
	 * Register  shortcodes
	 *
	 */
	private function register_shortcodes() {


		//use [widget-area id='something'] or [dynamic-sidebar id=something]
		add_shortcode( 'widget-area', array(
			$this,
			'generate_widget_area'
		) );//use [widget-area id='somewidgetarea' ][/widget-area]
		add_shortcode( 'dynamic-sidebar', array(
			$this,
			'generate_widget_area'
		) );//use [widget-area id='somewidgetarea' ][/widget-area]


	}

	/**
	 * Get Instance
	 *
	 * Use singleton pattern
	 * @return BD_Widgets_Shortcode_Helper
	 */
	public static function get_instance() {

		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}


	public function generate_widget_area( $atts, $content = '' ) {

		extract( shortcode_atts( array(
			'id'        => '',
			'before'    => '',
			'after'     => ''

		), $atts ) );


		$id = trim( $id );


		ob_start();   //start buffer
		echo $before;
		dynamic_sidebar( $id );
		echo $after;

		$content = ob_get_clean();//get it all and clean buffer

		return $content;
	}

}

BD_Widgets_Shortcode_Helper::get_instance();
