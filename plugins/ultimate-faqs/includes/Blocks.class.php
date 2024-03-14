<?php
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'ewdufaqBlocks' ) ) {
/**
 * Class to handle plugin Gutenberg blocks
 *
 * @since 2.0.0
 */
class ewdufaqBlocks {

	public function __construct() {

		add_action( 'init', array( $this, 'add_faq_blocks' ) );
		
		add_filter( 'block_categories_all', array( $this, 'add_block_category' ) );
	}

	/**
	 * Add the Gutenberg block to the list of available blocks
	 * @since 2.0.0
	 */
	public function add_faq_blocks() {

		if ( ! function_exists( 'render_block_core_block' ) ) { return; }

		$this->enqueue_assets();   

		$args = array(
			'attributes' => array(
				'post_count' => array(
					'type' => 'string',
					'default' => -1
				),
				'group_by_category' => array(
					'type' => 'string',
				),
				'faq_accordion' => array(
					'type' => 'string',
				),
				'category_accordion' => array(
					'type' => 'string',
				),
				'include_category_array' => array(
					'type' => 'object',
				),
				'include_category' => array(
					'type' => 'string',
				),
				'exclude_category' => array(
					'type' => 'string',
				),
				'post__in_string' => array(
					'type' => 'string',
				),
			),
			'render_callback' 	=> 'ewd_ufaq_faqs_shortcode',
		);

		register_block_type( 'ultimate-faqs/ewd-ufaq-display-faq-block', $args );

		$args = array(
			'attributes' => array(
				'post_count' => array(
					'type' => 'string',
					'default' => -1
				),
			),
			'render_callback' 	=> 'ewd_ufaq_recent_faqs_shortcode',
		);

		register_block_type( 'ultimate-faqs/ewd-ufaq-recent-faqs-block', $args );

		$args = array(
			'attributes' => array(
				'post_count' => array(
					'type' => 'string',
					'default' => -1
				),
			),
			'render_callback' 	=> 'ewd_ufaq_popular_faqs_shortcode',
		);

		register_block_type( 'ultimate-faqs/ewd-ufaq-popular-faqs-block', $args );

		$args = array(
			'attributes' => array(
				'show_on_load' => array(
					'type' => 'string',
				),
				'include_category_array' => array(
					'type' => 'object',
				),
				'include_category' => array(
					'type' => 'string',
				),
				'exclude_category' => array(
					'type' => 'string',
				),
			),
			'render_callback' 	=> 'ewd_ufaq_search_faqs_shortcode',
		);

		register_block_type( 'ultimate-faqs/ewd-ufaq-search-block', $args );

		$args = array(
			'render_callback' 	=> 'ewd_ufaq_submit_faq_shortcode',
		);

		register_block_type( 'ultimate-faqs/ewd-ufaq-submit-faq-block', $args );

		add_action( 'current_screen', array( $this, 'localize_data' ) );
	}

	/**
	 * Conditionally enqueue assets and perform any necessary data localization for use in block parameters
	 * @since 2.1.13
	 */
	public function localize_data() {

		$screen = get_current_screen();

		if ( ! $screen->is_block_editor and $screen->id != 'widgets' ) { return; }

		wp_enqueue_style( 'ewd-ufaq-css' );
		wp_enqueue_style( 'ewd-ufaq-blocks-css' );
		wp_enqueue_script( 'ewd-ufaq-blocks-js' );

		$faq_categories = get_terms( array(
			'taxonomy' => EWD_UFAQ_FAQ_CATEGORY_TAXONOMY,
			'hide_empty' => false,
		) );

		foreach ( $faq_categories as $faq_category ) {

			$faq_category_options[] = array(
				'label' => $faq_category->name,
				'slug'	=> $faq_category->slug,
			);
		}

		wp_add_inline_script(
			'ewd-ufaq-blocks-js',
			sprintf(
				'var ewd_ufaq_blocks = %s;',
				json_encode( array(
					'faqCategoryOptions' => $faq_category_options,
				) )
			),
			'before'
		);

	}
	
	/**
	 * Create a new category of blocks to hold our block
	 * @since 2.0.0
	 */
	public function add_block_category( $categories ) {
		
		$categories[] = array(
			'slug'  => 'ewd-ufaq-blocks',
			'title' => __( 'Ultimate FAQs', 'ultimate-faqs' ),
		);

		return $categories;
	}

	/**
	 * Register the necessary JS and CSS to display the block in the editor
	 * @since 2.0.0
	 */
	public function enqueue_assets() {

		wp_register_style( 'ewd-ufaq-css', EWD_UFAQ_PLUGIN_URL . '/assets/css/ewd-ufaq.css', EWD_UFAQ_VERSION );
		wp_register_style( 'ewd-ufaq-blocks-css', EWD_UFAQ_PLUGIN_URL . '/assets/css/ewd-ufaq-blocks.css', array( 'wp-edit-blocks' ), EWD_UFAQ_VERSION );
		wp_register_script( 'ewd-ufaq-blocks-js', EWD_UFAQ_PLUGIN_URL . '/assets/js/ewd-ufaq-blocks.js', array( 'wp-blocks', 'wp-element', 'wp-components', 'wp-editor', 'wp-server-side-render' ), EWD_UFAQ_VERSION );
	}
}

}