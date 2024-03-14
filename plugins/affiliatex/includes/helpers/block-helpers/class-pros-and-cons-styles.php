<?php
/**
 * Pros and Cons Block Styles
 *
 * @package AffiliateX
 */

class AffiliateX_Pros_and_Cons_Styles {

	public static function block_fonts( $attr ) {
		return array(
			'titleTypography' => isset( $attr['titleTypography'] ) ? $attr['titleTypography'] : array(),
			'listTypography'  => isset( $attr['listTypography'] ) ? $attr['listTypography'] : array(),
		);
	}

	public static function block_css( $attr, $id ) {
		$selectors = self::get_selectors( $attr );

		$m_selectors = self::get_mobileselectors( $attr );

		$t_selectors = self::get_tabletselectors( $attr );

		$desktop = AffiliateX_Helpers::generate_css( $selectors, '#affiliatex-pros-cons-style-' . $id );

		$tablet = AffiliateX_Helpers::generate_css( $t_selectors, '#affiliatex-pros-cons-style-' . $id );

		$mobile = AffiliateX_Helpers::generate_css( $m_selectors, '#affiliatex-pros-cons-style-' . $id );

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
		$global_btn_color       = isset( $customization_data['btnColor'] ) ? $customization_data['btnColor'] : '#2670FF';
		$global_btn_hover_color = isset( $customization_data['btnHoverColor'] ) ? $customization_data['btnHoverColor'] : '#084ACA';
		$title_variation        = isset( $attr['titleTypography']['variation'] ) ? $attr['titleTypography']['variation'] : 'n5';
		$variation              = isset( $attr['contentTypography']['variation'] ) ? $attr['contentTypography']['variation'] : 'n4';
		$list_variation         = isset( $attr['listTypography']['variation'] ) ? $attr['listTypography']['variation'] : 'n4';
		$contentAlignment       = isset( $attr['contentAlignment'] ) ? $attr['contentAlignment'] : 'left';
		$bgGradient             = isset( $attr['bgColorGradient']['gradient'] ) ? $attr['bgColorGradient']['gradient'] : '';
		$bgColor                = isset( $attr['bgColorSolid'] ) ? $attr['bgColorSolid'] : '#FFFFFF';
		$consBgGradient         = isset( $attr['consBgGradient']['gradient'] ) ? $attr['consBgGradient']['gradient'] : '';
		$consBgColor            = isset( $attr['consBgColor'] ) ? $attr['consBgColor'] : '#F13A3A';
		$prosBgGradient         = isset( $attr['prosBgGradient']['gradient'] ) ? $attr['prosBgGradient']['gradient'] : '';
		$prosBgColor            = isset( $attr['prosBgColor'] ) ? $attr['prosBgColor'] : '#24B644';
		$prosListBgGradient     = isset( $attr['prosListBgGradient']['gradient'] ) ? $attr['prosListBgGradient']['gradient'] : '';
		$prosListBgColor        = isset( $attr['prosListBgColor'] ) ? $attr['prosListBgColor'] : '#F5FFF8';
		$consListBgGradient     = isset( $attr['consListBgGradient']['gradient'] ) ? $attr['consListBgGradient']['gradient'] : '';
		$consListBgColor        = isset( $attr['consListBgColor'] ) ? $attr['consListBgColor'] : '#FFF5F5';
		$contentType            = isset( $attr['contentType'] ) ? $attr['contentType'] : 'list';
		$listType               = isset( $attr['listType'] ) ? $attr['listType'] : 'unordered';
		$unorderedType          = isset( $attr['unorderedType'] ) ? $attr['unorderedType'] : 'icon';
		$prosTextColorThree     = isset( $attr['prosTextColorThree'] ) ? $attr['prosTextColorThree'] : '#24B644';
		$prosTextColor          = isset( $attr['prosTextColor'] ) ? $attr['prosTextColor'] : '#ffffff';
		$consTextColorThree     = isset( $attr['consTextColorThree'] ) ? $attr['consTextColorThree'] : '#F13A3A';
		$consTextColor          = isset( $attr['consTextColor'] ) ? $attr['consTextColor'] : '#ffffff';
		$box_shadow             = array(
			'enable'   => true,
			'h_offset' => 2,
			'v_offset' => 5,
			'blur'     => 20,
			'spread'   => 0,
			'inset'    => false,
			'color'    => array(
				'color' => 'rgba(210,213,218,0.2)',
			),
		);
		$selector               = array(
			' .affx-pros-cons-inner-wrapper.layout-type-1' => array(
				'box-shadow' => isset( $attr['boxShadow'] ) && $attr['boxShadow']['enable'] ? AffiliateX_Helpers::get_css_boxshadow( $attr['boxShadow'] ) : AffiliateX_Helpers::get_css_boxshadow( $box_shadow ),
			),
			' .affx-pros-cons-inner-wrapper.layout-type-2 .affx-pros-inner' => array(
				'box-shadow' => isset( $attr['boxShadow'] ) && $attr['boxShadow']['enable'] ? AffiliateX_Helpers::get_css_boxshadow( $attr['boxShadow'] ) : AffiliateX_Helpers::get_css_boxshadow( $box_shadow ),
			),
			' .affx-pros-cons-inner-wrapper.layout-type-2 .affx-cons-inner' => array(
				'box-shadow' => isset( $attr['boxShadow'] ) && $attr['boxShadow']['enable'] ? AffiliateX_Helpers::get_css_boxshadow( $attr['boxShadow'] ) : AffiliateX_Helpers::get_css_boxshadow( $box_shadow ),
			),
			' .affx-pros-cons-inner-wrapper.layout-type-3 .affx-pros-inner' => array(
				'box-shadow' => isset( $attr['boxShadow'] ) && $attr['boxShadow']['enable'] ? AffiliateX_Helpers::get_css_boxshadow( $attr['boxShadow'] ) : AffiliateX_Helpers::get_css_boxshadow( $box_shadow ),
			),
			' .affx-pros-cons-inner-wrapper.layout-type-3 .affx-cons-inner' => array(
				'box-shadow' => isset( $attr['boxShadow'] ) && $attr['boxShadow']['enable'] ? AffiliateX_Helpers::get_css_boxshadow( $attr['boxShadow'] ) : AffiliateX_Helpers::get_css_boxshadow( $box_shadow ),
			),
			' .affx-pros-cons-inner-wrapper'               => array(
				'margin-top'     => isset( $attr['margin']['desktop']['top'] ) ? $attr['margin']['desktop']['top'] : '0px',
				'margin-left'    => isset( $attr['margin']['desktop']['left'] ) ? $attr['margin']['desktop']['left'] : '0px',
				'margin-right'   => isset( $attr['margin']['desktop']['right'] ) ? $attr['margin']['desktop']['right'] : '0px',
				'margin-bottom'  => isset( $attr['margin']['desktop']['bottom'] ) ? $attr['margin']['desktop']['bottom'] : '30px',
				'padding-top'    => isset( $attr['padding']['desktop']['top'] ) ? $attr['padding']['desktop']['top'] : '0px',
				'padding-left'   => isset( $attr['padding']['desktop']['left'] ) ? $attr['padding']['desktop']['left'] : '0px',
				'padding-right'  => isset( $attr['padding']['desktop']['right'] ) ? $attr['padding']['desktop']['right'] : '0px',
				'padding-bottom' => isset( $attr['padding']['desktop']['bottom'] ) ? $attr['padding']['desktop']['bottom'] : '0px',
			),
			' .pros-icon-title-wrap .affiliatex-block-pros' => array(
				'margin-top'     => isset( $attr['titleMargin']['desktop']['top'] ) ? $attr['titleMargin']['desktop']['top'] : '0px',
				'margin-left'    => isset( $attr['titleMargin']['desktop']['left'] ) ? $attr['titleMargin']['desktop']['left'] : '0px',
				'margin-right'   => isset( $attr['titleMargin']['desktop']['right'] ) ? $attr['titleMargin']['desktop']['right'] : '0px',
				'margin-bottom'  => isset( $attr['titleMargin']['desktop']['bottom'] ) ? $attr['titleMargin']['desktop']['bottom'] : '0px',
				'padding-top'    => isset( $attr['titlePadding']['desktop']['top'] ) ? $attr['titlePadding']['desktop']['top'] : '10px',
				'padding-left'   => isset( $attr['titlePadding']['desktop']['left'] ) ? $attr['titlePadding']['desktop']['left'] : '20px',
				'padding-right'  => isset( $attr['titlePadding']['desktop']['right'] ) ? $attr['titlePadding']['desktop']['right'] : '20px',
				'padding-bottom' => isset( $attr['titlePadding']['desktop']['bottom'] ) ? $attr['titlePadding']['desktop']['bottom'] : '10px',
			),
			' .cons-icon-title-wrap .affiliatex-block-cons' => array(
				'margin-top'     => isset( $attr['titleMargin']['desktop']['top'] ) ? $attr['titleMargin']['desktop']['top'] : '0px',
				'margin-left'    => isset( $attr['titleMargin']['desktop']['left'] ) ? $attr['titleMargin']['desktop']['left'] : '0px',
				'margin-right'   => isset( $attr['titleMargin']['desktop']['right'] ) ? $attr['titleMargin']['desktop']['right'] : '0px',
				'margin-bottom'  => isset( $attr['titleMargin']['desktop']['bottom'] ) ? $attr['titleMargin']['desktop']['bottom'] : '0px',
				'padding-top'    => isset( $attr['titlePadding']['desktop']['top'] ) ? $attr['titlePadding']['desktop']['top'] : '10px',
				'padding-left'   => isset( $attr['titlePadding']['desktop']['left'] ) ? $attr['titlePadding']['desktop']['left'] : '20px',
				'padding-right'  => isset( $attr['titlePadding']['desktop']['right'] ) ? $attr['titlePadding']['desktop']['right'] : '20px',
				'padding-bottom' => isset( $attr['titlePadding']['desktop']['bottom'] ) ? $attr['titlePadding']['desktop']['bottom'] : '10px',
			),
			' .affx-pros-cons-inner-wrapper.layout-type-1 .pros-icon-title-wrap .affiliatex-block-pros' => array(
				'border-width'  => isset( $attr['titleBorderWidthOne']['desktop']['top'] ) && isset( $attr['titleBorderWidthOne']['desktop']['right'] ) && isset( $attr['titleBorderWidthOne']['desktop']['bottom'] ) && isset( $attr['titleBorderWidthOne']['desktop']['left'] ) ? $attr['titleBorderWidthOne']['desktop']['top'] . ' ' . $attr['titleBorderWidthOne']['desktop']['right'] . ' ' . $attr['titleBorderWidthOne']['desktop']['bottom'] . ' ' . $attr['titleBorderWidthOne']['desktop']['left'] . ' ' : '1px 1px 1px 1px',
				'border-radius' => isset( $attr['titleBorderRadiusOne']['desktop']['top'] ) && isset( $attr['titleBorderRadiusOne']['desktop']['right'] ) && isset( $attr['titleBorderRadiusOne']['desktop']['bottom'] ) && isset( $attr['titleBorderRadiusOne']['desktop']['left'] ) ? $attr['titleBorderRadiusOne']['desktop']['top'] . ' ' . $attr['titleBorderRadiusOne']['desktop']['right'] . ' ' . $attr['titleBorderRadiusOne']['desktop']['bottom'] . ' ' . $attr['titleBorderRadiusOne']['desktop']['left'] . ' ' : '0px 0px 0px 0px',
				'border-style'  => isset( $attr['prosBorder']['style'] ) ? $attr['prosBorder']['style'] : 'none',
				'border-color'  => isset( $attr['prosBorder']['color']['color'] ) ? $attr['prosBorder']['color']['color'] : '#dddddd',
			),
			' .affx-pros-cons-inner-wrapper.layout-type-1 .affiliatex-pros' => array(
				'border-width'  => isset( $attr['contentBorderWidthOne']['desktop']['top'] ) && isset( $attr['contentBorderWidthOne']['desktop']['right'] ) && isset( $attr['contentBorderWidthOne']['desktop']['bottom'] ) && isset( $attr['contentBorderWidthOne']['desktop']['left'] ) ? $attr['contentBorderWidthOne']['desktop']['top'] . ' ' . $attr['contentBorderWidthOne']['desktop']['right'] . ' ' . $attr['contentBorderWidthOne']['desktop']['bottom'] . ' ' . $attr['contentBorderWidthOne']['desktop']['left'] . ' ' : '1px 1px 1px 1px',
				'border-radius' => isset( $attr['contentBorderRadiusOne']['desktop']['top'] ) && isset( $attr['contentBorderRadiusOne']['desktop']['right'] ) && isset( $attr['contentBorderRadiusOne']['desktop']['bottom'] ) && isset( $attr['contentBorderRadiusOne']['desktop']['left'] ) ? $attr['contentBorderRadiusOne']['desktop']['top'] . ' ' . $attr['contentBorderRadiusOne']['desktop']['right'] . ' ' . $attr['contentBorderRadiusOne']['desktop']['bottom'] . ' ' . $attr['contentBorderRadiusOne']['desktop']['left'] . ' ' : '0px 0px 0px 0px',
				'border-style'  => isset( $attr['prosContentBorder']['style'] ) ? $attr['prosContentBorder']['style'] : 'none',
				'border-color'  => isset( $attr['prosContentBorder']['color']['color'] ) ? $attr['prosContentBorder']['color']['color'] : '', // dddddd',
			),
			' .affx-pros-cons-inner-wrapper.layout-type-2 .pros-icon-title-wrap .affiliatex-block-pros' => array(
				'border-width'  => isset( $attr['titleBorderWidthTwo']['desktop']['top'] ) && isset( $attr['titleBorderWidthTwo']['desktop']['right'] ) && isset( $attr['titleBorderWidthTwo']['desktop']['bottom'] ) && isset( $attr['titleBorderWidthTwo']['desktop']['left'] ) ? $attr['titleBorderWidthTwo']['desktop']['top'] . ' ' . $attr['titleBorderWidthTwo']['desktop']['right'] . ' ' . $attr['titleBorderWidthTwo']['desktop']['bottom'] . ' ' . $attr['titleBorderWidthTwo']['desktop']['left'] . ' ' : '1px 1px 1px 1px',
				'border-radius' => isset( $attr['titleBorderRadiusTwo']['desktop']['top'] ) && isset( $attr['titleBorderRadiusTwo']['desktop']['right'] ) && isset( $attr['titleBorderRadiusTwo']['desktop']['bottom'] ) && isset( $attr['titleBorderRadiusTwo']['desktop']['left'] ) ? $attr['titleBorderRadiusTwo']['desktop']['top'] . ' ' . $attr['titleBorderRadiusTwo']['desktop']['right'] . ' ' . $attr['titleBorderRadiusTwo']['desktop']['bottom'] . ' ' . $attr['titleBorderRadiusTwo']['desktop']['left'] . ' ' : '0px 0px 0px 0px',
				'border-style'  => isset( $attr['prosBorder']['style'] ) ? $attr['prosBorder']['style'] : 'none',
				'border-color'  => isset( $attr['prosBorder']['color']['color'] ) ? $attr['prosBorder']['color']['color'] : '#dddddd',
			),
			' .affx-pros-cons-inner-wrapper.layout-type-2 .affiliatex-pros' => array(
				'border-width'  => isset( $attr['contentBorderWidthTwo']['desktop']['top'] ) && isset( $attr['contentBorderWidthTwo']['desktop']['right'] ) && isset( $attr['contentBorderWidthTwo']['desktop']['bottom'] ) && isset( $attr['contentBorderWidthTwo']['desktop']['left'] ) ? $attr['contentBorderWidthTwo']['desktop']['top'] . ' ' . $attr['contentBorderWidthTwo']['desktop']['right'] . ' ' . $attr['contentBorderWidthTwo']['desktop']['bottom'] . ' ' . $attr['contentBorderWidthTwo']['desktop']['left'] . ' ' : '1px 1px 1px 1px',
				'border-radius' => isset( $attr['contentBorderRadiusTwo']['desktop']['top'] ) && isset( $attr['contentBorderRadiusTwo']['desktop']['right'] ) && isset( $attr['contentBorderRadiusTwo']['desktop']['bottom'] ) && isset( $attr['contentBorderRadiusTwo']['desktop']['left'] ) ? $attr['contentBorderRadiusTwo']['desktop']['top'] . ' ' . $attr['contentBorderRadiusTwo']['desktop']['right'] . ' ' . $attr['contentBorderRadiusTwo']['desktop']['bottom'] . ' ' . $attr['contentBorderRadiusTwo']['desktop']['left'] . ' ' : '0px 0px 0px 0px',
				'border-style'  => isset( $attr['prosContentBorder']['style'] ) ? $attr['prosContentBorder']['style'] : 'none',
				'border-color'  => isset( $attr['prosContentBorder']['color']['color'] ) ? $attr['prosContentBorder']['color']['color'] : '', // dddddd',
			),
			' .affx-pros-cons-inner-wrapper.layout-type-4 .pros-icon-title-wrap .affiliatex-block-pros' => array(
				'border-width'  => isset( $attr['titleBorderWidthFour']['desktop']['top'] ) && isset( $attr['titleBorderWidthFour']['desktop']['right'] ) && isset( $attr['titleBorderWidthFour']['desktop']['bottom'] ) && isset( $attr['titleBorderWidthFour']['desktop']['left'] ) ? $attr['titleBorderWidthFour']['desktop']['top'] . ' ' . $attr['titleBorderWidthFour']['desktop']['right'] . ' ' . $attr['titleBorderWidthFour']['desktop']['bottom'] . ' ' . $attr['titleBorderWidthFour']['desktop']['left'] . ' ' : '1px 1px 1px 1px',
				'border-radius' => isset( $attr['titleBorderRadiusFour']['desktop']['top'] ) && isset( $attr['titleBorderRadiusFour']['desktop']['right'] ) && isset( $attr['titleBorderRadiusFour']['desktop']['bottom'] ) && isset( $attr['titleBorderRadiusFour']['desktop']['left'] ) ? $attr['titleBorderRadiusFour']['desktop']['top'] . ' ' . $attr['titleBorderRadiusFour']['desktop']['right'] . ' ' . $attr['titleBorderRadiusFour']['desktop']['bottom'] . ' ' . $attr['titleBorderRadiusFour']['desktop']['left'] . ' ' : '0px 0px 0px 0px',
				'border-style'  => isset( $attr['prosBorder']['style'] ) ? $attr['prosBorder']['style'] : 'none',
				'border-color'  => isset( $attr['prosBorder']['color']['color'] ) ? $attr['prosBorder']['color']['color'] : '#dddddd',
			),
			' .affx-pros-cons-inner-wrapper.layout-type-1 .cons-icon-title-wrap .affiliatex-block-cons' => array(
				'border-width'  => isset( $attr['titleBorderWidthOne']['desktop']['top'] ) && isset( $attr['titleBorderWidthOne']['desktop']['right'] ) && isset( $attr['titleBorderWidthOne']['desktop']['bottom'] ) && isset( $attr['titleBorderWidthOne']['desktop']['left'] ) ? $attr['titleBorderWidthOne']['desktop']['top'] . ' ' . $attr['titleBorderWidthOne']['desktop']['right'] . ' ' . $attr['titleBorderWidthOne']['desktop']['bottom'] . ' ' . $attr['titleBorderWidthOne']['desktop']['left'] . ' ' : '1px 1px 1px 1px',
				'border-radius' => isset( $attr['titleBorderRadiusOne']['desktop']['top'] ) && isset( $attr['titleBorderRadiusOne']['desktop']['right'] ) && isset( $attr['titleBorderRadiusOne']['desktop']['bottom'] ) && isset( $attr['titleBorderRadiusOne']['desktop']['left'] ) ? $attr['titleBorderRadiusOne']['desktop']['top'] . ' ' . $attr['titleBorderRadiusOne']['desktop']['right'] . ' ' . $attr['titleBorderRadiusOne']['desktop']['bottom'] . ' ' . $attr['titleBorderRadiusOne']['desktop']['left'] . ' ' : '0px 0px 0px 0px',
				'border-style'  => isset( $attr['consBorder']['style'] ) ? $attr['consBorder']['style'] : 'none',
				'border-color'  => isset( $attr['consBorder']['color']['color'] ) ? $attr['consBorder']['color']['color'] : '#dddddd',
			),
			' .affx-pros-cons-inner-wrapper.layout-type-1 .affiliatex-cons' => array(
				'border-width'  => isset( $attr['contentBorderWidthOne']['desktop']['top'] ) && isset( $attr['contentBorderWidthOne']['desktop']['right'] ) && isset( $attr['contentBorderWidthOne']['desktop']['bottom'] ) && isset( $attr['contentBorderWidthOne']['desktop']['left'] ) ? $attr['contentBorderWidthOne']['desktop']['top'] . ' ' . $attr['contentBorderWidthOne']['desktop']['right'] . ' ' . $attr['contentBorderWidthOne']['desktop']['bottom'] . ' ' . $attr['contentBorderWidthOne']['desktop']['left'] . ' ' : '1px 1px 1px 1px',
				'border-radius' => isset( $attr['contentBorderRadiusOne']['desktop']['top'] ) && isset( $attr['contentBorderRadiusOne']['desktop']['right'] ) && isset( $attr['contentBorderRadiusOne']['desktop']['bottom'] ) && isset( $attr['contentBorderRadiusOne']['desktop']['left'] ) ? $attr['contentBorderRadiusOne']['desktop']['top'] . ' ' . $attr['contentBorderRadiusOne']['desktop']['right'] . ' ' . $attr['contentBorderRadiusOne']['desktop']['bottom'] . ' ' . $attr['contentBorderRadiusOne']['desktop']['left'] . ' ' : '0px 0px 0px 0px',
				'border-style'  => isset( $attr['consContentBorder']['style'] ) ? $attr['consContentBorder']['style'] : 'none',
				'border-color'  => isset( $attr['consContentBorder']['color']['color'] ) ? $attr['consContentBorder']['color']['color'] : '#dddddd',
			),
			' .affx-pros-cons-inner-wrapper.layout-type-2 .cons-icon-title-wrap .affiliatex-block-cons' => array(
				'border-width'  => isset( $attr['titleBorderWidthTwo']['desktop']['top'] ) && isset( $attr['titleBorderWidthTwo']['desktop']['right'] ) && isset( $attr['titleBorderWidthTwo']['desktop']['bottom'] ) && isset( $attr['titleBorderWidthTwo']['desktop']['left'] ) ? $attr['titleBorderWidthTwo']['desktop']['top'] . ' ' . $attr['titleBorderWidthTwo']['desktop']['right'] . ' ' . $attr['titleBorderWidthTwo']['desktop']['bottom'] . ' ' . $attr['titleBorderWidthTwo']['desktop']['left'] . ' ' : '1px 1px 1px 1px',
				'border-radius' => isset( $attr['titleBorderRadiusTwo']['desktop']['top'] ) && isset( $attr['titleBorderRadiusTwo']['desktop']['right'] ) && isset( $attr['titleBorderRadiusTwo']['desktop']['bottom'] ) && isset( $attr['titleBorderRadiusTwo']['desktop']['left'] ) ? $attr['titleBorderRadiusTwo']['desktop']['top'] . ' ' . $attr['titleBorderRadiusTwo']['desktop']['right'] . ' ' . $attr['titleBorderRadiusTwo']['desktop']['bottom'] . ' ' . $attr['titleBorderRadiusTwo']['desktop']['left'] . ' ' : '0px 0px 0px 0px',
				'border-style'  => isset( $attr['consBorder']['style'] ) ? $attr['consBorder']['style'] : 'none',
				'border-color'  => isset( $attr['consBorder']['color']['color'] ) ? $attr['consBorder']['color']['color'] : '#dddddd',
			),
			' .affx-pros-cons-inner-wrapper.layout-type-2 .affiliatex-cons' => array(
				'border-width'  => isset( $attr['contentBorderWidthTwo']['desktop']['top'] ) && isset( $attr['contentBorderWidthTwo']['desktop']['right'] ) && isset( $attr['contentBorderWidthTwo']['desktop']['bottom'] ) && isset( $attr['contentBorderWidthTwo']['desktop']['left'] ) ? $attr['contentBorderWidthTwo']['desktop']['top'] . ' ' . $attr['contentBorderWidthTwo']['desktop']['right'] . ' ' . $attr['contentBorderWidthTwo']['desktop']['bottom'] . ' ' . $attr['contentBorderWidthTwo']['desktop']['left'] . ' ' : '1px 1px 1px 1px',
				'border-radius' => isset( $attr['contentBorderRadiusTwo']['desktop']['top'] ) && isset( $attr['contentBorderRadiusTwo']['desktop']['right'] ) && isset( $attr['contentBorderRadiusTwo']['desktop']['bottom'] ) && isset( $attr['contentBorderRadiusTwo']['desktop']['left'] ) ? $attr['contentBorderRadiusTwo']['desktop']['top'] . ' ' . $attr['contentBorderRadiusTwo']['desktop']['right'] . ' ' . $attr['contentBorderRadiusTwo']['desktop']['bottom'] . ' ' . $attr['contentBorderRadiusTwo']['desktop']['left'] . ' ' : '0px 0px 0px 0px',
				'border-style'  => isset( $attr['consContentBorder']['style'] ) ? $attr['consContentBorder']['style'] : 'none',
				'border-color'  => isset( $attr['consContentBorder']['color']['color'] ) ? $attr['consContentBorder']['color']['color'] : '#dddddd',
			),
			' .affx-pros-cons-inner-wrapper.layout-type-4 .cons-icon-title-wrap .affiliatex-block-cons' => array(
				'border-width'  => isset( $attr['titleBorderWidthFour']['desktop']['top'] ) && isset( $attr['titleBorderWidthFour']['desktop']['right'] ) && isset( $attr['titleBorderWidthFour']['desktop']['bottom'] ) && isset( $attr['titleBorderWidthFour']['desktop']['left'] ) ? $attr['titleBorderWidthFour']['desktop']['top'] . ' ' . $attr['titleBorderWidthFour']['desktop']['right'] . ' ' . $attr['titleBorderWidthFour']['desktop']['bottom'] . ' ' . $attr['titleBorderWidthFour']['desktop']['left'] . ' ' : '1px 1px 1px 1px',
				'border-radius' => isset( $attr['titleBorderRadiusFour']['desktop']['top'] ) && isset( $attr['titleBorderRadiusFour']['desktop']['right'] ) && isset( $attr['titleBorderRadiusFour']['desktop']['bottom'] ) && isset( $attr['titleBorderRadiusFour']['desktop']['left'] ) ? $attr['titleBorderRadiusFour']['desktop']['top'] . ' ' . $attr['titleBorderRadiusFour']['desktop']['right'] . ' ' . $attr['titleBorderRadiusFour']['desktop']['bottom'] . ' ' . $attr['titleBorderRadiusFour']['desktop']['left'] . ' ' : '0px 0px 0px 0px',
				'border-style'  => isset( $attr['consBorder']['style'] ) ? $attr['consBorder']['style'] : 'none',
				'border-color'  => isset( $attr['consBorder']['color']['color'] ) ? $attr['consBorder']['color']['color'] : '#dddddd',
			),
			' .affiliatex-block-cons'                      => array(
				'text-align' => isset( $attr['alignment'] ) ? $attr['alignment'] : 'left',
				'background' => isset( $attr['consBgType'] ) && $attr['consBgType'] === 'gradient' ? $consBgGradient : $consBgColor,
			),
			' .affiliatex-block-cons .affiliatex-title'    => array(
				'font-family'     => isset( $attr['titleTypography']['family'] ) ? $attr['titleTypography']['family'] : $global_font_family,
				'font-weight'     => AffiliateX_Helpers::get_fontweight_variation( $variation ),
				'font-style'      => AffiliateX_Helpers::get_font_style( $variation ),
				'font-size'       => isset( $attr['titleTypography']['size']['desktop'] ) ? $attr['titleTypography']['size']['desktop'] : '20px',
				'line-height'     => isset( $attr['titleTypography']['line-height']['desktop'] ) ? $attr['titleTypography']['line-height']['desktop'] : '1.5',
				'text-transform'  => isset( $attr['titleTypography']['text-transform'] ) ? $attr['titleTypography']['text-transform'] : 'none',
				'text-decoration' => isset( $attr['titleTypography']['text-decoration'] ) ? $attr['titleTypography']['text-decoration'] : 'none',
				'letter-spacing'  => isset( $attr['titleTypography']['letter-spacing']['desktop'] ) ? $attr['titleTypography']['letter-spacing']['desktop'] : '0em',
				'color'           => isset( $attr['layoutStyle'] ) && $attr['layoutStyle'] === 'layout-type-3' ? $consTextColorThree : $consTextColor,
			),
			' .affiliatex-block-pros'                      => array(
				'text-align' => isset( $attr['alignment'] ) ? $attr['alignment'] : 'left',
				'background' => isset( $attr['prosBgType'] ) && $attr['prosBgType'] === 'gradient' ? $prosBgGradient : $prosBgColor,

			),
			' .affx-pros-inner .affiliatex-pros'           => array(
				'background' => isset( $attr['prosListBgType'] ) && $attr['prosListBgType'] === 'gradient' ? $consListBgGradient : $prosListBgColor,

			),
			' .affx-cons-inner .affiliatex-cons'           => array(
				'background' => isset( $attr['consListBgType'] ) && $attr['consListBgType'] === 'gradient' ? $consListBgGradient : $consListBgColor,

			),
			' .affiliatex-block-pros .affiliatex-title'    => array(
				'font-family'     => isset( $attr['titleTypography']['family'] ) ? $attr['titleTypography']['family'] : $global_font_family,
				'font-weight'     => AffiliateX_Helpers::get_fontweight_variation( $title_variation ),
				'font-style'      => AffiliateX_Helpers::get_font_style( $title_variation ),
				'font-size'       => isset( $attr['titleTypography']['size']['desktop'] ) ? $attr['titleTypography']['size']['desktop'] : '20px',
				'line-height'     => isset( $attr['titleTypography']['line-height']['desktop'] ) ? $attr['titleTypography']['line-height']['desktop'] : '1.5',
				'text-transform'  => isset( $attr['titleTypography']['text-transform'] ) ? $attr['titleTypography']['text-transform'] : 'none',
				'text-decoration' => isset( $attr['titleTypography']['text-decoration'] ) ? $attr['titleTypography']['text-decoration'] : 'none',
				'letter-spacing'  => isset( $attr['titleTypography']['letter-spacing']['desktop'] ) ? $attr['titleTypography']['letter-spacing']['desktop'] : '0em',
				'color'           => isset( $attr['layoutStyle'] ) && $attr['layoutStyle'] === 'layout-type-3' ? $prosTextColorThree : $prosTextColor,
			),
			' .affiliatex-content'                         => array(
				'margin-top'     => isset( $attr['contentMargin']['desktop']['top'] ) ? $attr['contentMargin']['desktop']['top'] : '0px',
				'margin-left'    => isset( $attr['contentMargin']['desktop']['left'] ) ? $attr['contentMargin']['desktop']['left'] : '0px',
				'margin-right'   => isset( $attr['contentMargin']['desktop']['right'] ) ? $attr['contentMargin']['desktop']['right'] : '0px',
				'margin-bottom'  => isset( $attr['contentMargin']['desktop']['bottom'] ) ? $attr['contentMargin']['desktop']['bottom'] : '0px',
				'padding-top'    => isset( $attr['contentPadding']['desktop']['top'] ) ? $attr['contentPadding']['desktop']['top'] : '10px',
				'padding-left'   => isset( $attr['contentPadding']['desktop']['left'] ) ? $attr['contentPadding']['desktop']['left'] : '20px',
				'padding-right'  => isset( $attr['contentPadding']['desktop']['right'] ) ? $attr['contentPadding']['desktop']['right'] : '20px',
				'padding-bottom' => isset( $attr['contentPadding']['desktop']['bottom'] ) ? $attr['contentPadding']['desktop']['bottom'] : '10px',
			),
			' .affiliatex-list'                            => array(
				'margin-top'     => isset( $attr['contentMargin']['desktop']['top'] ) ? $attr['contentMargin']['desktop']['top'] : '0px',
				'margin-left'    => isset( $attr['contentMargin']['desktop']['left'] ) ? $attr['contentMargin']['desktop']['left'] : '0px',
				'margin-right'   => isset( $attr['contentMargin']['desktop']['right'] ) ? $attr['contentMargin']['desktop']['right'] : '0px',
				'margin-bottom'  => isset( $attr['contentMargin']['desktop']['bottom'] ) ? $attr['contentMargin']['desktop']['bottom'] : '0px',
				'padding-top'    => isset( $attr['contentPadding']['desktop']['top'] ) ? $attr['contentPadding']['desktop']['top'] : '10px',
				'padding-left'   => isset( $attr['contentPadding']['desktop']['left'] ) ? $attr['contentPadding']['desktop']['left'] : '20px',
				'padding-right'  => isset( $attr['contentPadding']['desktop']['right'] ) ? $attr['contentPadding']['desktop']['right'] : '20px',
				'padding-bottom' => isset( $attr['contentPadding']['desktop']['bottom'] ) ? $attr['contentPadding']['desktop']['bottom'] : '10px',
			),
			' .affiliatex-cons'                            => array(
				'text-align' => isset( $attr['contentAlignment'] ) ? $attr['contentAlignment'] : 'left',
			),
			' .affiliatex-pros'                            => array(
				'text-align' => isset( $attr['contentAlignment'] ) ? $attr['contentAlignment'] : 'left',
			),
			' .affiliatex-cons p'                          => array(
				'font-family'     => isset( $attr['listTypography']['family'] ) ? $attr['listTypography']['family'] : $global_font_family,
				'font-weight'     => AffiliateX_Helpers::get_fontweight_variation( $list_variation ),
				'font-style'      => AffiliateX_Helpers::get_font_style( $list_variation ),
				'font-size'       => isset( $attr['listTypography']['size']['desktop'] ) ? $attr['listTypography']['size']['desktop'] : '18px',
				'line-height'     => isset( $attr['listTypography']['line-height']['desktop'] ) ? $attr['listTypography']['line-height']['desktop'] : '1.5',
				'text-transform'  => isset( $attr['listTypography']['text-transform'] ) ? $attr['listTypography']['text-transform'] : 'none',
				'text-decoration' => isset( $attr['listTypography']['text-decoration'] ) ? $attr['listTypography']['text-decoration'] : 'none',
				'letter-spacing'  => isset( $attr['listTypography']['letter-spacing']['desktop'] ) ? $attr['listTypography']['letter-spacing']['desktop'] : '0em',
				'color'           => isset( $attr['consListColor'] ) ? $attr['consListColor'] : $global_font_color,
			),
			' .affiliatex-cons li'                         => array(
				'font-family'     => isset( $attr['listTypography']['family'] ) ? $attr['listTypography']['family'] : $global_font_family,
				'font-weight'     => AffiliateX_Helpers::get_fontweight_variation( $list_variation ),
				'font-style'      => AffiliateX_Helpers::get_font_style( $list_variation ),
				'font-size'       => isset( $attr['listTypography']['size']['desktop'] ) ? $attr['listTypography']['size']['desktop'] : '18px',
				'line-height'     => isset( $attr['listTypography']['line-height']['desktop'] ) ? $attr['listTypography']['line-height']['desktop'] : '1.5',
				'text-transform'  => isset( $attr['listTypography']['text-transform'] ) ? $attr['listTypography']['text-transform'] : 'none',
				'text-decoration' => isset( $attr['listTypography']['text-decoration'] ) ? $attr['listTypography']['text-decoration'] : 'none',
				'letter-spacing'  => isset( $attr['listTypography']['letter-spacing']['desktop'] ) ? $attr['listTypography']['letter-spacing']['desktop'] : '0em',
				'color'           => isset( $attr['consListColor'] ) ? $attr['consListColor'] : $global_font_color,
				'display'         => $contentAlignment != 'left' ? 'block' : 'flex',
			),
			' .affiliatex-pros p'                          => array(
				'font-family'     => isset( $attr['listTypography']['family'] ) ? $attr['listTypography']['family'] : $global_font_family,
				'font-weight'     => AffiliateX_Helpers::get_fontweight_variation( $list_variation ),
				'font-style'      => AffiliateX_Helpers::get_font_style( $list_variation ),
				'font-size'       => isset( $attr['listTypography']['size']['desktop'] ) ? $attr['listTypography']['size']['desktop'] : '18px',
				'line-height'     => isset( $attr['listTypography']['line-height']['desktop'] ) ? $attr['listTypography']['line-height']['desktop'] : '1.5',
				'text-transform'  => isset( $attr['listTypography']['text-transform'] ) ? $attr['listTypography']['text-transform'] : 'none',
				'text-decoration' => isset( $attr['listTypography']['text-decoration'] ) ? $attr['listTypography']['text-decoration'] : 'none',
				'letter-spacing'  => isset( $attr['listTypography']['letter-spacing']['desktop'] ) ? $attr['listTypography']['letter-spacing']['desktop'] : '0em',
				'color'           => isset( $attr['prosListColor'] ) ? $attr['prosListColor'] : $global_font_color,
			),
			' .affiliatex-pros li'                         => array(
				'font-family'     => isset( $attr['listTypography']['family'] ) ? $attr['listTypography']['family'] : $global_font_family,
				'font-weight'     => AffiliateX_Helpers::get_fontweight_variation( $list_variation ),
				'font-style'      => AffiliateX_Helpers::get_font_style( $list_variation ),
				'font-size'       => isset( $attr['listTypography']['size']['desktop'] ) ? $attr['listTypography']['size']['desktop'] : '18px',
				'line-height'     => isset( $attr['listTypography']['line-height']['desktop'] ) ? $attr['listTypography']['line-height']['desktop'] : '1.5',
				'text-transform'  => isset( $attr['listTypography']['text-transform'] ) ? $attr['listTypography']['text-transform'] : 'none',
				'text-decoration' => isset( $attr['listTypography']['text-decoration'] ) ? $attr['listTypography']['text-decoration'] : 'none',
				'letter-spacing'  => isset( $attr['listTypography']['letter-spacing']['desktop'] ) ? $attr['listTypography']['letter-spacing']['desktop'] : '0em',
				'color'           => isset( $attr['prosListColor'] ) ? $attr['prosListColor'] : $global_font_color,
				'display'         => $contentAlignment != 'left' ? 'block' : 'flex',
			),
			' .affiliatex-block-pros .affiliatex-icon::before' => array(
				'font-size' => isset( $attr['prosIconSize'] ) ? $attr['prosIconSize'] . 'px' : '18px',
			),
			' .affiliatex-block-cons .affiliatex-icon::before' => array(
				'font-size' => isset( $attr['consIconSize'] ) ? $attr['consIconSize'] . 'px' : '18px',
			),
			' .affiliatex-pros ul li::before'              => array(
				'color' => isset( $attr['prosIconColor'] ) ? $attr['prosIconColor'] : '#24B644',
			),
			' .affiliatex-pros li::marker'                 => array(
				'color' => isset( $attr['prosIconColor'] ) ? $attr['prosIconColor'] : '#24B644',
			),
			' .affiliatex-pros ul.bullet li::before'       => array(
				'background' => isset( $attr['prosIconColor'] ) ? $attr['prosIconColor'] : '#24B644',
			),
			' .affiliatex-cons ul li::before'              => array(
				'color' => isset( $attr['consIconColor'] ) ? $attr['consIconColor'] : '#F13A3A',
			),
			' .affiliatex-cons li::marker'                 => array(
				'color' => isset( $attr['consIconColor'] ) ? $attr['consIconColor'] : '#F13A3A',
			),
			' .affiliatex-cons ul.bullet li::before'       => array(
				'background' => isset( $attr['consIconColor'] ) ? $attr['consIconColor'] : '#F13A3A',
			),
			' .affiliatex-pros ol li::before'              => array(
				'border-color' => isset( $attr['prosIconColor'] ) ? $attr['prosIconColor'] : '#F13A3A',
				'color'        => isset( $attr['prosIconColor'] ) ? $attr['prosIconColor'] : '#F13A3A',
			),
			' .affiliatex-cons ol li::before'              => array(
				'border-color' => isset( $attr['consIconColor'] ) ? $attr['consIconColor'] : '#F13A3A',
				'color'        => isset( $attr['consIconColor'] ) ? $attr['consIconColor'] : '#F13A3A',
			),
			' .affx-pros-cons-inner-wrapper.layout-type-3 .pros-icon-title-wrap' => array(
				'border-width'  => isset( $attr['contentBorderWidthThree']['desktop']['top'] ) && isset( $attr['contentBorderWidthThree']['desktop']['right'] ) && isset( $attr['contentBorderWidthThree']['desktop']['bottom'] ) && isset( $attr['contentBorderWidthThree']['desktop']['left'] ) ? $attr['contentBorderWidthThree']['desktop']['top'] . ' ' . $attr['contentBorderWidthThree']['desktop']['right'] . ' ' . $attr['contentBorderWidthThree']['desktop']['bottom'] . ' ' . $attr['contentBorderWidthThree']['desktop']['left'] . ' ' : '4px 4px 0 4px',
				'border-radius' => isset( $attr['contentBorderRadiusThree']['desktop']['top'] ) && isset( $attr['contentBorderRadiusThree']['desktop']['right'] ) && isset( $attr['contentBorderRadiusThree']['desktop']['bottom'] ) && isset( $attr['contentBorderRadiusThree']['desktop']['left'] ) ? $attr['contentBorderRadiusThree']['desktop']['top'] . ' ' . $attr['contentBorderRadiusThree']['desktop']['right'] . ' ' . $attr['contentBorderRadiusThree']['desktop']['bottom'] . ' ' . $attr['contentBorderRadiusThree']['desktop']['left'] . ' ' : '0px 0px 0px 0px',
				'border-style'  => isset( $attr['prosContentBorderThree']['style'] ) ? $attr['prosContentBorderThree']['style'] : 'solid',
				'border-color'  => isset( $attr['prosContentBorderThree']['color']['color'] ) ? $attr['prosContentBorderThree']['color']['color'] : '#24B644',
			),
			' .affx-pros-cons-inner-wrapper.layout-type-3 .cons-icon-title-wrap' => array(
				'border-width'  => isset( $attr['contentBorderWidthThree']['desktop']['top'] ) && isset( $attr['contentBorderWidthThree']['desktop']['right'] ) && isset( $attr['contentBorderWidthThree']['desktop']['bottom'] ) && isset( $attr['contentBorderWidthThree']['desktop']['left'] ) ? $attr['contentBorderWidthThree']['desktop']['top'] . ' ' . $attr['contentBorderWidthThree']['desktop']['right'] . ' ' . $attr['contentBorderWidthThree']['desktop']['bottom'] . ' ' . $attr['contentBorderWidthThree']['desktop']['left'] . ' ' : '4px 4px 0 4px',
				'border-radius' => isset( $attr['contentBorderRadiusThree']['desktop']['top'] ) && isset( $attr['contentBorderRadiusThree']['desktop']['right'] ) && isset( $attr['contentBorderRadiusThree']['desktop']['bottom'] ) && isset( $attr['contentBorderRadiusThree']['desktop']['left'] ) ? $attr['contentBorderRadiusThree']['desktop']['top'] . ' ' . $attr['contentBorderRadiusThree']['desktop']['right'] . ' ' . $attr['contentBorderRadiusThree']['desktop']['bottom'] . ' ' . $attr['contentBorderRadiusThree']['desktop']['left'] . ' ' : '0px 0px 0px 0px',
				'border-style'  => isset( $attr['consContentBorderThree']['style'] ) ? $attr['consContentBorderThree']['style'] : 'solid',
				'border-color'  => isset( $attr['consContentBorderThree']['color']['color'] ) ? $attr['consContentBorderThree']['color']['color'] : '#F13A3A',
			),
			' .affx-pros-cons-inner-wrapper.layout-type-3 .affiliatex-pros' => array(
				'border-width'  => isset( $attr['contentBorderWidthThree']['desktop']['top'] ) && isset( $attr['contentBorderWidthThree']['desktop']['right'] ) && isset( $attr['contentBorderWidthThree']['desktop']['bottom'] ) && isset( $attr['contentBorderWidthThree']['desktop']['left'] ) ? $attr['contentBorderWidthThree']['desktop']['top'] . ' ' . $attr['contentBorderWidthThree']['desktop']['right'] . ' ' . $attr['contentBorderWidthThree']['desktop']['bottom'] . ' ' . $attr['contentBorderWidthThree']['desktop']['left'] . ' ' : '0 4px 4px 4px',
				'border-radius' => isset( $attr['contentBorderRadiusThree']['desktop']['top'] ) && isset( $attr['contentBorderRadiusThree']['desktop']['right'] ) && isset( $attr['contentBorderRadiusThree']['desktop']['bottom'] ) && isset( $attr['contentBorderRadiusThree']['desktop']['left'] ) ? $attr['contentBorderRadiusThree']['desktop']['top'] . ' ' . $attr['contentBorderRadiusThree']['desktop']['right'] . ' ' . $attr['contentBorderRadiusThree']['desktop']['bottom'] . ' ' . $attr['contentBorderRadiusThree']['desktop']['left'] . ' ' : '0px 0px 0px 0px',
				'border-style'  => isset( $attr['prosContentBorderThree']['style'] ) ? $attr['prosContentBorderThree']['style'] : 'solid',
				'border-color'  => isset( $attr['prosContentBorderThree']['color']['color'] ) ? $attr['prosContentBorderThree']['color']['color'] : '#24B644',
			),
			' .affx-pros-cons-inner-wrapper.layout-type-3 .affiliatex-cons' => array(
				'border-width'  => isset( $attr['contentBorderWidthThree']['desktop']['top'] ) && isset( $attr['contentBorderWidthThree']['desktop']['right'] ) && isset( $attr['contentBorderWidthThree']['desktop']['bottom'] ) && isset( $attr['contentBorderWidthThree']['desktop']['left'] ) ? $attr['contentBorderWidthThree']['desktop']['top'] . ' ' . $attr['contentBorderWidthThree']['desktop']['right'] . ' ' . $attr['contentBorderWidthThree']['desktop']['bottom'] . ' ' . $attr['contentBorderWidthThree']['desktop']['left'] . ' ' : '0 4px 4px 4px',
				'border-radius' => isset( $attr['contentBorderRadiusThree']['desktop']['top'] ) && isset( $attr['contentBorderRadiusThree']['desktop']['right'] ) && isset( $attr['contentBorderRadiusThree']['desktop']['bottom'] ) && isset( $attr['contentBorderRadiusThree']['desktop']['left'] ) ? $attr['contentBorderRadiusThree']['desktop']['top'] . ' ' . $attr['contentBorderRadiusThree']['desktop']['right'] . ' ' . $attr['contentBorderRadiusThree']['desktop']['bottom'] . ' ' . $attr['contentBorderRadiusThree']['desktop']['left'] . ' ' : '0px 0px 0px 0px',
				'border-style'  => isset( $attr['consContentBorderThree']['style'] ) ? $attr['consContentBorderThree']['style'] : 'solid',
				'border-color'  => isset( $attr['consContentBorderThree']['color']['color'] ) ? $attr['consContentBorderThree']['color']['color'] : '#F13A3A',
			),
			' .affx-pros-cons-inner-wrapper.layout-type-3 .affiliatex-block-pros .affiliatex-title:before' => array(
				'border-width'  => isset( $attr['titleBorderWidthThree']['desktop']['top'] ) && isset( $attr['titleBorderWidthThree']['desktop']['right'] ) && isset( $attr['titleBorderWidthThree']['desktop']['bottom'] ) && isset( $attr['titleBorderWidthThree']['desktop']['left'] ) ? $attr['titleBorderWidthThree']['desktop']['top'] . ' ' . $attr['titleBorderWidthThree']['desktop']['right'] . ' ' . $attr['titleBorderWidthThree']['desktop']['bottom'] . ' ' . $attr['titleBorderWidthThree']['desktop']['left'] . ' ' : '4px 4px 4px 4px',
				'border-radius' => isset( $attr['titleBorderRadiusThree']['desktop']['top'] ) && isset( $attr['titleBorderRadiusThree']['desktop']['right'] ) && isset( $attr['titleBorderRadiusThree']['desktop']['bottom'] ) && isset( $attr['titleBorderRadiusThree']['desktop']['left'] ) ? $attr['titleBorderRadiusThree']['desktop']['top'] . ' ' . $attr['titleBorderRadiusThree']['desktop']['right'] . ' ' . $attr['titleBorderRadiusThree']['desktop']['bottom'] . ' ' . $attr['titleBorderRadiusThree']['desktop']['left'] . ' ' : '50px 50px 50px 50px',
				'border-style'  => isset( $attr['prosBorderThree']['style'] ) ? $attr['prosBorderThree']['style'] : 'solid',
				'border-color'  => isset( $attr['prosBorderThree']['color']['color'] ) ? $attr['prosBorderThree']['color']['color'] : '#ffffff',
				'background'    => isset( $attr['prosBgType'] ) && $attr['prosBgType'] === 'gradient' ? $prosBgGradient : $prosBgColor,
			),
			' .affx-pros-cons-inner-wrapper.layout-type-3 .affiliatex-block-cons .affiliatex-title:before' => array(
				'border-width'  => isset( $attr['titleBorderWidthThree']['desktop']['top'] ) && isset( $attr['titleBorderWidthThree']['desktop']['right'] ) && isset( $attr['titleBorderWidthThree']['desktop']['bottom'] ) && isset( $attr['titleBorderWidthThree']['desktop']['left'] ) ? $attr['titleBorderWidthThree']['desktop']['top'] . ' ' . $attr['titleBorderWidthThree']['desktop']['right'] . ' ' . $attr['titleBorderWidthThree']['desktop']['bottom'] . ' ' . $attr['titleBorderWidthThree']['desktop']['left'] . ' ' : '4px 4px 4px 4px',
				'border-radius' => isset( $attr['titleBorderRadiusThree']['desktop']['top'] ) && isset( $attr['titleBorderRadiusThree']['desktop']['right'] ) && isset( $attr['titleBorderRadiusThree']['desktop']['bottom'] ) && isset( $attr['titleBorderRadiusThree']['desktop']['left'] ) ? $attr['titleBorderRadiusThree']['desktop']['top'] . ' ' . $attr['titleBorderRadiusThree']['desktop']['right'] . ' ' . $attr['titleBorderRadiusThree']['desktop']['bottom'] . ' ' . $attr['titleBorderRadiusThree']['desktop']['left'] . ' ' : '50px 50px 50px 50px',
				'border-style'  => isset( $attr['consBorderThree']['style'] ) ? $attr['consBorderThree']['style'] : 'solid',
				'border-color'  => isset( $attr['consBorderThree']['color']['color'] ) ? $attr['consBorderThree']['color']['color'] : '#ffffff',
				'background'    => isset( $attr['consBgType'] ) && $attr['consBgType'] === 'gradient' ? $consBgGradient : $consBgColor,
			),
			' .affx-pros-cons-inner-wrapper.layout-type-3 .affiliatex-block-pros .pros-title-icon' => array(
				'justify-content' => isset( $attr['alignmentThree'] ) ? $attr['alignmentThree'] : 'center',
			),
			' .affx-pros-cons-inner-wrapper.layout-type-3 .affiliatex-block-cons .cons-title-icon' => array(
				'justify-content' => isset( $attr['alignmentThree'] ) ? $attr['alignmentThree'] : 'center',
			),
			' .affx-pros-cons-inner-wrapper.layout-type-3 .affiliatex-block-pros' => array(
				'align-items'    => isset( $attr['alignmentThree'] ) ? $attr['alignmentThree'] : 'center',
				'background'     => isset( $attr['prosListBgType'] ) && $attr['prosListBgType'] === 'gradient' ? $prosListBgGradient : $prosListBgColor,
				'margin-top'     => '0px',
				'margin-left'    => '0px',
				'margin-right'   => '0px',
				'margin-bottom'  => '0px',
				'padding-top'    => '0px',
				'padding-left'   => '10px',
				'padding-right'  => '10px',
				'padding-bottom' => '0px',
			),
			' .affx-pros-cons-inner-wrapper.layout-type-3 .affiliatex-block-cons' => array(
				'align-items'    => isset( $attr['alignmentThree'] ) ? $attr['alignmentThree'] : 'center',
				'background'     => isset( $attr['consListBgType'] ) && $attr['consListBgType'] === 'gradient' ? $consListBgGradient : $consListBgColor,
				'margin-top'     => '0px',
				'margin-left'    => '0px',
				'margin-right'   => '0px',
				'margin-bottom'  => '0px',
				'padding-top'    => '0px',
				'padding-left'   => '10px',
				'padding-right'  => '10px',
				'padding-bottom' => '0px',
			),
			' .affx-pros-cons-inner-wrapper.layout-type-4 .affx-pros-inner .affiliatex-pros' => array(
				'background'    => 'transparent',
				'border'        => 'none',
				'border-radius' => '0',
			),
			' .affx-pros-cons-inner-wrapper.layout-type-4 .affx-cons-inner .affiliatex-cons' => array(
				'background'    => 'transparent',
				'border'        => 'none',
				'border-radius' => '0',
			),
			' .affx-pros-cons-inner-wrapper.layout-type-4 .affiliatex-pros ul li' => array(
				'border-width'  => isset( $attr['contentBorderWidthFour']['desktop']['top'] ) && isset( $attr['contentBorderWidthFour']['desktop']['right'] ) && isset( $attr['contentBorderWidthFour']['desktop']['bottom'] ) && isset( $attr['contentBorderWidthFour']['desktop']['left'] ) ? $attr['contentBorderWidthFour']['desktop']['top'] . ' ' . $attr['contentBorderWidthFour']['desktop']['right'] . ' ' . $attr['contentBorderWidthFour']['desktop']['bottom'] . ' ' . $attr['contentBorderWidthFour']['desktop']['left'] . ' ' : '1px 1px 1px 1px',
				'border-radius' => isset( $attr['contentBorderRadiusFour']['desktop']['top'] ) && isset( $attr['contentBorderRadiusFour']['desktop']['right'] ) && isset( $attr['contentBorderRadiusFour']['desktop']['bottom'] ) && isset( $attr['contentBorderRadiusFour']['desktop']['left'] ) ? $attr['contentBorderRadiusFour']['desktop']['top'] . ' ' . $attr['contentBorderRadiusFour']['desktop']['right'] . ' ' . $attr['contentBorderRadiusFour']['desktop']['bottom'] . ' ' . $attr['contentBorderRadiusFour']['desktop']['left'] . ' ' : '0px 0px 0px 0px',
				'border-style'  => isset( $attr['prosContentBorder']['style'] ) ? $attr['prosContentBorder']['style'] : 'none',
				'border-color'  => isset( $attr['prosContentBorder']['color']['color'] ) ? $attr['prosContentBorder']['color']['color'] : '#dddddd',
			),
			' .affx-pros-cons-inner-wrapper.layout-type-4 .affiliatex-cons ul li' => array(
				'border-width'  => isset( $attr['contentBorderWidthFour']['desktop']['top'] ) && isset( $attr['contentBorderWidthFour']['desktop']['right'] ) && isset( $attr['contentBorderWidthFour']['desktop']['bottom'] ) && isset( $attr['contentBorderWidthFour']['desktop']['left'] ) ? $attr['contentBorderWidthFour']['desktop']['top'] . ' ' . $attr['contentBorderWidthFour']['desktop']['right'] . ' ' . $attr['contentBorderWidthFour']['desktop']['bottom'] . ' ' . $attr['contentBorderWidthFour']['desktop']['left'] . ' ' : '1px 1px 1px 1px',
				'border-radius' => isset( $attr['contentBorderRadiusFour']['desktop']['top'] ) && isset( $attr['contentBorderRadiusFour']['desktop']['right'] ) && isset( $attr['contentBorderRadiusFour']['desktop']['bottom'] ) && isset( $attr['contentBorderRadiusFour']['desktop']['left'] ) ? $attr['contentBorderRadiusFour']['desktop']['top'] . ' ' . $attr['contentBorderRadiusFour']['desktop']['right'] . ' ' . $attr['contentBorderRadiusFour']['desktop']['bottom'] . ' ' . $attr['contentBorderRadiusFour']['desktop']['left'] . ' ' : '0px 0px 0px 0px',
				'border-style'  => isset( $attr['consContentBorder']['style'] ) ? $attr['consContentBorder']['style'] : 'none',
				'border-color'  => isset( $attr['consContentBorder']['color']['color'] ) ? $attr['consContentBorder']['color']['color'] : '#dddddd',
			),
			' .affx-pros-cons-inner-wrapper.layout-type-4 .affiliatex-list' => array(
				'padding' => '0',
			),
			' .affx-pros-cons-inner-wrapper.layout-type-4 .affiliatex-content li' => array(
				'padding-top'    => isset( $attr['contentPadding']['desktop']['top'] ) ? $attr['contentPadding']['desktop']['top'] : '10px',
				'padding-left'   => isset( $attr['contentPadding']['desktop']['left'] ) ? $attr['contentPadding']['desktop']['left'] : '20px',
				'padding-right'  => isset( $attr['contentPadding']['desktop']['right'] ) ? $attr['contentPadding']['desktop']['right'] : '20px',
				'padding-bottom' => isset( $attr['contentPadding']['desktop']['bottom'] ) ? $attr['contentPadding']['desktop']['bottom'] : '10px',
			),
			' .affx-pros-cons-inner-wrapper.layout-type-4 .affiliatex-list li' => array(
				'padding-top'    => isset( $attr['contentPadding']['desktop']['top'] ) ? $attr['contentPadding']['desktop']['top'] : '10px',
				'padding-left'   => isset( $attr['contentPadding']['desktop']['left'] ) ? $attr['contentPadding']['desktop']['left'] : '20px',
				'padding-right'  => isset( $attr['contentPadding']['desktop']['right'] ) ? $attr['contentPadding']['desktop']['right'] : '20px',
				'padding-bottom' => isset( $attr['contentPadding']['desktop']['bottom'] ) ? $attr['contentPadding']['desktop']['bottom'] : '10px',
			),
			' .affx-pros-cons-inner-wrapper.layout-type-4 .affiliatex-pros p' => array(
				'border-width'  => isset( $attr['contentBorderWidthFour']['desktop']['top'] ) && isset( $attr['contentBorderWidthFour']['desktop']['right'] ) && isset( $attr['contentBorderWidthFour']['desktop']['bottom'] ) && isset( $attr['contentBorderWidthFour']['desktop']['left'] ) ? $attr['contentBorderWidthFour']['desktop']['top'] . ' ' . $attr['contentBorderWidthFour']['desktop']['right'] . ' ' . $attr['contentBorderWidthFour']['desktop']['bottom'] . ' ' . $attr['contentBorderWidthFour']['desktop']['left'] . ' ' : '1px 1px 1px 1px',
				'border-radius' => isset( $attr['contentBorderRadiusFour']['desktop']['top'] ) && isset( $attr['contentBorderRadiusFour']['desktop']['right'] ) && isset( $attr['contentBorderRadiusFour']['desktop']['bottom'] ) && isset( $attr['contentBorderRadiusFour']['desktop']['left'] ) ? $attr['contentBorderRadiusFour']['desktop']['top'] . ' ' . $attr['contentBorderRadiusFour']['desktop']['right'] . ' ' . $attr['contentBorderRadiusFour']['desktop']['bottom'] . ' ' . $attr['contentBorderRadiusFour']['desktop']['left'] . ' ' : '0px 0px 0px 0px',
				'border-style'  => isset( $attr['prosContentBorder']['style'] ) ? $attr['prosContentBorder']['style'] : 'none',
				'border-color'  => isset( $attr['prosContentBorder']['color']['color'] ) ? $attr['prosContentBorder']['color']['color'] : '#dddddd',
				'background'    => isset( $attr['prosListBgType'] ) && $attr['prosListBgType'] === 'gradient' ? $prosListBgGradient : $prosListBgColor,
			),
			' .affx-pros-cons-inner-wrapper.layout-type-4 .affiliatex-cons p' => array(
				'border-width'  => isset( $attr['contentBorderWidthFour']['desktop']['top'] ) && isset( $attr['contentBorderWidthFour']['desktop']['right'] ) && isset( $attr['contentBorderWidthFour']['desktop']['bottom'] ) && isset( $attr['contentBorderWidthFour']['desktop']['left'] ) ? $attr['contentBorderWidthFour']['desktop']['top'] . ' ' . $attr['contentBorderWidthFour']['desktop']['right'] . ' ' . $attr['contentBorderWidthFour']['desktop']['bottom'] . ' ' . $attr['contentBorderWidthFour']['desktop']['left'] . ' ' : '1px 1px 1px 1px',
				'border-radius' => isset( $attr['contentBorderRadiusFour']['desktop']['top'] ) && isset( $attr['contentBorderRadiusFour']['desktop']['right'] ) && isset( $attr['contentBorderRadiusFour']['desktop']['bottom'] ) && isset( $attr['contentBorderRadiusFour']['desktop']['left'] ) ? $attr['contentBorderRadiusFour']['desktop']['top'] . ' ' . $attr['contentBorderRadiusFour']['desktop']['right'] . ' ' . $attr['contentBorderRadiusFour']['desktop']['bottom'] . ' ' . $attr['contentBorderRadiusFour']['desktop']['left'] . ' ' : '0px 0px 0px 0px',
				'border-style'  => isset( $attr['consContentBorder']['style'] ) ? $attr['consContentBorder']['style'] : 'none',
				'border-color'  => isset( $attr['consContentBorder']['color']['color'] ) ? $attr['consContentBorder']['color']['color'] : '#dddddd',
				'background'    => isset( $attr['consListBgType'] ) && $attr['consListBgType'] === 'gradient' ? $consListBgGradient : $consListBgColor,
				'margin-top'    => '10px',
			),
			' .affx-pros-cons-inner-wrapper.layout-type-4 .affiliatex-pros li' => array(
				'background' => isset( $attr['prosListBgType'] ) && $attr['prosListBgType'] === 'gradient' ? $prosListBgGradient : $prosListBgColor,
			),
			' .affx-pros-cons-inner-wrapper.layout-type-4 .affiliatex-cons li' => array(
				'background' => isset( $attr['consListBgType'] ) && $attr['consListBgType'] === 'gradient' ? $consListBgGradient : $consListBgColor,
			),
			' .affiliatex-pros ul'                         => array(
				'list-style' => $contentType == 'list' &&
				$listType == 'unordered' &&
				$unorderedType == 'icon'
					? 'none'
					: '',
			),
			' .affiliatex-cons ul'                         => array(
				'list-style' => $contentType == 'list' &&
				$listType == 'unordered' &&
				$unorderedType == 'icon'
					? 'none'
					: '',
			),

		);
		return $selector;
	}

	public static function get_mobileselectors( $attr ) {

		$mobile_selector = array(
			' .affx-pros-cons-inner-wrapper'            => array(
				'margin-top'     => isset( $attr['margin']['mobile']['top'] ) ? $attr['margin']['mobile']['top'] : '0px',
				'margin-left'    => isset( $attr['margin']['mobile']['left'] ) ? $attr['margin']['mobile']['left'] : '0px',
				'margin-right'   => isset( $attr['margin']['mobile']['right'] ) ? $attr['margin']['mobile']['right'] : '0px',
				'margin-bottom'  => isset( $attr['margin']['mobile']['bottom'] ) ? $attr['margin']['mobile']['bottom'] : '30px',
				'padding-top'    => isset( $attr['padding']['mobile']['top'] ) ? $attr['padding']['mobile']['top'] : '0px',
				'padding-left'   => isset( $attr['padding']['mobile']['left'] ) ? $attr['padding']['mobile']['left'] : '0px',
				'padding-right'  => isset( $attr['padding']['mobile']['right'] ) ? $attr['padding']['mobile']['right'] : '0px',
				'padding-bottom' => isset( $attr['padding']['mobile']['bottom'] ) ? $attr['padding']['mobile']['bottom'] : '0px',
			),
			' .pros-icon-title-wrap .affiliatex-block-pros' => array(
				'margin-top'     => isset( $attr['titleMargin']['mobile']['top'] ) ? $attr['titleMargin']['mobile']['top'] : '0px',
				'margin-left'    => isset( $attr['titleMargin']['mobile']['left'] ) ? $attr['titleMargin']['mobile']['left'] : '0px',
				'margin-right'   => isset( $attr['titleMargin']['mobile']['right'] ) ? $attr['titleMargin']['mobile']['right'] : '0px',
				'margin-bottom'  => isset( $attr['titleMargin']['mobile']['bottom'] ) ? $attr['titleMargin']['mobile']['bottom'] : '0px',
				'padding-top'    => isset( $attr['titlePadding']['mobile']['top'] ) ? $attr['titlePadding']['mobile']['top'] : '10px',
				'padding-left'   => isset( $attr['titlePadding']['mobile']['left'] ) ? $attr['titlePadding']['mobile']['left'] : '20px',
				'padding-right'  => isset( $attr['titlePadding']['mobile']['right'] ) ? $attr['titlePadding']['mobile']['right'] : '20px',
				'padding-bottom' => isset( $attr['titlePadding']['mobile']['bottom'] ) ? $attr['titlePadding']['mobile']['bottom'] : '10px',
			),
			' .cons-icon-title-wrap .affiliatex-block-cons' => array(
				'margin-top'     => isset( $attr['titleMargin']['mobile']['top'] ) ? $attr['titleMargin']['mobile']['top'] : '0px',
				'margin-left'    => isset( $attr['titleMargin']['mobile']['left'] ) ? $attr['titleMargin']['mobile']['left'] : '0px',
				'margin-right'   => isset( $attr['titleMargin']['mobile']['right'] ) ? $attr['titleMargin']['mobile']['right'] : '0px',
				'margin-bottom'  => isset( $attr['titleMargin']['mobile']['bottom'] ) ? $attr['titleMargin']['mobile']['bottom'] : '0px',
				'padding-top'    => isset( $attr['titlePadding']['mobile']['top'] ) ? $attr['titlePadding']['mobile']['top'] : '10px',
				'padding-left'   => isset( $attr['titlePadding']['mobile']['left'] ) ? $attr['titlePadding']['mobile']['left'] : '20px',
				'padding-right'  => isset( $attr['titlePadding']['mobile']['right'] ) ? $attr['titlePadding']['mobile']['right'] : '20px',
				'padding-bottom' => isset( $attr['titlePadding']['mobile']['bottom'] ) ? $attr['titlePadding']['mobile']['bottom'] : '10px',
			),
			' .affx-pros-cons-inner-wrapper.layout-type-1 .pros-icon-title-wrap .affiliatex-block-pros' => array(
				'border-width'  => isset( $attr['titleBorderWidthOne']['mobile']['top'] ) && isset( $attr['titleBorderWidthOne']['mobile']['right'] ) && isset( $attr['titleBorderWidthOne']['mobile']['bottom'] ) && isset( $attr['titleBorderWidthOne']['mobile']['left'] ) ? $attr['titleBorderWidthOne']['mobile']['top'] . ' ' . $attr['titleBorderWidthOne']['mobile']['right'] . ' ' . $attr['titleBorderWidthOne']['mobile']['bottom'] . ' ' . $attr['titleBorderWidthOne']['mobile']['left'] . ' ' : '1px 1px 1px 1px',
				'border-radius' => isset( $attr['titleBorderRadiusOne']['mobile']['top'] ) && isset( $attr['titleBorderRadiusOne']['mobile']['right'] ) && isset( $attr['titleBorderRadiusOne']['mobile']['bottom'] ) && isset( $attr['titleBorderRadiusOne']['mobile']['left'] ) ? $attr['titleBorderRadiusOne']['mobile']['top'] . ' ' . $attr['titleBorderRadiusOne']['mobile']['right'] . ' ' . $attr['titleBorderRadiusOne']['mobile']['bottom'] . ' ' . $attr['titleBorderRadiusOne']['mobile']['left'] . ' ' : '0px 0px 0px 0px',
			),
			' .affx-pros-cons-inner-wrapper.layout-type-1 .affiliatex-pros' => array(
				'border-width'  => isset( $attr['contentBorderWidthOne']['mobile']['top'] ) && isset( $attr['contentBorderWidthOne']['mobile']['right'] ) && isset( $attr['contentBorderWidthOne']['mobile']['bottom'] ) && isset( $attr['contentBorderWidthOne']['mobile']['left'] ) ? $attr['contentBorderWidthOne']['mobile']['top'] . ' ' . $attr['contentBorderWidthOne']['mobile']['right'] . ' ' . $attr['contentBorderWidthOne']['mobile']['bottom'] . ' ' . $attr['contentBorderWidthOne']['mobile']['left'] . ' ' : '1px 1px 1px 1px',
				'border-radius' => isset( $attr['contentBorderRadiusOne']['mobile']['top'] ) && isset( $attr['contentBorderRadiusOne']['mobile']['right'] ) && isset( $attr['contentBorderRadiusOne']['mobile']['bottom'] ) && isset( $attr['contentBorderRadiusOne']['mobile']['left'] ) ? $attr['contentBorderRadiusOne']['mobile']['top'] . ' ' . $attr['contentBorderRadiusOne']['mobile']['right'] . ' ' . $attr['contentBorderRadiusOne']['mobile']['bottom'] . ' ' . $attr['contentBorderRadiusOne']['mobile']['left'] . ' ' : '0px 0px 0px 0px',
			),
			' .affx-pros-cons-inner-wrapper.layout-type-2 .pros-icon-title-wrap .affiliatex-block-pros' => array(
				'border-width'  => isset( $attr['titleBorderWidthTwo']['mobile']['top'] ) && isset( $attr['titleBorderWidthTwo']['mobile']['right'] ) && isset( $attr['titleBorderWidthTwo']['mobile']['bottom'] ) && isset( $attr['titleBorderWidthTwo']['mobile']['left'] ) ? $attr['titleBorderWidthTwo']['mobile']['top'] . ' ' . $attr['titleBorderWidthTwo']['mobile']['right'] . ' ' . $attr['titleBorderWidthTwo']['mobile']['bottom'] . ' ' . $attr['titleBorderWidthTwo']['mobile']['left'] . ' ' : '1px 1px 1px 1px',
				'border-radius' => isset( $attr['titleBorderRadiusTwo']['mobile']['top'] ) && isset( $attr['titleBorderRadiusTwo']['mobile']['right'] ) && isset( $attr['titleBorderRadiusTwo']['mobile']['bottom'] ) && isset( $attr['titleBorderRadiusTwo']['mobile']['left'] ) ? $attr['titleBorderRadiusTwo']['mobile']['top'] . ' ' . $attr['titleBorderRadiusTwo']['mobile']['right'] . ' ' . $attr['titleBorderRadiusTwo']['mobile']['bottom'] . ' ' . $attr['titleBorderRadiusTwo']['mobile']['left'] . ' ' : '0px 0px 0px 0px',
			),
			' .affx-pros-cons-inner-wrapper.layout-type-2 .affiliatex-pros' => array(
				'border-width'  => isset( $attr['contentBorderWidthTwo']['mobile']['top'] ) && isset( $attr['contentBorderWidthTwo']['mobile']['right'] ) && isset( $attr['contentBorderWidthTwo']['mobile']['bottom'] ) && isset( $attr['contentBorderWidthTwo']['mobile']['left'] ) ? $attr['contentBorderWidthTwo']['mobile']['top'] . ' ' . $attr['contentBorderWidthTwo']['mobile']['right'] . ' ' . $attr['contentBorderWidthTwo']['mobile']['bottom'] . ' ' . $attr['contentBorderWidthTwo']['mobile']['left'] . ' ' : '1px 1px 1px 1px',
				'border-radius' => isset( $attr['contentBorderRadiusTwo']['mobile']['top'] ) && isset( $attr['contentBorderRadiusTwo']['mobile']['right'] ) && isset( $attr['contentBorderRadiusTwo']['mobile']['bottom'] ) && isset( $attr['contentBorderRadiusTwo']['mobile']['left'] ) ? $attr['contentBorderRadiusTwo']['mobile']['top'] . ' ' . $attr['contentBorderRadiusTwo']['mobile']['right'] . ' ' . $attr['contentBorderRadiusTwo']['mobile']['bottom'] . ' ' . $attr['contentBorderRadiusTwo']['mobile']['left'] . ' ' : '0px 0px 0px 0px',
			),
			' .affx-pros-cons-inner-wrapper.layout-type-4 .pros-icon-title-wrap .affiliatex-block-pros' => array(
				'border-width'  => isset( $attr['titleBorderWidthFour']['mobile']['top'] ) && isset( $attr['titleBorderWidthFour']['mobile']['right'] ) && isset( $attr['titleBorderWidthFour']['mobile']['bottom'] ) && isset( $attr['titleBorderWidthFour']['mobile']['left'] ) ? $attr['titleBorderWidthFour']['mobile']['top'] . ' ' . $attr['titleBorderWidthFour']['mobile']['right'] . ' ' . $attr['titleBorderWidthFour']['mobile']['bottom'] . ' ' . $attr['titleBorderWidthFour']['mobile']['left'] . ' ' : '1px 1px 1px 1px',
				'border-radius' => isset( $attr['titleBorderRadiusFour']['mobile']['top'] ) && isset( $attr['titleBorderRadiusFour']['mobile']['right'] ) && isset( $attr['titleBorderRadiusFour']['mobile']['bottom'] ) && isset( $attr['titleBorderRadiusFour']['mobile']['left'] ) ? $attr['titleBorderRadiusFour']['mobile']['top'] . ' ' . $attr['titleBorderRadiusFour']['mobile']['right'] . ' ' . $attr['titleBorderRadiusFour']['mobile']['bottom'] . ' ' . $attr['titleBorderRadiusFour']['mobile']['left'] . ' ' : '0px 0px 0px 0px',
			),
			' .affx-pros-cons-inner-wrapper.layout-type-1 .cons-icon-title-wrap .affiliatex-block-cons' => array(
				'border-width'  => isset( $attr['titleBorderWidthOne']['mobile']['top'] ) && isset( $attr['titleBorderWidthOne']['mobile']['right'] ) && isset( $attr['titleBorderWidthOne']['mobile']['bottom'] ) && isset( $attr['titleBorderWidthOne']['mobile']['left'] ) ? $attr['titleBorderWidthOne']['mobile']['top'] . ' ' . $attr['titleBorderWidthOne']['mobile']['right'] . ' ' . $attr['titleBorderWidthOne']['mobile']['bottom'] . ' ' . $attr['titleBorderWidthOne']['mobile']['left'] . ' ' : '1px 1px 1px 1px',
				'border-radius' => isset( $attr['titleBorderRadiusOne']['mobile']['top'] ) && isset( $attr['titleBorderRadiusOne']['mobile']['right'] ) && isset( $attr['titleBorderRadiusOne']['mobile']['bottom'] ) && isset( $attr['titleBorderRadiusOne']['mobile']['left'] ) ? $attr['titleBorderRadiusOne']['mobile']['top'] . ' ' . $attr['titleBorderRadiusOne']['mobile']['right'] . ' ' . $attr['titleBorderRadiusOne']['mobile']['bottom'] . ' ' . $attr['titleBorderRadiusOne']['mobile']['left'] . ' ' : '0px 0px 0px 0px',
			),
			' .affx-pros-cons-inner-wrapper.layout-type-1 .affiliatex-cons' => array(
				'border-width'  => isset( $attr['contentBorderWidthOne']['mobile']['top'] ) && isset( $attr['contentBorderWidthOne']['mobile']['right'] ) && isset( $attr['contentBorderWidthOne']['mobile']['bottom'] ) && isset( $attr['contentBorderWidthOne']['mobile']['left'] ) ? $attr['contentBorderWidthOne']['mobile']['top'] . ' ' . $attr['contentBorderWidthOne']['mobile']['right'] . ' ' . $attr['contentBorderWidthOne']['mobile']['bottom'] . ' ' . $attr['contentBorderWidthOne']['mobile']['left'] . ' ' : '1px 1px 1px 1px',
				'border-radius' => isset( $attr['contentBorderRadiusOne']['mobile']['top'] ) && isset( $attr['contentBorderRadiusOne']['mobile']['right'] ) && isset( $attr['contentBorderRadiusOne']['mobile']['bottom'] ) && isset( $attr['contentBorderRadiusOne']['mobile']['left'] ) ? $attr['contentBorderRadiusOne']['mobile']['top'] . ' ' . $attr['contentBorderRadiusOne']['mobile']['right'] . ' ' . $attr['contentBorderRadiusOne']['mobile']['bottom'] . ' ' . $attr['contentBorderRadiusOne']['mobile']['left'] . ' ' : '0px 0px 0px 0px',
			),
			' .affx-pros-cons-inner-wrapper.layout-type-2 .cons-icon-title-wrap .affiliatex-block-cons' => array(
				'border-width'  => isset( $attr['titleBorderWidthTwo']['mobile']['top'] ) && isset( $attr['titleBorderWidthTwo']['mobile']['right'] ) && isset( $attr['titleBorderWidthTwo']['mobile']['bottom'] ) && isset( $attr['titleBorderWidthTwo']['mobile']['left'] ) ? $attr['titleBorderWidthTwo']['mobile']['top'] . ' ' . $attr['titleBorderWidthTwo']['mobile']['right'] . ' ' . $attr['titleBorderWidthTwo']['mobile']['bottom'] . ' ' . $attr['titleBorderWidthTwo']['mobile']['left'] . ' ' : '1px 1px 1px 1px',
				'border-radius' => isset( $attr['titleBorderRadiusTwo']['mobile']['top'] ) && isset( $attr['titleBorderRadiusTwo']['mobile']['right'] ) && isset( $attr['titleBorderRadiusTwo']['mobile']['bottom'] ) && isset( $attr['titleBorderRadiusTwo']['mobile']['left'] ) ? $attr['titleBorderRadiusTwo']['mobile']['top'] . ' ' . $attr['titleBorderRadiusTwo']['mobile']['right'] . ' ' . $attr['titleBorderRadiusTwo']['mobile']['bottom'] . ' ' . $attr['titleBorderRadiusTwo']['mobile']['left'] . ' ' : '0px 0px 0px 0px',
			),
			' .affx-pros-cons-inner-wrapper.layout-type-2 .affiliatex-cons' => array(
				'border-width'  => isset( $attr['contentBorderWidthTwo']['mobile']['top'] ) && isset( $attr['contentBorderWidthTwo']['mobile']['right'] ) && isset( $attr['contentBorderWidthTwo']['mobile']['bottom'] ) && isset( $attr['contentBorderWidthTwo']['mobile']['left'] ) ? $attr['contentBorderWidthTwo']['mobile']['top'] . ' ' . $attr['contentBorderWidthTwo']['mobile']['right'] . ' ' . $attr['contentBorderWidthTwo']['mobile']['bottom'] . ' ' . $attr['contentBorderWidthTwo']['mobile']['left'] . ' ' : '1px 1px 1px 1px',
				'border-radius' => isset( $attr['contentBorderRadiusTwo']['mobile']['top'] ) && isset( $attr['contentBorderRadiusTwo']['mobile']['right'] ) && isset( $attr['contentBorderRadiusTwo']['mobile']['bottom'] ) && isset( $attr['contentBorderRadiusTwo']['mobile']['left'] ) ? $attr['contentBorderRadiusTwo']['mobile']['top'] . ' ' . $attr['contentBorderRadiusTwo']['mobile']['right'] . ' ' . $attr['contentBorderRadiusTwo']['mobile']['bottom'] . ' ' . $attr['contentBorderRadiusTwo']['mobile']['left'] . ' ' : '0px 0px 0px 0px',
			),
			' .affx-pros-cons-inner-wrapper.layout-type-4 .cons-icon-title-wrap .affiliatex-block-cons' => array(
				'border-width'  => isset( $attr['titleBorderWidthFour']['mobile']['top'] ) && isset( $attr['titleBorderWidthFour']['mobile']['right'] ) && isset( $attr['titleBorderWidthFour']['mobile']['bottom'] ) && isset( $attr['titleBorderWidthFour']['mobile']['left'] ) ? $attr['titleBorderWidthFour']['mobile']['top'] . ' ' . $attr['titleBorderWidthFour']['mobile']['right'] . ' ' . $attr['titleBorderWidthFour']['mobile']['bottom'] . ' ' . $attr['titleBorderWidthFour']['mobile']['left'] . ' ' : '1px 1px 1px 1px',
				'border-radius' => isset( $attr['titleBorderRadiusFour']['mobile']['top'] ) && isset( $attr['titleBorderRadiusFour']['mobile']['right'] ) && isset( $attr['titleBorderRadiusFour']['mobile']['bottom'] ) && isset( $attr['titleBorderRadiusFour']['mobile']['left'] ) ? $attr['titleBorderRadiusFour']['mobile']['top'] . ' ' . $attr['titleBorderRadiusFour']['mobile']['right'] . ' ' . $attr['titleBorderRadiusFour']['mobile']['bottom'] . ' ' . $attr['titleBorderRadiusFour']['mobile']['left'] . ' ' : '0px 0px 0px 0px',
			),
			' .affiliatex-block-cons .affiliatex-title' => array(
				'font-size'      => isset( $attr['titleTypography']['size']['mobile'] ) ? $attr['titleTypography']['size']['mobile'] : '20px',
				'line-height'    => isset( $attr['titleTypography']['line-height']['mobile'] ) ? $attr['titleTypography']['line-height']['mobile'] : '1.5',
				'letter-spacing' => isset( $attr['titleTypography']['letter-spacing']['mobile'] ) ? $attr['titleTypography']['letter-spacing']['mobile'] : '0em',
			),
			' .affiliatex-block-pros .affiliatex-title' => array(
				'font-size'      => isset( $attr['titleTypography']['size']['mobile'] ) ? $attr['titleTypography']['size']['mobile'] : '20px',
				'line-height'    => isset( $attr['titleTypography']['line-height']['mobile'] ) ? $attr['titleTypography']['line-height']['mobile'] : '1.5',
				'letter-spacing' => isset( $attr['titleTypography']['letter-spacing']['mobile'] ) ? $attr['titleTypography']['letter-spacing']['mobile'] : '0em',
			),
			' .affiliatex-content'                      => array(
				'margin-top'     => isset( $attr['contentMargin']['mobile']['top'] ) ? $attr['contentMargin']['mobile']['top'] : '0px',
				'margin-left'    => isset( $attr['contentMargin']['mobile']['left'] ) ? $attr['contentMargin']['mobile']['left'] : '0px',
				'margin-right'   => isset( $attr['contentMargin']['mobile']['right'] ) ? $attr['contentMargin']['mobile']['right'] : '0px',
				'margin-bottom'  => isset( $attr['contentMargin']['mobile']['bottom'] ) ? $attr['contentMargin']['mobile']['bottom'] : '0px',
				'padding-top'    => isset( $attr['contentPadding']['mobile']['top'] ) ? $attr['contentPadding']['mobile']['top'] : '10px',
				'padding-left'   => isset( $attr['contentPadding']['mobile']['left'] ) ? $attr['contentPadding']['mobile']['left'] : '20px',
				'padding-right'  => isset( $attr['contentPadding']['mobile']['right'] ) ? $attr['contentPadding']['mobile']['right'] : '20px',
				'padding-bottom' => isset( $attr['contentPadding']['mobile']['bottom'] ) ? $attr['contentPadding']['mobile']['bottom'] : '10px',
			),
			' .affiliatex-list'                         => array(
				'margin-top'     => isset( $attr['contentMargin']['mobile']['top'] ) ? $attr['contentMargin']['mobile']['top'] : '0px',
				'margin-left'    => isset( $attr['contentMargin']['mobile']['left'] ) ? $attr['contentMargin']['mobile']['left'] : '0px',
				'margin-right'   => isset( $attr['contentMargin']['mobile']['right'] ) ? $attr['contentMargin']['mobile']['right'] : '0px',
				'margin-bottom'  => isset( $attr['contentMargin']['mobile']['bottom'] ) ? $attr['contentMargin']['mobile']['bottom'] : '0px',
				'padding-top'    => isset( $attr['contentPadding']['mobile']['top'] ) ? $attr['contentPadding']['mobile']['top'] : '10px',
				'padding-left'   => isset( $attr['contentPadding']['mobile']['left'] ) ? $attr['contentPadding']['mobile']['left'] : '20px',
				'padding-right'  => isset( $attr['contentPadding']['mobile']['right'] ) ? $attr['contentPadding']['mobile']['right'] : '20px',
				'padding-bottom' => isset( $attr['contentPadding']['mobile']['bottom'] ) ? $attr['contentPadding']['mobile']['bottom'] : '10px',
			),
			' .affiliatex-cons p'                       => array(
				'font-size'      => isset( $attr['listTypography']['size']['mobile'] ) ? $attr['listTypography']['size']['mobile'] : '18px',
				'line-height'    => isset( $attr['listTypography']['line-height']['mobile'] ) ? $attr['listTypography']['line-height']['mobile'] : '1.5',
				'letter-spacing' => isset( $attr['listTypography']['letter-spacing']['mobile'] ) ? $attr['listTypography']['letter-spacing']['mobile'] : '0em',
			),
			' .affiliatex-cons li'                      => array(
				'font-size'      => isset( $attr['listTypography']['size']['mobile'] ) ? $attr['listTypography']['size']['mobile'] : '18px',
				'line-height'    => isset( $attr['listTypography']['line-height']['mobile'] ) ? $attr['listTypography']['line-height']['mobile'] : '1.5',
				'letter-spacing' => isset( $attr['listTypography']['letter-spacing']['mobile'] ) ? $attr['listTypography']['letter-spacing']['mobile'] : '0em',
			),
			' .affiliatex-pros p'                       => array(
				'font-size'      => isset( $attr['listTypography']['size']['mobile'] ) ? $attr['listTypography']['size']['mobile'] : '18px',
				'line-height'    => isset( $attr['listTypography']['line-height']['mobile'] ) ? $attr['listTypography']['line-height']['mobile'] : '1.5',
				'letter-spacing' => isset( $attr['listTypography']['letter-spacing']['mobile'] ) ? $attr['listTypography']['letter-spacing']['mobile'] : '0em',
			),
			' .affiliatex-pros li'                      => array(
				'font-size'      => isset( $attr['listTypography']['size']['mobile'] ) ? $attr['listTypography']['size']['mobile'] : '18px',
				'line-height'    => isset( $attr['listTypography']['line-height']['mobile'] ) ? $attr['listTypography']['line-height']['mobile'] : '1.5',
				'letter-spacing' => isset( $attr['listTypography']['letter-spacing']['mobile'] ) ? $attr['listTypography']['letter-spacing']['mobile'] : '0em',
			),

			' .affx-pros-cons-inner-wrapper.layout-type-3 .pros-icon-title-wrap' => array(
				'border-width'  => isset( $attr['contentBorderWidthThree']['mobile']['top'] ) && isset( $attr['contentBorderWidthThree']['mobile']['right'] ) && isset( $attr['contentBorderWidthThree']['mobile']['bottom'] ) && isset( $attr['contentBorderWidthThree']['mobile']['left'] ) ? $attr['contentBorderWidthThree']['mobile']['top'] . ' ' . $attr['contentBorderWidthThree']['mobile']['right'] . ' ' . $attr['contentBorderWidthThree']['mobile']['bottom'] . ' ' . $attr['contentBorderWidthThree']['mobile']['left'] . ' ' : '4px 4px 0 4px',
				'border-radius' => isset( $attr['contentBorderRadiusThree']['mobile']['top'] ) && isset( $attr['contentBorderRadiusThree']['mobile']['right'] ) && isset( $attr['contentBorderRadiusThree']['mobile']['bottom'] ) && isset( $attr['contentBorderRadiusThree']['mobile']['left'] ) ? $attr['contentBorderRadiusThree']['mobile']['top'] . ' ' . $attr['contentBorderRadiusThree']['mobile']['right'] . ' ' . $attr['contentBorderRadiusThree']['mobile']['bottom'] . ' ' . $attr['contentBorderRadiusThree']['mobile']['left'] . ' ' : '0px 0px 0px 0px',
			),
			' .affx-pros-cons-inner-wrapper.layout-type-3 .cons-icon-title-wrap' => array(
				'border-width'  => isset( $attr['contentBorderWidthThree']['mobile']['top'] ) && isset( $attr['contentBorderWidthThree']['mobile']['right'] ) && isset( $attr['contentBorderWidthThree']['mobile']['bottom'] ) && isset( $attr['contentBorderWidthThree']['mobile']['left'] ) ? $attr['contentBorderWidthThree']['mobile']['top'] . ' ' . $attr['contentBorderWidthThree']['mobile']['right'] . ' ' . $attr['contentBorderWidthThree']['mobile']['bottom'] . ' ' . $attr['contentBorderWidthThree']['mobile']['left'] . ' ' : '4px 4px 0 4px',
				'border-radius' => isset( $attr['contentBorderRadiusThree']['mobile']['top'] ) && isset( $attr['contentBorderRadiusThree']['mobile']['right'] ) && isset( $attr['contentBorderRadiusThree']['mobile']['bottom'] ) && isset( $attr['contentBorderRadiusThree']['mobile']['left'] ) ? $attr['contentBorderRadiusThree']['mobile']['top'] . ' ' . $attr['contentBorderRadiusThree']['mobile']['right'] . ' ' . $attr['contentBorderRadiusThree']['mobile']['bottom'] . ' ' . $attr['contentBorderRadiusThree']['mobile']['left'] . ' ' : '0px 0px 0px 0px',
			),
			' .affx-pros-cons-inner-wrapper.layout-type-3 .affiliatex-pros' => array(
				'border-width'  => isset( $attr['contentBorderWidthThree']['mobile']['top'] ) && isset( $attr['contentBorderWidthThree']['mobile']['right'] ) && isset( $attr['contentBorderWidthThree']['mobile']['bottom'] ) && isset( $attr['contentBorderWidthThree']['mobile']['left'] ) ? $attr['contentBorderWidthThree']['mobile']['top'] . ' ' . $attr['contentBorderWidthThree']['mobile']['right'] . ' ' . $attr['contentBorderWidthThree']['mobile']['bottom'] . ' ' . $attr['contentBorderWidthThree']['mobile']['left'] . ' ' : '0 4px 4px 4px',
				'border-radius' => isset( $attr['contentBorderRadiusThree']['mobile']['top'] ) && isset( $attr['contentBorderRadiusThree']['mobile']['right'] ) && isset( $attr['contentBorderRadiusThree']['mobile']['bottom'] ) && isset( $attr['contentBorderRadiusThree']['mobile']['left'] ) ? $attr['contentBorderRadiusThree']['mobile']['top'] . ' ' . $attr['contentBorderRadiusThree']['mobile']['right'] . ' ' . $attr['contentBorderRadiusThree']['mobile']['bottom'] . ' ' . $attr['contentBorderRadiusThree']['mobile']['left'] . ' ' : '0px 0px 0px 0px',
			),
			' .affx-pros-cons-inner-wrapper.layout-type-3 .affiliatex-cons' => array(
				'border-width'  => isset( $attr['contentBorderWidthThree']['mobile']['top'] ) && isset( $attr['contentBorderWidthThree']['mobile']['right'] ) && isset( $attr['contentBorderWidthThree']['mobile']['bottom'] ) && isset( $attr['contentBorderWidthThree']['mobile']['left'] ) ? $attr['contentBorderWidthThree']['mobile']['top'] . ' ' . $attr['contentBorderWidthThree']['mobile']['right'] . ' ' . $attr['contentBorderWidthThree']['mobile']['bottom'] . ' ' . $attr['contentBorderWidthThree']['mobile']['left'] . ' ' : '0 4px 4px 4px',
				'border-radius' => isset( $attr['contentBorderRadiusThree']['mobile']['top'] ) && isset( $attr['contentBorderRadiusThree']['mobile']['right'] ) && isset( $attr['contentBorderRadiusThree']['mobile']['bottom'] ) && isset( $attr['contentBorderRadiusThree']['mobile']['left'] ) ? $attr['contentBorderRadiusThree']['mobile']['top'] . ' ' . $attr['contentBorderRadiusThree']['mobile']['right'] . ' ' . $attr['contentBorderRadiusThree']['mobile']['bottom'] . ' ' . $attr['contentBorderRadiusThree']['mobile']['left'] . ' ' : '0px 0px 0px 0px',
			),
			' .affx-pros-cons-inner-wrapper.layout-type-3 .affiliatex-block-pros .affiliatex-title:before' => array(
				'border-width'  => isset( $attr['titleBorderWidthThree']['mobile']['top'] ) && isset( $attr['titleBorderWidthThree']['mobile']['right'] ) && isset( $attr['titleBorderWidthThree']['mobile']['bottom'] ) && isset( $attr['titleBorderWidthThree']['mobile']['left'] ) ? $attr['titleBorderWidthThree']['mobile']['top'] . ' ' . $attr['titleBorderWidthThree']['mobile']['right'] . ' ' . $attr['titleBorderWidthThree']['mobile']['bottom'] . ' ' . $attr['titleBorderWidthThree']['mobile']['left'] . ' ' : '4px 4px 4px 4px',
				'border-radius' => isset( $attr['titleBorderRadiusThree']['mobile']['top'] ) && isset( $attr['titleBorderRadiusThree']['mobile']['right'] ) && isset( $attr['titleBorderRadiusThree']['mobile']['bottom'] ) && isset( $attr['titleBorderRadiusThree']['mobile']['left'] ) ? $attr['titleBorderRadiusThree']['mobile']['top'] . ' ' . $attr['titleBorderRadiusThree']['mobile']['right'] . ' ' . $attr['titleBorderRadiusThree']['mobile']['bottom'] . ' ' . $attr['titleBorderRadiusThree']['mobile']['left'] . ' ' : '50px 50px 50px 50px',
			),
			' .affx-pros-cons-inner-wrapper.layout-type-3 .affiliatex-block-cons .affiliatex-title:before' => array(
				'border-width'  => isset( $attr['titleBorderWidthThree']['mobile']['top'] ) && isset( $attr['titleBorderWidthThree']['mobile']['right'] ) && isset( $attr['titleBorderWidthThree']['mobile']['bottom'] ) && isset( $attr['titleBorderWidthThree']['mobile']['left'] ) ? $attr['titleBorderWidthThree']['mobile']['top'] . ' ' . $attr['titleBorderWidthThree']['mobile']['right'] . ' ' . $attr['titleBorderWidthThree']['mobile']['bottom'] . ' ' . $attr['titleBorderWidthThree']['mobile']['left'] . ' ' : '4px 4px 4px 4px',
				'border-radius' => isset( $attr['titleBorderRadiusThree']['mobile']['top'] ) && isset( $attr['titleBorderRadiusThree']['mobile']['right'] ) && isset( $attr['titleBorderRadiusThree']['mobile']['bottom'] ) && isset( $attr['titleBorderRadiusThree']['mobile']['left'] ) ? $attr['titleBorderRadiusThree']['mobile']['top'] . ' ' . $attr['titleBorderRadiusThree']['mobile']['right'] . ' ' . $attr['titleBorderRadiusThree']['mobile']['bottom'] . ' ' . $attr['titleBorderRadiusThree']['mobile']['left'] . ' ' : '50px 50px 50px 50px',
			),

			' .affx-pros-cons-inner-wrapper.layout-type-4  .affiliatex-pros ul li' => array(
				'border-width'  => isset( $attr['contentBorderWidthFour']['mobile']['top'] ) && isset( $attr['contentBorderWidthFour']['mobile']['right'] ) && isset( $attr['contentBorderWidthFour']['mobile']['bottom'] ) && isset( $attr['contentBorderWidthFour']['mobile']['left'] ) ? $attr['contentBorderWidthFour']['mobile']['top'] . ' ' . $attr['contentBorderWidthFour']['mobile']['right'] . ' ' . $attr['contentBorderWidthFour']['mobile']['bottom'] . ' ' . $attr['contentBorderWidthFour']['mobile']['left'] . ' ' : '1px 1px 1px 1px',
				'border-radius' => isset( $attr['contentBorderRadiusFour']['mobile']['top'] ) && isset( $attr['contentBorderRadiusFour']['mobile']['right'] ) && isset( $attr['contentBorderRadiusFour']['mobile']['bottom'] ) && isset( $attr['contentBorderRadiusFour']['mobile']['left'] ) ? $attr['contentBorderRadiusFour']['mobile']['top'] . ' ' . $attr['contentBorderRadiusFour']['mobile']['right'] . ' ' . $attr['contentBorderRadiusFour']['mobile']['bottom'] . ' ' . $attr['contentBorderRadiusFour']['mobile']['left'] . ' ' : '0px 0px 0px 0px',
			),
			' .affx-pros-cons-inner-wrapper.layout-type-4  .affiliatex-cons ul li' => array(
				'border-width'  => isset( $attr['contentBorderWidthFour']['mobile']['top'] ) && isset( $attr['contentBorderWidthFour']['mobile']['right'] ) && isset( $attr['contentBorderWidthFour']['mobile']['bottom'] ) && isset( $attr['contentBorderWidthFour']['mobile']['left'] ) ? $attr['contentBorderWidthFour']['mobile']['top'] . ' ' . $attr['contentBorderWidthFour']['mobile']['right'] . ' ' . $attr['contentBorderWidthFour']['mobile']['bottom'] . ' ' . $attr['contentBorderWidthFour']['mobile']['left'] . ' ' : '1px 1px 1px 1px',
				'border-radius' => isset( $attr['contentBorderRadiusFour']['mobile']['top'] ) && isset( $attr['contentBorderRadiusFour']['mobile']['right'] ) && isset( $attr['contentBorderRadiusFour']['mobile']['bottom'] ) && isset( $attr['contentBorderRadiusFour']['mobile']['left'] ) ? $attr['contentBorderRadiusFour']['mobile']['top'] . ' ' . $attr['contentBorderRadiusFour']['mobile']['right'] . ' ' . $attr['contentBorderRadiusFour']['mobile']['bottom'] . ' ' . $attr['contentBorderRadiusFour']['mobile']['left'] . ' ' : '0px 0px 0px 0px',
			),
			' .affx-pros-cons-inner-wrapper.layout-type-4  .affiliatex-content li' => array(
				'padding-top'    => isset( $attr['contentPadding']['mobile']['top'] ) ? $attr['contentPadding']['mobile']['top'] : '10px',
				'padding-left'   => isset( $attr['contentPadding']['mobile']['left'] ) ? $attr['contentPadding']['mobile']['left'] : '20px',
				'padding-right'  => isset( $attr['contentPadding']['mobile']['right'] ) ? $attr['contentPadding']['mobile']['right'] : '20px',
				'padding-bottom' => isset( $attr['contentPadding']['mobile']['bottom'] ) ? $attr['contentPadding']['mobile']['bottom'] : '10px',
			),
			' .affx-pros-cons-inner-wrapper.layout-type-4  .affiliatex-list li' => array(
				'padding-top'    => isset( $attr['contentPadding']['mobile']['top'] ) ? $attr['contentPadding']['mobile']['top'] : '10px',
				'padding-left'   => isset( $attr['contentPadding']['mobile']['left'] ) ? $attr['contentPadding']['mobile']['left'] : '20px',
				'padding-right'  => isset( $attr['contentPadding']['mobile']['right'] ) ? $attr['contentPadding']['mobile']['right'] : '20px',
				'padding-bottom' => isset( $attr['contentPadding']['mobile']['bottom'] ) ? $attr['contentPadding']['mobile']['bottom'] : '10px',
			),
			' .affx-pros-cons-inner-wrapper.layout-type-4  .affiliatex-pros p' => array(
				'border-width'  => isset( $attr['contentBorderWidthFour']['mobile']['top'] ) && isset( $attr['contentBorderWidthFour']['mobile']['right'] ) && isset( $attr['contentBorderWidthFour']['mobile']['bottom'] ) && isset( $attr['contentBorderWidthFour']['mobile']['left'] ) ? $attr['contentBorderWidthFour']['mobile']['top'] . ' ' . $attr['contentBorderWidthFour']['mobile']['right'] . ' ' . $attr['contentBorderWidthFour']['mobile']['bottom'] . ' ' . $attr['contentBorderWidthFour']['mobile']['left'] . ' ' : '1px 1px 1px 1px',
				'border-radius' => isset( $attr['contentBorderRadiusFour']['mobile']['top'] ) && isset( $attr['contentBorderRadiusFour']['mobile']['right'] ) && isset( $attr['contentBorderRadiusFour']['mobile']['bottom'] ) && isset( $attr['contentBorderRadiusFour']['mobile']['left'] ) ? $attr['contentBorderRadiusFour']['mobile']['top'] . ' ' . $attr['contentBorderRadiusFour']['mobile']['right'] . ' ' . $attr['contentBorderRadiusFour']['mobile']['bottom'] . ' ' . $attr['contentBorderRadiusFour']['mobile']['left'] . ' ' : '0px 0px 0px 0px',
			),
			' .affx-pros-cons-inner-wrapper.layout-type-4  .affiliatex-cons p' => array(
				'border-width'  => isset( $attr['contentBorderWidthFour']['mobile']['top'] ) && isset( $attr['contentBorderWidthFour']['mobile']['right'] ) && isset( $attr['contentBorderWidthFour']['mobile']['bottom'] ) && isset( $attr['contentBorderWidthFour']['mobile']['left'] ) ? $attr['contentBorderWidthFour']['mobile']['top'] . ' ' . $attr['contentBorderWidthFour']['mobile']['right'] . ' ' . $attr['contentBorderWidthFour']['mobile']['bottom'] . ' ' . $attr['contentBorderWidthFour']['mobile']['left'] . ' ' : '1px 1px 1px 1px',
				'border-radius' => isset( $attr['contentBorderRadiusFour']['mobile']['top'] ) && isset( $attr['contentBorderRadiusFour']['mobile']['right'] ) && isset( $attr['contentBorderRadiusFour']['mobile']['bottom'] ) && isset( $attr['contentBorderRadiusFour']['mobile']['left'] ) ? $attr['contentBorderRadiusFour']['mobile']['top'] . ' ' . $attr['contentBorderRadiusFour']['mobile']['right'] . ' ' . $attr['contentBorderRadiusFour']['mobile']['bottom'] . ' ' . $attr['contentBorderRadiusFour']['mobile']['left'] . ' ' : '0px 0px 0px 0px',
			),
		);
		return $mobile_selector;
	}

	public static function get_tabletselectors( $attr ) {

		$tablet_selector = array(
			' .affx-pros-cons-inner-wrapper'            => array(
				'margin-top'     => isset( $attr['margin']['tablet']['top'] ) ? $attr['margin']['tablet']['top'] : '0px',
				'margin-left'    => isset( $attr['margin']['tablet']['left'] ) ? $attr['margin']['tablet']['left'] : '0px',
				'margin-right'   => isset( $attr['margin']['tablet']['right'] ) ? $attr['margin']['tablet']['right'] : '0px',
				'margin-bottom'  => isset( $attr['margin']['tablet']['bottom'] ) ? $attr['margin']['tablet']['bottom'] : '30px',
				'padding-top'    => isset( $attr['padding']['tablet']['top'] ) ? $attr['padding']['tablet']['top'] : '0px',
				'padding-left'   => isset( $attr['padding']['tablet']['left'] ) ? $attr['padding']['tablet']['left'] : '0px',
				'padding-right'  => isset( $attr['padding']['tablet']['right'] ) ? $attr['padding']['tablet']['right'] : '0px',
				'padding-bottom' => isset( $attr['padding']['tablet']['bottom'] ) ? $attr['padding']['tablet']['bottom'] : '0px',
			),
			' .pros-icon-title-wrap .affiliatex-block-pros' => array(
				'margin-top'     => isset( $attr['titleMargin']['tablet']['top'] ) ? $attr['titleMargin']['tablet']['top'] : '0px',
				'margin-left'    => isset( $attr['titleMargin']['tablet']['left'] ) ? $attr['titleMargin']['tablet']['left'] : '0px',
				'margin-right'   => isset( $attr['titleMargin']['tablet']['right'] ) ? $attr['titleMargin']['tablet']['right'] : '0px',
				'margin-bottom'  => isset( $attr['titleMargin']['tablet']['bottom'] ) ? $attr['titleMargin']['tablet']['bottom'] : '0px',
				'padding-top'    => isset( $attr['titlePadding']['tablet']['top'] ) ? $attr['titlePadding']['tablet']['top'] : '10px',
				'padding-left'   => isset( $attr['titlePadding']['tablet']['left'] ) ? $attr['titlePadding']['tablet']['left'] : '20px',
				'padding-right'  => isset( $attr['titlePadding']['tablet']['right'] ) ? $attr['titlePadding']['tablet']['right'] : '20px',
				'padding-bottom' => isset( $attr['titlePadding']['tablet']['bottom'] ) ? $attr['titlePadding']['tablet']['bottom'] : '10px',
			),
			' .cons-icon-title-wrap .affiliatex-block-cons' => array(
				'margin-top'     => isset( $attr['titleMargin']['tablet']['top'] ) ? $attr['titleMargin']['tablet']['top'] : '0px',
				'margin-left'    => isset( $attr['titleMargin']['tablet']['left'] ) ? $attr['titleMargin']['tablet']['left'] : '0px',
				'margin-right'   => isset( $attr['titleMargin']['tablet']['right'] ) ? $attr['titleMargin']['tablet']['right'] : '0px',
				'margin-bottom'  => isset( $attr['titleMargin']['tablet']['bottom'] ) ? $attr['titleMargin']['tablet']['bottom'] : '0px',
				'padding-top'    => isset( $attr['titlePadding']['tablet']['top'] ) ? $attr['titlePadding']['tablet']['top'] : '10px',
				'padding-left'   => isset( $attr['titlePadding']['tablet']['left'] ) ? $attr['titlePadding']['tablet']['left'] : '20px',
				'padding-right'  => isset( $attr['titlePadding']['tablet']['right'] ) ? $attr['titlePadding']['tablet']['right'] : '20px',
				'padding-bottom' => isset( $attr['titlePadding']['tablet']['bottom'] ) ? $attr['titlePadding']['tablet']['bottom'] : '10px',
			),
			' .affx-pros-cons-inner-wrapper.layout-type-1 .pros-icon-title-wrap .affiliatex-block-pros' => array(
				'border-width'  => isset( $attr['titleBorderWidthOne']['tablet']['top'] ) && isset( $attr['titleBorderWidthOne']['tablet']['right'] ) && isset( $attr['titleBorderWidthOne']['tablet']['bottom'] ) && isset( $attr['titleBorderWidthOne']['tablet']['left'] ) ? $attr['titleBorderWidthOne']['tablet']['top'] . ' ' . $attr['titleBorderWidthOne']['tablet']['right'] . ' ' . $attr['titleBorderWidthOne']['tablet']['bottom'] . ' ' . $attr['titleBorderWidthOne']['tablet']['left'] . ' ' : '1px 1px 1px 1px',
				'border-radius' => isset( $attr['titleBorderRadiusOne']['tablet']['top'] ) && isset( $attr['titleBorderRadiusOne']['tablet']['right'] ) && isset( $attr['titleBorderRadiusOne']['tablet']['bottom'] ) && isset( $attr['titleBorderRadiusOne']['tablet']['left'] ) ? $attr['titleBorderRadiusOne']['tablet']['top'] . ' ' . $attr['titleBorderRadiusOne']['tablet']['right'] . ' ' . $attr['titleBorderRadiusOne']['tablet']['bottom'] . ' ' . $attr['titleBorderRadiusOne']['tablet']['left'] . ' ' : '0px 0px 0px 0px',
			),
			' .affx-pros-cons-inner-wrapper.layout-type-1 .affiliatex-pros' => array(
				'border-width'  => isset( $attr['contentBorderWidthOne']['tablet']['top'] ) && isset( $attr['contentBorderWidthOne']['tablet']['right'] ) && isset( $attr['contentBorderWidthOne']['tablet']['bottom'] ) && isset( $attr['contentBorderWidthOne']['tablet']['left'] ) ? $attr['contentBorderWidthOne']['tablet']['top'] . ' ' . $attr['contentBorderWidthOne']['tablet']['right'] . ' ' . $attr['contentBorderWidthOne']['tablet']['bottom'] . ' ' . $attr['contentBorderWidthOne']['tablet']['left'] . ' ' : '1px 1px 1px 1px',
				'border-radius' => isset( $attr['contentBorderRadiusOne']['tablet']['top'] ) && isset( $attr['contentBorderRadiusOne']['tablet']['right'] ) && isset( $attr['contentBorderRadiusOne']['tablet']['bottom'] ) && isset( $attr['contentBorderRadiusOne']['tablet']['left'] ) ? $attr['contentBorderRadiusOne']['tablet']['top'] . ' ' . $attr['contentBorderRadiusOne']['tablet']['right'] . ' ' . $attr['contentBorderRadiusOne']['tablet']['bottom'] . ' ' . $attr['contentBorderRadiusOne']['tablet']['left'] . ' ' : '0px 0px 0px 0px',
			),
			' .affx-pros-cons-inner-wrapper.layout-type-2 .pros-icon-title-wrap .affiliatex-block-pros' => array(
				'border-width'  => isset( $attr['titleBorderWidthTwo']['tablet']['top'] ) && isset( $attr['titleBorderWidthTwo']['tablet']['right'] ) && isset( $attr['titleBorderWidthTwo']['tablet']['bottom'] ) && isset( $attr['titleBorderWidthTwo']['tablet']['left'] ) ? $attr['titleBorderWidthTwo']['tablet']['top'] . ' ' . $attr['titleBorderWidthTwo']['tablet']['right'] . ' ' . $attr['titleBorderWidthTwo']['tablet']['bottom'] . ' ' . $attr['titleBorderWidthTwo']['tablet']['left'] . ' ' : '1px 1px 1px 1px',
				'border-radius' => isset( $attr['titleBorderRadiusTwo']['tablet']['top'] ) && isset( $attr['titleBorderRadiusTwo']['tablet']['right'] ) && isset( $attr['titleBorderRadiusTwo']['tablet']['bottom'] ) && isset( $attr['titleBorderRadiusTwo']['tablet']['left'] ) ? $attr['titleBorderRadiusTwo']['tablet']['top'] . ' ' . $attr['titleBorderRadiusTwo']['tablet']['right'] . ' ' . $attr['titleBorderRadiusTwo']['tablet']['bottom'] . ' ' . $attr['titleBorderRadiusTwo']['tablet']['left'] . ' ' : '0px 0px 0px 0px',
			),
			' .affx-pros-cons-inner-wrapper.layout-type-2 .affiliatex-pros' => array(
				'border-width'  => isset( $attr['contentBorderWidthTwo']['tablet']['top'] ) && isset( $attr['contentBorderWidthTwo']['tablet']['right'] ) && isset( $attr['contentBorderWidthTwo']['tablet']['bottom'] ) && isset( $attr['contentBorderWidthTwo']['tablet']['left'] ) ? $attr['contentBorderWidthTwo']['tablet']['top'] . ' ' . $attr['contentBorderWidthTwo']['tablet']['right'] . ' ' . $attr['contentBorderWidthTwo']['tablet']['bottom'] . ' ' . $attr['contentBorderWidthTwo']['tablet']['left'] . ' ' : '1px 1px 1px 1px',
				'border-radius' => isset( $attr['contentBorderRadiusTwo']['tablet']['top'] ) && isset( $attr['contentBorderRadiusTwo']['tablet']['right'] ) && isset( $attr['contentBorderRadiusTwo']['tablet']['bottom'] ) && isset( $attr['contentBorderRadiusTwo']['tablet']['left'] ) ? $attr['contentBorderRadiusTwo']['tablet']['top'] . ' ' . $attr['contentBorderRadiusTwo']['tablet']['right'] . ' ' . $attr['contentBorderRadiusTwo']['tablet']['bottom'] . ' ' . $attr['contentBorderRadiusTwo']['tablet']['left'] . ' ' : '0px 0px 0px 0px',
			),
			' .affx-pros-cons-inner-wrapper.layout-type-4 .pros-icon-title-wrap .affiliatex-block-pros' => array(
				'border-width'  => isset( $attr['titleBorderWidthFour']['tablet']['top'] ) && isset( $attr['titleBorderWidthFour']['tablet']['right'] ) && isset( $attr['titleBorderWidthFour']['tablet']['bottom'] ) && isset( $attr['titleBorderWidthFour']['tablet']['left'] ) ? $attr['titleBorderWidthFour']['tablet']['top'] . ' ' . $attr['titleBorderWidthFour']['tablet']['right'] . ' ' . $attr['titleBorderWidthFour']['tablet']['bottom'] . ' ' . $attr['titleBorderWidthFour']['tablet']['left'] . ' ' : '1px 1px 1px 1px',
				'border-radius' => isset( $attr['titleBorderRadiusFour']['tablet']['top'] ) && isset( $attr['titleBorderRadiusFour']['tablet']['right'] ) && isset( $attr['titleBorderRadiusFour']['tablet']['bottom'] ) && isset( $attr['titleBorderRadiusFour']['tablet']['left'] ) ? $attr['titleBorderRadiusFour']['tablet']['top'] . ' ' . $attr['titleBorderRadiusFour']['tablet']['right'] . ' ' . $attr['titleBorderRadiusFour']['tablet']['bottom'] . ' ' . $attr['titleBorderRadiusFour']['tablet']['left'] . ' ' : '0px 0px 0px 0px',
			),
			' .affx-pros-cons-inner-wrapper.layout-type-1 .cons-icon-title-wrap .affiliatex-block-cons' => array(
				'border-width'  => isset( $attr['titleBorderWidthOne']['tablet']['top'] ) && isset( $attr['titleBorderWidthOne']['tablet']['right'] ) && isset( $attr['titleBorderWidthOne']['tablet']['bottom'] ) && isset( $attr['titleBorderWidthOne']['tablet']['left'] ) ? $attr['titleBorderWidthOne']['tablet']['top'] . ' ' . $attr['titleBorderWidthOne']['tablet']['right'] . ' ' . $attr['titleBorderWidthOne']['tablet']['bottom'] . ' ' . $attr['titleBorderWidthOne']['tablet']['left'] . ' ' : '1px 1px 1px 1px',
				'border-radius' => isset( $attr['titleBorderRadiusOne']['tablet']['top'] ) && isset( $attr['titleBorderRadiusOne']['tablet']['right'] ) && isset( $attr['titleBorderRadiusOne']['tablet']['bottom'] ) && isset( $attr['titleBorderRadiusOne']['tablet']['left'] ) ? $attr['titleBorderRadiusOne']['tablet']['top'] . ' ' . $attr['titleBorderRadiusOne']['tablet']['right'] . ' ' . $attr['titleBorderRadiusOne']['tablet']['bottom'] . ' ' . $attr['titleBorderRadiusOne']['tablet']['left'] . ' ' : '0px 0px 0px 0px',
			),
			' .affx-pros-cons-inner-wrapper.layout-type-1 .affiliatex-cons' => array(
				'border-width'  => isset( $attr['contentBorderWidthOne']['tablet']['top'] ) && isset( $attr['contentBorderWidthOne']['tablet']['right'] ) && isset( $attr['contentBorderWidthOne']['tablet']['bottom'] ) && isset( $attr['contentBorderWidthOne']['tablet']['left'] ) ? $attr['contentBorderWidthOne']['tablet']['top'] . ' ' . $attr['contentBorderWidthOne']['tablet']['right'] . ' ' . $attr['contentBorderWidthOne']['tablet']['bottom'] . ' ' . $attr['contentBorderWidthOne']['tablet']['left'] . ' ' : '1px 1px 1px 1px',
				'border-radius' => isset( $attr['contentBorderRadiusOne']['tablet']['top'] ) && isset( $attr['contentBorderRadiusOne']['tablet']['right'] ) && isset( $attr['contentBorderRadiusOne']['tablet']['bottom'] ) && isset( $attr['contentBorderRadiusOne']['tablet']['left'] ) ? $attr['contentBorderRadiusOne']['tablet']['top'] . ' ' . $attr['contentBorderRadiusOne']['tablet']['right'] . ' ' . $attr['contentBorderRadiusOne']['tablet']['bottom'] . ' ' . $attr['contentBorderRadiusOne']['tablet']['left'] . ' ' : '0px 0px 0px 0px',
			),
			' .affx-pros-cons-inner-wrapper.layout-type-2 .cons-icon-title-wrap .affiliatex-block-cons' => array(
				'border-width'  => isset( $attr['titleBorderWidthTwo']['tablet']['top'] ) && isset( $attr['titleBorderWidthTwo']['tablet']['right'] ) && isset( $attr['titleBorderWidthTwo']['tablet']['bottom'] ) && isset( $attr['titleBorderWidthTwo']['tablet']['left'] ) ? $attr['titleBorderWidthTwo']['tablet']['top'] . ' ' . $attr['titleBorderWidthTwo']['tablet']['right'] . ' ' . $attr['titleBorderWidthTwo']['tablet']['bottom'] . ' ' . $attr['titleBorderWidthTwo']['tablet']['left'] . ' ' : '1px 1px 1px 1px',
				'border-radius' => isset( $attr['titleBorderRadiusTwo']['tablet']['top'] ) && isset( $attr['titleBorderRadiusTwo']['tablet']['right'] ) && isset( $attr['titleBorderRadiusTwo']['tablet']['bottom'] ) && isset( $attr['titleBorderRadiusTwo']['tablet']['left'] ) ? $attr['titleBorderRadiusTwo']['tablet']['top'] . ' ' . $attr['titleBorderRadiusTwo']['tablet']['right'] . ' ' . $attr['titleBorderRadiusTwo']['tablet']['bottom'] . ' ' . $attr['titleBorderRadiusTwo']['tablet']['left'] . ' ' : '0px 0px 0px 0px',
			),
			' .affx-pros-cons-inner-wrapper.layout-type-2 .affiliatex-cons' => array(
				'border-width'  => isset( $attr['contentBorderWidthTwo']['tablet']['top'] ) && isset( $attr['contentBorderWidthTwo']['tablet']['right'] ) && isset( $attr['contentBorderWidthTwo']['tablet']['bottom'] ) && isset( $attr['contentBorderWidthTwo']['tablet']['left'] ) ? $attr['contentBorderWidthTwo']['tablet']['top'] . ' ' . $attr['contentBorderWidthTwo']['tablet']['right'] . ' ' . $attr['contentBorderWidthTwo']['tablet']['bottom'] . ' ' . $attr['contentBorderWidthTwo']['tablet']['left'] . ' ' : '1px 1px 1px 1px',
				'border-radius' => isset( $attr['contentBorderRadiusTwo']['tablet']['top'] ) && isset( $attr['contentBorderRadiusTwo']['tablet']['right'] ) && isset( $attr['contentBorderRadiusTwo']['tablet']['bottom'] ) && isset( $attr['contentBorderRadiusTwo']['tablet']['left'] ) ? $attr['contentBorderRadiusTwo']['tablet']['top'] . ' ' . $attr['contentBorderRadiusTwo']['tablet']['right'] . ' ' . $attr['contentBorderRadiusTwo']['tablet']['bottom'] . ' ' . $attr['contentBorderRadiusTwo']['tablet']['left'] . ' ' : '0px 0px 0px 0px',
			),
			' .affx-pros-cons-inner-wrapper.layout-type-4 .cons-icon-title-wrap .affiliatex-block-cons' => array(
				'border-width'  => isset( $attr['titleBorderWidthFour']['tablet']['top'] ) && isset( $attr['titleBorderWidthFour']['tablet']['right'] ) && isset( $attr['titleBorderWidthFour']['tablet']['bottom'] ) && isset( $attr['titleBorderWidthFour']['tablet']['left'] ) ? $attr['titleBorderWidthFour']['tablet']['top'] . ' ' . $attr['titleBorderWidthFour']['tablet']['right'] . ' ' . $attr['titleBorderWidthFour']['tablet']['bottom'] . ' ' . $attr['titleBorderWidthFour']['tablet']['left'] . ' ' : '1px 1px 1px 1px',
				'border-radius' => isset( $attr['titleBorderRadiusFour']['tablet']['top'] ) && isset( $attr['titleBorderRadiusFour']['tablet']['right'] ) && isset( $attr['titleBorderRadiusFour']['tablet']['bottom'] ) && isset( $attr['titleBorderRadiusFour']['tablet']['left'] ) ? $attr['titleBorderRadiusFour']['tablet']['top'] . ' ' . $attr['titleBorderRadiusFour']['tablet']['right'] . ' ' . $attr['titleBorderRadiusFour']['tablet']['bottom'] . ' ' . $attr['titleBorderRadiusFour']['tablet']['left'] . ' ' : '0px 0px 0px 0px',
			),
			' .affiliatex-block-cons .affiliatex-title' => array(
				'font-size'      => isset( $attr['titleTypography']['size']['tablet'] ) ? $attr['titleTypography']['size']['tablet'] : '20px',
				'line-height'    => isset( $attr['titleTypography']['line-height']['tablet'] ) ? $attr['titleTypography']['line-height']['tablet'] : '1.5',
				'letter-spacing' => isset( $attr['titleTypography']['letter-spacing']['tablet'] ) ? $attr['titleTypography']['letter-spacing']['tablet'] : '0em',
			),
			' .affiliatex-block-pros .affiliatex-title' => array(
				'font-size'      => isset( $attr['titleTypography']['size']['tablet'] ) ? $attr['titleTypography']['size']['tablet'] : '20px',
				'line-height'    => isset( $attr['titleTypography']['line-height']['tablet'] ) ? $attr['titleTypography']['line-height']['tablet'] : '1.5',
				'letter-spacing' => isset( $attr['titleTypography']['letter-spacing']['tablet'] ) ? $attr['titleTypography']['letter-spacing']['tablet'] : '0em',
			),
			' .affiliatex-content'                      => array(
				'margin-top'     => isset( $attr['contentMargin']['tablet']['top'] ) ? $attr['contentMargin']['tablet']['top'] : '0px',
				'margin-left'    => isset( $attr['contentMargin']['tablet']['left'] ) ? $attr['contentMargin']['tablet']['left'] : '0px',
				'margin-right'   => isset( $attr['contentMargin']['tablet']['right'] ) ? $attr['contentMargin']['tablet']['right'] : '0px',
				'margin-bottom'  => isset( $attr['contentMargin']['tablet']['bottom'] ) ? $attr['contentMargin']['tablet']['bottom'] : '0px',
				'padding-top'    => isset( $attr['contentPadding']['tablet']['top'] ) ? $attr['contentPadding']['tablet']['top'] : '10px',
				'padding-left'   => isset( $attr['contentPadding']['tablet']['left'] ) ? $attr['contentPadding']['tablet']['left'] : '20px',
				'padding-right'  => isset( $attr['contentPadding']['tablet']['right'] ) ? $attr['contentPadding']['tablet']['right'] : '20px',
				'padding-bottom' => isset( $attr['contentPadding']['tablet']['bottom'] ) ? $attr['contentPadding']['tablet']['bottom'] : '10px',
			),
			' .affiliatex-list'                         => array(
				'margin-top'     => isset( $attr['contentMargin']['tablet']['top'] ) ? $attr['contentMargin']['tablet']['top'] : '0px',
				'margin-left'    => isset( $attr['contentMargin']['tablet']['left'] ) ? $attr['contentMargin']['tablet']['left'] : '0px',
				'margin-right'   => isset( $attr['contentMargin']['tablet']['right'] ) ? $attr['contentMargin']['tablet']['right'] : '0px',
				'margin-bottom'  => isset( $attr['contentMargin']['tablet']['bottom'] ) ? $attr['contentMargin']['tablet']['bottom'] : '0px',
				'padding-top'    => isset( $attr['contentPadding']['tablet']['top'] ) ? $attr['contentPadding']['tablet']['top'] : '10px',
				'padding-left'   => isset( $attr['contentPadding']['tablet']['left'] ) ? $attr['contentPadding']['tablet']['left'] : '20px',
				'padding-right'  => isset( $attr['contentPadding']['tablet']['right'] ) ? $attr['contentPadding']['tablet']['right'] : '20px',
				'padding-bottom' => isset( $attr['contentPadding']['tablet']['bottom'] ) ? $attr['contentPadding']['tablet']['bottom'] : '10px',
			),
			' .affiliatex-cons p'                       => array(
				'font-size'      => isset( $attr['listTypography']['size']['tablet'] ) ? $attr['listTypography']['size']['tablet'] : '18px',
				'line-height'    => isset( $attr['listTypography']['line-height']['tablet'] ) ? $attr['listTypography']['line-height']['tablet'] : '1.5',
				'letter-spacing' => isset( $attr['listTypography']['letter-spacing']['tablet'] ) ? $attr['listTypography']['letter-spacing']['tablet'] : '0em',
			),
			' .affiliatex-cons li'                      => array(
				'font-size'      => isset( $attr['listTypography']['size']['tablet'] ) ? $attr['listTypography']['size']['tablet'] : '18px',
				'line-height'    => isset( $attr['listTypography']['line-height']['tablet'] ) ? $attr['listTypography']['line-height']['tablet'] : '1.5',
				'letter-spacing' => isset( $attr['listTypography']['letter-spacing']['tablet'] ) ? $attr['listTypography']['letter-spacing']['tablet'] : '0em',
			),
			' .affiliatex-pros p'                       => array(
				'font-size'      => isset( $attr['listTypography']['size']['tablet'] ) ? $attr['listTypography']['size']['tablet'] : '18px',
				'line-height'    => isset( $attr['listTypography']['line-height']['tablet'] ) ? $attr['listTypography']['line-height']['tablet'] : '1.5',
				'letter-spacing' => isset( $attr['listTypography']['letter-spacing']['tablet'] ) ? $attr['listTypography']['letter-spacing']['tablet'] : '0em',
			),
			' .affiliatex-pros li'                      => array(
				'font-size'      => isset( $attr['listTypography']['size']['tablet'] ) ? $attr['listTypography']['size']['tablet'] : '18px',
				'line-height'    => isset( $attr['listTypography']['line-height']['tablet'] ) ? $attr['listTypography']['line-height']['tablet'] : '1.5',
				'letter-spacing' => isset( $attr['listTypography']['letter-spacing']['tablet'] ) ? $attr['listTypography']['letter-spacing']['tablet'] : '0em',
			),
			' .affx-pros-cons-inner-wrapper.layout-type-3  .pros-icon-title-wrap' => array(
				'border-width'  => isset( $attr['contentBorderWidthThree']['tablet']['top'] ) && isset( $attr['contentBorderWidthThree']['tablet']['right'] ) && isset( $attr['contentBorderWidthThree']['tablet']['bottom'] ) && isset( $attr['contentBorderWidthThree']['tablet']['left'] ) ? $attr['contentBorderWidthThree']['tablet']['top'] . ' ' . $attr['contentBorderWidthThree']['tablet']['right'] . ' ' . $attr['contentBorderWidthThree']['tablet']['bottom'] . ' ' . $attr['contentBorderWidthThree']['tablet']['left'] . ' ' : '4px 0 4px 4px',
				'border-radius' => isset( $attr['contentBorderRadiusThree']['tablet']['top'] ) && isset( $attr['contentBorderRadiusThree']['tablet']['right'] ) && isset( $attr['contentBorderRadiusThree']['tablet']['bottom'] ) && isset( $attr['contentBorderRadiusThree']['tablet']['left'] ) ? $attr['contentBorderRadiusThree']['tablet']['top'] . ' ' . $attr['contentBorderRadiusThree']['tablet']['right'] . ' ' . $attr['contentBorderRadiusThree']['tablet']['bottom'] . ' ' . $attr['contentBorderRadiusThree']['tablet']['left'] . ' ' : '0px 0px 0px 0px',
			),
			' .affx-pros-cons-inner-wrapper.layout-type-3  .cons-icon-title-wrap' => array(
				'border-width'  => isset( $attr['contentBorderWidthThree']['tablet']['top'] ) && isset( $attr['contentBorderWidthThree']['tablet']['right'] ) && isset( $attr['contentBorderWidthThree']['tablet']['bottom'] ) && isset( $attr['contentBorderWidthThree']['tablet']['left'] ) ? $attr['contentBorderWidthThree']['tablet']['top'] . ' ' . $attr['contentBorderWidthThree']['tablet']['right'] . ' ' . $attr['contentBorderWidthThree']['tablet']['bottom'] . ' ' . $attr['contentBorderWidthThree']['tablet']['left'] . ' ' : '4px 0 4px 4px',
				'border-radius' => isset( $attr['contentBorderRadiusThree']['tablet']['top'] ) && isset( $attr['contentBorderRadiusThree']['tablet']['right'] ) && isset( $attr['contentBorderRadiusThree']['tablet']['bottom'] ) && isset( $attr['contentBorderRadiusThree']['tablet']['left'] ) ? $attr['contentBorderRadiusThree']['tablet']['top'] . ' ' . $attr['contentBorderRadiusThree']['tablet']['right'] . ' ' . $attr['contentBorderRadiusThree']['tablet']['bottom'] . ' ' . $attr['contentBorderRadiusThree']['tablet']['left'] . ' ' : '0px 0px 0px 0px',
			),
			' .affx-pros-cons-inner-wrapper.layout-type-3  .affiliatex-pros' => array(
				'border-width'  => isset( $attr['contentBorderWidthThree']['tablet']['top'] ) && isset( $attr['contentBorderWidthThree']['tablet']['right'] ) && isset( $attr['contentBorderWidthThree']['tablet']['bottom'] ) && isset( $attr['contentBorderWidthThree']['tablet']['left'] ) ? $attr['contentBorderWidthThree']['tablet']['top'] . ' ' . $attr['contentBorderWidthThree']['tablet']['right'] . ' ' . $attr['contentBorderWidthThree']['tablet']['bottom'] . ' ' . $attr['contentBorderWidthThree']['tablet']['left'] . ' ' : '0 4px 4px 4px',
				'border-radius' => isset( $attr['contentBorderRadiusThree']['tablet']['top'] ) && isset( $attr['contentBorderRadiusThree']['tablet']['right'] ) && isset( $attr['contentBorderRadiusThree']['tablet']['bottom'] ) && isset( $attr['contentBorderRadiusThree']['tablet']['left'] ) ? $attr['contentBorderRadiusThree']['tablet']['top'] . ' ' . $attr['contentBorderRadiusThree']['tablet']['right'] . ' ' . $attr['contentBorderRadiusThree']['tablet']['bottom'] . ' ' . $attr['contentBorderRadiusThree']['tablet']['left'] . ' ' : '0px 0px 0px 0px',
			),
			' .affx-pros-cons-inner-wrapper.layout-type-3  .affiliatex-cons' => array(
				'border-width'  => isset( $attr['contentBorderWidthThree']['tablet']['top'] ) && isset( $attr['contentBorderWidthThree']['tablet']['right'] ) && isset( $attr['contentBorderWidthThree']['tablet']['bottom'] ) && isset( $attr['contentBorderWidthThree']['tablet']['left'] ) ? $attr['contentBorderWidthThree']['tablet']['top'] . ' ' . $attr['contentBorderWidthThree']['tablet']['right'] . ' ' . $attr['contentBorderWidthThree']['tablet']['bottom'] . ' ' . $attr['contentBorderWidthThree']['tablet']['left'] . ' ' : '0 4px 4px 4px',
				'border-radius' => isset( $attr['contentBorderRadiusThree']['tablet']['top'] ) && isset( $attr['contentBorderRadiusThree']['tablet']['right'] ) && isset( $attr['contentBorderRadiusThree']['tablet']['bottom'] ) && isset( $attr['contentBorderRadiusThree']['tablet']['left'] ) ? $attr['contentBorderRadiusThree']['tablet']['top'] . ' ' . $attr['contentBorderRadiusThree']['tablet']['right'] . ' ' . $attr['contentBorderRadiusThree']['tablet']['bottom'] . ' ' . $attr['contentBorderRadiusThree']['tablet']['left'] . ' ' : '0px 0px 0px 0px',
			),
			' .affx-pros-cons-inner-wrapper.layout-type-3 .affiliatex-block-pros .affiliatex-title:before' => array(
				'border-width'  => isset( $attr['titleBorderWidthThree']['tablet']['top'] ) && isset( $attr['titleBorderWidthThree']['tablet']['right'] ) && isset( $attr['titleBorderWidthThree']['tablet']['bottom'] ) && isset( $attr['titleBorderWidthThree']['tablet']['left'] ) ? $attr['titleBorderWidthThree']['tablet']['top'] . ' ' . $attr['titleBorderWidthThree']['tablet']['right'] . ' ' . $attr['titleBorderWidthThree']['tablet']['bottom'] . ' ' . $attr['titleBorderWidthThree']['tablet']['left'] . ' ' : '4px 4px 4px 4px',
				'border-radius' => isset( $attr['titleBorderRadiusThree']['tablet']['top'] ) && isset( $attr['titleBorderRadiusThree']['tablet']['right'] ) && isset( $attr['titleBorderRadiusThree']['tablet']['bottom'] ) && isset( $attr['titleBorderRadiusThree']['tablet']['left'] ) ? $attr['titleBorderRadiusThree']['tablet']['top'] . ' ' . $attr['titleBorderRadiusThree']['tablet']['right'] . ' ' . $attr['titleBorderRadiusThree']['tablet']['bottom'] . ' ' . $attr['titleBorderRadiusThree']['tablet']['left'] . ' ' : '50px 50px 50px 50px',
			),
			' .affx-pros-cons-inner-wrapper.layout-type-3 .affiliatex-block-cons .affiliatex-title:before' => array(
				'border-width'  => isset( $attr['titleBorderWidthThree']['tablet']['top'] ) && isset( $attr['titleBorderWidthThree']['tablet']['right'] ) && isset( $attr['titleBorderWidthThree']['tablet']['bottom'] ) && isset( $attr['titleBorderWidthThree']['tablet']['left'] ) ? $attr['titleBorderWidthThree']['tablet']['top'] . ' ' . $attr['titleBorderWidthThree']['tablet']['right'] . ' ' . $attr['titleBorderWidthThree']['tablet']['bottom'] . ' ' . $attr['titleBorderWidthThree']['tablet']['left'] . ' ' : '4px 4px 4px 4px',
				'border-radius' => isset( $attr['titleBorderRadiusThree']['tablet']['top'] ) && isset( $attr['titleBorderRadiusThree']['tablet']['right'] ) && isset( $attr['titleBorderRadiusThree']['tablet']['bottom'] ) && isset( $attr['titleBorderRadiusThree']['tablet']['left'] ) ? $attr['titleBorderRadiusThree']['tablet']['top'] . ' ' . $attr['titleBorderRadiusThree']['tablet']['right'] . ' ' . $attr['titleBorderRadiusThree']['tablet']['bottom'] . ' ' . $attr['titleBorderRadiusThree']['tablet']['left'] . ' ' : '50px 50px 50px 50px',
			),
			' .affx-pros-cons-inner-wrapper.layout-type-4  .affiliatex-pros ul li' => array(
				'border-width'  => isset( $attr['contentBorderWidthFour']['tablet']['top'] ) && isset( $attr['contentBorderWidthFour']['tablet']['right'] ) && isset( $attr['contentBorderWidthFour']['tablet']['bottom'] ) && isset( $attr['contentBorderWidthFour']['tablet']['left'] ) ? $attr['contentBorderWidthFour']['tablet']['top'] . ' ' . $attr['contentBorderWidthFour']['tablet']['right'] . ' ' . $attr['contentBorderWidthFour']['tablet']['bottom'] . ' ' . $attr['contentBorderWidthFour']['tablet']['left'] . ' ' : '1px 1px 1px 1px',
				'border-radius' => isset( $attr['contentBorderRadiusFour']['tablet']['top'] ) && isset( $attr['contentBorderRadiusFour']['tablet']['right'] ) && isset( $attr['contentBorderRadiusFour']['tablet']['bottom'] ) && isset( $attr['contentBorderRadiusFour']['tablet']['left'] ) ? $attr['contentBorderRadiusFour']['tablet']['top'] . ' ' . $attr['contentBorderRadiusFour']['tablet']['right'] . ' ' . $attr['contentBorderRadiusFour']['tablet']['bottom'] . ' ' . $attr['contentBorderRadiusFour']['tablet']['left'] . ' ' : '0px 0px 0px 0px',
			),
			' .affx-pros-cons-inner-wrapper.layout-type-4  .affiliatex-cons ul li' => array(
				'border-width'  => isset( $attr['contentBorderWidthFour']['tablet']['top'] ) && isset( $attr['contentBorderWidthFour']['tablet']['right'] ) && isset( $attr['contentBorderWidthFour']['tablet']['bottom'] ) && isset( $attr['contentBorderWidthFour']['tablet']['left'] ) ? $attr['contentBorderWidthFour']['tablet']['top'] . ' ' . $attr['contentBorderWidthFour']['tablet']['right'] . ' ' . $attr['contentBorderWidthFour']['tablet']['bottom'] . ' ' . $attr['contentBorderWidthFour']['tablet']['left'] . ' ' : '1px 1px 1px 1px',
				'border-radius' => isset( $attr['contentBorderRadiusFour']['tablet']['top'] ) && isset( $attr['contentBorderRadiusFour']['tablet']['right'] ) && isset( $attr['contentBorderRadiusFour']['tablet']['bottom'] ) && isset( $attr['contentBorderRadiusFour']['tablet']['left'] ) ? $attr['contentBorderRadiusFour']['tablet']['top'] . ' ' . $attr['contentBorderRadiusFour']['tablet']['right'] . ' ' . $attr['contentBorderRadiusFour']['tablet']['bottom'] . ' ' . $attr['contentBorderRadiusFour']['tablet']['left'] . ' ' : '0px 0px 0px 0px',
			),
			' .affx-pros-cons-inner-wrapper.layout-type-4  .affiliatex-content li' => array(
				'padding-top'    => isset( $attr['contentPadding']['tablet']['top'] ) ? $attr['contentPadding']['tablet']['top'] : '10px',
				'padding-left'   => isset( $attr['contentPadding']['tablet']['left'] ) ? $attr['contentPadding']['tablet']['left'] : '20px',
				'padding-right'  => isset( $attr['contentPadding']['tablet']['right'] ) ? $attr['contentPadding']['tablet']['right'] : '20px',
				'padding-bottom' => isset( $attr['contentPadding']['tablet']['bottom'] ) ? $attr['contentPadding']['tablet']['bottom'] : '10px',
			),
			' .affx-pros-cons-inner-wrapper.layout-type-4  .affiliatex-list li' => array(
				'padding-top'    => isset( $attr['contentPadding']['tablet']['top'] ) ? $attr['contentPadding']['tablet']['top'] : '10px',
				'padding-left'   => isset( $attr['contentPadding']['tablet']['left'] ) ? $attr['contentPadding']['tablet']['left'] : '20px',
				'padding-right'  => isset( $attr['contentPadding']['tablet']['right'] ) ? $attr['contentPadding']['tablet']['right'] : '20px',
				'padding-bottom' => isset( $attr['contentPadding']['tablet']['bottom'] ) ? $attr['contentPadding']['tablet']['bottom'] : '10px',
			),
			' .affx-pros-cons-inner-wrapper.layout-type-4  .affiliatex-pros p' => array(
				'border-width'  => isset( $attr['contentBorderWidthFour']['tablet']['top'] ) && isset( $attr['contentBorderWidthFour']['tablet']['right'] ) && isset( $attr['contentBorderWidthFour']['tablet']['bottom'] ) && isset( $attr['contentBorderWidthFour']['tablet']['left'] ) ? $attr['contentBorderWidthFour']['tablet']['top'] . ' ' . $attr['contentBorderWidthFour']['tablet']['right'] . ' ' . $attr['contentBorderWidthFour']['tablet']['bottom'] . ' ' . $attr['contentBorderWidthFour']['tablet']['left'] . ' ' : '1px 1px 1px 1px',
				'border-radius' => isset( $attr['contentBorderRadiusFour']['tablet']['top'] ) && isset( $attr['contentBorderRadiusFour']['tablet']['right'] ) && isset( $attr['contentBorderRadiusFour']['tablet']['bottom'] ) && isset( $attr['contentBorderRadiusFour']['tablet']['left'] ) ? $attr['contentBorderRadiusFour']['tablet']['top'] . ' ' . $attr['contentBorderRadiusFour']['tablet']['right'] . ' ' . $attr['contentBorderRadiusFour']['tablet']['bottom'] . ' ' . $attr['contentBorderRadiusFour']['tablet']['left'] . ' ' : '0px 0px 0px 0px',
			),
			' .affx-pros-cons-inner-wrapper.layout-type-4  .affiliatex-cons p' => array(
				'border-width'  => isset( $attr['contentBorderWidthFour']['tablet']['top'] ) && isset( $attr['contentBorderWidthFour']['tablet']['right'] ) && isset( $attr['contentBorderWidthFour']['tablet']['bottom'] ) && isset( $attr['contentBorderWidthFour']['tablet']['left'] ) ? $attr['contentBorderWidthFour']['tablet']['top'] . ' ' . $attr['contentBorderWidthFour']['tablet']['right'] . ' ' . $attr['contentBorderWidthFour']['tablet']['bottom'] . ' ' . $attr['contentBorderWidthFour']['tablet']['left'] . ' ' : '1px 1px 1px 1px',
				'border-radius' => isset( $attr['contentBorderRadiusFour']['tablet']['top'] ) && isset( $attr['contentBorderRadiusFour']['tablet']['right'] ) && isset( $attr['contentBorderRadiusFour']['tablet']['bottom'] ) && isset( $attr['contentBorderRadiusFour']['tablet']['left'] ) ? $attr['contentBorderRadiusFour']['tablet']['top'] . ' ' . $attr['contentBorderRadiusFour']['tablet']['right'] . ' ' . $attr['contentBorderRadiusFour']['tablet']['bottom'] . ' ' . $attr['contentBorderRadiusFour']['tablet']['left'] . ' ' : '0px 0px 0px 0px',
			),
		);
		return $tablet_selector;
	}

}
