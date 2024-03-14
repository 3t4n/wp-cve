<?php
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'ewduwcfBlocks' ) ) {
/**
 * Class to handle plugin Gutenberg blocks
 *
 * @since 3.0.0
 */
class ewduwcfBlocks {

	public function __construct() {

		add_action( 'init', array( $this, 'add_filtering_block' ) );
		
		add_filter( 'block_categories_all', array( $this, 'add_block_category' ) );
	}

	/**
	 * Add the Gutenberg block to the list of available blocks
	 * @since 3.0.0
	 */
	public function add_filtering_block() {

		if ( ! function_exists( 'render_block_core_block' ) ) { return; }

		$this->enqueue_assets();   

		$args = array(
			'attributes' 		=> array(),
			'editor_script'   	=> 'ewd-uwcf-blocks-js',
			'editor_style'  	=> 'ewd-uwcf-blocks-css',
			'render_callback' 	=> 'ewd_uwcf_filters_shortcode',
		);

		register_block_type( 'color-filters/ewd-uwcf-display-filters-block', $args );

	}

	/**
	 * Create a new category of blocks to hold our block
	 * @since 3.0.0
	 */
	public function add_block_category( $categories ) {
		
		$categories[] = array(
			'slug'  => 'ewd-uwcf-blocks',
			'title' => __( 'Ultimate WooCommerce Filters', 'color-filters' ),
		);

		return $categories;
	}

	/**
	 * Register the necessary JS and CSS to display the block in the editor
	 * @since 3.0.0
	 */
	public function enqueue_assets() {

		wp_register_script( 'ewd-uwcf-blocks-js', EWD_UWCF_PLUGIN_URL . '/assets/js/ewd-uwcf-blocks.js', array( 'wp-blocks', 'wp-element', 'wp-components', 'wp-editor' ), EWD_UWCF_VERSION );
		wp_register_style( 'ewd-uwcf-blocks-css', EWD_UWCF_PLUGIN_URL . '/assets/css/ewd-uwcf-blocks.css', array( 'wp-edit-blocks' ), EWD_UWCF_VERSION );
	}
}

}