<?php
namespace GSLOGO;
use GSPLUGINS\GS_Asset_Generator_Base;

/**
 * Protect direct access
 */
if ( ! defined( 'ABSPATH' ) ) exit;

class GS_Logo_Asset_Generator extends GS_Asset_Generator_Base {

	private static $instance = null;

	public static function getInstance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function get_assets_key() {
		return 'gs-logo-slider';
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

		$selector = '#gs_logo_area_' . $shortCodeId;
		$selector_divi = '#et-boc .et-l div ' . $selector;
		
		if ( !empty( $settings['gs_l_margin'] ) || $settings['gs_l_margin'] == 0 ) {
			$space = intval( $settings['gs_l_margin'] ) / 2;
			$this->generateStyle( $selector, $selector_divi,  ':not(.verticaltickerdown):not(.verticalticker) .gs_logo_container', 'margin-left', '-' . $space . 'px' );
			$this->generateStyle( $selector, $selector_divi,  ':not(.verticaltickerdown):not(.verticalticker) .gs_logo_container', 'margin-right', '-' . $space . 'px' );
			$this->generateStyle( $selector, $selector_divi,  ':not(.verticaltickerdown):not(.verticalticker) .gs_logo_single--wrapper', 'padding', $space . 'px' );
		}
		
		if ( !empty( $settings['gs_logo_filter_align'] ) ) {
			$this->generateStyle( $selector, $selector_divi,  ' ul.gs-logo-filter-cats', 'text-align', $settings['gs_logo_filter_align'] . '!important' );
		}

		if ( ! in_array( $settings['gs_l_theme'], ['list1', 'list2', 'list3', 'list4', 'table1', 'table2', 'table3'] ) ) {

			$min_desk_items = !empty($settings['gs_l_min_logo']) ? absint( $settings['gs_l_min_logo'] ) : 1;
			$width = 100 / $min_desk_items;
			$this->generateStyle( $selector, $selector_divi,  ' .gs_logo_single--wrapper', 'width', $width . '%' );
			
			$min_tab_items = !empty($settings['gs_l_tab_logo']) ? absint( $settings['gs_l_tab_logo'] ) : 1;
			$resWidthLarge = 100 / $min_tab_items;
			echo "@media (max-width: 1023px) {";
			$this->generateStyle( $selector, $selector_divi,  ' .gs_logo_single--wrapper', 'width', $resWidthLarge . '%' );
			echo "}";
			
			$min_mob_items = !empty($settings['gs_l_mob_logo']) ? absint( $settings['gs_l_mob_logo'] ) : 1;
			$resWidthMedium = 100 / $min_mob_items;
			echo "@media (max-width: 767px) {";
			$this->generateStyle( $selector, $selector_divi,  ' .gs_logo_single--wrapper', 'width', $resWidthMedium . '%' );
			echo "}";
		}

		return ob_get_clean();
	}

	public function generate_assets_data( Array $settings ) {

		if ( empty($settings) || !empty($settings['is_preview']) ) return;

		$this->add_item_in_asset_list( 'styles', 'gs-logo-public' );
		$this->add_item_in_asset_list( 'scripts', 'gs-logo-public', ['jquery'] );

		if ( 'slider1' === $settings['gs_l_theme'] ) {
			$this->add_item_in_asset_list( 'styles', 'gs-logo-public', ['gs-swiper'] );
			$this->add_item_in_asset_list( 'scripts', 'gs-logo-public', ['gs-swiper'] );
		}
		
		if ( 'on' === $settings['gs_l_tooltip'] ) {
			$this->add_item_in_asset_list( 'styles', 'gs-logo-public', ['gs-tippyjs'] );
			$this->add_item_in_asset_list( 'scripts', 'gs-logo-public', ['gs-tippyjs'] );
		}

		// Hooked for Pro
		do_action( 'gs_logo_assets_data_generated', $settings );

		if ( is_divi_active() ) {
			$this->add_item_in_asset_list( 'styles', 'gs-logo-divi-public', ['gs-logo-public'] );
		}

		$css = $this->get_shortcode_custom_css( $settings );
		
		if ( !empty($css) ) {
			$this->add_item_in_asset_list( 'styles', 'inline', minimize_css_simple($css) );
		}

	}

	public function enqueue_plugin_assets( $main_post_id, $assets = [] ) {

		if ( empty($assets) || empty($assets['styles']) || empty($assets['scripts']) ) return;

		foreach ( $assets['styles'] as $asset => $data ) {
			if ( $asset == 'inline' ) {
				if ( !empty($data) ) wp_add_inline_style( 'gs-logo-public', $data );
			} else {
				Scripts::add_dependency_styles( $asset, $data );
			}
		}

		foreach ( $assets['scripts'] as $asset => $data ) {
			if ( $asset == 'inline' ) {
				if ( !empty($data) ) wp_add_inline_script( 'gs-logo-public', $data );
			} else {
				Scripts::add_dependency_scripts( $asset, $data );
			}
		}

		wp_enqueue_style( 'gs-logo-public' );
		wp_enqueue_script( 'gs-logo-public' );

		if ( is_divi_active() ) {
			wp_enqueue_style( 'gs-logo-divi-public' );
		}

		$this->enqueue_prefs_custom_css();
	}

	public function is_builder_preview() {
		return plugin()->integrations->is_builder_preview();
	}

	public function enqueue_builder_preview_assets() {
		plugin()->scripts->wp_enqueue_style_all( 'public', ['gs-logo-divi-public'] );
		plugin()->scripts->wp_enqueue_script_all( 'public' );
		$this->enqueue_prefs_custom_css();
	}

	public function maybe_force_enqueue_assets( Array $settings ) {

		$exclude = ['gs-logo-divi-public'];
		if ( is_divi_active() ) $exclude = [];

		plugin()->scripts->wp_enqueue_style_all( 'public', $exclude );
		plugin()->scripts->wp_enqueue_script_all( 'public' );

		// Shortcode Generated CSS
		$css = $this->get_shortcode_custom_css( $settings );
		$this->wp_add_inline_style( $css );
		
		// Prefs Custom CSS
		$this->enqueue_prefs_custom_css();

	}

	public function wp_add_inline_style( $css ) {
		if ( !empty($css) ) $css = minimize_css_simple($css);
		if ( !empty($css) ) wp_add_inline_style( 'gs-logo-public', wp_strip_all_tags($css) );
	}

	public function get_prefs_custom_css() {
		$prefs = plugin()->builder->_get_shortcode_pref( false );
		if ( empty($prefs['gs_logo_slider_custom_css']) ) return '';
		return $prefs['gs_logo_slider_custom_css'];
	}

	public function enqueue_prefs_custom_css() {
		$this->wp_add_inline_style( $this->get_prefs_custom_css() );
	}

	public function get_shortcode_custom_css( $settings ) {
		return $this->generateCustomCss( $settings, $settings['id'] );
	}

}

if ( ! function_exists( 'gsLogoAssetGenerator' ) ) {
	function gsLogoAssetGenerator() {
		return GS_Logo_Asset_Generator::getInstance(); 
	}
}

// Must inilialized for the hooks
gsLogoAssetGenerator();