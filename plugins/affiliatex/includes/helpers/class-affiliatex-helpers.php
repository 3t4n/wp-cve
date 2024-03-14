<?php
/**
 * Affiliatex Helpers class
 *
 */

class AffiliateX_Helpers {

	/**
	 * Parse CSS into correct CSS syntax.
	 *
	 * @param array  $selectors The block selectors.
	 * @param string $id The selector ID.
	 * @since 0.0.1
	 */
	public static function generate_css($selectors, $id) {
		$styling_css = '';

		if ( empty( $selectors ) ) {
			return '';
		}

		foreach ( $selectors as $key => $value ) {

			$css = '';

			foreach ( $value as $j => $val ) {

				if ( 'font-family' === $j && 'Default' === $val ) {
					continue;
				}

				if ( ! empty( $val ) ) {
					if ( 'font-family' === $j ) {
						$css .= $j . ': "' . $val . '";';
					} else {
						$css .= $j . ': ' . $val . ';';
					}

				}
			}

			if ( ! empty( $css ) ) {
				$styling_css     .= $id;
				$styling_css     .= $key . '{';
				$styling_css .= $css . '}';
			}
		}

		return $styling_css;
	}

	/**
	 * Get CSS value
	 *
	 * Syntax:
	 *
	 *  get_css_value( VALUE, UNIT );
	 *
	 * E.g.
	 *
	 *  get_css_value( VALUE, 'em' );
	 *
	 * @param string $value  CSS value.
	 * @param string $unit  CSS unit.
	 * @since x.x.x
	 */
	public static function get_css_value( $value = '', $unit = '' ) {

		if ( '' == $value) {
			return $value;
		}

		$css_val = '';

		if ( !empty( $value ) ) {

			$css_val = esc_attr( $value ) . $unit;
		}

		return $css_val;
	}

	public static function get_css_boxshadow( $v ) {
		if ( isset( $v['enable'] ) &&  $v['enable'] === true ) {
			$h_offset = isset( $v['h_offset'] ) ? $v['h_offset'] : 0;
			$v_offset = isset( $v['v_offset'] ) ? $v['v_offset'] : 5;
			$blur     = isset( $v['blur'] ) ? $v['blur'] : 20;
			$spread   = isset( $v['spread'] ) ? $v['spread'] : 0;
			$color    = isset( $v['color']['color'] ) ? $v['color']['color'] : 'rgba(210,213,218,0.2)';
			$inset    = isset( $v['inset'] ) && $v['inset'] === true ? 'inset' : '';

			return  $h_offset . 'px ' . $v_offset . 'px ' . $blur . 'px ' . $spread . 'px ' . $color . $inset;
		} else {
			return "none";
		}
	}
	
	public static function get_fontweight_variation( $variation ) {
		$fontType   = $variation[1];
		$fontWeight = $fontType * 100;
		return $fontWeight;
	}
	
	public static function get_font_style( $variation ) {
		$variationType = $variation[0];
		$font          = $variationType === 'n' ? 'normal' : ( $variationType === 'i' ? 'italic' : 'Default' );
		return $font;
	}

}
