<?php
/**
 * Section Block.
 *
 * @package Canvas
 */

/**
 * Initialize Section block.
 */
class CNVS_Block_Section {

	/**
	 * Initialize
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'init' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ), 5 );
		add_filter( 'canvas_register_block_type', array( $this, 'register_block_type' ) );
	}

	/**
	 * Enqueue the block's assets for the editor.
	 */
	public function init() {
		// Editor Scripts.
		wp_register_script(
			'canvas-block-section-editor-script',
			plugins_url( 'block-section/block.js', __FILE__ ),
			array( 'wp-blocks', 'wp-components', 'wp-element', 'wp-i18n', 'wp-editor' ),
			filemtime( plugin_dir_path( __FILE__ ) . 'block-section/block.js' ),
			true
		);

		wp_register_script(
			'canvas-block-section-content-editor-script',
			plugins_url( 'block-section-content/block.js', __FILE__ ),
			array( 'wp-blocks', 'wp-components', 'wp-element', 'wp-i18n', 'wp-editor' ),
			filemtime( plugin_dir_path( __FILE__ ) . 'block-section-content/block.js' ),
			true
		);

		wp_register_script(
			'canvas-block-section-sidebar-editor-script',
			plugins_url( 'block-section-sidebar/block.js', __FILE__ ),
			array( 'wp-blocks', 'wp-components', 'wp-element', 'wp-i18n', 'wp-editor' ),
			filemtime( plugin_dir_path( __FILE__ ) . 'block-section-sidebar/block.js' ),
			true
		);

		// Editor Styles.
		wp_register_style(
			'canvas-block-section-editor-style',
			plugins_url( 'block-section/block-section-editor.css', __FILE__ ),
			array(),
			filemtime( plugin_dir_path( __FILE__ ) . 'block-section/block-section-editor.css' )
		);

		wp_style_add_data( 'canvas-block-section-editor-style', 'rtl', 'replace' );

		// Styles.
		wp_register_style(
			'canvas-block-section-style',
			plugins_url( 'block-section/block-section.css', __FILE__ ),
			array(),
			filemtime( plugin_dir_path( __FILE__ ) . 'block-section/block-section.css' )
		);

		wp_style_add_data( 'canvas-block-section-style', 'rtl', 'replace' );
	}

	/**
	 * This function will register scripts and styles for admin dashboard.
	 *
	 * @param string $page Current page.
	 */
	public function admin_enqueue_scripts( $page ) {
		wp_localize_script( 'jquery-ui-core', 'canvasBSLocalize', array(
			'sectionResponsiveMaxWidth' => apply_filters( 'canvas_section_responsive_max_width', 1200 ),
			'disableSectionResponsive'  => get_theme_support( 'canvas-disable-section-responsive' ),
		) );
	}

	/**
	 * Register block
	 *
	 * @param array $blocks all registered blocks.
	 * @return array
	 */
	public function register_block_type( $blocks ) {

		$blocks[] = array(
			'name'          => 'canvas/section',
			'title'         => esc_html__( 'Section', 'canvas' ),
			'description'   => esc_html__( 'Section block with optional sidebar.', 'canvas' ),
			'category'      => 'canvas',
			'keywords'      => array( 'section', 'sidebar', 'widgets' ),
			'icon'          => '
				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
					<path d="M3 13h8v2H3zm0 4h8v2H3zm0-8h8v2H3zm0-4h8v2H3zm16 2v10h-4V7h4m2-2h-8v14h8V5z" />
				</svg>
			',
			'supports'      => array(
				'className'             => true,
				'anchor'                => true,
				'html'                  => false,
				'canvasBackgroundImage' => true,
				'canvasSpacings'        => true,
				'canvasBorder'          => true,
				'canvasResponsive'      => true,
			),
			'styles'        => array(),
			'location'      => array(),
			'sections'      => array(),
			'layouts'       => array(),

			// Set fields just for add block attributes.
			// Editor render for this block is custom JSX
			// so we don't need to render fields automatically.
			'fields'        => array(
				array(
					'key'     => 'layout',
					'type'    => 'type-string',
					'default' => '',
				),
				array(
					'key'     => 'layoutAlign',
					'type'    => 'type-string',
					'default' => '',
				),
				array(
					'key'     => 'contentWidth',
					'type'    => 'type-number',
					'default' => apply_filters( 'canvas_section_responsive_max_width', 1200 ),
				),
				array(
					'key'     => 'sidebarPosition',
					'type'    => 'type-string',
					'default' => 'right',
				),
				array(
					'key'     => 'sidebarSticky',
					'type'    => 'type-boolean',
					'default' => false,
				),
				array(
					'key'     => 'sidebarStickyMethod',
					'type'    => 'type-string',
					'default' => 'top',
				),
				array(
					'key'     => 'textColor',
					'label'   => esc_html__( 'Text Color', 'canvas' ),
					'section' => esc_html__( 'Color Settings' ),
					'type'    => 'color',
					'default' => '',
					'output'  => array(
						array(
							'property' => 'color',
							'suffix'   => '!important',
						),
					),
				),
				array(
					'key'     => 'backgroundColor',
					'label'   => esc_html__( 'Background Color', 'canvas' ),
					'section' => esc_html__( 'Color Settings' ),
					'type'    => 'color',
					'default' => '',
					'output'  => array(
						array(
							'property' => 'background-color',
							'suffix'   => '!important',
						),
					),
				),
			),
			'template'      => dirname( __FILE__ ) . '/block-section/render.php',

			// enqueue registered scripts/styles.
			'style'         => is_admin() ? '' : 'canvas-block-section-style',
			'editor_script' => 'canvas-block-section-editor-script',
			'editor_style'  => 'canvas-block-section-editor-style',
		);

		$blocks[] = array(
			'name'          => 'canvas/section-content',
			'title'         => esc_html__( 'Section Content', 'canvas' ),
			'description'   => '',
			'category'      => 'canvas',
			'keywords'      => array(),
			'icon'          => '
				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
					<path d="M3 13h8v2H3zm0 4h8v2H3zm0-8h8v2H3zm0-4h8v2H3zm16 2v10h-4V7h4m2-2h-8v14h8V5z" />
				</svg>
			',
			'supports'      => array(
				'inserter'              => false,
				'reusable'              => false,
				'className'             => true,
				'anchor'                => true,
				'canvasBackgroundImage' => true,
				'canvasSpacings'        => true,
				'canvasBorder'          => true,
				'canvasResponsive'      => true,
			),
			'parent'        => array(
				'canvas/section',
			),
			'styles'        => array(),
			'location'      => array(),

			'sections'      => array(),
			'layouts'       => array(),
			'fields'        => array(),
			'template'      => dirname( __FILE__ ) . '/block-section-content/render.php',

			// enqueue registered scripts/styles.
			'editor_script' => 'canvas-block-section-content-editor-script',
		);

		$blocks[] = array(
			'name'          => 'canvas/section-sidebar',
			'title'         => esc_html__( 'Section Sidebar', 'canvas' ),
			'description'   => '',
			'category'      => 'canvas',
			'keywords'      => array(),
			'icon'          => '
				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
					<path d="M3 13h8v2H3zm0 4h8v2H3zm0-8h8v2H3zm0-4h8v2H3zm16 2v10h-4V7h4m2-2h-8v14h8V5z" />
				</svg>
			',
			'supports'      => array(
				'inserter'              => false,
				'reusable'              => false,
				'className'             => true,
				'anchor'                => true,
				'canvasBackgroundImage' => true,
				'canvasSpacings'        => true,
				'canvasBorder'          => true,
				'canvasResponsive'      => true,
			),
			'parent'        => array(
				'canvas/section',
			),
			'styles'        => array(),
			'location'      => array(),

			'sections'      => array(),
			'layouts'       => array(),
			'fields'        => array(),
			'template'      => dirname( __FILE__ ) . '/block-section-sidebar/render.php',

			// enqueue registered scripts/styles.
			'editor_script' => 'canvas-block-section-sidebar-editor-script',
		);

		return $blocks;
	}
}

new CNVS_Block_Section();
