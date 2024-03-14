<?php

namespace Themes\DefaultTheme;

use Vimeotheque\Plugin;
use Vimeotheque\Themes\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * @private
 * @ignore
 *
 * Playlist theme Default script enqueue.
 *
 * Callback function for the block editor script hook that
 * enqueues block editor Playlist Block attributes extension.
 */
function block_editor() {
	wp_enqueue_script(
		'vimeotheque-theme-default-attributes',
		plugin_dir_url( __FILE__ ) . 'assets/js/block/app.build.js',
		[ Plugin::instance()->get_block( 'playlist' )->get_script_handle() ],
		'1.0.0',
		true
	);
}

add_action( 'enqueue_block_editor_assets', __NAMESPACE__ . '\block_editor' );

/**
 * @private
 * @return string
 * @since 2.0.15
 *
 * @ignore
 *
 * Get the image size name based on playlist option 'original_thumbnail_size' value
 *
 */
function get_image_size() {
	$options = Helper::get_player_options();

	return isset( $options['use_original_thumbnails'] )
	       && $options['use_original_thumbnails'] ? 'original' : 'small';
}