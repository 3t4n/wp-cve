<?php
namespace Ponhiro_Blocks;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * ブロックの登録
 */
add_action( 'init', function() {
	pb_register_block_type( 'cv-box' );
	pb_register_block_type( 'cv-box-note' );
	pb_register_block_type( 'compare-box' );
	pb_register_block_type( 'iconbox' );
	pb_register_block_type( 'list' );
	pb_register_block_type( 'button' );
	pb_register_block_type( 'image' );
	pb_register_block_type( 'bar-graph' );
	pb_register_block_type( 'bar-graph-item' );

	// 5.6以降でのみ使用可能なブロック(api v2 使用)
	global $wp_version;
	if ( version_compare( $wp_version, '5.6.RC1' ) >= 0 ) {
		pb_register_block_type( 'rating-graph' );
		pb_register_block_type( 'rating-graph-item' );
	}
});


/**
 * ブロックの登録処理
 */
function pb_register_block_type( $block_name ) {
	$asset = include( USFL_BLKS_PATH. 'dist/blocks/'. $block_name .'/index.asset.php');

	$script_handle = 'ponhiro-blocks/'. $block_name;

	// ブロック用スクリプトの登録
	wp_register_script(
		$script_handle,
		USFL_BLKS_URL. 'dist/blocks/'. $block_name .'/index.js',
		array_merge( ['ponhiro-blocks-script'], $asset['dependencies'] ),
		$asset['version'],
		true
	);

	// ブロックの登録
	register_block_type(
		'ponhiro-blocks/'. $block_name,
		[
			// 'editor_style'    => 'ponhiro-blocks-style',
			'editor_script'   => $script_handle,
		]
	);
}


/**
 * ブロックカテゴリー追加
 */
global $wp_version;
$hookname = ( version_compare( $wp_version, '5.8-beta' ) >= 0 ) ? 'block_categories_all' : 'block_categories';
add_filter( $hookname, function( $categories ) {

	$my_category = [
		[
			'slug'  => 'useful-blocks',
			'title' => __( 'Useful Blocks', 'useful-blocks' ),
			'icon'  => null,
		]
	];

	// ウィジェットの前にカテゴリーを追加する
	foreach ( $categories as $index => $data ) {
		$slug = $data['slug'] ?? '';
		if ( 'widgets' === $slug ) {
			array_splice( $categories, $index, 0, $my_category );
			break;
		}
	}

	return $categories;
} );
