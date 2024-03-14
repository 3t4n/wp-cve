<?php
/**
 * Alert Block.
 *
 * @package Canvas
 */

/**
 * Initialize Alert block.
 */
class CNVS_Block_Alert {

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
			'canvas-block-alert-editor-script',
			plugins_url( 'block-alert/block.js', __FILE__ ),
			array( 'wp-blocks', 'wp-components', 'wp-element', 'wp-i18n', 'wp-editor', 'lodash', 'jquery' ),
			filemtime( plugin_dir_path( __FILE__ ) . 'block-alert/block.js' ),
			true
		);

		wp_register_style(
			'canvas-block-alert-editor-style',
			plugins_url( 'block-alert/block-editor.css', __FILE__ ),
			array(),
			filemtime( plugin_dir_path( __FILE__ ) . 'block-alert/block-editor.css' )
		);

		wp_style_add_data( 'canvas-block-alert-editor-style', 'rtl', 'replace' );

		// Styles.
		wp_register_style(
			'canvas-block-alert-style',
			plugins_url( 'block-alert/block.css', __FILE__ ),
			array(),
			filemtime( plugin_dir_path( __FILE__ ) . 'block-alert/block.css' )
		);

		wp_style_add_data( 'canvas-block-alert-style', 'rtl', 'replace' );

		// Scripts.
		wp_register_script( 'canvas-block-alert-script', plugin_dir_url( __FILE__ ) . 'block-alert/public-block-alert.js', array( 'jquery' ), cnvs_get_setting( 'version' ), true );
	}

	/**
	 * Register block
	 *
	 * @param array $blocks all registered blocks.
	 * @return array
	 */
	public function register_block_type( $blocks ) {
		$blocks[] = array(
			'name'          => 'canvas/alert',
			'title'         => esc_html__( 'Alert', 'canvas' ),
			'description'   => '',
			'category'      => 'canvas',
			'keywords'      => array(),
			'icon'          => '
				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
					<path d="M11 15H13V17H11V15ZM11 7H13V13H11V7ZM11.99 2C6.47 2 2 6.48 2 12C2 17.52 6.47 22 11.99 22C17.52 22 22 17.52 22 12C22 6.48 17.52 2 11.99 2ZM12 20C7.58 20 4 16.42 4 12C4 7.58 7.58 4 12 4C16.42 4 20 7.58 20 12C20 16.42 16.42 20 12 20Z" />
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
					'name'  => 'cnvs-block-alert-primary',
					'label' => esc_html__( 'Primary', 'canvas' ),
				),
				array(
					'name'  => 'cnvs-block-alert-success',
					'label' => esc_html__( 'Success', 'canvas' ),
				),
				array(
					'name'  => 'cnvs-block-alert-info',
					'label' => esc_html__( 'Info', 'canvas' ),
				),
				array(
					'name'  => 'cnvs-block-alert-warning',
					'label' => esc_html__( 'Warning', 'canvas' ),
				),
				array(
					'name'  => 'cnvs-block-alert-danger',
					'label' => esc_html__( 'Danger', 'canvas' ),
				),
				array(
					'name'  => 'cnvs-block-alert-dark',
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
					'key'     => 'dismissible',
					'label'   => esc_html__( 'Dismissible', 'canvas' ),
					'type'    => 'toggle',
					'default' => false,
					'section' => 'general',
				),
			),
			'template'      => dirname( __FILE__ ) . '/block-alert/render.php',

			// enqueue registered scripts/styles.
			'style'         => 'canvas-block-alert-style',
			'script'        => is_admin() ? '' : 'canvas-block-alert-script',
			'editor_style'  => 'canvas-block-alert-editor-style',
			'editor_script' => 'canvas-block-alert-editor-script',
		);

		return $blocks;
	}
}

new CNVS_Block_Alert();
