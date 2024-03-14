/**
 * Returns Dynamic Generated CSS
 */

import generateCSS from "../../../generateCSS";
import generateCSSUnit from "../../../generateCSSUnit";
import { hexToRgba } from "../../../utils/index.js";
import generateBackgroundImageEffect from "../../../generateBackgroundImageEffect";

function EditorStyles(props) {
  const {
    block_id,
    ctaBackColor,
    ctaColor,
    ctaTitleFontFamily,
    ctaTitleFontSize,
    ctaTitleFontSizeMobile,
    ctaTitleFontSizeTablet,
    ctaTextFontFamily,
    ctaTextFontSize,
    ctaTextFontSizeMobile,
    ctaTextFontSizeTablet,
    ctaTextColor,
    backgroundImage,
    ctaTitleFontWeight,
    ctaTitleLineHeight,
    ctaTextFontWeight,
    ctaTextLineHeight,
    ctaVpadding,
    ctaHpadding,
    ctaBorderWidth,
    ctaBorderStyle,
    ctaHoverBackColor,
    ctaHoverColor,
    ctaBorderColor,
    ctaHoverBorderColor,
    titleSpace,
    titleSpaceMobile,
    titleSpaceTablet,
    subtitleSpace,
    subtitleSpaceMobile,
    subtitleSpaceTablet,
    iconSpace,
    opacity,
    backgroundColor,
    backgroundType,
    gradientDirection,
    colorLocation1,
    colorLocation2,
    backgroundColor1,
    backgroundColor2,
    buttonbackgroundType,
    buttongradientDirection,
    buttoncolorLocation1,
    buttoncolorLocation2,
    buttonbackgroundColor1,
    buttonbackgroundColor2,
    buttonSpace,
    buttonSpaceMobile,
    buttonSpaceTablet,
    borderRadius,
    boxShadowColor,
    boxShadowHOffset,
    boxShadowVOffset,
    boxShadowBlur,
    boxShadowSpread,
    boxShadowPosition,
    icon_color,
    blockTopPadding,
    blockTopPaddingMobile,
    blockTopPaddingTablet,
    blockBottomPadding,
    blockBottomPaddingMobile,
    blockBottomPaddingTablet,
    blockLeftPadding,
    blockLeftPaddingMobile,
    blockLeftPaddingTablet,
    blockRightPadding,
    blockRightPaddingMobile,
    blockRightPaddingTablet,
    backgroundImagePosition,
    backgroundImageRepeat,
    backgroundImageSize,
    buttonTextFontFamily,
    buttonTextFontSize,
    buttonTextFontSizeMobile,
    buttonTextFontSizeTablet,
    buttonTextLineHeight,
    buttonTextFontWeight,
    iconPosition,
    ctaBorderRadius,
    ctaHpaddingTablet,
    ctaHpaddingMobile,
    ctaVpaddingTablet,
    ctaVpaddingMobile,
    ctaTextOpacity,
    buttonHbackgroundType,
	buttonBackgroundColor, // For compatibility with v1.3.2.
	hbuttonBackgroundColor, // For compatibility with v1.3.2.
	buttonTextColor, // For compatibility with v1.3.2.
	hbuttonTextColor, // For compatibility with v1.3.2.
	buttonborderHColor, // For compatibility with v1.3.2.
	topPadding, // For compatibility with v1.3.2.
	bottomPadding, // For compatibility with v1.3.2.
	leftPadding, // For compatibility with v1.3.2.
	rightPadding, // For compatibility with v1.3.2.
	imgURL, // For compatibility with v1.3.2.
	imagePosition, // For compatibility with v1.3.2.
	imageRepeat, // For compatibility with v1.3.2.
	thumbsize, // For compatibility with v1.3.2.
	buttonvPadding, // For compatibility with v1.3.2.
	buttonhPadding, // For compatibility with v1.3.2.
	buttonborderStyle, // For compatibility with v1.3.2.
	buttonborderColor, // For compatibility with v1.3.2.
	buttonborderWidth, // For compatibility with v1.3.2.
	ctaBackgroundColor, // For compatibility with v1.3.2.
	headingLineHeight, // For compatibility with v1.3.2.
	headingFontWeight, // For compatibility with v1.3.2.
	contentLineHeight, // For compatibility with v1.3.2.
	contentFontWeight, // For compatibility with v1.3.2.
  } = props.attributes;

  let updatedButtonBackgroundColor = "";
  let updatedButtonBackgroundhColor = "";
  let updatedButtonBackgroundImage = '';
  if (buttonbackgroundType === "color") {
    updatedButtonBackgroundColor = buttonBackgroundColor !== "empty" && ctaBackColor === "#2091e1" ? buttonBackgroundColor : ctaBackColor; // For compatibility with v1.3.2.
  }
  if (buttonHbackgroundType == "color") {
    updatedButtonBackgroundhColor = hbuttonBackgroundColor !== "empty" && ctaHoverBackColor === "" ? hbuttonBackgroundColor : ctaHoverBackColor; // For compatibility with v1.3.2.
  } else {
    updatedButtonBackgroundhColor = '';
  }

  if ( 'gradient' === buttonbackgroundType) {
    updatedButtonBackgroundImage = generateBackgroundImageEffect(
      buttonbackgroundColor1,
      buttonbackgroundColor2,
      buttongradientDirection,
      buttoncolorLocation1,
      buttoncolorLocation2
    )
  }

  var boxShadowPositionCSS = boxShadowPosition;
  let imgopacity = opacity / 100;

  if ("outset" === boxShadowPosition) {
    boxShadowPositionCSS = "";
  }

  let ctaIconMargin = '';
  if (iconPosition === 'before') {
    ctaIconMargin = `auto ${generateCSSUnit(iconSpace, "px")} auto 0`
  }else if (iconPosition === 'after') {
    ctaIconMargin = `auto 0 auto ${generateCSSUnit(iconSpace, "px")}`
  }

  var selectors = {
    " .responsive-block-editor-addons-cta-button-wrapper .responsive-block-editor-addons-cta-button": {
      "color": buttonTextColor !== "empty" && ctaColor === "#fff" ? buttonTextColor : ctaColor, // For compatibility with v1.3.2.
      "opacity": ctaTextOpacity / 100,
    },

    " .responsive-block-editor-addons-cta-button-wrapper:hover .responsive-block-editor-addons-cta-button": {
      "color": hbuttonTextColor !== 'empty' && ctaHoverColor === "#e6f2ff" ? hbuttonTextColor : ctaHoverColor, // For compatibility with v1.3.2.
    },

    " .responsive-block-editor-addons-cta-link-text": {
      "color": buttonTextColor !== "empty" && ctaColor === "#fff" ? buttonTextColor : ctaColor, // For compatibility with v1.3.2.
      "font-family": buttonTextFontFamily,
      "font-size": generateCSSUnit(buttonTextFontSize, "px"),
      "font-weight": buttonTextFontWeight,
      "line-height": buttonTextLineHeight,
    },

    " .responsive-block-editor-addons-cta-link-text:hover": {
		"color": hbuttonTextColor !== 'empty' && ctaHoverColor === "#e6f2ff" ? hbuttonTextColor : ctaHoverColor, // For compatibility with v1.3.2.
    },

    " .responsive-block-editor-addons-cta-button-wrapper:hover": {
      "border-color": buttonborderHColor !== "empty" && ctaHoverBorderColor === "" ? buttonborderHColor : ctaHoverBorderColor, // For compatibility with v1.3.2.
      "background-color": updatedButtonBackgroundhColor,
      "background-image": buttonHbackgroundType == 'color' ? 'none' : updatedButtonBackgroundImage,
    },

    " .responsive-block-editor-addons-cta-button__icon svg": {
      "fill": icon_color,
    },

    "": {
      "background-color":
        backgroundType == "color"
          ? (ctaBackgroundColor !== "empty" && backgroundColor === "#f2f2f2" ? `${hexToRgba(ctaBackgroundColor || "#ffffff", imgopacity || 0)}` : `${hexToRgba(backgroundColor || "#ffffff", imgopacity || 0)}`) // For compatibility with v1.3.2.
          : undefined,
      "background-image":
        backgroundType == "gradient"
          ? generateBackgroundImageEffect(
              backgroundColor1,
              backgroundColor2,
              gradientDirection,
              colorLocation1,
              colorLocation2
            )
          : undefined,
      "border-radius": generateCSSUnit(borderRadius, "px"),
      "box-shadow":
        generateCSSUnit(boxShadowHOffset, "px") +
        " " +
        generateCSSUnit(boxShadowVOffset, "px") +
        " " +
        generateCSSUnit(boxShadowBlur, "px") +
        " " +
        generateCSSUnit(boxShadowSpread, "px") +
        " " +
        boxShadowColor +
        " " +
        boxShadowPositionCSS,
      "padding-top": topPadding !== 999 && blockTopPadding === 20 ? generateCSSUnit(topPadding, "px") : generateCSSUnit(blockTopPadding, "px"), // For compatibility with v1.3.2.
      "padding-bottom": bottomPadding !== 999 && blockBottomPadding === 20 ? generateCSSUnit(bottomPadding, "px") : generateCSSUnit(blockBottomPadding, "px"), // For compatibility with v1.3.2.
      "padding-left": leftPadding !== 999 && blockLeftPadding === 20 ? generateCSSUnit(leftPadding, "px") : generateCSSUnit(blockLeftPadding, "px"), // For compatibility with v1.3.2.
      "padding-right": rightPadding !== 999 && blockRightPadding === 20 ? generateCSSUnit(rightPadding, "px") : generateCSSUnit(blockRightPadding, "px"), // For compatibility with v1.3.2.
    },

    " .responsive-block-editor-addons-cta-image-wrap .responsive-block-editor-addons-cta-image": {
      "background-image": imgURL !== "empty" && backgroundImage === "" ? imgURL : (backgroundImage ? `url(${backgroundImage})` : null), // For compatibility with v1.3.2.
      "height": 100 + "%",
      "background-position": imagePosition !== "empty" && backgroundImagePosition === "center center" ? imagePosition : backgroundImagePosition, // For compatibility with v1.3.2.
      "background-repeat": imageRepeat !== "empty" && backgroundImageRepeat === "no-repeat" ? imageRepeat : backgroundImageRepeat, // For compatibility with v1.3.2.
      "background-size": thumbsize !== "empty" && backgroundImageSize === "cover" ? thumbsize : backgroundImageSize, // For compatibility with v1.3.2.
      "border-radius": generateCSSUnit(borderRadius, "px"),
    },

    " .responsive-block-editor-addons-cta-title": {
      "color": ctaTextColor,
      "line-height": headingLineHeight !== 999 && ctaTitleLineHeight === 1.8 ? headingLineHeight : ctaTitleLineHeight, // For compatibility with v1.3.2.
      "font-family": ctaTitleFontFamily,
      "font-weight": headingFontWeight !== 'empty' && ctaTitleFontWeight === "400" ? headingFontWeight : ctaTitleFontWeight, // For compatibility with v1.3.2.
      "margin-bottom": generateCSSUnit(titleSpace, "px"),
      "font-size": generateCSSUnit(ctaTitleFontSize, "px"),
    },

    " .responsive-block-editor-addons-cta-text": {
      "color": ctaTextColor,
      "font-size": generateCSSUnit(ctaTextFontSize, "px"),
      "font-family": ctaTextFontFamily,
      "line-height": contentLineHeight !== 999 && ctaTextLineHeight === 1.75 ? contentLineHeight : ctaTextLineHeight, // For compatibility with v1.3.2
      "font-weight": contentFontWeight !== 'empty' && ctaTextFontWeight === "400" ? contentFontWeight : ctaTextFontWeight, // For compatibility with v1.3.2.
      "margin-bottom": generateCSSUnit(subtitleSpace, "px"),
    },

    " .responsive-block-editor-addons-cta-button-wrapper": {
      "padding-top": buttonvPadding !== 999 && ctaVpadding === 10 ? generateCSSUnit(buttonvPadding, "px") : generateCSSUnit(ctaVpadding, "px"), // For compatibility with v1.3.2.
      "padding-bottom": buttonvPadding !== 999 && ctaVpadding === 10 ? generateCSSUnit(buttonvPadding, "px") : generateCSSUnit(ctaVpadding, "px"), // For compatibility with v1.3.2.
      "padding-left": buttonhPadding !== 999 && ctaHpadding === 14 ? generateCSSUnit(buttonhPadding, "px") : generateCSSUnit(ctaHpadding, "px"), // For compatibility with v1.3.2.
      "padding-right": buttonhPadding !== 999 && ctaHpadding === 14 ? generateCSSUnit(buttonhPadding, "px") : generateCSSUnit(ctaHpadding, "px"), // For compatibility with v1.3.2.
      "border-style": buttonborderStyle !== "empty" && ctaBorderStyle === "solid" ? buttonborderStyle : (ctaBorderStyle ? ctaBorderStyle : "solid"), // For compatibility with v1.3.2.
      "border-radius": generateCSSUnit(ctaBorderRadius, "px"),
      "border-color": buttonborderColor !== "empty" && ctaBorderColor === "" ? buttonborderColor : ctaBorderColor, // For compatibility with v1.3.2.
      "background-color": updatedButtonBackgroundColor,
      "border-width": buttonborderWidth !== 999 && ctaBorderWidth === 1 ? buttonborderWidth : (ctaBorderWidth
        ? generateCSSUnit(ctaBorderWidth, "px")
        : "0px"), // For compatibility with v1.3.2.
      "background-image": updatedButtonBackgroundImage,
      "margin-bottom": generateCSSUnit(buttonSpace, "px"),
    },

    " .responsive-block-editor-addons-cta-button__icon": {
      "margin":  ctaIconMargin,
    },

    " .responsive-block-editor-addons-cta-button.rich-text": {
      "font-family": buttonTextFontFamily,
      "font-size": generateCSSUnit(buttonTextFontSize, "px"),
      "font-weight": buttonTextFontWeight,
      "line-height": buttonTextLineHeight,
    }
  };

  var mobile_selectors = {
    " h2.responsive-block-editor-addons-cta-title": {
      "margin-bottom": generateCSSUnit(titleSpaceMobile, "px"),
      "font-size": generateCSSUnit(ctaTitleFontSizeMobile, "px") + "!important",
    },
    "": {
      "padding-top": generateCSSUnit(blockTopPaddingMobile, "px"),
      "padding-bottom": generateCSSUnit(blockBottomPaddingMobile, "px"),
      "padding-left": generateCSSUnit(blockLeftPaddingMobile, "px"),
      "padding-right": generateCSSUnit(blockRightPaddingMobile, "px"),
    },
    " .responsive-block-editor-addons-cta-text": {
      "margin-bottom": generateCSSUnit(subtitleSpaceMobile, "px"),
      "font-size": generateCSSUnit(ctaTextFontSizeMobile, "px") + "!important",
    },
    " .responsive-block-editor-addons-cta-button-wrapper": {
      "margin-bottom": generateCSSUnit(buttonSpaceMobile, "px"),
      "padding-top": generateCSSUnit(ctaVpaddingMobile, "px"),
      "padding-bottom": generateCSSUnit(ctaVpaddingMobile, "px"),
      "padding-left": generateCSSUnit(ctaHpaddingMobile, "px"),
      "padding-right": generateCSSUnit(ctaHpaddingMobile, "px"),
    },
    " .responsive-block-editor-addons-cta-button.rich-text": {
      "font-size": `${generateCSSUnit(buttonTextFontSizeMobile, "px")}`,
    },
    " .responsive-block-editor-addons-cta-link-text": {
      "font-size": `${generateCSSUnit(buttonTextFontSizeMobile, "px")}`,
    },
  };

  var tablet_selectors = {
    " h2.responsive-block-editor-addons-cta-title": {
      "margin-bottom": generateCSSUnit(titleSpaceTablet, "px"),
      "font-size": generateCSSUnit(ctaTitleFontSizeTablet, "px"),
    },
    "": {
      "padding-top": generateCSSUnit(blockTopPaddingTablet, "px"),
      "padding-bottom": generateCSSUnit(blockBottomPaddingTablet, "px"),
      "padding-left": generateCSSUnit(blockLeftPaddingTablet, "px"),
      "padding-right": generateCSSUnit(blockRightPaddingTablet, "px"),
    },
    " .responsive-block-editor-addons-cta-text": {
      "margin-bottom": generateCSSUnit(subtitleSpaceTablet, "px"),
      "font-size": generateCSSUnit(ctaTextFontSizeTablet, "px"),
    },
    " .responsive-block-editor-addons-cta-button-wrapper": {
      "margin-bottom": generateCSSUnit(buttonSpaceTablet, "px"),
      "padding-top": generateCSSUnit(ctaVpaddingTablet, "px"),
      "padding-bottom": generateCSSUnit(ctaVpaddingTablet, "px"),
      "padding-left": generateCSSUnit(ctaHpaddingTablet, "px"),
      "padding-right": generateCSSUnit(ctaHpaddingTablet, "px"),
    },
    " .responsive-block-editor-addons-cta-button.rich-text": {
      "font-size": generateCSSUnit(buttonTextFontSizeTablet, "px"),
    },
    " .responsive-block-editor-addons-cta-link-text": {
      "font-size": `${generateCSSUnit(buttonTextFontSizeTablet, "px")}`,
    },
  };

  var styling_css = "";
  var id = `.responsive-block-editor-addons-block-call-to-action.block-${block_id}`;

  styling_css = generateCSS(selectors, id);
  styling_css += generateCSS(tablet_selectors, id, true, "tablet");
  styling_css += generateCSS(mobile_selectors, id, true, "mobile");

  return styling_css;
}

export default EditorStyles;
