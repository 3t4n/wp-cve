<?php
/**
 * Separator Block.
 *
 * @package Canvas
 */

/**
 * Initialize Separator block.
 */
class CNVS_Block_Separator {

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
			'canvas-block-separator-editor-script',
			plugins_url( 'block-separator/block.js', __FILE__ ),
			array( 'wp-blocks', 'wp-components', 'wp-element', 'wp-i18n', 'wp-editor', 'lodash', 'jquery' ),
			filemtime( plugin_dir_path( __FILE__ ) . 'block-separator/block.js' ),
			true
		);

		// Styles.
		wp_enqueue_style(
			'canvas-block-separator-editor-style',
			plugins_url( 'block-separator/block-editor.css', __FILE__ ),
			array(),
			filemtime( plugin_dir_path( __FILE__ ) . 'block-separator/block-editor.css' )
		);

		wp_style_add_data( 'canvas-block-separator-editor-style', 'rtl', 'replace' );
	}

	/**
	 * Enqueue assets for the front-end.
	 */
	public function wp_enqueue_scripts() {
		// Styles.
		wp_enqueue_style(
			'canvas-block-separator-style',
			plugins_url( 'block-separator/block.css', __FILE__ ),
			array(),
			filemtime( plugin_dir_path( __FILE__ ) . 'block-separator/block.css' )
		);

		wp_style_add_data( 'canvas-block-separator-style', 'rtl', 'replace' );
	}
}

new CNVS_Block_Separator();
