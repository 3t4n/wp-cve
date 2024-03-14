// Element.matches() polyfill
if (!Element.prototype.matches) {
	Element.prototype.matches =
		Element.prototype.matchesSelector ||
		Element.prototype.mozMatchesSelector ||
		Element.prototype.msMatchesSelector ||
		Element.prototype.oMatchesSelector ||
		Element.prototype.webkitMatchesSelector ||
		function(s) {
			let matches = (this.document || this.ownerDocument).querySelectorAll(s),
				i = matches.length;
			while (--i >= 0 && matches.item(i) !== this) {}
			return i > -1;
		};
}

// Element.closest()
if (!Element.prototype.closest) {
	Element.prototype.closest = function(s) {
		var el = this;

		do {
			if (Element.prototype.matches.call(el, s)) return el;
			el = el.parentElement || el.parentNode;
		}
		while (el !== null && el.nodeType === 1);
		return null;
	};
}

// Element.classList
if ("document" in self) {
	// Full polyfill for browsers with no classList support
	// Including IE < Edge missing SVGElement.classList
	if (!("classList" in document.createElement("_"))
		|| document.createElementNS && !("classList" in document.createElementNS("http://www.w3.org/2000/svg", "g"))) {

		(function (view) {

			"use strict";

			if (!('Element' in view)) return;

			var
				classListProp = "classList"
				, protoProp = "prototype"
				, elemCtrProto = view.Element[protoProp]
				, objCtr = Object
				, strTrim = String[protoProp].trim || function () {
					return this.replace(/^\s+|\s+$/g, "");
				}
				, arrIndexOf = Array[protoProp].indexOf || function (item) {
					var
						i = 0
						, len = this.length
					;
					for (; i < len; i++) {
						if (i in this && this[i] === item) {
							return i;
						}
					}
					return -1;
				}
				// Vendors: please allow content code to instantiate DOMExceptions
				, DOMEx = function (type, message) {
					this.name = type;
					this.code = DOMException[type];
					this.message = message;
				}
				, checkTokenAndGetIndex = function (classList, token) {
					if (token === "") {
						throw new DOMEx(
							"SYNTAX_ERR"
							, "An invalid or illegal string was specified"
						);
					}
					if (/\s/.test(token)) {
						throw new DOMEx(
							"INVALID_CHARACTER_ERR"
							, "String contains an invalid character"
						);
					}
					return arrIndexOf.call(classList, token);
				}
				, ClassList = function (elem) {
					var
						trimmedClasses = strTrim.call(elem.getAttribute("class") || "")
						, classes = trimmedClasses ? trimmedClasses.split(/\s+/) : []
						, i = 0
						, len = classes.length
					;
					for (; i < len; i++) {
						this.push(classes[i]);
					}
					this._updateClassName = function () {
						elem.setAttribute("class", this.toString());
					};
				}
				, classListProto = ClassList[protoProp] = []
				, classListGetter = function () {
					return new ClassList(this);
				}
			;
			// Most DOMException implementations don't allow calling DOMException's toString()
			// on non-DOMExceptions. Error's toString() is sufficient here.
			DOMEx[protoProp] = Error[protoProp];
			classListProto.item = function (i) {
				return this[i] || null;
			};
			classListProto.contains = function (token) {
				token += "";
				return checkTokenAndGetIndex(this, token) !== -1;
			};
			classListProto.add = function () {
				var
					tokens = arguments
					, i = 0
					, l = tokens.length
					, token
					, updated = false
				;
				do {
					token = tokens[i] + "";
					if (checkTokenAndGetIndex(this, token) === -1) {
						this.push(token);
						updated = true;
					}
				}
				while (++i < l);

				if (updated) {
					this._updateClassName();
				}
			};
			classListProto.remove = function () {
				var
					tokens = arguments
					, i = 0
					, l = tokens.length
					, token
					, updated = false
					, index
				;
				do {
					token = tokens[i] + "";
					index = checkTokenAndGetIndex(this, token);
					while (index !== -1) {
						this.splice(index, 1);
						updated = true;
						index = checkTokenAndGetIndex(this, token);
					}
				}
				while (++i < l);

				if (updated) {
					this._updateClassName();
				}
			};
			classListProto.toggle = function (token, force) {
				token += "";

				var
					result = this.contains(token)
					, method = result ?
					force !== true && "remove"
					:
					force !== false && "add"
				;

				if (method) {
					this[method](token);
				}

				if (force === true || force === false) {
					return force;
				} else {
					return !result;
				}
			};
			classListProto.toString = function () {
				return this.join(" ");
			};

			if (objCtr.defineProperty) {
				var classListPropDesc = {
					get: classListGetter
					, enumerable: true
					, configurable: true
				};
				try {
					objCtr.defineProperty(elemCtrProto, classListProp, classListPropDesc);
				} catch (ex) { // IE 8 doesn't support enumerable:true
					// adding undefined to fight this issue https://github.com/eligrey/classList.js/issues/36
					// modernie IE8-MSW7 machine has IE8 8.0.6001.18702 and is affected
					if (ex.number === undefined || ex.number === -0x7FF5EC54) {
						classListPropDesc.enumerable = false;
						objCtr.defineProperty(elemCtrProto, classListProp, classListPropDesc);
					}
				}
			} else if (objCtr[protoProp].__defineGetter__) {
				elemCtrProto.__defineGetter__(classListProp, classListGetter);
			}

		}(self));

	}

	// There is full or partial native classList support, so just check if we need
	// to normalize the add/remove and toggle APIs.

	(function () {
		"use strict";

		var testElement = document.createElement("_");

		testElement.classList.add("c1", "c2");

		// Polyfill for IE 10/11 and Firefox <26, where classList.add and
		// classList.remove exist but support only one argument at a time.
		if (!testElement.classList.contains("c2")) {
			var createMethod = function (method) {
				var original = DOMTokenList.prototype[method];

				DOMTokenList.prototype[method] = function (token) {
					var i, len = arguments.length;

					for (i = 0; i < len; i++) {
						token = arguments[i];
						original.call(this, token);
					}
				};
			};
			createMethod('add');
			createMethod('remove');
		}

		testElement.classList.toggle("c3", false);

		// Polyfill for IE 10 and Firefox <24, where classList.toggle does not
		// support the second argument.
		if (testElement.classList.contains("c3")) {
			var _toggle = DOMTokenList.prototype.toggle;

			DOMTokenList.prototype.toggle = function (token, force) {
				if (1 in arguments && !this.contains(token) === !force) {
					return force;
				} else {
					return _toggle.call(this, token);
				}
			};

		}

		testElement = null;
	}());
}

// Array.from
if (!Array.from) {
	Array.from = (function () {
		var symbolIterator;
		try {
			symbolIterator = Symbol.iterator
				? Symbol.iterator
				: 'Symbol(Symbol.iterator)';
		} catch (e) {
			symbolIterator = 'Symbol(Symbol.iterator)';
		}

		var toStr = Object.prototype.toString;
		var isCallable = function (fn) {
			return (
				typeof fn === 'function' ||
				toStr.call(fn) === '[object Function]'
			);
		};
		var toInteger = function (value) {
			var number = Number(value);
			if (isNaN(number)) return 0;
			if (number === 0 || !isFinite(number)) return number;
			return (number > 0 ? 1 : -1) * Math.floor(Math.abs(number));
		};
		var maxSafeInteger = Math.pow(2, 53) - 1;
		var toLength = function (value) {
			var len = toInteger(value);
			return Math.min(Math.max(len, 0), maxSafeInteger);
		};

		var setGetItemHandler = function setGetItemHandler(isIterator, items) {
			var iterator = isIterator && items[symbolIterator]();
			return function getItem(k) {
				return isIterator ? iterator.next() : items[k];
			};
		};

		var getArray = function getArray(
			T,
			A,
			len,
			getItem,
			isIterator,
			mapFn
		) {
			// 16. Let k be 0.
			var k = 0;

			// 17. Repeat, while k < len… or while iterator is done (also steps a - h)
			while (k < len || isIterator) {
				var item = getItem(k);
				var kValue = isIterator ? item.value : item;

				if (isIterator && item.done) {
					return A;
				} else {
					if (mapFn) {
						A[k] =
							typeof T === 'undefined'
								? mapFn(kValue, k)
								: mapFn.call(T, kValue, k);
					} else {
						A[k] = kValue;
					}
				}
				k += 1;
			}

			if (isIterator) {
				throw new TypeError(
					'Array.from: provided arrayLike or iterator has length more then 2 ** 52 - 1'
				);
			} else {
				A.length = len;
			}

			return A;
		};

		// The length property of the from method is 1.
		return function from(arrayLikeOrIterator /*, mapFn, thisArg */) {
			// 1. Let C be the this value.
			var C = this;

			// 2. Let items be ToObject(arrayLikeOrIterator).
			var items = Object(arrayLikeOrIterator);
			var isIterator = isCallable(items[symbolIterator]);

			// 3. ReturnIfAbrupt(items).
			if (arrayLikeOrIterator == null && !isIterator) {
				throw new TypeError(
					'Array.from requires an array-like object or iterator - not null or undefined'
				);
			}

			// 4. If mapfn is undefined, then let mapping be false.
			var mapFn = arguments.length > 1 ? arguments[1] : void undefined;
			var T;
			if (typeof mapFn !== 'undefined') {
				// 5. else
				// 5. a If IsCallable(mapfn) is false, throw a TypeError exception.
				if (!isCallable(mapFn)) {
					throw new TypeError(
						'Array.from: when provided, the second argument must be a function'
					);
				}

				// 5. b. If thisArg was supplied, let T be thisArg; else let T be undefined.
				if (arguments.length > 2) {
					T = arguments[2];
				}
			}

			// 10. Let lenValue be Get(items, "length").
			// 11. Let len be ToLength(lenValue).
			var len = toLength(items.length);

			// 13. If IsConstructor(C) is true, then
			// 13. a. Let A be the result of calling the [[Construct]] internal method
			// of C with an argument list containing the single item len.
			// 14. a. Else, Let A be ArrayCreate(len).
			var A = isCallable(C) ? Object(new C(len)) : new Array(len);

			return getArray(
				T,
				A,
				len,
				setGetItemHandler(isIterator, items),
				isIterator,
				mapFn
			);
		};
	})();
}

// Array.forEach
if (!Array.prototype.forEach) {
	Array.prototype.forEach = function (callback, thisArg) {
		thisArg = thisArg || window;
		for (var i = 0; i < this.length; i++) {
			callback.call(thisArg, this[i], i, this);
		}
	};
}

// Array.includes
if (!Array.prototype.includes) {
	Object.defineProperty(Array.prototype, 'includes', {
		value: function(searchElement, fromIndex) {

			if (this == null) {
				throw new TypeError('"this" is null or not defined');
			}

			// 1. Let O be ? ToObject(this value).
			var o = Object(this);

			// 2. Let len be ? ToLength(? Get(O, "length")).
			var len = o.length >>> 0;

			// 3. If len is 0, return false.
			if (len === 0) {
				return false;
			}

			// 4. Let n be ? ToInteger(fromIndex).
			//    (If fromIndex is undefined, this step produces the value 0.)
			var n = fromIndex | 0;

			// 5. If n ≥ 0, then
			//  a. Let k be n.
			// 6. Else n < 0,
			//  a. Let k be len + n.
			//  b. If k < 0, let k be 0.
			var k = Math.max(n >= 0 ? n : len - Math.abs(n), 0);

			function sameValueZero(x, y) {
				return x === y || (typeof x === 'number' && typeof y === 'number' && isNaN(x) && isNaN(y));
			}

			// 7. Repeat, while k < len
			while (k < len) {
				// a. Let elementK be the result of ? Get(O, ! ToString(k)).
				// b. If SameValueZero(searchElement, elementK) is true, return true.
				if (sameValueZero(o[k], searchElement)) {
					return true;
				}
				// c. Increase k by 1.
				k++;
			}

			// 8. Return false
			return false;
		}
	});
}

// NodeList.forEach
if (window.NodeList && !NodeList.prototype.forEach) {
	NodeList.prototype.forEach = Array.prototype.forEach;
}

// String.includes
if (!String.prototype.includes) {
	String.prototype.includes = function(search, start) {
		'use strict';

		if (search instanceof RegExp) {
			throw TypeError('first argument must not be a RegExp');
		}
		if (start === undefined) { start = 0; }
		return this.indexOf(search, start) !== -1;
	};
}

// Object.entries
if (!Object.entries) {
	Object.entries = function( obj ){
		var ownProps = Object.keys( obj ),
			i = ownProps.length,
			resArray = new Array(i); // preallocate the Array
		while (i--)
			resArray[i] = [ownProps[i], obj[ownProps[i]]];

		return resArray;
	};
}

// Object.assign
if (typeof Object.assign !== 'function') {
	// Must be writable: true, enumerable: false, configurable: true
	Object.defineProperty(Object, "assign", {
		value: function assign(target, varArgs) { // .length of function is 2
			'use strict';
			if (target === null || target === undefined) {
				throw new TypeError('Cannot convert undefined or null to object');
			}

			var to = Object(target);

			for (var index = 1; index < arguments.length; index++) {
				var nextSource = arguments[index];

				if (nextSource !== null && nextSource !== undefined) {
					for (var nextKey in nextSource) {
						// Avoid bugs when hasOwnProperty is shadowed
						if (Object.prototype.hasOwnProperty.call(nextSource, nextKey)) {
							to[nextKey] = nextSource[nextKey];
						}
					}
				}
			}
			return to;
		},
		writable: true,
		configurable: true
	});
}

// Promise, from https://github.com/taylorhakes/promise-polyfill
(function (global, factory) {
	typeof exports === 'object' && typeof module !== 'undefined' ? factory() :
		typeof define === 'function' && define.amd ? define(factory) :
			(factory());
}(this, (function () { 'use strict';

	/**
	 * @this {Promise}
	 */
	function finallyConstructor(callback) {
		var constructor = this.constructor;
		return this.then(
			function(value) {
				// @ts-ignore
				return constructor.resolve(callback()).then(function() {
					return value;
				});
			},
			function(reason) {
				// @ts-ignore
				return constructor.resolve(callback()).then(function() {
					// @ts-ignore
					return constructor.reject(reason);
				});
			}
		);
	}

	function allSettled(arr) {
		var P = this;
		return new P(function(resolve, reject) {
			if (!(arr && typeof arr.length !== 'undefined')) {
				return reject(
					new TypeError(
						typeof arr +
						' ' +
						arr +
						' is not iterable(cannot read property Symbol(Symbol.iterator))'
					)
				);
			}
			var args = Array.prototype.slice.call(arr);
			if (args.length === 0) return resolve([]);
			var remaining = args.length;

			function res(i, val) {
				if (val && (typeof val === 'object' || typeof val === 'function')) {
					var then = val.then;
					if (typeof then === 'function') {
						then.call(
							val,
							function(val) {
								res(i, val);
							},
							function(e) {
								args[i] = { status: 'rejected', reason: e };
								if (--remaining === 0) {
									resolve(args);
								}
							}
						);
						return;
					}
				}
				args[i] = { status: 'fulfilled', value: val };
				if (--remaining === 0) {
					resolve(args);
				}
			}

			for (var i = 0; i < args.length; i++) {
				res(i, args[i]);
			}
		});
	}

// Store setTimeout reference so promise-polyfill will be unaffected by
// other code modifying setTimeout (like sinon.useFakeTimers())
	var setTimeoutFunc = setTimeout;

	function isArray(x) {
		return Boolean(x && typeof x.length !== 'undefined');
	}

	function noop() {}

// Polyfill for Function.prototype.bind
	function bind(fn, thisArg) {
		return function() {
			fn.apply(thisArg, arguments);
		};
	}

	/**
	 * @constructor
	 * @param {Function} fn
	 */
	function Promise(fn) {
		if (!(this instanceof Promise))
			throw new TypeError('Promises must be constructed via new');
		if (typeof fn !== 'function') throw new TypeError('not a function');
		/** @type {!number} */
		this._state = 0;
		/** @type {!boolean} */
		this._handled = false;
		/** @type {Promise|undefined} */
		this._value = undefined;
		/** @type {!Array<!Function>} */
		this._deferreds = [];

		doResolve(fn, this);
	}

	function handle(self, deferred) {
		while (self._state === 3) {
			self = self._value;
		}
		if (self._state === 0) {
			self._deferreds.push(deferred);
			return;
		}
		self._handled = true;
		Promise._immediateFn(function() {
			var cb = self._state === 1 ? deferred.onFulfilled : deferred.onRejected;
			if (cb === null) {
				(self._state === 1 ? resolve : reject)(deferred.promise, self._value);
				return;
			}
			var ret;
			try {
				ret = cb(self._value);
			} catch (e) {
				reject(deferred.promise, e);
				return;
			}
			resolve(deferred.promise, ret);
		});
	}

	function resolve(self, newValue) {
		try {
			// Promise Resolution Procedure: https://github.com/promises-aplus/promises-spec#the-promise-resolution-procedure
			if (newValue === self)
				throw new TypeError('A promise cannot be resolved with itself.');
			if (
				newValue &&
				(typeof newValue === 'object' || typeof newValue === 'function')
			) {
				var then = newValue.then;
				if (newValue instanceof Promise) {
					self._state = 3;
					self._value = newValue;
					finale(self);
					return;
				} else if (typeof then === 'function') {
					doResolve(bind(then, newValue), self);
					return;
				}
			}
			self._state = 1;
			self._value = newValue;
			finale(self);
		} catch (e) {
			reject(self, e);
		}
	}

	function reject(self, newValue) {
		self._state = 2;
		self._value = newValue;
		finale(self);
	}

	function finale(self) {
		if (self._state === 2 && self._deferreds.length === 0) {
			Promise._immediateFn(function() {
				if (!self._handled) {
					Promise._unhandledRejectionFn(self._value);
				}
			});
		}

		for (var i = 0, len = self._deferreds.length; i < len; i++) {
			handle(self, self._deferreds[i]);
		}
		self._deferreds = null;
	}

	/**
	 * @constructor
	 */
	function Handler(onFulfilled, onRejected, promise) {
		this.onFulfilled = typeof onFulfilled === 'function' ? onFulfilled : null;
		this.onRejected = typeof onRejected === 'function' ? onRejected : null;
		this.promise = promise;
	}

	/**
	 * Take a potentially misbehaving resolver function and make sure
	 * onFulfilled and onRejected are only called once.
	 *
	 * Makes no guarantees about asynchrony.
	 */
	function doResolve(fn, self) {
		var done = false;
		try {
			fn(
				function(value) {
					if (done) return;
					done = true;
					resolve(self, value);
				},
				function(reason) {
					if (done) return;
					done = true;
					reject(self, reason);
				}
			);
		} catch (ex) {
			if (done) return;
			done = true;
			reject(self, ex);
		}
	}

	Promise.prototype['catch'] = function(onRejected) {
		return this.then(null, onRejected);
	};

	Promise.prototype.then = function(onFulfilled, onRejected) {
		// @ts-ignore
		var prom = new this.constructor(noop);

		handle(this, new Handler(onFulfilled, onRejected, prom));
		return prom;
	};

	Promise.prototype['finally'] = finallyConstructor;

	Promise.all = function(arr) {
		return new Promise(function(resolve, reject) {
			if (!isArray(arr)) {
				return reject(new TypeError('Promise.all accepts an array'));
			}

			var args = Array.prototype.slice.call(arr);
			if (args.length === 0) return resolve([]);
			var remaining = args.length;

			function res(i, val) {
				try {
					if (val && (typeof val === 'object' || typeof val === 'function')) {
						var then = val.then;
						if (typeof then === 'function') {
							then.call(
								val,
								function(val) {
									res(i, val);
								},
								reject
							);
							return;
						}
					}
					args[i] = val;
					if (--remaining === 0) {
						resolve(args);
					}
				} catch (ex) {
					reject(ex);
				}
			}

			for (var i = 0; i < args.length; i++) {
				res(i, args[i]);
			}
		});
	};

	Promise.allSettled = allSettled;

	Promise.resolve = function(value) {
		if (value && typeof value === 'object' && value.constructor === Promise) {
			return value;
		}

		return new Promise(function(resolve) {
			resolve(value);
		});
	};

	Promise.reject = function(value) {
		return new Promise(function(resolve, reject) {
			reject(value);
		});
	};

	Promise.race = function(arr) {
		return new Promise(function(resolve, reject) {
			if (!isArray(arr)) {
				return reject(new TypeError('Promise.race accepts an array'));
			}

			for (var i = 0, len = arr.length; i < len; i++) {
				Promise.resolve(arr[i]).then(resolve, reject);
			}
		});
	};

// Use polyfill for setImmediate for performance gains
	Promise._immediateFn =
		// @ts-ignore
		(typeof setImmediate === 'function' &&
			function(fn) {
				// @ts-ignore
				setImmediate(fn);
			}) ||
		function(fn) {
			setTimeoutFunc(fn, 0);
		};

	Promise._unhandledRejectionFn = function _unhandledRejectionFn(err) {
		if (typeof console !== 'undefined' && console) {
			console.warn('Possible Unhandled Promise Rejection:', err); // eslint-disable-line no-console
		}
	};

	/** @suppress {undefinedVars} */
	var globalNS = (function() {
		// the only reliable means to get the global object is
		// `Function('return this')()`
		// However, this causes CSP violations in Chrome apps.
		if (typeof self !== 'undefined') {
			return self;
		}
		if (typeof window !== 'undefined') {
			return window;
		}
		if (typeof global !== 'undefined') {
			return global;
		}
		throw new Error('unable to locate global object');
	})();

// Expose the polyfill if Promise is undefined or set to a
// non-function value. The latter can be due to a named HTMLElement
// being exposed by browsers for legacy reasons.
// https://github.com/taylorhakes/promise-polyfill/issues/114
	if (typeof globalNS['Promise'] !== 'function') {
		globalNS['Promise'] = Promise;
	} else if (!globalNS.Promise.prototype['finally']) {
		globalNS.Promise.prototype['finally'] = finallyConstructor;
	} else if (!globalNS.Promise.allSettled) {
		globalNS.Promise.allSettled = allSettled;
	}

})));

// MouseEvent
(function (window) {
	try {
		new MouseEvent('test');
		return false; // No need to polyfill
	} catch (e) {
		// Need to polyfill - fall through
	}

	// Polyfills DOM4 MouseEvent
	var MouseEventPolyfill = function (eventType, params) {
		params = params || { bubbles: false, cancelable: false };
		var mouseEvent = document.createEvent('MouseEvent');
		mouseEvent.initMouseEvent(eventType,
			params.bubbles,
			params.cancelable,
			window,
			0,
			params.screenX || 0,
			params.screenY || 0,
			params.clientX || 0,
			params.clientY || 0,
			params.ctrlKey || false,
			params.altKey || false,
			params.shiftKey || false,
			params.metaKey || false,
			params.button || 0,
			params.relatedTarget || null
		);

		return mouseEvent;
	}

	MouseEventPolyfill.prototype = Event.prototype;

	window.MouseEvent = MouseEventPolyfill;
})(window);

// ChildNode.remove
// from:https://github.com/jserz/js_piece/blob/master/DOM/ChildNode/remove()/remove().md
(function (arr) {
	arr.forEach(function (item) {
		if (item.hasOwnProperty('remove')) {
			return;
		}
		Object.defineProperty(item, 'remove', {
			configurable: true,
			enumerable: true,
			writable: true,
			value: function remove() {
				this.parentNode.removeChild(this);
			}
		});
	});
})([Element.prototype, CharacterData.prototype, DocumentType.prototype]);

/**
 * URL Polyfill
 * Draft specification: https://url.spec.whatwg.org
 * https://polyfill.io/
 */
(function (global) {
	'use strict';

	function isSequence(o) {
		if (!o) return false;
		if ('Symbol' in global && 'iterator' in global.Symbol &&
			typeof o[Symbol.iterator] === 'function') return true;
		if (Array.isArray(o)) return true;
		return false;
	}

	function toArray(iter) {
		return ('from' in Array) ? Array.from(iter) : Array.prototype.slice.call(iter);
	}

	(function() {

		// Browsers may have:
		// * No global URL object
		// * URL with static methods only - may have a dummy constructor
		// * URL with members except searchParams
		// * Full URL API support
		var origURL = global.URL;
		var nativeURL;
		try {
			if (origURL) {
				nativeURL = new global.URL('http://example.com');
				if ('searchParams' in nativeURL) {
					var url = new URL('http://example.com');
					url.search = 'a=1&b=2';
					if (url.href === 'http://example.com/?a=1&b=2') {
						url.search = '';
						if (url.href === 'http://example.com/') {
							return;
						}
					}
				}
				if (!('href' in nativeURL)) {
					nativeURL = undefined;
				}
				nativeURL = undefined;
			}
			// eslint-disable-next-line no-empty
		} catch (_) {}

		// NOTE: Doesn't do the encoding/decoding dance
		function urlencoded_serialize(pairs) {
			var output = '', first = true;
			pairs.forEach(function (pair) {
				var name = encodeURIComponent(pair.name);
				var value = encodeURIComponent(pair.value);
				if (!first) output += '&';
				output += name + '=' + value;
				first = false;
			});
			return output.replace(/%20/g, '+');
		}

		// NOTE: Doesn't do the encoding/decoding dance
		function urlencoded_parse(input, isindex) {
			var sequences = input.split('&');
			if (isindex && sequences[0].indexOf('=') === -1)
				sequences[0] = '=' + sequences[0];
			var pairs = [];
			sequences.forEach(function (bytes) {
				if (bytes.length === 0) return;
				var index = bytes.indexOf('=');
				if (index !== -1) {
					var name = bytes.substring(0, index);
					var value = bytes.substring(index + 1);
				} else {
					name = bytes;
					value = '';
				}
				name = name.replace(/\+/g, ' ');
				value = value.replace(/\+/g, ' ');
				pairs.push({ name: name, value: value });
			});
			var output = [];
			pairs.forEach(function (pair) {
				output.push({
					name: decodeURIComponent(pair.name),
					value: decodeURIComponent(pair.value)
				});
			});
			return output;
		}

		function URLUtils(url) {
			if (nativeURL)
				return new origURL(url);
			var anchor = document.createElement('a');
			anchor.href = url;
			return anchor;
		}

		function URLSearchParams(init) {
			var $this = this;
			this._list = [];

			if (init === undefined || init === null) {
				// no-op
			} else if (init instanceof URLSearchParams) {
				// In ES6 init would be a sequence, but special case for ES5.
				this._list = urlencoded_parse(String(init));
			} else if (typeof init === 'object' && isSequence(init)) {
				toArray(init).forEach(function(e) {
					if (!isSequence(e)) throw TypeError();
					var nv = toArray(e);
					if (nv.length !== 2) throw TypeError();
					$this._list.push({name: String(nv[0]), value: String(nv[1])});
				});
			} else if (typeof init === 'object' && init) {
				Object.keys(init).forEach(function(key) {
					$this._list.push({name: String(key), value: String(init[key])});
				});
			} else {
				init = String(init);
				if (init.substring(0, 1) === '?')
					init = init.substring(1);
				this._list = urlencoded_parse(init);
			}

			this._url_object = null;
			this._setList = function (list) { if (!updating) $this._list = list; };

			var updating = false;
			this._update_steps = function() {
				if (updating) return;
				updating = true;

				if (!$this._url_object) return;

				// Partial workaround for IE issue with 'about:'
				if ($this._url_object.protocol === 'about:' &&
					$this._url_object.pathname.indexOf('?') !== -1) {
					$this._url_object.pathname = $this._url_object.pathname.split('?')[0];
				}

				$this._url_object.search = urlencoded_serialize($this._list);

				updating = false;
			};
		}


		Object.defineProperties(URLSearchParams.prototype, {
			append: {
				value: function (name, value) {
					this._list.push({ name: name, value: value });
					this._update_steps();
				}, writable: true, enumerable: true, configurable: true
			},

			'delete': {
				value: function (name) {
					for (var i = 0; i < this._list.length;) {
						if (this._list[i].name === name)
							this._list.splice(i, 1);
						else
							++i;
					}
					this._update_steps();
				}, writable: true, enumerable: true, configurable: true
			},

			get: {
				value: function (name) {
					for (var i = 0; i < this._list.length; ++i) {
						if (this._list[i].name === name)
							return this._list[i].value;
					}
					return null;
				}, writable: true, enumerable: true, configurable: true
			},

			getAll: {
				value: function (name) {
					var result = [];
					for (var i = 0; i < this._list.length; ++i) {
						if (this._list[i].name === name)
							result.push(this._list[i].value);
					}
					return result;
				}, writable: true, enumerable: true, configurable: true
			},

			has: {
				value: function (name) {
					for (var i = 0; i < this._list.length; ++i) {
						if (this._list[i].name === name)
							return true;
					}
					return false;
				}, writable: true, enumerable: true, configurable: true
			},

			set: {
				value: function (name, value) {
					var found = false;
					for (var i = 0; i < this._list.length;) {
						if (this._list[i].name === name) {
							if (!found) {
								this._list[i].value = value;
								found = true;
								++i;
							} else {
								this._list.splice(i, 1);
							}
						} else {
							++i;
						}
					}

					if (!found)
						this._list.push({ name: name, value: value });

					this._update_steps();
				}, writable: true, enumerable: true, configurable: true
			},

			entries: {
				value: function() { return new Iterator(this._list, 'key+value'); },
				writable: true, enumerable: true, configurable: true
			},

			keys: {
				value: function() { return new Iterator(this._list, 'key'); },
				writable: true, enumerable: true, configurable: true
			},

			values: {
				value: function() { return new Iterator(this._list, 'value'); },
				writable: true, enumerable: true, configurable: true
			},

			forEach: {
				value: function(callback) {
					var thisArg = (arguments.length > 1) ? arguments[1] : undefined;
					this._list.forEach(function(pair) {
						callback.call(thisArg, pair.value, pair.name);
					});

				}, writable: true, enumerable: true, configurable: true
			},

			toString: {
				value: function () {
					return urlencoded_serialize(this._list);
				}, writable: true, enumerable: false, configurable: true
			}
		});

		function Iterator(source, kind) {
			var index = 0;
			this.next = function() {
				if (index >= source.length)
					return {done: true, value: undefined};
				var pair = source[index++];
				return {done: false, value:
						kind === 'key' ? pair.name :
							kind === 'value' ? pair.value :
								[pair.name, pair.value]};
			};
		}

		if ('Symbol' in global && 'iterator' in global.Symbol) {
			Object.defineProperty(URLSearchParams.prototype, global.Symbol.iterator, {
				value: URLSearchParams.prototype.entries,
				writable: true, enumerable: true, configurable: true});
			Object.defineProperty(Iterator.prototype, global.Symbol.iterator, {
				value: function() { return this; },
				writable: true, enumerable: true, configurable: true});
		}

		function URL(url, base) {
			if (!(this instanceof global.URL))
				throw new TypeError("Failed to construct 'URL': Please use the 'new' operator.");

			if (base) {
				url = (function () {
					if (nativeURL) return new origURL(url, base).href;
					var iframe;
					try {
						var doc;
						// Use another document/base tag/anchor for relative URL resolution, if possible
						if (Object.prototype.toString.call(window.operamini) === "[object OperaMini]") {
							iframe = document.createElement('iframe');
							iframe.style.display = 'none';
							document.documentElement.appendChild(iframe);
							doc = iframe.contentWindow.document;
						} else if (document.implementation && document.implementation.createHTMLDocument) {
							doc = document.implementation.createHTMLDocument('');
						} else if (document.implementation && document.implementation.createDocument) {
							doc = document.implementation.createDocument('http://www.w3.org/1999/xhtml', 'html', null);
							doc.documentElement.appendChild(doc.createElement('head'));
							doc.documentElement.appendChild(doc.createElement('body'));
						} else if (window.ActiveXObject) {
							doc = new window.ActiveXObject('htmlfile');
							doc.write('<head></head><body></body>');
							doc.close();
						}

						if (!doc) throw Error('base not supported');

						var baseTag = doc.createElement('base');
						baseTag.href = base;
						doc.getElementsByTagName('head')[0].appendChild(baseTag);
						var anchor = doc.createElement('a');
						anchor.href = url;
						return anchor.href;
					} finally {
						if (iframe)
							iframe.parentNode.removeChild(iframe);
					}
				}());
			}

			// An inner object implementing URLUtils (either a native URL
			// object or an HTMLAnchorElement instance) is used to perform the
			// URL algorithms. With full ES5 getter/setter support, return a
			// regular object For IE8's limited getter/setter support, a
			// different HTMLAnchorElement is returned with properties
			// overridden

			var instance = URLUtils(url || '');

			// Detect for ES5 getter/setter support
			// (an Object.defineProperties polyfill that doesn't support getters/setters may throw)
			var ES5_GET_SET = (function() {
				if (!('defineProperties' in Object)) return false;
				try {
					var obj = {};
					Object.defineProperties(obj, { prop: { get: function () { return true; } } });
					return obj.prop;
				} catch (_) {
					return false;
				}
			}());

			var self = ES5_GET_SET ? this : document.createElement('a');



			var query_object = new URLSearchParams(
				instance.search ? instance.search.substring(1) : null);
			query_object._url_object = self;

			Object.defineProperties(self, {
				href: {
					get: function () { return instance.href; },
					set: function (v) { instance.href = v; tidy_instance(); update_steps(); },
					enumerable: true, configurable: true
				},
				origin: {
					get: function () {
						if ('origin' in instance) return instance.origin;
						return this.protocol + '//' + this.host;
					},
					enumerable: true, configurable: true
				},
				protocol: {
					get: function () { return instance.protocol; },
					set: function (v) { instance.protocol = v; },
					enumerable: true, configurable: true
				},
				username: {
					get: function () { return instance.username; },
					set: function (v) { instance.username = v; },
					enumerable: true, configurable: true
				},
				password: {
					get: function () { return instance.password; },
					set: function (v) { instance.password = v; },
					enumerable: true, configurable: true
				},
				host: {
					get: function () {
						// IE returns default port in |host|
						var re = {'http:': /:80$/, 'https:': /:443$/, 'ftp:': /:21$/}[instance.protocol];
						return re ? instance.host.replace(re, '') : instance.host;
					},
					set: function (v) { instance.host = v; },
					enumerable: true, configurable: true
				},
				hostname: {
					get: function () { return instance.hostname; },
					set: function (v) { instance.hostname = v; },
					enumerable: true, configurable: true
				},
				port: {
					get: function () { return instance.port; },
					set: function (v) { instance.port = v; },
					enumerable: true, configurable: true
				},
				pathname: {
					get: function () {
						// IE does not include leading '/' in |pathname|
						if (instance.pathname.charAt(0) !== '/') return '/' + instance.pathname;
						return instance.pathname;
					},
					set: function (v) { instance.pathname = v; },
					enumerable: true, configurable: true
				},
				search: {
					get: function () { return instance.search; },
					set: function (v) {
						if (instance.search === v) return;
						instance.search = v; tidy_instance(); update_steps();
					},
					enumerable: true, configurable: true
				},
				searchParams: {
					get: function () { return query_object; },
					enumerable: true, configurable: true
				},
				hash: {
					get: function () { return instance.hash; },
					set: function (v) { instance.hash = v; tidy_instance(); },
					enumerable: true, configurable: true
				},
				toString: {
					value: function() { return instance.toString(); },
					enumerable: false, configurable: true
				},
				valueOf: {
					value: function() { return instance.valueOf(); },
					enumerable: false, configurable: true
				}
			});

			function tidy_instance() {
				var href = instance.href.replace(/#$|\?$|\?(?=#)/g, '');
				if (instance.href !== href)
					instance.href = href;
			}

			function update_steps() {
				query_object._setList(instance.search ? urlencoded_parse(instance.search.substring(1)) : []);
				query_object._update_steps();
			}

			return self;
		}

		if (origURL) {
			for (var i in origURL) {
				if (Object.prototype.hasOwnProperty.call(origURL, i) && typeof origURL[i] === 'function')
					URL[i] = origURL[i];
			}
		}

		global.URL = URL;
		global.URLSearchParams = URLSearchParams;
	}());

	// Patch native URLSearchParams constructor to handle sequences/records
	// if necessary.
	(function() {
		if (new global.URLSearchParams([['a', 1]]).get('a') === '1' &&
			new global.URLSearchParams({a: 1}).get('a') === '1')
			return;
		var orig = global.URLSearchParams;
		global.URLSearchParams = function(init) {
			if (init && typeof init === 'object' && isSequence(init)) {
				var o = new orig();
				toArray(init).forEach(function(e) {
					if (!isSequence(e)) throw TypeError();
					var nv = toArray(e);
					if (nv.length !== 2) throw TypeError();
					o.append(nv[0], nv[1]);
				});
				return o;
			} else if (init && typeof init === 'object') {
				o = new orig();
				Object.keys(init).forEach(function(key) {
					o.set(key, init[key]);
				});
				return o;
			} else {
				return new orig(init);
			}
		};
	}());

}(self));

// Event, CustomEvent
(function () {

	if ( typeof window.CustomEvent === "function" ) return false;

	function CustomEvent ( event, params ) {
		params = params || { bubbles: false, cancelable: false, detail: null };
		var evt = document.createEvent( 'CustomEvent' );
		evt.initCustomEvent( event, params.bubbles, params.cancelable, params.detail );
		return evt;
	}

	window.CustomEvent = CustomEvent;
	window.Event = CustomEvent;
})();
