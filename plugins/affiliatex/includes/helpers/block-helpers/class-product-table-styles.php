<?php
/**
 * Product Table Block Styles
 *
 * @package AffiliateX
 */

class AffiliateX_Product_Table_Styles {

	public static function block_fonts( $attr ) {
		return array(
			'ribbonTypography'  => isset( $attr['ribbonTypography'] ) ? $attr['ribbonTypography'] : array(),
			'priceTypography'   => isset( $attr['priceTypography'] ) ? $attr['priceTypography'] : array(),
			'buttonTypography'  => isset( $attr['buttonTypography'] ) ? $attr['buttonTypography'] : array(),
			'contentTypography' => isset( $attr['contentTypography'] ) ? $attr['contentTypography'] : array(),
			'counterTypography' => isset( $attr['counterTypography'] ) ? $attr['counterTypography'] : array(),
			'titleTypography'   => isset( $attr['titleTypography'] ) ? $attr['titleTypography'] : array(),
			'headerTypography'  => isset( $attr['headerTypography'] ) ? $attr['headerTypography'] : array(),
			'ratingTypography'  => isset( $attr['ratingTypography'] ) ? $attr['ratingTypography'] : array(),
			'rating2Typography' => isset( $attr['rating2Typography'] ) ? $attr['rating2Typography'] : array(),
		);
	}

	public static function block_css( $attr, $id ) {
		$selectors = self::get_selectors( $attr );

		$m_selectors = self::get_mobileselectors( $attr );

		$t_selectors = self::get_tabletselectors( $attr );

		$desktop = AffiliateX_Helpers::generate_css( $selectors, '#affiliatex-pdt-table-style-' . $id );

		$tablet = AffiliateX_Helpers::generate_css( $t_selectors, '#affiliatex-pdt-table-style-' . $id );

		$mobile = AffiliateX_Helpers::generate_css( $m_selectors, '#affiliatex-pdt-table-style-' . $id );

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
		
		$bgType           = isset( $attr['bgType'] ) ? $attr['bgType'] : 'solid';
		$bgGradient       = isset( $attr['bgColorGradient']['gradient'] ) ? $attr['bgColorGradient']['gradient'] : 'linear-gradient(135deg,rgb(238,238,238) 0%,rgb(169,184,195) 100%)';
		$bgColor          = isset( $attr['bgColorSolid'] ) ? $attr['bgColorSolid'] : '#FFFFFF';
		$variation        = isset( $attr['contentTypography']['variation'] ) ? $attr['contentTypography']['variation'] : 'n4';
		$ratingVariation  = isset( $attr['ratingTypography']['variation'] ) ? $attr['ratingTypography']['variation'] : 'n7';
		$rating2Variation = isset( $attr['rating2Typography']['variation'] ) ? $attr['rating2Typography']['variation'] : 'n4';
		$contentVariation = isset( $attr['contentTypography']['variation'] ) ? $attr['contentTypography']['variation'] : 'n4';
		$titleVariation   = isset( $attr['titleTypography']['variation'] ) ? $attr['titleTypography']['variation'] : 'n4';
		$ribbonVariation  = isset( $attr['ribbonTypography']['variation'] ) ? $attr['ribbonTypography']['variation'] : 'n5';
		$counterVariation = isset( $attr['counterTypography']['variation'] ) ? $attr['counterTypography']['variation'] : 'n5';
		$buttonVariation  = isset( $attr['buttonTypography']['variation'] ) ? $attr['buttonTypography']['variation'] : 'n4';
		$priceVariation   = isset( $attr['priceTypography']['variation'] ) ? $attr['priceTypography']['variation'] : 'n4';
		$headerVariation  = isset( $attr['headerTypography']['variation'] ) ? $attr['headerTypography']['variation'] : 'n4';

		$selectors = array(
			' .affx-pdt-table-wrapper'                => array(
				'font-family'     => isset( $attr['contentTypography']['family'] ) ? $attr['contentTypography']['family'] : $global_font_family,
				'font-size'       => isset( $attr['contentTypography']['size']['desktop'] ) ? $attr['contentTypography']['size']['desktop'] : '18px',
				'line-height'     => isset( $attr['contentTypography']['line-height']['desktop'] ) ? $attr['contentTypography']['line-height']['desktop'] : '1.65',
				'text-transform'  => isset( $attr['contentTypography']['text-transform'] ) ? $attr['contentTypography']['text-transform'] : 'none',
				'text-decoration' => isset( $attr['contentTypography']['text-decoration'] ) ? $attr['contentTypography']['text-decoration'] : 'none',
				'letter-spacing'  => isset( $attr['contentTypography']['letter-spacing']['desktop'] ) ? $attr['contentTypography']['letter-spacing']['desktop'] : '0em',
				'font-weight'     => AffiliateX_Helpers::get_fontweight_variation( $variation ),
				'font-style'      => AffiliateX_Helpers::get_font_style( $variation ),
				'margin-top'      => isset( $attr['margin']['desktop']['top'] ) ? $attr['margin']['desktop']['top'] : '0px',
				'margin-left'     => isset( $attr['margin']['desktop']['left'] ) ? $attr['margin']['desktop']['left'] : '0px',
				'margin-right'    => isset( $attr['margin']['desktop']['right'] ) ? $attr['margin']['desktop']['right'] : '0px',
				'margin-bottom'   => isset( $attr['margin']['desktop']['bottom'] ) ? $attr['margin']['desktop']['bottom'] : '30px',
				'color'           => isset( $attr['contentColor'] ) ? $attr['contentColor'] : $global_font_color,
			),
			' .star-rating-single-wrap'               => array(
				'color'           => isset( $attr['ratingColor'] ) ? $attr['ratingColor'] : '#FFFFFF',
				'background'      => isset( $attr['ratingBgColor'] ) ? $attr['ratingBgColor'] : '#24B644',
				'font-family'     => isset( $attr['ratingTypography']['family'] ) ? $attr['ratingTypography']['family'] : $global_font_family,
				'font-size'       => isset( $attr['ratingTypography']['size']['desktop'] ) ? $attr['ratingTypography']['size']['desktop'] : '13px',
				'line-height'     => isset( $attr['ratingTypography']['line-height']['desktop'] ) ? $attr['ratingTypography']['line-height']['desktop'] : '1.5',
				'text-transform'  => isset( $attr['ratingTypography']['text-transform'] ) ? $attr['ratingTypography']['text-transform'] : 'none',
				'text-decoration' => isset( $attr['ratingTypography']['text-decoration'] ) ? $attr['ratingTypography']['text-decoration'] : 'none',
				'letter-spacing'  => isset( $attr['ratingTypography']['letter-spacing']['desktop'] ) ? $attr['ratingTypography']['letter-spacing']['desktop'] : '0em',
				'font-weight'     => AffiliateX_Helpers::get_fontweight_variation( $ratingVariation ),
				'font-style'      => AffiliateX_Helpers::get_font_style( $ratingVariation ),
			),
			' .circle-wrap .circle-mask .fill'        => array(
				'background' => isset( $attr['rating2BgColor'] ) ? $attr['rating2BgColor'] : '#24B644',
			),
			' .affx-circle-progress-container .affx-circle-inside' => array(
				'color'           => isset( $attr['rating2Color'] ) ? $attr['rating2Color'] : '#262B33',
				'font-family'     => isset( $attr['rating2Typography']['family'] ) ? $attr['rating2Typography']['family'] : $global_font_family,
				'font-size'       => isset( $attr['rating2Typography']['size']['desktop'] ) ? $attr['rating2Typography']['size']['desktop'] : '24px',
				'line-height'     => isset( $attr['rating2Typography']['line-height']['desktop'] ) ? $attr['rating2Typography']['line-height']['desktop'] : '1.5',
				'text-transform'  => isset( $attr['rating2Typography']['text-transform'] ) ? $attr['rating2Typography']['text-transform'] : 'none',
				'text-decoration' => isset( $attr['rating2Typography']['text-decoration'] ) ? $attr['rating2Typography']['text-decoration'] : 'none',
				'letter-spacing'  => isset( $attr['rating2Typography']['letter-spacing']['desktop'] ) ? $attr['rating2Typography']['letter-spacing']['desktop'] : '0em',
				'font-weight'     => AffiliateX_Helpers::get_fontweight_variation( $rating2Variation ),
				'font-style'      => AffiliateX_Helpers::get_font_style( $rating2Variation ),
			),
			' .affx-pdt-table-wrapper p'              => array(
				'color'           => isset( $attr['contentColor'] ) ? $attr['contentColor'] : $global_font_color,
				'font-family'     => isset( $attr['contentTypography']['family'] ) ? $attr['contentTypography']['family'] : $global_font_family,
				'font-size'       => isset( $attr['contentTypography']['size']['desktop'] ) ? $attr['contentTypography']['size']['desktop'] : '18px',
				'line-height'     => isset( $attr['contentTypography']['line-height']['desktop'] ) ? $attr['contentTypography']['line-height']['desktop'] : '1.5',
				'text-transform'  => isset( $attr['contentTypography']['text-transform'] ) ? $attr['contentTypography']['text-transform'] : 'none',
				'text-decoration' => isset( $attr['contentTypography']['text-decoration'] ) ? $attr['contentTypography']['text-decoration'] : 'none',
				'letter-spacing'  => isset( $attr['contentTypography']['letter-spacing']['desktop'] ) ? $attr['contentTypography']['letter-spacing']['desktop'] : '0em',
				'font-weight'     => AffiliateX_Helpers::get_fontweight_variation( $contentVariation ),
				'font-style'      => AffiliateX_Helpers::get_font_style( $contentVariation ),
			),
			' .affx-pdt-table-wrapper li'             => array(
				'color'           => isset( $attr['contentColor'] ) ? $attr['contentColor'] : $global_font_color,
				'font-family'     => isset( $attr['contentTypography']['family'] ) ? $attr['contentTypography']['family'] : $global_font_family,
				'font-size'       => isset( $attr['contentTypography']['size']['desktop'] ) ? $attr['contentTypography']['size']['desktop'] : '18px',
				'line-height'     => isset( $attr['contentTypography']['line-height']['desktop'] ) ? $attr['contentTypography']['line-height']['desktop'] : '1.5',
				'text-transform'  => isset( $attr['contentTypography']['text-transform'] ) ? $attr['contentTypography']['text-transform'] : 'none',
				'text-decoration' => isset( $attr['contentTypography']['text-decoration'] ) ? $attr['contentTypography']['text-decoration'] : 'none',
				'letter-spacing'  => isset( $attr['contentTypography']['letter-spacing']['desktop'] ) ? $attr['contentTypography']['letter-spacing']['desktop'] : '0em',
				'font-weight'     => AffiliateX_Helpers::get_fontweight_variation( $contentVariation ),
				'font-style'      => AffiliateX_Helpers::get_font_style( $contentVariation ),
			),
			' .affx-pdt-table-wrapper .affx-pdt-name' => array(
				'color'           => isset( $attr['titleColor'] ) ? $attr['titleColor'] : $global_font_color,
				'font-family'     => isset( $attr['titleTypography']['family'] ) ? $attr['titleTypography']['family'] : $global_font_family,
				'font-size'       => isset( $attr['titleTypography']['size']['desktop'] ) ? $attr['titleTypography']['size']['desktop'] : '22px',
				'line-height'     => isset( $attr['titleTypography']['line-height']['desktop'] ) ? $attr['titleTypography']['line-height']['desktop'] : '1.5',
				'text-transform'  => isset( $attr['titleTypography']['text-transform'] ) ? $attr['titleTypography']['text-transform'] : 'none',
				'text-decoration' => isset( $attr['titleTypography']['text-decoration'] ) ? $attr['titleTypography']['text-decoration'] : 'none',
				'letter-spacing'  => isset( $attr['titleTypography']['letter-spacing']['desktop'] ) ? $attr['titleTypography']['letter-spacing']['desktop'] : '0em',
				'font-weight'     => AffiliateX_Helpers::get_fontweight_variation( $titleVariation ),
				'font-style'      => AffiliateX_Helpers::get_font_style( $titleVariation ),
			),
			' .affx-pdt-table-wrapper:not(.layout-3)' => array(
				'border-style' => isset( $attr['border']['style'] ) ? $attr['border']['style'] : 'solid',
				'border-color' => isset( $attr['border']['color']['color'] ) ? $attr['border']['color']['color'] : '#E6ECF7',
				'border-width' => isset( $attr['borderWidth']['desktop']['top'] ) && isset( $attr['borderWidth']['desktop']['right'] ) && isset( $attr['borderWidth']['desktop']['bottom'] ) && isset( $attr['borderWidth']['desktop']['left'] ) ? $attr['borderWidth']['desktop']['top'] . ' ' . $attr['borderWidth']['desktop']['right'] . ' ' . $attr['borderWidth']['desktop']['bottom'] . ' ' . $attr['borderWidth']['desktop']['left'] . ' ' : '1px 1px 1px 1px',
				'background'   => $bgType && $bgType === 'solid' ? $bgColor : $bgGradient,
				'box-shadow'   => isset( $attr['boxShadow'] ) && $attr['boxShadow']['enable'] ? AffiliateX_Helpers::get_css_boxshadow( $attr['boxShadow'] ) : 'none',
			),
			' .affx-pdt-table' => array(
				'background' => $bgType && $bgType === 'solid' ? $bgColor : $bgGradient,
			),
			' .affx-pdt-table-single'                 => array(
				'margin-top'    => isset( $attr['margin']['desktop']['top'] ) ? $attr['margin']['desktop']['top'] : '0px',
				'margin-left'   => isset( $attr['margin']['desktop']['left'] ) ? $attr['margin']['desktop']['left'] : '0px',
				'margin-right'  => isset( $attr['margin']['desktop']['right'] ) ? $attr['margin']['desktop']['right'] : '0px',
				'margin-bottom' => isset( $attr['margin']['desktop']['bottom'] ) ? $attr['margin']['desktop']['bottom'] : '30px',
				'border-style'  => isset( $attr['border']['style'] ) ? $attr['border']['style'] : 'solid',
				'border-color'  => isset( $attr['border']['color']['color'] ) ? $attr['border']['color']['color'] : '#E6ECF7',
				'border-width'  => isset( $attr['borderWidth']['desktop']['top'] ) && isset( $attr['borderWidth']['desktop']['right'] ) && isset( $attr['borderWidth']['desktop']['bottom'] ) && isset( $attr['borderWidth']['desktop']['left'] ) ? $attr['borderWidth']['desktop']['top'] . ' ' . $attr['borderWidth']['desktop']['right'] . ' ' . $attr['borderWidth']['desktop']['bottom'] . ' ' . $attr['borderWidth']['desktop']['left'] . ' ' : '1px 1px 1px 1px',
				'border-radius' => isset( $attr['borderRadius']['desktop']['top'] ) && isset( $attr['borderRadius']['desktop']['right'] ) && isset( $attr['borderRadius']['desktop']['bottom'] ) && isset( $attr['borderRadius']['desktop']['left'] ) ? $attr['borderRadius']['desktop']['top'] . ' ' . $attr['borderRadius']['desktop']['right'] . ' ' . $attr['borderRadius']['desktop']['bottom'] . ' ' . $attr['borderRadius']['desktop']['left'] . ' ' : '0 0 0 0',
				'background'    => $bgType && $bgType === 'solid' ? $bgColor : $bgGradient,
				'box-shadow'    => isset( $attr['boxShadow'] ) && $attr['boxShadow']['enable'] ? AffiliateX_Helpers::get_css_boxshadow( $attr['boxShadow'] ) : 'none',
			),
			' .affx-pdt-table-wrapper td:not(.affx-img-col)'             => array(
				'padding-top'    => isset( $attr['padding']['desktop']['top'] ) ? $attr['padding']['desktop']['top'] : '24px',
				'padding-left'   => isset( $attr['padding']['desktop']['left'] ) ? $attr['padding']['desktop']['left'] : '24px',
				'padding-right'  => isset( $attr['padding']['desktop']['right'] ) ? $attr['padding']['desktop']['right'] : '24px',
				'padding-bottom' => isset( $attr['padding']['desktop']['bottom'] ) ? $attr['padding']['desktop']['bottom'] : '24px',
			),
			' .affx-pdt-table-wrapper th'             => array(
				'padding-top'    => isset( $attr['padding']['desktop']['top'] ) ? $attr['padding']['desktop']['top'] : '24px',
				'padding-left'   => isset( $attr['padding']['desktop']['left'] ) ? $attr['padding']['desktop']['left'] : '24px',
				'padding-right'  => isset( $attr['padding']['desktop']['right'] ) ? $attr['padding']['desktop']['right'] : '24px',
				'padding-bottom' => isset( $attr['padding']['desktop']['bottom'] ) ? $attr['padding']['desktop']['bottom'] : '24px',
			),
			' .affx-pdt-table-wrapper .affx-pdt-counter' => array(
				'color'           => isset( $attr['counterColor'] ) ? $attr['counterColor'] : '#FFFFFF',
				'background'      => isset( $attr['counterBgColor'] ) ? $attr['counterBgColor'] : '#24B644',
				'font-family'     => isset( $attr['counterTypography']['family'] ) ? $attr['counterTypography']['family'] : $global_font_family,
				'font-size'       => isset( $attr['counterTypography']['size']['desktop'] ) ? $attr['counterTypography']['size']['desktop'] : '18px',
				'line-height'     => isset( $attr['counterTypography']['line-height']['desktop'] ) ? $attr['counterTypography']['line-height']['desktop'] : '1.5',
				'text-transform'  => isset( $attr['counterTypography']['text-transform'] ) ? $attr['counterTypography']['text-transform'] : 'none',
				'text-decoration' => isset( $attr['counterTypography']['text-decoration'] ) ? $attr['counterTypography']['text-decoration'] : 'none',
				'letter-spacing'  => isset( $attr['counterTypography']['letter-spacing']['desktop'] ) ? $attr['counterTypography']['letter-spacing']['desktop'] : '0em',
				'font-weight'     => AffiliateX_Helpers::get_fontweight_variation( $counterVariation ),
				'font-style'      => AffiliateX_Helpers::get_font_style( $counterVariation ),
			),
			' .affx-pdt-table-wrapper .affx-pdt-ribbon' => array(
				'color'           => isset( $attr['ribbonColor'] ) ? $attr['ribbonColor'] : '#FFFFFF',
				'background'      => isset( $attr['ribbonBgColor'] ) ? $attr['ribbonBgColor'] : '#F13A3A',
				'font-family'     => isset( $attr['ribbonTypography']['family'] ) ? $attr['ribbonTypography']['family'] : $global_font_family,
				'font-size'       => isset( $attr['ribbonTypography']['size']['desktop'] ) ? $attr['ribbonTypography']['size']['desktop'] : '13px',
				'line-height'     => isset( $attr['ribbonTypography']['line-height']['desktop'] ) ? $attr['ribbonTypography']['line-height']['desktop'] : '1.5',
				'text-transform'  => isset( $attr['ribbonTypography']['text-transform'] ) ? $attr['ribbonTypography']['text-transform'] : 'none',
				'text-decoration' => isset( $attr['ribbonTypography']['text-decoration'] ) ? $attr['ribbonTypography']['text-decoration'] : 'none',
				'letter-spacing'  => isset( $attr['ribbonTypography']['letter-spacing']['desktop'] ) ? $attr['ribbonTypography']['letter-spacing']['desktop'] : '0em',
				'font-weight'     => AffiliateX_Helpers::get_fontweight_variation( $ribbonVariation ),
				'font-style'      => AffiliateX_Helpers::get_font_style( $ribbonVariation ),
			),
			' .affx-pdt-table-wrapper .affx-pdt-ribbon::before' => array(
				'background' => isset( $attr['ribbonBgColor'] ) ? $attr['ribbonBgColor'] : '#F13A3A',
			),
			' .affx-pdt-table-wrapper .affiliatex-button' => array(
				'font-family'     => isset( $attr['buttonTypography']['family'] ) ? $attr['buttonTypography']['family'] : $global_font_family,
				'font-size'       => isset( $attr['buttonTypography']['size']['desktop'] ) ? $attr['buttonTypography']['size']['desktop'] : '14px',
				'line-height'     => isset( $attr['buttonTypography']['line-height']['desktop'] ) ? $attr['buttonTypography']['line-height']['desktop'] : '1.65',
				'text-transform'  => isset( $attr['buttonTypography']['text-transform'] ) ? $attr['buttonTypography']['text-transform'] : 'none',
				'text-decoration' => isset( $attr['buttonTypography']['text-decoration'] ) ? $attr['buttonTypography']['text-decoration'] : 'none',
				'letter-spacing'  => isset( $attr['buttonTypography']['letter-spacing']['desktop'] ) ? $attr['buttonTypography']['letter-spacing']['desktop'] : '0em',
				'font-weight'     => AffiliateX_Helpers::get_fontweight_variation( $buttonVariation ),
				'font-style'      => AffiliateX_Helpers::get_font_style( $buttonVariation ),
			),
			' .affx-pdt-table-wrapper .affiliatex-button.primary' => array(
				'color'            => isset( $attr['button1TextColor'] ) ? $attr['button1TextColor'] : '#FFFFFF',
				'background-color' => isset( $attr['button1BgColor'] ) ? $attr['button1BgColor'] : $global_btn_color,
				'margin-top'       => isset( $attr['button1Margin']['desktop']['top'] ) ? $attr['button1Margin']['desktop']['top'] : '5px',
				'margin-left'      => isset( $attr['button1Margin']['desktop']['left'] ) ? $attr['button1Margin']['desktop']['left'] : '0px',
				'margin-right'     => isset( $attr['button1Margin']['desktop']['right'] ) ? $attr['button1Margin']['desktop']['right'] : '0px',
				'margin-bottom'    => isset( $attr['button1Margin']['desktop']['bottom'] ) ? $attr['button1Margin']['desktop']['bottom'] : '5px',
				'padding-top'      => isset( $attr['button1Padding']['desktop']['top'] ) ? $attr['button1Padding']['desktop']['top'] : '10px',
				'padding-left'     => isset( $attr['button1Padding']['desktop']['left'] ) ? $attr['button1Padding']['desktop']['left'] : '5px',
				'padding-right'    => isset( $attr['button1Padding']['desktop']['right'] ) ? $attr['button1Padding']['desktop']['right'] : '5px',
				'padding-bottom'   => isset( $attr['button1Padding']['desktop']['bottom'] ) ? $attr['button1Padding']['desktop']['bottom'] : '10px',
			),
			' .affx-pdt-table-wrapper .affiliatex-button.secondary' => array(
				'color'            => isset( $attr['button2TextColor'] ) ? $attr['button2TextColor'] : '#FFFFFF',
				'background-color' => isset( $attr['button2BgColor'] ) ? $attr['button2BgColor'] : '#FFB800',
				'margin-top'       => isset( $attr['button2Margin']['desktop']['top'] ) ? $attr['button2Margin']['desktop']['top'] : '5px',
				'margin-left'      => isset( $attr['button2Margin']['desktop']['left'] ) ? $attr['button2Margin']['desktop']['left'] : '0px',
				'margin-right'     => isset( $attr['button2Margin']['desktop']['right'] ) ? $attr['button2Margin']['desktop']['right'] : '0px',
				'margin-bottom'    => isset( $attr['button2Margin']['desktop']['bottom'] ) ? $attr['button2Margin']['desktop']['bottom'] : '5px',
				'padding-top'      => isset( $attr['button2Padding']['desktop']['top'] ) ? $attr['button2Padding']['desktop']['top'] : '10px',
				'padding-left'     => isset( $attr['button2Padding']['desktop']['left'] ) ? $attr['button2Padding']['desktop']['left'] : '5px',
				'padding-right'    => isset( $attr['button2Padding']['desktop']['right'] ) ? $attr['button2Padding']['desktop']['right'] : '5px',
				'padding-bottom'   => isset( $attr['button2Padding']['desktop']['bottom'] ) ? $attr['button2Padding']['desktop']['bottom'] : '10px',
			),
			' .affx-pdt-table-wrapper .affiliatex-button.primary:hover' => array(
				'color'            => isset( $attr['button1TextHoverColor'] ) ? $attr['button1TextHoverColor'] : '#FFFFFF',
				'background-color' => isset( $attr['button1BgHoverColor'] ) ? $attr['button1BgHoverColor'] : $global_btn_hover_color,
			),
			' .affx-pdt-table-wrapper .affiliatex-button.secondary:hover' => array(
				'color'            => isset( $attr['button2TextHoverColor'] ) ? $attr['button2TextHoverColor'] : '#FFFFFF',
				'background-color' => isset( $attr['button2BgHoverColor'] ) ? $attr['button2BgHoverColor'] : '#084ACA',
			),
			' .affx-pdt-table-wrapper .affx-pdt-price-wrap' => array(
				'color'           => isset( $attr['priceColor'] ) ? $attr['priceColor'] : '#262B33',
				'font-family'     => isset( $attr['priceTypography']['family'] ) ? $attr['priceTypography']['family'] : $global_font_family,
				'font-size'       => isset( $attr['priceTypography']['size']['desktop'] ) ? $attr['priceTypography']['size']['desktop'] : '22px',
				'line-height'     => isset( $attr['priceTypography']['line-height']['desktop'] ) ? $attr['priceTypography']['line-height']['desktop'] : '1.65',
				'text-transform'  => isset( $attr['priceTypography']['text-transform'] ) ? $attr['priceTypography']['text-transform'] : 'none',
				'text-decoration' => isset( $attr['priceTypography']['text-decoration'] ) ? $attr['priceTypography']['text-decoration'] : 'none',
				'letter-spacing'  => isset( $attr['priceTypography']['letter-spacing']['desktop'] ) ? $attr['priceTypography']['letter-spacing']['desktop'] : '0em',
				'font-weight'     => AffiliateX_Helpers::get_fontweight_variation( $priceVariation ),
				'font-style'      => AffiliateX_Helpers::get_font_style( $priceVariation ),
			),
			' .affx-pdt-table-wrapper .affx-pdt-table thead td' => array(
				'background'      => isset( $attr['tableHeaderBgColor'] ) ? $attr['tableHeaderBgColor'] : '#084ACA',
				'border-color'    => isset( $attr['tableHeaderBgColor'] ) ? $attr['tableHeaderBgColor'] : '#084ACA',
				'color'           => isset( $attr['tableHeaderColor'] ) ? $attr['tableHeaderColor'] : '#FFFFFF',
				'font-family'     => isset( $attr['headerTypography']['family'] ) ? $attr['headerTypography']['family'] : $global_font_family,
				'font-size'       => isset( $attr['headerTypography']['size']['desktop'] ) ? $attr['headerTypography']['size']['desktop'] : '18px',
				'line-height'     => isset( $attr['headerTypography']['line-height']['desktop'] ) ? $attr['headerTypography']['line-height']['desktop'] : '1.65',
				'text-transform'  => isset( $attr['headerTypography']['text-transform'] ) ? $attr['headerTypography']['text-transform'] : 'none',
				'text-decoration' => isset( $attr['headerTypography']['text-decoration'] ) ? $attr['headerTypography']['text-decoration'] : 'none',
				'letter-spacing'  => isset( $attr['headerTypography']['letter-spacing']['desktop'] ) ? $attr['headerTypography']['letter-spacing']['desktop'] : '0em',
				'font-weight'     => AffiliateX_Helpers::get_fontweight_variation( $headerVariation ),
				'font-style'      => AffiliateX_Helpers::get_font_style( $headerVariation ),
			),
			' .affx-pdt-table-wrapper .affiliatex-icon li:before' => array(
				'color' => isset( $attr['productIconColor'] ) ? $attr['productIconColor'] : '#24B644',
			),
			' .affx-pdt-table-wrapper .affx-pdt-img-container' => array(
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
			' .affx-pdt-table-wrapper'                => array(
				'font-size'      => isset( $attr['contentTypography']['size']['mobile'] ) ? $attr['contentTypography']['size']['mobile'] : '16px',
				'line-height'    => isset( $attr['contentTypography']['line-height']['mobile'] ) ? $attr['contentTypography']['line-height']['mobile'] : '1.65',
				'letter-spacing' => isset( $attr['contentTypography']['letter-spacing']['mobile'] ) ? $attr['contentTypography']['letter-spacing']['mobile'] : '0em',
				'margin-top'     => isset( $attr['margin']['mobile']['top'] ) ? $attr['margin']['mobile']['top'] : '0px',
				'margin-left'    => isset( $attr['margin']['mobile']['left'] ) ? $attr['margin']['mobile']['left'] : '0px',
				'margin-right'   => isset( $attr['margin']['mobile']['right'] ) ? $attr['margin']['mobile']['right'] : '0px',
				'margin-bottom'  => isset( $attr['margin']['mobile']['bottom'] ) ? $attr['margin']['mobile']['bottom'] : '30px',
			),
			' .star-rating-single-wrap'               => array(
				'font-size'      => isset( $attr['ratingTypography']['size']['mobile'] ) ? $attr['ratingTypography']['size']['mobile'] : '13px',
				'line-height'    => isset( $attr['ratingTypography']['line-height']['mobile'] ) ? $attr['ratingTypography']['line-height']['mobile'] : '1.5',
				'letter-spacing' => isset( $attr['ratingTypography']['letter-spacing']['mobile'] ) ? $attr['ratingTypography']['letter-spacing']['mobile'] : '0em',
			),
			' .affx-circle-progress-container .affx-circle-inside' => array(
				'font-size'      => isset( $attr['rating2Typography']['size']['mobile'] ) ? $attr['rating2Typography']['size']['mobile'] : '24px',
				'line-height'    => isset( $attr['rating2Typography']['line-height']['mobile'] ) ? $attr['rating2Typography']['line-height']['mobile'] : '1.5',
				'letter-spacing' => isset( $attr['rating2Typography']['letter-spacing']['mobile'] ) ? $attr['rating2Typography']['letter-spacing']['mobile'] : '0em',
			),
			' .affx-pdt-table-wrapper p'              => array(
				'font-size'      => isset( $attr['contentTypography']['size']['mobile'] ) ? $attr['contentTypography']['size']['mobile'] : '16px',
				'line-height'    => isset( $attr['contentTypography']['line-height']['mobile'] ) ? $attr['contentTypography']['line-height']['mobile'] : '1.65',
				'letter-spacing' => isset( $attr['contentTypography']['letter-spacing']['mobile'] ) ? $attr['contentTypography']['letter-spacing']['mobile'] : '0em',
			),
			' .affx-pdt-table-wrapper li'             => array(
				'font-size'      => isset( $attr['contentTypography']['size']['mobile'] ) ? $attr['contentTypography']['size']['mobile'] : '16px',
				'line-height'    => isset( $attr['contentTypography']['line-height']['mobile'] ) ? $attr['contentTypography']['line-height']['mobile'] : '1.65',
				'letter-spacing' => isset( $attr['contentTypography']['letter-spacing']['mobile'] ) ? $attr['contentTypography']['letter-spacing']['mobile'] : '0em',
			),
			' .affx-pdt-table-wrapper .affx-pdt-name' => array(
				'font-size'      => isset( $attr['titleTypography']['size']['mobile'] ) ? $attr['titleTypography']['size']['mobile'] : '22px',
				'line-height'    => isset( $attr['titleTypography']['line-height']['mobile'] ) ? $attr['titleTypography']['line-height']['mobile'] : '1.65',
				'letter-spacing' => isset( $attr['titleTypography']['letter-spacing']['mobile'] ) ? $attr['titleTypography']['letter-spacing']['mobile'] : '0em',
			),
			' .affx-pdt-table-wrapper:not(.layout-3)' => array(
				'border-width' => isset( $attr['borderWidth']['mobile']['top'] ) && isset( $attr['borderWidth']['mobile']['right'] ) && isset( $attr['borderWidth']['mobile']['bottom'] ) && isset( $attr['borderWidth']['mobile']['left'] ) ? $attr['borderWidth']['mobile']['top'] . ' ' . $attr['borderWidth']['mobile']['right'] . ' ' . $attr['borderWidth']['mobile']['bottom'] . ' ' . $attr['borderWidth']['mobile']['left'] . ' ' : '1px 1px 1px 1px',
			),
			' .affx-pdt-table-single'                 => array(
				'margin-top'    => isset( $attr['margin']['mobile']['top'] ) ? $attr['margin']['mobile']['top'] : '0px',
				'margin-left'   => isset( $attr['margin']['mobile']['left'] ) ? $attr['margin']['mobile']['left'] : '0px',
				'margin-right'  => isset( $attr['margin']['mobile']['right'] ) ? $attr['margin']['mobile']['right'] : '0px',
				'margin-bottom' => isset( $attr['margin']['mobile']['bottom'] ) ? $attr['margin']['mobile']['bottom'] : '30px',
				'border-width'  => isset( $attr['borderWidth']['mobile']['top'] ) && isset( $attr['borderWidth']['mobile']['right'] ) && isset( $attr['borderWidth']['mobile']['bottom'] ) && isset( $attr['borderWidth']['mobile']['left'] ) ? $attr['borderWidth']['mobile']['top'] . ' ' . $attr['borderWidth']['mobile']['right'] . ' ' . $attr['borderWidth']['mobile']['bottom'] . ' ' . $attr['borderWidth']['mobile']['left'] . ' ' : '1px 1px 1px 1px',
				'border-radius' => isset( $attr['borderRadius']['mobile']['top'] ) && isset( $attr['borderRadius']['mobile']['right'] ) && isset( $attr['borderRadius']['mobile']['bottom'] ) && isset( $attr['borderRadius']['mobile']['left'] ) ? $attr['borderRadius']['mobile']['top'] . ' ' . $attr['borderRadius']['mobile']['right'] . ' ' . $attr['borderRadius']['mobile']['bottom'] . ' ' . $attr['borderRadius']['mobile']['left'] . ' ' : '0 0 0 0',
			),
			' .affx-pdt-table-wrapper td'             => array(
				'padding-top'    => isset( $attr['padding']['mobile']['top'] ) ? $attr['padding']['mobile']['top'] : '16px',
				'padding-left'   => isset( $attr['padding']['mobile']['left'] ) ? $attr['padding']['mobile']['left'] : '16px',
				'padding-right'  => isset( $attr['padding']['mobile']['right'] ) ? $attr['padding']['mobile']['right'] : '16px',
				'padding-bottom' => isset( $attr['padding']['mobile']['bottom'] ) ? $attr['padding']['mobile']['bottom'] : '16px',
			),
			' .affx-pdt-table-wrapper th'             => array(
				'padding-top'    => isset( $attr['padding']['mobile']['top'] ) ? $attr['padding']['mobile']['top'] : '16px',
				'padding-left'   => isset( $attr['padding']['mobile']['left'] ) ? $attr['padding']['mobile']['left'] : '16px',
				'padding-right'  => isset( $attr['padding']['mobile']['right'] ) ? $attr['padding']['mobile']['right'] : '16px',
				'padding-bottom' => isset( $attr['padding']['mobile']['bottom'] ) ? $attr['padding']['mobile']['bottom'] : '16px',
			),
			' .affx-pdt-table-wrapper .affx-pdt-counter' => array(
				'font-size'      => isset( $attr['counterTypography']['size']['mobile'] ) ? $attr['counterTypography']['size']['mobile'] : '18px',
				'line-height'    => isset( $attr['counterTypography']['line-height']['mobile'] ) ? $attr['counterTypography']['line-height']['mobile'] : '1.5',
				'letter-spacing' => isset( $attr['counterTypography']['letter-spacing']['mobile'] ) ? $attr['counterTypography']['letter-spacing']['mobile'] : '0em',
			),
			' .affx-pdt-table-wrapper .affx-pdt-ribbon' => array(
				'font-size'      => isset( $attr['ribbonTypography']['size']['mobile'] ) ? $attr['ribbonTypography']['size']['mobile'] : '13px',
				'line-height'    => isset( $attr['ribbonTypography']['line-height']['mobile'] ) ? $attr['ribbonTypography']['line-height']['mobile'] : '1.5',
				'letter-spacing' => isset( $attr['ribbonTypography']['letter-spacing']['mobile'] ) ? $attr['ribbonTypography']['letter-spacing']['mobile'] : '0em',
			),
			' .affx-pdt-table-wrapper .affiliatex-button' => array(
				'font-size'      => isset( $attr['buttonTypography']['size']['mobile'] ) ? $attr['buttonTypography']['size']['mobile'] : '14px',
				'line-height'    => isset( $attr['buttonTypography']['line-height']['mobile'] ) ? $attr['buttonTypography']['line-height']['mobile'] : '1.65',
				'letter-spacing' => isset( $attr['buttonTypography']['letter-spacing']['mobile'] ) ? $attr['buttonTypography']['letter-spacing']['mobile'] : '0em',
			),
			' .affx-pdt-table-wrapper .affiliatex-button.primary' => array(
				'margin-top'     => isset( $attr['button1Margin']['mobile']['top'] ) ? $attr['button1Margin']['mobile']['top'] : '5px',
				'margin-left'    => isset( $attr['button1Margin']['mobile']['left'] ) ? $attr['button1Margin']['mobile']['left'] : '0px',
				'margin-right'   => isset( $attr['button1Margin']['mobile']['right'] ) ? $attr['button1Margin']['mobile']['right'] : '0px',
				'margin-bottom'  => isset( $attr['button1Margin']['mobile']['bottom'] ) ? $attr['button1Margin']['mobile']['bottom'] : '5px',
				'padding-top'    => isset( $attr['button1Padding']['mobile']['top'] ) ? $attr['button1Padding']['mobile']['top'] : '10px',
				'padding-left'   => isset( $attr['button1Padding']['mobile']['left'] ) ? $attr['button1Padding']['mobile']['left'] : '5px',
				'padding-right'  => isset( $attr['button1Padding']['mobile']['right'] ) ? $attr['button1Padding']['mobile']['right'] : '5px',
				'padding-bottom' => isset( $attr['button1Padding']['mobile']['bottom'] ) ? $attr['button1Padding']['mobile']['bottom'] : '10px',
			),
			' .affx-pdt-table-wrapper .affiliatex-button.secondary' => array(
				'margin-top'     => isset( $attr['button2Margin']['mobile']['top'] ) ? $attr['button2Margin']['mobile']['top'] : '5px',
				'margin-left'    => isset( $attr['button2Margin']['mobile']['left'] ) ? $attr['button2Margin']['mobile']['left'] : '0px',
				'margin-right'   => isset( $attr['button2Margin']['mobile']['right'] ) ? $attr['button2Margin']['mobile']['right'] : '0px',
				'margin-bottom'  => isset( $attr['button2Margin']['mobile']['bottom'] ) ? $attr['button2Margin']['mobile']['bottom'] : '5px',
				'padding-top'    => isset( $attr['button2Padding']['mobile']['top'] ) ? $attr['button2Padding']['mobile']['top'] : '10px',
				'padding-left'   => isset( $attr['button2Padding']['mobile']['left'] ) ? $attr['button2Padding']['mobile']['left'] : '5px',
				'padding-right'  => isset( $attr['button2Padding']['mobile']['right'] ) ? $attr['button2Padding']['mobile']['right'] : '5px',
				'padding-bottom' => isset( $attr['button2Padding']['mobile']['bottom'] ) ? $attr['button2Padding']['mobile']['bottom'] : '10px',
			),
			' .affx-pdt-table-wrapper .affx-pdt-price-wrap' => array(
				'font-size'      => isset( $attr['priceTypography']['size']['mobile'] ) ? $attr['priceTypography']['size']['mobile'] : '22px',
				'line-height'    => isset( $attr['priceTypography']['line-height']['mobile'] ) ? $attr['priceTypography']['line-height']['mobile'] : '1.65',
				'letter-spacing' => isset( $attr['priceTypography']['letter-spacing']['mobile'] ) ? $attr['priceTypography']['letter-spacing']['mobile'] : '0em',
			),
			' .affx-pdt-table-wrapper .affx-pdt-table thead td' => array(
				'font-size'      => isset( $attr['headerTypography']['size']['mobile'] ) ? $attr['headerTypography']['size']['mobile'] : '18px',
				'line-height'    => isset( $attr['headerTypography']['line-height']['mobile'] ) ? $attr['headerTypography']['line-height']['mobile'] : '1.65',
				'letter-spacing' => isset( $attr['headerTypography']['letter-spacing']['mobile'] ) ? $attr['headerTypography']['letter-spacing']['mobile'] : '0em',
			),
			' .affx-pdt-table-wrapper .affx-pdt-img-wrapper' => array(
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
			' .affx-pdt-table-wrapper'                => array(
				'font-size'      => isset( $attr['contentTypography']['size']['tablet'] ) ? $attr['contentTypography']['size']['tablet'] : '16px',
				'line-height'    => isset( $attr['contentTypography']['line-height']['tablet'] ) ? $attr['contentTypography']['line-height']['tablet'] : '1.65',
				'letter-spacing' => isset( $attr['contentTypography']['letter-spacing']['tablet'] ) ? $attr['contentTypography']['letter-spacing']['tablet'] : '0em',
				'margin-top'     => isset( $attr['margin']['tablet']['top'] ) ? $attr['margin']['tablet']['top'] : '0px',
				'margin-left'    => isset( $attr['margin']['tablet']['left'] ) ? $attr['margin']['tablet']['left'] : '0px',
				'margin-right'   => isset( $attr['margin']['tablet']['right'] ) ? $attr['margin']['tablet']['right'] : '0px',
				'margin-bottom'  => isset( $attr['margin']['tablet']['bottom'] ) ? $attr['margin']['tablet']['bottom'] : '30px',
			),
			' .star-rating-single-wrap'               => array(
				'font-size'      => isset( $attr['ratingTypography']['size']['tablet'] ) ? $attr['ratingTypography']['size']['tablet'] : '13px',
				'line-height'    => isset( $attr['ratingTypography']['line-height']['tablet'] ) ? $attr['ratingTypography']['line-height']['tablet'] : '1.5',
				'letter-spacing' => isset( $attr['ratingTypography']['letter-spacing']['tablet'] ) ? $attr['ratingTypography']['letter-spacing']['tablet'] : '0em',
			),
			' .affx-circle-progress-container .affx-circle-inside' => array(
				'font-size'      => isset( $attr['rating2Typography']['size']['tablet'] ) ? $attr['rating2Typography']['size']['tablet'] : '24px',
				'line-height'    => isset( $attr['rating2Typography']['line-height']['tablet'] ) ? $attr['rating2Typography']['line-height']['tablet'] : '1.5',
				'letter-spacing' => isset( $attr['rating2Typography']['letter-spacing']['tablet'] ) ? $attr['rating2Typography']['letter-spacing']['tablet'] : '0em',
			),
			' .affx-pdt-table-wrapper p'              => array(
				'font-size'      => isset( $attr['contentTypography']['size']['tablet'] ) ? $attr['contentTypography']['size']['tablet'] : '16px',
				'line-height'    => isset( $attr['contentTypography']['line-height']['tablet'] ) ? $attr['contentTypography']['line-height']['tablet'] : '1.65',
				'letter-spacing' => isset( $attr['contentTypography']['letter-spacing']['tablet'] ) ? $attr['contentTypography']['letter-spacing']['tablet'] : '0em',
			),
			' .affx-pdt-table-wrapper li'             => array(
				'font-size'      => isset( $attr['contentTypography']['size']['tablet'] ) ? $attr['contentTypography']['size']['tablet'] : '16px',
				'line-height'    => isset( $attr['contentTypography']['line-height']['tablet'] ) ? $attr['contentTypography']['line-height']['tablet'] : '1.65',
				'letter-spacing' => isset( $attr['contentTypography']['letter-spacing']['tablet'] ) ? $attr['contentTypography']['letter-spacing']['tablet'] : '0em',
			),
			' .affx-pdt-table-wrapper .affx-pdt-name' => array(
				'font-size'      => isset( $attr['titleTypography']['size']['tablet'] ) ? $attr['titleTypography']['size']['tablet'] : '22px',
				'line-height'    => isset( $attr['titleTypography']['line-height']['tablet'] ) ? $attr['titleTypography']['line-height']['tablet'] : '1.65',
				'letter-spacing' => isset( $attr['titleTypography']['letter-spacing']['tablet'] ) ? $attr['titleTypography']['letter-spacing']['tablet'] : '0em',
			),
			' .affx-pdt-table-wrapper:not(.layout-3)' => array(
				'border-width' => isset( $attr['borderWidth']['tablet']['top'] ) && isset( $attr['borderWidth']['tablet']['right'] ) && isset( $attr['borderWidth']['tablet']['bottom'] ) && isset( $attr['borderWidth']['tablet']['left'] ) ? $attr['borderWidth']['tablet']['top'] . ' ' . $attr['borderWidth']['tablet']['right'] . ' ' . $attr['borderWidth']['tablet']['bottom'] . ' ' . $attr['borderWidth']['tablet']['left'] . ' ' : '1px 1px 1px 1px',
			),
			' .affx-pdt-table-single'                 => array(
				'margin-top'    => isset( $attr['margin']['tablet']['top'] ) ? $attr['margin']['tablet']['top'] : '0px',
				'margin-left'   => isset( $attr['margin']['tablet']['left'] ) ? $attr['margin']['tablet']['left'] : '0px',
				'margin-right'  => isset( $attr['margin']['tablet']['right'] ) ? $attr['margin']['tablet']['right'] : '0px',
				'margin-bottom' => isset( $attr['margin']['tablet']['bottom'] ) ? $attr['margin']['tablet']['bottom'] : '30px',
				'border-width'  => isset( $attr['borderWidth']['tablet']['top'] ) && isset( $attr['borderWidth']['tablet']['right'] ) && isset( $attr['borderWidth']['tablet']['bottom'] ) && isset( $attr['borderWidth']['tablet']['left'] ) ? $attr['borderWidth']['tablet']['top'] . ' ' . $attr['borderWidth']['tablet']['right'] . ' ' . $attr['borderWidth']['tablet']['bottom'] . ' ' . $attr['borderWidth']['tablet']['left'] . ' ' : '1px 1px 1px 1px',
				'border-radius' => isset( $attr['borderRadius']['tablet']['top'] ) && isset( $attr['borderRadius']['tablet']['right'] ) && isset( $attr['borderRadius']['tablet']['bottom'] ) && isset( $attr['borderRadius']['tablet']['left'] ) ? $attr['borderRadius']['tablet']['top'] . ' ' . $attr['borderRadius']['tablet']['right'] . ' ' . $attr['borderRadius']['tablet']['bottom'] . ' ' . $attr['borderRadius']['tablet']['left'] . ' ' : '0 0 0 0',
			),
			' .affx-pdt-table-wrapper td'             => array(
				'padding-top'    => isset( $attr['padding']['tablet']['top'] ) ? $attr['padding']['tablet']['top'] : '16px',
				'padding-left'   => isset( $attr['padding']['tablet']['left'] ) ? $attr['padding']['tablet']['left'] : '16px',
				'padding-right'  => isset( $attr['padding']['tablet']['right'] ) ? $attr['padding']['tablet']['right'] : '16px',
				'padding-bottom' => isset( $attr['padding']['tablet']['bottom'] ) ? $attr['padding']['tablet']['bottom'] : '16px',
			),
			' .affx-pdt-table-wrapper th'             => array(
				'padding-top'    => isset( $attr['padding']['tablet']['top'] ) ? $attr['padding']['tablet']['top'] : '16px',
				'padding-left'   => isset( $attr['padding']['tablet']['left'] ) ? $attr['padding']['tablet']['left'] : '16px',
				'padding-right'  => isset( $attr['padding']['tablet']['right'] ) ? $attr['padding']['tablet']['right'] : '16px',
				'padding-bottom' => isset( $attr['padding']['tablet']['bottom'] ) ? $attr['padding']['tablet']['bottom'] : '16px',
			),
			' .affx-pdt-table-wrapper .affx-pdt-counter' => array(
				'font-size'      => isset( $attr['counterTypography']['size']['tablet'] ) ? $attr['counterTypography']['size']['tablet'] : '18px',
				'line-height'    => isset( $attr['counterTypography']['line-height']['tablet'] ) ? $attr['counterTypography']['line-height']['tablet'] : '1.5',
				'letter-spacing' => isset( $attr['counterTypography']['letter-spacing']['tablet'] ) ? $attr['counterTypography']['letter-spacing']['tablet'] : '0em',
			),
			' .affx-pdt-table-wrapper .affx-pdt-ribbon' => array(
				'font-size'      => isset( $attr['ribbonTypography']['size']['tablet'] ) ? $attr['ribbonTypography']['size']['tablet'] : '13px',
				'line-height'    => isset( $attr['ribbonTypography']['line-height']['tablet'] ) ? $attr['ribbonTypography']['line-height']['tablet'] : '1.5',
				'letter-spacing' => isset( $attr['ribbonTypography']['letter-spacing']['tablet'] ) ? $attr['ribbonTypography']['letter-spacing']['tablet'] : '0em',
			),
			' .affx-pdt-table-wrapper .affiliatex-button' => array(
				'font-size'      => isset( $attr['buttonTypography']['size']['tablet'] ) ? $attr['buttonTypography']['size']['tablet'] : '14px',
				'line-height'    => isset( $attr['buttonTypography']['line-height']['tablet'] ) ? $attr['buttonTypography']['line-height']['tablet'] : '1.65',
				'letter-spacing' => isset( $attr['buttonTypography']['letter-spacing']['tablet'] ) ? $attr['buttonTypography']['letter-spacing']['tablet'] : '0em',
			),
			' .affx-pdt-table-wrapper .affiliatex-button.primary' => array(
				'margin-top'     => isset( $attr['button1Margin']['tablet']['top'] ) ? $attr['button1Margin']['tablet']['top'] : '5px',
				'margin-left'    => isset( $attr['button1Margin']['tablet']['left'] ) ? $attr['button1Margin']['tablet']['left'] : '0px',
				'margin-right'   => isset( $attr['button1Margin']['tablet']['right'] ) ? $attr['button1Margin']['tablet']['right'] : '0px',
				'margin-bottom'  => isset( $attr['button1Margin']['tablet']['bottom'] ) ? $attr['button1Margin']['tablet']['bottom'] : '5px',
				'padding-top'    => isset( $attr['button1Padding']['tablet']['top'] ) ? $attr['button1Padding']['tablet']['top'] : '10px',
				'padding-left'   => isset( $attr['button1Padding']['tablet']['left'] ) ? $attr['button1Padding']['tablet']['left'] : '5px',
				'padding-right'  => isset( $attr['button1Padding']['tablet']['right'] ) ? $attr['button1Padding']['tablet']['right'] : '5px',
				'padding-bottom' => isset( $attr['button1Padding']['tablet']['bottom'] ) ? $attr['button1Padding']['tablet']['bottom'] : '10px',
			),
			' .affx-pdt-table-wrapper .affiliatex-button.secondary' => array(
				'margin-top'     => isset( $attr['button2Margin']['tablet']['top'] ) ? $attr['button2Margin']['tablet']['top'] : '5px',
				'margin-left'    => isset( $attr['button2Margin']['tablet']['left'] ) ? $attr['button2Margin']['tablet']['left'] : '0px',
				'margin-right'   => isset( $attr['button2Margin']['tablet']['right'] ) ? $attr['button2Margin']['tablet']['right'] : '0px',
				'margin-bottom'  => isset( $attr['button2Margin']['tablet']['bottom'] ) ? $attr['button2Margin']['tablet']['bottom'] : '5px',
				'padding-top'    => isset( $attr['button2Padding']['tablet']['top'] ) ? $attr['button2Padding']['tablet']['top'] : '10px',
				'padding-left'   => isset( $attr['button2Padding']['tablet']['left'] ) ? $attr['button2Padding']['tablet']['left'] : '5px',
				'padding-right'  => isset( $attr['button2Padding']['tablet']['right'] ) ? $attr['button2Padding']['tablet']['right'] : '5px',
				'padding-bottom' => isset( $attr['button2Padding']['tablet']['bottom'] ) ? $attr['button2Padding']['tablet']['bottom'] : '10px',
			),
			' .affx-pdt-table-wrapper .affx-pdt-price-wrap' => array(
				'font-size'      => isset( $attr['priceTypography']['size']['tablet'] ) ? $attr['priceTypography']['size']['tablet'] : '22px',
				'line-height'    => isset( $attr['priceTypography']['line-height']['tablet'] ) ? $attr['priceTypography']['line-height']['tablet'] : '1.65',
				'letter-spacing' => isset( $attr['priceTypography']['letter-spacing']['tablet'] ) ? $attr['priceTypography']['letter-spacing']['tablet'] : '0em',
			),
			' .affx-pdt-table-wrapper .affx-pdt-table thead td' => array(
				'font-size'      => isset( $attr['headerTypography']['size']['tablet'] ) ? $attr['headerTypography']['size']['tablet'] : '18px',
				'line-height'    => isset( $attr['headerTypography']['line-height']['tablet'] ) ? $attr['headerTypography']['line-height']['tablet'] : '1.65',
				'letter-spacing' => isset( $attr['headerTypography']['letter-spacing']['tablet'] ) ? $attr['headerTypography']['letter-spacing']['tablet'] : '0em',
			),
			' .affx-pdt-table-wrapper .affx-pdt-img-wrapper' => array(
				'padding-top'      => isset( $attr['imagePadding']['tablet']['top'] ) ? $attr['imagePadding']['tablet']['top'] : '0px',
				'padding-left'     => isset( $attr['imagePadding']['tablet']['left'] ) ? $attr['imagePadding']['tablet']['left'] : '0px',
				'padding-right'    => isset( $attr['imagePadding']['tablet']['right'] ) ? $attr['imagePadding']['tablet']['right'] : '0px',
				'padding-bottom'   => isset( $attr['imagePadding']['tablet']['bottom'] ) ? $attr['imagePadding']['tablet']['bottom'] : '0px',
			),
		);

		return $tablet_selectors;
	}

}
