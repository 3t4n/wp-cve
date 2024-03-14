<?php
/**
 * 'Single Product', Block Styles
 *
 * @package AffiliateX
 */

class AffiliateX_Single_Product_Styles {

	public static function block_fonts( $attr ) {
		return array(
			'productTitleTypography'    => isset( $attr['productTitleTypography'] ) ? $attr['productTitleTypography'] : array(),
			'productSubtitleTypography' => isset( $attr['productSubtitleTypography'] ) ? $attr['productSubtitleTypography'] : array(),
			'productContentTypography'  => isset( $attr['productContentTypography'] ) ? $attr['productContentTypography'] : array(),
			'pricingTypography'         => isset( $attr['pricingTypography'] ) ? $attr['pricingTypography'] : array(),
			'ribbonContentTypography'   => isset( $attr['ribbonContentTypography'] ) ? $attr['ribbonContentTypography'] : array(),
			'numRatingTypography'       => isset( $attr['numRatingTypography'] ) ? $attr['numRatingTypography'] : array(),
		);
	}

	public static function block_css( $attr, $id ) {
		$selectors = self::get_selectors( $attr );

		$m_selectors = self::get_mobileselectors( $attr );

		$t_selectors = self::get_tabletselectors( $attr );

		$desktop = AffiliateX_Helpers::generate_css( $selectors, '#affiliatex-single-product-style-' . $id );

		$tablet = AffiliateX_Helpers::generate_css( $t_selectors, '#affiliatex-single-product-style-' . $id );

		$mobile = AffiliateX_Helpers::generate_css( $m_selectors, '#affiliatex-single-product-style-' . $id );

		$generated_css = array(
			'desktop' => $desktop,
			'tablet'  => $tablet,
			'mobile'  => $mobile,
		);

		return $generated_css;
	}

	public static function get_selectors( $attr ) {

		$customization_data = affx_get_customization_settings();
		$global_font_family = isset( $customization_data['typography']['family'] ) ? $customization_data['typography']['family'] : 'Default';
		$global_font_color  = isset( $customization_data['fontColor'] ) ? $customization_data['fontColor'] : '#292929';
		$SPBgGradient       = isset( $attr['productBgGradient']['gradient'] ) ? $attr['productBgGradient']['gradient'] : '';
		$bgColor            = isset( $attr['productBGColor'] ) ? $attr['productBGColor'] : '#fff';
		$ribbonBgColor      = isset( $attr['ribbonBGColor'] ) ? $attr['ribbonBGColor'] : '#ff0000';
		$ribbonGradient     = isset( $attr['ribbonBgGradient']['gradient'] ) ? $attr['ribbonBgGradient']['gradient'] : '';
		$variation          = isset( $attr['productTitleTypography']['variation'] ) ? $attr['productTitleTypography']['variation'] : 'n5';
		$sub_variation      = isset( $attr['productSubtitleTypography']['variation'] ) ? $attr['productSubtitleTypography']['variation'] : 'n5';
		$con_variation      = isset( $attr['productContentTypography']['variation'] ) ? $attr['productContentTypography']['variation'] : 'n4';
		$price_variation    = isset( $attr['pricingTypography']['variation'] ) ? $attr['pricingTypography']['variation'] : 'n4';
		$ribbon_variation   = isset( $attr['ribbonContentTypography']['variation'] ) ? $attr['ribbonContentTypography']['variation'] : 'n4';
		$rating_variation   = isset( $attr['numRatingTypography']['variation'] ) ? $attr['numRatingTypography']['variation'] : 'n4';
		$box_shadow         = array(
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

		$selectors = array(
			' '                                        => array(
				'margin-top'    => '0',
				'margin-bottom' => '0',
			),
			' .affx-single-product-wrapper'            => array(
				'border-width'  => isset( $attr['productBorderWidth']['desktop']['top'] ) && isset( $attr['productBorderWidth']['desktop']['right'] ) && isset( $attr['productBorderWidth']['desktop']['bottom'] ) && isset( $attr['productBorderWidth']['desktop']['left'] ) ? $attr['productBorderWidth']['desktop']['top'] . ' ' . $attr['productBorderWidth']['desktop']['right'] . ' ' . $attr['productBorderWidth']['desktop']['bottom'] . ' ' . $attr['productBorderWidth']['desktop']['left'] . ' ' : '1px 1px 1px 1px',
				'border-radius' => isset( $attr['productBorderRadius']['desktop']['top'] ) && isset( $attr['productBorderRadius']['desktop']['right'] ) && isset( $attr['productBorderRadius']['desktop']['bottom'] ) && isset( $attr['productBorderRadius']['desktop']['left'] ) ? $attr['productBorderRadius']['desktop']['top'] . ' ' . $attr['productBorderRadius']['desktop']['right'] . ' ' . $attr['productBorderRadius']['desktop']['bottom'] . ' ' . $attr['productBorderRadius']['desktop']['left'] . ' ' : '0px 0px 0px 0px',
				'border-style'  => isset( $attr['productBorder']['style'] ) ? $attr['productBorder']['style'] : 'solid',
				'border-color'  => isset( $attr['productBorder']['color']['color'] ) ? $attr['productBorder']['color']['color'] : '#E6ECF7',
				'background'    => isset( $attr['productBgColorType'] ) && $attr['productBgColorType'] === 'gradient' ? $SPBgGradient : $bgColor,
				'margin-top'    => isset( $attr['contentMargin']['desktop']['top'] ) ? $attr['contentMargin']['desktop']['top'] : '0px',
				'margin-left'   => isset( $attr['contentMargin']['desktop']['left'] ) ? $attr['contentMargin']['desktop']['left'] : '0px',
				'margin-right'  => isset( $attr['contentMargin']['desktop']['right'] ) ? $attr['contentMargin']['desktop']['right'] : '0px',
				'margin-bottom' => isset( $attr['contentMargin']['desktop']['bottom'] ) ? $attr['contentMargin']['desktop']['bottom'] : '30px',
				'box-shadow'    => isset( $attr['productShadow'] ) && $attr['productShadow']['enable'] ? AffiliateX_Helpers::get_css_boxshadow( $attr['productShadow'] ) : array(),

			),
			' .affx-single-product-title'              => array(
				'font-family'     => isset( $attr['productTitleTypography']['family'] ) ? $attr['productTitleTypography']['family'] : $global_font_family,
				'font-weight'     => AffiliateX_Helpers::get_fontweight_variation( $variation ),
				'font-style'      => AffiliateX_Helpers::get_font_style( $variation ),
				'font-size'       => isset( $attr['productTitleTypography']['size']['desktop'] ) ? $attr['productTitleTypography']['size']['desktop'] : '24px',
				'line-height'     => isset( $attr['productTitleTypography']['line-height']['desktop'] ) ? $attr['productTitleTypography']['line-height']['desktop'] : '1.333',
				'text-transform'  => isset( $attr['productTitleTypography']['text-transform'] ) ? $attr['productTitleTypography']['text-transform'] : 'none',
				'text-decoration' => isset( $attr['productTitleTypography']['text-decoration'] ) ? $attr['productTitleTypography']['text-decoration'] : 'none',
				'letter-spacing'  => isset( $attr['productTitleTypography']['letter-spacing']['desktop'] ) ? $attr['productTitleTypography']['letter-spacing']['desktop'] : '0em',
				'text-align'      => isset( $attr['productTitleAlign'] ) ? $attr['productTitleAlign'] : 'left',
				'color'           => isset( $attr['productTitleColor'] ) ? $attr['productTitleColor'] : '#060c0e',

			),
			' .affx-single-product-subtitle'           => array(
				'font-family'     => isset( $attr['productSubtitleTypography']['family'] ) ? $attr['productSubtitleTypography']['family'] : $global_font_family,
				'font-weight'     => AffiliateX_Helpers::get_fontweight_variation( $sub_variation ),
				'font-style'      => AffiliateX_Helpers::get_font_style( $sub_variation ),
				'font-size'       => isset( $attr['productSubtitleTypography']['size']['desktop'] ) ? $attr['productSubtitleTypography']['size']['desktop'] : '18px',
				'line-height'     => isset( $attr['productSubtitleTypography']['line-height']['desktop'] ) ? $attr['productSubtitleTypography']['line-height']['desktop'] : '1.5',
				'text-transform'  => isset( $attr['productSubtitleTypography']['text-transform'] ) ? $attr['productSubtitleTypography']['text-transform'] : 'none',
				'text-decoration' => isset( $attr['productSubtitleTypography']['text-decoration'] ) ? $attr['productSubtitleTypography']['text-decoration'] : 'none',
				'letter-spacing'  => isset( $attr['productSubtitleTypography']['letter-spacing']['desktop'] ) ? $attr['productSubtitleTypography']['letter-spacing']['desktop'] : '0em',
				'text-align'      => isset( $attr['productSubtitleAlign'] ) ? $attr['productSubtitleAlign'] : 'left',
				'color'           => isset( $attr['productSubtitleColor'] ) ? $attr['productSubtitleColor'] : '#A3ACBF',
			),
			' .affx-single-product-content'            => array(
				'font-family'     => isset( $attr['productContentTypography']['family'] ) ? $attr['productContentTypography']['family'] : $global_font_family,
				'font-weight'     => AffiliateX_Helpers::get_fontweight_variation( $con_variation ),
				'font-style'      => AffiliateX_Helpers::get_font_style( $con_variation ),
				'font-size'       => isset( $attr['productContentTypography']['size']['desktop'] ) ? $attr['productContentTypography']['size']['desktop'] : '18px',
				'line-height'     => isset( $attr['productContentTypography']['line-height']['desktop'] ) ? $attr['productContentTypography']['line-height']['desktop'] : '1.65',
				'text-transform'  => isset( $attr['productContentTypography']['text-transform'] ) ? $attr['productContentTypography']['text-transform'] : 'none',
				'text-decoration' => isset( $attr['productContentTypography']['text-decoration'] ) ? $attr['productContentTypography']['text-decoration'] : 'none',
				'letter-spacing'  => isset( $attr['productContentTypography']['letter-spacing']['desktop'] ) ? $attr['productContentTypography']['letter-spacing']['desktop'] : '0em',
				'text-align'      => isset( $attr['productContentAlign'] ) ? $attr['productContentAlign'] : 'left',
				'color'           => isset( $attr['productContentColor'] ) ? $attr['productContentColor'] : $global_font_color,
			),
			' .affx-single-product-content p'          => array(
				'font-family'     => isset( $attr['productContentTypography']['family'] ) ? $attr['productContentTypography']['family'] : $global_font_family,
				'font-weight'     => AffiliateX_Helpers::get_fontweight_variation( $con_variation ),
				'font-style'      => AffiliateX_Helpers::get_font_style( $con_variation ),
				'font-size'       => isset( $attr['productContentTypography']['size']['desktop'] ) ? $attr['productContentTypography']['size']['desktop'] : '18px',
				'line-height'     => isset( $attr['productContentTypography']['line-height']['desktop'] ) ? $attr['productContentTypography']['line-height']['desktop'] : '1.65',
				'text-transform'  => isset( $attr['productContentTypography']['text-transform'] ) ? $attr['productContentTypography']['text-transform'] : 'none',
				'text-decoration' => isset( $attr['productContentTypography']['text-decoration'] ) ? $attr['productContentTypography']['text-decoration'] : 'none',
				'letter-spacing'  => isset( $attr['productContentTypography']['letter-spacing']['desktop'] ) ? $attr['productContentTypography']['letter-spacing']['desktop'] : '0em',
				'text-align'      => isset( $attr['productContentAlign'] ) ? $attr['productContentAlign'] : 'left',
				'color'           => isset( $attr['productContentColor'] ) ? $attr['productContentColor'] : $global_font_color,
			),
			' .affx-single-product-content ul li'      => array(
				'font-family'     => isset( $attr['productContentTypography']['family'] ) ? $attr['productContentTypography']['family'] : $global_font_family,
				'font-weight'     => AffiliateX_Helpers::get_fontweight_variation( $con_variation ),
				'font-style'      => AffiliateX_Helpers::get_font_style( $con_variation ),
				'font-size'       => isset( $attr['productContentTypography']['size']['desktop'] ) ? $attr['productContentTypography']['size']['desktop'] : '18px',
				'line-height'     => isset( $attr['productContentTypography']['line-height']['desktop'] ) ? $attr['productContentTypography']['line-height']['desktop'] : '1.65',
				'text-transform'  => isset( $attr['productContentTypography']['text-transform'] ) ? $attr['productContentTypography']['text-transform'] : 'none',
				'text-decoration' => isset( $attr['productContentTypography']['text-decoration'] ) ? $attr['productContentTypography']['text-decoration'] : 'none',
				'letter-spacing'  => isset( $attr['productContentTypography']['letter-spacing']['desktop'] ) ? $attr['productContentTypography']['letter-spacing']['desktop'] : '0em',
				'text-align'      => isset( $attr['productContentAlign'] ) ? $attr['productContentAlign'] : 'left',
				'color'           => isset( $attr['productContentColor'] ) ? $attr['productContentColor'] : $global_font_color,
			),
			' .affx-sp-marked-price'                   => array(
				'font-family'     => isset( $attr['pricingTypography']['family'] ) ? $attr['pricingTypography']['family'] : $global_font_family,
				'font-weight'     => AffiliateX_Helpers::get_fontweight_variation( $price_variation ),
				'font-style'      => AffiliateX_Helpers::get_font_style( $price_variation ),
				'font-size'       => isset( $attr['pricingTypography']['size']['desktop'] ) ? $attr['pricingTypography']['size']['desktop'] : '22px',
				'line-height'     => isset( $attr['pricingTypography']['line-height']['desktop'] ) ? $attr['pricingTypography']['line-height']['desktop'] : '1.65',
				'text-transform'  => isset( $attr['pricingTypography']['text-transform'] ) ? $attr['pricingTypography']['text-transform'] : 'none',
				'text-decoration' => isset( $attr['pricingTypography']['text-decoration'] ) ? $attr['pricingTypography']['text-decoration'] : 'none',
				'letter-spacing'  => isset( $attr['pricingTypography']['letter-spacing']['desktop'] ) ? $attr['pricingTypography']['letter-spacing']['desktop'] : '0em',
			),
			' .affx-sp-sale-price'                     => array(
				'font-family'     => isset( $attr['pricingTypography']['family'] ) ? $attr['pricingTypography']['family'] : $global_font_family,
				'font-weight'     => AffiliateX_Helpers::get_fontweight_variation( $price_variation ),
				'font-style'      => AffiliateX_Helpers::get_font_style( $price_variation ),
				'font-size'       => isset( $attr['pricingTypography']['size']['desktop'] ) ? $attr['pricingTypography']['size']['desktop'] : '22px',
				'line-height'     => isset( $attr['pricingTypography']['line-height']['desktop'] ) ? $attr['pricingTypography']['line-height']['desktop'] : '1.65',
				'text-transform'  => isset( $attr['pricingTypography']['text-transform'] ) ? $attr['pricingTypography']['text-transform'] : 'none',
				'text-decoration' => isset( $attr['pricingTypography']['text-decoration'] ) ? $attr['pricingTypography']['text-decoration'] : 'none',
				'letter-spacing'  => isset( $attr['pricingTypography']['letter-spacing']['desktop'] ) ? $attr['pricingTypography']['letter-spacing']['desktop'] : '0em',
			),
			' .affx-single-product-wrapper.product-layout-1 .affx-sp-img-wrapper' => array(
				'flex' => isset( $attr['productImageWidth'] ) && $attr['productImageWidth'] === 'custom' && isset( $attr['productImageCustomWidth'] ) ? '0 0 ' . $attr['productImageCustomWidth'] . '%' : '',
			),
			' .affx-single-product-wrapper.product-layout-3 .affx-sp-img-wrapper' => array(
				'flex' => isset( $attr['productImageWidth'] ) && $attr['productImageWidth'] === 'custom' && isset( $attr['productImageCustomWidth'] ) ? '0 0 ' . $attr['productImageCustomWidth'] . '%' : '',
			),
			' .affx-single-product-wrapper.product-layout-3 .affx-sp-content-wrapper' => array(
				'padding' => '0 0 0 0',
			),
			' .affx-sp-content-wrapper'                => array(
				'padding-top'    => isset( $attr['contentSpacing']['desktop']['top'] ) ? $attr['contentSpacing']['desktop']['top'] : '30px',
				'padding-left'   => isset( $attr['contentSpacing']['desktop']['left'] ) ? $attr['contentSpacing']['desktop']['left'] : '25px',
				'padding-right'  => isset( $attr['contentSpacing']['desktop']['right'] ) ? $attr['contentSpacing']['desktop']['right'] : '25px',
				'padding-bottom' => isset( $attr['contentSpacing']['desktop']['bottom'] ) ? $attr['contentSpacing']['desktop']['bottom'] : '30px',
			),
			' .affx-sp-price'                          => array(
				'color' => isset( $attr['pricingColor'] ) ? $attr['pricingColor'] : '#262B33',
			),
			' .affx-sp-price .affx-sp-sale-price'      => array(
				'color' => isset( $attr['pricingHoverColor'] ) ? $attr['pricingHoverColor'] : '#A3ACBF',
			),
			' .affx-sp-rating-number'                  => array(
				'width' => '100px',
			),

			' .affx-sp-content-wrapper .title-wrapper' => array(
				'border-color'        => isset( $attr['productDivider']['color']['color'] ) ? $attr['productDivider']['color']['color'] : '#E6ECF7',
				'border-style'        => isset( $attr['productDivider']['style'] ) ? $attr['productDivider']['style'] : 'none',
				'border-bottom-width' => isset( $attr['productDivider']['width'] ) ? $attr['productDivider']['width'] : '1',
			),

			' .affx-single-product-wrapper.product-layout-2 .title-wrapper' => array(
				'padding-top'    => isset( $attr['contentSpacing']['desktop']['top'] ) ? $attr['contentSpacing']['desktop']['top'] : '30px',
				'padding-left'   => isset( $attr['contentSpacing']['desktop']['left'] ) ? $attr['contentSpacing']['desktop']['left'] : '25px',
				'padding-right'  => isset( $attr['contentSpacing']['desktop']['right'] ) ? $attr['contentSpacing']['desktop']['right'] : '25px',
				'padding-bottom' => isset( $attr['contentSpacing']['desktop']['bottom'] ) ? $attr['contentSpacing']['desktop']['bottom'] : '30px',
			),

			' .affx-single-product-wrapper.product-layout-2 .affx-sp-price' => array(
				'padding-left'  => isset( $attr['contentSpacing']['desktop']['left'] ) ? $attr['contentSpacing']['desktop']['left'] : '25px',
				'padding-right' => isset( $attr['contentSpacing']['desktop']['right'] ) ? $attr['contentSpacing']['desktop']['right'] : '25px',
			),

			' .affx-single-product-wrapper.product-layout-2 .button-wrapper' => array(
				'padding-left'   => isset( $attr['contentSpacing']['desktop']['left'] ) ? $attr['contentSpacing']['desktop']['left'] : '25px',
				'padding-right'  => isset( $attr['contentSpacing']['desktop']['right'] ) ? $attr['contentSpacing']['desktop']['right'] : '25px',
				'padding-bottom' => isset( $attr['contentSpacing']['desktop']['bottom'] ) ? $attr['contentSpacing']['desktop']['bottom'] : '30px',
			),

			' .affx-single-product-wrapper.product-layout-2 .affx-single-product-content' => array(
				'padding-left'  => isset( $attr['contentSpacing']['desktop']['left'] ) ? $attr['contentSpacing']['desktop']['left'] : '25px',
				'padding-right' => isset( $attr['contentSpacing']['desktop']['right'] ) ? $attr['contentSpacing']['desktop']['right'] : '25px',
			),

			' .affx-single-product-wrapper.product-layout-3 .affx-sp-inner' => array(
				'padding-top'    => isset( $attr['contentSpacing']['desktop']['top'] ) ? $attr['contentSpacing']['desktop']['top'] : '30px',
				'padding-left'   => isset( $attr['contentSpacing']['desktop']['left'] ) ? $attr['contentSpacing']['desktop']['left'] : '25px',
				'padding-right'  => isset( $attr['contentSpacing']['desktop']['right'] ) ? $attr['contentSpacing']['desktop']['right'] : '25px',
				'padding-bottom' => isset( $attr['contentSpacing']['desktop']['bottom'] ) ? $attr['contentSpacing']['desktop']['bottom'] : '30px',
			),

			' .affx-single-product-wrapper.product-layout-3 .affx-sp-content.image-right .affx-sp-content-wrapper' => array(
				'padding-top'    => '0',
				'padding-left'   => '24px',
				'padding-right'  => '24px',
				'padding-bottom' => '0',
			),
			' .affx-single-product-wrapper.product-layout-3 .affx-sp-content.image-left .affx-sp-content-wrapper' => array(
				'padding-top'    => '0',
				'padding-left'   => '24px',
				'padding-right'  => '0',
				'padding-bottom' => '0',
			),
			' .affx-single-product-wrapper.product-layout-3 .affx-sp-content.image-left .button-wrapper' => array(
				'padding-left' => '24px',
			),

			' .affx-sp-ribbon'                         => array(
				'width'      => '100%',
				'text-align' => isset( $attr['ribbonAlign'] ) ? $attr['ribbonAlign'] : 'left',
			),

			' .affx-sp-ribbon-title'                   => array(
				'background'      => isset( $attr['ribbonBgColorType'] ) && $attr['ribbonBgColorType'] === 'gradient' ? $ribbonGradient : $ribbonBgColor,
				'font-family'     => isset( $attr['ribbonContentTypography']['family'] ) ? $attr['ribbonContentTypography']['family'] : 'Default',
				'font-weight'     => AffiliateX_Helpers::get_fontweight_variation( $ribbon_variation ),
				'font-style'      => AffiliateX_Helpers::get_font_style( $ribbon_variation ),
				'font-size'       => isset( $attr['ribbonContentTypography']['size']['desktop'] ) ? $attr['ribbonContentTypography']['size']['desktop'] : '17px',
				'line-height'     => isset( $attr['ribbonContentTypography']['line-height']['desktop'] ) ? $attr['ribbonContentTypography']['line-height']['desktop'] : '1.65',
				'text-transform'  => isset( $attr['ribbonContentTypography']['text-transform'] ) ? $attr['ribbonContentTypography']['text-transform'] : 'none',
				'text-decoration' => isset( $attr['ribbonContentTypography']['text-decoration'] ) ? $attr['ribbonContentTypography']['text-decoration'] : 'none',
				'letter-spacing'  => isset( $attr['ribbonContentTypography']['letter-spacing']['desktop'] ) ? $attr['ribbonContentTypography']['letter-spacing']['desktop'] : '0em',
				'color'           => isset( $attr['ribbonColor'] ) ? $attr['ribbonColor'] : '#fff',
			),

			' .affx-sp-ribbon.ribbon-layout-2 .affx-sp-ribbon-title:before' => array(
				'border-bottom-color' => isset( $attr['ribbonBgColorType'] ) && $attr['ribbonBgColorType'] === 'solid' ? $attr['ribbonBGColor'] : $ribbonGradient,
			),

			' .affx-sp-content.image-right .affx-sp-ribbon.ribbon-layout-2 .affx-sp-ribbon-title:before' => array(
				'border-bottom-color' => 'transparent',
				'border-bottom-color' => isset( $attr['ribbonBgColorType'] ) && $attr['ribbonBgColorType'] === 'solid' ? $attr['ribbonBGColor'] : $ribbonGradient,
			),

			' .affx-sp-content.image-right .affx-sp-ribbon.ribbon-layout-2 .affx-sp-ribbon-title:hover:before' => array(
				'border-bottom-color' => 'transparent',
				'border-bottom-color' => isset( $attr['ribbonBgColorType'] ) && $attr['ribbonBgColorType'] === 'solid' ? $attr['ribbonBGColor'] : $ribbonGradient,
			),

			' .affiliatex-icon li:before'              => array(
				'color' => isset( $attr['iconColor'] ) ? $attr['iconColor'] : '#24B644',
			),

			' .affx-rating-number'                     => array(
				'background'      => isset( $attr['productRateNumBgColor'] ) ? $attr['productRateNumBgColor'] : '#2670FF',
				'color'           => isset( $attr['productRateNumberColor'] ) ? $attr['productRateNumberColor'] : '#ffffff',
				'font-family'     => isset( $attr['numRatingTypography']['family'] ) ? $attr['numRatingTypography']['family'] : 'Default',
				'font-weight'     => AffiliateX_Helpers::get_fontweight_variation( $rating_variation ),
				'font-style'      => AffiliateX_Helpers::get_font_style( $rating_variation ),
				'font-size'       => isset( $attr['numRatingTypography']['size']['desktop'] ) ? $attr['numRatingTypography']['size']['desktop'] : '36px',
				'line-height'     => isset( $attr['numRatingTypography']['line-height']['desktop'] ) ? $attr['numRatingTypography']['line-height']['desktop'] : '1.65',
				'text-transform'  => isset( $attr['numRatingTypography']['text-transform'] ) ? $attr['numRatingTypography']['text-transform'] : 'none',
				'text-decoration' => isset( $attr['numRatingTypography']['text-decoration'] ) ? $attr['numRatingTypography']['text-decoration'] : 'none',
				'letter-spacing'  => isset( $attr['numRatingTypography']['letter-spacing']['desktop'] ) ? $attr['numRatingTypography']['letter-spacing']['desktop'] : '0em',
			),

			' .affx-rating-number .num'                => array(
				'background' => isset( $attr['productRateNumBgColor'] ) ? $attr['productRateNumBgColor'] : '#2670FF',
				'color'      => isset( $attr['productRateNumberColor'] ) ? $attr['productRateNumberColor'] : '#ffffff',
			),

			' .affx-rating-number .label'              => array(
				'background' => isset( $attr['productRateContentBgColor'] ) ? $attr['productRateContentBgColor'] : '#262B33',
				'color'      => isset( $attr['productRateContentColor'] ) ? $attr['productRateContentColor'] : '#ffffff',
				'font-size'  => '0.444em',
			),

			' .affx-rating-number .label::before'      => array(
				'border-bottom-color' => isset( $attr['productRateContentBgColor'] ) ? $attr['productRateContentBgColor'] : '#262B33',
			),

			' .affx-rating-input-content:before'       => array(
				'border-bottom-color' => isset( $attr['productRateContentBgColor'] ) ? $attr['productRateContentBgColor'] : '#262B33',
			),

			' .affx-rating-input-content input'        => array(
				'color'           => isset( $attr['productRateContentColor'] ) ? $attr['productRateContentColor'] : '#ffffff',
				'font-family'     => isset( $attr['numRatingTypography']['family'] ) ? $attr['numRatingTypography']['family'] : 'Default',
				'font-weight'     => AffiliateX_Helpers::get_fontweight_variation( $rating_variation ),
				'font-style'      => AffiliateX_Helpers::get_font_style( $rating_variation ),
				'font-size'       => isset( $attr['numRatingTypography']['size']['desktop'] ) ? $attr['numRatingTypography']['size']['desktop'] : '36px',
				'line-height'     => isset( $attr['numRatingTypography']['line-height']['desktop'] ) ? $attr['numRatingTypography']['line-height']['desktop'] : '1.65',
				'text-transform'  => isset( $attr['numRatingTypography']['text-transform'] ) ? $attr['numRatingTypography']['text-transform'] : 'none',
				'text-decoration' => isset( $attr['numRatingTypography']['text-decoration'] ) ? $attr['numRatingTypography']['text-decoration'] : 'none',
				'letter-spacing'  => isset( $attr['numRatingTypography']['letter-spacing']['desktop'] ) ? $attr['numRatingTypography']['letter-spacing']['desktop'] : '0em',
			),

			' .affx-single-product-wrapper .affx-sp-img-wrapper' => array(
				'padding-top'      => isset( $attr['imagePadding']['desktop']['top'] ) ? $attr['imagePadding']['desktop']['top'] : '0px',
				'padding-left'     => isset( $attr['imagePadding']['desktop']['left'] ) ? $attr['imagePadding']['desktop']['left'] : '0px',
				'padding-right'    => isset( $attr['imagePadding']['desktop']['right'] ) ? $attr['imagePadding']['desktop']['right'] : '0px',
				'padding-bottom'   => isset( $attr['imagePadding']['desktop']['bottom'] ) ? $attr['imagePadding']['desktop']['bottom'] : '0px',
			),
		);

		return $selectors;
	}

	public static function get_mobileselectors( $attr ) {

		$mobile_selectors = array(
			' .affx-single-product-wrapper'     => array(
				'border-width'  => isset( $attr['productBorderWidth']['mobile']['top'] ) && isset( $attr['productBorderWidth']['mobile']['right'] ) && isset( $attr['productBorderWidth']['mobile']['bottom'] ) && isset( $attr['productBorderWidth']['mobile']['left'] ) ? $attr['productBorderWidth']['mobile']['top'] . ' ' . $attr['productBorderWidth']['mobile']['right'] . ' ' . $attr['productBorderWidth']['mobile']['bottom'] . ' ' . $attr['productBorderWidth']['mobile']['left'] . ' ' : '1px 1px 1px 1px',
				'border-radius' => isset( $attr['productBorderRadius']['mobile']['top'] ) && isset( $attr['productBorderRadius']['mobile']['right'] ) && isset( $attr['productBorderRadius']['mobile']['bottom'] ) && isset( $attr['productBorderRadius']['mobile']['left'] ) ? $attr['productBorderRadius']['mobile']['top'] . ' ' . $attr['productBorderRadius']['mobile']['right'] . ' ' . $attr['productBorderRadius']['mobile']['bottom'] . ' ' . $attr['productBorderRadius']['mobile']['left'] . ' ' : '0px 0px 0px 0px',
				'margin-top'    => isset( $attr['contentMargin']['mobile']['top'] ) ? $attr['contentMargin']['mobile']['top'] : '0px',
				'margin-left'   => isset( $attr['contentMargin']['mobile']['left'] ) ? $attr['contentMargin']['mobile']['left'] : '0px',
				'margin-right'  => isset( $attr['contentMargin']['mobile']['right'] ) ? $attr['contentMargin']['mobile']['right'] : '0px',
				'margin-bottom' => isset( $attr['contentMargin']['mobile']['bottom'] ) ? $attr['contentMargin']['mobile']['bottom'] : '30px',

			),
			' .affx-single-product-title'       => array(
				'font-size'      => isset( $attr['productTitleTypography']['size']['mobile'] ) ? $attr['productTitleTypography']['size']['mobile'] : '24px',
				'line-height'    => isset( $attr['productTitleTypography']['line-height']['mobile'] ) ? $attr['productTitleTypography']['line-height']['mobile'] : '1.333',
				'letter-spacing' => isset( $attr['productTitleTypography']['letter-spacing']['mobile'] ) ? $attr['productTitleTypography']['letter-spacing']['mobile'] : '0em',

			),
			' .affx-single-product-subtitle'    => array(
				'font-size'      => isset( $attr['productSubtitleTypography']['size']['mobile'] ) ? $attr['productSubtitleTypography']['size']['mobile'] : '18px',
				'line-height'    => isset( $attr['productSubtitleTypography']['line-height']['mobile'] ) ? $attr['productSubtitleTypography']['line-height']['mobile'] : '1.5',
				'letter-spacing' => isset( $attr['productSubtitleTypography']['letter-spacing']['mobile'] ) ? $attr['productSubtitleTypography']['letter-spacing']['mobile'] : '0em',
			),
			' .affx-single-product-content'     => array(
				'font-size'      => isset( $attr['productContentTypography']['size']['mobile'] ) ? $attr['productContentTypography']['size']['mobile'] : '18px',
				'line-height'    => isset( $attr['productContentTypography']['line-height']['mobile'] ) ? $attr['productContentTypography']['line-height']['mobile'] : '1.5',
				'letter-spacing' => isset( $attr['productContentTypography']['letter-spacing']['mobile'] ) ? $attr['productContentTypography']['letter-spacing']['mobile'] : '0em',
			),
			' .affx-sp-marked-price'            => array(
				'font-size'      => isset( $attr['pricingTypography']['size']['mobile'] ) ? $attr['pricingTypography']['size']['mobile'] : '18px',
				'line-height'    => isset( $attr['pricingTypography']['line-height']['mobile'] ) ? $attr['pricingTypography']['line-height']['mobile'] : '1.5',
				'letter-spacing' => isset( $attr['pricingTypography']['letter-spacing']['mobile'] ) ? $attr['pricingTypography']['letter-spacing']['mobile'] : '0em',
			),
			' .affx-sp-sale-price'              => array(
				'font-size'      => isset( $attr['pricingTypography']['size']['mobile'] ) ? $attr['pricingTypography']['size']['mobile'] : '18px',
				'line-height'    => isset( $attr['pricingTypography']['line-height']['mobile'] ) ? $attr['pricingTypography']['line-height']['mobile'] : '1.5',
				'letter-spacing' => isset( $attr['pricingTypography']['letter-spacing']['mobile'] ) ? $attr['pricingTypography']['letter-spacing']['mobile'] : '0em',
			),
			' .affx-sp-content-wrapper'         => array(
				'padding-top'    => isset( $attr['contentSpacing']['mobile']['top'] ) ? $attr['contentSpacing']['mobile']['top'] : '30px',
				'padding-left'   => isset( $attr['contentSpacing']['mobile']['left'] ) ? $attr['contentSpacing']['mobile']['left'] : '25px',
				'padding-right'  => isset( $attr['contentSpacing']['mobile']['right'] ) ? $attr['contentSpacing']['mobile']['right'] : '25px',
				'padding-bottom' => isset( $attr['contentSpacing']['mobile']['bottom'] ) ? $attr['contentSpacing']['mobile']['bottom'] : '30px',
			),
			' .affx-single-product-wrapper.product-layout-2 .title-wrapper' => array(
				'padding-top'    => isset( $attr['contentSpacing']['mobile']['top'] ) ? $attr['contentSpacing']['mobile']['top'] : '30px',
				'padding-left'   => isset( $attr['contentSpacing']['mobile']['left'] ) ? $attr['contentSpacing']['mobile']['left'] : '25px',
				'padding-right'  => isset( $attr['contentSpacing']['mobile']['right'] ) ? $attr['contentSpacing']['mobile']['right'] : '25px',
				'padding-bottom' => isset( $attr['contentSpacing']['mobile']['bottom'] ) ? $attr['contentSpacing']['mobile']['bottom'] : '30px',
			),
			' .affx-single-product-wrapper.product-layout-2 .affx-sp-price' => array(
				'padding-left'  => isset( $attr['contentSpacing']['mobile']['left'] ) ? $attr['contentSpacing']['mobile']['left'] : '25px',
				'padding-right' => isset( $attr['contentSpacing']['mobile']['right'] ) ? $attr['contentSpacing']['mobile']['right'] : '25px',
			),
			' .affx-single-product-wrapper.product-layout-2 .button-wrapper' => array(
				'padding-left'   => isset( $attr['contentSpacing']['mobile']['left'] ) ? $attr['contentSpacing']['mobile']['left'] : '25px',
				'padding-right'  => isset( $attr['contentSpacing']['mobile']['right'] ) ? $attr['contentSpacing']['mobile']['right'] : '25px',
				'padding-bottom' => isset( $attr['contentSpacing']['mobile']['bottom'] ) ? $attr['contentSpacing']['mobile']['bottom'] : '30px',
			),
			' .affx-single-product-wrapper.product-layout-2 .affx-single-product-content' => array(
				'padding-left'  => isset( $attr['contentSpacing']['mobile']['left'] ) ? $attr['contentSpacing']['mobile']['left'] : '25px',
				'padding-right' => isset( $attr['contentSpacing']['mobile']['right'] ) ? $attr['contentSpacing']['mobile']['right'] : '25px',
			),
			' .affx-single-product-wrapper.product-layout-3 .affx-sp-inner' => array(
				'padding-top'    => isset( $attr['contentSpacing']['mobile']['top'] ) ? $attr['contentSpacing']['mobile']['top'] : '30px',
				'padding-left'   => isset( $attr['contentSpacing']['mobile']['left'] ) ? $attr['contentSpacing']['mobile']['left'] : '25px',
				'padding-right'  => isset( $attr['contentSpacing']['mobile']['right'] ) ? $attr['contentSpacing']['mobile']['right'] : '25px',
				'padding-bottom' => isset( $attr['contentSpacing']['mobile']['bottom'] ) ? $attr['contentSpacing']['mobile']['bottom'] : '30px',
			),
			' .affx-sp-ribbon-title'            => array(
				'font-size'      => isset( $attr['ribbonContentTypography']['size']['mobile'] ) ? $attr['ribbonContentTypography']['size']['mobile'] : '18px',
				'line-height'    => isset( $attr['ribbonContentTypography']['line-height']['mobile'] ) ? $attr['ribbonContentTypography']['line-height']['mobile'] : '1.5',
				'letter-spacing' => isset( $attr['ribbonContentTypography']['letter-spacing']['mobile'] ) ? $attr['ribbonContentTypography']['letter-spacing']['mobile'] : '0em',
			),
			' .affx-rating-input-number'        => array(
				'font-size'      => isset( $attr['numRatingTypography']['size']['mobile'] ) ? $attr['numRatingTypography']['size']['mobile'] : '18px',
				'line-height'    => isset( $attr['numRatingTypography']['line-height']['mobile'] ) ? $attr['numRatingTypography']['line-height']['mobile'] : '1.5',
				'letter-spacing' => isset( $attr['numRatingTypography']['letter-spacing']['mobile'] ) ? $attr['numRatingTypography']['letter-spacing']['mobile'] : '0em',
			),
			' .affx-rating-input-number input'  => array(
				'font-size'      => isset( $attr['numRatingTypography']['size']['mobile'] ) ? $attr['numRatingTypography']['size']['mobile'] : '18px',
				'line-height'    => isset( $attr['numRatingTypography']['line-height']['mobile'] ) ? $attr['numRatingTypography']['line-height']['mobile'] : '1.5',
				'letter-spacing' => isset( $attr['numRatingTypography']['letter-spacing']['mobile'] ) ? $attr['numRatingTypography']['letter-spacing']['mobile'] : '0em',
			),
			' .affx-rating-input-content'       => array(
				'font-size'      => isset( $attr['numRatingTypography']['size']['mobile'] ) ? $attr['numRatingTypography']['size']['mobile'] : '18px',
				'line-height'    => isset( $attr['numRatingTypography']['line-height']['mobile'] ) ? $attr['numRatingTypography']['line-height']['mobile'] : '1.5',
				'letter-spacing' => isset( $attr['numRatingTypography']['letter-spacing']['mobile'] ) ? $attr['numRatingTypography']['letter-spacing']['mobile'] : '0em',
			),
			' .affx-rating-input-content input' => array(
				'font-size'      => isset( $attr['numRatingTypography']['size']['mobile'] ) ? $attr['numRatingTypography']['size']['mobile'] : '18px',
				'line-height'    => isset( $attr['numRatingTypography']['line-height']['mobile'] ) ? $attr['numRatingTypography']['line-height']['mobile'] : '1.5',
				'letter-spacing' => isset( $attr['numRatingTypography']['letter-spacing']['mobile'] ) ? $attr['numRatingTypography']['letter-spacing']['mobile'] : '0em',
			),
			' .affx-single-product-wrapper .affx-sp-img-wrapper' => array(
				'padding-top'      => isset( $attr['imagePadding']['mobile']['top'] ) ? $attr['imagePadding']['mobile']['top'] : '0px',
				'padding-left'     => isset( $attr['imagePadding']['mobile']['left'] ) ? $attr['imagePadding']['mobile']['left'] : '0px',
				'padding-right'    => isset( $attr['imagePadding']['mobile']['right'] ) ? $attr['imagePadding']['mobile']['right'] : '0px',
				'padding-bottom'   => isset( $attr['imagePadding']['mobile']['bottom'] ) ? $attr['imagePadding']['mobile']['bottom'] : '0px',
			),
		);
		return $mobile_selectors;
	}

	public static function get_tabletselectors( $attr ) {

		$tablet_selectors = array(
			' .affx-single-product-wrapper'     => array(
				'border-width'  => isset( $attr['productBorderWidth']['tablet']['top'] ) && isset( $attr['productBorderWidth']['tablet']['right'] ) && isset( $attr['productBorderWidth']['tablet']['bottom'] ) && isset( $attr['productBorderWidth']['tablet']['left'] ) ? $attr['productBorderWidth']['tablet']['top'] . ' ' . $attr['productBorderWidth']['tablet']['right'] . ' ' . $attr['productBorderWidth']['tablet']['bottom'] . ' ' . $attr['productBorderWidth']['tablet']['left'] . ' ' : '1px 1px 1px 1px',
				'border-radius' => isset( $attr['productBorderRadius']['tablet']['top'] ) && isset( $attr['productBorderRadius']['tablet']['right'] ) && isset( $attr['productBorderRadius']['tablet']['bottom'] ) && isset( $attr['productBorderRadius']['tablet']['left'] ) ? $attr['productBorderRadius']['tablet']['top'] . ' ' . $attr['productBorderRadius']['tablet']['right'] . ' ' . $attr['productBorderRadius']['tablet']['bottom'] . ' ' . $attr['productBorderRadius']['tablet']['left'] . ' ' : '0px 0px 0px 0px',
				'margin-top'    => isset( $attr['contentMargin']['tablet']['top'] ) ? $attr['contentMargin']['tablet']['top'] : '0px',
				'margin-left'   => isset( $attr['contentMargin']['tablet']['left'] ) ? $attr['contentMargin']['tablet']['left'] : '0px',
				'margin-right'  => isset( $attr['contentMargin']['tablet']['right'] ) ? $attr['contentMargin']['tablet']['right'] : '0px',
				'margin-bottom' => isset( $attr['contentMargin']['tablet']['bottom'] ) ? $attr['contentMargin']['tablet']['bottom'] : '30px',

			),
			' .affx-single-product-title'       => array(
				'font-size'      => isset( $attr['productTitleTypography']['size']['tablet'] ) ? $attr['productTitleTypography']['size']['tablet'] : '24px',
				'line-height'    => isset( $attr['productTitleTypography']['line-height']['tablet'] ) ? $attr['productTitleTypography']['line-height']['tablet'] : '1.333',
				'letter-spacing' => isset( $attr['productTitleTypography']['letter-spacing']['tablet'] ) ? $attr['productTitleTypography']['letter-spacing']['tablet'] : '0em',

			),
			' .affx-single-product-subtitle'    => array(
				'font-size'      => isset( $attr['productSubtitleTypography']['size']['tablet'] ) ? $attr['productSubtitleTypography']['size']['tablet'] : '18px',
				'line-height'    => isset( $attr['productSubtitleTypography']['line-height']['tablet'] ) ? $attr['productSubtitleTypography']['line-height']['tablet'] : '1.5',
				'letter-spacing' => isset( $attr['productSubtitleTypography']['letter-spacing']['tablet'] ) ? $attr['productSubtitleTypography']['letter-spacing']['tablet'] : '0em',
			),
			' .affx-single-product-content'     => array(
				'font-size'      => isset( $attr['productContentTypography']['size']['tablet'] ) ? $attr['productContentTypography']['size']['tablet'] : '18px',
				'line-height'    => isset( $attr['productContentTypography']['line-height']['tablet'] ) ? $attr['productContentTypography']['line-height']['tablet'] : '1.5',
				'letter-spacing' => isset( $attr['productContentTypography']['letter-spacing']['tablet'] ) ? $attr['productContentTypography']['letter-spacing']['tablet'] : '0em',
			),
			' .affx-sp-marked-price'            => array(
				'font-size'      => isset( $attr['pricingTypography']['size']['tablet'] ) ? $attr['pricingTypography']['size']['tablet'] : '18px',
				'line-height'    => isset( $attr['pricingTypography']['line-height']['tablet'] ) ? $attr['pricingTypography']['line-height']['tablet'] : '1.5',
				'letter-spacing' => isset( $attr['pricingTypography']['letter-spacing']['tablet'] ) ? $attr['pricingTypography']['letter-spacing']['tablet'] : '0em',
			),
			' .affx-sp-sale-price'              => array(
				'font-size'      => isset( $attr['pricingTypography']['size']['tablet'] ) ? $attr['pricingTypography']['size']['tablet'] : '18px',
				'line-height'    => isset( $attr['pricingTypography']['line-height']['tablet'] ) ? $attr['pricingTypography']['line-height']['tablet'] : '1.5',
				'letter-spacing' => isset( $attr['pricingTypography']['letter-spacing']['tablet'] ) ? $attr['pricingTypography']['letter-spacing']['tablet'] : '0em',
			),
			' .affx-sp-content-wrapper'         => array(
				'padding-top'    => isset( $attr['contentSpacing']['tablet']['top'] ) ? $attr['contentSpacing']['tablet']['top'] : '30px',
				'padding-left'   => isset( $attr['contentSpacing']['tablet']['left'] ) ? $attr['contentSpacing']['tablet']['left'] : '25px',
				'padding-right'  => isset( $attr['contentSpacing']['tablet']['right'] ) ? $attr['contentSpacing']['tablet']['right'] : '25px',
				'padding-bottom' => isset( $attr['contentSpacing']['tablet']['bottom'] ) ? $attr['contentSpacing']['tablet']['bottom'] : '30px',
			),
			' .affx-single-product-wrapper.product-layout-2 .title-wrapper' => array(
				'padding-top'    => isset( $attr['contentSpacing']['tablet']['top'] ) ? $attr['contentSpacing']['tablet']['top'] : '30px',
				'padding-left'   => isset( $attr['contentSpacing']['tablet']['left'] ) ? $attr['contentSpacing']['tablet']['left'] : '25px',
				'padding-right'  => isset( $attr['contentSpacing']['tablet']['right'] ) ? $attr['contentSpacing']['tablet']['right'] : '25px',
				'padding-bottom' => isset( $attr['contentSpacing']['tablet']['bottom'] ) ? $attr['contentSpacing']['tablet']['bottom'] : '30px',
			),
			' .affx-single-product-wrapper.product-layout-2 .affx-sp-price' => array(
				'padding-left'  => isset( $attr['contentSpacing']['tablet']['left'] ) ? $attr['contentSpacing']['tablet']['left'] : '25px',
				'padding-right' => isset( $attr['contentSpacing']['tablet']['right'] ) ? $attr['contentSpacing']['tablet']['right'] : '25px',
			),
			' .affx-single-product-wrapper.product-layout-2 .button-wrapper' => array(
				'padding-left'   => isset( $attr['contentSpacing']['tablet']['left'] ) ? $attr['contentSpacing']['tablet']['left'] : '25px',
				'padding-right'  => isset( $attr['contentSpacing']['tablet']['right'] ) ? $attr['contentSpacing']['tablet']['right'] : '25px',
				'padding-bottom' => isset( $attr['contentSpacing']['tablet']['bottom'] ) ? $attr['contentSpacing']['tablet']['bottom'] : '30px',
			),
			' .affx-single-product-wrapper.product-layout-2 .affx-single-product-content' => array(
				'padding-left'  => isset( $attr['contentSpacing']['tablet']['left'] ) ? $attr['contentSpacing']['tablet']['left'] : '25px',
				'padding-right' => isset( $attr['contentSpacing']['tablet']['right'] ) ? $attr['contentSpacing']['tablet']['right'] : '25px',
			),
			' .affx-single-product-wrapper.product-layout-3 .affx-sp-inner' => array(
				'padding-top'    => isset( $attr['contentSpacing']['tablet']['top'] ) ? $attr['contentSpacing']['tablet']['top'] : '30px',
				'padding-left'   => isset( $attr['contentSpacing']['tablet']['left'] ) ? $attr['contentSpacing']['tablet']['left'] : '25px',
				'padding-right'  => isset( $attr['contentSpacing']['tablet']['right'] ) ? $attr['contentSpacing']['tablet']['right'] : '25px',
				'padding-bottom' => isset( $attr['contentSpacing']['tablet']['bottom'] ) ? $attr['contentSpacing']['tablet']['bottom'] : '30px',
			),
			' .affx-sp-ribbon-title'            => array(
				'font-size'      => isset( $attr['ribbonContentTypography']['size']['tablet'] ) ? $attr['ribbonContentTypography']['size']['tablet'] : '18px',
				'line-height'    => isset( $attr['ribbonContentTypography']['line-height']['tablet'] ) ? $attr['ribbonContentTypography']['line-height']['tablet'] : '1.5',
				'letter-spacing' => isset( $attr['ribbonContentTypography']['letter-spacing']['tablet'] ) ? $attr['ribbonContentTypography']['letter-spacing']['tablet'] : '0em',
			),
			' .affx-rating-input-number'        => array(
				'font-size'      => isset( $attr['numRatingTypography']['size']['tablet'] ) ? $attr['numRatingTypography']['size']['tablet'] : '18px',
				'line-height'    => isset( $attr['numRatingTypography']['line-height']['tablet'] ) ? $attr['numRatingTypography']['line-height']['tablet'] : '1.5',
				'letter-spacing' => isset( $attr['numRatingTypography']['letter-spacing']['tablet'] ) ? $attr['numRatingTypography']['letter-spacing']['tablet'] : '0em',
			),
			' .affx-rating-input-number input'  => array(
				'font-size'      => isset( $attr['numRatingTypography']['size']['tablet'] ) ? $attr['numRatingTypography']['size']['tablet'] : '18px',
				'line-height'    => isset( $attr['numRatingTypography']['line-height']['tablet'] ) ? $attr['numRatingTypography']['line-height']['tablet'] : '1.5',
				'letter-spacing' => isset( $attr['numRatingTypography']['letter-spacing']['tablet'] ) ? $attr['numRatingTypography']['letter-spacing']['tablet'] : '0em',
			),
			' .affx-rating-input-content'       => array(
				'font-size'      => isset( $attr['numRatingTypography']['size']['tablet'] ) ? $attr['numRatingTypography']['size']['tablet'] : '18px',
				'line-height'    => isset( $attr['numRatingTypography']['line-height']['tablet'] ) ? $attr['numRatingTypography']['line-height']['tablet'] : '1.5',
				'letter-spacing' => isset( $attr['numRatingTypography']['letter-spacing']['tablet'] ) ? $attr['numRatingTypography']['letter-spacing']['tablet'] : '0em',
			),
			' .affx-rating-input-content input' => array(
				'font-size'      => isset( $attr['numRatingTypography']['size']['tablet'] ) ? $attr['numRatingTypography']['size']['tablet'] : '18px',
				'line-height'    => isset( $attr['numRatingTypography']['line-height']['tablet'] ) ? $attr['numRatingTypography']['line-height']['tablet'] : '1.5',
				'letter-spacing' => isset( $attr['numRatingTypography']['letter-spacing']['tablet'] ) ? $attr['numRatingTypography']['letter-spacing']['tablet'] : '0em',
			),
			' .affx-single-product-wrapper .affx-sp-img-wrapper' => array(
				'padding-top'      => isset( $attr['imagePadding']['tablet']['top'] ) ? $attr['imagePadding']['tablet']['top'] : '0px',
				'padding-left'     => isset( $attr['imagePadding']['tablet']['left'] ) ? $attr['imagePadding']['tablet']['left'] : '0px',
				'padding-right'    => isset( $attr['imagePadding']['tablet']['right'] ) ? $attr['imagePadding']['tablet']['right'] : '0px',
				'padding-bottom'   => isset( $attr['imagePadding']['tablet']['bottom'] ) ? $attr['imagePadding']['tablet']['bottom'] : '0px',
			),
		);
		return $tablet_selectors;
	}

}
