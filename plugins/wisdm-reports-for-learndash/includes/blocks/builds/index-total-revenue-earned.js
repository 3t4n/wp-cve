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
/*!********************************************************************************!*\
  !*** ./includes/blocks/src/total-revenue-earned/index-total-revenue-earned.js ***!
  \********************************************************************************/
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
}();
/******/ })()
;
//# sourceMappingURL=index-total-revenue-earned.js.map