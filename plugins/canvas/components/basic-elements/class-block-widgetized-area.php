<?php
/**
 * Widgetized Area Block.
 *
 * @package Canvas
 */

/**
 * Initialize Widgetized Area block.
 */
class CNVS_Block_Widgetized_Area {

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
			'canvas-block-widgetized-area-editor-script',
			plugins_url( 'block-widgetized-area/block.js', __FILE__ ),
			array( 'wp-blocks', 'wp-components', 'wp-element', 'wp-i18n', 'wp-editor' ),
			filemtime( plugin_dir_path( __FILE__ ) . 'block-widgetized-area/block.js' ),
			true
		);

		// Get all sidebars.
		$sidebars = array();
		if ( ! empty( $GLOBALS['wp_registered_sidebars'] ) ) {
			foreach ( $GLOBALS['wp_registered_sidebars'] as $k => $sidebar ) {
				$sidebars[ $k ] = array(
					'id'   => $sidebar['id'],
					'name' => $sidebar['name'],
				);
			}
		}

		wp_localize_script(
			'canvas-block-widgetized-area-editor-script', 'canvasWidgetizedBlock', array(
				'sidebars' => $sidebars,
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
			'name'          => 'canvas/widgetized-area',
			'title'         => esc_html__( 'Widgetized Area', 'canvas' ),
			'category'      => 'canvas',
			'keywords'      => array(),
			'icon'          => '
				<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M3 13H11V15H3V13ZM3 17H11V19H3V17ZM3 9H11V11H3V9ZM3 5H11V7H3V5ZM19 7V17H15V7H19ZM21 5H13V19H21V5Z" fill="currentColor"/>
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

			'sections'      => array(),
			'layouts'       => array(),

			// Set fields just for add block attributes.
			// Editor render for this block is custom JSX
			// so we don't need to render fields automatically.
			'fields'        => array(
				array(
					'key'     => 'area',
					'type'    => 'type-string',
					'default' => '',
				),
			),
			'template'      => dirname( __FILE__ ) . '/block-widgetized-area/render.php',

			// enqueue registered scripts/styles.
			'editor_script' => 'canvas-block-widgetized-area-editor-script',
		);

		return $blocks;
	}
}

new CNVS_Block_Widgetized_Area();
