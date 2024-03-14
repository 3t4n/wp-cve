/******/ (function() { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "@wordpress/element":
/*!*********************************!*\
  !*** external ["wp","element"] ***!
  \*********************************/
/***/ (function(module) {

  module.exports = window["wp"]["element"];

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
  /******/ 	/* webpack/runtime/compat get default export */
  /******/ 	!function() {
  /******/ 		// getDefaultExport function for compatibility with non-harmony modules
  /******/ 		__webpack_require__.n = function(module) {
  /******/ 			var getter = module && module.__esModule ?
  /******/ 				function() { return module['default']; } :
  /******/ 				function() { return module; };
  /******/ 			__webpack_require__.d(getter, { a: getter });
  /******/ 			return getter;
  /******/ 		};
  /******/ 	}();
  /******/ 	
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
  var __webpack_exports__ = {};
  // This entry need to be wrapped in an IIFE because it need to be isolated against other modules in the chunk.
  !function() {
  /*!**********************!*\
    !*** ./src/index.js ***!
    \**********************/
  __webpack_require__.r(__webpack_exports__);
  /* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
  /* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
  
  const {
    __
  } = wp.i18n; // Import __() from wp.i18n
  
  const {
    SelectControl,
    TextControl,
    Placeholder,
    Spinner
  } = wp.components;
  const {
    Component
  } = wp.element;
  var gobal_check;
  var all_snippets_php = all_snippets.all_snippets.data;
  
  class BlockEdit extends Component {
    constructor(props) {
      super(props);
      this.state = {
        listVar: {},
        loadingVar: true,
        snippetSelected: false
      };
      /**Setting These Functions Internal Scope as that of this class as
       * when calling functions, their scope becomes that of
       * caller functions.
       */
  
      this.AllSnippetsSelect = this.AllSnippetsSelect.bind(this);
      this.getSnippetVars = this.getSnippetVars.bind(this);
      this.SnippetVars = this.SnippetVars.bind(this);
    }
  
    componentDidMount() {
      var _this$props, _this$props$attribute, _this$props2, _this$props2$attribut, _this$props4, _this$props4$attribut, _this$props5, _this$props5$attribut;
  
      /**Backward Compatibility */
      if (typeof (this === null || this === void 0 ? void 0 : (_this$props = this.props) === null || _this$props === void 0 ? void 0 : (_this$props$attribute = _this$props.attributes) === null || _this$props$attribute === void 0 ? void 0 : _this$props$attribute.snippet) != "undefined" && typeof (this === null || this === void 0 ? void 0 : (_this$props2 = this.props) === null || _this$props2 === void 0 ? void 0 : (_this$props2$attribut = _this$props2.attributes) === null || _this$props2$attribut === void 0 ? void 0 : _this$props2$attribut.snippetID) === "undefined") {
        for (const [snippet_id, snippet_details] of Object.entries(all_snippets_php)) {
          var _this$props3, _this$props3$attribut;
  
          if ((this === null || this === void 0 ? void 0 : (_this$props3 = this.props) === null || _this$props3 === void 0 ? void 0 : (_this$props3$attribut = _this$props3.attributes) === null || _this$props3$attribut === void 0 ? void 0 : _this$props3$attribut.snippet) === snippet_details.snippet_title) {
            this.getSnippetVars(snippet_id);
          }
        }
      }
  
      if (typeof (this === null || this === void 0 ? void 0 : (_this$props4 = this.props) === null || _this$props4 === void 0 ? void 0 : (_this$props4$attribut = _this$props4.attributes) === null || _this$props4$attribut === void 0 ? void 0 : _this$props4$attribut.snippetID) !== "undefined" && typeof all_snippets_php[this === null || this === void 0 ? void 0 : (_this$props5 = this.props) === null || _this$props5 === void 0 ? void 0 : (_this$props5$attribut = _this$props5.attributes) === null || _this$props5$attribut === void 0 ? void 0 : _this$props5$attribut.snippetID] !== "undefined") {
        /**If its not a new Block with already have a Snippet ID */
        this.getSnippetVarsRest(this.props.attributes.snippetID);
        this.setState({
          snippetSelected: true
        });
      }
    }
  
    AllSnippetsSelect() {
      var _this$props6, _this$props6$attribut, _this$props7, _this$props7$attribut, _this$props8, _this$props8$attribut;
  
      var allSnippetsOptions = [{
        value: -1,
        label: __('Select Snippet:', 'post-snippets'),
        disabled: true
      }];
      var all_snippets_options = all_snippets_php;
  
      for (const [snippet_id, snippet_details] of Object.entries(all_snippets_options)) {
        allSnippetsOptions.push({
          value: snippet_id,
          label: snippet_details.snippet_title
        });
      }
  
      return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(SelectControl // multiple
      , {
        label: __('Select Snippet:', 'post-snippets'),
        value: this !== null && this !== void 0 && (_this$props6 = this.props) !== null && _this$props6 !== void 0 && (_this$props6$attribut = _this$props6.attributes) !== null && _this$props6$attribut !== void 0 && _this$props6$attribut.snippetID && typeof all_snippets_php[this === null || this === void 0 ? void 0 : (_this$props7 = this.props) === null || _this$props7 === void 0 ? void 0 : (_this$props7$attribut = _this$props7.attributes) === null || _this$props7$attribut === void 0 ? void 0 : _this$props7$attribut.snippetID] !== "undefined" ? this === null || this === void 0 ? void 0 : (_this$props8 = this.props) === null || _this$props8 === void 0 ? void 0 : (_this$props8$attribut = _this$props8.attributes) === null || _this$props8$attribut === void 0 ? void 0 : _this$props8$attribut.snippetID : -1,
        onChange: this.getSnippetVars,
        options: allSnippetsOptions
      });
    }
  
    SnippetVars() {
      var varTextFields = [];
  
      for (const [key, value] of Object.entries(this.state.listVar)) {
        if (typeof this.props.attributes.snippetVars !== "undefined" && this.props.attributes.snippetVars.hasOwnProperty(key)) {
          //replacing default already loaded vars with atts vars
          var default_value = this.props.attributes.snippetVars[key];
        } else {
          var default_value = value;
        }
  
        varTextFields.push((0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(TextControl, {
          label: key + (value ? " = " + value : '')
          /**If there's a default value */
          // defaultValue = { default_value }
          ,
          value: default_value,
          onChange: props => {
            /**Getting previous Vars and Updatin it */
            if (typeof this.props.attributes.snippetVars !== "undefined") {
              var snippet_vars = this.props.attributes.snippetVars;
            } else {
              var snippet_vars = {};
            }
            /**Getting previous Vars and Updatin it */
  
  
            snippet_vars[key] = props;
            this.props.setAttributes({
              snippetVars: {}
            });
            this.props.setAttributes({
              snippetVars: snippet_vars
            });
            this.setState({
              loadingVar: false
            });
          }
        }));
      }
  
      return varTextFields;
    }
  
    getSnippetVars(props) {
      this.props.setAttributes({
        snippetID: props
      });
      this.props.setAttributes({
        snippetVars: {}
      });
      this.setState({
        snippetSelected: true
      });
      this.getSnippetVarsRest(props);
    }
  
    getSnippetVarsRest(snippet_id) {
      var data = all_snippets_php[snippet_id].snippet_vars;
      this.setState({
        listVar: data,
        loadingVar: false
      });
    }
  
    render() {
      return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(Placeholder, {
        icon: "admin-plugins",
        label: __("  Post Snippets", 'post-snippets'),
        className: "is-small"
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(this.AllSnippetsSelect, null), this.state.snippetSelected ? this.state.loadingVar ? (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(Spinner, null) : (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(this.SnippetVars, null)) : null));
    }
  
  }
  
  wp.blocks.registerBlockType("greentreelabs/post-snippets-block", {
    title: __('Post Snippets', 'post-snippets'),
    icon: "admin-plugins",
    description: __('Select Snippet From List to Apply them on page.', 'post-snippets'),
    category: "common",
    supports: {
      // Remove support for an HTML mode.
      html: false
    },
    keywords: [__('post snippets', 'post-snippets'), __('snippets', 'post-snippets')],
    attributes: {
      snippetID: {
        type: "string"
      },
      snippetVars: {
        type: "object"
      },
  
      /**For Backward Compatibility */
      snippet: {
        type: 'string'
      },
      vars: {
        type: 'string'
      },
      shortcode: {
        type: 'boolean'
      },
      text_fields: {
        type: 'array'
      }
    },
    edit: BlockEdit,
    save: function (props) {
      var _props$attributes;
  
      if (typeof (props === null || props === void 0 ? void 0 : (_props$attributes = props.attributes) === null || _props$attributes === void 0 ? void 0 : _props$attributes.snippet) != "undefined") {
        return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", null, props.attributes.shortcode ? "[" : "", props.attributes.snippet, " ", props.attributes.vars, props.attributes.shortcode ? "]" : "");
      }
  
      return null;
    },
    example: {}
  });
  }();
  /******/ })()
  ;
  //# sourceMappingURL=index.js.map