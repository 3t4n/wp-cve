<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Create Elementor Widget Category
 */
function wpsbc_elementor_add_widget_category( $elements_manager ) {

	$elements_manager->add_category(
		'wp-simple-booking-calendar',
		[
			'title' => esc_html__( 'WP Simple Booking Calendar', 'wp-simple-booking-calendar' ),
			'icon' => 'eicon-calendar',
		]
	);
    
}
add_action( 'elementor/elements/categories_registered', 'wpsbc_elementor_add_widget_category' );

/**
 * Register the Widgets
 * 
 */
function wpsbc_elementor_register_widget_calendars( $widgets_manager ) {

	require_once( __DIR__ . '/widgets/single-calendar.php' );

	$widgets_manager->register( new \Elementor_WPSBC_Single_Calendar_Widget() );

}
add_action( 'elementor/widgets/register', 'wpsbc_elementor_register_widget_calendars' );