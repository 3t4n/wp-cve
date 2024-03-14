/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = "./core/assets/js/blocks.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./core/assets/js/blocks.js":
/*!**********************************!*\
  !*** ./core/assets/js/blocks.js ***!
  \**********************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _blocks_collage__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./blocks/collage */ \"./core/assets/js/blocks/collage/index.js\");\n\n\n//# sourceURL=webpack:///./core/assets/js/blocks.js?");

/***/ }),

/***/ "./core/assets/js/blocks/collage/index.js":
/*!************************************************!*\
  !*** ./core/assets/js/blocks/collage/index.js ***!
  \************************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _css_public_scss__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../../../css/public.scss */ \"./core/css/public.scss\");\n/* harmony import */ var _css_public_scss__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_css_public_scss__WEBPACK_IMPORTED_MODULE_0__);\nvar __ = wp.i18n.__;\nvar _wp$components = wp.components,\n    Button = _wp$components.Button,\n    Disabled = _wp$components.Disabled,\n    ToolbarGroup = _wp$components.ToolbarGroup,\n    ToolbarButton = _wp$components.ToolbarButton;\n\nvar ServerSideRender = wp.serverSideRender;\nvar registerBlockType = wp.blocks.registerBlockType;\nvar Fragment = wp.element.Fragment;\nvar BlockControls = wp.blockEditor.BlockControls;\n\n\n\n\nregisterBlockType('easy-image-collage/collage', {\n    title: __('Easy Image Collage'),\n    description: __('Display multiple images in a collage.'),\n    icon: 'layout',\n    keywords: ['eic'],\n    category: 'layout',\n    supports: {\n        html: false\n    },\n    transforms: {\n        from: [{\n            type: 'shortcode',\n            tag: 'easy-image-collage',\n            attributes: {\n                id: {\n                    type: 'number',\n                    shortcode: function shortcode(_ref) {\n                        var _ref$named$id = _ref.named.id,\n                            id = _ref$named$id === undefined ? '' : _ref$named$id;\n\n                        return parseInt(id.replace('id', ''));\n                    }\n                }\n            }\n        }]\n    },\n    edit: function edit(props) {\n        var attributes = props.attributes,\n            setAttributes = props.setAttributes,\n            isSelected = props.isSelected,\n            className = props.className;\n\n\n        var modalCallback = function modalCallback(id) {\n            setAttributes({\n                id: id,\n                updated: Date.now()\n            });\n        };\n\n        return React.createElement(\n            'div',\n            { className: className },\n            attributes.id ? React.createElement(\n                Fragment,\n                null,\n                React.createElement(\n                    BlockControls,\n                    null,\n                    React.createElement(\n                        ToolbarGroup,\n                        null,\n                        React.createElement(ToolbarButton, {\n                            icon: 'edit',\n                            label: __('Edit'),\n                            onClick: function onClick() {\n                                EasyImageCollage.btnEditGrid(attributes.id, modalCallback);\n                            }\n                        })\n                    )\n                ),\n                React.createElement(\n                    Disabled,\n                    null,\n                    React.createElement(ServerSideRender, {\n                        block: 'easy-image-collage/collage',\n                        attributes: attributes\n                    })\n                )\n            ) : React.createElement(\n                Fragment,\n                null,\n                React.createElement(\n                    Button,\n                    {\n                        isPrimary: true,\n                        isLarge: true,\n                        onClick: function onClick() {\n                            EasyImageCollage.btnCreateGrid(attributes.id, modalCallback);\n                        } },\n                    __('Create new Image Collage')\n                )\n            )\n        );\n    },\n    save: function save(props) {\n        var id = props.attributes.id;\n\n        if (!id) {\n            return null;\n        } else {\n            // Store shortcode for compatibility with Classic Editor.\n            return '[easy-image-collage id=' + props.attributes.id + ']';\n        }\n    },\n    deprecated: [{\n        attributes: {\n            id: {\n                type: 'number',\n                default: 0\n            }\n        },\n        save: function save(props) {\n            return null;\n        }\n    }]\n});\n\n//# sourceURL=webpack:///./core/assets/js/blocks/collage/index.js?");

/***/ }),

/***/ "./core/css/public.scss":
/*!******************************!*\
  !*** ./core/css/public.scss ***!
  \******************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("// removed by extract-text-webpack-plugin\n\n//# sourceURL=webpack:///./core/css/public.scss?");

/***/ })

/******/ });