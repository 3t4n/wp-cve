<?php
/**
 * Plugin Name: Useful Blocks
 * Plugin URI: https://ponhiro.com/useful-blocks/
 * Description: It is a plugin that collects very convenient blocks.
 * Version: 1.7.4
 * Requires at least: 5.9
 * Author: Ponhiro, Ryo
 * Author URI: https://ponhiro.com/useful-blocks/
 * License: GPL2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: useful-blocks
 * Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// register_block_typeが未定義のバージョンではプラグインを読み込まない
if ( ! function_exists( 'register_block_type' ) ) return;


/**
 * 定数宣言
 */
define( 'USFL_BLKS_URL', plugins_url( '/', __FILE__ ) );
define( 'USFL_BLKS_PATH', plugin_dir_path( __FILE__ ) );
define( 'USFL_BLKS_BASENAME', plugin_basename( __FILE__ ) );
define( 'USFL_BLKS_VER', ( defined( 'WP_DEBUG' ) && WP_DEBUG ) ? date('mdGis') : '1.7.4');


/**
 * Autoload
 */
spl_autoload_register( function( $classname ) {

	if ( false === strpos( $classname, 'Ponhiro_Blocks' ) ) return;

	$file_name = str_replace( 'Ponhiro_Blocks\\', '', $classname );
	$file_name = str_replace( '\\', '/', $file_name );
	$file = USFL_BLKS_PATH . 'class/' . $file_name . '.php';

	if ( file_exists( $file ) ) require $file;
});


/**
 * Ponhiro_Blocks
 */
class Ponhiro_Blocks extends \Ponhiro_Blocks\Data {

	public function __construct() {
		if ( ! defined( 'USFL_BLKS_IS_PRO' ) ) define( 'USFL_BLKS_IS_PRO', false );
	
			// データセット
			self::set_variables();
			add_action( 'init', [ __CLASS__, 'set_settings' ], 10 );

			// ファイル読み込み
			require_once USFL_BLKS_PATH . 'inc/register_blocks.php';
			require_once USFL_BLKS_PATH . 'inc/enqueue.php';
			require_once USFL_BLKS_PATH . 'inc/ajax.php';
			require_once USFL_BLKS_PATH . 'inc/hooks.php';

			// 設定ページ
			if ( is_admin() ) {
				require_once USFL_BLKS_PATH . 'inc/admin_menu.php';
			}
	}
}


/**
 * プラグイン Init
 */
add_action( 'plugins_loaded', function() {
	// 翻訳ファイルの読み込み
	load_plugin_textdomain( 'useful-blocks', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

	new Ponhiro_Blocks();
});
