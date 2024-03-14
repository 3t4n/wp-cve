/**
 * Returns Dynamic Generated CSS
 */

import generateCSS from "../../../generateCSS";
import generateCSSUnit from "../../../generateCSSUnit";
import generateBackgroundImageEffect from "../../../generateBackgroundImageEffect";

function EditorStyles(props) {
  const {
    color,
  } = props;
  const {
    block_id,
    backgroundColor,
    backgroundColor1,
    backgroundColor2,
    colorLocation1,
    colorLocation2,
    gradientDirection,
    backgroundType,
    align,
  } = props.attributes;

  var selectors = {
    "": {
      color: props.color.color,
      "background-color": backgroundType == "color" ? backgroundColor : null,
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
    },
    " .wp-block-responsive-block-editor-addons-shape-divider__svg-wrapper svg": {
      fill: color.color
    }
  };

  var mobile_selectors = {};

  var tablet_selectors = {};

  var styling_css = "";
  var id = `.responsive-block-editor-addons-block-shape-divider.block-${block_id}`;

  styling_css = generateCSS(selectors, id);
  styling_css += generateCSS(tablet_selectors, id, true, "tablet");
  styling_css += generateCSS(mobile_selectors, id, true, "mobile");

  return styling_css;
}

export default EditorStyles;
