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
/******/ 	return __webpack_require__(__webpack_require__.s = 0);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./src/assets/js/script.js":
/*!*********************************!*\
  !*** ./src/assets/js/script.js ***!
  \*********************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("var $ = window.jQuery;\n$(function () {\n  function readMoreAnimation() {\n    var lineSingleHeight = parseInt($('.gosign-readmore-textbox .rTextBlock > p').css('line-height'));\n    var paddingTopBottom = parseInt($('.gosign-readmore-textbox .rTextBlock').css('padding-top')) * 2;\n    var numberOflines,\n        speedOfAnimation,\n        visibleHeight,\n        curHeight,\n        numberOflines2 = 0;\n    $('.gosign-readmore-textbox').each(function (index) {\n      numberOflines = parseInt($(this).find(\".rTextBlock\").attr(\"data-line\"));\n      $(this).find(\".rTextBlock\").css('height', numberOflines * lineSingleHeight + 'px'); // No slideUpDown\n\n      if ($(this).hasClass(\"slideUpDown\")) {\n        $(this).find('.rMoreBtn').on('click', function () {\n          speedOfAnimation = parseInt($(this).siblings(\".rTextBlock\").attr(\"data-speed\"));\n          curHeight = $(this).siblings(\".rTextBlock\").find('p').height();\n          $(this).siblings('.rTextBlock').animate({\n            height: curHeight + 'px'\n          }, speedOfAnimation);\n          $(this).parent().removeClass('detailClosed');\n          $(this).parent().addClass('detailOpen');\n        });\n        $(this).find('.rCloseBtn').on('click', function () {\n          var speedOfAnimation2 = parseInt($(this).siblings(\".rTextBlock\").attr(\"data-speed\"));\n          numberOflines2 = parseInt($(this).siblings(\".rTextBlock\").attr(\"data-line\"));\n          visibleHeight = numberOflines2 * lineSingleHeight;\n          $(this).siblings('.rTextBlock').animate({\n            height: visibleHeight + 'px'\n          }, speedOfAnimation2); //$(this).siblings('.rTextBlock').animate({height: \"initial\"});\n\n          $(this).parent().removeClass('detailOpen');\n          $(this).parent().addClass('detailClosed');\n        });\n      } // No Animation\n\n\n      if ($(this).hasClass(\"no-animation\")) {\n        $(this).find('.rMoreBtn').on('click', function () {\n          var curHeight2 = $(this).siblings(\".rTextBlock\").find('p').height();\n          $(this).siblings('.rTextBlock').animate({\n            height: curHeight2 + 'px'\n          }, 1);\n          $(this).parent().removeClass('detailClosed');\n          $(this).parent().addClass('detailOpen');\n        });\n        $(this).find('.rCloseBtn').on('click', function () {\n          var numberOflines3 = parseInt($(this).siblings(\".rTextBlock\").attr(\"data-line\"));\n          var visibleHeight2 = numberOflines3 * lineSingleHeight;\n          $(this).siblings('.rTextBlock').animate({\n            height: visibleHeight2 + 'px'\n          }, 1);\n          $(this).parent().removeClass('detailOpen');\n          $(this).parent().addClass('detailClosed');\n        });\n      }\n    });\n  }\n\n  if ($('.gosign-readmore-textbox').length > 0) {\n    readMoreAnimation();\n  }\n});\n\n//# sourceURL=webpack:///./src/assets/js/script.js?");

/***/ }),

/***/ 0:
/*!***************************************!*\
  !*** multi ./src/assets/js/script.js ***!
  \***************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

eval("module.exports = __webpack_require__(/*! /var/www/work/wordpress-test/wp-content/plugins/gosign-readmore-toggle-text-block/src/assets/js/script.js */\"./src/assets/js/script.js\");\n\n\n//# sourceURL=webpack:///multi_./src/assets/js/script.js?");

/***/ })

/******/ });