<?php
/**
 * Group Block.
 *
 * @package Canvas
 */

/**
 * Initialize Group block.
 */
class CNVS_Block_Group {

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
			'canvas-block-group-editor-script',
			plugins_url( 'block-group/block.js', __FILE__ ),
			array( 'wp-blocks', 'wp-components', 'wp-element', 'wp-i18n', 'wp-editor', 'lodash', 'jquery' ),
			filemtime( plugin_dir_path( __FILE__ ) . 'block-group/block.js' ),
			true
		);

		// Styles.
		wp_enqueue_style(
			'canvas-block-group-editor-style',
			plugins_url( 'block-group/block-editor.css', __FILE__ ),
			array(),
			filemtime( plugin_dir_path( __FILE__ ) . 'block-group/block-editor.css' )
		);

		wp_style_add_data( 'canvas-block-group-editor-style', 'rtl', 'replace' );
	}

	/**
	 * Enqueue assets for the front-end.
	 */
	public function wp_enqueue_scripts() {
		// Styles.
		wp_enqueue_style(
			'canvas-block-group-style',
			plugins_url( 'block-group/block.css', __FILE__ ),
			array(),
			filemtime( plugin_dir_path( __FILE__ ) . 'block-group/block.css' )
		);

		wp_style_add_data( 'canvas-block-group-style', 'rtl', 'replace' );
	}
}

new CNVS_Block_Group();
