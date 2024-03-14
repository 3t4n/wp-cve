<?php
/**
 * Block Reviews Posts
 *
 * @link       https://codesupply.co
 * @since      1.0.0
 *
 * @package    ABR
 * @subpackage ABR/public
 */

/**
 * Block Reviews Posts
 */
class ABR_Reviews_Posts_Block {

	/**
	 * Initialize
	 */
	public function __construct() {
		add_filter( 'canvas_block_layouts_canvas/posts', array( $this, 'register_layout' ), 99 );
		add_filter( 'canvas_block_posts_query_args', array( $this, 'change_query_args' ), 10, 3 );
	}

	/**
	 * Get fields array for Meta
	 *
	 * @param array $settings The settings.
	 */
	public function get_meta_fields( $settings ) {

		$settings = array_merge( array(
			'field_prefix'    => 'reviews',
			'section_name'    => '',
			'active_callback' => array(),
		), $settings );

		// Set fields.
		$fields = array(
			array(
				'key'             => $settings['field_prefix'] . '_showMetaCategory',
				'label'           => esc_html__( 'Category', 'authentic' ),
				'section'         => $settings['section_name'],
				'type'            => 'toggle',
				'default'         => true,
				'active_callback' => $settings['active_callback'],
			),
			array(
				'key'             => $settings['field_prefix'] . '_showMetaAuthor',
				'label'           => esc_html__( 'Author', 'authentic' ),
				'section'         => $settings['section_name'],
				'type'            => 'toggle',
				'default'         => true,
				'active_callback' => $settings['active_callback'],
			),
			array(
				'key'             => $settings['field_prefix'] . '_showMetaDate',
				'label'           => esc_html__( 'Date', 'authentic' ),
				'section'         => $settings['section_name'],
				'type'            => 'toggle',
				'default'         => true,
				'active_callback' => $settings['active_callback'],
			),
			array(
				'key'             => $settings['field_prefix'] . '_showMetaComments',
				'label'           => esc_html__( 'Comments', 'authentic' ),
				'section'         => $settings['section_name'],
				'type'            => 'toggle',
				'default'         => false,
				'active_callback' => $settings['active_callback'],
			),
			abr_post_views_enabled() ? array(
				'key'             => $settings['field_prefix'] . '_showMetaViews',
				'label'           => esc_html__( 'Views', 'authentic' ),
				'section'         => $settings['section_name'],
				'type'            => 'toggle',
				'default'         => false,
				'active_callback' => $settings['active_callback'],
			) : array(),
			abr_powerkit_module_enabled( 'reading_time' ) ? array(
				'key'             => $settings['field_prefix'] . '_showMetaReadingTime',
				'label'           => esc_html__( 'Reading Time', 'authentic' ),
				'section'         => $settings['section_name'],
				'type'            => 'toggle',
				'default'         => false,
				'active_callback' => $settings['active_callback'],
			) : array(),
			array(
				'key'             => uniqid(),
				'section'         => $settings['section_name'],
				'type'            => 'separator',
				'active_callback' => $settings['active_callback'],
			),
			array(
				'key'             => $settings['field_prefix'] . 'MetaCompact',
				'label'           => esc_html__( 'Display compact post meta', 'authentic' ),
				'section'         => $settings['section_name'],
				'type'            => 'toggle',
				'default'         => false,
				'active_callback' => $settings['active_callback'],
			),
		);

		return $fields;
	}

	/**
	 * Get types of layout.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	public function get_types_of_layouts() {
		$types = array(
			'reviews-1' => 'reviews-1',
			'reviews-2' => 'reviews-2',
			'reviews-3' => 'reviews-3',
			'reviews-4' => 'reviews-4',
			'reviews-5' => 'reviews-5',
			'reviews-6' => 'reviews-6',
			'reviews-7' => 'reviews-7',
			'reviews-8' => 'reviews-8',
		);

		return $types;
	}

	/**
	 * Get name of layout by key.
	 *
	 * @param mixed $key The key.
	 */
	public function get_name_of_layout_by( $key ) {

		switch ( $key ) {
			case 'reviews-1':
				return esc_html__( 'Reviews 1', 'absolute-reviews' );
			case 'reviews-2':
				return esc_html__( 'Reviews 2', 'absolute-reviews' );
			case 'reviews-3':
				return esc_html__( 'Reviews 3', 'absolute-reviews' );
			case 'reviews-4':
				return esc_html__( 'Reviews 4', 'absolute-reviews' );
			case 'reviews-5':
				return esc_html__( 'Reviews 5', 'absolute-reviews' );
			case 'reviews-6':
				return esc_html__( 'Reviews 6', 'absolute-reviews' );
			case 'reviews-7':
				return esc_html__( 'Reviews 7', 'absolute-reviews' );
			case 'reviews-8':
				return esc_html__( 'Reviews 8', 'absolute-reviews' );
		}
	}

	/**
	 * Get icon of layout by key.
	 *
	 * @param mixed $key The key.
	 */
	public function get_icon_of_layout_by( $key ) {

		switch ( $key ) {
			case 'reviews-1':
				return '<svg width="52" height="44" xmlns="http://www.w3.org/2000/svg"><g transform="translate(1 1)" stroke="#2D2D2D" fill="none" fill-rule="evenodd"><rect stroke-width="1.5" width="50" height="42" rx="3"/><g transform="translate(10 6)"><rect stroke-width="1.5" width="14" height="12" rx="1"/><path d="M18 4.625h12.278M18 7.625h8.273" stroke-linecap="round" stroke-linejoin="round"/></g><g transform="translate(10 24)"><rect stroke-width="1.5" width="14" height="12" rx="1"/><path d="M18 4.625h12.278M18 7.625h8.273" stroke-linecap="round" stroke-linejoin="round"/></g></g></svg>';
			case 'reviews-2':
				return '<svg width="52" height="44" xmlns="http://www.w3.org/2000/svg"><g transform="translate(1 1)" fill="none" fill-rule="evenodd"><rect stroke="#2D2D2D" stroke-width="1.5" width="50" height="42" rx="3"/><g transform="translate(10 24)"><rect stroke="#2D2D2D" stroke-width="1.5" width="14" height="12" rx="1"/><g transform="translate(18 9)"><rect fill="#C7C7C7" width="13" height="2" rx="1"/><rect fill="#2D2D2D" width="10" height="2" rx="1"/></g><path d="M18 2h12.278M18 5h8.273" stroke="#2D2D2D" stroke-linecap="round" stroke-linejoin="round"/></g><g transform="translate(10 6)"><rect stroke="#2D2D2D" stroke-width="1.5" width="14" height="12" rx="1"/><g transform="translate(18 9)"><rect fill="#C7C7C7" width="13" height="2" rx="1"/><rect fill="#2D2D2D" width="10" height="2" rx="1"/></g><path d="M18 2h12.278M18 5h8.273" stroke="#2D2D2D" stroke-linecap="round" stroke-linejoin="round"/></g></g></svg>';
			case 'reviews-3':
				return '<svg width="52" height="44" xmlns="http://www.w3.org/2000/svg"><g transform="translate(1 1)" stroke="#2D2D2D" fill="none" fill-rule="evenodd"><rect stroke-width="1.5" width="50" height="42" rx="3"/><rect x=".5" y=".5" width="24" height="16" rx="1" transform="translate(12 4)" stroke-width="1.5"/><g transform="translate(13 30)"><rect stroke-width="1.5" width="8" height="8" rx="1"/><path d="M11.361 2.5H23.64M11 5.5h8.667" stroke-linecap="round" stroke-linejoin="round"/></g><path d="M13.028 26.5H28.68m-15.652-3h22.666" stroke-linecap="round" stroke-linejoin="round"/></g></svg>';
			case 'reviews-4':
				return '<svg width="52" height="44" xmlns="http://www.w3.org/2000/svg"><g transform="translate(1 1)" fill="none" fill-rule="evenodd"><rect stroke="#2D2D2D" stroke-width="1.5" width="50" height="42" rx="3"/><rect x=".5" y=".5" width="24" height="16" rx="1" transform="translate(12 4)" stroke="#2D2D2D" stroke-width="1.5"/><rect stroke="#2D2D2D" stroke-width="1.5" x="13" y="30" width="8" height="8" rx="1"/><path d="M24.361 35.5h8.667m-8.667-3H36.64" stroke="#2D2D2D" stroke-linecap="round" stroke-linejoin="round"/><g transform="translate(12 24)"><rect fill="#C7C7C7" width="25" height="2" rx="1"/><rect fill="#2D2D2D" width="19" height="2" rx="1"/></g></g></svg>';
			case 'reviews-5':
				return '<svg width="52" height="44" xmlns="http://www.w3.org/2000/svg"><g transform="translate(1 1)" fill="none" fill-rule="evenodd"><rect stroke="#2D2D2D" stroke-width="1.5" width="50" height="42" rx="3"/><rect x=".5" y=".5" width="24" height="16" rx="1" transform="translate(12 4)" stroke="#2D2D2D" stroke-width="1.5"/><g transform="translate(13 30)" stroke="#2D2D2D"><rect stroke-width="1.5" width="8" height="8" rx="1"/><path d="M11.361 2.5H23.64M11 5.5h8.667" stroke-linecap="round" stroke-linejoin="round"/></g><path d="M12.722 26.5h10.212m-10.212-3H25" stroke="#2D2D2D" stroke-linecap="round" stroke-linejoin="round"/><text font-family="FuturaPT-Bold, Futura PT" font-size="5" font-weight="bold" fill="#2D2D2D"><tspan x="27" y="28">3/5</tspan></text></g></svg>';
			case 'reviews-6':
				return '<svg width="52" height="44" xmlns="http://www.w3.org/2000/svg"><g transform="translate(1 1)" fill="none" fill-rule="evenodd"><rect stroke="#2D2D2D" stroke-width="1.5" width="50" height="42" rx="3"/><g transform="translate(12 4)"><rect stroke="#2D2D2D" stroke-width="1.5" x=".5" y=".5" width="24" height="33" rx="1"/><g transform="translate(3 3)"><circle fill="#2D2D2D" cx="3" cy="3" r="3"/><path d="M4.333 5a.667.667 0 110-1.333.667.667 0 010 1.333zM1.667 2.333a.667.667 0 110-1.333.667.667 0 010 1.333zm3-1.333L5 1.333 1.333 5 1 4.667 4.666 1z" fill="#FFF" fill-rule="nonzero"/></g><path d="M3.528 26.5h17.944M3.44 29h11.416" stroke="#2D2D2D" stroke-linecap="round" stroke-linejoin="round"/></g></g></svg>';
			case 'reviews-7':
				return '<svg width="52" height="44" xmlns="http://www.w3.org/2000/svg"><g transform="translate(1 1)" fill="none" fill-rule="evenodd"><rect stroke="#2D2D2D" stroke-width="1.5" width="50" height="42" rx="3"/><g transform="translate(12 4)"><rect stroke="#2D2D2D" stroke-width="1.5" x=".5" y=".5" width="24" height="33" rx="1"/><path d="M3.57 29.758l1.172-1.173.849.848 1.371-1.37.538.537v-1.5H6l.538.538-.947.947-.849-.849L3.26 29.22a3 3 0 11.31.538z" fill="#2D2D2D" fill-rule="nonzero"/><path d="M3.528 19.5h17.944M3.44 22h11.416" stroke="#2D2D2D" stroke-linecap="round" stroke-linejoin="round"/></g></g></svg>';
			case 'reviews-8':
				return '<svg width="52" height="44" xmlns="http://www.w3.org/2000/svg"><g transform="translate(1 1)" fill="none" fill-rule="evenodd"><rect stroke="#2D2D2D" stroke-width="1.5" width="50" height="42" rx="3"/><g transform="translate(12 4)"><rect stroke="#2D2D2D" stroke-width="1.5" x=".5" y=".5" width="24" height="33" rx="1"/><path d="M3.57 7.758l1.172-1.173.849.848 1.371-1.37.538.537V5.1H6l.538.538-.947.947-.849-.849L3.26 7.22a3 3 0 11.31.538z" fill="#2D2D2D" fill-rule="nonzero"/><path d="M3.528 27.5h17.944M3.44 30h11.416" stroke="#2D2D2D" stroke-linecap="round" stroke-linejoin="round"/></g></g></svg>';
		}
	}

	/**
	 * Register layout.
	 *
	 * @param array $layouts List of layouts.
	 */
	public function register_layout( $layouts = array() ) {

		$image_sizes = abr_get_list_available_image_sizes();

		$types = $this->get_types_of_layouts();

		foreach ( $types as $type ) {

			$layouts[ $type ] = array(
				'location'    => array(),
				'name'        => $this->get_name_of_layout_by( $type ),
				'template'    => dirname( __FILE__ ) . '/posts-block.php',
				'icon'        => $this->get_icon_of_layout_by( $type ),
				'sections'    => array(
					'general'           => array(
						'title'    => esc_html__( 'Block Settings', 'absolute-reviews' ),
						'priority' => 5,
						'open'     => true,
					),
					'reviewsLargeMeta'  => array(
						'title'    => esc_html__( 'Large Post Meta Settings', 'absolute-reviews' ),
						'priority' => 10,
					),
					'reviewsSmallMeta'  => array(
						'title'    => esc_html__( 'Small Post Meta Settings', 'absolute-reviews' ),
						'priority' => 20,
					),
					'reviewsThumbnail'  => array(
						'title'    => esc_html__( 'Thumbnail Settings', 'absolute-reviews' ),
						'priority' => 30,
					),
					'reviewsTypography' => array(
						'title'    => esc_html__( 'Typography Settings', 'absolute-reviews' ),
						'priority' => 40,
					),
				),
				'hide_fields' => array(
					'showMetaCategory',
					'showMetaAuthor',
					'showMetaDate',
					'showMetaComments',
					'showMetaViews',
					'showMetaReadingTime',
					'showMetaShares',
					'showExcerpt',
					'showViewPostButton',
					'showPagination',
					'imageSize',
					'postsCount',
					'colorText',
					'colorHeading',
					'colorHeadingHover',
					'colorText',
					'colorMeta',
					'colorMetaHover',
					'colorMetaLinks',
					'colorMetaLinksHover',
				),
				'fields'      => array_merge(
					array(
						array(
							'key'      => 'reviewType',
							'label'    => esc_html__( 'Filter by Review Type', 'absolute-reviews' ),
							'section'  => 'query',
							'type'     => 'select',
							'multiple' => false,
							'choices'  => array(
								'all'        => esc_html__( 'All types', 'absolute-reviews' ),
								'percentage' => esc_html__( 'Percentage (1-100%)', 'absolute-reviews' ),
								'point-5'    => esc_html__( 'Points (1-5)', 'absolute-reviews' ),
								'point-10'   => esc_html__( 'Points (1-10)', 'absolute-reviews' ),
								'star'       => esc_html__( 'Stars (1-5)', 'absolute-reviews' ),
							),
							'default'  => 'all',
						),
						array(
							'key'     => 'reviewsPostsCount',
							'label'   => esc_html__( 'Posts Count', 'absolute-reviews' ),
							'section' => 'general',
							'type'    => 'number',
							'default' => 5,
							'min'     => 1,
							'max'     => 100,
						),
						array(
							'key'             => 'imageSize',
							'label'           => esc_html__( 'Image Size', 'absolute-reviews' ),
							'section'         => 'reviewsThumbnail',
							'type'            => 'select',
							'default'         => 'large',
							'choices'         => $image_sizes,
							'active_callback' => array(
								array(
									'field'    => 'layout',
									'operator' => '!=',
									'value'    => 'reviews-3',
								),
								array(
									'field'    => 'layout',
									'operator' => '!=',
									'value'    => 'reviews-4',
								),
								array(
									'field'    => 'layout',
									'operator' => '!=',
									'value'    => 'reviews-5',
								),
							),
						),
						array(
							'key'             => 'largeImageSize',
							'label'           => esc_html__( 'Large Post Image Size', 'absolute-reviews' ),
							'section'         => 'reviewsThumbnail',
							'type'            => 'select',
							'default'         => 'large',
							'choices'         => $image_sizes,
							'active_callback' => array(
								array(
									array(
										'field'    => 'layout',
										'operator' => '==',
										'value'    => 'reviews-3',
									),
									array(
										'field'    => 'layout',
										'operator' => '==',
										'value'    => 'reviews-4',
									),
									array(
										'field'    => 'layout',
										'operator' => '==',
										'value'    => 'reviews-5',
									),
								),
							),
						),
						array(
							'key'             => 'smallImageSize',
							'label'           => esc_html__( 'Small Post Image Size', 'absolute-reviews' ),
							'section'         => 'reviewsThumbnail',
							'type'            => 'select',
							'default'         => 'large',
							'choices'         => $image_sizes,
							'active_callback' => array(
								array(
									array(
										'field'    => 'layout',
										'operator' => '==',
										'value'    => 'reviews-3',
									),
									array(
										'field'    => 'layout',
										'operator' => '==',
										'value'    => 'reviews-4',
									),
									array(
										'field'    => 'layout',
										'operator' => '==',
										'value'    => 'reviews-5',
									),
								),
							),
						),
						// Typography.
						array(
							'key'             => 'typographyHeading',
							'label'           => esc_html__( 'Heading Font Size', 'absolute-reviews' ),
							'section'         => 'reviewsTypography',
							'type'            => 'dimension',
							'default'         => '1rem',
							'output'          => array(
								array(
									'element'  => '$ .entry-title a',
									'property' => 'font-size',
									'suffix'   => '!important',
								),
							),
							'active_callback' => array(
								array(
									'field'    => 'layout',
									'operator' => '!=',
									'value'    => 'reviews-3',
								),
								array(
									'field'    => 'layout',
									'operator' => '!=',
									'value'    => 'reviews-4',
								),
								array(
									'field'    => 'layout',
									'operator' => '!=',
									'value'    => 'reviews-5',
								),
							),
						),
						array(
							'key'             => 'typographyLargeHeading',
							'label'           => esc_html__( 'Heading Post Large Font Size', 'absolute-reviews' ),
							'section'         => 'reviewsTypography',
							'type'            => 'dimension',
							'default'         => '1.5rem',
							'output'          => array(
								array(
									'element'  => '$ .abr-post-item:first-child .entry-title a',
									'property' => 'font-size',
									'suffix'   => '!important',
								),
							),
							'active_callback' => array(
								array(
									array(
										'field'    => 'layout',
										'operator' => '==',
										'value'    => 'reviews-3',
									),
									array(
										'field'    => 'layout',
										'operator' => '==',
										'value'    => 'reviews-4',
									),
									array(
										'field'    => 'layout',
										'operator' => '==',
										'value'    => 'reviews-5',
									),
								),
							),
						),
						array(
							'key'             => 'typographySmallHeading',
							'label'           => esc_html__( 'Heading Post Small Font Size', 'absolute-reviews' ),
							'section'         => 'reviewsTypography',
							'type'            => 'dimension',
							'default'         => '1rem',
							'output'          => array(
								array(
									'element'  => '$ .abr-post-item:nth-child(n+2) .entry-title a',
									'property' => 'font-size',
									'suffix'   => '!important',
								),
							),
							'active_callback' => array(
								array(
									array(
										'field'    => 'layout',
										'operator' => '==',
										'value'    => 'reviews-3',
									),
									array(
										'field'    => 'layout',
										'operator' => '==',
										'value'    => 'reviews-4',
									),
									array(
										'field'    => 'layout',
										'operator' => '==',
										'value'    => 'reviews-5',
									),
								),
							),
						),
						// Color Settings.
						array(
							'key'     => 'colorHeading',
							'label'   => esc_html__( 'Heading', 'absolute-reviews' ),
							'section' => 'color',
							'type'    => 'color',
							'default' => '#000',
							'output'  => array(
								array(
									'element'  => '$ .abr-variation-default .entry-title a',
									'property' => 'color',
									'suffix'   => '!important',
								),
							),
						),
						array(
							'key'     => 'colorHeadingHover',
							'label'   => esc_html__( 'Heading Hover', 'absolute-reviews' ),
							'section' => 'color',
							'type'    => 'color',
							'default' => '#5a5a5a',
							'output'  => array(
								array(
									'element'  => '$ .abr-variation-default .entry-title a:hover, $ .abr-variation-default .entry-title a:focus',
									'property' => 'color',
									'suffix'   => '!important',
								),
							),
						),
						array(
							'key'     => 'colorMeta',
							'label'   => esc_html__( 'Post Meta', 'absolute-reviews' ),
							'section' => 'color',
							'type'    => 'color',
							'default' => '',
							'output'  => array(
								array(
									'element'  => '$ .abr-variation-default .post-meta span, $ .abr-variation-default .post-categories span',
									'property' => 'color',
									'suffix'   => '!important',
								),
							),
						),
						array(
							'key'     => 'colorMetaLinks',
							'label'   => esc_html__( 'Post Meta Links', 'absolute-reviews' ),
							'section' => 'color',
							'type'    => 'color',
							'default' => '',
							'output'  => array(
								array(
									'element'  => '$ .abr-variation-default .post-meta a, $ .abr-variation-default .post-categories a',
									'property' => 'color',
									'suffix'   => '!important',
								),
							),
						),
						array(
							'key'     => 'colorMetaLinksHover',
							'label'   => esc_html__( 'Post Meta Links Hover', 'absolute-reviews' ),
							'section' => 'color',
							'type'    => 'color',
							'default' => '',
							'output'  => array(
								array(
									'element'  => '$ .abr-variation-default .post-meta a:hover, $ .abr-variation-default .post-meta a:focus, $ .abr-variation-default .post-categories a:hover, $ .abr-variation-default .post-categories a:focus',
									'property' => 'color',
									'suffix'   => '!important',
								),
							),
						),
					),
					// Meta Settings.
					$this->get_meta_fields(
						array(
							'field_prefix'    => 'reviews',
							'section_name'    => 'meta',
							'active_callback' => array(
								array(
									'field'    => 'layout',
									'operator' => '!=',
									'value'    => 'reviews-3',
								),
								array(
									'field'    => 'layout',
									'operator' => '!=',
									'value'    => 'reviews-4',
								),
								array(
									'field'    => 'layout',
									'operator' => '!=',
									'value'    => 'reviews-5',
								),
							),
						)
					),
					$this->get_meta_fields(
						array(
							'field_prefix'    => 'reviewsLarge',
							'section_name'    => 'reviewsLargeMeta',
							'active_callback' => array(
								array(
									array(
										'field'    => 'layout',
										'operator' => '==',
										'value'    => 'reviews-3',
									),
									array(
										'field'    => 'layout',
										'operator' => '==',
										'value'    => 'reviews-4',
									),
									array(
										'field'    => 'layout',
										'operator' => '==',
										'value'    => 'reviews-5',
									),
								),
							),
						)
					),
					$this->get_meta_fields(
						array(
							'field_prefix'    => 'reviewsSmall',
							'section_name'    => 'reviewsSmallMeta',
							'active_callback' => array(
								array(
									array(
										'field'    => 'layout',
										'operator' => '==',
										'value'    => 'reviews-3',
									),
									array(
										'field'    => 'layout',
										'operator' => '==',
										'value'    => 'reviews-4',
									),
									array(
										'field'    => 'layout',
										'operator' => '==',
										'value'    => 'reviews-5',
									),
								),
							),
						)
					)
				),
			);
		}

		return $layouts;
	}

	/**
	 * Change post query attributes
	 *
	 * @param array $args       Args for post query.
	 * @param array $attributes Block attributes.
	 * @param array $options    Block options.
	 */
	public function change_query_args( $args, $attributes, $options ) {

		// Review type.
		if ( isset( $options['reviewType'] ) && 'all' !== $options['reviewType'] ) {
			$args['meta_query'] = array(
				'relation' => 'OR',
				array(
					'key'   => '_abr_review_type',
					'value' => $options['reviewType'],
				),
			);
		}

		// Posts count.
		if ( isset( $options['reviewsPostsCount'] ) && $options['reviewsPostsCount'] ) {
			$args['posts_per_page'] = $options['reviewsPostsCount'];
		}

		return $args;
	}
}

new ABR_Reviews_Posts_Block();
