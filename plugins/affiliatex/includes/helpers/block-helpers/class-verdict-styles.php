<?php
/**
 * Verdict Block Styles
 *
 * @package AffiliateX
 */

class AffiliateX_Verdict_Styles {

	public static function block_fonts( $attr ) {
		return array(
			'verdictTitleTypography'   => isset( $attr['verdictTitleTypography'] ) ? $attr['verdictTitleTypography'] : array(),
			'verdictContentTypography' => isset( $attr['verdictContentTypography'] ) ? $attr['verdictContentTypography'] : array(),
		);
	}

	public static function block_css( $attr, $id ) {
		$selectors = self::get_selectors( $attr );

		$m_selectors = self::get_mobileselectors( $attr );

		$t_selectors = self::get_tabletselectors( $attr );

		$desktop = AffiliateX_Helpers::generate_css( $selectors, '#affiliatex-verdict-style-' . $id );

		$tablet = AffiliateX_Helpers::generate_css( $t_selectors, '#affiliatex-verdict-style-' . $id );

		$mobile = AffiliateX_Helpers::generate_css( $m_selectors, '#affiliatex-verdict-style-' . $id );

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

		$bgType           = isset( $attr['verdictBgType'] ) ? $attr['verdictBgType'] : 'solid';
		$bgGradient       = isset( $attr['verdictBgColorGradient']['gradient'] ) ? $attr['verdictBgColorGradient']['gradient'] : 'linear-gradient(135deg,rgb(238,238,238) 0%,rgb(169,184,195) 100%)';
		$bgColor          = isset( $attr['verdictBgColorSolid'] ) ? $attr['verdictBgColorSolid'] : '#FFFFFF';
		$titleVariation   = isset( $attr['verdictTitleTypography']['variation'] ) ? $attr['verdictTitleTypography']['variation'] : 'n5';
		$contentVariation = isset( $attr['verdictContentTypography']['variation'] ) ? $attr['verdictContentTypography']['variation'] : 'n4';

		$selectors = array(
			' .affblk-verdict-wrapper'    => array(
				'border-style'   => isset( $attr['verdictBorder']['style'] ) ? $attr['verdictBorder']['style'] : 'solid',
				'border-color'   => isset( $attr['verdictBorder']['color']['color'] ) ? $attr['verdictBorder']['color']['color'] : '#E6ECF7',
				'border-width'   => isset( $attr['verdictBorderWidth']['desktop']['top'] ) && isset( $attr['verdictBorderWidth']['desktop']['right'] ) && isset( $attr['verdictBorderWidth']['desktop']['bottom'] ) && isset( $attr['verdictBorderWidth']['desktop']['left'] ) ? $attr['verdictBorderWidth']['desktop']['top'] . ' ' . $attr['verdictBorderWidth']['desktop']['right'] . ' ' . $attr['verdictBorderWidth']['desktop']['bottom'] . ' ' . $attr['verdictBorderWidth']['desktop']['left'] . ' ' : '1px 1px 1px 1px',
				'margin-top'     => isset( $attr['margin']['desktop']['top'] ) ? $attr['margin']['desktop']['top'] : '0px',
				'margin-left'    => isset( $attr['margin']['desktop']['left'] ) ? $attr['margin']['desktop']['left'] : '0px',
				'margin-right'   => isset( $attr['margin']['desktop']['right'] ) ? $attr['margin']['desktop']['right'] : '0px',
				'margin-bottom'  => isset( $attr['margin']['desktop']['bottom'] ) ? $attr['margin']['desktop']['bottom'] : '30px',
				'padding-top'    => isset( $attr['verdictBoxPadding']['desktop']['top'] ) ? $attr['verdictBoxPadding']['desktop']['top'] : '24px',
				'padding-left'   => isset( $attr['verdictBoxPadding']['desktop']['left'] ) ? $attr['verdictBoxPadding']['desktop']['left'] : '24px',
				'padding-right'  => isset( $attr['verdictBoxPadding']['desktop']['right'] ) ? $attr['verdictBoxPadding']['desktop']['right'] : '24px',
				'padding-bottom' => isset( $attr['verdictBoxPadding']['desktop']['bottom'] ) ? $attr['verdictBoxPadding']['desktop']['bottom'] : '24px',
				'border-radius'  => isset( $attr['verdictBorderRadius']['desktop']['top'] ) && isset( $attr['verdictBorderRadius']['desktop']['right'] ) && isset( $attr['verdictBorderRadius']['desktop']['bottom'] ) && isset( $attr['verdictBorderRadius']['desktop']['left'] ) ? $attr['verdictBorderRadius']['desktop']['top'] . ' ' . $attr['verdictBorderRadius']['desktop']['right'] . ' ' . $attr['verdictBorderRadius']['desktop']['bottom'] . ' ' . $attr['verdictBorderRadius']['desktop']['left'] . ' ' : '0 0 0 0',
				'box-shadow'     => isset( $attr['verdictBoxShadow'] ) && $attr['verdictBoxShadow']['enable'] ? AffiliateX_Helpers::get_css_boxshadow( $attr['verdictBoxShadow'] ) : 'none',
				'background'     => $bgType && $bgType === 'solid' ? $bgColor : $bgGradient,
			),
			'.wp-block-affiliatex-verdict .verdict-layout-2' => array(
				'text-align' => isset( $attr['contentAlignment'] ) ? $attr['contentAlignment'] : 'center',
			),
			'.wp-block-affiliatex-verdict .verdict-title' => array(
				'color'           => isset( $attr['verdictTitleColor'] ) ? $attr['verdictTitleColor'] : '#060C0E',
				'font-family'     => isset( $attr['verdictTitleTypography']['family'] ) ? $attr['verdictTitleTypography']['family'] : $global_font_family,
				'font-size'       => isset( $attr['verdictTitleTypography']['size']['desktop'] ) ? $attr['verdictTitleTypography']['size']['desktop'] : '24px',
				'line-height'     => isset( $attr['verdictTitleTypography']['line-height']['desktop'] ) ? $attr['verdictTitleTypography']['line-height']['desktop'] : '1.5',
				'text-transform'  => isset( $attr['verdictTitleTypography']['text-transform'] ) ? $attr['verdictTitleTypography']['text-transform'] : 'none',
				'text-decoration' => isset( $attr['verdictTitleTypography']['text-decoration'] ) ? $attr['verdictTitleTypography']['text-decoration'] : 'none',
				'letter-spacing'  => isset( $attr['verdictTitleTypography']['letter-spacing']['desktop'] ) ? $attr['verdictTitleTypography']['letter-spacing']['desktop'] : '0em',
				'font-weight'     => AffiliateX_Helpers::get_fontweight_variation( $titleVariation ),
				'font-style'      => AffiliateX_Helpers::get_font_style( $titleVariation ),
			),
			'.wp-block-affiliatex-verdict .verdict-content' => array(
				'color'           => isset( $attr['verdictContentColor'] ) ? $attr['verdictContentColor'] : $global_font_color,
				'font-family'     => isset( $attr['verdictContentTypography']['family'] ) ? $attr['verdictContentTypography']['family'] : $global_font_family,
				'font-size'       => isset( $attr['verdictContentTypography']['size']['desktop'] ) ? $attr['verdictContentTypography']['size']['desktop'] : '18px',
				'line-height'     => isset( $attr['verdictContentTypography']['line-height']['desktop'] ) ? $attr['verdictContentTypography']['line-height']['desktop'] : '1.65',
				'text-transform'  => isset( $attr['verdictContentTypography']['text-transform'] ) ? $attr['verdictContentTypography']['text-transform'] : 'none',
				'text-decoration' => isset( $attr['verdictContentTypography']['text-decoration'] ) ? $attr['verdictContentTypography']['text-decoration'] : 'none',
				'letter-spacing'  => isset( $attr['verdictContentTypography']['letter-spacing']['desktop'] ) ? $attr['verdictContentTypography']['letter-spacing']['desktop'] : '0em',
				'font-weight'     => AffiliateX_Helpers::get_fontweight_variation( $contentVariation ),
				'font-style'      => AffiliateX_Helpers::get_font_style( $contentVariation ),
			),

			' .verdict-user-rating-wrapper'    => array(
				'color' => isset( $attr['verdictContentColor'] ) ? $attr['verdictContentColor'] : $global_font_color,
			),
			'.wp-block-affiliatex-verdict .verdict-layout-2.display-arrow .affx-btn-inner .affiliatex-button::after' => array(
				'background' => isset( $attr['verdictArrowColor'] ) ? $attr['verdictArrowColor'] : '#2670FF',
			),

			'.wp-block-affiliatex-verdict .verdict-layout-1 .num' => array(
				'color'            => isset( $attr['scoreTextColor'] ) ? $attr['scoreTextColor'] : '#FFFFFF',
				'background-color' => isset( $attr['scoreBgTopColor'] ) ? $attr['scoreBgTopColor'] : '#2670FF',
			),
			'.wp-block-affiliatex-verdict .verdict-layout-1 .main-text-holder' => array(
				'flex-direction' => isset( $attr['ratingAlignment'] ) && $attr['ratingAlignment'] != 'left' ? 'row' : 'row-reverse',
			),
			'.wp-block-affiliatex-verdict .verdict-layout-1 .affx-verdict-rating-number' => array(
				'color' => isset( $attr['scoreTextColor'] ) ? $attr['scoreTextColor'] : '#FFFFFF',
			),
			'.wp-block-affiliatex-verdict .verdict-layout-1 .rich-content' => array(
				'color'            => isset( $attr['scoreTextColor'] ) ? $attr['scoreTextColor'] : '#FFFFFF',
				'background-color' => isset( $attr['scoreBgBotColor'] ) ? $attr['scoreBgBotColor'] : '#262B33',
			),
			'.wp-block-affiliatex-verdict .verdict-layout-1 .rich-content::after' => array(
				'border-top' => 5 . 'px' . ' solid ' . ( isset( $attr['scoreBgBotColor'] ) ? $attr['scoreBgBotColor'] : '#262B33' ),
			),

		);

		return $selectors;
	}

	public static function get_mobileselectors( $attr ) {

		$mobile_selectors = array(
			' .affblk-verdict-wrapper'    => array(
				'border-width'   => isset( $attr['verdictBorderWidth']['mobile']['top'] ) && isset( $attr['verdictBorderWidth']['mobile']['right'] ) && isset( $attr['verdictBorderWidth']['mobile']['bottom'] ) && isset( $attr['verdictBorderWidth']['mobile']['left'] ) ? $attr['verdictBorderWidth']['mobile']['top'] . ' ' . $attr['verdictBorderWidth']['mobile']['right'] . ' ' . $attr['verdictBorderWidth']['mobile']['bottom'] . ' ' . $attr['verdictBorderWidth']['mobile']['left'] . ' ' : '1px 1px 1px 1px',
				'margin-top'     => isset( $attr['margin']['mobile']['top'] ) ? $attr['margin']['mobile']['top'] : '0px',
				'margin-left'    => isset( $attr['margin']['mobile']['left'] ) ? $attr['margin']['mobile']['left'] : '0px',
				'margin-right'   => isset( $attr['margin']['mobile']['right'] ) ? $attr['margin']['mobile']['right'] : '0px',
				'margin-bottom'  => isset( $attr['margin']['mobile']['bottom'] ) ? $attr['margin']['mobile']['bottom'] : '30px',
				'padding-top'    => isset( $attr['verdictBoxPadding']['mobile']['top'] ) ? $attr['verdictBoxPadding']['mobile']['top'] : '24px',
				'padding-left'   => isset( $attr['verdictBoxPadding']['mobile']['left'] ) ? $attr['verdictBoxPadding']['mobile']['left'] : '24px',
				'padding-right'  => isset( $attr['verdictBoxPadding']['mobile']['right'] ) ? $attr['verdictBoxPadding']['mobile']['right'] : '24px',
				'padding-bottom' => isset( $attr['verdictBoxPadding']['mobile']['bottom'] ) ? $attr['verdictBoxPadding']['mobile']['bottom'] : '24px',
				'border-radius'  => isset( $attr['verdictBorderRadius']['mobile']['top'] ) && isset( $attr['verdictBorderRadius']['mobile']['right'] ) && isset( $attr['verdictBorderRadius']['mobile']['bottom'] ) && isset( $attr['verdictBorderRadius']['mobile']['left'] ) ? $attr['verdictBorderRadius']['mobile']['top'] . ' ' . $attr['verdictBorderRadius']['mobile']['right'] . ' ' . $attr['verdictBorderRadius']['mobile']['bottom'] . ' ' . $attr['verdictBorderRadius']['mobile']['left'] . ' ' : '0 0 0 0',
			),
			'.wp-block-affiliatex-verdict .verdict-title' => array(
				'font-size'      => isset( $attr['verdictTitleTypography']['size']['mobile'] ) ? $attr['verdictTitleTypography']['size']['mobile'] : '24px',
				'line-height'    => isset( $attr['verdictTitleTypography']['line-height']['mobile'] ) ? $attr['verdictTitleTypography']['line-height']['mobile'] : '1.5',
				'letter-spacing' => isset( $attr['verdictTitleTypography']['letter-spacing']['mobile'] ) ? $attr['verdictTitleTypography']['letter-spacing']['mobile'] : '0em',
			),
			'.wp-block-affiliatex-verdict .verdict-content' => array(
				'font-size'      => isset( $attr['verdictContentTypography']['size']['mobile'] ) ? $attr['verdictContentTypography']['size']['mobile'] : '18px',
				'line-height'    => isset( $attr['verdictContentTypography']['line-height']['mobile'] ) ? $attr['verdictContentTypography']['line-height']['mobile'] : '1.65',
				'letter-spacing' => isset( $attr['verdictContentTypography']['letter-spacing']['mobile'] ) ? $attr['verdictContentTypography']['letter-spacing']['mobile'] : '0em',
			),
		);

		return $mobile_selectors;
	}

	public static function get_tabletselectors( $attr ) {

		$tablet_selectors = array(
			' .affblk-verdict-wrapper'    => array(
				'border-width'   => isset( $attr['verdictBorderWidth']['tablet']['top'] ) && isset( $attr['verdictBorderWidth']['tablet']['right'] ) && isset( $attr['verdictBorderWidth']['tablet']['bottom'] ) && isset( $attr['verdictBorderWidth']['tablet']['left'] ) ? $attr['verdictBorderWidth']['tablet']['top'] . ' ' . $attr['verdictBorderWidth']['tablet']['right'] . ' ' . $attr['verdictBorderWidth']['tablet']['bottom'] . ' ' . $attr['verdictBorderWidth']['tablet']['left'] . ' ' : '1px 1px 1px 1px',
				'margin-top'     => isset( $attr['margin']['tablet']['top'] ) ? $attr['margin']['tablet']['top'] : '0px',
				'margin-left'    => isset( $attr['margin']['tablet']['left'] ) ? $attr['margin']['tablet']['left'] : '0px',
				'margin-right'   => isset( $attr['margin']['tablet']['right'] ) ? $attr['margin']['tablet']['right'] : '0px',
				'margin-bottom'  => isset( $attr['margin']['tablet']['bottom'] ) ? $attr['margin']['tablet']['bottom'] : '30px',
				'padding-top'    => isset( $attr['verdictBoxPadding']['tablet']['top'] ) ? $attr['verdictBoxPadding']['tablet']['top'] : '24px',
				'padding-left'   => isset( $attr['verdictBoxPadding']['tablet']['left'] ) ? $attr['verdictBoxPadding']['tablet']['left'] : '24px',
				'padding-right'  => isset( $attr['verdictBoxPadding']['tablet']['right'] ) ? $attr['verdictBoxPadding']['tablet']['right'] : '24px',
				'padding-bottom' => isset( $attr['verdictBoxPadding']['tablet']['bottom'] ) ? $attr['verdictBoxPadding']['tablet']['bottom'] : '24px',
				'border-radius'  => isset( $attr['verdictBorderRadius']['tablet']['top'] ) && isset( $attr['verdictBorderRadius']['tablet']['right'] ) && isset( $attr['verdictBorderRadius']['tablet']['bottom'] ) && isset( $attr['verdictBorderRadius']['tablet']['left'] ) ? $attr['verdictBorderRadius']['tablet']['top'] . ' ' . $attr['verdictBorderRadius']['tablet']['right'] . ' ' . $attr['verdictBorderRadius']['tablet']['bottom'] . ' ' . $attr['verdictBorderRadius']['tablet']['left'] . ' ' : '0 0 0 0',
			),
			'.wp-block-affiliatex-verdict .verdict-title' => array(
				'font-size'      => isset( $attr['verdictTitleTypography']['size']['tablet'] ) ? $attr['verdictTitleTypography']['size']['tablet'] : '24px',
				'line-height'    => isset( $attr['verdictTitleTypography']['line-height']['tablet'] ) ? $attr['verdictTitleTypography']['line-height']['tablet'] : '1.5',
				'letter-spacing' => isset( $attr['verdictTitleTypography']['letter-spacing']['tablet'] ) ? $attr['verdictTitleTypography']['letter-spacing']['tablet'] : '0em',
			),
			'.wp-block-affiliatex-verdict .verdict-content' => array(
				'font-size'      => isset( $attr['verdictContentTypography']['size']['tablet'] ) ? $attr['verdictContentTypography']['size']['tablet'] : '18px',
				'line-height'    => isset( $attr['verdictContentTypography']['line-height']['tablet'] ) ? $attr['verdictContentTypography']['line-height']['tablet'] : '1.65',
				'letter-spacing' => isset( $attr['verdictContentTypography']['letter-spacing']['tablet'] ) ? $attr['verdictContentTypography']['letter-spacing']['tablet'] : '0em',
			),
		);

		return $tablet_selectors;
	}

}
