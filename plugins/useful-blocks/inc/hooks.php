<?php
namespace Ponhiro_Blocks;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Pro版へのリンクを追加
 */
add_action( 'plugin_action_links_' . USFL_BLKS_BASENAME, __NAMESPACE__ . '\hook__plugin_action_links' );
function hook__plugin_action_links( $links ) {
	if ( USFL_BLKS_IS_PRO ) return $links;

	return array_merge( $links, [
		'<a class="pb-link-gopro" target="_blank" href="https://ponhiro.com/useful-blocks/" style="color: #42ce78;font-weight: 700;">' . esc_html__( 'Go Pro', 'useful-blocks' ) . '</a>',
	]);
}


/**
 * テーマごとの追加調整
 */
add_action( 'wp_head', __NAMESPACE__ . '\add_adjustment_css', 20 );
function add_adjustment_css() {
	$css = '';

	// テーマ情報取得
	$theme_data     = wp_get_theme();
	$theme_name     = $theme_data->get( 'Name' );
	$theme_template = $theme_data->get( 'Template' );

	// JINの場合
	if ( 'JIN' === $theme_name || 'jin' === $theme_template ) {
		$css .= '.pb-cv-box, .pb-compare-box, .pb-iconbox, .pb-bar-graph{ margin-top: 0 !important;}';
	}
	if ( $css ) {
		echo '<style id="usfl-blks-adjustment-css">' . $css . '</style>'. PHP_EOL;
	}
}

