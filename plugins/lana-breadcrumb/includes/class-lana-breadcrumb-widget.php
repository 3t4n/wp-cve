<?php

/**
 * Class Lana_Breadcrumb_Widget
 */
class Lana_Breadcrumb_Widget extends WP_Widget {

	/**
	 * Lana Breadcrumb Widget
	 * constructor
	 */
	public function __construct() {

		$widget_name        = __( 'Lana - Breadcrumb', 'lana-breadcrumb' );
		$widget_description = __( 'Indicate the current page\'s location within a navigational hierarchy.', 'lana-breadcrumb' );
		$widget_options     = array( 'description' => $widget_description );

		parent::__construct( 'lana_breadcrumb', $widget_name, $widget_options );
	}

	/**
	 * Output Widget HTML
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
		echo lana_breadcrumb_shortcode();
	}
} 