<?php
/**
 * Shortcode File for Blog Designer Block
 *
 * @version 1.0
 * @package Blog Designer
 */

/**
 * Shortcode callback function
 */
function bd_callback() {
	return do_shortcode( '[wp_blog_designer]' );
}

add_action( 'init', 'bd_register_block' );

/**
 * Register Blog designer block
 */
function bd_register_block() {
	wp_register_script(
		'bd_block_js',
		plugins_url( 'block.js', __FILE__ ),
		array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor' ),
		filemtime( plugin_dir_path( __FILE__ ) . 'block.js' )
	);
	register_block_type(
		'blog-designer/blog-designer-block',
		array(
			'editor_script'   => 'bd_block_js',
			'render_callback' => 'bd_callback',
		)
	);
}
