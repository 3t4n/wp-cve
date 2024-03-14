<?php
/**
 * Plugin Name: Gradient Text Widget for Elementor
 * Description: Easily add gradient texts to your Elementor.
 * Version: 1.0.1
 * Author: Blocksmarket
 * Author URI: https://blocksmarket.net/
 * Plugin URI: https://blocksmarket.net/widgets/gradient-text-widget-for-elementor/
 * Text Domain: gradient-text-widget
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Adds Gradient Text Widget styles to the site.

function bmgradient_widget_style() {

	wp_register_style( 'bmgradient-text-widget', plugins_url( 'assets/css/gradient.css', __FILE__ ) );

	wp_enqueue_style( 'bmgradient-text-widget' );


}

add_action( 'elementor/frontend/before_enqueue_styles', 'bmgradient_widget_style' );
 

// Create the Gradient Text Widget.

function bmgradient_gradient_text_widget( $widgets_manager ) {

    require_once( __DIR__ . '/widgets/gradient-text.php' );

    $widgets_manager->register( new \BMGradient_Text() );


}
add_action( 'elementor/widgets/register', 'bmgradient_gradient_text_widget' );


// Creates the Blocksmarket category for the Elementor Editor.

function bmgradient_add_elementor_widget_categories( $elements_manager ) {

	$elements_manager->add_category(
		'blocks-market',
		[
			'title' => esc_html__( 'Blocksmarket', 'gradient-text-widget' ),
			'icon' => 'fa fa-plug',
		]
	);

}
add_action( 'elementor/elements/categories_registered', 'bmgradient_add_elementor_widget_categories' );

