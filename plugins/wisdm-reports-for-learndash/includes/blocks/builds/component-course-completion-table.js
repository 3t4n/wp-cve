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
/*!*****************************************************************************************!*\
  !*** ./includes/blocks/src/course-completion-rate/component-course-completion-table.js ***!
  \*****************************************************************************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_2__);



class CourseCompletionTable extends react__WEBPACK_IMPORTED_MODULE_2__.Component {
  constructor(props) {
    super(props);
    this.state = {
      tableData: props.tableData,
      sort: props.sort
    };
    this.changeSort = this.changeSort.bind(this);
    this.changeDirection = this.changeDirection.bind(this);
  }
  componentDidMount() {
    //Patch logic for react state updaete on browser refresh bug.
    document.addEventListener('completion-parent-sort-changed', this.changeDirection);
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
    //Patch logic for react state updaete on browser refresh bug.
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
  static getDerivedStateFromProps(props, state) {
    if (props.sort !== state.sort) {
      //Change in props
      return {
        sort: props.sort
      };
    }
    if (props.tableData !== state.tableData) {
      //Change in props
      return {
        tableData: props.tableData
      };
    }
    return null; // No change to state
  }

  changeDirection(evnt) {
    this.setState({
      sort: evnt.detail.value
    });
  }
  changeSort() {
    let sort = '';
    if (this.state.sort == 'ASC') {
      sort = 'DESC';
    } else {
      sort = 'ASC';
    }
    const durationEvent = new CustomEvent("local_sort_change_completion", {
      "detail": {
        "value": sort
      }
    });
    document.dispatchEvent(durationEvent);
    this.setState({
      sort: sort
    });
  }

  // componentDidUpdate() {
  // 	jQuery( ".wisdm-learndash-reports-course-completion-table progress, .wisdm-learndash-reports-course-completion-table .progress-percentage" ).hover(
  //     function() {
  //     	console.log('ss');
  //       var $div = jQuery('<div/>').addClass('wdm-tooltip').css({
  //           position: 'absolute',
  //           zIndex: 999,
  //           display: 'none'
  //       }).appendTo(jQuery(this));
  //       console.log($div);
  //       $div.text(jQuery(this).attr('data-title'));
  //       var $font = jQuery(this).parents('.graph-card-container').css('font-family');
  //       $div.css('font-family', $font);
  //       $div.show();
  //     }, function() {
  //       jQuery( this ).find( ".wdm-tooltip" ).remove();
  //     }
  //   );
  // }

  render() {
    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)("div", {
      class: "wisdm-learndash-reports-course-completion-table"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)("table", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)("tbody", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)("tr", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)("th", null, wisdm_reports_get_ld_custom_lebel_if_avaiable('Course')), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)("th", null, wisdm_reports_get_ld_custom_lebel_if_avaiable('Course') + ' ' + (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Completion Rate', 'learndash-reports-by-wisdmlabs'))), Object.keys(this.props.tableData).map((key, index) => (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)("tr", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)("td", {
      width: "45%"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)("span", {
      className: "course-name"
    }, key)), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)("td", {
      width: "55%",
      className: "right-side"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)("progress", {
      className: "progress",
      max: "100",
      value: this.props.tableData[key].percentage,
      "data-title": this.props.tableData[key].completed + (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)(' out of ', 'learndash-reports-by-wisdmlabs') + this.props.tableData[key].total + (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)(' learners completed', 'learndash-reports-by-wisdmlabs')
    }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)("span", {
      className: "progress-percentage",
      "data-title": this.props.tableData[key].completed + (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)(' out of ', 'learndash-reports-by-wisdmlabs') + this.props.tableData[key].total + (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)(' learners completed', 'learndash-reports-by-wisdmlabs')
    }, this.props.tableData[key].percentage, "%")))))));
  }
}
/* harmony default export */ __webpack_exports__["default"] = (CourseCompletionTable);
}();
/******/ })()
;
//# sourceMappingURL=component-course-completion-table.js.map