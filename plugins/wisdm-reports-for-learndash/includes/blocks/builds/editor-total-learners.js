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

/***/ "./includes/blocks/src/total-learners/index-total-learners.js":
/*!********************************************************************!*\
  !*** ./includes/blocks/src/total-learners/index-total-learners.js ***!
  \********************************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _index_scss__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./index.scss */ "./includes/blocks/src/total-learners/index.scss");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _commons_loader_index_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../commons/loader/index.js */ "./includes/blocks/src/commons/loader/index.js");
/* harmony import */ var moment__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! moment */ "moment");
/* harmony import */ var moment__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(moment__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__);






class TotalLearners extends react__WEBPACK_IMPORTED_MODULE_2__.Component {
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
    var requestUrl = '/rp/v1/total-learners?start_date=' + this.state.start_date + '&end_date=' + this.state.end_date;
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
          totalLearners: response.totalLearners,
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
        class: "total-learners-container top-card-container"
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
        className: "wrld-date-filter"
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
        class: "dashicons dashicons-calendar-alt"
      }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
        className: "wdm-tooltip"
      }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Date filter applied:', 'learndash-reports-by-wisdmlabs'), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("br", null), this.state.startDate, " - ", this.state.endDate)), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
        class: "total-learners-icon"
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("img", {
        src: wisdm_learndash_reports_front_end_script_total_learners.plugin_asset_url + '/images/icon_learners_counter.png'
      })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
        class: "total-learners-details"
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
        class: "total-learners-text top-label-text"
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Total Learners', 'learndash-reports-by-wisdmlabs'))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
        class: "total-learners-figure"
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", null, this.state.graphData.totalLearners)), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
        class: `total-learners-percent-change ${this.state.graphData.hideChange}`
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
/* harmony default export */ __webpack_exports__["default"] = (TotalLearners);
document.addEventListener("DOMContentLoaded", function (event) {
  let elem = document.getElementsByClassName('wisdm-learndash-reports-total-learners front');
  if (elem.length > 0) {
    ReactDOM.render(react__WEBPACK_IMPORTED_MODULE_2___default().createElement(TotalLearners), elem[0]);
  }
});

/***/ }),

/***/ "./includes/blocks/src/total-learners/editor.scss":
/*!********************************************************!*\
  !*** ./includes/blocks/src/total-learners/editor.scss ***!
  \********************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./includes/blocks/src/total-learners/index.scss":
/*!*******************************************************!*\
  !*** ./includes/blocks/src/total-learners/index.scss ***!
  \*******************************************************/
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
/*!*********************************************************************!*\
  !*** ./includes/blocks/src/total-learners/editor-total-learners.js ***!
  \*********************************************************************/
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
/* harmony import */ var _editor_scss__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./editor.scss */ "./includes/blocks/src/total-learners/editor.scss");
/* harmony import */ var _index_total_learners_js__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./index-total-learners.js */ "./includes/blocks/src/total-learners/index-total-learners.js");

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
}, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("g", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
  d: "M7.4,10.8L7.4,10.8l0.1,0.1C7.6,12,8.3,13,9.2,13.8l0.8,0.4c1,0.5,2.7,0.5,3.7,0l0.8-0.4c0.9-0.8,1.5-1.8,1.7-2.9v-0.1h0.1 c0.4-0.1,0.7-0.5,0.7-1v-3l0,0c0.1-0.1,0.2-0.4,0.2-0.6V4.8l2.1-0.4c0.3-0.1,0.6-0.3,0.6-0.8l0,0c0.1-0.2,0-0.4-0.1-0.5 c-0.1-0.2-0.3-0.3-0.5-0.4l-7.5-2.4h-0.5L4.1,2.7C3.7,2.8,3.5,3.2,3.5,3.6c0,0.2,0.1,0.3,0.2,0.5c0,0.1,0.1,0.1,0.1,0.2v0.1v1.8 H3.7c-0.5,0-0.8,0.4-0.8,0.7v0.9l-0.4,3.1c0,0.2,0,0.5,0.1,0.8C2.7,11.9,2.9,12,3.3,12H5c0.3,0,0.5-0.1,0.7-0.3l0,0 c0.1-0.2,0.2-0.5,0.1-0.6V11L5.4,8V6.9c0-0.3-0.3-0.7-0.7-0.8H4.6V4.5l1.7,0.3v1.5c0,0.3,0.1,0.5,0.2,0.6l0,0v3 C6.6,10.3,7,10.7,7.4,10.8z M16.1,10.1h-0.2c-0.4,0-0.6,0.3-0.6,0.6c-0.1,1.9-1.6,3.1-3.4,3.1s-3.3-1.2-3.4-3.1 c0-0.4-0.3-0.7-0.7-0.7c-0.1,0-0.3-0.1-0.3-0.3V9h0.4c0.7,0,1.2-0.5,1.2-1.2V7h5.4v0.7c0,0.7,0.6,1.3,1.2,1.3h0.4V10.1z M7.5,8.1V7 h0.8v0.7c0,0.3-0.1,0.4-0.4,0.4C7.9,8.1,7.5,8.1,7.5,8.1z M16.1,8.1h-0.4c-0.1,0-0.4-0.1-0.4-0.4V7h0.8V8.1z M3.8,7h0.7v0.5H3.8V7z M4.6,8.3L5,10.9H3.3l0.4-2.5L4.6,8.3L4.6,8.3z M4.4,3.5l7.4-2.4l7.4,2.4l-2.4,0.4L15.7,4c-0.1,0.1-0.1,0.2-0.1,0.4 c0,0.3,0.1,0.4,0.4,0.5L16.7,5v1.3H7.3V4.9l3.9-0.5c0.5-0.1,1-0.1,1.7,0l1.3,0.1c0.1,0,0.3-0.2,0.3-0.3c0-0.3-0.1-0.4-0.4-0.5 l-1.3-0.1c-0.7-0.1-1.3-0.1-1.8,0L6.8,4L4.4,3.5z"
}), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("g", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
  d: "M22,18.9v-0.4c0-1-0.7-1.8-1.6-2.1l-6.2-1.7v-1l-0.9,0.6v0.5L12,16.1l-1.3-1.3v-0.5l-0.9-0.5v0.9l-6.2,1.7 c-1,0.3-1.6,1.1-1.6,2.1v0.4v0.9v2v1.5c0,0.2,0.2,0.4,0.4,0.4h19.2c0.2,0,0.4-0.2,0.4-0.4v-1.1v-2.4V18.9z M21.1,22.8h-2.6v-3.5 h-0.9v3.5H6.3v-3.5H5.5v3.5H2.9v-4.3c0-0.6,0.4-1.1,1-1.3l6.3-1.8l1.6,1.6c0.2,0.2,0.4,0.2,0.6,0l1.6-1.6l6.3,1.8 c0.6,0.2,1,0.7,1,1.3v4.3H21.1z"
}))));
(0,_wordpress_blocks__WEBPACK_IMPORTED_MODULE_1__.registerBlockType)('wisdm-learndash-reports/total-learners', {
  title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Total Learners', 'learndash-reports-by-wisdmlabs'),
  description: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Displays Count of the enrolled learners', 'learndash-reports-by-wisdmlabs'),
  category: 'wisdm-learndash-reports',
  className: 'learndash-reports-by-wisdmlabs-total-learners',
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
      class: "wisdm-learndash-reports-total-learners"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_index_total_learners_js__WEBPACK_IMPORTED_MODULE_7__["default"], null)));
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
      class: "wisdm-learndash-reports-total-learners front"
    }));
  }
});
}();
/******/ })()
;
//# sourceMappingURL=editor-total-learners.js.map