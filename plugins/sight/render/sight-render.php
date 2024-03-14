<?php
/**
 * Render Front End.
 *
 * @link       https://codesupply.co
 * @since      1.0.0
 *
 * @package    Sight
 */

/**
 * The initialize block.
 */
class Sight_Frontend_Render {

	/**
	 * Initialize
	 */
	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_filter( 'powerkit_pinit_exclude_selectors', array( $this, 'sight_pinit_exclude_selector' ) );
		add_filter( 'powerkit_lightbox_exclude_selectors', array( $this, 'sight_lightbox_exclude_selector' ) );
		add_filter( 'pk_toc_exclude', array( $this, 'sight_toc_exclude_selectors' ) );
	}

	/**
	 * Enqueue the block's assets for the editor.
	 */
	public function enqueue_scripts() {

		// Scripts.
		wp_register_script(
			'magnific-popup',
			SIGHT_URL . 'render/js/jquery.magnific-popup.min.js',
			array( 'jquery', 'imagesloaded' ),
			filemtime( SIGHT_PATH . 'render/js/jquery.magnific-popup.min.js' ),
			true
		);

		wp_register_script(
			'sight-block-script',
			SIGHT_URL . 'render/js/sight.js',
			array( 'jquery', 'imagesloaded', 'magnific-popup' ),
			filemtime( SIGHT_PATH . 'render/js/sight.js' ),
			true
		);

		wp_enqueue_script( 'sight-block-script' );

		wp_localize_script( 'sight-block-script', 'sight_lightbox_localize', array(
			'text_previous' => esc_html__( 'Previous', 'sight' ),
			'text_next'     => esc_html__( 'Next', 'sight' ),
			'text_close'    => esc_html__( 'Close', 'sight' ),
			'text_loading'  => esc_html__( 'Loading', 'sight' ),
			'text_counter'  => esc_html__( 'of', 'sight' ),
		) );

		// Styles.
		wp_enqueue_style(
			'magnific-popup',
			SIGHT_URL . 'render/css/magnific-popup.css',
			array(),
			filemtime( SIGHT_PATH . 'render/css/magnific-popup.css' )
		);

		wp_enqueue_style(
			'sight',
			SIGHT_URL . 'render/css/sight.css',
			array(),
			filemtime( SIGHT_PATH . 'render/css/sight.css' )
		);

		wp_enqueue_style(
			'sight-common',
			SIGHT_URL . 'render/css/sight-common.css',
			array(),
			filemtime( SIGHT_PATH . 'render/css/sight-common.css' )
		);

		wp_enqueue_style(
			'sight-lightbox',
			SIGHT_URL . 'render/css/sight-lightbox.css',
			array(),
			filemtime( SIGHT_PATH . 'render/css/sight-lightbox.css' )
		);

		wp_enqueue_style(
			'sight-layout-standard',
			SIGHT_URL . 'render/css/sight-layout-standard.css',
			array(),
			filemtime( SIGHT_PATH . 'render/css/sight-layout-standard.css' )
		);

		wp_style_add_data( 'sight', 'rtl', 'replace' );
		wp_style_add_data( 'sight-common', 'rtl', 'replace' );
		wp_style_add_data( 'sight-layout-standard', 'rtl', 'replace' );
	}

	/**
	 * PinIt exclude selectors
	 *
	 * @param string $selectors List selectors.
	 */
	public function sight_pinit_exclude_selector( $selectors ) {
		$selectors[] = '.sight-portfolio-entry-link-page';

		return $selectors;
	}

	/**
	 * Powerkit Lightbox exclude selector
	 *
	 * @param string $selectors List selectors.
	 */
	public function sight_lightbox_exclude_selector( $selectors ) {
		$selectors[] = '.sight-portfolio-area';

		return $selectors;
	}

	/**
	 * Add exclude selectors of TOC
	 *
	 * @param string $list List selectors.
	 */
	public function sight_toc_exclude_selectors( $list ) {
		$list .= '|.sight-portfolio-entry__heading';

		return $list;
	}

}

new Sight_Frontend_Render();
