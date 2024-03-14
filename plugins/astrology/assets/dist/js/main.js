/*
 * ATTENTION: An "eval-source-map" devtool has been used.
 * This devtool is neither made for production nor for readable output files.
 * It uses "eval()" calls to create a separate source file with attached SourceMaps in the browser devtools.
 * If you are trying to read the output file, select a different devtool (https://webpack.js.org/configuration/devtool/)
 * or disable the default devtool with "devtool: false".
 * If you are looking for production-ready output files, see mode: "production" (https://webpack.js.org/configuration/mode/).
 */
/******/ (function() { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./assets/src/js/front/main/app.js":
/*!*****************************************!*\
  !*** ./assets/src/js/front/main/app.js ***!
  \*****************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _main__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./main */ \"./assets/src/js/front/main/main.js\");\n\ndocument.addEventListener('DOMContentLoaded', () => {\n  new _main__WEBPACK_IMPORTED_MODULE_0__.Main();\n});//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiLi9hc3NldHMvc3JjL2pzL2Zyb250L21haW4vYXBwLmpzIiwibWFwcGluZ3MiOiI7O0FBQUE7QUFDQTtBQUNBO0FBQ0EiLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly9hc3Ryb2xvZ3kvLi9hc3NldHMvc3JjL2pzL2Zyb250L21haW4vYXBwLmpzPzllMDgiXSwic291cmNlc0NvbnRlbnQiOlsiaW1wb3J0IHsgTWFpbiB9IGZyb20gJy4vbWFpbic7XG5kb2N1bWVudC5hZGRFdmVudExpc3RlbmVyKCdET01Db250ZW50TG9hZGVkJywgKCkgPT4ge1xuICBuZXcgTWFpbigpO1xufSk7Il0sIm5hbWVzIjpbXSwic291cmNlUm9vdCI6IiJ9\n//# sourceURL=webpack-internal:///./assets/src/js/front/main/app.js\n");

/***/ }),

/***/ "./assets/src/js/front/main/main.js":
/*!******************************************!*\
  !*** ./assets/src/js/front/main/main.js ***!
  \******************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export */ __webpack_require__.d(__webpack_exports__, {\n/* harmony export */   Main: function() { return /* binding */ Main; }\n/* harmony export */ });\n/**\n * Class Main.\n *\n * @since 1.0.0\n */\nclass Main {\n  /**\n   * Main constructor.\n   *\n   * @since 1.0.0\n   */\n  constructor() {\n    const inputs = document.querySelectorAll('.prokerala-location-input');\n    this.register(inputs);\n  }\n  register(inputs) {\n    [...inputs].forEach(function (input) {\n      const inputPrefix = input.dataset.location_input_prefix ? input.dataset.location_input_prefix + '_' : '';\n      new LocationSearch(\n      // eslint-disable-line no-undef\n      input, function (data) {\n        const hiddenDiv = document.getElementById('form-hidden-fields');\n        const coordinates = document.createElement('input');\n        coordinates.name = inputPrefix + 'coordinates';\n        coordinates.type = 'hidden';\n        coordinates.value = `${data.latitude},${data.longitude}`;\n        const timezone = document.createElement('input');\n        timezone.name = inputPrefix + 'timezone';\n        timezone.type = 'hidden';\n        timezone.value = data.timezone;\n        hiddenDiv.appendChild(coordinates);\n        hiddenDiv.appendChild(timezone);\n      }, {\n        clientId: CLIENT_ID,\n        persistKey: `${inputPrefix}loc`\n      } // eslint-disable-line no-undef\n      );\n    });\n  }\n}//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiLi9hc3NldHMvc3JjL2pzL2Zyb250L21haW4vbWFpbi5qcyIsIm1hcHBpbmdzIjoiOzs7O0FBQUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EiLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly9hc3Ryb2xvZ3kvLi9hc3NldHMvc3JjL2pzL2Zyb250L21haW4vbWFpbi5qcz9kNzAwIl0sInNvdXJjZXNDb250ZW50IjpbIi8qKlxuICogQ2xhc3MgTWFpbi5cbiAqXG4gKiBAc2luY2UgMS4wLjBcbiAqL1xuZXhwb3J0IGNsYXNzIE1haW4ge1xuICAvKipcbiAgICogTWFpbiBjb25zdHJ1Y3Rvci5cbiAgICpcbiAgICogQHNpbmNlIDEuMC4wXG4gICAqL1xuICBjb25zdHJ1Y3RvcigpIHtcbiAgICBjb25zdCBpbnB1dHMgPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yQWxsKCcucHJva2VyYWxhLWxvY2F0aW9uLWlucHV0Jyk7XG4gICAgdGhpcy5yZWdpc3RlcihpbnB1dHMpO1xuICB9XG4gIHJlZ2lzdGVyKGlucHV0cykge1xuICAgIFsuLi5pbnB1dHNdLmZvckVhY2goZnVuY3Rpb24gKGlucHV0KSB7XG4gICAgICBjb25zdCBpbnB1dFByZWZpeCA9IGlucHV0LmRhdGFzZXQubG9jYXRpb25faW5wdXRfcHJlZml4ID8gaW5wdXQuZGF0YXNldC5sb2NhdGlvbl9pbnB1dF9wcmVmaXggKyAnXycgOiAnJztcbiAgICAgIG5ldyBMb2NhdGlvblNlYXJjaChcbiAgICAgIC8vIGVzbGludC1kaXNhYmxlLWxpbmUgbm8tdW5kZWZcbiAgICAgIGlucHV0LCBmdW5jdGlvbiAoZGF0YSkge1xuICAgICAgICBjb25zdCBoaWRkZW5EaXYgPSBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgnZm9ybS1oaWRkZW4tZmllbGRzJyk7XG4gICAgICAgIGNvbnN0IGNvb3JkaW5hdGVzID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnaW5wdXQnKTtcbiAgICAgICAgY29vcmRpbmF0ZXMubmFtZSA9IGlucHV0UHJlZml4ICsgJ2Nvb3JkaW5hdGVzJztcbiAgICAgICAgY29vcmRpbmF0ZXMudHlwZSA9ICdoaWRkZW4nO1xuICAgICAgICBjb29yZGluYXRlcy52YWx1ZSA9IGAke2RhdGEubGF0aXR1ZGV9LCR7ZGF0YS5sb25naXR1ZGV9YDtcbiAgICAgICAgY29uc3QgdGltZXpvbmUgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdpbnB1dCcpO1xuICAgICAgICB0aW1lem9uZS5uYW1lID0gaW5wdXRQcmVmaXggKyAndGltZXpvbmUnO1xuICAgICAgICB0aW1lem9uZS50eXBlID0gJ2hpZGRlbic7XG4gICAgICAgIHRpbWV6b25lLnZhbHVlID0gZGF0YS50aW1lem9uZTtcbiAgICAgICAgaGlkZGVuRGl2LmFwcGVuZENoaWxkKGNvb3JkaW5hdGVzKTtcbiAgICAgICAgaGlkZGVuRGl2LmFwcGVuZENoaWxkKHRpbWV6b25lKTtcbiAgICAgIH0sIHtcbiAgICAgICAgY2xpZW50SWQ6IENMSUVOVF9JRCxcbiAgICAgICAgcGVyc2lzdEtleTogYCR7aW5wdXRQcmVmaXh9bG9jYFxuICAgICAgfSAvLyBlc2xpbnQtZGlzYWJsZS1saW5lIG5vLXVuZGVmXG4gICAgICApO1xuICAgIH0pO1xuICB9XG59Il0sIm5hbWVzIjpbXSwic291cmNlUm9vdCI6IiJ9\n//# sourceURL=webpack-internal:///./assets/src/js/front/main/main.js\n");

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
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module can't be inlined because the eval-source-map devtool is used.
/******/ 	var __webpack_exports__ = __webpack_require__("./assets/src/js/front/main/app.js");
/******/ 	
/******/ })()
;