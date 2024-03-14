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

/***/ "./includes/blocks/src/total-courses/index-total-courses.js":
/*!******************************************************************!*\
  !*** ./includes/blocks/src/total-courses/index-total-courses.js ***!
  \******************************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _index_scss__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./index.scss */ "./includes/blocks/src/total-courses/index.scss");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _commons_loader_index_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../commons/loader/index.js */ "./includes/blocks/src/commons/loader/index.js");
/* harmony import */ var moment__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! moment */ "moment");
/* harmony import */ var moment__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(moment__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__);






class TotalCourses extends react__WEBPACK_IMPORTED_MODULE_2__.Component {
  constructor(props) {
    super(props);
    this.state = {
      isLoaded: false,
      error: null,
      start_date: moment__WEBPACK_IMPORTED_MODULE_4___default()(new Date(wisdm_ld_reports_common_script_data.start_date)).unix(),
      end_date: moment__WEBPACK_IMPORTED_MODULE_4___default()(new Date(wisdm_ld_reports_common_script_data.end_date)).unix()
    };
    this.durationUpdated = this.durationUpdated.bind(this);
    this.updateBlock = this.updateBlock.bind(this);
  }
  durationUpdated(event) {
    this.setState({
      start_date: event.detail.startDate,
      end_date: event.detail.endDate
    });
    this.updateBlock();
  }
  componentDidMount() {
    document.addEventListener('duration_updated', this.durationUpdated);
    this.updateBlock();
  }
  updateBlock() {
    let callback = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : '/rp/v1/total-courses';
    var requestUrl = '/rp/v1/total-courses?start_date=' + this.state.start_date + '&end_date=' + this.state.end_date;
    if (wisdm_ld_reports_common_script_data.wpml_lang) {
      requestUrl += '&wpml_lang=' + wisdm_ld_reports_common_script_data.wpml_lang;
    }
    wp.apiFetch({
      path: requestUrl
    }).then(response => {
      let percentChange = response.percentChange;
      let chnageDirectionClass = 'udup';
      let percentValueClass = 'change-value';
      let hideChange = '';
      let udtxt = '';
      let udsrc = '';
      if (0 < percentChange) {
        chnageDirectionClass = 'udup';
        percentValueClass = 'change-value-positive';
        udtxt = (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Up', 'learndash-reports-by-wisdmlabs');
        udsrc = wisdm_learndash_reports_front_end_script_total_revenue_earned.plugin_asset_url + '/images/up.png';
      } else if (0 > percentChange) {
        chnageDirectionClass = 'uddown';
        percentValueClass = 'change-value-negative';
        udtxt = (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Down', 'learndash-reports-by-wisdmlabs');
        udsrc = wisdm_learndash_reports_front_end_script_total_revenue_earned.plugin_asset_url + '/images/down.png';
      } else if (0 == percentChange) {
        hideChange = 'wrld-hidden';
        udtxt = (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Up', 'learndash-reports-by-wisdmlabs');
        udsrc = wisdm_learndash_reports_front_end_script_total_revenue_earned.plugin_asset_url + '/images/up.png';
      }
      this.setState({
        isLoaded: true,
        graphData: {
          totalCourses: response.totalCourses,
          percentChange: percentChange + '%',
          chnageDirectionClass: chnageDirectionClass,
          percentValueClass: percentValueClass,
          hideChange: hideChange,
          udtxt: udtxt,
          udsrc: udsrc
        },
        startDate: moment__WEBPACK_IMPORTED_MODULE_4___default().unix(response.requestData.start_date).format("MMM, DD YYYY"),
        endDate: moment__WEBPACK_IMPORTED_MODULE_4___default().unix(response.requestData.end_date).format("MMM, DD YYYY")
      });
    }).catch(error => {
      this.setState({
        error: error,
        graph_summary: [],
        isLoaded: true,
        series: []
      });
    });
  }
  render() {
    let body = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", null);
    if (!this.state.isLoaded) {
      // yet loading
      body = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_commons_loader_index_js__WEBPACK_IMPORTED_MODULE_3__["default"], null);
    } else if (this.state.error) {
      // error
      body = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
        class: "wisdm-learndash-reports-chart-block error"
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", null, this.state.error.message));
    } else {
      body = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
        class: "wisdm-learndash-reports-chart-block"
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
        class: "total-courses-container top-card-container"
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
        className: "wrld-date-filter"
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
        class: "dashicons dashicons-calendar-alt"
      }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
        className: "wdm-tooltip"
      }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Date filter applied:', 'learndash-reports-by-wisdmlabs'), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("br", null), this.state.startDate, " - ", this.state.endDate)), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
        class: "total-courses-icon"
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("img", {
        src: wisdm_learndash_reports_front_end_script_total_courses.plugin_asset_url + '/images/icon_course_counter.png'
      })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
        class: "total-courses-details"
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
        class: "total-courses-text top-label-text"
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Total', 'learndash-reports-by-wisdmlabs') + ' ' + wisdm_reports_get_ld_custom_lebel_if_avaiable('courses'))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
        class: "total-courses-figure"
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", null, this.state.graphData.totalCourses)), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
        class: `total-courses-percent-change ${this.state.graphData.hideChange}`
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
        class: this.state.graphData.chnageDirectionClass
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("img", {
        src: this.state.graphData.udsrc
      })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
        class: this.state.graphData.percentValueClass
      }, this.state.graphData.percentChange), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
        class: "ud-txt"
      }, this.state.graphData.udtxt)))));
    }
    return body;
  }
}
/* harmony default export */ __webpack_exports__["default"] = (TotalCourses);
document.addEventListener("DOMContentLoaded", function (event) {
  let elem = document.getElementsByClassName('wisdm-learndash-reports-total-courses front');
  if (elem.length > 0) {
    ReactDOM.render(react__WEBPACK_IMPORTED_MODULE_2___default().createElement(TotalCourses), elem[0]);
  }
});

/***/ }),

/***/ "./includes/blocks/src/total-courses/editor.scss":
/*!*******************************************************!*\
  !*** ./includes/blocks/src/total-courses/editor.scss ***!
  \*******************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./includes/blocks/src/total-courses/index.scss":
/*!******************************************************!*\
  !*** ./includes/blocks/src/total-courses/index.scss ***!
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

/***/ "moment":
/*!*************************!*\
  !*** external "moment" ***!
  \*************************/
/***/ (function(module) {

module.exports = window["moment"];

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
/*!*******************************************************************!*\
  !*** ./includes/blocks/src/total-courses/editor-total-courses.js ***!
  \*******************************************************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/blocks */ "@wordpress/blocks");
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_blocks__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _commons_loader_index_js__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../commons/loader/index.js */ "./includes/blocks/src/commons/loader/index.js");
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @wordpress/block-editor */ "@wordpress/block-editor");
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_5__);
/* harmony import */ var _editor_scss__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./editor.scss */ "./includes/blocks/src/total-courses/editor.scss");
/* harmony import */ var _index_total_courses_js__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./index-total-courses.js */ "./includes/blocks/src/total-courses/index-total-courses.js");

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
  d: "M9.2,6.9C9,6.8,8.9,6.8,8.7,6.8l0,0C8.3,6.7,8,7.2,8,7.4v5.7c0,0.3,0.2,0.5,0.3,0.6s0.3,0.1,0.4,0.1c0.1,0,0.2,0,0.3-0.1 l4.3-2.9c0.3-0.2,0.5-0.3,0.5-0.5s-0.1-0.3-0.4-0.5L9.2,6.9z M11.8,10.3l-2.5,1.5V8.7L11.8,10.3z"
}), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
  d: "M22.3,10.7h-7.2c-0.7,0-1.3,0.6-1.3,1.3v10c0,0.7,0.6,1.3,1.3,1.3h7.2c0.7,0,1.3-0.6,1.3-1.3V12 C23.6,11.3,23,10.7,22.3,10.7z M22.5,12v10c0,0.1-0.2,0.3-0.3,0.3H15c-0.1,0-0.3-0.2-0.3-0.3V12c0-0.1,0.2-0.3,0.3-0.3h7.2 C22.4,11.7,22.5,11.9,22.5,12z"
}), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
  d: "M17.7,17.5H16c-0.3,0-0.5,0.2-0.5,0.5s0.2,0.5,0.5,0.5h1.6c0.3,0,0.5-0.2,0.5-0.5C18.2,17.7,17.9,17.5,17.7,17.5z"
}), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
  d: "M17.7,19.7H16c-0.3,0-0.5,0.2-0.5,0.5s0.2,0.5,0.5,0.5h1.6c0.3,0,0.5-0.2,0.5-0.5C18.2,19.9,17.9,19.7,17.7,19.7z"
}), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
  d: "M21.3,17.5h-1.6c-0.3,0-0.5,0.2-0.5,0.5s0.2,0.5,0.5,0.5h1.6c0.3,0,0.5-0.2,0.5-0.5S21.5,17.5,21.3,17.5z"
}), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
  d: "M21.3,19.7h-1.6c-0.3,0-0.5,0.2-0.5,0.5s0.2,0.5,0.5,0.5h1.6c0.3,0,0.5-0.2,0.5-0.5C21.8,19.9,21.5,19.7,21.3,19.7z"
}), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
  d: "M22.6,3.8c-0.1-1.7-1.4-3.1-3.1-3.3l0,0H3.4C1.6,0.7,0.3,2,0.3,3.8v13.7c0,1.9,1.6,3.5,3.5,3.5H12c0.5,0,0.8-0.3,0.8-0.8 s-0.3-0.8-0.8-0.8H3.7c-1,0-1.9-0.8-1.9-1.9V3.8c0-1,0.8-1.9,1.9-1.9h15.5c1,0,1.9,0.8,1.9,1.9v5c0,0.5,0.3,0.8,0.8,0.8 s0.8-0.3,0.8-0.8L22.6,3.8z"
}), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("g", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
  d: "M21.6,15.8h-5.8c-0.1,0-0.2-0.1-0.2-0.2v-2.1c0-0.1,0.1-0.2,0.2-0.2h5.8c0.1,0,0.2,0.1,0.2,0.2v2.1 C21.8,15.7,21.7,15.8,21.6,15.8z M16,15.3h5.3v-1.6H16V15.3z"
})));
(0,_wordpress_blocks__WEBPACK_IMPORTED_MODULE_1__.registerBlockType)('wisdm-learndash-reports/total-courses', {
  title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Total Courses', 'learndash-reports-by-wisdmlabs'),
  description: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Displays Count of the courses', 'learndash-reports-by-wisdmlabs'),
  category: 'wisdm-learndash-reports',
  className: 'learndash-reports-by-wisdmlabs-total-courses',
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
    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", (0,_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_5__.useBlockProps)(), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      class: "wisdm-learndash-reports-total-courses"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_index_total_courses_js__WEBPACK_IMPORTED_MODULE_7__["default"], null)));
  },
  /**
   * save function
   * 
   * Makes the markup that will be rendered on the site page
   * 
   * @return {JSX object} ECMAScript JSX Markup for the site
   */
  save() {
    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_5__.useBlockProps.save(), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      class: "wisdm-learndash-reports-total-courses front"
    }));
  }
});
}();
/******/ })()
;
//# sourceMappingURL=editor-total-courses.js.map