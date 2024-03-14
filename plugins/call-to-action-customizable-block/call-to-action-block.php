<?php
/**
 * Plugin Name: Call to Action Customizable Block
 * Author: Bhavesh Khadodara
 * Version: 1.1.0
 * License: GPL2+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Description: Call to Action Gutenberg Block.
 */

defined( 'ABSPATH' ) || exit;

/**
 * Enqueue the block's assets for the editor.
 *
 * wp-blocks:  The registerBlockType() function to register blocks.
 * wp-element: The wp.element.createElement() function to create elements.
 * wp-i18n:    The __() function for internationalization.
 *
 * @since 1.0.0
 */
function call_to_action() {
	wp_enqueue_script(
		'call-to-action-block', 
		plugins_url( 'js/block.build.js', __FILE__ ),
		array( 'wp-blocks', 'wp-i18n', 'wp-editor', 'wp-element' )
	);
	wp_enqueue_style(
        'call-to-action-editor-css',
        plugins_url( 'css/calltoactionback.css', __FILE__ ),
        array( 'wp-edit-blocks' ) 
    );
}
add_action( 'enqueue_block_editor_assets', 'call_to_action' );

function call_to_action_block_assets() {
    wp_enqueue_style(
        'call-to-action-style-css', 
        plugins_url( 'css/calltoactionfront.css', __FILE__ ),
        array( 'wp-editor' ) 
    );
}
add_action( 'enqueue_block_assets', 'call_to_action_block_assets' );