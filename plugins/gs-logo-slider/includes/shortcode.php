<?php 
namespace GSLOGO;

use function GSLOGOPRO\is_plugin_loaded;

if ( ! defined( 'ABSPATH' ) ) exit;

class Shortcode {
	
	public function __construct() {
		add_shortcode( 'gslogo', [ $this, 'register_gslogo_shortcode_builder' ] );
	}

	public function register_gslogo_shortcode_builder( $atts ) {

		if ( empty($atts['id']) ) {
			return __( 'No shortcode ID found', 'gslogo' );
		}
	
		$is_preview = ! empty($atts['preview']);
	
		$settings = $this->get_shortcode_settings( $atts['id'], $is_preview );
	
		if ( empty($settings) ) return '';
	
		// Cache the $settings from being changed
		$_settings = $settings;
	
		// By default force mode
		$force_asset_load = true;
	
		if ( ! $is_preview ) {
		
			// For Asset Generator
			$main_post_id = gsLogoAssetGenerator()->get_current_page_id();
	
			$asset_data = gsLogoAssetGenerator()->get_assets_data( $main_post_id );
	
			if ( empty($asset_data) ) {
				// Saved assets not found
				// Force load the assets for first time load
				// Generate the assets for later use
				gsLogoAssetGenerator()->generate( $main_post_id, $settings );
			} else {
				// Saved assets found
				// Stop force loading the assets
				// Leave the job for Asset Loader
				$force_asset_load = false;
			}
	
		}
	
		if ( isset($settings['image_size']) && $settings['image_size'] == 'custom' ) {
	
			if ( empty( $settings['custom_image_size_width'] ) || empty( $settings['custom_image_size_width'] ) || empty( $settings['custom_image_size_crop'] ) ) {
				$settings['image_size'] = 'full';
			}
	
		}

		$atts = $settings;

		$atts = change_key( $atts, 'gs_l_title', 'title' );
		$atts = change_key( $atts, 'gs_l_mode', 'mode' );
		$atts = change_key( $atts, 'gs_l_slide_speed', 'speed' );
		$atts = change_key( $atts, 'gs_l_inf_loop', 'inf_loop' );
		$atts = change_key( $atts, 'gs_l_gray', 'logo_color' );
		$atts = change_key( $atts, 'gs_l_theme', 'theme' );
		$atts = change_key( $atts, 'gs_l_tooltip', 'tooltip' );
		
		extract( $atts );
	
		$args = [
			'order'				=> $order,
			'orderby'			=> $orderby,
			'posts_per_page'	=> $posts,
		];
	
		if ( !empty($logo_cat) ) {
	
			$args['tax_query'] = [
				[
					'taxonomy' => 'logo-category',
					'field'    => 'slug',
					'terms'    => explode(',', $logo_cat),
					'operator' => 'IN'
				],
			];
	
		}
	
		$GLOBALS['gs_logo_loop'] = get_gs_logo_query( $args );
	
		$id = empty($id) ? uniqid() : sanitize_key( $id );
	
		if ( $theme == '2rows' ) $theme = 'slider-2rows';
		
		$classes = [
			"gs_logo_area",
			"gs_logo_area_$id",
			$theme
		];
	
		ob_start();

		?>
	
		<div id="<?php echo 'gs_logo_area_' . esc_attr( $id ); ?>" class="<?php echo implode( ' ', $classes ); ?>" style="opacity: 0; visibility: hidden;">
			<div class="gs_logo_area--inner">
	
				<?php
					do_action( 'gs_logo_template_before__loaded', $theme );
	
					if ( $theme == 'slider1' ) {
						include Template_Loader::locate_template( 'gs-logo-theme-slider-1.php' );
					} else if ( $theme == 'grid1' ) {
						include Template_Loader::locate_template( 'gs-logo-theme-grid-1.php' );
					} else if ( $theme == 'list1' ) {
						include Template_Loader::locate_template( 'gs-logo-theme-list-1.php' );
					} else if ( $theme == 'table1' ) {
						include Template_Loader::locate_template( 'gs-logo-theme-table-1.php' );
					} else if ( ! is_pro_active() || ! is_plugin_loaded() ) {
						printf('<div class="gs-logo-template-upgrade"><p>%s</p></div>', __('Please upgrade to pro version to use this template', 'gslogo'));
					}
	
					do_action( 'gs_logo_template_after__loaded', $theme,  $atts );
					
					wp_reset_postdata();
				?>
			</div>
		</div>
	
		<?php
	
		if ( plugin()->integrations->is_builder_preview() || $force_asset_load ) {
	
			gsLogoAssetGenerator()->force_enqueue_assets( $_settings );
			wp_add_inline_script( 'gs-logo-public', "jQuery(document).trigger( 'gslogo:scripts:reprocess' );jQuery(function() { jQuery(document).trigger( 'gslogo:scripts:reprocess' ) })" );

			// Shortcode Custom CSS
			$css = gsLogoAssetGenerator()->get_shortcode_custom_css( $settings );
			if ( !empty($css) ) printf( "<style>%s</style>" , minimize_css_simple($css) );
			
			// Prefs Custom CSS
			$css = gsLogoAssetGenerator()->get_prefs_custom_css();
			if ( !empty($css) ) printf( "<style>%s</style>" , minimize_css_simple($css) );
	
		}
	
		$settings = null; // Free up the memory
	
		return ob_get_clean();
	
	}

	public function get_shortcode_settings($id, $is_preview = false) {

		$default_settings = array_merge( ['id' => $id, 'is_preview' => $is_preview], plugin()->builder->get_shortcode_default_settings() );
	
		if ( $is_preview ) {
			$preview_settings = plugin()->builder->validate_shortcode_settings( get_transient($id) );
			return shortcode_atts( $default_settings, $preview_settings );
		}
	
		$shortcode = plugin()->builder->_get_shortcode($id);
		return shortcode_atts( $default_settings, (array) $shortcode['shortcode_settings'] );
		
	}
}
