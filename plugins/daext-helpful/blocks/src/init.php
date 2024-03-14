<?php

//Prevent direct access to this file
if ( ! defined( 'WPINC' ) ) {
	die();
}

/**
 * Enqueue the Gutenberg block assets for the backend.
 *
 * 'wp-blocks': includes block type registration and related functions.
 * 'wp-element': includes the WordPress Element abstraction for describing the structure of your blocks.
 */
function daexthefu_editor_assets() {


	// Get the list of post types where the form should be applied.
	$post_types_a = maybe_unserialize( get_option( 'daexthefu_post_types' ) );

	// Verify the post type.
	if ( ! is_array( $post_types_a ) || ! in_array( get_post_type(), $post_types_a, true ) ) {
		return;
	}

	//Styles -----------------------------------------------------------------------------------------------------------

	wp_enqueue_style(
		'daexthefu-editor-css',
		plugins_url( 'css/editor.css', dirname( __FILE__ ) ),
		array( 'wp-edit-blocks' )//Dependency to include the CSS after it.
	);

	//Scripts ----------------------------------------------------------------------------------------------------------

	wp_enqueue_script(
		'daexthefu-editor-js', // Handle.
		plugins_url( '/build/index.js', dirname( __FILE__ ) ), //We register the block here.
		array( 'wp-blocks', 'wp-element' ), // Dependencies.
		false,
		true //Enqueue the script in the footer.
	);

}

/**
 * Do not enable the editor assets if we are in one of the following menus:
 *
 * - Appearance -> Widgets (widgets.php).
 * - Appearance -> Editor (site-editor.php)
 *
 * Enabling the assets in the widgets.php or site-editor.php menus would cause errors because the post editor sidebar is
 * not available in these menus.
 */
global $pagenow;
if ( $pagenow !== 'widgets.php' and
     $pagenow !== 'site-editor.php' ) {
	add_action( 'enqueue_block_editor_assets', 'daexthefu_editor_assets' );
}

/**
 * Register the meta fields used in the components of the post sidebar.
 *
 * See: https://developer.wordpress.org/reference/functions/register_post_meta/
 */
function helpful_register_post_meta() {

	/*
	 * Register the meta used to save the value of the selector available in the "Helpful" section of the post sidebar
	 * included in the post editor.
	 */
	register_post_meta(
		'', //Registered in all post types
		'_helpful_status',
		[
			'auth_callback' => '__return_true',
			'default'       => 1,
			'show_in_rest'  => true,
			'single'        => true,
			'type'          => 'integer',
		]
	);

}

add_action( 'init', 'helpful_register_post_meta' );