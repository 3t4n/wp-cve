/******/ (function() { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./includes/blocks/src/quiz-reports/editor.scss":
/*!******************************************************!*\
  !*** ./includes/blocks/src/quiz-reports/editor.scss ***!
  \******************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "react":
/*!************************!*\
  !*** external "React" ***!
  \************************/
/***/ (function(module) {

module.exports = window["React"];

/***/ }),

/***/ "@wordpress/block-editor":
/*!*************************************!*\
  !*** external ["wp","blockEditor"] ***!
  \*************************************/
/***/ (function(module) {

module.exports = window["wp"]["blockEditor"];

/***/ }),

/***/ "@wordpress/blocks":
/*!********************************!*\
  !*** external ["wp","blocks"] ***!
  \********************************/
/***/ (function(module) {

module.exports = window["wp"]["blocks"];

/***/ }),

/***/ "@wordpress/element":
/*!*********************************!*\
  !*** external ["wp","element"] ***!
  \*********************************/
/***/ (function(module) {

module.exports = window["wp"]["element"];

/***/ }),

/***/ "@wordpress/i18n":
/*!******************************!*\
  !*** external ["wp","i18n"] ***!
  \******************************/
/***/ (function(module) {

module.exports = window["wp"]["i18n"];

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
/******/ 	!function() {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = function(module) {
/******/ 			var getter = module && module.__esModule ?
/******/ 				function() { return module['default']; } :
/******/ 				function() { return module; };
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	!function() {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = function(exports, definition) {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	!function() {
/******/ 		__webpack_require__.o = function(obj, prop) { return Object.prototype.hasOwnProperty.call(obj, prop); }
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	!function() {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = function(exports) {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	}();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be isolated against other modules in the chunk.
!function() {
/*!*****************************************************************!*\
  !*** ./includes/blocks/src/quiz-reports/editor-quiz-reports.js ***!
  \*****************************************************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/blocks */ "@wordpress/blocks");
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_blocks__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/block-editor */ "@wordpress/block-editor");
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _editor_scss__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./editor.scss */ "./includes/blocks/src/quiz-reports/editor.scss");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_5__);

/**
 * Registers a new block provided a unique name and an object defining its behavior.
 *
 * @see https://developer.wordpress.org/block-editor/developers/block-api/#registering-a-block
 */


/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-i18n/
 */




const icon = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
  version: "1.0",
  xmlns: "http://www.w3.org/2000/svg",
  width: "24.000000pt",
  height: "24.000000pt",
  viewBox: "0 0 24.000000 24.000000",
  preserveAspectRatio: "xMidYMid meet"
}, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
  d: "M11.9,8.7h4.8c0.3,0,0.6-0.3,0.6-0.7c0-0.4-0.3-0.7-0.6-0.7h-4.8c-0.3,0-0.6,0.3-0.6,0.7C11.4,8.3,11.6,8.7,11.9,8.7z"
}), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
  d: "M16.7,12.4h-4.8c-0.3,0-0.6,0.3-0.6,0.7s0.3,0.7,0.6,0.7h4.8c0.3,0,0.6-0.3,0.6-0.7S17,12.4,16.7,12.4z"
}), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
  d: "M16.5,17.5h-4.8c-0.3,0-0.6,0.3-0.6,0.7c0,0.4,0.3,0.7,0.6,0.7h4.8c0.3,0,0.6-0.3,0.6-0.7C17.1,17.5,16.7,17.5,16.5,17.5z"
}), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
  d: "M7.5,6.2C7.4,6.1,7.2,6.1,7,6.2C6.8,6.3,6.7,6.5,6.7,6.7v2.9c0,0.2,0.1,0.5,0.3,0.5c0.2,0,0.4-0.1,0.5-0.2l2.3-1.4 C9.9,8.4,10,8.2,10,8.1c0-0.2-0.1-0.3-0.2-0.4L7.5,6.2z M8.6,8.1L7.7,8.6v-1L8.6,8.1z"
}), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
  d: "M7.5,11.2c-0.1-0.1-0.3-0.1-0.5,0c-0.2,0.1-0.3,0.3-0.3,0.5v2.9c0,0.2,0.1,0.5,0.3,0.5c0.2,0,0.4-0.1,0.5-0.2l2.3-1.4 c0.1-0.1,0.2-0.3,0.2-0.4c0-0.2-0.1-0.3-0.2-0.4L7.5,11.2z M8.6,13.1l-0.9,0.5v-1L8.6,13.1z"
}), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
  d: "M7.5,16.2c-0.1-0.1-0.3-0.1-0.5,0s-0.3,0.3-0.3,0.5v2.9c0,0.2,0.1,0.5,0.3,0.5c0.2,0,0.4-0.1,0.5-0.2l2.3-1.4 c0.1-0.1,0.2-0.3,0.2-0.4c0-0.2-0.1-0.3-0.2-0.4L7.5,16.2z M8.6,18.1l-0.9,0.5v-1L8.6,18.1z"
}), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
  d: "M2.9,21.6c0,1.1,0.9,2,1.9,2h14.4c1.1,0,1.9-0.9,1.9-2V3.7c0-1.1-0.9-2-1.9-2h-2.6l-0.1-0.1c-0.7-0.8-1.7-1.2-2.7-1.2h-3.7 c-1,0-1.9,0.4-2.6,1.2l0,0.1H4.9c-1.1,0-2,0.9-2,2V21.6z M7.8,3.2c0.4-1,1.4-1.7,2.3-1.7h3.7c1,0,2,0.7,2.3,1.7l0.1,0.2H7.7L7.8,3.2 z M4.1,3.7c0-0.5,0.4-0.9,0.8-0.9h2L6.8,3C6.7,3.3,6.6,3.7,6.6,4c0,0.4,0.2,0.6,0.6,0.6h9.7c0.3,0,0.5-0.3,0.5-0.6s0-0.6-0.1-0.9 l-0.1-0.2h2c0.5,0,0.8,0.4,0.8,0.9v17.9c0,0.5-0.4,0.9-0.8,0.9H4.9c-0.5,0-0.8-0.4-0.8-0.9V3.7z"
}));
class QuizReport extends react__WEBPACK_IMPORTED_MODULE_5__.Component {
  constructor(props) {
    super(props);
    this.state = {
      isLoaded: false,
      error: null
    };
  }
  componentDidMount() {
    this.setState({
      isLoaded: true,
      isProVersion: wisdm_learndash_reports_editor_script_quiz_reports.is_pro_version_active
    });
  }
  render() {
    let body = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Loading...', 'learndash-reports-by-wisdmlabs'));
    if (!this.state.isLoaded) {
      body = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
        class: "wisdm-learndash-reports-chart-block wisdm-ld-loading"
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Loading...', 'learndash-reports-by-wisdmlabs')));
    } else if (this.state.error) {
      // error
      body = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
        class: "wisdm-learndash-reports-chart-block error"
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", null, this.state.error.message));
    } else {
      body = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
        class: "wisdm-learndash-reports-chart-block"
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
        class: "wisdm-learndash-reports-quiz-reports"
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('This block will be hidden by default & will display the Quiz Reports when quiz reports are requested', 'learndash-reports-by-wisdmlabs'))));
    }
    return body;
  }
}
(0,_wordpress_blocks__WEBPACK_IMPORTED_MODULE_1__.registerBlockType)('wisdm-learndash-reports/quiz-reports', {
  title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Quiz Reports', 'learndash-reports-by-wisdmlabs'),
  description: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('A table containing a list of the courses', 'learndash-reports-by-wisdmlabs'),
  category: 'wisdm-learndash-reports',
  className: 'learndash-reports-by-wisdmlabs-quiz-reports',
  icon,
  attributes: {
    blockContent: {
      type: 'html',
      default: ''
    }
  },
  /**
       * edit function
       * 
       * Makes the markup for the editor interface.
       * 
       * @param {object} ObjectArgs {
       *      className - Automatic CSS class. Based on the block name: gutenberg-block-samples-block-simple
       * }
       * 
       * @return {JSX object} ECMAScript JSX Markup for the editor 
       */
  edit(props) {
    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", (0,_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_3__.useBlockProps)(), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(QuizReport, null));
  },
  /**
   * save function
   * 
   * Makes the markup that will be rendered on the site page
   * 
   * @return {JSX object} ECMAScript JSX Markup for the site
   */
  save() {
    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_3__.useBlockProps.save(), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      id: "wisdm-learndash-reports-quiz-report-view",
      class: "wisdm-learndash-reports-quiz-reports"
    }, "[ldrp_quiz_reports]"));
  }
});
}();
/******/ })()
;
//# sourceMappingURL=editor-quiz-reports.js.map