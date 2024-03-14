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
		imgURL,
		contentAlignment,
		ctaBGColor,
		ctaBgGradient,
		overlayOpacity,
		imagePosition,
		ctaTitleColor,
		ctaTextColor,
		ctaTitleTypography,
		ctaContentTypography,
		ctaBorder,
		ctaBorderWidth,
		ctaBgColorType,
		ctaBorderRadius,
		ctaBoxPadding,
		ctaMargin,
		ctaBoxShadow,
		ctaButtonAlignment,
	} = attributes;

	var selectors = {};
	var mobile_selectors = {};
	var tablet_selectors = {};

	const position =
		imagePosition === "center"
			? "center center"
			: imagePosition === "centerLeft"
			? "center left"
			: imagePosition === "centerRight"
			? "center right"
			: imagePosition === "topCenter"
			? "top center"
			: imagePosition === "topLeft"
			? "top left"
			: imagePosition === "topRight"
			? "top right"
			: imagePosition === "bottomCenter"
			? "bottom center"
			: imagePosition === "bottomLeft"
			? "bottom left"
			: imagePosition === "bottomRight"
			? "bottom right"
			: "";

	const gradientColor =
		ctaBgColorType === "solid" ? ctaBGColor : ctaBgGradient.gradient;

	selectors = {
		" .layout-type-1": {
			"background-image": "url(" + imgURL + ")",
		},
		" .layout-type-3": {
			"background-image": "url(" + imgURL + ")",
		},
		" .image-wrapper": {
			"background-image": "url(" + imgURL + ")",
			"background-position": position,
		},
		" .bg-color": {
			background: gradientColor,
		},

		".wp-block-affiliatex-cta > div": {
			"background-size": "cover",
			"background-repeat": "no-repeat",
			"background-position": position,
			"border-style": ctaBorder.style,
			"border-width":
				ctaBorderWidth.top +
				" " +
				ctaBorderWidth.right +
				" " +
				ctaBorderWidth.bottom +
				" " +
				ctaBorderWidth.left,
			"border-radius":
				ctaBorderRadius.desktop.top +
				" " +
				ctaBorderRadius.desktop.left +
				" " +
				" " +
				ctaBorderRadius.desktop.right +
				" " +
				ctaBorderRadius.desktop.bottom,
			"border-color": ctaBorder.color.color,
			"padding-top": ctaBoxPadding.desktop.top,
			"padding-left": ctaBoxPadding.desktop.left,
			"padding-right": ctaBoxPadding.desktop.right,
			"padding-bottom": ctaBoxPadding.desktop.bottom,
			"margin-top": ctaMargin.desktop.top,
			"margin-left": ctaMargin.desktop.left,
			"margin-right": ctaMargin.desktop.right,
			"margin-bottom": ctaMargin.desktop.bottom,
		},

		".wp-block-affiliatex-cta h2": {
			color: ctaTitleColor,
			"font-family": ctaTitleTypography.family,
			"font-weight": fontWeightVariation(ctaTitleTypography.variation),
			"font-style": fontStyle(ctaTitleTypography.variation),
			"font-size": ctaTitleTypography.size.desktop,
			"line-height": ctaTitleTypography["line-height"].desktop,
			"text-transform": ctaTitleTypography["text-transform"],
			"text-decoration": ctaTitleTypography["text-decoration"],
			"letter-spacing": ctaTitleTypography["letter-spacing"].desktop,
			"text-align": contentAlignment,
		},

		".wp-block-affiliatex-cta .affliatex-cta-content": {
			color: ctaTextColor,
			"font-family": ctaContentTypography.family,
			"font-weight": fontWeightVariation(ctaContentTypography.variation),
			"font-style": fontStyle(ctaContentTypography.variation),
			"font-size": ctaContentTypography.size.desktop,
			"line-height": ctaContentTypography["line-height"].desktop,
			"text-transform": ctaContentTypography["text-transform"],
			"text-decoration": ctaContentTypography["text-decoration"],
			"letter-spacing": ctaContentTypography["letter-spacing"].desktop,
			"text-align": contentAlignment,
		},

		" .img-opacity::before": {
			opacity: overlayOpacity,
		},

		".wp-block-affiliatex-cta .layout-type-2": {
			"padding-top": "0px",
			"padding-left": "0px",
			"padding-right": "0px",
			"padding-bottom": "0px",
		},

		".wp-block-affiliatex-cta .layout-type-2 .content-wrapper": {
			background: gradientColor,
			"padding-top": ctaBoxPadding.desktop.top,
			"padding-left": ctaBoxPadding.desktop.left,
			"padding-right": ctaBoxPadding.desktop.right,
			"padding-bottom": ctaBoxPadding.desktop.bottom,
		},
		" .button-wrapper": {
			"justify-content": ctaButtonAlignment,
		},
	};

	selectors[".wp-block-affiliatex-cta > div"]["box-shadow"] = cssBoxShadow(
		ctaBoxShadow
	)
		? cssBoxShadow(ctaBoxShadow)
		: "";

	tablet_selectors = {
		".wp-block-affiliatex-cta > div": {
			"font-size": ctaTitleTypography.size.tablet,
			"border-radius":
				ctaBorderRadius.tablet.top +
				" " +
				ctaBorderRadius.tablet.left +
				" " +
				" " +
				ctaBorderRadius.tablet.right +
				" " +
				ctaBorderRadius.tablet.bottom,
			"line-height": ctaTitleTypography["line-height"].tablet,
			"letter-spacing": ctaTitleTypography["letter-spacing"].tablet,
			"padding-top": ctaBoxPadding.tablet.top,
			"padding-left": ctaBoxPadding.tablet.left,
			"padding-right": ctaBoxPadding.tablet.right,
			"padding-bottom": ctaBoxPadding.tablet.bottom,
			"margin-top": ctaMargin.tablet.top,
			"margin-left": ctaMargin.tablet.left,
			"margin-right": ctaMargin.tablet.right,
			"margin-bottom": ctaMargin.tablet.bottom,
		},
		".wp-block-affiliatex-cta .affliatex-cta-content": {
			"font-size": ctaContentTypography.size.tablet,
			"line-height": ctaContentTypography["line-height"].tablet,
			"letter-spacing": ctaContentTypography["letter-spacing"].tablet,
		},
		".wp-block-affiliatex-cta h2": {
			"font-size": ctaTitleTypography.size.tablet,
			"line-height": ctaTitleTypography["line-height"].tablet,
			"letter-spacing": ctaTitleTypography["letter-spacing"].tablet,
		},

		".wp-block-affiliatex-cta .affliatex-cta-content": {
			"font-size": ctaContentTypography.size.tablet,
			"line-height": ctaContentTypography["line-height"].tablet,
			"letter-spacing": ctaContentTypography["letter-spacing"].tablet,
		},
		".wp-block-affiliatex-cta .layout-type-2": {
			"padding-top": "0px",
			"padding-left": "0px",
			"padding-right": "0px",
			"padding-bottom": "0px",
		},

		".wp-block-affiliatex-cta .layout-type-2 .content-wrapper": {
			"padding-top": ctaBoxPadding.tablet.top,
			"padding-left": ctaBoxPadding.tablet.left,
			"padding-right": ctaBoxPadding.tablet.right,
			"padding-bottom": ctaBoxPadding.tablet.bottom,
		},
	};

	mobile_selectors = {
		".wp-block-affiliatex-cta > div": {
			"padding-top": ctaBoxPadding.mobile.top,
			"border-radius":
				ctaBorderRadius.mobile.top +
				" " +
				ctaBorderRadius.mobile.left +
				" " +
				" " +
				ctaBorderRadius.mobile.right +
				" " +
				ctaBorderRadius.mobile.bottom,
			"padding-left": ctaBoxPadding.mobile.left,
			"padding-right": ctaBoxPadding.mobile.right,
			"padding-bottom": ctaBoxPadding.mobile.bottom,
			"margin-top": ctaMargin.mobile.top,
			"margin-left": ctaMargin.mobile.left,
			"margin-right": ctaMargin.mobile.right,
			"margin-bottom": ctaMargin.mobile.bottom,
		},
		".wp-block-affiliatex-cta .affliatex-cta-content": {
			"font-size": ctaContentTypography.size.mobile,
			"line-height": ctaContentTypography["line-height"].mobile,
			"letter-spacing": ctaContentTypography["letter-spacing"].mobile,
		},
		".wp-block-affiliatex-cta h2": {
			"font-size": ctaTitleTypography.size.mobile,
			"line-height": ctaTitleTypography["line-height"].mobile,
			"letter-spacing": ctaTitleTypography["letter-spacing"].mobile,
		},

		".wp-block-affiliatex-cta .affliatex-cta-content": {
			"font-size": ctaContentTypography.size.mobile,
			"line-height": ctaContentTypography["line-height"].mobile,
			"letter-spacing": ctaContentTypography["letter-spacing"].mobile,
		},
		".wp-block-affiliatex-cta .layout-type-2": {
			"padding-top": "0px",
			"padding-left": "0px",
			"padding-right": "0px",
			"padding-bottom": "0px",
		},

		".wp-block-affiliatex-cta .layout-type-2 .content-wrapper": {
			"padding-top": ctaBoxPadding.mobile.top,
			"padding-left": ctaBoxPadding.mobile.left,
			"padding-right": ctaBoxPadding.mobile.right,
			"padding-bottom": ctaBoxPadding.mobile.bottom,
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
