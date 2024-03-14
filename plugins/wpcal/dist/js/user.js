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
/******/ 		"user": 0
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
/******/ 	deferredModules.push([2,"chunk-vendors","chunk-common"]);
/******/ 	// run deferred modules when ready
/******/ 	return checkDeferredModules();
/******/ })
/************************************************************************/
/******/ ({

/***/ "0351":
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_node_modules_cache_loader_dist_cjs_js_ref_1_0_node_modules_vue_loader_lib_index_js_vue_loader_options_BookingView_vue_vue_type_style_index_2_id_52347f1a_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__("bf36");
/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_node_modules_cache_loader_dist_cjs_js_ref_1_0_node_modules_vue_loader_lib_index_js_vue_loader_options_BookingView_vue_vue_type_style_index_2_id_52347f1a_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_node_modules_cache_loader_dist_cjs_js_ref_1_0_node_modules_vue_loader_lib_index_js_vue_loader_options_BookingView_vue_vue_type_style_index_2_id_52347f1a_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__);
/* unused harmony reexport * */


/***/ }),

/***/ "093b":
/***/ (function(module, exports, __webpack_require__) {

// extracted by mini-css-extract-plugin

/***/ }),

/***/ "0a1f":
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_user_booking_css_vue_type_style_index_1_lang_css___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__("093b");
/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_user_booking_css_vue_type_style_index_1_lang_css___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_user_booking_css_vue_type_style_index_1_lang_css___WEBPACK_IMPORTED_MODULE_0__);
/* unused harmony reexport * */


/***/ }),

/***/ 2:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__("70e2");


/***/ }),

/***/ "4c7b":
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_wf_stylesheet_css_vue_type_style_index_1_id_1e699641_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__("eb9c");
/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_wf_stylesheet_css_vue_type_style_index_1_id_1e699641_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_wf_stylesheet_css_vue_type_style_index_1_id_1e699641_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__);
/* unused harmony reexport * */


/***/ }),

/***/ "4f2a":
/***/ (function(module, exports, __webpack_require__) {

// extracted by mini-css-extract-plugin

/***/ }),

/***/ "6ed8":
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_node_modules_cache_loader_dist_cjs_js_ref_1_0_node_modules_vue_loader_lib_index_js_vue_loader_options_BookingView_vue_vue_type_style_index_0_id_52347f1a_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__("4f2a");
/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_node_modules_cache_loader_dist_cjs_js_ref_1_0_node_modules_vue_loader_lib_index_js_vue_loader_options_BookingView_vue_vue_type_style_index_0_id_52347f1a_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_node_modules_cache_loader_dist_cjs_js_ref_1_0_node_modules_vue_loader_lib_index_js_vue_loader_options_BookingView_vue_vue_type_style_index_0_id_52347f1a_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__);
/* unused harmony reexport * */


/***/ }),

/***/ "70e2":
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

// CONCATENATED MODULE: ./src/utils/client_end_user.js
window.__wpcal_client_end = "user";
/* harmony default export */ var client_end_user = ({
  __dummy_cliend_end: "user"
});
// EXTERNAL MODULE: ./node_modules/vue/dist/vue.runtime.esm.js
var vue_runtime_esm = __webpack_require__("2b0e");

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"1bf56cee-vue-loader-template"}!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/vue-loader/lib??vue-loader-options!./src/UserApp.vue?vue&type=template&id=1e699641&scoped=true&
var UserAppvue_type_template_id_1e699641_scoped_true_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',{class:[_vm.main_cont_class],style:(_vm.user_app_custom_styles),attrs:{"id":"wpcal_user_app"}},[_c('IconFillSVG'),_c('router-view')],1)}
var staticRenderFns = []


// CONCATENATED MODULE: ./src/UserApp.vue?vue&type=template&id=1e699641&scoped=true&

// EXTERNAL MODULE: ./src/utils/common_func.js
var common_func = __webpack_require__("d1ff");

// EXTERNAL MODULE: ./src/assets/images/IconFillSVG.vue + 2 modules
var IconFillSVG = __webpack_require__("93fb");

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--13-0!./node_modules/thread-loader/dist/cjs.js!./node_modules/babel-loader/lib!./node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/vue-loader/lib??vue-loader-options!./src/UserApp.vue?vue&type=script&lang=js&
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//


/* harmony default export */ var UserAppvue_type_script_lang_js_ = ({
  components: {
    IconFillSVG: IconFillSVG["a" /* default */]
  },
  computed: {
    main_cont_class: function main_cont_class() {
      var app_cont_width = this.$store.getters.get_app_cont_width;

      if (!app_cont_width || app_cont_width < 700) {
        return "narrow";
      } else if (app_cont_width >= 700 && app_cont_width < 1000) {
        return "extended";
      } else if (app_cont_width >= 1000) {
        return "wide";
      }

      return "narrow";
    },
    branding_styles: function branding_styles() {
      var styles = {};
      var general_setting = this.$store.getters.get_general_settings;

      if (general_setting.hasOwnProperty("branding_color") && general_setting.branding_color) {
        styles["--accentClrRGB"] = Object(common_func["m" /* hex_to_rgb_txt */])(general_setting.branding_color);
      }

      return styles;
    },
    user_app_custom_styles: function user_app_custom_styles() {
      var branding_styles = this.branding_styles;
      var css_varaibles = this.get_css_varaibles();
      var styles = Object.assign({}, branding_styles, css_varaibles);
      return styles;
    }
  },
  methods: {
    handle_router_request: function handle_router_request() {
      //console.log("handle_router_request triggered");
      var param_route = Object(common_func["l" /* get_url_param_by_name */])("wpcal_r"); //console.log("param_route", param_route);

      if (!param_route || param_route == "/" || param_route == "/booking") {
        this.$router.push("/booking");
      } else {
        this.$router.push(param_route);
      }
    },
    get_css_varaibles: function get_css_varaibles() {
      var css_variables = {};
      css_variables["--wpcal-content-today"] = "'" + this._x("TODAY", "Date picker today date highlight", "wpcal") + "'";
      return css_variables;
    },
    may_apply_branding_font: function may_apply_branding_font() {
      var _this = this;

      //due to some local scope issue, this methdd works, that why not used in branding_styles()
      setTimeout(function () {
        var general_setting = _this.$store.getters.get_general_settings;

        if (_this._isEmpty(general_setting)) {
          _this.may_apply_branding_font();

          return;
        }

        if (general_setting.hasOwnProperty("branding_font") && general_setting.branding_font) {
          var style_element = document.createElement("style");
          style_element.type = "text/css";
          style_element.innerHTML = ":root{--font-family:" + general_setting.branding_font + "; --font-family-med:" + general_setting.branding_font + "; --font-family-med-weight:bold;}";
          document.body.appendChild(style_element);
        }
      }, 50);
    }
  },
  created: function created() {
    var _this2 = this;

    this.$store.dispatch("init");
    this.handle_router_request();
    window.addEventListener("popstate", function (state) {
      //console.log("popstate triggered");
      _this2.handle_router_request();
    });
    this.may_apply_branding_font();
  }
});
// CONCATENATED MODULE: ./src/UserApp.vue?vue&type=script&lang=js&
 /* harmony default export */ var src_UserAppvue_type_script_lang_js_ = (UserAppvue_type_script_lang_js_); 
// EXTERNAL MODULE: ./src/assets/fonts/wf-stylesheet.css?vue&type=style&index=1&id=1e699641&scoped=true&lang=css&
var wf_stylesheetvue_type_style_index_1_id_1e699641_scoped_true_lang_css_ = __webpack_require__("4c7b");

// EXTERNAL MODULE: ./node_modules/vue-loader/lib/runtime/componentNormalizer.js
var componentNormalizer = __webpack_require__("2877");

// CONCATENATED MODULE: ./src/UserApp.vue






/* normalize component */

var component = Object(componentNormalizer["a" /* default */])(
  src_UserAppvue_type_script_lang_js_,
  UserAppvue_type_template_id_1e699641_scoped_true_render,
  staticRenderFns,
  false,
  null,
  "1e699641",
  null
  
)

/* harmony default export */ var UserApp = (component.exports);
// EXTERNAL MODULE: ./node_modules/core-js/modules/es.object.to-string.js
var es_object_to_string = __webpack_require__("d3b7");

// EXTERNAL MODULE: ./node_modules/core-js/modules/es.string.iterator.js
var es_string_iterator = __webpack_require__("3ca3");

// EXTERNAL MODULE: ./node_modules/core-js/modules/web.dom-collections.iterator.js
var web_dom_collections_iterator = __webpack_require__("ddb0");

// EXTERNAL MODULE: ./node_modules/core-js/modules/web.url.js
var web_url = __webpack_require__("2b3d");

// EXTERNAL MODULE: ./node_modules/core-js/modules/web.url-search-params.js
var web_url_search_params = __webpack_require__("9861");

// EXTERNAL MODULE: ./node_modules/core-js/modules/es.regexp.to-string.js
var es_regexp_to_string = __webpack_require__("25f0");

// EXTERNAL MODULE: ./node_modules/vue-router/dist/vue-router.esm.js
var vue_router_esm = __webpack_require__("8c4f");

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"1bf56cee-vue-loader-template"}!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/vue-loader/lib??vue-loader-options!./src/views/user/UserHome.vue?vue&type=template&id=2917a209&
var UserHomevue_type_template_id_2917a209_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div')}
var UserHomevue_type_template_id_2917a209_staticRenderFns = []


// CONCATENATED MODULE: ./src/views/user/UserHome.vue?vue&type=template&id=2917a209&

// EXTERNAL MODULE: ./node_modules/core-js/modules/es.function.name.js
var es_function_name = __webpack_require__("b0c0");

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--13-0!./node_modules/thread-loader/dist/cjs.js!./node_modules/babel-loader/lib!./node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/vue-loader/lib??vue-loader-options!./src/views/user/UserHome.vue?vue&type=script&lang=js&

//
//
//
//
/* harmony default export */ var UserHomevue_type_script_lang_js_ = ({
  methods: {
    check_and_route: function check_and_route() {
      if (this.$route.name == "user_home") {
        if (typeof wpcal_booking_service_id !== "undefined" && wpcal_booking_service_id //eslint-disable-line
        ) {
          this.$router.push("booking");
        }
      }
    }
  },
  mounted: function mounted() {
    this.check_and_route();
  }
});
// CONCATENATED MODULE: ./src/views/user/UserHome.vue?vue&type=script&lang=js&
 /* harmony default export */ var user_UserHomevue_type_script_lang_js_ = (UserHomevue_type_script_lang_js_); 
// CONCATENATED MODULE: ./src/views/user/UserHome.vue





/* normalize component */

var UserHome_component = Object(componentNormalizer["a" /* default */])(
  user_UserHomevue_type_script_lang_js_,
  UserHomevue_type_template_id_2917a209_render,
  UserHomevue_type_template_id_2917a209_staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* harmony default export */ var UserHome = (UserHome_component.exports);
// EXTERNAL MODULE: ./src/views/user/Booking.vue + 17 modules
var Booking = __webpack_require__("eb8b");

// EXTERNAL MODULE: ./src/components/user/BookingStep2.vue + 4 modules
var BookingStep2 = __webpack_require__("e0f2");

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"1bf56cee-vue-loader-template"}!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/vue-loader/lib??vue-loader-options!./src/views/user/BookingView.vue?vue&type=template&id=52347f1a&scoped=true&
var BookingViewvue_type_template_id_52347f1a_scoped_true_render = function () {
var _obj;
var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',[_c('div',{attrs:{"id":"wpcal-widget"}},[_c('div',{staticClass:"widget-main state-confirmation cf"},[_c('div',{staticClass:"event-preset"},[_c('img',{attrs:{"src":_vm.service_admin_details.profile_picture}}),_c('div',{staticClass:"inviter-name"},[_vm._v(" "+_vm._s(_vm.service_admin_details.display_name)+" ")]),_c('div',{staticClass:"confimation-confirmed"},[_vm._v(" "+_vm._s(_vm.__("Hello", "wpcal"))+" "+_vm._s(_vm._f("wpcal_get_firstname")(_vm.booking_details.invitee_name))+","),_c('br'),_vm._v(" "+_vm._s(_vm.booking_status_txt)+" ")]),_c('div',{staticClass:"event-type-name",class:( _obj = {}, _obj['clr-' + _vm.service_details.color] = true, _obj )},[_vm._v(" "+_vm._s(_vm.service_details.name)+" ")]),(_vm.booking_details.status == '-5' && _vm.redirect_text)?_c('div',[_vm._v(" "+_vm._s(_vm.redirect_text)+" ")]):_vm._e(),(_vm.booking_details.status == '1')?_c('div',[_c('div',{staticClass:"event-type-duration",domProps:{"innerHTML":_vm._s(
              _vm.$options.filters.wpcal_unix_to_from_to_time_full_date_day_tz(
                _vm.booking_details.booking_from_time,
                _vm.booking_details.booking_to_time,
                _vm.tz,
                _vm.time_format
              ) + '.'
            )}}),_c('LocationAdvanced',{attrs:{"type":"display_user_after_booking_location","location_details":_vm.booking_details.location,"service_admin_details":_vm.service_admin_details}})],1):_vm._e(),(
            (_vm.$route.name == 'booking_view_confirmed' ||
              _vm.$route.name == 'booking_view_rescheduled') &&
              _vm.booking_details.status == 1 &&
              _vm.booking_details.is_invitee_notify_by_email &&
              _vm.booking_details.is_invitee_notify_by_calendar
          )?_c('div',{staticClass:"txt-h4 confimation-via"},[_c('span',{domProps:{"innerHTML":_vm._s(
              _vm.Tsprintf(
                /* translators: %s: html-tag */
                _vm.__(
                  'A booking confimation has been sent to %s your email address -',
                  'wpcal'
                ),
                '<br />'
              )
            )}}),_c('span',{staticStyle:{"font-weight":"500"}},[_vm._v(_vm._s(/* following spacing required */ " " + _vm.booking_details.invitee_email))])]):(
            (_vm.$route.name == 'booking_view_confirmed' ||
              _vm.$route.name == 'booking_view_rescheduled') &&
              _vm.booking_details.status == 1 &&
              _vm.booking_details.is_invitee_notify_by_calendar &&
              !_vm.booking_details.is_invitee_notify_by_email
          )?_c('div',{staticClass:"txt-h4 confimation-via"},[_c('span',{domProps:{"innerHTML":_vm._s(
              _vm.Tsprintf(
                /* translators: %s: html-tag */
                _vm.__(
                  'A calendar invitation has been sent to %s your email address -',
                  'wpcal'
                ),
                '<br />'
              )
            )}}),_c('span',{staticStyle:{"font-weight":"500"}},[_vm._v(_vm._s(/* following spacing required */ " " + _vm.booking_details.invitee_email))])]):(
            _vm.booking_details.status == 1 &&
              !_vm.booking_details.is_invitee_notify_by_calendar
          )?_c('div',{staticClass:"txt-h4 confimation-via"},[_c('a',{staticStyle:{"color":"#567bf3","text-decoration":"underline"},attrs:{"href":_vm.booking_details.add_event_to_google_calendar_url,"target":"_blank"}},[_vm._v(_vm._s(_vm.__("Add to", "wpcal"))+" Google Calendar")]),_vm._v("→ "),_c('a',{staticStyle:{"color":"#567bf3","text-decoration":"underline"},attrs:{"href":_vm.booking_details.download_ics_url,"target":"_blank"}},[_vm._v(_vm._s(_vm.__("Add to", "wpcal"))+" iCal/Outlook ")]),_vm._v("→ ")]):_vm._e(),(
            (_vm.$route.name == 'booking_view' ||
              _vm.$route.name == 'booking_view_cancel') &&
              !_vm.is_booking_slot_time_passed
          )?_c('div',{staticStyle:{"margin-top":"20px"}},[(_vm.can_show_rechedule_option)?_c('a',{staticClass:"btn",staticStyle:{"text-decoration":"underline"},attrs:{"href":'?wpcal_r=' +
                encodeURIComponent(
                  '/booking/reschedule/' + _vm.booking_unique_link
                )},on:{"click":function($event){return _vm.on_click_if_same_page_target_router_push(
                $event,
                '/booking/reschedule/' + _vm.booking_unique_link
              )}}},[_vm._v(_vm._s(_vm.__("Reschedule", "wpcal")))]):_vm._e(),(_vm.can_show_cancel_option)?_c('a',{staticClass:"btn cancel pointer",staticStyle:{"text-decoration":"underline"},on:{"click":function($event){return _vm.show_cancel_confirmation(_vm.booking_details)}}},[_vm._v(" "+_vm._s(_vm.__("Cancel", "wpcal"))+" ")]):_vm._e()]):_vm._e(),(!_vm._isEmpty(_vm.booking_details.invitee_question_answers))?_c('div',[_c('div',[(!_vm.show_question_answers)?_c('a',{staticClass:"btn pointer",staticStyle:{"text-decoration":"underline"},on:{"click":function($event){_vm.show_question_answers = true}}},[_vm._v(_vm._s(_vm.__("Show submitted form", "wpcal")))]):_vm._e(),(_vm.show_question_answers)?_c('a',{staticClass:"btn pointer",staticStyle:{"text-decoration":"underline"},on:{"click":function($event){_vm.show_question_answers = false}}},[_vm._v(_vm._s(_vm.__("Hide submitted form", "wpcal")))]):_vm._e()]),(_vm.show_question_answers)?_c('div',{staticClass:"event-survey"},_vm._l((_vm.booking_details.invitee_question_answers),function(question,index){return _c('div',{key:index,staticClass:"wpc-form-row"},[_c('div',{staticClass:"txt-h5 pre",staticStyle:{"color":"#7c7d9c","margin-bottom":"5px"}},[_vm._v(_vm._s(question.question))]),(question.answer == '')?_c('div',{staticClass:"pre",staticStyle:{"margin-bottom":"10px","font-size":"13px","font-style":"italic"}},[_vm._v(" ("+_vm._s(_vm.__("Not entered", "wpcal"))+") ")]):_c('div',{staticClass:"pre b",staticStyle:{"margin-bottom":"10px"}},[_vm._v(_vm._s(question.answer))])])}),0):_vm._e()]):_vm._e()])]),_c('BookingBottomPoweredBy')],1),_c('BookingCancelModal',{attrs:{"booking_details":_vm.booking_details,"cancel_booking_btn_is_enabled":_vm.cancel_booking_btn_is_enabled},on:{"cancel-booking":function($event){return _vm.cancel_booking($event)}},model:{value:(_vm.cancel_reason),callback:function ($$v) {_vm.cancel_reason=$$v},expression:"cancel_reason"}})],1)}
var BookingViewvue_type_template_id_52347f1a_scoped_true_staticRenderFns = []


// CONCATENATED MODULE: ./src/views/user/BookingView.vue?vue&type=template&id=52347f1a&scoped=true&

// EXTERNAL MODULE: ./src/components/LocationAdvanced.vue + 4 modules
var LocationAdvanced = __webpack_require__("2f7b");

// EXTERNAL MODULE: ./src/components/BookingCancelModal.vue + 4 modules
var BookingCancelModal = __webpack_require__("a046");

// EXTERNAL MODULE: ./src/components/user/BookingBottomPoweredBy.vue + 2 modules
var BookingBottomPoweredBy = __webpack_require__("81cb");

// EXTERNAL MODULE: ./node_modules/axios/index.js
var axios = __webpack_require__("bc3a");
var axios_default = /*#__PURE__*/__webpack_require__.n(axios);

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--13-0!./node_modules/thread-loader/dist/cjs.js!./node_modules/babel-loader/lib!./node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/vue-loader/lib??vue-loader-options!./src/views/user/BookingView.vue?vue&type=script&lang=js&

//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//




/* harmony default export */ var BookingViewvue_type_script_lang_js_ = ({
  components: {
    LocationAdvanced: LocationAdvanced["a" /* default */],
    BookingBottomPoweredBy: BookingBottomPoweredBy["a" /* default */],
    BookingCancelModal: BookingCancelModal["a" /* default */]
  },
  data: function data() {
    return {
      service_details: {},
      service_admin_details: {},
      booking_details: {},
      booking_unique_link: "",
      cancel_reason: "",
      redirect_text: "",
      cancel_booking_btn_is_enabled: true,
      show_question_answers: false
    };
  },
  computed: {
    booking_status_txt: function booking_status_txt() {
      var success_txt = "";

      if (!this.booking_details.hasOwnProperty("status")) {
        success_txt = "...";
      } else if (this.booking_details.status == "1") {
        success_txt = this.__("our meeting is confirmed!", "wpcal");

        if (this.$route.name === "booking_view_rescheduled") {
          success_txt = this.__("our meeting is rescheduled successfully!", "wpcal");
        }
      } else if (this.booking_details.status == "-1" || this.booking_details.status == "-2") {
        success_txt = this.__("our meeting is cancelled!", "wpcal");

        if (this.$route.name === "booking_view_cancelled") {
          success_txt = this.__("our meeting is cancelled successfully!", "wpcal");
        }
      } else if (this.booking_details.status == "-5") {
        success_txt = this.__("our meeting is rescheduled!", "wpcal");
      } else {
        success_txt = this.__("Technical error with meeting status.", "wpcal");
      }

      return success_txt;
    },
    is_booking_slot_time_passed: function is_booking_slot_time_passed() {
      //is booking to time is over
      var current_ts = Math.round(new Date().getTime() / 1000);
      return this.booking_details && this.booking_details.booking_to_time < current_ts;
    },
    can_show_rechedule_option: function can_show_rechedule_option() {
      return this.booking_details && this.booking_details.status == "1" && !this.is_booking_slot_time_passed;
    },
    can_show_cancel_option: function can_show_cancel_option() {
      return this.booking_details && this.booking_details.status == "1" && !this.is_booking_slot_time_passed;
    }
  },
  methods: {
    load_booking_view: function load_booking_view() {
      var _this = this;

      this.booking_unique_link = this.$route.params.unique_link;
      var action = "view_booking";
      var wpcal_request = {};
      wpcal_request[action] = {
        unique_link: this.booking_unique_link
      };
      var post_data = {
        action: "wpcal_process_user_ajax_request",
        wpcal_request: wpcal_request
      };
      axios_default.a.post(window.wpcal_ajax.ajax_url, post_data).then(function (response) {
        //console.log(response);
        if (response.data[action].status == "success") {
          _this.booking_details = response.data[action].booking_data;
          _this.service_details = response.data[action].service_data;
          _this.service_admin_details = response.data[action].service_admin_data;

          _this.may_redirect_to_new_booking();

          if (_this.$route.name === "booking_view_cancel") {
            _this.$nextTick(function () {
              if (_this.can_show_cancel_option) {
                _this.show_cancel_confirmation();
              }
            });
          }
        }
      }).catch(function (error) {
        console.log(error);
      });
    },
    may_redirect_to_new_booking: function may_redirect_to_new_booking() {
      var _this2 = this;

      if (this.booking_details.status == "-5" && this.booking_details.rescheduled_booking_unique_link) {
        this.redirect_text = this.__("Redirecting to new booking...", "wpcal");
        setTimeout(function () {
          _this2.$router.push("/booking/view/" + _this2.booking_details.rescheduled_booking_unique_link);
        }, 2000);
      }
    },
    show_cancel_confirmation: function show_cancel_confirmation() {
      this.$modal.show("booking_cancel_confirm_modal");
    },
    cancel_booking: function cancel_booking(booking) {
      var _this3 = this;

      this.cancel_booking_btn_is_enabled = false;
      var wpcal_request = {};
      var action = "cancel_booking";
      wpcal_request[action] = {
        booking_unique_link: booking.unique_link,
        cancel_reason: this.cancel_reason
      };
      var post_data = {
        action: "wpcal_process_user_ajax_request",
        wpcal_request: wpcal_request
      };
      axios_default.a.post(window.wpcal_ajax.ajax_url, post_data).then(function (response) {
        //console.log(response);
        _this3.notify_single_action_result(response.data[action], {
          dont_notify_success: true
        });

        if (response.data[action].status == "success") {
          var wpcal_booking_trigger = new CustomEvent("wpcal_booking_cancelled", {
            detail: {
              booking_details: {
                unique_link: booking.unique_link
              }
            }
          });
          document.dispatchEvent(wpcal_booking_trigger);

          _this3.$modal.hide("booking_cancel_confirm_modal");

          _this3.$router.push("/booking/view/" + booking.unique_link + "/cancelled");
        }
      }).catch(function (error) {
        console.log(error);
      }).then(function () {
        _this3.$store.dispatch("run_booking_background_tasks_by_unique_link", {
          unique_link: booking.unique_link
        });

        _this3.cancel_booking_btn_is_enabled = true;
      });
    }
  },
  watch: {
    "$route.name": function $routeName() {
      Object.assign(this.$data, this.$options.data.apply(this)); //re-initilize data in Vue

      this.load_booking_view();
    },
    "$route.params.unique_link": function $routeParamsUnique_link() {
      Object.assign(this.$data, this.$options.data.apply(this)); //re-initilize data in Vue

      this.load_booking_view();
    }
  },
  mounted: function mounted() {
    this.load_booking_view();
  }
});
// CONCATENATED MODULE: ./src/views/user/BookingView.vue?vue&type=script&lang=js&
 /* harmony default export */ var user_BookingViewvue_type_script_lang_js_ = (BookingViewvue_type_script_lang_js_); 
// EXTERNAL MODULE: ./src/views/user/BookingView.vue?vue&type=style&index=0&id=52347f1a&scoped=true&lang=css&
var BookingViewvue_type_style_index_0_id_52347f1a_scoped_true_lang_css_ = __webpack_require__("6ed8");

// EXTERNAL MODULE: ./src/assets/css/user_booking.css?vue&type=style&index=1&lang=css&
var user_bookingvue_type_style_index_1_lang_css_ = __webpack_require__("0a1f");

// EXTERNAL MODULE: ./src/views/user/BookingView.vue?vue&type=style&index=2&id=52347f1a&scoped=true&lang=css&
var BookingViewvue_type_style_index_2_id_52347f1a_scoped_true_lang_css_ = __webpack_require__("0351");

// CONCATENATED MODULE: ./src/views/user/BookingView.vue








/* normalize component */

var BookingView_component = Object(componentNormalizer["a" /* default */])(
  user_BookingViewvue_type_script_lang_js_,
  BookingViewvue_type_template_id_52347f1a_scoped_true_render,
  BookingViewvue_type_template_id_52347f1a_scoped_true_staticRenderFns,
  false,
  null,
  "52347f1a",
  null
  
)

/* harmony default export */ var BookingView = (BookingView_component.exports);
// CONCATENATED MODULE: ./src/router/user.js













vue_runtime_esm["default"].use(vue_router_esm["a" /* default */]);
var routes = [{
  path: "/",
  name: "user_home",
  // route level code-splitting
  // this generates a separate chunk (about.[hash].js) for this route
  // which is lazy-loaded when the route is visited.
  component: UserHome // component: () => import("../views/user/UserHome.vue")

}, {
  path: "/booking",
  name: "booking",
  children: [{
    path: "reschedule/:unique_link/",
    name: "booking_reschedule"
  }, {
    path: "schedule_again/:unique_link/",
    name: "booking_schedule_again"
  }, {
    path: "confirm",
    name: "booking_confirm",
    components: {
      step2: BookingStep2["a" /* default */] // step2: () => import("../components/user/BookingStep2.vue")

    }
  }],
  component: Booking["a" /* default */] // component: () => import("../views/user/Booking.vue")

}, {
  path: "/booking/view/:unique_link",
  name: "booking_view",
  component: BookingView,
  // component: () => import("../views/user/BookingView.vue"),
  children: [{
    path: "confirmed",
    name: "booking_view_confirmed"
  }, {
    path: "rescheduled",
    name: "booking_view_rescheduled"
  }, {
    path: "cancelled",
    name: "booking_view_cancelled"
  }, {
    path: "cancel",
    name: "booking_view_cancel"
  }]
}];
var router = new vue_router_esm["a" /* default */]({
  mode: "abstract",
  //mode: "history",
  //base: process.env.BASE_URL,
  routes: routes
});
router.afterEach(function (to, from) {
  var handle_push_state = function handle_push_state(to) {
    //console.log("to", to);
    var full_path = to.fullPath;
    var current_url = window.location.href;
    var url_obj = new URL(current_url);
    url_obj.searchParams.set("wpcal_r", full_path);

    if (full_path == "/" || full_path == "/booking") {
      url_obj.searchParams.delete("wpcal_r");
    }

    var history_url = url_obj.toString();
    var param_route = Object(common_func["l" /* get_url_param_by_name */])("wpcal_r");

    if (param_route != full_path) {
      //console.log("history_url  change", history_url);
      window.history.pushState(null, "", history_url);
    }
  };

  handle_push_state(to);
});
/* harmony default export */ var user = (router);
// EXTERNAL MODULE: ./node_modules/vuex/dist/vuex.esm.js
var vuex_esm = __webpack_require__("2f62");

// EXTERNAL MODULE: ./src/store/common_module.js
var common_module = __webpack_require__("cdab");

// CONCATENATED MODULE: ./src/store/user.js


vue_runtime_esm["default"].use(vuex_esm["a" /* default */]);

/* harmony default export */ var store_user = (new vuex_esm["a" /* default */].Store({
  strict: true,
  modules: {
    common: common_module["a" /* default */]
  },
  state: {
    client_end: "user"
  } // mutations: {},
  // getters: {},
  // actions: {}

}));
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

// CONCATENATED MODULE: ./src/user_main.js




//eslint-disable-next-line
__webpack_require__.p = window.__wpcal_dist_url; // As import files are hoisted "window.__wpcal_client_end = "user";" was not running, resulted in unexpected results. Now the same code run using import "./utils/client_end_user.js";










vue_runtime_esm["default"].use(dist_default.a, {
  dialog: true
}); //import VCalendar from "v-calendar";
//Vue.use(VCalendar);


vue_runtime_esm["default"].component("v-date-picker", date_picker_umd_default.a);

vue_runtime_esm["default"].use(lib_default.a);


vue_runtime_esm["default"].use(vuelidate_error_extractor_esm["a" /* default */], vuelidate_error_extractor_options["a" /* default */]);


var defaultVueIziToastOptions = {
  timeout: 5000,
  position: "topRight",
  drag: false
};
vue_runtime_esm["default"].use(vue_izitoast_default.a, defaultVueIziToastOptions);
vue_runtime_esm["default"].config.productionTip = false;

vue_runtime_esm["default"].prototype.__ = common_func["c" /* __ */];
vue_runtime_esm["default"].prototype._x = common_func["g" /* _x */];
vue_runtime_esm["default"].prototype._n = common_func["e" /* _n */];
vue_runtime_esm["default"].prototype._nx = common_func["f" /* _nx */];
vue_runtime_esm["default"].prototype.T__ = common_func["c" /* __ */];
vue_runtime_esm["default"].prototype.T_x = common_func["g" /* _x */];
vue_runtime_esm["default"].prototype.T_n = common_func["e" /* _n */];
vue_runtime_esm["default"].prototype.T_nx = common_func["f" /* _nx */];
vue_runtime_esm["default"].prototype.Tsprintf = common_func["b" /* Tsprintf */]; // document.addEventListener("DOMContentLoaded", () => {
//   new Vue({
//     router,
//     store,
//     render: h => h(UserApp)
//   }).$mount("#wpcal_user_app");
// });

var user_view_instance = null;
var wpcal_load_trigger = new Event("wpcal_load_booking_widget");
var elementor_delay_done = false;
var vue_initiated_once = false;
document.addEventListener("wpcal_load_booking_widget", function () {
  Object(common_func["j" /* debug_log */])("event fired received: wpcal_load_booking_widget");
  var widget_element = document.querySelector("#wpcal_user_app.loading-indicator-initial");

  if (widget_element) {
    if (vue_initiated_once === false && elementor_delay_done === false && widget_element.closest(".elementor-location-popup") && !widget_element.closest(".elementor-popup-modal")) {
      /* While using booking widget in elementor popup, widget html content loads in DOM and then removed by elementor and then re-adds it in different location that is in .elementor-popup-modal, in this gap if Vue is initiated its get stuck without giving error as the mount is removed from dom. so we can delay vue initation */
      //elementor content not in the popup, so delay vue initiation
      console.log("WPCal - Delaying check for elementor workaround");
      setTimeout(function () {
        elementor_delay_done = true;
        document.dispatchEvent(wpcal_load_trigger);
      }, 600);
      return;
    }

    if (user_view_instance) {
      user_view_instance.$destroy();
      user_view_instance = null;
    }

    if (!user_view_instance) {
      Object(common_func["j" /* debug_log */])("Triggering wpcal vue instance");
      vue_initiated_once = true;
      user_view_instance = new vue_runtime_esm["default"]({
        router: user,
        store: store_user,
        render: function render(h) {
          return h(UserApp);
        }
      }).$mount("#wpcal_user_app");
    }
  }
});

try {
  setTimeout(function () {
    Object(common_func["j" /* debug_log */])("WPCal dispatch event after Vue load");
    document.dispatchEvent(wpcal_load_trigger);
  }, 10);
} catch (err) {}

/***/ }),

/***/ "bf36":
/***/ (function(module, exports, __webpack_require__) {

// extracted by mini-css-extract-plugin

/***/ }),

/***/ "eb9c":
/***/ (function(module, exports, __webpack_require__) {

// extracted by mini-css-extract-plugin

/***/ })

/******/ });