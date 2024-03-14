/*!
 * Duplicate Variations for WooCommerce
 *
 * Author: Emran Ahmed ( emran.bd.08@gmail.com )
 * Date: 9/4/2023, 5:34:39 PM
 * Released under the GPLv3 license.
 */
/******/ (function() { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./src/js/variation-duplicator-for-woocommerce.js":
/***/ (function() {

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); Object.defineProperty(Constructor, "prototype", { writable: false }); return Constructor; }

/* global wp, WooVariationDuplicator, woocommerce_admin_meta_boxes_variations, woocommerce_admin, wc_meta_boxes_product_variations_pagenav */
jQuery(function ($) {
  'use strict'; // Variation Image Clone

  var Variation_Duplicator_For_Woocommerce_Variation_Image_Clone = /*#__PURE__*/function () {
    function Variation_Duplicator_For_Woocommerce_Variation_Image_Clone() {
      _classCallCheck(this, Variation_Duplicator_For_Woocommerce_Variation_Image_Clone);
    }

    _createClass(Variation_Duplicator_For_Woocommerce_Variation_Image_Clone, null, [{
      key: "init",
      value: function init() {
        var _this = this;

        // this.setOption()
        this.events();
        $('#woocommerce-product-data').on('woocommerce_variations_loaded', function () {
          _this.setCloneWrapper();

          _this.setSelect2(); // this.setOption()
          // this.events()

        });
        $('#variable_product_options').on('woocommerce_variations_added', function () {
          _this.setCloneWrapper(); //this.setOption()


          _this.events();

          _this.setSelect2();
        });
      }
    }, {
      key: "setCloneWrapper",
      value: function setCloneWrapper() {
        $('.woocommerce_variation').each(function () {
          var optionsWrapper = $(this).find('.options:first');
          var chooseVariationsIdWrapper = $(this).find('.woo-variable-image-duplicator-wrapper');
          chooseVariationsIdWrapper.insertBefore(optionsWrapper);
        });
      }
    }, {
      key: "setSelect2",
      value: function setSelect2() {
        var _this2 = this;

        try {
          $(':input.variable-image-duplicate-select').filter(':not(.enhanced)').each(function (index, element) {
            var select2_args = {
              minimumResultsForSearch: 10,
              allowClear: !!$(element).data('allow_clear'),
              placeholder: $(element).data('placeholder'),
              templateResult: _this2.imgCloneFormFormat,
              templateSelection: _this2.imgCloneFormFormat
            };
            $(element).select2(select2_args).addClass('enhanced');
          });
        } catch (err) {
          // If script (conflict?) log the error but don't stop other scripts breaking.
          console.log('Select2 Error: ', err);
        }
      }
    }, {
      key: "imgCloneFormFormat",
      value: function imgCloneFormFormat(state) {
        if (!state.id) {
          return state.text;
        }

        var thumbnail_url = $(state.element).data('thumbnail_url');

        if (thumbnail_url) {
          return $('<img class="variation-duplicator-thumbnail-image-preview" src="' + thumbnail_url + '" alt=" ' + state.text + '" width="30" height="30"/> <span class="variation-duplicator-thumbnail-image-text">' + state.text + '</span>');
        }

        return state.text;
      }
    }, {
      key: "setOption",
      value: function setOption() {
        var $select = $('#field_to_edit');
        var actionsWrapper = $select.find('option:first');
        $select.find('option.woo_variation_duplicate_option').insertAfter(actionsWrapper);
      }
    }, {
      key: "events",
      value: function events() {
        $('#variable_product_options').on('click', ':input.variable-image-duplicate-type', this.chooseType).on('click', 'button.select_all_variations', this.selectAll).on('click', 'button.select_no_variations', this.selectNone).on('change', ':input.upload_image_id', this.clearChooseType);
      }
    }, {
      key: "clearChooseType",
      value: function clearChooseType(event) {
        $(this).closest('.woocommerce_variable_attributes').find('.woo-variable-image-duplicator-wrapper .variable_image_duplicate_type_form_field :radio').prop('checked', false);
        $(this).closest('.woocommerce_variable_attributes').find('.woo-variable-image-duplicator-wrapper .variable-list').removeClass('show');
      }
    }, {
      key: "chooseType",
      value: function chooseType(event) {
        var type = $(this).val();
        $(this).closest('.woo-variable-image-duplicator-wrapper').find('.variable-list').removeClass('show');

        if (type === 'to') {
          var upload_image_id = parseInt($(this).closest('.woocommerce_variation').find(':input.upload_image_id').val(), 10);

          if (isNaN(upload_image_id) || upload_image_id < 1) {
            $(this).closest('.woo-variable-image-duplicator-wrapper').find('.variable-image-duplicate-to-notice').addClass('show');
            return;
          }
        }

        $(this).closest('.woo-variable-image-duplicator-wrapper').find('.variable-image-duplicate-' + type).addClass('show');
      }
    }, {
      key: "selectAll",
      value: function selectAll(event) {
        event.preventDefault();
        $(this).closest('p').find('select > option').prop('selected', true);
        $(this).closest('p').find('select').trigger('change');
      }
    }, {
      key: "selectNone",
      value: function selectNone(event) {
        event.preventDefault();
        $(this).closest('p').find('select > option').prop('selected', false);
        $(this).closest('p').find('select').trigger('change');
      }
    }]);

    return Variation_Duplicator_For_Woocommerce_Variation_Image_Clone;
  }(); // Variation Clone


  var Variation_Duplicator_For_Woocommerce_Variation_Clone = /*#__PURE__*/function () {
    function Variation_Duplicator_For_Woocommerce_Variation_Clone() {
      _classCallCheck(this, Variation_Duplicator_For_Woocommerce_Variation_Clone);
    }

    _createClass(Variation_Duplicator_For_Woocommerce_Variation_Clone, null, [{
      key: "init",
      value: function init() {
        var _this3 = this;

        this.setHowTo();
        $(document).on('click', 'input.variation_is_cloneable', this.cloneableClick);
        $(document).on('change', 'input.variation_is_cloneable', this.cloneableChange);
        $('select.variation_actions').on('woo_variation_duplicate_ajax_data', this.ajaxData);
        $(document).on('woocommerce_variations_added', '#variable_product_options', this.clean);
        $(document).on('woocommerce_variations_removed', '#woocommerce-product-data', this.clean);
        $(document).on('click', '#variation-duplicator-for-woocommerce-action-button', this.duplicate);

        var events = $._data(document.body, 'events')['change'];

        var input_change_callback = events.filter(function (event) {
          return event.selector === '#variable_product_options .woocommerce_variations :input';
        })[0]; // @TODO: We should add namespace support on event also

        $(document.body).off('change input', '#variable_product_options .woocommerce_variations :input');
        $(document.body).on('change input', '#variable_product_options .woocommerce_variations :input:not(.no-track-change)', input_change_callback.handler); // Re Init

        $('.wc-metaboxes-wrapper').on('click', 'button.add_variation_manually', function () {
          $('.variation-duplicator-for-woocommerce-notice').remove();
        });
        $('#woocommerce-product-data').on('woocommerce_variations_loaded', function () {
          _this3.setHowTo();

          $('#variation-duplicator-for-woocommerce-action-button').prop('disabled', true);
        }); // No longer exists on wc 7.8 ( Update: But again available on 8.0.0 )

        $('#variable_product_options').on('woocommerce_variations_added', function () {
          _this3.setHowTo(); //$('select.variation_actions').off('woo_variation_duplicate_ajax_data').on('woo_variation_duplicate_ajax_data', this.ajaxData)

        }).on('woocommerce_variations_input_changed', function (event) {
          var _this4 = this;

          // We wait for attaching out event for no change track
          _.delay(function () {
            $(_this4).find('.variation-needs-update input.variation_is_cloneable').prop('checked', false);
          }, 1);
        });
      }
    }, {
      key: "duplicate",
      value: function duplicate() {
        $('select#field_to_edit').val('woo_variation_duplicate').trigger('change');
      }
    }, {
      key: "setHowTo",
      value: function setHowTo() {
        var button = $('#variable_product_options_inner').find('#variation-duplicator-for-woocommerce-action-button');
        var actionsWrapper = $('#variable_product_options_inner .toolbar-top').find('.do_variation_action');
        var selectBox = $('select#field_to_edit'); //$('#variable_product_options_inner').find('.variation-duplicator-for-woocommerce-works').insertAfter(actionsWrapper)

        if (actionsWrapper.length > 0) {
          button.insertAfter(actionsWrapper);
        } else {
          button.insertAfter(selectBox);
        }
      }
    }, {
      key: "clean",
      value: function clean() {
        $('.woocommerce-notice-invalid-variation, .woo-variation-duplicator-notice').remove();
      }
    }, {
      key: "ajaxData",
      value: function ajaxData(data) {
        var clone = {
          items: [],
          times: 1,
          exceed: false
        };
        var $clonable = $('input.variation_is_cloneable:checked');
        var checked = $clonable.length;
        var variationsWrapper = $('#variable_product_options').find('.woocommerce_variations');

        if (checked < 1) {
          alert(WooVariationDuplicator.noCheckedText);
          return data['clone'] = {};
        }

        $clonable.each(function () {
          clone.items.push($(this).val());
        });

        if (clone.items.length > 0) {
          var times = Number(window.prompt(WooVariationDuplicator.limitText, 1));

          if (isNaN(times)) {
            return data['clone'] = {};
          } else {
            clone.times = times > Number(WooVariationDuplicator.limit) ? 1 : times;
            clone.exceed = times > Number(WooVariationDuplicator.limit);
          }
        } else {
          return data['clone'] = {};
        }

        var total = clone.times * clone.items.length;

        for (var $i = 0; $i < total; $i++) {
          // $('#variable_product_options').trigger('woocommerce_variations_added', 1)
          var totalVariation = parseInt(variationsWrapper.attr('data-total'), 10) + 1;
          variationsWrapper.attr('data-total', totalVariation);
        }

        return data['clone'] = clone;
      }
    }, {
      key: "cloneableClick",
      value: function cloneableClick(event) {
        if ($(this).is(':checked')) {
          $(this).closest('label.clone-checkbox').addClass('checked');
          $('#variation-duplicator-for-woocommerce-action-button').prop('disabled', false);
        } else {
          if ($('input.variation_is_cloneable:checked').length < 1) {
            $('#variation-duplicator-for-woocommerce-action-button').prop('disabled', true);
          }

          $(this).closest('label.clone-checkbox').removeClass('checked');
        }
      }
    }, {
      key: "cloneableChange",
      value: function cloneableChange(event) {
        $(this).closest('.wc-metaboxes-wrapper').find('.wc-metabox > .wc-metabox-content').hide();
        $(this).closest('.woocommerce_variations').find('.woocommerce_variation.open').removeClass('open').addClass('closed');
      }
    }]);

    return Variation_Duplicator_For_Woocommerce_Variation_Clone;
  }();

  Variation_Duplicator_For_Woocommerce_Variation_Image_Clone.init();
  Variation_Duplicator_For_Woocommerce_Variation_Clone.init();
});

/***/ }),

/***/ "./src/scss/variation-duplicator-for-woocommerce.scss":
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

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
/******/ 	!function() {
/******/ 		var deferred = [];
/******/ 		__webpack_require__.O = function(result, chunkIds, fn, priority) {
/******/ 			if(chunkIds) {
/******/ 				priority = priority || 0;
/******/ 				for(var i = deferred.length; i > 0 && deferred[i - 1][2] > priority; i--) deferred[i] = deferred[i - 1];
/******/ 				deferred[i] = [chunkIds, fn, priority];
/******/ 				return;
/******/ 			}
/******/ 			var notFulfilled = Infinity;
/******/ 			for (var i = 0; i < deferred.length; i++) {
/******/ 				var chunkIds = deferred[i][0];
/******/ 				var fn = deferred[i][1];
/******/ 				var priority = deferred[i][2];
/******/ 				var fulfilled = true;
/******/ 				for (var j = 0; j < chunkIds.length; j++) {
/******/ 					if ((priority & 1 === 0 || notFulfilled >= priority) && Object.keys(__webpack_require__.O).every(function(key) { return __webpack_require__.O[key](chunkIds[j]); })) {
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
/******/ 	/* webpack/runtime/jsonp chunk loading */
/******/ 	!function() {
/******/ 		// no baseURI
/******/ 		
/******/ 		// object to store loaded and loading chunks
/******/ 		// undefined = chunk not loaded, null = chunk preloaded/prefetched
/******/ 		// [resolve, reject, Promise] = chunk loading, 0 = chunk loaded
/******/ 		var installedChunks = {
/******/ 			"/assets/js/variation-duplicator-for-woocommerce": 0,
/******/ 			"assets/css/variation-duplicator-for-woocommerce": 0
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
/******/ 		__webpack_require__.O.j = function(chunkId) { return installedChunks[chunkId] === 0; };
/******/ 		
/******/ 		// install a JSONP callback for chunk loading
/******/ 		var webpackJsonpCallback = function(parentChunkLoadingFunction, data) {
/******/ 			var chunkIds = data[0];
/******/ 			var moreModules = data[1];
/******/ 			var runtime = data[2];
/******/ 			// add "moreModules" to the modules object,
/******/ 			// then flag all "chunkIds" as loaded and fire callback
/******/ 			var moduleId, chunkId, i = 0;
/******/ 			if(chunkIds.some(function(id) { return installedChunks[id] !== 0; })) {
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
/******/ 		var chunkLoadingGlobal = self["webpackChunkvariation_duplicator_for_woocommerce"] = self["webpackChunkvariation_duplicator_for_woocommerce"] || [];
/******/ 		chunkLoadingGlobal.forEach(webpackJsonpCallback.bind(null, 0));
/******/ 		chunkLoadingGlobal.push = webpackJsonpCallback.bind(null, chunkLoadingGlobal.push.bind(chunkLoadingGlobal));
/******/ 	}();
/******/ 	
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module depends on other loaded chunks and execution need to be delayed
/******/ 	__webpack_require__.O(undefined, ["assets/css/variation-duplicator-for-woocommerce"], function() { return __webpack_require__("./src/js/variation-duplicator-for-woocommerce.js"); })
/******/ 	var __webpack_exports__ = __webpack_require__.O(undefined, ["assets/css/variation-duplicator-for-woocommerce"], function() { return __webpack_require__("./src/scss/variation-duplicator-for-woocommerce.scss"); })
/******/ 	__webpack_exports__ = __webpack_require__.O(__webpack_exports__);
/******/ 	
/******/ })()
;