/******/ (function() { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./includes/blocks/src/commons/loader/index.js":
/*!*****************************************************!*\
  !*** ./includes/blocks/src/commons/loader/index.js ***!
  \*****************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__);



class WisdmLoader extends (react__WEBPACK_IMPORTED_MODULE_1___default().Component) {
  constructor(props) {
    super(props);
  }
  render() {
    let loadingData = '';
    let show_text = '';
    if (true == this.props.text) {
      show_text = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
        className: "supporting-text"
      }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Your report is being generated.', 'learndash-reports-by-wisdmlabs'));
    }
    loadingData = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      class: "wisdm-learndash-reports-chart-block"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      class: "wisdm-learndash-reports-revenue-from-courses graph-card-container"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      class: "wisdm-graph-loading"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("img", {
      src: wisdm_learndash_reports_front_end_script_total_revenue_earned.plugin_asset_url + '/images/loader.svg'
    }), show_text)));
    return loadingData;
  }
}
/* harmony default export */ __webpack_exports__["default"] = (WisdmLoader);

/***/ }),

/***/ "./includes/blocks/src/student-profile/index-student-profile.js":
/*!**********************************************************************!*\
  !*** ./includes/blocks/src/student-profile/index-student-profile.js ***!
  \**********************************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _index_scss__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./index.scss */ "./includes/blocks/src/student-profile/index.scss");
/* harmony import */ var _commons_loader_index_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../commons/loader/index.js */ "./includes/blocks/src/commons/loader/index.js");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__);





class StudentProfile extends react__WEBPACK_IMPORTED_MODULE_3__.Component {
  constructor(props) {
    super(props);
    let error = null;
    let avatar = this.getUserType() ? "www.gravatar.com/avatar/789047b2eb7fd33f3fb6858358dcc5d8?s=150&r=g&d=mm" : wisdm_learndash_reports_front_end_script_student_table.avatar_url;
    console.log(avatar);
    let username = this.getUserType() ? "No Name" : wisdm_learndash_reports_front_end_script_student_table.current_user.data.display_name;
    if (this.getUserType()) {
      error = {
        message: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)("Sorry you are not allowed to access this block, please check if you have proper access permissions", "learndash-reports-by-wisdmlabs")
      };
    }
    this.state = {
      isLoaded: true,
      userImage: avatar,
      userName: username,
      error: error
    };
  }

  /**
   * Based on the current user roles aray this function desides wether a user is a group
   * leader or an Administrator and returns the same.
   */
  getUserType() {
    if (wisdm_learndash_reports_front_end_script_student_table.current_user.ID == 0) {
      return true;
    }
    return false;
  }
  componentDidMount() {}
  componentDidUpdate() {}
  render() {
    let body = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", null);
    if (this.state.error) {
      // error
      body = '';
    } else if (!this.state.isLoaded) {
      // yet loading
      body = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_commons_loader_index_js__WEBPACK_IMPORTED_MODULE_2__["default"], null);
    } else {
      body = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
        className: "user-info-section"
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
        className: "thumbnail"
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("img", {
        alt: "",
        src: this.state.userImage,
        srcSet: this.state.userImage,
        className: "avatar avatar-96 photo",
        height: "96",
        width: "96",
        loading: "lazy",
        decoding: "async"
      })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
        className: "information"
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
        className: "label clabel"
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", null, "Student Name")), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
        className: "name"
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", null, this.state.userName))));
    }
    return body;
  }
}
/* harmony default export */ __webpack_exports__["default"] = (StudentProfile);
document.addEventListener("DOMContentLoaded", function (event) {
  let elem = document.getElementsByClassName("wisdm-learndash-reports-student-profile front");
  if (elem.length > 0) {
    ReactDOM.render(react__WEBPACK_IMPORTED_MODULE_3___default().createElement(StudentProfile), elem[0]);
  }
});

/***/ }),

/***/ "./includes/blocks/src/student-profile/editor.scss":
/*!*********************************************************!*\
  !*** ./includes/blocks/src/student-profile/editor.scss ***!
  \*********************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./includes/blocks/src/student-profile/index.scss":
/*!********************************************************!*\
  !*** ./includes/blocks/src/student-profile/index.scss ***!
  \********************************************************/
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

/***/ "@wordpress/hooks":
/*!*******************************!*\
  !*** external ["wp","hooks"] ***!
  \*******************************/
/***/ (function(module) {

module.exports = window["wp"]["hooks"];

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
/*!***********************************************************************!*\
  !*** ./includes/blocks/src/student-profile/editor-student-profile.js ***!
  \***********************************************************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/blocks */ "@wordpress/blocks");
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_blocks__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/block-editor */ "@wordpress/block-editor");
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _editor_scss__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./editor.scss */ "./includes/blocks/src/student-profile/editor.scss");
/* harmony import */ var _wordpress_hooks__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @wordpress/hooks */ "@wordpress/hooks");
/* harmony import */ var _wordpress_hooks__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_wordpress_hooks__WEBPACK_IMPORTED_MODULE_5__);
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_6__);
/* harmony import */ var _index_student_profile__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./index-student-profile */ "./includes/blocks/src/student-profile/index-student-profile.js");
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





let globalHooks = (0,_wordpress_hooks__WEBPACK_IMPORTED_MODULE_5__.createHooks)();


const icon = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_2__.createElement)("svg", {
  version: "1.0",
  xmlns: "http://www.w3.org/2000/svg",
  width: "24.000000pt",
  height: "24.000000pt",
  viewBox: "0 0 24.000000 24.000000",
  preserveAspectRatio: "xMidYMid meet"
}, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_2__.createElement)("g", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_2__.createElement)("path", {
  d: "M9.5,12.1c-0.3-0.2-1,0-1.1,0.2L7,13.6l-1.3-1.3c-0.2-0.3-0.9-0.4-1.2-0.1c-0.3,0.4-0.1,1,0.1,1.1l1.3,1.3L4.6,16 c-0.2,0.2-0.4,0.7,0,1.1c0.3,0.4,0.9,0.1,1.1-0.1L7,15.8l1.3,1.3c0.2,0.2,0.8,0.3,1.2,0c0.3-0.3,0.1-1-0.1-1.1l-1.3-1.3l1.3-1.3 C9.7,13.2,9.9,12.5,9.5,12.1z M5.4,16.9L5.4,16.9L5.4,16.9L5.4,16.9z"
}), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_2__.createElement)("path", {
  d: "M10.2,4.7C10,4.6,9.9,4.5,9.5,4.5C9.2,4.5,9,4.7,8.9,4.8L6,7.7l-1-1c-0.3-0.3-0.9-0.3-1.2,0C3.4,7,3.4,7.5,3.8,7.9l1.7,1.6 c0.2,0.2,0.4,0.3,0.6,0.3s0.4-0.1,0.6-0.3L10.2,6c0.2-0.2,0.3-0.4,0.3-0.6S10.4,4.9,10.2,4.7z"
})), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_2__.createElement)("path", {
  d: "M12,19.3H3.5c-1,0-1.8-0.8-1.8-1.8V4.8c0-1,0.8-1.8,1.8-1.8h14.4c1,0,1.8,0.8,1.8,1.8v4.7c0,0.4,0.3,0.8,0.8,0.8 s0.8-0.3,0.8-0.8V4.8c-0.1-1.7-1.4-3-2.9-3.1l0,0h-15C1.6,1.8,0.2,3.2,0.2,4.8v12.8c0,1.8,1.5,3.2,3.2,3.2h8.5 c0.4,0,0.8-0.3,0.8-0.8C12.7,19.7,12.4,19.3,12,19.3z"
}), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_2__.createElement)("g", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_2__.createElement)("path", {
  d: "M15.9,19.6L15.9,19.6c-0.2-0.1-0.4-0.4-0.1-0.6l3.4-6c0.1-0.2,0.4-0.4,0.6-0.1l0,0c0.2,0.1,0.4,0.4,0.1,0.6l-3.4,5.9 C16.4,19.6,16.2,19.7,15.9,19.6z"
}), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_2__.createElement)("path", {
  d: "M15.3,13.5c-0.8,0-1.5,0.7-1.5,1.5s0.7,1.5,1.5,1.5c0.8,0,1.5-0.7,1.5-1.5C16.9,14.2,16.2,13.5,15.3,13.5z"
}), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_2__.createElement)("path", {
  d: "M20.3,16.2c-0.8,0-1.5,0.7-1.5,1.5s0.7,1.5,1.5,1.5s1.5-0.7,1.5-1.5S21.2,16.2,20.3,16.2z"
})), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_2__.createElement)("path", {
  d: "M17.8,22.1c-3.3,0-6-2.7-6-6c0-3.3,2.7-6,6-6s6,2.7,6,6C23.7,19.5,21.1,22.1,17.8,22.1z M17.8,11c-2.9,0-5.2,2.3-5.2,5.2 s2.3,5.2,5.2,5.2S23,19,23,16.2S20.6,11,17.8,11z"
}));
(0,_wordpress_blocks__WEBPACK_IMPORTED_MODULE_0__.registerBlockType)('wisdm-learndash-reports/student-profile', {
  title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Student Profile', 'learndash-reports-by-wisdmlabs'),
  description: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Student Profile', 'learndash-reports-by-wisdmlabs'),
  category: 'wisdm-learndash-reports',
  className: 'learndash-reports-by-wisdmlabs-student-dashboard',
  icon,
  attributes: {},
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
    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_2__.createElement)("div", (0,_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_3__.useBlockProps)(), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_2__.createElement)(_index_student_profile__WEBPACK_IMPORTED_MODULE_7__["default"], null));
  },
  /**
   * save function
   * 
   * Makes the markup that will be rendered on the site page
   * 
   * @return {JSX object} ECMAScript JSX Markup for the site
   */
  save() {
    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_2__.createElement)("div", _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_3__.useBlockProps.save(), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_2__.createElement)("div", {
      class: "wisdm-learndash-reports-student-profile front"
    }));
  }
});
}();
/******/ })()
;
//# sourceMappingURL=editor-student-profile.js.map