<?php
/**
 * Heading Block.
 *
 * @package Canvas
 */

/**
 * Initialize Heading block.
 */
class CNVS_Block_Heading {

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
			'canvas-block-heading-editor-script',
			plugins_url( 'block-heading/block.js', __FILE__ ),
			array( 'wp-blocks', 'wp-components', 'wp-element', 'wp-i18n', 'wp-editor', 'lodash', 'jquery' ),
			filemtime( plugin_dir_path( __FILE__ ) . 'block-heading/block.js' ),
			true
		);

		// Styles.
		wp_enqueue_style(
			'canvas-block-heading-editor-style',
			plugins_url( 'block-heading/block-editor.css', __FILE__ ),
			array(),
			filemtime( plugin_dir_path( __FILE__ ) . 'block-heading/block-editor.css' )
		);

		wp_style_add_data( 'canvas-block-heading-editor-style', 'rtl', 'replace' );
	}

	/**
	 * Enqueue assets for the front-end.
	 */
	public function wp_enqueue_scripts() {
		// Styles.
		wp_enqueue_style(
			'canvas-block-heading-style',
			plugins_url( 'block-heading/block.css', __FILE__ ),
			array(),
			filemtime( plugin_dir_path( __FILE__ ) . 'block-heading/block.css' )
		);

		wp_style_add_data( 'canvas-block-heading-style', 'rtl', 'replace' );
	}
}

new CNVS_Block_Heading();
