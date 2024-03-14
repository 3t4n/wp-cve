/**
 * Set inline styles.
 * @param  {object} props - The block object.
 * @return {object} The inline background type CSS.
 */

import { generateCSS } from "../helpers/generateCSS";
import {
	cssBoxShadow,
	fontWeightVariation,
	fontStyle,
} from "../ui-components/helpers/get-label";

export default (attributes, id, clientId) => {
	const {
		prosIconSize,
		consIconSize,
		boxShadow,
		alignment,
		alignmentThree,
		titleTypography,
		listTypography,
		prosTextColor,
		prosTextColorThree,
		prosBgType,
		prosBgColor,
		prosBgGradient,
		prosListColor,
		consTextColor,
		consTextColorThree,
		consBgType,
		consBgColor,
		consBgGradient,
		consListColor,
		prosListBgType,
		prosListBgColor,
		prosListBgGradient,
		consListBgType,
		consListBgColor,
		consListBgGradient,
		contentType,
		consIconColor,
		listType,
		unorderedType,
		prosIconColor,
		titleMargin,
		titlePadding,
		contentMargin,
		contentPadding,
		layoutStyle,
		prosBorder,
		prosBorderThree,
		titleBorderWidthOne,
		titleBorderRadiusOne,
		titleBorderWidthTwo,
		titleBorderRadiusTwo,
		titleBorderWidthThree,
		titleBorderRadiusThree,
		titleBorderWidthFour,
		titleBorderRadiusFour,
		prosContentBorder,
		prosContentBorderThree,
		contentBorderWidthOne,
		contentBorderRadiusOne,
		contentBorderWidthTwo,
		contentBorderRadiusTwo,
		contentBorderWidthThree,
		contentBorderRadiusThree,
		contentBorderWidthFour,
		contentBorderRadiusFour,
		consBorder,
		consBorderThree,
		consContentBorder,
		consContentBorderThree,
		contentAlignment,
		margin,
		padding,
	} = attributes;

	var selectors = {};
	var mobile_selectors = {};
	var tablet_selectors = {};

	selectors = {
		" .affx-pros-cons-inner-wrapper.layout-type-1": {
			"box-shadow": cssBoxShadow(boxShadow),
		},
		" .affx-pros-cons-inner-wrapper.layout-type-2 .affx-pros-inner": {
			"box-shadow": cssBoxShadow(boxShadow),
		},
		" .affx-pros-cons-inner-wrapper.layout-type-2 .affx-cons-inner": {
			"box-shadow": cssBoxShadow(boxShadow),
		},
		" .affx-pros-cons-inner-wrapper.layout-type-3 .affx-pros-inner": {
			"box-shadow": cssBoxShadow(boxShadow),
		},
		" .affx-pros-cons-inner-wrapper.layout-type-3 .affx-cons-inner": {
			"box-shadow": cssBoxShadow(boxShadow),
		},
		" .affx-pros-cons-inner-wrapper": {
			"margin-top": margin.desktop.top,
			"margin-left": margin.desktop.left,
			"margin-right": margin.desktop.right,
			"margin-bottom": margin.desktop.bottom,
			"padding-top": padding.desktop.top,
			"padding-left": padding.desktop.left,
			"padding-right": padding.desktop.right,
			"padding-bottom": padding.desktop.bottom,
		},
		" .pros-icon-title-wrap .affiliatex-block-pros": {
			"margin-top": titleMargin.desktop.top,
			"margin-left": titleMargin.desktop.left,
			"margin-right": titleMargin.desktop.right,
			"margin-bottom": titleMargin.desktop.bottom,
			"padding-top": titlePadding.desktop.top,
			"padding-left": titlePadding.desktop.left,
			"padding-right": titlePadding.desktop.right,
			"padding-bottom": titlePadding.desktop.bottom,
		},
		" .cons-icon-title-wrap .affiliatex-block-cons": {
			"margin-top": titleMargin.desktop.top,
			"margin-left": titleMargin.desktop.left,
			"margin-right": titleMargin.desktop.right,
			"margin-bottom": titleMargin.desktop.bottom,
			"padding-top": titlePadding.desktop.top,
			"padding-left": titlePadding.desktop.left,
			"padding-right": titlePadding.desktop.right,
			"padding-bottom": titlePadding.desktop.bottom,
		},
		" .affx-pros-cons-inner-wrapper.layout-type-1 .pros-icon-title-wrap .affiliatex-block-pros": {
			"border-style": prosBorder.style,
			"border-width":
				titleBorderWidthOne.desktop.top +
				" " +
				titleBorderWidthOne.desktop.right +
				" " +
				titleBorderWidthOne.desktop.bottom +
				" " +
				titleBorderWidthOne.desktop.left,
			"border-color": prosBorder.color.color,
			"border-radius":
				titleBorderRadiusOne.desktop.top +
				" " +
				titleBorderRadiusOne.desktop.right +
				" " +
				titleBorderRadiusOne.desktop.bottom +
				" " +
				titleBorderRadiusOne.desktop.left,
		},
		" .affx-pros-cons-inner-wrapper.layout-type-1 .affiliatex-pros": {
			"border-style": prosContentBorder.style,
			"border-width":
				contentBorderWidthOne.desktop.top +
				" " +
				contentBorderWidthOne.desktop.right +
				" " +
				contentBorderWidthOne.desktop.bottom +
				" " +
				contentBorderWidthOne.desktop.left,
			"border-color": prosContentBorder.color.color,
			"border-radius":
				contentBorderRadiusOne.desktop.top +
				" " +
				contentBorderRadiusOne.desktop.right +
				" " +
				contentBorderRadiusOne.desktop.bottom +
				" " +
				contentBorderRadiusOne.desktop.left,
		},
		" .affx-pros-cons-inner-wrapper.layout-type-2 .pros-icon-title-wrap .affiliatex-block-pros": {
			"border-style": prosBorder.style,
			"border-width":
				titleBorderWidthTwo.desktop.top +
				" " +
				titleBorderWidthTwo.desktop.right +
				" " +
				titleBorderWidthTwo.desktop.bottom +
				" " +
				titleBorderWidthTwo.desktop.left,
			"border-color": prosBorder.color.color,
			"border-radius":
				titleBorderRadiusTwo.desktop.top +
				" " +
				titleBorderRadiusTwo.desktop.right +
				" " +
				titleBorderRadiusTwo.desktop.bottom +
				" " +
				titleBorderRadiusTwo.desktop.left,
		},
		" .affx-pros-cons-inner-wrapper.layout-type-2 .affiliatex-pros": {
			"border-style": prosContentBorder.style,
			"border-width":
				contentBorderWidthTwo.desktop.top +
				" " +
				contentBorderWidthTwo.desktop.right +
				" " +
				contentBorderWidthTwo.desktop.bottom +
				" " +
				contentBorderWidthTwo.desktop.left,
			"border-color": prosContentBorder.color.color,
			"border-radius":
				contentBorderRadiusTwo.desktop.top +
				" " +
				contentBorderRadiusTwo.desktop.right +
				" " +
				contentBorderRadiusTwo.desktop.bottom +
				" " +
				contentBorderRadiusTwo.desktop.left,
		},
		" .affx-pros-cons-inner-wrapper.layout-type-4 .pros-icon-title-wrap .affiliatex-block-pros": {
			"border-style": prosBorder.style,
			"border-width":
				titleBorderWidthFour.desktop.top +
				" " +
				titleBorderWidthFour.desktop.right +
				" " +
				titleBorderWidthFour.desktop.bottom +
				" " +
				titleBorderWidthFour.desktop.left,
			"border-color": prosBorder.color.color,
			"border-radius":
				titleBorderRadiusFour.desktop.top +
				" " +
				titleBorderRadiusFour.desktop.right +
				" " +
				titleBorderRadiusFour.desktop.bottom +
				" " +
				titleBorderRadiusFour.desktop.left,
		},
		" .affx-pros-cons-inner-wrapper.layout-type-1 .cons-icon-title-wrap .affiliatex-block-cons": {
			"border-style": consBorder.style,
			"border-width":
				titleBorderWidthOne.desktop.top +
				" " +
				titleBorderWidthOne.desktop.right +
				" " +
				titleBorderWidthOne.desktop.bottom +
				" " +
				titleBorderWidthOne.desktop.left,
			"border-color": consBorder.color.color,
			"border-radius":
				titleBorderRadiusOne.desktop.top +
				" " +
				titleBorderRadiusOne.desktop.right +
				" " +
				titleBorderRadiusOne.desktop.bottom +
				" " +
				titleBorderRadiusOne.desktop.left,
		},
		" .affx-pros-cons-inner-wrapper.layout-type-1 .affiliatex-cons": {
			"border-style": consContentBorder.style,
			"border-width":
				contentBorderWidthOne.desktop.top +
				" " +
				contentBorderWidthOne.desktop.right +
				" " +
				contentBorderWidthOne.desktop.bottom +
				" " +
				contentBorderWidthOne.desktop.left,
			"border-color": consContentBorder.color.color,
			"border-radius":
				contentBorderRadiusOne.desktop.top +
				" " +
				contentBorderRadiusOne.desktop.right +
				" " +
				contentBorderRadiusOne.desktop.bottom +
				" " +
				contentBorderRadiusOne.desktop.left,
		},
		" .affx-pros-cons-inner-wrapper.layout-type-2 .cons-icon-title-wrap .affiliatex-block-cons": {
			"border-style": consBorder.style,
			"border-width":
				titleBorderWidthTwo.desktop.top +
				" " +
				titleBorderWidthTwo.desktop.right +
				" " +
				titleBorderWidthTwo.desktop.bottom +
				" " +
				titleBorderWidthTwo.desktop.left,
			"border-color": consBorder.color.color,
			"border-radius":
				titleBorderRadiusTwo.desktop.top +
				" " +
				titleBorderRadiusTwo.desktop.right +
				" " +
				titleBorderRadiusTwo.desktop.bottom +
				" " +
				titleBorderRadiusTwo.desktop.left,
		},
		" .affx-pros-cons-inner-wrapper.layout-type-2 .affiliatex-cons": {
			"border-style": consContentBorder.style,
			"border-width":
				contentBorderWidthTwo.desktop.top +
				" " +
				contentBorderWidthTwo.desktop.right +
				" " +
				contentBorderWidthTwo.desktop.bottom +
				" " +
				contentBorderWidthTwo.desktop.left,
			"border-color": consContentBorder.color.color,
			"border-radius":
				contentBorderRadiusTwo.desktop.top +
				" " +
				contentBorderRadiusTwo.desktop.right +
				" " +
				contentBorderRadiusTwo.desktop.bottom +
				" " +
				contentBorderRadiusTwo.desktop.left,
		},
		" .affx-pros-cons-inner-wrapper.layout-type-4 .cons-icon-title-wrap .affiliatex-block-cons": {
			"border-style": consBorder.style,
			"border-width":
				titleBorderWidthFour.desktop.top +
				" " +
				titleBorderWidthFour.desktop.right +
				" " +
				titleBorderWidthFour.desktop.bottom +
				" " +
				titleBorderWidthFour.desktop.left,
			"border-color": consBorder.color.color,
			"border-radius":
				titleBorderRadiusFour.desktop.top +
				" " +
				titleBorderRadiusFour.desktop.right +
				" " +
				titleBorderRadiusFour.desktop.bottom +
				" " +
				titleBorderRadiusFour.desktop.left,
		},
		" .affiliatex-block-cons": {
			"text-align": alignment,
		},
		" .affiliatex-block-cons .affiliatex-title": {
			"font-family": titleTypography.family,
			"font-weight": fontWeightVariation(titleTypography.variation),
			"font-style": fontStyle(titleTypography.variation),
			"font-size": titleTypography.size.desktop,
			"line-height": titleTypography["line-height"].desktop,
			"text-transform": titleTypography["text-transform"],
			"text-decoration": titleTypography["text-decoration"],
			"letter-spacing": titleTypography["letter-spacing"].desktop,
		},
		" .affiliatex-block-pros": {
			"text-align": alignment,
		},
		" .affiliatex-block-pros .affiliatex-title": {
			"font-family": titleTypography.family,
			"font-weight": fontWeightVariation(titleTypography.variation),
			"font-style": fontStyle(titleTypography.variation),
			"font-size": titleTypography.size.desktop,
			"line-height": titleTypography["line-height"].desktop,
			"text-transform": titleTypography["text-transform"],
			"text-decoration": titleTypography["text-decoration"],
			"letter-spacing": titleTypography["letter-spacing"].desktop,
		},
		" .affiliatex-content": {
			"margin-top": contentMargin.desktop.top,
			"margin-left": contentMargin.desktop.left,
			"margin-right": contentMargin.desktop.right,
			"margin-bottom": contentMargin.desktop.bottom,
			"padding-top": contentPadding.desktop.top,
			"padding-left": contentPadding.desktop.left,
			"padding-right": contentPadding.desktop.right,
			"padding-bottom": contentPadding.desktop.bottom,
		},
		" .affiliatex-list": {
			"margin-top": contentMargin.desktop.top,
			"margin-left": contentMargin.desktop.left,
			"margin-right": contentMargin.desktop.right,
			"margin-bottom": contentMargin.desktop.bottom,
			"padding-top": contentPadding.desktop.top,
			"padding-left": contentPadding.desktop.left,
			"padding-right": contentPadding.desktop.right,
			"padding-bottom": contentPadding.desktop.bottom,
		},
		" .affiliatex-cons": {
			"text-align": contentAlignment,
		},
		" .affiliatex-pros": {
			"text-align": contentAlignment,
		},
		" .affiliatex-cons p": {
			"font-family": listTypography.family,
			"font-weight": fontWeightVariation(listTypography.variation),
			"font-style": fontStyle(listTypography.variation),
			"font-size": listTypography.size.desktop,
			"line-height": listTypography["line-height"].desktop,
			"text-transform": listTypography["text-transform"],
			"text-decoration": listTypography["text-decoration"],
			"letter-spacing": listTypography["letter-spacing"].desktop,
			color: consListColor,
		},
		" .affiliatex-cons li": {
			"font-family": listTypography.family,
			"font-weight": fontWeightVariation(listTypography.variation),
			"font-style": fontStyle(listTypography.variation),
			"font-size": listTypography.size.desktop,
			"line-height": listTypography["line-height"].desktop,
			"text-transform": listTypography["text-transform"],
			"text-decoration": listTypography["text-decoration"],
			"letter-spacing": listTypography["letter-spacing"].desktop,
			color: consListColor,
			display: contentAlignment != "left" ? "block" : "flex",
		},
		" .affiliatex-pros p": {
			"font-family": listTypography.family,
			"font-weight": fontWeightVariation(listTypography.variation),
			"font-style": fontStyle(listTypography.variation),
			"font-size": listTypography.size.desktop,
			"line-height": listTypography["line-height"].desktop,
			"text-transform": listTypography["text-transform"],
			"text-decoration": listTypography["text-decoration"],
			"letter-spacing": listTypography["letter-spacing"].desktop,
			color: prosListColor,
		},
		" .affiliatex-pros li": {
			"font-family": listTypography.family,
			"font-weight": fontWeightVariation(listTypography.variation),
			"font-style": fontStyle(listTypography.variation),
			"font-size": listTypography.size.desktop,
			"line-height": listTypography["line-height"].desktop,
			"text-transform": listTypography["text-transform"],
			"text-decoration": listTypography["text-decoration"],
			"letter-spacing": listTypography["letter-spacing"].desktop,
			color: prosListColor,
			display: contentAlignment != "left" ? "block" : "flex",
		},
		" .affiliatex-block-pros .affiliatex-icon::before": {
			"font-size": prosIconSize + "px",
		},
		" .affiliatex-block-cons .affiliatex-icon::before": {
			"font-size": consIconSize + "px",
		},
		" .affiliatex-pros ul li::before": {
			color: prosIconColor,
		},
		" .affiliatex-pros li::marker": {
			color: prosIconColor,
		},
		" .affiliatex-pros ul.bullet li::before": {
			background: prosIconColor,
		},
		" .affiliatex-cons ul li::before": {
			color: consIconColor,
		},
		" .affiliatex-cons li::marker": {
			color: consIconColor,
		},
		" .affiliatex-cons ul.bullet li::before": {
			background: consIconColor,
		},

		" .affiliatex-pros ol li::before": {
			"border-color": prosIconColor,
			color: prosIconColor,
		},

		" .affiliatex-cons ol li::before": {
			"border-color": consIconColor,
			color: consIconColor,
		},

		" .affx-pros-cons-inner-wrapper.layout-type-3  .pros-icon-title-wrap": {
			"border-style": prosContentBorderThree.style,
			"border-width":
				contentBorderWidthThree.desktop.top +
				" " +
				contentBorderWidthThree.desktop.right +
				" " +
				"0" +
				" " +
				contentBorderWidthThree.desktop.left,
			"border-color": prosContentBorderThree.color.color,
			"border-radius":
				contentBorderRadiusThree.desktop.top +
				" " +
				contentBorderRadiusThree.desktop.right +
				" " +
				contentBorderRadiusThree.desktop.bottom +
				" " +
				contentBorderRadiusThree.desktop.left,
		},

		" .affx-pros-cons-inner-wrapper.layout-type-3  .cons-icon-title-wrap": {
			"border-style": consContentBorderThree.style,
			"border-width":
				contentBorderWidthThree.desktop.top +
				" " +
				contentBorderWidthThree.desktop.right +
				" " +
				"0" +
				" " +
				contentBorderWidthThree.desktop.left,
			"border-color": consContentBorderThree.color.color,
			"border-radius":
				contentBorderRadiusThree.desktop.top +
				" " +
				contentBorderRadiusThree.desktop.right +
				" " +
				contentBorderRadiusThree.desktop.bottom +
				" " +
				contentBorderRadiusThree.desktop.left,
		},

		" .affx-pros-cons-inner-wrapper.layout-type-3  .affiliatex-pros": {
			"border-style": prosContentBorderThree.style,
			"border-width":
				"0" +
				" " +
				contentBorderWidthThree.desktop.right +
				" " +
				contentBorderWidthThree.desktop.bottom +
				" " +
				contentBorderWidthThree.desktop.left,
			"border-color": prosContentBorderThree.color.color,
			"border-radius":
				contentBorderRadiusThree.desktop.top +
				" " +
				contentBorderRadiusThree.desktop.right +
				" " +
				contentBorderRadiusThree.desktop.bottom +
				" " +
				contentBorderRadiusThree.desktop.left,
		},

		" .affx-pros-cons-inner-wrapper.layout-type-3  .affiliatex-cons": {
			"border-style": consContentBorderThree.style,
			"border-width":
				"0" +
				" " +
				contentBorderWidthThree.desktop.right +
				" " +
				contentBorderWidthThree.desktop.bottom +
				" " +
				contentBorderWidthThree.desktop.left,
			"border-color": consContentBorderThree.color.color,
			"border-radius":
				contentBorderRadiusThree.desktop.top +
				" " +
				contentBorderRadiusThree.desktop.right +
				" " +
				contentBorderRadiusThree.desktop.bottom +
				" " +
				contentBorderRadiusThree.desktop.left,
		},

		" .affx-pros-cons-inner-wrapper.layout-type-3 .affiliatex-block-pros .affiliatex-title:before": {
			"border-style": prosBorderThree.style,
			"border-width":
				titleBorderWidthThree.desktop.top +
				" " +
				titleBorderWidthThree.desktop.right +
				" " +
				titleBorderWidthThree.desktop.bottom +
				" " +
				titleBorderWidthThree.desktop.left,
			"border-color": prosBorderThree.color.color,
			"border-radius":
				titleBorderRadiusThree.desktop.top +
				" " +
				titleBorderRadiusThree.desktop.right +
				" " +
				titleBorderRadiusThree.desktop.bottom +
				" " +
				titleBorderRadiusThree.desktop.left,
		},

		" .affx-pros-cons-inner-wrapper.layout-type-3 .affiliatex-block-cons .affiliatex-title:before": {
			"border-style": consBorderThree.style,
			"border-width":
				titleBorderWidthThree.desktop.top +
				" " +
				titleBorderWidthThree.desktop.right +
				" " +
				titleBorderWidthThree.desktop.bottom +
				" " +
				titleBorderWidthThree.desktop.left,
			"border-color": consBorderThree.color.color,
			"border-radius":
				titleBorderRadiusThree.desktop.top +
				" " +
				titleBorderRadiusThree.desktop.right +
				" " +
				titleBorderRadiusThree.desktop.bottom +
				" " +
				titleBorderRadiusThree.desktop.left,
		},

		" .affx-pros-cons-inner-wrapper.layout-type-3 .affiliatex-block-pros .pros-title-icon": {
			"justify-content": alignmentThree,
		},
		" .affx-pros-cons-inner-wrapper.layout-type-3 .affiliatex-block-cons .cons-title-icon": {
			"justify-content": alignmentThree,
		},

		" .affx-pros-cons-inner-wrapper.layout-type-3 .affiliatex-block-pros": {
			"align-items": alignmentThree,
			"margin-top": "0px",
			"margin-left": "0px",
			"margin-right": "0px",
			"margin-bottom": "0px",
			"padding-top": "0px",
			"padding-left": "10px",
			"padding-right": "10px",
			"padding-bottom": "0px",
		},

		" .affx-pros-cons-inner-wrapper.layout-type-3 .affiliatex-block-cons": {
			"align-items": alignmentThree,
			"margin-top": "0px",
			"margin-left": "0px",
			"margin-right": "0px",
			"margin-bottom": "0px",
			"padding-top": "0px",
			"padding-left": "10px",
			"padding-right": "10px",
			"padding-bottom": "0px",
		},

		" .affx-pros-cons-inner-wrapper.layout-type-4 .affx-pros-inner .affiliatex-pros": {
			background: "transparent",
			border: "none",
			"border-radius": "0",
		},

		" .affx-pros-cons-inner-wrapper.layout-type-4 .affx-cons-inner .affiliatex-cons": {
			background: "transparent",
			border: "none",
			"border-radius": "0",
		},

		" .affx-pros-cons-inner-wrapper.layout-type-4  .affiliatex-pros ul li": {
			"border-style": prosContentBorder.style,
			"border-width":
				contentBorderWidthFour.desktop.top +
				" " +
				contentBorderWidthFour.desktop.right +
				" " +
				contentBorderWidthFour.desktop.bottom +
				" " +
				contentBorderWidthFour.desktop.left,
			"border-color": prosContentBorder.color.color,
			"border-radius":
				contentBorderRadiusFour.desktop.top +
				" " +
				contentBorderRadiusFour.desktop.right +
				" " +
				contentBorderRadiusFour.desktop.bottom +
				" " +
				contentBorderRadiusFour.desktop.left,
		},

		" .affx-pros-cons-inner-wrapper.layout-type-4  .affiliatex-cons ul li": {
			"border-style": consContentBorder.style,
			"border-width":
				contentBorderWidthFour.desktop.top +
				" " +
				contentBorderWidthFour.desktop.right +
				" " +
				contentBorderWidthFour.desktop.bottom +
				" " +
				contentBorderWidthFour.desktop.left,
			"border-color": consContentBorder.color.color,
			"border-radius":
				contentBorderRadiusFour.desktop.top +
				" " +
				contentBorderRadiusFour.desktop.right +
				" " +
				contentBorderRadiusFour.desktop.bottom +
				" " +
				contentBorderRadiusFour.desktop.left,
		},

		" .affx-pros-cons-inner-wrapper.layout-type-4  .affiliatex-list": {
			padding: "0",
		},

		" .affx-pros-cons-inner-wrapper.layout-type-4  .affiliatex-content li": {
			"padding-top": contentPadding.desktop.top,
			"padding-left": contentPadding.desktop.left,
			"padding-right": contentPadding.desktop.right,
			"padding-bottom": contentPadding.desktop.bottom,
		},
		" .affx-pros-cons-inner-wrapper.layout-type-4  .affiliatex-list li": {
			"padding-top": contentPadding.desktop.top,
			"padding-left": contentPadding.desktop.left,
			"padding-right": contentPadding.desktop.right,
			"padding-bottom": contentPadding.desktop.bottom,
		},

		" .affx-pros-cons-inner-wrapper.layout-type-4  .affiliatex-pros p": {
			"border-style": prosContentBorder.style,
			"border-width":
				contentBorderWidthFour.desktop.top +
				" " +
				contentBorderWidthFour.desktop.right +
				" " +
				contentBorderWidthFour.desktop.bottom +
				" " +
				contentBorderWidthFour.desktop.left,
			"border-color": prosContentBorder.color.color,
			"border-radius":
				contentBorderRadiusFour.desktop.top +
				" " +
				contentBorderRadiusFour.desktop.right +
				" " +
				contentBorderRadiusFour.desktop.bottom +
				" " +
				contentBorderRadiusFour.desktop.left,
			"margin-top": "10px",
		},

		" .affx-pros-cons-inner-wrapper.layout-type-4  .affiliatex-cons p": {
			"border-style": consContentBorder.style,
			"border-width":
				contentBorderWidthFour.desktop.top +
				" " +
				contentBorderWidthFour.desktop.right +
				" " +
				contentBorderWidthFour.desktop.bottom +
				" " +
				contentBorderWidthFour.desktop.left,
			"border-color": consContentBorder.color.color,
			"border-radius":
				contentBorderRadiusFour.desktop.top +
				" " +
				contentBorderRadiusFour.desktop.right +
				" " +
				contentBorderRadiusFour.desktop.bottom +
				" " +
				contentBorderRadiusFour.desktop.left,
			"margin-top": "10px",
		},

		" .affx-pros-inner .affiliatex-pros": {},
		" .affx-cons-inner .affiliatex-cons": {},
		" .affx-pros-cons-inner-wrapper.layout-type-4  .affiliatex-pros li": {},
		" .affx-pros-cons-inner-wrapper.layout-type-4  .affiliatex-cons li": {},
		" .affiliatex-pros ul": {},
		" .affiliatex-cons ul": {},
	};

	selectors[" .affiliatex-block-pros"].background =
		prosBgType === "solid" ? prosBgColor : prosBgGradient.gradient;
	selectors[" .affiliatex-block-cons"].background =
		consBgType === "solid" ? consBgColor : consBgGradient.gradient;
	selectors[" .affx-pros-inner .affiliatex-pros"].background =
		prosListBgType === "solid"
			? prosListBgColor
			: prosListBgGradient.gradient;
	selectors[" .affx-cons-inner .affiliatex-cons"].background =
		consListBgType === "solid"
			? consListBgColor
			: consListBgGradient.gradient;
	selectors[
		" .affx-pros-cons-inner-wrapper.layout-type-3 .affiliatex-block-pros"
	].background =
		prosListBgType === "solid"
			? prosListBgColor
			: prosListBgGradient.gradient;
	selectors[
		" .affx-pros-cons-inner-wrapper.layout-type-3 .affiliatex-block-cons"
	].background =
		consListBgType === "solid"
			? consListBgColor
			: consListBgGradient.gradient;
	selectors[
		" .affx-pros-cons-inner-wrapper.layout-type-3 .affiliatex-block-pros .affiliatex-title:before"
	].background =
		prosBgType === "solid" ? prosBgColor : prosBgGradient.gradient;
	selectors[
		" .affx-pros-cons-inner-wrapper.layout-type-3 .affiliatex-block-cons .affiliatex-title:before"
	].background =
		consBgType === "solid" ? consBgColor : consBgGradient.gradient;
	selectors[
		" .affx-pros-cons-inner-wrapper.layout-type-4  .affiliatex-pros li"
	].background =
		prosListBgType === "solid"
			? prosListBgColor
			: prosListBgGradient.gradient;
	selectors[
		" .affx-pros-cons-inner-wrapper.layout-type-4  .affiliatex-cons li"
	].background =
		consListBgType === "solid"
			? consListBgColor
			: consListBgGradient.gradient;
	selectors[
		" .affx-pros-cons-inner-wrapper.layout-type-4  .affiliatex-pros p"
	].background =
		prosListBgType === "solid"
			? prosListBgColor
			: prosListBgGradient.gradient;
	selectors[
		" .affx-pros-cons-inner-wrapper.layout-type-4  .affiliatex-cons p"
	].background =
		consListBgType === "solid"
			? consListBgColor
			: consListBgGradient.gradient;
	selectors[" .affiliatex-pros ul"]["list-style"] =
		contentType == "list" &&
		listType == "unordered" &&
		unorderedType == "icon"
			? "none"
			: "";
	selectors[" .affiliatex-cons ul"]["list-style"] =
		contentType == "list" &&
		listType == "unordered" &&
		unorderedType == "icon"
			? "none"
			: "";
	selectors[" .affiliatex-block-pros .affiliatex-title"].color =
		layoutStyle === "layout-type-3" ? prosTextColorThree : prosTextColor;
	selectors[" .affiliatex-block-cons .affiliatex-title"].color =
		layoutStyle === "layout-type-3" ? consTextColorThree : consTextColor;

	tablet_selectors = {
		" .pros-icon-title-wrap .affiliatex-block-pros": {
			"margin-top": titleMargin.tablet.top,
			"margin-left": titleMargin.tablet.left,
			"margin-right": titleMargin.tablet.right,
			"margin-bottom": titleMargin.tablet.bottom,
			"padding-top": titlePadding.tablet.top,
			"padding-left": titlePadding.tablet.left,
			"padding-right": titlePadding.tablet.right,
			"padding-bottom": titlePadding.tablet.bottom,
		},
		" .cons-icon-title-wrap .affiliatex-block-cons": {
			"margin-top": titleMargin.tablet.top,
			"margin-left": titleMargin.tablet.left,
			"margin-right": titleMargin.tablet.right,
			"margin-bottom": titleMargin.tablet.bottom,
			"padding-top": titlePadding.tablet.top,
			"padding-left": titlePadding.tablet.left,
			"padding-right": titlePadding.tablet.right,
			"padding-bottom": titlePadding.tablet.bottom,
		},
		" .affx-pros-cons-inner-wrapper.layout-type-1 .pros-icon-title-wrap .affiliatex-block-pros": {
			"border-width":
				titleBorderWidthOne.tablet.top +
				" " +
				titleBorderWidthOne.tablet.right +
				" " +
				titleBorderWidthOne.tablet.bottom +
				" " +
				titleBorderWidthOne.tablet.left,
			"border-radius":
				titleBorderRadiusOne.tablet.top +
				" " +
				titleBorderRadiusOne.tablet.right +
				" " +
				titleBorderRadiusOne.tablet.bottom +
				" " +
				titleBorderRadiusOne.tablet.left,
		},
		" .affx-pros-cons-inner-wrapper.layout-type-1 .affiliatex-pros": {
			"border-width":
				contentBorderWidthOne.tablet.top +
				" " +
				contentBorderWidthOne.tablet.right +
				" " +
				contentBorderWidthOne.tablet.bottom +
				" " +
				contentBorderWidthOne.tablet.left,
			"border-radius":
				contentBorderRadiusOne.tablet.top +
				" " +
				contentBorderRadiusOne.tablet.right +
				" " +
				contentBorderRadiusOne.tablet.bottom +
				" " +
				contentBorderRadiusOne.tablet.left,
		},
		" .affx-pros-cons-inner-wrapper.layout-type-2 .pros-icon-title-wrap .affiliatex-block-pros": {
			"border-width":
				titleBorderWidthTwo.tablet.top +
				" " +
				titleBorderWidthTwo.tablet.right +
				" " +
				titleBorderWidthTwo.tablet.bottom +
				" " +
				titleBorderWidthTwo.tablet.left,
			"border-radius":
				titleBorderRadiusTwo.tablet.top +
				" " +
				titleBorderRadiusTwo.tablet.right +
				" " +
				titleBorderRadiusTwo.tablet.bottom +
				" " +
				titleBorderRadiusTwo.tablet.left,
		},
		" .affx-pros-cons-inner-wrapper.layout-type-2 .affiliatex-pros": {
			"border-width":
				contentBorderWidthTwo.tablet.top +
				" " +
				contentBorderWidthTwo.tablet.right +
				" " +
				contentBorderWidthTwo.tablet.bottom +
				" " +
				contentBorderWidthTwo.tablet.left,
			"border-radius":
				contentBorderRadiusTwo.tablet.top +
				" " +
				contentBorderRadiusTwo.tablet.right +
				" " +
				contentBorderRadiusTwo.tablet.bottom +
				" " +
				contentBorderRadiusTwo.tablet.left,
		},
		" .affx-pros-cons-inner-wrapper.layout-type-4 .pros-icon-title-wrap .affiliatex-block-pros": {
			"border-width":
				titleBorderWidthFour.tablet.top +
				" " +
				titleBorderWidthFour.tablet.right +
				" " +
				titleBorderWidthFour.tablet.bottom +
				" " +
				titleBorderWidthFour.tablet.left,
			"border-radius":
				titleBorderRadiusFour.tablet.top +
				" " +
				titleBorderRadiusFour.tablet.right +
				" " +
				titleBorderRadiusFour.tablet.bottom +
				" " +
				titleBorderRadiusFour.tablet.left,
		},
		" .affx-pros-cons-inner-wrapper.layout-type-1 .cons-icon-title-wrap .affiliatex-block-cons": {
			"border-width":
				titleBorderWidthOne.tablet.top +
				" " +
				titleBorderWidthOne.tablet.right +
				" " +
				titleBorderWidthOne.tablet.bottom +
				" " +
				titleBorderWidthOne.tablet.left,
			"border-radius":
				titleBorderRadiusOne.tablet.top +
				" " +
				titleBorderRadiusOne.tablet.right +
				" " +
				titleBorderRadiusOne.tablet.bottom +
				" " +
				titleBorderRadiusOne.tablet.left,
		},
		" .affx-pros-cons-inner-wrapper.layout-type-1 .affiliatex-cons": {
			"border-width":
				contentBorderWidthOne.tablet.top +
				" " +
				contentBorderWidthOne.tablet.right +
				" " +
				contentBorderWidthOne.tablet.bottom +
				" " +
				contentBorderWidthOne.tablet.left,
			"border-radius":
				contentBorderRadiusOne.tablet.top +
				" " +
				contentBorderRadiusOne.tablet.right +
				" " +
				contentBorderRadiusOne.tablet.bottom +
				" " +
				contentBorderRadiusOne.tablet.left,
		},
		" .affx-pros-cons-inner-wrapper.layout-type-2 .cons-icon-title-wrap .affiliatex-block-cons": {
			"border-width":
				titleBorderWidthTwo.tablet.top +
				" " +
				titleBorderWidthTwo.tablet.right +
				" " +
				titleBorderWidthTwo.tablet.bottom +
				" " +
				titleBorderWidthTwo.tablet.left,
			"border-radius":
				titleBorderRadiusTwo.tablet.top +
				" " +
				titleBorderRadiusTwo.tablet.right +
				" " +
				titleBorderRadiusTwo.tablet.bottom +
				" " +
				titleBorderRadiusTwo.tablet.left,
		},
		" .affx-pros-cons-inner-wrapper.layout-type-2 .affiliatex-cons": {
			"border-width":
				contentBorderWidthTwo.tablet.top +
				" " +
				contentBorderWidthTwo.tablet.right +
				" " +
				contentBorderWidthTwo.tablet.bottom +
				" " +
				contentBorderWidthTwo.tablet.left,
			"border-radius":
				contentBorderRadiusTwo.tablet.top +
				" " +
				contentBorderRadiusTwo.tablet.right +
				" " +
				contentBorderRadiusTwo.tablet.bottom +
				" " +
				contentBorderRadiusTwo.tablet.left,
		},
		" .affx-pros-cons-inner-wrapper.layout-type-4 .cons-icon-title-wrap .affiliatex-block-cons": {
			"border-width":
				titleBorderWidthFour.tablet.top +
				" " +
				titleBorderWidthFour.tablet.right +
				" " +
				titleBorderWidthFour.tablet.bottom +
				" " +
				titleBorderWidthFour.tablet.left,
			"border-radius":
				titleBorderRadiusFour.tablet.top +
				" " +
				titleBorderRadiusFour.tablet.right +
				" " +
				titleBorderRadiusFour.tablet.bottom +
				" " +
				titleBorderRadiusFour.tablet.left,
		},
		" .affiliatex-block-cons .affiliatex-title": {
			"font-size": titleTypography.size.tablet,
			"line-height": titleTypography["line-height"].tablet,
			"letter-spacing": titleTypography["letter-spacing"].tablet,
		},
		" .affiliatex-block-pros .affiliatex-title": {
			"font-size": titleTypography.size.tablet,
			"line-height": titleTypography["line-height"].tablet,
			"letter-spacing": titleTypography["letter-spacing"].tablet,
		},
		" .affiliatex-content": {
			"margin-top": contentMargin.tablet.top,
			"margin-left": contentMargin.tablet.left,
			"margin-right": contentMargin.tablet.right,
			"margin-bottom": contentMargin.tablet.bottom,
			"padding-top": contentPadding.tablet.top,
			"padding-left": contentPadding.tablet.left,
			"padding-right": contentPadding.tablet.right,
			"padding-bottom": contentPadding.tablet.bottom,
		},
		" .affiliatex-list": {
			"margin-top": contentMargin.tablet.top,
			"margin-left": contentMargin.tablet.left,
			"margin-right": contentMargin.tablet.right,
			"margin-bottom": contentMargin.tablet.bottom,
			"padding-top": contentPadding.tablet.top,
			"padding-left": contentPadding.tablet.left,
			"padding-right": contentPadding.tablet.right,
			"padding-bottom": contentPadding.tablet.bottom,
		},
		" .affiliatex-cons p": {
			"font-size": listTypography.size.tablet,
			"line-height": listTypography["line-height"].tablet,
			"letter-spacing": listTypography["letter-spacing"].tablet,
		},
		" .affiliatex-cons li": {
			"font-size": listTypography.size.tablet,
			"line-height": listTypography["line-height"].tablet,
			"letter-spacing": listTypography["letter-spacing"].tablet,
		},
		" .affiliatex-pros p": {
			"font-size": listTypography.size.tablet,
			"line-height": listTypography["line-height"].tablet,
			"letter-spacing": listTypography["letter-spacing"].tablet,
		},
		" .affiliatex-pros li": {
			"font-size": listTypography.size.tablet,
			"line-height": listTypography["line-height"].tablet,
			"letter-spacing": listTypography["letter-spacing"].tablet,
		},
		" .affx-pros-cons-inner-wrapper.layout-type-3  .pros-icon-title-wrap": {
			"border-width":
				contentBorderWidthThree.tablet.top +
				" " +
				contentBorderWidthThree.tablet.right +
				" " +
				"0" +
				" " +
				contentBorderWidthThree.tablet.left,
			"border-radius":
				contentBorderRadiusThree.tablet.top +
				" " +
				contentBorderRadiusThree.tablet.right +
				" " +
				contentBorderRadiusThree.tablet.bottom +
				" " +
				contentBorderRadiusThree.tablet.left,
		},
		" .affx-pros-cons-inner-wrapper.layout-type-3  .cons-icon-title-wrap": {
			"border-width":
				contentBorderWidthThree.tablet.top +
				" " +
				contentBorderWidthThree.tablet.right +
				" " +
				"0" +
				" " +
				contentBorderWidthThree.tablet.left,
			"border-radius":
				contentBorderRadiusThree.tablet.top +
				" " +
				contentBorderRadiusThree.tablet.right +
				" " +
				contentBorderRadiusThree.tablet.bottom +
				" " +
				contentBorderRadiusThree.tablet.left,
		},
		" .affx-pros-cons-inner-wrapper.layout-type-3  .affiliatex-pros": {
			"border-width":
				"0" +
				" " +
				contentBorderWidthThree.tablet.right +
				" " +
				contentBorderWidthThree.tablet.bottom +
				" " +
				contentBorderWidthThree.tablet.left,
			"border-radius":
				contentBorderRadiusThree.tablet.top +
				" " +
				contentBorderRadiusThree.tablet.right +
				" " +
				contentBorderRadiusThree.tablet.bottom +
				" " +
				contentBorderRadiusThree.tablet.left,
		},
		" .affx-pros-cons-inner-wrapper.layout-type-3  .affiliatex-cons": {
			"border-width":
				"0" +
				" " +
				contentBorderWidthThree.tablet.right +
				" " +
				contentBorderWidthThree.tablet.bottom +
				" " +
				contentBorderWidthThree.tablet.left,
			"border-radius":
				contentBorderRadiusThree.tablet.top +
				" " +
				contentBorderRadiusThree.tablet.right +
				" " +
				contentBorderRadiusThree.tablet.bottom +
				" " +
				contentBorderRadiusThree.tablet.left,
		},
		" .affx-pros-cons-inner-wrapper.layout-type-3 .affiliatex-block-pros .affiliatex-title:before": {
			"border-width":
				titleBorderWidthThree.tablet.top +
				" " +
				titleBorderWidthThree.tablet.right +
				" " +
				titleBorderWidthThree.tablet.bottom +
				" " +
				titleBorderWidthThree.tablet.left,
			"border-radius":
				titleBorderRadiusThree.tablet.top +
				" " +
				titleBorderRadiusThree.tablet.right +
				" " +
				titleBorderRadiusThree.tablet.bottom +
				" " +
				titleBorderRadiusThree.tablet.left,
		},
		" .affx-pros-cons-inner-wrapper.layout-type-3 .affiliatex-block-cons .affiliatex-title:before": {
			"border-width":
				titleBorderWidthThree.tablet.top +
				" " +
				titleBorderWidthThree.tablet.right +
				" " +
				titleBorderWidthThree.tablet.bottom +
				" " +
				titleBorderWidthThree.tablet.left,
			"border-radius":
				titleBorderRadiusThree.tablet.top +
				" " +
				titleBorderRadiusThree.tablet.right +
				" " +
				titleBorderRadiusThree.tablet.bottom +
				" " +
				titleBorderRadiusThree.tablet.left,
		},
		" .affx-pros-cons-inner-wrapper.layout-type-4  .affiliatex-pros ul li": {
			"border-width":
				contentBorderWidthFour.tablet.top +
				" " +
				contentBorderWidthFour.tablet.right +
				" " +
				contentBorderWidthFour.tablet.bottom +
				" " +
				contentBorderWidthFour.tablet.left,
			"border-radius":
				contentBorderRadiusFour.tablet.top +
				" " +
				contentBorderRadiusFour.tablet.right +
				" " +
				contentBorderRadiusFour.tablet.bottom +
				" " +
				contentBorderRadiusFour.tablet.left,
		},
		" .affx-pros-cons-inner-wrapper.layout-type-4  .affiliatex-cons ul li": {
			"border-width":
				contentBorderWidthFour.tablet.top +
				" " +
				contentBorderWidthFour.tablet.right +
				" " +
				contentBorderWidthFour.tablet.bottom +
				" " +
				contentBorderWidthFour.tablet.left,
			"border-radius":
				contentBorderRadiusFour.tablet.top +
				" " +
				contentBorderRadiusFour.tablet.right +
				" " +
				contentBorderRadiusFour.tablet.bottom +
				" " +
				contentBorderRadiusFour.tablet.left,
		},
		" .affx-pros-cons-inner-wrapper.layout-type-4  .affiliatex-content li": {
			"padding-top": contentPadding.tablet.top,
			"padding-left": contentPadding.tablet.left,
			"padding-right": contentPadding.tablet.right,
			"padding-bottom": contentPadding.tablet.bottom,
		},
		" .affx-pros-cons-inner-wrapper.layout-type-4  .affiliatex-list li": {
			"padding-top": contentPadding.tablet.top,
			"padding-left": contentPadding.tablet.left,
			"padding-right": contentPadding.tablet.right,
			"padding-bottom": contentPadding.tablet.bottom,
		},
		" .affx-pros-cons-inner-wrapper.layout-type-4  .affiliatex-pros p": {
			"border-width":
				contentBorderWidthFour.tablet.top +
				" " +
				contentBorderWidthFour.tablet.right +
				" " +
				contentBorderWidthFour.tablet.bottom +
				" " +
				contentBorderWidthFour.tablet.left,
			"border-radius":
				contentBorderRadiusFour.tablet.top +
				" " +
				contentBorderRadiusFour.tablet.right +
				" " +
				contentBorderRadiusFour.tablet.bottom +
				" " +
				contentBorderRadiusFour.tablet.left,
		},
		" .affx-pros-cons-inner-wrapper.layout-type-4  .affiliatex-cons p": {
			"border-width":
				contentBorderWidthFour.tablet.top +
				" " +
				contentBorderWidthFour.tablet.right +
				" " +
				contentBorderWidthFour.tablet.bottom +
				" " +
				contentBorderWidthFour.tablet.left,
			"border-radius":
				contentBorderRadiusFour.tablet.top +
				" " +
				contentBorderRadiusFour.tablet.right +
				" " +
				contentBorderRadiusFour.tablet.bottom +
				" " +
				contentBorderRadiusFour.tablet.left,
		},
		" .affx-pros-cons-inner-wrapper": {
			"margin-top": margin.tablet.top,
			"margin-left": margin.tablet.left,
			"margin-right": margin.tablet.right,
			"margin-bottom": margin.tablet.bottom,
			"padding-top": padding.tablet.top,
			"padding-left": padding.tablet.left,
			"padding-right": padding.tablet.right,
			"padding-bottom": padding.tablet.bottom,
		},
	};

	mobile_selectors = {
		" .pros-icon-title-wrap .affiliatex-block-pros": {
			"margin-top": titleMargin.mobile.top,
			"margin-left": titleMargin.mobile.left,
			"margin-right": titleMargin.mobile.right,
			"margin-bottom": titleMargin.mobile.bottom,
			"padding-top": titlePadding.mobile.top,
			"padding-left": titlePadding.mobile.left,
			"padding-right": titlePadding.mobile.right,
			"padding-bottom": titlePadding.mobile.bottom,
		},
		" .cons-icon-title-wrap .affiliatex-block-cons": {
			"margin-top": titleMargin.mobile.top,
			"margin-left": titleMargin.mobile.left,
			"margin-right": titleMargin.mobile.right,
			"margin-bottom": titleMargin.mobile.bottom,
			"padding-top": titlePadding.mobile.top,
			"padding-left": titlePadding.mobile.left,
			"padding-right": titlePadding.mobile.right,
			"padding-bottom": titlePadding.mobile.bottom,
		},
		" .affx-pros-cons-inner-wrapper.layout-type-1 .pros-icon-title-wrap .affiliatex-block-pros": {
			"border-width":
				titleBorderWidthOne.mobile.top +
				" " +
				titleBorderWidthOne.mobile.right +
				" " +
				titleBorderWidthOne.mobile.bottom +
				" " +
				titleBorderWidthOne.mobile.left,
			"border-radius":
				titleBorderRadiusOne.mobile.top +
				" " +
				titleBorderRadiusOne.mobile.right +
				" " +
				titleBorderRadiusOne.mobile.bottom +
				" " +
				titleBorderRadiusOne.mobile.left,
		},
		" .affx-pros-cons-inner-wrapper.layout-type-1 .affiliatex-pros": {
			"border-width":
				contentBorderWidthOne.mobile.top +
				" " +
				contentBorderWidthOne.mobile.right +
				" " +
				contentBorderWidthOne.mobile.bottom +
				" " +
				contentBorderWidthOne.mobile.left,
			"border-radius":
				contentBorderRadiusOne.mobile.top +
				" " +
				contentBorderRadiusOne.mobile.right +
				" " +
				contentBorderRadiusOne.mobile.bottom +
				" " +
				contentBorderRadiusOne.mobile.left,
		},
		" .affx-pros-cons-inner-wrapper.layout-type-2 .pros-icon-title-wrap .affiliatex-block-pros": {
			"border-width":
				titleBorderWidthTwo.mobile.top +
				" " +
				titleBorderWidthTwo.mobile.right +
				" " +
				titleBorderWidthTwo.mobile.bottom +
				" " +
				titleBorderWidthTwo.mobile.left,
			"border-radius":
				titleBorderRadiusTwo.mobile.top +
				" " +
				titleBorderRadiusTwo.mobile.right +
				" " +
				titleBorderRadiusTwo.mobile.bottom +
				" " +
				titleBorderRadiusTwo.mobile.left,
		},
		" .affx-pros-cons-inner-wrapper.layout-type-2 .affiliatex-pros": {
			"border-width":
				contentBorderWidthTwo.mobile.top +
				" " +
				contentBorderWidthTwo.mobile.right +
				" " +
				contentBorderWidthTwo.mobile.bottom +
				" " +
				contentBorderWidthTwo.mobile.left,
			"border-radius":
				contentBorderRadiusTwo.mobile.top +
				" " +
				contentBorderRadiusTwo.mobile.right +
				" " +
				contentBorderRadiusTwo.mobile.bottom +
				" " +
				contentBorderRadiusTwo.mobile.left,
		},
		" .affx-pros-cons-inner-wrapper.layout-type-4 .pros-icon-title-wrap .affiliatex-block-pros": {
			"border-width":
				titleBorderWidthFour.mobile.top +
				" " +
				titleBorderWidthFour.mobile.right +
				" " +
				titleBorderWidthFour.mobile.bottom +
				" " +
				titleBorderWidthFour.mobile.left,
			"border-radius":
				titleBorderRadiusFour.mobile.top +
				" " +
				titleBorderRadiusFour.mobile.right +
				" " +
				titleBorderRadiusFour.mobile.bottom +
				" " +
				titleBorderRadiusFour.mobile.left,
		},
		" .affx-pros-cons-inner-wrapper.layout-type-1 .cons-icon-title-wrap .affiliatex-block-cons": {
			"border-width":
				titleBorderWidthOne.mobile.top +
				" " +
				titleBorderWidthOne.mobile.right +
				" " +
				titleBorderWidthOne.mobile.bottom +
				" " +
				titleBorderWidthOne.mobile.left,
			"border-radius":
				titleBorderRadiusOne.mobile.top +
				" " +
				titleBorderRadiusOne.mobile.right +
				" " +
				titleBorderRadiusOne.mobile.bottom +
				" " +
				titleBorderRadiusOne.mobile.left,
		},
		" .affx-pros-cons-inner-wrapper.layout-type-1 .affiliatex-cons": {
			"border-width":
				contentBorderWidthOne.mobile.top +
				" " +
				contentBorderWidthOne.mobile.right +
				" " +
				contentBorderWidthOne.mobile.bottom +
				" " +
				contentBorderWidthOne.mobile.left,
			"border-radius":
				contentBorderRadiusOne.mobile.top +
				" " +
				contentBorderRadiusOne.mobile.right +
				" " +
				contentBorderRadiusOne.mobile.bottom +
				" " +
				contentBorderRadiusOne.mobile.left,
		},
		" .affx-pros-cons-inner-wrapper.layout-type-2 .cons-icon-title-wrap .affiliatex-block-cons": {
			"border-width":
				titleBorderWidthTwo.mobile.top +
				" " +
				titleBorderWidthTwo.mobile.right +
				" " +
				titleBorderWidthTwo.mobile.bottom +
				" " +
				titleBorderWidthTwo.mobile.left,
			"border-radius":
				titleBorderRadiusTwo.mobile.top +
				" " +
				titleBorderRadiusTwo.mobile.right +
				" " +
				titleBorderRadiusTwo.mobile.bottom +
				" " +
				titleBorderRadiusTwo.mobile.left,
		},
		" .affx-pros-cons-inner-wrapper.layout-type-2 .affiliatex-cons": {
			"border-width":
				contentBorderWidthTwo.mobile.top +
				" " +
				contentBorderWidthTwo.mobile.right +
				" " +
				contentBorderWidthTwo.mobile.bottom +
				" " +
				contentBorderWidthTwo.mobile.left,
			"border-radius":
				contentBorderRadiusTwo.mobile.top +
				" " +
				contentBorderRadiusTwo.mobile.right +
				" " +
				contentBorderRadiusTwo.mobile.bottom +
				" " +
				contentBorderRadiusTwo.mobile.left,
		},
		" .affx-pros-cons-inner-wrapper.layout-type-4 .cons-icon-title-wrap .affiliatex-block-cons": {
			"border-width":
				titleBorderWidthFour.mobile.top +
				" " +
				titleBorderWidthFour.mobile.right +
				" " +
				titleBorderWidthFour.mobile.bottom +
				" " +
				titleBorderWidthFour.mobile.left,
			"border-radius":
				titleBorderRadiusFour.mobile.top +
				" " +
				titleBorderRadiusFour.mobile.right +
				" " +
				titleBorderRadiusFour.mobile.bottom +
				" " +
				titleBorderRadiusFour.mobile.left,
		},
		" .affiliatex-block-cons .affiliatex-title": {
			"font-size": titleTypography.size.mobile,
			"line-height": titleTypography["line-height"].mobile,
			"letter-spacing": titleTypography["letter-spacing"].mobile,
		},
		" .affiliatex-block-pros .affiliatex-title": {
			"font-size": titleTypography.size.mobile,
			"line-height": titleTypography["line-height"].mobile,
			"letter-spacing": titleTypography["letter-spacing"].mobile,
		},
		" .affiliatex-content": {
			"margin-top": contentMargin.mobile.top,
			"margin-left": contentMargin.mobile.left,
			"margin-right": contentMargin.mobile.right,
			"margin-bottom": contentMargin.mobile.bottom,
			"padding-top": contentPadding.mobile.top,
			"padding-left": contentPadding.mobile.left,
			"padding-right": contentPadding.mobile.right,
			"padding-bottom": contentPadding.mobile.bottom,
		},
		" .affiliatex-list": {
			"margin-top": contentMargin.mobile.top,
			"margin-left": contentMargin.mobile.left,
			"margin-right": contentMargin.mobile.right,
			"margin-bottom": contentMargin.mobile.bottom,
			"padding-top": contentPadding.mobile.top,
			"padding-left": contentPadding.mobile.left,
			"padding-right": contentPadding.mobile.right,
			"padding-bottom": contentPadding.mobile.bottom,
		},
		" .affiliatex-cons p": {
			"font-size": listTypography.size.mobile,
			"line-height": listTypography["line-height"].mobile,
			"letter-spacing": listTypography["letter-spacing"].mobile,
		},
		" .affiliatex-cons li": {
			"font-size": listTypography.size.mobile,
			"line-height": listTypography["line-height"].mobile,
			"letter-spacing": listTypography["letter-spacing"].mobile,
		},
		" .affiliatex-pros p": {
			"font-size": listTypography.size.mobile,
			"line-height": listTypography["line-height"].mobile,
			"letter-spacing": listTypography["letter-spacing"].mobile,
		},
		" .affiliatex-pros li": {
			"font-size": listTypography.size.mobile,
			"line-height": listTypography["line-height"].mobile,
			"letter-spacing": listTypography["letter-spacing"].mobile,
		},
		" .affx-pros-cons-inner-wrapper.layout-type-3  .pros-icon-title-wrap": {
			"border-width":
				contentBorderWidthThree.mobile.top +
				" " +
				contentBorderWidthThree.mobile.right +
				" " +
				"0" +
				" " +
				contentBorderWidthThree.mobile.left,
			"border-radius":
				contentBorderRadiusThree.mobile.top +
				" " +
				contentBorderRadiusThree.mobile.right +
				" " +
				contentBorderRadiusThree.mobile.bottom +
				" " +
				contentBorderRadiusThree.mobile.left,
		},
		" .affx-pros-cons-inner-wrapper.layout-type-3  .cons-icon-title-wrap": {
			"border-width":
				contentBorderWidthThree.mobile.top +
				" " +
				contentBorderWidthThree.mobile.right +
				" " +
				"0" +
				" " +
				contentBorderWidthThree.mobile.left,
			"border-radius":
				contentBorderRadiusThree.mobile.top +
				" " +
				contentBorderRadiusThree.mobile.right +
				" " +
				contentBorderRadiusThree.mobile.bottom +
				" " +
				contentBorderRadiusThree.mobile.left,
		},
		" .affx-pros-cons-inner-wrapper.layout-type-3  .affiliatex-pros": {
			"border-width":
				"0" +
				" " +
				contentBorderWidthThree.mobile.right +
				" " +
				contentBorderWidthThree.mobile.bottom +
				" " +
				contentBorderWidthThree.mobile.left,
			"border-radius":
				contentBorderRadiusThree.mobile.top +
				" " +
				contentBorderRadiusThree.mobile.right +
				" " +
				contentBorderRadiusThree.mobile.bottom +
				" " +
				contentBorderRadiusThree.mobile.left,
		},
		" .affx-pros-cons-inner-wrapper.layout-type-3  .affiliatex-cons": {
			"border-width":
				"0" +
				" " +
				contentBorderWidthThree.mobile.right +
				" " +
				contentBorderWidthThree.mobile.bottom +
				" " +
				contentBorderWidthThree.mobile.left,
			"border-radius":
				contentBorderRadiusThree.mobile.top +
				" " +
				contentBorderRadiusThree.mobile.right +
				" " +
				contentBorderRadiusThree.mobile.bottom +
				" " +
				contentBorderRadiusThree.mobile.left,
		},
		" .affx-pros-cons-inner-wrapper.layout-type-3 .affiliatex-block-pros .affiliatex-title:before": {
			"border-width":
				titleBorderWidthThree.mobile.top +
				" " +
				titleBorderWidthThree.mobile.right +
				" " +
				titleBorderWidthThree.mobile.bottom +
				" " +
				titleBorderWidthThree.mobile.left,
			"border-radius":
				titleBorderRadiusThree.mobile.top +
				" " +
				titleBorderRadiusThree.mobile.right +
				" " +
				titleBorderRadiusThree.mobile.bottom +
				" " +
				titleBorderRadiusThree.mobile.left,
		},
		" .affx-pros-cons-inner-wrapper.layout-type-3 .affiliatex-block-cons .affiliatex-title:before": {
			"border-width":
				titleBorderWidthThree.mobile.top +
				" " +
				titleBorderWidthThree.mobile.right +
				" " +
				titleBorderWidthThree.mobile.bottom +
				" " +
				titleBorderWidthThree.mobile.left,
			"border-radius":
				titleBorderRadiusThree.mobile.top +
				" " +
				titleBorderRadiusThree.mobile.right +
				" " +
				titleBorderRadiusThree.mobile.bottom +
				" " +
				titleBorderRadiusThree.mobile.left,
		},
		" .affx-pros-cons-inner-wrapper.layout-type-4  .affiliatex-pros ul li": {
			"border-width":
				contentBorderWidthFour.mobile.top +
				" " +
				contentBorderWidthFour.mobile.right +
				" " +
				contentBorderWidthFour.mobile.bottom +
				" " +
				contentBorderWidthFour.mobile.left,
			"border-radius":
				contentBorderRadiusFour.mobile.top +
				" " +
				contentBorderRadiusFour.mobile.right +
				" " +
				contentBorderRadiusFour.mobile.bottom +
				" " +
				contentBorderRadiusFour.mobile.left,
		},
		" .affx-pros-cons-inner-wrapper.layout-type-4  .affiliatex-cons ul li": {
			"border-width":
				contentBorderWidthFour.mobile.top +
				" " +
				contentBorderWidthFour.mobile.right +
				" " +
				contentBorderWidthFour.mobile.bottom +
				" " +
				contentBorderWidthFour.mobile.left,
			"border-radius":
				contentBorderRadiusFour.mobile.top +
				" " +
				contentBorderRadiusFour.mobile.right +
				" " +
				contentBorderRadiusFour.mobile.bottom +
				" " +
				contentBorderRadiusFour.mobile.left,
		},
		" .affx-pros-cons-inner-wrapper.layout-type-4  .affiliatex-content li": {
			"padding-top": contentPadding.mobile.top,
			"padding-left": contentPadding.mobile.left,
			"padding-right": contentPadding.mobile.right,
			"padding-bottom": contentPadding.mobile.bottom,
		},
		" .affx-pros-cons-inner-wrapper.layout-type-4  .affiliatex-list li": {
			"padding-top": contentPadding.mobile.top,
			"padding-left": contentPadding.mobile.left,
			"padding-right": contentPadding.mobile.right,
			"padding-bottom": contentPadding.mobile.bottom,
		},
		" .affx-pros-cons-inner-wrapper.layout-type-4  .affiliatex-pros p": {
			"border-width":
				contentBorderWidthFour.mobile.top +
				" " +
				contentBorderWidthFour.mobile.right +
				" " +
				contentBorderWidthFour.mobile.bottom +
				" " +
				contentBorderWidthFour.mobile.left,
			"border-radius":
				contentBorderRadiusFour.mobile.top +
				" " +
				contentBorderRadiusFour.mobile.right +
				" " +
				contentBorderRadiusFour.mobile.bottom +
				" " +
				contentBorderRadiusFour.mobile.left,
		},
		" .affx-pros-cons-inner-wrapper.layout-type-4  .affiliatex-cons p": {
			"border-width":
				contentBorderWidthFour.mobile.top +
				" " +
				contentBorderWidthFour.mobile.right +
				" " +
				contentBorderWidthFour.mobile.bottom +
				" " +
				contentBorderWidthFour.mobile.left,
			"border-radius":
				contentBorderRadiusFour.mobile.top +
				" " +
				contentBorderRadiusFour.mobile.right +
				" " +
				contentBorderRadiusFour.mobile.bottom +
				" " +
				contentBorderRadiusFour.mobile.left,
		},
		" .affx-pros-cons-inner-wrapper": {
			"margin-top": margin.mobile.top,
			"margin-left": margin.mobile.left,
			"margin-right": margin.mobile.right,
			"margin-bottom": margin.mobile.bottom,
			"padding-top": padding.mobile.top,
			"padding-left": padding.mobile.left,
			"padding-right": padding.mobile.right,
			"padding-bottom": padding.mobile.bottom,
		},
	};

	var block_styling_css = "";

	block_styling_css = generateCSS(selectors, `#${id}-${clientId}`);
	block_styling_css += generateCSS(
		tablet_selectors,
		`#${id}-${clientId}`,
		true,
		"tablet"
	);
	block_styling_css += generateCSS(
		mobile_selectors,
		`#${id}-${clientId}`,
		true,
		"mobile"
	);

	return block_styling_css;
};
