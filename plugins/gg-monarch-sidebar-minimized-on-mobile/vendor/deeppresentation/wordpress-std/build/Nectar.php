<?php namespace MSMoMDP\Wp;

use MSMoMDP\Std\Html\Html;
use MSMoMDP\Std\Core\Autoloader;

class Nectar {

	public static function init() {
		static $wasInit = false;
		if ( ! $wasInit ) {
			Autoloader::Instance()->require_once(
				array(
					'Shortcode_Processing',
				)
			);
		}
	}

	// Google map API key has to be set in nectar theme options
	// Markers are in form: lat|lng|desc
	public static function get_map_google_shortcode( $mapCenterLat, $mapCenterLng, float $zoom, int $height, bool $zoomEnable = true, array $markers = array() ) {
		$markersStr = implode( PHP_EOL, $markers );
		return '[nectar_gmap map_type="google" zoom="' . $zoom . '" enable_zoom="' . $zoomEnable . '" marker_style="nectar" marker_animation="0" nectar_marker_color="accent-color" size="' . $height . '"
         map_center_lat="' . $mapCenterLat . '" map_center_lng="' . $mapCenterLng . '" map_markers="' . $markersStr . '"]';
	}

	public static function get_heading_with_short_under_line_shortcode( string $title, string $id = '' ) {
		return '[vc_row type="in_container" full_screen_row_position="middle" scene_position="center" text_color="dark" text_align="left"
        overlay_strength="0.3" shape_divider_position="bottom"][vc_column column_padding="no-extra-padding" column_padding_position="all" 
        background_color_opacity="1" background_hover_color_opacity="1" column_link_target="_self" column_shadow="none" column_border_radius="none" 
        width="1/1" tablet_width_inherit="default" tablet_text_alignment="default" phone_text_alignment="default" column_border_width="none" 
        column_border_style="solid"][vc_custom_heading text="' . $title . '" font_container="tag:h2|text_align:center" use_theme_fonts="yes" 
        el_id="' . $id . '"][divider line_type="Small Line" line_alignment="center" line_thickness="3" divider_color="extra-color-2" 
        custom_line_width="57"][/vc_column][/vc_row]';
	}

	public static function heading( string $title, string $headingElement = 'h2', string $rootClass = '' ) {
		self::init();
		return Html::get_str(
			'div',
			array( $rootClass, 'section-title', 'text-align-center', 'extra-padding' ),
			null,
			array(
				Html::get_str( $headingElement, null, null, $title ),
			)
		);
	}

	public static function row_one_whole( bool $boxed = false, bool $centered_text = false, string $animation = '', int $delay = 0,
		string $rootClass, array $content = array(), array $customAttrs = array() ) {
		self::init();
		$classes = array( $rootClass );
		if ( $boxed ) {
			$classes[] = 'boxed';
			array_unshift( $content, '<span class="bottom-line"></span>' ); }
		if ( $centered_text ) {
			$classes[] = 'centered-text';
		}
		if ( $animation ) {
			$classes[]                     = 'has-animation';
			$customAttrs['data-animation'] = strtolower( str_replace( ' ', '-', $animation ) );
			$customAttrs['data-delay']     = $delay;
		}
		$content[] = Html::get_str( 'div', array( $rootClass . '__spacer' ) );
		return Html::get_str( 'div', $classes, null, $content, $customAttrs );
	}

	public static function get_video_shortcode( string $link ) {
		return '[vc_video link="' . $link . '"]';
	}

	public static function get_button_shortcode( string $text, string $link, string $id = '', string $extraClass = '', string $size = 'medium', string $style = 'regular', bool $openInNewTab = true, string $color = 'Accent-Color' ) {
		$openInNewTabStr = $openInNewTab ? 'true' : 'false';
		return '[nectar_btn id="' . $id . '" size="' . $size . '" open_new_tab="' . $openInNewTabStr . '" button_style="' . $style . '" button_color_2="' . $color . '" icon_family="none" el_class="' . $extraClass . '" url="' . $link . '" text="' . $text . '"]';
	}

}
