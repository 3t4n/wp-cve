<?php
/**
 * Megaone_Theme_Compatibility setup
 *
 * @package xpro-theme-builder
 */

/**
 * Megaone theme compatibility.
 */
class Megaone_Theme_Compatibility {

	/**
	 * Instance of Megaone_Theme_Compatibility.
	 *
	 * @var Megaone_Theme_Compatibility
	 */
	private static $instance;

	/**
	 *  Initiator
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new Megaone_Theme_Compatibility();

			add_action( 'wp', array( self::$instance, 'hooks' ) );
		}

		return self::$instance;
	}

	/**
	 * Run all the Actions / Filters.
	 */
	public function hooks() {

		if ( get_post_type() === 'xpro-themer' || ( \Elementor\Plugin::$instance->preview->is_preview_mode() && xpro_theme_builder_is_singular_enabled() ) ) {
			add_filter( 'single_template', array( $this, 'blank_template' ) );
			return;
		}

		$header_meta = megaone_get_meta( 'megaone-main-header-display' );
		$footer_meta = megaone_get_meta( 'megaone-footer-layout' );

		if ( xpro_theme_builder_header_enabled() && 'disabled' !== $header_meta ) {
			remove_action( 'megaone_header', 'megaone_construct_header' );
			add_action( 'megaone_header', 'xpro_theme_builder_render_header' );
		}

		if ( xpro_theme_builder_footer_enabled() && 'disabled' !== $footer_meta ) {
			remove_action( 'megaone_footer', 'megaone_construct_footer' );
			add_action( 'megaone_footer', 'xpro_theme_builder_render_footer' );
		}

		if ( xpro_theme_builder_is_singular_enabled() ) {
			remove_action( 'megaone_content_before', 'megaone_construct_content_before' );
			remove_action( 'megaone_content_after', 'megaone_construct_content_after' );
			remove_action( 'megaone_title_wrapper', 'megaone_construct_title_wrapper' );
			remove_action( 'megaone_content_loop', 'megaone_construct_content_loop' );
			add_filter( 'page_template', array( $this, 'empty_template' ) );
			add_filter( 'single_template', array( $this, 'empty_template' ) );
			add_filter( '404_template', array( $this, 'empty_template' ) );
			add_filter( 'frontpage_template', array( $this, 'empty_template' ) );

			if ( defined( 'WOOCOMMERCE_VERSION' ) && ( is_product() || is_cart() || is_checkout() || is_account_page() ) ) {
				add_action( 'template_redirect', array( $this, 'woo_template' ), 999 );
				add_action( 'template_include', array( $this, 'woo_template' ), 999 );
			}
		}

		if ( xpro_theme_builder_is_archive_enabled() ) {

			remove_action( 'megaone_content_before', 'megaone_construct_content_before' );
			remove_action( 'megaone_content_after', 'megaone_construct_content_after' );
			remove_action( 'megaone_title_wrapper', 'megaone_construct_title_wrapper' );
			remove_action( 'megaone_content_loop', 'megaone_construct_content_loop' );
			add_filter( 'search_template', array( $this, 'empty_template' ) );
			add_filter( 'date_template', array( $this, 'empty_template' ) );
			add_filter( 'author_template', array( $this, 'empty_template' ) );
			add_filter( 'archive_template', array( $this, 'empty_template' ) );
			add_filter( 'category_template', array( $this, 'empty_template' ) );
			add_filter( 'tag_template', array( $this, 'empty_template' ) );
			add_filter( 'home_template', array( $this, 'empty_template' ) );

			if ( defined( 'WOOCOMMERCE_VERSION' ) && is_shop() || ( is_tax( 'product_cat' ) && is_product_category() ) || ( is_tax( 'product_tag' ) && is_product_tag() ) ) {
				add_action( 'template_redirect', array( $this, 'woo_template' ), 999 );
				add_action( 'template_include', array( $this, 'woo_template' ), 999 );
			}
		}
	}

	public function blank_template( $template ) {

		global $post;

		if ( file_exists( XPRO_THEME_BUILDER_DIR . 'inc/templates/blank.php' ) ) {
			return XPRO_THEME_BUILDER_DIR . 'inc/templates/blank.php';
		}

		return $template;
	}

	public function empty_template( $template ) {

		if ( file_exists( XPRO_THEME_BUILDER_DIR . 'inc/templates/empty.php' ) ) {
			return XPRO_THEME_BUILDER_DIR . 'inc/templates/empty.php';
		}
		return $template;
	}

	public function woo_template( $template ) {
		if ( file_exists( XPRO_THEME_BUILDER_DIR . 'inc/templates/woo.php' ) ) {
			return XPRO_THEME_BUILDER_DIR . 'inc/templates/woo.php';
		}
		return $template;
	}

}

Megaone_Theme_Compatibility::instance();
