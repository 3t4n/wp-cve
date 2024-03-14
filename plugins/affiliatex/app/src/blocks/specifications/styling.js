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
		specificationBorder,
		specificationBorderWidth,
		specificationBorderRadius,
		specificationBoxShadow,
		specificationBgType,
		specificationBgColorSolid,
		specificationBgColorGradient,
		specificationTitleTypography,
		specificationLabelTypography,
		specificationValueTypography,
		specificationTitleColor,
		specificationTitleBgColor,
		specificationLabelColor,
		specificationValueColor,
		specificationRowColor,
		specificationTitleAlign,
		specificationLabelAlign,
		specificationValueAlign,
		specificationMargin,
		specificationPadding,
		specificationColumnWidth,
	} = attributes;

	var selectors = {};
	var mobile_selectors = {};
	var tablet_selectors = {};

	selectors = {
		" .affx-specification-block-container": {
			"border-style": specificationBorder.style,
			"border-width":
				specificationBorderWidth.desktop.top +
				" " +
				specificationBorderWidth.desktop.right +
				" " +
				specificationBorderWidth.desktop.bottom +
				" " +
				specificationBorderWidth.desktop.left,
			"border-color": specificationBorder.color.color,
			"border-radius":
				specificationBorderRadius.desktop.top +
				" " +
				specificationBorderRadius.desktop.right +
				" " +
				specificationBorderRadius.desktop.bottom +
				" " +
				specificationBorderRadius.desktop.left,
			overflow: "hidden",
			"margin-top": specificationMargin.desktop.top,
			"margin-left": specificationMargin.desktop.left,
			"margin-right": specificationMargin.desktop.right,
			"margin-bottom": specificationMargin.desktop.bottom,
		},
		" .affx-specification-table": {
			margin: "0",
		},
		" .affx-specification-table td": {
			"padding-top": specificationPadding.desktop.top,
			"padding-left": specificationPadding.desktop.left,
			"padding-right": specificationPadding.desktop.right,
			"padding-bottom": specificationPadding.desktop.bottom,
		},
		" .affx-specification-table th": {
			color: specificationTitleColor,
			background: specificationTitleBgColor,
			"text-align": specificationTitleAlign,
			"font-family": specificationTitleTypography.family,
			"text-decoration": specificationTitleTypography["text-decoration"],
			"font-weight": fontWeightVariation(
				specificationTitleTypography.variation
			),
			"font-style": fontStyle(specificationTitleTypography.variation),
			"font-size": specificationTitleTypography.size.desktop,
			"line-height": specificationTitleTypography["line-height"].desktop,
			"text-transform": specificationTitleTypography["text-transform"],
			"letter-spacing":
				specificationTitleTypography["letter-spacing"].desktop,
			"padding-top": specificationPadding.desktop.top,
			"padding-left": specificationPadding.desktop.left,
			"padding-right": specificationPadding.desktop.right,
			"padding-bottom": specificationPadding.desktop.bottom,
		},
		" .affx-specification-table td.affx-spec-label": {
			color: specificationLabelColor,
			"text-align": specificationLabelAlign,
			"font-family": specificationLabelTypography.family,
			"text-decoration": specificationLabelTypography["text-decoration"],
			"font-weight": fontWeightVariation(
				specificationLabelTypography.variation
			),
			"font-style": fontStyle(specificationLabelTypography.variation),
			"font-size": specificationLabelTypography.size.desktop,
			"line-height": specificationLabelTypography["line-height"].desktop,
			"text-transform": specificationLabelTypography["text-transform"],
			"letter-spacing":
				specificationLabelTypography["letter-spacing"].desktop,
			width:
				specificationColumnWidth === "styleOne"
					? "33.33%"
					: specificationColumnWidth === "styleTwo"
					? "50%"
					: "66.66%",
		},
		" .affx-specification-table td.affx-spec-value": {
			color: specificationValueColor,
			"text-align": specificationValueAlign,
			"font-family": specificationValueTypography.family,
			"text-decoration": specificationValueTypography["text-decoration"],
			"font-weight": fontWeightVariation(
				specificationValueTypography.variation
			),
			"font-style": fontStyle(specificationValueTypography.variation),
			"font-size": specificationValueTypography.size.desktop,
			"line-height": specificationValueTypography["line-height"].desktop,
			"text-transform": specificationValueTypography["text-transform"],
			"letter-spacing":
				specificationValueTypography["letter-spacing"].desktop,
			width:
				specificationColumnWidth === "styleOne"
					? "66.66%"
					: specificationColumnWidth === "styleTwo"
					? "50%"
					: "33.33%",
		},
		" .affx-specification-table.layout-2 td.affx-spec-label": {
			background: specificationRowColor,
		},
		" .affx-specification-table.layout-3 tbody tr:nth-child(even) td": {
			background: specificationRowColor,
		},
	};

	selectors[" .affx-specification-table"].background =
		specificationBgType === "solid"
			? specificationBgColorSolid
			: specificationBgColorGradient.gradient;

	selectors[" .affx-specification-block-container"][
		"box-shadow"
	] = cssBoxShadow(specificationBoxShadow)
		? cssBoxShadow(specificationBoxShadow)
		: "";

	tablet_selectors = {
		" .affx-specification-block-container": {
			"border-style": specificationBorder.style,
			"border-width":
				specificationBorderWidth.tablet.top +
				" " +
				specificationBorderWidth.tablet.right +
				" " +
				specificationBorderWidth.tablet.bottom +
				" " +
				specificationBorderWidth.tablet.left,
			"border-color": specificationBorder.color.color,
			"border-radius":
				specificationBorderRadius.tablet.top +
				" " +
				specificationBorderRadius.tablet.right +
				" " +
				specificationBorderRadius.tablet.bottom +
				" " +
				specificationBorderRadius.tablet.left,
		},
		" .affx-specification-table": {
			"margin-top": specificationMargin.tablet.top,
			"margin-left": specificationMargin.tablet.left,
			"margin-right": specificationMargin.tablet.right,
			"margin-bottom": specificationMargin.tablet.bottom,
		},
		" .affx-specification-table td": {
			"padding-top": specificationPadding.tablet.top,
			"padding-left": specificationPadding.tablet.left,
			"padding-right": specificationPadding.tablet.right,
			"padding-bottom": specificationPadding.tablet.bottom,
		},
		" .affx-specification-table th": {
			"font-size": specificationTitleTypography.size.tablet,
			"line-height": specificationTitleTypography["line-height"].tablet,
			"letter-spacing":
				specificationTitleTypography["letter-spacing"].tablet,
			"padding-top": specificationPadding.tablet.top,
			"padding-left": specificationPadding.tablet.left,
			"padding-right": specificationPadding.tablet.right,
			"padding-bottom": specificationPadding.tablet.bottom,
		},
		" .affx-specification-table td.affx-spec-label": {
			"font-size": specificationLabelTypography.size.tablet,
			"line-height": specificationLabelTypography["line-height"].tablet,
			"letter-spacing":
				specificationLabelTypography["letter-spacing"].tablet,
		},
		" .affx-specification-table td.affx-spec-value": {
			"font-size": specificationValueTypography.size.tablet,
			"line-height": specificationValueTypography["line-height"].tablet,
			"letter-spacing":
				specificationValueTypography["letter-spacing"].tablet,
		},
	};

	mobile_selectors = {
		" .affx-specification-block-container": {
			"border-style": specificationBorder.style,
			"border-width":
				specificationBorderWidth.mobile.top +
				" " +
				specificationBorderWidth.mobile.right +
				" " +
				specificationBorderWidth.mobile.bottom +
				" " +
				specificationBorderWidth.mobile.left,
			"border-color": specificationBorder.color.color,
			"border-radius":
				specificationBorderRadius.mobile.top +
				" " +
				specificationBorderRadius.mobile.right +
				" " +
				specificationBorderRadius.mobile.bottom +
				" " +
				specificationBorderRadius.mobile.left,
		},
		" .affx-specification-table": {
			"margin-top": specificationMargin.mobile.top,
			"margin-left": specificationMargin.mobile.left,
			"margin-right": specificationMargin.mobile.right,
			"margin-bottom": specificationMargin.mobile.bottom,
		},
		" .affx-specification-table td": {
			"padding-top": specificationPadding.mobile.top,
			"padding-left": specificationPadding.mobile.left,
			"padding-right": specificationPadding.mobile.right,
			"padding-bottom": specificationPadding.mobile.bottom,
		},
		" .affx-specification-table th": {
			"font-size": specificationTitleTypography.size.mobile,
			"line-height": specificationTitleTypography["line-height"].mobile,
			"letter-spacing":
				specificationTitleTypography["letter-spacing"].mobile,
			"padding-top": specificationPadding.mobile.top,
			"padding-left": specificationPadding.mobile.left,
			"padding-right": specificationPadding.mobile.right,
			"padding-bottom": specificationPadding.mobile.bottom,
		},
		" .affx-specification-table td.affx-spec-label": {
			"font-size": specificationLabelTypography.size.mobile,
			"line-height": specificationLabelTypography["line-height"].mobile,
			"letter-spacing":
				specificationLabelTypography["letter-spacing"].mobile,
		},
		" .affx-specification-table td.affx-spec-value": {
			"font-size": specificationValueTypography.size.mobile,
			"line-height": specificationValueTypography["line-height"].mobile,
			"letter-spacing":
				specificationValueTypography["letter-spacing"].mobile,
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
