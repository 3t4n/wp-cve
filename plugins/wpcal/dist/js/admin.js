/******/ (function(modules) { // webpackBootstrap
/******/ 	// install a JSONP callback for chunk loading
/******/ 	function webpackJsonpCallback(data) {
/******/ 		var chunkIds = data[0];
/******/ 		var moreModules = data[1];
/******/ 		var executeModules = data[2];
/******/
/******/ 		// add "moreModules" to the modules object,
/******/ 		// then flag all "chunkIds" as loaded and fire callback
/******/ 		var moduleId, chunkId, i = 0, resolves = [];
/******/ 		for(;i < chunkIds.length; i++) {
/******/ 			chunkId = chunkIds[i];
/******/ 			if(Object.prototype.hasOwnProperty.call(installedChunks, chunkId) && installedChunks[chunkId]) {
/******/ 				resolves.push(installedChunks[chunkId][0]);
/******/ 			}
/******/ 			installedChunks[chunkId] = 0;
/******/ 		}
/******/ 		for(moduleId in moreModules) {
/******/ 			if(Object.prototype.hasOwnProperty.call(moreModules, moduleId)) {
/******/ 				modules[moduleId] = moreModules[moduleId];
/******/ 			}
/******/ 		}
/******/ 		if(parentJsonpFunction) parentJsonpFunction(data);
/******/
/******/ 		while(resolves.length) {
/******/ 			resolves.shift()();
/******/ 		}
/******/
/******/ 		// add entry modules from loaded chunk to deferred list
/******/ 		deferredModules.push.apply(deferredModules, executeModules || []);
/******/
/******/ 		// run deferred modules when all chunks ready
/******/ 		return checkDeferredModules();
/******/ 	};
/******/ 	function checkDeferredModules() {
/******/ 		var result;
/******/ 		for(var i = 0; i < deferredModules.length; i++) {
/******/ 			var deferredModule = deferredModules[i];
/******/ 			var fulfilled = true;
/******/ 			for(var j = 1; j < deferredModule.length; j++) {
/******/ 				var depId = deferredModule[j];
/******/ 				if(installedChunks[depId] !== 0) fulfilled = false;
/******/ 			}
/******/ 			if(fulfilled) {
/******/ 				deferredModules.splice(i--, 1);
/******/ 				result = __webpack_require__(__webpack_require__.s = deferredModule[0]);
/******/ 			}
/******/ 		}
/******/
/******/ 		return result;
/******/ 	}
/******/
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// object to store loaded and loading chunks
/******/ 	// undefined = chunk not loaded, null = chunk preloaded/prefetched
/******/ 	// Promise = chunk loading, 0 = chunk loaded
/******/ 	var installedChunks = {
/******/ 		"admin": 0
/******/ 	};
/******/
/******/ 	var deferredModules = [];
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
/******/ 	__webpack_require__.p = "http://localhost:8080/";
/******/
/******/ 	var jsonpArray = window["wpcalMainWebpackJsonp"] = window["wpcalMainWebpackJsonp"] || [];
/******/ 	var oldJsonpFunction = jsonpArray.push.bind(jsonpArray);
/******/ 	jsonpArray.push = webpackJsonpCallback;
/******/ 	jsonpArray = jsonpArray.slice();
/******/ 	for(var i = 0; i < jsonpArray.length; i++) webpackJsonpCallback(jsonpArray[i]);
/******/ 	var parentJsonpFunction = oldJsonpFunction;
/******/
/******/
/******/ 	// add entry module to deferred list
/******/ 	deferredModules.push([0,"chunk-vendors","chunk-common"]);
/******/ 	// run deferred modules when ready
/******/ 	return checkDeferredModules();
/******/ })
/************************************************************************/
/******/ ({

/***/ 0:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__("ad9c");


/***/ }),

/***/ "005e":
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_node_modules_cache_loader_dist_cjs_js_ref_1_0_node_modules_vue_loader_lib_index_js_vue_loader_options_OnOffSlider_vue_vue_type_style_index_0_id_27868b2f_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__("84ed");
/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_node_modules_cache_loader_dist_cjs_js_ref_1_0_node_modules_vue_loader_lib_index_js_vue_loader_options_OnOffSlider_vue_vue_type_style_index_0_id_27868b2f_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_node_modules_cache_loader_dist_cjs_js_ref_1_0_node_modules_vue_loader_lib_index_js_vue_loader_options_OnOffSlider_vue_vue_type_style_index_0_id_27868b2f_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__);
/* unused harmony reexport * */


/***/ }),

/***/ "03d0":
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_node_modules_cache_loader_dist_cjs_js_ref_1_0_node_modules_vue_loader_lib_index_js_vue_loader_options_InlineRadioSelector_vue_vue_type_style_index_0_id_32d5feb0_lang_css_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__("4a53");
/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_node_modules_cache_loader_dist_cjs_js_ref_1_0_node_modules_vue_loader_lib_index_js_vue_loader_options_InlineRadioSelector_vue_vue_type_style_index_0_id_32d5feb0_lang_css_scoped_true___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_node_modules_cache_loader_dist_cjs_js_ref_1_0_node_modules_vue_loader_lib_index_js_vue_loader_options_InlineRadioSelector_vue_vue_type_style_index_0_id_32d5feb0_lang_css_scoped_true___WEBPACK_IMPORTED_MODULE_0__);
/* unused harmony reexport * */


/***/ }),

/***/ "056a":
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_node_modules_cache_loader_dist_cjs_js_ref_1_0_node_modules_vue_loader_lib_index_js_vue_loader_options_HostSelector1ToN_vue_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__("1dd7");
/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_node_modules_cache_loader_dist_cjs_js_ref_1_0_node_modules_vue_loader_lib_index_js_vue_loader_options_HostSelector1ToN_vue_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_node_modules_cache_loader_dist_cjs_js_ref_1_0_node_modules_vue_loader_lib_index_js_vue_loader_options_HostSelector1ToN_vue_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_0__);
/* unused harmony reexport * */


/***/ }),

/***/ "07f5":
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_wf_stylesheet_css_vue_type_style_index_1_lang_css___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__("7deb");
/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_wf_stylesheet_css_vue_type_style_index_1_lang_css___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_wf_stylesheet_css_vue_type_style_index_1_lang_css___WEBPACK_IMPORTED_MODULE_0__);
/* unused harmony reexport * */


/***/ }),

/***/ "1400":
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_node_modules_cache_loader_dist_cjs_js_ref_1_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ServiceAvailabilityCal_vue_vue_type_style_index_0_id_0ceb2c66_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__("2db2");
/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_node_modules_cache_loader_dist_cjs_js_ref_1_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ServiceAvailabilityCal_vue_vue_type_style_index_0_id_0ceb2c66_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_node_modules_cache_loader_dist_cjs_js_ref_1_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ServiceAvailabilityCal_vue_vue_type_style_index_0_id_0ceb2c66_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__);
/* unused harmony reexport * */


/***/ }),

/***/ "1dd7":
/***/ (function(module, exports, __webpack_require__) {

// extracted by mini-css-extract-plugin

/***/ }),

/***/ "1ee9":
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_node_modules_cache_loader_dist_cjs_js_ref_1_0_node_modules_vue_loader_lib_index_js_vue_loader_options_SettingAccountSignupLogin_vue_vue_type_style_index_0_id_37f1326d_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__("5ac6");
/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_node_modules_cache_loader_dist_cjs_js_ref_1_0_node_modules_vue_loader_lib_index_js_vue_loader_options_SettingAccountSignupLogin_vue_vue_type_style_index_0_id_37f1326d_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_node_modules_cache_loader_dist_cjs_js_ref_1_0_node_modules_vue_loader_lib_index_js_vue_loader_options_SettingAccountSignupLogin_vue_vue_type_style_index_0_id_37f1326d_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__);
/* unused harmony reexport * */


/***/ }),

/***/ "23d3":
/***/ (function(module, exports, __webpack_require__) {

// extracted by mini-css-extract-plugin

/***/ }),

/***/ "27c6":
/***/ (function(module, exports, __webpack_require__) {

// extracted by mini-css-extract-plugin

/***/ }),

/***/ "27d6":
/***/ (function(module, exports, __webpack_require__) {

// extracted by mini-css-extract-plugin

/***/ }),

/***/ "2cd5":
/***/ (function(module, exports, __webpack_require__) {

// extracted by mini-css-extract-plugin

/***/ }),

/***/ "2db2":
/***/ (function(module, exports, __webpack_require__) {

// extracted by mini-css-extract-plugin

/***/ }),

/***/ "3133":
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_node_modules_cache_loader_dist_cjs_js_ref_1_0_node_modules_vue_loader_lib_index_js_vue_loader_options_OnBoardingCheckList_vue_vue_type_style_index_0_id_7e87c989_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__("9195");
/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_node_modules_cache_loader_dist_cjs_js_ref_1_0_node_modules_vue_loader_lib_index_js_vue_loader_options_OnBoardingCheckList_vue_vue_type_style_index_0_id_7e87c989_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_node_modules_cache_loader_dist_cjs_js_ref_1_0_node_modules_vue_loader_lib_index_js_vue_loader_options_OnBoardingCheckList_vue_vue_type_style_index_0_id_7e87c989_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__);
/* unused harmony reexport * */


/***/ }),

/***/ "3404":
/***/ (function(module, exports, __webpack_require__) {

// extracted by mini-css-extract-plugin

/***/ }),

/***/ "361c":
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_node_modules_cache_loader_dist_cjs_js_ref_1_0_node_modules_vue_loader_lib_index_js_vue_loader_options_PricingStrongerTogether_vue_vue_type_style_index_0_id_69d67168_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__("27d6");
/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_node_modules_cache_loader_dist_cjs_js_ref_1_0_node_modules_vue_loader_lib_index_js_vue_loader_options_PricingStrongerTogether_vue_vue_type_style_index_0_id_69d67168_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_node_modules_cache_loader_dist_cjs_js_ref_1_0_node_modules_vue_loader_lib_index_js_vue_loader_options_PricingStrongerTogether_vue_vue_type_style_index_0_id_69d67168_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__);
/* unused harmony reexport * */


/***/ }),

/***/ "3833":
/***/ (function(module, exports, __webpack_require__) {

// extracted by mini-css-extract-plugin

/***/ }),

/***/ "38ac":
/***/ (function(module, exports, __webpack_require__) {

// extracted by mini-css-extract-plugin

/***/ }),

/***/ "3a8c":
/***/ (function(module, exports, __webpack_require__) {

// extracted by mini-css-extract-plugin

/***/ }),

/***/ "3f23":
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_node_modules_cache_loader_dist_cjs_js_ref_1_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AlertAdminToUpdateProfile_vue_vue_type_style_index_0_id_14402081_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__("f663");
/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_node_modules_cache_loader_dist_cjs_js_ref_1_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AlertAdminToUpdateProfile_vue_vue_type_style_index_0_id_14402081_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_node_modules_cache_loader_dist_cjs_js_ref_1_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AlertAdminToUpdateProfile_vue_vue_type_style_index_0_id_14402081_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__);
/* unused harmony reexport * */


/***/ }),

/***/ "401c":
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_9_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_9_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_9_oneOf_1_2_node_modules_sass_loader_dist_cjs_js_ref_9_oneOf_1_3_node_modules_cache_loader_dist_cjs_js_ref_1_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ServiceFormStep1_vue_vue_type_style_index_0_id_5ecf4cde_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__("a712");
/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_9_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_9_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_9_oneOf_1_2_node_modules_sass_loader_dist_cjs_js_ref_9_oneOf_1_3_node_modules_cache_loader_dist_cjs_js_ref_1_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ServiceFormStep1_vue_vue_type_style_index_0_id_5ecf4cde_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_mini_css_extract_plugin_dist_loader_js_ref_9_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_9_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_9_oneOf_1_2_node_modules_sass_loader_dist_cjs_js_ref_9_oneOf_1_3_node_modules_cache_loader_dist_cjs_js_ref_1_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ServiceFormStep1_vue_vue_type_style_index_0_id_5ecf4cde_lang_scss_scoped_true___WEBPACK_IMPORTED_MODULE_0__);
/* unused harmony reexport * */


/***/ }),

/***/ "4477":
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_node_modules_cache_loader_dist_cjs_js_ref_1_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ColorSelector_vue_vue_type_style_index_0_id_13a331cc_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__("5122");
/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_node_modules_cache_loader_dist_cjs_js_ref_1_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ColorSelector_vue_vue_type_style_index_0_id_13a331cc_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_node_modules_cache_loader_dist_cjs_js_ref_1_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ColorSelector_vue_vue_type_style_index_0_id_13a331cc_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__);
/* unused harmony reexport * */


/***/ }),

/***/ "4543":
/***/ (function(module, exports, __webpack_require__) {

// extracted by mini-css-extract-plugin

/***/ }),

/***/ "4753":
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_node_modules_cache_loader_dist_cjs_js_ref_1_0_node_modules_vue_loader_lib_index_js_vue_loader_options_TimeRange1ToN_vue_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__("27c6");
/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_node_modules_cache_loader_dist_cjs_js_ref_1_0_node_modules_vue_loader_lib_index_js_vue_loader_options_TimeRange1ToN_vue_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_node_modules_cache_loader_dist_cjs_js_ref_1_0_node_modules_vue_loader_lib_index_js_vue_loader_options_TimeRange1ToN_vue_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_0__);
/* unused harmony reexport * */


/***/ }),

/***/ "4a53":
/***/ (function(module, exports, __webpack_require__) {

// extracted by mini-css-extract-plugin

/***/ }),

/***/ "4d8e":
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_node_modules_cache_loader_dist_cjs_js_ref_1_0_node_modules_vue_loader_lib_index_js_vue_loader_options_TypeToSelect_vue_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__("6e16");
/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_node_modules_cache_loader_dist_cjs_js_ref_1_0_node_modules_vue_loader_lib_index_js_vue_loader_options_TypeToSelect_vue_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_node_modules_cache_loader_dist_cjs_js_ref_1_0_node_modules_vue_loader_lib_index_js_vue_loader_options_TypeToSelect_vue_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_0__);
/* unused harmony reexport * */


/***/ }),

/***/ "4fc3":
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_admin_app_css_vue_type_style_index_2_lang_css___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__("ede4");
/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_admin_app_css_vue_type_style_index_2_lang_css___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_admin_app_css_vue_type_style_index_2_lang_css___WEBPACK_IMPORTED_MODULE_0__);
/* unused harmony reexport * */


/***/ }),

/***/ "5122":
/***/ (function(module, exports, __webpack_require__) {

// extracted by mini-css-extract-plugin

/***/ }),

/***/ "524c":
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_node_modules_cache_loader_dist_cjs_js_ref_1_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ServiceAvailabilityCal_vue_vue_type_style_index_1_lang_css___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__("94a4");
/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_node_modules_cache_loader_dist_cjs_js_ref_1_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ServiceAvailabilityCal_vue_vue_type_style_index_1_lang_css___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_node_modules_cache_loader_dist_cjs_js_ref_1_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ServiceAvailabilityCal_vue_vue_type_style_index_1_lang_css___WEBPACK_IMPORTED_MODULE_0__);
/* unused harmony reexport * */


/***/ }),

/***/ "5366":
/***/ (function(module, exports, __webpack_require__) {

// extracted by mini-css-extract-plugin

/***/ }),

/***/ "5536":
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_admin_settings_css_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__("5fbe");
/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_admin_settings_css_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_admin_settings_css_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_0__);
/* unused harmony reexport * */


/***/ }),

/***/ "59fb":
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_admin_service_bookings_css_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__("8c72");
/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_admin_service_bookings_css_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_admin_service_bookings_css_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_0__);
/* unused harmony reexport * */


/***/ }),

/***/ "5ac6":
/***/ (function(module, exports, __webpack_require__) {

// extracted by mini-css-extract-plugin

/***/ }),

/***/ "5bcd":
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_node_modules_cache_loader_dist_cjs_js_ref_1_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ServiceFormStep2_vue_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__("e7fa");
/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_node_modules_cache_loader_dist_cjs_js_ref_1_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ServiceFormStep2_vue_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_node_modules_cache_loader_dist_cjs_js_ref_1_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ServiceFormStep2_vue_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_0__);
/* unused harmony reexport * */


/***/ }),

/***/ "5f56":
/***/ (function(module, exports, __webpack_require__) {

// extracted by mini-css-extract-plugin

/***/ }),

/***/ "5fbe":
/***/ (function(module, exports, __webpack_require__) {

// extracted by mini-css-extract-plugin

/***/ }),

/***/ "6806":
/***/ (function(module, exports, __webpack_require__) {

// extracted by mini-css-extract-plugin

/***/ }),

/***/ "6e16":
/***/ (function(module, exports, __webpack_require__) {

// extracted by mini-css-extract-plugin

/***/ }),

/***/ "71c3":
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_node_modules_cache_loader_dist_cjs_js_ref_1_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ServiceFormStep3_vue_vue_type_style_index_0_id_20ee7b70_lang_css_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__("ce1a");
/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_node_modules_cache_loader_dist_cjs_js_ref_1_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ServiceFormStep3_vue_vue_type_style_index_0_id_20ee7b70_lang_css_scoped_true___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_node_modules_cache_loader_dist_cjs_js_ref_1_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ServiceFormStep3_vue_vue_type_style_index_0_id_20ee7b70_lang_css_scoped_true___WEBPACK_IMPORTED_MODULE_0__);
/* unused harmony reexport * */


/***/ }),

/***/ "7ab7":
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_node_modules_cache_loader_dist_cjs_js_ref_1_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ServiceBookingList_vue_vue_type_style_index_0_id_746e8b6e_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__("ba0b");
/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_node_modules_cache_loader_dist_cjs_js_ref_1_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ServiceBookingList_vue_vue_type_style_index_0_id_746e8b6e_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_node_modules_cache_loader_dist_cjs_js_ref_1_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ServiceBookingList_vue_vue_type_style_index_0_id_746e8b6e_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__);
/* unused harmony reexport * */


/***/ }),

/***/ "7deb":
/***/ (function(module, exports, __webpack_require__) {

// extracted by mini-css-extract-plugin

/***/ }),

/***/ "7f09":
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_node_modules_cache_loader_dist_cjs_js_ref_1_0_node_modules_vue_loader_lib_index_js_vue_loader_options_SettingIntegrations_vue_vue_type_style_index_1_lang_css___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__("38ac");
/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_node_modules_cache_loader_dist_cjs_js_ref_1_0_node_modules_vue_loader_lib_index_js_vue_loader_options_SettingIntegrations_vue_vue_type_style_index_1_lang_css___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_node_modules_cache_loader_dist_cjs_js_ref_1_0_node_modules_vue_loader_lib_index_js_vue_loader_options_SettingIntegrations_vue_vue_type_style_index_1_lang_css___WEBPACK_IMPORTED_MODULE_0__);
/* unused harmony reexport * */


/***/ }),

/***/ "84ed":
/***/ (function(module, exports, __webpack_require__) {

// extracted by mini-css-extract-plugin

/***/ }),

/***/ "88a5":
/***/ (function(module, exports, __webpack_require__) {

// extracted by mini-css-extract-plugin

/***/ }),

/***/ "8c72":
/***/ (function(module, exports, __webpack_require__) {

// extracted by mini-css-extract-plugin

/***/ }),

/***/ "8d79":
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_node_modules_cache_loader_dist_cjs_js_ref_1_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ServiceInviteeQuestions_vue_vue_type_style_index_0_id_715d7140_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__("3833");
/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_node_modules_cache_loader_dist_cjs_js_ref_1_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ServiceInviteeQuestions_vue_vue_type_style_index_0_id_715d7140_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_node_modules_cache_loader_dist_cjs_js_ref_1_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ServiceInviteeQuestions_vue_vue_type_style_index_0_id_715d7140_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__);
/* unused harmony reexport * */


/***/ }),

/***/ "9195":
/***/ (function(module, exports, __webpack_require__) {

// extracted by mini-css-extract-plugin

/***/ }),

/***/ "94a4":
/***/ (function(module, exports, __webpack_require__) {

// extracted by mini-css-extract-plugin

/***/ }),

/***/ "a1e2":
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_node_modules_cache_loader_dist_cjs_js_ref_1_0_node_modules_vue_loader_lib_index_js_vue_loader_options_SettingAdmins_vue_vue_type_style_index_1_lang_css___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__("c937");
/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_node_modules_cache_loader_dist_cjs_js_ref_1_0_node_modules_vue_loader_lib_index_js_vue_loader_options_SettingAdmins_vue_vue_type_style_index_1_lang_css___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_node_modules_cache_loader_dist_cjs_js_ref_1_0_node_modules_vue_loader_lib_index_js_vue_loader_options_SettingAdmins_vue_vue_type_style_index_1_lang_css___WEBPACK_IMPORTED_MODULE_0__);
/* unused harmony reexport * */


/***/ }),

/***/ "a712":
/***/ (function(module, exports, __webpack_require__) {

// extracted by mini-css-extract-plugin

/***/ }),

/***/ "a817":
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_node_modules_cache_loader_dist_cjs_js_ref_1_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ServiceList_vue_vue_type_style_index_0_id_06620e8e_lang_css_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__("c8ed");
/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_node_modules_cache_loader_dist_cjs_js_ref_1_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ServiceList_vue_vue_type_style_index_0_id_06620e8e_lang_css_scoped_true___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_node_modules_cache_loader_dist_cjs_js_ref_1_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ServiceList_vue_vue_type_style_index_0_id_06620e8e_lang_css_scoped_true___WEBPACK_IMPORTED_MODULE_0__);
/* unused harmony reexport * */


/***/ }),

/***/ "ad9c":
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
// ESM COMPAT FLAG
__webpack_require__.r(__webpack_exports__);

// EXTERNAL MODULE: ./node_modules/core-js/modules/es.array.iterator.js
var es_array_iterator = __webpack_require__("e260");

// EXTERNAL MODULE: ./node_modules/core-js/modules/es.promise.js
var es_promise = __webpack_require__("e6cf");

// EXTERNAL MODULE: ./node_modules/core-js/modules/es.object.assign.js
var es_object_assign = __webpack_require__("cca6");

// EXTERNAL MODULE: ./node_modules/core-js/modules/es.promise.finally.js
var es_promise_finally = __webpack_require__("a79d");

// CONCATENATED MODULE: ./src/utils/client_end_admin.js
window.__wpcal_client_end = "admin";
/* harmony default export */ var client_end_admin = ({
  __dummy_cliend_end: "admin"
});
// EXTERNAL MODULE: ./node_modules/vue/dist/vue.runtime.esm.js
var vue_runtime_esm = __webpack_require__("2b0e");

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"1bf56cee-vue-loader-template"}!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/vue-loader/lib??vue-loader-options!./src/AdminApp.vue?vue&type=template&id=33102142&
var AdminAppvue_type_template_id_33102142_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',{style:(_vm.main_styles),attrs:{"id":"wpcal_admin_app"}},[_c('IconFillSVG'),(_vm.is_settings_loaded || !_vm.is_admin_end_access_granted)?_c('router-view'):_vm._e(),_c('OnBoardingCheckList'),_c('PricingCompareModal'),_c('v-dialog',{staticStyle:{"z-index":"10001"},on:{"before-closed":function($event){return _vm.event_bus.$emit('dialog-before-closed', $event)}}})],1)}
var staticRenderFns = []


// CONCATENATED MODULE: ./src/AdminApp.vue?vue&type=template&id=33102142&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"1bf56cee-vue-loader-template"}!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/vue-loader/lib??vue-loader-options!./src/components/admin/OnBoardingCheckList.vue?vue&type=template&id=7e87c989&scoped=true&
var OnBoardingCheckListvue_type_template_id_7e87c989_scoped_true_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('span',[(
      _vm.display_in == 'settings_calendars' &&
        _vm.is_show_todo_notice('add_calendar_account')
    )?_c('div',{staticClass:"ob-checklist-inline-cont"},[_vm._v(" You can, optionally, connect your Google Calendar account to sync your events. "),_c('a',{staticStyle:{"margin-left":"10px"},attrs:{"type":"button"},on:{"click":function($event){return _vm.dismiss_todo('add_calendar_account')}}},[_vm._v(" Dismiss ")])]):_vm._e(),(
      _vm.display_in == 'settings_integrations' &&
        _vm.is_show_todo_notice('add_meeting_app')
    )?_c('div',{staticClass:"ob-checklist-inline-cont"},[_vm._v(" Connect your meeting app accounts to use them as locations. When you invitees book with you, we'll email them the meeting links. "),_c('a',{staticStyle:{"margin-left":"10px"},attrs:{"type":"button"},on:{"click":function($event){return _vm.dismiss_todo('add_meeting_app')}}},[_vm._v(" Dismiss ")])]):_vm._e(),(
      _vm.display_in == 'settings_admins' &&
        _vm.is_show_todo_notice('add_wpcal_admin')
    )?_c('div',{staticClass:"ob-checklist-inline-cont"},[_vm._v(" Add your teammates to manage their own/others event types and bookings. "),_c('a',{staticStyle:{"margin-left":"10px"},attrs:{"type":"button"},on:{"click":function($event){return _vm.dismiss_todo('add_wpcal_admin')}}},[_vm._v(" Dismiss ")])]):_vm._e(),(_vm.display_in == 'admin_all' && _vm.is_show_onboarding_checklist)?_c('div',{staticClass:"ob-checklist-cont"},[_c('div',{staticClass:"ob-checklist-close-cont"},[_c('button',{on:{"click":_vm.show_dismiss_confirm}})]),_c('div',{staticClass:"hdr"},[_c('div',{staticClass:"ln1"},[_vm._v(_vm._s(_vm.T__("Let's get you started...")))])]),_c('ul',[_c('li',{class:[_vm.todo_class('create_or_edit_service')]},[_c('router-link',{attrs:{"to":"/event-type/add"}},[_vm._v(_vm._s(_vm.T__("Create")))]),_vm._v(" or "),_c('router-link',{attrs:{"to":"/event-types"}},[_vm._v(_vm._s(_vm.T__("Edit")))]),_vm._v(" an Event Type ")],1),_c('li',{class:[_vm.todo_class('add_calendar_account')]},[_c('router-link',{attrs:{"to":"/settings/calendars"}},[_vm._v(_vm._s(_vm.T__("Connect calendar account"))+" ")])],1),_c('li',{class:[_vm.todo_class('add_meeting_app')]},[_c('router-link',{attrs:{"to":"/settings/integrations"}},[_vm._v(_vm._s(_vm.T__("Connect meeting app account"))+" ")])],1),_c('li',{class:[_vm.todo_class('test_booking')]},[_c('span',{staticClass:"pseudo-a",on:{"click":_vm.show_test_booking_steps}},[_vm._v(_vm._s(_vm.T__("Test a booking")))])]),_c('li',{class:[_vm.todo_class('add_wpcal_admin')]},[_c('router-link',{attrs:{"to":"/settings/admins"}},[_vm._v(_vm._s(_vm.T__("Add your teammates")))])],1)])]):_vm._e(),(_vm.display_in == 'admin_all' && _vm.is_show_onboarding_checklist)?_c('div',{staticStyle:{"height":"250px"}}):_vm._e()])}
var OnBoardingCheckListvue_type_template_id_7e87c989_scoped_true_staticRenderFns = []


// CONCATENATED MODULE: ./src/components/admin/OnBoardingCheckList.vue?vue&type=template&id=7e87c989&scoped=true&

// EXTERNAL MODULE: ./node_modules/core-js/modules/es.function.name.js
var es_function_name = __webpack_require__("b0c0");

// EXTERNAL MODULE: ./node_modules/axios/index.js
var axios = __webpack_require__("bc3a");
var axios_default = /*#__PURE__*/__webpack_require__.n(axios);

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--13-0!./node_modules/thread-loader/dist/cjs.js!./node_modules/babel-loader/lib!./node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/vue-loader/lib??vue-loader-options!./src/components/admin/OnBoardingCheckList.vue?vue&type=script&lang=js&

//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

/* harmony default export */ var OnBoardingCheckListvue_type_script_lang_js_ = ({
  name: "OnBoardingCheckList",
  props: {
    display_in: {
      type: String,
      default: "admin_all"
    }
  },
  computed: {
    is_show_onboarding_checklist: function is_show_onboarding_checklist() {
      var _this$onboarding_chec, _this$onboarding_chec2;

      if (this.$store.getters.get_license_info === null) {
        //propably not data not loaded
        return false;
      }

      if (this._isEmpty(this.$store.getters.get_license_info)) {
        //initial setup not done, another page gonna ask admin for login
        return false;
      }

      if (!this.onboarding_checklist_notice) {
        return false;
      }

      if ((_this$onboarding_chec = this.onboarding_checklist_notice) !== null && _this$onboarding_chec !== void 0 && _this$onboarding_chec.status && (this.onboarding_checklist_notice.status == "dismissed" || ((_this$onboarding_chec2 = this.onboarding_checklist_details) === null || _this$onboarding_chec2 === void 0 ? void 0 : _this$onboarding_chec2.status) == "completed")) {
        return false;
      }

      return true;
    },
    onboarding_checklist_notice: function onboarding_checklist_notice() {
      var current_admin_notices = this.$store.getters.get_current_admin_notices;

      if (current_admin_notices == null) {
        //not yet loaded
        return false;
      }

      if (current_admin_notices !== null && current_admin_notices !== void 0 && current_admin_notices.onboarding_checklist) {
        return current_admin_notices === null || current_admin_notices === void 0 ? void 0 : current_admin_notices.onboarding_checklist;
      }

      return false;
    },
    onboarding_checklist_details: function onboarding_checklist_details() {
      var _this$onboarding_chec3;

      var onboarding_checklist_details = (_this$onboarding_chec3 = this.onboarding_checklist_notice) === null || _this$onboarding_chec3 === void 0 ? void 0 : _this$onboarding_chec3.details;
      return onboarding_checklist_details;
    }
  },
  methods: {
    todo_class: function todo_class(todo) {
      var _this$onboarding_chec4, _this$onboarding_chec5;

      if (((_this$onboarding_chec4 = this.onboarding_checklist_details) === null || _this$onboarding_chec4 === void 0 ? void 0 : _this$onboarding_chec4[todo]) == "completed" || ((_this$onboarding_chec5 = this.onboarding_checklist_details) === null || _this$onboarding_chec5 === void 0 ? void 0 : _this$onboarding_chec5[todo]) == "dismissed") {
        return "completed";
      } else if (!this.onboarding_checklist_details.hasOwnProperty(todo)) {
        return "hide";
      }

      return "";
    },
    is_show_todo_notice: function is_show_todo_notice(todo) {
      var _this$onboarding_chec6, _this$onboarding_chec7, _this$onboarding_chec8;

      if (!this.is_show_onboarding_checklist) {
        return false;
      }

      if (!((_this$onboarding_chec6 = this.onboarding_checklist_details) !== null && _this$onboarding_chec6 !== void 0 && _this$onboarding_chec6[todo])) {
        return false;
      }

      if (((_this$onboarding_chec7 = this.onboarding_checklist_details) === null || _this$onboarding_chec7 === void 0 ? void 0 : _this$onboarding_chec7[todo]) == "completed" || ((_this$onboarding_chec8 = this.onboarding_checklist_details) === null || _this$onboarding_chec8 === void 0 ? void 0 : _this$onboarding_chec8[todo]) == "dismissed") {
        return false;
      }

      return true;
    },
    dismiss_todo: function dismiss_todo(todo) {
      var _this = this;

      var wpcal_request = {};
      var action = "update_admin_notices";
      var onboarding_checklist_notice = this.clone_deep(this.onboarding_checklist_notice);
      onboarding_checklist_notice.details[todo] = "dismissed";
      wpcal_request[action] = {
        onboarding_checklist: onboarding_checklist_notice
      };
      var action2 = "get_admin_notices";
      wpcal_request[action2] = "dummy__";
      var post_data = {
        action: "wpcal_process_admin_ajax_request",
        wpcal_request: wpcal_request
      };
      axios_default.a.post(window.wpcal_ajax.ajax_url, post_data).then(function (response) {
        _this.notify_single_action_result(response.data[action], {
          success_msg: _this.T__("Successfully dismissed.")
        });

        if (response.data[action2].status === "success" && response.data[action2].hasOwnProperty("current_admin_notices")) {
          _this.$store.commit("set_store_by_obj", {
            current_admin_notices: response.data[action2].current_admin_notices
          });
        }
      }).catch(function (error) {
        console.log(error);
      });
    },
    dismiss_onboarding: function dismiss_onboarding() {
      var _this2 = this;

      var wpcal_request = {};
      var action = "update_admin_notices";
      var onboarding_checklist_notice = this.clone_deep(this.onboarding_checklist_notice);
      onboarding_checklist_notice.status = "dismissed";
      wpcal_request[action] = {
        onboarding_checklist: onboarding_checklist_notice
      };
      var action2 = "get_admin_notices";
      wpcal_request[action2] = "dummy__";
      var post_data = {
        action: "wpcal_process_admin_ajax_request",
        wpcal_request: wpcal_request
      };
      axios_default.a.post(window.wpcal_ajax.ajax_url, post_data).then(function (response) {
        _this2.notify_single_action_result(response.data[action], {
          success_msg: _this2.T__("Successfully dismissed.")
        });

        if (response.data[action2].status === "success" && response.data[action2].hasOwnProperty("current_admin_notices")) {
          _this2.$store.commit("set_store_by_obj", {
            current_admin_notices: response.data[action2].current_admin_notices
          });
        }
      }).catch(function (error) {
        console.log(error);
      });
    },
    update_notices_silently: function update_notices_silently() {
      var _this3 = this;

      var wpcal_request = {};
      var action = "get_admin_notices";
      wpcal_request[action] = "dummy__";
      var post_data = {
        action: "wpcal_process_admin_ajax_request",
        wpcal_request: wpcal_request
      };
      axios_default.a.post(window.wpcal_ajax.ajax_url, post_data, {
        params: {
          _remove_options_before_call: {
            background_call: true
          }
        }
      }).then(function (response) {
        if (response.data[action].status === "success" && response.data[action].hasOwnProperty("current_admin_notices")) {
          _this3.$store.commit("set_store_by_obj", {
            current_admin_notices: response.data[action].current_admin_notices
          });
        }
      }).catch(function (error) {
        console.log(error);
      });
    },
    check_and_may_update_notices: function check_and_may_update_notices(todo) {
      if (this.is_show_todo_notice(todo)) {
        this.update_notices_silently();
      }
    },
    show_dismiss_confirm: function show_dismiss_confirm() {
      var _this4 = this;

      this.$modal.show("dialog", {
        title: this.T__("Do you want to dismiss get you started list?"),
        text: "",
        buttons: [{
          title: this.T__("Yes, Dismiss."),
          class: "vue-dialog-button",
          handler: function handler() {
            _this4.dismiss_onboarding();

            _this4.$modal.hide("dialog");
          }
        }, {
          title: "No, don't.",
          handler: function handler() {
            _this4.$modal.hide("dialog");
          }
        }]
      });
    },
    show_test_booking_steps: function show_test_booking_steps() {
      var _this5 = this;

      this.$modal.show("dialog", {
        title: this.T__("Do a test booking?"),
        text: '<ol> <li>Choose a desired event type or create one.</li> <li>Click on "View Booking Page" of desired event type.</li> <li>Complete the booking steps by choosing the date & time and filling out the form.</li> <li>You can find the booking in the admin end WPCal &#62; Bookings section.</li> </ol>',
        buttons: [{
          title: this.T__("Okay"),
          class: "vue-dialog-button",
          handler: function handler() {
            if (_this5.$route.name !== "service_list") {
              _this5.$router.push("/event-types");
            }

            _this5.$modal.hide("dialog");
          },
          default: true // Will be triggered by default if 'Enter' pressed.

        }]
      });
    }
  },
  mounted: function mounted() {
    var _this6 = this;

    this.event_bus.$on("may_update_onboarding_checklist_notice", function (todo) {
      _this6.check_and_may_update_notices(todo);
    });
  }
});
// CONCATENATED MODULE: ./src/components/admin/OnBoardingCheckList.vue?vue&type=script&lang=js&
 /* harmony default export */ var admin_OnBoardingCheckListvue_type_script_lang_js_ = (OnBoardingCheckListvue_type_script_lang_js_); 
// EXTERNAL MODULE: ./src/components/admin/OnBoardingCheckList.vue?vue&type=style&index=0&id=7e87c989&scoped=true&lang=css&
var OnBoardingCheckListvue_type_style_index_0_id_7e87c989_scoped_true_lang_css_ = __webpack_require__("3133");

// EXTERNAL MODULE: ./node_modules/vue-loader/lib/runtime/componentNormalizer.js
var componentNormalizer = __webpack_require__("2877");

// CONCATENATED MODULE: ./src/components/admin/OnBoardingCheckList.vue






/* normalize component */

var OnBoardingCheckList_component = Object(componentNormalizer["a" /* default */])(
  admin_OnBoardingCheckListvue_type_script_lang_js_,
  OnBoardingCheckListvue_type_template_id_7e87c989_scoped_true_render,
  OnBoardingCheckListvue_type_template_id_7e87c989_scoped_true_staticRenderFns,
  false,
  null,
  "7e87c989",
  null
  
)

/* harmony default export */ var OnBoardingCheckList = (OnBoardingCheckList_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"1bf56cee-vue-loader-template"}!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/vue-loader/lib??vue-loader-options!./src/components/admin/PricingCompareModal.vue?vue&type=template&id=afb3b3a6&
var PricingCompareModalvue_type_template_id_afb3b3a6_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',[_c('modal',{staticStyle:{"z-index":"9999"},attrs:{"name":"pricing_compare_modal","width":'700px',"height":"auto","scrollable":true,"adaptive":true}},[_c('div',{staticClass:"modal_close_cont"},[_c('button',{on:{"click":function($event){_vm.$modal.hide('pricing_compare_modal');
          this.highlight_feature = '';}}})]),_c('PricingStrongerTogether',{attrs:{"highlight_feature":_vm.highlight_feature}})],1)],1)}
var PricingCompareModalvue_type_template_id_afb3b3a6_staticRenderFns = []


// CONCATENATED MODULE: ./src/components/admin/PricingCompareModal.vue?vue&type=template&id=afb3b3a6&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"1bf56cee-vue-loader-template"}!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/vue-loader/lib??vue-loader-options!./src/components/admin/PricingStrongerTogether.vue?vue&type=template&id=69d67168&scoped=true&
var PricingStrongerTogethervue_type_template_id_69d67168_scoped_true_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',{staticClass:"covid-consent-cont-table-plus"},[_vm._m(0),_c('table',[_vm._m(1),_vm._m(2),_c('tr',{class:{ highlight: _vm.highlight_feature == 'services' }},[_c('td',[_vm._v("Number of active Event Types")]),_c('td',[_vm._v("1 active event type")]),_c('td',[_vm._v("Unlimited")]),_c('td',[_vm._v("Unlimited")])]),_vm._m(3),_c('tr',{class:{ highlight: _vm.highlight_feature == 'wpcal_admins' }},[_c('td',[_vm._v("Number of WP admins who can create an event type")]),_c('td',[_vm._v("1 admin per site")]),_c('td',[_vm._v("Upto 10 admins per site")]),_c('td',[_vm._v("Unlimited")])]),_c('tr',{class:{ highlight: _vm.highlight_feature == 'calendars' }},[_c('td',[_vm._v("Number of connected calendar accounts")]),_c('td',[_vm._v("1 calendar account per admin")]),_c('td',[_vm._v("Unlimited calendar accounts per admin")]),_c('td',[_vm._v("Unlimited calendar accounts per admin")])]),_vm._m(4),_vm._m(5),_c('tr',{class:{ highlight: _vm.highlight_feature == 'questions' }},[_c('td',[_vm._v("Additional questions")]),_c('td',[_vm._v("1")]),_c('td',[_vm._v(" Unlimited ")]),_c('td',[_vm._v(" Unlimited ")])]),_c('tr',{class:{ highlight: _vm.highlight_feature == 'email_features' }},[_c('td',[_vm._v(" Send email notifications from your own server ")]),_c('td'),_vm._m(6),_vm._m(7)]),_c('tr',{class:{ highlight: _vm.highlight_feature == 'email_features' }},[_c('td',[_vm._v(" Email customization in PHP ")]),_c('td'),_vm._m(8),_vm._m(9)]),_c('tr',{class:{ highlight: _vm.highlight_feature == 'branding_customization' }},[_c('td',[_vm._v(" Brand customization ")]),_c('td'),_c('td',[_vm._v(" Pro only ")]),_vm._m(10)])]),_vm._m(11)])}
var PricingStrongerTogethervue_type_template_id_69d67168_scoped_true_staticRenderFns = [function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',{staticStyle:{"text-align":"center","display":"flex","width":"100%"}},[_c('div',{staticStyle:{"padding-right":"20px","padding-top":"10px"}},[_vm._v("🚨")]),_c('div',{staticStyle:{"flex":"1"}},[_vm._v(" To help you schedule meetings remotely, we are offering "),_c('strong',[_vm._v("all paid features 100% FREE")]),_vm._v(" exclusively for use during the COVID-19 crisis. ")]),_c('div',{staticStyle:{"padding-left":"20px","padding-top":"10px"}},[_vm._v("🚨")])])},function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('tr',[_c('td'),_c('td',[_vm._v("Free forever")]),_c('td',[_vm._v("Plus & Pro plans")]),_c('td',[_vm._v(" Stronger, Together! Plan "),_c('div',{staticStyle:{"background-color":"#fff200","font-size":"11px","font-weight":"normal","margin-top":"5px"}},[_vm._v(" All paid features 100% Free during Covid-19 crisis ")])])])},function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('tr',[_c('td',[_vm._v("Number of websites you can use the plugin on")]),_c('td',[_vm._v("Unlimited")]),_c('td',[_vm._v("Upto 5 sites")]),_c('td',[_vm._v("Unlimited")])])},function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('tr',[_c('td',[_vm._v("Unlimited event scheduling by invitees")]),_c('td',[_c('span',{staticClass:"check"})]),_c('td',[_c('span',{staticClass:"check"})]),_c('td',[_c('span',{staticClass:"check"})])])},function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('tr',{attrs:{"x":""}},[_c('td',[_vm._v("Google Calendar integration")]),_c('td',[_c('span',{staticClass:"check"})]),_c('td',[_c('span',{staticClass:"check"})]),_c('td',[_c('span',{staticClass:"check"})])])},function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('tr',[_c('td',[_vm._v("Zoom, GoToMeeting, Google Meet integrations")]),_c('td',[_c('span',{staticClass:"check"})]),_c('td',[_c('span',{staticClass:"check"})]),_c('td',[_c('span',{staticClass:"check"})])])},function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('td',[_c('span',{staticClass:"check"})])},function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('td',[_c('span',{staticClass:"check"})])},function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('td',[_c('span',{staticClass:"check"})])},function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('td',[_c('span',{staticClass:"check"})])},function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('td',[_c('span',{staticClass:"check"})])},function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',{staticStyle:{"text-align":"right"}},[_c('a',{attrs:{"href":"https://wpcal.io/?utm_source=wpcal_plugin&utm_medium=stronger_together_dialog#pricing","target":"_blank"}},[_vm._v("See Pricing for more Premium features ↗")])])}]


// CONCATENATED MODULE: ./src/components/admin/PricingStrongerTogether.vue?vue&type=template&id=69d67168&scoped=true&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--13-0!./node_modules/thread-loader/dist/cjs.js!./node_modules/babel-loader/lib!./node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/vue-loader/lib??vue-loader-options!./src/components/admin/PricingStrongerTogether.vue?vue&type=script&lang=js&
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
/* harmony default export */ var PricingStrongerTogethervue_type_script_lang_js_ = ({
  props: {
    highlight_feature: {
      type: String,
      default: ""
    }
  }
});
// CONCATENATED MODULE: ./src/components/admin/PricingStrongerTogether.vue?vue&type=script&lang=js&
 /* harmony default export */ var admin_PricingStrongerTogethervue_type_script_lang_js_ = (PricingStrongerTogethervue_type_script_lang_js_); 
// EXTERNAL MODULE: ./src/components/admin/PricingStrongerTogether.vue?vue&type=style&index=0&id=69d67168&scoped=true&lang=css&
var PricingStrongerTogethervue_type_style_index_0_id_69d67168_scoped_true_lang_css_ = __webpack_require__("361c");

// CONCATENATED MODULE: ./src/components/admin/PricingStrongerTogether.vue






/* normalize component */

var PricingStrongerTogether_component = Object(componentNormalizer["a" /* default */])(
  admin_PricingStrongerTogethervue_type_script_lang_js_,
  PricingStrongerTogethervue_type_template_id_69d67168_scoped_true_render,
  PricingStrongerTogethervue_type_template_id_69d67168_scoped_true_staticRenderFns,
  false,
  null,
  "69d67168",
  null
  
)

/* harmony default export */ var PricingStrongerTogether = (PricingStrongerTogether_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--13-0!./node_modules/thread-loader/dist/cjs.js!./node_modules/babel-loader/lib!./node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/vue-loader/lib??vue-loader-options!./src/components/admin/PricingCompareModal.vue?vue&type=script&lang=js&
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

/* harmony default export */ var PricingCompareModalvue_type_script_lang_js_ = ({
  components: {
    PricingStrongerTogether: PricingStrongerTogether
  },
  data: function data() {
    return {
      highlight_feature: ""
    };
  },
  mounted: function mounted() {
    var _this = this;

    this.event_bus.$on("show-pricing-compare-modal", function (payload) {
      _this.highlight_feature = payload;

      _this.$modal.show("pricing_compare_modal");
    });
  }
});
// CONCATENATED MODULE: ./src/components/admin/PricingCompareModal.vue?vue&type=script&lang=js&
 /* harmony default export */ var admin_PricingCompareModalvue_type_script_lang_js_ = (PricingCompareModalvue_type_script_lang_js_); 
// CONCATENATED MODULE: ./src/components/admin/PricingCompareModal.vue





/* normalize component */

var PricingCompareModal_component = Object(componentNormalizer["a" /* default */])(
  admin_PricingCompareModalvue_type_script_lang_js_,
  PricingCompareModalvue_type_template_id_afb3b3a6_render,
  PricingCompareModalvue_type_template_id_afb3b3a6_staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* harmony default export */ var PricingCompareModal = (PricingCompareModal_component.exports);
// EXTERNAL MODULE: ./src/assets/images/IconFillSVG.vue + 2 modules
var IconFillSVG = __webpack_require__("93fb");

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--13-0!./node_modules/thread-loader/dist/cjs.js!./node_modules/babel-loader/lib!./node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/vue-loader/lib??vue-loader-options!./src/AdminApp.vue?vue&type=script&lang=js&
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//



/* harmony default export */ var AdminAppvue_type_script_lang_js_ = ({
  components: {
    OnBoardingCheckList: OnBoardingCheckList,
    PricingCompareModal: PricingCompareModal,
    IconFillSVG: IconFillSVG["a" /* default */]
  },
  computed: {
    is_settings_loaded: function is_settings_loaded() {
      return !this._isEmpty(this.$store.getters.get_general_settings);
    },
    is_admin_end_access_granted: function is_admin_end_access_granted() {
      return this.$store.getters.get_is_admin_end_access_granted;
    },
    main_styles: function main_styles() {
      var styles = {};
      var general_setting = this.$store.getters.get_general_settings;

      if (general_setting.hasOwnProperty("hide_premium_info_badges") && general_setting.hide_premium_info_badges == "0") {
        styles["--hidePremiumInfoBadge"] = "block";
      }

      return styles;
    }
  },
  methods: {
    check_and_handle_license_info: function check_and_handle_license_info() {
      if (this.$store.getters.get_license_info === null) {
        //propably not data not loaded
        return;
      }

      if (this._isEmpty(this.$store.getters.get_license_info)) {
        //initial setup not done, ask admin for login
        this.$router.push("/settings/account/login");
      }
    }
  },
  watch: {
    "$store.getters.get_license_info": function $storeGettersGet_license_info() {
      this.check_and_handle_license_info();
    },
    is_admin_end_access_granted: function is_admin_end_access_granted(val) {
      if (!val) {
        this.$router.push("/non_wpcal_admin");
      }
    }
  },
  created: function created() {
    this.$store.dispatch("init");
  }
});
// CONCATENATED MODULE: ./src/AdminApp.vue?vue&type=script&lang=js&
 /* harmony default export */ var src_AdminAppvue_type_script_lang_js_ = (AdminAppvue_type_script_lang_js_); 
// EXTERNAL MODULE: ./src/AdminApp.vue?vue&type=style&index=0&lang=scss&
var AdminAppvue_type_style_index_0_lang_scss_ = __webpack_require__("eec2");

// EXTERNAL MODULE: ./src/assets/fonts/wf-stylesheet.css?vue&type=style&index=1&lang=css&
var wf_stylesheetvue_type_style_index_1_lang_css_ = __webpack_require__("07f5");

// EXTERNAL MODULE: ./src/assets/css/admin_app.css?vue&type=style&index=2&lang=css&
var admin_appvue_type_style_index_2_lang_css_ = __webpack_require__("4fc3");

// CONCATENATED MODULE: ./src/AdminApp.vue








/* normalize component */

var AdminApp_component = Object(componentNormalizer["a" /* default */])(
  src_AdminAppvue_type_script_lang_js_,
  AdminAppvue_type_template_id_33102142_render,
  staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* harmony default export */ var AdminApp = (AdminApp_component.exports);
// EXTERNAL MODULE: ./node_modules/@babel/runtime/helpers/esm/asyncToGenerator.js
var asyncToGenerator = __webpack_require__("1da1");

// EXTERNAL MODULE: ./node_modules/@babel/runtime/helpers/esm/createForOfIteratorHelper.js
var createForOfIteratorHelper = __webpack_require__("b85c");

// EXTERNAL MODULE: ./node_modules/regenerator-runtime/runtime.js
var runtime = __webpack_require__("96cf");

// EXTERNAL MODULE: ./node_modules/core-js/modules/es.regexp.exec.js
var es_regexp_exec = __webpack_require__("ac1f");

// EXTERNAL MODULE: ./node_modules/core-js/modules/es.string.split.js
var es_string_split = __webpack_require__("1276");

// EXTERNAL MODULE: ./node_modules/vue-router/dist/vue-router.esm.js
var vue_router_esm = __webpack_require__("8c4f");

// EXTERNAL MODULE: ./node_modules/core-js/modules/es.array.find.js
var es_array_find = __webpack_require__("7db0");

// EXTERNAL MODULE: ./node_modules/core-js/modules/es.object.to-string.js
var es_object_to_string = __webpack_require__("d3b7");

// EXTERNAL MODULE: ./node_modules/vuex/dist/vuex.esm.js
var vuex_esm = __webpack_require__("2f62");

// EXTERNAL MODULE: ./node_modules/lodash-es/cloneDeep.js + 29 modules
var cloneDeep = __webpack_require__("5c8a");

// EXTERNAL MODULE: ./src/store/common_module.js
var common_module = __webpack_require__("cdab");

// CONCATENATED MODULE: ./src/store/admin.js






vue_runtime_esm["default"].use(vuex_esm["a" /* default */]);

/* harmony default export */ var admin = (new vuex_esm["a" /* default */].Store({
  strict: true,
  modules: {
    common: common_module["a" /* default */]
  },
  state: {
    client_end: "admin",
    store: {
      license_info: null,
      current_admin_details: {},
      current_admin_notices: null,
      managed_active_admins_details: [],
      // futuristic - current wpcal admin type is administrator only
      wpcal_site_urls: {},
      is_admin_end_access_granted: true
    }
  },
  mutations: {
    set_license_info: function set_license_info(state, value) {
      state.store.license_info = value;
    },
    set_managed_active_admins_details: function set_managed_active_admins_details(state, value) {
      state.store.managed_active_admins_details = value;
    },
    set_store_by_obj: function set_store_by_obj(state, value_obj) {
      for (var prop in value_obj) {
        if (state.store.hasOwnProperty(prop)) {
          state.store[prop] = value_obj[prop];
        }
      }
    }
  },
  getters: {
    // get_client: state => state.client,
    // get_store: state => state.store,
    get_license_info: function get_license_info(state) {
      return state.store.license_info;
    },
    get_current_admin_details: function get_current_admin_details(state) {
      return state.store.current_admin_details;
    },
    get_current_admin_notices: function get_current_admin_notices(state) {
      return state.store.current_admin_notices;
    },
    get_managed_active_admins_details: function get_managed_active_admins_details(state) {
      return state.store.managed_active_admins_details;
    },
    get_managed_active_admins_for_host_filter: function get_managed_active_admins_for_host_filter(state) {
      var admins = Object(cloneDeep["a" /* default */])(state.store.managed_active_admins_details);

      if (Array.isArray(admins)) {
        var all_admin_as_admin = {
          admin_user_id: "0",
          admin_type: "administrator",
          status: "1",
          name: "All",
          firstname: "",
          display_name: "All",
          user_email: "All"
        };
        admins.unshift(all_admin_as_admin);
      }

      return admins;
    },
    get_admin_details: function get_admin_details(state) {
      return function (admin_user_id) {
        var admin_details = {};
        var admins = Object(cloneDeep["a" /* default */])(state.store.managed_active_admins_details);
        var found_admin_details = admins.find(function (_admin_details) {
          return _admin_details.admin_user_id == admin_user_id;
        });
        return found_admin_details ? found_admin_details : admin_details;
      };
    },
    get_wpcal_site_urls: function get_wpcal_site_urls(state) {
      return state.store.wpcal_site_urls;
    },
    get_is_admin_end_access_granted: function get_is_admin_end_access_granted(state) {
      return state.store.is_admin_end_access_granted;
    }
  },
  actions: {
    load_managed_active_admins_details: function load_managed_active_admins_details(context) {
      var wpcal_request = {};
      var action_managed_active_admins_details = "get_managed_active_admins_details_for_current_admin";
      wpcal_request[action_managed_active_admins_details] = "dummy__";
      var post_data = {
        action: context.getters.get_ajax_main_action,
        wpcal_request: wpcal_request
      };
      axios_default.a.post(window.wpcal_ajax.ajax_url, post_data, {
        params: {
          _remove_options_before_call: {
            background_call: true
          }
        }
      }).then(function (response) {
        var _response$data$action;

        if (response.data[action_managed_active_admins_details].status === "success" && (_response$data$action = response.data[action_managed_active_admins_details]) !== null && _response$data$action !== void 0 && _response$data$action.managed_active_admins_details) {
          context.commit("set_managed_active_admins_details", response.data[action_managed_active_admins_details].managed_active_admins_details);
        }
      }).catch(function (error) {
        console.log(error);
      });
    }
  }
}));
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"1bf56cee-vue-loader-template"}!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/vue-loader/lib??vue-loader-options!./src/views/admin/ServiceList.vue?vue&type=template&id=06620e8e&scoped=true&
var ServiceListvue_type_template_id_06620e8e_scoped_true_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',[_c('div',[_c('div',{staticClass:"page-title-cont"},[_c('h1',{staticClass:"wp-heading-inline"},[_vm._v("WPCal.io - "+_vm._s(_vm.T__("Event Types")))]),_c('router-link',{staticClass:"page-title-action",attrs:{"to":"/event-type/add"}},[_vm._v(_vm._s(_vm.T__("+ Create New Event Type")))]),_c('PremiumInfoBadge',{staticStyle:{"margin":"-6px 0 0 10px"},attrs:{"highlight_feature":"services"}},[_c('em',[_vm._v("Free: 1 active event type"),_c('br'),_vm._v("Plus: Unlimited")])]),_c('AdminHeaderRight')],1),_c('div',{staticStyle:{"display":"flex"}},[_c('div',[_c('div',{},[_c('div',{staticClass:"validation_errors_cont"}),_c('div',{staticClass:"et-filter-cont"},[_c('div',{staticStyle:{"color":"#7c7d9c","font-size":"11px","align-self":"center","padding":"0 7px"}},[_vm._v(" FILTER BY ")]),_c('ul',{staticClass:"filters-bar"},[_c('li',{staticClass:"bf-host"},[_c('span',{staticClass:"bf-label",staticStyle:{"margin-right":"-35px"}},[_vm._v("Host")]),(
                    !_vm._isEmpty(
                      _vm.$store.getters.get_managed_active_admins_for_host_filter
                    )
                  )?_c('TypeToSelect',{staticClass:"no-border",attrs:{"options":_vm.$store.getters.get_managed_active_admins_for_host_filter,"label_field":"name","value_field":"admin_user_id"},on:{"changed-form-selected":function($event){return _vm.load_page()}},model:{value:(_vm.filter_options.filter_by_admin_id),callback:function ($$v) {_vm.$set(_vm.filter_options, "filter_by_admin_id", $$v)},expression:"filter_options.filter_by_admin_id"}}):_vm._e()],1)])])]),_c('div',{staticClass:"service-list-cont"},[(
              _vm._isEmpty(_vm.grouped_service_list) && _vm.first_time_service_list_loaded
            )?_c('div',{staticClass:"mbox",staticStyle:{"margin-left":"10px","display":"inline"}},[_vm._v(" There are no event types available. "),_c('router-link',{attrs:{"to":"/event-type/add"}},[_vm._v("Create a new event type")]),_vm._v(" now. ")],1):_vm._e(),_vm._l((_vm.grouped_service_list),function(admin_details,admin_user_id){return _c('div',{key:admin_user_id,staticClass:"service-list-group"},[_c('div',{staticClass:"service-list-group-admin-profile"},[(
                  _vm.$store.getters.get_admin_details(
                    admin_details.admin_user_id
                  ).profile_picture
                )?_c('img',{staticStyle:{"border-radius":"50%"},attrs:{"src":_vm.$store.getters.get_admin_details(
                    admin_details.admin_user_id
                  ).profile_picture,"width":"30","title":_vm.$store.getters.get_admin_details(
                    admin_details.admin_user_id
                  ).display_name}}):_vm._e(),_vm._v(" "+_vm._s(admin_details.admin_user_id != 0 ? _vm.$store.getters.get_admin_details( admin_details.admin_user_id ).name : "Other")+" ")]),_c('div',{staticClass:"service-list-singles-cont"},_vm._l((admin_details.services),function(service,index){
                  var _obj;
return _c('div',{key:index,staticClass:"service-list-single",class:( _obj = {}, _obj['clr-' + service.color] = true, _obj.inactive = service.status == -1, _obj.private = service.is_manage_private == 1, _obj['del-alert'] =  _vm.show_delete_highlight(
                    'delete_highlight_service_id',
                    service.id
                  ), _obj )},[_c('h4',[_c('a',{attrs:{"href":'#/event-type/edit/' + service.id}},[(service.is_manage_private == 1)?_c('i'):_vm._e(),_vm._v(_vm._s(service.name))])]),_c('div',{staticClass:"et-edit-link"},[_c('a',{attrs:{"href":'#/event-type/edit/' + service.id}},[_vm._v(_vm._s(_vm.T__("EDIT")))])]),_c('OverflowMenu',{attrs:{"menu_id":service.id},model:{value:(_vm.service_overflow_menu_details),callback:function ($$v) {_vm.service_overflow_menu_details=$$v},expression:"service_overflow_menu_details"}},[_c('a',{staticClass:"menu-item del-hl",staticStyle:{"color":"rgb(232, 70, 83)"},on:{"click":function($event){return _vm.may_show_service_status_change_dialog(-2, service)},"mouseover":function($event){return _vm.set_mouseover_on_delete(
                        'delete_highlight_service_id',
                        service.id,
                        true
                      )},"mouseleave":function($event){return _vm.set_mouseover_on_delete(
                        'delete_highlight_service_id',
                        service.id,
                        false
                      )}}},[_vm._v(_vm._s(_vm.T__("Delete this Event Type")))]),_c('div',{staticClass:"menu-item",staticStyle:{"display":"flex !important"}},[_c('span',{staticStyle:{"color":"#7c7d9c !important","font-size":"13px"}},[_vm._v(_vm._s(_vm.T__("On/Off")))]),(
                        _vm.service_overflow_menu_details.open_menu_id ==
                          service.id &&
                          (service.status == 1 || service.status == -1)
                      )?_c('OnOffSlider',{staticStyle:{"margin-left":"auto"},attrs:{"name":service.id,"true_value":"1","false_value":"-1"},on:{"prop-slider-value-changed":function($event){return _vm.may_show_service_status_change_dialog($event, service)}},model:{value:(service.status),callback:function ($$v) {_vm.$set(service, "status", $$v)},expression:"service.status"}}):_vm._e()],1)]),_c('div',{staticClass:"et-meta"},[_vm._v(" "+_vm._s(_vm._f("mins_to_str")(service.duration))+", "+_vm._s(_vm._f("relationship_type_str")(service.relationship_type))+" ")]),(service.post_details.link)?_c('div',{staticStyle:{"display":"flex","margin-top":"auto"}},[_c('a',{staticClass:"view-booking-page",attrs:{"href":service.post_details.link,"target":"_blank"}},[_vm._v(_vm._s(_vm.T__("View Booking Page")))]),_c('div',{staticStyle:{"margin-left":"auto"}},[(
                        _vm.$store.getters.get_admin_details(
                          service.admin_user_id
                        ).profile_picture
                      )?_c('img',{staticStyle:{"border-radius":"50%"},attrs:{"src":_vm.$store.getters.get_admin_details(
                          service.admin_user_id
                        ).profile_picture,"width":"30","title":_vm.$store.getters.get_admin_details(
                          service.admin_user_id
                        ).display_name}}):_vm._e()])]):_vm._e()],1)}),0)])})],2)]),_vm._m(0)])]),(_vm.sample_real_service_details && _vm.sample_real_service_details.name)?_c('AlertAdminToUpdateProfile',{attrs:{"service_details":_vm.sample_real_service_details}}):_vm._e()],1)}
var ServiceListvue_type_template_id_06620e8e_scoped_true_staticRenderFns = [function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',{staticClass:"help-column"},[_c('div',[_c('div',[_vm._v("Help docs")]),_c('ul',[_c('li',[_c('a',{attrs:{"href":"https://help.wpcal.io/en/article/where-to-find-the-event-type-booking-widget-shortcode-and-how-to-use-it-1fuyala/?utm_source=wpcal_plugin&utm_medium=admin_header","target":"_blank"}},[_vm._v("How to integrate booking widget with front end?")])]),_c('li',[_c('a',{attrs:{"href":"https://help.wpcal.io/en/article/how-to-set-different-sizes-for-the-booking-widget-1cf6s2v/?utm_source=wpcal_plugin&utm_medium=admin_header","target":"_blank"}},[_vm._v("How to set different sizes of booking widget?")])]),_c('li',[_c('a',{attrs:{"href":"https://help.wpcal.io/?utm_source=wpcal_plugin&utm_medium=admin_et_list","target":"_blank"}},[_vm._v("More help docs.")])])])])])}]


// CONCATENATED MODULE: ./src/views/admin/ServiceList.vue?vue&type=template&id=06620e8e&scoped=true&

// EXTERNAL MODULE: ./node_modules/core-js/modules/es.array.concat.js
var es_array_concat = __webpack_require__("99af");

// EXTERNAL MODULE: ./node_modules/core-js/modules/es.array.find-index.js
var es_array_find_index = __webpack_require__("c740");

// EXTERNAL MODULE: ./node_modules/core-js/modules/es.array.sort.js
var es_array_sort = __webpack_require__("4e82");

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"1bf56cee-vue-loader-template"}!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/vue-loader/lib??vue-loader-options!./src/components/form_elements/OnOffSlider.vue?vue&type=template&id=27868b2f&scoped=true&
var OnOffSlidervue_type_template_id_27868b2f_scoped_true_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',{staticClass:"slider"},[_c('input',{directives:[{name:"model",rawName:"v-model",value:(_vm.slider_value),expression:"slider_value"}],staticClass:"slider-checkbox",attrs:{"type":"checkbox","id":'slider_' + _vm.name,"true-value":_vm.true_value,"false-value":_vm.false_value,"disabled":_vm.disabled},domProps:{"checked":Array.isArray(_vm.slider_value)?_vm._i(_vm.slider_value,null)>-1:_vm._q(_vm.slider_value,_vm.true_value)},on:{"change":function($event){var $$a=_vm.slider_value,$$el=$event.target,$$c=$$el.checked?(_vm.true_value):(_vm.false_value);if(Array.isArray($$a)){var $$v=null,$$i=_vm._i($$a,$$v);if($$el.checked){$$i<0&&(_vm.slider_value=$$a.concat([$$v]))}else{$$i>-1&&(_vm.slider_value=$$a.slice(0,$$i).concat($$a.slice($$i+1)))}}else{_vm.slider_value=$$c}}}}),_c('label',{staticClass:"slider-label",attrs:{"for":'slider_' + _vm.name}},[_c('span',{staticClass:"slider-inner"}),_c('span',{staticClass:"slider-circle"})])])}
var OnOffSlidervue_type_template_id_27868b2f_scoped_true_staticRenderFns = []


// CONCATENATED MODULE: ./src/components/form_elements/OnOffSlider.vue?vue&type=template&id=27868b2f&scoped=true&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--13-0!./node_modules/thread-loader/dist/cjs.js!./node_modules/babel-loader/lib!./node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/vue-loader/lib??vue-loader-options!./src/components/form_elements/OnOffSlider.vue?vue&type=script&lang=js&
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
/* harmony default export */ var OnOffSlidervue_type_script_lang_js_ = ({
  name: "OnOffSlider",
  model: {
    prop: "prop_slider_value",
    event: "prop-slider-value-changed"
  },
  props: {
    prop_slider_value: {
      type: String
    },
    name: {
      type: String
    },
    true_value: {},
    false_value: {},
    disabled: {
      type: Boolean,
      default: false
    }
  },
  computed: {
    slider_value: {
      get: function get() {
        return this.prop_slider_value;
      },
      set: function set(new_value) {
        this.$emit("prop-slider-value-changed", new_value);
      }
    }
  }
});
// CONCATENATED MODULE: ./src/components/form_elements/OnOffSlider.vue?vue&type=script&lang=js&
 /* harmony default export */ var form_elements_OnOffSlidervue_type_script_lang_js_ = (OnOffSlidervue_type_script_lang_js_); 
// EXTERNAL MODULE: ./src/components/form_elements/OnOffSlider.vue?vue&type=style&index=0&id=27868b2f&scoped=true&lang=css&
var OnOffSlidervue_type_style_index_0_id_27868b2f_scoped_true_lang_css_ = __webpack_require__("005e");

// CONCATENATED MODULE: ./src/components/form_elements/OnOffSlider.vue






/* normalize component */

var OnOffSlider_component = Object(componentNormalizer["a" /* default */])(
  form_elements_OnOffSlidervue_type_script_lang_js_,
  OnOffSlidervue_type_template_id_27868b2f_scoped_true_render,
  OnOffSlidervue_type_template_id_27868b2f_scoped_true_staticRenderFns,
  false,
  null,
  "27868b2f",
  null
  
)

/* harmony default export */ var OnOffSlider = (OnOffSlider_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"1bf56cee-vue-loader-template"}!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/vue-loader/lib??vue-loader-options!./src/components/form_elements/TypeToSelect.vue?vue&type=template&id=5d0baf80&
var TypeToSelectvue_type_template_id_5d0baf80_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',{ref:"ttf_container",staticClass:"ttf-cont",style:({ width: _vm.cont_elem_width })},[_c('v-select',{ref:"ttf_select",staticClass:"ttf-v-selector",attrs:{"label":_vm.label_field,"options":_vm.options,"value":_vm.form_selected,"clearable":false,"appendToBody":true,"calculate-position":_vm.with_popper,"reduce":function (option) { return option[_vm.value_field]; },"disabled":_vm.disabled,"filter-by":_vm.filterBy},on:{"input":function($event){return _vm.check_and_set_tz($event)},"open":_vm.update_cont_width,"close":_vm.update_cont_width},scopedSlots:_vm._u([_vm._l((_vm.$scopedSlots),function(_,slot){return {key:slot,fn:function(scope){return [_vm._t(slot,null,null,scope)]}}})],null,true)})],1)}
var TypeToSelectvue_type_template_id_5d0baf80_staticRenderFns = []


// CONCATENATED MODULE: ./src/components/form_elements/TypeToSelect.vue?vue&type=template&id=5d0baf80&

// EXTERNAL MODULE: ./node_modules/vue-select/dist/vue-select.js
var vue_select = __webpack_require__("4a7a");
var vue_select_default = /*#__PURE__*/__webpack_require__.n(vue_select);

// EXTERNAL MODULE: ./node_modules/vue-select/dist/vue-select.css
var dist_vue_select = __webpack_require__("6dfc");

// EXTERNAL MODULE: ./node_modules/@popperjs/core/lib/popper.js + 51 modules
var lib_popper = __webpack_require__("39c3");

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--13-0!./node_modules/thread-loader/dist/cjs.js!./node_modules/babel-loader/lib!./node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/vue-loader/lib??vue-loader-options!./src/components/form_elements/TypeToSelect.vue?vue&type=script&lang=js&
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//



/* harmony default export */ var TypeToSelectvue_type_script_lang_js_ = ({
  name: "TypeToSelect",
  components: {
    vSelect: vue_select_default.a
  },
  model: {
    prop: "form_selected",
    event: "changed-form-selected"
  },
  props: {
    form_selected: {},
    label_field: {
      type: String,
      default: "label"
    },
    value_field: {
      type: String,
      default: "id"
    },
    options: {
      type: Array
    },
    disabled: {
      type: Boolean,
      default: false
    },
    filterBy: {
      default: undefined
    }
  },
  data: function data() {
    return {
      cont_elem_width: "auto",
      last_cont_elem_width: ""
    };
  },
  methods: {
    check_and_set_tz: function check_and_set_tz(selected_value) {
      this.$emit("changed-form-selected", selected_value);
      return;
    },
    with_popper: function with_popper(dropdownList, component, _ref) {
      var width = _ref.width;
      //code taken from https://vue-select.org/guide/positioning.html#popper-js-integration

      /**
       * We need to explicitly define the dropdown width since
       * it is usually inherited from the parent with CSS.
       */
      dropdownList.style.width = width;
      dropdownList.style["z-index"] = 99000;
      dropdownList.classList.add("wpcal-v-select-body-dropdown"); //append to body class

      /**
       * Here we position the dropdownList relative to the $refs.toggle Element.
       *
       * The 'offset' modifier aligns the dropdown so that the $refs.toggle and
       * the dropdownList overlap by 1 pixel.
       *
       * The 'toggleClass' modifier adds a 'drop-up' class to the Vue Select
       * wrapper so that we can set some styles for when the dropdown is placed
       * above.
       */

      var popper = Object(lib_popper["a" /* createPopper */])(component.$refs.toggle, dropdownList, {
        //placement: this.placement,
        modifiers: [{
          name: "offset",
          options: {
            offset: [0, -1]
          }
        }, {
          name: "toggleClass",
          enabled: true,
          phase: "write",
          fn: function fn(_ref2) {
            var state = _ref2.state;
            component.$el.classList.toggle("drop-up", state.placement === "top");
          }
        }, {
          name: "preventOverflow",
          options: {
            boundary: this.$refs.ttf_container,
            padding: -1
          }
        }]
      });
      /**
       * To prevent memory leaks Popper needs to be destroyed.
       * If you return function, it will be called just before dropdown is removed from DOM.
       */

      return function () {
        return popper.destroy();
      };
    },
    update_cont_width: function update_cont_width() {
      var _this = this;

      if (this.$refs.ttf_select.$data.open) {
        this.cont_elem_width = this.last_cont_elem_width ? this.last_cont_elem_width + "px" : "auto";
      } else {
        this.cont_elem_width = "auto";
        this.$nextTick(function () {
          _this.last_cont_elem_width = _this.$refs["ttf_container"].clientWidth;
        });
      }
    }
  },
  mounted: function mounted() {
    var _this2 = this;

    this.$nextTick(function () {
      _this2.last_cont_elem_width = _this2.$refs["ttf_container"].clientWidth;
    });
  },
  watch: {
    options: function options() {
      var _this3 = this;

      this.$nextTick(function () {
        _this3.last_cont_elem_width = _this3.$refs["ttf_container"].clientWidth;
      });
    }
  }
});
// CONCATENATED MODULE: ./src/components/form_elements/TypeToSelect.vue?vue&type=script&lang=js&
 /* harmony default export */ var form_elements_TypeToSelectvue_type_script_lang_js_ = (TypeToSelectvue_type_script_lang_js_); 
// EXTERNAL MODULE: ./src/components/form_elements/TypeToSelect.vue?vue&type=style&index=0&lang=css&
var TypeToSelectvue_type_style_index_0_lang_css_ = __webpack_require__("4d8e");

// CONCATENATED MODULE: ./src/components/form_elements/TypeToSelect.vue






/* normalize component */

var TypeToSelect_component = Object(componentNormalizer["a" /* default */])(
  form_elements_TypeToSelectvue_type_script_lang_js_,
  TypeToSelectvue_type_template_id_5d0baf80_render,
  TypeToSelectvue_type_template_id_5d0baf80_staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* harmony default export */ var TypeToSelect = (TypeToSelect_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"1bf56cee-vue-loader-template"}!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/vue-loader/lib??vue-loader-options!./src/components/form_elements/OverflowMenu.vue?vue&type=template&id=3a345ca8&scoped=true&
var OverflowMenuvue_type_template_id_3a345ca8_scoped_true_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('span',{staticClass:"overflow-options-cont",style:({
    'z-index': _vm.overflow_menu_details.open_menu_id == _vm.menu_id ? '1' : ''
  }),attrs:{"id":'overflow_options_cont_id_' + _vm.menu_id}},[_c('a',{staticClass:"overflow-btn",attrs:{"id":'overflow_icon_' + _vm.menu_id},on:{"click":function($event){return _vm.toggle_menu(_vm.menu_id)}}}),_c('div',{staticClass:"popper-cont",class:{
      hidden: _vm.overflow_menu_details.open_menu_id != _vm.menu_id
    },attrs:{"id":'overflow_menu_' + _vm.menu_id}},[_c('div',{staticClass:"popper_arrow",attrs:{"data-popper-arrow":""}}),_c('span',{staticClass:"overflow-options"},[_vm._t("default")],2)])])}
var OverflowMenuvue_type_template_id_3a345ca8_scoped_true_staticRenderFns = []


// CONCATENATED MODULE: ./src/components/form_elements/OverflowMenu.vue?vue&type=template&id=3a345ca8&scoped=true&

// EXTERNAL MODULE: ./node_modules/core-js/modules/es.number.constructor.js
var es_number_constructor = __webpack_require__("a9e3");

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--13-0!./node_modules/thread-loader/dist/cjs.js!./node_modules/babel-loader/lib!./node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/vue-loader/lib??vue-loader-options!./src/components/form_elements/OverflowMenu.vue?vue&type=script&lang=js&

//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

/* harmony default export */ var OverflowMenuvue_type_script_lang_js_ = ({
  name: "OverflowMenu",
  model: {
    prop: "overflow_menu_details",
    event: "overflow-menu-details-changed"
  },
  props: {
    menu_id: {
      type: [Number, String]
    },
    overflow_menu_details: {
      type: Object
    },
    popper_position: {
      type: String,
      default: "bottom-end"
    }
  },
  data: function data() {
    return {};
  },
  methods: {
    may_destroy_popper: function may_destroy_popper() {
      if (this.overflow_menu_details.popper_instance) {
        this.overflow_menu_details.popper_instance.destroy();
        this.overflow_menu_details.popper_instance = null;
      }
    },
    toggle_menu: function toggle_menu(menu_id) {
      var _this = this;

      this.overflow_menu_details.open_menu_id = this.overflow_menu_details.open_menu_id == menu_id ? null : menu_id;
      this.$nextTick(function () {
        if (_this.overflow_menu_details.open_menu_id) {
          var element = document.querySelector("#overflow_icon_" + menu_id);
          var tooltip = document.querySelector("#overflow_menu_" + menu_id);
          _this.overflow_menu_details.popper_instance = Object(lib_popper["a" /* createPopper */])(element, tooltip, {
            placement: _this.popper_position,
            modifiers: [{
              name: "offset",
              options: {
                offset: [0, 5]
              }
            }]
          });

          _this.on_click_outside("#overflow_options_cont_id_" + menu_id, function () {
            if (_this.overflow_menu_details.open_menu_id == menu_id) {
              _this.overflow_menu_details.open_menu_id = null;
            }
          });
        }
      });
    }
  },
  watch: {
    "overflow_menu_details.open_menu_id": function overflow_menu_detailsOpen_menu_id(new_value) {
      if (new_value == null) {
        this.may_destroy_popper();
      }
    }
  }
});
// CONCATENATED MODULE: ./src/components/form_elements/OverflowMenu.vue?vue&type=script&lang=js&
 /* harmony default export */ var form_elements_OverflowMenuvue_type_script_lang_js_ = (OverflowMenuvue_type_script_lang_js_); 
// EXTERNAL MODULE: ./src/components/form_elements/OverflowMenu.vue?vue&type=style&index=0&id=3a345ca8&scoped=true&lang=css&
var OverflowMenuvue_type_style_index_0_id_3a345ca8_scoped_true_lang_css_ = __webpack_require__("ef4c");

// CONCATENATED MODULE: ./src/components/form_elements/OverflowMenu.vue






/* normalize component */

var OverflowMenu_component = Object(componentNormalizer["a" /* default */])(
  form_elements_OverflowMenuvue_type_script_lang_js_,
  OverflowMenuvue_type_template_id_3a345ca8_scoped_true_render,
  OverflowMenuvue_type_template_id_3a345ca8_scoped_true_staticRenderFns,
  false,
  null,
  "3a345ca8",
  null
  
)

/* harmony default export */ var OverflowMenu = (OverflowMenu_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"1bf56cee-vue-loader-template"}!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/vue-loader/lib??vue-loader-options!./src/components/admin/AlertAdminToUpdateProfile.vue?vue&type=template&id=14402081&scoped=true&
var AlertAdminToUpdateProfilevue_type_template_id_14402081_scoped_true_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return (_vm.is_current_admin_profile_needs_an_update)?_c('div',[_c('modal',{staticStyle:{"z-index":"9999"},attrs:{"name":"alert_admin_to_update_profile_modal","width":'600px',"height":'auto',"adaptive":true}},[_c('div',{staticClass:"modal_close_cont"},[_c('button',{on:{"click":function($event){return _vm.$modal.hide('alert_admin_to_update_profile_modal')}}})]),_c('div',{staticStyle:{"text-align":"center","padding":"20px"}},[_c('div',{staticStyle:{"font-size":"16px","font-weight":"500"}},[_vm._v(" "+_vm._s(_vm.T__("Booking Widget Info Preview"))+" ")]),_c('div',{staticClass:"gravatar-name-onboarding"},[_c('img',{attrs:{"src":_vm.admin_details.profile_picture,"width":"75"}}),_c('div',{staticClass:"display-name"},[_vm._v(_vm._s(_vm.admin_details.display_name))]),(_vm.service_details.name)?_c('div',{staticClass:"event-type-name"},[_vm._v(" "+_vm._s(_vm.service_details.name)+" ")]):_vm._e(),(_vm.service_details.duration)?_c('div',{staticClass:"event-type-duration"},[_vm._v(" "+_vm._s(_vm._f("mins_to_str")(_vm.service_details.duration))+" ")]):_vm._e()]),_c('div',{staticStyle:{"font-size":"16px","margin-bottom":"20px","line-height":"1.6em"}},[_vm._v(" "+_vm._s(_vm.T__("Want to change your Avatar or Display name?"))+" "),_c('br'),_vm._v(" "+_vm._s(_vm.T__("Go to"))+" "),_c('router-link',{staticStyle:{"font-size":"16px"},attrs:{"to":"/settings/profile"}},[_vm._v(_vm._s(_vm.T__("Settings › Profile"))+" ")])],1),_c('a',{on:{"click":_vm.dismiss_notice}},[_vm._v(_vm._s(_vm.T__("Nope, everything looks good")))])])]),_c('a',{staticStyle:{"display":"none"},on:{"click":_vm.check_and_show_alert_to_admin}},[_vm._v("Lorem ipsum... Admin profile check")])],1):_vm._e()}
var AlertAdminToUpdateProfilevue_type_template_id_14402081_scoped_true_staticRenderFns = []


// CONCATENATED MODULE: ./src/components/admin/AlertAdminToUpdateProfile.vue?vue&type=template&id=14402081&scoped=true&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--13-0!./node_modules/thread-loader/dist/cjs.js!./node_modules/babel-loader/lib!./node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/vue-loader/lib??vue-loader-options!./src/components/admin/AlertAdminToUpdateProfile.vue?vue&type=script&lang=js&


//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

/* harmony default export */ var AlertAdminToUpdateProfilevue_type_script_lang_js_ = ({
  props: {
    service_details: {}
  },
  computed: {
    is_current_admin_profile_needs_an_update: function is_current_admin_profile_needs_an_update() {
      if (this._isEmpty(this.admin_details)) {
        return false;
      }

      var admin_details = this.admin_details;

      if (!admin_details.id) {
        return false;
      }

      var current_admin_notices = this.$store.getters.get_current_admin_notices;

      if (current_admin_notices == null) {
        //not yet loaded
        return false;
      }

      if (current_admin_notices.hasOwnProperty("alert_admin_to_update_profile_for_name_v2") && current_admin_notices.alert_admin_to_update_profile_for_name_v2.hasOwnProperty("status") && current_admin_notices.alert_admin_to_update_profile_for_name_v2.status == "dismissed") {
        return false;
      }

      return true;
    },
    admin_details: function admin_details() {
      return this.$store.getters.get_current_admin_details;
    }
  },
  methods: {
    show_alert_admin_to_update_profile_modal: function show_alert_admin_to_update_profile_modal() {
      this.$modal.show("alert_admin_to_update_profile_modal");
    },
    check_and_show_alert_to_admin: function check_and_show_alert_to_admin() {
      var _this = this;

      return Object(asyncToGenerator["a" /* default */])( /*#__PURE__*/regeneratorRuntime.mark(function _callee() {
        return regeneratorRuntime.wrap(function _callee$(_context) {
          while (1) {
            switch (_context.prev = _context.next) {
              case 0:
                _context.next = 2;
                return _this.$store.dispatch("get_settings_and_defaults", {
                  background_call: true
                });

              case 2:
                //to get latest data and show alert.
                if (_this.is_current_admin_profile_needs_an_update) {
                  _this.show_alert_admin_to_update_profile_modal();
                }

              case 3:
              case "end":
                return _context.stop();
            }
          }
        }, _callee);
      }))();
    },
    dismiss_notice: function dismiss_notice() {
      var _this2 = this;

      this.$modal.hide("alert_admin_to_update_profile_modal");
      var wpcal_request = {};
      var action = "update_admin_notices";
      wpcal_request[action] = {
        alert_admin_to_update_profile_for_name_v2: {
          status: "dismissed"
        }
      };
      var action2 = "get_admin_notices";
      wpcal_request[action2] = "dummy__";
      var post_data = {
        action: "wpcal_process_admin_ajax_request",
        wpcal_request: wpcal_request
      };
      axios_default.a.post(window.wpcal_ajax.ajax_url, post_data).then(function (response) {
        //console.log(response);
        _this2.notify_single_action_result(response.data[action], {
          success_msg: _this2.T__("Successfully dismissed.")
        });

        if (response.data[action2].status === "success" && response.data[action2].hasOwnProperty("current_admin_notices")) {
          _this2.$store.commit("set_store_by_obj", {
            current_admin_notices: response.data[action2].current_admin_notices
          });
        }
      }).catch(function (error) {
        console.log(error);
      });
    }
  },
  mounted: function mounted() {
    var _this3 = this;

    setTimeout(function () {
      return _this3.check_and_show_alert_to_admin();
    }, 1000);
  }
});
// CONCATENATED MODULE: ./src/components/admin/AlertAdminToUpdateProfile.vue?vue&type=script&lang=js&
 /* harmony default export */ var admin_AlertAdminToUpdateProfilevue_type_script_lang_js_ = (AlertAdminToUpdateProfilevue_type_script_lang_js_); 
// EXTERNAL MODULE: ./src/components/admin/AlertAdminToUpdateProfile.vue?vue&type=style&index=0&id=14402081&scoped=true&lang=css&
var AlertAdminToUpdateProfilevue_type_style_index_0_id_14402081_scoped_true_lang_css_ = __webpack_require__("3f23");

// CONCATENATED MODULE: ./src/components/admin/AlertAdminToUpdateProfile.vue






/* normalize component */

var AlertAdminToUpdateProfile_component = Object(componentNormalizer["a" /* default */])(
  admin_AlertAdminToUpdateProfilevue_type_script_lang_js_,
  AlertAdminToUpdateProfilevue_type_template_id_14402081_scoped_true_render,
  AlertAdminToUpdateProfilevue_type_template_id_14402081_scoped_true_staticRenderFns,
  false,
  null,
  "14402081",
  null
  
)

/* harmony default export */ var AlertAdminToUpdateProfile = (AlertAdminToUpdateProfile_component.exports);
// CONCATENATED MODULE: ./src/utils/admin_service_status_toggle_mixin.js


var admin_service_status_toggle_mixin = {
  data: function data() {
    return {
      last_confirmed_action: null
    };
  },
  methods: {
    may_show_service_status_change_dialog: function may_show_service_status_change_dialog(status, service) {
      var _this = this;

      var allowed_status = [1, -1, -2, "1", "-1", "-2"];

      if (allowed_status.indexOf(status) == -1) {
        return;
      }

      this.last_confirmed_action = null;

      if (this.$options.name == "ServiceList" && status != 1) {
        this.service_overflow_menu_details.open_menu_id = null;
      }

      var delete_disable_str = "<ul class='bull'><li>" + this.T__("Existing bookings will NOT be affected.") + "</li><li>" + this.T__("New booking and rescheduling will NOT be allowed.") + "</li><li>" + this.T__("Cancellation will be allowed.") + "</li></ul>";
      var status_action_str = this.T__("turn on");
      var button_str = this.T__("Turn On");
      var detail_str = "";
      var title_str = "";

      if (status == -1) {
        status_action_str = this.T__("turn off");
        button_str = this.T__("Yes, turn off.");
        detail_str = delete_disable_str + '<span class="wpcal_fix_service_disable_attempt"></span>';
        title_str = "Turn off " + service.name + "?";
      } else if (status == -2) {
        status_action_str = "delete";
        button_str = this.T__("Yes, delete.");
        detail_str = delete_disable_str;
        title_str = "Delete " + service.name + "?";
      } else if (status == 1) {
        this.change_service_status(status, service);
        return;
      }

      this.$modal.show("dialog", {
        title: title_str,
        text: detail_str,
        buttons: [{
          title: button_str,
          class: status == -1 || status == -2 ? "vue-dialog-button dialog-red-btn" : "",
          handler: function handler() {
            _this.change_service_status(status, service);

            _this.last_confirmed_action = status == -1 ? "disable" : null;

            _this.$modal.hide("dialog");
          }
        }, {
          title: this.T__("No, don't."),
          default: true,
          // Will be triggered by default if 'Enter' pressed.
          handler: function handler() {
            _this.load_page();

            _this.$modal.hide("dialog");
          }
        }]
      });
    },
    change_service_status: function change_service_status(status, service) {
      var _this2 = this;

      var wpcal_request = {};
      var action_update_status = "update_service_status_of_current_admin";
      wpcal_request[action_update_status] = {
        service_id: service.id,
        status: status
      };
      var action_get_services = "get_managed_services_for_current_admin";

      if (this.$options.name == "ServiceList") {
        wpcal_request[action_get_services] = this.filter_options;
      }

      var action_edit_service = "edit_service";

      if (this.$options.name == "ServiceForm") {
        wpcal_request[action_edit_service] = {
          service_id: this.service_id
        };
      }

      var post_data = {
        action: "wpcal_process_admin_ajax_request",
        wpcal_request: wpcal_request
      };
      axios_default.a.post(window.wpcal_ajax.ajax_url, post_data).then(function (response) {
        _this2.notify_single_action_result(response.data[action_update_status], {
          dont_notify_success: true
        });

        if (_this2.$options.name == "ServiceList") {
          _this2.notify_single_action_result(response.data[action_get_services], {
            dont_notify_success: true
          });

          if (response.data && response.data[action_get_services].hasOwnProperty("service_list")) {
            _this2.service_list = response.data[action_get_services].service_list;
          }
        }

        if (_this2.$options.name == "ServiceForm") {
          if (response.data && response.data[action_edit_service].hasOwnProperty("service_data")) {
            _this2.form = response.data[action_edit_service].service_data;
            _this2.saved_form = _this2.clone_deep(_this2.form);
          }
        }
      }).catch(function (error) {
        console.log(error);
      });
    }
  },
  mounted: function mounted() {
    var _this3 = this;

    this.event_bus.$on("dialog-before-closed", function (event) {
      if (event !== null && event !== void 0 && event.ref && event.ref.querySelector(".wpcal_fix_service_disable_attempt")) {
        if (_this3.$options.name == "ServiceList" && _this3.last_confirmed_action != "disable"
        /* this.last_confirmed_action == "disable" UI purpose, on click toggle the whole Service shows in disabled feel, before confirming in the popup, after confirming yes or no, below code runs it goes back to active, once ajax call over comes back to disabled again to avoid flip flop this is done */
        ) {
          _this3.service_list = _this3.clone_deep(_this3.saved_service_list);
        } else if (_this3.$options.name == "ServiceForm") {
          _this3.form = _this3.clone_deep(_this3.saved_form);
        }
      }
    });
  }
};
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--13-0!./node_modules/thread-loader/dist/cjs.js!./node_modules/babel-loader/lib!./node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/vue-loader/lib??vue-loader-options!./src/views/admin/ServiceList.vue?vue&type=script&lang=js&




//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//






/* harmony default export */ var ServiceListvue_type_script_lang_js_ = ({
  name: "ServiceList",
  mixins: [admin_service_status_toggle_mixin],
  components: {
    OnOffSlider: OnOffSlider,
    TypeToSelect: TypeToSelect,
    AlertAdminToUpdateProfile: AlertAdminToUpdateProfile,
    OverflowMenu: OverflowMenu
  },
  data: function data() {
    return {
      document_title: this.T__("Event Types"),
      service_list: {},
      saved_service_list: {},
      filter_options: {
        filter_by_admin_id: this.$store.getters.get_current_admin_details.id
      },
      first_time_service_list_loaded: false,
      service_overflow_menu_details: {
        open_menu_id: null,
        popper_instance: null
      },
      delete_highlight_service_id: null
    };
  },
  computed: {
    grouped_service_list: function grouped_service_list() {
      var grouped_service_list = []; //to maintain order array is used

      var unidentified_service_list = []; //mostly deleted admins

      var unidentified_services = []; //mostly deleted admins

      var managed_admins = this.$store.getters.get_managed_active_admins_details;

      var _iterator = Object(createForOfIteratorHelper["a" /* default */])(managed_admins),
          _step;

      try {
        for (_iterator.s(); !(_step = _iterator.n()).done;) {
          var admin_detail = _step.value;
          grouped_service_list.push({
            admin_user_id: admin_detail.admin_user_id,
            services: []
          }); //to maintain order array inside object used
        }
      } catch (err) {
        _iterator.e(err);
      } finally {
        _iterator.f();
      }

      var _iterator2 = Object(createForOfIteratorHelper["a" /* default */])(this.service_list_final),
          _step2;

      try {
        var _loop = function _loop() {
          var service = _step2.value;

          var _index = grouped_service_list.findIndex(function (val) {
            return val.admin_user_id == service.admin_user_id;
          }); //let _unidentified_list_index;


          if (_index == -1) {
            // _unidentified_list_index = unidentified_service_list.findIndex(
            //   val => {
            //     return val.admin_user_id == service.admin_user_id;
            //   }
            // );
            // if (_unidentified_list_index == -1) {
            //   _unidentified_list_index =
            //     unidentified_service_list.push({
            //       admin_user_id: service.admin_user_id,
            //       services: []
            //     }) - 1;
            // }
            unidentified_services.push(service);
            return "continue";
          }

          grouped_service_list[_index].services.push(service);
        };

        for (_iterator2.s(); !(_step2 = _iterator2.n()).done;) {
          var _ret = _loop();

          if (_ret === "continue") continue;
        }
      } catch (err) {
        _iterator2.e(err);
      } finally {
        _iterator2.f();
      }

      var grouped_service_list_tmp = this.clone_deep(grouped_service_list);
      grouped_service_list = [];

      var _iterator3 = Object(createForOfIteratorHelper["a" /* default */])(grouped_service_list_tmp),
          _step3;

      try {
        for (_iterator3.s(); !(_step3 = _iterator3.n()).done;) {
          var val_obj = _step3.value;

          if (!this._isEmpty(val_obj.services)) {
            grouped_service_list.push(val_obj);
          }
        }
      } catch (err) {
        _iterator3.e(err);
      } finally {
        _iterator3.f();
      }

      if (unidentified_services.length) {
        unidentified_service_list[0] = {
          admin_user_id: 0,
          services: this.clone_deep(unidentified_services)
        };
        grouped_service_list = grouped_service_list.concat(unidentified_service_list);
      }

      return grouped_service_list;
    },
    service_list_final: function service_list_final() {
      if (!Array.isArray(this.service_list) || !this.service_list.length) {
        return [];
      }

      var service_list_tmp = this.clone_deep(this.service_list);
      service_list_tmp.sort(function (a, b) {
        return b.id - a.id;
      }); //latest first - decending

      return service_list_tmp;
    },
    sample_real_service_details: function sample_real_service_details() {
      var service_details = null;

      if (Array.isArray(this.service_list_final) && this.service_list_final.length) {
        for (var service_key in this.service_list_final) {
          if (this.service_list_final[service_key].status == "1") {
            service_details = this.clone_deep(this.service_list_final[service_key]);
            break;
          }
        }

        if (!service_details) {
          //If non of the service is active
          service_details = this.clone_deep(this.service_list_final[0]);
        }
      }

      return service_details;
    }
  },
  methods: {
    load_page: function load_page() {
      var _this = this;

      var action = "get_managed_services_for_current_admin";
      var wpcal_request = {};
      wpcal_request[action] = this.filter_options;
      var post_data = {
        action: "wpcal_process_admin_ajax_request",
        wpcal_request: wpcal_request
      };
      axios_default.a.post(window.wpcal_ajax.ajax_url, post_data).then(function (response) {
        //console.log(response);
        _this.notify_single_action_result(response.data[action], {
          dont_notify_success: true
        });

        if (response.data && response.data[action].hasOwnProperty("service_list")) {
          _this.service_list = response.data[action].service_list;
          _this.saved_service_list = _this.clone_deep(_this.service_list);
          _this.first_time_service_list_loaded = true;
        }
      }).catch(function (error) {
        console.log(error);
      });
    }
  },
  mounted: function mounted() {
    this.load_page();
    this.$store.dispatch("load_managed_active_admins_details");
  }
});
// CONCATENATED MODULE: ./src/views/admin/ServiceList.vue?vue&type=script&lang=js&
 /* harmony default export */ var admin_ServiceListvue_type_script_lang_js_ = (ServiceListvue_type_script_lang_js_); 
// EXTERNAL MODULE: ./src/views/admin/ServiceList.vue?vue&type=style&index=0&id=06620e8e&lang=css&scoped=true&
var ServiceListvue_type_style_index_0_id_06620e8e_lang_css_scoped_true_ = __webpack_require__("a817");

// CONCATENATED MODULE: ./src/views/admin/ServiceList.vue






/* normalize component */

var ServiceList_component = Object(componentNormalizer["a" /* default */])(
  admin_ServiceListvue_type_script_lang_js_,
  ServiceListvue_type_template_id_06620e8e_scoped_true_render,
  ServiceListvue_type_template_id_06620e8e_scoped_true_staticRenderFns,
  false,
  null,
  "06620e8e",
  null
  
)

/* harmony default export */ var ServiceList = (ServiceList_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"1bf56cee-vue-loader-template"}!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/vue-loader/lib??vue-loader-options!./src/views/admin/ServiceForm.vue?vue&type=template&id=a57f84d0&
var ServiceFormvue_type_template_id_a57f84d0_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',[_c('div',{staticClass:"page-title-cont"},[_c('h1',{staticClass:"wp-heading-inline"},[_vm._v("WPCal.io - "+_vm._s(_vm.page_title))]),_c('AdminHeaderRight')],1),_c('div',{class:{
      'create-event-type': _vm.view == 'add',
      'edit-event-type': _vm.view == 'edit'
    }},[_c('div',{staticClass:"steps-main",class:{ 'step-last': _vm.view == 'add' && _vm.tabs_flow.steps[2].form }},[(_vm.view == 'add')?_c('div',[_c('div',{staticClass:"validation_errors_cont"}),_c('ServiceFormStep1',{attrs:{"$v":_vm.$v,"step":_vm.tabs_flow.steps[0],"view":_vm.view,"form":_vm.form},on:{"validate-may-navigate-or-submit":function($event){return _vm.validate_may_navigate_or_submit($event)},"set-active-step-and-toggle":_vm.set_active_step_and_toggle}}),_c('ServiceFormStep2',{attrs:{"$v":_vm.$v,"step":_vm.tabs_flow.steps[1],"view":_vm.view,"form":_vm.form,"create_or_update_btn_is_enabled":_vm.create_or_update_btn_is_enabled},on:{"validate-may-navigate-or-submit":function($event){return _vm.validate_may_navigate_or_submit($event)},"set-active-step-and-toggle":_vm.set_active_step_and_toggle}}),_c('ServiceFormStep3',{attrs:{"$v":_vm.$v,"step":_vm.tabs_flow.steps[2],"view":_vm.view,"form":_vm.form,"create_or_update_btn_is_enabled":_vm.create_or_update_btn_is_enabled},on:{"validate-may-navigate-or-submit":function($event){return _vm.validate_may_navigate_or_submit($event)}}})],1):_vm._e(),(_vm.view == 'edit')?_c('div',[_c('div',{staticClass:"validation_errors_cont"})]):_vm._e(),(_vm.view == 'edit')?_c('ServiceFormEditView',{attrs:{"$v":_vm.$v,"view":_vm.view,"service_id":_vm.service_id,"form":_vm.form,"saved_form":_vm.saved_form,"edit_view":_vm.edit_view,"create_or_update_btn_is_enabled":_vm.create_or_update_btn_is_enabled},on:{"submit-form":function($event){return _vm.validate_may_navigate_or_submit($event)},"save-questions":function($event){return _vm.form_submit()},"edit-view-toggle-steps":function($event){return _vm.edit_view_toggle_steps($event)},"change-service-status":function($event){return _vm.may_show_service_status_change_dialog($event, {
            id: _vm.service_id,
            name: _vm.saved_form.name
          })}}}):_vm._e()],1)]),(_vm.view == 'edit')?_c('AdminInfoLine',{attrs:{"timezone":_vm.saved_form.timezone,"info_slugs":_vm.view == 'edit'
        ? [
            'booking_default_reminder_email',
            'timings_in_timezone',
            'conflict_resolution_for_host'
          ]
        : []}}):_vm._e()],1)}
var ServiceFormvue_type_template_id_a57f84d0_staticRenderFns = []


// CONCATENATED MODULE: ./src/views/admin/ServiceForm.vue?vue&type=template&id=a57f84d0&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"1bf56cee-vue-loader-template"}!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/vue-loader/lib??vue-loader-options!./src/components/admin/ServiceFormStep1.vue?vue&type=template&id=5ecf4cde&scoped=true&
var ServiceFormStep1vue_type_template_id_5ecf4cde_scoped_true_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',[_c('div',{directives:[{name:"show",rawName:"v-show",value:(_vm.step.form),expression:"step.form"}],staticClass:"step-single form active"},[_c('div',{staticClass:"step-title"},[_vm._v(_vm._s(_vm.T__("Select Participant Type")))]),_c('div',{staticClass:"form",on:{"submit":function($event){$event.preventDefault();}}},[_c('form-row',{attrs:{"validator":_vm.$v.form.relationship_type}},[_c('ul',{staticClass:"selector block"},[_c('li',[_c('input',{directives:[{name:"model",rawName:"v-model",value:(_vm.form.relationship_type),expression:"form.relationship_type"}],attrs:{"type":"radio","id":"one-on-one","name":"participant-type","value":"1to1"},domProps:{"checked":_vm._q(_vm.form.relationship_type,"1to1")},on:{"change":function($event){return _vm.$set(_vm.form, "relationship_type", "1to1")}}}),_c('label',{attrs:{"for":"one-on-one"}},[_c('div',{staticClass:"txt-h4"},[_vm._v(_vm._s(_vm.T__("One-on-One")))]),_c('div',{staticClass:"txt-help"},[_vm._v(" "+_vm._s(_vm.T__("Allow invitees to book individual slots with you."))+" ")])])]),_c('li',{staticClass:"disabled"},[_c('input',{attrs:{"type":"radio","name":"participant-type","id":"invitee-group","disabled":""}}),_c('label',{attrs:{"for":"invitee-group"}},[_c('div',{staticClass:"txt-h4"},[_vm._v(" "+_vm._s(_vm.T__("Group"))),_c('em',{staticClass:"coming-soon"},[_vm._v("COMING SOON")])]),_c('div',{staticClass:"txt-help"},[_vm._v(" "+_vm._s(_vm.T__( "Allow multiple invitees to book the same slot. Useful for tours, webinars, classes, workshops, etc." ))+" ")])])]),_c('li',{staticClass:"disabled"},[_c('input',{directives:[{name:"model",rawName:"v-model",value:(_vm.form.relationship_type),expression:"form.relationship_type"}],attrs:{"type":"radio","id":"collective","name":"participant-type","value":"collective","disabled":""},domProps:{"checked":_vm._q(_vm.form.relationship_type,"collective")},on:{"change":function($event){return _vm.$set(_vm.form, "relationship_type", "collective")}}}),_c('label',{attrs:{"for":"collective"}},[_c('div',{staticClass:"txt-h4"},[_vm._v(" "+_vm._s(_vm.T__("Collective"))),_c('span',[_vm._v("TEAM")]),_c('em',{staticClass:"coming-soon"},[_vm._v("COMING SOON")])]),_c('div',{staticClass:"txt-help"},[_vm._v(" "+_vm._s(_vm.T__( "Allow invitees to book slots when all assigned hosts are available." ))+" ")])])]),_c('li',{staticClass:"disabled"},[_c('input',{directives:[{name:"model",rawName:"v-model",value:(_vm.form.relationship_type),expression:"form.relationship_type"}],attrs:{"type":"radio","id":"round_robin","name":"participant-type","value":"round_robin","disabled":""},domProps:{"checked":_vm._q(_vm.form.relationship_type,"round_robin")},on:{"change":function($event){return _vm.$set(_vm.form, "relationship_type", "round_robin")}}}),_c('label',{attrs:{"for":"round_robin"}},[_c('div',{staticClass:"txt-h4"},[_vm._v(" "+_vm._s(_vm.T__("Round Robin"))),_c('span',[_vm._v("TEAM")]),_c('em',{staticClass:"coming-soon"},[_vm._v("COMING SOON")])]),_c('div',{staticClass:"txt-help"},[_vm._v(" "+_vm._s(_vm.T__("Assign slots to available hosts, cyclically."))+" ")])])])])]),_c('div',{staticClass:"wpc-form-row"},[_c('button',{staticClass:"wpc-btn primary lg",attrs:{"type":"button"},on:{"click":function($event){return _vm.$emit('validate-may-navigate-or-submit', 1)}}},[_vm._v(" "+_vm._s(_vm.T__("Continue"))+" ")])])],1)]),_c('div',{directives:[{name:"show",rawName:"v-show",value:(_vm.step.summary),expression:"step.summary"}],staticClass:"step-single summary"},[_c('div',{staticClass:"step-title"},[_c('span',[_vm._v(_vm._s(_vm.T__("Participant type")))]),_c('a',{on:{"click":function($event){return _vm.$emit('set-active-step-and-toggle', 1)}}},[_vm._v(_vm._s(_vm.T__("EDIT")))])]),_c('div',{staticClass:"step-summary-content"},[_vm._v(_vm._s(_vm.T__("One-on-One")))])])])}
var ServiceFormStep1vue_type_template_id_5ecf4cde_scoped_true_staticRenderFns = []


// CONCATENATED MODULE: ./src/components/admin/ServiceFormStep1.vue?vue&type=template&id=5ecf4cde&scoped=true&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--13-0!./node_modules/thread-loader/dist/cjs.js!./node_modules/babel-loader/lib!./node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/vue-loader/lib??vue-loader-options!./src/components/admin/ServiceFormStep1.vue?vue&type=script&lang=js&
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
/* harmony default export */ var ServiceFormStep1vue_type_script_lang_js_ = ({
  name: "ServiceFormStep1",
  props: {
    view: {
      type: String
    },
    step: {
      type: Object,
      required: true
    },
    form: {
      type: Object,
      required: true
    },
    $v: {}
  },
  mounted: function mounted() {
    if (this.view == "add") {
      this.$props.step.summary = true;
    }
  }
});
// CONCATENATED MODULE: ./src/components/admin/ServiceFormStep1.vue?vue&type=script&lang=js&
 /* harmony default export */ var admin_ServiceFormStep1vue_type_script_lang_js_ = (ServiceFormStep1vue_type_script_lang_js_); 
// EXTERNAL MODULE: ./src/components/admin/ServiceFormStep1.vue?vue&type=style&index=0&id=5ecf4cde&lang=scss&scoped=true&
var ServiceFormStep1vue_type_style_index_0_id_5ecf4cde_lang_scss_scoped_true_ = __webpack_require__("401c");

// CONCATENATED MODULE: ./src/components/admin/ServiceFormStep1.vue






/* normalize component */

var ServiceFormStep1_component = Object(componentNormalizer["a" /* default */])(
  admin_ServiceFormStep1vue_type_script_lang_js_,
  ServiceFormStep1vue_type_template_id_5ecf4cde_scoped_true_render,
  ServiceFormStep1vue_type_template_id_5ecf4cde_scoped_true_staticRenderFns,
  false,
  null,
  "5ecf4cde",
  null
  
)

/* harmony default export */ var ServiceFormStep1 = (ServiceFormStep1_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"1bf56cee-vue-loader-template"}!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/vue-loader/lib??vue-loader-options!./src/components/admin/ServiceFormStep2.vue?vue&type=template&id=1edd344c&scoped=true&
var ServiceFormStep2vue_type_template_id_1edd344c_scoped_true_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',[_c('div',{directives:[{name:"show",rawName:"v-show",value:(_vm.step.upcoming),expression:"step.upcoming"}],staticClass:"step-single upcoming"},[_c('div',{staticClass:"step-title"},[_vm._v(_vm._s(_vm.T__("Event type details")))])]),_c('div',{directives:[{name:"show",rawName:"v-show",value:(_vm.step.form),expression:"step.form"}],staticClass:"step-single form active",attrs:{"id":'service_form_step' + _vm.step.id}},[_c('div',{staticClass:"step-title"},[_vm._v(_vm._s(_vm.T__("Event Type Details")))]),_c('div',{staticClass:"form",on:{"submit":function($event){$event.preventDefault();}}},[_c('form-row',{attrs:{"validator":_vm.$v.form.name}},[_c('label',{attrs:{"for":"form_name"}},[_vm._v(" "+_vm._s(_vm.T__("Event type name"))+" "),_c('abbr',{staticClass:"required",attrs:{"title":"required"}},[_vm._v("*")]),_c('em',[_vm._v(_vm._s(_vm.T__("required")))])]),_c('span',{staticClass:"txt-help"},[_vm._v(" "+_vm._s(_vm.T__( "The event type name will be visible to your invitees. Enter something that will guide them through the booking process. Eg. Consultation call with John." ))+" ")]),_c('input',{directives:[{name:"model",rawName:"v-model",value:(_vm.form.name),expression:"form.name"}],ref:"service_name",attrs:{"id":"form_name","type":"text"},domProps:{"value":(_vm.form.name)},on:{"blur":function($event){return _vm.$v.form.name.$touch()},"input":function($event){if($event.target.composing){ return; }_vm.$set(_vm.form, "name", $event.target.value)}}})]),_c('form-row',{attrs:{"validator":_vm.$v.form.is_manage_private}},[_c('label',[_vm._v(" "+_vm._s(_vm.T__("Privately-managed event type"))+" "),_c('a',{staticClass:"icon-q-mark",attrs:{"href":"https://help.wpcal.io/en/article/what-is-a-privately-managed-event-type-how-to-use-it-724zbj/?utm_source=wpcal_plugin&utm_medium=contextual","target":"_blank"}})]),_c('span',{staticClass:"txt-help"},[_vm._v(" "+_vm._s(_vm.T__( "Only you will be able to view and manage this event type and its bookings. It will be not be visible to other admins." ))+" ")]),_c('div',{staticClass:"private-et-slider-cont",class:{
            private_event_disabled: _vm.is_disable_manage_private
          },staticStyle:{"margin-bottom":"5px"}},[_vm._v(" "+_vm._s(_vm.T__("Will this event type be privately managed?"))+" "),_c('span',{staticStyle:{"margin-left":"auto"},on:{"click":_vm.may_show_manage_private_not_changeable_error}},[_c('OnOffSlider',{staticStyle:{"margin-left":"auto"},attrs:{"name":'service_private',"true_value":"1","false_value":"0","disabled":_vm.is_disable_manage_private},model:{value:(_vm.form.is_manage_private),callback:function ($$v) {_vm.$set(_vm.form, "is_manage_private", $$v)},expression:"form.is_manage_private"}})],1)]),(!_vm.is_current_admin_is_host)?_c('div',[_vm._v(" "+_vm._s(_vm.T__( "You cannot set another admin's event type as privately managed." ))+" ")]):_vm._e()]),_c('form-row',{attrs:{"validator":_vm.$v.form.service_admin_user_ids}},[_c('label',[_vm._v(" "+_vm._s(_vm.T__("Host"))+" "),_c('abbr',{staticClass:"required",attrs:{"title":"required"}},[_vm._v("*")]),_c('em',[_vm._v(_vm._s(_vm.T__("required")))])]),_c('span',{staticClass:"txt-help"},[_vm._v(" "+_vm._s(_vm.T__( "This event type will be assigned to the admin you select below. The same admin's profile will be used while displaying the event type." ))+" ")]),_c('div',{staticClass:"et-create-host-selector",staticStyle:{"margin-bottom":"5px"},on:{"click":_vm.may_show_host_selector_not_changeable_error}},[_c('HostSelector1ToN',{attrs:{"max_host":1,"disabled":_vm.is_disable_host_selector},model:{value:(_vm.form.service_admin_user_ids),callback:function ($$v) {_vm.$set(_vm.form, "service_admin_user_ids", $$v)},expression:"form.service_admin_user_ids"}})],1),(_vm.is_disable_host_selector)?_c('div',[_vm._v(" "+_vm._s(_vm.T__( "You cannot change the host for your privately managed event types." ))+" ")]):_vm._e(),_c('div',{staticStyle:{"margin-top":"5px"}},[_c('router-link',{attrs:{"to":"/settings/admins"}},[_vm._v("Add new host to WPCal")])],1)]),(_vm.view == 'add')?_c('div',{staticClass:"wpc-form-row"},[_c('label',{staticStyle:{"cursor":"default"},attrs:{"for":"form_unique_link"}},[_vm._v(" "+_vm._s(_vm.T__("Event type booking page"))+" ")]),_c('span',{staticClass:"txt-help"},[_vm._v(" "+_vm._s(_vm.T__( "A page will be created and the booking widget will be added to it. You can manage it after creating this event type." ))+" ")])]):_vm._e(),_c('form-row',[_c('label',[_vm._v(" "+_vm._s(_vm.T__("Location (online & offline)"))+" "),_c('a',{staticClass:"icon-q-mark",attrs:{"href":"https://help.wpcal.io/en/article/how-to-setup-meeting-locations-online-offline-for-event-types-7b10qh/?utm_source=wpcal_plugin&utm_medium=contextual","target":"_blank"}}),_c('em',[_vm._v(_vm._s(_vm.T__("optional")))])]),_c('span',{staticClass:"txt-help"},[_vm._v(" "+_vm._s(_vm.T__( "Use this to specify how and where both parties will connect at the scheduled time. This will be displayed in the booking screen." ))+" ")]),_c('AdminLocationSelector',{attrs:{"form":_vm.form},model:{value:(_vm.form.locations),callback:function ($$v) {_vm.$set(_vm.form, "locations", $$v)},expression:"form.locations"}})],1),_c('form-row',{attrs:{"validator":_vm.$v.form.descr}},[_c('label',{attrs:{"for":"form_descr"}},[_vm._v(" "+_vm._s(_vm.T__("Description/Instructions"))+" "),_c('em',[_vm._v(_vm._s(_vm.T__("optional")))])]),_c('span',{staticClass:"txt-help"},[_vm._v(" "+_vm._s(_vm.T__( "Use this to provide a short description of your event. This will be displayed in the booking screen." ))+" ")]),_c('textarea',{directives:[{name:"model",rawName:"v-model",value:(_vm.form.descr),expression:"form.descr"}],attrs:{"id":"form_descr"},domProps:{"value":(_vm.form.descr)},on:{"input":function($event){if($event.target.composing){ return; }_vm.$set(_vm.form, "descr", $event.target.value)}}})]),_c('form-row',{attrs:{"validator":_vm.$v.form.color}},[_c('label',[_vm._v(" "+_vm._s(_vm.T__("Event type color"))+" "),_c('em',[_vm._v(_vm._s(_vm.T__("required")))])]),_c('ColorSelector',{attrs:{"form":_vm.form,"color_prop":"color"}})],1),_c('div',{staticClass:"wpc-form-row"},[(_vm.view == 'add')?_c('button',{staticClass:"wpc-btn primary lg",attrs:{"type":"button"},on:{"click":function($event){return _vm.$emit('validate-may-navigate-or-submit', 2)}}},[_vm._v(" "+_vm._s(_vm.T__("Continue"))+" ")]):(_vm.view == 'edit')?_c('button',{staticClass:"wpc-btn primary lg",attrs:{"type":"button","disabled":!_vm.create_or_update_btn_is_enabled},on:{"click":function($event){return _vm.$emit('submit-form', 2)}}},[_vm._v(" "+_vm._s(_vm.T__("Save"))+" ")]):_vm._e(),(_vm.view == 'edit')?_c('button',{staticClass:"wpc-btn tert-link sm mt10 center",attrs:{"type":"button"},on:{"click":function($event){return _vm.$emit('edit-view-toggle-steps')}}},[_vm._v(" "+_vm._s(_vm.T__("Cancel"))+" ")]):_vm._e()])],1)]),_c('div',{directives:[{name:"show",rawName:"v-show",value:(_vm.step.summary),expression:"step.summary"}],staticClass:"step-single summary"},[_c('div',{staticClass:"step-title"},[_c('span',[_vm._v(_vm._s(_vm.T__("Event type details")))]),_c('a',{on:{"click":function($event){return _vm.$emit('set-active-step-and-toggle', 2)}}},[_vm._v(_vm._s(_vm.T__("EDIT")))])]),_c('div',{staticClass:"step-summary-content"},[_c('div',[_vm._v(_vm._s(_vm.form.name))]),(_vm.form.locations.length)?_c('div',[_c('span',{staticStyle:{"display":"flex"},domProps:{"innerHTML":_vm._f("locations_summary")(_vm.form.locations)}})]):_vm._e()])])])}
var ServiceFormStep2vue_type_template_id_1edd344c_scoped_true_staticRenderFns = []


// CONCATENATED MODULE: ./src/components/admin/ServiceFormStep2.vue?vue&type=template&id=1edd344c&scoped=true&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"1bf56cee-vue-loader-template"}!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/vue-loader/lib??vue-loader-options!./src/components/form_elements/ColorSelector.vue?vue&type=template&id=13a331cc&scoped=true&
var ColorSelectorvue_type_template_id_13a331cc_scoped_true_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',[_c('ul',{staticClass:"event-type-colors"},_vm._l((_vm.colors),function(val,index){return _c('li',{key:index},[_c('input',{directives:[{name:"model",rawName:"v-model",value:(_vm.form[_vm.color_prop]),expression:"form[color_prop]"}],attrs:{"type":"radio","id":'clr-' + index,"name":"event-type-clr"},domProps:{"value":val,"checked":_vm._q(_vm.form[_vm.color_prop],val)},on:{"change":function($event){return _vm.$set(_vm.form, _vm.color_prop, val)}}}),_c('label',{class:'clr-' + val,attrs:{"for":'clr-' + index}})])}),0)])}
var ColorSelectorvue_type_template_id_13a331cc_scoped_true_staticRenderFns = []


// CONCATENATED MODULE: ./src/components/form_elements/ColorSelector.vue?vue&type=template&id=13a331cc&scoped=true&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--13-0!./node_modules/thread-loader/dist/cjs.js!./node_modules/babel-loader/lib!./node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/vue-loader/lib??vue-loader-options!./src/components/form_elements/ColorSelector.vue?vue&type=script&lang=js&
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
/* harmony default export */ var ColorSelectorvue_type_script_lang_js_ = ({
  props: {
    form: {},
    color_prop: {}
  },
  data: function data() {
    return {
      colors: ["chill", "sunflower", "nephritis", "carrot", "belize", "alizarin", "wisteria", "prunus", "midnightblue", "concrete"]
    };
  }
});
// CONCATENATED MODULE: ./src/components/form_elements/ColorSelector.vue?vue&type=script&lang=js&
 /* harmony default export */ var form_elements_ColorSelectorvue_type_script_lang_js_ = (ColorSelectorvue_type_script_lang_js_); 
// EXTERNAL MODULE: ./src/components/form_elements/ColorSelector.vue?vue&type=style&index=0&id=13a331cc&scoped=true&lang=css&
var ColorSelectorvue_type_style_index_0_id_13a331cc_scoped_true_lang_css_ = __webpack_require__("4477");

// CONCATENATED MODULE: ./src/components/form_elements/ColorSelector.vue






/* normalize component */

var ColorSelector_component = Object(componentNormalizer["a" /* default */])(
  form_elements_ColorSelectorvue_type_script_lang_js_,
  ColorSelectorvue_type_template_id_13a331cc_scoped_true_render,
  ColorSelectorvue_type_template_id_13a331cc_scoped_true_staticRenderFns,
  false,
  null,
  "13a331cc",
  null
  
)

/* harmony default export */ var ColorSelector = (ColorSelector_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"1bf56cee-vue-loader-template"}!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/vue-loader/lib??vue-loader-options!./src/components/form_elements/AdminLocationSelector.vue?vue&type=template&id=28cfacb0&
var AdminLocationSelectorvue_type_template_id_28cfacb0_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',[_c('div',{staticClass:"wpc-form-row",staticStyle:{"margin-bottom":"0"}},[_c('div',{directives:[{name:"drag-and-drop",rawName:"v-drag-and-drop:options",value:(
        _vm.selected_locations.length > 1
          ? _vm.selected_locations_drag_drop_options
          : {}
      ),expression:"\n        selected_locations.length > 1\n          ? selected_locations_drag_drop_options\n          : {}\n      ",arg:"options"}],key:_vm.selected_locations.length > 1 ? 100 : 0,staticClass:"mbox cal-accs",staticStyle:{"margin-bottom":"0"}},[_c('div',{staticClass:"set-locations-cont",on:{"reordered":_vm.reorder_selected_locations}},_vm._l((_vm.selected_locations),function(selected_location,index){
      var _obj;
return _c('div',{key:index,staticClass:"set-locations-single",class:( _obj = {}, _obj['location_' + selected_location.type] = true, _obj['del-alert'] =  _vm.show_delete_alert(index), _obj ),attrs:{"data-id":index}},[_c('div',{class:{ 'reorder-handle': _vm.selected_locations.length > 1 }}),_c('div',{staticClass:"txt-h5"},[_vm._v(" "+_vm._s(_vm._f("location_name_by_type")(selected_location.type))+" "),(_vm.is_having_edit_options(selected_location))?_c('a',{staticStyle:{"margin-right":"30px","margin-left":"auto"},on:{"click":function($event){return _vm.may_show_popup_form(selected_location, 'edit', index)}}},[_vm._v(_vm._s(_vm.T__("EDIT")))]):_vm._e()]),_c('div',{staticClass:"icon-delete",on:{"click":function($event){_vm.remove_from_selected_locations(index);
              _vm.set_mouseover(index, false);},"mouseover":function($event){return _vm.set_mouseover(index, true)},"mouseleave":function($event){return _vm.set_mouseover(index, false)}}}),(
              _vm.is_tp_service_account_connected(selected_location.type) !==
                false
            )?_c('div',{staticClass:"txt-help"},[_vm._v(" "+_vm._s(_vm.get_meta_data_for_location(selected_location))+" ")]):_vm._e(),(
              _vm.is_tp_service_account_connected(selected_location.type) ===
                false
            )?_c('div',{staticClass:"txt-help",staticStyle:{"margin-top":"5px","color":"#ea1919"}},[_vm._v(" "+_vm._s(_vm.tp_service_not_connected_text(selected_location))+" "),_c('span',{domProps:{"innerHTML":_vm._s(
                _vm.tp_service_not_connected_to_do_content(selected_location)
              )}})]):_vm._e()])}),0),(_vm.selected_locations.length)?_c('span',{staticClass:"txt-help",staticStyle:{"padding-top":"0","padding-bottom":"5px"}},[_vm._v(_vm._s(_vm.T__( "You can give more location options from which your invitees can choose one." )))]):_vm._e(),(!_vm.show_add_location_list)?_c('button',{staticClass:"wpc-btn secondary md full-width",attrs:{"disabled":!_vm.enable_add_new_location,"title":!_vm.enable_add_new_location
            ? this.T__('You can add only upto 10 locations.')
            : ''},on:{"click":function($event){_vm.show_add_location_list = true}}},[_vm._v(" "+_vm._s(_vm.T__("+ Add Location"))+" ")]):_vm._e(),(_vm.show_add_location_list)?_c('div',[_c('div',{staticClass:"txt-h5 caps"},[_vm._v(_vm._s(_vm.T__("Add a Location")))]),_c('ul',{staticClass:"selector block compact location"},_vm._l((_vm.all_locations_final),function(location,index2){return _c('li',{key:index2},[_c('label',{class:'location_' + location.type,on:{"click":function($event){return _vm.add_to_selected_locations(location)}}},[_c('div',{staticClass:"txt-h5"},[_vm._v(" "+_vm._s(_vm._f("location_name_by_type")(location.type))+" ")]),_c('div',{staticClass:"txt-help"},[_vm._v(" "+_vm._s(_vm._f("location_short_descr_by_type")(location.type))+" ")])])])}),0)]):_vm._e()])]),_c('modal',{staticStyle:{"z-index":"9999"},attrs:{"name":"location_advance_modal","adaptive":true,"height":"auto","width":"360px"},on:{"before-close":_vm.location_advance_modal_on_before_close}},[_c('div',{staticClass:"modal_close_cont"},[_c('button',{on:{"click":function($event){return _vm.$modal.hide('location_advance_modal')}}})]),_c('div',{staticClass:"mbox bg-light shadow mb0"},[_c('div',{staticStyle:{"font-size":"16px","text-align":"center","margin-bottom":"10px","padding":"5px 0"}},[_vm._v(" "+_vm._s(_vm._f("location_name_by_type")(_vm.current_popup_location.type))+" ")]),(_vm.current_popup_location.form)?_c('div',[(_vm.current_popup_location.form.hasOwnProperty('who_calls'))?_c('form-row',{attrs:{"validator":_vm.$v.current_popup_location.form.who_calls}},[_c('ul',{staticClass:"selector block mt10 "},[_c('li',[_c('input',{directives:[{name:"model",rawName:"v-model",value:(_vm.current_popup_location.form.who_calls),expression:"current_popup_location.form.who_calls"}],attrs:{"type":"radio","id":"who_calls_admin","name":"who_calls","value":"admin"},domProps:{"checked":_vm._q(_vm.current_popup_location.form.who_calls,"admin")},on:{"change":function($event){return _vm.$set(_vm.current_popup_location.form, "who_calls", "admin")}}}),_c('label',{attrs:{"for":"who_calls_admin"}},[_c('div',{staticClass:"txt-h5"},[_vm._v(_vm._s(_vm.T__("I will call my invitee")))]),_c('div',{staticClass:"txt-help",staticStyle:{"padding-top":"0"}},[_vm._v(" "+_vm._s(_vm.T__( "We'll ask your invitee’s phone number before scheduling." ))+" ")])])]),_c('li',[_c('input',{directives:[{name:"model",rawName:"v-model",value:(_vm.current_popup_location.form.who_calls),expression:"current_popup_location.form.who_calls"}],attrs:{"type":"radio","id":"who_calls_invitee","name":"who_calls","value":"invitee"},domProps:{"checked":_vm._q(_vm.current_popup_location.form.who_calls,"invitee")},on:{"focus":function($event){return _vm.ref_focus('location_txt')},"click":function($event){return _vm.ref_focus('location_txt')},"change":function($event){return _vm.$set(_vm.current_popup_location.form, "who_calls", "invitee")}}}),_c('label',{attrs:{"for":"who_calls_invitee"}},[_c('div',{staticClass:"txt-h5"},[_vm._v(" "+_vm._s(_vm.T__("My invitee should call me"))+" ")]),_c('div',{staticClass:"txt-help",staticStyle:{"padding-top":"0"}},[_vm._v(" "+_vm._s(_vm.T__( "We'll provide your number after the call has been scheduled." ))+" ")])])])])]):_vm._e(),(_vm.popup_show_location_field)?_c('form-row',{attrs:{"validator":_vm.$v.current_popup_location.form.location},scopedSlots:_vm._u([{key:"after",fn:function(){return [_c('span',{staticClass:"txt-help"},[_vm._v(" "+_vm._s(_vm.location_help_txt)+" ")])]},proxy:true}],null,false,1695413433)},[_c('label',{staticStyle:{"font-size":"14px"},attrs:{"for":"location_txt"}},[_vm._v(_vm._s(_vm.location_feild_label))]),_c('input',{directives:[{name:"model",rawName:"v-model",value:(_vm.current_popup_location.form.location),expression:"current_popup_location.form.location"}],ref:"location_txt",attrs:{"type":"text","id":"location_txt","maxlength":_vm.get_maxlength_from_validation(
                _vm.$v.current_popup_location.form.location
              )},domProps:{"value":(_vm.current_popup_location.form.location)},on:{"change":function($event){return _vm.$v.current_popup_location.form.location.$touch()},"input":function($event){if($event.target.composing){ return; }_vm.$set(_vm.current_popup_location.form, "location", $event.target.value)}}}),(_vm.current_popup_location.type != 'phone')?_c('CharLeft',{attrs:{"maxlength":_vm.get_maxlength_from_validation(
                _vm.$v.current_popup_location.form.location
              ),"input_text":_vm.current_popup_location.form.location}}):_vm._e()],1):_vm._e(),(_vm.current_popup_location.form.hasOwnProperty('location_extra'))?_c('span',[_c('div',{staticClass:"wpc-form-row"},[(!_vm.show_location_extra_field)?_c('a',{on:{"click":function($event){_vm.show_location_extra_field = true;
                _vm.ref_focus('location_extra');}}},[_vm._v(" "+_vm._s(_vm.T__("+ Add additional info"))+" ")]):_vm._e()]),(_vm.show_location_extra_field)?_c('form-row',{attrs:{"validator":_vm.$v.current_popup_location.form.location_extra}},[_c('label',{staticStyle:{"font-size":"14px"},attrs:{"for":"location_extra"}},[_vm._v(_vm._s(_vm.T__("Location additional info")))]),_c('span',{staticClass:"txt-help",staticStyle:{"padding-top":"0","padding-bottom":"5px"}},[_vm._v(" "+_vm._s(_vm.location_extra_help_txt)+" ")]),_c('textarea',{directives:[{name:"model",rawName:"v-model",value:(_vm.current_popup_location.form.location_extra),expression:"current_popup_location.form.location_extra"}],ref:"location_extra",attrs:{"type":"text","id":"location_extra","maxlength":_vm.get_maxlength_from_validation(
                  _vm.$v.current_popup_location.form.location_extra
                )},domProps:{"value":(_vm.current_popup_location.form.location_extra)},on:{"change":function($event){return _vm.$v.current_popup_location.form.location_extra.$touch()},"input":function($event){if($event.target.composing){ return; }_vm.$set(_vm.current_popup_location.form, "location_extra", $event.target.value)}}}),_vm._v(" "),_c('CharLeft',{attrs:{"maxlength":_vm.get_maxlength_from_validation(
                  _vm.$v.current_popup_location.form.location_extra
                ),"input_text":_vm.current_popup_location.form.location_extra}})],1):_vm._e()],1):_vm._e(),_c('button',{staticClass:"wpc-btn primary md mt20",attrs:{"type":"button"},on:{"click":_vm.save_current_popup_location}},[_vm._v(" "+_vm._s(_vm.current_popup_location_action == "add" ? "Add Location" : "Update")+" ")])],1):_vm._e()])])],1)}
var AdminLocationSelectorvue_type_template_id_28cfacb0_staticRenderFns = []


// CONCATENATED MODULE: ./src/components/form_elements/AdminLocationSelector.vue?vue&type=template&id=28cfacb0&

// EXTERNAL MODULE: ./node_modules/core-js/modules/es.array.splice.js
var es_array_splice = __webpack_require__("a434");

// EXTERNAL MODULE: ./node_modules/vue-draggable/lib/vue-draggable.js
var vue_draggable = __webpack_require__("d8c5");

// EXTERNAL MODULE: ./node_modules/vuelidate/lib/validators/index.js
var validators = __webpack_require__("b5ae");

// EXTERNAL MODULE: ./src/utils/common_func.js
var common_func = __webpack_require__("d1ff");

// CONCATENATED MODULE: ./src/utils/admin_tp_locations_state_mixin.js

var admin_tp_locations_state_mixin_admin_tp_locations_state_mixin = function admin_tp_locations_state_mixin() {
  var is_current_admin = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : true;
  return {
    data: function data() {
      return {
        tp_locations: {},
        is_tp_locations_loaded: false,
        tp_locations_refresh_interval_instance: null
      };
    },
    computed: {
      is_tp_service_account_connected: function is_tp_service_account_connected() {
        var _this = this;

        return function (location_type) {
          if (location_type !== "zoom_meeting" && location_type !== "gotomeeting_meeting" && location_type !== "googlemeet_meeting") {
            return null;
          }

          if (!_this.is_tp_locations_loaded) {
            return true; //till loading - to avoid showing accounts not connected
          }

          if (_this.tp_locations.hasOwnProperty(location_type)) {
            return _this.tp_locations[location_type].is_connected;
          }

          return false;
        };
      },
      googlemeet_meeting_is_account_connected: function googlemeet_meeting_is_account_connected() {
        if (this.tp_locations.hasOwnProperty("googlemeet_meeting")) {
          return this.tp_locations["googlemeet_meeting"].is_connected_and_active;
        }

        return null;
      },
      googlemeet_meeting_account_email: function googlemeet_meeting_account_email() {
        return this.tp_locations && this.tp_locations.googlemeet_meeting && this.tp_locations.googlemeet_meeting.account_email ? this.tp_locations.googlemeet_meeting.account_email : "";
      }
    },
    methods: {
      load_tp_locations_by_admin: function load_tp_locations_by_admin() {
        var _this2 = this;

        var background = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : false;
        var wpcal_request = {};
        var action = "";

        if (is_current_admin) {
          action = "get_tp_locations_details_for_current_admin";
          wpcal_request[action] = "dummy__";
        } else if (!is_current_admin && this.tp_locations_required_admin_user_id) {
          action = "get_tp_locations_details_by_admin";
          wpcal_request[action] = {
            admin_user_id: this.tp_locations_required_admin_user_id
          };
        } else {
          return;
        }

        var post_data = {
          action: "wpcal_process_admin_ajax_request",
          wpcal_request: wpcal_request
        };
        var axios_options = {};

        if (background) {
          axios_options = {
            params: {
              _remove_options_before_call: {
                background_call: true
              }
            }
          };
        }

        axios_default.a.post(window.wpcal_ajax.ajax_url, post_data, axios_options).then(function (response) {
          if (response.data && response.data[action].hasOwnProperty("tp_locations")) {
            // for (let key in response.data[action].tp_locations) {
            //   console.log(key, response.data[action].tp_locations[key]);
            //   this.$set(
            //     this.tp_locations,
            //     key,
            //     response.data[action].tp_locations[key]
            //   );
            // }
            _this2.tp_locations = response.data[action].tp_locations;
          }

          _this2.is_tp_locations_loaded = true;
        }).catch(function (error) {
          console.log(error);
        });
      }
    },
    mounted: function mounted() {
      var _this3 = this;

      setTimeout(function () {
        _this3.load_tp_locations_by_admin();

        var tp_locations_refresh_interval_instance = setInterval(function () {
          if (document.hasFocus()) {
            _this3.load_tp_locations_by_admin(true);
          }
        }, 5000);

        _this3.$store.commit("set_tp_locations_refresh_interval_instance", tp_locations_refresh_interval_instance); //using Vuex instead of this.tp_locations_refresh_interval_instance because it is not updating properly, when this code wrapped with setTimeout sometimes 700 ms it works, better use Vuex

      }, 100);
    },
    beforeDestroy: function beforeDestroy() {
      clearInterval(this.$store.getters.get_tp_locations_refresh_interval_instance);
      this.$store.commit("set_tp_locations_refresh_interval_instance", null);
    }
  };
};
// EXTERNAL MODULE: ./src/components/form_elements/CharLeft.vue + 4 modules
var CharLeft = __webpack_require__("84af");

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--13-0!./node_modules/thread-loader/dist/cjs.js!./node_modules/babel-loader/lib!./node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/vue-loader/lib??vue-loader-options!./src/components/form_elements/AdminLocationSelector.vue?vue&type=script&lang=js&



//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//






/* harmony default export */ var AdminLocationSelectorvue_type_script_lang_js_ = ({
  mixins: [admin_tp_locations_state_mixin_admin_tp_locations_state_mixin(false)],
  components: {
    CharLeft: CharLeft["a" /* default */]
  },
  directives: {
    dragAndDrop: vue_draggable["VueDraggableDirective"]
  },
  model: {
    prop: "locations",
    event: "locations-value-changed"
  },
  props: {
    locations: {
      type: Array
    },
    form: {
      type: Object,
      required: true
    }
  },
  data: function data() {
    return {
      all_locations: [{
        type: "physical",
        limit: -1,
        count: 0,
        show: true,
        form: {
          location: "",
          location_extra: ""
        }
      }, {
        type: "phone",
        limit: 1,
        count: 0,
        show: true,
        form: {
          who_calls: "admin",
          //admin - outbound, invitee - inbound so admin should provide number
          location: ""
        }
      }, {
        type: "googlemeet_meeting",
        limit: 1,
        count: 0,
        show: true
      }, {
        type: "zoom_meeting",
        limit: 1,
        count: 0,
        show: true
      }, {
        type: "gotomeeting_meeting",
        limit: 1,
        count: 0,
        show: true
      }, {
        type: "custom",
        limit: -1,
        count: 0,
        show: true,
        form: {
          location: "",
          location_extra: ""
        }
      }, {
        type: "ask_invitee",
        limit: 1,
        count: 0,
        show: true,
        form: {
          location: "",
          location_extra: ""
        }
      }],
      show_add_location_list: false,
      current_popup_location: {},
      current_popup_location_action: "",
      current_popup_location_index: null,
      show_location_extra_field: false,
      selected_locations_drag_drop_options: {
        dropzoneSelector: ".set-locations-cont",
        draggableSelector: ".set-locations-single",
        handlerSelector: ".reorder-handle",
        reactivityEnabled: true,
        multipleDropzonesItemsDraggingEnabled: false,
        showDropzoneAreas: true
      },
      delete_alert_index: null
    };
  },
  validations: {
    current_popup_location: {
      type: {
        required: validators["required"]
      },
      form: {
        required: Object(validators["requiredIf"])(function () {
          var types_required_form = ["physical", "phone", "custom", "ask_invitee"];
          return types_required_form.indexOf(this.current_popup_location.type) !== -1;
        }),
        who_calls: {
          required: Object(validators["requiredIf"])(function () {
            if (this.current_popup_location.type === "phone") {
              return true;
            }

            return false;
          }),
          who_calls_in_array: Object(common_func["o" /* in_array */])(["admin", "invitee"])
        },
        location: {
          required: Object(validators["requiredIf"])(function () {
            var types_required_location = ["physical", "custom"];

            if (types_required_location.indexOf(this.current_popup_location.type) !== -1) {
              return true;
            } else if (this.current_popup_location.type === "phone" && this.current_popup_location.form.who_calls === "invitee") {
              return true;
            }

            return false;
          }),
          max_char_length_500: Object(validators["maxLength"])(500)
        },
        location_extra: {
          max_char_length_500: Object(validators["maxLength"])(500)
        }
      }
    }
  },
  computed: {
    selected_locations: {
      get: function get() {
        return this.locations;
      },
      set: function set(new_value) {
        this.$emit("locations-value-changed", new_value);
      }
    },
    all_locations_with_stats: function all_locations_with_stats() {
      var list = this.clone_deep(this.all_locations);

      var _iterator = Object(createForOfIteratorHelper["a" /* default */])(this.selected_locations),
          _step;

      try {
        var _loop = function _loop() {
          var selected_location = _step.value;
          var list_index = list.findIndex(function (list_item) {
            return list_item.type === selected_location.type;
          });

          if (list_index != -1) {
            list[list_index].count++;

            if (list[list_index].limit > -1 && list[list_index].limit <= list[list_index].count) {
              list[list_index].show = false;
            } else {
              list[list_index].show = true;
            }
          }
        };

        for (_iterator.s(); !(_step = _iterator.n()).done;) {
          _loop();
        }
      } catch (err) {
        _iterator.e(err);
      } finally {
        _iterator.f();
      }

      return list;
    },
    all_locations_final: function all_locations_final() {
      var list = [];

      for (var index in this.all_locations_with_stats) {
        if (this.all_locations_with_stats[index].show) list.push(this.all_locations_with_stats[index]);
      }

      return list;
    },
    enable_add_new_location: function enable_add_new_location() {
      return this.selected_locations.length < 10;
    },
    location_feild_label: function location_feild_label() {
      var label = this.T__("Location");

      if (this.current_popup_location.type == "custom") {
        label = this.T__("Enter a location of your choice");
      } else if (this.current_popup_location.type == "phone") {
        label = this.T__("Enter your phone number");
      }

      return label;
    },
    popup_show_location_field: function popup_show_location_field() {
      var show_location = false;

      if (this.current_popup_location.type == "physical" || this.current_popup_location.type == "custom") {
        show_location = true;
      } else if (this.current_popup_location.type == "phone" && this.current_popup_location.form && this.current_popup_location.form.who_calls == "invitee") {
        show_location = true;
      }

      return show_location;
    },
    location_help_txt: function location_help_txt() {
      if (this.current_popup_location.type == "phone") {
        return this.T__("Please enter phone number with country code. E.g.") + " +1 555 555 1234";
      }

      return "";
    },
    location_extra_help_txt: function location_extra_help_txt() {
      if (this.current_popup_location.type == "physical" || this.current_popup_location.type == "custom") {
        return this.T__("This will be shown to the invitee AFTER the booking is confirmed.");
      } else if (this.current_popup_location.type == "ask_invitee") {
        return this.T__("You can use this to guide the invitee. The text you enter here will be displayed below the location text field.");
      }

      return "";
    },
    tp_locations_required_admin_user_id: function tp_locations_required_admin_user_id() {
      return this.form.service_admin_user_ids[0];
    },
    is_current_admin_is_this_service_host: function is_current_admin_is_this_service_host() {
      return this.$store.getters.get_current_admin_details.id == this.form.service_admin_user_ids[0];
    },
    tp_service_not_connected_text: function tp_service_not_connected_text() {
      var _this = this;

      return function (selected_location) {
        if (_this.is_current_admin_is_this_service_host) {
          return _this.Tsprintf(
          /* translators: %s: app name */
          _this.T__("%s is not connected. Until you do, it will not be displayed to your invitees."), _this.$options.filters.location_name_by_type(selected_location.type));
        } else {
          return _this.Tsprintf(
          /* translators: %s: app name */
          _this.T__("%s is not connected. Until the host connects it, it will not be displayed to your invitees."), _this.$options.filters.location_name_by_type(selected_location.type));
        }
      };
    },
    tp_service_not_connected_to_do_content: function tp_service_not_connected_to_do_content() {
      var _this2 = this;

      return function (selected_location) {
        if (selected_location.type == "googlemeet_meeting") {
          if (_this2.is_current_admin_is_this_service_host) {
            return _this2.Tsprintf(
            /* translators: 1: html-tag-open 2: html-tag-close */
            _this2.T__("Go to %1$sCalendar settings%2$s connect a calendar account (if not done already), and set &quot;Add Bookings to...&quot; calendar."), '<a href="admin.php?page=wpcal_admin#/settings/calendars" target="_blank" style="text-decoration:underline;">', "</a>");
          } else {
            return _this2.Tsprintf(_this2.T__("%1$sLearn more.%2$s"), '<a href="https://help.wpcal.io/en/article/how-do-i-connect-my-google-meet-with-wpcalio-hrh77h/?utm_source=wpcal_plugin&utm_medium=contextual" target="_blank">', "</a>");
          }
        } else {
          if (_this2.is_current_admin_is_this_service_host) {
            return _this2.Tsprintf(
            /* translators: 1: html-tag-open 2: html-tag-close */
            _this2.T__("Go to the %1$sIntegrations%2$s page to connect it now."), '<a href="admin.php?page=wpcal_admin#/settings/integrations" target="_blank" style="text-decoration:underline">', "</a>");
          } else {
            var help_link = "https://help.wpcal.io/en/article/how-to-connect-use-meeting-apps-bwour0/";

            if (selected_location.type == "zoom_meeting") {
              help_link = "https://help.wpcal.io/en/category/integrations-zoom-1dfihfm/";
            }

            return _this2.Tsprintf(_this2.T__("%1$sLearn more.%2$s"), '<a href="' + help_link + '?utm_source=wpcal_plugin&utm_medium=contextual" target="_blank">', "</a>");
          }
        }
      };
    }
  },
  methods: {
    add_to_selected_locations: function add_to_selected_locations(location) {
      var new_location = {};
      new_location.type = location.type;

      if (location.hasOwnProperty("form") && location.form) {
        new_location.form = this.clone_deep(location.form);
      }

      this.may_show_popup_form(new_location, "add");
      this.show_add_location_list = false;
    },
    remove_from_selected_locations: function remove_from_selected_locations(index) {
      this.show_add_location_list = false;
      this.selected_locations.splice(index, 1);
    },
    may_show_popup_form: function may_show_popup_form(location, action) {
      var index = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : null;
      location = this.clone_deep(location);

      if (action != "add" && action != "edit") {
        return;
      }

      if (action == "edit" && isNaN(index)) {
        return;
      }

      if (!this.is_having_edit_options(location)) {
        if (action == "add") {
          this.selected_locations.push(location);
        } else if (action == "edit") {
          this.selected_locations[index] = location;
        }

        return;
      }

      this.$v["current_popup_location"].$reset();
      this.current_popup_location = location;
      this.current_popup_location_action = action;
      this.current_popup_location_index = index;

      if (this.current_popup_location.form && this.current_popup_location.form.hasOwnProperty("location_extra") && this.current_popup_location.form.location_extra) {
        this.show_location_extra_field = true;
      }

      this.$modal.show("location_advance_modal");

      if (this.current_popup_location.type == "physical" || this.current_popup_location.type == "custom") {
        this.ref_focus("location_txt");
      }
    },
    save_current_popup_location: function save_current_popup_location() {
      var action = this.current_popup_location_action;
      var index = this.current_popup_location_index;
      this.$v["current_popup_location"].$touch(); //trigger validation

      if (this.$v.current_popup_location.$anyError) {
        return;
      }

      if (action == "add") {
        this.selected_locations.push(this.current_popup_location);
      } else if (action == "edit") {
        this.selected_locations[index] = this.current_popup_location;
      }

      this.$modal.hide("location_advance_modal");
    },
    is_having_edit_options: function is_having_edit_options(location) {
      if (location.hasOwnProperty("form") && location.form) {
        return true;
      }

      return false;
    },
    reset_current_popup_location: function reset_current_popup_location() {
      this.current_popup_location = {};
      this.current_popup_location_action = "";
      this.current_popup_location_index = null;
      this.show_location_extra_field = false;
    },
    location_advance_modal_on_before_close: function location_advance_modal_on_before_close() {
      this.reset_current_popup_location();
    },
    get_meta_data_for_location: function get_meta_data_for_location(location) {
      if (!this.is_having_edit_options(location)) {
        return this.$options.filters.location_short_descr_by_type(location.type);
      }

      var meta = "";

      if (location.type === "phone" && location.form.hasOwnProperty("who_calls") && location.form.who_calls) {
        meta += location.form.who_calls == "admin" ? this.T__("I will call my invitee") : this.T__("My invitee should call me at ");

        if (location.form.who_calls == "invitee" && location.form.hasOwnProperty("location") && location.form.location) {
          meta += location.form.location;
        }
      }

      if (location.type !== "phone" && location.form && location.form.hasOwnProperty("location") && location.form.location) {
        meta += location.form.location;
      }

      if (location.form && location.form.hasOwnProperty("location_extra") && location.form.location_extra) {
        meta += " " + this.T__("(more info)");
      }

      return meta ? meta : this.$options.filters.location_short_descr_by_type(location.type);
    },
    reorder_selected_locations: function reorder_selected_locations(event) {
      var move_item_old_index = event.detail.ids[0];
      var move_item_new_index = event.detail.index;
      var moved_item = this.selected_locations.splice(move_item_old_index, 1);
      var final_item_new_index = move_item_old_index < move_item_new_index ? move_item_new_index - 1 : move_item_new_index; // console.log(
      //   move_item_old_index,
      //   "to",
      //   move_item_new_index,
      //   "final",
      //   final_item_new_index
      // );

      this.selected_locations.splice(final_item_new_index, 0, moved_item[0]);
    },
    show_delete_alert: function show_delete_alert(index) {
      return this.delete_alert_index === index;
    },
    set_mouseover: function set_mouseover(index, is_hover) {
      if (is_hover) {
        this.delete_alert_index = index;
      } else {
        this.delete_alert_index = null;
      }
    }
  }
});
// CONCATENATED MODULE: ./src/components/form_elements/AdminLocationSelector.vue?vue&type=script&lang=js&
 /* harmony default export */ var form_elements_AdminLocationSelectorvue_type_script_lang_js_ = (AdminLocationSelectorvue_type_script_lang_js_); 
// CONCATENATED MODULE: ./src/components/form_elements/AdminLocationSelector.vue





/* normalize component */

var AdminLocationSelector_component = Object(componentNormalizer["a" /* default */])(
  form_elements_AdminLocationSelectorvue_type_script_lang_js_,
  AdminLocationSelectorvue_type_template_id_28cfacb0_render,
  AdminLocationSelectorvue_type_template_id_28cfacb0_staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* harmony default export */ var AdminLocationSelector = (AdminLocationSelector_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"1bf56cee-vue-loader-template"}!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/vue-loader/lib??vue-loader-options!./src/components/form_elements/HostSelector1ToN.vue?vue&type=template&id=7dfa00b8&
var HostSelector1ToNvue_type_template_id_7dfa00b8_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return (!_vm._isEmpty(_vm.$store.getters.get_managed_active_admins_details))?_c('div',_vm._l((_vm.selected_host),function(admin_user_id,index){return _c('TypeToSelect',{key:index,staticClass:"white",attrs:{"options":_vm.$store.getters.get_managed_active_admins_details,"label_field":"name","value_field":"admin_user_id","disabled":_vm.disabled,"filter-by":_vm.dropdown_filter},scopedSlots:_vm._u([{key:"selected-option",fn:function(option){return [_c('div',{staticClass:"profile-details"},[(option.profile_picture)?_c('img',{staticStyle:{"border-radius":"50%"},attrs:{"src":option.profile_picture,"width":"30"}}):_vm._e(),_c('div',[_c('div',{staticClass:"txt-h5"},[_vm._v(_vm._s(option.name))]),_c('div',{staticClass:"txt-help"},[_vm._v(_vm._s(option.email))])])])]}},{key:"option",fn:function(option){return [_c('div',{staticClass:"profile-details"},[(option.profile_picture)?_c('img',{staticStyle:{"border-radius":"50%"},attrs:{"src":option.profile_picture,"width":"30"}}):_vm._e(),_c('div',[_c('div',{staticClass:"txt-h5"},[_vm._v(_vm._s(option.name))]),_c('div',{staticClass:"txt-help"},[_vm._v(_vm._s(option.email))])])])]}}],null,true),model:{value:(_vm.selected_host[index]),callback:function ($$v) {_vm.$set(_vm.selected_host, index, $$v)},expression:"selected_host[index]"}})}),1):_vm._e()}
var HostSelector1ToNvue_type_template_id_7dfa00b8_staticRenderFns = []


// CONCATENATED MODULE: ./src/components/form_elements/HostSelector1ToN.vue?vue&type=template&id=7dfa00b8&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--13-0!./node_modules/thread-loader/dist/cjs.js!./node_modules/babel-loader/lib!./node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/vue-loader/lib??vue-loader-options!./src/components/form_elements/HostSelector1ToN.vue?vue&type=script&lang=js&

//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//yet to implement multiple

/* harmony default export */ var HostSelector1ToNvue_type_script_lang_js_ = ({
  name: "HostSelector1ToN",
  components: {
    TypeToSelect: TypeToSelect
  },
  model: {
    prop: "selected_host",
    event: "changed-selected-host"
  },
  props: {
    selected_host: {
      type: Array
    },
    max_host: {
      type: Number
    },
    disabled: {
      type: Boolean,
      default: false
    }
  },
  methods: {
    dropdown_filter: function dropdown_filter(option, label, search) {
      var search_text = search.toLowerCase();
      return (label || "").toLowerCase().indexOf(search_text) > -1 || option.email.toLowerCase().indexOf(search_text) > -1;
    }
  }
});
// CONCATENATED MODULE: ./src/components/form_elements/HostSelector1ToN.vue?vue&type=script&lang=js&
 /* harmony default export */ var form_elements_HostSelector1ToNvue_type_script_lang_js_ = (HostSelector1ToNvue_type_script_lang_js_); 
// EXTERNAL MODULE: ./src/components/form_elements/HostSelector1ToN.vue?vue&type=style&index=0&lang=css&
var HostSelector1ToNvue_type_style_index_0_lang_css_ = __webpack_require__("056a");

// CONCATENATED MODULE: ./src/components/form_elements/HostSelector1ToN.vue






/* normalize component */

var HostSelector1ToN_component = Object(componentNormalizer["a" /* default */])(
  form_elements_HostSelector1ToNvue_type_script_lang_js_,
  HostSelector1ToNvue_type_template_id_7dfa00b8_render,
  HostSelector1ToNvue_type_template_id_7dfa00b8_staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* harmony default export */ var HostSelector1ToN = (HostSelector1ToN_component.exports);
// EXTERNAL MODULE: ./node_modules/lodash-es/isEqual.js
var isEqual = __webpack_require__("32e8");

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--13-0!./node_modules/thread-loader/dist/cjs.js!./node_modules/babel-loader/lib!./node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/vue-loader/lib??vue-loader-options!./src/components/admin/ServiceFormStep2.vue?vue&type=script&lang=js&

//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//





/* harmony default export */ var ServiceFormStep2vue_type_script_lang_js_ = ({
  name: "ServiceFormStep2",
  components: {
    ColorSelector: ColorSelector,
    AdminLocationSelector: AdminLocationSelector,
    HostSelector1ToN: HostSelector1ToN,
    OnOffSlider: OnOffSlider
  },
  props: {
    view: {
      type: String
    },
    step: {
      type: Object,
      required: true
    },
    form: {
      type: Object,
      required: true
    },
    saved_form: {
      type: Object
    },
    create_or_update_btn_is_enabled: {},
    $v: {}
  },
  data: function data() {
    return {};
  },
  computed: {
    is_disable_manage_private: function is_disable_manage_private() {
      return this.form.is_manage_private == "0" && !this.is_current_admin_is_host;
    },
    is_current_admin_is_host: function is_current_admin_is_host() {
      return this.form.service_admin_user_ids.indexOf(this.$store.getters.get_current_admin_details.id) != -1;
    },
    is_disable_host_selector: function is_disable_host_selector() {
      return this.is_current_admin_is_host && this.form.is_manage_private == 1;
    }
  },
  methods: {
    auto_focus_form_element: function auto_focus_form_element() {
      var _this = this;

      if (this.view == "add" && !this.form.name && this.$refs.service_name) {
        setTimeout(function () {
          _this.$refs.service_name.focus();
        }, 50);
      }
    },
    may_show_host_selector_not_changeable_error: function may_show_host_selector_not_changeable_error() {
      if (!this.is_disable_host_selector) {
        return;
      }

      this.$modal.show("dialog", {
        title: this.T__("Heads up!"),
        text: this.T__("You cannot change the host for your privately managed event types."),
        buttons: [{
          title: this.T__("Okay"),
          default: true // Will be triggered by default if 'Enter' pressed.

        }]
      });
    },
    may_show_manage_private_not_changeable_error: function may_show_manage_private_not_changeable_error() {
      if (!this.is_disable_manage_private) {
        return;
      }

      this.$modal.show("dialog", {
        title: this.T__("Heads up!"),
        text: this.T__("You cannot set another admin's event type as privately managed."),
        buttons: [{
          title: this.T__("Okay"),
          default: true // Will be triggered by default if 'Enter' pressed.

        }]
      });
    },
    may_show_on_change_host_selector_info: function may_show_on_change_host_selector_info() {
      var _this2 = this;

      console.log("may_show_on_change_host_selector_info");

      if (this.view != "edit" || Object(isEqual["a" /* default */])(this.saved_form.service_admin_user_ids, this.form.service_admin_user_ids)) {
        return;
      }

      var dialog_descr = "<ul class='bull'><li>" + this.T__("Existing bookings will NOT be affected.") + "</li><li>" + this.T__("For existing bookings, the host will not be changed. So, the old host's email, calendar apps and meeting apps will be used.") + "</li><li>" + this.T__("New and rescheduled bookings will be assigned to the new host. So, emails related to it will be sent to the new host. Also, the new host's email, calendar apps and meeting apps will be used.") + "</li></ul>";
      this.$modal.show("dialog", {
        title: this.T__("Change the host?"),
        text: this.Tsprintf(this.T__("Here's what will happen -" + dialog_descr)),
        buttons: [{
          title: this.T__("Yes, change the host.")
        }, {
          title: this.T__("No, don't."),
          handler: function handler() {
            _this2.form.service_admin_user_ids = _this2.clone_deep(_this2.saved_form.service_admin_user_ids);

            _this2.$modal.hide("dialog");
          }
        }]
      });
    }
  },
  watch: {
    "step.form": function stepForm(new_value) {
      if (new_value) {
        this.auto_focus_form_element();
      }
    },
    "form.service_admin_user_ids": function formService_admin_user_ids() {
      this.may_show_on_change_host_selector_info();
    }
  },
  mounted: function mounted() {
    if (this.view == "add") {
      this.$props.step.summary = true;
    }
  }
});
// CONCATENATED MODULE: ./src/components/admin/ServiceFormStep2.vue?vue&type=script&lang=js&
 /* harmony default export */ var admin_ServiceFormStep2vue_type_script_lang_js_ = (ServiceFormStep2vue_type_script_lang_js_); 
// EXTERNAL MODULE: ./src/components/admin/ServiceFormStep2.vue?vue&type=style&index=0&lang=css&
var ServiceFormStep2vue_type_style_index_0_lang_css_ = __webpack_require__("5bcd");

// EXTERNAL MODULE: ./src/components/admin/ServiceFormStep2.vue?vue&type=style&index=1&id=1edd344c&scoped=true&lang=css&
var ServiceFormStep2vue_type_style_index_1_id_1edd344c_scoped_true_lang_css_ = __webpack_require__("ff75");

// CONCATENATED MODULE: ./src/components/admin/ServiceFormStep2.vue







/* normalize component */

var ServiceFormStep2_component = Object(componentNormalizer["a" /* default */])(
  admin_ServiceFormStep2vue_type_script_lang_js_,
  ServiceFormStep2vue_type_template_id_1edd344c_scoped_true_render,
  ServiceFormStep2vue_type_template_id_1edd344c_scoped_true_staticRenderFns,
  false,
  null,
  "1edd344c",
  null
  
)

/* harmony default export */ var ServiceFormStep2 = (ServiceFormStep2_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"1bf56cee-vue-loader-template"}!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/vue-loader/lib??vue-loader-options!./src/components/admin/ServiceFormStep3.vue?vue&type=template&id=20ee7b70&scoped=true&
var ServiceFormStep3vue_type_template_id_20ee7b70_scoped_true_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',[_c('div',{directives:[{name:"show",rawName:"v-show",value:(_vm.step.upcoming),expression:"step.upcoming"}],staticClass:"step-single upcoming"},[_c('div',{staticClass:"step-title"},[_vm._v(" "+_vm._s(_vm.T__("Event type duration & timings"))+" ")])]),_c('div',{directives:[{name:"show",rawName:"v-show",value:(_vm.step.form),expression:"step.form"}],staticClass:"step-single form active",attrs:{"id":'service_form_step' + _vm.step.id}},[_c('div',{staticClass:"step-title"},[_vm._v(" "+_vm._s(_vm.T__("Event Type Duration & Timings"))+" ")]),_c('div',{staticClass:"form"},[_c('form-row',{attrs:{"validator":_vm.$v.form.duration}},[_c('label',[_vm._v(_vm._s(_vm.T__("Event duration")))]),_c('span',{staticClass:"txt-help pt0"},[_vm._v(_vm._s(_vm.T__("Define how long the event will last.")))]),_c('InlineRadioSelector',{attrs:{"element_settings":_vm.event_duration_element_setting,"form":_vm.form,"$v":_vm.$v}})],1),_c('form-row',{attrs:{"validator":_vm.$v.form.default_availability_details.date_range_type}},[_c('label',[_vm._v(_vm._s(_vm.T__("When can this event be booked?")))]),_c('span',{staticClass:"txt-help pt0"},[_vm._v(_vm._s(_vm.T__("Select a time range within which this event can be booked.")))]),_c('ul',{staticClass:"selector block mt10"},[_c('li',[_c('input',{directives:[{name:"model",rawName:"v-model",value:(_vm.form.default_availability_details.date_range_type),expression:"form.default_availability_details.date_range_type"}],attrs:{"type":"radio","id":"days-into-future","name":"event-can-be-booked","value":"relative"},domProps:{"checked":_vm._q(_vm.form.default_availability_details.date_range_type,"relative")},on:{"change":function($event){return _vm.$set(_vm.form.default_availability_details, "date_range_type", "relative")}}}),_c('label',{attrs:{"for":"days-into-future"}},[_c('div',{staticClass:"txt-h5"},[_c('input',{directives:[{name:"model",rawName:"v-model",value:(_vm.show_relative_date_range),expression:"show_relative_date_range"}],staticStyle:{"width":"50px","height":"33px","text-align":"center","margin":"-9px 7px -7px 0px"},attrs:{"type":"number"},domProps:{"value":(_vm.show_relative_date_range)},on:{"blur":function($event){return _vm.$v.form.default_availability_details.date_misc.$touch()},"focus":function($event){_vm.form.default_availability_details.date_range_type =
                      'relative'},"input":function($event){if($event.target.composing){ return; }_vm.show_relative_date_range=$event.target.value}}}),_vm._v(_vm._s(_vm.T__("rolling days into the future"))+" ")])])]),_c('li',[_c('input',{directives:[{name:"model",rawName:"v-model",value:(_vm.form.default_availability_details.date_range_type),expression:"form.default_availability_details.date_range_type"}],attrs:{"type":"radio","id":"within-date-range","name":"event-can-be-booked","value":"from_to"},domProps:{"checked":_vm._q(_vm.form.default_availability_details.date_range_type,"from_to")},on:{"focus":function($event){_vm.form.default_availability_details.date_range_type !==
                  'from_to' &&
                  _vm.ref_click('default_availability_details_from_date_input')},"change":function($event){return _vm.$set(_vm.form.default_availability_details, "date_range_type", "from_to")}}}),_c('label',{attrs:{"for":"__unknown__"},on:{"click":function($event){_vm.form.default_availability_details.date_range_type = 'from_to'}}},[_c('div',{staticClass:"txt-h5"},[_vm._v(_vm._s(_vm.T__("Within a date range")))]),_c('div',{staticClass:"date-range-selector cf"},[_c('div',{staticClass:"col50"},[_c('div',[_c('div',{staticClass:"label txt-help"},[_vm._v(_vm._s(_vm.T__("FROM")))]),_c('v-date-picker',{attrs:{"locale":this.$store.getters.get_site_locale_intl,"popover":{
                        visibility: 'click'
                      },"masks":{ input: ['YYYY-MM-DD'] }},model:{value:(_vm.default_availability_details_from_date_obj),callback:function ($$v) {_vm.default_availability_details_from_date_obj=$$v},expression:"default_availability_details_from_date_obj"}},[_c('input',{ref:"default_availability_details_from_date_input",attrs:{"type":"text","readonly":"","placeholder":"M d, YYYY"},domProps:{"value":_vm.$options.filters.wpcal_format_date_style1(
                            _vm.default_availability_details_from_date
                          )}})])],1)]),_c('div',{staticClass:"col50"},[_c('div',[_c('div',{staticClass:"label txt-help"},[_vm._v(_vm._s(_vm.T__("TO")))]),_c('v-date-picker',{attrs:{"locale":this.$store.getters.get_site_locale_intl,"popover":{
                        visibility: 'click'
                      },"masks":{ input: ['YYYY-MM-DD'] },"min-date":_vm.default_availability_details_from_date_obj},model:{value:(_vm.default_availability_details_to_date_obj),callback:function ($$v) {_vm.default_availability_details_to_date_obj=$$v},expression:"default_availability_details_to_date_obj"}},[_c('input',{ref:"default_availability_details_to_date_input",attrs:{"type":"text","readonly":"","placeholder":"M d, YYYY"},domProps:{"value":_vm.$options.filters.wpcal_format_date_style1(
                            _vm.default_availability_details_to_date
                          )}})])],1)])])])]),_c('li',[_c('input',{directives:[{name:"model",rawName:"v-model",value:(_vm.form.default_availability_details.date_range_type),expression:"form.default_availability_details.date_range_type"}],attrs:{"type":"radio","id":"indefinitely","name":"event-can-be-booked","value":"infinite"},domProps:{"checked":_vm._q(_vm.form.default_availability_details.date_range_type,"infinite")},on:{"change":function($event){return _vm.$set(_vm.form.default_availability_details, "date_range_type", "infinite")}}}),_c('label',{attrs:{"for":"indefinitely"}},[_c('div',{staticClass:"txt-h5"},[_vm._v(_vm._s(_vm.T__("Indefinitely")))]),_c('div',{staticClass:"txt-help"},[_vm._v(" "+_vm._s(_vm.T__("Invitees can book slots indefinitely into the future"))+" ")])])])]),_c('form-row',{attrs:{"validator":_vm.$v.form.default_availability_details.date_misc}}),_c('form-row',{attrs:{"validator":_vm.$v.form.default_availability_details.from_date}}),_c('form-row',{attrs:{"validator":_vm.$v.form.default_availability_details.to_date}})],1),_c('form-row',[_c('label',[_vm._v(_vm._s(_vm.T__("Availability for this event type")))]),_c('span',{staticClass:"txt-help pt0"},[_vm._v(_vm._s(_vm.T__("Set your availability specifically for this event type.")))]),_c('div',{staticClass:"mbox mt10"},[_c('form-row',{staticStyle:{"display":"block","margin-bottom":"-10px"},attrs:{"validator":_vm.$v.form.default_availability_details.periods}},[_c('div',{staticClass:"txt-h5 caps"},[_vm._v(" "+_vm._s(_vm.T__("Available hours"))+" ")]),_c('TimeRange1ToN',{staticClass:"time-range-selector",attrs:{"$v":_vm.$v.form.default_availability_details.periods},model:{value:(_vm.form.default_availability_details.periods),callback:function ($$v) {_vm.$set(_vm.form.default_availability_details, "periods", $$v)},expression:"form.default_availability_details.periods"}})],1),_c('hr'),_c('form-row',{staticStyle:{"margin-bottom":"-10px","display":"block"},attrs:{"validator":_vm.$v.form.default_availability_details.day_index_list}},[_c('div',{staticClass:"txt-h5 caps"},[_vm._v(" "+_vm._s(_vm.T__("Available days"))+" ")]),_c('WeekDaysSelector',{model:{value:(_vm.form.default_availability_details.day_index_list),callback:function ($$v) {_vm.$set(_vm.form.default_availability_details, "day_index_list", $$v)},expression:"form.default_availability_details.day_index_list"}})],1),(_vm.view == 'edit')?_c('button',{staticClass:"wpc-btn secondary md mt20",staticStyle:{"width":"100%"},attrs:{"type":"button"},on:{"click":function($event){return _vm.$emit('may-show-service-availability-modal')}}},[_vm._v(" "+_vm._s(_vm.T__("Set custom hours for specific days/dates"))+" ")]):_vm._e(),(_vm.view == 'add')?_c('span',[_vm._v("More availability customization once event type is created.")]):_vm._e(),_c('hr'),_c('form-row',{staticStyle:{"margin-bottom":"-20px","display":"block"},attrs:{"validator":_vm.$v.form.timezone}},[_c('div',{staticClass:"txt-h5 caps"},[_vm._v(" "+_vm._s(_vm.T__("Time zone"))+" ")]),_c('TimeZoneSelector',{attrs:{"use_as":'form_tz'},model:{value:(_vm.form.timezone),callback:function ($$v) {_vm.$set(_vm.form, "timezone", $$v)},expression:"form.timezone"}})],1)],1)]),_c('form-row',{attrs:{"validator":_vm.$v.form.display_start_time_every}},[_c('label',[_vm._v(" "+_vm._s(_vm.T__("Show start times every:"))+" "),_c('a',{staticClass:"icon-q-mark",staticStyle:{"float":"right"},attrs:{"href":"https://help.wpcal.io/en/article/event-type-availability-options-show-start-times-every-minimum-scheduling-notice-and-event-buffers-17lvw88/?utm_source=wpcal_plugin&utm_medium=contextual#1-how-show-start-times-every-works","target":"_blank"}})]),_c('div',{staticClass:"txt-help"},[_vm._v(" "+_vm._s(_vm.T__("Show your available hours in blocks of:"))+" ")]),_c('InlineRadioSelector',{attrs:{"element_settings":_vm.display_start_time_every_element_setting,"form":_vm.form,"$v":_vm.$v}})],1),_c('a',{staticStyle:{"font-size":"14px"},on:{"click":function($event){_vm.show_advance_options = !_vm.show_advance_options}}},[_vm._v(_vm._s(_vm.show_advance_options_txt))]),_c('div',{directives:[{name:"show",rawName:"v-show",value:(_vm.show_advance_options),expression:"show_advance_options"}]},[_c('ul',{staticClass:"mbox avail-advanced-options"},[_c('li',[_c('form-row',{attrs:{"validator":_vm.$v.form.max_booking_per_day}},[_c('div',{staticClass:"txt-h5 caps"},[_vm._v(" "+_vm._s(_vm.T__("Max bookings per day"))+" ")]),_c('div',{staticClass:"txt-help",staticStyle:{"margin-bottom":"10px"}},[_vm._v(" "+_vm._s(_vm.T__( "Limit the number of bookings for this event type per day. Leave blank for no limit." ))+" ")]),_c('input',{directives:[{name:"model",rawName:"v-model",value:(_vm.form.max_booking_per_day),expression:"form.max_booking_per_day"}],attrs:{"type":"text","placeholder":"No limit"},domProps:{"value":(_vm.form.max_booking_per_day)},on:{"blur":function($event){return _vm.$v.form.max_booking_per_day.$touch()},"input":function($event){if($event.target.composing){ return; }_vm.$set(_vm.form, "max_booking_per_day", $event.target.value)}}})])],1),_c('li',[_c('div',{staticClass:"txt-h5 caps"},[_vm._v(" "+_vm._s(_vm.T__("Minimum Scheduling Notice"))+" "),_c('a',{staticClass:"icon-q-mark",attrs:{"href":"https://help.wpcal.io/en/article/event-type-availability-options-show-start-times-every-minimum-scheduling-notice-and-event-buffers-17lvw88/?utm_source=wpcal_plugin&utm_medium=contextual#1-how-minimum-scheduling-notice-works","target":"_blank"}})]),_c('div',{staticClass:"custom-radio-cont"},[_c('label',{staticStyle:{"font-size":"13px"}},[_c('input',{directives:[{name:"model",rawName:"v-model",value:(_vm.form.min_schedule_notice.type),expression:"form.min_schedule_notice.type"}],staticStyle:{"float":"left","margin-right":"7px","margin-top":"-1px"},attrs:{"type":"radio","name":"min_schedule_notice_type","value":"none"},domProps:{"checked":_vm._q(_vm.form.min_schedule_notice.type,"none")},on:{"change":function($event){return _vm.$set(_vm.form.min_schedule_notice, "type", "none")}}}),_vm._v(" "+_vm._s(_vm.T__("None"))+" ")]),_c('label',{staticStyle:{"font-size":"13px"}},[_c('input',{directives:[{name:"model",rawName:"v-model",value:(_vm.form.min_schedule_notice.type),expression:"form.min_schedule_notice.type"}],staticStyle:{"float":"left","margin-right":"7px","margin-top":"-1px","margin-bottom":"20px"},attrs:{"type":"radio","name":"min_schedule_notice_type","value":"units"},domProps:{"checked":_vm._q(_vm.form.min_schedule_notice.type,"units")},on:{"change":function($event){return _vm.$set(_vm.form.min_schedule_notice, "type", "units")}}}),_vm._v(" "+_vm._s(_vm.T__("Prevent booking of slots less than"))+" "),_c('div',{staticStyle:{"padding-left":"22px","padding-top":"4px"}},[_c('input',{directives:[{name:"model",rawName:"v-model",value:(_vm.form.min_schedule_notice.time_units),expression:"form.min_schedule_notice.time_units"}],staticStyle:{"width":"78px","padding":"3px 5px","margin-top":"-8px","font-size":"13px"},attrs:{"type":"number"},domProps:{"value":(_vm.form.min_schedule_notice.time_units)},on:{"focus":function($event){_vm.form.min_schedule_notice.type = 'units'},"blur":function($event){return _vm.$v.form.min_schedule_notice.time_units.$touch()},"input":function($event){if($event.target.composing){ return; }_vm.$set(_vm.form.min_schedule_notice, "time_units", $event.target.value)}}}),_c('select',{directives:[{name:"model",rawName:"v-model",value:(_vm.form.min_schedule_notice.time_units_in),expression:"form.min_schedule_notice.time_units_in"}],staticStyle:{"width":"auto","margin-top":"-3px"},on:{"focus":function($event){_vm.form.min_schedule_notice.type = 'units'},"change":function($event){var $$selectedVal = Array.prototype.filter.call($event.target.options,function(o){return o.selected}).map(function(o){var val = "_value" in o ? o._value : o.value;return val}); _vm.$set(_vm.form.min_schedule_notice, "time_units_in", $event.target.multiple ? $$selectedVal : $$selectedVal[0])}}},[_c('option',{attrs:{"value":"mins"}},[_vm._v(_vm._s(_vm.T__("mins")))]),_c('option',{attrs:{"value":"hrs","selected":""}},[_vm._v(_vm._s(_vm.T__("hrs")))]),_c('option',{attrs:{"value":"days"}},[_vm._v(_vm._s(_vm.T__("days")))])]),_vm._v(" "+_vm._s(_vm.T_x("away.", "Prevent booking of slots less than _ away"))+" ")]),_c('form-row',{attrs:{"validator":_vm.$v.form.min_schedule_notice.time_units}})],1),_c('label',{staticStyle:{"font-size":"13px","margin-bottom":"1px"}},[_c('input',{directives:[{name:"model",rawName:"v-model",value:(_vm.form.min_schedule_notice.type),expression:"form.min_schedule_notice.type"}],staticStyle:{"float":"left","margin-right":"7px","margin-top":"-1px","margin-bottom":"20px"},attrs:{"type":"radio","name":"min_schedule_notice_type","id":"min_schedule_notice_type_time_days_before","value":"time_days_before"},domProps:{"checked":_vm._q(_vm.form.min_schedule_notice.type,"time_days_before")},on:{"change":function($event){return _vm.$set(_vm.form.min_schedule_notice, "type", "time_days_before")}}}),_vm._v(" "+_vm._s(_vm.T__("Prevent all bookings after"))+" ")]),_c('div',{staticClass:"prevent-bookings-after-timepicker",staticStyle:{"display":"flex","align-items":"center"}},[_c('TimePicker',{on:{"time-picker-opened":function($event){_vm.form.min_schedule_notice.type = 'time_days_before'}},model:{value:(_vm.form.min_schedule_notice.days_before_time),callback:function ($$v) {_vm.$set(_vm.form.min_schedule_notice, "days_before_time", $$v)},expression:"form.min_schedule_notice.days_before_time"}}),_c('span',{staticStyle:{"margin-left":"3px"}},[(_vm.time_format == 'HH:mm')?_c('span',[_vm._v("hrs")]):_vm._e(),_vm._v(",")]),_c('select',{directives:[{name:"model",rawName:"v-model",value:(_vm.form.min_schedule_notice.days_before),expression:"form.min_schedule_notice.days_before"}],staticStyle:{"width":"auto","margin-left":"5px"},on:{"focus":function($event){_vm.form.min_schedule_notice.type = 'time_days_before'},"change":function($event){var $$selectedVal = Array.prototype.filter.call($event.target.options,function(o){return o.selected}).map(function(o){var val = "_value" in o ? o._value : o.value;return val}); _vm.$set(_vm.form.min_schedule_notice, "days_before", $event.target.multiple ? $$selectedVal : $$selectedVal[0])}}},_vm._l(([0, 1, 2, 3, 4, 5, 6, 7]),function(val,index){return _c('option',{key:index,domProps:{"value":val}},[_vm._v(_vm._s(val))])}),0),_c('label',{staticStyle:{"font-size":"13px","padding-left":"3px"},attrs:{"for":"min_schedule_notice_type_time_days_before"}},[_vm._v(" "+_vm._s(_vm.T_n( "day before.", "days before.", _vm.form.min_schedule_notice.days_before ))+" ")])],1),_c('form-row',{attrs:{"validator":_vm.$v.form.min_schedule_notice.days_before_time}})],1)]),_c('li',[_c('div',{staticClass:"txt-h5 caps"},[_vm._v(" "+_vm._s(_vm.T__("Event Buffers"))+" "),_c('a',{staticClass:"icon-q-mark",attrs:{"href":"https://help.wpcal.io/en/article/event-type-availability-options-show-start-times-every-minimum-scheduling-notice-and-event-buffers-17lvw88/?utm_source=wpcal_plugin&utm_medium=contextual#1-how-event-buffers-works","target":"_blank"}})]),_c('div',{staticClass:"txt-help",staticStyle:{"margin-bottom":"10px"}},[_vm._v(" "+_vm._s(_vm.T__( "Set aside preparation, rest or travel time before or after events. For example, if you define a 5-minute buffer before your events, we will make sure you have 5 minutes of free time before your scheduled events." ))+" ")]),_c('div',{staticClass:"col-main cf"},[_c('div',{staticClass:"col50"},[_c('div',{staticClass:"label txt-help",staticStyle:{"padding-bottom":"5px"}},[_vm._v(" "+_vm._s(_vm.T__("BEFORE EVENT"))+" ")]),_c('select',{directives:[{name:"model",rawName:"v-model",value:(_vm.form.event_buffer_before),expression:"form.event_buffer_before"}],on:{"change":[function($event){var $$selectedVal = Array.prototype.filter.call($event.target.options,function(o){return o.selected}).map(function(o){var val = "_value" in o ? o._value : o.value;return val}); _vm.$set(_vm.form, "event_buffer_before", $event.target.multiple ? $$selectedVal : $$selectedVal[0])},function($event){return _vm.$v.form.event_buffer_before.$touch()}]}},_vm._l((_vm.event_buffer_before_element_setting.options),function(option,index){return _c('option',{key:index,domProps:{"value":option.value}},[_vm._v(_vm._s(option.text))])}),0)]),_c('div',{staticClass:"col50"},[_c('div',{staticClass:"label txt-help",staticStyle:{"padding-bottom":"5px"}},[_vm._v(" "+_vm._s(_vm.T__("AFTER EVENT"))+" ")]),_c('select',{directives:[{name:"model",rawName:"v-model",value:(_vm.form.event_buffer_after),expression:"form.event_buffer_after"}],on:{"change":[function($event){var $$selectedVal = Array.prototype.filter.call($event.target.options,function(o){return o.selected}).map(function(o){var val = "_value" in o ? o._value : o.value;return val}); _vm.$set(_vm.form, "event_buffer_after", $event.target.multiple ? $$selectedVal : $$selectedVal[0])},function($event){return _vm.$v.form.event_buffer_after.$touch()}]}},[_vm._v("> "),_vm._l((_vm.event_buffer_after_element_setting.options),function(option,index){return _c('option',{key:index,domProps:{"value":option.value}},[_vm._v(_vm._s(option.text))])})],2)])]),_c('form-row',{attrs:{"validator":_vm.$v.form.event_buffer_before}}),_c('form-row',{attrs:{"validator":_vm.$v.form.event_buffer_after}})],1)])]),_c('div',{staticClass:"wpc-form-row"},[(_vm.view == 'add')?_c('button',{staticClass:"wpc-btn primary lg",staticStyle:{"margin":"10px 0"},attrs:{"type":"button","disabled":!_vm.create_or_update_btn_is_enabled},on:{"click":function($event){return _vm.$emit('validate-may-navigate-or-submit', 3)}}},[_vm._v(" "+_vm._s(_vm.T__("Create Event Type"))+" ")]):(_vm.view == 'edit')?_c('button',{staticClass:"wpc-btn primary lg",staticStyle:{"margin":"10px 0"},attrs:{"type":"button","disabled":!_vm.create_or_update_btn_is_enabled},on:{"click":function($event){return _vm.$emit('submit-form', 3)}}},[_vm._v(" "+_vm._s(_vm.T__("Save"))+" ")]):_vm._e(),(_vm.view == 'edit')?_c('button',{staticClass:"wpc-btn tert-link sm mt10 center",attrs:{"type":"button"},on:{"click":function($event){return _vm.$emit('edit-view-toggle-steps')}}},[_vm._v(" "+_vm._s(_vm.T__("Cancel"))+" ")]):_vm._e(),(_vm.view == 'add')?_c('div',{staticClass:"txt-help mt10",staticStyle:{"padding-bottom":"10px"}},[_vm._v(" "+_vm._s(_vm.T__( "You can configure your event type further after creating it." ))+" ")]):_vm._e()])],1)])])}
var ServiceFormStep3vue_type_template_id_20ee7b70_scoped_true_staticRenderFns = []


// CONCATENATED MODULE: ./src/components/admin/ServiceFormStep3.vue?vue&type=template&id=20ee7b70&scoped=true&

// EXTERNAL MODULE: ./node_modules/core-js/modules/es.string.replace.js
var es_string_replace = __webpack_require__("5319");

// EXTERNAL MODULE: ./node_modules/core-js/modules/es.string.trim.js
var es_string_trim = __webpack_require__("498a");

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"1bf56cee-vue-loader-template"}!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/vue-loader/lib??vue-loader-options!./src/components/form_elements/InlineRadioSelector.vue?vue&type=template&id=32d5feb0&scoped=true&
var InlineRadioSelectorvue_type_template_id_32d5feb0_scoped_true_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',[_c('ul',{staticClass:"selector inline time mt10 with-custom",class:_vm.element_settings.style_class},[_vm._l((_vm.element_settings.options),function(option,index){return _c('li',{key:index},[_c('input',{directives:[{name:"model",rawName:"v-model",value:(_vm.form[_vm.element_settings.name]),expression:"form[element_settings.name]"}],attrs:{"type":"radio","id":'duration-' + _vm.element_settings.name + '-' + option.value + '-min',"name":_vm.element_settings.name},domProps:{"value":option.value,"checked":_vm._q(_vm.form[_vm.element_settings.name],option.value)},on:{"change":[function($event){return _vm.$set(_vm.form, _vm.element_settings.name, option.value)},_vm.load_custom_value]}}),_c('label',{attrs:{"for":'duration-' + _vm.element_settings.name + '-' + option.value + '-min'}},[_vm._v(" "+_vm._s(option.text)+" "),_c('span',[_vm._v(_vm._s(_vm.T__("min")))])])])}),(_vm.element_settings.custom_option)?_c('li',{staticClass:"custom-duration"},[_c('input',{attrs:{"type":"radio","name":_vm.element_settings.name},domProps:{"checked":_vm.custom_checked}}),_c('label',{attrs:{"for":'custom-duration--' + _vm.element_settings.name + ''}},[_c('input',{attrs:{"type":"number","id":'custom-duration--' + _vm.element_settings.name + '',"placeholder":_vm.custom_value_focus === true ? '' : '-'},domProps:{"value":_vm.get_custom_value},on:{"focus":_vm.custom_value_on_focus,"blur":function($event){_vm.custom_value_focus = false;
            _vm.$v && _vm.$v.form[_vm.element_settings.name]
              ? _vm.$v.form[_vm.element_settings.name].$touch()
              : '';},"input":_vm.debounce_set_custom_value}}),_c('span',[_vm._v(_vm._s(_vm.T__("custom min")))])])]):_vm._e()],2)])}
var InlineRadioSelectorvue_type_template_id_32d5feb0_scoped_true_staticRenderFns = []


// CONCATENATED MODULE: ./src/components/form_elements/InlineRadioSelector.vue?vue&type=template&id=32d5feb0&scoped=true&

// EXTERNAL MODULE: ./node_modules/lodash-es/debounce.js + 1 modules
var debounce = __webpack_require__("85b1");

// EXTERNAL MODULE: ./node_modules/lodash-es/findIndex.js + 3 modules
var findIndex = __webpack_require__("1c8f");

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--13-0!./node_modules/thread-loader/dist/cjs.js!./node_modules/babel-loader/lib!./node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/vue-loader/lib??vue-loader-options!./src/components/form_elements/InlineRadioSelector.vue?vue&type=script&lang=js&

//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

/* harmony default export */ var InlineRadioSelectorvue_type_script_lang_js_ = ({
  name: "InlineRadioSelector",
  props: {
    element_settings: {
      type: Object,
      required: true
    },
    form: {
      type: Object,
      required: true
    },
    $v: {}
  },
  data: function data() {
    return {
      custom_value: "",
      custom_value_focus: false
    };
  },
  computed: {
    get_custom_value: function get_custom_value() {
      return isNaN(this.custom_value) ? "" : this.custom_value;
    },
    custom_checked: function custom_checked() {
      return this.custom_value !== "" ? true : false;
    }
  },
  methods: {
    set_custom_value: function set_custom_value($event) {
      this.custom_value = parseInt($event.target.value);
      this.form[this.element_settings.name] = this.custom_value;
      this.load_custom_value();
    },
    debounce_set_custom_value: Object(debounce["a" /* default */])(function ($event) {
      this.set_custom_value($event);
    }, 500),
    custom_value_on_focus: function custom_value_on_focus($event) {
      this.custom_value_focus = true;
      this.set_custom_value($event);
    },
    load_custom_value: function load_custom_value() {
      if (Object(findIndex["a" /* default */])(this.element_settings.options, {
        value: parseInt(this.form[this.element_settings.name])
      }) == -1) {
        this.custom_value = this.form[this.element_settings.name];
      } else {
        this.custom_value = "";
      }
    }
  },
  created: function created() {
    this.load_custom_value();
  }
});
// CONCATENATED MODULE: ./src/components/form_elements/InlineRadioSelector.vue?vue&type=script&lang=js&
 /* harmony default export */ var form_elements_InlineRadioSelectorvue_type_script_lang_js_ = (InlineRadioSelectorvue_type_script_lang_js_); 
// EXTERNAL MODULE: ./src/components/form_elements/InlineRadioSelector.vue?vue&type=style&index=0&id=32d5feb0&lang=css&scoped=true&
var InlineRadioSelectorvue_type_style_index_0_id_32d5feb0_lang_css_scoped_true_ = __webpack_require__("03d0");

// CONCATENATED MODULE: ./src/components/form_elements/InlineRadioSelector.vue






/* normalize component */

var InlineRadioSelector_component = Object(componentNormalizer["a" /* default */])(
  form_elements_InlineRadioSelectorvue_type_script_lang_js_,
  InlineRadioSelectorvue_type_template_id_32d5feb0_scoped_true_render,
  InlineRadioSelectorvue_type_template_id_32d5feb0_scoped_true_staticRenderFns,
  false,
  null,
  "32d5feb0",
  null
  
)

/* harmony default export */ var InlineRadioSelector = (InlineRadioSelector_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"1bf56cee-vue-loader-template"}!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/vue-loader/lib??vue-loader-options!./src/components/form_elements/TimeRange1ToN.vue?vue&type=template&id=4b8606c3&
var TimeRange1ToNvue_type_template_id_4b8606c3_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',[_vm._l((_vm.value),function(period,index){return _c('div',{key:index,staticStyle:{"margin-bottom":"5px"}},[_c('div',{staticClass:"date-range-selector cf",class:{
        multiple: _vm.value.length > 1,
        'del-alert': _vm.show_delete_alert(index)
      }},[_c('div',{staticClass:"col50"},[_c('div',[_c('div',{staticClass:"label txt-help",class:{
              'has-error':
                _vm.$v &&
                _vm.$v.$each &&
                _vm.$v.$each.$iter[index].from_time.$dirty &&
                _vm.$v.$each.$iter[index].from_time.$error
            }},[_vm._v(" "+_vm._s(_vm.T__("FROM"))+" ")]),_c('TimePicker',{model:{value:(period.from_time),callback:function ($$v) {_vm.$set(period, "from_time", $$v)},expression:"period.from_time"}})],1)]),(_vm.value.length > 1)?_c('div',{staticClass:"icon-delete",on:{"click":function($event){_vm.value.splice(index, 1);
          _vm.set_mouseover(index, false);},"mouseover":function($event){return _vm.set_mouseover(index, true)},"mouseleave":function($event){return _vm.set_mouseover(index, false)}}}):_vm._e(),_c('div',{staticClass:"col50"},[_c('div',[_c('div',{staticClass:"label txt-help",class:{
              'has-error':
                _vm.$v &&
                _vm.$v.$each &&
                _vm.$v.$each.$iter[index].to_time.$dirty &&
                _vm.$v.$each.$iter[index].to_time.$error
            }},[_vm._v(" "+_vm._s(_vm.T__("TO"))+" ")]),_c('TimePicker',{model:{value:(period.to_time),callback:function ($$v) {_vm.$set(period, "to_time", $$v)},expression:"period.to_time"}})],1)])]),(_vm.$v && _vm.$v.$each && _vm.$v.$each.$iter[index])?_c('form-row',{attrs:{"validator":_vm.$v.$each.$iter[index].from_time}}):_vm._e(),(_vm.$v && _vm.$v.$each && _vm.$v.$each.$iter[index])?_c('form-row',{attrs:{"validator":_vm.$v.$each.$iter[index].to_time}}):_vm._e()],1)}),(_vm.can_show_add_period)?_c('a',{on:{"click":_vm.add_new_range}},[_vm._v(_vm._s(_vm.T__("+ Add another period")))]):_vm._e()],2)}
var TimeRange1ToNvue_type_template_id_4b8606c3_staticRenderFns = []


// CONCATENATED MODULE: ./src/components/form_elements/TimeRange1ToN.vue?vue&type=template&id=4b8606c3&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"1bf56cee-vue-loader-template"}!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/vue-loader/lib??vue-loader-options!./src/components/form_elements/TimePicker.vue?vue&type=template&id=d63cd724&
var TimePickervue_type_template_id_d63cd724_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('span',[_c('vue-timepicker',{attrs:{"format":_vm.time_format,"hide-clear-button":"","auto-scroll":"","advanced-keyboard":"","minute-interval":_vm.minute_interval},on:{"change":_vm.handle_picker_change,"open":function($event){return _vm.$emit('time-picker-opened')}},model:{value:(_vm.time_data),callback:function ($$v) {_vm.time_data=$$v},expression:"time_data"}})],1)}
var TimePickervue_type_template_id_d63cd724_staticRenderFns = []


// CONCATENATED MODULE: ./src/components/form_elements/TimePicker.vue?vue&type=template&id=d63cd724&

// EXTERNAL MODULE: ./node_modules/core-js/modules/es.array.join.js
var es_array_join = __webpack_require__("a15b");

// EXTERNAL MODULE: ./node_modules/date-fns/esm/format/index.js + 28 modules
var format = __webpack_require__("b166");

// EXTERNAL MODULE: ./node_modules/vue2-timepicker/src/vue-timepicker.vue + 4 modules
var vue_timepicker = __webpack_require__("fea2");

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--13-0!./node_modules/thread-loader/dist/cjs.js!./node_modules/babel-loader/lib!./node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/vue-loader/lib??vue-loader-options!./src/components/form_elements/TimePicker.vue?vue&type=script&lang=js&



//
//
//
//
//
//
//
//
//
//
//
//
//
//
//


/* harmony default export */ var TimePickervue_type_script_lang_js_ = ({
  name: "TimePicker",
  components: {
    VueTimepicker: vue_timepicker["a" /* default */]
  },
  model: {
    prop: "time",
    event: "time-changed"
  },
  props: {
    time: {
      type: String
    }
  },
  computed: {
    time_data: {
      get: function get() {
        var time = this.time;
        var time_obj_return = {
          HH: "",
          hh: "",
          mm: "",
          a: ""
        };
        this.$store.getters.get_general_settings; //having this.$store.getters.get_general_settings absolutely required for reactivity, or else 17:00 becomes 5am when toggle 12hrs and 24hrs. Especially in setting page.

        if (time) {
          if (time == "::00" || time == "::") {
            return time_obj_return;
          }

          var time_parts = time.split(":");
          time_parts[0] = time_parts[0] ? time_parts[0] : "00";
          time_parts[1] = time_parts[1] ? time_parts[1] : "00";
          time_parts[2] = time_parts[2] ? time_parts[2] : "00";
          time = time_parts.join(":"); //need to validate the incoming format

          var time_obj = this.date_parse("2000-01-01 " + time);
          time_obj_return = {
            HH: Object(format["a" /* default */])(time_obj, "HH"),
            hh: Object(format["a" /* default */])(time_obj, "hh"),
            mm: Object(format["a" /* default */])(time_obj, "mm"),
            a: Object(format["a" /* default */])(time_obj, "a").toLocaleLowerCase()
          };
        }

        return time_obj_return;
      },
      set: function set(new_value) {
        new_value;
      }
    },
    time_format: function time_format() {
      var format = "HH:mm";
      var get_general_settings = this.$store.getters.get_general_settings;

      if (get_general_settings && get_general_settings.hasOwnProperty("time_format") && get_general_settings.time_format === "12hrs") {
        format = "hh:mm a";
      }

      return format;
    },
    minute_interval: function minute_interval() {
      var _this$time_data;

      var interval = 5;

      if (!((_this$time_data = this.time_data) !== null && _this$time_data !== void 0 && _this$time_data.mm)) {
        return interval;
      }

      interval = this.time_data.mm % 5 ? 1 : interval;
      return interval;
    }
  },
  methods: {
    handle_picker_change: function handle_picker_change(event) {
      var t = event.data;
      var final = t.HH + ":" + t.mm + ":00";
      this.$emit("time-changed", final);
    }
  }
});
// CONCATENATED MODULE: ./src/components/form_elements/TimePicker.vue?vue&type=script&lang=js&
 /* harmony default export */ var form_elements_TimePickervue_type_script_lang_js_ = (TimePickervue_type_script_lang_js_); 
// CONCATENATED MODULE: ./src/components/form_elements/TimePicker.vue





/* normalize component */

var TimePicker_component = Object(componentNormalizer["a" /* default */])(
  form_elements_TimePickervue_type_script_lang_js_,
  TimePickervue_type_template_id_d63cd724_render,
  TimePickervue_type_template_id_d63cd724_staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* harmony default export */ var TimePicker = (TimePicker_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--13-0!./node_modules/thread-loader/dist/cjs.js!./node_modules/babel-loader/lib!./node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/vue-loader/lib??vue-loader-options!./src/components/form_elements/TimeRange1ToN.vue?vue&type=script&lang=js&
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

/* harmony default export */ var TimeRange1ToNvue_type_script_lang_js_ = ({
  name: "TimeRange1ToN",
  components: {
    TimePicker: TimePicker
  },
  props: {
    $v: {},
    value: {
      type: Array,
      required: true
    },
    hide_add_period_on_no_periods: {
      default: "0"
    },
    disable_multiple: {
      type: Boolean,
      default: false
    }
  },
  data: function data() {
    return {
      delete_alert_index: null
    };
  },
  computed: {
    hide_add_period_on_no_periods_check: function hide_add_period_on_no_periods_check() {
      return this.hide_add_period_on_no_periods == "1" && this.value.length == 0;
    },
    can_show_add_period: function can_show_add_period() {
      return this.disable_multiple === false && !this.hide_add_period_on_no_periods_check;
    }
  },
  methods: {
    add_new_range: function add_new_range() {
      var new_range = {
        from_time: "",
        to_time: ""
      };
      this.value.push(new_range);
    },
    show_delete_alert: function show_delete_alert(index) {
      return this.delete_alert_index === index;
    },
    set_mouseover: function set_mouseover(index, is_hover) {
      if (is_hover) {
        this.delete_alert_index = index;
      } else {
        this.delete_alert_index = null;
      }
    }
  }
});
// CONCATENATED MODULE: ./src/components/form_elements/TimeRange1ToN.vue?vue&type=script&lang=js&
 /* harmony default export */ var form_elements_TimeRange1ToNvue_type_script_lang_js_ = (TimeRange1ToNvue_type_script_lang_js_); 
// EXTERNAL MODULE: ./src/components/form_elements/TimeRange1ToN.vue?vue&type=style&index=0&lang=css&
var TimeRange1ToNvue_type_style_index_0_lang_css_ = __webpack_require__("4753");

// CONCATENATED MODULE: ./src/components/form_elements/TimeRange1ToN.vue






/* normalize component */

var TimeRange1ToN_component = Object(componentNormalizer["a" /* default */])(
  form_elements_TimeRange1ToNvue_type_script_lang_js_,
  TimeRange1ToNvue_type_template_id_4b8606c3_render,
  TimeRange1ToNvue_type_template_id_4b8606c3_staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* harmony default export */ var TimeRange1ToN = (TimeRange1ToN_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"1bf56cee-vue-loader-template"}!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/vue-loader/lib??vue-loader-options!./src/components/form_elements/WeekDaysSelector.vue?vue&type=template&id=48cce514&scoped=true&
var WeekDaysSelectorvue_type_template_id_48cce514_scoped_true_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',[_c('ul',{staticClass:"selector block apply-multiple-days"},_vm._l((_vm.weekdays),function(day,index){return _c('li',{key:index,attrs:{"title":day.full_day}},[_c('input',{directives:[{name:"model",rawName:"v-model",value:(_vm.selected_days),expression:"selected_days"}],attrs:{"type":"checkbox","id":'multiple-' + day.index,"name":"apply-multiple-days"},domProps:{"value":day.index,"checked":Array.isArray(_vm.selected_days)?_vm._i(_vm.selected_days,day.index)>-1:(_vm.selected_days)},on:{"change":[function($event){var $$a=_vm.selected_days,$$el=$event.target,$$c=$$el.checked?(true):(false);if(Array.isArray($$a)){var $$v=day.index,$$i=_vm._i($$a,$$v);if($$el.checked){$$i<0&&(_vm.selected_days=$$a.concat([$$v]))}else{$$i>-1&&(_vm.selected_days=$$a.slice(0,$$i).concat($$a.slice($$i+1)))}}else{_vm.selected_days=$$c}},function($event){return _vm.$emit('change-prop-selected-days', _vm.selected_days)}]}}),_c('label',{attrs:{"for":'multiple-' + day.index}},[_vm._v(_vm._s(day.mini_day))])])}),0)])}
var WeekDaysSelectorvue_type_template_id_48cce514_scoped_true_staticRenderFns = []


// CONCATENATED MODULE: ./src/components/form_elements/WeekDaysSelector.vue?vue&type=template&id=48cce514&scoped=true&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--13-0!./node_modules/thread-loader/dist/cjs.js!./node_modules/babel-loader/lib!./node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/vue-loader/lib??vue-loader-options!./src/components/form_elements/WeekDaysSelector.vue?vue&type=script&lang=js&
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

/* harmony default export */ var WeekDaysSelectorvue_type_script_lang_js_ = ({
  model: {
    prop: "prop_selected_days",
    event: "change-prop-selected-days"
  },
  props: {
    prop_selected_days: {
      type: Array
    }
  },
  data: function data() {
    return {
      weekdays: common_func["A" /* weekdays_list */],
      selected_days: []
    };
  },
  watch: {
    prop_selected_days: function prop_selected_days(new_value) {
      this.selected_days = new_value;
    }
  },
  mounted: function mounted() {
    this.selected_days = this.prop_selected_days;
  }
});
// CONCATENATED MODULE: ./src/components/form_elements/WeekDaysSelector.vue?vue&type=script&lang=js&
 /* harmony default export */ var form_elements_WeekDaysSelectorvue_type_script_lang_js_ = (WeekDaysSelectorvue_type_script_lang_js_); 
// EXTERNAL MODULE: ./src/components/form_elements/WeekDaysSelector.vue?vue&type=style&index=0&id=48cce514&scoped=true&lang=css&
var WeekDaysSelectorvue_type_style_index_0_id_48cce514_scoped_true_lang_css_ = __webpack_require__("e98d");

// CONCATENATED MODULE: ./src/components/form_elements/WeekDaysSelector.vue






/* normalize component */

var WeekDaysSelector_component = Object(componentNormalizer["a" /* default */])(
  form_elements_WeekDaysSelectorvue_type_script_lang_js_,
  WeekDaysSelectorvue_type_template_id_48cce514_scoped_true_render,
  WeekDaysSelectorvue_type_template_id_48cce514_scoped_true_staticRenderFns,
  false,
  null,
  "48cce514",
  null
  
)

/* harmony default export */ var WeekDaysSelector = (WeekDaysSelector_component.exports);
// EXTERNAL MODULE: ./src/components/form_elements/TimeZoneSelector.vue + 4 modules
var TimeZoneSelector = __webpack_require__("e268");

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--13-0!./node_modules/thread-loader/dist/cjs.js!./node_modules/babel-loader/lib!./node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/vue-loader/lib??vue-loader-options!./src/components/admin/ServiceFormStep3.vue?vue&type=script&lang=js&



//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//






/* harmony default export */ var ServiceFormStep3vue_type_script_lang_js_ = ({
  name: "ServiceFormStep3",
  props: {
    view: {
      type: String
    },
    step: {
      type: Object,
      required: true
    },
    form: {
      type: Object,
      required: true
    },
    create_or_update_btn_is_enabled: {},
    $v: {}
  },
  components: {
    InlineRadioSelector: InlineRadioSelector,
    TimeRange1ToN: TimeRange1ToN,
    WeekDaysSelector: WeekDaysSelector,
    TimeZoneSelector: TimeZoneSelector["a" /* default */],
    TimePicker: TimePicker
  },
  data: function data() {
    return {
      show_advance_options: false,
      event_duration_element_setting: {
        name: "duration",
        options: [{
          value: 15,
          text: 15
        }, {
          value: 30,
          text: 30
        }, {
          value: 45,
          text: 45
        }, {
          value: 60,
          text: 60
        }],
        custom_option: true,
        style_class: []
      },
      display_start_time_every_element_setting: {
        name: "display_start_time_every",
        options: [{
          value: 15,
          text: 15
        }, {
          value: 30,
          text: 30
        }, {
          value: 45,
          text: 45
        }, {
          value: 60,
          text: 60
        }],
        custom_option: true,
        style_class: [] //"avail-increments"

      },
      event_buffer_before_element_setting: {
        name: "event_buffer_before",
        options: [{
          value: 0,
          text: "0 " + this.T_x("min", "mintue")
        }, {
          value: 5,
          text: "5 " + this.T_x("min", "mintue")
        }, {
          value: 10,
          text: "10 " + this.T_x("min", "mintue")
        }, {
          value: 15,
          text: "15 " + this.T_x("min", "mintue")
        }, {
          value: 30,
          text: "30 " + this.T_x("min", "mintue")
        }, {
          value: 45,
          text: "45 " + this.T_x("min", "mintue")
        }, {
          value: 60,
          text: "1 " + this.T_x("hr", "hour")
        }, {
          value: 90,
          text: "1 " + this.T_x("hr", "hour") + // intentional line break, i18n search replace
          " 30 " + this.T_x("min", "mintue")
        }, {
          value: 120,
          text: "2 " + this.T_x("hr", "hour")
        }, {
          value: 150,
          text: "2 hr 30 " + this.T_x("min", "mintue")
        }, {
          value: 180,
          text: "3 " + this.T_x("hr", "hour")
        }],
        selected_value: 30
      },
      event_buffer_after_element_setting: {
        name: "event_buffer_after",
        options: [{
          value: 0,
          text: "0 " + this.T_x("min", "mintue")
        }, {
          value: 5,
          text: "5 " + this.T_x("min", "mintue")
        }, {
          value: 10,
          text: "10 " + this.T_x("min", "mintue")
        }, {
          value: 15,
          text: "15 " + this.T_x("min", "mintue")
        }, {
          value: 30,
          text: "30 " + this.T_x("min", "mintue")
        }, {
          value: 45,
          text: "45 " + this.T_x("min", "mintue")
        }, {
          value: 60,
          text: "1 " + this.T_x("hr", "hour")
        }, {
          value: 90,
          text: "1 " + this.T_x("hr", "hour") + // intentional line break, i18n search replace
          " 30 " + this.T_x("min", "mintue")
        }, {
          value: 120,
          text: "2 " + this.T_x("hr", "hour")
        }, {
          value: 150,
          text: "2 " + this.T_x("hr", "hour") + // intentional line break, i18n search replace
          " 30 " + this.T_x("min", "mintue")
        }, {
          value: 180,
          text: "3 " + this.T_x("hr", "hour")
        }],
        selected_value: 30
      }
    };
  },
  computed: {
    show_advance_options_txt: function show_advance_options_txt() {
      return this.show_advance_options ? this.T__("- Hide Advanced options") : this.T__("+ Show Advanced options");
    },
    show_relative_date_range: {
      // getter
      get: function get() {
        var misc_value = this.form.default_availability_details.date_misc;

        if (this.form.default_availability_details.date_range_type === "relative" && misc_value) {
          var show_misc_value = misc_value.replace(/^\+((\d)*)D$/gm, function (x, y) {
            return y;
          });
          return show_misc_value;
        }

        return "";
      },
      // setter
      set: function set(new_value) {
        new_value = new_value.trim();

        if (new_value.length > 0) {
          this.form.default_availability_details.date_misc = "+" + new_value + "D";
        } else {
          this.form.default_availability_details.date_misc = "";
        }
      }
    },
    default_availability_details_from_date_obj: {
      get: function get() {
        if (this.default_availability_details_from_date) {
          var parsed_date = this.date_parse(this.default_availability_details_from_date);
          return parsed_date;
        }

        return "";
      },
      set: function set(new_value) {
        if (this.form.default_availability_details.date_range_type == "from_to") {
          this.form.default_availability_details.from_date = this.$options.filters.wpcal_format_std_date(new_value);
        }
      }
    },
    default_availability_details_to_date_obj: {
      get: function get() {
        if (this.default_availability_details_to_date) {
          var parsed_date = this.date_parse(this.default_availability_details_to_date);
          return parsed_date;
        }

        return "";
      },
      set: function set(new_value) {
        if (this.form.default_availability_details.date_range_type == "from_to") {
          this.form.default_availability_details.to_date = this.$options.filters.wpcal_format_std_date(new_value);
        }
      }
    },
    default_availability_details_from_date: {
      get: function get() {
        return this.form.default_availability_details.date_range_type == "from_to" ? this.form.default_availability_details.from_date : "";
      },
      set: Object(debounce["a" /* default */])(function (new_value) {
        this.form.default_availability_details.from_date = new_value;
      }, 1500)
    },
    default_availability_details_to_date: {
      get: function get() {
        return this.form.default_availability_details.date_range_type == "from_to" ? this.form.default_availability_details.to_date : "";
      },
      set: Object(debounce["a" /* default */])(function (new_value) {
        this.form.default_availability_details.to_date = new_value;
      }, 1500)
    }
  },
  mounted: function mounted() {
    var _this = this;

    this.$root.$on("show-advance-options", function () {
      _this.show_advance_options = true;
    });
  }
});
// CONCATENATED MODULE: ./src/components/admin/ServiceFormStep3.vue?vue&type=script&lang=js&
 /* harmony default export */ var admin_ServiceFormStep3vue_type_script_lang_js_ = (ServiceFormStep3vue_type_script_lang_js_); 
// EXTERNAL MODULE: ./src/components/admin/ServiceFormStep3.vue?vue&type=style&index=0&id=20ee7b70&lang=css&scoped=true&
var ServiceFormStep3vue_type_style_index_0_id_20ee7b70_lang_css_scoped_true_ = __webpack_require__("71c3");

// CONCATENATED MODULE: ./src/components/admin/ServiceFormStep3.vue






/* normalize component */

var ServiceFormStep3_component = Object(componentNormalizer["a" /* default */])(
  admin_ServiceFormStep3vue_type_script_lang_js_,
  ServiceFormStep3vue_type_template_id_20ee7b70_scoped_true_render,
  ServiceFormStep3vue_type_template_id_20ee7b70_scoped_true_staticRenderFns,
  false,
  null,
  "20ee7b70",
  null
  
)

/* harmony default export */ var ServiceFormStep3 = (ServiceFormStep3_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"1bf56cee-vue-loader-template"}!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/vue-loader/lib??vue-loader-options!./src/components/admin/ServiceFormEditView.vue?vue&type=template&id=5210770c&scoped=true&
var ServiceFormEditViewvue_type_template_id_5210770c_scoped_true_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',{staticClass:"col-cont"},[_c('div',{staticClass:"col"},[(_vm.edit_view.step2.summary)?_c('div',{staticClass:"mbox"},[_c('div',{staticStyle:{"float":"right","display":"flex"}},[(_vm.form.status == 1 || _vm.form.status == -1)?_c('OnOffSlider',{staticStyle:{"margin-left":"auto","margin-right":"10px"},attrs:{"name":_vm.form.id,"true_value":"1","false_value":"-1"},on:{"prop-slider-value-changed":function($event){return _vm.$emit('change-service-status', $event)}},model:{value:(_vm.form.status),callback:function ($$v) {_vm.$set(_vm.form, "status", $$v)},expression:"form.status"}}):_vm._e(),_c('a',{on:{"click":function($event){return _vm.$emit('edit-view-toggle-steps', _vm.edit_view.step2)}}},[_vm._v(_vm._s(_vm.T__("EDIT")))])],1),_c('div',{staticClass:"txt-h4"},[_vm._v(_vm._s(_vm.T__("Event type details")))]),_c('div',{staticClass:"form-step-meta"},[_c('div',{staticStyle:{"font-size":"14px","padding-top":"10px","margin-bottom":"10px"}},[_vm._v(" "+_vm._s(_vm.T__("Event type name:"))+" "),_c('span',{staticClass:"et-name-edit-view",class:{
              private: _vm.saved_form.is_manage_private == 1
            }},[(_vm.saved_form.is_manage_private == 1)?_c('i'):_vm._e(),_vm._v(_vm._s(_vm.saved_form.name))])]),_c('div',{staticStyle:{"margin-bottom":"10px"}},[_vm._v(" "+_vm._s(_vm.T__("Invitee type:"))+" "+_vm._s(_vm._f("relationship_type_str")(_vm.saved_form.relationship_type))+" ")]),_c('div',{staticStyle:{"padding-bottom":"10px","display":"flex"}},[_vm._v(" "+_vm._s(_vm.T__("Location:"))+" "),(_vm.saved_form.locations.length)?_c('span',{staticStyle:{"display":"flex","padding":"0 5px"},domProps:{"innerHTML":_vm._f("locations_summary")(_vm.saved_form.locations)}}):_vm._e(),(!_vm.saved_form.locations.length)?_c('span',{staticStyle:{"display":"flex","padding":"0 5px"}},[_vm._v(" "+_vm._s(_vm.T__("Not set")))]):_vm._e()]),_c('div',{staticStyle:{"margin-bottom":"10px"}},[_vm._v(" "+_vm._s(_vm.T__("Short code:"))+" "),_c('input',{staticClass:"shortCode",attrs:{"onClick":"this.select();","type":"text","spellcheck":"false"},domProps:{"value":'[wpcal id=' + _vm.service_id + ']'}}),(
              _vm.$store.getters.get_admin_details(
                _vm.saved_form.service_admin_user_ids[0]
              ).profile_picture
            )?_c('img',{staticClass:"serviceEditViewOwnerPic",staticStyle:{"border-radius":"50%","float":"right"},attrs:{"src":_vm.$store.getters.get_admin_details(
                _vm.saved_form.service_admin_user_ids[0]
              ).profile_picture,"width":"30","title":_vm.$store.getters.get_admin_details(
                _vm.saved_form.service_admin_user_ids[0]
              ).display_name}}):_vm._e()]),_c('div',[_vm._v(_vm._s(_vm.saved_form.descr))]),_c('hr'),_c('div',{staticStyle:{"display":"flex"}},[(_vm.saved_form.post_id)?_c('div',[_c('a',{attrs:{"href":'post.php?post=' + _vm.saved_form.post_id + '&action=edit',"target":"_blank"}},[_vm._v(_vm._s(_vm.T__("Edit Booking Page"))+" "),_c('span',{staticStyle:{"font-size":"11px","font-weight":"600"}},[_vm._v("↗")])])]):_vm._e(),(_vm.saved_form.post_details.link)?_c('div',{staticStyle:{"margin-left":"auto","margin-right":"12px"}},[_c('a',{attrs:{"href":_vm.saved_form.post_details.link,"target":"_blank"}},[_vm._v(_vm._s(_vm.T__("View Booking Page"))+" "),_c('span',{staticStyle:{"font-size":"11px","font-weight":"600"}},[_vm._v("↗")])])]):_vm._e()])])]):_vm._e(),(_vm.edit_view.step2.form)?_c('ServiceFormStep2',{attrs:{"$v":_vm.$v,"view":_vm.view,"step":{ id: 2, upcoming: false, summary: false, form: true },"form":_vm.form,"saved_form":_vm.saved_form,"create_or_update_btn_is_enabled":_vm.create_or_update_btn_is_enabled},on:{"submit-form":function($event){return _vm.$emit('submit-form', $event)},"edit-view-toggle-steps":function($event){return _vm.$emit('edit-view-toggle-steps', _vm.edit_view.step2)}}}):_vm._e(),(_vm.edit_view.step3.summary)?_c('div',{staticClass:"mbox"},[_c('div',{staticStyle:{"float":"right"}},[_c('a',{on:{"click":function($event){return _vm.$emit('edit-view-toggle-steps', _vm.edit_view.step3)}}},[_vm._v(_vm._s(_vm.T__("EDIT")))])]),_c('div',{staticClass:"txt-h4"},[_vm._v(_vm._s(_vm.T__("Event type duration & timings")))]),_c('div',{staticClass:"form-step-meta mt10"},[_c('div',{staticStyle:{"margin-bottom":"10px"}},[_vm._v(" "+_vm._s(_vm.T__("Date range:"))+" "+_vm._s(_vm.service_date_range_summary)+" ")]),_c('div',{staticStyle:{"margin-bottom":"10px"}},[_c('div',[_vm._v(" "+_vm._s(_vm.T__("Event duration:"))+" "+_vm._s(_vm._f("mins_to_str")(_vm.saved_form.duration))+" ")]),_c('div',[_vm._v(" "+_vm._s(_vm.T__("Show start times every:"))+" "+_vm._s(_vm._f("mins_to_str")(_vm.saved_form.display_start_time_every))+" ")])]),_c('div',{staticStyle:{"text-decoration":"underline"}},[_vm._v(" "+_vm._s(_vm.T__("Availability for this event type"))+" ")]),_c('div',[_vm._v(" "+_vm._s(_vm.T__("Available hours:"))+" "),_c('div',{staticStyle:{"display":"inline-grid"}},_vm._l((_vm.saved_form
                .default_availability_details.periods),function(period,index){return _c('div',{key:index},[_vm._v(" "+_vm._s(_vm._f("wpcal_format_time_only")(period.from_time,_vm.time_format))+" - "+_vm._s(_vm._f("wpcal_format_time_only")(period.to_time,_vm.time_format))+" ")])}),0)]),_c('div',{staticStyle:{"margin-bottom":"10px"}},[_vm._v(" "+_vm._s(_vm.T__("Available days:"))+" "),_c('span',[_vm._v(" "+_vm._s(_vm._f("wpcal_format_weekdays_summary")(_vm.saved_form.default_availability_details.day_index_list))+" ")])]),_c('div',{staticStyle:{"margin-bottom":"10px"}},[_vm._v(" "+_vm._s(_vm.T__("Time zone:"))+" "),_c('span',[_vm._v(" "+_vm._s(_vm._f("wpcal_format_tz")(_vm.saved_form.timezone))+" ")])]),_c('div',[_c('div',{staticStyle:{"text-decoration":"underline"}},[_vm._v(" "+_vm._s(_vm.T__("Advanced settings"))+" ")]),_c('div',[_vm._v(" "+_vm._s(_vm.T__("Max bookings per day"))+": "+_vm._s(_vm.saved_form.max_booking_per_day ? _vm.saved_form.max_booking_per_day : "No limit")+" ")]),_c('div',[_vm._v(" "+_vm._s(_vm.T__("Minimum Scheduling Notice"))+": "),(_vm.saved_form.min_schedule_notice.type == 'none')?_c('span',[_vm._v(_vm._s(_vm.T__("None")))]):_vm._e(),(_vm.saved_form.min_schedule_notice.type == 'units')?_c('span',[_vm._v(" "+_vm._s(_vm.Tsprintf( /* translators: 1: int 2: mins or hours or days */ _vm.T__("Prevent booking of slots less than %1$s %2$s away."), this.saved_form.min_schedule_notice.time_units, this.saved_form.min_schedule_notice.time_units_in ))+" ")]):_vm._e(),(_vm.saved_form.min_schedule_notice.type == 'time_days_before')?_c('span',[_vm._v(" "+_vm._s(_vm.Tsprintf( /* translators: 1: time HH:mm 2: int */ _vm.T_n( "Prevent all bookings after %1$s %2$s day before.", "Prevent all bookings after %1$s %2$s days before.", this.saved_form.min_schedule_notice.days_before ), this.$options.filters.wpcal_format_time_only( this.saved_form.min_schedule_notice.days_before_time, _vm.time_format ) + (_vm.time_format == "HH:mm" ? " " + _vm.T__("hrs") : ""), this.saved_form.min_schedule_notice.days_before ))+" ")]):_vm._e()]),_c('div',[_vm._v(" "+_vm._s(_vm.T__("Event buffers"))+": "),(
                !_vm.saved_form.event_buffer_before &&
                  !_vm.saved_form.event_buffer_after
              )?_c('span',[_vm._v(" "+_vm._s(_vm.T__("None"))+" ")]):_vm._e(),(_vm.saved_form.event_buffer_before)?_c('span',[_vm._v(_vm._s(_vm.Tsprintf( /* translators: 1: int min or int hr int min */ _vm.T__("%1$s before event"), this.$options.filters.mins_to_str( this.saved_form.event_buffer_before ) )))]):_vm._e(),(
                _vm.saved_form.event_buffer_before &&
                  _vm.saved_form.event_buffer_after
              )?_c('span',[_vm._v(" & ")]):_vm._e(),(_vm.saved_form.event_buffer_after)?_c('span',[_vm._v(_vm._s(_vm.Tsprintf( /* translators: 1: int min or int hr int min */ _vm.T__("%1$s after event"), this.$options.filters.mins_to_str( this.saved_form.event_buffer_after ) )))]):_vm._e()])])]),_c('button',{staticClass:"wpc-btn secondary md mt20",staticStyle:{"width":"100%"},attrs:{"type":"button"},on:{"click":_vm.show_service_availability_modal}},[_vm._v(" "+_vm._s(_vm.T__("Set custom hours for specific days/dates"))+" ")]),_c('modal',{staticStyle:{"z-index":"9999"},attrs:{"name":"service_availability_modal","width":'950px',"height":'80%',"adaptive":true},on:{"before-close":_vm.service_availability_modal_before_close}},[_c('div',{staticClass:"modal_close_cont"},[_c('button',{on:{"click":function($event){return _vm.$modal.hide('service_availability_modal')}}})]),_c('ServiceAvailabilityCal',{ref:"service_availability_modal_contents",attrs:{"timezone":_vm.saved_form.timezone}})],1)],1):_vm._e(),(_vm.edit_view.step3.form)?_c('ServiceFormStep3',{staticStyle:{"margin-top":"20px"},attrs:{"$v":_vm.$v,"view":_vm.view,"step":{ id: 3, upcoming: false, summary: false, form: true },"form":_vm.form,"create_or_update_btn_is_enabled":_vm.create_or_update_btn_is_enabled},on:{"submit-form":function($event){return _vm.$emit('submit-form', $event)},"edit-view-toggle-steps":function($event){return _vm.$emit('edit-view-toggle-steps', _vm.edit_view.step3)},"may-show-service-availability-modal":_vm.may_show_service_availability_modal}}):_vm._e(),(_vm.edit_view.step5.summary)?_c('div',{staticClass:"mbox"},[_c('div',{staticStyle:{"float":"right","display":"flex"}},[_c('a',{on:{"click":function($event){return _vm.$emit('edit-view-toggle-steps', _vm.edit_view.step5)}}},[_vm._v(_vm._s(_vm.T__("EDIT")))])]),_c('div',{staticClass:"txt-h4"},[_vm._v(" "+_vm._s(_vm.T__("Event type notification"))+" "),_c('a',{staticClass:"icon-q-mark",attrs:{"href":"https://help.wpcal.io/en/article/what-are-event-type-notification-options-how-does-it-work-how-to-change-it-13cie5k/?utm_source=wpcal_plugin&utm_medium=contextual","target":"_blank"}})]),_c('div',{staticClass:"form-step-meta mt10"},[_c('div',{staticStyle:{"margin-bottom":"5px"}},[_vm._v(" "+_vm._s(_vm.T__("Invitee notification type:"))+" "+_vm._s(_vm.saved_form.invitee_notify_by == "calendar_invitation" ? "Calendar Invitation" : "Email")+" ")]),(_vm.saved_form.invitee_notify_by == 'calendar_invitation')?_c('div',{staticClass:"txt-help"},[_vm._v(" A calendar invitation will be sent directly from the calendar app eg. Google Calendar. If the 'Add bookings to...' calendar is not set, it will fallback to email."),_c('a',{attrs:{"href":"https://help.wpcal.io/en/article/what-are-event-type-notification-options-how-does-it-work-how-to-change-it-13cie5k/?utm_source=wpcal_plugin&utm_medium=contextual#1-what-are-event-type-notification-options-for-invitee-and-how-does-it-work","target":"_blank"}},[_vm._v("Learn more")]),_vm._v(". ")]):_vm._e(),(_vm.saved_form.invitee_notify_by == 'email')?_c('div',{staticClass:"txt-help"},[_vm._v(" An email notification with the details of their booking will be sent to the invitees. ")]):_vm._e()])]):_vm._e(),(_vm.edit_view.step5.form)?_c('ServiceFormStep5',{staticStyle:{"margin-top":"20px"},attrs:{"$v":_vm.$v,"view":_vm.view,"step":{ id: 5, upcoming: false, summary: false, form: true },"form":_vm.form,"create_or_update_btn_is_enabled":_vm.create_or_update_btn_is_enabled},on:{"submit-form":function($event){return _vm.$emit('submit-form', $event)},"edit-view-toggle-steps":function($event){return _vm.$emit('edit-view-toggle-steps', _vm.edit_view.step5)}}}):_vm._e(),_c('div',{staticClass:"mbox"},[_c('div',{staticClass:"form-step-meta",domProps:{"innerHTML":_vm._s(
          _vm.Tsprintf(
            /* translators: 1: html-tag-open 2: html-tag-close 3: email */
            _vm.T__(
              'New booking, rescheduling and cancellation notifications will be sent to %1$s %3$s %2$s (host\'s WP admin email).'
            ),
            '<span style="color:#131336">',
            '</span>',
            _vm.$store.getters.get_admin_details(
              _vm.saved_form.service_admin_user_ids[0]
            ).email
          )
        )}})])],1),_c('div',{staticClass:"col"},[_c('div',{staticStyle:{"font-size":"16px","font-weight":"500","margin":"0px 0 10px","display":"flex"}},[_vm._v(" "+_vm._s(_vm.T__("Additional Options"))+" "),_c('PremiumInfoBadge',{staticStyle:{"margin":"-10px 0 0 10px"},attrs:{"highlight_feature":"questions"}},[_c('em',[_vm._v("Free: 1 question"),_c('br'),_vm._v("Plus: Unlimited")])])],1),_c('ServiceInviteeQuestions',{on:{"save-questions":function($event){return _vm.$emit('save-questions', $event)}},model:{value:(_vm.form.invitee_questions),callback:function ($$v) {_vm.$set(_vm.form, "invitee_questions", $$v)},expression:"form.invitee_questions"}})],1),(_vm.saved_form.name)?_c('AlertAdminToUpdateProfile',{attrs:{"service_details":_vm.saved_form}}):_vm._e()],1)}
var ServiceFormEditViewvue_type_template_id_5210770c_scoped_true_staticRenderFns = []


// CONCATENATED MODULE: ./src/components/admin/ServiceFormEditView.vue?vue&type=template&id=5210770c&scoped=true&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"1bf56cee-vue-loader-template"}!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/vue-loader/lib??vue-loader-options!./src/components/admin/ServiceFormStep5.vue?vue&type=template&id=04bb10b6&
var ServiceFormStep5vue_type_template_id_04bb10b6_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',[_c('div',{directives:[{name:"show",rawName:"v-show",value:(_vm.step.form),expression:"step.form"}],staticClass:"step-single form active",attrs:{"id":'service_form_step' + _vm.step.id}},[_c('div',{staticClass:"step-title"},[_vm._v(_vm._s(_vm.T__("Event Type Notification")))]),_c('div',{staticClass:"form",on:{"submit":function($event){$event.preventDefault();}}},[_c('form-row',{attrs:{"validator":_vm.$v.form.name}},[_c('label',{attrs:{"for":"form_name"}},[_vm._v(" "+_vm._s(_vm.T__("Invitee notification type"))),_c('abbr',{staticClass:"required",attrs:{"title":"required"}},[_vm._v("*")]),_c('a',{staticClass:"icon-q-mark",attrs:{"href":"https://help.wpcal.io/en/article/what-are-event-type-notification-options-how-does-it-work-how-to-change-it-13cie5k/?utm_source=wpcal_plugin&utm_medium=contextual","target":"_blank"}}),_c('em',[_vm._v(_vm._s(_vm.T__("required")))])]),_c('span',{staticClass:"txt-help"},[_vm._v(" "+_vm._s(_vm.T__( "Select how the booking notification and reminder emails will be sent " ))+" ")]),_c('ul',{staticClass:"selector block"},[_c('li',[_c('input',{directives:[{name:"model",rawName:"v-model",value:(_vm.form.invitee_notify_by),expression:"form.invitee_notify_by"}],attrs:{"id":"form_invitee_notify_by_cal","name":"form_invitee_notify_by","type":"radio","value":"calendar_invitation"},domProps:{"checked":_vm._q(_vm.form.invitee_notify_by,"calendar_invitation")},on:{"change":function($event){return _vm.$set(_vm.form, "invitee_notify_by", "calendar_invitation")}}}),_c('label',{attrs:{"for":"form_invitee_notify_by_cal"}},[_c('div',{staticClass:"txt-h4"},[_vm._v(_vm._s(_vm.T__("Calendar invitation")))]),_c('div',{staticClass:"txt-help"},[_vm._v(" A calendar invitation will be sent directly from the calendar app eg. Google Calendar. If the 'Add bookings to...' calendar is not set, it will fallback to email. "),_c('a',{attrs:{"href":"https://help.wpcal.io/en/article/what-are-event-type-notification-options-how-does-it-work-how-to-change-it-13cie5k/?utm_source=wpcal_plugin&utm_medium=contextual#1-what-are-event-type-notification-options-for-invitee-and-how-does-it-work","target":"_blank"}},[_vm._v("Learn more")]),_vm._v(". ")])])]),_c('li',[_c('input',{directives:[{name:"model",rawName:"v-model",value:(_vm.form.invitee_notify_by),expression:"form.invitee_notify_by"}],attrs:{"id":"form_invitee_notify_by_email","name":"form_invitee_notify_by","type":"radio","value":"email"},domProps:{"checked":_vm._q(_vm.form.invitee_notify_by,"email")},on:{"change":function($event){return _vm.$set(_vm.form, "invitee_notify_by", "email")}}}),_c('label',{attrs:{"for":"form_invitee_notify_by_email"}},[_c('div',{staticClass:"txt-h4"},[_vm._v(_vm._s(_vm.T__("Email")))]),_c('div',{staticClass:"txt-help"},[_vm._v(" An email notification with the details of their booking will be sent to the invitees. ")])])])])]),_c('div',{staticClass:"wpc-form-row"},[(_vm.view == 'edit')?_c('button',{staticClass:"wpc-btn primary lg",attrs:{"type":"button","disabled":!_vm.create_or_update_btn_is_enabled},on:{"click":function($event){return _vm.$emit('submit-form', 5)}}},[_vm._v(" "+_vm._s(_vm.T__("Save"))+" ")]):_vm._e(),(_vm.view == 'edit')?_c('button',{staticClass:"wpc-btn tert-link sm mt10 center",attrs:{"type":"button"},on:{"click":function($event){return _vm.$emit('edit-view-toggle-steps')}}},[_vm._v(" "+_vm._s(_vm.T__("Cancel"))+" ")]):_vm._e()])],1)]),_c('div',{directives:[{name:"show",rawName:"v-show",value:(_vm.step.summary),expression:"step.summary"}],staticClass:"step-single summary"},[_c('div',{staticClass:"step-title"},[_c('span',[_vm._v(_vm._s(_vm.T__("Event Type Notification")))]),_c('a',{on:{"click":function($event){return _vm.$emit('set-active-step-and-toggle', 5)}}},[_vm._v(_vm._s(_vm.T__("EDIT")))])]),_c('div',{staticClass:"step-summary-content"})])])}
var ServiceFormStep5vue_type_template_id_04bb10b6_staticRenderFns = []


// CONCATENATED MODULE: ./src/components/admin/ServiceFormStep5.vue?vue&type=template&id=04bb10b6&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--13-0!./node_modules/thread-loader/dist/cjs.js!./node_modules/babel-loader/lib!./node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/vue-loader/lib??vue-loader-options!./src/components/admin/ServiceFormStep5.vue?vue&type=script&lang=js&
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
/* harmony default export */ var ServiceFormStep5vue_type_script_lang_js_ = ({
  name: "ServiceFormStep5",
  components: {},
  props: {
    view: {
      type: String
    },
    step: {
      type: Object,
      required: true
    },
    form: {
      type: Object,
      required: true
    },
    create_or_update_btn_is_enabled: {},
    $v: {}
  },
  data: function data() {
    return {};
  },
  methods: {},
  watch: {},
  mounted: function mounted() {}
});
// CONCATENATED MODULE: ./src/components/admin/ServiceFormStep5.vue?vue&type=script&lang=js&
 /* harmony default export */ var admin_ServiceFormStep5vue_type_script_lang_js_ = (ServiceFormStep5vue_type_script_lang_js_); 
// CONCATENATED MODULE: ./src/components/admin/ServiceFormStep5.vue





/* normalize component */

var ServiceFormStep5_component = Object(componentNormalizer["a" /* default */])(
  admin_ServiceFormStep5vue_type_script_lang_js_,
  ServiceFormStep5vue_type_template_id_04bb10b6_render,
  ServiceFormStep5vue_type_template_id_04bb10b6_staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* harmony default export */ var ServiceFormStep5 = (ServiceFormStep5_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"1bf56cee-vue-loader-template"}!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/vue-loader/lib??vue-loader-options!./src/components/admin/ServiceAvailabilityCal.vue?vue&type=template&id=0ceb2c66&scoped=true&
var ServiceAvailabilityCalvue_type_template_id_0ceb2c66_scoped_true_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('span',[(_vm.events != null)?_c('vue-cal',{ref:"vuecal",staticClass:"vuecal--blue-theme",staticStyle:{"align-self":"stretch"},attrs:{"default-view":"month","hide-view-selector":"","events":_vm.events,"disable-views":['years', 'year', 'week', 'day'],"time":false,"min-date":_vm.cal_min_date,"max-date":_vm.cal_max_date,"events-on-month-view":true,"on-event-click":_vm.cal_event_trigger_show_edit_date_availability},on:{"cell-click":function($event){return _vm.cal_cell_trigger_show_edit_date_availability('cell-click', $event)},"view-change":_vm.process_calendar_month_move_event}}):_vm._e(),(_vm.events == null && (_vm.show_expired_msg || _vm.show_error_unable_fetch))?_c('div',{staticStyle:{"height":"100%"}},[(_vm.show_expired_msg)?_c('h3',{staticClass:"error_in_popup"},[_vm._v(" This event type does not have any active or future schedule to customise."),_c('br'),_vm._v(" Please check the date range of the event type. ")]):(_vm.show_error_unable_fetch)?_c('h3',{staticClass:"error_in_popup"},[_vm._v(" Unable to fetch the schedule. If you think this is an error, please "),_c('a',{attrs:{"href":"https://wpcal.io/support/?utm_source=wpcal_plugin&utm_medium="}},[_vm._v("contact support")]),_vm._v(". ")]):_vm._e()]):_vm._e(),_c('div',{directives:[{name:"show",rawName:"v-show",value:(_vm.show_edit_date_availability_popup),expression:"show_edit_date_availability_popup"}],staticClass:"popper-cont",staticStyle:{"z-index":"10000","width":"360px"},attrs:{"id":"edit_date_availability"}},[_c('div',{staticClass:"popper_arrow",attrs:{"data-popper-arrow":""}}),_c('div',{directives:[{name:"show",rawName:"v-show",value:(_vm.show_edit_date_availability),expression:"show_edit_date_availability"}],staticClass:"mbox bg-light shadow edit-availability"},[_c('div',{staticClass:"txt-h4 bold center mb20 mt10"},[_vm._v(" "+_vm._s(_vm.T__("Edit Availability"))+" ")]),(
          _vm.current_customizing_availability_date_details &&
            _vm.current_customizing_availability_date_details.is_available == 0
        )?_c('div',[_c('div',{staticClass:"unavailable"},[_vm._v(_vm._s(_vm.T__("Unavailable")))]),_c('button',{staticClass:"wpc-btn secondary sm mt10 center",attrs:{"disabled":!_vm.current_date_popup_action_btns_is_enabled},on:{"click":function($event){return _vm.update_customizes_date_availability(
              'this_day_available_use_previous_periods'
            )}}},[_vm._v(" "+_vm._s(_vm.Tsprintf( /* translators: %s: date */ _vm.T__("Set as available on %s"), _vm.apply_to_this_date ))+" ")])]):_vm._e(),(_vm.current_customizing_availability_date_details)?_c('TimeRange1ToN',{attrs:{"hide_add_period_on_no_periods":"1"},model:{value:(_vm.current_customizing_availability_date_details.periods),callback:function ($$v) {_vm.$set(_vm.current_customizing_availability_date_details, "periods", $$v)},expression:"current_customizing_availability_date_details.periods"}}):_vm._e(),(
          _vm.current_customizing_availability_date_details &&
            _vm.current_customizing_availability_date_details.is_available == 1
        )?_c('button',{staticClass:"wpc-btn secondary sm mt10 center",attrs:{"disabled":!_vm.current_date_popup_action_btns_is_enabled},on:{"click":function($event){return _vm.update_customizes_date_availability('this_day_unavailable')}}},[_vm._v(" "+_vm._s(_vm.Tsprintf( /* translators: %s: date */ _vm.T__("Set as unavailable on %s"), _vm.apply_to_this_date ))+" ")]):_vm._e(),(_vm.current_customizing_availability_date_details)?_c('span',[_c('button',{staticClass:"wpc-btn primary md mt20 full-width",attrs:{"disabled":!_vm.current_date_popup_action_btns_is_enabled},on:{"click":function($event){return _vm.update_customizes_date_availability('this_date_only')}}},[_vm._v(" "+_vm._s(_vm.Tsprintf( /* translators: %s: date */ _vm.T__("Apply to %s only"), _vm.apply_to_this_date ))+" ")]),_c('button',{staticClass:"wpc-btn primary md mt10 full-width",attrs:{"disabled":!_vm.current_date_popup_action_btns_is_enabled},on:{"click":function($event){return _vm.update_customizes_date_availability('this_day_from_this_date')}}},[_vm._v(" "+_vm._s(_vm.Tsprintf( /* translators: 1: weekday 2: date */ _vm.T__("Apply to all %1$s from %2$s"), _vm.apply_to_all_days, _vm.apply_from_this_date ))+" ")]),_c('button',{staticClass:"wpc-btn secondary-link md mt10 center",attrs:{"disabled":!_vm.current_date_popup_action_btns_is_enabled},on:{"click":_vm.show_apply_to_multiple_dates_by_date}},[_vm._v(" "+_vm._s(_vm.T__("Apply to multiple..."))+" ")])]):_vm._e(),_c('button',{staticClass:"wpc-btn tert-link sm mt20 center",on:{"click":_vm.close_edit_date_availability_popup_by_date}},[_vm._v(" "+_vm._s(_vm.T__("Cancel"))+" ")])],1),_c('div',{directives:[{name:"show",rawName:"v-show",value:(_vm.show_apply_to_multiple_dates),expression:"show_apply_to_multiple_dates"}],staticClass:"mbox bg-light shadow edit-availability",staticStyle:{"z-index":"10001"},attrs:{"id":"apply_to_multiple_dates"}},[_c('div',{staticClass:"txt-h4 center mb20 mt10"},[_c('div',{staticClass:"icon-back",on:{"click":_vm.back_to_show_edit_date_availability}}),_vm._v(" "+_vm._s(_vm.T__("Apply to multiple..."))+" ")]),_c('ul',{staticClass:"selector apply-multiple inline time mt10 mb10"},[_c('li',[_c('input',{directives:[{name:"model",rawName:"v-model",value:(_vm.popup_edit_availability_form.apply_multiple_dates_days),expression:"popup_edit_availability_form.apply_multiple_dates_days"}],attrs:{"type":"radio","id":"multiple-dates","name":"apply_multiple_dates_days","value":"dates"},domProps:{"checked":_vm._q(_vm.popup_edit_availability_form.apply_multiple_dates_days,"dates")},on:{"change":function($event){return _vm.$set(_vm.popup_edit_availability_form, "apply_multiple_dates_days", "dates")}}}),_c('label',{attrs:{"for":"multiple-dates"}},[_vm._v(_vm._s(_vm.T__("dates")))])]),_c('li',[_c('input',{directives:[{name:"model",rawName:"v-model",value:(_vm.popup_edit_availability_form.apply_multiple_dates_days),expression:"popup_edit_availability_form.apply_multiple_dates_days"}],attrs:{"type":"radio","id":"repeating-days","name":"apply_multiple_dates_days","value":"days"},domProps:{"checked":_vm._q(_vm.popup_edit_availability_form.apply_multiple_dates_days,"days")},on:{"change":function($event){return _vm.$set(_vm.popup_edit_availability_form, "apply_multiple_dates_days", "days")}}}),_c('label',{attrs:{"for":"repeating-days"}},[_vm._v(_vm._s(_vm.T__("repeating days of the week")))])])]),(
          _vm.popup_edit_availability_form.apply_multiple_dates_days === 'dates'
        )?_c('div',[_c('v-date-picker',{ref:"multiple_dates_picker",staticStyle:{"width":"100%"},attrs:{"mode":"multiple","is-inline":"","locale":this.$store.getters.get_site_locale_intl,"value":"current_customizing_availability_date","min-date":_vm.apply_multipe_date_picker_min_date,"max-date":_vm.apply_multipe_date_picker_max_date},on:{"dayclick":_vm.log},model:{value:(_vm.popup_edit_availability_form.selected_dates),callback:function ($$v) {_vm.$set(_vm.popup_edit_availability_form, "selected_dates", $$v)},expression:"popup_edit_availability_form.selected_dates"}}),_c('div',{directives:[{name:"show",rawName:"v-show",value:(_vm.dates_selected_meta_txt),expression:"dates_selected_meta_txt"}],staticStyle:{"text-align":"center","padding-top":"5px"}},[_vm._v(" "+_vm._s(_vm.Tsprintf( /* translators: 1: int */ _vm.T__("%1$s selected"), _vm.dates_selected_meta_txt ))+" • "),_c('a',{on:{"click":function($event){_vm.popup_edit_availability_form.selected_dates = []}}},[_vm._v(_vm._s(_vm.T__("Clear")))])]),_c('button',{staticClass:"wpc-btn primary md mt20 full-width",attrs:{"disabled":!_vm.current_date_popup_action_btns_is_enabled},on:{"click":function($event){return _vm.update_customizes_date_availability('apply_to_selected_dates')}}},[_vm._v(" "+_vm._s(_vm.T__("Apply"))+" ")])],1):_vm._e(),(
          _vm.popup_edit_availability_form.apply_multiple_dates_days === 'days'
        )?_c('div',[_c('WeekDaysSelector',{model:{value:(_vm.popup_edit_availability_form.apply_multiple_days),callback:function ($$v) {_vm.$set(_vm.popup_edit_availability_form, "apply_multiple_days", $$v)},expression:"popup_edit_availability_form.apply_multiple_days"}}),_c('button',{staticClass:"wpc-btn primary md mt20 full-width",attrs:{"disabled":!_vm.current_date_popup_action_btns_is_enabled},on:{"click":function($event){return _vm.update_customizes_date_availability('apply_to_selected_days')}}},[_vm._v(" Apply to selected day(s) from "+_vm._s(_vm.apply_from_this_date)+" ")])],1):_vm._e(),_c('button',{staticClass:"wpc-btn tert-link sm mt20 center",on:{"click":_vm.back_to_show_edit_date_availability}},[_vm._v(" "+_vm._s(_vm.T__("Cancel"))+" ")])])]),_c('div',{staticStyle:{"padding":"10px","margin-top":"-35px"}},[_c('AdminInfoLine',{staticStyle:{"margin":"0","width":"max-content"},attrs:{"info_slug":"timings_in_timezone","timezone":_vm.timezone}}),_c('a',{staticStyle:{"float":"right","margin-top":"-25px"},attrs:{"href":"https://help.wpcal.io/en/article/how-to-customise-availability-for-event-type-one-or-more-days-qfc8ez/?utm_source=wpcal_plugin&utm_medium=contextual","target":"_blank"}},[_vm._v("Learn more about advance customization")])],1)],1)}
var ServiceAvailabilityCalvue_type_template_id_0ceb2c66_scoped_true_staticRenderFns = []


// CONCATENATED MODULE: ./src/components/admin/ServiceAvailabilityCal.vue?vue&type=template&id=0ceb2c66&scoped=true&

// EXTERNAL MODULE: ./node_modules/@babel/runtime/helpers/esm/typeof.js
var esm_typeof = __webpack_require__("53ca");

// EXTERNAL MODULE: ./node_modules/vue-cal/dist/vuecal.common.js
var vuecal_common = __webpack_require__("7fa7");
var vuecal_common_default = /*#__PURE__*/__webpack_require__.n(vuecal_common);

// EXTERNAL MODULE: ./node_modules/vue-cal/dist/vuecal.css
var vuecal = __webpack_require__("b55b");

// EXTERNAL MODULE: ./node_modules/lodash-es/map.js
var map = __webpack_require__("ce69");

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--13-0!./node_modules/thread-loader/dist/cjs.js!./node_modules/babel-loader/lib!./node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/vue-loader/lib??vue-loader-options!./src/components/admin/ServiceAvailabilityCal.vue?vue&type=script&lang=js&

//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//








/* harmony default export */ var ServiceAvailabilityCalvue_type_script_lang_js_ = ({
  components: {
    VueCal: vuecal_common_default.a,
    TimeRange1ToN: TimeRange1ToN,
    WeekDaysSelector: WeekDaysSelector
  },
  props: {
    timezone: {
      type: String
    }
  },
  data: function data() {
    return {
      service_availability_data: null,
      events: null,
      show_edit_date_availability_popup: false,
      show_edit_date_availability: false,
      show_apply_to_multiple_dates: false,
      current_customizing_availability_date: null,
      current_customizing_availability_date_formatted: null,
      current_customizing_availability_date_details: null,
      popup_instance: null,
      popup_edit_availability_form: {
        apply_multiple_dates_days: "dates",
        apply_multiple_days: [],
        selected_dates: []
      },
      can_load_availability_for_calendar_month_move_event: false,
      current_calendar_month: {},
      current_date_popup_action_btns_is_enabled: true,
      show_expired_msg: false,
      show_error_unable_fetch: false
    };
  },
  computed: {
    cal_min_date: function cal_min_date() {
      if (!this.service_availability_data || !this.service_availability_data.availability_date_ranges || !this.service_availability_data.availability_date_ranges.current_available_from_date) {
        return "";
      }

      return this.service_availability_data.availability_date_ranges.current_available_from_date;
    },
    cal_max_date: function cal_max_date() {
      if (!this.service_availability_data || !this.service_availability_data.availability_date_ranges || !this.service_availability_data.availability_date_ranges.current_available_to_date) {
        return "";
      }

      return this.service_availability_data.availability_date_ranges.current_available_to_date;
    },
    apply_multipe_date_picker_min_date: function apply_multipe_date_picker_min_date() {
      //to restrict selectable dates
      if (!this.service_availability_data || !this.service_availability_data.availability_date_ranges || !this.service_availability_data.availability_date_ranges.current_available_from_date) {
        return undefined;
      }

      return this.service_availability_data.availability_date_ranges.current_available_from_date;
    },
    apply_multipe_date_picker_max_date: function apply_multipe_date_picker_max_date() {
      //to restrict selectable dates
      if (!this.service_availability_data || !this.service_availability_data.availability_date_ranges || !this.service_availability_data.availability_date_ranges.current_available_to_date) {
        return undefined;
      }

      return this.service_availability_data.availability_date_ranges.current_available_to_date;
    },
    apply_to_this_date: function apply_to_this_date() {
      if (!this.current_customizing_availability_date || typeof this.current_customizing_availability_date.getMonth !== "function") {
        return "";
      }

      return Object(format["a" /* default */])(this.current_customizing_availability_date, "d MMM");
    },
    apply_from_this_date: function apply_from_this_date() {
      return this.apply_to_this_date;
    },
    apply_to_all_days: function apply_to_all_days() {
      if (!this.current_customizing_availability_date || typeof this.current_customizing_availability_date.getMonth !== "function") {
        return "";
      }

      return Object(format["a" /* default */])(this.current_customizing_availability_date, "EEEE") + "s";
    },
    dates_selected_meta_txt: function dates_selected_meta_txt() {
      var len = this.popup_edit_availability_form.selected_dates.length;
      var txt = "";

      if (len) {
        txt = this.Tsprintf(
        /* translators: %s: int */
        this.T_n("%s day", "%s days", len), len);
      }

      return txt;
    } // Following commented because - admin end not going to translated - also default english not working

    /*
    vue_cal_locale() {
      let site_locale = this.$store.getters.get_site_locale_intl;
      site_locale = site_locale.toLowerCase();
       let default_locale = "";
       let available_locale = [
        "ar",
        "bg",
        "bn",
        "bs",
        "ca",
        "cs",
        "da",
        "de",
        "el",
        "es",
        "fa",
        "fr",
        "he",
        "hr",
        "hu",
        "id",
        "is",
        "it",
        "ja",
        "ka",
        "ko",
        "lt",
        "nl",
        "no",
        "pl",
        "pt-br",
        "ro",
        "ru",
        "sk",
        "sl",
        "sr",
        "sv",
        "tr",
        "uk",
        "vi",
        "zh-cn",
        "zh-hk"
      ];
       if (available_locale.indexOf(site_locale) !== -1) {
        return site_locale;
      }
      let site_locale_parts = site_locale.split("-");
      if (available_locale.indexOf(site_locale_parts[0]) !== -1) {
        return site_locale_parts[0];
      }
      return default_locale;
    }*/

  },
  mounted: function mounted() {
    // Following commented because - admin end not going to translated - also default english not working
    // if (this.vue_cal_locale) {
    //   require("vue-cal/dist/i18n/" + this.vue_cal_locale + ".js");
    // }
    this.show_service_availability_cal();
  },
  methods: {
    show_service_availability_cal: function show_service_availability_cal() {
      var _this = this;

      var action = "edit_service_availability";
      var wpcal_request = {};
      wpcal_request[action] = {
        service_id: this.$route.params.service_id
      };
      var post_data = {
        action: "wpcal_process_admin_ajax_request",
        wpcal_request: wpcal_request
      };
      axios_default.a.post(window.wpcal_ajax.ajax_url, post_data).then(function (response) {
        // response = wpcal_clean_and_parse_json_response(response);
        //console.log(response);
        //alert(response.data.status);
        if (response.data[action] && response.data[action].status == "success") {
          _this.service_availability_data = response.data[action].service_availability_data;
          _this.events = response.data[action].service_availability_data.dates_availability_for_cal;
          setTimeout(function () {
            if (_this.$refs.vuecal) {
              _this.$refs.vuecal.switchView("month", _this.date_parse(_this.cal_min_date)); //switch over to first available date

            }

            _this.can_load_availability_for_calendar_month_move_event = true; //hackish fix to avoid inital calendar view change event and one after initial load
          }, 100);
        }

        if (response.data[action] && response.data[action].status == "error") {
          if (response.data[action].error && response.data[action].error == "expired") {
            _this.show_expired_msg = true;
            _this.show_error_unable_fetch = false;
          } else {
            _this.show_expired_msg = false;
            _this.show_error_unable_fetch = true;
          }
        }
      }).catch(function (error) {
        console.log(error);
      });
    },
    process_calendar_month_move_event: function process_calendar_month_move_event(event_values) {
      var month = Object(format["a" /* default */])(event_values.startDate, "LL");
      var year = Object(format["a" /* default */])(event_values.startDate, "yyyy");
      this.current_calendar_month = {
        month: month,
        year: year
      };
      this.load_service_availability_by_calendar_view(this.current_calendar_month);
      this.close_edit_date_availability_popup_by_date();
    },
    load_service_availability_by_calendar_view: function load_service_availability_by_calendar_view(current_calendar_month) {
      var _this2 = this;

      if (!this.can_load_availability_for_calendar_month_move_event || !current_calendar_month || Object(esm_typeof["a" /* default */])(current_calendar_month) !== "object" || !current_calendar_month.hasOwnProperty("month")) {
        return;
      }

      var action = "edit_service_availability_by_month";
      var wpcal_request = {};
      wpcal_request[action] = {
        service_id: this.$route.params.service_id,
        current_month_view: current_calendar_month
      };
      var post_data = {
        action: "wpcal_process_admin_ajax_request",
        wpcal_request: wpcal_request
      };
      axios_default.a.post(window.wpcal_ajax.ajax_url, post_data).then(function (response) {
        // response = wpcal_clean_and_parse_json_response(response);
        //console.log(response);
        //alert(response.data.status);
        _this2.handle_load_service_availability_by_calendar_view_response(response);
      }).catch(function (error) {
        console.log(error);
      });
    },
    handle_load_service_availability_by_calendar_view_response: function handle_load_service_availability_by_calendar_view_response(response) {
      var action = "edit_service_availability_by_month";

      if (response.data[action] && response.data[action].status == "success" && response.data[action].service_availability_data && response.data[action].service_availability_data.dates_availability_for_cal) {
        this.service_availability_data = response.data[action].service_availability_data;
        this.events = response.data[action].service_availability_data.dates_availability_for_cal;
      }
    },
    // eslint-disable-next-line no-unused-vars
    cal_event_trigger_show_edit_date_availability: function cal_event_trigger_show_edit_date_availability(event, e) {
      var _this3 = this;

      //console.log(event, e);
      var formatted_date = Object(format["a" /* default */])(event.startDate, "yyyy-MM-dd");
      this.current_customizing_availability_date = event.startDate;
      this.current_customizing_availability_date_formatted = formatted_date;
      setTimeout(function () {
        //setTimeout used  const element = document.querySelector(".vuecal__cell--selected"); will have right element
        _this3.show_edit_date_availability_popup_by_date();
      }, 10);
    },
    cal_cell_trigger_show_edit_date_availability: function cal_cell_trigger_show_edit_date_availability(name, event) {
      //console.log(name, event);
      if (!event) {
        //unexpectedly this calls twice per click, 2nd time null coming
        return;
      }

      var formatted_date = Object(format["a" /* default */])(event, "yyyy-MM-dd");
      this.current_customizing_availability_date = event;
      this.current_customizing_availability_date_formatted = formatted_date;
      this.show_edit_date_availability_popup_by_date();
    },
    show_edit_date_availability_popup_by_date: function show_edit_date_availability_popup_by_date() {
      var _multiple_dates_picke, _multiple_dates_picke2;

      this.show_edit_date_availability_popup = true;
      this.show_edit_date_availability = true;
      this.show_apply_to_multiple_dates = false;
      this.current_date_popup_action_btns_is_enabled = true; //console.log(typeof this.current_customizing_availability_date);

      this.current_customizing_availability_date_details = Object(cloneDeep["a" /* default */])(this.service_availability_data.dates_availability[this.current_customizing_availability_date_formatted]);
      var multiple_dates_picker = this.$refs.multiple_dates_picker;
      multiple_dates_picker === null || multiple_dates_picker === void 0 ? void 0 : (_multiple_dates_picke = multiple_dates_picker.$refs) === null || _multiple_dates_picke === void 0 ? void 0 : (_multiple_dates_picke2 = _multiple_dates_picke.calendar) === null || _multiple_dates_picke2 === void 0 ? void 0 : _multiple_dates_picke2.move(this.current_customizing_availability_date);
      this.show_edit_date_availability = true;
      var element = document.querySelector(".vuecal__cell--selected");
      var tooltip = document.querySelector("#edit_date_availability");

      if (this.popup_instance !== null) {
        this.popup_instance.destroy();
        this.popup_instance = null;
      }

      this.reset_popup_edit_availability_form(); // Pass the button, the tooltip, and some options, and Popper will do the
      // magic positioning for you:

      this.popup_instance = Object(lib_popper["a" /* createPopper */])(element, tooltip, {
        placement: "auto",
        modifiers: [{
          name: "offset",
          options: {
            offset: [0, 10]
          }
        }]
      });
    },
    show_apply_to_multiple_dates_by_date: function show_apply_to_multiple_dates_by_date() {
      this.show_edit_date_availability = false;
      this.show_apply_to_multiple_dates = true;

      if (this.popup_instance) {
        this.popup_instance.update();
      }
    },
    back_to_show_edit_date_availability: function back_to_show_edit_date_availability() {
      this.show_edit_date_availability = true;
      this.show_apply_to_multiple_dates = false;

      if (this.popup_instance) {
        this.popup_instance.update();
      }
    },
    close_edit_date_availability_popup_by_date: function close_edit_date_availability_popup_by_date() {
      this.show_edit_date_availability_popup = false;
    },
    update_customizes_date_availability: function update_customizes_date_availability(do_) {
      var _this4 = this;

      //validation pending
      this.current_date_popup_action_btns_is_enabled = false;
      var action_request_data = {};

      if (do_ == "this_day_unavailable") {
        action_request_data["is_available"] = 0;
        action_request_data["apply_to_days_or_dates"] = "dates";
        action_request_data["dates"] = [this.current_customizing_availability_date_formatted];
      } else if (do_ == "this_day_available_use_previous_periods") {
        action_request_data["is_available"] = 1;
        action_request_data["use_previous_periods"] = "1";
        action_request_data["apply_to_days_or_dates"] = "dates";
        action_request_data["dates"] = [this.current_customizing_availability_date_formatted];
      } else if (do_ == "this_date_only") {
        action_request_data["apply_to_days_or_dates"] = "dates";
        action_request_data["dates"] = [this.current_customizing_availability_date_formatted];
      } else if (do_ == "this_day_from_this_date") {
        action_request_data["apply_to_days_or_dates"] = "days";
        action_request_data["day_index_list"] = [Object(format["a" /* default */])(this.current_customizing_availability_date, "i")];
        action_request_data["from_date"] = this.current_customizing_availability_date_formatted;
      } else if (do_ == "apply_to_selected_dates") {
        action_request_data["apply_to_days_or_dates"] = "dates";
        action_request_data["dates"] = Object(map["a" /* default */])(this.popup_edit_availability_form.selected_dates, this.format_date);
      } else if (do_ == "apply_to_selected_days") {
        action_request_data["apply_to_days_or_dates"] = "days";
        action_request_data["day_index_list"] = this.popup_edit_availability_form.apply_multiple_days;
        action_request_data["from_date"] = this.current_customizing_availability_date_formatted;
      }

      if (do_ !== "this_day_unavailable" && do_ !== "this_day_available_use_previous_periods") {
        action_request_data["is_available"] = this.current_customizing_availability_date_details.is_available;

        if (this.current_customizing_availability_date_details.is_available == 1) {
          action_request_data["periods"] = this.clone_deep(this.current_customizing_availability_date_details.periods);
        }
      }

      var action1 = "customize_service_availability";
      var action2 = "edit_service_availability_by_month";
      var wpcal_request = {};
      wpcal_request[action1] = {
        service_id: this.$route.params.service_id,
        request_data: action_request_data
      };
      wpcal_request[action2] = {
        service_id: this.$route.params.service_id,
        current_month_view: this.current_calendar_month
      };
      var post_data = {
        action: "wpcal_process_admin_ajax_request",
        wpcal_request: wpcal_request
      };
      axios_default.a.post(window.wpcal_ajax.ajax_url, post_data).then(function (response) {
        // response = wpcal_clean_and_parse_json_response(response);
        //console.log(response);
        //alert(response.data.status);
        if (response.data[action1]) {
          //console.log(response.data[action1].status);
          _this4.notify_single_action_result(response.data[action1], {
            success_msg: "Settings saved.",
            validation_errors: {
              //to get feal of normal toast instead of inline
              target: "",
              zindex: "99999"
            }
          });

          if (response.data[action1].status === "success") {
            _this4.close_edit_date_availability_popup_by_date();
          }
        }

        _this4.handle_load_service_availability_by_calendar_view_response(response);
      }).catch(function (error) {
        console.log(error);
      }).then(function () {
        _this4.current_date_popup_action_btns_is_enabled = true;
      });
    },
    // get_periods_to_prefill_while_marking_available() {
    //   //validation pending
    //   let date = this.current_customizing_availability_date_formatted;
    //   let action = "get_periods_for_prefill_for_marking_available";
    //   let wpcal_request = {};
    //   wpcal_request[action] = {
    //     service_id: this.$route.params.service_id,
    //     date: date
    //   };
    //   let post_data = {
    //     action: "wpcal_process_admin_ajax_request",
    //     wpcal_request: wpcal_request
    //   };
    //
    //   axios
    //     .post(window.wpcal_ajax.ajax_url, post_data)
    //     .then(response => {
    //       // response = wpcal_clean_and_parse_json_response(response);
    //       console.log(response);
    //       //alert(response.data.status);
    //       if (
    //         response.data[action] &&
    //         response.data[action].availability_data
    //       ) {
    //         this.current_customizing_availability_date_details.periods =
    //           response.data[action].availability_data.periods;
    //         this.current_customizing_availability_date_details.is_available =
    //           "1";
    //       }
    //     })
    //     .catch(error => {
    //       console.log(error);
    //     });
    // },
    reset_popup_edit_availability_form: function reset_popup_edit_availability_form() {
      this.popup_edit_availability_form.apply_multiple_dates_days = "dates";
      this.popup_edit_availability_form.apply_multiple_days = [];
      this.popup_edit_availability_form.selected_dates = [];
    },
    log: function log(d) {
      console.log(d);
    },
    format_date: function format_date(date_obj) {
      return Object(format["a" /* default */])(date_obj, "yyyy-MM-dd");
    }
  }
});
// CONCATENATED MODULE: ./src/components/admin/ServiceAvailabilityCal.vue?vue&type=script&lang=js&
 /* harmony default export */ var admin_ServiceAvailabilityCalvue_type_script_lang_js_ = (ServiceAvailabilityCalvue_type_script_lang_js_); 
// EXTERNAL MODULE: ./src/components/admin/ServiceAvailabilityCal.vue?vue&type=style&index=0&id=0ceb2c66&scoped=true&lang=css&
var ServiceAvailabilityCalvue_type_style_index_0_id_0ceb2c66_scoped_true_lang_css_ = __webpack_require__("1400");

// EXTERNAL MODULE: ./src/components/admin/ServiceAvailabilityCal.vue?vue&type=style&index=1&lang=css&
var ServiceAvailabilityCalvue_type_style_index_1_lang_css_ = __webpack_require__("524c");

// CONCATENATED MODULE: ./src/components/admin/ServiceAvailabilityCal.vue







/* normalize component */

var ServiceAvailabilityCal_component = Object(componentNormalizer["a" /* default */])(
  admin_ServiceAvailabilityCalvue_type_script_lang_js_,
  ServiceAvailabilityCalvue_type_template_id_0ceb2c66_scoped_true_render,
  ServiceAvailabilityCalvue_type_template_id_0ceb2c66_scoped_true_staticRenderFns,
  false,
  null,
  "0ceb2c66",
  null
  
)

/* harmony default export */ var ServiceAvailabilityCal = (ServiceAvailabilityCal_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"1bf56cee-vue-loader-template"}!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/vue-loader/lib??vue-loader-options!./src/components/admin/ServiceInviteeQuestions.vue?vue&type=template&id=715d7140&scoped=true&
var ServiceInviteeQuestionsvue_type_template_id_715d7140_scoped_true_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',[_c('div',{staticClass:"mbox"},[_c('div',{staticClass:"txt-h5 caps"},[_vm._v(" "+_vm._s(_vm.T__("Invitee Questions"))+" "),_c('a',{staticClass:"icon-q-mark",attrs:{"href":"https://help.wpcal.io/en/article/event-type-questions-forms-1mt8d6a/?utm_source=wpcal_plugin&utm_medium=contextual","target":"_blank"}})]),_c('div',{staticClass:"txt-help"},[_vm._v(" "+_vm._s(_vm.T__( "Invitees will be asked to fill out their full name and email to book this event." ))+" ")]),_c('div',{staticClass:"wpc-form-row"},[_c('div',{staticClass:"txt-h5 mt20"},[_vm._v(_vm._s(_vm.T__("Full Name")))]),_c('input',{attrs:{"type":"text","readonly":""}})]),_c('div',{staticClass:"wpc-form-row"},[_c('div',{staticClass:"txt-h5 mt20"},[_vm._v(_vm._s(_vm.T__("Email")))]),_c('input',{attrs:{"type":"text","readonly":""}})]),_c('div',{directives:[{name:"drag-and-drop",rawName:"v-drag-and-drop:options",value:(
        _vm.questions.length > 1 ? _vm.questions_drag_drop_options : {}
      ),expression:"\n        questions.length > 1 ? questions_drag_drop_options : {}\n      ",arg:"options"}],key:_vm.questions.length > 1 ? 100 : 0},[_c('div',{attrs:{"id":"question_answers_cont"},on:{"reordered":_vm.reorder_selected_question}},_vm._l((_vm.questions),function(question,index){return _c('div',{key:index,staticClass:"wpc-form-row invitee-question",class:{
            hover: _vm.question_mouseover_index === index,
            disabled: !question.is_enabled || question.is_enabled == 0
          },attrs:{"id":'question_index_' + index,"data-id":index},on:{"mouseover":function($event){_vm.question_mouseover_index = index},"mouseleave":function($event){_vm.question_mouseover_index = null}}},[_c('hr'),_c('div',{staticClass:"txt-h5"},[_c('div',{class:{ 'reorder-handle': _vm.questions.length > 1 }}),_c('a',{staticClass:"edit-question-link",on:{"click":function($event){return _vm.edit_question(index)}}},[_vm._v(_vm._s(_vm.T__("EDIT")))]),_c('span',{staticClass:"pre question_title"},[_vm._v(_vm._s(question.question))])]),(question.answer_type == 'textarea')?_c('textarea',{staticClass:"answer_type_input",attrs:{"readonly":""}}):_vm._e(),_vm._v(" "),(
              question.answer_type == 'input_text' ||
                question.answer_type == 'input_phone'
            )?_c('input',{attrs:{"type":"text","readonly":""}}):_vm._e()])}),0)]),_c('hr',{staticStyle:{"margin-top":"0"}}),_c('button',{staticClass:"wpc-btn secondary md",staticStyle:{"width":"100%"},attrs:{"type":"button","id":"add_question","disabled":!_vm.can_active_add_question_btn},on:{"click":_vm.add_question}},[_vm._v(" "+_vm._s(_vm.T__("+ Add Question"))+" ")])]),_c('div',{directives:[{name:"show",rawName:"v-show",value:(_vm.can_show_edit_question_popup),expression:"can_show_edit_question_popup"}],staticClass:"popper-cont",staticStyle:{"z-index":"10000","width":"360px"},attrs:{"id":"edit_question_popup"}},[_c('div',{staticClass:"mbox shadow"},[_c('div',{staticClass:"popper_arrow",attrs:{"data-popper-arrow":""}}),_c('div',{staticClass:"txt-h5 bold",staticStyle:{"display":"flex"}},[_vm._v(" "+_vm._s(this.current_edit_question_index == "new" ? this.T__("Add") : this.T__("Edit"))+" "+_vm._s(_vm.T__("Question"))+" "),_c('OnOffSlider',{attrs:{"name":"current_edit_question_is_enabled","true_value":"1","false_value":"0"},model:{value:(_vm.current_edit_question.is_enabled),callback:function ($$v) {_vm.$set(_vm.current_edit_question, "is_enabled", $$v)},expression:"current_edit_question.is_enabled"}}),(this.current_edit_question_index != 'new')?_c('div',{staticClass:"delete",on:{"click":_vm.show_delete_confirmation}},[_vm._v(" "+_vm._s(_vm.T__("Delete question"))+" ")]):_vm._e()],1),_c('div',{staticClass:"wpc-form-row"},[_c('div',{staticClass:"txt-h5 mt20"},[_vm._v(_vm._s(_vm.T__("Answer type")))]),_c('form-row',{attrs:{"validator":_vm.$v.current_edit_question.answer_type}},[_c('select',{directives:[{name:"model",rawName:"v-model",value:(_vm.current_edit_question.answer_type),expression:"current_edit_question.answer_type"}],on:{"change":[function($event){var $$selectedVal = Array.prototype.filter.call($event.target.options,function(o){return o.selected}).map(function(o){var val = "_value" in o ? o._value : o.value;return val}); _vm.$set(_vm.current_edit_question, "answer_type", $event.target.multiple ? $$selectedVal : $$selectedVal[0])},function($event){return _vm.$v.current_edit_question.answer_type.$touch()}]}},[_c('option',{attrs:{"value":"textarea"}},[_vm._v(_vm._s(_vm.T__("Multi-line")))]),_c('option',{attrs:{"value":"input_text"}},[_vm._v(_vm._s(_vm.T__("Single-line")))]),_c('option',{attrs:{"value":"input_phone"}},[_vm._v(_vm._s(_vm.T__("Phone")))])])]),_c('div',{staticClass:"txt-h5 mt20"},[_vm._v(_vm._s(_vm.T__("Question")))]),_c('form-row',{attrs:{"validator":_vm.$v.current_edit_question.question}},[_c('textarea',{directives:[{name:"model",rawName:"v-model",value:(_vm.current_edit_question.question),expression:"current_edit_question.question"}],domProps:{"value":(_vm.current_edit_question.question)},on:{"blur":function($event){return _vm.$v.current_edit_question.question.$touch()},"input":function($event){if($event.target.composing){ return; }_vm.$set(_vm.current_edit_question, "question", $event.target.value)}}})]),_c('label',[_c('input',{directives:[{name:"model",rawName:"v-model",value:(_vm.current_edit_question.is_required),expression:"current_edit_question.is_required"}],attrs:{"type":"checkbox","true-value":"1","false-value":"0"},domProps:{"checked":Array.isArray(_vm.current_edit_question.is_required)?_vm._i(_vm.current_edit_question.is_required,null)>-1:_vm._q(_vm.current_edit_question.is_required,"1")},on:{"change":function($event){var $$a=_vm.current_edit_question.is_required,$$el=$event.target,$$c=$$el.checked?("1"):("0");if(Array.isArray($$a)){var $$v=null,$$i=_vm._i($$a,$$v);if($$el.checked){$$i<0&&(_vm.$set(_vm.current_edit_question, "is_required", $$a.concat([$$v])))}else{$$i>-1&&(_vm.$set(_vm.current_edit_question, "is_required", $$a.slice(0,$$i).concat($$a.slice($$i+1))))}}else{_vm.$set(_vm.current_edit_question, "is_required", $$c)}}}}),_vm._v(_vm._s(_vm.T__("Required")))]),_c('div',{staticClass:"txt-h5 mt20"},[_vm._v(_vm._s(_vm.T__("Answer")))]),(_vm.current_edit_question.answer_type == 'textarea')?_c('textarea',{attrs:{"readonly":""}}):_vm._e(),_vm._v(" "),(
            _vm.current_edit_question.answer_type == 'input_text' ||
              _vm.current_edit_question.answer_type == 'input_phone'
          )?_c('input',{attrs:{"type":"text","readonly":""}}):_vm._e(),_c('button',{staticClass:"wpc-btn primary md mt20 full-width",on:{"click":_vm.save_question}},[_vm._v(" "+_vm._s(_vm.T__("Done"))+" ")]),_c('button',{staticClass:"wpc-btn tert-link sm mt10 center",on:{"click":_vm.reset_current_question_and_hide_edit_question_popup}},[_vm._v(" "+_vm._s(_vm.T__("Cancel"))+" ")])],1)])])])}
var ServiceInviteeQuestionsvue_type_template_id_715d7140_scoped_true_staticRenderFns = []


// CONCATENATED MODULE: ./src/components/admin/ServiceInviteeQuestions.vue?vue&type=template&id=715d7140&scoped=true&

// EXTERNAL MODULE: ./node_modules/core-js/modules/es.number.is-integer.js
var es_number_is_integer = __webpack_require__("8ba4");

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--13-0!./node_modules/thread-loader/dist/cjs.js!./node_modules/babel-loader/lib!./node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/vue-loader/lib??vue-loader-options!./src/components/admin/ServiceInviteeQuestions.vue?vue&type=script&lang=js&




//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//





/* harmony default export */ var ServiceInviteeQuestionsvue_type_script_lang_js_ = ({
  components: {
    OnOffSlider: OnOffSlider
  },
  directives: {
    dragAndDrop: vue_draggable["VueDraggableDirective"]
  },
  model: {
    prop: "invitee_questions",
    event: "invitee-questions-changed"
  },
  props: {
    invitee_questions: {
      type: Object,
      default: function _default() {
        return {
          questions: []
        };
      }
    }
  },
  data: function data() {
    return {
      can_show_edit_question_popup: false,
      edit_question_popup_instance: null,
      current_edit_question: {},
      current_edit_question_index: false,
      default_question: {
        is_enabled: "1",
        is_required: "0",
        question: this.T__("Please share anything that will help prepare for our meeting."),
        answer_type: "textarea"
      },
      question_mouseover_index: null,
      questions_drag_drop_options: {
        dropzoneSelector: "#question_answers_cont",
        draggableSelector: ".invitee-question",
        handlerSelector: ".reorder-handle",
        reactivityEnabled: true,
        multipleDropzonesItemsDraggingEnabled: false,
        showDropzoneAreas: true
      }
    };
  },
  validations: {
    current_edit_question: {
      is_enabled: {
        required: validators["required"],
        must_be_1_or_0: common_func["s" /* must_be_1_or_0 */]
      },
      is_required: {
        required: validators["required"],
        must_be_1_or_0: common_func["s" /* must_be_1_or_0 */]
      },
      question: {
        required: validators["required"]
      },
      answer_type: {
        required: validators["required"],
        answer_type_in_array: Object(common_func["o" /* in_array */])(["textarea", "input_text", "input_phone"])
      }
    }
  },
  computed: {
    can_active_add_question_btn: function can_active_add_question_btn() {
      if (!this.invitee_questions || Object(esm_typeof["a" /* default */])(this.invitee_questions) !== "object" || !this.invitee_questions.hasOwnProperty("questions") || !Array.isArray(this.invitee_questions.questions)) {
        //these checks are used because computed property calls even before "created" lifecycle
        return false;
      }

      return this.invitee_questions.questions.length < 10; //return true;
    },
    questions: function questions() {
      if (!this.invitee_questions || Object(esm_typeof["a" /* default */])(this.invitee_questions) !== "object" || !this.invitee_questions.hasOwnProperty("questions")) {
        return [];
      }

      return this.invitee_questions.questions;
    }
  },
  methods: {
    add_question: function add_question() {
      //if (this.invitee_questions.questions.length == 0) {
      this.current_edit_question_index = "new";
      this.current_edit_question = this.clone_deep(this.default_question);

      if (this.invitee_questions.questions.length >= 1) {
        this.current_edit_question.question = "";
      }

      this.$v.current_edit_question.$reset();
      this.show_edit_question_popup(); //}
    },
    edit_question: function edit_question(index) {
      if (Object(esm_typeof["a" /* default */])(this.invitee_questions.questions[index]) === "object") {
        this.current_edit_question_index = index;
        this.current_edit_question = this.clone_deep(this.invitee_questions.questions[index]);
        this.$v.current_edit_question.$reset();
        this.show_edit_question_popup(index);
      }
    },
    delete_question: function delete_question() {
      if (Number.isInteger(this.current_edit_question_index)) {
        this.invitee_questions.questions.splice(this.current_edit_question_index, 1);
      } else if (this.current_edit_question_index === "new") {
        this.current_edit_question = {};
      }

      this.$emit("invitee-questions-changed", this.invitee_questions);
      this.$emit("save-questions");
      this.reset_current_question_and_hide_edit_question_popup();
    },
    save_question: function save_question() {
      this.$v.current_edit_question.$touch();

      if (this.$v.current_edit_question.$anyError) {
        this.scroll_to_first_validation_error();
        return false;
      }

      if (Number.isInteger(this.current_edit_question_index)) {
        this.invitee_questions.questions[this.current_edit_question_index] = this.current_edit_question;
      } else if (this.current_edit_question_index === "new") {
        this.invitee_questions.questions.push(this.current_edit_question);
      }

      this.$emit("invitee-questions-changed", this.invitee_questions);
      this.$emit("save-questions");
      this.reset_current_question_and_hide_edit_question_popup();
    },
    show_delete_confirmation: function show_delete_confirmation() {
      var _this = this;

      /*let button_str = this.T__("Delete");*/
      this.$modal.show("dialog", {
        title: this.T__("Delete this question?"),
        text: "",
        buttons: [{
          title: this.T__("Yes, delete."),
          class: "vue-dialog-button dialog-red-btn",
          handler: function handler() {
            _this.delete_question();

            _this.$modal.hide("dialog");
          }
        }, {
          title: "No, don't.",
          handler: function handler() {
            _this.$modal.hide("dialog");
          }
        }]
      });
    },
    show_edit_question_popup: function show_edit_question_popup() {
      var q_index = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : null;
      var element_selector = "#add_question";

      if (q_index !== null) {
        element_selector = "#question_index_" + q_index;
      }

      this.can_show_edit_question_popup = true;
      var element = document.querySelector(element_selector);
      var tooltip = document.querySelector("#edit_question_popup");

      if (this.edit_question_popup_instance !== null) {
        this.edit_question_popup_instance.destroy();
        this.edit_question_popup_instance = null;
      }

      this.edit_question_popup_instance = Object(lib_popper["a" /* createPopper */])(element, tooltip, {
        placement: "right",
        modifiers: [{
          name: "offset",
          options: {
            offset: [0, 20]
          }
        }]
      });
    },
    hide_edit_question_popup: function hide_edit_question_popup() {
      this.can_show_edit_question_popup = false;
    },
    reset_current_question_and_hide_edit_question_popup: function reset_current_question_and_hide_edit_question_popup() {
      this.hide_edit_question_popup();
      this.current_edit_question_index = false;
      this.current_edit_question = {};
    },
    reorder_selected_question: function reorder_selected_question(event) {
      var _this2 = this;

      var move_item_old_index = event.detail.ids[0];
      var move_item_new_index = event.detail.index;
      var moved_item = this.invitee_questions.questions.splice(move_item_old_index, 1);
      var final_item_new_index = move_item_old_index < move_item_new_index ? move_item_new_index - 1 : move_item_new_index; // console.log(
      //   move_item_old_index,
      //   "to",
      //   move_item_new_index,
      //   "final",
      //   final_item_new_index
      // );

      this.invitee_questions.questions.splice(final_item_new_index, 0, moved_item[0]);
      this.$nextTick(function () {
        _this2.$emit("save-questions");
      });
    } // normalise_empty_invitee_questions() {
    //   if (
    //     !this.invitee_questions ||
    //     typeof this.invitee_questions !== "object" ||
    //     !this.invitee_questions.hasOwnProperty("questions") ||
    //     !Array.isArray(this.invitee_questions.questions)
    //   ) {
    //     let default_invitee_questions = { questions: [] };
    //     this.$emit("invitee-questions-changed", default_invitee_questions);
    //   }
    // }

  }
});
// CONCATENATED MODULE: ./src/components/admin/ServiceInviteeQuestions.vue?vue&type=script&lang=js&
 /* harmony default export */ var admin_ServiceInviteeQuestionsvue_type_script_lang_js_ = (ServiceInviteeQuestionsvue_type_script_lang_js_); 
// EXTERNAL MODULE: ./src/components/admin/ServiceInviteeQuestions.vue?vue&type=style&index=0&id=715d7140&scoped=true&lang=css&
var ServiceInviteeQuestionsvue_type_style_index_0_id_715d7140_scoped_true_lang_css_ = __webpack_require__("8d79");

// CONCATENATED MODULE: ./src/components/admin/ServiceInviteeQuestions.vue






/* normalize component */

var ServiceInviteeQuestions_component = Object(componentNormalizer["a" /* default */])(
  admin_ServiceInviteeQuestionsvue_type_script_lang_js_,
  ServiceInviteeQuestionsvue_type_template_id_715d7140_scoped_true_render,
  ServiceInviteeQuestionsvue_type_template_id_715d7140_scoped_true_staticRenderFns,
  false,
  null,
  "715d7140",
  null
  
)

/* harmony default export */ var ServiceInviteeQuestions = (ServiceInviteeQuestions_component.exports);
// CONCATENATED MODULE: ./src/utils/on_before_leave_alert_if_unsaved_mixin.js

var on_before_leave_alert_if_unsaved_mixin_on_before_leave_alert_if_unsaved = function on_before_leave_alert_if_unsaved(saved_form_str, unsaved_form_str) {
  return {
    methods: {
      oblaiu_is_form_dirty: function oblaiu_is_form_dirty() {
        if (!this[saved_form_str] || !this[unsaved_form_str]) {
          return false;
        }

        var is_form_and_saved_form_are_equal = Object(isEqual["a" /* default */])(this[saved_form_str], this[unsaved_form_str]);

        return !is_form_and_saved_form_are_equal;
      },
      oblaiu_on_before_unload: function oblaiu_on_before_unload(e) {
        if (this.oblaiu_is_form_dirty() && !this.oblaiu_confirm_leave()) {
          // Cancel the event
          e.preventDefault(); // Chrome requires returnValue to be set

          e.returnValue = "";
        }
      },
      oblaiu_confirm_leave: function oblaiu_confirm_leave() {
        var form_closing = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : false;
        var msg = "Hey wait! You have unsaved changes. \nClick Ok to discard your changes and leave, or \nclick Cancel to stay in this page.";

        if (form_closing) {
          msg = "Hey wait! You have unsaved changes. \nClick Ok to discard your changes and continue, or \nclick Cancel to stay in this section.";
        }

        return window.confirm(msg);
      }
    },
    created: function created() {
      window.addEventListener("beforeunload", this.oblaiu_on_before_unload);
    },
    beforeDestroy: function beforeDestroy() {
      window.removeEventListener("beforeunload", this.oblaiu_on_before_unload);
    },
    beforeRouteLeave: function beforeRouteLeave(to, from, next) {
      // If the form is not dirty or the user confirmed they want to lose unsaved changes,
      // continue to next view
      if (this.skip_leaving_warining || !this.oblaiu_is_form_dirty() || this.oblaiu_confirm_leave()) {
        next();
      } else {
        // Cancel navigation
        next(false);
      }
    }
  };
};
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--13-0!./node_modules/thread-loader/dist/cjs.js!./node_modules/babel-loader/lib!./node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/vue-loader/lib??vue-loader-options!./src/components/admin/ServiceFormEditView.vue?vue&type=script&lang=js&


//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//








/* harmony default export */ var ServiceFormEditViewvue_type_script_lang_js_ = ({
  name: "ServiceFormEditView",
  mixins: [on_before_leave_alert_if_unsaved_mixin_on_before_leave_alert_if_unsaved("saved_form", "form")],
  components: {
    ServiceFormStep2: ServiceFormStep2,
    ServiceFormStep3: ServiceFormStep3,
    ServiceFormStep5: ServiceFormStep5,
    ServiceAvailabilityCal: ServiceAvailabilityCal,
    ServiceInviteeQuestions: ServiceInviteeQuestions,
    AlertAdminToUpdateProfile: AlertAdminToUpdateProfile,
    OnOffSlider: OnOffSlider
  },
  props: {
    view: {
      type: String
    },
    edit_view: {
      type: Object
    },
    form: {
      type: Object,
      required: true
    },
    saved_form: {
      type: Object,
      required: true
    },
    service_id: {},
    create_or_update_btn_is_enabled: {},
    $v: {}
  },
  computed: {
    service_date_range_summary: function service_date_range_summary() {
      var default_availability_details = this.saved_form.default_availability_details;

      if (default_availability_details.date_range_type === "relative") {
        var misc_value = default_availability_details.date_misc;
        var show_misc_value = misc_value.replace(/^\+((\d)*)D$/gm, function (x, y) {
          return y;
        });
        return this.Tsprintf(
        /* translators: %s: int */
        this.T__("%s rolling days into the future"), show_misc_value);
      } else if (default_availability_details.date_range_type == "from_to") {
        var from_date_str = this.$options.filters.wpcal_format_std_date(default_availability_details.from_date);
        var to_date_str = this.$options.filters.wpcal_format_std_date(default_availability_details.to_date);
        return this.Tsprintf(
        /* translators: 1: date 2: date */
        this.T__("From %1$s to %2$s"), from_date_str, to_date_str);
      } else if (default_availability_details.date_range_type == "infinite") {
        return this.T__("Indefinitely");
      }

      return "";
    }
  },
  methods: {
    may_show_service_availability_modal: function may_show_service_availability_modal() {
      var _this = this;

      //request comes from step3
      if (this.oblaiu_is_form_dirty()) {
        if (this.oblaiu_confirm_leave(true)) {
          this.form = this.clone_deep(this.saved_form);
          this.$emit("edit-view-toggle-steps", this.edit_view.step3);
          this.$nextTick(function () {
            _this.show_service_availability_modal();
          });
        } else {
          return;
        }
      } else {
        this.$emit("edit-view-toggle-steps", this.edit_view.step3);
        this.$nextTick(function () {
          _this.show_service_availability_modal();
        });
      }
    },
    show_service_availability_modal: function show_service_availability_modal() {
      this.$modal.show("service_availability_modal");
    },
    service_availability_modal_before_close: function service_availability_modal_before_close(event) {
      var child = this.$refs.service_availability_modal_contents;

      if (child.show_edit_date_availability_popup) {
        child.close_edit_date_availability_popup_by_date();
        event.stop();
      }
    }
  }
});
// CONCATENATED MODULE: ./src/components/admin/ServiceFormEditView.vue?vue&type=script&lang=js&
 /* harmony default export */ var admin_ServiceFormEditViewvue_type_script_lang_js_ = (ServiceFormEditViewvue_type_script_lang_js_); 
// EXTERNAL MODULE: ./src/components/admin/ServiceFormEditView.vue?vue&type=style&index=0&id=5210770c&lang=css&scoped=true&
var ServiceFormEditViewvue_type_style_index_0_id_5210770c_lang_css_scoped_true_ = __webpack_require__("b812");

// CONCATENATED MODULE: ./src/components/admin/ServiceFormEditView.vue






/* normalize component */

var ServiceFormEditView_component = Object(componentNormalizer["a" /* default */])(
  admin_ServiceFormEditViewvue_type_script_lang_js_,
  ServiceFormEditViewvue_type_template_id_5210770c_scoped_true_render,
  ServiceFormEditViewvue_type_template_id_5210770c_scoped_true_staticRenderFns,
  false,
  null,
  "5210770c",
  null
  
)

/* harmony default export */ var ServiceFormEditView = (ServiceFormEditView_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--13-0!./node_modules/thread-loader/dist/cjs.js!./node_modules/babel-loader/lib!./node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/vue-loader/lib??vue-loader-options!./src/views/admin/ServiceForm.vue?vue&type=script&lang=js&



//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//










/* harmony default export */ var ServiceFormvue_type_script_lang_js_ = ({
  name: "ServiceForm",
  mixins: [on_before_leave_alert_if_unsaved_mixin_on_before_leave_alert_if_unsaved("saved_form", "form"), admin_service_status_toggle_mixin],
  props: {
    msg: String
  },
  components: {
    ServiceFormStep1: ServiceFormStep1,
    ServiceFormStep2: ServiceFormStep2,
    ServiceFormStep3: ServiceFormStep3,
    ServiceFormEditView: ServiceFormEditView
  },
  data: function data() {
    return {
      view: "",
      service_id: null,
      form: {
        name: "",
        status: "",
        locations: [],
        descr: "",
        post_id: "",
        color: "",
        relationship_type: "",
        timezone: "",
        duration: "30",
        display_start_time_every: "15",
        max_booking_per_day: "",
        min_schedule_notice: {
          type: "units",
          time_units: "4",
          time_units_in: "hrs",
          days_before_time: "00:00:00",
          days_before: 0
        },
        event_buffer_before: 0,
        event_buffer_after: 0,
        is_manage_private: "0",
        invitee_notify_by: "calendar_invitation",
        service_admin_user_ids: [this.$store.getters.get_current_admin_details.id],
        invitee_questions: {
          questions: []
        },
        default_availability_details: {
          date_range_type: "relative",
          from_date: "",
          to_date: "",
          date_misc: "+60D",
          periods: [],
          day_index_list: []
        },
        post_details: {
          status: "",
          link: ""
        }
      },
      saved_form: this.clone_deep(this.form),
      //to avoid console error on initial rendering.
      tabs_flow: {
        active_step_id: 1,
        steps: [{
          id: 1,
          form: true,
          summary: false,
          upcoming: false
        }, {
          id: 2,
          form: false,
          summary: false,
          upcoming: true
        }, {
          id: 3,
          form: false,
          summary: false,
          upcoming: true
        }]
      },
      edit_view: {
        active_step_id: null,
        step2: {
          //Event type details
          id: 2,
          summary: true,
          form: false
        },
        step3: {
          //Event type duration &amp; timings
          id: 3,
          summary: true,
          form: false
        },
        step5: {
          //Event type communication
          id: 5,
          summary: true,
          form: false
        }
      },
      create_or_update_btn_is_enabled: true,
      skip_leaving_warining: false,
      service_name_last_saved: ""
    };
  },
  validations: {
    form: {
      name: {
        required: validators["required"]
      },
      // locations: "",
      // descr: "",
      // post_id: "",
      color: {
        required: validators["required"]
      },
      relationship_type: {
        required: validators["required"]
      },
      timezone: {
        required: validators["required"]
      },
      duration: {
        required: validators["required"],
        integer: validators["integer"],
        between_duration: Object(validators["between"])(1, 1440)
      },
      display_start_time_every: {
        required: validators["required"],
        integer: validators["integer"],
        between_display_start_time_every: Object(validators["between"])(1, 1440)
      },
      max_booking_per_day: {
        integer: validators["integer"],
        between_max_booking_per_day: Object(validators["between"])(1, 1440)
      },
      min_schedule_notice: {
        //type: "units",
        time_units: {
          integer: validators["integer"],
          required: Object(validators["requiredIf"])(function () {
            return this.form.min_schedule_notice.type === "units";
          })
        },
        //time_units_in: "hrs",
        days_before_time: {
          valid_time: common_func["z" /* valid_time */],
          required: Object(validators["requiredIf"])(function () {
            return this.form.min_schedule_notice.type === "time_days_before";
          })
        } //days_before: 1

      },
      event_buffer_before: {
        integer: validators["integer"],
        between_event_buffer_before: Object(validators["between"])(0, 300)
      },
      event_buffer_after: {
        integer: validators["integer"],
        between_event_buffer_before: Object(validators["between"])(0, 300)
      },
      is_manage_private: {
        required: validators["required"],
        must_be_1_or_0: common_func["s" /* must_be_1_or_0 */]
      },
      invitee_notify_by: {
        required: validators["required"],
        invitee_notify_by_in_array: Object(common_func["o" /* in_array */])(["calendar_invitation", "email"])
      },
      service_admin_user_ids: {
        required: validators["required"],
        minLength: Object(validators["minLength"])(1)
      },
      // invitee_questions: {
      //   questions: []
      // },
      default_availability_details: {
        date_range_type: {
          required: validators["required"],
          date_range_type_in_array: Object(common_func["o" /* in_array */])(["relative", "from_to", "infinite"])
        },
        date_misc: {
          required: Object(validators["requiredIf"])(function () {
            return this.form.default_availability_details.date_range_type === "relative";
          })
        },
        from_date: {
          required: Object(validators["requiredIf"])(function () {
            return this.form.default_availability_details.date_range_type === "from_to";
          }),
          valid_date: common_func["w" /* valid_date */]
        },
        to_date: {
          required: Object(validators["requiredIf"])(function () {
            return this.form.default_availability_details.date_range_type === "from_to";
          }),
          valid_date: common_func["w" /* valid_date */],
          min_date: Object(common_func["q" /* min_date_as */])("from_date")
        },
        // : "",
        // date_misc: "+60D",
        periods: {
          required: validators["required"],
          minLength: Object(validators["minLength"])(1),
          check_periods_collide: common_func["h" /* check_periods_collide */],
          $each: {
            from_time: {
              required: validators["required"],
              valid_time: common_func["z" /* valid_time */]
            },
            to_time: {
              required: validators["required"],
              valid_time: common_func["z" /* valid_time */],
              min_time: Object(common_func["r" /* min_time_as */])("from_time")
            }
          }
        },
        day_index_list: {
          required: validators["required"],
          subset_working_days: Object(common_func["t" /* subset */])([1, 2, 3, 4, 5, 6, 7])
        }
      }
    },
    step1: ["form.relationship_type"],
    step2: ["form.name", "form.color", "form.is_manage_private", "form.service_admin_user_ids"],
    step3: ["form.duration", "form.display_start_time_every", "form.timezone", "form.max_booking_per_day", "form.min_schedule_notice.time_units", "form.min_schedule_notice.days_before_time", "form.event_buffer_before", "form.event_buffer_after", "form.default_availability_details.date_range_type", "form.default_availability_details.date_misc", "form.default_availability_details.from_date", "form.default_availability_details.to_date", "form.default_availability_details.periods", "form.default_availability_details.day_index_list"],
    step5: ["form.invitee_notify_by"],
    step3_advance_options: ["form.max_booking_per_day", "form.min_schedule_notice.time_units", "form.min_schedule_notice.days_before_time", "form.event_buffer_before", "form.event_buffer_after"]
  },
  computed: {
    page_title: function page_title() {
      var title = this.T__("Create New Event Type");

      if (this.$route.name == "service_edit") {
        title = this.T__("Event Type - ") + this.saved_form.name;
      }

      return title;
    },
    document_title: function document_title() {
      var title = this.T__("Create New Event Type");

      if (this.$route.name == "service_edit") {
        var _this$saved_form;

        title = "";

        if ((_this$saved_form = this.saved_form) !== null && _this$saved_form !== void 0 && _this$saved_form.name) {
          title = this.saved_form.name + " ‹ ";
        }

        title += this.T__("Event Type");
      }

      return title;
    }
  },
  methods: {
    set_active_step: function set_active_step(step_id) {
      this.tabs_flow.active_step_id = step_id;
    },
    toggle_steps: function toggle_steps() {
      for (var i in this.tabs_flow.steps) {
        if (this.tabs_flow.active_step_id == this.tabs_flow.steps[i].id) {
          this.tabs_flow.steps[i].form = true;
          this.tabs_flow.steps[i].summary = false;
          this.tabs_flow.steps[i].upcoming = false;
        } else if (this.tabs_flow.active_step_id < this.tabs_flow.steps[i].id) {
          this.tabs_flow.steps[i].form = false;
          this.tabs_flow.steps[i].summary = false;
          this.tabs_flow.steps[i].upcoming = true;
        } else if (this.tabs_flow.active_step_id > this.tabs_flow.steps[i].id) {
          this.tabs_flow.steps[i].form = false;
          this.tabs_flow.steps[i].summary = true;
          this.tabs_flow.steps[i].upcoming = false;
        }
      }
    },
    edit_view_toggle_steps: function edit_view_toggle_steps(step) {
      if (!step.form) {
        //currently close, check any other form is open, if so try to close -accordian style(one open at a time)
        var other_step_id = step.id != this.edit_view.active_step_id ? this.edit_view.active_step_id : null;

        if (other_step_id && this.edit_view["step" + other_step_id].form) {
          if (this.oblaiu_is_form_dirty()) {
            if (this.oblaiu_confirm_leave(true)) {
              this.form = this.clone_deep(this.saved_form);
            } else {
              return;
            }
          }

          this.edit_view["step" + other_step_id].form = false;
          this.edit_view["step" + other_step_id].summary = true;
          this.$v["step" + other_step_id].$reset();
          this.edit_view.active_step_id = other_step_id == this.edit_view.active_step_id ? null : this.edit_view.active_step_id;
        }
      } else {
        if (this.oblaiu_is_form_dirty()) {
          this.form = this.clone_deep(this.saved_form);
        }
      }

      step.summary = !step.summary;
      step.form = !step.form;
      this.$v["step" + step.id].$reset();

      if (step.form) {
        //form opened
        this.edit_view.active_step_id = step.id;
        this.scroll_to_if_not_in_view_port("#service_form_step" + step.id);
      } else {
        this.edit_view.active_step_id = null;
      }
    },
    set_active_step_and_toggle: function set_active_step_and_toggle(step_id) {
      this.set_active_step(step_id);
      this.toggle_steps();
    },
    edit_view_form_submit: function edit_view_form_submit() {
      this.form_submit();
    },
    edit_view_reset: function edit_view_reset() {
      this.edit_view.step2.summary = true;
      this.edit_view.step2.form = false;
      this.edit_view.step3.summary = true;
      this.edit_view.step3.form = false;
      this.edit_view.step5.summary = true;
      this.edit_view.step5.form = false;
      this.edit_view.active_step_id = null;
    },
    validate_may_navigate_or_submit: function validate_may_navigate_or_submit(step_id) {
      if (this.view == "add") {
        if (step_id == 1 || step_id == 2 || step_id == 3) {
          this.$v["step" + step_id].$touch(); //trigger validation

          if (step_id == 3) {
            if (this.$v.step3_advance_options.$anyError) {
              this.$root.$emit("show-advance-options");
            }
          }

          if (!this.$v["step" + step_id].$anyError) {
            if (step_id == 1 || step_id == 2) {
              var next_step_id = step_id + 1;
              this.set_active_step_and_toggle(next_step_id);
            } else if (step_id == 3) {
              this.form_submit();
            }
          } else {
            this.scroll_to_first_validation_error();
          }
        }
      } else if (this.view == "edit") {
        if (step_id == 2 || step_id == 3 || step_id == 5) {
          this.$v["step" + step_id].$touch(); //trigger validation

          if (step_id == 3) {
            if (this.$v.step3_advance_options.$anyError) {
              this.$root.$emit("show-advance-options");
            }
          }

          if (!this.$v["step" + step_id].$anyError) {
            this.edit_view_form_submit();
          } else {
            this.scroll_to_first_validation_error();
          }
        }
      }
    },
    load_page: function load_page() {
      if (this.$route.name == "service_add") {
        this.load_add_page();
      } else if (this.$route.name == "service_edit") {
        this.load_edit_page();
      }
    },
    load_add_page: function load_add_page() {
      var _this = this;

      return Object(asyncToGenerator["a" /* default */])( /*#__PURE__*/regeneratorRuntime.mark(function _callee() {
        var general_setting;
        return regeneratorRuntime.wrap(function _callee$(_context) {
          while (1) {
            switch (_context.prev = _context.next) {
              case 0:
                Object.assign(_this.$data, _this.$options.data.apply(_this)); //re-initilize data in Vue

                _this.view = "add";
                general_setting = _this.$store.getters.get_general_settings;

                if (general_setting.hasOwnProperty("working_hours")) {
                  _this.form.default_availability_details.periods.push(general_setting.working_hours);
                }

                if (general_setting.hasOwnProperty("working_days")) {
                  _this.form.default_availability_details.day_index_list = _this.clone_deep(general_setting.working_days);
                }

                if (general_setting.hasOwnProperty("timezone")) {
                  _this.form.timezone = _this.clone_deep(general_setting.timezone);
                }

                _this.saved_form = _this.clone_deep(_this.form); //here saved_form is default form data

              case 7:
              case "end":
                return _context.stop();
            }
          }
        }, _callee);
      }))();
    },
    load_edit_page: function load_edit_page() {
      var _this2 = this;

      Object.assign(this.$data, this.$options.data.apply(this)); //re-initilize data in Vue

      this.view = "edit";
      this.service_id = this.$route.params.service_id;
      this.saved_form.service_admin_user_ids = [0]; //to avoid showing current admin profile picture till data loads

      var action = "edit_service";
      var wpcal_request = {};
      wpcal_request[action] = {
        service_id: this.service_id
      };
      var post_data = {
        action: "wpcal_process_admin_ajax_request",
        wpcal_request: wpcal_request
      };
      axios_default.a.post(window.wpcal_ajax.ajax_url, post_data).then(function (response) {
        //console.log(response);
        _this2.notify_single_action_result(response.data[action], {
          dont_notify_success: true
        });

        if (response.data && response.data[action].status == "error" && (response.data[action].error == "access_denied" || response.data[action].error == "service_no_longer_editable")) {
          _this2.form = _this2.clone_deep(_this2.saved_form); //hack fix - to avoid on_before_leave_alert_if_unsaved alert

          _this2.$router.push("/event-types");

          return;
        }

        if (response.data && response.data[action].hasOwnProperty("service_data")) {
          _this2.form = response.data[action].service_data;
          _this2.saved_form = _this2.clone_deep(_this2.form);
        }
      }).catch(function (error) {
        console.log(error);
      });
    },
    form_submit: function form_submit() {
      var _this3 = this;

      return Object(asyncToGenerator["a" /* default */])( /*#__PURE__*/regeneratorRuntime.mark(function _callee2() {
        var action, form_data, wpcal_request, action_edit_service, post_data;
        return regeneratorRuntime.wrap(function _callee2$(_context2) {
          while (1) {
            switch (_context2.prev = _context2.next) {
              case 0:
                _this3.clear_validation_errors_cont();

                _this3.create_or_update_btn_is_enabled = false;

                if (_this3.$route.name == "service_add") {
                  action = "add_service";
                } else if (_this3.$route.name == "service_edit") {
                  action = "update_service";
                }

                form_data = _this3.clone_deep(_this3.form);

                if (form_data.hasOwnProperty("invitee_questions") && form_data.invitee_questions.hasOwnProperty("questions") && form_data.invitee_questions.questions.length === 0) {
                  form_data.invitee_questions["__questions_count"] = 0; //workaround to post this array if 'questions' is empty, currently that is only key
                }

                wpcal_request = {};
                wpcal_request[action] = {
                  service_id: _this3.service_id,
                  service_data: form_data
                };
                action_edit_service = "edit_service";

                if (_this3.$route.name == "service_edit") {
                  wpcal_request[action_edit_service] = {
                    service_id: _this3.service_id
                  };

                  _this3.log_service_name_last_saved();
                }

                post_data = {
                  action: "wpcal_process_admin_ajax_request",
                  wpcal_request: wpcal_request
                };
                _context2.next = 12;
                return axios_default.a.post(window.wpcal_ajax.ajax_url, post_data).then(function (response) {
                  //console.log(response);
                  //alert(response.data[action].status);
                  var success_msg = _this3.T__("Event type updated successfully.");

                  if (action === "add_service") {
                    success_msg = _this3.T__("Event type added successfully.");
                  }

                  _this3.notify_single_action_result(response.data[action], {
                    success_msg: success_msg
                  });

                  if (_this3.$route.name == "service_add") {
                    if (response.data && response.data[action].status == "success" && response.data[action].hasOwnProperty("service_id")) {
                      _this3.skip_leaving_warining = true;
                      var service_id = response.data[action].service_id;

                      _this3.$router.push("/event-type/edit/" + service_id);
                    }
                  }

                  if (_this3.$route.name == "service_edit") {
                    if (response.data && response.data[action].status === "success") {
                      var current_submitted_step_id = _this3.edit_view.step2.form ? 2 : _this3.edit_view.step3.form ? 3 : "";

                      if (current_submitted_step_id) {
                        _this3.scroll_to_if_not_in_view_port("#service_form_step" + current_submitted_step_id);
                      }

                      _this3.edit_view_reset();
                    }
                  }

                  if (response.data && response.data.hasOwnProperty(action_edit_service) && response.data[action_edit_service].hasOwnProperty("service_data")) {
                    _this3.form = response.data[action_edit_service].service_data;
                    _this3.saved_form = _this3.clone_deep(_this3.form);

                    _this3.may_alert_to_update_booking_page_on_service_name_change();
                  }
                }).catch(function (error) {
                  console.log(error);
                }).then(function () {
                  _this3.create_or_update_btn_is_enabled = true;
                });

              case 12:
              case "end":
                return _context2.stop();
            }
          }
        }, _callee2);
      }))();
    },
    log_service_name_last_saved: function log_service_name_last_saved() {
      if (this.view != "edit") {
        return;
      }

      this.service_name_last_saved = this.saved_form.name;
    },
    may_alert_to_update_booking_page_on_service_name_change: function may_alert_to_update_booking_page_on_service_name_change() {
      if (this.view != "edit") {
        return;
      }

      if (this.service_name_last_saved != this.saved_form.name) {
        this.$modal.show("dialog", {
          title: this.T__("The event type name has been updated."),
          text: this.Tsprintf(
          /* translators: 1: html-tag-open 2: html-tag-close */
          this.T__("Please note that this will not change the booking page title. If you want to change that, you can do so here - %1$sEdit Booking Page %2$s"), '<a style="font-size:14px;" href="post.php?post=' + this.saved_form.post_id + '&action=edit" target="_blank">', '<span style="font-size:11px; font-weight:600; position: absolute; margin: 2px;">&nearr;</span></a>'),
          buttons: [{
            title: this.T__("Okay"),
            default: true // Will be triggered by default if 'Enter' pressed.

          }]
        });
      }
    }
  },
  created: function created() {
    this.load_page();
    this.$store.dispatch("load_managed_active_admins_details");
  },
  mounted: function mounted() {
    //console.log(this.$route);
    //this.set_active_step(3);
    this.toggle_steps();
  },
  watch: {
    //eslint-disable-next-line
    "$route.name": function $routeName() {
      //console.log(new_route_name, old_route_name);
      this.load_page();
    },
    document_title: function document_title() {
      this.$store.dispatch("set_document_title", this.document_title);
    }
  }
});
// CONCATENATED MODULE: ./src/views/admin/ServiceForm.vue?vue&type=script&lang=js&
 /* harmony default export */ var admin_ServiceFormvue_type_script_lang_js_ = (ServiceFormvue_type_script_lang_js_); 
// CONCATENATED MODULE: ./src/views/admin/ServiceForm.vue





/* normalize component */

var ServiceForm_component = Object(componentNormalizer["a" /* default */])(
  admin_ServiceFormvue_type_script_lang_js_,
  ServiceFormvue_type_template_id_a57f84d0_render,
  ServiceFormvue_type_template_id_a57f84d0_staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* harmony default export */ var ServiceForm = (ServiceForm_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"1bf56cee-vue-loader-template"}!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/vue-loader/lib??vue-loader-options!./src/views/admin/ServiceBookings.vue?vue&type=template&id=a76c6c9c&
var ServiceBookingsvue_type_template_id_a76c6c9c_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',[_c('div',[_c('div',{staticClass:"page-title-cont"},[_c('h1',{staticClass:"wp-heading-inline"},[_vm._v("WPCal.io - "+_vm._s(_vm.T__("Bookings")))]),_c('AdminHeaderRight')],1),_c('div',{staticClass:"se-cont"},[_c('ul',{staticClass:"se-nav"},[_c('li',{class:{ active: _vm.tabs_view.upcoming },on:{"click":function($event){return _vm.switch_tabs('upcoming')}}},[_vm._v(" "+_vm._s(_vm.T__("UPCOMING"))+" ")]),_c('li',{class:{ active: _vm.tabs_view.past },on:{"click":function($event){return _vm.switch_tabs('past')}}},[_vm._v(" "+_vm._s(_vm.T__("PAST"))+" ")]),_c('li',{class:{ active: _vm.tabs_view.custom },on:{"click":function($event){return _vm.switch_tabs('custom')}}},[_vm._v(" "+_vm._s(_vm.T__("Find a Booking"))+" ")])]),_c('div',{attrs:{"id":"booking_list_cont"}},[(_vm.tabs_view.upcoming)?_c('div',[_c('ServiceBookingFilters',{model:{value:(_vm.filter_options),callback:function ($$v) {_vm.filter_options=$$v},expression:"filter_options"}}),_c('ServiceBookingList',{attrs:{"booking_list_type":'upcoming',"booking_details":_vm.upcoming_bookings,"filter_options":_vm.filter_options},on:{"remove-booking":function($event){return _vm.remove_booking($event)},"load-page":function($event){return _vm.load_page($event)}}})],1):_vm._e(),(_vm.tabs_view.past)?_c('div',[_c('ServiceBookingFilters',{model:{value:(_vm.filter_options),callback:function ($$v) {_vm.filter_options=$$v},expression:"filter_options"}}),_c('ServiceBookingList',{attrs:{"booking_list_type":'past',"booking_details":_vm.past_bookings,"filter_options":_vm.filter_options},on:{"load-page":function($event){return _vm.load_page($event)}}})],1):_vm._e(),(_vm.tabs_view.custom)?_c('div',[_c('ServiceBookingFilters',{attrs:{"filter_options":_vm.custom_filter_options,"mode":"custom"}}),_c('ServiceBookingList',{attrs:{"booking_list_type":'custom',"booking_details":_vm.custom_bookings},on:{"remove-booking":function($event){return _vm.remove_booking($event)},"load-page":function($event){return _vm.load_page($event)}}})],1):_vm._e()])]),_c('TimeZoneSelector')],1)])}
var ServiceBookingsvue_type_template_id_a76c6c9c_staticRenderFns = []


// CONCATENATED MODULE: ./src/views/admin/ServiceBookings.vue?vue&type=template&id=a76c6c9c&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"1bf56cee-vue-loader-template"}!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/vue-loader/lib??vue-loader-options!./src/components/admin/ServiceBookingList.vue?vue&type=template&id=746e8b6e&scoped=true&
var ServiceBookingListvue_type_template_id_746e8b6e_scoped_true_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',{staticClass:"bookings-pages-cont"},[(_vm.booking_details.page > 0)?_c('pagination',{attrs:{"placement":"top","records":_vm.booking_details.total_items,"per-page":_vm.booking_details.items_per_page,"options":_vm.pagination_options},on:{"paginate":function($event){return _vm.$emit('load-page', {
        page: _vm.booking_details.page,
        list_type: _vm.booking_list_type
      })}},model:{value:(_vm.booking_details.page),callback:function ($$v) {_vm.$set(_vm.booking_details, "page", $$v)},expression:"booking_details.page"}}):_vm._e(),(_vm.booking_details.bookings.length == 0 && _vm.booking_details.eol)?_c('div',{staticClass:"empty-booking-cont"},[(
        _vm.booking_list_type == 'upcoming' &&
          _vm.filter_options.booking_status == '1' &&
          _vm.filter_options.admin_user_id ==
            _vm.$store.getters.get_current_admin_details.id
      )?_c('span',[_vm._v(_vm._s(_vm.T__("You have no Upcoming bookings."))+" "+_vm._s(_vm.T__("Time to chill...")))]):_vm._e(),(
        _vm.booking_list_type == 'past' &&
          _vm.filter_options.booking_status == '1' &&
          _vm.filter_options.admin_user_id ==
            _vm.$store.getters.get_current_admin_details.id
      )?_c('span',[_vm._v(_vm._s(_vm.T__("There are no past bookings.")))]):(
        _vm.booking_list_type == 'upcoming' || _vm.booking_list_type == 'past'
      )?_c('span',[_vm._v(_vm._s(_vm.T__("No matching bookings found.")))]):_vm._e(),(_vm.booking_list_type == 'custom')?_c('span',[_vm._v(_vm._s(_vm.T__("No matching bookings found. Try changing the filters above.")))]):_vm._e()]):_vm._e(),_c('div',{staticClass:"all-days-cont"},_vm._l((_vm.booking_details_for_view),function(date_grouped_bookings,index){return _c('div',{key:index,staticClass:"day-cont"},[_c('div',{staticClass:"date-cont"},[_vm._v(" "+_vm._s(_vm._f("wpcal_unix_to_sw_sm_sd_if_fy")(date_grouped_bookings.group_date,_vm.tz))),_c('br'),_c('span',[_vm._v(_vm._s(_vm._f("wpcal_unix_to_relative_day")(date_grouped_bookings.group_date,_vm.tz)))])]),_c('div',{staticClass:"events-cont"},_vm._l((date_grouped_bookings.bookings),function(booking,index2){
      var _obj;
return _c('div',{key:index2,staticClass:"single-event-cont",class:( _obj = {
            expanded: _vm.is_booking_expanded(booking.id)
          }, _obj['clr-' + booking.service_details.color] = true, _obj )},[_c('div',{staticClass:"event-summary"},[_c('div',{staticClass:"event-slot"},[_c('div',{class:{
                  disabled: booking.status == -1 || booking.status == -5
                }},[_c('span',{attrs:{"title":_vm.$options.filters.wpcal_unix_to_time(
                      booking.booking_from_time,
                      _vm.tz,
                      _vm.time_format
                    ) +
                      ' ' +
                      _vm.$options.filters.wpcal_unix_to_sw_sm_sd_if_fy(
                        booking.booking_from_time,
                        _vm.tz
                      )}},[_vm._v(_vm._s(_vm._f("wpcal_unix_to_time")(booking.booking_from_time,_vm.tz, _vm.time_format)))]),_vm._v(" - "),_c('span',{attrs:{"title":_vm.$options.filters.wpcal_unix_to_time(
                      booking.booking_to_time,
                      _vm.tz,
                      _vm.time_format
                    ) +
                      ' ' +
                      _vm.$options.filters.wpcal_unix_to_sw_sm_sd_if_fy(
                        booking.booking_to_time,
                        _vm.tz
                      )}},[_vm._v(_vm._s(_vm._f("wpcal_unix_to_time")(booking.booking_to_time,_vm.tz, _vm.time_format)))])]),(booking.status == -1 || booking.status == -5)?_c('div',{staticClass:"status-cancelled-rescheduled"},[_vm._v(" "+_vm._s(_vm._f("admin_booking_status")(booking.status,booking.booking_to_time))+" ")]):_vm._e()]),_c('div',{staticClass:"event-invitee-type"},[_c('span',{domProps:{"innerHTML":_vm._s(
                  _vm.Tsprintf(
                    /* translators: 1: html-tag-open 2: html-tag-close 3: invitee name */
                    _vm.T__('%1$s %3$s %2$s with %4$s.'),
                    '<strong>',
                    '</strong>',
                    booking.invitee_name,
                    _vm.select_admin_name(booking)
                  )
                )}}),_c('br'),_vm._v(" "+_vm._s(_vm.T__("Event Type:"))+" "),_c('strong',[_vm._v(_vm._s(booking.service_details.name))])]),_c('div',{staticClass:"show-link"},[_c('a',{on:{"click":function($event){return _vm.toggle_expand_booking(booking.id)}}},[_vm._v(" "+_vm._s(_vm.T__("DETAILS")))]),_c('br'),_c('span',{staticClass:"created-date",attrs:{"title":_vm.$options.filters.wpcal_unix_to_time(booking.added_ts, _vm.tz) +
                    ' ' +
                    _vm.$options.filters.wpcal_unix_to_full_date_day(
                      booking.added_ts,
                      _vm.tz
                    )}},[_vm._v(_vm._s(_vm.T__("created"))+" "+_vm._s(_vm._f("wpcal_unix_to_sm_sd_if_fy")(booking.added_ts,_vm.tz)))])])]),_c('div',{staticClass:"event-detailed"},[_c('div',{staticClass:"actions-cont"},[(
                  booking.status == 1 && booking.booking_to_time > _vm.cutoff_time
                )?_c('div',[_c('a',{staticClass:"btn reschedule",on:{"click":function($event){return _vm.show_service_booking_modal(booking)}}},[_vm._v(_vm._s(_vm.T__("Reschedule")))]),_c('a',{staticClass:"btn cancel",on:{"click":function($event){return _vm.show_cancel_confirmation(booking)}}},[_vm._v(_vm._s(_vm.T__("Cancel")))])]):_vm._e(),_c('a',{attrs:{"href":'#/event-type/edit/' + booking.service_id,"target":"_blank"}},[_vm._v(_vm._s(_vm.T__("Edit Event Type")))]),_c('br'),_c('a',{on:{"click":function($event){return _vm.show_service_booking_modal_for_schedule_invitee_again(
                    booking
                  )}}},[_vm._v(_vm._s(_vm.T__("Schedule Invitee Again")))])]),_c('div',{staticClass:"event-meta"},[_c('div',{staticClass:"event-meta-invitee-details"},[_c('div',[_c('div',{staticClass:"event-meta-label"},[_vm._v(_vm._s(_vm.T__("NAME")))]),_vm._v(" "+_vm._s(booking.invitee_name)+" ")]),_c('div',[_c('div',{staticClass:"event-meta-label"},[_vm._v(_vm._s(_vm.T__("EMAIL")))]),_vm._v(" "+_vm._s(booking.invitee_email)+" ")]),_c('div',{staticStyle:{"padding-right":"10px","box-sizing":"border-box"}},[_c('div',{staticClass:"event-meta-label"},[_vm._v(_vm._s(_vm.T__("LOCATION")))]),_c('LocationAdvanced',{attrs:{"type":"display_user_after_booking_location","location_details":booking.location,"booking_details":booking}})],1),_c('div',[_c('div',{staticClass:"event-meta-label"},[_vm._v(" "+_vm._s(_vm.T__("INVITEE TIME ZONE"))+" ")]),_vm._v(" "+_vm._s(_vm._f("wpcal_format_tz")(booking.invitee_tz))+" ")])]),(booking.status == -1 || booking.status == -5)?_c('div',{staticClass:"cancel-reschedule-reason-cont"},[_c('div',[_c('div',{staticClass:"event-meta-label"},[_vm._v(" "+_vm._s(_vm.get_action_label(booking.status, "verb") + " " + "Reason")+" ")]),_c('div',{staticStyle:{"white-space":"pre-line"}},[_vm._v(" "+_vm._s(booking.reschedule_cancel_reason ? booking.reschedule_cancel_reason : "(Not entered)")+" ")])]),_c('div',[_c('div',{staticClass:"event-meta-label"},[_vm._v(" "+_vm._s(_vm.get_action_label(booking.status, "past") + " " + "by")+" ")]),_vm._v(" "+_vm._s(booking.reschedule_cancel_user_full_name ? booking.reschedule_cancel_user_full_name : "N/A")+" ")]),_c('div',[_c('div',{staticClass:"event-meta-label"},[_vm._v(" "+_vm._s(_vm.get_action_label(booking.status, "past") + " " + "at")+" ")]),_vm._v(" "+_vm._s(_vm._f("wpcal_unix_to_time")(booking.reschedule_cancel_action_ts,_vm.tz))+" "+_vm._s(_vm._f("wpcal_unix_to_full_date_day")(booking.reschedule_cancel_action_ts,_vm.tz))+" ")]),(
                    booking.status == -5 && booking.rescheduled_booking_id
                  )?_c('div',[_c('router-link',{attrs:{"to":'/bookings/custom/' + booking.rescheduled_booking_id}},[_vm._v(_vm._s(_vm.T__("View new booking")))])],1):_vm._e()]):_vm._e()]),_c('div',{staticClass:"event-survey"},_vm._l((booking.invitee_question_answers),function(question,index){return _c('div',{key:index,staticClass:"wpc-form-row"},[_c('div',{staticClass:"txt-h5 pre",staticStyle:{"color":"#7c7d9c","margin-bottom":"5px"}},[_vm._v(_vm._s(question.question))]),(question.answer == '')?_c('div',{staticClass:"pre",staticStyle:{"margin-bottom":"10px","font-size":"13px","font-style":"italic"}},[_vm._v(" ("+_vm._s(_vm.T__("Not entered"))+") ")]):_c('div',{staticClass:"pre b",staticStyle:{"margin-bottom":"10px"}},[_vm._v(_vm._s(question.answer))])])}),0)])])}),0)])}),0),(_vm.booking_details.page > 0)?_c('pagination',{attrs:{"placement":"bottom","records":_vm.booking_details.total_items,"per-page":_vm.booking_details.items_per_page,"options":_vm.pagination_options},on:{"paginate":function($event){return _vm.$emit('load-page', {
        page: _vm.booking_details.page,
        list_type: _vm.booking_list_type
      })}},model:{value:(_vm.booking_details.page),callback:function ($$v) {_vm.$set(_vm.booking_details, "page", $$v)},expression:"booking_details.page"}}):_vm._e(),_c('modal',{staticStyle:{"z-index":"9999"},attrs:{"name":"service_booking_modal","width":"1000px","height":"auto","scrollable":true,"adaptive":true},on:{"closed":function($event){_vm.reschedule_booking = {}}}},[(_vm.reschedule_booking.hasOwnProperty('id'))?_c('Booking',{attrs:{"admin_end_booking_type":"reschedule","admin_end_service_id":_vm.reschedule_booking.service_id,"admin_end_old_booking_unique_link":_vm.reschedule_booking.unique_link},on:{"booking-rescheduled":function($event){return _vm.handle_rescheduled_booking($event)}}}):(_vm.schedule_invitee_again.hasOwnProperty('id'))?_c('Booking',{attrs:{"admin_end_booking_type":"new","admin_end_service_id":_vm.schedule_invitee_again.service_id,"admin_end_old_booking_unique_link":_vm.schedule_invitee_again.unique_link,"admin_end_is_schedule_invitee_again":true},on:{"booking-added":function($event){return _vm.handle_schedule_invitee_again_booking($event)}}}):_vm._e()],1),(_vm.booking_detail_for_cancel_modal)?_c('BookingCancelModal',{attrs:{"booking_details":_vm.booking_detail_for_cancel_modal,"cancel_booking_btn_is_enabled":_vm.cancel_booking_btn_is_enabled},on:{"cancel-booking":function($event){return _vm.cancel_booking($event)}},model:{value:(_vm.cancel_reason),callback:function ($$v) {_vm.cancel_reason=$$v},expression:"cancel_reason"}}):_vm._e()],1)}
var ServiceBookingListvue_type_template_id_746e8b6e_scoped_true_staticRenderFns = []


// CONCATENATED MODULE: ./src/components/admin/ServiceBookingList.vue?vue&type=template&id=746e8b6e&scoped=true&

// EXTERNAL MODULE: ./src/views/user/Booking.vue + 17 modules
var Booking = __webpack_require__("eb8b");

// EXTERNAL MODULE: ./src/components/LocationAdvanced.vue + 4 modules
var LocationAdvanced = __webpack_require__("2f7b");

// EXTERNAL MODULE: ./src/components/BookingCancelModal.vue + 4 modules
var BookingCancelModal = __webpack_require__("a046");

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"1bf56cee-vue-loader-template"}!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/vue-loader/lib??vue-loader-options!./src/components/form_elements/Pagination.vue?vue&type=template&id=74222879&
var Paginationvue_type_template_id_74222879_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('VuePagination2',{attrs:{"records":_vm.records,"per-page":_vm.perPage,"options":_vm.options},on:{"paginate":function($event){return _vm.$emit('paginate', $event)}},model:{value:(_vm.page),callback:function ($$v) {_vm.page=$$v},expression:"page"}})}
var Paginationvue_type_template_id_74222879_staticRenderFns = []


// CONCATENATED MODULE: ./src/components/form_elements/Pagination.vue?vue&type=template&id=74222879&

// EXTERNAL MODULE: ./node_modules/vue-pagination-2/compiled/main.js
var main = __webpack_require__("b9eb");
var main_default = /*#__PURE__*/__webpack_require__.n(main);

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--13-0!./node_modules/thread-loader/dist/cjs.js!./node_modules/babel-loader/lib!./node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/vue-loader/lib??vue-loader-options!./src/components/form_elements/Pagination.vue?vue&type=script&lang=js&
//
//
//
//
//
//
//
//
//
//
//

/* harmony default export */ var Paginationvue_type_script_lang_js_ = ({
  name: "Pagination",
  components: {
    VuePagination2: main_default.a
  },
  model: {
    prop: "current_page",
    event: "changed-current-page"
  },
  props: {
    placement: {
      type: String,
      default: ""
    },
    current_page: {},
    records: {},
    "per-page": {},
    options: {}
  },
  computed: {
    page: {
      get: function get() {
        return this.current_page;
      },
      set: function set(new_value) {
        this.$emit("changed-current-page", new_value);
      }
    }
  }
});
// CONCATENATED MODULE: ./src/components/form_elements/Pagination.vue?vue&type=script&lang=js&
 /* harmony default export */ var form_elements_Paginationvue_type_script_lang_js_ = (Paginationvue_type_script_lang_js_); 
// EXTERNAL MODULE: ./src/components/form_elements/Pagination.vue?vue&type=style&index=0&lang=css&
var Paginationvue_type_style_index_0_lang_css_ = __webpack_require__("cf8a");

// CONCATENATED MODULE: ./src/components/form_elements/Pagination.vue






/* normalize component */

var Pagination_component = Object(componentNormalizer["a" /* default */])(
  form_elements_Paginationvue_type_script_lang_js_,
  Paginationvue_type_template_id_74222879_render,
  Paginationvue_type_template_id_74222879_staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* harmony default export */ var Pagination = (Pagination_component.exports);
// EXTERNAL MODULE: ./node_modules/lodash-es/sortBy.js + 14 modules
var sortBy = __webpack_require__("d66c");

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--13-0!./node_modules/thread-loader/dist/cjs.js!./node_modules/babel-loader/lib!./node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/vue-loader/lib??vue-loader-options!./src/components/admin/ServiceBookingList.vue?vue&type=script&lang=js&


//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
 //user view is imported here






/* harmony default export */ var ServiceBookingListvue_type_script_lang_js_ = ({
  components: {
    Booking: Booking["a" /* default */],
    LocationAdvanced: LocationAdvanced["a" /* default */],
    Pagination: Pagination,
    BookingCancelModal: BookingCancelModal["a" /* default */]
  },
  props: {
    booking_list_type: {
      type: String,
      required: true
    },
    booking_details: {
      type: Object
    },
    filter_options: {
      type: Object
    }
  },
  data: function data() {
    return {
      expanded_bookings: [],
      reschedule_booking: {},
      schedule_invitee_again: {},
      cutoff_time: 0,
      action_strings: {
        "-1": {
          verb: "Cancellation",
          past: "Cancelled"
        },
        "-5": {
          verb: "Reschedule",
          past: "Rescheduled"
        }
      },
      pagination_options: {
        chunk: 10,
        chunksNavigation: "scroll",
        edgeNavigation: true,
        texts: {
          count: "{from} - {to} of {count}|{count} bookings|{count} booking"
        }
      },
      booking_detail_for_cancel_modal: null,
      cancel_reason: "",
      cancel_booking_btn_is_enabled: true
    };
  },
  computed: {
    // pagination_options() {
    //   return {
    //     chunk: 3,
    //     chunksNavigation: "scroll",
    //     edgeNavigation: true,
    //     texts: {
    //       count: this.pagination_count_text
    //     }
    //   };
    // },
    // pagination_count_text() {
    //   let str = "{from} - {to} of {count}";
    //   let count = this.booking_details.total_items
    //     ? this.booking_details.total_items
    //     : 0;
    //   let final_str = str.replace(/{count_}/g, count);
    //   return final_str;
    // },
    booking_details_for_view: function booking_details_for_view() {
      var __tmp = [];

      var _iterator = Object(createForOfIteratorHelper["a" /* default */])(this.booking_details.bookings),
          _step;

      try {
        for (_iterator.s(); !(_step = _iterator.n()).done;) {
          var booking = _step.value;
          var new_date = this.$options.filters.wpcal_unix_to_unix_only_date(booking.booking_from_time, this.tz);

          var _index = Object(findIndex["a" /* default */])(__tmp, {
            group_date: new_date
          });

          if (_index == -1) {
            _index = __tmp.length;
            __tmp[_index] = {
              group_date: new_date,
              bookings: []
            };
          }

          __tmp[_index]["bookings"].push(booking);
        }
      } catch (err) {
        _iterator.e(err);
      } finally {
        _iterator.f();
      }

      if (this.booking_list_type === "past") {
        var _iterator2 = Object(createForOfIteratorHelper["a" /* default */])(__tmp),
            _step2;

        try {
          for (_iterator2.s(); !(_step2 = _iterator2.n()).done;) {
            var date_grouped_bookings = _step2.value;
            date_grouped_bookings.bookings = Object(sortBy["a" /* default */])(date_grouped_bookings.bookings, ["booking_from_time"]);
          }
        } catch (err) {
          _iterator2.e(err);
        } finally {
          _iterator2.f();
        }
      }

      return __tmp;
    }
  },
  methods: {
    is_booking_expanded: function is_booking_expanded(booking_id) {
      return this.expanded_bookings.indexOf(booking_id) !== -1;
    },
    toggle_expand_booking: function toggle_expand_booking(booking_id) {
      if (this.is_booking_expanded(booking_id)) {
        var index = this.expanded_bookings.indexOf(booking_id);
        this.expanded_bookings.splice(index, 1);
      } else {
        this.expanded_bookings.push(booking_id);
      }
    },
    get_action_label: function get_action_label(status, tense) {
      if (this.action_strings.hasOwnProperty(status)) {
        var _this$action_strings$;

        return (_this$action_strings$ = this.action_strings[status]) === null || _this$action_strings$ === void 0 ? void 0 : _this$action_strings$[tense];
      }

      return status;
    },
    select_admin_name: function select_admin_name(booking) {
      if (booking.admin_user_id == this.$store.getters.get_current_admin_details.id) {
        return "You";
      }

      return booking.admin_user_full_name;
    },
    show_cancel_confirmation: function show_cancel_confirmation(booking) {
      var _this = this;

      this.cancel_reason = "";
      this.booking_detail_for_cancel_modal = booking;
      this.$nextTick(function () {
        _this.$modal.show("booking_cancel_confirm_modal");
      }); // let dialog_text_from_to_time_str = this.$options.filters.wpcal_unix_to_from_to_time_full_date_day_tz(
      //   booking.booking_from_time,
      //   booking.booking_to_time,
      //   this.tz,
      //   this.time_format
      // );
      // let dialog_text = this.Tsprintf(
      //   /* translators: 1: html-tag 2: from and to date time 3: invitee name 4: email */
      //   this.T__(
      //     "Are you sure you want to Cancel this Booking? %1$s %1$s At %2$s %1$s with %3$s (%4$s)"
      //   ),
      //   "<br>",
      //   dialog_text_from_to_time_str,
      //   booking.invitee_name,
      //   booking.invitee_email
      // );
      // this.$modal.show("dialog", {
      //   title: this.T__("Alert!"),
      //   text: dialog_text,
      //   buttons: [
      //     {
      //       title: this.T__("Yes, Cancel the booking"),
      //       handler: () => {
      //         this.cancel_booking(booking);
      //         this.$modal.hide("dialog");
      //       }
      //     },
      //     {
      //       title: this.T__("No"),
      //       default: true // Will be triggered by default if 'Enter' pressed.
      //     }
      //   ]
      // });
    },
    cancel_booking: function cancel_booking(booking) {
      var _this2 = this;

      this.cancel_booking_btn_is_enabled = false;
      var wpcal_request = {};
      var action = "cancel_booking";
      wpcal_request[action] = {
        booking_id: booking.id,
        cancel_reason: this.cancel_reason
      };
      var post_data = {
        action: "wpcal_process_admin_ajax_request",
        wpcal_request: wpcal_request
      };
      axios_default.a.post(window.wpcal_ajax.ajax_url, post_data).then(function (response) {
        _this2.notify_single_action_result(response.data[action], {
          success_msg: _this2.T__("Booking cancelled successfully.")
        });

        if (response.data[action].status == "success") {
          // below coded commented - Instead of removing the booking, lets reload the page
          // this.$emit("remove-booking", {
          //   booking_id: booking.id,
          //   booking_list_type: this.booking_list_type
          // });
          var wpcal_booking_trigger = new CustomEvent("wpcal_booking_cancelled", {
            detail: {
              booking_details: {
                unique_link: booking.unique_link
              }
            }
          });
          document.dispatchEvent(wpcal_booking_trigger);

          _this2.$emit("load-page", {
            page: _this2.booking_details.page,
            list_type: _this2.booking_list_type
          }); //this.$modal.hide("dialog");


          _this2.$modal.hide("booking_cancel_confirm_modal");
        }
      }).catch(function (error) {
        console.log(error);
      }).then(function () {
        _this2.$store.dispatch("run_booking_background_tasks_by_unique_link", {
          unique_link: booking.unique_link
        });

        _this2.cancel_booking_btn_is_enabled = true;
      });
    },
    show_service_booking_modal: function show_service_booking_modal(booking) {
      this.reschedule_booking = this.clone_deep(booking);
      this.$modal.show("service_booking_modal");
    },
    handle_rescheduled_booking: function handle_rescheduled_booking(info) {
      this.reschedule_booking = {}; // below coded commented - Instead of removing the booking, lets reload the page
      // this.$emit("remove-booking", {
      //   booking_unique_link: info.old_booking.unique_link,
      //   booking_list_type: this.booking_list_type
      // });

      this.$emit("load-page", {
        page: this.booking_details.page,
        list_type: this.booking_list_type
      });
      this.$modal.hide("service_booking_modal");
      this.show_rescheduled_information(info);
    },
    show_rescheduled_information: function show_rescheduled_information(info) {
      var dialog_text = "";
      dialog_text += "<div>";
      dialog_text += this.T__("The booking has been successfully rescheduled.");

      if (info !== null && info !== void 0 && info.new_booking_id) {
        dialog_text += ' <a href="#/bookings/custom/' + info.new_booking_id + '" target="_blank">View new booking</a>';
      }

      dialog_text += "</div>"; // dialog_text +=
      //   '<div style="float:left;width:300px;word-wrap: break-word;">Old Booking' +
      //   JSON.stringify(info.old_booking) +
      //   "</div>";
      // dialog_text +=
      //   '<div style="float:left;width:300px;word-wrap: break-word;">New Booking' +
      //   JSON.stringify(info.new_booking) +
      //   "</div>";

      this.$modal.show("dialog", {
        title: this.T__("Booking rescheduled"),
        text: dialog_text,
        buttons: [{
          title: "Okay",
          default: true // Will be triggered by default if 'Enter' pressed.

        }],
        width: "610px"
      });
    },
    show_service_booking_modal_for_schedule_invitee_again: function show_service_booking_modal_for_schedule_invitee_again(booking) {
      this.schedule_invitee_again = this.clone_deep(booking);
      this.$modal.show("service_booking_modal");
    },
    handle_schedule_invitee_again_booking: function handle_schedule_invitee_again_booking() {
      this.schedule_invitee_again = {};
      this.$modal.hide("service_booking_modal");
      this.$modal.show("dialog", {
        title: this.T__("Booking scheduled"),
        text: this.T__("The new booking has been successfully scheduled."),
        buttons: [{
          title: this.T__("Okay")
        }]
      });
    },
    may_expand_booking_if_only_single_result: function may_expand_booking_if_only_single_result() {
      var _this$booking_details, _this$booking_details2, _this$booking_details3, _this$booking_details4;

      if (((_this$booking_details = this.booking_details) === null || _this$booking_details === void 0 ? void 0 : _this$booking_details.total_items) == 1 && !this._isEmpty((_this$booking_details2 = this.booking_details) === null || _this$booking_details2 === void 0 ? void 0 : _this$booking_details2.bookings) && !this._isEmpty((_this$booking_details3 = this.booking_details) === null || _this$booking_details3 === void 0 ? void 0 : (_this$booking_details4 = _this$booking_details3.bookings[0]) === null || _this$booking_details4 === void 0 ? void 0 : _this$booking_details4.id)) {
        var _this$booking_details5, _this$booking_details6;

        var booking_id = (_this$booking_details5 = this.booking_details) === null || _this$booking_details5 === void 0 ? void 0 : (_this$booking_details6 = _this$booking_details5.bookings[0]) === null || _this$booking_details6 === void 0 ? void 0 : _this$booking_details6.id;

        if (!this.is_booking_expanded(booking_id)) {
          this.toggle_expand_booking(booking_id);
        }
      }
    }
  },
  beforeUpdate: function beforeUpdate() {
    this.cutoff_time = Math.round(new Date().getTime() / 1000);
  },
  watch: {
    booking_details: {
      handler: function handler(new_val) {
        this.may_expand_booking_if_only_single_result();
      },
      deep: true
    }
  }
});
// CONCATENATED MODULE: ./src/components/admin/ServiceBookingList.vue?vue&type=script&lang=js&
 /* harmony default export */ var admin_ServiceBookingListvue_type_script_lang_js_ = (ServiceBookingListvue_type_script_lang_js_); 
// EXTERNAL MODULE: ./src/components/admin/ServiceBookingList.vue?vue&type=style&index=0&id=746e8b6e&scoped=true&lang=css&
var ServiceBookingListvue_type_style_index_0_id_746e8b6e_scoped_true_lang_css_ = __webpack_require__("7ab7");

// CONCATENATED MODULE: ./src/components/admin/ServiceBookingList.vue






/* normalize component */

var ServiceBookingList_component = Object(componentNormalizer["a" /* default */])(
  admin_ServiceBookingListvue_type_script_lang_js_,
  ServiceBookingListvue_type_template_id_746e8b6e_scoped_true_render,
  ServiceBookingListvue_type_template_id_746e8b6e_scoped_true_staticRenderFns,
  false,
  null,
  "746e8b6e",
  null
  
)

/* harmony default export */ var ServiceBookingList = (ServiceBookingList_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"1bf56cee-vue-loader-template"}!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/vue-loader/lib??vue-loader-options!./src/components/admin/ServiceBookingFilters.vue?vue&type=template&id=6d363a50&
var ServiceBookingFiltersvue_type_template_id_6d363a50_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',[_c('div',{staticClass:"validation_errors_cont"}),_c('ul',{staticClass:"filters-bar",staticStyle:{"border-bottom":"1px solid #b1b6d1"}},[(_vm.mode == 'custom')?_c('li',{staticStyle:{"border":"0"}},[_c('input',{directives:[{name:"model",rawName:"v-model",value:(_vm.filter_options.search_text),expression:"filter_options.search_text"}],attrs:{"id":"booking_search_text","type":"search","name":"booking_search_text","placeholder":"Search"},domProps:{"value":(_vm.filter_options.search_text)},on:{"input":function($event){if($event.target.composing){ return; }_vm.$set(_vm.filter_options, "search_text", $event.target.value)}}}),_c('span',[_vm._v("Search by invitee name, email or booking ID")])]):_vm._e(),(_vm.mode == 'custom')?_c('li',{staticClass:"bf-when"},[_c('span',{staticClass:"bf-label",staticStyle:{"margin-right":"-44px"}},[_vm._v("When?")]),_c('TypeToSelect',{staticClass:"no-border",attrs:{"options":_vm.when_options,"label_field":"label","value_field":"value"},model:{value:(_vm.filter_options.when),callback:function ($$v) {_vm.$set(_vm.filter_options, "when", $$v)},expression:"filter_options.when"}}),(_vm.filter_options.when == 'date_range')?_c('v-date-picker',{attrs:{"mode":"range","columns":2,"masks":{ input: ['YYYY-MM-DD'] },"id":"bf-dr-selector"},on:{"dayclick":_vm.log},model:{value:(_vm.filter_options.date_range),callback:function ($$v) {_vm.$set(_vm.filter_options, "date_range", $$v)},expression:"filter_options.date_range"}},[_c('input',{attrs:{"type":"text","readonly":""},domProps:{"value":_vm.$options.filters.wpcal_format_date_style1(
              _vm.filter_options.date_range.start
            ) +
              ' - ' +
              _vm.$options.filters.wpcal_format_date_style1(
                _vm.filter_options.date_range.end
              )}})]):_vm._e()],1):_vm._e(),_c('li',{staticClass:"bf-host"},[_c('span',{staticClass:"bf-label",staticStyle:{"margin-right":"-35px"}},[_vm._v("Host")]),_c('TypeToSelect',{staticClass:"no-border",attrs:{"options":_vm.$store.getters.get_managed_active_admins_for_host_filter,"label_field":"name","value_field":"admin_user_id"},model:{value:(_vm.filter_options.admin_user_id),callback:function ($$v) {_vm.$set(_vm.filter_options, "admin_user_id", $$v)},expression:"filter_options.admin_user_id"}})],1),_c('li',{staticClass:"bf-status"},[_c('span',{staticClass:"bf-label",staticStyle:{"margin-right":"-46px"}},[_vm._v("Status")]),_c('TypeToSelect',{staticClass:"no-border",attrs:{"options":_vm.booking_status_options,"label_field":"label","value_field":"value"},model:{value:(_vm.filter_options.booking_status),callback:function ($$v) {_vm.$set(_vm.filter_options, "booking_status", $$v)},expression:"filter_options.booking_status"}})],1),_c('li',{staticClass:"bf-et"},[_c('span',{staticClass:"bf-label",staticStyle:{"margin-right":"-70px"}},[_vm._v("Event Type")]),_c('TypeToSelect',{staticClass:"no-border",attrs:{"options":_vm.service_filter_options,"label_field":"name","value_field":"id"},model:{value:(_vm.filter_options.service_id),callback:function ($$v) {_vm.$set(_vm.filter_options, "service_id", $$v)},expression:"filter_options.service_id"}})],1)])])}
var ServiceBookingFiltersvue_type_template_id_6d363a50_staticRenderFns = []


// CONCATENATED MODULE: ./src/components/admin/ServiceBookingFilters.vue?vue&type=template&id=6d363a50&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--13-0!./node_modules/thread-loader/dist/cjs.js!./node_modules/babel-loader/lib!./node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/vue-loader/lib??vue-loader-options!./src/components/admin/ServiceBookingFilters.vue?vue&type=script&lang=js&


//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//


/* harmony default export */ var ServiceBookingFiltersvue_type_script_lang_js_ = ({
  name: "ServiceBookingFilters",
  components: {
    TypeToSelect: TypeToSelect
  },
  model: {
    prop: "filter_options",
    event: "changed-filter-options"
  },
  props: {
    filter_options: {
      type: Object
    },
    mode: {
      type: String,
      default: "normal"
    }
  },
  data: function data() {
    return {
      when_options: [{
        label: "All",
        value: "0"
      }, {
        label: "Upcoming",
        value: "upcoming"
      }, {
        label: "Past",
        value: "past"
      }, {
        label: "Date range",
        value: "date_range"
      }],
      booking_status_options: [{
        label: "All",
        value: "0"
      }, {
        label: "Active",
        value: "1"
      }, {
        label: "Cancelled",
        value: "-1"
      }, {
        label: "Rescheduled",
        value: "-5"
      }],
      service_list: []
    };
  },
  computed: {
    service_filter_options: function service_filter_options() {
      var services = this.clone_deep(this.service_list);

      if (Array.isArray(services)) {
        var all_service_as_service = {
          id: "0",
          name: "All"
        };
        services.unshift(all_service_as_service);
      }

      return services;
    }
  },
  methods: {
    load_service_list: function load_service_list() {
      var _this = this;

      return Object(asyncToGenerator["a" /* default */])( /*#__PURE__*/regeneratorRuntime.mark(function _callee() {
        var cached_data, action, wpcal_request, post_data;
        return regeneratorRuntime.wrap(function _callee$(_context) {
          while (1) {
            switch (_context.prev = _context.next) {
              case 0:
                _context.next = 2;
                return _this.$store.dispatch("get_dynamic_cache_by_prop", "managed_services_for_current_admin");

              case 2:
                cached_data = _context.sent;

                if (!cached_data) {
                  _context.next = 6;
                  break;
                }

                _this.service_list = cached_data;
                return _context.abrupt("return");

              case 6:
                action = "get_managed_services_for_current_admin";
                wpcal_request = {};
                wpcal_request[action] = {
                  filter_by_admin_id: "0"
                };
                post_data = {
                  action: "wpcal_process_admin_ajax_request",
                  wpcal_request: wpcal_request
                };
                axios_default.a.post(window.wpcal_ajax.ajax_url, post_data).then(function (response) {
                  //console.log(response);
                  _this.notify_single_action_result(response.data[action], {
                    dont_notify_success: true
                  });

                  if (response.data && response.data[action].hasOwnProperty("service_list")) {
                    _this.service_list = response.data[action].service_list;

                    _this.$store.commit("set_dynamic_cache_by_prop", {
                      prop: "managed_services_for_current_admin",
                      value: _this.service_list,
                      expiry_secs: 5 * 60
                      /* 5 mins */

                    });
                  }
                }).catch(function (error) {
                  console.log(error);
                });

              case 11:
              case "end":
                return _context.stop();
            }
          }
        }, _callee);
      }))();
    }
  },
  mounted: function mounted() {
    //this.$store.dispatch("load_managed_active_admins_details"); //to avoid multiple calling on each tab click or load this code is used in ServiceBookings.vue
    this.load_service_list();
  }
});
// CONCATENATED MODULE: ./src/components/admin/ServiceBookingFilters.vue?vue&type=script&lang=js&
 /* harmony default export */ var admin_ServiceBookingFiltersvue_type_script_lang_js_ = (ServiceBookingFiltersvue_type_script_lang_js_); 
// EXTERNAL MODULE: ./src/components/admin/ServiceBookingFilters.vue?vue&type=style&index=0&lang=css&
var ServiceBookingFiltersvue_type_style_index_0_lang_css_ = __webpack_require__("aef3");

// CONCATENATED MODULE: ./src/components/admin/ServiceBookingFilters.vue






/* normalize component */

var ServiceBookingFilters_component = Object(componentNormalizer["a" /* default */])(
  admin_ServiceBookingFiltersvue_type_script_lang_js_,
  ServiceBookingFiltersvue_type_template_id_6d363a50_render,
  ServiceBookingFiltersvue_type_template_id_6d363a50_staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* harmony default export */ var ServiceBookingFilters = (ServiceBookingFilters_component.exports);
// EXTERNAL MODULE: ./node_modules/date-fns/esm/getUnixTime/index.js + 1 modules
var getUnixTime = __webpack_require__("a1d8");

// EXTERNAL MODULE: ./node_modules/date-fns-tz/esm/zonedTimeToUtc/index.js + 2 modules
var zonedTimeToUtc = __webpack_require__("7f6f");

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--13-0!./node_modules/thread-loader/dist/cjs.js!./node_modules/babel-loader/lib!./node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/vue-loader/lib??vue-loader-options!./src/views/admin/ServiceBookings.vue?vue&type=script&lang=js&




//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//




 // import { required } from "vuelidate/lib/validators";
// import { format } from "date-fns";



/* harmony default export */ var ServiceBookingsvue_type_script_lang_js_ = ({
  components: {
    ServiceBookingList: ServiceBookingList,
    ServiceBookingFilters: ServiceBookingFilters,
    TimeZoneSelector: TimeZoneSelector["a" /* default */]
  },
  props: {
    redirected_to_admin_end_booking: {
      type: Boolean,
      default: false
    }
  },
  data: function data() {
    return {
      document_title: "Bookings",
      //view_base_timing: format(new Date(), "t"),
      upcoming_bookings: {
        page: -1,
        eol: false,
        total_items: 0,
        items_per_page: 20,
        //dummy data for default, actual loads from backend
        bookings: []
      },
      past_bookings: {
        page: -1,
        eol: false,
        total_items: 0,
        items_per_page: 20,
        //dummy data for default, actual loads from backend
        bookings: []
      },
      custom_bookings: {
        page: -1,
        eol: false,
        total_items: 0,
        items_per_page: 20,
        //dummy data for default, actual loads from backend
        bookings: []
      },
      tabs_view: {
        upcoming: true,
        past: false,
        custom: false
      },
      default_tab_options: {
        page: -1,
        eol: false,
        bookings: []
      },
      // custom_filters: {
      //   text: ""
      // },
      filter_options: {
        admin_user_id: this.$store.getters.get_current_admin_details.id,
        booking_status: "1",
        service_id: "0"
      },
      custom_filter_options: {
        admin_user_id: this.$store.getters.get_current_admin_details.id,
        booking_status: "1",
        service_id: "0",
        when: "upcoming",
        date_range: {
          from_date_ts: "",
          to_date_ts: "",
          start: new Date(),
          //will be set to from_date on form submit
          end: new Date() //will be set to to_date on form submit

        },
        search_text: ""
      },
      custom_filter_options_initial_loaded_value: {},
      no_more_customer_filter_set_filters_to_all: false,
      last_custom_filter_options_search_text: "",
      redirected_to_admin_end_booking_notice_shown: false
    };
  },
  // validations: {
  //   custom_filters: {
  //     text: { required }//validation not required, commented
  //   }
  // },
  computed: {
    current_view: function current_view() {
      if (this.tabs_view.upcoming == true) {
        return "upcoming";
      } else if (this.tabs_view.past == true) {
        return "past";
      } else if (this.tabs_view.custom == true) {
        return "custom";
      }

      return "";
    }
  },
  methods: {
    switch_tabs: function switch_tabs(list_type) {
      var _this = this;

      if (list_type === "upcoming") {
        this.tabs_view.upcoming = false;
        this.tabs_view.past = false;
        this.tabs_view.custom = false;
        this.$nextTick(function () {
          //for re-rendering
          _this.tabs_view.upcoming = true;
        });
        this.upcoming_bookings = this.clone_deep(this.default_tab_options);
        this.get_bookings("upcoming"); // if (
        //   this.upcoming_bookings.page == -1 &&
        //   this.upcoming_bookings.eol == false
        // ) {
        //   this.get_bookings("upcoming");
        // }
      } else if (list_type === "past") {
        this.tabs_view.upcoming = false;
        this.tabs_view.past = false;
        this.tabs_view.custom = false;
        this.$nextTick(function () {
          //for re-rendering
          _this.tabs_view.past = true;
        });
        this.past_bookings = this.clone_deep(this.default_tab_options);
        this.get_bookings("past"); // if (this.past_bookings.page == -1 && this.past_bookings.eol == false) {
        //   this.get_bookings("past");
        // }
      } else if (list_type === "custom") {
        this.tabs_view.upcoming = false;
        this.tabs_view.past = false;
        this.tabs_view.custom = false;
        this.$nextTick(function () {
          //for re-rendering
          _this.tabs_view.custom = true;
        });
        this.custom_bookings = this.clone_deep(this.default_tab_options);
        this.get_bookings("custom"); // if (
        //   this.custom_bookings.page == -1 &&
        //   this.custom_bookings.eol == false
        // ) {
        //   this.get_bookings("custom");
        // }
      }
    },
    // following commented because load_more() concept no longer used
    // load_more(list_type) {
    //   if (
    //     list_type !== "upcoming" &&
    //     list_type !== "past" &&
    //     list_type !== "custom"
    //   ) {
    //     return;
    //   }
    //   if (!this[list_type + "_bookings"].eol) {
    //     let requested_page = this[list_type + "_bookings"].page + 1;
    //     this.get_bookings(list_type, requested_page);
    //   }
    // },
    load_page: Object(debounce["a" /* default */])(function (details) {
      this.get_bookings(details.list_type, details.page);
    }, 10),
    load_bookings_page: function load_bookings_page() {
      this.get_bookings(this.current_view);
    },
    get_bookings: function get_bookings(list_type) {
      var _this2 = this;

      var requested_page = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 1;

      if (list_type !== "upcoming" && list_type !== "past" && list_type !== "custom") {
        return false;
      }

      var wpcal_request = {};
      var action = "get_managed_bookings_for_current_admin";
      var data_name = list_type + "_bookings";
      var options = {
        list_type: list_type,
        //view_base_timing: this.view_base_timing,
        filters: this.filter_options,
        page: requested_page
      };

      if (list_type === "custom") {
        options.filters = this.clone_deep(this.custom_filter_options); //getUnixTime(zonedTimeToUtc()) used to convert the date in browser timezone to the view_in tz selector at the bottom of the page and then convert to unix for easy use in backend

        options.filters.date_range.from_date_ts = Object(getUnixTime["a" /* default */])(Object(zonedTimeToUtc["a" /* default */])(options.filters.date_range.start, this.tz));
        options.filters.date_range.to_date_ts = Object(getUnixTime["a" /* default */])(Object(zonedTimeToUtc["a" /* default */])(options.filters.date_range.end, this.tz));
        delete options.filters.date_range.start;
        delete options.filters.date_range.end;
      }

      wpcal_request[action] = {
        options: options
      };
      var post_data = {
        action: "wpcal_process_admin_ajax_request",
        wpcal_request: wpcal_request
      };
      axios_default.a.post(window.wpcal_ajax.ajax_url, post_data).then(function (response) {
        if (response.data && response.data[action].hasOwnProperty("bookings_data")) {
          // following commented because load_more() concept no longer used
          // if (
          //   // list_type == "custom" &&
          //   response.data[action].bookings_data.page == 1
          // ) {
          //   this[data_name].bookings = [];
          // }
          // this[data_name].bookings = this[data_name].bookings.concat(
          //   response.data[action].bookings_data.bookings
          // );
          _this2[data_name].bookings = response.data[action].bookings_data.bookings;
          _this2[data_name].page = parseInt(response.data[action].bookings_data.page);
          _this2[data_name].total_items = parseInt(response.data[action].bookings_data.total_items);
          _this2[data_name].items_per_page = parseInt(response.data[action].bookings_data.items_per_page);
          _this2[data_name].eol = response.data[action].bookings_data.eol;

          _this2.$nextTick(function () {
            _this2.scroll_to("#booking_list_cont", 0, true);
          });
        }
      }).catch(function (error) {
        console.log(error);
      });
    },
    // search_bookings() {
    //   //reset custom_bookings
    //   this.custom_bookings.page = -1;
    //   this.custom_bookings.eol = false;
    //   this.custom_bookings.bookings = [];
    //   // //validate
    //   // this.$v.custom_filters.$touch(); //trigger validation
    //   // if (this.$v.custom_filters.$anyError) {
    //   //   this.scroll_to_first_validation_error();
    //   //   return;
    //   // }
    //   // if (!this.custom_filters.text.trim()) {
    //   //   //if no text return
    //   //   return;
    //   // }
    //   //all ok - go head and search
    //   this.get_bookings("custom");
    // },
    // set_view_base_timing() {
    //   this.view_base_timing = format(new Date(), "t");
    // },
    remove_booking: function remove_booking(booking_remove_details) {
      if (booking_remove_details.booking_list_type !== "upcoming" && booking_remove_details.booking_list_type !== "custom") {
        return false;
      }

      var bookings_var_name = booking_remove_details.booking_list_type + "_bookings";
      var result_index;

      if (booking_remove_details.hasOwnProperty("booking_id")) {
        var booking_id = booking_remove_details.booking_id;
        result_index = this[bookings_var_name].bookings.findIndex(function (_booking) {
          return _booking.id == booking_id;
        });
      } else if (booking_remove_details.hasOwnProperty("booking_unique_link")) {
        var booking_unique_link = booking_remove_details.booking_unique_link;
        result_index = this[bookings_var_name].bookings.findIndex(function (_booking) {
          return _booking.unique_link == booking_unique_link;
        });
      }

      if (result_index !== -1) {
        this[bookings_var_name].bookings.splice(result_index, 1);
      }
    },
    debounced_search: Object(debounce["a" /* default */])(function () {
      this.load_bookings_page();
    }, 200),
    one_time_on_custom_filter_search_text_used_set_other_filters_to_all: function one_time_on_custom_filter_search_text_used_set_other_filters_to_all() {
      //assuming this will call on search_text change
      if (this.no_more_customer_filter_set_filters_to_all) {
        return;
      }

      var current_custom_filters = this.clone_deep(this.custom_filter_options);
      var custom_filters_initial_loaded_value = this.clone_deep(this.custom_filter_options_initial_loaded_value);
      delete current_custom_filters.search_text;
      delete current_custom_filters.date_range; //better to avoid date_range, if when filter changed to 'date_range' that is enough

      delete custom_filters_initial_loaded_value.search_text;
      delete custom_filters_initial_loaded_value.date_range;

      var is_equal = Object(isEqual["a" /* default */])(current_custom_filters, custom_filters_initial_loaded_value);

      if (!is_equal) {
        this.no_more_customer_filter_set_filters_to_all = true;
        return;
      } //set other filters to all


      var other_filters_set_to_all = {
        admin_user_id: "0",
        booking_status: "0",
        service_id: "0",
        when: "0"
      };
      this.custom_filter_options = Object.assign(this.custom_filter_options, other_filters_set_to_all);
      this.no_more_customer_filter_set_filters_to_all = true;
    },
    may_booking_custom_by_id_process: function may_booking_custom_by_id_process() {
      if (this.$route.name == "booking_custom_by_id") {
        this.may_show_notice_for_redirected();

        if (typeof this.$route.params.booking_id === "string") {
          var booking_id = this.$route.params.booking_id.trim();

          if (booking_id) {
            this.custom_filter_options.search_text = booking_id;
            this.custom_filter_options.admin_user_id = "0";
            this.custom_filter_options.booking_status = "0";
            this.custom_filter_options.service_id = "0";
            this.custom_filter_options.when = "0";
            this.switch_tabs("custom");
            this.no_more_customer_filter_set_filters_to_all = true; //this.search_bookings();

            this.$router.push({
              name: "booking_list"
            }); //just redirect - required content will be loaded - this done if again user goes on to click a link with 'booking_custom_by_id' it is not working - so this is workaround fix

            return true;
          }
        }

        this.$router.push({
          name: "booking_list"
        });
      }
    },
    may_show_notice_for_redirected: function may_show_notice_for_redirected() {
      if (this.redirected_to_admin_end_booking && !this.redirected_to_admin_end_booking_notice_shown) {
        this.redirected_to_admin_end_booking_notice_shown = true;
        this.$toast.success("WPCal admins can view manage the bookings only from admin end. Front end only for the invitees.", "You have been redirected to admin end", {});
      }
    }
  },
  mounted: function mounted() {
    this.$store.dispatch("load_managed_active_admins_details");
    var filter_date_range = this.custom_filter_options.date_range;
    filter_date_range.start.setDate(filter_date_range.start.getDate() - 30); //set start date last 30 days

    filter_date_range.start.setHours(0, 0, 0, 0);
    filter_date_range.end.setHours(0, 0, 0, 0);
    this.custom_filter_options_initial_loaded_value = this.clone_deep(this.custom_filter_options);

    if (this.may_booking_custom_by_id_process()) {
      return;
    } //this.set_view_base_timing();


    this.load_bookings_page();
  },
  watch: {
    filter_options: {
      handler: function handler(new_val) {
        this.load_bookings_page(); // Follwoing commented as on clicking each tab it gonna fetch bookings
        // let default_tab_options = {
        //   page: -1,
        //   eol: false,
        //   bookings: []
        // };
        // //upcoming & past share same filter and its values. While change in filter reset the other tab options
        // let reset_tab = this.current_view == "upcoming" ? "past" : "upcoming";
        // this[reset_tab + "_bookings"] = Object.assign(
        //   this[reset_tab + "_bookings"],
        //   default_tab_options
        // );
      },
      deep: true
    },
    // "custom_filter_options.search_text": debounce(function(new_val, old_val) {
    //   this.load_bookings_page();
    // }, 200),
    custom_filter_options: {
      handler: function handler(new_val, old_val) {
        if (new_val.search_text != this.last_custom_filter_options_search_text) {
          //object property(child) new_val and old_val is coming same
          this.one_time_on_custom_filter_search_text_used_set_other_filters_to_all();
          this.debounced_search();
        } else {
          //this.load_bookings_page();
          this.debounced_search(); //there will be delay of 200ms thats ok it is needed when one_time_on_custom_filter_search_text_used_set_other_filters_to_all() is called - again comes here
        }

        this.last_custom_filter_options_search_text = new_val.search_text;
      },
      deep: true
    },
    tz: function tz() {
      if (this.current_view == "custom" && this.custom_filter_options.when == "date_range") {
        this.load_bookings_page();
      }
    },
    "$route.name": function $routeName(new_route_name, old_route_name) {
      if (new_route_name == "booking_custom_by_id") {
        this.may_booking_custom_by_id_process();
      }
    }
  }
});
// CONCATENATED MODULE: ./src/views/admin/ServiceBookings.vue?vue&type=script&lang=js&
 /* harmony default export */ var admin_ServiceBookingsvue_type_script_lang_js_ = (ServiceBookingsvue_type_script_lang_js_); 
// EXTERNAL MODULE: ./src/assets/css/admin_service_bookings.css?vue&type=style&index=0&lang=css&
var admin_service_bookingsvue_type_style_index_0_lang_css_ = __webpack_require__("59fb");

// CONCATENATED MODULE: ./src/views/admin/ServiceBookings.vue






/* normalize component */

var ServiceBookings_component = Object(componentNormalizer["a" /* default */])(
  admin_ServiceBookingsvue_type_script_lang_js_,
  ServiceBookingsvue_type_template_id_a76c6c9c_render,
  ServiceBookingsvue_type_template_id_a76c6c9c_staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* harmony default export */ var ServiceBookings = (ServiceBookings_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"1bf56cee-vue-loader-template"}!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/vue-loader/lib??vue-loader-options!./src/views/admin/SettingGeneral.vue?vue&type=template&id=0b4dc2c4&
var SettingGeneralvue_type_template_id_0b4dc2c4_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',[_c('div',[_c('div',{staticClass:"page-title-cont"},[_c('h1',{staticClass:"wp-heading-inline"},[_vm._v(" WPCal.io - "+_vm._s(_vm.T__("General Settings"))+" ")]),_c('AdminHeaderRight')],1),_c('SettingsMenu'),_c('div',{staticClass:"col-cont settings-cont"},[_c('div',{staticClass:"col"},[_c('div',{staticClass:"form"},[_c('div',{staticClass:"validation_errors_cont"}),_c('div',{staticClass:"setting-group-title"},[_vm._v(_vm._s(_vm.T__("Default settings")))]),_c('form-row',{attrs:{"validator":_vm.$v.form.working_hours}},[_c('label',[_vm._v(_vm._s(_vm.T__("Default Working Hours")))]),_c('TimeRange1ToN',{attrs:{"disable_multiple":true},model:{value:(_vm.working_hours),callback:function ($$v) {_vm.working_hours=$$v},expression:"working_hours"}}),_c('span',{staticClass:"txt-help"},[_vm._v(_vm._s(_vm.T__( "Changing this will not affect existing event types but only newly-created event types." )))])],1),_c('form-row',{attrs:{"validator":_vm.$v.form.working_hours.from_time}}),_c('form-row',{attrs:{"validator":_vm.$v.form.working_hours.to_time}}),_c('form-row',{attrs:{"validator":_vm.$v.form.working_days}},[_c('label',[_vm._v(_vm._s(_vm.T__("Default Working Days")))]),_c('WeekDaysSelector',{model:{value:(_vm.form.working_days),callback:function ($$v) {_vm.$set(_vm.form, "working_days", $$v)},expression:"form.working_days"}}),_c('span',{staticClass:"txt-help"},[_vm._v(_vm._s(_vm.T__( "Changing this will not affect existing event types but only newly-created event types." )))])],1),_c('form-row',{attrs:{"validator":_vm.$v.form.timezone}},[_c('label',[_vm._v(_vm._s(_vm.T__("Default Time Zone")))]),_c('TimeZoneSelector',{attrs:{"use_as":'form_tz'},model:{value:(_vm.form.timezone),callback:function ($$v) {_vm.$set(_vm.form, "timezone", $$v)},expression:"form.timezone"}}),_c('span',{staticClass:"txt-help"},[_vm._v(_vm._s(_vm.T__( "Changing this will not affect existing event types but only newly-created event types." )))])],1),_c('form-row',{attrs:{"validator":_vm.$v.form.time_format}},[_c('label',[_vm._v(_vm._s(_vm.T__("Time Format")))]),_c('ul',{staticClass:"selector inline time"},[_c('li',[_c('input',{directives:[{name:"model",rawName:"v-model",value:(_vm.form.time_format),expression:"form.time_format"}],attrs:{"type":"radio","id":"time-format-12h","name":"time-format","value":"12hrs"},domProps:{"checked":_vm._q(_vm.form.time_format,"12hrs")},on:{"blur":function($event){return _vm.$v.form.time_format.$touch()},"change":function($event){return _vm.$set(_vm.form, "time_format", "12hrs")}}}),_c('label',{attrs:{"for":"time-format-12h"}},[_vm._v(" 12 "),_c('span',[_vm._v(_vm._s(_vm.T_x("hr", "time hour")))])])]),_c('li',[_c('input',{directives:[{name:"model",rawName:"v-model",value:(_vm.form.time_format),expression:"form.time_format"}],attrs:{"type":"radio","id":"time-format-24h","name":"time-format","value":"24hrs"},domProps:{"checked":_vm._q(_vm.form.time_format,"24hrs")},on:{"blur":function($event){return _vm.$v.form.time_format.$touch()},"change":function($event){return _vm.$set(_vm.form, "time_format", "24hrs")}}}),_c('label',{attrs:{"for":"time-format-24h"}},[_vm._v(" 24 "),_c('span',[_vm._v(_vm._s(_vm.T_x("hr", "time hour")))])])])]),_c('span',{staticClass:"txt-help"},[_vm._v(_vm._s(_vm.T__( "This will be used for both admin end and for the invitee in the booking widget." )))])]),_c('hr'),_c('div',{staticClass:"setting-group-title",staticStyle:{"display":"flex"}},[_vm._v(" "+_vm._s(_vm.T__("Branding"))+" "),_c('PremiumInfoBadge',{staticStyle:{"margin":"-9px 0 0 10px"},attrs:{"highlight_feature":"branding_customization"}},[_c('em',[_vm._v("This feature is included"),_c('br'),_vm._v("in the PRO plan.")])])],1),_c('form-row',{attrs:{"validator":_vm.$v.form.branding_color}},[_c('label',[_vm._v(_vm._s(_vm.T__("Brand color")))]),_c('div',{staticStyle:{"display":"flex"}},[_c('input',{directives:[{name:"model",rawName:"v-model",value:(_vm.form.branding_color),expression:"form.branding_color"}],staticStyle:{"width":"90px"},attrs:{"type":"text","placeholder":"#567bf3"},domProps:{"value":(_vm.form.branding_color)},on:{"keyup":_vm.may_prefix_with_hash,"blur":function($event){return _vm.$v.form.branding_color.$touch()},"input":function($event){if($event.target.composing){ return; }_vm.$set(_vm.form, "branding_color", $event.target.value)}}}),_c('div',{staticStyle:{"height":"40px","width":"40px","border-radius":"5px","border":"1px solid #b1b6d1","margin-left":"5px"},style:({ 'background-color': _vm.brand_color_or_default })}),_c('div',{staticStyle:{"height":"40px","width":"40px","border-radius":"5px","border":"1px solid #b1b6d1","margin-left":"5px","background-color":"#fff","overflow":"hidden"}},[_c('div',{staticStyle:{"opacity":"0.1","width":"40px","height":"40px"},style:({ 'background-color': _vm.brand_color_or_default })})])]),_c('span',{staticClass:"txt-help"},[_vm._v(_vm._s(_vm.T__( "This color will be used as the accent color in the booking widget. We will automatically use a lighter shade as the background on certain elements." )))])]),_c('form-row',{attrs:{"validator":_vm.$v.form.branding_font}},[_c('label',[_vm._v(_vm._s(_vm.T__("Brand font")))]),_c('label',{staticClass:"label-cb"},[_c('input',{directives:[{name:"model",rawName:"v-model",value:(_vm.form.branding_font),expression:"form.branding_font"}],attrs:{"type":"checkbox","true-value":"inherit","false-value":""},domProps:{"checked":Array.isArray(_vm.form.branding_font)?_vm._i(_vm.form.branding_font,null)>-1:_vm._q(_vm.form.branding_font,"inherit")},on:{"change":function($event){var $$a=_vm.form.branding_font,$$el=$event.target,$$c=$$el.checked?("inherit"):("");if(Array.isArray($$a)){var $$v=null,$$i=_vm._i($$a,$$v);if($$el.checked){$$i<0&&(_vm.$set(_vm.form, "branding_font", $$a.concat([$$v])))}else{$$i>-1&&(_vm.$set(_vm.form, "branding_font", $$a.slice(0,$$i).concat($$a.slice($$i+1))))}}else{_vm.$set(_vm.form, "branding_font", $$c)}}}}),_vm._v(" "+_vm._s(_vm.T__("Use the website's default font for the booking widget.")))])]),_c('hr'),_c('form-row',{attrs:{"validator":_vm.$v.form.use_wp_mail}},[_c('div',{staticClass:"setting-group-title",staticStyle:{"display":"flex"}},[_vm._v(" "+_vm._s(_vm.T__("Email"))+" "),_c('PremiumInfoBadge',{staticStyle:{"margin":"-9px 0 0 10px"},attrs:{"highlight_feature":"email_features"}},[_c('em',[_vm._v("This feature is included"),_c('br'),_vm._v("in the PRO plan.")])])],1),_c('label',[_vm._v("WP Mailer")]),_c('span',{staticClass:"txt-help",staticStyle:{"margin-bottom":"5px"}},[_vm._v("We send all notification emails through our servers for better deliverability. If you have set up your own reliable system, you can choose to use WP Mailer (optionally, along with an SMTP plugin) instead.")]),_c('label',{staticClass:"label-cb"},[_c('input',{directives:[{name:"model",rawName:"v-model",value:(_vm.form.use_wp_mail),expression:"form.use_wp_mail"}],attrs:{"type":"checkbox","true-value":"1","false-value":"0"},domProps:{"checked":Array.isArray(_vm.form.use_wp_mail)?_vm._i(_vm.form.use_wp_mail,null)>-1:_vm._q(_vm.form.use_wp_mail,"1")},on:{"change":[function($event){var $$a=_vm.form.use_wp_mail,$$el=$event.target,$$c=$$el.checked?("1"):("0");if(Array.isArray($$a)){var $$v=null,$$i=_vm._i($$a,$$v);if($$el.checked){$$i<0&&(_vm.$set(_vm.form, "use_wp_mail", $$a.concat([$$v])))}else{$$i>-1&&(_vm.$set(_vm.form, "use_wp_mail", $$a.slice(0,$$i).concat($$a.slice($$i+1))))}}else{_vm.$set(_vm.form, "use_wp_mail", $$c)}},_vm.may_alert_already_scheduled_mails]}}),_vm._v(" "+_vm._s(_vm.T__("I understand. Use WP Mailer instead.")))])]),_c('div',{staticClass:"wpc-form-row"},[_c('label',[_vm._v("Email template & Calendar event content customization in PHP")]),_c('span',{staticClass:"txt-help",staticStyle:{"margin-bottom":"5px"}},[_c('a',{staticStyle:{"display":"inline-block","margin-bottom":"5px"},attrs:{"href":"https://help.wpcal.io/en/article/how-to-customise-email-templates-in-php-5369yp/?utm_source=wpcal_plugin&utm_medium=admin_header"}},[_vm._v("See how to customize ↗")]),_c('br'),_c('a',{on:{"click":_vm.load_and_show_template_overrides}},[_vm._v("See list of template overrides")])])]),_c('hr'),_c('form-row',{attrs:{"validator":_vm.$v.form.hide_premium_info_badges}},[_c('div',{staticClass:"setting-group-title"},[_vm._v(_vm._s(_vm.T__("Other")))]),_c('label',{staticClass:"label-cb"},[_c('input',{directives:[{name:"model",rawName:"v-model",value:(_vm.form.hide_premium_info_badges),expression:"form.hide_premium_info_badges"}],attrs:{"type":"checkbox","true-value":"1","false-value":"0"},domProps:{"checked":Array.isArray(_vm.form.hide_premium_info_badges)?_vm._i(_vm.form.hide_premium_info_badges,null)>-1:_vm._q(_vm.form.hide_premium_info_badges,"1")},on:{"change":function($event){var $$a=_vm.form.hide_premium_info_badges,$$el=$event.target,$$c=$$el.checked?("1"):("0");if(Array.isArray($$a)){var $$v=null,$$i=_vm._i($$a,$$v);if($$el.checked){$$i<0&&(_vm.$set(_vm.form, "hide_premium_info_badges", $$a.concat([$$v])))}else{$$i>-1&&(_vm.$set(_vm.form, "hide_premium_info_badges", $$a.slice(0,$$i).concat($$a.slice($$i+1))))}}else{_vm.$set(_vm.form, "hide_premium_info_badges", $$c)}}}}),_vm._v(" Hide the "),_c('span',{staticClass:"pro-badge-mu"},[_vm._v("Premium feature")]),_vm._v(" admin-end badge.")]),_c('span',{staticClass:"txt-help",staticStyle:{"margin-bottom":"5px"}},[_vm._v("This will hide the 'Premium feature' badge displayed in the plugin's admin pages. This is useful if you do not want your clients to see the badge."),_c('br'),_c('br'),_vm._v("This is NOT for hiding the 'Powered by WPCal.io' branding in the booking widget.")])]),_c('div',{staticClass:"wpc-form-row"},[_c('button',{staticClass:"wpc-btn primary lg mt20",staticStyle:{"width":"100%"},attrs:{"type":"button","disabled":!_vm.save_btn_is_enabled},on:{"click":_vm.form_submit}},[_vm._v(" "+_vm._s(_vm.T__("Save"))+" ")])])],1),_c('AdminInfoLine',{attrs:{"info_slug":"ownership_settings_general"}})],1)])],1),_c('modal',{staticStyle:{"z-index":"9999"},attrs:{"name":"template_overrides_list_modal","width":'800px',"height":"auto","scrollable":true,"adaptive":true}},[_c('div',{staticClass:"modal_close_cont"},[_c('button',{on:{"click":function($event){return _vm.$modal.hide('template_overrides_list_modal')}}})]),_c('div',{staticClass:"mbox bg-light shadow mb0"},[_c('table',{staticClass:"template_overrides_list",attrs:{"width":"100%"}},[_c('tr',[_c('th',{staticStyle:{"font-family":"'RubikMed'","font-weight":"unset !important"},attrs:{"width":"50%"}},[_vm._v(" Default template ")]),_c('th',{staticStyle:{"font-family":"'RubikMed'","font-weight":"unset !important"},attrs:{"width":"50%"}},[_vm._v(" Overriding template ")])]),(_vm._isEmpty(_vm.template_overrides))?_c('tr',[_c('td',{attrs:{"colspan":"2"}},[_vm._v("No template has been overridden")])]):_vm._e(),_vm._l((_vm.template_overrides),function(overridden_template,template){return _c('tr',{key:template},[_c('td',{staticStyle:{"vertical-align":"top:"}},[_vm._v(_vm._s(template))]),_c('td',{staticStyle:{"vertical-align":"top:"}},[_vm._v(_vm._s(overridden_template))])])})],2)])])],1)}
var SettingGeneralvue_type_template_id_0b4dc2c4_staticRenderFns = []


// CONCATENATED MODULE: ./src/views/admin/SettingGeneral.vue?vue&type=template&id=0b4dc2c4&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"1bf56cee-vue-loader-template"}!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/vue-loader/lib??vue-loader-options!./src/components/admin/SettingsMenu.vue?vue&type=template&id=5519ffc9&
var SettingsMenuvue_type_template_id_5519ffc9_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',{attrs:{"id":"nav"}},[_c('router-link',{attrs:{"to":"/settings"}},[_vm._v(_vm._s(_vm.T__("General")))]),_vm._v(" • "),_c('router-link',{attrs:{"to":"/settings/profile"}},[_vm._v(_vm._s(_vm.T__("Profile")))]),_vm._v(" • "),_c('router-link',{attrs:{"to":"/settings/calendars"}},[_vm._v(_vm._s(_vm.T__("Calendars")))]),_vm._v(" • "),_c('router-link',{attrs:{"to":"/settings/integrations"}},[_vm._v(_vm._s(_vm.T__("Integrations")))]),_vm._v(" • "),_c('router-link',{attrs:{"to":"/settings/admins"}},[_vm._v(_vm._s(_vm.T__("WPCal Admins")))]),_vm._v("  • "),_c('router-link',{attrs:{"to":"/settings/account"}},[_vm._v(_vm._s(_vm.T__("Plan")))])],1)}
var SettingsMenuvue_type_template_id_5519ffc9_staticRenderFns = []


// CONCATENATED MODULE: ./src/components/admin/SettingsMenu.vue?vue&type=template&id=5519ffc9&

// CONCATENATED MODULE: ./src/components/admin/SettingsMenu.vue

var script = {}


/* normalize component */

var SettingsMenu_component = Object(componentNormalizer["a" /* default */])(
  script,
  SettingsMenuvue_type_template_id_5519ffc9_render,
  SettingsMenuvue_type_template_id_5519ffc9_staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* harmony default export */ var SettingsMenu = (SettingsMenu_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--13-0!./node_modules/thread-loader/dist/cjs.js!./node_modules/babel-loader/lib!./node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/vue-loader/lib??vue-loader-options!./src/views/admin/SettingGeneral.vue?vue&type=script&lang=js&

//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//








/* harmony default export */ var SettingGeneralvue_type_script_lang_js_ = ({
  mixins: [on_before_leave_alert_if_unsaved_mixin_on_before_leave_alert_if_unsaved("saved_form", "form")],
  components: {
    SettingsMenu: SettingsMenu,
    TimeRange1ToN: TimeRange1ToN,
    WeekDaysSelector: WeekDaysSelector,
    TimeZoneSelector: TimeZoneSelector["a" /* default */]
  },
  data: function data() {
    return {
      document_title: this.T__("General ‹ Settings"),
      working_hours_array_: [],
      save_btn_is_enabled: true,
      form: this.clone_deep(this.$store.getters.get_general_settings),
      template_overrides: {} // form: {
      //   working_hours: {
      //     from_time: "",
      //     to_time: ""
      //   },
      //   working_days: [],
      //   time_format: "24hrs"
      // }

    };
  },
  validations: {
    form: {
      working_hours: {
        from_time: {
          required: validators["required"],
          valid_time: common_func["z" /* valid_time */]
        },
        to_time: {
          required: validators["required"],
          valid_time: common_func["z" /* valid_time */],
          min_time: Object(common_func["r" /* min_time_as */])("from_time")
        }
      },
      working_days: {
        subset_working_days: Object(common_func["t" /* subset */])([1, 2, 3, 4, 5, 6, 7])
      },
      time_format: {
        required: validators["required"],
        time_format_in_array: Object(common_func["o" /* in_array */])(["12hrs", "24hrs"])
      },
      branding_color: {
        valid_color: common_func["v" /* valid_color */]
      },
      branding_font: {
        branding_font_in_array: Object(common_func["o" /* in_array */])(["inherit", ""])
      },
      use_wp_mail: {
        use_wp_mail_in_array: Object(common_func["o" /* in_array */])(["1", "0"])
      },
      hide_premium_info_badges: {
        hide_premium_info_badges_in_array: Object(common_func["o" /* in_array */])(["1", "0"])
      }
    }
  },
  computed: {
    working_hours: function working_hours() {
      this.$set(this.working_hours_array_, 0, this.form.working_hours);
      return this.working_hours_array_;
    },
    brand_color_or_default: function brand_color_or_default() {
      return this.form.branding_color ? this.form.branding_color : "#567bf3";
    },
    saved_form: function saved_form() {
      return this.clone_deep(this.$store.getters.get_general_settings);
    } // form() {
    //   return this.clone_deep(this.$store.getters.get_general_settings);
    // }

  },
  methods: {
    load_page: function load_page() {// let action = "get_general_settings";
      // let wpcal_request = {};
      // wpcal_request[action] = {
      //   dummy__: ""
      // };
      // let post_data = {
      //   action: "wpcal_process_admin_ajax_request",
      //   wpcal_request: wpcal_request
      // };
      // axios
      //   .post(window.wpcal_ajax.ajax_url, post_data)
      //   .then(response => {
      //     console.log(response);
      //     if (
      //       response.data &&
      //       response.data[action].hasOwnProperty("general_settings")
      //     ) {
      //       this.form = response.data[action].general_settings;
      //       console.log(this.form);
      //     }
      //   })
      //   .catch(error => {
      //     console.log(error);
      //   });
      //console.log(this.$store.getters.get_general_settings);
      //this.form = this.$store.getters.get_general_settings;
      //this.form = Object.assign({}, this.$store.getters.get_general_settings);
    },
    form_submit: function form_submit() {
      var _this = this;

      this.clear_validation_errors_cont();
      this.$v.form.$touch();

      if (this.$v.form.$anyError) {
        this.scroll_to_first_validation_error();
        return false;
      }

      this.save_btn_is_enabled = false;
      var action_update_general_settings = "update_general_settings";
      var wpcal_request = {};
      wpcal_request[action_update_general_settings] = {
        general_settings: this.form
      };
      var action_get_general_settings = "get_general_settings";
      wpcal_request[action_get_general_settings] = {
        dummy__: ""
      };
      var post_data = {
        action: "wpcal_process_admin_ajax_request",
        wpcal_request: wpcal_request
      };
      axios_default.a.post(window.wpcal_ajax.ajax_url, post_data).then(function (response) {
        //console.log(response);
        _this.notify_single_action_result(response.data[action_update_general_settings], {
          success_msg: _this.T__("Settings saved.")
        });

        if (response.data && response.data[action_get_general_settings].hasOwnProperty("general_settings")) {
          var general_settings = response.data[action_get_general_settings].general_settings;

          _this.$store.commit("set_general_settings", general_settings);
        }
      }).catch(function (error) {
        console.log(error);
      }).then(function () {
        _this.save_btn_is_enabled = true;
      });
    },
    may_prefix_with_hash: function may_prefix_with_hash() {
      var color = this.form.branding_color;
      color = color.trim();

      if (color.length && color.charAt(0) !== "#") {
        color = "#" + color;
        this.form.branding_color = color;
      }
    },
    may_alert_already_scheduled_mails: function may_alert_already_scheduled_mails() {
      if (this.form.use_wp_mail == this.saved_form.use_wp_mail) {
        return;
      }

      this.$modal.show("dialog", {
        title: this.T__("Heads up!"),
        text: this.T__("The emails which are already scheduled will go as per old setting."),
        buttons: [{
          title: this.T__("Okay"),
          default: true // Will be triggered by default if 'Enter' pressed.

        }]
      });
    },
    load_and_show_template_overrides: function load_and_show_template_overrides() {
      var _this2 = this;

      var action = "get_template_overrides";
      var wpcal_request = {};
      wpcal_request[action] = {
        dummy__: ""
      };
      var post_data = {
        action: "wpcal_process_admin_ajax_request",
        wpcal_request: wpcal_request
      };
      axios_default.a.post(window.wpcal_ajax.ajax_url, post_data).then(function (response) {
        if (response.data && response.data[action].hasOwnProperty("template_overrides")) {
          _this2.template_overrides = response.data[action].template_overrides;
        }
      }).catch(function (error) {
        console.log(error);
      }).then(function () {
        _this2.show_template_overrides_modal();
      });
    },
    show_template_overrides_modal: function show_template_overrides_modal() {
      this.$modal.show("template_overrides_list_modal");
    }
  },
  watch: {
    "$store.getters.get_general_settings": function $storeGettersGet_general_settings() {
      this.form = this.clone_deep(this.$store.getters.get_general_settings);
      this.$v.form.$reset();
    }
  },
  created: function created() {
    this.load_page();
  }
});
// CONCATENATED MODULE: ./src/views/admin/SettingGeneral.vue?vue&type=script&lang=js&
 /* harmony default export */ var admin_SettingGeneralvue_type_script_lang_js_ = (SettingGeneralvue_type_script_lang_js_); 
// EXTERNAL MODULE: ./src/views/admin/SettingGeneral.vue?vue&type=style&index=0&lang=css&
var SettingGeneralvue_type_style_index_0_lang_css_ = __webpack_require__("d4ee");

// CONCATENATED MODULE: ./src/views/admin/SettingGeneral.vue






/* normalize component */

var SettingGeneral_component = Object(componentNormalizer["a" /* default */])(
  admin_SettingGeneralvue_type_script_lang_js_,
  SettingGeneralvue_type_template_id_0b4dc2c4_render,
  SettingGeneralvue_type_template_id_0b4dc2c4_staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* harmony default export */ var SettingGeneral = (SettingGeneral_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"1bf56cee-vue-loader-template"}!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/vue-loader/lib??vue-loader-options!./src/views/admin/SettingProfile.vue?vue&type=template&id=2547a160&
var SettingProfilevue_type_template_id_2547a160_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',[_c('div',[_c('div',{staticClass:"page-title-cont"},[_c('h1',{staticClass:"wp-heading-inline"},[_vm._v(" WPCal.io - "+_vm._s(_vm.T__("Profile Settings"))+" ")]),_c('AdminHeaderRight')],1),_c('SettingsMenu'),_c('div',{staticClass:"col-cont settings-cont"},[_c('div',{staticClass:"col"},[_c('div',{staticClass:"form"},[_c('div',{staticClass:"validation_errors_cont"}),_c('div',{staticClass:"setting-group-title"},[_vm._v(_vm._s(_vm.T__("My profile")))]),_c('form-row',{attrs:{"validator":_vm.$v.profile_settings.first_name}},[_c('label',[_vm._v(_vm._s(_vm.T__("First name"))),_c('abbr',{staticClass:"required",attrs:{"title":"required"}},[_vm._v("*")]),_c('em',[_vm._v(_vm._s(_vm.T__("required")))])]),_c('input',{directives:[{name:"model",rawName:"v-model",value:(_vm.profile_settings.first_name),expression:"profile_settings.first_name"}],attrs:{"type":"text"},domProps:{"value":(_vm.profile_settings.first_name)},on:{"input":function($event){if($event.target.composing){ return; }_vm.$set(_vm.profile_settings, "first_name", $event.target.value)}}})]),_c('form-row',{attrs:{"validator":_vm.$v.profile_settings.last_name}},[_c('label',[_vm._v(_vm._s(_vm.T__("Last name"))),_c('abbr',{staticClass:"required",attrs:{"title":"required"}},[_vm._v("*")]),_c('em',[_vm._v(_vm._s(_vm.T__("required")))])]),_c('input',{directives:[{name:"model",rawName:"v-model",value:(_vm.profile_settings.last_name),expression:"profile_settings.last_name"}],attrs:{"type":"text"},domProps:{"value":(_vm.profile_settings.last_name)},on:{"input":function($event){if($event.target.composing){ return; }_vm.$set(_vm.profile_settings, "last_name", $event.target.value)}}})]),_c('form-row',{attrs:{"validator":_vm.$v.profile_settings.display_name}},[_c('label',[_vm._v(_vm._s(_vm.T__("Display name"))),_c('abbr',{staticClass:"required",attrs:{"title":"required"}},[_vm._v("*")]),_c('em',[_vm._v(_vm._s(_vm.T__("required")))])]),_c('input',{directives:[{name:"model",rawName:"v-model",value:(_vm.profile_settings.display_name),expression:"profile_settings.display_name"}],attrs:{"type":"text"},domProps:{"value":(_vm.profile_settings.display_name)},on:{"input":function($event){if($event.target.composing){ return; }_vm.$set(_vm.profile_settings, "display_name", $event.target.value)}}})]),_c('form-row',{attrs:{"validator":_vm.$v.profile_settings.display_name}},[_c('label',[_vm._v(_vm._s(_vm.T__("Avatar"))),_c('abbr',{staticClass:"required",attrs:{"title":"required"}},[_vm._v("*")]),_c('em',[_vm._v(_vm._s(_vm.T__("required")))])]),_c('div',{staticStyle:{"display":"flex","align-items":"center","margin-bottom":"30px"}},[_c('div',[_c('img',{staticStyle:{"border-radius":"50%","border":"3px solid #fff","box-shadow":"0 0 0 1px #b1b6d1"},attrs:{"src":_vm.avatar_details.url,"width":parseInt(_vm.avatar_details.width) &&
                    parseInt(_vm.avatar_details.width) > 75
                      ? 75
                      : _vm.avatar_details.width}}),(_vm.avatar_details.is_gravatar)?_c('div',{staticStyle:{"font-size":"11px","font-style":"italic","color":"#7c7d9c","text-align":"center","position":"absolute","width":"81px"}},[_vm._v(" "+_vm._s(_vm.T__("Your Gravatar"))+" ")]):_vm._e()]),_c('div',{staticStyle:{"margin-left":"10px"}},[_c('a',{staticStyle:{"padding":"10px"},on:{"click":_vm.open_media_lib}},[_vm._v(_vm._s(_vm.T__("Change Pic")))]),(!_vm.avatar_details.is_gravatar)?_c('a',{staticStyle:{"padding":"10px"},on:{"click":_vm.use_gravatar}},[_vm._v(_vm._s(_vm.T__("Use my Gravatar")))]):_vm._e()])])]),_c('div',{staticClass:"wpc-form-row"},[_c('button',{staticClass:"wpc-btn primary lg mt20",staticStyle:{"width":"100%"},attrs:{"type":"button","disabled":!_vm.save_btn_is_enabled},on:{"click":_vm.form_submit}},[_vm._v(" "+_vm._s(_vm.T__("Save"))+" ")])])],1),_c('AdminInfoLine',{attrs:{"info_slug":"ownership_settings_profile"}})],1)])],1)])}
var SettingProfilevue_type_template_id_2547a160_staticRenderFns = []


// CONCATENATED MODULE: ./src/views/admin/SettingProfile.vue?vue&type=template&id=2547a160&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--13-0!./node_modules/thread-loader/dist/cjs.js!./node_modules/babel-loader/lib!./node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/vue-loader/lib??vue-loader-options!./src/views/admin/SettingProfile.vue?vue&type=script&lang=js&
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//




/* harmony default export */ var SettingProfilevue_type_script_lang_js_ = ({
  mixins: [on_before_leave_alert_if_unsaved_mixin_on_before_leave_alert_if_unsaved("saved_profile_settings", "profile_settings")],
  components: {
    SettingsMenu: SettingsMenu
  },
  data: function data() {
    return {
      document_title: this.T__("Profile ‹ Settings"),
      profile_settings: {
        first_name: "",
        last_name: "",
        display_name: "",
        avatar_attachment_id: ""
      },
      saved_profile_settings: this.clone_deep(this.profile_settings),
      //to avoid console error on initial rendering.
      avatar_details: {},
      is_refresh_avatar: false,
      save_btn_is_enabled: true
    };
  },
  validations: {
    profile_settings: {
      first_name: {
        required: validators["required"]
      },
      last_name: {
        required: validators["required"]
      },
      display_name: {
        required: validators["required"]
      }
    }
  },
  methods: {
    open_media_lib: function open_media_lib(e) {
      var this_v = this;
      e.preventDefault();
      var image = wp.media({
        title: this.T__("Upload Image"),
        // mutiple: true if you want to upload multiple files at once
        multiple: false
      }).open().on("select", function (e) {
        // This will return the selected image from the Media Uploader, the result is an object
        var uploaded_image = image.state().get("selection").first(); // We convert uploaded_image to a JSON object to make accessing it easier
        // Output to the console uploaded_image
        //console.log("here we go", uploaded_image);

        this_v.is_refresh_avatar = true;
        this_v.profile_settings.avatar_attachment_id = uploaded_image.id; //.toJSON().url;
        // Let's assign the url value to the input field
        //$("#image_url").val(image_url);
      });
    },
    get_avatar_attachment: function get_avatar_attachment(attachment_id) {
      var _this = this;

      var wpcal_request = {};
      var action_get_avatar_attachment = "get_admin_avatar_override_of_current_admin";
      wpcal_request[action_get_avatar_attachment] = {
        override_attachment_id: attachment_id
      };
      var post_data = {
        action: "wpcal_process_admin_ajax_request",
        wpcal_request: wpcal_request
      };
      axios_default.a.post(window.wpcal_ajax.ajax_url, post_data).then(function (response) {
        var _response$data$action;

        if (((_response$data$action = response.data[action_get_avatar_attachment]) === null || _response$data$action === void 0 ? void 0 : _response$data$action.status) == "success") {
          _this.is_refresh_avatar = false;
          _this.avatar_details = response.data[action_get_avatar_attachment].avatar_details;
        }
      }).catch(function (error) {
        console.log(error);
      });
    },
    use_gravatar: function use_gravatar() {
      this.is_refresh_avatar = true;
      this.profile_settings.avatar_attachment_id = "";
    },
    load_page: function load_page() {
      var _this2 = this;

      var wpcal_request = {};
      var action_get_profile_settings = "get_admin_profile_settings_of_current_admin";
      wpcal_request[action_get_profile_settings] = "dummy__";
      var action_get_avatar = "get_admin_avatar_of_current_admin";
      wpcal_request[action_get_avatar] = "dummy__";
      var post_data = {
        action: "wpcal_process_admin_ajax_request",
        wpcal_request: wpcal_request
      };
      axios_default.a.post(window.wpcal_ajax.ajax_url, post_data).then(function (response) {
        var _response$data$action2, _response$data$action3;

        if (((_response$data$action2 = response.data[action_get_profile_settings]) === null || _response$data$action2 === void 0 ? void 0 : _response$data$action2.status) == "success") {
          _this2.profile_settings = response.data[action_get_profile_settings].profile_settings;
          _this2.saved_profile_settings = _this2.clone_deep(_this2.profile_settings);
        }

        if (((_response$data$action3 = response.data[action_get_avatar]) === null || _response$data$action3 === void 0 ? void 0 : _response$data$action3.status) == "success") {
          _this2.is_refresh_avatar = false;
          _this2.avatar_details = response.data[action_get_avatar].avatar_details;
        }
      }).catch(function (error) {
        console.log(error);
      });
    },
    form_submit: function form_submit() {
      var _this3 = this;

      this.clear_validation_errors_cont();
      this.$v.profile_settings.$touch();

      if (this.$v.profile_settings.$anyError) {
        this.scroll_to_first_validation_error();
        return false;
      }

      this.save_btn_is_enabled = false;
      var wpcal_request = {};
      var action_update_profile_settings = "update_admin_profile_settings_of_current_admin";
      wpcal_request[action_update_profile_settings] = {
        profile_settings: this.profile_settings
      };
      var action_get_profile_settings = "get_admin_profile_settings_of_current_admin";
      wpcal_request[action_get_profile_settings] = "dummy__";
      var action_get_avatar = "get_admin_avatar_of_current_admin";
      wpcal_request[action_get_avatar] = "dummy__";
      var post_data = {
        action: "wpcal_process_admin_ajax_request",
        wpcal_request: wpcal_request
      };
      axios_default.a.post(window.wpcal_ajax.ajax_url, post_data).then(function (response) {
        var _response$data$action4, _response$data$action5;

        //console.log(response);
        _this3.notify_single_action_result(response.data[action_update_profile_settings], {
          success_msg: _this3.T__("Settings saved.")
        });

        if (((_response$data$action4 = response.data[action_get_profile_settings]) === null || _response$data$action4 === void 0 ? void 0 : _response$data$action4.status) == "success") {
          _this3.profile_settings = response.data[action_get_profile_settings].profile_settings;
          _this3.saved_profile_settings = _this3.clone_deep(_this3.profile_settings);
        }

        if (((_response$data$action5 = response.data[action_get_avatar]) === null || _response$data$action5 === void 0 ? void 0 : _response$data$action5.status) == "success") {
          _this3.is_refresh_avatar = false;
          _this3.avatar_details = response.data[action_get_avatar].avatar_details;
        }
      }).catch(function (error) {
        console.log(error);
      }).then(function () {
        _this3.save_btn_is_enabled = true;
      });
    }
  },
  watch: {
    "profile_settings.avatar_attachment_id": function profile_settingsAvatar_attachment_id(new_value, old_value) {
      if (this.is_refresh_avatar) {
        this.get_avatar_attachment(new_value);
      }
    }
  },
  mounted: function mounted() {
    this.load_page();
  }
});
// CONCATENATED MODULE: ./src/views/admin/SettingProfile.vue?vue&type=script&lang=js&
 /* harmony default export */ var admin_SettingProfilevue_type_script_lang_js_ = (SettingProfilevue_type_script_lang_js_); 
// CONCATENATED MODULE: ./src/views/admin/SettingProfile.vue





/* normalize component */

var SettingProfile_component = Object(componentNormalizer["a" /* default */])(
  admin_SettingProfilevue_type_script_lang_js_,
  SettingProfilevue_type_template_id_2547a160_render,
  SettingProfilevue_type_template_id_2547a160_staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* harmony default export */ var SettingProfile = (SettingProfile_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"1bf56cee-vue-loader-template"}!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/vue-loader/lib??vue-loader-options!./src/views/admin/SettingCalendars.vue?vue&type=template&id=a0542a7c&
var SettingCalendarsvue_type_template_id_a0542a7c_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',[_c('div',[_c('div',{staticClass:"page-title-cont"},[_c('h1',{staticClass:"wp-heading-inline"},[_vm._v(" WPCal.io - "+_vm._s(_vm.T__("Calendar Settings"))+" ")]),_c('AdminHeaderRight')],1),_c('SettingsMenu'),_c('OnBoardingCheckList',{attrs:{"display_in":"settings_calendars"}}),_c('div',{staticClass:"col-cont settings-cont"},[_c('div',{staticClass:"col"},[_c('div',{staticClass:"form"},[_c('div',{staticClass:"wpc-form-row"},[_c('label',{staticStyle:{"display":"flex","align-items":"center"}},[_vm._v(_vm._s(_vm.T__("My calendar accounts"))+" "),_c('a',{staticClass:"icon-q-mark",staticStyle:{"margin-top":"-2px"},attrs:{"href":"https://help.wpcal.io/en/article/calendar-integrations-4et2xz/?utm_source=wpcal_plugin&utm_medium=contextual","target":"_blank"}}),_c('PremiumInfoBadge',{staticStyle:{"margin":"-50px 0px 0px 10px"},attrs:{"highlight_feature":"calendars"}},[_c('em',[_vm._v("Free: 1 calendar account"),_c('br'),_vm._v("Plus: Unlimited")])])],1),_c('div',{staticClass:"mbox cal-accs"},[_c('div',{staticClass:"connected-accounts-cont"},_vm._l((_vm.calendar_accounts),function(calendar_account,index){
var _obj;
return _c('div',{key:index,staticClass:"connected-account",class:( _obj = {}, _obj[calendar_account.provider] = true, _obj['del-alert'] =  _vm.show_delete_highlight(
                      'disconnect_highlight_calendar_account_index',
                      index
                    ), _obj['status-disconnected'] =  calendar_account.status == -5, _obj )},[_c('div',{staticClass:"txt-h5"},[_c('div',{staticClass:"app-name"},[_vm._v(" "+_vm._s(_vm._f("calendar_provider_name")(calendar_account.provider))+" ")]),_c('div',{staticClass:"account-connection-actions"},[_c('OverflowMenu',{attrs:{"menu_id":calendar_account.id},model:{value:(_vm.calendar_account_overflow_menu_details),callback:function ($$v) {_vm.calendar_account_overflow_menu_details=$$v},expression:"calendar_account_overflow_menu_details"}},[(calendar_account.status == -5)?_c('a',{staticClass:"menu-item hover-hl",on:{"click":function($event){return _vm.add_calendar_account(
                              calendar_account.provider,
                              'reauth'
                            )}}},[_vm._v("Reconnect")]):_vm._e(),_c('a',{staticClass:"menu-item del-hl",staticStyle:{"color":"#e84653","font-size":"12px"},on:{"click":function($event){_vm.calendar_account_overflow_menu_details.open_menu_id = null;
                            _vm.show_disconnect_confirmation(calendar_account);},"mouseover":function($event){return _vm.set_mouseover_on_delete(
                              'disconnect_highlight_calendar_account_index',
                              index,
                              true
                            )},"mouseleave":function($event){return _vm.set_mouseover_on_delete(
                              'disconnect_highlight_calendar_account_index',
                              index,
                              false
                            )}}},[_vm._v(_vm._s(calendar_account.status == "-5" ? _vm.T__("Remove") : _vm.T__("Disconnect")))])]),_c('span',{staticClass:"status ",class:{
                          enabled: calendar_account.status == '1',
                          disabled: calendar_account.status != '1'
                        }},[_vm._v(_vm._s(calendar_account.status == "1" ? _vm.T__("Connected") : _vm.T__("Disconnected")))])],1)]),_c('div',{staticClass:"txt-help"},[_vm._v(" "+_vm._s(calendar_account.account_email)+" ")])])}),0),(!_vm.show_add_calandar_account_list)?_c('button',{staticClass:"wpc-btn secondary md full-width",on:{"click":function($event){_vm.show_add_calandar_account_list = true}}},[_vm._v(" "+_vm._s(_vm.T__("+ Add Calendar Account"))+" ")]):_vm._e(),(_vm.show_add_calandar_account_list)?_c('div',[_c('div',{staticClass:"txt-h5 caps"},[_vm._v(" "+_vm._s(_vm.T__("Select your calendar app"))+" ")]),_c('ul',{staticClass:"selector block cal_apps"},[_c('li',[_c('label',{staticClass:"google_calendar",on:{"click":function($event){return _vm.add_calendar_account('google_calendar')}}},[_vm._v("Google Calendar")])]),_c('li',{staticClass:"disabled"},[_c('label',{staticClass:"office365_calendar"},[_c('em',[_vm._v("Office 365")]),_c('span',[_vm._v(_vm._s(_vm.T__("COMING SOON")))])])]),_c('li',{staticClass:"disabled"},[_c('label',{staticClass:"exchange_calendar"},[_c('em',[_vm._v("Exchange Calendar")]),_c('span',[_vm._v(_vm._s(_vm.T__("COMING SOON")))])])]),_c('li',{staticClass:"disabled"},[_c('label',{staticClass:"outlook_calendar"},[_c('em',[_vm._v("Outlook Plug-in")]),_c('span',[_vm._v(_vm._s(_vm.T__("COMING SOON")))])])]),_c('li',{staticClass:"disabled"},[_c('label',{staticClass:"icloud_calendar"},[_c('em',[_vm._v("iCloud Calendar")]),_c('span',[_vm._v(_vm._s(_vm.T__("COMING SOON")))])])])])]):_vm._e()])])]),_c('AdminInfoLine',{attrs:{"info_slug":"ownership_settings_calendars"}})],1),_c('div',{staticClass:"col"},[_c('div',{staticClass:"form"},[_c('div',{staticClass:"wpc-form-row"},[_c('label',[_vm._v(_vm._s(_vm.T__("Configuration")))]),_c('ul',{staticClass:"mbox",class:{
                'onboard-overlay-element': _vm.show_default_calendars_added_onboard
              },staticStyle:{"margin-top":"0"},attrs:{"id":"calendar_cofiguration_cont"}},[_c('li',[_c('div',[_c('div',{staticClass:"txt-h5 caps"},[_vm._v(" "+_vm._s(_vm.T__("Add bookings to..."))+" "),_c('a',{staticClass:"icon-q-mark",attrs:{"href":"https://help.wpcal.io/en/article/calendar-integrations-4et2xz/?utm_source=wpcal_plugin&utm_medium=contextual#1-how-add-bookings-to-calendar-works","target":"_blank"}})]),_c('div',{staticClass:"txt-help"},[_vm._v(" "+_vm._s(_vm.T__( "Set the calendar you would like to add new bookings to as they're scheduled." ))+" ")]),_c('div',{staticClass:"cal-list cf p10 border-all border-round mt10",on:{"click":_vm.show_choose_add_bookings_to_calendar_modal}},[(
                        _vm.calendar_accounts.hasOwnProperty(
                          _vm.add_bookings_to_calendar_account_id
                        )
                      )?_c('div',[_vm._v(" "+_vm._s(_vm.T__("Add to"))+" "),_c('span',{staticClass:"cal-provider",class:_vm.calendar_accounts[
                            _vm.add_bookings_to_calendar_account_id
                          ].provider},[_vm._v(" "+_vm._s(_vm.calendar_accounts[ _vm.add_bookings_to_calendar_account_id ].account_email)),_c('a',{staticClass:"fl-rt"},[_vm._v(_vm._s(_vm.T__("EDIT")))])]),_c('ul',{staticClass:"bull"},[_c('li',[_vm._v(_vm._s(_vm.add_bookings_to_calendar.name))])])]):_c('div',[_vm._v(" "+_vm._s(_vm.T__("No calendar is choosen."))),_c('a',{staticClass:"fl-rt"},[_vm._v(_vm._s(_vm.T__("EDIT")))])])]),_c('div',{staticClass:"txt-help mt5"},[_vm._v(" "+_vm._s(_vm.T__( "This has to be set for Google Meet to work. When set, invitations and notifications will be sent to the invitee directly from your Google Calendar account." ))+" ")]),_c('modal',{staticStyle:{"z-index":"9999"},attrs:{"name":"choose_add_bookings_to_calendar_modal","height":"auto","width":"360px"},on:{"before-close":function($event){_vm.can_show_choose_add_bookings_to_calendar_modal = false}}},[_c('div',{staticClass:"modal_close_cont"},[_c('button',{on:{"click":function($event){return _vm.$modal.hide('choose_add_bookings_to_calendar_modal')}}})]),(_vm.can_show_choose_add_bookings_to_calendar_modal)?_c('div',{staticClass:"mbox bg-light shadow mb0"},[_c('div',{staticClass:"form"},[_c('div',{staticClass:"wpc-form-row"},[_c('label',{staticStyle:{"text-align":"center"}},[_vm._v("Which calendar should we add"),_c('br'),_vm._v("new events to?")]),_c('ul',{staticClass:"selector block mt10 add-to-cal"},[_vm._l((_vm.calendar_accounts),function(calendar_account,index){return _c('li',{key:index},[_c('input',{directives:[{name:"model",rawName:"v-model",value:(
                                  _vm.tmp_edit_add_bookings_to_calendar_account_id
                                ),expression:"\n                                  tmp_edit_add_bookings_to_calendar_account_id\n                                "}],attrs:{"type":"radio","id":'add-to-cal-opt' + index,"name":"add_bookings_to_calendar_account"},domProps:{"value":calendar_account.id,"checked":_vm._q(
                                  _vm.tmp_edit_add_bookings_to_calendar_account_id
                                ,calendar_account.id)},on:{"change":[function($event){_vm.tmp_edit_add_bookings_to_calendar_account_id
                                =calendar_account.id},_vm.choose_default_calendar_for_add_bookings_to]}}),_c('label',{class:calendar_account.provider,attrs:{"for":'add-to-cal-opt' + index}},[_vm._v(" "+_vm._s(calendar_account.account_email))])])}),_c('li',[_c('input',{directives:[{name:"model",rawName:"v-model",value:(
                                  _vm.tmp_edit_add_bookings_to_calendar_account_id
                                ),expression:"\n                                  tmp_edit_add_bookings_to_calendar_account_id\n                                "}],attrs:{"type":"radio","id":"add-to-cal-dont","name":"add_bookings_to_calendar_account","value":"no"},domProps:{"checked":_vm._q(
                                  _vm.tmp_edit_add_bookings_to_calendar_account_id
                                ,"no")},on:{"change":function($event){_vm.tmp_edit_add_bookings_to_calendar_account_id
                                ="no"}}}),_c('label',{attrs:{"for":"add-to-cal-dont"}},[_vm._v(_vm._s(_vm.T__("Do not add new bookings to any calendar")))])])],2)])]),(
                          _vm.calendar_accounts.hasOwnProperty(
                            _vm.tmp_edit_add_bookings_to_calendar_account_id
                          )
                        )?_c('div',[_c('div',{staticStyle:{"font-size":"14px","margin":"20px 0 5px"}},[_vm._v(" "+_vm._s(_vm.T__("Add to Calendar"))+" ")]),_c('select',{directives:[{name:"model",rawName:"v-model",value:(_vm.tmp_edit_add_bookings_to_calendar_id),expression:"tmp_edit_add_bookings_to_calendar_id"}],on:{"change":function($event){var $$selectedVal = Array.prototype.filter.call($event.target.options,function(o){return o.selected}).map(function(o){var val = "_value" in o ? o._value : o.value;return val}); _vm.tmp_edit_add_bookings_to_calendar_id=$event.target.multiple ? $$selectedVal : $$selectedVal[0]}}},_vm._l((this
                              .calendar_accounts[
                              _vm.tmp_edit_add_bookings_to_calendar_account_id
                            ].calendars),function(calendar,index){return _c('option',{key:index,attrs:{"disabled":calendar.is_writable == '0'},domProps:{"value":calendar.id}},[_vm._v(_vm._s(calendar.name))])}),0)]):_vm._e(),_c('button',{staticClass:"wpc-btn primary md mt20",attrs:{"type":"button"},on:{"click":_vm.update_add_bookings_to_calendar_id}},[_vm._v(" "+_vm._s(_vm.T__("Update"))+" ")])]):_vm._e()])],1)]),_c('li',[_c('div',[_c('div',{staticClass:"txt-h5 caps"},[_vm._v(" "+_vm._s(_vm.T__("Check for conflicts"))+" "),_c('a',{staticClass:"icon-q-mark",attrs:{"href":"https://help.wpcal.io/en/article/calendar-integrations-4et2xz/?utm_source=wpcal_plugin&utm_medium=contextual#1-how-check-for-conflicts-calendar-works","target":"_blank"}}),_c('SettingAdminContext',{staticStyle:{"all":"initial","padding-left":"5px"},attrs:{"options":['cal_conflict_free_as_busy'],"settings_name":"settings_calendar_conflict"}})],1),_c('div',{staticClass:"txt-help"},[_vm._v(" "+_vm._s(_vm.T__( "Set the calendar(s) to check and exclude slots already scheduled outside WPCal." ))+" ")]),_c('div',[_vm._l((_vm.conflict_calendar_accounts),function(calendar_account,index){return _c('div',{key:index,staticClass:"cal-list cf p10 border-all border-round mt10",on:{"click":function($event){_vm.conflict_calendar_account_id = calendar_account.id;
                        _vm.show_choose_conflict_calendars_modal();}}},[_c('div',[_vm._v(" Check "),_c('span',{staticClass:"cal-provider",class:calendar_account.provider},[_vm._v(" "+_vm._s(calendar_account.account_email)),_c('a',{staticClass:"fl-rt"},[_vm._v(_vm._s(_vm.T__("EDIT")))])]),_vm._l((calendar_account.calendars),function(calendar,index2){return _c('div',{key:index2},[_c('ul',{staticClass:"bull"},[_c('li',[_vm._v(_vm._s(calendar.name))])])])})],2)])}),(_vm._isEmpty(_vm.conflict_calendar_accounts))?_c('div',{staticClass:"cal-list cf p10 border-all border-round mt10",on:{"click":_vm.show_choose_conflict_calendars_modal}},[_c('div',[_vm._v(" "+_vm._s(_vm.T__("No calendar is choosen."))+" "),_c('a',{staticClass:"fl-rt"},[_vm._v(_vm._s(_vm.T__("EDIT")))])])]):_vm._e()],2),_c('modal',{staticStyle:{"z-index":"9999"},attrs:{"name":"choose_conflict_calendars_modal","height":"auto","width":"360px"},on:{"before-close":function($event){_vm.can_show_choose_conflict_calendars_modal = false}}},[_c('div',{staticClass:"modal_close_cont"},[_c('button',{on:{"click":function($event){return _vm.$modal.hide('choose_conflict_calendars_modal')}}})]),(_vm.can_show_choose_conflict_calendars_modal)?_c('div',{staticClass:"mbox bg-light shadow"},[_c('div',{staticClass:"form"},[_c('div',{staticClass:"wpc-form-row"},[_c('label',{staticClass:"mb10",staticStyle:{"text-align":"center"}},[_vm._v(_vm._s(_vm.T__("Check for conflicts in...")))]),(_vm.calendar_accounts)?_c('select',{directives:[{name:"model",rawName:"v-model",value:(_vm.tmp_edit_conflict_calendar_account_id),expression:"tmp_edit_conflict_calendar_account_id"}],on:{"change":function($event){var $$selectedVal = Array.prototype.filter.call($event.target.options,function(o){return o.selected}).map(function(o){var val = "_value" in o ? o._value : o.value;return val}); _vm.tmp_edit_conflict_calendar_account_id=$event.target.multiple ? $$selectedVal : $$selectedVal[0]}}},_vm._l((this
                                .calendar_accounts),function(calendar_account,index){return _c('option',{key:index,domProps:{"value":calendar_account.id}},[_vm._v(_vm._s(calendar_account.account_email))])}),0):_vm._e(),_c('div',{staticStyle:{"font-size":"14px","margin":"20px 0 5px"}},[_vm._v(" "+_vm._s(_vm.T__("Check these calendars for conflicts"))+" ")]),(_vm.tmp_edit_conflict_calendar_account_id)?_c('div',[_c('ul',{staticClass:"selector block mt10 check-conflicts"},_vm._l((_vm.calendar_accounts[
                                  _vm.tmp_edit_conflict_calendar_account_id
                                ].calendars),function(calendar,index){return _c('li',{key:index},[_c('input',{directives:[{name:"model",rawName:"v-model",value:(_vm.tmp_edit_conflict_calendar_ids),expression:"tmp_edit_conflict_calendar_ids"}],attrs:{"type":"checkbox","id":'check-conflicts-opt' + index},domProps:{"value":calendar.id,"checked":Array.isArray(_vm.tmp_edit_conflict_calendar_ids)?_vm._i(_vm.tmp_edit_conflict_calendar_ids,calendar.id)>-1:(_vm.tmp_edit_conflict_calendar_ids)},on:{"change":function($event){var $$a=_vm.tmp_edit_conflict_calendar_ids,$$el=$event.target,$$c=$$el.checked?(true):(false);if(Array.isArray($$a)){var $$v=calendar.id,$$i=_vm._i($$a,$$v);if($$el.checked){$$i<0&&(_vm.tmp_edit_conflict_calendar_ids=$$a.concat([$$v]))}else{$$i>-1&&(_vm.tmp_edit_conflict_calendar_ids=$$a.slice(0,$$i).concat($$a.slice($$i+1)))}}else{_vm.tmp_edit_conflict_calendar_ids=$$c}}}}),_c('label',{attrs:{"for":'check-conflicts-opt' + index}},[_vm._v(_vm._s(calendar.name))])])}),0)]):_vm._e(),_c('button',{staticClass:"wpc-btn primary md mt20",attrs:{"type":"button"},on:{"click":_vm.update_conflict_calendar_ids}},[_vm._v(" "+_vm._s(_vm.T__("Update"))+" ")])])])]):_vm._e()])],1)])]),(_vm.show_default_calendars_added_onboard)?_c('div',{staticClass:"onboard-overlay-popper popper-cont",class:{
                'popper-go-back':
                  _vm.show_default_calendars_added_onboard &&
                  (_vm.can_show_choose_add_bookings_to_calendar_modal ||
                    _vm.can_show_choose_conflict_calendars_modal)
              },staticStyle:{"z-index":"10000","width":"360px"},attrs:{"id":"calendar_cofiguration_cont_tip"}},[_c('div',{staticClass:"mbox shadow"},[_c('div',{staticClass:"popper_arrow",attrs:{"data-popper-arrow":""}}),_vm._v(" We have automatically added your "+_vm._s(_vm.calendar_accounts[_vm.add_bookings_to_calendar_account_id] .account_email)+" account's primary calendar "),_c('strong',[_vm._v(_vm._s(_vm.calendar_accounts[_vm.add_bookings_to_calendar_account_id] .calendars[_vm.add_bookings_to_calendar_id].name))]),_vm._v(" to both 'Add bookings to...' and 'Check for conflicts' options. "),_c('br'),_c('br'),_vm._v("You can change or remove this."),_c('br'),_c('br'),_c('div',{staticStyle:{"background-color":"#f9f5bf","color":"#e19334","border":"1px dashed #e19334","padding":"8px 10px","border-radius":"5px"}},[_vm._v(" Important: Since the "),_c('strong',[_vm._v(_vm._s(_vm.calendar_accounts[_vm.add_bookings_to_calendar_account_id] .calendars[_vm.add_bookings_to_calendar_id].name))]),_vm._v(" calendar has been added to 'Check for conflits', time slots of events in that calendar will be excluded from your availability. ")]),_c('button',{staticClass:"wpc-btn primary md",staticStyle:{"margin-top":"10px"},attrs:{"data-v-74b16bbd":"","type":"button"},on:{"click":function($event){_vm.can_close_default_calendars_added_onboard = true;
                    _vm.hide_default_calendars_added_onboard_modal();}}},[_vm._v(" Okay. Got it! ")])])]):_vm._e()])])])])],1),_c('modal',{staticClass:"onboard-overlay",attrs:{"name":"default_calendars_added_onboard_overlay"},on:{"before-close":_vm.before_close_default_calendars_added_onboard_modal}},[_c('span')])],1)}
var SettingCalendarsvue_type_template_id_a0542a7c_staticRenderFns = []


// CONCATENATED MODULE: ./src/views/admin/SettingCalendars.vue?vue&type=template&id=a0542a7c&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"1bf56cee-vue-loader-template"}!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/vue-loader/lib??vue-loader-options!./src/components/admin/SettingAdminContext.vue?vue&type=template&id=a268a04c&scoped=true&
var SettingAdminContextvue_type_template_id_a268a04c_scoped_true_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('span',[_c('span',[_c('span',{staticClass:"setting-icon",on:{"click":_vm.show_setting_modal}})]),(!_vm._isEmpty(_vm.options))?_c('modal',{staticStyle:{"z-index":"9999"},attrs:{"name":_vm.settings_modal_name,"height":"auto","width":"360px"},on:{"after-close":_vm.on_modal_after_close}},[_c('div',{staticClass:"modal_close_cont"},[_c('button',{on:{"click":function($event){return _vm.$modal.hide(_vm.settings_modal_name)}}})]),_c('div',{staticClass:"mbox bg-light shadow mb0"},[_c('div',{staticClass:"col-cont settings-cont"},[_c('div',{staticClass:"col"},[_c('div',{staticClass:"form"},[_c('div',{staticClass:"validation_errors_cont"}),(_vm.options.includes('cal_conflict_free_as_busy'))?_c('form-row',{attrs:{"validator":_vm.$v.form.cal_conflict_free_as_busy}},[_c('label',{staticStyle:{"text-align":"center"}},[_vm._v(_vm._s(_vm.T__("Conflict calendar settings")))]),_c('label',{staticClass:"label-cb"},[_c('input',{directives:[{name:"model",rawName:"v-model",value:(_vm.form.cal_conflict_free_as_busy),expression:"form.cal_conflict_free_as_busy"}],attrs:{"type":"checkbox","true-value":"1","false-value":"0"},domProps:{"checked":Array.isArray(_vm.form.cal_conflict_free_as_busy)?_vm._i(_vm.form.cal_conflict_free_as_busy,null)>-1:_vm._q(_vm.form.cal_conflict_free_as_busy,"1")},on:{"change":function($event){var $$a=_vm.form.cal_conflict_free_as_busy,$$el=$event.target,$$c=$$el.checked?("1"):("0");if(Array.isArray($$a)){var $$v=null,$$i=_vm._i($$a,$$v);if($$el.checked){$$i<0&&(_vm.$set(_vm.form, "cal_conflict_free_as_busy", $$a.concat([$$v])))}else{$$i>-1&&(_vm.$set(_vm.form, "cal_conflict_free_as_busy", $$a.slice(0,$$i).concat($$a.slice($$i+1))))}}else{_vm.$set(_vm.form, "cal_conflict_free_as_busy", $$c)}}}}),_vm._v(" "+_vm._s(_vm.T__("Consider all calendar events are busy.")))]),_c('span',{staticClass:"txt-help"},[_vm._v(_vm._s(_vm.T__( "When unchecked, busy events in the calendar are seen as conflicts, and overlapping time slots are automatically removed to avoid conflicts. If this option is checked, events marked as free will also be treated as busy." ))+" "),_c('br'),_c('br'),_vm._v(" "+_vm._s(_vm.T__( "Note: In Google Calendar by default all day events are marked as free." )))])]):_vm._e(),_c('div',{staticClass:"wpc-form-row"},[_c('button',{staticClass:"wpc-btn primary lg mt20",staticStyle:{"width":"100%"},attrs:{"type":"button","disabled":!_vm.save_btn_is_enabled},on:{"click":_vm.form_submit}},[_vm._v(" "+_vm._s(_vm.T__("Save"))+" ")])])],1)])])])]):_vm._e()],1)}
var SettingAdminContextvue_type_template_id_a268a04c_scoped_true_staticRenderFns = []


// CONCATENATED MODULE: ./src/components/admin/SettingAdminContext.vue?vue&type=template&id=a268a04c&scoped=true&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--13-0!./node_modules/thread-loader/dist/cjs.js!./node_modules/babel-loader/lib!./node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/vue-loader/lib??vue-loader-options!./src/components/admin/SettingAdminContext.vue?vue&type=script&lang=js&
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//


/* harmony default export */ var SettingAdminContextvue_type_script_lang_js_ = ({
  name: "SettingAdminContext",
  props: {
    options: {
      type: Array
    },
    settings_name: {
      type: String
    }
  },
  data: function data() {
    return {
      form: {
        cal_conflict_free_as_busy: "0"
      },
      saved_admin_settings: this.clone_deep(this.form),
      save_btn_is_enabled: true
    };
  },
  computed: {
    settings_modal_name: function settings_modal_name() {
      return this.settings_name + "_modal";
    }
  },
  validations: {
    form: {
      cal_conflict_free_as_busy: {
        cal_conflict_free_as_busy_in_array: Object(common_func["o" /* in_array */])(["1", "0"])
      }
    }
  },
  methods: {
    load_page: function load_page() {
      var _this = this;

      var wpcal_request = {};
      var action_get_admin_settings = "get_admin_settings_of_current_admin";
      wpcal_request[action_get_admin_settings] = "dummy__";
      var post_data = {
        action: "wpcal_process_admin_ajax_request",
        wpcal_request: wpcal_request
      };
      axios_default.a.post(window.wpcal_ajax.ajax_url, post_data).then(function (response) {
        var _response$data$action;

        if (((_response$data$action = response.data[action_get_admin_settings]) === null || _response$data$action === void 0 ? void 0 : _response$data$action.status) == "success") {
          _this.form = response.data[action_get_admin_settings].admin_settings;
          _this.saved_admin_settings = _this.clone_deep(_this.form);
        }
      }).catch(function (error) {
        console.log(error);
      });
    },
    form_submit: function form_submit() {
      var _this2 = this;

      this.clear_validation_errors_cont();
      this.$v.form.$touch();

      if (this.$v.form.$anyError) {
        this.scroll_to_first_validation_error();
        return false;
      }

      this.save_btn_is_enabled = false;
      var action_update_admin_settings = "update_admin_settings_of_current_admin";
      var wpcal_request = {};
      wpcal_request[action_update_admin_settings] = {
        admin_settings: this.form
      };
      var action_get_admin_settings = "get_admin_settings_of_current_admin";
      wpcal_request[action_get_admin_settings] = {
        dummy__: ""
      };
      var post_data = {
        action: "wpcal_process_admin_ajax_request",
        wpcal_request: wpcal_request
      };
      axios_default.a.post(window.wpcal_ajax.ajax_url, post_data).then(function (response) {
        //console.log(response);
        _this2.notify_single_action_result(response.data[action_update_admin_settings], {
          success_msg: _this2.T__("Settings saved.")
        });

        if (response.data && response.data[action_get_admin_settings].hasOwnProperty("admin_settings")) {
          _this2.form = response.data[action_get_admin_settings].admin_settings;
          _this2.saved_admin_settings = _this2.clone_deep(_this2.form);
          setTimeout(function () {
            _this2.close_setting_modal();
          }, 200);
        }
      }).catch(function (error) {
        console.log(error);
      }).then(function () {
        _this2.save_btn_is_enabled = true;
      });
    },
    on_mounted: function on_mounted() {// if (!this._isEmpty(this.options)) {
      //   this.$modal.show(this.settings_modal_name);
      // }
    },
    on_modal_after_close: function on_modal_after_close() {
      this.$emit("settings-modal-closed", "");
      this.$destroy();
    },
    show_setting_modal: function show_setting_modal() {
      this.$modal.show(this.settings_modal_name);
    },
    close_setting_modal: function close_setting_modal() {
      this.$modal.hide(this.settings_modal_name);
    }
  },
  mounted: function mounted() {
    this.load_page();
  },
  watch: {}
});
// CONCATENATED MODULE: ./src/components/admin/SettingAdminContext.vue?vue&type=script&lang=js&
 /* harmony default export */ var admin_SettingAdminContextvue_type_script_lang_js_ = (SettingAdminContextvue_type_script_lang_js_); 
// EXTERNAL MODULE: ./src/components/admin/SettingAdminContext.vue?vue&type=style&index=0&id=a268a04c&scoped=true&lang=css&
var SettingAdminContextvue_type_style_index_0_id_a268a04c_scoped_true_lang_css_ = __webpack_require__("e129");

// CONCATENATED MODULE: ./src/components/admin/SettingAdminContext.vue






/* normalize component */

var SettingAdminContext_component = Object(componentNormalizer["a" /* default */])(
  admin_SettingAdminContextvue_type_script_lang_js_,
  SettingAdminContextvue_type_template_id_a268a04c_scoped_true_render,
  SettingAdminContextvue_type_template_id_a268a04c_scoped_true_staticRenderFns,
  false,
  null,
  "a268a04c",
  null
  
)

/* harmony default export */ var SettingAdminContext = (SettingAdminContext_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--13-0!./node_modules/thread-loader/dist/cjs.js!./node_modules/babel-loader/lib!./node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/vue-loader/lib??vue-loader-options!./src/views/admin/SettingCalendars.vue?vue&type=script&lang=js&

//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//






/* harmony default export */ var SettingCalendarsvue_type_script_lang_js_ = ({
  components: {
    SettingsMenu: SettingsMenu,
    OnBoardingCheckList: OnBoardingCheckList,
    OverflowMenu: OverflowMenu,
    SettingAdminContext: SettingAdminContext
  },
  props: {
    connect_status: {
      type: String,
      default: null
    },
    connected_calendar_account_id: {
      type: String,
      default: null
    },
    connected_calendar_default_calendars_added: {
      type: Boolean,
      default: false
    }
  },
  data: function data() {
    return {
      document_title: "Calendars ‹ Settings",
      show_add_calandar_account_list: false,
      calendar_accounts: [],
      can_show_choose_add_bookings_to_calendar_modal: false,
      can_show_choose_conflict_calendars_modal: false,
      add_bookings_to_calendar_account_id: "no",
      add_bookings_to_calendar_id: "",
      tmp_edit_add_bookings_to_calendar_account_id: "no",
      tmp_edit_add_bookings_to_calendar_id: "",
      conflict_calendar_ids: [],
      conflict_calendar_account_id: "",
      tmp_edit_conflict_calendar_ids: [],
      tmp_edit_conflict_calendar_account_id: "",
      disconnect_highlight_calendar_account_index: null,
      connect_status_notified: false,
      show_default_calendars_added_onboard: false,
      default_calendars_added_onboard_popper_instance: null,
      can_close_default_calendars_added_onboard: false,
      calendar_account_overflow_menu_details: {
        open_menu_id: null,
        popper_instance: null
      }
    };
  },
  computed: {
    add_bookings_to_calendar: function add_bookings_to_calendar() {
      if (this.add_bookings_to_calendar_account_id && this.add_bookings_to_calendar_id) {
        if (this.calendar_accounts.hasOwnProperty(this.add_bookings_to_calendar_account_id) && this.calendar_accounts[this.add_bookings_to_calendar_account_id].calendars.hasOwnProperty(this.add_bookings_to_calendar_id)) {
          return this.calendar_accounts[this.add_bookings_to_calendar_account_id].calendars[this.add_bookings_to_calendar_id];
        }
      }

      return {};
    },
    conflict_calendar_accounts: function conflict_calendar_accounts() {
      var conflict_calendar_accounts = {};

      for (var calendar_account_id in this.calendar_accounts) {
        for (var calendar_id in this.calendar_accounts[calendar_account_id].calendars) {
          if (this.conflict_calendar_ids.indexOf(calendar_id) !== -1) {
            if (!conflict_calendar_accounts.hasOwnProperty(calendar_account_id)) {
              conflict_calendar_accounts[calendar_account_id] = Object.assign({}, this.calendar_accounts[calendar_account_id]);
              conflict_calendar_accounts[calendar_account_id].calendars = {};
            }

            conflict_calendar_accounts[calendar_account_id].calendars[calendar_id] = this.calendar_accounts[calendar_account_id].calendars[calendar_id];
          }
        }
      }

      return conflict_calendar_accounts;
    },
    enable_add_new_calendar_account: function enable_add_new_calendar_account() {
      // let count = Object.keys(this.calendar_accounts).length;
      // if (count >= 6) {
      //   return false;
      // }
      return true;
    }
  },
  methods: {
    add_calendar_account: function add_calendar_account(provider) {
      var do_ = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : "add";
      var action = "add_calendar_account";

      if (do_ == "reauth") {
        action = "reauth_calendar_account";
      }

      if (provider === "google_calendar") {
        window.location = "admin.php?page=wpcal_admin&wpcal_action=" + action + "&provider=google_calendar";
      }
    },
    load_page: function load_page() {
      var _this = this;

      var action = "get_calendar_accounts_details_of_current_admin";
      var wpcal_request = {};
      wpcal_request[action] = {
        dummy__: ""
      };
      var action2 = "get_add_bookings_to_calendar_of_current_admin";
      wpcal_request[action2] = {
        dummy__: ""
      };
      var action3 = "get_conflict_calendar_ids_of_current_admin";
      wpcal_request[action3] = {
        dummy__: ""
      };
      var post_data = {
        action: "wpcal_process_admin_ajax_request",
        wpcal_request: wpcal_request
      };
      axios_default.a.post(window.wpcal_ajax.ajax_url, post_data).then(function (response) {
        var connect_status_notified = _this.connect_status_notified; //Following commented for UI issue when popup opens before sync is done. As load page and sync happen in successions I belive it shouldn't create issue
        //Object.assign(this.$data, this.$options.data.apply(this)); //re-initilize data in Vue - this required as we are using this function to after update

        _this.connect_status_notified = connect_status_notified; //console.log(response);

        if (response.data && response.data[action].hasOwnProperty("calendar_accounts_details")) {
          _this.calendar_accounts = response.data[action].calendar_accounts_details;

          if (Object(esm_typeof["a" /* default */])(response.data[action2]) == "object" && response.data[action2].hasOwnProperty("add_bookings_to_calendar")) {
            if (response.data[action2].add_bookings_to_calendar && response.data[action2].add_bookings_to_calendar.hasOwnProperty("calendar_id")) {
              _this.add_bookings_to_calendar_id = response.data[action2].add_bookings_to_calendar.calendar_id;
              _this.add_bookings_to_calendar_account_id = response.data[action2].add_bookings_to_calendar.calendar_account_id;
            } else if (response.data[action2].add_bookings_to_calendar == null) {
              _this.add_bookings_to_calendar_account_id = "no";
              _this.add_bookings_to_calendar_id = "";
            }
          }

          _this.conflict_calendar_ids = response.data[action3].conflict_calendar_ids;

          _this.set_default_conflict_calendar_account_id();

          _this.handle_connect_result();
        }
      }).catch(function (error) {
        console.log(error);
      });
    },
    handle_connect_result: function handle_connect_result() {
      var _this2 = this;

      if (!this.connect_status || !this.connected_calendar_account_id) {
        return;
      }

      if (this.connect_status_notified) {
        return;
      }

      if (this.connect_status === "connected" && !this._isEmpty(this.calendar_accounts)) {
        for (var index in this.calendar_accounts) {
          if (this.calendar_accounts[index].id != this.connected_calendar_account_id) {
            continue;
          }

          var success_msg = this.Tsprintf(
          /* translators: 1: html-tag-open 2: html-tag-close 3: tp provider 4: account email */
          this.T__("Your %1$s %3$s %2$s account %1$s %4$s %2$s is succesfully connected."), "<strong>", "</strong>", this.$options.filters.calendar_provider_name(this.calendar_accounts[index].provider), this.calendar_accounts[index].account_email);
          var success_title = "Ok";
          this.$toast.success(success_msg, success_title, {});

          if (this.connected_calendar_default_calendars_added) {
            setTimeout(function () {
              _this2.show_default_calendars_added_onboard_modal();
            }, 1200);
            this.connected_calendar_default_calendars_added = false;
          } //resetting to avoid duplicate notifications


          this.connect_status_notified = true;
          break;
        }
      }
    },
    update_add_bookings_to_calendar_id: function update_add_bookings_to_calendar_id() {
      var _this3 = this;

      var add_bookings_to_calendar_id = this.tmp_edit_add_bookings_to_calendar_id;

      if (this.tmp_edit_add_bookings_to_calendar_account_id == "no") {
        add_bookings_to_calendar_id = "no";
      }

      var action = "update_add_bookings_to_calendar_id_for_current_admin";
      var wpcal_request = {};
      wpcal_request[action] = {
        add_bookings_to_calendar_id: add_bookings_to_calendar_id
      };
      var post_data = {
        action: "wpcal_process_admin_ajax_request",
        wpcal_request: wpcal_request
      };
      axios_default.a.post(window.wpcal_ajax.ajax_url, post_data).then(function (response) {
        //console.log(response);
        _this3.notify_single_action_result(response.data[action], {
          success_msg: "Settings saved."
        });

        if (response.data && response.data[action].hasOwnProperty("status")) {
          _this3.load_page();
        }
      }).catch(function (error) {
        console.log(error);
      });
      this.$modal.hide("choose_add_bookings_to_calendar_modal");
    },
    update_conflict_calendar_ids: function update_conflict_calendar_ids() {
      var _this4 = this;

      var action = "update_conflict_calendar_ids_for_current_admin";
      var wpcal_request = {};
      wpcal_request[action] = {
        conflict_calendar_ids: this.tmp_edit_conflict_calendar_ids,
        conflict_calendar_ids_length: this.tmp_edit_conflict_calendar_ids.length
      };
      var post_data = {
        action: "wpcal_process_admin_ajax_request",
        wpcal_request: wpcal_request
      };
      axios_default.a.post(window.wpcal_ajax.ajax_url, post_data).then(function (response) {
        //console.log(response);
        _this4.notify_single_action_result(response.data[action], {
          success_msg: _this4.T__("Settings saved.")
        });

        if (response.data && response.data[action].hasOwnProperty("status")) {
          _this4.load_page();

          if (response.data && response.data[action].status === "success") {
            _this4.sync_all_calendar_api();
          }
        }
      }).catch(function (error) {
        console.log(error);
      });
      this.$modal.hide("choose_conflict_calendars_modal");
    },
    disconnect_calendar_by_id: function disconnect_calendar_by_id(calendar_account) {
      var _this5 = this;

      var force = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : false;
      var action = "disconnect_calendar_by_id_for_current_admin";
      var wpcal_request = {};
      wpcal_request[action] = {
        calendar_account_id: calendar_account.id,
        provider: calendar_account.provider,
        force: force === true ? "force_remove" : ""
      };
      var post_data = {
        action: "wpcal_process_admin_ajax_request",
        wpcal_request: wpcal_request
      };
      axios_default.a.post(window.wpcal_ajax.ajax_url, post_data).then(function (response) {
        //console.log(response);
        _this5.notify_single_action_result(response.data[action], {
          success_msg: _this5.T__("Calendar is disconnected successfully.")
        });

        if (!force && response.data[action] && response.data[action].hasOwnProperty("status") && response.data[action].status == "error") {
          setTimeout(function () {
            _this5.show_disconnect_confirmation(calendar_account, true);
          }, 1000);
        }

        _this5.load_page();
      }).catch(function (error) {
        console.log(error);
      });
    },
    show_disconnect_confirmation: function show_disconnect_confirmation(calendar_account) {
      var _this6 = this;

      var force = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : false;
      var dialog_text;
      var yes_button_title;

      if (force) {
        dialog_text = this.Tsprintf(
        /* translators: 1: html-tag 2: html-tag-open 3: html-tag-close 4: account email */
        this.T__("Oops! Error while disconnecting the account. %1$s Do want to %2$sforce-disconnect%3$s the %1$s %2$s %4$s %3$s account?"), "<br>", "<strong>", "</strong>", calendar_account.account_email);
        yes_button_title = this.T__("Yes, force-disconnect.");
      } else {
        dialog_text = this.Tsprintf(
        /* translators: 1: html-tag 2: html-tag-open 3: html-tag-close 4: account email */
        this.T__("Are you sure you want to disconnect the %1$s %2$s %4$s %3$s account?"), "<br>", "<strong>", "</strong>", calendar_account.account_email);
        yes_button_title = this.T__("Yes, disconnect.");
      }

      this.$modal.show("dialog", {
        title: this.T__("Disconnect account?"),
        text: dialog_text,
        buttons: [{
          title: yes_button_title,
          class: "vue-dialog-button dialog-red-btn",
          handler: function handler() {
            _this6.disconnect_calendar_by_id(calendar_account, force);

            _this6.$modal.hide("dialog");
          }
        }, {
          title: this.T__("No, cancel."),
          default: true // Will be triggered by default if 'Enter' pressed.

        }]
      });
    },
    show_choose_add_bookings_to_calendar_modal: function show_choose_add_bookings_to_calendar_modal() {
      //this.hide_default_calendars_added_onboard_modal();
      this.tmp_edit_add_bookings_to_calendar_account_id = this.add_bookings_to_calendar_account_id;
      this.tmp_edit_add_bookings_to_calendar_id = this.add_bookings_to_calendar_id;
      this.can_show_choose_add_bookings_to_calendar_modal = true;
      this.$modal.show("choose_add_bookings_to_calendar_modal");
    },
    show_choose_conflict_calendars_modal: function show_choose_conflict_calendars_modal() {
      //this.hide_default_calendars_added_onboard_modal();
      this.tmp_edit_conflict_calendar_account_id = this.conflict_calendar_account_id;
      this.tmp_edit_conflict_calendar_ids = this.conflict_calendar_ids;
      this.can_show_choose_conflict_calendars_modal = true;
      this.$modal.show("choose_conflict_calendars_modal");
    },
    set_default_conflict_calendar_account_id: function set_default_conflict_calendar_account_id() {
      for (var calendar_account_id in this.calendar_accounts) {
        if (this.conflict_calendar_ids.length == 0) {
          this.conflict_calendar_account_id = calendar_account_id;
          break;
        }

        for (var calendar_id in this.calendar_accounts[calendar_account_id].calendars) {
          if (this.conflict_calendar_ids.indexOf(calendar_id) !== -1) {
            this.conflict_calendar_account_id = calendar_account_id;
            return;
          }
        }
      }
    },
    choose_default_calendar_for_add_bookings_to: function choose_default_calendar_for_add_bookings_to() {
      if (!this.tmp_edit_add_bookings_to_calendar_account_id || this.tmp_edit_add_bookings_to_calendar_account_id == "no") {
        return;
      }

      if (this.tmp_edit_add_bookings_to_calendar_id) {
        //check calendar_id belongs to calendar_account_id
        if (this.is_calendar_id_belongs_to_calendar_account_id(this.tmp_edit_add_bookings_to_calendar_id, this.tmp_edit_add_bookings_to_calendar_account_id)) {
          return;
        }

        this.tmp_edit_add_bookings_to_calendar_id = "";
      }

      if (!this.calendar_accounts[this.tmp_edit_add_bookings_to_calendar_account_id] || !this.calendar_accounts[this.tmp_edit_add_bookings_to_calendar_account_id].calendars) {
        return;
      }

      var first_calendar_id;

      for (var index in this.calendar_accounts[this.tmp_edit_add_bookings_to_calendar_account_id].calendars) {
        var calendar = this.calendar_accounts[this.tmp_edit_add_bookings_to_calendar_account_id].calendars[index];

        if (calendar.is_writable == "1") {
          first_calendar_id = calendar.id;
        }

        if (calendar.is_primary == "1") {
          this.tmp_edit_add_bookings_to_calendar_id = calendar.id;
          return;
        }
      }

      if (first_calendar_id) {
        this.tmp_edit_add_bookings_to_calendar_id = first_calendar_id;
        return;
      }

      return;
    },
    is_calendar_id_belongs_to_calendar_account_id: function is_calendar_id_belongs_to_calendar_account_id(calendar_id, calendar_account_id) {
      var _this$calendar_accoun;

      var is_exists = (_this$calendar_accoun = this.calendar_accounts[calendar_account_id]) === null || _this$calendar_accoun === void 0 ? void 0 : _this$calendar_accoun.calendars.hasOwnProperty(calendar_id);
      return is_exists ? true : false;
    },
    sync_all_calendar_api: function sync_all_calendar_api() {
      var _this7 = this;

      //current admin only
      //postpone manage_background_task
      this.$store.dispatch("manage_background_task", {
        initial_time_out: 30000 //30 secs

      });
      var action = "sync_all_calendar_api_for_current_admin";
      var wpcal_request = {};
      wpcal_request[action] = "dummy__";
      var post_data = {
        action: "wpcal_process_admin_ajax_request",
        wpcal_request: wpcal_request
      };
      axios_default.a.post(window.wpcal_ajax.ajax_url, post_data, {
        params: {
          _remove_options_before_call: {
            background_call: true
          }
        }
      }).then(function (response) {
        _this7.load_page();
      }).catch(function (error) {
        console.log(error);
      });
    },
    show_default_calendars_added_onboard_modal: function show_default_calendars_added_onboard_modal() {
      var _this8 = this;

      this.can_close_default_calendars_added_onboard = false;
      this.show_default_calendars_added_onboard = true;
      this.$nextTick(function () {
        var element = document.querySelector("#calendar_cofiguration_cont");
        var tooltip = document.querySelector("#calendar_cofiguration_cont_tip");
        _this8.default_calendars_added_onboard_popper_instance = Object(lib_popper["a" /* createPopper */])(element, tooltip, {
          placement: "left",
          modifiers: [{
            name: "offset",
            options: {
              offset: [0, 5]
            }
          }]
        });

        _this8.$modal.show("default_calendars_added_onboard_overlay");
      });
    },
    hide_default_calendars_added_onboard_modal: function hide_default_calendars_added_onboard_modal() {
      var hide_modal = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : true;
      this.show_default_calendars_added_onboard = false;

      if (this.default_calendars_added_onboard_popper_instance) {
        this.default_calendars_added_onboard_popper_instance.destroy();
        this.default_calendars_added_onboard_popper_instance = null;
      }

      if (hide_modal) {
        this.$modal.hide("default_calendars_added_onboard_overlay");
      }
    },
    before_close_default_calendars_added_onboard_modal: function before_close_default_calendars_added_onboard_modal(event) {
      if (!this.can_close_default_calendars_added_onboard) {
        event.stop();
        return;
      }

      this.hide_default_calendars_added_onboard_modal(false); //false it already before close function it will be closed
    }
  },
  mounted: function mounted() {
    var _this9 = this;

    this.load_page();
    setTimeout(function () {
      _this9.sync_all_calendar_api(); //Improve later - might create freq calls - should be rare admins visiting this page

    }, 1000);
  }
});
// CONCATENATED MODULE: ./src/views/admin/SettingCalendars.vue?vue&type=script&lang=js&
 /* harmony default export */ var admin_SettingCalendarsvue_type_script_lang_js_ = (SettingCalendarsvue_type_script_lang_js_); 
// EXTERNAL MODULE: ./src/assets/css/admin_settings.css?vue&type=style&index=0&lang=css&
var admin_settingsvue_type_style_index_0_lang_css_ = __webpack_require__("5536");

// EXTERNAL MODULE: ./src/views/admin/SettingCalendars.vue?vue&type=style&index=1&lang=css&
var SettingCalendarsvue_type_style_index_1_lang_css_ = __webpack_require__("fbbe");

// CONCATENATED MODULE: ./src/views/admin/SettingCalendars.vue







/* normalize component */

var SettingCalendars_component = Object(componentNormalizer["a" /* default */])(
  admin_SettingCalendarsvue_type_script_lang_js_,
  SettingCalendarsvue_type_template_id_a0542a7c_render,
  SettingCalendarsvue_type_template_id_a0542a7c_staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* harmony default export */ var SettingCalendars = (SettingCalendars_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"1bf56cee-vue-loader-template"}!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/vue-loader/lib??vue-loader-options!./src/views/admin/SettingIntegrations.vue?vue&type=template&id=12f43de3&
var SettingIntegrationsvue_type_template_id_12f43de3_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',[_c('div',[_c('div',{staticClass:"page-title-cont"},[_c('h1',{staticClass:"wp-heading-inline"},[_vm._v(" WPCal.io - "+_vm._s(_vm.T__("Integration Settings"))+" ")]),_c('AdminHeaderRight')],1),_c('SettingsMenu'),_c('OnBoardingCheckList',{attrs:{"display_in":"settings_integrations"}}),_c('div',{staticClass:"col-cont settings-cont"},[_c('div',{staticClass:"col"},[_c('div',{staticClass:"form"},[_c('div',{staticClass:"wpc-form-row"},[_c('label',[_vm._v(_vm._s(_vm.T__("My web conferencing apps"))+" "),_c('a',{staticClass:"icon-q-mark",attrs:{"href":"https://help.wpcal.io/en/article/how-to-connect-use-meeting-apps-bwour0/?utm_source=wpcal_plugin&utm_medium=contextual","target":"_blank"}})]),(_vm.tp_accounts.length > 0)?_c('span',{staticClass:"txt-help",staticStyle:{"margin-bottom":"10px"}},[_vm._v(_vm._s(_vm.T__( "After connecting your web conferencing app account below, you should edit your existing event types or create a new one and add the app as a location to use it for your bookings." )))]):_vm._e(),_c('div',{staticClass:"mbox loc-integration-accs"},[_c('div',{staticClass:"set-locations-cont"},[_c('div',{staticClass:"set-locations-single location_googlemeet_meeting"},[_c('div',{staticClass:"txt-h5",class:{
                      loading:
                        _vm.googlemeet_meeting_is_account_connected === null
                    }},[_c('div',{staticClass:"app-name"},[_vm._v(" "+_vm._s(_vm._f("tp_provider_name")("googlemeet_meeting"))+" "),_c('a',{staticClass:"icon-q-mark",attrs:{"href":"https://help.wpcal.io/en/article/how-do-i-connect-my-google-meet-with-wpcalio-hrh77h/?utm_source=wpcal_plugin&utm_medium=contextual","target":"_blank"}})]),(_vm.googlemeet_meeting_is_account_connected != null)?_c('span',{staticClass:"status",class:{
                        enabled: _vm.googlemeet_meeting_is_account_connected,
                        disabled: !_vm.googlemeet_meeting_is_account_connected
                      }},[_vm._v(_vm._s(_vm.googlemeet_meeting_is_account_connected ? _vm.T__("Connected") : _vm.T__("Disconnected")))]):_vm._e()]),_c('div',{staticClass:"txt-help",staticStyle:{"margin-bottom":"5px"}},[_vm._v(" "+_vm._s(_vm.googlemeet_meeting_account_email)+" ")]),_c('div',{staticClass:"txt-help"},[(_vm.googlemeet_meeting_is_account_connected === true)?_c('span',[_vm._v(" "+_vm._s(_vm.T__( "As long as a Google Calendar account is connected, this cannot be disconnected. If you do not want to use Google Meet for your bookings, you can simply not add it as a location in your event types." ))+" ")]):_vm._e(),(_vm.googlemeet_meeting_is_account_connected === false)?_c('span',{domProps:{"innerHTML":_vm._s(
                        _vm.Tsprintf(
                          /* translators: 1: html-tag-open 2: html-tag-close */
                          _vm.T__(
                            'This will be connected when you connect a calendar account (%1$s Settings › Calendars %2$s), and set a "Add Bookings to..." calendar. %3$s'
                          ),
                          '<a href="admin.php?page=wpcal_admin#/settings/calendars">',
                          '</a>',
                          '<a href="https://help.wpcal.io/en/article/how-do-i-connect-my-google-meet-with-wpcalio-hrh77h/?utm_source=wpcal_plugin&utm_medium=contextual">Learn more</a>'
                        )
                      )}}):_vm._e()])]),_vm._l((_vm.tp_accounts),function(tp_account,index){
                      var _obj;
return _c('div',{key:index,staticClass:"set-locations-single",class:( _obj = {}, _obj['location_' + tp_account.provider] = true, _obj['del-alert'] =  _vm.show_delete_highlight(
                      'disconnect_highlight_tp_account_index',
                      index
                    ), _obj )},[_c('div',{staticClass:"txt-h5"},[_c('div',{staticClass:"app-name"},[_vm._v(" "+_vm._s(_vm._f("tp_provider_name")(tp_account.provider))+" "),(_vm.help_links[tp_account.provider])?_c('a',{staticClass:"icon-q-mark",attrs:{"href":_vm.help_links[tp_account.provider],"target":"_blank"}}):_vm._e()]),_c('div',{staticClass:"account-connection-actions"},[_c('OverflowMenu',{attrs:{"menu_id":tp_account.id},model:{value:(_vm.tp_account_overflow_menu_details),callback:function ($$v) {_vm.tp_account_overflow_menu_details=$$v},expression:"tp_account_overflow_menu_details"}},[(tp_account.status == -5)?_c('a',{staticClass:"menu-item hover-hl",on:{"click":function($event){return _vm.add_tp_account(tp_account.provider, 'reauth')}}},[_vm._v("Reconnect")]):_vm._e(),_c('a',{staticClass:"menu-item del-hl",staticStyle:{"color":"#e84653","font-size":"12px"},on:{"click":function($event){_vm.tp_account_overflow_menu_details.open_menu_id = null;
                            _vm.show_disconnect_confirmation(tp_account);},"mouseover":function($event){return _vm.set_mouseover_on_delete(
                              'disconnect_highlight_tp_account_index',
                              index,
                              true
                            )},"mouseleave":function($event){return _vm.set_mouseover_on_delete(
                              'disconnect_highlight_tp_account_index',
                              index,
                              false
                            )}}},[_vm._v(_vm._s(tp_account.status == "-5" ? _vm.T__("Remove") : _vm.T__("Disconnect")))])]),_c('span',{staticClass:"status ",class:{
                          enabled: tp_account.status == '1',
                          disabled: tp_account.status != '1'
                        }},[_vm._v(_vm._s(tp_account.status == "1" ? _vm.T__("Connected") : _vm.T__("Disconnected")))])],1)]),_c('div',{staticClass:"txt-help"},[_vm._v(" "+_vm._s(tp_account.tp_account_email)+" ")])])})],2),(!_vm.show_add_tp_account_list)?_c('button',{staticClass:"wpc-btn secondary md full-width",attrs:{"disabled":!_vm.enable_add_new_tp_account,"title":!_vm.enable_add_new_tp_account
                    ? this.T__('You have added all available apps.')
                    : ''},on:{"click":function($event){_vm.show_add_tp_account_list = true}}},[_vm._v(" "+_vm._s(_vm.T__("+ Add an App"))+" ")]):_vm._e(),(_vm.show_add_tp_account_list)?_c('div',[_c('div',{staticClass:"txt-h5 caps"},[_vm._v(" "+_vm._s(_vm.T__("Select your web conferencing app"))+" ")]),_c('ul',{staticClass:"selector block compact location"},[_vm._l((_vm.tp_accounts_add_list_final),function(tp_account_add_item,index2){return _c('li',{key:index2},[_c('label',{class:'location_' + tp_account_add_item.provider,on:{"click":function($event){return _vm.add_tp_account(tp_account_add_item.provider)}}},[_c('div',{staticClass:"txt-h5"},[_vm._v(" "+_vm._s(_vm._f("tp_provider_name")(tp_account_add_item.provider))+" "),(_vm.help_links[tp_account_add_item.provider])?_c('a',{staticClass:"icon-q-mark",attrs:{"href":_vm.help_links[tp_account_add_item.provider],"target":"_blank"},on:{"click":function($event){$event.stopPropagation();}}}):_vm._e()]),_c('div',{staticClass:"txt-help",staticStyle:{"padding-top":"0"}},[_vm._v(" "+_vm._s(_vm.T__("Web conferencing"))+" ")])])])}),_vm._m(0)],2)]):_vm._e()])])]),_c('AdminInfoLine',{attrs:{"info_slug":"ownership_settings_integrations"}})],1)])],1)])}
var SettingIntegrationsvue_type_template_id_12f43de3_staticRenderFns = [function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('li',{staticClass:"disabled"},[_c('label',{staticClass:"location_ms_teams_meeting"},[_c('div',{staticClass:"txt-h5"},[_vm._v("Microsoft Teams")]),_c('div',{staticClass:"txt-help",staticStyle:{"padding-top":"0px"}},[_vm._v(" Web conferencing ")]),_c('span',[_vm._v("COMING SOON")])])])}]


// CONCATENATED MODULE: ./src/views/admin/SettingIntegrations.vue?vue&type=template&id=12f43de3&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--13-0!./node_modules/thread-loader/dist/cjs.js!./node_modules/babel-loader/lib!./node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/vue-loader/lib??vue-loader-options!./src/views/admin/SettingIntegrations.vue?vue&type=script&lang=js&

//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//





/* harmony default export */ var SettingIntegrationsvue_type_script_lang_js_ = ({
  mixins: [admin_tp_locations_state_mixin_admin_tp_locations_state_mixin()],
  components: {
    SettingsMenu: SettingsMenu,
    OnBoardingCheckList: OnBoardingCheckList,
    OverflowMenu: OverflowMenu
  },
  props: {
    connect_status: {
      type: String,
      default: null
    },
    connected_tp_account_id: {
      type: String,
      default: null
    }
  },
  data: function data() {
    return {
      document_title: this.T__("Integrations ‹ Settings"),
      show_add_tp_account_list: false,
      tp_accounts: [],
      tp_accounts_add_list: [{
        provider: "zoom_meeting",
        limit: 1,
        count: 0,
        show: true
      }, {
        provider: "gotomeeting_meeting",
        limit: 1,
        count: 0,
        show: true
      }],
      help_links: {
        zoom_meeting: "https://help.wpcal.io/en/category/integrations-zoom-1dfihfm/?utm_source=wpcal_plugin&utm_medium=contextual"
      },
      disconnect_highlight_tp_account_index: null,
      connect_status_notified: false,
      tp_account_overflow_menu_details: {
        open_menu_id: null,
        popper_instance: null
      }
    };
  },
  computed: {
    tp_accounts_add_list_with_stats: function tp_accounts_add_list_with_stats() {
      var _this = this;

      var list = this.clone_deep(this.tp_accounts_add_list);

      var _loop = function _loop(index) {
        var tp_account = _this.tp_accounts[index];
        var list_index = list.findIndex(function (list_item) {
          return list_item.provider === tp_account.provider;
        });

        if (list_index != undefined) {
          list[list_index].count++;

          if (list[list_index].limit <= list[list_index].count) {
            list[list_index].show = false;
          }
        }
      };

      for (var index in this.tp_accounts) {
        _loop(index);
      }

      return list;
    },
    tp_accounts_add_list_final: function tp_accounts_add_list_final() {
      var list = [];

      for (var index in this.tp_accounts_add_list_with_stats) {
        if (this.tp_accounts_add_list_with_stats[index].show) list.push(this.tp_accounts_add_list_with_stats[index]);
      }

      return list;
    },
    enable_add_new_tp_account: function enable_add_new_tp_account() {
      var is_enable = this.tp_accounts_add_list_final.length > 0;
      return is_enable;
    }
  },
  methods: {
    add_tp_account: function add_tp_account(provider) {
      var do_ = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : "add";
      var action = "add_tp_account";

      if (do_ == "reauth") {
        action = "reauth_tp_account";
      }

      if (provider === "zoom_meeting" || provider === "gotomeeting_meeting") {
        window.location = "admin.php?page=wpcal_admin&wpcal_action=" + action + "&provider=" + provider;
      }
    },
    load_page: function load_page() {
      var _this2 = this;

      var action = "get_tp_accounts_of_current_admin";
      var wpcal_request = {};
      wpcal_request[action] = {
        dummy__: ""
      };
      var post_data = {
        action: "wpcal_process_admin_ajax_request",
        wpcal_request: wpcal_request
      };
      axios_default.a.post(window.wpcal_ajax.ajax_url, post_data).then(function (response) {
        Object.assign(_this2.$data, _this2.$options.data.apply(_this2)); //re-initilize data in Vue - this required as we are using this function to after update
        //console.log(response);

        if (response.data && response.data[action].hasOwnProperty("tp_accounts")) {
          _this2.tp_accounts = response.data[action].tp_accounts;
        }

        _this2.handle_connect_result();

        _this2.check_auth_for_tp_accounts();
      }).catch(function (error) {
        console.log(error);
      });
    },
    handle_connect_result: function handle_connect_result() {
      var _this3 = this;

      if (!this.connect_status || !this.connected_tp_account_id) {
        return;
      }

      if (this.connect_status_notified) {
        return;
      }

      if (this.connect_status === "connected" && this.tp_accounts.length) {
        for (var index in this.tp_accounts) {
          if (this.tp_accounts[index].id != this.connected_tp_account_id) {
            continue;
          }

          var success_msg = this.Tsprintf(
          /* translators: 1: html-tag-open 2: html-tag-close 3: tp provider 4: account email */
          this.T__("Your %1$s %3$s %2$s account %1$s %4$s %2$s is succesfully connected."), "<strong>", "</strong>", this.$options.filters.tp_provider_name(this.tp_accounts[index].provider), this.tp_accounts[index].tp_account_email);
          var success_title = "Ok";
          this.$toast.success(success_msg, success_title, {}); //show a popup message

          this.$modal.show("dialog", {
            title: this.T__("App account connected"),
            text: this.Tsprintf(
            /* translators: 1: html-tag-open 2: html-tag-close */
            this.T__("To use a web conferencing app for your bookings, you should edit your existing %1$s event types %2$s or create a new one and add the connected app as a location."), '<a href="#/event-types" style="font-size:14px;">', "</a>"),
            buttons: [{
              title: "Okay",
              default: true,
              // Will be triggered by default if 'Enter' pressed.
              handler: function handler() {
                _this3.$modal.hide("dialog");
              }
            }]
          }); //resetting to avoid duplicate notifications

          this.connect_status_notified = true;
          break;
        }
      }
    },
    show_disconnect_confirmation: function show_disconnect_confirmation(tp_account) {
      var _this4 = this;

      var force = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : false;
      var dialog_text;
      var yes_button_title;

      if (force) {
        dialog_text = this.Tsprintf(
        /* translators: 1: html-tag 2: html-tag-open 3: html-tag-close 4: tp provider 5: account email */
        this.T__("Oops! Error while disconnecting the account. %1$s Do want to %2$sforce-disconnect%3$s the %1$s %2$s %4$s - %5$s %3$s account?"), "<br>", "<strong>", "</strong>", this.$options.filters.tp_provider_name(tp_account.provider), tp_account.tp_account_email); //"Oops! Error while disconnecting the account. <br>Do want to <strong>force-disconnect</strong> the <br>";

        yes_button_title = this.T__("Yes, force-disconnect.");
      } else {
        dialog_text = this.Tsprintf(
        /* translators: 1: html-tag 2: html-tag-open 3: html-tag-close 4: tp provider name 5: account email */
        this.T__("Are you sure you want to disconnect the %1$s %2$s %4$s - %5$s %3$s account?"), "<br>", "<strong>", "</strong>", this.$options.filters.tp_provider_name(tp_account.provider), tp_account.tp_account_email);
        yes_button_title = this.T__("Yes, disconnect.");
      }

      this.$modal.show("dialog", {
        title: this.T__("Disconnect account?"),
        text: dialog_text,
        buttons: [{
          title: yes_button_title,
          class: "vue-dialog-button dialog-red-btn",
          handler: function handler() {
            _this4.disconnect_tp_account_by_id(tp_account, force);

            _this4.$modal.hide("dialog");
          }
        }, {
          title: this.T__("No, don't.")
        }]
      });
    },
    disconnect_tp_account_by_id: function disconnect_tp_account_by_id(tp_account) {
      var _this5 = this;

      var force = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : false;
      var action = "disconnect_tp_account_by_id_for_current_admin";
      var wpcal_request = {};
      wpcal_request[action] = {
        tp_account_id: tp_account.id,
        provider: tp_account.provider,
        force: force === true ? "force_remove" : ""
      };
      var action_get_tp_accounts = "get_tp_accounts_of_current_admin";
      wpcal_request[action_get_tp_accounts] = "dummy__";
      var post_data = {
        action: "wpcal_process_admin_ajax_request",
        wpcal_request: wpcal_request
      };
      axios_default.a.post(window.wpcal_ajax.ajax_url, post_data).then(function (response) {
        //console.log(response);
        _this5.notify_single_action_result(response.data[action], {
          success_msg: _this5.T__("Account is disconnected successfully.")
        });

        if (!force && response.data[action] && response.data[action].hasOwnProperty("status") && response.data[action].status == "error") {
          setTimeout(function () {
            _this5.show_disconnect_confirmation(tp_account, true);
          }, 1000);
        }

        if (response.data && response.data[action_get_tp_accounts].hasOwnProperty("tp_accounts")) {
          _this5.tp_accounts = response.data[action_get_tp_accounts].tp_accounts;
        }
      }).catch(function (error) {
        console.log(error);
      });
    },
    check_auth_for_tp_accounts: function check_auth_for_tp_accounts() {
      var _this6 = this;

      var wpcal_request = {};
      var action_check_auth = "check_auth_if_fails_may_remove_tp_accounts_for_current_admin";
      wpcal_request[action_check_auth] = "dummy__";
      var action_get_tp_accounts = "get_tp_accounts_of_current_admin";
      wpcal_request[action_get_tp_accounts] = "dummy__";
      var post_data = {
        action: "wpcal_process_admin_ajax_request",
        wpcal_request: wpcal_request
      };
      axios_default.a.post(window.wpcal_ajax.ajax_url, post_data, {
        params: {
          _remove_options_before_call: {
            background_call: true
          }
        }
      }).then(function (response) {
        //console.log(response);
        if (response.data[action_check_auth] && response.data[action_check_auth].hasOwnProperty("status") && response.data[action_check_auth].status == "error") {
          _this6.notify_single_action_result(response.data[action_check_auth], {
            success_msg: "Account checked successfully.",
            timeout: false
          });
        }

        if (response.data && response.data[action_get_tp_accounts].hasOwnProperty("tp_accounts")) {
          _this6.tp_accounts = response.data[action_get_tp_accounts].tp_accounts;
        }
      }).catch(function (error) {
        console.log(error);
      });
    },
    show_googlemeet_meeting_know_more_dialog: function show_googlemeet_meeting_know_more_dialog() {
      this.$modal.show("dialog", {
        title: this.T__("Heads up!"),
        text: "Google Hangout/Meet will work only with Google Calendar.<br> ",
        buttons: [{
          title: this.T__("Okay"),
          default: true // Will be triggered by default if 'Enter' pressed.

        }]
      });
    } // sync_all_tp_api() {
    //   //current admin only
    //   //postpone manage_background_task
    //   this.$store.dispatch("manage_background_task", {
    //     initial_time_out: 30000 //30 secs
    //   });
    //   let action = "sync_all_tp_api_for_current_admin";
    //   let wpcal_request = {};
    //   wpcal_request[action] = "dummy__";
    //   let post_data = {
    //     action: "wpcal_process_admin_ajax_request",
    //     wpcal_request: wpcal_request
    //   };
    //   axios
    //     .post(window.wpcal_ajax.ajax_url, post_data, {
    //       params: { _remove_options_before_call: { background_call: true } }
    //     })
    //     .then(response => {
    //       this.load_page();
    //     })
    //     .catch(error => {
    //       console.log(error);
    //     });
    // }

  },
  mounted: function mounted() {
    this.load_page(); // setTimeout(() => {
    //   this.sync_all_tp_api(); //Improve later - might create freq calls - should be rare admins visiting this page
    // }, 1000);
  }
});
// CONCATENATED MODULE: ./src/views/admin/SettingIntegrations.vue?vue&type=script&lang=js&
 /* harmony default export */ var admin_SettingIntegrationsvue_type_script_lang_js_ = (SettingIntegrationsvue_type_script_lang_js_); 
// EXTERNAL MODULE: ./src/views/admin/SettingIntegrations.vue?vue&type=style&index=1&lang=css&
var SettingIntegrationsvue_type_style_index_1_lang_css_ = __webpack_require__("7f09");

// CONCATENATED MODULE: ./src/views/admin/SettingIntegrations.vue







/* normalize component */

var SettingIntegrations_component = Object(componentNormalizer["a" /* default */])(
  admin_SettingIntegrationsvue_type_script_lang_js_,
  SettingIntegrationsvue_type_template_id_12f43de3_render,
  SettingIntegrationsvue_type_template_id_12f43de3_staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* harmony default export */ var SettingIntegrations = (SettingIntegrations_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"1bf56cee-vue-loader-template"}!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/vue-loader/lib??vue-loader-options!./src/views/admin/SettingAdmins.vue?vue&type=template&id=371961b9&scoped=true&
var SettingAdminsvue_type_template_id_371961b9_scoped_true_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',[_c('div',[_c('div',{staticClass:"page-title-cont"},[_c('h1',{staticClass:"wp-heading-inline"},[_vm._v(" WPCal.io - "+_vm._s(_vm.T__("WPCal Admins Settings"))+" ")]),_c('AdminHeaderRight')],1),_c('SettingsMenu'),_c('OnBoardingCheckList',{attrs:{"display_in":"settings_admins"}}),_c('div',{staticClass:"col-cont settings-cont"},[_c('div',{staticClass:"col",staticStyle:{"width":"unset","max-width":"100%","margin-right":"30px"}},[_c('div',{staticStyle:{"font-size":"16px","padding":"5px 0","display":"flex"}},[_vm._v(" "+_vm._s(_vm.T__("WPCal Admins"))+" "),_c('PremiumInfoBadge',{staticStyle:{"margin":"-10px 0px 0px 10px"},attrs:{"highlight_feature":"wpcal_admins"}},[_c('em',[_vm._v("Free: 1 admin user"),_c('br'),_vm._v("Plus: 5 admin users"),_c('br'),_vm._v("Pro: 10 admin users")])])],1),_c('div',{staticClass:"validation_errors_cont"}),_c('table',{staticClass:"mbox settingAdmins"},[_vm._m(0),_vm._l((_vm.admin_users_details),function(admin_detail,index){return _c('tr',{key:index,class:{
              disabled: admin_detail.status == -1,
              'del-alert': _vm.show_delete_highlight(
                'delete_highlight_admin_index',
                index
              )
            }},[_c('td',[_c('a',{staticStyle:{"white-space":"nowrap"},attrs:{"href":'user-edit.php?user_id=' + admin_detail.admin_user_id}},[_vm._v(_vm._s(admin_detail.name))])]),_c('td',[_vm._v(_vm._s(admin_detail.services_count))]),_c('td',[_vm._v(_vm._s(admin_detail.services_active_count))]),_c('td',[_vm._v(_vm._s(admin_detail.bookings_upcoming_count))]),_c('td',[_vm._v(_vm._s(admin_detail.calendar_acccounts_count))]),_c('td',[_vm._v(" "+_vm._s(admin_detail.add_bookings_to_calendar ? "Added" : "-")+" ")]),_c('td',[_vm._v(_vm._s(admin_detail.conflict_calendars_count))]),_c('td',[_vm._v(_vm._s(admin_detail.zoom_meeting ? "Connected" : "-"))]),_c('td',[_vm._v(" "+_vm._s(admin_detail.gotomeeting_meeting ? "Connected" : "-")+" ")]),_c('td',[_c('OverflowMenu',{attrs:{"menu_id":admin_detail.admin_user_id,"popper_position":"right"},model:{value:(_vm.admin_overflow_menu_details),callback:function ($$v) {_vm.admin_overflow_menu_details=$$v},expression:"admin_overflow_menu_details"}},[_c('a',{staticClass:"menu-item del-hl",staticStyle:{"color":"rgb(232, 70, 83)"},on:{"click":function($event){return _vm.may_show_admin_status_change_dialog(-2, admin_detail)},"mouseover":function($event){return _vm.set_mouseover_on_delete(
                      'delete_highlight_admin_index',
                      index,
                      true
                    )},"mouseleave":function($event){return _vm.set_mouseover_on_delete(
                      'delete_highlight_admin_index',
                      index,
                      false
                    )}}},[_vm._v(_vm._s(_vm.T__("Delete this user")))]),_c('div',{staticClass:"menu-item",staticStyle:{"display":"flex !important"}},[_c('span',{staticStyle:{"color":"#7c7d9c !important","font-size":"13px"}},[_vm._v(_vm._s(_vm.T__("On/Off")))]),(
                      admin_detail.status == 1 || admin_detail.status == -1
                    )?_c('OnOffSlider',{staticStyle:{"margin-left":"auto"},attrs:{"name":admin_detail.admin_user_id,"true_value":"1","false_value":"-1"},on:{"prop-slider-value-changed":function($event){return _vm.may_show_admin_status_change_dialog(
                        $event,
                        admin_detail
                      )}},model:{value:(admin_detail.status),callback:function ($$v) {_vm.$set(admin_detail, "status", $$v)},expression:"admin_detail.status"}}):_vm._e()],1)])],1)])}),_c('tr',[_c('td',{attrs:{"colspan":"10"}},[_c('button',{staticClass:"wpc-btn primary sm",staticStyle:{"margin":"10px"},attrs:{"type":"button"},on:{"click":_vm.show_add_admin_model}},[_vm._v(" "+_vm._s(_vm.T__("Add WPCal Admin"))+" ")])])])],2),_c('AdminInfoLine',{attrs:{"info_slugs":['wpcal_admin_feature_access_info']}})],1)])],1),_c('modal',{staticStyle:{"z-index":"9999"},attrs:{"name":"add_admin_model","height":"auto","width":"360px"}},[_c('div',{staticClass:"modal_close_cont"},[_c('button',{on:{"click":function($event){return _vm.$modal.hide('add_admin_model')}}})]),_c('div',{staticClass:"mbox bg-light shadow"},[_c('div',{staticClass:"form"},[_c('div',{staticClass:"wpc-form-row"},[_c('label',{staticClass:"mb10",staticStyle:{"text-align":"center"}},[_vm._v(_vm._s(_vm.T__("Select WP user to add as a WPCal admin")))]),_c('TypeToSelect',{attrs:{"options":_vm.wp_admins_to_add_as_options,"label_field":"user_email","value_field":"ID"},scopedSlots:_vm._u([{key:"selected-option",fn:function(option){return [_c('div',{staticClass:"profile-details"},[(option.profile_picture)?_c('img',{staticStyle:{"border-radius":"50%"},attrs:{"src":option.profile_picture,"width":"30"}}):_vm._e(),_c('div',[_c('div',{staticClass:"txt-h5"},[_vm._v(_vm._s(option.display_name))]),_c('div',{staticClass:"txt-help"},[_vm._v(_vm._s(option.user_email))])])])]}},{key:"option",fn:function(option){return [_c('div',{staticClass:"profile-details"},[(option.profile_picture)?_c('img',{staticStyle:{"border-radius":"50%"},attrs:{"src":option.profile_picture,"width":"30"}}):_vm._e(),_c('div',[_c('div',{staticClass:"txt-h5"},[_vm._v(_vm._s(option.display_name))]),_c('div',{staticClass:"txt-help"},[_vm._v(_vm._s(option.user_email))])])])]}}]),model:{value:(_vm.add_wp_admin_id),callback:function ($$v) {_vm.add_wp_admin_id=$$v},expression:"add_wp_admin_id"}}),_c('div',{staticClass:"txt-help",staticStyle:{"padding-top":"5px"}},[_vm._v(" Contributors and other users with edit_posts capabilities will be listed above. ")]),_c('button',{staticClass:"wpc-btn primary md mt20",attrs:{"type":"button"},on:{"click":_vm.add_wpcal_admin}},[_vm._v(" "+_vm._s(_vm.T__("Add user as WPCal admin"))+" ")])],1)])])])],1)}
var SettingAdminsvue_type_template_id_371961b9_scoped_true_staticRenderFns = [function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('tr',[_c('td',[_vm._v("Name")]),_c('td',[_vm._v(" Total Event Types"),_c('br'),_c('span',{staticStyle:{"font-size":"11px"}},[_vm._v("incl. privately managed")])]),_c('td',[_vm._v("Active"),_c('br'),_vm._v("Event Types")]),_c('td',[_vm._v("Upcoming"),_c('br'),_vm._v("Bookings")]),_c('td',[_vm._v("Calendar"),_c('br'),_vm._v("Accounts")]),_c('td',[_vm._v("'Add bookings to...'"),_c('br'),_vm._v("calendar")]),_c('td',[_vm._v("Conflict"),_c('br'),_vm._v("calendars")]),_c('td',[_vm._v("Zoom")]),_c('td',[_vm._v("GoToMeeting")]),_c('td')])}]


// CONCATENATED MODULE: ./src/views/admin/SettingAdmins.vue?vue&type=template&id=371961b9&scoped=true&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--13-0!./node_modules/thread-loader/dist/cjs.js!./node_modules/babel-loader/lib!./node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/vue-loader/lib??vue-loader-options!./src/views/admin/SettingAdmins.vue?vue&type=script&lang=js&





//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//







/* harmony default export */ var SettingAdminsvue_type_script_lang_js_ = ({
  components: {
    SettingsMenu: SettingsMenu,
    TypeToSelect: TypeToSelect,
    OnOffSlider: OnOffSlider,
    OverflowMenu: OverflowMenu,
    OnBoardingCheckList: OnBoardingCheckList
  },
  data: function data() {
    return {
      document_title: "WPCal Admins ‹ Settings",
      admin_users_details: [],
      wp_admins_to_add: [],
      add_wp_admin_id: null,
      admin_overflow_menu_details: {
        open_menu_id: null,
        popper_instance: null
      },
      delete_highlight_admin_index: null
    };
  },
  computed: {
    wp_admins_to_add_as_options: function wp_admins_to_add_as_options() {
      // let results = [];
      // return results;
      return this.wp_admins_to_add;
    }
  },
  methods: {
    load_page: function load_page() {
      this.get_wpcal_admin_users_details();
    },
    get_wpcal_admin_users_details: function get_wpcal_admin_users_details() {
      var _this = this;

      return Object(asyncToGenerator["a" /* default */])( /*#__PURE__*/regeneratorRuntime.mark(function _callee() {
        var wpcal_request, action, post_data;
        return regeneratorRuntime.wrap(function _callee$(_context) {
          while (1) {
            switch (_context.prev = _context.next) {
              case 0:
                wpcal_request = {};
                action = "get_wpcal_admin_users_details";
                wpcal_request[action] = "dummy__";
                post_data = {
                  action: "wpcal_process_admin_ajax_request",
                  wpcal_request: wpcal_request
                };
                _context.next = 6;
                return axios_default.a.post(window.wpcal_ajax.ajax_url, post_data).then(function (response) {
                  //console.log(response);
                  if (response.data && response.data[action].hasOwnProperty("admin_users_details")) {
                    _this.admin_users_details = response.data[action].admin_users_details;
                  }
                }).catch(function (error) {
                  console.log(error);
                });

              case 6:
              case "end":
                return _context.stop();
            }
          }
        }, _callee);
      }))();
    },
    get_wp_admins_to_add: function get_wp_admins_to_add() {
      var _this2 = this;

      var wpcal_request = {};
      var action = "get_wp_admins_to_add";
      wpcal_request[action] = "dummy__";
      var post_data = {
        action: "wpcal_process_admin_ajax_request",
        wpcal_request: wpcal_request
      };
      axios_default.a.post(window.wpcal_ajax.ajax_url, post_data).then(function (response) {
        //console.log(response);
        if (response.data && response.data[action].hasOwnProperty("wp_admins_to_add")) {
          _this2.wp_admins_to_add = response.data[action].wp_admins_to_add;
        }
      }).catch(function (error) {
        console.log(error);
      });
    },
    add_wpcal_admin: function add_wpcal_admin() {
      var _this3 = this;

      var wpcal_request = {};
      var action_add_wpcal_admin = "add_wpcal_admin";
      wpcal_request[action_add_wpcal_admin] = {
        admin_data: {
          admin_user_id: this.add_wp_admin_id,
          admin_type: "administrator"
        }
      };
      var action_get_wpcal_admins = "get_wpcal_admin_users_details";
      wpcal_request[action_get_wpcal_admins] = "dummy__";
      var post_data = {
        action: "wpcal_process_admin_ajax_request",
        wpcal_request: wpcal_request
      };
      axios_default.a.post(window.wpcal_ajax.ajax_url, post_data).then(function (response) {
        _this3.notify_single_action_result(response.data[action_add_wpcal_admin], {
          success_msg: "Admin Added."
        });

        if (response.data && response.data[action_get_wpcal_admins].hasOwnProperty("admin_users_details")) {
          _this3.admin_users_details = response.data[action_get_wpcal_admins].admin_users_details;

          _this3.event_bus.$emit("may_update_onboarding_checklist_notice", "add_wpcal_admin");
        }
      }).catch(function (error) {
        console.log(error);
      });
      this.$modal.hide("add_admin_model");
    },
    enable_wpcal_admin: function enable_wpcal_admin(admin_user_id) {
      var _this4 = this;

      var wpcal_request = {};
      var action_enable_wpcal_admin = "enable_wpcal_admin";
      wpcal_request[action_enable_wpcal_admin] = {
        admin_user_id: admin_user_id
      };
      var action_get_wpcal_admins = "get_wpcal_admin_users_details";
      wpcal_request[action_get_wpcal_admins] = "dummy__";
      var post_data = {
        action: "wpcal_process_admin_ajax_request",
        wpcal_request: wpcal_request
      };
      axios_default.a.post(window.wpcal_ajax.ajax_url, post_data).then(function (response) {
        _this4.notify_single_action_result(response.data[action_enable_wpcal_admin], {
          success_msg: "Admin enabled."
        });

        if (response.data && response.data[action_get_wpcal_admins].hasOwnProperty("admin_users_details")) {
          _this4.admin_users_details = response.data[action_get_wpcal_admins].admin_users_details;
        }
      }).catch(function (error) {
        console.log(error);
      });
    },
    disable_wpcal_admin: function disable_wpcal_admin(admin_user_id) {
      var _this5 = this;

      var wpcal_request = {};
      var action_disable_wpcal_admin = "disable_wpcal_admin";
      wpcal_request[action_disable_wpcal_admin] = {
        admin_user_id: admin_user_id
      };
      var action_get_wpcal_admins = "get_wpcal_admin_users_details";
      wpcal_request[action_get_wpcal_admins] = "dummy__";
      var post_data = {
        action: "wpcal_process_admin_ajax_request",
        wpcal_request: wpcal_request
      };
      axios_default.a.post(window.wpcal_ajax.ajax_url, post_data).then(function (response) {
        _this5.notify_single_action_result(response.data[action_disable_wpcal_admin], {
          success_msg: "Admin disabled."
        });

        if (response.data && response.data[action_get_wpcal_admins].hasOwnProperty("admin_users_details")) {
          _this5.admin_users_details = response.data[action_get_wpcal_admins].admin_users_details;
        }
      }).catch(function (error) {
        console.log(error);
      });
    },
    delete_wpcal_admin: function delete_wpcal_admin(admin_user_id) {
      var _this6 = this;

      var wpcal_request = {};
      var action_delete_wpcal_admin = "delete_wpcal_admin";
      wpcal_request[action_delete_wpcal_admin] = {
        admin_user_id: admin_user_id
      };
      var action_get_wpcal_admins = "get_wpcal_admin_users_details";
      wpcal_request[action_get_wpcal_admins] = "dummy__";
      var post_data = {
        action: "wpcal_process_admin_ajax_request",
        wpcal_request: wpcal_request
      };
      axios_default.a.post(window.wpcal_ajax.ajax_url, post_data).then(function (response) {
        _this6.notify_single_action_result(response.data[action_delete_wpcal_admin], {
          success_msg: "Admin deleted."
        });

        if (response.data && response.data[action_get_wpcal_admins].hasOwnProperty("admin_users_details")) {
          _this6.admin_users_details = response.data[action_get_wpcal_admins].admin_users_details;
        }
      }).catch(function (error) {
        console.log(error);
      });
    },
    may_show_admin_status_change_dialog: function may_show_admin_status_change_dialog(status, admin_detail) {
      var _this7 = this;

      return Object(asyncToGenerator["a" /* default */])( /*#__PURE__*/regeneratorRuntime.mark(function _callee2() {
        var allowed_status, triggered_admin_user_id, found_admin_details, status_action_str, status_action_str_ing, status_action_str_ed, button_str, detail_str, services_active_count, bookings_upcoming_count, allow_disable_delete, not_allowed_str, final_title, final_modal_str, final_buttons, no_self_disable_delete_str, additional_info, except_calendars_tp_account_added;
        return regeneratorRuntime.wrap(function _callee2$(_context2) {
          while (1) {
            switch (_context2.prev = _context2.next) {
              case 0:
                allowed_status = [1, -1, -2, "1", "-1", "-2"];

                if (!(allowed_status.indexOf(status) == -1)) {
                  _context2.next = 3;
                  break;
                }

                return _context2.abrupt("return");

              case 3:
                if (!(status != 1)) {
                  _context2.next = 11;
                  break;
                }

                //load new data to get new service and bookings stats for the admins
                triggered_admin_user_id = admin_detail.admin_user_id; //find the same admin

                _context2.next = 7;
                return _this7.get_wpcal_admin_users_details();

              case 7:
                //Load new data
                found_admin_details = _this7.admin_users_details.find(function (_admin_details) {
                  return _admin_details.admin_user_id == triggered_admin_user_id;
                });

                if (found_admin_details) {
                  _context2.next = 10;
                  break;
                }

                return _context2.abrupt("return");

              case 10:
                admin_detail = found_admin_details;

              case 11:
                if (status != 1) {
                  _this7.admin_overflow_menu_details.open_menu_id = null;
                }

                if (!(status == 1)) {
                  _context2.next = 15;
                  break;
                }

                _this7.enable_wpcal_admin(admin_detail.admin_user_id);

                return _context2.abrupt("return");

              case 15:
                status_action_str = _this7.T__("enable");
                status_action_str_ing = _this7.T__("enabling");
                status_action_str_ed = _this7.T__("enabled");
                button_str = _this7.T__("Enable");
                detail_str = "";
                services_active_count = admin_detail.services_active_count;
                bookings_upcoming_count = admin_detail.bookings_upcoming_count;
                allow_disable_delete = true;
                not_allowed_str = "";

                if (services_active_count > 0 || bookings_upcoming_count > 0 || admin_detail.admin_user_id == _this7.$store.getters.get_current_admin_details.id) {
                  allow_disable_delete = false;
                }

                if (status == -1) {
                  status_action_str = _this7.T__("disable");
                  status_action_str_ing = _this7.T__("disabling");
                  status_action_str_ed = _this7.T__("disabled");
                  button_str = _this7.T__("Yes, disable admin.");
                } else if (status == -2) {
                  status_action_str = _this7.T__("delete");
                  status_action_str_ing = _this7.T__("deleting");
                  status_action_str_ed = _this7.T__("deleted");
                  button_str = _this7.T__("Yes, delete admin.");
                }

                if (!allow_disable_delete) {
                  if (services_active_count > 0) {
                    not_allowed_str += "<li>" + _this7.T__("Reassign the Event types to another admin or disable the event types.") + "</li>";
                  }

                  if (bookings_upcoming_count > 0) {
                    if (not_allowed_str) {
                      not_allowed_str += "<br>";
                    }

                    not_allowed_str += "<li>" + _this7.T__("Reschedule the bookings with another host (if possible) or cancel the bookings.") + "</li>";
                  }

                  if (admin_detail.admin_user_id == _this7.$store.getters.get_current_admin_details.id) {
                    if (not_allowed_str) {
                      not_allowed_str += "<br>";
                    }

                    not_allowed_str += "<li>" + _this7.T__("Ask another WPCal admin to " + status_action_str + " you.") + "</li>";
                  }

                  if (not_allowed_str) {
                    not_allowed_str = "<br><br>" + _this7.T__("Please do the following before " + status_action_str_ing + " the admin.") + "<br>" + "<ul class='bull'>" + not_allowed_str + "</ul>";
                  }
                }

                final_title = "";
                final_modal_str = "";
                final_buttons = [];
                no_self_disable_delete_str = "";

                if (!allow_disable_delete && admin_detail.admin_user_id == _this7.$store.getters.get_current_admin_details.id) {
                  no_self_disable_delete_str = '<div class="error-box center mb20">You cannot ' + status_action_str + " yourself.</div>";
                }

                final_modal_str += no_self_disable_delete_str + _this7.Tsprintf(
                /* translators: 1: number 2: number */
                _this7.T__("This admin is having %1$s active Event types and %2$s upcoming bookings."), services_active_count, bookings_upcoming_count) + not_allowed_str;

                if (allow_disable_delete) {
                  additional_info = "";
                  except_calendars_tp_account_added = admin_detail.zoom_meeting || admin_detail.gotomeeting_meeting;

                  if (status == -1) {
                    if (admin_detail.calendar_acccounts_count > 0) {
                      additional_info += "<li>" + _this7.T__("Calendars syncing will be paused.") + "</li>";
                    }

                    if (except_calendars_tp_account_added) {
                      additional_info += "<li>" + _this7.T__("All integrations will be deactivated temporarily and activated automatically when you enable this admin.") + "</li>";
                    }
                  } else if (status == -2) {
                    if (admin_detail.calendar_acccounts_count > 0 && except_calendars_tp_account_added) {
                      additional_info += "<li>" + _this7.T__("Calendars and other integrations will be removed.") + "</li>";
                    } else if (admin_detail.calendar_acccounts_count > 0) {
                      additional_info += "<li>" + _this7.T__("Calendar integrations will be removed.") + "</li>";
                    } else if (except_calendars_tp_account_added) {
                      additional_info += "<li>" + _this7.T__("All integrations will be removed.") + "</li>";
                    }
                  }

                  additional_info = additional_info ? "<br><ul class='bull'>" + additional_info + "</ul>" : "";
                  final_modal_str += additional_info;
                  final_buttons[final_buttons.length] = {
                    title: button_str,
                    class: status_action_str == "delete" || status_action_str == "disable" ? "vue-dialog-button dialog-red-btn" : "",
                    handler: function handler() {
                      if (status == -1) {
                        _this7.disable_wpcal_admin(admin_detail.admin_user_id);
                      } else if (status == -2) {
                        _this7.delete_wpcal_admin(admin_detail.admin_user_id);
                      }

                      _this7.$modal.hide("dialog");
                    }
                  };
                  final_title = _this7.Tsprintf(
                  /* translators: 1: turn on off 2: html-tag-open 3: html-tag-open 4: event type name */
                  _this7.T__("%1$s %2$s admin?"), status_action_str == "delete" ? "Delete" : "Disable", admin_detail.name);
                } else {
                  final_title = _this7.Tsprintf(
                  /* translators: 1: turn on off 2: html-tag-open 3: html-tag-open 4: event type name */
                  _this7.T__("%2$s %4$s %3$s admin cannot be %1$s."), status_action_str_ed, "<strong>", "</strong>", admin_detail.name);
                } //add cancel button


                final_buttons[final_buttons.length] = {
                  title: allow_disable_delete ? _this7.T__("No, don't.") : _this7.T__("Okay"),
                  default: true,
                  // Will be triggered by default if 'Enter' pressed.
                  handler: function handler() {
                    _this7.load_page();

                    _this7.$modal.hide("dialog");
                  }
                };

                _this7.$modal.show("dialog", {
                  title: final_title,
                  text: final_modal_str,
                  buttons: final_buttons
                });

              case 36:
              case "end":
                return _context2.stop();
            }
          }
        }, _callee2);
      }))();
    },
    show_add_admin_model: function show_add_admin_model() {
      this.get_wp_admins_to_add();
      this.add_wp_admin_id = null;
      this.$modal.show("add_admin_model");
    }
  },
  mounted: function mounted() {
    this.load_page();
  }
});
// CONCATENATED MODULE: ./src/views/admin/SettingAdmins.vue?vue&type=script&lang=js&
 /* harmony default export */ var admin_SettingAdminsvue_type_script_lang_js_ = (SettingAdminsvue_type_script_lang_js_); 
// EXTERNAL MODULE: ./src/views/admin/SettingAdmins.vue?vue&type=style&index=0&id=371961b9&scoped=true&lang=css&
var SettingAdminsvue_type_style_index_0_id_371961b9_scoped_true_lang_css_ = __webpack_require__("c2c9");

// EXTERNAL MODULE: ./src/views/admin/SettingAdmins.vue?vue&type=style&index=1&lang=css&
var SettingAdminsvue_type_style_index_1_lang_css_ = __webpack_require__("a1e2");

// CONCATENATED MODULE: ./src/views/admin/SettingAdmins.vue







/* normalize component */

var SettingAdmins_component = Object(componentNormalizer["a" /* default */])(
  admin_SettingAdminsvue_type_script_lang_js_,
  SettingAdminsvue_type_template_id_371961b9_scoped_true_render,
  SettingAdminsvue_type_template_id_371961b9_scoped_true_staticRenderFns,
  false,
  null,
  "371961b9",
  null
  
)

/* harmony default export */ var SettingAdmins = (SettingAdmins_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"1bf56cee-vue-loader-template"}!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/vue-loader/lib??vue-loader-options!./src/views/admin/SettingAccount.vue?vue&type=template&id=2165e2db&
var SettingAccountvue_type_template_id_2165e2db_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',[_c('div',[_c('div',{staticClass:"page-title-cont"},[_c('h1',{staticClass:"wp-heading-inline"},[_vm._v("WPCal.io - "+_vm._s(_vm.T__("Plan Settings")))]),_c('AdminHeaderRight')],1),_c('SettingsMenu'),_c('div',{staticClass:"col-cont settings-cont"},[_c('div',{staticClass:"col"},[_c('div',{staticStyle:{"font-size":"16px","padding":"5px 0px"}},[_vm._v(" "+_vm._s(_vm.T__("Your Plan"))+" ")]),(_vm.license_info.email)?_c('div',{staticClass:"mbox"},[_c('table',[_c('tr',[_c('td',{staticClass:"txt-help",staticStyle:{"text-align":"right","vertical-align":"top"}},[_vm._v(" "+_vm._s(_vm.T__("License email:"))+" ")]),_c('td',[_vm._v(" "+_vm._s(_vm.license_info.email)+" "),_c('br'),_c('a',{staticStyle:{"display":"inline-block","margin-bottom":"5px"},on:{"click":_vm.show_change_email_confirmation}},[_vm._v(_vm._s(_vm.T__("Change account")))]),_vm._v(" • "),_c('router-link',{staticStyle:{"display":"inline-block","margin-bottom":"5px"},attrs:{"to":"/settings/account/login"}},[_vm._v(_vm._s(_vm.T__("Re-login")))]),_c('span',{style:({
                    display: this.$store.getters.is_debug ? '' : 'none'
                  })},[_vm._v(" •"),_c('a',{staticStyle:{"display":"inline-block","margin-bottom":"5px"},on:{"click":_vm.force_validate_next_instance_and_dummy_call}},[_vm._v(_vm._s(_vm.T__("__Reset & validate__")))])])],1)]),(_vm.license_info.plan_name)?_c('tr',[_c('td',{staticClass:"txt-help",staticStyle:{"text-align":"right"}},[_vm._v(" "+_vm._s(_vm.T__("Plan:"))+" ")]),_c('td',[_vm._v(" "+_vm._s(_vm.license_info.plan_name)+" ")])]):_vm._e()])]):_vm._e()])])],1)])}
var SettingAccountvue_type_template_id_2165e2db_staticRenderFns = []


// CONCATENATED MODULE: ./src/views/admin/SettingAccount.vue?vue&type=template&id=2165e2db&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--13-0!./node_modules/thread-loader/dist/cjs.js!./node_modules/babel-loader/lib!./node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/vue-loader/lib??vue-loader-options!./src/views/admin/SettingAccount.vue?vue&type=script&lang=js&
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//


/* harmony default export */ var SettingAccountvue_type_script_lang_js_ = ({
  components: {
    SettingsMenu: SettingsMenu
  },
  data: function data() {
    return {
      document_title: this.T__("Plan ‹ Settings")
    };
  },
  computed: {
    license_info: function license_info() {
      return this.$store.getters.get_license_info;
    }
  },
  methods: {
    show_change_email_confirmation: function show_change_email_confirmation() {
      var _this = this;

      var dialog_text = this.T__("This account email is used only for licensing purposes") + ' - <ul class="bull"><li>' + this.T__("Plugin activation and license validation") + "</li><li>" + this.T__("License expiry and renewal alerts") + "</li><li>" + this.T__("Plugin-related support and communication") + "</li></ul>";
      dialog_text += this.Tsprintf(
      /* translators: 1: html-tag-open 2: html-tag-close */
      this.T__("Are you looking to change the new/reschedule/ cancellation booking notification email? %1$s Click here %2$s."), '<a href="https://help.wpcal.io/en/article/how-to-change-booking-notification-email-address-d2k93j/" target="_blank">', "</a>");
      dialog_text += "<br><br><strong>" + this.T__("") + "</strong>";
      this.$modal.show("dialog", {
        title: this.T__("Change the account?"),
        text: dialog_text,
        buttons: [{
          title: this.T__("Yes, I'm sure"),
          handler: function handler() {
            _this.$router.push({
              //if path is set params will be ignored, hence using name here.
              name: "setting_account_login",
              params: {
                attempt_to_change_account: true
              }
            });

            _this.$modal.hide("dialog");
          }
        }, {
          title: this.T__("No, don't."),
          default: true // Will be triggered by default if 'Enter' pressed.

        }]
      });
    },
    force_validate_next_instance_and_dummy_call: function force_validate_next_instance_and_dummy_call() {
      var _this2 = this;

      var wpcal_request = {};
      var action = "force_validate_next_instance";
      wpcal_request[action] = "dummy__";
      var post_data = {
        action: "wpcal_process_admin_ajax_request",
        wpcal_request: wpcal_request
      };
      axios_default.a.post(window.wpcal_ajax.ajax_url, post_data).then(function (response) {}).catch(function (error) {
        console.log(error);
      }).then(function () {
        _this2.dummy_call();
      });
    },
    dummy_call: function dummy_call() {
      var wpcal_request = {};
      var action = "dummy__";
      wpcal_request[action] = "dummy__";
      var post_data = {
        action: "wpcal_process_admin_ajax_request",
        wpcal_request: wpcal_request
      };
      axios_default.a.post(window.wpcal_ajax.ajax_url, post_data).then(function (response) {}).catch(function (error) {
        console.log(error);
      });
    }
  }
});
// CONCATENATED MODULE: ./src/views/admin/SettingAccount.vue?vue&type=script&lang=js&
 /* harmony default export */ var admin_SettingAccountvue_type_script_lang_js_ = (SettingAccountvue_type_script_lang_js_); 
// CONCATENATED MODULE: ./src/views/admin/SettingAccount.vue





/* normalize component */

var SettingAccount_component = Object(componentNormalizer["a" /* default */])(
  admin_SettingAccountvue_type_script_lang_js_,
  SettingAccountvue_type_template_id_2165e2db_render,
  SettingAccountvue_type_template_id_2165e2db_staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* harmony default export */ var SettingAccount = (SettingAccount_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"1bf56cee-vue-loader-template"}!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/vue-loader/lib??vue-loader-options!./src/components/admin/SettingAccountSignupLogin.vue?vue&type=template&id=37f1326d&scoped=true&
var SettingAccountSignupLoginvue_type_template_id_37f1326d_scoped_true_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',[_c('div',[(!_vm.first_time_logged_in)?_c('div',[_c('div',{staticClass:"page-title-cont"},[_c('h1',{staticClass:"wp-heading-inline"},[_vm._v("WPCal.io - "+_vm._s(_vm.T__("Settings")))]),_c('AdminHeaderRight')],1),_c('SettingsMenu')],1):_c('div',[_c('div',{staticClass:"page-title-cont"},[_c('AdminHeaderRight')],1)]),_c('div',{staticClass:"col-cont"},[_c('div',{staticClass:"col",staticStyle:{"margin":"100px auto 0"}},[(_vm.show_form == 'login')?_c('div',[_c('div',{staticStyle:{"font-size":"20px","text-align":"center","padding":"20px"}},[_vm._v(" "+_vm._s(_vm.T__("Login to your WPCal.io account"))+" ")]),_c('div',{staticClass:"validation_errors_cont"}),_c('div',{staticClass:"form"},[(_vm.form_signup_success)?_c('div',{staticStyle:{"border":"1px dashed #81e846","padding":"10px","border-radius":"5px","background-color":"rgba(129, 232, 70, 0.2)","margin-bottom":"10px"},domProps:{"innerHTML":_vm._s(
                _vm.Tsprintf(
                  /* translators: 1: html-tag-open 2: html-tag-close */
                  _vm.T__(
                    'Your account has been created successfully. Please login now using the %1$spassword sent to your email%2$s.'
                  ),
                  '<span style="border-bottom: 1px dashed;">',
                  '</span>'
                )
              )}}):_vm._e(),(_vm.show_login_with_other_account_txt)?_c('div',{staticStyle:{"background-color":"rgb(228, 233, 245)","border":"1px dashed rgb(86, 123, 243)","padding":"10px","border-radius":"5px","margin-bottom":"20px","text-align":"center"}},[_vm._v(" Login below with another account"),_c('br')]):_vm._e(),_c('form-row',{attrs:{"validator":_vm.$v.form_login.email}},[_c('label',{staticStyle:{"text-transform":"uppercase","font-size":"12px"},attrs:{"for":"form_login_email"}},[_vm._v(_vm._s(_vm.T__("Email address")))]),_c('input',{directives:[{name:"model",rawName:"v-model",value:(_vm.form_login.email),expression:"form_login.email"}],ref:"form_login_email",attrs:{"id":"form_login_email","type":"text","name":"email","tabindex":"1"},domProps:{"value":(_vm.form_login.email)},on:{"blur":function($event){return _vm.$v.form_login.email.$touch()},"input":function($event){if($event.target.composing){ return; }_vm.$set(_vm.form_login, "email", $event.target.value)}}})]),_c('form-row',{attrs:{"validator":_vm.$v.form_login.password}},[_c('label',{staticStyle:{"text-transform":"uppercase","font-size":"12px"},attrs:{"for":"form_login_password"}},[_vm._v(" "+_vm._s(_vm.T__("Password"))+" "),_c('em',{staticStyle:{"margin-top":"0"}},[_c('a',{staticStyle:{"text-transform":"capitalize"},attrs:{"href":_vm.$store.getters.get_wpcal_site_urls.lost_pass_url,"target":"_blank"}},[_vm._v(_vm._s(_vm.T__("Forgot password?")))])])]),_c('input',{directives:[{name:"model",rawName:"v-model",value:(_vm.form_login.password),expression:"form_login.password"}],attrs:{"id":"form_login_password","type":"password","tabindex":"2"},domProps:{"value":(_vm.form_login.password)},on:{"blur":function($event){return _vm.$v.form_login.password.$touch()},"input":function($event){if($event.target.composing){ return; }_vm.$set(_vm.form_login, "password", $event.target.value)}}})]),_c('div',{staticClass:"wpc-form-row"},[_c('button',{staticClass:"wpc-btn primary lg",attrs:{"type":"button","tabindex":"3","disabled":!_vm.login_btn_is_enabled},on:{"click":function($event){_vm.form_login_submit();
                  _vm.form_signup_success = false;}}},[_vm._v(" "+_vm._s(_vm.login_btn_txt)+" ")])])],1),_c('div',{staticClass:"wpc-form-row",staticStyle:{"text-align":"center","font-size":"14px"}},[_vm._v(" "+_vm._s(_vm.T__("Don't have an account yet?"))+" "),_c('a',{staticStyle:{"font-size":"14px"},on:{"click":_vm.show_signup_form}},[_vm._v(_vm._s(_vm.T__("Create your account now")))]),_vm._v(". ")])]):_vm._e(),(_vm.show_form == 'signup')?_c('div',[_c('div',{staticStyle:{"font-size":"20px","text-align":"center","padding":"20px"}},[_vm._v(" "+_vm._s(_vm.T__("Create your WPCal.io account"))+" ")]),_c('div',{staticStyle:{"background-color":"#E4E9F5","border":"1px dashed #567bf3","padding":"10px 10px 0px 10px","border-radius":"5px","margin-bottom":"20px"}},[_vm._v(" "+_vm._s(_vm.T__("You need to create an account for -"))+" "),_c('br'),_c('ul',[_c('li',{staticStyle:{"list-style":"disc","margin-left":"20px"}},[_vm._v(" "+_vm._s(_vm.T_x( "timely notification emails to you and your invitees.", "You need to create an account for" ))+" ")]),_c('li',{staticStyle:{"list-style":"disc","margin-left":"20px"}},[_vm._v(" "+_vm._s(_vm.T_x( "1-click connect to apps like Zoom, Google Meet etc.", "You need to create an account for" ))+" ")]),_c('li',{staticStyle:{"list-style":"disc","margin-left":"20px","margin-bottom":"0"}},[_vm._v(" "+_vm._s(_vm.T_x( "syncing calendar event changes to handle conflicts.", "You need to create an account for" ))+" ")])])]),_c('div',{staticClass:"validation_errors_cont"}),_c('div',{staticClass:"form"},[_c('form-row',{attrs:{"validator":_vm.$v.form_signup.email}},[_c('label',{staticStyle:{"text-transform":"uppercase","font-size":"12px","font-family":"'RubikMed'"},attrs:{"for":"form_singup_email"}},[_vm._v(_vm._s(_vm.T__("Enter your email")))]),_c('div',{staticStyle:{"margin-bottom":"5px"}},[_vm._v(" "+_vm._s(_vm.T__( " This email will be used only for licensing purposes. For bookings, your WP admin user email will be used." ))+" ")]),_c('input',{directives:[{name:"model",rawName:"v-model",value:(_vm.form_signup.email),expression:"form_signup.email"}],attrs:{"id":"form_singup_email","type":"text","name":"email"},domProps:{"value":(_vm.form_signup.email)},on:{"blur":function($event){return _vm.$v.form_signup.email.$touch()},"focus":function($event){_vm.signup_password_note_shown_once = true},"input":function($event){if($event.target.composing){ return; }_vm.$set(_vm.form_signup, "email", $event.target.value)}}}),_c('span',{directives:[{name:"show",rawName:"v-show",value:(_vm.show_signup_password_note),expression:"show_signup_password_note"}],staticStyle:{"font-size":"12px","font-style":"italic","padding":"5px","background-color":"#fff200","display":"inline-block","margin-top":"5px"}},[_vm._v(_vm._s(_vm.T__( "A secure password will be sent to this email for you to login next time." )))])]),_c('div',{staticStyle:{"padding-bottom":"10px","text-align":"center","font-size":"11px"},domProps:{"innerHTML":_vm._s(
                _vm.Tsprintf(
                  /* translators: 1: html-tag-open 2: html-tag-close */
                  _vm.T__(
                    'By clicking on the Create my Account now button below, you agree that you have read and accept the %1$sTerms of Use%2$s'
                  ),
                  '<a style="font-size: 11px;" href="https://wpcal.io/terms-of-use/" target="_blank">',
                  '</a>.'
                )
              )}}),_c('div',{staticClass:"wpc-form-row"},[_c('button',{staticClass:"wpc-btn primary lg",attrs:{"type":"button","disabled":!_vm.signup_btn_is_enabled},on:{"click":_vm.form_signup_and_login_submit}},[_vm._v(" "+_vm._s(_vm.signup_btn_txt)+" ")])]),_c('div',{staticClass:"wpc-form-row",staticStyle:{"text-align":"center","font-size":"14px"}},[_vm._v(" "+_vm._s(_vm.T__("Already have an account?"))+" "),_c('a',{staticStyle:{"font-size":"14px"},on:{"click":function($event){return _vm.show_login_form(false)}}},[_vm._v(_vm._s(_vm.T__("Login now")))]),_vm._v(". ")])],1)]):_vm._e()])])]),_c('modal',{staticStyle:{"z-index":"9999"},attrs:{"name":"stronger_together_plan_modal","width":'700px',"height":"auto","scrollable":true,"adaptive":true},on:{"before-close":_vm.stronger_together_plan_modal_before_close}},[_c('div',{staticClass:"covid-consent-cont"},[_c('PricingStrongerTogether'),_c('div',{staticClass:"consent-action"},[_c('div',{staticStyle:{"font-weight":"500"}},[_vm._v("COVID-19 CONSENT")]),_c('label',{staticClass:"consent-agreement"},[_c('input',{directives:[{name:"model",rawName:"v-model",value:(_vm.stronger_together_plan_details.consent),expression:"stronger_together_plan_details.consent"}],attrs:{"type":"checkbox"},domProps:{"checked":Array.isArray(_vm.stronger_together_plan_details.consent)?_vm._i(_vm.stronger_together_plan_details.consent,null)>-1:(_vm.stronger_together_plan_details.consent)},on:{"change":function($event){var $$a=_vm.stronger_together_plan_details.consent,$$el=$event.target,$$c=$$el.checked?(true):(false);if(Array.isArray($$a)){var $$v=null,$$i=_vm._i($$a,$$v);if($$el.checked){$$i<0&&(_vm.$set(_vm.stronger_together_plan_details, "consent", $$a.concat([$$v])))}else{$$i>-1&&(_vm.$set(_vm.stronger_together_plan_details, "consent", $$a.slice(0,$$i).concat($$a.slice($$i+1))))}}else{_vm.$set(_vm.stronger_together_plan_details, "consent", $$c)}}}}),_vm._v("I understand that the "),_c('strong',[_vm._v("Stronger, Together!")]),_vm._v(" plan will be available only during the COVID-19 crisis after which I can either continue using the free features or need to subscribe to a paid plan to continue using premium features. ")]),_c('div',{staticStyle:{"text-align":"center"}},[_vm._v(" We’ll email you at "),_c('strong',[_vm._v(_vm._s(_vm.$store.getters.get_license_info.email))]),_vm._v(", 30 days in advance before "),_c('br'),_vm._v("removing the "),_c('strong',[_vm._v("Stronger, Together!")]),_vm._v(" plan. ")])]),_c('div',{staticStyle:{"text-align":"right","margin-top":"30px"}},[_c('button',{attrs:{"disabled":!_vm.stronger_together_plan_details.consent},on:{"click":_vm.handle_stronger_together_plan_modal_button}},[_vm._v(" I understand, Continue → ")])])],1)]),_c('a',{staticStyle:{"display":"none"},on:{"click":_vm.show_stronger_together_plan_modal}},[_vm._v("___ Lorem Ipsum (Show Stronger, Together!)")])],1)}
var SettingAccountSignupLoginvue_type_template_id_37f1326d_scoped_true_staticRenderFns = []


// CONCATENATED MODULE: ./src/components/admin/SettingAccountSignupLogin.vue?vue&type=template&id=37f1326d&scoped=true&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--13-0!./node_modules/thread-loader/dist/cjs.js!./node_modules/babel-loader/lib!./node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/vue-loader/lib??vue-loader-options!./src/components/admin/SettingAccountSignupLogin.vue?vue&type=script&lang=js&
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//




/* harmony default export */ var SettingAccountSignupLoginvue_type_script_lang_js_ = ({
  components: {
    SettingsMenu: SettingsMenu,
    PricingStrongerTogether: PricingStrongerTogether
  },
  props: {
    attempt_to_change_account: {
      type: Boolean,
      default: false
    }
  },
  data: function data() {
    return {
      document_title: this.T__("Plan ‹ Settings"),
      form_login: {
        email: "",
        password: ""
      },
      form_signup: {
        email: ""
      },
      show_form: "login",
      form_signup_success: false,
      stronger_together_plan_details: {
        consent: false,
        i_understand_clicked: false
      },
      first_time_logged_in: false,
      login_btn_is_enabled: true,
      signup_btn_is_enabled: true,
      signup_password_note_shown_once: false,
      show_login_with_other_account_txt: false
    };
  },
  validations: {
    form_login: {
      email: {
        required: validators["required"],
        email: validators["email"]
      },
      password: {
        required: validators["required"]
      }
    },
    form_signup: {
      email: {
        required: validators["required"],
        email: validators["email"]
      }
    }
  },
  computed: {
    login_btn_txt: function login_btn_txt() {
      return this.login_btn_is_enabled ? this.T__("Login to my account") : this.T__("Logging you in...");
    },
    //
    signup_btn_txt: function signup_btn_txt() {
      return this.signup_btn_is_enabled ? this.T__("Create my account & Login") : this.T__("Creating your account & logging in..."); // return this.signup_btn_is_enabled
      //   ? this.T__("Create my Account now")
      //   : this.T__("Creating your account...");
    },
    show_signup_password_note: function show_signup_password_note() {
      if (this.signup_password_note_shown_once) {
        return true;
      }

      var is_show = this.form_signup.email.length > 0;
      return is_show;
    }
  },
  methods: {
    on_login_success: function on_login_success(response_license_info) {
      if (this.first_time_logged_in === true) {
        this.show_stronger_together_plan_modal();
      } else {
        this.$router.push("/settings/account");
      }

      this.$store.commit("set_license_info", response_license_info);
    },
    form_login_submit: function form_login_submit() {
      var _this = this;

      var do_signup = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : false;
      this.clear_validation_errors_cont();
      this.$v.form_login.$touch();

      if (this.$v.form_login.$anyError) {
        this.scroll_to_first_validation_error();
        return false;
      }

      this.login_btn_is_enabled = false;
      var wpcal_request = {};
      var action_auth_login = "license_auth_login";
      wpcal_request[action_auth_login] = this.form_login;
      var action_license_status = "license_status";
      wpcal_request[action_license_status] = "dummy__";
      var post_data = {
        action: "wpcal_process_admin_ajax_request",
        wpcal_request: wpcal_request
      };
      axios_default.a.post(window.wpcal_ajax.ajax_url, post_data).then(function (response) {
        _this.notify_single_action_result(response.data[action_auth_login], {
          success_msg: "Successfully logged in."
        });

        if (response.data[action_auth_login].status == "success" && response.data[action_license_status].status == "success") {
          var response_license_info = response.data[action_license_status].license_info;

          _this.on_login_success(response_license_info);
        }
      }).catch(function (error) {
        console.log(error);
      }).then(function () {
        _this.login_btn_is_enabled = true;
      });
    },
    form_signup_and_login_submit: function form_signup_and_login_submit() {
      var _this2 = this;

      this.clear_validation_errors_cont();
      this.$v.form_signup.$touch();

      if (this.$v.form_signup.$anyError) {
        this.scroll_to_first_validation_error();
        return false;
      }

      this.signup_btn_is_enabled = false;
      var wpcal_request = {};
      var action_signup = "license_signup_and_login";
      wpcal_request[action_signup] = this.form_signup;
      var action_license_status = "license_status";
      wpcal_request[action_license_status] = "dummy__";
      var post_data = {
        action: "wpcal_process_admin_ajax_request",
        wpcal_request: wpcal_request
      };
      axios_default.a.post(window.wpcal_ajax.ajax_url, post_data).then(function (response) {
        var _response$data$action, _response$data$action2;

        if (((_response$data$action = response.data[action_signup]) === null || _response$data$action === void 0 ? void 0 : _response$data$action.status) === "error") {
          _this2.notify_single_action_result(response.data[action_signup], {
            success_msg: _this2.T__("Settings saved.")
          });
        }

        if (((_response$data$action2 = response.data[action_signup]) === null || _response$data$action2 === void 0 ? void 0 : _response$data$action2.status) === "success") {
          var _response$data$action3, _response$data$action5;

          if (((_response$data$action3 = response.data[action_signup]) === null || _response$data$action3 === void 0 ? void 0 : _response$data$action3.success) === "signedup_and_added") {
            var _response$data$action4;

            _this2.notify_single_action_result(response.data[action_signup], {
              success_msg: _this2.T__("Successfully logged in.")
            });

            var response_license_info = (_response$data$action4 = response.data[action_license_status]) === null || _response$data$action4 === void 0 ? void 0 : _response$data$action4.license_info;

            _this2.on_login_success(response_license_info);
          } else if (((_response$data$action5 = response.data[action_signup]) === null || _response$data$action5 === void 0 ? void 0 : _response$data$action5.success) === "user_added") {
            //signup success but login failed
            _this2.on_signup_success();
          }
        }
      }).catch(function (error) {
        console.log(error);
      }).then(function () {
        _this2.signup_btn_is_enabled = true;
      });
    },
    // form_signup_submit() {
    //   this.clear_validation_errors_cont();
    //   this.$v.form_signup.$touch();
    //   if (this.$v.form_signup.$anyError) {
    //     this.scroll_to_first_validation_error();
    //     return false;
    //   }
    //   this.signup_btn_is_enabled = false;
    //   let wpcal_request = {};
    //   let action_signup = "license_signup";
    //   wpcal_request[action_signup] = this.form_signup;
    //   let post_data = {
    //     action: "wpcal_process_admin_ajax_request",
    //     wpcal_request: wpcal_request
    //   };
    //   axios
    //     .post(window.wpcal_ajax.ajax_url, post_data)
    //     .then(response => {
    //       if (
    //         response.data[action_signup].hasOwnProperty("status") &&
    //         response.data[action_signup].status === "error"
    //       ) {
    //         this.notify_single_action_result(response.data[action_signup], {
    //           success_msg: this.T__("Settings saved.")
    //         });
    //       }
    //       if (
    //         response.data[action_signup].hasOwnProperty("status") &&
    //         response.data[action_signup].status === "success"
    //       ) {
    //         this.on_signup_success();
    //       }
    //     })
    //     .catch(error => {
    //       console.log(error);
    //     })
    //     .then(() => {
    //       this.signup_btn_is_enabled = true;
    //     });
    // },
    on_signup_success: function on_signup_success() {
      this.form_login.email = this.form_signup.email;
      this.show_login_form(true);
    },
    show_stronger_together_plan_modal: function show_stronger_together_plan_modal() {
      this.$modal.show("stronger_together_plan_modal");
    },
    handle_stronger_together_plan_modal_button: function handle_stronger_together_plan_modal_button() {
      this.stronger_together_plan_details.i_understand_clicked = true;
      this.$modal.hide("stronger_together_plan_modal");
      this.$router.push("/event-types");
    },
    stronger_together_plan_modal_before_close: function stronger_together_plan_modal_before_close(event) {
      if (!this.stronger_together_plan_details.i_understand_clicked || !this.stronger_together_plan_details.consent) {
        event.stop();
      }
    },
    check_and_set_first_time_logged_in: function check_and_set_first_time_logged_in() {
      if (this.$store.getters.get_license_info === null) {
        //propably not data not loaded
        return;
      }

      if (this._isEmpty(this.$store.getters.get_license_info)) {
        //initial setup not done, ask admin for login
        this.first_time_logged_in = true;
        this.show_signup_form();
        return;
      }
    },
    show_login_form: function show_login_form() {
      var form_signup_success = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : false;
      this.$v.form_login.$reset();
      this.show_form = "login";
      this.form_signup_success = form_signup_success;
    },
    show_signup_form: function show_signup_form() {
      this.$v.form_signup.$reset();
      this.show_login_with_other_account_txt = false;
      this.signup_password_note_shown_once = false;
      this.show_form = "signup";
      this.form_signup_success = false;
    }
  },
  watch: {
    show_signup_password_note: function show_signup_password_note(is_show) {
      if (is_show) {
        this.signup_password_note_shown_once = true;
      }
    }
  },
  mounted: function mounted() {
    this.check_and_set_first_time_logged_in();

    if (this.form_login.email == "") {
      this.form_login.email = this.$store.getters.get_license_info.email ? this.$store.getters.get_license_info.email : this.$store.getters.get_current_admin_details.email;
    }

    if (this.form_signup.email == "") {
      this.form_signup.email = this.$store.getters.get_license_info.email ? this.$store.getters.get_license_info.email : this.$store.getters.get_current_admin_details.email;
    }

    if (this.attempt_to_change_account) {
      this.form_login.email = "";
      this.form_signup.email = "";
      this.show_login_with_other_account_txt = true;
      this.ref_focus("form_login_email");
    }
  },
  beforeRouteLeave: function beforeRouteLeave(to, from, next) {
    if (this.$store.getters.get_license_info === null) {
      //propably not data not loaded
      next();
      return;
    }

    if (this._isEmpty(this.$store.getters.get_license_info)) {
      //initial setup not done, ask admin for login
      //router.app.$router.push("/settings/account/login");
      next(false);
      return;
    }

    next();
  }
});
// CONCATENATED MODULE: ./src/components/admin/SettingAccountSignupLogin.vue?vue&type=script&lang=js&
 /* harmony default export */ var admin_SettingAccountSignupLoginvue_type_script_lang_js_ = (SettingAccountSignupLoginvue_type_script_lang_js_); 
// EXTERNAL MODULE: ./src/components/admin/SettingAccountSignupLogin.vue?vue&type=style&index=0&id=37f1326d&scoped=true&lang=css&
var SettingAccountSignupLoginvue_type_style_index_0_id_37f1326d_scoped_true_lang_css_ = __webpack_require__("1ee9");

// CONCATENATED MODULE: ./src/components/admin/SettingAccountSignupLogin.vue






/* normalize component */

var SettingAccountSignupLogin_component = Object(componentNormalizer["a" /* default */])(
  admin_SettingAccountSignupLoginvue_type_script_lang_js_,
  SettingAccountSignupLoginvue_type_template_id_37f1326d_scoped_true_render,
  SettingAccountSignupLoginvue_type_template_id_37f1326d_scoped_true_staticRenderFns,
  false,
  null,
  "37f1326d",
  null
  
)

/* harmony default export */ var SettingAccountSignupLogin = (SettingAccountSignupLogin_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"1bf56cee-vue-loader-template"}!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/vue-loader/lib??vue-loader-options!./src/views/admin/MiscNonAdmin.vue?vue&type=template&id=41592834&
var MiscNonAdminvue_type_template_id_41592834_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',[_vm._m(0),_c('div',{staticStyle:{"color":"rgb(232, 70, 83)","border":"1px dashed rgb(232, 70, 83)","padding":"5px 7px","background-color":"#ffecee","display":"inline-block","border-radius":"5px","font-size":"14px"}},[_vm._v(" You do not have access to WPCal. ")]),_c('div',{staticClass:"col-cont settings-cont",staticStyle:{"font-size":"15px"}},[_c('div',{staticClass:"col"},[_vm._m(1),(_vm.content_loaded && _vm.contact_wpcal_admins)?_c('div',[_c('div',[_vm._v(" Please contact one of admin users below and ask them to add you as a WPCal admin. "),_c('table',{staticClass:"mbox settingAdmins",staticStyle:{"padding":"0px","width":"100%","margin-top":"10px","border-collapse":"collapse"}},_vm._l((_vm.contact_wpcal_admins),function(admin_detail,index){return _c('tr',{key:index},[_c('td',{staticStyle:{"border-bottom":"1px solid #b1b6d1","width":"50%","text-align":"right","padding":"10px"}},[_vm._v(" "+_vm._s(admin_detail.wp_name)+" ")]),_c('td',{staticStyle:{"border-bottom":"1px solid #b1b6d1","width":"50%","padding":"10px"}},[_c('a',{staticStyle:{"white-space":"nowrap"},attrs:{"href":'mailto:' + admin_detail.email}},[_vm._v(_vm._s(admin_detail.email)+" ↗")])])])}),0)])]):(
          _vm.content_loaded &&
            _vm.current_user_can_be_self_added_as_wpcal_admin &&
            _vm.wp_user_id
        )?_c('div',[_vm._m(2),_c('div',{staticStyle:{"margin-top":"10px"}},[_c('button',{staticClass:"wpc-btn primary lg mt20",staticStyle:{"padding":"13px"},attrs:{"disabled":!_vm.self_add_btn_is_enabled},on:{"click":function($event){return _vm.add_current_user_as_wpcal_admin()}}},[_vm._v(" Add myself as WPCal Admin & Start managing ")])])]):_vm._e()])])])}
var MiscNonAdminvue_type_template_id_41592834_staticRenderFns = [function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',{staticClass:"page-title-cont"},[_c('h1',{staticClass:"wp-heading-inline"},[_vm._v("WPCal.io - Please request access")])])},function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('ul',{staticClass:"info-lines"},[_c('li',[_c('div',{staticStyle:{"font-size":"14px"}},[_vm._v(" WPCal Admin features are available to WPCal admins only. ")])])])},function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',{staticStyle:{"line-height":"1.5em","margin-top":"20px"}},[_vm._v(" It looks like either none of the WPCal admins are active now or no one is added as a WPCal admin."),_c('br'),_c('br'),_vm._v(" If you want to manage this plugin, click the button below to add yourself as a WPCal Admin."),_c('br'),_vm._v(" This option is available only to WP administrators. ")])}]


// CONCATENATED MODULE: ./src/views/admin/MiscNonAdmin.vue?vue&type=template&id=41592834&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--13-0!./node_modules/thread-loader/dist/cjs.js!./node_modules/babel-loader/lib!./node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/vue-loader/lib??vue-loader-options!./src/views/admin/MiscNonAdmin.vue?vue&type=script&lang=js&
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

/* harmony default export */ var MiscNonAdminvue_type_script_lang_js_ = ({
  data: function data() {
    return {
      content_loaded: false,
      non_wpcal_admin_details: {},
      self_add_btn_is_enabled: true
    };
  },
  computed: {
    contact_wpcal_admins: function contact_wpcal_admins() {
      var _this$non_wpcal_admin, _this$non_wpcal_admin2;

      return (_this$non_wpcal_admin = this.non_wpcal_admin_details) !== null && _this$non_wpcal_admin !== void 0 && _this$non_wpcal_admin.list_of_admins_to_contact.length ? (_this$non_wpcal_admin2 = this.non_wpcal_admin_details) === null || _this$non_wpcal_admin2 === void 0 ? void 0 : _this$non_wpcal_admin2.list_of_admins_to_contact : false;
    },
    current_user_can_be_self_added_as_wpcal_admin: function current_user_can_be_self_added_as_wpcal_admin() {
      var _this$non_wpcal_admin3;

      return (_this$non_wpcal_admin3 = this.non_wpcal_admin_details) !== null && _this$non_wpcal_admin3 !== void 0 && _this$non_wpcal_admin3.while_no_active_wpcal_admins_current_user_can_be_self_added_as_wpcal_admin ? true : false;
    },
    wp_user_id: function wp_user_id() {
      var _this$non_wpcal_admin4, _this$non_wpcal_admin5;

      return (_this$non_wpcal_admin4 = this.non_wpcal_admin_details) !== null && _this$non_wpcal_admin4 !== void 0 && _this$non_wpcal_admin4.wp_user_id ? (_this$non_wpcal_admin5 = this.non_wpcal_admin_details) === null || _this$non_wpcal_admin5 === void 0 ? void 0 : _this$non_wpcal_admin5.wp_user_id : "";
    }
  },
  methods: {
    get_non_wpcal_admin_details: function get_non_wpcal_admin_details() {
      var _this = this;

      var wpcal_request = {};
      var action_non_wpcal_admin_details = "get_non_wpcal_admin_details_to_show";
      wpcal_request[action_non_wpcal_admin_details] = "dummy__";
      var post_data = {
        action: "wpcal_process_user_ajax_request",
        wpcal_request: wpcal_request
      };
      axios_default.a.post(window.wpcal_ajax.ajax_url, post_data).then(function (response) {
        if (response.data && response.data[action_non_wpcal_admin_details].hasOwnProperty("non_wpcal_admin_details_to_show")) {
          _this.non_wpcal_admin_details = response.data[action_non_wpcal_admin_details].non_wpcal_admin_details_to_show;
          _this.content_loaded = true;
        }
      }).catch(function (error) {
        console.log(error);
      });
    },
    add_current_user_as_wpcal_admin: function add_current_user_as_wpcal_admin() {
      var _this2 = this;

      this.self_add_btn_is_enabled = false;
      var wpcal_request = {};
      var action_add_current_user_as_wpcal_admin = "add_current_user_as_wpcal_admin_while_no_active_wpcal_admins";
      wpcal_request[action_add_current_user_as_wpcal_admin] = {
        wp_user_id: this.wp_user_id
      };
      var post_data = {
        action: "wpcal_process_user_ajax_request",
        wpcal_request: wpcal_request
      };
      axios_default.a.post(window.wpcal_ajax.ajax_url, post_data).then(function (response) {
        var _response$data, _response$data$action;

        _this2.notify_single_action_result(response.data[action_add_current_user_as_wpcal_admin], {
          success_msg: _this2.T__("Successfully added.")
        });

        if (response.data && ((_response$data = response.data) === null || _response$data === void 0 ? void 0 : (_response$data$action = _response$data[action_add_current_user_as_wpcal_admin]) === null || _response$data$action === void 0 ? void 0 : _response$data$action.status) == "success") {
          _this2.content_loaded = false; //hack fix

          window.location.reload();
        }
      }).catch(function (error) {
        console.log(error);
      }).then(function () {
        _this2.self_add_btn_is_enabled = true;
      });
    }
  },
  mounted: function mounted() {
    this.get_non_wpcal_admin_details();

    if (this.$store.getters.get_is_admin_end_access_granted) {
      this.$router.push("/");
    }
  },
  beforeRouteLeave: function beforeRouteLeave(to, from, next) {
    if (!this.$store.getters.get_is_admin_end_access_granted) {
      //initial setup not done, ask admin for login
      //router.app.$router.push("/settings/account/login");
      next(false);
      return;
    }

    next();
  }
});
// CONCATENATED MODULE: ./src/views/admin/MiscNonAdmin.vue?vue&type=script&lang=js&
 /* harmony default export */ var admin_MiscNonAdminvue_type_script_lang_js_ = (MiscNonAdminvue_type_script_lang_js_); 
// CONCATENATED MODULE: ./src/views/admin/MiscNonAdmin.vue





/* normalize component */

var MiscNonAdmin_component = Object(componentNormalizer["a" /* default */])(
  admin_MiscNonAdminvue_type_script_lang_js_,
  MiscNonAdminvue_type_template_id_41592834_render,
  MiscNonAdminvue_type_template_id_41592834_staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* harmony default export */ var MiscNonAdmin = (MiscNonAdmin_component.exports);
// CONCATENATED MODULE: ./src/router/admin.js



















vue_runtime_esm["default"].use(vue_router_esm["a" /* default */]);
var routes = [{
  path: "/",
  //name: "home",
  redirect: "/bookings"
}, // {
//   path: "/about",
//   name: "about",
//   // route level code-splitting
//   // this generates a separate chunk (about.[hash].js) for this route
//   // which is lazy-loaded when the route is visited.
//   component: () => import("../views/About.vue")
// },
{
  path: "/event-types",
  name: "service_list",
  component: ServiceList // component: () =>
  //   import(
  //     /* webpackChunkName: "admin-ServiceList" */ "../views/admin/ServiceList.vue"
  //   )

}, {
  path: "/event-type/add",
  name: "service_add",
  component: ServiceForm // component: () =>
  //   import(
  //     /* webpackChunkName: "admin-ServiceForm" */ "../views/admin/ServiceForm.vue"
  //   )

}, {
  path: "/event-type/edit/:service_id",
  name: "service_edit",
  component: ServiceForm // component: () =>
  //   import(
  //     /* webpackChunkName: "admin-ServiceForm" */ "../views/admin/ServiceForm.vue"
  //   )

}, {
  path: "/bookings",
  name: "booking_list",
  props: true,
  children: [{
    path: "/bookings/custom/:booking_id/",
    name: "booking_custom_by_id"
  }, {
    path: "/bookings/custom/:booking_id/redirected-to-admin-end-booking/",
    redirect: function redirect(to) {
      return {
        name: "booking_custom_by_id",
        // redirecting using "path" instead of "name", then params passing not working.
        // So pass the path param in the below params, so here booking_id for path: "/bookings/custom/:booking_id/" in below params.
        params: {
          booking_id: to.params.booking_id,
          redirected_to_admin_end_booking: true
        }
      };
    }
  }],
  component: ServiceBookings // component: () =>
  //   import(
  //     /* webpackChunkName: "admin-ServiceBookings" */ "../views/admin/ServiceBookings.vue"
  //   )

}, {
  path: "/settings",
  name: "setting_general",
  component: SettingGeneral // component: () =>
  //   import(
  //     /* webpackChunkName: "admin-Settings" */ "../views/admin/SettingGeneral.vue"
  //   )

}, {
  path: "/settings/profile",
  name: "setting_profile",
  component: SettingProfile // component: () =>
  //   import(
  //     /* webpackChunkName: "admin-Settings" */ "../views/admin/SettingProfile.vue"
  //   )

}, {
  path: "/settings/calendars",
  name: "setting_calendars",
  component: SettingCalendars,
  props: true,
  children: [{
    path: "/settings/calendars/connected/:calendar_account_id/",
    redirect: function redirect(to) {
      return {
        name: "setting_calendars",
        params: {
          connect_status: "connected",
          connected_calendar_account_id: to.params.calendar_account_id
        }
      };
    }
  }, {
    path: "/settings/calendars/connected/:calendar_account_id/default_calendars_added",
    redirect: function redirect(to) {
      return {
        name: "setting_calendars",
        params: {
          connect_status: "connected",
          connected_calendar_account_id: to.params.calendar_account_id,
          connected_calendar_default_calendars_added: true
        }
      };
    }
  }] // component: () =>
  //   import(
  //     /* webpackChunkName: "admin-Settings" */ "../views/admin/SettingCalendars.vue"
  //   )

}, {
  path: "/settings/integrations",
  name: "setting_integrations",
  component: SettingIntegrations,
  props: true,
  children: [{
    path: "/settings/integrations/connected/:tp_account_id/",
    redirect: function redirect(to) {
      return {
        name: "setting_integrations",
        params: {
          connect_status: "connected",
          connected_tp_account_id: to.params.tp_account_id
        }
      };
    }
  }] // component: () =>
  //   import(
  //     /* webpackChunkName: "admin-Settings" */ "../views/admin/SettingIntegrations.vue"
  //   )

}, {
  path: "/settings/admins",
  name: "setting_admins",
  component: SettingAdmins // component: () =>
  //   import(
  //     /* webpackChunkName: "admin-Settings" */ "../views/admin/SettingAdmins.vue"
  //   )

}, {
  path: "/settings/account",
  name: "setting_account",
  component: SettingAccount // component: () =>
  //   import(
  //     /* webpackChunkName: "admin-Settings" */ "../views/admin/SettingAccount.vue"
  //   )

}, {
  path: "/settings/account/login",
  name: "setting_account_login",
  component: SettingAccountSignupLogin,
  props: true // component: () =>
  //   import(
  //     /* webpackChunkName: "admin-Settings" */ "../components/admin/SettingAccountSignupLogin.vue"
  //   )

}, {
  path: "/non_wpcal_admin",
  name: "misc_non_wpcal_admin",
  component: MiscNonAdmin
}];
var router = new vue_router_esm["a" /* default */]({
  //mode: "history",
  //base: process.env.BASE_URL,
  routes: routes
});

var admin_hightlight_wp_admin_menu = function hightlight_wp_admin_menu(to) {
  var sub_menu_conts = document.querySelectorAll("#toplevel_page_wpcal_admin .wp-submenu li");
  var main_path = to.path.split("/")[1];
  var hightlight_menu = {
    "event-types": ["event-types", "event-type"],
    bookings: ["bookings"],
    settings: ["settings"]
  };

  if (to.path) {
    var _iterator = Object(createForOfIteratorHelper["a" /* default */])(sub_menu_conts),
        _step;

    try {
      for (_iterator.s(); !(_step = _iterator.n()).done;) {
        var _sub_menu_link$getAtt, _sub_menu_link$getAtt2;

        var sub_menu_cont = _step.value;

        if (sub_menu_cont.classList.contains("wp-submenu-head")) {
          continue;
        }

        sub_menu_cont.classList.remove("current");
        var sub_menu_link = sub_menu_cont.querySelector("a");
        sub_menu_link.classList.remove("current");
        var href_main_path = (_sub_menu_link$getAtt = sub_menu_link.getAttribute("href")) === null || _sub_menu_link$getAtt === void 0 ? void 0 : (_sub_menu_link$getAtt2 = _sub_menu_link$getAtt.split("#")[1]) === null || _sub_menu_link$getAtt2 === void 0 ? void 0 : _sub_menu_link$getAtt2.split("/")[1];

        if (href_main_path && hightlight_menu !== null && hightlight_menu !== void 0 && hightlight_menu[href_main_path] && hightlight_menu[href_main_path].indexOf(main_path) > -1) {
          sub_menu_cont.classList.add("current");
          sub_menu_link.classList.add("current");
        }
      }
    } catch (err) {
      _iterator.e(err);
    } finally {
      _iterator.f();
    }
  }
}; //loading indicator for lazy loading the components in admin end


router.beforeEach(function (to, from, next) {
  var client_end = "admin";
  document.getElementById("wpcal_" + client_end + "_app").classList.add("loading-indicator");
  next();
});
router.afterEach(function (to, from) {
  var update_document_title = function update_document_title() {
    setTimeout( /*#__PURE__*/Object(asyncToGenerator["a" /* default */])( /*#__PURE__*/regeneratorRuntime.mark(function _callee() {
      var _to$matched$, _instances$default;

      var instances, document_title;
      return regeneratorRuntime.wrap(function _callee$(_context) {
        while (1) {
          switch (_context.prev = _context.next) {
            case 0:
              _context.next = 2;
              return (_to$matched$ = to.matched[0]) === null || _to$matched$ === void 0 ? void 0 : _to$matched$.instances;

            case 2:
              instances = _context.sent;
              document_title = instances !== null && instances !== void 0 && (_instances$default = instances.default) !== null && _instances$default !== void 0 && _instances$default.document_title_final ? instances.default.document_title_final : "";
              admin.dispatch("set_document_title", document_title);

            case 5:
            case "end":
              return _context.stop();
          }
        }
      }, _callee);
    })), 50);
  };

  update_document_title();
  admin_hightlight_wp_admin_menu(to);
  var client_end = "admin";
  document.getElementById("wpcal_" + client_end + "_app").classList.remove("loading-indicator");
});
/* harmony default export */ var router_admin = (router);
// EXTERNAL MODULE: ./src/utils/common_mixin.js + 6 modules
var common_mixin = __webpack_require__("399f");

// EXTERNAL MODULE: ./src/utils/filters.js
var filters = __webpack_require__("1619");

// EXTERNAL MODULE: ./src/utils/http.js
var http = __webpack_require__("751a");

// EXTERNAL MODULE: ./node_modules/vue-js-modal/dist/index.js
var dist = __webpack_require__("1881");
var dist_default = /*#__PURE__*/__webpack_require__.n(dist);

// EXTERNAL MODULE: ./node_modules/v-calendar/lib/components/date-picker.umd.js
var date_picker_umd = __webpack_require__("404b");
var date_picker_umd_default = /*#__PURE__*/__webpack_require__.n(date_picker_umd);

// EXTERNAL MODULE: ./node_modules/vuelidate/lib/index.js
var lib = __webpack_require__("1dce");
var lib_default = /*#__PURE__*/__webpack_require__.n(lib);

// EXTERNAL MODULE: ./node_modules/vuelidate-error-extractor/dist/vuelidate-error-extractor.esm.js + 2 modules
var vuelidate_error_extractor_esm = __webpack_require__("7f53");

// EXTERNAL MODULE: ./src/utils/vuelidate_error_extractor_options.js + 5 modules
var vuelidate_error_extractor_options = __webpack_require__("2242");

// EXTERNAL MODULE: ./node_modules/vue-izitoast/dist/vue-izitoast.js
var vue_izitoast = __webpack_require__("e399");
var vue_izitoast_default = /*#__PURE__*/__webpack_require__.n(vue_izitoast);

// EXTERNAL MODULE: ./node_modules/izitoast/dist/css/iziToast.css
var iziToast = __webpack_require__("2068");

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"1bf56cee-vue-loader-template"}!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/vue-loader/lib??vue-loader-options!./src/components/admin/AdminInfoLine.vue?vue&type=template&id=3ccc7b22&
var AdminInfoLinevue_type_template_id_3ccc7b22_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('ul',{staticClass:"info-lines"},_vm._l((_vm.info_slugs_final),function(slug,index){return _c('li',{key:index},[(slug == 'timings_in_timezone' && _vm.timezone)?_c('div',{domProps:{"innerHTML":_vm._s(
        _vm.Tsprintf(
          /* translators: 1: html-tag-open 2: html-tag-close 3: timezone */
          _vm.T__(
            'All timings in this page are in %1$s %3$s %2$s Time Zone as per Event Type settings.'
          ),
          '<span style="font-weight:500">',
          '</span>',
          _vm.timezone
        )
      )}}):(_vm.get_info_by_slug(slug))?_c('div',[_vm._v(" "+_vm._s(_vm.get_info_by_slug(slug))+" ")]):_vm._e()])}),0)}
var AdminInfoLinevue_type_template_id_3ccc7b22_staticRenderFns = []


// CONCATENATED MODULE: ./src/components/admin/AdminInfoLine.vue?vue&type=template&id=3ccc7b22&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--13-0!./node_modules/thread-loader/dist/cjs.js!./node_modules/babel-loader/lib!./node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/vue-loader/lib??vue-loader-options!./src/components/admin/AdminInfoLine.vue?vue&type=script&lang=js&
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
/* harmony default export */ var AdminInfoLinevue_type_script_lang_js_ = ({
  name: "AdminInfoLine",
  props: {
    info_slugs: {
      type: Array
    },
    info_slug: {
      type: String
    },
    timezone: {
      type: String
    }
  },
  data: function data() {
    return {
      admin_info_list: {
        ownership_settings_general: this.T__("These settings are common for and apply to all admins."),
        ownership_settings_profile: this.T__("This is used only for your event types and bookings. Each admin will have their own profile in their login."),
        ownership_settings_calendars: this.T__(" These calendar accounts are visible only to you and used only for your event types and bookings. Each admin will connect and use their own calendar accounts in their login."),
        ownership_settings_integrations: this.T__("These integration accounts are visible only to you and used only for your event types and bookings. Each admin will connect and use their own integration accounts in their login."),
        // ownership_single_event_type: this.T__(
        //   "This event type can be managed only by you."
        // ),
        // ownership_my_bookings: this.T__("Only your bookings are listed here."),
        booking_default_reminder_email: this.T__("A reminder email will be sent to invitees 24hrs before their scheduled event time."),
        conflict_resolution_for_host: this.T__("Time slots booked for an event type will be removed for other event types' availability for the same host to avoid double booking."),
        wpcal_admin_feature_access_info: this.T__("WPCal Admin features are available to WPCal admins only.")
      }
    };
  },
  computed: {
    info_slugs_final: function info_slugs_final() {
      var info_slugs_tmp = [];

      if (Array.isArray(this.info_slugs) && this.info_slugs.length) {
        info_slugs_tmp = this.clone_deep(this.info_slugs);
      } else if (this.info_slug) {
        info_slugs_tmp = [this.info_slug];
      }

      return info_slugs_tmp;
    }
  },
  methods: {
    get_info_by_slug: function get_info_by_slug(slug) {
      if (!this.admin_info_list.hasOwnProperty(slug)) {
        return "";
      }

      return this.admin_info_list[slug];
    }
  }
});
// CONCATENATED MODULE: ./src/components/admin/AdminInfoLine.vue?vue&type=script&lang=js&
 /* harmony default export */ var admin_AdminInfoLinevue_type_script_lang_js_ = (AdminInfoLinevue_type_script_lang_js_); 
// CONCATENATED MODULE: ./src/components/admin/AdminInfoLine.vue





/* normalize component */

var AdminInfoLine_component = Object(componentNormalizer["a" /* default */])(
  admin_AdminInfoLinevue_type_script_lang_js_,
  AdminInfoLinevue_type_template_id_3ccc7b22_render,
  AdminInfoLinevue_type_template_id_3ccc7b22_staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* harmony default export */ var AdminInfoLine = (AdminInfoLine_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"1bf56cee-vue-loader-template"}!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/vue-loader/lib??vue-loader-options!./src/components/admin/PremiumInfoBadge.vue?vue&type=template&id=2abcad30&
var PremiumInfoBadgevue_type_template_id_2abcad30_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',{staticClass:"pro-badge-cont",on:{"click":_vm.show_pricing_compare_modal}},[_c('div',{staticClass:"pro-badge"},[_vm._m(0),_vm._t("default")],2)])}
var PremiumInfoBadgevue_type_template_id_2abcad30_staticRenderFns = [function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('strong',[_vm._v("Premium feature"),_c('i',[_vm._v("Learn more")])])}]


// CONCATENATED MODULE: ./src/components/admin/PremiumInfoBadge.vue?vue&type=template&id=2abcad30&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--13-0!./node_modules/thread-loader/dist/cjs.js!./node_modules/babel-loader/lib!./node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/vue-loader/lib??vue-loader-options!./src/components/admin/PremiumInfoBadge.vue?vue&type=script&lang=js&
//
//
//
//
//
//
//
//
//
/* harmony default export */ var PremiumInfoBadgevue_type_script_lang_js_ = ({
  name: "PremiumInfoBadge",
  props: {
    highlight_feature: {
      type: String
    }
  },
  methods: {
    show_pricing_compare_modal: function show_pricing_compare_modal() {
      this.event_bus.$emit("show-pricing-compare-modal", this.highlight_feature);
    }
  }
});
// CONCATENATED MODULE: ./src/components/admin/PremiumInfoBadge.vue?vue&type=script&lang=js&
 /* harmony default export */ var admin_PremiumInfoBadgevue_type_script_lang_js_ = (PremiumInfoBadgevue_type_script_lang_js_); 
// CONCATENATED MODULE: ./src/components/admin/PremiumInfoBadge.vue





/* normalize component */

var PremiumInfoBadge_component = Object(componentNormalizer["a" /* default */])(
  admin_PremiumInfoBadgevue_type_script_lang_js_,
  PremiumInfoBadgevue_type_template_id_2abcad30_render,
  PremiumInfoBadgevue_type_template_id_2abcad30_staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* harmony default export */ var PremiumInfoBadge = (PremiumInfoBadge_component.exports);
// CONCATENATED MODULE: ./src/admin_main.js




//eslint-disable-next-line
__webpack_require__.p = window.__wpcal_dist_url; // As import files are hoisted "window.__wpcal_client_end = "admin";" was not running, resulted in unexpected results. Now the same code run using import "./utils/client_end_admin.js";










vue_runtime_esm["default"].use(dist_default.a, {
  dialog: true
}); // import VCalendar from "v-calendar";
// Vue.use(VCalendar);


vue_runtime_esm["default"].component("v-date-picker", date_picker_umd_default.a);

vue_runtime_esm["default"].use(lib_default.a);


vue_runtime_esm["default"].use(vuelidate_error_extractor_esm["a" /* default */], vuelidate_error_extractor_options["a" /* default */]);


var defaultVueIziToastOptions = {
  timeout: 5000,
  position: "topRight",
  drag: false
};
vue_runtime_esm["default"].use(vue_izitoast_default.a, defaultVueIziToastOptions);

vue_runtime_esm["default"].component("AdminInfoLine", AdminInfoLine);

vue_runtime_esm["default"].component("PremiumInfoBadge", PremiumInfoBadge);
vue_runtime_esm["default"].config.productionTip = false;

vue_runtime_esm["default"].prototype.__ = common_func["c" /* __ */];
vue_runtime_esm["default"].prototype._x = common_func["g" /* _x */];
vue_runtime_esm["default"].prototype._n = common_func["e" /* _n */];
vue_runtime_esm["default"].prototype._nx = common_func["f" /* _nx */];
vue_runtime_esm["default"].prototype.T__ = common_func["c" /* __ */];
vue_runtime_esm["default"].prototype.T_x = common_func["g" /* _x */];
vue_runtime_esm["default"].prototype.T_n = common_func["e" /* _n */];
vue_runtime_esm["default"].prototype.T_nx = common_func["f" /* _nx */];
vue_runtime_esm["default"].prototype.Tsprintf = common_func["b" /* Tsprintf */];
document.addEventListener("DOMContentLoaded", function () {
  new vue_runtime_esm["default"]({
    router: router_admin,
    store: admin,
    render: function render(h) {
      return h(AdminApp);
    }
  }).$mount("#wpcal_admin_app");
});

/***/ }),

/***/ "adcd":
/***/ (function(module, exports, __webpack_require__) {

// extracted by mini-css-extract-plugin

/***/ }),

/***/ "aef3":
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_node_modules_cache_loader_dist_cjs_js_ref_1_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ServiceBookingFilters_vue_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__("e16e");
/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_node_modules_cache_loader_dist_cjs_js_ref_1_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ServiceBookingFilters_vue_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_node_modules_cache_loader_dist_cjs_js_ref_1_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ServiceBookingFilters_vue_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_0__);
/* unused harmony reexport * */


/***/ }),

/***/ "b812":
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_node_modules_cache_loader_dist_cjs_js_ref_1_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ServiceFormEditView_vue_vue_type_style_index_0_id_5210770c_lang_css_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__("3404");
/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_node_modules_cache_loader_dist_cjs_js_ref_1_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ServiceFormEditView_vue_vue_type_style_index_0_id_5210770c_lang_css_scoped_true___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_node_modules_cache_loader_dist_cjs_js_ref_1_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ServiceFormEditView_vue_vue_type_style_index_0_id_5210770c_lang_css_scoped_true___WEBPACK_IMPORTED_MODULE_0__);
/* unused harmony reexport * */


/***/ }),

/***/ "ba0b":
/***/ (function(module, exports, __webpack_require__) {

// extracted by mini-css-extract-plugin

/***/ }),

/***/ "c2c9":
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_node_modules_cache_loader_dist_cjs_js_ref_1_0_node_modules_vue_loader_lib_index_js_vue_loader_options_SettingAdmins_vue_vue_type_style_index_0_id_371961b9_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__("23d3");
/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_node_modules_cache_loader_dist_cjs_js_ref_1_0_node_modules_vue_loader_lib_index_js_vue_loader_options_SettingAdmins_vue_vue_type_style_index_0_id_371961b9_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_node_modules_cache_loader_dist_cjs_js_ref_1_0_node_modules_vue_loader_lib_index_js_vue_loader_options_SettingAdmins_vue_vue_type_style_index_0_id_371961b9_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__);
/* unused harmony reexport * */


/***/ }),

/***/ "c8ed":
/***/ (function(module, exports, __webpack_require__) {

// extracted by mini-css-extract-plugin

/***/ }),

/***/ "c937":
/***/ (function(module, exports, __webpack_require__) {

// extracted by mini-css-extract-plugin

/***/ }),

/***/ "ce1a":
/***/ (function(module, exports, __webpack_require__) {

// extracted by mini-css-extract-plugin

/***/ }),

/***/ "cf8a":
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_node_modules_cache_loader_dist_cjs_js_ref_1_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Pagination_vue_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__("4543");
/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_node_modules_cache_loader_dist_cjs_js_ref_1_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Pagination_vue_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_node_modules_cache_loader_dist_cjs_js_ref_1_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Pagination_vue_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_0__);
/* unused harmony reexport * */


/***/ }),

/***/ "d4ee":
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_node_modules_cache_loader_dist_cjs_js_ref_1_0_node_modules_vue_loader_lib_index_js_vue_loader_options_SettingGeneral_vue_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__("6806");
/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_node_modules_cache_loader_dist_cjs_js_ref_1_0_node_modules_vue_loader_lib_index_js_vue_loader_options_SettingGeneral_vue_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_node_modules_cache_loader_dist_cjs_js_ref_1_0_node_modules_vue_loader_lib_index_js_vue_loader_options_SettingGeneral_vue_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_0__);
/* unused harmony reexport * */


/***/ }),

/***/ "e129":
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_node_modules_cache_loader_dist_cjs_js_ref_1_0_node_modules_vue_loader_lib_index_js_vue_loader_options_SettingAdminContext_vue_vue_type_style_index_0_id_a268a04c_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__("adcd");
/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_node_modules_cache_loader_dist_cjs_js_ref_1_0_node_modules_vue_loader_lib_index_js_vue_loader_options_SettingAdminContext_vue_vue_type_style_index_0_id_a268a04c_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_node_modules_cache_loader_dist_cjs_js_ref_1_0_node_modules_vue_loader_lib_index_js_vue_loader_options_SettingAdminContext_vue_vue_type_style_index_0_id_a268a04c_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__);
/* unused harmony reexport * */


/***/ }),

/***/ "e16e":
/***/ (function(module, exports, __webpack_require__) {

// extracted by mini-css-extract-plugin

/***/ }),

/***/ "e7fa":
/***/ (function(module, exports, __webpack_require__) {

// extracted by mini-css-extract-plugin

/***/ }),

/***/ "e98d":
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_node_modules_cache_loader_dist_cjs_js_ref_1_0_node_modules_vue_loader_lib_index_js_vue_loader_options_WeekDaysSelector_vue_vue_type_style_index_0_id_48cce514_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__("88a5");
/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_node_modules_cache_loader_dist_cjs_js_ref_1_0_node_modules_vue_loader_lib_index_js_vue_loader_options_WeekDaysSelector_vue_vue_type_style_index_0_id_48cce514_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_node_modules_cache_loader_dist_cjs_js_ref_1_0_node_modules_vue_loader_lib_index_js_vue_loader_options_WeekDaysSelector_vue_vue_type_style_index_0_id_48cce514_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__);
/* unused harmony reexport * */


/***/ }),

/***/ "ede4":
/***/ (function(module, exports, __webpack_require__) {

// extracted by mini-css-extract-plugin

/***/ }),

/***/ "eec2":
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_9_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_9_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_9_oneOf_1_2_node_modules_sass_loader_dist_cjs_js_ref_9_oneOf_1_3_node_modules_cache_loader_dist_cjs_js_ref_1_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AdminApp_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__("3a8c");
/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_9_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_9_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_9_oneOf_1_2_node_modules_sass_loader_dist_cjs_js_ref_9_oneOf_1_3_node_modules_cache_loader_dist_cjs_js_ref_1_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AdminApp_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_mini_css_extract_plugin_dist_loader_js_ref_9_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_9_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_9_oneOf_1_2_node_modules_sass_loader_dist_cjs_js_ref_9_oneOf_1_3_node_modules_cache_loader_dist_cjs_js_ref_1_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AdminApp_vue_vue_type_style_index_0_lang_scss___WEBPACK_IMPORTED_MODULE_0__);
/* unused harmony reexport * */


/***/ }),

/***/ "ef4c":
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_node_modules_cache_loader_dist_cjs_js_ref_1_0_node_modules_vue_loader_lib_index_js_vue_loader_options_OverflowMenu_vue_vue_type_style_index_0_id_3a345ca8_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__("5366");
/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_node_modules_cache_loader_dist_cjs_js_ref_1_0_node_modules_vue_loader_lib_index_js_vue_loader_options_OverflowMenu_vue_vue_type_style_index_0_id_3a345ca8_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_node_modules_cache_loader_dist_cjs_js_ref_1_0_node_modules_vue_loader_lib_index_js_vue_loader_options_OverflowMenu_vue_vue_type_style_index_0_id_3a345ca8_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__);
/* unused harmony reexport * */


/***/ }),

/***/ "f663":
/***/ (function(module, exports, __webpack_require__) {

// extracted by mini-css-extract-plugin

/***/ }),

/***/ "fbbe":
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_node_modules_cache_loader_dist_cjs_js_ref_1_0_node_modules_vue_loader_lib_index_js_vue_loader_options_SettingCalendars_vue_vue_type_style_index_1_lang_css___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__("2cd5");
/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_node_modules_cache_loader_dist_cjs_js_ref_1_0_node_modules_vue_loader_lib_index_js_vue_loader_options_SettingCalendars_vue_vue_type_style_index_1_lang_css___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_node_modules_cache_loader_dist_cjs_js_ref_1_0_node_modules_vue_loader_lib_index_js_vue_loader_options_SettingCalendars_vue_vue_type_style_index_1_lang_css___WEBPACK_IMPORTED_MODULE_0__);
/* unused harmony reexport * */


/***/ }),

/***/ "ff75":
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_node_modules_cache_loader_dist_cjs_js_ref_1_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ServiceFormStep2_vue_vue_type_style_index_1_id_1edd344c_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__("5f56");
/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_node_modules_cache_loader_dist_cjs_js_ref_1_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ServiceFormStep2_vue_vue_type_style_index_1_id_1edd344c_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_node_modules_cache_loader_dist_cjs_js_ref_1_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ServiceFormStep2_vue_vue_type_style_index_1_id_1edd344c_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__);
/* unused harmony reexport * */


/***/ })

/******/ });