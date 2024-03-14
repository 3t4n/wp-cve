<?php
namespace Ponhiro_Blocks;

use \Ponhiro_Blocks\Menu as Menu;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * 管理画面に独自メニューを追加
 */
add_action( 'admin_menu', __NAMESPACE__ . '\hook__admin_menu');
function hook__admin_menu() {

	add_menu_page(
		__( 'Useful Blocks', 'useful-blocks' ), // ページタイトルタグ
		__( 'Useful Blocks', 'useful-blocks' ), // メニュータイトル
		'manage_options', // 必要な権限
		\Ponhiro_Blocks::PAGE_SLUG, // このメニューを参照するスラッグ名
		function () {
			global $is_IE;
			if ( $is_IE ) {
				echo '<div style="padding:2em;font-size:2em;">※ IE以外のブラウザをお使いください。</div>';
				return;
			}

			if ( has_action( 'usefl_blks_admin_menu' ) ) {
				echo '<div style="padding:1.5em;font-size:1.5em;">※ Useful Blocks Pro-Addon を最新版へ更新してください。</div>';
				return;
			}

			require_once __DIR__ . '/menu/setting_page.php';
		},
		'dashicons-screenoptions', // アイコン
		30 // 管理画面での表示位置
	);
}



/**
 * 設定の追加
 */
add_action( 'admin_init', __NAMESPACE__ . '\hook__admin_init');
function hook__admin_init() {

	// 同じオプションに配列で値を保存するので、register_setting() は１つだけ
	register_setting( 'usfl_blks_setting_group', \Ponhiro_Blocks::DB_NAME['settings'] );

	Menu\Tab_Colors::color_set( \Ponhiro_Blocks::PAGE_NAMES['colors'] );
	Menu\Tab_Colors::cv_box( \Ponhiro_Blocks::PAGE_NAMES['colors'] );
	Menu\Tab_Colors::compare( \Ponhiro_Blocks::PAGE_NAMES['colors'] );
	Menu\Tab_Colors::iconbox( \Ponhiro_Blocks::PAGE_NAMES['colors'] );
	Menu\Tab_Colors::bar_graph( \Ponhiro_Blocks::PAGE_NAMES['colors'] );
	Menu\Tab_Colors::rating_graph( \Ponhiro_Blocks::PAGE_NAMES['colors'] );
	Menu\Tab_Icons::iconbox( \Ponhiro_Blocks::PAGE_NAMES['icons'] );

}
