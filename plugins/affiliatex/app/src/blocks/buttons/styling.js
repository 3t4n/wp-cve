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
		buttonTypography,
		buttonTextColor,
		buttonTextHoverColor,
		buttonMargin,
		buttonBGColor,
		buttonPadding,
		buttonBGHoverColor,
		buttonBorder,
		buttonAlignment,
		buttonShadow,
		buttonBGType,
		buttonBgGradient,
		buttonIconSize,
		buttonRadius,
		buttonFixWidth,
		buttonborderHoverColor,
		buttonIconColor,
		buttonIconHoverColor,
		priceTextColor,
		priceBackgroundColor,
	} = attributes;

	var selectors = {};
	var mobile_selectors = {};
	var tablet_selectors = {};

	selectors = {
		" .affiliatex-button": {
			"font-family": buttonTypography.family,
			"font-size": buttonTypography.size.desktop,
			"font-weight": buttonTypography.variation,
			"line-height": buttonTypography["line-height"].desktop,
			"text-transform": buttonTypography["text-transform"],
			"text-decoration": buttonTypography["text-decoration"],
			color: buttonTextColor,
			"margin-top": buttonMargin.desktop.top,
			"margin-left": buttonMargin.desktop.left,
			"margin-right": buttonMargin.desktop.right,
			"margin-bottom": buttonMargin.desktop.bottom,
			"background-color": buttonBGColor,
			"padding-top": buttonPadding.desktop.top,
			"padding-left": buttonPadding.desktop.left,
			"padding-right": buttonPadding.desktop.right,
			"padding-bottom": buttonPadding.desktop.bottom,
			"border-style": buttonBorder.style,
			"border-width": buttonBorder.width + "px",
			"border-color": buttonBorder.color.color,
			"box-shadow": cssBoxShadow(buttonShadow)
				? cssBoxShadow(buttonShadow)
				: "",
			"letter-spacing": buttonTypography["letter-spacing"].desktop,
			"font-weight": fontWeightVariation(buttonTypography.variation),
			"font-style": fontStyle(buttonTypography.variation),
			"border-radius":
				buttonRadius.desktop.top +
				" " +
				buttonRadius.desktop.right +
				" " +
				buttonRadius.desktop.bottom +
				" " +
				buttonRadius.desktop.left,
		},
		" .btn-is-fixed": {
			"max-width": buttonFixWidth,
			width: "100%",
		},
		" .affx-btn-inner": {
			"justify-content": buttonAlignment,
		},
		" .affiliatex-button:hover": {
			color: buttonTextHoverColor,
			background: buttonBGHoverColor,
			"border-color": buttonborderHoverColor,
		},
		" .button-icon": {
			"font-size": buttonIconSize,
			color: buttonIconColor,
		},
		" .affiliatex-button:hover .button-icon": {
			color: buttonIconHoverColor,
		},
		" .affiliatex-button .price-tag": {
			color: priceTextColor,
			"background-color": priceBackgroundColor,
			"--border-top-left-radius": buttonRadius.desktop.top,
			"--border-top-right-radius": buttonRadius.desktop.right,
			"--border-bottom-right-radius": buttonRadius.desktop.bottom,
			"--border-bottom-left-radius": buttonRadius.desktop.left,
		},
		" .affiliatex-button .price-tag::before": {
			"background-color": priceBackgroundColor,
		},
	};

	selectors[" .affiliatex-button"].background =
		buttonBGType === "solid" ? buttonBGColor : buttonBgGradient.gradient;

	tablet_selectors = {
		" .affiliatex-button": {
			"font-size": buttonTypography.size.tablet,
			"line-height": buttonTypography["line-height"].tablet,
			"margin-top": buttonMargin.tablet.top,
			"margin-left": buttonMargin.tablet.left,
			"margin-right": buttonMargin.tablet.right,
			"margin-bottom": buttonMargin.tablet.bottom,
			"padding-top": buttonPadding.tablet.top,
			"padding-left": buttonPadding.tablet.left,
			"padding-right": buttonPadding.tablet.right,
			"padding-bottom": buttonPadding.tablet.bottom,
			"border-radius":
				buttonRadius.tablet.top +
				" " +
				buttonRadius.tablet.right +
				" " +
				buttonRadius.tablet.bottom +
				" " +
				buttonRadius.tablet.left,
		},
	};

	mobile_selectors = {
		" .affiliatex-button": {
			"font-size": buttonTypography.size.mobile,
			"line-height": buttonTypography["line-height"].mobile,
			"margin-top": buttonMargin.mobile.top,
			"margin-left": buttonMargin.mobile.left,
			"margin-right": buttonMargin.mobile.right,
			"margin-bottom": buttonMargin.mobile.bottom,
			"padding-top": buttonPadding.mobile.top,
			"padding-left": buttonPadding.mobile.left,
			"padding-right": buttonPadding.mobile.right,
			"padding-bottom": buttonPadding.mobile.bottom,
			"border-radius":
				buttonRadius.mobile.top +
				" " +
				buttonRadius.mobile.right +
				" " +
				buttonRadius.mobile.bottom +
				" " +
				buttonRadius.mobile.left,
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
