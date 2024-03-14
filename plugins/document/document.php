<?php
/**
 * Plugin Name:     Document
 * Plugin URI: 		https://embedpress.com/
 * Description:     Embed Docs, PDF, PPT, XLS or Any Documents Without Any Coding
 * Version:         1.1.0
 * Author:          EmbedPress
 * Author URI: 		https://embedpress.com/
 * License:         GPL-3.0-or-later
 * License URI:     https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:     document
 *
 * @package         document
 */

/**
 * Registers all block assets so that they can be enqueued through the block editor
 * in the corresponding context.
 *
 * @see https://developer.wordpress.org/block-editor/tutorials/block-tutorial/applying-styles-with-stylesheets/
 */
function create_block_document_block_init()
{
	$dir = dirname( __FILE__ );

	$script_asset_path = "$dir/build/index.asset.php";
	if ( !file_exists( $script_asset_path ) ) {
		throw new Error(
			'You need to run `npm start` or `npm run build` for the "create-block/document" block first.'
		);
	}

	wp_register_script(
		'document-pdf-object',
		plugins_url( 'assets/pdfobject.min.js', __FILE__ ),
		['jquery'],
		1.0
	);

	$index_js = 'build/index.js';
	$script_asset = require( $script_asset_path );
	wp_register_script(
		'create-block-document-block-editor',
		plugins_url( $index_js, __FILE__ ),
		$script_asset[ 'dependencies' ],
		$script_asset[ 'version' ]
	);

	$editor_css = 'build/index.css';
	wp_register_style(
		'create-block-document-block-editor',
		plugins_url( $editor_css, __FILE__ ),
		[],
		filemtime( "$dir/$editor_css" )
	);

	$style_css = 'build/style-index.css';
	wp_register_style(
		'create-block-document-block',
		plugins_url( $style_css, __FILE__ ),
		[],
		filemtime( "$dir/$style_css" )
	);
	if ( !WP_Block_Type_Registry::get_instance()->is_registered( 'embedpress/document' ) ) {
		register_block_type( 'block/document', [
			'editor_script' => 'create-block-document-block-editor',
			'editor_style'  => 'create-block-document-block-editor',
			'script'        => ['document-pdf-object'],
			'style'         => 'create-block-document-block',
		] );
	}

}

add_action( 'init', 'create_block_document_block_init' );
