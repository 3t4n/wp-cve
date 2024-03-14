<?php
/**
 * Slider Block.
 *
 * @package Canvas
 */

/**
 * The initialize block.
 */
class CNVS_Block_Slider_Gallery {

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
		wp_register_script( 'flickity', plugin_dir_url( __FILE__ ) . 'block/flickity.pkgd.min.js', array( 'jquery', 'imagesloaded' ), cnvs_get_setting( 'version' ), true );

		wp_register_script( 'canvas-slider-gallery', plugin_dir_url( __FILE__ ) . 'block/public-block-slider-gallery.js', array( 'jquery', 'flickity', 'imagesloaded' ), cnvs_get_setting( 'version' ), true );

		wp_localize_script(
			'canvas-slider-gallery', 'canvas_sg_flickity', array(
				'page_info_sep' => esc_html__( ' of ', 'canvas' ),
			)
		);

		wp_register_script(
			'canvas-slider-gallery-block-editor-script',
			plugins_url( 'block/block.js', __FILE__ ),
			array( 'wp-blocks', 'wp-components', 'wp-element', 'wp-i18n', 'wp-editor', 'lodash', 'jquery', 'flickity', 'canvas-slider-gallery' ),
			filemtime( plugin_dir_path( __FILE__ ) . 'block/block.js' ),
			true
		);

		// Styles.
		wp_register_style(
			'canvas-slider-gallery-block-style',
			plugins_url( 'block/block-slider-gallery.css', __FILE__ ),
			array(),
			filemtime( plugin_dir_path( __FILE__ ) . 'block/block-slider-gallery.css' )
		);

		wp_style_add_data( 'canvas-slider-gallery-block-style', 'rtl', 'replace' );
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
			'name'          => 'canvas/slider-gallery',
			'title'         => esc_html__( 'Slider Gallery', 'canvas' ),
			'category'      => 'canvas',
			'keywords'      => array(),
			'icon'          => '
				<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M2 7H6V18H2V7ZM7 20H17V5H7V20ZM9 7H15V18H9V7ZM18 7H22V18H18V7Z" fill="currentColor" />
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
					'key'     => 'showPrevNextButtons',
					'label'   => esc_html__( 'Display Previous & Next Buttons', 'canvas' ),
					'section' => 'general',
					'type'    => 'toggle',
					'default' => true,
				),
				array(
					'key'     => 'showBullets',
					'label'   => esc_html__( 'Display Bullets', 'canvas' ),
					'section' => 'general',
					'type'    => 'toggle',
					'default' => true,
				),
				array(
					'key'     => 'showCaptions',
					'label'   => esc_html__( 'Display Captions', 'canvas' ),
					'section' => 'general',
					'type'    => 'toggle',
					'default' => false,
				),
			),
			'template'      => dirname( __FILE__ ) . '/block/render.php',

			// enqueue registered scripts/styles.
			'style'         => 'canvas-slider-gallery-block-style',
			'script'        => is_admin() ? '' : 'canvas-slider-gallery',
			'editor_style'  => 'canvas-slider-gallery-block-style',
			'editor_script' => 'canvas-slider-gallery-block-editor-script',
		);

		return $blocks;
	}
}

new CNVS_Block_Slider_Gallery();
