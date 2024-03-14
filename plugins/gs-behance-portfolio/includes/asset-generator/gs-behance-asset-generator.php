<?php
namespace GSBEH;
use GSPLUGINS\GS_Asset_Generator_Base;

/**
 * Protect direct access
 */
if ( ! defined( 'ABSPATH' ) ) exit;

class GS_Behance_Asset_Generator extends GS_Asset_Generator_Base {

	private static $instance = null;

	public static function getInstance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function get_assets_key() {
		return 'gs-behance-showcase';
	}

	public function generateStyle( $selector, $selector_divi, $targets, $prop, $value ) {
		
		$selectors = [];

		if ( empty($targets) ) return;

		if ( gettype($targets) !== 'array' ) $targets = [$targets];

		if ( !empty($selector_divi) && ( is_divi_active() || is_divi_editor() ) ) {
			foreach ( $targets as $target ) $selectors[] = $selector_divi . $target;
		}

		foreach ( $targets as $target ) $selectors[] = $selector . $target;

		echo wp_strip_all_tags( sprintf( '%s{%s:%s}', join(',', $selectors), $prop, $value ) );

	}

	public function generateCustomCss( $settings, $shortCodeId ) {

		ob_start();

		return ob_get_clean();
	}

	public function generate_assets_data( Array $settings ) {

		if ( empty($settings) || !empty($settings['is_preview']) ) return;

		$this->add_item_in_asset_list( 'styles', 'gs-behance-public', [ 'gs-font-awesome', 'gs-magnific-popup', 'gs-bootstrap-grid', 'gs-owl-carousel' ] );
		$this->add_item_in_asset_list( 'scripts', 'gs-behance-public', [ 'gs-magnific-popup', 'gs-owl-carousel', 'gs-isotope' ] );

		if ( is_divi_active() ) {
			$this->add_item_in_asset_list( 'styles', 'gs-behance-public-divi', ['gs-behance-public'] );
		}

		$css = $this->get_shortcode_custom_css( $settings );
		if ( !empty($css) ) {
			$this->add_item_in_asset_list( 'styles', 'inline', minimize_css_simple($css) );
		}

	}

	public function is_builder_preview() {
		return plugin()->integrations->is_builder_preview();
	}

	public function enqueue_builder_preview_assets() {
		plugin()->scripts->wp_enqueue_style_all( 'public', ['gs-behance-public-divi'] );
		plugin()->scripts->wp_enqueue_script_all( 'public' );
		$this->enqueue_prefs_custom_css();
	}

	public function maybe_force_enqueue_assets( Array $settings ) {
		
		$exclude = ['gs-behance-public-divi'];
		if ( is_divi_active() ) $exclude = [];
		
		plugin()->scripts->wp_enqueue_style_all( 'public', $exclude );
		plugin()->scripts->wp_enqueue_script_all( 'public' );

		// Shortcode Generated CSS
		$css = $this->get_shortcode_custom_css( $settings );
		$this->wp_add_inline_style( $css );
		
		// Prefs Custom CSS
		$this->enqueue_prefs_custom_css();

	}

	public function get_shortcode_custom_css( $settings ) {
		return $this->generateCustomCss( $settings, $settings['id'] );
	}

	public function get_prefs_custom_css() {
		return minimize_css_simple( plugin()->builder->get( 'gsbeh_shortcode_prefs' ) );
	}

	public function enqueue_prefs_custom_css() {
		$this->wp_add_inline_style( $this->get_prefs_custom_css() );
	}

	public function wp_add_inline_style( $css ) {
		if ( !empty($css) ) $css = minimize_css_simple($css);
		if ( !empty($css) ) wp_add_inline_style( 'gs-behance-public', $css );
	}

	public function enqueue_plugin_assets( $main_post_id, $assets = [] ) {

		if ( empty($assets) || empty($assets['styles']) || empty($assets['scripts']) ) return;

		foreach ( $assets['styles'] as $asset => $data ) {
			if ( $asset == 'inline' ) {
				if ( !empty($data) ) wp_add_inline_style( 'gs-behance-public', $data );
			} else {
				Scripts::add_dependency_styles( $asset, $data );
			}
		}

		foreach ( $assets['scripts'] as $asset => $data ) {
			if ( $asset == 'inline' ) {
				if ( !empty($data) ) wp_add_inline_script( 'gs-behance-public', $data );
			} else {
				Scripts::add_dependency_scripts( $asset, $data );
			}
		}

		wp_enqueue_style( 'gs-behance-public' );
		wp_enqueue_script( 'gs-behance-public' );

		if ( is_divi_active() ) {
			wp_enqueue_style( 'gs-behance-public-divi' );
		}

		$this->enqueue_prefs_custom_css();
	}
}

if ( ! function_exists( 'gsBehanceAssetGenerator' ) ) {
	function gsBehanceAssetGenerator() {
		return GS_Behance_Asset_Generator::getInstance(); 
	}
}

// Must inilialized for the hooks
gsBehanceAssetGenerator();
