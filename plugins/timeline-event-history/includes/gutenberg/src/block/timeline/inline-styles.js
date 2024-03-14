/**
 * Returns Dynamic Generated CSS
 */

import generateCSS from "../../../dist/blocks/controls/generateCSS"
import generateCSSUnit from "../../../dist/blocks/controls/generateCSSUnit"

function contentTimelineStyle(props) {
	const {
		dateBottomspace,
		backgroundColor,
		separatorColor,
		separatorFillColor,
		separatorBg,
		separatorBorder,
		borderFocus,
		verticalSpace,
		horizontalSpace,
		separatorwidth,
		borderwidth,
		connectorBgsize,
		borderRadius,
		bgPadding,
		width,
		readMoreText,
		icon,
		iconColor,
		dateFontsizeType,
		dateFontsize,
		dateFontsizeTablet,
		dateFontsizeMobile,
		dateFontFamily,
		dateFontWeight,
		dateFontSubset,
		dateLineHeightType,
		dateLineHeight,
		dateLineHeightTablet,
		dateLineHeightMobile,
		dateLoadGoogleFonts,
		dateColor,
		iconSize,
		iconFocus,
		iconBgFocus,
		block_id,
		headFontSizeType,
		headFontSize,
		headFontSizeTablet,
		headFontSizeMobile,
		headFontFamily,
		headFontWeight,
		headFontSubset,
		headLineHeightType,
		headLineHeight,
		headLineHeightTablet,
		headLineHeightMobile,
		headLoadGoogleFonts,
		align,
		accentColor,
		headingColor,
		headSpace,
		subHeadFontSizeType,
		subHeadFontSize,
		subHeadFontSizeTablet,
		subHeadFontSizeMobile,
		subHeadFontFamily,
		subHeadFontWeight,
		subHeadFontSubset,
		subHeadLineHeightType,
		subHeadLineHeight,
		subHeadLineHeightTablet,
		subHeadLineHeightMobile,
		subHeadLoadGoogleFonts,
		subHeadingColor,
		authorSpace,
		authorColor,
		authorFontSizeType,
		authorFontSize,
		authorFontSizeTablet,
		authorFontSizeMobile,
		authorFontFamily,
		authorFontWeight,
		authorFontSubset,
		authorLineHeightType,
		authorLineHeight,
		authorLineHeightTablet,
		authorLineHeightMobile,
		authorLoadGoogleFonts,
		ctaColor,
		ctaFontSizeType,
		ctaFontSize,
		ctaFontSizeTablet,
		ctaFontSizeMobile,
		ctaFontFamily,
		ctaFontWeight,
		ctaFontSubset,
		ctaLineHeightType,
		ctaLineHeight,
		ctaLineHeightTablet,
		ctaLineHeightMobile,
		ctaLoadGoogleFonts,
		ctaBackground,
	} = props.attributes

	if (props.clientId) {
		var clientId = props.clientId
	} else {
		var clientId = block_id
	}


	var resp_selectors = "left"

	var selectors = {
		" .main-timeline .timeline-content::before , .main-timeline .year, .main-timeline .circle::before, .main-timeline .icon:before, .main-timeline::before": {
			"background-color": accentColor,
		},
		" .main-timeline .title, .main-timeline .circle span svg": {
			"color": accentColor,
			"fill": accentColor,
		},
		" .main-timeline .circle, .main-timeline .circle span, .main-timeline .timeline:first-child::before, .main-timeline .icon, .main-timeline .timeline:last-child::before": {
			"border-color": accentColor,
		},
		" .main-timeline .year": {
			"color": dateColor,
		},
		" .main-timeline .title": {
			"color": headingColor,
		},
		" .main-timeline .circle span svg": {
			"fill": iconColor,
		},
		" .main-timeline .description": {
			"color": subHeadingColor,
		},
	}

	/* Generate Responsive CSS for timeline */
	var tablet_selectors = {
		" .uagb-timeline__date-hide.uagb-timeline__date-inner": {
			"font-size": generateCSSUnit(dateFontsizeTablet, dateFontsizeType),
			"line-height": generateCSSUnit(dateLineHeightTablet, dateLineHeightType),
		},
		" .uagb-timeline__date-new": {
			"font-size": generateCSSUnit(dateFontsizeTablet, dateFontsizeType),
			"line-height": generateCSSUnit(dateLineHeightTablet, dateLineHeightType),
		},
		" .uagb-timeline__heading": {
			"font-size": generateCSSUnit(headFontSizeTablet, headFontSizeType),
			"line-height": generateCSSUnit(headLineHeightTablet, headLineHeightType),
		},
		" .uagb-timeline__heading a": {
			"font-size": generateCSSUnit(headFontSizeTablet, headFontSizeType),
			"line-height": generateCSSUnit(headLineHeightTablet, headLineHeightType),
		},
		" .uagb-timeline-desc-content": {
			"font-size": generateCSSUnit(subHeadFontSizeTablet, subHeadFontSizeType),
			"line-height": generateCSSUnit(subHeadLineHeightTablet, subHeadLineHeightType),
		},
		" .uagb-timeline__center-block .uagb-timeline__marker": {
			"margin-left": 0,
			"margin-right": 0,
		},
		" .uagb-timeline__center-block.uagb-timeline__responsive-tablet .uagb-timeline__heading": {
			"text-align": resp_selectors,
		},
		" .uagb-timeline__center-block.uagb-timeline__responsive-tablet .uagb-timeline-desc-content": {
			"text-align": resp_selectors,
		},
		" .uagb-timeline__center-block.uagb-timeline__responsive-tablet .uagb-timeline__events-new": {
			"text-align": resp_selectors
		},
		" .uagb-timeline__center-block.uagb-timeline__responsive-tablet .uagb-timeline__date-inner": {
			"text-align": resp_selectors
		},
		" .uagb-timeline__center-block.uagb-timeline__responsive-tablet .uagb-timeline__date-hide.uagb-timeline__date-inner": {
			"text-align": resp_selectors,
		},
		" .uagb-timeline__center-block.uagb-timeline__responsive-tablet .uagb-timeline__day-right .uagb-timeline__arrow:after": {
			"border-right-color": backgroundColor,
		},
		" .uagb-timeline__center-block.uagb-timeline__responsive-tablet .uagb-timeline__line": {
			"left": "calc( " + connectorBgsize + "px / 2 )",
		},
		" .uagb-timeline__center-block.uagb-timeline__responsive-tablet .uagb-timeline__author": {
			"text-align": resp_selectors,
		},
		" .uagb-timeline__center-block.uagb-timeline__responsive-tablet .uagb-timeline__link_parent": {
			"text-align": resp_selectors,
		},
		" .uagb-timeline__center-block.uagb-timeline__responsive-tablet .uagb-timeline__image a": {
			"text-align": resp_selectors,
		},

		// CTA AUTHOR.
		" .uagb-timeline__author .dashicons-admin-users": {
			"font-size": generateCSSUnit(authorFontSizeTablet, authorFontSizeType),
			"line-height": generateCSSUnit(authorLineHeightTablet, authorLineHeightType),
		},
		" .uagb-timeline__author-link": {
			"font-size": generateCSSUnit(authorFontSizeTablet, authorFontSizeType),
			"line-height": generateCSSUnit(authorLineHeightTablet, authorLineHeightType),
		},
		" .uagb-timeline__link": {
			"font-size": generateCSSUnit(ctaFontSizeTablet, ctaFontSizeType),
			"line-height": generateCSSUnit(ctaLineHeightTablet, ctaLineHeightType),
		}
	}

	var mobile_selectors = {
		" .uagb-timeline__date-hide.uagb-timeline__date-inner": {
			"font-size": generateCSSUnit(dateFontsizeMobile, dateFontsizeType),
			"line-height": generateCSSUnit(dateLineHeightMobile, dateLineHeightType),
		},
		" .uagb-timeline__date-new": {
			"font-size": generateCSSUnit(dateFontsizeMobile, dateFontsizeType),
			"line-height": generateCSSUnit(dateLineHeightMobile, dateLineHeightType),
		},
		" .uagb-timeline__heading": {
			"font-size": generateCSSUnit(headFontSizeMobile, headFontSizeType),
			"line-height": generateCSSUnit(headLineHeightMobile, headLineHeightType),
		},
		" .uagb-timeline__heading a": {
			"font-size": generateCSSUnit(headFontSizeMobile, headFontSizeType),
			"line-height": generateCSSUnit(headLineHeightMobile, headLineHeightType),
		},
		" .uagb-timeline-desc-content": {
			"font-size": generateCSSUnit(subHeadFontSizeMobile, subHeadFontSizeType),
			"line-height": generateCSSUnit(subHeadLineHeightMobile, subHeadLineHeightType),
		},
		" .uagb-timeline__center-block .uagb-timeline__marker": {
			"margin-left": 0,
			"margin-right": 0,
		},
		" .uagb-timeline__center-block .uagb-timeline__day-new.uagb-timeline__day-left": {
			"margin-left": generateCSSUnit(horizontalSpace, "px"),
		},
		" .uagb-timeline__center-block .uagb-timeline__day-new.uagb-timeline__day-right": {
			"margin-left": generateCSSUnit(horizontalSpace, "px"),
		},
		" .uagb-timeline__center-block.uagb-timeline__responsive-mobile .uagb-timeline__heading": {
			"text-align": resp_selectors,
		},
		" .uagb-timeline__center-block.uagb-timeline__responsive-mobile .uagb-timeline-desc-content": {
			"text-align": resp_selectors,
		},
		" .uagb-timeline__center-block.uagb-timeline__responsive-mobile .uagb-timeline__events-new": {
			"text-align": resp_selectors
		},
		" .uagb-timeline__center-block.uagb-timeline__responsive-mobile .uagb-timeline__date-inner": {
			"text-align": resp_selectors
		},
		" .uagb-timeline__center-block.uagb-timeline__responsive-mobile .uagb-timeline__date-hide.uagb-timeline__date-inner": {
			"text-align": resp_selectors,
		},
		" .uagb-timeline__center-block.uagb-timeline__responsive-mobile .uagb-timeline__day-right .uagb-timeline__arrow:after": {
			"border-right-color": backgroundColor,
		},
		" .uagb-timeline__center-block.uagb-timeline__responsive-mobile .uagb-timeline__line": {
			"left": "calc( " + connectorBgsize + "px / 2 )",
		},
		" .uagb-timeline__center-block.uagb-timeline__responsive-mobile .uagb-timeline__author": {
			"text-align": resp_selectors,
		},
		" .uagb-timeline__center-block.uagb-timeline__responsive-mobile .uagb-timeline__link_parent": {
			"text-align": resp_selectors,
		},
		" .uagb-timeline__center-block.uagb-timeline__responsive-mobile .uagb-timeline__image a": {
			"text-align": resp_selectors,
		},

		// CTA  AUthor
		" .uagb-timeline__author .dashicons-admin-users": {
			"font-size": generateCSSUnit(authorFontSizeMobile, authorFontSizeType),
			"line-height": generateCSSUnit(authorLineHeightMobile, authorLineHeightType),
		},
		" .uagb-timeline__author-link": {
			"font-size": generateCSSUnit(authorFontSizeMobile, authorFontSizeType),
			"line-height": generateCSSUnit(authorLineHeightMobile, authorLineHeightType),
		},
		" .uagb-timeline__link": {
			"font-size": generateCSSUnit(ctaFontSizeMobile, ctaFontSizeType),
			"line-height": generateCSSUnit(ctaLineHeightMobile, ctaLineHeightType),
		}
	}

	var styling_css = ""
	var id = `#twp-ctm-${clientId}`

	styling_css = generateCSS(selectors, id)

	styling_css += generateCSS(tablet_selectors, id, true, "tablet")

	styling_css += generateCSS(mobile_selectors, id, true, "mobile")

	return styling_css

}

export default contentTimelineStyle
