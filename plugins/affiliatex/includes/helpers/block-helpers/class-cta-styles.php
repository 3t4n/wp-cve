<?php
/**
 * CTA Block Styles
 *
 * @package AffiliateX
 */

class AffiliateX_CTA_Styles {

	public static function block_fonts( $attr ) {
		return array(
			'ctaTitleTypography'   => isset( $attr['ctaTitleTypography'] ) ? $attr['ctaTitleTypography'] : array(),
			'ctaContentTypography' => isset( $attr['ctaContentTypography'] ) ? $attr['ctaContentTypography'] : array(),
		);
	}

	public static function block_css( $attr, $id ) {
		$selectors = self::get_selectors( $attr );

		$m_selectors = self::get_mobileselectors( $attr );

		$t_selectors = self::get_tabletselectors( $attr );

		$desktop = AffiliateX_Helpers::generate_css( $selectors, '#affiliatex-style-' . $id );

		$tablet = AffiliateX_Helpers::generate_css( $t_selectors, '#affiliatex-style-' . $id );

		$mobile = AffiliateX_Helpers::generate_css( $m_selectors, '#affiliatex-style-' . $id );

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
		$ctaBgGradient      = isset( $attr['ctaBgGradient']['gradient'] ) ? $attr['ctaBgGradient']['gradient'] : '';
		$ctaBGColor         = isset( $attr['ctaBGColor'] ) ? $attr['ctaBGColor'] : '#fff';
		$buttonBGColor      = isset( $attr['buttonBGColor'] ) ? $attr['buttonBGColor'] : '#2670FF';
		$variation          = isset( $attr['ctaTitleTypography']['variation'] ) ? $attr['ctaTitleTypography']['variation'] : 'n5';
		$content_variation  = isset( $attr['ctaContentTypography']['variation'] ) ? $attr['ctaContentTypography']['variation'] : 'n5';
		$position           = 'center';
		if ( isset( $attr['imagePosition'] ) ) {
			if ( $attr['imagePosition'] === 'center' ) {
				$position = 'center center';
			} elseif ( $attr['imagePosition'] === 'centerLeft' ) {
				$position = 'center left';
			} elseif ( $attr['imagePosition'] === 'centerRight' ) {
				$position = 'center right';
			} elseif ( $attr['imagePosition'] === 'topCenter' ) {
				$position = 'top center';
			} elseif ( $attr['imagePosition'] === 'topLeft' ) {
				$position = 'top left';
			} elseif ( $attr['imagePosition'] === 'topRight' ) {
				$position = 'top right';
			} elseif ( $attr['imagePosition'] === 'bottomCenter' ) {
				$position = 'bottom center';
			} elseif ( $attr['imagePosition'] === 'bottomLeft' ) {
				$position = 'bottom left';
			} elseif ( $attr['imagePosition'] === 'bottomRight' ) {
				$position = 'bottom right';
			}
		}
		$selectors = array(
			' .layout-type-1'                         => array(
				'background-image' => isset( $attr['imgURL'] ) ? 'url(' . $attr['imgURL'] . ')' : 'url(' . plugin_dir_url( AFFILIATEX_PLUGIN_FILE ) . 'app/src/images/fallback.jpg' . ')',
			),
			' .layout-type-3'                         => array(
				'background-image' => isset( $attr['imgURL'] ) ? 'url(' . $attr['imgURL'] . ')' : 'url(' . plugin_dir_url( AFFILIATEX_PLUGIN_FILE ) . 'app/src/images/fallback.jpg' . ')',
			),
			' .image-wrapper'                         => array(
				'background-image'    => isset( $attr['imgURL'] ) ? 'url(' . $attr['imgURL'] . ')' : 'url(' . plugin_dir_url( AFFILIATEX_PLUGIN_FILE ) . 'app/src/images/fallback.jpg' . ')',
				'background-position' => $position,
			),
			' .bg-color'                              => array(
				'background' => isset( $attr['ctaBgColorType'] ) && $attr['ctaBgColorType'] === 'gradient' ? $ctaBgGradient : $ctaBGColor,
			),

			'.wp-block-affiliatex-cta > div'               => array(
				'background-size'     => 'cover',
				'background-repeat'   => 'no-repeat',
				'background-position' => $position,
				'border-style'        => isset( $attr['ctaBorder']['style'] ) ? $attr['ctaBorder']['style'] : 'none',
				'border-width'        => isset( $attr['ctaBorderWidth']['top'] ) && isset( $attr['ctaBorderWidth']['right'] ) && isset( $attr['ctaBorderWidth']['bottom'] ) && isset( $attr['ctaBorderWidth']['left'] ) ? $attr['ctaBorderWidth']['top'] . ' ' . $attr['ctaBorderWidth']['right'] . ' ' . $attr['ctaBorderWidth']['bottom'] . ' ' . $attr['ctaBorderWidth']['left'] . ' ' : '1px 1px 1px 1px',
				'border-radius'       => isset( $attr['ctaBorderRadius']['desktop']['top'] ) && isset( $attr['ctaBorderRadius']['desktop']['right'] ) && isset( $attr['ctaBorderRadius']['desktop']['bottom'] ) && isset( $attr['ctaBorderRadius']['desktop']['left'] ) ? $attr['ctaBorderRadius']['desktop']['top'] . ' ' . $attr['ctaBorderRadius']['desktop']['right'] . ' ' . $attr['ctaBorderRadius']['desktop']['bottom'] . ' ' . $attr['ctaBorderRadius']['desktop']['left'] . ' ' : '8px 8px 8px 8px',
				'border-color'        => isset( $attr['ctaBorder']['color']['color'] ) ? $attr['ctaBorder']['color']['color'] : '#dddddd',
				'margin-top'          => isset( $attr['ctaMargin']['desktop']['top'] ) ? $attr['ctaMargin']['desktop']['top'] : '0px',
				'margin-left'         => isset( $attr['ctaMargin']['desktop']['left'] ) ? $attr['ctaMargin']['desktop']['left'] : '0px',
				'margin-right'        => isset( $attr['ctaMargin']['desktop']['right'] ) ? $attr['ctaMargin']['desktop']['right'] : '0px',
				'margin-bottom'       => isset( $attr['ctaMargin']['desktop']['bottom'] ) ? $attr['ctaMargin']['desktop']['bottom'] : '30px',
				'padding-top'         => isset( $attr['ctaBoxPadding']['desktop']['top'] ) ? $attr['ctaBoxPadding']['desktop']['top'] : '60px',
				'padding-left'        => isset( $attr['ctaBoxPadding']['desktop']['left'] ) ? $attr['ctaBoxPadding']['desktop']['left'] : '30px',
				'padding-right'       => isset( $attr['ctaBoxPadding']['desktop']['right'] ) ? $attr['ctaBoxPadding']['desktop']['right'] : '30px',
				'padding-bottom'      => isset( $attr['ctaBoxPadding']['desktop']['bottom'] ) ? $attr['ctaBoxPadding']['desktop']['bottom'] : '60px',
				'box-shadow'          => isset( $attr['ctaBoxShadow'] ) && $attr['ctaBoxShadow']['enable'] ? AffiliateX_Helpers::get_css_boxshadow( $attr['ctaBoxShadow'] ) : AffiliateX_Helpers::get_css_boxshadow( $box_shadow ),

			),

			'.wp-block-affiliatex-cta h2'            => array(
				'color'           => isset( $attr['ctaTitleColor'] ) ? $attr['ctaTitleColor'] : '#262B33',
				'font-family'     => isset( $attr['ctaTitleTypography']['family'] ) ? $attr['ctaTitleTypography']['family'] : $global_font_family,
				'font-weight'     => AffiliateX_Helpers::get_fontweight_variation( $variation ),
				'font-style'      => AffiliateX_Helpers::get_font_style( $variation ),
				'font-size'       => isset( $attr['ctaTitleTypography']['size']['desktop'] ) ? $attr['ctaTitleTypography']['size']['desktop'] : '40px',
				'line-height'     => isset( $attr['ctaTitleTypography']['line-height']['desktop'] ) ? $attr['ctaTitleTypography']['line-height']['desktop'] : '1.5',
				'text-transform'  => isset( $attr['ctaTitleTypography']['text-transform'] ) ? $attr['ctaTitleTypography']['text-transform'] : 'none',
				'text-decoration' => isset( $attr['ctaTitleTypography']['text-decoration'] ) ? $attr['ctaTitleTypography']['text-decoration'] : 'none',
				'letter-spacing'  => isset( $attr['ctaTitleTypography']['letter-spacing']['desktop'] ) ? $attr['ctaTitleTypography']['letter-spacing']['desktop'] : '0em',
				'text-align'      => isset( $attr['contentAlignment'] ) ? $attr['contentAlignment'] : 'center',

			),

			'.wp-block-affiliatex-cta .affliatex-cta-content' => array(
				'color'           => isset( $attr['ctaTextColor'] ) ? $attr['ctaTextColor'] : $global_font_color,
				'font-family'     => isset( $attr['ctaContentTypography']['family'] ) ? $attr['ctaContentTypography']['family'] : $global_font_family,
				'font-weight'     => AffiliateX_Helpers::get_fontweight_variation( $content_variation ),
				'font-style'      => AffiliateX_Helpers::get_font_style( $content_variation ),
				'font-size'       => isset( $attr['ctaContentTypography']['size']['desktop'] ) ? $attr['ctaContentTypography']['size']['desktop'] : '18px',
				'line-height'     => isset( $attr['ctaContentTypography']['line-height']['desktop'] ) ? $attr['ctaContentTypography']['line-height']['desktop'] : '1.5',
				'text-transform'  => isset( $attr['ctaContentTypography']['text-transform'] ) ? $attr['ctaContentTypography']['text-transform'] : 'none',
				'text-decoration' => isset( $attr['ctaContentTypography']['text-decoration'] ) ? $attr['ctaContentTypography']['text-decoration'] : 'none',
				'letter-spacing'  => isset( $attr['ctaContentTypography']['letter-spacing']['desktop'] ) ? $attr['ctaContentTypography']['letter-spacing']['desktop'] : '0em',
				'text-align'      => isset( $attr['contentAlignment'] ) ? $attr['contentAlignment'] : 'center',
			),

			' .img-opacity::before'                   => array(
				'opacity' => isset( $attr['overlayOpacity'] ) ? $attr['overlayOpacity'] : 0.1,
			),

			'.wp-block-affiliatex-cta .layout-type-2' => array(
				'padding-top'    => '0px',
				'padding-left'   => '0px',
				'padding-right'  => '0px',
				'padding-bottom' => '0px',
			),

			'.wp-block-affiliatex-cta .layout-type-2 .content-wrapper' => array(
				'background'     => isset( $attr['ctaBgColorType'] ) && $attr['ctaBgColorType'] === 'gradient' ? $ctaBgGradient : $ctaBGColor,
				'padding-top'    => isset( $attr['ctaBoxPadding']['desktop']['top'] ) ? $attr['ctaBoxPadding']['desktop']['top'] : '60px',
				'padding-left'   => isset( $attr['ctaBoxPadding']['desktop']['left'] ) ? $attr['ctaBoxPadding']['desktop']['left'] : '30px',
				'padding-right'  => isset( $attr['ctaBoxPadding']['desktop']['right'] ) ? $attr['ctaBoxPadding']['desktop']['right'] : '30px',
				'padding-bottom' => isset( $attr['ctaBoxPadding']['desktop']['bottom'] ) ? $attr['ctaBoxPadding']['desktop']['bottom'] : '60px',
			),
			' .button-wrapper'                        => array(
				'justify-content' => isset( $attr['contentAlignment'] ) ? $attr['contentAlignment'] : 'center',
			),
		);

		return $selectors;
	}

	public static function get_mobileselectors( $attr ) {

		$mobile_selectors = array(
			'.wp-block-affiliatex-cta > div'    => array(
				'letter-spacing' => isset( $attr['ctaTitleTypography']['letter-spacing']['mobile'] ) ? $attr['ctaTitleTypography']['letter-spacing']['mobile'] : '0em',
				'border-radius'  => isset( $attr['ctaBorderRadius']['mobile']['top'] ) && isset( $attr['ctaBorderRadius']['mobile']['right'] ) && isset( $attr['ctaBorderRadius']['mobile']['bottom'] ) && isset( $attr['ctaBorderRadius']['mobile']['left'] ) ? $attr['ctaBorderRadius']['mobile']['top'] . ' ' . $attr['ctaBorderRadius']['mobile']['right'] . ' ' . $attr['ctaBorderRadius']['mobile']['bottom'] . ' ' . $attr['ctaBorderRadius']['mobile']['left'] . ' ' : '8px 8px 8px 8px',
				'font-size'      => isset( $attr['ctaTitleTypography']['size']['mobile'] ) ? $attr['ctaTitleTypography']['size']['mobile'] : '40px',
				'line-height'    => isset( $attr['ctaTitleTypography']['line-height']['mobile'] ) ? $attr['ctaTitleTypography']['line-height']['mobile'] : '1.5',
				'margin-top'     => isset( $attr['ctaMargin']['mobile']['top'] ) ? $attr['ctaMargin']['mobile']['top'] : '0px',
				'margin-left'    => isset( $attr['ctaMargin']['mobile']['left'] ) ? $attr['ctaMargin']['mobile']['left'] : '0px',
				'margin-right'   => isset( $attr['ctaMargin']['mobile']['right'] ) ? $attr['ctaMargin']['mobile']['right'] : '0px',
				'margin-bottom'  => isset( $attr['ctaMargin']['mobile']['bottom'] ) ? $attr['ctaMargin']['mobile']['bottom'] : '30px',
				'padding-top'    => isset( $attr['ctaBoxPadding']['mobile']['top'] ) ? $attr['ctaBoxPadding']['mobile']['top'] : '60px',
				'padding-left'   => isset( $attr['ctaBoxPadding']['mobile']['left'] ) ? $attr['ctaBoxPadding']['mobile']['left'] : '30px',
				'padding-right'  => isset( $attr['ctaBoxPadding']['mobile']['right'] ) ? $attr['ctaBoxPadding']['mobile']['right'] : '30px',
				'padding-bottom' => isset( $attr['ctaBoxPadding']['mobile']['bottom'] ) ? $attr['ctaBoxPadding']['mobile']['bottom'] : '60px',
			),
			'.wp-block-affiliatex-cta .affliatex-cta-content' => array(
				'letter-spacing' => isset( $attr['ctaContentTypography']['letter-spacing']['mobile'] ) ? $attr['ctaContentTypography']['letter-spacing']['mobile'] : '0em',
				'font-size'      => isset( $attr['ctaContentTypography']['size']['mobile'] ) ? $attr['ctaContentTypography']['size']['mobile'] : '18px',
				'line-height'    => isset( $attr['ctaContentTypography']['line-height']['mobile'] ) ? $attr['ctaContentTypography']['line-height']['mobile'] : '1.5',
			),
			'.wp-block-affiliatex-cta h2' => array(
				'letter-spacing' => isset( $attr['ctaTitleTypography']['letter-spacing']['mobile'] ) ? $attr['ctaTitleTypography']['letter-spacing']['mobile'] : '0em',
				'font-size'      => isset( $attr['ctaTitleTypography']['size']['mobile'] ) ? $attr['ctaTitleTypography']['size']['mobile'] : '40px',
				'line-height'    => isset( $attr['ctaTitleTypography']['line-height']['mobile'] ) ? $attr['ctaTitleTypography']['line-height']['mobile'] : '1.5',
			),

			'.wp-block-affiliatex-cta .affliatex-cta-content' => array(
				'letter-spacing' => isset( $attr['ctaContentTypography']['letter-spacing']['mobile'] ) ? $attr['ctaContentTypography']['letter-spacing']['mobile'] : '0em',
				'font-size'      => isset( $attr['ctaContentTypography']['size']['mobile'] ) ? $attr['ctaContentTypography']['size']['mobile'] : '18px',
				'line-height'    => isset( $attr['ctaContentTypography']['line-height']['mobile'] ) ? $attr['ctaContentTypography']['line-height']['mobile'] : '1.5',
			),

			'.wp-block-affiliatex-cta .layout-type-2 .content-wrapper' => array(
				'padding-top'    => isset( $attr['ctaBoxPadding']['mobile']['top'] ) ? $attr['ctaBoxPadding']['mobile']['top'] : '60px',
				'padding-left'   => isset( $attr['ctaBoxPadding']['mobile']['left'] ) ? $attr['ctaBoxPadding']['mobile']['left'] : '30px',
				'padding-right'  => isset( $attr['ctaBoxPadding']['mobile']['right'] ) ? $attr['ctaBoxPadding']['mobile']['right'] : '30px',
				'padding-bottom' => isset( $attr['ctaBoxPadding']['mobile']['bottom'] ) ? $attr['ctaBoxPadding']['mobile']['bottom'] : '60px',
			),
		);
		return $mobile_selectors;
	}

	public static function get_tabletselectors( $attr ) {

		$tablet_selectors = array(
			'.wp-block-affiliatex-cta > div'    => array(
				'letter-spacing' => isset( $attr['ctaTitleTypography']['letter-spacing']['tablet'] ) ? $attr['ctaTitleTypography']['letter-spacing']['tablet'] : '0em',
				'border-radius'  => isset( $attr['ctaBorderRadius']['tablet']['top'] ) && isset( $attr['ctaBorderRadius']['tablet']['right'] ) && isset( $attr['ctaBorderRadius']['tablet']['bottom'] ) && isset( $attr['ctaBorderRadius']['tablet']['left'] ) ? $attr['ctaBorderRadius']['tablet']['top'] . ' ' . $attr['ctaBorderRadius']['tablet']['right'] . ' ' . $attr['ctaBorderRadius']['tablet']['bottom'] . ' ' . $attr['ctaBorderRadius']['tablet']['left'] . ' ' : '8px 8px 8px 8px',
				'font-size'      => isset( $attr['ctaTitleTypography']['size']['tablet'] ) ? $attr['ctaTitleTypography']['size']['tablet'] : '40px',
				'line-height'    => isset( $attr['ctaTitleTypography']['line-height']['tablet'] ) ? $attr['ctaTitleTypography']['line-height']['tablet'] : '1.5',
				'margin-top'     => isset( $attr['ctaMargin']['tablet']['top'] ) ? $attr['ctaMargin']['tablet']['top'] : '0px',
				'margin-left'    => isset( $attr['ctaMargin']['tablet']['left'] ) ? $attr['ctaMargin']['tablet']['left'] : '0px',
				'margin-right'   => isset( $attr['ctaMargin']['tablet']['right'] ) ? $attr['ctaMargin']['tablet']['right'] : '0px',
				'margin-bottom'  => isset( $attr['ctaMargin']['tablet']['bottom'] ) ? $attr['ctaMargin']['tablet']['bottom'] : '30px',
				'padding-top'    => isset( $attr['ctaBoxPadding']['tablet']['top'] ) ? $attr['ctaBoxPadding']['tablet']['top'] : '60px',
				'padding-left'   => isset( $attr['ctaBoxPadding']['tablet']['left'] ) ? $attr['ctaBoxPadding']['tablet']['left'] : '30px',
				'padding-right'  => isset( $attr['ctaBoxPadding']['tablet']['right'] ) ? $attr['ctaBoxPadding']['tablet']['right'] : '30px',
				'padding-bottom' => isset( $attr['ctaBoxPadding']['tablet']['bottom'] ) ? $attr['ctaBoxPadding']['tablet']['bottom'] : '60px',
			),
			'.wp-block-affiliatex-cta .affliatex-cta-content' => array(
				'letter-spacing' => isset( $attr['ctaContentTypography']['letter-spacing']['tablet'] ) ? $attr['ctaContentTypography']['letter-spacing']['tablet'] : '0em',
				'font-size'      => isset( $attr['ctaContentTypography']['size']['tablet'] ) ? $attr['ctaContentTypography']['size']['tablet'] : '18px',
				'line-height'    => isset( $attr['ctaContentTypography']['line-height']['tablet'] ) ? $attr['ctaContentTypography']['line-height']['tablet'] : '1.5',
			),
			'.wp-block-affiliatex-cta h2' => array(
				'letter-spacing' => isset( $attr['ctaTitleTypography']['letter-spacing']['tablet'] ) ? $attr['ctaTitleTypography']['letter-spacing']['tablet'] : '0em',
				'font-size'      => isset( $attr['ctaTitleTypography']['size']['tablet'] ) ? $attr['ctaTitleTypography']['size']['tablet'] : '40px',
				'line-height'    => isset( $attr['ctaTitleTypography']['line-height']['tablet'] ) ? $attr['ctaTitleTypography']['line-height']['tablet'] : '1.5',
			),

			'.wp-block-affiliatex-cta .affliatex-cta-content' => array(
				'letter-spacing' => isset( $attr['ctaContentTypography']['letter-spacing']['tablet'] ) ? $attr['ctaContentTypography']['letter-spacing']['tablet'] : '0em',
				'font-size'      => isset( $attr['ctaContentTypography']['size']['tablet'] ) ? $attr['ctaContentTypography']['size']['tablet'] : '18px',
				'line-height'    => isset( $attr['ctaContentTypography']['line-height']['tablet'] ) ? $attr['ctaContentTypography']['line-height']['tablet'] : '1.5',
			),

			'.wp-block-affiliatex-cta .layout-type-2 .content-wrapper' => array(
				'padding-top'    => isset( $attr['ctaBoxPadding']['tablet']['top'] ) ? $attr['ctaBoxPadding']['tablet']['top'] : '60px',
				'padding-left'   => isset( $attr['ctaBoxPadding']['tablet']['left'] ) ? $attr['ctaBoxPadding']['tablet']['left'] : '30px',
				'padding-right'  => isset( $attr['ctaBoxPadding']['tablet']['right'] ) ? $attr['ctaBoxPadding']['tablet']['right'] : '30px',
				'padding-bottom' => isset( $attr['ctaBoxPadding']['tablet']['bottom'] ) ? $attr['ctaBoxPadding']['tablet']['bottom'] : '60px',
			),
		);
		return $tablet_selectors;
	}

}
