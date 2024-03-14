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
		productTitleAlign,
		productTitleTypography,
		productTitleColor,
		productSubtitleAlign,
		productSubtitleTypography,
		productSubtitleColor,
		productBorderWidth,
		productShadow,
		productContentTypography,
		productContentAlign,
		productContentColor,
		pricingTypography,
		productBorder,
		productDivider,
		productBgColorType,
		productBGColor,
		productBgGradient,
		contentSpacing,
		pricingHoverColor,
		pricingColor,
		contentMargin,
		ribbonBgColorType,
		ribbonBGColor,
		ribbonBgGradient,
		ribbonColor,
		ribbonContentTypography,
		iconColor,
		productRateNumBgColor,
		productRateContentBgColor,
		productRateContentColor,
		productRateNumberColor,
		productBorderRadius,
		ribbonAlign,
		numRatingTypography,
		productImageWidth,
		productImageExternal,
		productImageType,
		productImageCustomWidth,
		imagePadding,
	} = attributes;

	var selectors = {};
	var mobile_selectors = {};
	var tablet_selectors = {};

	const gradientColor =
		productBgColorType === "solid"
			? productBGColor
			: productBgGradient.gradient;
	const ribbonGradientColor =
		ribbonBgColorType === "solid"
			? ribbonBGColor
			: ribbonBgGradient.gradient;

	selectors = {
		" .affx-single-product-wrapper": {
			"border-width":
				productBorderWidth.desktop.top +
				" " +
				productBorderWidth.desktop.right +
				" " +
				productBorderWidth.desktop.bottom +
				" " +
				productBorderWidth.desktop.left,
			"border-radius":
				productBorderRadius.desktop.top +
				" " +
				productBorderRadius.desktop.right +
				" " +
				productBorderRadius.desktop.bottom +
				" " +
				productBorderRadius.desktop.left,
			"border-style": productBorder.style,
			"border-color": productBorder.color.color,
			background: gradientColor,
			"margin-top": contentMargin.desktop.top,
			"margin-left": contentMargin.desktop.left,
			"margin-right": contentMargin.desktop.right,
			"margin-bottom": contentMargin.desktop.bottom,
		},
		" .affx-single-product-title": {
			"text-align": productTitleAlign,
			"font-family": productTitleTypography.family,
			"font-weight": fontWeightVariation(
				productTitleTypography.variation
			),
			"font-style": fontStyle(productTitleTypography.variation),
			"font-size": productTitleTypography.size.desktop,
			"line-height": productTitleTypography["line-height"].desktop,
			"letter-spacing": productTitleTypography["letter-spacing"].desktop,
			"text-transform": productTitleTypography["text-transform"],
			"text-decoration": productTitleTypography["text-decoration"],
			color: productTitleColor,
		},
		" .affx-single-product-subtitle": {
			"text-align": productSubtitleAlign,
			"font-family": productSubtitleTypography.family,
			"font-weight": fontWeightVariation(
				productSubtitleTypography.variation
			),
			"font-style": fontStyle(productSubtitleTypography.variation),
			"font-size": productSubtitleTypography.size.desktop,
			"line-height": productSubtitleTypography["line-height"].desktop,
			"letter-spacing":
				productSubtitleTypography["letter-spacing"].desktop,
			"text-transform": productSubtitleTypography["text-transform"],
			"text-decoration": productSubtitleTypography["text-decoration"],
			color: productSubtitleColor,
		},
		" .affx-single-product-content": {
			"text-align": productContentAlign,
			"font-family": productContentTypography.family,
			"font-weight": fontWeightVariation(
				productContentTypography.variation
			),
			"font-style": fontStyle(productContentTypography.variation),
			"font-size": productContentTypography.size.desktop,
			"line-height": productContentTypography["line-height"].desktop,
			"text-transform": productContentTypography["text-transform"],
			"text-decoration": productContentTypography["text-decoration"],
			"letter-spacing":
				productContentTypography["letter-spacing"].desktop,
			color: productContentColor,
		},
		" .affx-single-product-content p": {
			color: productContentColor,
			"text-align": productContentAlign,
			"font-family": productContentTypography.family,
			"font-weight": fontWeightVariation(
				productContentTypography.variation
			),
			"font-style": fontStyle(productContentTypography.variation),
			"font-size": productContentTypography.size.desktop,
			"line-height": productContentTypography["line-height"].desktop,
			"text-transform": productContentTypography["text-transform"],
			"text-decoration": productContentTypography["text-decoration"],
			"letter-spacing":
				productContentTypography["letter-spacing"].desktop,
		},
		" .affx-single-product-content ul li": {
			color: productContentColor,
			"text-align": productContentAlign,
			"font-family": productContentTypography.family,
			"font-weight": fontWeightVariation(
				productContentTypography.variation
			),
			"font-style": fontStyle(productContentTypography.variation),
			"font-size": productContentTypography.size.desktop,
			"line-height": productContentTypography["line-height"].desktop,
			"text-transform": productContentTypography["text-transform"],
			"text-decoration": productContentTypography["text-decoration"],
			"letter-spacing":
				productContentTypography["letter-spacing"].desktop,
		},
		" .affx-sp-marked-price": {
			"font-family": pricingTypography.family,
			"font-weight": fontWeightVariation(pricingTypography.variation),
			"font-style": fontStyle(pricingTypography.variation),
			"font-size": pricingTypography.size.desktop,
			"line-height": pricingTypography["line-height"].desktop,
			"text-transform": pricingTypography["text-transform"],
			"text-decoration": pricingTypography["text-decoration"],
			"letter-spacing": pricingTypography["letter-spacing"].desktop,
		},
		" .affx-sp-sale-price": {
			"font-family": pricingTypography.family,
			"font-weight": fontWeightVariation(pricingTypography.variation),
			"font-style": fontStyle(pricingTypography.variation),
			"font-size": pricingTypography.size.desktop,
			"line-height": pricingTypography["line-height"].desktop,
			"text-transform": pricingTypography["text-transform"],
			// "text-decoration": pricingTypography["text-decoration"],
			"letter-spacing": pricingTypography["letter-spacing"].desktop,
		},

		" .affx-sp-content-wrapper": {
			"padding-top": contentSpacing.desktop.top,
			"padding-left": contentSpacing.desktop.left,
			"padding-right": contentSpacing.desktop.right,
			"padding-bottom": contentSpacing.desktop.bottom,
		},
		" .affx-sp-price": {
			color: pricingColor,
		},
		" .affx-sp-price .affx-sp-sale-price": {
			color: pricingHoverColor,
		},
		" .affx-sp-rating-number": {
			width: "100px",
		},

		" .affx-sp-content-wrapper .title-wrapper": {
			"border-style": productDivider.style,
			"border-color": productDivider.color.color,
			"border-bottom-width": productDivider.width + "px",
		},

		" .affx-single-product-wrapper.product-layout-2 .title-wrapper": {
			"padding-top": contentSpacing.desktop.top,
			"padding-left": contentSpacing.desktop.left,
			"padding-right": contentSpacing.desktop.right,
			"padding-bottom": "0",
		},

		" .affx-single-product-wrapper.product-layout-2 .affx-sp-price": {
			"padding-left": contentSpacing.desktop.left,
			"padding-right": contentSpacing.desktop.right,
		},

		" .affx-single-product-wrapper.product-layout-2 .button-wrapper": {
			"padding-left": contentSpacing.desktop.left,
			"padding-right": contentSpacing.desktop.right,
			"padding-bottom": contentSpacing.desktop.bottom,
		},

		" .affx-single-product-wrapper.product-layout-2 .affx-single-product-content": {
			"padding-left": contentSpacing.desktop.left,
			"padding-right": contentSpacing.desktop.right,
		},

		" .affx-single-product-wrapper.product-layout-3 .affx-sp-inner": {
			"padding-top": contentSpacing.desktop.top,
			"padding-left": contentSpacing.desktop.left,
			"padding-right": contentSpacing.desktop.right,
			"padding-bottom": contentSpacing.desktop.bottom,
		},

		" .affx-single-product-wrapper.product-layout-3 .affx-sp-content.image-right .affx-sp-content-wrapper": {
			"padding-top": "0",
			"padding-left": "24px",
			"padding-right": "24px",
			"padding-bottom": "0",
		},
		" .affx-single-product-wrapper.product-layout-3 .affx-sp-content.image-left .affx-sp-content-wrapper": {
			"padding-top": "0",
			"padding-left": "24px",
			"padding-right": "0",
			"padding-bottom": "0",
		},
		" .affx-single-product-wrapper.product-layout-3 .affx-sp-content.image-left .button-wrapper": {
			"padding-left": "24px",
		},

		" .affx-sp-ribbon": {
			width: "100%",
			"text-align": ribbonAlign,
		},

		" .affx-sp-ribbon-title": {
			background: ribbonGradientColor,
			"font-family": ribbonContentTypography.family,
			"font-weight": fontWeightVariation(
				ribbonContentTypography.variation
			),
			"font-style": fontStyle(ribbonContentTypography.variation),
			"font-size": ribbonContentTypography.size.desktop,
			"line-height": ribbonContentTypography["line-height"].desktop,
			"text-transform": ribbonContentTypography["text-transform"],
			"text-decoration": ribbonContentTypography["text-decoration"],
			"letter-spacing": ribbonContentTypography["letter-spacing"].desktop,
			color: ribbonColor,
		},

		" .affx-sp-ribbon.ribbon-layout-2 .affx-sp-ribbon-title:before": {
			"border-bottom-color": ribbonGradientColor,
		},

		" .affx-sp-content.image-right .affx-sp-ribbon.ribbon-layout-2 .affx-sp-ribbon-title:before": {
			"border-bottom-color": "transparent",
			"border-right-color": ribbonGradientColor,
		},

		" .affx-sp-content.image-right .affx-sp-ribbon.ribbon-layout-2 .affx-sp-ribbon-title:hover:before": {
			"border-bottom-color": "transparent",
			"border-right-color": ribbonGradientColor,
		},

		" .affiliatex-icon li:before": {
			color: iconColor,
		},

		" .affx-rating-number": {
			background: productRateNumBgColor,
			color: productRateNumberColor,
			"font-family": numRatingTypography.family,
			"font-weight": fontWeightVariation(numRatingTypography.variation),
			"font-style": fontStyle(numRatingTypography.variation),
			"font-size": numRatingTypography.size.desktop,
			"line-height": numRatingTypography["line-height"].desktop,
			"text-transform": numRatingTypography["text-transform"],
			"text-decoration": numRatingTypography["text-decoration"],
			"letter-spacing": numRatingTypography["letter-spacing"].desktop,
		},
		" .affx-rating-number .num": {
			background: productRateNumBgColor,
			color: productRateNumberColor,
		},
		" .affx-rating-number .label": {
			background: productRateContentBgColor,
			color: productRateContentColor,
			"font-size": "0.444em",
		},
		" .affx-rating-number .label::before": {
			"border-bottom-color": productRateContentBgColor,
		},

		" .affx-rating-input-content:before": {
			"border-bottom-color": productRateContentBgColor,
		},

		" .affx-rating-input-content input": {
			color: productRateContentColor,
			"font-family": numRatingTypography.family,
			"font-weight": fontWeightVariation(numRatingTypography.variation),
			"font-style": fontStyle(numRatingTypography.variation),
			"font-size": numRatingTypography.size.desktop,
			"line-height": numRatingTypography["line-height"].desktop,
			"text-transform": numRatingTypography["text-transform"],
			"text-decoration": numRatingTypography["text-decoration"],
			"letter-spacing": numRatingTypography["letter-spacing"].desktop,
		},
		" .affx-single-product-wrapper.product-layout-1 .affx-sp-img-wrapper": {},
		" .affx-single-product-wrapper.product-layout-3 .affx-sp-img-wrapper": {},

		" .affx-single-product-wrapper .affx-sp-img-wrapper": {
			"padding-top": imagePadding.desktop.top,
			"padding-left": imagePadding.desktop.left,
			"padding-right": imagePadding.desktop.right,
			"padding-bottom": imagePadding.desktop.bottom,
		},
	};

	selectors[
		" .affx-single-product-wrapper.product-layout-1 .affx-sp-img-wrapper"
	].flex =
		productImageWidth === "custom"
			? "0 0 " + productImageCustomWidth + "%"
			: "";
	selectors[
		" .affx-single-product-wrapper.product-layout-3 .affx-sp-img-wrapper"
	].flex =
		productImageWidth === "custom"
			? "0 0 " + productImageCustomWidth + "%"
			: "";

	selectors[" .affx-single-product-wrapper"]["box-shadow"] = cssBoxShadow(
		productShadow
	)
		? cssBoxShadow(productShadow)
		: "";

	mobile_selectors = {
		" .affx-single-product-wrapper": {
			"border-width":
				productBorderWidth.mobile.top +
				" " +
				productBorderWidth.mobile.right +
				" " +
				productBorderWidth.mobile.bottom +
				" " +
				productBorderWidth.mobile.left,
			"border-radius":
				productBorderRadius.mobile.top +
				" " +
				productBorderRadius.mobile.right +
				" " +
				productBorderRadius.mobile.bottom +
				" " +
				productBorderRadius.mobile.left,
			"margin-top": contentMargin.mobile.top,
			"margin-left": contentMargin.mobile.left,
			"margin-right": contentMargin.mobile.right,
			"margin-bottom": contentMargin.mobile.bottom,
		},

		" .affx-single-product-title": {
			"font-size": productTitleTypography.size.mobile,
			"line-height": productTitleTypography["line-height"].mobile,
			"letter-spacing": productTitleTypography["letter-spacing"].mobile,
		},

		" .affx-single-product-subtitle": {
			"font-size": productSubtitleTypography.size.mobile,
			"line-height": productSubtitleTypography["line-height"].mobile,
			"letter-spacing":
				productSubtitleTypography["letter-spacing"].mobile,
		},

		" .affx-single-product-content": {
			"font-size": productContentTypography.size.mobile,
			"line-height": productContentTypography["line-height"].mobile,
			"letter-spacing": productContentTypography["letter-spacing"].mobile,
		},

		" .affx-sp-marked-price": {
			"font-size": pricingTypography.size.mobile,
			"line-height": pricingTypography["line-height"].mobile,
			"letter-spacing": pricingTypography["letter-spacing"].mobile,
		},

		" .affx-sp-sale-price": {
			"font-size": pricingTypography.size.mobile,
			"line-height": pricingTypography["line-height"].mobile,
			"letter-spacing": pricingTypography["letter-spacing"].mobile,
		},

		" .affx-sp-content-wrapper": {
			"padding-top": contentSpacing.mobile.top,
			"padding-left": contentSpacing.mobile.left,
			"padding-right": contentSpacing.mobile.right,
			"padding-bottom": contentSpacing.mobile.bottom,
		},

		" .affx-single-product-wrapper.product-layout-2 .title-wrapper": {
			"padding-top": contentSpacing.mobile.top,
			"padding-left": contentSpacing.mobile.left,
			"padding-right": contentSpacing.mobile.right,
			"padding-bottom": "0",
		},

		" .affx-single-product-wrapper.product-layout-2 .affx-sp-price": {
			"padding-left": contentSpacing.mobile.left,
			"padding-right": contentSpacing.mobile.right,
		},

		" .affx-single-product-wrapper.product-layout-2 .button-wrapper": {
			"padding-left": contentSpacing.mobile.left,
			"padding-right": contentSpacing.mobile.right,
			"padding-bottom": contentSpacing.mobile.bottom,
		},

		" .affx-single-product-wrapper.product-layout-2 .affx-single-product-content": {
			"padding-left": contentSpacing.mobile.left,
			"padding-right": contentSpacing.mobile.right,
		},

		" .affx-single-product-wrapper.product-layout-3 .affx-sp-inner": {
			"padding-top": contentSpacing.mobile.top,
			"padding-left": contentSpacing.mobile.left,
			"padding-right": contentSpacing.mobile.right,
			"padding-bottom": contentSpacing.mobile.bottom,
		},

		" .affx-sp-ribbon-title": {
			"font-size": ribbonContentTypography.size.mobile,
			"line-height": ribbonContentTypography["line-height"].mobile,
			"letter-spacing": ribbonContentTypography["letter-spacing"].mobile,
		},

		" .affx-single-product-wrapper.product-layout-3 .affx-sp-content-wrapper": {
			"padding-top": "0",
			"padding-left": "0",
			"padding-right": "0",
			"padding-bottom": "0",
		},

		" .affx-single-product-wrapper.product-layout-3 .affx-sp-content.image-left .button-wrapper": {
			"padding-left": "0",
		},

		" .affx-rating-input-number": {
			"font-size": numRatingTypography.size.mobile,
			"line-height": numRatingTypography["line-height"].mobile,
			"letter-spacing": numRatingTypography["letter-spacing"].mobile,
		},

		" .affx-rating-input-number input": {
			"font-size": numRatingTypography.size.mobile,
			"line-height": numRatingTypography["line-height"].mobile,
			"letter-spacing": numRatingTypography["letter-spacing"].mobile,
		},

		" .affx-rating-input-content": {
			"font-size": numRatingTypography.size.mobile,
			"line-height": numRatingTypography["line-height"].mobile,
			"letter-spacing": numRatingTypography["letter-spacing"].mobile,
		},

		" .affx-rating-input-content input": {
			"font-size": numRatingTypography.size.mobile,
			"line-height": numRatingTypography["line-height"].mobile,
			"letter-spacing": numRatingTypography["letter-spacing"].mobile,
		},

		" .affx-single-product-wrapper .affx-sp-img-wrapper": {
			"padding-top": imagePadding.mobile.top,
			"padding-left": imagePadding.mobile.left,
			"padding-right": imagePadding.mobile.right,
			"padding-bottom": imagePadding.mobile.bottom,
		},
	};

	tablet_selectors = {
		" .affx-single-product-wrapper": {
			"border-width":
				productBorderWidth.tablet.top +
				" " +
				productBorderWidth.tablet.right +
				" " +
				productBorderWidth.tablet.bottom +
				" " +
				productBorderWidth.tablet.left,
			"border-radius":
				productBorderRadius.tablet.top +
				" " +
				productBorderRadius.tablet.right +
				" " +
				productBorderRadius.tablet.bottom +
				" " +
				productBorderRadius.tablet.left,
			"margin-top": contentMargin.tablet.top,
			"margin-left": contentMargin.tablet.left,
			"margin-right": contentMargin.tablet.right,
			"margin-bottom": contentMargin.tablet.bottom,
		},
		" .affx-single-product-title": {
			"font-size": productTitleTypography.size.tablet,
			"line-height": productTitleTypography["line-height"].tablet,
			"letter-spacing": productTitleTypography["letter-spacing"].tablet,
		},

		" .affx-single-product-subtitle": {
			"font-size": productSubtitleTypography.size.tablet,
			"line-height": productSubtitleTypography["line-height"].tablet,
			"letter-spacing":
				productSubtitleTypography["letter-spacing"].tablet,
		},

		" .affx-single-product-content": {
			"font-size": productContentTypography.size.tablet,
			"line-height": productContentTypography["line-height"].tablet,
			"letter-spacing": productContentTypography["letter-spacing"].tablet,
		},

		" .affx-sp-marked-price": {
			"font-size": pricingTypography.size.tablet,
			"line-height": pricingTypography["line-height"].tablet,
			"letter-spacing": pricingTypography["letter-spacing"].tablet,
		},
		" .affx-sp-sale-price": {
			"font-size": pricingTypography.size.tablet,
			"line-height": pricingTypography["line-height"].tablet,
			"letter-spacing": pricingTypography["letter-spacing"].tablet,
		},

		" .affx-sp-content-wrapper": {
			"padding-top": contentSpacing.tablet.top,
			"padding-left": contentSpacing.tablet.left,
			"padding-right": contentSpacing.tablet.right,
			"padding-bottom": contentSpacing.tablet.bottom,
		},

		" .affx-single-product-wrapper.product-layout-2 .title-wrapper": {
			"padding-top": contentSpacing.tablet.top,
			"padding-left": contentSpacing.tablet.left,
			"padding-right": contentSpacing.tablet.right,
			"padding-bottom": "0",
		},

		" .affx-single-product-wrapper.product-layout-2 .affx-sp-price": {
			"padding-left": contentSpacing.tablet.left,
			"padding-right": contentSpacing.tablet.right,
		},

		" .affx-single-product-wrapper.product-layout-2 .button-wrapper": {
			"padding-left": contentSpacing.tablet.left,
			"padding-right": contentSpacing.tablet.right,
			"padding-bottom": contentSpacing.tablet.bottom,
		},

		" .affx-single-product-wrapper.product-layout-2 .affx-single-product-content": {
			"padding-left": contentSpacing.tablet.left,
			"padding-right": contentSpacing.tablet.right,
		},

		" .affx-single-product-wrapper.product-layout-3 .affx-sp-inner": {
			"padding-top": contentSpacing.tablet.top,
			"padding-left": contentSpacing.tablet.left,
			"padding-right": contentSpacing.tablet.right,
			"padding-bottom": contentSpacing.tablet.bottom,
		},

		" .affx-sp-ribbon-title": {
			"font-size": ribbonContentTypography.size.tablet,
			"line-height": ribbonContentTypography["line-height"].tablet,
			"letter-spacing": ribbonContentTypography["letter-spacing"].tablet,
		},

		" .affx-rating-input-number": {
			"font-size": numRatingTypography.size.tablet,
			"line-height": numRatingTypography["line-height"].tablet,
			"letter-spacing": numRatingTypography["letter-spacing"].tablet,
		},

		" .affx-rating-input-number input": {
			"font-size": numRatingTypography.size.tablet,
			"line-height": numRatingTypography["line-height"].tablet,
			"letter-spacing": numRatingTypography["letter-spacing"].tablet,
		},

		" .affx-rating-input-content": {
			"font-size": numRatingTypography.size.tablet,
			"line-height": numRatingTypography["line-height"].tablet,
			"letter-spacing": numRatingTypography["letter-spacing"].tablet,
		},

		" .affx-rating-input-content input": {
			"font-size": numRatingTypography.size.tablet,
			"line-height": numRatingTypography["line-height"].tablet,
			"letter-spacing": numRatingTypography["letter-spacing"].tablet,
		},

		" .affx-single-product-wrapper .affx-sp-img-wrapper": {
			"padding-top": imagePadding.tablet.top,
			"padding-left": imagePadding.tablet.left,
			"padding-right": imagePadding.tablet.right,
			"padding-bottom": imagePadding.tablet.bottom,
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
