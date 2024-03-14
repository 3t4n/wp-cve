<?php
/**
 * Format badge.
 *
 * @package Canvas
 */

/**
 * Initialize format badge.
 */
class CNVS_Format_Badge {

	/**
	 * Initialize
	 */
	public function __construct() {
		add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_block_editor_assets' ) );
	}

	/**
	 * Enqueue the block's assets for the editor.
	 */
	public function enqueue_block_editor_assets() {
		// Editor Scripts.
		wp_enqueue_script(
			'canvas-format-badge-editor-script',
			plugins_url( 'format-badge/block.js', __FILE__ ),
			array( 'wp-blocks', 'wp-components', 'wp-element', 'wp-i18n', 'wp-editor', 'lodash', 'jquery' ),
			filemtime( plugin_dir_path( __FILE__ ) . 'format-badge/block.js' ),
			true
		);
	}
}

new CNVS_Format_Badge();
