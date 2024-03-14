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
		verdictTitleTypography,
		verdictContentTypography,
		contentAlignment,
		verdictBorder,
		verdictBorderWidth,
		verdictBorderRadius,
		verdictBoxPadding,
		verdictMargin,
		verdictBoxShadow,
		verdictTitleColor,
		verdictContentColor,
		verdictArrowColor,
		scoreTextColor,
		scoreBgTopColor,
		scoreBgBotColor,
		verdictBgType,
		verdictBgColorSolid,
		verdictBgColorGradient,
		ratingAlignment,
	} = attributes;

	var selectors = {};
	var mobile_selectors = {};
	var tablet_selectors = {};

	selectors = {
		" .affblk-verdict-wrapper": {
			"border-style": verdictBorder.style,
			"border-width":
				verdictBorderWidth.desktop.top +
				" " +
				verdictBorderWidth.desktop.right +
				" " +
				verdictBorderWidth.desktop.bottom +
				" " +
				verdictBorderWidth.desktop.left,
			"border-color": verdictBorder.color.color,
			"border-radius":
				verdictBorderRadius.desktop.top +
				" " +
				verdictBorderRadius.desktop.right +
				" " +
				verdictBorderRadius.desktop.bottom +
				" " +
				verdictBorderRadius.desktop.left,
			"padding-top": verdictBoxPadding.desktop.top,
			"padding-left": verdictBoxPadding.desktop.left,
			"padding-right": verdictBoxPadding.desktop.right,
			"padding-bottom": verdictBoxPadding.desktop.bottom,
			"margin-top": verdictMargin.desktop.top,
			"margin-left": verdictMargin.desktop.left,
			"margin-right": verdictMargin.desktop.right,
			"margin-bottom": verdictMargin.desktop.bottom,
		},
		".wp-block-affiliatex-verdict .verdict-layout-2": {
			"text-align": contentAlignment,
		},
		".wp-block-affiliatex-verdict .verdict-title": {
			color: verdictTitleColor,
			"font-family": verdictTitleTypography.family,
			"text-decoration": verdictTitleTypography["text-decoration"],
			"font-weight": fontWeightVariation(
				verdictTitleTypography.variation
			),
			"font-style": fontStyle(verdictTitleTypography.variation),
			"font-size": verdictTitleTypography.size.desktop,
			"line-height": verdictTitleTypography["line-height"].desktop,
			"text-transform": verdictTitleTypography["text-transform"],
			"letter-spacing": verdictTitleTypography["letter-spacing"].desktop,
		},
		".wp-block-affiliatex-verdict .verdict-content": {
			color: verdictContentColor,
			"font-family": verdictContentTypography.family,
			"text-decoration": verdictContentTypography["text-decoration"],
			"font-weight": fontWeightVariation(
				verdictContentTypography.variation
			),
			"font-style": fontStyle(verdictContentTypography.variation),
			"font-size": verdictContentTypography.size.desktop,
			"line-height": verdictContentTypography["line-height"].desktop,
			"text-transform": verdictContentTypography["text-transform"],
			"letter-spacing":
				verdictContentTypography["letter-spacing"].desktop,
		},
		" .verdict-user-rating-wrapper .components-base-control__field > .components-text-control__input": {
			color: verdictContentColor,
		},
		" .verdict-user-rating-wrapper": {
			color: verdictContentColor,
		},
		".wp-block-affiliatex-verdict .verdict-layout-2.display-arrow .affx-btn-inner .affiliatex-button::after": {
			background: verdictArrowColor,
		},
		".wp-block-affiliatex-verdict .verdict-layout-1 .components-base-control__field > .components-text-control__input": {
			color: scoreTextColor,
		},
		".wp-block-affiliatex-verdict .verdict-layout-1 .num": {
			color: scoreTextColor,
		},
		".wp-block-affiliatex-verdict .verdict-layout-1 .main-text-holder": {
			"flex-direction": ratingAlignment == "left" ? "row-reverse" : "row",
		},
		".wp-block-affiliatex-verdict .verdict-layout-1 .affx-verdict-rating-number": {
			color: scoreTextColor,
		},
		".wp-block-affiliatex-verdict .verdict-layout-1 .num": {
			"background-color": scoreBgTopColor,
			color: scoreTextColor,
		},
		".wp-block-affiliatex-verdict .verdict-layout-1 .rich-content": {
			"background-color": scoreBgBotColor,
			color: scoreTextColor,
		},
		".wp-block-affiliatex-verdict .verdict-layout-1 .rich-content::after": {
			"border-top": 5 + "px" + " solid " + scoreBgBotColor,
		},
		".wp-block-affiliatex-verdict .verdict-layout-1 .block-editor-rich-text__editable.affx-rating-input-content.rich-text": {
			"background-color": scoreBgBotColor,
		},
		".wp-block-affiliatex-verdict .verdict-layout-1 .block-editor-rich-text__editable.affx-rating-input-content.rich-text::after": {
			"border-top": 5 + "px" + " solid " + scoreBgBotColor,
		},
		" .affblk-verdict-wrapper ": {},
	};

	selectors[" .affblk-verdict-wrapper"].background =
		verdictBgType === "solid"
			? verdictBgColorSolid
			: verdictBgColorGradient.gradient;
	selectors[" .affblk-verdict-wrapper"]["box-shadow"] = cssBoxShadow(
		verdictBoxShadow
	)
		? cssBoxShadow(verdictBoxShadow)
		: "";

	tablet_selectors = {
		" .affblk-verdict-wrapper": {
			"border-width":
				verdictBorderWidth.tablet.top +
				" " +
				verdictBorderWidth.tablet.right +
				" " +
				verdictBorderWidth.tablet.bottom +
				" " +
				verdictBorderWidth.tablet.left,
			"border-radius":
				verdictBorderRadius.tablet.top +
				" " +
				verdictBorderRadius.tablet.right +
				" " +
				verdictBorderRadius.tablet.bottom +
				" " +
				verdictBorderRadius.tablet.left,
			"padding-top": verdictBoxPadding.tablet.top,
			"padding-left": verdictBoxPadding.tablet.left,
			"padding-right": verdictBoxPadding.tablet.right,
			"padding-bottom": verdictBoxPadding.tablet.bottom,
			"margin-top": verdictMargin.tablet.top,
			"margin-left": verdictMargin.tablet.left,
			"margin-right": verdictMargin.tablet.right,
			"margin-bottom": verdictMargin.tablet.bottom,
		},
		".wp-block-affiliatex-verdict .verdict-title": {
			"font-size": verdictTitleTypography.size.tablet,
			"line-height": verdictTitleTypography["line-height"].tablet,
			"letter-spacing": verdictTitleTypography["letter-spacing"].tablet,
		},
		".wp-block-affiliatex-verdict .verdict-content": {
			"font-size": verdictContentTypography.size.tablet,
			"line-height": verdictContentTypography["line-height"].tablet,
			"letter-spacing": verdictContentTypography["letter-spacing"].tablet,
		},
	};

	mobile_selectors = {
		" .affblk-verdict-wrapper": {
			"border-width":
				verdictBorderWidth.mobile.top +
				" " +
				verdictBorderWidth.mobile.right +
				" " +
				verdictBorderWidth.mobile.bottom +
				" " +
				verdictBorderWidth.mobile.left,
			"border-radius":
				verdictBorderRadius.mobile.top +
				" " +
				verdictBorderRadius.mobile.right +
				" " +
				verdictBorderRadius.mobile.bottom +
				" " +
				verdictBorderRadius.mobile.left,
			"padding-top": verdictBoxPadding.mobile.top,
			"padding-left": verdictBoxPadding.mobile.left,
			"padding-right": verdictBoxPadding.mobile.right,
			"padding-bottom": verdictBoxPadding.mobile.bottom,
			"margin-top": verdictMargin.mobile.top,
			"margin-left": verdictMargin.mobile.left,
			"margin-right": verdictMargin.mobile.right,
			"margin-bottom": verdictMargin.mobile.bottom,
		},
		".wp-block-affiliatex-verdict .verdict-title": {
			"font-size": verdictTitleTypography.size.mobile,
			"line-height": verdictTitleTypography["line-height"].mobile,
			"letter-spacing": verdictTitleTypography["letter-spacing"].mobile,
		},
		".wp-block-affiliatex-verdict .verdict-content": {
			"font-size": verdictContentTypography.size.mobile,
			"line-height": verdictContentTypography["line-height"].mobile,
			"letter-spacing": verdictContentTypography["letter-spacing"].mobile,
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
