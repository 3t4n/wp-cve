/**
 * Returns Dynamic Generated CSS
 */

import generateCSS from "../../../generateCSS";
import generateCSSUnit from "../../../generateCSSUnit";
import { hexToRgba } from "../../../utils";

function EditorStyles(props) {
  const {
    layout,
    inactiveOtherItems,
    expandFirstItem,
    rowsGap,
    rowsGapMobile,
    rowsGapTablet,
    columnsGap,
    columnsGapMobile,
    columnsGapTablet,
    align,
    titleTextColor,
    titleActiveBackgroundColor,
    titleActiveTextColor,
    titleTextActiveColor,
    titlePaddingTypeDesktop,
    vtitlePaddingMobile,
    vtitlePaddingTablet,
    vtitlePaddingDesktop,
    htitlePaddingMobile,
    htitlePaddingTablet,
    htitlePaddingDesktop,
    contentPaddingTypeDesktop,
    vcontentPaddingMobile,
    vcontentPaddingTablet,
    vcontentPaddingDesktop,
    hcontentPaddingMobile,
    hcontentPaddingTablet,
    hcontentPaddingDesktop,
    iconColor,
    iconActiveColor,
    titleFontWeight,
    titleFontSize,
    titleFontFamily,
    titleLineHeight,
    contentFontWeight,
    contentFontSize,
    contentFontFamily,
    contentLineHeight,
    iconAlign,
    iconSize,
    iconSizeType,
    iconSizeMobile,
    iconSizeTablet,
    columns,
    titleLeftPaddingTablet,
    titleBottomPaddingTablet,
    titleLeftPaddingDesktop,
    titleBottomPaddingDesktop,
    titleLeftPaddingMobile,
    titleBottomPaddingMobile,
    titleBackgroundColorOpacity,
    marginV,
    marginVMobile,
    marginVTablet,
    marginH,
    marginHMobile,
    marginHTablet,
    titleSecondaryBackgroundColor,
    titleGradientDegree,
    titleBgGradient,
    titleBackgroundColor,
    contentTextColor,
    contentSecondaryBackgroundColor,
    contentGradientDegree,
    contentBgGradient,
    contentBackgroundColor,
    contentBackgroundColorOpacity,
	titleFontSizeMobile,
	titleFontSizeTablet,
	contentFontSizeMobile,
	contentFontSizeTablet,
  } = props.attributes;

  var selectors = {};
  var tablet_selectors = {};
  var mobile_selectors = {};
  var icon_color = iconColor;
  var icon_active_color = iconActiveColor;

  if ("undefined" == typeof iconColor || "" == iconColor) {
    icon_color = titleTextColor;
  }
  if ("undefined" == typeof iconActiveColor || "" == iconActiveColor) {
    icon_active_color = titleTextActiveColor;
  }

  let contentOpacity = titleBackgroundColorOpacity / 100;
  let contentBackgroundColorsOpacity = contentBackgroundColorOpacity / 100;

  var temptitleSecondaryBackgroundColor = titleBgGradient
    ? titleSecondaryBackgroundColor
    : titleBackgroundColor;

  var titleGradient;
  if (titleBgGradient) {
    titleGradient =
      "linear-gradient(" +
      titleGradientDegree +
      "deg," +
      hexToRgba(titleBackgroundColor || "#ffffff", contentOpacity || 0) +
      "," +
      hexToRgba(
        temptitleSecondaryBackgroundColor || "#ffffff",
        contentOpacity || 0
      ) +
      ")";
  }
  var tempActiveSecondaryBackgroundColor = contentBgGradient
    ? contentSecondaryBackgroundColor
    : contentBackgroundColor;

  var contentGradient =
    "linear-gradient(" +
    contentGradientDegree +
    "deg," +
    hexToRgba(
      contentBackgroundColor || "#ffffff",
      contentBackgroundColorsOpacity || 0
    ) +
    "," +
    hexToRgba(
      tempActiveSecondaryBackgroundColor || "#ffffff",
      contentBackgroundColorsOpacity || 0
    ) +
    ")";

  selectors = {
    " ": {
      "margin-top": marginV + "px",
      "margin-bottom": marginV + "px",
      "margin-left": marginH + "px",
      "margin-right": marginH + "px",
    },
    " .responsive-block-editor-addons-icon svg": {
      "width": generateCSSUnit(iconSize, iconSizeType),
      "height": generateCSSUnit(iconSize, iconSizeType),
      "font-size": generateCSSUnit(iconSize, iconSizeType),
      "fill": icon_color,
      "margin-right": "10px",
    },
    " .responsive-block-editor-addons-icon-active svg": {
      "width": generateCSSUnit(iconSize, iconSizeType),
      "height": generateCSSUnit(iconSize, iconSizeType),
      "font-size": generateCSSUnit(iconSize, iconSizeType),
      "fill": icon_active_color,
      "margin-right": "10px",
    },

    " .responsive-block-editor-addons-accordion-item__outer-wrap": {
      "margin-bottom": generateCSSUnit(rowsGap, "px"),
    },
    " .responsive-block-editor-addons-accordion-layout-grid .block-editor-inner-blocks .block-editor-block-list__layout": {
      "grid-column-gap": generateCSSUnit(columnsGap, "px"),
      "grid-row-gap": generateCSSUnit(rowsGap, "px"),
    },
    " .responsive-block-editor-addons-accordion-titles-button": {
      "padding-top": generateCSSUnit(
        vtitlePaddingDesktop,
        titlePaddingTypeDesktop
      ),
      "padding-bottom": generateCSSUnit(
        titleBottomPaddingDesktop,
        titlePaddingTypeDesktop
      ),
      "padding-right": generateCSSUnit(
        htitlePaddingDesktop,
        titlePaddingTypeDesktop
      ),
      "padding-left": generateCSSUnit(
        titleLeftPaddingDesktop,
        titlePaddingTypeDesktop
      ),
    },
    " .responsive-block-editor-addons-accordion-content span": {
      "margin-top": generateCSSUnit(
        vcontentPaddingDesktop,
        contentPaddingTypeDesktop
      ),
      "margin-bottom": generateCSSUnit(
        vcontentPaddingDesktop,
        contentPaddingTypeDesktop
      ),
      "margin-right": generateCSSUnit(
        hcontentPaddingDesktop,
        contentPaddingTypeDesktop
      ),
      "margin-left": generateCSSUnit(
        hcontentPaddingDesktop,
        contentPaddingTypeDesktop
      ),
    },
    " .responsive-block-editor-addons-accordion-item .responsive-block-editor-addons-accordion-titles-button.responsive-block-editor-addons-accordion-titles": {
      "flex-direction": iconAlign,
      "color": titleTextColor,
      "background-color": `${hexToRgba(titleBackgroundColor || "#fff", contentOpacity || 0)}`,
    },
    " .responsive-block-editor-addons-accordion__active .responsive-block-editor-addons-accordion-titles-button.responsive-block-editor-addons-accordion-titles ": {
      "color": titleActiveTextColor,
      "background-color": titleActiveBackgroundColor,
    },
    " .responsive-block-editor-addons-accordion-titles-button ": {
      "background-image": titleGradient,
    },
    " .responsive-block-editor-addons-accordion-titles-button .responsive-block-editor-addons-title": {
      "font-family": titleFontFamily,
      "font-size": generateCSSUnit(titleFontSize, "px"),
      "line-height": titleLineHeight,
      "font-weight": titleFontWeight,
    },
    " .responsive-block-editor-addons-accordion-item .responsive-block-editor-addons-accordion-content": {
      "color": contentTextColor,
      "background-image": contentGradient,
      "font-size": generateCSSUnit(contentFontSize, "px"),
      "font-family": contentFontFamily,
      "line-height": contentLineHeight,
      "font-weight": contentFontWeight,
    },
  };

  tablet_selectors = {
    " ": {
      "margin-top": marginVTablet + "px",
      "margin-bottom": marginVTablet + "px",
      "margin-left": marginHTablet + "px",
      "margin-right": marginHTablet + "px",
    },
    " .responsive-block-editor-addons-accordion-titles-button": {
      "padding-top": generateCSSUnit(
        vtitlePaddingTablet,
        titlePaddingTypeDesktop
      ),
      "padding-bottom": generateCSSUnit(
        titleBottomPaddingTablet,
        titlePaddingTypeDesktop
      ),
      "padding-right": generateCSSUnit(
        htitlePaddingTablet,
        titlePaddingTypeDesktop
      ),
      "padding-left": generateCSSUnit(
        titleLeftPaddingTablet,
        titlePaddingTypeDesktop
      ),
    },
    " .responsive-block-editor-addons-accordion-content span": {
      "margin-top": generateCSSUnit(
        vcontentPaddingTablet,
        contentPaddingTypeDesktop
      ),
      "margin-bottom": generateCSSUnit(
        vcontentPaddingTablet,
        contentPaddingTypeDesktop
      ),
      "margin-right": generateCSSUnit(
        hcontentPaddingTablet,
        contentPaddingTypeDesktop
      ),
      "margin-left": generateCSSUnit(
        hcontentPaddingTablet,
        contentPaddingTypeDesktop
      ),
    },
    " .responsive-block-editor-addons-icon svg": {
      "width": generateCSSUnit(iconSizeTablet, iconSizeType),
      "height": generateCSSUnit(iconSizeTablet, iconSizeType),
      "font-size": generateCSSUnit(iconSizeTablet, iconSizeType),
    },
    " .responsive-block-editor-addons-icon-active svg": {
      "width": generateCSSUnit(iconSizeTablet, iconSizeType),
      "height": generateCSSUnit(iconSizeTablet, iconSizeType),
      "font-size": generateCSSUnit(iconSizeTablet, iconSizeType),
    },
	" .responsive-block-editor-addons-accordion-titles-button .responsive-block-editor-addons-title": {
		"font-size": generateCSSUnit(titleFontSizeTablet, "px"),
	},
	" .responsive-block-editor-addons-accordion-item .responsive-block-editor-addons-accordion-content": {
		"font-size": generateCSSUnit(contentFontSizeTablet, "px"),
	},
  " .responsive-block-editor-addons-accordion-item__outer-wrap": {
    "margin-bottom": generateCSSUnit(rowsGapTablet, "px"),
  },
  " .responsive-block-editor-addons-accordion-layout-grid .block-editor-inner-blocks .block-editor-block-list__layout": {
    "grid-column-gap": generateCSSUnit(columnsGapTablet, "px"),
    "grid-row-gap": generateCSSUnit(rowsGapTablet, "px"),
  },
  };

  mobile_selectors = {
    " ": {
      "margin-top": marginVMobile + "px",
      "margin-bottom": marginVMobile + "px",
      "margin-left": marginHMobile + "px",
      "margin-right": marginHMobile + "px",
    },
    " .responsive-block-editor-addons-accordion-titles-button": {
      "padding-top": generateCSSUnit(
        vtitlePaddingMobile,
        titlePaddingTypeDesktop
      ),
      "padding-bottom": generateCSSUnit(
        titleBottomPaddingMobile,
        titlePaddingTypeDesktop
      ),
      "padding-right": generateCSSUnit(
        htitlePaddingMobile,
        titlePaddingTypeDesktop
      ),
      "padding-left": generateCSSUnit(
        titleLeftPaddingMobile,
        titlePaddingTypeDesktop
      ),
    },
    " .responsive-block-editor-addons-accordion-content span": {
      "margin-top": generateCSSUnit(
        vcontentPaddingMobile,
        contentPaddingTypeDesktop
      ),
      "margin-bottom": generateCSSUnit(
        vcontentPaddingMobile,
        contentPaddingTypeDesktop
      ),
      "margin-right": generateCSSUnit(
        hcontentPaddingMobile,
        contentPaddingTypeDesktop
      ),
      "margin-left": generateCSSUnit(
        hcontentPaddingMobile,
        contentPaddingTypeDesktop
      ),
    },
    " .responsive-block-editor-addons-icon svg": {
      "width": generateCSSUnit(iconSizeMobile, iconSizeType),
      "height": generateCSSUnit(iconSizeMobile, iconSizeType),
      "font-size": generateCSSUnit(iconSizeMobile, iconSizeType),
    },
    " .responsive-block-editor-addons-icon-active svg": {
      "width": generateCSSUnit(iconSizeMobile, iconSizeType),
      "height": generateCSSUnit(iconSizeMobile, iconSizeType),
      "font-size": generateCSSUnit(iconSizeMobile, iconSizeType),
    },
	" .responsive-block-editor-addons-accordion-titles-button .responsive-block-editor-addons-mobile": {
		"font-size": generateCSSUnit(titleFontSizeMobile, "px"),
	},
	" .responsive-block-editor-addons-accordion-item .responsive-block-editor-addons-accordion-content": {
		"font-size": generateCSSUnit(contentFontSizeMobile, "px"),
	},
  " .responsive-block-editor-addons-accordion-item__outer-wrap": {
    "margin-bottom": generateCSSUnit(rowsGapMobile, "px"),
  },
  " .responsive-block-editor-addons-accordion-layout-grid .block-editor-inner-blocks .block-editor-block-list__layout": {
    "grid-column-gap": generateCSSUnit(columnsGapMobile, "px"),
    "grid-row-gap": generateCSSUnit(rowsGapMobile, "px"),
  },
  };

  if ("accordion" === layout && true === inactiveOtherItems) {
    selectors[
      " .block-editor-block-list__layout .responsive-block-editor-addons-accordion-item__outer-wrap .responsive-block-editor-addons-accordion-content"
    ] = {
      "display": "none",
    };
  }
  if ("accordion" === layout && false === inactiveOtherItems) {
    (selectors[
      " .block-editor-inner-blocks .responsive-block-editor-addons-accordion-item__outer-wrap .responsive-block-editor-addons-accordion-item .responsive-block-editor-addons-accordion-titles-button .responsive-block-editor-addons-icon-active"
    ] = {
      "display": "inline-block",
    }),
      (selectors[
        " .block-editor-inner-blocks .responsive-block-editor-addons-accordion-item__outer-wrap .responsive-block-editor-addons-accordion-item .responsive-block-editor-addons-accordion-titles-button .responsive-block-editor-addons-icon"
      ] = {
        "display": "none",
      });
  }
  if ("accordion" === layout && true === expandFirstItem) {
    selectors[
      " .block-editor-block-list__layout > div:first-child > .responsive-block-editor-addons-accordion-item__outer-wrap .responsive-block-editor-addons-accordion-content"
    ] = {
      "display": "block",
    };
    (selectors[
      " .block-editor-block-list__layout > div:first-child > .responsive-block-editor-addons-accordion-item__outer-wrap .responsive-block-editor-addons-accordion-item .responsive-block-editor-addons-accordion-titles-button .responsive-block-editor-addons-icon-active"
    ] = {
      "display": "inline-block",
    }),
      (selectors[
        " .block-editor-block-list__layout > div:first-child > .responsive-block-editor-addons-accordion-item__outer-wrap .responsive-block-editor-addons-accordion-item .responsive-block-editor-addons-accordion-titles-button .responsive-block-editor-addons-icon"
      ] = {
        "display": "none",
      });
  }

  if ("grid" === layout) {
    selectors[
      " .block-editor-block-list__layout .responsive-block-editor-addons-accordion-item__outer-wrap"
    ] = {
      "text-align": align,
    };
    selectors[
      " .responsive-block-editor-addons-accordion-layout-grid .block-editor-inner-blocks > .block-editor-block-list__layout"
    ] = {
      "grid-template-columns": "repeat(" + columns + ", 1fr)",
    };
  }

  var styling_css = "";
  var id = `.responsive-block-editor-addons-accordion__outer-wrap.responsive-block-editor-addons-block-${props.clientId}`;

  styling_css = generateCSS(selectors, id);

  styling_css += generateCSS(tablet_selectors, id, true, "tablet");

  styling_css += generateCSS(mobile_selectors, id, true, "mobile");

  return styling_css;
}

export default EditorStyles;
