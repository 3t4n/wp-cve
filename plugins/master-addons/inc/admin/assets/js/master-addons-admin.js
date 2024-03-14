/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./dev/admin/master-addons-admin.js":
/*!******************************************!*\
  !*** ./dev/admin/master-addons-admin.js ***!
  \******************************************/
/***/ (() => {

(function ($) {
  // Notice Hide
  $('body').on('click', '.jltma-upgrade-popup .popup-dismiss', function (evt) {
    evt.preventDefault();
    $(this).closest('.jltma-upgrade-popup').fadeOut(200);
  });

  // Notice Show
  $('body').on('click', '.disabled', function (evt) {
    evt.preventDefault();
    $('.jltma-upgrade-popup').fadeIn(200);
  });

  // Recommended Plugins

  // Install
  $('body').on('click', '.plugin-action-buttons .install-now', function (e) {
    e.preventDefault();
    if (!$(this).hasClass("updating-message")) {
      var plugin = $(this).attr("data-install-url");
      installPlugin($(this), plugin);
    }
  });

  // Active
  $('body').on('click', '.plugin-action-buttons .activate-now', function () {
    var file = $(this).attr("data-plugin-file");
    activatePlugin($(this), file);
  });

  // Update
  $('body').on('click', '.plugin-action-buttons .update-now', function () {
    if (!$(this).hasClass("updating-message")) {
      var plugin = $(this).attr("data-plugin");
      updatePlugin($(this), plugin);
    }
  });

  // Tab
  $('.filter-links').on('click', 'a', function (e) {
    e.preventDefault();
    var cls = $(this).data('type');
    $(this).addClass('current').parent().siblings().find('a').removeClass('current');
    $('#the-list .plugin-card').each(function (i, el) {
      if (cls == 'all') {
        $(this).removeClass('hide');
      } else {
        if ($(this).hasClass(cls)) {
          $(this).removeClass('hide');
        } else {
          $(this).addClass('hide');
        }
      }
    });
  });

  // Search
  $('.-search-plugins #search-plugins').on('keyup', function () {
    var value = $(this).val();
    var srch = new RegExp(value, "i");
    $('#the-list .plugin-card').each(function () {
      var $this = $(this);
      if (!($this.find('.name h3 a, .desc p').text().search(srch) >= 0)) {
        $this.addClass('hide');
      }
      if ($this.find('.name h3 a, .desc p').text().search(srch) >= 0) {
        $this.removeClass('hide');
      }
    });
  });
})(jQuery);
function activatePlugin(element, file) {
  element.addClass("button-disabled");
  element.attr("disabled", "disabled");
  element.text("Processing...");
  jQuery.ajax({
    url: MASTER_ADDONSCORE.admin_ajax,
    type: "POST",
    data: {
      action: "jltma_recommended_activate_plugin",
      file: file,
      nonce: MASTER_ADDONSCORE.recommended_nonce
    },
    success: function success(response) {
      if (response.success === true) {
        var pluginStatus = jQuery(".plugin-status .plugin-status-inactive[data-plugin-file='" + file + "']");
        pluginStatus.text("Active");
        pluginStatus.addClass("plugin-status-active");
        pluginStatus.removeClass("plugin-status-inactive");
        element.removeClass("active-now");
        element.text("Activated");
      } else {
        element.removeClass("button-disabled");
        element.prop("disabled", false);
        element.text("Activated");
      }
    }
  });
}
function installPlugin(element, plugin) {
  element.removeClass("button-primary");
  element.addClass("updating-message");
  element.text("Installing...");
  jQuery.ajax({
    url: MASTER_ADDONSCORE.admin_ajax,
    type: "POST",
    data: {
      action: "jltma_recommended_upgrade_plugin",
      type: 'install',
      plugin: plugin,
      nonce: MASTER_ADDONSCORE.recommended_nonce
    },
    success: function success(response) {
      if (response.success === true) {
        element.removeClass("updating-message");
        element.addClass("updated-message installed button-disabled");
        element.attr("disabled", "disabled");
        element.removeAttr("data-install-url");
        element.text("Installed!");
        setTimeout(function () {
          var pluginStatus = jQuery(".plugin-status .plugin-status-not-install[data-plugin-url='" + plugin + "']");
          pluginStatus.text("Active");
          pluginStatus.addClass("plugin-status-active");
          pluginStatus.removeClass("plugin-status-not-install");
          pluginStatus.removeAttr("data-install-url");
          element.removeClass("install-now updated-message installed");
          element.text("Activated");
          element.removeAttr("aria-label");
        }, 500);
      } else {
        element.removeClass("updating-message");
        element.addClass("button-primary");
        element.text("Install Now");
      }
    }
  });
}
function updatePlugin(element, plugin) {
  element.addClass("updating-message");
  element.text("Updating...");
  jQuery.ajax({
    url: MASTER_ADDONSCORE.admin_ajax,
    type: "POST",
    data: {
      action: "jltma_recommended_upgrade_plugin",
      type: "update",
      plugin: plugin,
      nonce: MASTER_ADDONSCORE.recommended_nonce
    },
    success: function success(response) {
      if (response.success === true) {
        element.removeClass("updating-message");
        element.addClass("updated-message button-disabled");
        element.attr("disabled", "disabled");
        element.text("Updated!");
        if (response.data.active === false) {
          var pluginStatus = jQuery(".plugin-status .plugin-status-inactive[data-plugin-file='" + plugin + "']");
          pluginStatus.text("Active");
          pluginStatus.addClass("plugin-status-active");
          pluginStatus.removeClass("plugin-status-inactive");
          pluginStatus.removeAttr("data-plugin-file");
        }
      } else {
        element.removeClass("updating-message");
        element.text("Update Now");
      }
    }
  });
}

/***/ }),

/***/ "./assets/scss/premium/master-addons-pro.scss":
/*!****************************************************!*\
  !*** ./assets/scss/premium/master-addons-pro.scss ***!
  \****************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./assets/scss/tippy/tippy.scss":
/*!**************************************!*\
  !*** ./assets/scss/tippy/tippy.scss ***!
  \**************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./assets/scss/plugin-survey.scss":
/*!****************************************!*\
  !*** ./assets/scss/plugin-survey.scss ***!
  \****************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./assets/scss/admin/master-addons-admin.scss":
/*!****************************************************!*\
  !*** ./assets/scss/admin/master-addons-admin.scss ***!
  \****************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./assets/scss/admin/master-addons-admin-sdk.scss":
/*!********************************************************!*\
  !*** ./assets/scss/admin/master-addons-admin-sdk.scss ***!
  \********************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./assets/scss/modules/header-footer/header-footer.scss":
/*!**************************************************************!*\
  !*** ./assets/scss/modules/header-footer/header-footer.scss ***!
  \**************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./assets/scss/modules/mega-menu/mega-menu.scss":
/*!******************************************************!*\
  !*** ./assets/scss/modules/mega-menu/mega-menu.scss ***!
  \******************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./assets/scss/addons-styles.scss":
/*!****************************************!*\
  !*** ./assets/scss/addons-styles.scss ***!
  \****************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


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
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = __webpack_modules__;
/******/
/************************************************************************/
/******/ 	/* webpack/runtime/chunk loaded */
/******/ 	(() => {
/******/ 		var deferred = [];
/******/ 		__webpack_require__.O = (result, chunkIds, fn, priority) => {
/******/ 			if(chunkIds) {
/******/ 				priority = priority || 0;
/******/ 				for(var i = deferred.length; i > 0 && deferred[i - 1][2] > priority; i--) deferred[i] = deferred[i - 1];
/******/ 				deferred[i] = [chunkIds, fn, priority];
/******/ 				return;
/******/ 			}
/******/ 			var notFulfilled = Infinity;
/******/ 			for (var i = 0; i < deferred.length; i++) {
/******/ 				var [chunkIds, fn, priority] = deferred[i];
/******/ 				var fulfilled = true;
/******/ 				for (var j = 0; j < chunkIds.length; j++) {
/******/ 					if ((priority & 1 === 0 || notFulfilled >= priority) && Object.keys(__webpack_require__.O).every((key) => (__webpack_require__.O[key](chunkIds[j])))) {
/******/ 						chunkIds.splice(j--, 1);
/******/ 					} else {
/******/ 						fulfilled = false;
/******/ 						if(priority < notFulfilled) notFulfilled = priority;
/******/ 					}
/******/ 				}
/******/ 				if(fulfilled) {
/******/ 					deferred.splice(i--, 1)
/******/ 					var r = fn();
/******/ 					if (r !== undefined) result = r;
/******/ 				}
/******/ 			}
/******/ 			return result;
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
/******/ 	/* webpack/runtime/jsonp chunk loading */
/******/ 	(() => {
/******/ 		// no baseURI
/******/
/******/ 		// object to store loaded and loading chunks
/******/ 		// undefined = chunk not loaded, null = chunk preloaded/prefetched
/******/ 		// [resolve, reject, Promise] = chunk loading, 0 = chunk loaded
/******/ 		var installedChunks = {
/******/ 			"/assets/js/master-addons-admin": 0,
/******/ 			"assets/css/master-addons-styles": 0,
/******/ 			"assets/megamenu/css/megamenu": 0,
/******/ 			"inc/modules/header-footer-comment/assets/css/header-footer": 0,
/******/ 			"inc/admin/assets/css/master-addons-admin-sdk": 0,
/******/ 			"inc/admin/assets/css/master-addons-admin": 0,
/******/ 			"assets/css/plugin-survey": 0,
/******/ 			"assets/vendor/tippyjs/css/tippy": 0,
/******/ 			"premium/assets/css/master-addons-pro": 0
/******/ 		};
/******/
/******/ 		// no chunk on demand loading
/******/
/******/ 		// no prefetching
/******/
/******/ 		// no preloaded
/******/
/******/ 		// no HMR
/******/
/******/ 		// no HMR manifest
/******/
/******/ 		__webpack_require__.O.j = (chunkId) => (installedChunks[chunkId] === 0);
/******/
/******/ 		// install a JSONP callback for chunk loading
/******/ 		var webpackJsonpCallback = (parentChunkLoadingFunction, data) => {
/******/ 			var [chunkIds, moreModules, runtime] = data;
/******/ 			// add "moreModules" to the modules object,
/******/ 			// then flag all "chunkIds" as loaded and fire callback
/******/ 			var moduleId, chunkId, i = 0;
/******/ 			if(chunkIds.some((id) => (installedChunks[id] !== 0))) {
/******/ 				for(moduleId in moreModules) {
/******/ 					if(__webpack_require__.o(moreModules, moduleId)) {
/******/ 						__webpack_require__.m[moduleId] = moreModules[moduleId];
/******/ 					}
/******/ 				}
/******/ 				if(runtime) var result = runtime(__webpack_require__);
/******/ 			}
/******/ 			if(parentChunkLoadingFunction) parentChunkLoadingFunction(data);
/******/ 			for(;i < chunkIds.length; i++) {
/******/ 				chunkId = chunkIds[i];
/******/ 				if(__webpack_require__.o(installedChunks, chunkId) && installedChunks[chunkId]) {
/******/ 					installedChunks[chunkId][0]();
/******/ 				}
/******/ 				installedChunks[chunkId] = 0;
/******/ 			}
/******/ 			return __webpack_require__.O(result);
/******/ 		}
/******/
/******/ 		var chunkLoadingGlobal = self["webpackChunkmaster_addons"] = self["webpackChunkmaster_addons"] || [];
/******/ 		chunkLoadingGlobal.forEach(webpackJsonpCallback.bind(null, 0));
/******/ 		chunkLoadingGlobal.push = webpackJsonpCallback.bind(null, chunkLoadingGlobal.push.bind(chunkLoadingGlobal));
/******/ 	})();
/******/
/************************************************************************/
/******/
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module depends on other loaded chunks and execution need to be delayed
/******/ 	__webpack_require__.O(undefined, ["assets/css/master-addons-styles","assets/megamenu/css/megamenu","inc/modules/header-footer-comment/assets/css/header-footer","inc/admin/assets/css/master-addons-admin-sdk","inc/admin/assets/css/master-addons-admin","assets/css/plugin-survey","assets/vendor/tippyjs/css/tippy","premium/assets/css/master-addons-pro"], () => (__webpack_require__("./dev/admin/master-addons-admin.js")))
/******/ 	__webpack_require__.O(undefined, ["assets/css/master-addons-styles","assets/megamenu/css/megamenu","inc/modules/header-footer-comment/assets/css/header-footer","inc/admin/assets/css/master-addons-admin-sdk","inc/admin/assets/css/master-addons-admin","assets/css/plugin-survey","assets/vendor/tippyjs/css/tippy","premium/assets/css/master-addons-pro"], () => (__webpack_require__("./assets/scss/admin/master-addons-admin.scss")))
/******/ 	__webpack_require__.O(undefined, ["assets/css/master-addons-styles","assets/megamenu/css/megamenu","inc/modules/header-footer-comment/assets/css/header-footer","inc/admin/assets/css/master-addons-admin-sdk","inc/admin/assets/css/master-addons-admin","assets/css/plugin-survey","assets/vendor/tippyjs/css/tippy","premium/assets/css/master-addons-pro"], () => (__webpack_require__("./assets/scss/admin/master-addons-admin-sdk.scss")))
/******/ 	__webpack_require__.O(undefined, ["assets/css/master-addons-styles","assets/megamenu/css/megamenu","inc/modules/header-footer-comment/assets/css/header-footer","inc/admin/assets/css/master-addons-admin-sdk","inc/admin/assets/css/master-addons-admin","assets/css/plugin-survey","assets/vendor/tippyjs/css/tippy","premium/assets/css/master-addons-pro"], () => (__webpack_require__("./assets/scss/modules/header-footer/header-footer.scss")))
/******/ 	__webpack_require__.O(undefined, ["assets/css/master-addons-styles","assets/megamenu/css/megamenu","inc/modules/header-footer-comment/assets/css/header-footer","inc/admin/assets/css/master-addons-admin-sdk","inc/admin/assets/css/master-addons-admin","assets/css/plugin-survey","assets/vendor/tippyjs/css/tippy","premium/assets/css/master-addons-pro"], () => (__webpack_require__("./assets/scss/modules/mega-menu/mega-menu.scss")))
/******/ 	__webpack_require__.O(undefined, ["assets/css/master-addons-styles","assets/megamenu/css/megamenu","inc/modules/header-footer-comment/assets/css/header-footer","inc/admin/assets/css/master-addons-admin-sdk","inc/admin/assets/css/master-addons-admin","assets/css/plugin-survey","assets/vendor/tippyjs/css/tippy","premium/assets/css/master-addons-pro"], () => (__webpack_require__("./assets/scss/addons-styles.scss")))
/******/ 	__webpack_require__.O(undefined, ["assets/css/master-addons-styles","assets/megamenu/css/megamenu","inc/modules/header-footer-comment/assets/css/header-footer","inc/admin/assets/css/master-addons-admin-sdk","inc/admin/assets/css/master-addons-admin","assets/css/plugin-survey","assets/vendor/tippyjs/css/tippy","premium/assets/css/master-addons-pro"], () => (__webpack_require__("./assets/scss/premium/master-addons-pro.scss")))
/******/ 	__webpack_require__.O(undefined, ["assets/css/master-addons-styles","assets/megamenu/css/megamenu","inc/modules/header-footer-comment/assets/css/header-footer","inc/admin/assets/css/master-addons-admin-sdk","inc/admin/assets/css/master-addons-admin","assets/css/plugin-survey","assets/vendor/tippyjs/css/tippy","premium/assets/css/master-addons-pro"], () => (__webpack_require__("./assets/scss/tippy/tippy.scss")))
/******/ 	var __webpack_exports__ = __webpack_require__.O(undefined, ["assets/css/master-addons-styles","assets/megamenu/css/megamenu","inc/modules/header-footer-comment/assets/css/header-footer","inc/admin/assets/css/master-addons-admin-sdk","inc/admin/assets/css/master-addons-admin","assets/css/plugin-survey","assets/vendor/tippyjs/css/tippy","premium/assets/css/master-addons-pro"], () => (__webpack_require__("./assets/scss/plugin-survey.scss")))
/******/ 	__webpack_exports__ = __webpack_require__.O(__webpack_exports__);
/******/
/******/ })()
;
//# sourceMappingURL=master-addons-admin.js.map
