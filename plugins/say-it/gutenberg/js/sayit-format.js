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
/******/ 	return __webpack_require__(__webpack_require__.s = "./gutenberg/src/sayit-format.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./gutenberg/src/sayit-format.js":
/*!***************************************!*\
  !*** ./gutenberg/src/sayit-format.js ***!
  \***************************************/
/*! exports provided: sayit */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "sayit", function() { return sayit; });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_rich_text__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/rich-text */ "@wordpress/rich-text");
/* harmony import */ var _wordpress_rich_text__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_rich_text__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/block-editor */ "@wordpress/block-editor");
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _sayit_inline__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./sayit-inline */ "./gutenberg/src/sayit-inline.js");





/**
 * Block constants
 */

const name = 'sayit/sayit-inline';
const sayit = {
  name,
  title: 'Say It!',
  tagName: 'span',
  className: 'sayit',
  attributes: {
    mp3file: 'data-mp3-file',
    content: 'data-say-content'
  },
  edit: ({
    value,
    contentRef,
    onChange,
    isActive,
    activeAttributes
  }) => {
    const [loading, setLoading] = Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["useState"])(false);
    /*
     * Helper to update only one attributes
     */

    const setAttr = (attribute, newValue) => {
      let newattributes = { ...activeAttributes
      };
      newattributes[attribute] = newValue;
      onChange(Object(_wordpress_rich_text__WEBPACK_IMPORTED_MODULE_1__["applyFormat"])(value, {
        type: 'sayit/sayit-inline',
        attributes: newattributes
      }));
    };
    /*
     * Load mp3 from ajax method
     */


    const loadMP3 = async val => {
      setLoading(true);
      wp.ajax.post("sayit_mp3", {
        words: activeAttributes.content
      }).done(function (response) {
        setLoading(false);
        setAttr('mp3file', response.mp3);
      });
    };
    /*
     * Toggle the format
     */


    const onToggle = () => {
      let textContent = Object(_wordpress_rich_text__WEBPACK_IMPORTED_MODULE_1__["getTextContent"])(Object(_wordpress_rich_text__WEBPACK_IMPORTED_MODULE_1__["slice"])(value));
      onChange(Object(_wordpress_rich_text__WEBPACK_IMPORTED_MODULE_1__["toggleFormat"])(value, {
        type: 'sayit/sayit-inline',
        attributes: {
          content: textContent
        }
      }));
    };

    return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["Fragment"], null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_2__["RichTextShortcut"], {
      type: "primaryShift",
      character: "s",
      onUse: onToggle
    }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_2__["RichTextToolbarButton"], {
      icon: "admin-comments",
      title: "Say it !",
      onClick: onToggle,
      isActive: isActive,
      shortcutType: "primaryShift",
      shortcutCharacter: "s"
    }), isActive && Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_sayit_inline__WEBPACK_IMPORTED_MODULE_3__["default"], {
      isActive: isActive,
      activeAttributes: activeAttributes,
      value: value,
      contentRef: contentRef,
      onChange: onChange,
      loading: loading,
      setAttr: setAttr,
      loadMP3: loadMP3
    }));
  }
};

function registerFormats() {
  [sayit].forEach(({
    name,
    ...settings
  }) => Object(_wordpress_rich_text__WEBPACK_IMPORTED_MODULE_1__["registerFormatType"])(name, settings));
}

;
registerFormats();

/***/ }),

/***/ "./gutenberg/src/sayit-inline.js":
/*!***************************************!*\
  !*** ./gutenberg/src/sayit-inline.js ***!
  \***************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_rich_text__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/rich-text */ "@wordpress/rich-text");
/* harmony import */ var _wordpress_rich_text__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_rich_text__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _sayit_format__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./sayit-format */ "./gutenberg/src/sayit-format.js");





function SayItPopoverUI({
  isActive,
  activeAttributes,
  value,
  contentRef,
  onChange,
  loading,
  setAttr,
  loadMP3
}) {
  const anchorRef = Object(_wordpress_rich_text__WEBPACK_IMPORTED_MODULE_1__["useAnchorRef"])({
    ref: contentRef,
    value,
    settings: _sayit_format__WEBPACK_IMPORTED_MODULE_3__["sayit"]
  });
  return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__["Popover"], {
    anchorRef: anchorRef,
    position: "bottom center"
  }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("div", {
    style: {
      width: '280px',
      padding: '20px'
    }
  }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__["TextControl"], {
    placeholder: "Speech Content",
    label: "speech content",
    help: "Contenu textuel \xE0 lire",
    value: activeAttributes.content ? activeAttributes.content : '',
    onChange: newContent => {
      setAttr('content', newContent);
    }
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__["TextControl"], {
    placeholder: "Preloaded MP3",
    label: "Preloaded MP3",
    help: "Mp3 \xE0 lire, automatiquement g\xE9n\xE9r\xE9 si absent",
    value: activeAttributes.mp3file ? activeAttributes.mp3file : '',
    onChange: newMp3file => {
      setAttr('mp3file', newMp3file);
    }
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__["Button"], {
    isSecondary: true,
    isSmall: true,
    isBusy: loading,
    onClick: loadMP3
  }, "Preload MP3")));
}

/* harmony default export */ __webpack_exports__["default"] = (SayItPopoverUI);

/***/ }),

/***/ "@wordpress/block-editor":
/*!*************************************!*\
  !*** external ["wp","blockEditor"] ***!
  \*************************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function() { module.exports = window["wp"]["blockEditor"]; }());

/***/ }),

/***/ "@wordpress/components":
/*!************************************!*\
  !*** external ["wp","components"] ***!
  \************************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function() { module.exports = window["wp"]["components"]; }());

/***/ }),

/***/ "@wordpress/element":
/*!*********************************!*\
  !*** external ["wp","element"] ***!
  \*********************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function() { module.exports = window["wp"]["element"]; }());

/***/ }),

/***/ "@wordpress/rich-text":
/*!**********************************!*\
  !*** external ["wp","richText"] ***!
  \**********************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function() { module.exports = window["wp"]["richText"]; }());

/***/ })

/******/ });
//# sourceMappingURL=sayit-format.js.map