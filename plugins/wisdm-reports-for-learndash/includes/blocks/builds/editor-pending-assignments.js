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

/***/ "./includes/blocks/src/pending-assignments/index-pending-assignments.js":
/*!******************************************************************************!*\
  !*** ./includes/blocks/src/pending-assignments/index-pending-assignments.js ***!
  \******************************************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _index_scss__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./index.scss */ "./includes/blocks/src/pending-assignments/index.scss");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _commons_loader_index_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../commons/loader/index.js */ "./includes/blocks/src/commons/loader/index.js");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var moment__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! moment */ "moment");
/* harmony import */ var moment__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(moment__WEBPACK_IMPORTED_MODULE_5__);






var ld_api_settings = wisdm_learndash_reports_front_end_script_pending_assignments.ld_api_settings;
class PendingAssignments extends react__WEBPACK_IMPORTED_MODULE_2__.Component {
  constructor(props) {
    super(props);
    this.state = {
      isLoaded: false,
      error: null,
      start_date: null,
      end_date: null,
      lock_icon: '',
      start_date: moment__WEBPACK_IMPORTED_MODULE_5___default()(new Date(wisdm_ld_reports_common_script_data.start_date)).unix(),
      end_date: moment__WEBPACK_IMPORTED_MODULE_5___default()(new Date(wisdm_ld_reports_common_script_data.end_date)).unix(),
      upgrade_class: 'wisdm-class'
    };
    this.durationUpdated = this.durationUpdated.bind(this);
    this.updateBlock = this.updateBlock.bind(this);
  }
  durationUpdated(event) {
    this.setState({
      start_date: event.detail.startDate,
      end_date: event.detail.endDate
    });
    if (wisdm_learndash_reports_front_end_script_pending_assignments.is_pro_version_active) {
      this.updateBlock();
    }
  }
  componentDidMount() {
    this.updateBlock();
  }
  updateBlock() {
    if (undefined == ld_api_settings['sfwd-assignment']) {
      ld_api_settings['sfwd-assignment'] = 'sfwd-assignment';
    }
    let requestUrl = '/rp/v1/pending-assignments?start_date=' + this.state.start_date + '&end_date=' + this.state.end_date;
    if (wisdm_ld_reports_common_script_data.wpml_lang) {
      requestUrl += '&wpml_lang=' + wisdm_ld_reports_common_script_data.wpml_lang;
    }
    wp.apiFetch({
      path: requestUrl
    }).then(response => {
      if (true != wisdm_learndash_reports_front_end_script_pending_assignments.is_pro_version_active) {
        let lock_icon = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
          title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('Please upgrade the plugin to access this feature', 'learndash-reports-by-wisdmlabs'),
          class: "dashicons dashicons-lock ld-reports top-corner"
        });
        let hideChange = '';
        this.setState({
          graphData: {
            pendingAssignments: '??',
            percentChange: '--' + '%',
            chnageDirectionClass: 'udup',
            percentValueClass: 'change-value',
            hideChange: hideChange
          },
          upgrade_class: 'wisdm-upgrade-to-pro',
          isLoaded: true,
          lock_icon: lock_icon
        });
      } else {
        let pendingAssignments = response.pendingAssignments;
        let percentChange = 0;
        let chnageDirectionClass = 'udup';
        let percentValueClass = 'change-value';
        let hideChange = '';
        let udtxt = '';
        let udsrc = '';
        if (0 < percentChange) {
          chnageDirectionClass = 'udup';
          percentValueClass = 'change-value-positive';
          udtxt = (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('Up', 'learndash-reports-by-wisdmlabs');
          udsrc = wisdm_learndash_reports_front_end_script_total_revenue_earned.plugin_asset_url + '/images/up.png';
        } else if (0 > percentChange) {
          chnageDirectionClass = 'uddown';
          percentValueClass = 'change-value-negative';
          udtxt = (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('Down', 'learndash-reports-by-wisdmlabs');
          udsrc = wisdm_learndash_reports_front_end_script_total_revenue_earned.plugin_asset_url + '/images/down.png';
        } else if (0 == percentChange) {
          hideChange = 'wrld-hidden';
          udtxt = (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('Up', 'learndash-reports-by-wisdmlabs');
          udsrc = wisdm_learndash_reports_front_end_script_total_revenue_earned.plugin_asset_url + '/images/up.png';
        }
        this.setState({
          isLoaded: true,
          graphData: {
            pendingAssignments: pendingAssignments,
            percentChange: percentChange + '%',
            chnageDirectionClass: chnageDirectionClass,
            percentValueClass: percentValueClass,
            hideChange: hideChange,
            udtxt: udtxt,
            udsrc: udsrc
          }
        });
      }
    }).catch(error => {
      this.setState({
        error: error,
        graph_summary: [],
        series: [],
        isLoaded: true
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
      let upgrade_notice = '';
      if (true == wisdm_learndash_reports_front_end_script_pending_assignments.is_admin_user) {
        upgrade_notice = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("a", {
          class: "overlay pro-upgrade",
          href: "https://wisdmlabs.com/reports-for-learndash/?utm_source=reports-dashborad&utm_medium=assignments-pending&utm_campaign=upgradetopro#pricing",
          target: "__blank"
        }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
          class: "description"
        }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
          class: "upgrade-text"
        }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('Available in PRO version')), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("button", {
          class: "upgrade-button"
        }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('UPGRADE TO PRO', 'learndash-reports-by-wisdmlabs'))));
      }
      body = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
        class: "wisdm-learndash-reports-chart-block " + this.state.upgrade_class
      }, this.state.lock_icon, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
        class: "pending-assignments-container top-card-container "
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
        class: "pending-assignments-icon"
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("img", {
        src: wisdm_learndash_reports_front_end_script_pending_assignments.plugin_asset_url + '/images/icon_pending_assignment_counter.png'
      })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
        class: "pending-assignments-details"
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
        class: "pending-assignments-text top-label-text"
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('Assignments Pending', 'learndash-reports-by-wisdmlabs'))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
        class: "pending-assignments-figure"
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", null, this.state.graphData.pendingAssignments)), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
        class: `pending-assignments-percent-change ${this.state.graphData.hideChange}`
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
        class: this.state.graphData.chnageDirectionClass
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("img", {
        src: this.state.graphData.udsrc
      })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
        class: this.state.graphData.percentValueClass
      }, this.state.graphData.percentChange), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
        class: "ud-txt"
      }, this.state.graphData.udtxt)))), upgrade_notice);
    }
    return body;
  }
}
/* harmony default export */ __webpack_exports__["default"] = (PendingAssignments);
document.addEventListener("DOMContentLoaded", function (event) {
  let elem = document.getElementsByClassName('wisdm-learndash-reports-pending-assignments front');
  if (elem.length > 0) {
    ReactDOM.render(react__WEBPACK_IMPORTED_MODULE_2___default().createElement(PendingAssignments), elem[0]);
  }
});

/***/ }),

/***/ "./includes/blocks/src/pending-assignments/editor.scss":
/*!*************************************************************!*\
  !*** ./includes/blocks/src/pending-assignments/editor.scss ***!
  \*************************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./includes/blocks/src/pending-assignments/index.scss":
/*!************************************************************!*\
  !*** ./includes/blocks/src/pending-assignments/index.scss ***!
  \************************************************************/
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
/*!*******************************************************************************!*\
  !*** ./includes/blocks/src/pending-assignments/editor-pending-assignments.js ***!
  \*******************************************************************************/
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
/* harmony import */ var _editor_scss__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./editor.scss */ "./includes/blocks/src/pending-assignments/editor.scss");
/* harmony import */ var _index_pending_assignments_js__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./index-pending-assignments.js */ "./includes/blocks/src/pending-assignments/index-pending-assignments.js");

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






var ld_api_settings = wisdm_learndash_reports_front_end_script_pending_assignments.ld_api_settings;
const icon = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
  version: "1.0",
  xmlns: "http://www.w3.org/2000/svg",
  width: "24.000000pt",
  height: "24.000000pt",
  viewBox: "0 0 24.000000 24.000000",
  preserveAspectRatio: "xMidYMid meet"
}, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("g", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("g", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
  d: "M17.7,6.6h-5.5c-0.4,0-0.7,0.3-0.7,0.6c-0.1,0.1-0.1,0.3,0,0.4c0.1,0.3,0.4,0.5,0.8,0.5h5.5c0.5,0,0.7-0.4,0.7-0.7 C18.4,6.9,18,6.6,17.7,6.6z"
}), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
  d: "M17.7,10.4H6.5c-0.4,0-0.7,0.4-0.7,0.7c-0.1,0.2,0,0.4,0,0.5c0.1,0.2,0.4,0.3,0.7,0.3h11.1c0.5,0,0.7-0.4,0.7-0.7 C18.4,10.7,18,10.4,17.7,10.4z"
}), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
  d: "M17.7,13.9H6.5c-0.5,0-0.7,0.4-0.7,0.7c0,0.3,0.2,0.9,0.7,0.9h11.1c0.5,0,0.7-0.4,0.7-0.7C18.4,14.5,18.2,13.9,17.7,13.9z "
}), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
  d: "M17.7,17.6H6.5c-0.5,0-0.7,0.4-0.7,0.7c0,0.3,0.2,0.9,0.7,0.9h11.1c0.5,0,0.7-0.4,0.7-0.7C18.4,18.2,18.2,17.6,17.7,17.6z "
})), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
  d: "M21.1,2.4C20.9,1.6,20.3,1,19.4,1H4.6C3.7,1,2.9,1.8,2.9,2.7l0,9.3l0,9.3c0,0.9,0.7,1.7,1.7,1.7h14.9 c0.8,0,1.5-0.5,1.7-1.3V2.4z M19.8,21.2c0,0.2-0.1,0.4-0.2,0.4l-1.9,0H5.6v0H4.6c-0.2,0-0.2-0.2-0.3-0.3l0-6.5c0,0,0,0,0,0l0-2.8 l0-2.8c0,0,0,0,0,0l0-6.5c0-0.1,0.1-0.3,0.3-0.3h1.1v2c0,1.2,0.9,2.1,2.1,2.1c1.2,0,2.1-0.9,2.1-2.1V4.1c0-0.4-0.3-0.7-0.7-0.7 c-0.3,0-0.6,0.3-0.6,0.7v0.4c0,0.3-0.3,0.7-0.7,0.7C7.5,5.2,7.2,5,7.2,4.6V2.6l12.4,0c0.1,0,0.3,0.1,0.3,0.3V9V15V21.2z"
})));
(0,_wordpress_blocks__WEBPACK_IMPORTED_MODULE_1__.registerBlockType)('wisdm-learndash-reports/pending-assignments', {
  title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Pending Assignments', 'learndash-reports-by-wisdmlabs'),
  description: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Displays Count of the pending assignments', 'learndash-reports-by-wisdmlabs'),
  category: 'wisdm-learndash-reports',
  className: 'learndash-reports-by-wisdmlabs-pending-assignments',
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
      class: "wisdm-learndash-reports-pending-assignments"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_index_pending_assignments_js__WEBPACK_IMPORTED_MODULE_7__["default"], null)));
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
      class: "wisdm-learndash-reports-pending-assignments front"
    }));
  }
});
}();
/******/ })()
;
//# sourceMappingURL=editor-pending-assignments.js.map