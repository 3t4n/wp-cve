<?php
/**
 * Tabs Block.
 *
 * @package Canvas
 */

/**
 * Initialize Tabs block.
 */
class CNVS_Block_Tabs {

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
			'canvas-block-tabs-editor-script',
			plugins_url( 'block-tabs/block.js', __FILE__ ),
			array( 'wp-blocks', 'wp-components', 'wp-element', 'wp-i18n', 'wp-editor' ),
			filemtime( plugin_dir_path( __FILE__ ) . 'block-tabs/block.js' ),
			true
		);
		wp_register_script(
			'canvas-block-tab-editor-script',
			plugins_url( 'block-tab/block.js', __FILE__ ),
			array( 'wp-blocks', 'wp-components', 'wp-element', 'wp-i18n', 'wp-editor' ),
			filemtime( plugin_dir_path( __FILE__ ) . 'block-tab/block.js' ),
			true
		);

		// Editor Styles.
		wp_register_style(
			'canvas-block-tabs-editor-style',
			plugins_url( 'block-tabs/block-editor.css', __FILE__ ),
			array(),
			filemtime( plugin_dir_path( __FILE__ ) . 'block-tabs/block-editor.css' )
		);

		wp_style_add_data( 'canvas-block-tabs-editor-style', 'rtl', 'replace' );

		// Styles.
		wp_register_style(
			'canvas-block-tabs-style',
			plugins_url( 'block-tabs/block.css', __FILE__ ),
			array(),
			filemtime( plugin_dir_path( __FILE__ ) . 'block-tabs/block.css' )
		);

		wp_style_add_data( 'canvas-block-tabs-style', 'rtl', 'replace' );

		// Scripts.
		wp_register_script( 'canvas-block-tabs-script', plugin_dir_url( __FILE__ ) . 'block-tabs/public-block-tabs.js', array( 'jquery' ), cnvs_get_setting( 'version' ), true );
	}

	/**
	 * Register block
	 *
	 * @param array $blocks all registered blocks.
	 * @return array
	 */
	public function register_block_type( $blocks ) {
		$blocks[] = array(
			'name'          => 'canvas/tabs',
			'title'         => esc_html__( 'Tabs', 'canvas' ),
			'description'   => '',
			'category'      => 'canvas',
			'keywords'      => array(),
			'icon'          => '
				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
					<path d="M21 3H3C1.9 3 1 3.9 1 5V19C1 20.1 1.9 21 3 21H21C22.1 21 23 20.1 23 19V5C23 3.9 22.1 3 21 3ZM21 19H3V5H13V9H21V19Z" />
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
			'styles'        => array(
				array(
					'name'      => 'cnvs-block-tabs-default',
					'label'     => esc_html__( 'Default', 'canvas' ),
					'isDefault' => true,
				),
				array(
					'name'  => 'cnvs-block-tabs-pills',
					'label' => esc_html__( 'Pills', 'canvas' ),
				),
			),
			'location'      => array(),

			'sections'      => array(),
			'layouts'       => array(),

			// Set fields just for add block attributes.
			// Editor render for this block is custom JSX
			// so we don't need to render fields automatically.
			'fields'        => array(
				array(
					'key'     => 'tabActive',
					'type'    => 'type-number',
					'default' => 0,
				),
				array(
					'key'     => 'tabsData',
					'type'    => 'type-array',
					'default' => array(
						'Tab 1',
						'Tab 2',
					),
					'items'   => array(
						'type' => 'string',
					),
				),
				array(
					'key'     => 'tabsPosition',
					'label'   => esc_html__( 'Position', 'canvas' ),
					'type'    => 'select',
					'choices' => array(
						'horizontal' => esc_html__( 'Horizontal', 'canvas' ),
						'vertical'   => esc_html__( 'Vertical', 'canvas' ),
					),
					'default' => 'horizontal',
				),
			),
			'template'      => dirname( __FILE__ ) . '/block-tabs/render.php',

			// enqueue registered scripts/styles.
			'style'         => is_admin() ? '' : 'canvas-block-tabs-style',
			'script'        => is_admin() ? '' : 'canvas-block-tabs-script',
			'editor_script' => 'canvas-block-tabs-editor-script',
			'editor_style'  => 'canvas-block-tabs-editor-style',
		);

		$blocks[] = array(
			'name'          => 'canvas/tab',
			'title'         => esc_html__( 'Tab', 'canvas' ),
			'description'   => '',
			'category'      => 'canvas',
			'keywords'      => array(),
			'icon'          => '
				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
					<path d="M21 3H3C1.9 3 1 3.9 1 5V19C1 20.1 1.9 21 3 21H21C22.1 21 23 20.1 23 19V5C23 3.9 22.1 3 21 3ZM21 19H3V5H13V9H21V19Z" />
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
				'canvas/tabs',
			),
			'styles'        => array(),
			'location'      => array(),

			'sections'      => array(),
			'layouts'       => array(),
			'fields'        => array(),
			'template'      => dirname( __FILE__ ) . '/block-tab/render.php',

			// enqueue registered scripts/styles.
			'editor_script' => 'canvas-block-tab-editor-script',
		);

		return $blocks;
	}
}

new CNVS_Block_Tabs();
