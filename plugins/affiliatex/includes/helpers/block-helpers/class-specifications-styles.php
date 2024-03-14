<?php
/**
 * Specifications Block Styles
 *
 * @package AffiliateX
 */

class AffiliateX_Specifications_Styles {

	public static function block_fonts( $attr ) {
		return array(
			'specificationTitleTypography' => isset( $attr['specificationTitleTypography'] ) ? $attr['specificationTitleTypography'] : array(),
			'specificationLabelTypography' => isset( $attr['specificationLabelTypography'] ) ? $attr['specificationLabelTypography'] : array(),
			'specificationValueTypography' => isset( $attr['specificationValueTypography'] ) ? $attr['specificationValueTypography'] : array(),
		);
	}

	public static function block_css( $attr, $id ) {
		$selectors = self::get_selectors( $attr );

		$m_selectors = self::get_mobileselectors( $attr );

		$t_selectors = self::get_tabletselectors( $attr );

		$desktop = AffiliateX_Helpers::generate_css( $selectors, '#affiliatex-specification-style-' . $id );

		$tablet = AffiliateX_Helpers::generate_css( $t_selectors, '#affiliatex-specification-style-' . $id );

		$mobile = AffiliateX_Helpers::generate_css( $m_selectors, '#affiliatex-specification-style-' . $id );

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

		$bgType         = isset( $attr['specificationBgType'] ) ? $attr['specificationBgType'] : 'solid';
		$bgGradient     = isset( $attr['specificationBgColorGradient']['gradient'] ) ? $attr['specificationBgColorGradient']['gradient'] : 'linear-gradient(135deg,rgb(238,238,238) 0%,rgb(169,184,195) 100%)';
		$bgColor        = isset( $attr['specificationBgColorSolid'] ) ? $attr['specificationBgColorSolid'] : '#FFFFFF';
		$titleVariation = isset( $attr['specificationTitleTypography']['variation'] ) ? $attr['specificationTitleTypography']['variation'] : 'n5';
		$labelVariation = isset( $attr['specificationLabelTypography']['variation'] ) ? $attr['specificationLabelTypography']['variation'] : 'n4';
		$valueVariation = isset( $attr['specificationValueTypography']['variation'] ) ? $attr['specificationValueTypography']['variation'] : 'n4';

		$selectors = array(
			' .affx-specification-block-container' => array(
				'border-style'  => isset( $attr['specificationBorder']['style'] ) ? $attr['specificationBorder']['style'] : 'solid',
				'border-color'  => isset( $attr['specificationBorder']['color']['color'] ) ? $attr['specificationBorder']['color']['color'] : '#E6ECF7',
				'border-width'  => isset( $attr['specificationBorderWidth']['desktop']['top'] ) && isset( $attr['specificationBorderWidth']['desktop']['right'] ) && isset( $attr['specificationBorderWidth']['desktop']['bottom'] ) && isset( $attr['specificationBorderWidth']['desktop']['left'] ) ? $attr['specificationBorderWidth']['desktop']['top'] . ' ' . $attr['specificationBorderWidth']['desktop']['right'] . ' ' . $attr['specificationBorderWidth']['desktop']['bottom'] . ' ' . $attr['specificationBorderWidth']['desktop']['left'] . ' ' : '1px 1px 1px 1px',
				'margin-top'    => isset( $attr['specificationMargin']['desktop']['top'] ) ? $attr['specificationMargin']['desktop']['top'] : '0px',
				'margin-left'   => isset( $attr['specificationMargin']['desktop']['left'] ) ? $attr['specificationMargin']['desktop']['left'] : '0px',
				'margin-right'  => isset( $attr['specificationMargin']['desktop']['right'] ) ? $attr['specificationMargin']['desktop']['right'] : '0px',
				'margin-bottom' => isset( $attr['specificationMargin']['desktop']['bottom'] ) ? $attr['specificationMargin']['desktop']['bottom'] : '30px',
				'overflow'      => 'hidden',
				'border-radius' => isset( $attr['specificationBorderRadius']['desktop']['top'] ) && isset( $attr['specificationBorderRadius']['desktop']['right'] ) && isset( $attr['specificationBorderRadius']['desktop']['bottom'] ) && isset( $attr['specificationBorderRadius']['desktop']['left'] ) ? $attr['specificationBorderRadius']['desktop']['top'] . ' ' . $attr['specificationBorderRadius']['desktop']['right'] . ' ' . $attr['specificationBorderRadius']['desktop']['bottom'] . ' ' . $attr['specificationBorderRadius']['desktop']['left'] . ' ' : '0 0 0 0',
				'box-shadow'    => isset( $attr['specificationBoxShadow'] ) && $attr['specificationBoxShadow']['enable'] ? AffiliateX_Helpers::get_css_boxshadow( $attr['specificationBoxShadow'] ) : 'none',
			),
			' .affx-specification-table'          => array(
				'margin'     => '0',
				'background' => $bgType && $bgType === 'solid' ? $bgColor : $bgGradient,
			),
			' .affx-specification-table td'       => array(
				'padding-top'    => isset( $attr['specificationPadding']['desktop']['top'] ) ? $attr['specificationPadding']['desktop']['top'] : '16px',
				'padding-left'   => isset( $attr['specificationPadding']['desktop']['left'] ) ? $attr['specificationPadding']['desktop']['left'] : '24px',
				'padding-right'  => isset( $attr['specificationPadding']['desktop']['right'] ) ? $attr['specificationPadding']['desktop']['right'] : '24px',
				'padding-bottom' => isset( $attr['specificationPadding']['desktop']['bottom'] ) ? $attr['specificationPadding']['desktop']['bottom'] : '16px',
			),
			' .affx-specification-table th'       => array(
				'color'           => isset( $attr['specificationTitleColor'] ) ? $attr['specificationTitleColor'] : '#292929',
				'background'      => isset( $attr['specificationTitleBgColor'] ) ? $attr['specificationTitleBgColor'] : '#FFFFFF',
				'text-align'      => isset( $attr['specificationTitleAlign'] ) ? $attr['specificationTitleAlign'] : 'left',
				'font-family'     => isset( $attr['specificationTitleTypography']['family'] ) ? $attr['specificationTitleTypography']['family'] : $global_font_family,
				'font-size'       => isset( $attr['specificationTitleTypography']['size']['desktop'] ) ? $attr['specificationTitleTypography']['size']['desktop'] : '24px',
				'line-height'     => isset( $attr['specificationTitleTypography']['line-height']['desktop'] ) ? $attr['specificationTitleTypography']['line-height']['desktop'] : '1.5',
				'text-transform'  => isset( $attr['specificationTitleTypography']['text-transform'] ) ? $attr['specificationTitleTypography']['text-transform'] : 'none',
				'text-decoration' => isset( $attr['specificationTitleTypography']['text-decoration'] ) ? $attr['specificationTitleTypography']['text-decoration'] : 'none',
				'letter-spacing'  => isset( $attr['specificationTitleTypography']['letter-spacing']['desktop'] ) ? $attr['specificationTitleTypography']['letter-spacing']['desktop'] : '0em',
				'font-weight'     => AffiliateX_Helpers::get_fontweight_variation( $titleVariation ),
				'font-style'      => AffiliateX_Helpers::get_font_style( $titleVariation ),
				'padding-top'     => isset( $attr['specificationPadding']['desktop']['top'] ) ? $attr['specificationPadding']['desktop']['top'] : '16px',
				'padding-left'    => isset( $attr['specificationPadding']['desktop']['left'] ) ? $attr['specificationPadding']['desktop']['left'] : '24px',
				'padding-right'   => isset( $attr['specificationPadding']['desktop']['right'] ) ? $attr['specificationPadding']['desktop']['right'] : '24px',
				'padding-bottom'  => isset( $attr['specificationPadding']['desktop']['bottom'] ) ? $attr['specificationPadding']['desktop']['bottom'] : '16px',
			),
			' .affx-specification-table td.affx-spec-label' => array(
				'color'           => isset( $attr['specificationLabelColor'] ) ? $attr['specificationLabelColor'] : '#000000',
				'text-align'      => isset( $attr['specificationLabelAlign'] ) ? $attr['specificationLabelAlign'] : 'left',
				'font-family'     => isset( $attr['specificationLabelTypography']['family'] ) ? $attr['specificationLabelTypography']['family'] : $global_font_family,
				'font-size'       => isset( $attr['specificationLabelTypography']['size']['desktop'] ) ? $attr['specificationLabelTypography']['size']['desktop'] : '18px',
				'line-height'     => isset( $attr['specificationLabelTypography']['line-height']['desktop'] ) ? $attr['specificationLabelTypography']['line-height']['desktop'] : '1.65',
				'text-transform'  => isset( $attr['specificationLabelTypography']['text-transform'] ) ? $attr['specificationLabelTypography']['text-transform'] : 'none',
				'text-decoration' => isset( $attr['specificationLabelTypography']['text-decoration'] ) ? $attr['specificationLabelTypography']['text-decoration'] : 'none',
				'letter-spacing'  => isset( $attr['specificationLabelTypography']['letter-spacing']['desktop'] ) ? $attr['specificationLabelTypography']['letter-spacing']['desktop'] : '0em',
				'font-weight'     => AffiliateX_Helpers::get_fontweight_variation( $labelVariation ),
				'font-style'      => AffiliateX_Helpers::get_font_style( $labelVariation ),
				'width'           => isset( $attr['specificationColumnWidth'] ) && $attr['specificationColumnWidth'] === 'styleThree' ? '66.66%' : ( isset( $attr['specificationColumnWidth'] ) && $attr['specificationColumnWidth'] === 'styleTwo' ? '50%' : '33.33%' ),
			),
			' .affx-specification-table td.affx-spec-value' => array(
				'color'           => isset( $attr['specificationValueColor'] ) ? $attr['specificationValueColor'] : $global_font_color,
				'text-align'      => isset( $attr['specificationValueAlign'] ) ? $attr['specificationValueAlign'] : 'left',
				'font-family'     => isset( $attr['specificationValueTypography']['family'] ) ? $attr['specificationValueTypography']['family'] : $global_font_family,
				'font-size'       => isset( $attr['specificationValueTypography']['size']['desktop'] ) ? $attr['specificationValueTypography']['size']['desktop'] : '18px',
				'line-height'     => isset( $attr['specificationValueTypography']['line-height']['desktop'] ) ? $attr['specificationValueTypography']['line-height']['desktop'] : '1.65',
				'text-transform'  => isset( $attr['specificationValueTypography']['text-transform'] ) ? $attr['specificationValueTypography']['text-transform'] : 'none',
				'text-decoration' => isset( $attr['specificationValueTypography']['text-decoration'] ) ? $attr['specificationValueTypography']['text-decoration'] : 'none',
				'letter-spacing'  => isset( $attr['specificationValueTypography']['letter-spacing']['desktop'] ) ? $attr['specificationValueTypography']['letter-spacing']['desktop'] : '0em',
				'font-weight'     => AffiliateX_Helpers::get_fontweight_variation( $valueVariation ),
				'font-style'      => AffiliateX_Helpers::get_font_style( $valueVariation ),
				'width'           => isset( $attr['specificationColumnWidth'] ) && $attr['specificationColumnWidth'] === 'styleThree' ? '33.33%' : ( isset( $attr['specificationColumnWidth'] ) && $attr['specificationColumnWidth'] === 'styleTwo' ? '50%' : '66.66%' ),
			),
			' .affx-specification-table.layout-2 td.affx-spec-label' => array(
				'background' => isset( $attr['specificationRowColor'] ) ? $attr['specificationRowColor'] : '#F5F7FA',
			),
			' .affx-specification-table.layout-3 tbody tr:nth-child(even) td' => array(
				'background' => isset( $attr['specificationRowColor'] ) ? $attr['specificationRowColor'] : '#F5F7FA',
			),

		);

		return $selectors;
	}

	public static function get_mobileselectors( $attr ) {

		$mobile_selectors = array(
			' .affx-specification-block-container' => array(
				'border-width'  => isset( $attr['specificationBorderWidth']['mobile']['top'] ) && isset( $attr['specificationBorderWidth']['mobile']['right'] ) && isset( $attr['specificationBorderWidth']['mobile']['bottom'] ) && isset( $attr['specificationBorderWidth']['mobile']['left'] ) ? $attr['specificationBorderWidth']['mobile']['top'] . ' ' . $attr['specificationBorderWidth']['mobile']['right'] . ' ' . $attr['specificationBorderWidth']['mobile']['bottom'] . ' ' . $attr['specificationBorderWidth']['mobile']['left'] . ' ' : '1px 1px 1px 1px',
				'margin-top'    => isset( $attr['specificationMargin']['mobile']['top'] ) ? $attr['specificationMargin']['mobile']['top'] : '0px',
				'margin-left'   => isset( $attr['specificationMargin']['mobile']['left'] ) ? $attr['specificationMargin']['mobile']['left'] : '0px',
				'margin-right'  => isset( $attr['specificationMargin']['mobile']['right'] ) ? $attr['specificationMargin']['mobile']['right'] : '0px',
				'margin-bottom' => isset( $attr['specificationMargin']['mobile']['bottom'] ) ? $attr['specificationMargin']['mobile']['bottom'] : '30px',
				'border-radius' => isset( $attr['specificationBorderRadius']['mobile']['top'] ) && isset( $attr['specificationBorderRadius']['mobile']['right'] ) && isset( $attr['specificationBorderRadius']['mobile']['bottom'] ) && isset( $attr['specificationBorderRadius']['mobile']['left'] ) ? $attr['specificationBorderRadius']['mobile']['top'] . ' ' . $attr['specificationBorderRadius']['mobile']['right'] . ' ' . $attr['specificationBorderRadius']['mobile']['bottom'] . ' ' . $attr['specificationBorderRadius']['mobile']['left'] . ' ' : '0 0 0 0',
			),
			' .affx-specification-table td'       => array(
				'padding-top'    => isset( $attr['specificationPadding']['mobile']['top'] ) ? $attr['specificationPadding']['mobile']['top'] : '16px',
				'padding-left'   => isset( $attr['specificationPadding']['mobile']['left'] ) ? $attr['specificationPadding']['mobile']['left'] : '24px',
				'padding-right'  => isset( $attr['specificationPadding']['mobile']['right'] ) ? $attr['specificationPadding']['mobile']['right'] : '24px',
				'padding-bottom' => isset( $attr['specificationPadding']['mobile']['bottom'] ) ? $attr['specificationPadding']['mobile']['bottom'] : '16px',
			),
			' .affx-specification-table th'       => array(
				'font-size'      => isset( $attr['specificationTitleTypography']['size']['mobile'] ) ? $attr['specificationTitleTypography']['size']['mobile'] : '24px',
				'line-height'    => isset( $attr['specificationTitleTypography']['line-height']['mobile'] ) ? $attr['specificationTitleTypography']['line-height']['mobile'] : '1.5',
				'letter-spacing' => isset( $attr['specificationTitleTypography']['letter-spacing']['mobile'] ) ? $attr['specificationTitleTypography']['letter-spacing']['mobile'] : '0em',
				'padding-top'    => isset( $attr['specificationPadding']['mobile']['top'] ) ? $attr['specificationPadding']['mobile']['top'] : '16px',
				'padding-left'   => isset( $attr['specificationPadding']['mobile']['left'] ) ? $attr['specificationPadding']['mobile']['left'] : '24px',
				'padding-right'  => isset( $attr['specificationPadding']['mobile']['right'] ) ? $attr['specificationPadding']['mobile']['right'] : '24px',
				'padding-bottom' => isset( $attr['specificationPadding']['mobile']['bottom'] ) ? $attr['specificationPadding']['mobile']['bottom'] : '16px',
			),
			' .affx-specification-table td.affx-spec-label' => array(
				'font-size'      => isset( $attr['specificationLabelTypography']['size']['mobile'] ) ? $attr['specificationLabelTypography']['size']['mobile'] : '18px',
				'line-height'    => isset( $attr['specificationLabelTypography']['line-height']['mobile'] ) ? $attr['specificationLabelTypography']['line-height']['mobile'] : '1.65',
				'letter-spacing' => isset( $attr['specificationLabelTypography']['letter-spacing']['mobile'] ) ? $attr['specificationLabelTypography']['letter-spacing']['mobile'] : '0em',
			),
			' .affx-specification-table td.affx-spec-value' => array(
				'font-size'      => isset( $attr['specificationValueTypography']['size']['mobile'] ) ? $attr['specificationValueTypography']['size']['mobile'] : '18px',
				'line-height'    => isset( $attr['specificationValueTypography']['line-height']['mobile'] ) ? $attr['specificationValueTypography']['line-height']['mobile'] : '1.65',
				'letter-spacing' => isset( $attr['specificationValueTypography']['letter-spacing']['mobile'] ) ? $attr['specificationValueTypography']['letter-spacing']['mobile'] : '0em',
			),

		);

		return $mobile_selectors;
	}

	public static function get_tabletselectors( $attr ) {

		$tablet_selectors = array(
			' .affx-specification-block-container' => array(
				'border-width'  => isset( $attr['specificationBorderWidth']['tablet']['top'] ) && isset( $attr['specificationBorderWidth']['tablet']['right'] ) && isset( $attr['specificationBorderWidth']['tablet']['bottom'] ) && isset( $attr['specificationBorderWidth']['tablet']['left'] ) ? $attr['specificationBorderWidth']['tablet']['top'] . ' ' . $attr['specificationBorderWidth']['tablet']['right'] . ' ' . $attr['specificationBorderWidth']['tablet']['bottom'] . ' ' . $attr['specificationBorderWidth']['tablet']['left'] . ' ' : '1px 1px 1px 1px',
				'margin-top'    => isset( $attr['specificationMargin']['tablet']['top'] ) ? $attr['specificationMargin']['tablet']['top'] : '0px',
				'margin-left'   => isset( $attr['specificationMargin']['tablet']['left'] ) ? $attr['specificationMargin']['tablet']['left'] : '0px',
				'margin-right'  => isset( $attr['specificationMargin']['tablet']['right'] ) ? $attr['specificationMargin']['tablet']['right'] : '0px',
				'margin-bottom' => isset( $attr['specificationMargin']['tablet']['bottom'] ) ? $attr['specificationMargin']['tablet']['bottom'] : '30px',
				'border-radius' => isset( $attr['specificationBorderRadius']['tablet']['top'] ) && isset( $attr['specificationBorderRadius']['tablet']['right'] ) && isset( $attr['specificationBorderRadius']['tablet']['bottom'] ) && isset( $attr['specificationBorderRadius']['tablet']['left'] ) ? $attr['specificationBorderRadius']['tablet']['top'] . ' ' . $attr['specificationBorderRadius']['tablet']['right'] . ' ' . $attr['specificationBorderRadius']['tablet']['bottom'] . ' ' . $attr['specificationBorderRadius']['tablet']['left'] . ' ' : '0 0 0 0',
			),
			' .affx-specification-table td'       => array(
				'padding-top'    => isset( $attr['specificationPadding']['tablet']['top'] ) ? $attr['specificationPadding']['tablet']['top'] : '16px',
				'padding-left'   => isset( $attr['specificationPadding']['tablet']['left'] ) ? $attr['specificationPadding']['tablet']['left'] : '24px',
				'padding-right'  => isset( $attr['specificationPadding']['tablet']['right'] ) ? $attr['specificationPadding']['tablet']['right'] : '24px',
				'padding-bottom' => isset( $attr['specificationPadding']['tablet']['bottom'] ) ? $attr['specificationPadding']['tablet']['bottom'] : '16px',
			),
			' .affx-specification-table th'       => array(
				'font-size'      => isset( $attr['specificationTitleTypography']['size']['tablet'] ) ? $attr['specificationTitleTypography']['size']['tablet'] : '24px',
				'line-height'    => isset( $attr['specificationTitleTypography']['line-height']['tablet'] ) ? $attr['specificationTitleTypography']['line-height']['tablet'] : '1.5',
				'letter-spacing' => isset( $attr['specificationTitleTypography']['letter-spacing']['tablet'] ) ? $attr['specificationTitleTypography']['letter-spacing']['tablet'] : '0em',
				'padding-top'    => isset( $attr['specificationPadding']['tablet']['top'] ) ? $attr['specificationPadding']['tablet']['top'] : '16px',
				'padding-left'   => isset( $attr['specificationPadding']['tablet']['left'] ) ? $attr['specificationPadding']['tablet']['left'] : '24px',
				'padding-right'  => isset( $attr['specificationPadding']['tablet']['right'] ) ? $attr['specificationPadding']['tablet']['right'] : '24px',
				'padding-bottom' => isset( $attr['specificationPadding']['tablet']['bottom'] ) ? $attr['specificationPadding']['tablet']['bottom'] : '16px',
			),
			' .affx-specification-table td.affx-spec-label' => array(
				'font-size'      => isset( $attr['specificationLabelTypography']['size']['tablet'] ) ? $attr['specificationLabelTypography']['size']['tablet'] : '18px',
				'line-height'    => isset( $attr['specificationLabelTypography']['line-height']['tablet'] ) ? $attr['specificationLabelTypography']['line-height']['tablet'] : '1.65',
				'letter-spacing' => isset( $attr['specificationLabelTypography']['letter-spacing']['tablet'] ) ? $attr['specificationLabelTypography']['letter-spacing']['tablet'] : '0em',
			),
			' .affx-specification-table td.affx-spec-value' => array(
				'font-size'      => isset( $attr['specificationValueTypography']['size']['tablet'] ) ? $attr['specificationValueTypography']['size']['tablet'] : '18px',
				'line-height'    => isset( $attr['specificationValueTypography']['line-height']['tablet'] ) ? $attr['specificationValueTypography']['line-height']['tablet'] : '1.65',
				'letter-spacing' => isset( $attr['specificationValueTypography']['letter-spacing']['tablet'] ) ? $attr['specificationValueTypography']['letter-spacing']['tablet'] : '0em',
			),
		);

		return $tablet_selectors;
	}

}
