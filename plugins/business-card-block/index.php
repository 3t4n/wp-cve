<?php
/**
 * Plugin Name: Business Card Block
 * Description: Show your business card in web.
 * Version: 1.0.5
 * Author: bPlugins LLC
 * Author URI: http://bplugins.com
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain: business-card
 */

// ABS PATH
if ( !defined( 'ABSPATH' ) ) { exit; }

// Constant
define( 'BCB_PLUGIN_VERSION', isset( $_SERVER['HTTP_HOST'] ) && 'localhost' === $_SERVER['HTTP_HOST'] ? time() : '1.0.5' );
define( 'BCB_ASSETS_DIR', plugin_dir_url( __FILE__ ) . 'assets/' );

// Business Card
class BCBBusinessCard{
	function __construct(){
		add_action( 'enqueue_block_assets', [$this, 'enqueueBlockAssets'] );
		add_action( 'init', [$this, 'onInit'] );
	}

	function enqueueBlockAssets(){
		wp_enqueue_style( 'fontAwesome', BCB_ASSETS_DIR . 'css/fontAwesome.min.css', [], '5.15.4' ); // Font Awesome
	}

	function onInit() {
		wp_register_style( 'business-card-editor-style', plugins_url( 'dist/editor.css', __FILE__ ), [ 'business-card-style' ], BCB_PLUGIN_VERSION ); // Backend Style
		wp_register_style( 'business-card-style', plugins_url( 'dist/style.css', __FILE__ ), [], BCB_PLUGIN_VERSION ); // Style

		register_block_type( __DIR__, [
			'editor_style'		=> 'business-card-editor-style',
			'style'				=> 'business-card-style',
			'render_callback'	=> [$this, 'render']
		] ); // Register Block

		wp_set_script_translations( 'business-card-editor-script', 'business-card', plugin_dir_path( __FILE__ ) . 'languages' ); // Translate
	}

	function render( $attributes ){
		extract( $attributes );

		$className = $className ?? '';
		$blockClassName = 'wp-block-business-card ' . $className . ' align' . $align;

		ob_start(); ?>
		<div class='<?php echo esc_attr( $blockClassName ); ?>' id='bcbBusinessCard-<?php echo esc_attr( $cId ) ?>' data-attributes='<?php echo esc_attr( wp_json_encode( $attributes ) ); ?>'></div>

		<?php return ob_get_clean();
	} // Render
}
new BCBBusinessCard();