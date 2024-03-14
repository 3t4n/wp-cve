<?php
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'ewdufaqPatterns' ) ) {
/**
 * Class to handle plugin Gutenberg blocks
 *
 * @since 2.1.16
 */
class ewdufaqPatterns {

	/**
	 * Add hooks
	 * @since 2.1.16
	 */
	public function __construct() {

		add_action( 'init', array( $this, 'ewd_ufaq_add_pattern_category' ) );
		add_action( 'init', array( $this, 'ewd_ufaq_add_patterns' ) );
	}

	/**
	 * Register block patterns
	 * @since 2.1.16
	 */
	public function ewd_ufaq_add_patterns() {

		$block_patterns = array(
			'faqs',
			'featured-faqs',
			'search',
			'submit',
		);
	
		foreach ( $block_patterns as $block_pattern ) {
			$pattern_file = EWD_UFAQ_PLUGIN_DIR . '/includes/patterns/' . $block_pattern . '.php';
	
			register_block_pattern(
				'ultimate-faqs/' . $block_pattern,
				require $pattern_file
			);
		}
	}

	/**
	 * Create a new category of block patterns to hold our pattern(s)
	 * @since 2.1.16
	 */
	public function ewd_ufaq_add_pattern_category() {
		
		register_block_pattern_category(
			'ewd-ufaq-block-patterns',
			array(
				'label' => __( 'Ultimate FAQs', 'ultimate-faqs' )
			)
		);
	}
}
}