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
		vsTextColor,
		vsBgColor,
		contentColor,
		versusRowColor,
		bgType,
		bgColorSolid,
		bgColorGradient,
		vsTypography,
		versusContentTypography,
		margin,
		padding,
	} = attributes;

	var selectors = {};
	var mobile_selectors = {};
	var tablet_selectors = {};

	selectors = {
		" .affx-versus-table-wrap": {
			color: contentColor,
			"font-family": versusContentTypography.family,
			"text-decoration": versusContentTypography["text-decoration"],
			"font-weight": fontWeightVariation(
				versusContentTypography.variation
			),
			"font-style": fontStyle(versusContentTypography.variation),
			"font-size": versusContentTypography.size.desktop,
			"line-height": versusContentTypography["line-height"].desktop,
			"text-transform": versusContentTypography["text-transform"],
			"letter-spacing": versusContentTypography["letter-spacing"].desktop,
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
			"padding-top": padding.desktop.top,
			"padding-left": padding.desktop.left,
			"padding-right": padding.desktop.right,
			"padding-bottom": padding.desktop.bottom,
		},
		" .affx-versus-table-wrap .affx-vs-icon": {
			background: vsBgColor,
			color: vsTextColor,
			"font-family": vsTypography.family,
			"font-weight": fontWeightVariation(vsTypography.variation),
			"font-style": fontStyle(vsTypography.variation),
			"font-size": vsTypography.size.desktop,
			"line-height": vsTypography["line-height"].desktop,
			"text-transform": vsTypography["text-transform"],
			"text-decoration": vsTypography["text-decoration"],
			"letter-spacing": vsTypography["letter-spacing"].desktop,
		},
		" .affx-product-versus-table tbody tr:nth-child(odd) td": {
			background: versusRowColor,
		},
	};

	selectors[" .affx-versus-table-wrap"].background =
		bgType === "solid" ? bgColorSolid : bgColorGradient.gradient;

	selectors[" .affx-versus-table-wrap"]["box-shadow"] = cssBoxShadow(
		boxShadow
	)
		? cssBoxShadow(boxShadow)
		: "";

	tablet_selectors = {
		" .affx-versus-table-wrap": {
			"font-size": versusContentTypography.size.tablet,
			"line-height": versusContentTypography["line-height"].tablet,
			"letter-spacing": versusContentTypography["letter-spacing"].tablet,
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
			"padding-top": padding.tablet.top,
			"padding-left": padding.tablet.left,
			"padding-right": padding.tablet.right,
			"padding-bottom": padding.tablet.bottom,
		},
		" .affx-versus-table-wrap .affx-vs-icon": {
			"font-size": vsTypography.size.tablet,
			"line-height": vsTypography["line-height"].tablet,
			"letter-spacing": vsTypography["letter-spacing"].tablet,
		},
	};

	mobile_selectors = {
		" .affx-versus-table-wrap": {
			"font-size": versusContentTypography.size.mobile,
			"line-height": versusContentTypography["line-height"].mobile,
			"letter-spacing": versusContentTypography["letter-spacing"].mobile,
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
			"padding-top": padding.mobile.top,
			"padding-left": padding.mobile.left,
			"padding-right": padding.mobile.right,
			"padding-bottom": padding.mobile.bottom,
		},
		" .affx-versus-table-wrap .affx-vs-icon": {
			"font-size": vsTypography.size.mobile,
			"line-height": vsTypography["line-height"].mobile,
			"letter-spacing": vsTypography["letter-spacing"].mobile,
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
