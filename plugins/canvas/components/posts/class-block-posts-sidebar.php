<?php
/**
 * Posts Block Sidebar.
 *
 * @package Canvas
 */

/**
 * Initialize Featured Posts block sidebar.
 */
class CNVS_Block_Posts_Sidebar {

	/**
	 * Initialize
	 */
	public function __construct() {
		add_action( 'enqueue_block_assets', array( $this, 'enqueue_block_assets' ) );
		add_filter( 'canvas_block_layouts_canvas/posts', array( $this, 'register_layout' ), 99 );
		add_filter( 'canvas_block_posts_query_args', array( $this, 'change_query_args' ), 10, 3 );
	}

	/**
	 * Enqueue the block's assets for the editor.
	 */
	public function enqueue_block_assets() {

		wp_register_style(
			'canvas-block-posts-sidebar',
			plugins_url( 'block-posts-sidebar/block-posts-sidebar.css', __FILE__ ),
			array(),
			filemtime( plugin_dir_path( __FILE__ ) . 'block-posts-sidebar/block-posts-sidebar.css' )
		);

		wp_style_add_data( 'canvas-block-posts-sidebar', 'rtl', 'replace' );

		wp_enqueue_style( 'canvas-block-posts-sidebar' );
	}

	/**
	 * Get types of layout.
	 */
	public function get_types_of_layouts() {
		$types = array(
			'sidebar-list'     => 'sidebar-list',
			'sidebar-numbered' => 'sidebar-numbered',
			'sidebar-large'    => 'sidebar-large',
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
			case 'sidebar-list':
				return esc_html__( 'Widget 1', 'canvas' );
			case 'sidebar-numbered':
				return esc_html__( 'Widget 2', 'canvas' );
			case 'sidebar-large':
				return esc_html__( 'Widget 3', 'canvas' );
		}
	}

	/**
	 * Get icon of layout by key.
	 *
	 * @param mixed $key The key.
	 */
	public function get_icon_of_layout_by( $key ) {

		switch ( $key ) {
			case 'sidebar-list':
				return '<svg width="52" height="44" xmlns="http://www.w3.org/2000/svg"><g transform="translate(1 1)" stroke="#2D2D2D" fill="none" fill-rule="evenodd"><rect stroke-width="1.5" width="50" height="42" rx="3"/><rect stroke-width="1.5" x="5" y="5" width="8" height="8" rx="1"/><rect stroke-width="1.5" x="5" y="17" width="8" height="8" rx="1"/><rect stroke-width="1.5" x="5" y="29" width="8" height="8" rx="1"/><path d="M16.833 5.5h23.334m-23.334 3h28.334m-28.32 3h20.307M16.833 17.5h23.334m-23.334 3h28.334m-28.32 3h20.307M16.833 29.5h23.334m-23.334 3h28.334m-28.32 3h20.307" stroke-linecap="round" stroke-linejoin="round"/></g></svg>';
			case 'sidebar-numbered':
				return '<svg width="52" height="44" xmlns="http://www.w3.org/2000/svg"><g transform="translate(1 1)" fill="none" fill-rule="evenodd"><rect stroke="#2D2D2D" stroke-width="1.5" width="50" height="42" rx="3"/><g transform="translate(5 5)" stroke="#2D2D2D"><rect stroke-width="1.5" width="8" height="8" rx="1"/><path d="M11.833.5h23.334m-23.334 3h28.334m-28.32 3h20.307" stroke-linecap="round" stroke-linejoin="round"/></g><text font-family="FuturaPT-Bold, Futura PT" font-size="6" font-weight="bold" fill="#2D2D2D"><tspan x="7.2" y="11">1</tspan></text><text font-family="FuturaPT-Bold, Futura PT" font-size="6" font-weight="bold" fill="#2D2D2D"><tspan x="7.2" y="23">2</tspan></text><text font-family="FuturaPT-Bold, Futura PT" font-size="6" font-weight="bold" fill="#2D2D2D"><tspan x="7.2" y="35">3</tspan></text><rect stroke="#2D2D2D" stroke-width="1.5" x="5" y="17" width="8" height="8" rx="1"/><rect stroke="#2D2D2D" stroke-width="1.5" x="5" y="29" width="8" height="8" rx="1"/><path d="M16.833 17.5h23.334m-23.334 3h28.334m-28.32 3h20.307M16.833 29.5h23.334m-23.334 3h28.334m-28.32 3h20.307" stroke="#2D2D2D" stroke-linecap="round" stroke-linejoin="round"/></g></svg>';
			case 'sidebar-large':
				return '<svg width="52" height="44" xmlns="http://www.w3.org/2000/svg"><g transform="translate(1 1)" stroke="#2D2D2D" fill="none" fill-rule="evenodd"><rect stroke-width="1.5" width="50" height="42" rx="3"/><g transform="translate(16 8)"><rect stroke-width="1.5" width="18" height="22" rx="1"/><path d="M.5 24.5h17M.5 27h13" stroke-linecap="round" stroke-linejoin="round"/></g></g></svg>';
		}
	}

	/**
	 * Register layout.
	 *
	 * @param array $layouts List of layouts.
	 */
	public function register_layout( $layouts = array() ) {

		$types = $this->get_types_of_layouts();

		foreach ( $types as $type ) {

			$layouts[ $type ] = array(
				'location'    => array( 'section-sidebar' ),
				'name'        => $this->get_name_of_layout_by( $type ),
				'template'    => dirname( __FILE__ ) . '/block-posts-sidebar/layouts/posts-sidebar.php',
				'icon'        => $this->get_icon_of_layout_by( $type ),
				'sections'    => array(
					'general' => array(
						'title'    => esc_html__( 'Block Settings', 'canvas' ),
						'priority' => 5,
						'open'     => true,
					),
				),
				'hide_fields' => array(
					'postsCount',
					'showExcerpt',
					'showViewPostButton',
					'showPagination',
					'colorText',
					'colorHeading',
					'colorHeadingHover',
					'colorText',
					'colorMeta',
					'colorMetaHover',
					'colorMetaLinks',
					'colorMetaLinksHover',
				),
				'fields'      => array(
					array(
						'key'     => 'widgetPostsCount',
						'label'   => esc_html__( 'Posts Count', 'canvas' ),
						'section' => 'pagination',
						'type'    => 'number',
						'default' => 5,
						'min'     => 1,
						'max'     => 100,
					),
					// Color Settings.
					array(
						'key'     => 'colorHeading',
						'label'   => esc_html__( 'Heading', 'canvas' ),
						'section' => 'color',
						'type'    => 'color',
						'default' => '#000',
						'output'  => array(
							array(
								'element'  => '$.cnvs-block-posts-sidebar .entry-title a',
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
								'element'  => '$.cnvs-block-posts-sidebar .entry-title a:hover, $.cnvs-block-posts-sidebar .entry-title a:focus',
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
								'element'  => '$.cnvs-block-posts-sidebar .cnvs-post-meta a, $.cnvs-block-posts-sidebar .post-categories a',
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
								'element'  => '$.cnvs-block-posts-sidebar .cnvs-post-meta a:hover, $.cnvs-block-posts-sidebar .cnvs-post-meta a:focus, $.cnvs-block-posts-sidebar .post-categories a:hover, $.cnvs-block-posts-sidebar .post-categories a:focus',
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
								'element'  => '$.cnvs-block-posts-sidebar .cnvs-post-meta span, $.cnvs-block-posts-sidebar .post-categories span',
								'property' => 'color',
							),
						),
					),
					'sidebar-numbered' === $type ? array(
						'key'     => 'bgCounter',
						'label'   => esc_html__( 'Background Counter', 'canvas' ),
						'section' => 'color',
						'type'    => 'color',
						'output'  => array(
							array(
								'element'  => '$ .cnvs-post-item .cnvs-post-number',
								'property' => 'background-color',
							),
						),
					) : array(),
					'sidebar-numbered' === $type ? array(
						'key'     => 'colorCounter',
						'label'   => esc_html__( 'Color Counter', 'canvas' ),
						'section' => 'color',
						'type'    => 'color',
						'output'  => array(
							array(
								'element'  => '$ .cnvs-post-item .cnvs-post-number',
								'property' => 'color',
							),
						),
					) : array(),
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

		// Posts count.
		if ( isset( $options['widgetPostsCount'] ) && $options['widgetPostsCount'] ) {
			$args['posts_per_page'] = $options['widgetPostsCount'];
		}

		return $args;
	}
}

new CNVS_Block_Posts_Sidebar();
