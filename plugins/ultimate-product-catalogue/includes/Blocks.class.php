<?php
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'ewdupcpBlocks' ) ) {
/**
 * Class to handle plugin Gutenberg blocks
 *
 * @since 5.0.0
 */
class ewdupcpBlocks {

	public function __construct() {

		add_action( 'init', array( $this, 'add_blocks' ) );
		
		add_filter( 'block_categories_all', array( $this, 'add_block_category' ) );
	}

	/**
	 * Add the Gutenberg block to the list of available blocks
	 * @since 5.0.0
	 */
	public function add_blocks() {

		if ( ! function_exists( 'render_block_core_block' ) ) { return; }

		$this->enqueue_assets();   

		$args = array(
			'attributes' 	=> array(
				'id' 				=> array(
					'type' => 'string',
				),
				'sidebar' 			=> array(
					'type' => 'string',
				),
				'starting_layout' 	=> array(
					'type' => 'string',
				),
				'excluded_layouts' 	=> array(
					'type' => 'string',
				),
			),
			'render_callback' 	=> 'ewd_upcp_catalog_shortcode',
		);

		register_block_type( 'ultimate-product-catalogue/ewd-upcp-display-catalog-block', $args );

		$args = array(
			'attributes' => array(
				'catalogue_id' => array(
					'type' => 'string',
				),
				'catalogue_url' => array(
					'type' => 'string',
				),
				'product_count' => array(
					'type' => 'string',
					'default' => -1
				),
				'product_ids' => array(
					'type' => 'string',
				),
				'category_id' => array(
					'type' => 'string',
				),
				'subcategory_id' => array(
					'type' => 'string',
				),
			),
			'render_callback' 	=> 'ewd_upcp_minimal_products_shortcode',
		);

		register_block_type( 'ultimate-product-catalogue/ewd-upcp-insert-products-block', $args );

		$args = array(
			'attributes' => array(
				'catalogue_id' => array(
					'type' => 'string',
				),
				'catalogue_url' => array(
					'type' => 'string',
				),
				'product_count' => array(
					'type' => 'string',
					'default' => 5
				),
			),
			'render_callback' 	=> 'ewd_upcp_popular_products_shortcode',
		);

		register_block_type( 'ultimate-product-catalogue/ewd-upcp-popular-products-block', $args );

		$args = array(
			'attributes' => array(
				'catalogue_id' => array(
					'type' => 'string',
				),
				'catalogue_url' => array(
					'type' => 'string',
				),
				'product_count' => array(
					'type' => 'string',
					'default' => 5
				),
			),
			'render_callback' 	=> 'ewd_upcp_recent_products_shortcode',
		);

		register_block_type( 'ultimate-product-catalogue/ewd-upcp-recent-products-block', $args );

		$args = array(
			'attributes' => array(
				'catalogue_id' => array(
					'type' => 'string',
				),
				'catalogue_url' => array(
					'type' => 'string',
				),
				'product_count' => array(
					'type' => 'string',
					'default' => 5
				),
			),
			'render_callback' 	=> 'ewd_upcp_random_products_shortcode',
		);

		register_block_type( 'ultimate-product-catalogue/ewd-upcp-random-products-block', $args );

		$args = array(
			'attributes' => array(
				'catalogue_url' => array(
					'type' => 'string',
				),
				'search_label' => array(
					'type' => 'string',
					'default' => 'Search'
				),
				'search_placeholder' => array(
					'type' => 'string',
					'default' => 'Search...'
				),
				'submit_label' => array(
					'type' => 'string',
					'default' => 'Search'
				),
			),
			'render_callback' 	=> 'ewd_upcp_search_shortcode',
		);

		register_block_type( 'ultimate-product-catalogue/ewd-upcp-search-block', $args );

		add_action( 'current_screen', array( $this, 'localize_data' ) );
	}

	/**
	 * Localize data for use in block parameters
	 * @since 5.0.0
	 */
	public function localize_data() {

		$screen = get_current_screen();

		if ( ! $screen->is_block_editor and $screen->id != 'widgets' ) { return; }

		wp_enqueue_style( 'ewd-upcp-css' );
		wp_enqueue_style( 'ewd-upcp-blocks-css' );
		wp_enqueue_script( 'ewd-upcp-blocks-js' );

		$catalogs = new WP_Query( array(
			'post_type' => EWD_UPCP_CATALOG_POST_TYPE,
			'posts_per_page' => 1000,
			'post_status' => 'publish',
		) );

		$catalog_options = array( array( 'value' => 0, 'label' => '' ) );
		while ( $catalogs->have_posts() ) {
			$catalogs->the_post();
			$catalog_options[] = array(
				'value' => get_the_ID(),
				'label' => get_the_title(),
			);
		}
		wp_reset_postdata();

		wp_add_inline_script(
			'ewd-upcp-blocks-js',
			sprintf(
				'var ewd_upcp_blocks = %s;',
				json_encode( array(
					'catalogOptions' => $catalog_options,
				) )
			),
			'before'
		);
	}

	/**
	 * Create a new category of blocks to hold our block
	 * @since 5.0.0
	 */
	public function add_block_category( $categories ) {
		
		$categories[] = array(
			'slug'  => 'ewd-upcp-blocks',
			'title' => __( 'Ultimate Product Catalog', 'ultimate-product-catalogue' ),
		);

		return $categories;
	}	

	/**
	 * Register the necessary JS and CSS to display the block in the editor
	 * @since 5.1.6
	 */
	public function enqueue_assets() {

		wp_register_style( 'ewd-upcp-css', EWD_UPCP_PLUGIN_URL . '/assets/css/ewd-upcp.css', EWD_UPCP_VERSION );
		wp_register_style( 'ewd-upcp-blocks-css', EWD_UPCP_PLUGIN_URL . '/assets/css/ewd-upcp-blocks.css', array( 'wp-edit-blocks' ), EWD_UPCP_VERSION );
		wp_register_script( 'ewd-upcp-blocks-js', EWD_UPCP_PLUGIN_URL . '/assets/js/ewd-upcp-blocks.js', array( 'wp-blocks', 'wp-element', 'wp-components', 'wp-editor', 'wp-server-side-render' ), EWD_UPCP_VERSION );
	}
}
}