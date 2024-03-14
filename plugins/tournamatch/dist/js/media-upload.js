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
/******/ 	return __webpack_require__(__webpack_require__.s = 51);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./src/js/admin/media-upload.js":
/*!**************************************!*\
  !*** ./src/js/admin/media-upload.js ***!
  \**************************************/
/*! no static exports found */
/***/ (function(module, exports) {

/**
 * Admin media upload input handler.
 *
 * @link       https://www.tournamatch.com
 * @since      4.3.0
 *
 * @package    Tournamatch
 *
 */
(function ($) {
  'use strict';

  window.addEventListener('load', function () {
    // Uploading files
    var file_frame;
    var wp_media_post_id = wp.media.model.settings.post.id; // Store the old id

    jQuery('.trn-media-upload-button').each(function () {
      jQuery(this).on('click', function (event) {
        event.preventDefault();
        var post_id = jQuery(this).attr('data-post-id');
        var preview_id = jQuery(this).attr('data-preview-id');
        var input_id = jQuery(this).attr('data-input-id');
        var title = jQuery(this).attr('data-title');
        var button_text = jQuery(this).attr('data-button-text');

        if (!file_frame) {
          // Set the wp.media post id so the uploader grabs the ID we want when initialised
          if (post_id) {
            wp.media.model.settings.post.id = post_id;
          } // Create the media frame.


          file_frame = wp.media.frames.file_frame = wp.media({
            title: title,
            button: {
              text: button_text
            },
            multiple: false // Set to true to allow multiple files to be selected

          });
        } else {
          // Set the post ID to what we want
          if (post_id) {
            file_frame.uploader.uploader.param('post_id', post_id);
          }
        }

        file_frame.off('select'); // When an image is selected, run a callback.

        file_frame.on('select', function () {
          // We set multiple to false so only get one image from the uploader
          var attachment = file_frame.state().get('selection').first().toJSON(); // Do something with attachment.id and/or attachment.url here

          jQuery("#".concat(preview_id)).attr('src', attachment.url).removeClass('hidden');
          jQuery("#".concat(input_id)).val(attachment.id); // Restore the main post ID

          wp.media.model.settings.post.id = wp_media_post_id;
        }); // Finally, open the modal

        file_frame.open();
      }); // Restore the main ID when the add media button is pressed

      jQuery('a.add_media').on('click', function () {
        wp.media.model.settings.post.id = wp_media_post_id;
      });
    });
  }, false);
})(jQuery);

/***/ }),

/***/ 51:
/*!********************************************!*\
  !*** multi ./src/js/admin/media-upload.js ***!
  \********************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! C:\wamp\www\wordpress.dev\wp-content\plugins\tournamatch\src\js\admin\media-upload.js */"./src/js/admin/media-upload.js");


/***/ })

/******/ });
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vd2VicGFjay9ib290c3RyYXAiLCJ3ZWJwYWNrOi8vLy4vc3JjL2pzL2FkbWluL21lZGlhLXVwbG9hZC5qcyJdLCJuYW1lcyI6WyIkIiwid2luZG93IiwiYWRkRXZlbnRMaXN0ZW5lciIsImZpbGVfZnJhbWUiLCJ3cF9tZWRpYV9wb3N0X2lkIiwid3AiLCJtZWRpYSIsIm1vZGVsIiwic2V0dGluZ3MiLCJwb3N0IiwiaWQiLCJqUXVlcnkiLCJlYWNoIiwib24iLCJldmVudCIsInByZXZlbnREZWZhdWx0IiwicG9zdF9pZCIsImF0dHIiLCJwcmV2aWV3X2lkIiwiaW5wdXRfaWQiLCJ0aXRsZSIsImJ1dHRvbl90ZXh0IiwiZnJhbWVzIiwiYnV0dG9uIiwidGV4dCIsIm11bHRpcGxlIiwidXBsb2FkZXIiLCJwYXJhbSIsIm9mZiIsImF0dGFjaG1lbnQiLCJzdGF0ZSIsImdldCIsImZpcnN0IiwidG9KU09OIiwidXJsIiwicmVtb3ZlQ2xhc3MiLCJ2YWwiLCJvcGVuIl0sIm1hcHBpbmdzIjoiO1FBQUE7UUFDQTs7UUFFQTtRQUNBOztRQUVBO1FBQ0E7UUFDQTtRQUNBO1FBQ0E7UUFDQTtRQUNBO1FBQ0E7UUFDQTtRQUNBOztRQUVBO1FBQ0E7O1FBRUE7UUFDQTs7UUFFQTtRQUNBO1FBQ0E7OztRQUdBO1FBQ0E7O1FBRUE7UUFDQTs7UUFFQTtRQUNBO1FBQ0E7UUFDQSwwQ0FBMEMsZ0NBQWdDO1FBQzFFO1FBQ0E7O1FBRUE7UUFDQTtRQUNBO1FBQ0Esd0RBQXdELGtCQUFrQjtRQUMxRTtRQUNBLGlEQUFpRCxjQUFjO1FBQy9EOztRQUVBO1FBQ0E7UUFDQTtRQUNBO1FBQ0E7UUFDQTtRQUNBO1FBQ0E7UUFDQTtRQUNBO1FBQ0E7UUFDQSx5Q0FBeUMsaUNBQWlDO1FBQzFFLGdIQUFnSCxtQkFBbUIsRUFBRTtRQUNySTtRQUNBOztRQUVBO1FBQ0E7UUFDQTtRQUNBLDJCQUEyQiwwQkFBMEIsRUFBRTtRQUN2RCxpQ0FBaUMsZUFBZTtRQUNoRDtRQUNB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Q0E2Q3RDOztBQUNBRCxrQkFBVSxDQUFDa0MsSUFBWDtBQUNILE9BL0NELEVBRCtDLENBa0QvQzs7QUFDQTFCLFlBQU0sQ0FBQyxhQUFELENBQU4sQ0FBc0JFLEVBQXRCLENBQXlCLE9BQXpCLEVBQWtDLFlBQVk7QUFDMUNSLFVBQUUsQ0FBQ0MsS0FBSCxDQUFTQyxLQUFULENBQWVDLFFBQWYsQ0FBd0JDLElBQXhCLENBQTZCQyxFQUE3QixHQUFrQ04sZ0JBQWxDO0FBQ0gsT0FGRDtBQUdILEtBdEREO0FBdURILEdBN0RELEVBNkRHLEtBN0RIO0FBOERILENBakVELEVBaUVHTyxNQWpFSCxFIiwiZmlsZSI6Im1lZGlhLXVwbG9hZC5qcyIsInNvdXJjZXNDb250ZW50IjpbIiBcdC8vIFRoZSBtb2R1bGUgY2FjaGVcbiBcdHZhciBpbnN0YWxsZWRNb2R1bGVzID0ge307XG5cbiBcdC8vIFRoZSByZXF1aXJlIGZ1bmN0aW9uXG4gXHRmdW5jdGlvbiBfX3dlYnBhY2tfcmVxdWlyZV9fKG1vZHVsZUlkKSB7XG5cbiBcdFx0Ly8gQ2hlY2sgaWYgbW9kdWxlIGlzIGluIGNhY2hlXG4gXHRcdGlmKGluc3RhbGxlZE1vZHVsZXNbbW9kdWxlSWRdKSB7XG4gXHRcdFx0cmV0dXJuIGluc3RhbGxlZE1vZHVsZXNbbW9kdWxlSWRdLmV4cG9ydHM7XG4gXHRcdH1cbiBcdFx0Ly8gQ3JlYXRlIGEgbmV3IG1vZHVsZSAoYW5kIHB1dCBpdCBpbnRvIHRoZSBjYWNoZSlcbiBcdFx0dmFyIG1vZHVsZSA9IGluc3RhbGxlZE1vZHVsZXNbbW9kdWxlSWRdID0ge1xuIFx0XHRcdGk6IG1vZHVsZUlkLFxuIFx0XHRcdGw6IGZhbHNlLFxuIFx0XHRcdGV4cG9ydHM6IHt9XG4gXHRcdH07XG5cbiBcdFx0Ly8gRXhlY3V0ZSB0aGUgbW9kdWxlIGZ1bmN0aW9uXG4gXHRcdG1vZHVsZXNbbW9kdWxlSWRdLmNhbGwobW9kdWxlLmV4cG9ydHMsIG1vZHVsZSwgbW9kdWxlLmV4cG9ydHMsIF9fd2VicGFja19yZXF1aXJlX18pO1xuXG4gXHRcdC8vIEZsYWcgdGhlIG1vZHVsZSBhcyBsb2FkZWRcbiBcdFx0bW9kdWxlLmwgPSB0cnVlO1xuXG4gXHRcdC8vIFJldHVybiB0aGUgZXhwb3J0cyBvZiB0aGUgbW9kdWxlXG4gXHRcdHJldHVybiBtb2R1bGUuZXhwb3J0cztcbiBcdH1cblxuXG4gXHQvLyBleHBvc2UgdGhlIG1vZHVsZXMgb2JqZWN0IChfX3dlYnBhY2tfbW9kdWxlc19fKVxuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5tID0gbW9kdWxlcztcblxuIFx0Ly8gZXhwb3NlIHRoZSBtb2R1bGUgY2FjaGVcbiBcdF9fd2VicGFja19yZXF1aXJlX18uYyA9IGluc3RhbGxlZE1vZHVsZXM7XG5cbiBcdC8vIGRlZmluZSBnZXR0ZXIgZnVuY3Rpb24gZm9yIGhhcm1vbnkgZXhwb3J0c1xuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5kID0gZnVuY3Rpb24oZXhwb3J0cywgbmFtZSwgZ2V0dGVyKSB7XG4gXHRcdGlmKCFfX3dlYnBhY2tfcmVxdWlyZV9fLm8oZXhwb3J0cywgbmFtZSkpIHtcbiBcdFx0XHRPYmplY3QuZGVmaW5lUHJvcGVydHkoZXhwb3J0cywgbmFtZSwgeyBlbnVtZXJhYmxlOiB0cnVlLCBnZXQ6IGdldHRlciB9KTtcbiBcdFx0fVxuIFx0fTtcblxuIFx0Ly8gZGVmaW5lIF9fZXNNb2R1bGUgb24gZXhwb3J0c1xuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5yID0gZnVuY3Rpb24oZXhwb3J0cykge1xuIFx0XHRpZih0eXBlb2YgU3ltYm9sICE9PSAndW5kZWZpbmVkJyAmJiBTeW1ib2wudG9TdHJpbmdUYWcpIHtcbiBcdFx0XHRPYmplY3QuZGVmaW5lUHJvcGVydHkoZXhwb3J0cywgU3ltYm9sLnRvU3RyaW5nVGFnLCB7IHZhbHVlOiAnTW9kdWxlJyB9KTtcbiBcdFx0fVxuIFx0XHRPYmplY3QuZGVmaW5lUHJvcGVydHkoZXhwb3J0cywgJ19fZXNNb2R1bGUnLCB7IHZhbHVlOiB0cnVlIH0pO1xuIFx0fTtcblxuIFx0Ly8gY3JlYXRlIGEgZmFrZSBuYW1lc3BhY2Ugb2JqZWN0XG4gXHQvLyBtb2RlICYgMTogdmFsdWUgaXMgYSBtb2R1bGUgaWQsIHJlcXVpcmUgaXRcbiBcdC8vIG1vZGUgJiAyOiBtZXJnZSBhbGwgcHJvcGVydGllcyBvZiB2YWx1ZSBpbnRvIHRoZSBuc1xuIFx0Ly8gbW9kZSAmIDQ6IHJldHVybiB2YWx1ZSB3aGVuIGFscmVhZHkgbnMgb2JqZWN0XG4gXHQvLyBtb2RlICYgOHwxOiBiZWhhdmUgbGlrZSByZXF1aXJlXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLnQgPSBmdW5jdGlvbih2YWx1ZSwgbW9kZSkge1xuIFx0XHRpZihtb2RlICYgMSkgdmFsdWUgPSBfX3dlYnBhY2tfcmVxdWlyZV9fKHZhbHVlKTtcbiBcdFx0aWYobW9kZSAmIDgpIHJldHVybiB2YWx1ZTtcbiBcdFx0aWYoKG1vZGUgJiA0KSAmJiB0eXBlb2YgdmFsdWUgPT09ICdvYmplY3QnICYmIHZhbHVlICYmIHZhbHVlLl9fZXNNb2R1bGUpIHJldHVybiB2YWx1ZTtcbiBcdFx0dmFyIG5zID0gT2JqZWN0LmNyZWF0ZShudWxsKTtcbiBcdFx0X193ZWJwYWNrX3JlcXVpcmVfXy5yKG5zKTtcbiBcdFx0T2JqZWN0LmRlZmluZVByb3BlcnR5KG5zLCAnZGVmYXVsdCcsIHsgZW51bWVyYWJsZTogdHJ1ZSwgdmFsdWU6IHZhbHVlIH0pO1xuIFx0XHRpZihtb2RlICYgMiAmJiB0eXBlb2YgdmFsdWUgIT0gJ3N0cmluZycpIGZvcih2YXIga2V5IGluIHZhbHVlKSBfX3dlYnBhY2tfcmVxdWlyZV9fLmQobnMsIGtleSwgZnVuY3Rpb24oa2V5KSB7IHJldHVybiB2YWx1ZVtrZXldOyB9LmJpbmQobnVsbCwga2V5KSk7XG4gXHRcdHJldHVybiBucztcbiBcdH07XG5cbiBcdC8vIGdldERlZmF1bHRFeHBvcnQgZnVuY3Rpb24gZm9yIGNvbXBhdGliaWxpdHkgd2l0aCBub24taGFybW9ueSBtb2R1bGVzXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLm4gPSBmdW5jdGlvbihtb2R1bGUpIHtcbiBcdFx0dmFyIGdldHRlciA9IG1vZHVsZSAmJiBtb2R1bGUuX19lc01vZHVsZSA/XG4gXHRcdFx0ZnVuY3Rpb24gZ2V0RGVmYXVsdCgpIHsgcmV0dXJuIG1vZHVsZVsnZGVmYXVsdCddOyB9IDpcbiBcdFx0XHRmdW5jdGlvbiBnZXRNb2R1bGVFeHBvcnRzKCkgeyByZXR1cm4gbW9kdWxlOyB9O1xuIFx0XHRfX3dlYnBhY2tfcmVxdWlyZV9fLmQoZ2V0dGVyLCAnYScsIGdldHRlcik7XG4gXHRcdHJldHVybiBnZXR0ZXI7XG4gXHR9O1xuXG4gXHQvLyBPYmplY3QucHJvdG90eXBlLmhhc093blByb3BlcnR5LmNhbGxcbiBcdF9fd2VicGFja19yZXF1aXJlX18ubyA9IGZ1bmN0aW9uKG9iamVjdCwgcHJvcGVydHkpIHsgcmV0dXJuIE9iamVjdC5wcm90b3R5cGUuaGFzT3duUHJvcGVydHkuY2FsbChvYmplY3QsIHByb3BlcnR5KTsgfTtcblxuIFx0Ly8gX193ZWJwYWNrX3B1YmxpY19wYXRoX19cbiBcdF9fd2VicGFja19yZXF1aXJlX18ucCA9IFwiXCI7XG5cblxuIFx0Ly8gTG9hZCBlbnRyeSBtb2R1bGUgYW5kIHJldHVybiBleHBvcnRzXG4gXHRyZXR1cm4gX193ZWJwYWNrX3JlcXVpcmVfXyhfX3dlYnBhY2tfcmVxdWlyZV9fLnMgPSA1MSk7XG4iLCIvKipcclxuICogQWRtaW4gbWVkaWEgdXBsb2FkIGlucHV0IGhhbmRsZXIuXHJcbiAqXHJcbiAqIEBsaW5rICAgICAgIGh0dHBzOi8vd3d3LnRvdXJuYW1hdGNoLmNvbVxyXG4gKiBAc2luY2UgICAgICA0LjMuMFxyXG4gKlxyXG4gKiBAcGFja2FnZSAgICBUb3VybmFtYXRjaFxyXG4gKlxyXG4gKi9cclxuKGZ1bmN0aW9uICgkKSB7XHJcbiAgICAndXNlIHN0cmljdCc7XHJcblxyXG4gICAgd2luZG93LmFkZEV2ZW50TGlzdGVuZXIoJ2xvYWQnLCBmdW5jdGlvbiAoKSB7XHJcblxyXG4gICAgICAgIC8vIFVwbG9hZGluZyBmaWxlc1xyXG4gICAgICAgIGxldCBmaWxlX2ZyYW1lO1xyXG4gICAgICAgIGxldCB3cF9tZWRpYV9wb3N0X2lkID0gd3AubWVkaWEubW9kZWwuc2V0dGluZ3MucG9zdC5pZDsgLy8gU3RvcmUgdGhlIG9sZCBpZFxyXG5cclxuICAgICAgICBqUXVlcnkoJy50cm4tbWVkaWEtdXBsb2FkLWJ1dHRvbicpLmVhY2goZnVuY3Rpb24oKSB7XHJcbiAgICAgICAgICAgIGpRdWVyeSh0aGlzKS5vbignY2xpY2snLCBmdW5jdGlvbiAoZXZlbnQpIHtcclxuICAgICAgICAgICAgICAgIGV2ZW50LnByZXZlbnREZWZhdWx0KCk7XHJcblxyXG4gICAgICAgICAgICAgICAgbGV0IHBvc3RfaWQgPSBqUXVlcnkodGhpcykuYXR0cignZGF0YS1wb3N0LWlkJyk7XHJcbiAgICAgICAgICAgICAgICBsZXQgcHJldmlld19pZCA9IGpRdWVyeSh0aGlzKS5hdHRyKCdkYXRhLXByZXZpZXctaWQnKTtcclxuICAgICAgICAgICAgICAgIGxldCBpbnB1dF9pZCA9IGpRdWVyeSh0aGlzKS5hdHRyKCdkYXRhLWlucHV0LWlkJyk7XHJcbiAgICAgICAgICAgICAgICBsZXQgdGl0bGUgPSBqUXVlcnkodGhpcykuYXR0cignZGF0YS10aXRsZScpO1xyXG4gICAgICAgICAgICAgICAgbGV0IGJ1dHRvbl90ZXh0ID0galF1ZXJ5KHRoaXMpLmF0dHIoJ2RhdGEtYnV0dG9uLXRleHQnKTtcclxuXHJcbiAgICAgICAgICAgICAgICBpZiAoIWZpbGVfZnJhbWUpIHtcclxuICAgICAgICAgICAgICAgICAgICAvLyBTZXQgdGhlIHdwLm1lZGlhIHBvc3QgaWQgc28gdGhlIHVwbG9hZGVyIGdyYWJzIHRoZSBJRCB3ZSB3YW50IHdoZW4gaW5pdGlhbGlzZWRcclxuICAgICAgICAgICAgICAgICAgICBpZiAocG9zdF9pZCkge1xyXG4gICAgICAgICAgICAgICAgICAgICAgICB3cC5tZWRpYS5tb2RlbC5zZXR0aW5ncy5wb3N0LmlkID0gcG9zdF9pZDtcclxuICAgICAgICAgICAgICAgICAgICB9XHJcblxyXG4gICAgICAgICAgICAgICAgICAgIC8vIENyZWF0ZSB0aGUgbWVkaWEgZnJhbWUuXHJcbiAgICAgICAgICAgICAgICAgICAgZmlsZV9mcmFtZSA9IHdwLm1lZGlhLmZyYW1lcy5maWxlX2ZyYW1lID0gd3AubWVkaWEoe1xyXG4gICAgICAgICAgICAgICAgICAgICAgICB0aXRsZTogdGl0bGUsXHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGJ1dHRvbjoge1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgdGV4dDogYnV0dG9uX3RleHQsXHJcbiAgICAgICAgICAgICAgICAgICAgICAgIH0sXHJcbiAgICAgICAgICAgICAgICAgICAgICAgIG11bHRpcGxlOiBmYWxzZVx0Ly8gU2V0IHRvIHRydWUgdG8gYWxsb3cgbXVsdGlwbGUgZmlsZXMgdG8gYmUgc2VsZWN0ZWRcclxuICAgICAgICAgICAgICAgICAgICB9KTtcclxuICAgICAgICAgICAgICAgIH0gZWxzZSB7XHJcbiAgICAgICAgICAgICAgICAgICAgLy8gU2V0IHRoZSBwb3N0IElEIHRvIHdoYXQgd2Ugd2FudFxyXG4gICAgICAgICAgICAgICAgICAgIGlmIChwb3N0X2lkKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGZpbGVfZnJhbWUudXBsb2FkZXIudXBsb2FkZXIucGFyYW0oJ3Bvc3RfaWQnLCBwb3N0X2lkKTtcclxuICAgICAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgICAgICB9XHJcblxyXG4gICAgICAgICAgICAgICAgZmlsZV9mcmFtZS5vZmYoJ3NlbGVjdCcpO1xyXG5cclxuICAgICAgICAgICAgICAgIC8vIFdoZW4gYW4gaW1hZ2UgaXMgc2VsZWN0ZWQsIHJ1biBhIGNhbGxiYWNrLlxyXG4gICAgICAgICAgICAgICAgZmlsZV9mcmFtZS5vbignc2VsZWN0JywgZnVuY3Rpb24gKCkge1xyXG4gICAgICAgICAgICAgICAgICAgIC8vIFdlIHNldCBtdWx0aXBsZSB0byBmYWxzZSBzbyBvbmx5IGdldCBvbmUgaW1hZ2UgZnJvbSB0aGUgdXBsb2FkZXJcclxuICAgICAgICAgICAgICAgICAgICBsZXQgYXR0YWNobWVudCA9IGZpbGVfZnJhbWUuc3RhdGUoKS5nZXQoJ3NlbGVjdGlvbicpLmZpcnN0KCkudG9KU09OKCk7XHJcblxyXG4gICAgICAgICAgICAgICAgICAgIC8vIERvIHNvbWV0aGluZyB3aXRoIGF0dGFjaG1lbnQuaWQgYW5kL29yIGF0dGFjaG1lbnQudXJsIGhlcmVcclxuICAgICAgICAgICAgICAgICAgICBqUXVlcnkoYCMke3ByZXZpZXdfaWR9YCkuYXR0cignc3JjJywgYXR0YWNobWVudC51cmwpLnJlbW92ZUNsYXNzKCdoaWRkZW4nKTtcclxuICAgICAgICAgICAgICAgICAgICBqUXVlcnkoYCMke2lucHV0X2lkfWApLnZhbChhdHRhY2htZW50LmlkKTtcclxuXHJcbiAgICAgICAgICAgICAgICAgICAgLy8gUmVzdG9yZSB0aGUgbWFpbiBwb3N0IElEXHJcbiAgICAgICAgICAgICAgICAgICAgd3AubWVkaWEubW9kZWwuc2V0dGluZ3MucG9zdC5pZCA9IHdwX21lZGlhX3Bvc3RfaWQ7XHJcbiAgICAgICAgICAgICAgICB9KTtcclxuXHJcbiAgICAgICAgICAgICAgICAvLyBGaW5hbGx5LCBvcGVuIHRoZSBtb2RhbFxyXG4gICAgICAgICAgICAgICAgZmlsZV9mcmFtZS5vcGVuKCk7XHJcbiAgICAgICAgICAgIH0pO1xyXG5cclxuICAgICAgICAgICAgLy8gUmVzdG9yZSB0aGUgbWFpbiBJRCB3aGVuIHRoZSBhZGQgbWVkaWEgYnV0dG9uIGlzIHByZXNzZWRcclxuICAgICAgICAgICAgalF1ZXJ5KCdhLmFkZF9tZWRpYScpLm9uKCdjbGljaycsIGZ1bmN0aW9uICgpIHtcclxuICAgICAgICAgICAgICAgIHdwLm1lZGlhLm1vZGVsLnNldHRpbmdzLnBvc3QuaWQgPSB3cF9tZWRpYV9wb3N0X2lkO1xyXG4gICAgICAgICAgICB9KTtcclxuICAgICAgICB9KTtcclxuICAgIH0sIGZhbHNlKTtcclxufSkoalF1ZXJ5KTsiXSwic291cmNlUm9vdCI6IiJ9