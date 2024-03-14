<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The Lion_Badge_Style class holds styling options
 */
class Lion_Badge_Style {

	public static function get_badge_shape( $badge_id ) {
		$shape_name = get_post_meta( $badge_id, '_badge_shape_badge', true );

		return $shape_name;
	}

	public static function get_badge_shape_css( $badge_id ) {
		$shape_css = array();

		$shape_background = get_post_meta( $badge_id, '_badge_shape_style_background', true );
		if ( $shape_background ) {
			$shape_css[] = 'background: ' . esc_attr( $shape_background ) . ';';
		}

		$shape_size = get_post_meta( $badge_id, '_badge_shape_style_size', true );
		if ( $shape_size ) {
			$shape_css[] = 'width: ' . esc_attr( $shape_size ) . 'px;';
			$shape_css[] = 'height: ' . esc_attr( $shape_size ) . 'px;';
		}

		$shape_position_top = get_post_meta( $badge_id, '_badge_position_top', true );
		if ( ! $shape_position_top ) {
			$shape_position_top = 0;
		}

		$shape_css[] = 'top: ' . esc_attr( $shape_position_top ) . 'px;';

		$shape_position_right = get_post_meta( $badge_id, '_badge_position_right', true );
		if ( $shape_position_right ) {
			$shape_css[] = 'right: ' . esc_attr( $shape_position_right ) . 'px;';
		}

		$shape_position_left = get_post_meta( $badge_id, '_badge_position_left', true );
		if ( $shape_position_left ) {
			$shape_css[] = 'left: ' . esc_attr( $shape_position_left ) . 'px;';
		}

		return $shape_css;
	}

	public static function get_badge_text_css( $badge_id ) {
		$text_css = array();

		$font_family_val = get_post_meta( $badge_id, '_badge_text_font_family', true );
		if ( $font_family_val ) {
			$font_family = lion_badges_admin_get_font_family( $font_family_val );

			if ( $font_family_val != 'default' )
				$text_css[] = 'font-family: ' . esc_attr( $font_family ) . ';';
		}

		$font_size = get_post_meta( $badge_id, '_badge_text_font_size', true );
		if ( $font_size ) {
			$text_css[] = 'font-size: ' . esc_attr( $font_size ) . 'px;';
		}

		$text_color = get_post_meta( $badge_id, '_badge_text_color', true );
		if ( $text_color ) {
			$text_css[] = 'color: ' . esc_attr( $text_color ) . ';';
		}

		$text_align = get_post_meta( $badge_id, '_badge_text_align', true );
		if ( $text_align ) {
			$text_css[] = 'text-align: ' . esc_attr( $text_align ) . ';';
		}

		$text_padding_top = get_post_meta( $badge_id, '_badge_text_padding_top', true );
		if ( $text_padding_top ) {
			$text_css[] = 'padding-top: ' . esc_attr( $text_padding_top ) . 'px;';
		}

		$text_padding_right = get_post_meta( $badge_id, '_badge_text_padding_right', true );
		if ( $text_padding_right ) {
			$text_css[] = 'padding-right: ' . esc_attr( $text_padding_right ) . 'px;';
		}

		$text_padding_bottom = get_post_meta( $badge_id, '_badge_text_padding_bottom', true );
		if ( $text_padding_bottom ) {
			$text_css[] = 'padding-bottom: ' . esc_attr( $text_padding_bottom ) . 'px;';
		}

		$text_padding_left = get_post_meta( $badge_id, '_badge_text_padding_left', true );
		if ( $text_padding_left ) {
			$text_css[] = 'padding-left: ' . esc_attr( $text_padding_left ) . 'px;';
		}

		return $text_css;
	}

	public static function get_badge_text( $badge_id ) {
		return get_post_meta( $badge_id, '_badge_text_text', true );
	}
}