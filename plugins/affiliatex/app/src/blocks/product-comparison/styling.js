/**
 * Set inline styles.
 * @param  {object} props - The block object.
 * @return {object} The inline background type CSS.
 */
import { generateCSS } from "../helpers/generateCSS";
import {
	fontWeightVariation,
	fontStyle,
} from "../ui-components/helpers/get-label";
import { cssBoxShadow } from "../ui-components/helpers/get-label";

export default (attributes, id, clientId) => {
	const {
		boxShadow,
		buttonPadding,
		buttonMargin,
		border,
		borderWidth,
		borderRadius,
		priceColor,
		tableRowBgColor,
		contentColor,
		buttonTextColor,
		buttonTextHoverColor,
		bgType,
		bgColorSolid,
		bgColorGradient,
		buttonBgColor,
		buttonBgHoverColor,
		titleTypography,
		ribbonTypography,
		priceTypography,
		buttonTypography,
		contentTypography,
		margin,
		padding,
		ribbonColor,
		ribbonTextColor,
		titleColor,
		imagePadding,
	} = attributes;

	var selectors = {};
	var mobile_selectors = {};
	var tablet_selectors = {};

	selectors = {
		" .affx-product-comparison-block-container": {
			"margin-top": margin.desktop.top,
			"margin-left": margin.desktop.left,
			"margin-right": margin.desktop.right,
			"margin-bottom": margin.desktop.bottom,
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
		},
		" .affx-product-versus-table": {
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
		" .affx-comparison-title": {
			"font-family": titleTypography.family,
			"text-decoration": titleTypography["text-decoration"],
			"font-weight": fontWeightVariation(titleTypography.variation),
			"font-style": fontStyle(titleTypography.variation),
			"font-size": titleTypography.size.desktop,
			"line-height": titleTypography["line-height"].desktop,
			"text-transform": titleTypography["text-transform"],
			"letter-spacing": titleTypography["letter-spacing"].desktop,
			color: titleColor,
		},
		" .affx-versus-table-wrap tr:first-child th:first-child": {
			"border-top-left-radius": borderRadius.desktop.top,
		},
		" .affx-versus-table-wrap tr:first-child th:last-child": {
			"border-top-right-radius": borderRadius.desktop.right,
		},
		" .affx-versus-table-wrap tr:first-child th:last-child .affx-versus-product": {
			"border-top-right-radius": borderRadius.desktop.right,
			overflow: "hidden",
		},
		" .affx-versus-table-wrap tr:last-child td:first-child": {
			"border-bottom-left-radius": borderRadius.desktop.left,
		},
		" .affx-versus-table-wrap tr:last-child td:last-child": {
			"border-bottom-right-radius": borderRadius.desktop.bottom,
		},
		" .affx-versus-table-wrap td": {
			"padding-top": padding.desktop.top,
			"padding-left": padding.desktop.left,
			"padding-right": padding.desktop.right,
			"padding-bottom": padding.desktop.bottom,
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
		" .affx-versus-table-wrap th": {
			"padding-top": padding.desktop.top,
			"padding-left": padding.desktop.left,
			"padding-right": padding.desktop.right,
			"padding-bottom": padding.desktop.bottom,
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
		" .affx-versus-table-wrap .affx-pc-ribbon": {
			"font-family": ribbonTypography.family,
			"text-decoration": ribbonTypography["text-decoration"],
			"font-weight": fontWeightVariation(ribbonTypography.variation),
			"font-style": fontStyle(ribbonTypography.variation),
			"font-size": ribbonTypography.size.desktop,
			"line-height": ribbonTypography["line-height"].desktop,
			"text-transform": ribbonTypography["text-transform"],
			"letter-spacing": ribbonTypography["letter-spacing"].desktop,
			background: ribbonColor,
			color: ribbonTextColor,
		},
		" .affx-versus-table-wrap .affx-pc-ribbon::before": {
			background: ribbonColor,
		},
		" .affx-versus-table-wrap .affx-pc-ribbon::after": {
			background: ribbonColor,
		},
		" .affx-versus-table-wrap .affiliatex-button": {
			"font-family": buttonTypography.family,
			"text-decoration": buttonTypography["text-decoration"],
			"font-weight": fontWeightVariation(buttonTypography.variation),
			"font-style": fontStyle(buttonTypography.variation),
			"font-size": buttonTypography.size.desktop,
			"line-height": buttonTypography["line-height"].desktop,
			"text-transform": buttonTypography["text-transform"],
			"letter-spacing": buttonTypography["letter-spacing"].desktop,
		},
		" .affx-versus-table-wrap .affiliatex-button.affx-winner-button": {
			color: buttonTextColor,
			"background-color": buttonBgColor,
			"margin-top": buttonMargin.desktop.top,
			"margin-left": buttonMargin.desktop.left,
			"margin-right": buttonMargin.desktop.right,
			"margin-bottom": buttonMargin.desktop.bottom,
			"padding-top": buttonPadding.desktop.top,
			"padding-left": buttonPadding.desktop.left,
			"padding-right": buttonPadding.desktop.right,
			"padding-bottom": buttonPadding.desktop.bottom,
		},
		" .affx-versus-table-wrap .affiliatex-button.affx-winner-button:hover": {
			color: buttonTextHoverColor,
			"background-color": buttonBgHoverColor,
		},
		" .affx-versus-table-wrap .affx-price": {
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
		" .affx-versus-table-wrap .affx-product-versus-table .affx-table-row-header": {
			background: tableRowBgColor,
		},
		" .affx-versus-table-wrap .affx-product-versus-table .affx-table-row-header th": {
			"font-family": contentTypography.family,
			"text-decoration": contentTypography["text-decoration"],
			"font-weight": fontWeightVariation(contentTypography.variation),
			"font-style": fontStyle(contentTypography.variation),
			"font-size": contentTypography.size.desktop,
			"line-height": contentTypography["line-height"].desktop,
			"text-transform": contentTypography["text-transform"],
			"letter-spacing": contentTypography["letter-spacing"].desktop,
		},
		" .affx-versus-table-wrap .affx-product-versus-table th": {
			// background: tableRowBgColor,
			"font-family": contentTypography.family,
			"text-decoration": contentTypography["text-decoration"],
			"font-weight": fontWeightVariation(contentTypography.variation),
			"font-style": fontStyle(contentTypography.variation),
			"font-size": contentTypography.size.desktop,
			"line-height": contentTypography["line-height"].desktop,
			"text-transform": contentTypography["text-transform"],
			"letter-spacing": contentTypography["letter-spacing"].desktop,
		},
		" .affx-versus-table-wrap .affx-product-versus-table tbody tr:nth-child(odd) td": {
			background: tableRowBgColor,
		},
		" .affx-versus-table-wrap .affx-versus-product-img": {
			"padding-top": imagePadding.desktop.top,
			"padding-left": imagePadding.desktop.left,
			"padding-right": imagePadding.desktop.right,
			"padding-bottom": imagePadding.desktop.bottom,
		},
	};

	selectors[" .affx-product-comparison-block-container"].background =
		bgType === "solid" ? bgColorSolid : bgColorGradient.gradient;

	selectors[" .affx-product-comparison-block-container"][
		"box-shadow"
	] = cssBoxShadow(boxShadow) ? cssBoxShadow(boxShadow) : "";

	tablet_selectors = {
		" .affx-product-comparison-block-container": {
			"margin-top": margin.tablet.top,
			"margin-left": margin.tablet.left,
			"margin-right": margin.tablet.right,
			"margin-bottom": margin.tablet.bottom,
			"border-style": border.style,
			"border-width":
				borderWidth.tablet.top +
				" " +
				borderWidth.tablet.right +
				" " +
				borderWidth.tablet.bottom +
				" " +
				borderWidth.tablet.left,
			"border-color": border.color.color,
			"border-radius":
				borderRadius.tablet.top +
				" " +
				borderRadius.tablet.right +
				" " +
				borderRadius.tablet.bottom +
				" " +
				borderRadius.tablet.left,
		},
		" .affx-product-versus-table": {
			color: contentColor,
			"font-family": contentTypography.family,
			"text-decoration": contentTypography["text-decoration"],
			"font-weight": fontWeightVariation(contentTypography.variation),
			"font-style": fontStyle(contentTypography.variation),
			"font-size": contentTypography.size.tablet,
			"line-height": contentTypography["line-height"].tablet,
			"text-transform": contentTypography["text-transform"],
			"letter-spacing": contentTypography["letter-spacing"].tablet,
		},
		" .affx-comparison-title": {
			"font-family": titleTypography.family,
			"text-decoration": titleTypography["text-decoration"],
			"font-weight": fontWeightVariation(titleTypography.variation),
			"font-style": fontStyle(titleTypography.variation),
			"font-size": titleTypography.size.tablet,
			"line-height": titleTypography["line-height"].tablet,
			"text-transform": titleTypography["text-transform"],
			"letter-spacing": titleTypography["letter-spacing"].tablet,
			color: titleColor,
		},
		" .affx-versus-table-wrap tr:first-child th:first-child": {
			"border-top-left-radius": borderRadius.tablet.top,
		},
		" .affx-versus-table-wrap tr:first-child th:last-child": {
			"border-top-right-radius": borderRadius.tablet.right,
		},
		" .affx-versus-table-wrap tr:first-child th:last-child .affx-versus-product": {
			"border-top-right-radius": borderRadius.tablet.right,
			overflow: "hidden",
		},
		" .affx-versus-table-wrap tr:last-child td:first-child": {
			"border-bottom-left-radius": borderRadius.tablet.left,
		},
		" .affx-versus-table-wrap tr:last-child td:last-child": {
			"border-bottom-right-radius": borderRadius.tablet.bottom,
		},
		" .affx-versus-table-wrap td": {
			"padding-top": padding.tablet.top,
			"padding-left": padding.tablet.left,
			"padding-right": padding.tablet.right,
			"padding-bottom": padding.tablet.bottom,
			"border-style": border.style,
			"border-width":
				borderWidth.tablet.top +
				" " +
				borderWidth.tablet.right +
				" " +
				borderWidth.tablet.bottom +
				" " +
				borderWidth.tablet.left,
			"border-color": border.color.color,
		},
		" .affx-versus-table-wrap th": {
			"padding-top": padding.tablet.top,
			"padding-left": padding.tablet.left,
			"padding-right": padding.tablet.right,
			"padding-bottom": padding.tablet.bottom,
			"border-style": border.style,
			"border-width":
				borderWidth.tablet.top +
				" " +
				borderWidth.tablet.right +
				" " +
				borderWidth.tablet.bottom +
				" " +
				borderWidth.tablet.left,
			"border-color": border.color.color,
		},
		" .affx-versus-table-wrap .affx-pc-ribbon": {
			"font-family": ribbonTypography.family,
			"text-decoration": ribbonTypography["text-decoration"],
			"font-weight": fontWeightVariation(ribbonTypography.variation),
			"font-style": fontStyle(ribbonTypography.variation),
			"font-size": ribbonTypography.size.tablet,
			"line-height": ribbonTypography["line-height"].tablet,
			"text-transform": ribbonTypography["text-transform"],
			"letter-spacing": ribbonTypography["letter-spacing"].tablet,
			background: ribbonColor,
			color: ribbonTextColor,
		},
		" .affx-versus-table-wrap .affx-pc-ribbon::before": {
			background: ribbonColor,
		},
		" .affx-versus-table-wrap .affx-pc-ribbon::after": {
			background: ribbonColor,
		},
		" .affx-versus-table-wrap .affiliatex-button": {
			"font-family": buttonTypography.family,
			"text-decoration": buttonTypography["text-decoration"],
			"font-weight": fontWeightVariation(buttonTypography.variation),
			"font-style": fontStyle(buttonTypography.variation),
			"font-size": buttonTypography.size.tablet,
			"line-height": buttonTypography["line-height"].tablet,
			"text-transform": buttonTypography["text-transform"],
			"letter-spacing": buttonTypography["letter-spacing"].tablet,
		},
		" .affx-versus-table-wrap .affiliatex-button.affx-winner-button": {
			color: buttonTextColor,
			"background-color": buttonBgColor,
			"margin-top": buttonMargin.tablet.top,
			"margin-left": buttonMargin.tablet.left,
			"margin-right": buttonMargin.tablet.right,
			"margin-bottom": buttonMargin.tablet.bottom,
			"padding-top": buttonPadding.tablet.top,
			"padding-left": buttonPadding.tablet.left,
			"padding-right": buttonPadding.tablet.right,
			"padding-bottom": buttonPadding.tablet.bottom,
		},
		" .affx-versus-table-wrap .affiliatex-button.affx-winner-button:hover": {
			color: buttonTextHoverColor,
			"background-color": buttonBgHoverColor,
		},
		" .affx-versus-table-wrap .affx-price": {
			color: priceColor,
			"font-family": priceTypography.family,
			"font-weight": fontWeightVariation(priceTypography.variation),
			"font-style": fontStyle(priceTypography.variation),
			"font-size": priceTypography.size.tablet,
			"line-height": priceTypography["line-height"].tablet,
			"text-transform": priceTypography["text-transform"],
			"text-decoration": priceTypography["text-decoration"],
			"letter-spacing": priceTypography["letter-spacing"].tablet,
		},
		" .affx-versus-table-wrap .affx-product-versus-table .affx-table-row-header": {
			background: tableRowBgColor,
		},
		" .affx-versus-table-wrap .affx-product-versus-table .affx-table-row-header th": {
			"font-family": contentTypography.family,
			"text-decoration": contentTypography["text-decoration"],
			"font-weight": fontWeightVariation(contentTypography.variation),
			"font-style": fontStyle(contentTypography.variation),
			"font-size": contentTypography.size.tablet,
			"line-height": contentTypography["line-height"].tablet,
			"text-transform": contentTypography["text-transform"],
			"letter-spacing": contentTypography["letter-spacing"].tablet,
		},
		" .affx-versus-table-wrap .affx-product-versus-table th": {
			// background: tableRowBgColor,
			"font-family": contentTypography.family,
			"text-decoration": contentTypography["text-decoration"],
			"font-weight": fontWeightVariation(contentTypography.variation),
			"font-style": fontStyle(contentTypography.variation),
			"font-size": contentTypography.size.tablet,
			"line-height": contentTypography["line-height"].tablet,
			"text-transform": contentTypography["text-transform"],
			"letter-spacing": contentTypography["letter-spacing"].tablet,
		},
		" .affx-versus-table-wrap .affx-product-versus-table tbody tr:nth-child(odd) td": {
			background: tableRowBgColor,
		},
		" .affx-versus-table-wrap .affx-versus-product-img": {
			"padding-top": imagePadding.tablet.top,
			"padding-left": imagePadding.tablet.left,
			"padding-right": imagePadding.tablet.right,
			"padding-bottom": imagePadding.tablet.bottom,
		},
	};

	mobile_selectors = {
		" .affx-product-comparison-block-container": {
			"margin-top": margin.mobile.top,
			"margin-left": margin.mobile.left,
			"margin-right": margin.mobile.right,
			"margin-bottom": margin.mobile.bottom,
			"border-style": border.style,
			"border-width":
				borderWidth.mobile.top +
				" " +
				borderWidth.mobile.right +
				" " +
				borderWidth.mobile.bottom +
				" " +
				borderWidth.mobile.left,
			"border-color": border.color.color,
			"border-radius":
				borderRadius.mobile.top +
				" " +
				borderRadius.mobile.right +
				" " +
				borderRadius.mobile.bottom +
				" " +
				borderRadius.mobile.left,
		},
		" .affx-product-versus-table": {
			color: contentColor,
			"font-family": contentTypography.family,
			"text-decoration": contentTypography["text-decoration"],
			"font-weight": fontWeightVariation(contentTypography.variation),
			"font-style": fontStyle(contentTypography.variation),
			"font-size": contentTypography.size.mobile,
			"line-height": contentTypography["line-height"].mobile,
			"text-transform": contentTypography["text-transform"],
			"letter-spacing": contentTypography["letter-spacing"].mobile,
		},
		" .affx-comparison-title": {
			"font-family": titleTypography.family,
			"text-decoration": titleTypography["text-decoration"],
			"font-weight": fontWeightVariation(titleTypography.variation),
			"font-style": fontStyle(titleTypography.variation),
			"font-size": titleTypography.size.mobile,
			"line-height": titleTypography["line-height"].mobile,
			"text-transform": titleTypography["text-transform"],
			"letter-spacing": titleTypography["letter-spacing"].mobile,
			color: titleColor,
		},
		" .affx-versus-table-wrap tr:first-child th:first-child": {
			"border-top-left-radius": borderRadius.mobile.top,
		},
		" .affx-versus-table-wrap tr:first-child th:last-child": {
			"border-top-right-radius": borderRadius.mobile.right,
		},
		" .affx-versus-table-wrap tr:first-child th:last-child .affx-versus-product": {
			"border-top-right-radius": borderRadius.mobile.right,
			overflow: "hidden",
		},
		" .affx-versus-table-wrap tr:last-child td:first-child": {
			"border-bottom-left-radius": borderRadius.mobile.left,
		},
		" .affx-versus-table-wrap tr:last-child td:last-child": {
			"border-bottom-right-radius": borderRadius.mobile.bottom,
		},
		" .affx-versus-table-wrap td": {
			"padding-top": padding.mobile.top,
			"padding-left": padding.mobile.left,
			"padding-right": padding.mobile.right,
			"padding-bottom": padding.mobile.bottom,
			"border-style": border.style,
			"border-width":
				borderWidth.mobile.top +
				" " +
				borderWidth.mobile.right +
				" " +
				borderWidth.mobile.bottom +
				" " +
				borderWidth.mobile.left,
			"border-color": border.color.color,
		},
		" .affx-versus-table-wrap th": {
			"padding-top": padding.mobile.top,
			"padding-left": padding.mobile.left,
			"padding-right": padding.mobile.right,
			"padding-bottom": padding.mobile.bottom,
			"border-style": border.style,
			"border-width":
				borderWidth.mobile.top +
				" " +
				borderWidth.mobile.right +
				" " +
				borderWidth.mobile.bottom +
				" " +
				borderWidth.mobile.left,
			"border-color": border.color.color,
		},
		" .affx-versus-table-wrap .affx-pc-ribbon": {
			"font-family": ribbonTypography.family,
			"text-decoration": ribbonTypography["text-decoration"],
			"font-weight": fontWeightVariation(ribbonTypography.variation),
			"font-style": fontStyle(ribbonTypography.variation),
			"font-size": ribbonTypography.size.mobile,
			"line-height": ribbonTypography["line-height"].mobile,
			"text-transform": ribbonTypography["text-transform"],
			"letter-spacing": ribbonTypography["letter-spacing"].mobile,
			background: ribbonColor,
			color: ribbonTextColor,
		},
		" .affx-versus-table-wrap .affx-pc-ribbon::before": {
			background: ribbonColor,
		},
		" .affx-versus-table-wrap .affx-pc-ribbon::after": {
			background: ribbonColor,
		},
		" .affx-versus-table-wrap .affiliatex-button": {
			"font-family": buttonTypography.family,
			"text-decoration": buttonTypography["text-decoration"],
			"font-weight": fontWeightVariation(buttonTypography.variation),
			"font-style": fontStyle(buttonTypography.variation),
			"font-size": buttonTypography.size.mobile,
			"line-height": buttonTypography["line-height"].mobile,
			"text-transform": buttonTypography["text-transform"],
			"letter-spacing": buttonTypography["letter-spacing"].mobile,
		},
		" .affx-versus-table-wrap .affiliatex-button.affx-winner-button": {
			color: buttonTextColor,
			"background-color": buttonBgColor,
			"margin-top": buttonMargin.mobile.top,
			"margin-left": buttonMargin.mobile.left,
			"margin-right": buttonMargin.mobile.right,
			"margin-bottom": buttonMargin.mobile.bottom,
			"padding-top": buttonPadding.mobile.top,
			"padding-left": buttonPadding.mobile.left,
			"padding-right": buttonPadding.mobile.right,
			"padding-bottom": buttonPadding.mobile.bottom,
		},
		" .affx-versus-table-wrap .affiliatex-button.affx-winner-button:hover": {
			color: buttonTextHoverColor,
			"background-color": buttonBgHoverColor,
		},
		" .affx-versus-table-wrap .affx-price": {
			color: priceColor,
			"font-family": priceTypography.family,
			"font-weight": fontWeightVariation(priceTypography.variation),
			"font-style": fontStyle(priceTypography.variation),
			"font-size": priceTypography.size.mobile,
			"line-height": priceTypography["line-height"].mobile,
			"text-transform": priceTypography["text-transform"],
			"text-decoration": priceTypography["text-decoration"],
			"letter-spacing": priceTypography["letter-spacing"].mobile,
		},
		" .affx-versus-table-wrap .affx-product-versus-table .affx-table-row-header": {
			background: tableRowBgColor,
		},
		" .affx-versus-table-wrap .affx-product-versus-table .affx-table-row-header th": {
			"font-family": contentTypography.family,
			"text-decoration": contentTypography["text-decoration"],
			"font-weight": fontWeightVariation(contentTypography.variation),
			"font-style": fontStyle(contentTypography.variation),
			"font-size": contentTypography.size.mobile,
			"line-height": contentTypography["line-height"].mobile,
			"text-transform": contentTypography["text-transform"],
			"letter-spacing": contentTypography["letter-spacing"].mobile,
		},
		" .affx-versus-table-wrap .affx-product-versus-table th": {
			// background: tableRowBgColor,
			"font-family": contentTypography.family,
			"text-decoration": contentTypography["text-decoration"],
			"font-weight": fontWeightVariation(contentTypography.variation),
			"font-style": fontStyle(contentTypography.variation),
			"font-size": contentTypography.size.mobile,
			"line-height": contentTypography["line-height"].mobile,
			"text-transform": contentTypography["text-transform"],
			"letter-spacing": contentTypography["letter-spacing"].mobile,
		},
		" .affx-versus-table-wrap .affx-product-versus-table tbody tr:nth-child(odd) td": {
			background: tableRowBgColor,
		},
		" .affx-versus-table-wrap .affx-versus-product-img": {
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
