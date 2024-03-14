/*! elementor-pro - v2.5.9 - 28-05-2019 */
/******/ (function(modules) { // webpackBootstrap
					"use strict";
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
/******/ 	return __webpack_require__(__webpack_require__.s = 'leadlovers_require');
/******/ })
/************************************************************************/
/******/ ({

/***/ 'leadlovers_require':
/***/ (function(module, exports, __webpack_require__) {

"use strict";

var modules = {
	forms_integrations: __webpack_require__('api_leadlovers'),
};

window.elementorProAdminLL = {
	forms_integrations: new modules.forms_integrations()
};

/***/ }),

/***/ 'api_leadlovers':
/***/ (function(module, exports, __webpack_require__) {

			"use strict";


			module.exports = function () {
				var ApiValidations = __webpack_require__('api_validations');

			this.leadlovers = new ApiValidations('leadlovers_api_key');
			};

/***/ }),

/***/ 'api_validations':
/***/ (function(module, exports, __webpack_require__) {

"use strict";


module.exports = function (key, fieldID) {
	var self = this;
	self.cacheElements = function () {
		this.cache = {
			$button: jQuery('#elementor_pro_' + key + '_button'),
			$apiKeyField: jQuery('#elementor_pro_' + key),
			$apiUrlField: jQuery('#elementor_pro_' + fieldID)
		};
	};
	self.bindEvents = function () {
		this.cache.$button.on('click', function (event) {
			event.preventDefault();
			self.validateApi();
		});

		this.cache.$apiKeyField.on('change', function () {
			self.setState('clear');
		});
	};
	self.validateApi = function () {
		this.setState('loading');
		var apiKey = this.cache.$apiKeyField.val();

		if ('' === apiKey) {
			this.setState('clear');
			return;
		}

		if (this.cache.$apiUrlField.length && '' === this.cache.$apiUrlField.val()) {
			this.setState('clear');
			return;
		}

		jQuery.post(ajaxurl, {
			action: self.cache.$button.data('action'),
			api_key: apiKey,
			api_url: this.cache.$apiUrlField.val(),
			_nonce: self.cache.$button.data('nonce')
		}).done(function (data) {
			if (data.success) {
				self.setState('success');
			} else {
				self.setState('error');
			}
		}).fail(function () {
			self.setState();
		});
	};
	self.setState = function (type) {
		var classes = ['loading', 'success', 'error'],
		    currentClass,
		    classIndex;

		for (classIndex in classes) {
			currentClass = classes[classIndex];
			if (type === currentClass) {
				this.cache.$button.addClass(currentClass);
			} else {
				this.cache.$button.removeClass(currentClass);
			}
		}
	};
	self.init = function () {
		this.cacheElements();
		this.bindEvents();
	};
	self.init();
};

/***/ }),

/******/ });
//# sourceMappingURL=admin.js.map