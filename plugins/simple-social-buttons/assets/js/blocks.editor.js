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
/******/ 	return __webpack_require__(__webpack_require__.s = 49);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/***/ (function(module, exports) {

var core = module.exports = { version: '2.6.10' };
if (typeof __e == 'number') __e = core; // eslint-disable-line no-undef


/***/ }),
/* 1 */
/***/ (function(module, exports) {

// https://github.com/zloirock/core-js/issues/86#issuecomment-115759028
var global = module.exports = typeof window != 'undefined' && window.Math == Math
  ? window : typeof self != 'undefined' && self.Math == Math ? self
  // eslint-disable-next-line no-new-func
  : Function('return this')();
if (typeof __g == 'number') __g = global; // eslint-disable-line no-undef


/***/ }),
/* 2 */
/***/ (function(module, exports, __webpack_require__) {

var anObject = __webpack_require__(10);
var IE8_DOM_DEFINE = __webpack_require__(38);
var toPrimitive = __webpack_require__(16);
var dP = Object.defineProperty;

exports.f = __webpack_require__(3) ? Object.defineProperty : function defineProperty(O, P, Attributes) {
  anObject(O);
  P = toPrimitive(P, true);
  anObject(Attributes);
  if (IE8_DOM_DEFINE) try {
    return dP(O, P, Attributes);
  } catch (e) { /* empty */ }
  if ('get' in Attributes || 'set' in Attributes) throw TypeError('Accessors not supported!');
  if ('value' in Attributes) O[P] = Attributes.value;
  return O;
};


/***/ }),
/* 3 */
/***/ (function(module, exports, __webpack_require__) {

// Thank's IE8 for his funny defineProperty
module.exports = !__webpack_require__(11)(function () {
  return Object.defineProperty({}, 'a', { get: function () { return 7; } }).a != 7;
});


/***/ }),
/* 4 */
/***/ (function(module, exports) {

var hasOwnProperty = {}.hasOwnProperty;
module.exports = function (it, key) {
  return hasOwnProperty.call(it, key);
};


/***/ }),
/* 5 */
/***/ (function(module, exports, __webpack_require__) {

var global = __webpack_require__(1);
var core = __webpack_require__(0);
var ctx = __webpack_require__(37);
var hide = __webpack_require__(6);
var has = __webpack_require__(4);
var PROTOTYPE = 'prototype';

var $export = function (type, name, source) {
  var IS_FORCED = type & $export.F;
  var IS_GLOBAL = type & $export.G;
  var IS_STATIC = type & $export.S;
  var IS_PROTO = type & $export.P;
  var IS_BIND = type & $export.B;
  var IS_WRAP = type & $export.W;
  var exports = IS_GLOBAL ? core : core[name] || (core[name] = {});
  var expProto = exports[PROTOTYPE];
  var target = IS_GLOBAL ? global : IS_STATIC ? global[name] : (global[name] || {})[PROTOTYPE];
  var key, own, out;
  if (IS_GLOBAL) source = name;
  for (key in source) {
    // contains in native
    own = !IS_FORCED && target && target[key] !== undefined;
    if (own && has(exports, key)) continue;
    // export native or passed
    out = own ? target[key] : source[key];
    // prevent global pollution for namespaces
    exports[key] = IS_GLOBAL && typeof target[key] != 'function' ? source[key]
    // bind timers to global for call from export context
    : IS_BIND && own ? ctx(out, global)
    // wrap global constructors for prevent change them in library
    : IS_WRAP && target[key] == out ? (function (C) {
      var F = function (a, b, c) {
        if (this instanceof C) {
          switch (arguments.length) {
            case 0: return new C();
            case 1: return new C(a);
            case 2: return new C(a, b);
          } return new C(a, b, c);
        } return C.apply(this, arguments);
      };
      F[PROTOTYPE] = C[PROTOTYPE];
      return F;
    // make static versions for prototype methods
    })(out) : IS_PROTO && typeof out == 'function' ? ctx(Function.call, out) : out;
    // export proto methods to core.%CONSTRUCTOR%.methods.%NAME%
    if (IS_PROTO) {
      (exports.virtual || (exports.virtual = {}))[key] = out;
      // export proto methods to core.%CONSTRUCTOR%.prototype.%NAME%
      if (type & $export.R && expProto && !expProto[key]) hide(expProto, key, out);
    }
  }
};
// type bitmap
$export.F = 1;   // forced
$export.G = 2;   // global
$export.S = 4;   // static
$export.P = 8;   // proto
$export.B = 16;  // bind
$export.W = 32;  // wrap
$export.U = 64;  // safe
$export.R = 128; // real proto method for `library`
module.exports = $export;


/***/ }),
/* 6 */
/***/ (function(module, exports, __webpack_require__) {

var dP = __webpack_require__(2);
var createDesc = __webpack_require__(12);
module.exports = __webpack_require__(3) ? function (object, key, value) {
  return dP.f(object, key, createDesc(1, value));
} : function (object, key, value) {
  object[key] = value;
  return object;
};


/***/ }),
/* 7 */
/***/ (function(module, exports) {

module.exports = function (it) {
  return typeof it === 'object' ? it !== null : typeof it === 'function';
};


/***/ }),
/* 8 */
/***/ (function(module, exports, __webpack_require__) {

// to indexed object, toObject with fallback for non-array-like ES3 strings
var IObject = __webpack_require__(66);
var defined = __webpack_require__(20);
module.exports = function (it) {
  return IObject(defined(it));
};


/***/ }),
/* 9 */
/***/ (function(module, exports, __webpack_require__) {

var store = __webpack_require__(22)('wks');
var uid = __webpack_require__(14);
var Symbol = __webpack_require__(1).Symbol;
var USE_SYMBOL = typeof Symbol == 'function';

var $exports = module.exports = function (name) {
  return store[name] || (store[name] =
    USE_SYMBOL && Symbol[name] || (USE_SYMBOL ? Symbol : uid)('Symbol.' + name));
};

$exports.store = store;


/***/ }),
/* 10 */
/***/ (function(module, exports, __webpack_require__) {

var isObject = __webpack_require__(7);
module.exports = function (it) {
  if (!isObject(it)) throw TypeError(it + ' is not an object!');
  return it;
};


/***/ }),
/* 11 */
/***/ (function(module, exports) {

module.exports = function (exec) {
  try {
    return !!exec();
  } catch (e) {
    return true;
  }
};


/***/ }),
/* 12 */
/***/ (function(module, exports) {

module.exports = function (bitmap, value) {
  return {
    enumerable: !(bitmap & 1),
    configurable: !(bitmap & 2),
    writable: !(bitmap & 4),
    value: value
  };
};


/***/ }),
/* 13 */
/***/ (function(module, exports) {

module.exports = true;


/***/ }),
/* 14 */
/***/ (function(module, exports) {

var id = 0;
var px = Math.random();
module.exports = function (key) {
  return 'Symbol('.concat(key === undefined ? '' : key, ')_', (++id + px).toString(36));
};


/***/ }),
/* 15 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


exports.__esModule = true;

var _defineProperty = __webpack_require__(36);

var _defineProperty2 = _interopRequireDefault(_defineProperty);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

exports.default = function (obj, key, value) {
  if (key in obj) {
    (0, _defineProperty2.default)(obj, key, {
      value: value,
      enumerable: true,
      configurable: true,
      writable: true
    });
  } else {
    obj[key] = value;
  }

  return obj;
};

/***/ }),
/* 16 */
/***/ (function(module, exports, __webpack_require__) {

// 7.1.1 ToPrimitive(input [, PreferredType])
var isObject = __webpack_require__(7);
// instead of the ES6 spec version, we didn't implement @@toPrimitive case
// and the second argument - flag - preferred type is a string
module.exports = function (it, S) {
  if (!isObject(it)) return it;
  var fn, val;
  if (S && typeof (fn = it.toString) == 'function' && !isObject(val = fn.call(it))) return val;
  if (typeof (fn = it.valueOf) == 'function' && !isObject(val = fn.call(it))) return val;
  if (!S && typeof (fn = it.toString) == 'function' && !isObject(val = fn.call(it))) return val;
  throw TypeError("Can't convert object to primitive value");
};


/***/ }),
/* 17 */
/***/ (function(module, exports, __webpack_require__) {

var __WEBPACK_AMD_DEFINE_ARRAY__, __WEBPACK_AMD_DEFINE_RESULT__;/*!
  Copyright (c) 2017 Jed Watson.
  Licensed under the MIT License (MIT), see
  http://jedwatson.github.io/classnames
*/
/* global define */

(function () {
	'use strict';

	var hasOwn = {}.hasOwnProperty;

	function classNames () {
		var classes = [];

		for (var i = 0; i < arguments.length; i++) {
			var arg = arguments[i];
			if (!arg) continue;

			var argType = typeof arg;

			if (argType === 'string' || argType === 'number') {
				classes.push(arg);
			} else if (Array.isArray(arg) && arg.length) {
				var inner = classNames.apply(null, arg);
				if (inner) {
					classes.push(inner);
				}
			} else if (argType === 'object') {
				for (var key in arg) {
					if (hasOwn.call(arg, key) && arg[key]) {
						classes.push(key);
					}
				}
			}
		}

		return classes.join(' ');
	}

	if (typeof module !== 'undefined' && module.exports) {
		classNames.default = classNames;
		module.exports = classNames;
	} else if (true) {
		// register as 'classnames', consistent with npm package name
		!(__WEBPACK_AMD_DEFINE_ARRAY__ = [], __WEBPACK_AMD_DEFINE_RESULT__ = (function () {
			return classNames;
		}).apply(exports, __WEBPACK_AMD_DEFINE_ARRAY__),
				__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__));
	} else {
		window.classNames = classNames;
	}
}());


/***/ }),
/* 18 */
/***/ (function(module, exports, __webpack_require__) {

module.exports = { "default": __webpack_require__(57), __esModule: true };

/***/ }),
/* 19 */
/***/ (function(module, exports, __webpack_require__) {

// 7.1.13 ToObject(argument)
var defined = __webpack_require__(20);
module.exports = function (it) {
  return Object(defined(it));
};


/***/ }),
/* 20 */
/***/ (function(module, exports) {

// 7.2.1 RequireObjectCoercible(argument)
module.exports = function (it) {
  if (it == undefined) throw TypeError("Can't call method on  " + it);
  return it;
};


/***/ }),
/* 21 */
/***/ (function(module, exports, __webpack_require__) {

var shared = __webpack_require__(22)('keys');
var uid = __webpack_require__(14);
module.exports = function (key) {
  return shared[key] || (shared[key] = uid(key));
};


/***/ }),
/* 22 */
/***/ (function(module, exports, __webpack_require__) {

var core = __webpack_require__(0);
var global = __webpack_require__(1);
var SHARED = '__core-js_shared__';
var store = global[SHARED] || (global[SHARED] = {});

(module.exports = function (key, value) {
  return store[key] || (store[key] = value !== undefined ? value : {});
})('versions', []).push({
  version: core.version,
  mode: __webpack_require__(13) ? 'pure' : 'global',
  copyright: '© 2019 Denis Pushkarev (zloirock.ru)'
});


/***/ }),
/* 23 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


exports.__esModule = true;

exports.default = function (instance, Constructor) {
  if (!(instance instanceof Constructor)) {
    throw new TypeError("Cannot call a class as a function");
  }
};

/***/ }),
/* 24 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


exports.__esModule = true;

var _defineProperty = __webpack_require__(36);

var _defineProperty2 = _interopRequireDefault(_defineProperty);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

exports.default = function () {
  function defineProperties(target, props) {
    for (var i = 0; i < props.length; i++) {
      var descriptor = props[i];
      descriptor.enumerable = descriptor.enumerable || false;
      descriptor.configurable = true;
      if ("value" in descriptor) descriptor.writable = true;
      (0, _defineProperty2.default)(target, descriptor.key, descriptor);
    }
  }

  return function (Constructor, protoProps, staticProps) {
    if (protoProps) defineProperties(Constructor.prototype, protoProps);
    if (staticProps) defineProperties(Constructor, staticProps);
    return Constructor;
  };
}();

/***/ }),
/* 25 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


exports.__esModule = true;

var _typeof2 = __webpack_require__(41);

var _typeof3 = _interopRequireDefault(_typeof2);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

exports.default = function (self, call) {
  if (!self) {
    throw new ReferenceError("this hasn't been initialised - super() hasn't been called");
  }

  return call && ((typeof call === "undefined" ? "undefined" : (0, _typeof3.default)(call)) === "object" || typeof call === "function") ? call : self;
};

/***/ }),
/* 26 */
/***/ (function(module, exports) {

// 7.1.4 ToInteger
var ceil = Math.ceil;
var floor = Math.floor;
module.exports = function (it) {
  return isNaN(it = +it) ? 0 : (it > 0 ? floor : ceil)(it);
};


/***/ }),
/* 27 */
/***/ (function(module, exports) {

module.exports = {};


/***/ }),
/* 28 */
/***/ (function(module, exports, __webpack_require__) {

// 19.1.2.2 / 15.2.3.5 Object.create(O [, Properties])
var anObject = __webpack_require__(10);
var dPs = __webpack_require__(65);
var enumBugKeys = __webpack_require__(30);
var IE_PROTO = __webpack_require__(21)('IE_PROTO');
var Empty = function () { /* empty */ };
var PROTOTYPE = 'prototype';

// Create object with fake `null` prototype: use iframe Object with cleared prototype
var createDict = function () {
  // Thrash, waste and sodomy: IE GC bug
  var iframe = __webpack_require__(39)('iframe');
  var i = enumBugKeys.length;
  var lt = '<';
  var gt = '>';
  var iframeDocument;
  iframe.style.display = 'none';
  __webpack_require__(70).appendChild(iframe);
  iframe.src = 'javascript:'; // eslint-disable-line no-script-url
  // createDict = iframe.contentWindow.Object;
  // html.removeChild(iframe);
  iframeDocument = iframe.contentWindow.document;
  iframeDocument.open();
  iframeDocument.write(lt + 'script' + gt + 'document.F=Object' + lt + '/script' + gt);
  iframeDocument.close();
  createDict = iframeDocument.F;
  while (i--) delete createDict[PROTOTYPE][enumBugKeys[i]];
  return createDict();
};

module.exports = Object.create || function create(O, Properties) {
  var result;
  if (O !== null) {
    Empty[PROTOTYPE] = anObject(O);
    result = new Empty();
    Empty[PROTOTYPE] = null;
    // add "__proto__" for Object.getPrototypeOf polyfill
    result[IE_PROTO] = O;
  } else result = createDict();
  return Properties === undefined ? result : dPs(result, Properties);
};


/***/ }),
/* 29 */
/***/ (function(module, exports, __webpack_require__) {

// 19.1.2.14 / 15.2.3.14 Object.keys(O)
var $keys = __webpack_require__(44);
var enumBugKeys = __webpack_require__(30);

module.exports = Object.keys || function keys(O) {
  return $keys(O, enumBugKeys);
};


/***/ }),
/* 30 */
/***/ (function(module, exports) {

// IE 8- don't enum bug keys
module.exports = (
  'constructor,hasOwnProperty,isPrototypeOf,propertyIsEnumerable,toLocaleString,toString,valueOf'
).split(',');


/***/ }),
/* 31 */
/***/ (function(module, exports, __webpack_require__) {

var def = __webpack_require__(2).f;
var has = __webpack_require__(4);
var TAG = __webpack_require__(9)('toStringTag');

module.exports = function (it, tag, stat) {
  if (it && !has(it = stat ? it : it.prototype, TAG)) def(it, TAG, { configurable: true, value: tag });
};


/***/ }),
/* 32 */
/***/ (function(module, exports, __webpack_require__) {

exports.f = __webpack_require__(9);


/***/ }),
/* 33 */
/***/ (function(module, exports, __webpack_require__) {

var global = __webpack_require__(1);
var core = __webpack_require__(0);
var LIBRARY = __webpack_require__(13);
var wksExt = __webpack_require__(32);
var defineProperty = __webpack_require__(2).f;
module.exports = function (name) {
  var $Symbol = core.Symbol || (core.Symbol = LIBRARY ? {} : global.Symbol || {});
  if (name.charAt(0) != '_' && !(name in $Symbol)) defineProperty($Symbol, name, { value: wksExt.f(name) });
};


/***/ }),
/* 34 */
/***/ (function(module, exports) {

exports.f = {}.propertyIsEnumerable;


/***/ }),
/* 35 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


exports.__esModule = true;

var _setPrototypeOf = __webpack_require__(85);

var _setPrototypeOf2 = _interopRequireDefault(_setPrototypeOf);

var _create = __webpack_require__(89);

var _create2 = _interopRequireDefault(_create);

var _typeof2 = __webpack_require__(41);

var _typeof3 = _interopRequireDefault(_typeof2);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

exports.default = function (subClass, superClass) {
  if (typeof superClass !== "function" && superClass !== null) {
    throw new TypeError("Super expression must either be null or a function, not " + (typeof superClass === "undefined" ? "undefined" : (0, _typeof3.default)(superClass)));
  }

  subClass.prototype = (0, _create2.default)(superClass && superClass.prototype, {
    constructor: {
      value: subClass,
      enumerable: false,
      writable: true,
      configurable: true
    }
  });
  if (superClass) _setPrototypeOf2.default ? (0, _setPrototypeOf2.default)(subClass, superClass) : subClass.__proto__ = superClass;
};

/***/ }),
/* 36 */
/***/ (function(module, exports, __webpack_require__) {

module.exports = { "default": __webpack_require__(51), __esModule: true };

/***/ }),
/* 37 */
/***/ (function(module, exports, __webpack_require__) {

// optional / simple context binding
var aFunction = __webpack_require__(53);
module.exports = function (fn, that, length) {
  aFunction(fn);
  if (that === undefined) return fn;
  switch (length) {
    case 1: return function (a) {
      return fn.call(that, a);
    };
    case 2: return function (a, b) {
      return fn.call(that, a, b);
    };
    case 3: return function (a, b, c) {
      return fn.call(that, a, b, c);
    };
  }
  return function (/* ...args */) {
    return fn.apply(that, arguments);
  };
};


/***/ }),
/* 38 */
/***/ (function(module, exports, __webpack_require__) {

module.exports = !__webpack_require__(3) && !__webpack_require__(11)(function () {
  return Object.defineProperty(__webpack_require__(39)('div'), 'a', { get: function () { return 7; } }).a != 7;
});


/***/ }),
/* 39 */
/***/ (function(module, exports, __webpack_require__) {

var isObject = __webpack_require__(7);
var document = __webpack_require__(1).document;
// typeof document.createElement is 'object' in old IE
var is = isObject(document) && isObject(document.createElement);
module.exports = function (it) {
  return is ? document.createElement(it) : {};
};


/***/ }),
/* 40 */
/***/ (function(module, exports, __webpack_require__) {

// 19.1.2.9 / 15.2.3.2 Object.getPrototypeOf(O)
var has = __webpack_require__(4);
var toObject = __webpack_require__(19);
var IE_PROTO = __webpack_require__(21)('IE_PROTO');
var ObjectProto = Object.prototype;

module.exports = Object.getPrototypeOf || function (O) {
  O = toObject(O);
  if (has(O, IE_PROTO)) return O[IE_PROTO];
  if (typeof O.constructor == 'function' && O instanceof O.constructor) {
    return O.constructor.prototype;
  } return O instanceof Object ? ObjectProto : null;
};


/***/ }),
/* 41 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


exports.__esModule = true;

var _iterator = __webpack_require__(60);

var _iterator2 = _interopRequireDefault(_iterator);

var _symbol = __webpack_require__(75);

var _symbol2 = _interopRequireDefault(_symbol);

var _typeof = typeof _symbol2.default === "function" && typeof _iterator2.default === "symbol" ? function (obj) { return typeof obj; } : function (obj) { return obj && typeof _symbol2.default === "function" && obj.constructor === _symbol2.default && obj !== _symbol2.default.prototype ? "symbol" : typeof obj; };

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

exports.default = typeof _symbol2.default === "function" && _typeof(_iterator2.default) === "symbol" ? function (obj) {
  return typeof obj === "undefined" ? "undefined" : _typeof(obj);
} : function (obj) {
  return obj && typeof _symbol2.default === "function" && obj.constructor === _symbol2.default && obj !== _symbol2.default.prototype ? "symbol" : typeof obj === "undefined" ? "undefined" : _typeof(obj);
};

/***/ }),
/* 42 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";

var LIBRARY = __webpack_require__(13);
var $export = __webpack_require__(5);
var redefine = __webpack_require__(43);
var hide = __webpack_require__(6);
var Iterators = __webpack_require__(27);
var $iterCreate = __webpack_require__(64);
var setToStringTag = __webpack_require__(31);
var getPrototypeOf = __webpack_require__(40);
var ITERATOR = __webpack_require__(9)('iterator');
var BUGGY = !([].keys && 'next' in [].keys()); // Safari has buggy iterators w/o `next`
var FF_ITERATOR = '@@iterator';
var KEYS = 'keys';
var VALUES = 'values';

var returnThis = function () { return this; };

module.exports = function (Base, NAME, Constructor, next, DEFAULT, IS_SET, FORCED) {
  $iterCreate(Constructor, NAME, next);
  var getMethod = function (kind) {
    if (!BUGGY && kind in proto) return proto[kind];
    switch (kind) {
      case KEYS: return function keys() { return new Constructor(this, kind); };
      case VALUES: return function values() { return new Constructor(this, kind); };
    } return function entries() { return new Constructor(this, kind); };
  };
  var TAG = NAME + ' Iterator';
  var DEF_VALUES = DEFAULT == VALUES;
  var VALUES_BUG = false;
  var proto = Base.prototype;
  var $native = proto[ITERATOR] || proto[FF_ITERATOR] || DEFAULT && proto[DEFAULT];
  var $default = $native || getMethod(DEFAULT);
  var $entries = DEFAULT ? !DEF_VALUES ? $default : getMethod('entries') : undefined;
  var $anyNative = NAME == 'Array' ? proto.entries || $native : $native;
  var methods, key, IteratorPrototype;
  // Fix native
  if ($anyNative) {
    IteratorPrototype = getPrototypeOf($anyNative.call(new Base()));
    if (IteratorPrototype !== Object.prototype && IteratorPrototype.next) {
      // Set @@toStringTag to native iterators
      setToStringTag(IteratorPrototype, TAG, true);
      // fix for some old engines
      if (!LIBRARY && typeof IteratorPrototype[ITERATOR] != 'function') hide(IteratorPrototype, ITERATOR, returnThis);
    }
  }
  // fix Array#{values, @@iterator}.name in V8 / FF
  if (DEF_VALUES && $native && $native.name !== VALUES) {
    VALUES_BUG = true;
    $default = function values() { return $native.call(this); };
  }
  // Define iterator
  if ((!LIBRARY || FORCED) && (BUGGY || VALUES_BUG || !proto[ITERATOR])) {
    hide(proto, ITERATOR, $default);
  }
  // Plug for library
  Iterators[NAME] = $default;
  Iterators[TAG] = returnThis;
  if (DEFAULT) {
    methods = {
      values: DEF_VALUES ? $default : getMethod(VALUES),
      keys: IS_SET ? $default : getMethod(KEYS),
      entries: $entries
    };
    if (FORCED) for (key in methods) {
      if (!(key in proto)) redefine(proto, key, methods[key]);
    } else $export($export.P + $export.F * (BUGGY || VALUES_BUG), NAME, methods);
  }
  return methods;
};


/***/ }),
/* 43 */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(6);


/***/ }),
/* 44 */
/***/ (function(module, exports, __webpack_require__) {

var has = __webpack_require__(4);
var toIObject = __webpack_require__(8);
var arrayIndexOf = __webpack_require__(67)(false);
var IE_PROTO = __webpack_require__(21)('IE_PROTO');

module.exports = function (object, names) {
  var O = toIObject(object);
  var i = 0;
  var result = [];
  var key;
  for (key in O) if (key != IE_PROTO) has(O, key) && result.push(key);
  // Don't enum bug & hidden keys
  while (names.length > i) if (has(O, key = names[i++])) {
    ~arrayIndexOf(result, key) || result.push(key);
  }
  return result;
};


/***/ }),
/* 45 */
/***/ (function(module, exports) {

var toString = {}.toString;

module.exports = function (it) {
  return toString.call(it).slice(8, -1);
};


/***/ }),
/* 46 */
/***/ (function(module, exports) {

exports.f = Object.getOwnPropertySymbols;


/***/ }),
/* 47 */
/***/ (function(module, exports, __webpack_require__) {

// 19.1.2.7 / 15.2.3.4 Object.getOwnPropertyNames(O)
var $keys = __webpack_require__(44);
var hiddenKeys = __webpack_require__(30).concat('length', 'prototype');

exports.f = Object.getOwnPropertyNames || function getOwnPropertyNames(O) {
  return $keys(O, hiddenKeys);
};


/***/ }),
/* 48 */
/***/ (function(module, exports, __webpack_require__) {

var pIE = __webpack_require__(34);
var createDesc = __webpack_require__(12);
var toIObject = __webpack_require__(8);
var toPrimitive = __webpack_require__(16);
var has = __webpack_require__(4);
var IE8_DOM_DEFINE = __webpack_require__(38);
var gOPD = Object.getOwnPropertyDescriptor;

exports.f = __webpack_require__(3) ? gOPD : function getOwnPropertyDescriptor(O, P) {
  O = toIObject(O);
  P = toPrimitive(P, true);
  if (IE8_DOM_DEFINE) try {
    return gOPD(O, P);
  } catch (e) { /* empty */ }
  if (has(O, P)) return createDesc(!pIE.f.call(O, P), O[P]);
};


/***/ }),
/* 49 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__shortcode__ = __webpack_require__(50);


if (SSB.is_pro) {
  __webpack_require__(93);
}

/***/ }),
/* 50 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_babel_runtime_helpers_defineProperty__ = __webpack_require__(15);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_babel_runtime_helpers_defineProperty___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0_babel_runtime_helpers_defineProperty__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_classnames__ = __webpack_require__(17);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_classnames___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_1_classnames__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__editor_scss__ = __webpack_require__(54);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__editor_scss___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_2__editor_scss__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3__style_scss__ = __webpack_require__(55);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3__style_scss___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_3__style_scss__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_4__settings__ = __webpack_require__(56);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_5__buttons__ = __webpack_require__(92);

/**
 * Block dependencies.
 */






/**
 * Block libraries
 */

var __ = wp.i18n.__;
var Fragment = wp.element.Fragment;
var _wp$editor = wp.editor,
    InspectorControls = _wp$editor.InspectorControls,
    AlignmentToolbar = _wp$editor.AlignmentToolbar,
    BlockControls = _wp$editor.BlockControls,
    BlockAlignmentToolbar = _wp$editor.BlockAlignmentToolbar;
var registerBlockType = wp.blocks.registerBlockType;


var SSB_ALIGNMENT_CONTROLS = [{
  icon: "editor-alignleft",
  title: __("Align Left"),
  align: "left"
}, {
  icon: "editor-aligncenter",
  title: __("Align Center"),
  align: "centered"
}, {
  icon: "editor-alignright",
  title: __("Align Right"),
  align: "right"
}];

/* unused harmony default export */ var _unused_webpack_default_export = (registerBlockType("ssb/shortcode", {
  title: __("Simple Social Buttons"),
  description: __("Simple Social Buttons adds an advanced set of social media sharing buttons to your  sites, such as: Facebook, WhatsApp, Viber, Twitter, Reddit, LinkedIn and Pinterest."),
  category: "common",
  icon: "networking",
  keywords: [__("Social Share"), __("Button"), __("ssb")],
  attributes: {
    theme: {
      type: "string",
      default: "simple-icons"
    },
    counter: {
      type: "boolean",
      default: false
    },
    showTotalCount: {
      type: "boolean",
      default: false
    },
    align: {
      type: "string",
      default: ""
    },
    order: {
      type: "string",
      default: "fbshare,twitter,linkedin"
    },
    likeButtonSize: {
      type: "string",
      default: "small"
    },
    alignment: {
      type: "string",
      default: "left"
    }
  },
  getEditWrapperProps: function getEditWrapperProps(_ref) {
    var align = _ref.align;

    if ("center" === align || "wide" === align || "full" === align) {
      return { "data-align": align };
    }
  },

  supports: {
    // Turn off ability to edit HTML of block content
    html: false,
    // Turn off reusable block feature
    reusable: false
  },
  edit: function edit(props) {
    var _props$attributes = props.attributes,
        alignment = _props$attributes.alignment,
        align = _props$attributes.align,
        theme = _props$attributes.theme,
        counter = _props$attributes.counter,
        className = props.className,
        setAttributes = props.setAttributes;

    var mainClasses = __WEBPACK_IMPORTED_MODULE_1_classnames___default()(className, "simplesocialbuttons", "simplesocialbuttons_inline", __WEBPACK_IMPORTED_MODULE_0_babel_runtime_helpers_defineProperty___default()({}, "simplesocial-" + theme, theme), __WEBPACK_IMPORTED_MODULE_0_babel_runtime_helpers_defineProperty___default()({}, "align" + align, align), { "ssb_counter-activate": counter }, __WEBPACK_IMPORTED_MODULE_0_babel_runtime_helpers_defineProperty___default()({}, "simplesocialbuttons-align-" + alignment, alignment));
    return wp.element.createElement(
      Fragment,
      null,
      wp.element.createElement(
        BlockControls,
        null,
        wp.element.createElement(BlockAlignmentToolbar, {
          value: align,
          onChange: function onChange(align) {
            return setAttributes({ align: align });
          },
          controls: ["center", "wide", "full"]
        }),
        wp.element.createElement(AlignmentToolbar, {
          value: alignment,
          onChange: function onChange(alignment) {
            return setAttributes({ alignment: alignment });
          },
          alignmentControls: SSB_ALIGNMENT_CONTROLS
        })
      ),
      wp.element.createElement(
        InspectorControls,
        null,
        wp.element.createElement(__WEBPACK_IMPORTED_MODULE_4__settings__["a" /* default */], props)
      ),
      wp.element.createElement(
        "div",
        { className: mainClasses },
        wp.element.createElement(__WEBPACK_IMPORTED_MODULE_5__buttons__["a" /* default */], props)
      )
    );
  },
  save: function save(props) {
    return null;
  }
}));

/***/ }),
/* 51 */
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(52);
var $Object = __webpack_require__(0).Object;
module.exports = function defineProperty(it, key, desc) {
  return $Object.defineProperty(it, key, desc);
};


/***/ }),
/* 52 */
/***/ (function(module, exports, __webpack_require__) {

var $export = __webpack_require__(5);
// 19.1.2.4 / 15.2.3.6 Object.defineProperty(O, P, Attributes)
$export($export.S + $export.F * !__webpack_require__(3), 'Object', { defineProperty: __webpack_require__(2).f });


/***/ }),
/* 53 */
/***/ (function(module, exports) {

module.exports = function (it) {
  if (typeof it != 'function') throw TypeError(it + ' is not a function!');
  return it;
};


/***/ }),
/* 54 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),
/* 55 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),
/* 56 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_babel_runtime_core_js_object_get_prototype_of__ = __webpack_require__(18);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_babel_runtime_core_js_object_get_prototype_of___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0_babel_runtime_core_js_object_get_prototype_of__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_babel_runtime_helpers_classCallCheck__ = __webpack_require__(23);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_babel_runtime_helpers_classCallCheck___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_1_babel_runtime_helpers_classCallCheck__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2_babel_runtime_helpers_createClass__ = __webpack_require__(24);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2_babel_runtime_helpers_createClass___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_2_babel_runtime_helpers_createClass__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3_babel_runtime_helpers_possibleConstructorReturn__ = __webpack_require__(25);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3_babel_runtime_helpers_possibleConstructorReturn___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_3_babel_runtime_helpers_possibleConstructorReturn__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_4_babel_runtime_helpers_inherits__ = __webpack_require__(35);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_4_babel_runtime_helpers_inherits___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_4_babel_runtime_helpers_inherits__);





var __ = wp.i18n.__;
var _wp$element = wp.element,
    Component = _wp$element.Component,
    Fragment = _wp$element.Fragment;
var _wp$components = wp.components,
    PanelBody = _wp$components.PanelBody,
    PanelRow = _wp$components.PanelRow,
    SelectControl = _wp$components.SelectControl,
    ToggleControl = _wp$components.ToggleControl,
    TextControl = _wp$components.TextControl;
var AlignmentToolbar = wp.editor.AlignmentToolbar;

var Settings = function (_Component) {
  __WEBPACK_IMPORTED_MODULE_4_babel_runtime_helpers_inherits___default()(Settings, _Component);

  function Settings() {
    __WEBPACK_IMPORTED_MODULE_1_babel_runtime_helpers_classCallCheck___default()(this, Settings);

    return __WEBPACK_IMPORTED_MODULE_3_babel_runtime_helpers_possibleConstructorReturn___default()(this, (Settings.__proto__ || __WEBPACK_IMPORTED_MODULE_0_babel_runtime_core_js_object_get_prototype_of___default()(Settings)).apply(this, arguments));
  }

  __WEBPACK_IMPORTED_MODULE_2_babel_runtime_helpers_createClass___default()(Settings, [{
    key: "render",
    value: function render() {
      var _props = this.props,
          _props$attributes = _props.attributes,
          theme = _props$attributes.theme,
          counter = _props$attributes.counter,
          order = _props$attributes.order,
          likeButtonSize = _props$attributes.likeButtonSize,
          showTotalCount = _props$attributes.showTotalCount,
          setAttributes = _props.setAttributes;

      return wp.element.createElement(
        Fragment,
        null,
        wp.element.createElement(
          PanelBody,
          {
            title: __("Theme"),
            initialOpen: true,
            className: "ssb_panel_wrapper"
          },
          wp.element.createElement(
            PanelRow,
            null,
            wp.element.createElement(SelectControl, {
              value: theme,
              options: [{ label: "Theme1", value: "sm-round" }, { label: "Theme2", value: "simple-round" }, { label: "Theme3", value: "round-txt" }, { label: "Theme4", value: "round-btm-border" }, { label: "Flat", value: "flat-button-border" }, { label: "Circle", value: "round-icon" }, { label: "Official", value: "simple-icons" }],
              onChange: function onChange(theme) {
                return setAttributes({ theme: theme });
              }
            })
          )
        ),
        wp.element.createElement(
          PanelBody,
          {
            title: __("Configuration"),
            initialOpen: true,
            className: "ssb_panel_wrapper"
          },
          wp.element.createElement(
            PanelRow,
            null,
            wp.element.createElement(ToggleControl, {
              label: __("Share Counter"),
              checked: counter,
              onChange: function onChange(counter) {
                setAttributes({ counter: !!counter });
              }
            }),
            counter && wp.element.createElement(ToggleControl, {
              label: __("Total Counts"),
              checked: showTotalCount,
              onChange: function onChange(showTotalCount) {
                setAttributes({ showTotalCount: !!showTotalCount });
              }
            }),
            wp.element.createElement(TextControl, {
              help: "Supported Networks: fbshare,twitter,linkedin,pinterest,reddit,whatsapp,viber,tumblr,messenger,email,print,fblike",
              label: __("Order"),
              value: order,
              onChange: function onChange(order) {
                setAttributes({ order: order });
              }
            }),
            wp.element.createElement(SelectControl, {
              label: __("Like button size"),
              value: likeButtonSize,
              options: [{ label: "Small", value: "small" }, { label: "Large", value: "large" }],
              onChange: function onChange(size) {
                return setAttributes({ likeButtonSize: size });
              }
            })
          )
        )
      );
    }
  }]);

  return Settings;
}(Component);

/* harmony default export */ __webpack_exports__["a"] = (Settings);

/***/ }),
/* 57 */
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(58);
module.exports = __webpack_require__(0).Object.getPrototypeOf;


/***/ }),
/* 58 */
/***/ (function(module, exports, __webpack_require__) {

// 19.1.2.9 Object.getPrototypeOf(O)
var toObject = __webpack_require__(19);
var $getPrototypeOf = __webpack_require__(40);

__webpack_require__(59)('getPrototypeOf', function () {
  return function getPrototypeOf(it) {
    return $getPrototypeOf(toObject(it));
  };
});


/***/ }),
/* 59 */
/***/ (function(module, exports, __webpack_require__) {

// most Object methods by ES6 should accept primitives
var $export = __webpack_require__(5);
var core = __webpack_require__(0);
var fails = __webpack_require__(11);
module.exports = function (KEY, exec) {
  var fn = (core.Object || {})[KEY] || Object[KEY];
  var exp = {};
  exp[KEY] = exec(fn);
  $export($export.S + $export.F * fails(function () { fn(1); }), 'Object', exp);
};


/***/ }),
/* 60 */
/***/ (function(module, exports, __webpack_require__) {

module.exports = { "default": __webpack_require__(61), __esModule: true };

/***/ }),
/* 61 */
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(62);
__webpack_require__(71);
module.exports = __webpack_require__(32).f('iterator');


/***/ }),
/* 62 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";

var $at = __webpack_require__(63)(true);

// 21.1.3.27 String.prototype[@@iterator]()
__webpack_require__(42)(String, 'String', function (iterated) {
  this._t = String(iterated); // target
  this._i = 0;                // next index
// 21.1.5.2.1 %StringIteratorPrototype%.next()
}, function () {
  var O = this._t;
  var index = this._i;
  var point;
  if (index >= O.length) return { value: undefined, done: true };
  point = $at(O, index);
  this._i += point.length;
  return { value: point, done: false };
});


/***/ }),
/* 63 */
/***/ (function(module, exports, __webpack_require__) {

var toInteger = __webpack_require__(26);
var defined = __webpack_require__(20);
// true  -> String#at
// false -> String#codePointAt
module.exports = function (TO_STRING) {
  return function (that, pos) {
    var s = String(defined(that));
    var i = toInteger(pos);
    var l = s.length;
    var a, b;
    if (i < 0 || i >= l) return TO_STRING ? '' : undefined;
    a = s.charCodeAt(i);
    return a < 0xd800 || a > 0xdbff || i + 1 === l || (b = s.charCodeAt(i + 1)) < 0xdc00 || b > 0xdfff
      ? TO_STRING ? s.charAt(i) : a
      : TO_STRING ? s.slice(i, i + 2) : (a - 0xd800 << 10) + (b - 0xdc00) + 0x10000;
  };
};


/***/ }),
/* 64 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";

var create = __webpack_require__(28);
var descriptor = __webpack_require__(12);
var setToStringTag = __webpack_require__(31);
var IteratorPrototype = {};

// 25.1.2.1.1 %IteratorPrototype%[@@iterator]()
__webpack_require__(6)(IteratorPrototype, __webpack_require__(9)('iterator'), function () { return this; });

module.exports = function (Constructor, NAME, next) {
  Constructor.prototype = create(IteratorPrototype, { next: descriptor(1, next) });
  setToStringTag(Constructor, NAME + ' Iterator');
};


/***/ }),
/* 65 */
/***/ (function(module, exports, __webpack_require__) {

var dP = __webpack_require__(2);
var anObject = __webpack_require__(10);
var getKeys = __webpack_require__(29);

module.exports = __webpack_require__(3) ? Object.defineProperties : function defineProperties(O, Properties) {
  anObject(O);
  var keys = getKeys(Properties);
  var length = keys.length;
  var i = 0;
  var P;
  while (length > i) dP.f(O, P = keys[i++], Properties[P]);
  return O;
};


/***/ }),
/* 66 */
/***/ (function(module, exports, __webpack_require__) {

// fallback for non-array-like ES3 and non-enumerable old V8 strings
var cof = __webpack_require__(45);
// eslint-disable-next-line no-prototype-builtins
module.exports = Object('z').propertyIsEnumerable(0) ? Object : function (it) {
  return cof(it) == 'String' ? it.split('') : Object(it);
};


/***/ }),
/* 67 */
/***/ (function(module, exports, __webpack_require__) {

// false -> Array#indexOf
// true  -> Array#includes
var toIObject = __webpack_require__(8);
var toLength = __webpack_require__(68);
var toAbsoluteIndex = __webpack_require__(69);
module.exports = function (IS_INCLUDES) {
  return function ($this, el, fromIndex) {
    var O = toIObject($this);
    var length = toLength(O.length);
    var index = toAbsoluteIndex(fromIndex, length);
    var value;
    // Array#includes uses SameValueZero equality algorithm
    // eslint-disable-next-line no-self-compare
    if (IS_INCLUDES && el != el) while (length > index) {
      value = O[index++];
      // eslint-disable-next-line no-self-compare
      if (value != value) return true;
    // Array#indexOf ignores holes, Array#includes - not
    } else for (;length > index; index++) if (IS_INCLUDES || index in O) {
      if (O[index] === el) return IS_INCLUDES || index || 0;
    } return !IS_INCLUDES && -1;
  };
};


/***/ }),
/* 68 */
/***/ (function(module, exports, __webpack_require__) {

// 7.1.15 ToLength
var toInteger = __webpack_require__(26);
var min = Math.min;
module.exports = function (it) {
  return it > 0 ? min(toInteger(it), 0x1fffffffffffff) : 0; // pow(2, 53) - 1 == 9007199254740991
};


/***/ }),
/* 69 */
/***/ (function(module, exports, __webpack_require__) {

var toInteger = __webpack_require__(26);
var max = Math.max;
var min = Math.min;
module.exports = function (index, length) {
  index = toInteger(index);
  return index < 0 ? max(index + length, 0) : min(index, length);
};


/***/ }),
/* 70 */
/***/ (function(module, exports, __webpack_require__) {

var document = __webpack_require__(1).document;
module.exports = document && document.documentElement;


/***/ }),
/* 71 */
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(72);
var global = __webpack_require__(1);
var hide = __webpack_require__(6);
var Iterators = __webpack_require__(27);
var TO_STRING_TAG = __webpack_require__(9)('toStringTag');

var DOMIterables = ('CSSRuleList,CSSStyleDeclaration,CSSValueList,ClientRectList,DOMRectList,DOMStringList,' +
  'DOMTokenList,DataTransferItemList,FileList,HTMLAllCollection,HTMLCollection,HTMLFormElement,HTMLSelectElement,' +
  'MediaList,MimeTypeArray,NamedNodeMap,NodeList,PaintRequestList,Plugin,PluginArray,SVGLengthList,SVGNumberList,' +
  'SVGPathSegList,SVGPointList,SVGStringList,SVGTransformList,SourceBufferList,StyleSheetList,TextTrackCueList,' +
  'TextTrackList,TouchList').split(',');

for (var i = 0; i < DOMIterables.length; i++) {
  var NAME = DOMIterables[i];
  var Collection = global[NAME];
  var proto = Collection && Collection.prototype;
  if (proto && !proto[TO_STRING_TAG]) hide(proto, TO_STRING_TAG, NAME);
  Iterators[NAME] = Iterators.Array;
}


/***/ }),
/* 72 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";

var addToUnscopables = __webpack_require__(73);
var step = __webpack_require__(74);
var Iterators = __webpack_require__(27);
var toIObject = __webpack_require__(8);

// 22.1.3.4 Array.prototype.entries()
// 22.1.3.13 Array.prototype.keys()
// 22.1.3.29 Array.prototype.values()
// 22.1.3.30 Array.prototype[@@iterator]()
module.exports = __webpack_require__(42)(Array, 'Array', function (iterated, kind) {
  this._t = toIObject(iterated); // target
  this._i = 0;                   // next index
  this._k = kind;                // kind
// 22.1.5.2.1 %ArrayIteratorPrototype%.next()
}, function () {
  var O = this._t;
  var kind = this._k;
  var index = this._i++;
  if (!O || index >= O.length) {
    this._t = undefined;
    return step(1);
  }
  if (kind == 'keys') return step(0, index);
  if (kind == 'values') return step(0, O[index]);
  return step(0, [index, O[index]]);
}, 'values');

// argumentsList[@@iterator] is %ArrayProto_values% (9.4.4.6, 9.4.4.7)
Iterators.Arguments = Iterators.Array;

addToUnscopables('keys');
addToUnscopables('values');
addToUnscopables('entries');


/***/ }),
/* 73 */
/***/ (function(module, exports) {

module.exports = function () { /* empty */ };


/***/ }),
/* 74 */
/***/ (function(module, exports) {

module.exports = function (done, value) {
  return { value: value, done: !!done };
};


/***/ }),
/* 75 */
/***/ (function(module, exports, __webpack_require__) {

module.exports = { "default": __webpack_require__(76), __esModule: true };

/***/ }),
/* 76 */
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(77);
__webpack_require__(82);
__webpack_require__(83);
__webpack_require__(84);
module.exports = __webpack_require__(0).Symbol;


/***/ }),
/* 77 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";

// ECMAScript 6 symbols shim
var global = __webpack_require__(1);
var has = __webpack_require__(4);
var DESCRIPTORS = __webpack_require__(3);
var $export = __webpack_require__(5);
var redefine = __webpack_require__(43);
var META = __webpack_require__(78).KEY;
var $fails = __webpack_require__(11);
var shared = __webpack_require__(22);
var setToStringTag = __webpack_require__(31);
var uid = __webpack_require__(14);
var wks = __webpack_require__(9);
var wksExt = __webpack_require__(32);
var wksDefine = __webpack_require__(33);
var enumKeys = __webpack_require__(79);
var isArray = __webpack_require__(80);
var anObject = __webpack_require__(10);
var isObject = __webpack_require__(7);
var toObject = __webpack_require__(19);
var toIObject = __webpack_require__(8);
var toPrimitive = __webpack_require__(16);
var createDesc = __webpack_require__(12);
var _create = __webpack_require__(28);
var gOPNExt = __webpack_require__(81);
var $GOPD = __webpack_require__(48);
var $GOPS = __webpack_require__(46);
var $DP = __webpack_require__(2);
var $keys = __webpack_require__(29);
var gOPD = $GOPD.f;
var dP = $DP.f;
var gOPN = gOPNExt.f;
var $Symbol = global.Symbol;
var $JSON = global.JSON;
var _stringify = $JSON && $JSON.stringify;
var PROTOTYPE = 'prototype';
var HIDDEN = wks('_hidden');
var TO_PRIMITIVE = wks('toPrimitive');
var isEnum = {}.propertyIsEnumerable;
var SymbolRegistry = shared('symbol-registry');
var AllSymbols = shared('symbols');
var OPSymbols = shared('op-symbols');
var ObjectProto = Object[PROTOTYPE];
var USE_NATIVE = typeof $Symbol == 'function' && !!$GOPS.f;
var QObject = global.QObject;
// Don't use setters in Qt Script, https://github.com/zloirock/core-js/issues/173
var setter = !QObject || !QObject[PROTOTYPE] || !QObject[PROTOTYPE].findChild;

// fallback for old Android, https://code.google.com/p/v8/issues/detail?id=687
var setSymbolDesc = DESCRIPTORS && $fails(function () {
  return _create(dP({}, 'a', {
    get: function () { return dP(this, 'a', { value: 7 }).a; }
  })).a != 7;
}) ? function (it, key, D) {
  var protoDesc = gOPD(ObjectProto, key);
  if (protoDesc) delete ObjectProto[key];
  dP(it, key, D);
  if (protoDesc && it !== ObjectProto) dP(ObjectProto, key, protoDesc);
} : dP;

var wrap = function (tag) {
  var sym = AllSymbols[tag] = _create($Symbol[PROTOTYPE]);
  sym._k = tag;
  return sym;
};

var isSymbol = USE_NATIVE && typeof $Symbol.iterator == 'symbol' ? function (it) {
  return typeof it == 'symbol';
} : function (it) {
  return it instanceof $Symbol;
};

var $defineProperty = function defineProperty(it, key, D) {
  if (it === ObjectProto) $defineProperty(OPSymbols, key, D);
  anObject(it);
  key = toPrimitive(key, true);
  anObject(D);
  if (has(AllSymbols, key)) {
    if (!D.enumerable) {
      if (!has(it, HIDDEN)) dP(it, HIDDEN, createDesc(1, {}));
      it[HIDDEN][key] = true;
    } else {
      if (has(it, HIDDEN) && it[HIDDEN][key]) it[HIDDEN][key] = false;
      D = _create(D, { enumerable: createDesc(0, false) });
    } return setSymbolDesc(it, key, D);
  } return dP(it, key, D);
};
var $defineProperties = function defineProperties(it, P) {
  anObject(it);
  var keys = enumKeys(P = toIObject(P));
  var i = 0;
  var l = keys.length;
  var key;
  while (l > i) $defineProperty(it, key = keys[i++], P[key]);
  return it;
};
var $create = function create(it, P) {
  return P === undefined ? _create(it) : $defineProperties(_create(it), P);
};
var $propertyIsEnumerable = function propertyIsEnumerable(key) {
  var E = isEnum.call(this, key = toPrimitive(key, true));
  if (this === ObjectProto && has(AllSymbols, key) && !has(OPSymbols, key)) return false;
  return E || !has(this, key) || !has(AllSymbols, key) || has(this, HIDDEN) && this[HIDDEN][key] ? E : true;
};
var $getOwnPropertyDescriptor = function getOwnPropertyDescriptor(it, key) {
  it = toIObject(it);
  key = toPrimitive(key, true);
  if (it === ObjectProto && has(AllSymbols, key) && !has(OPSymbols, key)) return;
  var D = gOPD(it, key);
  if (D && has(AllSymbols, key) && !(has(it, HIDDEN) && it[HIDDEN][key])) D.enumerable = true;
  return D;
};
var $getOwnPropertyNames = function getOwnPropertyNames(it) {
  var names = gOPN(toIObject(it));
  var result = [];
  var i = 0;
  var key;
  while (names.length > i) {
    if (!has(AllSymbols, key = names[i++]) && key != HIDDEN && key != META) result.push(key);
  } return result;
};
var $getOwnPropertySymbols = function getOwnPropertySymbols(it) {
  var IS_OP = it === ObjectProto;
  var names = gOPN(IS_OP ? OPSymbols : toIObject(it));
  var result = [];
  var i = 0;
  var key;
  while (names.length > i) {
    if (has(AllSymbols, key = names[i++]) && (IS_OP ? has(ObjectProto, key) : true)) result.push(AllSymbols[key]);
  } return result;
};

// 19.4.1.1 Symbol([description])
if (!USE_NATIVE) {
  $Symbol = function Symbol() {
    if (this instanceof $Symbol) throw TypeError('Symbol is not a constructor!');
    var tag = uid(arguments.length > 0 ? arguments[0] : undefined);
    var $set = function (value) {
      if (this === ObjectProto) $set.call(OPSymbols, value);
      if (has(this, HIDDEN) && has(this[HIDDEN], tag)) this[HIDDEN][tag] = false;
      setSymbolDesc(this, tag, createDesc(1, value));
    };
    if (DESCRIPTORS && setter) setSymbolDesc(ObjectProto, tag, { configurable: true, set: $set });
    return wrap(tag);
  };
  redefine($Symbol[PROTOTYPE], 'toString', function toString() {
    return this._k;
  });

  $GOPD.f = $getOwnPropertyDescriptor;
  $DP.f = $defineProperty;
  __webpack_require__(47).f = gOPNExt.f = $getOwnPropertyNames;
  __webpack_require__(34).f = $propertyIsEnumerable;
  $GOPS.f = $getOwnPropertySymbols;

  if (DESCRIPTORS && !__webpack_require__(13)) {
    redefine(ObjectProto, 'propertyIsEnumerable', $propertyIsEnumerable, true);
  }

  wksExt.f = function (name) {
    return wrap(wks(name));
  };
}

$export($export.G + $export.W + $export.F * !USE_NATIVE, { Symbol: $Symbol });

for (var es6Symbols = (
  // 19.4.2.2, 19.4.2.3, 19.4.2.4, 19.4.2.6, 19.4.2.8, 19.4.2.9, 19.4.2.10, 19.4.2.11, 19.4.2.12, 19.4.2.13, 19.4.2.14
  'hasInstance,isConcatSpreadable,iterator,match,replace,search,species,split,toPrimitive,toStringTag,unscopables'
).split(','), j = 0; es6Symbols.length > j;)wks(es6Symbols[j++]);

for (var wellKnownSymbols = $keys(wks.store), k = 0; wellKnownSymbols.length > k;) wksDefine(wellKnownSymbols[k++]);

$export($export.S + $export.F * !USE_NATIVE, 'Symbol', {
  // 19.4.2.1 Symbol.for(key)
  'for': function (key) {
    return has(SymbolRegistry, key += '')
      ? SymbolRegistry[key]
      : SymbolRegistry[key] = $Symbol(key);
  },
  // 19.4.2.5 Symbol.keyFor(sym)
  keyFor: function keyFor(sym) {
    if (!isSymbol(sym)) throw TypeError(sym + ' is not a symbol!');
    for (var key in SymbolRegistry) if (SymbolRegistry[key] === sym) return key;
  },
  useSetter: function () { setter = true; },
  useSimple: function () { setter = false; }
});

$export($export.S + $export.F * !USE_NATIVE, 'Object', {
  // 19.1.2.2 Object.create(O [, Properties])
  create: $create,
  // 19.1.2.4 Object.defineProperty(O, P, Attributes)
  defineProperty: $defineProperty,
  // 19.1.2.3 Object.defineProperties(O, Properties)
  defineProperties: $defineProperties,
  // 19.1.2.6 Object.getOwnPropertyDescriptor(O, P)
  getOwnPropertyDescriptor: $getOwnPropertyDescriptor,
  // 19.1.2.7 Object.getOwnPropertyNames(O)
  getOwnPropertyNames: $getOwnPropertyNames,
  // 19.1.2.8 Object.getOwnPropertySymbols(O)
  getOwnPropertySymbols: $getOwnPropertySymbols
});

// Chrome 38 and 39 `Object.getOwnPropertySymbols` fails on primitives
// https://bugs.chromium.org/p/v8/issues/detail?id=3443
var FAILS_ON_PRIMITIVES = $fails(function () { $GOPS.f(1); });

$export($export.S + $export.F * FAILS_ON_PRIMITIVES, 'Object', {
  getOwnPropertySymbols: function getOwnPropertySymbols(it) {
    return $GOPS.f(toObject(it));
  }
});

// 24.3.2 JSON.stringify(value [, replacer [, space]])
$JSON && $export($export.S + $export.F * (!USE_NATIVE || $fails(function () {
  var S = $Symbol();
  // MS Edge converts symbol values to JSON as {}
  // WebKit converts symbol values to JSON as null
  // V8 throws on boxed symbols
  return _stringify([S]) != '[null]' || _stringify({ a: S }) != '{}' || _stringify(Object(S)) != '{}';
})), 'JSON', {
  stringify: function stringify(it) {
    var args = [it];
    var i = 1;
    var replacer, $replacer;
    while (arguments.length > i) args.push(arguments[i++]);
    $replacer = replacer = args[1];
    if (!isObject(replacer) && it === undefined || isSymbol(it)) return; // IE8 returns string on undefined
    if (!isArray(replacer)) replacer = function (key, value) {
      if (typeof $replacer == 'function') value = $replacer.call(this, key, value);
      if (!isSymbol(value)) return value;
    };
    args[1] = replacer;
    return _stringify.apply($JSON, args);
  }
});

// 19.4.3.4 Symbol.prototype[@@toPrimitive](hint)
$Symbol[PROTOTYPE][TO_PRIMITIVE] || __webpack_require__(6)($Symbol[PROTOTYPE], TO_PRIMITIVE, $Symbol[PROTOTYPE].valueOf);
// 19.4.3.5 Symbol.prototype[@@toStringTag]
setToStringTag($Symbol, 'Symbol');
// 20.2.1.9 Math[@@toStringTag]
setToStringTag(Math, 'Math', true);
// 24.3.3 JSON[@@toStringTag]
setToStringTag(global.JSON, 'JSON', true);


/***/ }),
/* 78 */
/***/ (function(module, exports, __webpack_require__) {

var META = __webpack_require__(14)('meta');
var isObject = __webpack_require__(7);
var has = __webpack_require__(4);
var setDesc = __webpack_require__(2).f;
var id = 0;
var isExtensible = Object.isExtensible || function () {
  return true;
};
var FREEZE = !__webpack_require__(11)(function () {
  return isExtensible(Object.preventExtensions({}));
});
var setMeta = function (it) {
  setDesc(it, META, { value: {
    i: 'O' + ++id, // object ID
    w: {}          // weak collections IDs
  } });
};
var fastKey = function (it, create) {
  // return primitive with prefix
  if (!isObject(it)) return typeof it == 'symbol' ? it : (typeof it == 'string' ? 'S' : 'P') + it;
  if (!has(it, META)) {
    // can't set metadata to uncaught frozen object
    if (!isExtensible(it)) return 'F';
    // not necessary to add metadata
    if (!create) return 'E';
    // add missing metadata
    setMeta(it);
  // return object ID
  } return it[META].i;
};
var getWeak = function (it, create) {
  if (!has(it, META)) {
    // can't set metadata to uncaught frozen object
    if (!isExtensible(it)) return true;
    // not necessary to add metadata
    if (!create) return false;
    // add missing metadata
    setMeta(it);
  // return hash weak collections IDs
  } return it[META].w;
};
// add metadata on freeze-family methods calling
var onFreeze = function (it) {
  if (FREEZE && meta.NEED && isExtensible(it) && !has(it, META)) setMeta(it);
  return it;
};
var meta = module.exports = {
  KEY: META,
  NEED: false,
  fastKey: fastKey,
  getWeak: getWeak,
  onFreeze: onFreeze
};


/***/ }),
/* 79 */
/***/ (function(module, exports, __webpack_require__) {

// all enumerable object keys, includes symbols
var getKeys = __webpack_require__(29);
var gOPS = __webpack_require__(46);
var pIE = __webpack_require__(34);
module.exports = function (it) {
  var result = getKeys(it);
  var getSymbols = gOPS.f;
  if (getSymbols) {
    var symbols = getSymbols(it);
    var isEnum = pIE.f;
    var i = 0;
    var key;
    while (symbols.length > i) if (isEnum.call(it, key = symbols[i++])) result.push(key);
  } return result;
};


/***/ }),
/* 80 */
/***/ (function(module, exports, __webpack_require__) {

// 7.2.2 IsArray(argument)
var cof = __webpack_require__(45);
module.exports = Array.isArray || function isArray(arg) {
  return cof(arg) == 'Array';
};


/***/ }),
/* 81 */
/***/ (function(module, exports, __webpack_require__) {

// fallback for IE11 buggy Object.getOwnPropertyNames with iframe and window
var toIObject = __webpack_require__(8);
var gOPN = __webpack_require__(47).f;
var toString = {}.toString;

var windowNames = typeof window == 'object' && window && Object.getOwnPropertyNames
  ? Object.getOwnPropertyNames(window) : [];

var getWindowNames = function (it) {
  try {
    return gOPN(it);
  } catch (e) {
    return windowNames.slice();
  }
};

module.exports.f = function getOwnPropertyNames(it) {
  return windowNames && toString.call(it) == '[object Window]' ? getWindowNames(it) : gOPN(toIObject(it));
};


/***/ }),
/* 82 */
/***/ (function(module, exports) {



/***/ }),
/* 83 */
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(33)('asyncIterator');


/***/ }),
/* 84 */
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(33)('observable');


/***/ }),
/* 85 */
/***/ (function(module, exports, __webpack_require__) {

module.exports = { "default": __webpack_require__(86), __esModule: true };

/***/ }),
/* 86 */
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(87);
module.exports = __webpack_require__(0).Object.setPrototypeOf;


/***/ }),
/* 87 */
/***/ (function(module, exports, __webpack_require__) {

// 19.1.3.19 Object.setPrototypeOf(O, proto)
var $export = __webpack_require__(5);
$export($export.S, 'Object', { setPrototypeOf: __webpack_require__(88).set });


/***/ }),
/* 88 */
/***/ (function(module, exports, __webpack_require__) {

// Works with __proto__ only. Old v8 can't work with null proto objects.
/* eslint-disable no-proto */
var isObject = __webpack_require__(7);
var anObject = __webpack_require__(10);
var check = function (O, proto) {
  anObject(O);
  if (!isObject(proto) && proto !== null) throw TypeError(proto + ": can't set as prototype!");
};
module.exports = {
  set: Object.setPrototypeOf || ('__proto__' in {} ? // eslint-disable-line
    function (test, buggy, set) {
      try {
        set = __webpack_require__(37)(Function.call, __webpack_require__(48).f(Object.prototype, '__proto__').set, 2);
        set(test, []);
        buggy = !(test instanceof Array);
      } catch (e) { buggy = true; }
      return function setPrototypeOf(O, proto) {
        check(O, proto);
        if (buggy) O.__proto__ = proto;
        else set(O, proto);
        return O;
      };
    }({}, false) : undefined),
  check: check
};


/***/ }),
/* 89 */
/***/ (function(module, exports, __webpack_require__) {

module.exports = { "default": __webpack_require__(90), __esModule: true };

/***/ }),
/* 90 */
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(91);
var $Object = __webpack_require__(0).Object;
module.exports = function create(P, D) {
  return $Object.create(P, D);
};


/***/ }),
/* 91 */
/***/ (function(module, exports, __webpack_require__) {

var $export = __webpack_require__(5);
// 19.1.2.2 / 15.2.3.5 Object.create(O [, Properties])
$export($export.S, 'Object', { create: __webpack_require__(28) });


/***/ }),
/* 92 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_babel_runtime_helpers_defineProperty__ = __webpack_require__(15);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_babel_runtime_helpers_defineProperty___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0_babel_runtime_helpers_defineProperty__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_babel_runtime_core_js_object_get_prototype_of__ = __webpack_require__(18);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_babel_runtime_core_js_object_get_prototype_of___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_1_babel_runtime_core_js_object_get_prototype_of__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2_babel_runtime_helpers_classCallCheck__ = __webpack_require__(23);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2_babel_runtime_helpers_classCallCheck___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_2_babel_runtime_helpers_classCallCheck__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3_babel_runtime_helpers_createClass__ = __webpack_require__(24);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3_babel_runtime_helpers_createClass___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_3_babel_runtime_helpers_createClass__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_4_babel_runtime_helpers_possibleConstructorReturn__ = __webpack_require__(25);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_4_babel_runtime_helpers_possibleConstructorReturn___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_4_babel_runtime_helpers_possibleConstructorReturn__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_5_babel_runtime_helpers_inherits__ = __webpack_require__(35);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_5_babel_runtime_helpers_inherits___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_5_babel_runtime_helpers_inherits__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_6_classnames__ = __webpack_require__(17);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_6_classnames___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_6_classnames__);






/**
 * External dependencies
 */


var __ = wp.i18n.__;
var _wp$element = wp.element,
    Component = _wp$element.Component,
    Fragment = _wp$element.Fragment;

var Buttons = function (_Component) {
  __WEBPACK_IMPORTED_MODULE_5_babel_runtime_helpers_inherits___default()(Buttons, _Component);

  function Buttons() {
    __WEBPACK_IMPORTED_MODULE_2_babel_runtime_helpers_classCallCheck___default()(this, Buttons);

    return __WEBPACK_IMPORTED_MODULE_4_babel_runtime_helpers_possibleConstructorReturn___default()(this, (Buttons.__proto__ || __WEBPACK_IMPORTED_MODULE_1_babel_runtime_core_js_object_get_prototype_of___default()(Buttons)).apply(this, arguments));
  }

  __WEBPACK_IMPORTED_MODULE_3_babel_runtime_helpers_createClass___default()(Buttons, [{
    key: "render",
    value: function render() {
      var _props$attributes = this.props.attributes,
          theme = _props$attributes.theme,
          order = _props$attributes.order,
          counter = _props$attributes.counter,
          likeButtonSize = _props$attributes.likeButtonSize,
          showTotalCount = _props$attributes.showTotalCount;


      var output = Array();
      var showCounter = counter;
      var buttonStyle = {};
      //fblike, totalshare -> having issue
      var knownNetworks = Array("twitter", "pinterest", "fbshare", "linkedin", "reddit", "whatsapp", "viber", "messenger", "email", "fblike", "print", "tumblr");

      var selectedNetworks = order.split(",");
      if (counter && showTotalCount) {
        selectedNetworks.push("totalshare");
      }
      selectedNetworks.forEach(function (network, index) {

        switch (network) {
          case "fbshare":
            if ("simple-icons" === theme) {
              output.push(wp.element.createElement(
                "button",
                { "class": "ssb_fbshare-icon", target: "_blank" },
                wp.element.createElement(
                  "span",
                  { "class": "icon" },
                  wp.element.createElement(
                    "svg",
                    {
                      xmlns: "http://www.w3.org/2000/svg",
                      viewBox: "0 0 16 16",
                      "class": "_1pbq",
                      color: "#ffffff"
                    },
                    wp.element.createElement("path", {
                      fill: "#ffffff",
                      "fill-rule": "evenodd",
                      "class": "icon",
                      d: "M8 14H3.667C2.733 13.9 2 13.167 2 12.233V3.667A1.65 1.65 0 0 1 3.667 2h8.666A1.65 1.65 0 0 1 14 3.667v8.566c0 .934-.733 1.667-1.667 1.767H10v-3.967h1.3l.7-2.066h-2V6.933c0-.466.167-.9.867-.9H12v-1.8c.033 0-.933-.266-1.533-.266-1.267 0-2.434.7-2.467 2.133v1.867H6v2.066h2V14z"
                    })
                  )
                ),
                wp.element.createElement(
                  "span",
                  { "class": "simplesocialtxt" },
                  "Share "
                ),
                showCounter && wp.element.createElement(
                  "span",
                  { "class": "ssb_counter" },
                  " 5 "
                )
              ));
            } else {
              output.push(wp.element.createElement(
                "button",
                { "class": "simplesocial-fb-share", style: buttonStyle },
                wp.element.createElement(
                  "span",
                  { "class": "simplesocialtxt" },
                  "Facebook "
                ),
                showCounter && wp.element.createElement(
                  "span",
                  { "class": "ssb_counter ssb_fbshare_counter" },
                  "5"
                )
              ));
            }
            break;
          case "twitter":
            if ("simple-icons" === theme) {
              output.push(wp.element.createElement(
                "button",
                { "class": "ssb_tweet-icon", rel: "nofollow" },
                wp.element.createElement(
                  "span",
                  { "class": "icon" },
                  wp.element.createElement(
                    "svg",
                    { xmlns: "http://www.w3.org/2000/svg", viewBox: "0 0 72 72" },
                    wp.element.createElement("path", { fill: "none", d: "M0 0h72v72H0z" }),
                    wp.element.createElement("path", {
                      "class": "icon",
                      fill: "#fff",
                      d: "M68.812 15.14c-2.348 1.04-4.87 1.744-7.52 2.06 2.704-1.62 4.78-4.186 5.757-7.243-2.53 1.5-5.33 2.592-8.314 3.176C56.35 10.59 52.948 9 49.182 9c-7.23 0-13.092 5.86-13.092 13.093 0 1.026.118 2.02.338 2.98C25.543 24.527 15.9 19.318 9.44 11.396c-1.125 1.936-1.77 4.184-1.77 6.58 0 4.543 2.312 8.552 5.824 10.9-2.146-.07-4.165-.658-5.93-1.64-.002.056-.002.11-.002.163 0 6.345 4.513 11.638 10.504 12.84-1.1.298-2.256.457-3.45.457-.845 0-1.666-.078-2.464-.23 1.667 5.2 6.5 8.985 12.23 9.09-4.482 3.51-10.13 5.605-16.26 5.605-1.055 0-2.096-.06-3.122-.184 5.794 3.717 12.676 5.882 20.067 5.882 24.083 0 37.25-19.95 37.25-37.25 0-.565-.013-1.133-.038-1.693 2.558-1.847 4.778-4.15 6.532-6.774z"
                    })
                  )
                ),
                showCounter ? wp.element.createElement(
                  "i",
                  { "class": "simplesocialtxt" },
                  "Tweet 5 "
                ) : wp.element.createElement(
                  "i",
                  { "class": "simplesocialtxt" },
                  "Tweet "
                )
              ));
            } else {
              output.push(wp.element.createElement(
                "button",
                {
                  "class": "simplesocial-twt-share",
                  rel: "nofollow",
                  style: buttonStyle
                },
                wp.element.createElement(
                  "span",
                  { "class": "simplesocialtxt" },
                  "Twitter"
                ),
                showCounter && wp.element.createElement(
                  "span",
                  { "class": "ssb_counter ssb_twitter_counter" },
                  " 5 "
                )
              ));
            }
            break;

          case "linkedin":
            if ("simple-icons" === theme) {
              output.push(wp.element.createElement(
                "button",
                { "class": "ssb_linkedin-icon" },
                wp.element.createElement(
                  "span",
                  { "class": "icon" },
                  wp.element.createElement(
                    "svg",
                    {
                      xmlns: "http://www.w3.org/2000/svg",
                      width: "15",
                      height: "14.1",
                      x: "0",
                      y: "0",
                      version: "1.1",
                      viewBox: "-301.4 387.5 15 14.1",
                      xmlSpace: "preserve"
                    },
                    wp.element.createElement(
                      "g",
                      { fill: "#FFF" },
                      wp.element.createElement("path", { d: "M-296.2 401.6v-9.5h3c.1 0 .1 0 .1.1v1.2c.1-.1.2-.3.3-.4.5-.7 1.2-1 2.1-1.1.8-.1 1.5 0 2.2.3.7.4 1.2.8 1.5 1.4.4.8.6 1.7.6 2.5v5.5h-3.2v-.2-4.8c0-.4 0-.8-.2-1.2-.2-.7-.8-1-1.6-1-.8.1-1.3.5-1.6 1.2-.1.2-.1.5-.1.8v5.1c0 .2 0 .2-.2.2h-2.9c.1-.1 0-.1 0-.1zM-298 401.6h-3c-.1 0-.1 0-.1-.1v-9.2c0-.1 0-.1.1-.1h3v9.4zM-299.6 390.9c-.7-.1-1.2-.3-1.6-.8-.5-.8-.2-2.1 1-2.4.6-.2 1.2-.1 1.8.2.5.4.7.9.6 1.5-.1.7-.5 1.1-1.1 1.3-.2.1-.5.1-.7.2z" })
                    )
                  )
                ),
                wp.element.createElement(
                  "span",
                  { "class": "simplesocialtxt" },
                  "Share"
                )
              ));
            } else {
              output.push(wp.element.createElement(
                "button",
                { target: "popup", "class": "simplesocial-linkedin-share" },
                wp.element.createElement(
                  "span",
                  { "class": "simplesocialtxt" },
                  "LinkedIn"
                )
              ));
            }
            break;
          case "pinterest":
            if ("simple-icons" === theme) {
              output.push(wp.element.createElement(
                "button",
                { "class": "ssb_pinterest-icon" },
                wp.element.createElement(
                  "span",
                  { "class": "icon" },
                  wp.element.createElement(
                    "svg",
                    {
                      xmlns: "http://www.w3.org/2000/svg",
                      height: "30px",
                      width: "30px",
                      viewBox: "-1 -1 31 31"
                    },
                    wp.element.createElement(
                      "g",
                      null,
                      wp.element.createElement("path", {
                        d: "M29.449,14.662 C29.449,22.722 22.868,29.256 14.75,29.256 C6.632,29.256 0.051,22.722 0.051,14.662 C0.051,6.601 6.632,0.067 14.75,0.067 C22.868,0.067 29.449,6.601 29.449,14.662",
                        fill: "#fff",
                        stroke: "#fff",
                        "stroke-width": "1"
                      }),
                      wp.element.createElement("path", {
                        d: "M14.733,1.686 C7.516,1.686 1.665,7.495 1.665,14.662 C1.665,20.159 5.109,24.854 9.97,26.744 C9.856,25.718 9.753,24.143 10.016,23.022 C10.253,22.01 11.548,16.572 11.548,16.572 C11.548,16.572 11.157,15.795 11.157,14.646 C11.157,12.842 12.211,11.495 13.522,11.495 C14.637,11.495 15.175,12.326 15.175,13.323 C15.175,14.436 14.462,16.1 14.093,17.643 C13.785,18.935 14.745,19.988 16.028,19.988 C18.351,19.988 20.136,17.556 20.136,14.046 C20.136,10.939 17.888,8.767 14.678,8.767 C10.959,8.767 8.777,11.536 8.777,14.398 C8.777,15.513 9.21,16.709 9.749,17.359 C9.856,17.488 9.872,17.6 9.84,17.731 C9.741,18.141 9.52,19.023 9.477,19.203 C9.42,19.44 9.288,19.491 9.04,19.376 C7.408,18.622 6.387,16.252 6.387,14.349 C6.387,10.256 9.383,6.497 15.022,6.497 C19.555,6.497 23.078,9.705 23.078,13.991 C23.078,18.463 20.239,22.062 16.297,22.062 C14.973,22.062 13.728,21.379 13.302,20.572 C13.302,20.572 12.647,23.05 12.488,23.657 C12.193,24.784 11.396,26.196 10.863,27.058 C12.086,27.434 13.386,27.637 14.733,27.637 C21.95,27.637 27.801,21.828 27.801,14.662 C27.801,7.495 21.95,1.686 14.733,1.686",
                        fill: "#bd081c"
                      })
                    )
                  )
                ),
                wp.element.createElement(
                  "span",
                  { "class": "simplesocialtxt" },
                  "Pinterest"
                ),
                "';",
                showCounter && wp.element.createElement(
                  "span",
                  { "class": "ssb_counter" },
                  "5"
                )
              ));
            } else {
              output.push(wp.element.createElement(
                "button",
                {
                  rel: "nofollow",
                  "class": "simplesocial-pinterest-share",
                  style: buttonStyle
                },
                wp.element.createElement(
                  "span",
                  { "class": "simplesocialtxt" },
                  "Pinterest"
                ),
                showCounter && wp.element.createElement(
                  "span",
                  { "class": "ssb_counter ssb_pinterest_counter" },
                  "5"
                )
              ));
            }
            break;
          case "totalshare":
            output.push(wp.element.createElement(
              "span",
              { "class": "ssb_total_counter" },
              "555",
              wp.element.createElement(
                "span",
                null,
                "Shares"
              )
            ));
            break;
          case "reddit":
            if ("simple-icons" === theme) {
              output.push(wp.element.createElement(
                "button",
                { "class": "ssb_reddit-icon" },
                wp.element.createElement(
                  "span",
                  { "class": "icon" },
                  wp.element.createElement(
                    "svg",
                    {
                      xmlns: "http://www.w3.org/2000/svg",
                      width: "430.117",
                      height: "430.117",
                      x: "0",
                      y: "0",
                      enableBackground: "new 0 0 430.117 430.117",
                      version: "1.1",
                      viewBox: "0 0 430.117 430.117",
                      xmlSpace: "preserve"
                    },
                    wp.element.createElement("path", { d: "M307.523 231.062c1.11 2.838 1.614 5.769 1.614 8.681 0 5.862-2.025 11.556-5.423 16.204-3.36 4.593-8.121 8.158-13.722 9.727h.01c-.047.019-.094.019-.117.037-.023 0-.061.019-.079.019a24.626 24.626 0 01-7.98 1.316c-6.254 0-12.396-2.254-17.306-6.096-4.872-3.826-8.56-9.324-9.717-15.845h-.01c0-.019 0-.042-.009-.069 0-.019 0-.038-.019-.065h.019a23.713 23.713 0 01-.551-5.021c0-5.647 1.923-11.07 5.097-15.551 3.164-4.453 7.626-7.99 12.848-9.811.019 0 .038-.01.038-.01.027 0 .027-.027.051-.027a26.476 26.476 0 019.157-1.639c5.619 0 11.154 1.704 15.821 4.821 4.611 3.066 8.354 7.561 10.23 13.143.019.037.019.07.037.103 0 .037.019.057.037.084h-.026zm-17.194 69.287c-2.202-1.428-4.751-2.291-7.448-2.291a11.66 11.66 0 00-6.445 1.955c-19.004 11.342-41.355 17.558-63.547 17.558-16.65 0-33.199-3.514-48.192-10.879l-.077-.037-.075-.028c-2.261-.924-4.837-2.889-7.647-4.76-1.428-.925-2.919-1.844-4.574-2.521-1.633-.695-3.447-1.181-5.386-1.181-1.605 0-3.292.359-4.957 1.115l-.252.098h.009a12.002 12.002 0 00-5.974 4.994c-1.372 2.23-2.046 4.826-2.046 7.411 0 2.334.551 4.667 1.691 6.786a12.163 12.163 0 004.938 4.938c21.429 14.454 46.662 21.002 71.992 20.979 22.838 0 45.814-5.287 66.27-14.911l.107-.065.103-.056c2.697-1.597 6.282-3.029 9.661-5.115 1.671-1.064 3.304-2.296 4.704-3.897a14.706 14.706 0 003.16-5.875v-.01c.266-1.026.392-2.025.392-3.024 0-1.899-.467-3.701-1.241-5.32-1.134-2.438-2.991-4.435-5.166-5.864zm-150.454-34.76c.037 0 .086.014.128.037a24.302 24.302 0 008.345 1.493c6.963 0 13.73-2.852 18.853-7.5 5.115-4.662 8.618-11.257 8.618-18.775 0-.196 0-.392-.009-.625.019-.336.028-.705.028-1.083 0-7.458-3.456-14.08-8.522-18.762-5.085-4.686-11.836-7.551-18.825-7.551-1.867 0-3.769.219-5.628.653-.028 0-.049.009-.077.009h-.028c-9.252 1.937-17.373 8.803-20.37 18.248v.01c0 .019-.009.037-.009.037a24.974 24.974 0 00-1.262 7.896c0 5.787 1.913 11.426 5.211 16.064 3.269 4.56 7.894 8.145 13.448 9.819.04.002.059.012.099.03zm290.158-67.495v.038c.066.94.084 1.878.084 2.81 0 10.447-3.351 20.493-8.941 29.016-5.218 7.976-12.414 14.649-20.703 19.177.532 4.158.84 8.349.84 12.526-.01 22.495-7.766 44.607-21.272 62.329v.009h-.028c-24.969 33.216-63.313 52.804-102.031 62.684h-.01l-.027.023a268.397 268.397 0 01-63.223 7.574c-31.729 0-63.433-5.722-93.018-17.585l-.009-.028h-.028c-30.672-12.643-59.897-32.739-77.819-62.184-9.642-15.71-14.935-34.141-14.935-52.659 0-4.19.283-8.387.843-12.536a60.094 60.094 0 01-20.255-18.687c-5.542-8.266-9.056-17.95-9.5-28.187v-.159c.009-14.337 6.237-27.918 15.915-37.932 9.677-10.011 22.896-16.554 37.075-16.554h.588a66.294 66.294 0 014.488-.159c7.122 0 14.26 1.153 21.039 3.752l.037.028.038.012c5.787 2.437 11.537 5.377 16.662 9.449 1.661-.871 3.472-1.851 5.504-2.625 31.064-18.395 67.171-25.491 102.358-27.538.306-17.431 2.448-35.68 10.949-51.65 7.08-13.269 19.369-23.599 34-27.179l.061-.03.079-.009c5.573-1.078 11.192-1.575 16.774-1.575 14.869 0 29.561 3.521 43.31 9.017 6.086-9.185 14.776-16.354 24.97-20.375l.098-.056.098-.037c5.983-1.864 12.303-2.954 18.646-2.954 6.692 0 13.437 1.223 19.756 4.046v-.023c.009.023.019.023.019.023.047.016.084.044.116.044 9.059 3.489 16.727 9.937 22.164 17.95 5.442 8.048 8.644 17.688 8.644 27.599 0 1.827-.103 3.657-.317 5.489l-.019.037c0 .028 0 .068-.01.096-1.063 12.809-7.551 24.047-16.736 32.063-9.24 8.048-21.207 12.909-33.49 12.909-1.97 0-3.958-.11-5.937-.374-12.182-.931-23.541-6.826-31.886-15.595-8.373-8.755-13.768-20.453-13.768-33.08 0-.611.056-1.237.074-1.843-11.435-5.092-23.578-9.316-35.646-9.306-1.746 0-3.491.096-5.237.273h-.019c-9.035.871-17.436 6.566-21.506 14.757v.037c-6.179 12.034-7.411 26.101-7.598 40.064 34.639 2.259 69.483 10.571 100.043 28.138h.047l.438.259c.579.343 1.652.931 2.623 1.449 2.101-1.704 4.322-3.456 6.856-4.966 9.264-6.17 20.241-9.238 31.223-9.238 4.872 0 9.749.621 14.481 1.834h.019l.196.058c.07.01.121.033.178.033v.009c11.183 2.845 21.3 9.267 28.917 17.927 7.612 8.674 12.731 19.648 13.73 31.561v.025h-.012zM328.002 84.733c0 .469.01.95.057 1.44v.084c.224 6.018 3.065 11.619 7.383 15.756 4.34 4.14 10.1 6.702 15.942 6.725h.159c.42.033.85.033 1.26.033 5.899.009 11.752-2.532 16.148-6.655 4.405-4.144 7.309-9.78 7.542-15.849l.009-.028v-.037c.038-.464.057-.903.057-1.377 0-6.247-2.922-12.202-7.496-16.612-4.555-4.406-10.688-7.136-16.735-7.12-1.951 0-3.884.266-5.778.854l-.065.005-.056.023c-4.984 1.295-9.656 4.368-13.012 8.449-3.371 4.062-5.415 9.084-5.415 14.309zm-255.69 92.845c-4.63-2.156-9.418-3.696-14.15-3.676-.794 0-1.597.047-2.39.133h-.11l-.11.014c-6.795.187-13.653 3.15-18.801 7.899-5.152 4.732-8.559 11.122-8.821 18.167v.065l-.012.058a21 21 0 00-.065 1.683c0 4.345 1.333 8.545 3.593 12.368 1.673 2.847 3.867 5.441 6.348 7.701 7.941-17.388 20.348-32.145 34.518-44.412zm301.754 85.057c0-15.5-5.592-31.069-14.646-43.604-18.053-25.119-46.055-41.502-75.187-50.636l-.205-.072a239.667 239.667 0 00-16.933-4.534c-17.025-3.876-34.48-5.806-51.917-5.806-23.414 0-46.827 3.465-69.245 10.379-29.125 9.243-57.221 25.51-75.233 50.71v.019c-9.129 12.587-14.475 28.208-14.475 43.763 0 5.727.716 11.453 2.23 17.025l.019.01c3.278 12.508 9.689 23.671 17.989 33.393 8.295 9.745 18.472 18.058 29.176 24.839 2.371 1.47 4.751 2.87 7.187 4.237 31.094 17.356 66.898 24.964 102.445 24.964 6.012 0 12.06-.214 18.033-.644 35.797-2.959 71.742-13.525 100.8-35.115l.01-.023c9.25-6.837 17.818-15.112 24.595-24.525 6.805-9.418 11.789-19.947 14.002-31.382v-.033l.009-.01a62.283 62.283 0 001.346-12.955zm28.254-61.685c-.009-3.762-.868-7.507-2.753-11l-.047-.044-.019-.056c-2.521-5.19-6.479-9.11-11.248-11.782-4.77-2.69-10.352-4.056-15.952-4.056-5.063 0-10.1 1.132-14.57 3.379 14.216 12.344 26.687 27.179 34.746 44.636a29.093 29.093 0 006.464-8.084c2.157-4.023 3.379-8.538 3.379-12.993z" })
                  )
                ),
                wp.element.createElement(
                  "span",
                  { "class": "simplesocialtxt" },
                  "reddit "
                ),
                showCounter && wp.element.createElement(
                  "span",
                  { "class": "ssb_counter" },
                  "5"
                )
              ));
            } else {
              output.push(wp.element.createElement(
                "button",
                { "class": "simplesocial-reddit-share", style: buttonStyle },
                wp.element.createElement(
                  "span",
                  { "class": "simplesocialtxt" },
                  "Reddit"
                ),
                showCounter && wp.element.createElement(
                  "span",
                  { "class": "ssb_counter ssb_reddit_counter" },
                  " 5 "
                )
              ));
            }
            break;
          case "whatsapp":
            if ("simple-icons" === theme) {
              output.push(wp.element.createElement(
                "button",
                { "class": "ssb_whatsapp-icon simplesocial-whatsapp-share" },
                wp.element.createElement(
                  "span",
                  { "class": "icon" },
                  wp.element.createElement(
                    "svg",
                    {
                      xmlns: "http://www.w3.org/2000/svg",
                      width: "512",
                      height: "512",
                      x: "0",
                      y: "0",
                      enableBackground: "new 0 0 90 90",
                      version: "1.1",
                      viewBox: "0 0 90 90",
                      xmlSpace: "preserve"
                    },
                    wp.element.createElement("path", { d: "M90 43.841c0 24.213-19.779 43.841-44.182 43.841a44.256 44.256 0 01-21.357-5.455L0 90l7.975-23.522a43.38 43.38 0 01-6.34-22.637C1.635 19.628 21.416 0 45.818 0 70.223 0 90 19.628 90 43.841zM45.818 6.982c-20.484 0-37.146 16.535-37.146 36.859 0 8.065 2.629 15.534 7.076 21.61L11.107 79.14l14.275-4.537A37.122 37.122 0 0045.819 80.7c20.481 0 37.146-16.533 37.146-36.857S66.301 6.982 45.818 6.982zm22.311 46.956c-.273-.447-.994-.717-2.076-1.254-1.084-.537-6.41-3.138-7.4-3.495-.993-.358-1.717-.538-2.438.537-.721 1.076-2.797 3.495-3.43 4.212-.632.719-1.263.809-2.347.271-1.082-.537-4.571-1.673-8.708-5.333-3.219-2.848-5.393-6.364-6.025-7.441-.631-1.075-.066-1.656.475-2.191.488-.482 1.084-1.255 1.625-1.882.543-.628.723-1.075 1.082-1.793.363-.717.182-1.344-.09-1.883-.27-.537-2.438-5.825-3.34-7.977-.902-2.15-1.803-1.792-2.436-1.792-.631 0-1.354-.09-2.076-.09s-1.896.269-2.889 1.344c-.992 1.076-3.789 3.676-3.789 8.963 0 5.288 3.879 10.397 4.422 11.113.541.716 7.49 11.92 18.5 16.223C58.2 65.771 58.2 64.336 60.186 64.156c1.984-.179 6.406-2.599 7.312-5.107.9-2.512.9-4.663.631-5.111z" })
                  )
                ),
                wp.element.createElement(
                  "span",
                  { "class": "simplesocialtxt" },
                  "Whatsapp"
                )
              ));
            } else {
              output.push(wp.element.createElement(
                "button",
                { "class": "simplesocial-whatsapp-share", style: buttonStyle },
                wp.element.createElement(
                  "span",
                  { "class": "simplesocialtxt" },
                  "WhatsApp"
                )
              ));
            }
            break;
          case "viber":
            if ("simple-icons" === theme) {
              output.push(wp.element.createElement(
                "button",
                { "class": "simplesocial-viber-share ssb_viber-icon" },
                wp.element.createElement(
                  "span",
                  { "class": "icon" },
                  wp.element.createElement(
                    "svg",
                    {
                      "aria-labelledby": "simpleicons-viber-icon",
                      role: "img",
                      viewBox: "0 0 24 24",
                      xmlns: "http://www.w3.org/2000/svg"
                    },
                    wp.element.createElement(
                      "title",
                      { id: "simpleicons-viber-icon" },
                      "Viber icon"
                    ),
                    wp.element.createElement("path", { d: "M20.812 2.343c-.596-.549-3.006-2.3-8.376-2.325 0 0-6.331-.38-9.415 2.451C1.302 4.189.698 6.698.634 9.82.569 12.934.487 18.774 6.12 20.36h.005l-.005 2.416s-.034.979.609 1.178c.779.24 1.236-.504 1.98-1.303.409-.439.972-1.088 1.397-1.582 3.851.322 6.813-.416 7.149-.525.777-.254 5.176-.816 5.893-6.658.738-6.021-.357-9.83-2.338-11.547v.004zm.652 11.112c-.615 4.876-4.184 5.187-4.83 5.396-.285.092-2.895.738-6.164.525 0 0-2.445 2.941-3.195 3.705-.121.121-.271.166-.361.145-.135-.029-.164-.18-.164-.404l.015-4.006c-.015 0 0 0 0 0-4.771-1.336-4.485-6.301-4.425-8.91.044-2.596.538-4.726 1.994-6.167 2.611-2.371 7.997-2.012 7.997-2.012 4.543.016 6.721 1.385 7.223 1.846 1.674 1.432 2.529 4.865 1.904 9.893l.006-.011zM7.741 4.983c.242 0 .459.109.629.311.004.002.58.695.83 1.034.235.32.551.83.711 1.115.285.51.104 1.032-.172 1.248l-.566.45c-.285.229-.25.653-.25.653s.84 3.157 3.959 3.953c0 0 .426.039.654-.246l.451-.569c.213-.285.734-.465 1.244-.181.285.15.795.466 1.116.704.339.24 1.032.826 1.036.826.33.271.404.689.18 1.109v.016c-.23.405-.541.78-.934 1.141h-.008c-.314.27-.629.42-.944.449-.03 0-.075.016-.136 0-.135 0-.27-.029-.404-.061v-.014c-.48-.135-1.275-.48-2.596-1.216-.855-.479-1.574-.96-2.189-1.455-.315-.255-.645-.54-.976-.87l-.076-.028-.03-.03-.029-.029c-.331-.33-.615-.66-.871-.98-.48-.609-.96-1.327-1.439-2.189-.735-1.32-1.08-2.115-1.215-2.596H5.7c-.045-.134-.075-.269-.06-.404-.015-.061 0-.105 0-.141.03-.299.189-.614.458-.944h.005c.355-.39.738-.704 1.146-.933.164-.091.329-.135.479-.135h.016l-.003.012zm4.095-.683h.116l.076.002h.02l.089.005h.511l.135.015h.074l.15.016h.03l.104.015h.016l.074.015c.046 0 .076.016.105.016h.091l.075.029.06.016.06.015.03.015h.045l.046.016h.029l.074.016.045.014.046.016.06.016.03.014c.03 0 .06.016.091.016l.044.015.046.016.119.044.061.031.135.06.045.015.045.016.09.045.061.015.029.015.076.031.029.014.061.031.045.014.045.03.059.03.046.029.03.016.061.03.044.03.075.045.045.016.074.044.016.015.045.031.09.074.046.03.044.03.031.014.045.031.074.074.061.045.045.03.016.015.029.016.074.061.046.044.03.03.045.029.045.031.029.015.12.12.06.061.135.135.031.029c.016.016.045.045.061.075l.029.03.166.194.045.06c.014.016.014.031.029.031l.09.135.045.045.09.12.076.12.045.09.059.105.045.09.016.029.029.061.076.15.074.149.031.075c.059.135.104.27.164.42.074.195.135.404.18.63.045.165.076.315.105.48l.029.27.045.3c.016.121.031.256.031.375.014.121.014.24.014.359v.256c0 .016-.006.029-.014.045-.016.03-.031.045-.061.075-.021.015-.049.046-.08.046-.029.014-.059.014-.09.014h-.045c-.029 0-.059-.014-.09-.029-.029-.016-.061-.03-.074-.061-.016-.029-.045-.061-.061-.09s-.031-.06-.031-.09v-.359c-.014-.209-.029-.425-.059-.639-.016-.146-.045-.284-.061-.42 0-.074-.016-.146-.029-.209l-.029-.15-.038-.141-.016-.09-.045-.15c-.029-.12-.074-.24-.119-.36-.029-.091-.061-.165-.105-.239l-.029-.076-.135-.27-.031-.045c-.061-.135-.135-.27-.225-.391l-.045-.074h-.201l-.064-.091c-.055-.089-.114-.165-.18-.239l-.125-.15-.015-.016-.046-.057-.035-.045-.075-.074-.015-.03-.07-.06-.045-.046-.083-.075-.04-.037-.046-.045-.015-.016c-.016-.015-.045-.045-.075-.06l-.076-.062-.03-.015-.061-.046-.074-.06-.045-.036-.03-.016-.06-.053c0-.016-.016-.016-.031-.016l-.029-.029-.015-.016v-.013l-.03-.014-.061-.037-.044-.031-.075-.045-.06-.045-.029-.016-.032-.013h-.09l-.019-.016-.065-.035-.009-.014-.03-.016-.045-.021h-.012l-.045-.016-.025-.015-.045-.015-.01-.011-.03-.016-.053-.029-.03-.015-.09-.03-.074-.029-.137-.016-.044-.029c-.015-.01-.03-.016-.046-.016l-.029-.015c-.029-.011-.045-.016-.075-.03l-.03-.016h-.029l-.061-.029-.029-.016-.045-.015h-.092c-.008 0-.019-.005-.03-.007h-.09l-.045-.016h-.015l-.045-.016h-.041c-.025-.014-.045-.014-.07-.014l-.01-.016-.06-.015c-.03-.016-.056-.016-.084-.016l-.045-.015-.05-.016-.045-.014-.061-.016h-.061l-.179-.022h-.09l-.116-.015h-.076l-.068-.008h-.03l-.054-.016h-.285l-.01-.015h-.061c-.03 0-.064-.015-.09-.03-.03-.016-.061-.029-.081-.06l-.03-.046c-.029-.029-.029-.06-.045-.09-.014-.028-.014-.059-.014-.089s0-.06.015-.09c.016-.029.029-.06.061-.075.015-.03.044-.044.074-.06.029-.016.061-.03.09-.03h.061l.015.066zm.554 1.574l.037.003.061.006c.008 0 .018 0 .029.003.022 0 .045.004.075.006l.06.008.024.016.045.015.048.015.045.016h.03l.042.015.07.015.056.016.026.014h.073l.119.028.046.015.045.015.045.016s.015 0 .015.015l.046.015.044.016.045.016c.015 0 .03.014.046.014.007 0 .014.016.025.016l.064.03h.029l.09.03.05.029.046.03.108.045.06.015.031.031c.045.014.09.044.135.059l.048.03.048.03.049.029c.045.03.082.046.121.076l.029.014.041.031.022.015.075.045.037.03.065.043.029.015.03.015.046.03.06.046c.015.014.022.014.034.029.01.015.016.015.025.03l.033.03.036.029.03.03.046.046.029.03.016.016.09.089.016.016c0 .015.015.03.029.03l.016.013.045.046.029.045.03.03.045.06.046.046.09.119.014.029.061.076.016.029.015.031.015.029.016.03c.016.015.016.03.029.06l.043.076.016.015.029.061.031.044c.014.015.014.029.029.045l.03.045.03.061.029.059.016.046c.015.044.045.075.06.12 0 .015.015.029.015.045l.045.119.061.195c0 .016.015.045.015.061l.046.135.044.18.046.24c.014.074.014.135.029.211.016.119.03.238.03.359l.015.21v.165c0 .016 0 .029-.015.045l-.044.043c-.029.023-.045.045-.074.061-.03.015-.061.029-.09.04-.031.016-.075.016-.105.016-.029 0-.061-.016-.09-.03-.016 0-.03-.016-.045-.021-.031-.014-.061-.039-.075-.065-.03-.03-.046-.06-.046-.091l-.014-.044v-.313c0-.133-.016-.256-.031-.385-.015-.135-.044-.285-.074-.42-.029-.09-.045-.18-.075-.26l-.03-.091-.029-.075-.016-.03-.045-.12-.045-.09-.075-.149-.069-.12v-.019l-.029-.047-.03-.038-.045-.075-.046-.061-.089-.119c-.046-.061-.09-.12-.142-.178-.014-.015-.029-.029-.029-.045l-.03-.029-.017-.016-.03-.014-.03-.027v-.146l-.119-.113-.075-.068v-.014l-.03-.031-.038-.029-.015-.016c0-.015-.016-.015-.029-.015l-.046-.016-.015-.015-.061-.045-.014-.016-.016-.015c-.012-.015-.023-.015-.03-.015l-.06-.045-.016-.016-.06-.029-.011-.016-.045-.029-.03-.016-.03-.029-.029-.031h-.016c-.029-.029-.06-.044-.105-.06l-.044-.03-.03-.014-.016-.016-.045-.03-.044-.015-.06-.03-.046-.015-.015-.016-.056-.014v-.012l-.091-.03-.06-.03-.03-.015h-.06c-.03-.015-.045-.015-.075-.03H13.2l-.045-.016h-.044l-.046-.014-.029-.016h-.061l-.061-.015-.029-.016h-.165l-.069-.015H12.3l-.046-.016c-.029-.014-.06-.029-.09-.06-.014-.03-.045-.06-.06-.089-.015-.031-.03-.061-.03-.091v-.09c.006-.046.016-.075.03-.105.008-.015.015-.03.03-.045.018-.03.045-.06.075-.075.015-.015.03-.015.044-.029.031-.016.061-.016.091-.016h.06l-.014.055zm.454 1.629c.015 0 .03 0 .044.004.016 0 .031 0 .046.002l.052.005c.104.009.213.024.318.046l.104.023.026.008.114.029.059.02.046.016c.045.014.091.045.135.06l.016.015.06.03.09.046.029.014c.016.016.031.016.046.03.015.016.045.03.06.045.061.03.105.075.15.105l.105.09.09.091.061.074.029.029.03.031.044.06.091.135.075.135.06.12.046.105c.044.104.06.195.09.299.029.091.045.196.06.285l.015.15.016.136V9.8c0 .045-.016.075-.03.105-.015.029-.046.074-.075.09-.03.029-.061.045-.105.061-.029.014-.06.014-.09.014-.029 0-.06 0-.09-.014l-.104-.046c-.03-.03-.06-.045-.091-.091-.015-.029-.029-.06-.045-.104v-.166l-.015-.105-.015-.119-.016-.105-.016-.06c0-.015-.014-.045-.014-.06-.03-.121-.09-.24-.15-.36l-.061-.06-.047-.06-.045-.045-.015-.03-.075-.06-.061-.061-.059-.045c-.016-.015-.03-.015-.061-.029l-.09-.061-.061-.03-.029-.015h-.016l-.076-.031-.09-.03-.09-.015h-.075l-.044-.015-.035-.007h-.045l-.06-.016h-.255l-.015-.075h-.039c-.03-.004-.055-.015-.08-.029-.035-.021-.064-.045-.09-.08-.018-.029-.034-.061-.045-.09-.008-.029-.012-.06-.012-.09 0-.037 0-.075.015-.113.015-.039.03-.07.06-.1l.061-.045c.029-.016.061-.03.09-.03l.062-.075h.032z" })
                  )
                ),
                wp.element.createElement(
                  "span",
                  { "class": "simplesocialtxt" },
                  "Viber"
                )
              ));
            } else {
              output.push(wp.element.createElement(
                "button",
                { "class": "simplesocial-viber-share" },
                wp.element.createElement(
                  "span",
                  { "class": "simplesocialtxt" },
                  "Viber"
                )
              ));
            }
            break;
          case "fblike":
            var likeButtonClasses = __WEBPACK_IMPORTED_MODULE_6_classnames___default()("fb-like", "ssb-fb-like", "fb_iframe_widget", __WEBPACK_IMPORTED_MODULE_0_babel_runtime_helpers_defineProperty___default()({}, "" + likeButtonSize, likeButtonSize));
            output.push(wp.element.createElement(
              "div",
              {
                "class": likeButtonClasses,
                "data-layout": "button_count",
                "data-action": "like",
                "data-size": "small",
                "data-show-faces": "false",
                "data-share": "false"
              },
              wp.element.createElement("img", { src: SSB.plugin_url + "/assets/images/fblike.svg" }),
              " "
            ));
            break;
          case "messenger":
            if ("simple-icons" === theme) {
              output.push(wp.element.createElement(
                "button",
                { "class": "simplesocial-viber-share ssb_msng-icon" },
                wp.element.createElement(
                  "span",
                  { "class": "icon" },
                  wp.element.createElement(
                    "svg",
                    {
                      xmlns: "http://www.w3.org/2000/svg",
                      width: "18",
                      height: "19",
                      x: "0",
                      y: "0",
                      version: "1.1",
                      viewBox: "-889.5 1161 18 19",
                      xmlSpace: "preserve"
                    },
                    wp.element.createElement("path", {
                      fill: "#FFF",
                      d: "M-880.5 1161c-5 0-9 3.8-9 8.5 0 2.4 1 4.5 2.7 6v4.5l3.8-2.3c.8.2 1.6.3 2.5.3 5 0 9-3.8 9-8.5s-4-8.5-9-8.5zm.9 11.2l-2.4-2.4-4.3 2.4 4.7-5.2 2.4 2.4 4.2-2.4-4.6 5.2z",
                      opacity: "0.99"
                    })
                  )
                ),
                wp.element.createElement(
                  "span",
                  { "class": "simplesocialtxt" },
                  "Messenger"
                )
              ));
            } else {
              output.push(wp.element.createElement(
                "button",
                { "class": "simplesocial-msng-share" },
                wp.element.createElement(
                  "span",
                  { "class": "simplesocialtxt" },
                  "Messenger"
                )
              ));
            }
            break;
          case "email":
            if ("simple-icons" === theme) {
              output.push(wp.element.createElement(
                "button",
                { "class": "ssb_email-icon simplesocial-email-share" },
                wp.element.createElement(
                  "span",
                  { "class": "icon" },
                  wp.element.createElement(
                    "svg",
                    {
                      xmlns: "http://www.w3.org/2000/svg",
                      width: "16",
                      height: "11.9",
                      x: "0",
                      y: "0",
                      version: "1.1",
                      viewBox: "-1214.1 1563.9 16 11.9",
                      xmlSpace: "preserve"
                    },
                    wp.element.createElement("path", { d: "M-1214.1 1565.2v1l8 4 8-4v-1c0-.7-.6-1.3-1.3-1.3h-13.4c-.7 0-1.3.5-1.3 1.3zm0 2.2v7.1c0 .7.6 1.3 1.3 1.3h13.4c.7 0 1.3-.6 1.3-1.3v-7.1l-8 4-8-4z" })
                  )
                ),
                wp.element.createElement(
                  "span",
                  { "class": "simplesocialtxt" },
                  "Email"
                )
              ));
            } else {
              output.push(wp.element.createElement(
                "button",
                { "class": "simplesocial-email-share" },
                wp.element.createElement(
                  "span",
                  { "class": "simplesocialtxt" },
                  "Email"
                )
              ));
            }
            break;
          case "print":
            if ("simple-icons" === theme) {
              output.push(wp.element.createElement(
                "button",
                { "class": " ssb_print-icon simplesocial-email-share" },
                wp.element.createElement(
                  "span",
                  { "class": "icon" },
                  wp.element.createElement(
                    "svg",
                    {
                      xmlns: "http://www.w3.org/2000/svg",
                      width: "16",
                      height: "13.7",
                      x: "0",
                      y: "0",
                      version: "1.1",
                      viewBox: "-1296.9 1876.4 16 13.7",
                      xmlSpace: "preserve"
                    },
                    wp.element.createElement(
                      "g",
                      { fill: "#FFF" },
                      wp.element.createElement("path", { d: "M-1288.9 1879.7h6.9c.4 0 .7.1.9.5.1.2.1.4.1.6v5.1c0 .7-.4 1.1-1.1 1h-1.8c-.1 0-.2 0-.2.2v2c0 .6-.4 1-1 1H-1292.9c-.3 0-.5 0-.8-.1-.3-.2-.5-.5-.5-.9v-2c0-.2-.1-.2-.2-.2h-1.7c-.7 0-1-.4-1-1v-5.1c0-.4.2-.8.6-.9.2-.1.3-.1.5-.1 2.5-.1 4.8-.1 7.1-.1zm0 5.2h-4.2c-.1 0-.2 0-.2.2v4c0 .3.1.4.4.4h8c.2 0 .3-.2.3-.3v-4c0-.2 0-.2-.2-.2-1.3-.1-2.7-.1-4.1-.1zm4.7-2.5c.4 0 .7-.3.7-.7 0-.4-.3-.7-.8-.7-.4 0-.7.3-.7.7.1.4.4.7.8.7zM-1283.9 1879h-9.8c-.1 0-.2 0-.2-.2v-1.5c0-.5.4-1 .9-1h8.1c.6 0 1 .4 1 1v1.7z" }),
                      wp.element.createElement("path", { d: "M-1291.9 1886.9v-.6h6v.6h-6zM-1289.6 1888.2h-2.1c-.1 0-.2 0-.2-.2v-.3c0-.1 0-.2.2-.2h4.2c.3 0 .3 0 .3.3 0 .4 0 .4-.4.4h-2z" })
                    )
                  )
                ),
                wp.element.createElement(
                  "span",
                  { "class": "simplesocialtxt" },
                  "Print"
                )
              ));
            } else {
              output.push(wp.element.createElement(
                "button",
                { "class": "simplesocial-print-share" },
                wp.element.createElement(
                  "span",
                  { "class": "simplesocialtxt" },
                  "Print"
                )
              ));
            }
            break;
          case "tumblr":
            if ("simple-icons" === theme) {
              output.push(wp.element.createElement(
                "button",
                { "class": "ssb_tumblr-icon" },
                wp.element.createElement(
                  "span",
                  { "class": "icon" },
                  wp.element.createElement(
                    "svg",
                    {
                      xmlns: "http://www.w3.org/2000/svg",
                      width: "12.6",
                      height: "17.8",
                      x: "0",
                      y: "0",
                      enableBackground: "new -299.1 388.3 12.6 17.8",
                      version: "1.1",
                      viewBox: "-299.1 388.3 12.6 17.8",
                      xmlSpace: "preserve"
                    },
                    wp.element.createElement("path", {
                      fill: "#FFF",
                      d: "M-294.7 388.3h3.2v4.4h5v3.4h-5v5c0 1.2.6 1.8 1.8 2 1.1.1 2.1 0 3-.5.1 0 .1-.1.2-.1v2.5c0 .1 0 .2-.2.3-1.6.6-3.2.9-5 .8-1-.1-2-.3-2.9-.8-1.2-.7-1.8-1.7-1.8-3.1V396v-.3h-2.7v-.2-2.2c0-.1 0-.2.2-.2.3-.1.7-.2 1-.3 1.6-.6 2.6-1.8 3-3.5 0-.1.1-.3.1-.4 0-.3.1-.5.1-.6z"
                    })
                  )
                ),
                wp.element.createElement(
                  "span",
                  { "class": "simplesocialtxt" },
                  "tumblr"
                ),
                showCounter && wp.element.createElement(
                  "span",
                  { "class": "ssb_counter" },
                  "5"
                )
              ));
            } else {
              output.push(wp.element.createElement(
                "button",
                { "class": "simplesocial-tumblr-share", style: buttonStyle },
                wp.element.createElement(
                  "span",
                  { "class": "simplesocialtxt" },
                  "Tumblr"
                ),
                showCounter && wp.element.createElement(
                  "span",
                  { "class": "ssb_counter ssb_tumblr_counter" },
                  " 5"
                )
              ));
            }
        }
      });

      return wp.element.createElement(
        Fragment,
        null,
        output
      );
    }
  }]);

  return Buttons;
}(Component);

/* harmony default export */ __webpack_exports__["a"] = (Buttons);

/***/ }),
/* 93 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_babel_runtime_helpers_defineProperty__ = __webpack_require__(15);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_babel_runtime_helpers_defineProperty___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0_babel_runtime_helpers_defineProperty__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_classnames__ = __webpack_require__(17);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_classnames___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_1_classnames__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__icon__ = __webpack_require__(94);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3__settings__ = __webpack_require__(95);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_4__editor_scss__ = __webpack_require__(96);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_4__editor_scss___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_4__editor_scss__);

/**
 * Block dependencies.
 */




/**
 * Block libraries
 */

var __ = wp.i18n.__;
var Fragment = wp.element.Fragment;
var _wp$editor = wp.editor,
    InspectorControls = _wp$editor.InspectorControls,
    BlockControls = _wp$editor.BlockControls,
    BlockAlignmentToolbar = _wp$editor.BlockAlignmentToolbar,
    RichText = _wp$editor.RichText;
var registerBlockType = wp.blocks.registerBlockType;


/* harmony default export */ __webpack_exports__["default"] = (registerBlockType("ssb/click-to-tweet", {
	title: __("Click to Tweet"),
	description: __("SSB Click to tweet easy way to tweet your content."),
	category: "common",
	icon: __WEBPACK_IMPORTED_MODULE_2__icon__["a" /* default */],
	keywords: [__("twiter"), __("clicktotweet"), __("ssb")],
	attributes: {
		theme: {
			type: "string",
			default: "twitter-round"
		},
		tweet: {
			type: "string",
			default: ""
		},
		front: {
			type: "string",
			default: ""
		},
		IncludePageLink: {
			type: "boolean",
			default: true
		},
		IncludeVia: {
			type: "boolean",
			default: true
		},
		showTweetButton: {
			type: "boolean",
			default: true
		},
		align: {
			type: "string",
			default: ""
		}
	},
	supports: {
		// Turn off ability to edit HTML of block content.
		html: false,
		// Turn off reusable block feature.
		reusable: false

	},
	getEditWrapperProps: function getEditWrapperProps(_ref) {
		var align = _ref.align;

		if ("center" === align || "wide" === align || "full" === align) {
			return { "data-align": align };
		}
	},

	edit: function edit(props) {
		var _props$attributes = props.attributes,
		    front = _props$attributes.front,
		    theme = _props$attributes.theme,
		    align = _props$attributes.align,
		    showTweetButton = _props$attributes.showTweetButton,
		    className = props.className,
		    setAttributes = props.setAttributes;

		var mainClasses = __WEBPACK_IMPORTED_MODULE_1_classnames___default()(className, "ssb-ctt-wrapper", __WEBPACK_IMPORTED_MODULE_0_babel_runtime_helpers_defineProperty___default()({}, "" + theme, theme), __WEBPACK_IMPORTED_MODULE_0_babel_runtime_helpers_defineProperty___default()({}, "align" + align, align), __WEBPACK_IMPORTED_MODULE_0_babel_runtime_helpers_defineProperty___default()({}, "hide-button", !showTweetButton));
		return wp.element.createElement(
			Fragment,
			null,
			wp.element.createElement(
				BlockControls,
				null,
				wp.element.createElement(BlockAlignmentToolbar, {
					value: align,
					onChange: function onChange(align) {
						return setAttributes({ align: align });
					},
					controls: ["center", "wide", "full"]
				})
			),
			wp.element.createElement(
				InspectorControls,
				null,
				wp.element.createElement(__WEBPACK_IMPORTED_MODULE_3__settings__["a" /* default */], props)
			),
			wp.element.createElement(
				"div",
				{ "class": mainClasses, "data-theme": "twitter-round" },
				wp.element.createElement(
					"div",
					{ "class": "ssb-ctt" },
					wp.element.createElement(RichText, {
						tabName: 'div',
						className: "ssb-ctt-text",
						value: front,
						onChange: function onChange(front) {
							return setAttributes({ front: front });
						},
						placeholder: __('Enter your content ...'),
						keepPlaceholderOnFocus: true
					}),
					showTweetButton && wp.element.createElement(
						"span",
						{ "class": "ssb-ctt-btn" },
						"Click to tweet",
						__WEBPACK_IMPORTED_MODULE_2__icon__["a" /* default */]
					)
				)
			)
		);
	},
	save: function save(props) {
		return null;
	}
}));

/***/ }),
/* 94 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
var twitterIcon = wp.element.createElement(
  "svg",
  {
    id: "twitter_icon_ctt",
    width: "17.1",
    height: "14",
    x: "0",
    y: "0",
    version: "1.1",
    viewBox: "0 0 17.1 14",
    xmlSpace: "preserve",
  },
  wp.element.createElement("path", { d: "M4.9 0H0L5.782 7.7098L0.315 14H2.17L6.6416 8.8557L10.5 14H15.4L9.3744 5.9654L14.56 0H12.705L8.5148 4.8202L4.9 0ZM11.2 12.6L2.8 1.4H4.2L12.6 12.6H11.2Z" })
);

/* harmony default export */ __webpack_exports__["a"] = (twitterIcon);

/***/ }),
/* 95 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_babel_runtime_core_js_object_get_prototype_of__ = __webpack_require__(18);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_babel_runtime_core_js_object_get_prototype_of___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0_babel_runtime_core_js_object_get_prototype_of__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_babel_runtime_helpers_classCallCheck__ = __webpack_require__(23);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_babel_runtime_helpers_classCallCheck___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_1_babel_runtime_helpers_classCallCheck__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2_babel_runtime_helpers_createClass__ = __webpack_require__(24);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2_babel_runtime_helpers_createClass___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_2_babel_runtime_helpers_createClass__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3_babel_runtime_helpers_possibleConstructorReturn__ = __webpack_require__(25);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3_babel_runtime_helpers_possibleConstructorReturn___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_3_babel_runtime_helpers_possibleConstructorReturn__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_4_babel_runtime_helpers_inherits__ = __webpack_require__(35);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_4_babel_runtime_helpers_inherits___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_4_babel_runtime_helpers_inherits__);





var __ = wp.i18n.__;
var _wp$element = wp.element,
    Component = _wp$element.Component,
    Fragment = _wp$element.Fragment;
var _wp$components = wp.components,
    PanelBody = _wp$components.PanelBody,
    PanelRow = _wp$components.PanelRow,
    SelectControl = _wp$components.SelectControl,
    TextareaControl = _wp$components.TextareaControl,
    ToggleControl = _wp$components.ToggleControl,
    TextControl = _wp$components.TextControl;
var AlignmentToolbar = wp.editor.AlignmentToolbar;

var Settings = function (_Component) {
  __WEBPACK_IMPORTED_MODULE_4_babel_runtime_helpers_inherits___default()(Settings, _Component);

  function Settings() {
    __WEBPACK_IMPORTED_MODULE_1_babel_runtime_helpers_classCallCheck___default()(this, Settings);

    return __WEBPACK_IMPORTED_MODULE_3_babel_runtime_helpers_possibleConstructorReturn___default()(this, (Settings.__proto__ || __WEBPACK_IMPORTED_MODULE_0_babel_runtime_core_js_object_get_prototype_of___default()(Settings)).apply(this, arguments));
  }

  __WEBPACK_IMPORTED_MODULE_2_babel_runtime_helpers_createClass___default()(Settings, [{
    key: "render",
    value: function render() {
      var _props = this.props,
          _props$attributes = _props.attributes,
          theme = _props$attributes.theme,
          front = _props$attributes.front,
          tweet = _props$attributes.tweet,
          IncludePageLink = _props$attributes.IncludePageLink,
          IncludeVia = _props$attributes.IncludeVia,
          showTweetButton = _props$attributes.showTweetButton,
          setAttributes = _props.setAttributes;

      return wp.element.createElement(
        Fragment,
        null,
        wp.element.createElement(
          PanelBody,
          {
            title: __("Theme"),
            initialOpen: true,
            className: "ssb_panel_wrapper"
          },
          wp.element.createElement(
            PanelRow,
            null,
            wp.element.createElement(SelectControl, {
              value: theme,
              options: [{ label: "Round", value: "twitter-round" }, { label: "Dark", value: "twitter-dark" }, { label: "Simple", value: "simple-twitter" }, { label: "Side line", value: "twitter-side-line" }, { label: "Own Style", value: "" }],
              onChange: function onChange(theme) {
                return setAttributes({ theme: theme });
              },
              help: "select the style for click to tweet "
            })
          )
        ),
        wp.element.createElement(
          PanelBody,
          {
            title: __("Content"),
            initialOpen: true,
            className: "ssb_panel_wrapper"
          },
          wp.element.createElement(
            PanelRow,
            null,
            wp.element.createElement(TextareaControl, {
              label: __("Quote Content"),
              value: front,
              help: __("This text will be shown to the user."),
              onChange: function onChange(front) {
                return setAttributes({ front: front });
              }
            }),
            wp.element.createElement(TextareaControl, {
              label: __("Tweet Content"),
              value: tweet,
              help: __("This text will be tweet . if empty Quote content will be used."),
              onChange: function onChange(tweet) {
                return setAttributes({ tweet: tweet });
              }
            })
          )
        ),
        wp.element.createElement(
          PanelBody,
          {
            title: __(" Configuration"),
            initialOpen: false,
            className: "ssb_panel_wrapper"
          },
          wp.element.createElement(
            PanelRow,
            null,
            wp.element.createElement(ToggleControl, {
              label: __('Show Tweet Button'),
              checked: showTweetButton,
              onChange: function onChange(showbtn) {
                setAttributes({ showTweetButton: !!showbtn });
              }
            }),
            wp.element.createElement(ToggleControl, {
              label: __('Include Page/post Link'),
              checked: IncludePageLink,
              onChange: function onChange(pageLink) {
                setAttributes({ IncludePageLink: !!pageLink });
              },
              help: __('\n Link to this post will be appended to the Tweet. we have a filter if you want to modify it `ssb_ctt_url`.')
            }),
            wp.element.createElement(ToggleControl, {
              label: __('Include Via'),
              checked: IncludeVia,
              onChange: function onChange(via) {
                setAttributes({ IncludeVia: !!via });
              },
              help: __('\n Twitter username from SSB Settings Advanced tab will be appended to the end of the Tweet with the text “via @username”.')
            })
          )
        )
      );
    }
  }]);

  return Settings;
}(Component);

/* harmony default export */ __webpack_exports__["a"] = (Settings);

/***/ }),
/* 96 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ })
/******/ ]);
//# sourceMappingURL=blocks.editor.js.map