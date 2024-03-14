<?php
/**
 * Collapsibles Block.
 *
 * @package Canvas
 */

/**
 * Initialize Collapsibles block.
 */
class CNVS_Block_Collapsibles {

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
			'canvas-block-collapsibles-editor-script',
			plugins_url( 'block-collapsibles/block.js', __FILE__ ),
			array( 'wp-blocks', 'wp-components', 'wp-element', 'wp-i18n', 'wp-editor' ),
			filemtime( plugin_dir_path( __FILE__ ) . 'block-collapsibles/block.js' ),
			true
		);

		wp_register_script(
			'canvas-block-collapsible-editor-script',
			plugins_url( 'block-collapsible/block.js', __FILE__ ),
			array( 'wp-blocks', 'wp-components', 'wp-element', 'wp-i18n', 'wp-editor' ),
			filemtime( plugin_dir_path( __FILE__ ) . 'block-collapsible/block.js' ),
			true
		);

		// Editor Styles.
		wp_register_style(
			'canvas-block-collapsibles-editor-style',
			plugins_url( 'block-collapsibles/block-editor.css', __FILE__ ),
			array(),
			filemtime( plugin_dir_path( __FILE__ ) . 'block-collapsibles/block-editor.css' )
		);

		wp_style_add_data( 'canvas-block-collapsibles-editor-style', 'rtl', 'replace' );

		// Styles.
		wp_register_style(
			'canvas-block-collapsibles-style',
			plugins_url( 'block-collapsibles/block.css', __FILE__ ),
			array(),
			filemtime( plugin_dir_path( __FILE__ ) . 'block-collapsibles/block.css' )
		);

		wp_style_add_data( 'canvas-block-collapsibles-style', 'rtl', 'replace' );

		// Scripts.
		wp_register_script( 'canvas-block-collapsibles-script', plugin_dir_url( __FILE__ ) . 'block-collapsibles/public-block-collapsibles.js', array( 'jquery' ), cnvs_get_setting( 'version' ), true );
	}

	/**
	 * Register block
	 *
	 * @param array $blocks all registered blocks.
	 * @return array
	 */
	public function register_block_type( $blocks ) {
		$blocks[] = array(
			'name'          => 'canvas/collapsibles',
			'title'         => esc_html__( 'Collapsibles', 'canvas' ),
			'description'   => '',
			'category'      => 'canvas',
			'keywords'      => array(),
			'icon'          => '
				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
					<path d="M19 15V17H5V15H19ZM21 5H3V7H21V5ZM21 9H3V11H21V9ZM21 13H3V19H21V13Z" />
				</svg>
			',
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
					'key'     => 'count',
					'label'   => esc_html__( 'Collapsibles', 'canvas' ),
					'type'    => 'number',
					'min'     => 1,
					'max'     => 20,
					'default' => 2,
					'section' => 'general',
				),
			),
			'template'      => dirname( __FILE__ ) . '/block-collapsibles/render.php',

			// enqueue registered scripts/styles.
			'style'         => 'canvas-block-collapsibles-style',
			'script'        => is_admin() ? '' : 'canvas-block-collapsibles-script',
			'editor_script' => 'canvas-block-collapsibles-editor-script',
			'editor_style'  => 'canvas-block-collapsibles-editor-style',
		);

		$blocks[] = array(
			'name'          => 'canvas/collapsible',
			'title'         => esc_html__( 'Collapsible', 'canvas' ),
			'description'   => '',
			'category'      => 'canvas',
			'keywords'      => array(),
			'icon'          => '
				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
					<path d="M19 15V17H5V15H19ZM21 5H3V7H21V5ZM21 9H3V11H21V9ZM21 13H3V19H21V13Z" />
				</svg>
			',
			'supports'      => array(
				'inserter'         => false,
				'reusable'         => false,
				'className'        => true,
				'anchor'           => true,
				'canvasSpacings'   => true,
				'canvasBorder'     => true,
				'canvasResponsive' => true,
			),
			'parent'        => array(
				'canvas/collapsibles',
			),
			'styles'        => array(),
			'location'      => array(),

			'sections'      => array(),
			'layouts'       => array(),
			'fields'        => array(
				array(
					'key'     => 'title',
					'type'    => 'type-string',
					'default' => '',
				),
				array(
					'key'     => 'opened',
					'label'   => esc_html__( 'Opened', 'canvas' ),
					'type'    => 'toggle',
					'default' => false,
				),
			),
			'template'      => dirname( __FILE__ ) . '/block-collapsible/render.php',

			// enqueue registered scripts/styles.
			'editor_script' => 'canvas-block-collapsible-editor-script',
		);

		return $blocks;
	}
}

new CNVS_Block_Collapsibles();
