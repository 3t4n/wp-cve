<?php
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'ewdupcpPatterns' ) ) {
/**
 * Class to handle plugin Gutenberg block patters
 *
 * @since 5.1.8
 */
class ewdupcpPatterns {

	/**
	 * Add hooks
	 * @since 5.1.8
	 */
	public function __construct() {

		add_action( 'init', array( $this, 'ewd_upcp_add_pattern_category' ) );
		add_action( 'init', array( $this, 'ewd_upcp_add_patterns' ) );
	}

	/**
	 * Register block patterns
	 * @since 5.1.8
	 */
	public function ewd_upcp_add_patterns() {

		$block_patterns = array(
			'catalog',
			'catalog-just-products',
			'featured-products-two',
			'featured-products-three',
			'featured-products-four',
			'featured-products-five',
		);
	
		foreach ( $block_patterns as $block_pattern ) {
			$pattern_file = EWD_UPCP_PLUGIN_DIR . '/includes/patterns/' . $block_pattern . '.php';
	
			register_block_pattern(
				'ultimate-product-catalogue/' . $block_pattern,
				require $pattern_file
			);
		}
	}

	/**
	 * Create a new category of block patterns to hold our pattern(s)
	 * @since 5.1.8
	 */
	public function ewd_upcp_add_pattern_category() {
		
		register_block_pattern_category(
			'ewd-upcp-block-patterns',
			array(
				'label' => __( 'Ultimate Product Catalog', 'ultimate-product-catalogue' )
			)
		);
	}
}
}