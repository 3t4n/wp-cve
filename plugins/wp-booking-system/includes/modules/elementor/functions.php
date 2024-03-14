<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Create Elementor Widget Category
 */
function wpbs_elementor_add_widget_category( $elements_manager ) {

	$elements_manager->add_category(
		'wp-booking-system',
		[
			'title' => esc_html__( 'WP Booking System', 'wp-booking-system' ),
			'icon' => 'eicon-calendar',
		]
	);
    
}
add_action( 'elementor/elements/categories_registered', 'wpbs_elementor_add_widget_category' );

/**
 * Register the Widgets
 * 
 */
function wpbs_elementor_register_widget_calendars( $widgets_manager ) {

	require_once( __DIR__ . '/widgets/single-calendar.php' );

	$widgets_manager->register( new \Elementor_WPBS_Single_Calendar_Widget() );

}
add_action( 'elementor/widgets/register', 'wpbs_elementor_register_widget_calendars' );