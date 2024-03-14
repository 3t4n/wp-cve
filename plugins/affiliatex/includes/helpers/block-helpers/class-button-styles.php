<?php
/**
 * Button Block Styles
 *
 * @package AffiliateX
 */

class AffiliateX_Button_Styles {

	public static function block_fonts( $attr ) {
		return array( 'buttonTypography' => isset( $attr['buttonTypography'] ) ? $attr['buttonTypography'] : array() );
	}

	public static function block_css( $attr, $id ) {
		$selectors = self::get_selectors( $attr );

		$m_selectors = self::get_mobileselectors( $attr );

		$t_selectors = self::get_tabletselectors( $attr );

		$desktop = AffiliateX_Helpers::generate_css( $selectors, '#affiliatex-blocks-style-' . $id );

		$tablet = AffiliateX_Helpers::generate_css( $t_selectors, '#affiliatex-blocks-style-' . $id );

		$mobile = AffiliateX_Helpers::generate_css( $m_selectors, '#affiliatex-blocks-style-' . $id );

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
		$global_btn_color       = isset( $customization_data['btnColor'] ) ? $customization_data['btnColor'] : '#2670FF';
		$global_btn_hover_color = isset( $customization_data['btnHoverColor'] ) ? $customization_data['btnHoverColor'] : '#084ACA';

		$bgType           = isset( $attr['buttonBGType'] ) ? $attr['buttonBGType'] : 'solid';
		$buttonBgGradient = isset( $attr['buttonBgGradient']['gradient'] ) ? $attr['buttonBgGradient']['gradient'] : 'linear-gradient(135deg,rgb(238,238,238) 0%,rgb(169,184,195) 100%)';
		$buttonBGColor    = isset( $attr['buttonBGColor'] ) ? $attr['buttonBGColor'] : $global_btn_color;
		$variation        = isset( $attr['buttonTypography']['variation'] ) ? $attr['buttonTypography']['variation'] : 'n4';

		$selectors = array(
			' .affiliatex-button'                    => array(
				'font-family'      => isset( $attr['buttonTypography']['family'] ) ? $attr['buttonTypography']['family'] : $global_font_family,
				'font-size'        => isset( $attr['buttonTypography']['size']['desktop'] ) ? $attr['buttonTypography']['size']['desktop'] : '18px',
				'line-height'      => isset( $attr['buttonTypography']['line-height']['desktop'] ) ? $attr['buttonTypography']['line-height']['desktop'] : '1.65',
				'text-transform'   => isset( $attr['buttonTypography']['text-transform'] ) ? $attr['buttonTypography']['text-transform'] : 'none',
				'text-decoration'  => isset( $attr['buttonTypography']['text-decoration'] ) ? $attr['buttonTypography']['text-decoration'] : 'none',
				'letter-spacing'   => isset( $attr['buttonTypography']['letter-spacing']['desktop'] ) ? $attr['buttonTypography']['letter-spacing']['desktop'] : '0em',
				'margin-top'       => isset( $attr['buttonMargin']['desktop']['top'] ) ? $attr['buttonMargin']['desktop']['top'] : '0px',
				'margin-left'      => isset( $attr['buttonMargin']['desktop']['left'] ) ? $attr['buttonMargin']['desktop']['left'] : '0px',
				'margin-right'     => isset( $attr['buttonMargin']['desktop']['right'] ) ? $attr['buttonMargin']['desktop']['right'] : '0px',
				'margin-bottom'    => isset( $attr['buttonMargin']['desktop']['bottom'] ) ? $attr['buttonMargin']['desktop']['bottom'] : '30px',
				'padding-top'      => isset( $attr['buttonPadding']['desktop']['top'] ) ? $attr['buttonPadding']['desktop']['top'] : '',
				'padding-left'     => isset( $attr['buttonPadding']['desktop']['left'] ) ? $attr['buttonPadding']['desktop']['left'] : '',
				'padding-right'    => isset( $attr['buttonPadding']['desktop']['right'] ) ? $attr['buttonPadding']['desktop']['right'] : '',
				'padding-bottom'   => isset( $attr['buttonPadding']['desktop']['bottom'] ) ? $attr['buttonPadding']['desktop']['bottom'] : '',
				'border-style'     => isset( $attr['buttonBorder']['style'] ) ? $attr['buttonBorder']['style'] : 'none',
				'border-width'     => isset( $attr['buttonBorder']['width'] ) ? $attr['buttonBorder']['width'] . 'px' : '1px',
				'border-color'     => isset( $attr['buttonBorder']['color']['color'] ) ? $attr['buttonBorder']['color']['color'] : '#dddddd',
				'color'            => isset( $attr['buttonTextColor'] ) ? $attr['buttonTextColor'] : '#ffffff',
				'background-color' => $buttonBGColor,
				'background'       => $bgType && $bgType === 'solid' ? $buttonBGColor : $buttonBgGradient,
				'box-shadow'       => isset( $attr['buttonShadow'] ) && $attr['buttonShadow']['enable'] ? AffiliateX_Helpers::get_css_boxshadow( $attr['buttonShadow'] ) : 'none',
				'font-weight'      => AffiliateX_Helpers::get_fontweight_variation( $variation ),
				'font-style'       => AffiliateX_Helpers::get_font_style( $variation ),
				'border-radius'    => isset( $attr['buttonRadius']['desktop']['top'] ) && isset( $attr['buttonRadius']['desktop']['right'] ) && isset( $attr['buttonRadius']['desktop']['bottom'] ) && isset( $attr['buttonRadius']['desktop']['left'] ) ? $attr['buttonRadius']['desktop']['top'] . ' ' . $attr['buttonRadius']['desktop']['right'] . ' ' . $attr['buttonRadius']['desktop']['bottom'] . ' ' . $attr['buttonRadius']['desktop']['left'] . ' ' : '0 0 0 0',

			),
			' .btn-is-fixed'                         => array(
				'max-width' => isset( $attr['buttonFixWidth'] ) ? $attr['buttonFixWidth'] : '100px',
				'width'     => '100%',
			),
			' .affx-btn-inner'                       => array(
				'justify-content' => isset( $attr['buttonAlignment'] ) ? $attr['buttonAlignment'] : 'flex-start',
			),
			' .affiliatex-button:hover'              => array(
				'color'        => isset( $attr['buttonTextHoverColor'] ) ? $attr['buttonTextHoverColor'] : '#ffffff',
				'background'   => isset( $attr['buttonBGHoverColor'] ) ? $attr['buttonBGHoverColor'] : $global_btn_hover_color,
				'border-color' => isset( $attr['buttonborderHoverColor'] ) ? $attr['buttonborderHoverColor'] : '#ffffff',
			),
			' .button-icon'                          => array(
				'font-size' => isset( $attr['buttonIconSize'] ) ? $attr['buttonIconSize'] : '18px',
				'color'     => isset( $attr['buttonIconColor'] ) ? $attr['buttonIconColor'] : '#ffffff',
			),
			' .affiliatex-button:hover .button-icon' => array(
				'color' => isset( $attr['buttonIconHoverColor'] ) ? $attr['buttonIconHoverColor'] : '#ffffff',
			),
			' .affiliatex-button .price-tag'         => array(
				'color'                        => isset( $attr['priceTextColor'] ) ? $attr['priceTextColor'] : '#2670FF',
				'background-color'             => isset( $attr['priceBackgroundColor'] ) ? $attr['priceBackgroundColor'] : '#ffff',
				'--border-top-left-radius'     => isset( $attr['buttonRadius']['desktop']['top'] ) ? $attr['buttonRadius']['desktop']['top'] : '0px',
				'--border-top-right-radius'    => isset( $attr['buttonRadius']['desktop']['right'] ) ? $attr['buttonRadius']['desktop']['right'] : '0px',
				'--border-bottom-right-radius' => isset( $attr['buttonRadius']['desktop']['bottom'] ) ? $attr['buttonRadius']['desktop']['bottom'] : '0px',
				'--border-bottom-left-radius'  => isset( $attr['buttonRadius']['desktop']['left'] ) ? $attr['buttonRadius']['desktop']['left'] : '0px',
			),
			' .affiliatex-button .price-tag::before' => array(
				'background-color' => isset( $attr['priceBackgroundColor'] ) ? $attr['priceBackgroundColor'] : '#ffff',
			),
		);

		return $selectors;
	}

	public static function get_mobileselectors( $attr ) {

		$mobile_selectors = array(
			' .affiliatex-button'            => array(
				'font-size'      => isset( $attr['buttonTypography']['size']['mobile'] ) ? $attr['buttonTypography']['size']['mobile'] : '18px',
				'line-height'    => isset( $attr['buttonTypography']['line-height']['mobile'] ) ? $attr['buttonTypography']['line-height']['mobile'] : '1.65',
				'letter-spacing' => isset( $attr['buttonTypography']['letter-spacing']['mobile'] ) ? $attr['buttonTypography']['letter-spacing']['mobile'] : '0em',
				'margin-top'     => isset( $attr['buttonMargin']['mobile']['top'] ) ? $attr['buttonMargin']['mobile']['top'] : '0px',
				'margin-left'    => isset( $attr['buttonMargin']['mobile']['left'] ) ? $attr['buttonMargin']['mobile']['left'] : '0px',
				'margin-right'   => isset( $attr['buttonMargin']['mobile']['right'] ) ? $attr['buttonMargin']['mobile']['right'] : '0px',
				'margin-bottom'  => isset( $attr['buttonMargin']['mobile']['bottom'] ) ? $attr['buttonMargin']['mobile']['bottom'] : '30px',
				'padding-top'    => isset( $attr['buttonPadding']['mobile']['top'] ) ? $attr['buttonPadding']['mobile']['top'] : '',
				'padding-left'   => isset( $attr['buttonPadding']['mobile']['left'] ) ? $attr['buttonPadding']['mobile']['left'] : '',
				'padding-right'  => isset( $attr['buttonPadding']['mobile']['right'] ) ? $attr['buttonPadding']['mobile']['right'] : '',
				'padding-bottom' => isset( $attr['buttonPadding']['mobile']['bottom'] ) ? $attr['buttonPadding']['mobile']['bottom'] : '',
				'border-radius'  => isset( $attr['buttonRadius']['mobile']['top'] ) && isset( $attr['buttonRadius']['mobile']['right'] ) && isset( $attr['buttonRadius']['mobile']['bottom'] ) && isset( $attr['buttonRadius']['mobile']['left'] ) ? $attr['buttonRadius']['mobile']['top'] . ' ' . $attr['buttonRadius']['mobile']['right'] . ' ' . $attr['buttonRadius']['mobile']['bottom'] . ' ' . $attr['buttonRadius']['mobile']['left'] . ' ' : '0 0 0 0',

			),
			' .affiliatex-button .price-tag' => array(
				'--border-top-left-radius'     => isset( $attr['buttonRadius']['mobile']['top'] ) ? $attr['buttonRadius']['mobile']['top'] : '0px',
				'--border-top-right-radius'    => isset( $attr['buttonRadius']['mobile']['right'] ) ? $attr['buttonRadius']['mobile']['right'] : '0px',
				'--border-bottom-right-radius' => isset( $attr['buttonRadius']['mobile']['bottom'] ) ? $attr['buttonRadius']['mobile']['bottom'] : '0px',
				'--border-bottom-left-radius'  => isset( $attr['buttonRadius']['mobile']['left'] ) ? $attr['buttonRadius']['mobile']['left'] : '0px',
			),
		);

		return $mobile_selectors;
	}

	public static function get_tabletselectors( $attr ) {

		$tablet_selectors = array(
			' .affiliatex-button'            => array(
				'font-size'      => isset( $attr['buttonTypography']['size']['tablet'] ) ? $attr['buttonTypography']['size']['tablet'] : '18px',
				'line-height'    => isset( $attr['buttonTypography']['line-height']['tablet'] ) ? $attr['buttonTypography']['line-height']['tablet'] : '1.65',
				'letter-spacing' => isset( $attr['buttonTypography']['letter-spacing']['tablet'] ) ? $attr['buttonTypography']['letter-spacing']['tablet'] : '0em',
				'margin-top'     => isset( $attr['buttonMargin']['tablet']['top'] ) ? $attr['buttonMargin']['tablet']['top'] : '0px',
				'margin-left'    => isset( $attr['buttonMargin']['tablet']['left'] ) ? $attr['buttonMargin']['tablet']['left'] : '0px',
				'margin-right'   => isset( $attr['buttonMargin']['tablet']['right'] ) ? $attr['buttonMargin']['tablet']['right'] : '0px',
				'margin-bottom'  => isset( $attr['buttonMargin']['tablet']['bottom'] ) ? $attr['buttonMargin']['tablet']['bottom'] : '30px',
				'padding-top'    => isset( $attr['buttonPadding']['tablet']['top'] ) ? $attr['buttonPadding']['tablet']['top'] : '',
				'padding-left'   => isset( $attr['buttonPadding']['tablet']['left'] ) ? $attr['buttonPadding']['tablet']['left'] : '',
				'padding-right'  => isset( $attr['buttonPadding']['tablet']['right'] ) ? $attr['buttonPadding']['tablet']['right'] : '',
				'padding-bottom' => isset( $attr['buttonPadding']['tablet']['bottom'] ) ? $attr['buttonPadding']['tablet']['bottom'] : '',
				'border-radius'  => isset( $attr['buttonRadius']['tablet']['top'] ) && isset( $attr['buttonRadius']['tablet']['right'] ) && isset( $attr['buttonRadius']['tablet']['bottom'] ) && isset( $attr['buttonRadius']['tablet']['left'] ) ? $attr['buttonRadius']['tablet']['top'] . ' ' . $attr['buttonRadius']['tablet']['right'] . ' ' . $attr['buttonRadius']['tablet']['bottom'] . ' ' . $attr['buttonRadius']['tablet']['left'] . ' ' : '0 0 0 0',

			),
			' .affiliatex-button .price-tag' => array(
				'--border-top-left-radius'     => isset( $attr['buttonRadius']['tablet']['top'] ) ? $attr['buttonRadius']['tablet']['top'] : '0px',
				'--border-top-right-radius'    => isset( $attr['buttonRadius']['tablet']['right'] ) ? $attr['buttonRadius']['tablet']['right'] : '0px',
				'--border-bottom-right-radius' => isset( $attr['buttonRadius']['tablet']['bottom'] ) ? $attr['buttonRadius']['tablet']['bottom'] : '0px',
				'--border-bottom-left-radius'  => isset( $attr['buttonRadius']['tablet']['left'] ) ? $attr['buttonRadius']['tablet']['left'] : '0px',
			),
		);

		return $tablet_selectors;
	}

}
