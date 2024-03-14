/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./assets/js/admin/src/functions.js":
/*!******************************************!*\
  !*** ./assets/js/admin/src/functions.js ***!
  \******************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   scrollTo: () => (/* binding */ scrollTo)
/* harmony export */ });
var scrollTo = function scrollTo(element) {
  jQuery('html,body').animate({
    scrollTop: jQuery(element).offset().top - 100
  }, 'slow');
};

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
/*!**********************************************!*\
  !*** ./assets/js/admin/src/search-fields.js ***!
  \**********************************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _functions__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./functions */ "./assets/js/admin/src/functions.js");
/**
 * Search fields manager
 *
 * @package
 * @since 2.0.0
 */

var YWCAS_Search_Fields = function YWCAS_Search_Fields() {
  var addFieldButton = document.querySelector('.ywcas-add-field');
  var searchFieldTableBody = document.querySelector('#search-fields tbody');
  var fieldTemplate = wp.template('ywcas-search-fields');
  var allPossibleOptions = [];
  var usedSearchFields = [];
  var availableFields = [];
  var maxPriority = 1;
  var fieldToCheck = ['product_tag', 'product_category', 'product_attribute', 'product_custom_field'];
  var setAllPossibleOptions = function setAllPossibleOptions() {
    var firstSearchFields = document.querySelector('.ywcas-search-field-type');
    var options = firstSearchFields.querySelectorAll('option');
    options.forEach(function (option) {
      return allPossibleOptions.push(option.value);
    });
  };
  var getUsedSearchFields = function getUsedSearchFields() {
    usedSearchFields = [];
    document.querySelectorAll('.ywcas-search-field-type').forEach(function (item) {
      usedSearchFields.push(item.value);
    });
  };
  var disableUsedOption = function disableUsedOption(selectItem) {
    jQuery(selectItem).find('option').attr('disabled', false);
    usedSearchFields.forEach(function (usedField) {
      if (selectItem.value !== usedField) {
        jQuery(selectItem).find("option[value=\"".concat(usedField, "\"]")).attr('disabled', true);
      }
    });
    jQuery(document.body).trigger('wc-enhanced-select-init');
    jQuery(selectItem).selectWoo();
  };
  var checkTrashButton = function checkTrashButton() {
    var searchFields = jQuery('.ywcas-search-field');
    if (searchFields.length == 1) {
      jQuery('.action__trash').hide();
    } else {
      jQuery('.action__trash').show();
    }
  };
  var refreshSearchFieldsOptions = function refreshSearchFieldsOptions() {
    getUsedSearchFields();
    checkTrashButton();
    jQuery(document).trigger('yith-plugin-fw-tips-init');
    document.querySelectorAll('.ywcas-search-field-type').forEach(function (item) {
      disableUsedOption(item);
    });
  };
  var addField = function addField(e) {
    e.preventDefault();
    getUsedSearchFields();
    availableFields = allPossibleOptions.filter(function (x) {
      return !usedSearchFields.includes(x);
    });
    var newField = fieldTemplate({
      id: Date.now(),
      edit: false
    });
    var row = document.createElement('tr');
    row.innerHTML = newField;
    row.classList.add('ywcas-search-field');
    row.querySelector('.ywcas-search-field-type').value = availableFields[0];
    row.querySelector('.ywcas-search-priority').value = getMaxPriority();
    searchFieldTableBody.appendChild(row);
    jQuery(row).find('.ywcas-search-field-type').trigger('change');
    updateAddFieldButton();
    jQuery(document.body).trigger('wc-enhanced-select-init');
    jQuery(document.body).trigger('yith-framework-enhanced-select-init');
    refreshSearchFieldsOptions();
  };
  var updateAddFieldButton = function updateAddFieldButton() {
    if (usedSearchFields.length === allPossibleOptions.length) {
      jQuery(addFieldButton).hide();
    } else {
      jQuery(addFieldButton).show();
    }
  };
  var checkFieldType = function checkFieldType(e) {
    var currentElement = e.target;
    var typeSelected = currentElement.value;
    var wrapper = currentElement.closest('.ywcas-search-field');
    var conditions = jQuery(wrapper).find("[data-type=\"".concat(typeSelected, "\"]"));
    jQuery(wrapper).find('.search-field-type-condition,.search-field-type-list').hide();
    if (conditions) {
      conditions.css({
        display: 'block'
      });
    }
    refreshSearchFieldsOptions();
  };
  var getMaxPriority = function getMaxPriority() {
    maxPriority = 1;
    document.querySelectorAll('.ywcas-search-priority').forEach(function (priority) {
      maxPriority = priority.value > maxPriority ? priority.value : maxPriority;
    });
    return ++maxPriority;
  };
  var checkSubCondition = function checkSubCondition(e) {
    var currentElement = e.target;
    var valueSelected = currentElement.value;
    var row = currentElement.closest('tr');
    var wrapper = currentElement.closest('.search-field-type-condition');
    var typeSelected = wrapper.dataset.type;
    if (valueSelected !== 'all') {
      jQuery(row).find("[data-subtype=\"".concat(typeSelected, "\"]")).show();
    } else {
      jQuery(row).find("[data-subtype=\"".concat(typeSelected, "\"]")).hide();
    }
  };
  var handleClickTrash = function handleClickTrash(e) {
    e.preventDefault();
    var trashElement = e.target;
    var searchFields = document.querySelectorAll('.ywcas-search-field');
    if (searchFields.length <= 1) {
      return;
    }
    yith.ui.confirm({
      title: ywcas_admin_params.message_alert.title,
      message: ywcas_admin_params.message_alert.desc,
      confirmButton: ywcas_admin_params.message_alert.confirmButton,
      closeAfterConfirm: true,
      classes: {
        wrap: 'ywcas-warning-popup'
      },
      onConfirm: function onConfirm() {
        var currentRow = trashElement.closest('tr');
        currentRow.remove();
        checkTrashButton();
        getUsedSearchFields();
        updateAddFieldButton();
        jQuery('#tiptip_holder').hide();
      }
    });
  };
  var handleClickSave = function handleClickSave(e) {
    e.preventDefault();
    var fields = document.querySelectorAll('.ywcas-search-field');
    var firstErrorElement = false;
    fields.forEach(function (field) {
      var _list$val;
      var type = jQuery(field).find('select.ywcas-search-field-type');
      var group = type.val();
      if (!fieldToCheck.includes(group)) {
        return;
      }
      var condition = null;
      if (group === 'product_tag' || group === 'product_category') {
        condition = jQuery(field).find(".".concat(group, "-condition"));
      }
      var list = jQuery(field).find(".select-".concat(group));
      var wrapper = list.closest('.yith-plugin-fw-select2-wrapper');
      if ((!condition || condition.val() != 'all') && ((_list$val = list.val()) === null || _list$val === void 0 ? void 0 : _list$val.length) === 0 && !wrapper.find('.select2').hasClass('is-empty')) {
        if (!firstErrorElement) {
          firstErrorElement = list;
        }
        wrapper.append(jQuery("<span class='empty-field description'>".concat(ywcas_admin_params.emptyField, "</span>")));
        wrapper.find('.select2').addClass('is-empty');
      }
    });
    if (firstErrorElement) {
      (0,_functions__WEBPACK_IMPORTED_MODULE_0__.scrollTo)(firstErrorElement);
    } else {
      jQuery('#plugin-fw-wc').submit();
    }
  };
  var removeError = function removeError(e) {
    var wrapper = jQuery(e.target).closest('.yith-plugin-fw-select2-wrapper');
    wrapper.find('.empty-field').remove();
    wrapper.find('.select2').removeClass('is-empty');
  };
  var init = function init() {
    setAllPossibleOptions();
    checkTrashButton();
    getUsedSearchFields();
    updateAddFieldButton();
    jQuery(document).on('click', '.ywcas-add-field', addField);
    jQuery(document).on('change', '.ywcas-search-field-type', checkFieldType);
    jQuery(document).on('change', '.search-field-type-condition select', checkSubCondition);
    jQuery(document).on('click', '.action__trash', handleClickTrash);
    jQuery(document).on('click', '#main-save-button', handleClickSave);
    jQuery(document).on('change', '.yith-term-search', removeError);
    jQuery(document).find('.ywcas-search-field-type').trigger('change');
    jQuery(document).find('.search-field-type-condition select').trigger('change');
  };
  init();
};
YWCAS_Search_Fields();
})();

var __webpack_export_target__ = window;
for(var i in __webpack_exports__) __webpack_export_target__[i] = __webpack_exports__[i];
if(__webpack_exports__.__esModule) Object.defineProperty(__webpack_export_target__, "__esModule", { value: true });
/******/ })()
;
//# sourceMappingURL=search-fields.js.map