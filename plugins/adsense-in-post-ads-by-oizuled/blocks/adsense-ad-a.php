<?php
/**
 * Registers AdSense Ad block utilities
 *
 * @package AdSense_In_Post_Ads_Block
 */
/**
 * Register block editor assets
 */
function adsense_ad_a_enqueue_block_editor_assets() {
	$dir        = dirname( __FILE__ );
	$block_js   = 'adsense-ad-a/index.js';
	wp_enqueue_script(
		'adsense-ad-block-a', plugins_url( $block_js, __FILE__ ), array(
			'wp-blocks',
			'wp-i18n',
			'wp-element',
			'wp-components',
			'wp-editor'
		), filemtime( "$dir/$block_js" )
	);
}
add_action( 'enqueue_block_editor_assets', 'adsense_ad_a_enqueue_block_editor_assets' );