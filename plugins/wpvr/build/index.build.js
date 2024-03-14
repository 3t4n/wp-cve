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
/******/ 	return __webpack_require__(__webpack_require__.s = "./src/index.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./src/index.js":
/*!**********************!*\
  !*** ./src/index.js ***!
  \**********************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("function _typeof(o) { \"@babel/helpers - typeof\"; return _typeof = \"function\" == typeof Symbol && \"symbol\" == typeof Symbol.iterator ? function (o) { return typeof o; } : function (o) { return o && \"function\" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? \"symbol\" : typeof o; }, _typeof(o); }\nfunction _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError(\"Cannot call a class as a function\"); } }\nfunction _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if (\"value\" in descriptor) descriptor.writable = true; Object.defineProperty(target, _toPropertyKey(descriptor.key), descriptor); } }\nfunction _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); Object.defineProperty(Constructor, \"prototype\", { writable: false }); return Constructor; }\nfunction _toPropertyKey(arg) { var key = _toPrimitive(arg, \"string\"); return _typeof(key) === \"symbol\" ? key : String(key); }\nfunction _toPrimitive(input, hint) { if (_typeof(input) !== \"object\" || input === null) return input; var prim = input[Symbol.toPrimitive]; if (prim !== undefined) { var res = prim.call(input, hint || \"default\"); if (_typeof(res) !== \"object\") return res; throw new TypeError(\"@@toPrimitive must return a primitive value.\"); } return (hint === \"string\" ? String : Number)(input); }\nfunction _inherits(subClass, superClass) { if (typeof superClass !== \"function\" && superClass !== null) { throw new TypeError(\"Super expression must either be null or a function\"); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, writable: true, configurable: true } }); Object.defineProperty(subClass, \"prototype\", { writable: false }); if (superClass) _setPrototypeOf(subClass, superClass); }\nfunction _setPrototypeOf(o, p) { _setPrototypeOf = Object.setPrototypeOf ? Object.setPrototypeOf.bind() : function _setPrototypeOf(o, p) { o.__proto__ = p; return o; }; return _setPrototypeOf(o, p); }\nfunction _createSuper(Derived) { var hasNativeReflectConstruct = _isNativeReflectConstruct(); return function _createSuperInternal() { var Super = _getPrototypeOf(Derived), result; if (hasNativeReflectConstruct) { var NewTarget = _getPrototypeOf(this).constructor; result = Reflect.construct(Super, arguments, NewTarget); } else { result = Super.apply(this, arguments); } return _possibleConstructorReturn(this, result); }; }\nfunction _possibleConstructorReturn(self, call) { if (call && (_typeof(call) === \"object\" || typeof call === \"function\")) { return call; } else if (call !== void 0) { throw new TypeError(\"Derived constructors may only return object or undefined\"); } return _assertThisInitialized(self); }\nfunction _assertThisInitialized(self) { if (self === void 0) { throw new ReferenceError(\"this hasn't been initialised - super() hasn't been called\"); } return self; }\nfunction _isNativeReflectConstruct() { if (typeof Reflect === \"undefined\" || !Reflect.construct) return false; if (Reflect.construct.sham) return false; if (typeof Proxy === \"function\") return true; try { Boolean.prototype.valueOf.call(Reflect.construct(Boolean, [], function () {})); return true; } catch (e) { return false; } }\nfunction _getPrototypeOf(o) { _getPrototypeOf = Object.setPrototypeOf ? Object.getPrototypeOf.bind() : function _getPrototypeOf(o) { return o.__proto__ || Object.getPrototypeOf(o); }; return _getPrototypeOf(o); }\nvar __ = wp.i18n.__; // Import __() from wp.i18n\nvar Component = wp.element.Component;\nvar el = wp.element.createElement,\n  registerBlockType = wp.blocks.registerBlockType,\n  TextControl = wp.components.TextControl,\n  SelectControl = wp.components.SelectControl,\n  ColorPalette = wp.components.ColorPalette,\n  NumberControl = wp.components.__experimentalNumberControl,\n  InspectorControls = wp.editor.InspectorControls,\n  blockStyle = {\n    fontFamily: 'Roboto',\n    backgroundColor: '#900',\n    color: '#fff',\n    padding: '20px'\n  };\nvar iconEl = el('svg', {\n  width: 20,\n  height: 20\n}, el('path', {\n  d: \"M16.1,16.6h-2.5c-1,0-1.9-0.6-2.4-1.5L11,14.5c-0.2-0.4-0.5-0.6-0.9-0.6c-0.4,0-0.8,0.2-0.9,0.6l-0.3,0.6 c-0.4,0.9-1.3,1.5-2.4,1.5H3.9c-2.2,0-3.9-1.8-3.9-3.9V7.3c0-2.2,1.8-3.9,3.9-3.9h12.2c2.2,0,3.9,1.8,3.9,3.9v1.5 c0,0.4-0.3,0.8-0.8,0.8c-0.4,0-0.8-0.3-0.8-0.8V7.3c0-1.3-1.1-2.3-2.3-2.3H3.9C2.6,4.9,1.6,6,1.6,7.3v5.4c0,1.3,1.1,2.3,2.3,2.3 h2.6c0.4,0,0.8-0.2,0.9-0.6l0.3-0.6c0.4-0.9,1.3-1.5,2.4-1.5c1,0,1.9,0.6,2.4,1.5l0.3,0.6c0.2,0.4,0.5,0.6,0.9,0.6h2.5 c1.3,0,2.3-1.1,2.3-2.3c0-0.4,0.3-0.8,0.8-0.8c0.4,0,0.8,0.3,0.8,0.8C20,14.9,18.2,16.6,16.1,16.6L16.1,16.6z M16.7,9.4 c0-1.3-1.1-2.3-2.3-2.3C13,7.1,12,8.1,12,9.4s1.1,2.3,2.3,2.3C15.6,11.7,16.7,10.7,16.7,9.4L16.7,9.4z M15.1,9.4 c0,0.4-0.4,0.8-0.8,0.8c-0.4,0-0.8-0.4-0.8-0.8s0.4-0.8,0.8-0.8C14.8,8.6,15.1,9,15.1,9.4L15.1,9.4z M8,9.4C8,8.1,7,7.1,5.7,7.1 S3.3,8.1,3.3,9.4s1.1,2.3,2.3,2.3S8,10.7,8,9.4L8,9.4z M6.4,9.4c0,0.4-0.4,0.8-0.8,0.8c-0.4,0-0.8-0.4-0.8-0.8s0.4-0.8,0.8-0.8 C6.1,8.6,6.4,9,6.4,9.4L6.4,9.4z M6.4,9.4\"\n}));\nvar wpvredit = /*#__PURE__*/function (_Component) {\n  _inherits(wpvredit, _Component);\n  var _super = _createSuper(wpvredit);\n  function wpvredit() {\n    var _this;\n    _classCallCheck(this, wpvredit);\n    _this = _super.apply(this, arguments);\n    _this.state = {\n      fullwidth: '',\n      data: [{\n        value: \"0\",\n        label: \"None\"\n      }],\n      border_style_option: [{\n        value: \"none\",\n        label: \"none\"\n      }, {\n        value: \"solid\",\n        label: \"Solid\"\n      }, {\n        value: \"dotted\",\n        label: \"Dotted\"\n      }, {\n        value: \"dashed\",\n        label: \"Dashed\"\n      }, {\n        value: \"double\",\n        label: \"Double\"\n      }],\n      colors: [{\n        name: 'red',\n        color: '#f00'\n      }, {\n        name: 'white',\n        color: '#fff'\n      }, {\n        name: 'blue',\n        color: '#00f'\n      }],\n      width_unit: [{\n        value: \"px\",\n        label: \"px\"\n      }, {\n        value: \"%\",\n        label: \"%\"\n      }, {\n        value: \"vw\",\n        label: \"vw\"\n      }, {\n        value: \"fullwidth\",\n        label: \"Fullwidth\"\n      }],\n      height_unit_option: [{\n        value: \"px\",\n        label: \"px\"\n      }, {\n        value: \"vh\",\n        label: \"vh\"\n      }],\n      radius_unit_option: [{\n        value: \"px\",\n        label: \"px\"\n      }],\n      mobile_height_unit_option: [{\n        value: \"px\",\n        label: \"px\"\n      }]\n    };\n    return _this;\n  }\n  _createClass(wpvredit, [{\n    key: \"componentDidMount\",\n    value: function componentDidMount() {\n      var _this2 = this;\n      wp.apiFetch({\n        path: 'wpvr/v1/panodata'\n      }).then(function (data) {\n        _this2.setState({\n          data: data\n        });\n      });\n    }\n  }, {\n    key: \"render\",\n    value: function render() {\n      var _this3 = this;\n      return [el(InspectorControls, {}, el(SelectControl, {\n        className: 'wpvr-base-control',\n        label: 'Id',\n        value: this.props.attributes.id,\n        onChange: function onChange(value) {\n          _this3.props.setAttributes({\n            id: value\n          });\n        },\n        options: this.state.data\n      })), el(InspectorControls, {}, el(TextControl, {\n        className: 'wpvr-base-control wpvr-width-base-control',\n        label: 'Width',\n        value: this.props.attributes.width,\n        onChange: function onChange(value) {\n          _this3.props.setAttributes({\n            width: value\n          });\n        }\n      })), el(InspectorControls, {}, el(SelectControl, {\n        className: 'wpvr-base-control wpvr-width-unit-control',\n        label: ' ',\n        value: this.props.attributes.width_unit,\n        onChange: function onChange(value) {\n          _this3.props.setAttributes({\n            width_unit: value\n          });\n          if (value == 'fullwidth') {\n            _this3.props.setAttributes({\n              width: value\n            });\n            _this3.props.setAttributes({\n              width_unit: ''\n            });\n          }\n        },\n        options: this.state.width_unit\n      })), el(InspectorControls, {}, el(NumberControl, {\n        className: 'wpvr-base-control wpvr-height-base-control',\n        label: 'Height',\n        value: this.props.attributes.height,\n        onChange: function onChange(value) {\n          _this3.props.setAttributes({\n            height: value\n          });\n        }\n      })), el(InspectorControls, {}, el(SelectControl, {\n        className: 'wpvr-base-control wpvr-height-unit-control',\n        label: ' ',\n        value: this.props.attributes.height_unit,\n        onChange: function onChange(value) {\n          _this3.props.setAttributes({\n            height_unit: value\n          });\n        },\n        options: this.state.height_unit_option\n      })), el(InspectorControls, {}, el(NumberControl, {\n        className: 'wpvr-base-control wpvr-radius-base-control',\n        label: 'Radius',\n        value: this.props.attributes.radius,\n        onChange: function onChange(value) {\n          _this3.props.setAttributes({\n            radius: value\n          });\n        }\n      })), el(InspectorControls, {}, el(SelectControl, {\n        className: 'wpvr-base-control wpvr-radius-unit-control',\n        label: ' ',\n        value: this.props.attributes.radius_unit,\n        onChange: function onChange(value) {\n          _this3.props.setAttributes({\n            radius_unit: value\n          });\n        },\n        options: this.state.radius_unit_option\n      })), el(InspectorControls, {}, el(NumberControl, {\n        className: 'wpvr-base-control wpvr-mobile-height-base-control',\n        label: 'Mobile Height',\n        value: this.props.attributes.mobile_height,\n        onChange: function onChange(value) {\n          _this3.props.setAttributes({\n            mobile_height: value\n          });\n        }\n      })), el(InspectorControls, {}, el(SelectControl, {\n        className: 'wpvr-base-control wpvr-mobile-height-unit-control',\n        label: ' ',\n        value: this.props.attributes.mobile_height_unit,\n        onChange: function onChange(value) {\n          _this3.props.setAttributes({\n            mobile_height_unit: value\n          });\n        },\n        options: this.state.mobile_height_unit_option\n      })), el(InspectorControls, {}, el(TextControl, {\n        className: 'wpvr-base-control wpvr-border-width-base-control',\n        label: 'Border Width',\n        value: this.props.attributes.border_width,\n        onChange: function onChange(value) {\n          _this3.props.setAttributes({\n            border_width: value\n          });\n        }\n      })), el(InspectorControls, {}, el(SelectControl, {\n        className: 'wpvr-base-control wpvr-border-style-base-control',\n        label: 'Border Style',\n        value: this.props.attributes.border_style,\n        onChange: function onChange(value) {\n          _this3.props.setAttributes({\n            border_style: value\n          });\n        },\n        options: this.state.border_style_option\n      })), el(InspectorControls, {}, el(ColorPalette, {\n        className: 'wpvr-base-control wpvr-border-color-base-control',\n        label: 'Border Color',\n        colors: this.state.colors,\n        value: this.props.attributes.border_color,\n        onChange: function onChange(value) {\n          _this3.props.setAttributes({\n            border_color: value\n          });\n        }\n      })), /*#__PURE__*/React.createElement(\"p\", {\n        className: \"wpvr-block-content\"\n      }, \"WPVR id=\", this.props.attributes.id, \", Width=\", this.props.attributes.width, this.props.attributes.width_unit, \", Height=\", this.props.attributes.height, this.props.attributes.height_unit, \", Mobile Height=\", this.props.attributes.mobile_height, this.props.attributes.mobile_height_unit, \", Radius=\", this.props.attributes.radius, this.props.attributes.radius_unit)];\n    }\n  }]);\n  return wpvredit;\n}(Component);\nregisterBlockType('wpvr/wpvr-block', {\n  title: 'WPVR',\n  icon: iconEl,\n  category: 'common',\n  edit: wpvredit,\n  save: function save(props) {\n    return null;\n  }\n});\n\n//# sourceURL=webpack:///./src/index.js?");

/***/ })

/******/ });