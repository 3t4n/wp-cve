<?php
/**
 * Posts Block.
 *
 * @package Canvas
 */

/**
 * Initialize Posts block.
 */
class CNVS_Block_Posts {
	/**
	 * All grouped queries with used posts IDs.
	 *
	 * @var array
	 */
	public $query_groups = array();

	/**
	 * Initialize
	 */
	public function __construct() {
		require_once plugin_dir_path( __FILE__ ) . 'class-related-posts-snippet.php';

		add_action( 'init', array( $this, 'init' ) );
		add_filter( 'powerkit_share_buttons_locations', array( $this, 'share_buttons_location' ), 11 );
		add_filter( 'canvas_register_block_type', array( $this, 'register_block_type' ) );
		add_filter( 'canvas_block_template_variables_canvas/posts', array( $this, 'block_template_variables' ) );
		add_action( 'canvas_block_server_rendered_template_canvas/posts', array( $this, 'block_server_rendered_template' ) );
		add_action( 'pre_get_posts', array( $this, 'pre_get_posts' ), 1 );
		add_filter( 'found_posts', array( $this, 'found_posts' ), 1, 2 );
	}



	/**
	 * Enqueue the block's assets for the editor.
	 */
	public function init() {
		// Editor Scripts.
		wp_register_script(
			'canvas-block-posts-editor-script',
			plugins_url( 'block-posts/block.js', __FILE__ ),
			array( 'wp-blocks', 'wp-components', 'wp-element', 'wp-i18n', 'wp-editor', 'lodash', 'jquery' ),
			filemtime( plugin_dir_path( __FILE__ ) . 'block-posts/block.js' ),
			true
		);

		// Editor Styles.
		wp_register_style(
			'canvas-block-posts-editor-style',
			plugins_url( 'block-posts/block-posts-editor.css', __FILE__ ),
			array(),
			filemtime( plugin_dir_path( __FILE__ ) . 'block-posts/block-posts-editor.css' )
		);

		wp_style_add_data( 'canvas-block-posts-editor-style', 'rtl', 'replace' );

		// Styles.
		wp_register_style(
			'canvas-block-posts-style',
			plugins_url( 'block-posts/block-posts.css', __FILE__ ),
			array(),
			filemtime( plugin_dir_path( __FILE__ ) . 'block-posts/block-posts.css' )
		);

		wp_style_add_data( 'canvas-block-posts-style', 'rtl', 'replace' );

		// Scripts.
		wp_register_script( 'colcade', plugin_dir_url( __FILE__ ) . 'block-posts/colcade.js', array(), cnvs_get_setting( 'version' ), true );

		wp_register_script( 'canvas-block-posts-script', plugin_dir_url( __FILE__ ) . 'block-posts/public-block-posts.js', array( 'jquery', 'colcade' ), cnvs_get_setting( 'version' ), true );
	}

	/**
	 * Register block
	 *
	 * @param array $blocks all registered blocks.
	 * @return array
	 */
	public function register_block_type( $blocks ) {

		$image_sizes = cnvs_get_list_available_image_sizes();

		$button_fields = cnvs_get_gutenberg_button_fields(
			'button',
			'buttonSettings',
			array(
				array(
					'field'    => 'showViewPostButton',
					'operator' => '==',
					'value'    => true,
				),
			)
		);

		array_unshift(
			$button_fields, array(
				'key'             => 'buttonLabel',
				'label'           => esc_html__( 'Label', 'canvas' ),
				'section'         => 'buttonSettings',
				'type'            => 'text',
				'default'         => esc_html__( 'View Post', 'canvas' ),
				'active_callback' => array(
					array(
						'field'    => 'showViewPostButton',
						'operator' => '==',
						'value'    => true,
					),
				),
			)
		);

		$blocks[] = array(
			'name'          => 'canvas/posts',
			'title'         => esc_html__( 'Posts', 'canvas' ),
			'description'   => '',
			'category'      => 'canvas',
			'keywords'      => array(),
			'icon'          => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M11 7h6v2h-6zm0 4h6v2h-6zm0 4h6v2h-6zM7 7h2v2H7zm0 4h2v2H7zm0 4h2v2H7zM20.1 3H3.9c-.5 0-.9.4-.9.9v16.2c0 .4.4.9.9.9h16.2c.4 0 .9-.5.9-.9V3.9c0-.5-.5-.9-.9-.9zM19 19H5V5h14v14z" /></svg>',
			'supports'      => array(
				'className'        => true,
				'anchor'           => true,
				'html'             => false,
				'canvasSpacings'   => true,
				'canvasBorder'     => true,
				'canvasResponsive' => true,
			),
			'styles'        => array(),
			'location'      => array(),
			'sections'      => array(
				'general'        => array(
					'title'    => esc_html__( 'Block Settings', 'canvas' ),
					'priority' => 5,
					'open'     => true,
				),
				'meta'           => array(
					'title'    => esc_html__( 'Meta Settings', 'canvas' ),
					'priority' => 10,
				),
				'thumbnail'      => array(
					'title'    => esc_html__( 'Thumbnail Settings', 'canvas' ),
					'priority' => 20,
				),
				'query'          => array(
					'title'    => esc_html__( 'Query Settings', 'canvas' ),
					'priority' => 30,
				),
				'pagination'     => array(
					'title'    => esc_html__( 'Pagination Settings', 'canvas' ),
					'priority' => 40,
				),
				'buttonSettings' => array(
					'title'    => esc_html__( 'Button Settings', 'canvas' ),
					'priority' => 50,
				),
				'color'          => array(
					'title'    => esc_html__( 'Color Settings', 'canvas' ),
					'priority' => 60,
				),
			),
			'layouts'       => array(
				'grid'    => array(
					'name'     => esc_html__( 'Grid', 'canvas' ),
					'icon'     => '<svg width="52" height="44" xmlns="http://www.w3.org/2000/svg"><g transform="translate(1 1)" stroke="#2D2D2D" fill="none" fill-rule="evenodd"><rect stroke-width="1.5" width="50" height="42" rx="3"/><g transform="translate(27 5)"><rect stroke-width="1.5" width="18" height="9" rx="1"/><path d="M.5 11.5h17M.5 14h13" stroke-linecap="round" stroke-linejoin="round"/></g><g transform="translate(27 23)"><rect stroke-width="1.5" width="18" height="9" rx="1"/><path d="M.5 11.5h17M.5 14h13" stroke-linecap="round" stroke-linejoin="round"/></g><g transform="translate(5 5)"><rect stroke-width="1.5" width="18" height="9" rx="1"/><path d="M.5 11.5h17M.5 14h13" stroke-linecap="round" stroke-linejoin="round"/></g><g transform="translate(5 23)"><rect stroke-width="1.5" width="18" height="9" rx="1"/><path d="M.5 11.5h17M.5 14h13" stroke-linecap="round" stroke-linejoin="round"/></g></g></svg>',
					'location' => array( 'root', 'section-wide', 'section-content' ),
					'template' => dirname( __FILE__ ) . '/block-posts/layouts/grid.php',
					'sections' => array(),
					'fields'   => array(
						array(
							'key'     => 'columns',
							'label'   => esc_html__( 'Columns', 'canvas' ),
							'type'    => 'number',
							'step'    => 1,
							'min'     => 2,
							'max'     => 4,
							'default' => 2,
						),
					),
				),
				'masonry' => array(
					'name'     => esc_html__( 'Masonry', 'canvas' ),
					'icon'     => '<svg width="52" height="44" xmlns="http://www.w3.org/2000/svg"><g transform="translate(1 1)" stroke="#2D2D2D" fill="none" fill-rule="evenodd"><rect stroke-width="1.5" width="50" height="42" rx="3"/><g transform="translate(27 5)"><rect stroke-width="1.5" width="18" height="27" rx="1"/><path d="M.5 29.5h17M.5 32h13" stroke-linecap="round" stroke-linejoin="round"/></g><g transform="translate(5 5)"><rect stroke-width="1.5" width="18" height="9" rx="1"/><path d="M.5 11.5h17M.5 14h13" stroke-linecap="round" stroke-linejoin="round"/></g><g transform="translate(5 23)"><rect stroke-width="1.5" width="18" height="9" rx="1"/><path d="M.5 11.5h17M.5 14h13" stroke-linecap="round" stroke-linejoin="round"/></g></g></svg>',
					'location' => array( 'root', 'section-wide', 'section-content' ),
					'template' => dirname( __FILE__ ) . '/block-posts/layouts/masonry.php',
					'sections' => array(),
					'fields'   => array(
						array(
							'key'     => 'columns',
							'label'   => esc_html__( 'Columns', 'canvas' ),
							'type'    => 'number',
							'step'    => 1,
							'min'     => 2,
							'max'     => 4,
							'default' => 2,
						),
					),
				),
				'list'    => array(
					'name'     => esc_html__( 'List', 'canvas' ),
					'icon'     => '<svg width="52" height="44" xmlns="http://www.w3.org/2000/svg"><g transform="translate(1 1)" stroke="#2D2D2D" fill="none" fill-rule="evenodd"><rect stroke-width="1.5" width="50" height="42" rx="3"/><g transform="translate(5 23)"><rect stroke-width="1.5" width="18" height="14" rx="1"/><path d="M22.5 3.5h14m-14 3h17m-17 3h12" stroke-linecap="round" stroke-linejoin="round"/></g><g transform="translate(5 5)"><rect stroke-width="1.5" width="18" height="14" rx="1"/><path d="M22.5 3.5h14m-14 3h17m-17 3h12" stroke-linecap="round" stroke-linejoin="round"/></g></g></svg>',
					'location' => array( 'root', 'section-wide', 'section-content' ),
					'template' => dirname( __FILE__ ) . '/block-posts/layouts/list.php',
					'sections' => array(),
					'fields'   => array(),
				),
			),
			'fields'        => array_merge(
				array(
					// Query Settings.
					array(
						'key'     => 'queryGroup',
						'section' => 'query',
						'type'    => 'type-string',
						'default' => '',
					),
					array(
						'key'             => 'query',
						'section'         => 'query',
						'type'            => 'query',
						'default'         => array(
							'posts_type'         => 'post',
							'categories'         => '',
							'tags'               => '',
							'exclude_categories' => '',
							'exclude_tags'       => '',
							'formats'            => '',
							'posts'              => '',
							'offset'             => '',
							'orderby'            => 'date',
							'order'              => 'DESC',
							'time_frame'         => '',
							'taxonomy'           => '',
							'terms'              => '',
						),
						'active_callback' => array(
							array(
								'field'    => 'relatedPosts',
								'operator' => '==',
								'value'    => false,
							),
						),
					),
					array(
						'key'             => 'relatedPosts',
						'label'           => esc_html__( 'Display Related Posts', 'canvas' ),
						'help'            => esc_html__( 'Changes will be visible on frontend only. If enabled, posts will be filtered by the categories of the current post', 'canvas' ),
						'section'         => 'query',
						'type'            => 'toggle',
						'default'         => false,
						'active_callback' => array(
							array(
								'field'    => 'query.categories',
								'operator' => '===',
								'value'    => '',
							),
							array(
								'field'    => 'query.tags',
								'operator' => '===',
								'value'    => '',
							),
							array(
								'field'    => 'query.orderby',
								'operator' => '===',
								'value'    => 'date',
							),
							array(
								'field'    => 'query.order',
								'operator' => '===',
								'value'    => 'DESC',
							),
							array(
								'field'    => 'query.posts_type',
								'operator' => '===',
								'value'    => 'post',
							),
							array(
								'field'    => 'query.formats',
								'operator' => '===',
								'value'    => '',
							),
							array(
								'field'    => 'query.posts',
								'operator' => '===',
								'value'    => '',
							),
							array(
								'field'    => 'query.offset',
								'operator' => '===',
								'value'    => '',
							),
						),
					),
					array(
						'key'     => 'avoidDuplicatePosts',
						'label'   => esc_html__( 'Avoid Duplicate Posts', 'canvas' ),
						'help'    => esc_html__( 'Changes will be visible on frontend only.', 'canvas' ),
						'section' => 'query',
						'type'    => 'toggle',
						'default' => false,
					),
					// Pagination Settings.
					array(
						'key'             => 'showPagination',
						'label'           => esc_html__( 'Display Pagination', 'canvas' ),
						'section'         => 'pagination',
						'type'            => 'toggle',
						'default'         => false,
						'active_callback' => array(
							array(
								'field'    => 'relatedPosts',
								'operator' => '==',
								'value'    => false,
							),
							array(
								array(
									array(
										'field'    => 'query.categories',
										'operator' => '===',
										'value'    => '',
									),
									array(
										'field'    => 'query.tags',
										'operator' => '===',
										'value'    => '',
									),
									array(
										'field'    => 'query.orderby',
										'operator' => '===',
										'value'    => 'date',
									),
									array(
										'field'    => 'query.order',
										'operator' => '===',
										'value'    => 'DESC',
									),
									array(
										'field'    => 'query.posts_type',
										'operator' => '===',
										'value'    => 'post',
									),
									array(
										'field'    => 'query.formats',
										'operator' => '===',
										'value'    => '',
									),
									array(
										'field'    => 'query.posts',
										'operator' => '===',
										'value'    => '',
									),
									array(
										'field'    => 'query.offset',
										'operator' => '===',
										'value'    => '',
									),
									array(
										'field'    => 'avoidDuplicatePosts',
										'operator' => '===',
										'value'    => false,
									),
								),
								array(
									array(
										'field'    => 'query.categories',
										'operator' => '!==',
										'value'    => '',
									),
									array(
										'field'    => 'query.categories',
										'count'    => ',',
										'operator' => '==',
										'value'    => 0,
									),
									array(
										'field'    => 'query.tags',
										'operator' => '===',
										'value'    => '',
									),
									array(
										'field'    => 'query.orderby',
										'operator' => '===',
										'value'    => 'date',
									),
									array(
										'field'    => 'query.order',
										'operator' => '===',
										'value'    => 'DESC',
									),
									array(
										'field'    => 'query.posts_type',
										'operator' => '===',
										'value'    => 'post',
									),
									array(
										'field'    => 'query.formats',
										'operator' => '===',
										'value'    => '',
									),
									array(
										'field'    => 'query.posts',
										'operator' => '===',
										'value'    => '',
									),
									array(
										'field'    => 'query.offset',
										'operator' => '===',
										'value'    => '',
									),
									array(
										'field'    => 'avoidDuplicatePosts',
										'operator' => '===',
										'value'    => false,
									),
								),
								array(
									array(
										'field'    => 'query.tags',
										'count'    => ',',
										'operator' => '==',
										'value'    => 0,
									),
									array(
										'field'    => 'query.categories',
										'operator' => '===',
										'value'    => '',
									),
									array(
										'field'    => 'query.orderby',
										'operator' => '===',
										'value'    => 'date',
									),
									array(
										'field'    => 'query.order',
										'operator' => '===',
										'value'    => 'DESC',
									),
									array(
										'field'    => 'query.posts_type',
										'operator' => '===',
										'value'    => 'post',
									),
									array(
										'field'    => 'query.formats',
										'operator' => '===',
										'value'    => '',
									),
									array(
										'field'    => 'query.posts',
										'operator' => '===',
										'value'    => '',
									),
									array(
										'field'    => 'query.offset',
										'operator' => '===',
										'value'    => '',
									),
									array(
										'field'    => 'avoidDuplicatePosts',
										'operator' => '===',
										'value'    => false,
									),
								),
							),
						),
					),
					array(
						'key'             => 'postsCount',
						'label'           => esc_html__( 'Posts Count', 'canvas' ),
						'section'         => 'pagination',
						'type'            => 'number',
						'default'         => 10,
						'min'             => 1,
						'max'             => 100,
						'active_callback' => array(
							array(
								array(
									'field'    => 'showPagination',
									'operator' => '==',
									'value'    => false,
								),
								array(
									'field'    => 'query.orderby',
									'operator' => '!==',
									'value'    => 'date',
								),
								array(
									'field'    => 'query.order',
									'operator' => '!==',
									'value'    => 'DESC',
								),
								array(
									'field'    => 'query.posts_type',
									'operator' => '!==',
									'value'    => 'post',
								),
								array(
									'field'    => 'query.formats',
									'operator' => '!==',
									'value'    => '',
								),
								array(
									'field'    => 'query.posts',
									'operator' => '!==',
									'value'    => '',
								),
								array(
									'field'    => 'query.offset',
									'operator' => '!==',
									'value'    => '',
								),
								array(
									'field'    => 'avoidDuplicatePosts',
									'operator' => '!==',
									'value'    => false,
								),
								array(
									array(
										'field'    => 'query.categories',
										'operator' => '!==',
										'value'    => '',
									),
									array(
										'field'    => 'query.tags',
										'operator' => '!==',
										'value'    => '',
									),
								),
								array(
									array(
										'field'    => 'query.categories',
										'operator' => '!==',
										'value'    => '',
									),
									array(
										'field'    => 'query.categories',
										'count'    => ',',
										'operator' => '>=',
										'value'    => 1,
									),
								),
								array(
									array(
										'field'    => 'query.tags',
										'operator' => '!==',
										'value'    => '',
									),
									array(
										'field'    => 'query.tags',
										'count'    => ',',
										'operator' => '>=',
										'value'    => 1,
									),
								),
							),
						),
					),
					// Meta Settings.
					array(
						'key'     => 'showMetaCategory',
						'label'   => esc_html__( 'Category', 'canvas' ),
						'section' => 'meta',
						'type'    => 'toggle',
						'default' => true,
					),
					array(
						'key'     => 'showMetaAuthor',
						'label'   => esc_html__( 'Author', 'canvas' ),
						'section' => 'meta',
						'type'    => 'toggle',
						'default' => true,
					),
					array(
						'key'     => 'showMetaDate',
						'label'   => esc_html__( 'Date', 'canvas' ),
						'section' => 'meta',
						'type'    => 'toggle',
						'default' => true,
					),
					array(
						'key'     => 'showMetaComments',
						'label'   => esc_html__( 'Comments', 'canvas' ),
						'section' => 'meta',
						'type'    => 'toggle',
						'default' => false,
					),
					cnvs_post_views_enabled() ? array(
						'key'     => 'showMetaViews',
						'label'   => esc_html__( 'Views', 'canvas' ),
						'section' => 'meta',
						'type'    => 'toggle',
						'default' => false,
					) : array(),
					cnvs_powerkit_module_enabled( 'reading_time' ) ? array(
						'key'     => 'showMetaReadingTime',
						'label'   => esc_html__( 'Reading Time', 'canvas' ),
						'section' => 'meta',
						'type'    => 'toggle',
						'default' => false,
					) : array(),
					array(
						'key'     => 'showExcerpt',
						'label'   => esc_html__( 'Excerpt', 'canvas' ),
						'section' => 'meta',
						'type'    => 'toggle',
						'default' => true,
					),
					array(
						'key'     => 'showViewPostButton',
						'label'   => esc_html__( 'View Post Button', 'canvas' ),
						'section' => 'meta',
						'type'    => 'toggle',
						'default' => false,
					),
				),
				array(
					array(
						'key'     => 'imageSize',
						'label'   => esc_html__( 'Image Size', 'canvas' ),
						'section' => 'thumbnail',
						'type'    => 'select',
						'default' => 'large',
						'choices' => $image_sizes,
					),
				),
				$button_fields,
				array(
					// Color Settings.
					array(
						'key'     => 'colorHeading',
						'label'   => esc_html__( 'Heading', 'canvas' ),
						'section' => 'color',
						'type'    => 'color',
						'default' => '#000',
						'output'  => array(
							array(
								'element'  => '$.cnvs-block-posts .cnvs-block-posts-title a',
								'property' => 'color',
							),
						),
					),
					array(
						'key'     => 'colorHeadingHover',
						'label'   => esc_html__( 'Heading Hover', 'canvas' ),
						'section' => 'color',
						'type'    => 'color',
						'default' => '#5a5a5a',
						'output'  => array(
							array(
								'element'  => '$.cnvs-block-posts .cnvs-block-posts-title a:hover, $ .cnvs-block-posts .cnvs-block-posts-title a:focus',
								'property' => 'color',
							),
						),
					),
					array(
						'key'     => 'colorText',
						'label'   => esc_html__( 'Text', 'canvas' ),
						'section' => 'color',
						'type'    => 'color',
						'default' => '',
						'output'  => array(
							array(
								'element'  => '$',
								'property' => 'color',
							),
						),
					),
					array(
						'key'     => 'colorMetaLinks',
						'label'   => esc_html__( 'Post Meta Links', 'canvas' ),
						'section' => 'color',
						'type'    => 'color',
						'default' => '',
						'output'  => array(
							array(
								'element'  => '$.cnvs-block-posts .cnvs-post-meta a, $.cnvs-block-posts .post-categories a',
								'property' => 'color',
							),
						),
					),
					array(
						'key'     => 'colorMetaLinksHover',
						'label'   => esc_html__( 'Post Meta Links Hover', 'canvas' ),
						'section' => 'color',
						'type'    => 'color',
						'default' => '',
						'output'  => array(
							array(
								'element'  => '$.cnvs-block-posts .cnvs-post-meta a:hover, $.cnvs-block-posts .cnvs-post-meta a:focus, $.cnvs-block-posts .post-categories a:hover, $.cnvs-block-posts .post-categories a:focus',
								'property' => 'color',
							),
						),
					),
					array(
						'key'     => 'colorMeta',
						'label'   => esc_html__( 'Post Meta', 'canvas' ),
						'section' => 'color',
						'type'    => 'color',
						'default' => '',
						'output'  => array(
							array(
								'element'  => '$.cnvs-block-posts .cnvs-post-meta span, $.cnvs-block-posts .post-categories span',
								'property' => 'color',
							),
						),
					),
				)
			),
			'template'      => dirname( __FILE__ ) . '/block-posts/layouts/list.php',
			'fallback'      => array(
				'layout' => 'list',
			),
			// enqueue registered scripts/styles.
			'style'         => is_admin() ? '' : 'canvas-block-posts-style',
			'script'        => is_admin() ? '' : 'canvas-block-posts-script',
			'editor_style'  => 'canvas-block-posts-editor-style',
			'editor_script' => 'canvas-block-posts-editor-script',
		);

		return $blocks;
	}

	/**
	 * Add $posts template variable.
	 *
	 * @param array $variables Template variables.
	 * @return array
	 */
	public function block_template_variables( $variables ) {
		$variables['posts'] = $this->get_post_query( $variables['attributes'], $variables['options'] );

		return $variables;
	}

	/**
	 * Reset WP Query data.
	 */
	public function block_server_rendered_template() {
		wp_reset_postdata();
	}

	/**
	 * Prepare post query by block attributes
	 *
	 * @param array $attributes Block attributes.
	 * @param array $options    Layout specific attributes.
	 * @return array
	 */
	public function get_post_query( $attributes, $options ) {
		global $cnvs_posts;
		global $wp_query;

		if ( ! $cnvs_posts ) {
			$cnvs_posts = array();
		}

		$query = isset( $attributes['query'] ) ? $attributes['query'] : array();

		$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

		$args = array(
			'ignore_sticky_posts' => true,
			'is_post_query'       => true,
			'orderby'             => 'date',
			'order'               => 'DESC',
			'paged'               => $paged,
		);

		$args['posts_per_page'] = get_option( 'posts_per_page' );

		// Filter by post type.
		if ( isset( $query['posts_type'] ) && $query['posts_type'] ) {
			$args['post_type'] = $query['posts_type'];
		}

		// Filter by posts.
		if ( isset( $query['posts'] ) && $query['posts'] ) {
			$args['post__in'] = explode( ',', $query['posts'] );
		}

		// Filter by categories.
		if ( isset( $query['posts_type'] ) && 'post' === $query['posts_type'] ) {

			if ( isset( $query['categories'] ) && $query['categories'] ) {
				$args['tax_query'] = isset( $args['tax_query'] ) && is_array( $args['tax_query'] ) ? $args['tax_query'] : array();

				$args['tax_query'][] = array(
					'taxonomy' => 'category',
					'field'    => 'slug',
					'terms'    => array_map( 'trim', explode( ',', $query['categories'] ) ),
				);
			}
		}

		// Filter by tags.
		if ( isset( $query['tags'] ) && $query['tags'] ) {
			$args['tax_query'] = isset( $args['tax_query'] ) && is_array( $args['tax_query'] ) ? $args['tax_query'] : array();

			$args['tax_query'][] = array(
				'taxonomy' => 'post_tag',
				'field'    => 'slug',
				'terms'    => array_map( 'trim', explode( ',', $query['tags'] ) ),
			);
		}

		// Exclude Categories.
		if ( isset( $query['posts_type'] ) && 'post' === $query['posts_type'] ) {

			if ( isset( $query['exclude_categories'] ) && $query['exclude_categories'] ) {
				$args['tax_query'] = isset( $args['tax_query'] ) && is_array( $args['tax_query'] ) ? $args['tax_query'] : array();

				$args['tax_query'][] = array(
					'taxonomy' => 'category',
					'field'    => 'slug',
					'operator' => 'NOT IN',
					'terms'    => array_map( 'trim', explode( ',', $query['exclude_categories'] ) ),
				);
			}
		}

		// Exclude tags.
		if ( isset( $query['exclude_tags'] ) && $query['exclude_tags'] ) {
			$args['tax_query'] = isset( $args['tax_query'] ) && is_array( $args['tax_query'] ) ? $args['tax_query'] : array();

			$args['tax_query'][] = array(
				'taxonomy' => 'post_tag',
				'field'    => 'slug',
				'operator' => 'NOT IN',
				'terms'    => array_map( 'trim', explode( ',', $query['exclude_tags'] ) ),
			);
		}

		// Taxonomy and term.
		if ( isset( $query['taxonomy'] ) && $query['taxonomy'] && isset( $query['terms'] ) && $query['terms'] ) {

			$args['tax_query'] = isset( $args['tax_query'] ) && is_array( $args['tax_query'] ) ? $args['tax_query'] : array();

			$args['tax_query'][] = array(
				'taxonomy' => $query['taxonomy'],
				'field'    => 'slug',
				'terms'    => array_map( 'trim', explode( ',', $query['terms'] ) ),
			);
		}

		// Filter by post formats.
		if ( current_theme_supports( 'post-formats' ) && isset( $query['formats'] ) && $query['formats'] ) {
			$post_formats = get_theme_support( 'post-formats' );
			$post_formats = is_array( $post_formats[0] ) ? $post_formats[0] : false;

			if ( $post_formats ) {
				$selected_formats = explode( ',', $query['formats'] );

				// first array used to include formats, the second to exclude
				// we need this, because when select `standard` post format, nothing loaded.
				$result_formats = array( array(), array() );

				foreach ( $post_formats as $format ) {
					$idx                      = in_array( $format, $selected_formats, true ) ? 0 : 1;
					$result_formats[ $idx ][] = 'post-format-' . $format;
				}

				$args['tax_query'] = isset( $args['tax_query'] ) && is_array( $args['tax_query'] ) ? $args['tax_query'] : array();

				// include formats only if Standard format is not selected.
				if ( ! empty( $result_formats[0] ) && ! in_array( 'standard', $selected_formats, true ) ) {
					$args['tax_query'][] = array(
						'taxonomy' => 'post_format',
						'field'    => 'slug',
						'terms'    => $result_formats[0],
					);
				}

				// exclude formats.
				if ( ! empty( $result_formats[1] ) ) {
					$args['tax_query'][] = array(
						'taxonomy' => 'post_format',
						'field'    => 'slug',
						'operator' => 'NOT IN',
						'terms'    => $result_formats[1],
					);
				}
			}
		}

		// Add offset.
		if ( isset( $query['offset'] ) && $query['offset'] ) {
			$args['offset'] = $query['offset'];
		}

		if ( isset( $query['orderby'] ) ) {
			$type_post_views = cnvs_post_views_enabled();
			// Order by Views.
			if ( $type_post_views && 'views' === $query['orderby'] ) {
				$args['orderby'] = $type_post_views;
				// Don't hide posts without views.
				$args['views_query']['hide_empty'] = false;

				// Time Frame.
				if ( isset( $query['time_frame'] ) && $query['time_frame'] ) {
					$args['date_query'] = array(
						array(
							'column' => 'post_date_gmt',
							'after'  => $query['time_frame'] . ' ago',
						),
					);
				}
			} else {
				$args['orderby'] = $query['orderby'];
			}
		}

		if ( isset( $query['order'] ) && $query['order'] ) {
			$args['order'] = $query['order'];
		}

		// Posts count.
		if ( isset( $attributes['postsCount'] ) && $attributes['postsCount'] ) {
			$args['posts_per_page'] = $attributes['postsCount'];
		}

		// Filter.
		$args = apply_filters( 'canvas_block_posts_query_args', $args, $attributes, $options );

		// Avoid Duplicate.
		if ( isset( $attributes['avoidDuplicatePosts'] ) && $attributes['avoidDuplicatePosts'] ) {

			$main_posts = array();

			if ( isset( $wp_query->posts ) && $wp_query->posts ) {
				$main_posts = wp_list_pluck( $wp_query->posts, 'ID' );
			}

			if ( $main_posts ) {
				$args['post__not_in'] = array_merge( $main_posts, $cnvs_posts );
			} else {
				$args['post__not_in'] = $cnvs_posts;
			}
		}

		// Display related posts.
		if ( isset( $attributes['relatedPosts'] ) && $attributes['relatedPosts'] ) {

			$exclude_posts = array();

			if ( $attributes['avoidDuplicatePosts'] ) {
				$exclude_posts = $args['post__not_in'];
			}

			$related_args = array(
				'ids'           => null,
				'offset'        => 0,
				'category'      => null,
				'tag'           => null,
				'time_frame'    => null,
				'orderby'       => 'ID',
				'order'         => 'DESC',
				'count'         => isset( $args['posts_per_page'] ) ? $args['posts_per_page'] : 1,
				'exclude_posts' => $exclude_posts,
			);

			$related_posts = cnvs_get_related_posts( $related_args );

			if ( $related_posts ) {
				$args['post__not_in'] = array();

				$args['post__in'] = wp_list_pluck( $related_posts, 'ID' );
			}
		}

		// Grouped posts.
		if ( isset( $attributes['queryGroup'] ) && $attributes['queryGroup'] ) {
			$group_ids = isset( $this->query_groups[ $attributes['queryGroup'] ] ) ? (array) $this->query_groups[ $attributes['queryGroup'] ] : array();

			// Exclude grouped posts.
			if ( ! empty( $group_ids ) ) {
				$args['post__not_in'] = array_merge(
					isset( $args['post__not_in'] ) ? (array) $args['post__not_in'] : array(),
					$group_ids
				);

				// Remove excluded posts from `post__in`.
				if ( isset( $args['post__in'] ) ) {
					$args['post__in'] = array_diff( $args['post__in'], $group_ids );
				}
			}
		}

		// Set post status for attachment posts.
		if ( isset( $query['posts_type'] ) && 'attachment' === $args['post_type'] ) {
			$args['post_status'] = 'inherit';
		}

		$query = new WP_Query( $args );

		if ( isset( $args['min_limit'] ) ) {
			if ( $query->post_count < $args['min_limit'] ) {
				return new WP_Query();
			}
		}

		// Save posts ids from the current query in group.
		if ( isset( $attributes['queryGroup'] ) && $attributes['queryGroup'] ) {
			$group_ids = isset( $this->query_groups[ $attributes['queryGroup'] ] ) ? (array) $this->query_groups[ $attributes['queryGroup'] ] : array();
			$new_group_ids = array();

			if ( isset( $query->posts ) ) {
				foreach ( $query->posts as $post ) {
					$new_group_ids[] = $post->ID;
				}
			}

			$new_group_ids = array_unique( $new_group_ids );

			$this->query_groups[ $attributes['queryGroup'] ] = array_merge(
				$group_ids,
				$new_group_ids
			);
		}

		// Save global IDs.
		if ( isset( $query->posts ) ) {
			foreach ( $query->posts as $post ) {
				$cnvs_posts[] = $post->ID;
			}
		}

		$cnvs_posts = array_unique( $cnvs_posts );

		return $query;
	}

	/**
	 * Fires after the query variable object is created, but before the actual query is run.
	 *
	 * @param object $wp_query WP Query.
	 */
	public function pre_get_posts( &$wp_query ) {

		if ( isset( $wp_query->query['is_post_query'] ) ) {
			$offset         = (int) $wp_query->get( 'offset' );
			$paged          = (int) $wp_query->get( 'paged' );
			$posts_per_page = (int) $wp_query->get( 'posts_per_page' );

			if ( $wp_query->is_paged ) {
				$page_offset = $offset + ( ( $paged - 1 ) * $posts_per_page );

				$wp_query->set( 'offset', $page_offset );
			} else {
				$wp_query->set( 'offset', $offset );
			}
		}
	}

	/**
	 * Filters the number of found posts for the query.
	 *
	 * @param int    $found_posts The number of posts found.
	 * @param object $wp_query     WP Query.
	 */
	public function found_posts( $found_posts, $wp_query ) {

		if ( isset( $wp_query->query['is_post_query'] ) ) {

			$offset = isset( $wp_query->query['offset'] ) ? $wp_query->query['offset'] : 0;

			$found_posts = (int) $found_posts - (int) $offset;
		}

		return $found_posts;
	}

	/**
	 * Filter Register Locations
	 *
	 * @param array $locations List of Locations.
	 */
	public function share_buttons_location( $locations = array() ) {
		$locations['block-posts'] = array(
			'shares'         => array( 'facebook', 'twitter', 'pinterest' ),
			'name'           => esc_html__( 'Block Posts', 'canvas' ),
			'location'       => 'block-posts',
			'mode'           => 'cached',
			'before'         => '',
			'after'          => '',
			'display'        => true,
			'meta'           => array(
				'icons'  => true,
				'titles' => false,
				'labels' => false,
			),
			// Display only the specified layouts and color schemes.
			'fields'         => array(
				'display_count'   => true,
				'layouts'         => array( 'simple' ),
				'schemes'         => array( 'simple-light', 'bold-light' ),
				'count_locations' => array( 'inside' ),
			),
			'display_total'  => false,
			'display_count'  => false,
			'layout'         => 'simple-light',
			'scheme'         => 'default',
			'count_location' => 'inside',
		);

		return $locations;
	}
}

new CNVS_Block_Posts();
