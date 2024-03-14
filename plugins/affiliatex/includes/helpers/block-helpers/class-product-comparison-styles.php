<?php
/**
 * Product Comparison Block Styles
 *
 * @package AffiliateX
 */

class AffiliateX_Product_Comparison_Styles {

	public static function block_fonts( $attr ) {
		return array(
			'titleTypography'   => isset( $attr['titleTypography'] ) ? $attr['titleTypography'] : array(),
			'ribbonTypography'  => isset( $attr['ribbonTypography'] ) ? $attr['ribbonTypography'] : array(),
			'priceTypography'   => isset( $attr['priceTypography'] ) ? $attr['priceTypography'] : array(),
			'buttonTypography'  => isset( $attr['buttonTypography'] ) ? $attr['buttonTypography'] : array(),
			'contentTypography' => isset( $attr['contentTypography'] ) ? $attr['contentTypography'] : array(),
		);
	}

	public static function block_css( $attr, $id ) {
		$selectors = self::get_selectors( $attr );

		$m_selectors = self::get_mobileselectors( $attr );

		$t_selectors = self::get_tabletselectors( $attr );

		$desktop = AffiliateX_Helpers::generate_css( $selectors, '#affiliatex-product-comparison-blocks-style-' . $id );

		$tablet = AffiliateX_Helpers::generate_css( $t_selectors, '#affiliatex-product-comparison-blocks-style-' . $id );

		$mobile = AffiliateX_Helpers::generate_css( $m_selectors, '#affiliatex-product-comparison-blocks-style-' . $id );

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
		$ribbon_variation       = isset( $attr['ribbonTypography']['variation'] ) ? $attr['ribbonTypography']['variation'] : 'n4';
		$button_variation       = isset( $attr['buttonTypography']['variation'] ) ? $attr['buttonTypography']['variation'] : 'n4';
		$price_variation        = isset( $attr['priceTypography']['variation'] ) ? $attr['priceTypography']['variation'] : 'n4';
		$bgGradient             = isset( $attr['bgColorGradient']['gradient'] ) ? $attr['bgColorGradient']['gradient'] : '';
		$bgColor                = isset( $attr['bgColorSolid'] ) ? $attr['bgColorSolid'] : '#FFFFFF';
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
			' .affx-product-comparison-block-container'   => array(
				'border-width'  => isset( $attr['borderWidth']['desktop']['top'] ) && isset( $attr['borderWidth']['desktop']['right'] ) && isset( $attr['borderWidth']['desktop']['bottom'] ) && isset( $attr['borderWidth']['desktop']['left'] ) ? $attr['borderWidth']['desktop']['top'] . ' ' . $attr['borderWidth']['desktop']['right'] . ' ' . $attr['borderWidth']['desktop']['bottom'] . ' ' . $attr['borderWidth']['desktop']['left'] . ' ' : '1px 1px 1px 1px',
				'border-radius' => isset( $attr['borderRadius']['desktop']['top'] ) && isset( $attr['borderRadius']['desktop']['right'] ) && isset( $attr['borderRadius']['desktop']['bottom'] ) && isset( $attr['borderRadius']['desktop']['left'] ) ? $attr['borderRadius']['desktop']['top'] . ' ' . $attr['borderRadius']['desktop']['right'] . ' ' . $attr['borderRadius']['desktop']['bottom'] . ' ' . $attr['borderRadius']['desktop']['left'] . ' ' : '0px 0px 0px 0px',
				'border-style'  => isset( $attr['border']['style'] ) ? $attr['border']['style'] : 'solid',
				'border-color'  => isset( $attr['border']['color']['color'] ) ? $attr['border']['color']['color'] : '#E6ECF7',
				'background'    => isset( $attr['bgType'] ) && $attr['bgType'] === 'gradient' ? $bgGradient : $bgColor,
				'margin-top'    => isset( $attr['margin']['desktop']['top'] ) ? $attr['margin']['desktop']['top'] : '0px',
				'margin-left'   => isset( $attr['margin']['desktop']['left'] ) ? $attr['margin']['desktop']['left'] : '0px',
				'margin-right'  => isset( $attr['margin']['desktop']['right'] ) ? $attr['margin']['desktop']['right'] : '0px',
				'margin-bottom' => isset( $attr['margin']['desktop']['bottom'] ) ? $attr['margin']['desktop']['bottom'] : '30px',
				'box-shadow'    => isset( $attr['boxShadow'] ) && $attr['boxShadow']['enable'] ? AffiliateX_Helpers::get_css_boxshadow( $attr['boxShadow'] ) : AffiliateX_Helpers::get_css_boxshadow( $box_shadow ),
			),
			' .affx-product-versus-table'                 => array(
				'font-family'     => isset( $attr['contentTypography']['family'] ) ? $attr['contentTypography']['family'] : $global_font_family,
				'font-weight'     => AffiliateX_Helpers::get_fontweight_variation( $variation ),
				'font-style'      => AffiliateX_Helpers::get_font_style( $variation ),
				'font-size'       => isset( $attr['contentTypography']['size']['desktop'] ) ? $attr['contentTypography']['size']['desktop'] : '18px',
				'line-height'     => isset( $attr['contentTypography']['line-height']['desktop'] ) ? $attr['contentTypography']['line-height']['desktop'] : '1.65',
				'text-transform'  => isset( $attr['contentTypography']['text-transform'] ) ? $attr['contentTypography']['text-transform'] : 'none',
				'text-decoration' => isset( $attr['contentTypography']['text-decoration'] ) ? $attr['contentTypography']['text-decoration'] : 'none',
				'letter-spacing'  => isset( $attr['contentTypography']['letter-spacing']['desktop'] ) ? $attr['contentTypography']['letter-spacing']['desktop'] : '0em',
				'color'           => isset( $attr['contentColor'] ) ? $attr['contentColor'] : $global_font_color,
			),
			' .affx-comparison-title'                     => array(
				'font-family'     => isset( $attr['titleTypography']['family'] ) ? $attr['titleTypography']['family'] : $global_font_family,
				'font-weight'     => AffiliateX_Helpers::get_fontweight_variation( $title_variation ),
				'font-style'      => AffiliateX_Helpers::get_font_style( $title_variation ),
				'font-size'       => isset( $attr['titleTypography']['size']['desktop'] ) ? $attr['titleTypography']['size']['desktop'] : '18px',
				'line-height'     => isset( $attr['titleTypography']['line-height']['desktop'] ) ? $attr['titleTypography']['line-height']['desktop'] : '1.5',
				'text-transform'  => isset( $attr['titleTypography']['text-transform'] ) ? $attr['titleTypography']['text-transform'] : 'none',
				'text-decoration' => isset( $attr['titleTypography']['text-decoration'] ) ? $attr['titleTypography']['text-decoration'] : 'none',
				'letter-spacing'  => isset( $attr['titleTypography']['letter-spacing']['desktop'] ) ? $attr['titleTypography']['letter-spacing']['desktop'] : '0em',
				'color'           => isset( $attr['titleColor'] ) ? $attr['titleColor'] : '#262B33',
			),
			' .affx-versus-table-wrap tr:first-child th:first-child' => array(
				'border-top-left-radius' => isset( $attr['borderRadius']['desktop']['top'] ) ? $attr['borderRadius']['desktop']['top'] : '0px',
			),
			' .affx-versus-table-wrap tr:first-child th:last-child' => array(
				'border-top-right-radius' => isset( $attr['borderRadius']['desktop']['right'] ) ? $attr['borderRadius']['desktop']['right'] : '0px',
			),
			' .affx-versus-table-wrap tr:first-child th:last-child .affx-versus-product' => array(
				'border-top-right-radius' => isset( $attr['borderRadius']['desktop']['right'] ) ? $attr['borderRadius']['desktop']['right'] : '0px',
				'overflow'                => 'hidden',
			),
			' .affx-versus-table-wrap tr:last-child td:first-child' => array(
				'border-top-left-radius' => isset( $attr['borderRadius']['desktop']['left'] ) ? $attr['borderRadius']['desktop']['left'] : '0px',
			),
			' .affx-versus-table-wrap tr:last-child td:last-child' => array(
				'border-bottom-left-radius' => isset( $attr['borderRadius']['desktop']['buttom'] ) ? $attr['borderRadius']['desktop']['buttom'] : '0px',
			),
			' .affx-versus-table-wrap td'                 => array(
				'border-width'   => isset( $attr['borderWidth']['desktop']['top'] ) && isset( $attr['borderWidth']['desktop']['right'] ) && isset( $attr['borderWidth']['desktop']['bottom'] ) && isset( $attr['borderWidth']['desktop']['left'] ) ? $attr['borderWidth']['desktop']['top'] . ' ' . $attr['borderWidth']['desktop']['right'] . ' ' . $attr['borderWidth']['desktop']['bottom'] . ' ' . $attr['borderWidth']['desktop']['left'] . ' ' : '1px 1px 1px 1px',
				'border-style'   => isset( $attr['border']['style'] ) ? $attr['border']['style'] : 'solid',
				'border-color'   => isset( $attr['border']['color']['color'] ) ? $attr['border']['color']['color'] : '#E6ECF7',
				'padding-top'    => isset( $attr['padding']['desktop']['top'] ) ? $attr['padding']['desktop']['top'] : '24px',
				'padding-left'   => isset( $attr['padding']['desktop']['left'] ) ? $attr['padding']['desktop']['left'] : '24px',
				'padding-right'  => isset( $attr['padding']['desktop']['right'] ) ? $attr['padding']['desktop']['right'] : '24px',
				'padding-bottom' => isset( $attr['padding']['desktop']['bottom'] ) ? $attr['padding']['desktop']['bottom'] : '24px',
			),
			' .affx-versus-table-wrap th'                 => array(
				'border-width'   => isset( $attr['borderWidth']['desktop']['top'] ) && isset( $attr['borderWidth']['desktop']['right'] ) && isset( $attr['borderWidth']['desktop']['bottom'] ) && isset( $attr['borderWidth']['desktop']['left'] ) ? $attr['borderWidth']['desktop']['top'] . ' ' . $attr['borderWidth']['desktop']['right'] . ' ' . $attr['borderWidth']['desktop']['bottom'] . ' ' . $attr['borderWidth']['desktop']['left'] . ' ' : '1px 1px 1px 1px',
				'border-style'   => isset( $attr['border']['style'] ) ? $attr['border']['style'] : 'solid',
				'border-color'   => isset( $attr['border']['color']['color'] ) ? $attr['border']['color']['color'] : '#E6ECF7',
				'padding-top'    => isset( $attr['padding']['desktop']['top'] ) ? $attr['padding']['desktop']['top'] : '24px',
				'padding-left'   => isset( $attr['padding']['desktop']['left'] ) ? $attr['padding']['desktop']['left'] : '24px',
				'padding-right'  => isset( $attr['padding']['desktop']['right'] ) ? $attr['padding']['desktop']['right'] : '24px',
				'padding-bottom' => isset( $attr['padding']['desktop']['bottom'] ) ? $attr['padding']['desktop']['bottom'] : '24px',
			),
			' .affx-versus-table-wrap .affx-pc-ribbon'    => array(
				'font-family'     => isset( $attr['ribbonTypography']['family'] ) ? $attr['ribbonTypography']['family'] : $global_font_family,
				'font-weight'     => AffiliateX_Helpers::get_fontweight_variation( $ribbon_variation ),
				'font-style'      => AffiliateX_Helpers::get_font_style( $ribbon_variation ),
				'font-size'       => isset( $attr['ribbonTypography']['size']['desktop'] ) ? $attr['ribbonTypography']['size']['desktop'] : '13px',
				'line-height'     => isset( $attr['ribbonTypography']['line-height']['desktop'] ) ? $attr['ribbonTypography']['line-height']['desktop'] : '1.5',
				'text-transform'  => isset( $attr['ribbonTypography']['text-transform'] ) ? $attr['ribbonTypography']['text-transform'] : 'none',
				'text-decoration' => isset( $attr['ribbonTypography']['text-decoration'] ) ? $attr['ribbonTypography']['text-decoration'] : 'none',
				'letter-spacing'  => isset( $attr['ribbonTypography']['letter-spacing']['desktop'] ) ? $attr['ribbonTypography']['letter-spacing']['desktop'] : '0em',
				'color'           => isset( $attr['ribbonTextColor'] ) ? $attr['ribbonTextColor'] : '#fff',
				'background'      => isset( $attr['ribbonColor'] ) ? $attr['ribbonColor'] : '#F13A3A',
			),
			' .affx-versus-table-wrap .affx-pc-ribbon::before' => array(
				'background' => isset( $attr['ribbonColor'] ) ? $attr['ribbonColor'] : '#F13A3A',
			),
			' .affx-versus-table-wrap .affx-pc-ribbon::after' => array(
				'background' => isset( $attr['ribbonColor'] ) ? $attr['ribbonColor'] : '#F13A3A',
			),
			' .affx-versus-table-wrap .affiliatex-button' => array(
				'font-family'     => isset( $attr['buttonTypography']['family'] ) ? $attr['buttonTypography']['family'] : $global_font_family,
				'font-weight'     => AffiliateX_Helpers::get_fontweight_variation( $button_variation ),
				'font-style'      => AffiliateX_Helpers::get_font_style( $button_variation ),
				'font-size'       => isset( $attr['buttonTypography']['size']['desktop'] ) ? $attr['buttonTypography']['size']['desktop'] : '16px',
				'line-height'     => isset( $attr['buttonTypography']['line-height']['desktop'] ) ? $attr['buttonTypography']['line-height']['desktop'] : '1.65',
				'text-transform'  => isset( $attr['buttonTypography']['text-transform'] ) ? $attr['buttonTypography']['text-transform'] : 'none',
				'text-decoration' => isset( $attr['buttonTypography']['text-decoration'] ) ? $attr['buttonTypography']['text-decoration'] : 'none',
				'letter-spacing'  => isset( $attr['buttonTypography']['letter-spacing']['desktop'] ) ? $attr['buttonTypography']['letter-spacing']['desktop'] : '0em',
			),
			' .affx-versus-table-wrap .affiliatex-button.affx-winner-button' => array(
				'padding-top'      => isset( $attr['buttonPadding']['desktop']['top'] ) ? $attr['buttonPadding']['desktop']['top'] : '10px',
				'padding-left'     => isset( $attr['buttonPadding']['desktop']['left'] ) ? $attr['buttonPadding']['desktop']['left'] : '10px',
				'padding-right'    => isset( $attr['buttonPadding']['desktop']['right'] ) ? $attr['buttonPadding']['desktop']['right'] : '10px',
				'padding-bottom'   => isset( $attr['buttonPadding']['desktop']['bottom'] ) ? $attr['buttonPadding']['desktop']['bottom'] : '10px',
				'margin-top'       => isset( $attr['buttonMargin']['desktop']['top'] ) ? $attr['buttonMargin']['desktop']['top'] : '0px',
				'margin-left'      => isset( $attr['buttonMargin']['desktop']['left'] ) ? $attr['buttonMargin']['desktop']['left'] : '0px',
				'margin-right'     => isset( $attr['buttonMargin']['desktop']['right'] ) ? $attr['buttonMargin']['desktop']['right'] : '0px',
				'margin-bottom'    => isset( $attr['buttonMargin']['desktop']['bottom'] ) ? $attr['buttonMargin']['desktop']['bottom'] : '0px',
				'color'            => isset( $attr['buttonTextColor'] ) ? $attr['buttonTextColor'] : '#fff',
				'background-color' => isset( $attr['buttonBgColor'] ) ? $attr['buttonBgColor'] : $global_btn_color,
			),
			' .affx-versus-table-wrap .affiliatex-button.affx-winner-button:hover' => array(
				'color'            => isset( $attr['buttonTextHoverColor'] ) ? $attr['buttonTextHoverColor'] : '#fff',
				'background-color' => isset( $attr['buttonBgHoverColor'] ) ? $attr['buttonBgHoverColor'] : $global_btn_hover_color,
			),
			' .affx-versus-table-wrap .affx-price'        => array(
				'font-family'     => isset( $attr['priceTypography']['family'] ) ? $attr['priceTypography']['family'] : $global_font_family,
				'font-weight'     => AffiliateX_Helpers::get_fontweight_variation( $price_variation ),
				'font-style'      => AffiliateX_Helpers::get_font_style( $price_variation ),
				'font-size'       => isset( $attr['priceTypography']['size']['desktop'] ) ? $attr['priceTypography']['size']['desktop'] : '16px',
				'line-height'     => isset( $attr['priceTypography']['line-height']['desktop'] ) ? $attr['priceTypography']['line-height']['desktop'] : '1.65',
				'text-transform'  => isset( $attr['priceTypography']['text-transform'] ) ? $attr['priceTypography']['text-transform'] : 'none',
				'text-decoration' => isset( $attr['priceTypography']['text-decoration'] ) ? $attr['priceTypography']['text-decoration'] : 'none',
				'letter-spacing'  => isset( $attr['priceTypography']['letter-spacing']['desktop'] ) ? $attr['priceTypography']['letter-spacing']['desktop'] : '0em',
				'color'           => isset( $attr['priceColor'] ) ? $attr['priceColor'] : '#262B33',
			),
			' .affx-versus-table-wrap .affx-product-versus-table .affx-table-row-header' => array(
				'background' => isset( $attr['tableRowBgColor'] ) ? $attr['tableRowBgColor'] : '#F5F7FA',
			),
			' .affx-versus-table-wrap .affx-product-versus-table .affx-table-row-header th' => array(
				'font-family'     => isset( $attr['contentTypography']['family'] ) ? $attr['contentTypography']['family'] : $global_font_family,
				'font-weight'     => AffiliateX_Helpers::get_fontweight_variation( $variation ),
				'font-style'      => AffiliateX_Helpers::get_font_style( $variation ),
				'font-size'       => isset( $attr['contentTypography']['size']['desktop'] ) ? $attr['contentTypography']['size']['desktop'] : '16px',
				'line-height'     => isset( $attr['contentTypography']['line-height']['desktop'] ) ? $attr['contentTypography']['line-height']['desktop'] : '1.65',
				'text-transform'  => isset( $attr['contentTypography']['text-transform'] ) ? $attr['contentTypography']['text-transform'] : 'none',
				'text-decoration' => isset( $attr['contentTypography']['text-decoration'] ) ? $attr['contentTypography']['text-decoration'] : 'none',
				'letter-spacing'  => isset( $attr['contentTypography']['letter-spacing']['desktop'] ) ? $attr['contentTypography']['letter-spacing']['desktop'] : '0em',
			),
			' .affx-versus-table-wrap .affx-product-versus-table th' => array(
				'font-family'     => isset( $attr['contentTypography']['family'] ) ? $attr['contentTypography']['family'] : $global_font_family,
				'font-weight'     => AffiliateX_Helpers::get_fontweight_variation( $variation ),
				'font-style'      => AffiliateX_Helpers::get_font_style( $variation ),
				'font-size'       => isset( $attr['contentTypography']['size']['desktop'] ) ? $attr['contentTypography']['size']['desktop'] : '16px',
				'line-height'     => isset( $attr['contentTypography']['line-height']['desktop'] ) ? $attr['contentTypography']['line-height']['desktop'] : '1.65',
				'text-transform'  => isset( $attr['contentTypography']['text-transform'] ) ? $attr['contentTypography']['text-transform'] : 'none',
				'text-decoration' => isset( $attr['contentTypography']['text-decoration'] ) ? $attr['contentTypography']['text-decoration'] : 'none',
				'letter-spacing'  => isset( $attr['contentTypography']['letter-spacing']['desktop'] ) ? $attr['contentTypography']['letter-spacing']['desktop'] : '0em',
			),
			' .affx-versus-table-wrap .affx-product-versus-table tbody tr:nth-child(odd) td' => array(
				'background' => isset( $attr['tableRowBgColor'] ) ? $attr['tableRowBgColor'] : '#F5F7FA',
			),
			' .affx-versus-table-wrap .affx-versus-product-img' => array(
				'padding-top'      => isset( $attr['imagePadding']['desktop']['top'] ) ? $attr['imagePadding']['desktop']['top'] : '0px',
				'padding-left'     => isset( $attr['imagePadding']['desktop']['left'] ) ? $attr['imagePadding']['desktop']['left'] : '0px',
				'padding-right'    => isset( $attr['imagePadding']['desktop']['right'] ) ? $attr['imagePadding']['desktop']['right'] : '0px',
				'padding-bottom'   => isset( $attr['imagePadding']['desktop']['bottom'] ) ? $attr['imagePadding']['desktop']['bottom'] : '0px',
			),
		);
		return $selector;
	}

	public static function get_mobileselectors( $attr ) {

		$mobile_selector = array(
			' .affx-product-comparison-block-container'   => array(
				'border-width'  => isset( $attr['borderWidth']['mobile']['top'] ) && isset( $attr['borderWidth']['mobile']['right'] ) && isset( $attr['borderWidth']['mobile']['bottom'] ) && isset( $attr['borderWidth']['mobile']['left'] ) ? $attr['borderWidth']['mobile']['top'] . ' ' . $attr['borderWidth']['mobile']['right'] . ' ' . $attr['borderWidth']['mobile']['bottom'] . ' ' . $attr['borderWidth']['mobile']['left'] . ' ' : '1px 1px 1px 1px',
				'border-radius' => isset( $attr['borderRadius']['mobile']['top'] ) && isset( $attr['borderRadius']['mobile']['right'] ) && isset( $attr['borderRadius']['mobile']['bottom'] ) && isset( $attr['borderRadius']['mobile']['left'] ) ? $attr['borderRadius']['mobile']['top'] . ' ' . $attr['borderRadius']['mobile']['right'] . ' ' . $attr['borderRadius']['mobile']['bottom'] . ' ' . $attr['borderRadius']['mobile']['left'] . ' ' : '0px 0px 0px 0px',
				'margin-top'    => isset( $attr['margin']['mobile']['top'] ) ? $attr['margin']['mobile']['top'] : '0px',
				'margin-left'   => isset( $attr['margin']['mobile']['left'] ) ? $attr['margin']['mobile']['left'] : '0px',
				'margin-right'  => isset( $attr['margin']['mobile']['right'] ) ? $attr['margin']['mobile']['right'] : '0px',
				'margin-bottom' => isset( $attr['margin']['mobile']['bottom'] ) ? $attr['margin']['mobile']['bottom'] : '30px',
			),
			' .affx-product-versus-table'                 => array(
				'font-size'      => isset( $attr['contentTypography']['size']['mobile'] ) ? $attr['contentTypography']['size']['mobile'] : '18px',
				'line-height'    => isset( $attr['contentTypography']['line-height']['mobile'] ) ? $attr['contentTypography']['line-height']['mobile'] : '1.65',
				'letter-spacing' => isset( $attr['contentTypography']['letter-spacing']['mobile'] ) ? $attr['contentTypography']['letter-spacing']['mobile'] : '0em',
			),
			' .affx-comparison-title'                     => array(
				'font-size'      => isset( $attr['titleTypography']['size']['mobile'] ) ? $attr['titleTypography']['size']['mobile'] : '18px',
				'line-height'    => isset( $attr['titleTypography']['line-height']['mobile'] ) ? $attr['titleTypography']['line-height']['mobile'] : '1.5',
				'letter-spacing' => isset( $attr['titleTypography']['letter-spacing']['mobile'] ) ? $attr['titleTypography']['letter-spacing']['mobile'] : '0em',
			),
			' .affx-versus-table-wrap tr:first-child th:first-child' => array(
				'border-top-left-radius' => isset( $attr['borderRadius']['mobile']['top'] ) ? $attr['borderRadius']['mobile']['top'] : '0px',
			),
			' .affx-versus-table-wrap tr:first-child th:last-child' => array(
				'border-top-right-radius' => isset( $attr['borderRadius']['mobile']['right'] ) ? $attr['borderRadius']['mobile']['right'] : '0px',
			),
			' .affx-versus-table-wrap tr:first-child th:last-child .affx-versus-product' => array(
				'border-top-right-radius' => isset( $attr['borderRadius']['mobile']['right'] ) ? $attr['borderRadius']['mobile']['right'] : '0px',
				'overflow'                => 'hidden',
			),
			' .affx-versus-table-wrap tr:last-child td:first-child' => array(
				'border-top-left-radius' => isset( $attr['borderRadius']['mobile']['left'] ) ? $attr['borderRadius']['mobile']['left'] : '0px',
			),
			' .affx-versus-table-wrap tr:last-child td:last-child' => array(
				'border-bottom-left-radius' => isset( $attr['borderRadius']['mobile']['buttom'] ) ? $attr['borderRadius']['mobile']['buttom'] : '0px',
			),
			' .affx-versus-table-wrap td'                 => array(
				'border-width'   => isset( $attr['borderWidth']['mobile']['top'] ) && isset( $attr['borderWidth']['mobile']['right'] ) && isset( $attr['borderWidth']['mobile']['bottom'] ) && isset( $attr['borderWidth']['mobile']['left'] ) ? $attr['borderWidth']['mobile']['top'] . ' ' . $attr['borderWidth']['mobile']['right'] . ' ' . $attr['borderWidth']['mobile']['bottom'] . ' ' . $attr['borderWidth']['mobile']['left'] . ' ' : '1px 1px 1px 1px',
				'padding-top'    => isset( $attr['padding']['mobile']['top'] ) ? $attr['padding']['mobile']['top'] : '24px',
				'padding-left'   => isset( $attr['padding']['mobile']['left'] ) ? $attr['padding']['mobile']['left'] : '24px',
				'padding-right'  => isset( $attr['padding']['mobile']['right'] ) ? $attr['padding']['mobile']['right'] : '24px',
				'padding-bottom' => isset( $attr['padding']['mobile']['bottom'] ) ? $attr['padding']['mobile']['bottom'] : '24px',
			),
			' .affx-versus-table-wrap th'                 => array(
				'border-width'   => isset( $attr['borderWidth']['mobile']['top'] ) && isset( $attr['borderWidth']['mobile']['right'] ) && isset( $attr['borderWidth']['mobile']['bottom'] ) && isset( $attr['borderWidth']['mobile']['left'] ) ? $attr['borderWidth']['mobile']['top'] . ' ' . $attr['borderWidth']['mobile']['right'] . ' ' . $attr['borderWidth']['mobile']['bottom'] . ' ' . $attr['borderWidth']['mobile']['left'] . ' ' : '1px 1px 1px 1px',
				'padding-top'    => isset( $attr['padding']['mobile']['top'] ) ? $attr['padding']['mobile']['top'] : '24px',
				'padding-left'   => isset( $attr['padding']['mobile']['left'] ) ? $attr['padding']['mobile']['left'] : '24px',
				'padding-right'  => isset( $attr['padding']['mobile']['right'] ) ? $attr['padding']['mobile']['right'] : '24px',
				'padding-bottom' => isset( $attr['padding']['mobile']['bottom'] ) ? $attr['padding']['mobile']['bottom'] : '24px',
			),
			' .affx-versus-table-wrap .affx-pc-ribbon'    => array(
				'font-size'      => isset( $attr['ribbonTypography']['size']['mobile'] ) ? $attr['ribbonTypography']['size']['mobile'] : '13px',
				'line-height'    => isset( $attr['ribbonTypography']['line-height']['mobile'] ) ? $attr['ribbonTypography']['line-height']['mobile'] : '1.5',
				'letter-spacing' => isset( $attr['ribbonTypography']['letter-spacing']['mobile'] ) ? $attr['ribbonTypography']['letter-spacing']['mobile'] : '0em',
			),
			' .affx-versus-table-wrap .affiliatex-button' => array(
				'font-size'      => isset( $attr['buttonTypography']['size']['mobile'] ) ? $attr['buttonTypography']['size']['mobile'] : '16px',
				'line-height'    => isset( $attr['buttonTypography']['line-height']['mobile'] ) ? $attr['buttonTypography']['line-height']['mobile'] : '1.65',
				'letter-spacing' => isset( $attr['buttonTypography']['letter-spacing']['mobile'] ) ? $attr['buttonTypography']['letter-spacing']['mobile'] : '0em',
			),
			' .affx-versus-table-wrap .affiliatex-button.affx-winner-button' => array(
				'padding-top'    => isset( $attr['buttonPadding']['mobile']['top'] ) ? $attr['buttonPadding']['mobile']['top'] : '10px',
				'padding-left'   => isset( $attr['buttonPadding']['mobile']['left'] ) ? $attr['buttonPadding']['mobile']['left'] : '10px',
				'padding-right'  => isset( $attr['buttonPadding']['mobile']['right'] ) ? $attr['buttonPadding']['mobile']['right'] : '10px',
				'padding-bottom' => isset( $attr['buttonPadding']['mobile']['bottom'] ) ? $attr['buttonPadding']['mobile']['bottom'] : '10px',
				'margin-top'     => isset( $attr['buttonMargin']['mobile']['top'] ) ? $attr['buttonMargin']['mobile']['top'] : '0px',
				'margin-left'    => isset( $attr['buttonMargin']['mobile']['left'] ) ? $attr['buttonMargin']['mobile']['left'] : '0px',
				'margin-right'   => isset( $attr['buttonMargin']['mobile']['right'] ) ? $attr['buttonMargin']['mobile']['right'] : '0px',
				'margin-bottom'  => isset( $attr['buttonMargin']['mobile']['bottom'] ) ? $attr['buttonMargin']['mobile']['bottom'] : '0px',
			),
			' .affx-versus-table-wrap .affx-price'        => array(
				'font-size'      => isset( $attr['priceTypography']['size']['mobile'] ) ? $attr['priceTypography']['size']['mobile'] : '16px',
				'line-height'    => isset( $attr['priceTypography']['line-height']['mobile'] ) ? $attr['priceTypography']['line-height']['mobile'] : '1.65',
				'letter-spacing' => isset( $attr['priceTypography']['letter-spacing']['mobile'] ) ? $attr['priceTypography']['letter-spacing']['mobile'] : '0em',
			),
			' .affx-versus-table-wrap .affx-product-versus-table .affx-table-row-header th' => array(
				'font-size'      => isset( $attr['contentTypography']['size']['mobile'] ) ? $attr['contentTypography']['size']['mobile'] : '16px',
				'line-height'    => isset( $attr['contentTypography']['line-height']['mobile'] ) ? $attr['contentTypography']['line-height']['mobile'] : '1.65',
				'letter-spacing' => isset( $attr['contentTypography']['letter-spacing']['mobile'] ) ? $attr['contentTypography']['letter-spacing']['mobile'] : '0em',
			),
			' .affx-versus-table-wrap .affx-product-versus-table th' => array(
				'font-size'      => isset( $attr['contentTypography']['size']['mobile'] ) ? $attr['contentTypography']['size']['mobile'] : '16px',
				'line-height'    => isset( $attr['contentTypography']['line-height']['mobile'] ) ? $attr['contentTypography']['line-height']['mobile'] : '1.65',
				'letter-spacing' => isset( $attr['contentTypography']['letter-spacing']['mobile'] ) ? $attr['contentTypography']['letter-spacing']['mobile'] : '0em',
			),
			' .affx-versus-table-wrap .affx-versus-product-img' => array(
				'padding-top'      => isset( $attr['imagePadding']['mobile']['top'] ) ? $attr['imagePadding']['mobile']['top'] : '0px',
				'padding-left'     => isset( $attr['imagePadding']['mobile']['left'] ) ? $attr['imagePadding']['mobile']['left'] : '0px',
				'padding-right'    => isset( $attr['imagePadding']['mobile']['right'] ) ? $attr['imagePadding']['mobile']['right'] : '0px',
				'padding-bottom'   => isset( $attr['imagePadding']['mobile']['bottom'] ) ? $attr['imagePadding']['mobile']['bottom'] : '0px',
			),
		);
		return $mobile_selector;
	}

	public static function get_tabletselectors( $attr ) {

		$tablet_selector = array(
			' .affx-product-comparison-block-container'   => array(
				'border-width'  => isset( $attr['borderWidth']['tablet']['top'] ) && isset( $attr['borderWidth']['tablet']['right'] ) && isset( $attr['borderWidth']['tablet']['bottom'] ) && isset( $attr['borderWidth']['tablet']['left'] ) ? $attr['borderWidth']['tablet']['top'] . ' ' . $attr['borderWidth']['tablet']['right'] . ' ' . $attr['borderWidth']['tablet']['bottom'] . ' ' . $attr['borderWidth']['tablet']['left'] . ' ' : '1px 1px 1px 1px',
				'border-radius' => isset( $attr['borderRadius']['tablet']['top'] ) && isset( $attr['borderRadius']['tablet']['right'] ) && isset( $attr['borderRadius']['tablet']['bottom'] ) && isset( $attr['borderRadius']['tablet']['left'] ) ? $attr['borderRadius']['tablet']['top'] . ' ' . $attr['borderRadius']['tablet']['right'] . ' ' . $attr['borderRadius']['tablet']['bottom'] . ' ' . $attr['borderRadius']['tablet']['left'] . ' ' : '0px 0px 0px 0px',
				'margin-top'    => isset( $attr['margin']['tablet']['top'] ) ? $attr['margin']['tablet']['top'] : '0px',
				'margin-left'   => isset( $attr['margin']['tablet']['left'] ) ? $attr['margin']['tablet']['left'] : '0px',
				'margin-right'  => isset( $attr['margin']['tablet']['right'] ) ? $attr['margin']['tablet']['right'] : '0px',
				'margin-bottom' => isset( $attr['margin']['tablet']['bottom'] ) ? $attr['margin']['tablet']['bottom'] : '30px',
			),
			' .affx-product-versus-table'                 => array(
				'font-size'      => isset( $attr['contentTypography']['size']['tablet'] ) ? $attr['contentTypography']['size']['tablet'] : '18px',
				'line-height'    => isset( $attr['contentTypography']['line-height']['tablet'] ) ? $attr['contentTypography']['line-height']['tablet'] : '1.65',
				'letter-spacing' => isset( $attr['contentTypography']['letter-spacing']['tablet'] ) ? $attr['contentTypography']['letter-spacing']['tablet'] : '0em',
			),
			' .affx-comparison-title'                     => array(
				'font-size'      => isset( $attr['titleTypography']['size']['tablet'] ) ? $attr['titleTypography']['size']['tablet'] : '18px',
				'line-height'    => isset( $attr['titleTypography']['line-height']['tablet'] ) ? $attr['titleTypography']['line-height']['tablet'] : '1.5',
				'letter-spacing' => isset( $attr['titleTypography']['letter-spacing']['tablet'] ) ? $attr['titleTypography']['letter-spacing']['tablet'] : '0em',
			),
			' .affx-versus-table-wrap tr:first-child th:first-child' => array(
				'border-top-left-radius' => isset( $attr['borderRadius']['tablet']['top'] ) ? $attr['borderRadius']['tablet']['top'] : '0px',
			),
			' .affx-versus-table-wrap tr:first-child th:last-child' => array(
				'border-top-right-radius' => isset( $attr['borderRadius']['tablet']['right'] ) ? $attr['borderRadius']['tablet']['right'] : '0px',
			),
			' .affx-versus-table-wrap tr:first-child th:last-child .affx-versus-product' => array(
				'border-top-right-radius' => isset( $attr['borderRadius']['tablet']['right'] ) ? $attr['borderRadius']['tablet']['right'] : '0px',
				'overflow'                => 'hidden',
			),
			' .affx-versus-table-wrap tr:last-child td:first-child' => array(
				'border-top-left-radius' => isset( $attr['borderRadius']['tablet']['left'] ) ? $attr['borderRadius']['tablet']['left'] : '0px',
			),
			' .affx-versus-table-wrap tr:last-child td:last-child' => array(
				'border-bottom-left-radius' => isset( $attr['borderRadius']['tablet']['buttom'] ) ? $attr['borderRadius']['tablet']['buttom'] : '0px',
			),
			' .affx-versus-table-wrap td'                 => array(
				'border-width'   => isset( $attr['borderWidth']['tablet']['top'] ) && isset( $attr['borderWidth']['tablet']['right'] ) && isset( $attr['borderWidth']['tablet']['bottom'] ) && isset( $attr['borderWidth']['tablet']['left'] ) ? $attr['borderWidth']['tablet']['top'] . ' ' . $attr['borderWidth']['tablet']['right'] . ' ' . $attr['borderWidth']['tablet']['bottom'] . ' ' . $attr['borderWidth']['tablet']['left'] . ' ' : '1px 1px 1px 1px',
				'padding-top'    => isset( $attr['padding']['tablet']['top'] ) ? $attr['padding']['tablet']['top'] : '24px',
				'padding-left'   => isset( $attr['padding']['tablet']['left'] ) ? $attr['padding']['tablet']['left'] : '24px',
				'padding-right'  => isset( $attr['padding']['tablet']['right'] ) ? $attr['padding']['tablet']['right'] : '24px',
				'padding-bottom' => isset( $attr['padding']['tablet']['bottom'] ) ? $attr['padding']['tablet']['bottom'] : '24px',
			),
			' .affx-versus-table-wrap th'                 => array(
				'border-width'   => isset( $attr['borderWidth']['tablet']['top'] ) && isset( $attr['borderWidth']['tablet']['right'] ) && isset( $attr['borderWidth']['tablet']['bottom'] ) && isset( $attr['borderWidth']['tablet']['left'] ) ? $attr['borderWidth']['tablet']['top'] . ' ' . $attr['borderWidth']['tablet']['right'] . ' ' . $attr['borderWidth']['tablet']['bottom'] . ' ' . $attr['borderWidth']['tablet']['left'] . ' ' : '1px 1px 1px 1px',
				'padding-top'    => isset( $attr['padding']['tablet']['top'] ) ? $attr['padding']['tablet']['top'] : '24px',
				'padding-left'   => isset( $attr['padding']['tablet']['left'] ) ? $attr['padding']['tablet']['left'] : '24px',
				'padding-right'  => isset( $attr['padding']['tablet']['right'] ) ? $attr['padding']['tablet']['right'] : '24px',
				'padding-bottom' => isset( $attr['padding']['tablet']['bottom'] ) ? $attr['padding']['tablet']['bottom'] : '24px',
			),
			' .affx-versus-table-wrap .affx-pc-ribbon'    => array(
				'font-size'      => isset( $attr['ribbonTypography']['size']['tablet'] ) ? $attr['ribbonTypography']['size']['tablet'] : '13px',
				'line-height'    => isset( $attr['ribbonTypography']['line-height']['tablet'] ) ? $attr['ribbonTypography']['line-height']['tablet'] : '1.5',
				'letter-spacing' => isset( $attr['ribbonTypography']['letter-spacing']['tablet'] ) ? $attr['ribbonTypography']['letter-spacing']['tablet'] : '0em',
			),
			' .affx-versus-table-wrap .affiliatex-button' => array(
				'font-size'      => isset( $attr['buttonTypography']['size']['tablet'] ) ? $attr['buttonTypography']['size']['tablet'] : '16px',
				'line-height'    => isset( $attr['buttonTypography']['line-height']['tablet'] ) ? $attr['buttonTypography']['line-height']['tablet'] : '1.65',
				'letter-spacing' => isset( $attr['buttonTypography']['letter-spacing']['tablet'] ) ? $attr['buttonTypography']['letter-spacing']['tablet'] : '0em',
			),
			' .affx-versus-table-wrap .affiliatex-button.affx-winner-button' => array(
				'padding-top'    => isset( $attr['buttonPadding']['tablet']['top'] ) ? $attr['buttonPadding']['tablet']['top'] : '10px',
				'padding-left'   => isset( $attr['buttonPadding']['tablet']['left'] ) ? $attr['buttonPadding']['tablet']['left'] : '0px',
				'padding-right'  => isset( $attr['buttonPadding']['tablet']['right'] ) ? $attr['buttonPadding']['tablet']['right'] : '0px',
				'padding-bottom' => isset( $attr['buttonPadding']['tablet']['bottom'] ) ? $attr['buttonPadding']['tablet']['bottom'] : '10px',
				'margin-top'     => isset( $attr['buttonMargin']['tablet']['top'] ) ? $attr['buttonMargin']['tablet']['top'] : '0px',
				'margin-left'    => isset( $attr['buttonMargin']['tablet']['left'] ) ? $attr['buttonMargin']['tablet']['left'] : '0px',
				'margin-right'   => isset( $attr['buttonMargin']['tablet']['right'] ) ? $attr['buttonMargin']['tablet']['right'] : '0px',
				'margin-bottom'  => isset( $attr['buttonMargin']['tablet']['bottom'] ) ? $attr['buttonMargin']['tablet']['bottom'] : '0px',
			),
			' .affx-versus-table-wrap .affx-price'        => array(
				'font-size'      => isset( $attr['priceTypography']['size']['tablet'] ) ? $attr['priceTypography']['size']['tablet'] : '16px',
				'line-height'    => isset( $attr['priceTypography']['line-height']['tablet'] ) ? $attr['priceTypography']['line-height']['tablet'] : '1.65',
				'letter-spacing' => isset( $attr['priceTypography']['letter-spacing']['tablet'] ) ? $attr['priceTypography']['letter-spacing']['tablet'] : '0em',
			),
			' .affx-versus-table-wrap .affx-product-versus-table .affx-table-row-header' => array(
				'background' => isset( $attr['tableRowBgColor'] ) ? $attr['tableRowBgColor'] : '#F5F7FA',
			),
			' .affx-versus-table-wrap .affx-product-versus-table .affx-table-row-header th' => array(
				'font-size'      => isset( $attr['contentTypography']['size']['tablet'] ) ? $attr['contentTypography']['size']['tablet'] : '16px',
				'line-height'    => isset( $attr['contentTypography']['line-height']['tablet'] ) ? $attr['contentTypography']['line-height']['tablet'] : '1.65',
				'letter-spacing' => isset( $attr['contentTypography']['letter-spacing']['tablet'] ) ? $attr['contentTypography']['letter-spacing']['tablet'] : '0em',
			),
			' .affx-versus-table-wrap .affx-product-versus-table th' => array(
				'font-size'      => isset( $attr['contentTypography']['size']['tablet'] ) ? $attr['contentTypography']['size']['tablet'] : '16px',
				'line-height'    => isset( $attr['contentTypography']['line-height']['tablet'] ) ? $attr['contentTypography']['line-height']['tablet'] : '1.65',
				'letter-spacing' => isset( $attr['contentTypography']['letter-spacing']['tablet'] ) ? $attr['contentTypography']['letter-spacing']['tablet'] : '0em',
			),
			' .affx-versus-table-wrap .affx-versus-product-img' => array(
				'padding-top'      => isset( $attr['imagePadding']['tablet']['top'] ) ? $attr['imagePadding']['tablet']['top'] : '0px',
				'padding-left'     => isset( $attr['imagePadding']['tablet']['left'] ) ? $attr['imagePadding']['tablet']['left'] : '0px',
				'padding-right'    => isset( $attr['imagePadding']['tablet']['right'] ) ? $attr['imagePadding']['tablet']['right'] : '0px',
				'padding-bottom'   => isset( $attr['imagePadding']['tablet']['bottom'] ) ? $attr['imagePadding']['tablet']['bottom'] : '0px',
			),
		);
		return $tablet_selector;
	}

}
