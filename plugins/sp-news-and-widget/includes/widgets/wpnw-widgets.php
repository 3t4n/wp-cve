<?php
/**
 * Widget Functionality
 *
 * @package WP News and Scrolling Widgets
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Widget Class
require_once( WPNW_DIR . '/includes/widgets/class-wpnw-latest-news-widget.php' );
require_once( WPNW_DIR . '/includes/widgets/class-wpnw-news-scrolling-widget.php' );
require_once( WPNW_DIR . '/includes/widgets/class-wpnw-news-thumbnail-widget.php' );

/**
 * Register Plugin Widgets
 */
function wpnw_pro_register_widgets() {
	register_widget( 'SP_News_Widget' );
	register_widget( 'SP_News_scrolling_Widget' );
	register_widget( 'SP_News_Thumb_Widget' );
}
add_action( 'widgets_init', 'wpnw_pro_register_widgets' );