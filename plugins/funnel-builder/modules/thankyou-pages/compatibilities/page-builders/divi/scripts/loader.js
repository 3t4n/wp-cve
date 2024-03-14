/******/
(function (modules) { // webpackBootstrap
    /******/ 	// The module cache
    /******/
    var installedModules = {};
    /******/
    /******/ 	// The require function
    /******/
    function __webpack_require__(moduleId) {
        /******/
        /******/ 		// Check if module is in cache
        /******/
        if (installedModules[moduleId]) {
            /******/
            return installedModules[moduleId].exports;
            /******/
        }
        /******/ 		// Create a new module (and put it into the cache)
        /******/
        var module = installedModules[moduleId] = {
            /******/            i: moduleId,
            /******/            l: false,
            /******/            exports: {}
            /******/
        };
        /******/
        /******/ 		// Execute the module function
        /******/
        modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
        /******/
        /******/ 		// Flag the module as loaded
        /******/
        module.l = true;
        /******/
        /******/ 		// Return the exports of the module
        /******/
        return module.exports;
        /******/
    }

    /******/
    /******/
    /******/ 	// expose the modules object (__webpack_modules__)
    /******/
    __webpack_require__.m = modules;
    /******/
    /******/ 	// expose the module cache
    /******/
    __webpack_require__.c = installedModules;
    /******/
    /******/ 	// define getter function for harmony exports
    /******/
    __webpack_require__.d = function (exports, name, getter) {
        /******/
        if (!__webpack_require__.o(exports, name)) {
            /******/
            Object.defineProperty(exports, name, {enumerable: true, get: getter});
            /******/
        }
        /******/
    };
    /******/
    /******/ 	// define __esModule on exports
    /******/
    __webpack_require__.r = function (exports) {
        /******/
        if (typeof Symbol !== 'undefined' && Symbol.toStringTag) {
            /******/
            Object.defineProperty(exports, Symbol.toStringTag, {value: 'Module'});
            /******/
        }
        /******/
        Object.defineProperty(exports, '__esModule', {value: true});
        /******/
    };
    /******/
    /******/ 	// create a fake namespace object
    /******/ 	// mode & 1: value is a module id, require it
    /******/ 	// mode & 2: merge all properties of value into the ns
    /******/ 	// mode & 4: return value when already ns object
    /******/ 	// mode & 8|1: behave like require
    /******/
    __webpack_require__.t = function (value, mode) {
        /******/
        if (mode & 1) value = __webpack_require__(value);
        /******/
        if (mode & 8) return value;
        /******/
        if ((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
        /******/
        var ns = Object.create(null);
        /******/
        __webpack_require__.r(ns);
        /******/
        Object.defineProperty(ns, 'default', {enumerable: true, value: value});
        /******/
        if (mode & 2 && typeof value != 'string') for (var key in value) __webpack_require__.d(ns, key, function (key) {
            return value[key];
        }.bind(null, key));
        /******/
        return ns;
        /******/
    };
    /******/
    /******/ 	// getDefaultExport function for compatibility with non-harmony modules
    /******/
    __webpack_require__.n = function (module) {
        /******/
        var getter = module && module.__esModule ?
            /******/            function getDefault() {
                return module['default'];
            } :
            /******/            function getModuleExports() {
                return module;
            };
        /******/
        __webpack_require__.d(getter, 'a', getter);
        /******/
        return getter;
        /******/
    };
    /******/
    /******/ 	// Object.prototype.hasOwnProperty.call
    /******/
    __webpack_require__.o = function (object, property) {
        return Object.prototype.hasOwnProperty.call(object, property);
    };
    /******/
    /******/ 	// __webpack_public_path__
    /******/
    __webpack_require__.p = "";
    /******/
    /******/
    /******/ 	// Load entry module and return exports
    /******/
    return __webpack_require__(__webpack_require__.s = 5);
    /******/
})
    /************************************************************************/
    /******/ ([
    /* 0 */
    /***/ (function (module, exports, __webpack_require__) {

        "use strict";


        if (true) {
            module.exports = __webpack_require__(3);
        } else {
        }


        /***/
    }),
    /* 1 */,
    /* 2 */
    /***/ (function (module, exports) {

        (function ($) {
            window.wfty_prepare_divi_css = function (data, utils, props) {
                var main_output = [];

                for (var m in data.margin_padding) {
                    (function (key, selector) {
                        var spacing = props[key];

                        if (spacing != null && spacing !== '' && spacing.split("|")) {
                            var element_here = key.indexOf("_padding");
                            var ele = "padding";

                            if (element_here === -1) {
                                ele = "margin";
                            }

                            spacing = props[key].split("|");
                            var enable_edited = props[key + "_last_edited"];
                            var key_tablet = props[key + "_tablet"];
                            var key_phone = props[key + "_phone"];
                            var enable_responsive_active = enable_edited && enable_edited.startsWith("on");
                            main_output.push({
                                'selector': selector,
                                'declaration': ele + "-top: ".concat(spacing[0], "  !important; ") + ele + "-right: ".concat(spacing[1], " !important; ") + ele + "-bottom: ".concat(spacing[2], "  !important; ") + ele + "-left: ".concat(spacing[3], "  !important;")
                            });

                            if (key_tablet && enable_responsive_active && key_tablet && '' !== key_tablet) {
                                var spacing_tablet = key_tablet.split("|");
                                main_output.push({
                                    'selector': selector,
                                    'declaration': ele + "-top: ".concat(spacing_tablet[0], " !important;") + ele + "-right: ".concat(spacing_tablet[1], " !important; ") + ele + "-bottom: ".concat(spacing_tablet[2], "  !important; ") + ele + "-left: ".concat(spacing_tablet[3], "  !important;"),
                                    'device': 'tablet'
                                });
                            }

                            if (key_phone && enable_responsive_active && key_phone && '' !== key_phone) {
                                var spacing_phone = key_phone.split("|");
                                main_output.push({
                                    'selector': selector,
                                    'declaration': ele + "-top: ".concat(spacing_phone[0], " !important; ") + ele + "-right: ".concat(spacing_phone[1], " !important; ") + ele + "-bottom: ".concat(spacing_phone[2], " !important; ") + ele + "-left: ".concat(spacing_phone[3], " !important;"),
                                    'device': 'phone'
                                });
                            }
                        }
                    })(m, data.margin_padding[m]);
                }

                for (var n in data.normal_data) {
                    (function (key, selector, css_prop) {
                        main_output.push({
                            'selector': selector,
                            'declaration': "".concat(css_prop, ":").concat(props[key]) + '!important'
                        });
                        var device_enable = props[key + "_last_edited"] && props[key + "_last_edited"].startsWith('on');

                        if (device_enable === true) {
                            main_output.push({
                                'selector': selector,
                                'declaration': "".concat(css_prop, ":").concat(props[key + "_tablet"]) + '!important',
                                'device': 'tablet'
                            });
                            main_output.push({
                                'selector': selector,
                                'declaration': "".concat(css_prop, ":").concat(props[key + "_phone"]) + '!important',
                                'device': 'phone'
                            });
                        }
                    })(n, data.normal_data[n]['selector'], data.normal_data[n]['property']);
                }

                for (var t in data.typography_data) {
                    (function (key, selector) {
                        var property = data.typography[key];
                        main_output.push({
                            'selector': selector,
                            'declaration': utils.setElementFont(props[property])
                        });
                    })(t, data.typography_data[t]);
                }

                for (var border_key in data.border_data) {
                    var selector = data.border_data[border_key];

                    (function (border_key, selector) {
                        var border_type = props[border_key + '_border_type'];
                        var width_top = props[border_key + '_border_width_top'];
                        var width_bottom = props[border_key + '_border_width_bottom'];
                        var width_left = props[border_key + '_border_width_left'];
                        var width_right = props[border_key + '_border_width_right'];
                        var border_color = props[border_key + '_border_color'];
                        var radius_top_left = props[border_key + '_border_radius_top'];
                        var radius_top_right = props[border_key + '_border_radius_bottom'];
                        var radius_bottom_right = props[border_key + '_border_radius_left'];
                        var radius_bottom_left = props[border_key + '_border_radius_right'];

                        if ('none' === border_type) {
                            main_output.push({
                                'selector': selector,
                                'declaration': 'border-style:none !important;'
                            });
                            main_output.push({
                                'selector': selector,
                                'declaration': 'border-radius:none !important;'
                            });
                        } else {
                            main_output.push({
                                'selector': selector,
                                'declaration': "border-color:".concat(border_color, " !important;")
                            });
                            main_output.push({
                                'selector': selector,
                                'declaration': "border-style:".concat(border_type, " !important;")
                            });
                            main_output.push({
                                'selector': selector,
                                'declaration': "border-top-width:".concat(width_top, "px !important;")
                            });
                            main_output.push({
                                'selector': selector,
                                'declaration': "border-bottom-width:".concat(width_bottom, "px !important;")
                            });
                            main_output.push({
                                'selector': selector,
                                'declaration': "border-left-width:".concat(width_left, "px !important;")
                            });
                            main_output.push({
                                'selector': selector,
                                'declaration': "border-right-width:".concat(width_right, "px !important;")
                            });
                            main_output.push({
                                'selector': selector,
                                'declaration': "border-top-left-radius:".concat(radius_top_left, "px !important;")
                            });
                            main_output.push({
                                'selector': selector,
                                'declaration': "border-top-right-radius:".concat(radius_top_right, "px !important;")
                            });
                            main_output.push({
                                'selector': selector,
                                'declaration': "border-bottom-right-radius:".concat(radius_bottom_right, "px !important;")
                            });
                            main_output.push({
                                'selector': selector,
                                'declaration': "border-bottom-left-radius:".concat(radius_bottom_left, "px !important;")
                            });
                        }
                    })(border_key, selector);
                }

                for (var shadow_key in data.box_shadow) {
                    var _selector = data.box_shadow[shadow_key];

                    (function (border_key, selector) {
                        var enabled = props[border_key + '_shadow_enable'];
                        var type = props[border_key + '_shadow_type'];
                        var horizontal = props[border_key + '_shadow_horizontal'];
                        var vertical = props[border_key + '_shadow_vertical'];
                        var blur = props[border_key + '_shadow_blur'];
                        var spread = props[border_key + '_shadow_spread'];
                        var color = props[border_key + '_shadow_color'];

                        if ('on' == enabled) {
                            main_output.push({
                                'selector': selector,
                                'declaration': "box-shadow:".concat(horizontal, "px ").concat(vertical, "px ").concat(blur, "px ").concat(spread, "px ").concat(color, " ").concat(type, " !important;")
                            });
                        } else {
                            main_output.push({
                                'selector': selector,
                                'declaration': 'box-shadow:none !important;'
                            });
                        }
                    })(shadow_key, _selector);
                }

                return main_output;
            };

            $(document.body).on('keypress', '.wfty_divi_border textarea', function (e) {
                // IE
                var keynum;

                if (window.event) {
                    keynum = e.keyCode;
                } else if (e.which) {
                    keynum = e.which;
                }

                if (keynum === 13) {
                    return false;
                }
            });
            $(document.body).on('click', '.et-fb-form__toggle', function () {
                var el = $(this);
                setTimeout(function (el) {
                    var siblings = el.children('.et-fb-form__group');
                    console.log('Hello Toggle run', siblings.length);

                    if (siblings.length === 0) {
                        return;
                    }

                    siblings.each(function () {
                        var wfty_border_width_top = $(this).find('.wfty_border_width_top');

                        if (wfty_border_width_top.length > 0) {
                            $(this).addClass('wfty_divi_border wfty_divi_border_width_start wfty_border_width_top');
                        }

                        var wfty_border_width_bottom = $(this).find('.wfty_border_width_bottom');

                        if (wfty_border_width_bottom.length > 0) {
                            $(this).addClass('wfty_divi_border wfty_border_width_bottom');
                        }

                        var wfty_border_width_left = $(this).find('.wfty_border_width_left');

                        if (wfty_border_width_left.length > 0) {
                            $(this).addClass('wfty_divi_border wfty_border_width_left');
                        }

                        var wfty_border_width_right = $(this).find('.wfty_border_width_right');

                        if (wfty_border_width_right.length > 0) {
                            $(this).addClass('wfty_divi_border wfty_divi_border_width_end wfty_border_width_right');
                        }

                        var heading = $(this).find('.wfty_heading_divi_builder');

                        if (heading.length > 0) {
                            heading.remove();
                            var text = $(this).find('.et-fb-form__label-text');

                            if (text.length > 0) {
                                $(this).find('.et-fb-form__label').replaceWith("<h3 class='wfty_c_heading'>" + text.text() + "</h3>");
                            }
                        }

                        var subheading = $(this).find('.wfty_subheading_divi_builder');

                        if (subheading.length > 0) {
                            subheading.remove();
                        }
                    });
                }, 50, el);
            });
        })(jQuery);

        /***/
    }),
    /* 3 */
    /***/ (function (module, exports, __webpack_require__) {

        "use strict";
        /** @license React v16.14.0
         * react.production.min.js
         *
         * Copyright (c) Facebook, Inc. and its affiliates.
         *
         * This source code is licensed under the MIT license found in the
         * LICENSE file in the root directory of this source tree.
         */

        var l = __webpack_require__(4), n = "function" === typeof Symbol && Symbol.for, p = n ? Symbol.for("react.element") : 60103, q = n ? Symbol.for("react.portal") : 60106,
            r = n ? Symbol.for("react.fragment") : 60107, t = n ? Symbol.for("react.strict_mode") : 60108, u = n ? Symbol.for("react.profiler") : 60114, v = n ? Symbol.for("react.provider") : 60109,
            w = n ? Symbol.for("react.context") : 60110, x = n ? Symbol.for("react.forward_ref") : 60112, y = n ? Symbol.for("react.suspense") : 60113, z = n ? Symbol.for("react.memo") : 60115,
            A = n ? Symbol.for("react.lazy") :
                60116, B = "function" === typeof Symbol && Symbol.iterator;

        function C(a) {
            for (var b = "https://reactjs.org/docs/error-decoder.html?invariant=" + a, c = 1; c < arguments.length; c++) b += "&args[]=" + encodeURIComponent(arguments[c]);
            return "Minified React error #" + a + "; visit " + b + " for the full message or use the non-minified dev environment for full errors and additional helpful warnings."
        }

        var D = {
            isMounted: function () {
                return !1
            }, enqueueForceUpdate: function () {
            }, enqueueReplaceState: function () {
            }, enqueueSetState: function () {
            }
        }, E = {};

        function F(a, b, c) {
            this.props = a;
            this.context = b;
            this.refs = E;
            this.updater = c || D
        }

        F.prototype.isReactComponent = {};
        F.prototype.setState = function (a, b) {
            if ("object" !== typeof a && "function" !== typeof a && null != a) throw Error(C(85));
            this.updater.enqueueSetState(this, a, b, "setState")
        };
        F.prototype.forceUpdate = function (a) {
            this.updater.enqueueForceUpdate(this, a, "forceUpdate")
        };

        function G() {
        }

        G.prototype = F.prototype;

        function H(a, b, c) {
            this.props = a;
            this.context = b;
            this.refs = E;
            this.updater = c || D
        }

        var I = H.prototype = new G;
        I.constructor = H;
        l(I, F.prototype);
        I.isPureReactComponent = !0;
        var J = {current: null}, K = Object.prototype.hasOwnProperty, L = {key: !0, ref: !0, __self: !0, __source: !0};

        function M(a, b, c) {
            var e, d = {}, g = null, k = null;
            if (null != b) for (e in void 0 !== b.ref && (k = b.ref), void 0 !== b.key && (g = "" + b.key), b) K.call(b, e) && !L.hasOwnProperty(e) && (d[e] = b[e]);
            var f = arguments.length - 2;
            if (1 === f) d.children = c; else if (1 < f) {
                for (var h = Array(f), m = 0; m < f; m++) h[m] = arguments[m + 2];
                d.children = h
            }
            if (a && a.defaultProps) for (e in f = a.defaultProps, f) void 0 === d[e] && (d[e] = f[e]);
            return {$$typeof: p, type: a, key: g, ref: k, props: d, _owner: J.current}
        }

        function N(a, b) {
            return {$$typeof: p, type: a.type, key: b, ref: a.ref, props: a.props, _owner: a._owner}
        }

        function O(a) {
            return "object" === typeof a && null !== a && a.$$typeof === p
        }

        function escape(a) {
            var b = {"=": "=0", ":": "=2"};
            return "$" + ("" + a).replace(/[=:]/g, function (a) {
                return b[a]
            })
        }

        var P = /\/+/g, Q = [];

        function R(a, b, c, e) {
            if (Q.length) {
                var d = Q.pop();
                d.result = a;
                d.keyPrefix = b;
                d.func = c;
                d.context = e;
                d.count = 0;
                return d
            }
            return {result: a, keyPrefix: b, func: c, context: e, count: 0}
        }

        function S(a) {
            a.result = null;
            a.keyPrefix = null;
            a.func = null;
            a.context = null;
            a.count = 0;
            10 > Q.length && Q.push(a)
        }

        function T(a, b, c, e) {
            var d = typeof a;
            if ("undefined" === d || "boolean" === d) a = null;
            var g = !1;
            if (null === a) g = !0; else switch (d) {
                case "string":
                case "number":
                    g = !0;
                    break;
                case "object":
                    switch (a.$$typeof) {
                        case p:
                        case q:
                            g = !0
                    }
            }
            if (g) return c(e, a, "" === b ? "." + U(a, 0) : b), 1;
            g = 0;
            b = "" === b ? "." : b + ":";
            if (Array.isArray(a)) for (var k = 0; k < a.length; k++) {
                d = a[k];
                var f = b + U(d, k);
                g += T(d, f, c, e)
            } else if (null === a || "object" !== typeof a ? f = null : (f = B && a[B] || a["@@iterator"], f = "function" === typeof f ? f : null), "function" === typeof f) for (a = f.call(a), k =
                0; !(d = a.next()).done;) d = d.value, f = b + U(d, k++), g += T(d, f, c, e); else if ("object" === d) throw c = "" + a, Error(C(31, "[object Object]" === c ? "object with keys {" + Object.keys(a).join(", ") + "}" : c, ""));
            return g
        }

        function V(a, b, c) {
            return null == a ? 0 : T(a, "", b, c)
        }

        function U(a, b) {
            return "object" === typeof a && null !== a && null != a.key ? escape(a.key) : b.toString(36)
        }

        function W(a, b) {
            a.func.call(a.context, b, a.count++)
        }

        function aa(a, b, c) {
            var e = a.result, d = a.keyPrefix;
            a = a.func.call(a.context, b, a.count++);
            Array.isArray(a) ? X(a, e, c, function (a) {
                return a
            }) : null != a && (O(a) && (a = N(a, d + (!a.key || b && b.key === a.key ? "" : ("" + a.key).replace(P, "$&/") + "/") + c)), e.push(a))
        }

        function X(a, b, c, e, d) {
            var g = "";
            null != c && (g = ("" + c).replace(P, "$&/") + "/");
            b = R(b, g, e, d);
            V(a, aa, b);
            S(b)
        }

        var Y = {current: null};

        function Z() {
            var a = Y.current;
            if (null === a) throw Error(C(321));
            return a
        }

        var ba = {ReactCurrentDispatcher: Y, ReactCurrentBatchConfig: {suspense: null}, ReactCurrentOwner: J, IsSomeRendererActing: {current: !1}, assign: l};
        exports.Children = {
            map: function (a, b, c) {
                if (null == a) return a;
                var e = [];
                X(a, e, null, b, c);
                return e
            }, forEach: function (a, b, c) {
                if (null == a) return a;
                b = R(null, null, b, c);
                V(a, W, b);
                S(b)
            }, count: function (a) {
                return V(a, function () {
                    return null
                }, null)
            }, toArray: function (a) {
                var b = [];
                X(a, b, null, function (a) {
                    return a
                });
                return b
            }, only: function (a) {
                if (!O(a)) throw Error(C(143));
                return a
            }
        };
        exports.Component = F;
        exports.Fragment = r;
        exports.Profiler = u;
        exports.PureComponent = H;
        exports.StrictMode = t;
        exports.Suspense = y;
        exports.__SECRET_INTERNALS_DO_NOT_USE_OR_YOU_WILL_BE_FIRED = ba;
        exports.cloneElement = function (a, b, c) {
            if (null === a || void 0 === a) throw Error(C(267, a));
            var e = l({}, a.props), d = a.key, g = a.ref, k = a._owner;
            if (null != b) {
                void 0 !== b.ref && (g = b.ref, k = J.current);
                void 0 !== b.key && (d = "" + b.key);
                if (a.type && a.type.defaultProps) var f = a.type.defaultProps;
                for (h in b) K.call(b, h) && !L.hasOwnProperty(h) && (e[h] = void 0 === b[h] && void 0 !== f ? f[h] : b[h])
            }
            var h = arguments.length - 2;
            if (1 === h) e.children = c; else if (1 < h) {
                f = Array(h);
                for (var m = 0; m < h; m++) f[m] = arguments[m + 2];
                e.children = f
            }
            return {
                $$typeof: p, type: a.type,
                key: d, ref: g, props: e, _owner: k
            }
        };
        exports.createContext = function (a, b) {
            void 0 === b && (b = null);
            a = {$$typeof: w, _calculateChangedBits: b, _currentValue: a, _currentValue2: a, _threadCount: 0, Provider: null, Consumer: null};
            a.Provider = {$$typeof: v, _context: a};
            return a.Consumer = a
        };
        exports.createElement = M;
        exports.createFactory = function (a) {
            var b = M.bind(null, a);
            b.type = a;
            return b
        };
        exports.createRef = function () {
            return {current: null}
        };
        exports.forwardRef = function (a) {
            return {$$typeof: x, render: a}
        };
        exports.isValidElement = O;
        exports.lazy = function (a) {
            return {$$typeof: A, _ctor: a, _status: -1, _result: null}
        };
        exports.memo = function (a, b) {
            return {$$typeof: z, type: a, compare: void 0 === b ? null : b}
        };
        exports.useCallback = function (a, b) {
            return Z().useCallback(a, b)
        };
        exports.useContext = function (a, b) {
            return Z().useContext(a, b)
        };
        exports.useDebugValue = function () {
        };
        exports.useEffect = function (a, b) {
            return Z().useEffect(a, b)
        };
        exports.useImperativeHandle = function (a, b, c) {
            return Z().useImperativeHandle(a, b, c)
        };
        exports.useLayoutEffect = function (a, b) {
            return Z().useLayoutEffect(a, b)
        };
        exports.useMemo = function (a, b) {
            return Z().useMemo(a, b)
        };
        exports.useReducer = function (a, b, c) {
            return Z().useReducer(a, b, c)
        };
        exports.useRef = function (a) {
            return Z().useRef(a)
        };
        exports.useState = function (a) {
            return Z().useState(a)
        };
        exports.version = "16.14.0";


        /***/
    }),
    /* 4 */
    /***/ (function (module, exports, __webpack_require__) {

        "use strict";
        /*
        object-assign
        (c) Sindre Sorhus
        @license MIT
        */


        /* eslint-disable no-unused-vars */
        var getOwnPropertySymbols = Object.getOwnPropertySymbols;
        var hasOwnProperty = Object.prototype.hasOwnProperty;
        var propIsEnumerable = Object.prototype.propertyIsEnumerable;

        function toObject(val) {
            if (val === null || val === undefined) {
                throw new TypeError('Object.assign cannot be called with null or undefined');
            }

            return Object(val);
        }

        function shouldUseNative() {
            try {
                if (!Object.assign) {
                    return false;
                }

                // Detect buggy property enumeration order in older V8 versions.

                // https://bugs.chromium.org/p/v8/issues/detail?id=4118
                var test1 = new String('abc');  // eslint-disable-line no-new-wrappers
                test1[5] = 'de';
                if (Object.getOwnPropertyNames(test1)[0] === '5') {
                    return false;
                }

                // https://bugs.chromium.org/p/v8/issues/detail?id=3056
                var test2 = {};
                for (var i = 0; i < 10; i++) {
                    test2['_' + String.fromCharCode(i)] = i;
                }
                var order2 = Object.getOwnPropertyNames(test2).map(function (n) {
                    return test2[n];
                });
                if (order2.join('') !== '0123456789') {
                    return false;
                }

                // https://bugs.chromium.org/p/v8/issues/detail?id=3056
                var test3 = {};
                'abcdefghijklmnopqrst'.split('').forEach(function (letter) {
                    test3[letter] = letter;
                });
                if (Object.keys(Object.assign({}, test3)).join('') !==
                    'abcdefghijklmnopqrst') {
                    return false;
                }

                return true;
            } catch (err) {
                // We don't expect any of the above to throw, but better to be safe.
                return false;
            }
        }

        module.exports = shouldUseNative() ? Object.assign : function (target, source) {
            var from;
            var to = toObject(target);
            var symbols;

            for (var s = 1; s < arguments.length; s++) {
                from = Object(arguments[s]);

                for (var key in from) {
                    if (hasOwnProperty.call(from, key)) {
                        to[key] = from[key];
                    }
                }

                if (getOwnPropertySymbols) {
                    symbols = getOwnPropertySymbols(from);
                    for (var i = 0; i < symbols.length; i++) {
                        if (propIsEnumerable.call(from, symbols[i])) {
                            to[symbols[i]] = from[symbols[i]];
                        }
                    }
                }
            }

            return to;
        };


        /***/
    }),
    /* 5 */
    /***/ (function (module, __webpack_exports__, __webpack_require__) {

        "use strict";
// ESM COMPAT FLAG
        __webpack_require__.r(__webpack_exports__);

// EXTERNAL MODULE: /home/user/public_html/localwc/wp-content/plugins/funnel-builder/node_modules/react/index.js
        var react = __webpack_require__(0);
        var react_default = /*#__PURE__*/__webpack_require__.n(react);

// CONCATENATED MODULE: ./abs-component.js
        function _typeof(obj) {
            "@babel/helpers - typeof";
            if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") {
                _typeof = function _typeof(obj) {
                    return typeof obj;
                };
            } else {
                _typeof = function _typeof(obj) {
                    return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj;
                };
            }
            return _typeof(obj);
        }

        function _classCallCheck(instance, Constructor) {
            if (!(instance instanceof Constructor)) {
                throw new TypeError("Cannot call a class as a function");
            }
        }

        function _defineProperties(target, props) {
            for (var i = 0; i < props.length; i++) {
                var descriptor = props[i];
                descriptor.enumerable = descriptor.enumerable || false;
                descriptor.configurable = true;
                if ("value" in descriptor) descriptor.writable = true;
                Object.defineProperty(target, descriptor.key, descriptor);
            }
        }

        function _createClass(Constructor, protoProps, staticProps) {
            if (protoProps) _defineProperties(Constructor.prototype, protoProps);
            if (staticProps) _defineProperties(Constructor, staticProps);
            return Constructor;
        }

        function _inherits(subClass, superClass) {
            if (typeof superClass !== "function" && superClass !== null) {
                throw new TypeError("Super expression must either be null or a function");
            }
            subClass.prototype = Object.create(superClass && superClass.prototype, {constructor: {value: subClass, writable: true, configurable: true}});
            if (superClass) _setPrototypeOf(subClass, superClass);
        }

        function _setPrototypeOf(o, p) {
            _setPrototypeOf = Object.setPrototypeOf || function _setPrototypeOf(o, p) {
                o.__proto__ = p;
                return o;
            };
            return _setPrototypeOf(o, p);
        }

        function _createSuper(Derived) {
            var hasNativeReflectConstruct = _isNativeReflectConstruct();
            return function _createSuperInternal() {
                var Super = _getPrototypeOf(Derived), result;
                if (hasNativeReflectConstruct) {
                    var NewTarget = _getPrototypeOf(this).constructor;
                    result = Reflect.construct(Super, arguments, NewTarget);
                } else {
                    result = Super.apply(this, arguments);
                }
                return _possibleConstructorReturn(this, result);
            };
        }

        function _possibleConstructorReturn(self, call) {
            if (call && (_typeof(call) === "object" || typeof call === "function")) {
                return call;
            } else if (call !== void 0) {
                throw new TypeError("Derived constructors may only return object or undefined");
            }
            return _assertThisInitialized(self);
        }

        function _assertThisInitialized(self) {
            if (self === void 0) {
                throw new ReferenceError("this hasn't been initialised - super() hasn't been called");
            }
            return self;
        }

        function _isNativeReflectConstruct() {
            if (typeof Reflect === "undefined" || !Reflect.construct) return false;
            if (Reflect.construct.sham) return false;
            if (typeof Proxy === "function") return true;
            try {
                Boolean.prototype.valueOf.call(Reflect.construct(Boolean, [], function () {
                }));
                return true;
            } catch (e) {
                return false;
            }
        }

        function _getPrototypeOf(o) {
            _getPrototypeOf = Object.setPrototypeOf ? Object.getPrototypeOf : function _getPrototypeOf(o) {
                return o.__proto__ || Object.getPrototypeOf(o);
            };
            return _getPrototypeOf(o);
        }

        function _defineProperty(obj, key, value) {
            if (key in obj) {
                Object.defineProperty(obj, key, {value: value, enumerable: true, configurable: true, writable: true});
            } else {
                obj[key] = value;
            }
            return obj;
        }


        var abs_component_WFTY_Component = /*#__PURE__*/function (_React$Component) {
            _inherits(WFTY_Component, _React$Component);

            var _super = _createSuper(WFTY_Component);

            function WFTY_Component() {
                var _this;

                _classCallCheck(this, WFTY_Component);

                _this = _super.call(this);
                _this.timeout = null;
                _this.ajax = false;
                _this.c_slug = '';
                _this.state = {
                    formData: 'Loading ....'
                };
                return _this;
            }

            _createClass(WFTY_Component, [{
                key: "componentDidMount",
                value: function componentDidMount() {
                    if (true != this.ajax) {
                        return;
                    }

                    this.send_json();
                }
            }, {
                key: "componentDidUpdate",
                value: function componentDidUpdate(prevProps, prevState, snapshot) {
                    var _this2 = this;

                    if (true != this.ajax) {
                        return;
                    }

                    if (JSON.stringify(this.props) === JSON.stringify(prevProps)) {
                        return;
                    }

                    clearTimeout(this.timeout);
                    this.timeout = setTimeout(function () {
                        _this2.send_json();
                    }, 600);
                }
            }, {
                key: "send_json",
                value: function send_json() {
                    var _this3 = this;

                    var settings = JSON.stringify(this.props);
                    settings = JSON.parse(settings);
                    settings.action = this.c_slug;
                    settings.post_id = et_pb_custom.page_id;
                    settings.et_load_builder_modules = '1';
                    var request = {
                        url: et_pb_custom.ajaxurl,
                        method: 'POST',
                        data: settings,
                        success: function success(rsp, jqxhr, status) {
                            _this3.setState({
                                formData: rsp
                            });

                            _this3.ajaxSuccess(rsp, jqxhr, status);
                        },
                        complete: function complete(rsp, jqxhr, status) {
                        },
                        error: function error(rsp, jqxhr, status) {
                        }
                    };
                    jQuery.ajax(request);
                }
            }, {
                key: "ajaxSuccess",
                value: function ajaxSuccess(rsp, jqxhr, status) {
                }
            }, {
                key: "render",
                value: function render() {
                    return react_default.a.createElement("div", {
                        className: this.c_slug + " wfacp_divi_loader",
                        dangerouslySetInnerHTML: {
                            __html: this.state.formData
                        }
                    });
                }
            }], [{
                key: "css",
                value: function css(props) {
                    var utils = window.ET_Builder.API.Utils;
                    var wfacp_divi_style = [];

                    if (window.hasOwnProperty(this.c_slug + '_fields')) {
                        wfacp_divi_style = window[this.c_slug + '_fields'](utils, props);
                    }

                    return wfacp_divi_style;
                }
            }]);

            return WFTY_Component;
        }(react_default.a.Component);

        _defineProperty(abs_component_WFTY_Component, "style_data", []);

        /* harmony default export */
        var abs_component = (abs_component_WFTY_Component);

// CONCATENATED MODULE: ./order-details.js
        function order_details_typeof(obj) {
            "@babel/helpers - typeof";
            if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") {
                order_details_typeof = function _typeof(obj) {
                    return typeof obj;
                };
            } else {
                order_details_typeof = function _typeof(obj) {
                    return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj;
                };
            }
            return order_details_typeof(obj);
        }


        function order_details_classCallCheck(instance, Constructor) {
            if (!(instance instanceof Constructor)) {
                throw new TypeError("Cannot call a class as a function");
            }
        }

        function order_details_defineProperties(target, props) {
            for (var i = 0; i < props.length; i++) {
                var descriptor = props[i];
                descriptor.enumerable = descriptor.enumerable || false;
                descriptor.configurable = true;
                if ("value" in descriptor) descriptor.writable = true;
                Object.defineProperty(target, descriptor.key, descriptor);
            }
        }

        function order_details_createClass(Constructor, protoProps, staticProps) {
            if (protoProps) order_details_defineProperties(Constructor.prototype, protoProps);
            if (staticProps) order_details_defineProperties(Constructor, staticProps);
            return Constructor;
        }

        function order_details_inherits(subClass, superClass) {
            if (typeof superClass !== "function" && superClass !== null) {
                throw new TypeError("Super expression must either be null or a function");
            }
            subClass.prototype = Object.create(superClass && superClass.prototype, {constructor: {value: subClass, writable: true, configurable: true}});
            if (superClass) order_details_setPrototypeOf(subClass, superClass);
        }

        function order_details_setPrototypeOf(o, p) {
            order_details_setPrototypeOf = Object.setPrototypeOf || function _setPrototypeOf(o, p) {
                o.__proto__ = p;
                return o;
            };
            return order_details_setPrototypeOf(o, p);
        }

        function order_details_createSuper(Derived) {
            var hasNativeReflectConstruct = order_details_isNativeReflectConstruct();
            return function _createSuperInternal() {
                var Super = order_details_getPrototypeOf(Derived), result;
                if (hasNativeReflectConstruct) {
                    var NewTarget = order_details_getPrototypeOf(this).constructor;
                    result = Reflect.construct(Super, arguments, NewTarget);
                } else {
                    result = Super.apply(this, arguments);
                }
                return order_details_possibleConstructorReturn(this, result);
            };
        }

        function order_details_possibleConstructorReturn(self, call) {
            if (call && (order_details_typeof(call) === "object" || typeof call === "function")) {
                return call;
            } else if (call !== void 0) {
                throw new TypeError("Derived constructors may only return object or undefined");
            }
            return order_details_assertThisInitialized(self);
        }

        function order_details_assertThisInitialized(self) {
            if (self === void 0) {
                throw new ReferenceError("this hasn't been initialised - super() hasn't been called");
            }
            return self;
        }

        function order_details_isNativeReflectConstruct() {
            if (typeof Reflect === "undefined" || !Reflect.construct) return false;
            if (Reflect.construct.sham) return false;
            if (typeof Proxy === "function") return true;
            try {
                Boolean.prototype.valueOf.call(Reflect.construct(Boolean, [], function () {
                }));
                return true;
            } catch (e) {
                return false;
            }
        }

        function order_details_getPrototypeOf(o) {
            order_details_getPrototypeOf = Object.setPrototypeOf ? Object.getPrototypeOf : function _getPrototypeOf(o) {
                return o.__proto__ || Object.getPrototypeOf(o);
            };
            return order_details_getPrototypeOf(o);
        }

        function order_details_defineProperty(obj, key, value) {
            if (key in obj) {
                Object.defineProperty(obj, key, {value: value, enumerable: true, configurable: true, writable: true});
            } else {
                obj[key] = value;
            }
            return obj;
        }


        /*global wftyDivi*/

        var order_details_WFTY_Order_Details = /*#__PURE__*/function (_WFTY_Component) {
            order_details_inherits(WFTY_Order_Details, _WFTY_Component);

            var _super = order_details_createSuper(WFTY_Order_Details);

            function WFTY_Order_Details() {
                var _this;

                order_details_classCallCheck(this, WFTY_Order_Details);

                _this = _super.call(this);
                _this.c_slug = 'et_wfty_order_details';
                return _this;
            }

            order_details_createClass(WFTY_Order_Details, [{
                key: "render",
                value: function render() {
                    var dsClass = 'on' == this.props.order_download_preview ? "" : " wfty-hide-download";
                    dsClass += 'on' == this.props.order_subscription_preview ? "" : " wfty-hide-subscription";
                    return Object(react["createElement"])("div", {
                        id: this.c_slug,
                        className: dsClass
                    }, Object(react["createElement"])("div", {
                        className: "wffn_order_details_table"
                    }, Object(react["createElement"])("div", {
                        className: "wfty_wrap"
                    }, Object(react["createElement"])("div", {
                        className: "wfty_box wfty_order_details"
                    }, Object(react["createElement"])("h2", {
                        className: "wfty-order-details-heading wfty_title"
                    }, this.props.order_details_heading), Object(react["createElement"])("div", {
                        className: 'on' == this.props.order_details_img ? "wfty_pro_list_cont wfty_show_images" : "wfty_pro_list_cont wfty_hide_images"
                    }, Object(react["createElement"])("div", {
                        className: "wfty_pro_list wfty_clearfix"
                    }, Object(react["createElement"])("div", {
                        className: "wfty_leftDiv wfty_clearfix"
                    }, 'on' == this.props.order_details_img ? Object(react["createElement"])("div", {
                        className: "wfty_p_img"
                    }, Object(react["createElement"])("a", {
                        href: "javascript:void(0);"
                    }, Object(react["createElement"])("img", {
                        height: "100",
                        width: "100",
                        className: "attachment-shop_thumbnail size-shop_thumbnail",
                        src: wftyDiviOrder.img_url
                    }))) : '', Object(react["createElement"])("div", {
                        className: "wfty_p_name"
                    }, Object(react["createElement"])("a", {
                        href: "javascript:void(0);"
                    }, Object(react["createElement"])("span", {
                        className: "wfty_t"
                    }, wftyDiviOrder.pro_name)), Object(react["createElement"])("span", {
                        className: "wfty_quantity_value_box"
                    }, Object(react["createElement"])("span", {
                        className: "multiply"
                    }, " x"), Object(react["createElement"])("span", {
                        className: "qty"
                    }, "1")), Object(react["createElement"])("div", {
                        className: "wfty_info"
                    }, Object(react["createElement"])("ul", {
                        className: "wc-item-meta"
                    }, Object(react["createElement"])("li", null, Object(react["createElement"])("strong", {
                        className: "wc-item-meta-label"
                    }, "Color: "), Object(react["createElement"])("p", null, "Blue")), Object(react["createElement"])("li", null, Object(react["createElement"])("strong", {
                        className: "wc-item-meta-label"
                    }, "Size: "), Object(react["createElement"])("p", null, "Large")))))), Object(react["createElement"])("div", {
                        className: "wfty_rightDiv"
                    }, Object(react["createElement"])("span", {
                        className: "woocommerce-Price-amount amount"
                    }, Object(react["createElement"])("span", {
                        className: "woocommerce-Price-currencySymbol"
                    }, wftyDiviOrder.currency), wftyDiviOrder.price)), Object(react["createElement"])("div", {
                        className: "wfty-clearfix"
                    })), Object(react["createElement"])("table", null, Object(react["createElement"])("tfoot", null, Object(react["createElement"])("tr", null, Object(react["createElement"])("th", {
                        scope: "row"
                    }, wftyDiviOrder.sub_head, ":"), Object(react["createElement"])("td", null, Object(react["createElement"])("span", {
                        className: "woocommerce-Price-amount amount"
                    }, Object(react["createElement"])("span", {
                        className: "woocommerce-Price-currencySymbol"
                    }, wftyDiviOrder.currency), wftyDiviOrder.price))), 'true' == wftyDiviOrder.shipping ? Object(react["createElement"])("tr", null, Object(react["createElement"])("th", {
                        scope: "row"
                    }, wftyDiviOrder.ship_head, ":"), Object(react["createElement"])("td", null, Object(react["createElement"])("span", {
                        className: "woocommerce-Price-amount amount"
                    }, Object(react["createElement"])("span", {
                        className: "woocommerce-Price-currencySymbol"
                    }, wftyDiviOrder.currency), wftyDiviOrder.shipping_price), " ", Object(react["createElement"])("small", {
                        className: "shipped_via"
                    }, wftyDiviOrder.ship_text))) : '', Object(react["createElement"])("tr", null, Object(react["createElement"])("th", {
                        scope: "row"
                    }, wftyDiviOrder.payment_head, ":"), Object(react["createElement"])("td", null, wftyDiviOrder.payment_text)), Object(react["createElement"])("tr", null, Object(react["createElement"])("th", {
                        scope: "row"
                    }, wftyDiviOrder.total_head, ":"), Object(react["createElement"])("td", null, Object(react["createElement"])("span", {
                        className: "woocommerce-Price-amount amount"
                    }, Object(react["createElement"])("span", {
                        className: "woocommerce-Price-currencySymbol"
                    }, wftyDiviOrder.currency), wftyDiviOrder.total_price))))))), Object(react["createElement"])("div", {
                        className: "wfty_box wfty_order_download"
                    }, Object(react["createElement"])("div", {
                        className: "wfty_title"
                    }, this.props.order_download_heading), Object(react["createElement"])("table", {
                        className: "shop_table shop_table_responsive wfty_order_downloads"
                    }, Object(react["createElement"])("thead", null, Object(react["createElement"])("tr", null, Object(react["createElement"])("th", {
                        className: "download-product"
                    }, Object(react["createElement"])("span", {
                        className: "nobr"
                    }, wftyDiviOrder.down_th_file)), 'on' == this.props.order_downloads_file ? Object(react["createElement"])("th", {
                        className: "download-remaining"
                    }, Object(react["createElement"])("span", {
                        className: "nobr"
                    }, wftyDiviOrder.down_th_down)) : '', 'on' == this.props.order_downloads_file_expiry ? Object(react["createElement"])("th", {
                        className: "download-expires"
                    }, Object(react["createElement"])("span", {
                        className: "nobr"
                    }, wftyDiviOrder.down_th_exp)) : '', Object(react["createElement"])("th", {
                        className: "download-file"
                    }, Object(react["createElement"])("span", {
                        className: "nobr"
                    })))), Object(react["createElement"])("tbody", null, Object(react["createElement"])("tr", null, Object(react["createElement"])("td", {
                        "data-title": wftyDiviOrder.down_th_file,
                        className: "download-product"
                    }, wftyDiviOrder.down_td_file), 'on' == this.props.order_downloads_file ? Object(react["createElement"])("td", {
                        "data-title": wftyDiviOrder.down_th_down,
                        className: "download-remaining"
                    }, "10") : '', 'on' == this.props.order_downloads_file_expiry ? Object(react["createElement"])("td", {
                        "data-title": wftyDiviOrder.down_th_exp,
                        className: "download-expires"
                    }, wftyDiviOrder.down_td_exp) : '', Object(react["createElement"])("td", {
                        className: "download-file"
                    }, Object(react["createElement"])("a", {
                        href: "javascript:void(0);"
                    }, this.props.order_downloads_btn_text)))))), Object(react["createElement"])("div", {
                        className: "wfty_box wfty_subscription"
                    }, Object(react["createElement"])("div", {
                        className: "wfty_title"
                    }, this.props.order_subscription_heading), Object(react["createElement"])("table", {
                        className: "shop_table shop_table_responsive my_account_orders"
                    }, Object(react["createElement"])("thead", null, Object(react["createElement"])("tr", null, Object(react["createElement"])("th", {
                        className: "order-number wfty_left"
                    }, Object(react["createElement"])("span", {
                        className: "nobr"
                    }, wftyDiviOrder.subs_th_title)), Object(react["createElement"])("th", {
                        className: "order-status wfty_center"
                    }, Object(react["createElement"])("span", {
                        className: "nobr"
                    }, wftyDiviOrder.subs_th_pay)), Object(react["createElement"])("th", {
                        className: "order-total wfty_center"
                    }, Object(react["createElement"])("span", {
                        className: "nobr"
                    }, wftyDiviOrder.subs_th_tot)), Object(react["createElement"])("th", {
                        className: "order-total wfty_center"
                    }, Object(react["createElement"])("span", {
                        className: "nobr"
                    }, wftyDiviOrder.subs_th_act)))), Object(react["createElement"])("tbody", null, Object(react["createElement"])("tr", {
                        className: "order"
                    }, Object(react["createElement"])("td", {
                        "data-title": wftyDiviOrder.subs_th_title,
                        className: "subscription-id order-number wfty_left"
                    }, Object(react["createElement"])("a", {
                        href: "javascript:void(0);"
                    }, Object(react["createElement"])("strong", null, "#1234 ")), Object(react["createElement"])("small", null, "(", wftyDiviOrder.subs_td_title, ")")), Object(react["createElement"])("td", {
                        "data-title": wftyDiviOrder.subs_th_pay,
                        className: "subscription-next-payment order-date wfty_center"
                    }, wftyDiviOrder.subs_td_pay), Object(react["createElement"])("td", {
                        "data-title": wftyDiviOrder.subs_th_tot,
                        className: "subscription-total order-total wfty_center"
                    }, Object(react["createElement"])("span", {
                        "class": "woocommerce-Price-amount amount"
                    }, Object(react["createElement"])("span", {
                        className: "woocommerce-Price-currencySymbol"
                    }, wftyDiviOrder.currency), wftyDiviOrder.subs_td_tot)), Object(react["createElement"])("td", {
                        "data-title": wftyDiviOrder.subs_th_act,
                        className: "subscription-actions order-actions wfty_center"
                    }, Object(react["createElement"])("a", {
                        href: "javascript:void(0);",
                        className: "button view"
                    }, wftyDiviOrder.subs_td_act)))))))));
                }
            }], [{
                key: "css",
                value: function css(props) {
                    var utils = window.ET_Builder.API.Utils;
                    var wfty_divi_style = [];

                    if (window.hasOwnProperty(WFTY_Order_Details.slug + '_fields')) {
                        wfty_divi_style = window[WFTY_Order_Details.slug + '_fields'](utils, props);
                    }

                    return [wfty_divi_style];
                }
            }]);

            return WFTY_Order_Details;
        }(abs_component);

        order_details_defineProperty(order_details_WFTY_Order_Details, "slug", 'et_wfty_order_details');

        /* harmony default export */
        var order_details = (order_details_WFTY_Order_Details);

// CONCATENATED MODULE: ./customer-details.js
        function customer_details_typeof(obj) {
            "@babel/helpers - typeof";
            if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") {
                customer_details_typeof = function _typeof(obj) {
                    return typeof obj;
                };
            } else {
                customer_details_typeof = function _typeof(obj) {
                    return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj;
                };
            }
            return customer_details_typeof(obj);
        }


        function customer_details_classCallCheck(instance, Constructor) {
            if (!(instance instanceof Constructor)) {
                throw new TypeError("Cannot call a class as a function");
            }
        }

        function customer_details_defineProperties(target, props) {
            for (var i = 0; i < props.length; i++) {
                var descriptor = props[i];
                descriptor.enumerable = descriptor.enumerable || false;
                descriptor.configurable = true;
                if ("value" in descriptor) descriptor.writable = true;
                Object.defineProperty(target, descriptor.key, descriptor);
            }
        }

        function customer_details_createClass(Constructor, protoProps, staticProps) {
            if (protoProps) customer_details_defineProperties(Constructor.prototype, protoProps);
            if (staticProps) customer_details_defineProperties(Constructor, staticProps);
            return Constructor;
        }

        function customer_details_inherits(subClass, superClass) {
            if (typeof superClass !== "function" && superClass !== null) {
                throw new TypeError("Super expression must either be null or a function");
            }
            subClass.prototype = Object.create(superClass && superClass.prototype, {constructor: {value: subClass, writable: true, configurable: true}});
            if (superClass) customer_details_setPrototypeOf(subClass, superClass);
        }

        function customer_details_setPrototypeOf(o, p) {
            customer_details_setPrototypeOf = Object.setPrototypeOf || function _setPrototypeOf(o, p) {
                o.__proto__ = p;
                return o;
            };
            return customer_details_setPrototypeOf(o, p);
        }

        function customer_details_createSuper(Derived) {
            var hasNativeReflectConstruct = customer_details_isNativeReflectConstruct();
            return function _createSuperInternal() {
                var Super = customer_details_getPrototypeOf(Derived), result;
                if (hasNativeReflectConstruct) {
                    var NewTarget = customer_details_getPrototypeOf(this).constructor;
                    result = Reflect.construct(Super, arguments, NewTarget);
                } else {
                    result = Super.apply(this, arguments);
                }
                return customer_details_possibleConstructorReturn(this, result);
            };
        }

        function customer_details_possibleConstructorReturn(self, call) {
            if (call && (customer_details_typeof(call) === "object" || typeof call === "function")) {
                return call;
            } else if (call !== void 0) {
                throw new TypeError("Derived constructors may only return object or undefined");
            }
            return customer_details_assertThisInitialized(self);
        }

        function customer_details_assertThisInitialized(self) {
            if (self === void 0) {
                throw new ReferenceError("this hasn't been initialised - super() hasn't been called");
            }
            return self;
        }

        function customer_details_isNativeReflectConstruct() {
            if (typeof Reflect === "undefined" || !Reflect.construct) return false;
            if (Reflect.construct.sham) return false;
            if (typeof Proxy === "function") return true;
            try {
                Boolean.prototype.valueOf.call(Reflect.construct(Boolean, [], function () {
                }));
                return true;
            } catch (e) {
                return false;
            }
        }

        function customer_details_getPrototypeOf(o) {
            customer_details_getPrototypeOf = Object.setPrototypeOf ? Object.getPrototypeOf : function _getPrototypeOf(o) {
                return o.__proto__ || Object.getPrototypeOf(o);
            };
            return customer_details_getPrototypeOf(o);
        }

        function customer_details_defineProperty(obj, key, value) {
            if (key in obj) {
                Object.defineProperty(obj, key, {value: value, enumerable: true, configurable: true, writable: true});
            } else {
                obj[key] = value;
            }
            return obj;
        }


        /*global wftyDiviCustomer*/

        var customer_details_WFTY_Customer_Details = /*#__PURE__*/function (_WFTY_Component) {
            customer_details_inherits(WFTY_Customer_Details, _WFTY_Component);

            var _super = customer_details_createSuper(WFTY_Customer_Details);

            function WFTY_Customer_Details() {
                var _this;

                customer_details_classCallCheck(this, WFTY_Customer_Details);

                _this = _super.call(this);
                _this.c_slug = 'et_wfty_customer_details';
                return _this;
            }

            customer_details_createClass(WFTY_Customer_Details, [{
                key: "render",
                value: function render() {
                    //const Content = this.props.content;
                    var wfty_width_class = 'wfty_content wfty_clearfix wfty_text';
                    wfty_width_class += '1c' === this.props.customer_layout ? " wfty_full_width" : "";
                    wfty_width_class += '2c' === this.props.customer_layout_tablet ? " wfty_2c_tab_width" : "";
                    wfty_width_class += '2c' === this.props.customer_layout_phone ? " wfty_2c_mob_width" : "";
                    return Object(react["createElement"])("div", {
                        id: this.c_slug
                    }, Object(react["createElement"])("div", {
                        "class": "wfty_wrap"
                    }, Object(react["createElement"])("div", {
                        "class": "wfty_box wfty_customer_info"
                    }, Object(react["createElement"])("h2", {
                        "class": "wfty-customer-info-heading wfty_title"
                    }, this.props.heading), Object(react["createElement"])("div", {
                        "class": wfty_width_class
                    }, Object(react["createElement"])("div", {
                        "class": "wfty_2_col_left"
                    }, Object(react["createElement"])("div", {
                        "class": "wfty_text_bold"
                    }, Object(react["createElement"])("strong", null, wftyDiviCustomer.email_text)), Object(react["createElement"])("div", {
                        "class": "wfty_view"
                    }, "john.doe@gmail.com")), Object(react["createElement"])("div", {
                        "class": "wfty_2_col_right"
                    }, Object(react["createElement"])("div", {
                        "class": "wfty_text_bold"
                    }, Object(react["createElement"])("strong", null, wftyDiviCustomer.phone_text)), Object(react["createElement"])("div", {
                        "class": "wfty_view"
                    }, "(999) 999-9999")), Object(react["createElement"])("div", {
                        "class": "wfty_clear_15"
                    }), Object(react["createElement"])("div", {
                        "class": "wfty_2_col_left"
                    }, Object(react["createElement"])("div", {
                        "class": "wfty_text"
                    }, Object(react["createElement"])("div", {
                        "class": "wfty_text_bold"
                    }, Object(react["createElement"])("strong", null, wftyDiviCustomer.bill_text)), Object(react["createElement"])("div", {
                        "class": "wfty_view"
                    }, "John Doe ", Object(react["createElement"])("br", null), "711-2880 Nulla St ", Object(react["createElement"])("br", null), "New York, NY 10001"))), 'true' == wftyDiviCustomer.shipping ? Object(react["createElement"])("div", {
                        "class": "wfty_2_col_right"
                    }, Object(react["createElement"])("div", {
                        "class": "wfty_text"
                    }, Object(react["createElement"])("div", {
                        "class": "wfty_text_bold"
                    }, Object(react["createElement"])("strong", null, wftyDiviCustomer.ship_text)), Object(react["createElement"])("div", {
                        className: "wfty_view"
                    }, "John Doe ", Object(react["createElement"])("br", null), "711-2880 Nulla St ", Object(react["createElement"])("br", null), "New York, NY 10001"))) : '', Object(react["createElement"])("div", {
                        "class": "wfty_clear"
                    })))));
                }
            }], [{
                key: "css",
                value: function css(props) {
                    var utils = window.ET_Builder.API.Utils;
                    var wfty_divi_style = [];

                    if (window.hasOwnProperty(WFTY_Customer_Details.slug + '_fields')) {
                        wfty_divi_style = window[WFTY_Customer_Details.slug + '_fields'](utils, props);
                    }

                    return [wfty_divi_style];
                }
            }]);

            return WFTY_Customer_Details;
        }(abs_component);

        customer_details_defineProperty(customer_details_WFTY_Customer_Details, "slug", 'et_wfty_customer_details');

        /* harmony default export */
        var customer_details = (customer_details_WFTY_Customer_Details);
// CONCATENATED MODULE: ./main.js
        __webpack_require__(2);


        (function ($) {
            $(window).on('et_builder_api_ready', function (event, API) {
                API.registerModules([order_details, customer_details]);
            });
        })(jQuery);

        /***/
    })
    /******/]);