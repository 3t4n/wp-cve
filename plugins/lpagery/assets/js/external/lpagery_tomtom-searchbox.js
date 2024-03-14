! function(t) { var e = {};

    function r(n) { if (e[n]) return e[n].exports; var o = e[n] = { i: n, l: !1, exports: {} }; return t[n].call(o.exports, o, o.exports, r), o.l = !0, o.exports }
    r.m = t, r.c = e, r.d = function(t, e, n) { r.o(t, e) || Object.defineProperty(t, e, { enumerable: !0, get: n }) }, r.r = function(t) { "undefined" != typeof Symbol && Symbol.toStringTag && Object.defineProperty(t, Symbol.toStringTag, { value: "Module" }), Object.defineProperty(t, "__esModule", { value: !0 }) }, r.t = function(t, e) { if (1 & e && (t = r(t)), 8 & e) return t; if (4 & e && "object" == typeof t && t && t.__esModule) return t; var n = Object.create(null); if (r.r(n), Object.defineProperty(n, "default", { enumerable: !0, value: t }), 2 & e && "string" != typeof t)
            for (var o in t) r.d(n, o, function(e) { return t[e] }.bind(null, o)); return n }, r.n = function(t) { var e = t && t.__esModule ? function() { return t.default } : function() { return t }; return r.d(e, "a", e), e }, r.o = function(t, e) { return Object.prototype.hasOwnProperty.call(t, e) }, r.p = "", r(r.s = 14) }([function(t, e, r) { "use strict";

    function n(t) { if ("undefined" == typeof Symbol || null == t[Symbol.iterator]) { if (Array.isArray(t) || (t = function(t, e) { if (!t) return; if ("string" == typeof t) return o(t, e); var r = Object.prototype.toString.call(t).slice(8, -1); "Object" === r && t.constructor && (r = t.constructor.name); if ("Map" === r || "Set" === r) return Array.from(t); if ("Arguments" === r || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(r)) return o(t, e) }(t))) { var e = 0,
                    r = function() {}; return { s: r, n: function() { return e >= t.length ? { done: !0 } : { done: !1, value: t[e++] } }, e: function(t) { throw t }, f: r } } throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.") } var n, i, a = !0,
            s = !1; return { s: function() { n = t[Symbol.iterator]() }, n: function() { var t = n.next(); return a = t.done, t }, e: function(t) { s = !0, i = t }, f: function() { try { a || null == n.return || n.return() } finally { if (s) throw i } } } }

    function o(t, e) {
        (null == e || e > t.length) && (e = t.length); for (var r = 0, n = new Array(e); r < e; r++) n[r] = t[r]; return n }

    function i(t) { var e, r = !1,
            o = n(document.styleSheets); try { for (o.s(); !(e = o.n()).done;) { var i = e.value.href; if (i && -1 !== i.indexOf(t)) { r = !0; break } } } catch (t) { o.e(t) } finally { o.f() }
        r || console.warn('It seems that you forgot to add "'.concat(t, '" to your page, that is ') + "why some information might not be visible on your map. You can find the missing asset on our Downloads page: https://developer.tomtom.com/maps-sdk-web-js-v6/downloads") }
    e.a = function(t) { var e, r = n(t); try { for (r.s(); !(e = r.n()).done;) { i(e.value) } } catch (t) { r.e(t) } finally { r.f() } } }, function(t, e) { var r;
    r = function() { return this }(); try { r = r || Function("return this")() || (0, eval)("this") } catch (t) { "object" == typeof window && (r = window) }
    t.exports = r }, function(t, e, r) { "use strict";

    function n(t, e, r) { void 0 === r && (r = {}); var n = { type: "Feature" }; return (0 === r.id || r.id) && (n.id = r.id), r.bbox && (n.bbox = r.bbox), n.properties = e || {}, n.geometry = t, n }

    function o(t, e, r) { return void 0 === r && (r = {}), n({ type: "Point", coordinates: t }, e, r) }

    function i(t, e, r) { void 0 === r && (r = {}); for (var o = 0, i = t; o < i.length; o++) { var a = i[o]; if (a.length < 4) throw new Error("Each LinearRing of a Polygon must have 4 or more Positions."); for (var s = 0; s < a[a.length - 1].length; s++)
                if (a[a.length - 1][s] !== a[0][s]) throw new Error("First and last Position are not equivalent.") } return n({ type: "Polygon", coordinates: t }, e, r) }

    function a(t, e, r) { if (void 0 === r && (r = {}), t.length < 2) throw new Error("coordinates must be an array of two or more positions"); return n({ type: "LineString", coordinates: t }, e, r) }

    function s(t, e) { void 0 === e && (e = {}); var r = { type: "FeatureCollection" }; return e.id && (r.id = e.id), e.bbox && (r.bbox = e.bbox), r.features = t, r }

    function u(t, e, r) { return void 0 === r && (r = {}), n({ type: "MultiLineString", coordinates: t }, e, r) }

    function c(t, e, r) { return void 0 === r && (r = {}), n({ type: "MultiPoint", coordinates: t }, e, r) }

    function l(t, e, r) { return void 0 === r && (r = {}), n({ type: "MultiPolygon", coordinates: t }, e, r) }

    function f(t, r) { void 0 === r && (r = "kilometers"); var n = e.factors[r]; if (!n) throw new Error(r + " units is invalid"); return t * n }

    function d(t, r) { void 0 === r && (r = "kilometers"); var n = e.factors[r]; if (!n) throw new Error(r + " units is invalid"); return t / n }

    function p(t) { return 180 * (t % (2 * Math.PI)) / Math.PI }

    function h(t) { return !isNaN(t) && null !== t && !Array.isArray(t) && !/^\s*$/.test(t) }
    Object.defineProperty(e, "__esModule", { value: !0 }), e.earthRadius = 6371008.8, e.factors = { centimeters: 100 * e.earthRadius, centimetres: 100 * e.earthRadius, degrees: e.earthRadius / 111325, feet: 3.28084 * e.earthRadius, inches: 39.37 * e.earthRadius, kilometers: e.earthRadius / 1e3, kilometres: e.earthRadius / 1e3, meters: e.earthRadius, metres: e.earthRadius, miles: e.earthRadius / 1609.344, millimeters: 1e3 * e.earthRadius, millimetres: 1e3 * e.earthRadius, nauticalmiles: e.earthRadius / 1852, radians: 1, yards: e.earthRadius / 1.0936 }, e.unitsFactors = { centimeters: 100, centimetres: 100, degrees: 1 / 111325, feet: 3.28084, inches: 39.37, kilometers: .001, kilometres: .001, meters: 1, metres: 1, miles: 1 / 1609.344, millimeters: 1e3, millimetres: 1e3, nauticalmiles: 1 / 1852, radians: 1 / e.earthRadius, yards: 1 / 1.0936 }, e.areaFactors = { acres: 247105e-9, centimeters: 1e4, centimetres: 1e4, feet: 10.763910417, inches: 1550.003100006, kilometers: 1e-6, kilometres: 1e-6, meters: 1, metres: 1, miles: 386e-9, millimeters: 1e6, millimetres: 1e6, yards: 1.195990046 }, e.feature = n, e.geometry = function(t, e, r) { switch (void 0 === r && (r = {}), t) {
            case "Point":
                return o(e).geometry;
            case "LineString":
                return a(e).geometry;
            case "Polygon":
                return i(e).geometry;
            case "MultiPoint":
                return c(e).geometry;
            case "MultiLineString":
                return u(e).geometry;
            case "MultiPolygon":
                return l(e).geometry;
            default:
                throw new Error(t + " is invalid") } }, e.point = o, e.points = function(t, e, r) { return void 0 === r && (r = {}), s(t.map((function(t) { return o(t, e) })), r) }, e.polygon = i, e.polygons = function(t, e, r) { return void 0 === r && (r = {}), s(t.map((function(t) { return i(t, e) })), r) }, e.lineString = a, e.lineStrings = function(t, e, r) { return void 0 === r && (r = {}), s(t.map((function(t) { return a(t, e) })), r) }, e.featureCollection = s, e.multiLineString = u, e.multiPoint = c, e.multiPolygon = l, e.geometryCollection = function(t, e, r) { return void 0 === r && (r = {}), n({ type: "GeometryCollection", geometries: t }, e, r) }, e.round = function(t, e) { if (void 0 === e && (e = 0), e && !(e >= 0)) throw new Error("precision must be a positive number"); var r = Math.pow(10, e || 0); return Math.round(t * r) / r }, e.radiansToLength = f, e.lengthToRadians = d, e.lengthToDegrees = function(t, e) { return p(d(t, e)) }, e.bearingToAzimuth = function(t) { var e = t % 360; return e < 0 && (e += 360), e }, e.radiansToDegrees = p, e.degreesToRadians = function(t) { return t % 360 * Math.PI / 180 }, e.convertLength = function(t, e, r) { if (void 0 === e && (e = "kilometers"), void 0 === r && (r = "kilometers"), !(t >= 0)) throw new Error("length must be a positive number"); return f(d(t, e), r) }, e.convertArea = function(t, r, n) { if (void 0 === r && (r = "meters"), void 0 === n && (n = "kilometers"), !(t >= 0)) throw new Error("area must be a positive number"); var o = e.areaFactors[r]; if (!o) throw new Error("invalid original units"); var i = e.areaFactors[n]; if (!i) throw new Error("invalid final units"); return t / o * i }, e.isNumber = h, e.isObject = function(t) { return !!t && t.constructor === Object }, e.validateBBox = function(t) { if (!t) throw new Error("bbox is required"); if (!Array.isArray(t)) throw new Error("bbox must be an Array"); if (4 !== t.length && 6 !== t.length) throw new Error("bbox must be an Array of 4 or 6 numbers");
        t.forEach((function(t) { if (!h(t)) throw new Error("bbox must only contain numbers") })) }, e.validateId = function(t) { if (!t) throw new Error("id is required"); if (-1 === ["string", "number"].indexOf(typeof t)) throw new Error("id must be a number or a string") }, e.radians2degrees = function() { throw new Error("method has been renamed to `radiansToDegrees`") }, e.degrees2radians = function() { throw new Error("method has been renamed to `degreesToRadians`") }, e.distanceToDegrees = function() { throw new Error("method has been renamed to `lengthToDegrees`") }, e.distanceToRadians = function() { throw new Error("method has been renamed to `lengthToRadians`") }, e.radiansToDistance = function() { throw new Error("method has been renamed to `radiansToLength`") }, e.bearingToAngle = function() { throw new Error("method has been renamed to `bearingToAzimuth`") }, e.convertDistance = function() { throw new Error("method has been renamed to `convertLength`") } }, , function(t, e) { t.exports = function(t) { return t.webpackPolyfill || (t.deprecate = function() {}, t.paths = [], t.children || (t.children = []), Object.defineProperty(t, "loaded", { enumerable: !0, get: function() { return t.l } }), Object.defineProperty(t, "id", { enumerable: !0, get: function() { return t.i } }), t.webpackPolyfill = 1), t } }, function(t, e, r) {
    (function(e) { var r = /^\s+|\s+$/g,
            n = /^[-+]0x[0-9a-f]+$/i,
            o = /^0b[01]+$/i,
            i = /^0o[0-7]+$/i,
            a = parseInt,
            s = "object" == typeof e && e && e.Object === Object && e,
            u = "object" == typeof self && self && self.Object === Object && self,
            c = s || u || Function("return this")(),
            l = Object.prototype.toString,
            f = Math.max,
            d = Math.min,
            p = function() { return c.Date.now() };

        function h(t) { var e = typeof t; return !!t && ("object" == e || "function" == e) }

        function y(t) { if ("number" == typeof t) return t; if (function(t) { return "symbol" == typeof t || function(t) { return !!t && "object" == typeof t }(t) && "[object Symbol]" == l.call(t) }(t)) return NaN; if (h(t)) { var e = "function" == typeof t.valueOf ? t.valueOf() : t;
                t = h(e) ? e + "" : e } if ("string" != typeof t) return 0 === t ? t : +t;
            t = t.replace(r, ""); var s = o.test(t); return s || i.test(t) ? a(t.slice(2), s ? 2 : 8) : n.test(t) ? NaN : +t }
        t.exports = function(t, e, r) { var n, o, i, a, s, u, c = 0,
                l = !1,
                _ = !1,
                v = !0; if ("function" != typeof t) throw new TypeError("Expected a function");

            function b(e) { var r = n,
                    i = o; return n = o = void 0, c = e, a = t.apply(i, r) }

            function g(t) { return c = t, s = setTimeout(w, e), l ? b(t) : a }

            function m(t) { var r = t - u; return void 0 === u || r >= e || r < 0 || _ && t - c >= i }

            function w() { var t = p(); if (m(t)) return j(t);
                s = setTimeout(w, function(t) { var r = e - (t - u); return _ ? d(r, i - (t - c)) : r }(t)) }

            function j(t) { return s = void 0, v && n ? b(t) : (n = o = void 0, a) }

            function O() { var t = p(),
                    r = m(t); if (n = arguments, o = this, u = t, r) { if (void 0 === s) return g(u); if (_) return s = setTimeout(w, e), b(u) } return void 0 === s && (s = setTimeout(w, e)), a } return e = y(e) || 0, h(r) && (l = !!r.leading, i = (_ = "maxWait" in r) ? f(y(r.maxWait) || 0, e) : i, v = "trailing" in r ? !!r.trailing : v), O.cancel = function() { void 0 !== s && clearTimeout(s), c = 0, n = u = o = s = void 0 }, O.flush = function() { return void 0 === s ? a : j(p()) }, O } }).call(this, r(1)) }, function(t, e, r) {
    (function(t, r) { var n = "[object Arguments]",
            o = "[object Map]",
            i = "[object Object]",
            a = "[object Set]",
            s = /^\[object .+?Constructor\]$/,
            u = /^(?:0|[1-9]\d*)$/,
            c = {};
        c["[object Float32Array]"] = c["[object Float64Array]"] = c["[object Int8Array]"] = c["[object Int16Array]"] = c["[object Int32Array]"] = c["[object Uint8Array]"] = c["[object Uint8ClampedArray]"] = c["[object Uint16Array]"] = c["[object Uint32Array]"] = !0, c[n] = c["[object Array]"] = c["[object ArrayBuffer]"] = c["[object Boolean]"] = c["[object DataView]"] = c["[object Date]"] = c["[object Error]"] = c["[object Function]"] = c[o] = c["[object Number]"] = c[i] = c["[object RegExp]"] = c[a] = c["[object String]"] = c["[object WeakMap]"] = !1; var l = "object" == typeof t && t && t.Object === Object && t,
            f = "object" == typeof self && self && self.Object === Object && self,
            d = l || f || Function("return this")(),
            p = e && !e.nodeType && e,
            h = p && "object" == typeof r && r && !r.nodeType && r,
            y = h && h.exports === p,
            _ = y && l.process,
            v = function() { try { return _ && _.binding && _.binding("util") } catch (t) {} }(),
            b = v && v.isTypedArray;

        function g(t, e) { for (var r = -1, n = null == t ? 0 : t.length; ++r < n;)
                if (e(t[r], r, t)) return !0;
            return !1 }

        function m(t) { var e = -1,
                r = Array(t.size); return t.forEach((function(t, n) { r[++e] = [n, t] })), r }

        function w(t) { var e = -1,
                r = Array(t.size); return t.forEach((function(t) { r[++e] = t })), r } var j, O, S, C = Array.prototype,
            A = Function.prototype,
            x = Object.prototype,
            P = d["__core-js_shared__"],
            E = A.toString,
            R = x.hasOwnProperty,
            F = (j = /[^.]+$/.exec(P && P.keys && P.keys.IE_PROTO || "")) ? "Symbol(src)_1." + j : "",
            T = x.toString,
            D = RegExp("^" + E.call(R).replace(/[\\^$.*+?()[\]{}|]/g, "\\$&").replace(/hasOwnProperty|(function).*?(?=\\\()| for .+?(?=\\\])/g, "$1.*?") + "$"),
            I = y ? d.Buffer : void 0,
            k = d.Symbol,
            M = d.Uint8Array,
            L = x.propertyIsEnumerable,
            B = C.splice,
            N = k ? k.toStringTag : void 0,
            z = Object.getOwnPropertySymbols,
            U = I ? I.isBuffer : void 0,
            $ = (O = Object.keys, S = Object, function(t) { return O(S(t)) }),
            H = vt(d, "DataView"),
            V = vt(d, "Map"),
            q = vt(d, "Promise"),
            K = vt(d, "Set"),
            W = vt(d, "WeakMap"),
            G = vt(Object, "create"),
            Z = wt(H),
            Q = wt(V),
            Y = wt(q),
            J = wt(K),
            X = wt(W),
            tt = k ? k.prototype : void 0,
            et = tt ? tt.valueOf : void 0;

        function rt(t) { var e = -1,
                r = null == t ? 0 : t.length; for (this.clear(); ++e < r;) { var n = t[e];
                this.set(n[0], n[1]) } }

        function nt(t) { var e = -1,
                r = null == t ? 0 : t.length; for (this.clear(); ++e < r;) { var n = t[e];
                this.set(n[0], n[1]) } }

        function ot(t) { var e = -1,
                r = null == t ? 0 : t.length; for (this.clear(); ++e < r;) { var n = t[e];
                this.set(n[0], n[1]) } }

        function it(t) { var e = -1,
                r = null == t ? 0 : t.length; for (this.__data__ = new ot; ++e < r;) this.add(t[e]) }

        function at(t) { var e = this.__data__ = new nt(t);
            this.size = e.size }

        function st(t, e) { var r = St(t),
                n = !r && Ot(t),
                o = !r && !n && Ct(t),
                i = !r && !n && !o && Rt(t),
                a = r || n || o || i,
                s = a ? function(t, e) { for (var r = -1, n = Array(t); ++r < t;) n[r] = e(r); return n }(t.length, String) : [],
                u = s.length; for (var c in t) !e && !R.call(t, c) || a && ("length" == c || o && ("offset" == c || "parent" == c) || i && ("buffer" == c || "byteLength" == c || "byteOffset" == c) || mt(c, u)) || s.push(c); return s }

        function ut(t, e) { for (var r = t.length; r--;)
                if (jt(t[r][0], e)) return r;
            return -1 }

        function ct(t) { return null == t ? void 0 === t ? "[object Undefined]" : "[object Null]" : N && N in Object(t) ? function(t) { var e = R.call(t, N),
                    r = t[N]; try { t[N] = void 0; var n = !0 } catch (t) {} var o = T.call(t);
                n && (e ? t[N] = r : delete t[N]); return o }(t) : function(t) { return T.call(t) }(t) }

        function lt(t) { return Et(t) && ct(t) == n }

        function ft(t, e, r, s, u) { return t === e || (null == t || null == e || !Et(t) && !Et(e) ? t != t && e != e : function(t, e, r, s, u, c) { var l = St(t),
                    f = St(e),
                    d = l ? "[object Array]" : gt(t),
                    p = f ? "[object Array]" : gt(e),
                    h = (d = d == n ? i : d) == i,
                    y = (p = p == n ? i : p) == i,
                    _ = d == p; if (_ && Ct(t)) { if (!Ct(e)) return !1;
                    l = !0, h = !1 } if (_ && !h) return c || (c = new at), l || Rt(t) ? ht(t, e, r, s, u, c) : function(t, e, r, n, i, s, u) { switch (r) {
                        case "[object DataView]":
                            if (t.byteLength != e.byteLength || t.byteOffset != e.byteOffset) return !1;
                            t = t.buffer, e = e.buffer;
                        case "[object ArrayBuffer]":
                            return !(t.byteLength != e.byteLength || !s(new M(t), new M(e)));
                        case "[object Boolean]":
                        case "[object Date]":
                        case "[object Number]":
                            return jt(+t, +e);
                        case "[object Error]":
                            return t.name == e.name && t.message == e.message;
                        case "[object RegExp]":
                        case "[object String]":
                            return t == e + "";
                        case o:
                            var c = m;
                        case a:
                            var l = 1 & n; if (c || (c = w), t.size != e.size && !l) return !1; var f = u.get(t); if (f) return f == e;
                            n |= 2, u.set(t, e); var d = ht(c(t), c(e), n, i, s, u); return u.delete(t), d;
                        case "[object Symbol]":
                            if (et) return et.call(t) == et.call(e) } return !1 }(t, e, d, r, s, u, c); if (!(1 & r)) { var v = h && R.call(t, "__wrapped__"),
                        b = y && R.call(e, "__wrapped__"); if (v || b) { var g = v ? t.value() : t,
                            j = b ? e.value() : e; return c || (c = new at), u(g, j, r, s, c) } } if (!_) return !1; return c || (c = new at),
                    function(t, e, r, n, o, i) { var a = 1 & r,
                            s = yt(t),
                            u = s.length,
                            c = yt(e).length; if (u != c && !a) return !1; var l = u; for (; l--;) { var f = s[l]; if (!(a ? f in e : R.call(e, f))) return !1 } var d = i.get(t); if (d && i.get(e)) return d == e; var p = !0;
                        i.set(t, e), i.set(e, t); var h = a; for (; ++l < u;) { f = s[l]; var y = t[f],
                                _ = e[f]; if (n) var v = a ? n(_, y, f, e, t, i) : n(y, _, f, t, e, i); if (!(void 0 === v ? y === _ || o(y, _, r, n, i) : v)) { p = !1; break }
                            h || (h = "constructor" == f) } if (p && !h) { var b = t.constructor,
                                g = e.constructor;
                            b == g || !("constructor" in t) || !("constructor" in e) || "function" == typeof b && b instanceof b && "function" == typeof g && g instanceof g || (p = !1) } return i.delete(t), i.delete(e), p }(t, e, r, s, u, c) }(t, e, r, s, ft, u)) }

        function dt(t) { return !(!Pt(t) || function(t) { return !!F && F in t }(t)) && (At(t) ? D : s).test(wt(t)) }

        function pt(t) { if (r = (e = t) && e.constructor, n = "function" == typeof r && r.prototype || x, e !== n) return $(t); var e, r, n, o = []; for (var i in Object(t)) R.call(t, i) && "constructor" != i && o.push(i); return o }

        function ht(t, e, r, n, o, i) { var a = 1 & r,
                s = t.length,
                u = e.length; if (s != u && !(a && u > s)) return !1; var c = i.get(t); if (c && i.get(e)) return c == e; var l = -1,
                f = !0,
                d = 2 & r ? new it : void 0; for (i.set(t, e), i.set(e, t); ++l < s;) { var p = t[l],
                    h = e[l]; if (n) var y = a ? n(h, p, l, e, t, i) : n(p, h, l, t, e, i); if (void 0 !== y) { if (y) continue;
                    f = !1; break } if (d) { if (!g(e, (function(t, e) { if (a = e, !d.has(a) && (p === t || o(p, t, r, n, i))) return d.push(e); var a }))) { f = !1; break } } else if (p !== h && !o(p, h, r, n, i)) { f = !1; break } } return i.delete(t), i.delete(e), f }

        function yt(t) { return function(t, e, r) { var n = e(t); return St(t) ? n : function(t, e) { for (var r = -1, n = e.length, o = t.length; ++r < n;) t[o + r] = e[r]; return t }(n, r(t)) }(t, Ft, bt) }

        function _t(t, e) { var r, n, o = t.__data__; return ("string" == (n = typeof(r = e)) || "number" == n || "symbol" == n || "boolean" == n ? "__proto__" !== r : null === r) ? o["string" == typeof e ? "string" : "hash"] : o.map }

        function vt(t, e) { var r = function(t, e) { return null == t ? void 0 : t[e] }(t, e); return dt(r) ? r : void 0 }
        rt.prototype.clear = function() { this.__data__ = G ? G(null) : {}, this.size = 0 }, rt.prototype.delete = function(t) { var e = this.has(t) && delete this.__data__[t]; return this.size -= e ? 1 : 0, e }, rt.prototype.get = function(t) { var e = this.__data__; if (G) { var r = e[t]; return "__lodash_hash_undefined__" === r ? void 0 : r } return R.call(e, t) ? e[t] : void 0 }, rt.prototype.has = function(t) { var e = this.__data__; return G ? void 0 !== e[t] : R.call(e, t) }, rt.prototype.set = function(t, e) { var r = this.__data__; return this.size += this.has(t) ? 0 : 1, r[t] = G && void 0 === e ? "__lodash_hash_undefined__" : e, this }, nt.prototype.clear = function() { this.__data__ = [], this.size = 0 }, nt.prototype.delete = function(t) { var e = this.__data__,
                r = ut(e, t); return !(r < 0) && (r == e.length - 1 ? e.pop() : B.call(e, r, 1), --this.size, !0) }, nt.prototype.get = function(t) { var e = this.__data__,
                r = ut(e, t); return r < 0 ? void 0 : e[r][1] }, nt.prototype.has = function(t) { return ut(this.__data__, t) > -1 }, nt.prototype.set = function(t, e) { var r = this.__data__,
                n = ut(r, t); return n < 0 ? (++this.size, r.push([t, e])) : r[n][1] = e, this }, ot.prototype.clear = function() { this.size = 0, this.__data__ = { hash: new rt, map: new(V || nt), string: new rt } }, ot.prototype.delete = function(t) { var e = _t(this, t).delete(t); return this.size -= e ? 1 : 0, e }, ot.prototype.get = function(t) { return _t(this, t).get(t) }, ot.prototype.has = function(t) { return _t(this, t).has(t) }, ot.prototype.set = function(t, e) { var r = _t(this, t),
                n = r.size; return r.set(t, e), this.size += r.size == n ? 0 : 1, this }, it.prototype.add = it.prototype.push = function(t) { return this.__data__.set(t, "__lodash_hash_undefined__"), this }, it.prototype.has = function(t) { return this.__data__.has(t) }, at.prototype.clear = function() { this.__data__ = new nt, this.size = 0 }, at.prototype.delete = function(t) { var e = this.__data__,
                r = e.delete(t); return this.size = e.size, r }, at.prototype.get = function(t) { return this.__data__.get(t) }, at.prototype.has = function(t) { return this.__data__.has(t) }, at.prototype.set = function(t, e) { var r = this.__data__; if (r instanceof nt) { var n = r.__data__; if (!V || n.length < 199) return n.push([t, e]), this.size = ++r.size, this;
                r = this.__data__ = new ot(n) } return r.set(t, e), this.size = r.size, this }; var bt = z ? function(t) { return null == t ? [] : (t = Object(t), function(t, e) { for (var r = -1, n = null == t ? 0 : t.length, o = 0, i = []; ++r < n;) { var a = t[r];
                        e(a, r, t) && (i[o++] = a) } return i }(z(t), (function(e) { return L.call(t, e) }))) } : function() { return [] },
            gt = ct;

        function mt(t, e) { return !!(e = null == e ? 9007199254740991 : e) && ("number" == typeof t || u.test(t)) && t > -1 && t % 1 == 0 && t < e }

        function wt(t) { if (null != t) { try { return E.call(t) } catch (t) {} try { return t + "" } catch (t) {} } return "" }

        function jt(t, e) { return t === e || t != t && e != e }(H && "[object DataView]" != gt(new H(new ArrayBuffer(1))) || V && gt(new V) != o || q && "[object Promise]" != gt(q.resolve()) || K && gt(new K) != a || W && "[object WeakMap]" != gt(new W)) && (gt = function(t) { var e = ct(t),
                r = e == i ? t.constructor : void 0,
                n = r ? wt(r) : ""; if (n) switch (n) {
                case Z:
                    return "[object DataView]";
                case Q:
                    return o;
                case Y:
                    return "[object Promise]";
                case J:
                    return a;
                case X:
                    return "[object WeakMap]" }
            return e }); var Ot = lt(function() { return arguments }()) ? lt : function(t) { return Et(t) && R.call(t, "callee") && !L.call(t, "callee") },
            St = Array.isArray; var Ct = U || function() { return !1 };

        function At(t) { if (!Pt(t)) return !1; var e = ct(t); return "[object Function]" == e || "[object GeneratorFunction]" == e || "[object AsyncFunction]" == e || "[object Proxy]" == e }

        function xt(t) { return "number" == typeof t && t > -1 && t % 1 == 0 && t <= 9007199254740991 }

        function Pt(t) { var e = typeof t; return null != t && ("object" == e || "function" == e) }

        function Et(t) { return null != t && "object" == typeof t } var Rt = b ? function(t) { return function(e) { return t(e) } }(b) : function(t) { return Et(t) && xt(t.length) && !!c[ct(t)] };

        function Ft(t) { return null != (e = t) && xt(e.length) && !At(e) ? st(t) : pt(t); var e }
        r.exports = function(t, e) { return ft(t, e) } }).call(this, r(1), r(4)(t)) }, function(t, e, r) {
    (function(t, r) { var n = "[object Arguments]",
            o = "[object Function]",
            i = "[object GeneratorFunction]",
            a = "[object Map]",
            s = "[object Set]",
            u = /\w*$/,
            c = /^\[object .+?Constructor\]$/,
            l = /^(?:0|[1-9]\d*)$/,
            f = {};
        f[n] = f["[object Array]"] = f["[object ArrayBuffer]"] = f["[object DataView]"] = f["[object Boolean]"] = f["[object Date]"] = f["[object Float32Array]"] = f["[object Float64Array]"] = f["[object Int8Array]"] = f["[object Int16Array]"] = f["[object Int32Array]"] = f[a] = f["[object Number]"] = f["[object Object]"] = f["[object RegExp]"] = f[s] = f["[object String]"] = f["[object Symbol]"] = f["[object Uint8Array]"] = f["[object Uint8ClampedArray]"] = f["[object Uint16Array]"] = f["[object Uint32Array]"] = !0, f["[object Error]"] = f[o] = f["[object WeakMap]"] = !1; var d = "object" == typeof t && t && t.Object === Object && t,
            p = "object" == typeof self && self && self.Object === Object && self,
            h = d || p || Function("return this")(),
            y = e && !e.nodeType && e,
            _ = y && "object" == typeof r && r && !r.nodeType && r,
            v = _ && _.exports === y;

        function b(t, e) { return t.set(e[0], e[1]), t }

        function g(t, e) { return t.add(e), t }

        function m(t, e, r, n) { var o = -1,
                i = t ? t.length : 0; for (n && i && (r = t[++o]); ++o < i;) r = e(r, t[o], o, t); return r }

        function w(t) { var e = !1; if (null != t && "function" != typeof t.toString) try { e = !!(t + "") } catch (t) {}
            return e }

        function j(t) { var e = -1,
                r = Array(t.size); return t.forEach((function(t, n) { r[++e] = [n, t] })), r }

        function O(t, e) { return function(r) { return t(e(r)) } }

        function S(t) { var e = -1,
                r = Array(t.size); return t.forEach((function(t) { r[++e] = t })), r } var C, A = Array.prototype,
            x = Function.prototype,
            P = Object.prototype,
            E = h["__core-js_shared__"],
            R = (C = /[^.]+$/.exec(E && E.keys && E.keys.IE_PROTO || "")) ? "Symbol(src)_1." + C : "",
            F = x.toString,
            T = P.hasOwnProperty,
            D = P.toString,
            I = RegExp("^" + F.call(T).replace(/[\\^$.*+?()[\]{}|]/g, "\\$&").replace(/hasOwnProperty|(function).*?(?=\\\()| for .+?(?=\\\])/g, "$1.*?") + "$"),
            k = v ? h.Buffer : void 0,
            M = h.Symbol,
            L = h.Uint8Array,
            B = O(Object.getPrototypeOf, Object),
            N = Object.create,
            z = P.propertyIsEnumerable,
            U = A.splice,
            $ = Object.getOwnPropertySymbols,
            H = k ? k.isBuffer : void 0,
            V = O(Object.keys, Object),
            q = _t(h, "DataView"),
            K = _t(h, "Map"),
            W = _t(h, "Promise"),
            G = _t(h, "Set"),
            Z = _t(h, "WeakMap"),
            Q = _t(Object, "create"),
            Y = wt(q),
            J = wt(K),
            X = wt(W),
            tt = wt(G),
            et = wt(Z),
            rt = M ? M.prototype : void 0,
            nt = rt ? rt.valueOf : void 0;

        function ot(t) { var e = -1,
                r = t ? t.length : 0; for (this.clear(); ++e < r;) { var n = t[e];
                this.set(n[0], n[1]) } }

        function it(t) { var e = -1,
                r = t ? t.length : 0; for (this.clear(); ++e < r;) { var n = t[e];
                this.set(n[0], n[1]) } }

        function at(t) { var e = -1,
                r = t ? t.length : 0; for (this.clear(); ++e < r;) { var n = t[e];
                this.set(n[0], n[1]) } }

        function st(t) { this.__data__ = new it(t) }

        function ut(t, e) { var r = Ot(t) || function(t) { return function(t) { return function(t) { return !!t && "object" == typeof t }(t) && St(t) }(t) && T.call(t, "callee") && (!z.call(t, "callee") || D.call(t) == n) }(t) ? function(t, e) { for (var r = -1, n = Array(t); ++r < t;) n[r] = e(r); return n }(t.length, String) : [],
                o = r.length,
                i = !!o; for (var a in t) !e && !T.call(t, a) || i && ("length" == a || gt(a, o)) || r.push(a); return r }

        function ct(t, e, r) { var n = t[e];
            T.call(t, e) && jt(n, r) && (void 0 !== r || e in t) || (t[e] = r) }

        function lt(t, e) { for (var r = t.length; r--;)
                if (jt(t[r][0], e)) return r;
            return -1 }

        function ft(t, e, r, c, l, d, p) { var h; if (c && (h = d ? c(t, l, d, p) : c(t)), void 0 !== h) return h; if (!xt(t)) return t; var y = Ot(t); if (y) { if (h = function(t) { var e = t.length,
                            r = t.constructor(e);
                        e && "string" == typeof t[0] && T.call(t, "index") && (r.index = t.index, r.input = t.input); return r }(t), !e) return function(t, e) { var r = -1,
                        n = t.length;
                    e || (e = Array(n)); for (; ++r < n;) e[r] = t[r]; return e }(t, h) } else { var _ = bt(t),
                    v = _ == o || _ == i; if (Ct(t)) return function(t, e) { if (e) return t.slice(); var r = new t.constructor(t.length); return t.copy(r), r }(t, e); if ("[object Object]" == _ || _ == n || v && !d) { if (w(t)) return d ? t : {}; if (h = function(t) { return "function" != typeof t.constructor || mt(t) ? {} : (e = B(t), xt(e) ? N(e) : {}); var e }(v ? {} : t), !e) return function(t, e) { return ht(t, vt(t), e) }(t, function(t, e) { return t && ht(e, Pt(e), t) }(h, t)) } else { if (!f[_]) return d ? t : {};
                    h = function(t, e, r, n) { var o = t.constructor; switch (e) {
                            case "[object ArrayBuffer]":
                                return pt(t);
                            case "[object Boolean]":
                            case "[object Date]":
                                return new o(+t);
                            case "[object DataView]":
                                return function(t, e) { var r = e ? pt(t.buffer) : t.buffer; return new t.constructor(r, t.byteOffset, t.byteLength) }(t, n);
                            case "[object Float32Array]":
                            case "[object Float64Array]":
                            case "[object Int8Array]":
                            case "[object Int16Array]":
                            case "[object Int32Array]":
                            case "[object Uint8Array]":
                            case "[object Uint8ClampedArray]":
                            case "[object Uint16Array]":
                            case "[object Uint32Array]":
                                return function(t, e) { var r = e ? pt(t.buffer) : t.buffer; return new t.constructor(r, t.byteOffset, t.length) }(t, n);
                            case a:
                                return function(t, e, r) { return m(e ? r(j(t), !0) : j(t), b, new t.constructor) }(t, n, r);
                            case "[object Number]":
                            case "[object String]":
                                return new o(t);
                            case "[object RegExp]":
                                return function(t) { var e = new t.constructor(t.source, u.exec(t)); return e.lastIndex = t.lastIndex, e }(t);
                            case s:
                                return function(t, e, r) { return m(e ? r(S(t), !0) : S(t), g, new t.constructor) }(t, n, r);
                            case "[object Symbol]":
                                return i = t, nt ? Object(nt.call(i)) : {} } var i }(t, _, ft, e) } }
            p || (p = new st); var O = p.get(t); if (O) return O; if (p.set(t, h), !y) var C = r ? function(t) { return function(t, e, r) { var n = e(t); return Ot(t) ? n : function(t, e) { for (var r = -1, n = e.length, o = t.length; ++r < n;) t[o + r] = e[r]; return t }(n, r(t)) }(t, Pt, vt) }(t) : Pt(t); return function(t, e) { for (var r = -1, n = t ? t.length : 0; ++r < n && !1 !== e(t[r], r, t);); }(C || t, (function(n, o) { C && (n = t[o = n]), ct(h, o, ft(n, e, r, c, o, t, p)) })), h }

        function dt(t) { return !(!xt(t) || (e = t, R && R in e)) && (At(t) || w(t) ? I : c).test(wt(t)); var e }

        function pt(t) { var e = new t.constructor(t.byteLength); return new L(e).set(new L(t)), e }

        function ht(t, e, r, n) { r || (r = {}); for (var o = -1, i = e.length; ++o < i;) { var a = e[o],
                    s = n ? n(r[a], t[a], a, r, t) : void 0;
                ct(r, a, void 0 === s ? t[a] : s) } return r }

        function yt(t, e) { var r, n, o = t.__data__; return ("string" == (n = typeof(r = e)) || "number" == n || "symbol" == n || "boolean" == n ? "__proto__" !== r : null === r) ? o["string" == typeof e ? "string" : "hash"] : o.map }

        function _t(t, e) { var r = function(t, e) { return null == t ? void 0 : t[e] }(t, e); return dt(r) ? r : void 0 }
        ot.prototype.clear = function() { this.__data__ = Q ? Q(null) : {} }, ot.prototype.delete = function(t) { return this.has(t) && delete this.__data__[t] }, ot.prototype.get = function(t) { var e = this.__data__; if (Q) { var r = e[t]; return "__lodash_hash_undefined__" === r ? void 0 : r } return T.call(e, t) ? e[t] : void 0 }, ot.prototype.has = function(t) { var e = this.__data__; return Q ? void 0 !== e[t] : T.call(e, t) }, ot.prototype.set = function(t, e) { return this.__data__[t] = Q && void 0 === e ? "__lodash_hash_undefined__" : e, this }, it.prototype.clear = function() { this.__data__ = [] }, it.prototype.delete = function(t) { var e = this.__data__,
                r = lt(e, t); return !(r < 0) && (r == e.length - 1 ? e.pop() : U.call(e, r, 1), !0) }, it.prototype.get = function(t) { var e = this.__data__,
                r = lt(e, t); return r < 0 ? void 0 : e[r][1] }, it.prototype.has = function(t) { return lt(this.__data__, t) > -1 }, it.prototype.set = function(t, e) { var r = this.__data__,
                n = lt(r, t); return n < 0 ? r.push([t, e]) : r[n][1] = e, this }, at.prototype.clear = function() { this.__data__ = { hash: new ot, map: new(K || it), string: new ot } }, at.prototype.delete = function(t) { return yt(this, t).delete(t) }, at.prototype.get = function(t) { return yt(this, t).get(t) }, at.prototype.has = function(t) { return yt(this, t).has(t) }, at.prototype.set = function(t, e) { return yt(this, t).set(t, e), this }, st.prototype.clear = function() { this.__data__ = new it }, st.prototype.delete = function(t) { return this.__data__.delete(t) }, st.prototype.get = function(t) { return this.__data__.get(t) }, st.prototype.has = function(t) { return this.__data__.has(t) }, st.prototype.set = function(t, e) { var r = this.__data__; if (r instanceof it) { var n = r.__data__; if (!K || n.length < 199) return n.push([t, e]), this;
                r = this.__data__ = new at(n) } return r.set(t, e), this }; var vt = $ ? O($, Object) : function() { return [] },
            bt = function(t) { return D.call(t) };

        function gt(t, e) { return !!(e = null == e ? 9007199254740991 : e) && ("number" == typeof t || l.test(t)) && t > -1 && t % 1 == 0 && t < e }

        function mt(t) { var e = t && t.constructor; return t === ("function" == typeof e && e.prototype || P) }

        function wt(t) { if (null != t) { try { return F.call(t) } catch (t) {} try { return t + "" } catch (t) {} } return "" }

        function jt(t, e) { return t === e || t != t && e != e }(q && "[object DataView]" != bt(new q(new ArrayBuffer(1))) || K && bt(new K) != a || W && "[object Promise]" != bt(W.resolve()) || G && bt(new G) != s || Z && "[object WeakMap]" != bt(new Z)) && (bt = function(t) { var e = D.call(t),
                r = "[object Object]" == e ? t.constructor : void 0,
                n = r ? wt(r) : void 0; if (n) switch (n) {
                case Y:
                    return "[object DataView]";
                case J:
                    return a;
                case X:
                    return "[object Promise]";
                case tt:
                    return s;
                case et:
                    return "[object WeakMap]" }
            return e }); var Ot = Array.isArray;

        function St(t) { return null != t && function(t) { return "number" == typeof t && t > -1 && t % 1 == 0 && t <= 9007199254740991 }(t.length) && !At(t) } var Ct = H || function() { return !1 };

        function At(t) { var e = xt(t) ? D.call(t) : ""; return e == o || e == i }

        function xt(t) { var e = typeof t; return !!t && ("object" == e || "function" == e) }

        function Pt(t) { return St(t) ? ut(t) : function(t) { if (!mt(t)) return V(t); var e = []; for (var r in Object(t)) T.call(t, r) && "constructor" != r && e.push(r); return e }(t) }
        r.exports = function(t, e) { return ft(t, !0, !0, e) } }).call(this, r(1), r(4)(t)) }, function(t, e, r) { "use strict";
    Object.defineProperty(e, "__esModule", { value: !0 }); var n = r(12),
        o = r(2);
    e.default = function(t, e, r) { void 0 === r && (r = {}); var i = n.getCoord(t),
            a = n.getCoord(e),
            s = o.degreesToRadians(a[1] - i[1]),
            u = o.degreesToRadians(a[0] - i[0]),
            c = o.degreesToRadians(i[1]),
            l = o.degreesToRadians(a[1]),
            f = Math.pow(Math.sin(s / 2), 2) + Math.pow(Math.sin(u / 2), 2) * Math.cos(c) * Math.cos(l); return o.radiansToLength(2 * Math.atan2(Math.sqrt(f), Math.sqrt(1 - f)), r.units) } }, , , function(t, e, r) { t.exports = r.p + "src/SearchBox/dist/SearchBox.css" }, function(t, e, r) { "use strict";
    Object.defineProperty(e, "__esModule", { value: !0 }); var n = r(2);
    e.getCoord = function(t) { if (!t) throw new Error("coord is required"); if (!Array.isArray(t)) { if ("Feature" === t.type && null !== t.geometry && "Point" === t.geometry.type) return t.geometry.coordinates; if ("Point" === t.type) return t.coordinates } if (Array.isArray(t) && t.length >= 2 && !Array.isArray(t[0]) && !Array.isArray(t[1])) return t; throw new Error("coord must be GeoJSON Point or an Array of numbers") }, e.getCoords = function(t) { if (Array.isArray(t)) return t; if ("Feature" === t.type) { if (null !== t.geometry) return t.geometry.coordinates } else if (t.coordinates) return t.coordinates; throw new Error("coords must be GeoJSON Feature, Geometry Object or an Array") }, e.containsNumber = function t(e) { if (e.length > 1 && n.isNumber(e[0]) && n.isNumber(e[1])) return !0; if (Array.isArray(e[0]) && e[0].length) return t(e[0]); throw new Error("coordinates must only contain numbers") }, e.geojsonType = function(t, e, r) { if (!e || !r) throw new Error("type and name required"); if (!t || t.type !== e) throw new Error("Invalid input to " + r + ": must be a " + e + ", given " + t.type) }, e.featureOf = function(t, e, r) { if (!t) throw new Error("No feature passed"); if (!r) throw new Error(".featureOf() requires a name"); if (!t || "Feature" !== t.type || !t.geometry) throw new Error("Invalid input to " + r + ", Feature with geometry required"); if (!t.geometry || t.geometry.type !== e) throw new Error("Invalid input to " + r + ": must be a " + e + ", given " + t.geometry.type) }, e.collectionOf = function(t, e, r) { if (!t) throw new Error("No featureCollection passed"); if (!r) throw new Error(".collectionOf() requires a name"); if (!t || "FeatureCollection" !== t.type) throw new Error("Invalid input to " + r + ", FeatureCollection required"); for (var n = 0, o = t.features; n < o.length; n++) { var i = o[n]; if (!i || "Feature" !== i.type || !i.geometry) throw new Error("Invalid input to " + r + ", Feature with geometry required"); if (!i.geometry || i.geometry.type !== e) throw new Error("Invalid input to " + r + ": must be a " + e + ", given " + i.geometry.type) } }, e.getGeom = function(t) { return "Feature" === t.type ? t.geometry : t }, e.getType = function(t, e) { return "FeatureCollection" === t.type ? "FeatureCollection" : "GeometryCollection" === t.type ? "GeometryCollection" : "Feature" === t.type && null !== t.geometry ? t.geometry.type : t.type } }, , function(t, e, r) { "use strict";
    r.r(e); var n = 40,
        o = 38,
        i = 46,
        a = 13,
        s = 27,
        u = 8,
        c = "FUZZY_SEARCH",
        l = "AUTOCOMPLETE",
        f = "brand",
        d = "category",
        p = '\n    <svg width="24px" height="24px" viewBox="0 0 24 24" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">\n            <path d="M10.5,4 C14.0898509,4 17,6.91014913 17,10.5 C17,11.9337106 16.5358211,13.2590065 15.7495478,14.3338028 L19.7071068,18.2928932 C20.0976311,18.6834175 20.0976311,19.3165825 19.7071068,19.7071068 C19.3165825,20.0976311 18.6834175,20.0976311 18.2928932,19.7071068 L14.3338028,15.7495478 C13.2590065,16.5358211 11.9337106,17 10.5,17 C6.91014913,17 4,14.0898509 4,10.5 C4,6.91014913 6.91014913,4 10.5,4 Z M10.5,6 C8.01471863,6 6,8.01471863 6,10.5 C6,12.9852814 8.01471863,15 10.5,15 C12.9852814,15 15,12.9852814 15,10.5 C15,8.01471863 12.9852814,6 10.5,6 Z" id="Shape"></path>\n    </svg>',
        h = '\n    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="13" viewBox="0 0 15 13">\n    <path d="M15.512418,11.5 L19.9637666,7.28870352 C20.1223719,7.13865149 20.1223719,6.89512441 19.9637666,6.74507238 L18.2390424,5.11253903 C18.0795704,4.96248699 17.8221618,4.96248699 17.6635565,5.11253903 L13.2122078,9.3238355 L8.7608592,5.11253903 C8.68545669,5.04120281 8.58145321,5.00020499 8.47398296,5.00020499 C8.36564601,5.00020499 8.26250923,5.04120281 8.18624002,5.11253903 L6.46064906,6.74507238 C6.38437985,6.81722855 6.34191176,6.91480337 6.34191176,7.01729793 C6.34191176,7.11979249 6.38437985,7.21736731 6.46064906,7.28952348 L10.9119977,11.5 L6.46064906,15.7112965 C6.38437985,15.7834526 6.34191176,15.8810275 6.34191176,15.9827021 C6.34191176,16.0851966 6.38437985,16.1827715 6.46064906,16.2549276 L8.18624002,17.887461 C8.26250923,17.9596171 8.36564601,17.999795 8.47398296,17.999795 C8.58145321,17.999795 8.68545669,17.9596171 8.7608592,17.887461 L13.2122078,13.6761645 L17.6635565,17.887461 C17.8221618,18.037513 18.0795704,18.037513 18.2390424,17.887461 L19.9637666,16.2549276 C20.1223719,16.1048756 20.1223719,15.8613485 19.9637666,15.7112965 L15.512418,11.5 Z" transform="translate(-5.544 -5)"/>\n    </svg>';

    function y(t, e) { if (!(t instanceof e)) throw new TypeError("Cannot call a class as a function") }

    function _(t, e) { for (var r = 0; r < e.length; r++) { var n = e[r];
            n.enumerable = n.enumerable || !1, n.configurable = !0, "value" in n && (n.writable = !0), Object.defineProperty(t, n.key, n) } }

    function v(t, e, r) { return e && _(t.prototype, e), r && _(t, r), t } var b = function() {
            function t(e, r, n, o, i) { var a, s, u, c = this;
                y(this, t), u = function() { c._actions.onResultSelected(c._index) }, (s = "_onSelect") in (a = this) ? Object.defineProperty(a, s, { value: u, enumerable: !0, configurable: !0, writable: !0 }) : a[s] = u, this._options = n, this._actions = o, this._result = e, this._type = r, this._index = i, this._createResultElement() } return v(t, [{ key: "getContainer", value: function() { return this._container } }, { key: "_createResultElement", value: function() { this._container = document.createElement("div"), this._container.onmousedown = function(t) { return t.preventDefault() }, this._container.onclick = this._onSelect, this._container.className = "tt-search-box-result-list", this._container.setAttribute("data-testid", "result-item"); var t = function(t) { return '<span class="tt-search-box-result-list-bold">'.concat(t, "</span>") },
                        e = function(t) { return '<span class="tt-search-box-result-list-text-content">'.concat(t, "</span>") }; switch (this._type) {
                        case c:
                            var r = this._getAddress(),
                                n = this._getPoiName(),
                                o = null;
                            r && n ? o = "".concat(t(n), " ").concat(r) : r && (o = "".concat(t(r))), o && (this._container.innerHTML = "".concat('\n    <svg width="24px" height="24px" viewBox="0 0 24 24" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">\n            <path d="M12,4 C15.3068357,4 18,6.61029768 18,9.84615385 C18,11.813391 16.4537597,14.7566138 13.3693459,18.8534202 L13.3693459,18.8534202 L12.7915956,19.6110453 C12.3912752,20.1296516 11.6087248,20.1296516 11.2084044,19.6110453 C7.73934285,15.1169529 6,11.9326175 6,9.84615385 C6,6.61029768 8.69316428,4 12,4 Z M12,6 C9.78398823,6 8,7.72909628 8,9.84615385 C8,11.1589113 9.25046927,13.5982613 11.758095,17.016979 L11.758095,17.016979 L11.999,17.344 L12.4887469,16.6780042 C14.7534108,13.5448791 15.9207088,11.2710802 15.9960961,9.97670688 L15.9960961,9.97670688 L16,9.84615385 C16,7.72909628 14.2160118,6 12,6 Z M12,8 C13.1045695,8 14,8.8954305 14,10 C14,11.1045695 13.1045695,12 12,12 C10.8954305,12 10,11.1045695 10,10 C10,8.8954305 10.8954305,8 12,8 Z" id="Combined-Shape" fill-rule="nonzero"></path>\n    </svg>', " ").concat(e("".concat(o)))), this._options.distanceFromPoint && (this._container.innerHTML += '<span class="tt-search-box-result-list-distance">'.concat(this._getDistance(), "</span>")); break;
                        case l:
                            var i = this._getSuggestionName(),
                                a = this._getSuggestionType(); if (i && a) { var s = "plaintext" === a ? "" : " ".concat(this._options.labels.suggestions[a]);
                                this._container.innerHTML = "".concat(p, " ").concat(e("".concat(t(i)).concat(s))) } } } }, { key: "_getDistance", value: function() { var t = this._result.dist,
                        e = "kilometers" === this._options.units ? "km" : "mi"; return "".concat(("km" === e ? t / 1e3 : 621371e-9 * t).toFixed(1), " ").concat(e) } }, { key: "_getSuggestionName", value: function() { return this._result.value ? this._result.value : null } }, { key: "_getSuggestionType", value: function() { return this._result.type ? this._result.type : null } }, { key: "_getPoiName", value: function() { return void 0 !== this._result.poi && void 0 !== this._result.poi.name ? this._result.poi.name : null } }, { key: "_getAddress", value: function() { if (void 0 !== this._result.address) { var t = []; return void 0 !== this._result.address.freeformAddress && t.push(this._result.address.freeformAddress), void 0 !== this._result.address.countryCodeISO3 && t.push(this._result.address.countryCodeISO3), t.join(", ") } return null } }]), t }(),
        g = function() {
            function t(e) { y(this, t), this._container = document.createElement("div"), this._container.className = "tt-search-box-result-list", this._container.innerText = e } return v(t, [{ key: "select", value: function() {} }, { key: "getContainer", value: function() { return this._container } }]), t }(),
        m = { resultListElement: function(t, e, r, n, o, i) { return new b(t, e, r, n, o, i) }, noResultsListElement: function(t) { return new g(t) } };

    function w(t, e, r) { return e in t ? Object.defineProperty(t, e, { value: r, enumerable: !0, configurable: !0, writable: !0 }) : t[e] = r, t } var j = function t(e, r, n) { var o = this;! function(t, e) { if (!(t instanceof e)) throw new TypeError("Cannot call a class as a function") }(this, t), w(this, "_setVisibility", (function(t) { t ? o._container.removeAttribute("hidden") : o._container.setAttribute("hidden", !0) })), w(this, "_convertSearchResponseToListElements", (function(t) { var e = o.store.getCurrentState().options; return t.length ? t.map((function(t, r) { return m.resultListElement(t.result, t.type, e, o.actions, r) })) : [m.noResultsListElement(e.labels.noResultsMessage)] })), w(this, "_clearResults", (function() { for (; o._container.firstChild;) o._container.removeChild(o._container.firstChild), o._container.style.height = "0px" })), w(this, "_updateHighlightedElementStyle", (function(t, e) { var r = o._container.childNodes[e];
                r && (r.classList[t]("-highlighted"), "add" === t && function(t, e, r) {
                    (!r || r && ! function(t, e) { var r = t.scrollTop,
                            n = r + t.clientHeight,
                            o = e.offsetTop,
                            i = o + e.clientHeight; return o >= r && i <= n }(t, e)) && (t.scrollTop = e.offsetTop - t.offsetTop) }(o._container, r, !0)) })), w(this, "_appendResults", (function(t) { o._clearResults(), o._convertSearchResponseToListElements(t).forEach((function(t) { o._container.appendChild(t.getContainer()) }), o), o._container.style.height = "auto" })), w(this, "update", (function() { var t = o.store.getCurrentState(),
                    e = t.resultData,
                    r = t.showResultList,
                    n = t.resultIndexPosition;
                o._setVisibility(r), e ? (o._appendResults(e), o._updateHighlightedElementStyle(-1 !== n ? "add" : "remove", n)) : o._clearResults() })), this.actions = n, this.store = r, this._container = document.createElement("div"), this._container.className = "tt-search-box-result-list-container", this._container.setAttribute("hidden", !0), e.appendChild(this._container) },
        O = r(5),
        S = r.n(O);

    function C(t, e, r) { return e in t ? Object.defineProperty(t, e, { value: r, enumerable: !0, configurable: !0, writable: !0 }) : t[e] = r, t } var A = function t(e, r, c) { var l = this;! function(t, e) { if (!(t instanceof e)) throw new TypeError("Cannot call a class as a function") }(this, t), C(this, "_create", (function() { l._inputContainer = document.createElement("div"), l._inputContainer.className = "tt-search-box-input-container", l._inputContainer.onmousedown = function(t) { return t.preventDefault() }, l._inputContainer.oninput = S()((function() { l._enterKeyPressed || l.actions.runQuery(!1, !0) }), l.store.getCurrentState().options.idleTimePress), l._inputContainer.addEventListener("input", (function(t) { return l.actions.setNewValue(t.target.value) })), l._inputContainer.onkeydown = l._onKeyDown, l._addSearchIcon(), l._addFilter(), l._addInput(), l._addClearIcon() })), C(this, "_addSearchIcon", (function() { l.store.getCurrentState().options.showSearchButton && (l._searchIcon = document.createElement("div"), l._searchIcon.innerHTML = p, l._inputContainer.appendChild(l._searchIcon)) })), C(this, "_addFilter", (function() { l._filter = document.createElement("div"), l._filter.classList.add("tt-searchbox-filter-label"), l._filter.setAttribute("style", "display: none;"), l._filterText = document.createElement("div"), l._filterText.classList.add("tt-searchbox-filter-label__text"), l._filterRemoveButton = document.createElement("div"), l._filterRemoveButton.classList.add("tt-searchbox-filter-label__close-button"), l._filterRemoveButton.innerHTML = h, l._filter.appendChild(l._filterText), l._filter.appendChild(l._filterRemoveButton), l._inputContainer.appendChild(l._filter) })), C(this, "_addInput", (function() { l._input = document.createElement("input"), l._input.className = "tt-search-box-input", l._setPlaceholder(), l._input.onmousedown = function(t) { return t.stopPropagation() }, l._input.onfocus = function() { return l.actions.updateFocus(!0) }, l._input.onblur = function() { return l.actions.updateFocus(!1) }, l._inputContainer.appendChild(l._input) })), C(this, "_indicateFilterRemoval", (function(t) { l._filter.classList.toggle("-highlighted", t), l._isDeletionConfirmed = t })), C(this, "_onBackspaceOrDelete", (function(t) { var e = l.store.getCurrentState().filter,
                r = 0 === l._input.selectionStart,
                n = l._input.selectionEnd - l._input.selectionStart,
                o = 0 !== n && n === l._input.value.length;
            r && e && (l._isDeletionConfirmed ? (l._indicateFilterRemoval(!1), l.actions.onClearFilterClick()) : !o && t && l._indicateFilterRemoval(!0)) })), C(this, "_onKeyDown", (function(t) { var e = t.keyCode || t.which,
                r = e === u || e === i,
                c = e === u;
            l._enterKeyPressed = !1, c || l._indicateFilterRemoval(!1), r && l._onBackspaceOrDelete(c), e !== o && e !== n || (t.preventDefault(), l._onArrowUpDownPress(e)), e === a && (t.preventDefault(), l._enterKeyPressed = !0, l.actions.onEnterKeyPress()), e === s && (t.preventDefault(), l.actions.onEscKeyPress()) })), C(this, "_onArrowUpDownPress", (function(t) { var e, r = l.store.getCurrentState(),
                n = r.resultIndexPosition,
                i = r.resultData,
                a = void 0 === i ? [] : i;
            t === o && -1 === n || !a.length || (e = t === o ? n - 1 < 0 ? -1 : n - 1 : n + 1 >= a.length ? 0 : n + 1, l.actions.updateOnArrowPress(e)) })), C(this, "_addClearIcon", (function() { l._closeIcon = document.createElement("div"), l._closeIcon.className = "tt-search-box-close-icon -hidden", l._closeIcon.innerHTML = h, l._inputContainer.appendChild(l._closeIcon), l._closeIcon.onclick = function() { l._indicateFilterRemoval(!1), l.actions.onClearClick() } })), C(this, "_setPlaceholder", (function() { var t = l.store.getCurrentState().options;
            l._input.setAttribute("placeholder", t.labels.placeholder) })), C(this, "_updateFilter", (function(t) { l._filterText.innerText = t.text, l._filter.setAttribute("style", "display: flex"), l._filterRemoveButton.onclick = function() { l._indicateFilterRemoval(!1), l.actions.onClearFilterClick() } })), C(this, "update", (function() { var t = l.store.getCurrentState(),
                e = t.resultData,
                r = t.value,
                n = void 0 === r ? "" : r,
                o = t.filter,
                i = t.isFocused;
            l._input.value !== n && (l._input.value = n), l._closeIcon.classList[e || n.length || o ? "remove" : "add"]("-hidden"), l._setPlaceholder(), l._input[i ? "focus" : "blur"](), l._inputContainer.classList[i ? "add" : "remove"]("-focused"), o ? l._updateFilter(o) : l._filter.setAttribute("style", "display: none;"), l._indicateFilterRemoval(l._isDeletionConfirmed) })), this.actions = c, this.store = r, this._create(), e.appendChild(this._inputContainer), this._isDeletionConfirmed = !1, this._enterKeyPressed = !1 };
    r(11);

    function x(t, e) { for (var r = 0; r < e.length; r++) { var n = e[r];
            n.enumerable = n.enumerable || !1, n.configurable = !0, "value" in n && (n.writable = !0), Object.defineProperty(t, n.key, n) } }

    function P(t) { return function(t) { if (Array.isArray(t)) return E(t) }(t) || function(t) { if ("undefined" != typeof Symbol && Symbol.iterator in Object(t)) return Array.from(t) }(t) || function(t, e) { if (!t) return; if ("string" == typeof t) return E(t, e); var r = Object.prototype.toString.call(t).slice(8, -1); "Object" === r && t.constructor && (r = t.constructor.name); if ("Map" === r || "Set" === r) return Array.from(t); if ("Arguments" === r || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(r)) return E(t, e) }(t) || function() { throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.") }() }

    function E(t, e) {
        (null == e || e > t.length) && (e = t.length); for (var r = 0, n = new Array(e); r < e; r++) n[r] = t[r]; return n }

    function R(t, e, r) { var n = t[e];
        n || (n = [], t[e] = n), n.push(r) }

    function F(t, e, r) { var n = t[e]; return n && n.forEach((function(t) { t.apply(void 0, P(r)) })), n } var T = function() {
            function t() {! function(t, e) { if (!(t instanceof e)) throw new TypeError("Cannot call a class as a function") }(this, t), this.eventToHandlersMap = {}, this.onceEventToHandlersMap = {} } var e, r, n; return e = t, (r = [{ key: "fire", value: function(t) { for (var e = arguments.length, r = new Array(e > 1 ? e - 1 : 0), n = 1; n < e; n++) r[n - 1] = arguments[n];
                    F(this.eventToHandlersMap, t, r), F(this.onceEventToHandlersMap, t, r) && delete this.onceEventToHandlersMap[t] } }, { key: "on", value: function(t, e) { R(this.eventToHandlersMap, t, e) } }, { key: "off", value: function(t) { t ? (delete this.eventToHandlersMap[t], delete this.onceEventToHandlersMap[t]) : (this.eventToHandlersMap = {}, this.onceEventToHandlersMap = {}) } }, { key: "once", value: function(t, e) { R(this.onceEventToHandlersMap, t, e) } }]) && x(e.prototype, r), n && x(e, n), t }(),
        D = r(0),
        I = r(6),
        k = r.n(I),
        M = r(7),
        L = r.n(M);

    function B(t) { return function(t) { if (Array.isArray(t)) return U(t) }(t) || function(t) { if ("undefined" != typeof Symbol && Symbol.iterator in Object(t)) return Array.from(t) }(t) || z(t) || function() { throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.") }() }

    function N(t, e) { return function(t) { if (Array.isArray(t)) return t }(t) || function(t, e) { if ("undefined" == typeof Symbol || !(Symbol.iterator in Object(t))) return; var r = [],
                n = !0,
                o = !1,
                i = void 0; try { for (var a, s = t[Symbol.iterator](); !(n = (a = s.next()).done) && (r.push(a.value), !e || r.length !== e); n = !0); } catch (t) { o = !0, i = t } finally { try { n || null == s.return || s.return() } finally { if (o) throw i } } return r }(t, e) || z(t, e) || function() { throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.") }() }

    function z(t, e) { if (t) { if ("string" == typeof t) return U(t, e); var r = Object.prototype.toString.call(t).slice(8, -1); return "Object" === r && t.constructor && (r = t.constructor.name), "Map" === r || "Set" === r ? Array.from(t) : "Arguments" === r || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(r) ? U(t, e) : void 0 } }

    function U(t, e) {
        (null == e || e > t.length) && (e = t.length); for (var r = 0, n = new Array(e); r < e; r++) n[r] = t[r]; return n }

    function $(t, e) { for (var r = 0; r < e.length; r++) { var n = e[r];
            n.enumerable = n.enumerable || !1, n.configurable = !0, "value" in n && (n.writable = !0), Object.defineProperty(t, n.key, n) } }

    function H(t, e) { var r = Object.keys(t); if (Object.getOwnPropertySymbols) { var n = Object.getOwnPropertySymbols(t);
            e && (n = n.filter((function(e) { return Object.getOwnPropertyDescriptor(t, e).enumerable }))), r.push.apply(r, n) } return r }

    function V(t) { for (var e = 1; e < arguments.length; e++) { var r = null != arguments[e] ? arguments[e] : {};
            e % 2 ? H(Object(r), !0).forEach((function(e) { q(t, e, r[e]) })) : Object.getOwnPropertyDescriptors ? Object.defineProperties(t, Object.getOwnPropertyDescriptors(r)) : H(Object(r)).forEach((function(e) { Object.defineProperty(t, e, Object.getOwnPropertyDescriptor(r, e)) })) } return t }

    function q(t, e, r) { return e in t ? Object.defineProperty(t, e, { value: r, enumerable: !0, configurable: !0, writable: !0 }) : t[e] = r, t } var K = { placeholder: "", suggestions: { brand: "Suggested brand", category: "Suggested category" }, noResultsMessage: "No results found." };

    function W(t) { return L()(t, (function(t) { if (t && t._sw && t._ne) { var e = new t.constructor; for (var r in t) Object.prototype.hasOwnProperty.call(t, r) && (t[r] instanceof Object ? e[r] = W(t[r]) : e[r] = t[r]); return e } })) } var G = function() {
            function t(e, r, n) { var o = this;! function(t, e) { if (!(t instanceof e)) throw new TypeError("Cannot call a class as a function") }(this, t), q(this, "__updater", (function(t) { return function() { for (var e = W(o._state), r = arguments.length, n = new Array(r), i = 0; i < r; i++) n[i] = arguments[i];
                        t.apply(o, n), k()(o._state, e) || o.onUpdate() } })), q(this, "_getInitialState", (function() { return { value: "", showClearButton: !1, isFocused: !1, resultData: void 0, showResultList: !1, resultIndexPosition: -1, filter: void 0, restoreData: void 0 } })), q(this, "_processOptions", (function(t) { var e = o._state && o._state.options || { idleTimePress: 200, minNumberOfCharacters: 3, searchOptions: null, autocompleteOptions: null, showSearchButton: !0, cssStyleCheck: !0, units: "kilometers" },
                        r = Object.assign({}, e, t); return r.distanceFromPoint && (r.distanceFromPoint = o._convertPointToArray(r.distanceFromPoint, "distanceFromPoint")), r.labels = function(t) { var e = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : {},
                            r = e.labels && e.labels.placeholder || e.placeholder,
                            n = e.labels && e.labels.noResultsMessage || e.noResultsMessage,
                            o = V({}, e.labels); return r && (o.placeholder = r), n && (o.noResultsMessage = n), Object.assign({}, t, o, { suggestions: Object.assign({}, t.suggestions, o.suggestions) }) }(K, t), r })), q(this, "_findAutocompleteResponseSegments", (function(t, e) { var r = {}; for (var n in t) { var o = t[n].segments; for (var i in o) { if (Object.keys(r).length === e) break; var a = o[i]; "plaintext" !== a.type && (r[a.type + a.value] = a) } } return r })), q(this, "_hasAnyResults", (function(t, e) { return !(t && t.results && t.results.length || e && e.results && e.results.length) })), q(this, "_combineSearchResponse", (function(t) { var e = N(t, 2),
                        r = e[0],
                        n = e[1];
                    o._hasAnyResults(r, n) && (o._state.combinedResults = []); var i = []; if (n && n.results) { var a = o._findAutocompleteResponseSegments(n.results, 2);
                        i.push.apply(i, B(Object.values(a).map((function(t) { return { result: t, type: l } })))) } return r && i.push.apply(i, B(r.results.map((function(t) { return { result: t, type: c } })))), i })), q(this, "_getResultText", (function(t) { switch (t.type) {
                        case c:
                            return t.result.poi ? t.result.poi.name + ", " + t.result.address.freeformAddress : t.result.address.freeformAddress;
                        case l:
                            return t.result.value } return "" })), q(this, "_getTextForFilterResult", (function(t) { var e = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : ""; if (!t.matches || !Array.isArray(t.matches.inputQuery)) return ""; var r = t.matches.inputQuery[0],
                        n = r.offset,
                        o = r.length; return e.split(e.slice(n, n + o)).join("").trim() })), q(this, "_updateInputAndFilterStateOnResultChanged", (function(t) { if (o._state.resultData && o._state.resultData.length) { var e = o._state.resultData[t],
                            r = e.result; if (e.type === l) { var n = r.type;
                            o._state.filter = V(V(V({}, n === d && { categorySet: r.id }), n === f && { brandSet: r.value }), {}, { type: n, text: o._getResultText(e) }); var i = o._state.restoreData && o._state.restoreData.value || o._state.value;
                            o._state.value = o._getTextForFilterResult(e.result, i) } else o._state.filter = void 0, o._state.value = o._getResultText(e) } })), q(this, "_emitResultEvent", (function(t, e) { if (o._state.resultData && o._state.resultData.length) { var r = o._state.resultData[t];
                        o.events[e]({ result: r.result, text: o._getResultText(r) }) } })), q(this, "_restoreInput", (function() { o._state.value = o._state.restoreData.value, o._state.filter = o._state.restoreData.filter, o.events.inputRestored() })), q(this, "getCurrentState", (function() { return W(o._state) })), q(this, "getMapCenter", (function() { return o.mapCenter })), q(this, "storeOptions", this.__updater((function(t) { o._state.options = o._processOptions(t) }))), q(this, "storeInputManually", (function(t) { o._state.resultIndexPosition = -1, o._state.resultData = void 0, o.storeInput(t) })), q(this, "storeInput", this.__updater((function(t) { o._state.value = t, t.length ? o._state.restoreData = { value: t, filter: o._state.filter } : o._state.filter || (o._state.resultIndexPosition = -1, o._state.resultData = void 0, o.events.resultsCleared()) }))), q(this, "reset", this.__updater((function() { o._state = V(V(V({}, o._state), o._getInitialState()), {}, { isFocused: !0 }), o._state.options.searchOptions && (delete o._state.options.searchOptions.categorySet, delete o._state.options.searchOptions.brandSet) }))), q(this, "updateResults", this.__updater((function(t) { o._state.resultData = o._combineSearchResponse(t), o._state.resultIndexPosition = -1 }))), q(this, "storeResults", (function(t) { o._state.showResultList = !0, o.updateResults(t) })), q(this, "updateOnUpDownPress", this.__updater((function(t) { o._state.resultIndexPosition = t, -1 !== o._state.resultIndexPosition ? (o._state.showResultList = !0, o._emitResultEvent(o._state.resultIndexPosition, "resultFocused"), o._updateInputAndFilterStateOnResultChanged(o._state.resultIndexPosition)) : o._restoreInput() }))), q(this, "onResultSelected", this.__updater((function(t) { o._emitResultEvent(t, "resultSelected"), o._updateInputAndFilterStateOnResultChanged(t), o._state.resultData = void 0, o._state.showResultList = !1, o._state.resultIndexPosition = -1, o._state.restoreData = void 0 }))), q(this, "onRemoveFilter", this.__updater((function() { o._state.filter = void 0, o._state.resultIndexPosition = -1, o._state.restoreData && (o._state.restoreData.filter = void 0), o._state.value.length && "" !== o._state.value ? (o._state.restoreData = o._state.restoreData || {}, o._state.restoreData.value = o._state.value) : (o._state.resultData = void 0, o._state.restoreData = void 0, o.events.resultsCleared()), o._state.options.searchOptions && (delete o._state.options.searchOptions.categorySet, delete o._state.options.searchOptions.brandSet) }))), q(this, "onEnterKeyPress", this.__updater((function() { o._state.showResultList = !1, o._state.resultIndexPosition = -1 }))), q(this, "onEscKeyPress", this.__updater((function() {!1 === o._state.showResultList && (o._state.isFocused = !1), o._state.showResultList = !1 }))), q(this, "updateFocus", this.__updater((function(t) { o._state.isFocused = t, o._state.resultData && (o._state.showResultList = t) }))), q(this, "setNewFilter", this.__updater((function(t) { o._state.filter = t, o._state.restoreData = V(V({}, o._state.restoreData && o._state.restoreData || {}), {}, { filter: o._state.filter }), o._state.options.searchOptions && (o._state.options.searchOptions.categorySet = t.categorySet || void 0, o._state.options.searchOptions.brandSet = t.brandSet || void 0) }))), this.onUpdate = e, this.events = n, this._state = V(V({}, this._getInitialState()), {}, { options: this._processOptions(r) }) } var e, r, n; return e = t, (r = [{ key: "_convertPointToArray", value: function(t, e) { var r; if (Array.isArray(t)) r = t;
                    else if ("string" == typeof t) r = t.split(",");
                    else { var n = t.latitude || t.lat;
                        r = [t.longitude || t.lng || t.lon, n] } if (2 !== r.length || !r[0] || !r[1]) throw new Error("Searchbox: ".concat(e, " is not valid.")); return r } }, { key: "setMapCenter", value: function(t) { this.mapCenter = t } }]) && $(e.prototype, r), n && $(e, n), t }(),
        Z = r(8),
        Q = r.n(Z),
        Y = r(2);

    function J(t, e, r, n, o, i, a) { try { var s = t[i](a),
                u = s.value } catch (t) { return void r(t) }
        s.done ? e(u) : Promise.resolve(u).then(n, o) }

    function X(t) { return function() { var e = this,
                r = arguments; return new Promise((function(n, o) { var i = t.apply(e, r);

                function a(t) { J(i, n, o, a, s, "next", t) }

                function s(t) { J(i, n, o, a, s, "throw", t) }
                a(void 0) })) } }

    function tt(t, e) { return function(t) { if (Array.isArray(t)) return t }(t) || function(t, e) { if ("undefined" == typeof Symbol || !(Symbol.iterator in Object(t))) return; var r = [],
                n = !0,
                o = !1,
                i = void 0; try { for (var a, s = t[Symbol.iterator](); !(n = (a = s.next()).done) && (r.push(a.value), !e || r.length !== e); n = !0); } catch (t) { o = !0, i = t } finally { try { n || null == s.return || s.return() } finally { if (o) throw i } } return r }(t, e) || function(t, e) { if (!t) return; if ("string" == typeof t) return et(t, e); var r = Object.prototype.toString.call(t).slice(8, -1); "Object" === r && t.constructor && (r = t.constructor.name); if ("Map" === r || "Set" === r) return Array.from(t); if ("Arguments" === r || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(r)) return et(t, e) }(t, e) || function() { throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.") }() }

    function et(t, e) {
        (null == e || e > t.length) && (e = t.length); for (var r = 0, n = new Array(e); r < e; r++) n[r] = t[r]; return n }

    function rt(t, e) { var r = Object.keys(t); if (Object.getOwnPropertySymbols) { var n = Object.getOwnPropertySymbols(t);
            e && (n = n.filter((function(e) { return Object.getOwnPropertyDescriptor(t, e).enumerable }))), r.push.apply(r, n) } return r }

    function nt(t) { for (var e = 1; e < arguments.length; e++) { var r = null != arguments[e] ? arguments[e] : {};
            e % 2 ? rt(Object(r), !0).forEach((function(e) { ot(t, e, r[e]) })) : Object.getOwnPropertyDescriptors ? Object.defineProperties(t, Object.getOwnPropertyDescriptors(r)) : rt(Object(r)).forEach((function(e) { Object.defineProperty(t, e, Object.getOwnPropertyDescriptor(r, e)) })) } return t }

    function ot(t, e, r) { return e in t ? Object.defineProperty(t, e, { value: r, enumerable: !0, configurable: !0, writable: !0 }) : t[e] = r, t }

    function it(t, e, r) {
        function n(t) { return t ? "submit" : "input" }

        function o(r, n, o) { var i = t.getCurrentState().filter,
                a = t.getMapCenter(),
                s = Boolean(i),
                u = {}; if (r.searchOptions) { var c = nt(nt({}, s && i.categorySet && { categorySet: i.categorySet }), s && i.brandSet && { brandSet: i.brandSet }),
                    l = nt(nt(nt({ query: n, typeahead: s || o }, a && { center: a }), r.searchOptions), c);
                u.fuzzySearch = e.fuzzySearch(l) } if (r.autocompleteOptions && !s) { var f = nt(nt({ query: n }, a && { center: a }), r.autocompleteOptions);
                u.autocomplete = e.autocomplete(f) } return function(t) { return Promise.all(Object.entries(t).map((function(t) { var e = tt(t, 2),
                        r = e[0]; return e[1].then((function(t) { return { name: r, value: t, resolved: !0 } })).catch((function(t) { return { name: r, value: t, rejected: !0 } })) }))).then((function(t) { return { results: t.filter((function(t) { return t.resolved })).reduce((function(t, e) { var r = e.name,
                                n = e.value; return nt(nt({}, t), {}, ot({}, r, n)) }), {}), errors: t.filter((function(t) { return t.rejected })).reduce((function(t, e) { var r = e.name,
                                n = e.value; return nt(nt({}, t), {}, ot({}, r, n)) }), {}) } })) }(u) }

        function i(t, e) { return t.map((function(t) { var r = nt({}, t),
                    n = r.position,
                    o = n.lng,
                    i = n.lat,
                    a = Q()(Object(Y.point)([o, i]), Object(Y.point)(e), { units: "kilometers" }); return r.dist = 1e3 * a, r })) } var a, s = X(regeneratorRuntime.mark((function e() { var s, u, c, l, f, d, p, h, y, _, v, b, g, m, w, j, O = arguments; return regeneratorRuntime.wrap((function(e) { for (;;) switch (e.prev = e.next) {
                    case 0:
                        if (s = O.length > 0 && void 0 !== O[0] ? O[0] : {}, u = s.triggeredBySubmit, c = void 0 !== u && u, l = s.useTypeahead, f = void 0 === l || l, d = Date.now(), a = d, p = t.getCurrentState(), h = p.value, y = p.options, _ = p.filter, !(h.length < y.minNumberOfCharacters && !_ || 0 === h.trim().length && !_)) { e.next = 6; break } return e.abrupt("return", void 0);
                    case 6:
                        return e.next = 8, o(y, h, f);
                    case 8:
                        if (v = e.sent, b = v.results, g = v.errors, m = b.autocomplete, w = b.fuzzySearch, y.filterSearchResults && w && w.results && (w.results = w.results.filter(y.filterSearchResults)), y.distanceFromPoint && (w.results = i(w.results, y.distanceFromPoint)), d !== a) { e.next = 19; break } return j = n(c), r.resultsFound({ triggeredBy: j, results: b, errors: g }), c && r.loadingFinished(j), e.abrupt("return", [w, m]);
                    case 19:
                        return e.abrupt("return", void 0);
                    case 20:
                    case "end":
                        return e.stop() } }), e) })));

        function u(e, o) { var i = t.getCurrentState().options,
                a = o || i.searchOptions && i.searchOptions.typeahead || !1,
                u = n(e);
            r.loadingStarted(u), s({ useTypeahead: a, triggeredBySubmit: e }).then((function(n) { n && (e ? t.updateResults(n) : t.storeResults(n)), e || r.loadingFinished(u) })) } return { onClearClick: function() { t.reset(), r.resultsCleared() }, onResultSelected: function(e) { t.onResultSelected(e) }, onClearFilterClick: function() { t.onRemoveFilter(), t.updateFocus(!0), u(!1, !0) }, onEnterKeyPress: function() { var e = t.getCurrentState().resultIndexPosition; - 1 !== e ? t.onResultSelected(e) : (t.onEnterKeyPress(), u(!0, !1)) }, updateOnArrowPress: function(e) { t.updateOnUpDownPress(e) }, onEscKeyPress: function() { t.onEscKeyPress() }, updateFocus: function(e) { t.updateFocus(e) }, runQuery: u, setNewValue: function(e) { t.storeInput(e) }, setNewValueManually: function(e) { t.storeInputManually(e) }, setNewFilter: function(e) { t.setNewFilter(e) }, setMapCenter: function(e) { t.setMapCenter(e) }, removeFilter: function() { t.onRemoveFilter() } } } var at = "tomtom.searchbox.resultscleared",
        st = "tomtom.searchbox.resultsfound",
        ut = "tomtom.searchbox.resultselected",
        ct = "tomtom.searchbox.resultfocused",
        lt = "tomtom.searchbox.inputrestored",
        ft = "tomtom.searchbox.loadingstarted",
        dt = "tomtom.searchbox.loadingfinished",
        pt = function t(e) {! function(t, e) { if (!(t instanceof e)) throw new TypeError("Cannot call a class as a function") }(this, t), this.data = e },
        ht = function(t) { return new pt(t) };

    function yt(t, e) { var r = Object.keys(t); if (Object.getOwnPropertySymbols) { var n = Object.getOwnPropertySymbols(t);
            e && (n = n.filter((function(e) { return Object.getOwnPropertyDescriptor(t, e).enumerable }))), r.push.apply(r, n) } return r }

    function _t(t) { for (var e = 1; e < arguments.length; e++) { var r = null != arguments[e] ? arguments[e] : {};
            e % 2 ? yt(Object(r), !0).forEach((function(e) { vt(t, e, r[e]) })) : Object.getOwnPropertyDescriptors ? Object.defineProperties(t, Object.getOwnPropertyDescriptors(r)) : yt(Object(r)).forEach((function(e) { Object.defineProperty(t, e, Object.getOwnPropertyDescriptor(r, e)) })) } return t }

    function vt(t, e, r) { return e in t ? Object.defineProperty(t, e, { value: r, enumerable: !0, configurable: !0, writable: !0 }) : t[e] = r, t }

    function bt(t) { return (bt = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function(t) { return typeof t } : function(t) { return t && "function" == typeof Symbol && t.constructor === Symbol && t !== Symbol.prototype ? "symbol" : typeof t })(t) }

    function gt(t) { return (gt = Object.setPrototypeOf ? Object.getPrototypeOf : function(t) { return t.__proto__ || Object.getPrototypeOf(t) })(t) }

    function mt(t, e) { return (mt = Object.setPrototypeOf || function(t, e) { return t.__proto__ = e, t })(t, e) }

    function wt(t) { if (void 0 === t) throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); return t }

    function jt(t, e, r) { return e in t ? Object.defineProperty(t, e, { value: r, enumerable: !0, configurable: !0, writable: !0 }) : t[e] = r, t } var Ot = function(t) {
        function e(t, r) { var n;! function(t, e) { if (!(t instanceof e)) throw new TypeError("Cannot call a class as a function") }(this, e), n = function(t, e) { return !e || "object" !== bt(e) && "function" != typeof e ? wt(t) : e }(this, gt(e).call(this)), jt(wt(wt(n)), "_onStoreChange", (function() { n._inputWrapper.update(), n._resultList.update() })), jt(wt(wt(n)), "_createSearchBoxContainer", (function() { return n._container = document.createElement("div"), n._container.className = "tt-search-box", n._inputWrapper = new A(n._container, n.store, n.userActions), n._resultList = new j(n._container, n.store, n.userActions), n._container })), jt(wt(wt(n)), "getOptions", (function() { return n.store.getCurrentState().options })), jt(wt(wt(n)), "updateOptions", (function(t) { n.store.storeOptions(t) })), jt(wt(wt(n)), "getSearchBoxHTML", (function() { return n._container })), jt(wt(wt(n)), "onAdd", (function(t) { return n.store.getCurrentState().options.cssStyleCheck && Object(D.a)(["SearchBox.css"]), n._map = t, n._map.on("move", (function() { return n.userActions.setMapCenter(t.getCenter()) })), n._container.classList.add("mapboxgl-ctrl", "tt-ctrl"), n._container })), jt(wt(wt(n)), "onRemove", (function() { n._container.parentNode.removeChild(n._container), n._map = void 0 })), jt(wt(wt(n)), "query", (function() { n.userActions.runQuery(!0) })), jt(wt(wt(n)), "setValue", (function(t) { n.userActions.setNewValueManually(t) })), jt(wt(wt(n)), "getValue", (function() { return n.store.getCurrentState().value || "" })), jt(wt(wt(n)), "setFilter", (function(t) { var e, r = t.value,
                    o = t.type,
                    i = n.store.getCurrentState().options; if (!o || !r) throw new Error("setFilter: Invalid filterOptions format passed. Expected object properties are[type] and [value]"); if (!i.searchOptions) throw new Error("setFilter: You can not use setFilter without setting searchOptions."); if ("category" === o) e = { categorySet: r.id, text: r.name, type: "category" };
                else { if ("brand" !== o) throw new Error("setFilter: Filter type is expected to be 'category' or 'brand'.");
                    e = { brandSet: r.name, text: r.name, type: "category" } }
                n.userActions.setNewFilter(e) })), jt(wt(wt(n)), "removeFilter", (function() { n.store.getCurrentState().options.searchOptions && n.userActions.removeFilter() })); var o, i = (o = wt(wt(n)), { resultsFound: function() { var t = arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : {},
                        e = t.triggeredBy,
                        r = t.results,
                        n = t.errors;
                    o.fire(st, ht(_t(_t({ metadata: { triggeredBy: e } }, Object.keys(r).length > 0 && { results: r }), Object.keys(n).length > 0 && { errors: n }))) }, resultsCleared: function() { o.fire(at) }, resultSelected: function(t) { var e = t.result,
                        r = t.text;
                    o.fire(ut, ht({ result: e, text: r })) }, resultFocused: function(t) { var e = t.result,
                        r = t.text;
                    o.fire(ct, ht({ result: e, text: r })) }, inputRestored: function() { o.fire(lt) }, loadingStarted: function(t) { o.fire(ft, ht({ metadata: { triggeredBy: t } })) }, loadingFinished: function(t) { o.fire(dt, ht({ metadata: { triggeredBy: t } })) } }); return n.store = new G(n._onStoreChange, r, i), n.userActions = it(n.store, t, i), n._createSearchBoxContainer(), n } return function(t, e) { if ("function" != typeof e && null !== e) throw new TypeError("Super expression must either be null or a function");
            t.prototype = Object.create(e && e.prototype, { constructor: { value: t, writable: !0, configurable: !0 } }), e && mt(t, e) }(e, t), e }(T);
    window.tt = window.tt || {}, window.tt.plugins = window.tt.plugins || {}, window.tt.plugins.SearchBox = Ot }]);