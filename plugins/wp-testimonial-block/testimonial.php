<?php
/**
 * Plugin Name:     Testimonial Block
 * Description:     A block to display testimonial on the site.
 * Version:         1.0.0
 * Author:          Achal Jain
 * Author URI:  	https://achalj.github.io
 * License:         GPL-2.0-or-later
 * License URI:     https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:     ib-testimonial
 */

/**
 * Registers all block assets so that they can be enqueued through the block editor
 * in the corresponding context.
 *
 * @see https://developer.wordpress.org/block-editor/tutorials/block-tutorial/applying-styles-with-stylesheets/
 */
function ideabox_testimonial_block_init() {
	$dir = dirname( __FILE__ );

	$script_asset_path = "$dir/build/index.asset.php";
	if ( ! file_exists( $script_asset_path ) ) {
		throw new Error(
			'You need to run `npm start` or `npm run build` for the "ideabox/testimonial" block first.'
		);
	}
	$index_js     = 'build/index.js';
	$script_asset = require( $script_asset_path );
	wp_register_script(
		'ideabox-testimonial-block-editor',
		plugins_url( $index_js, __FILE__ ),
		$script_asset['dependencies'],
		$script_asset['version']
	);
	wp_set_script_translations( 'ideabox-testimonial-block-editor', 'ib-testimonial' );

	$editor_css = 'build/index.css';
	wp_register_style(
		'ideabox-testimonial-block-editor',
		plugins_url( $editor_css, __FILE__ ),
		array(),
		filemtime( "$dir/$editor_css" )
	);

	$style_css = 'build/style-index.css';
	wp_register_style(
		'ideabox-testimonial-block',
		plugins_url( $style_css, __FILE__ ),
		array(),
		filemtime( "$dir/$style_css" )
	);

	register_block_type( 'ideabox/testimonial', array(
		'editor_script' => 'ideabox-testimonial-block-editor',
		'editor_style'  => 'ideabox-testimonial-block-editor',
		'style'         => 'ideabox-testimonial-block',
	) );
}
add_action( 'init', 'ideabox_testimonial_block_init' );
