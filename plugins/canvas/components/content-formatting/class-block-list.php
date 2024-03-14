<?php
/**
 * List Block.
 *
 * @package Canvas
 */

/**
 * Initialize List block.
 */
class CNVS_Block_List {

	/**
	 * Initialize
	 */
	public function __construct() {
		add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_block_editor_assets' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) );
	}

	/**
	 * Enqueue the block's assets for the editor.
	 */
	public function enqueue_block_editor_assets() {
		// Editor Scripts.
		wp_enqueue_script(
			'canvas-block-list-editor-script',
			plugins_url( 'block-list/block.js', __FILE__ ),
			array( 'wp-blocks', 'wp-components', 'wp-element', 'wp-i18n', 'wp-editor', 'lodash', 'jquery' ),
			filemtime( plugin_dir_path( __FILE__ ) . 'block-list/block.js' ),
			true
		);
		// Styles.
		wp_enqueue_style(
			'canvas-block-list-editor-style',
			plugins_url( 'block-list/block-editor.css', __FILE__ ),
			array(),
			filemtime( plugin_dir_path( __FILE__ ) . 'block-list/block-editor.css' )
		);

		wp_style_add_data( 'canvas-block-list-editor-style', 'rtl', 'replace' );
	}

	/**
	 * Enqueue assets for the front-end.
	 */
	public function wp_enqueue_scripts() {
		// Styles.
		wp_enqueue_style(
			'canvas-block-list-style',
			plugins_url( 'block-list/block.css', __FILE__ ),
			array(),
			filemtime( plugin_dir_path( __FILE__ ) . 'block-list/block.css' )
		);

		wp_style_add_data( 'canvas-block-list-style', 'rtl', 'replace' );
	}
}

new CNVS_Block_List();
