<?php
/**
 * Versus Line Block Styles
 *
 * @package AffiliateX
 */

class AffiliateX_Versus_Line_Styles {

	public static function block_fonts( $attr ) {
		return array(
			'vsTypography'            => isset( $attr['vsTypography'] ) ? $attr['vsTypography'] : array(),
			'versusContentTypography' => isset( $attr['versusContentTypography'] ) ? $attr['versusContentTypography'] : array(),
		);
	}

	public static function block_css( $attr, $id ) {
		$selectors = self::get_selectors( $attr );

		$m_selectors = self::get_mobileselectors( $attr );

		$t_selectors = self::get_tabletselectors( $attr );

		$desktop = AffiliateX_Helpers::generate_css( $selectors, '#affiliatex-versus-line-style-' . $id );

		$tablet = AffiliateX_Helpers::generate_css( $t_selectors, '#affiliatex-versus-line-style-' . $id );

		$mobile = AffiliateX_Helpers::generate_css( $m_selectors, '#affiliatex-versus-line-style-' . $id );

		$generated_css = array(
			'desktop' => $desktop,
			'tablet'  => $tablet,
			'mobile'  => $mobile,
		);

		return $generated_css;
	}

	public static function get_selectors( $attr ) {

		$customization_data     = affx_get_customization_settings();
		$global_font_family     = isset( $customization_data['typography']['family'] ) ? $customization_data['typography']['family'] : 'Default';
		$global_font_color      = isset( $customization_data['fontColor'] ) ? $customization_data['fontColor'] : '#292929';
		
		$bgType           = isset( $attr['bgType'] ) ? $attr['bgType'] : 'solid';
		$bgGradient       = isset( $attr['bgColorGradient']['gradient'] ) ? $attr['bgColorGradient']['gradient'] : 'linear-gradient(135deg,rgb(238,238,238) 0%,rgb(169,184,195) 100%)';
		$bgColor          = isset( $attr['bgColorSolid'] ) ? $attr['bgColorSolid'] : '#FFFFFF';
		$contentVariation = isset( $attr['versusContentTypography']['variation'] ) ? $attr['versusContentTypography']['variation'] : 'n4';
		$vsVariation      = isset( $attr['vsTypography']['variation'] ) ? $attr['vsTypography']['variation'] : 'n4';

		$selectors = array(
			' .affx-versus-table-wrap'               => array(
				'color'           => isset( $attr['contentColor'] ) ? $attr['contentColor'] : $global_font_color,
				'font-family'     => isset( $attr['versusContentTypography']['family'] ) ? $attr['versusContentTypography']['family'] : $global_font_family,
				'font-size'       => isset( $attr['versusContentTypography']['size']['desktop'] ) ? $attr['versusContentTypography']['size']['desktop'] : '18px',
				'line-height'     => isset( $attr['versusContentTypography']['line-height']['desktop'] ) ? $attr['versusContentTypography']['line-height']['desktop'] : '1.65',
				'text-transform'  => isset( $attr['versusContentTypography']['text-transform'] ) ? $attr['versusContentTypography']['text-transform'] : 'none',
				'text-decoration' => isset( $attr['versusContentTypography']['text-decoration'] ) ? $attr['versusContentTypography']['text-decoration'] : 'none',
				'letter-spacing'  => isset( $attr['versusContentTypography']['letter-spacing']['desktop'] ) ? $attr['versusContentTypography']['letter-spacing']['desktop'] : '0em',
				'font-weight'     => AffiliateX_Helpers::get_fontweight_variation( $contentVariation ),
				'font-style'      => AffiliateX_Helpers::get_font_style( $contentVariation ),
				'border-style'    => isset( $attr['border']['style'] ) ? $attr['border']['style'] : 'solid',
				'border-color'    => isset( $attr['border']['color']['color'] ) ? $attr['border']['color']['color'] : '#E6ECF7',
				'border-width'    => isset( $attr['borderWidth']['desktop']['top'] ) && isset( $attr['borderWidth']['desktop']['right'] ) && isset( $attr['borderWidth']['desktop']['bottom'] ) && isset( $attr['borderWidth']['desktop']['left'] ) ? $attr['borderWidth']['desktop']['top'] . ' ' . $attr['borderWidth']['desktop']['right'] . ' ' . $attr['borderWidth']['desktop']['bottom'] . ' ' . $attr['borderWidth']['desktop']['left'] . ' ' : '0px 0px 0px 0px',
				'margin-top'      => isset( $attr['margin']['desktop']['top'] ) ? $attr['margin']['desktop']['top'] : '0px',
				'margin-left'     => isset( $attr['margin']['desktop']['left'] ) ? $attr['margin']['desktop']['left'] : '0px',
				'margin-right'    => isset( $attr['margin']['desktop']['right'] ) ? $attr['margin']['desktop']['right'] : '0px',
				'margin-bottom'   => isset( $attr['margin']['desktop']['bottom'] ) ? $attr['margin']['desktop']['bottom'] : '30px',
				'padding-top'     => isset( $attr['padding']['desktop']['top'] ) ? $attr['padding']['desktop']['top'] : '0px',
				'padding-left'    => isset( $attr['padding']['desktop']['left'] ) ? $attr['padding']['desktop']['left'] : '0px',
				'padding-right'   => isset( $attr['padding']['desktop']['right'] ) ? $attr['padding']['desktop']['right'] : '0px',
				'padding-bottom'  => isset( $attr['padding']['desktop']['bottom'] ) ? $attr['padding']['desktop']['bottom'] : '0px',
				'border-radius'   => isset( $attr['borderRadius']['desktop']['top'] ) && isset( $attr['borderRadius']['desktop']['right'] ) && isset( $attr['borderRadius']['desktop']['bottom'] ) && isset( $attr['borderRadius']['desktop']['left'] ) ? $attr['borderRadius']['desktop']['top'] . ' ' . $attr['borderRadius']['desktop']['right'] . ' ' . $attr['borderRadius']['desktop']['bottom'] . ' ' . $attr['borderRadius']['desktop']['left'] . ' ' : '0 0 0 0',
				'box-shadow'      => isset( $attr['boxShadow'] ) && $attr['boxShadow']['enable'] ? AffiliateX_Helpers::get_css_boxshadow( $attr['boxShadow'] ) : 'none',
				'background'      => $bgType && $bgType === 'solid' ? $bgColor : $bgGradient,
			),
			' .affx-versus-table-wrap .affx-vs-icon' => array(
				'color'           => isset( $attr['vsTextColor'] ) ? $attr['vsTextColor'] : '#000',
				'background'      => isset( $attr['vsBgColor'] ) ? $attr['vsBgColor'] : '#E6ECF7',
				'font-family'     => isset( $attr['vsTypography']['family'] ) ? $attr['vsTypography']['family'] : $global_font_family,
				'font-size'       => isset( $attr['vsTypography']['size']['desktop'] ) ? $attr['vsTypography']['size']['desktop'] : '18px',
				'line-height'     => isset( $attr['vsTypography']['line-height']['desktop'] ) ? $attr['vsTypography']['line-height']['desktop'] : '1.65',
				'text-transform'  => isset( $attr['vsTypography']['text-transform'] ) ? $attr['vsTypography']['text-transform'] : 'none',
				'text-decoration' => isset( $attr['vsTypography']['text-decoration'] ) ? $attr['vsTypography']['text-decoration'] : 'none',
				'letter-spacing'  => isset( $attr['vsTypography']['letter-spacing']['desktop'] ) ? $attr['vsTypography']['letter-spacing']['desktop'] : '0em',
				'font-weight'     => AffiliateX_Helpers::get_fontweight_variation( $vsVariation ),
				'font-style'      => AffiliateX_Helpers::get_font_style( $vsVariation ),
			),
			' .affx-product-versus-table tbody tr:nth-child(odd) td' => array(
				'background' => isset( $attr['versusRowColor'] ) ? $attr['versusRowColor'] : '#F5F7FA',
			),

		);

		return $selectors;
	}

	public static function get_mobileselectors( $attr ) {

		$mobile_selectors = array(
			' .affx-versus-table-wrap'               => array(
				'font-size'      => isset( $attr['versusContentTypography']['size']['mobile'] ) ? $attr['versusContentTypography']['size']['mobile'] : '18px',
				'line-height'    => isset( $attr['versusContentTypography']['line-height']['mobile'] ) ? $attr['versusContentTypography']['line-height']['mobile'] : '1.65',
				'letter-spacing' => isset( $attr['versusContentTypography']['letter-spacing']['mobile'] ) ? $attr['versusContentTypography']['letter-spacing']['mobile'] : '0em',
				'border-width'   => isset( $attr['borderWidth']['mobile']['top'] ) && isset( $attr['borderWidth']['mobile']['right'] ) && isset( $attr['borderWidth']['mobile']['bottom'] ) && isset( $attr['borderWidth']['mobile']['left'] ) ? $attr['borderWidth']['mobile']['top'] . ' ' . $attr['borderWidth']['mobile']['right'] . ' ' . $attr['borderWidth']['mobile']['bottom'] . ' ' . $attr['borderWidth']['mobile']['left'] . ' ' : '0px 0px 0px 0px',
				'margin-top'     => isset( $attr['margin']['mobile']['top'] ) ? $attr['margin']['mobile']['top'] : '0px',
				'margin-left'    => isset( $attr['margin']['mobile']['left'] ) ? $attr['margin']['mobile']['left'] : '0px',
				'margin-right'   => isset( $attr['margin']['mobile']['right'] ) ? $attr['margin']['mobile']['right'] : '0px',
				'margin-bottom'  => isset( $attr['margin']['mobile']['bottom'] ) ? $attr['margin']['mobile']['bottom'] : '30px',
				'padding-top'    => isset( $attr['padding']['mobile']['top'] ) ? $attr['padding']['mobile']['top'] : '0px',
				'padding-left'   => isset( $attr['padding']['mobile']['left'] ) ? $attr['padding']['mobile']['left'] : '0px',
				'padding-right'  => isset( $attr['padding']['mobile']['right'] ) ? $attr['padding']['mobile']['right'] : '0px',
				'padding-bottom' => isset( $attr['padding']['mobile']['bottom'] ) ? $attr['padding']['mobile']['bottom'] : '0px',
				'border-radius'  => isset( $attr['borderRadius']['mobile']['top'] ) && isset( $attr['borderRadius']['mobile']['right'] ) && isset( $attr['borderRadius']['mobile']['bottom'] ) && isset( $attr['borderRadius']['mobile']['left'] ) ? $attr['borderRadius']['mobile']['top'] . ' ' . $attr['borderRadius']['mobile']['right'] . ' ' . $attr['borderRadius']['mobile']['bottom'] . ' ' . $attr['borderRadius']['mobile']['left'] . ' ' : '0 0 0 0',
			),
			' .affx-versus-table-wrap .affx-vs-icon' => array(
				'font-size'      => isset( $attr['vsTypography']['size']['mobile'] ) ? $attr['vsTypography']['size']['mobile'] : '18px',
				'line-height'    => isset( $attr['vsTypography']['line-height']['mobile'] ) ? $attr['vsTypography']['line-height']['mobile'] : '1.65',
				'letter-spacing' => isset( $attr['vsTypography']['letter-spacing']['mobile'] ) ? $attr['vsTypography']['letter-spacing']['mobile'] : '0em',
			),
		);

		return $mobile_selectors;
	}

	public static function get_tabletselectors( $attr ) {

		$tablet_selectors = array(
			' .affx-versus-table-wrap'               => array(
				'font-size'      => isset( $attr['versusContentTypography']['size']['tablet'] ) ? $attr['versusContentTypography']['size']['tablet'] : '18px',
				'line-height'    => isset( $attr['versusContentTypography']['line-height']['tablet'] ) ? $attr['versusContentTypography']['line-height']['tablet'] : '1.65',
				'letter-spacing' => isset( $attr['versusContentTypography']['letter-spacing']['tablet'] ) ? $attr['versusContentTypography']['letter-spacing']['tablet'] : '0em',
				'border-width'   => isset( $attr['borderWidth']['tablet']['top'] ) && isset( $attr['borderWidth']['tablet']['right'] ) && isset( $attr['borderWidth']['tablet']['bottom'] ) && isset( $attr['borderWidth']['tablet']['left'] ) ? $attr['borderWidth']['tablet']['top'] . ' ' . $attr['borderWidth']['tablet']['right'] . ' ' . $attr['borderWidth']['tablet']['bottom'] . ' ' . $attr['borderWidth']['tablet']['left'] . ' ' : '0px 0px 0px 0px',
				'margin-top'     => isset( $attr['margin']['tablet']['top'] ) ? $attr['margin']['tablet']['top'] : '0px',
				'margin-left'    => isset( $attr['margin']['tablet']['left'] ) ? $attr['margin']['tablet']['left'] : '0px',
				'margin-right'   => isset( $attr['margin']['tablet']['right'] ) ? $attr['margin']['tablet']['right'] : '0px',
				'margin-bottom'  => isset( $attr['margin']['tablet']['bottom'] ) ? $attr['margin']['tablet']['bottom'] : '30px',
				'padding-top'    => isset( $attr['padding']['tablet']['top'] ) ? $attr['padding']['tablet']['top'] : '0px',
				'padding-left'   => isset( $attr['padding']['tablet']['left'] ) ? $attr['padding']['tablet']['left'] : '0px',
				'padding-right'  => isset( $attr['padding']['tablet']['right'] ) ? $attr['padding']['tablet']['right'] : '0px',
				'padding-bottom' => isset( $attr['padding']['tablet']['bottom'] ) ? $attr['padding']['tablet']['bottom'] : '0px',
				'border-radius'  => isset( $attr['borderRadius']['tablet']['top'] ) && isset( $attr['borderRadius']['tablet']['right'] ) && isset( $attr['borderRadius']['tablet']['bottom'] ) && isset( $attr['borderRadius']['tablet']['left'] ) ? $attr['borderRadius']['tablet']['top'] . ' ' . $attr['borderRadius']['tablet']['right'] . ' ' . $attr['borderRadius']['tablet']['bottom'] . ' ' . $attr['borderRadius']['tablet']['left'] . ' ' : '0 0 0 0',
			),
			' .affx-versus-table-wrap .affx-vs-icon' => array(
				'font-size'      => isset( $attr['vsTypography']['size']['tablet'] ) ? $attr['vsTypography']['size']['tablet'] : '18px',
				'line-height'    => isset( $attr['vsTypography']['line-height']['tablet'] ) ? $attr['vsTypography']['line-height']['tablet'] : '1.65',
				'letter-spacing' => isset( $attr['vsTypography']['letter-spacing']['tablet'] ) ? $attr['vsTypography']['letter-spacing']['tablet'] : '0em',
			),
		);

		return $tablet_selectors;
	}

}
