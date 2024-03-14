<?php
/**
 * Progress Block.
 *
 * @package Canvas
 */

/**
 * Initialize Progress block.
 */
class CNVS_Block_Progress {

	/**
	 * Initialize
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'init' ) );
		add_filter( 'canvas_register_block_type', array( $this, 'register_block_type' ) );
	}

	/**
	 * Enqueue the block's assets for the editor.
	 */
	public function init() {
		// Editor Scripts.
		wp_register_script(
			'canvas-block-progress-editor-script',
			plugins_url( 'block-progress/block.js', __FILE__ ),
			array( 'wp-blocks', 'wp-components', 'wp-element', 'wp-i18n', 'wp-editor', 'lodash', 'jquery' ),
			filemtime( plugin_dir_path( __FILE__ ) . 'block-progress/block.js' ),
			true
		);

		wp_register_style(
			'canvas-block-progress-editor-style',
			plugins_url( 'block-progress/block-editor.css', __FILE__ ),
			array(),
			filemtime( plugin_dir_path( __FILE__ ) . 'block-progress/block-editor.css' )
		);

		// Styles.
		wp_register_style(
			'canvas-block-progress-style',
			plugins_url( 'block-progress/block.css', __FILE__ ),
			array(),
			filemtime( plugin_dir_path( __FILE__ ) . 'block-progress/block.css' )
		);

		wp_style_add_data( 'canvas-block-progress-style', 'rtl', 'replace' );
	}

	/**
	 * Register block
	 *
	 * @param array $blocks all registered blocks.
	 * @return array
	 */
	public function register_block_type( $blocks ) {
		$blocks[] = array(
			'name'          => 'canvas/progress',
			'title'         => esc_html__( 'Progress', 'canvas' ),
			'description'   => '',
			'category'      => 'canvas',
			'keywords'      => array(),
			'icon'          => '<svg width="24" height="24" xmlns="http://www.w3.org/2000/svg"><g fill="none" fill-rule="evenodd"><g transform="translate(3 5)"><rect stroke="#000" stroke-width="2" x="1" y="1" width="16" height="4" rx="1"/><path d="M1 0h9v6H1a1 1 0 01-1-1V1a1 1 0 011-1z" fill="#000"/></g><g transform="translate(3 13)"><rect stroke="#000" stroke-width="2" x="1" y="1" width="16" height="4" rx="1"/><path d="M1 0h6v6H1a1 1 0 01-1-1V1a1 1 0 011-1z" fill="#000"/></g></g></svg>',
			'supports'      => array(
				'className'        => true,
				'anchor'           => true,
				'html'             => false,
				'canvasSpacings'   => true,
				'canvasBorder'     => true,
				'canvasResponsive' => true,
			),
			'styles'        => array(
				array(
					'name'  => 'cnvs-block-progress-primary',
					'label' => esc_html__( 'Primary', 'canvas' ),
				),
				array(
					'name'  => 'cnvs-block-progress-success',
					'label' => esc_html__( 'Success', 'canvas' ),
				),
				array(
					'name'  => 'cnvs-block-progress-info',
					'label' => esc_html__( 'Info', 'canvas' ),
				),
				array(
					'name'  => 'cnvs-block-progress-warning',
					'label' => esc_html__( 'Warning', 'canvas' ),
				),
				array(
					'name'  => 'cnvs-block-progress-danger',
					'label' => esc_html__( 'Danger', 'canvas' ),
				),
				array(
					'name'  => 'cnvs-block-progress-dark',
					'label' => esc_html__( 'Dark', 'canvas' ),
				),
			),
			'location'      => array(),

			'sections'      => array(
				'general' => array(
					'title'    => esc_html__( 'Block Settings', 'canvas' ),
					'priority' => 5,
					'open'     => true,
				),
			),
			'layouts'       => array(),

			// Set fields just for add block attributes.
			// Editor render for this block is custom JSX
			// so we don't need to render fields automatically.
			'fields'        => array(
				array(
					'key'     => 'width',
					'label'   => esc_html__( 'Width', 'canvas' ),
					'type'    => 'number',
					'section' => 'general',
					'min'     => 1,
					'max'     => 100,
					'default' => 70,
					'output'  => array(
						array(
							'element'  => '$ .cnvs-block-progress-bar',
							'property' => 'width',
							'units'    => '%',
						),
					),
				),
				array(
					'key'     => 'height',
					'label'   => esc_html__( 'Height', 'canvas' ),
					'type'    => 'number',
					'section' => 'general',
					'min'     => 1,
					'max'     => 20,
					'default' => 14,
					'output'  => array(
						array(
							'property' => 'height',
							'units'    => 'px',
						),
					),
				),
				array(
					'key'     => 'striped',
					'label'   => esc_html__( 'Striped', 'canvas' ),
					'type'    => 'toggle',
					'section' => 'general',
					'default' => true,
				),
				array(
					'key'             => 'animated',
					'label'           => esc_html__( 'Animated', 'canvas' ),
					'type'            => 'toggle',
					'section'         => 'general',
					'default'         => false,
					'active_callback' => array(
						array(
							'field' => 'striped',
						),
					),
				),
				array(
					'key'     => 'displayPercent',
					'label'   => esc_html__( 'Display Percent', 'canvas' ),
					'type'    => 'toggle',
					'section' => 'general',
					'default' => false,
				),
			),
			'template'      => dirname( __FILE__ ) . '/block-progress/render.php',

			// enqueue registered scripts/styles.
			'style'         => 'canvas-block-progress-style',
			'editor_style'  => 'canvas-block-progress-editor-style',
			'editor_script' => 'canvas-block-progress-editor-script',
		);

		return $blocks;
	}
}

new CNVS_Block_Progress();
