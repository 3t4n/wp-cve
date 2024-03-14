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
/******/ 	return __webpack_require__(__webpack_require__.s = 89);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/***/ (function(module, exports) {

module.exports = React;

/***/ }),
/* 1 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var bind = __webpack_require__(23);
var isBuffer = __webpack_require__(68);

/*global toString:true*/

// utils is a library of generic helper functions non-specific to axios

var toString = Object.prototype.toString;

/**
 * Determine if a value is an Array
 *
 * @param {Object} val The value to test
 * @returns {boolean} True if value is an Array, otherwise false
 */
function isArray(val) {
  return toString.call(val) === '[object Array]';
}

/**
 * Determine if a value is an ArrayBuffer
 *
 * @param {Object} val The value to test
 * @returns {boolean} True if value is an ArrayBuffer, otherwise false
 */
function isArrayBuffer(val) {
  return toString.call(val) === '[object ArrayBuffer]';
}

/**
 * Determine if a value is a FormData
 *
 * @param {Object} val The value to test
 * @returns {boolean} True if value is an FormData, otherwise false
 */
function isFormData(val) {
  return (typeof FormData !== 'undefined') && (val instanceof FormData);
}

/**
 * Determine if a value is a view on an ArrayBuffer
 *
 * @param {Object} val The value to test
 * @returns {boolean} True if value is a view on an ArrayBuffer, otherwise false
 */
function isArrayBufferView(val) {
  var result;
  if ((typeof ArrayBuffer !== 'undefined') && (ArrayBuffer.isView)) {
    result = ArrayBuffer.isView(val);
  } else {
    result = (val) && (val.buffer) && (val.buffer instanceof ArrayBuffer);
  }
  return result;
}

/**
 * Determine if a value is a String
 *
 * @param {Object} val The value to test
 * @returns {boolean} True if value is a String, otherwise false
 */
function isString(val) {
  return typeof val === 'string';
}

/**
 * Determine if a value is a Number
 *
 * @param {Object} val The value to test
 * @returns {boolean} True if value is a Number, otherwise false
 */
function isNumber(val) {
  return typeof val === 'number';
}

/**
 * Determine if a value is undefined
 *
 * @param {Object} val The value to test
 * @returns {boolean} True if the value is undefined, otherwise false
 */
function isUndefined(val) {
  return typeof val === 'undefined';
}

/**
 * Determine if a value is an Object
 *
 * @param {Object} val The value to test
 * @returns {boolean} True if value is an Object, otherwise false
 */
function isObject(val) {
  return val !== null && typeof val === 'object';
}

/**
 * Determine if a value is a Date
 *
 * @param {Object} val The value to test
 * @returns {boolean} True if value is a Date, otherwise false
 */
function isDate(val) {
  return toString.call(val) === '[object Date]';
}

/**
 * Determine if a value is a File
 *
 * @param {Object} val The value to test
 * @returns {boolean} True if value is a File, otherwise false
 */
function isFile(val) {
  return toString.call(val) === '[object File]';
}

/**
 * Determine if a value is a Blob
 *
 * @param {Object} val The value to test
 * @returns {boolean} True if value is a Blob, otherwise false
 */
function isBlob(val) {
  return toString.call(val) === '[object Blob]';
}

/**
 * Determine if a value is a Function
 *
 * @param {Object} val The value to test
 * @returns {boolean} True if value is a Function, otherwise false
 */
function isFunction(val) {
  return toString.call(val) === '[object Function]';
}

/**
 * Determine if a value is a Stream
 *
 * @param {Object} val The value to test
 * @returns {boolean} True if value is a Stream, otherwise false
 */
function isStream(val) {
  return isObject(val) && isFunction(val.pipe);
}

/**
 * Determine if a value is a URLSearchParams object
 *
 * @param {Object} val The value to test
 * @returns {boolean} True if value is a URLSearchParams object, otherwise false
 */
function isURLSearchParams(val) {
  return typeof URLSearchParams !== 'undefined' && val instanceof URLSearchParams;
}

/**
 * Trim excess whitespace off the beginning and end of a string
 *
 * @param {String} str The String to trim
 * @returns {String} The String freed of excess whitespace
 */
function trim(str) {
  return str.replace(/^\s*/, '').replace(/\s*$/, '');
}

/**
 * Determine if we're running in a standard browser environment
 *
 * This allows axios to run in a web worker, and react-native.
 * Both environments support XMLHttpRequest, but not fully standard globals.
 *
 * web workers:
 *  typeof window -> undefined
 *  typeof document -> undefined
 *
 * react-native:
 *  navigator.product -> 'ReactNative'
 */
function isStandardBrowserEnv() {
  if (typeof navigator !== 'undefined' && navigator.product === 'ReactNative') {
    return false;
  }
  return (
    typeof window !== 'undefined' &&
    typeof document !== 'undefined'
  );
}

/**
 * Iterate over an Array or an Object invoking a function for each item.
 *
 * If `obj` is an Array callback will be called passing
 * the value, index, and complete array for each item.
 *
 * If 'obj' is an Object callback will be called passing
 * the value, key, and complete object for each property.
 *
 * @param {Object|Array} obj The object to iterate
 * @param {Function} fn The callback to invoke for each item
 */
function forEach(obj, fn) {
  // Don't bother if no value provided
  if (obj === null || typeof obj === 'undefined') {
    return;
  }

  // Force an array if not already something iterable
  if (typeof obj !== 'object' && !isArray(obj)) {
    /*eslint no-param-reassign:0*/
    obj = [obj];
  }

  if (isArray(obj)) {
    // Iterate over array values
    for (var i = 0, l = obj.length; i < l; i++) {
      fn.call(null, obj[i], i, obj);
    }
  } else {
    // Iterate over object keys
    for (var key in obj) {
      if (Object.prototype.hasOwnProperty.call(obj, key)) {
        fn.call(null, obj[key], key, obj);
      }
    }
  }
}

/**
 * Accepts varargs expecting each argument to be an object, then
 * immutably merges the properties of each object and returns result.
 *
 * When multiple objects contain the same key the later object in
 * the arguments list will take precedence.
 *
 * Example:
 *
 * ```js
 * var result = merge({foo: 123}, {foo: 456});
 * console.log(result.foo); // outputs 456
 * ```
 *
 * @param {Object} obj1 Object to merge
 * @returns {Object} Result of all merge properties
 */
function merge(/* obj1, obj2, obj3, ... */) {
  var result = {};
  function assignValue(val, key) {
    if (typeof result[key] === 'object' && typeof val === 'object') {
      result[key] = merge(result[key], val);
    } else {
      result[key] = val;
    }
  }

  for (var i = 0, l = arguments.length; i < l; i++) {
    forEach(arguments[i], assignValue);
  }
  return result;
}

/**
 * Extends object a by mutably adding to it the properties of object b.
 *
 * @param {Object} a The object to be extended
 * @param {Object} b The object to copy properties from
 * @param {Object} thisArg The object to bind function to
 * @return {Object} The resulting value of object a
 */
function extend(a, b, thisArg) {
  forEach(b, function assignValue(val, key) {
    if (thisArg && typeof val === 'function') {
      a[key] = bind(val, thisArg);
    } else {
      a[key] = val;
    }
  });
  return a;
}

module.exports = {
  isArray: isArray,
  isArrayBuffer: isArrayBuffer,
  isBuffer: isBuffer,
  isFormData: isFormData,
  isArrayBufferView: isArrayBufferView,
  isString: isString,
  isNumber: isNumber,
  isObject: isObject,
  isUndefined: isUndefined,
  isDate: isDate,
  isFile: isFile,
  isBlob: isBlob,
  isFunction: isFunction,
  isStream: isStream,
  isURLSearchParams: isURLSearchParams,
  isStandardBrowserEnv: isStandardBrowserEnv,
  forEach: forEach,
  merge: merge,
  extend: extend,
  trim: trim
};


/***/ }),
/* 2 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__components_Provider__ = __webpack_require__(49);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__components_connectAdvanced__ = __webpack_require__(20);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__connect_connect__ = __webpack_require__(55);
/* harmony reexport (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return __WEBPACK_IMPORTED_MODULE_0__components_Provider__["a"]; });
/* unused harmony reexport createProvider */
/* unused harmony reexport connectAdvanced */
/* harmony reexport (binding) */ __webpack_require__.d(__webpack_exports__, "b", function() { return __WEBPACK_IMPORTED_MODULE_2__connect_connect__["a"]; });






/***/ }),
/* 3 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_react__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_react___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0_react__);


// dumb component ... only props and presentation
var RangeField = function RangeField(_ref) {
    var val = _ref.val,
        onch = _ref.onch,
        name = _ref.name,
        label = _ref.label,
        _ref$aclass = _ref.aclass,
        aclass = _ref$aclass === undefined ? '' : _ref$aclass,
        min = _ref.min,
        max = _ref.max,
        _ref$step = _ref.step,
        step = _ref$step === undefined ? 1 : _ref$step,
        _ref$prefix = _ref.prefix,
        prefix = _ref$prefix === undefined ? '' : _ref$prefix,
        _ref$suffix = _ref.suffix,
        suffix = _ref$suffix === undefined ? '' : _ref$suffix;
    return __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
        'div',
        { className: "input-field " + aclass },
        __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
            'p',
            { className: 'grey-text bottom0' },
            label,
            ' : ',
            __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                'span',
                {
                    className: 'green-text text-darken-1' },
                ' ',
                prefix + val + suffix,
                ' '
            )
        ),
        __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
            'p',
            { className: 'range-field top0' },
            __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement('input', { type: 'range', name: name, value: val, step: step,
                onChange: onch, id: name, min: min, max: max })
        )
    );
};

/* harmony default export */ __webpack_exports__["a"] = (RangeField);

/***/ }),
/* 4 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__createStore__ = __webpack_require__(10);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__combineReducers__ = __webpack_require__(43);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__bindActionCreators__ = __webpack_require__(44);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3__applyMiddleware__ = __webpack_require__(45);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_4__compose__ = __webpack_require__(13);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_5__utils_warning__ = __webpack_require__(12);
/* harmony reexport (binding) */ __webpack_require__.d(__webpack_exports__, "createStore", function() { return __WEBPACK_IMPORTED_MODULE_0__createStore__["b"]; });
/* harmony reexport (binding) */ __webpack_require__.d(__webpack_exports__, "combineReducers", function() { return __WEBPACK_IMPORTED_MODULE_1__combineReducers__["a"]; });
/* harmony reexport (binding) */ __webpack_require__.d(__webpack_exports__, "bindActionCreators", function() { return __WEBPACK_IMPORTED_MODULE_2__bindActionCreators__["a"]; });
/* harmony reexport (binding) */ __webpack_require__.d(__webpack_exports__, "applyMiddleware", function() { return __WEBPACK_IMPORTED_MODULE_3__applyMiddleware__["a"]; });
/* harmony reexport (binding) */ __webpack_require__.d(__webpack_exports__, "compose", function() { return __WEBPACK_IMPORTED_MODULE_4__compose__["a"]; });







/*
* This is a dummy function to check if the function name has been altered by minification.
* If the function has been minified and NODE_ENV !== 'production', warn the user.
*/
function isCrushed() {}

if ("development" !== 'production' && typeof isCrushed.name === 'string' && isCrushed.name !== 'isCrushed') {
  Object(__WEBPACK_IMPORTED_MODULE_5__utils_warning__["a" /* default */])('You are currently using minified code outside of NODE_ENV === \'production\'. ' + 'This means that you are running a slower development build of Redux. ' + 'You can use loose-envify (https://github.com/zertosh/loose-envify) for browserify ' + 'or DefinePlugin for webpack (http://stackoverflow.com/questions/30030031) ' + 'to ensure you have the correct code for your production build.');
}



/***/ }),
/* 5 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__baseGetTag_js__ = __webpack_require__(31);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__getPrototype_js__ = __webpack_require__(36);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__isObjectLike_js__ = __webpack_require__(38);




/** `Object#toString` result references. */
var objectTag = '[object Object]';

/** Used for built-in method references. */
var funcProto = Function.prototype,
    objectProto = Object.prototype;

/** Used to resolve the decompiled source of functions. */
var funcToString = funcProto.toString;

/** Used to check objects for own properties. */
var hasOwnProperty = objectProto.hasOwnProperty;

/** Used to infer the `Object` constructor. */
var objectCtorString = funcToString.call(Object);

/**
 * Checks if `value` is a plain object, that is, an object created by the
 * `Object` constructor or one with a `[[Prototype]]` of `null`.
 *
 * @static
 * @memberOf _
 * @since 0.8.0
 * @category Lang
 * @param {*} value The value to check.
 * @returns {boolean} Returns `true` if `value` is a plain object, else `false`.
 * @example
 *
 * function Foo() {
 *   this.a = 1;
 * }
 *
 * _.isPlainObject(new Foo);
 * // => false
 *
 * _.isPlainObject([1, 2, 3]);
 * // => false
 *
 * _.isPlainObject({ 'x': 0, 'y': 0 });
 * // => true
 *
 * _.isPlainObject(Object.create(null));
 * // => true
 */
function isPlainObject(value) {
  if (!Object(__WEBPACK_IMPORTED_MODULE_2__isObjectLike_js__["a" /* default */])(value) || Object(__WEBPACK_IMPORTED_MODULE_0__baseGetTag_js__["a" /* default */])(value) != objectTag) {
    return false;
  }
  var proto = Object(__WEBPACK_IMPORTED_MODULE_1__getPrototype_js__["a" /* default */])(value);
  if (proto === null) {
    return true;
  }
  var Ctor = hasOwnProperty.call(proto, 'constructor') && proto.constructor;
  return typeof Ctor == 'function' && Ctor instanceof Ctor &&
    funcToString.call(Ctor) == objectCtorString;
}

/* harmony default export */ __webpack_exports__["a"] = (isPlainObject);


/***/ }),
/* 6 */
/***/ (function(module, exports) {

var g;

// This works in non-strict mode
g = (function() {
	return this;
})();

try {
	// This works if eval is allowed (see CSP)
	g = g || Function("return this")() || (1,eval)("this");
} catch(e) {
	// This works if the window reference is available
	if(typeof window === "object")
		g = window;
}

// g can still be undefined, but nothing to do about it...
// We return undefined, instead of nothing here, so it's
// easier to handle this case. if(!global) { ...}

module.exports = g;


/***/ }),
/* 7 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (immutable) */ __webpack_exports__["a"] = warning;
/**
 * Prints a warning in the console if it exists.
 *
 * @param {String} message The warning message.
 * @returns {void}
 */
function warning(message) {
  /* eslint-disable no-console */
  if (typeof console !== 'undefined' && typeof console.error === 'function') {
    console.error(message);
  }
  /* eslint-enable no-console */
  try {
    // This error was thrown as a convenience so that if you enable
    // "break on all exceptions" in your console,
    // it would pause the execution at this line.
    throw new Error(message);
    /* eslint-disable no-empty */
  } catch (e) {}
  /* eslint-enable no-empty */
}

/***/ }),
/* 8 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_react__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_react___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0_react__);


// dumb component ... only props and presentation
var TextField = function TextField(_ref) {
    var val = _ref.val,
        onch = _ref.onch,
        name = _ref.name,
        label = _ref.label,
        aclass = _ref.aclass,
        disabled = _ref.disabled;
    return __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
        "div",
        { className: "input-field " + aclass },
        __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement("input", { type: "text", name: name, value: val,
            onChange: onch, disabled: disabled }),
        __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
            "label",
            { htmlFor: name },
            label
        )
    );
};

/* harmony default export */ __webpack_exports__["a"] = (TextField);

/***/ }),
/* 9 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/* WEBPACK VAR INJECTION */(function(process) {

var utils = __webpack_require__(1);
var normalizeHeaderName = __webpack_require__(71);

var DEFAULT_CONTENT_TYPE = {
  'Content-Type': 'application/x-www-form-urlencoded'
};

function setContentTypeIfUnset(headers, value) {
  if (!utils.isUndefined(headers) && utils.isUndefined(headers['Content-Type'])) {
    headers['Content-Type'] = value;
  }
}

function getDefaultAdapter() {
  var adapter;
  if (typeof XMLHttpRequest !== 'undefined') {
    // For browsers use XHR adapter
    adapter = __webpack_require__(24);
  } else if (typeof process !== 'undefined') {
    // For node use HTTP adapter
    adapter = __webpack_require__(24);
  }
  return adapter;
}

var defaults = {
  adapter: getDefaultAdapter(),

  transformRequest: [function transformRequest(data, headers) {
    normalizeHeaderName(headers, 'Content-Type');
    if (utils.isFormData(data) ||
      utils.isArrayBuffer(data) ||
      utils.isBuffer(data) ||
      utils.isStream(data) ||
      utils.isFile(data) ||
      utils.isBlob(data)
    ) {
      return data;
    }
    if (utils.isArrayBufferView(data)) {
      return data.buffer;
    }
    if (utils.isURLSearchParams(data)) {
      setContentTypeIfUnset(headers, 'application/x-www-form-urlencoded;charset=utf-8');
      return data.toString();
    }
    if (utils.isObject(data)) {
      setContentTypeIfUnset(headers, 'application/json;charset=utf-8');
      return JSON.stringify(data);
    }
    return data;
  }],

  transformResponse: [function transformResponse(data) {
    /*eslint no-param-reassign:0*/
    if (typeof data === 'string') {
      try {
        data = JSON.parse(data);
      } catch (e) { /* Ignore */ }
    }
    return data;
  }],

  timeout: 0,

  xsrfCookieName: 'XSRF-TOKEN',
  xsrfHeaderName: 'X-XSRF-TOKEN',

  maxContentLength: -1,

  validateStatus: function validateStatus(status) {
    return status >= 200 && status < 300;
  }
};

defaults.headers = {
  common: {
    'Accept': 'application/json, text/plain, */*'
  }
};

utils.forEach(['delete', 'get', 'head'], function forEachMethodNoData(method) {
  defaults.headers[method] = {};
});

utils.forEach(['post', 'put', 'patch'], function forEachMethodWithData(method) {
  defaults.headers[method] = utils.merge(DEFAULT_CONTENT_TYPE);
});

module.exports = defaults;

/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(70)))

/***/ }),
/* 10 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return ActionTypes; });
/* harmony export (immutable) */ __webpack_exports__["b"] = createStore;
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_lodash_es_isPlainObject__ = __webpack_require__(5);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_symbol_observable__ = __webpack_require__(39);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_symbol_observable___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_1_symbol_observable__);



/**
 * These are private action types reserved by Redux.
 * For any unknown actions, you must return the current state.
 * If the current state is undefined, you must return the initial state.
 * Do not reference these action types directly in your code.
 */
var ActionTypes = {
  INIT: '@@redux/INIT'

  /**
   * Creates a Redux store that holds the state tree.
   * The only way to change the data in the store is to call `dispatch()` on it.
   *
   * There should only be a single store in your app. To specify how different
   * parts of the state tree respond to actions, you may combine several reducers
   * into a single reducer function by using `combineReducers`.
   *
   * @param {Function} reducer A function that returns the next state tree, given
   * the current state tree and the action to handle.
   *
   * @param {any} [preloadedState] The initial state. You may optionally specify it
   * to hydrate the state from the server in universal apps, or to restore a
   * previously serialized user session.
   * If you use `combineReducers` to produce the root reducer function, this must be
   * an object with the same shape as `combineReducers` keys.
   *
   * @param {Function} [enhancer] The store enhancer. You may optionally specify it
   * to enhance the store with third-party capabilities such as middleware,
   * time travel, persistence, etc. The only store enhancer that ships with Redux
   * is `applyMiddleware()`.
   *
   * @returns {Store} A Redux store that lets you read the state, dispatch actions
   * and subscribe to changes.
   */
};function createStore(reducer, preloadedState, enhancer) {
  var _ref2;

  if (typeof preloadedState === 'function' && typeof enhancer === 'undefined') {
    enhancer = preloadedState;
    preloadedState = undefined;
  }

  if (typeof enhancer !== 'undefined') {
    if (typeof enhancer !== 'function') {
      throw new Error('Expected the enhancer to be a function.');
    }

    return enhancer(createStore)(reducer, preloadedState);
  }

  if (typeof reducer !== 'function') {
    throw new Error('Expected the reducer to be a function.');
  }

  var currentReducer = reducer;
  var currentState = preloadedState;
  var currentListeners = [];
  var nextListeners = currentListeners;
  var isDispatching = false;

  function ensureCanMutateNextListeners() {
    if (nextListeners === currentListeners) {
      nextListeners = currentListeners.slice();
    }
  }

  /**
   * Reads the state tree managed by the store.
   *
   * @returns {any} The current state tree of your application.
   */
  function getState() {
    return currentState;
  }

  /**
   * Adds a change listener. It will be called any time an action is dispatched,
   * and some part of the state tree may potentially have changed. You may then
   * call `getState()` to read the current state tree inside the callback.
   *
   * You may call `dispatch()` from a change listener, with the following
   * caveats:
   *
   * 1. The subscriptions are snapshotted just before every `dispatch()` call.
   * If you subscribe or unsubscribe while the listeners are being invoked, this
   * will not have any effect on the `dispatch()` that is currently in progress.
   * However, the next `dispatch()` call, whether nested or not, will use a more
   * recent snapshot of the subscription list.
   *
   * 2. The listener should not expect to see all state changes, as the state
   * might have been updated multiple times during a nested `dispatch()` before
   * the listener is called. It is, however, guaranteed that all subscribers
   * registered before the `dispatch()` started will be called with the latest
   * state by the time it exits.
   *
   * @param {Function} listener A callback to be invoked on every dispatch.
   * @returns {Function} A function to remove this change listener.
   */
  function subscribe(listener) {
    if (typeof listener !== 'function') {
      throw new Error('Expected listener to be a function.');
    }

    var isSubscribed = true;

    ensureCanMutateNextListeners();
    nextListeners.push(listener);

    return function unsubscribe() {
      if (!isSubscribed) {
        return;
      }

      isSubscribed = false;

      ensureCanMutateNextListeners();
      var index = nextListeners.indexOf(listener);
      nextListeners.splice(index, 1);
    };
  }

  /**
   * Dispatches an action. It is the only way to trigger a state change.
   *
   * The `reducer` function, used to create the store, will be called with the
   * current state tree and the given `action`. Its return value will
   * be considered the **next** state of the tree, and the change listeners
   * will be notified.
   *
   * The base implementation only supports plain object actions. If you want to
   * dispatch a Promise, an Observable, a thunk, or something else, you need to
   * wrap your store creating function into the corresponding middleware. For
   * example, see the documentation for the `redux-thunk` package. Even the
   * middleware will eventually dispatch plain object actions using this method.
   *
   * @param {Object} action A plain object representing “what changed”. It is
   * a good idea to keep actions serializable so you can record and replay user
   * sessions, or use the time travelling `redux-devtools`. An action must have
   * a `type` property which may not be `undefined`. It is a good idea to use
   * string constants for action types.
   *
   * @returns {Object} For convenience, the same action object you dispatched.
   *
   * Note that, if you use a custom middleware, it may wrap `dispatch()` to
   * return something else (for example, a Promise you can await).
   */
  function dispatch(action) {
    if (!Object(__WEBPACK_IMPORTED_MODULE_0_lodash_es_isPlainObject__["a" /* default */])(action)) {
      throw new Error('Actions must be plain objects. ' + 'Use custom middleware for async actions.');
    }

    if (typeof action.type === 'undefined') {
      throw new Error('Actions may not have an undefined "type" property. ' + 'Have you misspelled a constant?');
    }

    if (isDispatching) {
      throw new Error('Reducers may not dispatch actions.');
    }

    try {
      isDispatching = true;
      currentState = currentReducer(currentState, action);
    } finally {
      isDispatching = false;
    }

    var listeners = currentListeners = nextListeners;
    for (var i = 0; i < listeners.length; i++) {
      var listener = listeners[i];
      listener();
    }

    return action;
  }

  /**
   * Replaces the reducer currently used by the store to calculate the state.
   *
   * You might need this if your app implements code splitting and you want to
   * load some of the reducers dynamically. You might also need this if you
   * implement a hot reloading mechanism for Redux.
   *
   * @param {Function} nextReducer The reducer for the store to use instead.
   * @returns {void}
   */
  function replaceReducer(nextReducer) {
    if (typeof nextReducer !== 'function') {
      throw new Error('Expected the nextReducer to be a function.');
    }

    currentReducer = nextReducer;
    dispatch({ type: ActionTypes.INIT });
  }

  /**
   * Interoperability point for observable/reactive libraries.
   * @returns {observable} A minimal observable of state changes.
   * For more information, see the observable proposal:
   * https://github.com/tc39/proposal-observable
   */
  function observable() {
    var _ref;

    var outerSubscribe = subscribe;
    return _ref = {
      /**
       * The minimal observable subscription method.
       * @param {Object} observer Any object that can be used as an observer.
       * The observer object should have a `next` method.
       * @returns {subscription} An object with an `unsubscribe` method that can
       * be used to unsubscribe the observable from the store, and prevent further
       * emission of values from the observable.
       */
      subscribe: function subscribe(observer) {
        if (typeof observer !== 'object') {
          throw new TypeError('Expected the observer to be an object.');
        }

        function observeState() {
          if (observer.next) {
            observer.next(getState());
          }
        }

        observeState();
        var unsubscribe = outerSubscribe(observeState);
        return { unsubscribe: unsubscribe };
      }
    }, _ref[__WEBPACK_IMPORTED_MODULE_1_symbol_observable___default.a] = function () {
      return this;
    }, _ref;
  }

  // When a store is created, an "INIT" action is dispatched so that every
  // reducer returns their initial state. This effectively populates
  // the initial state tree.
  dispatch({ type: ActionTypes.INIT });

  return _ref2 = {
    dispatch: dispatch,
    subscribe: subscribe,
    getState: getState,
    replaceReducer: replaceReducer
  }, _ref2[__WEBPACK_IMPORTED_MODULE_1_symbol_observable___default.a] = observable, _ref2;
}

/***/ }),
/* 11 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__root_js__ = __webpack_require__(32);


/** Built-in value references. */
var Symbol = __WEBPACK_IMPORTED_MODULE_0__root_js__["a" /* default */].Symbol;

/* harmony default export */ __webpack_exports__["a"] = (Symbol);


/***/ }),
/* 12 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (immutable) */ __webpack_exports__["a"] = warning;
/**
 * Prints a warning in the console if it exists.
 *
 * @param {String} message The warning message.
 * @returns {void}
 */
function warning(message) {
  /* eslint-disable no-console */
  if (typeof console !== 'undefined' && typeof console.error === 'function') {
    console.error(message);
  }
  /* eslint-enable no-console */
  try {
    // This error was thrown as a convenience so that if you enable
    // "break on all exceptions" in your console,
    // it would pause the execution at this line.
    throw new Error(message);
    /* eslint-disable no-empty */
  } catch (e) {}
  /* eslint-enable no-empty */
}

/***/ }),
/* 13 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (immutable) */ __webpack_exports__["a"] = compose;
/**
 * Composes single-argument functions from right to left. The rightmost
 * function can take multiple arguments as it provides the signature for
 * the resulting composite function.
 *
 * @param {...Function} funcs The functions to compose.
 * @returns {Function} A function obtained by composing the argument functions
 * from right to left. For example, compose(f, g, h) is identical to doing
 * (...args) => f(g(h(...args))).
 */

function compose() {
  for (var _len = arguments.length, funcs = Array(_len), _key = 0; _key < _len; _key++) {
    funcs[_key] = arguments[_key];
  }

  if (funcs.length === 0) {
    return function (arg) {
      return arg;
    };
  }

  if (funcs.length === 1) {
    return funcs[0];
  }

  return funcs.reduce(function (a, b) {
    return function () {
      return a(b.apply(undefined, arguments));
    };
  });
}

/***/ }),
/* 14 */
/***/ (function(module, exports, __webpack_require__) {

/**
 * Copyright 2013-present, Facebook, Inc.
 * All rights reserved.
 *
 * This source code is licensed under the BSD-style license found in the
 * LICENSE file in the root directory of this source tree. An additional grant
 * of patent rights can be found in the PATENTS file in the same directory.
 */

if (true) {
  var REACT_ELEMENT_TYPE = (typeof Symbol === 'function' &&
    Symbol.for &&
    Symbol.for('react.element')) ||
    0xeac7;

  var isValidElement = function(object) {
    return typeof object === 'object' &&
      object !== null &&
      object.$$typeof === REACT_ELEMENT_TYPE;
  };

  // By explicitly using `prop-types` you are opting into new development behavior.
  // http://fb.me/prop-types-in-prod
  var throwOnDirectAccess = true;
  module.exports = __webpack_require__(50)(isValidElement, throwOnDirectAccess);
} else {
  // By explicitly using `prop-types` you are opting into new production behavior.
  // http://fb.me/prop-types-in-prod
  module.exports = require('./factoryWithThrowingShims')();
}


/***/ }),
/* 15 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


/**
 * Copyright (c) 2013-present, Facebook, Inc.
 * All rights reserved.
 *
 * This source code is licensed under the BSD-style license found in the
 * LICENSE file in the root directory of this source tree. An additional grant
 * of patent rights can be found in the PATENTS file in the same directory.
 *
 * 
 */

function makeEmptyFunction(arg) {
  return function () {
    return arg;
  };
}

/**
 * This function accepts and discards inputs; it has no side effects. This is
 * primarily useful idiomatically for overridable function endpoints which
 * always need to be callable, since JS lacks a null-call idiom ala Cocoa.
 */
var emptyFunction = function emptyFunction() {};

emptyFunction.thatReturns = makeEmptyFunction;
emptyFunction.thatReturnsFalse = makeEmptyFunction(false);
emptyFunction.thatReturnsTrue = makeEmptyFunction(true);
emptyFunction.thatReturnsNull = makeEmptyFunction(null);
emptyFunction.thatReturnsThis = function () {
  return this;
};
emptyFunction.thatReturnsArgument = function (arg) {
  return arg;
};

module.exports = emptyFunction;

/***/ }),
/* 16 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/**
 * Copyright (c) 2013-present, Facebook, Inc.
 * All rights reserved.
 *
 * This source code is licensed under the BSD-style license found in the
 * LICENSE file in the root directory of this source tree. An additional grant
 * of patent rights can be found in the PATENTS file in the same directory.
 *
 */



/**
 * Use invariant() to assert state which your program assumes to be true.
 *
 * Provide sprintf-style format (only %s is supported) and arguments
 * to provide information about what broke and what you were
 * expecting.
 *
 * The invariant message will be stripped in production, but the invariant
 * will remain to ensure logic does not differ in production.
 */

var validateFormat = function validateFormat(format) {};

if (true) {
  validateFormat = function validateFormat(format) {
    if (format === undefined) {
      throw new Error('invariant requires an error message argument');
    }
  };
}

function invariant(condition, format, a, b, c, d, e, f) {
  validateFormat(format);

  if (!condition) {
    var error;
    if (format === undefined) {
      error = new Error('Minified exception occurred; use the non-minified dev environment ' + 'for the full error message and additional helpful warnings.');
    } else {
      var args = [a, b, c, d, e, f];
      var argIndex = 0;
      error = new Error(format.replace(/%s/g, function () {
        return args[argIndex++];
      }));
      error.name = 'Invariant Violation';
    }

    error.framesToPop = 1; // we don't care about invariant's own frame
    throw error;
  }
}

module.exports = invariant;

/***/ }),
/* 17 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/**
 * Copyright 2014-2015, Facebook, Inc.
 * All rights reserved.
 *
 * This source code is licensed under the BSD-style license found in the
 * LICENSE file in the root directory of this source tree. An additional grant
 * of patent rights can be found in the PATENTS file in the same directory.
 *
 */



var emptyFunction = __webpack_require__(15);

/**
 * Similar to invariant but only logs a warning if the condition is not met.
 * This can be used to log issues in development environments in critical
 * paths. Removing the logging code for production environments will keep the
 * same logic and follow the same code paths.
 */

var warning = emptyFunction;

if (true) {
  var printWarning = function printWarning(format) {
    for (var _len = arguments.length, args = Array(_len > 1 ? _len - 1 : 0), _key = 1; _key < _len; _key++) {
      args[_key - 1] = arguments[_key];
    }

    var argIndex = 0;
    var message = 'Warning: ' + format.replace(/%s/g, function () {
      return args[argIndex++];
    });
    if (typeof console !== 'undefined') {
      console.error(message);
    }
    try {
      // --- Welcome to debugging React ---
      // This error was thrown as a convenience so that you can use this stack
      // to find the callsite that caused this warning to fire.
      throw new Error(message);
    } catch (x) {}
  };

  warning = function warning(condition, format) {
    if (format === undefined) {
      throw new Error('`warning(condition, format, ...args)` requires a warning ' + 'message argument');
    }

    if (format.indexOf('Failed Composite propType: ') === 0) {
      return; // Ignore CompositeComponent proptype check.
    }

    if (!condition) {
      for (var _len2 = arguments.length, args = Array(_len2 > 2 ? _len2 - 2 : 0), _key2 = 2; _key2 < _len2; _key2++) {
        args[_key2 - 2] = arguments[_key2];
      }

      printWarning.apply(undefined, [format].concat(args));
    }
  };
}

module.exports = warning;

/***/ }),
/* 18 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/**
 * Copyright 2013-present, Facebook, Inc.
 * All rights reserved.
 *
 * This source code is licensed under the BSD-style license found in the
 * LICENSE file in the root directory of this source tree. An additional grant
 * of patent rights can be found in the PATENTS file in the same directory.
 */



var ReactPropTypesSecret = 'SECRET_DO_NOT_PASS_THIS_OR_YOU_WILL_BE_FIRED';

module.exports = ReactPropTypesSecret;


/***/ }),
/* 19 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "b", function() { return subscriptionShape; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return storeShape; });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_prop_types__ = __webpack_require__(14);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_prop_types___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0_prop_types__);


var subscriptionShape = __WEBPACK_IMPORTED_MODULE_0_prop_types___default.a.shape({
  trySubscribe: __WEBPACK_IMPORTED_MODULE_0_prop_types___default.a.func.isRequired,
  tryUnsubscribe: __WEBPACK_IMPORTED_MODULE_0_prop_types___default.a.func.isRequired,
  notifyNestedSubs: __WEBPACK_IMPORTED_MODULE_0_prop_types___default.a.func.isRequired,
  isSubscribed: __WEBPACK_IMPORTED_MODULE_0_prop_types___default.a.func.isRequired
});

var storeShape = __WEBPACK_IMPORTED_MODULE_0_prop_types___default.a.shape({
  subscribe: __WEBPACK_IMPORTED_MODULE_0_prop_types___default.a.func.isRequired,
  dispatch: __WEBPACK_IMPORTED_MODULE_0_prop_types___default.a.func.isRequired,
  getState: __WEBPACK_IMPORTED_MODULE_0_prop_types___default.a.func.isRequired
});

/***/ }),
/* 20 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (immutable) */ __webpack_exports__["a"] = connectAdvanced;
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_hoist_non_react_statics__ = __webpack_require__(52);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_hoist_non_react_statics___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0_hoist_non_react_statics__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_invariant__ = __webpack_require__(53);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_invariant___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_1_invariant__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2_react__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2_react___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_2_react__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3__utils_Subscription__ = __webpack_require__(54);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_4__utils_PropTypes__ = __webpack_require__(19);
var _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; };

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

function _objectWithoutProperties(obj, keys) { var target = {}; for (var i in obj) { if (keys.indexOf(i) >= 0) continue; if (!Object.prototype.hasOwnProperty.call(obj, i)) continue; target[i] = obj[i]; } return target; }








var hotReloadingVersion = 0;
var dummyState = {};
function noop() {}
function makeSelectorStateful(sourceSelector, store) {
  // wrap the selector in an object that tracks its results between runs.
  var selector = {
    run: function runComponentSelector(props) {
      try {
        var nextProps = sourceSelector(store.getState(), props);
        if (nextProps !== selector.props || selector.error) {
          selector.shouldComponentUpdate = true;
          selector.props = nextProps;
          selector.error = null;
        }
      } catch (error) {
        selector.shouldComponentUpdate = true;
        selector.error = error;
      }
    }
  };

  return selector;
}

function connectAdvanced(
/*
  selectorFactory is a func that is responsible for returning the selector function used to
  compute new props from state, props, and dispatch. For example:
     export default connectAdvanced((dispatch, options) => (state, props) => ({
      thing: state.things[props.thingId],
      saveThing: fields => dispatch(actionCreators.saveThing(props.thingId, fields)),
    }))(YourComponent)
   Access to dispatch is provided to the factory so selectorFactories can bind actionCreators
  outside of their selector as an optimization. Options passed to connectAdvanced are passed to
  the selectorFactory, along with displayName and WrappedComponent, as the second argument.
   Note that selectorFactory is responsible for all caching/memoization of inbound and outbound
  props. Do not use connectAdvanced directly without memoizing results between calls to your
  selector, otherwise the Connect component will re-render on every state or props change.
*/
selectorFactory) {
  var _contextTypes, _childContextTypes;

  var _ref = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {},
      _ref$getDisplayName = _ref.getDisplayName,
      getDisplayName = _ref$getDisplayName === undefined ? function (name) {
    return 'ConnectAdvanced(' + name + ')';
  } : _ref$getDisplayName,
      _ref$methodName = _ref.methodName,
      methodName = _ref$methodName === undefined ? 'connectAdvanced' : _ref$methodName,
      _ref$renderCountProp = _ref.renderCountProp,
      renderCountProp = _ref$renderCountProp === undefined ? undefined : _ref$renderCountProp,
      _ref$shouldHandleStat = _ref.shouldHandleStateChanges,
      shouldHandleStateChanges = _ref$shouldHandleStat === undefined ? true : _ref$shouldHandleStat,
      _ref$storeKey = _ref.storeKey,
      storeKey = _ref$storeKey === undefined ? 'store' : _ref$storeKey,
      _ref$withRef = _ref.withRef,
      withRef = _ref$withRef === undefined ? false : _ref$withRef,
      connectOptions = _objectWithoutProperties(_ref, ['getDisplayName', 'methodName', 'renderCountProp', 'shouldHandleStateChanges', 'storeKey', 'withRef']);

  var subscriptionKey = storeKey + 'Subscription';
  var version = hotReloadingVersion++;

  var contextTypes = (_contextTypes = {}, _contextTypes[storeKey] = __WEBPACK_IMPORTED_MODULE_4__utils_PropTypes__["a" /* storeShape */], _contextTypes[subscriptionKey] = __WEBPACK_IMPORTED_MODULE_4__utils_PropTypes__["b" /* subscriptionShape */], _contextTypes);
  var childContextTypes = (_childContextTypes = {}, _childContextTypes[subscriptionKey] = __WEBPACK_IMPORTED_MODULE_4__utils_PropTypes__["b" /* subscriptionShape */], _childContextTypes);

  return function wrapWithConnect(WrappedComponent) {
    __WEBPACK_IMPORTED_MODULE_1_invariant___default()(typeof WrappedComponent == 'function', 'You must pass a component to the function returned by ' + ('connect. Instead received ' + JSON.stringify(WrappedComponent)));

    var wrappedComponentName = WrappedComponent.displayName || WrappedComponent.name || 'Component';

    var displayName = getDisplayName(wrappedComponentName);

    var selectorFactoryOptions = _extends({}, connectOptions, {
      getDisplayName: getDisplayName,
      methodName: methodName,
      renderCountProp: renderCountProp,
      shouldHandleStateChanges: shouldHandleStateChanges,
      storeKey: storeKey,
      withRef: withRef,
      displayName: displayName,
      wrappedComponentName: wrappedComponentName,
      WrappedComponent: WrappedComponent
    });

    var Connect = function (_Component) {
      _inherits(Connect, _Component);

      function Connect(props, context) {
        _classCallCheck(this, Connect);

        var _this = _possibleConstructorReturn(this, _Component.call(this, props, context));

        _this.version = version;
        _this.state = {};
        _this.renderCount = 0;
        _this.store = props[storeKey] || context[storeKey];
        _this.propsMode = Boolean(props[storeKey]);
        _this.setWrappedInstance = _this.setWrappedInstance.bind(_this);

        __WEBPACK_IMPORTED_MODULE_1_invariant___default()(_this.store, 'Could not find "' + storeKey + '" in either the context or props of ' + ('"' + displayName + '". Either wrap the root component in a <Provider>, ') + ('or explicitly pass "' + storeKey + '" as a prop to "' + displayName + '".'));

        _this.initSelector();
        _this.initSubscription();
        return _this;
      }

      Connect.prototype.getChildContext = function getChildContext() {
        var _ref2;

        // If this component received store from props, its subscription should be transparent
        // to any descendants receiving store+subscription from context; it passes along
        // subscription passed to it. Otherwise, it shadows the parent subscription, which allows
        // Connect to control ordering of notifications to flow top-down.
        var subscription = this.propsMode ? null : this.subscription;
        return _ref2 = {}, _ref2[subscriptionKey] = subscription || this.context[subscriptionKey], _ref2;
      };

      Connect.prototype.componentDidMount = function componentDidMount() {
        if (!shouldHandleStateChanges) return;

        // componentWillMount fires during server side rendering, but componentDidMount and
        // componentWillUnmount do not. Because of this, trySubscribe happens during ...didMount.
        // Otherwise, unsubscription would never take place during SSR, causing a memory leak.
        // To handle the case where a child component may have triggered a state change by
        // dispatching an action in its componentWillMount, we have to re-run the select and maybe
        // re-render.
        this.subscription.trySubscribe();
        this.selector.run(this.props);
        if (this.selector.shouldComponentUpdate) this.forceUpdate();
      };

      Connect.prototype.componentWillReceiveProps = function componentWillReceiveProps(nextProps) {
        this.selector.run(nextProps);
      };

      Connect.prototype.shouldComponentUpdate = function shouldComponentUpdate() {
        return this.selector.shouldComponentUpdate;
      };

      Connect.prototype.componentWillUnmount = function componentWillUnmount() {
        if (this.subscription) this.subscription.tryUnsubscribe();
        this.subscription = null;
        this.notifyNestedSubs = noop;
        this.store = null;
        this.selector.run = noop;
        this.selector.shouldComponentUpdate = false;
      };

      Connect.prototype.getWrappedInstance = function getWrappedInstance() {
        __WEBPACK_IMPORTED_MODULE_1_invariant___default()(withRef, 'To access the wrapped instance, you need to specify ' + ('{ withRef: true } in the options argument of the ' + methodName + '() call.'));
        return this.wrappedInstance;
      };

      Connect.prototype.setWrappedInstance = function setWrappedInstance(ref) {
        this.wrappedInstance = ref;
      };

      Connect.prototype.initSelector = function initSelector() {
        var sourceSelector = selectorFactory(this.store.dispatch, selectorFactoryOptions);
        this.selector = makeSelectorStateful(sourceSelector, this.store);
        this.selector.run(this.props);
      };

      Connect.prototype.initSubscription = function initSubscription() {
        if (!shouldHandleStateChanges) return;

        // parentSub's source should match where store came from: props vs. context. A component
        // connected to the store via props shouldn't use subscription from context, or vice versa.
        var parentSub = (this.propsMode ? this.props : this.context)[subscriptionKey];
        this.subscription = new __WEBPACK_IMPORTED_MODULE_3__utils_Subscription__["a" /* default */](this.store, parentSub, this.onStateChange.bind(this));

        // `notifyNestedSubs` is duplicated to handle the case where the component is  unmounted in
        // the middle of the notification loop, where `this.subscription` will then be null. An
        // extra null check every change can be avoided by copying the method onto `this` and then
        // replacing it with a no-op on unmount. This can probably be avoided if Subscription's
        // listeners logic is changed to not call listeners that have been unsubscribed in the
        // middle of the notification loop.
        this.notifyNestedSubs = this.subscription.notifyNestedSubs.bind(this.subscription);
      };

      Connect.prototype.onStateChange = function onStateChange() {
        this.selector.run(this.props);

        if (!this.selector.shouldComponentUpdate) {
          this.notifyNestedSubs();
        } else {
          this.componentDidUpdate = this.notifyNestedSubsOnComponentDidUpdate;
          this.setState(dummyState);
        }
      };

      Connect.prototype.notifyNestedSubsOnComponentDidUpdate = function notifyNestedSubsOnComponentDidUpdate() {
        // `componentDidUpdate` is conditionally implemented when `onStateChange` determines it
        // needs to notify nested subs. Once called, it unimplements itself until further state
        // changes occur. Doing it this way vs having a permanent `componentDidUpdate` that does
        // a boolean check every time avoids an extra method call most of the time, resulting
        // in some perf boost.
        this.componentDidUpdate = undefined;
        this.notifyNestedSubs();
      };

      Connect.prototype.isSubscribed = function isSubscribed() {
        return Boolean(this.subscription) && this.subscription.isSubscribed();
      };

      Connect.prototype.addExtraProps = function addExtraProps(props) {
        if (!withRef && !renderCountProp && !(this.propsMode && this.subscription)) return props;
        // make a shallow copy so that fields added don't leak to the original selector.
        // this is especially important for 'ref' since that's a reference back to the component
        // instance. a singleton memoized selector would then be holding a reference to the
        // instance, preventing the instance from being garbage collected, and that would be bad
        var withExtras = _extends({}, props);
        if (withRef) withExtras.ref = this.setWrappedInstance;
        if (renderCountProp) withExtras[renderCountProp] = this.renderCount++;
        if (this.propsMode && this.subscription) withExtras[subscriptionKey] = this.subscription;
        return withExtras;
      };

      Connect.prototype.render = function render() {
        var selector = this.selector;
        selector.shouldComponentUpdate = false;

        if (selector.error) {
          throw selector.error;
        } else {
          return Object(__WEBPACK_IMPORTED_MODULE_2_react__["createElement"])(WrappedComponent, this.addExtraProps(selector.props));
        }
      };

      return Connect;
    }(__WEBPACK_IMPORTED_MODULE_2_react__["Component"]);

    Connect.WrappedComponent = WrappedComponent;
    Connect.displayName = displayName;
    Connect.childContextTypes = childContextTypes;
    Connect.contextTypes = contextTypes;
    Connect.propTypes = contextTypes;

    if (true) {
      Connect.prototype.componentWillUpdate = function componentWillUpdate() {
        var _this2 = this;

        // We are hot reloading!
        if (this.version !== version) {
          this.version = version;
          this.initSelector();

          // If any connected descendants don't hot reload (and resubscribe in the process), their
          // listeners will be lost when we unsubscribe. Unfortunately, by copying over all
          // listeners, this does mean that the old versions of connected descendants will still be
          // notified of state changes; however, their onStateChange function is a no-op so this
          // isn't a huge deal.
          var oldListeners = [];

          if (this.subscription) {
            oldListeners = this.subscription.listeners.get();
            this.subscription.tryUnsubscribe();
          }
          this.initSubscription();
          if (shouldHandleStateChanges) {
            this.subscription.trySubscribe();
            oldListeners.forEach(function (listener) {
              return _this2.subscription.listeners.subscribe(listener);
            });
          }
        }
      };
    }

    return __WEBPACK_IMPORTED_MODULE_0_hoist_non_react_statics___default()(Connect, WrappedComponent);
  };
}

/***/ }),
/* 21 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (immutable) */ __webpack_exports__["a"] = wrapMapToPropsConstant;
/* unused harmony export getDependsOnOwnProps */
/* harmony export (immutable) */ __webpack_exports__["b"] = wrapMapToPropsFunc;
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__utils_verifyPlainObject__ = __webpack_require__(22);


function wrapMapToPropsConstant(getConstant) {
  return function initConstantSelector(dispatch, options) {
    var constant = getConstant(dispatch, options);

    function constantSelector() {
      return constant;
    }
    constantSelector.dependsOnOwnProps = false;
    return constantSelector;
  };
}

// dependsOnOwnProps is used by createMapToPropsProxy to determine whether to pass props as args
// to the mapToProps function being wrapped. It is also used by makePurePropsSelector to determine
// whether mapToProps needs to be invoked when props have changed.
// 
// A length of one signals that mapToProps does not depend on props from the parent component.
// A length of zero is assumed to mean mapToProps is getting args via arguments or ...args and
// therefore not reporting its length accurately..
function getDependsOnOwnProps(mapToProps) {
  return mapToProps.dependsOnOwnProps !== null && mapToProps.dependsOnOwnProps !== undefined ? Boolean(mapToProps.dependsOnOwnProps) : mapToProps.length !== 1;
}

// Used by whenMapStateToPropsIsFunction and whenMapDispatchToPropsIsFunction,
// this function wraps mapToProps in a proxy function which does several things:
// 
//  * Detects whether the mapToProps function being called depends on props, which
//    is used by selectorFactory to decide if it should reinvoke on props changes.
//    
//  * On first call, handles mapToProps if returns another function, and treats that
//    new function as the true mapToProps for subsequent calls.
//    
//  * On first call, verifies the first result is a plain object, in order to warn
//    the developer that their mapToProps function is not returning a valid result.
//    
function wrapMapToPropsFunc(mapToProps, methodName) {
  return function initProxySelector(dispatch, _ref) {
    var displayName = _ref.displayName;

    var proxy = function mapToPropsProxy(stateOrDispatch, ownProps) {
      return proxy.dependsOnOwnProps ? proxy.mapToProps(stateOrDispatch, ownProps) : proxy.mapToProps(stateOrDispatch);
    };

    // allow detectFactoryAndVerify to get ownProps
    proxy.dependsOnOwnProps = true;

    proxy.mapToProps = function detectFactoryAndVerify(stateOrDispatch, ownProps) {
      proxy.mapToProps = mapToProps;
      proxy.dependsOnOwnProps = getDependsOnOwnProps(mapToProps);
      var props = proxy(stateOrDispatch, ownProps);

      if (typeof props === 'function') {
        proxy.mapToProps = props;
        proxy.dependsOnOwnProps = getDependsOnOwnProps(props);
        props = proxy(stateOrDispatch, ownProps);
      }

      if (true) Object(__WEBPACK_IMPORTED_MODULE_0__utils_verifyPlainObject__["a" /* default */])(props, displayName, methodName);

      return props;
    };

    return proxy;
  };
}

/***/ }),
/* 22 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (immutable) */ __webpack_exports__["a"] = verifyPlainObject;
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_lodash_es_isPlainObject__ = __webpack_require__(5);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__warning__ = __webpack_require__(7);



function verifyPlainObject(value, displayName, methodName) {
  if (!Object(__WEBPACK_IMPORTED_MODULE_0_lodash_es_isPlainObject__["a" /* default */])(value)) {
    Object(__WEBPACK_IMPORTED_MODULE_1__warning__["a" /* default */])(methodName + '() in ' + displayName + ' must return a plain object. Instead received ' + value + '.');
  }
}

/***/ }),
/* 23 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


module.exports = function bind(fn, thisArg) {
  return function wrap() {
    var args = new Array(arguments.length);
    for (var i = 0; i < args.length; i++) {
      args[i] = arguments[i];
    }
    return fn.apply(thisArg, args);
  };
};


/***/ }),
/* 24 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var utils = __webpack_require__(1);
var settle = __webpack_require__(72);
var buildURL = __webpack_require__(74);
var parseHeaders = __webpack_require__(75);
var isURLSameOrigin = __webpack_require__(76);
var createError = __webpack_require__(25);
var btoa = (typeof window !== 'undefined' && window.btoa && window.btoa.bind(window)) || __webpack_require__(77);

module.exports = function xhrAdapter(config) {
  return new Promise(function dispatchXhrRequest(resolve, reject) {
    var requestData = config.data;
    var requestHeaders = config.headers;

    if (utils.isFormData(requestData)) {
      delete requestHeaders['Content-Type']; // Let the browser set it
    }

    var request = new XMLHttpRequest();
    var loadEvent = 'onreadystatechange';
    var xDomain = false;

    // For IE 8/9 CORS support
    // Only supports POST and GET calls and doesn't returns the response headers.
    // DON'T do this for testing b/c XMLHttpRequest is mocked, not XDomainRequest.
    if ("development" !== 'test' &&
        typeof window !== 'undefined' &&
        window.XDomainRequest && !('withCredentials' in request) &&
        !isURLSameOrigin(config.url)) {
      request = new window.XDomainRequest();
      loadEvent = 'onload';
      xDomain = true;
      request.onprogress = function handleProgress() {};
      request.ontimeout = function handleTimeout() {};
    }

    // HTTP basic authentication
    if (config.auth) {
      var username = config.auth.username || '';
      var password = config.auth.password || '';
      requestHeaders.Authorization = 'Basic ' + btoa(username + ':' + password);
    }

    request.open(config.method.toUpperCase(), buildURL(config.url, config.params, config.paramsSerializer), true);

    // Set the request timeout in MS
    request.timeout = config.timeout;

    // Listen for ready state
    request[loadEvent] = function handleLoad() {
      if (!request || (request.readyState !== 4 && !xDomain)) {
        return;
      }

      // The request errored out and we didn't get a response, this will be
      // handled by onerror instead
      // With one exception: request that using file: protocol, most browsers
      // will return status as 0 even though it's a successful request
      if (request.status === 0 && !(request.responseURL && request.responseURL.indexOf('file:') === 0)) {
        return;
      }

      // Prepare the response
      var responseHeaders = 'getAllResponseHeaders' in request ? parseHeaders(request.getAllResponseHeaders()) : null;
      var responseData = !config.responseType || config.responseType === 'text' ? request.responseText : request.response;
      var response = {
        data: responseData,
        // IE sends 1223 instead of 204 (https://github.com/mzabriskie/axios/issues/201)
        status: request.status === 1223 ? 204 : request.status,
        statusText: request.status === 1223 ? 'No Content' : request.statusText,
        headers: responseHeaders,
        config: config,
        request: request
      };

      settle(resolve, reject, response);

      // Clean up request
      request = null;
    };

    // Handle low level network errors
    request.onerror = function handleError() {
      // Real errors are hidden from us by the browser
      // onerror should only fire if it's a network error
      reject(createError('Network Error', config, null, request));

      // Clean up request
      request = null;
    };

    // Handle timeout
    request.ontimeout = function handleTimeout() {
      reject(createError('timeout of ' + config.timeout + 'ms exceeded', config, 'ECONNABORTED',
        request));

      // Clean up request
      request = null;
    };

    // Add xsrf header
    // This is only done if running in a standard browser environment.
    // Specifically not if we're in a web worker, or react-native.
    if (utils.isStandardBrowserEnv()) {
      var cookies = __webpack_require__(78);

      // Add xsrf header
      var xsrfValue = (config.withCredentials || isURLSameOrigin(config.url)) && config.xsrfCookieName ?
          cookies.read(config.xsrfCookieName) :
          undefined;

      if (xsrfValue) {
        requestHeaders[config.xsrfHeaderName] = xsrfValue;
      }
    }

    // Add headers to the request
    if ('setRequestHeader' in request) {
      utils.forEach(requestHeaders, function setRequestHeader(val, key) {
        if (typeof requestData === 'undefined' && key.toLowerCase() === 'content-type') {
          // Remove Content-Type if data is undefined
          delete requestHeaders[key];
        } else {
          // Otherwise add header to the request
          request.setRequestHeader(key, val);
        }
      });
    }

    // Add withCredentials to request if needed
    if (config.withCredentials) {
      request.withCredentials = true;
    }

    // Add responseType to request if needed
    if (config.responseType) {
      try {
        request.responseType = config.responseType;
      } catch (e) {
        // Expected DOMException thrown by browsers not compatible XMLHttpRequest Level 2.
        // But, this can be suppressed for 'json' type as it can be parsed by default 'transformResponse' function.
        if (config.responseType !== 'json') {
          throw e;
        }
      }
    }

    // Handle progress if needed
    if (typeof config.onDownloadProgress === 'function') {
      request.addEventListener('progress', config.onDownloadProgress);
    }

    // Not all browsers support upload events
    if (typeof config.onUploadProgress === 'function' && request.upload) {
      request.upload.addEventListener('progress', config.onUploadProgress);
    }

    if (config.cancelToken) {
      // Handle cancellation
      config.cancelToken.promise.then(function onCanceled(cancel) {
        if (!request) {
          return;
        }

        request.abort();
        reject(cancel);
        // Clean up request
        request = null;
      });
    }

    if (requestData === undefined) {
      requestData = null;
    }

    // Send the request
    request.send(requestData);
  });
};


/***/ }),
/* 25 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var enhanceError = __webpack_require__(73);

/**
 * Create an Error with the specified message, config, error code, request and response.
 *
 * @param {string} message The error message.
 * @param {Object} config The config.
 * @param {string} [code] The error code (for example, 'ECONNABORTED').
 * @param {Object} [request] The request.
 * @param {Object} [response] The response.
 * @returns {Error} The created error.
 */
module.exports = function createError(message, config, code, request, response) {
  var error = new Error(message);
  return enhanceError(error, config, code, request, response);
};


/***/ }),
/* 26 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


module.exports = function isCancel(value) {
  return !!(value && value.__CANCEL__);
};


/***/ }),
/* 27 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


/**
 * A `Cancel` is an object that is thrown when an operation is canceled.
 *
 * @class
 * @param {string=} message The message.
 */
function Cancel(message) {
  this.message = message;
}

Cancel.prototype.toString = function toString() {
  return 'Cancel' + (this.message ? ': ' + this.message : '');
};

Cancel.prototype.__CANCEL__ = true;

module.exports = Cancel;


/***/ }),
/* 28 */
/***/ (function(module, exports) {

module.exports = ReactDOM;

/***/ }),
/* 29 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (immutable) */ __webpack_exports__["c"] = newInstance;
/* harmony export (immutable) */ __webpack_exports__["a"] = cancelInstance;
/* harmony export (immutable) */ __webpack_exports__["f"] = settingsOpen;
/* harmony export (immutable) */ __webpack_exports__["e"] = settingsClose;
/* harmony export (immutable) */ __webpack_exports__["d"] = saveInstance;
/* harmony export (immutable) */ __webpack_exports__["b"] = deleteInstance;
/* harmony export (immutable) */ __webpack_exports__["g"] = updateInstance;
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__messagesAction__ = __webpack_require__(63);


function newInstance() {
    return {
        type: 'SRIZON_MORTGAGE_SETTINGS_NEW_INSTANCE'
    };
}

function cancelInstance() {
    return {
        type: 'SRIZON_MORTGAGE_SETTINGS_CANCEL_INSTANCE'
    };
}

function settingsOpen(id) {
    return {
        type: 'SRIZON_MORTGAGE_INSTANCE_SETTINGS_OPEN',
        payload: id
    };
}
function settingsClose() {
    return {
        type: 'SRIZON_MORTGAGE_INSTANCE_SETTINGS_OPEN'
    };
}

function saveInstance(instanceData) {
    return function (dispatch) {
        dispatch({ type: 'SRIZON_MORTGAGE_SETTINGS_SAVING_INSTANCE' });
        axios.post(srzmortbase + 'instance', { title: instanceData.title }).then(function (response) {
            if (response.data.result == 'saved') {
                dispatch(Object(__WEBPACK_IMPORTED_MODULE_0__messagesAction__["h" /* successInstanceSaved */])());
                dispatch({ type: 'SRIZON_MORTGAGE_SETTINGS_SAVED_INSTANCE', payload: response.data.instances });
            } else {
                dispatch({ type: 'SRIZON_MORTGAGE_SETTINGS_CANCEL_INSTANCE' });
            }
        }).catch(function (error) {
            if (error.response) {
                dispatch(Object(__WEBPACK_IMPORTED_MODULE_0__messagesAction__["b" /* errorReceived */])(error));
                dispatch({ type: 'SRIZON_MORTGAGE_SETTINGS_NEW_INSTANCE' });
            } else if (error.request) {
                dispatch(Object(__WEBPACK_IMPORTED_MODULE_0__messagesAction__["c" /* errorRequesting */])(error));
                dispatch({ type: 'SRIZON_MORTGAGE_SETTINGS_NEW_INSTANCE' });
            }
        });
    };
}

function deleteInstance(id) {
    if (window.confirm('Are you sure?')) {
        return function (dispatch) {
            dispatch({ type: 'SRIZON_MORTGAGE_INSTANCE_DELETEING', payload: id });
            axios.delete(srzmortbase + 'instance/' + id).then(function (response) {
                if (response.data.result == 'deleted') {
                    dispatch(Object(__WEBPACK_IMPORTED_MODULE_0__messagesAction__["g" /* successInstanceDelete */])());
                    dispatch({ type: 'SRIZON_MORTGAGE_INSTANCE_DELETED', payload: response.data.instances });
                }
            }).catch(function () {
                dispatch(Object(__WEBPACK_IMPORTED_MODULE_0__messagesAction__["d" /* errorUnknown */])());
                dispatch({ type: 'ACTION_CANCELLED' });
            });
        };
    } else {
        return { type: 'ACTION_CANCELLED' };
    }
}

function updateInstance(id, settings) {
    return function (dispatch) {
        dispatch({ type: 'SRIZON_MORTGAGE_INSTANCE_UPDATING', payload: id });
        axios.post(srzmortbase + 'instance-settings', { id: id, settings: settings }).then(function (response) {
            if (response.data.result == 'updated') {
                dispatch(Object(__WEBPACK_IMPORTED_MODULE_0__messagesAction__["i" /* successInstanceUpdated */])());
                dispatch({ type: 'SRIZON_MORTGAGE_INSTANCE_UPDATED', payload: { id: id, instances: response.data.instances } });
            }
        }).catch(function () {
            dispatch(Object(__WEBPACK_IMPORTED_MODULE_0__messagesAction__["d" /* errorUnknown */])());
            dispatch({ type: 'ACTION_CANCELLED' });
        });
    };
}

/***/ }),
/* 30 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_react__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_react___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0_react__);


var CircularLoaderRow = function CircularLoaderRow() {
    return __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
        "div",
        null,
        __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
            "div",
            { className: "center top50" },
            __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                "div",
                { className: "preloader-wrapper big active" },
                __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                    "div",
                    { className: "spinner-layer spinner-blue" },
                    __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                        "div",
                        { className: "circle-clipper left" },
                        __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement("div", { className: "circle" })
                    ),
                    __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                        "div",
                        { className: "gap-patch" },
                        __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement("div", { className: "circle" })
                    ),
                    __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                        "div",
                        { className: "circle-clipper right" },
                        __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement("div", { className: "circle" })
                    )
                ),
                __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                    "div",
                    { className: "spinner-layer spinner-red" },
                    __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                        "div",
                        { className: "circle-clipper left" },
                        __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement("div", { className: "circle" })
                    ),
                    __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                        "div",
                        { className: "gap-patch" },
                        __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement("div", { className: "circle" })
                    ),
                    __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                        "div",
                        { className: "circle-clipper right" },
                        __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement("div", { className: "circle" })
                    )
                ),
                __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                    "div",
                    { className: "spinner-layer spinner-yellow" },
                    __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                        "div",
                        { className: "circle-clipper left" },
                        __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement("div", { className: "circle" })
                    ),
                    __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                        "div",
                        { className: "gap-patch" },
                        __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement("div", { className: "circle" })
                    ),
                    __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                        "div",
                        { className: "circle-clipper right" },
                        __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement("div", { className: "circle" })
                    )
                ),
                __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                    "div",
                    { className: "spinner-layer spinner-green" },
                    __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                        "div",
                        { className: "circle-clipper left" },
                        __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement("div", { className: "circle" })
                    ),
                    __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                        "div",
                        { className: "gap-patch" },
                        __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement("div", { className: "circle" })
                    ),
                    __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                        "div",
                        { className: "circle-clipper right" },
                        __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement("div", { className: "circle" })
                    )
                )
            )
        )
    );
};

/* harmony default export */ __webpack_exports__["a"] = (CircularLoaderRow);

/***/ }),
/* 31 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__Symbol_js__ = __webpack_require__(11);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__getRawTag_js__ = __webpack_require__(34);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__objectToString_js__ = __webpack_require__(35);




/** `Object#toString` result references. */
var nullTag = '[object Null]',
    undefinedTag = '[object Undefined]';

/** Built-in value references. */
var symToStringTag = __WEBPACK_IMPORTED_MODULE_0__Symbol_js__["a" /* default */] ? __WEBPACK_IMPORTED_MODULE_0__Symbol_js__["a" /* default */].toStringTag : undefined;

/**
 * The base implementation of `getTag` without fallbacks for buggy environments.
 *
 * @private
 * @param {*} value The value to query.
 * @returns {string} Returns the `toStringTag`.
 */
function baseGetTag(value) {
  if (value == null) {
    return value === undefined ? undefinedTag : nullTag;
  }
  return (symToStringTag && symToStringTag in Object(value))
    ? Object(__WEBPACK_IMPORTED_MODULE_1__getRawTag_js__["a" /* default */])(value)
    : Object(__WEBPACK_IMPORTED_MODULE_2__objectToString_js__["a" /* default */])(value);
}

/* harmony default export */ __webpack_exports__["a"] = (baseGetTag);


/***/ }),
/* 32 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__freeGlobal_js__ = __webpack_require__(33);


/** Detect free variable `self`. */
var freeSelf = typeof self == 'object' && self && self.Object === Object && self;

/** Used as a reference to the global object. */
var root = __WEBPACK_IMPORTED_MODULE_0__freeGlobal_js__["a" /* default */] || freeSelf || Function('return this')();

/* harmony default export */ __webpack_exports__["a"] = (root);


/***/ }),
/* 33 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* WEBPACK VAR INJECTION */(function(global) {/** Detect free variable `global` from Node.js. */
var freeGlobal = typeof global == 'object' && global && global.Object === Object && global;

/* harmony default export */ __webpack_exports__["a"] = (freeGlobal);

/* WEBPACK VAR INJECTION */}.call(__webpack_exports__, __webpack_require__(6)))

/***/ }),
/* 34 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__Symbol_js__ = __webpack_require__(11);


/** Used for built-in method references. */
var objectProto = Object.prototype;

/** Used to check objects for own properties. */
var hasOwnProperty = objectProto.hasOwnProperty;

/**
 * Used to resolve the
 * [`toStringTag`](http://ecma-international.org/ecma-262/7.0/#sec-object.prototype.tostring)
 * of values.
 */
var nativeObjectToString = objectProto.toString;

/** Built-in value references. */
var symToStringTag = __WEBPACK_IMPORTED_MODULE_0__Symbol_js__["a" /* default */] ? __WEBPACK_IMPORTED_MODULE_0__Symbol_js__["a" /* default */].toStringTag : undefined;

/**
 * A specialized version of `baseGetTag` which ignores `Symbol.toStringTag` values.
 *
 * @private
 * @param {*} value The value to query.
 * @returns {string} Returns the raw `toStringTag`.
 */
function getRawTag(value) {
  var isOwn = hasOwnProperty.call(value, symToStringTag),
      tag = value[symToStringTag];

  try {
    value[symToStringTag] = undefined;
    var unmasked = true;
  } catch (e) {}

  var result = nativeObjectToString.call(value);
  if (unmasked) {
    if (isOwn) {
      value[symToStringTag] = tag;
    } else {
      delete value[symToStringTag];
    }
  }
  return result;
}

/* harmony default export */ __webpack_exports__["a"] = (getRawTag);


/***/ }),
/* 35 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/** Used for built-in method references. */
var objectProto = Object.prototype;

/**
 * Used to resolve the
 * [`toStringTag`](http://ecma-international.org/ecma-262/7.0/#sec-object.prototype.tostring)
 * of values.
 */
var nativeObjectToString = objectProto.toString;

/**
 * Converts `value` to a string using `Object.prototype.toString`.
 *
 * @private
 * @param {*} value The value to convert.
 * @returns {string} Returns the converted string.
 */
function objectToString(value) {
  return nativeObjectToString.call(value);
}

/* harmony default export */ __webpack_exports__["a"] = (objectToString);


/***/ }),
/* 36 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__overArg_js__ = __webpack_require__(37);


/** Built-in value references. */
var getPrototype = Object(__WEBPACK_IMPORTED_MODULE_0__overArg_js__["a" /* default */])(Object.getPrototypeOf, Object);

/* harmony default export */ __webpack_exports__["a"] = (getPrototype);


/***/ }),
/* 37 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/**
 * Creates a unary function that invokes `func` with its argument transformed.
 *
 * @private
 * @param {Function} func The function to wrap.
 * @param {Function} transform The argument transform.
 * @returns {Function} Returns the new function.
 */
function overArg(func, transform) {
  return function(arg) {
    return func(transform(arg));
  };
}

/* harmony default export */ __webpack_exports__["a"] = (overArg);


/***/ }),
/* 38 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/**
 * Checks if `value` is object-like. A value is object-like if it's not `null`
 * and has a `typeof` result of "object".
 *
 * @static
 * @memberOf _
 * @since 4.0.0
 * @category Lang
 * @param {*} value The value to check.
 * @returns {boolean} Returns `true` if `value` is object-like, else `false`.
 * @example
 *
 * _.isObjectLike({});
 * // => true
 *
 * _.isObjectLike([1, 2, 3]);
 * // => true
 *
 * _.isObjectLike(_.noop);
 * // => false
 *
 * _.isObjectLike(null);
 * // => false
 */
function isObjectLike(value) {
  return value != null && typeof value == 'object';
}

/* harmony default export */ __webpack_exports__["a"] = (isObjectLike);


/***/ }),
/* 39 */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(40);


/***/ }),
/* 40 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/* WEBPACK VAR INJECTION */(function(global, module) {

Object.defineProperty(exports, "__esModule", {
  value: true
});

var _ponyfill = __webpack_require__(42);

var _ponyfill2 = _interopRequireDefault(_ponyfill);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { 'default': obj }; }

var root; /* global window */


if (typeof self !== 'undefined') {
  root = self;
} else if (typeof window !== 'undefined') {
  root = window;
} else if (typeof global !== 'undefined') {
  root = global;
} else if (true) {
  root = module;
} else {
  root = Function('return this')();
}

var result = (0, _ponyfill2['default'])(root);
exports['default'] = result;
/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(6), __webpack_require__(41)(module)))

/***/ }),
/* 41 */
/***/ (function(module, exports) {

module.exports = function(module) {
	if(!module.webpackPolyfill) {
		module.deprecate = function() {};
		module.paths = [];
		// module.parent = undefined by default
		if(!module.children) module.children = [];
		Object.defineProperty(module, "loaded", {
			enumerable: true,
			get: function() {
				return module.l;
			}
		});
		Object.defineProperty(module, "id", {
			enumerable: true,
			get: function() {
				return module.i;
			}
		});
		module.webpackPolyfill = 1;
	}
	return module;
};


/***/ }),
/* 42 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
	value: true
});
exports['default'] = symbolObservablePonyfill;
function symbolObservablePonyfill(root) {
	var result;
	var _Symbol = root.Symbol;

	if (typeof _Symbol === 'function') {
		if (_Symbol.observable) {
			result = _Symbol.observable;
		} else {
			result = _Symbol('observable');
			_Symbol.observable = result;
		}
	} else {
		result = '@@observable';
	}

	return result;
};

/***/ }),
/* 43 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (immutable) */ __webpack_exports__["a"] = combineReducers;
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__createStore__ = __webpack_require__(10);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_lodash_es_isPlainObject__ = __webpack_require__(5);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__utils_warning__ = __webpack_require__(12);




function getUndefinedStateErrorMessage(key, action) {
  var actionType = action && action.type;
  var actionName = actionType && '"' + actionType.toString() + '"' || 'an action';

  return 'Given action ' + actionName + ', reducer "' + key + '" returned undefined. ' + 'To ignore an action, you must explicitly return the previous state. ' + 'If you want this reducer to hold no value, you can return null instead of undefined.';
}

function getUnexpectedStateShapeWarningMessage(inputState, reducers, action, unexpectedKeyCache) {
  var reducerKeys = Object.keys(reducers);
  var argumentName = action && action.type === __WEBPACK_IMPORTED_MODULE_0__createStore__["a" /* ActionTypes */].INIT ? 'preloadedState argument passed to createStore' : 'previous state received by the reducer';

  if (reducerKeys.length === 0) {
    return 'Store does not have a valid reducer. Make sure the argument passed ' + 'to combineReducers is an object whose values are reducers.';
  }

  if (!Object(__WEBPACK_IMPORTED_MODULE_1_lodash_es_isPlainObject__["a" /* default */])(inputState)) {
    return 'The ' + argumentName + ' has unexpected type of "' + {}.toString.call(inputState).match(/\s([a-z|A-Z]+)/)[1] + '". Expected argument to be an object with the following ' + ('keys: "' + reducerKeys.join('", "') + '"');
  }

  var unexpectedKeys = Object.keys(inputState).filter(function (key) {
    return !reducers.hasOwnProperty(key) && !unexpectedKeyCache[key];
  });

  unexpectedKeys.forEach(function (key) {
    unexpectedKeyCache[key] = true;
  });

  if (unexpectedKeys.length > 0) {
    return 'Unexpected ' + (unexpectedKeys.length > 1 ? 'keys' : 'key') + ' ' + ('"' + unexpectedKeys.join('", "') + '" found in ' + argumentName + '. ') + 'Expected to find one of the known reducer keys instead: ' + ('"' + reducerKeys.join('", "') + '". Unexpected keys will be ignored.');
  }
}

function assertReducerShape(reducers) {
  Object.keys(reducers).forEach(function (key) {
    var reducer = reducers[key];
    var initialState = reducer(undefined, { type: __WEBPACK_IMPORTED_MODULE_0__createStore__["a" /* ActionTypes */].INIT });

    if (typeof initialState === 'undefined') {
      throw new Error('Reducer "' + key + '" returned undefined during initialization. ' + 'If the state passed to the reducer is undefined, you must ' + 'explicitly return the initial state. The initial state may ' + 'not be undefined. If you don\'t want to set a value for this reducer, ' + 'you can use null instead of undefined.');
    }

    var type = '@@redux/PROBE_UNKNOWN_ACTION_' + Math.random().toString(36).substring(7).split('').join('.');
    if (typeof reducer(undefined, { type: type }) === 'undefined') {
      throw new Error('Reducer "' + key + '" returned undefined when probed with a random type. ' + ('Don\'t try to handle ' + __WEBPACK_IMPORTED_MODULE_0__createStore__["a" /* ActionTypes */].INIT + ' or other actions in "redux/*" ') + 'namespace. They are considered private. Instead, you must return the ' + 'current state for any unknown actions, unless it is undefined, ' + 'in which case you must return the initial state, regardless of the ' + 'action type. The initial state may not be undefined, but can be null.');
    }
  });
}

/**
 * Turns an object whose values are different reducer functions, into a single
 * reducer function. It will call every child reducer, and gather their results
 * into a single state object, whose keys correspond to the keys of the passed
 * reducer functions.
 *
 * @param {Object} reducers An object whose values correspond to different
 * reducer functions that need to be combined into one. One handy way to obtain
 * it is to use ES6 `import * as reducers` syntax. The reducers may never return
 * undefined for any action. Instead, they should return their initial state
 * if the state passed to them was undefined, and the current state for any
 * unrecognized action.
 *
 * @returns {Function} A reducer function that invokes every reducer inside the
 * passed object, and builds a state object with the same shape.
 */
function combineReducers(reducers) {
  var reducerKeys = Object.keys(reducers);
  var finalReducers = {};
  for (var i = 0; i < reducerKeys.length; i++) {
    var key = reducerKeys[i];

    if (true) {
      if (typeof reducers[key] === 'undefined') {
        Object(__WEBPACK_IMPORTED_MODULE_2__utils_warning__["a" /* default */])('No reducer provided for key "' + key + '"');
      }
    }

    if (typeof reducers[key] === 'function') {
      finalReducers[key] = reducers[key];
    }
  }
  var finalReducerKeys = Object.keys(finalReducers);

  var unexpectedKeyCache = void 0;
  if (true) {
    unexpectedKeyCache = {};
  }

  var shapeAssertionError = void 0;
  try {
    assertReducerShape(finalReducers);
  } catch (e) {
    shapeAssertionError = e;
  }

  return function combination() {
    var state = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
    var action = arguments[1];

    if (shapeAssertionError) {
      throw shapeAssertionError;
    }

    if (true) {
      var warningMessage = getUnexpectedStateShapeWarningMessage(state, finalReducers, action, unexpectedKeyCache);
      if (warningMessage) {
        Object(__WEBPACK_IMPORTED_MODULE_2__utils_warning__["a" /* default */])(warningMessage);
      }
    }

    var hasChanged = false;
    var nextState = {};
    for (var _i = 0; _i < finalReducerKeys.length; _i++) {
      var _key = finalReducerKeys[_i];
      var reducer = finalReducers[_key];
      var previousStateForKey = state[_key];
      var nextStateForKey = reducer(previousStateForKey, action);
      if (typeof nextStateForKey === 'undefined') {
        var errorMessage = getUndefinedStateErrorMessage(_key, action);
        throw new Error(errorMessage);
      }
      nextState[_key] = nextStateForKey;
      hasChanged = hasChanged || nextStateForKey !== previousStateForKey;
    }
    return hasChanged ? nextState : state;
  };
}

/***/ }),
/* 44 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (immutable) */ __webpack_exports__["a"] = bindActionCreators;
function bindActionCreator(actionCreator, dispatch) {
  return function () {
    return dispatch(actionCreator.apply(undefined, arguments));
  };
}

/**
 * Turns an object whose values are action creators, into an object with the
 * same keys, but with every function wrapped into a `dispatch` call so they
 * may be invoked directly. This is just a convenience method, as you can call
 * `store.dispatch(MyActionCreators.doSomething())` yourself just fine.
 *
 * For convenience, you can also pass a single function as the first argument,
 * and get a function in return.
 *
 * @param {Function|Object} actionCreators An object whose values are action
 * creator functions. One handy way to obtain it is to use ES6 `import * as`
 * syntax. You may also pass a single function.
 *
 * @param {Function} dispatch The `dispatch` function available on your Redux
 * store.
 *
 * @returns {Function|Object} The object mimicking the original object, but with
 * every action creator wrapped into the `dispatch` call. If you passed a
 * function as `actionCreators`, the return value will also be a single
 * function.
 */
function bindActionCreators(actionCreators, dispatch) {
  if (typeof actionCreators === 'function') {
    return bindActionCreator(actionCreators, dispatch);
  }

  if (typeof actionCreators !== 'object' || actionCreators === null) {
    throw new Error('bindActionCreators expected an object or a function, instead received ' + (actionCreators === null ? 'null' : typeof actionCreators) + '. ' + 'Did you write "import ActionCreators from" instead of "import * as ActionCreators from"?');
  }

  var keys = Object.keys(actionCreators);
  var boundActionCreators = {};
  for (var i = 0; i < keys.length; i++) {
    var key = keys[i];
    var actionCreator = actionCreators[key];
    if (typeof actionCreator === 'function') {
      boundActionCreators[key] = bindActionCreator(actionCreator, dispatch);
    }
  }
  return boundActionCreators;
}

/***/ }),
/* 45 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (immutable) */ __webpack_exports__["a"] = applyMiddleware;
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__compose__ = __webpack_require__(13);
var _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; };



/**
 * Creates a store enhancer that applies middleware to the dispatch method
 * of the Redux store. This is handy for a variety of tasks, such as expressing
 * asynchronous actions in a concise manner, or logging every action payload.
 *
 * See `redux-thunk` package as an example of the Redux middleware.
 *
 * Because middleware is potentially asynchronous, this should be the first
 * store enhancer in the composition chain.
 *
 * Note that each middleware will be given the `dispatch` and `getState` functions
 * as named arguments.
 *
 * @param {...Function} middlewares The middleware chain to be applied.
 * @returns {Function} A store enhancer applying the middleware.
 */
function applyMiddleware() {
  for (var _len = arguments.length, middlewares = Array(_len), _key = 0; _key < _len; _key++) {
    middlewares[_key] = arguments[_key];
  }

  return function (createStore) {
    return function (reducer, preloadedState, enhancer) {
      var store = createStore(reducer, preloadedState, enhancer);
      var _dispatch = store.dispatch;
      var chain = [];

      var middlewareAPI = {
        getState: store.getState,
        dispatch: function dispatch(action) {
          return _dispatch(action);
        }
      };
      chain = middlewares.map(function (middleware) {
        return middleware(middlewareAPI);
      });
      _dispatch = __WEBPACK_IMPORTED_MODULE_0__compose__["a" /* default */].apply(undefined, chain)(store.dispatch);

      return _extends({}, store, {
        dispatch: _dispatch
      });
    };
  };
}

/***/ }),
/* 46 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


exports.__esModule = true;
function createThunkMiddleware(extraArgument) {
  return function (_ref) {
    var dispatch = _ref.dispatch,
        getState = _ref.getState;
    return function (next) {
      return function (action) {
        if (typeof action === 'function') {
          return action(dispatch, getState, extraArgument);
        }

        return next(action);
      };
    };
  };
}

var thunk = createThunkMiddleware();
thunk.withExtraArgument = createThunkMiddleware;

exports['default'] = thunk;

/***/ }),
/* 47 */
/***/ (function(module, exports, __webpack_require__) {

/* WEBPACK VAR INJECTION */(function(global) {!function(e,t){ true?t(exports):"function"==typeof define&&define.amd?define(["exports"],t):t(e.reduxLogger=e.reduxLogger||{})}(this,function(e){"use strict";function t(e,t){e.super_=t,e.prototype=Object.create(t.prototype,{constructor:{value:e,enumerable:!1,writable:!0,configurable:!0}})}function r(e,t){Object.defineProperty(this,"kind",{value:e,enumerable:!0}),t&&t.length&&Object.defineProperty(this,"path",{value:t,enumerable:!0})}function n(e,t,r){n.super_.call(this,"E",e),Object.defineProperty(this,"lhs",{value:t,enumerable:!0}),Object.defineProperty(this,"rhs",{value:r,enumerable:!0})}function o(e,t){o.super_.call(this,"N",e),Object.defineProperty(this,"rhs",{value:t,enumerable:!0})}function i(e,t){i.super_.call(this,"D",e),Object.defineProperty(this,"lhs",{value:t,enumerable:!0})}function a(e,t,r){a.super_.call(this,"A",e),Object.defineProperty(this,"index",{value:t,enumerable:!0}),Object.defineProperty(this,"item",{value:r,enumerable:!0})}function f(e,t,r){var n=e.slice((r||t)+1||e.length);return e.length=t<0?e.length+t:t,e.push.apply(e,n),e}function u(e){var t="undefined"==typeof e?"undefined":N(e);return"object"!==t?t:e===Math?"math":null===e?"null":Array.isArray(e)?"array":"[object Date]"===Object.prototype.toString.call(e)?"date":"function"==typeof e.toString&&/^\/.*\//.test(e.toString())?"regexp":"object"}function l(e,t,r,c,s,d,p){s=s||[],p=p||[];var g=s.slice(0);if("undefined"!=typeof d){if(c){if("function"==typeof c&&c(g,d))return;if("object"===("undefined"==typeof c?"undefined":N(c))){if(c.prefilter&&c.prefilter(g,d))return;if(c.normalize){var h=c.normalize(g,d,e,t);h&&(e=h[0],t=h[1])}}}g.push(d)}"regexp"===u(e)&&"regexp"===u(t)&&(e=e.toString(),t=t.toString());var y="undefined"==typeof e?"undefined":N(e),v="undefined"==typeof t?"undefined":N(t),b="undefined"!==y||p&&p[p.length-1].lhs&&p[p.length-1].lhs.hasOwnProperty(d),m="undefined"!==v||p&&p[p.length-1].rhs&&p[p.length-1].rhs.hasOwnProperty(d);if(!b&&m)r(new o(g,t));else if(!m&&b)r(new i(g,e));else if(u(e)!==u(t))r(new n(g,e,t));else if("date"===u(e)&&e-t!==0)r(new n(g,e,t));else if("object"===y&&null!==e&&null!==t)if(p.filter(function(t){return t.lhs===e}).length)e!==t&&r(new n(g,e,t));else{if(p.push({lhs:e,rhs:t}),Array.isArray(e)){var w;e.length;for(w=0;w<e.length;w++)w>=t.length?r(new a(g,w,new i(void 0,e[w]))):l(e[w],t[w],r,c,g,w,p);for(;w<t.length;)r(new a(g,w,new o(void 0,t[w++])))}else{var x=Object.keys(e),S=Object.keys(t);x.forEach(function(n,o){var i=S.indexOf(n);i>=0?(l(e[n],t[n],r,c,g,n,p),S=f(S,i)):l(e[n],void 0,r,c,g,n,p)}),S.forEach(function(e){l(void 0,t[e],r,c,g,e,p)})}p.length=p.length-1}else e!==t&&("number"===y&&isNaN(e)&&isNaN(t)||r(new n(g,e,t)))}function c(e,t,r,n){return n=n||[],l(e,t,function(e){e&&n.push(e)},r),n.length?n:void 0}function s(e,t,r){if(r.path&&r.path.length){var n,o=e[t],i=r.path.length-1;for(n=0;n<i;n++)o=o[r.path[n]];switch(r.kind){case"A":s(o[r.path[n]],r.index,r.item);break;case"D":delete o[r.path[n]];break;case"E":case"N":o[r.path[n]]=r.rhs}}else switch(r.kind){case"A":s(e[t],r.index,r.item);break;case"D":e=f(e,t);break;case"E":case"N":e[t]=r.rhs}return e}function d(e,t,r){if(e&&t&&r&&r.kind){for(var n=e,o=-1,i=r.path?r.path.length-1:0;++o<i;)"undefined"==typeof n[r.path[o]]&&(n[r.path[o]]="number"==typeof r.path[o]?[]:{}),n=n[r.path[o]];switch(r.kind){case"A":s(r.path?n[r.path[o]]:n,r.index,r.item);break;case"D":delete n[r.path[o]];break;case"E":case"N":n[r.path[o]]=r.rhs}}}function p(e,t,r){if(r.path&&r.path.length){var n,o=e[t],i=r.path.length-1;for(n=0;n<i;n++)o=o[r.path[n]];switch(r.kind){case"A":p(o[r.path[n]],r.index,r.item);break;case"D":o[r.path[n]]=r.lhs;break;case"E":o[r.path[n]]=r.lhs;break;case"N":delete o[r.path[n]]}}else switch(r.kind){case"A":p(e[t],r.index,r.item);break;case"D":e[t]=r.lhs;break;case"E":e[t]=r.lhs;break;case"N":e=f(e,t)}return e}function g(e,t,r){if(e&&t&&r&&r.kind){var n,o,i=e;for(o=r.path.length-1,n=0;n<o;n++)"undefined"==typeof i[r.path[n]]&&(i[r.path[n]]={}),i=i[r.path[n]];switch(r.kind){case"A":p(i[r.path[n]],r.index,r.item);break;case"D":i[r.path[n]]=r.lhs;break;case"E":i[r.path[n]]=r.lhs;break;case"N":delete i[r.path[n]]}}}function h(e,t,r){if(e&&t){var n=function(n){r&&!r(e,t,n)||d(e,t,n)};l(e,t,n)}}function y(e){return"color: "+F[e].color+"; font-weight: bold"}function v(e){var t=e.kind,r=e.path,n=e.lhs,o=e.rhs,i=e.index,a=e.item;switch(t){case"E":return[r.join("."),n,"→",o];case"N":return[r.join("."),o];case"D":return[r.join(".")];case"A":return[r.join(".")+"["+i+"]",a];default:return[]}}function b(e,t,r,n){var o=c(e,t);try{n?r.groupCollapsed("diff"):r.group("diff")}catch(e){r.log("diff")}o?o.forEach(function(e){var t=e.kind,n=v(e);r.log.apply(r,["%c "+F[t].text,y(t)].concat(P(n)))}):r.log("—— no diff ——");try{r.groupEnd()}catch(e){r.log("—— diff end —— ")}}function m(e,t,r,n){switch("undefined"==typeof e?"undefined":N(e)){case"object":return"function"==typeof e[n]?e[n].apply(e,P(r)):e[n];case"function":return e(t);default:return e}}function w(e){var t=e.timestamp,r=e.duration;return function(e,n,o){var i=["action"];return i.push("%c"+String(e.type)),t&&i.push("%c@ "+n),r&&i.push("%c(in "+o.toFixed(2)+" ms)"),i.join(" ")}}function x(e,t){var r=t.logger,n=t.actionTransformer,o=t.titleFormatter,i=void 0===o?w(t):o,a=t.collapsed,f=t.colors,u=t.level,l=t.diff,c="undefined"==typeof t.titleFormatter;e.forEach(function(o,s){var d=o.started,p=o.startedTime,g=o.action,h=o.prevState,y=o.error,v=o.took,w=o.nextState,x=e[s+1];x&&(w=x.prevState,v=x.started-d);var S=n(g),k="function"==typeof a?a(function(){return w},g,o):a,j=D(p),E=f.title?"color: "+f.title(S)+";":"",A=["color: gray; font-weight: lighter;"];A.push(E),t.timestamp&&A.push("color: gray; font-weight: lighter;"),t.duration&&A.push("color: gray; font-weight: lighter;");var O=i(S,j,v);try{k?f.title&&c?r.groupCollapsed.apply(r,["%c "+O].concat(A)):r.groupCollapsed(O):f.title&&c?r.group.apply(r,["%c "+O].concat(A)):r.group(O)}catch(e){r.log(O)}var N=m(u,S,[h],"prevState"),P=m(u,S,[S],"action"),C=m(u,S,[y,h],"error"),F=m(u,S,[w],"nextState");if(N)if(f.prevState){var L="color: "+f.prevState(h)+"; font-weight: bold";r[N]("%c prev state",L,h)}else r[N]("prev state",h);if(P)if(f.action){var T="color: "+f.action(S)+"; font-weight: bold";r[P]("%c action    ",T,S)}else r[P]("action    ",S);if(y&&C)if(f.error){var M="color: "+f.error(y,h)+"; font-weight: bold;";r[C]("%c error     ",M,y)}else r[C]("error     ",y);if(F)if(f.nextState){var _="color: "+f.nextState(w)+"; font-weight: bold";r[F]("%c next state",_,w)}else r[F]("next state",w);l&&b(h,w,r,k);try{r.groupEnd()}catch(e){r.log("—— log end ——")}})}function S(){var e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:{},t=Object.assign({},L,e),r=t.logger,n=t.stateTransformer,o=t.errorTransformer,i=t.predicate,a=t.logErrors,f=t.diffPredicate;if("undefined"==typeof r)return function(){return function(e){return function(t){return e(t)}}};if(e.getState&&e.dispatch)return console.error("[redux-logger] redux-logger not installed. Make sure to pass logger instance as middleware:\n// Logger with default options\nimport { logger } from 'redux-logger'\nconst store = createStore(\n  reducer,\n  applyMiddleware(logger)\n)\n// Or you can create your own logger with custom options http://bit.ly/redux-logger-options\nimport createLogger from 'redux-logger'\nconst logger = createLogger({\n  // ...options\n});\nconst store = createStore(\n  reducer,\n  applyMiddleware(logger)\n)\n"),function(){return function(e){return function(t){return e(t)}}};var u=[];return function(e){var r=e.getState;return function(e){return function(l){if("function"==typeof i&&!i(r,l))return e(l);var c={};u.push(c),c.started=O.now(),c.startedTime=new Date,c.prevState=n(r()),c.action=l;var s=void 0;if(a)try{s=e(l)}catch(e){c.error=o(e)}else s=e(l);c.took=O.now()-c.started,c.nextState=n(r());var d=t.diff&&"function"==typeof f?f(r,l):t.diff;if(x(u,Object.assign({},t,{diff:d})),u.length=0,c.error)throw c.error;return s}}}}var k,j,E=function(e,t){return new Array(t+1).join(e)},A=function(e,t){return E("0",t-e.toString().length)+e},D=function(e){return A(e.getHours(),2)+":"+A(e.getMinutes(),2)+":"+A(e.getSeconds(),2)+"."+A(e.getMilliseconds(),3)},O="undefined"!=typeof performance&&null!==performance&&"function"==typeof performance.now?performance:Date,N="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e},P=function(e){if(Array.isArray(e)){for(var t=0,r=Array(e.length);t<e.length;t++)r[t]=e[t];return r}return Array.from(e)},C=[];k="object"===("undefined"==typeof global?"undefined":N(global))&&global?global:"undefined"!=typeof window?window:{},j=k.DeepDiff,j&&C.push(function(){"undefined"!=typeof j&&k.DeepDiff===c&&(k.DeepDiff=j,j=void 0)}),t(n,r),t(o,r),t(i,r),t(a,r),Object.defineProperties(c,{diff:{value:c,enumerable:!0},observableDiff:{value:l,enumerable:!0},applyDiff:{value:h,enumerable:!0},applyChange:{value:d,enumerable:!0},revertChange:{value:g,enumerable:!0},isConflict:{value:function(){return"undefined"!=typeof j},enumerable:!0},noConflict:{value:function(){return C&&(C.forEach(function(e){e()}),C=null),c},enumerable:!0}});var F={E:{color:"#2196F3",text:"CHANGED:"},N:{color:"#4CAF50",text:"ADDED:"},D:{color:"#F44336",text:"DELETED:"},A:{color:"#2196F3",text:"ARRAY:"}},L={level:"log",logger:console,logErrors:!0,collapsed:void 0,predicate:void 0,duration:!1,timestamp:!0,stateTransformer:function(e){return e},actionTransformer:function(e){return e},errorTransformer:function(e){return e},colors:{title:function(){return"inherit"},prevState:function(){return"#9E9E9E"},action:function(){return"#03A9F4"},nextState:function(){return"#4CAF50"},error:function(){return"#F20404"}},diff:!1,diffPredicate:void 0,transformer:void 0},T=function(){var e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:{},t=e.dispatch,r=e.getState;return"function"==typeof t||"function"==typeof r?S()({dispatch:t,getState:r}):void console.error("\n[redux-logger v3] BREAKING CHANGE\n[redux-logger v3] Since 3.0.0 redux-logger exports by default logger with default settings.\n[redux-logger v3] Change\n[redux-logger v3] import createLogger from 'redux-logger'\n[redux-logger v3] to\n[redux-logger v3] import { createLogger } from 'redux-logger'\n")};e.defaults=L,e.createLogger=S,e.logger=T,e.default=T,Object.defineProperty(e,"__esModule",{value:!0})});

/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(6)))

/***/ }),
/* 48 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var compose = __webpack_require__(4).compose;

exports.__esModule = true;
exports.composeWithDevTools = (
  typeof window !== 'undefined' && window.__REDUX_DEVTOOLS_EXTENSION_COMPOSE__ ?
    window.__REDUX_DEVTOOLS_EXTENSION_COMPOSE__ :
    function() {
      if (arguments.length === 0) return undefined;
      if (typeof arguments[0] === 'object') return compose;
      return compose.apply(null, arguments);
    }
);

exports.devToolsEnhancer = (
  typeof window !== 'undefined' && window.__REDUX_DEVTOOLS_EXTENSION__ ?
    window.__REDUX_DEVTOOLS_EXTENSION__ :
    function() { return function(noop) { return noop; } }
);


/***/ }),
/* 49 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* unused harmony export createProvider */
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_react__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_react___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0_react__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_prop_types__ = __webpack_require__(14);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_prop_types___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_1_prop_types__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__utils_PropTypes__ = __webpack_require__(19);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3__utils_warning__ = __webpack_require__(7);
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }






var didWarnAboutReceivingStore = false;
function warnAboutReceivingStore() {
  if (didWarnAboutReceivingStore) {
    return;
  }
  didWarnAboutReceivingStore = true;

  Object(__WEBPACK_IMPORTED_MODULE_3__utils_warning__["a" /* default */])('<Provider> does not support changing `store` on the fly. ' + 'It is most likely that you see this error because you updated to ' + 'Redux 2.x and React Redux 2.x which no longer hot reload reducers ' + 'automatically. See https://github.com/reactjs/react-redux/releases/' + 'tag/v2.0.0 for the migration instructions.');
}

function createProvider() {
  var _Provider$childContex;

  var storeKey = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : 'store';
  var subKey = arguments[1];

  var subscriptionKey = subKey || storeKey + 'Subscription';

  var Provider = function (_Component) {
    _inherits(Provider, _Component);

    Provider.prototype.getChildContext = function getChildContext() {
      var _ref;

      return _ref = {}, _ref[storeKey] = this[storeKey], _ref[subscriptionKey] = null, _ref;
    };

    function Provider(props, context) {
      _classCallCheck(this, Provider);

      var _this = _possibleConstructorReturn(this, _Component.call(this, props, context));

      _this[storeKey] = props.store;
      return _this;
    }

    Provider.prototype.render = function render() {
      return __WEBPACK_IMPORTED_MODULE_0_react__["Children"].only(this.props.children);
    };

    return Provider;
  }(__WEBPACK_IMPORTED_MODULE_0_react__["Component"]);

  if (true) {
    Provider.prototype.componentWillReceiveProps = function (nextProps) {
      if (this[storeKey] !== nextProps.store) {
        warnAboutReceivingStore();
      }
    };
  }

  Provider.propTypes = {
    store: __WEBPACK_IMPORTED_MODULE_2__utils_PropTypes__["a" /* storeShape */].isRequired,
    children: __WEBPACK_IMPORTED_MODULE_1_prop_types___default.a.element.isRequired
  };
  Provider.childContextTypes = (_Provider$childContex = {}, _Provider$childContex[storeKey] = __WEBPACK_IMPORTED_MODULE_2__utils_PropTypes__["a" /* storeShape */].isRequired, _Provider$childContex[subscriptionKey] = __WEBPACK_IMPORTED_MODULE_2__utils_PropTypes__["b" /* subscriptionShape */], _Provider$childContex);

  return Provider;
}

/* harmony default export */ __webpack_exports__["a"] = (createProvider());

/***/ }),
/* 50 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/**
 * Copyright 2013-present, Facebook, Inc.
 * All rights reserved.
 *
 * This source code is licensed under the BSD-style license found in the
 * LICENSE file in the root directory of this source tree. An additional grant
 * of patent rights can be found in the PATENTS file in the same directory.
 */



var emptyFunction = __webpack_require__(15);
var invariant = __webpack_require__(16);
var warning = __webpack_require__(17);

var ReactPropTypesSecret = __webpack_require__(18);
var checkPropTypes = __webpack_require__(51);

module.exports = function(isValidElement, throwOnDirectAccess) {
  /* global Symbol */
  var ITERATOR_SYMBOL = typeof Symbol === 'function' && Symbol.iterator;
  var FAUX_ITERATOR_SYMBOL = '@@iterator'; // Before Symbol spec.

  /**
   * Returns the iterator method function contained on the iterable object.
   *
   * Be sure to invoke the function with the iterable as context:
   *
   *     var iteratorFn = getIteratorFn(myIterable);
   *     if (iteratorFn) {
   *       var iterator = iteratorFn.call(myIterable);
   *       ...
   *     }
   *
   * @param {?object} maybeIterable
   * @return {?function}
   */
  function getIteratorFn(maybeIterable) {
    var iteratorFn = maybeIterable && (ITERATOR_SYMBOL && maybeIterable[ITERATOR_SYMBOL] || maybeIterable[FAUX_ITERATOR_SYMBOL]);
    if (typeof iteratorFn === 'function') {
      return iteratorFn;
    }
  }

  /**
   * Collection of methods that allow declaration and validation of props that are
   * supplied to React components. Example usage:
   *
   *   var Props = require('ReactPropTypes');
   *   var MyArticle = React.createClass({
   *     propTypes: {
   *       // An optional string prop named "description".
   *       description: Props.string,
   *
   *       // A required enum prop named "category".
   *       category: Props.oneOf(['News','Photos']).isRequired,
   *
   *       // A prop named "dialog" that requires an instance of Dialog.
   *       dialog: Props.instanceOf(Dialog).isRequired
   *     },
   *     render: function() { ... }
   *   });
   *
   * A more formal specification of how these methods are used:
   *
   *   type := array|bool|func|object|number|string|oneOf([...])|instanceOf(...)
   *   decl := ReactPropTypes.{type}(.isRequired)?
   *
   * Each and every declaration produces a function with the same signature. This
   * allows the creation of custom validation functions. For example:
   *
   *  var MyLink = React.createClass({
   *    propTypes: {
   *      // An optional string or URI prop named "href".
   *      href: function(props, propName, componentName) {
   *        var propValue = props[propName];
   *        if (propValue != null && typeof propValue !== 'string' &&
   *            !(propValue instanceof URI)) {
   *          return new Error(
   *            'Expected a string or an URI for ' + propName + ' in ' +
   *            componentName
   *          );
   *        }
   *      }
   *    },
   *    render: function() {...}
   *  });
   *
   * @internal
   */

  var ANONYMOUS = '<<anonymous>>';

  // Important!
  // Keep this list in sync with production version in `./factoryWithThrowingShims.js`.
  var ReactPropTypes = {
    array: createPrimitiveTypeChecker('array'),
    bool: createPrimitiveTypeChecker('boolean'),
    func: createPrimitiveTypeChecker('function'),
    number: createPrimitiveTypeChecker('number'),
    object: createPrimitiveTypeChecker('object'),
    string: createPrimitiveTypeChecker('string'),
    symbol: createPrimitiveTypeChecker('symbol'),

    any: createAnyTypeChecker(),
    arrayOf: createArrayOfTypeChecker,
    element: createElementTypeChecker(),
    instanceOf: createInstanceTypeChecker,
    node: createNodeChecker(),
    objectOf: createObjectOfTypeChecker,
    oneOf: createEnumTypeChecker,
    oneOfType: createUnionTypeChecker,
    shape: createShapeTypeChecker
  };

  /**
   * inlined Object.is polyfill to avoid requiring consumers ship their own
   * https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Object/is
   */
  /*eslint-disable no-self-compare*/
  function is(x, y) {
    // SameValue algorithm
    if (x === y) {
      // Steps 1-5, 7-10
      // Steps 6.b-6.e: +0 != -0
      return x !== 0 || 1 / x === 1 / y;
    } else {
      // Step 6.a: NaN == NaN
      return x !== x && y !== y;
    }
  }
  /*eslint-enable no-self-compare*/

  /**
   * We use an Error-like object for backward compatibility as people may call
   * PropTypes directly and inspect their output. However, we don't use real
   * Errors anymore. We don't inspect their stack anyway, and creating them
   * is prohibitively expensive if they are created too often, such as what
   * happens in oneOfType() for any type before the one that matched.
   */
  function PropTypeError(message) {
    this.message = message;
    this.stack = '';
  }
  // Make `instanceof Error` still work for returned errors.
  PropTypeError.prototype = Error.prototype;

  function createChainableTypeChecker(validate) {
    if (true) {
      var manualPropTypeCallCache = {};
      var manualPropTypeWarningCount = 0;
    }
    function checkType(isRequired, props, propName, componentName, location, propFullName, secret) {
      componentName = componentName || ANONYMOUS;
      propFullName = propFullName || propName;

      if (secret !== ReactPropTypesSecret) {
        if (throwOnDirectAccess) {
          // New behavior only for users of `prop-types` package
          invariant(
            false,
            'Calling PropTypes validators directly is not supported by the `prop-types` package. ' +
            'Use `PropTypes.checkPropTypes()` to call them. ' +
            'Read more at http://fb.me/use-check-prop-types'
          );
        } else if ("development" !== 'production' && typeof console !== 'undefined') {
          // Old behavior for people using React.PropTypes
          var cacheKey = componentName + ':' + propName;
          if (
            !manualPropTypeCallCache[cacheKey] &&
            // Avoid spamming the console because they are often not actionable except for lib authors
            manualPropTypeWarningCount < 3
          ) {
            warning(
              false,
              'You are manually calling a React.PropTypes validation ' +
              'function for the `%s` prop on `%s`. This is deprecated ' +
              'and will throw in the standalone `prop-types` package. ' +
              'You may be seeing this warning due to a third-party PropTypes ' +
              'library. See https://fb.me/react-warning-dont-call-proptypes ' + 'for details.',
              propFullName,
              componentName
            );
            manualPropTypeCallCache[cacheKey] = true;
            manualPropTypeWarningCount++;
          }
        }
      }
      if (props[propName] == null) {
        if (isRequired) {
          if (props[propName] === null) {
            return new PropTypeError('The ' + location + ' `' + propFullName + '` is marked as required ' + ('in `' + componentName + '`, but its value is `null`.'));
          }
          return new PropTypeError('The ' + location + ' `' + propFullName + '` is marked as required in ' + ('`' + componentName + '`, but its value is `undefined`.'));
        }
        return null;
      } else {
        return validate(props, propName, componentName, location, propFullName);
      }
    }

    var chainedCheckType = checkType.bind(null, false);
    chainedCheckType.isRequired = checkType.bind(null, true);

    return chainedCheckType;
  }

  function createPrimitiveTypeChecker(expectedType) {
    function validate(props, propName, componentName, location, propFullName, secret) {
      var propValue = props[propName];
      var propType = getPropType(propValue);
      if (propType !== expectedType) {
        // `propValue` being instance of, say, date/regexp, pass the 'object'
        // check, but we can offer a more precise error message here rather than
        // 'of type `object`'.
        var preciseType = getPreciseType(propValue);

        return new PropTypeError('Invalid ' + location + ' `' + propFullName + '` of type ' + ('`' + preciseType + '` supplied to `' + componentName + '`, expected ') + ('`' + expectedType + '`.'));
      }
      return null;
    }
    return createChainableTypeChecker(validate);
  }

  function createAnyTypeChecker() {
    return createChainableTypeChecker(emptyFunction.thatReturnsNull);
  }

  function createArrayOfTypeChecker(typeChecker) {
    function validate(props, propName, componentName, location, propFullName) {
      if (typeof typeChecker !== 'function') {
        return new PropTypeError('Property `' + propFullName + '` of component `' + componentName + '` has invalid PropType notation inside arrayOf.');
      }
      var propValue = props[propName];
      if (!Array.isArray(propValue)) {
        var propType = getPropType(propValue);
        return new PropTypeError('Invalid ' + location + ' `' + propFullName + '` of type ' + ('`' + propType + '` supplied to `' + componentName + '`, expected an array.'));
      }
      for (var i = 0; i < propValue.length; i++) {
        var error = typeChecker(propValue, i, componentName, location, propFullName + '[' + i + ']', ReactPropTypesSecret);
        if (error instanceof Error) {
          return error;
        }
      }
      return null;
    }
    return createChainableTypeChecker(validate);
  }

  function createElementTypeChecker() {
    function validate(props, propName, componentName, location, propFullName) {
      var propValue = props[propName];
      if (!isValidElement(propValue)) {
        var propType = getPropType(propValue);
        return new PropTypeError('Invalid ' + location + ' `' + propFullName + '` of type ' + ('`' + propType + '` supplied to `' + componentName + '`, expected a single ReactElement.'));
      }
      return null;
    }
    return createChainableTypeChecker(validate);
  }

  function createInstanceTypeChecker(expectedClass) {
    function validate(props, propName, componentName, location, propFullName) {
      if (!(props[propName] instanceof expectedClass)) {
        var expectedClassName = expectedClass.name || ANONYMOUS;
        var actualClassName = getClassName(props[propName]);
        return new PropTypeError('Invalid ' + location + ' `' + propFullName + '` of type ' + ('`' + actualClassName + '` supplied to `' + componentName + '`, expected ') + ('instance of `' + expectedClassName + '`.'));
      }
      return null;
    }
    return createChainableTypeChecker(validate);
  }

  function createEnumTypeChecker(expectedValues) {
    if (!Array.isArray(expectedValues)) {
       true ? warning(false, 'Invalid argument supplied to oneOf, expected an instance of array.') : void 0;
      return emptyFunction.thatReturnsNull;
    }

    function validate(props, propName, componentName, location, propFullName) {
      var propValue = props[propName];
      for (var i = 0; i < expectedValues.length; i++) {
        if (is(propValue, expectedValues[i])) {
          return null;
        }
      }

      var valuesString = JSON.stringify(expectedValues);
      return new PropTypeError('Invalid ' + location + ' `' + propFullName + '` of value `' + propValue + '` ' + ('supplied to `' + componentName + '`, expected one of ' + valuesString + '.'));
    }
    return createChainableTypeChecker(validate);
  }

  function createObjectOfTypeChecker(typeChecker) {
    function validate(props, propName, componentName, location, propFullName) {
      if (typeof typeChecker !== 'function') {
        return new PropTypeError('Property `' + propFullName + '` of component `' + componentName + '` has invalid PropType notation inside objectOf.');
      }
      var propValue = props[propName];
      var propType = getPropType(propValue);
      if (propType !== 'object') {
        return new PropTypeError('Invalid ' + location + ' `' + propFullName + '` of type ' + ('`' + propType + '` supplied to `' + componentName + '`, expected an object.'));
      }
      for (var key in propValue) {
        if (propValue.hasOwnProperty(key)) {
          var error = typeChecker(propValue, key, componentName, location, propFullName + '.' + key, ReactPropTypesSecret);
          if (error instanceof Error) {
            return error;
          }
        }
      }
      return null;
    }
    return createChainableTypeChecker(validate);
  }

  function createUnionTypeChecker(arrayOfTypeCheckers) {
    if (!Array.isArray(arrayOfTypeCheckers)) {
       true ? warning(false, 'Invalid argument supplied to oneOfType, expected an instance of array.') : void 0;
      return emptyFunction.thatReturnsNull;
    }

    for (var i = 0; i < arrayOfTypeCheckers.length; i++) {
      var checker = arrayOfTypeCheckers[i];
      if (typeof checker !== 'function') {
        warning(
          false,
          'Invalid argument supplid to oneOfType. Expected an array of check functions, but ' +
          'received %s at index %s.',
          getPostfixForTypeWarning(checker),
          i
        );
        return emptyFunction.thatReturnsNull;
      }
    }

    function validate(props, propName, componentName, location, propFullName) {
      for (var i = 0; i < arrayOfTypeCheckers.length; i++) {
        var checker = arrayOfTypeCheckers[i];
        if (checker(props, propName, componentName, location, propFullName, ReactPropTypesSecret) == null) {
          return null;
        }
      }

      return new PropTypeError('Invalid ' + location + ' `' + propFullName + '` supplied to ' + ('`' + componentName + '`.'));
    }
    return createChainableTypeChecker(validate);
  }

  function createNodeChecker() {
    function validate(props, propName, componentName, location, propFullName) {
      if (!isNode(props[propName])) {
        return new PropTypeError('Invalid ' + location + ' `' + propFullName + '` supplied to ' + ('`' + componentName + '`, expected a ReactNode.'));
      }
      return null;
    }
    return createChainableTypeChecker(validate);
  }

  function createShapeTypeChecker(shapeTypes) {
    function validate(props, propName, componentName, location, propFullName) {
      var propValue = props[propName];
      var propType = getPropType(propValue);
      if (propType !== 'object') {
        return new PropTypeError('Invalid ' + location + ' `' + propFullName + '` of type `' + propType + '` ' + ('supplied to `' + componentName + '`, expected `object`.'));
      }
      for (var key in shapeTypes) {
        var checker = shapeTypes[key];
        if (!checker) {
          continue;
        }
        var error = checker(propValue, key, componentName, location, propFullName + '.' + key, ReactPropTypesSecret);
        if (error) {
          return error;
        }
      }
      return null;
    }
    return createChainableTypeChecker(validate);
  }

  function isNode(propValue) {
    switch (typeof propValue) {
      case 'number':
      case 'string':
      case 'undefined':
        return true;
      case 'boolean':
        return !propValue;
      case 'object':
        if (Array.isArray(propValue)) {
          return propValue.every(isNode);
        }
        if (propValue === null || isValidElement(propValue)) {
          return true;
        }

        var iteratorFn = getIteratorFn(propValue);
        if (iteratorFn) {
          var iterator = iteratorFn.call(propValue);
          var step;
          if (iteratorFn !== propValue.entries) {
            while (!(step = iterator.next()).done) {
              if (!isNode(step.value)) {
                return false;
              }
            }
          } else {
            // Iterator will provide entry [k,v] tuples rather than values.
            while (!(step = iterator.next()).done) {
              var entry = step.value;
              if (entry) {
                if (!isNode(entry[1])) {
                  return false;
                }
              }
            }
          }
        } else {
          return false;
        }

        return true;
      default:
        return false;
    }
  }

  function isSymbol(propType, propValue) {
    // Native Symbol.
    if (propType === 'symbol') {
      return true;
    }

    // 19.4.3.5 Symbol.prototype[@@toStringTag] === 'Symbol'
    if (propValue['@@toStringTag'] === 'Symbol') {
      return true;
    }

    // Fallback for non-spec compliant Symbols which are polyfilled.
    if (typeof Symbol === 'function' && propValue instanceof Symbol) {
      return true;
    }

    return false;
  }

  // Equivalent of `typeof` but with special handling for array and regexp.
  function getPropType(propValue) {
    var propType = typeof propValue;
    if (Array.isArray(propValue)) {
      return 'array';
    }
    if (propValue instanceof RegExp) {
      // Old webkits (at least until Android 4.0) return 'function' rather than
      // 'object' for typeof a RegExp. We'll normalize this here so that /bla/
      // passes PropTypes.object.
      return 'object';
    }
    if (isSymbol(propType, propValue)) {
      return 'symbol';
    }
    return propType;
  }

  // This handles more types than `getPropType`. Only used for error messages.
  // See `createPrimitiveTypeChecker`.
  function getPreciseType(propValue) {
    if (typeof propValue === 'undefined' || propValue === null) {
      return '' + propValue;
    }
    var propType = getPropType(propValue);
    if (propType === 'object') {
      if (propValue instanceof Date) {
        return 'date';
      } else if (propValue instanceof RegExp) {
        return 'regexp';
      }
    }
    return propType;
  }

  // Returns a string that is postfixed to a warning about an invalid type.
  // For example, "undefined" or "of type array"
  function getPostfixForTypeWarning(value) {
    var type = getPreciseType(value);
    switch (type) {
      case 'array':
      case 'object':
        return 'an ' + type;
      case 'boolean':
      case 'date':
      case 'regexp':
        return 'a ' + type;
      default:
        return type;
    }
  }

  // Returns class name of the object, if any.
  function getClassName(propValue) {
    if (!propValue.constructor || !propValue.constructor.name) {
      return ANONYMOUS;
    }
    return propValue.constructor.name;
  }

  ReactPropTypes.checkPropTypes = checkPropTypes;
  ReactPropTypes.PropTypes = ReactPropTypes;

  return ReactPropTypes;
};


/***/ }),
/* 51 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/**
 * Copyright 2013-present, Facebook, Inc.
 * All rights reserved.
 *
 * This source code is licensed under the BSD-style license found in the
 * LICENSE file in the root directory of this source tree. An additional grant
 * of patent rights can be found in the PATENTS file in the same directory.
 */



if (true) {
  var invariant = __webpack_require__(16);
  var warning = __webpack_require__(17);
  var ReactPropTypesSecret = __webpack_require__(18);
  var loggedTypeFailures = {};
}

/**
 * Assert that the values match with the type specs.
 * Error messages are memorized and will only be shown once.
 *
 * @param {object} typeSpecs Map of name to a ReactPropType
 * @param {object} values Runtime values that need to be type-checked
 * @param {string} location e.g. "prop", "context", "child context"
 * @param {string} componentName Name of the component for error messages.
 * @param {?Function} getStack Returns the component stack.
 * @private
 */
function checkPropTypes(typeSpecs, values, location, componentName, getStack) {
  if (true) {
    for (var typeSpecName in typeSpecs) {
      if (typeSpecs.hasOwnProperty(typeSpecName)) {
        var error;
        // Prop type validation may throw. In case they do, we don't want to
        // fail the render phase where it didn't fail before. So we log it.
        // After these have been cleaned up, we'll let them throw.
        try {
          // This is intentionally an invariant that gets caught. It's the same
          // behavior as without this statement except with a better message.
          invariant(typeof typeSpecs[typeSpecName] === 'function', '%s: %s type `%s` is invalid; it must be a function, usually from ' + 'React.PropTypes.', componentName || 'React class', location, typeSpecName);
          error = typeSpecs[typeSpecName](values, typeSpecName, componentName, location, null, ReactPropTypesSecret);
        } catch (ex) {
          error = ex;
        }
        warning(!error || error instanceof Error, '%s: type specification of %s `%s` is invalid; the type checker ' + 'function must return `null` or an `Error` but returned a %s. ' + 'You may have forgotten to pass an argument to the type checker ' + 'creator (arrayOf, instanceOf, objectOf, oneOf, oneOfType, and ' + 'shape all require an argument).', componentName || 'React class', location, typeSpecName, typeof error);
        if (error instanceof Error && !(error.message in loggedTypeFailures)) {
          // Only monitor this failure once because there tends to be a lot of the
          // same error.
          loggedTypeFailures[error.message] = true;

          var stack = getStack ? getStack() : '';

          warning(false, 'Failed %s type: %s%s', location, error.message, stack != null ? stack : '');
        }
      }
    }
  }
}

module.exports = checkPropTypes;


/***/ }),
/* 52 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/**
 * Copyright 2015, Yahoo! Inc.
 * Copyrights licensed under the New BSD License. See the accompanying LICENSE file for terms.
 */


var REACT_STATICS = {
    childContextTypes: true,
    contextTypes: true,
    defaultProps: true,
    displayName: true,
    getDefaultProps: true,
    mixins: true,
    propTypes: true,
    type: true
};

var KNOWN_STATICS = {
  name: true,
  length: true,
  prototype: true,
  caller: true,
  callee: true,
  arguments: true,
  arity: true
};

var defineProperty = Object.defineProperty;
var getOwnPropertyNames = Object.getOwnPropertyNames;
var getOwnPropertySymbols = Object.getOwnPropertySymbols;
var getOwnPropertyDescriptor = Object.getOwnPropertyDescriptor;
var getPrototypeOf = Object.getPrototypeOf;
var objectPrototype = getPrototypeOf && getPrototypeOf(Object);

module.exports = function hoistNonReactStatics(targetComponent, sourceComponent, blacklist) {
    if (typeof sourceComponent !== 'string') { // don't hoist over string (html) components

        if (objectPrototype) {
            var inheritedComponent = getPrototypeOf(sourceComponent);
            if (inheritedComponent && inheritedComponent !== objectPrototype) {
                hoistNonReactStatics(targetComponent, inheritedComponent, blacklist);
            }
        }

        var keys = getOwnPropertyNames(sourceComponent);

        if (getOwnPropertySymbols) {
            keys = keys.concat(getOwnPropertySymbols(sourceComponent));
        }

        for (var i = 0; i < keys.length; ++i) {
            var key = keys[i];
            if (!REACT_STATICS[key] && !KNOWN_STATICS[key] && (!blacklist || !blacklist[key])) {
                var descriptor = getOwnPropertyDescriptor(sourceComponent, key);
                try { // Avoid failures from read-only properties
                    defineProperty(targetComponent, key, descriptor);
                } catch (e) {}
            }
        }

        return targetComponent;
    }

    return targetComponent;
};


/***/ }),
/* 53 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
/**
 * Copyright 2013-2015, Facebook, Inc.
 * All rights reserved.
 *
 * This source code is licensed under the BSD-style license found in the
 * LICENSE file in the root directory of this source tree. An additional grant
 * of patent rights can be found in the PATENTS file in the same directory.
 */



/**
 * Use invariant() to assert state which your program assumes to be true.
 *
 * Provide sprintf-style format (only %s is supported) and arguments
 * to provide information about what broke and what you were
 * expecting.
 *
 * The invariant message will be stripped in production, but the invariant
 * will remain to ensure logic does not differ in production.
 */

var invariant = function(condition, format, a, b, c, d, e, f) {
  if (true) {
    if (format === undefined) {
      throw new Error('invariant requires an error message argument');
    }
  }

  if (!condition) {
    var error;
    if (format === undefined) {
      error = new Error(
        'Minified exception occurred; use the non-minified dev environment ' +
        'for the full error message and additional helpful warnings.'
      );
    } else {
      var args = [a, b, c, d, e, f];
      var argIndex = 0;
      error = new Error(
        format.replace(/%s/g, function() { return args[argIndex++]; })
      );
      error.name = 'Invariant Violation';
    }

    error.framesToPop = 1; // we don't care about invariant's own frame
    throw error;
  }
};

module.exports = invariant;


/***/ }),
/* 54 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return Subscription; });
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

// encapsulates the subscription logic for connecting a component to the redux store, as
// well as nesting subscriptions of descendant components, so that we can ensure the
// ancestor components re-render before descendants

var CLEARED = null;
var nullListeners = {
  notify: function notify() {}
};

function createListenerCollection() {
  // the current/next pattern is copied from redux's createStore code.
  // TODO: refactor+expose that code to be reusable here?
  var current = [];
  var next = [];

  return {
    clear: function clear() {
      next = CLEARED;
      current = CLEARED;
    },
    notify: function notify() {
      var listeners = current = next;
      for (var i = 0; i < listeners.length; i++) {
        listeners[i]();
      }
    },
    get: function get() {
      return next;
    },
    subscribe: function subscribe(listener) {
      var isSubscribed = true;
      if (next === current) next = current.slice();
      next.push(listener);

      return function unsubscribe() {
        if (!isSubscribed || current === CLEARED) return;
        isSubscribed = false;

        if (next === current) next = current.slice();
        next.splice(next.indexOf(listener), 1);
      };
    }
  };
}

var Subscription = function () {
  function Subscription(store, parentSub, onStateChange) {
    _classCallCheck(this, Subscription);

    this.store = store;
    this.parentSub = parentSub;
    this.onStateChange = onStateChange;
    this.unsubscribe = null;
    this.listeners = nullListeners;
  }

  Subscription.prototype.addNestedSub = function addNestedSub(listener) {
    this.trySubscribe();
    return this.listeners.subscribe(listener);
  };

  Subscription.prototype.notifyNestedSubs = function notifyNestedSubs() {
    this.listeners.notify();
  };

  Subscription.prototype.isSubscribed = function isSubscribed() {
    return Boolean(this.unsubscribe);
  };

  Subscription.prototype.trySubscribe = function trySubscribe() {
    if (!this.unsubscribe) {
      this.unsubscribe = this.parentSub ? this.parentSub.addNestedSub(this.onStateChange) : this.store.subscribe(this.onStateChange);

      this.listeners = createListenerCollection();
    }
  };

  Subscription.prototype.tryUnsubscribe = function tryUnsubscribe() {
    if (this.unsubscribe) {
      this.unsubscribe();
      this.unsubscribe = null;
      this.listeners.clear();
      this.listeners = nullListeners;
    }
  };

  return Subscription;
}();



/***/ }),
/* 55 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* unused harmony export createConnect */
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__components_connectAdvanced__ = __webpack_require__(20);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__utils_shallowEqual__ = __webpack_require__(56);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__mapDispatchToProps__ = __webpack_require__(57);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3__mapStateToProps__ = __webpack_require__(58);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_4__mergeProps__ = __webpack_require__(59);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_5__selectorFactory__ = __webpack_require__(60);
var _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; };

function _objectWithoutProperties(obj, keys) { var target = {}; for (var i in obj) { if (keys.indexOf(i) >= 0) continue; if (!Object.prototype.hasOwnProperty.call(obj, i)) continue; target[i] = obj[i]; } return target; }








/*
  connect is a facade over connectAdvanced. It turns its args into a compatible
  selectorFactory, which has the signature:

    (dispatch, options) => (nextState, nextOwnProps) => nextFinalProps
  
  connect passes its args to connectAdvanced as options, which will in turn pass them to
  selectorFactory each time a Connect component instance is instantiated or hot reloaded.

  selectorFactory returns a final props selector from its mapStateToProps,
  mapStateToPropsFactories, mapDispatchToProps, mapDispatchToPropsFactories, mergeProps,
  mergePropsFactories, and pure args.

  The resulting final props selector is called by the Connect component instance whenever
  it receives new props or store state.
 */

function match(arg, factories, name) {
  for (var i = factories.length - 1; i >= 0; i--) {
    var result = factories[i](arg);
    if (result) return result;
  }

  return function (dispatch, options) {
    throw new Error('Invalid value of type ' + typeof arg + ' for ' + name + ' argument when connecting component ' + options.wrappedComponentName + '.');
  };
}

function strictEqual(a, b) {
  return a === b;
}

// createConnect with default args builds the 'official' connect behavior. Calling it with
// different options opens up some testing and extensibility scenarios
function createConnect() {
  var _ref = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {},
      _ref$connectHOC = _ref.connectHOC,
      connectHOC = _ref$connectHOC === undefined ? __WEBPACK_IMPORTED_MODULE_0__components_connectAdvanced__["a" /* default */] : _ref$connectHOC,
      _ref$mapStateToPropsF = _ref.mapStateToPropsFactories,
      mapStateToPropsFactories = _ref$mapStateToPropsF === undefined ? __WEBPACK_IMPORTED_MODULE_3__mapStateToProps__["a" /* default */] : _ref$mapStateToPropsF,
      _ref$mapDispatchToPro = _ref.mapDispatchToPropsFactories,
      mapDispatchToPropsFactories = _ref$mapDispatchToPro === undefined ? __WEBPACK_IMPORTED_MODULE_2__mapDispatchToProps__["a" /* default */] : _ref$mapDispatchToPro,
      _ref$mergePropsFactor = _ref.mergePropsFactories,
      mergePropsFactories = _ref$mergePropsFactor === undefined ? __WEBPACK_IMPORTED_MODULE_4__mergeProps__["a" /* default */] : _ref$mergePropsFactor,
      _ref$selectorFactory = _ref.selectorFactory,
      selectorFactory = _ref$selectorFactory === undefined ? __WEBPACK_IMPORTED_MODULE_5__selectorFactory__["a" /* default */] : _ref$selectorFactory;

  return function connect(mapStateToProps, mapDispatchToProps, mergeProps) {
    var _ref2 = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : {},
        _ref2$pure = _ref2.pure,
        pure = _ref2$pure === undefined ? true : _ref2$pure,
        _ref2$areStatesEqual = _ref2.areStatesEqual,
        areStatesEqual = _ref2$areStatesEqual === undefined ? strictEqual : _ref2$areStatesEqual,
        _ref2$areOwnPropsEqua = _ref2.areOwnPropsEqual,
        areOwnPropsEqual = _ref2$areOwnPropsEqua === undefined ? __WEBPACK_IMPORTED_MODULE_1__utils_shallowEqual__["a" /* default */] : _ref2$areOwnPropsEqua,
        _ref2$areStatePropsEq = _ref2.areStatePropsEqual,
        areStatePropsEqual = _ref2$areStatePropsEq === undefined ? __WEBPACK_IMPORTED_MODULE_1__utils_shallowEqual__["a" /* default */] : _ref2$areStatePropsEq,
        _ref2$areMergedPropsE = _ref2.areMergedPropsEqual,
        areMergedPropsEqual = _ref2$areMergedPropsE === undefined ? __WEBPACK_IMPORTED_MODULE_1__utils_shallowEqual__["a" /* default */] : _ref2$areMergedPropsE,
        extraOptions = _objectWithoutProperties(_ref2, ['pure', 'areStatesEqual', 'areOwnPropsEqual', 'areStatePropsEqual', 'areMergedPropsEqual']);

    var initMapStateToProps = match(mapStateToProps, mapStateToPropsFactories, 'mapStateToProps');
    var initMapDispatchToProps = match(mapDispatchToProps, mapDispatchToPropsFactories, 'mapDispatchToProps');
    var initMergeProps = match(mergeProps, mergePropsFactories, 'mergeProps');

    return connectHOC(selectorFactory, _extends({
      // used in error messages
      methodName: 'connect',

      // used to compute Connect's displayName from the wrapped component's displayName.
      getDisplayName: function getDisplayName(name) {
        return 'Connect(' + name + ')';
      },

      // if mapStateToProps is falsy, the Connect component doesn't subscribe to store state changes
      shouldHandleStateChanges: Boolean(mapStateToProps),

      // passed through to selectorFactory
      initMapStateToProps: initMapStateToProps,
      initMapDispatchToProps: initMapDispatchToProps,
      initMergeProps: initMergeProps,
      pure: pure,
      areStatesEqual: areStatesEqual,
      areOwnPropsEqual: areOwnPropsEqual,
      areStatePropsEqual: areStatePropsEqual,
      areMergedPropsEqual: areMergedPropsEqual

    }, extraOptions));
  };
}

/* harmony default export */ __webpack_exports__["a"] = (createConnect());

/***/ }),
/* 56 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (immutable) */ __webpack_exports__["a"] = shallowEqual;
var hasOwn = Object.prototype.hasOwnProperty;

function is(x, y) {
  if (x === y) {
    return x !== 0 || y !== 0 || 1 / x === 1 / y;
  } else {
    return x !== x && y !== y;
  }
}

function shallowEqual(objA, objB) {
  if (is(objA, objB)) return true;

  if (typeof objA !== 'object' || objA === null || typeof objB !== 'object' || objB === null) {
    return false;
  }

  var keysA = Object.keys(objA);
  var keysB = Object.keys(objB);

  if (keysA.length !== keysB.length) return false;

  for (var i = 0; i < keysA.length; i++) {
    if (!hasOwn.call(objB, keysA[i]) || !is(objA[keysA[i]], objB[keysA[i]])) {
      return false;
    }
  }

  return true;
}

/***/ }),
/* 57 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* unused harmony export whenMapDispatchToPropsIsFunction */
/* unused harmony export whenMapDispatchToPropsIsMissing */
/* unused harmony export whenMapDispatchToPropsIsObject */
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_redux__ = __webpack_require__(4);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__wrapMapToProps__ = __webpack_require__(21);



function whenMapDispatchToPropsIsFunction(mapDispatchToProps) {
  return typeof mapDispatchToProps === 'function' ? Object(__WEBPACK_IMPORTED_MODULE_1__wrapMapToProps__["b" /* wrapMapToPropsFunc */])(mapDispatchToProps, 'mapDispatchToProps') : undefined;
}

function whenMapDispatchToPropsIsMissing(mapDispatchToProps) {
  return !mapDispatchToProps ? Object(__WEBPACK_IMPORTED_MODULE_1__wrapMapToProps__["a" /* wrapMapToPropsConstant */])(function (dispatch) {
    return { dispatch: dispatch };
  }) : undefined;
}

function whenMapDispatchToPropsIsObject(mapDispatchToProps) {
  return mapDispatchToProps && typeof mapDispatchToProps === 'object' ? Object(__WEBPACK_IMPORTED_MODULE_1__wrapMapToProps__["a" /* wrapMapToPropsConstant */])(function (dispatch) {
    return Object(__WEBPACK_IMPORTED_MODULE_0_redux__["bindActionCreators"])(mapDispatchToProps, dispatch);
  }) : undefined;
}

/* harmony default export */ __webpack_exports__["a"] = ([whenMapDispatchToPropsIsFunction, whenMapDispatchToPropsIsMissing, whenMapDispatchToPropsIsObject]);

/***/ }),
/* 58 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* unused harmony export whenMapStateToPropsIsFunction */
/* unused harmony export whenMapStateToPropsIsMissing */
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__wrapMapToProps__ = __webpack_require__(21);


function whenMapStateToPropsIsFunction(mapStateToProps) {
  return typeof mapStateToProps === 'function' ? Object(__WEBPACK_IMPORTED_MODULE_0__wrapMapToProps__["b" /* wrapMapToPropsFunc */])(mapStateToProps, 'mapStateToProps') : undefined;
}

function whenMapStateToPropsIsMissing(mapStateToProps) {
  return !mapStateToProps ? Object(__WEBPACK_IMPORTED_MODULE_0__wrapMapToProps__["a" /* wrapMapToPropsConstant */])(function () {
    return {};
  }) : undefined;
}

/* harmony default export */ __webpack_exports__["a"] = ([whenMapStateToPropsIsFunction, whenMapStateToPropsIsMissing]);

/***/ }),
/* 59 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* unused harmony export defaultMergeProps */
/* unused harmony export wrapMergePropsFunc */
/* unused harmony export whenMergePropsIsFunction */
/* unused harmony export whenMergePropsIsOmitted */
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__utils_verifyPlainObject__ = __webpack_require__(22);
var _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; };



function defaultMergeProps(stateProps, dispatchProps, ownProps) {
  return _extends({}, ownProps, stateProps, dispatchProps);
}

function wrapMergePropsFunc(mergeProps) {
  return function initMergePropsProxy(dispatch, _ref) {
    var displayName = _ref.displayName,
        pure = _ref.pure,
        areMergedPropsEqual = _ref.areMergedPropsEqual;

    var hasRunOnce = false;
    var mergedProps = void 0;

    return function mergePropsProxy(stateProps, dispatchProps, ownProps) {
      var nextMergedProps = mergeProps(stateProps, dispatchProps, ownProps);

      if (hasRunOnce) {
        if (!pure || !areMergedPropsEqual(nextMergedProps, mergedProps)) mergedProps = nextMergedProps;
      } else {
        hasRunOnce = true;
        mergedProps = nextMergedProps;

        if (true) Object(__WEBPACK_IMPORTED_MODULE_0__utils_verifyPlainObject__["a" /* default */])(mergedProps, displayName, 'mergeProps');
      }

      return mergedProps;
    };
  };
}

function whenMergePropsIsFunction(mergeProps) {
  return typeof mergeProps === 'function' ? wrapMergePropsFunc(mergeProps) : undefined;
}

function whenMergePropsIsOmitted(mergeProps) {
  return !mergeProps ? function () {
    return defaultMergeProps;
  } : undefined;
}

/* harmony default export */ __webpack_exports__["a"] = ([whenMergePropsIsFunction, whenMergePropsIsOmitted]);

/***/ }),
/* 60 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* unused harmony export impureFinalPropsSelectorFactory */
/* unused harmony export pureFinalPropsSelectorFactory */
/* harmony export (immutable) */ __webpack_exports__["a"] = finalPropsSelectorFactory;
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__verifySubselectors__ = __webpack_require__(61);
function _objectWithoutProperties(obj, keys) { var target = {}; for (var i in obj) { if (keys.indexOf(i) >= 0) continue; if (!Object.prototype.hasOwnProperty.call(obj, i)) continue; target[i] = obj[i]; } return target; }



function impureFinalPropsSelectorFactory(mapStateToProps, mapDispatchToProps, mergeProps, dispatch) {
  return function impureFinalPropsSelector(state, ownProps) {
    return mergeProps(mapStateToProps(state, ownProps), mapDispatchToProps(dispatch, ownProps), ownProps);
  };
}

function pureFinalPropsSelectorFactory(mapStateToProps, mapDispatchToProps, mergeProps, dispatch, _ref) {
  var areStatesEqual = _ref.areStatesEqual,
      areOwnPropsEqual = _ref.areOwnPropsEqual,
      areStatePropsEqual = _ref.areStatePropsEqual;

  var hasRunAtLeastOnce = false;
  var state = void 0;
  var ownProps = void 0;
  var stateProps = void 0;
  var dispatchProps = void 0;
  var mergedProps = void 0;

  function handleFirstCall(firstState, firstOwnProps) {
    state = firstState;
    ownProps = firstOwnProps;
    stateProps = mapStateToProps(state, ownProps);
    dispatchProps = mapDispatchToProps(dispatch, ownProps);
    mergedProps = mergeProps(stateProps, dispatchProps, ownProps);
    hasRunAtLeastOnce = true;
    return mergedProps;
  }

  function handleNewPropsAndNewState() {
    stateProps = mapStateToProps(state, ownProps);

    if (mapDispatchToProps.dependsOnOwnProps) dispatchProps = mapDispatchToProps(dispatch, ownProps);

    mergedProps = mergeProps(stateProps, dispatchProps, ownProps);
    return mergedProps;
  }

  function handleNewProps() {
    if (mapStateToProps.dependsOnOwnProps) stateProps = mapStateToProps(state, ownProps);

    if (mapDispatchToProps.dependsOnOwnProps) dispatchProps = mapDispatchToProps(dispatch, ownProps);

    mergedProps = mergeProps(stateProps, dispatchProps, ownProps);
    return mergedProps;
  }

  function handleNewState() {
    var nextStateProps = mapStateToProps(state, ownProps);
    var statePropsChanged = !areStatePropsEqual(nextStateProps, stateProps);
    stateProps = nextStateProps;

    if (statePropsChanged) mergedProps = mergeProps(stateProps, dispatchProps, ownProps);

    return mergedProps;
  }

  function handleSubsequentCalls(nextState, nextOwnProps) {
    var propsChanged = !areOwnPropsEqual(nextOwnProps, ownProps);
    var stateChanged = !areStatesEqual(nextState, state);
    state = nextState;
    ownProps = nextOwnProps;

    if (propsChanged && stateChanged) return handleNewPropsAndNewState();
    if (propsChanged) return handleNewProps();
    if (stateChanged) return handleNewState();
    return mergedProps;
  }

  return function pureFinalPropsSelector(nextState, nextOwnProps) {
    return hasRunAtLeastOnce ? handleSubsequentCalls(nextState, nextOwnProps) : handleFirstCall(nextState, nextOwnProps);
  };
}

// TODO: Add more comments

// If pure is true, the selector returned by selectorFactory will memoize its results,
// allowing connectAdvanced's shouldComponentUpdate to return false if final
// props have not changed. If false, the selector will always return a new
// object and shouldComponentUpdate will always return true.

function finalPropsSelectorFactory(dispatch, _ref2) {
  var initMapStateToProps = _ref2.initMapStateToProps,
      initMapDispatchToProps = _ref2.initMapDispatchToProps,
      initMergeProps = _ref2.initMergeProps,
      options = _objectWithoutProperties(_ref2, ['initMapStateToProps', 'initMapDispatchToProps', 'initMergeProps']);

  var mapStateToProps = initMapStateToProps(dispatch, options);
  var mapDispatchToProps = initMapDispatchToProps(dispatch, options);
  var mergeProps = initMergeProps(dispatch, options);

  if (true) {
    Object(__WEBPACK_IMPORTED_MODULE_0__verifySubselectors__["a" /* default */])(mapStateToProps, mapDispatchToProps, mergeProps, options.displayName);
  }

  var selectorFactory = options.pure ? pureFinalPropsSelectorFactory : impureFinalPropsSelectorFactory;

  return selectorFactory(mapStateToProps, mapDispatchToProps, mergeProps, dispatch, options);
}

/***/ }),
/* 61 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (immutable) */ __webpack_exports__["a"] = verifySubselectors;
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__utils_warning__ = __webpack_require__(7);


function verify(selector, methodName, displayName) {
  if (!selector) {
    throw new Error('Unexpected value for ' + methodName + ' in ' + displayName + '.');
  } else if (methodName === 'mapStateToProps' || methodName === 'mapDispatchToProps') {
    if (!selector.hasOwnProperty('dependsOnOwnProps')) {
      Object(__WEBPACK_IMPORTED_MODULE_0__utils_warning__["a" /* default */])('The selector for ' + methodName + ' of ' + displayName + ' did not specify a value for dependsOnOwnProps.');
    }
  }
}

function verifySubselectors(mapStateToProps, mapDispatchToProps, mergeProps, displayName) {
  verify(mapStateToProps, 'mapStateToProps', displayName);
  verify(mapDispatchToProps, 'mapDispatchToProps', displayName);
  verify(mergeProps, 'mergeProps', displayName);
}

/***/ }),
/* 62 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (immutable) */ __webpack_exports__["a"] = loadSettings;
/* unused harmony export loadAlbums */
/* harmony export (immutable) */ __webpack_exports__["c"] = toggleSettingsPanel;
/* harmony export (immutable) */ __webpack_exports__["b"] = saveGlobalSettings;
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__messagesAction__ = __webpack_require__(63);


function loadSettings() {
    return function (dispatch) {
        axios.get(srzmortbase + 'settings').then(function (response) {
            dispatch({
                type: 'SRIZON_MORTGAGE_SETTINGS_RECEIVED',
                payload: response.data
            });
            dispatch(loadAlbums());
        });
    };
}

function loadAlbums() {
    return function (dispatch) {
        axios.get(srzmortbase + 'instance').then(function (response) {
            dispatch({
                type: 'SRIZON_MORTGAGE_INSTANCES_RECEIVED',
                payload: response.data
            });
        }).catch(function (error) {
            if (error.response) {
                dispatch(Object(__WEBPACK_IMPORTED_MODULE_0__messagesAction__["b" /* errorReceived */])(error));
            } else if (error.request) {
                dispatch(Object(__WEBPACK_IMPORTED_MODULE_0__messagesAction__["c" /* errorRequesting */])(error));
            }
        });
    };
}

function toggleSettingsPanel() {
    return {
        type: 'SRIZON_MORTGAGE_SETTINGS_TOGGLE_SETTINGS_PANEL'
    };
}

function saveGlobalSettings(settings) {
    return function (dispatch) {
        dispatch({ type: 'SRIZON_MORTGAGE_SETTINGS_SAVING_GLOBAL' });
        axios.post(srzmortbase + 'save-global-settings', settings).then(function (response) {
            console.log(response.data);
            if (response.data.result == 'saved') {
                dispatch(Object(__WEBPACK_IMPORTED_MODULE_0__messagesAction__["f" /* successGlobalSettingsSaved */])());
                dispatch({ type: 'SRIZON_MORTGAGE_SETTINGS_SAVED_GLOBAL', payload: response.data.data });
            } else {
                dispatch(Object(__WEBPACK_IMPORTED_MODULE_0__messagesAction__["d" /* errorUnknown */])());
                dispatch({ type: 'SRIZON_MORTGAGE_SETTINGS_SAVING_ERROR_GLOBAL' });
            }
        }).catch(function (error) {
            if (error.response) {
                dispatch(Object(__WEBPACK_IMPORTED_MODULE_0__messagesAction__["b" /* errorReceived */])(error));
                dispatch({ type: 'SRIZON_MORTGAGE_SETTINGS_SAVING_ERROR_GLOBAL' });
            } else if (error.request) {
                dispatch(Object(__WEBPACK_IMPORTED_MODULE_0__messagesAction__["c" /* errorRequesting */])(error));
                dispatch({ type: 'SRIZON_MORTGAGE_SETTINGS_SAVING_ERROR_GLOBAL' });
            }
        });
    };
}

/***/ }),
/* 63 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (immutable) */ __webpack_exports__["h"] = successInstanceSaved;
/* harmony export (immutable) */ __webpack_exports__["g"] = successInstanceDelete;
/* harmony export (immutable) */ __webpack_exports__["f"] = successGlobalSettingsSaved;
/* harmony export (immutable) */ __webpack_exports__["b"] = errorReceived;
/* harmony export (immutable) */ __webpack_exports__["c"] = errorRequesting;
/* harmony export (immutable) */ __webpack_exports__["e"] = successCopy;
/* harmony export (immutable) */ __webpack_exports__["a"] = errorCopy;
/* harmony export (immutable) */ __webpack_exports__["d"] = errorUnknown;
/* harmony export (immutable) */ __webpack_exports__["i"] = successInstanceUpdated;
function successInstanceSaved() {
    return {
        type: 'SRIZON_MORTGAGE_MESSAGE_RECEIVED',
        payload: {
            txt: 'Instance Saved!',
            type: 'success',
            expire_in: 3
        }

    };
}

function successInstanceDelete() {
    return {
        type: 'SRIZON_MORTGAGE_MESSAGE_RECEIVED',
        payload: {
            txt: 'Instance Deleted!',
            type: 'success',
            expire_in: 3
        }

    };
}

function successGlobalSettingsSaved() {
    return {
        type: 'SRIZON_MORTGAGE_MESSAGE_RECEIVED',
        payload: {
            txt: 'Global Settings Saved! These will be used for new instances unless you override them',
            type: 'success',
            expire_in: 3
        }

    };
}

function errorReceived(error) {
    return {
        type: 'SRIZON_MORTGAGE_MESSAGE_RECEIVED',
        payload: {
            txt: error.response.data.message,
            type: 'error',
            expire_in: 5
        }
    };
}

function errorRequesting(error) {
    return {
        type: 'SRIZON_MORTGAGE_MESSAGE_RECEIVED',
        payload: {
            txt: 'Error occurred while connecting to server',
            type: 'error',
            expire_in: 5
        }
    };
}

function successCopy() {
    return {
        type: 'SRIZON_MORTGAGE_MESSAGE_RECEIVED',
        payload: {
            txt: 'Successfully Copied... Now paste it on a Page or Post',
            type: 'success',
            expire_in: 5
        }

    };
}

function errorCopy() {
    return {
        type: 'SRIZON_MORTGAGE_MESSAGE_RECEIVED',
        payload: {
            txt: 'Couldn\'t Select and Copy. Try to Select and Copy manually!',
            type: 'error',
            expire_in: 5
        }

    };
}
function errorUnknown() {
    return {
        type: 'SRIZON_MORTGAGE_MESSAGE_RECEIVED',
        payload: {
            txt: 'Something went wrong. Please try again',
            type: 'error',
            expire_in: 5
        }

    };
}

function successInstanceUpdated() {
    return {
        type: 'SRIZON_MORTGAGE_MESSAGE_RECEIVED',
        payload: {
            txt: 'Instance updated successfully',
            type: 'success',
            expire_in: 5
        }
    };
}

/***/ }),
/* 64 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
  value: true
});

var _FlipMove = __webpack_require__(98);

var _FlipMove2 = _interopRequireDefault(_FlipMove);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

exports.default = _FlipMove2.default;
/**
 * React Flip Move
 * (c) 2016-present Joshua Comeau
 */

module.exports = exports['default'];

/***/ }),
/* 65 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
  value: true
});
exports.omit = omit;
exports.arraysEqual = arraysEqual;
var isElementAnSFC = exports.isElementAnSFC = function isElementAnSFC(element) {
  var isNativeDOMElement = typeof element.type === 'string';

  if (isNativeDOMElement) {
    return false;
  }

  return !element.type.prototype.isReactComponent;
};
function omit(obj) {
  var attrs = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : [];

  var result = {};
  Object.keys(obj).forEach(function (key) {
    if (attrs.indexOf(key) === -1) {
      result[key] = obj[key];
    }
  });
  return result;
}

function arraysEqual(a, b) {
  var sameObject = a === b;
  if (sameObject) {
    return true;
  }

  var notBothArrays = !Array.isArray(a) || !Array.isArray(b);
  var differentLengths = a.length !== b.length;

  if (notBothArrays || differentLengths) {
    return false;
  }

  return a.every(function (element, index) {
    return element === b[index];
  });
}

function memoizeString(fn) {
  var cache = {};

  return function (str) {
    if (!cache[str]) {
      cache[str] = fn(str);
    }
    return cache[str];
  };
}

var hyphenate = exports.hyphenate = memoizeString(function (str) {
  return str.replace(/([A-Z])/g, '-$1').toLowerCase();
});

/***/ }),
/* 66 */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(67);

/***/ }),
/* 67 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var utils = __webpack_require__(1);
var bind = __webpack_require__(23);
var Axios = __webpack_require__(69);
var defaults = __webpack_require__(9);

/**
 * Create an instance of Axios
 *
 * @param {Object} defaultConfig The default config for the instance
 * @return {Axios} A new instance of Axios
 */
function createInstance(defaultConfig) {
  var context = new Axios(defaultConfig);
  var instance = bind(Axios.prototype.request, context);

  // Copy axios.prototype to instance
  utils.extend(instance, Axios.prototype, context);

  // Copy context to instance
  utils.extend(instance, context);

  return instance;
}

// Create the default instance to be exported
var axios = createInstance(defaults);

// Expose Axios class to allow class inheritance
axios.Axios = Axios;

// Factory for creating new instances
axios.create = function create(instanceConfig) {
  return createInstance(utils.merge(defaults, instanceConfig));
};

// Expose Cancel & CancelToken
axios.Cancel = __webpack_require__(27);
axios.CancelToken = __webpack_require__(84);
axios.isCancel = __webpack_require__(26);

// Expose all/spread
axios.all = function all(promises) {
  return Promise.all(promises);
};
axios.spread = __webpack_require__(85);

module.exports = axios;

// Allow use of default import syntax in TypeScript
module.exports.default = axios;


/***/ }),
/* 68 */
/***/ (function(module, exports) {

/*!
 * Determine if an object is a Buffer
 *
 * @author   Feross Aboukhadijeh <feross@feross.org> <http://feross.org>
 * @license  MIT
 */

// The _isBuffer check is for Safari 5-7 support, because it's missing
// Object.prototype.constructor. Remove this eventually
module.exports = function (obj) {
  return obj != null && (isBuffer(obj) || isSlowBuffer(obj) || !!obj._isBuffer)
}

function isBuffer (obj) {
  return !!obj.constructor && typeof obj.constructor.isBuffer === 'function' && obj.constructor.isBuffer(obj)
}

// For Node v0.10 support. Remove this eventually.
function isSlowBuffer (obj) {
  return typeof obj.readFloatLE === 'function' && typeof obj.slice === 'function' && isBuffer(obj.slice(0, 0))
}


/***/ }),
/* 69 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var defaults = __webpack_require__(9);
var utils = __webpack_require__(1);
var InterceptorManager = __webpack_require__(79);
var dispatchRequest = __webpack_require__(80);
var isAbsoluteURL = __webpack_require__(82);
var combineURLs = __webpack_require__(83);

/**
 * Create a new instance of Axios
 *
 * @param {Object} instanceConfig The default config for the instance
 */
function Axios(instanceConfig) {
  this.defaults = instanceConfig;
  this.interceptors = {
    request: new InterceptorManager(),
    response: new InterceptorManager()
  };
}

/**
 * Dispatch a request
 *
 * @param {Object} config The config specific for this request (merged with this.defaults)
 */
Axios.prototype.request = function request(config) {
  /*eslint no-param-reassign:0*/
  // Allow for axios('example/url'[, config]) a la fetch API
  if (typeof config === 'string') {
    config = utils.merge({
      url: arguments[0]
    }, arguments[1]);
  }

  config = utils.merge(defaults, this.defaults, { method: 'get' }, config);
  config.method = config.method.toLowerCase();

  // Support baseURL config
  if (config.baseURL && !isAbsoluteURL(config.url)) {
    config.url = combineURLs(config.baseURL, config.url);
  }

  // Hook up interceptors middleware
  var chain = [dispatchRequest, undefined];
  var promise = Promise.resolve(config);

  this.interceptors.request.forEach(function unshiftRequestInterceptors(interceptor) {
    chain.unshift(interceptor.fulfilled, interceptor.rejected);
  });

  this.interceptors.response.forEach(function pushResponseInterceptors(interceptor) {
    chain.push(interceptor.fulfilled, interceptor.rejected);
  });

  while (chain.length) {
    promise = promise.then(chain.shift(), chain.shift());
  }

  return promise;
};

// Provide aliases for supported request methods
utils.forEach(['delete', 'get', 'head', 'options'], function forEachMethodNoData(method) {
  /*eslint func-names:0*/
  Axios.prototype[method] = function(url, config) {
    return this.request(utils.merge(config || {}, {
      method: method,
      url: url
    }));
  };
});

utils.forEach(['post', 'put', 'patch'], function forEachMethodWithData(method) {
  /*eslint func-names:0*/
  Axios.prototype[method] = function(url, data, config) {
    return this.request(utils.merge(config || {}, {
      method: method,
      url: url,
      data: data
    }));
  };
});

module.exports = Axios;


/***/ }),
/* 70 */
/***/ (function(module, exports) {

// shim for using process in browser
var process = module.exports = {};

// cached from whatever global is present so that test runners that stub it
// don't break things.  But we need to wrap it in a try catch in case it is
// wrapped in strict mode code which doesn't define any globals.  It's inside a
// function because try/catches deoptimize in certain engines.

var cachedSetTimeout;
var cachedClearTimeout;

function defaultSetTimout() {
    throw new Error('setTimeout has not been defined');
}
function defaultClearTimeout () {
    throw new Error('clearTimeout has not been defined');
}
(function () {
    try {
        if (typeof setTimeout === 'function') {
            cachedSetTimeout = setTimeout;
        } else {
            cachedSetTimeout = defaultSetTimout;
        }
    } catch (e) {
        cachedSetTimeout = defaultSetTimout;
    }
    try {
        if (typeof clearTimeout === 'function') {
            cachedClearTimeout = clearTimeout;
        } else {
            cachedClearTimeout = defaultClearTimeout;
        }
    } catch (e) {
        cachedClearTimeout = defaultClearTimeout;
    }
} ())
function runTimeout(fun) {
    if (cachedSetTimeout === setTimeout) {
        //normal enviroments in sane situations
        return setTimeout(fun, 0);
    }
    // if setTimeout wasn't available but was latter defined
    if ((cachedSetTimeout === defaultSetTimout || !cachedSetTimeout) && setTimeout) {
        cachedSetTimeout = setTimeout;
        return setTimeout(fun, 0);
    }
    try {
        // when when somebody has screwed with setTimeout but no I.E. maddness
        return cachedSetTimeout(fun, 0);
    } catch(e){
        try {
            // When we are in I.E. but the script has been evaled so I.E. doesn't trust the global object when called normally
            return cachedSetTimeout.call(null, fun, 0);
        } catch(e){
            // same as above but when it's a version of I.E. that must have the global object for 'this', hopfully our context correct otherwise it will throw a global error
            return cachedSetTimeout.call(this, fun, 0);
        }
    }


}
function runClearTimeout(marker) {
    if (cachedClearTimeout === clearTimeout) {
        //normal enviroments in sane situations
        return clearTimeout(marker);
    }
    // if clearTimeout wasn't available but was latter defined
    if ((cachedClearTimeout === defaultClearTimeout || !cachedClearTimeout) && clearTimeout) {
        cachedClearTimeout = clearTimeout;
        return clearTimeout(marker);
    }
    try {
        // when when somebody has screwed with setTimeout but no I.E. maddness
        return cachedClearTimeout(marker);
    } catch (e){
        try {
            // When we are in I.E. but the script has been evaled so I.E. doesn't  trust the global object when called normally
            return cachedClearTimeout.call(null, marker);
        } catch (e){
            // same as above but when it's a version of I.E. that must have the global object for 'this', hopfully our context correct otherwise it will throw a global error.
            // Some versions of I.E. have different rules for clearTimeout vs setTimeout
            return cachedClearTimeout.call(this, marker);
        }
    }



}
var queue = [];
var draining = false;
var currentQueue;
var queueIndex = -1;

function cleanUpNextTick() {
    if (!draining || !currentQueue) {
        return;
    }
    draining = false;
    if (currentQueue.length) {
        queue = currentQueue.concat(queue);
    } else {
        queueIndex = -1;
    }
    if (queue.length) {
        drainQueue();
    }
}

function drainQueue() {
    if (draining) {
        return;
    }
    var timeout = runTimeout(cleanUpNextTick);
    draining = true;

    var len = queue.length;
    while(len) {
        currentQueue = queue;
        queue = [];
        while (++queueIndex < len) {
            if (currentQueue) {
                currentQueue[queueIndex].run();
            }
        }
        queueIndex = -1;
        len = queue.length;
    }
    currentQueue = null;
    draining = false;
    runClearTimeout(timeout);
}

process.nextTick = function (fun) {
    var args = new Array(arguments.length - 1);
    if (arguments.length > 1) {
        for (var i = 1; i < arguments.length; i++) {
            args[i - 1] = arguments[i];
        }
    }
    queue.push(new Item(fun, args));
    if (queue.length === 1 && !draining) {
        runTimeout(drainQueue);
    }
};

// v8 likes predictible objects
function Item(fun, array) {
    this.fun = fun;
    this.array = array;
}
Item.prototype.run = function () {
    this.fun.apply(null, this.array);
};
process.title = 'browser';
process.browser = true;
process.env = {};
process.argv = [];
process.version = ''; // empty string to avoid regexp issues
process.versions = {};

function noop() {}

process.on = noop;
process.addListener = noop;
process.once = noop;
process.off = noop;
process.removeListener = noop;
process.removeAllListeners = noop;
process.emit = noop;
process.prependListener = noop;
process.prependOnceListener = noop;

process.listeners = function (name) { return [] }

process.binding = function (name) {
    throw new Error('process.binding is not supported');
};

process.cwd = function () { return '/' };
process.chdir = function (dir) {
    throw new Error('process.chdir is not supported');
};
process.umask = function() { return 0; };


/***/ }),
/* 71 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var utils = __webpack_require__(1);

module.exports = function normalizeHeaderName(headers, normalizedName) {
  utils.forEach(headers, function processHeader(value, name) {
    if (name !== normalizedName && name.toUpperCase() === normalizedName.toUpperCase()) {
      headers[normalizedName] = value;
      delete headers[name];
    }
  });
};


/***/ }),
/* 72 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var createError = __webpack_require__(25);

/**
 * Resolve or reject a Promise based on response status.
 *
 * @param {Function} resolve A function that resolves the promise.
 * @param {Function} reject A function that rejects the promise.
 * @param {object} response The response.
 */
module.exports = function settle(resolve, reject, response) {
  var validateStatus = response.config.validateStatus;
  // Note: status is not exposed by XDomainRequest
  if (!response.status || !validateStatus || validateStatus(response.status)) {
    resolve(response);
  } else {
    reject(createError(
      'Request failed with status code ' + response.status,
      response.config,
      null,
      response.request,
      response
    ));
  }
};


/***/ }),
/* 73 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


/**
 * Update an Error with the specified config, error code, and response.
 *
 * @param {Error} error The error to update.
 * @param {Object} config The config.
 * @param {string} [code] The error code (for example, 'ECONNABORTED').
 * @param {Object} [request] The request.
 * @param {Object} [response] The response.
 * @returns {Error} The error.
 */
module.exports = function enhanceError(error, config, code, request, response) {
  error.config = config;
  if (code) {
    error.code = code;
  }
  error.request = request;
  error.response = response;
  return error;
};


/***/ }),
/* 74 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var utils = __webpack_require__(1);

function encode(val) {
  return encodeURIComponent(val).
    replace(/%40/gi, '@').
    replace(/%3A/gi, ':').
    replace(/%24/g, '$').
    replace(/%2C/gi, ',').
    replace(/%20/g, '+').
    replace(/%5B/gi, '[').
    replace(/%5D/gi, ']');
}

/**
 * Build a URL by appending params to the end
 *
 * @param {string} url The base of the url (e.g., http://www.google.com)
 * @param {object} [params] The params to be appended
 * @returns {string} The formatted url
 */
module.exports = function buildURL(url, params, paramsSerializer) {
  /*eslint no-param-reassign:0*/
  if (!params) {
    return url;
  }

  var serializedParams;
  if (paramsSerializer) {
    serializedParams = paramsSerializer(params);
  } else if (utils.isURLSearchParams(params)) {
    serializedParams = params.toString();
  } else {
    var parts = [];

    utils.forEach(params, function serialize(val, key) {
      if (val === null || typeof val === 'undefined') {
        return;
      }

      if (utils.isArray(val)) {
        key = key + '[]';
      }

      if (!utils.isArray(val)) {
        val = [val];
      }

      utils.forEach(val, function parseValue(v) {
        if (utils.isDate(v)) {
          v = v.toISOString();
        } else if (utils.isObject(v)) {
          v = JSON.stringify(v);
        }
        parts.push(encode(key) + '=' + encode(v));
      });
    });

    serializedParams = parts.join('&');
  }

  if (serializedParams) {
    url += (url.indexOf('?') === -1 ? '?' : '&') + serializedParams;
  }

  return url;
};


/***/ }),
/* 75 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var utils = __webpack_require__(1);

/**
 * Parse headers into an object
 *
 * ```
 * Date: Wed, 27 Aug 2014 08:58:49 GMT
 * Content-Type: application/json
 * Connection: keep-alive
 * Transfer-Encoding: chunked
 * ```
 *
 * @param {String} headers Headers needing to be parsed
 * @returns {Object} Headers parsed into an object
 */
module.exports = function parseHeaders(headers) {
  var parsed = {};
  var key;
  var val;
  var i;

  if (!headers) { return parsed; }

  utils.forEach(headers.split('\n'), function parser(line) {
    i = line.indexOf(':');
    key = utils.trim(line.substr(0, i)).toLowerCase();
    val = utils.trim(line.substr(i + 1));

    if (key) {
      parsed[key] = parsed[key] ? parsed[key] + ', ' + val : val;
    }
  });

  return parsed;
};


/***/ }),
/* 76 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var utils = __webpack_require__(1);

module.exports = (
  utils.isStandardBrowserEnv() ?

  // Standard browser envs have full support of the APIs needed to test
  // whether the request URL is of the same origin as current location.
  (function standardBrowserEnv() {
    var msie = /(msie|trident)/i.test(navigator.userAgent);
    var urlParsingNode = document.createElement('a');
    var originURL;

    /**
    * Parse a URL to discover it's components
    *
    * @param {String} url The URL to be parsed
    * @returns {Object}
    */
    function resolveURL(url) {
      var href = url;

      if (msie) {
        // IE needs attribute set twice to normalize properties
        urlParsingNode.setAttribute('href', href);
        href = urlParsingNode.href;
      }

      urlParsingNode.setAttribute('href', href);

      // urlParsingNode provides the UrlUtils interface - http://url.spec.whatwg.org/#urlutils
      return {
        href: urlParsingNode.href,
        protocol: urlParsingNode.protocol ? urlParsingNode.protocol.replace(/:$/, '') : '',
        host: urlParsingNode.host,
        search: urlParsingNode.search ? urlParsingNode.search.replace(/^\?/, '') : '',
        hash: urlParsingNode.hash ? urlParsingNode.hash.replace(/^#/, '') : '',
        hostname: urlParsingNode.hostname,
        port: urlParsingNode.port,
        pathname: (urlParsingNode.pathname.charAt(0) === '/') ?
                  urlParsingNode.pathname :
                  '/' + urlParsingNode.pathname
      };
    }

    originURL = resolveURL(window.location.href);

    /**
    * Determine if a URL shares the same origin as the current location
    *
    * @param {String} requestURL The URL to test
    * @returns {boolean} True if URL shares the same origin, otherwise false
    */
    return function isURLSameOrigin(requestURL) {
      var parsed = (utils.isString(requestURL)) ? resolveURL(requestURL) : requestURL;
      return (parsed.protocol === originURL.protocol &&
            parsed.host === originURL.host);
    };
  })() :

  // Non standard browser envs (web workers, react-native) lack needed support.
  (function nonStandardBrowserEnv() {
    return function isURLSameOrigin() {
      return true;
    };
  })()
);


/***/ }),
/* 77 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


// btoa polyfill for IE<10 courtesy https://github.com/davidchambers/Base64.js

var chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=';

function E() {
  this.message = 'String contains an invalid character';
}
E.prototype = new Error;
E.prototype.code = 5;
E.prototype.name = 'InvalidCharacterError';

function btoa(input) {
  var str = String(input);
  var output = '';
  for (
    // initialize result and counter
    var block, charCode, idx = 0, map = chars;
    // if the next str index does not exist:
    //   change the mapping table to "="
    //   check if d has no fractional digits
    str.charAt(idx | 0) || (map = '=', idx % 1);
    // "8 - idx % 1 * 8" generates the sequence 2, 4, 6, 8
    output += map.charAt(63 & block >> 8 - idx % 1 * 8)
  ) {
    charCode = str.charCodeAt(idx += 3 / 4);
    if (charCode > 0xFF) {
      throw new E();
    }
    block = block << 8 | charCode;
  }
  return output;
}

module.exports = btoa;


/***/ }),
/* 78 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var utils = __webpack_require__(1);

module.exports = (
  utils.isStandardBrowserEnv() ?

  // Standard browser envs support document.cookie
  (function standardBrowserEnv() {
    return {
      write: function write(name, value, expires, path, domain, secure) {
        var cookie = [];
        cookie.push(name + '=' + encodeURIComponent(value));

        if (utils.isNumber(expires)) {
          cookie.push('expires=' + new Date(expires).toGMTString());
        }

        if (utils.isString(path)) {
          cookie.push('path=' + path);
        }

        if (utils.isString(domain)) {
          cookie.push('domain=' + domain);
        }

        if (secure === true) {
          cookie.push('secure');
        }

        document.cookie = cookie.join('; ');
      },

      read: function read(name) {
        var match = document.cookie.match(new RegExp('(^|;\\s*)(' + name + ')=([^;]*)'));
        return (match ? decodeURIComponent(match[3]) : null);
      },

      remove: function remove(name) {
        this.write(name, '', Date.now() - 86400000);
      }
    };
  })() :

  // Non standard browser env (web workers, react-native) lack needed support.
  (function nonStandardBrowserEnv() {
    return {
      write: function write() {},
      read: function read() { return null; },
      remove: function remove() {}
    };
  })()
);


/***/ }),
/* 79 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var utils = __webpack_require__(1);

function InterceptorManager() {
  this.handlers = [];
}

/**
 * Add a new interceptor to the stack
 *
 * @param {Function} fulfilled The function to handle `then` for a `Promise`
 * @param {Function} rejected The function to handle `reject` for a `Promise`
 *
 * @return {Number} An ID used to remove interceptor later
 */
InterceptorManager.prototype.use = function use(fulfilled, rejected) {
  this.handlers.push({
    fulfilled: fulfilled,
    rejected: rejected
  });
  return this.handlers.length - 1;
};

/**
 * Remove an interceptor from the stack
 *
 * @param {Number} id The ID that was returned by `use`
 */
InterceptorManager.prototype.eject = function eject(id) {
  if (this.handlers[id]) {
    this.handlers[id] = null;
  }
};

/**
 * Iterate over all the registered interceptors
 *
 * This method is particularly useful for skipping over any
 * interceptors that may have become `null` calling `eject`.
 *
 * @param {Function} fn The function to call for each interceptor
 */
InterceptorManager.prototype.forEach = function forEach(fn) {
  utils.forEach(this.handlers, function forEachHandler(h) {
    if (h !== null) {
      fn(h);
    }
  });
};

module.exports = InterceptorManager;


/***/ }),
/* 80 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var utils = __webpack_require__(1);
var transformData = __webpack_require__(81);
var isCancel = __webpack_require__(26);
var defaults = __webpack_require__(9);

/**
 * Throws a `Cancel` if cancellation has been requested.
 */
function throwIfCancellationRequested(config) {
  if (config.cancelToken) {
    config.cancelToken.throwIfRequested();
  }
}

/**
 * Dispatch a request to the server using the configured adapter.
 *
 * @param {object} config The config that is to be used for the request
 * @returns {Promise} The Promise to be fulfilled
 */
module.exports = function dispatchRequest(config) {
  throwIfCancellationRequested(config);

  // Ensure headers exist
  config.headers = config.headers || {};

  // Transform request data
  config.data = transformData(
    config.data,
    config.headers,
    config.transformRequest
  );

  // Flatten headers
  config.headers = utils.merge(
    config.headers.common || {},
    config.headers[config.method] || {},
    config.headers || {}
  );

  utils.forEach(
    ['delete', 'get', 'head', 'post', 'put', 'patch', 'common'],
    function cleanHeaderConfig(method) {
      delete config.headers[method];
    }
  );

  var adapter = config.adapter || defaults.adapter;

  return adapter(config).then(function onAdapterResolution(response) {
    throwIfCancellationRequested(config);

    // Transform response data
    response.data = transformData(
      response.data,
      response.headers,
      config.transformResponse
    );

    return response;
  }, function onAdapterRejection(reason) {
    if (!isCancel(reason)) {
      throwIfCancellationRequested(config);

      // Transform response data
      if (reason && reason.response) {
        reason.response.data = transformData(
          reason.response.data,
          reason.response.headers,
          config.transformResponse
        );
      }
    }

    return Promise.reject(reason);
  });
};


/***/ }),
/* 81 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var utils = __webpack_require__(1);

/**
 * Transform the data for a request or a response
 *
 * @param {Object|String} data The data to be transformed
 * @param {Array} headers The headers for the request or response
 * @param {Array|Function} fns A single function or Array of functions
 * @returns {*} The resulting transformed data
 */
module.exports = function transformData(data, headers, fns) {
  /*eslint no-param-reassign:0*/
  utils.forEach(fns, function transform(fn) {
    data = fn(data, headers);
  });

  return data;
};


/***/ }),
/* 82 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


/**
 * Determines whether the specified URL is absolute
 *
 * @param {string} url The URL to test
 * @returns {boolean} True if the specified URL is absolute, otherwise false
 */
module.exports = function isAbsoluteURL(url) {
  // A URL is considered absolute if it begins with "<scheme>://" or "//" (protocol-relative URL).
  // RFC 3986 defines scheme name as a sequence of characters beginning with a letter and followed
  // by any combination of letters, digits, plus, period, or hyphen.
  return /^([a-z][a-z\d\+\-\.]*:)?\/\//i.test(url);
};


/***/ }),
/* 83 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


/**
 * Creates a new URL by combining the specified URLs
 *
 * @param {string} baseURL The base URL
 * @param {string} relativeURL The relative URL
 * @returns {string} The combined URL
 */
module.exports = function combineURLs(baseURL, relativeURL) {
  return relativeURL
    ? baseURL.replace(/\/+$/, '') + '/' + relativeURL.replace(/^\/+/, '')
    : baseURL;
};


/***/ }),
/* 84 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var Cancel = __webpack_require__(27);

/**
 * A `CancelToken` is an object that can be used to request cancellation of an operation.
 *
 * @class
 * @param {Function} executor The executor function.
 */
function CancelToken(executor) {
  if (typeof executor !== 'function') {
    throw new TypeError('executor must be a function.');
  }

  var resolvePromise;
  this.promise = new Promise(function promiseExecutor(resolve) {
    resolvePromise = resolve;
  });

  var token = this;
  executor(function cancel(message) {
    if (token.reason) {
      // Cancellation has already been requested
      return;
    }

    token.reason = new Cancel(message);
    resolvePromise(token.reason);
  });
}

/**
 * Throws a `Cancel` if cancellation has been requested.
 */
CancelToken.prototype.throwIfRequested = function throwIfRequested() {
  if (this.reason) {
    throw this.reason;
  }
};

/**
 * Returns an object that contains a new `CancelToken` and a function that, when called,
 * cancels the `CancelToken`.
 */
CancelToken.source = function source() {
  var cancel;
  var token = new CancelToken(function executor(c) {
    cancel = c;
  });
  return {
    token: token,
    cancel: cancel
  };
};

module.exports = CancelToken;


/***/ }),
/* 85 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


/**
 * Syntactic sugar for invoking a function and expanding an array for arguments.
 *
 * Common use case would be to use `Function.prototype.apply`.
 *
 *  ```js
 *  function f(x, y, z) {}
 *  var args = [1, 2, 3];
 *  f.apply(null, args);
 *  ```
 *
 * With `spread` this example can be re-written.
 *
 *  ```js
 *  spread(function(x, y, z) {})([1, 2, 3]);
 *  ```
 *
 * @param {Function} callback
 * @returns {Function}
 */
module.exports = function spread(callback) {
  return function wrap(arr) {
    return callback.apply(null, arr);
  };
};


/***/ }),
/* 86 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_react__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_react___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0_react__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_react_redux__ = __webpack_require__(2);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__actions_settingsAction__ = __webpack_require__(62);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3__components_RotatingText__ = __webpack_require__(97);
var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }






var AppTitle = function (_React$Component) {
    _inherits(AppTitle, _React$Component);

    function AppTitle() {
        _classCallCheck(this, AppTitle);

        return _possibleConstructorReturn(this, (AppTitle.__proto__ || Object.getPrototypeOf(AppTitle)).apply(this, arguments));
    }

    _createClass(AppTitle, [{
        key: 'render',
        value: function render() {
            var _props = this.props,
                toggleSettingsPanel = _props.toggleSettingsPanel,
                show_settings = _props.show_settings;

            var settings_btn_bg = show_settings ? "grey" : "blue";
            return __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                'div',
                { className: 'row app-title' },
                __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                    'div',
                    { className: 'col m7 title-col' },
                    __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                        'h5',
                        { className: 'thin main-title' },
                        'Reactive Mortgage Calculator',
                        __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                            'a',
                            {
                                className: "ml10 btn-floating btn-spin btn-floating-small waves-effect waves-light " + settings_btn_bg + " darken-3",
                                onClick: toggleSettingsPanel },
                            __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                                'i',
                                {
                                    className: 'material-icons' },
                                'settings'
                            )
                        )
                    )
                ),
                __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                    'div',
                    { className: 'col m12' },
                    __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                        __WEBPACK_IMPORTED_MODULE_3__components_RotatingText__["a" /* default */],
                        { interval: 5 },
                        __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                            'p',
                            { key: '1' },
                            'You are using the free version.',
                            ' ',
                            __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                                'a',
                                { href: 'https://srizon.com', target: '_blank' },
                                'Get Pro Version'
                            ),
                            ' ',
                            'for professional support and added feature.'
                        ),
                        __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                            'p',
                            { key: '2' },
                            'Created by ',
                            __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                                'a',
                                { href: 'https://srizon.com', target: '_blank' },
                                'Srizon Soft.'
                            )
                        ),
                        __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                            'p',
                            { key: '3' },
                            'Post a',
                            ' ',
                            __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                                'a',
                                { href: 'https://srizon.com', target: '_blank' },
                                'Review'
                            ),
                            ' ',
                            'if you havn\'t already.'
                        )
                    )
                )
            );
        }
    }]);

    return AppTitle;
}(__WEBPACK_IMPORTED_MODULE_0_react___default.a.Component);

// map state


function mapStateToProps(state) {
    return {
        show_settings: state.settings.show_settings
    };
}

// map dispatch
function mapDispatchToProps(dispatch) {
    return {
        toggleSettingsPanel: function toggleSettingsPanel() {
            dispatch(Object(__WEBPACK_IMPORTED_MODULE_2__actions_settingsAction__["c" /* toggleSettingsPanel */])());
        }
    };
}

// connect and export
/* harmony default export */ __webpack_exports__["a"] = (Object(__WEBPACK_IMPORTED_MODULE_1_react_redux__["b" /* connect */])(mapStateToProps, mapDispatchToProps)(AppTitle));

/***/ }),
/* 87 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_react__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_react___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0_react__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__components_form_TextField__ = __webpack_require__(8);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__components_form_RangeField__ = __webpack_require__(3);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3__components_form_RadioField__ = __webpack_require__(109);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_4__components_form_SwitchField__ = __webpack_require__(110);
var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }







var SettingsForm = function (_React$Component) {
           _inherits(SettingsForm, _React$Component);

           function SettingsForm() {
                      _classCallCheck(this, SettingsForm);

                      return _possibleConstructorReturn(this, (SettingsForm.__proto__ || Object.getPrototypeOf(SettingsForm)).apply(this, arguments));
           }

           _createClass(SettingsForm, [{
                      key: 'componentDidMount',
                      value: function componentDidMount() {
                                 jQuery('ul.tabs').tabs();
                      }
           }, {
                      key: 'render',
                      value: function render() {
                                 var _props = this.props,
                                     hich = _props.hich,
                                     pstate = _props.pstate,
                                     _props$global = _props.global,
                                     global = _props$global === undefined ? false : _props$global;

                                 return __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                                            'div',
                                            { className: 'row' },
                                            __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                                                       'div',
                                                       { className: 'col s12' },
                                                       __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                                                                  'ul',
                                                                  { className: 'tabs tabs-fixed-width bottom20' },
                                                                  __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                                                                             'li',
                                                                             { className: 'tab' },
                                                                             __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                                                                                        'a',
                                                                                        { className: 'active', href: '#property-tab' },
                                                                                        'Property Value'
                                                                             )
                                                                  ),
                                                                  __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                                                                             'li',
                                                                             { className: 'tab' },
                                                                             __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                                                                                        'a',
                                                                                        { href: '#downpayment-tab' },
                                                                                        'Down Payment'
                                                                             )
                                                                  ),
                                                                  __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                                                                             'li',
                                                                             { className: 'tab' },
                                                                             __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                                                                                        'a',
                                                                                        { href: '#interest-tab' },
                                                                                        'Interest Rate'
                                                                             )
                                                                  ),
                                                                  __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                                                                             'li',
                                                                             { className: 'tab' },
                                                                             __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                                                                                        'a',
                                                                                        { href: '#tenure-tab' },
                                                                                        'Tenure'
                                                                             )
                                                                  ),
                                                                  __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                                                                             'li',
                                                                             { className: 'tab' },
                                                                             __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                                                                                        'a',
                                                                                        { href: '#tax-tab' },
                                                                                        'Taxes and Charges'
                                                                             )
                                                                  ),
                                                                  __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                                                                             'li',
                                                                             { className: 'tab' },
                                                                             __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                                                                                        'a',
                                                                                        { href: '#other-tab' },
                                                                                        'Other'
                                                                             )
                                                                  )
                                                       )
                                            ),
                                            __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                                                       'div',
                                                       { className: "col s12 m6", id: 'property-tab' },
                                                       __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(__WEBPACK_IMPORTED_MODULE_1__components_form_TextField__["a" /* default */], { val: pstate.property_value_text, onch: hich, name: 'property_value_text',
                                                                  label: 'Property Value Text', aclass: '' }),
                                                       __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(__WEBPACK_IMPORTED_MODULE_4__components_form_SwitchField__["a" /* default */], { name: 'property_value_is_changeable', val: pstate.property_value_is_changeable,
                                                                  onch: hich, offtext: 'Fixed', ontext: 'Variable',
                                                                  label: 'Property Value Is', aclass: 'bottom40' }),
                                                       pstate.property_value_is_changeable ? __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(__WEBPACK_IMPORTED_MODULE_1__components_form_TextField__["a" /* default */], { val: pstate.property_value, onch: hich, name: 'property_value',
                                                                  label: 'Property Value - Default', aclass: '' }) : null,
                                                       pstate.property_value_is_changeable ? __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(__WEBPACK_IMPORTED_MODULE_1__components_form_TextField__["a" /* default */], { val: pstate.property_value_min, onch: hich, name: 'property_value_min',
                                                                  label: 'Property Value - Minimum', aclass: '' }) : null,
                                                       pstate.property_value_is_changeable ? __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(__WEBPACK_IMPORTED_MODULE_1__components_form_TextField__["a" /* default */], { val: pstate.property_value_max, onch: hich, name: 'property_value_max',
                                                                  label: 'Property Value - Maximum', aclass: '' }) : null,
                                                       !pstate.property_value_is_changeable ? __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(__WEBPACK_IMPORTED_MODULE_1__components_form_TextField__["a" /* default */], { val: pstate.property_value_fixed, onch: hich, name: 'property_value_fixed',
                                                                  label: 'Property Value', aclass: '' }) : null
                                            ),
                                            __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                                                       'div',
                                                       { className: "col s12 m6 offset-m1", id: 'downpayment-tab' },
                                                       __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(__WEBPACK_IMPORTED_MODULE_1__components_form_TextField__["a" /* default */], { val: pstate.downpayment_text, onch: hich, name: 'downpayment_text',
                                                                  label: 'Down Payment Text', aclass: '' }),
                                                       __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(__WEBPACK_IMPORTED_MODULE_4__components_form_SwitchField__["a" /* default */], { name: 'downpayment_is_changeable', val: pstate.downpayment_is_changeable,
                                                                  onch: hich, offtext: 'Fixed', ontext: 'Variable',
                                                                  label: 'Down Payment Is', aclass: 'bottom40' }),
                                                       __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(__WEBPACK_IMPORTED_MODULE_4__components_form_SwitchField__["a" /* default */], { name: 'downpayment_is_percent', val: pstate.downpayment_is_percent,
                                                                  onch: hich, offtext: 'Total Amount', ontext: 'Percent',
                                                                  label: 'Down Payment In', aclass: 'bottom40' }),
                                                       !pstate.downpayment_is_percent && !pstate.downpayment_is_changeable ? __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(__WEBPACK_IMPORTED_MODULE_1__components_form_TextField__["a" /* default */], { val: pstate.downpayment_amount_fixed, onch: hich, name: 'downpayment_amount_fixed',
                                                                  label: 'Down-Payment Amount', aclass: '' }) : null,
                                                       !pstate.downpayment_is_percent && pstate.downpayment_is_changeable ? __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(__WEBPACK_IMPORTED_MODULE_1__components_form_TextField__["a" /* default */], { val: pstate.downpayment_amount, onch: hich, name: 'downpayment_amount',
                                                                  label: 'Down-Payment Amount - Default', aclass: '' }) : null,
                                                       !pstate.downpayment_is_percent && pstate.downpayment_is_changeable ? __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(__WEBPACK_IMPORTED_MODULE_1__components_form_TextField__["a" /* default */], { val: pstate.downpayment_amount_min, onch: hich, name: 'downpayment_amount_min',
                                                                  label: 'Down-Payment Amount - Minimum', aclass: '' }) : null,
                                                       !pstate.downpayment_is_percent && pstate.downpayment_is_changeable ? __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(__WEBPACK_IMPORTED_MODULE_1__components_form_TextField__["a" /* default */], { val: pstate.downpayment_amount_max, onch: hich, name: 'downpayment_amount_max',
                                                                  label: 'Down-Payment Amount - Maximum', aclass: '' }) : null,
                                                       pstate.downpayment_is_percent && !pstate.downpayment_is_changeable ? __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(__WEBPACK_IMPORTED_MODULE_1__components_form_TextField__["a" /* default */], { val: pstate.downpayment_percent_fixed, onch: hich, name: 'downpayment_percent_fixed',
                                                                  label: 'Down-Payment Percent', aclass: '' }) : null,
                                                       pstate.downpayment_is_percent && pstate.downpayment_is_changeable ? __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(__WEBPACK_IMPORTED_MODULE_1__components_form_TextField__["a" /* default */], { val: pstate.downpayment_percent, onch: hich, name: 'downpayment_percent',
                                                                  label: 'Down-Payment Percent - Default', aclass: '' }) : null,
                                                       pstate.downpayment_is_percent && pstate.downpayment_is_changeable ? __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(__WEBPACK_IMPORTED_MODULE_1__components_form_TextField__["a" /* default */], { val: pstate.downpayment_percent_min, onch: hich, name: 'downpayment_percent_min',
                                                                  label: 'Down-Payment Percent - Minimum', aclass: '' }) : null,
                                                       pstate.downpayment_is_percent && pstate.downpayment_is_changeable ? __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(__WEBPACK_IMPORTED_MODULE_1__components_form_TextField__["a" /* default */], { val: pstate.downpayment_percent_max, onch: hich, name: 'downpayment_percent_max',
                                                                  label: 'Down-Payment Percent - Maximum', aclass: '' }) : null
                                            ),
                                            __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                                                       'div',
                                                       { className: "col s12 m6 offset-m2", id: 'interest-tab' },
                                                       __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(__WEBPACK_IMPORTED_MODULE_1__components_form_TextField__["a" /* default */], { val: pstate.interest_text, onch: hich, name: 'interest_text',
                                                                  label: 'Interest Text', aclass: '' }),
                                                       __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(__WEBPACK_IMPORTED_MODULE_4__components_form_SwitchField__["a" /* default */], { name: 'interest_is_changeable', val: pstate.interest_is_changeable,
                                                                  onch: hich, offtext: 'Fixed', ontext: 'Variable',
                                                                  label: 'Interest Rate Is', aclass: 'bottom40' }),
                                                       pstate.interest_is_changeable ? __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(__WEBPACK_IMPORTED_MODULE_1__components_form_TextField__["a" /* default */], { val: pstate.interest, onch: hich, name: 'interest',
                                                                  label: 'Interest Rate - Default', aclass: '' }) : null,
                                                       pstate.interest_is_changeable ? __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(__WEBPACK_IMPORTED_MODULE_1__components_form_TextField__["a" /* default */], { val: pstate.interest_min, onch: hich, name: 'interest_min',
                                                                  label: 'Interest Rate - Mininum', aclass: '' }) : null,
                                                       pstate.interest_is_changeable ? __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(__WEBPACK_IMPORTED_MODULE_1__components_form_TextField__["a" /* default */], { val: pstate.interest_max, onch: hich, name: 'interest_max',
                                                                  label: 'Interest Rate - Maximum', aclass: '' }) : null,
                                                       !pstate.interest_is_changeable ? __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(__WEBPACK_IMPORTED_MODULE_1__components_form_TextField__["a" /* default */], { val: pstate.interest_fixed, onch: hich, name: 'interest_fixed',
                                                                  label: 'Interest Rate', aclass: '' }) : null
                                            ),
                                            __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                                                       'div',
                                                       { className: "col s12 m6 offset-m4", id: 'tenure-tab' },
                                                       __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(__WEBPACK_IMPORTED_MODULE_1__components_form_TextField__["a" /* default */], { val: pstate.tenure_text, onch: hich, name: 'tenure_text',
                                                                  label: 'Tenure Text', aclass: '' }),
                                                       __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(__WEBPACK_IMPORTED_MODULE_4__components_form_SwitchField__["a" /* default */], { name: 'tenure_is_changeable', val: pstate.tenure_is_changeable,
                                                                  onch: hich, offtext: 'Fixed', ontext: 'Variable',
                                                                  label: 'Tenure Is', aclass: 'bottom40' }),
                                                       pstate.tenure_is_changeable ? __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(__WEBPACK_IMPORTED_MODULE_1__components_form_TextField__["a" /* default */], { val: pstate.tenure, onch: hich, name: 'tenure',
                                                                  label: 'Tenure - Default (Year)', aclass: '' }) : null,
                                                       pstate.tenure_is_changeable ? __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(__WEBPACK_IMPORTED_MODULE_1__components_form_TextField__["a" /* default */], { val: pstate.tenure_min, onch: hich, name: 'tenure_min',
                                                                  label: 'Tenure - Mininum (Year)', aclass: '' }) : null,
                                                       pstate.tenure_is_changeable ? __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(__WEBPACK_IMPORTED_MODULE_1__components_form_TextField__["a" /* default */], { val: pstate.tenure_max, onch: hich, name: 'tenure_max',
                                                                  label: 'Tenure - Maximum (Year)', aclass: '' }) : null,
                                                       !pstate.tenure_is_changeable ? __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(__WEBPACK_IMPORTED_MODULE_1__components_form_TextField__["a" /* default */], { val: pstate.tenure_fixed, onch: hich, name: 'tenure_fixed',
                                                                  label: 'Tenure (Year)', aclass: '' }) : null
                                            ),
                                            __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                                                       'div',
                                                       { className: "col s12 m6 offset-m5", id: 'tax-tab' },
                                                       __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(__WEBPACK_IMPORTED_MODULE_1__components_form_TextField__["a" /* default */], { val: pstate.property_tax_text, onch: hich, name: 'property_tax_text',
                                                                  label: 'Property Tax Text', aclass: '' }),
                                                       __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(__WEBPACK_IMPORTED_MODULE_1__components_form_TextField__["a" /* default */], { val: pstate.property_tax, onch: hich, name: 'property_tax',
                                                                  label: 'Property Tax - Default (% Per Year)', aclass: '' }),
                                                       __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(__WEBPACK_IMPORTED_MODULE_1__components_form_TextField__["a" /* default */], { val: pstate.property_tax_min, onch: hich, name: 'property_tax_min',
                                                                  label: 'Property Tax - Mininum', aclass: '' }),
                                                       __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(__WEBPACK_IMPORTED_MODULE_1__components_form_TextField__["a" /* default */], { val: pstate.property_tax_max, onch: hich, name: 'property_tax_max',
                                                                  label: 'Property Tax - Maximum', aclass: '' }),
                                                       __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement('br', null),
                                                       __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(__WEBPACK_IMPORTED_MODULE_1__components_form_TextField__["a" /* default */], { val: pstate.hazard_insurance_text, onch: hich, name: 'hazard_insurance_text',
                                                                  label: 'Annual Hazard Insurance Text', aclass: '' }),
                                                       __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(__WEBPACK_IMPORTED_MODULE_1__components_form_TextField__["a" /* default */], { val: pstate.hazard_insurance, onch: hich, name: 'hazard_insurance',
                                                                  label: 'Annual Hazard Insurance- Default (Per Year)', aclass: '' }),
                                                       __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(__WEBPACK_IMPORTED_MODULE_1__components_form_TextField__["a" /* default */], { val: pstate.hazard_insurance_min, onch: hich, name: 'hazard_insurance_min',
                                                                  label: 'Annual Hazard Insurance - Mininum', aclass: '' }),
                                                       __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(__WEBPACK_IMPORTED_MODULE_1__components_form_TextField__["a" /* default */], { val: pstate.hazard_insurance_max, onch: hich, name: 'hazard_insurance_max',
                                                                  label: 'Annual Hazard Insurance - Maximum', aclass: '' }),
                                                       __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement('br', null),
                                                       __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(__WEBPACK_IMPORTED_MODULE_1__components_form_TextField__["a" /* default */], { val: pstate.monthly_hoa_text, onch: hich, name: 'monthly_hoa_text',
                                                                  label: 'Monthly HOA Text', aclass: '' }),
                                                       __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(__WEBPACK_IMPORTED_MODULE_1__components_form_TextField__["a" /* default */], { val: pstate.monthly_hoa, onch: hich, name: 'monthly_hoa',
                                                                  label: 'Monthly HOA', aclass: '' }),
                                                       __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(__WEBPACK_IMPORTED_MODULE_1__components_form_TextField__["a" /* default */], { val: pstate.monthly_hoa_min, onch: hich, name: 'monthly_hoa_min',
                                                                  label: 'Monthly HOA - Mininum', aclass: '' }),
                                                       __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(__WEBPACK_IMPORTED_MODULE_1__components_form_TextField__["a" /* default */], { val: pstate.monthly_hoa_max, onch: hich, name: 'monthly_hoa_max',
                                                                  label: 'Monthly HOA - Maximum', aclass: '' }),
                                                       __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement('br', null),
                                                       __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(__WEBPACK_IMPORTED_MODULE_1__components_form_TextField__["a" /* default */], { val: pstate.mortgage_insurance_text, onch: hich, name: 'mortgage_insurance_text',
                                                                  label: 'Mortgage Insurance Text', aclass: '' }),
                                                       __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(__WEBPACK_IMPORTED_MODULE_1__components_form_TextField__["a" /* default */], { val: pstate.mortgage_insurance, onch: hich, name: 'mortgage_insurance',
                                                                  label: 'Mortgage Insurance', aclass: '' }),
                                                       __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(__WEBPACK_IMPORTED_MODULE_1__components_form_TextField__["a" /* default */], { val: pstate.mortgage_insurance_min, onch: hich, name: 'mortgage_insurance_min',
                                                                  label: 'Mortgage Insurance - Mininum', aclass: '' }),
                                                       __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(__WEBPACK_IMPORTED_MODULE_1__components_form_TextField__["a" /* default */], { val: pstate.mortgage_insurance_max, onch: hich, name: 'mortgage_insurance_max',
                                                                  label: 'Mortgage Insurance - Maximum', aclass: '' })
                                            ),
                                            __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                                                       'div',
                                                       { className: "col s12 m6 offset-m6", id: 'other-tab' },
                                                       __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(__WEBPACK_IMPORTED_MODULE_1__components_form_TextField__["a" /* default */], { val: pstate.currency, onch: hich, name: 'currency',
                                                                  label: 'Currency Symbol', aclass: '' })
                                            )
                                 );
                      }
           }]);

           return SettingsForm;
}(__WEBPACK_IMPORTED_MODULE_0_react___default.a.Component);

/* harmony default export */ __webpack_exports__["a"] = (SettingsForm);

/***/ }),
/* 88 */,
/* 89 */
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(90);
__webpack_require__(119);
__webpack_require__(120);
__webpack_require__(121);
module.exports = __webpack_require__(122);


/***/ }),
/* 90 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_react__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_react___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0_react__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_react_dom__ = __webpack_require__(28);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_react_dom___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_1_react_dom__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__store__ = __webpack_require__(91);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3_react_redux__ = __webpack_require__(2);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_4__containers_Base__ = __webpack_require__(95);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_5__actions_settingsAction__ = __webpack_require__(62);







var render = function render() {
    __WEBPACK_IMPORTED_MODULE_1_react_dom___default.a.render(__WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
        __WEBPACK_IMPORTED_MODULE_3_react_redux__["a" /* Provider */],
        { store: __WEBPACK_IMPORTED_MODULE_2__store__["a" /* default */] },
        __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(__WEBPACK_IMPORTED_MODULE_4__containers_Base__["a" /* default */], null)
    ), document.getElementById('srizon-mortgage-admin'));
};

window.axios = __webpack_require__(66);

if (wpApiSettings) {
    window.axios.defaults.headers.common['X-WP-Nonce'] = wpApiSettings.nonce;
    window.srzmortbase = wpApiSettings.root + 'srizon-mortgage/v1/';
}
window.store = __WEBPACK_IMPORTED_MODULE_2__store__["a" /* default */];
__WEBPACK_IMPORTED_MODULE_2__store__["a" /* default */].dispatch(Object(__WEBPACK_IMPORTED_MODULE_5__actions_settingsAction__["a" /* loadSettings */])());
__WEBPACK_IMPORTED_MODULE_2__store__["a" /* default */].subscribe(render);
render();

/***/ }),
/* 91 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_redux__ = __webpack_require__(4);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__reducers_settingsReducer__ = __webpack_require__(92);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__reducers_instanceReducer__ = __webpack_require__(93);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3__reducers_messagesReducer__ = __webpack_require__(94);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_4_redux_thunk__ = __webpack_require__(46);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_4_redux_thunk___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_4_redux_thunk__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_5_redux_logger__ = __webpack_require__(47);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_5_redux_logger___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_5_redux_logger__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_6_redux_devtools_extension__ = __webpack_require__(48);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_6_redux_devtools_extension___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_6_redux_devtools_extension__);






// flip commented/uncommented parts below this line to dev/prod build




/* harmony default export */ __webpack_exports__["a"] = (Object(__WEBPACK_IMPORTED_MODULE_0_redux__["createStore"])(Object(__WEBPACK_IMPORTED_MODULE_0_redux__["combineReducers"])({
    settings: __WEBPACK_IMPORTED_MODULE_1__reducers_settingsReducer__["a" /* default */],
    messages: __WEBPACK_IMPORTED_MODULE_3__reducers_messagesReducer__["a" /* default */],
    instances: __WEBPACK_IMPORTED_MODULE_2__reducers_instanceReducer__["a" /* default */]
}), Object(__WEBPACK_IMPORTED_MODULE_6_redux_devtools_extension__["composeWithDevTools"])(Object(__WEBPACK_IMPORTED_MODULE_0_redux__["applyMiddleware"])(__WEBPACK_IMPORTED_MODULE_5_redux_logger___default.a, __WEBPACK_IMPORTED_MODULE_4_redux_thunk___default.a))));

// export default  createStore(combineReducers({
//     settings: settingsReducer,
//     messages: messagesReducer,
//     instances: instanceReducer
// }), applyMiddleware(thunk));

/***/ }),
/* 92 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (immutable) */ __webpack_exports__["a"] = settingsReducer;
var _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; };

var initial_state = {
    init: true,
    open_instance_form: false,
    saving_instance_in_progress: false,
    show_settings: false,
    saving_settings_in_progress: false
};
function settingsReducer() {
    var state = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : initial_state;
    var action = arguments[1];

    switch (action.type) {
        case 'SRIZON_MORTGAGE_SETTINGS_RECEIVED':
            return _extends({}, state, { init: false, options: action.payload });
        case 'SRIZON_MORTGAGE_SETTINGS_TOGGLE_SETTINGS_PANEL':
            return _extends({}, state, { show_settings: !state.show_settings });
        case 'SRIZON_MORTGAGE_SETTINGS_NEW_INSTANCE':
            return _extends({}, state, { open_instance_form: true, saving_instance_in_progress: false });
        case 'SRIZON_MORTGAGE_SETTINGS_CANCEL_INSTANCE':
            return _extends({}, state, {
                open_instance_form: false,
                saving_instance_in_progress: false
            });
        case 'SRIZON_MORTGAGE_SETTINGS_SAVING_INSTANCE':
            return _extends({}, state, { saving_instance_in_progress: true });
        case 'SRIZON_MORTGAGE_SETTINGS_SAVED_INSTANCE':
            return _extends({}, state, { open_instance_form: false, saving_instance_in_progress: false });
        case 'SRIZON_MORTGAGE_SETTINGS_SAVING_GLOBAL':
            return _extends({}, state, { saving_settings_in_progress: true });
        case 'SRIZON_MORTGAGE_SETTINGS_SAVING_ERROR_GLOBAL':
            return _extends({}, state, { saving_settings_in_progress: false });
        case 'SRIZON_MORTGAGE_SETTINGS_SAVED_GLOBAL':
            return _extends({}, state, {
                options: _extends({}, state.options, { global: action.payload }),
                saving_settings_in_progress: false,
                show_settings: false
            });
        default:
            return state;
    }
}

/***/ }),
/* 93 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (immutable) */ __webpack_exports__["a"] = instanceReducer;
var _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; };

function _toConsumableArray(arr) { if (Array.isArray(arr)) { for (var i = 0, arr2 = Array(arr.length); i < arr.length; i++) { arr2[i] = arr[i]; } return arr2; } else { return Array.from(arr); } }

var initial_state = {
    instances: [],
    instances_updating: [],
    settings_open: false,
    initial_load: false
};
function instanceReducer() {
    var state = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : initial_state;
    var action = arguments[1];

    switch (action.type) {
        case 'SRIZON_MORTGAGE_INSTANCE_SETTINGS_OPEN':
            return _extends({}, state, { settings_open: action.payload });
        case 'SRIZON_MORTGAGE_INSTANCE_SETTINGS_CLOSE':
            return _extends({}, state, { settings_open: false });
        case 'SRIZON_MORTGAGE_INSTANCES_RECEIVED':
            return _extends({}, state, { initial_load: true, instances: action.payload });
        case 'SRIZON_MORTGAGE_INSTANCE_DELETEING':
            return _extends({}, state, { instances: state.instances.filter(function (instance) {
                    return instance.id != action.payload;
                }) });
        case 'SRIZON_MORTGAGE_INSTANCE_DELETED':
            return _extends({}, state, { instances: action.payload });
        case 'SRIZON_MORTGAGE_INSTANCE_ADDED':
            return _extends({}, state, { instances: [].concat(_toConsumableArray(state.instances), [action.payload]) });
        case 'SRIZON_MORTGAGE_SETTINGS_SAVED_INSTANCE':
            return _extends({}, state, { instances: action.payload, settings_open: false });
        case 'SRIZON_MORTGAGE_INSTANCE_UPDATING':
            return _extends({}, state, { instances_updating: [].concat(_toConsumableArray(state.instances_updating), [action.payload]) });
        case 'SRIZON_MORTGAGE_INSTANCE_UPDATED':
            return _extends({}, state, {
                instances_updating: state.instances_updating.filter(function (id) {
                    return id != action.payload.id;
                }),
                instances: action.payload.instances
            });
        default:
            return state;
    }
}

/***/ }),
/* 94 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (immutable) */ __webpack_exports__["a"] = messagesReducer;
var _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; };

function _toConsumableArray(arr) { if (Array.isArray(arr)) { for (var i = 0, arr2 = Array(arr.length); i < arr.length; i++) { arr2[i] = arr[i]; } return arr2; } else { return Array.from(arr); } }

var initial_state = {
    current_id: 1,
    count: 0,
    messages: []
};
function messagesReducer() {
    var state = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : initial_state;
    var action = arguments[1];

    switch (action.type) {
        case 'SRIZON_MORTGAGE_MESSAGE_RECEIVED':
            return _extends({}, state, {
                count: state.count + 1,
                messages: [].concat(_toConsumableArray(state.messages), [{
                    txt: action.payload.txt,
                    type: action.payload.type,
                    id: state.current_id,
                    expire_in: action.payload.expire_in ? action.payload.expire_in : 5
                }]),
                current_id: state.current_id + 1
            });
        case 'SRIZON_MORTGAGE_MESSAGE_EXPIRED':
            return _extends({}, state, {
                count: state.count - 1,
                messages: state.messages.filter(function (msg) {
                    return msg.id != action.payload;
                })
            });

        default:
            return state;
    }
}

/***/ }),
/* 95 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_react__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_react___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0_react__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_react_redux__ = __webpack_require__(2);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__components_partials_CircularLoaderFull__ = __webpack_require__(96);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3__MainPanel__ = __webpack_require__(104);





// smart component with redux connect

var Base = function Base(_ref) {
    var init = _ref.init;
    return __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
        'div',
        null,
        init ? __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(__WEBPACK_IMPORTED_MODULE_2__components_partials_CircularLoaderFull__["a" /* default */], null) : __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(__WEBPACK_IMPORTED_MODULE_3__MainPanel__["a" /* default */], null)
    );
};

// map state
function mapStateToProps(state) {
    return {
        init: state.settings.init
    };
}

// map dispatch
function mapDispatchToProps(dispatch) {
    return {};
}

// connect and export
/* harmony default export */ __webpack_exports__["a"] = (Object(__WEBPACK_IMPORTED_MODULE_1_react_redux__["b" /* connect */])(mapStateToProps, mapDispatchToProps)(Base));

/***/ }),
/* 96 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_react__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_react___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0_react__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__containers_AppTitle__ = __webpack_require__(86);



var CircularLoaderFull = function CircularLoaderFull() {
    return __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
        'div',
        null,
        __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(__WEBPACK_IMPORTED_MODULE_1__containers_AppTitle__["a" /* default */], { connected_user: false }),
        __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
            'div',
            { className: 'center top50' },
            __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                'div',
                { className: 'preloader-wrapper big active' },
                __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                    'div',
                    { className: 'spinner-layer spinner-blue' },
                    __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                        'div',
                        { className: 'circle-clipper left' },
                        __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement('div', { className: 'circle' })
                    ),
                    __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                        'div',
                        { className: 'gap-patch' },
                        __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement('div', { className: 'circle' })
                    ),
                    __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                        'div',
                        { className: 'circle-clipper right' },
                        __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement('div', { className: 'circle' })
                    )
                ),
                __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                    'div',
                    { className: 'spinner-layer spinner-red' },
                    __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                        'div',
                        { className: 'circle-clipper left' },
                        __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement('div', { className: 'circle' })
                    ),
                    __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                        'div',
                        { className: 'gap-patch' },
                        __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement('div', { className: 'circle' })
                    ),
                    __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                        'div',
                        { className: 'circle-clipper right' },
                        __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement('div', { className: 'circle' })
                    )
                ),
                __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                    'div',
                    { className: 'spinner-layer spinner-yellow' },
                    __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                        'div',
                        { className: 'circle-clipper left' },
                        __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement('div', { className: 'circle' })
                    ),
                    __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                        'div',
                        { className: 'gap-patch' },
                        __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement('div', { className: 'circle' })
                    ),
                    __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                        'div',
                        { className: 'circle-clipper right' },
                        __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement('div', { className: 'circle' })
                    )
                ),
                __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                    'div',
                    { className: 'spinner-layer spinner-green' },
                    __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                        'div',
                        { className: 'circle-clipper left' },
                        __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement('div', { className: 'circle' })
                    ),
                    __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                        'div',
                        { className: 'gap-patch' },
                        __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement('div', { className: 'circle' })
                    ),
                    __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                        'div',
                        { className: 'circle-clipper right' },
                        __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement('div', { className: 'circle' })
                    )
                )
            )
        )
    );
};

/* harmony default export */ __webpack_exports__["a"] = (CircularLoaderFull);

/***/ }),
/* 97 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_react__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_react___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0_react__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_react_flip_move__ = __webpack_require__(64);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_react_flip_move___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_1_react_flip_move__);
var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }




var RotatingText = function (_Component) {
    _inherits(RotatingText, _Component);

    function RotatingText() {
        _classCallCheck(this, RotatingText);

        var _this = _possibleConstructorReturn(this, (RotatingText.__proto__ || Object.getPrototypeOf(RotatingText)).call(this));

        _this.state = {
            index: 0,
            hovering: false
        };
        return _this;
    }

    _createClass(RotatingText, [{
        key: 'mouseEnter',
        value: function mouseEnter() {
            this.setState({ hovering: true });
        }
    }, {
        key: 'mouseLeave',
        value: function mouseLeave() {
            this.setState({ hovering: false });
        }
    }, {
        key: 'componentDidMount',
        value: function componentDidMount() {
            var _this2 = this;

            var _props$interval = this.props.interval,
                interval = _props$interval === undefined ? 5 : _props$interval;

            setInterval(function () {
                !_this2.state.hovering ? _this2.setState(function (pState) {
                    return { index: pState.index + 1 };
                }) : null;
            }, interval * 1000);
        }
    }, {
        key: 'render',
        value: function render() {
            var children = this.props.children;

            return __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                'div',
                { onMouseEnter: this.mouseEnter.bind(this), onMouseLeave: this.mouseLeave.bind(this) },
                __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                    __WEBPACK_IMPORTED_MODULE_1_react_flip_move___default.a,
                    { duration: 500, enterAnimation: 'accordionVertical', leaveAnimation: 'accordionVertical' },
                    children.length ? children[this.state.index % children.length] : children
                )
            );
        }
    }]);

    return RotatingText;
}(__WEBPACK_IMPORTED_MODULE_0_react__["Component"]);

/* harmony default export */ __webpack_exports__["a"] = (RotatingText);

/***/ }),
/* 98 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
  value: true
});

var _slicedToArray = function () { function sliceIterator(arr, i) { var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"]) _i["return"](); } finally { if (_d) throw _e; } } return _arr; } return function (arr, i) { if (Array.isArray(arr)) { return arr; } else if (Symbol.iterator in Object(arr)) { return sliceIterator(arr, i); } else { throw new TypeError("Invalid attempt to destructure non-iterable instance"); } }; }();

var _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; };

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _react = __webpack_require__(0);

var _react2 = _interopRequireDefault(_react);

__webpack_require__(99);

var _propConverter = __webpack_require__(100);

var _propConverter2 = _interopRequireDefault(_propConverter);

var _domManipulation = __webpack_require__(103);

var _helpers = __webpack_require__(65);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }
/**
 * React Flip Move
 * (c) 2016-present Joshua Comeau
 *
 * For information on how this code is laid out, check out CODE_TOUR.md
 */

/* eslint-disable react/prop-types */

var transitionEnd = (0, _domManipulation.whichTransitionEvent)();
var noBrowserSupport = !transitionEnd;

function getKey(childData) {
  return childData.key || '';
}

var FlipMove = function (_Component) {
  _inherits(FlipMove, _Component);

  function FlipMove() {
    var _ref;

    var _temp, _this, _ret;

    _classCallCheck(this, FlipMove);

    for (var _len = arguments.length, args = Array(_len), _key = 0; _key < _len; _key++) {
      args[_key] = arguments[_key];
    }

    return _ret = (_temp = (_this = _possibleConstructorReturn(this, (_ref = FlipMove.__proto__ || Object.getPrototypeOf(FlipMove)).call.apply(_ref, [this].concat(args))), _this), _this.state = {
      children: _react.Children.toArray(_this.props.children).map(function (element) {
        return _extends({}, element, {
          element: element,
          appearing: true
        });
      })
    }, _this.childrenData = {}, _this.parentData = {
      domNode: null,
      boundingBox: null
    }, _this.heightPlaceholderData = {
      domNode: null
    }, _this.remainingAnimations = 0, _this.childrenToAnimate = [], _this.runAnimation = function () {
      var dynamicChildren = _this.state.children.filter(_this.doesChildNeedToBeAnimated);

      dynamicChildren.forEach(function (child, n) {
        _this.remainingAnimations += 1;
        _this.childrenToAnimate.push(getKey(child));
        _this.animateChild(child, n);
      });

      if (typeof _this.props.onStartAll === 'function') {
        _this.callChildrenHook(_this.props.onStartAll);
      }
    }, _this.doesChildNeedToBeAnimated = function (child) {
      // If the child doesn't have a key, it's an immovable child (one that we
      // do not want to do FLIP stuff to.)
      if (!getKey(child)) {
        return false;
      }

      var childData = _this.getChildData(getKey(child));
      var childDomNode = childData.domNode;
      var childBoundingBox = childData.boundingBox;
      var parentBoundingBox = _this.parentData.boundingBox;

      if (!childDomNode) {
        return false;
      }

      var _this$props = _this.props;
      var appearAnimation = _this$props.appearAnimation;
      var enterAnimation = _this$props.enterAnimation;
      var leaveAnimation = _this$props.leaveAnimation;
      var getPosition = _this$props.getPosition;


      var isAppearingWithAnimation = child.appearing && appearAnimation;
      var isEnteringWithAnimation = child.entering && enterAnimation;
      var isLeavingWithAnimation = child.leaving && leaveAnimation;

      if (isAppearingWithAnimation || isEnteringWithAnimation || isLeavingWithAnimation) {
        return true;
      }

      // If it isn't entering/leaving, we want to animate it if it's
      // on-screen position has changed.

      var _getPositionDelta = (0, _domManipulation.getPositionDelta)({
        childDomNode: childDomNode,
        childBoundingBox: childBoundingBox,
        parentBoundingBox: parentBoundingBox,
        getPosition: getPosition
      });

      var _getPositionDelta2 = _slicedToArray(_getPositionDelta, 2);

      var dX = _getPositionDelta2[0];
      var dY = _getPositionDelta2[1];


      return dX !== 0 || dY !== 0;
    }, _temp), _possibleConstructorReturn(_this, _ret);
  }
  // Copy props.children into state.
  // To understand why this is important (and not an anti-pattern), consider
  // how "leave" animations work. An item has "left" when the component
  // receives a new set of props that do NOT contain the item.
  // If we just render the props as-is, the item would instantly disappear.
  // We want to keep the item rendered for a little while, until its animation
  // can complete. Because we cannot mutate props, we make `state` the source
  // of truth.


  // FlipMove needs to know quite a bit about its children in order to do
  // its job. We store these as a property on the instance. We're not using
  // state, because we don't want changes to trigger re-renders, we just
  // need a place to keep the data for reference, when changes happen.
  // This field should not be accessed directly. Instead, use getChildData,
  // putChildData, etc...


  // Similarly, track the dom node and box of our parent element.


  // If `maintainContainerHeight` prop is set to true, we'll create a
  // placeholder element which occupies space so that the parent height
  // doesn't change when items are removed from the document flow (which
  // happens during leave animations)


  // Keep track of remaining animations so we know when to fire the
  // all-finished callback, and clean up after ourselves.
  // NOTE: we can't simply use childrenToAnimate.length to track remaining
  // animations, because we need to maintain the list of animating children,
  // to pass to the `onFinishAll` handler.


  _createClass(FlipMove, [{
    key: 'componentDidMount',
    value: function componentDidMount() {
      // Run our `appearAnimation` if it was requested, right after the
      // component mounts.
      var shouldTriggerFLIP = this.props.appearAnimation && !this.isAnimationDisabled(this.props);

      if (shouldTriggerFLIP) {
        this.prepForAnimation();
        this.runAnimation();
      }
    }
  }, {
    key: 'componentWillReceiveProps',
    value: function componentWillReceiveProps(nextProps) {
      // When the component is handed new props, we need to figure out the
      // "resting" position of all currently-rendered DOM nodes.
      // We store that data in this.parent and this.children,
      // so it can be used later to work out the animation.
      this.updateBoundingBoxCaches();

      // Convert opaque children object to array.
      var nextChildren = _react.Children.toArray(nextProps.children);

      // Next, we need to update our state, so that it contains our new set of
      // children. If animation is disabled or unsupported, this is easy;
      // we just copy our props into state.
      // Assuming that we can animate, though, we have to do some work.
      // Essentially, we want to keep just-deleted nodes in the DOM for a bit
      // longer, so that we can animate them away.
      this.setState({
        children: this.isAnimationDisabled(nextProps) ? nextChildren.map(function (element) {
          return _extends({}, element, { element: element });
        }) : this.calculateNextSetOfChildren(nextChildren)
      });
    }
  }, {
    key: 'componentDidUpdate',
    value: function componentDidUpdate(previousProps) {
      // If the children have been re-arranged, moved, or added/removed,
      // trigger the main FLIP animation.
      //
      // IMPORTANT: We need to make sure that the children have actually changed.
      // At the end of the transition, we clean up nodes that need to be removed.
      var oldChildrenKeys = _react.Children.toArray(this.props.children).map(function (d) {
        return d.key;
      });
      var nextChildrenKeys = _react.Children.toArray(previousProps.children).map(function (d) {
        return d.key;
      });

      var shouldTriggerFLIP = !(0, _helpers.arraysEqual)(oldChildrenKeys, nextChildrenKeys) && !this.isAnimationDisabled(this.props);

      if (shouldTriggerFLIP) {
        this.prepForAnimation();
        this.runAnimation();
      }
    }
  }, {
    key: 'calculateNextSetOfChildren',
    value: function calculateNextSetOfChildren(nextChildren) {
      var _this2 = this;

      // We want to:
      //   - Mark all new children as `entering`
      //   - Pull in previous children that aren't in nextChildren, and mark them
      //     as `leaving`
      //   - Preserve the nextChildren list order, with leaving children in their
      //     appropriate places.
      //

      var updatedChildren = nextChildren.map(function (nextChild) {
        var child = _this2.findChildByKey(nextChild.key || '');

        // If the current child did exist, but it was in the midst of leaving,
        // we want to treat it as though it's entering
        var isEntering = !child || child.leaving;

        return _extends({}, nextChild, { element: nextChild, entering: isEntering });
      });

      // This is tricky. We want to keep the nextChildren's ordering, but with
      // any just-removed items maintaining their original position.
      // eg.
      //   this.state.children  = [ 1, 2, 3, 4 ]
      //   nextChildren         = [ 3, 1 ]
      //
      // In this example, we've removed the '2' & '4'
      // We want to end up with:  [ 2, 3, 1, 4 ]
      //
      // To accomplish that, we'll iterate through this.state.children. whenever
      // we find a match, we'll append our `leaving` flag to it, and insert it
      // into the nextChildren in its ORIGINAL position. Note that, as we keep
      // inserting old items into the new list, the "original" position will
      // keep incrementing.
      var numOfChildrenLeaving = 0;
      this.state.children.forEach(function (child, index) {
        var isLeaving = !nextChildren.find(function (_ref2) {
          var key = _ref2.key;
          return key === getKey(child);
        });

        // If the child isn't leaving (or, if there is no leave animation),
        // we don't need to add it into the state children.
        if (!isLeaving || !_this2.props.leaveAnimation) return;

        var nextChild = _extends({}, child, { leaving: true });
        var nextChildIndex = index + numOfChildrenLeaving;

        updatedChildren.splice(nextChildIndex, 0, nextChild);
        numOfChildrenLeaving += 1;
      });

      return updatedChildren;
    }
  }, {
    key: 'prepForAnimation',
    value: function prepForAnimation() {
      var _this3 = this;

      // Our animation prep consists of:
      // - remove children that are leaving from the DOM flow, so that the new
      //   layout can be accurately calculated,
      // - update the placeholder container height, if needed, to ensure that
      //   the parent's height doesn't collapse.

      var _props = this.props;
      var leaveAnimation = _props.leaveAnimation;
      var maintainContainerHeight = _props.maintainContainerHeight;
      var getPosition = _props.getPosition;

      // we need to make all leaving nodes "invisible" to the layout calculations
      // that will take place in the next step (this.runAnimation).

      if (leaveAnimation) {
        var leavingChildren = this.state.children.filter(function (child) {
          return child.leaving;
        });

        leavingChildren.forEach(function (leavingChild) {
          var childData = _this3.getChildData(getKey(leavingChild));

          // We need to take the items out of the "flow" of the document, so that
          // its siblings can move to take its place.
          if (childData.boundingBox) {
            (0, _domManipulation.removeNodeFromDOMFlow)(childData, _this3.props.verticalAlignment);
          }
        });

        if (maintainContainerHeight && this.heightPlaceholderData.domNode) {
          (0, _domManipulation.updateHeightPlaceholder)({
            domNode: this.heightPlaceholderData.domNode,
            parentData: this.parentData,
            getPosition: getPosition
          });
        }
      }

      // For all children not in the middle of entering or leaving,
      // we need to reset the transition, so that the NEW shuffle starts from
      // the right place.
      this.state.children.forEach(function (child) {
        var _getChildData = _this3.getChildData(getKey(child));

        var domNode = _getChildData.domNode;

        // Ignore children that don't render DOM nodes (eg. by returning null)

        if (!domNode) {
          return;
        }

        if (!child.entering && !child.leaving) {
          (0, _domManipulation.applyStylesToDOMNode)({
            domNode: domNode,
            styles: {
              transition: ''
            }
          });
        }
      });
    }
  }, {
    key: 'animateChild',
    value: function animateChild(child, index) {
      var _this4 = this;

      var _getChildData2 = this.getChildData(getKey(child));

      var domNode = _getChildData2.domNode;

      if (!domNode) {
        return;
      }

      // Apply the relevant style for this DOM node
      // This is the offset from its actual DOM position.
      // eg. if an item has been re-rendered 20px lower, we want to apply a
      // style of 'transform: translate(-20px)', so that it appears to be where
      // it started.
      // In FLIP terminology, this is the 'Invert' stage.
      (0, _domManipulation.applyStylesToDOMNode)({
        domNode: domNode,
        styles: this.computeInitialStyles(child)
      });

      // Start by invoking the onStart callback for this child.
      if (this.props.onStart) this.props.onStart(child, domNode);

      // Next, animate the item from it's artificially-offset position to its
      // new, natural position.
      requestAnimationFrame(function () {
        requestAnimationFrame(function () {
          // NOTE, RE: the double-requestAnimationFrame:
          // Sadly, this is the most browser-compatible way to do this I've found.
          // Essentially we need to set the initial styles outside of any request
          // callbacks to avoid batching them. Then, a frame needs to pass with
          // the styles above rendered. Then, on the second frame, we can apply
          // our final styles to perform the animation.

          // Our first order of business is to "undo" the styles applied in the
          // previous frames, while also adding a `transition` property.
          // This way, the item will smoothly transition from its old position
          // to its new position.

          // eslint-disable-next-line flowtype/require-variable-type
          var styles = {
            transition: (0, _domManipulation.createTransitionString)(index, _this4.props),
            transform: '',
            opacity: ''
          };

          if (child.appearing && _this4.props.appearAnimation) {
            styles = _extends({}, styles, _this4.props.appearAnimation.to);
          } else if (child.entering && _this4.props.enterAnimation) {
            styles = _extends({}, styles, _this4.props.enterAnimation.to);
          } else if (child.leaving && _this4.props.leaveAnimation) {
            styles = _extends({}, styles, _this4.props.leaveAnimation.to);
          }

          // In FLIP terminology, this is the 'Play' stage.
          (0, _domManipulation.applyStylesToDOMNode)({ domNode: domNode, styles: styles });
        });
      });

      this.bindTransitionEndHandler(child);
    }
  }, {
    key: 'bindTransitionEndHandler',
    value: function bindTransitionEndHandler(child) {
      var _this5 = this;

      var _getChildData3 = this.getChildData(getKey(child));

      var domNode = _getChildData3.domNode;

      if (!domNode) {
        return;
      }

      // The onFinish callback needs to be bound to the transitionEnd event.
      // We also need to unbind it when the transition completes, so this ugly
      // inline function is required (we need it here so it closes over
      // dependent variables `child` and `domNode`)
      var transitionEndHandler = function transitionEndHandler(ev) {
        // It's possible that this handler is fired not on our primary transition,
        // but on a nested transition (eg. a hover effect). Ignore these cases.
        if (ev.target !== domNode) return;

        // Remove the 'transition' inline style we added. This is cleanup.
        domNode.style.transition = '';

        // Trigger any applicable onFinish/onFinishAll hooks
        _this5.triggerFinishHooks(child, domNode);

        domNode.removeEventListener(transitionEnd, transitionEndHandler);

        if (child.leaving) {
          _this5.removeChildData(getKey(child));
        }
      };

      domNode.addEventListener(transitionEnd, transitionEndHandler);
    }
  }, {
    key: 'triggerFinishHooks',
    value: function triggerFinishHooks(child, domNode) {
      var _this6 = this;

      if (this.props.onFinish) this.props.onFinish(child, domNode);

      // Reduce the number of children we need to animate by 1,
      // so that we can tell when all children have finished.
      this.remainingAnimations -= 1;

      if (this.remainingAnimations === 0) {
        // Remove any items from the DOM that have left, and reset `entering`.
        var nextChildren = this.state.children.filter(function (_ref3) {
          var leaving = _ref3.leaving;
          return !leaving;
        }).map(function (item) {
          return _extends({}, item, {
            appearing: false,
            entering: false
          });
        });

        this.setState({ children: nextChildren }, function () {
          if (typeof _this6.props.onFinishAll === 'function') {
            _this6.callChildrenHook(_this6.props.onFinishAll);
          }

          // Reset our variables for the next iteration
          _this6.childrenToAnimate = [];
        });

        // If the placeholder was holding the container open while elements were
        // leaving, we we can now set its height to zero.
        if (this.heightPlaceholderData.domNode) {
          this.heightPlaceholderData.domNode.style.height = '0';
        }
      }
    }
  }, {
    key: 'callChildrenHook',
    value: function callChildrenHook(hook) {
      var _this7 = this;

      var elements = [];
      var domNodes = [];

      this.childrenToAnimate.forEach(function (childKey) {
        // If this was an exit animation, the child may no longer exist.
        // If so, skip it.
        var child = _this7.findChildByKey(childKey);

        if (!child) {
          return;
        }

        elements.push(child);

        if (_this7.hasChildData(childKey)) {
          domNodes.push(_this7.getChildData(childKey).domNode);
        }
      });

      hook(elements, domNodes);
    }
  }, {
    key: 'updateBoundingBoxCaches',
    value: function updateBoundingBoxCaches() {
      var _this8 = this;

      // This is the ONLY place that parentData and childrenData's
      // bounding boxes are updated. They will be calculated at other times
      // to be compared to this value, but it's important that the cache is
      // updated once per update.
      var parentDomNode = this.parentData.domNode;

      if (!parentDomNode) {
        return;
      }

      this.parentData.boundingBox = this.props.getPosition(parentDomNode);

      this.state.children.forEach(function (child) {
        var childKey = getKey(child);

        // It is possible that a child does not have a `key` property;
        // Ignore these children, they don't need to be moved.
        if (!childKey) {
          return;
        }

        // In very rare circumstances, for reasons unknown, the ref is never
        // populated for certain children. In this case, avoid doing this update.
        // see: https://github.com/joshwcomeau/react-flip-move/pull/91
        if (!_this8.hasChildData(childKey)) {
          return;
        }

        var childData = _this8.getChildData(childKey);

        // If the child element returns null, we need to avoid trying to
        // account for it
        if (!childData.domNode || !child) {
          return;
        }

        _this8.setChildData(childKey, {
          boundingBox: (0, _domManipulation.getRelativeBoundingBox)({
            childDomNode: childData.domNode,
            parentDomNode: parentDomNode,
            getPosition: _this8.props.getPosition
          })
        });
      });
    }
  }, {
    key: 'computeInitialStyles',
    value: function computeInitialStyles(child) {
      if (child.appearing) {
        return this.props.appearAnimation ? this.props.appearAnimation.from : {};
      } else if (child.entering) {
        if (!this.props.enterAnimation) {
          return {};
        }
        // If this child was in the middle of leaving, it still has its
        // absolute positioning styles applied. We need to undo those.
        return _extends({
          position: '',
          top: '',
          left: '',
          right: '',
          bottom: ''
        }, this.props.enterAnimation.from);
      } else if (child.leaving) {
        return this.props.leaveAnimation ? this.props.leaveAnimation.from : {};
      }

      var childData = this.getChildData(getKey(child));
      var childDomNode = childData.domNode;
      var childBoundingBox = childData.boundingBox;
      var parentBoundingBox = this.parentData.boundingBox;

      if (!childDomNode) {
        return {};
      }

      var _getPositionDelta3 = (0, _domManipulation.getPositionDelta)({
        childDomNode: childDomNode,
        childBoundingBox: childBoundingBox,
        parentBoundingBox: parentBoundingBox,
        getPosition: this.props.getPosition
      });

      var _getPositionDelta4 = _slicedToArray(_getPositionDelta3, 2);

      var dX = _getPositionDelta4[0];
      var dY = _getPositionDelta4[1];


      return {
        transform: 'translate(' + dX + 'px, ' + dY + 'px)'
      };
    }

    // eslint-disable-next-line class-methods-use-this

  }, {
    key: 'isAnimationDisabled',
    value: function isAnimationDisabled(props) {
      // If the component is explicitly passed a `disableAllAnimations` flag,
      // we can skip this whole process. Similarly, if all of the numbers have
      // been set to 0, there is no point in trying to animate; doing so would
      // only cause a flicker (and the intent is probably to disable animations)
      // We can also skip this rigamarole if there's no browser support for it.
      return noBrowserSupport || props.disableAllAnimations || props.duration === 0 && props.delay === 0 && props.staggerDurationBy === 0 && props.staggerDelayBy === 0;
    }
  }, {
    key: 'findChildByKey',
    value: function findChildByKey(key) {
      return this.state.children.find(function (child) {
        return getKey(child) === key;
      });
    }
  }, {
    key: 'hasChildData',
    value: function hasChildData(key) {
      // Object has some built-in properties on its prototype, such as toString.  hasOwnProperty makes
      // sure that key is present on childrenData itself, not on its prototype.
      return Object.prototype.hasOwnProperty.call(this.childrenData, key);
    }
  }, {
    key: 'getChildData',
    value: function getChildData(key) {
      return this.hasChildData(key) ? this.childrenData[key] : {};
    }
  }, {
    key: 'setChildData',
    value: function setChildData(key, data) {
      this.childrenData[key] = _extends({}, this.getChildData(key), data);
    }
  }, {
    key: 'removeChildData',
    value: function removeChildData(key) {
      delete this.childrenData[key];
      this.setState(function (prevState) {
        return {
          children: prevState.children.filter(function (child) {
            return child.key !== key;
          })
        };
      });
    }
  }, {
    key: 'createHeightPlaceholder',
    value: function createHeightPlaceholder() {
      var _this9 = this;

      var typeName = this.props.typeName;

      // If requested, create an invisible element at the end of the list.
      // Its height will be modified to prevent the container from collapsing
      // prematurely.

      var isContainerAList = typeName === 'ul' || typeName === 'ol';
      var placeholderType = isContainerAList ? 'li' : 'div';

      return _react2.default.createElement(placeholderType, {
        key: 'height-placeholder',
        ref: function ref(domNode) {
          _this9.heightPlaceholderData.domNode = domNode;
        },
        style: { visibility: 'hidden', height: 0 }
      });
    }
  }, {
    key: 'childrenWithRefs',
    value: function childrenWithRefs() {
      var _this10 = this;

      // We need to clone the provided children, capturing a reference to the
      // underlying DOM node. Flip Move needs to use the React escape hatches to
      // be able to do its calculations.
      return this.state.children.map(function (child) {
        return _react2.default.cloneElement(child.element, {
          ref: function ref(element) {
            // Stateless Functional Components are not supported by FlipMove,
            // because they don't have instances.
            if (!element) {
              return;
            }

            var domNode = (0, _domManipulation.getNativeNode)(element);
            _this10.setChildData(getKey(child), { domNode: domNode });
          }
        });
      });
    }
  }, {
    key: 'render',
    value: function render() {
      var _this11 = this;

      var _props2 = this.props;
      var typeName = _props2.typeName;
      var delegated = _props2.delegated;
      var leaveAnimation = _props2.leaveAnimation;
      var maintainContainerHeight = _props2.maintainContainerHeight;


      var props = _extends({}, delegated, {
        ref: function ref(node) {
          _this11.parentData.domNode = node;
        }
      });

      var children = this.childrenWithRefs();
      if (leaveAnimation && maintainContainerHeight) {
        children.push(this.createHeightPlaceholder());
      }

      return _react2.default.createElement(typeName, props, children);
    }
  }]);

  return FlipMove;
}(_react.Component);

exports.default = (0, _propConverter2.default)(FlipMove);
module.exports = exports['default'];

/***/ }),
/* 99 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


// @noflow
/**
 * React Flip Move - Polyfills
 * (c) 2016-present Joshua Comeau
 */

/* eslint-disable */

if (!Array.prototype.find) {
  Array.prototype.find = function (predicate) {
    if (this === null) {
      throw new TypeError('Array.prototype.find called on null or undefined');
    }
    if (typeof predicate !== 'function') {
      throw new TypeError('predicate must be a function');
    }
    var list = Object(this);
    var length = list.length >>> 0;
    var thisArg = arguments[1];
    var value = void 0;

    for (var i = 0; i < length; i++) {
      value = list[i];
      if (predicate.call(thisArg, value, i, list)) {
        return value;
      }
    }
    return undefined;
  };
}

if (!Array.prototype.every) {
  Array.prototype.every = function (callbackfn, thisArg) {
    'use strict';

    var T, k;

    if (this == null) {
      throw new TypeError('this is null or not defined');
    }

    var O = Object(this);
    var len = O.length >>> 0;

    if (typeof callbackfn !== 'function') {
      throw new TypeError();
    }

    if (arguments.length > 1) {
      T = thisArg;
    }

    k = 0;

    while (k < len) {

      var kValue;

      if (k in O) {
        kValue = O[k];

        var testResult = callbackfn.call(T, kValue, k, O);

        if (!testResult) {
          return false;
        }
      }
      k++;
    }
    return true;
  };
}

if (!Array.isArray) {
  Array.isArray = function (arg) {
    return Object.prototype.toString.call(arg) === '[object Array]';
  };
}

/***/ }),
/* 100 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
  value: true
});

var _typeof = typeof Symbol === "function" && typeof Symbol.iterator === "symbol" ? function (obj) { return typeof obj; } : function (obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; };

var _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; };

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _react = __webpack_require__(0);

var _react2 = _interopRequireDefault(_react);

var _errorMessages = __webpack_require__(101);

var _enterLeavePresets = __webpack_require__(102);

var _helpers = __webpack_require__(65);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }
/**
 * React Flip Move | propConverter
 * (c) 2016-present Joshua Comeau
 *
 * Abstracted away a bunch of the messy business with props.
 *   - props flow types and defaultProps
 *   - Type conversion (We accept 'string' and 'number' values for duration,
 *     delay, and other fields, but we actually need them to be ints.)
 *   - Children conversion (we need the children to be an array. May not always
 *     be, if a single child is passed in.)
 *   - Resolving animation presets into their base CSS styles
 */
/* eslint-disable block-scoped-var */

var nodeEnv = void 0;
try {
  nodeEnv = "development";
} catch (e) {
  nodeEnv = 'development';
}

function propConverter(ComposedComponent) {
  var _class, _temp;

  return _temp = _class = function (_Component) {
    _inherits(FlipMovePropConverter, _Component);

    function FlipMovePropConverter() {
      _classCallCheck(this, FlipMovePropConverter);

      return _possibleConstructorReturn(this, (FlipMovePropConverter.__proto__ || Object.getPrototypeOf(FlipMovePropConverter)).apply(this, arguments));
    }

    _createClass(FlipMovePropConverter, [{
      key: 'checkForStatelessFunctionalComponents',


      // eslint-disable-next-line class-methods-use-this
      value: function checkForStatelessFunctionalComponents(children) {
        // Skip all console warnings in production.
        // Bail early, to avoid unnecessary work.
        if (nodeEnv === 'production') {
          return;
        }

        // FlipMove does not support stateless functional components.
        // Check to see if any supplied components won't work.
        // If the child doesn't have a key, it means we aren't animating it.
        // It's allowed to be an SFC, since we ignore it.
        var childArray = _react.Children.toArray(children);
        var noStateless = childArray.every(function (child) {
          return !(0, _helpers.isElementAnSFC)(child) || typeof child.key === 'undefined';
        });

        if (!noStateless) {
          (0, _errorMessages.statelessFunctionalComponentSupplied)();
        }
      }
    }, {
      key: 'convertProps',
      value: function convertProps(props) {
        var workingProps = {
          // explicitly bypass the props that don't need conversion
          children: props.children,
          easing: props.easing,
          onStart: props.onStart,
          onFinish: props.onFinish,
          onStartAll: props.onStartAll,
          onFinishAll: props.onFinishAll,
          typeName: props.typeName,
          disableAllAnimations: props.disableAllAnimations,
          getPosition: props.getPosition,
          maintainContainerHeight: props.maintainContainerHeight,
          verticalAlignment: props.verticalAlignment,

          // Do string-to-int conversion for all timing-related props
          duration: this.convertTimingProp('duration'),
          delay: this.convertTimingProp('delay'),
          staggerDurationBy: this.convertTimingProp('staggerDurationBy'),
          staggerDelayBy: this.convertTimingProp('staggerDelayBy'),

          // Our enter/leave animations can be specified as boolean (default or
          // disabled), string (preset name), or object (actual animation values).
          // Let's standardize this so that they're always objects
          appearAnimation: this.convertAnimationProp(props.appearAnimation, _enterLeavePresets.appearPresets),
          enterAnimation: this.convertAnimationProp(props.enterAnimation, _enterLeavePresets.enterPresets),
          leaveAnimation: this.convertAnimationProp(props.leaveAnimation, _enterLeavePresets.leavePresets),

          delegated: {}
        };

        this.checkForStatelessFunctionalComponents(workingProps.children);

        // Accept `disableAnimations`, but add a deprecation warning
        if (typeof props.disableAnimations !== 'undefined') {
          if (nodeEnv !== 'production') {
            (0, _errorMessages.deprecatedDisableAnimations)();
          }

          workingProps.disableAllAnimations = props.disableAnimations;
        }

        // Gather any additional props;
        // they will be delegated to the ReactElement created.
        var primaryPropKeys = Object.keys(workingProps);
        var delegatedProps = (0, _helpers.omit)(this.props, primaryPropKeys);

        // The FlipMove container element needs to have a non-static position.
        // We use `relative` by default, but it can be overridden by the user.
        // Now that we're delegating props, we need to merge this in.
        delegatedProps.style = _extends({
          position: 'relative'
        }, delegatedProps.style);

        workingProps.delegated = delegatedProps;

        return workingProps;
      }
    }, {
      key: 'convertTimingProp',
      value: function convertTimingProp(prop) {
        var rawValue = this.props[prop];

        var value = typeof rawValue === 'number' ? rawValue : parseInt(rawValue, 10);

        if (isNaN(value)) {
          var defaultValue = FlipMovePropConverter.defaultProps[prop];

          if (nodeEnv !== 'production') {
            (0, _errorMessages.invalidTypeForTimingProp)({
              prop: prop,
              value: rawValue,
              defaultValue: defaultValue
            });
          }

          return defaultValue;
        }

        return value;
      }

      // eslint-disable-next-line class-methods-use-this

    }, {
      key: 'convertAnimationProp',
      value: function convertAnimationProp(animation, presets) {
        switch (typeof animation === 'undefined' ? 'undefined' : _typeof(animation)) {
          case 'boolean':
            {
              // If it's true, we want to use the default preset.
              // If it's false, we want to use the 'none' preset.
              return presets[animation ? _enterLeavePresets.defaultPreset : _enterLeavePresets.disablePreset];
            }

          case 'string':
            {
              var presetKeys = Object.keys(presets);

              if (presetKeys.indexOf(animation) === -1) {
                if (nodeEnv !== 'production') {
                  (0, _errorMessages.invalidEnterLeavePreset)({
                    value: animation,
                    acceptableValues: presetKeys.join(', '),
                    defaultValue: _enterLeavePresets.defaultPreset
                  });
                }

                return presets[_enterLeavePresets.defaultPreset];
              }

              return presets[animation];
            }

          default:
            {
              return animation;
            }
        }
      }
    }, {
      key: 'render',
      value: function render() {
        return _react2.default.createElement(ComposedComponent, this.convertProps(this.props));
      }
    }]);

    return FlipMovePropConverter;
  }(_react.Component), _class.defaultProps = {
    easing: 'ease-in-out',
    duration: 350,
    delay: 0,
    staggerDurationBy: 0,
    staggerDelayBy: 0,
    typeName: 'div',
    enterAnimation: _enterLeavePresets.defaultPreset,
    leaveAnimation: _enterLeavePresets.defaultPreset,
    disableAllAnimations: false,
    getPosition: function getPosition(node) {
      return node.getBoundingClientRect();
    },
    maintainContainerHeight: false,
    verticalAlignment: 'top'
  }, _temp;
}

exports.default = propConverter;
module.exports = exports['default'];

/***/ }),
/* 101 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
  value: true
});


function warnOnce(msg) {
  var hasWarned = false;
  return function () {
    if (!hasWarned) {
      console.warn(msg);
      hasWarned = true;
    }
  };
}
var statelessFunctionalComponentSupplied = exports.statelessFunctionalComponentSupplied = warnOnce('\n>> Error, via react-flip-move <<\n\nYou provided a stateless functional component as a child to <FlipMove>. Unfortunately, SFCs aren\'t supported, because Flip Move needs access to the backing instances via refs, and SFCs don\'t have a public instance that holds that info.\n\nPlease wrap your components in a native element (eg. <div>), or a non-functional component.\n');

var invalidTypeForTimingProp = exports.invalidTypeForTimingProp = function invalidTypeForTimingProp(args) {
  return console.error('\n>> Error, via react-flip-move <<\n\nThe prop you provided for \'' + args.prop + '\' is invalid. It needs to be a positive integer, or a string that can be resolved to a number. The value you provided is \'' + args.value + '\'.\n\nAs a result,  the default value for this parameter will be used, which is \'' + args.defaultValue + '\'.\n');
};

var deprecatedDisableAnimations = exports.deprecatedDisableAnimations = warnOnce('\n>> Warning, via react-flip-move <<\n\nThe \'disableAnimations\' prop you provided is deprecated. Please switch to use \'disableAllAnimations\'.\n\nThis will become a silent error in future versions of react-flip-move.\n');

var invalidEnterLeavePreset = exports.invalidEnterLeavePreset = function invalidEnterLeavePreset(args) {
  return console.error('\n>> Error, via react-flip-move <<\n\nThe enter/leave preset you provided is invalid. We don\'t currently have a \'' + args.value + ' preset.\'\n\nAcceptable values are ' + args.acceptableValues + '. The default value of \'' + args.defaultValue + '\' will be used.\n');
};

/***/ }),
/* 102 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
  value: true
});
var enterPresets = exports.enterPresets = {
  elevator: {
    from: { transform: 'scale(0)', opacity: '0' },
    to: { transform: '', opacity: '' }
  },
  fade: {
    from: { opacity: '0' },
    to: { opacity: '' }
  },
  accordionVertical: {
    from: { transform: 'scaleY(0)', transformOrigin: 'center top' },
    to: { transform: '', transformOrigin: 'center top' }
  },
  accordionHorizontal: {
    from: { transform: 'scaleX(0)', transformOrigin: 'left center' },
    to: { transform: '', transformOrigin: 'left center' }
  },
  none: null
};
/**
 * React Flip Move | enterLeavePresets
 * (c) 2016-present Joshua Comeau
 *
 * This contains the master list of presets available for enter/leave animations,
 * along with the mapping between preset and styles.
 */
var leavePresets = exports.leavePresets = {
  elevator: {
    from: { transform: 'scale(1)', opacity: '1' },
    to: { transform: 'scale(0)', opacity: '0' }
  },
  fade: {
    from: { opacity: '1' },
    to: { opacity: '0' }
  },
  accordionVertical: {
    from: { transform: 'scaleY(1)', transformOrigin: 'center top' },
    to: { transform: 'scaleY(0)', transformOrigin: 'center top' }
  },
  accordionHorizontal: {
    from: { transform: 'scaleX(1)', transformOrigin: 'left center' },
    to: { transform: 'scaleX(0)', transformOrigin: 'left center' }
  },
  none: null
};

// For now, appearPresets will be identical to enterPresets.
// Assigning a custom export in case we ever want to add appear-specific ones.
var appearPresets = exports.appearPresets = enterPresets;

// Embarrassingly enough, v2.0 launched with typo'ed preset names.
// To avoid penning a new major version over something so inconsequential,
// we're supporting both spellings. In a future version, these alternatives
// may be deprecated.
// $FlowFixMe
enterPresets.accordianVertical = enterPresets.accordionVertical;
// $FlowFixMe
enterPresets.accordianHorizontal = enterPresets.accordionHorizontal;
// $FlowFixMe
leavePresets.accordianVertical = leavePresets.accordionVertical;
// $FlowFixMe
leavePresets.accordianHorizontal = leavePresets.accordionHorizontal;

var defaultPreset = exports.defaultPreset = 'elevator';
var disablePreset = exports.disablePreset = 'none';

/***/ }),
/* 103 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
  value: true
});
exports.createTransitionString = exports.getNativeNode = exports.updateHeightPlaceholder = exports.removeNodeFromDOMFlow = exports.getPositionDelta = exports.getRelativeBoundingBox = undefined;

var _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; };
/**
 * React Flip Move
 * (c) 2016-present Joshua Comeau
 *
 * These methods read from and write to the DOM.
 * They almost always have side effects, and will hopefully become the
 * only spot in the codebase with impure functions.
 */


exports.applyStylesToDOMNode = applyStylesToDOMNode;
exports.whichTransitionEvent = whichTransitionEvent;

var _reactDom = __webpack_require__(28);

var _helpers = __webpack_require__(65);

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

function applyStylesToDOMNode(_ref) {
  var domNode = _ref.domNode;
  var styles = _ref.styles;

  // Can't just do an object merge because domNode.styles is no regular object.
  // Need to do it this way for the engine to fire its `set` listeners.
  Object.keys(styles).forEach(function (key) {
    domNode.style.setProperty((0, _helpers.hyphenate)(key), styles[key]);
  });
}

// Modified from Modernizr
function whichTransitionEvent() {
  var transitions = {
    transition: 'transitionend',
    '-o-transition': 'oTransitionEnd',
    '-moz-transition': 'transitionend',
    '-webkit-transition': 'webkitTransitionEnd'
  };

  // If we're running in a browserless environment (eg. SSR), it doesn't apply.
  // Return a placeholder string, for consistent type return.
  if (typeof document === 'undefined') return '';

  var el = document.createElement('fakeelement');

  var match = Object.keys(transitions).find(function (t) {
    return el.style.getPropertyValue(t) !== undefined;
  });

  // If no `transition` is found, we must be running in a browser so ancient,
  // React itself won't run. Return an empty string, for consistent type return
  return match ? transitions[match] : '';
}

var getRelativeBoundingBox = exports.getRelativeBoundingBox = function getRelativeBoundingBox(_ref2) {
  var childDomNode = _ref2.childDomNode;
  var parentDomNode = _ref2.parentDomNode;
  var getPosition = _ref2.getPosition;

  var parentBox = getPosition(parentDomNode);

  var _getPosition = getPosition(childDomNode);

  var top = _getPosition.top;
  var left = _getPosition.left;
  var right = _getPosition.right;
  var bottom = _getPosition.bottom;
  var width = _getPosition.width;
  var height = _getPosition.height;


  return {
    top: top - parentBox.top,
    left: left - parentBox.left,
    right: parentBox.right - right,
    bottom: parentBox.bottom - bottom,
    width: width,
    height: height
  };
};

/** getPositionDelta
 * This method returns the delta between two bounding boxes, to figure out
 * how many pixels on each axis the element has moved.
 *
 */
var getPositionDelta = exports.getPositionDelta = function getPositionDelta(_ref3) {
  var childDomNode = _ref3.childDomNode;
  var childBoundingBox = _ref3.childBoundingBox;
  var parentBoundingBox = _ref3.parentBoundingBox;
  var getPosition = _ref3.getPosition;

  // TEMP: A mystery bug is sometimes causing unnecessary boundingBoxes to
  var defaultBox = { top: 0, left: 0, right: 0, bottom: 0, height: 0, width: 0 };

  // Our old box is its last calculated position, derived on mount or at the
  // start of the previous animation.
  var oldRelativeBox = childBoundingBox || defaultBox;
  var parentBox = parentBoundingBox || defaultBox;

  // Our new box is the new final resting place: Where we expect it to wind up
  // after the animation. First we get the box in absolute terms (AKA relative
  // to the viewport), and then we calculate its relative box (relative to the
  // parent container)
  var newAbsoluteBox = getPosition(childDomNode);
  var newRelativeBox = {
    top: newAbsoluteBox.top - parentBox.top,
    left: newAbsoluteBox.left - parentBox.left
  };

  return [oldRelativeBox.left - newRelativeBox.left, oldRelativeBox.top - newRelativeBox.top];
};

/** removeNodeFromDOMFlow
 * This method does something very sneaky: it removes a DOM node from the
 * document flow, but without actually changing its on-screen position.
 *
 * It works by calculating where the node is, and then applying styles
 * so that it winds up being positioned absolutely, but in exactly the
 * same place.
 *
 * This is a vital part of the FLIP technique.
 */
var removeNodeFromDOMFlow = exports.removeNodeFromDOMFlow = function removeNodeFromDOMFlow(childData, verticalAlignment) {
  var domNode = childData.domNode;
  var boundingBox = childData.boundingBox;


  if (!domNode || !boundingBox) {
    return;
  }

  // For this to work, we have to offset any given `margin`.
  var computed = window.getComputedStyle(domNode);

  // We need to clean up margins, by converting and removing suffix:
  // eg. '21px' -> 21
  var marginAttrs = ['margin-top', 'margin-left', 'margin-right'];
  var margins = marginAttrs.reduce(function (acc, margin) {
    var propertyVal = computed.getPropertyValue(margin);

    return _extends({}, acc, _defineProperty({}, margin, Number(propertyVal.replace('px', ''))));
  }, {});

  // If we're bottom-aligned, we need to add the height of the child to its
  // top offset. This is because, when the container is bottom-aligned, its
  // height shrinks from the top, not the bottom. We're removing this node
  // from the flow, so the top is going to drop by its height.
  var topOffset = verticalAlignment === 'bottom' ? boundingBox.top - boundingBox.height : boundingBox.top;

  var styles = {
    position: 'absolute',
    top: topOffset - margins['margin-top'] + 'px',
    left: boundingBox.left - margins['margin-left'] + 'px',
    right: boundingBox.right - margins['margin-right'] + 'px'
  };

  applyStylesToDOMNode({ domNode: domNode, styles: styles });
};

/** updateHeightPlaceholder
 * An optional property to FlipMove is a `maintainContainerHeight` boolean.
 * This property creates a node that fills space, so that the parent
 * container doesn't collapse when its children are removed from the
 * document flow.
 */
var updateHeightPlaceholder = exports.updateHeightPlaceholder = function updateHeightPlaceholder(_ref4) {
  var domNode = _ref4.domNode;
  var parentData = _ref4.parentData;
  var getPosition = _ref4.getPosition;

  var parentDomNode = parentData.domNode;
  var parentBoundingBox = parentData.boundingBox;

  if (!parentDomNode || !parentBoundingBox) {
    return;
  }

  // We need to find the height of the container *without* the placeholder.
  // Since it's possible that the placeholder might already be present,
  // we first set its height to 0.
  // This allows the container to collapse down to the size of just its
  // content (plus container padding or borders if any).
  applyStylesToDOMNode({ domNode: domNode, styles: { height: '0' } });

  // Find the distance by which the container would be collapsed by elements
  // leaving. We compare the freshly-available parent height with the original,
  // cached container height.
  var originalParentHeight = parentBoundingBox.height;
  var collapsedParentHeight = getPosition(parentDomNode).height;
  var reductionInHeight = originalParentHeight - collapsedParentHeight;

  // If the container has become shorter, update the padding element's
  // height to take up the difference. Otherwise set its height to zero,
  // so that it has no effect.
  var styles = {
    height: reductionInHeight > 0 ? reductionInHeight + 'px' : '0'
  };

  applyStylesToDOMNode({ domNode: domNode, styles: styles });
};

var getNativeNode = exports.getNativeNode = function getNativeNode(element) {
  // When running in a windowless environment, abort!
  if (typeof HTMLElement === 'undefined') {
    return null;
  }

  // `element` may already be a native node.
  if (element instanceof HTMLElement) {
    return element;
  }

  // While ReactDOM's `findDOMNode` is discouraged, it's the only
  // publicly-exposed way to find the underlying DOM node for
  // composite components.
  var foundNode = (0, _reactDom.findDOMNode)(element);

  if (!(foundNode instanceof HTMLElement)) {
    // Text nodes are not supported
    return null;
  }

  return foundNode;
};

var createTransitionString = exports.createTransitionString = function createTransitionString(index, props) {
  var delay = props.delay;
  var duration = props.duration;
  var staggerDurationBy = props.staggerDurationBy;
  var staggerDelayBy = props.staggerDelayBy;
  var easing = props.easing;


  delay += index * staggerDelayBy;
  duration += index * staggerDurationBy;

  var cssProperties = ['transform', 'opacity'];

  return cssProperties.map(function (prop) {
    return prop + ' ' + duration + 'ms ' + easing + ' ' + delay + 'ms';
  }).join(', ');
};

/***/ }),
/* 104 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_react__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_react___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0_react__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_react_redux__ = __webpack_require__(2);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__AppTitle__ = __webpack_require__(86);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3__BodyPanel__ = __webpack_require__(105);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_4__FlashMessages__ = __webpack_require__(117);






// smart component with redux connect

var MainPannel = function MainPannel(_ref) {
    var options = _ref.options;
    return __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
        'div',
        null,
        __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(__WEBPACK_IMPORTED_MODULE_2__AppTitle__["a" /* default */], null),
        __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(__WEBPACK_IMPORTED_MODULE_4__FlashMessages__["a" /* default */], null),
        __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(__WEBPACK_IMPORTED_MODULE_3__BodyPanel__["a" /* default */], null)
    );
};

// map state
function mapStateToProps(state) {
    return {
        options: state.settings.options
    };
}

// map dispatch
function mapDispatchToProps(dispatch) {
    return {};
}

// connect and export
/* harmony default export */ __webpack_exports__["a"] = (Object(__WEBPACK_IMPORTED_MODULE_1_react_redux__["b" /* connect */])(mapStateToProps, mapDispatchToProps)(MainPannel));

/***/ }),
/* 105 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_react__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_react___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0_react__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_react_redux__ = __webpack_require__(2);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__InstanceList__ = __webpack_require__(106);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3__SettingsPanel__ = __webpack_require__(116);
var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }






var BodyPanel = function (_React$Component) {
    _inherits(BodyPanel, _React$Component);

    function BodyPanel() {
        _classCallCheck(this, BodyPanel);

        return _possibleConstructorReturn(this, (BodyPanel.__proto__ || Object.getPrototypeOf(BodyPanel)).apply(this, arguments));
    }

    _createClass(BodyPanel, [{
        key: 'render',
        value: function render() {
            var show_settings = this.props.show_settings;

            return show_settings ? __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(__WEBPACK_IMPORTED_MODULE_3__SettingsPanel__["a" /* default */], null) : __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(__WEBPACK_IMPORTED_MODULE_2__InstanceList__["a" /* default */], null);
        }
    }]);

    return BodyPanel;
}(__WEBPACK_IMPORTED_MODULE_0_react___default.a.Component);

// map state


function mapStateToProps(state) {
    return {
        show_settings: state.settings.show_settings
    };
}

// map dispatch
function mapDispatchToProps(dispatch) {
    return {
        newAction: function newAction() {
            dispatch({});
        }
    };
}

// connect and export
/* harmony default export */ __webpack_exports__["a"] = (Object(__WEBPACK_IMPORTED_MODULE_1_react_redux__["b" /* connect */])(mapStateToProps, mapDispatchToProps)(BodyPanel));

/***/ }),
/* 106 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_react__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_react___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0_react__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_react_redux__ = __webpack_require__(2);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__InstanceListItem__ = __webpack_require__(107);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3__components_partials_CardLoading__ = __webpack_require__(111);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_4__AddInstanceCard__ = __webpack_require__(113);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_5__actions_instancesAction__ = __webpack_require__(29);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_6_react_flip_move__ = __webpack_require__(64);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_6_react_flip_move___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_6_react_flip_move__);








// smart component with redux connect

var InstanceList = function InstanceList(_ref) {
    var loaded = _ref.loaded,
        instances = _ref.instances,
        saving_instance = _ref.saving_instance,
        cancelUserAlbum = _ref.cancelUserAlbum,
        saveUserAlbum = _ref.saveUserAlbum,
        settings_open = _ref.settings_open;
    return __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
        'div',
        { className: 'row' },
        settings_open ? __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(__WEBPACK_IMPORTED_MODULE_2__InstanceListItem__["a" /* default */], { key: settings_open, instance: instances.find(function (instance) {
                return instance.id == settings_open;
            }) }) : __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
            __WEBPACK_IMPORTED_MODULE_6_react_flip_move___default.a,
            { duration: 500, easing: 'ease-out' },
            __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                'div',
                { key: 'static1' },
                saving_instance ? __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(__WEBPACK_IMPORTED_MODULE_3__components_partials_CardLoading__["a" /* default */], { title: 'Saving' }) : __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(__WEBPACK_IMPORTED_MODULE_4__AddInstanceCard__["a" /* default */], null)
            ),
            !loaded ? __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                'div',
                { key: 'static2' },
                __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(__WEBPACK_IMPORTED_MODULE_3__components_partials_CardLoading__["a" /* default */], { title: 'Loading Albums' })
            ) : instances.map(function (instance) {
                return __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(__WEBPACK_IMPORTED_MODULE_2__InstanceListItem__["a" /* default */], { key: instance.id, instance: instance });
            })
        )
    );
};

// map state
function mapStateToProps(state) {
    return {
        saving_instance: state.settings.saving_instance_in_progress,
        loaded: state.instances.initial_load,
        instances: state.instances.instances,
        settings_open: state.instances.settings_open
    };
}

// map dispatch
function mapDispatchToProps(dispatch) {
    return {
        cancelUserAlbum: function cancelUserAlbum() {
            dispatch(Object(__WEBPACK_IMPORTED_MODULE_5__actions_instancesAction__["a" /* cancelInstance */])());
        },
        saveUserAlbum: function saveUserAlbum(instance) {
            dispatch(Object(__WEBPACK_IMPORTED_MODULE_5__actions_instancesAction__["d" /* saveInstance */])(instance));
        }
    };
}

// connect and export
/* harmony default export */ __webpack_exports__["a"] = (Object(__WEBPACK_IMPORTED_MODULE_1_react_redux__["b" /* connect */])(mapStateToProps, mapDispatchToProps)(InstanceList));

/***/ }),
/* 107 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_react__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_react___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0_react__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_react_redux__ = __webpack_require__(2);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__actions_messagesAction__ = __webpack_require__(63);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3__actions_instancesAction__ = __webpack_require__(29);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_4__AlbumListItemSettings__ = __webpack_require__(108);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_5__components_partials_CircularLoaderRow__ = __webpack_require__(30);
var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }








var AlbumListItem = function (_React$Component) {
    _inherits(AlbumListItem, _React$Component);

    function AlbumListItem() {
        _classCallCheck(this, AlbumListItem);

        return _possibleConstructorReturn(this, (AlbumListItem.__proto__ || Object.getPrototypeOf(AlbumListItem)).apply(this, arguments));
    }

    _createClass(AlbumListItem, [{
        key: 'toggleSettingsForm',
        value: function toggleSettingsForm() {
            if (this.props.settings_open) {
                this.props.settingsClose();
            } else {
                this.props.settingsOpen(this.props.instance.id);
            }
        }
    }, {
        key: 'componentDidMount',
        value: function componentDidMount() {
            Materialize.updateTextFields();
        }
    }, {
        key: 'handleCopy',
        value: function handleCopy() {
            var _props = this.props,
                successCopy = _props.successCopy,
                errorCopy = _props.errorCopy;

            this.shortcode.select();
            try {
                var successful = document.execCommand('copy');
                successful ? successCopy() : errorCopy();
            } catch (err) {
                errorCopy();
            }
        }
    }, {
        key: 'render',
        value: function render() {
            var _this2 = this;

            var _props2 = this.props,
                instances_updating = _props2.instances_updating,
                instance = _props2.instance,
                deleteAlbum = _props2.deleteAlbum,
                settings_open = _props2.settings_open;


            var this_instance_updating = false;
            if (instances_updating.indexOf(instance.id) != -1) this_instance_updating = true;
            return __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                'div',
                { className: settings_open == instance.id ? "col s12" : "col s12 l4 m6" },
                __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                    'div',
                    { className: settings_open || this_instance_updating ? "card" : "card small" },
                    __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                        'div',
                        { className: 'card-content' },
                        !settings_open ? __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                            'div',
                            { className: 'row center' },
                            __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                                'span',
                                { className: 'card-title' },
                                instance.title
                            )
                        ) : null,
                        !settings_open ? __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                            'div',
                            { className: 'row plr0 top20' },
                            __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                                'div',
                                { className: 'col s10 pl0' },
                                __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                                    'div',
                                    { className: 'input-field' },
                                    __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement('input', { className: 'grey-text', id: 'shortcode', type: 'text', name: 'shortcode',
                                        value: "[srzmort id=" + instance.id + "]",
                                        onChange: function onChange() {},
                                        ref: function ref(input) {
                                            _this2.shortcode = input;
                                        }
                                    }),
                                    __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                                        'label',
                                        { htmlFor: 'shortcode' },
                                        'ShortCode'
                                    )
                                )
                            ),
                            __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                                'div',
                                { className: 'col s2 pl0 input-align' },
                                __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                                    'div',
                                    { className: 'copy-text blue-text text-darken-3',
                                        onClick: this.handleCopy.bind(this) },
                                    'Copy'
                                )
                            )
                        ) : null,
                        __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                            'div',
                            { className: 'row' },
                            this_instance_updating ? __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(__WEBPACK_IMPORTED_MODULE_5__components_partials_CircularLoaderRow__["a" /* default */], null) : settings_open == instance.id ? __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(__WEBPACK_IMPORTED_MODULE_4__AlbumListItemSettings__["a" /* default */], { instance: instance,
                                cancelForm: this.toggleSettingsForm.bind(this) }) : null
                        )
                    ),
                    !settings_open ? __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                        'div',
                        { className: 'card-action' },
                        __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                            'a',
                            { className: 'no-transform blue-text', onClick: this.toggleSettingsForm.bind(this) },
                            settings_open == instance.id ? "Hide Settings" : "Show Settings"
                        ),
                        __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                            'a',
                            { className: 'right mlr0', onClick: function onClick() {
                                    deleteAlbum(instance.id);
                                } },
                            __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                                'i',
                                {
                                    className: 'material-icons red-icon' },
                                'delete'
                            )
                        )
                    ) : null
                )
            );
        }
    }]);

    return AlbumListItem;
}(__WEBPACK_IMPORTED_MODULE_0_react___default.a.Component);

// map state


function mapStateToProps(state) {
    return {
        instances_updating: state.instances.instances_updating,
        settings_open: state.instances.settings_open
    };
}

// map dispatch
function mapDispatchToProps(dispatch) {
    return {
        successCopy: function successCopy() {
            dispatch(Object(__WEBPACK_IMPORTED_MODULE_2__actions_messagesAction__["e" /* successCopy */])());
        },
        errorCopy: function errorCopy() {
            dispatch(Object(__WEBPACK_IMPORTED_MODULE_2__actions_messagesAction__["a" /* errorCopy */])());
        },
        deleteAlbum: function deleteAlbum(id) {
            dispatch(Object(__WEBPACK_IMPORTED_MODULE_3__actions_instancesAction__["b" /* deleteInstance */])(id));
        },
        settingsOpen: function settingsOpen(id) {
            dispatch(Object(__WEBPACK_IMPORTED_MODULE_3__actions_instancesAction__["f" /* settingsOpen */])(id));
        },
        settingsClose: function settingsClose() {
            dispatch(Object(__WEBPACK_IMPORTED_MODULE_3__actions_instancesAction__["e" /* settingsClose */])());
        }
    };
}
// connect and export
/* harmony default export */ __webpack_exports__["a"] = (Object(__WEBPACK_IMPORTED_MODULE_1_react_redux__["b" /* connect */])(mapStateToProps, mapDispatchToProps)(AlbumListItem));

/***/ }),
/* 108 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_react__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_react___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0_react__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_react_redux__ = __webpack_require__(2);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__components_form_TextField__ = __webpack_require__(8);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3__components_SettingsForm__ = __webpack_require__(87);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_4__actions_instancesAction__ = __webpack_require__(29);
var _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; };

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }







var AlbumListItemSettings = function (_React$Component) {
    _inherits(AlbumListItemSettings, _React$Component);

    function AlbumListItemSettings(props) {
        _classCallCheck(this, AlbumListItemSettings);

        var _this = _possibleConstructorReturn(this, (AlbumListItemSettings.__proto__ || Object.getPrototypeOf(AlbumListItemSettings)).call(this, props));

        _this.state = _extends({}, _this.props.instance.options, { title: _this.props.instance.title });
        _this.hich = _this.hich.bind(_this);
        return _this;
    }

    _createClass(AlbumListItemSettings, [{
        key: 'componentDidMount',
        value: function componentDidMount() {
            Materialize.updateTextFields();
            jQuery('select').material_select();
        }
    }, {
        key: 'componentDidUpdate',
        value: function componentDidUpdate() {
            Materialize.updateTextFields();
            jQuery('select').material_select();
        }
    }, {
        key: 'hich',
        value: function hich(event) {
            var target = event.target;
            var value = target.type === 'checkbox' ? target.checked : target.value;
            var name = target.name;

            this.setState(_defineProperty({}, name, value));
        }
    }, {
        key: 'render',
        value: function render() {
            var _this2 = this;

            var _props = this.props,
                instance = _props.instance,
                updateAlbum = _props.updateAlbum,
                cancelForm = _props.cancelForm;

            return __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                'div',
                { className: 'row bottom0' },
                __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                    'div',
                    { className: 'col s12 plr0' },
                    __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                        'div',
                        { className: 'row bottom40' },
                        __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                            'div',
                            { className: 'col s6 plr0' },
                            __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                                'button',
                                { className: 'btn btn-small green left',
                                    onClick: function onClick() {
                                        cancelForm();updateAlbum(instance.id, _this2.state);
                                    } },
                                'Save'
                            )
                        ),
                        __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                            'div',
                            { className: 'col s6 plr0' },
                            __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                                'button',
                                { className: 'btn btn-small right grey', onClick: cancelForm },
                                'Cancel'
                            )
                        )
                    ),
                    __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(__WEBPACK_IMPORTED_MODULE_2__components_form_TextField__["a" /* default */], { val: this.state.title, onch: this.hich, name: 'title',
                        label: 'Title' }),
                    __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(__WEBPACK_IMPORTED_MODULE_3__components_SettingsForm__["a" /* default */], { hich: this.hich, pstate: this.state }),
                    __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                        'div',
                        { className: 'row top30 bottom0' },
                        __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                            'div',
                            { className: 'col s6 plr0' },
                            __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                                'button',
                                { className: 'btn btn-small green left',
                                    onClick: function onClick() {
                                        cancelForm();updateAlbum(instance.id, _this2.state);
                                    } },
                                'Save'
                            )
                        ),
                        __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                            'div',
                            { className: 'col s6 plr0' },
                            __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                                'button',
                                { className: 'btn btn-small right grey', onClick: cancelForm },
                                'Cancel'
                            )
                        )
                    )
                )
            );
        }
    }]);

    return AlbumListItemSettings;
}(__WEBPACK_IMPORTED_MODULE_0_react___default.a.Component);

// map state


function mapStateToProps(state) {
    return {
        instances_updating: state.instances.instances_updating
    };
}

// map dispatch
function mapDispatchToProps(dispatch) {
    return {
        updateAlbum: function updateAlbum(id, settings) {
            dispatch(Object(__WEBPACK_IMPORTED_MODULE_4__actions_instancesAction__["g" /* updateInstance */])(id, settings));
        }
    };
}

// connect and export
/* harmony default export */ __webpack_exports__["a"] = (Object(__WEBPACK_IMPORTED_MODULE_1_react_redux__["b" /* connect */])(mapStateToProps, mapDispatchToProps)(AlbumListItemSettings));

/***/ }),
/* 109 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_react__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_react___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0_react__);


// dumb component ... only props and presentation
var RadioField = function RadioField(_ref) {
    var val = _ref.val,
        curval = _ref.curval,
        onch = _ref.onch,
        name = _ref.name,
        label = _ref.label,
        aclass = _ref.aclass;
    return __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
        "p",
        { className: aclass },
        __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement("input", { type: "radio", name: name, id: val, onChange: onch,
            value: val, checked: curval == val }),
        __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
            "label",
            { htmlFor: val },
            label
        )
    );
};

/* unused harmony default export */ var _unused_webpack_default_export = (RadioField);

/***/ }),
/* 110 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_react__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_react___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0_react__);


var SwitchField = function SwitchField(_ref) {
    var val = _ref.val,
        onch = _ref.onch,
        name = _ref.name,
        label = _ref.label,
        aclass = _ref.aclass,
        _ref$offtext = _ref.offtext,
        offtext = _ref$offtext === undefined ? 'Disabled' : _ref$offtext,
        _ref$ontext = _ref.ontext,
        ontext = _ref$ontext === undefined ? 'Enabled' : _ref$ontext;
    return __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
        'div',
        { className: aclass },
        __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
            'p',
            null,
            label
        ),
        __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
            'div',
            { className: 'switch' },
            __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                'label',
                null,
                offtext,
                __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement('input', { type: 'checkbox', name: name, onChange: onch, checked: val }),
                __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement('span', {
                    className: 'lever' }),
                ontext
            )
        )
    );
};

/* harmony default export */ __webpack_exports__["a"] = (SwitchField);

/***/ }),
/* 111 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_react__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_react___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0_react__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__CircularLoaderCard__ = __webpack_require__(112);



var CardLoading = function CardLoading(_ref) {
    var title = _ref.title;
    return __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
        'div',
        { className: 'col s12 l4 m6' },
        __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
            'div',
            { className: 'card small' },
            __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                'div',
                { className: 'card-content' },
                title ? __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                    'div',
                    { className: 'center' },
                    __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                        'h5',
                        { className: 'thin' },
                        title
                    )
                ) : '',
                __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(__WEBPACK_IMPORTED_MODULE_1__CircularLoaderCard__["a" /* default */], null)
            )
        )
    );
};

// connect and export
/* harmony default export */ __webpack_exports__["a"] = (CardLoading);

/***/ }),
/* 112 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_react__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_react___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0_react__);


var CircularLoaderCard = function CircularLoaderCard() {
    return __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
        "div",
        null,
        __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
            "div",
            { className: "center top50" },
            __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                "div",
                { className: "preloader-wrapper big active" },
                __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                    "div",
                    { className: "spinner-layer spinner-blue" },
                    __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                        "div",
                        { className: "circle-clipper left" },
                        __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement("div", { className: "circle" })
                    ),
                    __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                        "div",
                        { className: "gap-patch" },
                        __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement("div", { className: "circle" })
                    ),
                    __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                        "div",
                        { className: "circle-clipper right" },
                        __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement("div", { className: "circle" })
                    )
                ),
                __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                    "div",
                    { className: "spinner-layer spinner-red" },
                    __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                        "div",
                        { className: "circle-clipper left" },
                        __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement("div", { className: "circle" })
                    ),
                    __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                        "div",
                        { className: "gap-patch" },
                        __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement("div", { className: "circle" })
                    ),
                    __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                        "div",
                        { className: "circle-clipper right" },
                        __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement("div", { className: "circle" })
                    )
                ),
                __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                    "div",
                    { className: "spinner-layer spinner-yellow" },
                    __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                        "div",
                        { className: "circle-clipper left" },
                        __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement("div", { className: "circle" })
                    ),
                    __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                        "div",
                        { className: "gap-patch" },
                        __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement("div", { className: "circle" })
                    ),
                    __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                        "div",
                        { className: "circle-clipper right" },
                        __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement("div", { className: "circle" })
                    )
                ),
                __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                    "div",
                    { className: "spinner-layer spinner-green" },
                    __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                        "div",
                        { className: "circle-clipper left" },
                        __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement("div", { className: "circle" })
                    ),
                    __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                        "div",
                        { className: "gap-patch" },
                        __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement("div", { className: "circle" })
                    ),
                    __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                        "div",
                        { className: "circle-clipper right" },
                        __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement("div", { className: "circle" })
                    )
                )
            )
        )
    );
};

/* harmony default export */ __webpack_exports__["a"] = (CircularLoaderCard);

/***/ }),
/* 113 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_react__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_react___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0_react__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_react_redux__ = __webpack_require__(2);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__actions_instancesAction__ = __webpack_require__(29);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3__components_add_new_AddInstanceFront__ = __webpack_require__(114);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_4__components_add_new_AddInstanceForm__ = __webpack_require__(115);
var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }







// smart component with redux connect

var AddInstanceCard = function (_React$Component) {
    _inherits(AddInstanceCard, _React$Component);

    function AddInstanceCard() {
        _classCallCheck(this, AddInstanceCard);

        return _possibleConstructorReturn(this, (AddInstanceCard.__proto__ || Object.getPrototypeOf(AddInstanceCard)).apply(this, arguments));
    }

    _createClass(AddInstanceCard, [{
        key: 'render',
        value: function render() {
            var _props = this.props,
                newUserAlbum = _props.newUserAlbum,
                cancelUserAlbum = _props.cancelUserAlbum,
                open_form = _props.open_form,
                saveUserAlbum = _props.saveUserAlbum;

            return __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                'div',
                { className: 'col s12 l4 m6' },
                !open_form ? __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(__WEBPACK_IMPORTED_MODULE_3__components_add_new_AddInstanceFront__["a" /* default */], { newUserAlbum: newUserAlbum }) : __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(__WEBPACK_IMPORTED_MODULE_4__components_add_new_AddInstanceForm__["a" /* default */], { cancelUserAlbum: cancelUserAlbum, saveUserAlbum: saveUserAlbum })
            );
        }
    }]);

    return AddInstanceCard;
}(__WEBPACK_IMPORTED_MODULE_0_react___default.a.Component);

// map state


function mapStateToProps(state) {
    return {
        open_form: state.settings.open_instance_form
    };
}

// map dispatch
function mapDispatchToProps(dispatch) {
    return {
        newUserAlbum: function newUserAlbum() {
            dispatch(Object(__WEBPACK_IMPORTED_MODULE_2__actions_instancesAction__["c" /* newInstance */])());
        },
        cancelUserAlbum: function cancelUserAlbum() {
            dispatch(Object(__WEBPACK_IMPORTED_MODULE_2__actions_instancesAction__["a" /* cancelInstance */])());
        },
        saveUserAlbum: function saveUserAlbum(data) {
            dispatch(Object(__WEBPACK_IMPORTED_MODULE_2__actions_instancesAction__["d" /* saveInstance */])(data));
        }
    };
}

// connect and export
/* harmony default export */ __webpack_exports__["a"] = (Object(__WEBPACK_IMPORTED_MODULE_1_react_redux__["b" /* connect */])(mapStateToProps, mapDispatchToProps)(AddInstanceCard));

/***/ }),
/* 114 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_react__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_react___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0_react__);
var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }



var AddCardFront = function (_React$Component) {
    _inherits(AddCardFront, _React$Component);

    function AddCardFront() {
        _classCallCheck(this, AddCardFront);

        return _possibleConstructorReturn(this, (AddCardFront.__proto__ || Object.getPrototypeOf(AddCardFront)).apply(this, arguments));
    }

    _createClass(AddCardFront, [{
        key: "render",
        value: function render() {
            var newUserAlbum = this.props.newUserAlbum;

            return __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                "div",
                { className: "card small clickable", onClick: newUserAlbum },
                __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                    "div",
                    { className: "card-content" },
                    __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                        "div",
                        { className: "row" },
                        __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                            "div",
                            { className: "col s12 center" },
                            __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                                "span",
                                { className: "green-text text-lighten-3 big-icon" },
                                "+"
                            )
                        ),
                        __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                            "div",
                            { className: "s12 center" },
                            __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                                "h5",
                                { className: "thin" },
                                __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                                    "span",
                                    {
                                        className: "green-text text-darken-3" },
                                    "Add New Instance"
                                )
                            )
                        )
                    )
                )
            );
        }
    }]);

    return AddCardFront;
}(__WEBPACK_IMPORTED_MODULE_0_react___default.a.Component);

/* harmony default export */ __webpack_exports__["a"] = (AddCardFront);

/***/ }),
/* 115 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_react__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_react___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0_react__);
var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }



var AddCardForm = function (_React$Component) {
    _inherits(AddCardForm, _React$Component);

    function AddCardForm(props) {
        _classCallCheck(this, AddCardForm);

        var _this = _possibleConstructorReturn(this, (AddCardForm.__proto__ || Object.getPrototypeOf(AddCardForm)).call(this, props));

        _this.state = {
            title: ''
        };
        _this.handleInputChange = _this.handleInputChange.bind(_this);
        _this.handleKeyPress = _this.handleKeyPress.bind(_this);
        _this.handleKeyDown = _this.handleKeyDown.bind(_this);
        return _this;
    }

    _createClass(AddCardForm, [{
        key: 'componentDidMount',
        value: function componentDidMount() {
            Materialize.updateTextFields();
            this.userTitleInput.focus();
        }
    }, {
        key: 'handleInputChange',
        value: function handleInputChange(event) {
            var target = event.target;
            var value = target.type === 'checkbox' ? target.checked : target.value;
            var name = target.name;

            this.setState(_defineProperty({}, name, value));
        }
    }, {
        key: 'handleKeyPress',
        value: function handleKeyPress(event) {
            if (event.key === 'Enter' && this.state.title.trim().length > 0) {
                this.props.saveUserAlbum(this.state);
            }
        }
    }, {
        key: 'handleKeyDown',
        value: function handleKeyDown(event) {
            if (event.keyCode == 27) {
                console.log('escape');
                this.props.cancelUserAlbum();
            }
        }
    }, {
        key: 'render',
        value: function render() {
            var _this2 = this;

            var _props = this.props,
                cancelUserAlbum = _props.cancelUserAlbum,
                saveUserAlbum = _props.saveUserAlbum;

            return __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                'div',
                { className: 'card small' },
                __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                    'div',
                    { className: 'card-content' },
                    __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                        'div',
                        { className: 'input-field col s12' },
                        __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement('input', { id: 'title', type: 'text',
                            name: 'title',
                            value: this.state.title,
                            onChange: this.handleInputChange,
                            onKeyPress: this.handleKeyPress,
                            onKeyDown: this.handleKeyDown,
                            ref: function ref(input) {
                                _this2.userTitleInput = input;
                            }
                        }),
                        __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                            'label',
                            { htmlFor: 'title' },
                            'Instance Title'
                        )
                    ),
                    __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                        'div',
                        { className: 'col s6 top20' },
                        __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                            'button',
                            { className: 'btn grey', onClick: cancelUserAlbum },
                            'Cancel'
                        )
                    ),
                    __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                        'div',
                        { className: 'col s6 top20' },
                        __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                            'button',
                            {
                                onClick: function onClick() {
                                    saveUserAlbum(_this2.state);
                                },
                                className: 'btn green ' + (this.state.title.trim().length < 1 ? 'disabled' : '') },
                            'Submit'
                        )
                    )
                )
            );
        }
    }]);

    return AddCardForm;
}(__WEBPACK_IMPORTED_MODULE_0_react___default.a.Component);

/* harmony default export */ __webpack_exports__["a"] = (AddCardForm);

/***/ }),
/* 116 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_react__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_react___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0_react__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_react_redux__ = __webpack_require__(2);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__components_SettingsForm__ = __webpack_require__(87);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3__actions_settingsAction__ = __webpack_require__(62);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_4__components_partials_CircularLoaderRow__ = __webpack_require__(30);
var _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; };

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }







var SettingsPanel = function (_React$Component) {
    _inherits(SettingsPanel, _React$Component);

    function SettingsPanel(props) {
        _classCallCheck(this, SettingsPanel);

        var _this = _possibleConstructorReturn(this, (SettingsPanel.__proto__ || Object.getPrototypeOf(SettingsPanel)).call(this, props));

        _this.state = _extends({}, _this.props.globalSettings);
        _this.hich = _this.hich.bind(_this);
        return _this;
    }

    _createClass(SettingsPanel, [{
        key: 'componentDidMount',
        value: function componentDidMount() {
            Materialize.updateTextFields();
            jQuery('select').material_select();
        }
    }, {
        key: 'componentDidUpdate',
        value: function componentDidUpdate() {
            Materialize.updateTextFields();
            jQuery('select').material_select();
        }
    }, {
        key: 'hich',
        value: function hich(event) {
            var target = event.target;
            var value = target.type === 'checkbox' ? target.checked : target.value;
            var name = target.name;

            this.setState(_defineProperty({}, name, value));
        }
    }, {
        key: 'render',
        value: function render() {
            var _this2 = this;

            var _props = this.props,
                closeSettings = _props.closeSettings,
                saveSettings = _props.saveSettings,
                saving_in_progress = _props.saving_in_progress;

            return saving_in_progress ? __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(__WEBPACK_IMPORTED_MODULE_4__components_partials_CircularLoaderRow__["a" /* default */], null) : __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                'div',
                { className: 'row' },
                __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                    'div',
                    { className: 'col s6' },
                    __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                        'h6',
                        null,
                        'Global Settings'
                    )
                ),
                __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                    'div',
                    { className: 'col s6' },
                    __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                        'div',
                        { className: 'right' },
                        __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                            'a',
                            { className: 'btn wave green btn-small', onClick: function onClick() {
                                    return saveSettings(_this2.state);
                                } },
                            'Save'
                        ),
                        ' ',
                        __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                            'a',
                            {
                                className: 'btn wave grey btn-small', onClick: closeSettings },
                            'Close'
                        )
                    )
                ),
                __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                    'div',
                    { className: 'col s12' },
                    __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement('hr', null)
                ),
                __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(__WEBPACK_IMPORTED_MODULE_2__components_SettingsForm__["a" /* default */], { hich: this.hich, pstate: this.state, global: true })
            );
        }
    }]);

    return SettingsPanel;
}(__WEBPACK_IMPORTED_MODULE_0_react___default.a.Component);

// map state


function mapStateToProps(state) {
    return {
        globalSettings: state.settings.options.global,
        saving_in_progress: state.settings.saving_settings_in_progress
    };
}

// map dispatch
function mapDispatchToProps(dispatch) {
    return {
        saveSettings: function saveSettings(settings) {
            dispatch(Object(__WEBPACK_IMPORTED_MODULE_3__actions_settingsAction__["b" /* saveGlobalSettings */])(settings));
        },
        closeSettings: function closeSettings() {
            dispatch(Object(__WEBPACK_IMPORTED_MODULE_3__actions_settingsAction__["c" /* toggleSettingsPanel */])());
        }
    };
}

// connect and export
/* harmony default export */ __webpack_exports__["a"] = (Object(__WEBPACK_IMPORTED_MODULE_1_react_redux__["b" /* connect */])(mapStateToProps, mapDispatchToProps)(SettingsPanel));

/***/ }),
/* 117 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_react__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_react___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0_react__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_react_redux__ = __webpack_require__(2);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__components_partials_TempMessage__ = __webpack_require__(118);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3_react_flip_move__ = __webpack_require__(64);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3_react_flip_move___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_3_react_flip_move__);





// smart component with redux connect

var FlashMessages = function FlashMessages(_ref) {
    var count = _ref.count,
        messages = _ref.messages,
        hidemsg = _ref.hidemsg;
    return __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
        'div',
        { className: 'fixed-top-right' },
        __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
            __WEBPACK_IMPORTED_MODULE_3_react_flip_move___default.a,
            { duration: 500, easing: 'ease-out' },
            count ? messages.map(function (msg) {
                return __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(__WEBPACK_IMPORTED_MODULE_2__components_partials_TempMessage__["a" /* default */], { key: msg.id, msg: msg, hidemsg: hidemsg });
            }) : null
        )
    );
};

// map state
function mapStateToProps(state) {
    return {
        count: state.messages.count,
        messages: state.messages.messages
    };
}

// map dispatch
function mapDispatchToProps(dispatch) {
    return {
        hidemsg: function hidemsg(id) {
            dispatch({
                type: 'SRIZON_MORTGAGE_MESSAGE_EXPIRED',
                payload: id
            });
        }
    };
}

// connect and export
/* harmony default export */ __webpack_exports__["a"] = (Object(__WEBPACK_IMPORTED_MODULE_1_react_redux__["b" /* connect */])(mapStateToProps, mapDispatchToProps)(FlashMessages));

/***/ }),
/* 118 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_react__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_react___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0_react__);
var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }



var TempMessage = function (_React$Component) {
    _inherits(TempMessage, _React$Component);

    function TempMessage() {
        _classCallCheck(this, TempMessage);

        return _possibleConstructorReturn(this, (TempMessage.__proto__ || Object.getPrototypeOf(TempMessage)).apply(this, arguments));
    }

    _createClass(TempMessage, [{
        key: 'componentDidMount',
        value: function componentDidMount() {
            var _props = this.props,
                hidemsg = _props.hidemsg,
                msg = _props.msg;

            var delay = msg.expire_in;
            setTimeout(function () {
                hidemsg(msg.id);
            }, delay * 1000);
        }
    }, {
        key: 'render',
        value: function render() {
            var msg = this.props.msg;

            return __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                'div',
                { className: 'temp-msg-container' },
                msg.type.toLowerCase() == 'error' ? __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                    'div',
                    { className: 'col s12 red white-text temp-msg' },
                    __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                        'h5',
                        null,
                        msg.txt
                    )
                ) : msg.type.toLowerCase() == 'warning' ? __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                    'div',
                    { className: 'col s12 yellow white-text temp-msg' },
                    __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                        'h5',
                        null,
                        msg.txt
                    )
                ) : msg.type.toLowerCase() == 'success' ? __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                    'div',
                    { className: 'col s12 green white-text temp-msg' },
                    __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                        'h5',
                        null,
                        msg.txt
                    )
                ) : __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                    'div',
                    { className: 'col s12 grey lighten-2 grey-text text-darken-2 temp-msg' },
                    __WEBPACK_IMPORTED_MODULE_0_react___default.a.createElement(
                        'h5',
                        null,
                        msg.txt
                    )
                )
            );
        }
    }]);

    return TempMessage;
}(__WEBPACK_IMPORTED_MODULE_0_react___default.a.Component);

/* harmony default export */ __webpack_exports__["a"] = (TempMessage);

/***/ }),
/* 119 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),
/* 120 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),
/* 121 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),
/* 122 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ })
/******/ ]);