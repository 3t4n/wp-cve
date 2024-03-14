<?php

/**
 * Plugin Name:       Image Quality Control | Still BE
 * Description:       Control the compression quality level of each image size individually to speed your site up display. It also contributes to improving CWV by automatically generating WebP.
 * Version:           1.7.1
 * Requires at least: 5.3
 * Requires PHP:      5.6
 * Author:            Daisuke Yamamoto
 * Author URI:        https://web.analogstd.com/
 * License:           GPL2
 * Text Domain:       still-be-image-quality-control
 */




// Do not allow direct access to the file.
if( ! defined( 'ABSPATH' ) ) {
	exit;
}


// Alt が空の時に Exif を使って自動設定するフラグ
define( 'STILLBE_IQ_AUTOSET_ALT_FROM_EXIF',         false );

// 自動リサイズした画像に圧縮品質を表す suffix を追加するフラグ
define( 'STILLBE_IQ_ENABLE_QUALITY_VALUE_SUFFIX',   false );

// srcset 属性に src 属性の画像サイズ以上を含めないようにするフラグ
define( 'STILLBE_IQ_OPTIMIZE_SRCSET',               true );

// 画像キャッシュをクリアするクエリパラメータ付与を強制するフラグ
define( 'STILLBE_IQ_ENABLE_FORCE_CACHE_CLEAR',      true );

// インターレース PNG・プログレッシブ JPEG を有効化するフラグ
// ただし、サーバ環境がインターレースに対応している場合に限る
define( 'STILLBE_IQ_ENABLE_INTERLACE',              true  );
define( 'STILLBE_IQ_ENABLE_INTERLACE_JPEG',         true  );
define( 'STILLBE_IQ_ENABLE_INTERLACE_PNG',          false );

// WebP を作成するフラグ
// ただし、サーバ環境が WebP 作成に対応している場合に限る
define( 'STILLBE_IQ_ENABLE_WEBP',                   true  );

// WebP 作成時に cwebp ライブラリを使用するフラグ
//   @since 0.5.1 true -> false
//   @since 0.9.0 false -> true
define( 'STILLBE_IQ_ENABLE_CWEBP_LIBRARY',          true );

// PNG / GIF の WebP 作成時に -lossless (or -near_lossless) 圧縮を有効にするフラグ
// ただし、cwebp が有効の場合に限る
//   @since 0.5.1 true -> false
//   @since 0.9.0 false -> true
define( 'STILLBE_IQ_ENABLE_WEBP_LOSSLESS',          true );

// PNG / GIF の WebP 作成時に -near_lossless オプションが利用可能な場合に使うフラグ
// ただし、cwebp が有効の場合に限る
define( 'STILLBE_IQ_ENABLE_WEBP_NEAR_LOSSLESS',     false );

// 元画像がインデックスカラーの場合、リサイズ画像もインデックスカラーに変換するフラグ
//   @since 1.1.1 ライブラリが GD の場合は透過色が保持されないバグがあるため false とする
define( 'STILLBE_IQ_ENABLE_INDEX_COLOR_GD',         false );
define( 'STILLBE_IQ_ENABLE_INDEX_COLOR_Imagick',    true  );

// リサイズ画像を強制的にインデックスカラーに変換するフラグ
define( 'STILLBE_IQ_ENABLE_INDEX_COLOR_FORCE',      false );

// EXIF を削除するフラグ
//   @since 1.4.0
define( 'STILLBE_IQ_ENABLE_STRIP_EXIF',             true  );

// cURL のバージョンが 7.32.0 よりも古い場合に WP-Cron のブロッキング時間が制限されない問題を解消するフラグ
define( 'STILLBE_IQ_ENABLE_DECIMAL_TIMEOUT_WPCRON', true  );

// Prefix
define( 'STILLBE_IQ_PREFIX',                        'sb-imgq-' );

// Require the Version of Extends Plugin
define( 'STILLBE_IQ_REQUIRED_EXT_PLUGIN_VER',       '1.0.0' );

// Download URL of Extends Plugin
define( 'STILLBE_IQ_REQUIRED_EXT_PLUGIN_URL',       'https://still-be.com/download/still-be-image-quality-control-extends-v1.0.0.zip' );

// Plugin Base Dir
if( ! defined( 'STILLBE_IQ_BASE_DIR' ) ) {
	define( 'STILLBE_IQ_BASE_DIR', untrailingslashit( __DIR__ ) );
}

// Plugin Base URL
if( ! defined( 'STILLBE_IQ_BASE_URL' ) ) {
	define( 'STILLBE_IQ_BASE_URL', trailingslashit( plugin_dir_url( __FILE__ ) ) );
}




// Load Translate File
add_action( 'init', function() {
	load_plugin_textdomain( 'still-be-image-quality-control' );
}, 1 );





// デフォルトの圧縮品質を返す関数
require_once( __DIR__. '/includes/function/function-stillbe-get-quality-level-array.php' );

// WebP対応ブラウザではWebPを配信するようにする.htaccessを設定する関数
require_once( __DIR__. '/includes/function/function-stillbe-iqc-htaccess-webp.php' );

// 画像IDをすべて取得してDBに保存する関数
require_once( __DIR__. '/includes/function/function-stillbe-iqc-get-attachment-ids.php' );

// 現在の設定で画像を再生成する関数
require_once( __DIR__. '/includes/function/function-stillbe-iqc-regenerate-images.php' );

// WP-Cronを使って画像を再生成する関数
require_once( __DIR__. '/includes/function/function-stillbe-iqc-arg-wpcron-run.php' );

// 画像データに手を加えずに EXIF のみを除去する関数
require_once( __DIR__. '/includes/function/function-stillbe-iqc-strip-exif.php' );


// 拡張プラグインのインストールを確認する関数
require_once( __DIR__. '/includes/function/function-stillbe-iqc-is-extended.php' );

require_once( __DIR__. '/includes/function/function-stillbe-iqc-is-enabled-cwebp.php' );


// 設定画面のタブ化用の関数
require_once( __DIR__. '/includes/function/function-stillbe-do-settings-sections-tab-style.php' );


// コアの画像処理系に適応するフィルター
require_once( __DIR__. '/includes/add-filters.php' );

// メタデータ生成時に alt を exif から自動設定する
require_once( __DIR__. '/includes/operate-exif.php' );


// Image Editor で使用する共通メソッドを定義する
require_once( __DIR__. '/includes/trait/trait-stillbe-image-editor-common-variables.php' );

require_once( __DIR__. '/includes/trait/trait-stillbe-image-editor-common-overwrite.php' );

require_once( __DIR__. '/includes/trait/trait-stillbe-image-editor-common-append.php' );


// GD と Imagick の Class ファイルを読み込む
if( file_exists( ABSPATH. WPINC. '/class-wp-image-editor.php' ) ) {
	require_once( ABSPATH. WPINC. '/class-wp-image-editor.php' );
}
if( file_exists( ABSPATH. WPINC. '/class-wp-image-editor-gd.php' ) ) {
	require_once( ABSPATH. WPINC. '/class-wp-image-editor-gd.php' );
}
if( file_exists( ABSPATH. WPINC. '/class-wp-image-editor-imagick.php' ) ) {
	require_once( ABSPATH. WPINC. '/class-wp-image-editor-imagick.php' );
}


// WP の組込 Class を継承した GD 用エディタ Class
if( class_exists( 'WP_Image_Editor_GD' ) ) {
	require_once( __DIR__. '/includes/class/class-stillbe-wp-image-editor-gd.php' );
}

// WP の組込 Class を継承した Imagick 用エディタ Class
if( class_exists( 'WP_Image_Editor_Imagick' ) ) {
	require_once( __DIR__. '/includes/class/class-stillbe-wp-image-editor-imagick.php' );
}


// 有効化の時にWebP置換用のhtaccessを追加する
register_activation_hook( __FILE__, function() {
	if( apply_filters( 'stillbe_image_quality_control_enable_webp', STILLBE_IQ_ENABLE_WEBP, 'activate' ) ) {
		_stillbe_iqc_htaccess_webp( null );
	}
} );

// 有効化の時にWebP置換用のhtaccessを削除する
register_deactivation_hook( __FILE__, function() {
	_stillbe_iqc_htaccess_webp( false );
} );


// 設定画面
require_once( __DIR__. '/includes/trait/trait-stillbe-iqc-setting-main.php' );

require_once( __DIR__. '/includes/trait/trait-stillbe-iqc-setting-common-methods.php' );

require_once( __DIR__. '/includes/trait/trait-stillbe-iqc-setting-section-general.php' );

require_once( __DIR__. '/includes/trait/trait-stillbe-iqc-setting-section-test.php' );

require_once( __DIR__. '/includes/trait/trait-stillbe-iqc-setting-section-advanced-toggle.php' );

require_once( __DIR__. '/includes/trait/trait-stillbe-iqc-setting-section-advanced-others.php' );

require_once( __DIR__. '/includes/trait/trait-stillbe-iqc-setting-section-recomp.php' );

require_once( __DIR__. '/includes/class/class-stillbe-img-quality-ctrl-setting.php' );

require_once( __DIR__. '/includes/class/class-stillbe-img-quality-ctrl-other-products.php' );

if( is_admin() ) {
	$GLOBALS['sb-iqc-setting'] = new StillBE_Image_Quality_Ctrl_Setting();
}


// 設定を適応するフィルター
require_once( __DIR__. '/includes/apply-filter-settings.php' );


// 画像のメタデータ取得
require_once( __DIR__. '/includes/ajax/ajax-get-attachment-meta.php' );


// 画像のID一覧
require_once( __DIR__. '/includes/ajax/ajax-get-attachment-ids.php' );


// 再生成用
require_once( __DIR__. '/includes/ajax/ajax-regenerate-images.php' );


// テストイメージ作成用
require_once( __DIR__. '/includes/ajax/ajax-generate-test-image.php' );


// 設定リセット用
require_once( __DIR__. '/includes/ajax/ajax-reset.php' );




// END of the File



