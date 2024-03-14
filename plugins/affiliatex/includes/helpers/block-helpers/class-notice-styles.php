<?php
/**
 * Notice Block Styles
 *
 * @package AffiliateX
 */

class AffiliateX_Notice_Styles {

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

		$desktop = AffiliateX_Helpers::generate_css( $selectors, '#affiliatex-notice-style-' . $id );

		$tablet = AffiliateX_Helpers::generate_css( $t_selectors, '#affiliatex-notice-style-' . $id );

		$mobile = AffiliateX_Helpers::generate_css( $m_selectors, '#affiliatex-notice-style-' . $id );

		$generated_css = array(
			'desktop' => $desktop,
			'tablet'  => $tablet,
			'mobile'  => $mobile,
		);

		return $generated_css;
	}

	public static function get_selectors( $attr ) {

		$customization_data  = affx_get_customization_settings();
		$global_font_family  = isset( $customization_data['typography']['family'] ) ? $customization_data['typography']['family'] : 'Default';
		$global_font_color   = isset( $customization_data['fontColor'] ) ? $customization_data['fontColor'] : '#292929';
		$variation           = isset( $attr['titleTypography']['variation'] ) ? $attr['titleTypography']['variation'] : 'n4';
		$list_variation      = isset( $attr['listTypography']['variation'] ) ? $attr['listTypography']['variation'] : 'n4';
		$bgGradient          = isset( $attr['noticeBgGradient']['gradient'] ) ? $attr['noticeBgGradient']['gradient'] : '';
		$bgColor             = isset( $attr['noticeBgColor'] ) ? $attr['noticeBgColor'] : '#24b644';
		$listBgGradient      = isset( $attr['listBgGradient']['gradient'] ) ? $attr['listBgGradient']['gradient'] : '';
		$listBgColor         = isset( $attr['listBgColor'] ) ? $attr['listBgColor'] : '#ffffff';
		$bg2Gradient         = isset( $attr['noticeBgTwoGradient']['gradient'] ) ? $attr['noticeBgTwoGradient']['gradient'] : '';
		$bg2Color            = isset( $attr['noticeBgTwoColor'] ) ? $attr['noticeBgTwoColor'] : '#F6F9FF';
		$noticeContentType   = isset( $attr['noticeContentType'] ) ? $attr['noticeContentType'] : 'list';
		$noticeListType      = isset( $attr['noticeListType'] ) ? $attr['noticeListType'] : 'unordered';
		$noticeunorderedType = isset( $attr['noticeunorderedType'] ) ? $attr['noticeunorderedType'] : 'icon';
		$box_shadow          = array(
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
			' .affx-notice-inner-wrapper'                  => array(
				'border-width'  => isset( $attr['noticeBorderWidth']['desktop']['top'] ) && isset( $attr['noticeBorderWidth']['desktop']['right'] ) && isset( $attr['noticeBorderWidth']['desktop']['bottom'] ) && isset( $attr['noticeBorderWidth']['desktop']['left'] ) ? $attr['noticeBorderWidth']['desktop']['top'] . ' ' . $attr['noticeBorderWidth']['desktop']['right'] . ' ' . $attr['noticeBorderWidth']['desktop']['bottom'] . ' ' . $attr['noticeBorderWidth']['desktop']['left'] . ' ' : '0px 0px 0px 0px',
				'border-radius' => isset( $attr['noticeBorderRadius']['desktop']['top'] ) && isset( $attr['noticeBorderRadius']['desktop']['right'] ) && isset( $attr['noticeBorderRadius']['desktop']['bottom'] ) && isset( $attr['noticeBorderRadius']['desktop']['left'] ) ? $attr['noticeBorderRadius']['desktop']['top'] . ' ' . $attr['noticeBorderRadius']['desktop']['right'] . ' ' . $attr['noticeBorderRadius']['desktop']['bottom'] . ' ' . $attr['noticeBorderRadius']['desktop']['left'] . ' ' : '0px 0px 0px 0px',
				'border-style'  => isset( $attr['noticeBorder']['style'] ) ? $attr['noticeBorder']['style'] : 'solid',
				'border-color'  => isset( $attr['noticeBorder']['color']['color'] ) ? $attr['noticeBorder']['color']['color'] : '#E6ECF7',
				'margin-top'    => isset( $attr['noticeMargin']['desktop']['top'] ) ? $attr['noticeMargin']['desktop']['top'] : '0px',
				'margin-left'   => isset( $attr['noticeMargin']['desktop']['left'] ) ? $attr['noticeMargin']['desktop']['left'] : '0px',
				'margin-right'  => isset( $attr['noticeMargin']['desktop']['right'] ) ? $attr['noticeMargin']['desktop']['right'] : '0px',
				'margin-bottom' => isset( $attr['noticeMargin']['desktop']['bottom'] ) ? $attr['noticeMargin']['desktop']['bottom'] : '30px',
				'box-shadow'    => isset( $attr['boxShadow'] ) && $attr['boxShadow']['enable'] ? AffiliateX_Helpers::get_css_boxshadow( $attr['boxShadow'] ) : AffiliateX_Helpers::get_css_boxshadow( $box_shadow ),
				'text-align'    => isset( $attr['alignment'] ) ? $attr['alignment'] : 'left',
			),
			' .affiliatex-notice-title'                    => array(
				'font-family'     => isset( $attr['titleTypography']['family'] ) ? $attr['titleTypography']['family'] : $global_font_family,
				'font-weight'     => AffiliateX_Helpers::get_fontweight_variation( $variation ),
				'font-style'      => AffiliateX_Helpers::get_font_style( $variation ),
				'font-size'       => isset( $attr['titleTypography']['size']['desktop'] ) ? $attr['titleTypography']['size']['desktop'] : '18px',
				'line-height'     => isset( $attr['titleTypography']['line-height']['desktop'] ) ? $attr['titleTypography']['line-height']['desktop'] : '1.5',
				'text-transform'  => isset( $attr['titleTypography']['text-transform'] ) ? $attr['titleTypography']['text-transform'] : 'none',
				'text-decoration' => isset( $attr['titleTypography']['text-decoration'] ) ? $attr['titleTypography']['text-decoration'] : 'none',
				'letter-spacing'  => isset( $attr['titleTypography']['letter-spacing']['desktop'] ) ? $attr['titleTypography']['letter-spacing']['desktop'] : '0em',
				'text-align'      => isset( $attr['productTitleAlign'] ) ? $attr['productTitleAlign'] : 'left',
				'color'           => isset( $attr['noticeTextColor'] ) ? $attr['noticeTextColor'] : '#ffffff',
				'padding-top'     => isset( $attr['titlePadding']['desktop']['top'] ) ? $attr['titlePadding']['desktop']['top'] : '10px',
				'padding-left'    => isset( $attr['titlePadding']['desktop']['left'] ) ? $attr['titlePadding']['desktop']['left'] : '15px',
				'padding-right'   => isset( $attr['titlePadding']['desktop']['right'] ) ? $attr['titlePadding']['desktop']['right'] : '15px',
				'padding-bottom'  => isset( $attr['titlePadding']['desktop']['bottom'] ) ? $attr['titlePadding']['desktop']['bottom'] : '10px',
			),
			' .affiliatex-notice-icon'                     => array(
				'color'     => isset( $attr['noticeIconTwoColor'] ) ? $attr['noticeIconTwoColor'] : '#084ACA',
				'font-size' => isset( $attr['noticeIconSize'] ) ? $attr['noticeIconSize'] . 'px' : '18px',
			),
			' .affiliatex-notice-content'                  => array(
				'padding-top'    => isset( $attr['contentPadding']['desktop']['top'] ) ? $attr['contentPadding']['desktop']['top'] : '10px',
				'padding-left'   => isset( $attr['contentPadding']['desktop']['left'] ) ? $attr['contentPadding']['desktop']['left'] : '15px',
				'padding-right'  => isset( $attr['contentPadding']['desktop']['right'] ) ? $attr['contentPadding']['desktop']['right'] : '15px',
				'padding-bottom' => isset( $attr['contentPadding']['desktop']['bottom'] ) ? $attr['contentPadding']['desktop']['bottom'] : '10px',
			),
			' .affiliatex-notice-content p'                => array(
				'font-family'     => isset( $attr['listTypography']['family'] ) ? $attr['listTypography']['family'] : $global_font_family,
				'font-weight'     => AffiliateX_Helpers::get_fontweight_variation( $list_variation ),
				'font-style'      => AffiliateX_Helpers::get_font_style( $list_variation ),
				'font-size'       => isset( $attr['listTypography']['size']['desktop'] ) ? $attr['listTypography']['size']['desktop'] : '18px',
				'line-height'     => isset( $attr['listTypography']['line-height']['desktop'] ) ? $attr['listTypography']['line-height']['desktop'] : '1.65',
				'text-transform'  => isset( $attr['listTypography']['text-transform'] ) ? $attr['listTypography']['text-transform'] : 'none',
				'text-decoration' => isset( $attr['listTypography']['text-decoration'] ) ? $attr['listTypography']['text-decoration'] : 'none',
				'letter-spacing'  => isset( $attr['listTypography']['letter-spacing']['desktop'] ) ? $attr['titleTypography']['letter-spacing']['desktop'] : '0em',
				'text-align'      => isset( $attr['alignment'] ) ? $attr['alignment'] : 'left',
				'color'           => isset( $attr['noticeListColor'] ) ? $attr['noticeListColor'] : $global_font_color,
			),
			' .affiliatex-notice-content li'               => array(
				'font-family'     => isset( $attr['listTypography']['family'] ) ? $attr['listTypography']['family'] : $global_font_family,
				'font-weight'     => AffiliateX_Helpers::get_fontweight_variation( $list_variation ),
				'font-style'      => AffiliateX_Helpers::get_font_style( $list_variation ),
				'font-size'       => isset( $attr['listTypography']['size']['desktop'] ) ? $attr['listTypography']['size']['desktop'] : '18px',
				'line-height'     => isset( $attr['listTypography']['line-height']['desktop'] ) ? $attr['listTypography']['line-height']['desktop'] : '1.65',
				'text-transform'  => isset( $attr['listTypography']['text-transform'] ) ? $attr['listTypography']['text-transform'] : 'none',
				'text-decoration' => isset( $attr['listTypography']['text-decoration'] ) ? $attr['listTypography']['text-decoration'] : 'none',
				'letter-spacing'  => isset( $attr['listTypography']['letter-spacing']['desktop'] ) ? $attr['titleTypography']['letter-spacing']['desktop'] : '0em',
				'justify-content' => isset( $attr['alignment'] ) ? $attr['alignment'] : 'left',
				'color'           => isset( $attr['noticeListColor'] ) ? $attr['noticeListColor'] : $global_font_color,
			),
			' .affiliatex-notice-content .affiliatex-list li::marker' => array(
				'color' => isset( $attr['noticeIconColor'] ) ? $attr['noticeIconColor'] : '#24b644',
			),
			' .affiliatex-notice-content .affiliatex-list li:before' => array(
				'color'     => isset( $attr['noticeIconColor'] ) ? $attr['noticeIconColor'] : '#24b644',
				'font-size' => isset( $attr['noticeListIconSize'] ) ? $attr['noticeListIconSize'] . 'px' : '17px',
			),
			' .affx-notice-inner-wrapper.layout-type-2'    => array(
				'margin-top'     => isset( $attr['noticeMargin']['desktop']['top'] ) ? $attr['noticeMargin']['desktop']['top'] : '0px',
				'margin-left'    => isset( $attr['noticeMargin']['desktop']['left'] ) ? $attr['noticeMargin']['desktop']['left'] : '0px',
				'margin-right'   => isset( $attr['noticeMargin']['desktop']['right'] ) ? $attr['noticeMargin']['desktop']['right'] : '0px',
				'margin-bottom'  => isset( $attr['noticeMargin']['desktop']['bottom'] ) ? $attr['noticeMargin']['desktop']['bottom'] : '30px',
				'padding-top'    => isset( $attr['noticePadding']['desktop']['top'] ) ? $attr['noticePadding']['desktop']['top'] : '20px',
				'padding-left'   => isset( $attr['noticePadding']['desktop']['left'] ) ? $attr['noticePadding']['desktop']['left'] : '20px',
				'padding-right'  => isset( $attr['noticePadding']['desktop']['right'] ) ? $attr['noticePadding']['desktop']['right'] : '20px',
				'padding-bottom' => isset( $attr['noticePadding']['desktop']['bottom'] ) ? $attr['noticePadding']['desktop']['bottom'] : '20px',
				'background'     => isset( $attr['noticeBgTwoType'] ) && $attr['noticeBgTwoType'] === 'gradient' ? $bg2Gradient : $bg2Color,
			),
			' .affx-notice-inner-wrapper.layout-type-2 .affiliatex-notice-title' => array(
				'color'          => isset( $attr['noticeTextTwoColor'] ) ? $attr['noticeTextTwoColor'] : '#084ACA',
				'padding-top'    => '0px',
				'padding-left'   => '0px',
				'padding-right'  => '0px',
				'padding-bottom' => '10px',
			),
			' .affx-notice-inner-wrapper.layout-type-1 .affiliatex-notice-title:before' => array(
				'font-size' => isset( $attr['noticeIconSize'] ) ? $attr['noticeIconSize'] . 'px' : '17px',
			),
			' .affx-notice-inner-wrapper.layout-type-2 .affiliatex-notice-title:before' => array(
				'color'     => isset( $attr['noticeTextTwoColor'] ) ? $attr['noticeTextTwoColor'] : '#084ACA',
				'font-size' => isset( $attr['noticeIconSize'] ) ? $attr['noticeIconSize'] . 'px' : '17px',
			),
			' .affx-notice-inner-wrapper.layout-type-2 .affiliatex-notice-content' => array(
				'padding-top'    => '0px',
				'padding-left'   => '0px',
				'padding-right'  => '0px',
				'padding-bottom' => '0px',
			),
			' .affx-notice-inner-wrapper.layout-type-3'    => array(
				'margin-top'     => isset( $attr['noticeMargin']['desktop']['top'] ) ? $attr['noticeMargin']['desktop']['top'] : '0px',
				'margin-left'    => isset( $attr['noticeMargin']['desktop']['left'] ) ? $attr['noticeMargin']['desktop']['left'] : '0px',
				'margin-right'   => isset( $attr['noticeMargin']['desktop']['right'] ) ? $attr['noticeMargin']['desktop']['right'] : '0px',
				'margin-bottom'  => isset( $attr['noticeMargin']['desktop']['bottom'] ) ? $attr['noticeMargin']['desktop']['bottom'] : '30px',
				'padding-top'    => isset( $attr['noticePadding']['desktop']['top'] ) ? $attr['noticePadding']['desktop']['top'] : '20px',
				'padding-left'   => isset( $attr['noticePadding']['desktop']['left'] ) ? $attr['noticePadding']['desktop']['left'] : '20px',
				'padding-right'  => isset( $attr['noticePadding']['desktop']['right'] ) ? $attr['noticePadding']['desktop']['right'] : '20px',
				'padding-bottom' => isset( $attr['noticePadding']['desktop']['bottom'] ) ? $attr['noticePadding']['desktop']['bottom'] : '20px',
				'background'     => isset( $attr['noticeBgTwoType'] ) && $attr['noticeBgTwoType'] === 'gradient' ? $bg2Gradient : $bg2Color,
			),
			' .affx-notice-inner-wrapper.layout-type-3 .affiliatex-notice-title' => array(
				'color'          => isset( $attr['noticeTextTwoColor'] ) ? $attr['noticeTextTwoColor'] : '#084ACA',
				'padding-top'    => '0px',
				'padding-left'   => '0px',
				'padding-right'  => '0px',
				'padding-bottom' => '10px',
			),
			' .affx-notice-inner-wrapper.layout-type-3 .affiliatex-notice-content' => array(
				'padding-top'    => '0px',
				'padding-left'   => '0px',
				'padding-right'  => '0px',
				'padding-bottom' => '0px',
			),
			' .affx-notice-inner-wrapper.layout-type-1 .affiliatex-notice-title' => array(
				'background' => isset( $attr['noticeBgType'] ) && $attr['noticeBgType'] === 'gradient' ? $bgGradient : $bgColor,
			),
			' .affx-notice-inner-wrapper.layout-type-1 .affiliatex-notice-content' => array(
				'background' => isset( $attr['listBgType'] ) && $attr['listBgType'] === 'gradient' ? $listBgGradient : $listBgColor,
			),
			' .affiliatex-notice-content .affiliatex-list' => array(
				'list-style' => $noticeContentType == 'list' &&
				$noticeListType == 'unordered' &&
				$noticeunorderedType == 'icon'
					? 'none'
					: '',
			),
		);

		return $selectors;
	}

	public static function get_mobileselectors( $attr ) {

		$mobile_selectors = array(
			' .affiliatex-notice-title'                 => array(
				'font-size'      => isset( $attr['titleTypography']['size']['mobile'] ) ? $attr['titleTypography']['size']['mobile'] : '24px',
				'line-height'    => isset( $attr['titleTypography']['line-height']['mobile'] ) ? $attr['titleTypography']['line-height']['mobile'] : '1.333',
				'letter-spacing' => isset( $attr['titleTypography']['letter-spacing']['mobile'] ) ? $attr['titleTypography']['letter-spacing']['mobile'] : '0em',
				'padding-top'    => isset( $attr['titlePadding']['mobile']['top'] ) ? $attr['titlePadding']['mobile']['top'] : '10px',
				'padding-left'   => isset( $attr['titlePadding']['mobile']['left'] ) ? $attr['titlePadding']['mobile']['left'] : '15px',
				'padding-right'  => isset( $attr['titlePadding']['mobile']['right'] ) ? $attr['titlePadding']['mobile']['right'] : '15px',
				'padding-bottom' => isset( $attr['titlePadding']['mobile']['bottom'] ) ? $attr['titlePadding']['mobile']['bottom'] : '10px',
			),
			' .affiliatex-notice-content'               => array(
				'padding-top'    => isset( $attr['contentPadding']['mobile']['top'] ) ? $attr['contentPadding']['mobile']['top'] : '10px',
				'padding-left'   => isset( $attr['contentPadding']['mobile']['left'] ) ? $attr['contentPadding']['mobile']['left'] : '15px',
				'padding-right'  => isset( $attr['contentPadding']['mobile']['right'] ) ? $attr['contentPadding']['mobile']['right'] : '15px',
				'padding-bottom' => isset( $attr['contentPadding']['mobile']['bottom'] ) ? $attr['contentPadding']['mobile']['bottom'] : '10px',
			),
			' .affiliatex-notice-content li'            => array(
				'font-size'      => isset( $attr['listTypography']['size']['mobile'] ) ? $attr['listTypography']['size']['mobile'] : '18px',
				'line-height'    => isset( $attr['listTypography']['line-height']['mobile'] ) ? $attr['listTypography']['line-height']['mobile'] : '1.333',
				'letter-spacing' => isset( $attr['listTypography']['letter-spacing']['mobile'] ) ? $attr['listTypography']['letter-spacing']['mobile'] : '0em',
			),
			' .affx-notice-inner-wrapper'               => array(
				'border-width'  => isset( $attr['noticeBorderWidth']['mobile']['top'] ) && isset( $attr['noticeBorderWidth']['mobile']['right'] ) && isset( $attr['noticeBorderWidth']['mobile']['bottom'] ) && isset( $attr['noticeBorderWidth']['mobile']['left'] ) ? $attr['noticeBorderWidth']['mobile']['top'] . ' ' . $attr['noticeBorderWidth']['mobile']['right'] . ' ' . $attr['noticeBorderWidth']['mobile']['bottom'] . ' ' . $attr['noticeBorderWidth']['mobile']['left'] . ' ' : '0px 0px 0px 0px',
				'border-radius' => isset( $attr['noticeBorderRadius']['mobile']['top'] ) && isset( $attr['noticeBorderRadius']['mobile']['right'] ) && isset( $attr['noticeBorderRadius']['mobile']['bottom'] ) && isset( $attr['noticeBorderRadius']['mobile']['left'] ) ? $attr['noticeBorderRadius']['mobile']['top'] . ' ' . $attr['noticeBorderRadius']['mobile']['right'] . ' ' . $attr['noticeBorderRadius']['mobile']['bottom'] . ' ' . $attr['noticeBorderRadius']['mobile']['left'] . ' ' : '0px 0px 0px 0px',
				'margin-top'    => isset( $attr['noticeMargin']['mobile']['top'] ) ? $attr['noticeMargin']['mobile']['top'] : '0px',
				'margin-left'   => isset( $attr['noticeMargin']['mobile']['left'] ) ? $attr['noticeMargin']['mobile']['left'] : '0px',
				'margin-right'  => isset( $attr['noticeMargin']['mobile']['right'] ) ? $attr['noticeMargin']['mobile']['right'] : '0px',
				'margin-bottom' => isset( $attr['noticeMargin']['mobile']['bottom'] ) ? $attr['noticeMargin']['mobile']['bottom'] : '30px',
			),
			' .affx-notice-inner-wrapper.layout-type-2' => array(
				'margin-top'     => isset( $attr['noticeMargin']['mobile']['top'] ) ? $attr['noticeMargin']['mobile']['top'] : '0px',
				'margin-left'    => isset( $attr['noticeMargin']['mobile']['left'] ) ? $attr['noticeMargin']['mobile']['left'] : '0px',
				'margin-right'   => isset( $attr['noticeMargin']['mobile']['right'] ) ? $attr['noticeMargin']['mobile']['right'] : '0px',
				'margin-bottom'  => isset( $attr['noticeMargin']['mobile']['bottom'] ) ? $attr['noticeMargin']['mobile']['bottom'] : '30px',
				'padding-top'    => isset( $attr['noticePadding']['mobile']['top'] ) ? $attr['noticePadding']['mobile']['top'] : '20px',
				'padding-left'   => isset( $attr['noticePadding']['mobile']['left'] ) ? $attr['noticePadding']['mobile']['left'] : '20px',
				'padding-right'  => isset( $attr['noticePadding']['mobile']['right'] ) ? $attr['noticePadding']['mobile']['right'] : '20px',
				'padding-bottom' => isset( $attr['noticePadding']['mobile']['bottom'] ) ? $attr['noticePadding']['mobile']['bottom'] : '20px',
			),
			' .affx-notice-inner-wrapper.layout-type-3' => array(
				'margin-top'     => isset( $attr['noticeMargin']['mobile']['top'] ) ? $attr['noticeMargin']['mobile']['top'] : '0px',
				'margin-left'    => isset( $attr['noticeMargin']['mobile']['left'] ) ? $attr['noticeMargin']['mobile']['left'] : '0px',
				'margin-right'   => isset( $attr['noticeMargin']['mobile']['right'] ) ? $attr['noticeMargin']['mobile']['right'] : '0px',
				'margin-bottom'  => isset( $attr['noticeMargin']['mobile']['bottom'] ) ? $attr['noticeMargin']['mobile']['bottom'] : '30px',
				'padding-top'    => isset( $attr['noticePadding']['mobile']['top'] ) ? $attr['noticePadding']['mobile']['top'] : '20px',
				'padding-left'   => isset( $attr['noticePadding']['mobile']['left'] ) ? $attr['noticePadding']['mobile']['left'] : '20px',
				'padding-right'  => isset( $attr['noticePadding']['mobile']['right'] ) ? $attr['noticePadding']['mobile']['right'] : '20px',
				'padding-bottom' => isset( $attr['noticePadding']['mobile']['bottom'] ) ? $attr['noticePadding']['mobile']['bottom'] : '20px',
			),
		);
		return $mobile_selectors;
	}

	public static function get_tabletselectors( $attr ) {

		$tablet_selectors = array(
			' .affiliatex-notice-title'                 => array(
				'font-size'      => isset( $attr['titleTypography']['size']['tablet'] ) ? $attr['titleTypography']['size']['tablet'] : '24px',
				'line-height'    => isset( $attr['titleTypography']['line-height']['tablet'] ) ? $attr['titleTypography']['line-height']['tablet'] : '1.333',
				'letter-spacing' => isset( $attr['titleTypography']['letter-spacing']['tablet'] ) ? $attr['titleTypography']['letter-spacing']['tablet'] : '0em',
				'padding-top'    => isset( $attr['titlePadding']['tablet']['top'] ) ? $attr['titlePadding']['tablet']['top'] : '10px',
				'padding-left'   => isset( $attr['titlePadding']['tablet']['left'] ) ? $attr['titlePadding']['tablet']['left'] : '15px',
				'padding-right'  => isset( $attr['titlePadding']['tablet']['right'] ) ? $attr['titlePadding']['tablet']['right'] : '15px',
				'padding-bottom' => isset( $attr['titlePadding']['tablet']['bottom'] ) ? $attr['titlePadding']['tablet']['bottom'] : '10px',
			),
			' .affiliatex-notice-content'               => array(
				'padding-top'    => isset( $attr['contentPadding']['tablet']['top'] ) ? $attr['contentPadding']['tablet']['top'] : '10px',
				'padding-left'   => isset( $attr['contentPadding']['tablet']['left'] ) ? $attr['contentPadding']['tablet']['left'] : '15px',
				'padding-right'  => isset( $attr['contentPadding']['tablet']['right'] ) ? $attr['contentPadding']['tablet']['right'] : '15px',
				'padding-bottom' => isset( $attr['contentPadding']['tablet']['bottom'] ) ? $attr['contentPadding']['tablet']['bottom'] : '10px',
			),
			' .affiliatex-notice-content li'            => array(
				'font-size'      => isset( $attr['listTypography']['size']['tablet'] ) ? $attr['listTypography']['size']['tablet'] : '18px',
				'line-height'    => isset( $attr['listTypography']['line-height']['tablet'] ) ? $attr['listTypography']['line-height']['tablet'] : '1.333',
				'letter-spacing' => isset( $attr['listTypography']['letter-spacing']['tablet'] ) ? $attr['listTypography']['letter-spacing']['tablet'] : '0em',
			),
			' .affx-notice-inner-wrapper'               => array(
				'border-width'  => isset( $attr['noticeBorderWidth']['tablet']['top'] ) && isset( $attr['noticeBorderWidth']['tablet']['right'] ) && isset( $attr['noticeBorderWidth']['tablet']['bottom'] ) && isset( $attr['noticeBorderWidth']['tablet']['left'] ) ? $attr['noticeBorderWidth']['tablet']['top'] . ' ' . $attr['noticeBorderWidth']['tablet']['right'] . ' ' . $attr['noticeBorderWidth']['tablet']['bottom'] . ' ' . $attr['noticeBorderWidth']['tablet']['left'] . ' ' : '0px 0px 0px 0px',
				'border-radius' => isset( $attr['noticeBorderRadius']['tablet']['top'] ) && isset( $attr['noticeBorderRadius']['tablet']['right'] ) && isset( $attr['noticeBorderRadius']['tablet']['bottom'] ) && isset( $attr['noticeBorderRadius']['tablet']['left'] ) ? $attr['noticeBorderRadius']['tablet']['top'] . ' ' . $attr['noticeBorderRadius']['tablet']['right'] . ' ' . $attr['noticeBorderRadius']['tablet']['bottom'] . ' ' . $attr['noticeBorderRadius']['tablet']['left'] . ' ' : '0px 0px 0px 0px',
				'margin-top'    => isset( $attr['noticeMargin']['tablet']['top'] ) ? $attr['noticeMargin']['tablet']['top'] : '0px',
				'margin-left'   => isset( $attr['noticeMargin']['tablet']['left'] ) ? $attr['noticeMargin']['tablet']['left'] : '0px',
				'margin-right'  => isset( $attr['noticeMargin']['tablet']['right'] ) ? $attr['noticeMargin']['tablet']['right'] : '0px',
				'margin-bottom' => isset( $attr['noticeMargin']['tablet']['bottom'] ) ? $attr['noticeMargin']['tablet']['bottom'] : '30px',
			),
			' .affx-notice-inner-wrapper.layout-type-2' => array(
				'margin-top'     => isset( $attr['noticeMargin']['tablet']['top'] ) ? $attr['noticeMargin']['tablet']['top'] : '0px',
				'margin-left'    => isset( $attr['noticeMargin']['tablet']['left'] ) ? $attr['noticeMargin']['tablet']['left'] : '0px',
				'margin-right'   => isset( $attr['noticeMargin']['tablet']['right'] ) ? $attr['noticeMargin']['tablet']['right'] : '0px',
				'margin-bottom'  => isset( $attr['noticeMargin']['tablet']['bottom'] ) ? $attr['noticeMargin']['tablet']['bottom'] : '30px',
				'padding-top'    => isset( $attr['noticePadding']['tablet']['top'] ) ? $attr['noticePadding']['tablet']['top'] : '20px',
				'padding-left'   => isset( $attr['noticePadding']['tablet']['left'] ) ? $attr['noticePadding']['tablet']['left'] : '20px',
				'padding-right'  => isset( $attr['noticePadding']['tablet']['right'] ) ? $attr['noticePadding']['tablet']['right'] : '20px',
				'padding-bottom' => isset( $attr['noticePadding']['tablet']['bottom'] ) ? $attr['noticePadding']['tablet']['bottom'] : '20px',
			),
			' .affx-notice-inner-wrapper.layout-type-3' => array(
				'margin-top'     => isset( $attr['noticeMargin']['tablet']['top'] ) ? $attr['noticeMargin']['tablet']['top'] : '0px',
				'margin-left'    => isset( $attr['noticeMargin']['tablet']['left'] ) ? $attr['noticeMargin']['tablet']['left'] : '0px',
				'margin-right'   => isset( $attr['noticeMargin']['tablet']['right'] ) ? $attr['noticeMargin']['tablet']['right'] : '0px',
				'margin-bottom'  => isset( $attr['noticeMargin']['tablet']['bottom'] ) ? $attr['noticeMargin']['tablet']['bottom'] : '30px',
				'padding-top'    => isset( $attr['noticePadding']['tablet']['top'] ) ? $attr['noticePadding']['tablet']['top'] : '20px',
				'padding-left'   => isset( $attr['noticePadding']['tablet']['left'] ) ? $attr['noticePadding']['tablet']['left'] : '20px',
				'padding-right'  => isset( $attr['noticePadding']['tablet']['right'] ) ? $attr['noticePadding']['tablet']['right'] : '20px',
				'padding-bottom' => isset( $attr['noticePadding']['tablet']['bottom'] ) ? $attr['noticePadding']['tablet']['bottom'] : '20px',
			),
		);
		return $tablet_selectors;
	}

}
