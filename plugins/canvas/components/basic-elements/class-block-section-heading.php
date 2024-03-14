<?php
/**
 * Section Heading Block.
 *
 * @package Canvas
 */

/**
 * Initialize Section Heading Block.
 */
class CNVS_Block_Section_Heading {

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
			'canvas-block-section-heading-editor-script',
			plugins_url( 'block-section-heading/block.js', __FILE__ ),
			array( 'wp-blocks', 'wp-components', 'wp-element', 'wp-i18n', 'wp-editor', 'lodash', 'jquery' ),
			filemtime( plugin_dir_path( __FILE__ ) . 'block-section-heading/block.js' ),
			true
		);

		wp_register_style(
			'canvas-block-section-heading-editor-style',
			plugins_url( 'block-section-heading/block-editor.css', __FILE__ ),
			array(),
			filemtime( plugin_dir_path( __FILE__ ) . 'block-section-heading/block-editor.css' )
		);

		wp_style_add_data( 'canvas-block-section-heading-editor-style', 'rtl', 'replace' );

		// Styles.
		wp_register_style(
			'canvas-block-section-heading-style',
			plugins_url( 'block-section-heading/block.css', __FILE__ ),
			array(),
			filemtime( plugin_dir_path( __FILE__ ) . 'block-section-heading/block.css' )
		);

		wp_style_add_data( 'canvas-block-section-heading-style', 'rtl', 'replace' );
	}

	/**
	 * This function will register scripts and styles for admin dashboard.
	 *
	 * @param string $page Current page.
	 */
	public function admin_enqueue_scripts( $page ) {
		wp_localize_script(
			'jquery-ui-core',
			'canvasBSHLocalize',
			array(
				'sectionHeadingAlign' => apply_filters( 'canvas_section_heading_align', 'halignleft' ),
				'sectionHeadingTag'   => apply_filters( 'canvas_section_heading_tag', 'h2' ),
			)
		);
	}

	/**
	 * Register block
	 *
	 * @param array $blocks all registered blocks.
	 * @return array
	 */
	public function register_block_type( $blocks ) {

		$blocks[] = array(
			'name'          => 'canvas/section-heading',
			'title'         => esc_html__( 'Section Heading', 'canvas' ),
			'description'   => '',
			'category'      => 'canvas',
			'keywords'      => array(),
			'icon'          => 'heading',
			'supports'      => array(
				'className'        => true,
				'anchor'           => true,
				'html'             => false,
				'canvasSpacings'   => true,
				'canvasBorder'     => true,
				'canvasResponsive' => true,
				'align'            => false,
			),
			'styles'        => array(
				array(
					'name'  => 'cnvs-block-section-heading-default',
					'label' => esc_html__( 'Default', 'canvas' ),
				),
				array(
					'name'  => 'cnvs-block-section-heading-1',
					'label' => esc_html__( 'Plain', 'canvas' ),
				),
				array(
					'name'  => 'cnvs-block-section-heading-2',
					'label' => esc_html__( 'Thin Bottom Line', 'canvas' ),
				),
				array(
					'name'  => 'cnvs-block-section-heading-3',
					'label' => esc_html__( 'Thick Bottom Line', 'canvas' ),
				),
				array(
					'name'  => 'cnvs-block-section-heading-4',
					'label' => esc_html__( 'Thin Side Line', 'canvas' ),
				),
				array(
					'name'  => 'cnvs-block-section-heading-5',
					'label' => esc_html__( 'Thick Side Line', 'canvas' ),
				),
				array(
					'name'  => 'cnvs-block-section-heading-6',
					'label' => esc_html__( 'Top Line', 'canvas' ),
				),
				array(
					'name'  => 'cnvs-block-section-heading-7',
					'label' => esc_html__( 'Bottom Line, Medium Length', 'canvas' ),
				),
				array(
					'name'  => 'cnvs-block-section-heading-8',
					'label' => esc_html__( 'Side Line with Angle', 'canvas' ),
				),
				array(
					'name'  => 'cnvs-block-section-heading-9',
					'label' => esc_html__( 'Cross Icon', 'canvas' ),
				),
				array(
					'name'  => 'cnvs-block-section-heading-10',
					'label' => esc_html__( 'Scewed Background', 'canvas' ),
				),
				array(
					'name'  => 'cnvs-block-section-heading-11',
					'label' => esc_html__( 'Scewed Background, Side Line', 'canvas' ),
				),
				array(
					'name'  => 'cnvs-block-section-heading-12',
					'label' => esc_html__( 'Solid Background', 'canvas' ),
				),
				array(
					'name'  => 'cnvs-block-section-heading-13',
					'label' => esc_html__( 'Bordered', 'canvas' ),
				),
				array(
					'name'  => 'cnvs-block-section-heading-14',
					'label' => esc_html__( 'Solid Background, Fullwidth', 'canvas' ),
				),
				array(
					'name'  => 'cnvs-block-section-heading-15',
					'label' => esc_html__( 'Bordered, Fullwidth', 'canvas' ),
				),
				array(
					'name'  => 'cnvs-block-section-heading-16',
					'label' => esc_html__( 'Double Line with Angle', 'canvas' ),
				),
				array(
					'name'  => 'cnvs-block-section-heading-17',
					'label' => esc_html__( 'Bottom Line, Short Length', 'canvas' ),
				),
			),
			'location'      => array(),
			'sections'      => array(
				'color'      => array(
					'title'    => esc_html__( 'Color Settings', 'canvas' ),
					'priority' => 50,
				),
				'typography' => array(
					'title'    => esc_html__( 'Typography Settings', 'canvas' ),
					'priority' => 45,
				),
			),
			'layouts'       => array(),

			// Set fields just for add block attributes.
			// Editor render for this block is custom JSX
			// so we don't need to render fields automatically.
			'fields'        => array(
				array(
					'key'  => 'halign',
					'type' => 'type-string',
				),
				array(
					'key'  => 'tag',
					'type' => 'type-string',
				),
				array(
					'key'  => 'content',
					'type' => 'type-string',
				),
				array(
					'key'     => 'colorHeadingBorder',
					'label'   => esc_html__( 'Border Color', 'canvas' ),
					'section' => 'color',
					'type'    => 'color',
					'output'  => array(
						array(
							'element'  => '$, $ .cnvs-section-title, $:before, $:after, $ .cnvs-section-title:before, $ .cnvs-section-title:after',
							'property' => 'border-color',
							'suffix'   => '!important',
						),
					),
				),
				array(
					'key'     => 'colorHeadingAccent',
					'label'   => esc_html__( 'Accent Color', 'canvas' ),
					'section' => 'color',
					'type'    => 'color',
					'output'  => array(
						array(
							'element'  => '$.is-style-cnvs-block-section-heading-11 .cnvs-section-title:before, $.is-style-cnvs-block-section-heading-10 .cnvs-section-title:before, $.is-style-cnvs-block-section-heading-9 .cnvs-section-title:before, $.is-style-cnvs-block-section-heading-9 .cnvs-section-title:after, $.is-style-cnvs-block-section-heading-12 .cnvs-section-title, $.is-style-cnvs-block-section-heading-14, .section-heading-default-style-11 $.is-style-cnvs-block-section-heading-default .cnvs-section-title:before, .section-heading-default-style-10 $.is-style-cnvs-block-section-heading-default .cnvs-section-title:before, .section-heading-default-style-9 $.is-style-cnvs-block-section-heading-default .cnvs-section-title:before, .section-heading-default-style-9 $.is-style-cnvs-block-section-heading-default .cnvs-section-title:after, .section-heading-default-style-12 $.is-style-cnvs-block-section-heading-default .cnvs-section-title, .section-heading-default-style-14 $.is-style-cnvs-block-section-heading-default',
							'property' => 'background-color',
							'suffix'   => '!important',
						),
					),
				),
				array(
					'key'     => 'colorHeading',
					'label'   => esc_html__( 'Text Color', 'canvas' ),
					'section' => 'color',
					'type'    => 'color',
					'output'  => array(
						array(
							'element'  => '$ .cnvs-section-title',
							'property' => 'color',
							'suffix'   => '!important',
						),
					),
				),
				array(
					'key'        => 'typographyHeading',
					'label'      => esc_html__( 'Font Size', 'canvas' ),
					'section'    => 'typography',
					'type'       => 'dimension',
					'responsive' => true,
					'output'     => array(
						array(
							'element'  => '$ .cnvs-section-title',
							'property' => 'font-size',
							'suffix'   => '!important',
						),
					),
				),
			),
			'template'      => dirname( __FILE__ ) . '/block-section-heading/render.php',

			// enqueue registered scripts/styles.
			'style'         => 'canvas-block-section-heading-style',
			'editor_style'  => 'canvas-block-section-heading-editor-style',
			'editor_script' => 'canvas-block-section-heading-editor-script',
		);

		return $blocks;
	}
}

new CNVS_Block_Section_Heading();
