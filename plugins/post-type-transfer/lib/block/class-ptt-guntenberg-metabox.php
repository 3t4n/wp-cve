<?php
// If check class exist or not
if ( ! class_exists( 'PTT_Gutenberg_Metabox' ) ) {

	class PTT_Gutenberg_Metabox extends Post_Type_Transfer {
		// Calling class construct
		public function __construct() {
			add_action( 'add_meta_boxes', array( $this, 'ptt_gutenberg_editor_metabox' ) );
			add_action( 'enqueue_block_editor_assets', array( $this, 'ptt_gutenberg_editor_enqueue' ) );
		}

		/**
		 * Register gutenberg metabox
		 */
		public function ptt_gutenberg_editor_metabox() {
			// Register metabox
			add_meta_box( 'ptt-gutenberg', __( 'Post Types', 'ptt-post-types' ), array( $this, 'ptt_post_metabox' ),
				null, 'side', 'high',
				array(
					'__block_editor_compatible_meta_box' => true,
				)
			);
		}

		/**
		 * Enqueue script
		 */
		public function ptt_gutenberg_editor_enqueue() {
			wp_enqueue_style( 'ptt-gutenberg', plugin_dir_url( __FILE__ ) . 'css/style.css' );
			wp_enqueue_script( 'ptt-gutenberg', plugin_dir_url( __FILE__ ) . 'js/block.js', array( 'jquery', 'wp-blocks' ), '', true );
			wp_enqueue_script( 'jquery-sweetalert', plugin_dir_url( __FILE__ ) . 'js/sweetalert.min.js', array( 'jquery', 'wp-blocks' ), '', true );
		}
	}
}