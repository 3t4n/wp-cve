<?php
/**
 * Css handling logic for group.
 *
 * @package CoolPlugins\GutenbergBlocks\CSS\Blocks
 */

namespace CoolPlugins\GutenbergBlocks\CSS\Blocks;

use CoolPlugins\GutenbergBlocks\Cfb_CSS_Base;

use CoolPlugins\GutenbergBlocks\CSS\CSS_Utility;

/**
 * Class Flip_CSS
 */
class Flip_CSS extends Cfb_CSS_Base {

	/**
	 * The namespace under which the blocks are registered.
	 *
	 * @var string
	 */
	public $block_prefix = 'cool-flipbox-block';

	/**
	 * Generate Button CSS
	 *
	 * @param mixed $block Block data.
	 * @return string
	 * @since   1.3.0
	 * @access  public
	 */
	public function render_css( $block ) {
		if ( isset( $block['attrs']['id'] ) ) {
			$this->get_google_fonts( $block['attrs'] );
			$this->font_awesome_library( $block['attrs'] );
		}

		$css = new CSS_Utility(
			$block
		);

		$css->add_item(
			array(
				'properties' => array(
					array(
						'property'  => '--cfb-block-width',
						'value'     => 'width',
						'unit'      => 'px',
						'condition' => function( $attrs ) {
							return isset( $attrs['width'] ) && is_numeric( $attrs['width'] );
						},
					),
					array(
						'property'  => '--cfb-block-width',
						'value'     => 'width',
						'condition' => function( $attrs ) {
							return isset( $attrs['width'] ) && is_string( $attrs['width'] );
						},
					),
					array(
						'property' => '--cfb-block-width',
						'value'    => 'widthTablet',
						'media'    => 'tablet',
					),
					array(
						'property' => '--cfb-block-width',
						'value'    => 'widthMobile',
						'media'    => 'mobile',
					),
					array(
						'property'  => '--cfb-block-height',
						'value'     => 'height',
						'unit'      => 'px',
						'condition' => function( $attrs ) {
							return isset( $attrs['height'] ) && is_numeric( $attrs['height'] );
						},
					),
					array(
						'property'  => '--cfb-block-height',
						'value'     => 'height',
						'condition' => function( $attrs ) {
							return isset( $attrs['height'] ) && is_string( $attrs['height'] );
						},
					),
					array(
						'property' => '--cfb-block-height',
						'value'    => 'heightTablet',
						'media'    => 'tablet',
					),
					array(
						'property' => '--cfb-block-height',
						'value'    => 'heightMobile',
						'media'    => 'mobile',
					),
					array(
						'property' => '--cfb-block-border-color',
						'value'    => 'borderColor',
					),
					array(
						'property'  => '--cfb-block-border-width',
						'value'     => 'borderWidth',
						'unit'      => 'px',
						'condition' => function( $attrs ) {
							return isset( $attrs['borderWidth'] ) && is_numeric( $attrs['borderWidth'] );
						},
					),
					array(
						'property'  => '--cfb-block-border-width',
						'value'     => 'borderWidth',
						'condition' => function( $attrs ) {
							return isset( $attrs['borderWidth'] ) && is_array( $attrs['borderWidth'] );
						},
						'format'    => function( $value, $attrs ) {
							return CSS_Utility::box_values( $value, CSS_Utility::make_box( '1px' ) );
						},
					),
					array(
						'property'  => '--cfb-block-border-radius',
						'value'     => 'borderRadius',
						'unit'      => 'px',
						'condition' => function( $attrs ) {
							return isset( $attrs['borderRadius'] ) && is_numeric( $attrs['borderRadius'] );
						},
					),
					array(
						'property'  => '--cfb-block-border-radius',
						'value'     => 'borderRadius',
						'condition' => function( $attrs ) {
							return isset( $attrs['borderRadius'] ) && is_array( $attrs['borderRadius'] );
						},
						'format'    => function( $value, $attrs ) {
							return CSS_Utility::box_values( $value, CSS_Utility::make_box( '10px' ) );
						},
					),
					array(
						'property'  => '--cfb-block-front-background',
						'value'     => 'frontBackgroundColor',
						'condition' => function( $attrs ) {
							return ! isset( $attrs['frontBackgroundType'] );
						},
					),
					array(
						'property' => '--cfb-block-title-color',
						'value'    => 'titleColor',
					),
					array(
						'property' => '--cfb-block-desc-color',
						'value'    => 'descriptionColor',
					),
					array(
						'property'  => '--cfb-block-front-background',
						'value'     => 'frontBackgroundGradient',
						'condition' => function( $attrs ) {
							return isset( $attrs['frontBackgroundType'] ) && 'gradient' === $attrs['frontBackgroundType'];
						},
					),
					array(
						'property'       => '--cfb-block-front-background',
						'pattern'        => 'url( imageURL ) repeat attachment position/size',
						'pattern_values' => array(
							'imageURL'   => array(
								'value'  => 'frontBackgroundImage',
								'format' => function( $value, $attrs ) {
									return apply_filters( 'cfb_apply_dynamic_image', $value['url'] );
								},
							),
							'repeat'     => array(
								'value'   => 'frontBackgroundRepeat',
								'default' => 'repeat',
							),
							'attachment' => array(
								'value'   => 'frontBackgroundAttachment',
								'default' => 'scroll',
							),
							'position'   => array(
								'value'   => 'frontBackgroundPosition',
								'default' => array(
									'x' => 0.5,
									'y' => 0.5,
								),
								'format'  => function( $value, $attrs ) {
									if ( isset( $value['x'] ) && isset( $value['y'] ) ) {
										return ( $value['x'] * 100 ) . '% ' . ( $value['y'] * 100 ) . '%';
									}
									return '50% 50%';
								},
							),
							'size'       => array(
								'value'   => 'frontBackgroundSize',
								'default' => 'auto',
							),
						),
						'condition'      => function( $attrs ) {
							return isset( $attrs['frontBackgroundType'] ) && 'image' === $attrs['frontBackgroundType'] && isset( $attrs['frontBackgroundImage'] ) && isset( $attrs['frontBackgroundImage']['url'] );
						},
					),
					array(
						'property' => '--cfb-block-front-vertical-align',
						'value'    => 'frontVerticalAlign',
					),
					array(
						'property' => '--cfb-block-front-horizontal-align',
						'value'    => 'frontHorizontalAlign',
					),
					array(
						'property' => '--cfb-block-front-text-align',
						'value'    => 'frontTextAlign',
					),
					array(
						'property' => '--cfb-block-back-vertical-align',
						'value'    => 'backVerticalAlign',
					),
					array(
						'property' => '--cfb-block-back-horizontal-align',
						'value'    => 'backHorizontalAlign',
					),
					array(
						'property' => '--cfb-block-back-text-align',
						'value'    => 'backTextAlign',
					),
					array(
						'property'  => '--cfb-block-back-background',
						'value'     => 'backBackgroundColor',
						'condition' => function( $attrs ) {
							return isset( $attrs['backBackgroundType'] ) && 'color' === $attrs['backBackgroundType'];
						},
					),
					array(
						'property'  => '--cfb-block-back-background',
						'value'     => 'backBackgroundGradient',
						'condition' => function( $attrs ) {
							return ! isset( $attrs['backBackgroundType'] );
						},
					),
					array(
						'property'       => '--cfb-block-back-background',
						'pattern'        => 'url( imageURL ) repeat attachment position/size',
						'pattern_values' => array(
							'imageURL'   => array(
								'value'  => 'backBackgroundImage',
								'format' => function( $value, $attrs ) {
									return apply_filters( 'cfb_apply_dynamic_image', $value['url'] );
								},
							),
							'repeat'     => array(
								'value'   => 'backBackgroundRepeat',
								'default' => 'repeat',
							),
							'attachment' => array(
								'value'   => 'backBackgroundAttachment',
								'default' => 'scroll',
							),
							'position'   => array(
								'value'   => 'backBackgroundPosition',
								'default' => array(
									'x' => 0.5,
									'y' => 0.5,
								),
								'format'  => function( $value, $attrs ) {
									if ( isset( $value['x'] ) && isset( $value['y'] ) ) {
										return ( $value['x'] * 100 ) . '% ' . ( $value['y'] * 100 ) . '%';
									}
									return '50% 50%';
								},
							),
							'size'       => array(
								'value'   => 'backBackgroundSize',
								'default' => 'auto',
							),
						),
						'condition'      => function( $attrs ) {
							return isset( $attrs['backBackgroundType'] ) && 'image' === $attrs['backBackgroundType'] && isset( $attrs['backBackgroundImage'] ) && isset( $attrs['backBackgroundImage']['url'] );
						},
					),
					array(
						'property' => '--cfb-block-back-vertical-align',
						'value'    => 'backVerticalAlign',
					),
					array(
						'property'  => '--cfb-block-padding',
						'value'     => 'padding',
						'unit'      => 'px',
						'condition' => function( $attrs ) {
							return isset( $attrs['padding'] ) && is_numeric( $attrs['padding'] );
						},
					),
					array(
						'property'  => '--cfb-block-padding',
						'value'     => 'padding',
						'condition' => function( $attrs ) {
							return isset( $attrs['padding'] ) && is_array( $attrs['padding'] );
						},
						'format'    => function( $value, $attrs ) {
							return CSS_Utility::box_values( $value, CSS_Utility::make_box( '20px' ) );
						},
					),
					array(
						'property' => '--cfb-block-padding',
						'value'    => 'paddingTablet',
						'format'   => function( $value, $attrs ) {
							return CSS_Utility::render_box(
								CSS_Utility::merge_views(
									CSS_Utility::make_box( '20px' ),
									isset( $attrs['padding'] ) && is_array( $attrs['padding'] ) ? $attrs['padding'] : array(),
									$value
								)
							);
						},
						'media'    => 'tablet',
					),
					array(
						'property' => '--cfb-block-padding',
						'value'    => 'paddingMobile',
						'format'   => function( $value, $attrs ) {
							return CSS_Utility::render_box(
								CSS_Utility::merge_views(
									CSS_Utility::make_box( '20px' ),
									isset( $attrs['padding'] ) && is_array( $attrs['padding'] ) ? $attrs['padding'] : array(),
									isset( $attrs['paddingTablet'] ) ? $attrs['paddingTablet'] : array(),
									$value
								)
							);
						},
						'media'    => 'mobile',
					),
					array(
						'property'       => '--cfb-block-box-shadow',
						'pattern'        => 'horizontal vertical blur color',
						'pattern_values' => array(
							'horizontal' => array(
								'value'   => 'boxShadowHorizontal',
								'unit'    => 'px',
								'default' => 0,
							),
							'vertical'   => array(
								'value'   => 'boxShadowVertical',
								'unit'    => 'px',
								'default' => 0,
							),
							'blur'       => array(
								'value'   => 'boxShadowBlur',
								'unit'    => 'px',
								'default' => 5,
							),
							'color'      => array(
								'value'   => 'boxShadowColor',
								'default' => '#000',
								'format'  => function( $value, $attrs ) {
									$opacity = ( isset( $attrs['boxShadowColorOpacity'] ) ? $attrs['boxShadowColorOpacity'] : 50 );
									return ( strpos( $value, '#' ) !== false && $opacity < 100 ) ? Cfb_CSS_Base::hex2rgba( $value, $opacity / 100 ) : $value;
								},
							),
						),
						'condition'      => function( $attrs ) {
							return isset( $attrs['boxShadow'] );
						},
					),
					array(
						'property'  => '--cfb-block-front-media-width',
						'value'     => 'frontMediaWidth',
						'unit'      => 'px',
						'condition' => function( $attrs ) {
							return isset( $attrs['--cfb-block-front-media-width'] ) && is_numeric( $attrs['--cfb-block-front-media-width'] );
						},
					),
					array(
						'property'  => '--cfb-block-front-media-height',
						'value'     => 'frontMediaHeight',
						'unit'      => 'px',
						'condition' => function( $attrs ) {
							return isset( $attrs['--cfb-block-front-media-height'] ) && is_numeric( $attrs['--cfb-block-front-media-height'] );
						},
					),
					array(
						'property'  => '--cfb-block-front-media-width',
						'value'     => 'frontMediaWidth',
						'condition' => function( $attrs ) {
							return isset( $attrs['frontMediaWidth'] ) && is_string( $attrs['frontMediaWidth'] );
						},
					),
					array(
						'property'  => '--cfb-block-front-media-height',
						'value'     => 'frontMediaHeight',
						'condition' => function( $attrs ) {
							return isset( $attrs['frontMediaHeight'] ) && is_string( $attrs['frontMediaHeight'] );
						},
					),
					array(
						'property'  => '--cfb-block-back-media-width',
						'value'     => 'backMediaWidth',
						'unit'      => 'px',
						'condition' => function( $attrs ) {
							return isset( $attrs['--cfb-block-back-media-width'] ) && is_numeric( $attrs['--cfb-block-back-media-width'] );
						},
					),
					array(
						'property'  => '--cfb-block-back-media-height',
						'value'     => 'backMediaHeight',
						'unit'      => 'px',
						'condition' => function( $attrs ) {
							return isset( $attrs['--cfb-block-back-media-height'] ) && is_numeric( $attrs['--cfb-block-back-media-height'] );
						},
					),
					array(
						'property'  => '--cfb-block-back-media-width',
						'value'     => 'backMediaWidth',
						'condition' => function( $attrs ) {
							return isset( $attrs['backMediaWidth'] ) && is_string( $attrs['backMediaWidth'] );
						},
					),
					array(
						'property'  => '--cfb-block-back-media-height',
						'value'     => 'backMediaHeight',
						'condition' => function( $attrs ) {
							return isset( $attrs['backMediaHeight'] ) && is_string( $attrs['backMediaHeight'] );
						},
					),
					array(
						'property'  => '--cfb-block-front-icon-size',
						'value'     => 'frontIconSize',
						'unit'      => 'px',
						'condition' => function( $attrs ) {
							return isset( $attrs['--cfb-block-front-icon-size'] ) && is_numeric( $attrs['--cfb-block-front-icon-size'] );
						},
					),
					array(
						'property'  => '--cfb-block-front-icon-size',
						'value'     => 'frontIconSize',
						'condition' => function( $attrs ) {
							return isset( $attrs['frontIconSize'] ) && is_string( $attrs['frontIconSize'] );
						},
					),
					array(
						'property'  => '--cfb-block-back-icon-size',
						'value'     => 'backIconSize',
						'unit'      => 'px',
						'condition' => function( $attrs ) {
							return isset( $attrs['--cfb-block-back-icon-size'] ) && is_numeric( $attrs['--cfb-block-back-icon-size'] );
						},
					),
					array(
						'property'  => '--cfb-block-back-icon-size',
						'value'     => 'backIconSize',
						'condition' => function( $attrs ) {
							return isset( $attrs['backIconSize'] ) && is_string( $attrs['backIconSize'] );
						},
					),
					array(
						'property' => '--cfb-block-front-icon-color',
						'value'    => 'frontIconColor',
					),
					array(
						'property' => '--cfb-block-back-icon-color',
						'value'    => 'backIconColor',
					),
				),
			)
		);

		$css->add_item(
			array(
				'selector'   => ' .cfb-block-flip-front .cfb-block-front-title',
				'properties' => array(
					array(
						'property' => '--cfb-block-font-size',
						'value'    => 'frontTitleFontSize',
						'format'   => function( $value, $attrs ) {
							return is_numeric( $value ) ? $value . 'px' : $value;
						},
					),
					array(
						'property' => '--cfb-block-font-family',
						'value'    => 'frontTitleFontFamily',
					),
					array(
						'property' => '--cfb-block-font-weight',
						'value'    => 'frontTitleFontWeight',
					),
					array(
						'property' => '--cfb-block-line-height',
						'value'    => 'frontTitleLineHeight',
						'format'   => function( $value, $attrs ) {
							return is_numeric( $value ) ? $value . 'px' : $value;
						},
					),
				),
			)
		);

		$css->add_item(
			array(
				'selector'   => ' .cfb-block-flip-front .cfb-block-front-desc',
				'properties' => array(
					array(
						'property' => '--cfb-block-font-size',
						'value'    => 'frontDescFontSize',
						'format'   => function( $value, $attrs ) {
							return is_numeric( $value ) ? $value . 'px' : $value;
						},
					),
					array(
						'property' => '--cfb-block-font-family',
						'value'    => 'frontDescFontFamily',
					),
					array(
						'property' => '--cfb-block-font-weight',
						'value'    => 'frontDescFontWeight',
					),
					array(
						'property' => '--cfb-block-line-height',
						'value'    => 'frontDescLineHeight',
						'format'   => function( $value, $attrs ) {
							return is_numeric( $value ) ? $value . 'px' : $value;
						},
					),
				),
			)
		);

		$css->add_item(
			array(
				'selector'   => ' .cfb-block-flip-back .cfb-block-back-title',
				'properties' => array(
					array(
						'property' => '--cfb-block-font-size',
						'value'    => 'backTitleFontSize',
						'format'   => function( $value, $attrs ) {
							return is_numeric( $value ) ? $value . 'px' : $value;
						},
					),
					array(
						'property' => '--cfb-block-font-family',
						'value'    => 'backTitleFontFamily',
					),
					array(
						'property' => '--cfb-block-font-weight',
						'value'    => 'backTitleFontWeight',
					),
					array(
						'property' => '--cfb-block-line-height',
						'value'    => 'backTitleLineHeight',
						'format'   => function( $value, $attrs ) {
							return is_numeric( $value ) ? $value . 'px' : $value;
						},
					),
				),
			)
		);

		$css->add_item(
			array(
				'selector'   => ' .cfb-block-flip-back .cfb-block-back-desc',
				'properties' => array(
					array(
						'property' => '--cfb-block-font-size',
						'value'    => 'backDescFontSize',
						'format'   => function( $value, $attrs ) {
							return is_numeric( $value ) ? $value . 'px' : $value;
						},
					),
					array(
						'property' => '--cfb-block-font-family',
						'value'    => 'backDescFontFamily',
					),
					array(
						'property' => '--cfb-block-font-weight',
						'value'    => 'backDescFontWeight',
					),
					array(
						'property' => '--cfb-block-line-height',
						'value'    => 'backDescLineHeight',
						'format'   => function( $value, $attrs ) {
							return is_numeric( $value ) ? $value . 'px' : $value;
						},
					),
				),
			)
		);

		$style = $css->generate();
		return $style;
	}
}
