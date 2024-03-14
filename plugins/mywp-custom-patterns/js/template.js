/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ([
/* 0 */,
/* 1 */
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ })
/******/ 	]);
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
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be isolated against other modules in the chunk.
(() => {
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _scss_style_scss__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(1);
function _slicedToArray(arr, i) { return _arrayWithHoles(arr) || _iterableToArrayLimit(arr, i) || _unsupportedIterableToArray(arr, i) || _nonIterableRest(); }

function _nonIterableRest() { throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }

function _iterableToArrayLimit(arr, i) { var _i = arr == null ? null : typeof Symbol !== "undefined" && arr[Symbol.iterator] || arr["@@iterator"]; if (_i == null) return; var _arr = []; var _n = true; var _d = false; var _s, _e; try { for (_i = _i.call(arr); !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"] != null) _i["return"](); } finally { if (_d) throw _e; } } return _arr; }

function _arrayWithHoles(arr) { if (Array.isArray(arr)) return arr; }


var CustomIcon = /*#__PURE__*/React.createElement("svg", {
  fillRule: "nonzero",
  clipRule: "evenodd",
  strokeLinecap: "round",
  strokeLinejoin: "round",
  xmlns: "http://www.w3.org/2000/svg",
  viewBox: "0 0 24 24",
  width: "18",
  height: "18"
}, /*#__PURE__*/React.createElement("g", null, /*#__PURE__*/React.createElement("path", {
  d: "M13.7104+8.56898L23.9931+8.56898L23.9931+15.4242L13.7104+15.4242L13.7104+8.56898ZM23.5647+0L13.7104+0L13.7104+6.85518L23.9931+6.85518L23.9931+0.428449C23.9931+0.191823+23.8013-1.03432e-08+23.5647+0ZM13.7104+23.9931L23.5647+23.9931C23.8013+23.9931+23.9931+23.8013+23.9931+23.5647L23.9931+17.138L13.7104+17.138L13.7104+23.9931ZM0.428449+23.9931L10.2828+23.9931L10.2828+13.7104L0+13.7104L0+23.5647C0+23.7995+0.193659+23.9931+0.428449+23.9931ZM10.2828+0L0.428449+0C0.191823+0-2.06865e-08+0.191823+0+0.428449L0+10.2828L10.2828+10.2828L10.2828+0Z"
})));

(function (wp) {
  var registerPlugin = wp.plugins.registerPlugin;
  var __ = wp.i18n.__;
  var _wp$element = wp.element,
      Fragment = _wp$element.Fragment,
      useState = _wp$element.useState;
  var addFilter = wp.hooks.addFilter;
  var _wp$components = wp.components,
      Modal = _wp$components.Modal,
      Button = _wp$components.Button;
  var _wp$editPost = wp.editPost,
      PluginSidebarMoreMenuItem = _wp$editPost.PluginSidebarMoreMenuItem,
      PluginSidebar = _wp$editPost.PluginSidebar,
      PluginBlockSettingsMenuItem = _wp$editPost.PluginBlockSettingsMenuItem;
  /*   const {select, dispatch} = wp.data;
     const {count} = wp.wordcount;
     const {serialize} = wp.blocks;
     const {Panel, PanelBody, PanelRow} = wp.components; */
  //   const{Snackbar} = wp.components;

  var list_blocks;

  var mywpCustomPatternsPanel = function mywpCustomPatternsPanel() {
    var options_template = []; //  let options_cat = [];

    options_template.push( /*#__PURE__*/React.createElement("option", {
      key: "0",
      disabled: true,
      "data-content": "",
      value: ""
    }, __('Pattern name', 'mywp-custom-patterns')));
    Object.entries(MYWP_DATA.template_list).forEach(function (_ref) {
      var _ref2 = _slicedToArray(_ref, 2),
          index = _ref2[0],
          item = _ref2[1];

      options_template.push( /*#__PURE__*/React.createElement("option", {
        key: index + 1,
        "data-content": item.content,
        value: item.id
      }, item.title));
    });
    /*
    Object.entries(MYWP_DATA.categories_list).forEach(([index, item]) => {
        options_cat.push(
            <option data-content={item.content} value={item.id}>{item.title}</option>
        );
    }); */

    var styleCustom = {
      display: 'none'
    };
    return /*#__PURE__*/React.createElement(Fragment, null, /*#__PURE__*/React.createElement(PluginSidebarMoreMenuItem, {
      target: "mywp-custom-patterns"
    }, "MyWP Custom Patterns"), /*#__PURE__*/React.createElement(PluginSidebar, {
      name: "mywp-custom-patterns",
      title: __('MyWP Custom Patterns', 'mywp-custom-patterns')
    }, /*#__PURE__*/React.createElement("div", {
      id: "mywp-custom-patterns__wrapper",
      className: "mywp-custom-patterns__wrapper"
    }, /*#__PURE__*/React.createElement("div", {
      className: "mywp-custom-patterns__settings"
    }, /*#__PURE__*/React.createElement("p", null, __('Save your current blocks patterns', 'mywp-custom-patterns')), /*#__PURE__*/React.createElement("div", {
      style: styleCustom,
      className: "mywp-error-field"
    }, __('Required field', 'mywp-custom-patterns')), /*#__PURE__*/React.createElement("input", {
      name: "mywp_name_pattern",
      type: "text",
      placeholder: __('Pattern name', 'mywp-custom-patterns')
    }), /*#__PURE__*/React.createElement("button", {
      onClick: saveMyWPCustomPattern,
      className: "mywp-custom-patterns__button",
      id: "saveMyWPCustomPattern"
    }, __('Save your pattern', 'mywp-custom-patterns'))), /*#__PURE__*/React.createElement("div", {
      className: "mywp-custom-patterns__settings"
    }, /*#__PURE__*/React.createElement("p", null, /*#__PURE__*/React.createElement("strong", null, __('Load and replace all you content by your custom patterns', 'mywp-custom-patterns'))), /*#__PURE__*/React.createElement("select", {
      name: "list_patterns",
      id: "list_patterns",
      defaultValue: ""
    }, options_template), /*#__PURE__*/React.createElement("br", null), /*#__PURE__*/React.createElement("button", {
      onClick: loadMyWPCustomPattern,
      className: "mywp-custom-patterns__button",
      id: "loadMyWPCustomPattern"
    }, __('Load your pattern', 'mywp-custom-patterns'))))));
  };

  registerPlugin('mywp-custom-patterns-settings', {
    render: mywpCustomPatternsPanel,
    icon: CustomIcon
  });
  var closeModal;

  var mywpCustomPatternsAddSettings = function mywpCustomPatternsAddSettings() {
    var _useState = useState(false),
        _useState2 = _slicedToArray(_useState, 2),
        isOpen = _useState2[0],
        setOpen = _useState2[1];

    var openModal = function openModal() {
      return setOpen(true);
    };

    closeModal = function closeModal() {
      return setOpen(false);
    };

    return /*#__PURE__*/React.createElement(Fragment, null, /*#__PURE__*/React.createElement(PluginBlockSettingsMenuItem, {
      icon: CustomIcon,
      label: __('Add to Block Patterns', 'mywp-custom-patterns'),
      onClick: openModal
    }), isOpen && /*#__PURE__*/React.createElement(Modal, {
      title: __('Add to Block Patterns', 'mywp-custom-patterns'),
      onRequestClose: closeModal,
      className: "mywp-modal-patterns",
      style: {
        width: "460px"
      }
    }, /*#__PURE__*/React.createElement("input", {
      style: {
        width: "100%"
      },
      name: "mywp_name_pattern_single_block",
      type: "text",
      placeholder: __('Pattern name', 'mywp-custom-patterns')
    }), /*#__PURE__*/React.createElement("div", {
      style: {
        display: "none",
        color: "#c03",
        fontSize: "14px",
        marginBottom: "4px"
      },
      className: "mywp-error-field"
    }, __('Required field', 'mywp-custom-patterns')), /*#__PURE__*/React.createElement("button", {
      className: "mywp-custom-patterns__button",
      onClick: function onClick(e) {
        saveMyWPCustomPatternSingleBlock(e);
      }
    }, __('Save your pattern', 'mywp-custom-patterns')), /*#__PURE__*/React.createElement("p", {
      style: {
        marginTop: "3em",
        background: "#ff9b0014",
        padding: "0.5em",
        fontWeight: '700'
      }
    }, __("Note: after the pattern has been created, you'll need to reload the page to make the pattern visible in the inserter.", 'mywp-custom-patterns'))));
  };

  registerPlugin('mywp-custom-patterns-settings-all-blocks', {
    render: mywpCustomPatternsAddSettings,
    icon: CustomIcon
  });

  function saveMyWPCustomPatternAjax(name_pattern, content) {
    var data = new FormData();
    data.append('action', 'save_template_who');
    data.append('title', name_pattern);
    data.append('post_id', wp.data.select('core/editor').getCurrentPostId());
    data.append('template_content', content);
    data.append('nonce', MYWP_DATA.nonce);
    /*
    let toto = new URLSearchParams({
        'action': 'save_template_who',
        'nonce': MYWP_DATA.nonce,
        'title': name_pattern,
        'post_id' : wp.data.select('core/editor').getCurrentPostId(),
        'template_content' : wp.data.select('core/editor').getEditedPostContent()
    }); */
    //  console.log(toto.toString());

    fetch(MYWP_DATA.ajaxurl, {
      method: "POST",
      credentials: 'same-origin',
      body: data
    }).then(function (res) {
      return res.json();
    }).then(function (data) {
      if (data.response === 1) {
        wp.data.dispatch('core/notices').createNotice('success', __('Pattern saved', 'mywp-custom-patterns'), {
          type: "snackbar",
          isDismissible: true,
          actions: [{
            url: window.location.href,
            label: __('Reload page', 'mywp-custom-patterns')
          }]
        });
        var item = document.getElementsByName('list_patterns')[0];
        var opt = document.createElement("option");
        opt.value = data.id;
        opt.text = data.title;
        opt.dataset.content = data.content;
        item.add(opt, null);
      } else {
        wp.data.dispatch('core/notices').createNotice('warning', __('Pattern not saved', 'mywp-custom-patterns'));
      }

      document.getElementById('saveMyWPCustomPattern').disabled = false;
    })["catch"](function (error) {//   console.log(error);
    });
  }

  function saveMyWPCustomPatternSingleBlock(e) {
    // console.log(e);
    var name_pattern = document.getElementsByName('mywp_name_pattern_single_block')[0].value;
    document.querySelector('.mywp-modal-patterns .mywp-custom-patterns__button').disabled = true;

    if (name_pattern === '') {
      document.querySelector('.mywp-modal-patterns .mywp-error-field').style.display = 'block';
      document.querySelector('.mywp-modal-patterns .mywp-custom-patterns__button').disabled = false;
      return;
    }

    document.querySelector('.mywp-modal-patterns .mywp-error-field').style.display = 'none';
    var content;

    if (wp.data.select('core/editor').getSelectedBlockCount() > 1) {
      content = Object(wp.blocks.serialize)(wp.data.select('core/editor').getMultiSelectedBlocks());
    } else {
      content = Object(wp.blocks.serialize)(wp.data.select('core/editor').getSelectedBlock());
    } // saveMyWPCustomPatternAjax(name_pattern, JSON.stringify(content));


    saveMyWPCustomPatternAjax(name_pattern, content); //   console.log(Object(wp.data.select('core/editor').getSelectedBlock().serialize)); // getSelectedBlockClientId
    //   console.log(Object(wp.data.select('core/block-editor').getSelectedBlock().serialize)); // getSelectedBlockClientId
    // console.log(name_pattern);

    closeModal();
  }
  /*
      function who_add_cat() {
          console.log('aa');
          document.getElementsByClassName('mywp_template_cat_add_input')[0].style.display = 'block';
          return false;
      }
  */


  function saveMyWPCustomPattern() {
    document.getElementById('saveMyWPCustomPattern').disabled = true;
    var name_pattern = document.getElementsByName('mywp_name_pattern')[0].value;

    if (name_pattern === '') {
      document.querySelector('.mywp-custom-patterns__wrapper .mywp-error-field').style.display = 'block'; // document.getElementById('wrapper-custom-patterns');

      document.getElementById('saveMyWPCustomPattern').disabled = false;
      return;
    }

    document.querySelector('.mywp-custom-patterns__wrapper .mywp-error-field').style.display = 'none';

    if (document.getElementsByName('mywp_template_cat')[0] !== undefined) {
      var mywp_template_cat = document.getElementsByName('mywp_template_cat')[0].value;
    } //   list_blocks = wp.data.select('core/block-editor').getBlocks();


    list_blocks = wp.data.select('core/editor').getEditedPostContent(); // saveMyWPCustomPatternAjax(name_pattern, JSON.stringify(list_blocks));

    saveMyWPCustomPatternAjax(name_pattern, list_blocks);
    /*
            const data = new FormData();
              data.append('action', 'save_template_who');
            data.append('title', name_pattern);
            /*
            if (document.getElementsByName('mywp_template_cat')[0] !== undefined) {
                data.append('mywp_template_cat', mywp_template_cat);
            } */

    /*
    data.append('post_id', wp.data.select('core/editor').getCurrentPostId());
    data.append('template_content', JSON.stringify(list_blocks));
    data.append('nonce', MYWP_DATA.nonce)
    data.append('nonce', MYWP_DATA.nonce)
    */

    /*
            fetch(MYWP_DATA.ajaxurl, {
                method: "POST",
                credentials: 'same-origin',
                body: data
            })
                .then((res) => res.json())
                .then((data) => {
                    if (data.response === 1) {
                        let item = document.getElementsByName('list_patterns')[0];
                        let opt = document.createElement("option");
                        opt.value = data.id;
                        opt.text = data.title;
                        opt.dataset.content = data.content;
                        item.add(opt, null);
                        wp.data.dispatch('core/notices').createNotice('success', __('Pattern save', 'mywp-custom-patterns'));
                      } else {
                        wp.data.dispatch('core/notices').createNotice('warning', __('Pattern not saved', 'mywp-custom-patterns'));
                    }
                        document.getElementById('saveMyWPCustomPattern').disabled = false;
                })
                .catch((error) => {
                    //   console.log(error);
                }); */

    document.getElementById('saveMyWPCustomPattern').disabled = false;
    return false;
  }

  function loadMyWPCustomPattern() {
    if (confirm(__('Replace all you content by your custom pattern?', 'mywp-custom-patterns'))) {
      var item = document.getElementsByName('list_patterns')[0];
      var id_template = item.value;
      var selectedIndex = item.selectedIndex;
      var load_template = item.options[selectedIndex].getAttribute('data-content');

      if (load_template != '') {
        // wp.data.dispatch('core/block-editor').insertBlocks(list_blocks);
        wp.data.dispatch('core/editor').resetBlocks(wp.blocks.parse(load_template));
      }
    }

    return false;
  }
})(window.wp);
})();

/******/ })()
;