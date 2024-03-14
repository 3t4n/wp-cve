<?php

add_action('init', function() {
		// Register Gutenberg blocks
		// wp_register_script('block-say-it-js', plugin_dir_url( __FILE__ ) . '/js/block-say-it.js');
		// register_block_type('davidmanson/sayit', ['editor_script' => 'block-say-it-js']);

		// Register the sayit format
		wp_register_script('sayit-format-js', plugins_url( '/js/sayit-format.js', __FILE__ ),
			array( 'wp-rich-text' )
		);

		// Register sayit css
		wp_register_style("sayit-block-css", plugins_url("/style.css", __FILE__), array() , '1.1.0', 'all');
});

function my_custom_format_enqueue_assets_editor() {
    wp_enqueue_script( 'sayit-format-js' );
	wp_enqueue_style( 'sayit-block-css' );
}
add_action( 'enqueue_block_editor_assets', 'my_custom_format_enqueue_assets_editor' );