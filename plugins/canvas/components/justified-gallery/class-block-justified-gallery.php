<?php
/**
 * Justified Gallery Block.
 *
 * @package Canvas
 */

/**
 * Initialize Posts block.
 */
class CNVS_Block_Justified_Gallery {

	/**
	 * Initialize
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'block' ) );
		add_filter( 'canvas_register_block_type', array( $this, 'register_block_type' ) );
	}

	/**
	 * Enqueue the block's assets for the editor.
	 */
	public function block() {
		// Scripts.
		wp_register_script( 'justifiedgallery', plugin_dir_url( __FILE__ ) . 'block/jquery.justifiedGallery.min.js', array( 'jquery' ), cnvs_get_setting( 'version' ), true );

		wp_register_script( 'canvas-justified-gallery', plugin_dir_url( __FILE__ ) . 'block/public-block-justified-gallery.js', array( 'jquery', 'justifiedgallery' ), cnvs_get_setting( 'version' ), true );

		wp_localize_script(
			'canvas-justified-gallery', 'canvasJG', array(
				'rtl' => is_rtl(),
			)
		);

		wp_register_script(
			'canvas-justified-gallery-block-editor-script',
			plugins_url( 'block/block.js', __FILE__ ),
			array( 'wp-blocks', 'wp-components', 'wp-element', 'wp-i18n', 'wp-editor', 'lodash', 'jquery', 'justifiedgallery', 'canvas-justified-gallery' ),
			filemtime( plugin_dir_path( __FILE__ ) . 'block/block.js' ),
			true
		);

		// Styles.
		wp_register_style(
			'canvas-justified-gallery-block-style',
			plugins_url( 'block/block-justified-gallery.css', __FILE__ ),
			array(),
			filemtime( plugin_dir_path( __FILE__ ) . 'block/block-justified-gallery.css' )
		);

		wp_style_add_data( 'canvas-justified-gallery-block-style', 'rtl', 'replace' );
	}

	/**
	 * Register block
	 *
	 * @param array $blocks all registered blocks.
	 * @return array
	 */
	public function register_block_type( $blocks ) {
		$intermediate_image_sizes = get_intermediate_image_sizes();
		$image_sizes              = array();

		foreach ( $intermediate_image_sizes as $size ) {
			$image_sizes[ $size ] = $size;
		}

		$blocks[] = array(
			'name'          => 'canvas/justified-gallery',
			'title'         => esc_html__( 'Justified Gallery', 'canvas' ),
			'category'      => 'canvas',
			'keywords'      => array(),
			'icon'          => '
				<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M3 3H15V10H3V3ZM19 3H21V10H19V3ZM3 14H8V21H3L3 14ZM12 14H21V21H12V14Z" stroke="currentColor" fill="none" stroke-width="2"/>
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
				'general'        => array(
					'title'    => esc_html__( 'Block Settings', 'canvas' ),
					'priority' => 5,
					'open'     => true,
				),
			),
			'layouts'       => array(),
			'fields'        => array(
				array(
					'key'     => 'images',
					'label'   => esc_html__( 'Images', 'canvas' ),
					'section' => 'general',
					'type'    => 'gallery',
					'items'   => array(
						'type' => 'integer',
					),
				),
				array(
					'key'     => 'imageSize',
					'label'   => esc_html__( 'Images Size', 'canvas' ),
					'section' => 'general',
					'type'    => 'select',
					'default' => 'thumbnail',
					'choices' => $image_sizes,
				),
				array(
					'key'     => 'linkTo',
					'label'   => esc_html__( 'Link To', 'canvas' ),
					'section' => 'general',
					'type'    => 'select',
					'default' => 'file',
					'choices' => array(
						'post' => esc_html__( 'Attachment Page', 'canvas' ),
						'file' => esc_html__( 'Media File', 'canvas' ),
						'none' => esc_html__( 'None', 'canvas' ),
					),
				),
				array(
					'key'     => 'rowHeight',
					'label'   => esc_html__( 'Row Height', 'canvas' ),
					'section' => 'general',
					'type'    => 'number',
					'min'     => 50,
					'max'     => 5000,
					'default' => 275,
				),
				array(
					'key'     => 'maxRowHeight',
					'label'   => esc_html__( 'Max Row Height', 'canvas' ),
					'help'    => esc_html__( 'This option could be a false or negative value to keep it disabled. Could be a number (e.g 200) which specifies the maximum row height in pixel. Alternatively, a string which specifies a percentage (e.g. "200%" means that the row height can\'t exceed 2 * rowHeight).', 'canvas' ),
					'section' => 'general',
					'type'    => 'text',
					'default' => '-1',
				),
				array(
					'key'     => 'margins',
					'label'   => esc_html__( 'Margins', 'canvas' ),
					'section' => 'general',
					'type'    => 'number',
					'min'     => 0,
					'max'     => 60,
					'default' => 10,
				),
				array(
					'key'     => 'border',
					'label'   => esc_html__( 'Border', 'canvas' ),
					'section' => 'general',
					'type'    => 'number',
					'min'     => -60,
					'max'     => 60,
					'default' => -10,
				),
				array(
					'key'     => 'lastRow',
					'label'   => esc_html__( 'Last Row', 'canvas' ),
					'section' => 'general',
					'type'    => 'select',
					'choices' => array(
						'nojustify' => esc_html__( 'No Justify', 'canvas' ),
						'justify'   => esc_html__( 'Justify', 'canvas' ),
						'hide'      => esc_html__( 'Hide', 'canvas' ),
					),
					'default' => 'nojustify',
				),
				array(
					'key'     => 'showCaptions',
					'label'   => esc_html__( 'Display Captions', 'canvas' ),
					'section' => 'general',
					'type'    => 'toggle',
					'default' => false,
				),
				array(
					'key'     => 'randomize',
					'label'   => esc_html__( 'Randomize', 'canvas' ),
					'section' => 'general',
					'type'    => 'toggle',
					'default' => false,
				),
			),
			'template'      => dirname( __FILE__ ) . '/block/render.php',

			// enqueue registered scripts/styles.
			'style'         => 'canvas-justified-gallery-block-style',
			'script'        => is_admin() ? '' : 'canvas-justified-gallery',
			'editor_style'  => 'canvas-justified-gallery-block-style',
			'editor_script' => 'canvas-justified-gallery-block-editor-script',
		);

		return $blocks;
	}
}

new CNVS_Block_Justified_Gallery();
