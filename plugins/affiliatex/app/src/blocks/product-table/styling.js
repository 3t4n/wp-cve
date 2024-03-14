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
		boxShadow,
		border,
		borderWidth,
		borderRadius,
		button1Padding,
		button1Margin,
		button2Padding,
		button2Margin,
		ribbonColor,
		ribbonBgColor,
		counterColor,
		counterBgColor,
		tableHeaderColor,
		tableHeaderBgColor,
		priceColor,
		ratingColor,
		ratingBgColor,
		rating2Color,
		rating2BgColor,
		contentColor,
		titleColor,
		bgType,
		bgColorSolid,
		bgColorGradient,
		button1TextColor,
		button1TextHoverColor,
		button1BgColor,
		button1BgHoverColor,
		button2TextColor,
		button2TextHoverColor,
		button2BgColor,
		button2BgHoverColor,
		margin,
		padding,
		ribbonTypography,
		priceTypography,
		buttonTypography,
		contentTypography,
		counterTypography,
		titleTypography,
		headerTypography,
		ratingTypography,
		rating2Typography,
		productIconColor,
		imagePadding,
	} = attributes;

	var selectors = {};
	var mobile_selectors = {};
	var tablet_selectors = {};

	selectors = {
		" .affx-pdt-table-wrapper": {
			color: contentColor,
			"font-family": contentTypography.family,
			"text-decoration": contentTypography["text-decoration"],
			"font-weight": fontWeightVariation(contentTypography.variation),
			"font-style": fontStyle(contentTypography.variation),
			"font-size": contentTypography.size.desktop,
			"line-height": contentTypography["line-height"].desktop,
			"text-transform": contentTypography["text-transform"],
			"letter-spacing": contentTypography["letter-spacing"].desktop,
			"margin-top": margin.desktop.top,
			"margin-left": margin.desktop.left,
			"margin-right": margin.desktop.right,
			"margin-bottom": margin.desktop.bottom,
		},
		" .star-rating-single-wrap": {
			color: ratingColor,
			background: ratingBgColor,
			"font-family": ratingTypography.family,
			"text-decoration": ratingTypography["text-decoration"],
			"font-weight": fontWeightVariation(ratingTypography.variation),
			"font-style": fontStyle(ratingTypography.variation),
			"font-size": ratingTypography.size.desktop,
			"line-height": ratingTypography["line-height"].desktop,
			"text-transform": ratingTypography["text-transform"],
			"letter-spacing": ratingTypography["letter-spacing"].desktop,
		},
		" .circle-wrap .circle-mask .fill": {
			background: rating2BgColor,
		},
		" .affx-circle-progress-container .affx-circle-inside": {
			color: rating2Color,
			"font-family": rating2Typography.family,
			"text-decoration": rating2Typography["text-decoration"],
			"font-weight": fontWeightVariation(rating2Typography.variation),
			"font-style": fontStyle(rating2Typography.variation),
			"font-size": rating2Typography.size.desktop,
			"line-height": rating2Typography["line-height"].desktop,
			"text-transform": rating2Typography["text-transform"],
			"letter-spacing": rating2Typography["letter-spacing"].desktop,
		},
		" .affx-pdt-table-wrapper p": {
			color: contentColor,
			"font-family": contentTypography.family,
			"text-decoration": contentTypography["text-decoration"],
			"font-weight": fontWeightVariation(contentTypography.variation),
			"font-style": fontStyle(contentTypography.variation),
			"font-size": contentTypography.size.desktop,
			"line-height": contentTypography["line-height"].desktop,
			"text-transform": contentTypography["text-transform"],
			"letter-spacing": contentTypography["letter-spacing"].desktop,
		},
		" .affx-pdt-table-wrapper li": {
			color: contentColor,
			"font-family": contentTypography.family,
			"text-decoration": contentTypography["text-decoration"],
			"font-weight": fontWeightVariation(contentTypography.variation),
			"font-style": fontStyle(contentTypography.variation),
			"font-size": contentTypography.size.desktop,
			"line-height": contentTypography["line-height"].desktop,
			"text-transform": contentTypography["text-transform"],
			"letter-spacing": contentTypography["letter-spacing"].desktop,
		},
		" .affx-pdt-table-wrapper .affx-pdt-name": {
			color: titleColor,
			"font-family": titleTypography.family,
			"text-decoration": titleTypography["text-decoration"],
			"font-weight": fontWeightVariation(titleTypography.variation),
			"font-style": fontStyle(titleTypography.variation),
			"font-size": titleTypography.size.desktop,
			"line-height": titleTypography["line-height"].desktop,
			"text-transform": titleTypography["text-transform"],
			"letter-spacing": titleTypography["letter-spacing"].desktop,
		},
		" .affx-pdt-table-wrapper:not(.layout-3)": {
			"border-style": border.style,
			"border-width":
				borderWidth.desktop.top +
				" " +
				borderWidth.desktop.right +
				" " +
				borderWidth.desktop.bottom +
				" " +
				borderWidth.desktop.left,
			"border-color": border.color.color,
		},
		" .affx-pdt-table-single": {
			"border-style": border.style,
			"border-width":
				borderWidth.desktop.top +
				" " +
				borderWidth.desktop.right +
				" " +
				borderWidth.desktop.bottom +
				" " +
				borderWidth.desktop.left,
			"border-color": border.color.color,
			"border-radius":
				borderRadius.desktop.top +
				" " +
				borderRadius.desktop.right +
				" " +
				borderRadius.desktop.bottom +
				" " +
				borderRadius.desktop.left,
			"margin-top": margin.desktop.top,
			"margin-left": margin.desktop.left,
			"margin-right": margin.desktop.right,
			"margin-bottom": margin.desktop.bottom,
		},
		" .affx-pdt-table-wrapper td": {
			"padding-top": padding.desktop.top,
			"padding-left": padding.desktop.left,
			"padding-right": padding.desktop.right,
			"padding-bottom": padding.desktop.bottom,
		},
		" .affx-pdt-table-wrapper th": {
			"padding-top": padding.desktop.top,
			"padding-left": padding.desktop.left,
			"padding-right": padding.desktop.right,
			"padding-bottom": padding.desktop.bottom,
		},

		" .affx-pdt-table-wrapper .affx-pdt-counter": {
			color: counterColor,
			background: counterBgColor,
			"font-family": counterTypography.family,
			"text-decoration": counterTypography["text-decoration"],
			"font-weight": fontWeightVariation(counterTypography.variation),
			"font-style": fontStyle(counterTypography.variation),
			"font-size": counterTypography.size.desktop,
			"line-height": counterTypography["line-height"].desktop,
			"text-transform": counterTypography["text-transform"],
			"letter-spacing": counterTypography["letter-spacing"].desktop,
		},

		" .affx-pdt-table-wrapper .affx-pdt-ribbon": {
			"font-family": ribbonTypography.family,
			"text-decoration": ribbonTypography["text-decoration"],
			"font-weight": fontWeightVariation(ribbonTypography.variation),
			"font-style": fontStyle(ribbonTypography.variation),
			"font-size": ribbonTypography.size.desktop,
			"line-height": ribbonTypography["line-height"].desktop,
			"text-transform": ribbonTypography["text-transform"],
			"letter-spacing": ribbonTypography["letter-spacing"].desktop,
			color: ribbonColor,
			background: ribbonBgColor,
		},
		" .affx-pdt-table-wrapper .affx-pdt-ribbon::before": {
			background: ribbonBgColor,
		},
		" .affx-pdt-table-wrapper .affiliatex-button": {
			"font-family": buttonTypography.family,
			"text-decoration": buttonTypography["text-decoration"],
			"font-weight": fontWeightVariation(buttonTypography.variation),
			"font-style": fontStyle(buttonTypography.variation),
			"font-size": buttonTypography.size.desktop,
			"line-height": buttonTypography["line-height"].desktop,
			"text-transform": buttonTypography["text-transform"],
			"letter-spacing": buttonTypography["letter-spacing"].desktop,
		},
		" .affx-pdt-table-wrapper .affiliatex-button.primary": {
			color: button1TextColor,
			"background-color": button1BgColor,
			"margin-top": button1Margin.desktop.top,
			"margin-left": button1Margin.desktop.left,
			"margin-right": button1Margin.desktop.right,
			"margin-bottom": button1Margin.desktop.bottom,
			"padding-top": button1Padding.desktop.top,
			"padding-left": button1Padding.desktop.left,
			"padding-right": button1Padding.desktop.right,
			"padding-bottom": button1Padding.desktop.bottom,
		},
		" .affx-pdt-table-wrapper .affiliatex-button.secondary": {
			color: button2TextColor,
			"background-color": button2BgColor,
			"margin-top": button2Margin.desktop.top,
			"margin-left": button2Margin.desktop.left,
			"margin-right": button2Margin.desktop.right,
			"margin-bottom": button2Margin.desktop.bottom,
			"padding-top": button2Padding.desktop.top,
			"padding-left": button2Padding.desktop.left,
			"padding-right": button2Padding.desktop.right,
			"padding-bottom": button2Padding.desktop.bottom,
		},
		" .affx-pdt-table-wrapper .affiliatex-button.primary:hover": {
			color: button1TextHoverColor,
			"background-color": button1BgHoverColor,
		},
		" .affx-pdt-table-wrapper .affiliatex-button.secondary:hover": {
			color: button2TextHoverColor,
			"background-color": button2BgHoverColor,
		},
		" .affx-pdt-table-wrapper .affx-pdt-price-wrap": {
			color: priceColor,
			"font-family": priceTypography.family,
			"font-weight": fontWeightVariation(priceTypography.variation),
			"font-style": fontStyle(priceTypography.variation),
			"font-size": priceTypography.size.desktop,
			"line-height": priceTypography["line-height"].desktop,
			"text-transform": priceTypography["text-transform"],
			"text-decoration": priceTypography["text-decoration"],
			"letter-spacing": priceTypography["letter-spacing"].desktop,
		},
		" .affx-pdt-table-wrapper .affx-pdt-table thead td": {
			background: tableHeaderBgColor,
			"border-color": tableHeaderBgColor,
			color: tableHeaderColor,
			"font-family": headerTypography.family,
			"text-decoration": headerTypography["text-decoration"],
			"font-weight": fontWeightVariation(headerTypography.variation),
			"font-style": fontStyle(headerTypography.variation),
			"font-size": headerTypography.size.desktop,
			"line-height": headerTypography["line-height"].desktop,
			"text-transform": headerTypography["text-transform"],
			"letter-spacing": headerTypography["letter-spacing"].desktop,
		},
		" .affx-pdt-table-wrapper .affiliatex-icon li:before": {
			color: productIconColor,
		},
		" .affx-pdt-table": {},
		" .affx-pdt-table-wrapper .affx-pdt-img-container": {
			"padding-top": imagePadding.desktop.top,
			"padding-left": imagePadding.desktop.left,
			"padding-right": imagePadding.desktop.right,
			"padding-bottom": imagePadding.desktop.bottom,
		},
	};

	selectors[" .affx-pdt-table-wrapper:not(.layout-3)"].background =
		bgType === "solid" ? bgColorSolid : bgColorGradient.gradient;
	selectors[" .affx-pdt-table"].background =
		bgType === "solid" ? bgColorSolid : bgColorGradient.gradient;
	selectors[" .affx-pdt-table-single"].background =
		bgType === "solid" ? bgColorSolid : bgColorGradient.gradient;

	selectors[" .affx-pdt-table-wrapper:not(.layout-3)"][
		"box-shadow"
	] = cssBoxShadow(boxShadow) ? cssBoxShadow(boxShadow) : "";
	selectors[" .affx-pdt-table-single"]["box-shadow"] = cssBoxShadow(boxShadow)
		? cssBoxShadow(boxShadow)
		: "";

	tablet_selectors = {
		" .affx-pdt-table-wrapper": {
			"font-size": contentTypography.size.tablet,
			"line-height": contentTypography["line-height"].tablet,
			"letter-spacing": contentTypography["letter-spacing"].tablet,
		},
		" .star-rating-single-wrap": {
			"font-size": ratingTypography.size.tablet,
			"line-height": ratingTypography["line-height"].tablet,
			"letter-spacing": ratingTypography["letter-spacing"].tablet,
		},
		" .affx-circle-progress-container .affx-circle-inside": {
			"font-size": rating2Typography.size.tablet,
			"line-height": rating2Typography["line-height"].tablet,
			"letter-spacing": rating2Typography["letter-spacing"].tablet,
		},
		" .affx-pdt-table-wrapper p": {
			"font-size": contentTypography.size.tablet,
			"line-height": contentTypography["line-height"].tablet,
			"letter-spacing": contentTypography["letter-spacing"].tablet,
		},
		" .affx-pdt-table-wrapper li": {
			"font-size": contentTypography.size.tablet,
			"line-height": contentTypography["line-height"].tablet,
			"letter-spacing": contentTypography["letter-spacing"].tablet,
		},
		" .affx-pdt-table-wrapper .affx-pdt-name": {
			"font-size": titleTypography.size.tablet,
			"line-height": titleTypography["line-height"].tablet,
			"letter-spacing": titleTypography["letter-spacing"].tablet,
		},
		" .affx-pdt-table-wrapper:not(.layout-3)": {
			"margin-top": margin.tablet.top,
			"margin-left": margin.tablet.left,
			"margin-right": margin.tablet.right,
			"margin-bottom": margin.tablet.bottom,
		},
		" .affx-pdt-table-single": {
			"border-width":
				borderWidth.tablet.top +
				" " +
				borderWidth.tablet.right +
				" " +
				borderWidth.tablet.bottom +
				" " +
				borderWidth.tablet.left,

			"border-radius":
				borderRadius.tablet.top +
				" " +
				borderRadius.tablet.right +
				" " +
				borderRadius.tablet.bottom +
				" " +
				borderRadius.tablet.left,
			"margin-top": margin.tablet.top,
			"margin-left": margin.tablet.left,
			"margin-right": margin.tablet.right,
			"margin-bottom": margin.tablet.bottom,
		},
		" .affx-pdt-table-wrapper td": {
			"padding-top": padding.tablet.top,
			"padding-left": padding.tablet.left,
			"padding-right": padding.tablet.right,
			"padding-bottom": padding.tablet.bottom,
			"border-width":
				borderWidth.tablet.top +
				" " +
				borderWidth.tablet.right +
				" " +
				borderWidth.tablet.bottom +
				" " +
				borderWidth.tablet.left,
		},

		" .affx-pdt-table-wrapper th": {
			"padding-top": padding.tablet.top,
			"padding-left": padding.tablet.left,
			"padding-right": padding.tablet.right,
			"padding-bottom": padding.tablet.bottom,
		},

		" .affx-pdt-table-wrapper .affx-pdt-counter": {
			"font-size": counterTypography.size.tablet,
			"line-height": counterTypography["line-height"].tablet,
			"letter-spacing": counterTypography["letter-spacing"].tablet,
		},

		" .affx-pdt-table-wrapper .affx-pdt-ribbon": {
			"font-size": ribbonTypography.size.tablet,
			"line-height": ribbonTypography["line-height"].tablet,
			"letter-spacing": ribbonTypography["letter-spacing"].tablet,
		},
		" .affx-pdt-table-wrapper .affiliatex-button": {
			"font-size": buttonTypography.size.tablet,
			"line-height": buttonTypography["line-height"].tablet,
			"letter-spacing": buttonTypography["letter-spacing"].tablet,
		},
		" .affx-pdt-table-wrapper .affiliatex-button.primary": {
			"margin-top": button1Margin.tablet.top,
			"margin-left": button1Margin.tablet.left,
			"margin-right": button1Margin.tablet.right,
			"margin-bottom": button1Margin.tablet.bottom,
			"padding-top": button1Padding.tablet.top,
			"padding-left": button1Padding.tablet.left,
			"padding-right": button1Padding.tablet.right,
			"padding-bottom": button1Padding.tablet.bottom,
		},
		" .affx-pdt-table-wrapper .affiliatex-button.secondary": {
			"margin-top": button2Margin.tablet.top,
			"margin-left": button2Margin.tablet.left,
			"margin-right": button2Margin.tablet.right,
			"margin-bottom": button2Margin.tablet.bottom,
			"padding-top": button2Padding.tablet.top,
			"padding-left": button2Padding.tablet.left,
			"padding-right": button2Padding.tablet.right,
			"padding-bottom": button2Padding.tablet.bottom,
		},
		" .affx-pdt-table-wrapper .affx-pdt-price-wrap": {
			"font-size": priceTypography.size.tablet,
			"line-height": priceTypography["line-height"].tablet,

			"letter-spacing": priceTypography["letter-spacing"].tablet,
		},
		" .affx-pdt-table-wrapper .affx-pdt-table thead td": {
			"font-size": headerTypography.size.tablet,
			"line-height": headerTypography["line-height"].tablet,
			"letter-spacing": headerTypography["letter-spacing"].tablet,
		},
		" .affx-pdt-table-wrapper .affx-pdt-img-container": {
			"padding-top": imagePadding.tablet.top,
			"padding-left": imagePadding.tablet.left,
			"padding-right": imagePadding.tablet.right,
			"padding-bottom": imagePadding.tablet.bottom,
		},
	};

	mobile_selectors = {
		" .affx-pdt-table-wrapper": {
			"font-size": contentTypography.size.mobile,
			"line-height": contentTypography["line-height"].mobile,
			"letter-spacing": contentTypography["letter-spacing"].mobile,
		},
		" .star-rating-single-wrap": {
			"font-size": ratingTypography.size.mobile,
			"line-height": ratingTypography["line-height"].mobile,
			"letter-spacing": ratingTypography["letter-spacing"].mobile,
		},
		" .affx-circle-progress-container .affx-circle-inside": {
			"font-size": rating2Typography.size.mobile,
			"line-height": rating2Typography["line-height"].mobile,
			"letter-spacing": rating2Typography["letter-spacing"].mobile,
		},
		" .affx-pdt-table-wrapper p": {
			"font-size": contentTypography.size.mobile,
			"line-height": contentTypography["line-height"].mobile,
			"letter-spacing": contentTypography["letter-spacing"].mobile,
		},
		" .affx-pdt-table-wrapper li": {
			"font-size": contentTypography.size.mobile,
			"line-height": contentTypography["line-height"].mobile,
			"letter-spacing": contentTypography["letter-spacing"].mobile,
		},
		" .affx-pdt-table-wrapper .affx-pdt-name": {
			"font-size": titleTypography.size.mobile,
			"line-height": titleTypography["line-height"].mobile,
			"letter-spacing": titleTypography["letter-spacing"].mobile,
		},
		" .affx-pdt-table-wrapper:not(.layout-3)": {
			"margin-top": margin.mobile.top,
			"margin-left": margin.mobile.left,
			"margin-right": margin.mobile.right,
			"margin-bottom": margin.mobile.bottom,
		},
		" .affx-pdt-table-single": {
			"border-width":
				borderWidth.mobile.top +
				" " +
				borderWidth.mobile.right +
				" " +
				borderWidth.mobile.bottom +
				" " +
				borderWidth.mobile.left,

			"border-radius":
				borderRadius.mobile.top +
				" " +
				borderRadius.mobile.right +
				" " +
				borderRadius.mobile.bottom +
				" " +
				borderRadius.mobile.left,
			"margin-top": margin.mobile.top,
			"margin-left": margin.mobile.left,
			"margin-right": margin.mobile.right,
			"margin-bottom": margin.mobile.bottom,
		},
		" .affx-pdt-table-wrapper td": {
			"padding-top": padding.mobile.top,
			"padding-left": padding.mobile.left,
			"padding-right": padding.mobile.right,
			"padding-bottom": padding.mobile.bottom,
			"border-width":
				borderWidth.mobile.top +
				" " +
				borderWidth.mobile.right +
				" " +
				borderWidth.mobile.bottom +
				" " +
				borderWidth.mobile.left,
		},

		" .affx-pdt-table-wrapper th": {
			"padding-top": padding.mobile.top,
			"padding-left": padding.mobile.left,
			"padding-right": padding.mobile.right,
			"padding-bottom": padding.mobile.bottom,
		},

		" .affx-pdt-table-wrapper .affx-pdt-counter": {
			"font-size": counterTypography.size.mobile,
			"line-height": counterTypography["line-height"].mobile,
			"letter-spacing": counterTypography["letter-spacing"].mobile,
		},

		" .affx-pdt-table-wrapper .affx-pdt-ribbon": {
			"font-size": ribbonTypography.size.mobile,
			"line-height": ribbonTypography["line-height"].mobile,
			"letter-spacing": ribbonTypography["letter-spacing"].mobile,
		},
		" .affx-pdt-table-wrapper .affiliatex-button": {
			"font-size": buttonTypography.size.mobile,
			"line-height": buttonTypography["line-height"].mobile,
			"letter-spacing": buttonTypography["letter-spacing"].mobile,
		},
		" .affx-pdt-table-wrapper .affiliatex-button.primary": {
			"margin-top": button1Margin.mobile.top,
			"margin-left": button1Margin.mobile.left,
			"margin-right": button1Margin.mobile.right,
			"margin-bottom": button1Margin.mobile.bottom,
			"padding-top": button1Padding.mobile.top,
			"padding-left": button1Padding.mobile.left,
			"padding-right": button1Padding.mobile.right,
			"padding-bottom": button1Padding.mobile.bottom,
		},
		" .affx-pdt-table-wrapper .affiliatex-button.secondary": {
			"margin-top": button2Margin.mobile.top,
			"margin-left": button2Margin.mobile.left,
			"margin-right": button2Margin.mobile.right,
			"margin-bottom": button2Margin.mobile.bottom,
			"padding-top": button2Padding.mobile.top,
			"padding-left": button2Padding.mobile.left,
			"padding-right": button2Padding.mobile.right,
			"padding-bottom": button2Padding.mobile.bottom,
		},
		" .affx-pdt-table-wrapper .affx-pdt-price-wrap": {
			"font-size": priceTypography.size.mobile,
			"line-height": priceTypography["line-height"].mobile,

			"letter-spacing": priceTypography["letter-spacing"].mobile,
		},
		" .affx-pdt-table-wrapper .affx-pdt-table thead td": {
			"font-size": headerTypography.size.mobile,
			"line-height": headerTypography["line-height"].mobile,
			"letter-spacing": headerTypography["letter-spacing"].mobile,
		},
		" .affx-pdt-table-wrapper .affx-pdt-img-container": {
			"padding-top": imagePadding.mobile.top,
			"padding-left": imagePadding.mobile.left,
			"padding-right": imagePadding.mobile.right,
			"padding-bottom": imagePadding.mobile.bottom,
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
