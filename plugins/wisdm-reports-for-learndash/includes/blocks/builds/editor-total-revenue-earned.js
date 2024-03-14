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

/***/ "./includes/blocks/src/total-revenue-earned/index-total-revenue-earned.js":
/*!********************************************************************************!*\
  !*** ./includes/blocks/src/total-revenue-earned/index-total-revenue-earned.js ***!
  \********************************************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _index_scss__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./index.scss */ "./includes/blocks/src/total-revenue-earned/index.scss");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _commons_loader_index_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../commons/loader/index.js */ "./includes/blocks/src/commons/loader/index.js");
/* harmony import */ var moment__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! moment */ "moment");
/* harmony import */ var moment__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(moment__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__);






class TotalRevenueEarned extends react__WEBPACK_IMPORTED_MODULE_2__.Component {
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
    let callback = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : '/rp/v1/total-revenue-earned';
    var requestUrl = '/rp/v1/total-revenue-earned?start_date=' + this.state.start_date + '&end_date=' + this.state.end_date;
    if (wisdm_ld_reports_common_script_data.wpml_lang) {
      requestUrl += '&wpml_lang=' + wisdm_ld_reports_common_script_data.wpml_lang;
    }
    wp.apiFetch({
      path: requestUrl //Replace with the correct API
    }).then(response => {
      let chnageDirectionClass = 'udup';
      let percentValueClass = 'change-value';
      let hideChange = '';
      let udtxt = '';
      let udsrc = '';
      if (0 < response.percentChange) {
        chnageDirectionClass = 'udup';
        percentValueClass = 'change-value-positive';
        udtxt = (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Up', 'learndash-reports-by-wisdmlabs');
        udsrc = wisdm_learndash_reports_front_end_script_total_revenue_earned.plugin_asset_url + '/images/up.png';
      } else if (0 > response.percentChange) {
        chnageDirectionClass = 'uddown';
        percentValueClass = 'change-value-negative';
        udtxt = (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Down', 'learndash-reports-by-wisdmlabs');
        udsrc = wisdm_learndash_reports_front_end_script_total_revenue_earned.plugin_asset_url + '/images/down.png';
      } else if (0 == response.percentChange) {
        hideChange = 'wrld-hidden';
        udtxt = (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Up', 'learndash-reports-by-wisdmlabs');
        udsrc = wisdm_learndash_reports_front_end_script_total_revenue_earned.plugin_asset_url + '/images/up.png';
      }
      let currency = wisdm_ld_reports_common_script_data.currency_in_use;
      currency = currency.length > 1 ? currency + ' ' : currency;
      this.setState({
        isLoaded: true,
        graphData: {
          totalRevenueEarned: currency + response.totalRevenueEarned,
          percentChange: response.percentChange + '%',
          percentChangeDirection: chnageDirectionClass,
          percentValueClass: percentValueClass,
          hideChange: hideChange,
          udtxt: udtxt,
          udsrc: udsrc
        },
        startDate: moment__WEBPACK_IMPORTED_MODULE_4___default().unix(response.requestData.start_date).format("MMM, DD YYYY"),
        endDate: moment__WEBPACK_IMPORTED_MODULE_4___default().unix(response.requestData.end_date).format("MMM, DD YYYY")
      });
    }).catch(error => {
      if ('rest_forbidden' == error.code) {
        wisdm_reports_change_block_visibility('.wisdm-learndash-reports-total-revenue-earned', false, '.wp-block-column.lr-tre');
      }
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
        class: "total-revenue-earned-container top-card-container"
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
        className: "wrld-date-filter"
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
        class: "dashicons dashicons-calendar-alt"
      }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
        className: "wdm-tooltip"
      }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Date filter applied:', 'learndash-reports-by-wisdmlabs'), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("br", null), this.state.startDate, " - ", this.state.endDate)), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
        class: "total-revenue-earned-icon"
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("img", {
        src: wisdm_learndash_reports_front_end_script_total_revenue_earned.plugin_asset_url + '/images/icon_total_revenue.png'
      })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
        class: "total-revenue-earned-details"
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
        class: "total-revenue-earned-text top-label-text"
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Total Revenue Earned', 'learndash-reports-by-wisdmlabs'))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
        class: "total-revenue-earned-figure"
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", null, this.state.graphData.totalRevenueEarned)), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
        class: `total-revenue-earned-percent-change ${this.state.graphData.hideChange}`
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
        class: this.state.graphData.percentChangeDirection
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
/* harmony default export */ __webpack_exports__["default"] = (TotalRevenueEarned);
document.addEventListener("DOMContentLoaded", function (event) {
  let elem = document.getElementsByClassName('wisdm-learndash-reports-total-revenue-earned front');
  if (elem.length > 0) {
    ReactDOM.render(react__WEBPACK_IMPORTED_MODULE_2___default().createElement(TotalRevenueEarned), elem[0]);
  }
});

/***/ }),

/***/ "./includes/blocks/src/total-revenue-earned/editor.scss":
/*!**************************************************************!*\
  !*** ./includes/blocks/src/total-revenue-earned/editor.scss ***!
  \**************************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./includes/blocks/src/total-revenue-earned/index.scss":
/*!*************************************************************!*\
  !*** ./includes/blocks/src/total-revenue-earned/index.scss ***!
  \*************************************************************/
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
/*!*********************************************************************************!*\
  !*** ./includes/blocks/src/total-revenue-earned/editor-total-revenue-earned.js ***!
  \*********************************************************************************/
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
/* harmony import */ var _editor_scss__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./editor.scss */ "./includes/blocks/src/total-revenue-earned/editor.scss");
/* harmony import */ var _index_total_revenue_earned_js__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./index-total-revenue-earned.js */ "./includes/blocks/src/total-revenue-earned/index-total-revenue-earned.js");

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
  d: "M12.4,18.4l0.2,0c1-0.1,1.8-1,1.8-2c0-1.1-0.9-2-2-2h-0.9c-0.5,0-0.9-0.4-0.9-0.9s0.4-0.9,0.9-0.9h2.5 c0.4,0,0.6-0.2,0.6-0.6s-0.2-0.6-0.6-0.6h-1.5v-0.9c0-0.4-0.2-0.6-0.6-0.6c-0.3,0-0.6,0.2-0.6,0.6v0.9l-0.1,0c-1,0.1-1.7,1-1.7,2 c0,1.1,0.9,2,2,2h0.9c0.5,0,0.9,0.4,0.9,0.9s-0.4,0.9-0.9,0.9H9.8c-0.4,0-0.6,0.2-0.6,0.6s0.2,0.6,0.6,0.6h1.4v0.9 c0,0.3,0.2,0.6,0.6,0.6c0.4,0,0.6-0.2,0.6-0.6V18.4z"
}), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
  d: "M8.5,23.7h6.8c1.5,0,2.7-0.5,3.7-1.5c1.1-1.2,1.7-2.9,1.7-4.9c0-3.8-1.8-7.9-4.4-10.2L16.1,7l0.2-0.1 c0.4-0.3,0.5-0.7,0.5-1.2c0-0.6-0.3-1.1-0.8-1.3l-0.2-0.1L17.3,3c0.6-0.4,0.8-1.1,0.5-1.7c-0.2-0.6-0.8-1-1.4-1H7.4 c-0.6,0-1.1,0.4-1.5,1C5.6,1.9,5.8,2.6,6.4,3L8,4.3L7.8,4.4C7.3,4.7,7,5.2,7,5.7c0,0.4,0.2,0.8,0.5,1.1L7.6,7L7.5,7.1 c-2.6,2.3-4.4,6.4-4.4,10.2C3.1,20.5,4.7,23.7,8.5,23.7z M15.7,5.7c0,0.3-0.2,0.4-0.4,0.4h-0.4c-0.4-0.1-1.4-0.1-5.8,0l-0.5,0 C8.2,6.2,8.1,6,8.1,5.7s0.2-0.4,0.4-0.4h6.8C15.6,5.3,15.7,5.5,15.7,5.7z M7.2,2C7,1.9,7,1.8,7,1.7c0,0,0-0.1,0-0.1l0-0.1 c0,0,0.1-0.2,0.3-0.2h9.2c0.1,0,0.2,0,0.2,0.1l0.1,0.2c0,0,0,0.3-0.2,0.4l0,0L14,4.2H9.9L7.2,2z M9.2,7.3L9.2,7.3l5.4,0l0,0 c2.8,1.8,4.9,6.1,4.9,10.1c0,2.4-1.1,5.3-4.2,5.3H8.5c-3.1,0-4.2-2.8-4.2-5.3C4.2,13.2,6.3,9,9.2,7.3z"
}));
(0,_wordpress_blocks__WEBPACK_IMPORTED_MODULE_1__.registerBlockType)('wisdm-learndash-reports/total-revenue-earned', {
  title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Total Revenue', 'learndash-reports-by-wisdmlabs'),
  description: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Displays the total revenue earned during the selected time & its comparison with the previous duration', 'learndash-reports-by-wisdmlabs'),
  category: 'wisdm-learndash-reports',
  className: 'learndash-reports-by-wisdmlabs-total-revenue-earned',
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
    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", (0,_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_5__.useBlockProps)(), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_index_total_revenue_earned_js__WEBPACK_IMPORTED_MODULE_7__["default"], null));
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
      class: "wisdm-learndash-reports-total-revenue-earned front"
    }));
  }
});
}();
/******/ })()
;
//# sourceMappingURL=editor-total-revenue-earned.js.map