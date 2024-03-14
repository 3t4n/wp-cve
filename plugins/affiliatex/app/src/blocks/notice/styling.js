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
		noticeBorder,
		noticeBorderWidth,
		noticeBorderRadius,
		boxShadow,
		alignment,
		titleTypography,
		listTypography,
		noticeTextColor,
		noticeTextTwoColor,
		noticeIconColor,
		noticeIconTwoColor,
		noticeBgType,
		noticeBgColor,
		noticeBgGradient,
		noticeBgTwoType,
		noticeBgTwoGradient,
		noticeBgTwoColor,
		noticeListColor,
		listBgType,
		listBgColor,
		listBgGradient,
		noticeContentType,
		noticeListType,
		noticeMargin,
		titlePadding,
		contentPadding,
		noticePadding,
		noticeunorderedType,
		noticeIconSize,
		noticeListIconSize,
		titleAlignment,
	} = attributes;

	var selectors = {};
	var mobile_selectors = {};
	var tablet_selectors = {};

	selectors = {
		" .affx-notice-inner-wrapper": {
			"border-style": noticeBorder.style,
			"border-width":
				noticeBorderWidth.desktop.top +
				" " +
				noticeBorderWidth.desktop.right +
				" " +
				noticeBorderWidth.desktop.bottom +
				" " +
				noticeBorderWidth.desktop.left,
			"border-color": noticeBorder.color.color,
			"border-radius":
				noticeBorderRadius.desktop.top +
				" " +
				noticeBorderRadius.desktop.right +
				" " +
				noticeBorderRadius.desktop.bottom +
				" " +
				noticeBorderRadius.desktop.left,
			"box-shadow": cssBoxShadow(boxShadow),
			"margin-top": noticeMargin.desktop.top,
			"margin-left": noticeMargin.desktop.left,
			"margin-right": noticeMargin.desktop.right,
			"margin-bottom": noticeMargin.desktop.bottom,
			"text-align": alignment,
		},

		" .affiliatex-notice-title": {
			color: noticeTextColor,
			"font-family": titleTypography.family,
			"font-weight": fontWeightVariation(titleTypography.variation),
			"font-style": fontStyle(titleTypography.variation),
			"font-size": titleTypography.size.desktop,
			"line-height": titleTypography["line-height"].desktop,
			"text-align": titleAlignment,
			"text-transform": titleTypography["text-transform"],
			"text-decoration": titleTypography["text-decoration"],
			"letter-spacing": titleTypography["letter-spacing"].desktop,
			"padding-top": titlePadding.desktop.top,
			"padding-left": titlePadding.desktop.left,
			"padding-right": titlePadding.desktop.right,
			"padding-bottom": titlePadding.desktop.bottom,
		},
		" .affiliatex-notice-icon": {
			color: noticeIconTwoColor,
			"font-size": noticeIconSize + "px",
		},
		" .affiliatex-notice-content": {
			"padding-top": contentPadding.desktop.top,
			"padding-left": contentPadding.desktop.left,
			"padding-right": contentPadding.desktop.right,
			"padding-bottom": contentPadding.desktop.bottom,
		},
		" .affiliatex-notice-content p": {
			"font-family": listTypography.family,
			"font-weight": fontWeightVariation(listTypography.variation),
			"font-style": fontStyle(listTypography.variation),
			"font-size": listTypography.size.desktop,
			"line-height": listTypography["line-height"].desktop,
			"text-transform": listTypography["text-transform"].desktop,
			"text-decoration": listTypography["text-decoration"].desktop,
			"letter-spacing": listTypography["letter-spacing"].desktop,
			color: noticeListColor,
			"text-align": alignment,
		},
		" .affiliatex-notice-content li": {
			"font-family": listTypography.family,
			"font-weight": fontWeightVariation(listTypography.variation),
			"font-style": fontStyle(listTypography.variation),
			"font-size": listTypography.size.desktop,
			"line-height": listTypography["line-height"].desktop,
			"text-transform": listTypography["text-transform"].desktop,
			"text-decoration": listTypography["text-decoration"].desktop,
			"letter-spacing": listTypography["letter-spacing"].desktop,
			color: noticeListColor,
			"justify-content": alignment,
		},
		" .affiliatex-notice-content .affiliatex-list li::marker": {
			color: noticeIconColor,
		},
		" .affiliatex-notice-content .affiliatex-list li:before": {
			color: noticeIconColor,
			"font-size": noticeListIconSize + "px",
		},
		" .affx-notice-inner-wrapper.layout-type-2": {
			"margin-top": noticeMargin.desktop.top,
			"margin-left": noticeMargin.desktop.left,
			"margin-right": noticeMargin.desktop.right,
			"margin-bottom": noticeMargin.desktop.bottom,
			"padding-top": noticePadding.desktop.top,
			"padding-left": noticePadding.desktop.left,
			"padding-right": noticePadding.desktop.right,
			"padding-bottom": noticePadding.desktop.bottom,
		},
		" .affx-notice-inner-wrapper.layout-type-2 .affiliatex-notice-title": {
			color: noticeTextTwoColor,
			"padding-top": "0px",
			"padding-left": "0px",
			"padding-right": "0px",
			"padding-bottom": "10px",
		},
		" .affx-notice-inner-wrapper.layout-type-1 .affiliatex-notice-title:before": {
			"font-size": noticeIconSize + "px",
		},
		" .affx-notice-inner-wrapper.layout-type-2 .affiliatex-notice-title:before": {
			color: noticeIconTwoColor,
			"font-size": noticeIconSize + "px",
		},

		" .affx-notice-inner-wrapper.layout-type-2 .affiliatex-notice-content": {
			"padding-top": "0px",
			"padding-left": "0px",
			"padding-right": "0px",
			"padding-bottom": "0px",
		},
		" .affx-notice-inner-wrapper.layout-type-3": {
			"margin-top": noticeMargin.desktop.top,
			"margin-left": noticeMargin.desktop.left,
			"margin-right": noticeMargin.desktop.right,
			"margin-bottom": noticeMargin.desktop.bottom,
			"padding-top": noticePadding.desktop.top,
			"padding-left": noticePadding.desktop.left,
			"padding-right": noticePadding.desktop.right,
			"padding-bottom": noticePadding.desktop.bottom,
		},
		" .affx-notice-inner-wrapper.layout-type-3 .affiliatex-notice-title": {
			color: noticeTextTwoColor,
			"padding-top": "0px",
			"padding-left": "0px",
			"padding-right": "0px",
			"padding-bottom": "10px",
		},
		" .affx-notice-inner-wrapper.layout-type-3 .affiliatex-notice-content": {
			"padding-top": "0px",
			"padding-left": "0px",
			"padding-right": "0px",
			"padding-bottom": "0px",
		},

		" .affiliatex-notice-content .affiliatex-list": {},
		" .affx-notice-inner-wrapper.layout-type-1 .affiliatex-notice-title": {},
		" .affx-notice-inner-wrapper.layout-type-1 .affiliatex-notice-content": {},
	};
	selectors[
		" .affx-notice-inner-wrapper.layout-type-1 .affiliatex-notice-title"
	].background =
		noticeBgType === "solid" ? noticeBgColor : noticeBgGradient.gradient;
	selectors[
		" .affx-notice-inner-wrapper.layout-type-1 .affiliatex-notice-content"
	].background =
		listBgType === "solid" ? listBgColor : listBgGradient.gradient;
	selectors[" .affx-notice-inner-wrapper.layout-type-2"].background =
		noticeBgTwoType === "solid"
			? noticeBgTwoColor
			: noticeBgTwoGradient.gradient;
	selectors[" .affx-notice-inner-wrapper.layout-type-3"].background =
		noticeBgTwoType === "solid"
			? noticeBgTwoColor
			: noticeBgTwoGradient.gradient;
	selectors[" .affiliatex-notice-content .affiliatex-list"]["list-style"] =
		noticeContentType == "list" &&
		noticeListType == "unordered" &&
		noticeunorderedType == "icon"
			? "none"
			: "";

	tablet_selectors = {
		" .affiliatex-notice-title": {
			"font-size": titleTypography.size.tablet,
			"line-height": titleTypography["line-height"],
			"letter-spacing": titleTypography["letter-spacing"],
			"padding-top": titlePadding.tablet.top,
			"padding-left": titlePadding.tablet.left,
			"padding-right": titlePadding.tablet.right,
			"padding-bottom": titlePadding.tablet.bottom,
		},

		" .affiliatex-notice-content": {
			"padding-top": contentPadding.tablet.top,
			"padding-left": contentPadding.tablet.left,
			"padding-right": contentPadding.tablet.right,
			"padding-bottom": contentPadding.tablet.bottom,
		},

		" .affiliatex-notice-content p": {
			"font-size": listTypography.size.tablet,
			"line-height": listTypography["line-height"],
			"letter-spacing": listTypography["letter-spacing"],
		},
		" .affiliatex-notice-content li": {
			"font-size": listTypography.size.tablet,
			"line-height": listTypography["line-height"],
			"letter-spacing": listTypography["letter-spacing"],
		},

		" .affx-notice-inner-wrapper": {
			"border-width":
				noticeBorderWidth.tablet.top +
				" " +
				noticeBorderWidth.tablet.right +
				" " +
				noticeBorderWidth.tablet.bottom +
				" " +
				noticeBorderWidth.tablet.left,
			"border-radius":
				noticeBorderRadius.tablet.top +
				" " +
				noticeBorderRadius.tablet.right +
				" " +
				noticeBorderRadius.tablet.bottom +
				" " +
				noticeBorderRadius.tablet.left,
			"margin-top": noticeMargin.tablet.top,
			"margin-left": noticeMargin.tablet.left,
			"margin-right": noticeMargin.tablet.right,
			"margin-bottom": noticeMargin.tablet.bottom,
		},

		" .affx-notice-inner-wrapper.layout-type-3": {
			"margin-top": noticeMargin.tablet.top,
			"margin-left": noticeMargin.tablet.left,
			"margin-right": noticeMargin.tablet.right,
			"margin-bottom": noticeMargin.tablet.bottom,
			"padding-top": noticePadding.tablet.top,
			"padding-left": noticePadding.tablet.left,
			"padding-right": noticePadding.tablet.right,
			"padding-bottom": noticePadding.tablet.bottom,
		},

		" .affx-notice-inner-wrapper.layout-type-2": {
			"margin-top": noticeMargin.tablet.top,
			"margin-left": noticeMargin.tablet.left,
			"margin-right": noticeMargin.tablet.right,
			"margin-bottom": noticeMargin.tablet.bottom,
			"padding-top": noticePadding.tablet.top,
			"padding-left": noticePadding.tablet.left,
			"padding-right": noticePadding.tablet.right,
			"padding-bottom": noticePadding.tablet.bottom,
		},

		" .affx-notice-inner-wrapper.layout-type-2 .affiliatex-notice-title": {
			"padding-top": "0px",
			"padding-left": "0px",
			"padding-right": "0px",
			"padding-bottom": "10px",
		},
		" .affx-notice-inner-wrapper.layout-type-2 .affiliatex-notice-content": {
			"padding-top": "0px",
			"padding-left": "0px",
			"padding-right": "0px",
			"padding-bottom": "0px",
		},

		" .affx-notice-inner-wrapper.layout-type-3 .affiliatex-notice-title": {
			"padding-top": "0px",
			"padding-left": "0px",
			"padding-right": "0px",
			"padding-bottom": "10px",
		},
		" .affx-notice-inner-wrapper.layout-type-3 .affiliatex-notice-content": {
			"padding-top": "0px",
			"padding-left": "0px",
			"padding-right": "0px",
			"padding-bottom": "0px",
		},
	};

	mobile_selectors = {
		" .affiliatex-notice-title": {
			"font-size": titleTypography.size.mobile,
			"line-height": titleTypography["line-height"],
			"letter-spacing": titleTypography["letter-spacing"],
			"padding-top": titlePadding.mobile.top,
			"padding-left": titlePadding.mobile.left,
			"padding-right": titlePadding.mobile.right,
			"padding-bottom": titlePadding.mobile.bottom,
		},

		" .affiliatex-notice-content": {
			"padding-top": contentPadding.mobile.top,
			"padding-left": contentPadding.mobile.left,
			"padding-right": contentPadding.mobile.right,
			"padding-bottom": contentPadding.mobile.bottom,
		},

		" .affiliatex-notice-content p": {
			"font-size": listTypography.size.mobile,
			"line-height": listTypography["line-height"].mobile,
			"letter-spacing": listTypography["letter-spacing"].mobile,
		},
		" .affiliatex-notice-content li": {
			"font-size": listTypography.size.mobile,
			"line-height": listTypography["line-height"].mobile,
			"letter-spacing": listTypography["letter-spacing"].mobile,
		},

		" .affx-notice-inner-wrapper": {
			"border-width":
				noticeBorderWidth.mobile.top +
				" " +
				noticeBorderWidth.mobile.right +
				" " +
				noticeBorderWidth.mobile.bottom +
				" " +
				noticeBorderWidth.mobile.left,
			"border-radius":
				noticeBorderRadius.mobile.top +
				" " +
				noticeBorderRadius.mobile.right +
				" " +
				noticeBorderRadius.mobile.bottom +
				" " +
				noticeBorderRadius.mobile.left,
			"margin-top": noticeMargin.mobile.top,
			"margin-left": noticeMargin.mobile.left,
			"margin-right": noticeMargin.mobile.right,
			"margin-bottom": noticeMargin.mobile.bottom,
		},

		" .affx-notice-inner-wrapper.layout-type-3": {
			"margin-top": noticeMargin.mobile.top,
			"margin-left": noticeMargin.mobile.left,
			"margin-right": noticeMargin.mobile.right,
			"margin-bottom": noticeMargin.mobile.bottom,
			"padding-top": noticePadding.mobile.top,
			"padding-left": noticePadding.mobile.left,
			"padding-right": noticePadding.mobile.right,
			"padding-bottom": noticePadding.mobile.bottom,
		},

		" .affx-notice-inner-wrapper.layout-type-2": {
			"margin-top": noticeMargin.mobile.top,
			"margin-left": noticeMargin.mobile.left,
			"margin-right": noticeMargin.mobile.right,
			"margin-bottom": noticeMargin.mobile.bottom,
			"padding-top": noticePadding.mobile.top,
			"padding-left": noticePadding.mobile.left,
			"padding-right": noticePadding.mobile.right,
			"padding-bottom": noticePadding.mobile.bottom,
		},

		" .affx-notice-inner-wrapper.layout-type-2 .affiliatex-notice-title": {
			"padding-top": "0px",
			"padding-left": "0px",
			"padding-right": "0px",
			"padding-bottom": "10px",
		},
		" .affx-notice-inner-wrapper.layout-type-2 .affiliatex-notice-content": {
			"padding-top": "0px",
			"padding-left": "0px",
			"padding-right": "0px",
			"padding-bottom": "0px",
		},

		" .affx-notice-inner-wrapper.layout-type-3 .affiliatex-notice-title": {
			"padding-top": "0px",
			"padding-left": "0px",
			"padding-right": "0px",
			"padding-bottom": "10px",
		},
		" .affx-notice-inner-wrapper.layout-type-3 .affiliatex-notice-content": {
			"padding-top": "0px",
			"padding-left": "0px",
			"padding-right": "0px",
			"padding-bottom": "0px",
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
