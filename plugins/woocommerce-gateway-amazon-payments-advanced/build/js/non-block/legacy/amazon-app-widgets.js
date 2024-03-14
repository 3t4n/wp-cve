/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ 2383:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

__webpack_require__(1501);
var entryVirtual = __webpack_require__(5703);

module.exports = entryVirtual('Array').filter;


/***/ }),

/***/ 7671:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

__webpack_require__(833);
var entryVirtual = __webpack_require__(5703);

module.exports = entryVirtual('Array').find;


/***/ }),

/***/ 991:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

__webpack_require__(7690);
var entryVirtual = __webpack_require__(5703);

module.exports = entryVirtual('Array').includes;


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

/***/ 2480:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var isPrototypeOf = __webpack_require__(7046);
var method = __webpack_require__(2383);

var ArrayPrototype = Array.prototype;

module.exports = function (it) {
  var own = it.filter;
  return it === ArrayPrototype || (isPrototypeOf(ArrayPrototype, it) && own === ArrayPrototype.filter) ? method : own;
};


/***/ }),

/***/ 2236:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var isPrototypeOf = __webpack_require__(7046);
var method = __webpack_require__(7671);

var ArrayPrototype = Array.prototype;

module.exports = function (it) {
  var own = it.find;
  return it === ArrayPrototype || (isPrototypeOf(ArrayPrototype, it) && own === ArrayPrototype.find) ? method : own;
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

/***/ 2774:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var isPrototypeOf = __webpack_require__(7046);
var method = __webpack_require__(3348);

var StringPrototype = String.prototype;

module.exports = function (it) {
  var own = it.trim;
  return typeof it == 'string' || it === StringPrototype
    || (isPrototypeOf(StringPrototype, it) && own === StringPrototype.trim) ? method : own;
};


/***/ }),

/***/ 5999:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

__webpack_require__(9221);
var path = __webpack_require__(4058);

module.exports = path.Object.assign;


/***/ }),

/***/ 8494:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

__webpack_require__(1724);
var path = __webpack_require__(4058);

module.exports = path.Object.keys;


/***/ }),

/***/ 1631:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

__webpack_require__(1035);
var entryVirtual = __webpack_require__(5703);

module.exports = entryVirtual('String').includes;


/***/ }),

/***/ 3348:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

__webpack_require__(7398);
var entryVirtual = __webpack_require__(5703);

module.exports = entryVirtual('String').trim;


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

/***/ 3610:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var bind = __webpack_require__(6843);
var uncurryThis = __webpack_require__(5329);
var IndexedObject = __webpack_require__(7026);
var toObject = __webpack_require__(9678);
var lengthOfArrayLike = __webpack_require__(623);
var arraySpeciesCreate = __webpack_require__(4692);

var push = uncurryThis([].push);

// `Array.prototype.{ forEach, map, filter, some, every, find, findIndex, filterReject }` methods implementation
var createMethod = function (TYPE) {
  var IS_MAP = TYPE == 1;
  var IS_FILTER = TYPE == 2;
  var IS_SOME = TYPE == 3;
  var IS_EVERY = TYPE == 4;
  var IS_FIND_INDEX = TYPE == 6;
  var IS_FILTER_REJECT = TYPE == 7;
  var NO_HOLES = TYPE == 5 || IS_FIND_INDEX;
  return function ($this, callbackfn, that, specificCreate) {
    var O = toObject($this);
    var self = IndexedObject(O);
    var boundFunction = bind(callbackfn, that);
    var length = lengthOfArrayLike(self);
    var index = 0;
    var create = specificCreate || arraySpeciesCreate;
    var target = IS_MAP ? create($this, length) : IS_FILTER || IS_FILTER_REJECT ? create($this, 0) : undefined;
    var value, result;
    for (;length > index; index++) if (NO_HOLES || index in self) {
      value = self[index];
      result = boundFunction(value, index, O);
      if (TYPE) {
        if (IS_MAP) target[index] = result; // map
        else if (result) switch (TYPE) {
          case 3: return true;              // some
          case 5: return value;             // find
          case 6: return index;             // findIndex
          case 2: push(target, value);      // filter
        } else switch (TYPE) {
          case 4: return false;             // every
          case 7: push(target, value);      // filterReject
        }
      }
    }
    return IS_FIND_INDEX ? -1 : IS_SOME || IS_EVERY ? IS_EVERY : target;
  };
};

module.exports = {
  // `Array.prototype.forEach` method
  // https://tc39.es/ecma262/#sec-array.prototype.foreach
  forEach: createMethod(0),
  // `Array.prototype.map` method
  // https://tc39.es/ecma262/#sec-array.prototype.map
  map: createMethod(1),
  // `Array.prototype.filter` method
  // https://tc39.es/ecma262/#sec-array.prototype.filter
  filter: createMethod(2),
  // `Array.prototype.some` method
  // https://tc39.es/ecma262/#sec-array.prototype.some
  some: createMethod(3),
  // `Array.prototype.every` method
  // https://tc39.es/ecma262/#sec-array.prototype.every
  every: createMethod(4),
  // `Array.prototype.find` method
  // https://tc39.es/ecma262/#sec-array.prototype.find
  find: createMethod(5),
  // `Array.prototype.findIndex` method
  // https://tc39.es/ecma262/#sec-array.prototype.findIndex
  findIndex: createMethod(6),
  // `Array.prototype.filterReject` method
  // https://github.com/tc39/proposal-array-filtering
  filterReject: createMethod(7)
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

/***/ 5693:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var isArray = __webpack_require__(1052);
var isConstructor = __webpack_require__(4284);
var isObject = __webpack_require__(941);
var wellKnownSymbol = __webpack_require__(9813);

var SPECIES = wellKnownSymbol('species');
var $Array = Array;

// a part of `ArraySpeciesCreate` abstract operation
// https://tc39.es/ecma262/#sec-arrayspeciescreate
module.exports = function (originalArray) {
  var C;
  if (isArray(originalArray)) {
    C = originalArray.constructor;
    // cross-realm fallback
    if (isConstructor(C) && (C === $Array || isArray(C.prototype))) C = undefined;
    else if (isObject(C)) {
      C = C[SPECIES];
      if (C === null) C = undefined;
    }
  } return C === undefined ? $Array : C;
};


/***/ }),

/***/ 4692:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var arraySpeciesConstructor = __webpack_require__(5693);

// `ArraySpeciesCreate` abstract operation
// https://tc39.es/ecma262/#sec-arrayspeciescreate
module.exports = function (originalArray, length) {
  return new (arraySpeciesConstructor(originalArray))(length === 0 ? 0 : length);
};


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

/***/ 6491:
/***/ ((module) => {

/* global Bun -- Deno case */
module.exports = typeof Bun == 'function' && Bun && typeof Bun.version == 'string';


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

/***/ 9417:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var DESCRIPTORS = __webpack_require__(5746);
var hasOwn = __webpack_require__(953);

var FunctionPrototype = Function.prototype;
// eslint-disable-next-line es/no-object-getownpropertydescriptor -- safe
var getDescriptor = DESCRIPTORS && Object.getOwnPropertyDescriptor;

var EXISTS = hasOwn(FunctionPrototype, 'name');
// additional protection from minified / mangled / dropped function names
var PROPER = EXISTS && (function something() { /* empty */ }).name === 'something';
var CONFIGURABLE = EXISTS && (!DESCRIPTORS || (DESCRIPTORS && getDescriptor(FunctionPrototype, 'name').configurable));

module.exports = {
  EXISTS: EXISTS,
  PROPER: PROPER,
  CONFIGURABLE: CONFIGURABLE
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

/***/ 7620:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

"use strict";

var global = __webpack_require__(1899);
var apply = __webpack_require__(9730);
var isCallable = __webpack_require__(7475);
var ENGINE_IS_BUN = __webpack_require__(6491);
var USER_AGENT = __webpack_require__(2861);
var arraySlice = __webpack_require__(3765);
var validateArgumentsLength = __webpack_require__(8348);

var Function = global.Function;
// dirty IE9- and Bun 0.3.0- checks
var WRAP = /MSIE .\./.test(USER_AGENT) || ENGINE_IS_BUN && (function () {
  var version = global.Bun.version.split('.');
  return version.length < 3 || version[0] == 0 && (version[1] < 3 || version[1] == 3 && version[2] == 0);
})();

// IE9- / Bun 0.3.0- setTimeout / setInterval / setImmediate additional parameters fix
// https://html.spec.whatwg.org/multipage/timers-and-user-prompts.html#timers
// https://github.com/oven-sh/bun/issues/1633
module.exports = function (scheduler, hasTimeArg) {
  var firstParamIndex = hasTimeArg ? 2 : 1;
  return WRAP ? function (handler, timeout /* , ...arguments */) {
    var boundArgs = validateArgumentsLength(arguments.length, 1) > firstParamIndex;
    var fn = isCallable(handler) ? handler : Function(handler);
    var params = boundArgs ? arraySlice(arguments, firstParamIndex) : [];
    var callback = boundArgs ? function () {
      apply(fn, this, params);
    } : fn;
    return hasTimeArg ? scheduler(callback, timeout) : scheduler(callback);
  } : scheduler;
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

/***/ 3093:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var PROPER_FUNCTION_NAME = (__webpack_require__(9417).PROPER);
var fails = __webpack_require__(5981);
var whitespaces = __webpack_require__(3483);

var non = '\u200B\u0085\u180E';

// check that a method works with the correct list
// of whitespaces and has a correct name
module.exports = function (METHOD_NAME) {
  return fails(function () {
    return !!whitespaces[METHOD_NAME]()
      || non[METHOD_NAME]() !== non
      || (PROPER_FUNCTION_NAME && whitespaces[METHOD_NAME].name !== METHOD_NAME);
  });
};


/***/ }),

/***/ 4853:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var uncurryThis = __webpack_require__(5329);
var requireObjectCoercible = __webpack_require__(8219);
var toString = __webpack_require__(5803);
var whitespaces = __webpack_require__(3483);

var replace = uncurryThis(''.replace);
var ltrim = RegExp('^[' + whitespaces + ']+');
var rtrim = RegExp('(^|[^' + whitespaces + '])[' + whitespaces + ']+$');

// `String.prototype.{ trim, trimStart, trimEnd, trimLeft, trimRight }` methods implementation
var createMethod = function (TYPE) {
  return function ($this) {
    var string = toString(requireObjectCoercible($this));
    if (TYPE & 1) string = replace(string, ltrim, '');
    if (TYPE & 2) string = replace(string, rtrim, '$1');
    return string;
  };
};

module.exports = {
  // `String.prototype.{ trimLeft, trimStart }` methods
  // https://tc39.es/ecma262/#sec-string.prototype.trimstart
  start: createMethod(1),
  // `String.prototype.{ trimRight, trimEnd }` methods
  // https://tc39.es/ecma262/#sec-string.prototype.trimend
  end: createMethod(2),
  // `String.prototype.trim` method
  // https://tc39.es/ecma262/#sec-string.prototype.trim
  trim: createMethod(3)
};


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

/***/ 8348:
/***/ ((module) => {

var $TypeError = TypeError;

module.exports = function (passed, required) {
  if (passed < required) throw $TypeError('Not enough arguments');
  return passed;
};


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

/***/ 3483:
/***/ ((module) => {

// a string of all valid unicode whitespaces
module.exports = '\u0009\u000A\u000B\u000C\u000D\u0020\u00A0\u1680\u2000\u2001\u2002' +
  '\u2003\u2004\u2005\u2006\u2007\u2008\u2009\u200A\u202F\u205F\u3000\u2028\u2029\uFEFF';


/***/ }),

/***/ 1501:
/***/ ((__unused_webpack_module, __unused_webpack_exports, __webpack_require__) => {

"use strict";

var $ = __webpack_require__(6887);
var $filter = (__webpack_require__(3610).filter);
var arrayMethodHasSpeciesSupport = __webpack_require__(568);

var HAS_SPECIES_SUPPORT = arrayMethodHasSpeciesSupport('filter');

// `Array.prototype.filter` method
// https://tc39.es/ecma262/#sec-array.prototype.filter
// with adding support of @@species
$({ target: 'Array', proto: true, forced: !HAS_SPECIES_SUPPORT }, {
  filter: function filter(callbackfn /* , thisArg */) {
    return $filter(this, callbackfn, arguments.length > 1 ? arguments[1] : undefined);
  }
});


/***/ }),

/***/ 833:
/***/ ((__unused_webpack_module, __unused_webpack_exports, __webpack_require__) => {

"use strict";

var $ = __webpack_require__(6887);
var $find = (__webpack_require__(3610).find);
var addToUnscopables = __webpack_require__(8479);

var FIND = 'find';
var SKIPS_HOLES = true;

// Shouldn't skip holes
// eslint-disable-next-line es/no-array-prototype-find -- testing
if (FIND in []) Array(1)[FIND](function () { SKIPS_HOLES = false; });

// `Array.prototype.find` method
// https://tc39.es/ecma262/#sec-array.prototype.find
$({ target: 'Array', proto: true, forced: SKIPS_HOLES }, {
  find: function find(callbackfn /* , that = undefined */) {
    return $find(this, callbackfn, arguments.length > 1 ? arguments[1] : undefined);
  }
});

// https://tc39.es/ecma262/#sec-array.prototype-@@unscopables
addToUnscopables(FIND);


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

/***/ 1724:
/***/ ((__unused_webpack_module, __unused_webpack_exports, __webpack_require__) => {

var $ = __webpack_require__(6887);
var toObject = __webpack_require__(9678);
var nativeKeys = __webpack_require__(4771);
var fails = __webpack_require__(5981);

var FAILS_ON_PRIMITIVES = fails(function () { nativeKeys(1); });

// `Object.keys` method
// https://tc39.es/ecma262/#sec-object.keys
$({ target: 'Object', stat: true, forced: FAILS_ON_PRIMITIVES }, {
  keys: function keys(it) {
    return nativeKeys(toObject(it));
  }
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

/***/ 7398:
/***/ ((__unused_webpack_module, __unused_webpack_exports, __webpack_require__) => {

"use strict";

var $ = __webpack_require__(6887);
var $trim = (__webpack_require__(4853).trim);
var forcedStringTrimMethod = __webpack_require__(3093);

// `String.prototype.trim` method
// https://tc39.es/ecma262/#sec-string.prototype.trim
$({ target: 'String', proto: true, forced: forcedStringTrimMethod('trim') }, {
  trim: function trim() {
    return $trim(this);
  }
});


/***/ }),

/***/ 9229:
/***/ ((__unused_webpack_module, __unused_webpack_exports, __webpack_require__) => {

var $ = __webpack_require__(6887);
var global = __webpack_require__(1899);
var schedulersFix = __webpack_require__(7620);

var setInterval = schedulersFix(global.setInterval, true);

// Bun / IE9- setInterval additional parameters fix
// https://html.spec.whatwg.org/multipage/timers-and-user-prompts.html#dom-setinterval
$({ global: true, bind: true, forced: global.setInterval !== setInterval }, {
  setInterval: setInterval
});


/***/ }),

/***/ 7749:
/***/ ((__unused_webpack_module, __unused_webpack_exports, __webpack_require__) => {

var $ = __webpack_require__(6887);
var global = __webpack_require__(1899);
var schedulersFix = __webpack_require__(7620);

var setTimeout = schedulersFix(global.setTimeout, true);

// Bun / IE9- setTimeout additional parameters fix
// https://html.spec.whatwg.org/multipage/timers-and-user-prompts.html#dom-settimeout
$({ global: true, bind: true, forced: global.setTimeout !== setTimeout }, {
  setTimeout: setTimeout
});


/***/ }),

/***/ 1249:
/***/ ((__unused_webpack_module, __unused_webpack_exports, __webpack_require__) => {

// TODO: Remove this module from `core-js@4` since it's split to modules listed below
__webpack_require__(9229);
__webpack_require__(7749);


/***/ }),

/***/ 8196:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var parent = __webpack_require__(6246);

module.exports = parent;


/***/ }),

/***/ 1955:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var parent = __webpack_require__(2480);

module.exports = parent;


/***/ }),

/***/ 1577:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var parent = __webpack_require__(2236);

module.exports = parent;


/***/ }),

/***/ 3778:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var parent = __webpack_require__(8557);

module.exports = parent;


/***/ }),

/***/ 6361:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var parent = __webpack_require__(2774);

module.exports = parent;


/***/ }),

/***/ 3383:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var parent = __webpack_require__(5999);

module.exports = parent;


/***/ }),

/***/ 3059:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var parent = __webpack_require__(8494);

module.exports = parent;


/***/ }),

/***/ 7989:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

__webpack_require__(1249);
var path = __webpack_require__(4058);

module.exports = path.setTimeout;


/***/ }),

/***/ 1189:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

module.exports = __webpack_require__(8196);

/***/ }),

/***/ 4418:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

module.exports = __webpack_require__(1955);

/***/ }),

/***/ 1679:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

module.exports = __webpack_require__(1577);

/***/ }),

/***/ 8118:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

module.exports = __webpack_require__(3778);

/***/ }),

/***/ 1607:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

module.exports = __webpack_require__(6361);

/***/ }),

/***/ 6986:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

module.exports = __webpack_require__(3383);

/***/ }),

/***/ 8222:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

module.exports = __webpack_require__(3059);

/***/ }),

/***/ 7198:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

module.exports = __webpack_require__(7989);

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
/* harmony import */ var _babel_runtime_corejs3_core_js_stable_object_keys__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(8222);
/* harmony import */ var _babel_runtime_corejs3_core_js_stable_object_keys__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_corejs3_core_js_stable_object_keys__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _babel_runtime_corejs3_core_js_stable_instance_includes__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(8118);
/* harmony import */ var _babel_runtime_corejs3_core_js_stable_instance_includes__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_corejs3_core_js_stable_instance_includes__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _babel_runtime_corejs3_core_js_stable_instance_trim__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(1607);
/* harmony import */ var _babel_runtime_corejs3_core_js_stable_instance_trim__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_corejs3_core_js_stable_instance_trim__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _babel_runtime_corejs3_core_js_stable_instance_filter__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(4418);
/* harmony import */ var _babel_runtime_corejs3_core_js_stable_instance_filter__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_corejs3_core_js_stable_instance_filter__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _babel_runtime_corejs3_core_js_stable_object_assign__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(6986);
/* harmony import */ var _babel_runtime_corejs3_core_js_stable_object_assign__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_corejs3_core_js_stable_object_assign__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var _babel_runtime_corejs3_core_js_stable_instance_bind__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(1189);
/* harmony import */ var _babel_runtime_corejs3_core_js_stable_instance_bind__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_corejs3_core_js_stable_instance_bind__WEBPACK_IMPORTED_MODULE_5__);
/* harmony import */ var _babel_runtime_corejs3_core_js_stable_set_timeout__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(7198);
/* harmony import */ var _babel_runtime_corejs3_core_js_stable_set_timeout__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_corejs3_core_js_stable_set_timeout__WEBPACK_IMPORTED_MODULE_6__);
/* harmony import */ var _babel_runtime_corejs3_core_js_stable_instance_find__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(1679);
/* harmony import */ var _babel_runtime_corejs3_core_js_stable_instance_find__WEBPACK_IMPORTED_MODULE_7___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_corejs3_core_js_stable_instance_find__WEBPACK_IMPORTED_MODULE_7__);








/*global jQuery, window, document, setTimeout, console, amazon_payments_advanced_params, amazon, OffAmazonPayments */
jQuery(function ($) {
  var referenceId,
    billingAgreementId,
    addressBookWidgetExists,
    buttonLoaded = false;
  var renderAddressBookWidget = amazon_payments_advanced_params.render_address_widget;
  var germanSpeakingCountries = ['AT', 'DE'];

  /**
   * Helper method for logging - don't want to cause an error trying to log an error!
   */
  function logError() {
    if ('undefined' === typeof console.log) {
      return;
    }
    console.log.apply(console, arguments);
  }
  function wcAmazonErrorToString(error) {
    var message = '';
    if ('object' !== typeof error) {
      return message;
    }
    if ('function' === typeof error.getErrorCode) {
      message += '(' + error.getErrorCode() + ') ';
    }
    if ('function' === typeof error.getErrorMessage) {
      message += error.getErrorMessage();
    }
    return message;
  }
  function isAmazonCheckout() {
    return 'amazon_payments_advanced' === $('input[name=payment_method]:checked').val();
  }

  // Login with Amazon Widget.
  wcAmazonPaymentsButton();

  // AddressBook, Wallet, and maybe Recurring Payment Consent widgets.
  addressBookWidgetExists = $('#amazon_addressbook_widget').length > 0;
  if (renderAddressBookWidget && addressBookWidgetExists) {
    wcAmazonWidgets();
  } else {
    wcAmazonWalletWidget();
  }
  function wcAmazonOnPaymentSelect(orderReference) {
    if (orderReference && orderReference.getAmazonOrderReferenceId) {
      referenceId = orderReference.getAmazonOrderReferenceId();
    }
    renderReferenceIdInput();
    // When wcAmazonOnPaymentSelect is set on walletConfig it overrides the address update handler
    // so it needs to be called from here.
    wcAmazonUpdateCheckoutAddresses();
  }
  function wcAmazonOnOrderReferenceCreate(orderReference) {
    if (referenceId) {
      return;
    }
    referenceId = orderReference.getAmazonOrderReferenceId();
    renderReferenceIdInput();
  }
  function renderReferenceIdInput() {
    // Added the reference ID field.
    $('input.amazon-reference-id').remove();
    var referenceIdInput = '<input class="amazon-reference-id" type="hidden" name="amazon_reference_id" value="' + referenceId + '" />';
    $('form.checkout').append(referenceIdInput);
    $('form#order_review').append(referenceIdInput);
  }
  function wcAmazonOnBillingAgreementCreate(billingAgreement) {
    if (billingAgreementId) {
      return;
    }
    billingAgreementId = billingAgreement.getAmazonBillingAgreementId();
    var billingAgreementIdInput = '<input class="amazon-billing-agreement-id" type="hidden" name="amazon_billing_agreement_id" value="' + billingAgreementId + '" />';
    $('form.checkout').append(billingAgreementIdInput);
    $('form#order_review').append(billingAgreementIdInput);
    $('#amazon_consent_widget').show();
  }
  function editFormInputField(name, value) {
    if ($('#' + name).length) {
      $('#' + name).val(value || '').change();
    }
  }
  function formatAmazonName(name) {
    // Use fallback value for the last name to avoid field required errors.
    var lastNameFallback = '.';
    var names = name.split(' ');
    return {
      first_name: names.shift(),
      last_name: names.join(' ') || lastNameFallback
    };
  }

  /*
   * Some address fields could have a string value of "undefined", causing issues
   * when filling the form. This method removes any field with that value prior to
   * formatting the addresses and filling the form.
   */
  function removeUndefinedStrings(address) {
    var fieldList = _babel_runtime_corejs3_core_js_stable_object_keys__WEBPACK_IMPORTED_MODULE_0___default()(address);
    for (var key in fieldList) {
      var field = fieldList[key];
      if ('undefined' === address[field]) {
        delete address[field];
      }
    }
  }
  function formatAmazonAddress(address) {
    removeUndefinedStrings(address); // Remove eventual "undefined" string values from properties.
    var name = formatAmazonName(address.Name);
    var formattedAddress = {
      first_name: name.first_name,
      last_name: name.last_name
    };

    // Special handling for German speaking countries.
    //
    // @see https://github.com/woothemes/woocommerce-gateway-amazon-payments-advanced/issues/25
    if (address.CountryCode && _babel_runtime_corejs3_core_js_stable_instance_includes__WEBPACK_IMPORTED_MODULE_1___default()(germanSpeakingCountries).call(germanSpeakingCountries, address.CountryCode)) {
      if (address.AddressLine3) {
        var companyName = address.AddressLine1 + ' ' + address.AddressLine2;
        formattedAddress.company = _babel_runtime_corejs3_core_js_stable_instance_trim__WEBPACK_IMPORTED_MODULE_2___default()(companyName).call(companyName);
        formattedAddress.address_1 = address.AddressLine3;
      } else if (address.AddressLine2) {
        formattedAddress.company = address.AddressLine1;
        formattedAddress.address_1 = address.AddressLine2;
      } else {
        formattedAddress.address_1 = address.AddressLine1;
      }
    } else {
      var _context;
      var addressLines = _babel_runtime_corejs3_core_js_stable_instance_filter__WEBPACK_IMPORTED_MODULE_3___default()(_context = [address.AddressLine1, address.AddressLine2, address.AddressLine3]).call(_context, function (a) {
        return a;
      });
      if (3 === addressLines.length) {
        formattedAddress.company = addressLines[0];
        formattedAddress.address_1 = addressLines[1];
        formattedAddress.address_2 = addressLines[2];
      } else if (2 === addressLines.length) {
        formattedAddress.address_1 = addressLines[0];
        formattedAddress.address_2 = addressLines[1];
      } else {
        formattedAddress.address_1 = addressLines[0];
      }
    }

    // country needs to be set prior to state due to form validation
    formattedAddress.country = address.CountryCode;
    formattedAddress.phone = address.Phone;
    formattedAddress.city = address.City;
    formattedAddress.postcode = address.PostalCode;
    formattedAddress.state = address.StateOrRegion;
    return formattedAddress;
  }
  function setOrderAddress(prefix, address) {
    var fieldList = _babel_runtime_corejs3_core_js_stable_object_keys__WEBPACK_IMPORTED_MODULE_0___default()(address);
    for (var key in fieldList) {
      var field = fieldList[key];
      editFormInputField(prefix + field, address[field]);
    }
  }
  function clearOrderFormData() {
    var fieldsToBeCleared = ['billing_first_name', 'billing_last_name', 'billing_company', 'billing_address_1', 'billing_address_2', 'billing_phone', 'billing_city', 'billing_postcode', 'billing_state', 'billing_country', 'billing_email', 'shipping_first_name', 'shipping_last_name', 'shipping_company', 'shipping_address_1', 'shipping_address_2', 'shipping_phone', 'shipping_city', 'shipping_postcode', 'shipping_state', 'shipping_country'];
    for (var field in fieldsToBeCleared) {
      editFormInputField(fieldsToBeCleared[field], '');
    }
  }
  function setOrderFormData(orderReference) {
    clearOrderFormData();
    var hasShippingAddress = orderReference.Destination && orderReference.Destination.PhysicalDestination;
    var hasBillingAddress = orderReference.BillingAddress && orderReference.BillingAddress.PhysicalAddress;
    var shippingAddress = hasShippingAddress ? formatAmazonAddress(orderReference.Destination.PhysicalDestination) : {};
    var billingAddress = null;
    if (hasBillingAddress) {
      billingAddress = formatAmazonAddress(orderReference.BillingAddress.PhysicalAddress);
    } else {
      var buyerName = formatAmazonName(orderReference.Buyer.Name);
      billingAddress = _babel_runtime_corejs3_core_js_stable_object_assign__WEBPACK_IMPORTED_MODULE_4___default()({}, shippingAddress);
      billingAddress.first_name = buyerName.first_name;
      billingAddress.last_name = buyerName.last_name;
    }
    billingAddress.email = orderReference.Buyer.Email;
    billingAddress.phone = billingAddress.phone || orderReference.Buyer.Phone;
    setOrderAddress('billing_', billingAddress);
    if (hasShippingAddress) {
      setOrderAddress('shipping_', shippingAddress);
    }
  }
  function wcAmazonUpdateCheckoutAddresses() {
    blockCheckoutForm();
    $.ajax({
      type: 'post',
      url: amazon_payments_advanced_params.ajax_url,
      data: {
        action: 'amazon_get_order_reference',
        nonce: amazon_payments_advanced_params.order_reference_nonce,
        order_reference_id: referenceId
      },
      success: function (orderReference) {
        setOrderFormData(orderReference);
        unblockCheckoutForm();
        $('body').trigger('update_checkout');
      },
      error: function (jqXHR, textStatus, errorThrown) {
        logError('Error encountered in amazon_get_order_reference:', jqXHR.responseJSON || errorThrown);
        unblockCheckoutForm();
      }
    });
  }
  function wcAmazonPaymentsButton() {
    if (buttonLoaded) {
      return;
    }
    if (0 !== $('#pay_with_amazon').length && 0 === $('.amazonpay-button-inner-image').length) {
      var popup = true;
      if ('optimal' === amazon_payments_advanced_params.redirect_authentication && isSmallScreen()) {
        popup = false;
      }
      var buttonWidgetParams = {
        type: amazon_payments_advanced_params.button_type,
        color: amazon_payments_advanced_params.button_color,
        size: amazon_payments_advanced_params.button_size,
        authorization: function () {
          var loginOptions = {
            scope: 'profile postal_code payments:widget payments:shipping_address payments:billing_address',
            popup: popup
          };
          amazon.Login.authorize(loginOptions, amazon_payments_advanced_params.redirect);
        },
        onError: function (error) {
          var msg = wcAmazonErrorToString(error);
          logError('Error encountered in OffAmazonPayments.Button', msg ? ': ' + msg : '');
        }
      };
      if ('' !== amazon_payments_advanced_params.button_language) {
        buttonWidgetParams.language = amazon_payments_advanced_params.button_language;
      }
      OffAmazonPayments.Button('pay_with_amazon', amazon_payments_advanced_params.seller_id, buttonWidgetParams);
      buttonLoaded = true;
    }
  }
  function isSmallScreen() {
    return window.innerWidth <= 800;
  }
  function toggleFieldVisibility(type, key) {
    var fieldWrapper = $('#' + type + '_' + key + '_field'),
      fieldValue = $('#' + type + '_' + key).val();
    fieldWrapper.addClass('hidden');
    $('.woocommerce-' + type + '-fields').addClass('hidden');
    if (fieldValue == null || fieldValue === '') {
      fieldWrapper.removeClass('hidden');
      $('.woocommerce-' + type + '-fields').removeClass('hidden');
    }
  }
  function toggleDetailsVisibility(detailsListName) {
    if ($('.' + detailsListName + '__field-wrapper').children(':not(.hidden)').length === 0) {
      $('.' + detailsListName).addClass('hidden');
    } else {
      $('.' + detailsListName).removeClass('hidden');
    }
  }
  toggleDetailsVisibility('woocommerce-billing-fields');
  toggleDetailsVisibility('woocommerce-shipping-fields');
  toggleDetailsVisibility('woocommerce-additional-fields');
  function blockCheckoutForm(form) {
    form = form ? form : $('form.checkout');
    form.addClass('processing').block({
      message: null,
      overlayCSS: {
        background: '#fff',
        opacity: 0.6
      }
    });
  }
  function unblockCheckoutForm(form) {
    form = form ? form : $('form.checkout');
    form.removeClass('processing').unblock();
  }
  function wcAmazonWidgets() {
    var _context2;
    var addressBookConfig = {
      sellerId: amazon_payments_advanced_params.seller_id,
      onReady: function () {
        wcAmazonWalletWidget();
        $(document).trigger('wc_amazon_pa_widget_ready');
      },
      onOrderReferenceCreate: wcAmazonOnOrderReferenceCreate,
      design: {
        designMode: 'responsive'
      },
      onError: function (error) {
        var msg = wcAmazonErrorToString(error);
        logError('Error encountered in OffAmazonPayments.Widgets.AddressBook', msg ? ': ' + msg : '');
      }
    };
    var isRecurring = amazon_payments_advanced_params.is_recurring;
    var declinedCode = amazon_payments_advanced_params.declined_code;

    // When declinedCode is 'AmazonRejected' rendering AddressBook may throw
    // an error 'UnknownError: Unknown error in the service', so it's best
    // to bail out instantiating the widget and redirect asap.
    if ('AmazonRejected' === declinedCode) {
      return wcAmazonMaybeRedirectAfterDeclined();
    }
    if (isRecurring) {
      addressBookConfig.agreementType = 'BillingAgreement';
      addressBookConfig.onReady = function (billingAgreement) {
        wcAmazonOnBillingAgreementCreate(billingAgreement);
        wcAmazonWalletWidget();
        wcAmazonConsentWidget();
        $(document).trigger('wc_amazon_pa_widget_ready');
      };
    }
    if (declinedCode) {
      addressBookConfig.displayMode = 'Read';
      addressBookConfig.amazonOrderReferenceId = amazon_payments_advanced_params.reference_id;
      delete addressBookConfig.onOrderReferenceCreate;
    }
    _babel_runtime_corejs3_core_js_stable_instance_bind__WEBPACK_IMPORTED_MODULE_5___default()(_context2 = new OffAmazonPayments.Widgets.AddressBook(addressBookConfig)).call(_context2, 'amazon_addressbook_widget');
  }

  // To be used on some multicurrency solutions, that change currency without reloading the checkout, then we need to rebind the widget with proper currency.
  if (amazon_payments_advanced_params.multi_currency_supported && amazon_payments_advanced_params.multi_currency_reload_wallet) {
    $(document.body).on('updated_checkout', function () {
      $.ajax({
        url: amazon_payments_advanced_params.ajax_url,
        type: 'post',
        data: {
          action: 'amazon_get_currency',
          nonce: amazon_payments_advanced_params.multi_currency_nonce
        },
        success: function (response) {
          wcAmazonWalletWidget(response);
        }
      });
    });
  }

  // Holds walletWidget instance.
  var walletWidget = null;

  // Wallet widget
  function wcAmazonWalletWidget(force_currency) {
    // If previously declined with redirection to cart, do not render the
    // wallet widget.
    if (amazon_payments_advanced_params.declined_redirect_url) {
      return;
    }

    // Only instantiate walletWidget once
    if (!walletWidget) {
      var walletConfig = {
        sellerId: amazon_payments_advanced_params.seller_id,
        design: {
          designMode: 'responsive'
        },
        // Update the checkout addresses when a shipping address is selected or when payment method changes.
        // Since onPaymentSelect is fired on both actions, we're not using onAddressSelect to avoid fetching the
        // same data twice.
        onPaymentSelect: wcAmazonUpdateCheckoutAddresses,
        onError: function (error) {
          var msg = wcAmazonErrorToString(error);
          logError('Error encountered in OffAmazonPayments.Widgets.Wallet', msg ? ': ' + msg : '');
        }
      };
      if (amazon_payments_advanced_params.reference_id) {
        referenceId = amazon_payments_advanced_params.reference_id;
        walletConfig.amazonOrderReferenceId = referenceId;
        walletConfig.onPaymentSelect = wcAmazonOnPaymentSelect;
      }
      if (!renderAddressBookWidget || !addressBookWidgetExists) {
        walletConfig.onOrderReferenceCreate = wcAmazonOnOrderReferenceCreate;
      }
      if (amazon_payments_advanced_params.is_recurring) {
        walletConfig.agreementType = 'BillingAgreement';
        if (billingAgreementId) {
          walletConfig.amazonBillingAgreementId = billingAgreementId;
        } else {
          walletConfig.onReady = function (billingAgreement) {
            wcAmazonOnBillingAgreementCreate(billingAgreement);
            wcAmazonConsentWidget();
          };
        }
      }
      walletWidget = new OffAmazonPayments.Widgets.Wallet(walletConfig);
    }
    if (amazon_payments_advanced_params.multi_currency_supported) {
      var currency = force_currency ? force_currency : amazon_payments_advanced_params.current_currency;
      walletWidget.setPresentmentCurrency(currency);
    }
    _babel_runtime_corejs3_core_js_stable_instance_bind__WEBPACK_IMPORTED_MODULE_5___default()(walletWidget).call(walletWidget, 'amazon_wallet_widget');
  }

  // Recurring payment consent widget
  function wcAmazonConsentWidget() {
    var _context3;
    if (!amazon_payments_advanced_params.is_recurring || !billingAgreementId) {
      return;
    }
    _babel_runtime_corejs3_core_js_stable_instance_bind__WEBPACK_IMPORTED_MODULE_5___default()(_context3 = new OffAmazonPayments.Widgets.Consent({
      sellerId: amazon_payments_advanced_params.seller_id,
      amazonBillingAgreementId: billingAgreementId,
      design: {
        designMode: 'responsive'
      },
      onReady: toggleAbleCheckoutButton,
      onConsent: toggleAbleCheckoutButton,
      onError: function (error) {
        var msg = wcAmazonErrorToString(error);
        logError('Error encountered in OffAmazonPayments.Widgets.Consent', msg ? ': ' + msg : '');
      }
    })).call(_context3, 'amazon_consent_widget');
  }
  function toggleAbleCheckoutButton(billingAgreementConsentStatus) {
    var buyerBillingAgreementConsentStatus = billingAgreementConsentStatus.getConsentStatus();
    if (buyerBillingAgreementConsentStatus !== 'undefined') {
      /* eslint-disable eqeqeq */
      $('#place_order').css('opacity', 'true' == buyerBillingAgreementConsentStatus ? 1 : 0.5);
      $('#place_order').prop('disabled', 'true' != buyerBillingAgreementConsentStatus);
      /* eslint-enable eqeqeq */
      return;
    }
    $('#amazon_consent_widget').hide();
  }

  /**
   * Maybe redirect to cart page when authorization is declined by Amazon.
   *
   * When authorization is declined, `amazon_payments_advanced_params.declined_redirect_url`
   * will be set globally (by PHP). This func handles the scroll to the notice
   * and the redirection once page is rendered.
   *
   * @see https://github.com/woocommerce/woocommerce-gateway-amazon-payments-advanced/issues/214
   * @see https://github.com/woocommerce/woocommerce-gateway-amazon-payments-advanced/issues/247
   */
  function wcAmazonMaybeRedirectAfterDeclined() {
    if (amazon_payments_advanced_params.declined_redirect_url) {
      // Scroll to top so customer notices the error message before being
      // redirected to cancel order URL.
      $('body').on('updated_checkout', function () {
        $('html, body').scrollTop(0);

        // Gives time for customer to read the notice. It's short notice
        // (plus confirmation about redirection) and similar message will
        // be displayed again in the cart page. So 3s should be sufficient.
        _babel_runtime_corejs3_core_js_stable_set_timeout__WEBPACK_IMPORTED_MODULE_6___default()(function () {
          window.location = amazon_payments_advanced_params.declined_redirect_url;
        }, 3000);
      });
    }
  }
  $('body').on('click', '#amazon-logout', function () {
    amazon.Login.logout();
    document.cookie = 'amazon_Login_accessToken=; expires=Thu, 01 Jan 1970 00:00:00 GMT';
    window.location = amazon_payments_advanced_params.redirect_url;
  });

  /**
   *
   * The AJAX order review refresh causes some duplicate form fields to be created.
   * If we're checking out with Amazon enabled and creating a new account, disable the duplicate fields
   * that don't have values so we don't overwrite the good values in $_POST
   *
   */
  $('form.checkout').on('checkout_place_order', function () {
    var _context4;
    var fieldSelectors = [':input[name=billing_email]', ':input[name=billing_first_name]', ':input[name=billing_last_name]', ':input[name=account_username]', ':input[name=account_password]', ':input[name=createaccount]'].join(',');
    _babel_runtime_corejs3_core_js_stable_instance_find__WEBPACK_IMPORTED_MODULE_7___default()(_context4 = $(this)).call(_context4, fieldSelectors).each(function () {
      var $input = $(this);
      if ('' === $input.val() && $input.is(':hidden')) {
        $input.attr('disabled', 'disabled');
      }

      // For createaccount checkbox, the value on dupe element should
      // matches with visible createaccount checkbox.
      if ('createaccount' === $input.attr('name') && $('#createaccount').length) {
        $input.prop('checked', $('#createaccount').is(':checked'));
      }
    });
  });
  $('body').on('updated_checkout', wcAmazonConsentWidget);
  $('body').on('updated_checkout', wcAmazonPaymentsButton);
  $('body').on('updated_cart_totals', function () {
    buttonLoaded = false;
    wcAmazonPaymentsButton();
  });
  $(window.document).on('wc_amazon_pa_widget_ready', function () {
    wcAmazonMaybeRedirectAfterDeclined();
  });

  // Hijack form checkout button to trigger SCA if needed.
  $('form.checkout').on('checkout_place_order', function (evt) {
    // Check if Amazon is selected
    if (!isAmazonCheckout()) {
      return true;
    }

    // On SCA implementations (EU regions)
    if (!amazon_payments_advanced_params.is_sca) {
      return true;
    }
    var $form = $(this);
    OffAmazonPayments.initConfirmationFlow(amazon_payments_advanced_params.seller_id, referenceId, function (amazonPayFlow) {
      placeOrder(amazonPayFlow, $form);
    });
    evt.preventDefault();
    return false;
  });

  /**
   *
   * @param amazonPayFlow
   * @param $form
   */
  function placeOrder(amazonPayFlow, $form) {
    blockCheckoutForm($form);
    $.ajax({
      type: 'post',
      url: amazon_payments_advanced_params.ajax_url,
      data: {
        action: 'amazon_sca_processing',
        nonce: amazon_payments_advanced_params.sca_nonce,
        data: $('form.checkout').serialize()
      },
      success: function (result) {
        if ('success' === result.result) {
          amazonPayFlow.success();
        } else if ('failure' === result.result) {
          amazonPayFlow.error();
          // Reload page
          if (true === result.reload) {
            window.location.reload();
            return;
          }
          // Trigger update in case we need a fresh nonce
          if (true === result.refresh) {
            $(document.body).trigger('update_checkout');
          }
          submit_error(result.messages, $form);
        }
      },
      error: function (jqXHR, textStatus, errorThrown) {
        amazonPayFlow.error();
        submit_error('<div class="woocommerce-error">' + errorThrown + '</div>', $form);
      }
    });
  }

  /**
   * Submit error to checkout page coming from AJAX call.
   *
   * @param error_message
   * @param $form
   */
  function submit_error(error_message, $form) {
    $('.woocommerce-NoticeGroup-checkout, .woocommerce-error, .woocommerce-message').remove();
    $form.prepend('<div class="woocommerce-NoticeGroup woocommerce-NoticeGroup-checkout">' + error_message + '</div>');
    $form.removeClass('processing').unblock();
    _babel_runtime_corejs3_core_js_stable_instance_find__WEBPACK_IMPORTED_MODULE_7___default()($form).call($form, '.input-text, select, input:checkbox').trigger('validate').blur();
    scroll_to_notices();
    $(document.body).trigger('checkout_error');
  }

  /**
   * Scroll to errors.
   */
  function scroll_to_notices() {
    var scrollElement = $('.woocommerce-NoticeGroup-updateOrderReview, .woocommerce-NoticeGroup-checkout');
    if (!scrollElement.length) {
      scrollElement = $('.form.checkout');
    }
    $.scroll_to_notices(scrollElement);
  }
  $('body').on('updated_checkout', function () {
    if (!isAmazonCheckout()) {
      return;
    }
    toggleFieldVisibility('shipping', 'state');
    if ($('.woocommerce-billing-fields .woocommerce-billing-fields__field-wrapper > *').length > 0) {
      $('.woocommerce-billing-fields').insertBefore('#payment');
    }
    if ($('.woocommerce-shipping-fields .woocommerce-shipping-fields__field-wrapper > *').length > 0) {
      var title = $('#ship-to-different-address');
      _babel_runtime_corejs3_core_js_stable_instance_find__WEBPACK_IMPORTED_MODULE_7___default()(title).call(title, ':checkbox#ship-to-different-address-checkbox').hide();
      _babel_runtime_corejs3_core_js_stable_instance_find__WEBPACK_IMPORTED_MODULE_7___default()(title).call(title, 'span').text(amazon_payments_advanced_params.shipping_title);
      $('.woocommerce-shipping-fields').insertBefore('#payment');
    }
    $('.woocommerce-additional-fields').insertBefore('#payment');
  });
});
})();

/******/ })()
;