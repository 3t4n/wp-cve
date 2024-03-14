/**
 * Returns Dynamic Generated CSS
 */

import generateCSS from "../../../generateCSS";
import generateCSSUnit from "../../../generateCSSUnit";

function EditorStyles(props) {
  const {
    popupContainerWidth,
    popupContainerWidthTablet,
    popupContainerWidthMobile,
    popupToggleCloseBtnPosition,
    popupHeightType,
    popupHeightCustom,
    popupHeightCustomTablet,
    popupHeightCustomMobile,
    popupPaddingTop,
    popupPaddingTopTablet,
    popupPaddingTopMobile,
    popupPaddingBottom,
    popupPaddingBottomTablet,
    popupPaddingBottomMobile,
    popupPaddingLeft,
    popupPaddingLeftTablet,
    popupPaddingLeftMobile,
    popupPaddingRight,
    popupPaddingRightTablet,
    popupPaddingRightMobile,
    popupScreenType,
    popupScreenTypeTablet,
    popupScreenTypeMobile,
    popupBgType,
    popupBgColor,
    popupGradient,
    popupCloseBtnColor,
    popupOverlayColor,
    popupOverlayOpacity,
    popupBlockBorderStyle,
    popupBlockBorderWidth,
    popupBlockBorderRadius,
    popupBlockBorderColor,
    popupTriggerAlign,
    popupTriggerAlignTablet,
    popupTriggerAlignMobile,
    popupButtonColor,
    popupButtonBGState,
    popupButtonBGColor,
    popupButtonBGGradient,
    popupButtonTypographyFontFamily,
    popupButtonTypographyFontSize,
    popupButtonTypographyFontSizeMobile,
    popupButtonTypographyFontSizeTablet,
    popupButtonTypographyFontWeight,
    popupButtonTypographyLineHeight,
    popupButtonTypographyLetterSpacing,
    popupButtonPaddingTop,
    popupButtonPaddingTopTablet,
    popupButtonPaddingTopMobile,
    popupButtonPaddingBottom,
    popupButtonPaddingBottomTablet,
    popupButtonPaddingBottomMobile,
    popupButtonPaddingLeft,
    popupButtonPaddingLeftTablet,
    popupButtonPaddingLeftMobile,
    popupButtonPaddingRight,
    popupButtonPaddingRightTablet,
    popupButtonPaddingRightMobile,
    popupButtonBorderRadius,
    popupButtonBorderStyle,
    popupButtonBorderWidth,
    popupButtonBorderColor,
    popupButtonBGHoverState,
    popupButtonHoverColor,
    popupButtonBGHoverColor,
    popupButtonHoverBGGradient,
    popupButtonBorderHoverColor,
    popupTextColor,
    popupTextTypographyFontFamily,
    popupTextTypographyFontSize,
    popupTextTypographyFontSizeMobile,
    popupTextTypographyFontSizeTablet,
    popupTextTypographyFontWeight,
    popupTextTypographyLineHeight,
    popupTextTypographyLetterSpacing,
    popupIconTriggerSize,
    popupIconTriggerColor,
    popupImageTriggerWidth,
    popupImageTriggerWidthTablet,
    popupImageTriggerWidthMobile,
    popupImageTriggerBorderRadius,
    block_id,
  } = props.attributes;

  const popupScreenPositions = {
    desktop: {
      top: '',
      right: '',
      bottom: '',
      left: '',
    },
    tablet: {
      top: '',
      right: '',
      bottom: '',
      left: '',
    },
    mobile: {
      top: '',
      right: '',
      bottom: '',
      left: '',
    },
  };

  // Define a function to set popup positions based on screen type
  function setPopupPosition(screen, top, left, center, right, bottom) {
    const popupScreenPosition = popupScreenPositions[screen];
    popupScreenPosition.top = top !== 'unset' ? generateCSSUnit(top, "px") : 'unset';
    popupScreenPosition.left = left !== 'unset' ? generateCSSUnit(left, "px") : 'unset';
    popupScreenPosition.center = center;
    popupScreenPosition.right = right !== 'unset' ? generateCSSUnit(right, "px") : 'unset';
    popupScreenPosition.bottom = bottom !== 'unset' ? generateCSSUnit(bottom, "px") : 'unset';
  }

  // Set positions for desktop
  switch (popupScreenType) {
    case 'top left':
      setPopupPosition('desktop', 30, 30, 'unset', 'unset', 'unset');
      break
    case 'top center':
      setPopupPosition('desktop', 30, 'unset', 'unset', 'unset', 'unset');
      break
    case 'top right':
      setPopupPosition('desktop', 30, 'unset', 'unset', 30, 'unset');
      break;
    case 'center left':
      setPopupPosition('desktop', 'unset', 30, 'unset', 'unset', 'unset');
      break;
    case 'center center':
      setPopupPosition('desktop', 'unset', 'unset', 'unset', 'unset', 'unset');
      break;
    case 'center right':
      setPopupPosition('desktop', 'unset', 'unset', 'unset', 30, 'unset');
      break;
    case 'bottom left':
      setPopupPosition('desktop', 'unset', 30, 'unset', 'unset', 30);
      break;
    case 'bottom center':
      setPopupPosition('desktop', 'unset', 'unset', 'unset', 'unset', 30);
      break;
    case 'bottom right':
      setPopupPosition('desktop', 'unset', 'unset', 'unset', 30, 30);
      break;
  }

  // Set positions for tablet
  switch (popupScreenTypeTablet) {
    case 'top left':
      setPopupPosition('tablet', 15, 15, 'unset', 'unset', 'unset');
      break
    case 'top center':
      setPopupPosition('tablet', 15, 'unset', 'unset', 'unset', 'unset');
      break
    case 'top right':
      setPopupPosition('tablet', 15, 'unset', 'unset', 15, 'unset');
      break;
    case 'center left':
      setPopupPosition('tablet', 'unset', 15, 'unset', 'unset', 'unset');
      break;
    case 'center center':
      setPopupPosition('tablet', 'unset', 'unset', 'unset', 'unset', 'unset');
      break;
    case 'center right':
      setPopupPosition('tablet', 'unset', 'unset', 'unset', 15, 'unset');
      break;
    case 'bottom left':
      setPopupPosition('tablet', 'unset', 15, 'unset', 'unset', 15);
      break;
    case 'bottom center':
      setPopupPosition('tablet', 'unset', 'unset', 'unset', 'unset', 15);
      break;
    case 'bottom right':
      setPopupPosition('tablet', 'unset', 'unset', 'unset', 15, 15);
      break;
  }

  // Set positions for mobile
  switch (popupScreenTypeMobile) {
    case 'top left':
      setPopupPosition('mobile', 10, 10, 'unset', 'unset', 'unset');
      break
    case 'top center':
      setPopupPosition('mobile', 10, 'unset', 'unset', 'unset', 'unset');
      break
    case 'top right':
      setPopupPosition('mobile', 10, 'unset', 'unset', 10, 'unset');
      break;
    case 'center left':
      setPopupPosition('mobile', 'unset', 10, 'unset', 'unset', 'unset');
      break;
    case 'center center':
      setPopupPosition('mobile', 'unset', 'unset', 'unset', 'unset', 'unset');
      break;
    case 'center right':
      setPopupPosition('mobile', 'unset', 'unset', 'unset', 10, 'unset');
      break;
    case 'bottom left':
      setPopupPosition('mobile', 'unset', 10, 'unset', 'unset', 10);
      break;
    case 'bottom center':
      setPopupPosition('mobile', 'unset', 'unset', 'unset', 'unset', 10);
      break;
    case 'bottom right':
      setPopupPosition('mobile', 'unset', 'unset', 'unset', 10, 10);
      break;
  }

  const { desktop, tablet, mobile } = popupScreenPositions;

  const popupContainerBackground = popupBgType === 'gradient' ? { 'background-image': popupGradient } : { 'background-color': popupBgColor };

  const buttonBGColor = {
    'solid': { 'background-color': popupButtonBGColor },
    'gradient': { 'background-image': popupButtonBGGradient },
  }[popupButtonBGState] || { 'background-color': 'transparent' };

  const buttonHoverBGColor = {
    'solid': { 'background-color': popupButtonBGHoverColor },
    'gradient': { 'background-image': popupButtonHoverBGGradient },
  }[popupButtonBGHoverState] || { 'background-color': 'transparent' };

  var selectors = {
    "": {

    },
    " .responsive-block-editor-addons-popup-trigger-wrap": {
      "justify-content": popupTriggerAlign,
    },
    " .responsive-block-editor-addons-popup-button-trigger": {
      "color": popupButtonColor,
      ...buttonBGColor,
      "font-family": popupButtonTypographyFontFamily,
      "font-size": generateCSSUnit(popupButtonTypographyFontSize, "px"),
      "font-weight": parseInt(popupButtonTypographyFontWeight),
      "line-height": popupButtonTypographyLineHeight,
      "letter-spacing": generateCSSUnit(popupButtonTypographyLetterSpacing, "px"),
      "padding-top": generateCSSUnit(popupButtonPaddingTop, "px"),
      "padding-bottom": generateCSSUnit(popupButtonPaddingBottom, "px"),
      "padding-left": generateCSSUnit(popupButtonPaddingLeft, "px"),
      "padding-right": generateCSSUnit(popupButtonPaddingRight, "px"),
      "border-width": generateCSSUnit(popupButtonBorderWidth, "px"),
      "border-style": popupButtonBorderStyle,
      "border-color": popupButtonBorderColor,
      "border-radius": generateCSSUnit(popupButtonBorderRadius, "px"),
    },
    " .responsive-block-editor-addons-popup-button-trigger:hover": {
      "color": popupButtonHoverColor,
      ...buttonHoverBGColor,
      "border-color": popupButtonBorderHoverColor,
    },
    " .responsive-block-editor-addons-popup-text-trigger": {
      "color": popupTextColor,
      "font-family": popupTextTypographyFontFamily,
      "font-size": generateCSSUnit(popupTextTypographyFontSize, "px"),
      "font-weight": parseInt(popupTextTypographyFontWeight),
      "line-height": popupTextTypographyLineHeight,
      "letter-spacing": generateCSSUnit(popupTextTypographyLetterSpacing, "px"),
    },
    " .responsive-block-editor-addons-popup-icon-trigger svg": {
      "fill": popupIconTriggerColor,
      "width": generateCSSUnit(popupIconTriggerSize, "px"),
      "height": generateCSSUnit(popupIconTriggerSize, "px"),
      "line-height": generateCSSUnit(popupIconTriggerSize, "px"),
      "font-size": generateCSSUnit(popupIconTriggerSize, "px"),popupImageTriggerBorderRadius
    },
    " .responsive-block-editor-addons-popup-image-trigger": {
      "width": generateCSSUnit(popupImageTriggerWidth, "px"),
      "border-radius": generateCSSUnit(popupImageTriggerBorderRadius, "px"),
    },
    " .responsive-block-editor-addons-popup-modal-content": {
      "width": generateCSSUnit(popupContainerWidth, "px"),
      "height": 'auto' !== popupHeightType ? generateCSSUnit(popupHeightCustom, "px") : 'auto',
      "padding-top": generateCSSUnit(popupPaddingTop, "px"),
      "padding-right": generateCSSUnit(popupPaddingRight, "px"),
      "padding-bottom": generateCSSUnit(popupPaddingBottom, "px"),
      "padding-left": generateCSSUnit(popupPaddingLeft, "px"),
      ...desktop,
      ...popupContainerBackground,
      "border-style": popupBlockBorderStyle,
      "border-width": generateCSSUnit(popupBlockBorderWidth, "px"),
      "border-radius": generateCSSUnit(popupBlockBorderRadius, "px"),
      "border-color": popupBlockBorderColor,
    },
    " .responsive-block-editor-addons-popup-modal-header": {
      "justify-content": popupToggleCloseBtnPosition,
    },
    " .responsive-block-editor-addons-popup-modal-header .dashicons.dashicons-no": {
      "color": popupCloseBtnColor,
    },
    " .responsive-block-editor-addons-popup-modal-wrap-overlay": {
      "background-color": popupOverlayColor,
      "opacity": parseInt(popupOverlayOpacity) / 100,
    },
  };

  var mobile_selectors = {
    "": {

    },
    " .responsive-block-editor-addons-popup-trigger-wrap": {
      "justify-content": popupTriggerAlignMobile,
    },
    " .responsive-block-editor-addons-popup-button-trigger": {
      "font-size": generateCSSUnit(popupButtonTypographyFontSizeMobile, "px"),
      "padding-top": generateCSSUnit(popupButtonPaddingTopMobile, "px"),
      "padding-bottom": generateCSSUnit(popupButtonPaddingBottomMobile, "px"),
      "padding-left": generateCSSUnit(popupButtonPaddingLeftMobile, "px"),
      "padding-right": generateCSSUnit(popupButtonPaddingRightMobile, "px"),
    },
    " .responsive-block-editor-addons-popup-text-trigger": {
      "font-size": generateCSSUnit(popupTextTypographyFontSizeMobile, "px"),
    },
    " .responsive-block-editor-addons-popup-image-trigger": {
      "width": generateCSSUnit(popupImageTriggerWidthMobile, "px"),
    },
    " .responsive-block-editor-addons-popup-modal-content": {
      "width": generateCSSUnit(popupContainerWidthMobile, "px"),
      "height": 'auto' !== popupHeightType ? generateCSSUnit(popupHeightCustomMobile, "px") : 'auto',
      "padding-top": generateCSSUnit(popupPaddingTopMobile, "px"),
      "padding-right": generateCSSUnit(popupPaddingRightMobile, "px"),
      "padding-bottom": generateCSSUnit(popupPaddingBottomMobile, "px"),
      "padding-left": generateCSSUnit(popupPaddingLeftMobile, "px"),
      ...tablet,
    },
  };

  var tablet_selectors = {
    "": {

    },
    " .responsive-block-editor-addons-popup-trigger-wrap": {
      "justify-content": popupTriggerAlignTablet,
    },
    " .responsive-block-editor-addons-popup-button-trigger": {
      "font-size": generateCSSUnit(popupButtonTypographyFontSizeTablet, "px"),
      "padding-top": generateCSSUnit(popupButtonPaddingTopTablet, "px"),
      "padding-bottom": generateCSSUnit(popupButtonPaddingBottomTablet, "px"),
      "padding-left": generateCSSUnit(popupButtonPaddingLeftTablet, "px"),
      "padding-right": generateCSSUnit(popupButtonPaddingRightTablet, "px"),
    },
    " .responsive-block-editor-addons-popup-text-trigger": {
      "font-size": generateCSSUnit(popupTextTypographyFontSizeTablet, "px"),
    },
    " .responsive-block-editor-addons-popup-image-trigger": {
      "width": generateCSSUnit(popupImageTriggerWidthTablet, "px"),
    },
    " .responsive-block-editor-addons-popup-modal-content": {
      "width": generateCSSUnit(popupContainerWidthTablet, "px"),
      "height": 'auto' !== popupHeightType ? generateCSSUnit(popupHeightCustomTablet, "px") : 'auto',
      "padding-top": generateCSSUnit(popupPaddingTopTablet, "px"),
      "padding-right": generateCSSUnit(popupPaddingRightTablet, "px"),
      "padding-bottom": generateCSSUnit(popupPaddingBottomTablet, "px"),
      "padding-left": generateCSSUnit(popupPaddingLeftTablet, "px"),
      ...mobile,
    },
  };

  var styling_css = "";
  var id = `.responsive-block-editor-addons-block-popup.block-${block_id}`;

  styling_css = generateCSS(selectors, id);
  styling_css += generateCSS(tablet_selectors, id, true, "tablet");
  styling_css += generateCSS(mobile_selectors, id, true, "mobile");

  return styling_css;
}

export default EditorStyles;
