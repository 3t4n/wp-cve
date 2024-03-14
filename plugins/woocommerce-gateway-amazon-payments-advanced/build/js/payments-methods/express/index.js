/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ 93:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var parent = __webpack_require__(8196);

module.exports = parent;


/***/ }),

/***/ 5362:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var parent = __webpack_require__(3383);

module.exports = parent;


/***/ }),

/***/ 991:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

__webpack_require__(7690);
var entryVirtual = __webpack_require__(5703);

module.exports = entryVirtual('Array').includes;


/***/ }),

/***/ 4900:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

__webpack_require__(186);
var entryVirtual = __webpack_require__(5703);

module.exports = entryVirtual('Array').slice;


/***/ }),

/***/ 7700:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

__webpack_require__(3381);
var entryVirtual = __webpack_require__(5703);

module.exports = entryVirtual('Function').bind;


/***/ }),

/***/ 6246:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var isPrototypeOf = __webpack_require__(7046);
var method = __webpack_require__(7700);

var FunctionPrototype = Function.prototype;

module.exports = function (it) {
  var own = it.bind;
  return it === FunctionPrototype || (isPrototypeOf(FunctionPrototype, it) && own === FunctionPrototype.bind) ? method : own;
};


/***/ }),

/***/ 8557:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var isPrototypeOf = __webpack_require__(7046);
var arrayMethod = __webpack_require__(991);
var stringMethod = __webpack_require__(1631);

var ArrayPrototype = Array.prototype;
var StringPrototype = String.prototype;

module.exports = function (it) {
  var own = it.includes;
  if (it === ArrayPrototype || (isPrototypeOf(ArrayPrototype, it) && own === ArrayPrototype.includes)) return arrayMethod;
  if (typeof it == 'string' || it === StringPrototype || (isPrototypeOf(StringPrototype, it) && own === StringPrototype.includes)) {
    return stringMethod;
  } return own;
};


/***/ }),

/***/ 9601:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var isPrototypeOf = __webpack_require__(7046);
var method = __webpack_require__(4900);

var ArrayPrototype = Array.prototype;

module.exports = function (it) {
  var own = it.slice;
  return it === ArrayPrototype || (isPrototypeOf(ArrayPrototype, it) && own === ArrayPrototype.slice) ? method : own;
};


/***/ }),

/***/ 4426:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

__webpack_require__(2619);
var path = __webpack_require__(4058);
var apply = __webpack_require__(9730);

// eslint-disable-next-line es/no-json -- safe
if (!path.JSON) path.JSON = { stringify: JSON.stringify };

// eslint-disable-next-line no-unused-vars -- required for `.length`
module.exports = function stringify(it, replacer, space) {
  return apply(path.JSON.stringify, null, arguments);
};


/***/ }),

/***/ 5999:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

__webpack_require__(9221);
var path = __webpack_require__(4058);

module.exports = path.Object.assign;


/***/ }),

/***/ 1631:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

__webpack_require__(1035);
var entryVirtual = __webpack_require__(5703);

module.exports = entryVirtual('String').includes;


/***/ }),

/***/ 9097:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var parent = __webpack_require__(93);

module.exports = parent;


/***/ }),

/***/ 6936:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var parent = __webpack_require__(5362);

module.exports = parent;


/***/ }),

/***/ 4883:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var isCallable = __webpack_require__(7475);
var tryToString = __webpack_require__(9826);

var $TypeError = TypeError;

// `Assert: IsCallable(argument) is true`
module.exports = function (argument) {
  if (isCallable(argument)) return argument;
  throw $TypeError(tryToString(argument) + ' is not a function');
};


/***/ }),

/***/ 8479:
/***/ ((module) => {

module.exports = function () { /* empty */ };


/***/ }),

/***/ 6059:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var isObject = __webpack_require__(941);

var $String = String;
var $TypeError = TypeError;

// `Assert: Type(argument) is Object`
module.exports = function (argument) {
  if (isObject(argument)) return argument;
  throw $TypeError($String(argument) + ' is not an object');
};


/***/ }),

/***/ 1692:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var toIndexedObject = __webpack_require__(4529);
var toAbsoluteIndex = __webpack_require__(9413);
var lengthOfArrayLike = __webpack_require__(623);

// `Array.prototype.{ indexOf, includes }` methods implementation
var createMethod = function (IS_INCLUDES) {
  return function ($this, el, fromIndex) {
    var O = toIndexedObject($this);
    var length = lengthOfArrayLike(O);
    var index = toAbsoluteIndex(fromIndex, length);
    var value;
    // Array#includes uses SameValueZero equality algorithm
    // eslint-disable-next-line no-self-compare -- NaN check
    if (IS_INCLUDES && el != el) while (length > index) {
      value = O[index++];
      // eslint-disable-next-line no-self-compare -- NaN check
      if (value != value) return true;
    // Array#indexOf ignores holes, Array#includes - not
    } else for (;length > index; index++) {
      if ((IS_INCLUDES || index in O) && O[index] === el) return IS_INCLUDES || index || 0;
    } return !IS_INCLUDES && -1;
  };
};

module.exports = {
  // `Array.prototype.includes` method
  // https://tc39.es/ecma262/#sec-array.prototype.includes
  includes: createMethod(true),
  // `Array.prototype.indexOf` method
  // https://tc39.es/ecma262/#sec-array.prototype.indexof
  indexOf: createMethod(false)
};


/***/ }),

/***/ 568:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var fails = __webpack_require__(5981);
var wellKnownSymbol = __webpack_require__(9813);
var V8_VERSION = __webpack_require__(3385);

var SPECIES = wellKnownSymbol('species');

module.exports = function (METHOD_NAME) {
  // We can't use this feature detection in V8 since it causes
  // deoptimization and serious performance degradation
  // https://github.com/zloirock/core-js/issues/677
  return V8_VERSION >= 51 || !fails(function () {
    var array = [];
    var constructor = array.constructor = {};
    constructor[SPECIES] = function () {
      return { foo: 1 };
    };
    return array[METHOD_NAME](Boolean).foo !== 1;
  });
};


/***/ }),

/***/ 3765:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var uncurryThis = __webpack_require__(5329);

module.exports = uncurryThis([].slice);


/***/ }),

/***/ 2532:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var uncurryThis = __webpack_require__(5329);

var toString = uncurryThis({}.toString);
var stringSlice = uncurryThis(''.slice);

module.exports = function (it) {
  return stringSlice(toString(it), 8, -1);
};


/***/ }),

/***/ 9697:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var TO_STRING_TAG_SUPPORT = __webpack_require__(2885);
var isCallable = __webpack_require__(7475);
var classofRaw = __webpack_require__(2532);
var wellKnownSymbol = __webpack_require__(9813);

var TO_STRING_TAG = wellKnownSymbol('toStringTag');
var $Object = Object;

// ES3 wrong here
var CORRECT_ARGUMENTS = classofRaw(function () { return arguments; }()) == 'Arguments';

// fallback for IE11 Script Access Denied error
var tryGet = function (it, key) {
  try {
    return it[key];
  } catch (error) { /* empty */ }
};

// getting tag from ES6+ `Object.prototype.toString`
module.exports = TO_STRING_TAG_SUPPORT ? classofRaw : function (it) {
  var O, tag, result;
  return it === undefined ? 'Undefined' : it === null ? 'Null'
    // @@toStringTag case
    : typeof (tag = tryGet(O = $Object(it), TO_STRING_TAG)) == 'string' ? tag
    // builtinTag case
    : CORRECT_ARGUMENTS ? classofRaw(O)
    // ES3 arguments fallback
    : (result = classofRaw(O)) == 'Object' && isCallable(O.callee) ? 'Arguments' : result;
};


/***/ }),

/***/ 7772:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var wellKnownSymbol = __webpack_require__(9813);

var MATCH = wellKnownSymbol('match');

module.exports = function (METHOD_NAME) {
  var regexp = /./;
  try {
    '/./'[METHOD_NAME](regexp);
  } catch (error1) {
    try {
      regexp[MATCH] = false;
      return '/./'[METHOD_NAME](regexp);
    } catch (error2) { /* empty */ }
  } return false;
};


/***/ }),

/***/ 2029:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var DESCRIPTORS = __webpack_require__(5746);
var definePropertyModule = __webpack_require__(5988);
var createPropertyDescriptor = __webpack_require__(1887);

module.exports = DESCRIPTORS ? function (object, key, value) {
  return definePropertyModule.f(object, key, createPropertyDescriptor(1, value));
} : function (object, key, value) {
  object[key] = value;
  return object;
};


/***/ }),

/***/ 1887:
/***/ ((module) => {

module.exports = function (bitmap, value) {
  return {
    enumerable: !(bitmap & 1),
    configurable: !(bitmap & 2),
    writable: !(bitmap & 4),
    value: value
  };
};


/***/ }),

/***/ 5449:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

"use strict";

var toPropertyKey = __webpack_require__(3894);
var definePropertyModule = __webpack_require__(5988);
var createPropertyDescriptor = __webpack_require__(1887);

module.exports = function (object, key, value) {
  var propertyKey = toPropertyKey(key);
  if (propertyKey in object) definePropertyModule.f(object, propertyKey, createPropertyDescriptor(0, value));
  else object[propertyKey] = value;
};


/***/ }),

/***/ 5609:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var global = __webpack_require__(1899);

// eslint-disable-next-line es/no-object-defineproperty -- safe
var defineProperty = Object.defineProperty;

module.exports = function (key, value) {
  try {
    defineProperty(global, key, { value: value, configurable: true, writable: true });
  } catch (error) {
    global[key] = value;
  } return value;
};


/***/ }),

/***/ 5746:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var fails = __webpack_require__(5981);

// Detect IE8's incomplete defineProperty implementation
module.exports = !fails(function () {
  // eslint-disable-next-line es/no-object-defineproperty -- required for testing
  return Object.defineProperty({}, 1, { get: function () { return 7; } })[1] != 7;
});


/***/ }),

/***/ 6616:
/***/ ((module) => {

var documentAll = typeof document == 'object' && document.all;

// https://tc39.es/ecma262/#sec-IsHTMLDDA-internal-slot
// eslint-disable-next-line unicorn/no-typeof-undefined -- required for testing
var IS_HTMLDDA = typeof documentAll == 'undefined' && documentAll !== undefined;

module.exports = {
  all: documentAll,
  IS_HTMLDDA: IS_HTMLDDA
};


/***/ }),

/***/ 1333:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var global = __webpack_require__(1899);
var isObject = __webpack_require__(941);

var document = global.document;
// typeof document.createElement is 'object' in old IE
var EXISTS = isObject(document) && isObject(document.createElement);

module.exports = function (it) {
  return EXISTS ? document.createElement(it) : {};
};


/***/ }),

/***/ 2861:
/***/ ((module) => {

module.exports = typeof navigator != 'undefined' && String(navigator.userAgent) || '';


/***/ }),

/***/ 3385:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var global = __webpack_require__(1899);
var userAgent = __webpack_require__(2861);

var process = global.process;
var Deno = global.Deno;
var versions = process && process.versions || Deno && Deno.version;
var v8 = versions && versions.v8;
var match, version;

if (v8) {
  match = v8.split('.');
  // in old Chrome, versions of V8 isn't V8 = Chrome / 10
  // but their correct versions are not interesting for us
  version = match[0] > 0 && match[0] < 4 ? 1 : +(match[0] + match[1]);
}

// BrowserFS NodeJS `process` polyfill incorrectly set `.v8` to `0.0`
// so check `userAgent` even if `.v8` exists, but 0
if (!version && userAgent) {
  match = userAgent.match(/Edge\/(\d+)/);
  if (!match || match[1] >= 74) {
    match = userAgent.match(/Chrome\/(\d+)/);
    if (match) version = +match[1];
  }
}

module.exports = version;


/***/ }),

/***/ 5703:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var path = __webpack_require__(4058);

module.exports = function (CONSTRUCTOR) {
  return path[CONSTRUCTOR + 'Prototype'];
};


/***/ }),

/***/ 6759:
/***/ ((module) => {

// IE8- don't enum bug keys
module.exports = [
  'constructor',
  'hasOwnProperty',
  'isPrototypeOf',
  'propertyIsEnumerable',
  'toLocaleString',
  'toString',
  'valueOf'
];


/***/ }),

/***/ 6887:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

"use strict";

var global = __webpack_require__(1899);
var apply = __webpack_require__(9730);
var uncurryThis = __webpack_require__(7484);
var isCallable = __webpack_require__(7475);
var getOwnPropertyDescriptor = (__webpack_require__(9677).f);
var isForced = __webpack_require__(7252);
var path = __webpack_require__(4058);
var bind = __webpack_require__(6843);
var createNonEnumerableProperty = __webpack_require__(2029);
var hasOwn = __webpack_require__(953);

var wrapConstructor = function (NativeConstructor) {
  var Wrapper = function (a, b, c) {
    if (this instanceof Wrapper) {
      switch (arguments.length) {
        case 0: return new NativeConstructor();
        case 1: return new NativeConstructor(a);
        case 2: return new NativeConstructor(a, b);
      } return new NativeConstructor(a, b, c);
    } return apply(NativeConstructor, this, arguments);
  };
  Wrapper.prototype = NativeConstructor.prototype;
  return Wrapper;
};

/*
  options.target         - name of the target object
  options.global         - target is the global object
  options.stat           - export as static methods of target
  options.proto          - export as prototype methods of target
  options.real           - real prototype method for the `pure` version
  options.forced         - export even if the native feature is available
  options.bind           - bind methods to the target, required for the `pure` version
  options.wrap           - wrap constructors to preventing global pollution, required for the `pure` version
  options.unsafe         - use the simple assignment of property instead of delete + defineProperty
  options.sham           - add a flag to not completely full polyfills
  options.enumerable     - export as enumerable property
  options.dontCallGetSet - prevent calling a getter on target
  options.name           - the .name of the function if it does not match the key
*/
module.exports = function (options, source) {
  var TARGET = options.target;
  var GLOBAL = options.global;
  var STATIC = options.stat;
  var PROTO = options.proto;

  var nativeSource = GLOBAL ? global : STATIC ? global[TARGET] : (global[TARGET] || {}).prototype;

  var target = GLOBAL ? path : path[TARGET] || createNonEnumerableProperty(path, TARGET, {})[TARGET];
  var targetPrototype = target.prototype;

  var FORCED, USE_NATIVE, VIRTUAL_PROTOTYPE;
  var key, sourceProperty, targetProperty, nativeProperty, resultProperty, descriptor;

  for (key in source) {
    FORCED = isForced(GLOBAL ? key : TARGET + (STATIC ? '.' : '#') + key, options.forced);
    // contains in native
    USE_NATIVE = !FORCED && nativeSource && hasOwn(nativeSource, key);

    targetProperty = target[key];

    if (USE_NATIVE) if (options.dontCallGetSet) {
      descriptor = getOwnPropertyDescriptor(nativeSource, key);
      nativeProperty = descriptor && descriptor.value;
    } else nativeProperty = nativeSource[key];

    // export native or implementation
    sourceProperty = (USE_NATIVE && nativeProperty) ? nativeProperty : source[key];

    if (USE_NATIVE && typeof targetProperty == typeof sourceProperty) continue;

    // bind methods to global for calling from export context
    if (options.bind && USE_NATIVE) resultProperty = bind(sourceProperty, global);
    // wrap global constructors for prevent changes in this version
    else if (options.wrap && USE_NATIVE) resultProperty = wrapConstructor(sourceProperty);
    // make static versions for prototype methods
    else if (PROTO && isCallable(sourceProperty)) resultProperty = uncurryThis(sourceProperty);
    // default case
    else resultProperty = sourceProperty;

    // add a flag to not completely full polyfills
    if (options.sham || (sourceProperty && sourceProperty.sham) || (targetProperty && targetProperty.sham)) {
      createNonEnumerableProperty(resultProperty, 'sham', true);
    }

    createNonEnumerableProperty(target, key, resultProperty);

    if (PROTO) {
      VIRTUAL_PROTOTYPE = TARGET + 'Prototype';
      if (!hasOwn(path, VIRTUAL_PROTOTYPE)) {
        createNonEnumerableProperty(path, VIRTUAL_PROTOTYPE, {});
      }
      // export virtual prototype methods
      createNonEnumerableProperty(path[VIRTUAL_PROTOTYPE], key, sourceProperty);
      // export real prototype methods
      if (options.real && targetPrototype && (FORCED || !targetPrototype[key])) {
        createNonEnumerableProperty(targetPrototype, key, sourceProperty);
      }
    }
  }
};


/***/ }),

/***/ 5981:
/***/ ((module) => {

module.exports = function (exec) {
  try {
    return !!exec();
  } catch (error) {
    return true;
  }
};


/***/ }),

/***/ 9730:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var NATIVE_BIND = __webpack_require__(8285);

var FunctionPrototype = Function.prototype;
var apply = FunctionPrototype.apply;
var call = FunctionPrototype.call;

// eslint-disable-next-line es/no-reflect -- safe
module.exports = typeof Reflect == 'object' && Reflect.apply || (NATIVE_BIND ? call.bind(apply) : function () {
  return call.apply(apply, arguments);
});


/***/ }),

/***/ 6843:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var uncurryThis = __webpack_require__(7484);
var aCallable = __webpack_require__(4883);
var NATIVE_BIND = __webpack_require__(8285);

var bind = uncurryThis(uncurryThis.bind);

// optional / simple context binding
module.exports = function (fn, that) {
  aCallable(fn);
  return that === undefined ? fn : NATIVE_BIND ? bind(fn, that) : function (/* ...args */) {
    return fn.apply(that, arguments);
  };
};


/***/ }),

/***/ 8285:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var fails = __webpack_require__(5981);

module.exports = !fails(function () {
  // eslint-disable-next-line es/no-function-prototype-bind -- safe
  var test = (function () { /* empty */ }).bind();
  // eslint-disable-next-line no-prototype-builtins -- safe
  return typeof test != 'function' || test.hasOwnProperty('prototype');
});


/***/ }),

/***/ 8308:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

"use strict";

var uncurryThis = __webpack_require__(5329);
var aCallable = __webpack_require__(4883);
var isObject = __webpack_require__(941);
var hasOwn = __webpack_require__(953);
var arraySlice = __webpack_require__(3765);
var NATIVE_BIND = __webpack_require__(8285);

var $Function = Function;
var concat = uncurryThis([].concat);
var join = uncurryThis([].join);
var factories = {};

var construct = function (C, argsLength, args) {
  if (!hasOwn(factories, argsLength)) {
    for (var list = [], i = 0; i < argsLength; i++) list[i] = 'a[' + i + ']';
    factories[argsLength] = $Function('C,a', 'return new C(' + join(list, ',') + ')');
  } return factories[argsLength](C, args);
};

// `Function.prototype.bind` method implementation
// https://tc39.es/ecma262/#sec-function.prototype.bind
// eslint-disable-next-line es/no-function-prototype-bind -- detection
module.exports = NATIVE_BIND ? $Function.bind : function bind(that /* , ...args */) {
  var F = aCallable(this);
  var Prototype = F.prototype;
  var partArgs = arraySlice(arguments, 1);
  var boundFunction = function bound(/* args... */) {
    var args = concat(partArgs, arraySlice(arguments));
    return this instanceof boundFunction ? construct(F, args.length, args) : F.apply(that, args);
  };
  if (isObject(Prototype)) boundFunction.prototype = Prototype;
  return boundFunction;
};


/***/ }),

/***/ 8834:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var NATIVE_BIND = __webpack_require__(8285);

var call = Function.prototype.call;

module.exports = NATIVE_BIND ? call.bind(call) : function () {
  return call.apply(call, arguments);
};


/***/ }),

/***/ 7484:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var classofRaw = __webpack_require__(2532);
var uncurryThis = __webpack_require__(5329);

module.exports = function (fn) {
  // Nashorn bug:
  //   https://github.com/zloirock/core-js/issues/1128
  //   https://github.com/zloirock/core-js/issues/1130
  if (classofRaw(fn) === 'Function') return uncurryThis(fn);
};


/***/ }),

/***/ 5329:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var NATIVE_BIND = __webpack_require__(8285);

var FunctionPrototype = Function.prototype;
var call = FunctionPrototype.call;
var uncurryThisWithBind = NATIVE_BIND && FunctionPrototype.bind.bind(call, call);

module.exports = NATIVE_BIND ? uncurryThisWithBind : function (fn) {
  return function () {
    return call.apply(fn, arguments);
  };
};


/***/ }),

/***/ 626:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var path = __webpack_require__(4058);
var global = __webpack_require__(1899);
var isCallable = __webpack_require__(7475);

var aFunction = function (variable) {
  return isCallable(variable) ? variable : undefined;
};

module.exports = function (namespace, method) {
  return arguments.length < 2 ? aFunction(path[namespace]) || aFunction(global[namespace])
    : path[namespace] && path[namespace][method] || global[namespace] && global[namespace][method];
};


/***/ }),

/***/ 3323:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var uncurryThis = __webpack_require__(5329);
var isArray = __webpack_require__(1052);
var isCallable = __webpack_require__(7475);
var classof = __webpack_require__(2532);
var toString = __webpack_require__(5803);

var push = uncurryThis([].push);

module.exports = function (replacer) {
  if (isCallable(replacer)) return replacer;
  if (!isArray(replacer)) return;
  var rawLength = replacer.length;
  var keys = [];
  for (var i = 0; i < rawLength; i++) {
    var element = replacer[i];
    if (typeof element == 'string') push(keys, element);
    else if (typeof element == 'number' || classof(element) == 'Number' || classof(element) == 'String') push(keys, toString(element));
  }
  var keysLength = keys.length;
  var root = true;
  return function (key, value) {
    if (root) {
      root = false;
      return value;
    }
    if (isArray(this)) return value;
    for (var j = 0; j < keysLength; j++) if (keys[j] === key) return value;
  };
};


/***/ }),

/***/ 4229:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var aCallable = __webpack_require__(4883);
var isNullOrUndefined = __webpack_require__(2119);

// `GetMethod` abstract operation
// https://tc39.es/ecma262/#sec-getmethod
module.exports = function (V, P) {
  var func = V[P];
  return isNullOrUndefined(func) ? undefined : aCallable(func);
};


/***/ }),

/***/ 1899:
/***/ (function(module, __unused_webpack_exports, __webpack_require__) {

var check = function (it) {
  return it && it.Math == Math && it;
};

// https://github.com/zloirock/core-js/issues/86#issuecomment-115759028
module.exports =
  // eslint-disable-next-line es/no-global-this -- safe
  check(typeof globalThis == 'object' && globalThis) ||
  check(typeof window == 'object' && window) ||
  // eslint-disable-next-line no-restricted-globals -- safe
  check(typeof self == 'object' && self) ||
  check(typeof __webpack_require__.g == 'object' && __webpack_require__.g) ||
  // eslint-disable-next-line no-new-func -- fallback
  (function () { return this; })() || this || Function('return this')();


/***/ }),

/***/ 953:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var uncurryThis = __webpack_require__(5329);
var toObject = __webpack_require__(9678);

var hasOwnProperty = uncurryThis({}.hasOwnProperty);

// `HasOwnProperty` abstract operation
// https://tc39.es/ecma262/#sec-hasownproperty
// eslint-disable-next-line es/no-object-hasown -- safe
module.exports = Object.hasOwn || function hasOwn(it, key) {
  return hasOwnProperty(toObject(it), key);
};


/***/ }),

/***/ 7748:
/***/ ((module) => {

module.exports = {};


/***/ }),

/***/ 2840:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var DESCRIPTORS = __webpack_require__(5746);
var fails = __webpack_require__(5981);
var createElement = __webpack_require__(1333);

// Thanks to IE8 for its funny defineProperty
module.exports = !DESCRIPTORS && !fails(function () {
  // eslint-disable-next-line es/no-object-defineproperty -- required for testing
  return Object.defineProperty(createElement('div'), 'a', {
    get: function () { return 7; }
  }).a != 7;
});


/***/ }),

/***/ 7026:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var uncurryThis = __webpack_require__(5329);
var fails = __webpack_require__(5981);
var classof = __webpack_require__(2532);

var $Object = Object;
var split = uncurryThis(''.split);

// fallback for non-array-like ES3 and non-enumerable old V8 strings
module.exports = fails(function () {
  // throws an error in rhino, see https://github.com/mozilla/rhino/issues/346
  // eslint-disable-next-line no-prototype-builtins -- safe
  return !$Object('z').propertyIsEnumerable(0);
}) ? function (it) {
  return classof(it) == 'String' ? split(it, '') : $Object(it);
} : $Object;


/***/ }),

/***/ 1302:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var uncurryThis = __webpack_require__(5329);
var isCallable = __webpack_require__(7475);
var store = __webpack_require__(3030);

var functionToString = uncurryThis(Function.toString);

// this helper broken in `core-js@3.4.1-3.4.4`, so we can't use `shared` helper
if (!isCallable(store.inspectSource)) {
  store.inspectSource = function (it) {
    return functionToString(it);
  };
}

module.exports = store.inspectSource;


/***/ }),

/***/ 1052:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var classof = __webpack_require__(2532);

// `IsArray` abstract operation
// https://tc39.es/ecma262/#sec-isarray
// eslint-disable-next-line es/no-array-isarray -- safe
module.exports = Array.isArray || function isArray(argument) {
  return classof(argument) == 'Array';
};


/***/ }),

/***/ 7475:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var $documentAll = __webpack_require__(6616);

var documentAll = $documentAll.all;

// `IsCallable` abstract operation
// https://tc39.es/ecma262/#sec-iscallable
module.exports = $documentAll.IS_HTMLDDA ? function (argument) {
  return typeof argument == 'function' || argument === documentAll;
} : function (argument) {
  return typeof argument == 'function';
};


/***/ }),

/***/ 4284:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var uncurryThis = __webpack_require__(5329);
var fails = __webpack_require__(5981);
var isCallable = __webpack_require__(7475);
var classof = __webpack_require__(9697);
var getBuiltIn = __webpack_require__(626);
var inspectSource = __webpack_require__(1302);

var noop = function () { /* empty */ };
var empty = [];
var construct = getBuiltIn('Reflect', 'construct');
var constructorRegExp = /^\s*(?:class|function)\b/;
var exec = uncurryThis(constructorRegExp.exec);
var INCORRECT_TO_STRING = !constructorRegExp.exec(noop);

var isConstructorModern = function isConstructor(argument) {
  if (!isCallable(argument)) return false;
  try {
    construct(noop, empty, argument);
    return true;
  } catch (error) {
    return false;
  }
};

var isConstructorLegacy = function isConstructor(argument) {
  if (!isCallable(argument)) return false;
  switch (classof(argument)) {
    case 'AsyncFunction':
    case 'GeneratorFunction':
    case 'AsyncGeneratorFunction': return false;
  }
  try {
    // we can't check .prototype since constructors produced by .bind haven't it
    // `Function#toString` throws on some built-it function in some legacy engines
    // (for example, `DOMQuad` and similar in FF41-)
    return INCORRECT_TO_STRING || !!exec(constructorRegExp, inspectSource(argument));
  } catch (error) {
    return true;
  }
};

isConstructorLegacy.sham = true;

// `IsConstructor` abstract operation
// https://tc39.es/ecma262/#sec-isconstructor
module.exports = !construct || fails(function () {
  var called;
  return isConstructorModern(isConstructorModern.call)
    || !isConstructorModern(Object)
    || !isConstructorModern(function () { called = true; })
    || called;
}) ? isConstructorLegacy : isConstructorModern;


/***/ }),

/***/ 7252:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var fails = __webpack_require__(5981);
var isCallable = __webpack_require__(7475);

var replacement = /#|\.prototype\./;

var isForced = function (feature, detection) {
  var value = data[normalize(feature)];
  return value == POLYFILL ? true
    : value == NATIVE ? false
    : isCallable(detection) ? fails(detection)
    : !!detection;
};

var normalize = isForced.normalize = function (string) {
  return String(string).replace(replacement, '.').toLowerCase();
};

var data = isForced.data = {};
var NATIVE = isForced.NATIVE = 'N';
var POLYFILL = isForced.POLYFILL = 'P';

module.exports = isForced;


/***/ }),

/***/ 2119:
/***/ ((module) => {

// we can't use just `it == null` since of `document.all` special case
// https://tc39.es/ecma262/#sec-IsHTMLDDA-internal-slot-aec
module.exports = function (it) {
  return it === null || it === undefined;
};


/***/ }),

/***/ 941:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var isCallable = __webpack_require__(7475);
var $documentAll = __webpack_require__(6616);

var documentAll = $documentAll.all;

module.exports = $documentAll.IS_HTMLDDA ? function (it) {
  return typeof it == 'object' ? it !== null : isCallable(it) || it === documentAll;
} : function (it) {
  return typeof it == 'object' ? it !== null : isCallable(it);
};


/***/ }),

/***/ 2529:
/***/ ((module) => {

module.exports = true;


/***/ }),

/***/ 685:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var isObject = __webpack_require__(941);
var classof = __webpack_require__(2532);
var wellKnownSymbol = __webpack_require__(9813);

var MATCH = wellKnownSymbol('match');

// `IsRegExp` abstract operation
// https://tc39.es/ecma262/#sec-isregexp
module.exports = function (it) {
  var isRegExp;
  return isObject(it) && ((isRegExp = it[MATCH]) !== undefined ? !!isRegExp : classof(it) == 'RegExp');
};


/***/ }),

/***/ 6664:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var getBuiltIn = __webpack_require__(626);
var isCallable = __webpack_require__(7475);
var isPrototypeOf = __webpack_require__(7046);
var USE_SYMBOL_AS_UID = __webpack_require__(2302);

var $Object = Object;

module.exports = USE_SYMBOL_AS_UID ? function (it) {
  return typeof it == 'symbol';
} : function (it) {
  var $Symbol = getBuiltIn('Symbol');
  return isCallable($Symbol) && isPrototypeOf($Symbol.prototype, $Object(it));
};


/***/ }),

/***/ 623:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var toLength = __webpack_require__(3057);

// `LengthOfArrayLike` abstract operation
// https://tc39.es/ecma262/#sec-lengthofarraylike
module.exports = function (obj) {
  return toLength(obj.length);
};


/***/ }),

/***/ 5331:
/***/ ((module) => {

var ceil = Math.ceil;
var floor = Math.floor;

// `Math.trunc` method
// https://tc39.es/ecma262/#sec-math.trunc
// eslint-disable-next-line es/no-math-trunc -- safe
module.exports = Math.trunc || function trunc(x) {
  var n = +x;
  return (n > 0 ? floor : ceil)(n);
};


/***/ }),

/***/ 344:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var isRegExp = __webpack_require__(685);

var $TypeError = TypeError;

module.exports = function (it) {
  if (isRegExp(it)) {
    throw $TypeError("The method doesn't accept regular expressions");
  } return it;
};


/***/ }),

/***/ 4420:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

"use strict";

var DESCRIPTORS = __webpack_require__(5746);
var uncurryThis = __webpack_require__(5329);
var call = __webpack_require__(8834);
var fails = __webpack_require__(5981);
var objectKeys = __webpack_require__(4771);
var getOwnPropertySymbolsModule = __webpack_require__(7857);
var propertyIsEnumerableModule = __webpack_require__(6760);
var toObject = __webpack_require__(9678);
var IndexedObject = __webpack_require__(7026);

// eslint-disable-next-line es/no-object-assign -- safe
var $assign = Object.assign;
// eslint-disable-next-line es/no-object-defineproperty -- required for testing
var defineProperty = Object.defineProperty;
var concat = uncurryThis([].concat);

// `Object.assign` method
// https://tc39.es/ecma262/#sec-object.assign
module.exports = !$assign || fails(function () {
  // should have correct order of operations (Edge bug)
  if (DESCRIPTORS && $assign({ b: 1 }, $assign(defineProperty({}, 'a', {
    enumerable: true,
    get: function () {
      defineProperty(this, 'b', {
        value: 3,
        enumerable: false
      });
    }
  }), { b: 2 })).b !== 1) return true;
  // should work with symbols and should have deterministic property order (V8 bug)
  var A = {};
  var B = {};
  // eslint-disable-next-line es/no-symbol -- safe
  var symbol = Symbol();
  var alphabet = 'abcdefghijklmnopqrst';
  A[symbol] = 7;
  alphabet.split('').forEach(function (chr) { B[chr] = chr; });
  return $assign({}, A)[symbol] != 7 || objectKeys($assign({}, B)).join('') != alphabet;
}) ? function assign(target, source) { // eslint-disable-line no-unused-vars -- required for `.length`
  var T = toObject(target);
  var argumentsLength = arguments.length;
  var index = 1;
  var getOwnPropertySymbols = getOwnPropertySymbolsModule.f;
  var propertyIsEnumerable = propertyIsEnumerableModule.f;
  while (argumentsLength > index) {
    var S = IndexedObject(arguments[index++]);
    var keys = getOwnPropertySymbols ? concat(objectKeys(S), getOwnPropertySymbols(S)) : objectKeys(S);
    var length = keys.length;
    var j = 0;
    var key;
    while (length > j) {
      key = keys[j++];
      if (!DESCRIPTORS || call(propertyIsEnumerable, S, key)) T[key] = S[key];
    }
  } return T;
} : $assign;


/***/ }),

/***/ 5988:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {

var DESCRIPTORS = __webpack_require__(5746);
var IE8_DOM_DEFINE = __webpack_require__(2840);
var V8_PROTOTYPE_DEFINE_BUG = __webpack_require__(3937);
var anObject = __webpack_require__(6059);
var toPropertyKey = __webpack_require__(3894);

var $TypeError = TypeError;
// eslint-disable-next-line es/no-object-defineproperty -- safe
var $defineProperty = Object.defineProperty;
// eslint-disable-next-line es/no-object-getownpropertydescriptor -- safe
var $getOwnPropertyDescriptor = Object.getOwnPropertyDescriptor;
var ENUMERABLE = 'enumerable';
var CONFIGURABLE = 'configurable';
var WRITABLE = 'writable';

// `Object.defineProperty` method
// https://tc39.es/ecma262/#sec-object.defineproperty
exports.f = DESCRIPTORS ? V8_PROTOTYPE_DEFINE_BUG ? function defineProperty(O, P, Attributes) {
  anObject(O);
  P = toPropertyKey(P);
  anObject(Attributes);
  if (typeof O === 'function' && P === 'prototype' && 'value' in Attributes && WRITABLE in Attributes && !Attributes[WRITABLE]) {
    var current = $getOwnPropertyDescriptor(O, P);
    if (current && current[WRITABLE]) {
      O[P] = Attributes.value;
      Attributes = {
        configurable: CONFIGURABLE in Attributes ? Attributes[CONFIGURABLE] : current[CONFIGURABLE],
        enumerable: ENUMERABLE in Attributes ? Attributes[ENUMERABLE] : current[ENUMERABLE],
        writable: false
      };
    }
  } return $defineProperty(O, P, Attributes);
} : $defineProperty : function defineProperty(O, P, Attributes) {
  anObject(O);
  P = toPropertyKey(P);
  anObject(Attributes);
  if (IE8_DOM_DEFINE) try {
    return $defineProperty(O, P, Attributes);
  } catch (error) { /* empty */ }
  if ('get' in Attributes || 'set' in Attributes) throw $TypeError('Accessors not supported');
  if ('value' in Attributes) O[P] = Attributes.value;
  return O;
};


/***/ }),

/***/ 9677:
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {

var DESCRIPTORS = __webpack_require__(5746);
var call = __webpack_require__(8834);
var propertyIsEnumerableModule = __webpack_require__(6760);
var createPropertyDescriptor = __webpack_require__(1887);
var toIndexedObject = __webpack_require__(4529);
var toPropertyKey = __webpack_require__(3894);
var hasOwn = __webpack_require__(953);
var IE8_DOM_DEFINE = __webpack_require__(2840);

// eslint-disable-next-line es/no-object-getownpropertydescriptor -- safe
var $getOwnPropertyDescriptor = Object.getOwnPropertyDescriptor;

// `Object.getOwnPropertyDescriptor` method
// https://tc39.es/ecma262/#sec-object.getownpropertydescriptor
exports.f = DESCRIPTORS ? $getOwnPropertyDescriptor : function getOwnPropertyDescriptor(O, P) {
  O = toIndexedObject(O);
  P = toPropertyKey(P);
  if (IE8_DOM_DEFINE) try {
    return $getOwnPropertyDescriptor(O, P);
  } catch (error) { /* empty */ }
  if (hasOwn(O, P)) return createPropertyDescriptor(!call(propertyIsEnumerableModule.f, O, P), O[P]);
};


/***/ }),

/***/ 7857:
/***/ ((__unused_webpack_module, exports) => {

// eslint-disable-next-line es/no-object-getownpropertysymbols -- safe
exports.f = Object.getOwnPropertySymbols;


/***/ }),

/***/ 7046:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var uncurryThis = __webpack_require__(5329);

module.exports = uncurryThis({}.isPrototypeOf);


/***/ }),

/***/ 5629:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var uncurryThis = __webpack_require__(5329);
var hasOwn = __webpack_require__(953);
var toIndexedObject = __webpack_require__(4529);
var indexOf = (__webpack_require__(1692).indexOf);
var hiddenKeys = __webpack_require__(7748);

var push = uncurryThis([].push);

module.exports = function (object, names) {
  var O = toIndexedObject(object);
  var i = 0;
  var result = [];
  var key;
  for (key in O) !hasOwn(hiddenKeys, key) && hasOwn(O, key) && push(result, key);
  // Don't enum bug & hidden keys
  while (names.length > i) if (hasOwn(O, key = names[i++])) {
    ~indexOf(result, key) || push(result, key);
  }
  return result;
};


/***/ }),

/***/ 4771:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var internalObjectKeys = __webpack_require__(5629);
var enumBugKeys = __webpack_require__(6759);

// `Object.keys` method
// https://tc39.es/ecma262/#sec-object.keys
// eslint-disable-next-line es/no-object-keys -- safe
module.exports = Object.keys || function keys(O) {
  return internalObjectKeys(O, enumBugKeys);
};


/***/ }),

/***/ 6760:
/***/ ((__unused_webpack_module, exports) => {

"use strict";

var $propertyIsEnumerable = {}.propertyIsEnumerable;
// eslint-disable-next-line es/no-object-getownpropertydescriptor -- safe
var getOwnPropertyDescriptor = Object.getOwnPropertyDescriptor;

// Nashorn ~ JDK8 bug
var NASHORN_BUG = getOwnPropertyDescriptor && !$propertyIsEnumerable.call({ 1: 2 }, 1);

// `Object.prototype.propertyIsEnumerable` method implementation
// https://tc39.es/ecma262/#sec-object.prototype.propertyisenumerable
exports.f = NASHORN_BUG ? function propertyIsEnumerable(V) {
  var descriptor = getOwnPropertyDescriptor(this, V);
  return !!descriptor && descriptor.enumerable;
} : $propertyIsEnumerable;


/***/ }),

/***/ 9811:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var call = __webpack_require__(8834);
var isCallable = __webpack_require__(7475);
var isObject = __webpack_require__(941);

var $TypeError = TypeError;

// `OrdinaryToPrimitive` abstract operation
// https://tc39.es/ecma262/#sec-ordinarytoprimitive
module.exports = function (input, pref) {
  var fn, val;
  if (pref === 'string' && isCallable(fn = input.toString) && !isObject(val = call(fn, input))) return val;
  if (isCallable(fn = input.valueOf) && !isObject(val = call(fn, input))) return val;
  if (pref !== 'string' && isCallable(fn = input.toString) && !isObject(val = call(fn, input))) return val;
  throw $TypeError("Can't convert object to primitive value");
};


/***/ }),

/***/ 4058:
/***/ ((module) => {

module.exports = {};


/***/ }),

/***/ 8219:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var isNullOrUndefined = __webpack_require__(2119);

var $TypeError = TypeError;

// `RequireObjectCoercible` abstract operation
// https://tc39.es/ecma262/#sec-requireobjectcoercible
module.exports = function (it) {
  if (isNullOrUndefined(it)) throw $TypeError("Can't call method on " + it);
  return it;
};


/***/ }),

/***/ 3030:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var global = __webpack_require__(1899);
var defineGlobalProperty = __webpack_require__(5609);

var SHARED = '__core-js_shared__';
var store = global[SHARED] || defineGlobalProperty(SHARED, {});

module.exports = store;


/***/ }),

/***/ 8726:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var IS_PURE = __webpack_require__(2529);
var store = __webpack_require__(3030);

(module.exports = function (key, value) {
  return store[key] || (store[key] = value !== undefined ? value : {});
})('versions', []).push({
  version: '3.30.2',
  mode: IS_PURE ? 'pure' : 'global',
  copyright: '© 2014-2023 Denis Pushkarev (zloirock.ru)',
  license: 'https://github.com/zloirock/core-js/blob/v3.30.2/LICENSE',
  source: 'https://github.com/zloirock/core-js'
});


/***/ }),

/***/ 3405:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

/* eslint-disable es/no-symbol -- required for testing */
var V8_VERSION = __webpack_require__(3385);
var fails = __webpack_require__(5981);
var global = __webpack_require__(1899);

var $String = global.String;

// eslint-disable-next-line es/no-object-getownpropertysymbols -- required for testing
module.exports = !!Object.getOwnPropertySymbols && !fails(function () {
  var symbol = Symbol();
  // Chrome 38 Symbol has incorrect toString conversion
  // `get-own-property-symbols` polyfill symbols converted to object are not Symbol instances
  // nb: Do not call `String` directly to avoid this being optimized out to `symbol+''` which will,
  // of course, fail.
  return !$String(symbol) || !(Object(symbol) instanceof Symbol) ||
    // Chrome 38-40 symbols are not inherited from DOM collections prototypes to instances
    !Symbol.sham && V8_VERSION && V8_VERSION < 41;
});


/***/ }),

/***/ 9413:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var toIntegerOrInfinity = __webpack_require__(2435);

var max = Math.max;
var min = Math.min;

// Helper for a popular repeating case of the spec:
// Let integer be ? ToInteger(index).
// If integer < 0, let result be max((length + integer), 0); else let result be min(integer, length).
module.exports = function (index, length) {
  var integer = toIntegerOrInfinity(index);
  return integer < 0 ? max(integer + length, 0) : min(integer, length);
};


/***/ }),

/***/ 4529:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

// toObject with fallback for non-array-like ES3 strings
var IndexedObject = __webpack_require__(7026);
var requireObjectCoercible = __webpack_require__(8219);

module.exports = function (it) {
  return IndexedObject(requireObjectCoercible(it));
};


/***/ }),

/***/ 2435:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var trunc = __webpack_require__(5331);

// `ToIntegerOrInfinity` abstract operation
// https://tc39.es/ecma262/#sec-tointegerorinfinity
module.exports = function (argument) {
  var number = +argument;
  // eslint-disable-next-line no-self-compare -- NaN check
  return number !== number || number === 0 ? 0 : trunc(number);
};


/***/ }),

/***/ 3057:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var toIntegerOrInfinity = __webpack_require__(2435);

var min = Math.min;

// `ToLength` abstract operation
// https://tc39.es/ecma262/#sec-tolength
module.exports = function (argument) {
  return argument > 0 ? min(toIntegerOrInfinity(argument), 0x1FFFFFFFFFFFFF) : 0; // 2 ** 53 - 1 == 9007199254740991
};


/***/ }),

/***/ 9678:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var requireObjectCoercible = __webpack_require__(8219);

var $Object = Object;

// `ToObject` abstract operation
// https://tc39.es/ecma262/#sec-toobject
module.exports = function (argument) {
  return $Object(requireObjectCoercible(argument));
};


/***/ }),

/***/ 6935:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var call = __webpack_require__(8834);
var isObject = __webpack_require__(941);
var isSymbol = __webpack_require__(6664);
var getMethod = __webpack_require__(4229);
var ordinaryToPrimitive = __webpack_require__(9811);
var wellKnownSymbol = __webpack_require__(9813);

var $TypeError = TypeError;
var TO_PRIMITIVE = wellKnownSymbol('toPrimitive');

// `ToPrimitive` abstract operation
// https://tc39.es/ecma262/#sec-toprimitive
module.exports = function (input, pref) {
  if (!isObject(input) || isSymbol(input)) return input;
  var exoticToPrim = getMethod(input, TO_PRIMITIVE);
  var result;
  if (exoticToPrim) {
    if (pref === undefined) pref = 'default';
    result = call(exoticToPrim, input, pref);
    if (!isObject(result) || isSymbol(result)) return result;
    throw $TypeError("Can't convert object to primitive value");
  }
  if (pref === undefined) pref = 'number';
  return ordinaryToPrimitive(input, pref);
};


/***/ }),

/***/ 3894:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var toPrimitive = __webpack_require__(6935);
var isSymbol = __webpack_require__(6664);

// `ToPropertyKey` abstract operation
// https://tc39.es/ecma262/#sec-topropertykey
module.exports = function (argument) {
  var key = toPrimitive(argument, 'string');
  return isSymbol(key) ? key : key + '';
};


/***/ }),

/***/ 2885:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var wellKnownSymbol = __webpack_require__(9813);

var TO_STRING_TAG = wellKnownSymbol('toStringTag');
var test = {};

test[TO_STRING_TAG] = 'z';

module.exports = String(test) === '[object z]';


/***/ }),

/***/ 5803:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var classof = __webpack_require__(9697);

var $String = String;

module.exports = function (argument) {
  if (classof(argument) === 'Symbol') throw TypeError('Cannot convert a Symbol value to a string');
  return $String(argument);
};


/***/ }),

/***/ 9826:
/***/ ((module) => {

var $String = String;

module.exports = function (argument) {
  try {
    return $String(argument);
  } catch (error) {
    return 'Object';
  }
};


/***/ }),

/***/ 9418:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var uncurryThis = __webpack_require__(5329);

var id = 0;
var postfix = Math.random();
var toString = uncurryThis(1.0.toString);

module.exports = function (key) {
  return 'Symbol(' + (key === undefined ? '' : key) + ')_' + toString(++id + postfix, 36);
};


/***/ }),

/***/ 2302:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

/* eslint-disable es/no-symbol -- required for testing */
var NATIVE_SYMBOL = __webpack_require__(3405);

module.exports = NATIVE_SYMBOL
  && !Symbol.sham
  && typeof Symbol.iterator == 'symbol';


/***/ }),

/***/ 3937:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var DESCRIPTORS = __webpack_require__(5746);
var fails = __webpack_require__(5981);

// V8 ~ Chrome 36-
// https://bugs.chromium.org/p/v8/issues/detail?id=3334
module.exports = DESCRIPTORS && fails(function () {
  // eslint-disable-next-line es/no-object-defineproperty -- required for testing
  return Object.defineProperty(function () { /* empty */ }, 'prototype', {
    value: 42,
    writable: false
  }).prototype != 42;
});


/***/ }),

/***/ 9813:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var global = __webpack_require__(1899);
var shared = __webpack_require__(8726);
var hasOwn = __webpack_require__(953);
var uid = __webpack_require__(9418);
var NATIVE_SYMBOL = __webpack_require__(3405);
var USE_SYMBOL_AS_UID = __webpack_require__(2302);

var Symbol = global.Symbol;
var WellKnownSymbolsStore = shared('wks');
var createWellKnownSymbol = USE_SYMBOL_AS_UID ? Symbol['for'] || Symbol : Symbol && Symbol.withoutSetter || uid;

module.exports = function (name) {
  if (!hasOwn(WellKnownSymbolsStore, name)) {
    WellKnownSymbolsStore[name] = NATIVE_SYMBOL && hasOwn(Symbol, name)
      ? Symbol[name]
      : createWellKnownSymbol('Symbol.' + name);
  } return WellKnownSymbolsStore[name];
};


/***/ }),

/***/ 7690:
/***/ ((__unused_webpack_module, __unused_webpack_exports, __webpack_require__) => {

"use strict";

var $ = __webpack_require__(6887);
var $includes = (__webpack_require__(1692).includes);
var fails = __webpack_require__(5981);
var addToUnscopables = __webpack_require__(8479);

// FF99+ bug
var BROKEN_ON_SPARSE = fails(function () {
  // eslint-disable-next-line es/no-array-prototype-includes -- detection
  return !Array(1).includes();
});

// `Array.prototype.includes` method
// https://tc39.es/ecma262/#sec-array.prototype.includes
$({ target: 'Array', proto: true, forced: BROKEN_ON_SPARSE }, {
  includes: function includes(el /* , fromIndex = 0 */) {
    return $includes(this, el, arguments.length > 1 ? arguments[1] : undefined);
  }
});

// https://tc39.es/ecma262/#sec-array.prototype-@@unscopables
addToUnscopables('includes');


/***/ }),

/***/ 186:
/***/ ((__unused_webpack_module, __unused_webpack_exports, __webpack_require__) => {

"use strict";

var $ = __webpack_require__(6887);
var isArray = __webpack_require__(1052);
var isConstructor = __webpack_require__(4284);
var isObject = __webpack_require__(941);
var toAbsoluteIndex = __webpack_require__(9413);
var lengthOfArrayLike = __webpack_require__(623);
var toIndexedObject = __webpack_require__(4529);
var createProperty = __webpack_require__(5449);
var wellKnownSymbol = __webpack_require__(9813);
var arrayMethodHasSpeciesSupport = __webpack_require__(568);
var nativeSlice = __webpack_require__(3765);

var HAS_SPECIES_SUPPORT = arrayMethodHasSpeciesSupport('slice');

var SPECIES = wellKnownSymbol('species');
var $Array = Array;
var max = Math.max;

// `Array.prototype.slice` method
// https://tc39.es/ecma262/#sec-array.prototype.slice
// fallback for not array-like ES3 strings and DOM objects
$({ target: 'Array', proto: true, forced: !HAS_SPECIES_SUPPORT }, {
  slice: function slice(start, end) {
    var O = toIndexedObject(this);
    var length = lengthOfArrayLike(O);
    var k = toAbsoluteIndex(start, length);
    var fin = toAbsoluteIndex(end === undefined ? length : end, length);
    // inline `ArraySpeciesCreate` for usage native `Array#slice` where it's possible
    var Constructor, result, n;
    if (isArray(O)) {
      Constructor = O.constructor;
      // cross-realm fallback
      if (isConstructor(Constructor) && (Constructor === $Array || isArray(Constructor.prototype))) {
        Constructor = undefined;
      } else if (isObject(Constructor)) {
        Constructor = Constructor[SPECIES];
        if (Constructor === null) Constructor = undefined;
      }
      if (Constructor === $Array || Constructor === undefined) {
        return nativeSlice(O, k, fin);
      }
    }
    result = new (Constructor === undefined ? $Array : Constructor)(max(fin - k, 0));
    for (n = 0; k < fin; k++, n++) if (k in O) createProperty(result, n, O[k]);
    result.length = n;
    return result;
  }
});


/***/ }),

/***/ 3381:
/***/ ((__unused_webpack_module, __unused_webpack_exports, __webpack_require__) => {

// TODO: Remove from `core-js@4`
var $ = __webpack_require__(6887);
var bind = __webpack_require__(8308);

// `Function.prototype.bind` method
// https://tc39.es/ecma262/#sec-function.prototype.bind
// eslint-disable-next-line es/no-function-prototype-bind -- detection
$({ target: 'Function', proto: true, forced: Function.bind !== bind }, {
  bind: bind
});


/***/ }),

/***/ 2619:
/***/ ((__unused_webpack_module, __unused_webpack_exports, __webpack_require__) => {

var $ = __webpack_require__(6887);
var getBuiltIn = __webpack_require__(626);
var apply = __webpack_require__(9730);
var call = __webpack_require__(8834);
var uncurryThis = __webpack_require__(5329);
var fails = __webpack_require__(5981);
var isCallable = __webpack_require__(7475);
var isSymbol = __webpack_require__(6664);
var arraySlice = __webpack_require__(3765);
var getReplacerFunction = __webpack_require__(3323);
var NATIVE_SYMBOL = __webpack_require__(3405);

var $String = String;
var $stringify = getBuiltIn('JSON', 'stringify');
var exec = uncurryThis(/./.exec);
var charAt = uncurryThis(''.charAt);
var charCodeAt = uncurryThis(''.charCodeAt);
var replace = uncurryThis(''.replace);
var numberToString = uncurryThis(1.0.toString);

var tester = /[\uD800-\uDFFF]/g;
var low = /^[\uD800-\uDBFF]$/;
var hi = /^[\uDC00-\uDFFF]$/;

var WRONG_SYMBOLS_CONVERSION = !NATIVE_SYMBOL || fails(function () {
  var symbol = getBuiltIn('Symbol')();
  // MS Edge converts symbol values to JSON as {}
  return $stringify([symbol]) != '[null]'
    // WebKit converts symbol values to JSON as null
    || $stringify({ a: symbol }) != '{}'
    // V8 throws on boxed symbols
    || $stringify(Object(symbol)) != '{}';
});

// https://github.com/tc39/proposal-well-formed-stringify
var ILL_FORMED_UNICODE = fails(function () {
  return $stringify('\uDF06\uD834') !== '"\\udf06\\ud834"'
    || $stringify('\uDEAD') !== '"\\udead"';
});

var stringifyWithSymbolsFix = function (it, replacer) {
  var args = arraySlice(arguments);
  var $replacer = getReplacerFunction(replacer);
  if (!isCallable($replacer) && (it === undefined || isSymbol(it))) return; // IE8 returns string on undefined
  args[1] = function (key, value) {
    // some old implementations (like WebKit) could pass numbers as keys
    if (isCallable($replacer)) value = call($replacer, this, $String(key), value);
    if (!isSymbol(value)) return value;
  };
  return apply($stringify, null, args);
};

var fixIllFormed = function (match, offset, string) {
  var prev = charAt(string, offset - 1);
  var next = charAt(string, offset + 1);
  if ((exec(low, match) && !exec(hi, next)) || (exec(hi, match) && !exec(low, prev))) {
    return '\\u' + numberToString(charCodeAt(match, 0), 16);
  } return match;
};

if ($stringify) {
  // `JSON.stringify` method
  // https://tc39.es/ecma262/#sec-json.stringify
  $({ target: 'JSON', stat: true, arity: 3, forced: WRONG_SYMBOLS_CONVERSION || ILL_FORMED_UNICODE }, {
    // eslint-disable-next-line no-unused-vars -- required for `.length`
    stringify: function stringify(it, replacer, space) {
      var args = arraySlice(arguments);
      var result = apply(WRONG_SYMBOLS_CONVERSION ? stringifyWithSymbolsFix : $stringify, null, args);
      return ILL_FORMED_UNICODE && typeof result == 'string' ? replace(result, tester, fixIllFormed) : result;
    }
  });
}


/***/ }),

/***/ 9221:
/***/ ((__unused_webpack_module, __unused_webpack_exports, __webpack_require__) => {

var $ = __webpack_require__(6887);
var assign = __webpack_require__(4420);

// `Object.assign` method
// https://tc39.es/ecma262/#sec-object.assign
// eslint-disable-next-line es/no-object-assign -- required for testing
$({ target: 'Object', stat: true, arity: 2, forced: Object.assign !== assign }, {
  assign: assign
});


/***/ }),

/***/ 1035:
/***/ ((__unused_webpack_module, __unused_webpack_exports, __webpack_require__) => {

"use strict";

var $ = __webpack_require__(6887);
var uncurryThis = __webpack_require__(5329);
var notARegExp = __webpack_require__(344);
var requireObjectCoercible = __webpack_require__(8219);
var toString = __webpack_require__(5803);
var correctIsRegExpLogic = __webpack_require__(7772);

var stringIndexOf = uncurryThis(''.indexOf);

// `String.prototype.includes` method
// https://tc39.es/ecma262/#sec-string.prototype.includes
$({ target: 'String', proto: true, forced: !correctIsRegExpLogic('includes') }, {
  includes: function includes(searchString /* , position = 0 */) {
    return !!~stringIndexOf(
      toString(requireObjectCoercible(this)),
      toString(notARegExp(searchString)),
      arguments.length > 1 ? arguments[1] : undefined
    );
  }
});


/***/ }),

/***/ 8196:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var parent = __webpack_require__(6246);

module.exports = parent;


/***/ }),

/***/ 3778:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var parent = __webpack_require__(8557);

module.exports = parent;


/***/ }),

/***/ 2073:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var parent = __webpack_require__(9601);

module.exports = parent;


/***/ }),

/***/ 8933:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var parent = __webpack_require__(4426);

module.exports = parent;


/***/ }),

/***/ 3383:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var parent = __webpack_require__(5999);

module.exports = parent;


/***/ }),

/***/ 8118:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

module.exports = __webpack_require__(3778);

/***/ }),

/***/ 4278:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

module.exports = __webpack_require__(2073);

/***/ }),

/***/ 5627:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

/* unused reexport */ __webpack_require__(8933);

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
/******/ 		__webpack_modules__[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	(() => {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = (module) => {
/******/ 			var getter = module && module.__esModule ?
/******/ 				() => (module['default']) :
/******/ 				() => (module);
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/global */
/******/ 	(() => {
/******/ 		__webpack_require__.g = (function() {
/******/ 			if (typeof globalThis === 'object') return globalThis;
/******/ 			try {
/******/ 				return this || new Function('return this')();
/******/ 			} catch (e) {
/******/ 				if (typeof window === 'object') return window;
/******/ 			}
/******/ 		})();
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be in strict mode.
(() => {
"use strict";

;// CONCATENATED MODULE: external ["wp","element"]
const external_wp_element_namespaceObject = window["wp"]["element"];
;// CONCATENATED MODULE: external ["wp","i18n"]
const external_wp_i18n_namespaceObject = window["wp"]["i18n"];
;// CONCATENATED MODULE: external ["wp","htmlEntities"]
const external_wp_htmlEntities_namespaceObject = window["wp"]["htmlEntities"];
;// CONCATENATED MODULE: external "React"
const external_React_namespaceObject = window["React"];
var external_React_default = /*#__PURE__*/__webpack_require__.n(external_React_namespaceObject);
;// CONCATENATED MODULE: ./src/js/payments-methods/express/_constants.js
const PAYMENT_METHOD_NAME = 'amazon_payments_advanced_express';
;// CONCATENATED MODULE: ./src/js/payments-methods/classic/_constants.js
const _constants_PAYMENT_METHOD_NAME = 'amazon_payments_advanced';
// EXTERNAL MODULE: ./node_modules/@babel/runtime-corejs3/core-js-stable/instance/includes.js
var includes = __webpack_require__(8118);
var includes_default = /*#__PURE__*/__webpack_require__.n(includes);
;// CONCATENATED MODULE: ./src/js/_utils.js


/**
 * External dependencies
 */




/**
 * Returns an array of the sibling of the element that have the class className.
 * 
 * @param {node} element The element's whose siblings we are searching for.
 * @param {string} className Filter the siblings by this className.
 * @returns {array}
 */
const getSiblings = (element, className) => {
  let siblings = [];
  let sibling = element.parentNode.firstChild;
  while (sibling) {
    if (sibling.nodeType === 1 && sibling !== element && sibling.classList.contains(className)) {
      siblings.push(sibling);
    }
    sibling = sibling.nextSibling;
  }
  return siblings;
};

/**
 * Returns the backend provided settings based on the name param.
 *
 * @param {string} name The settings to access.
 * @returns {object}|{null}
 */
const getBlocksConfiguration = name => {
  const amazonPayServerData = wc.wcSettings.getSetting(name, null);
  if (!amazonPayServerData) {
    throw new Error('Amazon Pay initialization data is not available');
  }
  return amazonPayServerData;
};

/**
 * Label component
 *
 * @param {string} label The text label.
 * @param {object} props Props from payment API.
 * @returns React Component
 */
const Label = _ref => {
  let {
    label,
    ...props
  } = _ref;
  const {
    PaymentMethodLabel
  } = props.components;
  return (0,external_wp_element_namespaceObject.createElement)(PaymentMethodLabel, {
    text: label
  });
};

/**
 * Returns a React Component.
 *
 * @param {object} param0  RenderedComponent and props
 * @returns {RenderedComponent}
 */
const AmazonComponent = _ref2 => {
  let {
    RenderedComponent,
    ...props
  } = _ref2;
  const [errorMessage, setErrorMessage] = (0,external_wp_element_namespaceObject.useState)('');
  (0,external_wp_element_namespaceObject.useEffect)(() => {
    if (errorMessage) {
      throw new Error(errorMessage);
    }
  }, [errorMessage]);
  return (0,external_wp_element_namespaceObject.createElement)(RenderedComponent, props);
};

/**
 * Returns the payment method's description.
 *
 * @returns {string}
 */
const Content = _ref3 => {
  let {
    description,
    ...props
  } = _ref3;
  return decodeEntities(description);
};

/**
 * The Amazon Pay preview image for the editor.
 *
 * @returns React component
 */
const AmazonPayPreview = _ref4 => {
  let {
    settings,
    ...props
  } = _ref4;
  const {
    amazonPayPreviewUrl
  } = settings;
  return (0,external_wp_element_namespaceObject.createElement)("img", {
    style: {
      width: 'auto'
    },
    src: amazonPayPreviewUrl,
    alt: ""
  });
};

/**
 * Returns a checkout field's label.
 *
 * @param {string} field The field's name to retrieve the label for.
 * @param {string} billingOrShipping If the field is for billing or shipping details.
 * @returns {string}|{bool} The field's label or false if the field is not present.
 */
const getCheckOutFieldsLabel = (field, billingOrShipping) => {
  const elem = document.getElementById(billingOrShipping + '-' + field);
  if (!elem) {
    return false;
  }
  return elem && elem.getAttribute('aria-label') ? elem.getAttribute('aria-label') : '';
};

/**
 * Manages the FE's availability of the Gateway.
 *
 * @param {object} props All the props being fed to the canMakePayment callback of the Gateways.
 * @param {object} settings The gateways settings.
 * @returns {bool}
 */
const amazonPayCanMakePayment = (_ref5, _ref6) => {
  let {
    cartTotals
  } = _ref5;
  let {
    allowedCurrencies
  } = _ref6;
  return allowedCurrencies && allowedCurrencies.length > 0 ? includes_default()(allowedCurrencies).call(allowedCurrencies, cartTotals.currency_code) : true;
};
// EXTERNAL MODULE: ./node_modules/core-js-pure/full/object/assign.js
var object_assign = __webpack_require__(6936);
// EXTERNAL MODULE: ./node_modules/core-js-pure/full/instance/bind.js
var bind = __webpack_require__(9097);
;// CONCATENATED MODULE: ./node_modules/@babel/runtime-corejs3/helpers/esm/extends.js


function _extends() {
  var _context;
  _extends = object_assign ? bind(_context = object_assign).call(_context) : function (target) {
    for (var i = 1; i < arguments.length; i++) {
      var source = arguments[i];
      for (var key in source) {
        if (Object.prototype.hasOwnProperty.call(source, key)) {
          target[key] = source[key];
        }
      }
    }
    return target;
  };
  return _extends.apply(this, arguments);
}
// EXTERNAL MODULE: ./node_modules/@babel/runtime-corejs3/core-js-stable/instance/slice.js
var slice = __webpack_require__(4278);
var slice_default = /*#__PURE__*/__webpack_require__.n(slice);
// EXTERNAL MODULE: ./node_modules/@babel/runtime-corejs3/core-js-stable/json/stringify.js
var stringify = __webpack_require__(5627);
;// CONCATENATED MODULE: ./src/js/_renderAmazonButton.js

/* global amazon_payments_advanced, amazon */

/**
 * Returns the settings needed to be provided to the amazon.Pay.renderButton().
 *
 * @param {string} buttonSettingsFlag Specifies the context of the rendering.
 * @param {string} checkoutConfig The checkoutConfig with which we will provide Amazon Pay.
 * @param {object} estimatedOrderAmount The estimatedOrderAmount object or null to be passed to the Button's settings.
 * @returns {object} The settings to provide the Amazon Pay Button with.
 */
const getButtonSettings = (buttonSettingsFlag, checkoutConfig, estimatedOrderAmount) => {
  estimatedOrderAmount = estimatedOrderAmount || amazon_payments_advanced.estimated_order_amount;
  let obj = {
    // set checkout environment
    merchantId: amazon_payments_advanced.merchant_id,
    ledgerCurrency: amazon_payments_advanced.ledger_currency,
    sandbox: amazon_payments_advanced.sandbox === '1',
    // customize the buyer experience
    placement: amazon_payments_advanced.placement,
    buttonColor: amazon_payments_advanced.button_color,
    estimatedOrderAmount: estimatedOrderAmount,
    checkoutLanguage: amazon_payments_advanced.button_language !== '' ? amazon_payments_advanced.button_language.replace('-', '_') : undefined
  };
  if ('express' === buttonSettingsFlag) {
    obj.productType = amazon_payments_advanced.action;
    obj.createCheckoutSessionConfig = amazon_payments_advanced.create_checkout_session_config;
  } else {
    obj.productType = 'undefined' !== typeof checkoutConfig.payloadJSON.addressDetails ? 'PayAndShip' : 'PayOnly';
  }
  return obj;
};

/**
 * Renders an Amazon Pay Button on elements identified by buttonId.
 *
 * @param {string} buttonId Selector on where the Amazon Pay button will be rendered on.
 * @param {string} buttonSettingsFlag Specifies the context of the rendering.
 * @param {string} checkoutConfig The checkoutConfig with which we will provide Amazon Pay.
 * @param {object} estimatedOrderAmount The estimatedOrderAmount object or null to be passed to the Button's settings.
 * @returns {object} The Amazon Pay rendered button.
 */
const renderAmazonButton = (buttonId, buttonSettingsFlag, checkoutConfig, estimatedOrderAmount) => {
  let amazonPayButton = null;
  const buttons = document.querySelectorAll(buttonId);
  for (const button of buttons) {
    const thisId = '#' + button.getAttribute('id');
    const buttonSettings = getButtonSettings(buttonSettingsFlag, checkoutConfig, estimatedOrderAmount);
    amazonPayButton = amazon.Pay.renderButton(thisId, buttonSettings);
  }
  return amazonPayButton;
};

/**
 * Renders and inits the Amazon checkout Process on elements identified by buttonId.
 *
 * @param {string} buttonId Selector on where the Amazon Pay button will be rendered on.
 * @param {string} flag Specifies the context of the rendering.
 * @param {string} checkoutConfig The checkoutConfig with which we will provide Amazon Pay on init.
 */
const renderAndInitAmazonCheckout = (buttonId, flag, checkoutConfig) => {
  checkoutConfig = JSON.parse(checkoutConfig);
  const amazonClassicButton = renderAmazonButton(buttonId, flag, checkoutConfig);
  if (null !== amazonClassicButton) {
    checkoutConfig.payloadJSON = _JSON$stringify(checkoutConfig.payloadJSON);
    amazonClassicButton.initCheckout({
      createCheckoutSessionConfig: checkoutConfig
    });
  }
};

/**
 * Bounds a change Action to button identified by button_id.
 *
 * @param {string} button_id ID of button to bound Amazon Change event on.
 * @param {string} action Type of action to bound the button with.
 */
const activateChange = (button_id, action) => {
  var button = document.getElementById(button_id);
  if (0 === button.length || button.getAttribute('data-wc_apa_change_bind') === action) {
    return;
  }
  button.setAttribute('data-wc_apa_change_bind', action);
  button.addEventListener('click', function (e) {
    e.preventDefault();
  });
  amazon.Pay.bindChangeAction('#' + button.getAttribute('id'), {
    amazonCheckoutSessionId: amazon_payments_advanced.checkout_session_id,
    changeAction: action
  });
};
;// CONCATENATED MODULE: ./src/js/payments-methods/express/_payment-methods-express.js



/**
 * External dependencies
 */


/**
 * Internal dependencies
 */


/**
 * Returns a react component and also sets an observer for the onCheckoutAfterProcessingWithSuccess event.
 * @param {object} props
 * @returns React component
 */
const AmazonPayExpressBtn = props => {
  const estimatedOrderAmount = calculateEstimatedOrderAmount(props);
  (0,external_wp_element_namespaceObject.useEffect)(() => {
    renderAmazonButton('#pay_with_amazon_express', 'express', null, estimatedOrderAmount);
  }, []);
  return (0,external_wp_element_namespaceObject.createElement)("div", {
    id: "pay_with_amazon_express"
  });
};

/**
 * Returns the estimated order amount button attribute.
 * @param {object} props
 * @returns {object}|null Estimated order amount button attribute.
 */
const calculateEstimatedOrderAmount = props => {
  const {
    billing
  } = props;
  const {
    currency
  } = billing;

  /**
   * Get how many charactes are present in the cart's total value.
   * So if the checkout value was 23.76,
   * billing.cartTotal.value would be equal to 2376
   * cartTotalLength would be equal to 4 and
   * currency.minorUnit would be 2.
   */
  const stringCartTotal = String(billing.cartTotal.value);
  const cartTotalLength = stringCartTotal.length;

  // Get how many decimals is the store configured to use.
  const decimals = currency.minorUnit;

  /**
   * Since we know the total length of the checkout value and the length of the decimals,
   * we can build the checkout value in the format expected by Amazon Pay.
   */
  const checkOutValue = slice_default()(stringCartTotal).call(stringCartTotal, 0, cartTotalLength - decimals) + '.' + slice_default()(stringCartTotal).call(stringCartTotal, cartTotalLength - decimals);

  // If the number of decimals are more than the total number of chars in the checkout value. Something has gone wrong, so we return null.
  return cartTotalLength < decimals ? null : {
    amount: checkOutValue,
    currencyCode: currency.code
  };
};

/**
 * Returns the Components that will be used by Amazon Pay "Express".
 *
 * @param {object} props
 * @returns React Component
 */
const AmazonExpressContent = props => {
  const estimatedOrderAmount = calculateEstimatedOrderAmount(props);
  const key = estimatedOrderAmount ? `${estimatedOrderAmount.amount}${estimatedOrderAmount.currencyCode}` : '0';
  const [id, setId] = (0,external_wp_element_namespaceObject.useState)(key);
  (0,external_wp_element_namespaceObject.useEffect)(() => {
    setId(key);
  }, [key]);
  return (0,external_wp_element_namespaceObject.createElement)(AmazonPayExpressBtn, _extends({
    key: id
  }, props));
};
;// CONCATENATED MODULE: ./src/js/payments-methods/express/_settings.js
/**
 * Internal dependencies
 */


const settings = getBlocksConfiguration(PAYMENT_METHOD_NAME + '_data');
;// CONCATENATED MODULE: ./src/js/payments-methods/express/_payment-methods.js

/**
 * External dependencies
 */





/**
 * Internal dependencies
 */




/**
 * The change Payment method component.
 *
 * @returns React component
 */
const ChangePayment = () => {
  (0,external_wp_element_namespaceObject.useEffect)(() => {
    activateChange('amazon_change_payment_method', 'changePayment');
  }, []);
  return (0,external_wp_element_namespaceObject.createElement)("a", {
    href: "#",
    className: "wc-apa-widget-change",
    id: "amazon_change_payment_method"
  }, settings.hasPaymentPreferences ? (0,external_wp_i18n_namespaceObject.__)('Change', 'woocommerce-gateway-amazon-payments-advanced') : (0,external_wp_i18n_namespaceObject.__)('Select', 'woocommerce-gateway-amazon-payments-advanced'));
};

/**
 * Returns a react component and also sets an observer for the onCheckoutValidation event.
 *
 * @param {object} props
 * @returns React component
 */
const AmazonPayInfo = props => {
  const {
    shippingAddress,
    setShippingAddress
  } = props.shippingData;
  const {
    billingData
  } = props.billing;
  const {
    amazonBilling,
    amazonShipping
  } = settings.amazonAddress;
  (0,external_wp_element_namespaceObject.useEffect)(() => {
    const unsubscribe = props.eventRegistration.onCheckoutValidation(async () => {
      for (const shippingField in amazonShipping) {
        // Values are the same as expected. Bail.
        if (amazonShipping[shippingField] === shippingAddress[shippingField]) {
          continue;
        }
        const checkoutFieldLabel = getCheckOutFieldsLabel(shippingField, 'shipping');
        // Field not present in the form, as a result value can't be supplied. Bail.
        if (false === checkoutFieldLabel) {
          continue;
        }

        // Field present in the form but value mismatch. Return error.
        return {
          errorMessage: (0,external_wp_i18n_namespaceObject.sprintf)((0,external_wp_i18n_namespaceObject.__)('We were expecting "%1$s" but we received "%2$s" instead for the Shipping field "%3$s". Please make any changes to your Shipping details through Amazon."', 'woocommerce-gateway-amazon-payments-advanced'), amazonShipping[shippingField], shippingAddress[shippingField], checkoutFieldLabel)
        };
      }
      return true;
    });
    return () => unsubscribe();
  }, [props.eventRegistration.onCheckoutValidation, billingData, shippingAddress, amazonBilling, amazonShipping, props.emitResponse.noticeContexts.PAYMENTS, props.emitResponse.responseTypes.ERROR, props.emitResponse.responseTypes.SUCCESS]);
  return (0,external_wp_element_namespaceObject.createElement)((external_React_default()).Fragment, null, (0,external_wp_element_namespaceObject.createElement)("p", null, (0,external_wp_element_namespaceObject.createElement)(ChangePayment, null), (0,external_wp_i18n_namespaceObject.__)('Payment Method', 'woocommerce-gateway-amazon-payments-advanced')), (0,external_wp_element_namespaceObject.createElement)("div", {
    className: "payment_method_display"
  }, (0,external_wp_element_namespaceObject.createElement)("span", {
    className: "wc-apa-amazon-logo"
  }), (0,external_wp_htmlEntities_namespaceObject.decodeEntities)(settings.selectedPaymentMethod)));
};

/**
 * Returns the Components that will be used by Amazon Pay "Express".
 *
 * @param {object} props
 * @returns React Component
 */
const AmazonContent = props => {
  return (0,external_wp_element_namespaceObject.createElement)((external_React_default()).Fragment, null, (0,external_wp_element_namespaceObject.createElement)(AmazonPayInfo, props));
};
;// CONCATENATED MODULE: ./src/js/payments-methods/express/_checkout-blocks.js

/**
 * External dependencies
 */




/**
 * Internal dependencies
 */



/**
 * The change Shipping Address Component.
 *
 * @returns React component
 */
const ChangeShippingAddress = () => {
  (0,external_wp_element_namespaceObject.useEffect)(() => {
    activateChange('amazon_change_shipping_address', 'changeAddress');
  }, []);
  return (0,external_wp_element_namespaceObject.createElement)("a", {
    href: "#",
    className: "wc-apa-widget-change",
    id: "amazon_change_shipping_address"
  }, (0,external_wp_i18n_namespaceObject.__)('Change', 'woocommerce-gateway-amazon-payments-advanced'));
};

/**
 * The logout Banner component.
 *
 * @returns React component
 */
const LogOutBanner = () => {
  return (0,external_wp_element_namespaceObject.createElement)("div", {
    className: "woocommerce-info info amazon-pay-first-order"
  }, (0,external_wp_htmlEntities_namespaceObject.decodeEntities)(settings.logoutMessage), " ", " ", (0,external_wp_element_namespaceObject.createElement)("a", {
    href: settings.logoutUrl
  }, (0,external_wp_htmlEntities_namespaceObject.decodeEntities)((0,external_wp_i18n_namespaceObject.__)('Log out &raquo;', 'woocommerce-gateway-amazon-payments-advanced'))));
};
const changeShippingAddressOptions = {
  metadata: {
    name: 'amazon-payments-advanced/change-address',
    parent: ['woocommerce/checkout-shipping-address-block']
  },
  component: () => (0,external_wp_element_namespaceObject.createElement)(ChangeShippingAddress, null)
};
const logOutBannerOptions = {
  metadata: {
    name: 'amazon-payments-advanced/log-out-banner',
    parent: ['woocommerce/checkout-fields-block']
  },
  component: () => (0,external_wp_element_namespaceObject.createElement)(LogOutBanner, null)
};
;// CONCATENATED MODULE: ./src/js/payments-methods/express/index.js

/**
 * External dependencies
 */



const {
  registerExpressPaymentMethod,
  registerPaymentMethod,
  registerPaymentMethodExtensionCallbacks
} = wc.wcBlocksRegistry;
const {
  registerCheckoutBlock
} = wc.blocksCheckout;

/**
 * Internal dependencies
 */







if (settings.loggedIn) {
  var _settings$supports;
  const label = (0,external_wp_htmlEntities_namespaceObject.decodeEntities)(settings.title) || (0,external_wp_i18n_namespaceObject.__)('Amazon Pay', 'woocommerce-gateway-amazon-payments-advanced');

  // Unset all other Gateways.
  if (settings.allOtherGateways) {
    let hideAllOtherPaymentGateways = {};
    for (const offset in settings.allOtherGateways) {
      hideAllOtherPaymentGateways[settings.allOtherGateways[offset]] = () => {
        return false;
      };
    }
    registerPaymentMethodExtensionCallbacks('amazon_payments_advanced', hideAllOtherPaymentGateways);
  }

  // Register our checkout Blocks.
  registerCheckoutBlock(changeShippingAddressOptions);
  registerCheckoutBlock(logOutBannerOptions);

  /**
   * Amazon Pay "Express" payment method config object in the case user is logged in to Amazon.
   * In this case Amazon pay is being registered as a normal WooCommerce Gateway.
   */
  const amazonPayExpressPaymentMethod = {
    name: _constants_PAYMENT_METHOD_NAME,
    label: (0,external_wp_element_namespaceObject.createElement)(Label, {
      label: label
    }),
    placeOrderButtonLabel: (0,external_wp_i18n_namespaceObject.__)('Proceed to Amazon', 'woocommerce-gateway-amazon-payments-advanced'),
    content: (0,external_wp_element_namespaceObject.createElement)(AmazonComponent, {
      RenderedComponent: AmazonContent
    }),
    edit: (0,external_wp_element_namespaceObject.createElement)(AmazonComponent, {
      RenderedComponent: AmazonContent
    }),
    canMakePayment: props => {
      return amazonPayCanMakePayment(props, settings);
    },
    ariaLabel: label,
    supports: {
      features: (_settings$supports = settings === null || settings === void 0 ? void 0 : settings.supports) !== null && _settings$supports !== void 0 ? _settings$supports : []
    }
  };

  /**
   * Registers Amazon Pay "Express" as a Payment Method in the Checkout Block of WooCommerce Blocks.
   */
  registerPaymentMethod(amazonPayExpressPaymentMethod);
} else {
  var _settings$supports2;
  /**
   * Amazon Pay "Express" payment method config object in the case user is logged out of Amazon.
   * In this case Amazon pay is being registered as an Express WooCommerce Gateway.
   */
  const amazonPayExpressPaymentMethod = {
    name: PAYMENT_METHOD_NAME,
    content: (0,external_wp_element_namespaceObject.createElement)(AmazonComponent, {
      RenderedComponent: AmazonExpressContent
    }),
    edit: (0,external_wp_element_namespaceObject.createElement)(AmazonPayPreview, {
      settings: settings
    }),
    canMakePayment: props => {
      return amazonPayCanMakePayment(props, settings);
    },
    supports: {
      features: (_settings$supports2 = settings === null || settings === void 0 ? void 0 : settings.supports) !== null && _settings$supports2 !== void 0 ? _settings$supports2 : []
    }
  };

  /**
   * Don't register as an Express Payment method if the hidden button mode is on,
   * since the layout would appear misleading to users in cases when there are no
   * other registered Express Payment methods.
   *
   * In the cart an "OR" would appear without an actual user selection
   * and in the checkout the express checkout block would render and it would appear empty.
   */
  if ('yes' !== settings.hide_button_mode) {
    /**
     * Registers Amazon Pay "Express" as a Payment Method in the Checkout Block of WooCommerce Blocks.
     */
    registerExpressPaymentMethod(amazonPayExpressPaymentMethod);
  }
}
})();

/******/ })()
;