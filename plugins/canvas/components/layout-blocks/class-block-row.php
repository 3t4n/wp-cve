<?php
/**
 * Row Block.
 *
 * @package Canvas
 */

/**
 * Initialize Row block.
 */
class CNVS_Block_Row {

	/**
	 * Initialize
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'init' ) );
		add_filter( 'canvas_register_block_type', array( $this, 'register_block_type' ) );
		add_filter( 'canvas_blocks_dynamic_css_canvas/row', array( $this, 'row_dynamic_styles' ), 10, 2 );
		add_filter( 'canvas_blocks_dynamic_css_canvas/column', array( $this, 'column_dynamic_styles' ), 10, 2 );
		add_filter( 'canvas_blocks_dynamic_css_spacings_canvas/column', array( $this, 'spacings_dynamic_styles' ), 10, 4 );
	}

	/**
	 * Enqueue the block's assets for the editor.
	 */
	public function init() {
		// Editor Scripts.
		wp_register_script(
			'canvas-block-row-editor-script',
			plugins_url( 'block-row/block.js', __FILE__ ),
			array( 'wp-blocks', 'wp-components', 'wp-element', 'wp-i18n', 'wp-editor', 'lodash', 'jquery' ),
			filemtime( plugin_dir_path( __FILE__ ) . 'block-row/block.js' ),
			true
		);

		wp_register_script(
			'canvas-block-column-editor-script',
			plugins_url( 'block-column/block.js', __FILE__ ),
			array( 'wp-blocks', 'wp-components', 'wp-element', 'wp-i18n', 'wp-editor' ),
			filemtime( plugin_dir_path( __FILE__ ) . 'block-column/block.js' ),
			true
		);

		// Editor Styles.
		wp_register_style(
			'canvas-block-row-editor-style',
			plugins_url( 'block-row/block-row-editor.css', __FILE__ ),
			array(),
			filemtime( plugin_dir_path( __FILE__ ) . 'block-row/block-row-editor.css' )
		);

		wp_style_add_data( 'canvas-block-row-editor-style', 'rtl', 'replace' );

		// Styles.
		wp_register_style(
			'canvas-block-row-style',
			plugins_url( 'block-row/block-row.css', __FILE__ ),
			array(),
			filemtime( plugin_dir_path( __FILE__ ) . 'block-row/block-row.css' )
		);

		wp_style_add_data( 'canvas-block-row-style', 'rtl', 'replace' );
	}

	/**
	 * Register block
	 *
	 * @param array $blocks all registered blocks.
	 * @return array
	 */
	public function register_block_type( $blocks ) {
		$blocks[] = array(
			'name'          => 'canvas/row',
			'title'         => esc_html__( 'Row', 'canvas' ),
			'description'   => esc_html__( '12-columns system for your content.', 'canvas' ),
			'category'      => 'canvas',
			'keywords'      => array( 'row', 'grid', 'columns' ),
			'icon'          => '
				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
					<path d="M19.5 19.5L15.5 19.5L15.5 4.5L19.5 4.5L19.5 19.5ZM9.5 19.5L5.5 19.5L5.5 4.5L9.5 4.5L9.5 19.5ZM21.5 20.5L21.5 3.5C21.5 2.95 21.05 2.5 20.5 2.5L14.5 2.5C13.95 2.5 13.5 2.95 13.5 3.5L13.5 20.5C13.5 21.05 13.95 21.5 14.5 21.5L20.5 21.5C21.05 21.5 21.5 21.05 21.5 20.5ZM11.5 20.5L11.5 3.5C11.5 2.95 11.05 2.5 10.5 2.5L4.5 2.5C3.95 2.5 3.5 2.95 3.5 3.5L3.5 20.5C3.5 21.05 3.95 21.5 4.5 21.5L10.5 21.5C11.05 21.5 11.5 21.05 11.5 20.5Z" />
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
			'fields'        => array(
				array(
					'key'     => 'verticalAlignment',
					'type'    => 'type-string',
					'default' => '',
				),
				array(
					'key'     => 'columns',
					'type'    => 'number',
					'label'   => esc_html__( 'Columns', 'canvas' ),
					'min'     => 1,
					'max'     => 6,
					'default' => 2,
				),
				array(
					'key'        => 'gap',
					'type'       => 'number',
					'label'      => esc_html__( 'Gap', 'canvas' ),
					'min'        => 0,
					'max'        => 100,
					'default'    => 30,
					'responsive' => true,
				),
			),
			'template'      => dirname( __FILE__ ) . '/block-row/render.php',

			// enqueue registered scripts/styles.
			'style'         => is_admin() ? '' : 'canvas-block-row-style',
			'editor_script' => 'canvas-block-row-editor-script',
			'editor_style'  => 'canvas-block-row-editor-style',
		);

		$blocks[] = array(
			'name'          => 'canvas/column',
			'title'         => esc_html__( 'Column', 'canvas' ),
			'description'   => '',
			'category'      => 'canvas',
			'keywords'      => array(),
			'icon'          => '
				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
					<path d="M19.5 19.5L15.5 19.5L15.5 4.5L19.5 4.5L19.5 19.5ZM9.5 19.5L5.5 19.5L5.5 4.5L9.5 4.5L9.5 19.5ZM21.5 20.5L21.5 3.5C21.5 2.95 21.05 2.5 20.5 2.5L14.5 2.5C13.95 2.5 13.5 2.95 13.5 3.5L13.5 20.5C13.5 21.05 13.95 21.5 14.5 21.5L20.5 21.5C21.05 21.5 21.5 21.05 21.5 20.5ZM11.5 20.5L11.5 3.5C11.5 2.95 11.05 2.5 10.5 2.5L4.5 2.5C3.95 2.5 3.5 2.95 3.5 3.5L3.5 20.5C3.5 21.05 3.95 21.5 4.5 21.5L10.5 21.5C11.05 21.5 11.5 21.05 11.5 20.5Z" />
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
				'canvas/row',
			),
			'styles'        => array(),
			'location'      => array(),
			'sections'      => array(),
			'layouts'       => array(),
			'fields'        => array(
				array(
					'key'              => 'size',
					'label'            => esc_html__( 'Size', 'canvas' ),
					'type'             => 'number',
					'min'              => 1,
					'max'              => 12,
					'default'          => '',
					'default_notebook' => 12,
					'default_laptop'   => 12,
					'default_tablet'   => 12,
					'default_mobile'   => 12,
					'responsive'       => true,
				),
				array(
					'key'        => 'order',
					'label'      => esc_html__( 'Order', 'canvas' ),
					'type'       => 'number',
					'min'        => 1,
					'max'        => 12,
					'default'    => '',
					'responsive' => true,
				),
				array(
					'key'        => 'minHeight',
					'label'      => esc_html__( 'Minimum Height', 'canvas' ),
					'type'       => 'dimension',
					'default'    => '',
					'responsive' => true,
				),
				array(
					'key'        => 'verticalAlign',
					'label'      => esc_html__( 'Vertical Align', 'canvas' ),
					'type'       => 'icon-buttons',
					'choices'    => array(
						'top'    => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" role="img" aria-hidden="true" focusable="false"><path fill="none" d="M0 0h24v24H0V0z"></path><path d="M8 11h3v10h2V11h3l-4-4-4 4zM4 3v2h16V3H4z"></path></svg>',
						'center' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" role="img" aria-hidden="true" focusable="false"><path fill="none" d="M0 0h24v24H0V0z"></path><path d="M8 19h3v4h2v-4h3l-4-4-4 4zm8-14h-3V1h-2v4H8l4 4 4-4zM4 11v2h16v-2H4z"></path></svg>',
						'bottom' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" role="img" aria-hidden="true" focusable="false"><path fill="none" d="M0 0h24v24H0V0z"></path><path d="M16 13h-3V3h-2v10H8l4 4 4-4zM4 19v2h16v-2H4z"></path></svg>',
					),
					'default'    => '',
					'responsive' => true,
				),

				// Colors.
				array(
					'key'     => 'textColor',
					'label'   => esc_html__( 'Text Color', 'canvas' ),
					'section' => esc_html__( 'Color Settings' ),
					'type'    => 'color',
					'default' => '',
					'output'  => array(
						array(
							'element'  => '$ .cnvs-block-column-inner',
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
							'element'  => '$ .cnvs-block-column-inner',
							'property' => 'background-color',
							'suffix'   => '!important',
						),
					),
				),
			),
			'template'      => dirname( __FILE__ ) . '/block-column/render.php',

			// enqueue registered scripts/styles.
			'editor_script' => 'canvas-block-column-editor-script',
		);

		return $blocks;
	}

	/**
	 * Change row styles
	 *
	 * @param string $return   Current return.
	 * @param string $block    Block data.
	 * @return string
	 */
	public function row_dynamic_styles( $return, $block ) {
		$attributes        = $block['attrs'];
		$registered_blocks = WP_Block_Type_Registry::get_instance()->get_all_registered();

		// prepare defaults.
		if ( isset( $registered_blocks['canvas/row'] ) && isset( $registered_blocks['canvas/row']->attributes ) ) {
			foreach ( $registered_blocks['canvas/row']->attributes as $k => $attrs ) {
				if ( isset( $attrs['default'] ) && ! isset( $attributes[ $k ] ) ) {
					$attributes[ $k ] = $attrs['default'];
				}
			}
		}

		if ( isset( $attributes['canvasClassName'] ) ) {
			$class_name = $attributes['canvasClassName'];

			ob_start();
			require plugin_dir_path( __FILE__ ) . '/block-row/styles.php';
			$return .= ob_get_clean();
		}

		return $return;
	}

	/**
	 * Change column styles
	 *
	 * @param string $return   Current return.
	 * @param string $block    Block data.
	 * @return string
	 */
	public function column_dynamic_styles( $return, $block ) {
		$attributes        = $block['attrs'];
		$registered_blocks = WP_Block_Type_Registry::get_instance()->get_all_registered();

		// prepare defaults.
		if ( isset( $registered_blocks['canvas/column'] ) && isset( $registered_blocks['canvas/column']->attributes ) ) {
			foreach ( $registered_blocks['canvas/column']->attributes as $k => $attrs ) {
				if ( isset( $attrs['default'] ) && ! isset( $attributes[ $k ] ) ) {
					$attributes[ $k ] = $attrs['default'];
				}
			}
		}

		if ( isset( $attributes['canvasClassName'] ) ) {
			$class_name = $attributes['canvasClassName'];

			ob_start();
			require plugin_dir_path( __FILE__ ) . '/block-column/styles.php';
			$return .= ob_get_clean();
		}

		return $return;
	}

	/**
	 * Change spacings styles
	 *
	 * @param string $return   Current return.
	 * @param string $selector Current selector.
	 * @param string $styles   Current styles.
	 * @param string $block    Block data.
	 * @return string
	 */
	public function spacings_dynamic_styles( $return, $selector, $styles, $block ) {
		$return = $selector . ' .cnvs-block-column-inner { ' . $styles . ' } ';

		return $return;
	}
}

new CNVS_Block_Row();
