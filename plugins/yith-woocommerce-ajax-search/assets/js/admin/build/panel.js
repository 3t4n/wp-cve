/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./assets/js/admin/src/synonymous-field.js":
/*!*************************************************!*\
  !*** ./assets/js/admin/src/synonymous-field.js ***!
  \*************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/**
 * Synonymous fields manager
 *
 * @package
 * @since 2.0.0
 */

var YWCAS_Synonymous_Field = function YWCAS_Synonymous_Field() {
  var fieldTemplate = wp.template('ywcas-synonymous');
  var mainWrapper = document.querySelector('.ywcas-synonymous-main-wrapper');
  var addNewRow = function addNewRow(e) {
    e.preventDefault();
    var newField = fieldTemplate({
      id: Date.now(),
      edit: false
    });
    jQuery(mainWrapper).append(newField);
    refresh();
  };
  var deleteRow = function deleteRow(e) {
    e.preventDefault();
    e.target.closest('.ywcas-synonymous').remove();
    refresh();
  };
  var refresh = function refresh() {
    checkTrashButton();
    jQuery(document).trigger('yith-plugin-fw-tips-init');
  };
  var checkTrashButton = function checkTrashButton() {
    var synFields = jQuery(mainWrapper).find('.option');
    if (synFields.length == 1) {
      document.querySelector('#tiptip_holder').remove();
      jQuery('.action__trash').hide();
    } else {
      jQuery('.action__trash').show();
    }
  };
  var init = function init() {
    checkTrashButton();
    var addRow = document.querySelector('.ywcas-add-synonymous a');
    if (addRow) {
      addRow.addEventListener('click', addNewRow);
      jQuery(document).on('click', '.action__trash', deleteRow);
    }
  };
  init();
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (YWCAS_Synonymous_Field);

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
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be isolated against other modules in the chunk.
(() => {
/*!**************************************!*\
  !*** ./assets/js/admin/src/panel.js ***!
  \**************************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _synonymous_field__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./synonymous-field */ "./assets/js/admin/src/synonymous-field.js");

var YWCAS_Admin_Panel = function YWCAS_Admin_Panel() {
  var handleDeps = function handleDeps() {
    var documentDeps = document.querySelectorAll('[data-deps]');
    documentDeps.forEach(function (item) {
      var wrap = jQuery(item).closest('.yith-plugin-fw__panel__option');
      var deps = item.dataset.deps.split(',');
      var values = item.dataset.deps_value.split(',');
      var conditions = [];
      deps.forEach(function (dep, index) {
        jQuery('#' + dep).on('change', function () {
          var value = this.value;
          var check_values = '';

          // exclude radio if not checked
          if (this.type == 'radio' && !this.checked) {
            return;
          }
          if (this.type == 'checkbox') {
            value = this.checked ? 'yes' : 'no';
          }
          check_values = String(values[index]); // force to string
          check_values = check_values.split('|');
          conditions[index] = check_values.includes(value);
          if (!conditions.includes(false)) {
            wrap.show('slow');
          } else {
            wrap.hide();
          }
        }).change();
      });
    });
  };
  var checkFuzzySlider = function checkFuzzySlider() {
    jQuery('.ywcas-slider-wrapper .ui-slider-horizontal input').on('change', function (e) {
      var sliderValue = jQuery('#yith_wcas_fuzzy_level').val();
      sliderValue = '' === sliderValue ? 0 : sliderValue;
      jQuery('#yith_wcas_fuzzy_level-preview').val(sliderValue);
    }).change();
  };
  var init = function init() {
    handleDeps();
    checkFuzzySlider();
    if (typeof document.querySelector('.ywcas-synonymous') !== 'undefined') {
      (0,_synonymous_field__WEBPACK_IMPORTED_MODULE_0__["default"])();
    }
  };
  init();
};
YWCAS_Admin_Panel();
})();

var __webpack_export_target__ = window;
for(var i in __webpack_exports__) __webpack_export_target__[i] = __webpack_exports__[i];
if(__webpack_exports__.__esModule) Object.defineProperty(__webpack_export_target__, "__esModule", { value: true });
/******/ })()
;
//# sourceMappingURL=panel.js.map