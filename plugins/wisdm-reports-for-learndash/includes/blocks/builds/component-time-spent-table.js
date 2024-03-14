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
/*!**********************************************************************************!*\
  !*** ./includes/blocks/src/time-spent-on-a-course/component-time-spent-table.js ***!
  \**********************************************************************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_2__);




// import WisdmLoader from '../commons/loader/index.js';

class TimeSpentTable extends react__WEBPACK_IMPORTED_MODULE_2__.Component {
  constructor(props) {
    super(props);
    this.state = {
      type: props.type,
      data_point: props.data_point,
      table: props.table,
      course: props.course,
      learner: props.learner,
      selectedTimePeriod: props.timeperiod
    };
  }
  formatDate = timestamp => {
    if (parseInt(timestamp) === 0 || timestamp === null || timestamp === undefined) {
      return "-";
    }
    const date = new Date(parseInt(timestamp) * 1000);

    // Format the date as "ddth Month, yyyy"
    const day = date.getDate();
    const month = date.toLocaleString('default', {
      month: 'short'
    });
    const year = date.getFullYear();
    const suffixes = ['th', 'st', 'nd', 'rd'];
    const remainder = day % 100;
    const ordinalSuffix = suffixes[(remainder - 20) % 10] || suffixes[remainder] || suffixes[0];
    const formattedDate = `${day}${ordinalSuffix} ${month}, ${year}`;
    return formattedDate;
  };
  static getDerivedStateFromProps(props, state) {
    if (props.type !== state.type) {
      //Change in props
      return {
        type: props.type
      };
    }
    if (props.data_point !== state.data_point) {
      //Change in props
      return {
        data_point: props.data_point
      };
    }
    if (props.table !== state.table) {
      //Change in props
      return {
        table: props.table
      };
    }
    if (props.course !== state.course) {
      //Change in props
      return {
        course: props.course
      };
    }
    if (props.learner !== state.learner) {
      //Change in props
      return {
        learner: props.learner
      };
    }
    return null; // No change to state
  }

  wisdmLDRConvertTime(seconds) {
    if (seconds === undefined || seconds === null) {
      return "00:00:00";
    }
    var hours = Math.floor(seconds / 3600);
    var minutes = Math.floor(seconds % 3600 / 60);
    var seconds = Math.floor(seconds % 3600 % 60);
    if (hours < 10) {
      hours = "0" + hours;
    }
    if (minutes < 10) {
      minutes = "0" + minutes;
    }
    if (seconds < 10) {
      seconds = "0" + seconds;
    }
    if (!!hours) {
      if (!!minutes) {
        return `${hours}:${minutes}:${seconds}`;
      } else {
        return `${hours}:00:${seconds}`;
      }
    }
    if (!!minutes) {
      return `00:${minutes}:${seconds}`;
    }
    return `00:00:${seconds}`;
  }
  render() {
    let table = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("table", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("tbody", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("tr", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("th", null, this.state.type == 'learner' ? (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Courses', 'learndash-reports-by-wisdmlabs') : (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Learners', 'learndash-reports-by-wisdmlabs')), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("th", null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Enrollment Date', 'learndash-reports-by-wisdmlabs')), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("th", null, this.state.type == 'learner' ? (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Progress %', 'learndash-reports-by-wisdmlabs') : (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Time spent', 'learndash-reports-by-wisdmlabs'))), Object.keys(this.state.table).map((key, index) => (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("tr", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("td", {
      width: "45%"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
      className: "course-name"
    }, this.state.table[key].display_name)), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("td", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", null, this.formatDate(this.state.table[key].enrollment_date))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("td", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", null, this.wisdmLDRConvertTime(this.state.table[key].total_time_spent))))), 0 == this.state.table.length ? (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("tr", null, this.state.type == 'learner' ? (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('No Courses in this range.', 'learndash-reports-by-wisdmlabs') : (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('No Learners in this progress range.', 'learndash-reports-by-wisdmlabs')) : ''));
    let header = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: "heading_wrapper"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("h1", null, this.state.type == 'learner' ? this.state.learner + '\'s progress' : (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Time spent in the ', 'learndash-reports-by-wisdmlabs') + this.state.course + ''), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", null, this.state.type == 'learner' ? (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Following are the courses for which completion percentage rate is ', 'learndash-reports-by-wisdmlabs')), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("strong", null, this.state.data_point, " ", this.state.selectedTimePeriod == 1 ? 'minutes.' : 'hours.')) : (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Following are the learners in this course for which time spent is ', 'learndash-reports-by-wisdmlabs')), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("strong", null, this.state.data_point, " ", this.state.selectedTimePeriod == 1 ? 'minutes.' : 'hours.'))));
    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: "header"
    }, header), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: "wisdm-learndash-reports-course-completion-table"
    }, table));
  }
}
/* harmony default export */ __webpack_exports__["default"] = (TimeSpentTable);
}();
/******/ })()
;
//# sourceMappingURL=component-time-spent-table.js.map