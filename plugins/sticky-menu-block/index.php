<?php
/**
 * Plugin Name: Sticky Content Block
 * Description: Stick element to top when reached at top.
 * Version: 1.0.1
 * Author: bPlugins LLC
 * Author URI: http://bplugins.com
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain: sticky-menu
 */

// ABS PATH
if ( !defined( 'ABSPATH' ) ) { exit; }

// Constant
define( 'SMB_PLUGIN_VERSION', isset($_SERVER['HTTP_HOST']) && 'localhost' === $_SERVER['HTTP_HOST'] ? time() : '1.0.1' );
define( 'SMB_ASSETS_DIR', plugin_dir_url( __FILE__ ) . 'assets/' );

// Sticky Content Block
class SMBStickyMenu{
	function __construct(){
		add_action( 'enqueue_block_assets', [$this, 'enqueueBlockAssets'] );
		add_action( 'init', [$this, 'onInit'] );
	}

	function enqueueBlockAssets(){ wp_enqueue_script( 'stickyAnything', SMB_ASSETS_DIR . 'js/jq-sticky-anything.min.js', [ 'jquery' ], '2.0.1' ); }

	function onInit() {
		wp_register_style( 'smb-sticky-editor-style', plugins_url( 'dist/editor.css', __FILE__ ), [ 'wp-edit-blocks' ], SMB_PLUGIN_VERSION ); // Backend Style
		wp_register_style( 'smb-sticky-style', plugins_url( 'dist/style.css', __FILE__ ), [ 'wp-editor' ], SMB_PLUGIN_VERSION ); // Frontend Style

		register_block_type( __DIR__, [
			'editor_style'		=> 'smb-sticky-editor-style',
			'style'				=> 'smb-sticky-style',
			'render_callback'	=> [$this, 'render']
		] ); // Register Block

		wp_set_script_translations( 'smb-sticky-editor-script', 'sticky-block', plugin_dir_path( __FILE__ ) . 'languages' ); // Translate
	}

	function render( $attributes, $content ){
		extract( $attributes );

		$className = $className ?? '';
		$smbBlockClassName = 'wp-block-smb-sticky ' . $className . ' align' . $align;

		ob_start(); ?>
		<div class='<?php echo esc_attr( $smbBlockClassName ); ?>' id='smbStickyMenu-<?php echo esc_attr( $cId ) ?>' data-props='<?php echo esc_attr( wp_json_encode( [ 'attributes' => $attributes, 'content' => $content ] ) ); ?>'></div>

		<?php return ob_get_clean();
	} // Render
}
new SMBStickyMenu;