<?php

function SME_image_block() {

	// Scripts.
	wp_register_script(
		'SME_image_block-script', // Handle.
		plugins_url( 'block.js', __FILE__ ), // Block.js: We register the block here.
		array( 'wp-blocks', 'wp-components', 'wp-element', 'wp-i18n', 'wp-editor'), // Dependencies, defined above.
		filemtime( plugin_dir_path( __FILE__ ) . 'block.js' ),
		true // Load script in footer.
	);
	// Styles.
	wp_register_style(
		'SME_image_block-editor-style', // Handle.
		plugins_url( 'editor.css', __FILE__ ), // Block editor CSS.
		array( 'wp-edit-blocks' ), // Dependency to include the CSS after it.
		filemtime( plugin_dir_path( __FILE__ ) . 'editor.css' )
	);
	wp_register_style(
		'SME_image_block-frontend-style', // Handle.
		plugins_url( 'style.css', __FILE__ ), // Block editor CSS.
		array(), // Dependency to include the CSS after it.
		filemtime( plugin_dir_path( __FILE__ ) . 'style.css' )
	);
// Register the block with WP using our namespacing
	// We also specify the scripts and styles to be used in the Gutenberg interface
	register_block_type( 'smugmugembed/block', array(
		'editor_script' => 'SME_image_block-script',
		'editor_style' => 'SME_image_block-editor-style',
		'style' => 'SME_image_block-frontend-style',
	) );

} // End function SME_image_block().
add_action( 'init', 'SME_image_block' );