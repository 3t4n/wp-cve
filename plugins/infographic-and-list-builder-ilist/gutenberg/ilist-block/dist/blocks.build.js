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
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
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
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 0);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/*!***********************!*\
  !*** ./src/blocks.js ***!
  \***********************/
/*! no exports provided */
/*! all exports used */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("Object.defineProperty(__webpack_exports__, \"__esModule\", { value: true });\n/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__block_block_js__ = __webpack_require__(/*! ./block/block.js */ 1);\n/**\n * Gutenberg Blocks\n *\n * All blocks related JavaScript files should be imported here.\n * You can create a new block folder in this dir and include code\n * for that block here as well.\n *\n * All blocks should be included here since this is the file that\n * Webpack is compiling as the input file.\n */\n\n//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiMC5qcyIsInNvdXJjZXMiOlsid2VicGFjazovLy8uL3NyYy9ibG9ja3MuanM/N2I1YiJdLCJzb3VyY2VzQ29udGVudCI6WyIvKipcbiAqIEd1dGVuYmVyZyBCbG9ja3NcbiAqXG4gKiBBbGwgYmxvY2tzIHJlbGF0ZWQgSmF2YVNjcmlwdCBmaWxlcyBzaG91bGQgYmUgaW1wb3J0ZWQgaGVyZS5cbiAqIFlvdSBjYW4gY3JlYXRlIGEgbmV3IGJsb2NrIGZvbGRlciBpbiB0aGlzIGRpciBhbmQgaW5jbHVkZSBjb2RlXG4gKiBmb3IgdGhhdCBibG9jayBoZXJlIGFzIHdlbGwuXG4gKlxuICogQWxsIGJsb2NrcyBzaG91bGQgYmUgaW5jbHVkZWQgaGVyZSBzaW5jZSB0aGlzIGlzIHRoZSBmaWxlIHRoYXRcbiAqIFdlYnBhY2sgaXMgY29tcGlsaW5nIGFzIHRoZSBpbnB1dCBmaWxlLlxuICovXG5cbmltcG9ydCAnLi9ibG9jay9ibG9jay5qcyc7XG5cblxuLy8vLy8vLy8vLy8vLy8vLy8vXG4vLyBXRUJQQUNLIEZPT1RFUlxuLy8gLi9zcmMvYmxvY2tzLmpzXG4vLyBtb2R1bGUgaWQgPSAwXG4vLyBtb2R1bGUgY2h1bmtzID0gMCJdLCJtYXBwaW5ncyI6IkFBQUE7QUFBQTtBQUFBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7Iiwic291cmNlUm9vdCI6IiJ9\n//# sourceURL=webpack-internal:///0\n");

/***/ }),
/* 1 */
/*!****************************!*\
  !*** ./src/block/block.js ***!
  \****************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__style_scss__ = __webpack_require__(/*! ./style.scss */ 2);\n/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__style_scss___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0__style_scss__);\n/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__editor_scss__ = __webpack_require__(/*! ./editor.scss */ 3);\n/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__editor_scss___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_1__editor_scss__);\n/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__wordpress_components__ = __webpack_require__(/*! @wordpress/components */ 4);\n/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__wordpress_components___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_2__wordpress_components__);\n/**\n * BLOCK: ilist-block\n *\n * Registering a basic block with Gutenberg.\n * Simple block, renders and saves the same content without any interactivity.\n */\n\n//  Import CSS.\n\n\n\nvar __ = wp.i18n.__; // Import __() from wp.i18n\n\nvar registerBlockType = wp.blocks.registerBlockType; // Import registerBlockType() from wp.blocks\n\nvar RichText = wp.editor.RichText;\n\n\n\nfunction Qcopd_ilist_Shortcode_Preview(_ref) {\n\tvar shortcode = _ref.shortcode;\n\n\tif (shortcode.length > 0) {\n\t\treturn wp.element.createElement(\n\t\t\t'div',\n\t\t\tnull,\n\t\t\tshortcode\n\t\t);\n\t} else {\n\t\treturn '';\n\t}\n}\nfunction Qcopd_ilist_Shortcode_Preview_edit(_ref2) {\n\tvar shortcode = _ref2.shortcode;\n\n\tif (shortcode.length > 0) {\n\t\treturn wp.element.createElement(\n\t\t\t'textarea',\n\t\t\t{ className: 'editor-plain-text input-control' },\n\t\t\tshortcode\n\t\t);\n\t} else {\n\t\treturn '';\n\t}\n}\n\nregisterBlockType('cgb/block-ilist-block', {\n\n\ttitle: __('iList - Shortcode Maker'), // Block title.\n\ticon: 'list-view', // Block icon from Dashicons → https://developer.wordpress.org/resource/dashicons/.\n\tcategory: 'common', // Block category — Group blocks together based on common traits E.g. common, formatting, layout widgets, embed.\n\tkeywords: [__('iList - Shortcode Maker'), __('iList - Shortcode Maker')],\n\n\tattributes: {\n\t\tshortcode: {\n\t\t\ttype: 'string',\n\t\t\tdefault: ''\n\t\t}\n\t},\n\n\tedit: function edit(props) {\n\t\tvar shortcode = props.attributes.shortcode,\n\t\t    setAttributes = props.setAttributes;\n\n\t\tvar cnt = 0;\n\n\t\tfunction showShortcodeModal(e) {\n\t\t\tjQuery('#ilist_shortcode_generator_meta').prop('disabled', true);\n\t\t\tjQuery(e.target).addClass('currently_editing');\n\t\t\tjQuery.post(ajaxurl, {\n\t\t\t\taction: 'show_shortcodes'\n\n\t\t\t}, function (data) {\n\t\t\t\t//console.log(jQuery(data).find('#qcsld_add_shortcode').attr('value'));\n\t\t\t\tif (jQuery('#ilist-modal').length < 1) {\n\t\t\t\t\tjQuery('#wpwrap').append(data);\n\t\t\t\t}\n\t\t\t});\n\t\t}\n\n\t\tfunction insertShortCode(e) {\n\t\t\tvar shortcode = jQuery('#wpwrap').find('.ilist_shortcode_modal #ilist_shortcode_container').val();\n\t\t\tsetAttributes({ shortcode: shortcode });\n\t\t\tconsole.log(shortcode);\n\t\t}\n\n\t\tjQuery(document).on('click', '.ilist_copy_close', function (e) {\n\t\t\te.preventDefault();\n\t\t\tjQuery('.currently_editing').next('#ilist_insert_shortcode').trigger('click');\n\t\t\tjQuery(document).find('.ilist_shortcode_modal .ilist-modal-content .close').trigger('click');\n\t\t\t//const shortdata = jQuery(this).attr('short-data');\n\t\t\t//setAttributes( { shortcode: shortdata } );\n\t\t});\n\n\t\tjQuery(document).on('click', '.ilist_shortcode_modal .ilist-modal-content .close', function () {\n\t\t\tjQuery('.currently_editing').removeClass('currently_editing');\n\t\t\tjQuery('#ilist_shortcode_generator_meta').prop('disabled', false);\n\t\t\tjQuery(this).parent().parent().remove();\n\t\t});\n\n\t\treturn wp.element.createElement(\n\t\t\t'div',\n\t\t\t{ className: props.className },\n\t\t\twp.element.createElement('input', { type: 'button', id: 'ilist_shortcode_generator_meta', onClick: showShortcodeModal, className: 'button button-primary button-large', value: 'Generate iList Shortcode' }),\n\t\t\twp.element.createElement('input', { type: 'button', id: 'ilist_insert_shortcode', onClick: insertShortCode, className: 'button button-primary button-large', value: 'Test iList Shortcode' }),\n\t\t\twp.element.createElement('br', null),\n\t\t\tshortcode\n\t\t);\n\t},\n\n\tsave: function save(props) {\n\t\tvar shortcode = props.attributes.shortcode;\n\n\t\treturn wp.element.createElement(\n\t\t\t'div',\n\t\t\tnull,\n\t\t\twp.element.createElement(Qcopd_ilist_Shortcode_Preview, { shortcode: shortcode })\n\t\t);\n\t}\n});//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiMS5qcyIsInNvdXJjZXMiOlsid2VicGFjazovLy8uL3NyYy9ibG9jay9ibG9jay5qcz85MjFkIl0sInNvdXJjZXNDb250ZW50IjpbIi8qKlxuICogQkxPQ0s6IGlsaXN0LWJsb2NrXG4gKlxuICogUmVnaXN0ZXJpbmcgYSBiYXNpYyBibG9jayB3aXRoIEd1dGVuYmVyZy5cbiAqIFNpbXBsZSBibG9jaywgcmVuZGVycyBhbmQgc2F2ZXMgdGhlIHNhbWUgY29udGVudCB3aXRob3V0IGFueSBpbnRlcmFjdGl2aXR5LlxuICovXG5cbi8vICBJbXBvcnQgQ1NTLlxuaW1wb3J0ICcuL3N0eWxlLnNjc3MnO1xuaW1wb3J0ICcuL2VkaXRvci5zY3NzJztcblxudmFyIF9fID0gd3AuaTE4bi5fXzsgLy8gSW1wb3J0IF9fKCkgZnJvbSB3cC5pMThuXG5cbnZhciByZWdpc3RlckJsb2NrVHlwZSA9IHdwLmJsb2Nrcy5yZWdpc3RlckJsb2NrVHlwZTsgLy8gSW1wb3J0IHJlZ2lzdGVyQmxvY2tUeXBlKCkgZnJvbSB3cC5ibG9ja3NcblxudmFyIFJpY2hUZXh0ID0gd3AuZWRpdG9yLlJpY2hUZXh0O1xuXG5pbXBvcnQgeyBTZXJ2ZXJTaWRlUmVuZGVyIH0gZnJvbSAnQHdvcmRwcmVzcy9jb21wb25lbnRzJztcblxuZnVuY3Rpb24gUWNvcGRfaWxpc3RfU2hvcnRjb2RlX1ByZXZpZXcoX3JlZikge1xuXHR2YXIgc2hvcnRjb2RlID0gX3JlZi5zaG9ydGNvZGU7XG5cblx0aWYgKHNob3J0Y29kZS5sZW5ndGggPiAwKSB7XG5cdFx0cmV0dXJuIHdwLmVsZW1lbnQuY3JlYXRlRWxlbWVudChcblx0XHRcdCdkaXYnLFxuXHRcdFx0bnVsbCxcblx0XHRcdHNob3J0Y29kZVxuXHRcdCk7XG5cdH0gZWxzZSB7XG5cdFx0cmV0dXJuICcnO1xuXHR9XG59XG5mdW5jdGlvbiBRY29wZF9pbGlzdF9TaG9ydGNvZGVfUHJldmlld19lZGl0KF9yZWYyKSB7XG5cdHZhciBzaG9ydGNvZGUgPSBfcmVmMi5zaG9ydGNvZGU7XG5cblx0aWYgKHNob3J0Y29kZS5sZW5ndGggPiAwKSB7XG5cdFx0cmV0dXJuIHdwLmVsZW1lbnQuY3JlYXRlRWxlbWVudChcblx0XHRcdCd0ZXh0YXJlYScsXG5cdFx0XHR7IGNsYXNzTmFtZTogJ2VkaXRvci1wbGFpbi10ZXh0IGlucHV0LWNvbnRyb2wnIH0sXG5cdFx0XHRzaG9ydGNvZGVcblx0XHQpO1xuXHR9IGVsc2Uge1xuXHRcdHJldHVybiAnJztcblx0fVxufVxuXG5yZWdpc3RlckJsb2NrVHlwZSgnY2diL2Jsb2NrLWlsaXN0LWJsb2NrJywge1xuXG5cdHRpdGxlOiBfXygnaUxpc3QgLSBTaG9ydGNvZGUgTWFrZXInKSwgLy8gQmxvY2sgdGl0bGUuXG5cdGljb246ICdsaXN0LXZpZXcnLCAvLyBCbG9jayBpY29uIGZyb20gRGFzaGljb25zIOKGkiBodHRwczovL2RldmVsb3Blci53b3JkcHJlc3Mub3JnL3Jlc291cmNlL2Rhc2hpY29ucy8uXG5cdGNhdGVnb3J5OiAnY29tbW9uJywgLy8gQmxvY2sgY2F0ZWdvcnkg4oCUIEdyb3VwIGJsb2NrcyB0b2dldGhlciBiYXNlZCBvbiBjb21tb24gdHJhaXRzIEUuZy4gY29tbW9uLCBmb3JtYXR0aW5nLCBsYXlvdXQgd2lkZ2V0cywgZW1iZWQuXG5cdGtleXdvcmRzOiBbX18oJ2lMaXN0IC0gU2hvcnRjb2RlIE1ha2VyJyksIF9fKCdpTGlzdCAtIFNob3J0Y29kZSBNYWtlcicpXSxcblxuXHRhdHRyaWJ1dGVzOiB7XG5cdFx0c2hvcnRjb2RlOiB7XG5cdFx0XHR0eXBlOiAnc3RyaW5nJyxcblx0XHRcdGRlZmF1bHQ6ICcnXG5cdFx0fVxuXHR9LFxuXG5cdGVkaXQ6IGZ1bmN0aW9uIGVkaXQocHJvcHMpIHtcblx0XHR2YXIgc2hvcnRjb2RlID0gcHJvcHMuYXR0cmlidXRlcy5zaG9ydGNvZGUsXG5cdFx0ICAgIHNldEF0dHJpYnV0ZXMgPSBwcm9wcy5zZXRBdHRyaWJ1dGVzO1xuXG5cdFx0dmFyIGNudCA9IDA7XG5cblx0XHRmdW5jdGlvbiBzaG93U2hvcnRjb2RlTW9kYWwoZSkge1xuXHRcdFx0alF1ZXJ5KCcjaWxpc3Rfc2hvcnRjb2RlX2dlbmVyYXRvcl9tZXRhJykucHJvcCgnZGlzYWJsZWQnLCB0cnVlKTtcblx0XHRcdGpRdWVyeShlLnRhcmdldCkuYWRkQ2xhc3MoJ2N1cnJlbnRseV9lZGl0aW5nJyk7XG5cdFx0XHRqUXVlcnkucG9zdChhamF4dXJsLCB7XG5cdFx0XHRcdGFjdGlvbjogJ3Nob3dfc2hvcnRjb2RlcydcblxuXHRcdFx0fSwgZnVuY3Rpb24gKGRhdGEpIHtcblx0XHRcdFx0Ly9jb25zb2xlLmxvZyhqUXVlcnkoZGF0YSkuZmluZCgnI3Fjc2xkX2FkZF9zaG9ydGNvZGUnKS5hdHRyKCd2YWx1ZScpKTtcblx0XHRcdFx0aWYgKGpRdWVyeSgnI2lsaXN0LW1vZGFsJykubGVuZ3RoIDwgMSkge1xuXHRcdFx0XHRcdGpRdWVyeSgnI3dwd3JhcCcpLmFwcGVuZChkYXRhKTtcblx0XHRcdFx0fVxuXHRcdFx0fSk7XG5cdFx0fVxuXG5cdFx0ZnVuY3Rpb24gaW5zZXJ0U2hvcnRDb2RlKGUpIHtcblx0XHRcdHZhciBzaG9ydGNvZGUgPSBqUXVlcnkoJyN3cHdyYXAnKS5maW5kKCcuaWxpc3Rfc2hvcnRjb2RlX21vZGFsICNpbGlzdF9zaG9ydGNvZGVfY29udGFpbmVyJykudmFsKCk7XG5cdFx0XHRzZXRBdHRyaWJ1dGVzKHsgc2hvcnRjb2RlOiBzaG9ydGNvZGUgfSk7XG5cdFx0XHRjb25zb2xlLmxvZyhzaG9ydGNvZGUpO1xuXHRcdH1cblxuXHRcdGpRdWVyeShkb2N1bWVudCkub24oJ2NsaWNrJywgJy5pbGlzdF9jb3B5X2Nsb3NlJywgZnVuY3Rpb24gKGUpIHtcblx0XHRcdGUucHJldmVudERlZmF1bHQoKTtcblx0XHRcdGpRdWVyeSgnLmN1cnJlbnRseV9lZGl0aW5nJykubmV4dCgnI2lsaXN0X2luc2VydF9zaG9ydGNvZGUnKS50cmlnZ2VyKCdjbGljaycpO1xuXHRcdFx0alF1ZXJ5KGRvY3VtZW50KS5maW5kKCcuaWxpc3Rfc2hvcnRjb2RlX21vZGFsIC5pbGlzdC1tb2RhbC1jb250ZW50IC5jbG9zZScpLnRyaWdnZXIoJ2NsaWNrJyk7XG5cdFx0XHQvL2NvbnN0IHNob3J0ZGF0YSA9IGpRdWVyeSh0aGlzKS5hdHRyKCdzaG9ydC1kYXRhJyk7XG5cdFx0XHQvL3NldEF0dHJpYnV0ZXMoIHsgc2hvcnRjb2RlOiBzaG9ydGRhdGEgfSApO1xuXHRcdH0pO1xuXG5cdFx0alF1ZXJ5KGRvY3VtZW50KS5vbignY2xpY2snLCAnLmlsaXN0X3Nob3J0Y29kZV9tb2RhbCAuaWxpc3QtbW9kYWwtY29udGVudCAuY2xvc2UnLCBmdW5jdGlvbiAoKSB7XG5cdFx0XHRqUXVlcnkoJy5jdXJyZW50bHlfZWRpdGluZycpLnJlbW92ZUNsYXNzKCdjdXJyZW50bHlfZWRpdGluZycpO1xuXHRcdFx0alF1ZXJ5KCcjaWxpc3Rfc2hvcnRjb2RlX2dlbmVyYXRvcl9tZXRhJykucHJvcCgnZGlzYWJsZWQnLCBmYWxzZSk7XG5cdFx0XHRqUXVlcnkodGhpcykucGFyZW50KCkucGFyZW50KCkucmVtb3ZlKCk7XG5cdFx0fSk7XG5cblx0XHRyZXR1cm4gd3AuZWxlbWVudC5jcmVhdGVFbGVtZW50KFxuXHRcdFx0J2RpdicsXG5cdFx0XHR7IGNsYXNzTmFtZTogcHJvcHMuY2xhc3NOYW1lIH0sXG5cdFx0XHR3cC5lbGVtZW50LmNyZWF0ZUVsZW1lbnQoJ2lucHV0JywgeyB0eXBlOiAnYnV0dG9uJywgaWQ6ICdpbGlzdF9zaG9ydGNvZGVfZ2VuZXJhdG9yX21ldGEnLCBvbkNsaWNrOiBzaG93U2hvcnRjb2RlTW9kYWwsIGNsYXNzTmFtZTogJ2J1dHRvbiBidXR0b24tcHJpbWFyeSBidXR0b24tbGFyZ2UnLCB2YWx1ZTogJ0dlbmVyYXRlIGlMaXN0IFNob3J0Y29kZScgfSksXG5cdFx0XHR3cC5lbGVtZW50LmNyZWF0ZUVsZW1lbnQoJ2lucHV0JywgeyB0eXBlOiAnYnV0dG9uJywgaWQ6ICdpbGlzdF9pbnNlcnRfc2hvcnRjb2RlJywgb25DbGljazogaW5zZXJ0U2hvcnRDb2RlLCBjbGFzc05hbWU6ICdidXR0b24gYnV0dG9uLXByaW1hcnkgYnV0dG9uLWxhcmdlJywgdmFsdWU6ICdUZXN0IGlMaXN0IFNob3J0Y29kZScgfSksXG5cdFx0XHR3cC5lbGVtZW50LmNyZWF0ZUVsZW1lbnQoJ2JyJywgbnVsbCksXG5cdFx0XHRzaG9ydGNvZGVcblx0XHQpO1xuXHR9LFxuXG5cdHNhdmU6IGZ1bmN0aW9uIHNhdmUocHJvcHMpIHtcblx0XHR2YXIgc2hvcnRjb2RlID0gcHJvcHMuYXR0cmlidXRlcy5zaG9ydGNvZGU7XG5cblx0XHRyZXR1cm4gd3AuZWxlbWVudC5jcmVhdGVFbGVtZW50KFxuXHRcdFx0J2RpdicsXG5cdFx0XHRudWxsLFxuXHRcdFx0d3AuZWxlbWVudC5jcmVhdGVFbGVtZW50KFFjb3BkX2lsaXN0X1Nob3J0Y29kZV9QcmV2aWV3LCB7IHNob3J0Y29kZTogc2hvcnRjb2RlIH0pXG5cdFx0KTtcblx0fVxufSk7XG5cblxuLy8vLy8vLy8vLy8vLy8vLy8vXG4vLyBXRUJQQUNLIEZPT1RFUlxuLy8gLi9zcmMvYmxvY2svYmxvY2suanNcbi8vIG1vZHVsZSBpZCA9IDFcbi8vIG1vZHVsZSBjaHVua3MgPSAwIl0sIm1hcHBpbmdzIjoiQUFBQTtBQUFBO0FBQUE7QUFBQTtBQUFBO0FBQUE7QUFBQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EiLCJzb3VyY2VSb290IjoiIn0=\n//# sourceURL=webpack-internal:///1\n");

/***/ }),
/* 2 */
/*!******************************!*\
  !*** ./src/block/style.scss ***!
  \******************************/
/*! dynamic exports provided */
/***/ (function(module, exports) {

eval("// removed by extract-text-webpack-plugin//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiMi5qcyIsInNvdXJjZXMiOlsid2VicGFjazovLy8uL3NyYy9ibG9jay9zdHlsZS5zY3NzPzgwZjMiXSwic291cmNlc0NvbnRlbnQiOlsiLy8gcmVtb3ZlZCBieSBleHRyYWN0LXRleHQtd2VicGFjay1wbHVnaW5cblxuXG4vLy8vLy8vLy8vLy8vLy8vLy9cbi8vIFdFQlBBQ0sgRk9PVEVSXG4vLyAuL3NyYy9ibG9jay9zdHlsZS5zY3NzXG4vLyBtb2R1bGUgaWQgPSAyXG4vLyBtb2R1bGUgY2h1bmtzID0gMCJdLCJtYXBwaW5ncyI6IkFBQUEiLCJzb3VyY2VSb290IjoiIn0=\n//# sourceURL=webpack-internal:///2\n");

/***/ }),
/* 3 */
/*!*******************************!*\
  !*** ./src/block/editor.scss ***!
  \*******************************/
/*! dynamic exports provided */
/***/ (function(module, exports) {

eval("// removed by extract-text-webpack-plugin//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiMy5qcyIsInNvdXJjZXMiOlsid2VicGFjazovLy8uL3NyYy9ibG9jay9lZGl0b3Iuc2Nzcz80OWQyIl0sInNvdXJjZXNDb250ZW50IjpbIi8vIHJlbW92ZWQgYnkgZXh0cmFjdC10ZXh0LXdlYnBhY2stcGx1Z2luXG5cblxuLy8vLy8vLy8vLy8vLy8vLy8vXG4vLyBXRUJQQUNLIEZPT1RFUlxuLy8gLi9zcmMvYmxvY2svZWRpdG9yLnNjc3Ncbi8vIG1vZHVsZSBpZCA9IDNcbi8vIG1vZHVsZSBjaHVua3MgPSAwIl0sIm1hcHBpbmdzIjoiQUFBQSIsInNvdXJjZVJvb3QiOiIifQ==\n//# sourceURL=webpack-internal:///3\n");

/***/ }),
/* 4 */
/*!********************************!*\
  !*** external "wp.components" ***!
  \********************************/
/*! dynamic exports provided */
/***/ (function(module, exports) {

module.exports = wp.components;

/***/ })
/******/ ]);