<?php

// Register Widget
if ( ! function_exists( 'wp_post_thumb_list_register_widget' ) ) {

	/**
     * Load widget.
     *
     * @since 1.0.0
     */
    function wp_post_thumb_list_register_widget() {
    	// Register Widget
    	register_widget( 'wp_post_thumb_list_widget' );
    }
}

add_action( 'widgets_init', 'wp_post_thumb_list_register_widget' );


//	Print Shortcodes in widgets
add_filter('widget_text', 'do_shortcode');