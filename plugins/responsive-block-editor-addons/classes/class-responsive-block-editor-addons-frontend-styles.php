<?php
/**
 * RBEA Styles.
 *
 * @package category
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'Responsive_Block_Editor_Addons_Frontend_Styles' ) ) {

	/**
	 * Class Responsive_Block_Editor_Addons_Frontend_Styles.
	 */
	final class Responsive_Block_Editor_Addons_Frontend_Styles {

		/**
		 * Get Advanced Heading CSS
		 *
		 * @param array  $attr The block attributes.
		 * @param string $id The selector ID.
		 * @return array Styles.
		 */
		public static function get_responsive_block_advanced_heading_css( $attr, $id ) {
			$defaults = self::get_responsive_block_advanced_heading_default_attributes();
			$attr     = array_merge( $defaults, (array) $attr );

			$mobile_selectors = array();
			$tablet_selectors = array();

			$selectors        = array(
				''                                => array(
					'text-align' => $attr['headingAlignment'],
				),
				' .responsive-heading-title-text' => array(
					'color'           => $attr['headingTitleColor'],
					'font-family'     => $attr['headingTitleFontFamily'],
					'font-size'       => self::get_css_value( $attr['headingTitleFontSize'], 'px' ),
					'font-weight'     => $attr['headingTitleFontWeight'],
					'line-height'     => $attr['headingTitleLineHeight'],
					'letter-spacing'  => self::get_css_value( $attr['headingTitleLetterSpacing'], 'px' ),
					'margin-bottom'   => self::get_css_value( $attr['headSpacing'], 'px' ),
					'text-decoration' => $attr['textDecoration'],
				),
				' .responsive-heading-seperator'  => array(
					'border-top-style' => $attr['seperatorStyle'],
					'border-color'     => $attr['separatorColor'],
					'border-top-width' => self::get_css_value( $attr['separatorHeight'], 'px' ),
					'width'            => self::get_css_value( $attr['separatorWidth'], $attr['separatorWidthType'] ),
					'margin-bottom'    => self::get_css_value( $attr['separatorSpacing'], 'px' ),
				),
				' .responsive-heading-desc-text'  => array(
					'color'           => $attr['subHeadingTitleColor'],
					'font-family'     => $attr['subHeadingTitleFontFamily'],
					'font-size'       => self::get_css_value( $attr['subHeadingTitleFontSize'], 'px' ),
					'font-weight'     => $attr['subHeadingTitleFontWeight'],
					'line-height'     => $attr['subHeadingTitleLineHeight'],
					'letter-spacing'  => self::get_css_value( $attr['subHeadingTitleLetterSpacing'], 'px' ),
					'margin-bottom'   => self::get_css_value( $attr['subheadSpacing'], 'px' ),
					'text-decoration' => $attr['textDecorationSubHeading'],
				),
			);
			$mobile_selectors = array(
				''                                => array(
					'text-align' => $attr['headingAlignmentMobile'],
				),
				' .responsive-heading-title-text' => array(
					'font-size'     => self::get_css_value( $attr['headingTitleFontSizeMobile'], 'px' ),
					'margin-bottom' => self::get_css_value( $attr['headSpacingMobile'], 'px' ),
				),
				' .responsive-heading-seperator'  => array(
					'margin-bottom' => self::get_css_value( $attr['separatorSpacingMobile'], 'px' ),
				),
				' .responsive-heading-desc-text'  => array(
					'font-size'     => self::get_css_value( $attr['subHeadingTitleFontSizeMobile'], 'px' ),
					'margin-bottom' => self::get_css_value( $attr['subheadSpacingMobile'], 'px' ),
				),
			);

			$tablet_selectors = array(
				''                                => array(
					'text-align' => $attr['headingAlignmentTablet'],
				),
				' .responsive-heading-title-text' => array(
					'font-size'     => self::get_css_value( $attr['headingTitleFontSizeTablet'], 'px' ),
					'margin-bottom' => self::get_css_value( $attr['headSpacingTablet'], 'px' ),
				),
				' .responsive-heading-seperator'  => array(
					'margin-bottom' => self::get_css_value( $attr['separatorSpacingTablet'], 'px' ),
				),
				' .responsive-heading-desc-text'  => array(
					'font-size'     => self::get_css_value( $attr['subHeadingTitleFontSizeTablet'], 'px' ),
					'margin-bottom' => self::get_css_value( $attr['subheadSpacingTablet'], 'px' ),
				),
			);

			$combined_selectors = array(
				'desktop' => $selectors,
				'tablet'  => $tablet_selectors,
				'mobile'  => $mobile_selectors,
			);

			$id  = '.responsive-block-editor-addons-block-advanced-heading.block-' . $id;
			$css = Responsive_Block_Editor_Addons_Frontend_Styles_Helper::responsive_block_editor_addons_generate_all_css( $combined_selectors, $id );
			return $css;
		}


		/**
		 * Get Advanced Columns CSS
		 *
		 * @param array  $attr The block attributes.
		 * @param string $id The selector ID.
		 * @return array Styles.
		 */
		public static function get_responsive_block_advanced_columns_css( $attr, $id ) {
			$defaults = self::get_responsive_block_advanced_columns_default_attributes();
			$attr     = array_merge( $defaults, (array) $attr );

			$mobile_selectors = array();
			$tablet_selectors = array();

			$imgopacity          = $attr['opacity'] / 100;
			$column_height_style = '';
			if ( 'half' === $attr['height'] ) {
				$column_height_style = '50vh !important';
			}
			if ( 'full' === $attr['height'] ) {
				$column_height_style = '100vh !important';
			}
			if ( 'custom' === $attr['height'] ) {
				$column_height_style = $attr['customHeight'] . 'px !important';
			}

			$box_shadow_position_css = $attr['boxShadowPosition'];

			if ( 'outset' === $attr['boxShadowPosition'] ) {
				$box_shadow_position_css = '';
			}
			$max_width = '100%';

			if ( 'custom' === $attr['contentWidth'] ) {
				if ( '' !== $attr['width'] ) {
					$max_width = self::get_css_value( $attr['width'], $attr['widthType'] );
				}
			}

			$background_type_image_styles = array();
			if ( 'image' === $attr['backgroundType'] && $attr['backgroundImage'] ) {
				$background_type_image_styles = array(
					'background-image'      => 'linear-gradient(' . self::hex_to_rgb( $attr['backgroundImageColor'] ? $attr['backgroundImageColor'] : '#fff', $imgopacity ) . ',' . self::hex_to_rgb( $attr['backgroundImageColor'] ? $attr['backgroundImageColor'] : '#fff', $imgopacity ) . '),url(' . $attr['backgroundImage'] . ')',
					'background-position'   => 'empty' !== $attr['backgroundPosition'] && 'center-center' === $attr['backgroundImagePosition'] ? $attr['backgroundPosition'] : $attr['backgroundImagePosition'], // For compatibility with v1.3.2.
					'background-attachment' => $attr['backgroundAttachment'],
					'background-repeat'     => 'empty' !== $attr['backgroundRepeat'] && 'no-repeat' === $attr['backgroundImageRpeat'] ? $attr['backgroundRepeat'] : $attr['backgroundImageRepeat'], // For compatibility with v1.3.2.
					'background-size'       => 'empty' !== $attr['backgroundSize'] && 'cover' === $attr['backgroundImageSize'] ? $attr['backgroundSize'] : $attr['backgroundImageSize'], // For compatibility with v1.3.2.
				);
			}
			$selectors = array(
				''                                => array(
					'z-index' => $attr['z_index'],
				),
				' .responsive-columns-wrap'       => array(
					'text-align'       => $attr['blockAlign'],
					'border-style'     => $attr['blockBorderStyle'],
					'border-color'     => $attr['blockBorderColor'],
					'border-radius'    => self::get_css_value( $attr['blockBorderRadius'], 'px' ) . ' !important',
					'border-width'     => self::get_css_value( $attr['blockBorderWidth'], 'px' ),
					'background-color' => 'color' === $attr['backgroundType'] ? self::hex_to_rgb( $attr['backgroundColor'], $imgopacity ) : '',
					'background-image' => 'gradient' === $attr['backgroundType'] ? self::generate_background_image_effect(
						self::hex_to_rgb( $attr['backgroundColor1'], $imgopacity ),
						self::hex_to_rgb( $attr['backgroundColor2'], $imgopacity ),
						$attr['gradientDirection'],
						$attr['colorLocation1'],
						$attr['colorLocation2']
					) : '',
					'box-shadow'       => self::get_css_value( $attr['boxShadowHOffset'], 'px' ) . ' ' . self::get_css_value( $attr['boxShadowVOffset'], 'px' ) . ' ' . self::get_css_value( $attr['boxShadowBlur'], 'px' ) . ' ' . self::get_css_value( $attr['boxShadowSpread'], 'px' ) . ' ' . $attr['boxShadowColor'] . ' ' . $box_shadow_position_css,
				),
				' .responsive-columns-inner-wrap' => array(
					'max-width' => $max_width,
				),
				'.background-type-image'          => $background_type_image_styles,
				' .responsive-block-editor-addons-block-columns.overlay-type-color' => array(
					'background-color' => 'image' === $attr['backgroundType'] ? self::hex_to_rgb( $attr['backgroundImageColor'], $imgopacity ) : '',
				),
				' .responsive-block-editor-addons-block-columns.overlay-type-gradient.linear' => array(
					'background-image' => self::generate_background_image_effect(
						self::hex_to_rgb( $attr['gradientOverlayColor1'], $imgopacity ),
						self::hex_to_rgb( $attr['gradientOverlayColor2'], $imgopacity ),
						$attr['gradientOverlayAngle'],
						$attr['gradientOverlayLocation1'],
						$attr['gradientOverlayLocation2']
					),
				),
				' .responsive-block-editor-addons-block-columns.overlay-type-gradient.radial' => array(
					'background-image' => 'radial-gradient( at ' . $attr['gradientOverlayPosition'] . ',' . self::hex_to_rgb( $attr['gradientOverlayColor1'] ? $attr['gradientOverlayColor1'] : '#fff', $imgopacity ) .
						' ' . $attr['gradientOverlayLocation1'] . '%,' . self::hex_to_rgb( $attr['gradientOverlayColor2'] ? $attr['gradientOverlayColor2'] : '#fff', $imgopacity ) .
						' ' . $attr['gradientOverlayLocation2'] . '%)',
				),
				' .responsive-block-editor-addons-block-column' => array(
					'min-height'  => $column_height_style,
					'align-items' => $attr['verticalAlign'],
				),
				' .responsive-block-editor-addons-block-columns' => array(
					'padding-top'    => 999 !== $attr['topPadding'] && 10 === $attr['boxTopPadding'] ? self::get_css_value( $attr['topPadding'], 'px' ) : self::get_css_value( $attr['boxTopPadding'], 'px' ), // For compatibility with v1.3.2.
					'padding-bottom' => 999 !== $attr['bottomPadding'] && 10 === $attr['boxBottomPadding'] ? self::get_css_value( $attr['bottomPadding'], 'px' ) : self::get_css_value( $attr['boxBottomPadding'], 'px' ), // For compatibility with v1.3.2.
					'padding-left'   => 999 !== $attr['leftPadding'] && 10 === $attr['boxLeftPadding'] ? self::get_css_value( $attr['leftPadding'], 'px' ) : self::get_css_value( $attr['boxLeftPadding'], 'px' ), // For compatibility with v1.3.2.
					'padding-right'  => 999 !== $attr['rightPadding'] && 10 === $attr['boxRightPadding'] ? self::get_css_value( $attr['rightPadding'], 'px' ) : self::get_css_value( $attr['boxRightPadding'], 'px' ), // For compatibility with v1.3.2.
					'margin-top'     => self::get_css_value( $attr['topMargin'], 'px' ),
					'margin-bottom'  => self::get_css_value( $attr['bottomMargin'], 'px' ),
				),

				' .responsive-columns__gap-narrow .responsive-columns-inner-wrap' => array(
					'gap' => '5px',
				),

				' .responsive-columns__gap-medium .responsive-columns-inner-wrap' => array(
					'gap' => '10px',
				),

				' .responsive-columns__gap-extended .responsive-columns-inner-wrap' => array(
					'gap' => '15px',
				),

				' .responsive-columns__gap-wide .responsive-columns-inner-wrap' => array(
					'gap' => '20px',
				),

				' .responsive-columns__gap-wider .responsive-columns-inner-wrap' => array(
					'gap' => '30px',
				),
			);
			$mobile_selectors = array(
				' .responsive-block-editor-addons-block-columns' => array(
					'padding-top'    => 999 !== $attr['topPaddingMobile'] && 10 === $attr['boxTopPaddingMobile'] ? self::get_css_value( $attr['topPaddingMobile'], 'px' ) : self::get_css_value( $attr['boxTopPaddingMobile'], 'px' ), // For compatibility with v1.3.2.
					'padding-bottom' => 999 !== $attr['bottomPaddingMobile'] && 10 === $attr['boxBottomPaddingMobile'] ? self::get_css_value( $attr['bottomPaddingMobile'], 'px' ) : self::get_css_value( $attr['boxBottomPaddingMobile'], 'px' ), // For compatibility with v1.3.2.
					'padding-left'   => 999 !== $attr['leftPaddingMobile'] && 10 === $attr['boxLeftPaddingMobile'] ? self::get_css_value( $attr['leftPaddingMobile'], 'px' ) : self::get_css_value( $attr['boxLeftPaddingMobile'], 'px' ), // For compatibility with v1.3.2.
					'padding-right'  => 999 !== $attr['rightPaddingMobile'] && 10 === $attr['boxRightPaddingMobile'] ? self::get_css_value( $attr['rightPaddingMobile'], 'px' ) : self::get_css_value( $attr['boxRightPaddingMobile'], 'px' ), // For compatibility with v1.3.2.
					'margin-top'     => self::get_css_value( $attr['topMarginMobile'], 'px' ),
					'margin-bottom'  => self::get_css_value( $attr['bottomMarginMobile'], 'px' ),
				),

			);

			$tablet_selectors = array(
				' .responsive-block-editor-addons-block-columns' => array(
					'padding-top'    => 999 !== $attr['topPaddingTablet'] && 10 === $attr['boxTopPaddingTablet'] ? self::get_css_value( $attr['topPaddingTablet'], 'px' ) : self::get_css_value( $attr['boxTopPaddingTablet'], 'px' ), // For compatibility with v1.3.2.
					'padding-bottom' => 999 !== $attr['bottomPaddingTablet'] && 10 === $attr['boxBottomPaddingTablet'] ? self::get_css_value( $attr['bottomPaddingTablet'], 'px' ) : self::get_css_value( $attr['boxBottomPaddingTablet'], 'px' ), // For compatibility with v1.3.2.
					'padding-left'   => 999 !== $attr['leftPaddingTablet'] && 10 === $attr['boxLeftPaddingTablet'] ? self::get_css_value( $attr['leftPaddingTablet'], 'px' ) : self::get_css_value( $attr['boxLeftPaddingTablet'], 'px' ), // For compatibility with v1.3.2.
					'padding-right'  => 999 !== $attr['rightPaddingTablet'] && 10 === $attr['boxRightPaddingTablet'] ? self::get_css_value( $attr['rightPaddingTablet'], 'px' ) : self::get_css_value( $attr['boxRightPaddingTablet'], 'px' ), // For compatibility with v1.3.2.
					'margin-top'     => self::get_css_value( $attr['topMarginTablet'], 'px' ),
					'margin-bottom'  => self::get_css_value( $attr['bottomMarginTablet'], 'px' ),
				),
			);

			$combined_selectors = array(
				'desktop' => $selectors,
				'tablet'  => $tablet_selectors,
				'mobile'  => $mobile_selectors,
			);
			$id                 = '.responsive-block-editor-addons-advanced-column-outer-wrap.block-' . $id;
			$css                = Responsive_Block_Editor_Addons_Frontend_Styles_Helper::responsive_block_editor_addons_generate_all_css( $combined_selectors, $id );
			return $css;
		}

		/**
		 * Get Advanced Columns CSS
		 *
		 * @param array  $attr The block attributes.
		 * @param string $id The selector ID.
		 * @return array Styles.
		 */
		public static function get_responsive_block_post_carousel_css( $attr, $id ) {
			$defaults = self::get_responsive_block_post_carousel_default_attributes();
			$attr     = array_merge( $defaults, (array) $attr );

			$mobile_selectors = array();
			$tablet_selectors = array();

			$inner_height        = $attr['displayPostImage'] ? '' : '100% !important';
			$slick_button_styles = array(
				'border-color'  => $attr['arrowDotsColor'],
				'border-radius' => self::get_css_value( $attr['arrowBorderRadius'], 'px' ),
				'border-width'  => self::get_css_value( $attr['arrowBorderSize'], 'px' ),
			);
			$imgopacity          = $attr['opacity'] / 100;

			$pc_background_image_gradient = '';
			$pc_color                     = '';
			if ( 'gradient' === $attr['buttonbackgroundType'] ) {
				$pc_background_image_gradient = 'linear-gradient(' . $attr['buttongradientDirection'] . 'deg, ' . $attr['buttonbackgroundColor1'] . ' ' . $attr['buttoncolorLocation1'] . '%, ' . $attr['buttonbackgroundColor2'] . ' ' . $attr['buttoncolorLocation2'] . '%)';
			} elseif ( 'color' === $attr['buttonbackgroundType'] ) {
				$pc_background_image_gradient = '';
				$pc_color                     = $attr['ctaBackColor'];
			}

			$updated_buttonh_color = '';
			if ( 'color' === $attr['buttonHbackgroundType'] ) {
				$updated_buttonh_color = $attr['ctaHoverBackColor'] ? $attr['ctaHoverBackColor'] : 'none';
			}

			$selectors = array(
				' .responsive-post-slick-carousel-' . $id . ' .slick-prev.slick-arrow' => $slick_button_styles,
				' .responsive-post-slick-carousel-' . $id . ' .slick-next.slick-arrow' => $slick_button_styles,
				' .responsive-post-slick-carousel-' . $id . ' .slick-slide>div:first-child' => array(
					'margin-left'  => self::get_css_value( ( $attr['columnGap'] / 2 ), 'px' ),
					'margin-right' => self::get_css_value( ( $attr['columnGap'] / 2 ), 'px' ),
				),
				' .responsive-block-editor-addons-block-post-carousel-title' => array(
					'font-family'   => $attr['titleFontFamily'],
					'font-size'     => self::get_css_value( $attr['titleFontSize'], 'px' ),
					'font-weight'   => $attr['titleFontWeight'],
					'line-height'   => $attr['titleLineHeight'],
					'margin-bottom' => self::get_css_value( $attr['titleSpace'], 'px' ),
					'margin-top'    => self::get_css_value( $attr['imageSpace'], 'px' ),
				),
				' .responsive-block-editor-addons-block-post-carousel-title a' => array(
					'color' => $attr['titleColor'],
				),
				' .responsive-block-editor-addons-block-post-carousel-byline' => array(
					'color'         => $attr['metaColor'],
					'font-family'   => $attr['metaFontFamily'],
					'font-size'     => self::get_css_value( $attr['metaFontSize'], 'px' ),
					'font-weight'   => $attr['metaFontWeight'],
					'line-height'   => $attr['metaLineHeight'],
					'margin-bottom' => self::get_css_value( $attr['dateSpace'], 'px' ),
				),
				' .responsive-block-editor-addons-block-post-carousel-excerpt' => array(
					'text-align'  => $attr['blockAlign'],
					'color'       => $attr['contentColor'],
					'font-family' => $attr['excerptFontFamily'],
					'font-size'   => self::get_css_value( $attr['excerptFontSize'], 'px' ),
					'font-weight' => $attr['excerptFontWeight'],
					'line-height' => $attr['excerptLineHeight'],
				),
				' .responsive-block-editor-addons-block-post-carousel-excerpt p:first-child' => array(
					'margin-bottom' => self::get_css_value( $attr['excerptSpace'], 'px' ),

				),
				' .responsive-block-editor-addons-block-post-carousel-date' => array(
					'color' => $attr['metaColor'],
				),
				' .responsive-block-editor-addons-block-post-carousel-author a' => array(
					'color' => $attr['metaColor'],
				),
				' .responsive-block-editor-addons-block-post-carousel-taxonomy a' => array(
					'color' => $attr['metaColor'],
				),
				' .responsive-block-editor-addons-block-post-carousel-more-link-wrapper' => array(
					'font-family'   => $attr['ctaFontFamily'],
					'font-size'     => self::get_css_value( $attr['ctaFontSize'], 'px' ),
					'font-weight'   => $attr['ctaFontWeight'],
					'line-height'   => $attr['ctaLineHeight'],
					'margin-bottom' => self::get_css_value( $attr['ctaSpace'], 'px' ) . ' !important',
					'margin-top'    => '0px',
				),
				' .responsive-block-editor-addons-block-post-carousel-more-link.responsive-block-editor-addons-text-link' => array(
					'color'            => $attr['ctaColor'],
					'background-color' => $pc_color,
					'background-image' => $pc_background_image_gradient,
					'border-color'     => $attr['ctaBorderColor'],
					'border-style'     => $attr['ctaBorderStyle'],
					'border-radius'    => self::get_css_value( $attr['ctaBorderRadius'], 'px' ),
					'border-width'     => self::get_css_value( $attr['ctaBorderWidth'], 'px' ),
					'padding-left'     => self::get_css_value( $attr['ctaHpadding'], 'px' ),
					'padding-right'    => self::get_css_value( $attr['ctaHpadding'], 'px' ),
					'padding-top'      => self::get_css_value( $attr['ctaVpadding'], 'px' ),
					'padding-bottom'   => self::get_css_value( $attr['ctaVpadding'], 'px' ),
					'display'          => 'inline-block',
				),
				' .responsive-block-editor-addons-block-post-carousel-more-link:hover' => array(
					'color'            => $attr['ctaHoverColor'],
					'background-color' => $updated_buttonh_color,
					'border-color'     => $attr['ctaHoverBorderColor'],
					'background-image' => 'color' === $attr['buttonHbackgroundType'] ? 'none' : $pc_background_image_gradient,
				),
				' .slick-slide'                   => array(
					'margin-bottom' => self::get_css_value( $attr['rowGap'], 'px' ),
				),
				' .responsive-block-editor-addons-block-post-carousel-text-wrap' => array(
					'text-align' => $attr['blockAlign'],
					'padding'    => self::get_css_value( $attr['contentPadding'], 'px' ),
				),
				' .responsive-block-editor-addons-block-post-carousel-header' => array(
					'text-align' => $attr['blockAlign'],
				),
				' .responsive-block-editor-addons-post-carousel-inner' => array(
					'background-color' => $attr['bgColor'],
					'height'           => $inner_height,
				),
				' .responsive-block-editor-addons-block-post-carousel-image-background img' => array(
					'opacity' => $imgopacity,
				),
				' ul.slick-dots li button:before' => array(
					'color' => $attr['arrowDotsColor'],
				),
				' ul.slick-dots li.slick-active button:before' => array(
					'color' => $attr['arrowDotsColor'],
				),

			);
			$mobile_selectors = array(
				' .responsive-block-editor-addons-block-post-carousel-text-wrap' => array(
					'padding' => self::get_css_value( $attr['contentPaddingMobile'], 'px' ),
				),
				' .responsive-block-editor-addons-block-post-carousel-title' => array(
					'font-size' => self::get_css_value( $attr['titleFontSizeMobile'], 'px' ),
				),
				' .responsive-block-editor-addons-block-post-carousel-byline' => array(
					'font-size' => self::get_css_value( $attr['metaFontSizeMobile'], 'px' ),
				),
				' .responsive-block-editor-addons-block-post-carousel-excerpt' => array(
					'font-size' => self::get_css_value( $attr['excerptFontSizeMobile'], 'px' ),
				),
				' .responsive-block-editor-addons-block-post-carousel-more-link-wrapper' => array(
					'font-size' => self::get_css_value( $attr['ctaFontSizeMobile'], 'px' ),
				),
				' .responsive-block-editor-addons-block-post-carousel-more-link.responsive-block-editor-addons-text-link' => array(
					'padding-left'   => self::get_css_value( $attr['ctaHpaddingMobile'], 'px' ),
					'padding-right'  => self::get_css_value( $attr['ctaHpaddingMobile'], 'px' ),
					'padding-top'    => self::get_css_value( $attr['ctaVpaddingMobile'], 'px' ),
					'padding-bottom' => self::get_css_value( $attr['ctaVpaddingMobile'], 'px' ),
				),
				' .responsive-post-slick-carousel-' . $id . ' .slick-slide>div:first-child' => array(
					'margin-left'  => self::get_css_value( ( $attr['columnGapMobile'] / 2 ), 'px' ),
					'margin-right' => self::get_css_value( ( $attr['columnGapMobile'] / 2 ), 'px' ),
				),
			);

			$tablet_selectors = array(
				' .responsive-block-editor-addons-block-post-carousel-text-wrap' => array(
					'padding' => self::get_css_value( $attr['contentPaddingTablet'], 'px' ),
				),
				' .responsive-block-editor-addons-block-post-carousel-title' => array(
					'font-size' => self::get_css_value( $attr['titleFontSizeTablet'], 'px' ),
				),
				' .responsive-block-editor-addons-block-post-carousel-byline' => array(
					'font-size' => self::get_css_value( $attr['metaFontSizeTablet'], 'px' ),
				),
				' .responsive-block-editor-addons-block-post-carousel-excerpt' => array(
					'font-size' => self::get_css_value( $attr['excerptFontSizeTablet'], 'px' ),
				),
				' .responsive-block-editor-addons-block-post-carousel-more-link-wrapper' => array(
					'font-size' => self::get_css_value( $attr['ctaFontSizeTablet'], 'px' ),
				),
				' .responsive-block-editor-addons-block-post-carousel-more-link.responsive-block-editor-addons-text-link' => array(
					'padding-left'   => self::get_css_value( $attr['ctaHpaddingTablet'], 'px' ),
					'padding-right'  => self::get_css_value( $attr['ctaHpaddingTablet'], 'px' ),
					'padding-top'    => self::get_css_value( $attr['ctaVpaddingTablet'], 'px' ),
					'padding-bottom' => self::get_css_value( $attr['ctaVpaddingTablet'], 'px' ),
				),
				' .responsive-post-slick-carousel-' . $id . ' .slick-slide>div:first-child' => array(
					'margin-left'  => self::get_css_value( ( $attr['columnGapTablet'] / 2 ), 'px' ),
					'margin-right' => self::get_css_value( ( $attr['columnGapTablet'] / 2 ), 'px' ),
				),
			);

			$combined_selectors = array(
				'desktop' => $selectors,
				'tablet'  => $tablet_selectors,
				'mobile'  => $mobile_selectors,
			);
			$id                 = '.responsive-block-editor-addons-block-post-carousel.block-' . $id;
			$css                = Responsive_Block_Editor_Addons_Frontend_Styles_Helper::responsive_block_editor_addons_generate_all_css( $combined_selectors, $id );
			return $css;
		}

		/**
		 * Get Post Grid CSS
		 *
		 * @param array  $attr The block attributes.
		 * @param string $id The selector ID.
		 * @return array Styles.
		 */
		public static function get_responsive_block_post_grid_css( $attr, $id ) {
			$defaults = self::get_responsive_block_post_grid_block_default_attributes();
			$attr     = array_merge( $defaults, (array) $attr );

			$box_shadow_position_css = $attr['boxShadowPosition'];

			if ( 'outset' === $attr['boxShadowPosition'] ) {
				$box_shadow_position_css = '';
			}
			$hoverbox_shadow_position_css = $attr['hoverboxShadowPosition'];

			if ( 'outset' === $attr['hoverboxShadowPosition'] ) {
				$hoverbox_shadow_position_css = '';
			}

			$mobile_selectors = array();
			$tablet_selectors = array();

			$display_link = 'none';
			if ( $attr['displayPostLink'] ) {
				$display_link = 'block';
			}

			$link_styles = array();
			$link_styles = array(
				'display'        => $display_link,
				'color'          => $attr['readMoreLinkColor'],
				'font-size'      => self::get_css_value( $attr['continueFontSize'], 'px' ),
				'font-weight'    => $attr['continueFontWeight'],
				'line-height'    => $attr['continueLineHeight'],
				'text-transform' => $attr['continueTextTransform'],
				'font-family'    => $attr['continueFontFamily'],
			);

			$boxed_content_padding = 0;
			$content_padding       = 0;
			if ( 'content' === $attr['layout'] ) {
				$content_padding       = $attr['contentPadding'];
				$boxed_content_padding = 0;
			}
			if ( 'boxed' === $attr['layout'] ) {
				$boxed_content_padding = $attr['contentPadding'];
			}

			$column_gap = '';
			if ( $attr['columnGap'] ) {
				$column_gap = $attr['columnGap'];
			}
			$column_gap_tablet = '';
			if ( $attr['columnGapTablet'] ) {
				$column_gap_tablet = $attr['columnGapTablet'];
			}
			$column_gap_mobile = '';
			if ( $attr['columnGapMobile'] ) {
				$column_gap_mobile = $attr['columnGapMobile'];
			}
			$row_gap = '';
			if ( $attr['rowGap'] ) {
				$row_gap = $attr['rowGap'];
			}
			$row_gap_tablet = '';
			if ( $attr['columnGapTablet'] ) {
				$row_gap_tablet = $attr['rowGapTablet'];
			}
			$row_gap_mobile = '';
			if ( $attr['rowGapMobile'] ) {
				$row_gap_mobile = $attr['rowGapMobile'];
			}
			$equal_height = 'fit-content';
			if ( $attr['equalHeight'] ) {
				$equal_height = 'auto';
			}
			$pagination_background        = 'transparent';
			$pagination_background_active = 'transparent';
			if ( 'filled' === $attr['paginationLayout'] ) {
				$pagination_background        = $attr['paginationBorderColor'];
				$pagination_background_active = $attr['paginationActiveBorderColor'];
			}

			$selectors = array(
				' .responsive-block-editor-addons-post-grid-items' => array(
					'grid-column-gap' => self::get_css_value( $column_gap, 'px' ),
					'grid-row-gap'    => self::get_css_value( $row_gap, 'px' ),
				),
				' article'                     => array(
					'background-color' => $attr['bgColor'] . ' !important',
					'border-style'     => $attr['blockBorderStyle'],
					'border-color'     => $attr['blockBorderColor'],
					'border-radius'    => self::get_css_value( $attr['blockBorderRadius'], 'px' ) . ' !important',
					'border-width'     => self::get_css_value( $attr['blockBorderWidth'], 'px' ),
					'height'           => $equal_height,
					'box-shadow'       => self::get_css_value( $attr['boxShadowHOffset'], 'px' ) . ' ' . self::get_css_value( $attr['boxShadowVOffset'], 'px' ) . ' ' . self::get_css_value( $attr['boxShadowBlur'], 'px' ) . ' ' . self::get_css_value( $attr['boxShadowSpread'], 'px' ) . ' ' . $attr['boxShadowColor'] . ' ' . $box_shadow_position_css,
					'padding'          => self::get_css_value( $boxed_content_padding, 'px' ),

				),
				' article:hover'               => array(
					'box-shadow' => self::get_css_value( $attr['hoverboxShadowHOffset'], 'px' ) . ' ' . self::get_css_value( $attr['hoverboxShadowVOffset'], 'px' ) . ' ' . self::get_css_value( $attr['hoverboxShadowBlur'], 'px' ) . ' ' . self::get_css_value( $attr['hoverboxShadowSpread'], 'px' ) . ' ' . $attr['hoverboxShadowColor'] . ' ' . $hoverbox_shadow_position_css,
				),
				' .responsive-block-editor-addons-post-grid-items article' => array(
					'padding' => self::get_css_value( $boxed_content_padding, 'px' ),
				),
				' .responsive-block-editor-addons-block-post-grid-text' => array(
					'padding'    => self::get_css_value( $content_padding, 'px' ),
					'text-align' => $attr['textAlignment'],
					'display'    => 'block',
				),
				' header .responsive-block-editor-addons-block-post-grid-title' => array(
					'font-family'    => $attr['titleFontFamily'],
					'font-size'      => self::get_css_value( $attr['titleFontSize'], 'px' ),
					'font-weight'    => $attr['titleFontWeight'],
					'line-height'    => $attr['titleLineHeight'],
					'text-transform' => $attr['titleTextTransform'],
					'margin-bottom'  => self::get_css_value( $attr['titleBottomSpacing'], 'px' ),
				),
				' header .responsive-block-editor-addons-block-post-grid-title a' => array(
					'color' => $attr['titleColor'],
				),
				' header .responsive-block-editor-addons-block-post-grid-title a:hover' => array(
					'color' => $attr['titleHoverColor'],
				),
				' .responsive-block-editor-addons-block-post-grid-byline' => array(
					'color'          => $attr['metaColor'],
					'font-family'    => $attr['metaFontFamily'],
					'font-size'      => self::get_css_value( $attr['metaFontSize'], 'px' ),
					'font-weight'    => $attr['metaFontWeight'],
					'line-height'    => $attr['metaLineHeight'],
					'text-transform' => $attr['metaTextTransform'],
					'margin-bottom'  => self::get_css_value( $attr['metaBottomSpacing'], 'px' ),
				),
				' .responsive-block-editor-addons-block-post-grid-text .responsive-block-editor-addons-block-post-grid-excerpt' => array(
					'color'          => $attr['textColor'],
					'font-family'    => $attr['excerptFontFamily'],
					'font-size'      => self::get_css_value( $attr['excerptFontSize'], 'px' ),
					'font-weight'    => $attr['excerptFontWeight'],
					'line-height'    => $attr['excerptLineHeight'],
					'text-transform' => $attr['excerptTextTransform'],
					'margin-bottom'  => self::get_css_value( $attr['excerptBottomSpacing'], 'px' ),
				),
				' .responsive-block-editor-addons-block-post-grid-more-link' => $link_styles,
				' .responsive-block-editor-addons-block-post-grid-more-link:hover' => array(
					'color'           => $attr['readMoreHoverColor'],
					'text-decoration' => 'none',
				),
				' .read-more a'                => $link_styles,
				' .responsive-block-editor-addons-post-grid-item' => array(
					'margin-bottom' => 0,
				),
				' .is-list .responsive-block-editor-addons-post-grid-item:not(:last-child)' => array(
					'margin-bottom' => self::get_css_value( $row_gap, 'px' ),
				),
				' .is-list article:last-child' => array(
					'margin-bottom' => 0,
				),
				' .responsive-block-editor-addons-block-post-grid-image' => array(
					'width' => 'fit-content',
				),
				' .responsive-block-editor-addons-block-post-grid-image img' => array(
					'border-radius' => self::get_css_value( $attr['imageBorderRadius'], 'px' ),
					'object-fit'    => 'cover',
					'height'        => '100%',
					'margin-bottom' => self::get_css_value( $attr['imageBottomSpacing'], 'px' ),
				),
				' .responsive-block-editor-addons-block-post-grid-image a' => array(
					'display' => 'block',
					'width'   => 'list' === $attr['postLayout'] ? self::get_css_value( $attr['imageWidth'], 'px' ) : null,
					'height'  => 'list' === $attr['postLayout'] ? self::get_css_value( $attr['imageHeight'], 'px' ) : null,
				),
				' .responsive-block-editor-addons-post-pagination-wrap > *' => array(
					'border-width'     => self::get_css_value( $attr['paginationBorderWidth'], 'px' ),
					'border-color'     => $attr['paginationBorderColor'],
					'border-style'     => ' solid ',
					'background-color' => $pagination_background,
					'border-radius'    => self::get_css_value( $attr['paginationBorderRadius'], 'px' ),
					'color'            => $attr['paginationTextColor'] . ' !important',
					'margin-right'     => '10px',
					'padding'          => '0.5em',
				),
				' .responsive-block-editor-addons-post-pagination-wrap > *:last-child' => array(
					'margin-right' => '0',
				),
				' .responsive-block-editor-addons-post-pagination-wrap > span' => array(
					'border-width'     => self::get_css_value( $attr['paginationBorderWidth'], 'px' ),
					'border-color'     => $attr['paginationActiveBorderColor'],
					'border-style'     => ' solid ',
					'background-color' => $pagination_background_active,
					'color'            => $attr['paginationTextActiveColor'] . ' !important',
				),
				' .responsive-block-editor-addons-post-pagination-wrap' => array(
					'text-align' => $attr['paginationAlignment'],
					'margin-top' => self::get_css_value( $attr['paginationSpacing'], 'px' ),
				),

			);

			$grid_template_columns = '1fr 1fr';
			if ( $attr['stackonMobile'] ) {
				$grid_template_columns = '1fr';
			}
			$mobile_content_padding = '0';
			if ( 'boxed' === $attr['layout'] && $attr['contentPaddingMobile'] || $attr['mobileContentPadding'] ) { // For compatibility with v1.3.2.
				$mobile_content_padding = ( 999 !== $attr['mobileContentPadding'] && 10 === $attr['contentPaddingMobile'] ) ? $attr['mobileContentPadding'] : $attr['contentPaddingMobile']; // For compatibility with v1.3.2.
			}
			$tablet_content_padding = '0';
			if ( 'boxed' === $attr['layout'] && $attr['contentPaddingTablet'] ) {
				$tablet_content_padding = $attr['contentPaddingTablet'];
			}
			$mobile_selectors = array(
				' .responsive-block-editor-addons-block-post-grid-image img' => array(
					'margin-bottom' => self::get_css_value( $attr['imageBottomSpacingMobile'], 'px' ),
				),
				' header .responsive-block-editor-addons-block-post-grid-title' => array(
					'font-size'     => self::get_css_value( $attr['titleFontSizeMobile'], 'px' ),
					'margin-bottom' => self::get_css_value( $attr['titleBottomSpacingMobile'], 'px' ),
				),
				' .responsive-block-editor-addons-post-grid-items article' => array(
					'padding'    => self::get_css_value( $mobile_content_padding, 'px' ) . ' !important',
					'text-align' => $attr['textAlignment'],
				),
				' .is-list article' => array(
					'grid-template-columns' => $grid_template_columns,
				),
				' .responsive-block-editor-addons-post-grid-items' => array(
					'grid-column-gap' => self::get_css_value( $column_gap_mobile, 'px' ),
					'grid-row-gap'    => self::get_css_value( $row_gap_mobile, 'px' ),
				),
				' .responsive-block-editor-addons-block-post-grid-text .responsive-block-editor-addons-block-post-grid-excerpt' => array(
					'margin-bottom' => self::get_css_value( $attr['excerptBottomSpacingMobile'], 'px' ),
					'font-size'     => self::get_css_value( $attr['excerptFontSizeMobile'], 'px' ),
				),
				' .responsive-block-editor-addons-block-post-grid-byline' => array(
					'font-size'     => self::get_css_value( $attr['metaFontSizeMobile'], 'px' ),
					'margin-bottom' => self::get_css_value( $attr['metaBottomSpacingMobile'], 'px' ),
				),
				' .responsive-block-editor-addons-block-post-grid-more-link' => array(
					'font-size' => self::get_css_value( $attr['continueFontSizeMobile'], 'px' ),
				),
				' .responsive-block-editor-addons-block-post-grid-image a' => array(
					'width'  => 'list' === $attr['postLayout'] ? self::get_css_value( $attr['imageWidthMobile'], 'px' ) : null,
					'height' => 'list' === $attr['postLayout'] ? self::get_css_value( $attr['imageHeightMobile'], 'px' ) : null,
				),
			);

			$tablet_selectors = array(
				' .responsive-block-editor-addons-block-post-grid-image img' => array(
					'margin-bottom' => self::get_css_value( $attr['imageBottomSpacingTablet'], 'px' ),
				),
				' header .responsive-block-editor-addons-block-post-grid-title' => array(
					'font-size'     => self::get_css_value( $attr['titleFontSizeTablet'], 'px' ),
					'margin-bottom' => self::get_css_value( $attr['titleBottomSpacingTablet'], 'px' ),
				),
				' .responsive-block-editor-addons-post-grid-items' => array(
					'grid-column-gap' => self::get_css_value( $column_gap_tablet, 'px' ),
					'grid-row-gap'    => self::get_css_value( $row_gap_tablet, 'px' ),
				),
				' .responsive-block-editor-addons-post-grid-items article' => array(
					'padding' => self::get_css_value( $tablet_content_padding, 'px' ) . ' !important',
				),
				' .responsive-block-editor-addons-block-post-grid-text .responsive-block-editor-addons-block-post-grid-excerpt' => array(
					'margin-bottom' => self::get_css_value( $attr['excerptBottomSpacingTablet'], 'px' ),
					'font-size'     => self::get_css_value( $attr['excerptFontSizeTablet'], 'px' ),
				),
				' .responsive-block-editor-addons-block-post-grid-byline' => array(
					'font-size'     => self::get_css_value( $attr['metaFontSizeTablet'], 'px' ),
					'margin-bottom' => self::get_css_value( $attr['metaBottomSpacingTablet'], 'px' ),
				),
				' .responsive-block-editor-addons-block-post-grid-more-link' => array(
					'font-size' => self::get_css_value( $attr['continueFontSizeTablet'], 'px' ),
				),
				' .responsive-block-editor-addons-block-post-grid-image a' => array(
					'width'  => 'list' === $attr['postLayout'] ? self::get_css_value( $attr['imageWidthTablet'], 'px' ) : null,
					'height' => 'list' === $attr['postLayout'] ? self::get_css_value( $attr['imageHeightTablet'], 'px' ) : null,
				),
			);

			$combined_selectors = array(
				'desktop' => $selectors,
				'tablet'  => $tablet_selectors,
				'mobile'  => $mobile_selectors,
			);

			$id              = '.responsive-block-editor-addons-block-post-grid.block-id-' . $id;
			$css             = Responsive_Block_Editor_Addons_Frontend_Styles_Helper::responsive_block_editor_addons_generate_all_css( $combined_selectors, $id );
			$css['desktop'] .= '.page-template-gutenberg-fullwidth ' . $id . ' .responsive-block-editor-addons-post-grid-items article {padding:' . ( 'boxed' === $attr['layout'] ? $attr['contentPadding'] ? $attr['contentPadding'] : '0' : '0' ) . 'px;}
    ';
			return $css;
		}

		/**
		 * Get Count Up CSS
		 *
		 * @param array  $attr The block attributes.
		 * @param string $id The selector ID.
		 * @return array Styles.
		 */
		public static function get_responsive_block_count_up_css( $attr, $id ) {
			$defaults = self::get_responsive_block_count_up_default_attributes();
			$attr     = array_merge( $defaults, (array) $attr );

			$mobile_selectors = array();
			$tablet_selectors = array();

			$imgopacity = $attr['opacity'] / 100;

			$selectors = array(
				'.responsive-count__inner .responsive-count-item__features li' => array(
					'line-height' => $attr['contentLineHeight'],
				),
				'.responsive-count__inner .responsive-block-editor-addons-count-up__source-wrap.res-countup-icon-design-shaped .responsive-block-editor-addons-count-up__source-icon' => array(
					'background-color' => $attr['iconShapeColor'],
					'border-radius'    => self::get_css_value( $attr['shapeBorderRadius'], 'px' ),
					'padding'          => self::get_css_value( $attr['shapePadding'], 'px' ),
				),
				'.responsive-count__inner .responsive-block-editor-addons-count-up__source-wrap.res-countup-icon-design-outline .responsive-block-editor-addons-count-up__source-icon' => array(
					'border-color'  => $attr['iconShapeColor'],
					'border-radius' => self::get_css_value( $attr['shapeBorderRadius'], 'px' ),
					'padding'       => self::get_css_value( $attr['shapePadding'], 'px' ),
					'border-width'  => self::get_css_value( $attr['shapeBorder'], 'px' ),
				),
				'.responsive-count__inner .responsive-block-editor-addons-count-up__source-icon svg' => array(
					'width'  => self::get_css_value( $attr['iconsize'], 'px' ),
					'height' => self::get_css_value( $attr['iconsize'], 'px' ),
					'fill'   => $attr['icon_color'],
				),
				' .responsive-count-item'           => array(
					'background-color' => self::hex_to_rgb( $attr['itemBackgroundColor'], $imgopacity ),
					'border-width'     => self::get_css_value( $attr['blockBorderWidth'], 'px' ),
					'border-color'     => $attr['blockBorderColor'],
					'border-style'     => $attr['blockBorderStyle'],
					'border-radius'    => self::get_css_value( $attr['blockBorderRadius'], 'px' ),
				),
				' .responsive-block-editor-addons-count-up__source-wrap' => array(
					'margin-bottom' => self::get_css_value( $attr['iconSpacing'], 'px' ),
				),
				' .responsive-count-item__title'    => array(
					'color'         => $attr['titleColor'],
					'line-height'   => $attr['headingLineHeight'],
					'font-size'     => self::get_css_value( $attr['headingFontSize'], 'px' ),
					'font-family'   => $attr['headingFontFamily'],
					'font-weight'   => 'empty' !== $attr['titleFontWeight'] && '900' === $attr['headingFontWeight'] ? $attr['titleFontWeight'] : $attr['headingFontWeight'], // For compatibility with v1.3.2.
					'margin-bottom' => self::get_css_value( $attr['titleSpace'], 'px' ),
				),
				'.responsive-count__inner .responsive-count-item__price-wrapper .responsive-count-item__amount' => array(
					'color'         => $attr['numColor'],
					'line-height'   => $attr['dateLineHeight'],
					'font-weight'   => $attr['dateFontWeight'],
					'font-size'     => self::get_css_value( $attr['dateFontSize'], 'px' ),
					'font-family'   => $attr['dateFontFamily'],
					'margin-bottom' => self::get_css_value( $attr['numSpace'], 'px' ),
				),
				' .responsive-count-item__features' => array(
					'color'         => $attr['textColor'],
					'line-height'   => $attr['contentLineHeight'],
					'font-weight'   => $attr['contentFontWeight'],
					'font-size'     => self::get_css_value( $attr['contentFontSize'], 'px' ),
					'font-family'   => $attr['contentFontFamily'],
					'margin-bottom' => self::get_css_value( $attr['contentSpace'], 'px' ),
				),
			);

			$mobile_selectors = array(
				'.responsive-count__inner .responsive-count-item__title' => array(
					'font-size' => self::get_css_value( $attr['headingFontSizeMobile'], 'px' ),
				),
				'.responsive-count__inner .responsive-count-item__price-wrapper .responsive-count-item__amount' => array(
					'font-size'     => self::get_css_value( $attr['dateFontSizeMobile'], 'px' ),
					'margin-bottom' => self::get_css_value( $attr['numSpaceMobile'], 'px' ),
				),
				' .responsive-count-item__features' => array(
					'font-size'     => self::get_css_value( $attr['contentFontSizeMobile'], 'px' ),
					'margin-bottom' => self::get_css_value( $attr['contentSpaceMobile'], 'px' ),
				),
				' .responsive-block-editor-addons-count-up__source-wrap' => array(
					'margin-bottom' => self::get_css_value( $attr['iconSpacingMobile'], 'px' ),
				),
				' .responsive-count-item__title'    => array(
					'margin-bottom' => self::get_css_value( $attr['titleSpaceMobile'], 'px' ),
				),
			);

			$tablet_selectors = array(
				'.responsive-count__inner .responsive-count-item__title' => array(
					'font-size' => self::get_css_value( $attr['headingFontSize'], 'px' ),
				),
				'.responsive-count__inner .responsive-count-item__title' => array(
					'font-size' => self::get_css_value( $attr['headingFontSizeTablet'], 'px' ),
				),
				'.responsive-count__inner .responsive-count-item__price-wrapper .responsive-count-item__amount' => array(
					'font-size'     => self::get_css_value( $attr['dateFontSizeTablet'], 'px' ),
					'margin-bottom' => self::get_css_value( $attr['numSpaceTablet'], 'px' ),
				),
				' .responsive-count-item__features' => array(
					'font-size'     => self::get_css_value( $attr['contentFontSizeTablet'], 'px' ),
					'margin-bottom' => self::get_css_value( $attr['contentSpaceTablet'], 'px' ),
				),
				' .responsive-block-editor-addons-count-up__source-wrap' => array(
					'margin-bottom' => self::get_css_value( $attr['iconSpacingTablet'], 'px' ),
				),
				' .responsive-count-item__title'    => array(
					'margin-bottom' => self::get_css_value( $attr['titleSpaceTablet'], 'px' ),
				),
			);

			$combined_selectors = array(
				'desktop' => $selectors,
				'tablet'  => $tablet_selectors,
				'mobile'  => $mobile_selectors,
			);

			$id              = '.responsive-block-editor-addons-block-count-up.block-' . $id;
			$css             = Responsive_Block_Editor_Addons_Frontend_Styles_Helper::responsive_block_editor_addons_generate_all_css( $combined_selectors, $id );
			$css['desktop'] .= '
            .responsive-count {
               padding:' . $attr['contentSpacing'] . 'px !important
            }
            ';
			$css['tablet']  .= '
            .responsive-count {
               padding:' . $attr['contentSpacingTablet'] . 'px !important
            }
            ';
			$css['mobile']  .= '
            .responsive-count {
               padding:' . $attr['contentSpacingMobile'] . 'px !important
            }
            ';

			return $css;
		}

		/**
		 * Get Blockquote CSS
		 *
		 * @param array  $attr The block attributes.
		 * @param string $id The selector ID.
		 * @return array Styles.
		 */
		public static function get_responsive_block_blockquote_css( $attr, $id ) {
			$defaults = self::get_responsive_block_blockquote_default_attributes();
			$attr     = array_merge( $defaults, (array) $attr );

			$mobile_selectors = array();
			$tablet_selectors = array();

			$quoteopacity            = $attr['quoteOpacity'] / 100;
			$imgopacity              = $attr['opacity'] / 100;
			$box_shadow_position_css = $attr['boxShadowPosition'];

			if ( 'outset' === $attr['boxShadowPosition'] ) {
				$box_shadow_position_css = '';
			}

			$selectors = array(
				' ' => array(
					'background-color' =>
						'color' === $attr['backgroundType']
						? self::hex_to_rgb( $attr['backgroundColor'], $imgopacity ) : '',
					'color'            => $attr['quoteTextColor'],
					'border-color'     => 'empty' !== $attr['borderColor'] && '' === $attr['blockBorderColor'] ? $attr['borderColor'] : $attr['blockBorderColor'], // For compatibility with v1.3.2.
					'border-style'     => 'empty' !== $attr['borderStyle'] && 'none' === $attr['blockBorderStyle'] ? $attr['borderStyle'] : $attr['blockBorderStyle'], // For compatibility with v1.3.2.
					'border-width'     => 999 !== $attr['borderWidth'] && 1 === $attr['blockBorderWidth'] ? self::get_css_value( $attr['borderWidth'], 'px' ) : self::get_css_value( $attr['blockBorderWidth'], 'px' ), // For compatibility with v1.3.2.
					'border-radius'    => self::get_css_value( $attr['blockBorderRadius'], 'px' ),
					'box-shadow'       =>
					self::get_css_value( $attr['boxShadowHOffset'], 'px' ) .
					' ' .
					self::get_css_value( $attr['boxShadowVOffset'], 'px' ) .
					' ' .
					self::get_css_value( $attr['boxShadowBlur'], 'px' ) .
					' ' .
					self::get_css_value( $attr['boxShadowSpread'], 'px' ) .
					' ' .
					$attr['boxShadowColor'] .
					' ' .
					$box_shadow_position_css,
					'position'         => 'relative',
					'text-align'       => $attr['quoteAlign'],
					'padding-left'     => 999 !== $attr['leftPadding'] && 60 === $attr['blockLeftPadding'] ? self::get_css_value( $attr['leftPadding'], 'px' ) : self::get_css_value( $attr['blockLeftPadding'], 'px' ), // For compatibility with v1.3.2.
					'padding-right'    => 999 !== $attr['rightPadding'] && 60 === $attr['blockRightPadding'] ? self::get_css_value( $attr['rightPadding'], 'px' ) : self::get_css_value( $attr['blockRightPadding'], 'px' ), // For compatibility with v1.3.2.
					'padding-top'      => 999 !== $attr['topPadding'] && 70 === $attr['blockTopPadding'] ? self::get_css_value( $attr['topPadding'], 'px' ) : self::get_css_value( $attr['blockTopPadding'], 'px' ), // For compatibility with v1.3.2.
					'padding-bottom'   => 999 !== $attr['bottomPadding'] && 70 === $attr['blockBottomPadding'] ? self::get_css_value( $attr['bottomPadding'], 'px' ) : self::get_css_value( $attr['blockBottomPadding'], 'px' ), // For compatibility with v1.3.2.
					'background-color' =>
						'color' === $attr['backgroundType']
						? self::hex_to_rgb( $attr['backgroundColor'], $imgopacity )
						: null,
					'background-image' =>
						'gradient' === $attr['backgroundType']
						? 'linear-gradient(' .
							$attr['gradientDirection'] .
							'deg,' .
							self::hex_to_rgb( $attr['backgroundColor1'] ? $attr['backgroundColor1'] : '#ffffff', $imgopacity ) .
							',' .
							self::hex_to_rgb( $attr['backgroundColor2'] ? $attr['backgroundColor2'] : '#ffffff', $imgopacity ) .
							')'
						: null,
				),
				' .responsive-block-editor-addons-section__video-wrap' => array(
					'opacity' => $imgopacity,
				),
				' .responsive-block-editor-addons-section-background-image-wrap .responsive-block-editor-addons-section-background-image' => array(
					'height'        => 100 . '%',
					'opacity'       => $imgopacity,
					'border-radius' => self::get_css_value( $attr['blockBorderRadius'], 'px' ),
				),
				' .responsive-block-editor-addons-block-blockquote-item .responsive-block-editor-addons-block-blockquote-quote' => array(
					'height'  => self::get_css_value( $attr['quoteSize'], 'px' ),
					'width'   => self::get_css_value( $attr['quoteSize'], 'px' ),
					'fill'    => $attr['quoteColor'],
					'left'    => self::get_css_value( $attr['quoteHposition'], 'px' ),
					'top'     => self::get_css_value( $attr['quoteVposition'], 'px' ),
					'opacity' => $quoteopacity,
				),
				' .responsive-block-editor-addons-block-blockquote-text' => array(
					'text-align'  => $attr['quoteAlign'],
					'font-family' => $attr['quoteFontFamily'],
					'font-size'   => self::get_css_value( $attr['quoteFontSize'], 'px' ),
					'font-weight' => $attr['quoteFontWeight'],
					'line-height' => $attr['quoteLineHeight'],
				),
				' .responsive-block-editor-addons-block-blockquote-item' => array(
					'padding-left'   => self::get_css_value( $attr['textLeftPadding'], 'px' ),
					'padding-right'  => self::get_css_value( $attr['textRightPadding'], 'px' ),
					'padding-top'    => self::get_css_value( $attr['textTopPadding'], 'px' ),
					'padding-bottom' => self::get_css_value( $attr['textBottomPadding'], 'px' ),
				),

			);
			$mobile_selectors = array(
				' ' => array(
					'padding-left'   => self::get_css_value( $attr['blockLeftPaddingMobile'], 'px' ) . '!important',
					'padding-right'  => self::get_css_value( $attr['blockRightPaddingMobile'], 'px' ) . '!important',
					'padding-top'    => self::get_css_value( $attr['blockTopPaddingMobile'], 'px' ) . '!important',
					'padding-bottom' => self::get_css_value( $attr['blockBottomPaddingMobile'], 'px' ) . '!important',
				),
				' .responsive-block-editor-addons-block-blockquote-item' => array(
					'padding-left'   => self::get_css_value( $attr['textLeftPaddingMobile'], 'px' ),
					'padding-right'  => self::get_css_value( $attr['textRightPaddingMobile'], 'px' ),
					'padding-top'    => self::get_css_value( $attr['textTopPaddingMobile'], 'px' ),
					'padding-bottom' => self::get_css_value( $attr['textBottomPaddingMobile'], 'px' ),
				),
				' .responsive-block-editor-addons-block-blockquote-text' => array(
					'font-size' => self::get_css_value( $attr['quoteFontSizeMobile'], 'px' ),
				),
			);

			$tablet_selectors = array(
				' ' => array(
					'padding-left'   => self::get_css_value( $attr['blockLeftPaddingTablet'], 'px' ) . '!important',
					'padding-right'  => self::get_css_value( $attr['blockRightPaddingTablet'], 'px' ) . '!important',
					'padding-top'    => self::get_css_value( $attr['blockTopPaddingTablet'], 'px' ) . '!important',
					'padding-bottom' => self::get_css_value( $attr['blockBottomPaddingTablet'], 'px' ) . '!important',
				),
				' .responsive-block-editor-addons-block-blockquote-item' => array(
					'padding-left'   => self::get_css_value( $attr['textLeftPaddingTablet'], 'px' ),
					'padding-right'  => self::get_css_value( $attr['textRightPaddingTablet'], 'px' ),
					'padding-top'    => self::get_css_value( $attr['textTopPaddingTablet'], 'px' ),
					'padding-bottom' => self::get_css_value( $attr['textBottomPaddingTablet'], 'px' ),
				),
				' .responsive-block-editor-addons-block-blockquote-text' => array(
					'font-size' => self::get_css_value( $attr['quoteFontSizeTablet'], 'px' ),
				),
			);

			$combined_selectors = array(
				'desktop' => $selectors,
				'tablet'  => $tablet_selectors,
				'mobile'  => $mobile_selectors,
			);

			$id  = '.responsive-block-editor-addons-block-blockquote.block-' . $id;
			$css = Responsive_Block_Editor_Addons_Frontend_Styles_Helper::responsive_block_editor_addons_generate_all_css( $combined_selectors, $id );

			return $css;
		}

		/**
		 * Get Divider CSS
		 *
		 * @param array  $attr The block attributes.
		 * @param string $id The selector ID.
		 * @return array Styles.
		 */
		public static function get_responsive_block_divider_css( $attr, $id ) {
			$defaults = self::get_responsive_block_divider_default_attributes();
			$attr     = array_merge( $defaults, (array) $attr );

			$mobile_selectors = array();
			$tablet_selectors = array();

			$selectors        = array(
				' ' => array(
					'color' => $attr['spacerDividerColor'],
				),
				' .responsive-block-editor-addons-spacer-handle' => array(
					'color' => $attr['spacerDividerColor'],
				),
				' .responsive-block-editor-addons-divider-inner .responsive-block-editor-addons-divider-content' => array(
					'margin-top'    => self::get_css_value( $attr['spacerHeight'], 'px' ),
					'margin-bottom' => self::get_css_value( $attr['spacerHeight'], 'px' ),
				),
				' .responsive-block-editor-addons-divider-inner .responsive-block-editor-addons-divider-content .responsive-block-editor-addons-divider_hr' => array(
					'height'           => self::get_css_value( $attr['spacerDividerHeight'], 'px' ),
					'width'            => $attr['spacerDividerWidth'] . '%',
					'background-color' => $attr['spacerDividerColor'],
					'border-radius'    =>
						'basic' === $attr['spacerDividerStyle']
						? 0
						: self::get_css_value( $attr['spacerDividerHeight'] / 2, 'px' ),
					'margin-left'      => 'left' === $attr['spacerDividerAlignment'] ? 0 : 'auto',
					'margin-right'     => 'right' === $attr['spacerDividerAlignment'] ? 0 : 'auto',
				),
				' .responsive-block-editor-addons-divider-inner .responsive-block-editor-addons-divider-content .rgbl-divider__dots' => array(
					'width'        => $attr['spacerDividerWidth'] . '%',
					'margin-left'  => 'left' === $attr['spacerDividerAlignment'] ? 0 : 'auto',
					'margin-right' => 'right' === $attr['spacerDividerAlignment'] ? 0 : 'auto',
				),
				' .responsive-block-editor-addons-divider-inner .responsive-block-editor-addons-divider-content .rgbl-divider__dots .rgbl-divider__dot' => array(
					'height'           => self::get_css_value( $attr['spacerDividerHeight'], 'px' ),
					'background-color' => $attr['spacerDividerColor'],
					'font-size'        => self::get_css_value( $attr['spacerDividerHeight'] * 1.8, 'px' ),
					'width'            => self::get_css_value( $attr['spacerDividerHeight'], 'px' ),
				),
			);
			$mobile_selectors = array(
				' .responsive-block-editor-addons-divider-inner .responsive-block-editor-addons-divider-content' => array(
					'margin-top'    => self::get_css_value( $attr['spacerHeightMobile'], 'px' ),
					'margin-bottom' => self::get_css_value( $attr['spacerHeightMobile'], 'px' ),
				),
			);

			$tablet_selectors = array(
				' .responsive-block-editor-addons-divider-inner .responsive-block-editor-addons-divider-content' => array(
					'margin-top'    => self::get_css_value( $attr['spacerHeightTablet'], 'px' ),
					'margin-bottom' => self::get_css_value( $attr['spacerHeightTablet'], 'px' ),
				),
			);

			$combined_selectors = array(
				'desktop' => $selectors,
				'tablet'  => $tablet_selectors,
				'mobile'  => $mobile_selectors,
			);

			$id  = '.responsive-block-editor-addons-block-divider.block-' . $id;
			$css = Responsive_Block_Editor_Addons_Frontend_Styles_Helper::responsive_block_editor_addons_generate_all_css( $combined_selectors, $id );
			return $css;
		}

		/**
		 * Get Defaults for post grid block
		 *
		 * @return array
		 */
		public static function get_responsive_block_post_grid_block_default_attributes() {
			return array(
				'textAlignment'               => 'left',
				'postsToShow'                 => 6,
				'stackonMobile'               => true,
				'displayPostDate'             => true,
				'excludeCurrentPost'          => true,
				'displayPostExcerpt'          => true,
				'displayPostAuthor'           => true,
				'displayPostImage'            => true,
				'displayPostLink'             => true,
				'displayPostTitle'            => true,
				'displaySectionTitle'         => false,
				'postPagination'              => false,
				'equalHeight'                 => true,
				'postTitleTag'                => 'h3',
				'postLayout'                  => 'grid',
				'columns'                     => 2,
				'align'                       => 'center',
				'width'                       => 'wide',
				'orderBy'                     => 'date',
				'order'                       => 'desc',
				'readMoreText'                => 'Read More »',
				'offset'                      => 0,
				'excerptLength'               => 55,
				'postType'                    => 'post',
				'postTaxonomy'                => 'category',
				'taxonomyType'                => 'category',
				'paginationAlignment'         => 'left',
				'paginationLayout'            => '',
				'sectionTag'                  => 'section',
				'sectionTitle'                => '',
				'sectionTitleTag'             => 'h2',
				'imageSize'                   => 'full',
				'id'                          => '',
				'bgColor'                     => '#e4e4e4',
				'paginationBorderColor'       => '#e4e4e4',
				'paginationTextActiveColor'   => '',
				'paginationTextColor'         => '',
				'paginationActiveBorderColor' => '',
				'paginationBorderWidth'       => '',
				'paginationBorderRadius'      => '',
				'paginationSpacing'           => '',
				'imageBorderRadius'           => '',
				'textColor'                   => '#444444',
				'previousButtonText'          => 'Previous',
				'nextButtonText'              => 'Next',
				'imagePosition'               => 'top',
				'layout'                      => 'boxed',
				'metaColor'                   => '#444444',
				'readMoreLinkColor'           => '#0066cc',
				'readMoreHoverColor'          => '#0558ab',
				'titleColor'                  => '#444444',
				'titleHoverColor'             => '#444444',
				'contentPadding'              => 30,
				'contentPaddingMobile'        => '',
				'mobileContentPadding'        => 999, // For compatibility with v1.3.2.
				'contentPaddingTablet'        => '',
				'continueFontSize'            => '',
				'continueLineHeight'          => '',
				'continueFontWeight'          => '',
				'continueTextTransform'       => '',
				'continueFontFamily'          => '',
				'titleFontSize'               => '',
				'titleFontSizeMobile'         => '',
				'titleFontSizeTablet'         => '',
				'titleLineHeight'             => '',
				'titleFontWeight'             => '',
				'titleTextTransform'          => '',
				'metaFontSize'                => '',
				'metaLineHeight'              => '',
				'metaFontWeight'              => '',
				'metaTextTransform'           => '',
				'titleFontFamily'             => '',
				'metaFontFamily'              => '',
				'excerptFontFamily'           => '',
				'excerptFontSize'             => '',
				'excerptLineHeight'           => '',
				'excerptFontWeight'           => '',
				'excerptTextTransform'        => '',
				'excerptBottomSpacing'        => '',
				'metaBottomSpacing'           => '',
				'imageBottomSpacing'          => '',
				'imageBottomSpacingMobile'    => '',
				'imageBottomSpacingTablet'    => '',
				'titleBottomSpacing'          => '',
				'columnGap'                   => 20,
				'excerptBottomSpacingMobile'  => '',
				'metaBottomSpacingMobile'     => '',
				'titleBottomSpacingMobile'    => '',
				'excerptBottomSpacingTablet'  => '',
				'metaBottomSpacingTablet'     => '',
				'titleBottomSpacingTablet'    => '',
				'rowGap'                      => 0,
				'rowGapMobile'                => 0,
				'rowGapTablet'                => 0,
				'blockBorderWidth'            => '0',
				'blockBorderRadius'           => '0',
				'blockBorderStyle'            => 'none',
				'blockBorderColor'            => '#333',
				'pageLimit'                   => '10',
				'taxonomyType'                => 'category',
				'postGridBlockId'             => '',
				'boxShadowColor'              => '',
				'boxShadowHOffset'            => '0',
				'boxShadowVOffset'            => '0',
				'boxShadowBlur'               => '0',
				'boxShadowSpread'             => '0',
				'boxShadowPosition'           => 'outset',
				'hoverboxShadowColor'         => '#cccccc',
				'hoverboxShadowHOffset'       => 0,
				'hoverboxShadowVOffset'       => 0,
				'hoverboxShadowBlur'          => 6,
				'hoverboxShadowSpread'        => 1,
				'hoverboxShadowPosition'      => 'outset',
				'columnGapTablet'             => '',
				'columnGapMobile'             => '',
				'continueFontSizeMobile'      => '',
				'continueFontSizeTablet'      => '',
				'metaFontSizeMobile'          => '',
				'metaFontSizeTablet'          => '',
				'excerptFontSizeMobile'       => '',
				'excerptFontSizeTablet'       => '',
				'imageWidth'                  => '',
				'imageWidthTablet'            => '',
				'imageWidthMobile'            => '',
				'imageHeight'                 => '',
				'imageHeightTablet'           => '',
				'imageHeightMobile'           => '',
			);
		}

		/**
		 * Get Defaults for post carousel block
		 *
		 * @return array
		 */
		public static function get_responsive_block_post_carousel_default_attributes() {
			return array(
				'blockAlign'              => 'left',
				'columns'                 => 2,
				'tcolumns'                => 1,
				'mcolumns'                => 1,
				'block_id'                => 1,
				'autoplaySpeed'           => 2000,
				'autoplay'                => true,
				'infiniteLoop'            => true,
				'pauseOnHover'            => true,
				'transitionSpeed'         => 500,
				'arrowSize'               => 20,
				'arrowDots'               => 'arrows_dots',
				'arrowBorderSize'         => 1,
				'arrowBorderRadius'       => 0,
				'postsToShow'             => 6,
				'displayPostDate'         => true,
				'displayPostExcerpt'      => true,
				'displayPostAuthor'       => true,
				'displayPostImage'        => true,
				'displayPostLink'         => true,
				'displayPostTitle'        => true,
				'displayPostComment'      => true,
				'displayPostTaxonomy'     => true,
				'buttonTarget'            => false,
				'equalHeight'             => true,
				'categories'              => '',
				'className'               => '',
				'postTitleTag'            => 'h3',
				'align'                   => 'center',
				'order'                   => 'desc',
				'orderBy'                 => 'date',
				'readMoreText'            => 'Continue Reading',
				'offset'                  => 0,
				'excerptLength'           => 20,
				'postType'                => 'post',
				'sectionTag'              => 'section',
				'sectionTitle'            => '',
				'sectionTitleTag'         => 'h2',
				'imageSize'               => 'full',
				'ctaHoverColor'           => '#ffffff',
				'bgColor'                 => '#ffffff',
				'ctaColor'                => '#ffffff',
				'ctaBackColor'            => '#333333',
				'titleColor'              => '#333333',
				'contentColor'            => '#333333',
				'metaColor'               => '#333333',
				'arrowDotsColor'          => '#333333',
				'ctaHoverBackColor'       => '#444444',
				'ctaBorderColor'          => '',
				'ctaHoverBorderColor'     => '',
				'ctaBorderStyle'          => 'none',
				'ctaBorderRadius'         => 0,
				'ctaBorderWidth'          => 2,
				'ctaHpadding'             => 20,
				'ctaVpadding'             => 15,
				'contentPadding'          => 20,
				'contentPaddingMobile'    => 20,
				'contentPaddingTablet'    => 20,
				'rowGap'                  => 20,
				'columnGap'               => 20,
				'columnGapMobile'         => 20,
				'columnGapTablet'         => 20,
				'imageSpace'              => null,
				'titleSpace'              => null,
				'dateSpace'               => 20,
				'excerptSpace'            => 20,
				'ctaSpace'                => 20,
				'titleFontSize'           => 20,
				'titleFontSizeMobile'     => 20,
				'titleFontSizeTablet'     => 20,
				'titleFontWeight'         => 100,
				'titleLineHeight'         => 1,
				'metaFontSize'            => 16,
				'metaFontWeight'          => 100,
				'metaLineHeight'          => 1,
				'excerptFontSize'         => 16,
				'excerptFontWeight'       => 100,
				'excerptLineHeight'       => 1,
				'ctaFontSize'             => 16,
				'ctaFontWeight'           => 100,
				'ctaLineHeight'           => 1,
				'opacity'                 => 50,
				'imagePosition'           => 'background',
				'titleFontFamily'         => '',
				'metaFontFamily'          => '',
				'excerptFontFamily'       => '',
				'ctaFontFamily'           => '',
				'metaFontSizeMobile'      => '',
				'metaFontSizeTablet'      => '',
				'excerptFontSizeMobile'   => '',
				'excerptFontSizeTablet'   => '',
				'ctaFontSizeMobile'       => '',
				'ctaFontSizeTablet'       => '',
				'buttoncolorLocation1'    => 0,
				'buttoncolorLocation2'    => 100,
				'buttongradientDirection' => 90,
				'buttonbackgroundColor1'  => '',
				'buttonbackgroundColor2'  => '#fff',
				'buttonbackgroundType'    => 'color',
				'ctaHpaddingTablet'       => 20,
				'ctaHpaddingMobile'       => 20,
				'ctaVpaddingTablet'       => 15,
				'ctaVpaddingMobile'       => 15,
				'buttonHbackgroundType'   => 'none',
			);
		}

		/**
		 * Get Accordian Block CSS
		 * *
		 *
		 * @param array  $attr The block attributes.
		 * @param string $id The selector ID.
		 * @return array Styles.
		 */
		public static function get_responsive_block_accordian_css( $attr, $id ) {
			$defaults = self::get_responsive_block_accordian_block_default_attributes();
			$attr     = array_merge( $defaults, (array) $attr );

			$mobile_selectors = array();
			$tablet_selectors = array();

			$icon_color        = $attr['iconColor'];
			$icon_active_color = $attr['iconActiveColor'];
			if ( ! $attr['iconColor'] ) {
				$icon_color = $attr['titleTextColor'];
			}
			if ( ! $attr['iconActiveColor'] ) {
				$icon_active_color = $attr['titleTextActiveColor'];
			}

			$content_opacity                   = $attr['titleBackgroundColorOpacity'] / 100;
			$content_background_colors_opacity = $attr['contentBackgroundColorOpacity'] / 100;

			$temptitle_secondary_background_color = $attr['titleBgGradient'] ? $attr['titleSecondaryBackgroundColor'] : $attr['titleBackgroundColor'];
			$title_gradient                       = '';
			if ( $attr['titleBgGradient'] ) {
				$title_gradient = 'linear-gradient(' .
					$attr['titleGradientDegree'] .
					'deg,' .
					self::hex_to_rgb( $attr['titleBackgroundColor'] ? $attr['titleBackgroundColor'] : '#ffffff', $content_opacity ) .
					',' .
					self::hex_to_rgb( $temptitle_secondary_background_color ? $temptitle_secondary_background_color : '#ffffff', $content_opacity ) .
					')';
			}

			$temp_active_secondary_background_color = $attr['contentBgGradient'] ? $attr['contentSecondaryBackgroundColor'] : $attr['contentBackgroundColor'];
			$content_gradient                       = 'linear-gradient(' .
				$attr['contentGradientDegree'] .
				'deg,' .
				self::hex_to_rgb( $attr['contentBackgroundColor'] ? $attr['contentBackgroundColor'] : '#ffffff', $content_background_colors_opacity ) .
				',' .
				self::hex_to_rgb( $temp_active_secondary_background_color ? $temp_active_secondary_background_color : '#ffffff', $content_background_colors_opacity ) .
				')';

			$selectors = array(
				' ' => array(
					'margin-top'    => self::get_css_value( ( $attr['marginV'] ), 'px' ),
					'margin-bottom' => self::get_css_value( ( $attr['marginV'] ), 'px' ),
					'margin-left'   => self::get_css_value( ( $attr['marginH'] ), 'px' ),
					'margin-right'  => self::get_css_value( ( $attr['marginH'] ), 'px' ),
				),
				' .responsive-block-editor-addons-icon svg' => array(
					'width'        => self::get_css_value( ( $attr['iconSize'] ), $attr['iconSizeType'] ),
					'height'       => self::get_css_value( ( $attr['iconSize'] ), $attr['iconSizeType'] ),
					'font-size'    => self::get_css_value( ( $attr['iconSize'] ), $attr['iconSizeType'] ),
					'fill'         => $icon_color,
					'margin-right' => '10px',
				),
				' .responsive-block-editor-addons-icon-active svg' => array(
					'width'        => self::get_css_value( ( $attr['iconSize'] ), $attr['iconSizeType'] ),
					'height'       => self::get_css_value( ( $attr['iconSize'] ), $attr['iconSizeType'] ),
					'font-size'    => self::get_css_value( ( $attr['iconSize'] ), $attr['iconSizeType'] ),
					'fill'         => $icon_active_color,
					'margin-right' => '10px',
				),
				' .responsive-block-editor-addons-accordion-item__outer-wrap' => array(
					'margin-bottom' => self::get_css_value( ( $attr['rowsGap'] ), 'px' ),
				),
				' .responsive-block-editor-addons-accordion__wrap.responsive-block-editor-addons-buttons-layout-wrap' => array(
					'grid-column-gap' => self::get_css_value( ( $attr['columnsGap'] ), 'px' ),
					'grid-row-gap'    => self::get_css_value( ( $attr['rowsGap'] ), 'px' ),
				),
				' .responsive-block-editor-addons-accordion-titles-button' => array(
					'padding-top'    => self::get_css_value( ( $attr['vtitlePaddingDesktop'] ), $attr['titlePaddingTypeDesktop'] ),
					'padding-bottom' => self::get_css_value( ( $attr['titleBottomPaddingDesktop'] ), $attr['titlePaddingTypeDesktop'] ),
					'padding-right'  => self::get_css_value( ( $attr['htitlePaddingDesktop'] ), $attr['titlePaddingTypeDesktop'] ),
					'padding-left'   => self::get_css_value( ( $attr['titleLeftPaddingDesktop'] ), $attr['titlePaddingTypeDesktop'] ),
				),
				' .responsive-block-editor-addons-accordion-content span' => array(
					'margin-top'    => self::get_css_value( ( $attr['vcontentPaddingDesktop'] ), $attr['contentPaddingTypeDesktop'] ),
					'margin-bottom' => self::get_css_value( ( $attr['vcontentPaddingDesktop'] ), $attr['contentPaddingTypeDesktop'] ),
					'margin-right'  => self::get_css_value( ( $attr['hcontentPaddingDesktop'] ), $attr['contentPaddingTypeDesktop'] ),
					'margin-left'   => self::get_css_value( ( $attr['hcontentPaddingDesktop'] ), $attr['contentPaddingTypeDesktop'] ),
				),
				' .responsive-block-editor-addons-accordion-item .responsive-block-editor-addons-accordion-titles-button.responsive-block-editor-addons-accordion-titles' => array(
					'flex-direction' => $attr['iconAlign'],
				),
				' .responsive-block-editor-addons-accordion-titles-button' => array(
					'background-image' => $title_gradient,
				),
				' .responsive-block-editor-addons-accordion-titles-button .responsive-block-editor-addons-title' => array(
					'font-family' => $attr['titleFontFamily'],
					'font-size'   => self::get_css_value( $attr['titleFontSize'], 'px' ),
					'font-weight' => $attr['titleFontWeight'],
					'line-height' => $attr['titleLineHeight'],
				),
				' .responsive-block-editor-addons-accordion-item .responsive-block-editor-addons-accordion-content' => array(
					'background-image' => $content_gradient,
					'font-family'      => $attr['contentFontFamily'],
					'font-size'        => self::get_css_value( $attr['contentFontSize'], 'px' ),
					'font-weight'      => $attr['contentFontWeight'],
					'line-height'      => $attr['contentLineHeight'],
					'color'            => $attr['contentTextColor'],
				),
				' .responsive-block-editor-addons-accordion-titles-button.responsive-block-editor-addons-accordion-titles' => array(
					'color'            => $attr['titleTextColor'],
					'background-color' => self::hex_to_rgb( $attr['titleBackgroundColor'] ? $attr['titleBackgroundColor'] : '#fff', $content_opacity ),
				),
				' .responsive-block-editor-addons-accordion-item-active .responsive-block-editor-addons-accordion-titles-button.responsive-block-editor-addons-accordion-titles' => array(
					'color'            => $attr['titleActiveTextColor'],
					'background-color' => $attr['titleActiveBackgroundColor'],
				),

			);
			$mobile_selectors = array(
				' ' => array(
					'margin-top'    => self::get_css_value( ( $attr['marginVMobile'] ), 'px' ) . ' !important',
					'margin-bottom' => self::get_css_value( ( $attr['marginVMobile'] ), 'px' ) . ' !important',
					'margin-left'   => self::get_css_value( ( $attr['marginHMobile'] ), 'px' ),
					'margin-right'  => self::get_css_value( ( $attr['marginHMobile'] ), 'px' ),
				),
				' .responsive-block-editor-addons-accordion-titles-button' => array(
					'padding-top'    => self::get_css_value( ( $attr['vtitlePaddingMobile'] ), $attr['titlePaddingTypeDesktop'] ),
					'padding-bottom' => self::get_css_value( ( $attr['titleBottomPaddingMobile'] ), $attr['titlePaddingTypeDesktop'] ),
					'padding-right'  => self::get_css_value( ( $attr['htitlePaddingMobile'] ), $attr['titlePaddingTypeDesktop'] ),
					'padding-left'   => self::get_css_value( ( $attr['titleLeftPaddingMobile'] ), $attr['titlePaddingTypeDesktop'] ),
				),
				' .responsive-block-editor-addons-accordion-content span' => array(
					'margin-top'    => self::get_css_value( ( $attr['vcontentPaddingMobile'] ), $attr['contentPaddingTypeDesktop'] ),
					'margin-bottom' => self::get_css_value( ( $attr['vcontentPaddingMobile'] ), $attr['contentPaddingTypeDesktop'] ),
					'margin-right'  => self::get_css_value( ( $attr['hcontentPaddingMobile'] ), $attr['contentPaddingTypeDesktop'] ),
					'margin-left'   => self::get_css_value( ( $attr['hcontentPaddingMobile'] ), $attr['contentPaddingTypeDesktop'] ),
				),
				' .responsive-block-editor-addons-icon svg' => array(
					'width'     => self::get_css_value( ( $attr['iconSizeMobile'] ), $attr['iconSizeType'] ),
					'height'    => self::get_css_value( ( $attr['iconSizeMobile'] ), $attr['iconSizeType'] ),
					'font-size' => self::get_css_value( ( $attr['iconSizeMobile'] ), $attr['iconSizeType'] ),
				),
				' .responsive-block-editor-addons-icon-active svg' => array(
					'width'     => self::get_css_value( ( $attr['iconSizeMobile'] ), $attr['iconSizeType'] ),
					'height'    => self::get_css_value( ( $attr['iconSizeMobile'] ), $attr['iconSizeType'] ),
					'font-size' => self::get_css_value( ( $attr['iconSizeMobile'] ), $attr['iconSizeType'] ),
				),
				' .responsive-block-editor-addons-accordion-titles-button .responsive-block-editor-addons-title' => array(
					'font-size' => self::get_css_value( $attr['titleFontSizeMobile'], 'px' ),
				),
				' .responsive-block-editor-addons-accordion-item .responsive-block-editor-addons-accordion-content' => array(
					'font-size' => self::get_css_value( $attr['contentFontSizeMobile'], 'px' ),
				),
				' .responsive-block-editor-addons-accordion-item__outer-wrap' => array(
					'margin-bottom' => self::get_css_value( ( $attr['rowsGapMobile'] ), 'px' ),
				),
				' .responsive-block-editor-addons-accordion__wrap.responsive-block-editor-addons-buttons-layout-wrap' => array(
					'grid-column-gap' => self::get_css_value( ( $attr['columnsGapMobile'] ), 'px' ),
					'grid-row-gap'    => self::get_css_value( ( $attr['rowsGapMobile'] ), 'px' ),
				),
			);

			$tablet_selectors = array(
				' ' => array(
					'margin-top'    => self::get_css_value( ( $attr['marginVTablet'] ), 'px' ) . ' !important',
					'margin-bottom' => self::get_css_value( ( $attr['marginVTablet'] ), 'px' ) . ' !important',
					'margin-left'   => self::get_css_value( ( $attr['marginHTablet'] ), 'px' ),
					'margin-right'  => self::get_css_value( ( $attr['marginHTablet'] ), 'px' ),
				),
				' .responsive-block-editor-addons-accordion-titles-button' => array(
					'padding-top'    => self::get_css_value( ( $attr['vtitlePaddingTablet'] ), $attr['titlePaddingTypeDesktop'] ),
					'padding-bottom' => self::get_css_value( ( $attr['titleBottomPaddingTablet'] ), $attr['titlePaddingTypeDesktop'] ),
					'padding-right'  => self::get_css_value( ( $attr['htitlePaddingTablet'] ), $attr['titlePaddingTypeDesktop'] ),
					'padding-left'   => self::get_css_value( ( $attr['titleLeftPaddingTablet'] ), $attr['titlePaddingTypeDesktop'] ),
				),
				' .responsive-block-editor-addons-accordion-content span' => array(
					'margin-top'    => self::get_css_value( ( $attr['vcontentPaddingTablet'] ), $attr['contentPaddingTypeDesktop'] ),
					'margin-bottom' => self::get_css_value( ( $attr['vcontentPaddingTablet'] ), $attr['contentPaddingTypeDesktop'] ),
					'margin-right'  => self::get_css_value( ( $attr['hcontentPaddingTablet'] ), $attr['contentPaddingTypeDesktop'] ),
					'margin-left'   => self::get_css_value( ( $attr['hcontentPaddingTablet'] ), $attr['contentPaddingTypeDesktop'] ),
				),
				' .responsive-block-editor-addons-icon svg' => array(
					'width'     => self::get_css_value( ( $attr['iconSizeTablet'] ), $attr['iconSizeType'] ),
					'height'    => self::get_css_value( ( $attr['iconSizeTablet'] ), $attr['iconSizeType'] ),
					'font-size' => self::get_css_value( ( $attr['iconSizeTablet'] ), $attr['iconSizeType'] ),
				),
				' .responsive-block-editor-addons-icon-active svg' => array(
					'width'     => self::get_css_value( ( $attr['iconSizeTablet'] ), $attr['iconSizeType'] ),
					'height'    => self::get_css_value( ( $attr['iconSizeTablet'] ), $attr['iconSizeType'] ),
					'font-size' => self::get_css_value( ( $attr['iconSizeTablet'] ), $attr['iconSizeType'] ),
				),
				' .responsive-block-editor-addons-accordion-titles-button .responsive-block-editor-addons-title' => array(
					'font-size' => self::get_css_value( $attr['titleFontSizeTablet'], 'px' ),
				),
				' .responsive-block-editor-addons-accordion-item .responsive-block-editor-addons-accordion-content' => array(
					'font-size' => self::get_css_value( $attr['contentFontSizeTablet'], 'px' ),
				),
				' .responsive-block-editor-addons-accordion-item__outer-wrap' => array(
					'margin-bottom' => self::get_css_value( ( $attr['rowsGapTablet'] ), 'px' ),
				),
				' .responsive-block-editor-addons-accordion__wrap.responsive-block-editor-addons-buttons-layout-wrap' => array(
					'grid-column-gap' => self::get_css_value( ( $attr['columnsGapTablet'] ), 'px' ),
					'grid-row-gap'    => self::get_css_value( ( $attr['rowsGapTablet'] ), 'px' ),
				),
			);

			if ( 'accordion' === $attr['layout'] && true === $attr['inactiveOtherItems'] ) {
				$selectors = array_merge(
					$selectors,
					array(
						' .responsive-block-editor-addons-accordion-item__outer-wrap .responsive-block-editor-addons-accordion-content' => array(
							'display' => 'none',
						),
					)
				);
			}

			if ( 'accordion' === $attr['layout'] && true === $attr['expandFirstItem'] ) {
				$selectors = array_merge(
					$selectors,
					array(
						'  .responsive-block-editor-addons-accordion__wrap > div:first-child > .responsive-block-editor-addons-accordion-item__outer-wrap .responsive-block-editor-addons-accordion-content' => array(
							'display' => 'block',
						),
						'  .responsive-block-editor-addons-accordion__wrap > div:first-child > .responsive-block-editor-addons-accordion-item__outer-wrap .responsive-block-editor-addons-accordion-item .responsive-block-editor-addons-accordion-titles-button .responsive-block-editor-addons-icon-active' => array(
							'display' => 'inline-block',
						),
						'  .responsive-block-editor-addons-accordion__wrap > div:first-child > .responsive-block-editor-addons-accordion-item__outer-wrap .responsive-block-editor-addons-accordion-item .responsive-block-editor-addons-accordion-titles-button .responsive-block-editor-addons-icon' => array(
							'display' => 'none',
						),
					)
				);
			}
			if ( 'grid' === $attr['layout'] ) {
				$selectors = array_merge(
					$selectors,
					array(
						'  .responsive-block-editor-addons-accordion-item__outer-wrap' => array(
							'text-align' => $attr['align'],
						),
						'   .responsive-block-editor-addons-accordion__wrap.responsive-block-editor-addons-buttons-layout-wrap' => array(
							'grid-template-columns' => 'repeat(' . $attr['columns'] . ', 1fr)',
							'display'               => 'grid',
						),
						'  > div:first-child > .responsive-block-editor-addons-accordion-item__outer-wrap .responsive-block-editor-addons-accordion-item .responsive-block-editor-addons-accordion-titles-button .responsive-block-editor-addons-icon' => array(
							'display' => 'none',
						),
					)
				);
			}

			$combined_selectors = array(
				'desktop' => $selectors,
				'tablet'  => $tablet_selectors,
				'mobile'  => $mobile_selectors,
			);

			$id  = '.responsive-block-editor-addons-accordion__outer-wrap.responsive-block-editor-addons-block-' . $id;
			$css = Responsive_Block_Editor_Addons_Frontend_Styles_Helper::responsive_block_editor_addons_generate_all_css( $combined_selectors, $id );
			return $css;
		}

		/**
		 * Get Defaults for Accordian block
		 *
		 * @return array
		 */
		public static function get_responsive_block_accordian_block_default_attributes() {
			return array(
				'block_id'                        => '',
				'layout'                          => 'accordion',
				'inactiveOtherItems'              => true,
				'expandFirstItem'                 => false,
				'enableSchemaSupport'             => false,
				'align'                           => 'left',
				'rowsGap'                         => 10,
				'columnsGap'                      => 10,
				'rowsGapMobile'                   => 10,
				'columnsGapMobile'                => 10,
				'rowsGapTablet'                   => 10,
				'columnsGapTablet'                => 10,
				'boxPaddingTypeMobile'            => 'px',
				'boxPaddingTypeTablet'            => 'px',
				'boxPaddingTypeDesktop'           => 'px',
				'vBoxPaddingMobile'               => 10,
				'hBoxPaddingMobile'               => 10,
				'vBoxPaddingTablet'               => 10,
				'hBoxPaddingTablet'               => 10,
				'vBoxPaddingDesktop'              => 10,
				'hBoxPaddingDesktop'              => 10,
				'titleTextColor'                  => '#313131',
				'titleTextActiveColor'            => '#656565',
				'titlePaddingTypeDesktop'         => 'px',
				'vtitlePaddingMobile'             => 10,
				'vtitlePaddingTablet'             => 10,
				'vtitlePaddingDesktop'            => 10,
				'htitlePaddingMobile'             => 10,
				'htitlePaddingTablet'             => 10,
				'htitlePaddingDesktop'            => 10,
				'contentTextColor'                => '#313131',
				'contentPaddingTypeDesktop'       => 'px',
				'vcontentPaddingMobile'           => 10,
				'vcontentPaddingTablet'           => 10,
				'vcontentPaddingDesktop'          => 10,
				'hcontentPaddingMobile'           => 10,
				'hcontentPaddingTablet'           => 10,
				'hcontentPaddingDesktop'          => 10,
				'titleActiveTextColor'            => '',
				'titleActiveBackgroundColor'      => '',
				'iconColor'                       => '',
				'iconActiveColor'                 => '',
				'titleFontWeight'                 => '',
				'titleFontSize'                   => '',
				'titleLineHeight'                 => '',
				'titleFontFamily'                 => '',
				'contentFontFamily'               => '',
				'contentFontWeight'               => '',
				'contentFontSize'                 => '',
				'contentLineHeight'               => '',
				'icon'                            => 'fas fa-plus',
				'iconActive'                      => 'fas fa-minus',
				'iconAlign'                       => 'row',
				'iconSize'                        => 12,
				'iconSizeTablet'                  => 12,
				'iconSizeMobile'                  => 12,
				'iconSizeType'                    => 'px',
				'columns'                         => 2,
				'schema'                          => '',
				'enableToggle'                    => true,
				'equalHeight'                     => true,
				'titleLeftPaddingTablet'          => 10,
				'titleBottomPaddingTablet'        => 10,
				'titleLeftPaddingDesktop'         => 10,
				'titleBottomPaddingDesktop'       => 10,
				'titleLeftPaddingMobile'          => 10,
				'titleBottomPaddingMobile'        => 10,
				'headingTag'                      => 'span',
				'titleBackgroundColorOpacity'     => 100,
				'marginV'                         => '',
				'marginH'                         => '',
				'marginVMobile'                   => '',
				'marginHMobile'                   => '',
				'marginVTablet'                   => '',
				'marginHTablet'                   => '',
				'titleSecondaryBackgroundColor'   => '',
				'titleGradientDegree'             => 100,
				'titleBgGradient'                 => false,
				'titleBackgroundColor'            => '#ffffff',
				'contentSecondaryBackgroundColor' => '',
				'contentGradientDegree'           => 100,
				'contentBgGradient'               => false,
				'contentBackgroundColor'          => '#eeeeee',
				'contentBackgroundColorOpacity'   => 100,
				'borderStyle'                     => 'solid',
				'borderColor'                     => '',
				'borderRadius'                    => 2,
				'borderWidth'                     => 1,
				'titleFontSizeMobile'             => '',
				'titleFontSizeTablet'             => '',
				'contentFontSizeMobile'           => '',
				'contentFontSizeTablet'           => '',
			);
		}

		/**
		 * Get Accordian Child Block CSS
		 *
		 * @param array  $attr The block attributes.
		 * @param string $id The selector ID.
		 * @return array Styles.
		 */
		public static function get_responsive_block_accordian_child_css( $attr, $id ) {
			$defaults         = self::get_responsive_block_accordian_child_block_default_attributes();
			$attr             = array_merge( $defaults, (array) $attr );
			$mobile_selectors = array();
			$tablet_selectors = array();

			$box_shadow_position_css = $attr['boxShadowPosition'];

			if ( 'outset' === $attr['boxShadowPosition'] ) {
				$box_shadow_position_css = '';
			}
			$selectors = array(
				' ' => array(
					'border-color'  => 'empty' !== $attr['borderColor'] && 'black' === $attr['blockBorderColor'] ? $attr['borderColor'] : $attr['blockBorderColor'], // For compatibility with v1.3.2.
					'border-style'  => 'empty' !== $attr['borderStyle'] && 'solid' === $attr['blockBorderStyle'] ? $attr['borderStyle'] : $attr['blockBorderStyle'], // For compatibility with v1.3.2.
					'border-width'  => 999 !== $attr['borderWidth'] && 1 === $attr['blockBorderWidth'] ? self::get_css_value( $attr['borderWidth'], 'px' ) : self::get_css_value( $attr['blockBorderWidth'], 'px' ), // For compatibility with v1.3.2.
					'border-radius' => 999 !== $attr['borderRadius'] && 2 === $attr['blockBorderRadius'] ? self::get_css_value( $attr['borderRadius'], 'px' ) : self::get_css_value( $attr['blockBorderRadius'], 'px' ), // For compatibility with v1.3.2.
					'box-shadow'    =>
						self::get_css_value( $attr['boxShadowHOffset'], 'px' ) .
						' ' .
						self::get_css_value( $attr['boxShadowVOffset'], 'px' ) .
						' ' .
						self::get_css_value( $attr['boxShadowBlur'], 'px' ) .
						' ' .
						self::get_css_value( $attr['boxShadowSpread'], 'px' ) .
						' ' .
						$attr['boxShadowColor'] .
						' ' .
						$box_shadow_position_css,
					'overflow'      => 'hidden',

				),
				' .responsive-block-editor-addons-accordion-titles-button.responsive-block-editor-addons-accordion-titles' => array(
					'box-shadow' => 'inset' === $box_shadow_position_css ?
						$box_shadow_position_css .
						' ' .
						self::get_css_value( $attr['boxShadowHOffset'], 'px' ) .
						' ' .
						self::get_css_value( $attr['boxShadowVOffset'], 'px' ) .
						' ' .
						self::get_css_value( $attr['boxShadowBlur'], 'px' ) .
						' ' .
						self::get_css_value( $attr['boxShadowSpread'], 'px' ) .
						' ' .
						$attr['boxShadowColor'] : '',
					'padding'    => self::get_css_value( $attr['titlePadding'], 'px' ),
				),
				' .responsive-block-editor-addons-accordion-content span' => array(
					'margin'  => '0',
					'padding' => self::get_css_value( $attr['contentPadding'], 'px' ),
				),
			);
			$mobile_selectors = array(
				' .responsive-block-editor-addons-accordion-titles-button.responsive-block-editor-addons-accordion-titles' => array(
					'padding' => self::get_css_value( $attr['titlePaddingMobile'], 'px' ),
				),
				' .responsive-block-editor-addons-accordion-content span' => array(
					'padding' => self::get_css_value( $attr['contentPaddingMobile'], 'px' ),
				),
			);

			$tablet_selectors = array(
				' .responsive-block-editor-addons-accordion-titles-button.responsive-block-editor-addons-accordion-titles' => array(
					'padding' => self::get_css_value( $attr['titlePaddingTablet'], 'px' ),
				),
				' .responsive-block-editor-addons-accordion-content span' => array(
					'padding' => self::get_css_value( $attr['contentPaddingTablet'], 'px' ),
				),
			);

			$combined_selectors = array(
				'desktop' => $selectors,
				'tablet'  => $tablet_selectors,
				'mobile'  => $mobile_selectors,
			);

			$id  = '.responsive-block-editor-addons-accordion-item__outer-wrap.responsive-block-editor-addons-block-' . $id;
			$css = Responsive_Block_Editor_Addons_Frontend_Styles_Helper::responsive_block_editor_addons_generate_all_css( $combined_selectors, $id );
			return $css;
		}

		/**
		 * Get Defaults for Accordian Child block
		 *
		 * @return array
		 */
		public static function get_responsive_block_accordian_child_block_default_attributes() {
			return array(
				'block_id'             => '',
				'title'                => 'What is Accordion?',
				'content'              => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.',
				'icon'                 => 'fas fa-plus',
				'iconActive'           => 'fas fa-minus',
				'layout'               => 'accordion',
				'headingTag'           => 'span',
				'blockBorderStyle'     => 'solid',
				'blockBorderColor'     => 'black',
				'blockBorderWidth'     => 1,
				'blockBorderRadius'    => 2,
				'boxShadowColor'       => '#fff',
				'boxShadowHOffset'     => 9,
				'boxShadowVOffset'     => 9,
				'boxShadowBlur'        => 9,
				'boxShadowSpread'      => 9,
				'boxShadowPosition'    => 'outset',
				'titlePadding'         => 10,
				'contentPadding'       => 10,
				'titlePaddingMobile'   => '',
				'contentPaddingMobile' => '',
				'titlePaddingTablet'   => '',
				'contentPaddingTablet' => '',
				'borderColor'          => 'empty', // For compatibility with v1.3.2.
				'borderStyle'          => 'empty', // For compatibility with v1.3.2.
				'borderWidth'          => 999, // For compatibility with v1.3.2.
				'borderRadius'         => 999, // For compatibility with v1.3.2.
			);
		}

		/**
		 * Get Defaults for advanced heading block
		 *
		 * @return array
		 */
		public static function get_responsive_block_advanced_heading_default_attributes() {
			return array(
				'block_id'                      => '',
				'headingTitle'                  => '',
				'headingDesc'                   => '',
				'seperatorStyle'                => 'solid',
				'separatorWidthType'            => '%',
				'separatorColor'                => '',
				'headingTitleColor'             => '',
				'subHeadingTitleColor'          => '',
				'headingTitleFontFamily'        => '',
				'subHeadingTitleFontFamily'     => '',
				'headingTitleFontSize'          => '',
				'subHeadingTitleFontSize'       => '',
				'headingTitleFontSizeTablet'    => '',
				'subHeadingTitleFontSizeTablet' => '',
				'headingTitleFontSizeMobile'    => '',
				'subHeadingTitleFontSizeMobile' => '',
				'headingTitleFontWeight'        => '600',
				'subHeadingTitleFontWeight'     => '400',
				'headingTag'                    => 'h2',
				'headingAlignment'              => 'center',
				'headingAlignmentTablet'        => 'center',
				'headingAlignmentMobile'        => 'center',
				'showHeading'                   => true,
				'showSubHeading'                => true,
				'showSeparator'                 => true,
				'separatorHeight'               => 3,
				'separatorWidth'                => 20,
				'headSpacing'                   => 15,
				'subheadSpacing'                => 15,
				'separatorSpacing'              => 15,
				'headSpacingTablet'             => 15,
				'subheadSpacingTablet'          => 15,
				'separatorSpacingTablet'        => 15,
				'headSpacingMobile'             => 15,
				'subheadSpacingMobile'          => 15,
				'separatorSpacingMobile'        => 15,
				'headingTitleLineHeight'        => 1,
				'subHeadingTitleLineHeight'     => 1,
				'headingTitleLetterSpacing'     => 0,
				'subHeadingTitleLetterSpacing'  => 0,
				'level'                         => 2,
				'textDecoration'                => 'none',
				'textDecorationSubHeading'      => 'none',
			);
		}

		/**
		 * Get Advanced column child Block CSS
		 *
		 * @param array  $attr The block attributes.
		 * @param string $id The selector ID.
		 * @return array Styles.
		 */
		public static function get_responsive_block_advanced_column_child_css( $attr, $id ) {
			$defaults = self::get_responsive_block_advanced_column_child_block_default_attributes();
			$attr     = array_merge( $defaults, (array) $attr );

			$mobile_selectors = array();
			$tablet_selectors = array();

			$imgopacity              = $attr['opacity'] / 100 === 0 ? '0.0' : $attr['opacity'] / 100; //phpcs:ignore
			$column_width            = $attr['width'] . '%';
			$box_shadow_position_css = $attr['boxShadowPosition'];

			if ( 'outset' === $attr['boxShadowPosition'] ) {
				$box_shadow_position_css = '';
			}
			$hoverbox_shadow_position_css = $attr['hoverboxShadowPosition'];

			if ( 'outset' === $attr['hoverboxShadowPosition'] ) {
				$hoverbox_shadow_position_css = '';
			}

			$background_type_image_styles       = array();
			$background_type_image_hover_styles = array();
			if ( 'image' === $attr['backgroundType'] && $attr['backgroundImage'] ) {

				if ( 'gradient' === $attr['overlayType'] && 'linear' === $attr['gradientOverlayType'] ) {
					$background_type_image_styles       = array(
						'background-image' => self::generate_background_image_effect(
							self::hex_to_rgb( $attr['gradientOverlayColor1'], $imgopacity ),
							self::hex_to_rgb( $attr['gradientOverlayColor2'], $imgopacity ),
							$attr['gradientOverlayAngle'],
							$attr['gradientOverlayLocation1'],
							$attr['gradientOverlayLocation2']
						) . ',url(' . $attr['backgroundImage'] . ')',
					);
					$background_type_image_hover_styles = array(
						'background-image' => $attr['backgroundHoverImage'] ? self::generate_background_image_effect(
							self::hex_to_rgb( $attr['gradientOverlayColor1'], $imgopacity ),
							self::hex_to_rgb( $attr['gradientOverlayColor2'], $imgopacity ),
							$attr['gradientOverlayAngle'],
							$attr['gradientOverlayLocation1'],
							$attr['gradientOverlayLocation2']
						) . ',url(' . $attr['backgroundHoverImage'] . ')' : null,
					);
				} elseif ( 'gradient' === $attr['overlayType'] && 'radial' === $attr['gradientOverlayType'] ) {
					$background_type_image_styles       = array(
						'background-image' => 'radial-gradient( at ' . $attr['gradientOverlayPosition'] . ','
							. self::hex_to_rgb( $attr['gradientOverlayColor1'], $imgopacity ) .
							' ' . $attr['gradientOverlayLocation1'] . '%,' . self::hex_to_rgb( $attr['gradientOverlayColor2'] ? $attr['gradientOverlayColor2'] : '#fff', $imgopacity ) .
							' ' . $attr['gradientOverlayLocation2'] . '%),url(' . $attr['backgroundImage'] . ')',
					);
					$background_type_image_hover_styles = array(
						'background-image' => $attr['backgroundHoverImage'] ? 'radial-gradient( at ' . $attr['gradientOverlayPosition'] . ','
							. self::hex_to_rgb( $attr['gradientOverlayColor1'], $imgopacity ) .
							' ' . $attr['gradientOverlayLocation1'] . '%,' . self::hex_to_rgb( $attr['gradientOverlayColor2'] ? $attr['gradientOverlayColor2'] : '#fff', $imgopacity ) .
							' ' . $attr['gradientOverlayLocation2'] . '%),url(' . $attr['backgroundHoverImage'] . ')' : null,
					);
				} else {
					$background_type_image_styles       = array(
						'background-image' => 'linear-gradient(' . self::hex_to_rgb( $attr['backgroundImageColor'] ? $attr['backgroundImageColor'] : '#fff', $imgopacity ) . ',' . self::hex_to_rgb( $attr['backgroundImageColor'] ? $attr['backgroundImageColor'] : '#fff', $imgopacity ) . '),url(' . $attr['backgroundImage'] . ')',
					);
					$background_type_image_hover_styles = array(
						'background-image' => $attr['backgroundHoverImage'] ? 'linear-gradient(' . self::hex_to_rgb( $attr['backgroundImageColor'] ? $attr['backgroundImageColor'] : '#fff', $imgopacity ) . ',' . self::hex_to_rgb( $attr['backgroundImageColor'] ? $attr['backgroundImageColor'] : '#fff', $imgopacity ) . '),url(' . $attr['backgroundHoverImage'] . ')' : null,
					);
				}
				$background_type_image_styles       = array_merge(
					$background_type_image_styles,
					array(
						'background-position'   => 'empty' !== $attr['backgroundPosition'] && 'center center' === $attr['backgroundImagePosition'] ? $attr['backgroundPosition'] : $attr['backgroundImagePosition'], // For compatibility with v1.3.2.
						'background-attachment' => $attr['backgroundAttachment'],
						'background-repeat'     => 'empty' !== $attr['backgroundRepeat'] && 'no-repeat' === $attr['backgroundImageRpeat'] ? $attr['backgroundRepeat'] : $attr['backgroundImageRepeat'], // For compatibility with v1.3.2.
						'background-size'       => 'empty' !== $attr['backgroundSize'] && 'cover' === $attr['backgroundImageSize'] ? $attr['backgroundSize'] : $attr['backgroundImageSize'], // For compatibility with v1.3.2.
					)
				);
				$background_type_image_hover_styles = array_merge(
					$background_type_image_hover_styles,
					array(
						'background-position'   => $attr['backgroundImageHoverPosition'],
						'background-attachment' => $attr['backgroundImageHoverAttachment'],
						'background-repeat'     => $attr['backgroundImageHoverRepeat'],
						'background-size'       => $attr['backgroundImageHoverSize'],
					)
				);
			}
			$selectors = array(
				'' => array(
					'width' => $column_width,
				),
				' .responsive-block-editor-addons-block-column' => array_merge(
					array(
						'padding-left'     => self::get_css_value( $attr['leftPadding'], 'px' ),
						'padding-right'    => self::get_css_value( $attr['rightPadding'], 'px' ),
						'padding-top'      => self::get_css_value( $attr['topPadding'], 'px' ),
						'padding-bottom'   => self::get_css_value( $attr['bottomPadding'], 'px' ),
						'margin-left'      => self::get_css_value( $attr['leftMargin'], 'px' ),
						'margin-right'     => self::get_css_value( $attr['rightMargin'], 'px' ),
						'margin-top'       => self::get_css_value( $attr['topMargin'], 'px' ),
						'margin-bottom'    => self::get_css_value( $attr['bottomMargin'], 'px' ),
						'box-shadow'       => self::get_css_value( $attr['boxShadowHOffset'], 'px' ) . ' ' . self::get_css_value( $attr['boxShadowVOffset'], 'px' ) . ' ' . self::get_css_value( $attr['boxShadowBlur'], 'px' ) . ' ' . self::get_css_value( $attr['boxShadowSpread'], 'px' ) . ' ' . $attr['boxShadowColor'] . ' ' . $box_shadow_position_css,
						'background-color' => 'color' === $attr['backgroundType'] ? self::hex_to_rgb( $attr['backgroundColor'] ? $attr['backgroundColor'] : '#fff', $imgopacity ) : '',
						'background-image' => 'gradient' === $attr['backgroundType'] ? self::generate_background_image_effect(
							self::hex_to_rgb( $attr['backgroundColor1'], $imgopacity ),
							self::hex_to_rgb( $attr['backgroundColor2'], $imgopacity ),
							$attr['gradientDirection'],
							$attr['colorLocation1'],
							$attr['colorLocation2']
						) : '',
						'border-style'     => $attr['blockBorderStyle'],
						'border-color'     => $attr['blockBorderColor'],
						'border-radius'    => self::get_css_value( $attr['blockBorderRadius'], 'px' ) . ' !important',
						'border-width'     => self::get_css_value( $attr['blockBorderWidth'], 'px' ),

					),
					$background_type_image_styles
				),
				' .responsive-block-editor-addons-block-column:hover' => array_merge(
					array(
						'box-shadow'       => '' !== $attr['hoverboxShadowColor'] ? self::get_css_value( $attr['hoverboxShadowHOffset'], 'px' ) . ' ' . self::get_css_value( $attr['hoverboxShadowVOffset'], 'px' ) . ' ' . self::get_css_value( $attr['hoverboxShadowBlur'], 'px' ) . ' ' . self::get_css_value( $attr['hoverboxShadowSpread'], 'px' ) . ' ' . $attr['hoverboxShadowColor'] . ' ' . $hoverbox_shadow_position_css : '',
						'background-image' => 'gradient' === $attr['backgroundType'] ? self::generate_background_image_effect(
							self::hex_to_rgb( $attr['hoverbackgroundColor1'], $imgopacity ),
							self::hex_to_rgb( $attr['hoverbackgroundColor2'], $imgopacity ),
							$attr['hovergradientDirection'],
							$attr['hovercolorLocation1'],
							$attr['hovercolorLocation2']
						) : '',
						'background-color' => 'color' === $attr['backgroundType'] && '' !== $attr['backgroundColorHover'] ? self::hex_to_rgb( $attr['backgroundColorHover'] ? $attr['backgroundColorHover'] : '#fff', $imgopacity ) : '',
					),
					$background_type_image_hover_styles
				),
			);
			$mobile_selectors = array(
				' .responsive-block-editor-addons-block-column' => array(
					'padding-left'   => self::get_css_value( $attr['leftPaddingMobile'], 'px' ),
					'padding-right'  => self::get_css_value( $attr['rightPaddingMobile'], 'px' ),
					'padding-top'    => self::get_css_value( $attr['topPaddingMobile'], 'px' ),
					'padding-bottom' => self::get_css_value( $attr['bottomPaddingMobile'], 'px' ),
					'margin-left'    => self::get_css_value( $attr['leftMarginMobile'], 'px' ),
					'margin-right'   => self::get_css_value( $attr['rightMarginMobile'], 'px' ),
					'margin-top'     => self::get_css_value( $attr['topMarginMobile'], 'px' ),
					'margin-bottom'  => self::get_css_value( $attr['bottomMarginMobile'], 'px' ),
				),
			);

			$tablet_selectors = array(
				' .responsive-block-editor-addons-block-column' => array(
					'padding-left'   => self::get_css_value( $attr['leftPaddingTablet'], 'px' ),
					'padding-right'  => self::get_css_value( $attr['rightPaddingTablet'], 'px' ),
					'padding-top'    => self::get_css_value( $attr['topPaddingTablet'], 'px' ),
					'padding-bottom' => self::get_css_value( $attr['bottomPaddingTablet'], 'px' ),
					'margin-left'    => self::get_css_value( $attr['leftMarginTablet'], 'px' ),
					'margin-right'   => self::get_css_value( $attr['rightMarginTablet'], 'px' ),
					'margin-top'     => self::get_css_value( $attr['topMarginTablet'], 'px' ),
					'margin-bottom'  => self::get_css_value( $attr['bottomMarginTablet'], 'px' ),
				),
			);

			$combined_selectors = array(
				'desktop' => $selectors,
				'tablet'  => $tablet_selectors,
				'mobile'  => $mobile_selectors,
			);

			$id  = '.responsive-block-editor-addons-advanced-column-child.block-' . $id;
			$css = Responsive_Block_Editor_Addons_Frontend_Styles_Helper::responsive_block_editor_addons_generate_all_css( $combined_selectors, $id );

			return $css;
		}

		/**
		 * Get Defaults for advanced column child block
		 *
		 * @return array
		 */
		public static function get_responsive_block_advanced_column_child_block_default_attributes() {
			return array(
				'width'                          => '',
				'topPadding'                     => '',
				'bottomPadding'                  => '',
				'leftPadding'                    => '',
				'rightPadding'                   => '',
				'leftMargin'                     => '',
				'rightMargin'                    => '',
				'topMargin'                      => '',
				'bottomMargin'                   => '',
				'topPaddingTablet'               => '',
				'bottomPaddingTablet'            => '',
				'leftPaddingTablet'              => '',
				'rightPaddingTablet'             => '',
				'leftMarginTablet'               => '',
				'rightMarginTablet'              => '',
				'topMarginTablet'                => '',
				'bottomMarginTablet'             => '',
				'topPaddingMobile'               => '',
				'bottomPaddingMobile'            => '',
				'leftPaddingMobile'              => '',
				'rightPaddingMobile'             => '',
				'leftMarginMobile'               => '',
				'rightMarginMobile'              => '',
				'topMarginMobile'                => '',
				'bottomMarginMobile'             => '',
				'block_id'                       => '',
				'blockBorderStyle'               => 'none',
				'blockBorderWidth'               => 1,
				'blockBorderRadius'              => '',
				'blockBorderColor'               => '',
				'boxShadowColor'                 => '',
				'boxShadowHOffset'               => 0,
				'boxShadowVOffset'               => 0,
				'boxShadowBlur'                  => 0,
				'boxShadowSpread'                => 0,
				'boxShadowPosition'              => 'outset',
				'hoverboxShadowColor'            => '',
				'hoverboxShadowHOffset'          => 0,
				'hoverboxShadowVOffset'          => 0,
				'hoverboxShadowBlur'             => 6,
				'hoverboxShadowSpread'           => 1,
				'hoverboxShadowPosition'         => 'outset',
				'opacity'                        => 20,
				'colorLocation1'                 => 0,
				'colorLocation2'                 => 100,
				'gradientDirection'              => 90,
				'hovercolorLocation1'            => 0,
				'hovercolorLocation2'            => 100,
				'hovergradientDirection'         => 90,
				'backgroundImage'                => '',
				'backgroundImagePosition'        => 'center center',
				'backgroundImageSize'            => 'cover',
				'backgroundImageRepeat'          => 'no-repeat',
				'backgroundAttachment'           => 'scroll',
				'backgroundImageColor'           => '',
				'overlayType'                    => 'color',
				'gradientOverlayColor1'          => '',
				'gradientOverlayColor2'          => '',
				'gradientOverlayType'            => 'linear',
				'gradientOverlayLocation1'       => 0,
				'gradientOverlayLocation2'       => 100,
				'gradientOverlayAngle'           => 0,
				'gradientOverlayPosition'        => 'center center',
				'backgroundType'                 => '',
				'backgroundColor'                => '',
				'backgroundColorHover'           => '',
				'backgroundColor1'               => '',
				'backgroundColor2'               => '#fff',
				'hoverbackgroundColor1'          => '',
				'hoverbackgroundColor2'          => '#fff',
				'backgroundPosition'             => 'empty', // For compatibility with v1.3.2.
				'backgroundRepeat'               => 'empty', // For compatibility with v1.3.2.
				'backgroundSize'                 => 'empty', // For compatibility with v1.3.2.
				'backgroundHoverImage'           => '',
				'backgroundImageHoverPosition'   => '',
				'backgroundImageHoverRepeat'     => '',
				'backgroundImageHoverAttachment' => '',
				'backgroundImageHoverSize'       => '',
			);
		}

		/**
		 * Get Buttons Block CSS
		 *
		 * @param array  $attr The block attributes.
		 * @param string $id The selector ID.
		 * @return array Styles.
		 */
		public static function get_responsive_block_buttons_css( $attr, $id ) {
			$defaults = self::get_responsive_block_buttons_default_attributes();
			$attr     = array_merge( $defaults, (array) $attr );

			$mobile_selectors = array();
			$tablet_selectors = array();

			$selectors        = array();
			$mobile_selectors = array();

			$tablet_selectors = array();

			$combined_selectors = array(
				'desktop' => $selectors,
				'tablet'  => $tablet_selectors,
				'mobile'  => $mobile_selectors,
			);

			$id  = '.responsive-block-editor-addons-block-buttons.block-' . $id;
			$css = Responsive_Block_Editor_Addons_Frontend_Styles_Helper::responsive_block_editor_addons_generate_all_css( $combined_selectors, $id );
			return $css;
		}

		/**
		 * Get Defaults for buttons block
		 *
		 * @return array
		 */
		public static function get_responsive_block_buttons_default_attributes() {
			return array();
		}

		/**
		 * Get Buttons Child Block CSS
		 *
		 * @param array  $attr The block attributes.
		 * @param string $id The selector ID.
		 * @return array Styles.
		 */
		public static function get_responsive_block_buttons_child_css( $attr, $id ) {
			$defaults = self::get_responsive_block_buttons_child_default_attributes();
			$attr     = array_merge( $defaults, (array) $attr );

			$mobile_selectors = array();
			$tablet_selectors = array();

			$imgopacity = $attr['opacity'] / 100;

			$box_shadow_position_css = $attr['boxShadowPosition'];

			if ( 'outset' === $attr['boxShadowPosition'] ) {
				$box_shadow_position_css = '';
			}

			$icon_space_right = '';
			if ( 'before' === $attr['iconPosition'] ) {
				$icon_space_right = $attr['iconSpace'];
			}
			$icon_space_left = '';
			if ( 'after' === $attr['iconPosition'] ) {
				$icon_space_left = $attr['iconSpace'];
			}

			$updated_v_margin_tablet = '';
			if ( isset( $attr['vMarginTablet'] ) ) {
				$updated_v_margin_tablet = $attr['vMarginTablet'];
			}

			$updated_v_margin_mobile = '';
			if ( isset( $attr['vMarginMobile'] ) ) {
				$updated_v_margin_mobile = $attr['vMarginMobile'];
			}

			$updated_h__margin_tablet = '';
			if ( isset( $attr['hMarginTablet'] ) ) {
				$updated_h__margin_tablet = $attr['hMarginTablet'];
			}

			$updated_h_margin_mobile = '';
			if ( isset( $attr['hMarginMobile'] ) ) {
				$updated_h_margin_mobile = $attr['hMarginMobile'];
			}

			$updated_v__padding_tablet = '';
			if ( isset( $attr['vPaddingTablet'] ) ) {
				$updated_v__padding_tablet = $attr['vPaddingTablet'];
			}

			$updated_v_padding_mobile = '';
			if ( isset( $attr['vPaddingMobile'] ) ) {
				$updated_v_padding_mobile = $attr['vPaddingMobile'];
			}

			$updated_h_padding_tablet = '';
			if ( isset( $attr['hPaddingTablet'] ) ) {
				$updated_h_padding_tablet = $attr['hPaddingTablet'];
			}

			$updated_h_padding_mobile = '';
			if ( isset( $attr['hPaddingMobile'] ) ) {
				$updated_h_padding_mobile = $attr['hPaddingMobile'];
			}

			$updated_background_color   = '';
			$updated_background_h_color = '';
			$updated_background_image   = '';
			if ( 'color' === $attr['backgroundType'] && ! $attr['inheritFromTheme'] ) {
				$updated_background_color   = $attr['background'];
				$updated_background_h_color = $attr['hbackground'];
			}

			$updated_border_color   = '';
			$updated_border_h_color = '';
			if ( $attr['borderColor'] && ! $attr['inheritFromTheme'] ) {
				$updated_border_color = $attr['borderColor'];
			}
			if ( $attr['borderHColor'] && ! $attr['inheritFromTheme'] ) {
				$updated_border_h_color = $attr['borderHColor'];
			}

			$updated_text_color   = '';
			$updated_text_h_color = '';
			if ( $attr['color'] && ! $attr['inheritFromTheme'] ) {
				$updated_text_color = $attr['color'];
			}
			if ( $attr['hColor'] && ! $attr['inheritFromTheme'] ) {
				$updated_text_h_color = $attr['hColor'];
			}

			if ( 'gradient' === $attr['backgroundType'] ) {
				$updated_background_image = self::generate_background_image_effect(
					$attr['backgroundColor1'],
					$attr['backgroundColor2'],
					$attr['gradientDirection'],
					$attr['colorLocation1'],
					$attr['colorLocation2']
				);
			}

			$selectors        = array(
				' .responsive-block-editor-addons-button__wrapper .responsive-block-editor-addons-button__icon svg' => array(
					'color'  => $attr['icon_color'],
					'width'  => self::get_css_value( $attr['iconsize'], 'px' ),
					'height' => self::get_css_value( $attr['iconsize'], 'px' ),
				),
				' .responsive-block-editor-addons-button__wrapper a:hover .responsive-block-editor-addons-button__icon svg' => array(
					'color' => $attr['icon_hover_color'],
				),
				' .responsive-block-editor-addons-buttons-repeater.responsive-block-editor-addons-button__wrapper:hover .responsive-block-editor-addons-button__link_child, .edit-post-visual-editor.editor-styles-wrapper .wp-block-cover .responsive-block-editor-addons-buttons-child .responsive-block-editor-addons-buttons-repeater.responsive-block-editor-addons-button__wrapper:hover .responsive-block-editor-addons-button__link_child' => array(
					'color' => $updated_text_h_color,
				),
				' .responsive-block-editor-addons-1.responsive-block-editor-addons-button__wrapper' => array(
					'margin-left'   => self::get_css_value( $attr['hMargin'], 'px' ),
					'margin-right'  => self::get_css_value( $attr['hMargin'], 'px' ),
					'margin-top'    => self::get_css_value( $attr['vMargin'], 'px' ),
					'margin-bottom' => self::get_css_value( $attr['vMargin'], 'px' ),
				),
				' .responsive-block-editor-addons-buttons-repeater.responsive-block-editor-addons-button__wrapper' => array(
					'border-color'     => $attr['borderColor'] ? $updated_border_color : '#000',
					'border-radius'    => self::get_css_value( $attr['borderRadius'], 'px' ),
					'border-style'     => $attr['borderStyle'],
					'border-width'     => self::get_css_value( $attr['borderWidth'], 'px' ),
					'box-shadow'       =>
					self::get_css_value( $attr['boxShadowHOffset'], 'px' ) .
					' ' .
					self::get_css_value( $attr['boxShadowVOffset'], 'px' ) .
					' ' .
					self::get_css_value( $attr['boxShadowBlur'], 'px' ) .
					' ' .
					self::get_css_value( $attr['boxShadowSpread'], 'px' ) .
					' ' .
					$attr['boxShadowColor'] .
					' ' .
					$box_shadow_position_css,
					'padding-left'     => self::get_css_value( $attr['hPadding'], 'px' ),
					'padding-right'    => self::get_css_value( $attr['hPadding'], 'px' ),
					'background-image' => $updated_background_image,
					'padding-top'      => self::get_css_value( $attr['vPadding'], 'px' ),
					'padding-bottom'   => self::get_css_value( $attr['vPadding'], 'px' ),
					'background-color' => $attr['inheritFromTheme'] ? '' : $updated_background_color,
					'font-size'        => self::get_css_value( $attr['buttonFontSize'], 'px' ),
					'font-family'      => $attr['buttonFontFamily'],
					'font-weight'      => $attr['buttonFontWeight'],
					'line-height'      => $attr['buttonLineHeight'],
					'opacity'          => $imgopacity,
					'color'            => $attr['inheritFromTheme'] ? '' : ( $attr['color'] ? $attr['color'] : '#000' ),
					'text-decoration'  => 'none',
				),
				' .responsive-block-editor-addons-buttons-repeater.responsive-block-editor-addons-button__wrapper div' => array(
					'color' => $attr['inheritFromTheme'] ? '' : ( $attr['color'] ? $attr['color'] : '#000' ),
				),
				' .responsive-block-editor-addons-buttons-repeater.responsive-block-editor-addons-button__wrapper:hover' => array(
					'border-color'     => $updated_border_h_color,
					'background-color' => $updated_background_h_color,
				),
				' .responsive-block-editor-addons-button__icon' => array(
					'margin-left'  => $icon_space_left . 'px',
					'margin-right' => $icon_space_right . 'px',
				),
				' .responsive-block-editor-addons-buttons-repeater.responsive-block-editor-addons-button__wrapper.wp-block-button__link' => array(
					'background-color' => '',
				),
			);
			$mobile_selectors = array(
				' .responsive-block-editor-addons-1.responsive-block-editor-addons-button__wrapper' => array(
					'margin-top'    => self::get_css_value( $updated_v_margin_mobile, 'px' ),
					'margin-bottom' => self::get_css_value( $updated_v_margin_mobile, 'px' ),
					'margin-left'   => self::get_css_value( $updated_h_margin_mobile, 'px' ),
					'margin-right'  => self::get_css_value( $updated_h_margin_mobile, 'px' ),
				),
				' .responsive-block-editor-addons-buttons-repeater.responsive-block-editor-addons-button__wrapper' => array(
					'padding-top'    => self::get_css_value( $updated_v_padding_mobile, 'px' ),
					'padding-bottom' => self::get_css_value( $updated_v_padding_mobile, 'px' ),
					'padding-left'   => self::get_css_value( $updated_h_padding_mobile, 'px' ),
					'padding-right'  => self::get_css_value( $updated_h_padding_mobile, 'px' ),
					'font-size'      => self::get_css_value( $attr['buttonFontSizeMobile'], 'px' ) . '',
				),
			);

			$tablet_selectors = array(
				' .responsive-block-editor-addons-1.responsive-block-editor-addons-button__wrapper' => array(
					'margin-top'    => self::get_css_value( $updated_v_margin_tablet, 'px' ),
					'margin-bottom' => self::get_css_value( $updated_v_margin_tablet, 'px' ),
					'margin-left'   => self::get_css_value( $updated_h__margin_tablet, 'px' ),
					'margin-right'  => self::get_css_value( $updated_h__margin_tablet, 'px' ),
				),
				' .responsive-block-editor-addons-buttons-repeater.responsive-block-editor-addons-button__wrapper' => array(
					'padding-top'    => self::get_css_value( $updated_v__padding_tablet, 'px' ),
					'padding-bottom' => self::get_css_value( $updated_v__padding_tablet, 'px' ),
					'padding-left'   => self::get_css_value( $updated_h_padding_tablet, 'px' ),
					'padding-right'  => self::get_css_value( $updated_h_padding_tablet, 'px' ),
					'font-size'      => self::get_css_value( $attr['buttonFontSizeTablet'], 'px' ),
				),
			);

			$combined_selectors = array(
				'desktop' => $selectors,
				'tablet'  => $tablet_selectors,
				'mobile'  => $mobile_selectors,
			);

			$id  = '.responsive-block-editor-addons-buttons-child.block-' . $id;
			$css = Responsive_Block_Editor_Addons_Frontend_Styles_Helper::responsive_block_editor_addons_generate_all_css( $combined_selectors, $id );
			return $css;
		}

		/**
		 * Get Defaults for buttons block
		 *
		 * @return array
		 */
		public static function get_responsive_block_buttons_child_default_attributes() {
			return array(
				'buttonAlignment'      => 'center',
				'fontFamily'           => 'Default',
				'fontWeight'           => '',
				'fontSubset'           => '',
				'label'                => '#Click Here',
				'link'                 => '',
				'target'               => '_blank',
				'iconsize'             => 16,
				'counterId'            => 1,
				'vPadding'             => 10,
				'hPadding'             => 14,
				'vPaddingTablet'       => '',
				'hPaddingTablet'       => '',
				'vPaddingMobile'       => '',
				'hPaddingMobile'       => '',
				'vMargin'              => 10,
				'vMarginTablet'        => '',
				'vMarginMobile'        => '',
				'hMargin'              => 14,
				'hMarginTablet'        => '',
				'hMarginMobile'        => '',
				'borderWidth'          => 1,
				'borderRadius'         => 2,
				'borderStyle'          => 'solid',
				'borderColor'          => '#000',
				'borderHColor'         => '',
				'color'                => '#000',
				'background'           => '',
				'hColor'               => '',
				'sizeType'             => 'px',
				'sizeMobile'           => '',
				'sizeTablet'           => '',
				'lineHeight'           => '',
				'lineHeightType'       => 'em',
				'lineHeightMobile'     => '',
				'lineHeightTablet'     => '',
				'opensInNewTab'        => '',
				'colorLocation1'       => 0,
				'colorLocation2'       => 100,
				'gradientDirection'    => 90,
				'backgroundColor1'     => '',
				'backgroundColor2'     => '',
				'opacity'              => 100,
				'icon'                 => '',
				'iconPosition'         => 'after',
				'buttonFontFamily'     => '',
				'buttonFontSize'       => '',
				'buttonFontSizeTablet' => '',
				'buttonFontSizeMobile' => '',
				'buttonLineHeight'     => '',
				'boxShadowColor'       => '',
				'boxShadowHOffset'     => 0,
				'boxShadowVOffset'     => 0,
				'boxShadowBlur'        => '',
				'boxShadowSpread'      => '',
				'boxShadowPosition'    => 'outset',
				'icon_color'           => '#3a3a3a',
				'icon_hover_color'     => '',
				'hbackground'          => '',
				'iconSpace'            => 8,
				'buttonFontWeight'     => '400',
				'inheritFromTheme'     => false,
				'hoverEffect'          => '',
				'backgroundType'       => 'none',
			);
		}

		/**
		 * Get Call to action Block CSS
		 *
		 * @param array  $attr The block attributes.
		 * @param string $id The selector ID.
		 * @return array Styles.
		 */
		public static function get_responsive_block_call_to_action_css( $attr, $id ) {
			$defaults = self::get_responsive_block_call_to_action_default_attributes();
			$attr     = array_merge( $defaults, (array) $attr );

			$mobile_selectors = array();
			$tablet_selectors = array();

			$imgopacity              = $attr['opacity'] / 100;
			$button_text_opacity     = $attr['ctaTextOpacity'] / 100;
			$box_shadow_position_css = $attr['boxShadowPosition'];

			if ( 'outset' === $attr['boxShadowPosition'] ) {
				$box_shadow_position_css = '';
			}

			$updated_button_background_color   = '';
			$updated_button_background_h_color = '';
			$updated_background_color          = '';
			$updated_background_type           = '';
			$updated_button_background_type    = '';

			if ( 'color' === $attr['buttonbackgroundType'] ) {
				$updated_button_background_color = 'empty' !== $attr['buttonBackgroundColor'] && '#2091e1' === $attr['ctaBackColor'] ? $attr['buttonBackgroundColor'] : $attr['ctaBackColor']; // For compatibility with v1.3.2.
			}

			if ( 'color' === $attr['buttonHbackgroundType'] ) {
				$updated_button_background_h_color = 'empty' !== $attr['hbuttonBackgroundColor'] && '' === $attr['ctaHoverBackColor'] ? $attr['hbuttonBackgroundColor'] : ( $attr['ctaHoverBackColor'] ? $attr['ctaHoverBackColor'] : 'none' ); // For compatibility with v1.3.2.
			}

			if ( 'color' === $attr['backgroundType'] ) {
				$updated_background_color = 'empty' !== $attr['ctaBackgroundColor'] && '#f2f2f2' === $attr['backgroundColor'] ? self::hex_to_rgb( $attr['ctaBackgroundColor'], $imgopacity ) : self::hex_to_rgb( $attr['backgroundColor'], $imgopacity ); // For compatibility with v1.3.2.
			} else {
				$updated_background_color = '#ffffff';
			}

			if ( 'gradient' === $attr['backgroundType'] ) {
				$updated_background_type = self::generate_background_image_effect( $attr['backgroundColor1'], $attr['backgroundColor2'], $attr['gradientDirection'], $attr['colorLocation1'], $attr['colorLocation2'] );
			}

			if ( 'gradient' === $attr['buttonbackgroundType'] ) {
				$updated_button_background_type = self::generate_background_image_effect( $attr['buttonbackgroundColor1'], $attr['buttonbackgroundColor2'], $attr['buttongradientDirection'], $attr['buttoncolorLocation1'], $attr['buttoncolorLocation2'] );

			}

			$cta_icon_margin = '';
			if ( 'before' === $attr['iconPosition'] ) {
				$cta_icon_margin = 'auto ' . self::get_css_value( $attr['iconSpace'], 'px' ) . ' auto 0';
			} elseif ( 'after' === $attr['iconPosition'] ) {
				$cta_icon_margin = 'auto 0 auto ' . self::get_css_value( $attr['iconSpace'], 'px' );
			}

			$selectors = array(
				' .responsive-block-editor-addons-cta-button-wrapper .responsive-block-editor-addons-cta-button' => array(
					'color'   => 'empty' !== $attr['buttonTextColor'] && '#fff' === $attr['ctaColor'] ? $attr['buttonTextColor'] : $attr['ctaColor'], // For compatibility with v1.3.2.
					'opacity' => $button_text_opacity,
				),

				' .responsive-block-editor-addons-cta-button-wrapper:hover .responsive-block-editor-addons-cta-button' => array(
					'color' => 'empty' !== $attr['hbuttonTextColor'] && '#e6f2ff' === $attr['ctaHoverColor'] ? $attr['hbuttonTextColor'] : $attr['ctaHoverColor'], // For compatibility with v1.3.2.
				),

				' .responsive-block-editor-addons-cta-button-wrapper:hover' => array(
					'border-color'     => 'empty' !== $attr['buttonborderHColor'] && '' === $attr['ctaHoverBorderColor'] ? $attr['buttonborderHColor'] : $attr['ctaHoverBorderColor'], // For compatibility with v1.3.2.
					'background-color' => $updated_button_background_h_color,
					'background-image' => 'color' === $attr['buttonHbackgroundType'] ? 'none' : $updated_button_background_color,
				),

				' .responsive-block-editor-addons-cta-link-text' => array(
					'color'       => 'empty' !== $attr['buttonTextColor'] && '#fff' === $attr['ctaColor'] ? $attr['buttonTextColor'] : $attr['ctaColor'], // For compatibility with v1.3.2.
					'font-family' => $attr['buttonTextFontFamily'],
					'font-size'   => self::get_css_value( $attr['buttonTextFontSize'], 'px' ),
					'font-weight' => $attr['buttonTextFontWeight'],
					'line-height' => $attr['buttonTextLineHeight'],
				),

				' .responsive-block-editor-addons-cta-link-text:hover' => array(
					'color' => 'empty' !== $attr['hbuttonTextColor'] && '#e6f2ff' === $attr['ctaHoverColor'] ? $attr['hbuttonTextColor'] : $attr['ctaHoverColor'], // For compatibility with v1.3.2.
				),

				' .responsive-block-editor-addons-cta-button__icon svg' => array(
					'fill' => $attr['icon_color'],
				),

				' .responsive-block-editor-addons-cta-title' => array(
					'font-size'     => self::get_css_value( $attr['ctaTitleFontSize'], 'px' ),
					'color'         => $attr['ctaTextColor'],
					'line-height'   => 999 !== $attr['headingLineHeight'] && 1.8 === $attr['ctaTitleLineHeight'] ? $attr['headingLineHeight'] : $attr['ctaTitleLineHeight'], // For compatibility with v1.3.2.
					'font-family'   => $attr['ctaTitleFontFamily'],
					'font-weight'   => 'empty' !== $attr['headingFontWeight'] && '400' === $attr['ctaTitleFontWeight'] ? $attr['headingFontWeight'] : $attr['ctaTitleFontWeight'], // For compatibility with v1.3.2.
					'margin-bottom' => self::get_css_value( $attr['titleSpace'], 'px' ),
				),

				'' => array(
					'background-color' => $updated_background_color,
					'background-image' => $updated_background_type,
					'border-radius'    => self::get_css_value( $attr['borderRadius'], 'px' ),
					'box-shadow'       =>
						self::get_css_value( $attr['boxShadowHOffset'], 'px' ) .
						' ' .
						self::get_css_value( $attr['boxShadowVOffset'], 'px' ) .
						' ' .
						self::get_css_value( $attr['boxShadowBlur'], 'px' ) .
						' ' .
						self::get_css_value( $attr['boxShadowSpread'], 'px' ) .
						' ' .
						$attr['boxShadowColor'] .
						' ' .
						$box_shadow_position_css,
					'padding-top'      => 999 !== $attr['topPadding'] && 20 === $attr['blockTopPadding'] ? self::get_css_value( $attr['topPadding'], 'px' ) : self::get_css_value( $attr['blockTopPadding'], 'px' ), // For compatibility with v1.3.2.
					'padding-bottom'   => 999 !== $attr['bottomPadding'] && 20 === $attr['blockBottomPadding'] ? self::get_css_value( $attr['bottomPadding'], 'px' ) : self::get_css_value( $attr['blockBottomPadding'], 'px' ), // For compatibility with v1.3.2.
					'padding-left'     => 999 !== $attr['leftPadding'] && 20 === $attr['blockLeftPadding'] ? self::get_css_value( $attr['leftPadding'], 'px' ) : self::get_css_value( $attr['blockLeftPadding'], 'px' ), // For compatibility with v1.3.2.
					'padding-right'    => 999 !== $attr['rightPadding'] && 20 === $attr['blockRightPadding'] ? self::get_css_value( $attr['rightPadding'], 'px' ) : self::get_css_value( $attr['blockRightPadding'], 'px' ), // For compatibility with v1.3.2.
				),

				' .responsive-block-editor-addons-cta-image' => array(
					'background-image'    => 'empty' !== $attr['imgURL'] && '' === $attr['backgroundImage'] ? $attr['imgURL'] : ( $attr['backgroundImage'] ? 'url(' . $attr['backgroundImage'] . ')' : null ), // For compatibility with v1.3.2.
					'height'              => 100 . '%',
					'background-position' => 'empty' !== $attr['imagePosition'] && 'center center' === $attr['backgroundImagePosition'] ? $attr['imagePosition'] : $attr['backgroundImagePosition'], // For compatibility with v1.3.2.
					'background-repeat'   => 'empty' !== $attr['imageRepeat'] && 'no-repeat' === $attr['backgroundImageRepeat'] ? $attr['imageRepeat'] : $attr['backgroundImageRepeat'], // For compatibility with v1.3.2.
					'background-size'     => 'empty' !== $attr['thumbsize'] && 'cover' === $attr['backgroundImageSize'] ? $attr['thumbsize'] : $attr['backgroundImageSize'], // For compatibility with v1.3.2.
					'border-radius'       => self::get_css_value( $attr['borderRadius'], 'px' ),
				),

				' .responsive-block-editor-addons-cta-text' => array(
					'color'         => $attr['ctaTextColor'],
					'font-size'     => self::get_css_value( $attr['ctaTextFontSize'], 'px' ),
					'font-family'   => $attr['ctaTextFontFamily'],
					'line-height'   => 999 !== $attr['contentLineHeight'] && 1.75 === $attr['ctaTextLineHeight'] ? $attr['contentLineHeight'] : $attr['ctaTextLineHeight'], // For compatibility with v1.3.2.
					'font-weight'   => 'empty' !== $attr['contentFontWeight'] && '400' === $attr['ctaTextFontWeight'] ? $attr['contentFontWeight'] : $attr['ctaTextFontWeight'], // For compatibility with v1.3.2.
					'margin-bottom' => self::get_css_value( $attr['subtitleSpace'], 'px' ),
				),

				' .responsive-block-editor-addons-cta-button-wrapper' => array(
					'color'            => $attr['ctaColor'],
					'background-color' => $attr['ctaBackColor'],
					'padding-top'      => 999 !== $attr['buttonvPadding'] && 10 === $attr['ctaVpadding'] ? self::get_css_value( $attr['buttonvPadding'], 'px' ) : self::get_css_value( $attr['ctaVpadding'], 'px' ), // For compatibility with v1.3.2.
					'padding-bottom'   => 999 !== $attr['buttonvPadding'] && 10 === $attr['ctaVpadding'] ? self::get_css_value( $attr['buttonvPadding'], 'px' ) : self::get_css_value( $attr['ctaVpadding'], 'px' ), // For compatibility with v1.3.2.
					'padding-left'     => 999 !== $attr['buttonhPadding'] && 14 === $attr['ctaHpadding'] ? self::get_css_value( $attr['buttonhPadding'], 'px' ) : self::get_css_value( $attr['ctaHpadding'], 'px' ), // For compatibility with v1.3.2.
					'padding-right'    => 999 !== $attr['buttonhPadding'] && 14 === $attr['ctaHpadding'] ? self::get_css_value( $attr['buttonhPadding'], 'px' ) : self::get_css_value( $attr['ctaHpadding'], 'px' ), // For compatibility with v1.3.2.
					'border-style'     => 'empty' !== $attr['buttonborderStyle'] && 'solid' === $attr['ctaBorderStyle'] ? $attr['buttonborderStyle'] : ( $attr['ctaBorderStyle'] ? $attr['ctaBorderStyle'] : 'solid' ), // For compatibility with v1.3.2.
					'border-width'     => 999 !== $attr['buttonborderWidth'] && 1 === $attr['ctaBorderWidth'] ? $attr['buttonborderWidth'] : ( $attr['ctaBorderWidth'] ? self::get_css_value( $attr['ctaBorderWidth'], 'px' ) : '0px' ), // For compatibility with v1.3.2.
					'background-image' => $updated_button_background_type,
					'margin-bottom'    => self::get_css_value( $attr['buttonSpace'], 'px' ),
					'border-color'     => 'empty' !== $attr['buttonborderColor'] && '' === $attr['ctaBorderColor'] ? $attr['buttonborderColor'] : $attr['ctaBorderColor'], // For compatibility with v1.3.2.
					'background-color' => $updated_button_background_color,
					'border-radius'    => self::get_css_value( $attr['ctaBorderRadius'], 'px' ),
				),

				' .responsive-block-editor-addons-cta-button__icon' => array(
					'margin' => $cta_icon_margin,
				),
				' .responsive-block-editor-addons-cta-button' => array(
					'font-family' => $attr['buttonTextFontFamily'],
					'font-size'   => self::get_css_value( $attr['buttonTextFontSize'], 'px' ),
					'font-weight' => $attr['buttonTextFontWeight'],
					'line-height' => $attr['buttonTextLineHeight'],
				),

			);
			$mobile_selectors = array(
				' h2.responsive-block-editor-addons-cta-title' => array(
					'font-size' => self::get_css_value( $attr['ctaTitleFontSizeMobile'], 'px' ),
				),
				'' => array(
					$box_shadow_position_css,
					'padding-top'    => self::get_css_value( $attr['blockTopPaddingMobile'], 'px' ),
					'padding-bottom' => self::get_css_value( $attr['blockBottomPaddingMobile'], 'px' ),
					'padding-left'   => self::get_css_value( $attr['blockLeftPaddingMobile'], 'px' ),
					'padding-right'  => self::get_css_value( $attr['blockRightPaddingMobile'], 'px' ),
				),
				' .responsive-block-editor-addons-cta-title' => array(
					'margin-bottom' => self::get_css_value( $attr['titleSpaceMobile'], 'px' ),
				),
				' .responsive-block-editor-addons-cta-text' => array(
					'margin-bottom' => self::get_css_value( $attr['subtitleSpaceMobile'], 'px' ),
					'font-size'     => self::get_css_value( $attr['ctaTextFontSizeMobile'], 'px' ),
				),
				' .responsive-block-editor-addons-cta-button-wrapper' => array(
					'margin-bottom'  => self::get_css_value( $attr['buttonSpaceMobile'], 'px' ),
					'padding-top'    => self::get_css_value( $attr['ctaVpaddingMobile'], 'px' ),
					'padding-bottom' => self::get_css_value( $attr['ctaVpaddingMobile'], 'px' ),
					'padding-left'   => self::get_css_value( $attr['ctaHpaddingMobile'], 'px' ),
					'padding-right'  => self::get_css_value( $attr['ctaHpaddingMobile'], 'px' ),
				),
				' .responsive-block-editor-addons-cta-button' => array(
					'font-size' => self::get_css_value( $attr['buttonTextFontSizeMobile'], 'px' ),
				),
				' .responsive-block-editor-addons-cta-link-text' => array(
					'font-size' => self::get_css_value( $attr['buttonTextFontSizeMobile'], 'px' ),
				),
			);

			$tablet_selectors = array(
				' h2.responsive-block-editor-addons-cta-title' => array(
					'font-size' => self::get_css_value( $attr['ctaTitleFontSizeTablet'], 'px' ),
				),
				'' => array(
					$box_shadow_position_css,
					'padding-top'    => self::get_css_value( $attr['blockTopPaddingTablet'], 'px' ),
					'padding-bottom' => self::get_css_value( $attr['blockBottomPaddingTablet'], 'px' ),
					'padding-left'   => self::get_css_value( $attr['blockLeftPaddingTablet'], 'px' ),
					'padding-right'  => self::get_css_value( $attr['blockRightPaddingTablet'], 'px' ),
				),
				' .responsive-block-editor-addons-cta-title' => array(
					'margin-bottom' => self::get_css_value( $attr['titleSpaceTablet'], 'px' ),
				),
				' .responsive-block-editor-addons-cta-text' => array(
					'margin-bottom' => self::get_css_value( $attr['subtitleSpaceTablet'], 'px' ),
					'font-size'     => self::get_css_value( $attr['ctaTextFontSizeTablet'], 'px' ),
				),
				' .responsive-block-editor-addons-cta-button-wrapper' => array(
					'margin-bottom'  => self::get_css_value( $attr['buttonSpaceTablet'], 'px' ),
					'padding-top'    => self::get_css_value( $attr['ctaVpaddingTablet'], 'px' ),
					'padding-bottom' => self::get_css_value( $attr['ctaVpaddingTablet'], 'px' ),
					'padding-left'   => self::get_css_value( $attr['ctaHpaddingTablet'], 'px' ),
					'padding-right'  => self::get_css_value( $attr['ctaHpaddingTablet'], 'px' ),
				),
				' .responsive-block-editor-addons-cta-button' => array(
					'font-size' => self::get_css_value( $attr['buttonTextFontSizeTablet'], 'px' ),
				),
				' .responsive-block-editor-addons-cta-link-text' => array(
					'font-size' => self::get_css_value( $attr['buttonTextFontSizeTablet'], 'px' ),
				),
			);

			$combined_selectors = array(
				'desktop' => $selectors,
				'tablet'  => $tablet_selectors,
				'mobile'  => $mobile_selectors,
			);

			$id  = '.responsive-block-editor-addons-block-call-to-action.block-' . $id;
			$css = Responsive_Block_Editor_Addons_Frontend_Styles_Helper::responsive_block_editor_addons_generate_all_css( $combined_selectors, $id );

			return $css;
		}

		/**
		 * Get Defaults for Call to action block
		 *
		 * @return array
		 */
		public static function get_responsive_block_call_to_action_default_attributes() {
			return array(
				'block_id'                 => '',
				'buttonText'               => '',
				'buttonUrl'                => '',
				'buttonAlignment'          => 'center',
				'ctaBackColor'             => '#2091e1',
				'ctaColor'                 => '#fff',
				'buttonSize'               => 'responsive-block-editor-addons-cta-button-size-medium',
				'buttonShape'              => 'responsive-block-editor-addons-cta-button-shape-rounded',
				'buttonTarget'             => false,
				'ctaTitle'                 => '',
				'ctaTitleFontFamily'       => '',
				'ctaTextFontFamily'        => '',
				'ctaTitleFontSize'         => '22',
				'ctaTitleFontSizeMobile'   => '22',
				'ctaTitleFontSizeTablet'   => '22',
				'ctaTextFontSize'          => '16',
				'ctaTextFontSizeMobile'    => '16',
				'ctaTextFontSizeTablet'    => '16',
				'ctaText'                  => '',
				'ctaWidth'                 => '',
				'backgroundColor'          => '#f2f2f2',
				'ctaTextColor'             => '',
				'backgroundImage'          => '',
				'imgID'                    => '',
				'imgAlt'                   => '',
				'dimRatio'                 => 50,
				'opacity'                  => 100,
				'ctaTitleLineHeight'       => 1.8,
				'ctaTitleFontWeight'       => '400',
				'ctaTextLineHeight'        => 1.75,
				'ctaTextFontWeight'        => '400',
				'ctaVpadding'              => 18,
				'ctaHpadding'              => 14,
				'ctaBorderWidth'           => 1,
				'ctaBorderStyle'           => 'solid',
				'icon'                     => '',
				'iconPosition'             => 'after',
				'counterId'                => 1,
				'ctaHoverBackColor'        => '',
				'ctaHoverColor'            => '#e6f2ff',
				'ctaBorderColor'           => '',
				'ctaHoverBorderColor'      => '',
				'resctaType'               => 'button',
				'ctalinkText'              => '',
				'titleSpace'               => 25,
				'subtitleSpace'            => 28,
				'titleSpaceMobile'         => 25,
				'subtitleSpaceMobile'      => 28,
				'titleSpaceTablet'         => 25,
				'subtitleSpaceTablet'      => 28,
				'iconSpace'                => 8,
				'colorLocation1'           => 0,
				'colorLocation2'           => 100,
				'gradientDirection'        => 90,
				'backgroundColor1'         => '',
				'backgroundColor2'         => '',
				'backgroundType'           => 'color',
				'buttoncolorLocation1'     => 0,
				'buttoncolorLocation2'     => 100,
				'buttongradientDirection'  => 90,
				'buttonbackgroundColor1'   => '',
				'buttonbackgroundColor2'   => '',
				'buttonbackgroundType'     => 'color',
				'boxShadowColor'           => '',
				'boxShadowHOffset'         => 0,
				'boxShadowVOffset'         => 0,
				'boxShadowBlur'            => '',
				'boxShadowSpread'          => '',
				'boxShadowPosition'        => 'outset',
				'icon_color'               => '#3a3a3a',
				'blockTopPadding'          => 20,
				'blockBottomPadding'       => 20,
				'blockLeftPadding'         => 20,
				'blockRightPadding'        => 20,
				'blockTopPaddingMobile'    => 20,
				'blockBottomPaddingMobile' => 20,
				'blockLeftPaddingMobile'   => 20,
				'blockRightPaddingMobile'  => 20,
				'blockTopPaddingTablet'    => 20,
				'blockBottomPaddingTablet' => 20,
				'blockLeftPaddingTablet'   => 20,
				'blockRightPaddingTablet'  => 20,
				'backgroundImagePosition'  => 'center center',
				'backgroundImageRepeat'    => 'no-repeat',
				'backgroundImageSize'      => 'cover',
				'buttonSpace'              => 28,
				'buttonSpaceMobile'        => 28,
				'buttonSpaceTablet'        => 28,
				'borderRadius'             => 12,
				'buttonTextFontFamily'     => '',
				'buttonTextFontSize'       => 18,
				'buttonTextFontSizeMobile' => '',
				'buttonTextFontSizeTablet' => '',
				'buttonTextLineHeight'     => 1,
				'buttonTextFontWeight'     => '400',
				'ctaBorderRadius'          => 4,
				'ctaVpaddingMobile'        => '',
				'ctaHpaddingMobile'        => '',
				'ctaVpaddingTablet'        => '',
				'ctaHpaddingTablet'        => '',
				'ctaTextOpacity'           => 100,
				'buttonHbackgroundType'    => 'none',
				'buttonBackgroundColor'    => 'empty', // For compatibility with v1.3.2.
				'hbuttonBackgroundColor'   => 'empty', // For compatibility with v1.3.2.
				'buttonTextColor'          => 'empty', // For compatibility with v1.3.2.
				'hbuttonTextColor'         => 'empty', // For compatibility with v1.3.2.
				'buttonborderHColor'       => 'empty', // For compatibility with v1.3.2.
				'topPadding'               => 999, // For compatibility with v1.3.2.
				'bottomPadding'            => 999, // For compatibility with v1.3.2.
				'leftPadding'              => 999, // For compatibility with v1.3.2.
				'rightPadding'             => 999, // For compatibility with v1.3.2.
				'imgURL'                   => 'empty', // For compatibility with v1.3.2.
				'imagePosition'            => 'empty', // For compatibility with v1.3.2.
				'imageRepeat'              => 'empty', // For compatibility with v1.3.2.
				'thumbsize'                => 'empty', // For compatibility with v1.3.2.
				'buttonvPadding'           => 999, // For compatibility with v1.3.2.
				'buttonhPadding'           => 999, // For compatibility with v1.3.2.
				'buttonborderStyle'        => 'empty', // For compatibility with v1.3.2.
				'buttonborderColor'        => 'empty', // For compatibility with v1.3.2.
				'buttonborderWidth'        => 999, // For compatibility with v1.3.2.
				'ctaBackgroundColor'       => 'empty', // For compatibility with v1.3.2.
				'headingLineHeight'        => 999, // For compatibility with v1.3.2.
				'headingFontWeight'        => 'empty', // For compatibility with v1.3.2.
				'contentLineHeight'        => 999, // For compatibility with v1.3.2.
				'contentFontWeight'        => 'empty', // For compatibility with v1.3.2.
			);
		}

		/**
		 * Get Card Block CSS
		 *
		 * @param array  $attr The block attributes.
		 * @param string $id The selector ID.
		 * @return array Styles.
		 */
		public static function get_responsive_block_card_css( $attr, $id ) {
			$defaults = self::get_responsive_block_card_default_attributes();
			$attr     = array_merge( $defaults, (array) $attr );

			$mobile_selectors = array();
			$tablet_selectors = array();

			$imgopacity  = $attr['opacity'] / 100;
			$butopacity  = 999 !== $attr['butopacity'] && 100 === $attr['butopacity'] ? $attr['butopacity'] / 100 : $attr['buttonopacity'] / 100; // For compatibility with v1.3.2.
			$buthopacity = $attr['buttonHopacity'] / 100;
			$textopacity = $attr['ctaTextOpacity'] / 100;

			$updated_button_color            = '';
			$updated_buttonh_color           = '';
			$updated_background_color        = '';
			$updated_background_type         = '';
			$updated_button_background_color = '';
			$box_shadow_position_css         = $attr['boxShadowPosition'];
			$background_image_url_check      = $attr['backgroundImage'] ? $attr['backgroundImage'] : null;

			if ( 'outset' === $attr['boxShadowPosition'] ) {
				$box_shadow_position_css = '';
			}

			if ( 'color' === $attr['buttonbackgroundType'] ) {
				$updated_button_color = 'empty' !== $attr['buttonColor'] && '' === $attr['ctaBackColor'] ? self::hex_to_rgb( $attr['buttonColor'], $butopacity ) : self::hex_to_rgb( $attr['ctaBackColor'] ? $attr['ctaBackColor'] : '#2091e1', $butopacity ); // For compatibility with v1.3.2.
			}

			if ( 'color' === $attr['buttonHbackgroundType'] ) {
				$updated_buttonh_color = 'empty' !== $attr['buttonhColor'] && '' === $attr['ctaHoverBackColor'] ? self::hex_to_rgb( $attr['buttonhColor'], $buthopacity ) : self::hex_to_rgb( $attr['ctaHoverBackColor'] ? $attr['ctaHoverBackColor'] : 'none', $buthopacity ); // For compatibility with v1.3.2.
			}

			if ( 'color' === $attr['backgroundType'] ) {
				$updated_background_color = 'empty' !== $attr['itemBackgroundColor'] && '' === $attr['backgroundColor'] ? self::hex_to_rgb( $attr['itemBackgroundColor'], $imgopacity ) : self::hex_to_rgb( $attr['backgroundColor'] ? $attr['backgroundColor'] : '#fff', $imgopacity );  // For compatibility with v1.3.2.
			} else {
				$updated_background_color = '#fff';
			}

			if ( 'gradient' === $attr['backgroundType'] ) {
				$updated_background_type = self::generate_background_image_effect( $attr['backgroundColor1'], $attr['backgroundColor2'], $attr['gradientDirection'], $attr['colorLocation1'], $attr['colorLocation2'] );
			}

			if ( 'gradient' === $attr['buttonbackgroundType'] ) {
				$updated_button_background_color = self::generate_background_image_effect( $attr['buttonbackgroundColor1'], $attr['buttonbackgroundColor2'], $attr['buttongradientDirection'], $attr['buttoncolorLocation1'], $attr['buttoncolorLocation2'] );
			}

			$selectors = array(
				' .responsive-block-editor-addons-card-button-inner .res-button' => array(
					'color'   => 'empty' !== $attr['buttonTextColor'] && '#fff' === $attr['ctaColor'] ? $attr['buttonTextColor'] : $attr['ctaColor'], // For compatibility with v1.3.2.
					'opacity' => $textopacity,
				),

				' .responsive-block-editor-addons-card-button-inner:hover .res-button' => array(
					'color' => 'empty' !== $attr['buttonhTextColor'] && '#e6f2ff' === $attr['ctaHoverColor'] ? $attr['buttonhTextColor'] : $attr['ctaHoverColor'], // For compatibility with v1.3.2.
				),

				' .responsive-block-editor-addons-card-button-inner .responsive-block-editor-addons-button__icon svg' => array(
					'fill' => $attr['icon_color'],
				),

				' .responsive-block-editor-addons-card-button-inner:hover .responsive-block-editor-addons-button__icon svg' => array(
					'fill' => $attr['icon_hcolor'],
				),

				' .wp-block-responsive-block-editor-addons-card-item__button-wrapper .responsive-block-editor-addons-card-button-inner' => array(
					'background-color' => $updated_button_color,
				),

				' .responsive-block-editor-addons-card-button-inner:hover' => array(
					'background-color' => $updated_buttonh_color,
					'border-color'     => $attr['ctaHoverBorderColor'],
					'background-image' => 'color' === $attr['buttonHbackgroundType'] ? 'none' : $updated_button_background_color,
				),

				''                    => array(
					'margin-bottom' => self::get_css_value( $attr['blockbotmargin'], 'px' ) . ' !important',
					'margin-top'    => self::get_css_value( $attr['blockmargin'], 'px' ) . ' !important',
					'margin-left'   => self::get_css_value( $attr['blockleftmargin'], 'px' ),
					'margin-right'  => self::get_css_value( $attr['blockrightmargin'], 'px' ),
					'z-index'       => $attr['blockzindex'],
				),

				' .wp-block-responsive-block-editor-addons-card-item' => array(
					'border-color'     => 'empty' !== $attr['borderColor'] && '' === $attr['blockBorderColor'] ? $attr['borderColor'] : $attr['blockBorderColor'], // For compatibility with v1.3.2.
					'border-style'     => 'empty' !== $attr['borderStyle'] && 'none' === $attr['blockBorderStyle'] ? $attr['borderStyle'] : $attr['blockBorderStyle'], // For compatibility with v1.3.2.
					'border-width'     => 999 !== $attr['borderWidth'] && 0 === $attr['blockBorderWidth'] ? self::get_css_value( $attr['borderWidth'], 'px' ) : self::get_css_value( $attr['blockBorderWidth'], 'px' ), // For compatibility with v1.3.2.
					'border-radius'    => 999 !== $attr['borderRadius'] && 12 === $attr['blockBorderRadius'] ? self::get_css_value( $attr['borderRadius'], 'px' ) : self::get_css_value( $attr['blockBorderRadius'], 'px' ), // For compatibility with v1.3.2.
					'background-color' => $updated_background_color,
					'background-image' => $updated_background_type,
					'box-shadow'       => self::get_css_value( $attr['boxShadowHOffset'], 'px' ) .
						' ' .
						self::get_css_value( $attr['boxShadowVOffset'], 'px' ) .
						' ' .
						self::get_css_value( $attr['boxShadowBlur'], 'px' ) .
						' ' .
						self::get_css_value( $attr['boxShadowSpread'], 'px' ) .
						' ' .
						$attr['boxShadowColor'] .
						' ' .
						$box_shadow_position_css,
				),

				' .responsive-block-editor-addons-card-background-image' => array(
					'background-image'    => 'url(' . $background_image_url_check . ')',
					'height'              => 100 . '%',
					'background-position' => 'empty' !== $attr['bgimagePosition'] && 'center center' === $attr['backgroundImagePosition'] ? $attr['bgimagePosition'] : $attr['backgroundImagePosition'], // For compatibility with v1.3.2.
					'background-repeat'   => 'empty' !== $attr['bgimageRepeat'] && 'no-repeat' === $attr['backgroundImageRepeat'] ? $attr['bgimageRepeat'] : $attr['backgroundImageRepeat'], // For compatibility with v1.3.2.
					'background-size'     => 'empty' !== $attr['bgthumbsize'] && 'cover' === $attr['backgroundImageSize'] ? $attr['bgthumbsize'] : $attr['backgroundImageSize'], // For compatibility with v1.3.2.
				),

				' .responsive-block-editor-addons-card-avatar' => array(
					'height' => self::get_css_value( $attr['imageheight'], 'px' ),
				),

				' .responsive-block-editor-addons-card-avatar-img' => array(
					'background-position' => $attr['imagePosition'],
					'background-repeat'   => $attr['imageRepeat'],
					'background-size'     => $attr['thumbsize'],
				),

				' .responsive-block-editor-addons-card-avatar-img.responsive-block-editor-addons-card-avatar-img-0' => array(
					'background-image' => 'url(' . $attr['backgroundImageOne'] . ')',
					'display'          => $attr['backgroundImageOne'] ? 'block' : 'none',
				),

				' .responsive-block-editor-addons-card-avatar-img.responsive-block-editor-addons-card-avatar-img-1' => array(
					'background-image' => 'url(' . $attr['backgroundImageTwo'] . ')',
					'display'          => $attr['backgroundImageTwo'] ? 'block' : 'none',
				),

				' .responsive-block-editor-addons-card-avatar-img.responsive-block-editor-addons-card-avatar-img-2' => array(
					'background-image' => 'url(' . $attr['backgroundImageThree'] . ')',
					'display'          => $attr['backgroundImageThree'] ? 'block' : 'none',
				),

				' .responsive-block-editor-addons-card-avatar-img.responsive-block-editor-addons-card-avatar-img-3' => array(
					'background-image' => 'url(' . $attr['backgroundImageFour'] . ')',
					'display'          => $attr['backgroundImageFour'] ? 'block' : 'none',
				),

				' .card-content-wrap' => array(
					'text-align' => $attr['contentAlignment'],
				),

				' .wp-block-responsive-block-editor-addons-card-item__title' => array(
					'margin-top'    => 0,
					'margin-bottom' => self::get_css_value( $attr['titleSpace'], 'px' ),
					'color'         => $attr['textColor'],
					'line-height'   => $attr['headingLineHeight'],
					'font-family'   => $attr['headingFontFamily'],
					'font-weight'   => $attr['headingFontWeight'],
					'font-size'     => self::get_css_value( $attr['headingFontSize'], 'px' ),
				),

				' .wp-block-responsive-block-editor-addons-card-item__subtitle' => array(
					'margin-top'    => 0,
					'margin-bottom' => self::get_css_value( $attr['subtitleSpace'], 'px' ),
					'color'         => $attr['textColor'],
					'line-height'   => $attr['subLineHeight'],
					'font-weight'   => $attr['subFontWeight'],
					'font-family'   => $attr['subFontFamily'],
					'font-size'     => self::get_css_value( $attr['subFontSize'], 'px' ),
				),

				' .wp-block-responsive-block-editor-addons-card-item__content' => array(
					'margin-top'    => 0,
					'margin-bottom' => self::get_css_value( $attr['contentSpace'], 'px' ),
					'color'         => $attr['textColor'],
					'line-height'   => $attr['contentLineHeight'],
					'font-weight'   => $attr['contentFontWeight'],
					'font-size'     => self::get_css_value( $attr['contentFontSize'], 'px' ),
					'font-family'   => $attr['contentFontFamily'],
				),

				' .responsive-block-editor-addons-card-button-inner' => array(
					'padding-top'      => 999 !== $attr['vPadding'] && 10 === $attr['ctaVpadding'] ? self::get_css_value( $attr['vPadding'], 'px' ) : self::get_css_value( $attr['ctaVpadding'], 'px' ), // For compatibility with v1.3.2.
					'padding-bottom'   => 999 !== $attr['vPadding'] && 10 === $attr['ctaVpadding'] ? self::get_css_value( $attr['vPadding'], 'px' ) : self::get_css_value( $attr['ctaVpadding'], 'px' ), // For compatibility with v1.3.2.
					'padding-left'     => 999 !== $attr['hPadding'] && 14 === $attr['ctaHpadding'] ? self::get_css_value( $attr['hPadding'], 'px' ) : self::get_css_value( $attr['ctaHpadding'], 'px' ), // For compatibility with v1.3.2.
					'padding-right'    => 999 !== $attr['hPadding'] && 14 === $attr['ctaHpadding'] ? self::get_css_value( $attr['hPadding'], 'px' ) : self::get_css_value( $attr['ctaHpadding'], 'px' ), // For compatibility with v1.3.2.
					'margin-top'       => self::get_css_value( $attr['vMargin'], 'px' ),
					'margin-bottom'    => self::get_css_value( $attr['vMargin'], 'px' ),
					'margin-left'      => self::get_css_value( $attr['hMargin'], 'px' ),
					'margin-right'     => self::get_css_value( $attr['hMargin'], 'px' ),
					'border-style'     => 'empty' !== $attr['butborderStyle'] && 'none' !== $attr['ctaBorderStyle'] ? $attr['butborderStyle'] : $attr['ctaBorderStyle'], // For compatibility with v1.3.2.
					'border-radius'    => 999 !== $attr['butborderRadius'] && 2 === $attr['ctaBorderRadius'] ? self::get_css_value( $attr['butborderRadius'], 'px' ) : self::get_css_value( $attr['ctaBorderRadius'], 'px' ), // For compatibility with v1.3.2.
					'border-width'     => 999 !== $attr['butborderWidth'] && 1 === $attr['ctaBorderWidth'] ? self::get_css_value( $attr['butborderWidth'], 'px' ) : self::get_css_value( $attr['ctaBorderWidth'], 'px' ), // For compatibility with v1.3.2.
					'background-image' => $updated_button_background_color,
					'border-color'     => $attr['ctaBorderColor'],
				),

			);

			$mobile_selectors = array(
				'' => array(
					'margin-bottom' => self::get_css_value( $attr['blockbotmarginMobile'], 'px' ) . ' !important',
					'margin-top'    => self::get_css_value( $attr['blockmarginMobile'], 'px' ) . ' !important',
					'margin-left'   => self::get_css_value( $attr['blockleftmarginMobile'], 'px' ),
					'margin-right'  => self::get_css_value( $attr['blockrightmarginMobile'], 'px' ),
				),

				' .wp-block-responsive-block-editor-addons-card-item__title' => array(
					'margin-bottom' => self::get_css_value( $attr['titleSpaceMobile'], 'px' ),
					'font-size'     => self::get_css_value( $attr['headingFontSizeMobile'], 'px' ),
				),

				' .wp-block-responsive-block-editor-addons-card-item__subtitle' => array(
					'margin-bottom' => self::get_css_value( $attr['subtitleSpaceMobile'], 'px' ),
					'font-size'     => self::get_css_value( $attr['subFontSizeMobile'], 'px' ),
				),
				' .wp-block-responsive-block-editor-addons-card-item__content' => array(
					'font-size'     => self::get_css_value( $attr['contentFontSizeMobile'], 'px' ),
					'margin-bottom' => self::get_css_value( $attr['contentSpaceMobile'], 'px' ),
				),
				' .responsive-block-editor-addons-card-button-inner' => array(
					'padding-top'    => $attr['ctaVpaddingMobile'] ? self::get_css_value( $attr['ctaVpaddingMobile'], 'px' ) : self::get_css_value( $attr['ctaVpadding'], 'px' ),
					'padding-bottom' => $attr['ctaVpaddingMobile'] ? self::get_css_value( $attr['ctaVpaddingMobile'], 'px' ) : self::get_css_value( $attr['ctaVpadding'], 'px' ),
					'padding-left'   => $attr['ctaHpaddingMobile'] ? self::get_css_value( $attr['ctaHpaddingMobile'], 'px' ) : self::get_css_value( $attr['ctaHpadding'], 'px' ),
					'padding-right'  => $attr['ctaHpaddingMobile'] ? self::get_css_value( $attr['ctaHpaddingMobile'], 'px' ) : self::get_css_value( $attr['ctaHpadding'], 'px' ),
					'margin-top'     => $attr['vMarginMobile'] ? self::get_css_value( $attr['vMarginMobile'], 'px' ) : self::get_css_value( $attr['vMargin'], 'px' ),
					'margin-bottom'  => $attr['vMarginMobile'] ? self::get_css_value( $attr['vMarginMobile'], 'px' ) : self::get_css_value( $attr['vMargin'], 'px' ),
					'margin-left'    => $attr['hMarginMobile'] ? self::get_css_value( $attr['hMarginMobile'], 'px' ) : self::get_css_value( $attr['hMargin'], 'px' ),
					'margin-right'   => $attr['hMarginMobile'] ? self::get_css_value( $attr['hMarginMobile'], 'px' ) : self::get_css_value( $attr['hMargin'], 'px' ),
				),
			);

			$tablet_selectors = array(
				'' => array(
					'margin-bottom' => self::get_css_value( $attr['blockbotmarginTablet'], 'px' ) . ' !important',
					'margin-top'    => self::get_css_value( $attr['blockmarginTablet'], 'px' ) . ' !important',
					'margin-left'   => self::get_css_value( $attr['blockleftmarginTablet'], 'px' ),
					'margin-right'  => self::get_css_value( $attr['blockrightmarginTablet'], 'px' ),
				),

				' .wp-block-responsive-block-editor-addons-card-item__title' => array(
					'margin-bottom' => self::get_css_value( $attr['titleSpaceTablet'], 'px' ),
					'font-size'     => self::get_css_value( $attr['headingFontSizeTablet'], 'px' ),
				),

				' .wp-block-responsive-block-editor-addons-card-item__subtitle' => array(
					'margin-bottom' => self::get_css_value( $attr['subtitleSpaceTablet'], 'px' ),
					'font-size'     => self::get_css_value( $attr['subFontSizeTablet'], 'px' ),
				),
				' .wp-block-responsive-block-editor-addons-card-item__content' => array(
					'font-size'     => self::get_css_value( $attr['contentFontSizeTablet'], 'px' ),
					'margin-bottom' => self::get_css_value( $attr['contentSpaceTablet'], 'px' ),
				),
				' .responsive-block-editor-addons-card-button-inner' => array(
					'padding-top'    => $attr['ctaVpaddingTablet'] ? self::get_css_value( $attr['ctaVpaddingTablet'], 'px' ) : self::get_css_value( $attr['ctaVpadding'], 'px' ),
					'padding-bottom' => $attr['ctaVpaddingTablet'] ? self::get_css_value( $attr['ctaVpaddingTablet'], 'px' ) : self::get_css_value( $attr['ctaVpadding'], 'px' ),
					'padding-left'   => $attr['ctaHpaddingTablet'] ? self::get_css_value( $attr['ctaHpaddingTablet'], 'px' ) : self::get_css_value( $attr['ctaHpadding'], 'px' ),
					'padding-right'  => $attr['ctaHpaddingTablet'] ? self::get_css_value( $attr['ctaHpaddingTablet'], 'px' ) : self::get_css_value( $attr['ctaHpadding'], 'px' ),
					'margin-top'     => $attr['vMarginTablet'] ? self::get_css_value( $attr['vMarginTablet'], 'px' ) : self::get_css_value( $attr['vMargin'], 'px' ),
					'margin-bottom'  => $attr['vMarginTablet'] ? self::get_css_value( $attr['vMarginTablet'], 'px' ) : self::get_css_value( $attr['vMargin'], 'px' ),
					'margin-left'    => $attr['hMarginTablet'] ? self::get_css_value( $attr['hMarginTablet'], 'px' ) : self::get_css_value( $attr['hMargin'], 'px' ),
					'margin-right'   => $attr['hMarginTablet'] ? self::get_css_value( $attr['hMarginTablet'], 'px' ) : self::get_css_value( $attr['hMargin'], 'px' ),
				),
			);

			$combined_selectors = array(
				'desktop' => $selectors,
				'tablet'  => $tablet_selectors,
				'mobile'  => $mobile_selectors,
			);

			$id  = '.responsive-block-editor-addons-block-card.block-' . $id;
			$css = Responsive_Block_Editor_Addons_Frontend_Styles_Helper::responsive_block_editor_addons_generate_all_css( $combined_selectors, $id );

			return $css;
		}

		/**
		 * Get Defaults for card block
		 *
		 * @return array
		 */
		public static function get_responsive_block_card_default_attributes() {
			return array(
				'block_id'                => '',
				'cardsArray'              => '',
				'count'                   => '',
				'gutter'                  => 'medium',
				'stack'                   => 'mobile',
				'contentAlign'            => 'center',
				'textColor'               => '',
				'backgroundColor'         => '',
				'itemBackgroundColor'     => 'empty', // For compatibility with v1.3.2.
				'ctaBackColor'            => '',
				'ctaColor'                => '#fff',
				'buttonColor'             => 'empty', // For compatibility with v1.3.2.
				'buttonTextColor'         => 'empty', // For compatibility with v1.3.2.
				'boxShadowColor'          => '',
				'boxShadowHOffset'        => 0,
				'boxShadowVOffset'        => 0,
				'boxShadowBlur'           => 0,
				'boxShadowSpread'         => 0,
				'boxShadowPosition'       => 'outset',
				'titleSpace'              => 8,
				'subtitleSpace'           => 16,
				'contentSpace'            => 16,
				'titleSpaceMobile'        => '',
				'subtitleSpaceMobile'     => '',
				'contentSpaceMobile'      => '',
				'titleSpaceTablet'        => '',
				'subtitleSpaceTablet'     => '',
				'contentSpaceTablet'      => '',
				'buttonSpace'             => 20,
				'opacity'                 => 100,
				'resshowImage'            => true,
				'colorLocation1'          => 0,
				'colorLocation2'          => 100,
				'gradientDirection'       => 90,
				'backgroundImage'         => '',
				'backgroundColor'         => '',
				'backgroundColor1'        => '',
				'backgroundColor2'        => '#fff',
				'backgroundType'          => 'none',
				'imageopacity'            => 20,
				'imageSize'               => 'full',
				'imagePosition'           => 'center center',
				'imageRepeat'             => 'no-repeat',
				'thumbsize'               => 'cover',
				'imageheight'             => 200,
				'blockmargin'             => 2,
				'blockmarginMobile'       => '',
				'blockmarginTablet'       => '',
				'blockzindex'             => 1,
				'icon'                    => '',
				'iconPosition'            => 'after',
				'icon_color'              => '#3a3a3a',
				'counterId'               => 1,
				'ctaHoverBackColor'       => '',
				'ctaHoverColor'           => '#e6f2ff',
				'buttonhColor'            => 'empty', // For compatibility with v1.3.2.
				'buttonhTextColor'        => 'empty', // For compatibility with v1.3.2.
				'buttonopacity'           => 100,
				'butopacity'              => 999,  // For compatibility with v1.3.2.
				'buttonHopacity'          => 100,
				'ctaVpadding'             => 10,
				'ctaHpadding'             => 14,
				'vPadding'                => 999, // For compatibility with v1.3.2.
				'hPadding'                => 999, // For compatibility with v1.3.2.
				'vMargin'                 => 10,
				'hMargin'                 => 0,
				'ctaBorderWidth'          => 1,
				'ctaBorderRadius'         => 2,
				'butborderWidth'          => 999, // For compatibility with v1.3.2.
				'butborderRadius'         => 999, // For compatibility with v1.3.2.
				'butborderStyle'          => 'empty', // For compatibility with v1.3.2.
				'ctaBorderStyle'          => 'none',
				'buttonSize'              => 'responsive-block-editor-addons-button-size-medium',
				'buttoncolorLocation1'    => 0,
				'buttoncolorLocation2'    => 100,
				'buttongradientDirection' => 90,
				'buttonbackgroundColor1'  => '',
				'buttonbackgroundColor2'  => '#fff',
				'buttonbackgroundType'    => 'none',
				'icon_hcolor'             => '#3a3a3a',
				'headingFontFamily'       => '',
				'subFontFamily'           => '',
				'contentFontFamily'       => '',
				'subLineHeight'           => 1,
				'subFontWeight'           => 400,
				'subFontSize'             => 16,
				'headingLineHeight'       => 1,
				'headingFontWeight'       => 900,
				'headingFontSize'         => 20,
				'contentLineHeight'       => 2,
				'contentFontSize'         => 16,
				'contentFontWeight'       => 400,
				'blockbotmargin'          => 2,
				'blockleftmargin'         => 0,
				'blockrightmargin'        => 0,
				'blockbotmarginMobile'    => '',
				'blockleftmarginMobile'   => '',
				'blockrightmarginMobile'  => '',
				'blockbotmarginTablet'    => '',
				'blockleftmarginTablet'   => '',
				'blockrightmarginTablet'  => '',
				'bgimageSize'             => 'full', // For compatibility with v1.3.2.
				'bgimagePosition'         => 'center center', // For compatibility with v1.3.2.
				'bgimageRepeat'           => 'no-repeat', // For compatibility with v1.3.2.
				'bgthumbsize'             => 'cover',
				'blockBorderStyle'        => 'none',
				'blockBorderColor'        => '',
				'blockBorderWidth'        => 0,
				'blockBorderRadius'       => 12,
				'borderStyle'             => 'empty', // For compatibility with v1.3.2.
				'borderColor'             => 'empty', // For compatibility with v1.3.2.
				'borderWidth'             => 999, // For compatibility with v1.3.2.
				'borderRadius'            => 999, // For compatibility with v1.3.2.
				'buttonTarget'            => 'false',
				'contentAlignment'        => 'left',
				'backgroundImageOne'      => '',
				'backgroundImageTwo'      => '',
				'backgroundImageThree'    => '',
				'backgroundImageFour'     => '',
				'backgroundImagePosition' => 'center center',
				'backgroundImageRepeat'   => 'no-repeat',
				'backgroundImageSize'     => 'cover',
				'headingFontSizeMobile'   => '',
				'headingFontSizeTablet'   => '',
				'subFontSizeMobile'       => '',
				'subFontSizeTablet'       => '',
				'contentFontSizeMobile'   => '',
				'contentFontSizeTablet'   => '',
				'ctaBorderColor'          => '',
				'ctaHoverBorderColor'     => '',
				'ctaTextOpacity'          => 100,
				'ctaHpaddingTablet'       => '',
				'ctaHpaddingMobile'       => '',
				'ctaVpaddingTablet'       => '',
				'ctaVpaddingMobile'       => '',
				'vMarginTablet'           => '',
				'vMarginMobile'           => '',
				'hMarginTablet'           => '',
				'hMarginMobile'           => '',
				'buttonHbackgroundType'   => 'none',
			);
		}

		/**
		 * Get Content timeline Block CSS
		 *
		 * @param array  $attr The block attributes.
		 * @param string $id The selector ID.
		 * @return array Styles.
		 */
		public static function get_responsive_block_content_timeline_css( $attr, $id ) {
			$defaults = self::get_responsive_block_content_timeline_default_attributes();
			$attr     = array_merge( $defaults, (array) $attr );

			$mobile_selectors = array();
			$tablet_selectors = array();

			$border_with_color = '13px solid' . $attr['backgroundColor'];

			$imgopacity = $attr['opacity'] / 100;

			$selectors = array(
				' .responsive-timeline__left .responsive-timeline__day-left .responsive-timeline__arrow:after' => array(
					'border-right' => $border_with_color,
				),

				' .responsive-timeline__right .responsive-timeline__day-right .responsive-timeline__arrow:after' => array(
					'border-left' => $border_with_color,
				),

				' .responsive-timeline__line'             => array(
					'background-color' => $attr['separatorColor'],
				),

				' .responsive-timeline__line__inner'      => array(
					'background-color' => $attr['separatorFillColor'],
				),

				' .responsive-timeline__main .responsive-block-editor-addons-ifb-icon' => array(
					'color' => $attr['iconColor'],
				),

				' .responsive-timeline__main .responsive-block-editor-addons-ifb-icon svg' => array(
					'fill' => $attr['iconColor'],
				),

				' .responsive-timeline__marker'           => array(
					'background-color' => $attr['separatorBg'],
					'border-color'     => $attr['separatorBorder'],
				),

				' .responsive-timeline__main .responsive-timeline__marker.responsive-timeline__in-view-icon' => array(
					'background'   => $attr['iconBgFocus'],
					'border-color' => $attr['borderFocus'],
					'color'        => $attr['iconFocus'],
				),

				' .responsive-timeline__main .responsive-timeline__marker.responsive-timeline__in-view-icon svg' => array(
					'fill' => $attr['iconFocus'],
				),

				' .responsive-timeline__main .responsive-timeline__marker.responsive-timeline__in-view-icon .responsive-timeline__icon-new' => array(
					'color' => $attr['iconFocus'],
				),

				' .responsive-timeline__left-block .responsive-timeline__line' => array(
					'left' => $attr['connectorBgsize'] / 2 . 'px',
				),

				' .responsive-timeline__rightt-block .responsive-timeline__line' => array(
					'right' => $attr['connectorBgsize'] / 2 . 'px',
				),

				' .responsive-timeline__field.responsive-timeline__field-wrap' => array(
					'margin-bottom' => self::get_css_value( $attr['verticalSpace'], 'px' ),
				),

				' .responsive-timeline__marker.out-view-responsive-timeline__icon, .responsive-timeline__marker.in-view-responsive-timeline__icon' => array(
					'margin-left'  => self::get_css_value( $attr['horizontalSpace'], 'px' ),
					'margin-right' => self::get_css_value( $attr['horizontalSpace'], 'px' ),
					'min-width'    => self::get_css_value( $attr['connectorBgsize'], 'px' ),
					'min-height'   => self::get_css_value( $attr['connectorBgsize'], 'px' ),
					'border-width' => self::get_css_value( $attr['borderwidth'], 'px' ),
				),

				' .responsive-block-editor-addons-ifb-icon' => array(
					'width'  => self::get_css_value( $attr['iconSize'], 'px' ),
					'height' => self::get_css_value( $attr['iconSize'], 'px' ),
				),

				' .responsive-timeline__events-inner-new' => array(
					'border-radius'    => self::get_css_value( $attr['itemBorderRadius'], 'px' ),
					'border-width'     => self::get_css_value( $attr['itemBorderWidth'], 'px' ),
					'border-style'     => $attr['itemBorderStyle'],
					'border-color'     => $attr['itemBorderColor'],
					'padding'          => self::get_css_value( $attr['itemPadding'], 'px' ),
					'background-color' => self::hex_to_rgb( $attr['backgroundColor'], $imgopacity ),
				),

				' .responsive-timeline__inner-date-new'   => array(
					'color'       => $attr['dateColor'],
					'line-height' => $attr['dateLineHeight'],
					'font-weight' => $attr['dateFontWeight'],
					'font-size'   => self::get_css_value( $attr['dateFontSize'], 'px' ),
					'font-family' => $attr['dateFontFamily'],
				),

				' .responsive-timeline__heading'          => array(
					'color'         => $attr['headingColor'],
					'line-height'   => $attr['headingLineHeight'],
					'font-weight'   => $attr['headingFontWeight'],
					'font-size'     => self::get_css_value( $attr['headingFontSize'], 'px' ),
					'font-family'   => $attr['headingFontFamily'],
					'margin-bottom' => self::get_css_value( $attr['headingBottomMargin'], 'px' ),
				),

				' .responsive-timeline-desc-content'      => array(
					'color'       => $attr['contentColor'],
					'line-height' => $attr['contentLineHeight'],
					'font-weight' => $attr['contentFontWeight'],
					'font-size'   => self::get_css_value( $attr['contentFontSize'], 'px' ),
					'font-family' => $attr['contentFontFamily'],
				),

				' .responsive-timeline__date-new'         => array(
					'color'       => $attr['dateColor'],
					'line-height' => $attr['dateLineHeight'],
					'font-weight' => $attr['dateFontWeight'],
					'font-size'   => self::get_css_value( $attr['dateFontSize'], 'px' ),
					'font-family' => $attr['dateFontFamily'],
				),

				' .responsive-timeline__line'             => array(
					'background-color' => $attr['separatorColor'],
					'width'            => self::get_css_value( $attr['separatorwidth'], 'px' ),
					'margin-left'      => 'center' !== $attr['timelinAlignment'] ? self::get_css_value( $attr['horizontalSpace'], 'px' ) : '',
					'margin-right'     => 'center' !== $attr['timelinAlignment'] ? self::get_css_value( $attr['horizontalSpace'], 'px' ) : '',
				),

			);

			$mobile_selectors = array(
				' .responsive-timeline__center-block.responsive-timeline__responsive-mobile .responsive-timeline__line' => array(
					'left'  => $attr['connectorBgsize'] / 2 . 'px',
					'right' => $attr['connectorBgsize'] / 2 . 'px',
				),

				' .responsive-timeline__left-block.responsive-timeline__responsive-mobile .responsive-timeline__line' => array(
					'left' => $attr['connectorBgsize'] / 2 . 'px',
				),

				' .responsive-timeline__right-block.responsive-timeline__responsive-mobile .responsive-timeline__line' => array(
					'right' => $attr['connectorBgsize'] / 2 . 'px',
				),
				' .responsive-timeline__field.responsive-timeline__field-wrap' => array(
					'margin-bottom' => self::get_css_value( $attr['verticalSpaceMobile'], 'px' ),
				),

				' .responsive-timeline__marker.out-view-responsive-timeline__icon, .responsive-timeline__marker.in-view-responsive-timeline__icon' => array(
					'margin-left'  => self::get_css_value( $attr['horizontalSpaceMobile'], 'px' ),
					'margin-right' => self::get_css_value( $attr['horizontalSpaceMobile'], 'px' ),
				),
				' .responsive-timeline__date-new'       => array(
					'font-size' => self::get_css_value( $attr['dateFontSizeMobile'], 'px' ),
				),
				' .responsive-timeline-desc-content'    => array(
					'font-size' => self::get_css_value( $attr['contentFontSizeMobile'], 'px' ),
				),
				' .responsive-timeline__heading'        => array(
					'font-size'     => self::get_css_value( $attr['headingFontSizeMobile'], 'px' ),
					'margin-bottom' => self::get_css_value( $attr['headingBottomMarginMobile'], 'px' ),
				),
				' .responsive-timeline__inner-date-new' => array(
					'font-size' => self::get_css_value( $attr['dateFontSizeMobile'], 'px' ),
				),
			);

			$tablet_selectors = array(
				' .responsive-timeline__center-block.responsive-timeline__responsive-tablet .responsive-timeline__line' => array(
					'left'  => $attr['connectorBgsize'] / 2 . 'px',
					'right' => $attr['connectorBgsize'] / 2 . 'px',
				),

				' .responsive-timeline__left-block.responsive-timeline__responsive-tablet .responsive-timeline__line' => array(
					'left' => $attr['connectorBgsize'] / 2 . 'px',
				),

				' .responsive-timeline__right-block.responsive-timeline__responsive-tablet .responsive-timeline__line' => array(
					'right' => $attr['connectorBgsize'] / 2 . 'px',
				),
				' .responsive-timeline__field.responsive-timeline__field-wrap' => array(
					'margin-bottom' => self::get_css_value( $attr['verticalSpaceTablet'], 'px' ),
				),

				' .responsive-timeline__marker.out-view-responsive-timeline__icon, .responsive-timeline__marker.in-view-responsive-timeline__icon' => array(
					'margin-left'  => self::get_css_value( $attr['horizontalSpaceTablet'], 'px' ),
					'margin-right' => self::get_css_value( $attr['horizontalSpaceTablet'], 'px' ),
				),
				' .responsive-timeline__date-new'       => array(
					'font-size' => self::get_css_value( $attr['dateFontSizeTablet'], 'px' ),
				),
				' .responsive-timeline-desc-content'    => array(
					'font-size' => self::get_css_value( $attr['contentFontSizeTablet'], 'px' ),
				),
				' .responsive-timeline__heading'        => array(
					'font-size'     => self::get_css_value( $attr['headingFontSizeTablet'], 'px' ),
					'margin-bottom' => self::get_css_value( $attr['headingBottomMarginTablet'], 'px' ),
				),
				' .responsive-timeline__inner-date-new' => array(
					'font-size' => self::get_css_value( $attr['dateFontSizeTablet'], 'px' ),
				),
			);

			$combined_selectors = array(
				'desktop' => $selectors,
				'tablet'  => $tablet_selectors,
				'mobile'  => $mobile_selectors,
			);

			$id  = '.responsive-block-editor-addons-block-content-timeline.block-' . $id;
			$css = Responsive_Block_Editor_Addons_Frontend_Styles_Helper::responsive_block_editor_addons_generate_all_css( $combined_selectors, $id );
			return $css;
		}

		/**
		 * Get Defaults for content timeline block
		 *
		 * @return array
		 */
		public static function get_responsive_block_content_timeline_default_attributes() {
			return array(
				'block_id'                  => '',
				'timelinAlignment'          => 'center',
				'timelineItems'             => '',
				'dateFormat'                => 'F j, Y',
				'headingTag'                => 'h4',
				't_date'                    => '',
				'displayPostDate'           => true,
				'count'                     => 5,
				'dateBottomspace'           => 5,
				'itemBorderStyle'           => 'none',
				'itemBorderColor'           => '',
				'itemBorderWidth'           => 1,
				'itemBorderRadius'          => 2,
				'itemPadding'               => 20,
				'horizontalSpace'           => 0,
				'verticalSpace'             => 15,
				'headingBottomMargin'       => 15,
				'horizontalSpaceMobile'     => '',
				'verticalSpaceMobile'       => '',
				'headingBottomMarginMobile' => '',
				'horizontalSpaceTablet'     => '',
				'verticalSpaceTablet'       => '',
				'headingBottomMarginTablet' => '',
				'dateLineHeight'            => 1,
				'contentFontFamily'         => '',
				'headingFontFamily'         => '',
				'dateFontFamily'            => '',
				'dateFontWeight'            => '400',
				'dateFontSize'              => 16,
				'headingLineHeight'         => 1,
				'headingFontWeight'         => '400',
				'headingFontSize'           => 20,
				'dateColor'                 => '',
				'headingColor'              => '',
				'contentColor'              => '',
				'backgroundColor'           => '#eee',
				'counterId'                 => 1,
				'contentLineHeight'         => 2,
				'contentFontSize'           => 16,
				'contentFontWeight'         => '400',
				'opacity'                   => 100,
				'separatorColor'            => '#eee',
				'iconColor'                 => '#333',
				'separatorBg'               => '#eee',
				'separatorBorder'           => '#eee',
				'separatorFillColor'        => '#61ce70',
				'iconFocus'                 => '#fff',
				'iconBgFocus'               => '#61ce70',
				'borderFocus'               => '#5cb85c',
				'separatorwidth'            => 3,
				'borderwidth'               => 0,
				'connectorBgsize'           => 35,
				'iconSize'                  => 20,
				'icon'                      => 'fa fa-calendar-alt',
				'stack'                     => 'mobile',
				'arrowlinAlignment'         => 'center',
				'dateFontSizeMobile'        => '',
				'dateFontSizeTablet'        => '',
				'headingFontSizeMobile'     => '',
				'headingFontSizeTablet'     => '',
				'contentFontSizeMobile'     => '',
				'contentFontSizeTablet'     => '',
			);
		}

		/**
		 * Get Expand Block CSS
		 *
		 * @param array  $attr The block attributes.
		 * @param string $id The selector ID.
		 * @return array Styles.
		 */
		public static function get_responsive_block_expand_css( $attr, $id ) {
			$defaults = self::get_responsive_block_expand_default_attributes();
			$attr     = array_merge( $defaults, (array) $attr );

			$mobile_selectors = array();
			$tablet_selectors = array();

			$selectors = array(
				' .responsive-block-editor-addons-expand-block-content' => array(
					'text-align' => $attr['expandAlignment'],
				),

				' .responsive-block-editor-addons-expand-title' => array(
					'margin-bottom' => self::get_css_value( $attr['titleSpace'], 'px' ),
					'font-family'   => $attr['titleFontFamily'],
					'font-size'     => self::get_css_value( $attr['titleFontSize'], 'px' ),
					'font-weight'   => $attr['titleFontWeight'],
					'line-height'   => $attr['titleLineHeight'],
					'color'         => $attr['titleColor'],
				),

				' .responsive-block-editor-addons-expand-less-text' => array(
					'margin-bottom' => self::get_css_value( $attr['textSpace'], 'px' ),
					'font-family'   => $attr['textFontFamily'],
					'font-size'     => self::get_css_value( $attr['textFontSize'], 'px' ),
					'font-weight'   => $attr['textFontWeight'],
					'line-height'   => $attr['textLineHeight'],
					'color'         => $attr['textColor'],
				),

				' .responsive-block-editor-addons-expand-more-text' => array(
					'display'       => 'none',
					'margin-bottom' => self::get_css_value( $attr['textSpace'], 'px' ),
					'font-family'   => $attr['textFontFamily'],
					'font-size'     => self::get_css_value( $attr['textFontSize'], 'px' ),
					'font-weight'   => $attr['textFontWeight'],
					'line-height'   => $attr['textLineHeight'],
					'color'         => $attr['textColor'],
				),

				' .responsive-block-editor-addons-expand-more-toggle-text' => array(
					'margin-bottom' => self::get_css_value( $attr['linkSpace'], 'px' ),
					'font-family'   => $attr['linkFontFamily'],
					'font-size'     => self::get_css_value( $attr['linkFontSize'], 'px' ),
					'font-weight'   => $attr['linkFontWeight'],
					'line-height'   => $attr['linkLineHeight'],
					'color'         => $attr['linkColor'],
				),

				' .responsive-block-editor-addons-expand-less-toggle-text' => array(
					'display'       => 'none',
					'margin-bottom' => self::get_css_value( $attr['linkSpace'], 'px' ),
					'font-family'   => $attr['linkFontFamily'],
					'font-size'     => self::get_css_value( $attr['linkFontSize'], 'px' ),
					'font-weight'   => $attr['linkFontWeight'],
					'line-height'   => $attr['linkLineHeight'],
					'color'         => $attr['linkColor'],
				),
			);

			$mobile_selectors = array(
				' .responsive-block-editor-addons-expand-title' => array(
					'margin-bottom' => self::get_css_value( $attr['titleSpaceMobile'], 'px' ),
					'font-size'     => self::get_css_value( $attr['titleFontSizeMobile'], 'px' ),
				),
				' .responsive-block-editor-addons-expand-less-text' => array(
					'margin-bottom' => self::get_css_value( $attr['textSpaceMobile'], 'px' ),
					'font-size'     => self::get_css_value( $attr['textFontSizeMobile'], 'px' ),
				),
				' .responsive-block-editor-addons-expand-more-text' => array(
					'margin-bottom' => self::get_css_value( $attr['textSpaceMobile'], 'px' ),
					'font-size'     => self::get_css_value( $attr['textFontSizeMobile'], 'px' ),
				),
				' .responsive-block-editor-addons-expand-more-toggle-text' => array(
					'margin-bottom' => self::get_css_value( $attr['linkSpaceMobile'], 'px' ),
					'font-size'     => self::get_css_value( $attr['linkFontSizeMobile'], 'px' ),
				),
				' .responsive-block-editor-addons-expand-less-toggle-text' => array(
					'margin-bottom' => self::get_css_value( $attr['linkSpaceMobile'], 'px' ),
					'font-size'     => self::get_css_value( $attr['linkFontSizeMobile'], 'px' ),
				),
			);

			$tablet_selectors = array(
				' .responsive-block-editor-addons-expand-title' => array(
					'margin-bottom' => self::get_css_value( $attr['titleSpaceTablet'], 'px' ),
					'font-size'     => self::get_css_value( $attr['titleFontSizeTablet'], 'px' ),
				),
				' .responsive-block-editor-addons-expand-less-text' => array(
					'margin-bottom' => self::get_css_value( $attr['textSpaceTablet'], 'px' ),
					'font-size'     => self::get_css_value( $attr['textFontSizeTablet'], 'px' ),
				),
				' .responsive-block-editor-addons-expand-more-text' => array(
					'margin-bottom' => self::get_css_value( $attr['textSpaceTablet'], 'px' ),
					'font-size'     => self::get_css_value( $attr['textFontSizeTablet'], 'px' ),
				),
				' .responsive-block-editor-addons-expand-more-toggle-text' => array(
					'margin-bottom' => self::get_css_value( $attr['linkSpaceTablet'], 'px' ),
					'font-size'     => self::get_css_value( $attr['linkFontSizeTablet'], 'px' ),
				),
				' .responsive-block-editor-addons-expand-less-toggle-text' => array(
					'margin-bottom' => self::get_css_value( $attr['linkSpaceTablet'], 'px' ),
					'font-size'     => self::get_css_value( $attr['linkFontSizeTablet'], 'px' ),
				),
			);

			$combined_selectors = array(
				'desktop' => $selectors,
				'tablet'  => $tablet_selectors,
				'mobile'  => $mobile_selectors,
			);

			$id  = '.responsive-block-editor-addons-block-expand.block-' . $id;
			$css = Responsive_Block_Editor_Addons_Frontend_Styles_Helper::responsive_block_editor_addons_generate_all_css( $combined_selectors, $id );
			return $css;
		}

		/**
		 * Get Defaults for expand block
		 *
		 * @return array
		 */
		public static function get_responsive_block_expand_default_attributes() {
			return array(
				'block_id'            => '',
				'blockTitle'          => 'Title for this block',
				'expandLessText'      => 'Some short text that can be expanded to show more details.',
				'expandMoreText'      => 'Some short text that can be expanded to show more details. Description for this block. Use this space for describing your block. Any text will do. Description for this block. You can use this space for describing your block.',
				'moreLabel'           => 'Show more',
				'lessLabel'           => 'Show less',
				'showTitle'           => true,
				'expandAlignment'     => '',
				'textColor'           => '',
				'linkColor'           => '#0066cc',
				'titleColor'          => '',
				'titleSpace'          => 28,
				'textSpace'           => 20,
				'linkSpace'           => 18,
				'titleSpaceMobile'    => '',
				'textSpaceMobile'     => '',
				'linkSpaceMobile'     => '',
				'titleSpaceTablet'    => '',
				'textSpaceTablet'     => '',
				'linkSpaceTablet'     => '',
				'titleFontFamily'     => '',
				'textFontFamily'      => '',
				'linkFontFamily'      => '',
				'titleFontSize'       => 20,
				'titleFontWeight'     => 400,
				'titleLineHeight'     => 1,
				'textFontSize'        => 16,
				'textFontWeight'      => 400,
				'textLineHeight'      => 2,
				'linkFontSize'        => 16,
				'linkFontWeight'      => 400,
				'linkLineHeight'      => 1,
				'titleFontSizeMobile' => '',
				'titleFontSizeTablet' => '',
				'textFontSizeMobile'  => '',
				'textFontSizeTablet'  => '',
				'linkFontSizeMobile'  => '',
				'linkFontSizeTablet'  => '',
			);
		}

		/**
		 * Get Flipbox Block CSS
		 *
		 * @param array  $attr The block attributes.
		 * @param string $id The selector ID.
		 * @return array Styles.
		 */
		public static function get_responsive_block_flipbox_css( $attr, $id ) {
			$defaults = self::get_responsive_block_flipbox_default_attributes();
			$attr     = array_merge( $defaults, (array) $attr );

			$mobile_selectors = array();
			$tablet_selectors = array();

			$box_shadow_position_css = $attr['boxShadowPosition'];

			if ( 'outset' === $attr['boxShadowPosition'] ) {
				$box_shadow_position_css = '';
			}

			$flip_style = 'rotateY(0deg)';
			$flip_style_back;
			$flip_class;

			if ( 'back_selected' === $attr['colorButtonSelected'] ) {
				$flip_class = 'backSelected';
				if ( 'LTR' === $attr['flipStyleSelected'] ) {
					$flip_style      = 'rotateY(180deg)';
					$flip_style_back = 'rotateY(180deg)';
				}
				if ( 'RTL' === $attr['flipStyleSelected'] ) {
					$flip_style      = 'rotateY(-180deg)';
					$flip_style_back = 'rotateY(-180deg)';
				}
				if ( 'BTT' === $attr['flipStyleSelected'] ) {
					$flip_style      = 'rotateX(180deg)';
					$flip_style_back = 'rotateX(180deg)';
				}
				if ( 'TTB' === $attr['flipStyleSelected'] ) {
					$flip_style      = 'rotateX(-180deg)';
					$flip_style_back = 'rotateX(-180deg)';
				}
			} else {
				$flip_class = 'frontSelected';
				if ( 'LTR' === $attr['flipStyleSelected'] ) {
					$flip_style      = 'rotateY(0deg)';
					$flip_style_back = 'rotateY(180deg)';
				}
				if ( 'RTL' === $attr['flipStyleSelected'] ) {
					$flip_style      = 'rotateY(0deg)';
					$flip_style_back = 'rotateY(-180deg)';
				}
				if ( 'BTT' === $attr['flipStyleSelected'] ) {
					$flip_style      = 'rotateX(0deg)';
					$flip_style_back = 'rotateX(180deg)';
				}
				if ( 'TTB' === $attr['flipStyleSelected'] ) {
					$flip_style      = 'rotateX(0deg)';
					$flip_style_back = 'rotateX(-180deg)';
				}
			};

			$transition_speed_sec = $attr['transitionSpeed'] / 10;

			$flipbox_transition =
				$attr['transitionSpeed'] < 10
				? 'transform 0.' . $attr['transitionSpeed'] . 's'
				: 'transform ' . $transition_speed_sec . 's';

			$coloropacity     = $attr['colorOpacity'] / 100;
			$backcoloropacity = $attr['backColorOpacity'] / 100;
			$imageopacity     = $attr['imageOpacity'] / 100;
			$backimageopacity = $attr['backImageOpacity'] / 100;

			$background_front = '';
			$background_back  = '';

			if ( $attr['backgroundImage'] ) {
				$background_front = 'linear-gradient(' .

				self::hex_to_rgb( $attr['frontBackgroundColor'], $coloropacity ) .

				',' .

				self::hex_to_rgb( $attr['frontBackgroundColor'], $coloropacity ) .

				'),url(' .

				$attr['backgroundImage'] .

				')';
			}
			if ( $attr['backBackgroundImage'] ) {
				$background_back = 'linear-gradient(' .

				self::hex_to_rgb( $attr['backBackgroundColor'], $backcoloropacity ) .

				',' .

				self::hex_to_rgb( $attr['backBackgroundColor'], $backcoloropacity ) .

				'),url(' .

				$attr['backBackgroundImage'] .

				')';
			}

			$background_image_gradient = '';
			$btn_color                 = $attr['ctaBackColor'];
			$btn_opacity               = $attr['buttonopacity'];
			if ( 'gradient' === $attr['buttonbackgroundType'] ) {
				$background_image_gradient = 'linear-gradient(' . $attr['buttongradientDirection'] . 'deg, ' . $attr['buttonbackgroundColor1'] . ' ' . $attr['buttoncolorLocation1'] . '%, ' . $attr['buttonbackgroundColor2'] . ' ' . $attr['buttoncolorLocation2'] . '%)';
			} elseif ( 'color' === $attr['buttonbackgroundType'] ) {
				$btn_color   = $attr['ctaBackColor'];
				$btn_opacity = $attr['buttonopacity'];
			};

			$background_hover_image_gradient = '';
			$btn_h_color                     = $attr['ctaHoverBackColor'];
			$btn_h_opacity                   = $attr['buttonHopacity'];
			if ( 'gradient' === $attr['buttonHbackgroundType'] ) {
				$background_hover_image_gradient = 'linear-gradient(' . $attr['buttonHgradientDirection'] . 'deg, ' . $attr['buttonHbackgroundColor1'] . ' ' . $attr['buttonHcolorLocation1'] . '%, ' . $attr['buttonHbackgroundColor2'] . ' ' . $attr['buttonHcolorLocation2'] . '%)';
			} elseif ( 'color' === $attr['buttonHbackgroundType'] ) {
				$btn_h_color   = $attr['ctaHoverBackColor'];
				$btn_h_opacity = $attr['buttonHopacity'];
			}

			$selectors        = array(
				' '               => array(
					'margin-bottom' => self::get_css_value( $attr['bottomMargin'], 'px' ) . ' !important',
					'margin-top'    => self::get_css_value( $attr['topMargin'], 'px' ) . ' !important',
				),
				' .wp-block-responsive-block-editor-addons-flip-box' => array(
					'height' => self::get_css_value( $attr['height'], 'px' ),
				),
				' .wp-block-responsive-block-editor-addons-flip-box .flip-box-inner' => array(
					'transition' => $flipbox_transition,
				),
				' .wp-block-responsive-block-editor-addons-flip-box .flip-box-front' => array(
					'background-image'      => $background_front,
					'background-position'   => $attr['backgroundPosition'],
					'background-attachment' => $attr['backgroundAttachment'],
					'background-repeat'     => $attr['backgroundRepeat'],
					'background-size'       => $attr['backgroundSize'],
					'background-color'      => self::hex_to_rgb( $attr['frontBackgroundColor'], $coloropacity ),
					'color'                 => $attr['frontTextColor'],
					'border-color'          => 'empty' !== $attr['borderColor'] && ! $attr['blockBorderColor'] ? $attr['borderColor'] : $attr['blockBorderColor'], // For compatibility with v1.3.2.
					'border-style'          => 'empty' !== $attr['borderStyle'] && 'none' === $attr['blockBorderStyle'] ? $attr['borderStyle'] : $attr['blockBorderStyle'], // For compatibility with v1.3.2.
					'border-width'          => 999 !== $attr['borderWidth'] && 2 === $attr['blockBorderWidth'] ? self::get_css_value( $attr['borderWidth'], 'px' ) : self::get_css_value( $attr['blockBorderWidth'], 'px' ), // For compatibility with v1.3.2.
					'border-radius'         => 999 !== $attr['borderRadius'] && ! $attr['blockBorderRadius'] ? self::get_css_value( $attr['borderRadius'], 'px' ) : self::get_css_value( $attr['blockBorderRadius'], 'px' ), // For compatibility with v1.3.2.
					'box-shadow'            =>
					self::get_css_value( $attr['boxShadowHOffset'], 'px' ) .
					' ' .
					self::get_css_value( $attr['boxShadowVOffset'], 'px' ) .
					' ' .
					self::get_css_value( $attr['boxShadowBlur'], 'px' ) .
					' ' .
					self::get_css_value( $attr['boxShadowSpread'], 'px' ) .
					' ' .
					$attr['boxShadowColor'] .
					' ' .
					$box_shadow_position_css,
					'height'                => self::get_css_value( $attr['height'], 'px' ),
					'padding-top'           => 999 !== $attr['topPadding'] && 0 === $attr['frontTopPadding'] ? self::get_css_value( $attr['topPadding'], 'px' ) : self::get_css_value( $attr['frontTopPadding'], 'px' ), // For compatibility with v1.3.2.
					'padding-bottom'        => 999 !== $attr['bottomPadding'] && 0 === $attr['frontBottomPadding'] ? self::get_css_value( $attr['bottomPadding'], 'px' ) : self::get_css_value( $attr['frontBottomPadding'], 'px' ), // For compatibility with v1.3.2.
					'padding-left'          => 999 !== $attr['leftPadding'] && 0 === $attr['frontLeftPadding'] ? self::get_css_value( $attr['leftPadding'], 'px' ) : self::get_css_value( $attr['frontLeftPadding'], 'px' ), // For compatibility with v1.3.2.
					'padding-right'         => 999 !== $attr['frontRightPadding'] && 0 === $attr['rightPadding'] ? self::get_css_value( $attr['rightPadding'], 'px' ) : self::get_css_value( $attr['frontRightPadding'], 'px' ),  // For compatibility with v1.3.2.
				),
				' .wp-block-responsive-block-editor-addons-flip-box__title' => array(
					'color'       => $attr['frontTextColor'],
					'font-family' => $attr['frontTitleFontFamily'],
					'font-size'   => self::get_css_value( $attr['frontTitleFontSize'], 'px' ),
					'font-weight' => $attr['frontTitleFontWeight'],
					'line-height' => $attr['frontTitleLineHeight'],
				),
				' .wp-block-responsive-block-editor-addons-flip-box__subtitle' => array(
					'color'       => $attr['frontTextColor'],
					'font-family' => $attr['frontSubtitleFontFamily'],
					'font-size'   => self::get_css_value( $attr['frontSubtitleFontSize'], 'px' ),
					'font-weight' => $attr['frontSubtitleFontWeight'],
					'line-height' => $attr['frontSubtitleLineHeight'],
				),
				' .flip-box-back' => array(
					'background-image'      => $background_back,
					'background-position'   => $attr['backBackgroundPosition'],
					'background-attachment' => $attr['backBackgroundAttachment'],
					'background-repeat'     => $attr['backBackgroundRepeat'],
					'background-size'       => $attr['backBackgroundSize'],
					'background-color'      => self::hex_to_rgb( $attr['backBackgroundColor'], $backcoloropacity ),
					'color'                 => $attr['backTextColor'],
					'transform'             => $flip_style_back,
					'border-color'          => 'empty' !== $attr['borderColor'] && ! $attr['blockBorderColor'] ? $attr['borderColor'] : $attr['blockBorderColor'], // For compatibility with v1.3.2.
					'border-style'          => 'empty' !== $attr['borderStyle'] && 'none' === $attr['blockBorderStyle'] ? $attr['borderStyle'] : $attr['blockBorderStyle'], // For compatibility with v1.3.2.
					'border-width'          => 999 !== $attr['borderWidth'] && 2 === $attr['blockBorderWidth'] ? self::get_css_value( $attr['borderWidth'], 'px' ) : self::get_css_value( $attr['blockBorderWidth'], 'px' ), // For compatibility with v1.3.2.
					'border-radius'         => 999 !== $attr['borderRadius'] && ! $attr['blockBorderRadius'] ? self::get_css_value( $attr['borderRadius'], 'px' ) : self::get_css_value( $attr['blockBorderRadius'], 'px' ), // For compatibility with v1.3.2.
					'box-shadow'            =>
						self::get_css_value( $attr['boxShadowHOffset'], 'px' ) .
						' ' .
						self::get_css_value( $attr['boxShadowVOffset'], 'px' ) .
						' ' .
						self::get_css_value( $attr['boxShadowBlur'], 'px' ) .
						' ' .
						self::get_css_value( $attr['boxShadowSpread'], 'px' ) .
						' ' .
						$attr['boxShadowColor'] .
						' ' .
						$box_shadow_position_css,
					'height'                => self::get_css_value( $attr['height'], 'px' ),
					'padding-top'           => 999 !== $attr['backtopPadding'] && 0 === $attr['backTopPadding'] ? self::get_css_value( $attr['backtopPadding'], 'px' ) : self::get_css_value( $attr['backTopPadding'], 'px' ), // For compatibility with v1.3.2.
					'padding-bottom'        => 999 !== $attr['backbottomPadding'] && 0 === $attr['backBottomPadding'] ? self::get_css_value( $attr['backbottomPadding'], 'px' ) : self::get_css_value( $attr['backBottomPadding'], 'px' ), // For compatibility with v1.3.2.
					'padding-left'          => 999 !== $attr['backleftPadding'] && 0 === $attr['backLeftPadding'] ? self::get_css_value( $attr['backleftPadding'], 'px' ) : self::get_css_value( $attr['backLeftPadding'], 'px' ), // For compatibility with v1.3.2.
					'padding-right'         => 999 !== $attr['backrightPadding'] && 0 === $attr['backRightPadding'] ? self::get_css_value( $attr['backrightPadding'], 'px' ) : self::get_css_value( $attr['backRightPadding'], 'px' ), // For compatibility with v1.3.2.
				),
				' .wp-block-responsive-block-editor-addons-flip-box__backtitle' => array(
					'color'       => $attr['backTextColor'],
					'font-family' => $attr['backTitleFontFamily'],
					'font-size'   => self::get_css_value( $attr['backTitleFontSize'], 'px' ),
					'font-weight' => $attr['backTitleFontWeight'],
					'line-height' => $attr['backTitleLineHeight'],
				),
				' .wp-block-responsive-block-editor-addons-flip-box__backsubtitle' => array(
					'color'       => $attr['backTextColor'],
					'font-family' => $attr['backSubtitleFontFamily'],
					'font-size'   => self::get_css_value( $attr['backSubtitleFontSize'], 'px' ),
					'font-weight' => $attr['backSubtitleFontWeight'],
					'line-height' => $attr['backSubtitleLineHeight'],
				),
				' .wp-block-responsive-block-editor-addons-flip-box-dashicon-fronticon-wrap' => array(
					'font-size' => self::get_css_value( $attr['iconSize'], 'px' ),
				),
				' .wp-block-responsive-block-editor-addons-flip-box-dashicon-fronticon-wrap svg' => array(
					'font-size' => self::get_css_value( $attr['iconSize'], 'px' ),
					'fill'      => $attr['iconColor'],
					'height'    => self::get_css_value( $attr['iconSize'], 'px' ),
					'width'     => self::get_css_value( $attr['iconSize'], 'px' ),
				),
				' .dashicons'     => array(
					'width'  => 'auto !important',
					'height' => 'auto !important',
				),
				' .wp-block-responsive-block-editor-addons-flip-box-dashicon-backicon-wrap' => array(
					'font-size' => self::get_css_value( $attr['backIconSize'], 'px' ),
				),
				' .wp-block-responsive-block-editor-addons-flip-box-dashicon-backicon-wrap svg' => array(
					'font-size' => self::get_css_value( $attr['backIconSize'], 'px' ),
					'fill'      => $attr['backIconColor'],
					'height'    => self::get_css_value( $attr['backIconSize'], 'px' ),
					'width'     => self::get_css_value( $attr['backIconSize'], 'px' ),
				),
				' .wp-block-responsive-block-editor-addons-flipbox-item__button.wp-block-button__link' => array(
					'border-style'     => $attr['ctaBorderStyle'],
					'border-width'     => self::get_css_value( $attr['ctaBorderWidth'], 'px' ),
					'border-radius'    => self::get_css_value( $attr['ctaBorderRadius'], 'px' ),
					'border-color'     => $attr['ctaBorderColor'],
					'padding-left'     => 999 !== $attr['buttonHPadding'] && 20 !== $attr['ctaHpadding'] ? self::get_css_value( $attr['buttonHPadding'], 'px' ) : self::get_css_value( $attr['ctaHpadding'], 'px' ), // For compatibility with v1.3.2.
					'padding-right'    => 999 !== $attr['buttonHPadding'] && 20 !== $attr['ctaHpadding'] ? self::get_css_value( $attr['buttonHPadding'], 'px' ) : self::get_css_value( $attr['ctaHpadding'], 'px' ), // For compatibility with v1.3.2.
					'padding-top'      => 999 !== $attr['buttonVPadding'] && 10 !== $attr['ctaHpadding'] ? self::get_css_value( $attr['buttonVPadding'], 'px' ) : self::get_css_value( $attr['ctaVpadding'], 'px' ), // For compatibility with v1.3.2.
					'padding-bottom'   => 999 !== $attr['buttonVPadding'] && 10 !== $attr['ctaHpadding'] ? self::get_css_value( $attr['buttonVPadding'], 'px' ) : self::get_css_value( $attr['ctaVpadding'], 'px' ), // For compatibility with v1.3.2.
					'background-image' => $background_image_gradient,
					'background-color' => $btn_color . '!important',
					'opacity'          => $btn_opacity / 100,
					'color'            => $attr['ctaColor'] . '!important',
					'font-family'      => $attr['backButtonFontFamily'],
					'font-size'        => self::get_css_value( $attr['backButtonFontSize'], 'px' ),
					'font-weight'      => $attr['backButtonFontWeight'],
					'line-height'      => $attr['backButtonLineHeight'],
				),
				' .wp-block-responsive-block-editor-addons-flipbox-item__button.wp-block-button__link:hover' => array(
					'background-image' => 'color' === $attr['buttonHbackgroundType'] ? 'none' : $background_hover_image_gradient,
					'background-color' => $btn_h_color . '!important',
					'border-color'     => $attr['ctaHoverBorderColor'],
					'opacity'          => $btn_h_opacity / 100,
					'color'            => $attr['ctaHoverColor'] . '!important',
				),
				' .has-medium-gutter.has-2-columns > *:not(.block-editor-inner-blocks)' => array(
					'max-width' => 'calc(100% / 2 - ' . $attr['flipBoxGutterGap'] . 'px) !important',
				),
				' .has-medium-gutter.has-3-columns > *:not(.block-editor-inner-blocks)' => array(
					'max-width' => 'calc(100% / 3 - ' . $attr['flipBoxGutterGap'] . 'px) !important',
				),
				' .has-medium-gutter.has-4-columns > *:not(.block-editor-inner-blocks)' => array(
					'max-width' => 'calc(100% / 4 - ' . $attr['flipBoxGutterGap'] . 'px) !important',
				),
			);
			$mobile_selectors = array(
				' .has-medium-gutter.responsive-flipbox-columns__stack-mobile > *:not(.block-editor-inner-blocks)' => array(
					'min-width' => '100%',
					'max-width' => '100%',
				),
				' .has-medium-gutter.responsive-flipbox-columns__stack-mobile > .wp-block-responsive-block-editor-addons-flip-box:not(:last-child)' => array(
					'margin-bottom' => self::get_css_value( $attr['flipBoxGutterGap'], 'px' ),
				),
				' '               => array(
					'margin-bottom' => self::get_css_value( $attr['bottomMarginMobile'], 'px' ) . ' !important',
					'margin-top'    => self::get_css_value( $attr['topMarginMobile'], 'px' ) . ' !important',
				),
				' .wp-block-responsive-block-editor-addons-flip-box .flip-box-front' => array(
					'padding-top'    => self::get_css_value( $attr['frontTopPaddingMobile'], 'px' ),
					'padding-bottom' => self::get_css_value( $attr['frontBottomPaddingMobile'], 'px' ),
					'padding-left'   => self::get_css_value( $attr['frontLeftPaddingMobile'], 'px' ),
					'padding-right'  => self::get_css_value( $attr['frontRightPaddingMobile'], 'px' ),
				),
				' .flip-box-back' => array(
					'padding-top'    => self::get_css_value( $attr['backTopPaddingMobile'], 'px' ),
					'padding-bottom' => self::get_css_value( $attr['backBottomPaddingMobile'], 'px' ),
					'padding-left'   => self::get_css_value( $attr['backLeftPaddingMobile'], 'px' ),
					'padding-right'  => self::get_css_value( $attr['backRightPaddingMobile'], 'px' ),
				),
				' .wp-block-responsive-block-editor-addons-flip-box__title' => array(
					'font-size' => self::get_css_value( $attr['frontTitleFontSizeMobile'], 'px' ),
				),
				' .wp-block-responsive-block-editor-addons-flip-box__subtitle' => array(
					'font-size' => self::get_css_value( $attr['frontSubtitleFontSizeMobile'], 'px' ),
				),
				' .wp-block-responsive-block-editor-addons-flip-box__backtitle' => array(
					'font-size' => self::get_css_value( $attr['backTitleFontSizeMobile'], 'px' ),
				),
				' .wp-block-responsive-block-editor-addons-flip-box__backsubtitle' => array(
					'font-size' => self::get_css_value( $attr['backSubtitleFontSizeMobile'], 'px' ),
				),
				' .wp-block-responsive-block-editor-addons-flipbox-item__button.wp-block-button__link' => array(
					'font-size'      => self::get_css_value( $attr['backButtonFontSizeMobile'], 'px' ),
					'padding-left'   => self::get_css_value( $attr['ctaHpaddingMobile'], 'px' ),
					'padding-right'  => self::get_css_value( $attr['ctaHpaddingMobile'], 'px' ),
					'padding-top'    => self::get_css_value( $attr['ctaVpaddingMobile'], 'px' ),
					'padding-bottom' => self::get_css_value( $attr['ctaVpaddingMobile'], 'px' ),
				),
			);

			$tablet_selectors = array(
				' .has-medium-gutter.responsive-flipbox-columns__stack-tablet > *:not(.block-editor-inner-blocks)' => array(
					'min-width' => '100%',
					'max-width' => '100%',
				),
				' .has-medium-gutter.responsive-flipbox-columns__stack-tablet > .wp-block-responsive-block-editor-addons-flip-box:not(:last-child)' => array(
					'margin-bottom' => self::get_css_value( $attr['flipBoxGutterGap'], 'px' ),
				),
				' '               => array(
					'margin-bottom' => self::get_css_value( $attr['bottomMarginTablet'], 'px' ) . ' !important',
					'margin-top'    => self::get_css_value( $attr['topMarginTablet'], 'px' ) . ' !important',
				),
				' .wp-block-responsive-block-editor-addons-flip-box .flip-box-front' => array(
					'padding-top'    => self::get_css_value( $attr['frontTopPaddingTablet'], 'px' ),
					'padding-bottom' => self::get_css_value( $attr['frontBottomPaddingTablet'], 'px' ),
					'padding-left'   => self::get_css_value( $attr['frontLeftPaddingTablet'], 'px' ),
					'padding-right'  => self::get_css_value( $attr['frontRightPaddingTablet'], 'px' ),
				),
				' .flip-box-back' => array(
					'padding-top'    => self::get_css_value( $attr['backTopPaddingTablet'], 'px' ),
					'padding-bottom' => self::get_css_value( $attr['backBottomPaddingTablet'], 'px' ),
					'padding-left'   => self::get_css_value( $attr['backLeftPaddingTablet'], 'px' ),
					'padding-right'  => self::get_css_value( $attr['backRightPaddingTablet'], 'px' ),
				),
				' .wp-block-responsive-block-editor-addons-flip-box__title' => array(
					'font-size' => self::get_css_value( $attr['frontTitleFontSizeTablet'], 'px' ),
				),
				' .wp-block-responsive-block-editor-addons-flip-box__subtitle' => array(
					'font-size' => self::get_css_value( $attr['frontSubtitleFontSizeTablet'], 'px' ),
				),
				' .wp-block-responsive-block-editor-addons-flip-box__backtitle' => array(
					'font-size' => self::get_css_value( $attr['backTitleFontSizeTablet'], 'px' ),
				),
				' .wp-block-responsive-block-editor-addons-flip-box__backsubtitle' => array(
					'font-size' => self::get_css_value( $attr['backSubtitleFontSizeTablet'], 'px' ),
				),
				' .wp-block-responsive-block-editor-addons-flipbox-item__button.wp-block-button__link' => array(
					'font-size'      => self::get_css_value( $attr['backButtonFontSizeTablet'], 'px' ),
					'padding-left'   => self::get_css_value( $attr['ctaHpaddingTablet'], 'px' ),
					'padding-right'  => self::get_css_value( $attr['ctaHpaddingTablet'], 'px' ),
					'padding-top'    => self::get_css_value( $attr['ctaVpaddingTablet'], 'px' ),
					'padding-bottom' => self::get_css_value( $attr['ctaVpaddingTablet'], 'px' ),
				),
			);

			$combined_selectors = array(
				'desktop' => $selectors,
				'tablet'  => $tablet_selectors,
				'mobile'  => $mobile_selectors,
			);

			$id  = '.responsive-block-editor-addons-block-flipbox.block-' . $id;
			$css = Responsive_Block_Editor_Addons_Frontend_Styles_Helper::responsive_block_editor_addons_generate_all_css( $combined_selectors, $id );
			return $css;
		}

		/**
		 * Get Defaults for flipbox block
		 *
		 * @return array
		 */
		public static function get_responsive_block_flipbox_default_attributes() {
			return array(
				'block_id'                    => '',
				'flipboxArray'                => '',
				'count'                       => '',
				'height'                      => 420,
				'iconSize'                    => 50,
				'backIconSize'                => 50,
				'transitionSpeed'             => 8,
				'backgroundImage'             => '',
				'backgroundPosition'          => 'center center',
				'backgroundAttachment'        => 'scroll',
				'backgroundRepeat'            => 'no-repeat',
				'backgroundSize'              => 'cover',
				'backBackgroundImage'         => '',
				'backBackgroundPosition'      => 'center center',
				'backBackgroundAttachment'    => 'scroll',
				'backBackgroundRepeat'        => 'no-repeat',
				'backBackgroundSize'          => 'cover',
				'colorOpacity'                => 30,
				'backColorOpacity'            => 30,
				'imageOpacity'                => 30,
				'backImageOpacity'            => 30,
				'buttonbackgroundType'        => 'none',
				'buttoncolorLocation1'        => 0,
				'buttoncolorLocation2'        => 100,
				'buttongradientDirection'     => 90,
				'buttonbackgroundColor1'      => '#333',
				'buttonbackgroundColor2'      => '',
				'buttonHTextColor'            => '#fff',
				'buttonHColor'                => '#333',
				'buttonopacity'               => 100,
				'buttonHopacity'              => 100,
				'buttonHbackgroundType'       => 'none',
				'buttonHcolorLocation1'       => 0,
				'buttonHcolorLocation2'       => 100,
				'buttonHgradientDirection'    => 90,
				'buttonHbackgroundColor1'     => '#333',
				'buttonHbackgroundColor2'     => '',
				'buttonBorderRadius'          => 0,
				'ctaHpadding'                 => 20,
				'ctaVpadding'                 => 10,
				'buttonHPadding'              => 999, // For compatibility with v1.3.2.
				'buttonVPadding'              => 999, // For compatibility with v1.3.2.
				'iconSelected'                => 'editor-textcolor',
				'flipStyleSelected'           => 'LTR',
				'align'                       => 'wide',
				'gutter'                      => 'medium',
				'contentAlign'                => 'center',
				'frontTextColor'              => '',
				'frontBackgroundColor'        => '#fff',
				'backTextColor'               => '',
				'backBackgroundColor'         => '#fff',
				'buttonColor'                 => '#333',
				'buttonTextColor'             => '',
				'iconColor'                   => '',
				'backIconColor'               => '',
				'blockBorderStyle'            => 'none',
				'blockBorderWidth'            => 2,
				'blockBorderRadius'           => '',
				'blockBorderColor'            => '',
				'borderStyle'                 => 'empty',
				'borderWidth'                 => 999, // For compatibility with v1.3.2.
				'borderRadius'                => 999, // For compatibility with v1.3.2.
				'borderColor'                 => 'empty', // For compatibility with v1.3.2.
				'boxShadowColor'              => '', // For compatibility with v1.3.2.
				'boxShadowHOffset'            => 0,
				'boxShadowVOffset'            => 0,
				'boxShadowBlur'               => 0,
				'boxShadowSpread'             => 0,
				'boxShadowPosition'           => 'outset',
				'showFrontIcon'               => true,
				'showFrontTitle'              => true,
				'showFrontSubtitle'           => true,
				'showBackIcon'                => true,
				'showBackTitle'               => true,
				'showBackSubtitle'            => true,
				'showBackButton'              => true,
				'colorButtonSelected'         => '',
				'topMargin'                   => 0,
				'bottomMargin'                => 0,
				'frontTopPadding'             => 0,
				'frontBottomPadding'          => 0,
				'frontLeftPadding'            => 0,
				'frontRightPadding'           => 0,
				'topPadding'                  => 999, // For compatibility with v1.3.2.
				'bottomPadding'               => 999, // For compatibility with v1.3.2.
				'leftPadding'                 => 999, // For compatibility with v1.3.2.
				'rightPadding'                => 999, // For compatibility with v1.3.2.
				'backTopPadding'              => 0,
				'backBottomPadding'           => 0,
				'backLeftPadding'             => 0,
				'backRightPadding'            => 0,
				'backtopPadding'              => 999, // For compatibility with v1.3.2.
				'backbottomPadding'           => 999, // For compatibility with v1.3.2.
				'backleftPadding'             => 999, // For compatibility with v1.3.2.
				'backrightPadding'            => 999, // For compatibility with v1.3.2.
				'topMarginMobile'             => 0,
				'bottomMarginMobile'          => 0,
				'frontTopPaddingMobile'       => 0,
				'frontBottomPaddingMobile'    => 0,
				'frontLeftPaddingMobile'      => 0,
				'frontRightPaddingMobile'     => 0,
				'backTopPaddingMobile'        => 0,
				'backBottomPaddingMobile'     => 0,
				'backLeftPaddingMobile'       => 0,
				'backRightPaddingMobile'      => 0,
				'topMarginTablet'             => 0,
				'bottomMarginTablet'          => 0,
				'frontTopPaddingTablet'       => 0,
				'frontBottomPaddingTablet'    => 0,
				'frontLeftPaddingTablet'      => 0,
				'frontRightPaddingTablet'     => 0,
				'backTopPaddingTablet'        => 0,
				'backBottomPaddingTablet'     => 0,
				'backLeftPaddingTablet'       => 0,
				'backRightPaddingTablet'      => 0,
				'flipBoxGutterGap'            => 10,
				'stack'                       => 'mobile',
				'frontTitleFontSize'          => 16,
				'frontTitleFontSizeMobile'    => 16,
				'frontTitleFontSizeTablet'    => 16,
				'frontTitleFontWeight'        => '100',
				'frontTitleLineHeight'        => 1,
				'frontTitleFontFamily'        => '',
				'frontSubtitleFontFamily'     => '',
				'frontSubtitleFontSize'       => 16,
				'frontSubtitleFontSizeMobile' => 16,
				'frontSubtitleFontSizeTablet' => 16,
				'frontSubtitleFontWeight'     => '100',
				'frontSubtitleLineHeight'     => 1,
				'backTitleFontSize'           => 16,
				'backTitleFontSizeMobile'     => 16,
				'backTitleFontSizeTablet'     => 16,
				'backTitleFontWeight'         => '100',
				'backTitleLineHeight'         => 1,
				'backTitleFontFamily'         => '',
				'backSubtitleFontFamily'      => '',
				'backSubtitleFontSize'        => 16,
				'backSubtitleFontSizeMobile'  => 16,
				'backSubtitleFontSizeTablet'  => 16,
				'backSubtitleFontWeight'      => '100',
				'backSubtitleLineHeight'      => 1,
				'backButtonFontSize'          => 16,
				'backButtonFontSizeMobile'    => 16,
				'backButtonFontSizeTablet'    => 16,
				'backButtonFontWeight'        => '100',
				'backButtonLineHeight'        => 1,
				'backButtonFontFamily'        => '',
				'ctaBorderWidth'              => 2,
				'ctaBorderStyle'              => 'none',
				'ctaBorderRadius'             => 0,
				'ctaVpaddingTablet'           => 10,
				'ctaVpaddingMobile'           => 10,
				'ctaHpaddingTablet'           => 20,
				'ctaHpaddingMobile'           => 20,
				'ctaHoverColor'               => '#fff',
				'ctaHoverBackColor'           => '#333',
				'ctaBackColor'                => '#333',
				'ctaColor'                    => '',
				'ctaBorderColor'              => '#0066cc',
				'ctaHoverBorderColor'         => '#0066cc',
			);
		}

		/**
		 * Get Gallery Masonry Block CSS
		 *
		 * @param array  $attr The block attributes.
		 * @param string $id The selector ID.
		 * @return array Styles.
		 */
		public static function get_responsive_block_gallery_masonry_css( $attr, $id ) {
			return array();
		}

		/**
		 * Get Defaults for gallery masonry block
		 *
		 * @return array
		 */
		public static function get_responsive_block_gallery_masonry_block_default_attributes() {
			return array();
		}

		/**
		 * Get Googlemap Block CSS
		 *
		 * @param array  $attr The block attributes.
		 * @param string $id The selector ID.
		 * @return array Styles.
		 */
		public static function get_responsive_block_googlemap_css( $attr, $id ) {
			$defaults = self::get_responsive_block_googlemap_default_attributes();
			$attr     = array_merge( $defaults, (array) $attr );

			$mobile_selectors = array();
			$tablet_selectors = array();

			$selectors        = array(
				' '       => array(
					'max-height' => $attr['height'] ? self::get_css_value( $attr['height'], 'px' ) : '400px',
				),
				' iframe' => array(
					'width'  => '100%',
					'height' => $attr['height'] ? self::get_css_value( $attr['height'], 'px' ) : '400px',
				),
			);
			$mobile_selectors = array(
				' '       => array(
					'max-height' => self::get_css_value( $attr['heightMobile'], 'px' ),
				),
				' iframe' => array(
					'width'  => '100%',
					'height' => self::get_css_value( $attr['heightMobile'], 'px' ),
				),
			);

			$tablet_selectors = array(
				' '       => array(
					'max-height' => self::get_css_value( $attr['heightTablet'], 'px' ),
				),
				' iframe' => array(
					'width'  => '100%',
					'height' => self::get_css_value( $attr['heightTablet'], 'px' ),
				),
			);

			$combined_selectors = array(
				'desktop' => $selectors,
				'tablet'  => $tablet_selectors,
				'mobile'  => $mobile_selectors,
			);

			$id  = '.responsive-block-editor-addons-block-googlemap.block-' . $id;
			$css = Responsive_Block_Editor_Addons_Frontend_Styles_Helper::responsive_block_editor_addons_generate_all_css( $combined_selectors, $id );

			return $css;
		}

		/**
		 * Get Defaults for googlemap block
		 *
		 * @return array
		 */
		public static function get_responsive_block_googlemap_default_attributes() {
			return array(
				'address'      => '',
				'apiKey'       => '',
				'zoom'         => 12,
				'height'       => 400,
				'heightTablet' => '',
				'heightMobile' => '',
				'pinned'       => false,
			);
		}

		/**
		 * Get Icon List Block CSS
		 *
		 * @param array  $attr The block attributes.
		 * @param string $id The selector ID.
		 * @return array Styles.
		 */
		public static function get_responsive_block_icon_list_css( $attr, $id ) {
			$defaults = self::get_responsive_block_icon_list_block_default_attributes();
			$attr     = array_merge( $defaults, (array) $attr );

			$mobile_selectors = array();
			$tablet_selectors = array();

			$editor_gap = 0;
			if ( '' !== $attr['gap'] ) { //phpcs:ignore
				$editor_gap = $attr['gap'];
			}

			$alignment = 'center';
			if ( 'left' === $attr['align'] ) {
				$alignment = 'flex-start';
			}
			if ( 'right' === $attr['align'] ) {
				$alignment = 'flex-end';
			}

			$selectors        = array(
				' .responsive-block-editor-addons-icon-list__source-icon svg' => array(
					'width'  => self::get_css_value( $attr['size'], $attr['fontSizeType'] ),
					'height' => self::get_css_value( $attr['size'], $attr['fontSizeType'] ),
				),
				'.responsive-block-editor-addons-icon-list__layout-horizontal .wp-block-responsive-block-editor-addons-icons-list-child' => array(
					'margin-left'  => self::get_css_value( $editor_gap / 2, 'px' ),
					'margin-right' => self::get_css_value( $editor_gap / 2, 'px' ),
				),
				'.responsive-block-editor-addons-icon-list__layout-horizontal .wp-block-responsive-block-editor-addons-icons-list-child:first-child' => array(
					'margin-left' => '0',
				),
				'.responsive-block-editor-addons-icon-list__layout-horizontal .wp-block-responsive-block-editor-addons-icons-list-child:nth-last-child(2)' => array(
					'margin-right' => '0',
				),
				'.responsive-block-editor-addons-icon-list__layout-vertical .responsive-block-editor-addons-icon-list__content-wrap' => array(
					'margin-bottom' => self::get_css_value( $editor_gap, 'px' ),
				),
				'.responsive-block-editor-addons-icon-list__layout-vertical .wp-block-responsive-block-editor-addons-icons-list-child:last-child .responsive-block-editor-addons-icon-list__content-wrap' => array(
					'margin-bottom' => '0px',
				),
				' .responsive-block-editor-addons-icon-list__source-wrap' => array(
					'padding'       => self::get_css_value( $attr['bgSize'], 'px' ),
					'border-radius' => self::get_css_value( $attr['borderRadius'], 'px' ),
					'border-width'  => self::get_css_value( $attr['border'], 'px' ),
					'margin-right'  => $attr['hideLabel'] ? '0px' : self::get_css_value( $attr['inner_gap'], 'px' ),
				),
				' .responsive-block-editor-addons-icon-list__source-icon' => array(
					'width'     => self::get_css_value( $attr['size'], $attr['fontSizeType'] ),
					'height'    => self::get_css_value( $attr['size'], $attr['fontSizeType'] ),
					'font-size' => self::get_css_value( $attr['size'], $attr['fontSizeType'] ),
				),
				' .responsive-block-editor-addons-icon-list__source-image' => array(
					'width'  => self::get_css_value( $attr['size'], $attr['fontSizeType'] ),
					'height' => self::get_css_value( $attr['size'], $attr['fontSizeType'] ),
				),
				' .responsive-block-editor-addons-icon-list__label-wrap' => array(
					'text-align' => $attr['align'],
				),
				' .responsive-block-editor-addons-icon-list__wrap' => array(
					'align-items'     => $alignment,
					'justify-content' => $alignment,
				),
				'.responsive-block-editor-addons-icon-list__layout-horizontal .block-editor-block-list__layout' => array(
					'justify-content'  => $alignment,
					'-webkit-box-pack' => $alignment,
					'-ms-flex-pack'    => $alignment,
				),
				' .responsive-block-editor-addons-icon-list__label' => array(
					'font-family' => $attr['labelFontFamily'],
					'font-weight' => $attr['labelFontWeight'],
					'line-height' => 999 !== $attr['labelFontLineHeight'] && 1 === $attr['labelLineHeight'] ? $attr['labelFontLineHeight'] : $attr['labelLineHeight'], // For compatibility with v1.3.2.
					'font-size'   => self::get_css_value( $attr['labelFontSize'], 'px' ),
				),
			);
			$mobile_selectors = array(
				' .responsive-block-editor-addons-icon-list__source-icon' => array(
					'width'  => $attr['sizeMobile'] ? self::get_css_value( $attr['sizeMobile'], $attr['fontSizeType'] ) : self::get_css_value( $attr['size'], $attr['fontSizeType'] ),
					'height' => $attr['sizeMobile'] ? self::get_css_value( $attr['sizeMobile'], $attr['fontSizeType'] ) : self::get_css_value( $attr['size'], $attr['fontSizeType'] ),
				),
				' .responsive-block-editor-addons-icon-list__source-icon svg' => array(
					'width'  => $attr['sizeMobile'] ? self::get_css_value( $attr['sizeMobile'], $attr['fontSizeType'] ) : self::get_css_value( $attr['size'], $attr['fontSizeType'] ),
					'height' => $attr['sizeMobile'] ? self::get_css_value( $attr['sizeMobile'], $attr['fontSizeType'] ) : self::get_css_value( $attr['size'], $attr['fontSizeType'] ),
				),
				' .responsive-block-editor-addons-icon-list__label' => array(
					'font-size' => $attr['labelFontSizeMobile'] ? self::get_css_value( $attr['labelFontSizeMobile'], 'px' ) : self::get_css_value( $attr['labelFontSize'], 'px' ),
				),
				' .responsive-block-editor-addons-icon-list__source-wrap' => array(
					'padding' => $attr['bgSizeMobile'] ? self::get_css_value( $attr['bgSizeMobile'], 'px' ) : self::get_css_value( $attr['bgSize'], 'px' ),
				),
			);

			$tablet_selectors = array(
				' .responsive-block-editor-addons-icon-list__source-icon' => array(
					'width'  => $attr['sizeTablet'] ? self::get_css_value( $attr['sizeTablet'], $attr['fontSizeType'] ) : self::get_css_value( $attr['size'], $attr['fontSizeType'] ),
					'height' => $attr['sizeTablet'] ? self::get_css_value( $attr['sizeTablet'], $attr['fontSizeType'] ) : self::get_css_value( $attr['size'], $attr['fontSizeType'] ),
				),
				' .responsive-block-editor-addons-icon-list__source-icon svg' => array(
					'width'  => $attr['sizeTablet'] ? self::get_css_value( $attr['sizeTablet'], $attr['fontSizeType'] ) : self::get_css_value( $attr['size'], $attr['fontSizeType'] ),
					'height' => $attr['sizeTablet'] ? self::get_css_value( $attr['sizeTablet'], $attr['fontSizeType'] ) : self::get_css_value( $attr['size'], $attr['fontSizeType'] ),
				),
				' .responsive-block-editor-addons-icon-list__label' => array(
					'font-size' => $attr['labelFontSizeTablet'] ? self::get_css_value( $attr['labelFontSizeTablet'], 'px' ) : self::get_css_value( $attr['labelFontSize'], 'px' ),
				),
				' .responsive-block-editor-addons-icon-list__source-wrap' => array(
					'padding' => $attr['bgSizeTablet'] ? self::get_css_value( $attr['bgSizeTablet'], 'px' ) : self::get_css_value( $attr['bgSize'], 'px' ),
				),
			);

			$combined_selectors = array(
				'desktop' => $selectors,
				'tablet'  => $tablet_selectors,
				'mobile'  => $mobile_selectors,
			);

			$id  = '.responsive-block-editor-addons-icon-list__outer-wrap.responsive-block-editor-addons-' . $id;
			$css = Responsive_Block_Editor_Addons_Frontend_Styles_Helper::responsive_block_editor_addons_generate_all_css( $combined_selectors, $id );
			return $css;
		}

		/**
		 * Get Defaults for icon list block
		 *
		 * @return array
		 */
		public static function get_responsive_block_icon_list_block_default_attributes() {

			return array(
				'align'               => 'left',
				'icon_count'          => 1,
				'icons'               => array(),
				'gap'                 => 10,
				'inner_gap'           => 15,
				'iconPosition'        => 'middle',
				'size'                => 16,
				'sizeMobile'          => '',
				'sizeTablet'          => '',
				'bgSize'              => 0,
				'bgSizeMobile'        => '',
				'bgSizeTablet'        => '',
				'border'              => 0,
				'borderRadius'        => 0,
				'hideLabel'           => false,
				'labelFontFamily'     => '',
				'labelFontWeight'     => '',
				'labelFontSize'       => '',
				'labelFontSizeTablet' => '',
				'labelFontSizeMobile' => '',
				'labelLineHeight'     => 1,
				'icon_layout'         => 'vertical',
				'fontSizeType'        => 'px',
				'block_id'            => 1,
				'labelFontLineHeight' => 999, // For compatibility with v1.3.2.
			);
		}

		/**
		 * Get Icon List Child Block CSS
		 *
		 * @param array  $attr The block attributes.
		 * @param string $id The selector ID.
		 * @return array Styles.
		 */
		public static function get_responsive_block_icon_list_child_css( $attr, $id ) {

			$defaults = self::get_responsive_block_icon_list_child_block_default_attributes();
			$attr     = array_merge( $defaults, (array) $attr );

			$mobile_selectors = array();
			$tablet_selectors = array();

			$selectors = array(
				' .responsive-block-editor-addons-icon-list__content-wrap .responsive-block-editor-addons-icon-list__source-icon' => array(
					'color' => $attr['icon_color'],
				),
				' .responsive-block-editor-addons-icon-list__content-wrap .responsive-block-editor-addons-icon-list__source-icon:hover' => array(
					'color' => $attr['icon_hover_color'],
				),
				' .responsive-block-editor-addons-icon-list__content-wrap .responsive-block-editor-addons-icon-list__source-wrap' => array(
					'background-color' => $attr['icon_bg_color'],
					'border-color'     => $attr['icon_border_color'],
				),
				':hover .responsive-block-editor-addons-icon-list__content-wrap .responsive-block-editor-addons-icon-list__source-wrap' => array(
					'background-color' => $attr['icon_bg_hover_color'],
					'border-color'     => $attr['icon_border_hover_color'],
				),
				' .responsive-block-editor-addons-icon-list__content-wrap .responsive-block-editor-addons-icon-list__source-icon svg' => array(
					'fill' => $attr['icon_color'],
				),
				':hover .responsive-block-editor-addons-icon-list__content-wrap .responsive-block-editor-addons-icon-list__source-icon svg' => array(
					'fill' => $attr['icon_hover_color'],
				),
				' .responsive-block-editor-addons-icon-list__content-wrap .responsive-block-editor-addons-icon-list__label' => array(
					'color' => $attr['label_color'],
				),
				':hover .responsive-block-editor-addons-icon-list__content-wrap .responsive-block-editor-addons-icon-list__label' => array(
					'color' => $attr['label_hover_color'],
				),

			);
			$mobile_selectors = array();

			$tablet_selectors = array();

			$combined_selectors = array(
				'desktop' => $selectors,
				'tablet'  => $tablet_selectors,
				'mobile'  => $mobile_selectors,
			);

			$id  = '.responsive-block-editor-addons-icon-list-repeater.responsive-block-editor-addons-' . $id;
			$css = Responsive_Block_Editor_Addons_Frontend_Styles_Helper::responsive_block_editor_addons_generate_all_css( $combined_selectors, $id );
			return $css;
		}

		/**
		 * Get Defaults for icon list child block
		 *
		 * @return array
		 */
		public static function get_responsive_block_icon_list_child_block_default_attributes() {
			return array(
				'label'                   => '#Label',
				'image_icon'              => 'icon',
				'icon'                    => 'fab fa-arrow-circle-right',
				'hideLabel'               => false,
				'image'                   => '',
				'icon_color'              => '#3a3a3a',
				'label_color'             => '',
				'icon_hover_color'        => '',
				'label_hover_color'       => '',
				'icon_bg_color'           => '',
				'icon_bg_hover_color'     => '',
				'icon_border_color'       => '',
				'icon_border_hover_color' => '',
				'link'                    => '#',
				'target'                  => false,
				'disableLink'             => true,
				'block_id'                => 1,
				'source_type'             => 'icon',
			);
		}

		/**
		 * Get Image Box Block CSS
		 *
		 * @param array  $attr The block attributes.
		 * @param string $id The selector ID.
		 * @return array Styles.
		 */
		public static function get_responsive_block_image_boxes_css( $attr, $id ) {
			$defaults = self::get_responsive_block_image_boxes_default_attributes();
			$attr     = array_merge( $defaults, (array) $attr );

			$mobile_selectors = array();
			$tablet_selectors = array();

			$imgopacity       = $attr['opacity'] / 100;
			$hover_imgopacity = $attr['hoverOpacity'] / 100;

			$tempsecondary_background_color        = $attr['bgGradient']
			? $attr['secondaryBackgroundColor']
			: $attr['itemBackgroundColor'];
			$temp_hover_secondary_background_color = $attr['hoverBgGradient']
			? $attr['hoverSecondaryBackgroundColor']
			: $attr['itemHoverBackgroundColor'];

			$hover_gradient =
			'linear-gradient(' .
			$attr['hoverGradientDegree'] .
			'deg,' .
			self::hex_to_rgb( $attr['itemHoverBackgroundColor'], $hover_imgopacity ) .
			',' .
			self::hex_to_rgb(
				$temp_hover_secondary_background_color,
				$hover_imgopacity
			) .
			')';

			$box_shadow_position_css = $attr['boxShadowPosition'];

			if ( 'outset' === $attr['boxShadowPosition'] ) {
				$box_shadow_position_css = '';
			}

			$background_image_first  = '';
			$background_image_second = '';
			$background_image_third  = '';
			$background_image_fourth = '';

			$background_image_first = 'linear-gradient(' . $attr['gradientDegree'] . 'deg, ' .

			self::hex_to_rgb( $attr['itemBackgroundColor'], $imgopacity ) .

			',' .

			self::hex_to_rgb( $tempsecondary_background_color, $imgopacity ) .

			'),url(' .

			$attr['backgroundImageOne'] .

			')';

			$background_image_second = 'linear-gradient(' . $attr['gradientDegree'] . 'deg, ' .

			self::hex_to_rgb( $attr['itemBackgroundColor'], $imgopacity ) .

			',' .

			self::hex_to_rgb( $tempsecondary_background_color, $imgopacity ) .

			'),url(' .

			$attr['backgroundImageTwo'] .

			')';

			$background_image_third = 'linear-gradient(' . $attr['gradientDegree'] . 'deg, ' .

			self::hex_to_rgb( $attr['itemBackgroundColor'], $imgopacity ) .

			',' .

			self::hex_to_rgb( $tempsecondary_background_color, $imgopacity ) .

			'),url(' .

			$attr['backgroundImageThree'] .

			')';

			$background_image_fourth = 'linear-gradient(' . $attr['gradientDegree'] . 'deg, ' .

			self::hex_to_rgb( $attr['itemBackgroundColor'], $imgopacity ) .

			',' .

			self::hex_to_rgb( $tempsecondary_background_color, $imgopacity ) .

			'),url(' .

			$attr['backgroundImageFour'] .

			')';

			$gutter_margin_ib = '';
			if ( $attr['count'] > 1 ) {
				if ( 'small' === $attr['gutter'] ) {
					$gutter_margin_ib = '20px';
				} elseif ( 'medium' === $attr['gutter'] ) {
					$gutter_margin_ib = '30px';
				} elseif ( 'large' === $attr['gutter'] ) {
					$gutter_margin_ib = '40px';
				} elseif ( 'huge' === $attr['gutter'] ) {
					$gutter_margin_ib = '50px';
				} else {
					$gutter_margin_ib = '';
				}
			}

			$selectors = array(
				' '                => array(
					'text-align'          => $attr['contentAlign'],
					'border-color'        => $attr['blockBorderColor'],
					'border-style'        => $attr['blockBorderStyle'],
					'border-width'        => self::get_css_value( $attr['blockBorderWidth'], 'px' ),
					'border-radius'       => 999 !== $attr['boxRadius'] && '' === $attr['blockBorderRadius'] ? self::get_css_value( $attr['boxRadius'], 'px' ) : self::get_css_value( $attr['blockBorderRadius'], 'px' ), // For compatibility with v1.3.2.
					'justify-content'     => $attr['verticalAlignment'] . '!important',
					'background-color'    => self::hex_to_rgb( $attr['itemBackgroundColor'], $imgopacity ),
					'background-size'     => $attr['backgroundSize'],
					'background-repeat'   => $attr['backgroundRepeat'],
					'background-position' => $attr['backgroundPosition'],
					'padding-left'        => self::get_css_value( $attr['boxPaddingLeft'], 'px' ),
					'padding-right'       => self::get_css_value( $attr['boxPaddingRight'], 'px' ),
					'padding-bottom'      => self::get_css_value( $attr['boxPaddingBottom'], 'px' ),
					'padding-top'         => self::get_css_value( $attr['boxPaddingTop'], 'px' ),
					'height'              => self::get_css_value( $attr['boxHeight'], 'px' ),
					'box-shadow'          =>
					self::get_css_value( $attr['boxShadowHOffset'], 'px' ) .
						' ' .
						self::get_css_value( $attr['boxShadowVOffset'], 'px' ) .
						' ' .
						self::get_css_value( $attr['boxShadowBlur'], 'px' ) .
						' ' .
						self::get_css_value( $attr['boxShadowSpread'], 'px' ) .
						' ' .
						$attr['boxShadowColor'] .
						' ' .
						$box_shadow_position_css,
				),

				':hover .responsive-block-editor-addons-add-image' => array(
					'background-image' => $hover_gradient,
					'border-radius'    => 999 !== $attr['boxRadius'] && '' === $attr['blockBorderRadius'] ? self::get_css_value( $attr['boxRadius'], 'px' ) : self::get_css_value( $attr['blockBorderRadius'], 'px' ), // For compatibility with v1.3.2.
				),

				':hover'           => array(
					'transform' => 'scale(' . $attr['imageHoverEffect'] . ')',
				),

				' .responsive-block-editor-addons-imagebox-image' => array(
					'width'     => $attr['imageSize'],
					'max-width' => 100 . '%',
				),
				' .wp-block-responsive-block-editor-addons-image-boxes-block-item__title' => array(
					'font-family'   => $attr['titleFontFamily'],
					'font-weight'   => $attr['titleFontWeight'],
					'font-size'     => self::get_css_value( $attr['titleFontSize'], 'px' ),
					'line-height'   => $attr['titleLineHeight'],
					'color'         => $attr['titleColor'],
					'margin-bottom' => self::get_css_value( $attr['titleSpacing'], 'px' ),
				),
				' .wp-block-responsive-block-editor-addons-image-boxes-block-item__description' => array(
					'font-family'   => $attr['descriptionFontFamily'],
					'font-size'     => self::get_css_value( $attr['descriptionFontSize'], 'px' ),
					'font-weight'   => $attr['descriptionFontWeight'],
					'line-height'   => $attr['descriptionLineHeight'],
					'color'         => $attr['descriptionColor'],
					'margin-bottom' => self::get_css_value( $attr['descriptionSpacing'], 'px' ),
				),
				' .imagebox-arrow' => array(
					'color'     => $attr['arrowColor'],
					'font-size' => self::get_css_value( $attr['arrowSize'], 'px' ),
				),
				'.responsive-block-editor-addons-block-image-boxes-0' => array(
					'background-image' => $background_image_first,
				),
				'.responsive-block-editor-addons-block-image-boxes-1' => array(
					'background-image' => $background_image_second,
				),
				'.responsive-block-editor-addons-block-image-boxes-2' => array(
					'background-image' => $background_image_third,
				),
				'.responsive-block-editor-addons-block-image-boxes-3' => array(
					'background-image' => $background_image_fourth,
				),
			);
			$mobile_selectors = array(
				' .wp-block-responsive-block-editor-addons-image-boxes-block-item__title' => array(
					'font-size'     => self::get_css_value( $attr['titleFontSizeMobile'], 'px' ),
					'margin-bottom' => self::get_css_value( $attr['titleSpacingMobile'], 'px' ),
				),
				' .wp-block-responsive-block-editor-addons-image-boxes-block-item__description' => array(
					'font-size'     => self::get_css_value( $attr['descriptionFontSizeMobile'], 'px' ),
					'margin-bottom' => self::get_css_value( $attr['descriptionSpacingMobile'], 'px' ),
				),
			);

			$tablet_selectors = array(
				' .wp-block-responsive-block-editor-addons-image-boxes-block-item__title' => array(
					'font-size'     => self::get_css_value( $attr['titleFontSizeTablet'], 'px' ),
					'margin-bottom' => self::get_css_value( $attr['titleSpacingTablet'], 'px' ),
				),
				' .wp-block-responsive-block-editor-addons-image-boxes-block-item__description' => array(
					'font-size'     => self::get_css_value( $attr['descriptionFontSizeTablet'], 'px' ),
					'margin-bottom' => self::get_css_value( $attr['descriptionSpacingTablet'], 'px' ),
				),
			);

			$combined_selectors = array(
				'desktop' => $selectors,
				'tablet'  => $tablet_selectors,
				'mobile'  => $mobile_selectors,
			);

			$id             = '.responsive-block-editor-addons-block-image-boxes.block-' . $id;
			$css            = Responsive_Block_Editor_Addons_Frontend_Styles_Helper::responsive_block_editor_addons_generate_all_css( $combined_selectors, $id );
			$css['mobile'] .= '
                .wp-block-responsive-block-editor-addons-image-boxes-block__inner a {
                    margin-bottom: ' . $gutter_margin_ib . '!important;
					max-width: 100% !important;
                }
				.wp-block-responsive-block-editor-addons-image-boxes-block__inner {
                    flex-direction: column;
					align-items: center;
                }
            ';

			return $css;
		}

		/**
		 * Get Defaults for image box block
		 *
		 * @return array
		 */
		public static function get_responsive_block_image_boxes_default_attributes() {
			return array(
				'block_id'                      => '',
				'imageboxesBlock'               => '',
				'counterId'                     => 1,
				'count'                         => '',
				'titleHeadingTag'               => 'h3',
				'gutter'                        => 'medium',
				'contentAlign'                  => 'center',
				'textColor'                     => '',
				'itemBackgroundColor'           => '#fff',
				'hoverTextColor'                => '',
				'verticalAlignment'             => 'center',
				'itemHoverBackgroundColor'      => '#fff',
				'hoverBorderColor'              => '',
				'titleSpacing'                  => '',
				'descriptionSpacing'            => '',
				'titleSpacingMobile'            => '',
				'descriptionSpacingMobile'      => '',
				'titleSpacingTablet'            => '',
				'descriptionSpacingTablet'      => '',
				'blockBorderRadius'             => '',
				'blockBorderStyle'              => 'solid',
				'blockBorderWidth'              => 2,
				'blockBorderColor'              => '#1E1E1E',
				'boxPaddingLeft'                => 15,
				'boxPaddingRight'               => 15,
				'boxPaddingBottom'              => 15,
				'boxPaddingTop'                 => 15,
				'boxHeight'                     => '',
				'hasArrow'                      => '',
				'hasArrow'                      => '',
				'arrowColor'                    => '',
				'arrowSize'                     => '',
				'boxShadowColor'                => '#fff',
				'boxShadowHOffset'              => 9,
				'boxShadowVOffset'              => 9,
				'boxShadowBlur'                 => 9,
				'opacity'                       => 70,
				'hoverOpacity'                  => 70,
				'boxShadowSpread'               => 9,
				'boxShadowPosition'             => 'outset',
				'backgroundPosition'            => '',
				'backgroundSize'                => '',
				'backgroundRepeat'              => '',
				'imageHoverEffect'              => '',
				'bggradient'                    => '',
				'secondaryBackgroundColor'      => '',
				'hoverSecondaryBackgroundColor' => '',
				'gradientDegree'                => 180,
				'bgGradient'                    => false,
				'hoverGradientDegree'           => 180,
				'hoverBgGradient'               => false,
				'titleFontFamily'               => '',
				'descriptionFontFamily'         => '',
				'titleFontSize'                 => '',
				'titleFontSizeMobile'           => '',
				'titleFontSizeTablet'           => '',
				'titleFontWeight'               => '',
				'imageSize'                     => 'full',
				'titleLineHeight'               => '',
				'titleColor'                    => '#1E1E1E',
				'descriptionFontSize'           => '',
				'descriptionFontWeight'         => '',
				'descriptionLineHeight'         => '',
				'descriptionColor'              => '#1E1E1E',
				'backgroundImageOne'            => '',
				'backgroundImageTwo'            => '',
				'backgroundImageThree'          => '',
				'backgroundImageFour'           => '',
				'descriptionFontSizeMobile'     => '',
				'descriptionFontSizeTablet'     => '',
				'boxRadius'                     => 999, // For compatibility with v1.3.2.
			);
		}

		/**
		 * Get Image Slider Block CSS
		 *
		 * @param array  $attr The block attributes.
		 * @param string $id The selector ID.
		 * @return array Styles.
		 */
		public static function get_responsive_block_image_slider_css( $attr, $id ) {
			$defaults = self::get_responsive_block_image_slider_default_attributes();
			$attr     = array_merge( $defaults, (array) $attr );

			$mobile_selectors = array();
			$tablet_selectors = array();

			$imgopacity = $attr['iconBackgroundOpacity'] / 100;

			$selectors = array(
				' .flickity-button .flickity-button-icon' => array(
					'fill' => $attr['iconColor'],
				),

				' .flickity-button'                       => array(
					'background-color' => self::hex_to_rgb( $attr['iconBackgroundColor'] ? $attr['iconBackgroundColor'] : '#ffffff', $imgopacity ),
					'border-radius'    => $attr['iconBackgroundRadius'] . '%',
				),

				' .responsive-block-editor-addons-gallery--item figure' => array(
					'height' => ( $attr['height'] - ( $attr['blockBorderWidth'] ? $attr['blockBorderWidth'] * 2 : 0 ) ) . 'px',
				),

				' .has-carousel-lrg .responsive-block-editor-addons-gallery--item' => array(
					'width' => self::get_css_value( $attr['width'], 'px' ),
				),

				' .responsive-block-editor-addons-gallery--item' => array(
					'margin-left'   => $attr['gutter'] > 0 && ! ( $attr['responsiveHeight'] ) ? $attr['gutter'] . 'px' : null,
					'margin-right'  => $attr['gutter'] > 0 && ! ( $attr['responsiveHeight'] ) ? $attr['gutter'] . 'px' : null,
					'border-width'  => 999 !== $attr['borderWidth'] && '' === $attr['blockBorderWidth'] ? self::get_css_value( $attr['borderWidth'], 'px' ) : self::get_css_value( $attr['blockBorderWidth'], 'px' ), // For compatibility with v1.3.2.
					'border-radius' => self::get_css_value( $attr['blockBorderRadius'], 'px' ),
					'border-style'  => 'empty' !== $attr['borderStyle'] && '' === $attr['blockBorderStyle'] ? $attr['borderStyle'] : $attr['blockBorderStyle'], // For compatibility with v1.3.2.
					'border-color'  => 'empty' !== $attr['borderColor'] && '' === $attr['blockBorderColor'] ? $attr['borderColor'] : $attr['blockBorderColor'], // For compatibility with v1.3.2.
				),
			);

			$mobile_selectors = array(
				' .responsive-block-editor-addons-gallery--item' => array(
					'margin-left'   => $attr['gutterMobile'] >= 0 && ! ( $attr['responsiveHeight'] ) ? $attr['gutterMobile'] . 'px' : null,
					'margin-right'  => $attr['gutterMobile'] >= 0 && ! ( $attr['responsiveHeight'] ) ? $attr['gutterMobile'] . 'px' : null,
				),
			);

			$tablet_selectors = array();

			$combined_selectors = array(
				'desktop' => $selectors,
				'tablet'  => $tablet_selectors,
				'mobile'  => $mobile_selectors,
			);

			$id  = '.responsive-block-editor-addons-block-image-slider.block-' . $id;
			$css = Responsive_Block_Editor_Addons_Frontend_Styles_Helper::responsive_block_editor_addons_generate_all_css( $combined_selectors, $id );
			return $css;
		}

		/**
		 * Get Defaults for image slider block
		 *
		 * @return array
		 */
		public static function get_responsive_block_image_slider_default_attributes() {
			return array(
				'block_id'              => '',
				'gutter'                => 5,
				'gutterMobile'          => 5,
				'gridSize'              => 'lrg',
				'height'                => 400,
				'width'                 => '',
				'customWidth'           => false,
				'pageDots'              => false,
				'prevNextButtons'       => true,
				'autoPlay'              => false,
				'autoPlaySpeed'         => 3000,
				'draggable'             => true,
				'alignCells'            => false,
				'pauseHover'            => false,
				'freeScroll'            => false,
				'isSmallImage'          => false,
				'thumbnails'            => false,
				'responsiveHeight'      => false,
				'blockBorderWidth'      => '',
				'blockBorderRadius'     => '',
				'iconBackgroundRadius'  => 20,
				'iconBackgroundOpacity' => 90,
				'iconBackgroundColor'   => '',
				'blockBorderColor'      => '',
				'iconColor'             => '',
				'blockBorderStyle'      => '',
				'counterId'             => '',
				'borderWidth'           => 999, // For compatibility with v1.3.2.
				'borderStyle'           => 'empty', // For compatibility with v1.3.2.
				'borderColor'           => 'empty', // For compatibility with v1.3.2.
			);
		}

		/**
		 * Get Info Block CSS
		 *
		 * @param array  $attr The block attributes.
		 * @param string $id The selector ID.
		 * @return array Styles.
		 */
		public static function get_responsive_block_info_block_css( $attr, $id ) {
			$defaults = self::get_responsive_block_info_block_default_attributes();
			$attr     = array_merge( $defaults, (array) $attr );

			$mobile_selectors = array();
			$tablet_selectors = array();

			$image_box_shadow_position_css = $attr['imageBoxShadowPosition'];
			if ( 'outset' === $attr['imageBoxShadowPosition'] ) {
				$image_box_shadow_position_css = '';
			}

			$updated_background_h_color = '';

			if ( 'color' === $attr['buttonHbackgroundType'] ) {
				$updated_background_h_color = ( 'empty' !== $attr['hoverctaBgColor'] && '#333' === $attr['ctaBackColor'] ? $attr['hoverctaBgColor'] : $attr['ctaHoverBackColor'] ) ? $attr['ctaHoverBackColor'] : 'none'; // For compatibility with v1.3.2.
			}

			$box_shadow_position_css = $attr['boxShadowPosition'];
			if ( 'outset' === $attr['boxShadowPosition'] ) {
				$box_shadow_position_css = '';
			}

			$hoverbox_shadow_position_css = $attr['hoverboxShadowPosition'];
			if ( 'outset' === $attr['hoverboxShadowPosition'] ) {
				$hoverbox_shadow_position_css = '';
			}

			$newopacity = $attr['opacity'] / 100;

			$imgopacity = $attr['imageopacity'] / 100;

			$icon_bg_color = '';
			if ( 'solid' === $attr['iconBackgroundType'] ) {
				$icon_bg_color = $attr['iconBackgroundColor'];
			}

			$icon_bg_hover_color = '';
			if ( 'solid' === $attr['iconBackgroundType'] ) {
				$icon_bg_hover_color = $attr['iconBackgroundHoverColor'];
			}

			$icon_bg_padding = 0;
			if ( 'none' !== $attr['iconBackgroundType'] ) {
				$icon_bg_padding = self::get_css_value( $attr['iconPadding'], 'px' );
			}

			$icon_border = 'none';
			if ( 'outline' === $attr['iconBackgroundType'] ) {
				$icon_border = self::get_css_value( $attr['iconBorderWidth'], 'px' ) . ' solid ' . $attr['iconBackgroundColor'];
			}

			$icon_hover_border = 'none';
			if ( 'outline' === $attr['iconBackgroundType'] ) {
				$icon_hover_border = self::get_css_value( $attr['iconBorderWidth'], 'px' ) . ' solid ' . $attr['iconBackgroundHoverColor'];
			}

			$background_image_gradient = '';
			$button_color              = '';
			if ( 'gradient' === $attr['buttonbackgroundType'] ) {
				$background_image_gradient = 'linear-gradient(' . $attr['buttongradientDirection'] . 'deg, ' . $attr['buttonbackgroundColor1'] . ' ' . $attr['buttoncolorLocation1'] . '%, ' . $attr['buttonbackgroundColor2'] . ' ' . $attr['buttoncolorLocation2'] . '%)';
			} elseif ( 'color' === $attr['buttonbackgroundType'] ) {
				$background_image_gradient = '';
				$button_color              = 'empty' !== $attr['resctaBgColor'] && 'transparent' === $attr['ctaBackColor'] ? $attr['resctaBgColor'] : $attr['ctaBackColor']; // For compatibility with v1.3.2.
			}

			$selectors = array(
				' '                                        => array(
					'z-index'          => $attr['zIndex'],
					'border-width'     => self::get_css_value( $attr['blockBorderWidth'], 'px' ),
					'background-color' => 'empty' !== $attr['boxBackgroundColor'] && '#ffffff' === $attr['backgroundColor'] ? self::hex_to_rgb( $attr['boxBackgroundColor'], $newopacity ) : self::hex_to_rgb( $attr['backgroundColor'], $newopacity ), // For compatibility with v1.3.2.
					'padding'          => self::get_css_value( $attr['contentPadding'], 'px' ),
					'border-color'     => $attr['blockBorderColor'],
					'border-style'     => $attr['blockBorderStyle'],
					'border-radius'    => self::get_css_value( $attr['blockBorderRadius'], 'px' ),
					'box-shadow'       =>
						self::get_css_value( $attr['boxShadowHOffset'], 'px' ) .
						' ' .
						self::get_css_value( $attr['boxShadowVOffset'], 'px' ) .
						' ' .
						self::get_css_value( $attr['boxShadowBlur'], 'px' ) .
						' ' .
						self::get_css_value( $attr['boxShadowSpread'], 'px' ) .
						' ' .
						$attr['boxShadowColor'] .
						' ' .
						$box_shadow_position_css,
				),

				':hover'                                   => array(
					'box-shadow' =>
					self::get_css_value( $attr['hoverboxShadowHOffset'], 'px' ) .
					' ' .
					self::get_css_value( $attr['hoverboxShadowVOffset'], 'px' ) .
					' ' .
					self::get_css_value( $attr['hoverboxShadowBlur'], 'px' ) .
					' ' .
					self::get_css_value( $attr['hoverboxShadowSpread'], 'px' ) .
					' ' .
					$attr['hoverboxShadowColor'] .
					' ' .
					$hoverbox_shadow_position_css,
				),

				' .responsive-block-editor-addons-ifb-image-icon-content.responsive-block-editor-addons-ifb-imgicon-wrap' => array(
					'margin-bottom' => self::get_css_value( $attr['iconBottomMargin'], 'px' ),
					'margin-top'    => self::get_css_value( $attr['iconTopMargin'], 'px' ),
					'margin-left'   => self::get_css_value( $attr['iconLeftMargin'], 'px' ),
					'margin-right'  => self::get_css_value( $attr['iconRightMargin'], 'px' ),
				),

				' .responsive-block-editor-addons-ifb-icon' => array(
					'width'            => self::get_css_value( $attr['resIconSize'], 'px' ),
					'height'           => self::get_css_value( $attr['resIconSize'], 'px' ),
					'background-color' => $icon_bg_color,
					'border-radius'    => self::get_css_value( $attr['iconBorderRadius'], 'px' ),
					'padding'          => $icon_bg_padding,
					'border'           => $icon_border,
				),

				' .responsive-block-editor-addons-ifb-icon:hover' => array(
					'background-color'          => $icon_bg_hover_color,
					'border'                    => $icon_hover_border,
					'animation-name'            => $attr['animationName'] . '' . $attr['animationDirection'],
					'animation-timing-function' => $attr['animationCurve'],
					'animation-duration'        => $attr['animationDuration'] . 'ms',
					'animation-delay'           => $attr['animationDelay'] . 'ms',
					'animation-iteration-count' => 'once' === $attr['animationRepeat'] ? 1 : 'infinite',
				),

				' .responsive-block-editor-addons-ifb-image-content > img' => array(
					'border-color'  => $attr['resImageBorderColor'],
					'border-radius' => self::get_css_value( $attr['resImageBorderRadius'], 'px' ),
					'border-width'  => self::get_css_value( $attr['resImageBorderWidth'], 'px' ),
					'border-style'  => $attr['resImageBorderStyle'],
					'box-shadow'    =>
						self::get_css_value( $attr['imageBoxShadowHOffset'], 'px' ) .
						' ' .
						self::get_css_value( $attr['imageBoxShadowVOffset'], 'px' ) .
						' ' .
						self::get_css_value( $attr['imageBoxShadowBlur'], 'px' ) .
						' ' .
						self::get_css_value( $attr['imageBoxShadowSpread'], 'px' ) .
						' ' .
						$attr['imageBoxShadowColor'] .
						' ' .
						$image_box_shadow_position_css,
					'opacity'       => $imgopacity,
				),

				' .responsive-block-editor-addons-ifb-separator' => array(
					'border-width'     => self::get_css_value( $attr['resseperatorThickness'], 'px' ),
					'border-color'     => $attr['resseperatorColor'],
					'border-top-style' => $attr['resseperatorStyle'],
					'width'            => self::get_css_value( $attr['resseperatorWidth'], $attr['resseparatorWidthType'] ),
					'margin-bottom'    => self::get_css_value( $attr['sepSpace'], 'px' ),
				),

				' .responsive-block-editor-addons-ifb-cta-button' => array(
					'background-color' => $button_color,
					'background-image' => $background_image_gradient,
					'border-color'     => 'empty' !== $attr['resctaBorderColor'] && '#333' !== $attr['ctaBorderColor'] ? $attr['resctaBorderColor'] : $attr['ctaBorderColor'], // For compatibility with v1.3.2.
				),

				' .responsive-block-editor-addons-ifb-cta-button .responsive-block-editor-addons-inline-editing' => array(
					'color' => 'empty' !== $attr['resctaBtnLinkColor'] && '#333' === $attr['ctaColor'] ? $attr['resctaBtnLinkColor'] : $attr['ctaColor'], // For compatibility with v1.3.2.
				),

				' .responsive-block-editor-addons-ifb-cta-button:hover' => array(
					'background-color' => $updated_background_h_color,
					'border-color'     => 'empty' !== $attr['hoverctaBorderColor'] && '#333' === $attr['ctaHoverBorderColor'] ? $attr['hoverctaBorderColor'] : $attr['ctaHoverBorderColor'], // For compatibility with v1.3.2.
					'background-image' => 'color' === $attr['buttonHbackgroundType'] ? 'none' : $background_image_gradient,
				),

				' .responsive-block-editor-addons-ifb-cta-button:hover .responsive-block-editor-addons-inline-editing' => array(
					'color' => 'empty' !== $attr['hoverctaBtnLinkColor'] && '#333' === $attr['ctaHoverColor'] ? $attr['hoverctaBtnLinkColor'] : $attr['ctaHoverColor'], // For compatibility with v1.3.2.
				),

				' .responsive-block-editor-addons-ifb-icon svg' => array(
					'fill' => $attr['icon_color'],
				),

				' .responsive-block-editor-addons-ifb-icon:hover svg' => array(
					'fill' => $attr['icon_hcolor'],
				),

				' .responsive-block-editor-addons-infobox__content-wrap' => array(
					'text-align' => ( 'above-title' === $attr['imgiconPosition'] || 'below-title' === $attr['imgiconPosition'] ) ? $attr['resheadingAlign'] : '',
				),

				' .responsive-block-editor-addons-infobox__content-wrap .responsive-block-editor-addons-ifb-title' => array(
					'font-size' => self::get_css_value( $attr['resheadFontSize'], 'px !important' ),
				),

				' .responsive-block-editor-addons-infobox__content-wrap .responsive-block-editor-addons-ifb-desc' => array(
					'font-size' => self::get_css_value( $attr['ressubHeadFontSize'], 'px !important' ),
				),

				' .responsive-block-editor-addons-ifb-image-content img' => array(
					'width'     => self::get_css_value( $attr['imageWidth'], 'px' ),
					'max-width' => self::get_css_value( $attr['imageWidth'], 'px' ),
				),

				' .responsive-block-editor-addons-cta-image' => array(
					'background-image'      => 'empty' !== $attr['imgURL'] && '' === $attr['backgroundImage'] ? 'url(' . $attr['imgURL'] . ')' : 'url(' . $attr['backgroundImage'] . ')', // For compatibility with v1.3.2.
					'background-position'   => 'empty' !== $attr['imagePosition'] && 'center center' === $attr['backgroundImagePosition'] ? $attr['imagePosition'] : $attr['backgroundImagePosition'], // For compatibility with v1.3.2.
					'background-repeat'     => 'empty' !== $attr['imageRepeat'] && 'no-repeat' === $attr['backgroundImageRepeat'] ? $attr['imageRepeat'] : $attr['backgroundImageRepeat'], // For compatibility with v1.3.2.
					'background-size'       => 'empty' !== $attr['thumbsize'] && 'cover' === $attr['backgroundImageSize'] ? $attr['thumbsize'] : $attr['backgroundImageSize'], // For compatibility with v1.3.2.
					'background-attachment' => $attr['backgroundAttachment'],
				),

				' .responsive-block-editor-addons-ifb-title-prefix' => array(
					'color'         => $attr['resprefixColor'],
					'font-size'     => self::get_css_value( $attr['resprefixFontSize'], 'px' ),
					'font-weight'   => $attr['resprefixFontWeight'],
					'line-height'   => $attr['resprefixLineHeight'],
					'margin-bottom' => self::get_css_value( $attr['resprefixSpace'], 'px' ),
				),

				' .responsive-block-editor-addons-ifb-title' => array(
					'color'         => $attr['resheadingColor'],
					'font-family'   => $attr['resheadFontFamily'],
					'font-weight'   => $attr['resheadFontWeight'],
					'line-height'   => $attr['resheadLineHeight'],
					'margin-bottom' => self::get_css_value( $attr['resheadSpace'], 'px' ),
				),

				' .responsive-block-editor-addons-ifb-desc' => array(
					'color'         => $attr['ressubheadingColor'],
					'font-family'   => $attr['ressubHeadFontFamily'],
					'font-weight'   => $attr['ressubHeadFontWeight'],
					'line-height'   => $attr['ressubHeadLineHeight'],
					'margin-bottom' => self::get_css_value( $attr['ressubHeadSpace'], 'px' ),
				),

				' .responsive-block-editor-addons-infobox-cta-link' => array(
					'color'          => $attr['resctaLinkColor'],
					'padding-top'    => 'text' !== $attr['resctaType'] ? ( 999 !== $attr['ctaVertPadding'] && 10 === $attr['ctaVpadding'] ? self::get_css_value( $attr['ctaVertPadding'], 'px' ) : self::get_css_value( $attr['ctaVpadding'], 'px' ) ) : 0, // For compatibility with v1.3.2.
					'padding-bottom' => 'text' !== $attr['resctaType'] ? ( 999 !== $attr['ctaVertPadding'] && 10 === $attr['ctaVpadding'] ? self::get_css_value( $attr['ctaVertPadding'], 'px' ) : self::get_css_value( $attr['ctaVpadding'], 'px' ) ) : 0, // For compatibility with v1.3.2.
					'padding-left'   => 'text' !== $attr['resctaType'] ? ( 999 !== $attr['ctaHrPadding'] && 14 === $attr['ctaHpadding'] ? self::get_css_value( $attr['ctaHrPadding'], 'px' ) : self::get_css_value( $attr['ctaHpadding'], 'px' ) ) : 0, // For compatibility with v1.3.2.
					'padding-right'  => 'text' !== $attr['resctaType'] ? ( 999 !== $attr['ctaHrPadding'] && 14 === $attr['ctaHpadding'] ? self::get_css_value( $attr['ctaHrPadding'], 'px' ) : self::get_css_value( $attr['ctaHpadding'], 'px' ) ) : 0, // For compatibility with v1.3.2.
					'font-size'      => self::get_css_value( $attr['ctaTextFontSize'], 'px' ),
					'font-weight'    => $attr['ctaTextFontWeight'],
					'font-family'    => $attr['ctaTextFontFamily'],
					'line-height'    => $attr['ctaTextLineHeight'],
				),

				' .responsive-block-editor-addons-inline-editing ' => array(
					'color'     => $attr['resctaLinkColor'],
					'font-size' => self::get_css_value( $attr['resctaFontSize'], 'px' ),
				),

				' .responsive-block-editor-addons-infobox-cta-link.responsive-block-editor-addons-ifb-cta-button' => array(
					'border-width'   => 999 !== $attr['resctaBorderWidth'] && 1 === $attr['ctaBorderWidth'] ? self::get_css_value( $attr['resctaBorderWidth'], 'px' ) : self::get_css_value( $attr['ctaBorderWidth'], 'px' ), // For compatibility with v1.3.2.
					'border-style'   => 'empty' !== $attr['resctaBorderStyle'] && 'solid' === $attr['ctaBorderStyle'] ? $attr['resctaBorderStyle'] : $attr['ctaBorderStyle'], // For compatibility with v1.3.2.
					'border-radius'  => 999 !== $attr['resctaBorderRadius'] && 0 === $attr['ctaBorderRadius'] ? self::get_css_value( $attr['resctaBorderRadius'], 'px' ) : self::get_css_value( $attr['ctaBorderRadius'], 'px' ), // For compatibility with v1.3.2.
					'padding-top'    => 999 !== $attr['ctaVertPadding'] && 10 === $attr['ctaVpadding'] ? self::get_css_value( $attr['ctaVertPadding'], 'px' ) : self::get_css_value( $attr['ctaVpadding'], 'px' ), // For compatibility with v1.3.2.
					'padding-bottom' => 999 !== $attr['ctaVertPadding'] && 10 === $attr['ctaVpadding'] ? self::get_css_value( $attr['ctaVertPadding'], 'px' ) : self::get_css_value( $attr['ctaVpadding'], 'px' ), // For compatibility with v1.3.2.
					'padding-left'   => 999 !== $attr['ctaHrPadding'] && 14 === $attr['ctaHpadding'] ? self::get_css_value( $attr['ctaHrPadding'], 'px' ) : self::get_css_value( $attr['ctaHpadding'], 'px' ), // For compatibility with v1.3.2.
					'padding-right'  => 999 !== $attr['ctaHrPadding'] && 14 === $attr['ctaHpadding'] ? self::get_css_value( $attr['ctaHrPadding'], 'px' ) : self::get_css_value( $attr['ctaHpadding'], 'px' ), // For compatibility with v1.3.2.
					'font-size'      => self::get_css_value( $attr['resctaFontSize'], 'px' ),
					'font-weight'    => $attr['resctaFontWeight'],
				),

				' .responsive-block-editor-addons-ifb-cta' => array(
					'margin-bottom' => self::get_css_value( $attr['ctaBottomMargin'], 'px' ),
				),
			);

			$mobile_selectors = array(
				' '                                        => array(
					'padding' => self::get_css_value( $attr['contentPaddingMobile'], 'px' ) . ' !important',
				),
				' .responsive-block-editor-addons-ifb-image-icon-content.responsive-block-editor-addons-ifb-imgicon-wrap' => array(
					'margin-bottom' => self::get_css_value( $attr['iconBottomMarginMobile'], 'px' ),
					'margin-top'    => self::get_css_value( $attr['iconTopMarginMobile'], 'px' ),
					'margin-left'   => self::get_css_value( $attr['iconLeftMarginMobile'], 'px' ),
					'margin-right'  => self::get_css_value( $attr['iconRightMarginMobile'], 'px' ),
				),
				' .responsive-block-editor-addons-infobox__content-wrap.responsive-block-editor-addons-infobox-stacked-mobile .responsive-block-editor-addons-ifb-content' => array(
					'text-align' => $attr['alignment'],
				),
				' .responsive-block-editor-addons-infobox__content-wrap.responsive-block-editor-addons-infobox-stacked-mobile .responsive-block-editor-addons-ifb-icon-wrap' => array(
					'text-align' => $attr['alignment'],
				),
				' .responsive-block-editor-addons-infobox__content-wrap.responsive-block-editor-addons-infobox-stacked-mobile .responsive-block-editor-addons-ifb-imgicon-wrap' => array(
					'text-align' => $attr['alignment'],
				),

				' .responsive-block-editor-addons-infobox__content-wrap .responsive-block-editor-addons-ifb-title' => array(
					'font-size' => self::get_css_value( $attr['resheadFontSizeMobile'], 'px !important' ),
				),

				' .responsive-block-editor-addons-infobox__content-wrap .responsive-block-editor-addons-ifb-desc' => array(
					'font-size' => self::get_css_value( $attr['ressubHeadFontSizeMobile'], 'px !important' ),
				),

				' .responsive-block-editor-addons-ifb-image-content img' => array(
					'width'     => self::get_css_value( $attr['imageWidthMobile'], 'px' ),
					'max-width' => self::get_css_value( $attr['imageWidthMobile'], 'px' ),
				),
				' .responsive-block-editor-addons-infobox-cta-link' => array(
					'font-size' => self::get_css_value( $attr['ctaTextFontSizeMobile'], 'px' ),
				),
				' .responsive-block-editor-addons-ifb-title-prefix' => array(
					'margin-bottom' => self::get_css_value( $attr['resprefixSpaceMobile'], 'px' ),
				),
				' .responsive-block-editor-addons-ifb-title' => array(
					'margin-bottom' => self::get_css_value( $attr['resheadSpaceMobile'], 'px' ),
				),
				' .responsive-block-editor-addons-ifb-separator' => array(
					'margin-bottom' => self::get_css_value( $attr['sepSpaceMobile'], 'px' ),
				),
				' .responsive-block-editor-addons-ifb-desc' => array(
					'margin-bottom' => self::get_css_value( $attr['ressubHeadSpaceMobile'], 'px' ),
				),
				' .responsive-block-editor-addons-ifb-cta' => array(
					'margin-bottom' => self::get_css_value( $attr['ctaBottomMarginMobile'], 'px' ),
				),
				' .responsive-block-editor-addons-infobox-cta-link.responsive-block-editor-addons-ifb-cta-button' => array(
					'padding-top'    => self::get_css_value( $attr['ctaVpaddingMobile'], 'px' ),
					'padding-bottom' => self::get_css_value( $attr['ctaVpaddingMobile'], 'px' ),
					'padding-left'   => self::get_css_value( $attr['ctaHpaddingMobile'], 'px' ),
					'padding-right'  => self::get_css_value( $attr['ctaHpaddingMobile'], 'px' ),
				),
			);

			$tablet_selectors = array(
				' '                                        => array(
					'padding' => self::get_css_value( $attr['contentPaddingTablet'], 'px' ) . ' !important',
				),
				' .responsive-block-editor-addons-ifb-image-icon-content.responsive-block-editor-addons-ifb-imgicon-wrap' => array(
					'margin-bottom' => self::get_css_value( $attr['iconBottomMarginTablet'], 'px' ),
					'margin-top'    => self::get_css_value( $attr['iconTopMarginTablet'], 'px' ),
					'margin-left'   => self::get_css_value( $attr['iconLeftMarginTablet'], 'px' ),
					'margin-right'  => self::get_css_value( $attr['iconRightMarginTablet'], 'px' ),
				),
				' .responsive-block-editor-addons-infobox__content-wrap.responsive-block-editor-addons-infobox-stacked-tablet .responsive-block-editor-addons-ifb-content' => array(
					'text-align' => $attr['alignment'],
				),

				' .responsive-block-editor-addons-infobox__content-wrap.responsive-block-editor-addons-infobox-stacked-tablet .responsive-block-editor-addons-ifb-icon-wrap' => array(
					'text-align' => $attr['alignment'],
				),

				' .responsive-block-editor-addons-infobox__content-wrap.responsive-block-editor-addons-infobox-stacked-tablet .responsive-block-editor-addons-ifb-imgicon-wrap' => array(
					'text-align' => $attr['alignment'],
				),

				' .responsive-block-editor-addons-infobox__content-wrap .responsive-block-editor-addons-ifb-title' => array(
					'font-size' => self::get_css_value( $attr['resheadFontSizeTablet'], 'px !important' ),
				),

				' .responsive-block-editor-addons-infobox__content-wrap .responsive-block-editor-addons-ifb-desc' => array(
					'font-size' => self::get_css_value( $attr['ressubHeadFontSizeTablet'], 'px !important' ),
				),

				' .responsive-block-editor-addons-ifb-image-content img' => array(
					'width'     => self::get_css_value( $attr['imageWidthTablet'], 'px' ),
					'max-width' => self::get_css_value( $attr['imageWidthTablet'], 'px' ),
				),
				' .responsive-block-editor-addons-infobox-cta-link' => array(
					'font-size' => self::get_css_value( $attr['ctaTextFontSizeTablet'], 'px' ),
				),
				' .responsive-block-editor-addons-ifb-title-prefix' => array(
					'margin-bottom' => self::get_css_value( $attr['resprefixSpaceTablet'], 'px' ),
				),
				' .responsive-block-editor-addons-ifb-title' => array(
					'margin-bottom' => self::get_css_value( $attr['resheadSpaceTablet'], 'px' ),
				),
				' .responsive-block-editor-addons-ifb-separator' => array(
					'margin-bottom' => self::get_css_value( $attr['sepSpaceTablet'], 'px' ),
				),
				' .responsive-block-editor-addons-ifb-desc' => array(
					'margin-bottom' => self::get_css_value( $attr['ressubHeadSpaceTablet'], 'px' ),
				),
				' .responsive-block-editor-addons-ifb-cta' => array(
					'margin-bottom' => self::get_css_value( $attr['ctaBottomMarginTablet'], 'px' ),
				),
				' .responsive-block-editor-addons-infobox-cta-link.responsive-block-editor-addons-ifb-cta-button' => array(
					'padding-top'    => self::get_css_value( $attr['ctaVpaddingTablet'], 'px' ),
					'padding-bottom' => self::get_css_value( $attr['ctaVpaddingTablet'], 'px' ),
					'padding-left'   => self::get_css_value( $attr['ctaHpaddingTablet'], 'px' ),
					'padding-right'  => self::get_css_value( $attr['ctaHpaddingTablet'], 'px' ),
				),
			);

			$combined_selectors = array(
				'desktop' => $selectors,
				'tablet'  => $tablet_selectors,
				'mobile'  => $mobile_selectors,
			);

			$id  = '.responsive-block-editor-addons-block-info-block.block-' . $id;
			$css = Responsive_Block_Editor_Addons_Frontend_Styles_Helper::responsive_block_editor_addons_generate_all_css( $combined_selectors, $id );
			return $css;
		}

		/**
		 * Get Defaults for info block
		 *
		 * @return array
		 */
		public static function get_responsive_block_info_block_default_attributes() {
			return array(
				'block_id'                 => '',
				'inheritFromTheme'         => false,
				'resprefixTitle'           => 'Prefix',
				'classMigrate'             => false,
				'resinfoBlockTitle'        => 'Info Box',
				'resDescHeading'           => 'Click here to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.',
				'resheadingAlign'          => 'center',
				'alignment'                => 'center',
				'resheadingColor'          => '',
				'ressubheadingColor'       => '',
				'resprefixColor'           => '',
				'icon'                     => 'fa fa-star',
				'imgiconPosition'          => 'above-title',
				'resIconSize'              => 40,
				'iconColor'                => '#333',
				'resprefixFontSize'        => '',
				'resprefixFontWeight'      => '',
				'resprefixLineHeight'      => '',
				'resheadingTag'            => 'h3',
				'resheadFontFamily'        => '',
				'ressubHeadFontFamily'     => '',
				'resheadFontSize'          => '',
				'resheadFontSizeMobile'    => '',
				'resheadFontSizeTablet'    => '',
				'resheadFontWeight'        => '700',
				'resheadLineHeight'        => '',
				'ressubHeadFontSize'       => '',
				'ressubHeadFontSizeTablet' => '',
				'ressubHeadFontSizeMobile' => '',
				'ressubHeadFontWeight'     => '100',
				'ressubHeadLineHeight'     => '',
				'resheadSpace'             => 10,
				'resheadSpaceMobile'       => '',
				'resheadSpaceTablet'       => '',
				'ressubHeadSpace'          => 10,
				'ressubHeadSpaceMobile'    => '',
				'ressubHeadSpaceTablet'    => '',
				'resseperatorSpace'        => 10,
				'source_type'              => 'icon',
				'ressourceAlign'           => 'top',
				'resctaTarget'             => false,
				'ctaIcon'                  => '',
				'resseperatorPosition'     => 'after_title',
				'resseperatorStyle'        => 'solid',
				'resseperatorColor'        => '',
				'resseperatorWidth'        => 30,
				'resseparatorWidthType'    => '%',
				'resseperatorThickness'    => 2,
				'resctaType'               => 'none',
				'resctaText'               => 'Read More',
				'resctaLink'               => '#',
				'resctaLinkColor'          => '#333',
				'resctaFontSize'           => '',
				'resctaFontWeight'         => '',
				'ctaColor'                 => '#333',
				'resctaBtnLinkColor'       => 'empty', // For compatibility with v1.3.2.
				'ctaBackColor'             => 'transparent',
				'resctaBgColor'            => 'empty', // For compatibility with v1.3.2.
				'ctaBorderColor'           => '#333',
				'resctaBorderColor'        => 'empty', // For compatibility with v1.3.2.
				'ctaBorderStyle'           => 'solid',
				'resctaBorderStyle'        => 'empty', // For compatibility with v1.3.2.
				'ctaVpadding'              => 10,
				'ctaHpadding'              => 14,
				'ctaVertPadding'           => 999, // For compatibility with v1.3.2.
				'ctaHrPadding'             => 999, // For compatibility with v1.3.2.
				'ctaBorderWidth'           => 1,
				'ctaBorderRadius'          => 0,
				'resctaBorderWidth'        => 999, // For compatibility with v1.3.2.
				'resctaBorderRadius'       => 999, // For compatibility with v1.3.2.
				'resprefixSpace'           => 5,
				'resprefixSpaceMobile'     => '',
				'resprefixSpaceTablet'     => '',
				'iconLeftMargin'           => 0,
				'iconRightMargin'          => 0,
				'iconTopMargin'            => 5,
				'iconBottomMargin'         => 5,
				'iconLeftMarginMobile'     => '',
				'iconRightMarginMobile'    => '',
				'iconTopMarginMobile'      => '',
				'iconBottomMarginMobile'   => '',
				'iconLeftMarginTablet'     => '',
				'iconRightMarginTablet'    => '',
				'iconTopMarginTablet'      => '',
				'iconBottomMarginTablet'   => '',
				'iconImage'                => '',
				'imageSize'                => 'thumbnail',
				'imageWidth'               => 120,
				'imageWidthTablet'         => '',
				'imageWidthMobile'         => '',
				'imageWidthType'           => true,
				'stack'                    => 'tablet',
				'resshowPrefix'            => true,
				'resshowTitle'             => true,
				'resshowDesc'              => true,
				'blockBorderStyle'         => 'none',
				'blockBorderWidth'         => 1,
				'blockBorderRadius'        => '',
				'blockBorderColor'         => '',
				'boxShadowColor'           => '',
				'boxShadowHOffset'         => 0,
				'boxShadowVOffset'         => 0,
				'boxShadowBlur'            => '',
				'boxShadowSpread'          => '',
				'boxShadowPosition'        => 'outset',
				'imageBoxShadowColor'      => '',
				'imageBoxShadowHOffset'    => 0,
				'imageBoxShadowVOffset'    => 0,
				'imageBoxShadowBlur'       => '',
				'imageBoxShadowSpread'     => '',
				'imageBoxShadowPosition'   => 'outset',
				'counterId'                => 1,
				'backgroundColor'          => '#ffffff',
				'boxBackgroundColor'       => 'empty', // For compatibility with v1.3.2.
				'contentPadding'           => 0,
				'contentPaddingMobile'     => '',
				'contentPaddingTablet'     => '',
				'opacity'                  => 100,
				'imageopacity'             => 100,
				'imgURL'                   => '', // For compatibility with v1.3.2.
				'imgID'                    => '',
				'imgAlt'                   => '',
				'dimRatio'                 => 50,
				'ctaHoverColor'            => '#333',
				'ctaHoverBackColor'        => '#333',
				'ctaHoverBorderColor'      => '#333',
				'hoverctaBorderColor'      => 'empty',  // For compatibility with v1.3.2.
				'hoverctaBtnLinkColor'     => 'empty',  // For compatibility with v1.3.2.
				'hoverctaBgColor'          => 'empty',  // For compatibility with v1.3.2.
				'imagePosition'            => 'center center', // For compatibility with v1.3.2.
				'imageRepeat'              => 'no-repeat', // For compatibility with v1.3.2.
				'thumbsize'                => 'cover', // For compatibility with v1.3.2.
				'backgroundAttachment'     => 'scroll',
				'sepSpace'                 => 10,
				'sepSpaceMobile'           => '',
				'sepSpaceTablet'           => '',
				'icon_color'               => '#3a3a3a',
				'icon_hcolor'              => '#3a3a3a',
				'resImageBorderColor'      => '#333',
				'resImageBorderStyle'      => 'none',
				'resImageBorderRadius'     => 0,
				'resImageBorderWidth'      => 2,
				'ctaTextFontFamily'        => '',
				'ctaTextFontSize'          => '',
				'ctaTextFontSizeMobile'    => '',
				'ctaTextFontSizeTablet'    => '',
				'ctaTextFontWeight'        => '100',
				'ctaTextLineHeight'        => '',
				'ctaBottomMargin'          => 10,
				'ctaBottomMarginMobile'    => '',
				'ctaBottomMarginTablet'    => '',
				'hoverboxShadowColor'      => '#ccc',
				'hoverboxShadowHOffset'    => 0,
				'hoverboxShadowVOffset'    => 0,
				'hoverboxShadowBlur'       => 6,
				'hoverboxShadowSpread'     => 1,
				'hoverboxShadowPosition'   => 'outset',
				'iconBackgroundColor'      => '#0066cc',
				'iconBackgroundHoverColor' => '',
				'iconBackgroundType'       => 'none',
				'iconPadding'              => 5,
				'iconBorderRadius'         => 0,
				'iconBorderWidth'          => 1,
				'backgroundImage'          => '',
				'backgroundImagePosition'  => 'center center',
				'backgroundAttachment'     => 'scroll',
				'backgroundImageRepeat'    => 'no-repeat',
				'backgroundImageSize'      => 'cover',
				'ctaHpaddingTablet'        => 14,
				'ctaHpaddingMobile'        => 14,
				'ctaVpaddingTablet'        => 10,
				'ctaVpaddingMobile'        => 10,
				'buttoncolorLocation1'     => 0,
				'buttoncolorLocation2'     => 100,
				'buttongradientDirection'  => 90,
				'buttonbackgroundColor1'   => '',
				'buttonbackgroundColor2'   => '#fff',
				'buttonbackgroundType'     => '',
				'buttonHbackgroundType'    => 'none',
				'zIndex'                   => 0,
				'animationName'            => 'none',
				'animationDirection'       => 'Left',
				'animationRepeat'          => 'once',
				'animationDuration'        => 1000,
				'animationDelay'           => 1000,
				'animationCurve'           => '',
			);
		}

		/**
		 * Get Post timeline Block CSS
		 *
		 * @param array  $attr The block attributes.
		 * @param string $id The selector ID.
		 * @return array Styles.
		 */
		public static function get_responsive_block_post_timeline_css( $attr, $id ) {
			$defaults = self::get_responsive_block_post_timeline_default_attributes();
			$attr     = array_merge( $defaults, (array) $attr );

			$mobile_selectors = array();
			$tablet_selectors = array();

			$box_shadow_position_css = $attr['boxShadowPosition'];

			if ( 'outset' === $attr['boxShadowPosition'] ) {
				$box_shadow_position_css = '';
			}

			$selectors = array(
				' .responsive-block-editor-addons-timeline__icon-new svg' => array(
					'width'  => self::get_css_value( $attr['iconSize'], 'px' ),
					'height' => self::get_css_value( $attr['iconSize'], 'px' ),
					'fill'   => $attr['iconColor'],
				),
				' .responsive-block-editor-addons-timeline__marker' => array(
					'border'           => self::get_css_value( $attr['borderWidth'], 'px solid' ),
					'border-color'     => $attr['separatorBorder'],
					'background-color' => $attr['separatorBg'],
					'min-width'        => self::get_css_value( $attr['bgSize'], 'px' ),
					'min-height'       => self::get_css_value( $attr['bgSize'], 'px' ),
				),
				' .responsive-block-editor-addons-timeline__line' => array(
					'background-color' => $attr['connectorColor'],
					'width'            => self::get_css_value( $attr['connectorWidth'], 'px' ),
				),
				' .responsive-block-editor-addons-timeline__link_parent' => array(
					'background-color' => $attr['continuebgColor'],
					'border'           => '1px solid ' . $attr['borderColor'] . '',
					'box-shadow'       =>
					self::get_css_value(
						$attr['boxShadowHOffset'],
						'px'
					) .
					' ' .
					self::get_css_value(
						$attr['boxShadowVOffset'],
						'px'
					) .
					' ' .
					self::get_css_value(
						$attr['boxShadowBlur'],
						'px'
					) .
					' ' .
					self::get_css_value(
						$attr['boxShadowSpread'],
						'px'
					) .
					' ' .
					$attr['boxShadowColor'] .
					' ' .
					$box_shadow_position_css,
				),
				' .responsive-block-editor-addons-timeline__link_parent:hover' => array(
					'background-color' => $attr['continuebghColor'],
					'border'           => '1px solid ' . $attr['borderHColor'] . '',
				),
				' .responsive-block-editor-addons-timeline__link_parent .responsive-block-editor-addons-timeline__link' => array(
					'color'       => $attr['continueColor'] . '!important',
					'line-height' => $attr['continueLineHeight'],
					'font-weight' => $attr['continueFontWeight'],
					'font-size'   => self::get_css_value( $attr['continueFontSize'], 'px' ),
					'font-family' => $attr['continueFontFamily'],
				),
				' .responsive-block-editor-addons-timeline__link_parent:hover .responsive-block-editor-addons-timeline__link' => array(
					'color' => $attr['hColor'] . '!important',
				),
				'.responsive-block-editor-addons-timeline__center-block .responsive-block-editor-addons-timeline__marker' => array(
					'margin-left'  => self::get_css_value( $attr['horSpace'], 'px' ),
					'margin-right' => self::get_css_value( $attr['horSpace'], 'px' ),
				),
				'.responsive-block-editor-addons-timeline__left-block .responsive-block-editor-addons-timeline__day-new' => array(
					'margin-left' => self::get_css_value( $attr['horSpace'], 'px' ),
				),
				'.responsive-block-editor-addons-timeline__right-block .responsive-block-editor-addons-timeline__day-new' => array(
					'margin-right' => self::get_css_value( $attr['horSpace'], 'px' ),
				),
				' .responsive-block-editor-addons-timeline__field.responsive-block-editor-addons-timeline__field-wrap' => array(
					'margin-bottom' => self::get_css_value( $attr['verSpace'], 'px' ),
				),
				' .responsive-block-editor-addons-timeline__left-block .responsive-block-editor-addons-timeline__line' => array(
					'left' => 'calc(' . $attr['bgSize'] . '/2)px',
				),
				' .responsive-block-editor-addons-timeline__right-block .responsive-block-editor-addons-timeline__line' => array(
					'right' => 'calc(' . $attr['bgSize'] . '/2)px',
				),
				' .responsive-block-editor-addons-timeline__main .responsive-block-editor-addons-timeline__post p' => array(
					'line-height' => $attr['contentLineHeight'],
				),
				' .responsive-block-editor-addons-timeline__events-new' => array(
					'margin-bottom' => self::get_css_value( $attr['blockSpace'], 'px' ),
				),
				' .responsive-block-editor-addons-timeline__events-new .responsive-block-editor-addons-timeline__events-inner-new' => array(
					'background-color' => $attr['bgColor']
					? $attr['bgColor'] : '#e4e4e4',
					'border-radius'    => self::get_css_value( $attr['borderRadius'], 'px' ),
				),
				' .responsive-block-editor-addons-timeline__date-hide.responsive-block-editor-addons-timeline__date-inner .responsive-block-editor-addons-timeline__date-new' => array(
					'line-height' => $attr['dateLineHeight'],
					'font-weight' => $attr['dateFontWeight'],
					'font-size'   => self::get_css_value( $attr['dateFontSize'], 'px' ),
					'font-family' => $attr['dateFontFamily'],
				),
				' .responsive-block-editor-addons-content' => array(
					'padding' => self::get_css_value( $attr['contentPadding'], 'px' ),
				),
				' .responsive-block-editor-addons-block-post-timeline-title' => array(
					'margin-bottom' => self::get_css_value( $attr['headingSpace'], 'px' ),
				),
				' .responsive-block-editor-addons-block-post-timeline-title .responsive-block-editor-addons-block-post-timeline-title-heading' => array(
					'color'       => $attr['headingColor']
					? $attr['headingColor'] . '!important'
					: '#333',
					'line-height' => $attr['headingLineHeight'],
					'font-weight' => $attr['headingFontWeight'],
					'font-size'   => self::get_css_value( $attr['headingFontSize'], 'px' ),
					'font-family' => $attr['headingFontFamily'],
				),
				' .responsive-block-editor-addons-block-post-timeline-byline' => array(
					'margin-bottom' => self::get_css_value( $attr['authorSpace'], 'px' ),
				),
				' .responsive-block-editor-addons-block-post-timeline-author .responsive-block-editor-addons-text-link' => array(
					'color'       => $attr['authorColor']
					? $attr['authorColor'] . '!important'
					: '#626e81',
					'line-height' => $attr['authorLineHeight'],
					'font-weight' => $attr['authorFontWeight'],
					'font-size'   => self::get_css_value( $attr['authorFontSize'], 'px' ),
					'font-family' => $attr['authorFontFamily'],
				),
				' .responsive-block-editor-addons-block-post-timeline-excerpt .responsive-block-editor-addons-timeline__post' => array(
					'color'         => $attr['textColor']
					? $attr['textColor'] . '!important'
					: '#333',
					'font-weight'   => $attr['contentFontWeight'],
					'font-size'     => self::get_css_value( $attr['contentFontSize'], 'px' ),
					'font-family'   => $attr['contentFontFamily'],
					'margin-bottom' => self::get_css_value( $attr['excerptSpace'], 'px' ),
				),
				' .responsive-block-editor-addons-timeline__date-new.responsive-block-editor-addons-timeline__date-outer' => array(
					'line-height' => $attr['dateLineHeight'],
					'font-weight' => $attr['dateFontWeight'],
					'font-size'   => self::get_css_value( $attr['dateFontSize'], 'px' ),
					'font-family' => $attr['dateFontFamily'],
				),
				' .responsive-block-editor-addons-timeline__main .responsive-block-editor-addons-timeline__line__inner' => array(
					'background-color' => $attr['separatorFillColor'],
				),
				' .responsive-block-editor-addons-timeline__main .responsive-block-editor-addons-timeline__marker.responsive-block-editor-addons-timeline__in-view-icon' => array(
					'background'   => $attr['iconBgFocus'],
					'border-color' => $attr['borderFocus'],
					'color'        => $attr['iconFocus'],
				),

				' .responsive-block-editor-addons-timeline__main .responsive-block-editor-addons-timeline__marker.responsive-block-editor-addons-timeline__in-view-icon svg' => array(
					'fill' => $attr['iconFocus'],
				),

				' .responsive-block-editor-addons-timeline__main .responsive-block-editor-addons-timeline__marker.responsive-block-editor-addons-timeline__in-view-icon .responsive-block-editor-addons-timeline__icon-new' => array(
					'color' => $attr['iconFocus'],
				),
			);

			$mobile_selectors = array(
				' .responsive-block-editor-addons-timeline__center-block.responsive-block-editor-addons-timeline__responsive-mobile .responsive-block-editor-addons-timeline__line' => array(
					'left'  => 'calc(' . $attr['bgSize'] . '/2)px',
					'right' => 'calc(' . $attr['bgSize'] . '/2)px',
				),
				' .responsive-block-editor-addons-timeline__left-block.responsive-block-editor-addons-timeline__responsive-mobile .responsive-block-editor-addons-timeline__line' => array(
					'left' => 'calc(' . $attr['bgSize'] . '/2)px',
				),
				' .responsive-block-editor-addons-timeline__right-block.responsive-block-editor-addons-timeline__responsive-mobile .responsive-block-editor-addons-timeline__line' => array(
					'right' => 'calc(' . $attr['bgSize'] . '/2)px',
				),
				' .responsive-block-editor-addons-timeline__link_parent .responsive-block-editor-addons-timeline__link' => array(
					'font-size' => self::get_css_value( $attr['continueFontSizeMobile'], 'px' ),
				),
				' .responsive-block-editor-addons-timeline__date-hide.responsive-block-editor-addons-timeline__date-inner .responsive-block-editor-addons-timeline__date-new' => array(
					'font-size' => self::get_css_value( $attr['dateFontSizeMobile'], 'px' ),
				),
				' .responsive-block-editor-addons-block-post-timeline-title .responsive-block-editor-addons-block-post-timeline-title-heading' => array(
					'font-size' => self::get_css_value( $attr['headingFontSizeMobile'], 'px' ),
				),
				' .responsive-block-editor-addons-block-post-timeline-author .responsive-block-editor-addons-text-link' => array(
					'font-size' => self::get_css_value( $attr['authorFontSizeMobile'], 'px' ),
				),
				' .responsive-block-editor-addons-block-post-timeline-excerpt .responsive-block-editor-addons-timeline__post' => array(
					'font-size'     => self::get_css_value( $attr['contentFontSizeMobile'], 'px' ),
					'margin-bottom' => self::get_css_value( $attr['excerptSpaceMobile'], 'px' ),
				),
				' .responsive-block-editor-addons-timeline__date-new.responsive-block-editor-addons-timeline__date-outer' => array(
					'font-size' => self::get_css_value( $attr['dateFontSizeMobile'], 'px' ),
				),
				' .responsive-block-editor-addons-content' => array(
					'padding' => self::get_css_value( $attr['contentPaddingMobile'], 'px' ),
				),
				' .responsive-block-editor-addons-timeline__events-new' => array(
					'margin-bottom' => self::get_css_value( $attr['blockSpaceMobile'], 'px' ),
				),
				' .responsive-block-editor-addons-block-post-timeline-title' => array(
					'margin-bottom' => self::get_css_value( $attr['headingSpaceMobile'], 'px' ),
				),
				' .responsive-block-editor-addons-block-post-timeline-byline' => array(
					'margin-bottom' => self::get_css_value( $attr['authorSpaceMobile'], 'px' ),
				),
				' .responsive-block-editor-addons-timeline__field.responsive-block-editor-addons-timeline__field-wrap' => array(
					'margin-bottom' => self::get_css_value( $attr['verSpaceMobile'], 'px' ),
				),
				'.responsive-block-editor-addons-timeline__center-block .responsive-block-editor-addons-timeline__marker' => array(
					'margin-left'  => self::get_css_value( $attr['horSpaceMobile'], 'px' ),
					'margin-right' => self::get_css_value( $attr['horSpaceMobile'], 'px' ),
				),
				'.responsive-block-editor-addons-timeline__left-block .responsive-block-editor-addons-timeline__day-new' => array(
					'margin-left' => self::get_css_value( $attr['horSpaceMobile'], 'px' ),
				),
				'.responsive-block-editor-addons-timeline__right-block .responsive-block-editor-addons-timeline__day-new' => array(
					'margin-right' => self::get_css_value( $attr['horSpaceMobile'], 'px' ),
				),
			);

			$tablet_selectors = array(
				' .responsive-block-editor-addons-timeline__center-block.responsive-block-editor-addons-timeline__responsive-tablet .responsive-block-editor-addons-timeline__line' => array(
					'left'  => 'calc(' . $attr['bgSize'] . '/2)px',
					'right' => 'calc(' . $attr['bgSize'] . '/2)px',
				),
				' .responsive-block-editor-addons-timeline__left-block.responsive-block-editor-addons-timeline__responsive-tablet .responsive-block-editor-addons-timeline__line' => array(
					'left' => 'calc(' . $attr['bgSize'] . '/2)px',
				),
				' .responsive-block-editor-addons-timeline__right-block.responsive-block-editor-addons-timeline__responsive-tablet .responsive-block-editor-addons-timeline__line' => array(
					'right' => 'calc(' . $attr['bgSize'] . '/2)px',
				),
				' .responsive-block-editor-addons-timeline__link_parent .responsive-block-editor-addons-timeline__link' => array(
					'font-size' => self::get_css_value( $attr['continueFontSizeTablet'], 'px' ),
				),
				' .responsive-block-editor-addons-timeline__date-hide.responsive-block-editor-addons-timeline__date-inner .responsive-block-editor-addons-timeline__date-new' => array(
					'font-size' => self::get_css_value( $attr['dateFontSizeTablet'], 'px' ),
				),
				' .responsive-block-editor-addons-block-post-timeline-title .responsive-block-editor-addons-block-post-timeline-title-heading' => array(
					'font-size' => self::get_css_value( $attr['headingFontSizeTablet'], 'px' ),
				),
				' .responsive-block-editor-addons-block-post-timeline-author .responsive-block-editor-addons-text-link' => array(
					'font-size' => self::get_css_value( $attr['authorFontSizeTablet'], 'px' ),
				),
				' .responsive-block-editor-addons-block-post-timeline-excerpt .responsive-block-editor-addons-timeline__post' => array(
					'font-size'     => self::get_css_value( $attr['contentFontSizeTablet'], 'px' ),
					'margin-bottom' => self::get_css_value( $attr['excerptSpaceTablet'], 'px' ),
				),
				' .responsive-block-editor-addons-timeline__date-new.responsive-block-editor-addons-timeline__date-outer' => array(
					'font-size' => self::get_css_value( $attr['dateFontSizeTablet'], 'px' ),
				),
				' .responsive-block-editor-addons-content' => array(
					'padding' => self::get_css_value( $attr['contentPaddingTablet'], 'px' ),
				),
				' .responsive-block-editor-addons-timeline__events-new' => array(
					'margin-bottom' => self::get_css_value( $attr['blockSpaceTablet'], 'px' ),
				),
				' .responsive-block-editor-addons-block-post-timeline-title' => array(
					'margin-bottom' => self::get_css_value( $attr['headingSpaceTablet'], 'px' ),
				),
				' .responsive-block-editor-addons-block-post-timeline-byline' => array(
					'margin-bottom' => self::get_css_value( $attr['authorSpaceTablet'], 'px' ),
				),
				' .responsive-block-editor-addons-timeline__field.responsive-block-editor-addons-timeline__field-wrap' => array(
					'margin-bottom' => self::get_css_value( $attr['verSpaceTablet'], 'px' ),
				),
				'.responsive-block-editor-addons-timeline__center-block .responsive-block-editor-addons-timeline__marker' => array(
					'margin-left'  => self::get_css_value( $attr['horSpaceTablet'], 'px' ),
					'margin-right' => self::get_css_value( $attr['horSpaceTablet'], 'px' ),
				),
				'.responsive-block-editor-addons-timeline__left-block .responsive-block-editor-addons-timeline__day-new' => array(
					'margin-left' => self::get_css_value( $attr['horSpaceTablet'], 'px' ),
				),
				'.responsive-block-editor-addons-timeline__right-block .responsive-block-editor-addons-timeline__day-new' => array(
					'margin-right' => self::get_css_value( $attr['horSpaceTablet'], 'px' ),
				),
			);

			$combined_selectors = array(
				'desktop' => $selectors,
				'tablet'  => $tablet_selectors,
				'mobile'  => $mobile_selectors,
			);

			$id  = '.responsive-block-editor-addons-block-post-timeline.block-' . $id;
			$css = Responsive_Block_Editor_Addons_Frontend_Styles_Helper::responsive_block_editor_addons_generate_all_css( $combined_selectors, $id );
			return $css;
		}

		/**
		 * Get Defaults for post timeline block
		 *
		 * @return array
		 */
		public static function get_responsive_block_post_timeline_default_attributes() {
			return array(
				'categories'             => '',
				'className'              => '',
				'postsToShow'            => 6,
				'displayPostDate'        => true,
				'displayPostExcerpt'     => true,
				'displayPostAuthor'      => true,
				'displayPostImage'       => true,
				'displayPostLink'        => true,
				'displayPostTitle'       => true,
				'displaySectionTitle'    => false,
				'postTitleTag'           => 'h3',
				'postLayout'             => 'grid',
				'columns'                => 2,
				'align'                  => 'center',
				'timelinAlignment'       => 'center',
				'arrowlinAlignment'      => 'center',
				'width'                  => 'wide',
				'order'                  => 'desc',
				'orderBy'                => 'date',
				'readMoreText'           => 'Continue Reading',
				'offset'                 => 0,
				'excerptLength'          => 55,
				'postType'               => 'post',
				'sectionTag'             => 'section',
				'sectionTitle'           => '',
				'sectionTitleTag'        => 'h2',
				'imageSize'              => 'full',
				'url'                    => '',
				'source'                 => 'attribute',
				'selector'               => 'img',
				'attribute'              => 'src',
				'id'                     => '',
				'bgColor'                => '#e4e4e4',
				'textColor'              => '#333',
				'contentPadding'         => 20,
				'contentPaddingMobile'   => '',
				'contentPaddingTablet'   => '',
				'authorSpace'            => '',
				'authorSpaceTablet'      => '',
				'authorSpaceMobile'      => '',
				'excerptSpace'           => '',
				'excerptSpaceTablet'     => '',
				'excerptSpaceMobile'     => '',
				'blockSpace'             => '',
				'blockSpaceTablet'       => '',
				'blockSpaceMobile'       => '',
				'headingSpace'           => '',
				'headingSpaceTablet'     => '',
				'headingSpaceMobile'     => '',
				'headingColor'           => '#333',
				'authorColor'            => '#626e81',
				'continueColor'          => '#333',
				'dateFontFamily'         => '',
				'headingFontFamily'      => '',
				'authorFontFamily'       => '',
				'contentFontFamily'      => '',
				'continueFontFamily'     => '',
				'connectorColor'         => '#eeeeee',
				'dateFontSize'           => 16,
				'dateFontWeight'         => 400,
				'dateLineHeight'         => 1.75,
				'headingFontSize'        => 20,
				'headingFontWeight'      => 700,
				'headingLineHeight'      => 1.5,
				'authorFontSize'         => 14,
				'authorFontWeight'       => 400,
				'authorLineHeight'       => 1.5,
				'contentFontSize'        => 16,
				'contentFontWeight'      => 400,
				'contentLineHeight'      => 1.75,
				'continueFontSize'       => 16,
				'continueFontWeight'     => 700,
				'continueLineHeight'     => 1.75,
				'icon'                   => 'calendar-alt',
				'iconSize'               => 16,
				'bgSize'                 => 35,
				'borderWidth'            => 0,
				'connectorWidth'         => 3,
				'iconColor'              => '#333',
				'separatorBg'            => '#eee',
				'separatorBorder'        => '#eee',
				'separatorFillColor'     => '#61ce70',
				'iconFocus'              => '#fff',
				'iconBgFocus'            => '#61ce70',
				'borderFocus'            => '#5cb85c',
				'continuebgColor'        => '',
				'borderColor'            => '',
				'hColor'                 => '#333',
				'continuebghColor'       => '',
				'borderHColor'           => '',
				'target'                 => true,
				'borderRadius'           => 0,
				'verSpace'               => 0,
				'verSpaceTablet'         => 0,
				'verSpaceMobile'         => 0,
				'horSpace'               => 0,
				'horSpaceTablet'         => 0,
				'horSpaceMobile'         => 0,
				'stack'                  => 'mobile',
				'boxShadowColor'         => '',
				'boxShadowPosition'      => 'outset',
				'boxShadowHOffset'       => 0,
				'boxShadowVOffset'       => 0,
				'boxShadowBlur'          => 0,
				'boxShadowSpread'        => 0,
				'taxonomyType'           => 'category',
				'block_id'               => '',
				'dateFontSizeMobile'     => '',
				'dateFontSizeTablet'     => '',
				'headingFontSizeMobile'  => '',
				'headingFontSizeTablet'  => '',
				'authorFontSizeMobile'   => '',
				'authorFontSizeTablet'   => '',
				'contentFontSizeMobile'  => '',
				'contentFontSizeTablet'  => '',
				'continueFontSizeMobile' => '',
				'continueFontSizeTablet' => '',
			);
		}

		/**
		 * Get Pricing list Block CSS
		 *
		 * @param array  $attr The block attributes.
		 * @param string $id The selector ID.
		 * @return array Styles.
		 */
		public static function get_responsive_block_pricing_list_css( $attr, $id ) {
			$defaults = self::get_responsive_block_pricing_list_default_attributes();
			$attr     = array_merge( $defaults, (array) $attr );

			$mobile_selectors = array();
			$tablet_selectors = array();

			$align = $attr['contentAlign'];
			if ( 'left' === $align ) {
				$align = 'flex-start';
			} elseif ( 'right' === $align ) {
				$align = 'flex-end';
			}

			$selectors        = array(
				' .responsive-block-editior-addons-pricing-list-item-wrap' => array(
					'margin-bottom' => self::get_css_value( $attr['rowGap'], 'px' ),
					'padding-left'  => self::get_css_value( $attr['columnGap'] / 2, 'px' ),
					'padding-right' => self::get_css_value( $attr['columnGap'] / 2, 'px' ),
				),
				' .responsive-block-editior-addons-pricing-list-item-wrap .responsive-block-editior-addons-pricing-list-item-content' => array(
					'padding-top'    => 999 !== $attr['topPadding'] && 5 === $attr['blockTopPadding'] ? self::get_css_value( $attr['topPadding'], 'px' ) : self::get_css_value( $attr['blockTopPadding'], 'px' ), // For compatibility with v1.3.2.
					'padding-bottom' => 999 !== $attr['bottomPadding'] && 5 === $attr['blockBottomPadding'] ? self::get_css_value( $attr['bottomPadding'], 'px' ) : self::get_css_value( $attr['blockBottomPadding'], 'px' ), // For compatibility with v1.3.2.
					'padding-left'   => 999 !== $attr['leftPadding'] && 5 === $attr['blockLeftPadding'] ? self::get_css_value( $attr['leftPadding'], 'px' ) : self::get_css_value( $attr['blockLeftPadding'], 'px' ), // For compatibility with v1.3.2.
					'padding-right'  => 999 !== $attr['rightPadding'] && 5 === $attr['blockRightPadding'] ? self::get_css_value( $attr['rightPadding'], 'px' ) : self::get_css_value( $attr['blockRightPadding'], 'px' ), // For compatibility with v1.3.2.
					'text-align'     => $attr['contentAlign'],
				),
				' .responsive-block-editior-addons-pricing-list-item-image-wrap .responsive-block-editior-addons-pricing-list-item-image' => array(
					'height'    => 'auto',
					'width'     => self::get_css_value( $attr['imageWidth'], 'px' ),
					'max-width' => self::get_css_value( $attr['imageWidth'], 'px' ),
				),
				' .responsive-block-editior-addons-pricing-list-item-title' => array(
					'color'         => $attr['titleColor'],
					'line-height'   => $attr['titleLineHeight'],
					'font-weight'   => $attr['titleFontWeight'],
					'font-size'     => self::get_css_value( $attr['titleFontSize'], 'px' ),
					'font-family'   => $attr['titleFontFamily'],
					'margin-bottom' => self::get_css_value( $attr['titleSpace'], 'px' ),
				),
				' .responsive-block-editior-addons-pricing-list-item-description' => array(
					'color'       => $attr['descColor'],
					'line-height' => $attr['descriptionLineHeight'],
					'font-weight' => $attr['descriptionFontWeight'],
					'font-size'   => self::get_css_value( $attr['descriptionFontSize'], 'px' ),
					'font-family' => $attr['descriptionFontFamily'],
				),
				' .responsive-block-editior-addons-pricing-list-item-price-wrap' => array(
					'color'       => $attr['priceColor'],
					'line-height' => $attr['priceLineHeight'],
					'font-weight' => $attr['priceFontWeight'],
					'font-size'   => self::get_css_value( $attr['priceFontSize'], 'px' ),
					'font-family' => $attr['priceFontFamily'],
				),
				' .responsive-block-editior-addons-pricing-list-item-image-wrap responsive-block-editior-addons-pricing-list-item-image' => array(
					'height'    => 'auto',
					'width'     => self::get_css_value( $attr['imageWidth'], 'px' ),
					'max-width' => self::get_css_value( $attr['imageWidth'], 'px' ),
				),
				' .responsive-block-editior-addons-pricing-list-item-separator-wrap' => array(
					'justify-content' => $align,
				),
				' .responsive-block-editior-addons-pricing-list-item-separator' => array(
					'border-top-color' => $attr['seperatorColor'],
					'border-top-style' => $attr['seperatorStyle'],
					'border-top-width' => self::get_css_value( $attr['seperatorThickness'], 'px' ),
					'width'            => $attr['seperatorWidth'] . '%',
				),
			);
			$mobile_selectors = array(
				' .responsive-block-editior-addons-pricing-list-item-wrap' => array(
					'margin-bottom' => self::get_css_value( $attr['rowGapMobile'], 'px' ),
					'padding-left'  => self::get_css_value( $attr['columnGapMobile'] / 2, 'px' ),
					'padding-right' => self::get_css_value( $attr['columnGapMobile'] / 2, 'px' ),
				),
				' .responsive-block-editior-addons-pricing-list-item-wrap .responsive-block-editior-addons-pricing-list-item-content' => array(
					'padding-top'    => self::get_css_value( $attr['blockTopPaddingMobile'], 'px' ),
					'padding-bottom' => self::get_css_value( $attr['blockBottomPaddingMobile'], 'px' ),
					'padding-left'   => self::get_css_value( $attr['blockLeftPaddingMobile'], 'px' ),
					'padding-right'  => self::get_css_value( $attr['blockRightPaddingMobile'], 'px' ),
				),
				' .responsive-block-editior-addons-pricing-list-item-title' => array(
					'font-size'     => self::get_css_value( $attr['titleFontSizeMobile'], 'px' ),
					'margin-bottom' => self::get_css_value( $attr['titleSpaceMobile'], 'px' ),
				),
				' .responsive-block-editior-addons-pricing-list-item-description' => array(
					'font-size' => self::get_css_value( $attr['descriptionFontSizeMobile'], 'px' ),
				),
				' .responsive-block-editior-addons-pricing-list-item-price-wrap' => array(
					'font-size' => self::get_css_value( $attr['priceFontSizeMobile'], 'px' ),
				),
			);

			$tablet_selectors = array(
				' .responsive-block-editior-addons-pricing-list-item-wrap' => array(
					'margin-bottom' => self::get_css_value( $attr['rowGapTablet'], 'px' ),
					'padding-left'  => self::get_css_value( $attr['columnGapTablet'] / 2, 'px' ),
					'padding-right' => self::get_css_value( $attr['columnGapTablet'] / 2, 'px' ),
				),
				' .responsive-block-editior-addons-pricing-list-item-wrap .responsive-block-editior-addons-pricing-list-item-content' => array(
					'padding-top'    => self::get_css_value( $attr['blockTopPaddingTablet'], 'px' ),
					'padding-bottom' => self::get_css_value( $attr['blockBottomPaddingTablet'], 'px' ),
					'padding-left'   => self::get_css_value( $attr['blockLeftPaddingTablet'], 'px' ),
					'padding-right'  => self::get_css_value( $attr['blockRightPaddingTablet'], 'px' ),
				),
				' .responsive-block-editior-addons-pricing-list-item-title' => array(
					'font-size'     => self::get_css_value( $attr['titleFontSizeTablet'], 'px' ),
					'margin-bottom' => self::get_css_value( $attr['titleSpaceTablet'], 'px' ),
				),
				' .responsive-block-editior-addons-pricing-list-item-description' => array(
					'font-size' => self::get_css_value( $attr['descriptionFontSizeTablet'], 'px' ),
				),
				' .responsive-block-editior-addons-pricing-list-item-price-wrap' => array(
					'font-size' => self::get_css_value( $attr['priceFontSizeTablet'], 'px' ),
				),
			);

			$combined_selectors = array(
				'desktop' => $selectors,
				'tablet'  => $tablet_selectors,
				'mobile'  => $mobile_selectors,
			);

			$id  = '.responsive-block-editor-addons-block-pricing-list.block-' . $id;
			$css = Responsive_Block_Editor_Addons_Frontend_Styles_Helper::responsive_block_editor_addons_generate_all_css( $combined_selectors, $id );
			return $css;
		}

		/**
		 * Get Defaults for pricing list block
		 *
		 * @return array
		 */
		public static function get_responsive_block_pricing_list_default_attributes() {
			return array(
				'block_id'                  => '',
				'pricingList'               => '',
				'blockTopPadding'           => 5,
				'blockBottomPadding'        => 5,
				'blockLeftPadding'          => 5,
				'blockRightPadding'         => 5,
				'rowGap'                    => 10,
				'columnGap'                 => 10,
				'titleSpace'                => 10,
				'blockTopPaddingMobile'     => 5,
				'blockBottomPaddingMobile'  => 5,
				'blockLeftPaddingMobile'    => 5,
				'blockRightPaddingMobile'   => 5,
				'rowGapMobile'              => 10,
				'columnGapMobile'           => 10,
				'titleSpaceMobile'          => 10,
				'blockTopPaddingTablet'     => 5,
				'blockBottomPaddingTablet'  => 5,
				'blockLeftPaddingTablet'    => 5,
				'blockRightPaddingTablet'   => 5,
				'rowGapTablet'              => 10,
				'columnGapTablet'           => 10,
				'titleSpaceTablet'          => 10,
				'titleFontFamily'           => '',
				'descriptionFontFamily'     => '',
				'priceFontFamily'           => '',
				'titleFontSize'             => '',
				'titleFontWeight'           => '',
				'titleLineHeight'           => '',
				'descriptionFontSize'       => '',
				'descriptionFontWeight'     => '',
				'descriptionLineHeight'     => '',
				'priceFontSize'             => '',
				'priceFontWeight'           => '',
				'priceLineHeight'           => '',
				'seperatorStyle'            => 'dashed',
				'seperatorWidth'            => 100,
				'seperatorThickness'        => 1,
				'seperatorColor'            => '',
				'titleColor'                => '',
				'descColor'                 => '',
				'priceColor'                => '',
				'columns'                   => 2,
				'count'                     => '',
				'contentAlign'              => 'left',
				'imagePosition'             => 'top',
				'imageAlignment'            => 'middle',
				'imageSize'                 => 'medium',
				'imageWidth'                => '',
				'titleFontSizeMobile'       => '',
				'titleFontSizeTablet'       => '',
				'descriptionFontSizeMobile' => '',
				'descriptionFontSizeTablet' => '',
				'priceFontSizeMobile'       => '',
				'priceFontSizeTablet'       => '',
				'topPadding'                => 999, // For compatibility with v1.3.2.
				'bottomPadding'             => 999, // For compatibility with v1.3.2.
				'leftPadding'               => 999, // For compatibility with v1.3.2.
				'rightPadding'              => 999, // For compatibility with v1.3.2.
			);
		}

		/**
		 * Get Pricing table Block CSS
		 *
		 * @param array  $attr The block attributes.
		 * @param string $id The selector ID.
		 * @return array Styles.
		 */
		public static function get_responsive_block_pricing_table_css( $attr, $id ) {
			$defaults = self::get_responsive_block_pricing_table_default_attributes();
			$attr     = array_merge( $defaults, (array) $attr );

			$mobile_selectors = array();
			$tablet_selectors = array();

			$box_shadow_position_css = $attr['boxShadowPosition'];
			if ( 'outset' === $attr['boxShadowPosition'] ) {
				$box_shadow_position_css = '';
			}

			$button_box_shadow_position_css = $attr['buttonBoxShadowPosition'];
			if ( 'outset' === $attr['buttonBoxShadowPosition'] ) {
				$button_box_shadow_position_css = '';
			}

			$imgopacity             = $attr['opacity'] / 100;
			$blockimgopacity        = $attr['blockopacity'] / 100;
			$blockbackcoloropacity  = $attr['blockBackColorOpacity'] / 100;
			$columnbackcoloropacity = $attr['columnBackColorOpacity'] / 100;

			$align_style = 'center';
			if ( 'left' === $attr['blockAlign'] ) {
				$align_style = 'flex-start';
			}
			if ( 'right' === $attr['blockAlign'] ) {
				$align_style = 'flex-end';
			}

			$updated_button_bg_h_image = '';
			if ( 'gradient' === $attr['buttonHbackgroundType'] ) {
				$updated_button_bg_h_image = 'linear-gradient(' . $attr['buttonHgradientDirection'] . 'deg, ' . $attr['buttonHbackgroundColor1'] . ' ' . $attr['buttonHcolorLocation1'] . '% , ' . $attr['buttonHbackgroundColor2'] . ' ' . $attr['buttonHcolorLocation2'] . '%)';
			}

			$updated_button_background_color = '';
			$updated_button_background_image = '';
			if ( 'color' === $attr['buttonbackgroundType'] ) {
				$updated_button_background_color = $attr['ctaBackColor'];
			} elseif ( 'gradient' === $attr['buttonbackgroundType'] ) {
				$updated_button_background_image = 'linear-gradient(' . $attr['buttongradientDirection'] . 'deg, ' . $attr['buttonbackgroundColor1'] . ' ' . $attr['buttoncolorLocation1'] . '%, ' . $attr['buttonbackgroundColor2'] . ' ' . $attr['buttoncolorLocation2'] . '%)';
			}

			$selectors = array(
				' .wp-block-responsive-block-editor-addons-pricing-table-item__button' => array(
					'color'            => $attr['ctaColor'] . '!important',
					'display'          => 'block',
					'background-color' => $updated_button_background_color,
					'background-image' => $updated_button_background_image,
					'margin-left'      => 'left' === $attr['blockAlign'] ? 0 : '',
					'margin-right'     => 'right' === $attr['blockAlign'] ? 0 : '',
					'margin-bottom'    => self::get_css_value( $attr['buttonSpace'], 'px' ),
					'padding-left'     => self::get_css_value( $attr['ctaHpadding'], 'px' ),
					'padding-right'    => self::get_css_value( $attr['ctaHpadding'], 'px' ),
					'padding-top'      => self::get_css_value( $attr['ctaVpadding'], 'px' ),
					'padding-bottom'   => self::get_css_value( $attr['ctaVpadding'], 'px' ),
					'border-color'     => $attr['ctaBorderColor'],
					'border-radius'    => self::get_css_value( $attr['ctaBorderRadius'], 'px' ),
					'border-width'     => self::get_css_value( $attr['ctaBorderWidth'], 'px' ),
					'border-style'     => $attr['ctaBorderStyle'],
					'line-height'      => $attr['ctaLineHeight'],
					'font-weight'      => $attr['ctaFontWeight'],
					'font-size'        => self::get_css_value( $attr['ctaFontSize'], 'px' ),
					'font-family'      => $attr['ctaFontFamily'],
					'box-shadow'       =>
						self::get_css_value( $attr['buttonBoxShadowHOffset'], 'px' ) .
						' ' .
						self::get_css_value( $attr['buttonBoxShadowVOffset'], 'px' ) .
						' ' .
						self::get_css_value( $attr['buttonBoxShadowBlur'], 'px' ) .
						' ' .
						self::get_css_value( $attr['buttonBoxShadowSpread'], 'px' ) .
						' ' .
						$attr['buttonBoxShadowColor'] .
						' ' .
						$button_box_shadow_position_css,
				),

				' .wp-block-responsive-block-editor-addons-pricing-table-item__button:hover' => array(
					'color'            => $attr['ctaHoverColor'] . '!important',
					'background-color' => 'color' === $attr['buttonHbackgroundType'] ? $attr['ctaHoverBackColor'] : ' ',
					'background-image' => 'color' === $attr['buttonHbackgroundType'] ? 'none' : $updated_button_bg_h_image,
					'border-color'     => $attr['ctaHoverBorderColor'],
				),

				' .wp-block-responsive-block-editor-addons-pricing-table-item.background-type-image' => array(
					'background-image'      => 'linear-gradient(' .
					self::hex_to_rgb( '#fff', 1 - $imgopacity ) .
					',' .
					self::hex_to_rgb( '#fff', 1 - $imgopacity ) .
					'),' .
					'url(' . $attr['backgroundImage'] . ')',
					'background-position'   => 'center center',
					'background-attachment' => 'scroll',
					'background-repeat'     => 'no-repeat',
					'background-size'       => 'cover',
				),

				'' => array(
					'text-align'       => $attr['blockAlign'],
					'padding-top'      => self::get_css_value( $attr['blockTopPadding'], 'px!important' ),
					'padding-bottom'   => self::get_css_value( $attr['blockBottomPadding'], 'px!important' ),
					'padding-left'     => self::get_css_value( $attr['blockLeftPadding'], 'px!important' ),
					'padding-right'    => self::get_css_value( $attr['blockRightPadding'], 'px!important' ),
					'background-color' => 'color' === $attr['blockbackgroundType'] ? self::hex_to_rgb( $attr['blockbackgroundColor'], 0 ) : '',
					'opacity'          => 'color' === $attr['blockbackgroundType'] ? $attr['blockBackColorOpacity'] : 100,
					'background-image' =>
						'gradient' === $attr['blockbackgroundType']
						? self::generate_background_image_effect(
							$attr['blockbackgroundColor1'],
							$attr['blockbackgroundColor2'],
							$attr['blockgradientDirection'],
							$attr['blockcolorLocation1'],
							$attr['blockcolorLocation2']
						)
						: null,
				),

				' .responsive-block-editor-addons-pricing-table-background-image' => array(
					'height'  => '100%',
					'opacity' => $blockimgopacity,
				),

				' .wp-block-responsive-block-editor-addons-pricing-table-item' => array(
					'padding-top'      => self::get_css_value( $attr['columnTopPadding'], 'px' ),
					'padding-bottom'   => self::get_css_value( $attr['columnBottomPadding'], 'px' ),
					'padding-left'     => self::get_css_value( $attr['columnLeftPadding'], 'px' ),
					'padding-right'    => self::get_css_value( $attr['columnRightPadding'], 'px' ),
					'color'            => $attr['textColor'],
					'background-color' => $attr['itemBackgroundColor'],
					'border-width'     => self::get_css_value( $attr['blockBorderWidth'], 'px' ),
					'border-color'     => $attr['blockBorderColor'],
					'border-style'     => $attr['blockBorderStyle'],
					'border-radius'    => self::get_css_value( $attr['blockBorderRadius'], 'px' ),
					'background-color' =>
						'color' === $attr['backgroundType']
						? self::hex_to_rgb( $attr['backgroundColor'], $columnbackcoloropacity )
						: '#eee',
					'background-image' =>
						'gradient' === $attr['backgroundType']
						? self::generate_background_image_effect(
							self::hex_to_rgb(
								$attr['backgroundColor1'],
								$columnbackcoloropacity
							),
							self::hex_to_rgb(
								$attr['backgroundColor2'],
								$columnbackcoloropacity
							),
							$attr['gradientDirection'],
							$attr['colorLocation1'],
							$attr['colorLocation2']
						)
						: null,
					'box-shadow'       =>
						self::get_css_value( $attr['boxShadowHOffset'], 'px' ) .
						' ' .
						self::get_css_value( $attr['boxShadowVOffset'], 'px' ) .
						' ' .
						self::get_css_value( $attr['boxShadowBlur'], 'px' ) .
						' ' .
						self::get_css_value( $attr['boxShadowSpread'], 'px' ) .
						' ' .
						$attr['boxShadowColor'] .
						' ' .
						$box_shadow_position_css,
				),

				' .wp-block-responsive-block-editor-addons-pricing-table-item__title' => array(
					'color'         => $attr['titleColor'],
					'line-height'   => $attr['titleLineHeight'],
					'font-weight'   => $attr['titleFontWeight'],
					'font-size'     => self::get_css_value( $attr['titleFontSize'], 'px' ),
					'font-family'   => $attr['titleFontFamily'],
					'margin-bottom' => self::get_css_value( $attr['titleSpace'], 'px' ),
				),

				' .wp-block-responsive-block-editor-addons-pricing-table-item__price-wrapper' => array(
					'margin-bottom'   => self::get_css_value( $attr['priceSpace'], 'px' ),
					'justify-content' => $align_style,
				),

				' .wp-block-responsive-block-editor-addons-pricing-table-item__currency' => array(
					'color'       => $attr['prefixColor'],
					'line-height' => $attr['prefixLineHeight'],
					'font-weight' => $attr['prefixFontWeight'],
					'font-size'   => self::get_css_value( $attr['prefixFontSize'], 'px' ),
					'font-family' => $attr['prefixFontFamily'],
				),

				' .wp-block-responsive-block-editor-addons-pricing-table-item__amount' => array(
					'color'       => $attr['priceColor'],
					'line-height' => $attr['amountLineHeight'],
					'font-weight' => $attr['amountFontWeight'],
					'font-size'   => self::get_css_value( $attr['amountFontSize'], 'px' ),
					'font-family' => $attr['amountFontFamily'],
				),

				' .wp-block-responsive-block-editor-addons-pricing-table-item__price_suffix' => array(
					'color'       => $attr['suffixColor'],
					'line-height' => $attr['suffixLineHeight'],
					'font-weight' => $attr['suffixFontWeight'],
					'font-size'   => self::get_css_value( $attr['suffixFontSize'], 'px' ),
					'font-family' => $attr['suffixFontFamily'],
				),

				' .wp-block-responsive-block-editor-addons-pricing-table-item__sub_price' => array(
					'color'          => $attr['subpriceColor'],
					'line-height'    => $attr['subpriceLineHeight'],
					'text-transform' => $attr['subpriceTextTransform'],
					'font-weight'    => $attr['subpriceFontWeight'],
					'font-size'      => self::get_css_value( $attr['subpriceFontSize'], 'px' ),
					'font-family'    => $attr['subpriceFontFamily'],
					'margin-bottom'  => self::get_css_value( $attr['subpriceSpace'], 'px' ),
				),

				' .wp-block-responsive-block-editor-addons-pricing-table-item__features' => array(
					'color'         => $attr['featuresColor'],
					'line-height'   => $attr['featuresLineHeight'],
					'font-weight'   => $attr['featuresFontWeight'],
					'font-size'     => self::get_css_value( $attr['featuresFontSize'], 'px' ),
					'font-family'   => $attr['featuresFontFamily'],
					'margin-bottom' => self::get_css_value( $attr['featuresSpace'], 'px' ),
				),
				' .responsive-block-editor-addons-pricing-image' => array(
					'width' => self::get_css_value( $attr['imageWidth'], 'px' ),
				),

			);

			$mobile_selectors = array(
				'' => array(
					'padding-top'    => self::get_css_value( $attr['blockTopPaddingMobile'], 'px' ) . ' !important',
					'padding-bottom' => self::get_css_value( $attr['blockBottomPaddingMobile'], 'px' ) . ' !important',
					'padding-left'   => self::get_css_value( $attr['blockLeftPaddingMobile'], 'px' ) . ' !important',
					'padding-right'  => self::get_css_value( $attr['blockRightPaddingMobile'], 'px' ) . ' !important',
				),
				' .wp-block-responsive-block-editor-addons-pricing-table__inner' => array(
					'flex-direction' => 'column',
				),
				' .wp-block-responsive-block-editor-addons-pricing-table-item__price-wrapper' => array(
					'margin-bottom' => self::get_css_value( $attr['priceSpaceMobile'], 'px' ),
				),
				' .wp-block-responsive-block-editor-addons-pricing-table-item' => array(
					'max-width'      => 'none',
					'padding-top'    => self::get_css_value( $attr['columnTopPaddingMobile'], 'px' ),
					'padding-bottom' => self::get_css_value( $attr['columnBottomPaddingMobile'], 'px' ),
					'padding-left'   => self::get_css_value( $attr['columnLeftPaddingMobile'], 'px' ),
					'padding-right'  => self::get_css_value( $attr['columnRightPaddingMobile'], 'px' ),
				),
				' .wp-block-responsive-block-editor-addons-pricing-table-item__title' => array(
					'font-size'     => self::get_css_value( $attr['titleFontSizeMobile'], 'px' ),
					'margin-bottom' => self::get_css_value( $attr['titleSpaceMobile'], 'px' ),
				),
				' .wp-block-responsive-block-editor-addons-pricing-table-item__currency' => array(
					'font-size' => self::get_css_value( $attr['prefixFontSizeMobile'], 'px' ),
				),
				' .wp-block-responsive-block-editor-addons-pricing-table-item__amount' => array(
					'font-size' => self::get_css_value( $attr['amountFontSizeMobile'], 'px' ),
				),
				' .wp-block-responsive-block-editor-addons-pricing-table-item__price_suffix' => array(
					'font-size' => self::get_css_value( $attr['suffixFontSizeMobile'], 'px' ),
				),
				' .wp-block-responsive-block-editor-addons-pricing-table-item__sub_price' => array(
					'font-size'     => self::get_css_value( $attr['subpriceFontSizeMobile'], 'px' ),
					'margin-bottom' => self::get_css_value( $attr['subpriceSpaceMobile'], 'px' ),
				),
				' .wp-block-responsive-block-editor-addons-pricing-table-item__features' => array(
					'font-size'     => self::get_css_value( $attr['featuresFontSizeMobile'], 'px' ),
					'margin-bottom' => self::get_css_value( $attr['featuresSpaceMobile'], 'px' ),
				),
				' .wp-block-responsive-block-editor-addons-pricing-table-item__button' => array(
					'padding-left'   => self::get_css_value( $attr['ctaHpaddingMobile'], 'px' ),
					'padding-right'  => self::get_css_value( $attr['ctaHpaddingMobile'], 'px' ),
					'padding-top'    => self::get_css_value( $attr['ctaVpaddingMobile'], 'px' ),
					'padding-bottom' => self::get_css_value( $attr['ctaVpaddingMobile'], 'px' ),
					'font-size'      => self::get_css_value( $attr['ctaFontSizeMobile'], 'px' ),
					'font-size'      => self::get_css_value( $attr['ctaFontSizeMobile'], 'px' ),
					'margin-bottom'  => self::get_css_value( $attr['buttonSpaceMobile'], 'px' ),
				),
				' .responsive-block-editor-addons-pricing-image' => array(
					'width' => self::get_css_value( $attr['imageWidthMobile'], 'px' ),
				),
			);

			$tablet_selectors = array(
				'' => array(
					'padding-top'    => self::get_css_value( $attr['blockTopPaddingTablet'], 'px' ) . ' !important',
					'padding-bottom' => self::get_css_value( $attr['blockBottomPaddingTablet'], 'px' ) . ' !important',
					'padding-left'   => self::get_css_value( $attr['blockLeftPaddingTablet'], 'px' ) . ' !important',
					'padding-right'  => self::get_css_value( $attr['blockRightPaddingTablet'], 'px' ) . ' !important',
				),
				' .wp-block-responsive-block-editor-addons-pricing-table-item' => array(
					'padding-top'    => self::get_css_value( $attr['columnTopPaddingTablet'], 'px' ),
					'padding-bottom' => self::get_css_value( $attr['columnBottomPaddingTablet'], 'px' ),
					'padding-left'   => self::get_css_value( $attr['columnLeftPaddingTablet'], 'px' ),
					'padding-right'  => self::get_css_value( $attr['columnRightPaddingTablet'], 'px' ),
				),
				' .wp-block-responsive-block-editor-addons-pricing-table-item__price-wrapper' => array(
					'margin-bottom' => self::get_css_value( $attr['priceSpaceTablet'], 'px' ),
				),
				' .wp-block-responsive-block-editor-addons-pricing-table-item__title' => array(
					'font-size'     => self::get_css_value( $attr['titleFontSizeTablet'], 'px' ),
					'margin-bottom' => self::get_css_value( $attr['titleSpaceTablet'], 'px' ),
				),
				' .wp-block-responsive-block-editor-addons-pricing-table-item__currency' => array(
					'font-size' => self::get_css_value( $attr['prefixFontSizeTablet'], 'px' ),
				),
				' .wp-block-responsive-block-editor-addons-pricing-table-item__amount' => array(
					'font-size' => self::get_css_value( $attr['amountFontSizeTablet'], 'px' ),
				),
				' .wp-block-responsive-block-editor-addons-pricing-table-item__price_suffix' => array(
					'font-size' => self::get_css_value( $attr['suffixFontSizeTablet'], 'px' ),
				),
				' .wp-block-responsive-block-editor-addons-pricing-table-item__sub_price' => array(
					'font-size'     => self::get_css_value( $attr['subpriceFontSizeTablet'], 'px' ),
					'margin-bottom' => self::get_css_value( $attr['subpriceSpaceTablet'], 'px' ),
				),
				' .wp-block-responsive-block-editor-addons-pricing-table-item__features' => array(
					'font-size'     => self::get_css_value( $attr['featuresFontSizeTablet'], 'px' ),
					'margin-bottom' => self::get_css_value( $attr['featuresSpaceTablet'], 'px' ),
				),
				' .wp-block-responsive-block-editor-addons-pricing-table-item__button' => array(
					'padding-left'   => self::get_css_value( $attr['ctaHpaddingTablet'], 'px' ),
					'padding-right'  => self::get_css_value( $attr['ctaHpaddingTablet'], 'px' ),
					'padding-top'    => self::get_css_value( $attr['ctaVpaddingTablet'], 'px' ),
					'padding-bottom' => self::get_css_value( $attr['ctaVpaddingTablet'], 'px' ),
					'font-size'      => self::get_css_value( $attr['ctaFontSizeTablet'], 'px' ),
					'font-size'      => self::get_css_value( $attr['ctaFontSizeTablet'], 'px' ),
					'margin-bottom'  => self::get_css_value( $attr['buttonSpaceTablet'], 'px' ),
				),
				' .responsive-block-editor-addons-pricing-image' => array(
					'width' => self::get_css_value( $attr['imageWidthTablet'], 'px' ),
				),
			);

			$combined_selectors = array(
				'desktop' => $selectors,
				'tablet'  => $tablet_selectors,
				'mobile'  => $mobile_selectors,
			);

			$id  = '.responsive-block-editor-addons-block-pricing-table.block-' . $id;
			$css = Responsive_Block_Editor_Addons_Frontend_Styles_Helper::responsive_block_editor_addons_generate_all_css( $combined_selectors, $id );
			return $css;
		}

		/**
		 * Get Defaults for pricing table block
		 *
		 * @return array
		 */
		public static function get_responsive_block_pricing_table_default_attributes() {
			return array(
				'block_id'                  => '',
				'pricingTable'              => '',
				'blockId'                   => '',
				'count'                     => 2,
				'gutter'                    => 'medium',
				'contentAlign'              => 'center',
				'textColor'                 => '',
				'titleColor'                => '',
				'prefixColor'               => '',
				'priceColor'                => '',
				'suffixColor'               => '',
				'subpriceColor'             => '',
				'featuresColor'             => '',
				'itemBackgroundColor'       => '',
				'blockBorderStyle'          => 'none',
				'blockBorderWidth'          => 1,
				'blockBorderRadius'         => 0,
				'blockBorderColor'          => '',
				'sectionTag'                => 'section',
				'opacity'                   => 30,
				'blockBackColorOpacity'     => 100,
				'columnBackColorOpacity'    => 100,
				'colorLocation1'            => 0,
				'colorLocation2'            => 100,
				'gradientDirection'         => 90,
				'backgroundImage'           => '',
				'backgroundColor'           => '',
				'backgroundColor1'          => '',
				'backgroundColor2'          => '#fff',
				'backgroundType'            => 'none',
				'blockopacity'              => '30',
				'blockcolorLocation1'       => 0,
				'blockcolorLocation2'       => 100,
				'blockgradientDirection'    => 90,
				'blockbackgroundImage'      => '',
				'blockbackgroundColor'      => '',
				'blockbackgroundColor1'     => '',
				'blockbackgroundColor2'     => '#fff',
				'blockbackgroundType'       => 'none',
				'boxShadowColor'            => '',
				'boxShadowHOffset'          => 0,
				'boxShadowVOffset'          => 0,
				'boxShadowBlur'             => 0,
				'boxShadowSpread'           => 0,
				'boxShadowPosition'         => 'outset',
				'buttonBoxShadowColor'      => '',
				'buttonBoxShadowHOffset'    => 0,
				'buttonBoxShadowVOffset'    => 0,
				'buttonBoxShadowBlur'       => 0,
				'buttonBoxShadowSpread'     => 0,
				'buttonBoxShadowPosition'   => 'outset',
				'ctaColor'                  => '#ffffff',
				'ctaBackColor'              => '#3f46ae',
				'ctaHoverColor'             => '#ffffff',
				'ctaHoverBackColor'         => '#3f46ae',
				'ctaBorderColor'            => '#333',
				'ctaBorderStyle'            => 'none',
				'ctaBorderRadius'           => 0,
				'ctaBorderWidth'            => 2,
				'ctaHpadding'               => 30,
				'ctaVpadding'               => 15,
				'buttonbackgroundType'      => 'color',
				'buttoncolorLocation1'      => 0,
				'buttoncolorLocation2'      => 100,
				'buttongradientDirection'   => 90,
				'buttonbackgroundColor1'    => '',
				'buttonbackgroundColor2'    => '#fff',
				'buttonHbackgroundType'     => 'color',
				'buttonHcolorLocation1'     => 0,
				'buttonHcolorLocation2'     => 100,
				'buttonHgradientDirection'  => 90,
				'buttonHbackgroundColor1'   => '',
				'buttonHbackgroundColor2'   => '#fff',
				'titleFontFamily'           => '',
				'amountFontFamily'          => '',
				'prefixFontFamily'          => '',
				'suffixFontFamily'          => '',
				'subpriceFontFamily'        => '',
				'featuresFontFamily'        => '',
				'ctaFontFamily'             => '',
				'titleFontSize'             => '',
				'titleFontWeight'           => '',
				'titleLineHeight'           => '',
				'amountFontSize'            => '',
				'amountFontWeight'          => '',
				'amountLineHeight'          => '',
				'subpriceFontSize'          => '',
				'subpriceFontWeight'        => '',
				'subpriceLineHeight'        => '',
				'subpriceTextTransform'     => 'uppercase',
				'prefixFontSize'            => '',
				'prefixFontWeight'          => '',
				'prefixLineHeight'          => '',
				'suffixFontSize'            => '',
				'suffixFontWeight'          => '',
				'suffixLineHeight'          => '',
				'featuresFontSize'          => '',
				'featuresFontWeight'        => '',
				'featuresLineHeight'        => '',
				'ctaFontSize'               => '',
				'ctaFontWeight'             => '',
				'ctaLineHeight'             => '',
				'blockTopPadding'           => 0,
				'blockBottomPadding'        => 0,
				'blockLeftPadding'          => 0,
				'blockRightPadding'         => 0,
				'columnTopPadding'          => 64,
				'columnBottomPadding'       => 64,
				'columnLeftPadding'         => 24,
				'columnRightPadding'        => 24,
				'blockTopPaddingMobile'     => '',
				'blockBottomPaddingMobile'  => '',
				'blockLeftPaddingMobile'    => '',
				'blockRightPaddingMobile'   => '',
				'columnTopPaddingMobile'    => '',
				'columnBottomPaddingMobile' => '',
				'columnLeftPaddingMobile'   => '',
				'columnRightPaddingMobile'  => '',
				'blockTopPaddingTablet'     => '',
				'blockBottomPaddingTablet'  => '',
				'blockLeftPaddingTablet'    => '',
				'blockRightPaddingTablet'   => '',
				'columnTopPaddingTablet'    => '',
				'columnBottomPaddingTablet' => '',
				'columnLeftPaddingTablet'   => '',
				'columnRightPaddingTablet'  => '',
				'showImage'                 => true,
				'showTitle'                 => true,
				'showPrefix'                => true,
				'showPrice'                 => true,
				'showSuffix'                => true,
				'showSubprice'              => true,
				'showFeatures'              => true,
				'showButton'                => true,
				'buttonTarget'              => false,
				'titleSpace'                => '',
				'priceSpace'                => '',
				'subpriceSpace'             => 0,
				'buttonSpace'               => '',
				'featuresSpace'             => '',
				'titleSpaceMobile'          => '',
				'priceSpaceMobile'          => '',
				'subpriceSpaceMobile'       => '',
				'buttonSpaceMobile'         => '',
				'featuresSpaceMobile'       => '',
				'titleSpaceTablet'          => '',
				'priceSpaceTablet'          => '',
				'subpriceSpaceTablet'       => '',
				'buttonSpaceTablet'         => '',
				'featuresSpaceTablet'       => '',
				'blockAlign'                => 'center',
				'imageWidth'                => '',
				'imageWidthTablet'          => '',
				'imageWidthMobile'          => '',
				'imageSize'                 => 'full',
				'imageShape'                => '',
				'ctaHoverBorderColor'       => '#333',
				'ctaHpaddingTablet'         => '',
				'ctaHpaddingMobile'         => '',
				'ctaVpaddingTablet'         => '',
				'ctaVpaddingMobile'         => '',
				'titleFontSizeMobile'       => '',
				'titleFontSizeTablet'       => '',
				'prefixFontSizeMobile'      => '',
				'prefixFontSizeTablet'      => '',
				'amountFontSizeMobile'      => '',
				'amountFontSizeTablet'      => '',
				'suffixFontSizeMobile'      => '',
				'suffixFontSizeTablet'      => '',
				'subpriceFontSizeMobile'    => '',
				'subpriceFontSizeTablet'    => '',
				'featuresFontSizeMobile'    => '',
				'featuresFontSizeTablet'    => '',
				'ctaFontSizeMobile'         => '',
				'ctaFontSizeTablet'         => '',
			);
		}

		/**
		 * Get Defaults for section block
		 *
		 * @return array
		 */
		public static function get_responsive_block_section_default_attributes() {
			return array(
				'block_id'                 => '',
				'blockId'                  => '',
				'align'                    => '',
				'width'                    => 900,
				'innerWidth'               => 1140,
				'innerWidthTablet'         => 1140,
				'innerWidthMobile'         => 1140,
				'innerWidthType'           => 'px',
				'themeWidth'               => false,
				'blockTopPadding'          => 10,
				'blockTopPaddingMobile'    => '',
				'blockTopPaddingTablet'    => '',
				'blockBottomPadding'       => 10,
				'blockBottomPaddingMobile' => '',
				'blockBottomPaddingTablet' => '',
				'blockLeftPadding'         => 10,
				'blockLeftPaddingMobile'   => '',
				'blockLeftPaddingTablet'   => '',
				'blockRightPadding'        => 10,
				'blockRightPaddingMobile'  => '',
				'blockRightPaddingTablet'  => '',
				'blockTopMargin'           => 0,
				'blockBottomMargin'        => 0,
				'blockLeftMargin'          => 0,
				'blockRightMargin'         => 0,
				'blockTopMarginTablet'     => '',
				'blockBottomMarginTablet'  => '',
				'blockLeftMarginTablet'    => '',
				'blockRightMarginTablet'   => '',
				'blockTopMarginMobile'     => '',
				'blockBottomMarginMobile'  => '',
				'blockLeftMarginMobile'    => '',
				'blockRightMarginMobile'   => '',
				'blockBorderStyle'         => 'none',
				'blockBorderWidth'         => 1,
				'blockBorderRadius'        => 0,
				'blockBorderColor'         => '#000',
				'sectionTag'               => 'section',
				'opacity'                  => 20,
				'colorLocation1'           => 0,
				'colorLocation2'           => 100,
				'gradientDirection'        => 90,
				'backgroundImage'          => '',
				'backgroundPosition'       => 'center-center',
				'backgroundPositionTablet' => 'center center',
				'backgroundPositionMobile' => 'center center',
				'backgroundSize'           => 'cover',
				'backgroundSizeTablet'     => 'cover',
				'backgroundSizeMobile'     => 'cover',
				'backgroundRepeat'         => 'no-repeat',
				'backgroundAttachment'     => 'scroll',
				'backgroundImageColor'     => '#fff',
				'overlayType'              => 'color',
				'gradientOverlayColor1'    => '#fff',
				'gradientOverlayColor2'    => '#fff',
				'gradientOverlayType'      => 'linear',
				'gradientOverlayLocation1' => 0,
				'gradientOverlayLocation2' => 100,
				'gradientOverlayAngle'     => 0,
				'gradientOverlayPosition'  => 'center center',
				'backgroundVideo'          => '',
				'backgroundColor'          => '#fff',
				'backgroundColor1'         => '#fff',
				'backgroundColor2'         => '#fff',
				'backgroundType'           => 'none',
				'boxShadowColor'           => '#fff',
				'boxShadowHOffset'         => 0,
				'boxShadowVOffset'         => 0,
				'boxShadowBlur'            => 0,
				'boxShadowSpread'          => 0,
				'boxShadowPosition'        => 'outset',
				'z_index'                  => 1,
				'topMargin'                => 999, // For compatibility with v1.3.2.
				'bottomMargin'             => 999, // For compatibility with v1.3.2.
				'leftMargin'               => 999, // For compatibility with v1.3.2.
				'rightMargin'              => 999, // For compatibility with v1.3.2.
				'topPadding'               => 999, // For compatibility with v1.3.2.
				'bottomPadding'            => 999, // For compatibility with v1.3.2.
				'leftPadding'              => 999, // For compatibility with v1.3.2.
				'rightPadding'             => 999, // For compatibility with v1.3.2.
				'topMarginMobile'          => 999, // For compatibility with v1.3.2.
				'bottomMarginMobile'       => 999, // For compatibility with v1.3.2.
				'leftMarginMobile'         => 999, // For compatibility with v1.3.2.
				'rightMarginMobile'        => 999, // For compatibility with v1.3.2.
				'topPaddingMobile'         => 999, // For compatibility with v1.3.2.
				'bottomPaddingMobile'      => 999, // For compatibility with v1.3.2.
				'leftPaddingMobile'        => 999, // For compatibility with v1.3.2.
				'rightPaddingMobile'       => 999, // For compatibility with v1.3.2.
				'topMarginTablet'          => 999, // For compatibility with v1.3.2.
				'bottomMarginTablet'       => 999, // For compatibility with v1.3.2.
				'leftMarginTablet'         => 999, // For compatibility with v1.3.2.
				'rightMarginTablet'        => 999, // For compatibility with v1.3.2.
				'topPaddingTablet'         => 999, // For compatibility with v1.3.2.
				'bottomPaddingTablet'      => 999, // For compatibility with v1.3.2.
				'leftPaddingTablet'        => 999, // For compatibility with v1.3.2.
				'rightPaddingTablet'       => 999, // For compatibility with v1.3.2.
			);
		}

		/**
		 * Get Shape divider Block CSS
		 *
		 * @param array  $attr The block attributes.
		 * @param string $id The selector ID.
		 * @return array Styles.
		 */
		public static function get_responsive_block_shape_divider_css( $attr, $id ) {
			$defaults = self::get_responsive_block_shape_divider_default_attributes();
			$attr     = array_merge( $defaults, (array) $attr );

			$mobile_selectors = array();
			$tablet_selectors = array();

			$updated_background_color = null;
			if ( 'color' === $attr['backgroundType'] && null !== $attr['backgroundColor'] ) {
				$updated_background_color = $attr['backgroundColor'];
			}

			$updated_background_image = null;
			if ( 'gradient' === $attr['backgroundType'] ) {
				$updated_background_image = self::generate_background_image_effect( $attr['backgroundColor1'], $attr['backgroundColor2'], $attr['gradientDirection'], $attr['colorLocation1'], $attr['colorLocation2'] );
			}

			$selectors = array(
				'' => array(
					'background-color' => $updated_background_color,
					'background-image' => $updated_background_image,
					'color'            => $attr['customColor'],
				),

				' .wp-block-responsive-block-editor-addons-shape-divider__svg-wrapper' => array(
					'min-height' => self::get_css_value( $attr['shapeHeight'], 'px' ),
				),

				' .wp-block-responsive-block-editor-addons-shape-divider__alt-wrapper' => array(
					'min-height' => self::get_css_value( $attr['backgroundHeight'], 'px' ),
				),
			);
			$mobile_selectors = array(
				' .wp-block-responsive-block-editor-addons-shape-divider__svg-wrapper' => array(
					'min-height' => $attr['shapeHeightMobile'] ? self::get_css_value( $attr['shapeHeightMobile'], 'px' ) : self::get_css_value( $attr['shapeHeight'], 'px' ),
				),
				' .wp-block-responsive-block-editor-addons-shape-divider__alt-wrapper' => array(
					'min-height' => $attr['backgroundHeightMobile'] ? self::get_css_value( $attr['backgroundHeightMobile'], 'px' ) : self::get_css_value( $attr['backgroundHeight'], 'px' ),
				),
			);

			$tablet_selectors = array(
				' .wp-block-responsive-block-editor-addons-shape-divider__svg-wrapper' => array(
					'min-height' => $attr['shapeHeightTablet'] ? self::get_css_value( $attr['shapeHeightTablet'], 'px' ) : self::get_css_value( $attr['shapeHeight'], 'px' ),
				),
				' .wp-block-responsive-block-editor-addons-shape-divider__alt-wrapper' => array(
					'min-height' => $attr['backgroundHeightTablet'] ? self::get_css_value( $attr['backgroundHeightTablet'], 'px' ) : self::get_css_value( $attr['backgroundHeight'], 'px' ),
				),
			);

			$combined_selectors = array(
				'desktop' => $selectors,
				'tablet'  => $tablet_selectors,
				'mobile'  => $mobile_selectors,
			);

			$id  = '.responsive-block-editor-addons-block-shape-divider.block-' . $id;
			$css = Responsive_Block_Editor_Addons_Frontend_Styles_Helper::responsive_block_editor_addons_generate_all_css( $combined_selectors, $id );

			return $css;
		}

		/**
		 * Get Defaults for shape divider block
		 *
		 * @return array
		 */
		public static function get_responsive_block_shape_divider_default_attributes() {
			return array(
				'block_id'               => '',
				'align'                  => 'full',
				'height'                 => 100,
				'heightTablet'           => '',
				'heightMobile'           => '',
				'shapeHeight'            => 100,
				'shapeHeightTablet'      => 0,
				'shapeHeightMobile'      => 0,
				'backgroundHeight'       => 50,
				'backgroundHeightTablet' => '',
				'backgroundHeightMobile' => '',
				'syncHeight'             => true,
				'syncHeightAlt'          => true,
				'verticalFlip'           => false,
				'horizontalFlip'         => false,
				'color'                  => '',
				'customColor'            => '',
				'backgroundColor'        => '#fff',
				'customBackgroundColor'  => '#fff',
				'noBottomMargin'         => true,
				'noTopMargin'            => true,
				'justAdded'              => true,
				'colorLocation1'         => 0,
				'colorLocation2'         => 100,
				'gradientDirection'      => 90,
				'backgroundColor1'       => '#fff',
				'backgroundColor2'       => '#fff',
				'backgroundType'         => 'none',
			);
		}

		/**
		 * Get Spacer Block CSS
		 *
		 * @param array  $attr The block attributes.
		 * @param string $id The selector ID.
		 * @return array Styles.
		 */
		public static function get_responsive_block_spacer_css( $attr, $id ) {
			$defaults = self::get_responsive_block_spacer_default_attributes();
			$attr     = array_merge( $defaults, (array) $attr );

			$mobile_selectors = array();
			$tablet_selectors = array();

			$selectors = array(
				'.responsive-block-editor-addons-spacer' => array(
					'height' => self::get_css_value( $attr['height'], 'px' ),
					'margin' => '0px',
				),
			);

			$mobile_selectors = array(
				'.responsive-block-editor-addons-spacer' => array(
					'height' => self::get_css_value( $attr['heightMobile'], 'px' ),
				),
			);

			$tablet_selectors = array(
				'.responsive-block-editor-addons-spacer' => array(
					'height' => self::get_css_value( $attr['heightTablet'], 'px' ),
				),
			);

			$combined_selectors = array(
				'desktop' => $selectors,
				'tablet'  => $tablet_selectors,
				'mobile'  => $mobile_selectors,
			);

			$id  = '.responsive-block-editor-addons-block-spacer.block-' . $id;
			$css = Responsive_Block_Editor_Addons_Frontend_Styles_Helper::responsive_block_editor_addons_generate_all_css( $combined_selectors, $id );
			return $css;
		}

		/**
		 * Get Defaults for spacer block
		 *
		 * @return array
		 */
		public static function get_responsive_block_spacer_default_attributes() {
			return array(
				'block_id'     => '',
				'height'       => 100,
				'heightTablet' => '',
				'heightMobile' => '',
			);
		}

		/**
		 * Get Team Block CSS
		 *
		 * @param array  $attr The block attributes.
		 * @param string $id The selector ID.
		 * @return array Styles.
		 */
		public static function get_responsive_block_team_css( $attr, $id ) {
			$defaults = self::get_responsive_block_team_default_attributes();
			$attr     = array_merge( $defaults, (array) $attr );

			$mobile_selectors = array();
			$tablet_selectors = array();

			$bgopacity = $attr['opacity'] / 100;

			$tempsecondary_background_color = $attr['bgGradient'] ? $attr['backgroundColor2'] : $attr['backgroundColor'];

			$bggradient = 'linear-gradient(' .
				$attr['gradientDirection'] .
				'deg,' .
				self::hex_to_rgb( $attr['backgroundColor'] ? $attr['backgroundColor'] : '#ffffff', $bgopacity ? $bgopacity : 0 ) .
				$attr['colorLocation1'] .
				'% ,' .
				self::hex_to_rgb( $tempsecondary_background_color ? $tempsecondary_background_color : '#ffffff', $bgopacity ? $bgopacity : 0 ) .
				$attr['colorLocation2'] .
				'% )';

			if ( $attr['backgroundImage'] ) {
				$bggradient = 'linear-gradient(' .
					$attr['gradientDirection'] .
					'deg,' .
					self::hex_to_rgb( $attr['backgroundColor'] ? $attr['backgroundColor'] : '#ffffff', $bgopacity ? $bgopacity : 0 ) .
					$attr['colorLocation1'] .
					'% ,' .
					self::hex_to_rgb( $tempsecondary_background_color ? $tempsecondary_background_color : '#ffffff', $bgopacity ? $bgopacity : 0 ) .
					$attr['colorLocation2'] .
					'% ),url(' .
					$attr['backgroundImage'] .
					')';
			}

			$box_shadow_position_css = $attr['boxShadowPosition'];

			if ( 'outset' === $attr['boxShadowPosition'] ) {
				$box_shadow_position_css = '';
			}

			$gutter_margin = '';
			if ( $attr['count'] > 1 ) {
				if ( 'small' === $attr['gutter'] ) {
					$gutter_margin = '20px';
				} elseif ( 'medium' === $attr['gutter'] ) {
					$gutter_margin = '30px';
				} elseif ( 'large' === $attr['gutter'] ) {
					$gutter_margin = '40px';
				} elseif ( 'huge' === $attr['gutter'] ) {
					$gutter_margin = '50px';
				} else {
					$gutter_margin = '';
				}
			}

			$selectors = array(
				' .responsive-block-editor-addons-team-avatar-wrapper' => array(
					'text-align' => $attr['alignment'],
					'text-align' => '-webkit-' . $attr['alignment'],
				),

				' .responsive-block-editor-addons-team-avatar' => array(
					'width'         => self::get_css_value( $attr['imageWidth'], 'px' ),
					'margin-top'    => self::get_css_value( $attr['imageMarginTop'], 'px' ),
					'margin-bottom' => self::get_css_value( $attr['imageMarginBottom'], 'px' ),
					'text-align'    => 'justify',
				),

				' .responsive-block-editor-addons-team-name'   => array(
					'color'         => $attr['titleColor'],
					'font-family'   => $attr['titleFontFamily'],
					'font-size'     => self::get_css_value( $attr['titleFontSize'], 'px' ),
					'font-weight'   => $attr['titleFontWeight'],
					'line-height'   => $attr['titleLineHeight'],
					'margin-bottom' => self::get_css_value( $attr['titleSpacing'], 'px' ),
				),

				' .responsive-block-editor-addons-team-designation' => array(
					'color'         => $attr['designationColor'],
					'font-family'   => $attr['designationFontFamily'],
					'font-size'     => self::get_css_value( $attr['designationFontSize'], 'px' ),
					'font-weight'   => $attr['designationFontWeight'],
					'line-height'   => $attr['designationLineHeight'],
					'margin-bottom' => self::get_css_value( $attr['designationSpacing'], 'px' ),
				),

				' .responsive-block-editor-addons-team-description' => array(
					'color'         => $attr['descriptionColor'],
					'font-family'   => $attr['descriptionFontFamily'],
					'font-size'     => self::get_css_value( $attr['descriptionFontSize'], 'px' ),
					'font-weight'   => $attr['descriptionFontWeight'],
					'line-height'   => $attr['descriptionLineHeight'],
					'margin-bottom' => self::get_css_value( $attr['descriptionSpacing'], 'px' ),
				),

				' .responsive-block-editor-addons-team-social-icons .dashicons' => array(
					'display'			=> 'inline-block',
				),

				' .responsive-block-editor-addons-team-social-icons .dashicons.dashicons-twitter' => array(
					'color'           => $attr['socialIconColor'],
					'font-size'       => self::get_css_value( $attr['iconSize'], 'px' ),
					'text-decoration' => 'none',
					'height'          => self::get_css_value( $attr['iconSize'], 'px' ),
					'width'           => self::get_css_value( $attr['iconSize'], 'px' ),
				),

				' .responsive-block-editor-addons-team-social-icons .dashicons.dashicons-facebook' => array(
					'color'           => $attr['socialIconColor'],
					'font-size'       => self::get_css_value( $attr['iconSize'], 'px' ),
					'text-decoration' => 'none',
					'height'          => self::get_css_value( $attr['iconSize'], 'px' ),
					'width'           => self::get_css_value( $attr['iconSize'], 'px' ),
				),

				' .responsive-block-editor-addons-team-social-icons .dashicons.dashicons-linkedin' => array(
					'color'           => $attr['socialIconColor'],
					'font-size'       => self::get_css_value( $attr['iconSize'], 'px' ),
					'text-decoration' => 'none',
					'height'          => self::get_css_value( $attr['iconSize'], 'px' ),
					'width'           => self::get_css_value( $attr['iconSize'], 'px' ),
				),

				' .responsive-block-editor-addons-team-social-icons .dashicons.dashicons-instagram' => array(
					'color'           => $attr['socialIconColor'],
					'font-size'       => self::get_css_value( $attr['iconSize'], 'px' ),
					'text-decoration' => 'none',
					'height'          => self::get_css_value( $attr['iconSize'], 'px' ),
					'width'           => self::get_css_value( $attr['iconSize'], 'px' ),
				),

				' .responsive-block-editor-addons-team-social-icons .dashicons.dashicons-email' => array(
					'color'           => $attr['socialIconColor'],
					'font-size'       => self::get_css_value( $attr['iconSize'], 'px' ),
					'text-decoration' => 'none',
					'height'          => self::get_css_value( $attr['iconSize'], 'px' ),
					'width'           => self::get_css_value( $attr['iconSize'], 'px' ),
				),

				' .responsive-block-editor-addons-team-social-icons .dashicons.dashicons-youtube' => array(
					'color'           => $attr['socialIconColor'],
					'font-size'       => self::get_css_value( $attr['iconSize'], 'px' ),
					'text-decoration' => 'none',
					'height'          => self::get_css_value( $attr['iconSize'], 'px' ),
					'width'           => self::get_css_value( $attr['iconSize'], 'px' ),
				),

				' .responsive-block-editor-addons-team-social-icons .dashicons.dashicons-pinterest' => array(
					'color'           => $attr['socialIconColor'],
					'font-size'       => self::get_css_value( $attr['iconSize'], 'px' ),
					'text-decoration' => 'none',
					'height'          => self::get_css_value( $attr['iconSize'], 'px' ),
					'width'           => self::get_css_value( $attr['iconSize'], 'px' ),
				),

				' .responsive-block-editor-addons-team-social-icons a' => array(
					'line-height'      => '1',
					'margin-left'      => self::get_css_value( $attr['socialIconSpacing'], 'px' ),
					'margin-right'     => self::get_css_value( $attr['socialIconSpacing'], 'px' ),
					'padding'          => self::get_css_value( $attr['iconBackgroundSize'], 'px' ),
					'background-color' => $attr['socialIconBackgroundColor'],
					'border-width'     => self::get_css_value( $attr['iconBorderSize'], 'px' ),
					'border-style'     => 'solid',
					'border-color'     => $attr['socialIconBorderColor'],
					'border-radius'    => self::get_css_value( $attr['iconBorderRadius'], '%' ),
				),

				' .responsive-block-editor-addons-team-social-icons li:hover a' => array(
					'background-color' => $attr['socialIconBackgroundHoverColor'],
					'border-width'     => self::get_css_value( $attr['iconBorderSize'], 'px' ),
					'border-style'     => 'solid',
					'border-color'     => $attr['socialIconBorderHoverColor'],
				),

				' .responsive-block-editor-addons-team-social-icons li:hover .dashicons' => array(
					'color' => $attr['socialIconHoverColor'] . '!important',
				),

				' .wp-block-responsive-block-editor-addons-team' => array(
					'background-image'      => $bggradient,
					'background-size'       => $attr['backgroundImageSize'],
					'background-repeat'     => $attr['backgroundImageRepeat'],
					'background-position'   => $attr['backgroundImagePosition'],
					'background-attachment' => $attr['backgroundAttachment'],
					'border-width'          => self::get_css_value( $attr['borderWidth'], 'px' ),
					'border-color'          => $attr['borderColor'],
					'border-radius'         => self::get_css_value( $attr['borderRadius'], 'px' ),
					'padding'               => self::get_css_value( $attr['padding'], 'px' ),
					'text-align'            => $attr['alignment'],
					'box-shadow'            =>
						self::get_css_value( $attr['boxShadowHOffset'], 'px' ) .
						' ' .
						self::get_css_value( $attr['boxShadowVOffset'], 'px' ) .
						' ' .
						self::get_css_value( $attr['boxShadowBlur'], 'px' ) .
						' ' .
						self::get_css_value( $attr['boxShadowSpread'], 'px' ) .
						' ' .
						$attr['boxShadowColor'] .
						' ' .
						$box_shadow_position_css,
				),
			);

			$mobile_selectors = array(
				' .wp-block-responsive-block-editor-addons-team'    => array(
					'margin-bottom' => $gutter_margin,
				),
				' .responsive-block-editor-addons-team-avatar' => array(
					'width'         => self::get_css_value( $attr['imageWidthMobile'], 'px' ),
					'max-width'     => self::get_css_value( $attr['imageWidthMobile'], 'px' ),
					'margin-top'    => self::get_css_value( $attr['imageMarginTopMobile'], 'px' ),
					'margin-bottom' => self::get_css_value( $attr['imageMarginBottomMobile'], 'px' ),
				),
				'.has-columns.has-responsive-columns.responsive-team-block-columns__stack-mobile > *:not(.block-editor-inner-blocks)' => array(
					'max-width' => '100%',
					'min-width' => '100%',
				),
				' .responsive-block-editor-addons-team-name'   => array(
					'margin-bottom' => self::get_css_value( $attr['titleSpacingMobile'], 'px' ),
					'font-size'     => self::get_css_value( $attr['titleFontSizeMobile'], 'px' ),
				),

				' .responsive-block-editor-addons-team-designation' => array(
					'margin-bottom' => self::get_css_value( $attr['designationSpacingMobile'], 'px' ),
					'font-size'     => self::get_css_value( $attr['designationFontSizeMobile'], 'px' ),
				),

				' .responsive-block-editor-addons-team-description' => array(
					'margin-bottom' => self::get_css_value( $attr['descriptionSpacingMobile'], 'px' ),
					'font-size'     => self::get_css_value( $attr['descriptionFontSizeMobile'], 'px' ),
				),

				' .responsive-block-editor-addons-team-social-icons a' => array(
					'margin-left'  => self::get_css_value( $attr['socialIconSpacingMobile'], 'px' ),
					'margin-right' => self::get_css_value( $attr['socialIconSpacingMobile'], 'px' ),
				),
			);

			$tablet_selectors = array(
				' .wp-block-responsive-block-editor-addons-team'    => array(
					'margin-bottom' => $gutter_margin,
				),
				' .responsive-block-editor-addons-team-avatar' => array(
					'width'         => self::get_css_value( $attr['imageWidthTablet'], 'px' ),
					'max-width'     => self::get_css_value( $attr['imageWidthTablet'], 'px' ),
					'margin-top'    => self::get_css_value( $attr['imageMarginTopTablet'], 'px' ),
					'margin-bottom' => self::get_css_value( $attr['imageMarginBottomTablet'], 'px' ),
				),
				'.has-columns.has-responsive-columns.responsive-team-block-columns__stack-tablet > *:not(.block-editor-inner-blocks)' => array(
					'max-width' => '100%',
					'min-width' => '100%',
				),

				' .responsive-block-editor-addons-team-name'   => array(
					'margin-bottom' => self::get_css_value( $attr['titleSpacingTablet'], 'px' ),
					'font-size'     => self::get_css_value( $attr['titleFontSizeTablet'], 'px' ),
				),

				' .responsive-block-editor-addons-team-designation' => array(
					'margin-bottom' => self::get_css_value( $attr['designationSpacingTablet'], 'px' ),
					'font-size'     => self::get_css_value( $attr['designationFontSizeTablet'], 'px' ),
				),

				' .responsive-block-editor-addons-team-description' => array(
					'margin-bottom' => self::get_css_value( $attr['descriptionSpacingTablet'], 'px' ),
					'font-size'     => self::get_css_value( $attr['descriptionFontSizeTablet'], 'px' ),
				),

				' .responsive-block-editor-addons-team-social-icons a' => array(
					'margin-left' => self::get_css_value( $attr['socialIconSpacingTablet'], 'px' ),
				),
			);

			$combined_selectors = array(
				'desktop' => $selectors,
				'tablet'  => $tablet_selectors,
				'mobile'  => $mobile_selectors,
			);

			$id  = '.wp-block-responsive-block-editor-addons-team-wrapper.block-' . $id;
			$css = Responsive_Block_Editor_Addons_Frontend_Styles_Helper::responsive_block_editor_addons_generate_all_css( $combined_selectors, $id );

			return $css;
		}

		/**
		 * Get Defaults for team block
		 *
		 * @return array
		 */
		public static function get_responsive_block_team_default_attributes() {
			return array(
				'block_id'                       => '',
				'teamBlock'                      => 'teamBlock',
				'teamImgURL'                     => '',
				'counterId'                      => '1',
				'count'                          => 2,
				'gutter'                         => 'medium',
				'designationColor'               => '',
				'descriptionColor'               => '',
				'socialIconColor'                => '#0066CC',
				'titleColor'                     => '',
				'titleFontWeight'                => '',
				'designationFontWeight'          => '',
				'descriptionFontWeight'          => '',
				'titleLineHeight'                => '',
				'designationLineHeight'          => '',
				'descriptionLineHeight'          => '',
				'imageSize'                      => 'full',
				'titleFontFamily'                => '',
				'designationFontFamily'          => '',
				'descriptionFontFamily'          => '',
				'titleFontSize'                  => 23,
				'designationFontSize'            => 15,
				'descriptionFontSize'            => 15,
				'socialIconFontSize'             => 23,
				'imageMarginTop'                 => '',
				'imageMarginBottom'              => '',
				'imageMarginTopMobile'           => '',
				'imageMarginBottomMobile'        => '',
				'imageMarginTopTablet'           => '',
				'imageMarginBottomTablet'        => '',
				'iconSize'                       => '',
				'titleSpacing'                   => '',
				'designationSpacing'             => '',
				'descriptionSpacing'             => '',
				'socialIconSpacing'              => '',
				'titleSpacingMobile'             => '',
				'designationSpacingMobile'       => '',
				'descriptionSpacingMobile'       => '',
				'socialIconSpacingMobile'        => '',
				'titleSpacingTablet'             => '',
				'designationSpacingTablet'       => '',
				'descriptionSpacingTablet'       => '',
				'socialIconSpacingTablet'        => '',
				'imageStyle'                     => '0%',
				'imageWidth'                     => 120,
				'imageWidthMobile'               => 120,
				'imageWidthTablet'               => 120,
				'backgroundColor'                => '',
				'borderColor'                    => '',
				'borderWidth'                    => 2,
				'borderRadius'                   => 2,
				'padding'                        => 2,
				'alignment'                      => 'center',
				'imageShape'                     => '',
				'boxShadowColor'                 => '',
				'boxShadowHOffset'               => 0,
				'boxShadowVOffset'               => 0,
				'boxShadowBlur'                  => 0,
				'boxShadowSpread'                => 0,
				'boxShadowPosition'              => 'outset',
				'opacity'                        => 50,
				'backgroundColor2'               => '',
				'gradientDirection'              => 100,
				'colorLocation1'                 => 0,
				'colorLocation2'                 => 100,
				'bgGradient'                     => '',
				'backgroundImage'                => '',
				'backgroundImagePosition'        => 'center center',
				'backgroundImageRepeat'          => 'no-repeat',
				'backgroundImageSize'            => 'cover',
				'backgroundAttachment'           => 'scroll',
				'showImage'                      => true,
				'showName'                       => true,
				'showDesignation'                => true,
				'showDescription'                => true,
				'showSocialIcons'                => true,
				'facebook'                       => '',
				'twitter'                        => '',
				'linkedin'                       => '',
				'instagram'                      => '',
				'email'                          => '',
				'youtube'                        => '',
				'pinterest'                      => '',
				'stack'                          => 'mobile',
				'titleFontSizeMobile'            => '',
				'titleFontSizeTablet'            => '',
				'designationFontSizeMobile'      => '',
				'designationFontSizeTablet'      => '',
				'descriptionFontSizeMobile'      => '',
				'descriptionFontSizeTablet'      => '',
				'socialIconBorderColor'          => '#fff',
				'socialIconHoverColor'           => '',
				'socialIconBackgroundHoverColor' => '',
				'socialIconBackgroundColor'      => '',
				'socialIconBorderHoverColor'     => '#fff',
				'iconBackgroundSize'             => '',
				'iconBorderSize'                 => '',
				'iconBorderRadius'               => 0,
			);
		}

		/**
		 * Get testimonial Block CSS
		 *
		 * @param array  $attr The block attributes.
		 * @param string $id The selector ID.
		 * @return array Styles.
		 */
		public static function get_responsive_block_testimonial_css( $attr, $id ) {
			$defaults = self::get_responsive_block_testimonial_default_attributes();
			$attr     = array_merge( $defaults, (array) $attr );

			$mobile_selectors = array();
			$tablet_selectors = array();

			$box_shadow_position_css      = $attr['boxShadowPosition'];
			$hoverbox_shadow_position_css = $attr['hoverboxShadowPosition'];

			$bgimage                        = $attr['backgroundImage'] ? $attr['backgroundImage'] : '';
			$tempsecondary_background_color = $attr['bgGradient']
			? ( 'empty' !== $attr['secondaryBackgroundColor'] && '' === $attr['backgroundColor2'] ? $attr['secondaryBackgroundColor'] : $attr['backgroundColor2'] ) // For compatibility with v1.3.2.
			: $attr['testimonialBackgroundColor'];
			$bggradient                     =
			'linear-gradient(' .
			$attr['gradientDirection'] .
			'deg,' .
			self::hex_to_rgb( $attr['testimonialBackgroundColor'], $attr['opacity'] ) .
			$attr['colorLocation1'] .
			'% ,' .
			self::hex_to_rgb( $tempsecondary_background_color, $attr['opacity'] ) .
			$attr['colorLocation2'] .
			'% ),url(' .
			$bgimage .
			')';

			if ( 'outset' === $attr['boxShadowPosition'] ) {
				$box_shadow_position_css = '';
			}

			if ( 'outset' === $attr['hoverboxShadowPosition'] ) {
				$hoverbox_shadow_position_css = '';
			}

			$selectors = array(
				' .wp-block-responsive-block-editor-addons-testimonial:last-child' => array(
					'margin-bottom' => '0 !important',
				),
				' .responsive-block-editor-addons-testimonial-text' => array(
					'text-align'     => $attr['testimonialAlignment'],
					'font-family'    => $attr['contentFontFamily'],
					'font-size'      => self::get_css_value( $attr['contentFontSize'], 'px' ),
					'line-height'    => $attr['contentLineHeight'],
					'font-weight'    => $attr['contentFontWeight'],
					'text-transform' => $attr['contentTextTransform'],
					'margin-bottom'  => self::get_css_value( $attr['contentSpacing'], 'px' ),
					'color'          => $attr['testimonialTextColor'],
				),
				' .responsive-block-editor-addons-testimonial-info' => array(
					'margin-bottom' => self::get_css_value( $attr['titleSpacing'], 'px' ),
				),
				' .responsive-block-editor-addons-testimonial-info .responsive-block-editor-addons-testimonial-inner-block .responsive-block-editor-addons-testimonial-avatar-wrap' => array(
					'padding-right' => self::get_css_value( $attr['imageSpacing'], 'px' ),
				),
				' .responsive-block-editor-addons-testimonial-info .responsive-block-editor-addons-testimonial-inner-block .responsive-block-editor-addons-testimonial-avatar-wrap .responsive-block-editor-addons-testimonial-image-wrap' => array(
					'height' => self::get_css_value( $attr['imageWidth'], 'px' ),
					'width'  => self::get_css_value( $attr['imageWidth'], 'px' ),
				),
				' .responsive-block-editor-addons-testimonial-avatar' => array(
					'height' => self::get_css_value( $attr['imageWidth'], 'px' ),
					'width'  => self::get_css_value( $attr['imageWidth'], 'px' ),
				),
				' .responsive-block-editor-addons-testimonial-details .responsive-block-editor-addons-testimonial-name' => array(
					'color'          => $attr['testimonialNameColor'],
					'font-family'    => $attr['nameFontFamily'],
					'font-size'      => self::get_css_value( $attr['nameFontSize'], 'px' ),
					'line-height'    => $attr['nameLineHeight'],
					'font-weight'    => $attr['nameFontWeight'],
					'text-transform' => $attr['nameTextTransform'],
					'margin-bottom'  => self::get_css_value( $attr['nameSpacing'], 'px' ),
				),
				' .responsive-block-editor-addons-testimonial-details .responsive-block-editor-addons-testimonial-title' => array(
					'color'          => $attr['testimonialTitleColor'],
					'font-family'    => $attr['titleFontFamily'],
					'font-size'      => self::get_css_value( $attr['titleFontSize'], 'px' ),
					'line-height'    => $attr['titleLineHeight'],
					'font-weight'    => $attr['titleFontWeight'],
					'text-transform' => $attr['titleTextTransform'],
				),
				' .testimonial-box.responsive-block-editor-addons-block-testimonial' => array(
					'box-shadow' =>
						self::get_css_value( $attr['boxShadowHOffset'], 'px' ) .
						' ' .
						self::get_css_value( $attr['boxShadowVOffset'], 'px' ) .
						' ' .
						self::get_css_value( $attr['boxShadowBlur'], 'px' ) .
						' ' .
						self::get_css_value( $attr['boxShadowSpread'], 'px' ) .
						' ' .
						$attr['boxShadowColor'] .
						' ' .
						$box_shadow_position_css,
					'padding'    => self::get_css_value( $attr['padding'], 'px' ),
				),
				' .responsive-block-editor-addons-block-testimonial:hover' => array(
					'box-shadow' =>
						self::get_css_value( $attr['hoverboxShadowHOffset'], 'px' ) .
						' ' .
						self::get_css_value( $attr['hoverboxShadowVOffset'], 'px' ) .
						' ' .
						self::get_css_value( $attr['hoverboxShadowBlur'], 'px' ) .
						' ' .
						self::get_css_value( $attr['hoverboxShadowSpread'], 'px' ) .
						' ' .
						$attr['hoverboxShadowColor'] .
						' ' .
						$hoverbox_shadow_position_css,
				),
				' .responsive-block-editor-addons-block-testimonial' => array(
					'background-image'    => $bggradient,
					'background-size'     => $attr['backgroundSize'],
					'background-repeat'   => $attr['backgroundRepeat'],
					'background-position' => $attr['backgroundPosition'],
					'color'               => $attr['testimonialTextColor'],
					'border-style'        => 'empty' !== $attr['borderStyle'] && 'none' === $attr['blockBorderStyle'] ? $attr['borderStyle'] : $attr['blockBorderStyle'], // For compatibility with v1.3.2.
					'border-width'        => 999 !== $attr['borderWidth'] && 1 === $attr['blockBorderWidth'] ? self::get_css_value( $attr['borderWidth'], 'px' ) : self::get_css_value( $attr['blockBorderWidth'], 'px' ), // For compatibility with v1.3.2.
					'border-radius'       => 999 !== $attr['borderRadius'] && 2 === $attr['blockBorderRadius'] ? self::get_css_value( $attr['borderRadius'], 'px' ) : self::get_css_value( $attr['blockBorderRadius'], 'px' ), // For compatibility with v1.3.2.
					'border-color'        => 'empty' !== $attr['borderColor'] && '' === $attr['blockBorderColor'] ? $attr['borderColor'] : $attr['blockBorderColor'], // For compatibility with v1.3.2.
				),

			);
			$mobile_selectors = array(
				' .testimonial-box.responsive-block-editor-addons-block-testimonial' => array(
					'padding' => self::get_css_value( $attr['paddingMobile'], 'px' ),
				),
				' .wp-block-responsive-block-editor-addons-testimonial:last-child' => array(
					'margin-bottom' => '0 !important',
				),
				' .responsive-block-editor-addons-testimonial-text' => array(
					'font-size'     => self::get_css_value( $attr['contentFontSizeMobile'], 'px' ),
					'margin-bottom' => self::get_css_value( $attr['contentSpacingMobile'], 'px' ),
				),
				' .responsive-block-editor-addons-testimonial-details .responsive-block-editor-addons-testimonial-name' => array(
					'font-size'     => self::get_css_value( $attr['nameFontSizeMobile'], 'px' ),
					'margin-bottom' => self::get_css_value( $attr['nameSpacingMobile'], 'px' ),
				),
				' .responsive-block-editor-addons-testimonial-details .responsive-block-editor-addons-testimonial-title' => array(
					'font-size' => self::get_css_value( $attr['titleFontSizeMobile'], 'px' ),
				),
				' .responsive-block-editor-addons-testimonial-info' => array(
					'margin-bottom' => self::get_css_value( $attr['titleSpacingMobile'], 'px' ),
				),
				' .responsive-block-editor-addons-testimonial-info .responsive-block-editor-addons-testimonial-inner-block .responsive-block-editor-addons-testimonial-avatar-wrap' => array(
					'padding-right' => self::get_css_value( $attr['imageSpacingMobile'], 'px' ),
				),
				' .responsive-block-editor-addons-testimonial-info .responsive-block-editor-addons-testimonial-inner-block .responsive-block-editor-addons-testimonial-avatar-wrap .responsive-block-editor-addons-testimonial-image-wrap' => array(
					'height' => self::get_css_value( $attr['imageWidthMobile'], 'px' ),
					'width'  => self::get_css_value( $attr['imageWidthMobile'], 'px' ),
				),
				' .responsive-block-editor-addons-testimonial-avatar' => array(
					'height' => self::get_css_value( $attr['imageWidthMobile'], 'px' ),
					'width'  => self::get_css_value( $attr['imageWidthMobile'], 'px' ),
				),
			);

			$tablet_selectors = array(
				' .testimonial-box.responsive-block-editor-addons-block-testimonial' => array(
					'padding' => self::get_css_value( $attr['paddingTablet'], 'px' ),
				),
				' .wp-block-responsive-block-editor-addons-testimonial:last-child' => array(
					'margin-bottom' => self::get_css_value( 0, 'px' ) . ' !important',
				),
				' .responsive-block-editor-addons-testimonial-text' => array(
					'font-size'     => self::get_css_value( $attr['contentFontSizeTablet'], 'px' ),
					'margin-bottom' => self::get_css_value( $attr['contentSpacingTablet'], 'px' ),
				),
				' .responsive-block-editor-addons-testimonial-details .responsive-block-editor-addons-testimonial-name' => array(
					'font-size'     => self::get_css_value( $attr['nameFontSizeTablet'], 'px' ),
					'margin-bottom' => self::get_css_value( $attr['nameSpacingTablet'], 'px' ),
				),
				' .responsive-block-editor-addons-testimonial-details .responsive-block-editor-addons-testimonial-title' => array(
					'font-size' => self::get_css_value( $attr['titleFontSizeTablet'], 'px' ),
				),
				' .responsive-block-editor-addons-testimonial-info' => array(
					'margin-bottom' => self::get_css_value( $attr['titleSpacingTablet'], 'px' ),
				),
				' .responsive-block-editor-addons-testimonial-info .responsive-block-editor-addons-testimonial-inner-block .responsive-block-editor-addons-testimonial-avatar-wrap' => array(
					'padding-right' => self::get_css_value( $attr['imageSpacingTablet'], 'px' ),
				),
				' .responsive-block-editor-addons-testimonial-info .responsive-block-editor-addons-testimonial-inner-block .responsive-block-editor-addons-testimonial-avatar-wrap .responsive-block-editor-addons-testimonial-image-wrap' => array(
					'height' => self::get_css_value( $attr['imageWidthTablet'], 'px' ),
					'width'  => self::get_css_value( $attr['imageWidthTablet'], 'px' ),
				),
				' .responsive-block-editor-addons-testimonial-avatar' => array(
					'height' => self::get_css_value( $attr['imageWidthTablet'], 'px' ),
					'width'  => self::get_css_value( $attr['imageWidthTablet'], 'px' ),
				),
			);

			$combined_selectors = array(
				'desktop' => $selectors,
				'tablet'  => $tablet_selectors,
				'mobile'  => $mobile_selectors,
			);

			$id  = '.responsive-block-editor-addons-block-testimonial.block-' . $id;
			$css = Responsive_Block_Editor_Addons_Frontend_Styles_Helper::responsive_block_editor_addons_generate_all_css( $combined_selectors, $id );
			return $css;
		}

		/**
		 * Get Defaults for testimonial block
		 *
		 * @return array
		 */
		public static function get_responsive_block_testimonial_default_attributes() {
			return array(
				'block_id'                   => '',
				'testimonialBlock'           => '',
				'counterId'                  => '',
				'count'                      => '',
				'gutter'                     => 'medium',
				'testimonialAlignment'       => '',
				'testimonialBackgroundColor' => '#f2f2f2',
				'testimonialTextColor'       => '',
				'testimonialNameColor'       => '',
				'testimonialTitleColor'      => '',
				'titleFontSize'              => '',
				'titleLineHeight'            => '',
				'titleFontWeight'            => '',
				'titleTextTransform'         => '',
				'nameFontSize'               => '',
				'nameLineHeight'             => '',
				'imageWidth'                 => '',
				'imageWidthTablet'           => '',
				'imageWidthMobile'           => '',
				'nameFontFamily'             => '',
				'titleFontFamily'            => '',
				'contentFontFamily'          => '',
				'nameFontWeight'             => '',
				'nameTextTransform'          => '',
				'contentFontSize'            => '',
				'contentLineHeight'          => 1.6,
				'contentFontWeight'          => '',
				'contentTextTransform'       => '',
				'testimonialCiteAlign'       => 'left-aligned',
				'backgroundColor'            => '#f2f2f2',
				'blockBorderStyle'           => 'none',
				'blockBorderWidth'           => 1,
				'blockBorderColor'           => '',
				'blockBorderRadius'          => 2,
				'padding'                    => 20,
				'paddingTablet'              => 20,
				'paddingMobile'              => 20,
				'contentSpacing'             => 8,
				'titleSpacing'               => '',
				'nameSpacing'                => -5,
				'imageSpacing'               => '',
				'contentSpacingMobile'       => 8,
				'titleSpacingMobile'         => '',
				'nameSpacingMobile'          => -5,
				'imageSpacingMobile'         => '',
				'contentSpacingTablet'       => 8,
				'titleSpacingTablet'         => '',
				'nameSpacingTablet'          => -5,
				'imageSpacingTablet'         => '',
				'alignment'                  => 'center',
				'imageShape'                 => '',
				'imageSize'                  => 'full',
				'boxShadowColor'             => '#fff',
				'boxShadowHOffset'           => 0,
				'boxShadowVOffset'           => 0,
				'boxShadowBlur'              => 0,
				'boxShadowSpread'            => 0,
				'boxShadowPosition'          => 'outset',
				'hoverboxShadowColor'        => '#ccc',
				'hoverboxShadowHOffset'      => 0,
				'hoverboxShadowVOffset'      => 0,
				'hoverboxShadowBlur'         => 6,
				'hoverboxShadowSpread'       => 1,
				'hoverboxShadowPosition'     => 'outset',
				'opacity'                    => 0.7,
				'gradientDirection'          => 180,
				'bgGradient'                 => false,
				'backgroundImage'            => '',
				'backgroundPosition'         => '',
				'backgroundSize'             => '',
				'backgroundRepeat'           => '',
				'imageHoverEffect'           => '',
				'bggradient'                 => '',
				'backgroundColor2'           => '',
				'colorLocation1'             => 0,
				'colorLocation2'             => 100,
				'contentFontSizeMobile'      => '',
				'contentFontSizeTablet'      => '',
				'nameFontSizeMobile'         => '',
				'nameFontSizeTablet'         => '',
				'titleFontSizeMobile'        => '',
				'titleFontSizeTablet'        => '',
				'borderStyle'                => 'empty', // For compatibility with v1.3.2.
				'borderWidth'                => 999, // For compatibility with v1.3.2.
				'borderRadius'               => 999, // For compatibility with v1.3.2.
				'borderColor'                => 'empty', // For compatibility with v1.3.2.
				'secondaryBackgroundColor'   => 'empty', // For compatibility with v1.3.2.
			);
		}

		/**
		 * Get Testimonial Slider Block CSS
		 *
		 * @param array  $attr The block attributes.
		 * @param string $id The selector ID.
		 * @return array Styles.
		 */
		public static function get_responsive_block_testimonial_slider_css( $attr, $id ) {
			$defaults = self::get_responsive_block_testimonial_slider_block_default_attributes();
			$attr     = array_merge( $defaults, (array) $attr );

			$updated_background_image = '';
			$background_image_effect  = '';
			$imgopacity               = $attr['backgroundOpacity'] / 100;
			if ( $attr['backgroundImage'] ) {
				$updated_background_image = 'linear-gradient(' . self::hex_to_rgb( $attr['backgroundImageColor'] ? $attr['backgroundImageColor'] : '#fff', $imgopacity ) . ',' . self::hex_to_rgb( $attr['backgroundImageColor'] ? $attr['backgroundImageColor'] : '#fff', $imgopacity ) . '),url(' . $attr['backgroundImage'] . ')';
				if ( 'linear' === $attr['gradientOverlayType'] ) {
					$background_image_effect = self::generate_background_image_effect(
						self::hex_to_rgb( $attr['gradientOverlayColor1'], $imgopacity ),
						self::hex_to_rgb( $attr['gradientOverlayColor2'], $imgopacity ),
						$attr['gradientOverlayAngle'],
						$attr['gradientOverlayLocation1'],
						$attr['gradientOverlayLocation2']
					);
				} else {
					$background_image_effect = 'radial-gradient( at ' . $attr['gradientOverlayPosition'] . ',' . self::hex_to_rgb( $attr['gradientOverlayColor1'] ? $attr['gradientOverlayColor1'] : '#fff', $imgopacity ) .
						' ' . $attr['gradientOverlayLocation1'] . '%,' . self::hex_to_rgb( $attr['gradientOverlayColor2'] ? $attr['gradientOverlayColor2'] : '#fff', $imgopacity ) .
						' ' . $attr['gradientOverlayLocation2'] . '%)';
				}
			}

			$mobile_selectors = array();
			$tablet_selectors = array();

			$position    = str_replace( '-', ' ', $attr['backgroundImagePosition'] );
			$backopacity = $attr['backgroundOpacity'] ? ( 100 - $attr['backgroundOpacity'] ) / 100 : 0.5;

			$selectors = array(
				' '                   => array(
					'padding' => self::get_css_value( $attr['blockPadding'], 'px' ) . ' !important',
				),
				' .slick-arrow svg'   => array(
					'height' => self::get_css_value( $attr['arrowSize'], 'px' ),
					'width'  => self::get_css_value( $attr['arrowSize'], 'px' ),
					'fill'   => $attr['arrowColor'],
				),
				' .responsive-block-editor-addons-testimonial__wrap .responsive-block-editor-addons-tm__content' => array(
					'border-width'  => 999 !== $attr['borderWidth'] && 1 === $attr['blockBorderWidth'] ? self::get_css_value( $attr['borderWidth'], 'px' ) : self::get_css_value( $attr['blockBorderWidth'], 'px' ), // For compatibility with v1.3.2.
					'border-color'  => 'empty' !== $attr['borderColor'] && '' === $attr['blockBorderColor'] ? $attr['borderColor'] : $attr['blockBorderColor'],  // For compatibility with v1.3.2.
					'border-style'  => 'empty' !== $attr['borderStyle'] && 'none' === $attr['blockBorderStyle'] ? $attr['borderStyle'] : $attr['blockBorderStyle'],  // For compatibility with v1.3.2.
					'border-radius' => 999 !== $attr['borderRadius'] && '' === $attr['blockBorderRadius'] ? self::get_css_value( $attr['borderRadius'], 'px' ) : self::get_css_value( $attr['blockBorderRadius'], 'px' ),  // For compatibility with v1.3.2.
					'padding'       => self::get_css_value( $attr['contentPadding'], 'px' ),
					'text-align'    => $attr['headingAlign'],
				),
				' button.slick-arrow' => array(
					'border-width'  => self::get_css_value( $attr['arrowBorderWidth'], 'px' ),
					'border-color'  => $attr['arrowBorderColor'],
					'border-radius' => self::get_css_value( $attr['arrowBorderRadius'], 'px' ),
					'border-style'  => $attr['arrowBorderStyle'],
				),
				' ul.slick-dots li button:before, ul.slick-dots li.slick-active button:before' => array(
					'color' => $attr['arrowColor'],
				),
				' .slick-arrow svg'   => array(
					'fill'   => $attr['arrowColor'],
					'width'  => self::get_css_value( $attr['arrowSize'], 'px' ),
					'height' => self::get_css_value( $attr['arrowSize'], 'px' ),
				),
				' .responsive-block-editor-addons-tm__image img' => array(
					'width'     => self::get_css_value( $attr['imageWidth'], 'px' ),
					'max-width' => self::get_css_value( $attr['imageWidth'], 'px' ),
				),
				' .responsive-block-editor-addons-testimonial__wrap.responsive-block-editor-addons-tm__bg-type-color .responsive-block-editor-addons-tm__content' => array(
					'background-color' => $attr['backgroundColor'],
				),
				' .responsive-block-editor-addons-testimonial__wrap.responsive-block-editor-addons-tm__bg-type-image .responsive-block-editor-addons-tm__content' => array(
					'background-image'      => $updated_background_image,
					'background-position'   => $attr['backgroundImagePosition'],
					'background-repeat'     => 'empty' !== $attr['backgroundRepeat'] && 'no-repeat' === $attr['backgroundImageRepeat'] ? $attr['backgroundRepeat'] : $attr['backgroundImageRepeat'], // For compatibility with v1.3.2.
					'background-size'       => 'empty' !== $attr['backgroundSize'] && 'cover' === $attr['backgroundImageSize'] ? $attr['backgroundSize'] : $attr['backgroundImageSize'], // For compatibility with v1.3.2.
					'background-attachment' => $attr['backgroundAttachment'],
				),
				' .responsive-block-editor-addons-testimonial__wrap.responsive-block-editor-addons-tm__bg-type-image .responsive-block-editor-addons-tm__overlay' => array(
					'background-color' => 'color' === $attr['overlayType'] ? self::hex_to_rgb( $attr['backgroundColor'], $imgopacity ) : '',
					'background-image' => $background_image_effect,
				),
				' .responsive-block-editor-addons-testimonial__wrap' => array(
					'padding-left'  => self::get_css_value( $attr['columnGap'] / 2, 'px' ),
					'padding-right' => self::get_css_value( $attr['columnGap'] / 2, 'px' ),
					'margin-bottom' => self::get_css_value( $attr['rowGap'], 'px' ),
				),
				' .responsive-block-editor-addons-testimonial__wrap .responsive-block-editor-addons-tm__image-content' => array(
					'padding-left'   => self::get_css_value( $attr['imgHrPadding'], 'px' ),
					'padding-right'  => self::get_css_value( $attr['imgHrPadding'], 'px' ),
					'padding-top'    => self::get_css_value( $attr['imgVrPadding'], 'px' ),
					'padding-bottom' => self::get_css_value( $attr['imgVrPadding'], 'px' ),
				),
				' .responsive-block-editor-addons-tm__content.skin-type-bubble .responsive-block-editor-addons-tm__desc' => array(
					'padding' => self::get_css_value( $attr['bubblePadding'], 'px' ),
				),
				' .responsive-block-editor-addons-tm__content.skin-type-bubble .responsive-block-editor-addons-testinomial-text-wrap:before' => array(
					'border-top' => '10px solid ' . $attr['bubbleColor'],
				),
				' .responsive-block-editor-addons-tm__content.skin-type-bubble .responsive-block-editor-addons-testinomial-text-wrap' => array(
					'background-color' => $attr['bubbleColor'],
					'border-radius'    => self::get_css_value( $attr['bubbleBorderRadius'], 'px' ),
				),
				' .responsive-block-editor-addons-tm__desc' => array(
					'font-size' => self::get_css_value( $attr['descFontSize'], $attr['descFontSizeType'] ),
				),
				' .responsive-block-editor-addons-tm__author-name' => array(
					'font-size' => self::get_css_value( $attr['nameFontSize'], $attr['nameFontSizeType'] ),
				),
				' .responsive-block-editor-addons-tm__company' => array(
					'font-size' => self::get_css_value( $attr['companyFontSize'], $attr['companyFontSizeType'] ),
				),
			);

			$mobile_selectors = array(
				' ' => array(
					'padding' => self::get_css_value( $attr['blockPaddingMobile'], 'px' ) . ' !important',
				),
				' .responsive-block-editor-addons-testimonial__wrap .responsive-block-editor-addons-tm__content' => array(
					'text-align' => $attr['headingAlignMobile'],
					'padding'    => self::get_css_value( $attr['contentPaddingMobile'], 'px' ),
				),
				' .responsive-block-editor-addons-testimonial__wrap' => array(
					'padding-left'  => self::get_css_value( $attr['columnGapMobile'] / 2, 'px' ),
					'padding-right' => self::get_css_value( $attr['columnGapMobile'] / 2, 'px' ),
					'margin-bottom' => self::get_css_value( $attr['rowGapMobile'], 'px' ),
				),
				' .responsive-block-editor-addons-testimonial__wrap .responsive-block-editor-addons-tm__image-content' => array(
					'padding-left'   => self::get_css_value( $attr['imgHrPaddingMobile'], 'px' ),
					'padding-right'  => self::get_css_value( $attr['imgHrPaddingMobile'], 'px' ),
					'padding-top'    => self::get_css_value( $attr['imgVrPaddingMobile'], 'px' ),
					'padding-bottom' => self::get_css_value( $attr['imgVrPaddingMobile'], 'px' ),
				),
				' .responsive-block-editor-addons-tm__author-name' => array(
					'margin-bottom' => self::get_css_value( $attr['nameSpaceMobile'], 'px' ) . ' !important',
				),
				' .responsive-block-editor-addons-tm__desc' => array(
					'margin-bottom' => self::get_css_value( $attr['descSpaceMobile'], 'px' ) . ' !important',
				),
				' .responsive-block-editor-addons-tm__desc' => array(
					'font-size' => self::get_css_value( $attr['descFontSizeMobile'], $attr['descFontSizeType'] ) . '!important',
				),
				' .responsive-block-editor-addons-tm__author-name' => array(
					'font-size' => self::get_css_value( $attr['nameFontSizeMobile'], $attr['nameFontSizeType'] ) . '!important',
				),
				' .responsive-block-editor-addons-tm__company' => array(
					'font-size' => self::get_css_value( $attr['companyFontSizeMobile'], $attr['companyFontSizeType'] ) . '!important',
				),
			);

			$tablet_selectors = array(
				' ' => array(
					'padding' => self::get_css_value( $attr['blockPaddingTablet'], 'px' ) . ' !important',
				),
				' .responsive-block-editor-addons-testimonial__wrap .responsive-block-editor-addons-tm__content' => array(
					'text-align' => $attr['headingAlignTablet'],
					'padding'    => self::get_css_value( $attr['contentPaddingTablet'], 'px' ),
				),
				' .responsive-block-editor-addons-testimonial__wrap' => array(
					'padding-left'  => self::get_css_value( $attr['columnGapTablet'] / 2, 'px' ),
					'padding-right' => self::get_css_value( $attr['columnGapTablet'] / 2, 'px' ),
					'margin-bottom' => self::get_css_value( $attr['rowGapTablet'], 'px' ),
				),
				' .responsive-block-editor-addons-testimonial__wrap .responsive-block-editor-addons-tm__image-content' => array(
					'padding-left'   => self::get_css_value( $attr['imgHrPaddingTablet'], 'px' ),
					'padding-right'  => self::get_css_value( $attr['imgHrPaddingTablet'], 'px' ),
					'padding-top'    => self::get_css_value( $attr['imgVrPaddingTablet'], 'px' ),
					'padding-bottom' => self::get_css_value( $attr['imgVrPaddingTablet'], 'px' ),
				),
				' .responsive-block-editor-addons-tm__author-name' => array(
					'margin-bottom' => self::get_css_value( $attr['nameSpaceTablet'], 'px' ) . ' !important',
				),
				' .responsive-block-editor-addons-tm__desc' => array(
					'margin-bottom' => self::get_css_value( $attr['descSpaceTablet'], 'px' ) . ' !important',
				),
				' .responsive-block-editor-addons-tm__desc' => array(
					'font-size' => self::get_css_value( $attr['descFontSizeTablet'], $attr['descFontSizeType'] ) . '!important',
				),
				' .responsive-block-editor-addons-tm__author-name' => array(
					'font-size' => self::get_css_value( $attr['nameFontSizeTablet'], $attr['nameFontSizeType'] ) . '!important',
				),
				' .responsive-block-editor-addons-tm__company' => array(
					'font-size' => self::get_css_value( $attr['companyFontSizeTablet'], $attr['companyFontSizeType'] ) . '!important',
				),
			);

			$combined_selectors = array(
				'desktop' => $selectors,
				'tablet'  => $tablet_selectors,
				'mobile'  => $mobile_selectors,
			);

			$id  = '.responsive-block-editor-addons-testomonial__outer-wrap.responsive-block-editor-addons-block-' . $id;
			$css = Responsive_Block_Editor_Addons_Frontend_Styles_Helper::responsive_block_editor_addons_generate_all_css( $combined_selectors, $id );
			return $css;
		}

		/**
		 * Get Section Block CSS
		 *
		 * @param array  $attr The block attributes.
		 * @param string $id The selector ID.
		 * @return array Styles.
		 */
		public static function get_responsive_block_section_css( $attr, $id ) {
			$defaults = self::get_responsive_block_section_default_attributes();
			$attr     = array_merge( $defaults, (array) $attr );

			$mobile_selectors = array();
			$tablet_selectors = array();

			$box_shadow_position_css = $attr['boxShadowPosition'];

			if ( 'outset' === $attr['boxShadowPosition'] ) {
				$box_shadow_position_css = '';
			}
			$imgopacity = $attr['opacity'] / 100;

			$background_image_effect  = '';
			$updated_background_image = '';

			$color_type = '';
			if ( 'color' === $attr['overlayType'] ) {
				$color_type = self::hex_to_rgb(
					$attr['backgroundImageColor'],
					$imgopacity
				);

				if ( $attr['backgroundImage'] ) {
					$updated_background_image = 'linear-gradient(' .
					self::hex_to_rgb( $attr['backgroundImageColor'], $imgopacity ) .
					',' .
					self::hex_to_rgb( $attr['backgroundImageColor'], $imgopacity ) .
					'),url(' .
					$attr['backgroundImage'] .
					')';
				}
				$background_image_effect = '';
			} else {
				if ( 'linear' === $attr['gradientOverlayType'] ) {
					$background_image_effect = 'linear-gradient(' .
					$attr['gradientOverlayAngle'] .
					'deg,' .
					self::hex_to_rgb( $attr['gradientOverlayColor1'], $imgopacity ) .
					$attr['gradientOverlayLocation1'] .
					'% ,' .
					self::hex_to_rgb( $attr['gradientOverlayColor2'], $imgopacity ) .
					$attr['gradientOverlayLocation2'] .
					'% ),url(' .
					$attr['backgroundImage'] .
					')';
				}
				if ( 'radial' === $attr['gradientOverlayType'] ) {
					$background_image_effect = 'radial-gradient(' .
					'at ' . $attr['gradientOverlayPosition'] . ', ' .
					self::hex_to_rgb( $attr['gradientOverlayColor1'], $imgopacity ) .
					$attr['gradientOverlayLocation1'] .
					'% ,' .
					self::hex_to_rgb( $attr['gradientOverlayColor2'], $imgopacity ) .
					$attr['gradientOverlayLocation2'] .
					'% ),url(' .
					$attr['backgroundImage'] .
					')';
				}
			}

			$selectors = array(
				' > .responsive-block-editor-addons-block-section' => array(
					'margin-top'       => 999 !== $attr['topMargin'] && 0 === $attr['blockTopMargin'] ? self::get_css_value( $attr['topMargin'], 'px' ) : self::get_css_value( $attr['blockTopMargin'], 'px' ), // For compatibility with v1.3.2.
					'margin-bottom'    => 999 !== $attr['bottomMargin'] && 0 === $attr['blockBottomMargin'] ? self::get_css_value( $attr['bottomMargin'], 'px' ) : self::get_css_value( $attr['blockBottomMargin'], 'px' ), // For compatibility with v1.3.2.
					'margin-left'      => 999 !== $attr['leftMargin'] && 0 === $attr['blockLeftMargin'] ? self::get_css_value( $attr['leftMargin'], 'px' ) : self::get_css_value( $attr['blockLeftMargin'], 'px' ), // For compatibility with v1.3.2.
					'margin-right'     => 999 !== $attr['rightMargin'] && 0 === $attr['blockRightMargin'] ? self::get_css_value( $attr['rightMargin'], 'px' ) : self::get_css_value( $attr['blockRightMargin'], 'px' ), // For compatibility with v1.3.2.
					'padding-top'      => 999 !== $attr['topPadding'] && 10 === $attr['blockTopPadding'] ? self::get_css_value( $attr['topPadding'], 'px' ) : self::get_css_value( $attr['blockTopPadding'], 'px' ), // For compatibility with v1.3.2.
					'padding-bottom'   => 999 !== $attr['bottomPadding'] && 10 === $attr['blockBottomPadding'] ? self::get_css_value( $attr['bottomPadding'], 'px' ) : self::get_css_value( $attr['blockBottomPadding'], 'px' ), // For compatibility with v1.3.2.
					'padding-left'     => 999 !== $attr['leftPadding'] && 10 === $attr['blockLeftPadding'] ? self::get_css_value( $attr['leftPadding'], 'px' ) : self::get_css_value( $attr['blockLeftPadding'], 'px' ), // For compatibility with v1.3.2.
					'padding-right'    => 999 !== $attr['rightPadding'] && 10 === $attr['blockRightPadding'] ? self::get_css_value( $attr['rightPadding'], 'px' ) : self::get_css_value( $attr['blockRightPadding'], 'px' ), // For compatibility with v1.3.2.
					'background-color' => $color_type,
					'background-image' => $background_image_effect,
				),
				' > .responsive-section-wrap > .responsive-section-inner-wrap' => array(
					'max-width' => 'full' === $attr['align'] ? self::get_css_value( $attr['innerWidth'], 'px' ) : '',
					'z-index'   => $attr['z_index'],
				),
				' .background-type-video'     => array(
					'background-color' => self::hex_to_rgb(
						$attr['backgroundColor'],
						$imgopacity
					),
				),
				' > .responsive-section-wrap' => array(
					'background-image'      => $updated_background_image,
					'background-position'   => $attr['backgroundPosition'],
					'background-attachment' => $attr['backgroundAttachment'],
					'background-repeat'     => $attr['backgroundRepeat'],
					'background-size'       => $attr['backgroundSize'],
					'border-radius'         => self::get_css_value( $attr['blockBorderRadius'], 'px' ),
					'z-index'               => $attr['z_index'],
					'max-width'             => 'full' !== $attr['align'] ? self::get_css_value( $attr['width'], 'px' ) : '',
					'margin-left'           => 'full' !== $attr['align'] ? 'auto' : '',
					'margin-right'          => 'full' !== $attr['align'] ? 'auto' : '',
				),
				' > .responsive-section-wrap.responsive-block-editor-addons-block-section' => array(
					'border-width'     => self::get_css_value( $attr['blockBorderWidth'], 'px' ),
					'border-color'     => $attr['blockBorderColor'],
					'border-style'     => $attr['blockBorderStyle'],
					'border-radius'    => self::get_css_value( $attr['blockBorderRadius'], 'px' ),
					'background-color' =>
						'color' === $attr['backgroundType']
						? self::hex_to_rgb( $attr['backgroundColor'], $imgopacity )
						: '',
					'background-image' =>
						'gradient' === $attr['backgroundType']
						? self::generate_background_image_effect(
							self::hex_to_rgb( $attr['backgroundColor1'], $imgopacity ),
							self::hex_to_rgb( $attr['backgroundColor2'], $imgopacity ),
							$attr['gradientDirection'],
							$attr['colorLocation1'],
							$attr['colorLocation2']
						)
						: '',
					'box-shadow'       =>
						self::get_css_value( $attr['boxShadowHOffset'], 'px' ) .
						' ' .
						self::get_css_value( $attr['boxShadowVOffset'], 'px' ) .
						' ' .
						self::get_css_value( $attr['boxShadowBlur'], 'px' ) .
						' ' .
						self::get_css_value( $attr['boxShadowSpread'], 'px' ) .
						' ' .
						$attr['boxShadowColor'] .
						' ' .
						$box_shadow_position_css,
				),
			);

			$mobile_selectors = array(
				' > .responsive-block-editor-addons-block-section' => array(
					'margin-top'     => 999 !== $attr['topMarginMobile'] && '' === $attr['blockTopMarginMobile'] ? self::get_css_value( $attr['topMarginMobile'], 'px' ) : self::get_css_value( $attr['blockTopMarginMobile'], 'px' ), // For compatibility with v1.3.2.
					'margin-bottom'  => 999 !== $attr['bottomMarginMobile'] && '' === $attr['blockBottomMarginMobile'] ? self::get_css_value( $attr['bottomMarginMobile'], 'px' ) : self::get_css_value( $attr['blockBottomMarginMobile'], 'px' ), // For compatibility with v1.3.2.
					'margin-left'    => 999 !== $attr['leftMarginMobile'] && '' === $attr['blockLeftMarginMobile'] ? self::get_css_value( $attr['leftMarginMobile'], 'px' ) : self::get_css_value( $attr['blockLeftMarginMobile'], 'px' ), // For compatibility with v1.3.2.
					'margin-right'   => 999 !== $attr['rightMarginMobile'] && '' === $attr['blockRightMarginMobile'] ? self::get_css_value( $attr['rightMarginMobile'], 'px' ) : self::get_css_value( $attr['blockRightMarginMobile'], 'px' ), // For compatibility with v1.3.2.
					'padding-top'    => 999 !== $attr['topPaddingMobile'] && 10 === $attr['blockTopPaddingMobile'] ? self::get_css_value( $attr['topPaddingMobile'], 'px' ) : self::get_css_value( $attr['blockTopPaddingMobile'], 'px' ), // For compatibility with v1.3.2.
					'padding-bottom' => 999 !== $attr['bottomPaddingMobile'] && 10 === $attr['blockBottomPaddingMobile'] ? self::get_css_value( $attr['bottomPaddingMobile'], 'px' ) : self::get_css_value( $attr['blockBottomPaddingMobile'], 'px' ), // For compatibility with v1.3.2.
					'padding-left'   => 999 !== $attr['leftPaddingMobile'] && 10 === $attr['blockLeftPaddingMobile'] ? self::get_css_value( $attr['leftPaddingMobile'], 'px' ) : self::get_css_value( $attr['blockLeftPaddingMobile'], 'px' ), // For compatibility with v1.3.2.
					'padding-right'  => 999 !== $attr['rightPaddingMobile'] && 10 === $attr['blockRightPaddingMobile'] ? self::get_css_value( $attr['rightPaddingMobile'], 'px' ) : self::get_css_value( $attr['blockRightPaddingMobile'], 'px' ), // For compatibility with v1.3.2.
				),
				' > .responsive-section-wrap > .responsive-section-inner-wrap' => array(
					'max-width' =>
						'full' === $attr['align'] ? self::get_css_value( $attr['innerWidthMobile'], 'px' ) : '',
				),
				' > .responsive-section-wrap' => array(
					'background-position' => $attr['backgroundPositionMobile'],
					'background-size'     => '' === $attr['backgroundSizeMobile'] ? $attr['backgroundSize'] : $attr['backgroundSizeMobile'],
				),
			);

			$tablet_selectors = array(
				' > .responsive-block-editor-addons-block-section' => array(
					'margin-top'     => 999 !== $attr['topMarginTablet'] && '' === $attr['blockTopMarginTablet'] ? self::get_css_value( $attr['topMarginTablet'], 'px' ) : self::get_css_value( $attr['blockTopMarginTablet'], 'px' ), // For compatibility with v1.3.2.
					'margin-bottom'  => 999 !== $attr['bottomMarginTablet'] && '' === $attr['blockBottomMarginTablet'] ? self::get_css_value( $attr['bottomMarginTablet'], 'px' ) : self::get_css_value( $attr['blockBottomMarginTablet'], 'px' ), // For compatibility with v1.3.2.
					'margin-left'    => 999 !== $attr['leftMarginTablet'] && '' === $attr['blockLeftMarginTablet'] ? self::get_css_value( $attr['leftMarginTablet'], 'px' ) : self::get_css_value( $attr['blockLeftMarginTablet'], 'px' ), // For compatibility with v1.3.2.
					'margin-right'   => 999 !== $attr['rightMarginTablet'] && '' === $attr['blockRightMarginTablet'] ? self::get_css_value( $attr['rightMarginTablet'], 'px' ) : self::get_css_value( $attr['blockRightMarginTablet'], 'px' ), // For compatibility with v1.3.2.
					'padding-top'    => 999 !== $attr['topPaddingTablet'] && 10 === $attr['blockTopPaddingTablet'] ? self::get_css_value( $attr['topPaddingTablet'], 'px' ) : self::get_css_value( $attr['blockTopPaddingTablet'], 'px' ), // For compatibility with v1.3.2.
					'padding-bottom' => 999 !== $attr['bottomPaddingTablet'] && 10 === $attr['blockBottomPaddingTablet'] ? self::get_css_value( $attr['bottomPaddingTablet'], 'px' ) : self::get_css_value( $attr['blockBottomPaddingTablet'], 'px' ), // For compatibility with v1.3.2.
					'padding-left'   => 999 !== $attr['leftPaddingTablet'] && 10 === $attr['blockLeftPaddingTablet'] ? self::get_css_value( $attr['leftPaddingTablet'], 'px' ) : self::get_css_value( $attr['blockLeftPaddingTablet'], 'px' ), // For compatibility with v1.3.2.
					'padding-right'  => 999 !== $attr['rightPaddingTablet'] && 10 === $attr['blockRightPaddingTablet'] ? self::get_css_value( $attr['rightPaddingTablet'], 'px' ) : self::get_css_value( $attr['blockRightPaddingTablet'], 'px' ), // For compatibility with v1.3.2.
				),
				' > .responsive-section-wrap > .responsive-section-inner-wrap' => array(
					'max-width' =>
						'full' === $attr['align'] ? self::get_css_value( $attr['innerWidthTablet'], 'px' ) : '',
				),
				' > .responsive-section-wrap' => array(
					'background-position' => $attr['backgroundPositionTablet'],
					'background-size'     => '' === $attr['backgroundSizeTablet'] ? $attr['backgroundSize'] : $attr['backgroundSizeTablet'],
				),
			);

			$combined_selectors = array(
				'desktop' => $selectors,
				'tablet'  => $tablet_selectors,
				'mobile'  => $mobile_selectors,
			);

			$id              = '.responsive-block-editor-addons-block-section-outer-wrap.block-' . $id;
			$css             = Responsive_Block_Editor_Addons_Frontend_Styles_Helper::responsive_block_editor_addons_generate_all_css( $combined_selectors, $id );
			$css['desktop'] .= '
            .responsive-block-editor-addons-section__video-wrap {
                border-radius:' . $attr['blockBorderRadius'] . 'px;
              }
            .page.page-template-gutenberg-fullwidth .alignfull' . $id . ' > .responsive-section-wrap > .responsive-section-inner-wrap{
                max-width:' . ( 'full' === $attr['align'] ? self::get_css_value( $attr['innerWidth'], 'px' ) : ' ' ) . ' ;}

                .page.page-template-gutenberg-fullwidth .alignfull ' . $id . '{
                max-width:' . ( 'full' !== $attr['align'] ? self::get_css_value( $attr['width'], 'px' ) . ' !important' : ' ' ) . ' ;
                margin-left:' . ( 'full' !== $attr['align'] ? 'auto' : ' ' ) . ' ;
                margin-right:' . ( 'full' !== $attr['align'] ? 'auto' : ' ' ) . ' ;
                }
            ';
			$css['tablet']  .= '
            .page.page-template-gutenberg-fullwidth .alignfull' . $id . ' > .responsive-section-wrap > .responsive-section-inner-wrap{
                max-width:' . ( 'full' === $attr['align'] ? self::get_css_value( $attr['innerWidthTablet'], 'px' ) : ' ' ) . ' ;}
            ';
			$css['mobile']  .= '
            .page.page-template-gutenberg-fullwidth .alignfull' . $id . ' > .responsive-section-wrap > .responsive-section-inner-wrap{
                max-width:' . ( 'full' === $attr['align'] ? self::get_css_value( $attr['innerWidthMobile'], 'px' ) : ' ' ) . ' ;}
            ';

			return $css;
		}

		/**
		 * Get Defaults for testimonial slider block
		 *
		 * @return array
		 */
		public static function get_responsive_block_testimonial_slider_block_default_attributes() {
			return array(
				'test_item_count'          => 3,
				'classMigrate'             => false,
				'test_block'               => array(),
				'skin'                     => 'default',
				'bubblePadding'            => 20,
				'bubbleBorderRadius'       => 0,
				'bubbleColor'              => 'center',
				'headingAlign'             => 'center',
				'headingAlignMobile'       => 'center',
				'headingAlignTablet'       => 'center',
				'descColor'                => '#333',
				'companyColor'             => '#888888',
				'authorColor'              => '#333',
				'iconimgStyle'             => 'circle',
				'imagePosition'            => 'bottom',
				'imageAlignment'           => 'top',
				'nameFontSizeType'         => 'px',
				'nameFontSize'             => '',
				'nameFontSizeTablet'       => '',
				'nameFontSizeMobile'       => '',
				'nameFontFamily'           => '',
				'nameFontWeight'           => '',
				'nameFontSubset'           => '',
				'nameLineHeightType'       => 'em',
				'nameLineHeight'           => '',
				'nameLineHeightTablet'     => '',
				'nameLineHeightMobile'     => '',
				'nameLoadGoogleFonts'      => false,
				'companyFontSizeType'      => 'px',
				'companyFontSize'          => '',
				'companyFontSizeTablet'    => '',
				'companyFontSizeMobile'    => '',
				'companyFontFamily'        => '',
				'companyFontWeight'        => '',
				'companyFontSubset'        => '',
				'companyLineHeightType'    => 'em',
				'companyLineHeight'        => '',
				'companyLineHeightTablet'  => '',
				'companyLineHeightMobile'  => '',
				'companyLoadGoogleFonts'   => false,
				'descFontSizeType'         => 'px',
				'descFontSize'             => '',
				'descFontSizeTablet'       => '',
				'descFontSizeMobile'       => '',
				'descFontFamily'           => '',
				'descFontWeight'           => '',
				'descFontSubset'           => '',
				'descLineHeightType'       => 'em',
				'descLineHeight'           => '',
				'descLineHeightTablet'     => '',
				'descLineHeightMobile'     => '',
				'descLoadGoogleFonts'      => false,
				'nameSpace'                => 5,
				'descSpace'                => 15,
				'nameSpaceMobile'          => 5,
				'descSpaceMobile'          => 15,
				'nameSpaceTablet'          => 5,
				'descSpaceTablet'          => 15,
				'block_id'                 => 'not_set',
				'authorSpace'              => 5,
				'imgVrPadding'             => 10,
				'imgHrPadding'             => 10,
				'imgVrPaddingMobile'       => 10,
				'imgHrPaddingMobile'       => 10,
				'imgVrPaddingTablet'       => 10,
				'imgHrPaddingTablet'       => 10,
				'imgTopPadding'            => 10,
				'imgBottomPadding'         => 10,
				'iconImage'                => array(
					'url' => '',
					'alt' => 'InfoBox placeholder img',
				),
				'imageSize'                => 'thumbnail',
				'imageWidth'               => 60,
				'columns'                  => 1,
				'tcolumns'                 => 1,
				'mcolumns'                 => 1,
				'pauseOnHover'             => true,
				'infiniteLoop'             => true,
				'transitionSpeed'          => 500,
				'autoplay'                 => true,
				'autoplaySpeed'            => 2000,
				'arrowDots'                => 'arrows_dots',
				'arrowSize'                => 20,
				'arrowBorderWidth'         => 1,
				'arrowBorderRadius'        => 0,
				'arrowBorderColor'         => '',
				'arrowBorderStyle'         => '',
				'rowGap'                   => 10,
				'rowGapMobile'             => 10,
				'rowGapTablet'             => 10,
				'columnGap'                => 10,
				'columnGapMobile'          => 10,
				'columnGapTablet'          => 10,
				'contentPadding'           => 5,
				'contentPaddingMobile'     => 5,
				'contentPaddingTablet'     => 5,
				'backgroundType'           => '',
				'backgroundImage'          => '',
				'backgroundImagePosition'  => 'center-center',
				'backgroundImageSize'      => 'cover',
				'backgroundImageRepeat'    => 'no-repeat',
				'backgroundAttachment'     => 'scroll',
				'overlayType'              => 'color',
				'backgroundColor'          => '',
				'backgroundImageColor'     => '',
				'blockBorderStyle'         => 'none',
				'blockBorderWidth'         => '1',
				'blockBorderRadius'        => '',
				'blockBorderColor'         => '',
				'backgroundOpacity'        => 50,
				'arrowColor'               => '#333',
				'stack'                    => 'tablet',
				'blockPadding'             => 45,
				'blockPaddingMobile'       => 45,
				'blockPaddingTablet'       => 45,
				'gradientOverlayType'      => 'linear',
				'gradientOverlayColor1'    => '#fff',
				'gradientOverlayColor2'    => '#fff',
				'gradientOverlayAngle'     => '0',
				'gradientOverlayLocation1' => '0',
				'gradientOverlayLocation2' => '100',
				'backgroundRepeat'         => 'empty', // For compatibility with v1.3.2.
				'backgroundSize'           => 'empty', // For compatibility with v1.3.2.
				'borderStyle'              => 'empty', // For compatibility with v1.3.2.
				'borderColor'              => 'empty', // For compatibility with v1.3.2.
				'borderWidth'              => 999, // For compatibility with v1.3.2.
				'borderRadius'             => 999, // For compatibility with v1.3.2.
			);
		}

		/**
		 * Get Video Popup Block CSS
		 *
		 * @param array  $attr The block attributes.
		 * @param string $id The selector ID.
		 * @return array Styles.
		 */
		public static function get_responsive_block_video_popup_css( $attr, $id ) {
			$defaults = self::get_responsive_block_video_popup_default_attributes();
			$attr     = array_merge( $defaults, (array) $attr );

			$mobile_selectors = array();
			$tablet_selectors = array();

			$box_shadow_position_css = $attr['boxShadowPosition'];
			if ( 'outset' === $attr['boxShadowPosition'] ) {
				$box_shadow_position_css = '';
			}

			$imgopacity  = $attr['opacity'] / 100;
			$playopacity = $attr['butopacity'] / 100;

			$bg_image_with_opacity = '';
			if ( $attr['backgroundImage'] ) {
				$bg_image_with_opacity = 'linear-gradient( to bottom, ' .
					self::hex_to_rgb( $attr['vidBackgroundColor'], $imgopacity ) .
					', ' .
					self::hex_to_rgb( $attr['vidBackgroundColor'], $imgopacity ) .
					'), ' .
					'url(' .
					$attr['backgroundImage'] .
					')';
			}

			$selectors = array(
				' .responsive-block-editor-addons-video-popup__wrapper' => array(
					'background-image' => 'empty' !== $attr['imgURL'] && '' === $attr['backgroundImage'] ? 'url(' . $attr['imgURL'] . ')' : $bg_image_with_opacity, // For compatibility with v1.3.2.
					'background-color' => self::hex_to_rgb( $attr['vidBackgroundColor'] || '#000000', $imgopacity ),
					'max-width'        => self::get_css_value( $attr['vidwidth'], 'px' ),
					'height'           => self::get_css_value( $attr['vidheight'], 'px' ),
					'border-width'     => self::get_css_value( $attr['blockBorderWidth'], 'px' ),
					'border-color'     => $attr['blockBorderColor'],
					'border-style'     => $attr['blockBorderStyle'],
					'border-radius'    => self::get_css_value( $attr['blockBorderRadius'], 'px' ),
					'box-shadow'       =>
					self::get_css_value( $attr['boxShadowHOffset'], 'px' ) .
					' ' .
					self::get_css_value( $attr['boxShadowVOffset'], 'px' ) .
					' ' .
					self::get_css_value( $attr['boxShadowBlur'], 'px' ) .
					' ' .
					self::get_css_value( $attr['boxShadowSpread'], 'px' ) .
					' ' .
					$attr['boxShadowColor'] .
					' ' .
					$box_shadow_position_css,
				),

				' .responsive-block-editor-addons-video-popup__play-button svg' => array(
					'width'   => self::get_css_value( $attr['playButtonSize'], 'px' ),
					'height'  => self::get_css_value( $attr['playButtonSize'], 'px' ),
					'fill'    => $attr['playButtonColor'],
					'opacity' => $playopacity,
				),
			);

			$mobile_selectors = array(
				' .responsive-block-editor-addons-video-popup__wrapper' => array(
					'max-width' => self::get_css_value( $attr['vidwidthMobile'], 'px' ),
					'height'    => self::get_css_value( $attr['vidheightMobile'], 'px' ),
				),
			);

			$tablet_selectors = array(
				' .responsive-block-editor-addons-video-popup__wrapper' => array(
					'max-width' => self::get_css_value( $attr['vidwidthTablet'], 'px' ),
					'height'    => self::get_css_value( $attr['vidheightTablet'], 'px' ),
				),
			);

			$combined_selectors = array(
				'desktop' => $selectors,
				'tablet'  => $tablet_selectors,
				'mobile'  => $mobile_selectors,
			);

			$id  = '.responsive-block-editor-addons-block-video-popup.block-' . $id;
			$css = Responsive_Block_Editor_Addons_Frontend_Styles_Helper::responsive_block_editor_addons_generate_all_css( $combined_selectors, $id );
			return $css;
		}

		/**
		 * Get Defaults for video popup block
		 *
		 * @return array
		 */
		public static function get_responsive_block_video_popup_default_attributes() {
			return array(
				'block_id'               => '',
				'align'                  => '',
				'videoLink'              => '',
				'videoID'                => '',
				'borderRadius'           => '',
				'shadow'                 => '',
				'playButtonType'         => 'normal',
				'playButtonColor'        => '',
				'playButtonSize'         => 30,
				'vidwidth'               => '',
				'vidwidthTablet'         => '',
				'vidwidthMobile'         => '',
				'vidheight'              => '',
				'vidheightTablet'        => '',
				'vidheightMobile'        => '',
				'opacity'                => 50,
				'imgURL'                 => 'empty', // For compatibility with v1.3.2.
				'imgID'                  => '',
				'imgAlt'                 => '',
				'counterId'              => 1,
				'butopacity'             => 100,
				'blockBorderStyle'       => 'none',
				'blockBorderWidth'       => 1,
				'blockBorderRadius'      => '',
				'blockBorderColor'       => '',
				'boxShadowColor'         => '',
				'boxShadowHOffset'       => 0,
				'boxShadowVOffset'       => 0,
				'boxShadowBlur'          => '',
				'boxShadowSpread'        => '',
				'boxShadowPosition'      => 'outset',
				'previewBackgroundColor' => '#000000',
				'hoverEffect'            => '',
				'backgroundImage'        => '',
				'vidBackgroundColor'     => '#000000',
			);
		}

		/**
		 * Get Defaults for advanced column block
		 *
		 * @return array
		 */
		public static function get_responsive_block_advanced_columns_default_attributes() {
			return array(
				'columnGap'                => 'default',
				'widthType'                => 'px',
				'contentWidth'             => 'theme',
				'stack'                    => 'mobile',
				'columns'                  => 2,
				'width'                    => 900,
				'boxTopPadding'            => 10,
				'boxBottomPadding'         => 10,
				'boxLeftPadding'           => 10,
				'boxRightPadding'          => 10,
				'boxTopPaddingTablet'      => '',
				'boxBottomPaddingTablet'   => '',
				'boxLeftPaddingTablet'     => '',
				'boxRightPaddingTablet'    => '',
				'boxTopPaddingMobile'      => '',
				'boxBottomPaddingMobile'   => '',
				'boxLeftPaddingMobile'     => '',
				'boxRightPaddingMobile'    => '',
				'topMargin'                => 0,
				'bottomMargin'             => 0,
				'topMarginTablet'          => '',
				'bottomMarginTablet'       => '',
				'topMarginMobile'          => '',
				'bottomMarginMobile'       => '',
				'blockBorderStyle'         => 'none',
				'blockBorderWidth'         => 1,
				'blockBorderRadius'        => '',
				'blockBorderColor'         => '',
				'boxShadowColor'           => '',
				'boxShadowHOffset'         => 0,
				'boxShadowVOffset'         => 0,
				'boxShadowBlur'            => 0,
				'boxShadowSpread'          => 0,
				'boxShadowPosition'        => 'outset',
				'opacity'                  => 20,
				'colorLocation1'           => 0,
				'colorLocation2'           => 100,
				'gradientDirection'        => 90,
				'backgroundType'           => '',
				'backgroundColor'          => '',
				'backgroundColor1'         => '',
				'backgroundColor2'         => '#fff',
				'backgroundImage'          => '',
				'backgroundImagePosition'  => 'center-center',
				'backgroundImageSize'      => 'cover',
				'backgroundImageRepeat'    => 'no-repeat',
				'backgroundAttachment'     => 'scroll',
				'backgroundImageColor'     => '',
				'overlayType'              => 'color',
				'gradientOverlayColor1'    => '',
				'gradientOverlayColor2'    => '',
				'gradientOverlayType'      => 'linear',
				'gradientOverlayLocation1' => 0,
				'gradientOverlayLocation2' => 100,
				'gradientOverlayAngle'     => 0,
				'gradientOverlayPosition'  => 'center center',
				'blockAlign'               => 'left',
				'verticalAlign'            => 'flex-start',
				'blockId'                  => '',
				'height'                   => 'normal',
				'customHeight'             => 50,
				'z_index'                  => 1,
				'backgroundPosition'       => 'empty', // For compatibility with v1.3.2.
				'backgroundRepeat'         => 'empty', // For compatibility with v1.3.2.
				'backgroundSize'           => 'empty', // For compatibility with v1.3.2.
				'topPadding'               => 999, // For compatibility with v1.3.2.
				'bottomPadding'            => 999, // For compatibility with v1.3.2.
				'leftPadding'              => 999, // For compatibility with v1.3.2.
				'rightPadding'             => 999, // For compatibility with v1.3.2.
				'topPaddingMobile'         => 999, // For compatibility with v1.3.2.
				'bottomPaddingMobile'      => 999, // For compatibility with v1.3.2.
				'leftPaddingMobile'        => 999, // For compatibility with v1.3.2.
				'rightPaddingMobile'       => 999, // For compatibility with v1.3.2.
				'topPaddingTablet'         => 999, // For compatibility with v1.3.2.
				'bottomPaddingTablet'      => 999, // For compatibility with v1.3.2.
				'leftPaddingTablet'        => 999, // For compatibility with v1.3.2.
				'rightPaddingTablet'       => 999, // For compatibility with v1.3.2.
			);
		}

		/**
		 * Get Defaults for count up block
		 *
		 * @return array
		 */
		public static function get_responsive_block_count_up_default_attributes() {
			return array(
				'block_id'              => '',
				'countUp'               => '',
				'count'                 => '',
				'gutter'                => 'medium',
				'contentAlign'          => 'center',
				'textColor'             => '',
				'itemBackgroundColor'   => '',
				'contentFontFamily'     => '',
				'headingFontFamily'     => '',
				'dateFontFamily'        => '',
				'dateLineHeight'        => 1,
				'dateFontWeight'        => '400',
				'dateFontSize'          => 40,
				'dateFontSizeMobile'    => '',
				'dateFontSizeTablet'    => '',
				'headingLineHeight'     => 1.8,
				'headingFontSize'       => 16,
				'headingFontWeight'     => '900',
				'titleFontWeight'       => 'empty', // For compatibility with v1.3.2.
				'headingFontSizeTablet' => '',
				'headingFontSizeMobile' => '',
				'blockBorderRadius'     => '',
				'blockBorderColor'      => '',
				'numColor'              => '',
				'titleColor'            => '',
				'titleSpacing'          => '',
				'numberSpacing'         => '',
				'descriptionSpacing'    => '',
				'contentLineHeight'     => 1.75,
				'contentFontWeight'     => '400',
				'contentFontSize'       => 16,
				'contentFontSizeMobile' => '',
				'contentFontSizeTablet' => '',
				'icon'                  => 'welcome-add-page',
				'resshowIcon'           => false,
				'resshowTitle'          => true,
				'resshowDesc'           => true,
				'blockBorderStyle'      => 'none',
				'blockBorderWidth'      => 1,
				'opacity'               => 10,
				'icon_color'            => '#3a3a3a',
				'iconsize'              => 16,
				'resshowNum'            => true,
				'titleSpace'            => 20,
				'titleSpaceTablet'      => '',
				'titleSpaceMobile'      => '',
				'contentSpace'          => 30,
				'contentSpaceTablet'    => '',
				'contentSpaceMobile'    => '',
				'numSpace'              => 20,
				'numSpaceMobile'        => '',
				'numSpaceTablet'        => '',
				'iconStyle'             => 'none',
				'shapeBorderRadius'     => 100,
				'shapePadding'          => 20,
				'shapeBorder'           => 2,
				'iconShapeColor'        => '#add5ef',
				'contentSpacing'        => 0,
				'contentSpacingMobile'  => '',
				'contentSpacingTablet'  => '',
				'iconSpacing'           => 16,
				'iconSpacingTablet'     => '',
				'iconSpacingMobile'     => '',
			);
		}

		/**
		 * Get Defaults for blockquote block
		 *
		 * @return array
		 */
		public static function get_responsive_block_blockquote_default_attributes() {
			return array(
				'block_id'                 => '',
				'quoteContent'             => '',
				'quoteBackgroundColor'     => '',
				'quoteTextColor'           => '',
				'quoteFontFamily'          => '',
				'quoteFontSize'            => 18,
				'quoteFontWeight'          => '400',
				'quoteLineHeight'          => 1,
				'quoteSize'                => 70,
				'quoteColor'               => '',
				'blockBorderStyle'         => 'none',
				'blockBorderWidth'         => 1,
				'blockBorderRadius'        => 0,
				'blockBorderColor'         => '',
				'blockLeftPadding'         => 60,
				'blockRightPadding'        => 60,
				'blockTopPadding'          => 70,
				'blockBottomPadding'       => 70,
				'blockLeftPaddingMobile'   => 60,
				'blockRightPaddingMobile'  => 60,
				'blockTopPaddingMobile'    => 70,
				'blockBottomPaddingMobile' => 70,
				'blockLeftPaddingTablet'   => 60,
				'blockRightPaddingTablet'  => 60,
				'blockTopPaddingTablet'    => 70,
				'blockBottomPaddingTablet' => 70,
				'quoteHposition'           => 30,
				'quoteVposition'           => 20,
				'quoteAlign'               => 'left-aligned',
				'quoteOpacity'             => 100,
				'showQuote'                => true,
				'opacity'                  => 100,
				'colorLocation1'           => 0,
				'colorLocation2'           => 100,
				'gradientDirection'        => 90,
				'backgroundImage'          => '',
				'backgroundVideo'          => '',
				'backgroundColor'          => '',
				'backgroundColor1'         => '',
				'backgroundColor2'         => '',
				'backgroundType'           => 'none',
				'icon'                     => 'round-fat',
				'boxShadowColor'           => '#fff',
				'boxShadowHOffset'         => 0,
				'boxShadowVOffset'         => 0,
				'boxShadowBlur'            => 20,
				'boxShadowSpread'          => 20,
				'boxShadowPosition'        => 'outset',
				'textTopPadding'           => 60,
				'textTopPaddingMobile'     => 30,
				'textTopPaddingTablet'     => 30,
				'textBottomPadding'        => 0,
				'textBottomPaddingMobile'  => 0,
				'textBottomPaddingTablet'  => 0,
				'textLeftPadding'          => 70,
				'textLeftPaddingMobile'    => 35,
				'textLeftPaddingTablet'    => 35,
				'textRightPadding'         => 70,
				'textRightPaddingMobile'   => 35,
				'textRightPaddingTablet'   => 35,
				'quoteFontSizeMobile'      => '',
				'quoteFontSizeTablet'      => '',
				'borderColor'              => 'empty', // For compatibility with v1.3.2.
				'borderStyle'              => 'empty', // For compatibility with v1.3.2.
				'borderWidth'              => 999, // For compatibility with v1.3.2.
				'topPadding'               => 999, // For compatibility with v1.3.2.
				'bottomPadding'            => 999, // For compatibility with v1.3.2.
				'leftPadding'              => 999, // For compatibility with v1.3.2.
				'rightPadding'             => 999, // For compatibility with v1.3.2.
			);
		}

		/**
		 * Get Defaults for divider block
		 *
		 * @return array
		 */
		public static function get_responsive_block_divider_default_attributes() {
			return array(
				'block_id'               => '',
				'spacerHeight'           => 30,
				'spacerHeightMobile'     => '',
				'spacerHeightTablet'     => '',
				'spacerDivider'          => false,
				'spacerDividerStyle'     => 'solid',
				'spacerDividerColor'     => '#000',
				'spacerDividerHeight'    => 7,
				'spacerDividerWidth'     => 60,
				'spacerDividerAlignment' => 'center',
			);
		}

		/**
		 * Get Count Down Block CSS
		 *
		 * @param array  $attr The block attributes.
		 * @param string $id The selector ID.
		 * @return array Styles.
		 */
		public static function get_responsive_block_count_down_css( $attr, $id ) {
			$defaults = self::get_responsive_block_count_down_default_attributes();
			$attr     = array_merge( $defaults, (array) $attr );

			$mobile_selectors = array();
			$tablet_selectors = array();

			$box_shadow_position_css = $attr['boxShadowPosition'];
			if ( 'outset' === $attr['boxShadowPosition'] ) {
				$box_shadow_position_css = '';
			}

			$display_days    = $attr['showDaysBox'] ? 'block' : 'none';
			$display_hours   = $attr['showHoursBox'] ? 'block' : 'none';
			$display_minutes = $attr['showMinutesBox'] ? 'block' : 'none';
			$display_seconds = $attr['showSecondsBox'] ? 'block' : 'none';

			$flex_column = $attr['stackOnMobile'] ? 'column' : 'row';

			$selectors = array(
				' .responsive-block-editor-addons-countdown-box-stylings' => array(
					'height'           => self::get_css_value( $attr['boxHeight'], 'px' ),
					'width'            => self::get_css_value( $attr['boxWidth'], 'px' ),
					'margin-left'      => self::get_css_value( $attr['boxMargin'], 'px' ),
					'padding-top'      => 999 !== $attr['boxPaddingTop'] && 0 === $attr['boxTopPadding'] ? self::get_css_value( $attr['boxPaddingTop'], 'px' ) : self::get_css_value( $attr['boxTopPadding'], 'px' ), // For compatibility with v1.3.2.
					'padding-bottom'   => 999 !== $attr['boxPaddingBottom'] && 10 === $attr['boxBottomPadding'] ? self::get_css_value( $attr['boxPaddingBottom'], 'px' ) : self::get_css_value( $attr['boxBottomPadding'], 'px' ), // For compatibility with v1.3.2.
					'padding-left'     => 999 !== $attr['boxPaddingLeft'] && 0 === $attr['boxLeftPadding'] ? self::get_css_value( $attr['boxPaddingLeft'], 'px' ) : self::get_css_value( $attr['boxLeftPadding'], 'px' ), // For compatibility with v1.3.2.
					'padding-right'    => 999 !== $attr['boxPaddingRight'] && 0 === $attr['boxRightPadding'] ? self::get_css_value( $attr['boxPaddingRight'], 'px' ) : self::get_css_value( $attr['boxRightPadding'], 'px' ), // For compatibility with v1.3.2.
					'border'           => $attr['boxBorderSize'] . 'px ' . $attr['boxBorderStyle'] . ' ' . $attr['boxBorderColor'],
					'border-radius'    => $attr['borderRadiusTopLeft'] . 'px ' . $attr['borderRadiusTopRight'] . 'px ' . $attr['borderRadiusBottomRight'] . 'px ' . $attr['borderRadiusBottomLeft'] . 'px',
					'background-color' => 'empty' !== $attr['boxBackgroundColor'] && '#6EC1E4' === $attr['backgroundColor'] ? $attr['boxBackgroundColor'] : $attr['backgroundColor'], // For compatibility with v1.3.2.
					'box-shadow'       => $attr['boxShadowHOffset'] . 'px ' . $attr['boxShadowVOffset'] . 'px ' . $attr['boxShadowBlur'] . 'px ' . $attr['boxShadowSpread'] . 'px ' . $attr['boxShadowColor'] . ' ' . $box_shadow_position_css,
				),
				' .responsive-block-editor-addons-countdown-box-stylings:first-of-type' => array(
					'margin-left' => '0px !important',
				),
				' .responsive-block-editor-addons-countdown-digits' => array(
					'font-family'     => $attr['digitFontFamily'],
					'font-size'       => self::get_css_value( $attr['digitFontSize'], 'px' ),
					'font-weight'     => $attr['digitFontWeight'],
					'letter-spacing'  => self::get_css_value( $attr['digitLetterSpacing'], 'px' ),
					'line-height'     => $attr['digitLineHeight'],
					'color'           => $attr['digitColor'],
					'display'         => $attr['displayInline'] ? 'flex' : 'block',
					'flex'            => $attr['displayInline'] ? 1 : null,
					'justify-content' => $attr['displayInline'] ? 'flex-end' : null,
				),
				' .responsive-block-editor-addons-countdown-label' => array(
					'font-family'     => $attr['labelFontFamily'],
					'font-size'       => self::get_css_value( $attr['labelFontSize'], 'px' ),
					'font-weight'     => $attr['labelFontWeight'],
					'line-height'     => $attr['labelLineHeight'],
					'padding-left'    => $attr['displayInline'] ? self::get_css_value( $attr['labelLeftPadding'], 'px' ) : '0px',
					'letter-spacing'  => self::get_css_value( $attr['labelLetterSpacing'], 'px' ),
					'color'           => $attr['labelColor'],
					'display'         => $attr['showDigitLabels'] ? ( $attr['displayInline'] ? 'flex' : 'block' ) : 'none',
					'flex'            => $attr['displayInline'] ? 1 : null,
					'justify-content' => $attr['displayInline'] ? 'flex-start' : null,
				),
				' .responsive-block-editor-addons-countdown-box-margins' => array(
					'margin-top'      => self::get_css_value( $attr['boxItemMarginTop'], 'px' ),
					'margin-right'    => self::get_css_value( $attr['boxItemMarginRight'], 'px' ),
					'margin-bottom'   => self::get_css_value( $attr['boxItemMarginBottom'], 'px' ),
					'margin-left'     => self::get_css_value( $attr['boxItemMarginLeft'], 'px' ),
					'text-align'      => $attr['boxItemTextAlign'],
					'display'         => $attr['displayInline'] ? 'flex' : null,
					'justify-content' => $attr['displayInline'] ? 'center' : null,
					'align-items'     => $attr['displayInline'] ? 'center' : null,
				),
				'.responsive-block-editor-addons-countdown-wrapper' => array(
					'margin-top'     => 999 !== $attr['containerMarginTop'] && 0 === $attr['containerTopMargin'] ? self::get_css_value( $attr['containerMarginTop'], 'px' ) : self::get_css_value( $attr['containerTopMargin'], 'px' ), // For compatibility with v1.3.2.
					'margin-right'   => 999 !== $attr['containerMarginRight'] && 0 === $attr['containerRightMargin'] ? self::get_css_value( $attr['containerMarginRight'], 'px' ) : self::get_css_value( $attr['containerRightMargin'], 'px' ), // For compatibility with v1.3.2.
					'margin-bottom'  => 999 !== $attr['containerMarginBottom'] && 0 === $attr['containerBottomMargin'] ? self::get_css_value( $attr['containerMarginBottom'], 'px' ) : self::get_css_value( $attr['containerBottomMargin'], 'px' ), // For compatibility with v1.3.2.
					'margin-left'    => 999 !== $attr['containerMarginLeft'] && 0 === $attr['containerLeftMargin'] ? self::get_css_value( $attr['containerMarginLeft'], 'px' ) : self::get_css_value( $attr['containerLeftMargin'], 'px' ), // For compatibility with v1.3.2.
					'padding-top'    => 999 !== $attr['containerPaddingTop'] && 0 === $attr['containerTopPadding'] ? self::get_css_value( $attr['containerPaddingTop'], 'px!important' ) : self::get_css_value( $attr['containerTopPadding'], 'px!important' ), // For compatibility with v1.3.2.
					'padding-right'  => 999 !== $attr['containerPaddingRight'] && 0 === $attr['containerRightPadding'] ? self::get_css_value( $attr['containerPaddingRight'], 'px!important' ) : self::get_css_value( $attr['containerRightPadding'], 'px!important' ), // For compatibility with v1.3.2.
					'padding-bottom' => 999 !== $attr['containerPaddingBottom'] && 0 === $attr['containerBottomPadding'] ? self::get_css_value( $attr['containerPaddingBottom'], 'px!important' ) : self::get_css_value( $attr['containerBottomPadding'], 'px!important' ), // For compatibility with v1.3.2.
					'padding-left'   => 999 !== $attr['containerPaddingLeft'] && 0 === $attr['containerLeftPadding'] ? self::get_css_value( $attr['containerPaddingLeft'], 'px!important' ) : self::get_css_value( $attr['containerLeftPadding'], 'px!important' ), // For compatibility with v1.3.2.
				),
				' .responsive-block-editor-addons-countdown-container .responsive-block-editor-addons-countdown-items' => array(
					'justify-content' => $attr['justifyItems'],
					'margin'          => 0,
				),
				' .responsive-block-editor-addons-countdown-get-date' => array(
					'display' => 'none',
				),
				' .responsive-block-editor-addons-countdown-item.responsive-block-editor-addons-countdown-item-days' => array(
					'display' => $display_days,
				),
				' .responsive-block-editor-addons-countdown-item.responsive-block-editor-addons-countdown-item-hours' => array(
					'display' => $display_hours,
				),
				' .responsive-block-editor-addons-countdown-item.responsive-block-editor-addons-countdown-item-minutes' => array(
					'display' => $display_minutes,
				),
				' .responsive-block-editor-addons-countdown-item.responsive-block-editor-addons-countdown-item-seconds' => array(
					'display' => $display_seconds,
				),
			);

			$mobile_selectors = array(
				' .responsive-block-editor-addons-countdown-box-stylings' => array(
					'height'           => self::get_css_value( $attr['boxHeightMobile'], 'px' ),
					'width'            => true === $attr['stackOnMobile'] ? '100%' : self::get_css_value( $attr['boxWidthMobile'], 'px' ),
					'margin-left'      => true === $attr['stackOnMobile'] ? '0px' : self::get_css_value( $attr['boxMarginMobile'], 'px' ),
					'margin-bottom'    => true === $attr['stackOnMobile'] ? self::get_css_value( $attr['boxMarginMobile'], 'px' ) : '0px',
					'padding-top'      => 999 !== $attr['boxPaddingTopMobile'] && '' === $attr['boxTopPaddingMobile'] ? self::get_css_value( $attr['boxPaddingTopMobile'], 'px' ) : self::get_css_value( $attr['boxTopPaddingMobile'], 'px' ), // For compatibility with v1.3.2.
					'padding-bottom'   => 999 !== $attr['boxPaddingBottomMobile'] && '' === $attr['boxBottomPaddingMobile'] ? self::get_css_value( $attr['boxPaddingBottomMobile'], 'px' ) : self::get_css_value( $attr['boxBottomPaddingMobile'], 'px' ), // For compatibility with v1.3.2.
					'padding-left'     => 999 !== $attr['boxPaddingLeftMobile'] && '' === $attr['boxLeftPaddingMobile'] ? self::get_css_value( $attr['boxPaddingLeftMobile'], 'px' ) : self::get_css_value( $attr['boxLeftPaddingMobile'], 'px' ), // For compatibility with v1.3.2.
					'padding-right'    => 999 !== $attr['boxPaddingRightMobile'] && '' === $attr['boxRightPaddingMobile'] ? self::get_css_value( $attr['boxPaddingRightMobile'], 'px' ) : self::get_css_value( $attr['boxRightPaddingMobile'], 'px' ), // For compatibility with v1.3.2.
					'border'           => $attr['boxBorderSize'] . 'px ' . $attr['boxBorderStyle'] . ' ' . $attr['boxBorderColor'],
					'border-radius'    => $attr['borderRadiusTopLeft'] . 'px ' . $attr['borderRadiusTopRight'] . 'px ' . $attr['borderRadiusBottomRight'] . 'px ' . $attr['borderRadiusBottomLeft'] . 'px',
					'background-color' => 'empty' !== $attr['boxBackgroundColor'] && '#6EC1E4' === $attr['backgroundColor'] ? $attr['boxBackgroundColor'] : $attr['backgroundColor'], // For compatibility with v1.3.2.
					'box-shadow'       => $attr['boxShadowHOffset'] . 'px ' . $attr['boxShadowVOffset'] . 'px ' . $attr['boxShadowBlur'] . 'px ' . $attr['boxShadowSpread'] . 'px ' . $attr['boxShadowColor'] . ' ' . $box_shadow_position_css,
				),
				' .responsive-block-editor-addons-countdown-box-stylings:first-of-type' => array(
					'margin-left' => true === $attr['stackOnMobile'] ? '' : '0px !important',
				),
				' .responsive-block-editor-addons-countdown-box-stylings:last-of-type' => array(
					'margin-bottom' => true === $attr['stackOnMobile'] ? '0px !important' : '',
				),
				' .responsive-block-editor-addons-countdown-digits' => array(
					'font-family'     => $attr['digitFontFamily'],
					'font-size'       => self::get_css_value( $attr['digitFontSizeMobile'], 'px' ),
					'font-weight'     => $attr['digitFontWeight'],
					'letter-spacing'  => self::get_css_value( $attr['digitLetterSpacing'], 'px' ),
					'line-height'     => $attr['digitLineHeight'],
					'color'           => $attr['digitColor'],
					'display'         => $attr['displayInline'] ? 'flex' : 'block',
					'flex'            => $attr['displayInline'] ? 1 : null,
					'justify-content' => $attr['displayInline'] ? 'flex-end' : null,
				),
				' .responsive-block-editor-addons-countdown-label' => array(
					'font-family'     => $attr['labelFontFamily'],
					'font-size'       => self::get_css_value( $attr['labelFontSizeMobile'], 'px' ),
					'font-weight'     => $attr['labelFontWeight'],
					'line-height'     => $attr['labelLineHeight'],
					'padding-left'    => $attr['displayInline'] ? self::get_css_value( $attr['labelLeftPadding'], 'px' ) : '0px',
					'letter-spacing'  => self::get_css_value( $attr['labelLetterSpacing'], 'px' ),
					'color'           => $attr['labelColor'],
					'flex'            => $attr['displayInline'] ? 1.5 : null,
					'justify-content' => $attr['displayInline'] ? 'flex-start' : null,
				),
				' .responsive-block-editor-addons-countdown-box-margins' => array(
					'margin-top'    => self::get_css_value( $attr['boxItemMarginTopMobile'], 'px' ),
					'margin-right'  => self::get_css_value( $attr['boxItemMarginRightMobile'], 'px' ),
					'margin-bottom' => self::get_css_value( $attr['boxItemMarginBottomMobile'], 'px' ),
					'margin-left'   => self::get_css_value( $attr['boxItemMarginLeftMobile'], 'px' ),
				),
				'.responsive-block-editor-addons-countdown-wrapper' => array(
					'margin-top'     => 999 !== $attr['containerMarginTopMobile'] && '' === $attr['containerTopMarginMobile'] ? self::get_css_value( $attr['containerMarginTopMobile'], 'px' ) : self::get_css_value( $attr['containerTopMarginMobile'], 'px' ), // For compatibility with v1.3.2.
					'margin-right'   => 999 !== $attr['containerMarginRightMobile'] && '' === $attr['containerRightMarginMobile'] ? self::get_css_value( $attr['containerMarginRightMobile'], 'px' ) : self::get_css_value( $attr['containerRightMarginMobile'], 'px' ), // For compatibility with v1.3.2.
					'margin-bottom'  => 999 !== $attr['containerMarginBottomMobile'] && '' === $attr['containerBottomMarginMobile'] ? self::get_css_value( $attr['containerMarginBottomMobile'], 'px' ) : self::get_css_value( $attr['containerBottomMarginMobile'], 'px' ), // For compatibility with v1.3.2.
					'margin-left'    => 999 !== $attr['containerMarginLeftMobile'] && '' === $attr['containerLeftMarginMobile'] ? self::get_css_value( $attr['containerMarginLeftMobile'], 'px' ) : self::get_css_value( $attr['containerLeftMarginMobile'], 'px' ), // For compatibility with v1.3.2.
					'padding-top'    => 999 !== $attr['containerPaddingTopMobile'] && '' === $attr['containerTopPaddingMobile'] ? self::get_css_value( $attr['containerPaddingTopMobile'], 'px!important' ) : self::get_css_value( $attr['containerTopPaddingMobile'], 'px!important' ), // For compatibility with v1.3.2.
					'padding-right'  => 999 !== $attr['containerPaddingRightMobile'] && '' === $attr['containerRightPaddingMobile'] ? self::get_css_value( $attr['containerPaddingRightMobile'], 'px!important' ) : self::get_css_value( $attr['containerRightPaddingMobile'], 'px!important' ), // For compatibility with v1.3.2.
					'padding-bottom' => 999 !== $attr['containerPaddingBottomMobile'] && '' === $attr['containerBottomPaddingMobile'] ? self::get_css_value( $attr['containerPaddingBottomMobile'], 'px!important' ) : self::get_css_value( $attr['containerBottomPaddingMobile'], 'px!important' ), // For compatibility with v1.3.2.
					'padding-left'   => 999 !== $attr['containerPaddingLeftMobile'] && '' === $attr['containerLeftPaddingMobile'] ? self::get_css_value( $attr['containerPaddingLeftMobile'], 'px!important' ) : self::get_css_value( $attr['containerLeftPaddingMobile'], 'px!important' ), // For compatibility with v1.3.2.
				),
				' .responsive-block-editor-addons-countdown-container .responsive-block-editor-addons-countdown-items' => array(
					'flex-direction' => $flex_column,
					'align-items'    => 'center',
				),
			);

			$tablet_selectors = array(
				' .responsive-block-editor-addons-countdown-box-stylings' => array(
					'height'           => self::get_css_value( $attr['boxHeightTablet'], 'px' ),
					'width'            => self::get_css_value( $attr['boxWidthTablet'], 'px' ),
					'margin-left'      => self::get_css_value( $attr['boxMarginTablet'], 'px' ),
					'padding-top'      => 999 !== $attr['boxPaddingTopTablet'] && '' === $attr['boxTopPaddingTablet'] ? self::get_css_value( $attr['boxPaddingTopTablet'], 'px' ) : self::get_css_value( $attr['boxTopPaddingTablet'], 'px' ), // For compatibility with v1.3.2.
					'padding-bottom'   => 999 !== $attr['boxPaddingBottomTablet'] && '' === $attr['boxBottomPaddingTablet'] ? self::get_css_value( $attr['boxPaddingBottomTablet'], 'px' ) : self::get_css_value( $attr['boxBottomPaddingTablet'], 'px' ), // For compatibility with v1.3.2.
					'padding-left'     => 999 !== $attr['boxPaddingLeftTablet'] && '' === $attr['boxLeftPaddingTablet'] ? self::get_css_value( $attr['boxPaddingLeftTablet'], 'px' ) : self::get_css_value( $attr['boxLeftPaddingTablet'], 'px' ), // For compatibility with v1.3.2.
					'padding-right'    => 999 !== $attr['boxPaddingRightTablet'] && '' === $attr['boxRightPaddingTablet'] ? self::get_css_value( $attr['boxPaddingRightTablet'], 'px' ) : self::get_css_value( $attr['boxRightPaddingTablet'], 'px' ), // For compatibility with v1.3.2.
					'border'           => $attr['boxBorderSize'] . 'px ' . $attr['boxBorderStyle'] . ' ' . $attr['boxBorderColor'],
					'border-radius'    => $attr['borderRadiusTopLeft'] . 'px ' . $attr['borderRadiusTopRight'] . 'px ' . $attr['borderRadiusBottomRight'] . 'px ' . $attr['borderRadiusBottomLeft'] . 'px',
					'background-color' => 'empty' !== $attr['boxBackgroundColor'] && '#6EC1E4' === $attr['backgroundColor'] ? $attr['boxBackgroundColor'] : $attr['backgroundColor'], // For compatibility with v1.3.2.
					'box-shadow'       => $attr['boxShadowHOffset'] . 'px ' . $attr['boxShadowVOffset'] . 'px ' . $attr['boxShadowBlur'] . 'px ' . $attr['boxShadowSpread'] . 'px ' . $attr['boxShadowColor'] . ' ' . $box_shadow_position_css,
				),

				' .responsive-block-editor-addons-countdown-digits' => array(
					'font-family'     => $attr['digitFontFamily'],
					'font-size'       => self::get_css_value( $attr['digitFontSizeTablet'], 'px' ),
					'font-weight'     => $attr['digitFontWeight'],
					'letter-spacing'  => self::get_css_value( $attr['digitLetterSpacing'], 'px' ),
					'line-height'     => $attr['digitLineHeight'],
					'color'           => $attr['digitColor'],
					'display'         => $attr['displayInline'] ? 'flex' : 'block',
					'flex'            => $attr['displayInline'] ? 1 : null,
					'justify-content' => $attr['displayInline'] ? 'flex-end' : null,
				),

				' .responsive-block-editor-addons-countdown-label' => array(
					'font-family'     => $attr['labelFontFamily'],
					'font-size'       => self::get_css_value( $attr['labelFontSizeTablet'], 'px' ),
					'font-weight'     => $attr['labelFontWeight'],
					'line-height'     => $attr['labelLineHeight'],
					'padding-left'    => $attr['displayInline'] ? self::get_css_value( $attr['labelLeftPadding'], 'px' ) : '0px',
					'letter-spacing'  => self::get_css_value( $attr['labelLetterSpacing'], 'px' ),
					'color'           => $attr['labelColor'],
					'flex'            => $attr['displayInline'] ? 1 : null,
					'justify-content' => $attr['displayInline'] ? 'flex-start' : null,
				),
				' .responsive-block-editor-addons-countdown-box-margins' => array(
					'margin-top'    => self::get_css_value( $attr['boxItemMarginTopTablet'], 'px' ),
					'margin-right'  => self::get_css_value( $attr['boxItemMarginRightTablet'], 'px' ),
					'margin-bottom' => self::get_css_value( $attr['boxItemMarginBottomTablet'], 'px' ),
					'margin-left'   => self::get_css_value( $attr['boxItemMarginLeftTablet'], 'px' ),
				),
				'.responsive-block-editor-addons-countdown-wrapper' => array(
					'padding'        => '30px' ? '0px!important' : '',
					'margin-top'     => 999 !== $attr['containerMarginTopTablet'] && '' === $attr['containerTopMarginTablet'] ? self::get_css_value( $attr['containerMarginTopTablet'], 'px' ) : self::get_css_value( $attr['containerTopMarginTablet'], 'px' ), // For compatibility with v1.3.2.
					'margin-right'   => 999 !== $attr['containerMarginRightTablet'] && '' === $attr['containerRightMarginTablet'] ? self::get_css_value( $attr['containerMarginRightTablet'], 'px' ) : self::get_css_value( $attr['containerRightMarginTablet'], 'px' ), // For compatibility with v1.3.2.
					'margin-bottom'  => 999 !== $attr['containerMarginBottomTablet'] && '' === $attr['containerBottomMarginTablet'] ? self::get_css_value( $attr['containerMarginBottomTablet'], 'px' ) : self::get_css_value( $attr['containerBottomMarginTablet'], 'px' ), // For compatibility with v1.3.2.
					'margin-left'    => 999 !== $attr['containerMarginLeftTablet'] && '' === $attr['containerLeftMarginTablet'] ? self::get_css_value( $attr['containerMarginLeftTablet'], 'px' ) : self::get_css_value( $attr['containerLeftMarginTablet'], 'px' ), // For compatibility with v1.3.2.
					'padding-top'    => 999 !== $attr['containerPaddingTopTablet'] && '' === $attr['containerTopPaddingTablet'] ? self::get_css_value( $attr['containerPaddingTopTablet'], 'px!important' ) : self::get_css_value( $attr['containerTopPaddingTablet'], 'px!important' ), // For compatibility with v1.3.2.
					'padding-right'  => 999 !== $attr['containerPaddingRightTablet'] && '' === $attr['containerRightPaddingTablet'] ? self::get_css_value( $attr['containerPaddingRightTablet'], 'px!important' ) : self::get_css_value( $attr['containerRightPaddingTablet'], 'px!important' ), // For compatibility with v1.3.2.
					'padding-bottom' => 999 !== $attr['containerPaddingBottomTablet'] && '' === $attr['containerBottomPaddingTablet'] ? self::get_css_value( $attr['containerPaddingBottomTablet'], 'px!important' ) : self::get_css_value( $attr['containerBottomPaddingTablet'], 'px!important' ), // For compatibility with v1.3.2.
					'padding-left'   => 999 !== $attr['containerPaddingLeftTablet'] && '' === $attr['containerLeftPaddingTablet'] ? self::get_css_value( $attr['containerPaddingLeftTablet'], 'px!important' ) : self::get_css_value( $attr['containerLeftPaddingTablet'], 'px!important' ), // For compatibility with v1.3.2.
				),
			);

			$combined_selectors = array(
				'desktop' => $selectors,
				'tablet'  => $tablet_selectors,
				'mobile'  => $mobile_selectors,
			);

			$id  = '.responsive-block-editor-addons-block-count-down.block-' . $id;
			$css = Responsive_Block_Editor_Addons_Frontend_Styles_Helper::responsive_block_editor_addons_generate_all_css( $combined_selectors, $id );
			return $css;
		}

		/**
		 * Get Defaults for count down block
		 *
		 * @return array
		 */
		public static function get_responsive_block_count_down_default_attributes() {
			return array(
				'block_id'                     => '',
				'date'                         => '',
				'days'                         => '0',
				'hours'                        => '0',
				'minutes'                      => '0',
				'seconds'                      => '0',
				'digitDaysLabel'               => 'Days',
				'digitHoursLabel'              => 'Hours',
				'digitMinutesLabel'            => 'Minutes',
				'digitSecondsLabel'            => 'Seconds',
				'showDigitLabels'              => true,
				'showDaysBox'                  => true,
				'showHoursBox'                 => true,
				'showMinutesBox'               => true,
				'showSecondsBox'               => true,
				'digitFontFamily'              => '',
				'digitFontSize'                => 48,
				'digitFontSizeMobile'          => '',
				'digitFontSizeTablet'          => '',
				'digitFontWeight'              => '500',
				'digitLetterSpacing'           => 0,
				'digitLineHeight'              => 2,
				'digitColor'                   => '#fff',
				'labelFontFamily'              => '',
				'labelFontSize'                => 14,
				'labelFontSizeMobile'          => '',
				'labelFontSizeTablet'          => '',
				'labelColor'                   => '#fff',
				'labelLineHeight'              => 2,
				'labelFontWeight'              => '500',
				'labelLeftPadding'             => 0,
				'labelLetterSpacing'           => 0,
				'boxItemMarginTop'             => 0,
				'boxItemMarginRight'           => 0,
				'boxItemMarginBottom'          => 0,
				'boxItemMarginLeft'            => 0,
				'boxItemMarginTopTablet'       => 0,
				'boxItemMarginRightTablet'     => 0,
				'boxItemMarginBottomTablet'    => 0,
				'boxItemMarginLeftTablet'      => 0,
				'boxItemMarginTopMobile'       => 0,
				'boxItemMarginRightMobile'     => 0,
				'boxItemMarginBottomMobile'    => 0,
				'boxItemMarginLeftMobile'      => 0,
				'boxItemTextAlign'             => 'center',
				'boxHeight'                    => '',
				'boxWidth'                     => 140,
				'boxMargin'                    => 10,
				'boxHeightMobile'              => '',
				'boxWidthMobile'               => 80,
				'boxMarginMobile'              => '',
				'boxHeightTablet'              => '',
				'boxWidthTablet'               => 140,
				'boxMarginTablet'              => '',
				'boxTopPadding'                => 0,
				'boxRightPadding'              => 0,
				'boxBottomPadding'             => 10,
				'boxLeftPadding'               => 0,
				'boxTopPaddingMobile'          => '',
				'boxRightPaddingMobile'        => '',
				'boxBottomPaddingMobile'       => '',
				'boxLeftPaddingMobile'         => '',
				'boxTopPaddingTablet'          => '',
				'boxRightPaddingTablet'        => '',
				'boxBottomPaddingTablet'       => '',
				'boxLeftPaddingTablet'         => '',
				'showBoxBorder'                => true,
				'boxBorderColor'               => '000',
				'boxBorderSize'                => 0,
				'boxBorderStyle'               => 'solid',
				'borderRadiusTopLeft'          => 0,
				'borderRadiusTopRight'         => 0,
				'borderRadiusBottomRight'      => 0,
				'borderRadiusBottomLeft'       => 0,
				'showBoxShadow'                => true,
				'boxShadowHOffset'             => 0,
				'boxShadowVOffset'             => 0,
				'boxShadowBlur'                => 0,
				'boxShadowPosition'            => 'outset',
				'boxShadowSpread'              => 0,
				'boxShadowColor'               => '#000',
				'backgroundColor'              => '#6EC1E4',
				'containerTopMargin'           => 0,
				'containerRightMargin'         => 0,
				'containerBottomMargin'        => 0,
				'containerLeftMargin'          => 0,
				'containerTopPadding'          => 0,
				'containerRightPadding'        => 0,
				'containerBottomPadding'       => 0,
				'containerLeftPadding'         => 0,
				'containerTopMarginTablet'     => '',
				'containerRightMarginTablet'   => '',
				'containerBottomMarginTablet'  => '',
				'containerLeftMarginTablet'    => '',
				'containerTopPaddingTablet'    => '',
				'containerRightPaddingTablet'  => '',
				'containerBottomPaddingTablet' => '',
				'containerLeftPaddingTablet'   => '',
				'containerTopMarginMobile'     => '',
				'containerRightMarginMobile'   => '',
				'containerBottomMarginMobile'  => '',
				'containerLeftMarginMobile'    => '',
				'containerTopPaddingMobile'    => '',
				'containerRightPaddingMobile'  => '',
				'containerBottomPaddingMobile' => '',
				'containerLeftPaddingMobile'   => '',
				'justifyItems'                 => 'center',
				'displayInline'                => false,
				'stackOnMobile'                => false,
				'boxPaddingTop'                => 999, // For compatibility with v1.3.2.
				'boxPaddingBottom'             => 999, // For compatibility with v1.3.2.
				'boxPaddingLeft'               => 999, // For compatibility with v1.3.2.
				'boxPaddingRight'              => 999, // For compatibility with v1.3.2.
				'boxPaddingTopMobile'          => 999, // For compatibility with v1.3.2.
				'boxPaddingBottomMobile'       => 999, // For compatibility with v1.3.2.
				'boxPaddingLeftMobile'         => 999, // For compatibility with v1.3.2.
				'boxPaddingRightMobile'        => 999, // For compatibility with v1.3.2.
				'boxPaddingTopTablet'          => 999, // For compatibility with v1.3.2.
				'boxPaddingBottomTablet'       => 999, // For compatibility with v1.3.2.
				'boxPaddingLeftTablet'         => 999, // For compatibility with v1.3.2.
				'boxPaddingRightTablet'        => 999, // For compatibility with v1.3.2.
				'containerMarginTop'           => 999, // For compatibility with v1.3.2.
				'containerMarginTopMobile'     => 999, // For compatibility with v1.3.2.
				'containerMarginTopTablet'     => 999, // For compatibility with v1.3.2.
				'containerMarginBottom'        => 999, // For compatibility with v1.3.2.
				'containerMarginBottomMobile'  => 999, // For compatibility with v1.3.2.
				'containerMarginBottomTablet'  => 999, // For compatibility with v1.3.2.
				'containerMarginLeft'          => 999, // For compatibility with v1.3.2.
				'containerMarginLeftMobile'    => 999, // For compatibility with v1.3.2.
				'containerMarginLeftTablet'    => 999, // For compatibility with v1.3.2.
				'containerMarginRight'         => 999, // For compatibility with v1.3.2.
				'containerMarginRightMobile'   => 999, // For compatibility with v1.3.2.
				'containerMarginRightTablet'   => 999, // For compatibility with v1.3.2.
				'containerPaddingTop'          => 999, // For compatibility with v1.3.2.
				'containerPaddingTopMobile'    => 999, // For compatibility with v1.3.2.
				'containerPaddingTopTablet'    => 999, // For compatibility with v1.3.2.
				'containerPaddingBottom'       => 999, // For compatibility with v1.3.2.
				'containerPaddingBottomMobile' => 999, // For compatibility with v1.3.2.
				'containerPaddingBottomTablet' => 999, // For compatibility with v1.3.2.
				'containerPaddingLeft'         => 999, // For compatibility with v1.3.2.
				'containerPaddingLeftMobile'   => 999, // For compatibility with v1.3.2.
				'containerPaddingLeftTablet'   => 999, // For compatibility with v1.3.2.
				'containerPaddingRight'        => 999, // For compatibility with v1.3.2.
				'containerPaddingRightMobile'  => 999, // For compatibility with v1.3.2.
				'containerPaddingRightTablet'  => 999, // For compatibility with v1.3.2.
				'boxBackgroundColor'           => 'empty', // For compatibility with v1.3.2.
			);
		}

		/**
		 * Get Table Of Contents Block CSS
		 *
		 * @param array  $attr The block attributes.
		 * @param string $id The selector ID.
		 * @return array Styles.
		 */
		public static function get_responsive_block_table_of_contents_css( $attr, $id ) {
			$defaults = self::get_responsive_block_table_of_contents_default_attributes();
			$attr     = array_merge( $defaults, (array) $attr );
			?>
			<script>
				( function( $ ) {

					RBEATableOfContents = {

						init: function() {
							$( document ).on( "click",'.responsive-block-editor-addons-toc__title-wrap', RBEATableOfContents._toggleCollapse )
						},

						_toggleCollapse: function( e ) {
							if ( $( this ).find( '.responsive-block-editor-addons-toc__collapsible-wrap' ).length > 0 ) {
								let $root = $( this ).closest( '.wp-block-responsive-block-editor-addons-table-of-contents' )
								if ( $root.hasClass( 'responsive-block-editor-addons-toc__collapse' ) ) {
									$root.removeClass( 'responsive-block-editor-addons-toc__collapse' );
								} else {
									$root.addClass( 'responsive-block-editor-addons-toc__collapse' );
								}
							}
						},

						_run: function( attr, id ) {
							var $this_scope = $( id );
							if ( $this_scope.find( '.responsive-block-editor-addons-toc__collapsible-wrap' ).length > 0 ) {
								$this_scope.find( '.responsive-block-editor-addons-toc__title-wrap' ).addClass( 'responsive-block-editor-addons-toc__is-collapsible' );
							}
						},
					}

					$( document ).ready(function() {
						RBEATableOfContents.init()
						RBEATableOfContents._run( <?php echo wp_json_encode( $attr ); ?>, '<?php echo esc_attr( $id ); ?>' );
					})
				})( jQuery )
			</script>
			<?php

			$mobile_selectors = array();
			$tablet_selectors = array();

			$justify_content = 'flex-start';
			if ( 'center' === $attr['align'] ) {
				$justify_content = 'center';
			} elseif ( 'right' === $attr['align'] ) {
				$justify_content = 'flex-end';
			}

			$updated_background_image = '';
			if ( $attr['backgroundImage'] ) {
				$updated_background_image = 'linear-gradient(' .
				self::hex_to_rgb( '#fff', 0 ) .
				',' .
				self::hex_to_rgb( '#fff', 0 ) .
				'),url(' .
				$attr['backgroundImage'] .
				')';
			}
			if ( 'image' !== $attr['backgroundType'] ) {
				$updated_background_image = '';
			}

			$selectors = array(
				' ' => array(
					'background-image'    => $updated_background_image,
					'padding-top'         => self::get_css_value( $attr['blockTopPadding'], 'px' ) . '!important',
					'padding-bottom'      => self::get_css_value( $attr['blockBottomPadding'], 'px' ) . '!important',
					'padding-left'        => self::get_css_value( $attr['blockLeftPadding'], 'px' ) . '!important',
					'padding-right'       => self::get_css_value( $attr['blockRightPadding'], 'px' ) . '!important',
					'margin-top'          => self::get_css_value( $attr['blockTopMargin'], 'px' ) . '!important',
					'margin-bottom'       => self::get_css_value( $attr['blockBottomMargin'], 'px' ) . '!important',
					'z-index'             => $attr['zIndex'],
					'display'             => 'flex',
					'background-position' => $attr['backgroundPosition'],
					'background-repeat'   => $attr['backgroundRepeat'],
					'background-size'     => $attr['backgroundSize'],
					'background-color'    => 'color' === $attr['backgroundType'] ? self::hex_to_rgb(
						$attr['backgroundColor'],
						1
					) : null,
				),
				' .responsive-block-editor-addons-toc__title-wrap' => array(
					'justify-content'  => $justify_content,
					'font-family'      => $attr['headingFontFamily'],
					'font-weight'      => $attr['headingFontWeight'],
					'font-size'        => self::get_css_value( $attr['headingFontSize'], 'px' ),
					'line-height'      => $attr['headingLineHeight'],
					'color'            => $attr['headingColor'],
					'background-color' => $attr['headingBgColor'],
					'padding-top'      => self::get_css_value( $attr['headingTopPadding'], 'px' ) . '!important',
					'padding-bottom'   => self::get_css_value( $attr['headingBottomPadding'], 'px' ) . '!important',
					'padding-left'     => self::get_css_value( $attr['headingLeftPadding'], 'px' ) . '!important',
					'padding-right'    => self::get_css_value( $attr['headingRightPadding'], 'px' ) . '!important',
					'margin-top'       => self::get_css_value( $attr['headingTopMargin'], 'px' ) . '!important',
					'margin-bottom'    => self::get_css_value( $attr['headingBottomMargin'], 'px' ) . '!important',
					'margin-left'      => self::get_css_value( $attr['headingLeftMargin'], 'px' ) . '!important',
					'margin-right'     => self::get_css_value( $attr['headingRightMargin'], 'px' ) . '!important',
					'border-style'     => $attr['headingBorderStyle'],
					'border-color'     => $attr['headingBorderColor'],
					'border-radius'    => self::get_css_value( $attr['headingBorderRadius'], 'px' ) . ' !important',
					'border-width'     => self::get_css_value( $attr['headingBorderWidth'], 'px' ),
				),
				' .responsive-block-editor-addons-toc__title-wrap:hover' => array(
					'color'            => $attr['headingColorHover'],
					'background-color' => $attr['headingBgColorHover'],
				),
				' .responsive-block-editor-addons-toc__list-wrap' => array(
					'font-family'         => $attr['contentFontFamily'],
					'font-weight'         => $attr['contentFontWeight'],
					'font-size'           => self::get_css_value( $attr['contentFontSize'], 'px' ),
					'line-height'         => $attr['contentLineHeight'],
					'background-color'    => $attr['bodyBgColor'],
					'padding-top'         => self::get_css_value( $attr['contentTopPadding'], 'px' ) . '!important',
					'padding-bottom'      => self::get_css_value( $attr['contentBottomPadding'], 'px' ) . '!important',
					'padding-left'        => self::get_css_value( $attr['contentLeftPadding'], 'px' ) . '!important',
					'padding-right'       => self::get_css_value( $attr['contentRightPadding'], 'px' ) . '!important',
					'margin-top'          => self::get_css_value( $attr['contentTopMargin'], 'px' ) . '!important',
					'margin-bottom'       => self::get_css_value( $attr['contentBottomMargin'], 'px' ) . '!important',
					'margin-left'         => self::get_css_value( $attr['contentLeftMargin'], 'px' ) . '!important',
					'margin-right'        => self::get_css_value( $attr['contentRightMargin'], 'px' ) . '!important',
					'border-style'        => $attr['bodyBorderStyle'],
					'border-color'        => $attr['bodyBorderColor'],
					'border-radius'       => self::get_css_value( $attr['bodyBorderRadius'], 'px' ) . ' !important',
					'border-top-width'    => self::get_css_value( 0, 'px' ),
					'border-left-width'   => self::get_css_value( $attr['bodyBorderWidth'], 'px' ),
					'border-right-width'  => self::get_css_value( $attr['bodyBorderWidth'], 'px' ),
					'border-bottom-width' => self::get_css_value( $attr['bodyBorderWidth'], 'px' ),
				),
				' .responsive-block-editor-addons_table-of-contents-placeholder' => array(
					'font-family'         => $attr['contentFontFamily'],
					'font-weight'         => $attr['contentFontWeight'],
					'font-size'           => self::get_css_value( $attr['contentFontSize'], 'px' ),
					'line-height'         => $attr['contentLineHeight'],
					'background-color'    => $attr['bodyBgColor'],
					'padding-top'         => self::get_css_value( $attr['contentTopPadding'], 'px' ) . '!important',
					'padding-bottom'      => self::get_css_value( $attr['contentBottomPadding'], 'px' ) . '!important',
					'padding-left'        => self::get_css_value( $attr['contentLeftPadding'], 'px' ) . '!important',
					'padding-right'       => self::get_css_value( $attr['contentRightPadding'], 'px' ) . '!important',
					'margin-top'          => self::get_css_value( $attr['contentTopMargin'], 'px' ) . '!important',
					'margin-bottom'       => self::get_css_value( $attr['contentBottomMargin'], 'px' ) . '!important',
					'margin-left'         => self::get_css_value( $attr['contentLeftMargin'], 'px' ) . '!important',
					'margin-right'        => self::get_css_value( $attr['contentRightMargin'], 'px' ) . '!important',
					'border-style'        => $attr['bodyBorderStyle'],
					'border-color'        => $attr['bodyBorderColor'],
					'border-radius'       => self::get_css_value( $attr['bodyBorderRadius'], 'px' ) . ' !important',
					'border-top-width'    => self::get_css_value( 0, 'px' ),
					'border-left-width'   => self::get_css_value( $attr['bodyBorderWidth'], 'px' ),
					'border-right-width'  => self::get_css_value( $attr['bodyBorderWidth'], 'px' ),
					'border-bottom-width' => self::get_css_value( $attr['bodyBorderWidth'], 'px' ),
					'color'               => $attr['bodyColor'],
				),
				' .responsive-block-editor-addons-toc__list-wrap .responsive-block-editor-addons-toc__list li a' => array(
					'color' => $attr['bodyColor'],
				),
				' .responsive-block-editor-addons-toc__list-wrap .responsive-block-editor-addons-toc__list li' => array(
					'color' => $attr['bodyColor'],
					'width' => 'fit-content',
				),
				' .responsive-block-editor-addons-toc__list-wrap:hover' => array(
					'background-color' => $attr['bodyBgColorHover'],
				),
				' .responsive-block-editor-addons-toc__list-wrap .responsive-block-editor-addons-toc__list li:hover, .responsive-block-editor-addons-toc__list-wrap .responsive-block-editor-addons-toc__list li:hover a, .responsive-block-editor-addons-toc__list-wrap .responsive-block-editor-addons-toc__list li a:hover' => array(
					'color' => $attr['bodyColorHover'],
				),
				' .responsive-block-editor-addons-toc__list-wrap .responsive-block-editor-addons-toc__list li a:hover' => array(
					'color' => $attr['bodyColorHover'],
				),
				' .responsive-block-editor-addons-toc__wrap' => array(
					'width'         => self::get_css_value( $attr['blockWidth'], '%' ),
					'border-style'  => $attr['blockBorderStyle'],
					'border-color'  => $attr['blockBorderColor'],
					'border-radius' => self::get_css_value( $attr['blockBorderRadius'], 'px' ) . ' !important',
					'border-width'  => self::get_css_value( $attr['blockBorderWidth'], 'px' ),
				),
			);

			$mobile_selectors = array(
				' ' => array(
					'padding-top'    => self::get_css_value( $attr['blockTopPaddingMobile'], 'px' ) . '!important',
					'padding-bottom' => self::get_css_value( $attr['blockBottomPaddingMobile'], 'px' ) . '!important',
					'padding-left'   => self::get_css_value( $attr['blockLeftPaddingMobile'], 'px' ) . '!important',
					'padding-right'  => self::get_css_value( $attr['blockRightPaddingMobile'], 'px' ) . '!important',
					'margin-top'     => self::get_css_value( $attr['blockTopMarginMobile'], 'px' ) . '!important',
					'margin-bottom'  => self::get_css_value( $attr['blockBottomMarginMobile'], 'px' ) . '!important',
					'margin-left'    => self::get_css_value( $attr['blockLeftMarginMobile'], 'px' ) . '!important',
					'margin-right'   => self::get_css_value( $attr['blockRightMarginMobile'], 'px' ) . '!important',
				),
				' .responsive-block-editor-addons-toc__title-wrap' => array(
					'font-size'      => self::get_css_value( $attr['headingFontSizeMobile'], 'px' ),
					'padding-top'    => self::get_css_value( $attr['headingTopPaddingMobile'], 'px' ) . '!important',
					'padding-bottom' => self::get_css_value( $attr['headingBottomPaddingMobile'], 'px' ) . '!important',
					'padding-left'   => self::get_css_value( $attr['headingLeftPaddingMobile'], 'px' ) . '!important',
					'padding-right'  => self::get_css_value( $attr['headingRightPaddingMobile'], 'px' ) . '!important',
					'margin-top'     => self::get_css_value( $attr['headingTopMarginMobile'], 'px' ) . '!important',
					'margin-bottom'  => self::get_css_value( $attr['headingBottomMarginMobile'], 'px' ) . '!important',
					'margin-left'    => self::get_css_value( $attr['headingLeftMarginMobile'], 'px' ) . '!important',
					'margin-right'   => self::get_css_value( $attr['headingRightMarginMobile'], 'px' ) . '!important',
				),
				' .responsive-block-editor-addons-toc__list-wrap' => array(
					'font-size'      => self::get_css_value( $attr['contentFontSizeMobile'], 'px' ),
					'padding-top'    => self::get_css_value( $attr['contentTopPaddingMobile'], 'px' ) . '!important',
					'padding-bottom' => self::get_css_value( $attr['contentBottomPaddingMobile'], 'px' ) . '!important',
					'padding-left'   => self::get_css_value( $attr['contentLeftPaddingMobile'], 'px' ) . '!important',
					'padding-right'  => self::get_css_value( $attr['contentRightPaddingMobile'], 'px' ) . '!important',
					'margin-top'     => self::get_css_value( $attr['contentTopMarginMobile'], 'px' ) . '!important',
					'margin-bottom'  => self::get_css_value( $attr['contentBottomMarginMobile'], 'px' ) . '!important',
					'margin-left'    => self::get_css_value( $attr['contentLeftMarginMobile'], 'px' ) . '!important',
					'margin-right'   => self::get_css_value( $attr['contentRightMarginMobile'], 'px' ) . '!important',
				),
			);

			$tablet_selectors = array(
				' ' => array(
					'padding-top'    => self::get_css_value( $attr['blockTopPaddingTablet'], 'px' ) . '!important',
					'padding-bottom' => self::get_css_value( $attr['blockBottomPaddingTablet'], 'px' ) . '!important',
					'padding-left'   => self::get_css_value( $attr['blockLeftPaddingTablet'], 'px' ) . '!important',
					'padding-right'  => self::get_css_value( $attr['blockRightPaddingTablet'], 'px' ) . '!important',
					'margin-top'     => self::get_css_value( $attr['blockTopMarginTablet'], 'px' ) . '!important',
					'margin-bottom'  => self::get_css_value( $attr['blockBottomMarginTablet'], 'px' ) . '!important',
					'margin-left'    => self::get_css_value( $attr['blockLeftMarginTablet'], 'px' ) . '!important',
					'margin-right'   => self::get_css_value( $attr['blockRightMarginTablet'], 'px' ) . '!important',
				),
				' .responsive-block-editor-addons-toc__title-wrap' => array(
					'font-size'      => self::get_css_value( $attr['headingFontSizeTablet'], 'px' ),
					'padding-top'    => self::get_css_value( $attr['headingTopPaddingTablet'], 'px' ) . '!important',
					'padding-bottom' => self::get_css_value( $attr['headingBottomPaddingTablet'], 'px' ) . '!important',
					'padding-left'   => self::get_css_value( $attr['headingLeftPaddingTablet'], 'px' ) . '!important',
					'padding-right'  => self::get_css_value( $attr['headingRightPaddingTablet'], 'px' ) . '!important',
					'margin-top'     => self::get_css_value( $attr['headingTopMarginTablet'], 'px' ) . '!important',
					'margin-bottom'  => self::get_css_value( $attr['headingBottomMarginTablet'], 'px' ) . '!important',
					'margin-left'    => self::get_css_value( $attr['headingLeftMarginTablet'], 'px' ) . '!important',
					'margin-right'   => self::get_css_value( $attr['headingRightMarginTablet'], 'px' ) . '!important',
				),
				' .responsive-block-editor-addons-toc__list-wrap' => array(
					'font-size'      => self::get_css_value( $attr['contentFontSizeTablet'], 'px' ),
					'padding-top'    => self::get_css_value( $attr['contentTopPaddingTablet'], 'px' ) . '!important',
					'padding-bottom' => self::get_css_value( $attr['contentBottomPaddingTablet'], 'px' ) . '!important',
					'padding-left'   => self::get_css_value( $attr['contentLeftPaddingTablet'], 'px' ) . '!important',
					'padding-right'  => self::get_css_value( $attr['contentRightPaddingTablet'], 'px' ) . '!important',
					'margin-top'     => self::get_css_value( $attr['contentTopMarginTablet'], 'px' ) . '!important',
					'margin-bottom'  => self::get_css_value( $attr['contentBottomMarginTablet'], 'px' ) . '!important',
					'margin-left'    => self::get_css_value( $attr['contentLeftMarginTablet'], 'px' ) . '!important',
					'margin-right'   => self::get_css_value( $attr['contentRightMarginTablet'], 'px' ) . '!important',
				),
			);

			$combined_selectors = array(
				'desktop' => $selectors,
				'tablet'  => $tablet_selectors,
				'mobile'  => $mobile_selectors,
			);

			$id  = '.responsive-block-editor-addons-block-table-of-contents.block-' . $id;
			$css = Responsive_Block_Editor_Addons_Frontend_Styles_Helper::responsive_block_editor_addons_generate_all_css( $combined_selectors, $id );

			return $css;
		}

		/**
		 * Get Defaults for Table Of Contents block
		 *
		 * @return array
		 */
		public static function get_responsive_block_table_of_contents_default_attributes() {

			return array(
				'block_id'                   => '',
				'classMigrate'               => false,
				'isCollapsible'              => false,
				'icon'                       => 'fa-angle-down',
				'heading'                    => 'Table Of Contents',
				'headingTitle'               => 'Table Of Contents',
				'borderStyle'                => 'solid',
				'borderWidth'                => 1,
				'borderRadius'               => 0,
				'borderColor'                => '#333',
				'blockTopPadding'            => 0,
				'blockTopPaddingMobile'      => 0,
				'blockTopPaddingTablet'      => 0,
				'blockBottomPadding'         => 0,
				'blockBottomPaddingTablet'   => 0,
				'blockBottomPaddingMobile'   => 0,
				'blockRightPadding'          => 0,
				'blockRightPaddingMobile'    => 0,
				'blockRightPaddingTablet'    => 0,
				'blockLeftPadding'           => 0,
				'blockLeftPaddingTablet'     => 0,
				'blockLeftPaddingMobile'     => 0,
				'blockTopMargin'             => 0,
				'blockTopMarginMobile'       => 0,
				'blockTopMarginTablet'       => 0,
				'blockBottomMargin'          => 0,
				'blockBottomMarginTablet'    => 0,
				'blockBottomMarginMobile'    => 0,
				'blockRightMargin'           => 0,
				'blockRightMarginMobile'     => 0,
				'blockRightMarginTablet'     => 0,
				'blockLeftMargin'            => 0,
				'blockLeftMarginTablet'      => 0,
				'blockLeftMarginMobile'      => 0,
				'zIndex'                     => 0,
				'headingFontFamily'          => '',
				'headingFontWeight'          => '500',
				'headingFontSize'            => 16,
				'headingFontSizeTablet'      => 16,
				'headingFontSizeMobile'      => 14,
				'headingLineHeight'          => 1,
				'contentFontFamily'          => '',
				'contentFontWeight'          => '500',
				'contentFontSize'            => 16,
				'contentFontSizeTablet'      => 16,
				'contentFontSizeMobile'      => 14,
				'contentLineHeight'          => 1.8,
				'align'                      => 'left',
				'headingColor'               => '#fff',
				'headingBgColor'             => '#0984ff',
				'headingColorHover'          => '',
				'headingBgColorHover'        => '',
				'bodyColor'                  => '',
				'bodyBgColor'                => '#fff',
				'bodyColorHover'             => '',
				'bodyBgColorHover'           => '',
				'headingTopPadding'          => 15,
				'headingTopPaddingMobile'    => 15,
				'headingTopPaddingTablet'    => 15,
				'headingBottomPadding'       => 15,
				'headingBottomPaddingTablet' => 15,
				'headingBottomPaddingMobile' => 15,
				'headingRightPadding'        => 20,
				'headingRightPaddingMobile'  => 20,
				'headingRightPaddingTablet'  => 20,
				'headingLeftPadding'         => 20,
				'headingLeftPaddingTablet'   => 20,
				'headingLeftPaddingMobile'   => 20,
				'headingTopMargin'           => 0,
				'headingTopMarginMobile'     => 0,
				'headingTopMarginTablet'     => 0,
				'headingBottomMargin'        => 0,
				'headingBottomMarginTablet'  => 0,
				'headingBottomMarginMobile'  => 0,
				'headingRightMargin'         => 0,
				'headingRightMarginMobile'   => 0,
				'headingRightMarginTablet'   => 0,
				'headingLeftMargin'          => 0,
				'headingLeftMarginTablet'    => 0,
				'headingLeftMarginMobile'    => 0,
				'contentTopPadding'          => 15,
				'contentTopPaddingMobile'    => 15,
				'contentTopPaddingTablet'    => 15,
				'contentBottomPadding'       => 15,
				'contentBottomPaddingTablet' => 15,
				'contentBottomPaddingMobile' => 15,
				'contentRightPadding'        => 20,
				'contentRightPaddingMobile'  => 20,
				'contentRightPaddingTablet'  => 20,
				'contentLeftPadding'         => 20,
				'contentLeftPaddingTablet'   => 20,
				'contentLeftPaddingMobile'   => 20,
				'contentTopMargin'           => 0,
				'contentTopMarginMobile'     => 0,
				'contentTopMarginTablet'     => 0,
				'contentBottomMargin'        => 0,
				'contentBottomMarginTablet'  => 0,
				'contentBottomMarginMobile'  => 0,
				'contentRightMargin'         => 0,
				'contentRightMarginMobile'   => 0,
				'contentRightMarginTablet'   => 0,
				'contentLeftMargin'          => 0,
				'contentLeftMarginTablet'    => 0,
				'contentLeftMarginMobile'    => 0,
				'blockWidth'                 => 100,
				'blockBorderStyle'           => 'solid',
				'blockBorderWidth'           => 1,
				'blockBorderRadius'          => '',
				'blockBorderColor'           => '#0984ff',
				'headingBorderStyle'         => '',
				'headingBorderWidth'         => 1,
				'headingBorderRadius'        => 0,
				'headingBorderColor'         => '',
				'bodyBorderStyle'            => '',
				'bodyBorderWidth'            => 1,
				'bodyBorderRadius'           => 0,
				'bodyBorderColor'            => '',
				'tableType'                  => 'unordered',
				'orderListType'              => 'none',
				'headerLayout'               => 'fill',
				'backgroundImage'            => '',
				'backgroundPosition'         => 'center-center',
				'backgroundSize'             => 'cover',
				'backgroundRepeat'           => 'no-repeat',
				'backgroundColor'            => '',
				'backgroundVideo'            => '',
				'backgroundType'             => 'none',
			);
		}

		/**
		 * Get How-To Schema Block CSS
		 *
		 * @param array  $attr The block attributes.
		 * @param string $id The selector ID.
		 * @return array Styles.
		 */
		public static function get_responsive_block_how_to_css( $attr, $id ) {
			$defaults = self::get_responsive_block_how_to_default_attributes();
			$attr     = array_merge( $defaults, (array) $attr );

			$mobile_selectors = array();
			$tablet_selectors = array();

			$border     = 'none' !== $attr['borderStyle'] ? self::get_css_value( $attr['borderWidth'], 'px' ) . ' ' . $attr['borderStyle'] . ' ' . $attr['borderColor'] : '';
			$border_rad = 'none' !== $attr['borderStyle'] ? self::get_css_value( $attr['borderRadius'], 'px' ) : '';

			$selectors = array(
				'' => array(
					'text-align' => $attr['overallAlignment'],
					'padding'    => self::get_css_value( 10, 'px' ),
				),
				' .responsive-block-editor-addons-block-how-to-main-heading' => array(
					'color'          => $attr['mainHeadingColor'],
					'font-family'    => $attr['mainHeadingFontFamily'],
					'font-size'      => self::get_css_value( $attr['mainHeadingFontSize'], 'px' ),
					'font-weight'    => $attr['mainHeadingFontWeight'],
					'margin-bottom'  => self::get_css_value( $attr['rowGap'], 'px' ),
					'letter-spacing' => self::get_css_value( $attr['mainHeadingLetterSpacing'], 'px' ),
					'line-height'    => $attr['mainHeadingLineHeight'],
				),
				' .responsive-block-editor-addons-block-how-to-sub-heading-time' => array(
					'color'          => $attr['secondaryHeadingColor'],
					'display'        => 'inline',
					'letter-spacing' => self::get_css_value( $attr['subHeadingLetterSpacing'], 'px' ),
					'line-height'    => $attr['subHeadingLineHeight'],
					'font-family'    => $attr['subHeadingFontFamily'],
					'font-size'      => self::get_css_value( $attr['subHeadingFontSize'], 'px' ),
					'font-weight'    => $attr['subHeadingFontWeight'],
					'margin-right'   => self::get_css_value( $attr['timeMargin'], 'px' ),
				),
				' .responsive-block-editor-addons-block-how-to-time-value' => array(
					'color'          => $attr['descriptionColor'],
					'letter-spacing' => self::get_css_value( $attr['descriptionLetterSpacing'], 'px' ),
					'line-height'    => $attr['descriptionLineHeight'],
					'font-family'    => $attr['descriptionFontFamily'],
					'font-size'      => self::get_css_value( $attr['descriptionFontSize'], 'px' ),
					'font-weight'    => $attr['descriptionFontWeight'],
				),
				' .responsive-block-editor-addons-block-how-to-sub-heading-cost' => array(
					'color'          => $attr['secondaryHeadingColor'],
					'display'        => 'inline',
					'letter-spacing' => self::get_css_value( $attr['subHeadingLetterSpacing'], 'px' ),
					'line-height'    => $attr['subHeadingLineHeight'],
					'font-family'    => $attr['subHeadingFontFamily'],
					'font-size'      => self::get_css_value( $attr['subHeadingFontSize'], 'px' ),
					'font-weight'    => $attr['subHeadingFontWeight'],
					'margin-right'   => self::get_css_value( $attr['costMargin'], 'px' ),
				),
				' .responsive-block-editor-addons-block-how-to-cost-value' => array(
					'color'          => $attr['descriptionColor'],
					'display'        => 'inline',
					'letter-spacing' => self::get_css_value( $attr['descriptionLetterSpacing'], 'px' ),
					'line-height'    => $attr['descriptionLineHeight'],
					'font-family'    => $attr['descriptionFontFamily'],
					'font-size'      => self::get_css_value( $attr['descriptionFontSize'], 'px' ),
					'font-weight'    => $attr['descriptionFontWeight'],
				),
				' .responsive-block-editor-addons-block-how-to-sub-heading-tools' => array(
					'color'          => $attr['secondaryHeadingColor'],
					'letter-spacing' => self::get_css_value( $attr['subHeadingLetterSpacing'], 'px' ),
					'line-height'    => $attr['subHeadingLineHeight'],
					'font-family'    => $attr['subHeadingFontFamily'],
					'font-size'      => self::get_css_value( $attr['subHeadingFontSize'], 'px' ),
					'font-weight'    => $attr['subHeadingFontWeight'],
				),
				' .responsive-block-editor-addons-block-how-to-tools-list-item' => array(
					'color'          => $attr['descriptionColor'],
					'letter-spacing' => self::get_css_value( $attr['descriptionLetterSpacing'], 'px' ),
					'font-family'    => $attr['descriptionFontFamily'],
					'font-size'      => self::get_css_value( $attr['descriptionFontSize'], 'px' ),
					'font-weight'    => $attr['descriptionFontWeight'],
					'list-style'     => $attr['toolsListStyle'],
				),
				' .responsive-block-editor-addons-block-how-to-sub-heading-materials' => array(
					'color'          => $attr['secondaryHeadingColor'],
					'letter-spacing' => self::get_css_value( $attr['subHeadingLetterSpacing'], 'px' ),
					'line-height'    => $attr['subHeadingLineHeight'],
					'font-family'    => $attr['subHeadingFontFamily'],
					'font-size'      => self::get_css_value( $attr['subHeadingFontSize'], 'px' ),
					'font-weight'    => $attr['subHeadingFontWeight'],
				),
				' .responsive-block-editor-addons-block-how-to-materials-list-item' => array(
					'color'          => $attr['descriptionColor'],
					'letter-spacing' => self::get_css_value( $attr['descriptionLetterSpacing'], 'px' ),
					'font-family'    => $attr['descriptionFontFamily'],
					'font-size'      => self::get_css_value( $attr['descriptionFontSize'], 'px' ),
					'font-weight'    => $attr['descriptionFontWeight'],
					'list-style'     => $attr['materialsListStyle'],
				),
				' .responsive-block-editor-addons-block-how-to-sub-heading-steps' => array(
					'color'          => $attr['secondaryHeadingColor'],
					'display'        => 'inline',
					'letter-spacing' => self::get_css_value( $attr['subHeadingLetterSpacing'], 'px' ),
					'line-height'    => $attr['subHeadingLineHeight'],
					'font-family'    => $attr['subHeadingFontFamily'],
					'font-size'      => self::get_css_value( $attr['subHeadingFontSize'], 'px' ),
					'font-weight'    => $attr['subHeadingFontWeight'],
				),
				' .responsive-block-editor-addons-block-how-to-time-area' => array(
					'margin-top' => self::get_css_value( $attr['rowGap'], 'px' ),
				),
				' .responsive-block-editor-addons-block-how-to-cost-area' => array(
					'margin-top' => self::get_css_value( $attr['rowGap'], 'px' ),
				),
				' .responsive-block-editor-addons-block-how-to-tools-area' => array(
					'margin-top' => self::get_css_value( $attr['rowGap'], 'px' ),
				),
				' .responsive-block-editor-addons-block-how-to-materials-area' => array(
					'margin-top' => self::get_css_value( $attr['rowGap'], 'px' ),
				),
				' .responsive-block-editor-addons-block-how-to-steps-area' => array(
					'margin-top' => self::get_css_value( $attr['rowGap'], 'px' ),
				),
				' .responsive-block-editor-addons-block-how-to-main-image' => array(
					'border'        => $border,
					'border-radius' => $border_rad,
				),
				' .responsive-block-editor-addons-block-how-to-steps .wp-block-responsive-block-editor-addons-info-block' => array(
					'margin-top' => self::get_css_value( $attr['stepsMargin'], 'px' ),
				),
			);

			$mobile_selectors = array(
				' .responsive-block-editor-addons-block-how-to-main-heading' => array(
					'line-height'   => $attr['mainHeadingLineHeight'],
					'font-size'     => self::get_css_value( $attr['mainHeadingFontSizeMobile'], 'px' ),
					'margin-bottom' => self::get_css_value( $attr['rowGapMobile'], 'px' ),
				),
				' .responsive-block-editor-addons-block-how-to-sub-heading-time' => array(
					'line-height'  => $attr['subHeadingLineHeight'],
					'font-weight'  => self::get_css_value( $attr['subHeadingFontSizeMobile'], 'px' ),
					'margin-right' => self::get_css_value( $attr['timeMarginMobile'], 'px' ),
				),
				' .responsive-block-editor-addons-block-how-to-sub-heading-cost' => array(
					'line-height'  => $attr['subHeadingLineHeight'],
					'font-weight'  => self::get_css_value( $attr['subHeadingFontSizeMobile'], 'px' ),
					'margin-right' => self::get_css_value( $attr['costMarginMobile'], 'px' ),
				),
				' .responsive-block-editor-addons-block-how-to-sub-heading-tools' => array(
					'line-height' => $attr['subHeadingLineHeight'],
					'font-weight' => self::get_css_value( $attr['subHeadingFontSizeMobile'], 'px' ),
				),
				' .responsive-block-editor-addons-block-how-to-sub-heading-materials' => array(
					'line-height' => $attr['subHeadingLineHeight'],
					'font-weight' => self::get_css_value( $attr['subHeadingFontSizeMobile'], 'px' ),
				),
				' .responsive-block-editor-addons-block-how-to-sub-heading-steps' => array(
					'line-height' => $attr['subHeadingLineHeight'],
					'font-weight' => self::get_css_value( $attr['subHeadingFontSizeMobile'], 'px' ),
				),
				' .responsive-block-editor-addons-block-how-to-time-value' => array(
					'line-height' => $attr['descriptionLineHeight'],
					'font-size'   => self::get_css_value( $attr['descriptionFontSizeMobile'], 'px' ),
				),
				' .responsive-block-editor-addons-block-how-to-cost-value' => array(
					'line-height' => $attr['descriptionLineHeight'],
					'font-size'   => self::get_css_value( $attr['descriptionFontSizeMobile'], 'px' ),
				),
				' .responsive-block-editor-addons-block-how-to-tools-list-item' => array(
					'line-height' => $attr['descriptionLineHeight'],
					'font-size'   => self::get_css_value( $attr['descriptionFontSizeMobile'], 'px' ),
				),
				' .responsive-block-editor-addons-block-how-to-materials-list-item' => array(
					'line-height' => $attr['descriptionLineHeight'],
					'font-size'   => self::get_css_value( $attr['descriptionFontSizeMobile'], 'px' ),
				),
				' .responsive-block-editor-addons-block-how-to-time-area' => array(
					'margin-top' => self::get_css_value( $attr['rowGapMobile'], 'px' ),
				),
				' .responsive-block-editor-addons-block-how-to-cost-area' => array(
					'margin-top' => self::get_css_value( $attr['rowGapMobile'], 'px' ),
				),
				' .responsive-block-editor-addons-block-how-to-tools-area' => array(
					'margin-top' => self::get_css_value( $attr['rowGapMobile'], 'px' ),
				),
				' .responsive-block-editor-addons-block-how-to-materials-area' => array(
					'margin-top' => self::get_css_value( $attr['rowGapMobile'], 'px' ),
				),
				' .responsive-block-editor-addons-block-how-to-steps-area' => array(
					'margin-top' => self::get_css_value( $attr['rowGapMobile'], 'px' ),
				),
				' .responsive-block-editor-addons-block-how-to-steps .wp-block-responsive-block-editor-addons-info-block' => array(
					'margin-top' => self::get_css_value( $attr['stepsMarginMobile'], 'px' ),
				),

			);

			$tablet_selectors = array(
				' .responsive-block-editor-addons-block-how-to-main-heading' => array(
					'line-height'   => $attr['mainHeadingLineHeight'],
					'font-size'     => self::get_css_value( $attr['mainHeadingFontSizeTablet'], 'px' ),
					'margin-bottom' => self::get_css_value( $attr['rowGapTablet'], 'px' ),
				),
				' .responsive-block-editor-addons-block-how-to-sub-heading-time' => array(
					'line-height'  => $attr['subHeadingLineHeight'],
					'font-weight'  => self::get_css_value( $attr['subHeadingFontSizeTablet'], 'px' ),
					'margin-right' => self::get_css_value( $attr['timeMarginTablet'], 'px' ),
				),
				' .responsive-block-editor-addons-block-how-to-sub-heading-cost' => array(
					'line-height'  => $attr['subHeadingLineHeight'],
					'font-weight'  => self::get_css_value( $attr['subHeadingFontSizeTablet'], 'px' ),
					'margin-right' => self::get_css_value( $attr['costMarginTablet'], 'px' ),
				),
				' .responsive-block-editor-addons-block-how-to-sub-heading-tools' => array(
					'line-height' => $attr['subHeadingLineHeight'],
					'font-weight' => self::get_css_value( $attr['subHeadingFontSizeTablet'], 'px' ),
				),
				' .responsive-block-editor-addons-block-how-to-sub-heading-materials' => array(
					'line-height' => $attr['subHeadingLineHeight'],
					'font-weight' => self::get_css_value( $attr['subHeadingFontSizeTablet'], 'px' ),
				),
				' .responsive-block-editor-addons-block-how-to-sub-heading-steps' => array(
					'line-height' => $attr['subHeadingLineHeight'],
					'font-weight' => self::get_css_value( $attr['subHeadingFontSizeTablet'], 'px' ),
				),
				' .responsive-block-editor-addons-block-how-to-time-value' => array(
					'line-height' => $attr['descriptionLineHeight'],
					'font-size'   => self::get_css_value( $attr['descriptionFontSizeTablet'], 'px' ),
				),
				' .responsive-block-editor-addons-block-how-to-cost-value' => array(
					'line-height' => $attr['descriptionLineHeight'],
					'font-size'   => self::get_css_value( $attr['descriptionFontSizeTablet'], 'px' ),
				),
				' .responsive-block-editor-addons-block-how-to-tools-list-item' => array(
					'line-height' => $attr['descriptionLineHeight'],
					'font-size'   => self::get_css_value( $attr['descriptionFontSizeTablet'], 'px' ),
				),
				' .responsive-block-editor-addons-block-how-to-materials-list-item' => array(
					'line-height' => $attr['descriptionLineHeight'],
					'font-size'   => self::get_css_value( $attr['descriptionFontSizeTablet'], 'px' ),
				),
				' .responsive-block-editor-addons-block-how-to-time-area' => array(
					'margin-top' => self::get_css_value( $attr['rowGapTablet'], 'px' ),
				),
				' .responsive-block-editor-addons-block-how-to-cost-area' => array(
					'margin-top' => self::get_css_value( $attr['rowGapTablet'], 'px' ),
				),
				' .responsive-block-editor-addons-block-how-to-tools-area' => array(
					'margin-top' => self::get_css_value( $attr['rowGapTablet'], 'px' ),
				),
				' .responsive-block-editor-addons-block-how-to-materials-area' => array(
					'margin-top' => self::get_css_value( $attr['rowGapTablet'], 'px' ),
				),
				' .responsive-block-editor-addons-block-how-to-steps-area' => array(
					'margin-top' => self::get_css_value( $attr['rowGapTablet'], 'px' ),
				),
				' .responsive-block-editor-addons-block-how-to-steps .wp-block-responsive-block-editor-addons-info-block' => array(
					'margin-top' => self::get_css_value( $attr['stepsMarginTablet'], 'px' ),
				),
			);

			$combined_selectors = array(
				'desktop' => $selectors,
				'tablet'  => $tablet_selectors,
				'mobile'  => $mobile_selectors,
			);

			$id  = '.responsive-block-editor-addons-block-how-to.block-' . $id;
			$css = Responsive_Block_Editor_Addons_Frontend_Styles_Helper::responsive_block_editor_addons_generate_all_css( $combined_selectors, $id );
			return $css;
		}

		/**
		 * Get Defaults for how to block
		 *
		 * @return array
		 */
		public static function get_responsive_block_how_to_default_attributes() {
			return array(
				'block_id'                    => '',
				'overallAlignment'            => 'left',
				'mainHeadingColor'            => '',
				'secondaryHeadingColor'       => '',
				'descriptionColor'            => '',
				'mainHeadingFontFamily'       => '',
				'mainHeadingFontSize'         => 12,
				'mainHeadingFontSizeMobile'   => 12,
				'mainHeadingFontSizeTablet'   => 14,
				'mainHeadingFontWeight'       => '',
				'mainHeadingLineHeight'       => 0,
				'mainHeadingLineHeightMobile' => 0,
				'mainHeadingLineHeightTablet' => 0,
				'mainHeadingLetterSpacing'    => 0,
				'subHeadingFontFamily'        => '',
				'subHeadingFontSize'          => 12,
				'subHeadingFontSizeMobile'    => 12,
				'subHeadingFontSizeTablet'    => 14,
				'subHeadingFontWeight'        => '',
				'subHeadingLineHeight'        => 0,
				'subHeadingLineHeightMobile'  => 0,
				'subHeadingLineHeightTablet'  => 0,
				'subHeadingLetterSpacing'     => 0,
				'descriptionFontFamily'       => '',
				'descriptionFontSize'         => 12,
				'descriptionFontSizeMobile'   => 12,
				'descriptionFontSizeTablet'   => 14,
				'descriptionFontWeight'       => '',
				'descriptionLineHeight'       => 0,
				'descriptionLineHeightMobile' => 0,
				'descriptionLineHeightTablet' => 0,
				'descriptionLetterSpacing'    => 0,
				'timeMargin'                  => 10,
				'costMargin'                  => 10,
				'rowGap'                      => 10,
				'timeMarginMobile'            => 10,
				'costMarginMobile'            => 10,
				'rowGapMobile'                => 10,
				'timeMarginTablet'            => 10,
				'costMarginTablet'            => 10,
				'rowGapTablet'                => 10,
				'toolsListStyle'              => 'none',
				'materialsListStyle'          => 'none',
				'borderStyle'                 => 'none',
				'borderRadius'                => 0,
				'borderWidth'                 => 1,
				'borderColor'                 => '',
				'stepsMargin'                 => 10,
				'stepsMarginMobile'           => 10,
				'stepsMarginTablet'           => 10,
			);
		}

		/**
		 * Get Inline Notice Block CSS
		 *
		 * @param array  $attr The block attributes.
		 * @param string $id The selector ID.
		 * @return array Styles.
		 */
		public static function get_responsive_block_inline_notice_css( $attr, $id ) {
			$defaults      = self::get_responsive_block_inline_notice_default_attributes();
			$attr          = array_merge( $defaults, (array) $attr );
			$base_selector = '.responsive-block-editor-addons-block-inline-notice.block-';
			$selector      = $base_selector . $id;
			$js_attr       = array(
				'cookies_id'         => $attr['cookies_id'],
				'cookies'            => $attr['cookies'],
				'close_cookies_days' => $attr['close_cookies_days'],
				'noticeDismiss'      => $attr['noticeDismiss'],
			);
			?>

			<script>
				( function( $ ) {

					RBEAInlineNotice = {

						_run: function( attr, id ) {

							if ( $( id ).length === 0 ) {
								return;
							}

							var unique_id = attr['cookies_id'];
							var is_cookie = attr['cookies'];
							var cookies_days = attr['close_cookies_days'];
							var current_cookie = Cookies.get( 'rbea-notice-' + unique_id );

							if( 'undefined' === typeof current_cookie && true === is_cookie ){
								$( id ).show()
							}

							if ( attr['noticeDismiss'] !== '' ) {
								$( id + " .rbea-notice-dismiss" ).on( 'click',function() {
									if ( true === is_cookie && 'undefined' !== typeof current_cookie) {
										current_cookies = Cookies.set( 'rbea-notice-' + unique_id, true, { expires: cookies_days } );
									$( id ).addClass("rbea-notice__active").css('display' ,'none')
									}

									if( 'undefined' === typeof current_cookie && true === is_cookie ){
										current_cookies = Cookies.set( 'rbea-notice-' + unique_id, true, { expires: cookies_days } );
										$( id ).addClass("rbea-notice__active").css('display' ,'none')
									}

									if( false === is_cookie ){
										$( id ).addClass("rbea-notice__active").css('display' ,'none')
									}
								});
							}
						}
					}
					$( document ).ready(function() {
						RBEAInlineNotice._run( <?php echo wp_json_encode( $js_attr ); ?>, '<?php echo esc_attr( $selector ); ?>' );
					})
				})( jQuery )
			</script>

			<?php

			$mobile_selectors = array();
			$tablet_selectors = array();

			$notice = $attr['noticeType'];
			switch ( $notice ) {
				case 'warning':
					$notice_type_color = '#ffd54f';
					break;
				case 'info':
					$notice_type_color = '#2091e1';
					break;
				case 'error':
					$notice_type_color = '#d9534f';
					break;
				case 'notification':
					$notice_type_color = '#40ba7b';
					break;
				default:
					$notice_type_color = $attr['noticeBoxColor'];
			}

			$notice_area_border = 'simple' === $attr['layout'] ? '1px solid ' . $notice_type_color : 'none';
			$modern_border      = '1px solid ' . $notice_type_color;
			$border_values      = 'modern' === $attr['layout'] ? $modern_border : 'none';
			$border_left        = 'simple' === $attr['layout'] ? '5px solid ' . $notice_type_color : $modern_border;
			$notice_bg_color    = 'modern' === $attr['layout'] ? $notice_type_color : '';

			$selectors = array(
				' ' => array(
					'text-align' => $attr['noticeAlignment'],
					'padding'    => '' . self::get_css_value( $attr['blockTopPadding'], 'px' ) . ' ' . self::get_css_value( $attr['blockRightPadding'], 'px' ) . ' ' . self::get_css_value( $attr['blockBottomPadding'], 'px' ) . ' ' . self::get_css_value( $attr['blockLeftPadding'], 'px' ) . ' !important',
					'margin'     => '' . self::get_css_value( $attr['blockTopMargin'], 'px' ) . ' ' . self::get_css_value( $attr['blockRightMargin'], 'px' ) . ' ' . self::get_css_value( $attr['blockBottomMargin'], 'px' ) . ' ' . self::get_css_value( $attr['blockLeftMargin'], 'px' ) . ' !important',
					'z-index'    => $attr['blockZIndex'],
				),
				' .responsive-block-editor-addons-block-inline-notice-title-area' => array(
					'background-color' => $notice_type_color,
					'border-left'      => $notice_area_border,
					'display'          => 'flex',
					'align-items'      => 'center',
					'justify-content'  => 'space-between',
				),
				' .responsive-block-editor-addons-block-inline-notice-title' => array(
					'color'          => $attr['titleColor'],
					'font-family'    => $attr['titleFontFamily'],
					'font-size'      => self::get_css_value( $attr['titleFontSize'], 'px' ),
					'font-weight'    => $attr['titleFontWeight'],
					'line-height'    => $attr['titleLineHeight'],
					'letter-spacing' => self::get_css_value( $attr['titleLetterSpacing'], 'px' ),
					'margin'         => 0,
					'padding'        => '' . self::get_css_value( $attr['titlePaddingVertical'], 'px' ) . ' ' . self::get_css_value( $attr['titlePaddingHorizontal'], 'px' ),
				),
				' .responsive-block-editor-addons-block-inline-notice-svg > svg' => array(
					'height'       => '17px',
					'width'        => '17px',
					'margin-right' => '10px',
				),
				' .responsive-block-editor-addons-block-inline-notice-content-area' => array(
					'background-color' => $attr['contentBgColor'],
					'border-bottom'    => $border_values,
					'border-left'      => $border_left,
					'border-right'     => $border_values,
				),
				' .responsive-block-editor-addons-block-inline-notice-content' => array(
					'color'          => $attr['contentColor'],
					'font-family'    => $attr['contentFontFamily'],
					'font-size'      => self::get_css_value( $attr['contentFontSize'], 'px' ),
					'font-weight'    => $attr['contentFontWeight'],
					'line-height'    => $attr['contentLineHeight'],
					'letter-spacing' => self::get_css_value( $attr['contentLetterSpacing'], 'px' ),
					'padding'        => '' . self::get_css_value( $attr['contentPaddingVertical'], 'px' ) . ' ' . self::get_css_value( $attr['contentPaddingHorizontal'], 'px' ),
				),
			);

			$mobile_selectors = array(
				' ' => array(
					'padding' => '' . self::get_css_value( $attr['blockTopPaddingMobile'], 'px' ) . ' ' . self::get_css_value( $attr['blockRightPaddingMobile'], 'px' ) . ' ' . self::get_css_value( $attr['blockBottomPaddingMobile'], 'px' ) . ' ' . self::get_css_value( $attr['blockLeftPaddingMobile'], 'px' ) . ' !important',
					'margin'  => '' . self::get_css_value( $attr['blockTopMarginMobile'], 'px' ) . ' ' . self::get_css_value( $attr['blockRightMarginMobile'], 'px' ) . ' ' . self::get_css_value( $attr['blockBottomMarginMobile'], 'px' ) . ' ' . self::get_css_value( $attr['blockLeftMarginMobile'], 'px' ) . ' !important',
				),
				' .responsive-block-editor-addons-block-inline-notice-title' => array(
					'font-size' => self::get_css_value( $attr['titleFontSizeMobile'], 'px' ),
					'padding'   => '' . self::get_css_value( $attr['titlePaddingVerticalMobile'], 'px' ) . ' ' . self::get_css_value( $attr['titlePaddingHorizontalMobile'], 'px' ),
				),
				' .responsive-block-editor-addons-block-inline-notice-content' => array(
					'font-size' => self::get_css_value( $attr['contentFontSizeMobile'], 'px' ),
					'padding'   => '' . self::get_css_value( $attr['contentPaddingVerticalMobile'], 'px' ) . ' ' . self::get_css_value( $attr['contentPaddingHorizontalMobile'], 'px' ),
				),
			);

			$tablet_selectors = array(
				' ' => array(
					'padding' => '' . self::get_css_value( $attr['blockTopPaddingTablet'], 'px' ) . ' ' . self::get_css_value( $attr['blockRightPaddingTablet'], 'px' ) . ' ' . self::get_css_value( $attr['blockBottomPaddingTablet'], 'px' ) . ' ' . self::get_css_value( $attr['blockLeftPaddingTablet'], 'px' ) . ' !important',
					'margin'  => '' . self::get_css_value( $attr['blockTopMarginTablet'], 'px' ) . ' ' . self::get_css_value( $attr['blockRightMarginTablet'], 'px' ) . ' ' . self::get_css_value( $attr['blockBottomMarginTablet'], 'px' ) . ' ' . self::get_css_value( $attr['blockLeftMarginTablet'], 'px' ) . ' !important',
				),
				' .responsive-block-editor-addons-block-inline-notice-title' => array(
					'font-size' => self::get_css_value( $attr['titleFontSizeTablet'], 'px' ),
					'padding'   => '' . self::get_css_value( $attr['titlePaddingVerticalTablet'], 'px' ) . ' ' . self::get_css_value( $attr['titlePaddingHorizontalTablet'], 'px' ),
				),
				' .responsive-block-editor-addons-block-inline-notice-content' => array(
					'font-size' => self::get_css_value( $attr['contentFontSizeTablet'], 'px' ),
					'padding'   => '' . self::get_css_value( $attr['contentPaddingVerticalTablet'], 'px' ) . ' ' . self::get_css_value( $attr['contentPaddingHorizontalTablet'], 'px' ),
				),
			);

			$combined_selectors = array(
				'desktop' => $selectors,
				'tablet'  => $tablet_selectors,
				'mobile'  => $mobile_selectors,
			);

			$id  = '.responsive-block-editor-addons-block-inline-notice.block-' . $id;
			$css = Responsive_Block_Editor_Addons_Frontend_Styles_Helper::responsive_block_editor_addons_generate_all_css( $combined_selectors, $id );
			return $css;
		}

		/**
		 * Get Defaults for inline notice block
		 *
		 * @return array
		 */
		public static function get_responsive_block_inline_notice_default_attributes() {
			return array(
				'block_id'                       => '',
				'blockTopPadding'                => 20,
				'blockBottomPadding'             => 20,
				'blockLeftPadding'               => 20,
				'blockRightPadding'              => 20,
				'blockTopPaddingMobile'          => 20,
				'blockBottomPaddingMobile'       => 20,
				'blockLeftPaddingMobile'         => 20,
				'blockRightPaddingMobile'        => 20,
				'blockTopPaddingTablet'          => 20,
				'blockBottomPaddingTablet'       => 20,
				'blockLeftPaddingTablet'         => 20,
				'blockRightPaddingTablet'        => 20,
				'blockTopMargin'                 => 0,
				'blockBottomMargin'              => 0,
				'blockLeftMargin'                => 0,
				'blockRightMargin'               => 0,
				'blockTopMarginMobile'           => 0,
				'blockBottomMarginMobile'        => 0,
				'blockLeftMarginMobile'          => 0,
				'blockRightMarginMobile'         => 0,
				'blockTopMarginTablet'           => 0,
				'blockBottomMarginTablet'        => 0,
				'blockLeftMarginTablet'          => 0,
				'blockRightMarginTablet'         => 0,
				'blockZIndex'                    => 0,
				'cookies'                        => false,
				'cookies_id'                     => '',
				'close_cookies_days'             => 1,
				'contentBgColor'                 => '',
				'contentColor'                   => '',
				'contentFontFamily'              => '',
				'contentFontSize'                => 16,
				'contentFontSizeMobile'          => 16,
				'contentFontSizeTablet'          => 16,
				'contentFontWeight'              => '600',
				'contentLineHeight'              => 1,
				'contentLetterSpacing'           => 0,
				'contentPaddingVertical'         => 20,
				'contentPaddingHorizontal'       => 20,
				'contentPaddingVerticalMobile'   => 2,
				'contentPaddingHorizontalMobile' => 2,
				'contentPaddingVerticalTablet'   => 5,
				'contentPaddingHorizontalTablet' => 5,
				'headingTag'                     => 'h4',
				'layout'                         => 'modern',
				'noticeAlignment'                => 'left',
				'noticeBoxColor'                 => '#FFD54F',
				'noticeContent'                  => '',
				'noticeDismiss'                  => '',
				'noticeType'                     => 'default',
				'noticeTitle'                    => '',
				'titleColor'                     => '',
				'titleFontFamily'                => '',
				'titleFontSize'                  => 20,
				'titleFontSizeMobile'            => 20,
				'titleFontSizeTablet'            => 20,
				'titleFontWeight'                => '600',
				'titleLineHeight'                => 1,
				'titleLetterSpacing'             => 0,
				'titlePaddingVertical'           => 15,
				'titlePaddingHorizontal'         => 15,
				'titlePaddingVerticalMobile'     => 2,
				'titlePaddingHorizontalMobile'   => 2,
				'titlePaddingVerticalTablet'     => 5,
				'titlePaddingHorizontalTablet'   => 5,
			);
		}

		/**
		 * Get Call/Mail Button CSS
		 *
		 * @param array  $attr The block attributes.
		 * @param string $id The selector ID.
		 * @return array Styles.
		 */
		public static function get_responsive_block_call_mail_button_css( $attr, $id ) {
			$defaults = self::get_responsive_block_call_mail_button_default_attributes();
			$attr     = array_merge( $defaults, (array) $attr );

			$mobile_selectors = array();
			$tablet_selectors = array();

			$button_width_css = 'fit-content';

			$button_width_css        = 'fixed' === $attr['buttonWidthType'] ? 'fit-content' : ( 'flexible' === $attr['buttonWidthType'] ? self::get_css_value( $attr['buttonWidth'], 'px' ) : self::get_css_value( 100, '%' ) );
			$button_width_css_mobile = 'fixed' === $attr['buttonWidthType'] ? 'fit-content' : ( 'flexible' === $attr['buttonWidthType'] ? self::get_css_value( $attr['buttonWidthMobile'], 'px' ) : self::get_css_value( 100, '%' ) );
			$button_width_css_tablet = 'fixed' === $attr['buttonWidthType'] ? 'fit-content' : ( 'flexible' === $attr['buttonWidthType'] ? self::get_css_value( $attr['buttonWidthTablet'], 'px' ) : self::get_css_value( 100, '%' ) );

			$button_border           = '1px solid ' . $attr['buttonColor'];
			$button_border_hover     = '1px solid ' . $attr['buttonColorHover'];
			$button_border_radius    = true === $attr['buttonRounded'] ? self::get_css_value( $attr['buttonRadius'], 'px' ) : 0;
			$text_color              = true === $attr['buttonTransparent'] ? $attr['buttonColor'] : $attr['buttonTextColor'];
			$button_background       = $attr['buttonTransparent'] ? '' : $attr['buttonColor'];
			$text_color_hover        = $attr['buttonTransparent'] ? $attr['buttonColorHover'] : $attr['buttonTextColorHover'];
			$button_background_hover = $attr['buttonTransparent'] ? '' : $attr['buttonColorHover'];

			$justify_content_direction = 'flex-start';
			if ( 'left' === $attr['buttonAlign'] ) {
				$justify_content_direction = 'flex-start';
			} elseif ( 'center' === $attr['buttonAlign'] ) {
				$justify_content_direction = 'center';
			} elseif ( 'right' === $attr['buttonAlign'] ) {
				$justify_content_direction = 'flex-end';
			}

			$selectors = array(
				' ' => array(
					'margin'          => self::get_css_value( $attr['blockTopMargin'], 'px' ) . ' ' . self::get_css_value( $attr['blockRightMargin'], 'px' ) . ' ' . self::get_css_value( $attr['blockBottomMargin'], 'px' ) . ' ' . self::get_css_value( $attr['blockLeftMargin'], 'px' ) . ' !important',
					'padding'         => self::get_css_value( $attr['blockTopPadding'], 'px' ) . ' ' . self::get_css_value( $attr['blockRightPadding'], 'px' ) . ' ' . self::get_css_value( $attr['blockBottomPadding'], 'px' ) . ' ' . self::get_css_value( $attr['blockLeftPadding'], 'px' ) . ' !important',
					'display'         => 'flex',
					'justify-content' => $justify_content_direction,
				),
				' .responsive-block-editor-addons-call-mail-button-button-container' => array(
					'width'            => $button_width_css,
					'border'           => $button_border,
					'background-color' => $button_background,
					'border-radius'    => $button_border_radius,
				),
				' .responsive-block-editor-addons-call-mail-button-text' => array(
					'color'       => $text_color,
					'font-family' => $attr['textFontFamily'],
					'font-size'   => self::get_css_value( $attr['textFontSize'], 'px' ) . ' !important',
					'font-weight' => $attr['textFontWeight'],
					'line-height' => $attr['textLineHeight'],
				),
				' .responsive-block-editor-addons-call-mail-button-icon' => array(
					'fill' => $text_color,
				),
				' .responsive-block-editor-addons-call-mail-button-icon svg' => array(
					'height' => self::get_css_value( $attr['iconSize'], 'px' ) . ' !important',
					'width'  => self::get_css_value( $attr['iconSize'], 'px' ) . ' !important',
				),
				' .responsive-block-editor-addons-call-mail-button-button-container:hover' => array(
					'border'           => $button_border_hover,
					'background-color' => $button_background_hover,
				),
				' .responsive-block-editor-addons-call-mail-button-button-container:hover .responsive-block-editor-addons-call-mail-button-text' => array(
					'color' => $text_color_hover,
				),
				' .responsive-block-editor-addons-call-mail-button-button-container:hover .responsive-block-editor-addons-call-mail-button-icon' => array(
					'fill' => $text_color_hover,
				),
				' .responsive-block-editor-addons-call-mail-button-icon-iconPosition-left' => array(
					'margin-right' => self::get_css_value( $attr['iconTextGap'], 'px' ),
				),
				' .responsive-block-editor-addons-call-mail-button-icon-iconPosition-right' => array(
					'margin-left' => self::get_css_value( $attr['iconTextGap'], 'px' ),
				),
			);

			$mobile_selectors = array(
				' .responsive-block-editor-addons-call-mail-button-button-container' => array(
					'width' => $button_width_css_mobile,
				),
				' .responsive-block-editor-addons-call-mail-button-text' => array(
					'font-size' => self::get_css_value( $attr['textFontSizeMobile'], 'px' ) . ' !important',
				),
				' .responsive-block-editor-addons-call-mail-button-icon svg' => array(
					'height' => self::get_css_value( $attr['iconSizeMobile'], 'px' ) . ' !important',
					'width'  => self::get_css_value( $attr['iconSizeMobile'], 'px' ) . ' !important',
				),
				' ' => array(
					'margin'  => self::get_css_value( $attr['blockTopMarginMobile'], 'px' ) . ' ' . self::get_css_value( $attr['blockRightMarginMobile'], 'px' ) . ' ' . self::get_css_value( $attr['blockBottomMarginMobile'], 'px' ) . ' ' . self::get_css_value( $attr['blockLeftMarginMobile'], 'px' ) . ' !important',
					'padding' => self::get_css_value( $attr['blockTopPaddingMobile'], 'px' ) . ' ' . self::get_css_value( $attr['blockRightPaddingMobile'], 'px' ) . ' ' . self::get_css_value( $attr['blockBottomPaddingMobile'], 'px' ) . ' ' . self::get_css_value( $attr['blockLeftPaddingMobile'], 'px' ) . ' !important',
				),
				' .responsive-block-editor-addons-call-mail-button-icon-iconPosition-left' => array(
					'margin-right' => self::get_css_value( $attr['iconTextGapMobile'], 'px' ),
				),
				' .responsive-block-editor-addons-call-mail-button-icon-iconPosition-right' => array(
					'margin-left' => self::get_css_value( $attr['iconTextGapMobile'], 'px' ),
				),
			);

			$tablet_selectors = array(
				' .responsive-block-editor-addons-call-mail-button-button-container' => array(
					'width' => $button_width_css_tablet,
				),
				' .responsive-block-editor-addons-call-mail-button-text' => array(
					'font-size' => self::get_css_value( $attr['textFontSizeTablet'], 'px' ) . ' !important',
				),
				' .responsive-block-editor-addons-call-mail-button-icon svg' => array(
					'height' => self::get_css_value( $attr['iconSizeTablet'], 'px' ) . ' !important',
					'width'  => self::get_css_value( $attr['iconSizeTablet'], 'px' ) . ' !important',
				),
				' ' => array(
					'margin'  => self::get_css_value( $attr['blockTopMarginTablet'], 'px' ) . ' ' . self::get_css_value( $attr['blockRightMarginTablet'], 'px' ) . ' ' . self::get_css_value( $attr['blockBottomMarginTablet'], 'px' ) . ' ' . self::get_css_value( $attr['blockLeftMarginTablet'], 'px' ) . ' !important',
					'padding' => self::get_css_value( $attr['blockTopPaddingTablet'], 'px' ) . ' ' . self::get_css_value( $attr['blockRightPaddingTablet'], 'px' ) . ' ' . self::get_css_value( $attr['blockBottomPaddingTablet'], 'px' ) . ' ' . self::get_css_value( $attr['blockLeftPaddingTablet'], 'px' ) . ' !important',
				),
				' .responsive-block-editor-addons-call-mail-button-icon-iconPosition-left' => array(
					'margin-right' => self::get_css_value( $attr['iconTextGapTablet'], 'px' ),
				),
				' .responsive-block-editor-addons-call-mail-button-icon-iconPosition-right' => array(
					'margin-left' => self::get_css_value( $attr['iconTextGapTablet'], 'px' ),
				),
			);

			$combined_selectors = array(
				'desktop' => $selectors,
				'tablet'  => $tablet_selectors,
				'mobile'  => $mobile_selectors,
			);

			$id  = '.responsive-block-editor-addons-block-call-mail-button.block-' . $id;
			$css = Responsive_Block_Editor_Addons_Frontend_Styles_Helper::responsive_block_editor_addons_generate_all_css( $combined_selectors, $id );
			return $css;
		}

		/**
		 * Get Defaults for Call/Mail Button block
		 *
		 * @return array
		 */
		public static function get_responsive_block_call_mail_button_default_attributes() {
			return array(
				'block_id'                 => '',
				'buttonWidthType'          => 'fixed',
				'buttonWidth'              => 200,
				'buttonWidthMobile'        => 200,
				'buttonWidthTablet'        => 200,
				'iconSize'                 => '',
				'iconSizeMobile'           => '',
				'iconSizeTablet'           => '',
				'buttonColor'              => '#4aac38',
				'buttonTextColor'          => '#ffffff',
				'buttonColorHover'         => '',
				'buttonTextColorHover'     => '#ffffff',
				'buttonRounded'            => false,
				'buttonTransparent'        => false,
				'buttonRadius'             => 0,
				'textFontFamily'           => '',
				'textFontSize'             => '',
				'textFontSizeMobile'       => '',
				'textFontSizeTablet'       => '',
				'textFontWeight'           => '100',
				'textLineHeight'           => 1,
				'blockTopMargin'           => 10,
				'blockBottomMargin'        => 10,
				'blockLeftMargin'          => 10,
				'blockRightMargin'         => 10,
				'blockTopMarginMobile'     => 10,
				'blockBottomMarginMobile'  => 10,
				'blockLeftMarginMobile'    => 10,
				'blockRightMarginMobile'   => 10,
				'blockTopMarginTablet'     => 10,
				'blockBottomMarginTablet'  => 10,
				'blockLeftMarginTablet'    => 10,
				'blockRightMarginTablet'   => 10,
				'blockTopPadding'          => 10,
				'blockBottomPadding'       => 10,
				'blockLeftPadding'         => 10,
				'blockRightPadding'        => 10,
				'blockTopPaddingMobile'    => 10,
				'blockBottomPaddingMobile' => 10,
				'blockLeftPaddingMobile'   => 10,
				'blockRightPaddingMobile'  => 10,
				'blockTopPaddingTablet'    => 10,
				'blockBottomPaddingTablet' => 10,
				'blockLeftPaddingTablet'   => 10,
				'blockRightPaddingTablet'  => 10,
				'iconTextGap'              => 5,
				'iconTextGapMobile'        => 3,
				'iconTextGapTablet'        => 5,
				'buttonAlign'              => 'left',
			);
		}

		/**
		 * Get Progress Bar block CSS
		 *
		 * @param array  $attr The block attributes.
		 * @param string $id The selector ID.
		 * @return array Styles.
		 */
		public static function get_responsive_block_progress_bar_css( $attr, $id ) {
			$defaults = self::get_responsive_block_progress_bar_default_attributes();
			$attr     = array_merge( $defaults, (array) $attr );

			$mobile_selectors = array();
			$tablet_selectors = array();

			$circle_radius_val      = 0;
			$stroke_dash_array_val  = 0;
			$stroke_dash_offset_val = 0;

			if ( $attr['circularProgressBarSize'] ) {
				$circle_radius_val      = ( $attr['circularProgressBarSize'] / 2 ) - 10;
				$stroke_dash_array_val  = ( $circle_radius_val * 6.27 ) + 3;
				$stroke_dash_offset_val = ( $circle_radius_val * 6.27 ) + 3;
			}

			$horizontal_track_color = '';
			if ( $attr['gradientTrack'] ) {
				$horizontal_track_color =
					'linear-gradient( to bottom, ' .
					$attr['horizontalProgressBarPrimaryTrackColor'] .
					', ' .
					$attr['horizontalProgressBarSecondaryTrackColor'] .
					')';
			} else {
				$horizontal_track_color =
					'linear-gradient( to bottom, ' .
					$attr['horizontalProgressBarPrimaryTrackColor'] .
					', ' .
					$attr['horizontalProgressBarPrimaryTrackColor'] .
					')';
			}

			$track_stripe_color = 'linear-gradient(45deg, ' .
				$attr['horizontalProgressBarStripeColor'] . ' 25%,' .
				' rgba(0, 0, 0, 0) 25%,' .
				' rgba(0, 0, 0, 0) 50%,' .
				$attr['horizontalProgressBarStripeColor'] . ' 50%,' .
				$attr['horizontalProgressBarStripeColor'] . ' 75%,' .
				' rgba(0, 0, 0, 0) 75%,' .
				' rgba(0, 0, 0, 0) 25%)';

			$stripe_animation_duration_val = 0;
			if ( $attr['stripedHorizontalProgressBarAnimationTime'] ) {
				$stripe_animation_duration_val = $attr['stripedHorizontalProgressBarAnimationTime'] * 0.1;
			}

			$semi_circular_background_track_colors = '';
			if ( $attr['semiCircularProgressBarBackgroundColor'] && $attr['semiCircularProgressBarTrackColor'] ) {
				$semi_circular_background_track_colors =
					$attr['semiCircularProgressBarBackgroundColor'] .
					$attr['semiCircularProgressBarBackgroundColor'] .
					$attr['semiCircularProgressBarTrackColor'] .
					$attr['semiCircularProgressBarTrackColor'];
			}

			$selectors = array(
				' .responsive-horizontal-progress-bar' => array(
					'height'           => self::get_css_value( $attr['horizontalProgressBarSize'], 'px' ),
					'background-color' => $attr['horizontalProgressBarBackgroundColor'],
					'border-radius'    => self::get_css_value( $attr['horizontalProgressBarBorderRadius'], 'px' ),
					'border-style'     => $attr['horizontalProgressBarBorderStyle'],
					'border-width'     => self::get_css_value( $attr['horizontalProgressBarBorderWidth'], 'px' ),
					'border-color'     => $attr['horizontalProgressBarBorderColor'],
				),
				' .responsive-horizontal-progress-bar-progress' => array(
					'width'            => $attr['progressBarValue'] . '%',
					'background-image' => $horizontal_track_color,
				),
				' .responsive-striped-horizontal-progress-bar-progress' => array(
					'width'            => $attr['progressBarValue'] . '%',
					'background-image' => $horizontal_track_color,
				),
				' .responsive-semi-circular-progress'  => array(
					'width'  => self::get_css_value( $attr['semiCircularProgressBarSize'], 'px' ),
					'height' => self::get_css_value( ( $attr['semiCircularProgressBarSize'] / 2 ), 'px' ),
				),
				' .responsive-semi-circular-progress::after' => array(
					'width'        => self::get_css_value( $attr['semiCircularProgressBarSize'], 'px' ),
					'height'       => self::get_css_value( $attr['semiCircularProgressBarSize'], 'px' ),
					'transform'    => 'rotate(calc(1deg * (-45 + ' . $attr['progressBarValue'] . ' * 1.8)))',
					'border-width' => self::get_css_value( $attr['semiCircularProgressBarWidth'], 'px' ),
					'border-color' => $semi_circular_background_track_colors,
				),
				' .responsive-circular-progress-bar-circle' => array(
					'width'  => self::get_css_value( $attr['circularProgressBarSize'], 'px' ),
					'height' => self::get_css_value( $attr['circularProgressBarSize'], 'px' ),
				),
				' .responsive-circular-progress-bar-circle circle:nth-child(2)' => array(
					'stroke-dasharray'  => $stroke_dash_array_val,
					'stroke-dashoffset' => $stroke_dash_offset_val,
					'stroke'            => $attr['circularProgressBarTrackColor'],
				),
				' .responsive-circular-progress-bar-container:nth-child(1) .responsive-circular-progress-bar-circle circle:nth-child(2)' => array(
					'stroke-dashoffset' => 'calc(' . $stroke_dash_offset_val . ' - (' . $stroke_dash_offset_val . ' * ' . $attr['progressBarValue'] . ') / 100)',
				),
				' .responsive-circular-progress-bar-circle circle' => array(
					'stroke-width'   => self::get_css_value( $attr['circularProgressBarWidth'], 'px' ),
					'stroke'         => $attr['circularProgressBarBackgroundColor'],
					'stroke-linecap' => $attr['circularProgressBarTrackStyle'],
				),
				' .responsive-striped-horizontal-progress-bar-inner-span::after' => array(
					'background-image' => $track_stripe_color,
				),
				' .responsive-horizontal-progress-bar-top-title' => array(
					'color'          => $attr['horizontalProgressBarTopTitleValueColor'],
					'font-family'    => $attr['topTitleValueFontFamily'],
					'font-size'      => self::get_css_value( $attr['topTitleValueFontSize'], 'px' ),
					'font-weight'    => $attr['topTitleValueFontWeight'],
					'line-height'    => $attr['topTitleValueLineHeight'],
					'letter-spacing' => self::get_css_value( $attr['topTitleValueLetterSpacing'], 'px' ),
					'text-transform' => $attr['topTitleValueTextTransform'],
				),
				' .responsive-horizontal-progress-bar-top-value' => array(
					'color'          => $attr['horizontalProgressBarTopTitleValueColor'],
					'font-family'    => $attr['topTitleValueFontFamily'],
					'font-size'      => self::get_css_value( $attr['topTitleValueFontSize'], 'px' ),
					'font-weight'    => $attr['topTitleValueFontWeight'],
					'line-height'    => $attr['topTitleValueLineHeight'],
					'letter-spacing' => self::get_css_value( $attr['topTitleValueLetterSpacing'], 'px' ),
					'text-transform' => $attr['topTitleValueTextTransform'],
				),
				' .responsive-horizontal-progress-bar-inner-title' => array(
					'color'          => $attr['horizontalProgressBarInnerTitleValueColor'],
					'font-family'    => $attr['innerTitleValueFontFamily'],
					'font-size'      => self::get_css_value( $attr['innerTitleValueFontSize'], 'px' ),
					'font-weight'    => $attr['innerTitleValueFontWeight'],
					'line-height'    => $attr['innerTitleValueLineHeight'],
					'letter-spacing' => self::get_css_value( $attr['innerTitleValueLetterSpacing'], 'px' ),
					'text-transform' => $attr['innerTitleValueTextTransform'],
				),
				' .responsive-horizontal-progress-bar-inner-value' => array(
					'color'          => $attr['horizontalProgressBarInnerTitleValueColor'],
					'font-family'    => $attr['innerTitleValueFontFamily'],
					'font-size'      => self::get_css_value( $attr['innerTitleValueFontSize'], 'px' ),
					'font-weight'    => $attr['innerTitleValueFontWeight'],
					'line-height'    => $attr['innerTitleValueLineHeight'],
					'letter-spacing' => self::get_css_value( $attr['innerTitleValueLetterSpacing'], 'px' ),
					'text-transform' => $attr['innerTitleValueTextTransform'],
				),
				' .responsive-horizontal-progress-bar-bottom-title' => array(
					'color'          => $attr['horizontalProgressBarBottomTitleValueColor'],
					'font-family'    => $attr['bottomTitleValueFontFamily'],
					'font-size'      => self::get_css_value( $attr['bottomTitleValueFontSize'], 'px' ),
					'font-weight'    => $attr['bottomTitleValueFontWeight'],
					'line-height'    => $attr['bottomTitleValueLineHeight'],
					'letter-spacing' => self::get_css_value( $attr['bottomTitleValueLetterSpacing'], 'px' ),
					'text-transform' => $attr['bottomTitleValueTextTransform'],
				),
				' .responsive-horizontal-progress-bar-bottom-value' => array(
					'color'          => $attr['horizontalProgressBarBottomTitleValueColor'],
					'font-family'    => $attr['bottomTitleValueFontFamily'],
					'font-size'      => self::get_css_value( $attr['bottomTitleValueFontSize'], 'px' ),
					'font-weight'    => $attr['bottomTitleValueFontWeight'],
					'line-height'    => $attr['bottomTitleValueLineHeight'],
					'letter-spacing' => self::get_css_value( $attr['bottomTitleValueLetterSpacing'], 'px' ),
					'text-transform' => $attr['bottomTitleValueTextTransform'],
				),
				' .responsive-circular-progress-bar-top-title' => array(
					'color'          => $attr['circularProgressBarTopTitleValueColor'],
					'font-family'    => $attr['circularTopTitleValueFontFamily'],
					'font-size'      => self::get_css_value( $attr['circularTopTitleValueFontSize'], 'px' ),
					'line-height'    => $attr['circularTopTitleValueLineHeight'],
					'font-weight'    => $attr['circularTopTitleValueFontWeight'],
					'letter-spacing' => self::get_css_value( $attr['circularTopTitleValueLetterSpacing'], 'px' ),
					'text-transform' => $attr['circularTopTitleValueTextTransform'],
				),
				' .responsive-circular-progress-bar-top-value' => array(
					'color'          => $attr['circularProgressBarTopTitleValueColor'],
					'font-family'    => $attr['circularTopTitleValueFontFamily'],
					'font-size'      => self::get_css_value( $attr['circularTopTitleValueFontSize'], 'px' ),
					'line-height'    => $attr['circularTopTitleValueLineHeight'],
					'font-weight'    => $attr['circularTopTitleValueFontWeight'],
					'letter-spacing' => self::get_css_value( $attr['circularTopTitleValueLetterSpacing'], 'px' ),
					'text-transform' => $attr['circularTopTitleValueTextTransform'],
				),
				' .responsive-circular-progress-bar-progress' => array(
					'color'           => $attr['circularProgressBarInnerValueColor'],
					'font-family'     => $attr['circularInnerValueFontFamily'],
					'font-size'       => self::get_css_value( $attr['circularInnerValueFontSize'], 'px' ),
					'line-height'     => $attr['circularInnerValueLineHeight'],
					'font-weight'     => $attr['circularInnerValueFontWeight'],
					'letter-spacing'  => self::get_css_value( $attr['circularInnerValueLetterSpacing'], 'px' ),
					'display'         => 'flex',
					'justify-content' => 'center',
					'align-items'     => 'center',
					'width'           => self::get_css_value( $attr['circularProgressBarSize'] - 40, 'px' ),
					'height'          => self::get_css_value( $attr['circularProgressBarSize'] - 40, 'px' ),
					'border-radius'   => '100%',
					'overflow'        => 'hidden',
				),
				' .responsive-circular-progress-bar-bottom-title' => array(
					'color'          => $attr['circularProgressBarBottomTitleValueColor'],
					'font-family'    => $attr['circularBottomTitleValueFontFamily'],
					'font-size'      => self::get_css_value( $attr['circularBottomTitleValueFontSize'], 'px' ),
					'line-height'    => $attr['circularBottomTitleValueLineHeight'],
					'font-weight'    => $attr['circularBottomTitleValueFontWeight'],
					'letter-spacing' => self::get_css_value( $attr['circularBottomTitleValueLetterSpacing'], 'px' ),
					'text-transform' => $attr['circularBottomTitleValueTextTransform'],
				),
				' .responsive-circular-progress-bar-bottom-value' => array(
					'color'          => $attr['circularProgressBarBottomTitleValueColor'],
					'font-family'    => $attr['circularBottomTitleValueFontFamily'],
					'font-size'      => self::get_css_value( $attr['circularBottomTitleValueFontSize'], 'px' ),
					'line-height'    => $attr['circularBottomTitleValueLineHeight'],
					'font-weight'    => $attr['circularBottomTitleValueFontWeight'],
					'letter-spacing' => self::get_css_value( $attr['circularBottomTitleValueLetterSpacing'], 'px' ),
					'text-transform' => $attr['circularBottomTitleValueTextTransform'],
				),
				' .responsive-semi-circular-progress-bar-top-title' => array(
					'color'          => $attr['semiCircularProgressBarTopTitleValueColor'],
					'font-family'    => $attr['semiCircularTopTitleValueFontFamily'],
					'font-size'      => self::get_css_value( $attr['semiCircularTopTitleValueFontSize'], 'px' ),
					'line-height'    => $attr['semiCircularTopTitleValueLineHeight'],
					'font-weight'    => $attr['semiCircularTopTitleValueFontWeight'],
					'letter-spacing' => self::get_css_value( $attr['semiCircularTopTitleValueLetterSpacing'], 'px' ),
					'text-transform' => $attr['semiCircularTopTitleValueTextTransform'],
				),
				' .responsive-semi-circular-progress-bar-top-value' => array(
					'color'          => $attr['semiCircularProgressBarTopTitleValueColor'],
					'font-family'    => $attr['semiCircularTopTitleValueFontFamily'],
					'font-size'      => self::get_css_value( $attr['semiCircularTopTitleValueFontSize'], 'px' ),
					'line-height'    => $attr['semiCircularTopTitleValueLineHeight'],
					'font-weight'    => $attr['semiCircularTopTitleValueFontWeight'],
					'letter-spacing' => self::get_css_value( $attr['semiCircularTopTitleValueLetterSpacing'], 'px' ),
					'text-transform' => $attr['semiCircularTopTitleValueTextTransform'],
				),
				' .responsive-semi-circular-progress-bar-value' => array(
					'color'          => $attr['semiCircularProgressBarInnerValueColor'],
					'font-family'    => $attr['semiCircularInnerValueFontFamily'],
					'font-size'      => self::get_css_value( $attr['semiCircularInnerValueFontSize'], 'px' ),
					'line-height'    => $attr['semiCircularInnerValueLineHeight'],
					'font-weight'    => $attr['semiCircularInnerValueFontWeight'],
					'letter-spacing' => self::get_css_value( $attr['semiCircularInnerValueLetterSpacing'], 'px' ),
				),
				' .responsive-semi-circular-progress-bar-bottom-title' => array(
					'color'          => $attr['semiCircularProgressBarBottomTitleValueColor'],
					'font-family'    => $attr['semiCircularBottomTitleValueFontFamily'],
					'font-size'      => self::get_css_value( $attr['semiCircularBottomTitleValueFontSize'], 'px' ),
					'line-height'    => $attr['semiCircularBottomTitleValueLineHeight'],
					'font-weight'    => $attr['semiCircularBottomTitleValueFontWeight'],
					'letter-spacing' => self::get_css_value( $attr['semiCircularBottomTitleValueLetterSpacing'], 'px' ),
					'text-transform' => $attr['semiCircularBottomTitleValueTextTransform'],
				),
				' .responsive-semi-circular-progress-bar-bottom-value' => array(
					'color'          => $attr['semiCircularProgressBarBottomTitleValueColor'],
					'font-family'    => $attr['semiCircularBottomTitleValueFontFamily'],
					'font-size'      => self::get_css_value( $attr['semiCircularBottomTitleValueFontSize'], 'px' ),
					'line-height'    => $attr['semiCircularBottomTitleValueLineHeight'],
					'font-weight'    => $attr['semiCircularBottomTitleValueFontWeight'],
					'letter-spacing' => self::get_css_value( $attr['semiCircularBottomTitleValueLetterSpacing'], 'px' ),
					'text-transform' => $attr['semiCircularBottomTitleValueTextTransform'],
				),
				' .stripe-movement-effect::after'      => array(
					'animation-duration' => $stripe_animation_duration_val . 's',
				),
			);

			$mobile_selectors = array(
				' .responsive-horizontal-progress-bar-top-title'   => array(
					'font-size' => self::get_css_value( $attr['topTitleValueFontSizeMobile'], 'px' ),
				),
				' .responsive-horizontal-progress-bar-top-value'   => array(
					'font-size' => self::get_css_value( $attr['topTitleValueFontSizeMobile'], 'px' ),
				),
				' .responsive-horizontal-progress-bar-inner-title' => array(
					'font-size' => self::get_css_value( $attr['innerTitleValueFontSizeMobile'], 'px' ),
				),
				' .responsive-horizontal-progress-bar-inner-value' => array(
					'font-size' => self::get_css_value( $attr['innerTitleValueFontSizeMobile'], 'px' ),
				),
				' .responsive-horizontal-progress-bar-bottom-title' => array(
					'font-size' => self::get_css_value( $attr['bottomTitleValueFontSizeMobile'], 'px' ),
				),
				' .responsive-horizontal-progress-bar-bottom-value' => array(
					'font-size' => self::get_css_value( $attr['bottomTitleValueFontSizeMobile'], 'px' ),
				),
				' .responsive-circular-progress-bar-top-title'     => array(
					'font-size' => self::get_css_value( $attr['circularTopTitleValueFontSizeMobile'], 'px' ),
				),
				' .responsive-circular-progress-bar-top-value'     => array(
					'font-size' => self::get_css_value( $attr['circularTopTitleValueFontSizeMobile'], 'px' ),
				),
				' .responsive-circular-progress-bar-progress'      => array(
					'font-size' => self::get_css_value( $attr['circularInnerValueFontSizeMobile'], 'px' ),
				),
				' .responsive-circular-progress-bar-bottom-title'  => array(
					'font-size' => self::get_css_value( $attr['circularBottomTitleValueFontSizeMobile'], 'px' ),
				),
				' .responsive-circular-progress-bar-bottom-value'  => array(
					'font-size' => self::get_css_value( $attr['circularBottomTitleValueFontSizeMobile'], 'px' ),
				),
				' .responsive-semi-circular-progress-bar-top-title' => array(
					'font-size' => self::get_css_value( $attr['semiCircularTopTitleValueFontSizeMobile'], 'px' ),
				),
				' .responsive-semi-circular-progress-bar-top-value' => array(
					'font-size' => self::get_css_value( $attr['semiCircularTopTitleValueFontSizeMobile'], 'px' ),
				),
				' .responsive-semi-circular-progress-bar-value'    => array(
					'font-size' => self::get_css_value( $attr['semiCircularInnerValueFontSizeMobile'], 'px' ),
				),
				' .responsive-semi-circular-progress-bar-bottom-title' => array(
					'font-size' => self::get_css_value( $attr['semiCircularBottomTitleValueFontSizeMobile'], 'px' ),
				),
				' .responsive-semi-circular-progress-bar-bottom-value' => array(
					'font-size' => self::get_css_value( $attr['semiCircularBottomTitleValueFontSizeMobile'], 'px' ),
				),
			);

			$tablet_selectors = array(
				' .responsive-horizontal-progress-bar-top-title'   => array(
					'font-size' => self::get_css_value( $attr['topTitleValueFontSizeTablet'], 'px' ),
				),
				' .responsive-horizontal-progress-bar-top-value'   => array(
					'font-size' => self::get_css_value( $attr['topTitleValueFontSizeTablet'], 'px' ),
				),
				' .responsive-horizontal-progress-bar-inner-title' => array(
					'font-size' => self::get_css_value( $attr['innerTitleValueFontSizeTablet'], 'px' ),
				),
				' .responsive-horizontal-progress-bar-inner-value' => array(
					'font-size' => self::get_css_value( $attr['innerTitleValueFontSizeTablet'], 'px' ),
				),
				' .responsive-horizontal-progress-bar-bottom-title' => array(
					'font-size' => self::get_css_value( $attr['bottomTitleValueFontSizeTablet'], 'px' ),
				),
				' .responsive-horizontal-progress-bar-bottom-value' => array(
					'font-size' => self::get_css_value( $attr['bottomTitleValueFontSizeTablet'], 'px' ),
				),
				' .responsive-circular-progress-bar-top-title'     => array(
					'font-size' => self::get_css_value( $attr['circularTopTitleValueFontSizeTablet'], 'px' ),
				),
				' .responsive-circular-progress-bar-top-value'     => array(
					'font-size' => self::get_css_value( $attr['circularTopTitleValueFontSizeTablet'], 'px' ),
				),
				' .responsive-circular-progress-bar-progress'      => array(
					'font-size' => self::get_css_value( $attr['circularInnerValueFontSizeTablet'], 'px' ),
				),
				' .responsive-circular-progress-bar-bottom-title'  => array(
					'font-size' => self::get_css_value( $attr['circularBottomTitleValueFontSizeTablet'], 'px' ),
				),
				' .responsive-circular-progress-bar-bottom-value'  => array(
					'font-size' => self::get_css_value( $attr['circularBottomTitleValueFontSizeTablet'], 'px' ),
				),
				' .responsive-semi-circular-progress-bar-top-title' => array(
					'font-size' => self::get_css_value( $attr['semiCircularTopTitleValueFontSizeTablet'], 'px' ),
				),
				' .responsive-semi-circular-progress-bar-top-value' => array(
					'font-size' => self::get_css_value( $attr['semiCircularTopTitleValueFontSizeTablet'], 'px' ),
				),
				' .responsive-semi-circular-progress-bar-value'    => array(
					'font-size' => self::get_css_value( $attr['semiCircularInnerValueFontSizeTablet'], 'px' ),
				),
				' .responsive-semi-circular-progress-bar-bottom-title' => array(
					'font-size' => self::get_css_value( $attr['semiCircularBottomTitleValueFontSizeTablet'], 'px' ),
				),
				' .responsive-semi-circular-progress-bar-bottom-value' => array(
					'font-size' => self::get_css_value( $attr['semiCircularBottomTitleValueFontSizeTablet'], 'px' ),
				),
			);

			$combined_selectors = array(
				'desktop' => $selectors,
				'tablet'  => $tablet_selectors,
				'mobile'  => $mobile_selectors,
			);

			$id  = '.responsive-block-editor-addons-block-progress-bar.block-' . $id;
			$css = Responsive_Block_Editor_Addons_Frontend_Styles_Helper::responsive_block_editor_addons_generate_all_css( $combined_selectors, $id );
			return $css;
		}

		/**
		 * Get Defaults for Progress Bar block
		 *
		 * @return array
		 */
		public static function get_responsive_block_progress_bar_default_attributes() {
			return array(
				'block_id'                                 => '',
				'progressBarStyle'                         => 'horizontal',
				'progressBarColorType'                     => 'default',
				'horizontalProgressBarStyle'               => 'plain',
				'circularProgressBarStyle'                 => 'circle',
				'progressBarTopTitle'                      => '',
				'progressBarTopTitleEnable'                => true,
				'progressBarTopValueEnable'                => true,
				'progressBarValue'                         => 50,
				'progressBarInnerTitle'                    => '',
				'progressBarInnerTitleEnable'              => false,
				'progressBarInnerValueEnable'              => true,
				'progressBarBottomTitle'                   => '',
				'progressBarBottomTitleEnable'             => false,
				'progressBarBottomValueEnable'             => false,
				'circularProgressBarTopTitle'              => '',
				'circularProgressBarBottomTitle'           => '',
				'circularProgressBarValueEnable'           => true,
				'circularProgressBarTopTitleEnable'        => true,
				'circularProgressBarTopValueEnable'        => false,
				'circularProgressBarBottomTitleEnable'     => true,
				'circularProgressBarBottomValueEnable'     => false,
				'semiCircularProgressBarValueEnable'       => true,
				'semiCircularProgressBarTopTitle'          => '',
				'semiCircularProgressBarBottomTitle'       => '',
				'semiCircularProgressBarTopTitleEnable'    => true,
				'semiCircularProgressBarTopValueEnable'    => false,
				'semiCircularProgressBarBottomTitleEnable' => true,
				'semiCircularProgressBarBottomValueEnable' => false,
				'horizontalProgressBarSize'                => 25,
				'semiCircularProgressBarSize'              => 200,
				'circularProgressBarSize'                  => 150,
				'circularProgressBarWidth'                 => 10,
				'semiCircularProgressBarWidth'             => 10,
				'horizontalProgressBarBackgroundColor'     => '#d9d9d9',
				'horizontalProgressBarPrimaryTrackColor'   => '#80bfff',
				'gradientTrack'                            => true,
				'horizontalProgressBarSecondaryTrackColor' => '#1a8cff',
				'horizontalProgressBarStripeColor'         => '#fff',
				'horizontalProgressBarTopTitleValueColor'  => '#333333',
				'horizontalProgressBarInnerTitleValueColor' => '#333333',
				'horizontalProgressBarBottomTitleValueColor' => '#333333',
				'horizontalProgressBarBorderRadius'        => 5,
				'horizontalProgressBarBorderStyle'         => 'solid',
				'horizontalProgressBarBorderWidth'         => 0,
				'horizontalProgressBarBorderColor'         => '#333333',
				'topTitleValueFontFamily'                  => '',
				'topTitleValueFontSize'                    => '',
				'topTitleValueFontSizeMobile'              => '',
				'topTitleValueFontSizeTablet'              => '',
				'topTitleValueFontWeight'                  => '400',
				'topTitleValueLineHeight'                  => 1,
				'topTitleValueLetterSpacing'               => 1,
				'topTitleValueTextTransform'               => '',
				'innerTitleValueFontFamily'                => '',
				'innerTitleValueFontSize'                  => '',
				'innerTitleValueFontSizeMobile'            => '',
				'innerTitleValueFontSizeTablet'            => '',
				'innerTitleValueFontWeight'                => '400',
				'innerTitleValueLineHeight'                => 1,
				'innerTitleValueLetterSpacing'             => 1,
				'innerTitleValueTextTransform'             => '',
				'bottomTitleValueFontFamily'               => '',
				'bottomTitleValueFontSize'                 => '',
				'bottomTitleValueFontSizeMobile'           => '',
				'bottomTitleValueFontSizeTablet'           => '',
				'bottomTitleValueFontWeight'               => '400',
				'bottomTitleValueLineHeight'               => 1,
				'bottomTitleValueLetterSpacing'            => 1,
				'bottomTitleValueTextTransform'            => '',
				'stripedHorizontalProgressBarAnimationTime' => 20,
				'circularProgressBarBackgroundColor'       => '#d9d9d9',
				'circularProgressBarTrackColor'            => '#6665ae',
				'circularProgressBarTopTitleValueColor'    => '#333333',
				'circularProgressBarInnerValueColor'       => '#333333',
				'circularProgressBarBottomTitleValueColor' => '#333333',
				'semiCircularProgressBarTopTitleValueColor' => '#333333',
				'semiCircularProgressBarInnerValueColor'   => '#333333',
				'semiCircularProgressBarBottomTitleValueColor' => '#333333',
				'circularProgressBarTrackStyle'            => 'square',
				'circularTopTitleValueFontFamily'          => '',
				'circularTopTitleValueFontSize'            => '',
				'circularTopTitleValueFontSizeMobile'      => '',
				'circularTopTitleValueFontSizeTablet'      => '',
				'circularTopTitleValueFontWeight'          => '400',
				'circularTopTitleValueLineHeight'          => 1,
				'circularTopTitleValueLetterSpacing'       => 1,
				'circularTopTitleValueTextTransform'       => '',
				'circularBottomTitleValueFontFamily'       => '',
				'circularBottomTitleValueFontSize'         => '',
				'circularBottomTitleValueFontSizeMobile'   => '',
				'circularBottomTitleValueFontSizeTablet'   => '',
				'circularBottomTitleValueFontWeight'       => '400',
				'circularBottomTitleValueLineHeight'       => 1,
				'circularBottomTitleValueLetterSpacing'    => 1,
				'circularBottomTitleValueTextTransform'    => '',
				'circularInnerValueFontFamily'             => '',
				'circularInnerValueFontSize'               => '',
				'circularInnerValueFontSizeMobile'         => '',
				'circularInnerValueFontSizeTablet'         => '',
				'circularInnerValueFontWeight'             => '400',
				'circularInnerValueLineHeight'             => 1,
				'circularInnerValueLetterSpacing'          => 1,
				'semiCircularTopTitleValueFontFamily'      => '',
				'semiCircularTopTitleValueFontSize'        => '',
				'semiCircularTopTitleValueFontSizeMobile'  => '',
				'semiCircularTopTitleValueFontSizeTablet'  => '',
				'semiCircularTopTitleValueFontWeight'      => '400',
				'semiCircularTopTitleValueLineHeight'      => 1,
				'semiCircularTopTitleValueLetterSpacing'   => 0,
				'semiCircularTopTitleValueTextTransform'   => '',
				'semiCircularBottomTitleValueFontFamily'   => '',
				'semiCircularBottomTitleValueFontSize'     => '',
				'semiCircularBottomTitleValueFontSizeMobile' => '',
				'semiCircularBottomTitleValueFontSizeTablet' => '',
				'semiCircularBottomTitleValueFontWeight'   => '400',
				'semiCircularBottomTitleValueLineHeight'   => 1,
				'semiCircularBottomTitleValueLetterSpacing' => 1,
				'semiCircularBottomTitleValueTextTransform' => '',
				'semiCircularInnerValueFontFamily'         => '',
				'semiCircularInnerValueFontSize'           => '',
				'semiCircularInnerValueFontSizeMobile'     => '',
				'semiCircularInnerValueFontSizeTablet'     => '',
				'semiCircularInnerValueFontWeight'         => '400',
				'semiCircularInnerValueLineHeight'         => 1,
				'semiCircularInnerValueLetterSpacing'      => 1,
				'semiCircularProgressBarBackgroundColor'   => '#d9d9d9',
				'semiCircularProgressBarTrackColor'        => '#1a8cff',
			);
		}

		/**
		 * Get Social Share Block CSS
		 *
		 * @param array  $attr The block attributes.
		 * @param string $id The selector ID.
		 * @return array Styles.
		 */
		public static function get_responsive_block_social_share_css( $attr, $id ) {
			$defaults = self::get_responsive_block_social_share_default_attributes();
			$attr     = array_merge( $defaults, (array) $attr );

			$mobile_selectors = array();
			$tablet_selectors = array();

			$icon_shape_radius = '';
			if ( 'square' === $attr['iconShape'] ) {
				$icon_shape_radius = '0%';
			} elseif ( 'rounded' === $attr['iconShape'] ) {
				$icon_shape_radius = '10%';
			} elseif ( 'circle' === $attr['iconShape'] ) {
				$icon_shape_radius = '100%';
			}

			$box_shadow_position_css = $attr['boxShadowPosition'];

			if ( 'outset' === $attr['boxShadowPosition'] ) {
				$box_shadow_position_css = '';
			}

			$newopacity = $attr['opacity'] / 100;

			$selectors = array(
				' ' => array(
					'margin-top'       => self::get_css_value( $attr['blockTopMargin'], 'px' ) . '!important',
					'margin-bottom'    => self::get_css_value( $attr['blockBottomMargin'], 'px' ) . '!important',
					'margin-left'      => self::get_css_value( $attr['blockLeftMargin'], 'px' ) . '!important',
					'margin-right'     => self::get_css_value( $attr['blockRightMargin'], 'px' ) . '!important',
					'padding-top'      => self::get_css_value( $attr['blockTopPadding'], 'px' ) . '!important',
					'padding-bottom'   => self::get_css_value( $attr['blockBottomPadding'], 'px' ) . '!important',
					'padding-left'     => self::get_css_value( $attr['blockLeftPadding'], 'px' ) . '!important',
					'padding-right'    => self::get_css_value( $attr['blockRightPadding'], 'px' ) . '!important',
					'z-index'          => $attr['socialZindex'],
					'background-color' => self::hex_to_rgb( $attr['backgroundColor'], $newopacity ),
					'border-color'     => $attr['blockBorderColor'],
					'border-style'     => $attr['blockBorderStyle'],
					'border-width'     => self::get_css_value( $attr['blockBorderWidth'], 'px' ),
					'border-radius'    => self::get_css_value( $attr['blockBorderRadius'], 'px' ),
					'box-shadow'       =>
					self::get_css_value( $attr['boxShadowHOffset'], 'px' ) .
					' ' .
					self::get_css_value( $attr['boxShadowVOffset'], 'px' ) .
					' ' .
					self::get_css_value( $attr['boxShadowBlur'], 'px' ) .
					' ' .
					self::get_css_value( $attr['boxShadowSpread'], 'px' ) .
					' ' .
					$attr['boxShadowColor'] .
					' ' .
					$box_shadow_position_css,
					'min-width'        => 'fit-content',
					'max-width'        => '100%',
				),
				' .responsive-block-editor-addons-share-icon' => array(
					'border-radius'    => $icon_shape_radius,
					'fill'             => 'custom' === $attr['iconColorType'] ? $attr['iconPrimaryColor'] : '',
					'background-color' => 'custom' === $attr['iconColorType'] && 'flat' === $attr['skin'] ? $attr['iconSecondaryColor'] : '',
					'border'           => 'custom' === $attr['iconColorType'] && ( 'framed' === $attr['skin'] || 'boxed' === $attr['skin'] ) ? '1px solid ' . $attr['iconSecondaryColor'] : '',
					'display'          => 'flex',
					'justify-content'  => 'center',
					'padding-top'      => self::get_css_value( $attr['iconContainerHeight'], 'px' ),
					'padding-bottom'   => self::get_css_value( $attr['iconContainerHeight'], 'px' ),
					'padding-left'     => self::get_css_value( $attr['iconContainerSize'], 'px' ),
					'padding-right'    => self::get_css_value( $attr['iconContainerSize'], 'px' ),
					'width'            => 'fit-content',
				),
				' .responsive-block-editor-addons-share-icon > a:first-child' => array(
					'padding'          => 'boxed' === $attr['skin'] || 'minimal' === $attr['skin'] ? '0 10px 0 10px' : '',
					'background-color' => ( 'boxed' === $attr['skin'] || 'minimal' === $attr['skin'] ) ? $attr['iconSecondaryColor'] : '',
				),
				' .responsive-block-editor-addons-share-icon > a:last-child' => array(
					'padding' => ( 'boxed' === $attr['skin'] || 'minimal' === $attr['skin'] ) ? '0 10px 0 0' : '',
				),
				' .responsive-block-editor-addons-share-icon-svg svg' => array(
					'height' => self::get_css_value( $attr['iconSize'], 'px' ),
					'width'  => self::get_css_value( $attr['iconSize'], 'px' ),
					'fill'   => 'custom' === $attr['iconColorType'] ? $attr['iconPrimaryColor'] : '',
				),
				' .responsive-block-editor-addons-share-icons-container' => array(
					'display'               => 'inline-grid',
					'grid-template-columns' =>
					'auto' !== $attr['iconColumns'] ? 'repeat(' . $attr['iconColumns'] . ' , auto)' : '',
					'grid-auto-flow'        =>
					'auto' !== $attr['iconColumns'] ? '' : 'column',
					'grid-column-gap'       => self::get_css_value( $attr['iconColumnsGap'], 'px' ),
					'grid-row-gap'          => self::get_css_value( $attr['iconRowsGap'], 'px' ),
				),
				' .responsive-block-editor-addons-share-icon-label' => array(
					'font-size'   => self::get_css_value( $attr['labelFontSize'], 'px' ),
					'font-family' => $attr['labelFontFamily'],
					'font-weight' => $attr['labelFontWeight'],
					'line-height' => $attr['labelLineHeight'],
					'margin-left' => self::get_css_value( $attr['iconLabelGap'], 'px' ),
					'color'       => $attr['labelColor'],
				),
				' .responsive-block-editor-addons-icon-facebook, .responsive-block-editor-addons-icon-facebook-f, .responsive-block-editor-addons-icon-facebook-square, .responsive-block-editor-addons-icon-facebook-messenger' => array(
					'fill' => '#3b5998',
				),
				' .responsive-block-editor-addons-icon-twitter, .responsive-block-editor-addons-icon-twitter-square' => array(
					'fill' => '#00aced',
				),
				' .responsive-block-editor-addons-icon-linkedin, .responsive-block-editor-addons-icon-linkedin-in' => array(
					'fill' => '#007bb6',
				),
				' .responsive-block-editor-addons-icon-youtube, .responsive-block-editor-addons-icon-youtube-square' => array(
					'fill' => '#bb0000',
				),
				' .responsive-block-editor-addons-icon-pinterest, .responsive-block-editor-addons-icon-pinterest-p, .responsive-block-editor-addons-icon-pinterest-square' => array(
					'fill' => '#bb0000',
				),
				' .responsive-block-editor-addons-icon-instagram' => array(
					'fill' => '#e95950',
				),
				' .responsive-block-editor-addons-icon-snapchat, .responsive-block-editor-addons-icon-snapchat-ghost,  .responsive-block-editor-addons-icon-snapchat-square' => array(
					'fill' => '#fffc00',
				),
				' .responsive-block-editor-addons-icon-tumblr, .responsive-block-editor-addons-icon-tumblr-square' => array(
					'fill' => '#32506d',
				),
				' .responsive-block-editor-addons-icon-vimeo, .responsive-block-editor-addons-icon-vimeo-v, .responsive-block-editor-addons-icon-vimeo-square' => array(
					'fill' => '#aad450',
				),
				' .responsive-block-editor-addons-icon-quora' => array(
					'fill' => '#a82400',
				),
				' .responsive-block-editor-addons-icon-google-plus, .responsive-block-editor-addons-icon-google-plus-g, .responsive-block-editor-addons-icon-google-plus-square' => array(
					'fill' => '#dd4b39',
				),
				' .responsive-block-editor-addons-icon-reddit, .responsive-block-editor-addons-icon-reddit-alien, .responsive-block-editor-addons-icon-reddit-square' => array(
					'fill' => '#FF5700',
				),
				' .responsive-block-editor-addons-icon-skype' => array(
					'fill' => '#00aff0',
				),
				' .responsive-block-editor-addons-icon-telegram, .responsive-block-editor-addons-icon-telegram-plane' => array(
					'fill' => '#0088cc',
				),
				' .responsive-block-editor-addons-icon-whatsapp, .responsive-block-editor-addons-icon-whatsapp-square' => array(
					'fill' => '#34B7F1',
				),
				' .responsive-block-editor-addons-icon-envelope' => array(
					'fill' => '#BB001B',
				),
			);

			$mobile_selectors = array(
				' ' => array(
					'margin-top'     => self::get_css_value( $attr['blockTopMarginMobile'], 'px' ) . '!important',
					'margin-bottom'  => self::get_css_value( $attr['blockBottomMarginMobile'], 'px' ) . '!important',
					'margin-left'    => self::get_css_value( $attr['blockLeftMarginMobile'], 'px' ) . '!important',
					'margin-right'   => self::get_css_value( $attr['blockRightMarginMobile'], 'px' ) . '!important',
					'padding-top'    => self::get_css_value( $attr['blockTopPaddingMobile'], 'px' ) . '!important',
					'padding-bottom' => self::get_css_value( $attr['blockBottomPaddingMobile'], 'px' ) . '!important',
					'padding-left'   => self::get_css_value( $attr['blockLeftPaddingMobile'], 'px' ) . '!important',
					'padding-right'  => self::get_css_value( $attr['blockRightPaddingMobile'], 'px' ) . '!important',
				),
				' .responsive-block-editor-addons-share-icon-label' => array(
					'font-size' => self::get_css_value( $attr['labelFontSizeMobile'], 'px' ),
				),
				' .responsive-block-editor-addons-share-icons-container' => array(
					'grid-template-columns' =>
					'auto' !== $attr['iconColumnsMobile'] ? 'repeat(' . $attr['iconColumnsMobile'] . ' , auto)' : '',
					'grid-auto-flow'        =>
					'auto' !== $attr['iconColumnsMobile'] ? 'unset' : 'column',
				),
			);

			$tablet_selectors = array(
				' ' => array(
					'margin-top'     => self::get_css_value( $attr['blockTopMarginTablet'], 'px' ) . '!important',
					'margin-bottom'  => self::get_css_value( $attr['blockBottomMarginTablet'], 'px' ) . '!important',
					'margin-left'    => self::get_css_value( $attr['blockLeftMarginTablet'], 'px' ) . '!important',
					'margin-right'   => self::get_css_value( $attr['blockRightMarginTablet'], 'px' ) . '!important',
					'padding-top'    => self::get_css_value( $attr['blockTopPaddingTablet'], 'px' ) . '!important',
					'padding-bottom' => self::get_css_value( $attr['blockBottomPaddingTablet'], 'px' ) . '!important',
					'padding-left'   => self::get_css_value( $attr['blockLeftPaddingTablet'], 'px' ) . '!important',
					'padding-right'  => self::get_css_value( $attr['blockRightPaddingTablet'], 'px' ) . '!important',
				),
				' .responsive-block-editor-addons-share-icon-label' => array(
					'font-size' => self::get_css_value( $attr['labelFontSizeTablet'], 'px' ),
				),
				' .responsive-block-editor-addons-share-icons-container' => array(
					'grid-template-columns' =>
					'auto' !== $attr['iconColumnsTablet'] ? 'repeat(' . $attr['iconColumnsTablet'] . ' , auto)' : '',
					'grid-auto-flow'        =>
					'auto' !== $attr['iconColumnsTablet'] ? 'unset' : 'column',
				),
			);

			$combined_selectors = array(
				'desktop' => $selectors,
				'tablet'  => $tablet_selectors,
				'mobile'  => $mobile_selectors,
			);

			$id  = '.responsive-block-editor-addons-block-social-share.block-' . $id;
			$css = Responsive_Block_Editor_Addons_Frontend_Styles_Helper::responsive_block_editor_addons_generate_all_css( $combined_selectors, $id );
			return $css;
		}

		/**
		 * Get Defaults for social share block
		 *
		 * @return array
		 */
		public static function get_responsive_block_social_share_default_attributes() {
			return array(
				'block_id'                 => '',
				'socialMediaIcons'         => '',
				'iconShape'                => 'square',
				'iconColorType'            => 'official',
				'iconPrimaryColor'         => '#0112ff',
				'iconSecondaryColor'       => '#e4f3ff',
				'showIconLabel'            => true,
				'iconSize'                 => 30,
				'iconColumns'              => 'auto',
				'iconColumnsGap'           => 15,
				'iconRowsGap'              => 10,
				'iconsAlign'               => 'left',
				'iconContainerHeight'      => 5,
				'iconContainerSize'        => 5,
				'socialZindex'             => 0,
				'blockTopPadding'          => 0,
				'blockBottomPadding'       => 0,
				'blockLeftPadding'         => 0,
				'blockRightPadding'        => 0,
				'blockTopPaddingTablet'    => 0,
				'blockBottomPaddingTablet' => 0,
				'blockLeftPaddingTablet'   => 0,
				'blockRightPaddingTablet'  => 0,
				'blockTopPaddingMobile'    => 0,
				'blockBottomPaddingMobile' => 0,
				'blockLeftPaddingMobile'   => 0,
				'blockRightPaddingMobile'  => 0,
				'blockTopMargin'           => 0,
				'blockBottomMargin'        => 0,
				'blockLeftMargin'          => 0,
				'blockRightMargin'         => 0,
				'blockTopMarginMobile'     => 0,
				'blockBottomMarginMobile'  => 0,
				'blockLeftMarginMobile'    => 0,
				'blockRightMarginMobile'   => 0,
				'blockTopMarginTablet'     => 0,
				'blockBottomMarginTablet'  => 0,
				'blockLeftMarginTablet'    => 0,
				'blockRightMarginTablet'   => 0,
				'labelFontFamily'          => 'Default',
				'labelFontSize'            => 16,
				'labelFontSizeMobile'      => 16,
				'labelFontSizeTablet'      => 16,
				'labelFontWeight'          => '500',
				'labelLineHeight'          => 1,
				'iconLabelGap'             => 5,
				'labelColor'               => '#333',
				'viewOption'               => 'icontext',
				'skin'                     => 'default',
				'blockBorderStyle'         => 'none',
				'blockBorderWidth'         => 2,
				'blockBorderRadius'        => '',
				'blockBorderColor'         => '',
				'boxShadowColor'           => '',
				'boxShadowHOffset'         => 0,
				'boxShadowVOffset'         => 0,
				'boxShadowBlur'            => 0,
				'boxShadowSpread'          => 0,
				'boxShadowPosition'        => 'outset',
				'backgroundColor'          => '#fff',
				'opacity'                  => 100,
				'iconColumnsMobile'        => '2',
				'iconColumnsTablet'        => 'auto',
			);
		}

		/**
		 * Get Tabs CSS
		 *
		 * @param array  $attr The block attributes.
		 * @param string $id The selector ID.
		 * @return array Styles.
		 */
		public static function get_responsive_block_tabs_css( $attr, $id ) {
			$defaults         = self::get_responsive_block_tabs_default_attributes();
			$attr             = array_merge( $defaults, (array) $attr );
			$mobile_selectors = array();
			$tablet_selectors = array();
			$img_opacity      = 0 === $attr['opacity'] / 100 ? '0.0' : $attr['opacity'] / 100;
			if ( 'hstyle3' === $attr['tabsStyleD'] ) {
				$active_tab_border_bottom = '0px';
				$active_tab_border_right  = self::get_css_value( $attr['tabBorderWidth'], 'px' );
				$active_tab_border_left   = self::get_css_value( $attr['tabBorderWidth'], 'px' );
			} elseif ( 'vstyle8' === $attr['tabsStyleD'] && 'left' === $attr['alignTabsVertical'] ) {
				$active_tab_border_bottom = self::get_css_value( $attr['tabBorderWidth'], 'px' );
				$active_tab_border_left   = self::get_css_value( $attr['tabBorderWidth'], 'px' );
				$active_tab_border_right  = '0px';
			} elseif ( 'vstyle8' === $attr['tabsStyleD'] && 'right' === $attr['alignTabsVertical'] ) {
				$active_tab_border_bottom = self::get_css_value( $attr['tabBorderWidth'], 'px' );
				$active_tab_border_right  = self::get_css_value( $attr['tabBorderWidth'], 'px' );
				$active_tab_border_left   = '0px';
			}
			$box_shadow_position_css = $attr['boxShadowPosition'];
			if ( 'outset' === $attr['boxShadowPosition'] ) {
				$box_shadow_position_css = '';
			}
			$selectors          = array(
				' '      => array(
					'padding-top'               => self::get_css_value( $attr['tabsTopPadding'], 'px' ) . '!important',
					'padding-bottom'            => self::get_css_value( $attr['tabsBottomPadding'], 'px' ) . '!important',
					'padding-left'              => self::get_css_value( $attr['tabsLeftPadding'], 'px' ) . '!important',
					'padding-right'             => self::get_css_value( $attr['tabsRightPadding'], 'px' ) . '!important',
					'margin-top'                => self::get_css_value( $attr['tabsTopMargin'], 'px' ) . '!important',
					'margin-bottom'             => self::get_css_value( $attr['tabsBottomMargin'], 'px' ) . '!important',
					'margin-left'               => self::get_css_value( $attr['tabsLeftMargin'], 'px' ) . '!important',
					'margin-right'              => self::get_css_value( $attr['tabsRightMargin'], 'px' ) . '!important',
					'z-index'                   => $attr['tabsZindex'],
					'background-color'          => 'color' === $attr['backgroundType'] ? self::hex_to_rgb( $attr['backgroundColor'] ? $attr['backgroundColor'] : '#fff', $img_opacity ) : '',
					'background-image'          => 'gradient' === $attr['backgroundType'] ? self::generate_background_image_effect(
						self::hex_to_rgb( $attr['backgroundColor1'], $img_opacity ),
						self::hex_to_rgb( $attr['backgroundColor2'], $img_opacity ),
						$attr['gradientDirection'],
						$attr['colorLocation1'],
						$attr['colorLocation2']
					) : '',
					'border-style'              => $attr['blockBorderStyle'],
					'border-radius'             => self::get_css_value( $attr['blockBorderRadius'], 'px' ),
					'border-width'              => self::get_css_value( $attr['blockBorderWidth'], 'px' ),
					'border-color'              => $attr['blockBorderColor'],
					'animation-name'            => $attr['animationName'] . '' . $attr['animationDirection'],
					'animation-timing-function' => $attr['animationCurve'],
					'animation-duration'        => $attr['animationDuration'] . 'ms',
					'animation-delay'           => $attr['animationDelay'] . 'ms',
					'animation-iteration-count' => 'once' === $attr['animationRepeat'] ? 1 : 'infinite',
					'box-shadow'                => $attr['boxShadowHOffset'] . 'px ' . $attr['boxShadowVOffset'] . 'px ' . $attr['boxShadowBlur'] . 'px ' . $attr['boxShadowSpread'] . 'px ' . $attr['boxShadowColor'] . ' ' . $box_shadow_position_css,
				),
				':hover' => array(
					'background-color' => 'color' === $attr['backgroundType'] ? self::hex_to_rgb( $attr['backgroundHoverColor'] ? $attr['backgroundHoverColor'] : '#fff', $img_opacity ) : '',
					'background-image' => 'gradient' === $attr['backgroundType'] ? self::generate_background_image_effect(
						self::hex_to_rgb( $attr['hoverbackgroundColor1'], $img_opacity ),
						self::hex_to_rgb( $attr['hoverbackgroundColor2'], $img_opacity ),
						$attr['hovergradientDirection'],
						$attr['hovercolorLocation1'],
						$attr['hovercolorLocation2']
					) : '',
				),
				' > .responsive-block-editor-addons-tabs__panel .responsive-block-editor-addons-tab.responsive-block-editor-addons-tabs__active' => array(
					'background' => $attr['tabBackgroundColor'],
				),
				' > .responsive-block-editor-addons-tabs__body-wrap ' => array(
					'background' => $attr['tabBackgroundColor'],
				),
				'.responsive-block-editor-addons-tabs__wrap > .responsive-block-editor-addons-tabs__panel .responsive-block-editor-addons-tab' => array(
					'border-style'        => $attr['tabBorderWidth'] > 0 ? 'solid' : 'none',
					'border-top-width'    => self::get_css_value( $attr['tabBorderWidth'], 'px' ),
					'border-left-width'   => $active_tab_border_left,
					'border-bottom-width' => $active_tab_border_bottom,
					'border-right-width'  => $active_tab_border_right,
					'border-color'        => 'transparent',
					'text-align'          => 'vstyle8' === $attr['tabsStyleD'] ? 'center' : 'left',
				),
				'.responsive-block-editor-addons-tabs__wrap > .responsive-block-editor-addons-tabs__panel .responsive-block-editor-addons-tab.responsive-block-editor-addons-tabs__active' => array(
					'border-style'        => $attr['tabBorderWidth'] > 0 ? 'solid' : 'none',
					'border-top-width'    => self::get_css_value( $attr['tabBorderWidth'], 'px' ),
					'border-left-width'   => $active_tab_border_left,
					'border-bottom-width' => $active_tab_border_bottom,
					'border-right-width'  => $active_tab_border_right,
					'border-color'        => $attr['tabBorderColor'],
					'z-index'             => '10',
					'margin-bottom'       => 'hstyle3' === $attr['tabsStyleD'] ? '-2px' : '',
					'margin-right'        => 'vstyle8' === $attr['tabsStyleD'] ? '-2px' : '',
				),
				'.responsive-block-editor-addons-tabs__wrap > .responsive-block-editor-addons-tabs__body-wrap' => array(
					'border-width' => self::get_css_value( $attr['tabBorderWidth'], 'px' ),
					'border-color' => $attr['tabBorderColor'],
				),
				'  .responsive-block-editor-addons-tabs__panel .responsive-block-editor-addons-tab span' => array(
					'color'       => $attr['tabTitleColor'],
					'font-family' => $attr['tabTitleFontFamily'],
					'font-weight' => $attr['tabTitleFontWeight'],
					'font-size'   => self::get_css_value( $attr['tabTitleFontSize'], 'px' ),
					'line-height' => self::get_css_value( $attr['tabTitleLineHeight'], 'px' ),
				),
				'  .responsive-block-editor-addons-tabs__panel .responsive-block-editor-addons-tab.responsive-block-editor-addons-tabs__active span' => array(
					'color' => $attr['tabTitleActiveColor'],
				),
				' > .responsive-block-editor-addons-tabs__body-wrap .wp-block-responsive-block-editor-addons-tabs-child p' => array(
					'color'       => $attr['tabContentColor'],
					'font-family' => $attr['tabContentFontFamily'],
					'font-weight' => $attr['tabContentFontWeight'],
					'font-size'   => self::get_css_value( $attr['tabContentFontSize'], 'px' ),
					'line-height' => self::get_css_value( $attr['tabContentLineHeight'], 'px' ),
				),
				'.responsive-block-editor-addons-vstyle8-right' => array(
					'flex-direction' => 'row-reverse',
				),
			);
			$mobile_selectors   = array(
				' ' => array(
					'padding-top'    => self::get_css_value( $attr['tabsTopPaddingMobile'], 'px' ) . '!important',
					'padding-bottom' => self::get_css_value( $attr['tabsBottomPaddingMobile'], 'px' ) . '!important',
					'padding-left'   => self::get_css_value( $attr['tabsLeftPaddingMobile'], 'px' ) . '!important',
					'padding-right'  => self::get_css_value( $attr['tabsRightPaddingMobile'], 'px' ) . '!important',
					'margin-top'     => self::get_css_value( $attr['tabsTopMarginMobile'], 'px' ) . '!important',
					'margin-bottom'  => self::get_css_value( $attr['tabsBottomMarginMobile'], 'px' ) . '!important',
					'margin-left'    => self::get_css_value( $attr['tabsLeftMarginMobile'], 'px' ) . '!important',
					'margin-right'   => self::get_css_value( $attr['tabsRightMarginMobile'], 'px' ) . '!important',
				),
				' > .responsive-block-editor-addons-tabs__panel .responsive-block-editor-addons-tab span' => array(
					'font-size' => self::get_css_value( $attr['tabTitleFontSizeMobile'], 'px' ),
				),
				' > .responsive-block-editor-addons-tabs__body-wrap .wp-block-responsive-block-editor-addons-tabs-child p' => array(
					'font-size' => self::get_css_value( $attr['tabContentFontSizeMobile'], 'px' ),
				),
			);
			$tablet_selectors   = array(
				' ' => array(
					'padding-top'    => self::get_css_value( $attr['tabsTopPaddingTablet'], 'px' ) . '!important',
					'padding-bottom' => self::get_css_value( $attr['tabsBottomPaddingTablet'], 'px' ) . '!important',
					'padding-left'   => self::get_css_value( $attr['tabsLeftPaddingTablet'], 'px' ) . '!important',
					'padding-right'  => self::get_css_value( $attr['tabsRightPaddingTablet'], 'px' ) . '!important',
					'margin-top'     => self::get_css_value( $attr['tabsTopMarginTablet'], 'px' ) . '!important',
					'margin-bottom'  => self::get_css_value( $attr['tabsBottomMarginTablet'], 'px' ) . '!important',
					'margin-left'    => self::get_css_value( $attr['tabsLeftMarginTablet'], 'px' ) . '!important',
					'margin-right'   => self::get_css_value( $attr['tabsRightMarginTablet'], 'px' ) . '!important',
				),
				' > .responsive-block-editor-addons-tabs__panel .responsive-block-editor-addons-tab span' => array(
					'font-size' => self::get_css_value( $attr['tabTitleFontSizeTablet'], 'px' ),
				),
				' > .responsive-block-editor-addons-tabs__body-wrap .wp-block-responsive-block-editor-addons-tabs-child p' => array(
					'font-size' => self::get_css_value( $attr['tabContentFontSizeTablet'], 'px' ),
				),
			);
			$combined_selectors = array(
				'desktop' => $selectors,
				'tablet'  => $tablet_selectors,
				'mobile'  => $mobile_selectors,
			);
			$id                 = '.responsive-block-editor-addons-block-tabs.block-' . $id;
			$css                = Responsive_Block_Editor_Addons_Frontend_Styles_Helper::responsive_block_editor_addons_generate_all_css( $combined_selectors, $id );
			return $css;
		}

		/**
		 * Get Defaults for tabs block
		 *
		 * @return array
		 */
		public static function get_responsive_block_tabs_default_attributes() {
			return array(
				'block_id'                 => '',
				'tabHeaderOptions'         => '',
				'activeTab'                => 0,
				'tabActiveFrontend'        => 0,
				'alignTabs'                => 'left',
				'tabsStyleD'               => 'hstyle3',
				'tabsStyleT'               => 'hstyle3',
				'tabsStyleM'               => 'hstyle3',
				'tabBorderWidth'           => 1,
				'tabBorderColor'           => '#e0e0e0',
				'tabBackgroundColor'       => '',
				'tabTitleColor'            => '#007cba',
				'tabTitleActiveColor'      => '#000',
				'tabTitleFontFamily'       => '',
				'tabTitleFontSize'         => '',
				'tabTitleFontSizeMobile'   => '',
				'tabTitleFontSizeTablet'   => '',
				'tabTitleFontWeight'       => '',
				'tabTitleLineHeight'       => '',
				'tabContentColor'          => '000',
				'tabContentFontFamily'     => '',
				'tabContentFontSize'       => '',
				'tabContentFontSizeMobile' => '',
				'tabContentFontSizeTablet' => '',
				'tabContentFontWeight'     => '',
				'tabContentLineHeight'     => '',
				'alignTabs'                => '',
				'tabsZindex'               => 0,
				'tabsTopPadding'           => 0,
				'tabsBottomPadding'        => 0,
				'tabsLeftPadding'          => 0,
				'tabsRightPadding'         => 0,
				'tabsTopPaddingTablet'     => 0,
				'tabsBottomPaddingTablet'  => 0,
				'tabsLeftPaddingTablet'    => 0,
				'tabsRightPaddingTablet'   => 0,
				'tabsTopPaddingMobile'     => 0,
				'tabsBottomPaddingMobile'  => 0,
				'tabsLeftPaddingMobile'    => 0,
				'tabsRightPaddingMobile'   => 0,
				'tabsTopMargin'            => 0,
				'tabsBottomMargin'         => 0,
				'tabsLeftMargin'           => 0,
				'tabsRightMargin'          => 0,
				'tabsTopMarginTablet'      => 0,
				'tabsBottomMarginTablet'   => 0,
				'tabsLeftMarginTablet'     => 0,
				'tabsRightMarginTablet'    => 0,
				'tabsTopMarginMobile'      => 0,
				'tabsBottomMarginMobile'   => 0,
				'tabsLeftMarginMobile'     => 0,
				'tabsRightMarginMobile'    => 0,
				'backgroundType'           => '',
				'backgroundColor'          => '',
				'backgroundColor1'         => '',
				'backgroundColor2'         => '',
				'colorLocation1'           => 0,
				'colorLocation2'           => 100,
				'gradientDirection'        => 90,
				'hoverbackgroundColor1'    => '',
				'hoverbackgroundColor2'    => '',
				'hovercolorLocation1'      => 0,
				'hovercolorLocation2'      => 100,
				'hovergradientDirection'   => 90,
				'backgroundHoverColor'     => '',
				'opacity'                  => 20,
				'animationName'            => 'none',
				'animationDirection'       => 'Left',
				'animationRepeat'          => 'once',
				'animationDuration'        => 1000,
				'animationDelay'           => 0,
				'animationCurve'           => '',
				'blockBorderStyle'         => 'none',
				'blockBorderWidth'         => 1,
				'blockBorderRadius'        => 0,
				'blockBorderColor'         => '',
				'boxShadowHOffset'         => 0,
				'boxShadowVOffset'         => 0,
				'boxShadowBlur'            => 0,
				'boxShadowPosition'        => 'outset',
				'boxShadowSpread'          => 0,
				'boxShadowColor'           => '#000',
				'alignTabsVertical'        => 'left',
			);
		}

		/**
		 * Get Tabs Child CSS
		 *
		 * @param array  $attr The block attributes.
		 * @param string $id The selector ID.
		 * @return array Styles.
		 */
		public static function get_responsive_block_tabs_child_css( $attr, $id ) {
			$defaults = self::get_responsive_block_tabs_child_default_attributes();
			$attr     = array_merge( $defaults, (array) $attr );

			$mobile_selectors = array();
			$tablet_selectors = array();

			$selectors        = array();
			$mobile_selectors = array();

			$tablet_selectors = array();

			$combined_selectors = array(
				'desktop' => $selectors,
				'tablet'  => $tablet_selectors,
				'mobile'  => $mobile_selectors,
			);

			$id  = '.responsive-block-editor-addons-block-tabs-child.block-' . $id;
			$css = Responsive_Block_Editor_Addons_Frontend_Styles_Helper::responsive_block_editor_addons_generate_all_css( $combined_selectors, $id );
			return $css;
		}

		/**
		 * Get Defaults for tabs child block
		 *
		 * @return array
		 */
		public static function get_responsive_block_tabs_child_default_attributes() {
			return array();
		}

		/**
		 * Get Taxonomy List CSS
		 *
		 * @param array  $attr The block attributes.
		 * @param string $id The selector ID.
		 * @return array Styles.
		 */
		public static function get_responsive_block_taxonomy_list_css( $attr, $id ) {
			$defaults = self::get_responsive_block_taxonomy_list_block_default_attributes();
			$attr     = array_merge( $defaults, (array) $attr );

			$border_grid             = 'none' !== $attr['gridBorderStyle'] ? self::get_css_value( $attr['gridBorderWidth'], 'px' ) . ' ' . $attr['gridBorderStyle'] . ' ' . $attr['gridBorderColor'] : 'none';
			$border_radius_grid      = 'none' !== $attr['gridBorderStyle'] ? self::get_css_value( $attr['gridBorderRadius'], 'px' ) : 0;
			$border_bottom_color     = 'none' !== $attr['separatorStyle'] ? $attr['separatorColor'] : '';
			$border_bottom_width     = 'none' !== $attr['separatorStyle'] ? $attr['separatorWidth'] : 0;
			$box_shadow_position_css = $attr['boxShadowPosition'];
			if ( 'outset' === $attr['boxShadowPosition'] ) {
				$box_shadow_position_css = '';
			}
			$box_shadow = self::get_css_value( $attr['boxShadowHOffset'], 'px' ) . ' ' . self::get_css_value( $attr['boxShadowVOffset'], 'px' ) . ' ' . self::get_css_value( $attr['boxShadowBlur'], 'px' ) . ' ' . self::get_css_value( $attr['boxShadowSpread'], 'px' ) . ' ' . $attr['boxShadowColor'] . ' ' . $box_shadow_position_css;

			$selectors = array(
				' .responsive-block-editor-addons-block-grid'      => array(
					'display'               => 'grid',
					'grid-template-columns' => 'repeat(' . $attr['columns'] . ', 1fr)',
					'grid-column-gap'       => self::get_css_value( $attr['columnGap'], 'px' ),
					'grid-row-gap'          => self::get_css_value( $attr['rowGap'], 'px' ),
				),
				' .responsive-block-editor-addons-block-box'       => array(
					'border'           => $border_grid,
					'border-radius'    => $border_radius_grid,
					'padding'          => self::get_css_value( $attr['contentPadding'], 'px' ),
					'background-color' => $attr['bgColor'],
					'text-align'       => $attr['alignment'],
					'box-shadow'       => $box_shadow,
				),
				' .responsive-block-editor-addons-block-title'     => array(
					'color'         => $attr['titleColor'],
					'margin-bottom' => self::get_css_value( $attr['titleBottomSpace'], 'px' ),
					'margin-top'    => 0,
					'font-family'   => $attr['titleFontFamily'],
					'font-size'     => self::get_css_value( $attr['titleFontSize'], 'px' ),
					'font-weight'   => $attr['titleFontWeight'],
					'line-height'   => $attr['titleLineHeight'],
				),
				' .responsive-block-editor-addons-block-count'     => array(
					'color'       => $attr['countColor'],
					'font-family' => $attr['countFontFamily'],
					'font-size'   => self::get_css_value( $attr['countFontSize'], 'px' ),
					'font-weight' => $attr['countFontWeight'],
					'line-height' => $attr['countLineHeight'],
				),
				' .responsive-block-editor-addons-block-list-item' => array(
					'list-style'  => $attr['listStyle'],
					'color'       => $attr['listStyleColor'],
					'font-family' => $attr['listFontFamily'],
					'font-size'   => self::get_css_value( $attr['listFontSize'], 'px' ),
					'font-weight' => $attr['listFontWeight'],
					'line-height' => self::get_css_value( $attr['listLineHeight'], 'px' ),
				),
				' .responsive-block-editor-addons-block-list-item:hover' => array(
					'color' => $attr['listStyleColorHover'],
				),
				' .responsive-block-editor-addons-block-link-name' => array(
					'color'   => $attr['listTextColor'],
					'display' => 'inline',
				),
				' .responsive-block-editor-addons-block-link-name:hover' => array(
					'color' => $attr['listTextColorHover'],
				),
				' .responsive-block-editor-addons-block-link-wrap' => array(
					'margin-bottom' => self::get_css_value( $attr['listBottomMargin'], 'px' ),
					'margin-top'    => self::get_css_value( $attr['listTopMargin'], 'px' ),
				),
				' .responsive-block-editor-addons-block-separator' => array(
					'border-bottom-style' => $attr['separatorStyle'],
					'border-bottom-width' => self::get_css_value( $border_bottom_width, 'px' ),
					'border-bottom-color' => $border_bottom_color,
				),
			);

			$mobile_selectors = array(
				' .responsive-block-editor-addons-block-grid'      => array(
					'display'               => 'grid',
					'grid-template-columns' => 'repeat(' . $attr['columnsMobile'] . ', 1fr)',
					'grid-column-gap'       => self::get_css_value( $attr['columnGapMobile'], 'px' ),
					'grid-row-gap'          => self::get_css_value( $attr['rowGapMobile'], 'px' ),
				),
				' .responsive-block-editor-addons-block-box'       => array(
					'padding' => self::get_css_value( $attr['contentPaddingMobile'], 'px' ),
				),
				' .responsive-block-editor-addons-block-title'     => array(
					'font-size'     => self::get_css_value( $attr['titleFontSizeMobile'], 'px' ),
					'margin-bottom' => self::get_css_value( $attr['titleBottomSpaceMobile'], 'px' ),
				),
				' .responsive-block-editor-addons-block-count'     => array(
					'font-size' => self::get_css_value( $attr['countFontSizeMobile'], 'px' ),
				),
				' .responsive-block-editor-addons-block-list-item' => array(
					'font-size' => self::get_css_value( $attr['listFontSizeMobile'], 'px' ),
				),
				' .responsive-block-editor-addons-block-link-wrap' => array(
					'margin-bottom' => self::get_css_value( $attr['listBottomMarginMobile'], 'px' ),
					'margin-top'    => self::get_css_value( $attr['listTopMarginMobile'], 'px' ),
				),
			);

			$tablet_selectors = array(
				' .responsive-block-editor-addons-block-grid'      => array(
					'display'               => 'grid',
					'grid-template-columns' => 'repeat(' . $attr['columnsTablet'] . ', 1fr)',
					'grid-column-gap'       => self::get_css_value( $attr['columnGapTablet'], 'px' ),
					'grid-row-gap'          => self::get_css_value( $attr['rowGapTablet'], 'px' ),
				),
				' .responsive-block-editor-addons-block-box'       => array(
					'padding' => self::get_css_value( $attr['contentPaddingTablet'], 'px' ),
				),
				' .responsive-block-editor-addons-block-title'     => array(
					'font-size'     => self::get_css_value( $attr['titleFontSizeTablet'], 'px' ),
					'margin-bottom' => self::get_css_value( $attr['titleBottomSpaceTablet'], 'px' ),
				),
				' .responsive-block-editor-addons-block-count'     => array(
					'font-size' => self::get_css_value( $attr['countFontSizeTablet'], 'px' ),
				),
				' .responsive-block-editor-addons-block-list-item' => array(
					'font-size' => self::get_css_value( $attr['listFontSizeTablet'], 'px' ),
				),
				' .responsive-block-editor-addons-block-link-wrap' => array(
					'margin-bottom' => self::get_css_value( $attr['listBottomMarginTablet'], 'px' ),
					'margin-top'    => self::get_css_value( $attr['listTopMarginTablet'], 'px' ),
				),
			);

			$combined_selectors = array(
				'desktop' => $selectors,
				'tablet'  => $tablet_selectors,
				'mobile'  => $mobile_selectors,
			);

			$id  = '.responsive-block-editor-addons-block-taxonomy-list.block-' . $id;
			$css = Responsive_Block_Editor_Addons_Frontend_Styles_Helper::responsive_block_editor_addons_generate_all_css( $combined_selectors, $id );
			return $css;
		}

		/**
		 * Get Defaults for Taxnomy List block
		 *
		 * @return array
		 */
		public static function get_responsive_block_taxonomy_list_block_default_attributes() {
			return array(
				'block_id'               => '',
				'columns'                => 3,
				'columnsMobile'          => 1,
				'columnsTablet'          => 2,
				'layout'                 => 'grid',
				'postType'               => 'post',
				'showEmptyTaxonomy'      => false,
				'showPostCount'          => true,
				'taxonomyType'           => 'category',
				'categories'             => '',
				'order'                  => 'desc',
				'orderBy'                => 'date',
				'postsToShow'            => '8',
				'noTaxDisplaytext'       => __( 'Taxonomy Not Available.', 'responsive-block-editor-addons' ),
				'showEmptyTaxonomy'      => false,
				'titleTag'               => 'div',
				'alignment'              => 'center',
				'listStyle'              => 'disc',
				'listStyleColor'         => '#3b3b3b',
				'listStyleColorHover'    => '#3b3b3b',
				'bgColor'                => '#f5f5f5',
				'titleColor'             => '#3b3b3b',
				'countColor'             => '#777777',
				'listTextColor'          => '#3b3b3b',
				'listTextColorHover'     => '#3b3b3b',
				'rowGap'                 => 20,
				'columnGap'              => 20,
				'titleBottomSpace'       => 15,
				'rowGapMobile'           => 20,
				'columnGapMobile'        => 20,
				'titleBottomSpaceMobile' => 15,
				'rowGapTablet'           => 20,
				'columnGapTablet'        => 20,
				'titleBottomSpaceTablet' => 15,
				'contentPadding'         => 15,
				'contentPaddingMobile'   => 15,
				'contentPaddingTablet'   => 15,
				'listBottomMargin'       => 5,
				'listBottomMarginMobile' => 5,
				'listBottomMarginTablet' => 5,
				'listTopMargin'          => 5,
				'listTopMarginMobile'    => 5,
				'listTopMarginTablet'    => 5,
				'titleFontFamily'        => '',
				'titleFontSize'          => 16,
				'titleFontSizeMobile'    => 14,
				'titleFontSizeTablet'    => 16,
				'titleFontWeight'        => '',
				'titleLineHeight'        => '',
				'titleLineHeightMobile'  => '',
				'titleLineHeightTablet'  => '',
				'countFontFamily'        => '',
				'countFontSize'          => 16,
				'countFontSizeMobile'    => 14,
				'countFontSizeTablet'    => 16,
				'countFontWeight'        => '',
				'countLineHeight'        => '',
				'countLineHeightMobile'  => '',
				'countLineHeightTablet'  => '',
				'gridBorderStyle'        => 'solid',
				'gridBorderWidth'        => 1,
				'gridBorderRadius'       => 0,
				'gridBorderColor'        => '#e0e0e0',
				'boxShadow'              => 'none',
				'boxShadowColor'         => '',
				'boxShadowHOffset'       => '',
				'boxShadowVOffset'       => '',
				'boxShadowBlur'          => '',
				'boxShadowSpread'        => '',
				'boxShadowPosition'      => 'outset',
				'listFontFamily'         => '',
				'listFontSize'           => 16,
				'listFontSizeMobile'     => 14,
				'listFontSizeTablet'     => 16,
				'listFontWeight'         => '',
				'listLineHeight'         => '',
				'listLineHeightMobile'   => '',
				'listLineHeightTablet'   => '',
				'separatorStyle'         => 'solid',
				'separatorColor'         => '#b2b4b5',
				'separatorWidth'         => 1,
			);
		}

		/**
		 * Get WP Search Block CSS
		 *
		 * @param array  $attr The block attributes.
		 * @param string $id The selector ID.
		 * @return array Styles.
		 */
		public static function get_responsive_block_wp_search_css( $attr, $id ) {
			$defaults = self::get_responsive_block_wp_search_default_attributes();
			$attr     = array_merge( $defaults, (array) $attr );

			$width = 'px' === $attr['inputWidthType'] ? self::get_css_value( $attr['inputWidth'], 'px' ) : $attr['inputWidth'] . '%';

			$bg_color = 'minimal' === $attr['layout'] ? $attr['inputBackgroundColor'] : '';

			$box_shadow_position_css = $attr['boxShadowPosition'];
			if ( 'outset' === $attr['boxShadowPosition'] ) {
				$box_shadow_position_css = '';
			}
			$box_shadow = self::get_css_value( $attr['boxShadowHOffset'], 'px' ) . ' ' . self::get_css_value( $attr['boxShadowVOffset'], 'px' ) . ' ' . self::get_css_value( $attr['boxShadowBlur'], 'px' ) . ' ' . self::get_css_value( $attr['boxShadowSpread'], 'px' ) . ' ' . $attr['boxShadowColor'] . ' ' . $box_shadow_position_css;

			$button_size = 0 === $attr['buttonWidth'] ? 'button' === $attr['buttonType'] ? 50 : 110 : $attr['buttonWidth'];

			$icon_color = '' === $attr['iconColor'] ? 'classic' === $attr['layout'] ? '#ffffff' : '#1e1e1e' : $attr['iconColor'];

			$input_box_padding     = self::get_css_value( $attr['inputTopPadding'], 'px' ) . ' ' . self::get_css_value( $attr['inputRightPadding'], 'px' ) . ' ' . self::get_css_value( $attr['inputBottomPadding'], 'px' ) . ' ' . self::get_css_value( $attr['inputLeftPadding'], 'px' );
			$button_padding        = 'classic' === $attr['layout'] ? $input_box_padding : 0;
			$icon_and_text_padding = 'minimal' === $attr['layout'] ? $input_box_padding : 0;

			$input_box_padding_mobile     = self::get_css_value( $attr['inputTopPaddingMobile'], 'px' ) . ' ' . self::get_css_value( $attr['inputRightPaddingMobile'], 'px' ) . ' ' . self::get_css_value( $attr['inputBottomPaddingMobile'], 'px' ) . ' ' . self::get_css_value( $attr['inputLeftPaddingMobile'], 'px' );
			$button_padding_mobile        = 'classic' === $attr['layout'] ? $input_box_padding_mobile : 0;
			$icon_and_text_padding_mobile = 'minimal' === $attr['layout'] ? $input_box_padding_mobile : 0;

			$input_box_padding_tablet     = self::get_css_value( $attr['inputTopPaddingTablet'], 'px' ) . ' ' . self::get_css_value( $attr['inputRightPaddingTablet'], 'px' ) . ' ' . self::get_css_value( $attr['inputBottomPaddingTablet'], 'px' ) . ' ' . self::get_css_value( $attr['inputLeftPaddingTablet'], 'px' );
			$button_padding_tablet        = 'classic' === $attr['layout'] ? $input_box_padding_tablet : 0;
			$icon_and_text_padding_tablet = 'minimal' === $attr['layout'] ? $input_box_padding_tablet : 0;

			$border = '0' . $attr['blockBorderStyle'] . $attr['blockBorderColor'];
			if ( '' !== $attr['blockBorderStyle'] ) {
				$border = self::get_css_value( $attr['blockBorderWidth'], 'px' ) . ' ' . $attr['blockBorderStyle'] . ' ' . $attr['blockBorderColor'];
			}
			$input_border_top    = $border;
			$input_border_bottom = $border;
			$input_border_left   = 'classic' === $attr['layout'] ? $border : 0;
			$input_border_right  = 'classic' === $attr['layout'] ? 0 : $border;

			$icon_border_top    = 'minimal' === $attr['layout'] ? $border : 0;
			$icon_border_bottom = 'minimal' === $attr['layout'] ? $border : 0;
			$icon_border_left   = 'minimal' === $attr['layout'] ? $border : 0;
			$icon_border_right  = 0;

			$border_radius        = self::get_css_value( $attr['blockBorderRadius'], 'px' );
			$input_border_radius  = 'classic' === $attr['layout'] ? $border_radius . ' 0 0 ' . $border_radius : '0  ' . $border_radius . ' ' . $border_radius . ' 0';
			$icon_border_radius   = 'minimal' === $attr['layout'] ? $border_radius . ' 0 0 ' . $border_radius : 0;
			$button_border_radius = '0  ' . $border_radius . ' ' . $border_radius . ' 0';

			$mobile_selectors = array();
			$tablet_selectors = array();

			$selectors = array(
				' ' => array(
					'width'         => $width,
					'border-radius' => $border_radius,
					'padding'       => '0 !important',
					'max-width'     => '100%',
				),
				' .responsive-block-editor-addons-search-form__input' => array(
					'border-top'       => $input_border_top . ' !important',
					'border-right'     => $input_border_right . ' !important',
					'border-bottom'    => $input_border_bottom . ' !important',
					'border-left'      => $input_border_left . ' !important',
					'border-radius'    => $input_border_radius,
					'color'            => $attr['inputTextColor'],
					'background-color' => $attr['inputBackgroundColor'],
					'font-size'        => self::get_css_value( $attr['inputFontSize'], 'px' ),
					'font-family'      => $attr['inputFontFamily'],
					'font-weight'      => $attr['inputFontWeight'],
					'line-height'      => $attr['inputLineHeight'],
					'padding'          => $input_box_padding,
				),
				' .responsive-block-editor-addons-search-form__input::placeholder' => array(
					'color'   => $attr['inputTextColor'],
					'opacity' => 0.6,
				),
				' .responsive-block-editor-addons-search-submit' => array(
					'border-top'       => $border . ' !important',
					'border-bottom'    => $border . ' !important',
					'border-left'      => 0 . ' !important',
					'border-right'     => $border . ' !important',
					'border-radius'    => $button_border_radius,
					'padding'          => $button_padding,
					'display'          => 'flex',
					'align-items'      => 'center',
					'justify-content'  => 'center',
					'width'            => self::get_css_value( $button_size, 'px' ),
					'background-color' => $attr['buttonBackgroundColor'],
				),
				' .responsive-block-editor-addons-search-submit:hover' => array(
					'background-color' => $attr['buttonBackgroundHoverColor'],
				),
				' .responsive-block-editor-addons-search-icon-wrap' => array(
					'border-top'       => $icon_border_top . ' !important',
					'border-bottom'    => $icon_border_bottom . ' !important',
					'border-left'      => $icon_border_left . ' !important',
					'border-right'     => $icon_border_right . ' !important',
					'border-radius'    => $icon_border_radius,
					'display'          => 'flex',
					'align-items'      => 'center',
					'justify-content'  => 'center',
					'background-color' => $bg_color,
					'width'            => 'fit-content',
					'padding'          => $icon_and_text_padding,
				),
				' .responsive-block-editor-addons-search-icon-wrap svg' => array(
					'width'  => self::get_css_value( $attr['iconSize'], 'px' ),
					'height' => self::get_css_value( $attr['iconSize'], 'px' ),
					'fill'   => $icon_color,
				),
				'  .responsive-block-editor-addons-search-submit:hover .responsive-block-editor-addons-search-icon-wrap svg' => array(
					'fill' => $attr['iconHoverColor'],
				),
				' .responsive-block-editor-addons-search-button-text' => array(
					'color'       => $attr['buttonTextColor'],
					'font-family' => $attr['buttonFontFamily'],
					'font-size'   => self::get_css_value( $attr['buttonFontSize'], 'px' ),
					'font-weight' => $attr['buttonFontWeight'],
					'line-height' => $attr['buttonLineHeight'],
					'padding'     => $icon_and_text_padding,
				),
				' .responsive-block-editor-addons-search-button-text:hover' => array(
					'color' => $attr['buttonTextHoverColor'],
				),
			);

			if ( 'inset' === $attr['boxShadowPosition'] ) {
				$selectors[' .responsive-block-editor-addons-search-form__input']['box-shadow'] = $box_shadow;
			} else {
				$selectors[' ']['box-shadow'] = $box_shadow;
			}

			$mobile_selectors = array(
				' .responsive-block-editor-addons-search-form__input' => array(
					'font-size' => self::get_css_value( $attr['inputFontSizeMobile'], 'px' ),
					'padding'   => $input_box_padding_mobile,
				),
				' .responsive-block-editor-addons-search-icon-wrap' => array(
					'padding' => $icon_and_text_padding_mobile,
				),
				' .responsive-block-editor-addons-search-button-text' => array(
					'font-size' => self::get_css_value( $attr['buttonFontSizeMobile'], 'px' ),
					'padding'   => $icon_and_text_padding_mobile,
				),
				' .responsive-block-editor-addons-search-submit' => array(
					'padding' => $button_padding_mobile,
				),
			);

			$tablet_selectors = array(
				' .responsive-block-editor-addons-search-form__input' => array(
					'font-size' => self::get_css_value( $attr['inputFontSizeTablet'], 'px' ),
					'padding'   => $input_box_padding_tablet,
				),
				' .responsive-block-editor-addons-search-icon-wrap' => array(
					'padding' => $icon_and_text_padding_tablet,
				),
				' .responsive-block-editor-addons-search-button-text' => array(
					'font-size' => self::get_css_value( $attr['buttonFontSizeTablet'], 'px' ),
					'padding'   => $icon_and_text_padding_tablet,
				),
				' .responsive-block-editor-addons-search-submit' => array(
					'padding' => $button_padding_tablet,
				),
			);

			$combined_selectors = array(
				'desktop' => $selectors,
				'tablet'  => $tablet_selectors,
				'mobile'  => $mobile_selectors,
			);

			$id  = '.responsive-block-editor-addons-block-wp-search.block-' . $id;
			$css = Responsive_Block_Editor_Addons_Frontend_Styles_Helper::responsive_block_editor_addons_generate_all_css( $combined_selectors, $id );
			return $css;
		}

		/**
		 * Get Defaults for wp search block
		 *
		 * @return array
		 */
		public static function get_responsive_block_wp_search_default_attributes() {
			return array(
				'layout'                     => 'classic',
				'inputWidth'                 => 100,
				'inputWidthType'             => '%',
				'inputTextColor'             => '',
				'inputBackgroundColor'       => '#eceeef',
				'blockBorderColor'           => '',
				'blockBorderRadius'          => 1,
				'blockBorderStyle'           => 'none',
				'blockBorderWidth'           => 0,
				'inputTopPadding'            => 15,
				'inputRightPadding'          => 15,
				'inputRightPadding'          => 15,
				'inputRightPadding'          => 15,
				'inputBottomPadding'         => 15,
				'inputLeftPadding'           => 15,
				'inputTopPaddingMobile'      => 15,
				'inputRightPaddingMobile'    => 15,
				'inputBottomPaddingMobile'   => 15,
				'inputLeftPaddingMobile'     => 15,
				'inputTopPaddingTablet'      => 15,
				'inputRightPaddingTablet'    => 15,
				'inputBottomPaddingTablet'   => 15,
				'inputLeftPaddingTablet'     => 15,
				'inputFontFamily'            => '',
				'inputFontSize'              => 16,
				'inputFontSizeMobile'        => 16,
				'inputFontSizeTablet'        => 16,
				'inputFontWeight'            => '100',
				'inputLineHeight'            => 1,
				'boxShadowColor'             => '',
				'boxShadowHOffset'           => 0,
				'boxShadowVOffset'           => 0,
				'boxShadowBlur'              => '',
				'boxShadowSpread'            => '',
				'boxShadowPosition'          => 'outset',
				'iconSize'                   => 16,
				'iconColor'                  => '',
				'iconHoverColor'             => '',
				'buttonType'                 => 'button',
				'buttonWidth'                => 0,
				'buttonBackgroundColor'      => '#ff6f61',
				'buttonBackgroundHoverColor' => '',
				'buttonTextColor'            => '#313131',
				'buttonTextHoverColor'       => '',
				'buttonFontFamily'           => '',
				'buttonFontSize'             => 16,
				'buttonFontSizeMobile'       => 16,
				'buttonFontSizeTablet'       => 16,
				'buttonFontWeight'           => '100',
				'buttonLineHeight'           => 1,
			);
		}

		/**
		 * Get Instagram Block CSS
		 *
		 * @param array  $attr The block attributes.
		 * @param string $id The selector ID.
		 * @return array Styles.
		 */
		public static function get_responsive_block_instagram_css( $attr, $id ) {
			$defaults = self::get_responsive_block_instagram_default_attributes();
			$attr     = array_merge( $defaults, (array) $attr );

			$mobile_selectors = array();
			$tablet_selectors = array();

			$selectors = array(
				' ' => array(
					'padding-top'    => self::get_css_value( $attr['instaTopPadding'], 'px' ) . '!important',
					'padding-bottom' => self::get_css_value( $attr['instaBottomPadding'], 'px' ) . '!important',
					'padding-left'   => self::get_css_value( $attr['instaLeftPadding'], 'px' ) . '!important',
					'padding-right'  => self::get_css_value( $attr['instaRightPadding'], 'px' ) . '!important',
					'margin-top'     => self::get_css_value( $attr['instaTopMargin'], 'px' ) . '!important',
					'margin-bottom'  => self::get_css_value( $attr['instaBottomMargin'], 'px' ) . '!important',
					'margin-left'    => self::get_css_value( $attr['instaLeftMargin'], 'px' ),
					'margin-right'   => self::get_css_value( $attr['instaRightMargin'], 'px' ),
				),
				' .responsive-block-editor-addons-intro-page' => array(
					'border'  => '1px solid black',
					'padding' => '0 20px',
				),
				' .responsive-block-editor-addons-intro-page p > a' => array(
					'color' => 'blue',
				),
				' .responsive-block-editor-addons-instagram-posts-container' => array(
					'grid-template-columns' => 'repeat(' . $attr['columns'] . ', 1fr)',
					'grid-gap'              => self::get_css_value( $attr['imagesGap'], 'px' ),
				),
				' .responsive-block-editor-addons-instagram-image' => array(
					'border-radius' => self::get_css_value( $attr['borderRadius'], '%' ),
				),
				' .responsive-block-editor-addons-instagram-wrapper' => array(
					'width' => self::get_css_value( 100, '%' ),
				),
			);

			$mobile_selectors = array(
				' ' => array(
					'padding-top'    => self::get_css_value( $attr['instaTopPaddingMobile'], 'px' ) . '!important',
					'padding-bottom' => self::get_css_value( $attr['instaBottomPaddingMobile'], 'px' ) . '!important',
					'padding-left'   => self::get_css_value( $attr['instaLeftPaddingMobile'], 'px' ) . '!important',
					'padding-right'  => self::get_css_value( $attr['instaRightPaddingMobile'], 'px' ) . '!important',
					'margin-top'     => self::get_css_value( $attr['instaTopMarginMobile'], 'px' ) . '!important',
					'margin-bottom'  => self::get_css_value( $attr['instaBottomMarginMobile'], 'px' ) . '!important',
					'margin-left'    => self::get_css_value( $attr['instaLeftMarginMobile'], 'px' ) . '!important',
					'margin-right'   => self::get_css_value( $attr['instaRightMarginMobile'], 'px' ) . '!important',
				),
				' .responsive-block-editor-addons-instagram-posts-container' => array(
					'grid-template-columns' => 'repeat(' . $attr['columnsMobile'] . ', 1fr)',
				),
			);

			$tablet_selectors = array(
				' ' => array(
					'padding-top'    => self::get_css_value( $attr['instaTopPaddingTablet'], 'px' ) . '!important',
					'padding-bottom' => self::get_css_value( $attr['instaBottomPaddingTablet'], 'px' ) . '!important',
					'padding-left'   => self::get_css_value( $attr['instaLeftPaddingTablet'], 'px' ) . '!important',
					'padding-right'  => self::get_css_value( $attr['instaRightPaddingTablet'], 'px' ) . '!important',
					'margin-top'     => self::get_css_value( $attr['instaTopMarginTablet'], 'px' ) . '!important',
					'margin-bottom'  => self::get_css_value( $attr['instaBottomMarginTablet'], 'px' ) . '!important',
					'margin-left'    => self::get_css_value( $attr['instaLeftMarginTablet'], 'px' ) . '!important',
					'margin-right'   => self::get_css_value( $attr['instaRightMarginTablet'], 'px' ) . '!important',
				),
				' .responsive-block-editor-addons-instagram-posts-container' => array(
					'grid-template-columns' => 'repeat(' . $attr['columnsTablet'] . ', 1fr)',
				),
			);

			$combined_selectors = array(
				'desktop' => $selectors,
				'tablet'  => $tablet_selectors,
				'mobile'  => $mobile_selectors,
			);

			$id  = '.responsive-block-editor-addons-block-instagram.block-' . $id;
			$css = Responsive_Block_Editor_Addons_Frontend_Styles_Helper::responsive_block_editor_addons_generate_all_css( $combined_selectors, $id );

			return $css;
		}

		/**
		 * Get Defaults for instagram block
		 *
		 * @return array
		 */
		public static function get_responsive_block_instagram_default_attributes() {
			return array(
				'block_id'                 => '',
				'token'                    => '',
				'columns'                  => '4',
				'columnsMobile'            => '',
				'columnsTablet'            => '',
				'numberOfItems'            => 4,
				'gridGap'                  => 0,
				'thumbs'                   => '',
				'backgroundColor'          => 'transparent',
				'borderRadius'             => 0,
				'hasEqualImages'           => false,
				'showCaptions'             => false,
				'instaTopPadding'          => 0,
				'instaBottomPadding'       => 0,
				'instaRightPadding'        => 0,
				'instaLeftPadding'         => 0,
				'instaTopPaddingMobile'    => '',
				'instaBottomPaddingMobile' => '',
				'instaRightPaddingMobile'  => '',
				'instaLeftPaddingMobile'   => '',
				'instaTopPaddingTablet'    => '',
				'instaBottomPaddingTablet' => '',
				'instaRightPaddingTablet'  => '',
				'instaLeftPaddingTablet'   => '',
				'instaTopMargin'           => 0,
				'instaBottomMargin'        => 0,
				'instaRightMargin'         => 0,
				'instaLeftMargin'          => 0,
				'instaTopMarginMobile'     => '',
				'instaBottomMarginMobile'  => '',
				'instaRightMarginMobile'   => '',
				'instaLeftMarginMobile'    => '',
				'instaTopMarginTablet'     => '',
				'instaBottomMarginTablet'  => '',
				'instaRightMarginTablet'   => '',
				'instaLeftMarginTablet'    => '',
				'gridSize'                 => 700,
				'imagesGap'                => 0,
			);
		}

		/**
		 * Get Image Hotspot Block CSS
		 *
		 * @param array  $attr The block attributes.
		 * @param string $id The selector ID.
		 * @return array Styles.
		 */
		public static function get_responsive_block_image_hotspot_css( $attr, $id ) {
			$defaults = self::get_responsive_block_image_hotspot_default_attributes();
			$attr     = array_merge( $defaults, (array) $attr );

			$mobile_selectors = array();
			$tablet_selectors = array();

			$updated_point_opacity = $attr['pointOpacity'] / 100;

			$selectors = array(
				' '                                      => array(
					'display'         => 'flex',
					'justify-content' => 'center',
				),
				' .responsive_block_addons__image'       => array(
					'display' => 'block',
				),
				' .responsive_block_addons__dot'         => array(
					'left'                      => 0,
					'top'                       => 0,
					'opacity'                   => $updated_point_opacity,
					'outline'                   => 'none',
					'cursor'                    => 'pointer',
					'position'                  => 'absolute',
					'border-radius'             => '50%',
					'background-color'          => $attr['pointBackgroundColor'],
					'padding'                   => self::get_css_value( $attr['pointSpacing'], 'px' ),
					'animation-name'            => $attr['animationName'] . '' . $attr['animationDirection'],
					'animation-timing-function' => $attr['animationCurve'],
					'animation-duration'        => $attr['animationDuration'] . 'ms',
					'animation-delay'           => $attr['animationDelay'] . 'ms',
					'animation-iteration-count' => 'once' === $attr['animationRepeat'] ? 1 : 'infinite',
				),
				' .responsive_block_addons__dot::before' => array(
					'display'   => $attr['pulseEffect'] ? 'block' : 'none',
					'animation' => $attr['pulseEffect'] ? '' : 'none',
				),
				' .responsive_block_addons__dot-content svg' => array(
					'font-size' => self::get_css_value( $attr['hotspotSize'], 'px' ),
				),
				' .responsive_block_addons__dot-content' => array(
					'fill' => $attr['iconColor'],
				),
				' .responsive_block_addons__wrapper'     => array(
					'position' => 'relative',
				),
			);

			$mobile_selectors = array();

			$tablet_selectors = array();

			$combined_selectors = array(
				'desktop' => $selectors,
				'tablet'  => $tablet_selectors,
				'mobile'  => $mobile_selectors,
			);

			$id  = '.responsive-block-editor-addons-block-image-hotspot.block-' . $id;
			$css = Responsive_Block_Editor_Addons_Frontend_Styles_Helper::responsive_block_editor_addons_generate_all_css( $combined_selectors, $id );

			return $css;
		}

		/**
		 * Get Defaults for image hotspot block
		 *
		 * @return array
		 */
		public static function get_responsive_block_image_hotspot_default_attributes() {
			return array(
				'block_id'             => '',
				'pointBackgroundColor' => '#333',
				'iconColor'            => '#fff',
				'pointOpacity'         => 100,
				'animationName'        => 'none',
				'animationDirection'   => 'Left',
				'animationRepeat'      => 'once',
				'animationDuration'    => 1000,
				'animationDelay'       => 0,
				'animationCurve'       => '',
				'pulseEffect'          => true,
				'hotspotSize'          => 16,
				'pointSpacing'         => 6,
			);
		}

		/**
		 * Get Advanced Text CSS
		 *
		 * @param array  $attr The block attributes.
		 * @param string $id The selector ID.
		 * @return array Styles.
		 */
		public static function get_responsive_block_advanced_text_css( $attr, $id ) {
			$defaults = self::get_responsive_block_advanced_text_default_attributes();
			$attr     = array_merge( $defaults, (array) $attr );

			$mobile_selectors = array();
			$tablet_selectors = array();

			$block_opacity = $attr['blockOpacity'] / 100;

			$selectors = array(
				' '                                      => array(
					'opacity'        => $block_opacity,
					'z-index'        => $attr['zIndex'],
					'max-width'      => self::get_css_value( $attr['containerWidth'], 'px' ),
					'margin-top'     => self::get_css_value( $attr['containerTopMargin'], 'px!important' ),
					'margin-right'   => self::get_css_value( $attr['containerRightMargin'], 'px!important' ),
					'margin-bottom'  => self::get_css_value( $attr['containerBottomMargin'], 'px!important' ),
					'margin-left'    => self::get_css_value( $attr['containerLeftMargin'], 'px!important' ),
					'padding-top'    => self::get_css_value( $attr['containerTopPadding'], 'px!important' ),
					'padding-right'  => self::get_css_value( $attr['containerRightPadding'], 'px!important' ),
					'padding-bottom' => self::get_css_value( $attr['containerBottomPadding'], 'px!important' ),
					'padding-left'   => self::get_css_value( $attr['containerLeftPadding'], 'px!important' ),
					'text-align'     => $attr['contentAlign'],
				),
				' .responsive-block-editor-addons-text-container' => array(
					'padding' => self::get_css_value( $attr['contentPadding'], 'px' ),
				),
				' .responsive-block-editor-addons-separator' => array(
					'height'           => self::get_css_value( $attr['columnDividerHeight'], 'px' ),
					'width'            => self::get_css_value( $attr['columnDividerWidth'], 'px' ),
					'background-color' => $attr['dividerColor'],
				),
				' .responsive-block-editor-addons-title' => array(
					'margin-bottom' => self::get_css_value( $attr['titleBottomMargin'], 'px' ),
					'margin-left'   => 'right' !== $attr['contentAlign'] || ( 'right' === $attr['contentAlign'] && '' !== $attr['titleAlign'] ) ? 'right' !== $attr['titleAlign'] ? self::get_css_value( $attr['titleLeftMargin'], 'px' ) : '' : '',
					'margin-right'  => 'left' !== $attr['contentAlign'] || ( 'left' === $attr['contentAlign'] && '' !== $attr['titleAlign'] ) ? 'left' !== $attr['titleAlign'] ? self::get_css_value( $attr['titleRightMargin'], 'px' ) : '' : '',
					'text-align'    => $attr['titleAlign'],
					'color'         => $attr['titleColor'],
					'font-family'   => $attr['titleFontFamily'],
					'font-size'     => self::get_css_value( $attr['titleFontSize'], 'px' ),
					'font-weight'   => $attr['titleFontWeight'],
					'line-height'   => $attr['titleLineHeight'],
				),
				' .responsive-block-editor-addons-subtitle' => array(
					'margin-bottom' => self::get_css_value( $attr['subtitleBottomMargin'], 'px' ),
					'margin-left'   => 'right' !== $attr['contentAlign'] || ( 'right' === $attr['contentAlign'] && '' !== $attr['subtitleAlign'] ) ? 'right' !== $attr['subtitleAlign'] ? self::get_css_value( $attr['subtitleLeftMargin'], 'px' ) : '' : '',
					'margin-right'  => 'left' !== $attr['contentAlign'] || ( 'left' === $attr['contentAlign'] && '' !== $attr['subtitleAlign'] ) ? 'left' !== $attr['subtitleAlign'] ? self::get_css_value( $attr['subtitleRightMargin'], 'px' ) : '' : '',
					'text-align'    => $attr['subtitleAlign'],
					'color'         => $attr['subtitleColor'],
					'font-family'   => $attr['subtitleFontFamily'],
					'font-size'     => self::get_css_value( $attr['subtitleFontSize'], 'px' ),
					'font-weight'   => $attr['subtitleFontWeight'],
					'line-height'   => $attr['subtitleLineHeight'],
				),
				' .responsive-block-editor-addons-text-content' => array(
					'margin-bottom' => self::get_css_value( $attr['textBottomMargin'], 'px' ),
					'text-align'    => $attr['textAlign'],
					'color'         => $attr['textColor'],
					'font-family'   => $attr['textFontFamily'],
					'font-size'     => self::get_css_value( $attr['textFontSize'], 'px' ),
					'font-weight'   => $attr['textFontWeight'],
					'line-height'   => $attr['textLineHeight'],
				),
			);

			$mobile_selectors = array(
				' '                                      => array(
					'max-width'      => self::get_css_value( $attr['containerWidthMobile'], 'px' ),
					'margin-top'     => self::get_css_value( $attr['containerTopMarginMobile'], 'px!important' ),
					'margin-right'   => self::get_css_value( $attr['containerRightMarginMobile'], 'px!important' ),
					'margin-bottom'  => self::get_css_value( $attr['containerBottomMarginMobile'], 'px!important' ),
					'margin-left'    => self::get_css_value( $attr['containerLeftMarginMobile'], 'px!important' ),
					'padding-top'    => self::get_css_value( $attr['containerTopPaddingMobile'], 'px!important' ),
					'padding-right'  => self::get_css_value( $attr['containerRightPaddingMobile'], 'px!important' ),
					'padding-bottom' => self::get_css_value( $attr['containerBottomPaddingMobile'], 'px!important' ),
					'padding-left'   => self::get_css_value( $attr['containerLeftPaddingMobile'], 'px!important' ),
				),
				' .responsive-block-editor-addons-text-container' => array(
					'padding'               => self::get_css_value( $attr['contentPaddingMobile'], 'px' ),
					'grid-auto-flow'        => 'row',
					'grid-template-columns' => '1fr',
					'grid-row-gap'          => '10px',
				),
				' .responsive-block-editor-addons-separator' => array(
					'height' => $attr['columnDividerHeightMobile'] . 'px',
					'width'  => self::get_css_value( $attr['columnDividerWidthMobile'], 'px' ),
				),
				' .responsive-block-editor-addons-title' => array(
					'margin-bottom' => self::get_css_value( $attr['titleBottomMarginMobile'], 'px' ),
					'margin-left'   => 'right' !== $attr['titleAlign'] ? self::get_css_value( $attr['titleLeftMarginMobile'], 'px' ) : '',
					'margin-right'  => 'left' !== $attr['titleAlign'] ? self::get_css_value( $attr['titleRightMarginMobile'], 'px' ) : '',
					'font-size'     => self::get_css_value( $attr['titleFontSizeMobile'], 'px' ),
				),
				' .responsive-block-editor-addons-subtitle' => array(
					'margin-bottom' => self::get_css_value( $attr['subtitleBottomMarginMobile'], 'px' ),
					'margin-left'   => 'right' !== $attr['subtitleAlign'] ? self::get_css_value( $attr['subtitleLeftMarginMobile'], 'px' ) : '',
					'margin-right'  => 'left' !== $attr['subtitleAlign'] ? self::get_css_value( $attr['subtitleRightMarginMobile'], 'px' ) : '',
					'font-size'     => self::get_css_value( $attr['subtitleFontSizeMobile'], 'px' ),
				),
				' .responsive-block-editor-addons-text-content' => array(
					'margin-bottom' => self::get_css_value( $attr['textBottomMarginMobile'], 'px' ),
					'font-size'     => self::get_css_value( $attr['textFontSizeMobile'], 'px' ),
				),
			);

			$tablet_selectors = array(
				' '                                      => array(
					'max-width'      => self::get_css_value( $attr['containerWidthTablet'], 'px' ),
					'margin-top'     => self::get_css_value( $attr['containerTopMarginTablet'], 'px!important' ),
					'margin-right'   => self::get_css_value( $attr['containerRightMarginTablet'], 'px!important' ),
					'margin-bottom'  => self::get_css_value( $attr['containerBottomMarginTablet'], 'px!important' ),
					'margin-left'    => self::get_css_value( $attr['containerLeftMarginTablet'], 'px!important' ),
					'padding-top'    => self::get_css_value( $attr['containerTopPaddingTablet'], 'px!important' ),
					'padding-right'  => self::get_css_value( $attr['containerRightPaddingTablet'], 'px!important' ),
					'padding-bottom' => self::get_css_value( $attr['containerBottomPaddingTablet'], 'px!important' ),
					'padding-left'   => self::get_css_value( $attr['containerLeftPaddingTablet'], 'px!important' ),
				),
				' .responsive-block-editor-addons-text-container' => array(
					'padding' => self::get_css_value( $attr['contentPaddingTablet'], 'px' ),
				),
				' .responsive-block-editor-addons-title' => array(
					'margin-bottom' => self::get_css_value( $attr['titleBottomMarginTablet'], 'px' ),
					'margin-left'   => 'right' !== $attr['titleAlign'] ? self::get_css_value( $attr['titleLeftMarginTablet'], 'px' ) : '',
					'margin-right'  => 'left' !== $attr['titleAlign'] ? self::get_css_value( $attr['titleRightMarginTablet'], 'px' ) : '',
					'font-size'     => self::get_css_value( $attr['titleFontSizeTablet'], 'px' ),
				),
				' .responsive-block-editor-addons-subtitle' => array(
					'margin-bottom' => self::get_css_value( $attr['subtitleBottomMarginTablet'], 'px' ),
					'margin-left'   => 'right' !== $attr['subtitleAlign'] ? self::get_css_value( $attr['subtitleLeftMarginTablet'], 'px' ) : '',
					'margin-right'  => 'left' !== $attr['subtitleAlign'] ? self::get_css_value( $attr['subtitleRightMarginTablet'], 'px' ) : '',
					'font-size'     => self::get_css_value( $attr['subtitleFontSizeTablet'], 'px' ),
				),
				' .responsive-block-editor-addons-text-content' => array(
					'margin-bottom' => self::get_css_value( $attr['textBottomMarginTablet'], 'px' ),
					'font-size'     => self::get_css_value( $attr['textFontSizeTablet'], 'px' ),
				),
				' .responsive-block-editor-addons-separator' => array(
					'height' => $attr['columnDividerHeightTablet'] . 'px',
					'width'  => self::get_css_value( $attr['columnDividerWidthTablet'], 'px' ),
				),
			);

			$combined_selectors = array(
				'desktop' => $selectors,
				'tablet'  => $tablet_selectors,
				'mobile'  => $mobile_selectors,
			);

			$id  = '.responsive-block-editor-addons-block-advanced-text.block-' . $id;
			$css = Responsive_Block_Editor_Addons_Frontend_Styles_Helper::responsive_block_editor_addons_generate_all_css( $combined_selectors, $id );

			return $css;
		}


		/**
		 * Get Defaults for Advanced Text block
		 *
		 * @return array
		 */
		public static function get_responsive_block_advanced_text_default_attributes() {
			return array(
				'block_id'                     => '',
				'blockOpacity'                 => 100,
				'zIndex'                       => '',
				'blockTag'                     => 'div',
				'containerWidth'               => '',
				'containerWidthTablet'         => '',
				'containerWidthMobile'         => '',
				'containerTopPadding'          => '',
				'containerBottomPadding'       => '',
				'containerLeftPadding'         => '',
				'containerRightPadding'        => '',
				'containerTopPaddingTablet'    => '',
				'containerBottomPaddingTablet' => '',
				'containerLeftPaddingTablet'   => '',
				'containerRightPaddingTablet'  => '',
				'containerTopPaddingMobile'    => '',
				'containerBottomPaddingMobile' => '',
				'containerLeftPaddingMobile'   => '',
				'containerRightPaddingMobile'  => '',
				'containerTopMargin'           => '',
				'containerBottomMargin'        => '',
				'containerLeftMargin'          => '',
				'containerRightMargin'         => '',
				'containerTopMarginTablet'     => '',
				'containerBottomMarginTablet'  => '',
				'containerLeftMarginTablet'    => '',
				'containerRightMarginTablet'   => '',
				'containerTopMarginMobile'     => '',
				'containerBottomMarginMobile'  => '',
				'containerLeftMarginMobile'    => '',
				'containerRightMarginMobile'   => '',
				'contentAlign'                 => 'left',
				'columnDividerHeight'          => 100,
				'columnDividerHeightTablet'    => '',
				'columnDividerHeightMobile'    => 2,
				'columnDividerWidth'           => 2,
				'columnDividerWidthTablet'     => '',
				'columnDividerWidthMobile'     => 100,
				'dividerColor'                 => '#ccc',
				'contentPadding'               => '',
				'contentPaddingTablet'         => '',
				'contentPaddingMobile'         => '',
				'titleBottomMargin'            => 10,
				'titleBottomMarginTablet'      => '',
				'titleBottomMarginMobile'      => '',
				'subtitleBottomMargin'         => 15,
				'subtitleBottomMarginTablet'   => '',
				'subtitleBottomMarginMobile'   => '',
				'textBottomMargin'             => 0,
				'textBottomMarginTablet'       => '',
				'textBottomMarginMobile'       => '',
				'subtitleAlign'                => '',
				'titleAlign'                   => '',
				'textAlign'                    => '',
				'textColor'                    => '',
				'titleColor'                   => '',
				'subtitleColor'                => '',
				'textFontFamily'               => '',
				'textFontSize'                 => '',
				'textFontSizeMobile'           => '',
				'textFontSizeTablet'           => '',
				'textFontWeight'               => '',
				'textLineHeight'               => '',
				'titleFontFamily'              => '',
				'titleFontSize'                => '',
				'titleFontSizeMobile'          => '',
				'titleFontSizeTablet'          => '',
				'titleFontWeight'              => '',
				'titleLineHeight'              => '',
				'subtitleFontFamily'           => '',
				'subtitleFontSize'             => '',
				'subtitleFontSizeMobile'       => '',
				'subtitleFontSizeTablet'       => '',
				'subtitleFontWeight'           => '',
				'subtitleLineHeight'           => '',
				'titleLeftMargin'              => '',
				'titleLeftMarginTablet'        => '',
				'titleLeftMarginMobile'        => '',
				'titleRightMargin'             => '',
				'titleRightMarginTablet'       => '',
				'titleRightMarginMobile'       => '',
				'subtitleLeftMargin'           => '',
				'subtitleLeftMarginTablet'     => '',
				'subtitleLeftMarginMobile'     => '',
				'subtitleRightMargin'          => '',
				'subtitleRightMarginTablet'    => '',
				'subtitleRightMarginMobile'    => '',
			);
		}

		/**
		 * Get Feature Grid CSS
		 *
		 * @param array  $attr The block attributes.
		 * @param string $id The selector ID.
		 * @return array Styles.
		 */
		public static function get_responsive_block_feature_grid_css( $attr, $id ) {
			$defaults = self::get_responsive_block_feature_grid_default_attributes();
			$attr     = array_merge( $defaults, (array) $attr );

			$mobile_selectors = array();
			$tablet_selectors = array();

			$imgopacity             = $attr['opacity'] / 100;
			$columnbackcoloropacity = $attr['columnBackColorOpacity'] / 100;

			$box_shadow_position_css = $attr['boxShadowPosition'];
			if ( 'outset' === $attr['boxShadowPosition'] ) {
				$box_shadow_position_css = '';
			}

			$button_box_shadow_position_css = $attr['buttonBoxShadowPosition'];
			if ( 'outset' === $attr['buttonBoxShadowPosition'] ) {
				$button_box_shadow_position_css = '';
			}

			$updated_button_bg_h_color = '';
			$updated_button_bg_h_image = '';
			if ( 'color' === $attr['buttonHbackgroundType'] ) {
				$updated_button_bg_h_color = $attr['ctaHoverBackColor'];
			} elseif ( 'gradient' === $attr['buttonHbackgroundType'] ) {
				$updated_button_bg_h_image = 'linear-gradient(' . $attr['buttonHgradientDirection'] . 'deg, ' . $attr['buttonHbackgroundColor1'] . ' ' . $attr['buttonHcolorLocation1'] . '%, ' . $attr['buttonHbackgroundColor2'] . ' ' . $attr['buttonHcolorLocation2'] . '%)';
			}

			$updated_button_background_color = '';
			$updated_button_background_image = '';
			if ( 'color' === $attr['buttonbackgroundType'] ) {
				$updated_button_background_color = $attr['ctaBackColor'];
			} elseif ( 'gradient' === $attr['buttonbackgroundType'] ) {
				$updated_button_background_image = 'linear-gradient(' . $attr['buttongradientDirection'] . 'deg, ' . $attr['buttonbackgroundColor1'] . ' ' . $attr['buttoncolorLocation1'] . '%, ' . $attr['buttonbackgroundColor2'] . ' ' . $attr['buttoncolorLocation2'] . '%)';
			}

			$selectors = array(
				' ' => array(
					'text-align'     => $attr['blockAlign'],
					'padding-top'    => self::get_css_value( $attr['blockTopPadding'], 'px' ),
					'padding-bottom' => self::get_css_value( $attr['blockBottomPadding'], 'px' ),
					'padding-left'   => self::get_css_value( $attr['blockLeftPadding'], 'px' ),
					'padding-right'  => self::get_css_value( $attr['blockRightPadding'], 'px' ),

				),
				' .wp-block-responsive-block-editor-addons-feature-grid-item' => array(
					'border-color'     => $attr['blockBorderColor'],
					'border-style'     => $attr['blockBorderStyle'],
					'border-radius'    => self::get_css_value( $attr['blockBorderRadius'], 'px' ),
					'border-width'     => self::get_css_value( $attr['blockBorderWidth'], 'px' ),
					'padding-top'      => self::get_css_value( $attr['columnTopPadding'], 'px' ),
					'padding-bottom'   => self::get_css_value( $attr['columnBottomPadding'], 'px' ),
					'padding-left'     => self::get_css_value( $attr['columnLeftPadding'], 'px' ),
					'padding-right'    => self::get_css_value( $attr['columnRightPadding'], 'px' ),
					'background-color' =>
						'color' === $attr['backgroundType']
							? self::hex_to_rgb( $attr['backgroundColor'], $columnbackcoloropacity )
							: '#fff',
					'background-image' =>
						'gradient' === $attr['backgroundType']
							? self::generate_background_image_effect(
								self::hex_to_rgb(
									$attr['backgroundColor1'],
									$columnbackcoloropacity
								),
								self::hex_to_rgb(
									$attr['backgroundColor2'],
									$columnbackcoloropacity
								),
								$attr['gradientDirection'],
								$attr['colorLocation1'],
								$attr['colorLocation2']
							)
							: null,
					'box-shadow'       =>
						self::get_css_value( $attr['boxShadowHOffset'], 'px' ) .
						' ' .
						self::get_css_value( $attr['boxShadowVOffset'], 'px' ) .
						' ' .
						self::get_css_value( $attr['boxShadowBlur'], 'px' ) .
						' ' .
						self::get_css_value( $attr['boxShadowSpread'], 'px' ) .
						' ' .
						$attr['boxShadowColor'] .
						' ' .
						$box_shadow_position_css,
				),
				' .wp-block-responsive-block-editor-addons-feature-grid-item.background-type-image' => array(
					'background-image'      => 'linear-gradient(' .
						self::hex_to_rgb( '#fff', 1 - $imgopacity ) .
						',' .
						self::hex_to_rgb( '#fff', 1 - $imgopacity ) .
						'),' .
						'url(' . $attr['backgroundImage'] . ')',
					'background-position'   => 'center center',
					'background-attachment' => 'scroll',
					'background-repeat'     => 'no-repeat',
					'background-size'       => 'cover',
				),
				' .responsive-block-editor-addons-feature-image' => array(
					'width' => self::get_css_value( $attr['imageWidth'], 'px' ),
				),
				' .responsive-block-editor-addons-feature-image-wrap' => array(
					'margin-bottom' => self::get_css_value( $attr['imageSpace'], 'px' ),
				),
				' .wp-block-responsive-block-editor-addons-feature-grid-item__title' => array(
					'color'          => $attr['titleColor'],
					'line-height'    => $attr['titleLineHeight'],
					'font-weight'    => $attr['titleFontWeight'],
					'font-size'      => self::get_css_value( $attr['titleFontSize'], 'px' ),
					'text-transform' => $attr['titleTextTransform'],
					'font-family'    => $attr['titleFontFamily'],
					'margin-bottom'  => self::get_css_value( $attr['titleSpace'], 'px' ),
				),
				' .wp-block-responsive-block-editor-addons-feature-grid-item__desc' => array(
					'color'          => $attr['descColor'],
					'line-height'    => $attr['descLineHeight'],
					'text-transform' => $attr['descTextTransform'],
					'font-weight'    => $attr['descFontWeight'],
					'font-size'      => self::get_css_value( $attr['descFontSize'], 'px' ),
					'font-family'    => $attr['descFontFamily'],
					'margin-bottom'  => self::get_css_value( $attr['descSpace'], 'px' ),
				),
				' .wp-block-responsive-block-editor-addons-feature-grid-item__button' => array(
					'display'          => 'block',
					'margin-left'      => 'left' === $attr['blockAlign'] ? 0 : '',
					'margin-right'     => 'right' === $attr['blockAlign'] ? 0 : '',
					'color'            => $attr['ctaColor'],
					'background-color' => $updated_button_background_color,
					'background-image' => $updated_button_background_image,
					'border-color'     => $attr['ctaBorderColor'],
					'border-style'     => $attr['ctaBorderStyle'],
					'border-radius'    => self::get_css_value( $attr['ctaBorderRadius'], 'px' ),
					'border-width'     => self::get_css_value( $attr['ctaBorderWidth'], 'px' ),
					'padding-top'      => self::get_css_value( $attr['ctaVpadding'], 'px' ),
					'padding-bottom'   => self::get_css_value( $attr['ctaVpadding'], 'px' ),
					'padding-left'     => self::get_css_value( $attr['ctaHpadding'], 'px' ),
					'padding-right'    => self::get_css_value( $attr['ctaHpadding'], 'px' ),
					'margin-bottom'    => self::get_css_value( $attr['buttonSpace'], 'px' ),
					'line-height'      => $attr['ctaLineHeight'],
					'font-weight'      => $attr['ctaFontWeight'],
					'font-size'        => self::get_css_value( $attr['ctaFontSize'], 'px' ),
					'font-family'      => $attr['ctaFontFamily'],
					'box-shadow'       =>
						self::get_css_value( $attr['buttonBoxShadowHOffset'], 'px' ) .
						' ' .
						self::get_css_value( $attr['buttonBoxShadowVOffset'], 'px' ) .
						' ' .
						self::get_css_value( $attr['buttonBoxShadowBlur'], 'px' ) .
						' ' .
						self::get_css_value( $attr['buttonBoxShadowSpread'], 'px' ) .
						' ' .
						$attr['buttonBoxShadowColor'] .
						' ' .
						$button_box_shadow_position_css,
					'margin-bottom'    => self::get_css_value( $attr['buttonSpace'], 'px' ),
				),
				' .wp-block-responsive-block-editor-addons-feature-grid-item__button:hover' => array(
					'color'            => $attr['ctaHoverColor'] . '!important',
					'background-color' => $updated_button_bg_h_color,
					'background-image' => 'color' === $attr['buttonHbackgroundType'] ? 'none' : $updated_button_bg_h_image,
					'border-color'     => $attr['ctaHoverBorderColor'],
				),
			);

			$mobile_selectors = array(
				' ' => array(
					'padding-top'    => self::get_css_value( $attr['blockTopPaddingMobile'], 'px' ),
					'padding-bottom' => self::get_css_value( $attr['blockBottomPaddingMobile'], 'px' ),
					'padding-left'   => self::get_css_value( $attr['blockLeftPaddingMobile'], 'px' ),
					'padding-right'  => self::get_css_value( $attr['blockRightPaddingMobile'], 'px' ),
				),
				' .wp-block-responsive-block-editor-addons-feature-grid-item' => array(
					'padding-top'    => self::get_css_value( $attr['columnTopPaddingMobile'], 'px' ),
					'padding-bottom' => self::get_css_value( $attr['columnBottomPaddingMobile'], 'px' ),
					'padding-left'   => self::get_css_value( $attr['columnLeftPaddingMobile'], 'px' ),
					'padding-right'  => self::get_css_value( $attr['columnRightPaddingMobile'], 'px' ),
				),
				' .responsive-block-editor-addons-feature-image-wrap' => array(
					'margin-bottom' => self::get_css_value( $attr['imageSpaceMobile'], 'px' ),
				),
				' .wp-block-responsive-block-editor-addons-feature-grid-item__title' => array(
					'font-size'     => self::get_css_value( $attr['titleFontSizeMobile'], 'px' ),
					'margin-bottom' => self::get_css_value( $attr['titleSpaceMobile'], 'px' ),
				),
				' .wp-block-responsive-block-editor-addons-feature-grid-item__desc' => array(
					'font-size'     => self::get_css_value( $attr['descFontSizeMobile'], 'px' ),
					'margin-bottom' => self::get_css_value( $attr['descSpaceMobile'], 'px' ),
				),
				' .responsive-block-editor-addons-feature-image' => array(
					'width' => self::get_css_value( $attr['imageWidthMobile'], 'px' ),

				),
				' .wp-block-responsive-block-editor-addons-feature-grid-item__button' => array(
					'padding-top'    => self::get_css_value( $attr['ctaVpaddingMobile'], 'px' ),
					'padding-bottom' => self::get_css_value( $attr['ctaVpaddingMobile'], 'px' ),
					'padding-left'   => self::get_css_value( $attr['ctaHpaddingMobile'], 'px' ),
					'padding-right'  => self::get_css_value( $attr['ctaHpaddingMobile'], 'px' ),
					'margin-bottom'  => self::get_css_value( $attr['buttonSpaceMobile'], 'px' ),
					'font-size'      => self::get_css_value( $attr['ctaFontSizeMobile'], 'px' ),
				),

			);

			$tablet_selectors = array(
				' ' => array(
					'padding-top'    => self::get_css_value( $attr['blockTopPaddingTablet'], 'px' ),
					'padding-bottom' => self::get_css_value( $attr['blockBottomPaddingTablet'], 'px' ),
					'padding-left'   => self::get_css_value( $attr['blockLeftPaddingTablet'], 'px' ),
					'padding-right'  => self::get_css_value( $attr['blockRightPaddingTablet'], 'px' ),
				),
				' .wp-block-responsive-block-editor-addons-feature-grid-item' => array(
					'padding-top'    => self::get_css_value( $attr['columnTopPaddingTablet'], 'px' ),
					'padding-bottom' => self::get_css_value( $attr['columnBottomPaddingTablet'], 'px' ),
					'padding-left'   => self::get_css_value( $attr['columnLeftPaddingTablet'], 'px' ),
					'padding-right'  => self::get_css_value( $attr['columnRightPaddingTablet'], 'px' ),
				),
				' .responsive-block-editor-addons-feature-image-wrap' => array(
					'margin-bottom' => self::get_css_value( $attr['imageSpaceTablet'], 'px' ),

				),
				' .wp-block-responsive-block-editor-addons-feature-grid-item__title' => array(
					'font-size'     => self::get_css_value( $attr['titleFontSizeTablet'], 'px' ),
					'margin-bottom' => self::get_css_value( $attr['titleSpaceTablet'], 'px' ),
				),
				' .wp-block-responsive-block-editor-addons-feature-grid-item__desc' => array(
					'font-size'     => self::get_css_value( $attr['descFontSizeTablet'], 'px' ),
					'margin-bottom' => self::get_css_value( $attr['descSpaceTablet'], 'px' ),
				),
				' .responsive-block-editor-addons-feature-image' => array(
					'width' => self::get_css_value( $attr['imageWidthTablet'], 'px' ),

				),
				' .wp-block-responsive-block-editor-addons-feature-grid-item__button' => array(
					'padding-top'    => self::get_css_value( $attr['ctaVpaddingTablet'], 'px' ),
					'padding-bottom' => self::get_css_value( $attr['ctaVpaddingTablet'], 'px' ),
					'padding-left'   => self::get_css_value( $attr['ctaHpaddingTablet'], 'px' ),
					'padding-right'  => self::get_css_value( $attr['ctaHpaddingTablet'], 'px' ),
					'margin-bottom'  => self::get_css_value( $attr['buttonSpaceTablet'], 'px' ),
					'font-size'      => self::get_css_value( $attr['ctaFontSizeTablet'], 'px' ),
				),

			);

			$combined_selectors = array(
				'desktop' => $selectors,
				'tablet'  => $tablet_selectors,
				'mobile'  => $mobile_selectors,
			);

			$id  = '.wp-block-responsive-block-editor-addons-feature-grid.block-' . $id;
			$css = Responsive_Block_Editor_Addons_Frontend_Styles_Helper::responsive_block_editor_addons_generate_all_css( $combined_selectors, $id );

			return $css;
		}


		/**
		 * Get Defaults for Feature Grid block
		 *
		 * @return array
		 */
		public static function get_responsive_block_feature_grid_default_attributes() {
			return array(
				'block_id'                  => '',
				'blockAlign'                => 'center',
				'imageWidth'                => '',
				'imageWidthTablet'          => '',
				'imageWidthMobile'          => '',
				'opacity'                   => 30,
				'buttonSpace'               => 15,
				'ctaHpadding'               => '',
				'ctaHpaddingTablet'         => '',
				'ctaHpaddingMobile'         => '',
				'ctaVpadding'               => '',
				'ctaVpaddingTablet'         => '',
				'ctaVpaddingMobile'         => '',
				'titleFontFamily'           => '',
				'descFontFamily'            => '',
				'titleFontSize'             => '',
				'titleFontSizeTablet'       => '',
				'titleFontSizeMobile'       => '',
				'titleFontWeight'           => '',
				'titleLineHeight'           => '',
				'descFontSize'              => '',
				'descFontSizeTablet'        => '',
				'descFontSizeMobile'        => '',
				'descFontWeight'            => '',
				'descLineHeight'            => '',
				'titleTextTransform'        => '',
				'descTextTransform'         => '',
				'ctaFontFamily'             => '',
				'ctaFontSize'               => '',
				'ctaFontSizeTablet'         => '',
				'ctaFontSizeMobile'         => '',
				'ctaFontWeight'             => '',
				'ctaLineHeight'             => '',
				'ctaBorderColor'            => '',
				'ctaBorderStyle'            => 'none',
				'ctaBorderRadius'           => 0,
				'ctaBorderWidth'            => 0,
				'ctaDesign'                 => 'plain',
				'ctaColor'                  => '',
				'ctaBackColor'              => '',
				'ctaHoverColor'             => '',
				'ctaHoverBackColor'         => '',
				'buttonbackgroundType'      => 'color',
				'buttoncolorLocation1'      => 0,
				'buttoncolorLocation2'      => 100,
				'buttongradientDirection'   => 90,
				'buttonbackgroundColor1'    => '',
				'buttonbackgroundColor2'    => '#fff',
				'buttonHbackgroundType'     => 'color',
				'buttonHcolorLocation1'     => 0,
				'buttonHcolorLocation2'     => 100,
				'buttonHgradientDirection'  => 90,
				'buttonHbackgroundColor1'   => '',
				'buttonHbackgroundColor2'   => '#fff',
				'buttonBoxShadowColor'      => '',
				'buttonBoxShadowHOffset'    => 0,
				'buttonBoxShadowVOffset'    => 0,
				'buttonBoxShadowBlur'       => 0,
				'buttonBoxShadowSpread'     => 0,
				'buttonBoxShadowPosition'   => 'outset',
				'boxShadowColor'            => '#e9e7e7',
				'boxShadowHOffset'          => 0,
				'boxShadowVOffset'          => 0,
				'boxShadowBlur'             => 2,
				'boxShadowSpread'           => 1,
				'boxShadowPosition'         => 'outset',
				'columnBackColorOpacity'    => 100,
				'blockBorderColor'          => '',
				'blockBorderStyle'          => 'none',
				'blockBorderRadius'         => 12,
				'blockBorderWidth'          => 1,
				'blockTopPadding'           => 0,
				'blockBottomPadding'        => 0,
				'blockLeftPadding'          => 0,
				'blockRightPadding'         => 0,
				'blockTopPaddingMobile'     => '',
				'blockBottomPaddingMobile'  => '',
				'blockLeftPaddingMobile'    => '',
				'blockRightPaddingMobile'   => '',
				'blockTopPaddingTablet'     => '',
				'blockBottomPaddingTablet'  => '',
				'blockLeftPaddingTablet'    => '',
				'blockRightPaddingTablet'   => '',
				'columnTopPadding'          => 60,
				'columnBottomPadding'       => 60,
				'columnLeftPadding'         => 35,
				'columnRightPadding'        => 35,
				'columnTopPaddingMobile'    => '',
				'columnBottomPaddingMobile' => '',
				'columnLeftPaddingMobile'   => '',
				'columnRightPaddingMobile'  => '',
				'columnTopPaddingTablet'    => '',
				'columnBottomPaddingTablet' => '',
				'columnLeftPaddingTablet'   => '',
				'columnRightPaddingTablet'  => '',
				'backgroundColor'           => '',
				'backgroundColor1'          => '',
				'backgroundColor2'          => '#fff',
				'backgroundType'            => 'none',
				'colorLocation1'            => 0,
				'colorLocation2'            => 100,
				'gradientDirection'         => 90,
				'backgroundImage'           => '',
				'imageSpace'                => 15,
				'titleSpace'                => 15,
				'descSpace'                 => 20,
				'buttonSpace'               => 15,
				'imageSpaceMobile'          => '',
				'titleSpaceMobile'          => '',
				'descSpaceMobile'           => '',
				'buttonSpaceMobile'         => '',
				'imageSpaceTablet'          => '',
				'titleSpaceTablet'          => '',
				'descSpaceTablet'           => '',
				'buttonSpaceTablet'         => '',
				'titleColor'                => '',
				'descColor'                 => '',
				'ctaHoverBorderColor'       => '',
			);
		}

		/**
		 * Get Portfolio CSS
		 *
		 * @param array  $attr The block attributes.
		 * @param string $id The selector ID.
		 * @return array Styles.
		 */
		public static function get_responsive_block_portfolio_css( $attr, $id ) {
			$defaults = self::get_responsive_block_portfolio_block_default_attributes();
			$attr     = array_merge( $defaults, (array) $attr );

			$mobile_selectors = array();
			$tablet_selectors = array();

			$boxed_content_padding = 0;
			$content_padding       = 0;
			if ( 'content' === $attr['layout'] ) {
				$content_padding       = $attr['contentPadding'];
				$boxed_content_padding = 0;
			}
			if ( 'boxed' === $attr['layout'] ) {
				$boxed_content_padding = $attr['contentPadding'];
			}

			$column_gap = '';
			if ( $attr['columnGap'] ) {
				$column_gap = $attr['columnGap'];
			}
			$row_gap = '';
			if ( $attr['rowGap'] ) {
				$row_gap = $attr['rowGap'];
			}
			$opacity = $attr['overlayOpacity'] / 100;

			$selectors = array(
				' .responsive-block-editor-addons-portfolio-items' => array(
					'grid-column-gap' => self::get_css_value( $column_gap, 'px' ),
					'grid-row-gap'    => self::get_css_value( $row_gap, 'px' ),
				),
				' .responsive-block-editor-addons-portfolio-items a' => array(
					'padding-bottom' => 'calc( ' . $attr['itemRatio'] . ' * 100% )',
				),
				' .responsive-block-editor-addons-block-portfolio-image' => array(
					'border-style'  => $attr['blockBorderStyle'],
					'border-color'  => $attr['blockBorderColor'],
					'border-radius' => self::get_css_value( $attr['blockBorderRadius'], 'px' ) . ' !important',
					'border-width'  => self::get_css_value( $attr['blockBorderWidth'], 'px' ),
				),
				' .responsive-block-editor-addons-block-portfolio-image-overlay' => array(
					'background-color' => $attr['overlayBackgroundColor'],
					'text-align'       => $attr['overlayTextAlign'],
					'justify-content'  => $attr['overlayTextVerticalAlign'],
					'border-style'     => $attr['blockBorderStyle'],
					'border-color'     => $attr['blockBorderColor'],
					'border-radius'    => self::get_css_value( $attr['blockBorderRadius'], 'px' ) . ' !important',
					'border-width'     => self::get_css_value( $attr['blockBorderWidth'], 'px' ),
				),
				' .responsive-block-editor-addons-block-portfolio-image-overlay *' => array(
					'color'          => $attr['overlayTextColor'],
					'font-family'    => $attr['overlayTextFontFamily'],
					'font-size'      => self::get_css_value( $attr['overlayTextFontSize'], 'px' ),
					'font-weight'    => $attr['overlayTextFontWeight'],
					'line-height'    => $attr['overlayTextLineHeight'],
					'text-transform' => $attr['overlayTextTextTransform'],
					'margin-left'    => self::get_css_value( $attr['horizontalSpacing'], 'px' ),
					'margin-right'   => self::get_css_value( $attr['horizontalSpacing'], 'px' ),
					'margin-top'     => self::get_css_value( $attr['verticalSpacing'], 'px' ),
					'margin-bottom'  => self::get_css_value( $attr['verticalSpacing'], 'px' ),
				),
				' .responsive-block-editor-addons-block-portfolio-image-overlay:hover' => array(
					'opacity' => $opacity,
				),
				' .responsive-block-editor-addons-portfolio-items article' => array(
					'padding'       => self::get_css_value( $boxed_content_padding, 'px' ),
					'margin'        => 0,
					'border-radius' => self::get_css_value( $attr['blockBorderRadius'], 'px' ) . ' !important',
				),
				' .is-list .responsive-block-editor-addons-portfolio-item:not(:last-child)' => array(
					'margin-bottom' => self::get_css_value( $row_gap, 'px' ),
				),
				' .is-list article:last-child' => array(
					'margin-bottom' => 0,
				),

			);

			$mobile_selectors = array();

			$tablet_selectors = array();

			$combined_selectors = array(
				'desktop' => $selectors,
				'tablet'  => $tablet_selectors,
				'mobile'  => $mobile_selectors,
			);

			$id              = '.responsive-block-editor-addons-block-portfolio.block-id-' . $id;
			$css             = Responsive_Block_Editor_Addons_Frontend_Styles_Helper::responsive_block_editor_addons_generate_all_css( $combined_selectors, $id );
			$css['desktop'] .= '.page-template-gutenberg-fullwidth ' . $id . ' .responsive-block-editor-addons-portfolio-items article {padding:' . ( 'boxed' === $attr['layout'] ? $attr['contentPadding'] ? $attr['contentPadding'] : '0' : '0' ) . 'px;}';
			return $css;
		}

		/**
		 * Get Defaults for portfolio block
		 *
		 * @return array
		 */
		public static function get_responsive_block_portfolio_block_default_attributes() {
			return array(
				'block_id'                 => '',
				'postsToShow'              => 6,
				'offset'                   => 0,
				'postLayout'               => 'grid',
				'categories'               => '',
				'itemRatio'                => 0.66,
				'overlayOpacity'           => 100,
				'horizontalSpacing'        => 10,
				'verticalSpacing'          => 15,
				'overlayTextAlign'         => 'center',
				'overlayTextVerticalAlign' => 'center',
				'overlayTextFontSize'      => '',
				'overlayTextLineHeight'    => '',
				'overlayTextFontWeight'    => '',
				'overlayTextTextTransform' => '',
				'overlayTextFontFamily'    => '',
				'overlayBackgroundColor'   => '#ff6f61',
				'overlayTextColor'         => '#ffffff',
				'stackonMobile'            => true,
				'displayPostTitle'         => true,
				'displaySectionTitle'      => false,
				'postTitleTag'             => 'h3',
				'postLayout'               => 'grid',
				'columns'                  => 3,
				'align'                    => 'center',
				'width'                    => 'wide',
				'orderBy'                  => 'date',
				'order'                    => 'desc',
				'postType'                 => 'post',
				'postTaxonomy'             => 'category',
				'taxonomyType'             => 'category',
				'sectionTag'               => 'section',
				'sectionTitle'             => '',
				'sectionTitleTag'          => 'h2',
				'imageSize'                => 'full',
				'id'                       => '',
				'bgColor'                  => '#e4e4e4',
				'layout'                   => 'boxed',
				'contentPadding'           => 0,
				'contentPaddingMobile'     => '',
				'mobileContentPadding'     => 999,
				'contentPaddingTablet'     => '',
				'columnGap'                => 0,
				'rowGap'                   => 0,
				'blockBorderWidth'         => '0',
				'blockBorderRadius'        => '0',
				'blockBorderStyle'         => 'none',
				'blockBorderColor'         => '#333',
				'taxonomyType'             => 'category',
			);
		}

		/**
		 * Get Contact Form 7 Styler CSS
		 *
		 * @param array  $attr The block attributes.
		 * @param string $id The selector ID.
		 * @return array Styles.
		 */
		public static function get_responsive_block_contact_form_7_styler_css( $attr, $id ) {
			$defaults = self::get_responsive_block_contact_form_7_styler_block_default_attributes();
			$attr     = array_merge( $defaults, (array) $attr );

			$mobile_selectors = array();
			$tablet_selectors = array();


			$ctaButtonmarginleft   = '';
			$ctaButtonmarginright = '';
			
			if ( 'right' === $attr['ctaButtonAlignment'] ) {
				$ctaButtonmarginright=self::get_css_value( 0, 'px' );
			}
			if ( 'left' === $attr['ctaButtonAlignment'] ) {
				$ctaButtonmarginleft=self::get_css_value( 0, 'px' );
			}
			if ( 'center' === $attr['ctaButtonAlignment'] ) {
				
			}
			

			$imgopacity          = $attr['opacity'] / 100;


			$background_image_gradient = '';
			$btn_color                 = $attr['ctaBackColor'];
			if ( 'gradient' === $attr['buttonbackgroundType'] ) {
				$background_image_gradient = 'linear-gradient(' . $attr['buttongradientDirection'] . 'deg, ' . $attr['buttonbackgroundColor1'] . ' ' . $attr['buttoncolorLocation1'] . '%, ' . $attr['buttonbackgroundColor2'] . ' ' . $attr['buttoncolorLocation2'] . '%)';
			} elseif ( 'color' === $attr['buttonbackgroundType'] ) {
				$btn_color   = $attr['ctaBackColor'];
			};

			$background_hover_image_gradient = '';
			$btn_h_color                     = $attr['ctaHoverBackColor'];
			if ( 'gradient' === $attr['buttonHbackgroundType'] ) {
				$background_hover_image_gradient = 'linear-gradient(' . $attr['buttonHgradientDirection'] . 'deg, ' . $attr['buttonHbackgroundColor1'] . ' ' . $attr['buttonHcolorLocation1'] . '%, ' . $attr['buttonHbackgroundColor2'] . ' ' . $attr['buttonHcolorLocation2'] . '%)';
			} elseif ( 'color' === $attr['buttonHbackgroundType'] ) {
				$btn_h_color   = $attr['ctaHoverBackColor'];
			}
			
			$box_shadow_position_css = $attr['boxShadowPosition'];

			if ( 'outset' === $attr['boxShadowPosition'] ) {
				$box_shadow_position_css = '';
			}

			$hoverbox_shadow_position_css = $attr['hoverboxShadowPosition'];

			if ( 'outset' === $attr['hoverboxShadowPosition'] ) {
				$hoverbox_shadow_position_css = '';
			}

			$inputBox_shadow_position_css = $attr['inputBoxShadowPosition'];

			if ( 'outset' === $attr['inputBoxShadowPosition'] ) {
				$inputBox_shadow_position_css = '';
			}

			$submit_button_box_shadow_position_css = $attr['submitButtonBoxShadowPosition'];

			if ( 'outset' === $attr['submitButtonBoxShadowPosition'] ) {
				$submit_button_box_shadow_position_css = '';
			}

			
			

			$selectors = array(
				' .responsive-form-title-text'             => array(
					'color'          => $attr['formTitleColor'],
					'font-family'    => $attr['formTitleFontFamily'],
					'font-size'      => self::get_css_value( $attr['formTitleFontSize'], 'px' ),
					'font-weight'    => $attr['formTitleFontWeight'],
					'line-height'    => $attr['formTitleLineHeight'],
					'letter-spacing' => self::get_css_value( $attr['formTitleLetterSpacing'], 'px' ),
					'text-align'     => $attr['formTitleAlignment'],

				),
				' .form-container'                         => array(
					'justify-content' => $attr['formAlignment'],

				),
				' .responsive-form-desc-text'              => array(
					'color'          => $attr['formDescriptionColor'],
					'font-family'    => $attr['formDescriptionFontFamily'],
					'font-size'      => self::get_css_value( $attr['formDescriptionFontSize'], 'px' ),
					'font-weight'    => $attr['formDescriptionFontWeight'],
					'line-height'    => $attr['formDescriptionLineHeight'],
					'letter-spacing' => self::get_css_value( $attr['formDescriptionLetterSpacing'], 'px' ),
					'text-align'     => $attr['formTitleAlignment'],

				),
				' .form'                                   => array(
					'width'            => self::get_css_value( $attr['formWidth'], '%' ),
					'padding-top'      => self::get_css_value( $attr['topPadding'], 'px' ),
					'padding-bottom'   => self::get_css_value( $attr['bottomPadding'], 'px' ),
					'padding-left'     => self::get_css_value( $attr['leftPadding'], 'px' ),
					'padding-right'    => self::get_css_value( $attr['rightPadding'], 'px' ),
					'margin-top'       => self::get_css_value( $attr['topMargin'], 'px' ),
					'margin-bottom'    => self::get_css_value( $attr['bottomMargin'], 'px' ),
					'margin-left'      => self::get_css_value( $attr['leftMargin'], 'px' ),
					'margin-right'     => self::get_css_value( $attr['rightMargin'], 'px' ),
					'border-width'     => self::get_css_value( $attr['formBorderWidth'], 'px' ),
					'border-color'     => $attr['formBorderColor'],
					'border-style'     => $attr['formBorderStyle'],
					'border-radius'    => self::get_css_value( $attr['formBorderRadius'], 'px' ),
					'background-color' => 'color' === $attr['backgroundType'] ? self::hex_to_rgb( $attr['backgroundColor'], $imgopacity ) : '',
					'background-image' => 'gradient' === $attr['backgroundType'] ? self::generate_background_image_effect(
						self::hex_to_rgb( $attr['backgroundColor1'], $imgopacity ),
						self::hex_to_rgb( $attr['backgroundColor2'], $imgopacity ),
						$attr['gradientDirection'],
						$attr['colorLocation1'],
						$attr['colorLocation2']
					) : '',
					'box-shadow'       => self::get_css_value( $attr['boxShadowHOffset'], 'px' ) . ' ' . self::get_css_value( $attr['boxShadowVOffset'], 'px' ) . ' ' . self::get_css_value( $attr['boxShadowBlur'], 'px' ) . ' ' . self::get_css_value( $attr['boxShadowSpread'], 'px' ) . ' ' . $attr['boxShadowColor'] . ' ' . $box_shadow_position_css,

				),

				' .form:hover'                             => array(

					'box-shadow'       => '' !== $attr['hoverboxShadowColor'] ? self::get_css_value( $attr['hoverboxShadowHOffset'], 'px' ) . ' ' . self::get_css_value( $attr['hoverboxShadowVOffset'], 'px' ) . ' ' . self::get_css_value( $attr['hoverboxShadowBlur'], 'px' ) . ' ' . self::get_css_value( $attr['hoverboxShadowSpread'], 'px' ) . ' ' . $attr['hoverboxShadowColor'] . ' ' . $hoverbox_shadow_position_css : '',
					'background-image' => 'gradient' === $attr['backgroundType'] ? self::generate_background_image_effect(
						self::hex_to_rgb( $attr['hoverbackgroundColor1'], $imgopacity ),
						self::hex_to_rgb( $attr['hoverbackgroundColor2'], $imgopacity ),
						$attr['hovergradientDirection'],
						$attr['hovercolorLocation1'],
						$attr['hovercolorLocation2']
					) : '',
					'background-color' => 'color' === $attr['backgroundType'] && '' !== $attr['backgroundColorHover'] ? self::hex_to_rgb( $attr['backgroundColorHover'] ? $attr['backgroundColorHover'] : '#fff', $imgopacity ) : '',
				),

				' p>label'                       => array(					
					'display'          => $attr['showLabels'] ? 'block' : 'none',
				),
				' .wpcf7-not-valid-tip'                       => array(
					'display'          => $attr['showErrorMsgs'] ? 'block' : 'none',
					'font-family'    => $attr['messageFontFamily'],
					'font-size'      => self::get_css_value( $attr['messageFontSize'], 'px' ),
					'font-weight'    => $attr['messageFontWeight'],
					'line-height'    => $attr['messageLineHeight'],
					'letter-spacing' => self::get_css_value( $attr['messageLetterSpacing'], 'px' ),
					'color' => $attr['errorMsgColor'],
				),

				' .wpcf7 input[type=text] ,.wpcf7 input[type=email], .wpcf7 .wpcf7-select'         => array(
					'color'            => $attr['inputTextColor'],
					'background-color' => $attr['inputBackgroundColor'],
					'border-width'     => self::get_css_value( $attr['inputBorderWidth'], 'px' ),
					'border-color'     => $attr['inputBorderColor'],
					'border-style'     => $attr['inputBorderStyle'],
					'border-radius'    => self::get_css_value( $attr['inputBorderRadius'], 'px' ),
					'padding-top'      => self::get_css_value( $attr['inputTopPadding'], 'px' ),
					'padding-bottom'   => self::get_css_value( $attr['inputBottomPadding'], 'px' ),
					'padding-left'     => self::get_css_value( $attr['inputLeftPadding'], 'px' ),
					'padding-right'    => self::get_css_value( $attr['inputRightPadding'], 'px' ),
					'text-indent'      => self::get_css_value( $attr['textIndent'], 'px' ),
					'font-family'      => $attr['inputFontFamily'],
					'font-size'        => self::get_css_value( $attr['inputFontSize'], 'px' ),
					'font-weight'      => $attr['inputFontWeight'],
					'line-height'      => $attr['inputLineHeight'],
					'letter-spacing'   => self::get_css_value( $attr['inputLetterSpacing'], 'px' ),
					'margin-top'       => self::get_css_value( $attr['labelSpacing'], 'px' ),
					'box-shadow'       => self::get_css_value( $attr['inputBoxShadowHOffset'], 'px' ) . ' ' . self::get_css_value( $attr['inputBoxShadowVOffset'], 'px' ) . ' ' . self::get_css_value( $attr['inputBoxShadowBlur'], 'px' ) . ' ' . self::get_css_value( $attr['inputBoxShadowSpread'], 'px' ) . ' ' . $attr['inputBoxShadowColor'] . ' ' . $inputBox_shadow_position_css,
				),

				' .wpcf7 wpcf7-text'                       => array(
					'width' => self::get_css_value( $attr['textareaWidth'], '%' ),
				),

				' .wpcf7 textarea'                         => array(
					'color'            => $attr['inputTextColor'],
					'background-color' => $attr['inputBackgroundColor'],
					'border-width'     => self::get_css_value( $attr['inputBorderWidth'], 'px' ),
					'border-color'     => $attr['inputBorderColor'],
					'border-style'     => $attr['inputBorderStyle'],
					'border-radius'    => self::get_css_value( $attr['inputBorderRadius'], 'px' ),
					'padding-top'      => self::get_css_value( $attr['inputTopPadding'], 'px' ),
					'padding-bottom'   => self::get_css_value( $attr['inputBottomPadding'], 'px' ),
					'padding-left'     => self::get_css_value( $attr['inputLeftPadding'], 'px' ),
					'padding-right'    => self::get_css_value( $attr['inputRightPadding'], 'px' ),
					'width'            => self::get_css_value( $attr['textareaWidth'], '%' ),
					'height'           => self::get_css_value( $attr['textareaHeight'], 'px' ),
					'text-indent'      => self::get_css_value( $attr['textIndent'], 'px' ),
					'font-family'      => $attr['inputFontFamily'],
					'font-size'        => self::get_css_value( $attr['inputFontSize'], 'px' ),
					'font-weight'      => $attr['inputFontWeight'],
					'line-height'      => $attr['inputLineHeight'],
					'letter-spacing'   => self::get_css_value( $attr['inputLetterSpacing'], 'px' ),
					'margin-top'       => self::get_css_value( $attr['labelSpacing'], 'px' ),
					'box-shadow'       => self::get_css_value( $attr['inputBoxShadowHOffset'], 'px' ) . ' ' . self::get_css_value( $attr['inputBoxShadowVOffset'], 'px' ) . ' ' . self::get_css_value( $attr['inputBoxShadowBlur'], 'px' ) . ' ' . self::get_css_value( $attr['inputBoxShadowSpread'], 'px' ) . ' ' . $attr['inputBoxShadowColor'] . ' ' . $inputBox_shadow_position_css,

				),

				' .wpcf7-select, .wpcf7-checkbox, .wpcf7-radio, .wpcf7-textarea, .wpcf7-text, .wpcf7-email'                     => array(
					'width'  => self::get_css_value( $attr['inputWidth'], '%' ),
					'height' => self::get_css_value( $attr['inputHeight'], 'px !important' ),
				),

				' .wpcf7-select'                           => array(
					'color'            => $attr['inputTextColor'],
					'background-color' => $attr['inputBackgroundColor'],
				),

				' .wpcf7 form.wpcf7-form:not(input)'       => array(
					'color'          => $attr['labelColor'],
					'font-family'    => $attr['labelFontFamily'],
					'font-size'      => self::get_css_value( $attr['labelFontSize'], 'px' ),
					'font-weight'    => $attr['labelFontWeight'],
					'line-height'    => $attr['labelLineHeight'],
					'letter-spacing' => self::get_css_value( $attr['labelLetterSpacing'], 'px' ),
					'margin-top'     => self::get_css_value( $attr['labelSpacing'], 'px' ),
				),
				' ::-webkit-input-placeholder'             => array(
					'color' => $attr['placeholderColor'],
				),
				' :-moz-placeholder'                       => array(
					'color' => $attr['placeholderColor'],
				),
				' ::-moz-placeholder'                      => array(
					'color' => $attr['placeholderColor'],
				),
				' :-ms-input-placeholder'                  => array(
					'color' => $attr['placeholderColor'],
				),
				' input[type="checkbox"]'                  => array(
					'color' => $attr['hoverRadioCheckboxColor'],
				),
				" .wpcf7 .wpcf7-checkbox input[type='checkbox']" => array(
					'color'            => $attr['hoverRadioCheckboxColor'],
					'background-color' => $attr['radioCheckboxColor'],
					'font-size'        => 'calc( ' . $attr['radioCheckboxSize'] . 'px / 1.2 )',
					'border'           => 'solid',
					'border-width'     => self::get_css_value( $attr['radioCheckboxBorderWidth'], 'px' ),
					'border-color'     => $attr['radioCheckboxBorderColor'],
					'border-radius'    => self::get_css_value( $attr['checkboxBorderRadius'], 'px' ),
					'width'            => self::get_css_value( $attr['radioCheckboxSize'] + $attr['radioCheckboxBorderWidth'] + $attr['radioCheckboxBorderWidth'], 'px' ),
					'height'           => self::get_css_value( $attr['radioCheckboxSize'] + $attr['radioCheckboxBorderWidth'] + $attr['radioCheckboxBorderWidth'], 'px' ),
				),

				" .wpcf7 .wpcf7-checkbox input[type='checkbox']:checked + span:before" => array(
					'color'            => $attr['hoverRadioCheckboxColor'],
					'background-color' => $attr['radioCheckboxColor'],
					'font-size'        => 'calc( ' . $attr['radioCheckboxSize'] . 'px / 1.2 )',
					'border-color'     => $attr['hoverRadioCheckboxColor'],
				),
				" .wpcf7 .wpcf7-acceptance input[type='checkbox']:checked + span:before" => array(
					'color'            => $attr['hoverRadioCheckboxColor'],
					'border-color'     => $attr['hoverRadioCheckboxColor'],
					'background-color' => $attr['radioCheckboxColor'],
				),
				" .wpcf7 .wpcf7-acceptance input[type='checkbox'] + span:before" => array(
					'color'            => $attr['hoverRadioCheckboxColor'],
					'background-color' => $attr['radioCheckboxColor'],
					'font-size'        => 'calc( ' . $attr['radioCheckboxSize'] . 'px / 1.2 )',
					'width'            => self::get_css_value( $attr['radioCheckboxSize'], 'px' ),
					'height'           => self::get_css_value( $attr['radioCheckboxSize'], 'px' ),
				),
				" .wpcf7 .wpcf7-radio input[type='radio']" => array(
					'color'            => $attr['hoverRadioCheckboxColor'],
					'background-color' => $attr['radioCheckboxColor'],
					'font-size'        => 'calc( ' . $attr['radioCheckboxSize'] . 'px / 1.2 )',
					'border'           => 'solid',
					'border-width'     => self::get_css_value( $attr['radioCheckboxBorderWidth'], 'px' ),
					'border-color'     => $attr['radioCheckboxBorderColor'],
					'border-radius'    => self::get_css_value( $attr['radioButtonBorderRadius'], 'px' ),
					'width'            => self::get_css_value( $attr['radioCheckboxSize'] + $attr['radioCheckboxBorderWidth'] + $attr['radioCheckboxBorderWidth'], 'px' ),
					'height'           => self::get_css_value( $attr['radioCheckboxSize'] + $attr['radioCheckboxBorderWidth'] + $attr['radioCheckboxBorderWidth'], 'px' ),
				),
				
				' .wpcf7 .wpcf7-radio input[type=radio]:checked::before' => array(
					'color'            => $attr['hoverRadioCheckboxColor'],
					'background-color' => $attr['hoverRadioCheckboxColor'],
					'font-size'        => 'calc( ' . $attr['radioCheckboxSize'] . 'px * 0.4 )',
					'border'           => 'solid',
					'width'            => 'calc( ' . $attr['radioCheckboxSize'] . 'px * 0.4 )',
					'height'           => 'calc( ' . $attr['radioCheckboxSize'] . 'px * 0.4 )',
				),
				" .wpcf7 .wpcf7-radio input[type='radio']:checked + span:before" => array(
					'color'            => $attr['hoverRadioCheckboxColor'],
					'background-color' => $attr['radioCheckboxColor'],
					'font-size'        => 'calc( ' . $attr['radioCheckboxSize'] . 'px / 1.2 )',
					'border-color'     => $attr['radioCheckboxBorderColor'],
				),
				" .wpcf7 .wpcf7-radio input[type='radio'] + span:before" => array(
					'color'            => $attr['hoverRadioCheckboxColor'],
					'background-color' => $attr['radioCheckboxColor'],
					'display'          => 'inline-flex',
					'border-radius'    => '100%',
					'width'            => 'calc( ' . $attr['radioCheckboxSize'] . 'px / 1.2 )',
					'height'           => 'calc( ' . $attr['radioCheckboxSize'] . 'px / 1.2 )',
				),

				// list.
				' .wpcf7 .wpcf7-list-item'                 => array(
					'margin-top' => self::get_css_value( $attr['labelSpacing'], 'px' ),
				),

				' .wpcf7 .wpcf7-list-item-label'           => array(
					'color'          => $attr['radioCheckboxTextColor'],
					'font-family'    => $attr['radioCheckboxFontFamily'],
					'font-size'      => self::get_css_value( $attr['radioCheckboxFontSize'], 'px' ),
					'font-weight'    => $attr['radioCheckboxFontWeight'],
					'line-height'    => $attr['radioCheckboxLineHeight'],
					'letter-spacing' => self::get_css_value( $attr['radioCheckboxLetterSpacing'], 'px' ),
				),

				' .wpcf7 select.wpcf7-form-control.wpcf7-select.wpcf7-validates-as-required' => array(
					'margin-top' => self::get_css_value( $attr['labelSpacing'], 'px' ),
				),
				// Submit button
				' .wpcf7 input.wpcf7-form-control.wpcf7-submit' => array(
					'font-family'      => $attr['submitButtonFontFamily'],
					'font-size'        => self::get_css_value( $attr['submitButtonFontSize'], 'px' ),
					'font-weight'      => $attr['submitButtonFontWeight'],
					'line-height'      => $attr['submitButtonLineHeight'],
					'letter-spacing'   => self::get_css_value( $attr['submitButtonLetterSpacing'], 'px' ),
					'background-image' => $background_image_gradient,
					'background-color' => $btn_color . '!important',
					'color'            => $attr['ctaColor'] . '!important',
					'padding-top'      => self::get_css_value( $attr['ctaVpadding'], 'px' ),
					'padding-bottom'   => self::get_css_value( $attr['ctaVpadding'], 'px' ),
					'padding-left'     => self::get_css_value( $attr['ctaHpadding'], 'px' ),
					'padding-right'    => self::get_css_value( $attr['ctaHpadding'], 'px' ),
					'margin'           =>'auto',
					'margin-left'      => $ctaButtonmarginleft,
					'margin-right'     => $ctaButtonmarginright,
					'border-width'     => self::get_css_value( $attr['ctaBorderWidth'], 'px' ),
					'border-color'     => $attr['ctaBorderColor'],
					'border-style'     => $attr['ctaBorderStyle'],
					'border-radius'    => self::get_css_value( $attr['ctaBorderRadius'], 'px' ),
					'width'            => self::get_css_value( $attr['submitButtonWidth'], '%' ),
					'height'           => self::get_css_value( $attr['submitButtonHeight'], 'px' ),
					'box-shadow'       => self::get_css_value( $attr['submitButtonBoxShadowHOffset'], 'px' ) . ' ' . self::get_css_value( $attr['submitButtonBoxShadowVOffset'], 'px' ) . ' ' . self::get_css_value( $attr['submitButtonBoxShadowBlur'], 'px' ) . ' ' . self::get_css_value( $attr['submitButtonBoxShadowSpread'], 'px' ) . ' ' . $attr['submitButtonBoxShadowColor'] . ' ' . $submit_button_box_shadow_position_css,

				),

				' .wpcf7 input.wpcf7-form-control.wpcf7-submit:hover' => array(
					'background-image' => 'color' === $attr['buttonHbackgroundType'] ? 'none' : $background_hover_image_gradient,
					'background-color' => $btn_h_color . '!important',
					'border-color'     => $attr['ctaHoverBorderColor'],
					'color'            => $attr['ctaHoverColor'] . '!important',
					'border-color'     => $attr['ctaHoverBorderColor'],
					'box-shadow'       => self::get_css_value( $attr['submitButtonBoxShadowHOffset'], 'px' ) . ' ' . self::get_css_value( $attr['submitButtonBoxShadowVOffset'], 'px' ) . ' ' . self::get_css_value( $attr['submitButtonBoxShadowBlur'], 'px' ) . ' ' . self::get_css_value( $attr['submitButtonBoxShadowSpread'], 'px' ) . ' ' . $attr['submitButtonBoxShadowColor'] . ' ' . $submit_button_box_shadow_position_css,
				),

				' .wpcf7 form .wpcf7-response-output'      => array(					
					'border-width'   => self::get_css_value( $attr['afterSubmitBorderWidth'], 'px' ),
					'border-color'   => $attr['afterSubmitBorderColor'],
					'border-style'   => $attr['afterSubmitBorderStyle'],
					'border-radius'  => self::get_css_value( $attr['afterSubmitBorderRadius'], 'px' ),
					'font-family'    => $attr['afterSubmitFontFamily'],
					'font-size'      => self::get_css_value( $attr['afterSubmitFontSize'], 'px' ),
					'font-weight'    => $attr['afterSubmitFontWeight'],
					'line-height'    => $attr['afterSubmitLineHeight'],
					'letter-spacing' => self::get_css_value( $attr['afterSubmitLetterSpacing'], 'px' ),
					'padding-top'    => self::get_css_value( $attr['afterSubmitTopPadding'], 'px' ),
					'padding-bottom' => self::get_css_value( $attr['afterSubmitBottomPadding'], 'px' ),
					'padding-left'   => self::get_css_value( $attr['afterSubmitLeftPadding'], 'px' ),
					'padding-right'  => self::get_css_value( $attr['afterSubmitRightPadding'], 'px' ),
					'margin-top'     => self::get_css_value( $attr['afterSubmitTopMargin'], 'px' ),
					'margin-bottom'  => self::get_css_value( $attr['afterSubmitBottomMargin'], 'px' ),
					'margin-left'    => self::get_css_value( $attr['afterSubmitLeftMargin'], 'px' ),
					'margin-right'   => self::get_css_value( $attr['afterSubmitRightMargin'], 'px' ),
					'background-color' => $attr['afterSubmitMsgbgColor'],					
				),
				' .wpcf7 form.sent .wpcf7-response-output'      => array(
					'color'          => $attr['successMsgColor'],					
				),

				' .wpcf7 form.invalid .wpcf7-response-output'      => array(
					'color'          => $attr['afterSubmitErrorMsgColor'],
					
				),				

			);

			$mobile_selectors = array(
				' .responsive-form-title-text'        => array(
					'font-size'  => self::get_css_value( $attr['formTitleFontSizeMobile'], 'px' ),
					'text-align' => $attr['formTitleAlignmentMobile'],
				),

				' .form-container'                    => array(
					'justify-content' => $attr['formAlignmentMobile'],

				),
				' .responsive-form-desc-text'         => array(
					'font-size'  => self::get_css_value( $attr['formDescriptionFontSizeMobile'], 'px' ),
					'text-align' => $attr['formTitleAlignmentMobile'],

				),
				' .form'                              => array(
					'width'          => self::get_css_value( $attr['formWidthMobile'], '%' ),
					'padding-top'    => self::get_css_value( $attr['topPaddingMobile'], 'px' ),
					'padding-bottom' => self::get_css_value( $attr['bottomPaddingMobile'], 'px' ),
					'padding-left'   => self::get_css_value( $attr['leftPaddingMobile'], 'px' ),
					'padding-right'  => self::get_css_value( $attr['rightPaddingMobile'], 'px' ),
					'margin-top'     => self::get_css_value( $attr['topMarginMobile'], 'px' ),
					'margin-bottom'  => self::get_css_value( $attr['bottomMarginMobile'], 'px' ),
					'margin-left'    => self::get_css_value( $attr['leftMarginMobile'], 'px' ),
					'margin-right'   => self::get_css_value( $attr['rightMarginMobile'], 'px' ),

				),

				' .wpcf7 input:not([type=submit])'    => array(
					'padding-top'    => self::get_css_value( $attr['inputTopPaddingMobile'], 'px' ),
					'padding-bottom' => self::get_css_value( $attr['inputBottomPaddingMobile'], 'px' ),
					'padding-left'   => self::get_css_value( $attr['inputLeftPaddingMobile'], 'px' ),
					'padding-right'  => self::get_css_value( $attr['inputRightPaddingMobile'], 'px' ),
					'text-indent'    => self::get_css_value( $attr['textIndentMobile'], 'px' ),
					'font-size'      => self::get_css_value( $attr['inputFontSizeMobile'], 'px' ),
					'margin-top'     => self::get_css_value( $attr['labelSpacingMobile'], 'px' ),
				),

				' .wpcf7 wpcf7-text'                  => array(
					'width' => self::get_css_value( $attr['textareaWidthMobile'], '%' ),
				),

				' .wpcf7 textarea'                    => array(
					'padding-top'    => self::get_css_value( $attr['inputTopPaddingMobile'], 'px' ),
					'padding-bottom' => self::get_css_value( $attr['inputBottomPaddingMobile'], 'px' ),
					'padding-left'   => self::get_css_value( $attr['inputLeftPaddingMobile'], 'px' ),
					'padding-right'  => self::get_css_value( $attr['inputRightPaddingMobile'], 'px' ),
					'width'          => self::get_css_value( $attr['textareaWidthMobile'], '%' ),
					'height'         => self::get_css_value( $attr['textareaHeightMobile'], 'px' ),
					'text-indent'    => self::get_css_value( $attr['textIndentMobile'], 'px' ),
					'font-size'      => self::get_css_value( $attr['inputFontSizeMobile'], 'px' ),
					'margin-top'     => self::get_css_value( $attr['labelSpacingMobile'], 'px' ),
				),

				' .wpcf7-form-control'                => array(
					'width'  => self::get_css_value( $attr['inputWidthMobile'], '%' ),
					'height' => self::get_css_value( $attr['inputHeightMobile'], 'px !important' ),
				),

				' .wpcf7 form.wpcf7-form:not(input)'  => array(
					'font-size'  => self::get_css_value( $attr['labelFontSizeMobile'], 'px' ),
					'margin-top' => self::get_css_value( $attr['labelSpacingMobile'], 'px' ),
				),
				// list.
				' .wpcf7 .wpcf7-list-item'            => array(
					'margin-top' => self::get_css_value( $attr['labelSpacingMobile'], 'px' ),
				),

				' .wpcf7 .wpcf7-list-item-label'      => array(
					'font-size' => self::get_css_value( $attr['radioCheckboxFontSizeMobile'], 'px' ),
				),

				' .wpcf7 select.wpcf7-form-control.wpcf7-select.wpcf7-validates-as-required' => array(
					'margin-top' => self::get_css_value( $attr['labelSpacingMobile'], 'px' ),
				),
				// Submit button
				' .wpcf7 input.wpcf7-form-control.wpcf7-submit' => array(
					'font-size'      => self::get_css_value( $attr['submitButtonFontSizeMobile'], 'px' ),
					'padding-top'    => self::get_css_value( $attr['ctaVpaddingMobile'], 'px' ),
					'padding-bottom' => self::get_css_value( $attr['ctaVpaddingMobile'], 'px' ),
					'padding-left'   => self::get_css_value( $attr['ctaHpaddingMobile'], 'px' ),
					'padding-right'  => self::get_css_value( $attr['ctaHpaddingMobile'], 'px' ),
					'width'          => self::get_css_value( $attr['submitButtonWidthMobile'], '%' ),
					'height'         => self::get_css_value( $attr['submitButtonHeightMobile'], 'px' ),
				),

				' .wpcf7-not-valid-tip'               => array(
					'font-size' => self::get_css_value( $attr['messageFontSizeMobile'], 'px' ),

				),
				' .wpcf7 form .wpcf7-response-output' => array(
					'font-size'      => self::get_css_value( $attr['afterSubmitFontSizeMobile'], 'px' ),
					'padding-top'    => self::get_css_value( $attr['afterSubmitTopPaddingMobile'], 'px' ),
					'padding-bottom' => self::get_css_value( $attr['afterSubmitBottomPaddingMobile'], 'px' ),
					'padding-left'   => self::get_css_value( $attr['afterSubmitLeftPaddingMobile'], 'px' ),
					'padding-right'  => self::get_css_value( $attr['afterSubmitRightPaddingMobile'], 'px' ),
					'margin-top'     => self::get_css_value( $attr['afterSubmitTopMarginMobile'], 'px' ),
					'margin-bottom'  => self::get_css_value( $attr['afterSubmitBottomMarginMobile'], 'px' ),
					'margin-left'    => self::get_css_value( $attr['afterSubmitLeftMarginMobile'], 'px' ),
					'margin-right'   => self::get_css_value( $attr['afterSubmitRightMarginMobile'], 'px' ),

				),

			);

			$tablet_selectors = array(
				' .responsive-form-title-text'        => array(
					'font-size'  => self::get_css_value( $attr['formTitleFontSizeTablet'], 'px' ),
					'text-align' => $attr['formTitleAlignmentTablet'],
				),

				' .form-container'                    => array(
					'justify-content' => $attr['formAlignmentTablet'],

				),
				' .responsive-form-desc-text'         => array(
					'font-size'  => self::get_css_value( $attr['formDescriptionFontSizeTablet'], 'px' ),
					'text-align' => $attr['formTitleAlignmentTablet'],

				),
				' .form'                              => array(
					'width'          => self::get_css_value( $attr['formWidthTablet'], '%' ),
					'padding-top'    => self::get_css_value( $attr['topPaddingTablet'], 'px' ),
					'padding-bottom' => self::get_css_value( $attr['bottomPaddingTablet'], 'px' ),
					'padding-left'   => self::get_css_value( $attr['leftPaddingTablet'], 'px' ),
					'padding-right'  => self::get_css_value( $attr['rightPaddingTablet'], 'px' ),
					'margin-top'     => self::get_css_value( $attr['topMarginTablet'], 'px' ),
					'margin-bottom'  => self::get_css_value( $attr['bottomMarginTablet'], 'px' ),
					'margin-left'    => self::get_css_value( $attr['leftMarginTablet'], 'px' ),
					'margin-right'   => self::get_css_value( $attr['rightMarginTablet'], 'px' ),

				),

				' .wpcf7 input:not([type=submit])'    => array(
					'padding-top'    => self::get_css_value( $attr['inputTopPaddingTablet'], 'px' ),
					'padding-bottom' => self::get_css_value( $attr['inputBottomPaddingTablet'], 'px' ),
					'padding-left'   => self::get_css_value( $attr['inputLeftPaddingTablet'], 'px' ),
					'padding-right'  => self::get_css_value( $attr['inputRightPaddingTablet'], 'px' ),
					'text-indent'    => self::get_css_value( $attr['textIndentTablet'], 'px' ),
					'font-size'      => self::get_css_value( $attr['inputFontSizeTablet'], 'px' ),
					'margin-top'     => self::get_css_value( $attr['labelSpacingTablet'], 'px' ),
				),

				' .wpcf7 wpcf7-text'                  => array(
					'width' => self::get_css_value( $attr['textareaWidthTablet'], '%' ),
				),

				' .wpcf7 textarea'                    => array(
					'padding-top'    => self::get_css_value( $attr['inputTopPaddingTablet'], 'px' ),
					'padding-bottom' => self::get_css_value( $attr['inputBottomPaddingTablet'], 'px' ),
					'padding-left'   => self::get_css_value( $attr['inputLeftPaddingTablet'], 'px' ),
					'padding-right'  => self::get_css_value( $attr['inputRightPaddingTablet'], 'px' ),
					'width'          => self::get_css_value( $attr['textareaWidthTablet'], '%' ),
					'height'         => self::get_css_value( $attr['textareaHeightTablet'], 'px' ),
					'text-indent'    => self::get_css_value( $attr['textIndentTablet'], 'px' ),
					'font-size'      => self::get_css_value( $attr['inputFontSizeTablet'], 'px' ),
					'margin-top'     => self::get_css_value( $attr['labelSpacingTablet'], 'px' ),
				),

				' .wpcf7-form-control'                => array(
					'width'  => self::get_css_value( $attr['inputWidthTablet'], '%' ),
					'height' => self::get_css_value( $attr['inputHeightTablet'], 'px !important' ),
				),

				' .wpcf7 form.wpcf7-form:not(input)'  => array(
					'font-size'  => self::get_css_value( $attr['labelFontSizeTablet'], 'px' ),
					'margin-top' => self::get_css_value( $attr['labelSpacingTablet'], 'px' ),
				),
				// list.
				' .wpcf7 .wpcf7-list-item'            => array(
					'margin-top' => self::get_css_value( $attr['labelSpacingTablet'], 'px' ),
				),

				' .wpcf7 .wpcf7-list-item-label'      => array(
					'font-size' => self::get_css_value( $attr['radioCheckboxFontSizeTablet'], 'px' ),
				),

				' .wpcf7 select.wpcf7-form-control.wpcf7-select.wpcf7-validates-as-required' => array(
					'margin-top' => self::get_css_value( $attr['labelSpacingTablet'], 'px' ),
				),
				// Submit button
				' .wpcf7 input.wpcf7-form-control.wpcf7-submit' => array(
					'font-size'      => self::get_css_value( $attr['submitButtonFontSizeTablet'], 'px' ),
					'padding-top'    => self::get_css_value( $attr['ctaVpaddingTablet'], 'px' ),
					'padding-bottom' => self::get_css_value( $attr['ctaVpaddingTablet'], 'px' ),
					'padding-left'   => self::get_css_value( $attr['ctaHpaddingTablet'], 'px' ),
					'padding-right'  => self::get_css_value( $attr['ctaHpaddingTablet'], 'px' ),
					'width'          => self::get_css_value( $attr['submitButtonWidthTablet'], '%' ),
					'height'         => self::get_css_value( $attr['submitButtonHeightTablet'], 'px' ),
				),

				' .wpcf7-not-valid-tip'               => array(
					'font-size' => self::get_css_value( $attr['messageFontSizeTablet'], 'px' ),

				),
				' .wpcf7 form .wpcf7-response-output' => array(
					'font-size'      => self::get_css_value( $attr['afterSubmitFontSizeTablet'], 'px' ),
					'padding-top'    => self::get_css_value( $attr['afterSubmitTopPaddingTablet'], 'px' ),
					'padding-bottom' => self::get_css_value( $attr['afterSubmitBottomPaddingTablet'], 'px' ),
					'padding-left'   => self::get_css_value( $attr['afterSubmitLeftPaddingTablet'], 'px' ),
					'padding-right'  => self::get_css_value( $attr['afterSubmitRightPaddingTablet'], 'px' ),
					'margin-top'     => self::get_css_value( $attr['afterSubmitTopMarginTablet'], 'px' ),
					'margin-bottom'  => self::get_css_value( $attr['afterSubmitBottomMarginTablet'], 'px' ),
					'margin-left'    => self::get_css_value( $attr['afterSubmitLeftMarginTablet'], 'px' ),
					'margin-right'   => self::get_css_value( $attr['afterSubmitRightMarginTablet'], 'px' ),

				),
			);

			$combined_selectors = array(
				'desktop' => $selectors,
				'tablet'  => $tablet_selectors,
				'mobile'  => $mobile_selectors,
			);

			// $id              = '.responsive-block-editor-addons-block-contact-form-7-styler.block-id-' . $id;
			$id  = '.responsive-block-editor-addons-block-contact-form-7-styler.block-' . $id;
			$css = Responsive_Block_Editor_Addons_Frontend_Styles_Helper::responsive_block_editor_addons_generate_all_css( $combined_selectors, $id );
			return $css;
		}

		/**
		 * Get Defaults for Contact Form 7 Styler block
		 *
		 * @return array
		 */
		public static function get_responsive_block_contact_form_7_styler_block_default_attributes() {
			return array(
				'formId' => '-1',
				'formJson' => null,
				'isHtml' => false,
				'block_id'                       => '',
				'formTitleTag'                   => 'h2',
				'formTitle'						 => 'Form Title',
				'formTitleId'    				=> '',
				'showFormTitle'                  => false,
				'showFormDescription'            => false,
				// 'showLabels'=>true,
				// 'showErrorMsgs'=>true,
				'formDescription'=>'Form Description',
				'formWidth'                      => 100,
				'formWidthMobile'                => '',
				'formWidthTablet'                => '',
				'formAlignment'                  => 'left',
				'formAlignmentMobile'            => 'left',
				'formAlignmentTablet'            => 'left',
				'formTitleAlignment'             => 'center',
				'formTitleAlignmentMobile'       => 'center',
				'formTitleAlignmentTablet'       => 'center',
				'formBorderColor'                => '',
				'formBorderStyle'                => 'none',
				'formBorderWidth'                => 0,
				'formBorderRadius'               => 0,
				'formTitleColor'                 => '',
				'formTitleFontFamily'            => '',
				'formTitleFontSize'              => '',
				'formTitleFontSizeMobile'        => '',
				'formTitleFontSizeTablet'        => '',
				'formTitleFontWeight'            => 600,
				'formTitleLineHeight'            => 1,
				'formTitleLetterSpacing'         => 0,
				'formDescriptionColor'           => '',
				'formDescriptionFontFamily'      => '',
				'formDescriptionFontSize'        => '',
				'formDescriptionFontSizeMobile'  => '',
				'formDescriptionFontSizeTablet'  => '',
				'formDescriptionFontWeight'      => 400,
				'formDescriptionLineHeight'      => 1,
				'formDescriptionLetterSpacing'   => 0,
				'topPadding'                     => 0,
				'topPaddingMobile'               => 0,
				'topPaddingTablet'               => 0,
				'bottomPadding'                  => 0,
				'bottomPaddingMobile'            => 0,
				'bottomPaddingTablet'            => 0,
				'leftPadding'                    => 0,
				'leftPaddingMobile'              => 0,
				'leftPaddingTablet'              => 0,
				'rightPadding'                   => 0,
				'rightPaddingMobile'             => 0,
				'rightPaddingTablet'             => 0,
				'topMargin'                      => 0,
				'topMarginMobile'                => 0,
				'topMarginTablet'                => 0,
				'bottomMargin'                   => 0,
				'bottomMarginMobile'             => 0,
				'bottomMarginTablet'             => 0,
				'leftMargin'                     => 0,
				'leftMarginMobile'               => 0,
				'leftMarginTablet'               => 0,
				'rightMargin'                    => 0,
				'rightMarginMobile'              => 0,
				'rightMarginTablet'              => 0,

				'opacity'                        => 20,
				'colorLocation1'                 => 0,
				'colorLocation2'                 => 100,
				'gradientDirection'              => 90,
				'hovercolorLocation1'            => 0,
				'hovercolorLocation2'            => 100,
				'hovergradientDirection'         => 90,
				'backgroundImage'                => '',
				'backgroundImagePosition'        => 'center center',
				'backgroundImageSize'            => 'cover',
				'backgroundImageRepeat'          => 'no-repeat',
				'backgroundAttachment'           => 'scroll',
				'backgroundImageColor'           => '',
				'overlayType'                    => 'color',
				'gradientOverlayColor1'          => '',
				'gradientOverlayColor2'          => '',
				'gradientOverlayType'            => 'linear',
				'gradientOverlayLocation1'       => 0,
				'gradientOverlayLocation2'       => 100,
				'gradientOverlayAngle'           => 0,
				'gradientOverlayPosition'        => 'center center',
				'backgroundType'                 => '',
				'backgroundColor'                => '',
				'backgroundColorHover'           => '',
				'backgroundColor1'               => '',
				'backgroundColor2'               => '',
				'hoverbackgroundColor1'          => '',
				'hoverbackgroundColor2'          => 'contrast',
				'backgroundPosition'             => 'empty', // For compatibility with v1.3.2.
				'backgroundRepeat'               => 'empty', // For compatibility with v1.3.2.
				'backgroundSize'                 => 'empty', // For compatibility with v1.3.2.
				'backgroundHoverImage'           => '',
				'backgroundImageHoverPosition'   => '',
				'backgroundImageHoverRepeat'     => '',
				'backgroundImageHoverAttachment' => '',
				'backgroundImageHoverSize'       => '',
				'boxShadowColor'                 => '',
				'boxShadowHOffset'               => 0,
				'boxShadowVOffset'               => 0,
				'boxShadowBlur'                  => 0,
				'boxShadowSpread'                => 0,
				'boxShadowPosition'              => 'outset',
				'hoverboxShadowColor'            => '',
				'hoverboxShadowHOffset'          => 0,
				'hoverboxShadowVOffset'          => 0,
				'hoverboxShadowBlur'             => 0,
				'hoverboxShadowSpread'           => 0,
				'hoverboxShadowPosition'         => 'outset',
				'inputTextColor'                 => '',
				'inputBackgroundColor'           => '#f7f7f7',
				'inputBorderColor'               => '',
				'inputBorderStyle'               => 'none',
				'inputBorderWidth'               => 1,
				'inputBorderRadius'              => '',
				'inputTopPadding'                => 10,
				'inputTopPaddingMobile'          => 10,
				'inputTopPaddingTablet'          => 10,
				'inputBottomPadding'             => 10,
				'inputBottomPaddingMobile'       => 10,
				'inputBottomPaddingTablet'       => 10,
				'inputLeftPadding'               => 10,
				'inputLeftPaddingMobile'         => 10,
				'inputLeftPaddingTablet'         => 10,
				'inputRightPadding'              => 10,
				'inputRightPaddingMobile'        => 10,
				'inputRightPaddingTablet'        => 10,
				'inputBoxShadowColor'            => '',
				'inputBoxShadowHOffset'          => 0,
				'inputBoxShadowVOffset'          => 0,
				'inputBoxShadowBlur'             => 0,
				'inputBoxShadowSpread'           => 0,
				'inputBoxShadowPosition'         => 'outset',
				'textareaWidth'                  => 100,
				'textareaWidthMobile'            => 100,
				'textareaWidthTablet'            => 100,
				'textareaHeight'                 => '',
				'textareaHeightMobile'           => '',
				'textareaHeightTablet'           => '',
				'textIndent'                     => '',
				'textIndentMobile'               => '',
				'textIndentTablet'               => '',
				'inputFontFamily'                => '',
				'inputFontSize'                  => 14,
				'inputFontSizeMobile'            => 14,
				'inputFontSizeTablet'            => 14,
				'inputFontWeight'                => 400,
				'inputLineHeight'                => 1,
				'inputLetterSpacing'             => 0,
				'showLabels'                     => true,
				'showErrorMsgs'                  => true,		
				'inputWidth'                     => 100,
				'inputWidthMobile'               => 100,
				'inputWidthTablet'               => 100,
				'inputHeight'                    => '',
				'inputHeightMobile'              => '',
				'inputHeightTablet'              => '',
				'labelColor'                     => '',
				'labelFontFamily'                => '',
				'labelFontSize'                  => '',
				'labelFontSizeMobile'            => '',
				'labelFontSizeTablet'            => '',
				'labelFontWeight'                => '',
				'labelLineHeight'                => 1,
				'labelLetterSpacing'             => 0,
				'labelSpacing'                   => 10,
				'labelSpacingMobile'             => 10,
				'labelSpacingTablet'             => 10,
				'placeholderColor'               => '',
				'hoverRadioCheckboxColor'        => '#0066cc',
				'radioCheckboxColor'             => '',
				'radioCheckboxSize'              => 20,
				'radioCheckboxBorderWidth'       => 0,
				'radioCheckboxBorderColor'       => '',
				'radioButtonBorderRadius'        => 500,
				'checkboxBorderRadius'           => 0,
				'radioCheckboxTextColor'         => '',
				'radioCheckboxFontFamily'        => '',
				'radioCheckboxFontSize'          => '',
				'radioCheckboxFontSizeMobile'    => '',
				'radioCheckboxFontSizeTablet'    => '',
				'radioCheckboxFontWeight'        => '',
				'radioCheckboxLineHeight'        => 1,
				'radioCheckboxLetterSpacing'     => 0,
				'submitButtonFontFamily'         => '',
				'submitButtonFontSize'           => '',
				'submitButtonFontSizeMobile'     => '',
				'submitButtonFontSizeTablet'     => '',
				'submitButtonFontWeight'         => 400,
				'submitButtonLineHeight'         => 1,
				'submitButtonLetterSpacing'      => 0,
				'ctaColor'                       => '#ffffff',
				'ctaBackColor'                   => '#ff6f61',
				'ctaHoverColor'                  => '#ffffff',
				'ctaHoverBackColor'              => '#d64031',
				'buttonbackgroundType'           => 'color',
				'buttoncolorLocation1'           => 0,
				'buttoncolorLocation2'           => 100,
				'buttongradientDirection'        => 90,
				'buttonbackgroundColor1'         => '',
				'buttonbackgroundColor2'         => '#fff',
				'buttonHbackgroundType'          => 'color',
				'buttonHcolorLocation1'          => 0,
				'buttonHcolorLocation2'          => 100,
				'buttonHgradientDirection'       => 90,
				'buttonHbackgroundColor1'        => '',
				'buttonHbackgroundColor2'        => '#fff',
				'ctaButtonAlignment'             =>'center',
				'ctaButtonmarginleft'            =>0,
				'ctaButtonmarginright'           =>0,
				'ctaVpadding'                    => 15,
				'ctaVpaddingMobile'              => 15,
				'ctaVpaddingTablet'              => 15,
				'ctaHpadding'                    => 30,
				'ctaHpaddingMobile'              => 30,
				'ctaHpaddingTablet'              => 30,
				'ctaBorderColor'                 => '',
				'ctaBorderStyle'                 => 'none',
				'ctaBorderWidth'                 => '',
				'ctaBorderRadius'                => 0,
				'submitButtonWidth'              => '',
				'submitButtonWidthMobile'        => '',
				'submitButtonWidthTablet'        => '',
				'submitButtonHeight'             => '',
				'submitButtonHeightMobile'       => '',
				'submitButtonHeightTablet'       => '',
				'submitButtonBoxShadowColor'     => '',
				'submitButtonBoxShadowHOffset'   => 0,
				'submitButtonBoxShadowVOffset'   => 0,
				'submitButtonBoxShadowBlur'      => 0,
				'submitButtonBoxShadowSpread'    => 0,
				'submitButtonBoxShadowPosition'  => 'outset',				
				'ctaHoverColor'                  => '',
				'updatedButtonBgHColor'          => '',
				'ctaHoverBorderColor'            => '',
				'errorMsgColor'                  => '',
				'afterSubmitColor'               => '',
				'afterSubmitBorderColor'         => '',
				'afterSubmitBorderStyle'         => 'none',
				'afterSubmitBorderWidth'         => 1,
				'afterSubmitBorderRadius'        => 0,
				'afterSubmitFontFamily'          => '',
				'afterSubmitFontSize'            => '',
				'afterSubmitFontSizeMobile'      => '',
				'afterSubmitFontSizeTablet'      => '',
				'afterSubmitFontWeight'          => 400,
				'afterSubmitLineHeight'          => 1,
				'afterSubmitLetterSpacing'       => 0,
				'afterSubmitTopPadding'          => 15,
				'afterSubmitTopPaddingMobile'    => 15,
				'afterSubmitTopPaddingTablet'    => 15,
				'afterSubmitBottomPadding'       => 15,
				'afterSubmitBottomPaddingMobile' => 15,
				'afterSubmitBottomPaddingTablet' => 15,
				'afterSubmitLeftPadding'         => 15,
				'afterSubmitLeftPaddingMobile'   => 15,
				'afterSubmitLeftPaddingTablet'   => 15,
				'afterSubmitRightPadding'        => 15,
				'afterSubmitRightPaddingMobile'  => 15,
				'afterSubmitRightPaddingTablet'  => 15,
				'afterSubmitTopMargin'           => 15,
				'afterSubmitTopMarginMobile'     => 15,
				'afterSubmitTopMarginTablet'     => 15,
				'afterSubmitBottomMargin'        => 15,
				'afterSubmitBottomMarginMobile'  => 15,
				'afterSubmitBottomMarginTablet'  => 15,
				'afterSubmitLeftMargin'          => 15,
				'afterSubmitLeftMarginMobile'    => 15,
				'afterSubmitLeftMarginTablet'    => 15,
				'afterSubmitRightMargin'         => 15,
				'afterSubmitRightMarginMobile'   => 15,
				'afterSubmitRightMarginTablet'   => 15,
				'messageFontFamily'              => '',
				'messageFontSize'                => '',
				'messageFontSizeMobile'          => '',
				'messageFontSizeTablet'          => '',
				'messageFontWeight'              => 400,
				'messageLineHeight'              => 1,
				'messageLetterSpacing'           => 0,
				'successMsgColor'                => '',
				'afterSubmitErrorMsgColor'       => '',
				'afterSubmitMsgbgColor'          =>'',
			);
		}
		public static function get_responsive_block_image_css($attr, $id)
		{
			//get the protocol
			$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
			// Get the host (domain and port)
			$host = $_SERVER['HTTP_HOST'];
			$baseUrl = $protocol . '://' . $host;

			$defaults = self::get_responsive_block_image_block_default_attributes();
			$attr     = array_merge($defaults, (array) $attr);
			$filterValue = ($attr['imageOnHoverImage'] === "grayscale") ? "grayscale(100%)" : (($attr['imageOnHoverImage'] === "blur") ? "blur(5px)" : null);
			$zoomintransition = ($attr['imageOnHoverImage'] === "zoomin" || "slide") ? "transform .35s ease-in-out" : null;
			$zoomintransform = ($attr['imageOnHoverImage'] === "zoomin") ? "scale(1.1)" : (($attr['imageOnHoverImage'] === "slide") ? "translate3d(0px, 0, 0)" :
				null);
			$slidetransform = ($attr['imageOnHoverImage'] === "slide") ? "translate3d(-40px, 0, 0)" : null;

			$mobile_selectors = array();
			$tablet_selectors = array();
			$maskImageUrl = $baseUrl . $attr['MaskShape'];
			$leftOverlayAlign =
				$attr['LayoverContentPosition'] === "lefttop" ||
				$attr['LayoverContentPosition'] === "leftcenter" ||
				$attr['LayoverContentPosition'] === "leftbottom"
				? "flex-start"
				: ($attr['LayoverContentPosition'] === "centertop" ||
					$attr['LayoverContentPosition'] === "centercenter" ||
					$attr['LayoverContentPosition'] === "centerbottom"
					? "center"
					: ($attr['LayoverContentPosition'] === "righttop" ||
						$attr['LayoverContentPosition'] === "rightcenter" ||
						$attr['LayoverContentPosition'] === "rightbottom"
						? "flex-end"
						: "center"));

			$leftOverlayJustify =
				$attr['LayoverContentPosition'] === "lefttop" ||
				$attr['LayoverContentPosition'] === "centertop" ||
				$attr['LayoverContentPosition'] === "righttop"
				? "flex-start"
				: ($attr['LayoverContentPosition'] === "leftcenter" ||
					$attr['LayoverContentPosition'] === "centercenter" ||
					$attr['LayoverContentPosition'] === "rightcenter"
					? "center"
					: ($attr['LayoverContentPosition'] === "leftbottom" ||
						$attr['LayoverContentPosition'] === "centerbottom" ||
						$attr['LayoverContentPosition'] === "rightbottom"
						? "flex-end"
						: "center"));
			$selectors = array(
				' .img-main-block' => array(
					'text-align' => $attr['imageAlignment'],
				),
				' .img-block' => array(
					"transform" => $slidetransform,
				), 
				"  .responsive-blocks-image-block" => array(
					"border-color" => $attr['imageBorderColor'],
					"border-width" => self::get_css_value($attr['imageBorderWidth'], 'px'),
					"border-radius" => self::get_css_value($attr['imageBorderRadius'], 'px'),
					"border-style" => $attr['imageBorderStyle'],
					"box-shadow" =>
					self::get_css_value($attr['imageboxShadowHOffset'], 'px') . " " .
						self::get_css_value($attr['imageboxShadowVOffset'], 'px') .
						" " .
						self::get_css_value($attr['imageboxShadowBlur'], 'px') .
						" " .
						self::get_css_value($attr['imageboxShadowSpread'], 'px') .
						" " .
						$attr['imageboxShadowColor'],
					"margin-top" => self::get_css_value($attr['imagetopmargin'], 'px'),
					"margin-bottom" => self::get_css_value($attr['imagebottommargin'], 'px'),
					"margin-left" => self::get_css_value($attr['imageleftmargin'], 'px'),
					"margin-right" => self::get_css_value($attr['imagerightmargin'], 'px'),
					"-webkit-mask-image" => $attr['MaskShape'] !== "none" ? "url('" . $maskImageUrl . "')" : null,
					"mask-shape" => $attr['MaskShape'] !== "none" ? "url('" . $maskImageUrl . "')" : null,
					"-webkit-mask-size" => $attr['MaskSize'],
					"mask-size" => $attr['MaskSize'],
					"-webkit-mask-position" => $attr['MaskPosition'],
					"mask-position" => $attr['MaskPosition'],
					"-webkit-mask-repeat" => $attr['MaskRepeat'],
					"mask-repeat" => $attr['MaskRepeat'],
					"object-fit" => $attr['imageObjectFit'],
					"width" => self::get_css_value($attr['imageWidth'], 'px'),
					"height" => self::get_css_value($attr['imageHeight'], 'px'),
				),
				"  .img-main-block:hover figure" => array(
					"box-shadow" =>
					self::get_css_value($attr['imageboxShadowHoverHOffset'], 'px') . " " .
						self::get_css_value($attr['imageboxShadowHoverVOffset'], 'px') .
						" " .
						self::get_css_value($attr['imageboxShadowHoverBlur'], 'px') .
						" " .
						self::get_css_value($attr['imageboxShadowHoverSpread'], 'px') .
						" " .
						$attr['imageboxShadowHoverColor'],
					"filter" => $filterValue,
					"transition" => $zoomintransition,
					"transform" => $zoomintransform

				),
				" .responsive-image-block-description" => array(
					"align-items" => $leftOverlayAlign,
					"justify-content" => $leftOverlayJustify,
					"border-color" => $attr['layoverimageBorderColor'],
					"border-width" => self::get_css_value($attr['layoverimageBorderWidth'], "px"),
					"border-radius" => self::get_css_value($attr['layoverimageBorderRadius'], "px"),
					"border-style" => $attr['layoverimageBorderStyle'],
					"top" => self::get_css_value($attr['layoverInputDistance'], "px"),
					"bottom" => self::get_css_value($attr['layoverInputDistance'], "px"),
					"right" => self::get_css_value($attr['layoverInputDistance'], "px"),
					"left" => self::get_css_value($attr['layoverInputDistance'], "px"),
					"background" => $attr['layoverBackgroundcolor'],
					"opacity" => self::get_css_value($attr['layoverOpacity'], "%"),
				),
				" .responsive-img-heading"=> array(
					"font-family" => $attr['layoverHeadingFontFamily'],
					"font-size" => self::get_css_value($attr['layoverHeadingFontSize'], "px"),
					"font-weight" => $attr['layoverHeadingFontWeight'],
					"line-height" => $attr['layoverHeadingLineHeight'],
					"letter-spacing" => self::get_css_value($attr['layoverHeadingLetterSpacing'], "px"),
					"text-transform" => $attr['layoverHeadingTextTransform'],
					"color" => $attr['layoverHeadingColor'],
					"margin-top" => self::get_css_value($attr['layoverHeadingtopmargin'], "px"),
					"margin-bottom" => self::get_css_value($attr['layoverHeadingbottommargin'], "px"),
					"margin-left" => self::get_css_value($attr['layoverHeadingleftmargin'], "px"),
					"margin-right" => self::get_css_value($attr['layoverHeadingrightmargin'], "px"),
				),
				" .responsive-image-block-description:hover" => array(
					"opacity" => self::get_css_value($attr['layoverHoverOpacity'], "%"),
				),
				" .responsive-image-block-description-overlay" => array(
					"opacity" => self::get_css_value($attr['layoverOpacity'], "%")
				),
				" .responsive-image-block-description-overlay:hover" => array(
					"opacity" => self::get_css_value($attr['layoverHoverOpacity'], "%"),
				),

				" .responsive-img-caption" => array(
					"font-family" => $attr['captionFontFamily'],
					"font-size" => self::get_css_value($attr['captionFontSize'], 'px'),
					"line-height" =>  $attr['captionLineHeight'],
					"letter-spacing" => self::get_css_value($attr['captionLetterSpacing'], 'px'),
					"text-transform" => $attr['captionTextTransform'],
					"color" => $attr['captionColor'],
					"margin-top" => self::get_css_value($attr['captiontopmargin'], 'px'),
					"margin-bottom" => self::get_css_value($attr['captionbottommargin'], 'px'),
					"margin-left" => self::get_css_value($attr['captionleftmargin'], 'px'),
					"margin-right" => self::get_css_value($attr['captionrightmargin'], 'px'),
					"text-align" => $attr['captionimageAlignment']
				)
			);
			$tablet_selectors = array(
				' .img-main-block' => array(
					'text-align' => $attr['imageAlignmentTablet'],
				),
				"  .responsive-blocks-image-block" => array(
					"margin-top" => self::get_css_value($attr['imagetopmarginTablet'], 'px'),
					"margin-bottom" => self::get_css_value($attr['imagebottommarginTablet'], 'px'),
					"margin-left" => self::get_css_value($attr['imageleftmarginTablet'], 'px'),
					"margin-right" => self::get_css_value($attr['imagerightmarginTablet'], 'px'),
					"width" => self::get_css_value($attr['imageWidthTablet'], 'px'),
					"height" => self::get_css_value($attr['imageHeightTablet'], 'px'),
				),
				" .responsive-img-caption" => array(
					"font-size" => self::get_css_value($attr['captionFontSizeTablet'], 'px'),
					"margin-top" => self::get_css_value($attr['captiontopmarginTablet'], 'px'),
					"margin-bottom" => self::get_css_value($attr['captionbottommarginTablet'], 'px'),
					"margin-left" => self::get_css_value($attr['captionleftmarginTablet'], 'px'),
					"margin-right" => self::get_css_value($attr['captionrightmarginTablet'], 'px'),
					"text-align" => $attr['captionimageAlignmentTablet']
				),
				" .responsive-img-heading" => array(
					"font-size" => self::get_css_value($attr['layoverHeadingFontSizeTablet'],"px"),
					"margin-top" => self::get_css_value($attr['layoverHeadingtopmarginTablet'],"px"),
					"margin-bottom" => self::get_css_value($attr['layoverHeadingbottommarginTablet'],"px"),
					"margin-left" => self::get_css_value($attr['layoverHeadingleftmarginTablet'],"px"),
					"margin-right" => self::get_css_value($attr['layoverHeadingrightmarginTablet'],"px"),
				),
			);

			$mobile_selectors = array(
				' .img-main-block' => array(
					'text-align' => $attr['imageAlignmentMobile'],
				),
				"  .responsive-blocks-image-block" => array(
					"margin-top" => self::get_css_value($attr['imagetopmarginMobile'], 'px'),
					"margin-bottom" => self::get_css_value($attr['imagebottommarginMobile'], 'px'),
					"margin-left" => self::get_css_value($attr['imageleftmarginMobile'], 'px'),
					"margin-right" => self::get_css_value($attr['imagerightmarginMobile'], 'px'),
					"width" => self::get_css_value($attr['imageWidthMobile'], 'px'),
					"height" => self::get_css_value($attr['imageHeightMobile'], 'px'),
				),
				" .responsive-img-caption" => array(
					"font-size" => self::get_css_value($attr['captionFontSizeMobile'], 'px'),
					"margin-top" => self::get_css_value($attr['captiontopmarginMobile'], 'px'),
					"margin-bottom" => self::get_css_value($attr['captionbottommarginMobile'], 'px'),
					"margin-left" => self::get_css_value($attr['captionleftmarginMobile'], 'px'),
					"margin-right" => self::get_css_value($attr['captionrightmarginMobile'], 'px'),
					"text-align" => $attr['captionimageAlignmentMobile'],
				),
				" .responsive-img-heading" => array(
					"font-size" => self::get_css_value($attr['layoverHeadingFontSizeMobile'],"px"),
					"margin-top" => self::get_css_value($attr['layoverHeadingtopmarginMobile'],"px"),
					"margin-bottom" => self::get_css_value($attr['layoverHeadingbottommarginMobile'],"px"),
					"margin-left" => self::get_css_value($attr['layoverHeadingleftmarginMobile'],"px"),
					"margin-right" => self::get_css_value($attr['layoverHeadingrightmarginMobile'],"px"),
				),
			);

			$combined_selectors = array(
				'desktop' => $selectors,
				'tablet'  => $tablet_selectors,
				'mobile'  => $mobile_selectors,
			);

			$id  = '.responsive-block-editor-addons-block-image.block-' . $id;
			$css = Responsive_Block_Editor_Addons_Frontend_Styles_Helper::responsive_block_editor_addons_generate_all_css($combined_selectors, $id);
			return $css;
		}
		public static function get_responsive_block_image_block_default_attributes()
		{
			return array(
				'imageUrl' => '',
				'altText' => '',
				'caption' => '',
				'sourceType' => '',
				'imageAlignment' => 'left',
				'imageAlignmentTablet' => 'left',
				'imageAlignmentMobile' => 'left',
				'imageBorderColor' => 'none',
				'imageBorderRadius' => '0',
				'imageBorderStyle' => '0',
				'imageBorderWidth' => '0',
				'imageboxShadowColor' => 'none',
				'imageboxShadowHOffset' => '0',
				'imageboxShadowVOffset' => '0',
				'imageboxShadowBlur' => '0',
				'imageboxShadowSpread' => '0',
				'imagebottommargin' => '0',
				'imagetopmargin' => '0',
				'imageleftmargin' => '0',
				'imagerightmargin' => '0',
				'imagetopmarginTablet' => '0',
				'imagebottommarginTablet' => '0',
				'imageleftmarginTablet' => '0',
				'imagerightmarginTablet' => '0',
				'imagetopmarginMobile' => '0',
				'imagebottommarginMobile' => '0',
				'imageleftmarginMobile' => '0',
				'imagerightmarginMobile' => '0',
				'imageboxShadowHoverColor' => 'none',
				'imageboxShadowHoverHOffset' => '0',
				'imageboxShadowHoverVOffset' => '0',
				'imageboxShadowHoverBlur' => '0',
				'imageboxShadowHoverSpread' => '0',
				'imageboxShadowHoverPosition' => '0',
				'captionFontFamily' => '',
				'captionFontSize' => '13',
				'captionLineHeight' => '1',
				'captionLetterSpacing' => '0',
				'captionTextTransform' => 'default',
				'captionFontSizeMobile' => '13',
				'captionFontSizeTablet' => '13',
				'captionColor' => '',
				'captiontopmargin' => '0',
				'captionbottommargin' => '0',
				'captionleftmargin' => '0',
				'captionrightmargin' => '0',
				'captiontopmarginTablet' => '0',
				'captionbottommarginTablet' => '0',
				'captionleftmarginTablet' => '0',
				'captionrightmarginTablet' => '0',
				'captiontopmarginMobile' => '0',
				'captionbottommarginMobile' => '0',
				'captionleftmarginMobile' => '0',
				'captionrightmarginMobile' => '0',
				'captionimageAlignment' => 'left',
				'captionimageAlignmentTablet' => 'left',
				'captionimageAlignmentMobile' => 'left',
				'MaskShape' => 'none',
				'MaskSize' => 'auto',
				'MaskPosition' => 'center top',
				'MaskRepeat' => 'no-repeat',
				'imageOnHoverImage' => '',
				'imageObjectFit' => 'inherit',
				'imageWidth' => '',
				'imageHeight' => '',
				'imageWidthTablet' => '',
				'imageHeightTablet' => '',
				'imageWidthMobile' => '',
				'imageHeightMobile' => '',
				'LayoverContentPosition' => '',
				'layoverimageBorderColor' => 'none',
				'layoverimageBorderRadius' => '0',
				'layoverimageBorderStyle' => '',
				'layoverimageBorderWidth' => '0',
				'layoverInputDistance' => '15',
				'layoverBackgroundcolor' => 'none',
				'layoverOpacity' => '20',
				'layoverHoverOpacity' => '100',
				'Layoverswitch' => 'false',
				'layoverHeadingFontFamily' => 'none',
				'layoverHeadingFontSize' => '16',
				'layoverHeadingFontSizeMobile' => '16',
				'layoverHeadingFontSizeTablet' => '16',
				'layoverHeadingFontWeight' => '600',
				'layoverHeadingLineHeight' => '1',
				'layoverHeadingLetterSpacing' => '0',
				'layoverHeadingTextTransform' => 'default',
				'layoverHeadingColor' => 'none',
				'layoverHeadingtopmargin' => '0',
				'layoverHeadingbottommargin' => '0',
				'layoverHeadingleftmargin' => '0',
				'layoverHeadingrightmargin' => '0',
				'layoverHeadingtopmarginTablet' => '0',
				'layoverHeadingbottommarginTablet' => '0',
				'layoverHeadingleftmarginTablet' => '0',
				'layoverHeadingrightmarginTablet' => '0',
				'layoverHeadingtopmarginMobile' => '0',
				'layoverHeadingbottommarginMobile' => '0',
				'layoverHeadingleftmarginMobile' => '0',
				'layoverHeadingrightmarginMobile' => '0',
			);
		}

		/**
		 * Get Popup CSS
		 *
		 * @param array  $attr The block attributes.
		 * @param string $id The selector ID.
		 * @return array Styles.
		 */
		public static function get_responsive_block_popup_css( $attr, $id ) {
			$defaults = self::get_responsive_block_popup_block_default_attributes();
			$attr     = array_merge( $defaults, (array) $attr );

			$mobile_selectors = array();
			$tablet_selectors = array();

			$selectors        = array();

			$popupScreenPositions = [];

			// Define a private method to set popup positions based on screen type
			$set_popup_position = function (&$popup_screen_positions, $screen, $top, $left, $center, $right, $bottom) {
				$popup_screen_position = &$popup_screen_positions[$screen];
				$popup_screen_position['top']    = $top !== 'unset' ? self::get_css_value($top, "px") : 'unset';
				$popup_screen_position['left']   = $left !== 'unset' ? self::get_css_value($left, "px") : 'unset';
				$popup_screen_position['center'] = $center;
				$popup_screen_position['right']  = $right !== 'unset' ? self::get_css_value($right, "px") : 'unset';
				$popup_screen_position['bottom'] = $bottom !== 'unset' ? self::get_css_value($bottom, "px") : 'unset';
			};

			// Set positions for desktop
			switch ( $attr['popupScreenType'] ) {
				case 'top left':
					$set_popup_position($popupScreenPositions, 'desktop', 30, 30, 'unset', 'unset', 'unset');
					break;
				case 'top center':
					$set_popup_position($popupScreenPositions, 'desktop', 30, 'unset', 'unset', 'unset', 'unset');
					break;
				case 'top right':
					$set_popup_position($popupScreenPositions, 'desktop', 30, 'unset', 'unset', 30, 'unset');
					break;
				case 'center left':
					$set_popup_position($popupScreenPositions, 'desktop', 'unset', 30, 'unset', 'unset', 'unset');
					break;
				case 'center center':
					$set_popup_position($popupScreenPositions, 'desktop', 'unset', 'unset', 'unset', 'unset', 'unset');
					break;
				case 'center right':
					$set_popup_position($popupScreenPositions, 'desktop', 'unset', 'unset', 'unset', 30, 'unset');
					break;
				case 'bottom left':
					$set_popup_position($popupScreenPositions, 'desktop', 'unset', 30, 'unset', 'unset', 30);
					break;
				case 'bottom center':
					$set_popup_position($popupScreenPositions, 'desktop', 'unset', 'unset', 'unset', 'unset', 30);
					break;
				case 'bottom right':
					$set_popup_position($popupScreenPositions, 'desktop', 'unset', 'unset', 'unset', 30, 30);
					break;
			}

			// Set positions for tablet
			switch ( $attr['popupScreenTypeTablet'] ) {
				case 'top left':
					$set_popup_position($popupScreenPositions, 'tablet', 15, 15, 'unset', 'unset', 'unset');
					break;
				case 'top center':
					$set_popup_position($popupScreenPositions, 'tablet', 15, 'unset', 'unset', 'unset', 'unset');
					break;
				case 'top right':
					$set_popup_position($popupScreenPositions, 'tablet', 15, 'unset', 'unset', 15, 'unset');
					break;
				case 'center left':
					$set_popup_position($popupScreenPositions, 'tablet', 'unset', 15, 'unset', 'unset', 'unset');
					break;
				case 'center center':
					$set_popup_position($popupScreenPositions, 'tablet', 'unset', 'unset', 'unset', 'unset', 'unset');
					break;
				case 'center right':
					$set_popup_position($popupScreenPositions, 'tablet', 'unset', 'unset', 'unset', 15, 'unset');
					break;
				case 'bottom left':
					$set_popup_position($popupScreenPositions, 'tablet', 'unset', 15, 'unset', 'unset', 15);
					break;
				case 'bottom center':
					$set_popup_position($popupScreenPositions, 'tablet', 'unset', 'unset', 'unset', 'unset', 15);
					break;
				case 'bottom right':
					$set_popup_position($popupScreenPositions, 'tablet', 'unset', 'unset', 'unset', 15, 15);
					break;
			}

			// Set positions for mobile
			switch ( $attr['popupScreenTypeMobile'] ) {
				case 'top left':
					$set_popup_position($popupScreenPositions, 'mobile', 10, 10, 'unset', 'unset', 'unset');
					break;
				case 'top center':
					$set_popup_position($popupScreenPositions, 'mobile', 10, 'unset', 'unset', 'unset', 'unset');
					break;
				case 'top right':
					$set_popup_position($popupScreenPositions, 'mobile', 10, 'unset', 'unset', 10, 'unset');
					break;
				case 'center left':
					$set_popup_position($popupScreenPositions, 'mobile', 'unset', 10, 'unset', 'unset', 'unset');
					break;
				case 'center center':
					$set_popup_position($popupScreenPositions, 'mobile', 'unset', 'unset', 'unset', 'unset', 'unset');
					break;
				case 'center right':
					$set_popup_position($popupScreenPositions, 'mobile', 'unset', 'unset', 'unset', 10, 'unset');
					break;
				case 'bottom left':
					$set_popup_position($popupScreenPositions, 'mobile', 'unset', 10, 'unset', 'unset', 10);
					break;
				case 'bottom center':
					$set_popup_position($popupScreenPositions, 'mobile', 'unset', 'unset', 'unset', 'unset', 10);
					break;
				case 'bottom right':
					$set_popup_position($popupScreenPositions, 'mobile', 'unset', 'unset', 'unset', 10, 10);
					break;
			}

			[$desktop, $tablet, $mobile] = array_values($popupScreenPositions);

			$selectors        = array(
				''                                                                            => array(),
				' .responsive-block-editor-addons-popup-trigger-wrap'                         => array(
				    'justify-content' => $attr['popupTriggerAlign'],
				),
				' .responsive-block-editor-addons-popup-button-trigger'                       => array(
					'color'            =>  $attr['popupButtonColor'],
					'background-color' => $attr['popupButtonBGState'] === 'transparent' ? 'transparent' : ( $attr['popupButtonBGState'] === 'solid' ? $attr['popupButtonBGColor'] : 'unset' ),
					'background-image' => $attr['popupButtonBGState'] === 'gradient' ? $attr['popupButtonBGGradient'] : 'unset',
					'font-family'      => $attr['popupButtonTypographyFontFamily'],
					'font-size'        => self::get_css_value( $attr['popupButtonTypographyFontSize'], 'px' ),
					'font-weight'      => (int) $attr['popupButtonTypographyFontWeight'],
					'line-height'      => $attr['popupButtonTypographyLineHeight'],
					'letter-spacing'   => self::get_css_value( $attr['popupButtonTypographyLetterSpacing'], 'px' ),
					'padding-top'      => self::get_css_value( $attr['popupButtonPaddingTop'], 'px' ),
					'padding-bottom'   => self::get_css_value( $attr['popupButtonPaddingBottom'], 'px' ),
					'padding-left'     => self::get_css_value( $attr['popupButtonPaddingLeft'], 'px' ),
					'padding-right'    => self::get_css_value( $attr['popupButtonPaddingRight'], 'px' ),
					'border-width'     => self::get_css_value( $attr['popupButtonBorderWidth'], 'px' ),
					'border-style'     => $attr['popupButtonBorderStyle'],
					'border-color'     => $attr['popupButtonBorderColor'],
					'border-radius'    => self::get_css_value( $attr['popupButtonBorderRadius'], 'px' ),
				),
				' .responsive-block-editor-addons-popup-button-trigger:hover'                 => array(
				    'color'            => $attr['popupButtonHoverColor'],
					'background-color' => $attr['popupButtonBGHoverState'] === 'transparent' ? 'transparent' : ( $attr['popupButtonBGHoverState'] === 'solid' ? $attr['popupButtonBGHoverColor'] : 'unset' ),
					'background-image' => $attr['popupButtonBGHoverState'] === 'gradient' ? $attr['popupButtonHoverBGGradient'] : 'unset',
					'border-color'     => $attr['popupButtonBorderHoverColor'],
				),
				' .responsive-block-editor-addons-popup-text-trigger'                         => array(
				    'color'          => $attr['popupTextColor'],
				    'font-family'    => $attr['popupTextTypographyFontFamily'],
				    'font-size'      => self::get_css_value( $attr['popupTextTypographyFontSize'], 'px' ),
					'font-weight'    => (int) $attr['popupTextTypographyFontWeight'],
					'line-height'    => $attr['popupTextTypographyLineHeight'],
				    'letter-spacing' => self::get_css_value( $attr['popupTextTypographyLetterSpacing'], 'px' ),
				),
				' .responsive-block-editor-addons-popup-icon-trigger svg'                     => array(
				    'fill'        => $attr['popupIconTriggerColor'],
					'width'       => self::get_css_value( $attr['popupIconTriggerSize'], 'px' ),
					'height'      => self::get_css_value( $attr['popupIconTriggerSize'], 'px' ),
					'line-height' => self::get_css_value( $attr['popupIconTriggerSize'], 'px' ),
					'font-size'   => self::get_css_value( $attr['popupIconTriggerSize'], 'px' ),
				),
				' .responsive-block-editor-addons-popup-image-trigger'                        => array(
				    'width'         => self::get_css_value( $attr['popupImageTriggerWidth'], 'px'),
      				'border-radius' => self::get_css_value( $attr['popupImageTriggerBorderRadius'], 'px'),
				),
				' .responsive-block-editor-addons-popup-modal-content'                        => array(
					'width'            => self::get_css_value( $attr['popupContainerWidth'], 'px' ),
					'height'           => 'auto' !== $attr['popupHeightType'] ? self::get_css_value( $attr['popupHeightCustom'], "px" ) : 'auto',
					'padding-top'      => self::get_css_value( $attr['popupPaddingTop'], 'px' ),
					'padding-right'    => self::get_css_value( $attr['popupPaddingRight'], 'px' ),
					'padding-bottom'   => self::get_css_value( $attr['popupPaddingBottom'], 'px' ),
					'padding-left'     => self::get_css_value( $attr['popupPaddingLeft'], 'px' ),
					'top'              => $desktop['top'],
					'left'             => $desktop['left'],
					'right'            => $desktop['right'],
					'bottom'           => $desktop['bottom'],
					'background-image' => 'gradient' === $attr['popupBgType'] ? $attr['popupGradient'] : 'none',
					'background-color' => 'color' === $attr['popupBgType'] ? $attr['popupBgColor'] : 'unset',
					'border-style'     => $attr['popupBlockBorderStyle'],
					'border-width'     => self::get_css_value( $attr['popupBlockBorderWidth'], 'px' ),
					'border-radius'    => self::get_css_value( $attr['popupBlockBorderRadius'], 'px' ),
					'border-color'     => $attr['popupBlockBorderColor'],
				),
				' .responsive-block-editor-addons-popup-modal-header'                         => array(
					'justify-content' => $attr['popupToggleCloseBtnPosition'],
				),
				' .responsive-block-editor-addons-popup-modal-header .dashicons.dashicons-no' => array(
					'color' => $attr['popupCloseBtnColor'],
				),
				' .responsive-block-editor-addons-popup-modal-wrap-overlay'                   => array(
					'background-color' => $attr['popupOverlayColor'],
					'opacity'          => (int) $attr['popupOverlayOpacity'] / 100,
				),
			);
			$mobile_selectors = array(
				''                                                      => array(),
				' .responsive-block-editor-addons-popup-trigger-wrap'   => array(
					'justify-content' => $attr['popupTriggerAlignMobile'],
				),
				' .responsive-block-editor-addons-popup-button-trigger' => array(
					'font-size'      => self::get_css_value( $attr['popupButtonTypographyFontSizeMobile'], 'px'),
					'padding-top'    => self::get_css_value( $attr['popupButtonPaddingTopMobile'], 'px'),
					'padding-bottom' => self::get_css_value( $attr['popupButtonPaddingBottomMobile'], 'px'),
					'padding-left'   => self::get_css_value( $attr['popupButtonPaddingLeftMobile'], 'px'),
					'padding-right'  => self::get_css_value( $attr['popupButtonPaddingRightMobile'], 'px'),
				),
				' .responsive-block-editor-addons-popup-text-trigger'   => array(
					'font-size' => self::get_css_value( $attr['popupTextTypographyFontSizeMobile'], 'px'),
				),
				' .responsive-block-editor-addons-popup-image-trigger'  => array(
					'width' => self::get_css_value( $attr['popupImageTriggerWidthMobile'], 'px'),
				),
				' .responsive-block-editor-addons-popup-modal-content'  => array(
					'width'          => self::get_css_value( $attr['popupContainerWidthMobile'], 'px' ),
					'height'         => 'auto' !== $attr['popupHeightType'] ? self::get_css_value( $attr['popupHeightCustomMobile'], 'px' ) : 'auto',
					'padding-top'    => self::get_css_value( $attr['popupPaddingTopMobile'], 'px' ),
					'padding-right'  => self::get_css_value( $attr['popupPaddingRightMobile'], 'px' ),
					'padding-bottom' => self::get_css_value( $attr['popupPaddingBottomMobile'], 'px' ),
					'padding-left'   => self::get_css_value( $attr['popupPaddingLeftMobile'], 'px' ),
					'top'            => $mobile['top'],
					'left'           => $mobile['left'],
					'right'          => $mobile['right'],
					'bottom'         => $mobile['bottom'],
				),
			);

			$tablet_selectors = array(
				''                                                      => array(),
				' .responsive-block-editor-addons-popup-trigger-wrap'   => array(
					'justify-content' => $attr['popupTriggerAlignTablet'],
				),
				' .responsive-block-editor-addons-popup-button-trigger' => array(
					'font-size' => self::get_css_value( $attr['popupButtonTypographyFontSizeTablet'], 'px'),
					'padding-top'    => self::get_css_value( $attr['popupButtonPaddingTopTablet'], 'px'),
					'padding-bottom' => self::get_css_value( $attr['popupButtonPaddingBottomTablet'], 'px'),
					'padding-left'   => self::get_css_value( $attr['popupButtonPaddingLeftTablet'], 'px'),
					'padding-right'  => self::get_css_value( $attr['popupButtonPaddingRightTablet'], 'px'),
				),
				' .responsive-block-editor-addons-popup-text-trigger'   => array(
					'font-size' => self::get_css_value( $attr['popupTextTypographyFontSizeTablet'], 'px'),
				),
				' .responsive-block-editor-addons-popup-image-trigger'  => array(
					'width' => self::get_css_value( $attr['popupImageTriggerWidthTablet'], 'px'),
				),
				' .responsive-block-editor-addons-popup-modal-content'  => array(
					'width'          => self::get_css_value( $attr['popupContainerWidthTablet'], 'px' ),
					'height'         => 'auto' !== $attr['popupHeightType'] ? self::get_css_value( $attr['popupHeightCustomTablet'], 'px' ) : 'auto',
					'padding-top'    => self::get_css_value( $attr['popupPaddingTopTablet'], 'px' ),
					'padding-right'  => self::get_css_value( $attr['popupPaddingRightTablet'], 'px' ),
					'padding-bottom' => self::get_css_value( $attr['popupPaddingBottomTablet'], 'px' ),
					'padding-left'   => self::get_css_value( $attr['popupPaddingLeftTablet'], 'px' ),
					'top'            => $tablet['top'],
					'left'           => $tablet['left'],
					'right'          => $tablet['right'],
					'bottom'         => $tablet['bottom'],
				),
			);

			$combined_selectors = array(
				'desktop' => $selectors,
				'tablet'  => $tablet_selectors,
				'mobile'  => $mobile_selectors,
			);

			$id  = '.responsive-block-editor-addons-block-popup.block-' . $id;
			$css = Responsive_Block_Editor_Addons_Frontend_Styles_Helper::responsive_block_editor_addons_generate_all_css( $combined_selectors, $id );
			return $css;
		}

		/**
		 * Get Defaults for Popup block.
		 *
		 * @return array
		 */
		public static function get_responsive_block_popup_block_default_attributes() {
			return array(
				'block_id'                            => '',
				'isPopupVariantSelected'              => false,
				'popupVariant'                        => '',
				'popupIcon'                           => 'fa fa-angellist',
				'popupContainerWidth'                 => 600,
				'popupContainerWidthTablet'           => 600,
				'popupContainerWidthMobile'           => 600,
				'popupHeightType'                     => 'auto',
				'popupHeightCustom'                   => 500,
				'popupHeightCustomTablet'             => 500,
				'popupHeightCustomMobile'             => 500,
				'popupPaddingTop'                     => 20,
				'popupPaddingTopTablet'               => 15,
				'popupPaddingTopMobile'               => 10,
				'popupPaddingBottom'                  => 20,
				'popupPaddingBottomTablet'            => 15,
				'popupPaddingBottomMobile'            => 10,
				'popupPaddingLeft'                    => 20,
				'popupPaddingLeftTablet'              => 15,
				'popupPaddingLeftMobile'              => 10,
				'popupPaddingRight'                   => 20,
				'popupPaddingRightTablet'             => 15,
				'popupPaddingRightMobile'             => 10,
				'popupScreenType'                     => 'center center',
				'popupScreenTypeMobile'               => 'center center',
				'popupScreenTypeTablet'               => 'center center',
				'popupTrigger'                        => 'load',
				'popupTriggerDelay'                   => 1,
				'popupToggleCloseBtn'                 => true,
				'popupToggleCloseBtnPosition'         => "flex-end",
				'popupBgType'                         => "color",
				'popupGradient'                       => "linear-gradient(135deg,#12c2e9 0%,#c471ed 50%,#f64f59 100%)",
				'popupBgColor'                        => "#fff",
				'popupCloseBtnColor'                  => "#000",
				'popupOverlayColor'                   => "#10659C",
				'popupOverlayOpacity'                 => 30,
				'popupBlockBorderStyle'               => "solid",
				'popupBlockBorderWidth'               => 1,
				'popupBlockBorderRadius'              => 2,
				'popupBlockBorderColor'               => "black",
				'popupTriggerType'                    => "button",
				'popupTriggerAlign'                   => "left",
				'popupTriggerAlignTablet'             => "left",
				'popupTriggerAlignMobile'             => "left",
				'popupButtonPreset'                   => "",
				'popupIconTrigger'                    => "fa fa-angellist",
				'popupImageTrigger'                   => "",
				'popupTextTrigger'                    => "Click Here",
				'popupButtonHoverState'               => false,
				'popupButtonColor'                    => "#fff",
				'popupButtonBGColor'                  => "#10659C",
				'popupButtonBGTransparent'            => "transparent",
				'popupButtonBGGradient'               => "linear-gradient(135deg,#12c2e9 0%,#c471ed 50%,#f64f59 100%)",
				'popupButtonHoverColor'               => "#fff",
				'popupButtonBGHoverColor'             => "#10659C",
				'popupButtonHoverBGGradient'          => "linear-gradient(135deg,#12c2e9 0%,#c471ed 50%,#f64f59 100%)",
				'popupButtonBGState'                  => "solid",
				'popupButtonBGHoverState'             => "solid",
				'popupButtonTypographyFontFamily'     => "",
				'popupButtonTypographyFontSize'       => "",
				'popupButtonTypographyFontSizeMobile' => "",
				'popupButtonTypographyFontSizeTablet' => "",
				'popupButtonTypographyFontWeight'     => "600",
				'popupButtonTypographyLineHeight'     => 1,
				'popupButtonTypographyLetterSpacing'  => 0,
				'popupButtonBorderStyle'              => "solid",
				'popupButtonBorderWidth'              => 1,
				'popupButtonBorderRadius'             => 2,
				'popupButtonBorderColor'              => "black",
				'popupButtonBorderHoverColor'         => "",
				'popupTextColor'                      => "black",
				'popupTextTypographyFontFamily'       => "",
				'popupTextTypographyFontSize'         => "",
				'popupTextTypographyFontSizeMobile'   => "",
				'popupTextTypographyFontSizeTablet'   => "",
				'popupTextTypographyFontWeight'       => "600",
				'popupTextTypographyLineHeight'       => 1,
				'popupTextTypographyLetterSpacing'    => 0,
				'popupIconTriggerSize'                => 30,
				'popupIconTriggerColor'               => "#000",
				'popupImageTriggerWidth'              => 350,
				'popupImageTriggerWidthTablet'        => 350,
				'popupImageTriggerWidthMobile'        => 350,
				'popupImageTriggerBorderRadius'       => 0,
				'popupButtonText'                     => "Click Here",
				'popupButtonPaddingTop'               => 14,
				'popupButtonPaddingTopTablet'         => 14,
				'popupButtonPaddingTopMobile'         => 14,
				'popupButtonPaddingBottom'            => 14,
				'popupButtonPaddingBottomTablet'      => 14,
				'popupButtonPaddingBottomMobile'      => 14,
				'popupButtonPaddingLeft'              => 32,
				'popupButtonPaddingLeftTablet'        => 32,
				'popupButtonPaddingLeftMobile'        => 32,
				'popupButtonPaddingRight'             => 32,
				'popupButtonPaddingRightTablet'       => 32,
				'popupButtonPaddingRightMobile'       => 32,
			);
		}

		/**
		 * Get Form CSS
		 *
		 * @param array  $attr The block attributes.
		 * @param string $id The selector ID.
		 * @return array Styles.
		 */
		public static function get_responsive_block_form_css( $attr, $id ) {
			$defaults = self::get_responsive_block_form_block_default_attributes();
			$attr     = array_merge( $defaults, (array) $attr );

			$mobile_selectors = array();
			$tablet_selectors = array();

			$selectors        = array();

			$selectors        = array(
				''                                                              => array(),
				' .responsive-block-editor-addons-form-input'                   => array(
				    "margin-bottom" => self::get_css_value( $attr['formFieldInputGap'], 'px' ),
				),
				' .responsive-block-editor-addons-form-input-label'             => array(
					"font-size"     => $attr['formLabelSize'],
					"margin-bottom" => self::get_css_value( $attr['formLabelInputGap'], 'px' ),
				),
				' .responsive-block-editor-addons-form-input__label'            => array(
					"color" => $attr['formLabelColor'],
				),
				' .responsive-block-editor-addons-form-input__required'         => array(
					"color" => $attr['formRequiredLabelColor'] === null || $attr['formRequiredLabelColor'] === '' ? '#ff0000' : $attr['formRequiredLabelColor'],
				),
				' .responsive-block-editor-addons-form-input__input'            => array(
					"font-size"                  => $attr['formInputSize'],
					"color"                      => $attr['formInputTextColor'],
					"background-color"           => $attr['formInputBGColor'],
					"border-color"               => $attr['formBorderColor'] === null || $attr['formBorderColor'] === '' ? '#111111': $attr['formBorderColor'],
					"border-top-left-radius"     => $attr['formBorderRadius']['top'],
					"border-top-right-radius"    => $attr['formBorderRadius']['right'],
					"border-bottom-left-radius"  => $attr['formBorderRadius']['left'],
					"border-bottom-right-radius" => $attr['formBorderRadius']['right'],
					"border-top-width"           => $attr['formBorderWidth']['top'],
					"border-bottom-width"        => $attr['formBorderWidth']['bottom'],
					"border-left-width"          => $attr['formBorderWidth']['left'],
					"border-right-width"         => $attr['formBorderWidth']['right'],
				),
				' .responsive-block-editor-addons-form-input__text'             => array(
					"padding-top"    => $attr['inputFieldPadding']['top'],
					"padding-bottom" => $attr['inputFieldPadding']['bottom'],
					"padding-left"   => $attr['inputFieldPadding']['left'],
					"padding-right"  => $attr['inputFieldPadding']['right'],
				),
				' .responsive-block-editor-addons-form-input__helper'           => array(
					"color"     => $attr['formHelperLabelColor'],
      				"font-size" => $attr['formHelperTextSize'],
				),
				' .responsive-block-editor-addons-form-submit-button-container' => array(
					"justify-content" => $attr['formButtonAlign'],
      				"margin-top"      => self::get_css_value( $attr['formFieldInputGap'], "px"),
				),
				' .responsive-block-editor-addons-form-submit-button'           => array(
					"color"                      => $attr['formButtonLabelColor'],
					"background-color"           => $attr['formButtonLabelBGColor'],
					"padding-top"                => $attr['formButtonPadding']['top'],
					"padding-bottom"             => $attr['formButtonPadding']['bottom'],
					"padding-left"               => $attr['formButtonPadding']['left'],
					"padding-right"              => $attr['formButtonPadding']['right'],
					"border-top-left-radius"     => $attr['formButtonBorderRadius']['top'],
					"border-top-right-radius"    => $attr['formButtonBorderRadius']['right'],
					"border-bottom-left-radius"  => $attr['formButtonBorderRadius']['left'],
					"border-bottom-right-radius" => $attr['formButtonBorderRadius']['right'],
				),
				' .responsive-block-editor-addons-form-submit-button-spinner::before'                             => array(
					"border-color" => $attr['formButtonLabelColor'] . '!important',
				),
				' .responsive-block-editor-addons-form-submit-button:hover'     => array(
					"color"            => $attr['formButtonLabelHoverColor'],
      				"background-color" => $attr['formButtonLabelHoverBGColor'],
				),			
				' .responsive-block-editor-addons-form-submit-success-message'  => array(
					"color"     => $attr['formSuccessMessageColor'] === null || $attr['formSuccessMessageColor'] === '' ? '#008000' : $attr['formSuccessMessageColor'],
      				"font-size" => $attr['formSuccessErrorMessageSize'],
				),			
				' .responsive-block-editor-addons-form-submit-error-message'    => array(
					"color"     => $attr['formErrorMessageColor'] === null || $attr['formErrorMessageColor'] === '' ? '#FF0000' : $attr['formErrorMessageColor'],
      				"font-size" =>  $attr['formSuccessErrorMessageSize'],
				),			
			);
			$mobile_selectors = array(
				''                                                             => array(),
				' .responsive-block-editor-addons-form-input__text'            => array(
					"padding-top"    => $attr['inputFieldPaddingMobile']['top'],
					"padding-bottom" => $attr['inputFieldPaddingMobile']['bottom'],
					"padding-left"   => $attr['inputFieldPaddingMobile']['left'],
					"padding-right"  => $attr['inputFieldPaddingMobile']['right'],
				),
				' .responsive-block-editor-addons-form-submit-button-container' => array(
					"justify-content" => $attr['formButtonAlignMobile'],
				),
				' .responsive-block-editor-addons-form-submit-button'           => array(
					"padding-top"    => $attr['formButtonPaddingMobile']['top'],
					"padding-bottom" => $attr['formButtonPaddingMobile']['bottom'],
					"padding-left"   => $attr['formButtonPaddingMobile']['left'],
					"padding-right"  => $attr['formButtonPaddingMobile']['right'],
				),
			);

			$tablet_selectors = array(
				''                                                              => array(),
				' .responsive-block-editor-addons-form-input__text'             => array(
					"padding-top"    => $attr['inputFieldPaddingTablet']['top'],
					"padding-bottom" => $attr['inputFieldPaddingTablet']['bottom'],
					"padding-left"   => $attr['inputFieldPaddingTablet']['left'],
					"padding-right"  => $attr['inputFieldPaddingTablet']['right'],
				),
				' .responsive-block-editor-addons-form-submit-button-container' => array(
					"justify-content" => $attr['formButtonAlignTablet'],
				),
				' .responsive-block-editor-addons-form-submit-button'           => array(
					"padding-top"    => $attr['formButtonPaddingTablet']['top'],
					"padding-bottom" => $attr['formButtonPaddingTablet']['bottom'],
					"padding-left"   => $attr['formButtonPaddingTablet']['left'],
					"padding-right"  => $attr['formButtonPaddingTablet']['right'],
				),
			);

			$combined_selectors = array(
				'desktop' => $selectors,
				'tablet'  => $tablet_selectors,
				'mobile'  => $mobile_selectors,
			);

			$id  = '.responsive-block-editor-addons-block-form.block-' . $id;
			$css = Responsive_Block_Editor_Addons_Frontend_Styles_Helper::responsive_block_editor_addons_generate_all_css( $combined_selectors, $id );
			return $css;
		}

		/**
		 * Get Defaults for Form block.
		 *
		 * @return array
		 */
		public static function get_responsive_block_form_block_default_attributes() {
			return array(
				'block_id'                    => '',
				'isFormVariantSelected'       => false,
				'formSubmitBtnLabel'          => __( 'Submit', 'responsive-block-editor-addons'),
				'formEmailTo'                 => '',
				'formEmailSubject'            => '',
				'formEmailSubject'            => '',
				'formSuccessMessage'          => __( "Success", "responsive-block-editor-addons" ),
				'formErrorMessage'            => __( "Error. Please try again.", "responsive-block-editor-addons" ),
				'formLabelSize'               => '16px',
				'formLabelInputGap'           => 10,
				'formInputSize'               => '16px',
				'formFieldInputGap'           => 16,
				'inputFieldPadding'           => array( 'top' => '8px', 'bottom' => '8px', 'left' => '8px', 'right' => '8px' ),
				'inputFieldPaddingTablet'     => array( 'top' => '6px', 'bottom' => '6px', 'left' => '6px', 'right' => '6px' ),
				'inputFieldPaddingMobile'     => array( 'top' => '4px', 'bottom' => '4px', 'left' => '4px', 'right' => '4px' ),
				'formButtonLabelColor'        => '#f9f9f9',
				'formButtonLabelBGColor'      => '#111111',
				'formButtonLabelHoverColor'   => '',
				'formButtonLabelHoverBGColor' => '',
				'formButtonPadding'           => array( 'top' => '10px', 'bottom' => '10px', 'left' => '20px', 'right' => '20px' ),
				'formButtonPaddingTablet'     => array( 'top' => '8px', 'bottom' => '8px', 'left' => '16px', 'right' => '16px' ),
				'formButtonPaddingMobile'     => array( 'top' => '6px', 'bottom' => '6px', 'left' => '12px', 'right' => '12px' ),
				'formButtonBorderRadius'      => array( 'top' => '4px', 'bottom' => '4px', 'left' => '4px', 'right' => '4px' ),
				'formButtonAlign'             => 'left',
				'formButtonAlignTablet'       => 'left',
				'formButtonAlignMobile'       => 'left',
				'formLabelColor'              => '',
				'formInputTextColor'          => '',
				'formInputBGColor'            => '',
				'formBorderColor'             => '',
				'formHelperLabelColor'        => '',
				'formRequiredLabelColor'      => '',
				'formSuccessMessageColor'     => '',
				'formErrorMessageColor'       => '',
				'formBorderRadius'            => array( 'top' => '4px', 'bottom' => '4px', 'left' => '4px', 'right' => '4px' ),
				'formBorderWidth'             => array( 'top' => '1px', 'bottom' => '1px', 'left' => '1px', 'right' => '1px' ),
				'formHelperTextSize'          => '14px',
				'formSuccessErrorMessageSize' => '16px',
			);
		}

		/**
		 * Get Form Input CSS
		 *
		 * @param array  $attr The block attributes.
		 * @param string $id The selector ID.
		 * @return array Styles.
		 */
		public static function get_responsive_block_form_input_css( $attr, $id ) {
			$defaults = self::get_responsive_block_form_input_block_default_attributes();
			$attr     = array_merge( $defaults, (array) $attr );

			$mobile_selectors = array();
			$tablet_selectors = array();

			$selectors        = array();

			$selectors        = array(
				''                                                             => array(),
				' .responsive-block-editor-addons-form-input__label'            => array(
				    'color' => $attr['formInputLabelColor'],
				),
				' .responsive-block-editor-addons-form-input__input'           => array(
					'width' => $attr['formInputWidth'] . '%',
				),
				' .responsive-block-editor-addons-form-input-checkbox-wrapper' => array(
					'display'   => $attr['formInputInline'] ? 'flex' : 'block',
					"flex-wrap" => "wrap",
					"gap"       => "8px",
				),
			);
			$mobile_selectors = array(
				'' => array(),
			);

			$tablet_selectors = array(
				'' => array(),
			);

			$combined_selectors = array(
				'desktop' => $selectors,
				'tablet'  => $tablet_selectors,
				'mobile'  => $mobile_selectors,
			);

			$id  = '.responsive-block-editor-addons-block-form-input.block-' . $id;
			$css = Responsive_Block_Editor_Addons_Frontend_Styles_Helper::responsive_block_editor_addons_generate_all_css( $combined_selectors, $id );
			return $css;
		}

		/**
		 * Get Defaults for Form block.
		 *
		 * @return array
		 */
		public static function get_responsive_block_form_input_block_default_attributes() {
			return array(
				'block_id'              => '',
				'formInputFieldType'    => 'text',
				'formInputFieldLabel'   => 'Name',
				'formInputHideLabel'    => false,
				'formInputWidth'        => 100,
				'formInputPlaceholder'  => '',
				'formInputHelpText'     => '',
				'formInputRequired'     => true,
				'formInputDefaultValue' => '',
				'formInputLabelColor'   => '',
				'formInputInline'       => false,
				'formCheckBoxOptions'   => array(),
			);
		}

		/**
		 * Generate gradient effect
		 *
		 * @param string $color1  primary color.
		 * @param string $color2  secondary color.
		 * @param string $gradient_direction  gradient direction.
		 * @param string $color_location1  primary color location.
		 * @param string $color_location2  secondary color location.
		 */
		public static function generate_background_image_effect(
			$color1,
			$color2,
			$gradient_direction,
			$color_location1,
			$color_location2
		) {
			$css =
				'linear-gradient(' .
				$gradient_direction .
				'deg, ' .
				$color1 .
				' ' .
				$color_location1 .
				'%,' .
				$color2 .
				' ' .
				$color_location2 .
				'%)';

			return $css;
		}

		/**
		 * Get rgb value from hex value
		 *
		 * @param string $hex  color value.
		 * @param string $alpha  opacity.
		 */
		public static function hex_to_rgb( $hex, $alpha = false ) {
			$hex      = str_replace( '#', '', $hex );
			$length   = strlen( $hex );
			$rgb['r'] = hexdec( 6 === $length ? substr( $hex, 0, 2 ) : ( 3 === $length ? str_repeat( substr( $hex, 0, 1 ), 2 ) : 0 ) );
			$rgb['g'] = hexdec( 6 === $length ? substr( $hex, 2, 2 ) : ( 3 === $length ? str_repeat( substr( $hex, 1, 1 ), 2 ) : 0 ) );
			$rgb['b'] = hexdec( 6 === $length ? substr( $hex, 4, 2 ) : ( 3 === $length ? str_repeat( substr( $hex, 2, 1 ), 2 ) : 0 ) );
			if ( $alpha ) {
				$rgb['a'] = $alpha;
			} else {
				$rgb['a'] = '0.0';
			}
			return implode( array_keys( $rgb ) ) . '(' . implode( ', ', $rgb ) . ')';
		}

		/**
		 * Get CSS value
		 *
		 * @param string $value  CSS value.
		 * @param string $unit  CSS unit.
		 */
		public static function get_css_value( $value = '', $unit = '' ) {

			if ( '' == $value ) { // phpcs:ignore WordPress.PHP.StrictComparisons.LooseComparison
				return $value;
			}

			$css_val = '';

			if ( 0 === $value ) {
				$css_val = 0;
			}

			if ( ! empty( $value ) ) {
				$css_val = esc_attr( $value ) . $unit;
			}

			return $css_val;
		}
	}
}
