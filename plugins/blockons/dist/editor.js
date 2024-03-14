/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ 4184:
/***/ ((module, exports) => {

var __WEBPACK_AMD_DEFINE_ARRAY__, __WEBPACK_AMD_DEFINE_RESULT__;/*!
	Copyright (c) 2018 Jed Watson.
	Licensed under the MIT License (MIT), see
	http://jedwatson.github.io/classnames
*/
/* global define */

(function () {
	'use strict';

	var hasOwn = {}.hasOwnProperty;
	var nativeCodeString = '[native code]';

	function classNames() {
		var classes = [];

		for (var i = 0; i < arguments.length; i++) {
			var arg = arguments[i];
			if (!arg) continue;

			var argType = typeof arg;

			if (argType === 'string' || argType === 'number') {
				classes.push(arg);
			} else if (Array.isArray(arg)) {
				if (arg.length) {
					var inner = classNames.apply(null, arg);
					if (inner) {
						classes.push(inner);
					}
				}
			} else if (argType === 'object') {
				if (arg.toString !== Object.prototype.toString && !arg.toString.toString().includes('[native code]')) {
					classes.push(arg.toString());
					continue;
				}

				for (var key in arg) {
					if (hasOwn.call(arg, key) && arg[key]) {
						classes.push(key);
					}
				}
			}
		}

		return classes.join(' ');
	}

	if ( true && module.exports) {
		classNames.default = classNames;
		module.exports = classNames;
	} else if (true) {
		// register as 'classnames', consistent with npm package name
		!(__WEBPACK_AMD_DEFINE_ARRAY__ = [], __WEBPACK_AMD_DEFINE_RESULT__ = (function () {
			return classNames;
		}).apply(exports, __WEBPACK_AMD_DEFINE_ARRAY__),
		__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__));
	} else {}
}());


/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	(() => {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = (module) => {
/******/ 			var getter = module && module.__esModule ?
/******/ 				() => (module['default']) :
/******/ 				() => (module);
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be in strict mode.
(() => {
"use strict";
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(4184);
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(classnames__WEBPACK_IMPORTED_MODULE_0__);
function _extends() { _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; }; return _extends.apply(this, arguments); }

function _toConsumableArray(arr) { return _arrayWithoutHoles(arr) || _iterableToArray(arr) || _unsupportedIterableToArray(arr) || _nonIterableSpread(); }

function _nonIterableSpread() { throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _iterableToArray(iter) { if (typeof Symbol !== "undefined" && iter[Symbol.iterator] != null || iter["@@iterator"] != null) return Array.from(iter); }

function _arrayWithoutHoles(arr) { if (Array.isArray(arr)) return _arrayLikeToArray(arr); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }

function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); enumerableOnly && (symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; })), keys.push.apply(keys, symbols); } return keys; }

function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = null != arguments[i] ? arguments[i] : {}; i % 2 ? ownKeys(Object(source), !0).forEach(function (key) { _defineProperty(target, key, source[key]); }) : Object.getOwnPropertyDescriptors ? Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)) : ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } return target; }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }


var __ = wp.i18n.__;
var addFilter = wp.hooks.addFilter;
var _lodash = lodash,
    assign = _lodash.assign,
    merge = _lodash.merge;

var createHigherOrderComponent = wp.compose.createHigherOrderComponent;
var isPremium = Boolean(blockonsEditorObj.isPremium);
var blockOptions = blockonsEditorObj.blockonsOptions;
/**
 * Add New Attributes to all blocks
 */

function blockonsAddCustomAttributes(settings, name) {
  var _blockOptions$blockvi, _blockOptions$blockan;

  // console.log({ settings, name });
  var blockVisibilityAtts = ((_blockOptions$blockvi = blockOptions.blockvisibility) === null || _blockOptions$blockvi === void 0 ? void 0 : _blockOptions$blockvi.enabled) === true ? {
    blockonsHideOnDesktop: {
      type: "boolean",
      "default": false
    },
    blockonsHideOnTablet: {
      type: "boolean",
      "default": false
    },
    blockonsHideOnMobile: {
      type: "boolean",
      "default": false
    }
  } : {};
  var blockAnimationAtts = ((_blockOptions$blockan = blockOptions.blockanimation) === null || _blockOptions$blockan === void 0 ? void 0 : _blockOptions$blockan.enabled) === true ? {
    blockonsEnableAnimation: {
      type: "boolean",
      "default": false
    },
    blockonsAnimation: {
      type: "string",
      "default": "fade"
    },
    blockonsAnimationDirection: {
      type: "string",
      "default": "-up"
    },
    blockonsAnimationDuration: {
      type: "number",
      "default": 400
    },
    blockonsAnimationDelay: {
      type: "number",
      "default": 50
    },
    blockonsAnimationOffset: {
      type: "number",
      "default": 120
    },
    blockonsAnimationOnce: {
      type: "boolean",
      "default": false
    },
    blockonsAnimationMirror: {
      type: "boolean",
      "default": false
    }
  } : {};

  var newAttributes = _objectSpread(_objectSpread({}, blockVisibilityAtts), blockAnimationAtts);

  return assign({}, settings, {
    attributes: merge(settings.attributes, newAttributes)
  }); // return settings;
}
/**
 * Add New Controls to all blocks
 */


var blockonsAddInspectorControls = createHigherOrderComponent(function (BlockEdit) {
  return function (props) {
    var _blockOptions$blockvi2, _blockOptions$blockan2;

    var Fragment = wp.element.Fragment;
    var InspectorControls = wp.blockEditor.InspectorControls;
    var _wp$components = wp.components,
        PanelBody = _wp$components.PanelBody,
        ToggleControl = _wp$components.ToggleControl,
        RangeControl = _wp$components.RangeControl,
        SelectControl = _wp$components.SelectControl;
    var _props$attributes = props.attributes,
        blockonsHideOnDesktop = _props$attributes.blockonsHideOnDesktop,
        blockonsHideOnTablet = _props$attributes.blockonsHideOnTablet,
        blockonsHideOnMobile = _props$attributes.blockonsHideOnMobile,
        blockonsEnableAnimation = _props$attributes.blockonsEnableAnimation,
        blockonsAnimation = _props$attributes.blockonsAnimation,
        blockonsAnimationDirection = _props$attributes.blockonsAnimationDirection,
        blockonsAnimationDuration = _props$attributes.blockonsAnimationDuration,
        blockonsAnimationDelay = _props$attributes.blockonsAnimationDelay,
        blockonsAnimationOffset = _props$attributes.blockonsAnimationOffset,
        blockonsAnimationOnce = _props$attributes.blockonsAnimationOnce,
        blockonsAnimationMirror = _props$attributes.blockonsAnimationMirror,
        setAttributes = props.setAttributes,
        name = props.name;
    return /*#__PURE__*/React.createElement(Fragment, null, /*#__PURE__*/React.createElement(BlockEdit, props), ((_blockOptions$blockvi2 = blockOptions.blockvisibility) === null || _blockOptions$blockvi2 === void 0 ? void 0 : _blockOptions$blockvi2.enabled) === true && /*#__PURE__*/React.createElement(InspectorControls, null, /*#__PURE__*/React.createElement(PanelBody, {
      title: __("Block Visibility", "blockons"),
      initialOpen: false
    }, /*#__PURE__*/React.createElement(ToggleControl, {
      checked: blockonsHideOnDesktop,
      label: __("Hide on desktop", "blockons"),
      onChange: function onChange(newValue) {
        return setAttributes({
          blockonsHideOnDesktop: newValue
        });
      }
    }), /*#__PURE__*/React.createElement(ToggleControl, {
      checked: blockonsHideOnTablet,
      label: __("Hide on tablet", "blockons"),
      onChange: function onChange(newValue) {
        return setAttributes({
          blockonsHideOnTablet: newValue
        });
      }
    }), /*#__PURE__*/React.createElement(ToggleControl, {
      checked: blockonsHideOnMobile,
      label: __("Hide on mobile", "blockons"),
      onChange: function onChange(newValue) {
        return setAttributes({
          blockonsHideOnMobile: newValue
        });
      }
    }))), ((_blockOptions$blockan2 = blockOptions.blockanimation) === null || _blockOptions$blockan2 === void 0 ? void 0 : _blockOptions$blockan2.enabled) === true && /*#__PURE__*/React.createElement(InspectorControls, null, /*#__PURE__*/React.createElement(PanelBody, {
      title: __("Block Animations", "blockons"),
      initialOpen: false
    }, /*#__PURE__*/React.createElement(ToggleControl, {
      checked: blockonsEnableAnimation,
      label: __("Enable Block Animations", "blockons"),
      onChange: function onChange(newValue) {
        setAttributes({
          blockonsEnableAnimation: newValue
        });
      }
    }), blockonsEnableAnimation && /*#__PURE__*/React.createElement(React.Fragment, null, /*#__PURE__*/React.createElement(SelectControl, {
      label: "Style",
      value: blockonsAnimation,
      options: [{
        label: "Fade",
        value: "fade"
      }, {
        label: "Slide",
        value: "slide"
      }, {
        label: "Flip",
        value: "flip"
      }, {
        label: "Zoom In",
        value: "zoom-in"
      }, {
        label: "Zoom Out",
        value: "zoom-out"
      }],
      onChange: function onChange(newAnimationStyle) {
        setAttributes({
          blockonsAnimation: newAnimationStyle
        });
      },
      __nextHasNoMarginBottom: true
    }), /*#__PURE__*/React.createElement(SelectControl, {
      label: "Direction",
      value: blockonsAnimationDirection,
      options: [{
        label: "Up",
        value: "-up"
      }, {
        label: "Down",
        value: "-down"
      }, {
        label: "Left",
        value: "-left"
      }, {
        label: "Right",
        value: "-right"
      }].concat(_toConsumableArray(blockonsAnimation === "zoom-in" || blockonsAnimation === "zoom-out" ? [{
        label: "None",
        value: ""
      }] : []), _toConsumableArray(blockonsAnimation === "fade" ? [{
        label: "Up Right",
        value: "-up-right"
      }, {
        label: "Up Left",
        value: "-up-left"
      }, {
        label: "Down Right",
        value: "-down-right"
      }, {
        label: "Down Left",
        value: "-down-left"
      }] : [])),
      onChange: function onChange(newAnimationDirection) {
        setAttributes({
          blockonsAnimationDirection: newAnimationDirection
        });
      },
      __nextHasNoMarginBottom: true
    }), /*#__PURE__*/React.createElement(RangeControl, {
      label: __("Duration", "blockons"),
      value: blockonsAnimationDuration,
      onChange: function onChange(newDurationValue) {
        return setAttributes({
          blockonsAnimationDuration: parseInt(newDurationValue)
        });
      },
      min: 50,
      max: 3000,
      step: 50,
      help: __("How long the animation takes - 1000 = 1 second", "blockons")
    }), /*#__PURE__*/React.createElement(RangeControl, {
      label: __("Delay", "blockons"),
      value: blockonsAnimationDelay,
      onChange: function onChange(newDelayValue) {
        return setAttributes({
          blockonsAnimationDelay: parseInt(newDelayValue)
        });
      },
      min: 0,
      max: 3000,
      step: 50,
      help: __("The milliseconds before the animation starts - 1000 = 1 second", "blockons")
    }), /*#__PURE__*/React.createElement(RangeControl, {
      label: __("Offset", "blockons"),
      value: blockonsAnimationOffset,
      onChange: function onChange(newOffsetValue) {
        return setAttributes({
          blockonsAnimationOffset: parseInt(newOffsetValue)
        });
      },
      min: 0,
      max: 600,
      step: 5,
      help: __("The offset in pixels from the bottom of the window", "blockons")
    }), /*#__PURE__*/React.createElement(ToggleControl, {
      checked: blockonsAnimationOnce,
      label: __("Animate Once", "blockons"),
      onChange: function onChange(newValue) {
        setAttributes({
          blockonsAnimationOnce: newValue
        });
      },
      help: __("Whether animation should happen only once - while scrolling down", "blockons")
    }), /*#__PURE__*/React.createElement(ToggleControl, {
      checked: blockonsAnimationMirror,
      label: __("Mirror Animations", "blockons"),
      onChange: function onChange(newValue) {
        setAttributes({
          blockonsAnimationMirror: newValue
        });
      },
      help: __("Whether elements should animate out while scrolling past them", "blockons")
    })))));
  };
}, "blockonsAddInspectorControls");
/**
 * Add CSS Classes to the blocks in the editor
 */

var blockonsAddEditorNewAttributes = createHigherOrderComponent(function (BlockListBlock) {
  return function (props) {
    var _blockOptions$blockvi3;

    var _props$attributes2 = props.attributes,
        blockonsHideOnDesktop = _props$attributes2.blockonsHideOnDesktop,
        blockonsHideOnTablet = _props$attributes2.blockonsHideOnTablet,
        blockonsHideOnMobile = _props$attributes2.blockonsHideOnMobile,
        blockonsEnableAnimation = _props$attributes2.blockonsEnableAnimation,
        blockonsAnimation = _props$attributes2.blockonsAnimation,
        blockonsAnimationDirection = _props$attributes2.blockonsAnimationDirection,
        blockonsAnimationDuration = _props$attributes2.blockonsAnimationDuration,
        blockonsAnimationDelay = _props$attributes2.blockonsAnimationDelay,
        blockonsAnimationOffset = _props$attributes2.blockonsAnimationOffset,
        blockonsAnimationOnce = _props$attributes2.blockonsAnimationOnce,
        blockonsAnimationMirror = _props$attributes2.blockonsAnimationMirror,
        className = props.className,
        name = props.name;
    var newClassnames = isPremium && ((_blockOptions$blockvi3 = blockOptions.blockvisibility) === null || _blockOptions$blockvi3 === void 0 ? void 0 : _blockOptions$blockvi3.enabled) === true ? classnames__WEBPACK_IMPORTED_MODULE_0___default()(className, "".concat(blockonsHideOnDesktop ? "hide-on-desktop" : "", " ").concat(blockonsHideOnTablet ? "hide-on-tablet" : "", " ").concat(blockonsHideOnMobile ? "hide-on-mobile" : "")) : className;
    var newWrapperProps = isPremium && blockonsEnableAnimation && blockonsEnableAnimation === true ? {
      "data-aos": "".concat(blockonsAnimation).concat(blockonsAnimationDirection),
      "data-aos-easing": "ease-in-out",
      "data-aos-duration": blockonsAnimationDuration,
      "data-aos-delay": blockonsAnimationDelay,
      "data-aos-offset": blockonsAnimationOffset,
      "data-aos-once": blockonsAnimationOnce,
      "data-aos-mirror": blockonsAnimationMirror
    } : {};
    return /*#__PURE__*/React.createElement(BlockListBlock, _extends({}, props, {
      className: newClassnames,
      wrapperProps: newWrapperProps // ANIMATIONS

    }));
  };
}, "blockonsAddEditorNewAttributes");
/**
 * Add Classes to the blocks on the Frontend
 */

var blockonsAddFrontendNewAttributes = function blockonsAddFrontendNewAttributes(extraProps, blockType, attributes) {
  var _blockOptions$blockvi4, _blockOptions$blockan3;

  var blockonsHideOnDesktop = attributes.blockonsHideOnDesktop,
      blockonsHideOnTablet = attributes.blockonsHideOnTablet,
      blockonsHideOnMobile = attributes.blockonsHideOnMobile,
      blockonsEnableAnimation = attributes.blockonsEnableAnimation,
      blockonsAnimation = attributes.blockonsAnimation,
      blockonsAnimationDirection = attributes.blockonsAnimationDirection,
      blockonsAnimationDuration = attributes.blockonsAnimationDuration,
      blockonsAnimationDelay = attributes.blockonsAnimationDelay,
      blockonsAnimationOffset = attributes.blockonsAnimationOffset,
      blockonsAnimationOnce = attributes.blockonsAnimationOnce,
      blockonsAnimationMirror = attributes.blockonsAnimationMirror;

  if (((_blockOptions$blockvi4 = blockOptions.blockvisibility) === null || _blockOptions$blockvi4 === void 0 ? void 0 : _blockOptions$blockvi4.enabled) === true) {
    extraProps.className = classnames__WEBPACK_IMPORTED_MODULE_0___default()(extraProps.className, {
      "hide-on-desktop": blockonsHideOnDesktop,
      "hide-on-tablet": blockonsHideOnTablet,
      "hide-on-mobile": blockonsHideOnMobile
    });
  }

  if (((_blockOptions$blockan3 = blockOptions.blockanimation) === null || _blockOptions$blockan3 === void 0 ? void 0 : _blockOptions$blockan3.enabled) === true && blockonsEnableAnimation) {
    extraProps["data-aos"] = "".concat(blockonsAnimation).concat(blockonsAnimationDirection);
    extraProps["data-aos-easing"] = "ease-in-out";
    extraProps["data-aos-duration"] = blockonsAnimationDuration;
    extraProps["data-aos-delay"] = blockonsAnimationDelay;
    extraProps["data-aos-offset"] = blockonsAnimationOffset;
    extraProps["data-aos-once"] = blockonsAnimationOnce;
    extraProps["data-aos-mirror"] = blockonsAnimationMirror;
  }

  return extraProps;
};
/**
 * WP Editor Hooks
 */


if (isPremium) {
  addFilter("blocks.registerBlockType", "blockons/block-newsettings", blockonsAddCustomAttributes);
  addFilter("editor.BlockEdit", "blockons/block-newsettings-controls", blockonsAddInspectorControls);
  addFilter("blocks.getSaveContent.extraProps", "blockons/block-newsettings-frontend-clases", blockonsAddFrontendNewAttributes);
  addFilter("editor.BlockListBlock", "blockons/block-newsettings-editor-clases", blockonsAddEditorNewAttributes);
}
})();

/******/ })()
;