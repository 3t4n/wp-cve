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
/******/ 		"admin_other": 0
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
/******/ 	deferredModules.push([3,"chunk-vendors","chunk-common"]);
/******/ 	// run deferred modules when ready
/******/ 	return checkDeferredModules();
/******/ })
/************************************************************************/
/******/ ({

/***/ "08d8":
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_wf_stylesheet_css_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__("2d89");
/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_wf_stylesheet_css_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_wf_stylesheet_css_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_0__);
/* unused harmony reexport * */


/***/ }),

/***/ "2d89":
/***/ (function(module, exports, __webpack_require__) {

// extracted by mini-css-extract-plugin

/***/ }),

/***/ 3:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__("850d");


/***/ }),

/***/ "3c37":
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_admin_other_app_css_vue_type_style_index_1_lang_css___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__("ac96");
/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_admin_other_app_css_vue_type_style_index_1_lang_css___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_admin_other_app_css_vue_type_style_index_1_lang_css___WEBPACK_IMPORTED_MODULE_0__);
/* unused harmony reexport * */


/***/ }),

/***/ "850d":
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

// EXTERNAL MODULE: ./node_modules/vue/dist/vue.runtime.esm.js
var vue_runtime_esm = __webpack_require__("2b0e");

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"1bf56cee-vue-loader-template"}!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/vue-loader/lib??vue-loader-options!./src/AdminOtherApp.vue?vue&type=template&id=22ad5278&
var AdminOtherAppvue_type_template_id_22ad5278_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',{staticClass:"wpcal_admin_end",attrs:{"id":"wpcal_admin_other_app"}},[_c(_vm.dynamic_component,{tag:"component",on:{"change-dynamic-component":function($event){return _vm.load_component($event)}}})],1)}
var staticRenderFns = []


// CONCATENATED MODULE: ./src/AdminOtherApp.vue?vue&type=template&id=22ad5278&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"1bf56cee-vue-loader-template"}!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/vue-loader/lib??vue-loader-options!./src/admin_other/DeactivateFeedback.vue?vue&type=template&id=082fa33d&
var DeactivateFeedbackvue_type_template_id_082fa33d_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',[_c('modal',{staticStyle:{"z-index":"9999"},attrs:{"name":"deactivate_feedback_modal","height":"auto","width":"360px"},on:{"closed":_vm.deactivate_feedback_modal_on_closed}},[_c('div',{staticClass:"modal_close_cont"},[_c('button',{on:{"click":function($event){return _vm.$modal.hide('deactivate_feedback_modal')}}})]),_c('div',{staticClass:"mbox bg-light shadow mb0"},[_c('div',{staticClass:"form"},[_c('div',{staticClass:"wpc-form-row"},[_c('div',{staticClass:"mb10",staticStyle:{"text-align":"center","font-size":"16px"}},[_c('span',{domProps:{"innerHTML":_vm._s(
                _vm.Tsprintf(
                  /* translators: 1: html-tag */
                  _vm.T__(
                    'If you have a moment, please share why %1$s you are deactivating'
                  ),
                  '<br>'
                )
              )}}),_vm._v(" WPCal.io ")])]),_c('ul',{staticClass:"selector block mt10 exit-survey"},_vm._l((_vm.reasons),function(reason,index){return _c('li',{key:index},[_c('input',{directives:[{name:"model",rawName:"v-model",value:(_vm.form.reason),expression:"form.reason"}],attrs:{"type":"radio","name":"wpcal_deactivate_reason","id":'wpcal_deactivate_reason_' + index},domProps:{"value":reason.value,"checked":_vm._q(_vm.form.reason,reason.value)},on:{"focus":function($event){return _vm.ref_focus('deactivate_reason_user_descr_' + index)},"change":function($event){return _vm.$set(_vm.form, "reason", reason.value)}}}),_c('label',{staticStyle:{"font-size":"15px"},attrs:{"for":'wpcal_deactivate_reason_' + index}},[_vm._v(_vm._s(reason.label)+" "),(
                  _vm.form.reason == reason.value &&
                    reason.hasOwnProperty('user_descr')
                )?_c('div',[(reason.user_descr_label)?_c('label',{staticStyle:{"display":"block","font-size":"13px","margin":"5px 0","color":"#7c7d9c","white-space":"normal"},attrs:{"for":'wpcal_deactivate_reason_user_descr_' + index}},[_vm._v(_vm._s(reason.user_descr_label))]):_vm._e(),(reason.value == 'having_issues')?_c('div',{staticStyle:{"margin-bottom":"5px"}},[_c('div',{staticStyle:{"font-size":"13px","color":"rgb(124, 125, 156)","white-space":"normal"}},[_vm._v(" "+_vm._s(_vm.T__( "Please contact our support team for further assistance." ))+" ")]),_c('div',[_c('a',{staticStyle:{"font-size":"14px"},attrs:{"href":"https://wpcal.io/support/","target":"_blank"},on:{"click":function($event){return _vm.$emit('change-dynamic-component', '')}}},[_vm._v("https://wpcal.io/support/ "),_c('span',{staticStyle:{"float":"right"}},[_vm._v("↗")])])])]):_vm._e(),_c('textarea',{directives:[{name:"model",rawName:"v-model",value:(_vm.form.user_descr),expression:"form.user_descr"}],ref:'deactivate_reason_user_descr_' + index,refInFor:true,attrs:{"id":'wpcal_deactivate_reason_user_descr_' + index},domProps:{"value":(_vm.form.user_descr)},on:{"input":function($event){if($event.target.composing){ return; }_vm.$set(_vm.form, "user_descr", $event.target.value)}}})]):_vm._e()])])}),0)]),_c('div',{staticClass:"wpc-form-row"},[_c('button',{staticClass:"wpc-btn primary lg mt20",staticStyle:{"width":"100%"},attrs:{"type":"button","disabled":!_vm.modal_deactivate_btn_is_enabled},on:{"click":_vm.submit_form_deactivate}},[_vm._v(" "+_vm._s(_vm.T__("Submit & Deactivate"))+" ")])])])])],1)}
var DeactivateFeedbackvue_type_template_id_082fa33d_staticRenderFns = []


// CONCATENATED MODULE: ./src/admin_other/DeactivateFeedback.vue?vue&type=template&id=082fa33d&

// EXTERNAL MODULE: ./node_modules/axios/index.js
var axios = __webpack_require__("bc3a");
var axios_default = /*#__PURE__*/__webpack_require__.n(axios);

// EXTERNAL MODULE: ./node_modules/qs/lib/index.js
var lib = __webpack_require__("4328");
var lib_default = /*#__PURE__*/__webpack_require__.n(lib);

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--13-0!./node_modules/thread-loader/dist/cjs.js!./node_modules/babel-loader/lib!./node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/vue-loader/lib??vue-loader-options!./src/admin_other/DeactivateFeedback.vue?vue&type=script&lang=js&
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//


/* harmony default export */ var DeactivateFeedbackvue_type_script_lang_js_ = ({
  data: function data() {
    return {
      reasons: [{
        value: "no_longer_needed",
        label: this.T__("I no longer need this plugin")
      }, {
        value: "found_better_plugin",
        label: this.T__("I found a better plugin"),
        user_descr_label: this.T__("Is it possible to provide the plugin's name? It will help us understand what are missing."),
        user_descr: ""
      }, {
        value: "having_issues",
        label: this.T__("I am facing some issues with the plugin"),
        user_descr_label: this.T__("We would really love an opportunity to fix any issue and help you succeed with the plugin."),
        user_descr: ""
      }, {
        value: "feature_missing",
        label: this.T__("Feature(s) I need are missing"),
        user_descr_label: this.T__("Can you please tell us what's missing?"),
        user_descr: ""
      }, {
        value: "temporary_deactivate",
        label: this.T__("It's a temporary deactivation")
      }, {
        value: "other",
        label: "Other",
        user_descr_label: this.T__("Can you please give us more details?"),
        user_descr: ""
      }],
      form: {
        reason: "",
        user_descr: ""
      },
      modal_deactivate_btn_is_enabled: true
    };
  },
  methods: {
    show_deactivate_feedback_modal: function show_deactivate_feedback_modal() {
      this.$modal.show("deactivate_feedback_modal");
    },
    submit_form_deactivate: function submit_form_deactivate() {
      var _this = this;

      this.modal_deactivate_btn_is_enabled = false;
      var form_details = Object.assign({}, this.form);

      if (form_details.reason && (form_details.reason == "no_longer_needed" || form_details.reason == "temporary_deactivate")) {
        form_details.user_descr = "";
      }

      var wpcal_request = {};
      wpcal_request["submit_plugin_deactivate_feedback"] = {
        form: form_details
      };
      var post_data = {
        action: "wpcal_process_admin_ajax_request",
        wpcal_request: wpcal_request
      };
      axios_default.a.post(window.wpcal_ajax.ajax_url, lib_default.a.stringify(post_data)).then(function (response) {
        response.data;
      }).catch(function (error) {
        console.log(error);
      }).then(function () {
        setTimeout(function () {
          //time out, so that it deactivate will also starts
          _this.modal_deactivate_btn_is_enabled = true;
        }, 4000);
        wpcal_deactivate_feedback_shown = true; //even the form not able to show, next time let it consider it has shown

        window.jQuery("[data-slug=wpcal] .deactivate a")[0].click();
      });
    },
    deactivate_feedback_modal_on_closed: function deactivate_feedback_modal_on_closed() {
      this.$emit("change-dynamic-component", "");
    },
    ref_focus: function ref_focus(ref_name) {
      var _this2 = this;

      setTimeout(function () {
        if (Array.isArray(_this2.$refs[ref_name])) {
          _this2.$refs[ref_name][0].focus();
        } else {
          _this2.$refs[ref_name].focus();
        }
      }, 100);
    }
  },
  mounted: function mounted() {
    this.show_deactivate_feedback_modal();
  }
});
// CONCATENATED MODULE: ./src/admin_other/DeactivateFeedback.vue?vue&type=script&lang=js&
 /* harmony default export */ var admin_other_DeactivateFeedbackvue_type_script_lang_js_ = (DeactivateFeedbackvue_type_script_lang_js_); 
// EXTERNAL MODULE: ./node_modules/vue-loader/lib/runtime/componentNormalizer.js
var componentNormalizer = __webpack_require__("2877");

// CONCATENATED MODULE: ./src/admin_other/DeactivateFeedback.vue





/* normalize component */

var component = Object(componentNormalizer["a" /* default */])(
  admin_other_DeactivateFeedbackvue_type_script_lang_js_,
  DeactivateFeedbackvue_type_template_id_082fa33d_render,
  DeactivateFeedbackvue_type_template_id_082fa33d_staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* harmony default export */ var DeactivateFeedback = (component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--13-0!./node_modules/thread-loader/dist/cjs.js!./node_modules/babel-loader/lib!./node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/vue-loader/lib??vue-loader-options!./src/AdminOtherApp.vue?vue&type=script&lang=js&
//
//
//
//
//
//
//
//
//

/* harmony default export */ var AdminOtherAppvue_type_script_lang_js_ = ({
  components: {
    DeactivateFeedback: DeactivateFeedback
  },
  data: function data() {
    return {
      dynamic_component: null
    };
  },
  methods: {
    load_component: function load_component(component) {
      this.dynamic_component = component;
    }
  }
});
// CONCATENATED MODULE: ./src/AdminOtherApp.vue?vue&type=script&lang=js&
 /* harmony default export */ var src_AdminOtherAppvue_type_script_lang_js_ = (AdminOtherAppvue_type_script_lang_js_); 
// EXTERNAL MODULE: ./src/assets/fonts/wf-stylesheet.css?vue&type=style&index=0&lang=css&
var wf_stylesheetvue_type_style_index_0_lang_css_ = __webpack_require__("08d8");

// EXTERNAL MODULE: ./src/assets/css/admin_other_app.css?vue&type=style&index=1&lang=css&
var admin_other_appvue_type_style_index_1_lang_css_ = __webpack_require__("3c37");

// EXTERNAL MODULE: ./src/AdminOtherApp.vue?vue&type=style&index=2&lang=css&
var AdminOtherAppvue_type_style_index_2_lang_css_ = __webpack_require__("aa19");

// CONCATENATED MODULE: ./src/AdminOtherApp.vue








/* normalize component */

var AdminOtherApp_component = Object(componentNormalizer["a" /* default */])(
  src_AdminOtherAppvue_type_script_lang_js_,
  AdminOtherAppvue_type_template_id_22ad5278_render,
  staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* harmony default export */ var AdminOtherApp = (AdminOtherApp_component.exports);
// EXTERNAL MODULE: ./node_modules/vue-js-modal/dist/index.js
var dist = __webpack_require__("1881");
var dist_default = /*#__PURE__*/__webpack_require__.n(dist);

// EXTERNAL MODULE: ./src/utils/common_func.js
var common_func = __webpack_require__("d1ff");

// CONCATENATED MODULE: ./src/admin_other_main.js




//eslint-disable-next-line
__webpack_require__.p = window.__wpcal_dist_url;



vue_runtime_esm["default"].use(dist_default.a, {
  dialog: true
});
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
  window.wpcal_admin_other_vins = new vue_runtime_esm["default"]({
    render: function render(h) {
      return h(AdminOtherApp);
    }
  }).$mount("#wpcal_admin_other_app");
});

/***/ }),

/***/ "aa19":
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_node_modules_cache_loader_dist_cjs_js_ref_1_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AdminOtherApp_vue_vue_type_style_index_2_lang_css___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__("be22");
/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_node_modules_cache_loader_dist_cjs_js_ref_1_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AdminOtherApp_vue_vue_type_style_index_2_lang_css___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_mini_css_extract_plugin_dist_loader_js_ref_7_oneOf_1_0_node_modules_vue_cli_service_node_modules_css_loader_dist_cjs_js_ref_7_oneOf_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_oneOf_1_2_node_modules_cache_loader_dist_cjs_js_ref_1_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AdminOtherApp_vue_vue_type_style_index_2_lang_css___WEBPACK_IMPORTED_MODULE_0__);
/* unused harmony reexport * */


/***/ }),

/***/ "ac96":
/***/ (function(module, exports, __webpack_require__) {

// extracted by mini-css-extract-plugin

/***/ }),

/***/ "be22":
/***/ (function(module, exports, __webpack_require__) {

// extracted by mini-css-extract-plugin

/***/ })

/******/ });