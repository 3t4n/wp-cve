/******/ (function() { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "react":
/*!************************!*\
  !*** external "React" ***!
  \************************/
/***/ (function(module) {

module.exports = window["React"];

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
/*!***************************************************************************************!*\
  !*** ./includes/blocks/src/course-completion-rate/component-completion-rate-modal.js ***!
  \***************************************************************************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_2__);




// import WisdmLoader from '../commons/loader/index.js';

class CompletionRateModal extends react__WEBPACK_IMPORTED_MODULE_2__.Component {
  constructor(props) {
    super(props);
    this.state = {
      table: props.table,
      category: props.category,
      group: props.group,
      sort: props.sort
    };
    this.changeSort = this.changeSort.bind(this);
  }
  componentDidMount() {
    jQuery(".wisdm-learndash-reports-course-completion-table progress, .wisdm-learndash-reports-course-completion-table .progress-percentage").hover(function () {
      jQuery(this).parent().css({
        'position': 'relative'
      });
      var $div = jQuery('<div/>').addClass('wrld-tooltip').css({
        position: 'absolute',
        zIndex: 999,
        display: 'none'
      }).appendTo(jQuery(this).parent());
      $div.text(jQuery(this).attr('data-title'));
      var $font = jQuery(this).parents('.graph-card-container').css('font-family');
      $div.css('font-family', $font);
      $div.show();
    }, function () {
      jQuery(this).parent().find(".wrld-tooltip").remove();
    });
    jQuery(".wisdm-learndash-reports-course-completion-table span.toggle").hover(function () {
      jQuery(this).parent().css({
        'position': 'relative'
      });
      var $div = jQuery('<div/>').addClass('wrld-tooltip').css({
        position: 'absolute',
        zIndex: 999,
        display: 'none'
      }).appendTo(jQuery(this).parent());
      $div.text(jQuery(this).attr('data-title'));
      var $font = jQuery(this).parents('.wisdm-learndash-reports-course-completion-table').css('font-family');
      $div.css('font-family', $font);
      $div.show();
    }, function () {
      jQuery(this).parent().find(".wrld-tooltip").remove();
    });
  }
  componentDidUpdate() {
    jQuery(".wisdm-learndash-reports-course-completion-table progress, .wisdm-learndash-reports-course-completion-table .progress-percentage").hover(function () {
      jQuery(this).parent().css({
        'position': 'relative'
      });
      var $div = jQuery('<div/>').addClass('wrld-tooltip').css({
        position: 'absolute',
        zIndex: 999,
        display: 'none'
      }).appendTo(jQuery(this).parent());
      $div.text(jQuery(this).attr('data-title'));
      var $font = jQuery(this).parents('.graph-card-container').css('font-family');
      $div.css('font-family', $font);
      $div.show();
    }, function () {
      jQuery(this).parent().find(".wrld-tooltip").remove();
    });
    jQuery(".wisdm-learndash-reports-course-completion-table span.toggle").hover(function () {
      jQuery(this).parent().css({
        'position': 'relative'
      });
      var $div = jQuery('<div/>').addClass('wrld-tooltip').css({
        position: 'absolute',
        zIndex: 999,
        display: 'none'
      }).appendTo(jQuery(this).parent());
      $div.text(jQuery(this).attr('data-title'));
      var $font = jQuery(this).parents('.wisdm-learndash-reports-course-completion-table').css('font-family');
      $div.css('font-family', $font);
      $div.show();
    }, function () {
      jQuery(this).parent().find(".wrld-tooltip").remove();
    });
  }
  changeSort() {
    let sort = '';
    if (this.state.sort == 'ASC') {
      sort = 'DESC';
    } else {
      sort = 'ASC';
    }
    this.setState({
      sort: sort
    });
    const durationEvent = new CustomEvent("local_sort_change_completion_modal", {
      "detail": {
        "value": sort
      }
    });
    document.dispatchEvent(durationEvent);
  }
  static getDerivedStateFromProps(props, state) {
    if (props.table !== state.table) {
      //Change in props
      return {
        table: props.table
      };
    }
    if (props.category !== state.category) {
      //Change in props
      return {
        category: props.category
      };
    }
    if (props.group !== state.group) {
      //Change in props
      return {
        group: props.group
      };
    }
    if (props.sort !== state.sort) {
      //Change in props
      return {
        sort: props.sort
      };
    }
    return null; // No change to state
  }

  render() {
    let text = '';
    if (this.state.sort == 'ASC') {
      text = (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)(' (oldest courses first)', 'learndash-reports-by-wisdmlabs');
    } else {
      text = (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)(' (latest courses first)', 'learndash-reports-by-wisdmlabs');
    }
    let table = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("table", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("tbody", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("tr", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("th", null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Course', 'learndash-reports-by-wisdmlabs'), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
      className: "toggle",
      onClick: this.changeSort,
      "data-title": (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Courses are sorted by publish date', 'learndash-reports-by-wisdmlabs') + text
    })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("th", null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Course Completion Rate', 'learndash-reports-by-wisdmlabs'))), this.props.loading ? (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("tr", {
      className: "wrld-ccr-more-data-loader"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("td", {
      colspan: "2"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("img", {
      src: wisdm_learndash_reports_front_end_script_total_revenue_earned.plugin_asset_url + '/images/loader.svg'
    }))) : (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null), Object.keys(this.state.table).map((key, index) => (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("tr", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("td", {
      width: "45%"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
      className: "course-name"
    }, key)), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("td", {
      width: "55%",
      className: "right-side"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("progress", {
      className: "progress",
      max: "100",
      value: this.state.table[key].percentage,
      "data-title": this.state.table[key].completed + (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)(' out of ', 'learndash-reports-by-wisdmlabs') + this.state.table[key].total + (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)(' learners completed', 'learndash-reports-by-wisdmlabs')
    }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
      className: "progress-percentage",
      "data-title": this.state.table[key].completed + (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)(' out of ', 'learndash-reports-by-wisdmlabs') + this.state.table[key].total + (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)(' learners completed', 'learndash-reports-by-wisdmlabs')
    }, this.state.table[key].percentage, "%"))))));
    let header = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: "heading_wrapper"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("h1", null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Course Completion Rate', 'learndash-reports-by-wisdmlabs')));
    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: "header"
    }, header), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: "wisdm-learndash-reports-course-completion-table"
    }, table));
  }
}
/* harmony default export */ __webpack_exports__["default"] = (CompletionRateModal);
}();
/******/ })()
;
//# sourceMappingURL=component-completion-rate-modal.js.map