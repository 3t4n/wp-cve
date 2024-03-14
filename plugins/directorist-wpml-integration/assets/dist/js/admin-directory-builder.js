/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = "./assets/src/js/admin/directory-builder.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./assets/src/js/admin/admin-main.js":
/*!*******************************************!*\
  !*** ./assets/src/js/admin/admin-main.js ***!
  \*******************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _scss_admin_admin_main_scss__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./../../scss/admin/admin-main.scss */ "./assets/src/scss/admin/admin-main.scss");
/* harmony import */ var _scss_admin_admin_main_scss__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_scss_admin_admin_main_scss__WEBPACK_IMPORTED_MODULE_0__);


/***/ }),

/***/ "./assets/src/js/admin/directory-builder.js":
/*!**************************************************!*\
  !*** ./assets/src/js/admin/directory-builder.js ***!
  \**************************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/toConsumableArray */ "./node_modules/@babel/runtime/helpers/toConsumableArray.js");
/* harmony import */ var _babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _scss_admin_directory_builder_scss__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./../../scss/admin/directory-builder.scss */ "./assets/src/scss/admin/directory-builder.scss");
/* harmony import */ var _scss_admin_directory_builder_scss__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_scss_admin_directory_builder_scss__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _admin_main__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./admin-main */ "./assets/src/js/admin/admin-main.js");

// CSS
 // JS


var tasks = {
  init: function init() {
    this.setupTranslationLinksToDirectoryType();
  },
  // setupTranslationLinksToDirectoryType
  setupTranslationLinksToDirectoryType: function setupTranslationLinksToDirectoryType() {
    var self = this;
    var url = directory_builder_script_data.ajax_url;
    var formData = {
      action: 'get_directory_type_translations',
      directorist_nonce: directory_builder_script_data.directorist_nonce
    };
    var queryStrings = new URLSearchParams(formData).toString();
    url = url + '?' + queryStrings;
    fetch(url).then(function (response) {
      return response.json();
    }).then(function (response) {
      if (!response.success) {
        console.log({
          response: response
        });
        return;
      }

      self.addTranslationLinksToDirectoryType(response.data);
      self.attachAddTranslationActionHandler();
    }).catch(function (error) {
      console.log({
        error: error
      });
    });
  },
  // addTranslationLinksToDirectoryType
  addTranslationLinksToDirectoryType: function addTranslationLinksToDirectoryType(data) {
    if (!data) {
      return;
    }

    if (!data.translation_edit_link_template) {
      return;
    }

    if (!data.translations) {
      return;
    }

    if (!data.wpml_active_languages) {
      return;
    }

    var self = this;
    var currentLanguage = data.wpml_current_language;
    var activeLanguages = data.wpml_active_languages;
    var term_translations = data.translations;
    var actions = document.querySelectorAll('.directorist_listing-actions'); // Add Translation Actions

    _babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_0___default()(actions).map(function (item) {
      var parent = item.closest('.directory-type-row');
      var termID = parent.getAttribute('data-term-id');
      termID = parseInt(termID);
      var translation_button_wrap = document.createElement('div');
      translation_button_wrap.className = 'directorist_more-dropdown';
      var translationListItems = Object.keys(activeLanguages).map(function (translation_key) {
        var translation = activeLanguages[translation_key];

        if (currentLanguage === translation_key) {
          return '';
        }

        var label = translation.translated_name;
        var hasTerm = Object.keys(term_translations).includes(termID.toString());
        var termTranslationKeys = hasTerm ? Object.keys(term_translations[termID]) : [];
        var hasTranslation = termTranslationKeys.includes(translation_key);
        var iconName = hasTranslation ? 'fas fa-edit' : 'fas fa-plus';
        var flag = translation.country_flag_url;
        var translationTermID = hasTranslation ? term_translations[termID][translation_key].term_id : 0;
        var link = hasTranslation ? self.parseTranslationEditLinkTemplate(data.translation_edit_link_template, translationTermID, translation_key) : '#';
        var linkClass = hasTranslation ? '' : ' directorist-link-has-action directorist-wpml-add-translation';
        return "<li class=\"directorist-list-item\" data-language-code=\"".concat(translation_key, "\">\n                    <span class=\"directorist-list-item-label\">\n                        <span class=\"directorist-list-item-icon\">\n                            <img src=\"").concat(flag, "\" />\n                        </span>\n        \n                        ").concat(label, "\n                    </span>\n        \n                    <div class=\"directorist-list-item-actions\">\n                        <a href=\"").concat(link, "\" class=\"directorist-list-item-action-link directorist-text-right--important").concat(linkClass, "\">\n                            <i class=\"").concat(iconName, "\"></i>\n                        </a>\n                    </div>\n                </li>");
      }).filter(function (item) {
        return item;
      }).join("\n");
      var translation_button = "\n                <a href=\"#\" class=\"directorist_btn directorist_btn-primary directorist_more-dropdown-toggle directorist_translation-dropdown-toggle\">\n                    <i class=\"fas fa-language\"></i>\n                </a>\n        \n                <div class=\"directorist_more-dropdown-option\">\n                    <ul>".concat(translationListItems, "</ul>\n                </div>\n            ");
      translation_button_wrap.innerHTML = translation_button;
      item.prepend(translation_button_wrap);
    });
  },
  // parseTranslationEditLinkTemplate
  parseTranslationEditLinkTemplate: function parseTranslationEditLinkTemplate(template, translationID, languageKey) {
    return template.replace('__ID__', translationID).replace('__LANGUAGE__', languageKey);
  },
  // attachAddTranslationActionHandler
  attachAddTranslationActionHandler: function attachAddTranslationActionHandler() {
    var links = document.querySelectorAll('.directorist-wpml-add-translation');

    _babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_0___default()(links).map(function (link) {
      link.addEventListener('click', handleAddTranslationAction);
    });
  },
  // addTranslation
  addTranslation: function addTranslation(context, event) {
    var dropdown = context.closest('.directorist_more-dropdown-option');
    dropdown.classList.add('active');

    var isLoading = _babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_0___default()(context.classList).includes('directorist-is-loading');

    if (isLoading) {
      return;
    }

    var originalContent = context.innerHTML;
    context.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    context.classList.add('directorist-is-loading');
    var directory_type_id = context.closest('.directory-type-row').getAttribute('data-term-id');
    var language_code = context.closest('.directorist-list-item').getAttribute('data-language-code');
    var url = directory_builder_script_data.ajax_url;
    var formData = {
      action: 'create_directory_type_translation',
      directorist_nonce: directory_builder_script_data.directorist_nonce,
      directory_type_id: directory_type_id,
      taranslation_language_code: language_code
    };
    var queryStrings = new URLSearchParams(formData).toString();
    url = url + '?' + queryStrings;
    fetch(url).then(function (response) {
      return response.json();
    }).then(function (response) {
      context.classList.remove('directorist-is-loading');

      if (!response.success) {
        context.innerHTML = originalContent;
        console.log({
          response: response
        });
        alert(response.message);
        return;
      }

      context.setAttribute('href', response.data.edit_link);
      context.removeEventListener('click', handleAddTranslationAction);
      context.classList.remove('directorist-link-has-action');
      context.classList.remove('directorist-wpml-add-translation');
      context.innerHTML = '<i class="fas fa-edit"></i>';
    }).catch(function (error) {
      context.classList.remove('directorist-is-loading');
      context.innerHTML = originalContent;
      console.log({
        error: error
      });
    });
  }
}; // Init

tasks.init(); // handleAddTranslationAction

function handleAddTranslationAction(event) {
  event.preventDefault();
  var context = this;
  setTimeout(function () {
    tasks.addTranslation(context, event);
  }, 0);
}

/***/ }),

/***/ "./assets/src/scss/admin/admin-main.scss":
/*!***********************************************!*\
  !*** ./assets/src/scss/admin/admin-main.scss ***!
  \***********************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

// extracted by mini-css-extract-plugin

/***/ }),

/***/ "./assets/src/scss/admin/directory-builder.scss":
/*!******************************************************!*\
  !*** ./assets/src/scss/admin/directory-builder.scss ***!
  \******************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

// extracted by mini-css-extract-plugin

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/arrayLikeToArray.js":
/*!*****************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/arrayLikeToArray.js ***!
  \*****************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

function _arrayLikeToArray(arr, len) {
  if (len == null || len > arr.length) len = arr.length;

  for (var i = 0, arr2 = new Array(len); i < len; i++) {
    arr2[i] = arr[i];
  }

  return arr2;
}

module.exports = _arrayLikeToArray, module.exports.__esModule = true, module.exports["default"] = module.exports;

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/arrayWithoutHoles.js":
/*!******************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/arrayWithoutHoles.js ***!
  \******************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var arrayLikeToArray = __webpack_require__(/*! ./arrayLikeToArray.js */ "./node_modules/@babel/runtime/helpers/arrayLikeToArray.js");

function _arrayWithoutHoles(arr) {
  if (Array.isArray(arr)) return arrayLikeToArray(arr);
}

module.exports = _arrayWithoutHoles, module.exports.__esModule = true, module.exports["default"] = module.exports;

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/iterableToArray.js":
/*!****************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/iterableToArray.js ***!
  \****************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

function _iterableToArray(iter) {
  if (typeof Symbol !== "undefined" && iter[Symbol.iterator] != null || iter["@@iterator"] != null) return Array.from(iter);
}

module.exports = _iterableToArray, module.exports.__esModule = true, module.exports["default"] = module.exports;

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/nonIterableSpread.js":
/*!******************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/nonIterableSpread.js ***!
  \******************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

function _nonIterableSpread() {
  throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.");
}

module.exports = _nonIterableSpread, module.exports.__esModule = true, module.exports["default"] = module.exports;

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/toConsumableArray.js":
/*!******************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/toConsumableArray.js ***!
  \******************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var arrayWithoutHoles = __webpack_require__(/*! ./arrayWithoutHoles.js */ "./node_modules/@babel/runtime/helpers/arrayWithoutHoles.js");

var iterableToArray = __webpack_require__(/*! ./iterableToArray.js */ "./node_modules/@babel/runtime/helpers/iterableToArray.js");

var unsupportedIterableToArray = __webpack_require__(/*! ./unsupportedIterableToArray.js */ "./node_modules/@babel/runtime/helpers/unsupportedIterableToArray.js");

var nonIterableSpread = __webpack_require__(/*! ./nonIterableSpread.js */ "./node_modules/@babel/runtime/helpers/nonIterableSpread.js");

function _toConsumableArray(arr) {
  return arrayWithoutHoles(arr) || iterableToArray(arr) || unsupportedIterableToArray(arr) || nonIterableSpread();
}

module.exports = _toConsumableArray, module.exports.__esModule = true, module.exports["default"] = module.exports;

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/unsupportedIterableToArray.js":
/*!***************************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/unsupportedIterableToArray.js ***!
  \***************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var arrayLikeToArray = __webpack_require__(/*! ./arrayLikeToArray.js */ "./node_modules/@babel/runtime/helpers/arrayLikeToArray.js");

function _unsupportedIterableToArray(o, minLen) {
  if (!o) return;
  if (typeof o === "string") return arrayLikeToArray(o, minLen);
  var n = Object.prototype.toString.call(o).slice(8, -1);
  if (n === "Object" && o.constructor) n = o.constructor.name;
  if (n === "Map" || n === "Set") return Array.from(o);
  if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return arrayLikeToArray(o, minLen);
}

module.exports = _unsupportedIterableToArray, module.exports.__esModule = true, module.exports["default"] = module.exports;

/***/ })

/******/ });
//# sourceMappingURL=admin-directory-builder.js.map