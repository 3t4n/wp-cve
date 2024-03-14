<?php
/**
 * The Gutenberg Block.
 *
 * @link       https://codesupply.co
 * @since      1.0.0
 *
 * @package    ABR
 * @subpackage ABR/includes
 */

/**
 * The initialize block.
 */
class ABR_Review_Block {

	/**
	 * Initialize
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'block' ) );
		add_filter( 'canvas_register_block_type', array( $this, 'register_block_type' ) );
	}

	/**
	 * Enqueue the block's assets for the editor.
	 */
	public function block() {
		// Styles.
		wp_register_style(
			'absolute-reviews-block-editor-style',
			ABR_URL . 'public/css/absolute-reviews-public.css',
			array( 'wp-edit-blocks' ),
			filemtime( ABR_PATH . 'public/css/absolute-reviews-public.css' )
		);

		wp_style_add_data( 'absolute-reviews-block-editor-style', 'rtl', 'replace' );
	}

	/**
	 * Register block
	 *
	 * @param array $blocks all registered blocks.
	 * @return array
	 */
	public function register_block_type( $blocks ) {

		// Add block.
		$blocks[] = array(
			'name'         => 'canvas/absolute-reviews',
			'title'        => esc_html__( 'Reviews', 'absolute-reviews' ),
			'description'  => esc_html__( 'The block allows you to display images from your review account.', 'absolute-reviews' ),
			'category'     => 'canvas',
			'keywords'     => array(),
			'icon'         => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><rect x="0" fill="none" width="20" height="20"/><g><path d="M10 1l3 6 6 .75-4.12 4.62L16 19l-6-3-6 3 1.13-6.63L1 7.75 7 7z"/></g></svg>',
			'supports'     => array(
				'className'      => true,
				'anchor'         => true,
				'html'           => false,
				'canvasSpacings' => true,
			),
			'styles'       => array(),
			'location'     => array(),
			'sections'     => array(
				'general'      => array(
					'title'    => esc_html__( 'Block Settings', 'absolute-reviews' ),
					'priority' => 5,
					'open'     => true,
				),
				'schema_attrs' => array(
					'title'    => esc_html__( 'Schema Attributes', 'absolute-reviews' ),
					'priority' => 10,
				),
				'typography'  => array(
					'title'    => esc_html__( 'Typography Settings', 'absolute-reviews' ),
					'priority' => 10,
				),
			),
			'layouts'      => array(
				'percentage' => array(
					'name'     => esc_html__( 'Percentage (1-100%)', 'absolute-reviews' ),
					'icon'     => '<svg width="52" height="44" xmlns="http://www.w3.org/2000/svg"><g transform="translate(1 1)" fill="none" fill-rule="evenodd"><rect stroke="#2D2D2D" stroke-width="1.5" width="50" height="42" rx="3"/><path d="M19 19.429h18.286M19 16h24m-24-3.429h20.571M7 30.429h33.75M7 27h31.5M7 23.571h36" stroke="#2D2D2D" stroke-linecap="round" stroke-linejoin="round"/><path fill="#2D2D2D" d="M7 12h8v8H7z"/><path d="M13 19a1 1 0 110-2 1 1 0 010 2zm-4-4a1 1 0 110-2 1 1 0 010 2zm4.5-2l.5.5L8.5 19l-.5-.5 5.5-5.5z" fill="#FFF" fill-rule="nonzero"/></g></svg>',
					'location' => array(),
					'template' => dirname( __FILE__ ) . '/block/render.php',
					'sections' => array(),
					'fields'   => array(
						array(
							'key'     => 'total_score_percentage',
							'label'   => esc_html__( 'Total Score', 'absolute-reviews' ),
							'section' => 'general',
							'type'    => 'number',
							'step'    => 1,
							'min'     => 1,
							'max'     => 100,
							'default' => 50,

						),
					),
				),
				'point-5'    => array(
					'name'     => esc_html__( 'Points (1-5)', 'absolute-reviews' ),
					'icon'     => '<svg width="52" height="44" xmlns="http://www.w3.org/2000/svg"><g transform="translate(1 1)" fill="none" fill-rule="evenodd"><rect stroke="#2D2D2D" stroke-width="1.5" width="50" height="42" rx="3"/><g stroke="#2D2D2D" stroke-linecap="round" stroke-linejoin="round"><path d="M7 25.5h27m-27-4h36m-36-4h31M7 29.571h36"/></g><path fill="#2D2D2D" d="M7 12h5v2H7zM13 12h5v2h-5zM19 12h5v2h-5zM25 12h5v2h-5z"/><path fill="#C7C7C7" d="M31 12h5v2h-5zM37 12h5v2h-5z"/></g></svg>',
					'location' => array(),
					'template' => dirname( __FILE__ ) . '/block/render.php',
					'sections' => array(),
					'fields'   => array(
						array(
							'key'     => 'total_score_point_5',
							'label'   => esc_html__( 'Total Score', 'absolute-reviews' ),
							'section' => 'general',
							'type'    => 'number',
							'step'    => 1,
							'min'     => 1,
							'max'     => 5,
							'default' => 1,

						),
					),
				),
				'point-10'   => array(
					'name'     => esc_html__( 'Points (1-10)', 'absolute-reviews' ),
					'icon'     => '<svg width="52" height="44" xmlns="http://www.w3.org/2000/svg"><g transform="translate(1 1)" fill="none" fill-rule="evenodd"><rect stroke="#2D2D2D" stroke-width="1.5" width="50" height="42" rx="3"/><g stroke="#2D2D2D" stroke-linecap="round" stroke-linejoin="round"><path d="M7 25.5h27m-27-4h36m-36-4h31M7 29.571h36"/></g><path fill="#2D2D2D" d="M7 12h5v2H7zM13 12h5v2h-5zM19 12h5v2h-5zM25 12h5v2h-5z"/><path fill="#C7C7C7" d="M31 12h5v2h-5zM37 12h5v2h-5z"/></g></svg>',
					'location' => array(),
					'template' => dirname( __FILE__ ) . '/block/render.php',
					'sections' => array(),
					'fields'   => array(
						array(
							'key'     => 'total_score_point_10',
							'label'   => esc_html__( 'Total Score', 'absolute-reviews' ),
							'section' => 'general',
							'type'    => 'number',
							'step'    => 1,
							'min'     => 1,
							'max'     => 10,
							'default' => 1,
						),
					),
				),
				'star'       => array(
					'name'     => esc_html__( 'Stars (1-5)', 'absolute-reviews' ),
					'icon'     => '<svg width="52" height="44" xmlns="http://www.w3.org/2000/svg"><g transform="translate(1 1)" fill="none" fill-rule="evenodd"><rect stroke="#2D2D2D" stroke-width="1.5" width="50" height="42" rx="3"/><path d="M19 19.429h18.286M19 16h24m-24-3.429h20.571M7 30.429h33.75M7 27h31.5M7 23.571h36" stroke="#2D2D2D" stroke-linecap="round" stroke-linejoin="round"/><path fill="#2D2D2D" d="M7 12h8v8H7z"/><path d="M11 13.143l.883 1.88 1.974.304-1.428 1.463.337 2.067L11 17.881l-1.766.976.337-2.067-1.428-1.463 1.974-.303z" fill="#FFF"/></g></svg>',
					'location' => array(),
					'template' => dirname( __FILE__ ) . '/block/render.php',
					'sections' => array(),
					'fields'   => array(
						array(
							'key'     => 'total_score_star',
							'label'   => esc_html__( 'Total Score', 'absolute-reviews' ),
							'section' => 'general',
							'type'    => 'number',
							'step'    => 1,
							'min'     => 1,
							'max'     => 5,
							'default' => 1,
						),
					),
				),
			),
			'fields'       => array(
				array(
					'key'     => 'heading',
					'label'   => esc_html__( 'Heading', 'absolute-reviews' ),
					'section' => 'general',
					'type'    => 'text',
					'default' => '',
				),
				array(
					'key'     => 'desc',
					'label'   => esc_html__( 'Description', 'absolute-reviews' ),
					'section' => 'general',
					'type'    => 'textarea',
					'default' => '',
				),
				array(
					'key'     => 'legend',
					'label'   => esc_html__( 'Legend', 'absolute-reviews' ),
					'section' => 'general',
					'type'    => 'textarea',
					'default' => '',
				),
				array(
					'key'     => 'total_score_label',
					'label'   => esc_html__( 'Total Score Label', 'absolute-reviews' ),
					'section' => 'general',
					'type'    => 'text',
					'default' => esc_html__( 'Total Score', 'absolute-reviews' ),
				),
				array(
					'key'     => 'schema_author_custom',
					'label'   => esc_html__( 'Custom Author', 'absolute-reviews' ),
					'section' => 'schema_attrs',
					'type'    => 'text',
					'default' => '',
				),
				// Typography.
				array(
					'key'             => 'typographyDescription',
					'label'           => esc_html__( 'Description Font Size', 'authentic' ),
					'section'         => 'typography',
					'type'            => 'dimension',
					'default'         => '0.875rem',
					'output'          => array(
						array(
							'element'  => '$ .abr-review-info .abr-review-description',
							'property' => 'font-size',
							'suffix'   => '!important',
						),
					),
					'active_callback' => array(
						array(
							'field'    => 'desc',
							'operator' => '!=',
							'value'    => '',
						),
					),
				),
				array(
					'key'             => 'typographyLegend',
					'label'           => esc_html__( 'Legend Font Size', 'authentic' ),
					'section'         => 'typography',
					'type'            => 'dimension',
					'default'         => '0.875rem',
					'output'          => array(
						array(
							'element'  => '$ .abr-review-total .abr-review-subtext .abr-data-info > span',
							'property' => 'font-size',
							'suffix'   => '!important',
						),
					),
					'active_callback' => array(
						array(
							'field'    => 'legend',
							'operator' => '!=',
							'value'    => '',
						),
					),
				),
				array(
					'key'     => 'typographyTotal',
					'label'   => esc_html__( 'Total Font Size', 'authentic' ),
					'section' => 'typography',
					'type'    => 'dimension',
					'default' => '3rem',
					'output'  => array(
						array(
							'element'  => '$ .abr-review-total .abr-review-score .abr-review-text',
							'property' => 'font-size',
							'suffix'   => '!important',
						),
					),
				),
			),
			'template'     => dirname( __FILE__ ) . '/block/render.php',
			'editor_style' => 'absolute-reviews-block-editor-style',
		);

		return $blocks;
	}
}

new ABR_Review_Block();
