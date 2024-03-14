var _e = (i, t, e) => new Promise((s, n) => {
  var r = (a) => {
    try {
      l(e.next(a));
    } catch (h) {
      n(h);
    }
  }, o = (a) => {
    try {
      l(e.throw(a));
    } catch (h) {
      n(h);
    }
  }, l = (a) => a.done ? s(a.value) : Promise.resolve(a.value).then(r, o);
  l((e = e.apply(i, t)).next());
});
function za(i, t) {
  const e = /* @__PURE__ */ Object.create(null), s = i.split(",");
  for (let n = 0; n < s.length; n++)
    e[s[n]] = !0;
  return t ? (n) => !!e[n.toLowerCase()] : (n) => !!e[n];
}
const Km = "itemscope,allowfullscreen,formnovalidate,ismap,nomodule,novalidate,readonly", Xm = /* @__PURE__ */ za(Km);
function Uf(i) {
  return !!i || i === "";
}
function Wa(i) {
  if (st(i)) {
    const t = {};
    for (let e = 0; e < i.length; e++) {
      const s = i[e], n = Dt(s) ? Ym(s) : Wa(s);
      if (n)
        for (const r in n)
          t[r] = n[r];
    }
    return t;
  } else {
    if (Dt(i))
      return i;
    if (Mt(i))
      return i;
  }
}
const Gm = /;(?![^(]*\))/g, Jm = /:(.+)/;
function Ym(i) {
  const t = {};
  return i.split(Gm).forEach((e) => {
    if (e) {
      const s = e.split(Jm);
      s.length > 1 && (t[s[0].trim()] = s[1].trim());
    }
  }), t;
}
function $a(i) {
  let t = "";
  if (Dt(i))
    t = i;
  else if (st(i))
    for (let e = 0; e < i.length; e++) {
      const s = $a(i[e]);
      s && (t += s + " ");
    }
  else if (Mt(i))
    for (const e in i)
      i[e] && (t += e + " ");
  return t.trim();
}
const Xs = (i) => Dt(i) ? i : i == null ? "" : st(i) || Mt(i) && (i.toString === Kf || !nt(i.toString)) ? JSON.stringify(i, jf, 2) : String(i), jf = (i, t) => t && t.__v_isRef ? jf(i, t.value) : as(t) ? {
  [`Map(${t.size})`]: [...t.entries()].reduce((e, [s, n]) => (e[`${s} =>`] = n, e), {})
} : Hf(t) ? {
  [`Set(${t.size})`]: [...t.values()]
} : Mt(t) && !st(t) && !Xf(t) ? String(t) : t, mt = {}, ls = [], Te = () => {
}, Zm = () => !1, t_ = /^on[^a-z]/, no = (i) => t_.test(i), Fa = (i) => i.startsWith("onUpdate:"), Zt = Object.assign, Va = (i, t) => {
  const e = i.indexOf(t);
  e > -1 && i.splice(e, 1);
}, e_ = Object.prototype.hasOwnProperty, lt = (i, t) => e_.call(i, t), st = Array.isArray, as = (i) => ro(i) === "[object Map]", Hf = (i) => ro(i) === "[object Set]", nt = (i) => typeof i == "function", Dt = (i) => typeof i == "string", Ua = (i) => typeof i == "symbol", Mt = (i) => i !== null && typeof i == "object", qf = (i) => Mt(i) && nt(i.then) && nt(i.catch), Kf = Object.prototype.toString, ro = (i) => Kf.call(i), i_ = (i) => ro(i).slice(8, -1), Xf = (i) => ro(i) === "[object Object]", ja = (i) => Dt(i) && i !== "NaN" && i[0] !== "-" && "" + parseInt(i, 10) === i, or = /* @__PURE__ */ za(
  ",key,ref,ref_for,ref_key,onVnodeBeforeMount,onVnodeMounted,onVnodeBeforeUpdate,onVnodeUpdated,onVnodeBeforeUnmount,onVnodeUnmounted"
), oo = (i) => {
  const t = /* @__PURE__ */ Object.create(null);
  return (e) => t[e] || (t[e] = i(e));
}, s_ = /-(\w)/g, Ue = oo((i) => i.replace(s_, (t, e) => e ? e.toUpperCase() : "")), n_ = /\B([A-Z])/g, Os = oo((i) => i.replace(n_, "-$1").toLowerCase()), lo = oo((i) => i.charAt(0).toUpperCase() + i.slice(1)), Io = oo((i) => i ? `on${lo(i)}` : ""), on = (i, t) => !Object.is(i, t), Qo = (i, t) => {
  for (let e = 0; e < i.length; e++)
    i[e](t);
}, xr = (i, t, e) => {
  Object.defineProperty(i, t, {
    configurable: !0,
    enumerable: !1,
    value: e
  });
}, r_ = (i) => {
  const t = parseFloat(i);
  return isNaN(t) ? i : t;
};
let Vh;
const o_ = () => Vh || (Vh = typeof globalThis != "undefined" ? globalThis : typeof self != "undefined" ? self : typeof window != "undefined" ? window : typeof global != "undefined" ? global : {});
let Ne;
class l_ {
  constructor(t = !1) {
    this.active = !0, this.effects = [], this.cleanups = [], !t && Ne && (this.parent = Ne, this.index = (Ne.scopes || (Ne.scopes = [])).push(this) - 1);
  }
  run(t) {
    if (this.active) {
      const e = Ne;
      try {
        return Ne = this, t();
      } finally {
        Ne = e;
      }
    }
  }
  on() {
    Ne = this;
  }
  off() {
    Ne = this.parent;
  }
  stop(t) {
    if (this.active) {
      let e, s;
      for (e = 0, s = this.effects.length; e < s; e++)
        this.effects[e].stop();
      for (e = 0, s = this.cleanups.length; e < s; e++)
        this.cleanups[e]();
      if (this.scopes)
        for (e = 0, s = this.scopes.length; e < s; e++)
          this.scopes[e].stop(!0);
      if (this.parent && !t) {
        const n = this.parent.scopes.pop();
        n && n !== this && (this.parent.scopes[this.index] = n, n.index = this.index);
      }
      this.active = !1;
    }
  }
}
function a_(i, t = Ne) {
  t && t.active && t.effects.push(i);
}
const Ha = (i) => {
  const t = new Set(i);
  return t.w = 0, t.n = 0, t;
}, Gf = (i) => (i.w & ki) > 0, Jf = (i) => (i.n & ki) > 0, h_ = ({ deps: i }) => {
  if (i.length)
    for (let t = 0; t < i.length; t++)
      i[t].w |= ki;
}, u_ = (i) => {
  const { deps: t } = i;
  if (t.length) {
    let e = 0;
    for (let s = 0; s < t.length; s++) {
      const n = t[s];
      Gf(n) && !Jf(n) ? n.delete(i) : t[e++] = n, n.w &= ~ki, n.n &= ~ki;
    }
    t.length = e;
  }
}, Dl = /* @__PURE__ */ new WeakMap();
let Ws = 0, ki = 1;
const Bl = 30;
let Oe;
const zi = Symbol(""), Nl = Symbol("");
class qa {
  constructor(t, e = null, s) {
    this.fn = t, this.scheduler = e, this.active = !0, this.deps = [], this.parent = void 0, a_(this, s);
  }
  run() {
    if (!this.active)
      return this.fn();
    let t = Oe, e = yi;
    for (; t; ) {
      if (t === this)
        return;
      t = t.parent;
    }
    try {
      return this.parent = Oe, Oe = this, yi = !0, ki = 1 << ++Ws, Ws <= Bl ? h_(this) : Uh(this), this.fn();
    } finally {
      Ws <= Bl && u_(this), ki = 1 << --Ws, Oe = this.parent, yi = e, this.parent = void 0, this.deferStop && this.stop();
    }
  }
  stop() {
    Oe === this ? this.deferStop = !0 : this.active && (Uh(this), this.onStop && this.onStop(), this.active = !1);
  }
}
function Uh(i) {
  const { deps: t } = i;
  if (t.length) {
    for (let e = 0; e < t.length; e++)
      t[e].delete(i);
    t.length = 0;
  }
}
let yi = !0;
const Yf = [];
function Ss() {
  Yf.push(yi), yi = !1;
}
function Cs() {
  const i = Yf.pop();
  yi = i === void 0 ? !0 : i;
}
function me(i, t, e) {
  if (yi && Oe) {
    let s = Dl.get(i);
    s || Dl.set(i, s = /* @__PURE__ */ new Map());
    let n = s.get(e);
    n || s.set(e, n = Ha()), Zf(n);
  }
}
function Zf(i, t) {
  let e = !1;
  Ws <= Bl ? Jf(i) || (i.n |= ki, e = !Gf(i)) : e = !i.has(Oe), e && (i.add(Oe), Oe.deps.push(i));
}
function ti(i, t, e, s, n, r) {
  const o = Dl.get(i);
  if (!o)
    return;
  let l = [];
  if (t === "clear")
    l = [...o.values()];
  else if (e === "length" && st(i))
    o.forEach((a, h) => {
      (h === "length" || h >= s) && l.push(a);
    });
  else
    switch (e !== void 0 && l.push(o.get(e)), t) {
      case "add":
        st(i) ? ja(e) && l.push(o.get("length")) : (l.push(o.get(zi)), as(i) && l.push(o.get(Nl)));
        break;
      case "delete":
        st(i) || (l.push(o.get(zi)), as(i) && l.push(o.get(Nl)));
        break;
      case "set":
        as(i) && l.push(o.get(zi));
        break;
    }
  if (l.length === 1)
    l[0] && Ll(l[0]);
  else {
    const a = [];
    for (const h of l)
      h && a.push(...h);
    Ll(Ha(a));
  }
}
function Ll(i, t) {
  const e = st(i) ? i : [...i];
  for (const s of e)
    s.computed && jh(s);
  for (const s of e)
    s.computed || jh(s);
}
function jh(i, t) {
  (i !== Oe || i.allowRecurse) && (i.scheduler ? i.scheduler() : i.run());
}
const c_ = /* @__PURE__ */ za("__proto__,__v_isRef,__isVue"), td = new Set(
  /* @__PURE__ */ Object.getOwnPropertyNames(Symbol).filter((i) => i !== "arguments" && i !== "caller").map((i) => Symbol[i]).filter(Ua)
), f_ = /* @__PURE__ */ Ka(), d_ = /* @__PURE__ */ Ka(!1, !0), p_ = /* @__PURE__ */ Ka(!0), Hh = /* @__PURE__ */ g_();
function g_() {
  const i = {};
  return ["includes", "indexOf", "lastIndexOf"].forEach((t) => {
    i[t] = function(...e) {
      const s = ut(this);
      for (let r = 0, o = this.length; r < o; r++)
        me(s, "get", r + "");
      const n = s[t](...e);
      return n === -1 || n === !1 ? s[t](...e.map(ut)) : n;
    };
  }), ["push", "pop", "shift", "unshift", "splice"].forEach((t) => {
    i[t] = function(...e) {
      Ss();
      const s = ut(this)[t].apply(this, e);
      return Cs(), s;
    };
  }), i;
}
function Ka(i = !1, t = !1) {
  return function(s, n, r) {
    if (n === "__v_isReactive")
      return !i;
    if (n === "__v_isReadonly")
      return i;
    if (n === "__v_isShallow")
      return t;
    if (n === "__v_raw" && r === (i ? t ? M_ : rd : t ? nd : sd).get(s))
      return s;
    const o = st(s);
    if (!i && o && lt(Hh, n))
      return Reflect.get(Hh, n, r);
    const l = Reflect.get(s, n, r);
    return (Ua(n) ? td.has(n) : c_(n)) || (i || me(s, "get", n), t) ? l : qt(l) ? o && ja(n) ? l : l.value : Mt(l) ? i ? od(l) : Ja(l) : l;
  };
}
const m_ = /* @__PURE__ */ ed(), __ = /* @__PURE__ */ ed(!0);
function ed(i = !1) {
  return function(e, s, n, r) {
    let o = e[s];
    if (ln(o) && qt(o) && !qt(n))
      return !1;
    if (!i && !ln(n) && (Il(n) || (n = ut(n), o = ut(o)), !st(e) && qt(o) && !qt(n)))
      return o.value = n, !0;
    const l = st(e) && ja(s) ? Number(s) < e.length : lt(e, s), a = Reflect.set(e, s, n, r);
    return e === ut(r) && (l ? on(n, o) && ti(e, "set", s, n) : ti(e, "add", s, n)), a;
  };
}
function b_(i, t) {
  const e = lt(i, t);
  i[t];
  const s = Reflect.deleteProperty(i, t);
  return s && e && ti(i, "delete", t, void 0), s;
}
function y_(i, t) {
  const e = Reflect.has(i, t);
  return (!Ua(t) || !td.has(t)) && me(i, "has", t), e;
}
function w_(i) {
  return me(i, "iterate", st(i) ? "length" : zi), Reflect.ownKeys(i);
}
const id = {
  get: f_,
  set: m_,
  deleteProperty: b_,
  has: y_,
  ownKeys: w_
}, v_ = {
  get: p_,
  set(i, t) {
    return !0;
  },
  deleteProperty(i, t) {
    return !0;
  }
}, x_ = /* @__PURE__ */ Zt({}, id, {
  get: d_,
  set: __
}), Xa = (i) => i, ao = (i) => Reflect.getPrototypeOf(i);
function Mn(i, t, e = !1, s = !1) {
  i = i.__v_raw;
  const n = ut(i), r = ut(t);
  e || (t !== r && me(n, "get", t), me(n, "get", r));
  const { has: o } = ao(n), l = s ? Xa : e ? Za : an;
  if (o.call(n, t))
    return l(i.get(t));
  if (o.call(n, r))
    return l(i.get(r));
  i !== n && i.get(t);
}
function Rn(i, t = !1) {
  const e = this.__v_raw, s = ut(e), n = ut(i);
  return t || (i !== n && me(s, "has", i), me(s, "has", n)), i === n ? e.has(i) : e.has(i) || e.has(n);
}
function Dn(i, t = !1) {
  return i = i.__v_raw, !t && me(ut(i), "iterate", zi), Reflect.get(i, "size", i);
}
function qh(i) {
  i = ut(i);
  const t = ut(this);
  return ao(t).has.call(t, i) || (t.add(i), ti(t, "add", i, i)), this;
}
function Kh(i, t) {
  t = ut(t);
  const e = ut(this), { has: s, get: n } = ao(e);
  let r = s.call(e, i);
  r || (i = ut(i), r = s.call(e, i));
  const o = n.call(e, i);
  return e.set(i, t), r ? on(t, o) && ti(e, "set", i, t) : ti(e, "add", i, t), this;
}
function Xh(i) {
  const t = ut(this), { has: e, get: s } = ao(t);
  let n = e.call(t, i);
  n || (i = ut(i), n = e.call(t, i)), s && s.call(t, i);
  const r = t.delete(i);
  return n && ti(t, "delete", i, void 0), r;
}
function Gh() {
  const i = ut(this), t = i.size !== 0, e = i.clear();
  return t && ti(i, "clear", void 0, void 0), e;
}
function Bn(i, t) {
  return function(s, n) {
    const r = this, o = r.__v_raw, l = ut(o), a = t ? Xa : i ? Za : an;
    return !i && me(l, "iterate", zi), o.forEach((h, u) => s.call(n, a(h), a(u), r));
  };
}
function Nn(i, t, e) {
  return function(...s) {
    const n = this.__v_raw, r = ut(n), o = as(r), l = i === "entries" || i === Symbol.iterator && o, a = i === "keys" && o, h = n[i](...s), u = e ? Xa : t ? Za : an;
    return !t && me(r, "iterate", a ? Nl : zi), {
      next() {
        const { value: c, done: f } = h.next();
        return f ? { value: c, done: f } : {
          value: l ? [u(c[0]), u(c[1])] : u(c),
          done: f
        };
      },
      [Symbol.iterator]() {
        return this;
      }
    };
  };
}
function oi(i) {
  return function(...t) {
    return i === "delete" ? !1 : this;
  };
}
function k_() {
  const i = {
    get(r) {
      return Mn(this, r);
    },
    get size() {
      return Dn(this);
    },
    has: Rn,
    add: qh,
    set: Kh,
    delete: Xh,
    clear: Gh,
    forEach: Bn(!1, !1)
  }, t = {
    get(r) {
      return Mn(this, r, !1, !0);
    },
    get size() {
      return Dn(this);
    },
    has: Rn,
    add: qh,
    set: Kh,
    delete: Xh,
    clear: Gh,
    forEach: Bn(!1, !0)
  }, e = {
    get(r) {
      return Mn(this, r, !0);
    },
    get size() {
      return Dn(this, !0);
    },
    has(r) {
      return Rn.call(this, r, !0);
    },
    add: oi("add"),
    set: oi("set"),
    delete: oi("delete"),
    clear: oi("clear"),
    forEach: Bn(!0, !1)
  }, s = {
    get(r) {
      return Mn(this, r, !0, !0);
    },
    get size() {
      return Dn(this, !0);
    },
    has(r) {
      return Rn.call(this, r, !0);
    },
    add: oi("add"),
    set: oi("set"),
    delete: oi("delete"),
    clear: oi("clear"),
    forEach: Bn(!0, !0)
  };
  return ["keys", "values", "entries", Symbol.iterator].forEach((r) => {
    i[r] = Nn(r, !1, !1), e[r] = Nn(r, !0, !1), t[r] = Nn(r, !1, !0), s[r] = Nn(r, !0, !0);
  }), [
    i,
    e,
    t,
    s
  ];
}
const [O_, S_, C_, A_] = /* @__PURE__ */ k_();
function Ga(i, t) {
  const e = t ? i ? A_ : C_ : i ? S_ : O_;
  return (s, n, r) => n === "__v_isReactive" ? !i : n === "__v_isReadonly" ? i : n === "__v_raw" ? s : Reflect.get(lt(e, n) && n in s ? e : s, n, r);
}
const T_ = {
  get: /* @__PURE__ */ Ga(!1, !1)
}, P_ = {
  get: /* @__PURE__ */ Ga(!1, !0)
}, E_ = {
  get: /* @__PURE__ */ Ga(!0, !1)
}, sd = /* @__PURE__ */ new WeakMap(), nd = /* @__PURE__ */ new WeakMap(), rd = /* @__PURE__ */ new WeakMap(), M_ = /* @__PURE__ */ new WeakMap();
function R_(i) {
  switch (i) {
    case "Object":
    case "Array":
      return 1;
    case "Map":
    case "Set":
    case "WeakMap":
    case "WeakSet":
      return 2;
    default:
      return 0;
  }
}
function D_(i) {
  return i.__v_skip || !Object.isExtensible(i) ? 0 : R_(i_(i));
}
function Ja(i) {
  return ln(i) ? i : Ya(i, !1, id, T_, sd);
}
function B_(i) {
  return Ya(i, !1, x_, P_, nd);
}
function od(i) {
  return Ya(i, !0, v_, E_, rd);
}
function Ya(i, t, e, s, n) {
  if (!Mt(i) || i.__v_raw && !(t && i.__v_isReactive))
    return i;
  const r = n.get(i);
  if (r)
    return r;
  const o = D_(i);
  if (o === 0)
    return i;
  const l = new Proxy(i, o === 2 ? s : e);
  return n.set(i, l), l;
}
function hs(i) {
  return ln(i) ? hs(i.__v_raw) : !!(i && i.__v_isReactive);
}
function ln(i) {
  return !!(i && i.__v_isReadonly);
}
function Il(i) {
  return !!(i && i.__v_isShallow);
}
function ld(i) {
  return hs(i) || ln(i);
}
function ut(i) {
  const t = i && i.__v_raw;
  return t ? ut(t) : i;
}
function ad(i) {
  return xr(i, "__v_skip", !0), i;
}
const an = (i) => Mt(i) ? Ja(i) : i, Za = (i) => Mt(i) ? od(i) : i;
function hd(i) {
  yi && Oe && (i = ut(i), Zf(i.dep || (i.dep = Ha())));
}
function ud(i, t) {
  i = ut(i), i.dep && Ll(i.dep);
}
function qt(i) {
  return !!(i && i.__v_isRef === !0);
}
function zo(i) {
  return N_(i, !0);
}
function N_(i, t) {
  return qt(i) ? i : new L_(i, t);
}
class L_ {
  constructor(t, e) {
    this.__v_isShallow = e, this.dep = void 0, this.__v_isRef = !0, this._rawValue = e ? t : ut(t), this._value = e ? t : an(t);
  }
  get value() {
    return hd(this), this._value;
  }
  set value(t) {
    t = this.__v_isShallow ? t : ut(t), on(t, this._rawValue) && (this._rawValue = t, this._value = this.__v_isShallow ? t : an(t), ud(this));
  }
}
function I_(i) {
  return qt(i) ? i.value : i;
}
const Q_ = {
  get: (i, t, e) => I_(Reflect.get(i, t, e)),
  set: (i, t, e, s) => {
    const n = i[t];
    return qt(n) && !qt(e) ? (n.value = e, !0) : Reflect.set(i, t, e, s);
  }
};
function cd(i) {
  return hs(i) ? i : new Proxy(i, Q_);
}
class z_ {
  constructor(t, e, s, n) {
    this._setter = e, this.dep = void 0, this.__v_isRef = !0, this._dirty = !0, this.effect = new qa(t, () => {
      this._dirty || (this._dirty = !0, ud(this));
    }), this.effect.computed = this, this.effect.active = this._cacheable = !n, this.__v_isReadonly = s;
  }
  get value() {
    const t = ut(this);
    return hd(t), (t._dirty || !t._cacheable) && (t._dirty = !1, t._value = t.effect.run()), t._value;
  }
  set value(t) {
    this._setter(t);
  }
}
function W_(i, t, e = !1) {
  let s, n;
  const r = nt(i);
  return r ? (s = i, n = Te) : (s = i.get, n = i.set), new z_(s, n, r || !n, e);
}
function wi(i, t, e, s) {
  let n;
  try {
    n = s ? i(...s) : i();
  } catch (r) {
    ho(r, t, e);
  }
  return n;
}
function Pe(i, t, e, s) {
  if (nt(i)) {
    const r = wi(i, t, e, s);
    return r && qf(r) && r.catch((o) => {
      ho(o, t, e);
    }), r;
  }
  const n = [];
  for (let r = 0; r < i.length; r++)
    n.push(Pe(i[r], t, e, s));
  return n;
}
function ho(i, t, e, s = !0) {
  const n = t ? t.vnode : null;
  if (t) {
    let r = t.parent;
    const o = t.proxy, l = e;
    for (; r; ) {
      const h = r.ec;
      if (h) {
        for (let u = 0; u < h.length; u++)
          if (h[u](i, o, l) === !1)
            return;
      }
      r = r.parent;
    }
    const a = t.appContext.config.errorHandler;
    if (a) {
      wi(a, null, 10, [i, o, l]);
      return;
    }
  }
  $_(i, e, n, s);
}
function $_(i, t, e, s = !0) {
  console.error(i);
}
let kr = !1, Ql = !1;
const ce = [];
let Je = 0;
const Gs = [];
let $s = null, ts = 0;
const Js = [];
let ai = null, es = 0;
const fd = /* @__PURE__ */ Promise.resolve();
let th = null, zl = null;
function F_(i) {
  const t = th || fd;
  return i ? t.then(this ? i.bind(this) : i) : t;
}
function V_(i) {
  let t = Je + 1, e = ce.length;
  for (; t < e; ) {
    const s = t + e >>> 1;
    hn(ce[s]) < i ? t = s + 1 : e = s;
  }
  return t;
}
function dd(i) {
  (!ce.length || !ce.includes(i, kr && i.allowRecurse ? Je + 1 : Je)) && i !== zl && (i.id == null ? ce.push(i) : ce.splice(V_(i.id), 0, i), pd());
}
function pd() {
  !kr && !Ql && (Ql = !0, th = fd.then(_d));
}
function U_(i) {
  const t = ce.indexOf(i);
  t > Je && ce.splice(t, 1);
}
function gd(i, t, e, s) {
  st(i) ? e.push(...i) : (!t || !t.includes(i, i.allowRecurse ? s + 1 : s)) && e.push(i), pd();
}
function j_(i) {
  gd(i, $s, Gs, ts);
}
function H_(i) {
  gd(i, ai, Js, es);
}
function uo(i, t = null) {
  if (Gs.length) {
    for (zl = t, $s = [...new Set(Gs)], Gs.length = 0, ts = 0; ts < $s.length; ts++)
      $s[ts]();
    $s = null, ts = 0, zl = null, uo(i, t);
  }
}
function md(i) {
  if (uo(), Js.length) {
    const t = [...new Set(Js)];
    if (Js.length = 0, ai) {
      ai.push(...t);
      return;
    }
    for (ai = t, ai.sort((e, s) => hn(e) - hn(s)), es = 0; es < ai.length; es++)
      ai[es]();
    ai = null, es = 0;
  }
}
const hn = (i) => i.id == null ? 1 / 0 : i.id;
function _d(i) {
  Ql = !1, kr = !0, uo(i), ce.sort((e, s) => hn(e) - hn(s));
  const t = Te;
  try {
    for (Je = 0; Je < ce.length; Je++) {
      const e = ce[Je];
      e && e.active !== !1 && wi(e, null, 14);
    }
  } finally {
    Je = 0, ce.length = 0, md(), kr = !1, th = null, (ce.length || Gs.length || Js.length) && _d(i);
  }
}
function q_(i, t, ...e) {
  if (i.isUnmounted)
    return;
  const s = i.vnode.props || mt;
  let n = e;
  const r = t.startsWith("update:"), o = r && t.slice(7);
  if (o && o in s) {
    const u = `${o === "modelValue" ? "model" : o}Modifiers`, { number: c, trim: f } = s[u] || mt;
    f && (n = e.map((g) => g.trim())), c && (n = e.map(r_));
  }
  let l, a = s[l = Io(t)] || s[l = Io(Ue(t))];
  !a && r && (a = s[l = Io(Os(t))]), a && Pe(a, i, 6, n);
  const h = s[l + "Once"];
  if (h) {
    if (!i.emitted)
      i.emitted = {};
    else if (i.emitted[l])
      return;
    i.emitted[l] = !0, Pe(h, i, 6, n);
  }
}
function bd(i, t, e = !1) {
  const s = t.emitsCache, n = s.get(i);
  if (n !== void 0)
    return n;
  const r = i.emits;
  let o = {}, l = !1;
  if (!nt(i)) {
    const a = (h) => {
      const u = bd(h, t, !0);
      u && (l = !0, Zt(o, u));
    };
    !e && t.mixins.length && t.mixins.forEach(a), i.extends && a(i.extends), i.mixins && i.mixins.forEach(a);
  }
  return !r && !l ? (s.set(i, null), null) : (st(r) ? r.forEach((a) => o[a] = null) : Zt(o, r), s.set(i, o), o);
}
function co(i, t) {
  return !i || !no(t) ? !1 : (t = t.slice(2).replace(/Once$/, ""), lt(i, t[0].toLowerCase() + t.slice(1)) || lt(i, Os(t)) || lt(i, t));
}
let Se = null, fo = null;
function Or(i) {
  const t = Se;
  return Se = i, fo = i && i.type.__scopeId || null, t;
}
function yd(i) {
  fo = i;
}
function wd() {
  fo = null;
}
function K_(i, t = Se, e) {
  if (!t || i._n)
    return i;
  const s = (...n) => {
    s._d && lu(-1);
    const r = Or(t), o = i(...n);
    return Or(r), s._d && lu(1), o;
  };
  return s._n = !0, s._c = !0, s._d = !0, s;
}
function Wo(i) {
  const { type: t, vnode: e, proxy: s, withProxy: n, props: r, propsOptions: [o], slots: l, attrs: a, emit: h, render: u, renderCache: c, data: f, setupState: g, ctx: _, inheritAttrs: A } = i;
  let m, p;
  const y = Or(i);
  try {
    if (e.shapeFlag & 4) {
      const x = n || s;
      m = Qe(u.call(x, x, c, r, g, f, _)), p = a;
    } else {
      const x = t;
      m = Qe(x.length > 1 ? x(r, { attrs: a, slots: l, emit: h }) : x(r, null)), p = t.props ? a : X_(a);
    }
  } catch (x) {
    Ys.length = 0, ho(x, i, 1), m = Rt(Vi);
  }
  let M = m;
  if (p && A !== !1) {
    const x = Object.keys(p), { shapeFlag: B } = M;
    x.length && B & 7 && (o && x.some(Fa) && (p = G_(p, o)), M = ps(M, p));
  }
  return e.dirs && (M = ps(M), M.dirs = M.dirs ? M.dirs.concat(e.dirs) : e.dirs), e.transition && (M.transition = e.transition), m = M, Or(y), m;
}
const X_ = (i) => {
  let t;
  for (const e in i)
    (e === "class" || e === "style" || no(e)) && ((t || (t = {}))[e] = i[e]);
  return t;
}, G_ = (i, t) => {
  const e = {};
  for (const s in i)
    (!Fa(s) || !(s.slice(9) in t)) && (e[s] = i[s]);
  return e;
};
function J_(i, t, e) {
  const { props: s, children: n, component: r } = i, { props: o, children: l, patchFlag: a } = t, h = r.emitsOptions;
  if (t.dirs || t.transition)
    return !0;
  if (e && a >= 0) {
    if (a & 1024)
      return !0;
    if (a & 16)
      return s ? Jh(s, o, h) : !!o;
    if (a & 8) {
      const u = t.dynamicProps;
      for (let c = 0; c < u.length; c++) {
        const f = u[c];
        if (o[f] !== s[f] && !co(h, f))
          return !0;
      }
    }
  } else
    return (n || l) && (!l || !l.$stable) ? !0 : s === o ? !1 : s ? o ? Jh(s, o, h) : !0 : !!o;
  return !1;
}
function Jh(i, t, e) {
  const s = Object.keys(t);
  if (s.length !== Object.keys(i).length)
    return !0;
  for (let n = 0; n < s.length; n++) {
    const r = s[n];
    if (t[r] !== i[r] && !co(e, r))
      return !0;
  }
  return !1;
}
function Y_({ vnode: i, parent: t }, e) {
  for (; t && t.subTree === i; )
    (i = t.vnode).el = e, t = t.parent;
}
const Z_ = (i) => i.__isSuspense;
function t0(i, t) {
  t && t.pendingBranch ? st(i) ? t.effects.push(...i) : t.effects.push(i) : H_(i);
}
function e0(i, t) {
  if (Wt) {
    let e = Wt.provides;
    const s = Wt.parent && Wt.parent.provides;
    s === e && (e = Wt.provides = Object.create(s)), e[i] = t;
  }
}
function lr(i, t, e = !1) {
  const s = Wt || Se;
  if (s) {
    const n = s.parent == null ? s.vnode.appContext && s.vnode.appContext.provides : s.parent.provides;
    if (n && i in n)
      return n[i];
    if (arguments.length > 1)
      return e && nt(t) ? t.call(s.proxy) : t;
  }
}
const Yh = {};
function Ie(i, t, e) {
  return vd(i, t, e);
}
function vd(i, t, { immediate: e, deep: s, flush: n, onTrack: r, onTrigger: o } = mt) {
  const l = Wt;
  let a, h = !1, u = !1;
  if (qt(i) ? (a = () => i.value, h = Il(i)) : hs(i) ? (a = () => i, s = !0) : st(i) ? (u = !0, h = i.some((p) => hs(p) || Il(p)), a = () => i.map((p) => {
    if (qt(p))
      return p.value;
    if (hs(p))
      return Li(p);
    if (nt(p))
      return wi(p, l, 2);
  })) : nt(i) ? t ? a = () => wi(i, l, 2) : a = () => {
    if (!(l && l.isUnmounted))
      return c && c(), Pe(i, l, 3, [f]);
  } : a = Te, t && s) {
    const p = a;
    a = () => Li(p());
  }
  let c, f = (p) => {
    c = m.onStop = () => {
      wi(p, l, 4);
    };
  };
  if (cn)
    return f = Te, t ? e && Pe(t, l, 3, [
      a(),
      u ? [] : void 0,
      f
    ]) : a(), Te;
  let g = u ? [] : Yh;
  const _ = () => {
    if (!!m.active)
      if (t) {
        const p = m.run();
        (s || h || (u ? p.some((y, M) => on(y, g[M])) : on(p, g))) && (c && c(), Pe(t, l, 3, [
          p,
          g === Yh ? void 0 : g,
          f
        ]), g = p);
      } else
        m.run();
  };
  _.allowRecurse = !!t;
  let A;
  n === "sync" ? A = _ : n === "post" ? A = () => te(_, l && l.suspense) : A = () => j_(_);
  const m = new qa(a, A);
  return t ? e ? _() : g = m.run() : n === "post" ? te(m.run.bind(m), l && l.suspense) : m.run(), () => {
    m.stop(), l && l.scope && Va(l.scope.effects, m);
  };
}
function i0(i, t, e) {
  const s = this.proxy, n = Dt(i) ? i.includes(".") ? xd(s, i) : () => s[i] : i.bind(s, s);
  let r;
  nt(t) ? r = t : (r = t.handler, e = t);
  const o = Wt;
  gs(this);
  const l = vd(n, r.bind(s), e);
  return o ? gs(o) : Wi(), l;
}
function xd(i, t) {
  const e = t.split(".");
  return () => {
    let s = i;
    for (let n = 0; n < e.length && s; n++)
      s = s[e[n]];
    return s;
  };
}
function Li(i, t) {
  if (!Mt(i) || i.__v_skip || (t = t || /* @__PURE__ */ new Set(), t.has(i)))
    return i;
  if (t.add(i), qt(i))
    Li(i.value, t);
  else if (st(i))
    for (let e = 0; e < i.length; e++)
      Li(i[e], t);
  else if (Hf(i) || as(i))
    i.forEach((e) => {
      Li(e, t);
    });
  else if (Xf(i))
    for (const e in i)
      Li(i[e], t);
  return i;
}
function s0(i) {
  return nt(i) ? { setup: i, name: i.name } : i;
}
const ar = (i) => !!i.type.__asyncLoader, kd = (i) => i.type.__isKeepAlive;
function n0(i, t) {
  Od(i, "a", t);
}
function r0(i, t) {
  Od(i, "da", t);
}
function Od(i, t, e = Wt) {
  const s = i.__wdc || (i.__wdc = () => {
    let n = e;
    for (; n; ) {
      if (n.isDeactivated)
        return;
      n = n.parent;
    }
    return i();
  });
  if (po(t, s, e), e) {
    let n = e.parent;
    for (; n && n.parent; )
      kd(n.parent.vnode) && o0(s, t, e, n), n = n.parent;
  }
}
function o0(i, t, e, s) {
  const n = po(t, i, s, !0);
  Ad(() => {
    Va(s[t], n);
  }, e);
}
function po(i, t, e = Wt, s = !1) {
  if (e) {
    const n = e[i] || (e[i] = []), r = t.__weh || (t.__weh = (...o) => {
      if (e.isUnmounted)
        return;
      Ss(), gs(e);
      const l = Pe(t, e, i, o);
      return Wi(), Cs(), l;
    });
    return s ? n.unshift(r) : n.push(r), r;
  }
}
const ii = (i) => (t, e = Wt) => (!cn || i === "sp") && po(i, t, e), l0 = ii("bm"), Sd = ii("m"), a0 = ii("bu"), h0 = ii("u"), Cd = ii("bum"), Ad = ii("um"), u0 = ii("sp"), c0 = ii("rtg"), f0 = ii("rtc");
function d0(i, t = Wt) {
  po("ec", i, t);
}
function Zh(i, t) {
  const e = Se;
  if (e === null)
    return i;
  const s = mo(e) || e.proxy, n = i.dirs || (i.dirs = []);
  for (let r = 0; r < t.length; r++) {
    let [o, l, a, h = mt] = t[r];
    nt(o) && (o = {
      mounted: o,
      updated: o
    }), o.deep && Li(l), n.push({
      dir: o,
      instance: s,
      value: l,
      oldValue: void 0,
      arg: a,
      modifiers: h
    });
  }
  return i;
}
function Pi(i, t, e, s) {
  const n = i.dirs, r = t && t.dirs;
  for (let o = 0; o < n.length; o++) {
    const l = n[o];
    r && (l.oldValue = r[o].value);
    let a = l.dir[s];
    a && (Ss(), Pe(a, e, 8, [
      i.el,
      l,
      i,
      t
    ]), Cs());
  }
}
const Td = "components";
function Fs(i, t) {
  return g0(Td, i, !0, t) || i;
}
const p0 = Symbol();
function g0(i, t, e = !0, s = !1) {
  const n = Se || Wt;
  if (n) {
    const r = n.type;
    if (i === Td) {
      const l = U0(r, !1);
      if (l && (l === t || l === Ue(t) || l === lo(Ue(t))))
        return r;
    }
    const o = tu(n[i] || r[i], t) || tu(n.appContext[i], t);
    return !o && s ? r : o;
  }
}
function tu(i, t) {
  return i && (i[t] || i[Ue(t)] || i[lo(Ue(t))]);
}
function Sr(i, t, e, s) {
  let n;
  const r = e && e[s];
  if (st(i) || Dt(i)) {
    n = new Array(i.length);
    for (let o = 0, l = i.length; o < l; o++)
      n[o] = t(i[o], o, void 0, r && r[o]);
  } else if (typeof i == "number") {
    n = new Array(i);
    for (let o = 0; o < i; o++)
      n[o] = t(o + 1, o, void 0, r && r[o]);
  } else if (Mt(i))
    if (i[Symbol.iterator])
      n = Array.from(i, (o, l) => t(o, l, void 0, r && r[l]));
    else {
      const o = Object.keys(i);
      n = new Array(o.length);
      for (let l = 0, a = o.length; l < a; l++) {
        const h = o[l];
        n[l] = t(i[h], h, l, r && r[l]);
      }
    }
  else
    n = [];
  return e && (e[s] = n), n;
}
const Wl = (i) => i ? $d(i) ? mo(i) || i.proxy : Wl(i.parent) : null, Cr = /* @__PURE__ */ Zt(/* @__PURE__ */ Object.create(null), {
  $: (i) => i,
  $el: (i) => i.vnode.el,
  $data: (i) => i.data,
  $props: (i) => i.props,
  $attrs: (i) => i.attrs,
  $slots: (i) => i.slots,
  $refs: (i) => i.refs,
  $parent: (i) => Wl(i.parent),
  $root: (i) => Wl(i.root),
  $emit: (i) => i.emit,
  $options: (i) => Ed(i),
  $forceUpdate: (i) => i.f || (i.f = () => dd(i.update)),
  $nextTick: (i) => i.n || (i.n = F_.bind(i.proxy)),
  $watch: (i) => i0.bind(i)
}), m0 = {
  get({ _: i }, t) {
    const { ctx: e, setupState: s, data: n, props: r, accessCache: o, type: l, appContext: a } = i;
    let h;
    if (t[0] !== "$") {
      const g = o[t];
      if (g !== void 0)
        switch (g) {
          case 1:
            return s[t];
          case 2:
            return n[t];
          case 4:
            return e[t];
          case 3:
            return r[t];
        }
      else {
        if (s !== mt && lt(s, t))
          return o[t] = 1, s[t];
        if (n !== mt && lt(n, t))
          return o[t] = 2, n[t];
        if ((h = i.propsOptions[0]) && lt(h, t))
          return o[t] = 3, r[t];
        if (e !== mt && lt(e, t))
          return o[t] = 4, e[t];
        $l && (o[t] = 0);
      }
    }
    const u = Cr[t];
    let c, f;
    if (u)
      return t === "$attrs" && me(i, "get", t), u(i);
    if ((c = l.__cssModules) && (c = c[t]))
      return c;
    if (e !== mt && lt(e, t))
      return o[t] = 4, e[t];
    if (f = a.config.globalProperties, lt(f, t))
      return f[t];
  },
  set({ _: i }, t, e) {
    const { data: s, setupState: n, ctx: r } = i;
    return n !== mt && lt(n, t) ? (n[t] = e, !0) : s !== mt && lt(s, t) ? (s[t] = e, !0) : lt(i.props, t) || t[0] === "$" && t.slice(1) in i ? !1 : (r[t] = e, !0);
  },
  has({ _: { data: i, setupState: t, accessCache: e, ctx: s, appContext: n, propsOptions: r } }, o) {
    let l;
    return !!e[o] || i !== mt && lt(i, o) || t !== mt && lt(t, o) || (l = r[0]) && lt(l, o) || lt(s, o) || lt(Cr, o) || lt(n.config.globalProperties, o);
  },
  defineProperty(i, t, e) {
    return e.get != null ? i._.accessCache[t] = 0 : lt(e, "value") && this.set(i, t, e.value, null), Reflect.defineProperty(i, t, e);
  }
};
let $l = !0;
function _0(i) {
  const t = Ed(i), e = i.proxy, s = i.ctx;
  $l = !1, t.beforeCreate && eu(t.beforeCreate, i, "bc");
  const {
    data: n,
    computed: r,
    methods: o,
    watch: l,
    provide: a,
    inject: h,
    created: u,
    beforeMount: c,
    mounted: f,
    beforeUpdate: g,
    updated: _,
    activated: A,
    deactivated: m,
    beforeDestroy: p,
    beforeUnmount: y,
    destroyed: M,
    unmounted: x,
    render: B,
    renderTracked: v,
    renderTriggered: C,
    errorCaptured: k,
    serverPrefetch: E,
    expose: d,
    inheritAttrs: S,
    components: T,
    directives: $,
    filters: K
  } = t;
  if (h && b0(h, s, null, i.appContext.config.unwrapInjectedRef), o)
    for (const et in o) {
      const Z = o[et];
      nt(Z) && (s[et] = Z.bind(e));
    }
  if (n) {
    const et = n.call(e, e);
    Mt(et) && (i.data = Ja(et));
  }
  if ($l = !0, r)
    for (const et in r) {
      const Z = r[et], bt = nt(Z) ? Z.bind(e, e) : nt(Z.get) ? Z.get.bind(e, e) : Te, Ut = !nt(Z) && nt(Z.set) ? Z.set.bind(e) : Te, jt = Vd({
        get: bt,
        set: Ut
      });
      Object.defineProperty(s, et, {
        enumerable: !0,
        configurable: !0,
        get: () => jt.value,
        set: (re) => jt.value = re
      });
    }
  if (l)
    for (const et in l)
      Pd(l[et], s, e, et);
  if (a) {
    const et = nt(a) ? a.call(e) : a;
    Reflect.ownKeys(et).forEach((Z) => {
      e0(Z, et[Z]);
    });
  }
  u && eu(u, i, "c");
  function Y(et, Z) {
    st(Z) ? Z.forEach((bt) => et(bt.bind(e))) : Z && et(Z.bind(e));
  }
  if (Y(l0, c), Y(Sd, f), Y(a0, g), Y(h0, _), Y(n0, A), Y(r0, m), Y(d0, k), Y(f0, v), Y(c0, C), Y(Cd, y), Y(Ad, x), Y(u0, E), st(d))
    if (d.length) {
      const et = i.exposed || (i.exposed = {});
      d.forEach((Z) => {
        Object.defineProperty(et, Z, {
          get: () => e[Z],
          set: (bt) => e[Z] = bt
        });
      });
    } else
      i.exposed || (i.exposed = {});
  B && i.render === Te && (i.render = B), S != null && (i.inheritAttrs = S), T && (i.components = T), $ && (i.directives = $);
}
function b0(i, t, e = Te, s = !1) {
  st(i) && (i = Fl(i));
  for (const n in i) {
    const r = i[n];
    let o;
    Mt(r) ? "default" in r ? o = lr(r.from || n, r.default, !0) : o = lr(r.from || n) : o = lr(r), qt(o) && s ? Object.defineProperty(t, n, {
      enumerable: !0,
      configurable: !0,
      get: () => o.value,
      set: (l) => o.value = l
    }) : t[n] = o;
  }
}
function eu(i, t, e) {
  Pe(st(i) ? i.map((s) => s.bind(t.proxy)) : i.bind(t.proxy), t, e);
}
function Pd(i, t, e, s) {
  const n = s.includes(".") ? xd(e, s) : () => e[s];
  if (Dt(i)) {
    const r = t[i];
    nt(r) && Ie(n, r);
  } else if (nt(i))
    Ie(n, i.bind(e));
  else if (Mt(i))
    if (st(i))
      i.forEach((r) => Pd(r, t, e, s));
    else {
      const r = nt(i.handler) ? i.handler.bind(e) : t[i.handler];
      nt(r) && Ie(n, r, i);
    }
}
function Ed(i) {
  const t = i.type, { mixins: e, extends: s } = t, { mixins: n, optionsCache: r, config: { optionMergeStrategies: o } } = i.appContext, l = r.get(t);
  let a;
  return l ? a = l : !n.length && !e && !s ? a = t : (a = {}, n.length && n.forEach((h) => Ar(a, h, o, !0)), Ar(a, t, o)), r.set(t, a), a;
}
function Ar(i, t, e, s = !1) {
  const { mixins: n, extends: r } = t;
  r && Ar(i, r, e, !0), n && n.forEach((o) => Ar(i, o, e, !0));
  for (const o in t)
    if (!(s && o === "expose")) {
      const l = y0[o] || e && e[o];
      i[o] = l ? l(i[o], t[o]) : t[o];
    }
  return i;
}
const y0 = {
  data: iu,
  props: Ri,
  emits: Ri,
  methods: Ri,
  computed: Ri,
  beforeCreate: Kt,
  created: Kt,
  beforeMount: Kt,
  mounted: Kt,
  beforeUpdate: Kt,
  updated: Kt,
  beforeDestroy: Kt,
  beforeUnmount: Kt,
  destroyed: Kt,
  unmounted: Kt,
  activated: Kt,
  deactivated: Kt,
  errorCaptured: Kt,
  serverPrefetch: Kt,
  components: Ri,
  directives: Ri,
  watch: v0,
  provide: iu,
  inject: w0
};
function iu(i, t) {
  return t ? i ? function() {
    return Zt(nt(i) ? i.call(this, this) : i, nt(t) ? t.call(this, this) : t);
  } : t : i;
}
function w0(i, t) {
  return Ri(Fl(i), Fl(t));
}
function Fl(i) {
  if (st(i)) {
    const t = {};
    for (let e = 0; e < i.length; e++)
      t[i[e]] = i[e];
    return t;
  }
  return i;
}
function Kt(i, t) {
  return i ? [...new Set([].concat(i, t))] : t;
}
function Ri(i, t) {
  return i ? Zt(Zt(/* @__PURE__ */ Object.create(null), i), t) : t;
}
function v0(i, t) {
  if (!i)
    return t;
  if (!t)
    return i;
  const e = Zt(/* @__PURE__ */ Object.create(null), i);
  for (const s in t)
    e[s] = Kt(i[s], t[s]);
  return e;
}
function x0(i, t, e, s = !1) {
  const n = {}, r = {};
  xr(r, go, 1), i.propsDefaults = /* @__PURE__ */ Object.create(null), Md(i, t, n, r);
  for (const o in i.propsOptions[0])
    o in n || (n[o] = void 0);
  e ? i.props = s ? n : B_(n) : i.type.props ? i.props = n : i.props = r, i.attrs = r;
}
function k0(i, t, e, s) {
  const { props: n, attrs: r, vnode: { patchFlag: o } } = i, l = ut(n), [a] = i.propsOptions;
  let h = !1;
  if ((s || o > 0) && !(o & 16)) {
    if (o & 8) {
      const u = i.vnode.dynamicProps;
      for (let c = 0; c < u.length; c++) {
        let f = u[c];
        if (co(i.emitsOptions, f))
          continue;
        const g = t[f];
        if (a)
          if (lt(r, f))
            g !== r[f] && (r[f] = g, h = !0);
          else {
            const _ = Ue(f);
            n[_] = Vl(a, l, _, g, i, !1);
          }
        else
          g !== r[f] && (r[f] = g, h = !0);
      }
    }
  } else {
    Md(i, t, n, r) && (h = !0);
    let u;
    for (const c in l)
      (!t || !lt(t, c) && ((u = Os(c)) === c || !lt(t, u))) && (a ? e && (e[c] !== void 0 || e[u] !== void 0) && (n[c] = Vl(a, l, c, void 0, i, !0)) : delete n[c]);
    if (r !== l)
      for (const c in r)
        (!t || !lt(t, c) && !0) && (delete r[c], h = !0);
  }
  h && ti(i, "set", "$attrs");
}
function Md(i, t, e, s) {
  const [n, r] = i.propsOptions;
  let o = !1, l;
  if (t)
    for (let a in t) {
      if (or(a))
        continue;
      const h = t[a];
      let u;
      n && lt(n, u = Ue(a)) ? !r || !r.includes(u) ? e[u] = h : (l || (l = {}))[u] = h : co(i.emitsOptions, a) || (!(a in s) || h !== s[a]) && (s[a] = h, o = !0);
    }
  if (r) {
    const a = ut(e), h = l || mt;
    for (let u = 0; u < r.length; u++) {
      const c = r[u];
      e[c] = Vl(n, a, c, h[c], i, !lt(h, c));
    }
  }
  return o;
}
function Vl(i, t, e, s, n, r) {
  const o = i[e];
  if (o != null) {
    const l = lt(o, "default");
    if (l && s === void 0) {
      const a = o.default;
      if (o.type !== Function && nt(a)) {
        const { propsDefaults: h } = n;
        e in h ? s = h[e] : (gs(n), s = h[e] = a.call(null, t), Wi());
      } else
        s = a;
    }
    o[0] && (r && !l ? s = !1 : o[1] && (s === "" || s === Os(e)) && (s = !0));
  }
  return s;
}
function Rd(i, t, e = !1) {
  const s = t.propsCache, n = s.get(i);
  if (n)
    return n;
  const r = i.props, o = {}, l = [];
  let a = !1;
  if (!nt(i)) {
    const u = (c) => {
      a = !0;
      const [f, g] = Rd(c, t, !0);
      Zt(o, f), g && l.push(...g);
    };
    !e && t.mixins.length && t.mixins.forEach(u), i.extends && u(i.extends), i.mixins && i.mixins.forEach(u);
  }
  if (!r && !a)
    return s.set(i, ls), ls;
  if (st(r))
    for (let u = 0; u < r.length; u++) {
      const c = Ue(r[u]);
      su(c) && (o[c] = mt);
    }
  else if (r)
    for (const u in r) {
      const c = Ue(u);
      if (su(c)) {
        const f = r[u], g = o[c] = st(f) || nt(f) ? { type: f } : f;
        if (g) {
          const _ = ou(Boolean, g.type), A = ou(String, g.type);
          g[0] = _ > -1, g[1] = A < 0 || _ < A, (_ > -1 || lt(g, "default")) && l.push(c);
        }
      }
    }
  const h = [o, l];
  return s.set(i, h), h;
}
function su(i) {
  return i[0] !== "$";
}
function nu(i) {
  const t = i && i.toString().match(/^\s*function (\w+)/);
  return t ? t[1] : i === null ? "null" : "";
}
function ru(i, t) {
  return nu(i) === nu(t);
}
function ou(i, t) {
  return st(t) ? t.findIndex((e) => ru(e, i)) : nt(t) && ru(t, i) ? 0 : -1;
}
const Dd = (i) => i[0] === "_" || i === "$stable", eh = (i) => st(i) ? i.map(Qe) : [Qe(i)], O0 = (i, t, e) => {
  if (t._n)
    return t;
  const s = K_((...n) => eh(t(...n)), e);
  return s._c = !1, s;
}, Bd = (i, t, e) => {
  const s = i._ctx;
  for (const n in i) {
    if (Dd(n))
      continue;
    const r = i[n];
    if (nt(r))
      t[n] = O0(n, r, s);
    else if (r != null) {
      const o = eh(r);
      t[n] = () => o;
    }
  }
}, Nd = (i, t) => {
  const e = eh(t);
  i.slots.default = () => e;
}, S0 = (i, t) => {
  if (i.vnode.shapeFlag & 32) {
    const e = t._;
    e ? (i.slots = ut(t), xr(t, "_", e)) : Bd(t, i.slots = {});
  } else
    i.slots = {}, t && Nd(i, t);
  xr(i.slots, go, 1);
}, C0 = (i, t, e) => {
  const { vnode: s, slots: n } = i;
  let r = !0, o = mt;
  if (s.shapeFlag & 32) {
    const l = t._;
    l ? e && l === 1 ? r = !1 : (Zt(n, t), !e && l === 1 && delete n._) : (r = !t.$stable, Bd(t, n)), o = t;
  } else
    t && (Nd(i, t), o = { default: 1 });
  if (r)
    for (const l in n)
      !Dd(l) && !(l in o) && delete n[l];
};
function Ld() {
  return {
    app: null,
    config: {
      isNativeTag: Zm,
      performance: !1,
      globalProperties: {},
      optionMergeStrategies: {},
      errorHandler: void 0,
      warnHandler: void 0,
      compilerOptions: {}
    },
    mixins: [],
    components: {},
    directives: {},
    provides: /* @__PURE__ */ Object.create(null),
    optionsCache: /* @__PURE__ */ new WeakMap(),
    propsCache: /* @__PURE__ */ new WeakMap(),
    emitsCache: /* @__PURE__ */ new WeakMap()
  };
}
let A0 = 0;
function T0(i, t) {
  return function(s, n = null) {
    nt(s) || (s = Object.assign({}, s)), n != null && !Mt(n) && (n = null);
    const r = Ld(), o = /* @__PURE__ */ new Set();
    let l = !1;
    const a = r.app = {
      _uid: A0++,
      _component: s,
      _props: n,
      _container: null,
      _context: r,
      _instance: null,
      version: q0,
      get config() {
        return r.config;
      },
      set config(h) {
      },
      use(h, ...u) {
        return o.has(h) || (h && nt(h.install) ? (o.add(h), h.install(a, ...u)) : nt(h) && (o.add(h), h(a, ...u))), a;
      },
      mixin(h) {
        return r.mixins.includes(h) || r.mixins.push(h), a;
      },
      component(h, u) {
        return u ? (r.components[h] = u, a) : r.components[h];
      },
      directive(h, u) {
        return u ? (r.directives[h] = u, a) : r.directives[h];
      },
      mount(h, u, c) {
        if (!l) {
          const f = Rt(s, n);
          return f.appContext = r, u && t ? t(f, h) : i(f, h, c), l = !0, a._container = h, h.__vue_app__ = a, mo(f.component) || f.component.proxy;
        }
      },
      unmount() {
        l && (i(null, a._container), delete a._container.__vue_app__);
      },
      provide(h, u) {
        return r.provides[h] = u, a;
      }
    };
    return a;
  };
}
function Ul(i, t, e, s, n = !1) {
  if (st(i)) {
    i.forEach((f, g) => Ul(f, t && (st(t) ? t[g] : t), e, s, n));
    return;
  }
  if (ar(s) && !n)
    return;
  const r = s.shapeFlag & 4 ? mo(s.component) || s.component.proxy : s.el, o = n ? null : r, { i: l, r: a } = i, h = t && t.r, u = l.refs === mt ? l.refs = {} : l.refs, c = l.setupState;
  if (h != null && h !== a && (Dt(h) ? (u[h] = null, lt(c, h) && (c[h] = null)) : qt(h) && (h.value = null)), nt(a))
    wi(a, l, 12, [o, u]);
  else {
    const f = Dt(a), g = qt(a);
    if (f || g) {
      const _ = () => {
        if (i.f) {
          const A = f ? u[a] : a.value;
          n ? st(A) && Va(A, r) : st(A) ? A.includes(r) || A.push(r) : f ? (u[a] = [r], lt(c, a) && (c[a] = u[a])) : (a.value = [r], i.k && (u[i.k] = a.value));
        } else
          f ? (u[a] = o, lt(c, a) && (c[a] = o)) : g && (a.value = o, i.k && (u[i.k] = o));
      };
      o ? (_.id = -1, te(_, e)) : _();
    }
  }
}
const te = t0;
function P0(i) {
  return E0(i);
}
function E0(i, t) {
  const e = o_();
  e.__VUE__ = !0;
  const { insert: s, remove: n, patchProp: r, createElement: o, createText: l, createComment: a, setText: h, setElementText: u, parentNode: c, nextSibling: f, setScopeId: g = Te, cloneNode: _, insertStaticContent: A } = i, m = (b, w, P, N = null, D = null, Q = null, F = !1, I = null, z = !!w.dynamicChildren) => {
    if (b === w)
      return;
    b && !Ms(b, w) && (N = En(b), oe(b, D, Q, !0), b = null), w.patchFlag === -2 && (z = !1, w.dynamicChildren = null);
    const { type: L, ref: G, shapeFlag: U } = w;
    switch (L) {
      case ih:
        p(b, w, P, N);
        break;
      case Vi:
        y(b, w, P, N);
        break;
      case hr:
        b == null && M(w, P, N, F);
        break;
      case he:
        $(b, w, P, N, D, Q, F, I, z);
        break;
      default:
        U & 1 ? v(b, w, P, N, D, Q, F, I, z) : U & 6 ? K(b, w, P, N, D, Q, F, I, z) : (U & 64 || U & 128) && L.process(b, w, P, N, D, Q, F, I, z, Gi);
    }
    G != null && D && Ul(G, b && b.ref, Q, w || b, !w);
  }, p = (b, w, P, N) => {
    if (b == null)
      s(w.el = l(w.children), P, N);
    else {
      const D = w.el = b.el;
      w.children !== b.children && h(D, w.children);
    }
  }, y = (b, w, P, N) => {
    b == null ? s(w.el = a(w.children || ""), P, N) : w.el = b.el;
  }, M = (b, w, P, N) => {
    [b.el, b.anchor] = A(b.children, w, P, N, b.el, b.anchor);
  }, x = ({ el: b, anchor: w }, P, N) => {
    let D;
    for (; b && b !== w; )
      D = f(b), s(b, P, N), b = D;
    s(w, P, N);
  }, B = ({ el: b, anchor: w }) => {
    let P;
    for (; b && b !== w; )
      P = f(b), n(b), b = P;
    n(w);
  }, v = (b, w, P, N, D, Q, F, I, z) => {
    F = F || w.type === "svg", b == null ? C(w, P, N, D, Q, F, I, z) : d(b, w, D, Q, F, I, z);
  }, C = (b, w, P, N, D, Q, F, I) => {
    let z, L;
    const { type: G, props: U, shapeFlag: J, transition: tt, patchFlag: ht, dirs: dt } = b;
    if (b.el && _ !== void 0 && ht === -1)
      z = b.el = _(b.el);
    else {
      if (z = b.el = o(b.type, Q, U && U.is, U), J & 8 ? u(z, b.children) : J & 16 && E(b.children, z, null, N, D, Q && G !== "foreignObject", F, I), dt && Pi(b, null, N, "created"), U) {
        for (const yt in U)
          yt !== "value" && !or(yt) && r(z, yt, null, U[yt], Q, b.children, N, D, Xe);
        "value" in U && r(z, "value", null, U.value), (L = U.onVnodeBeforeMount) && Me(L, N, b);
      }
      k(z, b, b.scopeId, F, N);
    }
    dt && Pi(b, null, N, "beforeMount");
    const pt = (!D || D && !D.pendingBranch) && tt && !tt.persisted;
    pt && tt.beforeEnter(z), s(z, w, P), ((L = U && U.onVnodeMounted) || pt || dt) && te(() => {
      L && Me(L, N, b), pt && tt.enter(z), dt && Pi(b, null, N, "mounted");
    }, D);
  }, k = (b, w, P, N, D) => {
    if (P && g(b, P), N)
      for (let Q = 0; Q < N.length; Q++)
        g(b, N[Q]);
    if (D) {
      let Q = D.subTree;
      if (w === Q) {
        const F = D.vnode;
        k(b, F, F.scopeId, F.slotScopeIds, D.parent);
      }
    }
  }, E = (b, w, P, N, D, Q, F, I, z = 0) => {
    for (let L = z; L < b.length; L++) {
      const G = b[L] = I ? hi(b[L]) : Qe(b[L]);
      m(null, G, w, P, N, D, Q, F, I);
    }
  }, d = (b, w, P, N, D, Q, F) => {
    const I = w.el = b.el;
    let { patchFlag: z, dynamicChildren: L, dirs: G } = w;
    z |= b.patchFlag & 16;
    const U = b.props || mt, J = w.props || mt;
    let tt;
    P && Ei(P, !1), (tt = J.onVnodeBeforeUpdate) && Me(tt, P, w, b), G && Pi(w, b, P, "beforeUpdate"), P && Ei(P, !0);
    const ht = D && w.type !== "foreignObject";
    if (L ? S(b.dynamicChildren, L, I, P, N, ht, Q) : F || bt(b, w, I, null, P, N, ht, Q, !1), z > 0) {
      if (z & 16)
        T(I, w, U, J, P, N, D);
      else if (z & 2 && U.class !== J.class && r(I, "class", null, J.class, D), z & 4 && r(I, "style", U.style, J.style, D), z & 8) {
        const dt = w.dynamicProps;
        for (let pt = 0; pt < dt.length; pt++) {
          const yt = dt[pt], xe = U[yt], Ji = J[yt];
          (Ji !== xe || yt === "value") && r(I, yt, xe, Ji, D, b.children, P, N, Xe);
        }
      }
      z & 1 && b.children !== w.children && u(I, w.children);
    } else
      !F && L == null && T(I, w, U, J, P, N, D);
    ((tt = J.onVnodeUpdated) || G) && te(() => {
      tt && Me(tt, P, w, b), G && Pi(w, b, P, "updated");
    }, N);
  }, S = (b, w, P, N, D, Q, F) => {
    for (let I = 0; I < w.length; I++) {
      const z = b[I], L = w[I], G = z.el && (z.type === he || !Ms(z, L) || z.shapeFlag & 70) ? c(z.el) : P;
      m(z, L, G, null, N, D, Q, F, !0);
    }
  }, T = (b, w, P, N, D, Q, F) => {
    if (P !== N) {
      for (const I in N) {
        if (or(I))
          continue;
        const z = N[I], L = P[I];
        z !== L && I !== "value" && r(b, I, L, z, F, w.children, D, Q, Xe);
      }
      if (P !== mt)
        for (const I in P)
          !or(I) && !(I in N) && r(b, I, P[I], null, F, w.children, D, Q, Xe);
      "value" in N && r(b, "value", P.value, N.value);
    }
  }, $ = (b, w, P, N, D, Q, F, I, z) => {
    const L = w.el = b ? b.el : l(""), G = w.anchor = b ? b.anchor : l("");
    let { patchFlag: U, dynamicChildren: J, slotScopeIds: tt } = w;
    tt && (I = I ? I.concat(tt) : tt), b == null ? (s(L, P, N), s(G, P, N), E(w.children, P, G, D, Q, F, I, z)) : U > 0 && U & 64 && J && b.dynamicChildren ? (S(b.dynamicChildren, J, P, D, Q, F, I), (w.key != null || D && w === D.subTree) && Id(b, w, !0)) : bt(b, w, P, G, D, Q, F, I, z);
  }, K = (b, w, P, N, D, Q, F, I, z) => {
    w.slotScopeIds = I, b == null ? w.shapeFlag & 512 ? D.ctx.activate(w, P, N, F, z) : it(w, P, N, D, Q, F, z) : Y(b, w, z);
  }, it = (b, w, P, N, D, Q, F) => {
    const I = b.component = z0(b, N, D);
    if (kd(b) && (I.ctx.renderer = Gi), W0(I), I.asyncDep) {
      if (D && D.registerDep(I, et), !b.el) {
        const z = I.subTree = Rt(Vi);
        y(null, z, w, P);
      }
      return;
    }
    et(I, b, w, P, D, Q, F);
  }, Y = (b, w, P) => {
    const N = w.component = b.component;
    if (J_(b, w, P))
      if (N.asyncDep && !N.asyncResolved) {
        Z(N, w, P);
        return;
      } else
        N.next = w, U_(N.update), N.update();
    else
      w.el = b.el, N.vnode = w;
  }, et = (b, w, P, N, D, Q, F) => {
    const I = () => {
      if (b.isMounted) {
        let { next: G, bu: U, u: J, parent: tt, vnode: ht } = b, dt = G, pt;
        Ei(b, !1), G ? (G.el = ht.el, Z(b, G, F)) : G = ht, U && Qo(U), (pt = G.props && G.props.onVnodeBeforeUpdate) && Me(pt, tt, G, ht), Ei(b, !0);
        const yt = Wo(b), xe = b.subTree;
        b.subTree = yt, m(
          xe,
          yt,
          c(xe.el),
          En(xe),
          b,
          D,
          Q
        ), G.el = yt.el, dt === null && Y_(b, yt.el), J && te(J, D), (pt = G.props && G.props.onVnodeUpdated) && te(() => Me(pt, tt, G, ht), D);
      } else {
        let G;
        const { el: U, props: J } = w, { bm: tt, m: ht, parent: dt } = b, pt = ar(w);
        if (Ei(b, !1), tt && Qo(tt), !pt && (G = J && J.onVnodeBeforeMount) && Me(G, dt, w), Ei(b, !0), U && Lo) {
          const yt = () => {
            b.subTree = Wo(b), Lo(U, b.subTree, b, D, null);
          };
          pt ? w.type.__asyncLoader().then(
            () => !b.isUnmounted && yt()
          ) : yt();
        } else {
          const yt = b.subTree = Wo(b);
          m(null, yt, P, N, b, D, Q), w.el = yt.el;
        }
        if (ht && te(ht, D), !pt && (G = J && J.onVnodeMounted)) {
          const yt = w;
          te(() => Me(G, dt, yt), D);
        }
        (w.shapeFlag & 256 || dt && ar(dt.vnode) && dt.vnode.shapeFlag & 256) && b.a && te(b.a, D), b.isMounted = !0, w = P = N = null;
      }
    }, z = b.effect = new qa(
      I,
      () => dd(L),
      b.scope
    ), L = b.update = () => z.run();
    L.id = b.uid, Ei(b, !0), L();
  }, Z = (b, w, P) => {
    w.component = b;
    const N = b.vnode.props;
    b.vnode = w, b.next = null, k0(b, w.props, N, P), C0(b, w.children, P), Ss(), uo(void 0, b.update), Cs();
  }, bt = (b, w, P, N, D, Q, F, I, z = !1) => {
    const L = b && b.children, G = b ? b.shapeFlag : 0, U = w.children, { patchFlag: J, shapeFlag: tt } = w;
    if (J > 0) {
      if (J & 128) {
        jt(L, U, P, N, D, Q, F, I, z);
        return;
      } else if (J & 256) {
        Ut(L, U, P, N, D, Q, F, I, z);
        return;
      }
    }
    tt & 8 ? (G & 16 && Xe(L, D, Q), U !== L && u(P, U)) : G & 16 ? tt & 16 ? jt(L, U, P, N, D, Q, F, I, z) : Xe(L, D, Q, !0) : (G & 8 && u(P, ""), tt & 16 && E(U, P, N, D, Q, F, I, z));
  }, Ut = (b, w, P, N, D, Q, F, I, z) => {
    b = b || ls, w = w || ls;
    const L = b.length, G = w.length, U = Math.min(L, G);
    let J;
    for (J = 0; J < U; J++) {
      const tt = w[J] = z ? hi(w[J]) : Qe(w[J]);
      m(b[J], tt, P, null, D, Q, F, I, z);
    }
    L > G ? Xe(b, D, Q, !0, !1, U) : E(w, P, N, D, Q, F, I, z, U);
  }, jt = (b, w, P, N, D, Q, F, I, z) => {
    let L = 0;
    const G = w.length;
    let U = b.length - 1, J = G - 1;
    for (; L <= U && L <= J; ) {
      const tt = b[L], ht = w[L] = z ? hi(w[L]) : Qe(w[L]);
      if (Ms(tt, ht))
        m(tt, ht, P, null, D, Q, F, I, z);
      else
        break;
      L++;
    }
    for (; L <= U && L <= J; ) {
      const tt = b[U], ht = w[J] = z ? hi(w[J]) : Qe(w[J]);
      if (Ms(tt, ht))
        m(tt, ht, P, null, D, Q, F, I, z);
      else
        break;
      U--, J--;
    }
    if (L > U) {
      if (L <= J) {
        const tt = J + 1, ht = tt < G ? w[tt].el : N;
        for (; L <= J; )
          m(null, w[L] = z ? hi(w[L]) : Qe(w[L]), P, ht, D, Q, F, I, z), L++;
      }
    } else if (L > J)
      for (; L <= U; )
        oe(b[L], D, Q, !0), L++;
    else {
      const tt = L, ht = L, dt = /* @__PURE__ */ new Map();
      for (L = ht; L <= J; L++) {
        const le = w[L] = z ? hi(w[L]) : Qe(w[L]);
        le.key != null && dt.set(le.key, L);
      }
      let pt, yt = 0;
      const xe = J - ht + 1;
      let Ji = !1, Wh = 0;
      const Es = new Array(xe);
      for (L = 0; L < xe; L++)
        Es[L] = 0;
      for (L = tt; L <= U; L++) {
        const le = b[L];
        if (yt >= xe) {
          oe(le, D, Q, !0);
          continue;
        }
        let Ee;
        if (le.key != null)
          Ee = dt.get(le.key);
        else
          for (pt = ht; pt <= J; pt++)
            if (Es[pt - ht] === 0 && Ms(le, w[pt])) {
              Ee = pt;
              break;
            }
        Ee === void 0 ? oe(le, D, Q, !0) : (Es[Ee - ht] = L + 1, Ee >= Wh ? Wh = Ee : Ji = !0, m(le, w[Ee], P, null, D, Q, F, I, z), yt++);
      }
      const $h = Ji ? M0(Es) : ls;
      for (pt = $h.length - 1, L = xe - 1; L >= 0; L--) {
        const le = ht + L, Ee = w[le], Fh = le + 1 < G ? w[le + 1].el : N;
        Es[L] === 0 ? m(null, Ee, P, Fh, D, Q, F, I, z) : Ji && (pt < 0 || L !== $h[pt] ? re(Ee, P, Fh, 2) : pt--);
      }
    }
  }, re = (b, w, P, N, D = null) => {
    const { el: Q, type: F, transition: I, children: z, shapeFlag: L } = b;
    if (L & 6) {
      re(b.component.subTree, w, P, N);
      return;
    }
    if (L & 128) {
      b.suspense.move(w, P, N);
      return;
    }
    if (L & 64) {
      F.move(b, w, P, Gi);
      return;
    }
    if (F === he) {
      s(Q, w, P);
      for (let U = 0; U < z.length; U++)
        re(z[U], w, P, N);
      s(b.anchor, w, P);
      return;
    }
    if (F === hr) {
      x(b, w, P);
      return;
    }
    if (N !== 2 && L & 1 && I)
      if (N === 0)
        I.beforeEnter(Q), s(Q, w, P), te(() => I.enter(Q), D);
      else {
        const { leave: U, delayLeave: J, afterLeave: tt } = I, ht = () => s(Q, w, P), dt = () => {
          U(Q, () => {
            ht(), tt && tt();
          });
        };
        J ? J(Q, ht, dt) : dt();
      }
    else
      s(Q, w, P);
  }, oe = (b, w, P, N = !1, D = !1) => {
    const { type: Q, props: F, ref: I, children: z, dynamicChildren: L, shapeFlag: G, patchFlag: U, dirs: J } = b;
    if (I != null && Ul(I, null, P, b, !0), G & 256) {
      w.ctx.deactivate(b);
      return;
    }
    const tt = G & 1 && J, ht = !ar(b);
    let dt;
    if (ht && (dt = F && F.onVnodeBeforeUnmount) && Me(dt, w, b), G & 6)
      qm(b.component, P, N);
    else {
      if (G & 128) {
        b.suspense.unmount(P, N);
        return;
      }
      tt && Pi(b, null, w, "beforeUnmount"), G & 64 ? b.type.remove(b, w, P, D, Gi, N) : L && (Q !== he || U > 0 && U & 64) ? Xe(L, w, P, !1, !0) : (Q === he && U & 384 || !D && G & 16) && Xe(z, w, P), N && Ps(b);
    }
    (ht && (dt = F && F.onVnodeUnmounted) || tt) && te(() => {
      dt && Me(dt, w, b), tt && Pi(b, null, w, "unmounted");
    }, P);
  }, Ps = (b) => {
    const { type: w, el: P, anchor: N, transition: D } = b;
    if (w === he) {
      Hm(P, N);
      return;
    }
    if (w === hr) {
      B(b);
      return;
    }
    const Q = () => {
      n(P), D && !D.persisted && D.afterLeave && D.afterLeave();
    };
    if (b.shapeFlag & 1 && D && !D.persisted) {
      const { leave: F, delayLeave: I } = D, z = () => F(P, Q);
      I ? I(b.el, Q, z) : z();
    } else
      Q();
  }, Hm = (b, w) => {
    let P;
    for (; b !== w; )
      P = f(b), n(b), b = P;
    n(w);
  }, qm = (b, w, P) => {
    const { bum: N, scope: D, update: Q, subTree: F, um: I } = b;
    N && Qo(N), D.stop(), Q && (Q.active = !1, oe(F, b, w, P)), I && te(I, w), te(() => {
      b.isUnmounted = !0;
    }, w), w && w.pendingBranch && !w.isUnmounted && b.asyncDep && !b.asyncResolved && b.suspenseId === w.pendingId && (w.deps--, w.deps === 0 && w.resolve());
  }, Xe = (b, w, P, N = !1, D = !1, Q = 0) => {
    for (let F = Q; F < b.length; F++)
      oe(b[F], w, P, N, D);
  }, En = (b) => b.shapeFlag & 6 ? En(b.component.subTree) : b.shapeFlag & 128 ? b.suspense.next() : f(b.anchor || b.el), zh = (b, w, P) => {
    b == null ? w._vnode && oe(w._vnode, null, null, !0) : m(w._vnode || null, b, w, null, null, null, P), md(), w._vnode = b;
  }, Gi = {
    p: m,
    um: oe,
    m: re,
    r: Ps,
    mt: it,
    mc: E,
    pc: bt,
    pbc: S,
    n: En,
    o: i
  };
  let No, Lo;
  return t && ([No, Lo] = t(Gi)), {
    render: zh,
    hydrate: No,
    createApp: T0(zh, No)
  };
}
function Ei({ effect: i, update: t }, e) {
  i.allowRecurse = t.allowRecurse = e;
}
function Id(i, t, e = !1) {
  const s = i.children, n = t.children;
  if (st(s) && st(n))
    for (let r = 0; r < s.length; r++) {
      const o = s[r];
      let l = n[r];
      l.shapeFlag & 1 && !l.dynamicChildren && ((l.patchFlag <= 0 || l.patchFlag === 32) && (l = n[r] = hi(n[r]), l.el = o.el), e || Id(o, l));
    }
}
function M0(i) {
  const t = i.slice(), e = [0];
  let s, n, r, o, l;
  const a = i.length;
  for (s = 0; s < a; s++) {
    const h = i[s];
    if (h !== 0) {
      if (n = e[e.length - 1], i[n] < h) {
        t[s] = n, e.push(s);
        continue;
      }
      for (r = 0, o = e.length - 1; r < o; )
        l = r + o >> 1, i[e[l]] < h ? r = l + 1 : o = l;
      h < i[e[r]] && (r > 0 && (t[s] = e[r - 1]), e[r] = s);
    }
  }
  for (r = e.length, o = e[r - 1]; r-- > 0; )
    e[r] = o, o = t[o];
  return e;
}
const R0 = (i) => i.__isTeleport, he = Symbol(void 0), ih = Symbol(void 0), Vi = Symbol(void 0), hr = Symbol(void 0), Ys = [];
let Ce = null;
function gt(i = !1) {
  Ys.push(Ce = i ? null : []);
}
function D0() {
  Ys.pop(), Ce = Ys[Ys.length - 1] || null;
}
let un = 1;
function lu(i) {
  un += i;
}
function Qd(i) {
  return i.dynamicChildren = un > 0 ? Ce || ls : null, D0(), un > 0 && Ce && Ce.push(i), i;
}
function xt(i, t, e, s, n, r) {
  return Qd(V(i, t, e, s, n, r, !0));
}
function jl(i, t, e, s, n) {
  return Qd(Rt(i, t, e, s, n, !0));
}
function Hl(i) {
  return i ? i.__v_isVNode === !0 : !1;
}
function Ms(i, t) {
  return i.type === t.type && i.key === t.key;
}
const go = "__vInternal", zd = ({ key: i }) => i != null ? i : null, ur = ({ ref: i, ref_key: t, ref_for: e }) => i != null ? Dt(i) || qt(i) || nt(i) ? { i: Se, r: i, k: t, f: !!e } : i : null;
function V(i, t = null, e = null, s = 0, n = null, r = i === he ? 0 : 1, o = !1, l = !1) {
  const a = {
    __v_isVNode: !0,
    __v_skip: !0,
    type: i,
    props: t,
    key: t && zd(t),
    ref: t && ur(t),
    scopeId: fo,
    slotScopeIds: null,
    children: e,
    component: null,
    suspense: null,
    ssContent: null,
    ssFallback: null,
    dirs: null,
    transition: null,
    el: null,
    anchor: null,
    target: null,
    targetAnchor: null,
    staticCount: 0,
    shapeFlag: r,
    patchFlag: s,
    dynamicProps: n,
    dynamicChildren: null,
    appContext: null
  };
  return l ? (sh(a, e), r & 128 && i.normalize(a)) : e && (a.shapeFlag |= Dt(e) ? 8 : 16), un > 0 && !o && Ce && (a.patchFlag > 0 || r & 6) && a.patchFlag !== 32 && Ce.push(a), a;
}
const Rt = B0;
function B0(i, t = null, e = null, s = 0, n = null, r = !1) {
  if ((!i || i === p0) && (i = Vi), Hl(i)) {
    const l = ps(i, t, !0);
    return e && sh(l, e), un > 0 && !r && Ce && (l.shapeFlag & 6 ? Ce[Ce.indexOf(i)] = l : Ce.push(l)), l.patchFlag |= -2, l;
  }
  if (j0(i) && (i = i.__vccOpts), t) {
    t = N0(t);
    let { class: l, style: a } = t;
    l && !Dt(l) && (t.class = $a(l)), Mt(a) && (ld(a) && !st(a) && (a = Zt({}, a)), t.style = Wa(a));
  }
  const o = Dt(i) ? 1 : Z_(i) ? 128 : R0(i) ? 64 : Mt(i) ? 4 : nt(i) ? 2 : 0;
  return V(i, t, e, s, n, o, r, !0);
}
function N0(i) {
  return i ? ld(i) || go in i ? Zt({}, i) : i : null;
}
function ps(i, t, e = !1) {
  const { props: s, ref: n, patchFlag: r, children: o } = i, l = t ? Wd(s || {}, t) : s;
  return {
    __v_isVNode: !0,
    __v_skip: !0,
    type: i.type,
    props: l,
    key: l && zd(l),
    ref: t && t.ref ? e && n ? st(n) ? n.concat(ur(t)) : [n, ur(t)] : ur(t) : n,
    scopeId: i.scopeId,
    slotScopeIds: i.slotScopeIds,
    children: o,
    target: i.target,
    targetAnchor: i.targetAnchor,
    staticCount: i.staticCount,
    shapeFlag: i.shapeFlag,
    patchFlag: t && i.type !== he ? r === -1 ? 16 : r | 16 : r,
    dynamicProps: i.dynamicProps,
    dynamicChildren: i.dynamicChildren,
    appContext: i.appContext,
    dirs: i.dirs,
    transition: i.transition,
    component: i.component,
    suspense: i.suspense,
    ssContent: i.ssContent && ps(i.ssContent),
    ssFallback: i.ssFallback && ps(i.ssFallback),
    el: i.el,
    anchor: i.anchor
  };
}
function Ui(i = " ", t = 0) {
  return Rt(ih, null, i, t);
}
function L0(i, t) {
  const e = Rt(hr, null, i);
  return e.staticCount = t, e;
}
function di(i = "", t = !1) {
  return t ? (gt(), jl(Vi, null, i)) : Rt(Vi, null, i);
}
function Qe(i) {
  return i == null || typeof i == "boolean" ? Rt(Vi) : st(i) ? Rt(
    he,
    null,
    i.slice()
  ) : typeof i == "object" ? hi(i) : Rt(ih, null, String(i));
}
function hi(i) {
  return i.el === null || i.memo ? i : ps(i);
}
function sh(i, t) {
  let e = 0;
  const { shapeFlag: s } = i;
  if (t == null)
    t = null;
  else if (st(t))
    e = 16;
  else if (typeof t == "object")
    if (s & 65) {
      const n = t.default;
      n && (n._c && (n._d = !1), sh(i, n()), n._c && (n._d = !0));
      return;
    } else {
      e = 32;
      const n = t._;
      !n && !(go in t) ? t._ctx = Se : n === 3 && Se && (Se.slots._ === 1 ? t._ = 1 : (t._ = 2, i.patchFlag |= 1024));
    }
  else
    nt(t) ? (t = { default: t, _ctx: Se }, e = 32) : (t = String(t), s & 64 ? (e = 16, t = [Ui(t)]) : e = 8);
  i.children = t, i.shapeFlag |= e;
}
function Wd(...i) {
  const t = {};
  for (let e = 0; e < i.length; e++) {
    const s = i[e];
    for (const n in s)
      if (n === "class")
        t.class !== s.class && (t.class = $a([t.class, s.class]));
      else if (n === "style")
        t.style = Wa([t.style, s.style]);
      else if (no(n)) {
        const r = t[n], o = s[n];
        o && r !== o && !(st(r) && r.includes(o)) && (t[n] = r ? [].concat(r, o) : o);
      } else
        n !== "" && (t[n] = s[n]);
  }
  return t;
}
function Me(i, t, e, s = null) {
  Pe(i, t, 7, [
    e,
    s
  ]);
}
const I0 = Ld();
let Q0 = 0;
function z0(i, t, e) {
  const s = i.type, n = (t ? t.appContext : i.appContext) || I0, r = {
    uid: Q0++,
    vnode: i,
    type: s,
    parent: t,
    appContext: n,
    root: null,
    next: null,
    subTree: null,
    effect: null,
    update: null,
    scope: new l_(!0),
    render: null,
    proxy: null,
    exposed: null,
    exposeProxy: null,
    withProxy: null,
    provides: t ? t.provides : Object.create(n.provides),
    accessCache: null,
    renderCache: [],
    components: null,
    directives: null,
    propsOptions: Rd(s, n),
    emitsOptions: bd(s, n),
    emit: null,
    emitted: null,
    propsDefaults: mt,
    inheritAttrs: s.inheritAttrs,
    ctx: mt,
    data: mt,
    props: mt,
    attrs: mt,
    slots: mt,
    refs: mt,
    setupState: mt,
    setupContext: null,
    suspense: e,
    suspenseId: e ? e.pendingId : 0,
    asyncDep: null,
    asyncResolved: !1,
    isMounted: !1,
    isUnmounted: !1,
    isDeactivated: !1,
    bc: null,
    c: null,
    bm: null,
    m: null,
    bu: null,
    u: null,
    um: null,
    bum: null,
    da: null,
    a: null,
    rtg: null,
    rtc: null,
    ec: null,
    sp: null
  };
  return r.ctx = { _: r }, r.root = t ? t.root : r, r.emit = q_.bind(null, r), i.ce && i.ce(r), r;
}
let Wt = null;
const gs = (i) => {
  Wt = i, i.scope.on();
}, Wi = () => {
  Wt && Wt.scope.off(), Wt = null;
};
function $d(i) {
  return i.vnode.shapeFlag & 4;
}
let cn = !1;
function W0(i, t = !1) {
  cn = t;
  const { props: e, children: s } = i.vnode, n = $d(i);
  x0(i, e, n, t), S0(i, s);
  const r = n ? $0(i, t) : void 0;
  return cn = !1, r;
}
function $0(i, t) {
  const e = i.type;
  i.accessCache = /* @__PURE__ */ Object.create(null), i.proxy = ad(new Proxy(i.ctx, m0));
  const { setup: s } = e;
  if (s) {
    const n = i.setupContext = s.length > 1 ? V0(i) : null;
    gs(i), Ss();
    const r = wi(s, i, 0, [i.props, n]);
    if (Cs(), Wi(), qf(r)) {
      if (r.then(Wi, Wi), t)
        return r.then((o) => {
          au(i, o, t);
        }).catch((o) => {
          ho(o, i, 0);
        });
      i.asyncDep = r;
    } else
      au(i, r, t);
  } else
    Fd(i, t);
}
function au(i, t, e) {
  nt(t) ? i.type.__ssrInlineRender ? i.ssrRender = t : i.render = t : Mt(t) && (i.setupState = cd(t)), Fd(i, e);
}
let hu;
function Fd(i, t, e) {
  const s = i.type;
  if (!i.render) {
    if (!t && hu && !s.render) {
      const n = s.template;
      if (n) {
        const { isCustomElement: r, compilerOptions: o } = i.appContext.config, { delimiters: l, compilerOptions: a } = s, h = Zt(Zt({
          isCustomElement: r,
          delimiters: l
        }, o), a);
        s.render = hu(n, h);
      }
    }
    i.render = s.render || Te;
  }
  gs(i), Ss(), _0(i), Cs(), Wi();
}
function F0(i) {
  return new Proxy(i.attrs, {
    get(t, e) {
      return me(i, "get", "$attrs"), t[e];
    }
  });
}
function V0(i) {
  const t = (s) => {
    i.exposed = s || {};
  };
  let e;
  return {
    get attrs() {
      return e || (e = F0(i));
    },
    slots: i.slots,
    emit: i.emit,
    expose: t
  };
}
function mo(i) {
  if (i.exposed)
    return i.exposeProxy || (i.exposeProxy = new Proxy(cd(ad(i.exposed)), {
      get(t, e) {
        if (e in t)
          return t[e];
        if (e in Cr)
          return Cr[e](i);
      }
    }));
}
function U0(i, t = !0) {
  return nt(i) ? i.displayName || i.name : i.name || t && i.__name;
}
function j0(i) {
  return nt(i) && "__vccOpts" in i;
}
const Vd = (i, t) => W_(i, t, cn);
function H0(i, t, e) {
  const s = arguments.length;
  return s === 2 ? Mt(t) && !st(t) ? Hl(t) ? Rt(i, null, [t]) : Rt(i, t) : Rt(i, null, t) : (s > 3 ? e = Array.prototype.slice.call(arguments, 2) : s === 3 && Hl(e) && (e = [e]), Rt(i, t, e));
}
const q0 = "3.2.37", K0 = "http://www.w3.org/2000/svg", Di = typeof document != "undefined" ? document : null, uu = Di && /* @__PURE__ */ Di.createElement("template"), X0 = {
  insert: (i, t, e) => {
    t.insertBefore(i, e || null);
  },
  remove: (i) => {
    const t = i.parentNode;
    t && t.removeChild(i);
  },
  createElement: (i, t, e, s) => {
    const n = t ? Di.createElementNS(K0, i) : Di.createElement(i, e ? { is: e } : void 0);
    return i === "select" && s && s.multiple != null && n.setAttribute("multiple", s.multiple), n;
  },
  createText: (i) => Di.createTextNode(i),
  createComment: (i) => Di.createComment(i),
  setText: (i, t) => {
    i.nodeValue = t;
  },
  setElementText: (i, t) => {
    i.textContent = t;
  },
  parentNode: (i) => i.parentNode,
  nextSibling: (i) => i.nextSibling,
  querySelector: (i) => Di.querySelector(i),
  setScopeId(i, t) {
    i.setAttribute(t, "");
  },
  cloneNode(i) {
    const t = i.cloneNode(!0);
    return "_value" in i && (t._value = i._value), t;
  },
  insertStaticContent(i, t, e, s, n, r) {
    const o = e ? e.previousSibling : t.lastChild;
    if (n && (n === r || n.nextSibling))
      for (; t.insertBefore(n.cloneNode(!0), e), !(n === r || !(n = n.nextSibling)); )
        ;
    else {
      uu.innerHTML = s ? `<svg>${i}</svg>` : i;
      const l = uu.content;
      if (s) {
        const a = l.firstChild;
        for (; a.firstChild; )
          l.appendChild(a.firstChild);
        l.removeChild(a);
      }
      t.insertBefore(l, e);
    }
    return [
      o ? o.nextSibling : t.firstChild,
      e ? e.previousSibling : t.lastChild
    ];
  }
};
function G0(i, t, e) {
  const s = i._vtc;
  s && (t = (t ? [t, ...s] : [...s]).join(" ")), t == null ? i.removeAttribute("class") : e ? i.setAttribute("class", t) : i.className = t;
}
function J0(i, t, e) {
  const s = i.style, n = Dt(e);
  if (e && !n) {
    for (const r in e)
      ql(s, r, e[r]);
    if (t && !Dt(t))
      for (const r in t)
        e[r] == null && ql(s, r, "");
  } else {
    const r = s.display;
    n ? t !== e && (s.cssText = e) : t && i.removeAttribute("style"), "_vod" in i && (s.display = r);
  }
}
const cu = /\s*!important$/;
function ql(i, t, e) {
  if (st(e))
    e.forEach((s) => ql(i, t, s));
  else if (e == null && (e = ""), t.startsWith("--"))
    i.setProperty(t, e);
  else {
    const s = Y0(i, t);
    cu.test(e) ? i.setProperty(Os(s), e.replace(cu, ""), "important") : i[s] = e;
  }
}
const fu = ["Webkit", "Moz", "ms"], $o = {};
function Y0(i, t) {
  const e = $o[t];
  if (e)
    return e;
  let s = Ue(t);
  if (s !== "filter" && s in i)
    return $o[t] = s;
  s = lo(s);
  for (let n = 0; n < fu.length; n++) {
    const r = fu[n] + s;
    if (r in i)
      return $o[t] = r;
  }
  return t;
}
const du = "http://www.w3.org/1999/xlink";
function Z0(i, t, e, s, n) {
  if (s && t.startsWith("xlink:"))
    e == null ? i.removeAttributeNS(du, t.slice(6, t.length)) : i.setAttributeNS(du, t, e);
  else {
    const r = Xm(t);
    e == null || r && !Uf(e) ? i.removeAttribute(t) : i.setAttribute(t, r ? "" : e);
  }
}
function tb(i, t, e, s, n, r, o) {
  if (t === "innerHTML" || t === "textContent") {
    s && o(s, n, r), i[t] = e == null ? "" : e;
    return;
  }
  if (t === "value" && i.tagName !== "PROGRESS" && !i.tagName.includes("-")) {
    i._value = e;
    const a = e == null ? "" : e;
    (i.value !== a || i.tagName === "OPTION") && (i.value = a), e == null && i.removeAttribute(t);
    return;
  }
  let l = !1;
  if (e === "" || e == null) {
    const a = typeof i[t];
    a === "boolean" ? e = Uf(e) : e == null && a === "string" ? (e = "", l = !0) : a === "number" && (e = 0, l = !0);
  }
  try {
    i[t] = e;
  } catch (a) {
  }
  l && i.removeAttribute(t);
}
const [Ud, eb] = /* @__PURE__ */ (() => {
  let i = Date.now, t = !1;
  if (typeof window != "undefined") {
    Date.now() > document.createEvent("Event").timeStamp && (i = performance.now.bind(performance));
    const e = navigator.userAgent.match(/firefox\/(\d+)/i);
    t = !!(e && Number(e[1]) <= 53);
  }
  return [i, t];
})();
let Kl = 0;
const ib = /* @__PURE__ */ Promise.resolve(), sb = () => {
  Kl = 0;
}, nb = () => Kl || (ib.then(sb), Kl = Ud());
function rb(i, t, e, s) {
  i.addEventListener(t, e, s);
}
function ob(i, t, e, s) {
  i.removeEventListener(t, e, s);
}
function lb(i, t, e, s, n = null) {
  const r = i._vei || (i._vei = {}), o = r[t];
  if (s && o)
    o.value = s;
  else {
    const [l, a] = ab(t);
    if (s) {
      const h = r[t] = hb(s, n);
      rb(i, l, h, a);
    } else
      o && (ob(i, l, o, a), r[t] = void 0);
  }
}
const pu = /(?:Once|Passive|Capture)$/;
function ab(i) {
  let t;
  if (pu.test(i)) {
    t = {};
    let e;
    for (; e = i.match(pu); )
      i = i.slice(0, i.length - e[0].length), t[e[0].toLowerCase()] = !0;
  }
  return [Os(i.slice(2)), t];
}
function hb(i, t) {
  const e = (s) => {
    const n = s.timeStamp || Ud();
    (eb || n >= e.attached - 1) && Pe(ub(s, e.value), t, 5, [s]);
  };
  return e.value = i, e.attached = nb(), e;
}
function ub(i, t) {
  if (st(t)) {
    const e = i.stopImmediatePropagation;
    return i.stopImmediatePropagation = () => {
      e.call(i), i._stopped = !0;
    }, t.map((s) => (n) => !n._stopped && s && s(n));
  } else
    return t;
}
const gu = /^on[a-z]/, cb = (i, t, e, s, n = !1, r, o, l, a) => {
  t === "class" ? G0(i, s, n) : t === "style" ? J0(i, e, s) : no(t) ? Fa(t) || lb(i, t, e, s, o) : (t[0] === "." ? (t = t.slice(1), !0) : t[0] === "^" ? (t = t.slice(1), !1) : fb(i, t, s, n)) ? tb(i, t, s, r, o, l, a) : (t === "true-value" ? i._trueValue = s : t === "false-value" && (i._falseValue = s), Z0(i, t, s, n));
};
function fb(i, t, e, s) {
  return s ? !!(t === "innerHTML" || t === "textContent" || t in i && gu.test(t) && nt(e)) : t === "spellcheck" || t === "draggable" || t === "translate" || t === "form" || t === "list" && i.tagName === "INPUT" || t === "type" && i.tagName === "TEXTAREA" || gu.test(t) && Dt(e) ? !1 : t in i;
}
const db = ["ctrl", "shift", "alt", "meta"], pb = {
  stop: (i) => i.stopPropagation(),
  prevent: (i) => i.preventDefault(),
  self: (i) => i.target !== i.currentTarget,
  ctrl: (i) => !i.ctrlKey,
  shift: (i) => !i.shiftKey,
  alt: (i) => !i.altKey,
  meta: (i) => !i.metaKey,
  left: (i) => "button" in i && i.button !== 0,
  middle: (i) => "button" in i && i.button !== 1,
  right: (i) => "button" in i && i.button !== 2,
  exact: (i, t) => db.some((e) => i[`${e}Key`] && !t.includes(e))
}, Xl = (i, t) => (e, ...s) => {
  for (let n = 0; n < t.length; n++) {
    const r = pb[t[n]];
    if (r && r(e, t))
      return;
  }
  return i(e, ...s);
}, mu = {
  beforeMount(i, { value: t }, { transition: e }) {
    i._vod = i.style.display === "none" ? "" : i.style.display, e && t ? e.beforeEnter(i) : Rs(i, t);
  },
  mounted(i, { value: t }, { transition: e }) {
    e && t && e.enter(i);
  },
  updated(i, { value: t, oldValue: e }, { transition: s }) {
    !t != !e && (s ? t ? (s.beforeEnter(i), Rs(i, !0), s.enter(i)) : s.leave(i, () => {
      Rs(i, !1);
    }) : Rs(i, t));
  },
  beforeUnmount(i, { value: t }) {
    Rs(i, t);
  }
};
function Rs(i, t) {
  i.style.display = t ? i._vod : "none";
}
const gb = /* @__PURE__ */ Zt({ patchProp: cb }, X0);
let _u;
function mb() {
  return _u || (_u = P0(gb));
}
const _b = (...i) => {
  const t = mb().createApp(...i), { mount: e } = t;
  return t.mount = (s) => {
    const n = bb(s);
    if (!n)
      return;
    const r = t._component;
    !nt(r) && !r.render && !r.template && (r.template = n.innerHTML), n.innerHTML = "";
    const o = e(n, !1, n instanceof SVGElement);
    return n instanceof Element && (n.removeAttribute("v-cloak"), n.setAttribute("data-v-app", "")), o;
  }, t;
};
function bb(i) {
  return Dt(i) ? document.querySelector(i) : i;
}
const kn = (i, t) => {
  const e = i.__vccOpts || i;
  for (const [s, n] of t)
    e[s] = n;
  return e;
}, yb = {
  name: "Home",
  props: {
    urlEndPoint: String,
    username: String,
    password: String,
    mapToken: String,
    usageTrackingEnabled: String,
    api_token: String,
    supplierId: String,
    storeCountryCode: String
  },
  data() {
    return {
      errors: [],
      messages: [],
      pargoUser: {},
      mapUrl: ""
    };
  },
  mounted() {
    return _e(this, null, function* () {
      this.pargoUser = {
        urlEndPoint: this.urlEndPoint,
        username: this.username,
        password: this.password,
        mapToken: this.mapToken,
        usageTrackingEnabled: this.usageTrackingEnabled,
        api_token: this.api_token,
        supplierId: this.supplierId,
        storeCountryCode: this.storeCountryCode
      };
    });
  },
  methods: {
    generateNewTokenRequest() {
      return _e(this, null, function* () {
        this.messages = [], this.errors = [], confirm("You will need to notify Pargo of your new token, continue?") && (yield fetch(`${OBJ.api_url}pargo/v1/regenerate-token`, {
          method: "POST",
          headers: {
            "X-WP-Nonce": OBJ.nonce
          }
        }).then((i) => _e(this, null, function* () {
          if (!i.ok) {
            let t;
            throw yield i.json().then((e) => t = e), t.message ? Error(t.message) : Error(i.statusText);
          }
          return i.json();
        })).then((i) => _e(this, null, function* () {
          i.data && i.data.status && i.data.status === 500 && this.errors.push(i.message), i.api_token && (this.messages.push(i.message), i.api_token && (this.pargoUser.api_token = i.api_token, yield this.copyAPIToken()));
        })).catch((i) => {
          i.toString().includes("TypeError") ? this.errors.push("Please try again or contact support@pargo.co.za") : this.errors.push(i), console.error(i);
        }));
      });
    },
    copyAPIToken() {
      return _e(this, null, function* () {
        this.messages = [], yield navigator.clipboard.writeText(this.pargoUser.api_token).then(() => {
          this.messages.push("Copied Webhook Auth token");
        }).catch((i) => {
          this.errors.push("Failed to copy Webhook Auth token to clipboard, copy manually or contact support@pargo.co.za for assistance.");
        });
      });
    },
    saveVerifyCredentials() {
      return _e(this, null, function* () {
        if (this.messages = [], this.errors = [], this.pargoUser.username || this.errors.push("Your Pargo username is required."), this.pargoUser.password || this.errors.push("Your Pargo password is required."), this.pargoUser.urlEndPoint || this.errors.push("The Pargo API url is required."), this.pargoUser.supplierId.length > 0 && !this.pargoUser.supplierId.startsWith("sup") && this.errors.push('The Pargo Supplier ID must start with "sup".'), this.errors.length === 0) {
          this.messages.push("Step 1. Saving Credentials...");
          const i = new FormData();
          i.append("pargo_username", this.pargoUser.username), i.append("pargo_password", this.pargoUser.password), i.append("pargo_url_endpoint", this.pargoUser.urlEndPoint), i.append("pargo_map_token", this.pargoUser.mapToken), i.append("pargo_usage_tracking_enabled", this.pargoUser.usageTrackingEnabled), i.append("pargo_supplier_id", this.pargoUser.supplierId), yield fetch(`${OBJ.api_url}pargo/v1/store-credentials`, {
            method: "POST",
            body: i,
            headers: {
              "X-WP-Nonce": OBJ.nonce
            }
          }).then((t) => t.json()).then((t) => _e(this, null, function* () {
            this.messages.push(t.message), this.messages.push("Step 2. Verifying Credentials..."), yield fetch(`${OBJ.api_url}pargo/v1/verify-credentials`, {
              method: "POST",
              headers: {
                "X-WP-Nonce": OBJ.nonce
              }
            }).then((e) => e.json()).then((e) => {
              e.code === "success" ? this.messages.push(e.message) : this.errors.push(e.message);
            });
          })).catch((t) => {
            console.error(t);
          });
        }
      });
    }
  }
}, Nt = (i) => (yd("data-v-75ebaece"), i = i(), wd(), i), wb = { class: "home" }, vb = /* @__PURE__ */ Nt(() => /* @__PURE__ */ V("h2", null, "PARGO CREDENTIALS", -1)), xb = /* @__PURE__ */ Nt(() => /* @__PURE__ */ V("p", null, "When you are ready to move to production and start shipping real orders, select \u2018no\u2019, and ensure you update your username, password and map token with production credentials from Pargo.", -1)), kb = { class: "pargo_form_group pargo_form_group--testing" }, Ob = /* @__PURE__ */ Nt(() => /* @__PURE__ */ V("label", {
  class: "control-label",
  for: "pargo_using_test"
}, " Are you using Test / Staging Credentials? ", -1)), Sb = { for: "pargo_using_test_yes" }, Cb = ["checked"], Ab = /* @__PURE__ */ Ui(" Yes "), Tb = { for: "pargo_using_test_no" }, Pb = ["checked"], Eb = /* @__PURE__ */ Ui(" No "), Mb = /* @__PURE__ */ Nt(() => /* @__PURE__ */ V("p", {
  id: "pargo_using_testHelpBlock",
  class: "help-block"
}, "Select if you are using testing / staging credentials for debugging purposes.", -1)), Rb = { class: "pargo_form_group pargo_form_group--username" }, Db = /* @__PURE__ */ Nt(() => /* @__PURE__ */ V("label", {
  class: "control-label",
  for: "pargo_username"
}, "Pargo Username", -1)), Bb = ["value"], Nb = /* @__PURE__ */ Nt(() => /* @__PURE__ */ V("p", {
  id: "pargo_usernameHelpBlock",
  class: "help-block"
}, "Please enter your Pargo Account Username", -1)), Lb = {
  key: 0,
  class: "button button-pargo",
  href: "https://mypargo.pargo.co.za/mypargo/auth/sign-up?source=woocomm",
  target: "_blank"
}, Ib = { class: "pargo_form_group pargo_form_group--password" }, Qb = /* @__PURE__ */ Nt(() => /* @__PURE__ */ V("label", {
  class: "control-label",
  for: "pargo_password"
}, "Pargo Password", -1)), zb = ["value"], Wb = /* @__PURE__ */ Nt(() => /* @__PURE__ */ V("p", {
  id: "pargo_passwordHelpBlock",
  class: "help-block"
}, "Please enter your Pargo Account Password", -1)), $b = { class: "pargo_form_group pargo_form_group--map_token" }, Fb = /* @__PURE__ */ Nt(() => /* @__PURE__ */ V("label", {
  class: "control-label",
  for: "pargo_map_token_field"
}, "Pargo Map Token", -1)), Vb = ["value"], Ub = /* @__PURE__ */ Nt(() => /* @__PURE__ */ V("p", {
  id: "pargo_map_tokenHelpBlock",
  class: "help-block"
}, "Please enter your Pargo Account Map Token, if you do not have a token leave this field empty and the default token will be used.", -1)), jb = /* @__PURE__ */ Nt(() => /* @__PURE__ */ V("br", null, null, -1)), Hb = { class: "pargo_form_group pargo_form_group--analytics" }, qb = /* @__PURE__ */ Nt(() => /* @__PURE__ */ V("label", {
  for: "pargo_using_analytics",
  class: "control-label"
}, "Usage Insights", -1)), Kb = {
  for: "pargo_using_analytics",
  class: "control-label"
}, Xb = ["checked"], Gb = /* @__PURE__ */ Ui(" Enable Tracking "), Jb = /* @__PURE__ */ Nt(() => /* @__PURE__ */ V("p", {
  id: "pargo_using_analyticsHelpBlock",
  class: "help-block"
}, " Gathering usage data allows us to make the Pargo Pickup Points Plugin better \u2014 your store will be considered as we evaluate new features, judge the quality of an update, or determine if an improvement makes sense. To opt out, uncheck this box. ", -1)), Yb = { class: "pargo_form_group pargo_form_group--api_token" }, Zb = /* @__PURE__ */ Nt(() => /* @__PURE__ */ V("label", {
  for: "pargo_api_token",
  class: "control-label"
}, "Pargo order status update API token", -1)), ty = /* @__PURE__ */ Nt(() => /* @__PURE__ */ V("p", { class: "help-block" }, " Click on the text above to copy and provide this to your Pargo Support Representative to enable status updates. ", -1)), ey = { class: "pargo_form_group pargo_form_group--multistore" }, iy = /* @__PURE__ */ Nt(() => /* @__PURE__ */ V("label", {
  class: "control-label",
  for: "pargo_supplier_id"
}, [
  /* @__PURE__ */ Ui("Multiple stores setup with Pargo"),
  /* @__PURE__ */ V("br"),
  /* @__PURE__ */ Ui("(leave blank if not provided)")
], -1)), sy = ["value"], ny = /* @__PURE__ */ Nt(() => /* @__PURE__ */ V("p", {
  id: "pargo_supplier_idHelpBlock",
  class: "help-block"
}, "Supplier ID provided by Pargo.", -1)), ry = { key: 0 }, oy = { class: "errors" }, ly = { key: 1 }, ay = { class: "success" }, hy = /* @__PURE__ */ Nt(() => /* @__PURE__ */ V("hr", null, null, -1));
function uy(i, t, e, s, n, r) {
  return gt(), xt("div", wb, [
    vb,
    xb,
    V("form", null, [
      V("div", kb, [
        Ob,
        V("label", Sb, [
          V("input", {
            id: "pargo_using_test_yes",
            checked: n.pargoUser.urlEndPoint === "staging",
            "aria-describedby": "pargo_api_urlHelpBlock",
            name: "pargo_using_test",
            placeholder: "Pargo API Url",
            type: "radio",
            value: "staging",
            onInput: t[0] || (t[0] = (o) => {
              n.pargoUser.urlEndPoint = o.target.value, n.pargoUser.mapToken = "";
            })
          }, null, 40, Cb),
          Ab
        ]),
        V("label", Tb, [
          V("input", {
            id: "pargo_using_test_no",
            checked: n.pargoUser.urlEndPoint === "production",
            "aria-describedby": "pargo_api_urlHelpBlock",
            name: "pargo_using_test",
            placeholder: "Pargo API Url",
            type: "radio",
            value: "production",
            onInput: t[1] || (t[1] = (o) => {
              n.pargoUser.urlEndPoint = o.target.value, n.pargoUser.mapToken = "";
            })
          }, null, 40, Pb),
          Eb
        ]),
        Mb
      ]),
      V("div", Rb, [
        V("div", null, [
          Db,
          V("input", {
            id: "pargo_username",
            value: n.pargoUser.username,
            "aria-describedby": "pargo_usernameHelpBlock",
            autocomplete: "off",
            name: "pargo_username",
            placeholder: "Pargo Username",
            required: "required",
            type: "text",
            onInput: t[2] || (t[2] = (o) => n.pargoUser.username = o.target.value)
          }, null, 40, Bb),
          Nb
        ]),
        !n.pargoUser.username && n.pargoUser.storeCountryCode === "ZA" && n.pargoUser.urlEndPoint === "production" ? (gt(), xt("a", Lb, "Sign Up")) : di("", !0)
      ]),
      V("div", Ib, [
        Qb,
        V("input", {
          id: "pargo_password",
          value: n.pargoUser.password,
          "aria-describedby": "pargo_passwordHelpBlock",
          autocomplete: "off",
          name: "pargo_password",
          placeholder: "Pargo Password",
          required: "required",
          type: "password",
          onInput: t[3] || (t[3] = (o) => n.pargoUser.password = o.target.value)
        }, null, 40, zb),
        Wb
      ]),
      V("div", $b, [
        Fb,
        V("input", {
          id: "pargo_map_token_field",
          value: n.pargoUser.mapToken,
          "aria-describedby": "pargo_map_tokenHelpBlock",
          autocomplete: "off",
          name: "pargo_map_token",
          placeholder: "Pargo Map Token",
          type: "text",
          onInput: t[4] || (t[4] = (o) => n.pargoUser.mapToken = o.target.value)
        }, null, 40, Vb),
        Ub
      ]),
      V("button", {
        class: "button button-secondary",
        type: "button",
        onClick: t[5] || (t[5] = (o) => i.$emit("test-map-token", n.pargoUser))
      }, "Test Your Map Token "),
      jb,
      V("div", Hb, [
        qb,
        V("label", Kb, [
          V("input", {
            id: "pargo_using_analytics",
            name: "pargo_using_analytics",
            type: "checkbox",
            "aria-describedby": "pargo_using_analyticsHelpBlock",
            checked: n.pargoUser.usageTrackingEnabled === "true",
            onInput: t[6] || (t[6] = (o) => {
              n.pargoUser.usageTrackingEnabled = o.target.checked;
            })
          }, null, 40, Xb),
          Gb
        ]),
        Jb
      ]),
      V("div", Yb, [
        Zb,
        V("textarea", {
          id: "pargo_api_token",
          readOnly: "",
          rows: "10",
          onClick: t[7] || (t[7] = (...o) => r.copyAPIToken && r.copyAPIToken(...o))
        }, Xs(n.pargoUser.api_token), 1),
        V("button", {
          class: "button button-pargo",
          type: "button",
          onClick: t[8] || (t[8] = (...o) => r.generateNewTokenRequest && r.generateNewTokenRequest(...o))
        }, "Generate New Token"),
        ty
      ]),
      V("div", ey, [
        V("div", null, [
          iy,
          V("input", {
            id: "pargo_supplier_id",
            value: n.pargoUser.supplierId,
            "aria-describedby": "pargo_supplier_idHelpBlock",
            autocomplete: "off",
            name: "pargo_supplier_id",
            placeholder: "supXXXX",
            required: "required",
            type: "text",
            onInput: t[9] || (t[9] = (o) => n.pargoUser.supplierId = o.target.value)
          }, null, 40, sy),
          ny
        ])
      ]),
      n.errors.length ? (gt(), xt("div", ry, [
        V("ul", oy, [
          (gt(!0), xt(he, null, Sr(n.errors, (o, l) => (gt(), xt("li", { key: l }, Xs(o), 1))), 128))
        ])
      ])) : di("", !0),
      n.messages.length ? (gt(), xt("div", ly, [
        V("ul", ay, [
          (gt(!0), xt(he, null, Sr(n.messages, (o, l) => (gt(), xt("li", { key: l }, Xs(o), 1))), 128))
        ])
      ])) : di("", !0),
      hy,
      V("button", {
        class: "button button-primary",
        type: "submit",
        onClick: t[10] || (t[10] = Xl((...o) => r.saveVerifyCredentials && r.saveVerifyCredentials(...o), ["prevent"]))
      }, "Save and Verify Credentials")
    ])
  ]);
}
const cy = /* @__PURE__ */ kn(yb, [["render", uy], ["__scopeId", "data-v-75ebaece"]]);
class ct {
  constructor() {
  }
  lineAt(t) {
    if (t < 0 || t > this.length)
      throw new RangeError(`Invalid position ${t} in document of length ${this.length}`);
    return this.lineInner(t, !1, 1, 0);
  }
  line(t) {
    if (t < 1 || t > this.lines)
      throw new RangeError(`Invalid line number ${t} in ${this.lines}-line document`);
    return this.lineInner(t, !0, 1, 0);
  }
  replace(t, e, s) {
    let n = [];
    return this.decompose(0, t, n, 2), s.length && s.decompose(0, s.length, n, 3), this.decompose(e, this.length, n, 1), We.from(n, this.length - (e - t) + s.length);
  }
  append(t) {
    return this.replace(this.length, this.length, t);
  }
  slice(t, e = this.length) {
    let s = [];
    return this.decompose(t, e, s, 0), We.from(s, e - t);
  }
  eq(t) {
    if (t == this)
      return !0;
    if (t.length != this.length || t.lines != this.lines)
      return !1;
    let e = this.scanIdentical(t, 1), s = this.length - this.scanIdentical(t, -1), n = new Zs(this), r = new Zs(t);
    for (let o = e, l = e; ; ) {
      if (n.next(o), r.next(o), o = 0, n.lineBreak != r.lineBreak || n.done != r.done || n.value != r.value)
        return !1;
      if (l += n.value.length, n.done || l >= s)
        return !0;
    }
  }
  iter(t = 1) {
    return new Zs(this, t);
  }
  iterRange(t, e = this.length) {
    return new jd(this, t, e);
  }
  iterLines(t, e) {
    let s;
    if (t == null)
      s = this.iter();
    else {
      e == null && (e = this.lines + 1);
      let n = this.line(t).from;
      s = this.iterRange(n, Math.max(n, e == this.lines + 1 ? this.length : e <= 1 ? 0 : this.line(e - 1).to));
    }
    return new Hd(s);
  }
  toString() {
    return this.sliceString(0);
  }
  toJSON() {
    let t = [];
    return this.flatten(t), t;
  }
  static of(t) {
    if (t.length == 0)
      throw new RangeError("A document must have at least one line");
    return t.length == 1 && !t[0] ? ct.empty : t.length <= 32 ? new Ct(t) : We.from(Ct.split(t, []));
  }
}
class Ct extends ct {
  constructor(t, e = fy(t)) {
    super(), this.text = t, this.length = e;
  }
  get lines() {
    return this.text.length;
  }
  get children() {
    return null;
  }
  lineInner(t, e, s, n) {
    for (let r = 0; ; r++) {
      let o = this.text[r], l = n + o.length;
      if ((e ? s : l) >= t)
        return new dy(n, l, s, o);
      n = l + 1, s++;
    }
  }
  decompose(t, e, s, n) {
    let r = t <= 0 && e >= this.length ? this : new Ct(bu(this.text, t, e), Math.min(e, this.length) - Math.max(0, t));
    if (n & 1) {
      let o = s.pop(), l = cr(r.text, o.text.slice(), 0, r.length);
      if (l.length <= 32)
        s.push(new Ct(l, o.length + r.length));
      else {
        let a = l.length >> 1;
        s.push(new Ct(l.slice(0, a)), new Ct(l.slice(a)));
      }
    } else
      s.push(r);
  }
  replace(t, e, s) {
    if (!(s instanceof Ct))
      return super.replace(t, e, s);
    let n = cr(this.text, cr(s.text, bu(this.text, 0, t)), e), r = this.length + s.length - (e - t);
    return n.length <= 32 ? new Ct(n, r) : We.from(Ct.split(n, []), r);
  }
  sliceString(t, e = this.length, s = `
`) {
    let n = "";
    for (let r = 0, o = 0; r <= e && o < this.text.length; o++) {
      let l = this.text[o], a = r + l.length;
      r > t && o && (n += s), t < a && e > r && (n += l.slice(Math.max(0, t - r), e - r)), r = a + 1;
    }
    return n;
  }
  flatten(t) {
    for (let e of this.text)
      t.push(e);
  }
  scanIdentical() {
    return 0;
  }
  static split(t, e) {
    let s = [], n = -1;
    for (let r of t)
      s.push(r), n += r.length + 1, s.length == 32 && (e.push(new Ct(s, n)), s = [], n = -1);
    return n > -1 && e.push(new Ct(s, n)), e;
  }
}
class We extends ct {
  constructor(t, e) {
    super(), this.children = t, this.length = e, this.lines = 0;
    for (let s of t)
      this.lines += s.lines;
  }
  lineInner(t, e, s, n) {
    for (let r = 0; ; r++) {
      let o = this.children[r], l = n + o.length, a = s + o.lines - 1;
      if ((e ? a : l) >= t)
        return o.lineInner(t, e, s, n);
      n = l + 1, s = a + 1;
    }
  }
  decompose(t, e, s, n) {
    for (let r = 0, o = 0; o <= e && r < this.children.length; r++) {
      let l = this.children[r], a = o + l.length;
      if (t <= a && e >= o) {
        let h = n & ((o <= t ? 1 : 0) | (a >= e ? 2 : 0));
        o >= t && a <= e && !h ? s.push(l) : l.decompose(t - o, e - o, s, h);
      }
      o = a + 1;
    }
  }
  replace(t, e, s) {
    if (s.lines < this.lines)
      for (let n = 0, r = 0; n < this.children.length; n++) {
        let o = this.children[n], l = r + o.length;
        if (t >= r && e <= l) {
          let a = o.replace(t - r, e - r, s), h = this.lines - o.lines + a.lines;
          if (a.lines < h >> 5 - 1 && a.lines > h >> 5 + 1) {
            let u = this.children.slice();
            return u[n] = a, new We(u, this.length - (e - t) + s.length);
          }
          return super.replace(r, l, a);
        }
        r = l + 1;
      }
    return super.replace(t, e, s);
  }
  sliceString(t, e = this.length, s = `
`) {
    let n = "";
    for (let r = 0, o = 0; r < this.children.length && o <= e; r++) {
      let l = this.children[r], a = o + l.length;
      o > t && r && (n += s), t < a && e > o && (n += l.sliceString(t - o, e - o, s)), o = a + 1;
    }
    return n;
  }
  flatten(t) {
    for (let e of this.children)
      e.flatten(t);
  }
  scanIdentical(t, e) {
    if (!(t instanceof We))
      return 0;
    let s = 0, [n, r, o, l] = e > 0 ? [0, 0, this.children.length, t.children.length] : [this.children.length - 1, t.children.length - 1, -1, -1];
    for (; ; n += e, r += e) {
      if (n == o || r == l)
        return s;
      let a = this.children[n], h = t.children[r];
      if (a != h)
        return s + a.scanIdentical(h, e);
      s += a.length + 1;
    }
  }
  static from(t, e = t.reduce((s, n) => s + n.length + 1, -1)) {
    let s = 0;
    for (let g of t)
      s += g.lines;
    if (s < 32) {
      let g = [];
      for (let _ of t)
        _.flatten(g);
      return new Ct(g, e);
    }
    let n = Math.max(32, s >> 5), r = n << 1, o = n >> 1, l = [], a = 0, h = -1, u = [];
    function c(g) {
      let _;
      if (g.lines > r && g instanceof We)
        for (let A of g.children)
          c(A);
      else
        g.lines > o && (a > o || !a) ? (f(), l.push(g)) : g instanceof Ct && a && (_ = u[u.length - 1]) instanceof Ct && g.lines + _.lines <= 32 ? (a += g.lines, h += g.length + 1, u[u.length - 1] = new Ct(_.text.concat(g.text), _.length + 1 + g.length)) : (a + g.lines > n && f(), a += g.lines, h += g.length + 1, u.push(g));
    }
    function f() {
      a != 0 && (l.push(u.length == 1 ? u[0] : We.from(u, h)), h = -1, a = u.length = 0);
    }
    for (let g of t)
      c(g);
    return f(), l.length == 1 ? l[0] : new We(l, e);
  }
}
ct.empty = /* @__PURE__ */ new Ct([""], 0);
function fy(i) {
  let t = -1;
  for (let e of i)
    t += e.length + 1;
  return t;
}
function cr(i, t, e = 0, s = 1e9) {
  for (let n = 0, r = 0, o = !0; r < i.length && n <= s; r++) {
    let l = i[r], a = n + l.length;
    a >= e && (a > s && (l = l.slice(0, s - n)), n < e && (l = l.slice(e - n)), o ? (t[t.length - 1] += l, o = !1) : t.push(l)), n = a + 1;
  }
  return t;
}
function bu(i, t, e) {
  return cr(i, [""], t, e);
}
class Zs {
  constructor(t, e = 1) {
    this.dir = e, this.done = !1, this.lineBreak = !1, this.value = "", this.nodes = [t], this.offsets = [e > 0 ? 1 : (t instanceof Ct ? t.text.length : t.children.length) << 1];
  }
  nextInner(t, e) {
    for (this.done = this.lineBreak = !1; ; ) {
      let s = this.nodes.length - 1, n = this.nodes[s], r = this.offsets[s], o = r >> 1, l = n instanceof Ct ? n.text.length : n.children.length;
      if (o == (e > 0 ? l : 0)) {
        if (s == 0)
          return this.done = !0, this.value = "", this;
        e > 0 && this.offsets[s - 1]++, this.nodes.pop(), this.offsets.pop();
      } else if ((r & 1) == (e > 0 ? 0 : 1)) {
        if (this.offsets[s] += e, t == 0)
          return this.lineBreak = !0, this.value = `
`, this;
        t--;
      } else if (n instanceof Ct) {
        let a = n.text[o + (e < 0 ? -1 : 0)];
        if (this.offsets[s] += e, a.length > Math.max(0, t))
          return this.value = t == 0 ? a : e > 0 ? a.slice(t) : a.slice(0, a.length - t), this;
        t -= a.length;
      } else {
        let a = n.children[o + (e < 0 ? -1 : 0)];
        t > a.length ? (t -= a.length, this.offsets[s] += e) : (e < 0 && this.offsets[s]--, this.nodes.push(a), this.offsets.push(e > 0 ? 1 : (a instanceof Ct ? a.text.length : a.children.length) << 1));
      }
    }
  }
  next(t = 0) {
    return t < 0 && (this.nextInner(-t, -this.dir), t = this.value.length), this.nextInner(t, this.dir);
  }
}
class jd {
  constructor(t, e, s) {
    this.value = "", this.done = !1, this.cursor = new Zs(t, e > s ? -1 : 1), this.pos = e > s ? t.length : 0, this.from = Math.min(e, s), this.to = Math.max(e, s);
  }
  nextInner(t, e) {
    if (e < 0 ? this.pos <= this.from : this.pos >= this.to)
      return this.value = "", this.done = !0, this;
    t += Math.max(0, e < 0 ? this.pos - this.to : this.from - this.pos);
    let s = e < 0 ? this.pos - this.from : this.to - this.pos;
    t > s && (t = s), s -= t;
    let { value: n } = this.cursor.next(t);
    return this.pos += (n.length + t) * e, this.value = n.length <= s ? n : e < 0 ? n.slice(n.length - s) : n.slice(0, s), this.done = !this.value, this;
  }
  next(t = 0) {
    return t < 0 ? t = Math.max(t, this.from - this.pos) : t > 0 && (t = Math.min(t, this.to - this.pos)), this.nextInner(t, this.cursor.dir);
  }
  get lineBreak() {
    return this.cursor.lineBreak && this.value != "";
  }
}
class Hd {
  constructor(t) {
    this.inner = t, this.afterBreak = !0, this.value = "", this.done = !1;
  }
  next(t = 0) {
    let { done: e, lineBreak: s, value: n } = this.inner.next(t);
    return e ? (this.done = !0, this.value = "") : s ? this.afterBreak ? this.value = "" : (this.afterBreak = !0, this.next()) : (this.value = n, this.afterBreak = !1), this;
  }
  get lineBreak() {
    return !1;
  }
}
typeof Symbol != "undefined" && (ct.prototype[Symbol.iterator] = function() {
  return this.iter();
}, Zs.prototype[Symbol.iterator] = jd.prototype[Symbol.iterator] = Hd.prototype[Symbol.iterator] = function() {
  return this;
});
class dy {
  constructor(t, e, s, n) {
    this.from = t, this.to = e, this.number = s, this.text = n;
  }
  get length() {
    return this.to - this.from;
  }
}
let us = /* @__PURE__ */ "lc,34,7n,7,7b,19,,,,2,,2,,,20,b,1c,l,g,,2t,7,2,6,2,2,,4,z,,u,r,2j,b,1m,9,9,,o,4,,9,,3,,5,17,3,3b,f,,w,1j,,,,4,8,4,,3,7,a,2,t,,1m,,,,2,4,8,,9,,a,2,q,,2,2,1l,,4,2,4,2,2,3,3,,u,2,3,,b,2,1l,,4,5,,2,4,,k,2,m,6,,,1m,,,2,,4,8,,7,3,a,2,u,,1n,,,,c,,9,,14,,3,,1l,3,5,3,,4,7,2,b,2,t,,1m,,2,,2,,3,,5,2,7,2,b,2,s,2,1l,2,,,2,4,8,,9,,a,2,t,,20,,4,,2,3,,,8,,29,,2,7,c,8,2q,,2,9,b,6,22,2,r,,,,,,1j,e,,5,,2,5,b,,10,9,,2u,4,,6,,2,2,2,p,2,4,3,g,4,d,,2,2,6,,f,,jj,3,qa,3,t,3,t,2,u,2,1s,2,,7,8,,2,b,9,,19,3,3b,2,y,,3a,3,4,2,9,,6,3,63,2,2,,1m,,,7,,,,,2,8,6,a,2,,1c,h,1r,4,1c,7,,,5,,14,9,c,2,w,4,2,2,,3,1k,,,2,3,,,3,1m,8,2,2,48,3,,d,,7,4,,6,,3,2,5i,1m,,5,ek,,5f,x,2da,3,3x,,2o,w,fe,6,2x,2,n9w,4,,a,w,2,28,2,7k,,3,,4,,p,2,5,,47,2,q,i,d,,12,8,p,b,1a,3,1c,,2,4,2,2,13,,1v,6,2,2,2,2,c,,8,,1b,,1f,,,3,2,2,5,2,,,16,2,8,,6m,,2,,4,,fn4,,kh,g,g,g,a6,2,gt,,6a,,45,5,1ae,3,,2,5,4,14,3,4,,4l,2,fx,4,ar,2,49,b,4w,,1i,f,1k,3,1d,4,2,2,1x,3,10,5,,8,1q,,c,2,1g,9,a,4,2,,2n,3,2,,,2,6,,4g,,3,8,l,2,1l,2,,,,,m,,e,7,3,5,5f,8,2,3,,,n,,29,,2,6,,,2,,,2,,2,6j,,2,4,6,2,,2,r,2,2d,8,2,,,2,2y,,,,2,6,,,2t,3,2,4,,5,77,9,,2,6t,,a,2,,,4,,40,4,2,2,4,,w,a,14,6,2,4,8,,9,6,2,3,1a,d,,2,ba,7,,6,,,2a,m,2,7,,2,,2,3e,6,3,,,2,,7,,,20,2,3,,,,9n,2,f0b,5,1n,7,t4,,1r,4,29,,f5k,2,43q,,,3,4,5,8,8,2,7,u,4,44,3,1iz,1j,4,1e,8,,e,,m,5,,f,11s,7,,h,2,7,,2,,5,79,7,c5,4,15s,7,31,7,240,5,gx7k,2o,3k,6o".split(",").map((i) => i ? parseInt(i, 36) : 1);
for (let i = 1; i < us.length; i++)
  us[i] += us[i - 1];
function py(i) {
  for (let t = 1; t < us.length; t += 2)
    if (us[t] > i)
      return us[t - 1] <= i;
  return !1;
}
function yu(i) {
  return i >= 127462 && i <= 127487;
}
const wu = 8205;
function de(i, t, e = !0, s = !0) {
  return (e ? qd : gy)(i, t, s);
}
function qd(i, t, e) {
  if (t == i.length)
    return t;
  t && Kd(i.charCodeAt(t)) && Xd(i.charCodeAt(t - 1)) && t--;
  let s = It(i, t);
  for (t += ue(s); t < i.length; ) {
    let n = It(i, t);
    if (s == wu || n == wu || e && py(n))
      t += ue(n), s = n;
    else if (yu(n)) {
      let r = 0, o = t - 2;
      for (; o >= 0 && yu(It(i, o)); )
        r++, o -= 2;
      if (r % 2 == 0)
        break;
      t += 2;
    } else
      break;
  }
  return t;
}
function gy(i, t, e) {
  for (; t > 0; ) {
    let s = qd(i, t - 2, e);
    if (s < t)
      return s;
    t--;
  }
  return 0;
}
function Kd(i) {
  return i >= 56320 && i < 57344;
}
function Xd(i) {
  return i >= 55296 && i < 56320;
}
function It(i, t) {
  let e = i.charCodeAt(t);
  if (!Xd(e) || t + 1 == i.length)
    return e;
  let s = i.charCodeAt(t + 1);
  return Kd(s) ? (e - 55296 << 10) + (s - 56320) + 65536 : e;
}
function nh(i) {
  return i <= 65535 ? String.fromCharCode(i) : (i -= 65536, String.fromCharCode((i >> 10) + 55296, (i & 1023) + 56320));
}
function ue(i) {
  return i < 65536 ? 1 : 2;
}
const Gl = /\r\n?|\n/;
var Gt = /* @__PURE__ */ function(i) {
  return i[i.Simple = 0] = "Simple", i[i.TrackDel = 1] = "TrackDel", i[i.TrackBefore = 2] = "TrackBefore", i[i.TrackAfter = 3] = "TrackAfter", i;
}(Gt || (Gt = {}));
class Ve {
  constructor(t) {
    this.sections = t;
  }
  get length() {
    let t = 0;
    for (let e = 0; e < this.sections.length; e += 2)
      t += this.sections[e];
    return t;
  }
  get newLength() {
    let t = 0;
    for (let e = 0; e < this.sections.length; e += 2) {
      let s = this.sections[e + 1];
      t += s < 0 ? this.sections[e] : s;
    }
    return t;
  }
  get empty() {
    return this.sections.length == 0 || this.sections.length == 2 && this.sections[1] < 0;
  }
  iterGaps(t) {
    for (let e = 0, s = 0, n = 0; e < this.sections.length; ) {
      let r = this.sections[e++], o = this.sections[e++];
      o < 0 ? (t(s, n, r), n += r) : n += o, s += r;
    }
  }
  iterChangedRanges(t, e = !1) {
    Jl(this, t, e);
  }
  get invertedDesc() {
    let t = [];
    for (let e = 0; e < this.sections.length; ) {
      let s = this.sections[e++], n = this.sections[e++];
      n < 0 ? t.push(s, n) : t.push(n, s);
    }
    return new Ve(t);
  }
  composeDesc(t) {
    return this.empty ? t : t.empty ? this : Gd(this, t);
  }
  mapDesc(t, e = !1) {
    return t.empty ? this : Yl(this, t, e);
  }
  mapPos(t, e = -1, s = Gt.Simple) {
    let n = 0, r = 0;
    for (let o = 0; o < this.sections.length; ) {
      let l = this.sections[o++], a = this.sections[o++], h = n + l;
      if (a < 0) {
        if (h > t)
          return r + (t - n);
        r += l;
      } else {
        if (s != Gt.Simple && h >= t && (s == Gt.TrackDel && n < t && h > t || s == Gt.TrackBefore && n < t || s == Gt.TrackAfter && h > t))
          return null;
        if (h > t || h == t && e < 0 && !l)
          return t == n || e < 0 ? r : r + a;
        r += a;
      }
      n = h;
    }
    if (t > n)
      throw new RangeError(`Position ${t} is out of range for changeset of length ${n}`);
    return r;
  }
  touchesRange(t, e = t) {
    for (let s = 0, n = 0; s < this.sections.length && n <= e; ) {
      let r = this.sections[s++], o = this.sections[s++], l = n + r;
      if (o >= 0 && n <= e && l >= t)
        return n < t && l > e ? "cover" : !0;
      n = l;
    }
    return !1;
  }
  toString() {
    let t = "";
    for (let e = 0; e < this.sections.length; ) {
      let s = this.sections[e++], n = this.sections[e++];
      t += (t ? " " : "") + s + (n >= 0 ? ":" + n : "");
    }
    return t;
  }
  toJSON() {
    return this.sections;
  }
  static fromJSON(t) {
    if (!Array.isArray(t) || t.length % 2 || t.some((e) => typeof e != "number"))
      throw new RangeError("Invalid JSON representation of ChangeDesc");
    return new Ve(t);
  }
  static create(t) {
    return new Ve(t);
  }
}
class Pt extends Ve {
  constructor(t, e) {
    super(t), this.inserted = e;
  }
  apply(t) {
    if (this.length != t.length)
      throw new RangeError("Applying change set to a document with the wrong length");
    return Jl(this, (e, s, n, r, o) => t = t.replace(n, n + (s - e), o), !1), t;
  }
  mapDesc(t, e = !1) {
    return Yl(this, t, e, !0);
  }
  invert(t) {
    let e = this.sections.slice(), s = [];
    for (let n = 0, r = 0; n < e.length; n += 2) {
      let o = e[n], l = e[n + 1];
      if (l >= 0) {
        e[n] = l, e[n + 1] = o;
        let a = n >> 1;
        for (; s.length < a; )
          s.push(ct.empty);
        s.push(o ? t.slice(r, r + o) : ct.empty);
      }
      r += o;
    }
    return new Pt(e, s);
  }
  compose(t) {
    return this.empty ? t : t.empty ? this : Gd(this, t, !0);
  }
  map(t, e = !1) {
    return t.empty ? this : Yl(this, t, e, !0);
  }
  iterChanges(t, e = !1) {
    Jl(this, t, e);
  }
  get desc() {
    return Ve.create(this.sections);
  }
  filter(t) {
    let e = [], s = [], n = [], r = new fn(this);
    t:
      for (let o = 0, l = 0; ; ) {
        let a = o == t.length ? 1e9 : t[o++];
        for (; l < a || l == a && r.len == 0; ) {
          if (r.done)
            break t;
          let u = Math.min(r.len, a - l);
          Ht(n, u, -1);
          let c = r.ins == -1 ? -1 : r.off == 0 ? r.ins : 0;
          Ht(e, u, c), c > 0 && pi(s, e, r.text), r.forward(u), l += u;
        }
        let h = t[o++];
        for (; l < h; ) {
          if (r.done)
            break t;
          let u = Math.min(r.len, h - l);
          Ht(e, u, -1), Ht(n, u, r.ins == -1 ? -1 : r.off == 0 ? r.ins : 0), r.forward(u), l += u;
        }
      }
    return {
      changes: new Pt(e, s),
      filtered: Ve.create(n)
    };
  }
  toJSON() {
    let t = [];
    for (let e = 0; e < this.sections.length; e += 2) {
      let s = this.sections[e], n = this.sections[e + 1];
      n < 0 ? t.push(s) : n == 0 ? t.push([s]) : t.push([s].concat(this.inserted[e >> 1].toJSON()));
    }
    return t;
  }
  static of(t, e, s) {
    let n = [], r = [], o = 0, l = null;
    function a(u = !1) {
      if (!u && !n.length)
        return;
      o < e && Ht(n, e - o, -1);
      let c = new Pt(n, r);
      l = l ? l.compose(c.map(l)) : c, n = [], r = [], o = 0;
    }
    function h(u) {
      if (Array.isArray(u))
        for (let c of u)
          h(c);
      else if (u instanceof Pt) {
        if (u.length != e)
          throw new RangeError(`Mismatched change set length (got ${u.length}, expected ${e})`);
        a(), l = l ? l.compose(u.map(l)) : u;
      } else {
        let { from: c, to: f = c, insert: g } = u;
        if (c > f || c < 0 || f > e)
          throw new RangeError(`Invalid change range ${c} to ${f} (in doc of length ${e})`);
        let _ = g ? typeof g == "string" ? ct.of(g.split(s || Gl)) : g : ct.empty, A = _.length;
        if (c == f && A == 0)
          return;
        c < o && a(), c > o && Ht(n, c - o, -1), Ht(n, f - c, A), pi(r, n, _), o = f;
      }
    }
    return h(t), a(!l), l;
  }
  static empty(t) {
    return new Pt(t ? [t, -1] : [], []);
  }
  static fromJSON(t) {
    if (!Array.isArray(t))
      throw new RangeError("Invalid JSON representation of ChangeSet");
    let e = [], s = [];
    for (let n = 0; n < t.length; n++) {
      let r = t[n];
      if (typeof r == "number")
        e.push(r, -1);
      else {
        if (!Array.isArray(r) || typeof r[0] != "number" || r.some((o, l) => l && typeof o != "string"))
          throw new RangeError("Invalid JSON representation of ChangeSet");
        if (r.length == 1)
          e.push(r[0], 0);
        else {
          for (; s.length < n; )
            s.push(ct.empty);
          s[n] = ct.of(r.slice(1)), e.push(r[0], s[n].length);
        }
      }
    }
    return new Pt(e, s);
  }
  static createSet(t, e) {
    return new Pt(t, e);
  }
}
function Ht(i, t, e, s = !1) {
  if (t == 0 && e <= 0)
    return;
  let n = i.length - 2;
  n >= 0 && e <= 0 && e == i[n + 1] ? i[n] += t : t == 0 && i[n] == 0 ? i[n + 1] += e : s ? (i[n] += t, i[n + 1] += e) : i.push(t, e);
}
function pi(i, t, e) {
  if (e.length == 0)
    return;
  let s = t.length - 2 >> 1;
  if (s < i.length)
    i[i.length - 1] = i[i.length - 1].append(e);
  else {
    for (; i.length < s; )
      i.push(ct.empty);
    i.push(e);
  }
}
function Jl(i, t, e) {
  let s = i.inserted;
  for (let n = 0, r = 0, o = 0; o < i.sections.length; ) {
    let l = i.sections[o++], a = i.sections[o++];
    if (a < 0)
      n += l, r += l;
    else {
      let h = n, u = r, c = ct.empty;
      for (; h += l, u += a, a && s && (c = c.append(s[o - 2 >> 1])), !(e || o == i.sections.length || i.sections[o + 1] < 0); )
        l = i.sections[o++], a = i.sections[o++];
      t(n, h, r, u, c), n = h, r = u;
    }
  }
}
function Yl(i, t, e, s = !1) {
  let n = [], r = s ? [] : null, o = new fn(i), l = new fn(t);
  for (let a = -1; ; )
    if (o.ins == -1 && l.ins == -1) {
      let h = Math.min(o.len, l.len);
      Ht(n, h, -1), o.forward(h), l.forward(h);
    } else if (l.ins >= 0 && (o.ins < 0 || a == o.i || o.off == 0 && (l.len < o.len || l.len == o.len && !e))) {
      let h = l.len;
      for (Ht(n, l.ins, -1); h; ) {
        let u = Math.min(o.len, h);
        o.ins >= 0 && a < o.i && o.len <= u && (Ht(n, 0, o.ins), r && pi(r, n, o.text), a = o.i), o.forward(u), h -= u;
      }
      l.next();
    } else if (o.ins >= 0) {
      let h = 0, u = o.len;
      for (; u; )
        if (l.ins == -1) {
          let c = Math.min(u, l.len);
          h += c, u -= c, l.forward(c);
        } else if (l.ins == 0 && l.len < u)
          u -= l.len, l.next();
        else
          break;
      Ht(n, h, a < o.i ? o.ins : 0), r && a < o.i && pi(r, n, o.text), a = o.i, o.forward(o.len - u);
    } else {
      if (o.done && l.done)
        return r ? Pt.createSet(n, r) : Ve.create(n);
      throw new Error("Mismatched change set lengths");
    }
}
function Gd(i, t, e = !1) {
  let s = [], n = e ? [] : null, r = new fn(i), o = new fn(t);
  for (let l = !1; ; ) {
    if (r.done && o.done)
      return n ? Pt.createSet(s, n) : Ve.create(s);
    if (r.ins == 0)
      Ht(s, r.len, 0, l), r.next();
    else if (o.len == 0 && !o.done)
      Ht(s, 0, o.ins, l), n && pi(n, s, o.text), o.next();
    else {
      if (r.done || o.done)
        throw new Error("Mismatched change set lengths");
      {
        let a = Math.min(r.len2, o.len), h = s.length;
        if (r.ins == -1) {
          let u = o.ins == -1 ? -1 : o.off ? 0 : o.ins;
          Ht(s, a, u, l), n && u && pi(n, s, o.text);
        } else
          o.ins == -1 ? (Ht(s, r.off ? 0 : r.len, a, l), n && pi(n, s, r.textBit(a))) : (Ht(s, r.off ? 0 : r.len, o.off ? 0 : o.ins, l), n && !o.off && pi(n, s, o.text));
        l = (r.ins > a || o.ins >= 0 && o.len > a) && (l || s.length > h), r.forward2(a), o.forward(a);
      }
    }
  }
}
class fn {
  constructor(t) {
    this.set = t, this.i = 0, this.next();
  }
  next() {
    let { sections: t } = this.set;
    this.i < t.length ? (this.len = t[this.i++], this.ins = t[this.i++]) : (this.len = 0, this.ins = -2), this.off = 0;
  }
  get done() {
    return this.ins == -2;
  }
  get len2() {
    return this.ins < 0 ? this.len : this.ins;
  }
  get text() {
    let { inserted: t } = this.set, e = this.i - 2 >> 1;
    return e >= t.length ? ct.empty : t[e];
  }
  textBit(t) {
    let { inserted: e } = this.set, s = this.i - 2 >> 1;
    return s >= e.length && !t ? ct.empty : e[s].slice(this.off, t == null ? void 0 : this.off + t);
  }
  forward(t) {
    t == this.len ? this.next() : (this.len -= t, this.off += t);
  }
  forward2(t) {
    this.ins == -1 ? this.forward(t) : t == this.ins ? this.next() : (this.ins -= t, this.off += t);
  }
}
class Ii {
  constructor(t, e, s) {
    this.from = t, this.to = e, this.flags = s;
  }
  get anchor() {
    return this.flags & 16 ? this.to : this.from;
  }
  get head() {
    return this.flags & 16 ? this.from : this.to;
  }
  get empty() {
    return this.from == this.to;
  }
  get assoc() {
    return this.flags & 4 ? -1 : this.flags & 8 ? 1 : 0;
  }
  get bidiLevel() {
    let t = this.flags & 3;
    return t == 3 ? null : t;
  }
  get goalColumn() {
    let t = this.flags >> 5;
    return t == 33554431 ? void 0 : t;
  }
  map(t, e = -1) {
    let s, n;
    return this.empty ? s = n = t.mapPos(this.from, e) : (s = t.mapPos(this.from, 1), n = t.mapPos(this.to, -1)), s == this.from && n == this.to ? this : new Ii(s, n, this.flags);
  }
  extend(t, e = t) {
    if (t <= this.anchor && e >= this.anchor)
      return R.range(t, e);
    let s = Math.abs(t - this.anchor) > Math.abs(e - this.anchor) ? t : e;
    return R.range(this.anchor, s);
  }
  eq(t) {
    return this.anchor == t.anchor && this.head == t.head;
  }
  toJSON() {
    return { anchor: this.anchor, head: this.head };
  }
  static fromJSON(t) {
    if (!t || typeof t.anchor != "number" || typeof t.head != "number")
      throw new RangeError("Invalid JSON representation for SelectionRange");
    return R.range(t.anchor, t.head);
  }
  static create(t, e, s) {
    return new Ii(t, e, s);
  }
}
class R {
  constructor(t, e) {
    this.ranges = t, this.mainIndex = e;
  }
  map(t, e = -1) {
    return t.empty ? this : R.create(this.ranges.map((s) => s.map(t, e)), this.mainIndex);
  }
  eq(t) {
    if (this.ranges.length != t.ranges.length || this.mainIndex != t.mainIndex)
      return !1;
    for (let e = 0; e < this.ranges.length; e++)
      if (!this.ranges[e].eq(t.ranges[e]))
        return !1;
    return !0;
  }
  get main() {
    return this.ranges[this.mainIndex];
  }
  asSingle() {
    return this.ranges.length == 1 ? this : new R([this.main], 0);
  }
  addRange(t, e = !0) {
    return R.create([t].concat(this.ranges), e ? 0 : this.mainIndex + 1);
  }
  replaceRange(t, e = this.mainIndex) {
    let s = this.ranges.slice();
    return s[e] = t, R.create(s, this.mainIndex);
  }
  toJSON() {
    return { ranges: this.ranges.map((t) => t.toJSON()), main: this.mainIndex };
  }
  static fromJSON(t) {
    if (!t || !Array.isArray(t.ranges) || typeof t.main != "number" || t.main >= t.ranges.length)
      throw new RangeError("Invalid JSON representation for EditorSelection");
    return new R(t.ranges.map((e) => Ii.fromJSON(e)), t.main);
  }
  static single(t, e = t) {
    return new R([R.range(t, e)], 0);
  }
  static create(t, e = 0) {
    if (t.length == 0)
      throw new RangeError("A selection needs at least one range");
    for (let s = 0, n = 0; n < t.length; n++) {
      let r = t[n];
      if (r.empty ? r.from <= s : r.from < s)
        return R.normalized(t.slice(), e);
      s = r.to;
    }
    return new R(t, e);
  }
  static cursor(t, e = 0, s, n) {
    return Ii.create(t, t, (e == 0 ? 0 : e < 0 ? 4 : 8) | (s == null ? 3 : Math.min(2, s)) | (n != null ? n : 33554431) << 5);
  }
  static range(t, e, s) {
    let n = (s != null ? s : 33554431) << 5;
    return e < t ? Ii.create(e, t, 16 | n | 8) : Ii.create(t, e, n | (e > t ? 4 : 0));
  }
  static normalized(t, e = 0) {
    let s = t[e];
    t.sort((n, r) => n.from - r.from), e = t.indexOf(s);
    for (let n = 1; n < t.length; n++) {
      let r = t[n], o = t[n - 1];
      if (r.empty ? r.from <= o.to : r.from < o.to) {
        let l = o.from, a = Math.max(r.to, o.to);
        n <= e && e--, t.splice(--n, 2, r.anchor > r.head ? R.range(a, l) : R.range(l, a));
      }
    }
    return new R(t, e);
  }
}
function Jd(i, t) {
  for (let e of i.ranges)
    if (e.to > t)
      throw new RangeError("Selection points outside of document");
}
let rh = 0;
class q {
  constructor(t, e, s, n, r) {
    this.combine = t, this.compareInput = e, this.compare = s, this.isStatic = n, this.id = rh++, this.default = t([]), this.extensions = typeof r == "function" ? r(this) : r;
  }
  static define(t = {}) {
    return new q(t.combine || ((e) => e), t.compareInput || ((e, s) => e === s), t.compare || (t.combine ? (e, s) => e === s : oh), !!t.static, t.enables);
  }
  of(t) {
    return new fr([], this, 0, t);
  }
  compute(t, e) {
    if (this.isStatic)
      throw new Error("Can't compute a static facet");
    return new fr(t, this, 1, e);
  }
  computeN(t, e) {
    if (this.isStatic)
      throw new Error("Can't compute a static facet");
    return new fr(t, this, 2, e);
  }
  from(t, e) {
    return e || (e = (s) => s), this.compute([t], (s) => e(s.field(t)));
  }
}
function oh(i, t) {
  return i == t || i.length == t.length && i.every((e, s) => e === t[s]);
}
class fr {
  constructor(t, e, s, n) {
    this.dependencies = t, this.facet = e, this.type = s, this.value = n, this.id = rh++;
  }
  dynamicSlot(t) {
    var e;
    let s = this.value, n = this.facet.compareInput, r = this.id, o = t[r] >> 1, l = this.type == 2, a = !1, h = !1, u = [];
    for (let c of this.dependencies)
      c == "doc" ? a = !0 : c == "selection" ? h = !0 : (((e = t[c.id]) !== null && e !== void 0 ? e : 1) & 1) == 0 && u.push(t[c.id]);
    return {
      create(c) {
        return c.values[o] = s(c), 1;
      },
      update(c, f) {
        if (a && f.docChanged || h && (f.docChanged || f.selection) || Zl(c, u)) {
          let g = s(c);
          if (l ? !vu(g, c.values[o], n) : !n(g, c.values[o]))
            return c.values[o] = g, 1;
        }
        return 0;
      },
      reconfigure: (c, f) => {
        let g = s(c), _ = f.config.address[r];
        if (_ != null) {
          let A = Pr(f, _);
          if (this.dependencies.every((m) => m instanceof q ? f.facet(m) === c.facet(m) : m instanceof Vt ? f.field(m, !1) == c.field(m, !1) : !0) || (l ? vu(g, A, n) : n(g, A)))
            return c.values[o] = A, 0;
        }
        return c.values[o] = g, 1;
      }
    };
  }
}
function vu(i, t, e) {
  if (i.length != t.length)
    return !1;
  for (let s = 0; s < i.length; s++)
    if (!e(i[s], t[s]))
      return !1;
  return !0;
}
function Zl(i, t) {
  let e = !1;
  for (let s of t)
    tn(i, s) & 1 && (e = !0);
  return e;
}
function my(i, t, e) {
  let s = e.map((a) => i[a.id]), n = e.map((a) => a.type), r = s.filter((a) => !(a & 1)), o = i[t.id] >> 1;
  function l(a) {
    let h = [];
    for (let u = 0; u < s.length; u++) {
      let c = Pr(a, s[u]);
      if (n[u] == 2)
        for (let f of c)
          h.push(f);
      else
        h.push(c);
    }
    return t.combine(h);
  }
  return {
    create(a) {
      for (let h of s)
        tn(a, h);
      return a.values[o] = l(a), 1;
    },
    update(a, h) {
      if (!Zl(a, r))
        return 0;
      let u = l(a);
      return t.compare(u, a.values[o]) ? 0 : (a.values[o] = u, 1);
    },
    reconfigure(a, h) {
      let u = Zl(a, s), c = h.config.facets[t.id], f = h.facet(t);
      if (c && !u && oh(e, c))
        return a.values[o] = f, 0;
      let g = l(a);
      return t.compare(g, f) ? (a.values[o] = f, 0) : (a.values[o] = g, 1);
    }
  };
}
const xu = /* @__PURE__ */ q.define({ static: !0 });
class Vt {
  constructor(t, e, s, n, r) {
    this.id = t, this.createF = e, this.updateF = s, this.compareF = n, this.spec = r, this.provides = void 0;
  }
  static define(t) {
    let e = new Vt(rh++, t.create, t.update, t.compare || ((s, n) => s === n), t);
    return t.provide && (e.provides = t.provide(e)), e;
  }
  create(t) {
    let e = t.facet(xu).find((s) => s.field == this);
    return ((e == null ? void 0 : e.create) || this.createF)(t);
  }
  slot(t) {
    let e = t[this.id] >> 1;
    return {
      create: (s) => (s.values[e] = this.create(s), 1),
      update: (s, n) => {
        let r = s.values[e], o = this.updateF(r, n);
        return this.compareF(r, o) ? 0 : (s.values[e] = o, 1);
      },
      reconfigure: (s, n) => n.config.address[this.id] != null ? (s.values[e] = n.field(this), 0) : (s.values[e] = this.create(s), 1)
    };
  }
  init(t) {
    return [this, xu.of({ field: this, create: t })];
  }
  get extension() {
    return this;
  }
}
const is = { lowest: 4, low: 3, default: 2, high: 1, highest: 0 };
function Ds(i) {
  return (t) => new Yd(t, i);
}
const As = {
  highest: /* @__PURE__ */ Ds(is.highest),
  high: /* @__PURE__ */ Ds(is.high),
  default: /* @__PURE__ */ Ds(is.default),
  low: /* @__PURE__ */ Ds(is.low),
  lowest: /* @__PURE__ */ Ds(is.lowest)
};
class Yd {
  constructor(t, e) {
    this.inner = t, this.prec = e;
  }
}
class On {
  of(t) {
    return new ta(this, t);
  }
  reconfigure(t) {
    return On.reconfigure.of({ compartment: this, extension: t });
  }
  get(t) {
    return t.config.compartments.get(this);
  }
}
class ta {
  constructor(t, e) {
    this.compartment = t, this.inner = e;
  }
}
class Tr {
  constructor(t, e, s, n, r, o) {
    for (this.base = t, this.compartments = e, this.dynamicSlots = s, this.address = n, this.staticValues = r, this.facets = o, this.statusTemplate = []; this.statusTemplate.length < s.length; )
      this.statusTemplate.push(0);
  }
  staticFacet(t) {
    let e = this.address[t.id];
    return e == null ? t.default : this.staticValues[e >> 1];
  }
  static resolve(t, e, s) {
    let n = [], r = /* @__PURE__ */ Object.create(null), o = /* @__PURE__ */ new Map();
    for (let f of _y(t, e, o))
      f instanceof Vt ? n.push(f) : (r[f.facet.id] || (r[f.facet.id] = [])).push(f);
    let l = /* @__PURE__ */ Object.create(null), a = [], h = [];
    for (let f of n)
      l[f.id] = h.length << 1, h.push((g) => f.slot(g));
    let u = s == null ? void 0 : s.config.facets;
    for (let f in r) {
      let g = r[f], _ = g[0].facet, A = u && u[f] || [];
      if (g.every((m) => m.type == 0))
        if (l[_.id] = a.length << 1 | 1, oh(A, g))
          a.push(s.facet(_));
        else {
          let m = _.combine(g.map((p) => p.value));
          a.push(s && _.compare(m, s.facet(_)) ? s.facet(_) : m);
        }
      else {
        for (let m of g)
          m.type == 0 ? (l[m.id] = a.length << 1 | 1, a.push(m.value)) : (l[m.id] = h.length << 1, h.push((p) => m.dynamicSlot(p)));
        l[_.id] = h.length << 1, h.push((m) => my(m, _, g));
      }
    }
    let c = h.map((f) => f(l));
    return new Tr(t, o, c, l, a, r);
  }
}
function _y(i, t, e) {
  let s = [[], [], [], [], []], n = /* @__PURE__ */ new Map();
  function r(o, l) {
    let a = n.get(o);
    if (a != null) {
      if (a <= l)
        return;
      let h = s[a].indexOf(o);
      h > -1 && s[a].splice(h, 1), o instanceof ta && e.delete(o.compartment);
    }
    if (n.set(o, l), Array.isArray(o))
      for (let h of o)
        r(h, l);
    else if (o instanceof ta) {
      if (e.has(o.compartment))
        throw new RangeError("Duplicate use of compartment in extensions");
      let h = t.get(o.compartment) || o.inner;
      e.set(o.compartment, h), r(h, l);
    } else if (o instanceof Yd)
      r(o.inner, o.prec);
    else if (o instanceof Vt)
      s[l].push(o), o.provides && r(o.provides, l);
    else if (o instanceof fr)
      s[l].push(o), o.facet.extensions && r(o.facet.extensions, l);
    else {
      let h = o.extension;
      if (!h)
        throw new Error(`Unrecognized extension value in extension set (${o}). This sometimes happens because multiple instances of @codemirror/state are loaded, breaking instanceof checks.`);
      r(h, l);
    }
  }
  return r(i, is.default), s.reduce((o, l) => o.concat(l));
}
function tn(i, t) {
  if (t & 1)
    return 2;
  let e = t >> 1, s = i.status[e];
  if (s == 4)
    throw new Error("Cyclic dependency between fields and/or facets");
  if (s & 2)
    return s;
  i.status[e] = 4;
  let n = i.computeSlot(i, i.config.dynamicSlots[e]);
  return i.status[e] = 2 | n;
}
function Pr(i, t) {
  return t & 1 ? i.config.staticValues[t >> 1] : i.values[t >> 1];
}
const Zd = /* @__PURE__ */ q.define(), tp = /* @__PURE__ */ q.define({
  combine: (i) => i.some((t) => t),
  static: !0
}), ep = /* @__PURE__ */ q.define({
  combine: (i) => i.length ? i[0] : void 0,
  static: !0
}), ip = /* @__PURE__ */ q.define(), sp = /* @__PURE__ */ q.define(), np = /* @__PURE__ */ q.define(), rp = /* @__PURE__ */ q.define({
  combine: (i) => i.length ? i[0] : !1
});
class Ki {
  constructor(t, e) {
    this.type = t, this.value = e;
  }
  static define() {
    return new by();
  }
}
class by {
  of(t) {
    return new Ki(this, t);
  }
}
class yy {
  constructor(t) {
    this.map = t;
  }
  of(t) {
    return new rt(this, t);
  }
}
class rt {
  constructor(t, e) {
    this.type = t, this.value = e;
  }
  map(t) {
    let e = this.type.map(this.value, t);
    return e === void 0 ? void 0 : e == this.value ? this : new rt(this.type, e);
  }
  is(t) {
    return this.type == t;
  }
  static define(t = {}) {
    return new yy(t.map || ((e) => e));
  }
  static mapEffects(t, e) {
    if (!t.length)
      return t;
    let s = [];
    for (let n of t) {
      let r = n.map(e);
      r && s.push(r);
    }
    return s;
  }
}
rt.reconfigure = /* @__PURE__ */ rt.define();
rt.appendConfig = /* @__PURE__ */ rt.define();
class Et {
  constructor(t, e, s, n, r, o) {
    this.startState = t, this.changes = e, this.selection = s, this.effects = n, this.annotations = r, this.scrollIntoView = o, this._doc = null, this._state = null, s && Jd(s, e.newLength), r.some((l) => l.type == Et.time) || (this.annotations = r.concat(Et.time.of(Date.now())));
  }
  static create(t, e, s, n, r, o) {
    return new Et(t, e, s, n, r, o);
  }
  get newDoc() {
    return this._doc || (this._doc = this.changes.apply(this.startState.doc));
  }
  get newSelection() {
    return this.selection || this.startState.selection.map(this.changes);
  }
  get state() {
    return this._state || this.startState.applyTransaction(this), this._state;
  }
  annotation(t) {
    for (let e of this.annotations)
      if (e.type == t)
        return e.value;
  }
  get docChanged() {
    return !this.changes.empty;
  }
  get reconfigured() {
    return this.startState.config != this.state.config;
  }
  isUserEvent(t) {
    let e = this.annotation(Et.userEvent);
    return !!(e && (e == t || e.length > t.length && e.slice(0, t.length) == t && e[t.length] == "."));
  }
}
Et.time = /* @__PURE__ */ Ki.define();
Et.userEvent = /* @__PURE__ */ Ki.define();
Et.addToHistory = /* @__PURE__ */ Ki.define();
Et.remote = /* @__PURE__ */ Ki.define();
function wy(i, t) {
  let e = [];
  for (let s = 0, n = 0; ; ) {
    let r, o;
    if (s < i.length && (n == t.length || t[n] >= i[s]))
      r = i[s++], o = i[s++];
    else if (n < t.length)
      r = t[n++], o = t[n++];
    else
      return e;
    !e.length || e[e.length - 1] < r ? e.push(r, o) : e[e.length - 1] < o && (e[e.length - 1] = o);
  }
}
function op(i, t, e) {
  var s;
  let n, r, o;
  return e ? (n = t.changes, r = Pt.empty(t.changes.length), o = i.changes.compose(t.changes)) : (n = t.changes.map(i.changes), r = i.changes.mapDesc(t.changes, !0), o = i.changes.compose(n)), {
    changes: o,
    selection: t.selection ? t.selection.map(r) : (s = i.selection) === null || s === void 0 ? void 0 : s.map(n),
    effects: rt.mapEffects(i.effects, n).concat(rt.mapEffects(t.effects, r)),
    annotations: i.annotations.length ? i.annotations.concat(t.annotations) : t.annotations,
    scrollIntoView: i.scrollIntoView || t.scrollIntoView
  };
}
function ea(i, t, e) {
  let s = t.selection, n = cs(t.annotations);
  return t.userEvent && (n = n.concat(Et.userEvent.of(t.userEvent))), {
    changes: t.changes instanceof Pt ? t.changes : Pt.of(t.changes || [], e, i.facet(ep)),
    selection: s && (s instanceof R ? s : R.single(s.anchor, s.head)),
    effects: cs(t.effects),
    annotations: n,
    scrollIntoView: !!t.scrollIntoView
  };
}
function lp(i, t, e) {
  let s = ea(i, t.length ? t[0] : {}, i.doc.length);
  t.length && t[0].filter === !1 && (e = !1);
  for (let r = 1; r < t.length; r++) {
    t[r].filter === !1 && (e = !1);
    let o = !!t[r].sequential;
    s = op(s, ea(i, t[r], o ? s.changes.newLength : i.doc.length), o);
  }
  let n = Et.create(i, s.changes, s.selection, s.effects, s.annotations, s.scrollIntoView);
  return xy(e ? vy(n) : n);
}
function vy(i) {
  let t = i.startState, e = !0;
  for (let n of t.facet(ip)) {
    let r = n(i);
    if (r === !1) {
      e = !1;
      break;
    }
    Array.isArray(r) && (e = e === !0 ? r : wy(e, r));
  }
  if (e !== !0) {
    let n, r;
    if (e === !1)
      r = i.changes.invertedDesc, n = Pt.empty(t.doc.length);
    else {
      let o = i.changes.filter(e);
      n = o.changes, r = o.filtered.mapDesc(o.changes).invertedDesc;
    }
    i = Et.create(t, n, i.selection && i.selection.map(r), rt.mapEffects(i.effects, r), i.annotations, i.scrollIntoView);
  }
  let s = t.facet(sp);
  for (let n = s.length - 1; n >= 0; n--) {
    let r = s[n](i);
    r instanceof Et ? i = r : Array.isArray(r) && r.length == 1 && r[0] instanceof Et ? i = r[0] : i = lp(t, cs(r), !1);
  }
  return i;
}
function xy(i) {
  let t = i.startState, e = t.facet(np), s = i;
  for (let n = e.length - 1; n >= 0; n--) {
    let r = e[n](i);
    r && Object.keys(r).length && (s = op(i, ea(t, r, i.changes.newLength), !0));
  }
  return s == i ? i : Et.create(t, i.changes, i.selection, s.effects, s.annotations, s.scrollIntoView);
}
const ky = [];
function cs(i) {
  return i == null ? ky : Array.isArray(i) ? i : [i];
}
var Jt = /* @__PURE__ */ function(i) {
  return i[i.Word = 0] = "Word", i[i.Space = 1] = "Space", i[i.Other = 2] = "Other", i;
}(Jt || (Jt = {}));
const Oy = /[\u00df\u0587\u0590-\u05f4\u0600-\u06ff\u3040-\u309f\u30a0-\u30ff\u3400-\u4db5\u4e00-\u9fcc\uac00-\ud7af]/;
let ia;
try {
  ia = /* @__PURE__ */ new RegExp("[\\p{Alphabetic}\\p{Number}_]", "u");
} catch (i) {
}
function Sy(i) {
  if (ia)
    return ia.test(i);
  for (let t = 0; t < i.length; t++) {
    let e = i[t];
    if (/\w/.test(e) || e > "\x80" && (e.toUpperCase() != e.toLowerCase() || Oy.test(e)))
      return !0;
  }
  return !1;
}
function Cy(i) {
  return (t) => {
    if (!/\S/.test(t))
      return Jt.Space;
    if (Sy(t))
      return Jt.Word;
    for (let e = 0; e < i.length; e++)
      if (t.indexOf(i[e]) > -1)
        return Jt.Word;
    return Jt.Other;
  };
}
class at {
  constructor(t, e, s, n, r, o) {
    this.config = t, this.doc = e, this.selection = s, this.values = n, this.status = t.statusTemplate.slice(), this.computeSlot = r, o && (o._state = this);
    for (let l = 0; l < this.config.dynamicSlots.length; l++)
      tn(this, l << 1);
    this.computeSlot = null;
  }
  field(t, e = !0) {
    let s = this.config.address[t.id];
    if (s == null) {
      if (e)
        throw new RangeError("Field is not present in this state");
      return;
    }
    return tn(this, s), Pr(this, s);
  }
  update(...t) {
    return lp(this, t, !0);
  }
  applyTransaction(t) {
    let e = this.config, { base: s, compartments: n } = e;
    for (let o of t.effects)
      o.is(On.reconfigure) ? (e && (n = /* @__PURE__ */ new Map(), e.compartments.forEach((l, a) => n.set(a, l)), e = null), n.set(o.value.compartment, o.value.extension)) : o.is(rt.reconfigure) ? (e = null, s = o.value) : o.is(rt.appendConfig) && (e = null, s = cs(s).concat(o.value));
    let r;
    e ? r = t.startState.values.slice() : (e = Tr.resolve(s, n, this), r = new at(e, this.doc, this.selection, e.dynamicSlots.map(() => null), (l, a) => a.reconfigure(l, this), null).values), new at(e, t.newDoc, t.newSelection, r, (o, l) => l.update(o, t), t);
  }
  replaceSelection(t) {
    return typeof t == "string" && (t = this.toText(t)), this.changeByRange((e) => ({
      changes: { from: e.from, to: e.to, insert: t },
      range: R.cursor(e.from + t.length)
    }));
  }
  changeByRange(t) {
    let e = this.selection, s = t(e.ranges[0]), n = this.changes(s.changes), r = [s.range], o = cs(s.effects);
    for (let l = 1; l < e.ranges.length; l++) {
      let a = t(e.ranges[l]), h = this.changes(a.changes), u = h.map(n);
      for (let f = 0; f < l; f++)
        r[f] = r[f].map(u);
      let c = n.mapDesc(h, !0);
      r.push(a.range.map(c)), n = n.compose(u), o = rt.mapEffects(o, u).concat(rt.mapEffects(cs(a.effects), c));
    }
    return {
      changes: n,
      selection: R.create(r, e.mainIndex),
      effects: o
    };
  }
  changes(t = []) {
    return t instanceof Pt ? t : Pt.of(t, this.doc.length, this.facet(at.lineSeparator));
  }
  toText(t) {
    return ct.of(t.split(this.facet(at.lineSeparator) || Gl));
  }
  sliceDoc(t = 0, e = this.doc.length) {
    return this.doc.sliceString(t, e, this.lineBreak);
  }
  facet(t) {
    let e = this.config.address[t.id];
    return e == null ? t.default : (tn(this, e), Pr(this, e));
  }
  toJSON(t) {
    let e = {
      doc: this.sliceDoc(),
      selection: this.selection.toJSON()
    };
    if (t)
      for (let s in t) {
        let n = t[s];
        n instanceof Vt && this.config.address[n.id] != null && (e[s] = n.spec.toJSON(this.field(t[s]), this));
      }
    return e;
  }
  static fromJSON(t, e = {}, s) {
    if (!t || typeof t.doc != "string")
      throw new RangeError("Invalid JSON representation for EditorState");
    let n = [];
    if (s) {
      for (let r in s)
        if (Object.prototype.hasOwnProperty.call(t, r)) {
          let o = s[r], l = t[r];
          n.push(o.init((a) => o.spec.fromJSON(l, a)));
        }
    }
    return at.create({
      doc: t.doc,
      selection: R.fromJSON(t.selection),
      extensions: e.extensions ? n.concat([e.extensions]) : n
    });
  }
  static create(t = {}) {
    let e = Tr.resolve(t.extensions || [], /* @__PURE__ */ new Map()), s = t.doc instanceof ct ? t.doc : ct.of((t.doc || "").split(e.staticFacet(at.lineSeparator) || Gl)), n = t.selection ? t.selection instanceof R ? t.selection : R.single(t.selection.anchor, t.selection.head) : R.single(0);
    return Jd(n, s.length), e.staticFacet(tp) || (n = n.asSingle()), new at(e, s, n, e.dynamicSlots.map(() => null), (r, o) => o.create(r), null);
  }
  get tabSize() {
    return this.facet(at.tabSize);
  }
  get lineBreak() {
    return this.facet(at.lineSeparator) || `
`;
  }
  get readOnly() {
    return this.facet(rp);
  }
  phrase(t, ...e) {
    for (let s of this.facet(at.phrases))
      if (Object.prototype.hasOwnProperty.call(s, t)) {
        t = s[t];
        break;
      }
    return e.length && (t = t.replace(/\$(\$|\d*)/g, (s, n) => {
      if (n == "$")
        return "$";
      let r = +(n || 1);
      return !r || r > e.length ? s : e[r - 1];
    })), t;
  }
  languageDataAt(t, e, s = -1) {
    let n = [];
    for (let r of this.facet(Zd))
      for (let o of r(this, e, s))
        Object.prototype.hasOwnProperty.call(o, t) && n.push(o[t]);
    return n;
  }
  charCategorizer(t) {
    return Cy(this.languageDataAt("wordChars", t).join(""));
  }
  wordAt(t) {
    let { text: e, from: s, length: n } = this.doc.lineAt(t), r = this.charCategorizer(t), o = t - s, l = t - s;
    for (; o > 0; ) {
      let a = de(e, o, !1);
      if (r(e.slice(a, o)) != Jt.Word)
        break;
      o = a;
    }
    for (; l < n; ) {
      let a = de(e, l);
      if (r(e.slice(l, a)) != Jt.Word)
        break;
      l = a;
    }
    return o == l ? null : R.range(o + s, l + s);
  }
}
at.allowMultipleSelections = tp;
at.tabSize = /* @__PURE__ */ q.define({
  combine: (i) => i.length ? i[0] : 4
});
at.lineSeparator = ep;
at.readOnly = rp;
at.phrases = /* @__PURE__ */ q.define({
  compare(i, t) {
    let e = Object.keys(i), s = Object.keys(t);
    return e.length == s.length && e.every((n) => i[n] == t[n]);
  }
});
at.languageData = Zd;
at.changeFilter = ip;
at.transactionFilter = sp;
at.transactionExtender = np;
On.reconfigure = /* @__PURE__ */ rt.define();
function si(i, t, e = {}) {
  let s = {};
  for (let n of i)
    for (let r of Object.keys(n)) {
      let o = n[r], l = s[r];
      if (l === void 0)
        s[r] = o;
      else if (!(l === o || o === void 0))
        if (Object.hasOwnProperty.call(e, r))
          s[r] = e[r](l, o);
        else
          throw new Error("Config merge conflict for field " + r);
    }
  for (let n in t)
    s[n] === void 0 && (s[n] = t[n]);
  return s;
}
class ji {
  eq(t) {
    return this == t;
  }
  range(t, e = t) {
    return dn.create(t, e, this);
  }
}
ji.prototype.startSide = ji.prototype.endSide = 0;
ji.prototype.point = !1;
ji.prototype.mapMode = Gt.TrackDel;
class dn {
  constructor(t, e, s) {
    this.from = t, this.to = e, this.value = s;
  }
  static create(t, e, s) {
    return new dn(t, e, s);
  }
}
function sa(i, t) {
  return i.from - t.from || i.value.startSide - t.value.startSide;
}
class lh {
  constructor(t, e, s, n) {
    this.from = t, this.to = e, this.value = s, this.maxPoint = n;
  }
  get length() {
    return this.to[this.to.length - 1];
  }
  findIndex(t, e, s, n = 0) {
    let r = s ? this.to : this.from;
    for (let o = n, l = r.length; ; ) {
      if (o == l)
        return o;
      let a = o + l >> 1, h = r[a] - t || (s ? this.value[a].endSide : this.value[a].startSide) - e;
      if (a == o)
        return h >= 0 ? o : l;
      h >= 0 ? l = a : o = a + 1;
    }
  }
  between(t, e, s, n) {
    for (let r = this.findIndex(e, -1e9, !0), o = this.findIndex(s, 1e9, !1, r); r < o; r++)
      if (n(this.from[r] + t, this.to[r] + t, this.value[r]) === !1)
        return !1;
  }
  map(t, e) {
    let s = [], n = [], r = [], o = -1, l = -1;
    for (let a = 0; a < this.value.length; a++) {
      let h = this.value[a], u = this.from[a] + t, c = this.to[a] + t, f, g;
      if (u == c) {
        let _ = e.mapPos(u, h.startSide, h.mapMode);
        if (_ == null || (f = g = _, h.startSide != h.endSide && (g = e.mapPos(u, h.endSide), g < f)))
          continue;
      } else if (f = e.mapPos(u, h.startSide), g = e.mapPos(c, h.endSide), f > g || f == g && h.startSide > 0 && h.endSide <= 0)
        continue;
      (g - f || h.endSide - h.startSide) < 0 || (o < 0 && (o = f), h.point && (l = Math.max(l, g - f)), s.push(h), n.push(f - o), r.push(g - o));
    }
    return { mapped: s.length ? new lh(n, r, s, l) : null, pos: o };
  }
}
class ft {
  constructor(t, e, s, n) {
    this.chunkPos = t, this.chunk = e, this.nextLayer = s, this.maxPoint = n;
  }
  static create(t, e, s, n) {
    return new ft(t, e, s, n);
  }
  get length() {
    let t = this.chunk.length - 1;
    return t < 0 ? 0 : Math.max(this.chunkEnd(t), this.nextLayer.length);
  }
  get size() {
    if (this.isEmpty)
      return 0;
    let t = this.nextLayer.size;
    for (let e of this.chunk)
      t += e.value.length;
    return t;
  }
  chunkEnd(t) {
    return this.chunkPos[t] + this.chunk[t].length;
  }
  update(t) {
    let { add: e = [], sort: s = !1, filterFrom: n = 0, filterTo: r = this.length } = t, o = t.filter;
    if (e.length == 0 && !o)
      return this;
    if (s && (e = e.slice().sort(sa)), this.isEmpty)
      return e.length ? ft.of(e) : this;
    let l = new ap(this, null, -1).goto(0), a = 0, h = [], u = new Oi();
    for (; l.value || a < e.length; )
      if (a < e.length && (l.from - e[a].from || l.startSide - e[a].value.startSide) >= 0) {
        let c = e[a++];
        u.addInner(c.from, c.to, c.value) || h.push(c);
      } else
        l.rangeIndex == 1 && l.chunkIndex < this.chunk.length && (a == e.length || this.chunkEnd(l.chunkIndex) < e[a].from) && (!o || n > this.chunkEnd(l.chunkIndex) || r < this.chunkPos[l.chunkIndex]) && u.addChunk(this.chunkPos[l.chunkIndex], this.chunk[l.chunkIndex]) ? l.nextChunk() : ((!o || n > l.to || r < l.from || o(l.from, l.to, l.value)) && (u.addInner(l.from, l.to, l.value) || h.push(dn.create(l.from, l.to, l.value))), l.next());
    return u.finishInner(this.nextLayer.isEmpty && !h.length ? ft.empty : this.nextLayer.update({ add: h, filter: o, filterFrom: n, filterTo: r }));
  }
  map(t) {
    if (t.empty || this.isEmpty)
      return this;
    let e = [], s = [], n = -1;
    for (let o = 0; o < this.chunk.length; o++) {
      let l = this.chunkPos[o], a = this.chunk[o], h = t.touchesRange(l, l + a.length);
      if (h === !1)
        n = Math.max(n, a.maxPoint), e.push(a), s.push(t.mapPos(l));
      else if (h === !0) {
        let { mapped: u, pos: c } = a.map(l, t);
        u && (n = Math.max(n, u.maxPoint), e.push(u), s.push(c));
      }
    }
    let r = this.nextLayer.map(t);
    return e.length == 0 ? r : new ft(s, e, r || ft.empty, n);
  }
  between(t, e, s) {
    if (!this.isEmpty) {
      for (let n = 0; n < this.chunk.length; n++) {
        let r = this.chunkPos[n], o = this.chunk[n];
        if (e >= r && t <= r + o.length && o.between(r, t - r, e - r, s) === !1)
          return;
      }
      this.nextLayer.between(t, e, s);
    }
  }
  iter(t = 0) {
    return pn.from([this]).goto(t);
  }
  get isEmpty() {
    return this.nextLayer == this;
  }
  static iter(t, e = 0) {
    return pn.from(t).goto(e);
  }
  static compare(t, e, s, n, r = -1) {
    let o = t.filter((c) => c.maxPoint > 0 || !c.isEmpty && c.maxPoint >= r), l = e.filter((c) => c.maxPoint > 0 || !c.isEmpty && c.maxPoint >= r), a = ku(o, l, s), h = new Bs(o, a, r), u = new Bs(l, a, r);
    s.iterGaps((c, f, g) => Ou(h, c, u, f, g, n)), s.empty && s.length == 0 && Ou(h, 0, u, 0, 0, n);
  }
  static eq(t, e, s = 0, n) {
    n == null && (n = 1e9);
    let r = t.filter((u) => !u.isEmpty && e.indexOf(u) < 0), o = e.filter((u) => !u.isEmpty && t.indexOf(u) < 0);
    if (r.length != o.length)
      return !1;
    if (!r.length)
      return !0;
    let l = ku(r, o), a = new Bs(r, l, 0).goto(s), h = new Bs(o, l, 0).goto(s);
    for (; ; ) {
      if (a.to != h.to || !na(a.active, h.active) || a.point && (!h.point || !a.point.eq(h.point)))
        return !1;
      if (a.to > n)
        return !0;
      a.next(), h.next();
    }
  }
  static spans(t, e, s, n, r = -1) {
    let o = new Bs(t, null, r).goto(e), l = e, a = o.openStart;
    for (; ; ) {
      let h = Math.min(o.to, s);
      if (o.point ? (n.point(l, h, o.point, o.activeForPoint(o.to), a, o.pointRank), a = o.openEnd(h) + (o.to > h ? 1 : 0)) : h > l && (n.span(l, h, o.active, a), a = o.openEnd(h)), o.to > s)
        break;
      l = o.to, o.next();
    }
    return a;
  }
  static of(t, e = !1) {
    let s = new Oi();
    for (let n of t instanceof dn ? [t] : e ? Ay(t) : t)
      s.add(n.from, n.to, n.value);
    return s.finish();
  }
}
ft.empty = /* @__PURE__ */ new ft([], [], null, -1);
function Ay(i) {
  if (i.length > 1)
    for (let t = i[0], e = 1; e < i.length; e++) {
      let s = i[e];
      if (sa(t, s) > 0)
        return i.slice().sort(sa);
      t = s;
    }
  return i;
}
ft.empty.nextLayer = ft.empty;
class Oi {
  constructor() {
    this.chunks = [], this.chunkPos = [], this.chunkStart = -1, this.last = null, this.lastFrom = -1e9, this.lastTo = -1e9, this.from = [], this.to = [], this.value = [], this.maxPoint = -1, this.setMaxPoint = -1, this.nextLayer = null;
  }
  finishChunk(t) {
    this.chunks.push(new lh(this.from, this.to, this.value, this.maxPoint)), this.chunkPos.push(this.chunkStart), this.chunkStart = -1, this.setMaxPoint = Math.max(this.setMaxPoint, this.maxPoint), this.maxPoint = -1, t && (this.from = [], this.to = [], this.value = []);
  }
  add(t, e, s) {
    this.addInner(t, e, s) || (this.nextLayer || (this.nextLayer = new Oi())).add(t, e, s);
  }
  addInner(t, e, s) {
    let n = t - this.lastTo || s.startSide - this.last.endSide;
    if (n <= 0 && (t - this.lastFrom || s.startSide - this.last.startSide) < 0)
      throw new Error("Ranges must be added sorted by `from` position and `startSide`");
    return n < 0 ? !1 : (this.from.length == 250 && this.finishChunk(!0), this.chunkStart < 0 && (this.chunkStart = t), this.from.push(t - this.chunkStart), this.to.push(e - this.chunkStart), this.last = s, this.lastFrom = t, this.lastTo = e, this.value.push(s), s.point && (this.maxPoint = Math.max(this.maxPoint, e - t)), !0);
  }
  addChunk(t, e) {
    if ((t - this.lastTo || e.value[0].startSide - this.last.endSide) < 0)
      return !1;
    this.from.length && this.finishChunk(!0), this.setMaxPoint = Math.max(this.setMaxPoint, e.maxPoint), this.chunks.push(e), this.chunkPos.push(t);
    let s = e.value.length - 1;
    return this.last = e.value[s], this.lastFrom = e.from[s] + t, this.lastTo = e.to[s] + t, !0;
  }
  finish() {
    return this.finishInner(ft.empty);
  }
  finishInner(t) {
    if (this.from.length && this.finishChunk(!1), this.chunks.length == 0)
      return t;
    let e = ft.create(this.chunkPos, this.chunks, this.nextLayer ? this.nextLayer.finishInner(t) : t, this.setMaxPoint);
    return this.from = null, e;
  }
}
function ku(i, t, e) {
  let s = /* @__PURE__ */ new Map();
  for (let r of i)
    for (let o = 0; o < r.chunk.length; o++)
      r.chunk[o].maxPoint <= 0 && s.set(r.chunk[o], r.chunkPos[o]);
  let n = /* @__PURE__ */ new Set();
  for (let r of t)
    for (let o = 0; o < r.chunk.length; o++) {
      let l = s.get(r.chunk[o]);
      l != null && (e ? e.mapPos(l) : l) == r.chunkPos[o] && !(e != null && e.touchesRange(l, l + r.chunk[o].length)) && n.add(r.chunk[o]);
    }
  return n;
}
class ap {
  constructor(t, e, s, n = 0) {
    this.layer = t, this.skip = e, this.minPoint = s, this.rank = n;
  }
  get startSide() {
    return this.value ? this.value.startSide : 0;
  }
  get endSide() {
    return this.value ? this.value.endSide : 0;
  }
  goto(t, e = -1e9) {
    return this.chunkIndex = this.rangeIndex = 0, this.gotoInner(t, e, !1), this;
  }
  gotoInner(t, e, s) {
    for (; this.chunkIndex < this.layer.chunk.length; ) {
      let n = this.layer.chunk[this.chunkIndex];
      if (!(this.skip && this.skip.has(n) || this.layer.chunkEnd(this.chunkIndex) < t || n.maxPoint < this.minPoint))
        break;
      this.chunkIndex++, s = !1;
    }
    if (this.chunkIndex < this.layer.chunk.length) {
      let n = this.layer.chunk[this.chunkIndex].findIndex(t - this.layer.chunkPos[this.chunkIndex], e, !0);
      (!s || this.rangeIndex < n) && this.setRangeIndex(n);
    }
    this.next();
  }
  forward(t, e) {
    (this.to - t || this.endSide - e) < 0 && this.gotoInner(t, e, !0);
  }
  next() {
    for (; ; )
      if (this.chunkIndex == this.layer.chunk.length) {
        this.from = this.to = 1e9, this.value = null;
        break;
      } else {
        let t = this.layer.chunkPos[this.chunkIndex], e = this.layer.chunk[this.chunkIndex], s = t + e.from[this.rangeIndex];
        if (this.from = s, this.to = t + e.to[this.rangeIndex], this.value = e.value[this.rangeIndex], this.setRangeIndex(this.rangeIndex + 1), this.minPoint < 0 || this.value.point && this.to - this.from >= this.minPoint)
          break;
      }
  }
  setRangeIndex(t) {
    if (t == this.layer.chunk[this.chunkIndex].value.length) {
      if (this.chunkIndex++, this.skip)
        for (; this.chunkIndex < this.layer.chunk.length && this.skip.has(this.layer.chunk[this.chunkIndex]); )
          this.chunkIndex++;
      this.rangeIndex = 0;
    } else
      this.rangeIndex = t;
  }
  nextChunk() {
    this.chunkIndex++, this.rangeIndex = 0, this.next();
  }
  compare(t) {
    return this.from - t.from || this.startSide - t.startSide || this.rank - t.rank || this.to - t.to || this.endSide - t.endSide;
  }
}
class pn {
  constructor(t) {
    this.heap = t;
  }
  static from(t, e = null, s = -1) {
    let n = [];
    for (let r = 0; r < t.length; r++)
      for (let o = t[r]; !o.isEmpty; o = o.nextLayer)
        o.maxPoint >= s && n.push(new ap(o, e, s, r));
    return n.length == 1 ? n[0] : new pn(n);
  }
  get startSide() {
    return this.value ? this.value.startSide : 0;
  }
  goto(t, e = -1e9) {
    for (let s of this.heap)
      s.goto(t, e);
    for (let s = this.heap.length >> 1; s >= 0; s--)
      Fo(this.heap, s);
    return this.next(), this;
  }
  forward(t, e) {
    for (let s of this.heap)
      s.forward(t, e);
    for (let s = this.heap.length >> 1; s >= 0; s--)
      Fo(this.heap, s);
    (this.to - t || this.value.endSide - e) < 0 && this.next();
  }
  next() {
    if (this.heap.length == 0)
      this.from = this.to = 1e9, this.value = null, this.rank = -1;
    else {
      let t = this.heap[0];
      this.from = t.from, this.to = t.to, this.value = t.value, this.rank = t.rank, t.value && t.next(), Fo(this.heap, 0);
    }
  }
}
function Fo(i, t) {
  for (let e = i[t]; ; ) {
    let s = (t << 1) + 1;
    if (s >= i.length)
      break;
    let n = i[s];
    if (s + 1 < i.length && n.compare(i[s + 1]) >= 0 && (n = i[s + 1], s++), e.compare(n) < 0)
      break;
    i[s] = e, i[t] = n, t = s;
  }
}
class Bs {
  constructor(t, e, s) {
    this.minPoint = s, this.active = [], this.activeTo = [], this.activeRank = [], this.minActive = -1, this.point = null, this.pointFrom = 0, this.pointRank = 0, this.to = -1e9, this.endSide = 0, this.openStart = -1, this.cursor = pn.from(t, e, s);
  }
  goto(t, e = -1e9) {
    return this.cursor.goto(t, e), this.active.length = this.activeTo.length = this.activeRank.length = 0, this.minActive = -1, this.to = t, this.endSide = e, this.openStart = -1, this.next(), this;
  }
  forward(t, e) {
    for (; this.minActive > -1 && (this.activeTo[this.minActive] - t || this.active[this.minActive].endSide - e) < 0; )
      this.removeActive(this.minActive);
    this.cursor.forward(t, e);
  }
  removeActive(t) {
    Ln(this.active, t), Ln(this.activeTo, t), Ln(this.activeRank, t), this.minActive = Su(this.active, this.activeTo);
  }
  addActive(t) {
    let e = 0, { value: s, to: n, rank: r } = this.cursor;
    for (; e < this.activeRank.length && this.activeRank[e] <= r; )
      e++;
    In(this.active, e, s), In(this.activeTo, e, n), In(this.activeRank, e, r), t && In(t, e, this.cursor.from), this.minActive = Su(this.active, this.activeTo);
  }
  next() {
    let t = this.to, e = this.point;
    this.point = null;
    let s = this.openStart < 0 ? [] : null, n = 0;
    for (; ; ) {
      let r = this.minActive;
      if (r > -1 && (this.activeTo[r] - this.cursor.from || this.active[r].endSide - this.cursor.startSide) < 0) {
        if (this.activeTo[r] > t) {
          this.to = this.activeTo[r], this.endSide = this.active[r].endSide;
          break;
        }
        this.removeActive(r), s && Ln(s, r);
      } else if (this.cursor.value)
        if (this.cursor.from > t) {
          this.to = this.cursor.from, this.endSide = this.cursor.startSide;
          break;
        } else {
          let o = this.cursor.value;
          if (!o.point)
            this.addActive(s), this.cursor.next();
          else if (e && this.cursor.to == this.to && this.cursor.from < this.cursor.to)
            this.cursor.next();
          else {
            this.point = o, this.pointFrom = this.cursor.from, this.pointRank = this.cursor.rank, this.to = this.cursor.to, this.endSide = o.endSide, this.cursor.from < t && (n = 1), this.cursor.next(), this.forward(this.to, this.endSide);
            break;
          }
        }
      else {
        this.to = this.endSide = 1e9;
        break;
      }
    }
    if (s) {
      let r = 0;
      for (; r < s.length && s[r] < t; )
        r++;
      this.openStart = r + n;
    }
  }
  activeForPoint(t) {
    if (!this.active.length)
      return this.active;
    let e = [];
    for (let s = this.active.length - 1; s >= 0 && !(this.activeRank[s] < this.pointRank); s--)
      (this.activeTo[s] > t || this.activeTo[s] == t && this.active[s].endSide >= this.point.endSide) && e.push(this.active[s]);
    return e.reverse();
  }
  openEnd(t) {
    let e = 0;
    for (let s = this.activeTo.length - 1; s >= 0 && this.activeTo[s] > t; s--)
      e++;
    return e;
  }
}
function Ou(i, t, e, s, n, r) {
  i.goto(t), e.goto(s);
  let o = s + n, l = s, a = s - t;
  for (; ; ) {
    let h = i.to + a - e.to || i.endSide - e.endSide, u = h < 0 ? i.to + a : e.to, c = Math.min(u, o);
    if (i.point || e.point ? i.point && e.point && (i.point == e.point || i.point.eq(e.point)) && na(i.activeForPoint(i.to + a), e.activeForPoint(e.to)) || r.comparePoint(l, c, i.point, e.point) : c > l && !na(i.active, e.active) && r.compareRange(l, c, i.active, e.active), u > o)
      break;
    l = u, h <= 0 && i.next(), h >= 0 && e.next();
  }
}
function na(i, t) {
  if (i.length != t.length)
    return !1;
  for (let e = 0; e < i.length; e++)
    if (i[e] != t[e] && !i[e].eq(t[e]))
      return !1;
  return !0;
}
function Ln(i, t) {
  for (let e = t, s = i.length - 1; e < s; e++)
    i[e] = i[e + 1];
  i.pop();
}
function In(i, t, e) {
  for (let s = i.length - 1; s >= t; s--)
    i[s + 1] = i[s];
  i[t] = e;
}
function Su(i, t) {
  let e = -1, s = 1e9;
  for (let n = 0; n < t.length; n++)
    (t[n] - s || i[n].endSide - i[e].endSide) < 0 && (e = n, s = t[n]);
  return e;
}
function Sn(i, t, e = i.length) {
  let s = 0;
  for (let n = 0; n < e; )
    i.charCodeAt(n) == 9 ? (s += t - s % t, n++) : (s++, n = de(i, n));
  return s;
}
function ra(i, t, e, s) {
  for (let n = 0, r = 0; ; ) {
    if (r >= t)
      return n;
    if (n == i.length)
      break;
    r += i.charCodeAt(n) == 9 ? e - r % e : 1, n = de(i, n);
  }
  return s === !0 ? -1 : i.length;
}
const oa = "\u037C", Cu = typeof Symbol == "undefined" ? "__" + oa : Symbol.for(oa), la = typeof Symbol == "undefined" ? "__styleSet" + Math.floor(Math.random() * 1e8) : Symbol("styleSet"), Au = typeof globalThis != "undefined" ? globalThis : typeof window != "undefined" ? window : {};
class Si {
  constructor(t, e) {
    this.rules = [];
    let { finish: s } = e || {};
    function n(o) {
      return /^@/.test(o) ? [o] : o.split(/,\s*/);
    }
    function r(o, l, a, h) {
      let u = [], c = /^@(\w+)\b/.exec(o[0]), f = c && c[1] == "keyframes";
      if (c && l == null)
        return a.push(o[0] + ";");
      for (let g in l) {
        let _ = l[g];
        if (/&/.test(g))
          r(
            g.split(/,\s*/).map((A) => o.map((m) => A.replace(/&/, m))).reduce((A, m) => A.concat(m)),
            _,
            a
          );
        else if (_ && typeof _ == "object") {
          if (!c)
            throw new RangeError("The value of a property (" + g + ") should be a primitive value.");
          r(n(g), _, u, f);
        } else
          _ != null && u.push(g.replace(/_.*/, "").replace(/[A-Z]/g, (A) => "-" + A.toLowerCase()) + ": " + _ + ";");
      }
      (u.length || f) && a.push((s && !c && !h ? o.map(s) : o).join(", ") + " {" + u.join(" ") + "}");
    }
    for (let o in t)
      r(n(o), t[o], this.rules);
  }
  getRules() {
    return this.rules.join(`
`);
  }
  static newName() {
    let t = Au[Cu] || 1;
    return Au[Cu] = t + 1, oa + t.toString(36);
  }
  static mount(t, e) {
    (t[la] || new Ty(t)).mount(Array.isArray(e) ? e : [e]);
  }
}
let Qn = null;
class Ty {
  constructor(t) {
    if (!t.head && t.adoptedStyleSheets && typeof CSSStyleSheet != "undefined") {
      if (Qn)
        return t.adoptedStyleSheets = [Qn.sheet].concat(t.adoptedStyleSheets), t[la] = Qn;
      this.sheet = new CSSStyleSheet(), t.adoptedStyleSheets = [this.sheet].concat(t.adoptedStyleSheets), Qn = this;
    } else {
      this.styleTag = (t.ownerDocument || t).createElement("style");
      let e = t.head || t;
      e.insertBefore(this.styleTag, e.firstChild);
    }
    this.modules = [], t[la] = this;
  }
  mount(t) {
    let e = this.sheet, s = 0, n = 0;
    for (let r = 0; r < t.length; r++) {
      let o = t[r], l = this.modules.indexOf(o);
      if (l < n && l > -1 && (this.modules.splice(l, 1), n--, l = -1), l == -1) {
        if (this.modules.splice(n++, 0, o), e)
          for (let a = 0; a < o.rules.length; a++)
            e.insertRule(o.rules[a], s++);
      } else {
        for (; n < l; )
          s += this.modules[n++].rules.length;
        s += o.rules.length, n++;
      }
    }
    if (!e) {
      let r = "";
      for (let o = 0; o < this.modules.length; o++)
        r += this.modules[o].getRules() + `
`;
      this.styleTag.textContent = r;
    }
  }
}
var Ci = {
  8: "Backspace",
  9: "Tab",
  10: "Enter",
  12: "NumLock",
  13: "Enter",
  16: "Shift",
  17: "Control",
  18: "Alt",
  20: "CapsLock",
  27: "Escape",
  32: " ",
  33: "PageUp",
  34: "PageDown",
  35: "End",
  36: "Home",
  37: "ArrowLeft",
  38: "ArrowUp",
  39: "ArrowRight",
  40: "ArrowDown",
  44: "PrintScreen",
  45: "Insert",
  46: "Delete",
  59: ";",
  61: "=",
  91: "Meta",
  92: "Meta",
  106: "*",
  107: "+",
  108: ",",
  109: "-",
  110: ".",
  111: "/",
  144: "NumLock",
  145: "ScrollLock",
  160: "Shift",
  161: "Shift",
  162: "Control",
  163: "Control",
  164: "Alt",
  165: "Alt",
  173: "-",
  186: ";",
  187: "=",
  188: ",",
  189: "-",
  190: ".",
  191: "/",
  192: "`",
  219: "[",
  220: "\\",
  221: "]",
  222: "'"
}, ms = {
  48: ")",
  49: "!",
  50: "@",
  51: "#",
  52: "$",
  53: "%",
  54: "^",
  55: "&",
  56: "*",
  57: "(",
  59: ":",
  61: "+",
  173: "_",
  186: ":",
  187: "+",
  188: "<",
  189: "_",
  190: ">",
  191: "?",
  192: "~",
  219: "{",
  220: "|",
  221: "}",
  222: '"'
}, Tu = typeof navigator != "undefined" && /Chrome\/(\d+)/.exec(navigator.userAgent), Py = typeof navigator != "undefined" && /Apple Computer/.test(navigator.vendor), Ey = typeof navigator != "undefined" && /Gecko\/\d+/.test(navigator.userAgent), Pu = typeof navigator != "undefined" && /Mac/.test(navigator.platform), My = typeof navigator != "undefined" && /MSIE \d|Trident\/(?:[7-9]|\d{2,})\..*rv:(\d+)/.exec(navigator.userAgent), Ry = Tu && (Pu || +Tu[1] < 57) || Ey && Pu;
for (var Qt = 0; Qt < 10; Qt++)
  Ci[48 + Qt] = Ci[96 + Qt] = String(Qt);
for (var Qt = 1; Qt <= 24; Qt++)
  Ci[Qt + 111] = "F" + Qt;
for (var Qt = 65; Qt <= 90; Qt++)
  Ci[Qt] = String.fromCharCode(Qt + 32), ms[Qt] = String.fromCharCode(Qt);
for (var Vo in Ci)
  ms.hasOwnProperty(Vo) || (ms[Vo] = Ci[Vo]);
function Dy(i) {
  var t = Ry && (i.ctrlKey || i.altKey || i.metaKey) || (Py || My) && i.shiftKey && i.key && i.key.length == 1 || i.key == "Unidentified", e = !t && i.key || (i.shiftKey ? ms : Ci)[i.keyCode] || i.key || "Unidentified";
  return e == "Esc" && (e = "Escape"), e == "Del" && (e = "Delete"), e == "Left" && (e = "ArrowLeft"), e == "Up" && (e = "ArrowUp"), e == "Right" && (e = "ArrowRight"), e == "Down" && (e = "ArrowDown"), e;
}
function Er(i) {
  let t;
  return i.nodeType == 11 ? t = i.getSelection ? i : i.ownerDocument : t = i, t.getSelection();
}
function _s(i, t) {
  return t ? i == t || i.contains(t.nodeType != 1 ? t.parentNode : t) : !1;
}
function By() {
  let i = document.activeElement;
  for (; i && i.shadowRoot; )
    i = i.shadowRoot.activeElement;
  return i;
}
function dr(i, t) {
  if (!t.anchorNode)
    return !1;
  try {
    return _s(i, t.anchorNode);
  } catch (e) {
    return !1;
  }
}
function gn(i) {
  return i.nodeType == 3 ? bs(i, 0, i.nodeValue.length).getClientRects() : i.nodeType == 1 ? i.getClientRects() : [];
}
function Mr(i, t, e, s) {
  return e ? Eu(i, t, e, s, -1) || Eu(i, t, e, s, 1) : !1;
}
function Rr(i) {
  for (var t = 0; ; t++)
    if (i = i.previousSibling, !i)
      return t;
}
function Eu(i, t, e, s, n) {
  for (; ; ) {
    if (i == e && t == s)
      return !0;
    if (t == (n < 0 ? 0 : mn(i))) {
      if (i.nodeName == "DIV")
        return !1;
      let r = i.parentNode;
      if (!r || r.nodeType != 1)
        return !1;
      t = Rr(i) + (n < 0 ? 0 : 1), i = r;
    } else if (i.nodeType == 1) {
      if (i = i.childNodes[t + (n < 0 ? -1 : 0)], i.nodeType == 1 && i.contentEditable == "false")
        return !1;
      t = n < 0 ? mn(i) : 0;
    } else
      return !1;
  }
}
function mn(i) {
  return i.nodeType == 3 ? i.nodeValue.length : i.childNodes.length;
}
const hp = { left: 0, right: 0, top: 0, bottom: 0 };
function _o(i, t) {
  let e = t ? i.left : i.right;
  return { left: e, right: e, top: i.top, bottom: i.bottom };
}
function Ny(i) {
  return {
    left: 0,
    right: i.innerWidth,
    top: 0,
    bottom: i.innerHeight
  };
}
function Ly(i, t, e, s, n, r, o, l) {
  let a = i.ownerDocument, h = a.defaultView;
  for (let u = i; u; )
    if (u.nodeType == 1) {
      let c, f = u == a.body;
      if (f)
        c = Ny(h);
      else {
        if (u.scrollHeight <= u.clientHeight && u.scrollWidth <= u.clientWidth) {
          u = u.parentNode;
          continue;
        }
        let A = u.getBoundingClientRect();
        c = {
          left: A.left,
          right: A.left + u.clientWidth,
          top: A.top,
          bottom: A.top + u.clientHeight
        };
      }
      let g = 0, _ = 0;
      if (n == "nearest")
        t.top < c.top ? (_ = -(c.top - t.top + o), e > 0 && t.bottom > c.bottom + _ && (_ = t.bottom - c.bottom + _ + o)) : t.bottom > c.bottom && (_ = t.bottom - c.bottom + o, e < 0 && t.top - _ < c.top && (_ = -(c.top + _ - t.top + o)));
      else {
        let A = t.bottom - t.top, m = c.bottom - c.top;
        _ = (n == "center" && A <= m ? t.top + A / 2 - m / 2 : n == "start" || n == "center" && e < 0 ? t.top - o : t.bottom - m + o) - c.top;
      }
      if (s == "nearest" ? t.left < c.left ? (g = -(c.left - t.left + r), e > 0 && t.right > c.right + g && (g = t.right - c.right + g + r)) : t.right > c.right && (g = t.right - c.right + r, e < 0 && t.left < c.left + g && (g = -(c.left + g - t.left + r))) : g = (s == "center" ? t.left + (t.right - t.left) / 2 - (c.right - c.left) / 2 : s == "start" == l ? t.left - r : t.right - (c.right - c.left) + r) - c.left, g || _)
        if (f)
          h.scrollBy(g, _);
        else {
          if (_) {
            let A = u.scrollTop;
            u.scrollTop += _, _ = u.scrollTop - A;
          }
          if (g) {
            let A = u.scrollLeft;
            u.scrollLeft += g, g = u.scrollLeft - A;
          }
          t = {
            left: t.left - g,
            top: t.top - _,
            right: t.right - g,
            bottom: t.bottom - _
          };
        }
      if (f)
        break;
      u = u.assignedSlot || u.parentNode, s = n = "nearest";
    } else if (u.nodeType == 11)
      u = u.host;
    else
      break;
}
class Iy {
  constructor() {
    this.anchorNode = null, this.anchorOffset = 0, this.focusNode = null, this.focusOffset = 0;
  }
  eq(t) {
    return this.anchorNode == t.anchorNode && this.anchorOffset == t.anchorOffset && this.focusNode == t.focusNode && this.focusOffset == t.focusOffset;
  }
  setRange(t) {
    this.set(t.anchorNode, t.anchorOffset, t.focusNode, t.focusOffset);
  }
  set(t, e, s, n) {
    this.anchorNode = t, this.anchorOffset = e, this.focusNode = s, this.focusOffset = n;
  }
}
let Yi = null;
function up(i) {
  if (i.setActive)
    return i.setActive();
  if (Yi)
    return i.focus(Yi);
  let t = [];
  for (let e = i; e && (t.push(e, e.scrollTop, e.scrollLeft), e != e.ownerDocument); e = e.parentNode)
    ;
  if (i.focus(Yi == null ? {
    get preventScroll() {
      return Yi = { preventScroll: !0 }, !0;
    }
  } : void 0), !Yi) {
    Yi = !1;
    for (let e = 0; e < t.length; ) {
      let s = t[e++], n = t[e++], r = t[e++];
      s.scrollTop != n && (s.scrollTop = n), s.scrollLeft != r && (s.scrollLeft = r);
    }
  }
}
let Mu;
function bs(i, t, e = t) {
  let s = Mu || (Mu = document.createRange());
  return s.setEnd(i, e), s.setStart(i, t), s;
}
function en(i, t, e) {
  let s = { key: t, code: t, keyCode: e, which: e, cancelable: !0 }, n = new KeyboardEvent("keydown", s);
  n.synthetic = !0, i.dispatchEvent(n);
  let r = new KeyboardEvent("keyup", s);
  return r.synthetic = !0, i.dispatchEvent(r), n.defaultPrevented || r.defaultPrevented;
}
function Qy(i) {
  for (; i; ) {
    if (i && (i.nodeType == 9 || i.nodeType == 11 && i.host))
      return i;
    i = i.assignedSlot || i.parentNode;
  }
  return null;
}
function cp(i) {
  for (; i.attributes.length; )
    i.removeAttributeNode(i.attributes[0]);
}
function zy(i, t) {
  let e = t.focusNode, s = t.focusOffset;
  if (!e || t.anchorNode != e || t.anchorOffset != s)
    return !1;
  for (; ; )
    if (s) {
      if (e.nodeType != 1)
        return !1;
      let n = e.childNodes[s - 1];
      n.contentEditable == "false" ? s-- : (e = n, s = mn(e));
    } else {
      if (e == i)
        return !0;
      s = Rr(e), e = e.parentNode;
    }
}
class $t {
  constructor(t, e, s = !0) {
    this.node = t, this.offset = e, this.precise = s;
  }
  static before(t, e) {
    return new $t(t.parentNode, Rr(t), e);
  }
  static after(t, e) {
    return new $t(t.parentNode, Rr(t) + 1, e);
  }
}
const ah = [];
class kt {
  constructor() {
    this.parent = null, this.dom = null, this.dirty = 2;
  }
  get editorView() {
    if (!this.parent)
      throw new Error("Accessing view in orphan content view");
    return this.parent.editorView;
  }
  get overrideDOMText() {
    return null;
  }
  get posAtStart() {
    return this.parent ? this.parent.posBefore(this) : 0;
  }
  get posAtEnd() {
    return this.posAtStart + this.length;
  }
  posBefore(t) {
    let e = this.posAtStart;
    for (let s of this.children) {
      if (s == t)
        return e;
      e += s.length + s.breakAfter;
    }
    throw new RangeError("Invalid child in posBefore");
  }
  posAfter(t) {
    return this.posBefore(t) + t.length;
  }
  coordsAt(t, e) {
    return null;
  }
  sync(t) {
    if (this.dirty & 2) {
      let e = this.dom, s = null, n;
      for (let r of this.children) {
        if (r.dirty) {
          if (!r.dom && (n = s ? s.nextSibling : e.firstChild)) {
            let o = kt.get(n);
            (!o || !o.parent && o.constructor == r.constructor) && r.reuseDOM(n);
          }
          r.sync(t), r.dirty = 0;
        }
        if (n = s ? s.nextSibling : e.firstChild, t && !t.written && t.node == e && n != r.dom && (t.written = !0), r.dom.parentNode == e)
          for (; n && n != r.dom; )
            n = Ru(n);
        else
          e.insertBefore(r.dom, n);
        s = r.dom;
      }
      for (n = s ? s.nextSibling : e.firstChild, n && t && t.node == e && (t.written = !0); n; )
        n = Ru(n);
    } else if (this.dirty & 1)
      for (let e of this.children)
        e.dirty && (e.sync(t), e.dirty = 0);
  }
  reuseDOM(t) {
  }
  localPosFromDOM(t, e) {
    let s;
    if (t == this.dom)
      s = this.dom.childNodes[e];
    else {
      let n = mn(t) == 0 ? 0 : e == 0 ? -1 : 1;
      for (; ; ) {
        let r = t.parentNode;
        if (r == this.dom)
          break;
        n == 0 && r.firstChild != r.lastChild && (t == r.firstChild ? n = -1 : n = 1), t = r;
      }
      n < 0 ? s = t : s = t.nextSibling;
    }
    if (s == this.dom.firstChild)
      return 0;
    for (; s && !kt.get(s); )
      s = s.nextSibling;
    if (!s)
      return this.length;
    for (let n = 0, r = 0; ; n++) {
      let o = this.children[n];
      if (o.dom == s)
        return r;
      r += o.length + o.breakAfter;
    }
  }
  domBoundsAround(t, e, s = 0) {
    let n = -1, r = -1, o = -1, l = -1;
    for (let a = 0, h = s, u = s; a < this.children.length; a++) {
      let c = this.children[a], f = h + c.length;
      if (h < t && f > e)
        return c.domBoundsAround(t, e, h);
      if (f >= t && n == -1 && (n = a, r = h), h > e && c.dom.parentNode == this.dom) {
        o = a, l = u;
        break;
      }
      u = f, h = f + c.breakAfter;
    }
    return {
      from: r,
      to: l < 0 ? s + this.length : l,
      startDOM: (n ? this.children[n - 1].dom.nextSibling : null) || this.dom.firstChild,
      endDOM: o < this.children.length && o >= 0 ? this.children[o].dom : null
    };
  }
  markDirty(t = !1) {
    this.dirty |= 2, this.markParentsDirty(t);
  }
  markParentsDirty(t) {
    for (let e = this.parent; e; e = e.parent) {
      if (t && (e.dirty |= 2), e.dirty & 1)
        return;
      e.dirty |= 1, t = !1;
    }
  }
  setParent(t) {
    this.parent != t && (this.parent = t, this.dirty && this.markParentsDirty(!0));
  }
  setDOM(t) {
    this.dom && (this.dom.cmView = null), this.dom = t, t.cmView = this;
  }
  get rootView() {
    for (let t = this; ; ) {
      let e = t.parent;
      if (!e)
        return t;
      t = e;
    }
  }
  replaceChildren(t, e, s = ah) {
    this.markDirty();
    for (let n = t; n < e; n++) {
      let r = this.children[n];
      r.parent == this && r.destroy();
    }
    this.children.splice(t, e - t, ...s);
    for (let n = 0; n < s.length; n++)
      s[n].setParent(this);
  }
  ignoreMutation(t) {
    return !1;
  }
  ignoreEvent(t) {
    return !1;
  }
  childCursor(t = this.length) {
    return new fp(this.children, t, this.children.length);
  }
  childPos(t, e = 1) {
    return this.childCursor().findPos(t, e);
  }
  toString() {
    let t = this.constructor.name.replace("View", "");
    return t + (this.children.length ? "(" + this.children.join() + ")" : this.length ? "[" + (t == "Text" ? this.text : this.length) + "]" : "") + (this.breakAfter ? "#" : "");
  }
  static get(t) {
    return t.cmView;
  }
  get isEditable() {
    return !0;
  }
  merge(t, e, s, n, r, o) {
    return !1;
  }
  become(t) {
    return !1;
  }
  getSide() {
    return 0;
  }
  destroy() {
    this.parent = null;
  }
}
kt.prototype.breakAfter = 0;
function Ru(i) {
  let t = i.nextSibling;
  return i.parentNode.removeChild(i), t;
}
class fp {
  constructor(t, e, s) {
    this.children = t, this.pos = e, this.i = s, this.off = 0;
  }
  findPos(t, e = 1) {
    for (; ; ) {
      if (t > this.pos || t == this.pos && (e > 0 || this.i == 0 || this.children[this.i - 1].breakAfter))
        return this.off = t - this.pos, this;
      let s = this.children[--this.i];
      this.pos -= s.length + s.breakAfter;
    }
  }
}
function dp(i, t, e, s, n, r, o, l, a) {
  let { children: h } = i, u = h.length ? h[t] : null, c = r.length ? r[r.length - 1] : null, f = c ? c.breakAfter : o;
  if (!(t == s && u && !o && !f && r.length < 2 && u.merge(e, n, r.length ? c : null, e == 0, l, a))) {
    if (s < h.length) {
      let g = h[s];
      g && n < g.length ? (t == s && (g = g.split(n), n = 0), !f && c && g.merge(0, n, c, !0, 0, a) ? r[r.length - 1] = g : (n && g.merge(0, n, null, !1, 0, a), r.push(g))) : g != null && g.breakAfter && (c ? c.breakAfter = 1 : o = 1), s++;
    }
    for (u && (u.breakAfter = o, e > 0 && (!o && r.length && u.merge(e, u.length, r[0], !1, l, 0) ? u.breakAfter = r.shift().breakAfter : (e < u.length || u.children.length && u.children[u.children.length - 1].length == 0) && u.merge(e, u.length, null, !1, l, 0), t++)); t < s && r.length; )
      if (h[s - 1].become(r[r.length - 1]))
        s--, r.pop(), a = r.length ? 0 : l;
      else if (h[t].become(r[0]))
        t++, r.shift(), l = r.length ? 0 : a;
      else
        break;
    !r.length && t && s < h.length && !h[t - 1].breakAfter && h[s].merge(0, 0, h[t - 1], !1, l, a) && t--, (t < s || r.length) && i.replaceChildren(t, s, r);
  }
}
function pp(i, t, e, s, n, r) {
  let o = i.childCursor(), { i: l, off: a } = o.findPos(e, 1), { i: h, off: u } = o.findPos(t, -1), c = t - e;
  for (let f of s)
    c += f.length;
  i.length += c, dp(i, h, u, l, a, s, 0, n, r);
}
let fe = typeof navigator != "undefined" ? navigator : { userAgent: "", vendor: "", platform: "" }, aa = typeof document != "undefined" ? document : { documentElement: { style: {} } };
const ha = /* @__PURE__ */ /Edge\/(\d+)/.exec(fe.userAgent), gp = /* @__PURE__ */ /MSIE \d/.test(fe.userAgent), ua = /* @__PURE__ */ /Trident\/(?:[7-9]|\d{2,})\..*rv:(\d+)/.exec(fe.userAgent), bo = !!(gp || ua || ha), Du = !bo && /* @__PURE__ */ /gecko\/(\d+)/i.test(fe.userAgent), Uo = !bo && /* @__PURE__ */ /Chrome\/(\d+)/.exec(fe.userAgent), Bu = "webkitFontSmoothing" in aa.documentElement.style, mp = !bo && /* @__PURE__ */ /Apple Computer/.test(fe.vendor), Nu = mp && (/* @__PURE__ */ /Mobile\/\w+/.test(fe.userAgent) || fe.maxTouchPoints > 2);
var j = {
  mac: Nu || /* @__PURE__ */ /Mac/.test(fe.platform),
  windows: /* @__PURE__ */ /Win/.test(fe.platform),
  linux: /* @__PURE__ */ /Linux|X11/.test(fe.platform),
  ie: bo,
  ie_version: gp ? aa.documentMode || 6 : ua ? +ua[1] : ha ? +ha[1] : 0,
  gecko: Du,
  gecko_version: Du ? +(/* @__PURE__ */ /Firefox\/(\d+)/.exec(fe.userAgent) || [0, 0])[1] : 0,
  chrome: !!Uo,
  chrome_version: Uo ? +Uo[1] : 0,
  ios: Nu,
  android: /* @__PURE__ */ /Android\b/.test(fe.userAgent),
  webkit: Bu,
  safari: mp,
  webkit_version: Bu ? +(/* @__PURE__ */ /\bAppleWebKit\/(\d+)/.exec(navigator.userAgent) || [0, 0])[1] : 0,
  tabSize: aa.documentElement.style.tabSize != null ? "tab-size" : "-moz-tab-size"
};
const Wy = 256;
class Ai extends kt {
  constructor(t) {
    super(), this.text = t;
  }
  get length() {
    return this.text.length;
  }
  createDOM(t) {
    this.setDOM(t || document.createTextNode(this.text));
  }
  sync(t) {
    this.dom || this.createDOM(), this.dom.nodeValue != this.text && (t && t.node == this.dom && (t.written = !0), this.dom.nodeValue = this.text);
  }
  reuseDOM(t) {
    t.nodeType == 3 && this.createDOM(t);
  }
  merge(t, e, s) {
    return s && (!(s instanceof Ai) || this.length - (e - t) + s.length > Wy) ? !1 : (this.text = this.text.slice(0, t) + (s ? s.text : "") + this.text.slice(e), this.markDirty(), !0);
  }
  split(t) {
    let e = new Ai(this.text.slice(t));
    return this.text = this.text.slice(0, t), this.markDirty(), e;
  }
  localPosFromDOM(t, e) {
    return t == this.dom ? e : e ? this.text.length : 0;
  }
  domAtPos(t) {
    return new $t(this.dom, t);
  }
  domBoundsAround(t, e, s) {
    return { from: s, to: s + this.length, startDOM: this.dom, endDOM: this.dom.nextSibling };
  }
  coordsAt(t, e) {
    return ca(this.dom, t, e);
  }
}
class je extends kt {
  constructor(t, e = [], s = 0) {
    super(), this.mark = t, this.children = e, this.length = s;
    for (let n of e)
      n.setParent(this);
  }
  setAttrs(t) {
    if (cp(t), this.mark.class && (t.className = this.mark.class), this.mark.attrs)
      for (let e in this.mark.attrs)
        t.setAttribute(e, this.mark.attrs[e]);
    return t;
  }
  reuseDOM(t) {
    t.nodeName == this.mark.tagName.toUpperCase() && (this.setDOM(t), this.dirty |= 6);
  }
  sync(t) {
    this.dom ? this.dirty & 4 && this.setAttrs(this.dom) : this.setDOM(this.setAttrs(document.createElement(this.mark.tagName))), super.sync(t);
  }
  merge(t, e, s, n, r, o) {
    return s && (!(s instanceof je && s.mark.eq(this.mark)) || t && r <= 0 || e < this.length && o <= 0) ? !1 : (pp(this, t, e, s ? s.children : [], r - 1, o - 1), this.markDirty(), !0);
  }
  split(t) {
    let e = [], s = 0, n = -1, r = 0;
    for (let l of this.children) {
      let a = s + l.length;
      a > t && e.push(s < t ? l.split(t - s) : l), n < 0 && s >= t && (n = r), s = a, r++;
    }
    let o = this.length - t;
    return this.length = t, n > -1 && (this.children.length = n, this.markDirty()), new je(this.mark, e, o);
  }
  domAtPos(t) {
    return yp(this.dom, this.children, t);
  }
  coordsAt(t, e) {
    return vp(this, t, e);
  }
}
function ca(i, t, e) {
  let s = i.nodeValue.length;
  t > s && (t = s);
  let n = t, r = t, o = 0;
  t == 0 && e < 0 || t == s && e >= 0 ? j.chrome || j.gecko || (t ? (n--, o = 1) : r < s && (r++, o = -1)) : e < 0 ? n-- : r < s && r++;
  let l = bs(i, n, r).getClientRects();
  if (!l.length)
    return hp;
  let a = l[(o ? o < 0 : e >= 0) ? 0 : l.length - 1];
  return j.safari && !o && a.width == 0 && (a = Array.prototype.find.call(l, (h) => h.width) || a), o ? _o(a, o < 0) : a || null;
}
class gi extends kt {
  constructor(t, e, s) {
    super(), this.widget = t, this.length = e, this.side = s, this.prevWidget = null;
  }
  static create(t, e, s) {
    return new (t.customView || gi)(t, e, s);
  }
  split(t) {
    let e = gi.create(this.widget, this.length - t, this.side);
    return this.length -= t, e;
  }
  sync() {
    (!this.dom || !this.widget.updateDOM(this.dom)) && (this.dom && this.prevWidget && this.prevWidget.destroy(this.dom), this.prevWidget = null, this.setDOM(this.widget.toDOM(this.editorView)), this.dom.contentEditable = "false");
  }
  getSide() {
    return this.side;
  }
  merge(t, e, s, n, r, o) {
    return s && (!(s instanceof gi) || !this.widget.compare(s.widget) || t > 0 && r <= 0 || e < this.length && o <= 0) ? !1 : (this.length = t + (s ? s.length : 0) + (this.length - e), !0);
  }
  become(t) {
    return t.length == this.length && t instanceof gi && t.side == this.side && this.widget.constructor == t.widget.constructor ? (this.widget.eq(t.widget) || this.markDirty(!0), this.dom && !this.prevWidget && (this.prevWidget = this.widget), this.widget = t.widget, !0) : !1;
  }
  ignoreMutation() {
    return !0;
  }
  ignoreEvent(t) {
    return this.widget.ignoreEvent(t);
  }
  get overrideDOMText() {
    if (this.length == 0)
      return ct.empty;
    let t = this;
    for (; t.parent; )
      t = t.parent;
    let e = t.editorView, s = e && e.state.doc, n = this.posAtStart;
    return s ? s.slice(n, n + this.length) : ct.empty;
  }
  domAtPos(t) {
    return t == 0 ? $t.before(this.dom) : $t.after(this.dom, t == this.length);
  }
  domBoundsAround() {
    return null;
  }
  coordsAt(t, e) {
    let s = this.dom.getClientRects(), n = null;
    if (!s.length)
      return hp;
    for (let r = t > 0 ? s.length - 1 : 0; n = s[r], !(t > 0 ? r == 0 : r == s.length - 1 || n.top < n.bottom); r += t > 0 ? -1 : 1)
      ;
    return t == 0 && e > 0 || t == this.length && e <= 0 ? n : _o(n, t == 0);
  }
  get isEditable() {
    return !1;
  }
  destroy() {
    super.destroy(), this.dom && this.widget.destroy(this.dom);
  }
}
class _p extends gi {
  domAtPos(t) {
    let { topView: e, text: s } = this.widget;
    return e ? fa(t, 0, e, s, (n, r) => n.domAtPos(r), (n) => new $t(s, Math.min(n, s.nodeValue.length))) : new $t(s, Math.min(t, s.nodeValue.length));
  }
  sync() {
    this.setDOM(this.widget.toDOM());
  }
  localPosFromDOM(t, e) {
    let { topView: s, text: n } = this.widget;
    return s ? bp(t, e, s, n) : Math.min(e, this.length);
  }
  ignoreMutation() {
    return !1;
  }
  get overrideDOMText() {
    return null;
  }
  coordsAt(t, e) {
    let { topView: s, text: n } = this.widget;
    return s ? fa(t, e, s, n, (r, o, l) => r.coordsAt(o, l), (r, o) => ca(n, r, o)) : ca(n, t, e);
  }
  destroy() {
    var t;
    super.destroy(), (t = this.widget.topView) === null || t === void 0 || t.destroy();
  }
  get isEditable() {
    return !0;
  }
}
function fa(i, t, e, s, n, r) {
  if (e instanceof je) {
    for (let o of e.children) {
      let l = _s(o.dom, s), a = l ? s.nodeValue.length : o.length;
      if (i < a || i == a && o.getSide() <= 0)
        return l ? fa(i, t, o, s, n, r) : n(o, i, t);
      i -= a;
    }
    return n(e, e.length, -1);
  } else
    return e.dom == s ? r(i, t) : n(e, i, t);
}
function bp(i, t, e, s) {
  if (e instanceof je)
    for (let n of e.children) {
      let r = 0, o = _s(n.dom, s);
      if (_s(n.dom, i))
        return r + (o ? bp(i, t, n, s) : n.localPosFromDOM(i, t));
      r += o ? s.nodeValue.length : n.length;
    }
  else if (e.dom == s)
    return Math.min(t, s.nodeValue.length);
  return e.localPosFromDOM(i, t);
}
class ys extends kt {
  constructor(t) {
    super(), this.side = t;
  }
  get length() {
    return 0;
  }
  merge() {
    return !1;
  }
  become(t) {
    return t instanceof ys && t.side == this.side;
  }
  split() {
    return new ys(this.side);
  }
  sync() {
    if (!this.dom) {
      let t = document.createElement("img");
      t.className = "cm-widgetBuffer", t.setAttribute("aria-hidden", "true"), this.setDOM(t);
    }
  }
  getSide() {
    return this.side;
  }
  domAtPos(t) {
    return $t.before(this.dom);
  }
  localPosFromDOM() {
    return 0;
  }
  domBoundsAround() {
    return null;
  }
  coordsAt(t) {
    let e = this.dom.getBoundingClientRect(), s = $y(this, this.side > 0 ? -1 : 1);
    return s && s.top < e.bottom && s.bottom > e.top ? { left: e.left, right: e.right, top: s.top, bottom: s.bottom } : e;
  }
  get overrideDOMText() {
    return ct.empty;
  }
}
Ai.prototype.children = gi.prototype.children = ys.prototype.children = ah;
function $y(i, t) {
  let e = i.parent, s = e ? e.children.indexOf(i) : -1;
  for (; e && s >= 0; )
    if (t < 0 ? s > 0 : s < e.children.length) {
      let n = e.children[s + t];
      if (n instanceof Ai) {
        let r = n.coordsAt(t < 0 ? n.length : 0, t);
        if (r)
          return r;
      }
      s += t;
    } else if (e instanceof je && e.parent)
      s = e.parent.children.indexOf(e) + (t < 0 ? 0 : 1), e = e.parent;
    else {
      let n = e.dom.lastChild;
      if (n && n.nodeName == "BR")
        return n.getClientRects()[0];
      break;
    }
}
function yp(i, t, e) {
  let s = 0;
  for (let n = 0; s < t.length; s++) {
    let r = t[s], o = n + r.length;
    if (!(o == n && r.getSide() <= 0)) {
      if (e > n && e < o && r.dom.parentNode == i)
        return r.domAtPos(e - n);
      if (e <= n)
        break;
      n = o;
    }
  }
  for (; s > 0; s--) {
    let n = t[s - 1].dom;
    if (n.parentNode == i)
      return $t.after(n);
  }
  return new $t(i, 0);
}
function wp(i, t, e) {
  let s, { children: n } = i;
  e > 0 && t instanceof je && n.length && (s = n[n.length - 1]) instanceof je && s.mark.eq(t.mark) ? wp(s, t.children[0], e - 1) : (n.push(t), t.setParent(i)), i.length += t.length;
}
function vp(i, t, e) {
  for (let r = 0, o = 0; o < i.children.length; o++) {
    let l = i.children[o], a = r + l.length, h;
    if ((e <= 0 || a == i.length || l.getSide() > 0 ? a >= t : a > t) && (t < a || o + 1 == i.children.length || (h = i.children[o + 1]).length || h.getSide() > 0)) {
      let u = 0;
      if (a == r) {
        if (l.getSide() <= 0)
          continue;
        u = e = -l.getSide();
      }
      let c = l.coordsAt(Math.max(0, t - r), e);
      return u && c ? _o(c, e < 0) : c;
    }
    r = a;
  }
  let s = i.dom.lastChild;
  if (!s)
    return i.dom.getBoundingClientRect();
  let n = gn(s);
  return n[n.length - 1] || null;
}
function da(i, t) {
  for (let e in i)
    e == "class" && t.class ? t.class += " " + i.class : e == "style" && t.style ? t.style += ";" + i.style : t[e] = i[e];
  return t;
}
function hh(i, t) {
  if (i == t)
    return !0;
  if (!i || !t)
    return !1;
  let e = Object.keys(i), s = Object.keys(t);
  if (e.length != s.length)
    return !1;
  for (let n of e)
    if (s.indexOf(n) == -1 || i[n] !== t[n])
      return !1;
  return !0;
}
function pa(i, t, e) {
  let s = null;
  if (t)
    for (let n in t)
      e && n in e || i.removeAttribute(s = n);
  if (e)
    for (let n in e)
      t && t[n] == e[n] || i.setAttribute(s = n, e[n]);
  return !!s;
}
class ni {
  eq(t) {
    return !1;
  }
  updateDOM(t) {
    return !1;
  }
  compare(t) {
    return this == t || this.constructor == t.constructor && this.eq(t);
  }
  get estimatedHeight() {
    return -1;
  }
  ignoreEvent(t) {
    return !0;
  }
  get customView() {
    return null;
  }
  destroy(t) {
  }
}
var _t = /* @__PURE__ */ function(i) {
  return i[i.Text = 0] = "Text", i[i.WidgetBefore = 1] = "WidgetBefore", i[i.WidgetAfter = 2] = "WidgetAfter", i[i.WidgetRange = 3] = "WidgetRange", i;
}(_t || (_t = {}));
class X extends ji {
  constructor(t, e, s, n) {
    super(), this.startSide = t, this.endSide = e, this.widget = s, this.spec = n;
  }
  get heightRelevant() {
    return !1;
  }
  static mark(t) {
    return new yo(t);
  }
  static widget(t) {
    let e = t.side || 0, s = !!t.block;
    return e += s ? e > 0 ? 3e8 : -4e8 : e > 0 ? 1e8 : -1e8, new Hi(t, e, e, s, t.widget || null, !1);
  }
  static replace(t) {
    let e = !!t.block, s, n;
    if (t.isBlockGap)
      s = -5e8, n = 4e8;
    else {
      let { start: r, end: o } = xp(t, e);
      s = (r ? e ? -3e8 : -1 : 5e8) - 1, n = (o ? e ? 2e8 : 1 : -6e8) + 1;
    }
    return new Hi(t, s, n, e, t.widget || null, !0);
  }
  static line(t) {
    return new Cn(t);
  }
  static set(t, e = !1) {
    return ft.of(t, e);
  }
  hasHeight() {
    return this.widget ? this.widget.estimatedHeight > -1 : !1;
  }
}
X.none = ft.empty;
class yo extends X {
  constructor(t) {
    let { start: e, end: s } = xp(t);
    super(e ? -1 : 5e8, s ? 1 : -6e8, null, t), this.tagName = t.tagName || "span", this.class = t.class || "", this.attrs = t.attributes || null;
  }
  eq(t) {
    return this == t || t instanceof yo && this.tagName == t.tagName && this.class == t.class && hh(this.attrs, t.attrs);
  }
  range(t, e = t) {
    if (t >= e)
      throw new RangeError("Mark decorations may not be empty");
    return super.range(t, e);
  }
}
yo.prototype.point = !1;
class Cn extends X {
  constructor(t) {
    super(-2e8, -2e8, null, t);
  }
  eq(t) {
    return t instanceof Cn && hh(this.spec.attributes, t.spec.attributes);
  }
  range(t, e = t) {
    if (e != t)
      throw new RangeError("Line decoration ranges must be zero-length");
    return super.range(t, e);
  }
}
Cn.prototype.mapMode = Gt.TrackBefore;
Cn.prototype.point = !0;
class Hi extends X {
  constructor(t, e, s, n, r, o) {
    super(e, s, r, t), this.block = n, this.isReplace = o, this.mapMode = n ? e <= 0 ? Gt.TrackBefore : Gt.TrackAfter : Gt.TrackDel;
  }
  get type() {
    return this.startSide < this.endSide ? _t.WidgetRange : this.startSide <= 0 ? _t.WidgetBefore : _t.WidgetAfter;
  }
  get heightRelevant() {
    return this.block || !!this.widget && this.widget.estimatedHeight >= 5;
  }
  eq(t) {
    return t instanceof Hi && Fy(this.widget, t.widget) && this.block == t.block && this.startSide == t.startSide && this.endSide == t.endSide;
  }
  range(t, e = t) {
    if (this.isReplace && (t > e || t == e && this.startSide > 0 && this.endSide <= 0))
      throw new RangeError("Invalid range for replacement decoration");
    if (!this.isReplace && e != t)
      throw new RangeError("Widget decorations can only have zero-length ranges");
    return super.range(t, e);
  }
}
Hi.prototype.point = !0;
function xp(i, t = !1) {
  let { inclusiveStart: e, inclusiveEnd: s } = i;
  return e == null && (e = i.inclusive), s == null && (s = i.inclusive), { start: e != null ? e : t, end: s != null ? s : t };
}
function Fy(i, t) {
  return i == t || !!(i && t && i.compare(t));
}
function ga(i, t, e, s = 0) {
  let n = e.length - 1;
  n >= 0 && e[n] + s >= i ? e[n] = Math.max(e[n], t) : e.push(i, t);
}
class Yt extends kt {
  constructor() {
    super(...arguments), this.children = [], this.length = 0, this.prevAttrs = void 0, this.attrs = null, this.breakAfter = 0;
  }
  merge(t, e, s, n, r, o) {
    if (s) {
      if (!(s instanceof Yt))
        return !1;
      this.dom || s.transferDOM(this);
    }
    return n && this.setDeco(s ? s.attrs : null), pp(this, t, e, s ? s.children : [], r, o), !0;
  }
  split(t) {
    let e = new Yt();
    if (e.breakAfter = this.breakAfter, this.length == 0)
      return e;
    let { i: s, off: n } = this.childPos(t);
    n && (e.append(this.children[s].split(n), 0), this.children[s].merge(n, this.children[s].length, null, !1, 0, 0), s++);
    for (let r = s; r < this.children.length; r++)
      e.append(this.children[r], 0);
    for (; s > 0 && this.children[s - 1].length == 0; )
      this.children[--s].destroy();
    return this.children.length = s, this.markDirty(), this.length = t, e;
  }
  transferDOM(t) {
    !this.dom || (this.markDirty(), t.setDOM(this.dom), t.prevAttrs = this.prevAttrs === void 0 ? this.attrs : this.prevAttrs, this.prevAttrs = void 0, this.dom = null);
  }
  setDeco(t) {
    hh(this.attrs, t) || (this.dom && (this.prevAttrs = this.attrs, this.markDirty()), this.attrs = t);
  }
  append(t, e) {
    wp(this, t, e);
  }
  addLineDeco(t) {
    let e = t.spec.attributes, s = t.spec.class;
    e && (this.attrs = da(e, this.attrs || {})), s && (this.attrs = da({ class: s }, this.attrs || {}));
  }
  domAtPos(t) {
    return yp(this.dom, this.children, t);
  }
  reuseDOM(t) {
    t.nodeName == "DIV" && (this.setDOM(t), this.dirty |= 6);
  }
  sync(t) {
    var e;
    this.dom ? this.dirty & 4 && (cp(this.dom), this.dom.className = "cm-line", this.prevAttrs = this.attrs ? null : void 0) : (this.setDOM(document.createElement("div")), this.dom.className = "cm-line", this.prevAttrs = this.attrs ? null : void 0), this.prevAttrs !== void 0 && (pa(this.dom, this.prevAttrs, this.attrs), this.dom.classList.add("cm-line"), this.prevAttrs = void 0), super.sync(t);
    let s = this.dom.lastChild;
    for (; s && kt.get(s) instanceof je; )
      s = s.lastChild;
    if (!s || !this.length || s.nodeName != "BR" && ((e = kt.get(s)) === null || e === void 0 ? void 0 : e.isEditable) == !1 && (!j.ios || !this.children.some((n) => n instanceof Ai))) {
      let n = document.createElement("BR");
      n.cmIgnore = !0, this.dom.appendChild(n);
    }
  }
  measureTextSize() {
    if (this.children.length == 0 || this.length > 20)
      return null;
    let t = 0;
    for (let e of this.children) {
      if (!(e instanceof Ai))
        return null;
      let s = gn(e.dom);
      if (s.length != 1)
        return null;
      t += s[0].width;
    }
    return {
      lineHeight: this.dom.getBoundingClientRect().height,
      charWidth: t / this.length
    };
  }
  coordsAt(t, e) {
    return vp(this, t, e);
  }
  become(t) {
    return !1;
  }
  get type() {
    return _t.Text;
  }
  static find(t, e) {
    for (let s = 0, n = 0; s < t.children.length; s++) {
      let r = t.children[s], o = n + r.length;
      if (o >= e) {
        if (r instanceof Yt)
          return r;
        if (o > e)
          break;
      }
      n = o + r.breakAfter;
    }
    return null;
  }
}
class $i extends kt {
  constructor(t, e, s) {
    super(), this.widget = t, this.length = e, this.type = s, this.breakAfter = 0, this.prevWidget = null;
  }
  merge(t, e, s, n, r, o) {
    return s && (!(s instanceof $i) || !this.widget.compare(s.widget) || t > 0 && r <= 0 || e < this.length && o <= 0) ? !1 : (this.length = t + (s ? s.length : 0) + (this.length - e), !0);
  }
  domAtPos(t) {
    return t == 0 ? $t.before(this.dom) : $t.after(this.dom, t == this.length);
  }
  split(t) {
    let e = this.length - t;
    this.length = t;
    let s = new $i(this.widget, e, this.type);
    return s.breakAfter = this.breakAfter, s;
  }
  get children() {
    return ah;
  }
  sync() {
    (!this.dom || !this.widget.updateDOM(this.dom)) && (this.dom && this.prevWidget && this.prevWidget.destroy(this.dom), this.prevWidget = null, this.setDOM(this.widget.toDOM(this.editorView)), this.dom.contentEditable = "false");
  }
  get overrideDOMText() {
    return this.parent ? this.parent.view.state.doc.slice(this.posAtStart, this.posAtEnd) : ct.empty;
  }
  domBoundsAround() {
    return null;
  }
  become(t) {
    return t instanceof $i && t.type == this.type && t.widget.constructor == this.widget.constructor ? (t.widget.eq(this.widget) || this.markDirty(!0), this.dom && !this.prevWidget && (this.prevWidget = this.widget), this.widget = t.widget, this.length = t.length, this.breakAfter = t.breakAfter, !0) : !1;
  }
  ignoreMutation() {
    return !0;
  }
  ignoreEvent(t) {
    return this.widget.ignoreEvent(t);
  }
  destroy() {
    super.destroy(), this.dom && this.widget.destroy(this.dom);
  }
}
class uh {
  constructor(t, e, s, n) {
    this.doc = t, this.pos = e, this.end = s, this.disallowBlockEffectsFor = n, this.content = [], this.curLine = null, this.breakAtStart = 0, this.pendingBuffer = 0, this.atCursorPos = !0, this.openStart = -1, this.openEnd = -1, this.text = "", this.textOff = 0, this.cursor = t.iter(), this.skip = e;
  }
  posCovered() {
    if (this.content.length == 0)
      return !this.breakAtStart && this.doc.lineAt(this.pos).from != this.pos;
    let t = this.content[this.content.length - 1];
    return !t.breakAfter && !(t instanceof $i && t.type == _t.WidgetBefore);
  }
  getLine() {
    return this.curLine || (this.content.push(this.curLine = new Yt()), this.atCursorPos = !0), this.curLine;
  }
  flushBuffer(t) {
    this.pendingBuffer && (this.curLine.append(zn(new ys(-1), t), t.length), this.pendingBuffer = 0);
  }
  addBlockWidget(t) {
    this.flushBuffer([]), this.curLine = null, this.content.push(t);
  }
  finish(t) {
    t ? this.pendingBuffer = 0 : this.flushBuffer([]), this.posCovered() || this.getLine();
  }
  buildText(t, e, s) {
    for (; t > 0; ) {
      if (this.textOff == this.text.length) {
        let { value: r, lineBreak: o, done: l } = this.cursor.next(this.skip);
        if (this.skip = 0, l)
          throw new Error("Ran out of text content when drawing inline views");
        if (o) {
          this.posCovered() || this.getLine(), this.content.length ? this.content[this.content.length - 1].breakAfter = 1 : this.breakAtStart = 1, this.flushBuffer([]), this.curLine = null, t--;
          continue;
        } else
          this.text = r, this.textOff = 0;
      }
      let n = Math.min(this.text.length - this.textOff, t, 512);
      this.flushBuffer(e.slice(0, s)), this.getLine().append(zn(new Ai(this.text.slice(this.textOff, this.textOff + n)), e), s), this.atCursorPos = !0, this.textOff += n, t -= n, s = 0;
    }
  }
  span(t, e, s, n) {
    this.buildText(e - t, s, n), this.pos = e, this.openStart < 0 && (this.openStart = n);
  }
  point(t, e, s, n, r, o) {
    if (this.disallowBlockEffectsFor[o] && s instanceof Hi) {
      if (s.block)
        throw new RangeError("Block decorations may not be specified via plugins");
      if (e > this.doc.lineAt(this.pos).to)
        throw new RangeError("Decorations that replace line breaks may not be specified via plugins");
    }
    let l = e - t;
    if (s instanceof Hi)
      if (s.block) {
        let { type: a } = s;
        a == _t.WidgetAfter && !this.posCovered() && this.getLine(), this.addBlockWidget(new $i(s.widget || new Lu("div"), l, a));
      } else {
        let a = gi.create(s.widget || new Lu("span"), l, s.startSide), h = this.atCursorPos && !a.isEditable && r <= n.length && (t < e || s.startSide > 0), u = !a.isEditable && (t < e || s.startSide <= 0), c = this.getLine();
        this.pendingBuffer == 2 && !h && (this.pendingBuffer = 0), this.flushBuffer(n), h && (c.append(zn(new ys(1), n), r), r = n.length + Math.max(0, r - n.length)), c.append(zn(a, n), r), this.atCursorPos = u, this.pendingBuffer = u ? t < e ? 1 : 2 : 0;
      }
    else
      this.doc.lineAt(this.pos).from == this.pos && this.getLine().addLineDeco(s);
    l && (this.textOff + l <= this.text.length ? this.textOff += l : (this.skip += l - (this.text.length - this.textOff), this.text = "", this.textOff = 0), this.pos = e), this.openStart < 0 && (this.openStart = r);
  }
  static build(t, e, s, n, r) {
    let o = new uh(t, e, s, r);
    return o.openEnd = ft.spans(n, e, s, o), o.openStart < 0 && (o.openStart = o.openEnd), o.finish(o.openEnd), o;
  }
}
function zn(i, t) {
  for (let e of t)
    i = new je(e, [i], i.length);
  return i;
}
class Lu extends ni {
  constructor(t) {
    super(), this.tag = t;
  }
  eq(t) {
    return t.tag == this.tag;
  }
  toDOM() {
    return document.createElement(this.tag);
  }
  updateDOM(t) {
    return t.nodeName.toLowerCase() == this.tag;
  }
}
const kp = /* @__PURE__ */ q.define(), Op = /* @__PURE__ */ q.define(), Sp = /* @__PURE__ */ q.define(), Cp = /* @__PURE__ */ q.define(), ma = /* @__PURE__ */ q.define(), Ap = /* @__PURE__ */ q.define(), Tp = /* @__PURE__ */ q.define({
  combine: (i) => i.some((t) => t)
});
class Dr {
  constructor(t, e = "nearest", s = "nearest", n = 5, r = 5) {
    this.range = t, this.y = e, this.x = s, this.yMargin = n, this.xMargin = r;
  }
  map(t) {
    return t.empty ? this : new Dr(this.range.map(t), this.y, this.x, this.yMargin, this.xMargin);
  }
}
const Iu = /* @__PURE__ */ rt.define({ map: (i, t) => i.map(t) });
function ge(i, t, e) {
  let s = i.facet(Cp);
  s.length ? s[0](t) : window.onerror ? window.onerror(String(t), e, void 0, void 0, t) : e ? console.error(e + ":", t) : console.error(t);
}
const wo = /* @__PURE__ */ q.define({ combine: (i) => i.length ? i[0] : !0 });
let Vy = 0;
const Vs = /* @__PURE__ */ q.define();
class At {
  constructor(t, e, s, n) {
    this.id = t, this.create = e, this.domEventHandlers = s, this.extension = n(this);
  }
  static define(t, e) {
    const { eventHandlers: s, provide: n, decorations: r } = e || {};
    return new At(Vy++, t, s, (o) => {
      let l = [Vs.of(o)];
      return r && l.push(_n.of((a) => {
        let h = a.plugin(o);
        return h ? r(h) : X.none;
      })), n && l.push(n(o)), l;
    });
  }
  static fromClass(t, e) {
    return At.define((s) => new t(s), e);
  }
}
class jo {
  constructor(t) {
    this.spec = t, this.mustUpdate = null, this.value = null;
  }
  update(t) {
    if (this.value) {
      if (this.mustUpdate) {
        let e = this.mustUpdate;
        if (this.mustUpdate = null, this.value.update)
          try {
            this.value.update(e);
          } catch (s) {
            if (ge(e.state, s, "CodeMirror plugin crashed"), this.value.destroy)
              try {
                this.value.destroy();
              } catch (n) {
              }
            this.deactivate();
          }
      }
    } else if (this.spec)
      try {
        this.value = this.spec.create(t);
      } catch (e) {
        ge(t.state, e, "CodeMirror plugin crashed"), this.deactivate();
      }
    return this;
  }
  destroy(t) {
    var e;
    if (!((e = this.value) === null || e === void 0) && e.destroy)
      try {
        this.value.destroy();
      } catch (s) {
        ge(t.state, s, "CodeMirror plugin crashed");
      }
  }
  deactivate() {
    this.spec = this.value = null;
  }
}
const Pp = /* @__PURE__ */ q.define(), Ep = /* @__PURE__ */ q.define(), _n = /* @__PURE__ */ q.define(), Mp = /* @__PURE__ */ q.define(), Rp = /* @__PURE__ */ q.define(), Us = /* @__PURE__ */ q.define();
class Ye {
  constructor(t, e, s, n) {
    this.fromA = t, this.toA = e, this.fromB = s, this.toB = n;
  }
  join(t) {
    return new Ye(Math.min(this.fromA, t.fromA), Math.max(this.toA, t.toA), Math.min(this.fromB, t.fromB), Math.max(this.toB, t.toB));
  }
  addToSet(t) {
    let e = t.length, s = this;
    for (; e > 0; e--) {
      let n = t[e - 1];
      if (!(n.fromA > s.toA)) {
        if (n.toA < s.fromA)
          break;
        s = s.join(n), t.splice(e - 1, 1);
      }
    }
    return t.splice(e, 0, s), t;
  }
  static extendWithRanges(t, e) {
    if (e.length == 0)
      return t;
    let s = [];
    for (let n = 0, r = 0, o = 0, l = 0; ; n++) {
      let a = n == t.length ? null : t[n], h = o - l, u = a ? a.fromB : 1e9;
      for (; r < e.length && e[r] < u; ) {
        let c = e[r], f = e[r + 1], g = Math.max(l, c), _ = Math.min(u, f);
        if (g <= _ && new Ye(g + h, _ + h, g, _).addToSet(s), f > u)
          break;
        r += 2;
      }
      if (!a)
        return s;
      new Ye(a.fromA, a.toA, a.fromB, a.toB).addToSet(s), o = a.toA, l = a.toB;
    }
  }
}
class Br {
  constructor(t, e, s) {
    this.view = t, this.state = e, this.transactions = s, this.flags = 0, this.startState = t.state, this.changes = Pt.empty(this.startState.doc.length);
    for (let o of s)
      this.changes = this.changes.compose(o.changes);
    let n = [];
    this.changes.iterChangedRanges((o, l, a, h) => n.push(new Ye(o, l, a, h))), this.changedRanges = n;
    let r = t.hasFocus;
    r != t.inputState.notifiedFocused && (t.inputState.notifiedFocused = r, this.flags |= 1);
  }
  static create(t, e, s) {
    return new Br(t, e, s);
  }
  get viewportChanged() {
    return (this.flags & 4) > 0;
  }
  get heightChanged() {
    return (this.flags & 2) > 0;
  }
  get geometryChanged() {
    return this.docChanged || (this.flags & 10) > 0;
  }
  get focusChanged() {
    return (this.flags & 1) > 0;
  }
  get docChanged() {
    return !this.changes.empty;
  }
  get selectionSet() {
    return this.transactions.some((t) => t.selection);
  }
  get empty() {
    return this.flags == 0 && this.transactions.length == 0;
  }
}
var St = /* @__PURE__ */ function(i) {
  return i[i.LTR = 0] = "LTR", i[i.RTL = 1] = "RTL", i;
}(St || (St = {}));
const _a = St.LTR, Uy = St.RTL;
function Dp(i) {
  let t = [];
  for (let e = 0; e < i.length; e++)
    t.push(1 << +i[e]);
  return t;
}
const jy = /* @__PURE__ */ Dp("88888888888888888888888888888888888666888888787833333333337888888000000000000000000000000008888880000000000000000000000000088888888888888888888888888888888888887866668888088888663380888308888800000000000000000000000800000000000000000000000000000008"), Hy = /* @__PURE__ */ Dp("4444448826627288999999999992222222222222222222222222222222222222222222222229999999999999999999994444444444644222822222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222999999949999999229989999223333333333"), ba = /* @__PURE__ */ Object.create(null), Re = [];
for (let i of ["()", "[]", "{}"]) {
  let t = /* @__PURE__ */ i.charCodeAt(0), e = /* @__PURE__ */ i.charCodeAt(1);
  ba[t] = e, ba[e] = -t;
}
function qy(i) {
  return i <= 247 ? jy[i] : 1424 <= i && i <= 1524 ? 2 : 1536 <= i && i <= 1785 ? Hy[i - 1536] : 1774 <= i && i <= 2220 ? 4 : 8192 <= i && i <= 8203 || i == 8204 ? 256 : 1;
}
const Ky = /[\u0590-\u05f4\u0600-\u06ff\u0700-\u08ac]/;
class fs {
  constructor(t, e, s) {
    this.from = t, this.to = e, this.level = s;
  }
  get dir() {
    return this.level % 2 ? Uy : _a;
  }
  side(t, e) {
    return this.dir == e == t ? this.to : this.from;
  }
  static find(t, e, s, n) {
    let r = -1;
    for (let o = 0; o < t.length; o++) {
      let l = t[o];
      if (l.from <= e && l.to >= e) {
        if (l.level == s)
          return o;
        (r < 0 || (n != 0 ? n < 0 ? l.from < e : l.to > e : t[r].level > l.level)) && (r = o);
      }
    }
    if (r < 0)
      throw new RangeError("Index out of range");
    return r;
  }
}
const Ot = [];
function Xy(i, t) {
  let e = i.length, s = t == _a ? 1 : 2, n = t == _a ? 2 : 1;
  if (!i || s == 1 && !Ky.test(i))
    return Bp(e);
  for (let o = 0, l = s, a = s; o < e; o++) {
    let h = qy(i.charCodeAt(o));
    h == 512 ? h = l : h == 8 && a == 4 && (h = 16), Ot[o] = h == 4 ? 2 : h, h & 7 && (a = h), l = h;
  }
  for (let o = 0, l = s, a = s; o < e; o++) {
    let h = Ot[o];
    if (h == 128)
      o < e - 1 && l == Ot[o + 1] && l & 24 ? h = Ot[o] = l : Ot[o] = 256;
    else if (h == 64) {
      let u = o + 1;
      for (; u < e && Ot[u] == 64; )
        u++;
      let c = o && l == 8 || u < e && Ot[u] == 8 ? a == 1 ? 1 : 8 : 256;
      for (let f = o; f < u; f++)
        Ot[f] = c;
      o = u - 1;
    } else
      h == 8 && a == 1 && (Ot[o] = 1);
    l = h, h & 7 && (a = h);
  }
  for (let o = 0, l = 0, a = 0, h, u, c; o < e; o++)
    if (u = ba[h = i.charCodeAt(o)])
      if (u < 0) {
        for (let f = l - 3; f >= 0; f -= 3)
          if (Re[f + 1] == -u) {
            let g = Re[f + 2], _ = g & 2 ? s : g & 4 ? g & 1 ? n : s : 0;
            _ && (Ot[o] = Ot[Re[f]] = _), l = f;
            break;
          }
      } else {
        if (Re.length == 189)
          break;
        Re[l++] = o, Re[l++] = h, Re[l++] = a;
      }
    else if ((c = Ot[o]) == 2 || c == 1) {
      let f = c == s;
      a = f ? 0 : 1;
      for (let g = l - 3; g >= 0; g -= 3) {
        let _ = Re[g + 2];
        if (_ & 2)
          break;
        if (f)
          Re[g + 2] |= 2;
        else {
          if (_ & 4)
            break;
          Re[g + 2] |= 4;
        }
      }
    }
  for (let o = 0; o < e; o++)
    if (Ot[o] == 256) {
      let l = o + 1;
      for (; l < e && Ot[l] == 256; )
        l++;
      let a = (o ? Ot[o - 1] : s) == 1, h = (l < e ? Ot[l] : s) == 1, u = a == h ? a ? 1 : 2 : s;
      for (let c = o; c < l; c++)
        Ot[c] = u;
      o = l - 1;
    }
  let r = [];
  if (s == 1)
    for (let o = 0; o < e; ) {
      let l = o, a = Ot[o++] != 1;
      for (; o < e && a == (Ot[o] != 1); )
        o++;
      if (a)
        for (let h = o; h > l; ) {
          let u = h, c = Ot[--h] != 2;
          for (; h > l && c == (Ot[h - 1] != 2); )
            h--;
          r.push(new fs(h, u, c ? 2 : 1));
        }
      else
        r.push(new fs(l, o, 0));
    }
  else
    for (let o = 0; o < e; ) {
      let l = o, a = Ot[o++] == 2;
      for (; o < e && a == (Ot[o] == 2); )
        o++;
      r.push(new fs(l, o, a ? 1 : 2));
    }
  return r;
}
function Bp(i) {
  return [new fs(0, i, 0)];
}
let Np = "";
function Gy(i, t, e, s, n) {
  var r;
  let o = s.head - i.from, l = -1;
  if (o == 0) {
    if (!n || !i.length)
      return null;
    t[0].level != e && (o = t[0].side(!1, e), l = 0);
  } else if (o == i.length) {
    if (n)
      return null;
    let f = t[t.length - 1];
    f.level != e && (o = f.side(!0, e), l = t.length - 1);
  }
  l < 0 && (l = fs.find(t, o, (r = s.bidiLevel) !== null && r !== void 0 ? r : -1, s.assoc));
  let a = t[l];
  o == a.side(n, e) && (a = t[l += n ? 1 : -1], o = a.side(!n, e));
  let h = n == (a.dir == e), u = de(i.text, o, h);
  if (Np = i.text.slice(Math.min(o, u), Math.max(o, u)), u != a.side(n, e))
    return R.cursor(u + i.from, h ? -1 : 1, a.level);
  let c = l == (n ? t.length - 1 : 0) ? null : t[l + (n ? 1 : -1)];
  return !c && a.level != e ? R.cursor(n ? i.to : i.from, n ? -1 : 1, e) : c && c.level < a.level ? R.cursor(c.side(!n, e) + i.from, n ? 1 : -1, c.level) : R.cursor(u + i.from, n ? -1 : 1, a.level);
}
const mi = "\uFFFF";
class Lp {
  constructor(t, e) {
    this.points = t, this.text = "", this.lineSeparator = e.facet(at.lineSeparator);
  }
  append(t) {
    this.text += t;
  }
  lineBreak() {
    this.text += mi;
  }
  readRange(t, e) {
    if (!t)
      return this;
    let s = t.parentNode;
    for (let n = t; ; ) {
      this.findPointBefore(s, n), this.readNode(n);
      let r = n.nextSibling;
      if (r == e)
        break;
      let o = kt.get(n), l = kt.get(r);
      (o && l ? o.breakAfter : (o ? o.breakAfter : Qu(n)) || Qu(r) && (n.nodeName != "BR" || n.cmIgnore)) && this.lineBreak(), n = r;
    }
    return this.findPointBefore(s, e), this;
  }
  readTextNode(t) {
    let e = t.nodeValue;
    for (let s of this.points)
      s.node == t && (s.pos = this.text.length + Math.min(s.offset, e.length));
    for (let s = 0, n = this.lineSeparator ? null : /\r\n?|\n/g; ; ) {
      let r = -1, o = 1, l;
      if (this.lineSeparator ? (r = e.indexOf(this.lineSeparator, s), o = this.lineSeparator.length) : (l = n.exec(e)) && (r = l.index, o = l[0].length), this.append(e.slice(s, r < 0 ? e.length : r)), r < 0)
        break;
      if (this.lineBreak(), o > 1)
        for (let a of this.points)
          a.node == t && a.pos > this.text.length && (a.pos -= o - 1);
      s = r + o;
    }
  }
  readNode(t) {
    if (t.cmIgnore)
      return;
    let e = kt.get(t), s = e && e.overrideDOMText;
    if (s != null) {
      this.findPointInside(t, s.length);
      for (let n = s.iter(); !n.next().done; )
        n.lineBreak ? this.lineBreak() : this.append(n.value);
    } else
      t.nodeType == 3 ? this.readTextNode(t) : t.nodeName == "BR" ? t.nextSibling && this.lineBreak() : t.nodeType == 1 && this.readRange(t.firstChild, null);
  }
  findPointBefore(t, e) {
    for (let s of this.points)
      s.node == t && t.childNodes[s.offset] == e && (s.pos = this.text.length);
  }
  findPointInside(t, e) {
    for (let s of this.points)
      (t.nodeType == 3 ? s.node == t : t.contains(s.node)) && (s.pos = this.text.length + Math.min(e, s.offset));
  }
}
function Qu(i) {
  return i.nodeType == 1 && /^(DIV|P|LI|UL|OL|BLOCKQUOTE|DD|DT|H\d|SECTION|PRE)$/.test(i.nodeName);
}
class zu {
  constructor(t, e) {
    this.node = t, this.offset = e, this.pos = -1;
  }
}
class Wu extends kt {
  constructor(t) {
    super(), this.view = t, this.compositionDeco = X.none, this.decorations = [], this.dynamicDecorationMap = [], this.minWidth = 0, this.minWidthFrom = 0, this.minWidthTo = 0, this.impreciseAnchor = null, this.impreciseHead = null, this.forceSelection = !1, this.lastUpdate = Date.now(), this.setDOM(t.contentDOM), this.children = [new Yt()], this.children[0].setParent(this), this.updateDeco(), this.updateInner([new Ye(0, 0, 0, t.state.doc.length)], 0);
  }
  get root() {
    return this.view.root;
  }
  get editorView() {
    return this.view;
  }
  get length() {
    return this.view.state.doc.length;
  }
  update(t) {
    let e = t.changedRanges;
    this.minWidth > 0 && e.length && (e.every(({ fromA: o, toA: l }) => l < this.minWidthFrom || o > this.minWidthTo) ? (this.minWidthFrom = t.changes.mapPos(this.minWidthFrom, 1), this.minWidthTo = t.changes.mapPos(this.minWidthTo, 1)) : this.minWidth = this.minWidthFrom = this.minWidthTo = 0), this.view.inputState.composing < 0 ? this.compositionDeco = X.none : (t.transactions.length || this.dirty) && (this.compositionDeco = Yy(this.view, t.changes)), (j.ie || j.chrome) && !this.compositionDeco.size && t && t.state.doc.lines != t.startState.doc.lines && (this.forceSelection = !0);
    let s = this.decorations, n = this.updateDeco(), r = i1(s, n, t.changes);
    return e = Ye.extendWithRanges(e, r), this.dirty == 0 && e.length == 0 ? !1 : (this.updateInner(e, t.startState.doc.length), t.transactions.length && (this.lastUpdate = Date.now()), !0);
  }
  updateInner(t, e) {
    this.view.viewState.mustMeasureContent = !0, this.updateChildren(t, e);
    let { observer: s } = this.view;
    s.ignore(() => {
      this.dom.style.height = this.view.viewState.contentHeight + "px", this.dom.style.flexBasis = this.minWidth ? this.minWidth + "px" : "";
      let r = j.chrome || j.ios ? { node: s.selectionRange.focusNode, written: !1 } : void 0;
      this.sync(r), this.dirty = 0, r && (r.written || s.selectionRange.focusNode != r.node) && (this.forceSelection = !0), this.dom.style.height = "";
    });
    let n = [];
    if (this.view.viewport.from || this.view.viewport.to < this.view.state.doc.length)
      for (let r of this.children)
        r instanceof $i && r.widget instanceof $u && n.push(r.dom);
    s.updateGaps(n);
  }
  updateChildren(t, e) {
    let s = this.childCursor(e);
    for (let n = t.length - 1; ; n--) {
      let r = n >= 0 ? t[n] : null;
      if (!r)
        break;
      let { fromA: o, toA: l, fromB: a, toB: h } = r, { content: u, breakAtStart: c, openStart: f, openEnd: g } = uh.build(this.view.state.doc, a, h, this.decorations, this.dynamicDecorationMap), { i: _, off: A } = s.findPos(l, 1), { i: m, off: p } = s.findPos(o, -1);
      dp(this, m, p, _, A, u, c, f, g);
    }
  }
  updateSelection(t = !1, e = !1) {
    if ((t || !this.view.observer.selectionRange.focusNode) && this.view.observer.readSelectionRange(), !(e || this.mayControlSelection()) || j.ios && this.view.inputState.rapidCompositionStart)
      return;
    let s = this.forceSelection;
    this.forceSelection = !1;
    let n = this.view.state.selection.main, r = this.domAtPos(n.anchor), o = n.empty ? r : this.domAtPos(n.head);
    if (j.gecko && n.empty && Jy(r)) {
      let a = document.createTextNode("");
      this.view.observer.ignore(() => r.node.insertBefore(a, r.node.childNodes[r.offset] || null)), r = o = new $t(a, 0), s = !0;
    }
    let l = this.view.observer.selectionRange;
    (s || !l.focusNode || !Mr(r.node, r.offset, l.anchorNode, l.anchorOffset) || !Mr(o.node, o.offset, l.focusNode, l.focusOffset)) && (this.view.observer.ignore(() => {
      j.android && j.chrome && this.dom.contains(l.focusNode) && s1(l.focusNode, this.dom) && (this.dom.blur(), this.dom.focus({ preventScroll: !0 }));
      let a = Er(this.root);
      if (a)
        if (n.empty) {
          if (j.gecko) {
            let h = t1(r.node, r.offset);
            if (h && h != 3) {
              let u = Qp(r.node, r.offset, h == 1 ? 1 : -1);
              u && (r = new $t(u, h == 1 ? 0 : u.nodeValue.length));
            }
          }
          a.collapse(r.node, r.offset), n.bidiLevel != null && l.cursorBidiLevel != null && (l.cursorBidiLevel = n.bidiLevel);
        } else if (a.extend)
          a.collapse(r.node, r.offset), a.extend(o.node, o.offset);
        else {
          let h = document.createRange();
          n.anchor > n.head && ([r, o] = [o, r]), h.setEnd(o.node, o.offset), h.setStart(r.node, r.offset), a.removeAllRanges(), a.addRange(h);
        }
    }), this.view.observer.setSelectionRange(r, o)), this.impreciseAnchor = r.precise ? null : new $t(l.anchorNode, l.anchorOffset), this.impreciseHead = o.precise ? null : new $t(l.focusNode, l.focusOffset);
  }
  enforceCursorAssoc() {
    if (this.compositionDeco.size)
      return;
    let t = this.view.state.selection.main, e = Er(this.root);
    if (!e || !t.empty || !t.assoc || !e.modify)
      return;
    let s = Yt.find(this, t.head);
    if (!s)
      return;
    let n = s.posAtStart;
    if (t.head == n || t.head == n + s.length)
      return;
    let r = this.coordsAt(t.head, -1), o = this.coordsAt(t.head, 1);
    if (!r || !o || r.bottom > o.top)
      return;
    let l = this.domAtPos(t.head + t.assoc);
    e.collapse(l.node, l.offset), e.modify("move", t.assoc < 0 ? "forward" : "backward", "lineboundary");
  }
  mayControlSelection() {
    let t = this.root.activeElement;
    return t == this.dom || dr(this.dom, this.view.observer.selectionRange) && !(t && this.dom.contains(t));
  }
  nearest(t) {
    for (let e = t; e; ) {
      let s = kt.get(e);
      if (s && s.rootView == this)
        return s;
      e = e.parentNode;
    }
    return null;
  }
  posFromDOM(t, e) {
    let s = this.nearest(t);
    if (!s)
      throw new RangeError("Trying to find position for a DOM position outside of the document");
    return s.localPosFromDOM(t, e) + s.posAtStart;
  }
  domAtPos(t) {
    let { i: e, off: s } = this.childCursor().findPos(t, -1);
    for (; e < this.children.length - 1; ) {
      let n = this.children[e];
      if (s < n.length || n instanceof Yt)
        break;
      e++, s = 0;
    }
    return this.children[e].domAtPos(s);
  }
  coordsAt(t, e) {
    for (let s = this.length, n = this.children.length - 1; ; n--) {
      let r = this.children[n], o = s - r.breakAfter - r.length;
      if (t > o || t == o && r.type != _t.WidgetBefore && r.type != _t.WidgetAfter && (!n || e == 2 || this.children[n - 1].breakAfter || this.children[n - 1].type == _t.WidgetBefore && e > -2))
        return r.coordsAt(t - o, e);
      s = o;
    }
  }
  measureVisibleLineHeights(t) {
    let e = [], { from: s, to: n } = t, r = this.view.contentDOM.clientWidth, o = r > Math.max(this.view.scrollDOM.clientWidth, this.minWidth) + 1, l = -1, a = this.view.textDirection == St.LTR;
    for (let h = 0, u = 0; u < this.children.length; u++) {
      let c = this.children[u], f = h + c.length;
      if (f > n)
        break;
      if (h >= s) {
        let g = c.dom.getBoundingClientRect();
        if (e.push(g.height), o) {
          let _ = c.dom.lastChild, A = _ ? gn(_) : [];
          if (A.length) {
            let m = A[A.length - 1], p = a ? m.right - g.left : g.right - m.left;
            p > l && (l = p, this.minWidth = r, this.minWidthFrom = h, this.minWidthTo = f);
          }
        }
      }
      h = f + c.breakAfter;
    }
    return e;
  }
  textDirectionAt(t) {
    let { i: e } = this.childPos(t, 1);
    return getComputedStyle(this.children[e].dom).direction == "rtl" ? St.RTL : St.LTR;
  }
  measureTextSize() {
    for (let n of this.children)
      if (n instanceof Yt) {
        let r = n.measureTextSize();
        if (r)
          return r;
      }
    let t = document.createElement("div"), e, s;
    return t.className = "cm-line", t.style.width = "99999px", t.textContent = "abc def ghi jkl mno pqr stu", this.view.observer.ignore(() => {
      this.dom.appendChild(t);
      let n = gn(t.firstChild)[0];
      e = t.getBoundingClientRect().height, s = n ? n.width / 27 : 7, t.remove();
    }), { lineHeight: e, charWidth: s };
  }
  childCursor(t = this.length) {
    let e = this.children.length;
    return e && (t -= this.children[--e].length), new fp(this.children, t, e);
  }
  computeBlockGapDeco() {
    let t = [], e = this.view.viewState;
    for (let s = 0, n = 0; ; n++) {
      let r = n == e.viewports.length ? null : e.viewports[n], o = r ? r.from - 1 : this.length;
      if (o > s) {
        let l = e.lineBlockAt(o).bottom - e.lineBlockAt(s).top;
        t.push(X.replace({
          widget: new $u(l),
          block: !0,
          inclusive: !0,
          isBlockGap: !0
        }).range(s, o));
      }
      if (!r)
        break;
      s = r.to + 1;
    }
    return X.set(t);
  }
  updateDeco() {
    let t = this.view.state.facet(_n).map((e, s) => (this.dynamicDecorationMap[s] = typeof e == "function") ? e(this.view) : e);
    for (let e = t.length; e < t.length + 3; e++)
      this.dynamicDecorationMap[e] = !1;
    return this.decorations = [
      ...t,
      this.compositionDeco,
      this.computeBlockGapDeco(),
      this.view.viewState.lineGapDeco
    ];
  }
  scrollIntoView(t) {
    let { range: e } = t, s = this.coordsAt(e.head, e.empty ? e.assoc : e.head > e.anchor ? -1 : 1), n;
    if (!s)
      return;
    !e.empty && (n = this.coordsAt(e.anchor, e.anchor > e.head ? -1 : 1)) && (s = {
      left: Math.min(s.left, n.left),
      top: Math.min(s.top, n.top),
      right: Math.max(s.right, n.right),
      bottom: Math.max(s.bottom, n.bottom)
    });
    let r = 0, o = 0, l = 0, a = 0;
    for (let u of this.view.state.facet(Rp).map((c) => c(this.view)))
      if (u) {
        let { left: c, right: f, top: g, bottom: _ } = u;
        c != null && (r = Math.max(r, c)), f != null && (o = Math.max(o, f)), g != null && (l = Math.max(l, g)), _ != null && (a = Math.max(a, _));
      }
    let h = {
      left: s.left - r,
      top: s.top - l,
      right: s.right + o,
      bottom: s.bottom + a
    };
    Ly(this.view.scrollDOM, h, e.head < e.anchor ? -1 : 1, t.x, t.y, t.xMargin, t.yMargin, this.view.textDirection == St.LTR);
  }
}
function Jy(i) {
  return i.node.nodeType == 1 && i.node.firstChild && (i.offset == 0 || i.node.childNodes[i.offset - 1].contentEditable == "false") && (i.offset == i.node.childNodes.length || i.node.childNodes[i.offset].contentEditable == "false");
}
class $u extends ni {
  constructor(t) {
    super(), this.height = t;
  }
  toDOM() {
    let t = document.createElement("div");
    return this.updateDOM(t), t;
  }
  eq(t) {
    return t.height == this.height;
  }
  updateDOM(t) {
    return t.style.height = this.height + "px", !0;
  }
  get estimatedHeight() {
    return this.height;
  }
}
function Ip(i) {
  let t = i.observer.selectionRange, e = t.focusNode && Qp(t.focusNode, t.focusOffset, 0);
  if (!e)
    return null;
  let s = i.docView.nearest(e);
  if (!s)
    return null;
  if (s instanceof Yt) {
    let n = e;
    for (; n.parentNode != s.dom; )
      n = n.parentNode;
    let r = n.previousSibling;
    for (; r && !kt.get(r); )
      r = r.previousSibling;
    let o = r ? kt.get(r).posAtEnd : s.posAtStart;
    return { from: o, to: o, node: n, text: e };
  } else {
    for (; ; ) {
      let { parent: r } = s;
      if (!r)
        return null;
      if (r instanceof Yt)
        break;
      s = r;
    }
    let n = s.posAtStart;
    return { from: n, to: n + s.length, node: s.dom, text: e };
  }
}
function Yy(i, t) {
  let e = Ip(i);
  if (!e)
    return X.none;
  let { from: s, to: n, node: r, text: o } = e, l = t.mapPos(s, 1), a = Math.max(l, t.mapPos(n, -1)), { state: h } = i, u = r.nodeType == 3 ? r.nodeValue : new Lp([], h).readRange(r.firstChild, null).text;
  if (a - l < u.length)
    if (h.doc.sliceString(l, Math.min(h.doc.length, l + u.length), mi) == u)
      a = l + u.length;
    else if (h.doc.sliceString(Math.max(0, a - u.length), a, mi) == u)
      l = a - u.length;
    else
      return X.none;
  else if (h.doc.sliceString(l, a, mi) != u)
    return X.none;
  let c = kt.get(r);
  return c instanceof _p ? c = c.widget.topView : c && (c.parent = null), X.set(X.replace({ widget: new Zy(r, o, c), inclusive: !0 }).range(l, a));
}
class Zy extends ni {
  constructor(t, e, s) {
    super(), this.top = t, this.text = e, this.topView = s;
  }
  eq(t) {
    return this.top == t.top && this.text == t.text;
  }
  toDOM() {
    return this.top;
  }
  ignoreEvent() {
    return !1;
  }
  get customView() {
    return _p;
  }
}
function Qp(i, t, e) {
  for (; ; ) {
    if (i.nodeType == 3)
      return i;
    if (i.nodeType == 1 && t > 0 && e <= 0)
      i = i.childNodes[t - 1], t = mn(i);
    else if (i.nodeType == 1 && t < i.childNodes.length && e >= 0)
      i = i.childNodes[t], t = 0;
    else
      return null;
  }
}
function t1(i, t) {
  return i.nodeType != 1 ? 0 : (t && i.childNodes[t - 1].contentEditable == "false" ? 1 : 0) | (t < i.childNodes.length && i.childNodes[t].contentEditable == "false" ? 2 : 0);
}
class e1 {
  constructor() {
    this.changes = [];
  }
  compareRange(t, e) {
    ga(t, e, this.changes);
  }
  comparePoint(t, e) {
    ga(t, e, this.changes);
  }
}
function i1(i, t, e) {
  let s = new e1();
  return ft.compare(i, t, e, s), s.changes;
}
function s1(i, t) {
  for (let e = i; e && e != t; e = e.assignedSlot || e.parentNode)
    if (e.nodeType == 1 && e.contentEditable == "false")
      return !0;
  return !1;
}
function n1(i, t, e = 1) {
  let s = i.charCategorizer(t), n = i.doc.lineAt(t), r = t - n.from;
  if (n.length == 0)
    return R.cursor(t);
  r == 0 ? e = 1 : r == n.length && (e = -1);
  let o = r, l = r;
  e < 0 ? o = de(n.text, r, !1) : l = de(n.text, r);
  let a = s(n.text.slice(o, l));
  for (; o > 0; ) {
    let h = de(n.text, o, !1);
    if (s(n.text.slice(h, o)) != a)
      break;
    o = h;
  }
  for (; l < n.length; ) {
    let h = de(n.text, l);
    if (s(n.text.slice(l, h)) != a)
      break;
    l = h;
  }
  return R.range(o + n.from, l + n.from);
}
function r1(i, t) {
  return t.left > i ? t.left - i : Math.max(0, i - t.right);
}
function o1(i, t) {
  return t.top > i ? t.top - i : Math.max(0, i - t.bottom);
}
function Ho(i, t) {
  return i.top < t.bottom - 1 && i.bottom > t.top + 1;
}
function Fu(i, t) {
  return t < i.top ? { top: t, left: i.left, right: i.right, bottom: i.bottom } : i;
}
function Vu(i, t) {
  return t > i.bottom ? { top: i.top, left: i.left, right: i.right, bottom: t } : i;
}
function ya(i, t, e) {
  let s, n, r, o, l, a, h, u;
  for (let g = i.firstChild; g; g = g.nextSibling) {
    let _ = gn(g);
    for (let A = 0; A < _.length; A++) {
      let m = _[A];
      n && Ho(n, m) && (m = Fu(Vu(m, n.bottom), n.top));
      let p = r1(t, m), y = o1(e, m);
      if (p == 0 && y == 0)
        return g.nodeType == 3 ? Uu(g, t, e) : ya(g, t, e);
      (!s || o > y || o == y && r > p) && (s = g, n = m, r = p, o = y), p == 0 ? e > m.bottom && (!h || h.bottom < m.bottom) ? (l = g, h = m) : e < m.top && (!u || u.top > m.top) && (a = g, u = m) : h && Ho(h, m) ? h = Vu(h, m.bottom) : u && Ho(u, m) && (u = Fu(u, m.top));
    }
  }
  if (h && h.bottom >= e ? (s = l, n = h) : u && u.top <= e && (s = a, n = u), !s)
    return { node: i, offset: 0 };
  let c = Math.max(n.left, Math.min(n.right, t));
  if (s.nodeType == 3)
    return Uu(s, c, e);
  if (!r && s.contentEditable == "true")
    return ya(s, c, e);
  let f = Array.prototype.indexOf.call(i.childNodes, s) + (t >= (n.left + n.right) / 2 ? 1 : 0);
  return { node: i, offset: f };
}
function Uu(i, t, e) {
  let s = i.nodeValue.length, n = -1, r = 1e9, o = 0;
  for (let l = 0; l < s; l++) {
    let a = bs(i, l, l + 1).getClientRects();
    for (let h = 0; h < a.length; h++) {
      let u = a[h];
      if (u.top == u.bottom)
        continue;
      o || (o = t - u.left);
      let c = (u.top > e ? u.top - e : e - u.bottom) - 1;
      if (u.left - 1 <= t && u.right + 1 >= t && c < r) {
        let f = t >= (u.left + u.right) / 2, g = f;
        if ((j.chrome || j.gecko) && bs(i, l).getBoundingClientRect().left == u.right && (g = !f), c <= 0)
          return { node: i, offset: l + (g ? 1 : 0) };
        n = l + (g ? 1 : 0), r = c;
      }
    }
  }
  return { node: i, offset: n > -1 ? n : o > 0 ? i.nodeValue.length : 0 };
}
function zp(i, { x: t, y: e }, s, n = -1) {
  var r;
  let o = i.contentDOM.getBoundingClientRect(), l = o.top + i.viewState.paddingTop, a, { docHeight: h } = i.viewState, u = e - l;
  if (u < 0)
    return 0;
  if (u > h)
    return i.state.doc.length;
  for (let p = i.defaultLineHeight / 2, y = !1; a = i.elementAtHeight(u), a.type != _t.Text; )
    for (; u = n > 0 ? a.bottom + p : a.top - p, !(u >= 0 && u <= h); ) {
      if (y)
        return s ? null : 0;
      y = !0, n = -n;
    }
  e = l + u;
  let c = a.from;
  if (c < i.viewport.from)
    return i.viewport.from == 0 ? 0 : s ? null : ju(i, o, a, t, e);
  if (c > i.viewport.to)
    return i.viewport.to == i.state.doc.length ? i.state.doc.length : s ? null : ju(i, o, a, t, e);
  let f = i.dom.ownerDocument, g = i.root.elementFromPoint ? i.root : f, _ = g.elementFromPoint(t, e);
  _ && !i.contentDOM.contains(_) && (_ = null), _ || (t = Math.max(o.left + 1, Math.min(o.right - 1, t)), _ = g.elementFromPoint(t, e), _ && !i.contentDOM.contains(_) && (_ = null));
  let A, m = -1;
  if (_ && ((r = i.docView.nearest(_)) === null || r === void 0 ? void 0 : r.isEditable) != !1) {
    if (f.caretPositionFromPoint) {
      let p = f.caretPositionFromPoint(t, e);
      p && ({ offsetNode: A, offset: m } = p);
    } else if (f.caretRangeFromPoint) {
      let p = f.caretRangeFromPoint(t, e);
      p && ({ startContainer: A, startOffset: m } = p, (j.safari && l1(A, m, t) || j.chrome && a1(A, m, t)) && (A = void 0));
    }
  }
  if (!A || !i.docView.dom.contains(A)) {
    let p = Yt.find(i.docView, c);
    if (!p)
      return u > a.top + a.height / 2 ? a.to : a.from;
    ({ node: A, offset: m } = ya(p.dom, t, e));
  }
  return i.docView.posFromDOM(A, m);
}
function ju(i, t, e, s, n) {
  let r = Math.round((s - t.left) * i.defaultCharacterWidth);
  if (i.lineWrapping && e.height > i.defaultLineHeight * 1.5) {
    let l = Math.floor((n - e.top) / i.defaultLineHeight);
    r += l * i.viewState.heightOracle.lineLength;
  }
  let o = i.state.sliceDoc(e.from, e.to);
  return e.from + ra(o, r, i.state.tabSize);
}
function l1(i, t, e) {
  let s;
  if (i.nodeType != 3 || t != (s = i.nodeValue.length))
    return !1;
  for (let n = i.nextSibling; n; n = n.nextSibling)
    if (n.nodeType != 1 || n.nodeName != "BR")
      return !1;
  return bs(i, s - 1, s).getBoundingClientRect().left > e;
}
function a1(i, t, e) {
  if (t != 0)
    return !1;
  for (let n = i; ; ) {
    let r = n.parentNode;
    if (!r || r.nodeType != 1 || r.firstChild != n)
      return !1;
    if (r.classList.contains("cm-line"))
      break;
    n = r;
  }
  let s = i.nodeType == 1 ? i.getBoundingClientRect() : bs(i, 0, Math.max(i.nodeValue.length, 1)).getBoundingClientRect();
  return e - s.left > 5;
}
function h1(i, t, e, s) {
  let n = i.state.doc.lineAt(t.head), r = !s || !i.lineWrapping ? null : i.coordsAtPos(t.assoc < 0 && t.head > n.from ? t.head - 1 : t.head);
  if (r) {
    let a = i.dom.getBoundingClientRect(), h = i.textDirectionAt(n.from), u = i.posAtCoords({
      x: e == (h == St.LTR) ? a.right - 1 : a.left + 1,
      y: (r.top + r.bottom) / 2
    });
    if (u != null)
      return R.cursor(u, e ? -1 : 1);
  }
  let o = Yt.find(i.docView, t.head), l = o ? e ? o.posAtEnd : o.posAtStart : e ? n.to : n.from;
  return R.cursor(l, e ? -1 : 1);
}
function Hu(i, t, e, s) {
  let n = i.state.doc.lineAt(t.head), r = i.bidiSpans(n), o = i.textDirectionAt(n.from);
  for (let l = t, a = null; ; ) {
    let h = Gy(n, r, o, l, e), u = Np;
    if (!h) {
      if (n.number == (e ? i.state.doc.lines : 1))
        return l;
      u = `
`, n = i.state.doc.line(n.number + (e ? 1 : -1)), r = i.bidiSpans(n), h = R.cursor(e ? n.from : n.to);
    }
    if (a) {
      if (!a(u))
        return l;
    } else {
      if (!s)
        return h;
      a = s(u);
    }
    l = h;
  }
}
function u1(i, t, e) {
  let s = i.state.charCategorizer(t), n = s(e);
  return (r) => {
    let o = s(r);
    return n == Jt.Space && (n = o), n == o;
  };
}
function c1(i, t, e, s) {
  let n = t.head, r = e ? 1 : -1;
  if (n == (e ? i.state.doc.length : 0))
    return R.cursor(n, t.assoc);
  let o = t.goalColumn, l, a = i.contentDOM.getBoundingClientRect(), h = i.coordsAtPos(n), u = i.documentTop;
  if (h)
    o == null && (o = h.left - a.left), l = r < 0 ? h.top : h.bottom;
  else {
    let g = i.viewState.lineBlockAt(n);
    o == null && (o = Math.min(a.right - a.left, i.defaultCharacterWidth * (n - g.from))), l = (r < 0 ? g.top : g.bottom) + u;
  }
  let c = a.left + o, f = s != null ? s : i.defaultLineHeight >> 1;
  for (let g = 0; ; g += 10) {
    let _ = l + (f + g) * r, A = zp(i, { x: c, y: _ }, !1, r);
    if (_ < a.top || _ > a.bottom || (r < 0 ? A < n : A > n))
      return R.cursor(A, t.assoc, void 0, o);
  }
}
function qo(i, t, e) {
  let s = i.state.facet(Mp).map((n) => n(i));
  for (; ; ) {
    let n = !1;
    for (let r of s)
      r.between(e.from - 1, e.from + 1, (o, l, a) => {
        e.from > o && e.from < l && (e = t.from > e.from ? R.cursor(o, 1) : R.cursor(l, -1), n = !0);
      });
    if (!n)
      return e;
  }
}
class f1 {
  constructor(t) {
    this.lastKeyCode = 0, this.lastKeyTime = 0, this.lastTouchTime = 0, this.lastFocusTime = 0, this.lastScrollTop = 0, this.lastScrollLeft = 0, this.chromeScrollHack = -1, this.pendingIOSKey = void 0, this.lastSelectionOrigin = null, this.lastSelectionTime = 0, this.lastEscPress = 0, this.lastContextMenu = 0, this.scrollHandlers = [], this.registeredEvents = [], this.customHandlers = [], this.composing = -1, this.compositionFirstChange = null, this.compositionEndedAt = 0, this.rapidCompositionStart = !1, this.mouseSelection = null;
    for (let e in Bt) {
      let s = Bt[e];
      t.contentDOM.addEventListener(e, (n) => {
        !qu(t, n) || this.ignoreDuringComposition(n) || e == "keydown" && this.keydown(t, n) || (this.mustFlushObserver(n) && t.observer.forceFlush(), this.runCustomHandlers(e, t, n) ? n.preventDefault() : s(t, n));
      }, wa[e]), this.registeredEvents.push(e);
    }
    j.chrome && j.chrome_version == 102 && t.scrollDOM.addEventListener("wheel", () => {
      this.chromeScrollHack < 0 ? t.contentDOM.style.pointerEvents = "none" : window.clearTimeout(this.chromeScrollHack), this.chromeScrollHack = setTimeout(() => {
        this.chromeScrollHack = -1, t.contentDOM.style.pointerEvents = "";
      }, 100);
    }, { passive: !0 }), this.notifiedFocused = t.hasFocus, j.safari && t.contentDOM.addEventListener("input", () => null);
  }
  setSelectionOrigin(t) {
    this.lastSelectionOrigin = t, this.lastSelectionTime = Date.now();
  }
  ensureHandlers(t, e) {
    var s;
    let n;
    this.customHandlers = [];
    for (let r of e)
      if (n = (s = r.update(t).spec) === null || s === void 0 ? void 0 : s.domEventHandlers) {
        this.customHandlers.push({ plugin: r.value, handlers: n });
        for (let o in n)
          this.registeredEvents.indexOf(o) < 0 && o != "scroll" && (this.registeredEvents.push(o), t.contentDOM.addEventListener(o, (l) => {
            !qu(t, l) || this.runCustomHandlers(o, t, l) && l.preventDefault();
          }));
      }
  }
  runCustomHandlers(t, e, s) {
    for (let n of this.customHandlers) {
      let r = n.handlers[t];
      if (r)
        try {
          if (r.call(n.plugin, s, e) || s.defaultPrevented)
            return !0;
        } catch (o) {
          ge(e.state, o);
        }
    }
    return !1;
  }
  runScrollHandlers(t, e) {
    this.lastScrollTop = t.scrollDOM.scrollTop, this.lastScrollLeft = t.scrollDOM.scrollLeft;
    for (let s of this.customHandlers) {
      let n = s.handlers.scroll;
      if (n)
        try {
          n.call(s.plugin, e, t);
        } catch (r) {
          ge(t.state, r);
        }
    }
  }
  keydown(t, e) {
    if (this.lastKeyCode = e.keyCode, this.lastKeyTime = Date.now(), e.keyCode == 9 && Date.now() < this.lastEscPress + 2e3)
      return !0;
    if (j.android && j.chrome && !e.synthetic && (e.keyCode == 13 || e.keyCode == 8))
      return t.observer.delayAndroidKey(e.key, e.keyCode), !0;
    let s;
    return j.ios && (s = Wp.find((n) => n.keyCode == e.keyCode)) && !(e.ctrlKey || e.altKey || e.metaKey) && !e.synthetic ? (this.pendingIOSKey = s, setTimeout(() => this.flushIOSKey(t), 250), !0) : !1;
  }
  flushIOSKey(t) {
    let e = this.pendingIOSKey;
    return e ? (this.pendingIOSKey = void 0, en(t.contentDOM, e.key, e.keyCode)) : !1;
  }
  ignoreDuringComposition(t) {
    return /^key/.test(t.type) ? this.composing > 0 ? !0 : j.safari && !j.ios && Date.now() - this.compositionEndedAt < 100 ? (this.compositionEndedAt = 0, !0) : !1 : !1;
  }
  mustFlushObserver(t) {
    return t.type == "keydown" && t.keyCode != 229 || t.type == "compositionend" && !j.ios;
  }
  startMouseSelection(t) {
    this.mouseSelection && this.mouseSelection.destroy(), this.mouseSelection = t;
  }
  update(t) {
    this.mouseSelection && this.mouseSelection.update(t), t.transactions.length && (this.lastKeyCode = this.lastSelectionTime = 0);
  }
  destroy() {
    this.mouseSelection && this.mouseSelection.destroy();
  }
}
const Wp = [
  { key: "Backspace", keyCode: 8, inputType: "deleteContentBackward" },
  { key: "Enter", keyCode: 13, inputType: "insertParagraph" },
  { key: "Delete", keyCode: 46, inputType: "deleteContentForward" }
], $p = [16, 17, 18, 20, 91, 92, 224, 225];
class d1 {
  constructor(t, e, s, n) {
    this.view = t, this.style = s, this.mustSelect = n, this.lastEvent = e;
    let r = t.contentDOM.ownerDocument;
    r.addEventListener("mousemove", this.move = this.move.bind(this)), r.addEventListener("mouseup", this.up = this.up.bind(this)), this.extend = e.shiftKey, this.multiple = t.state.facet(at.allowMultipleSelections) && p1(t, e), this.dragMove = g1(t, e), this.dragging = m1(t, e) && ch(e) == 1 ? null : !1, this.dragging === !1 && (e.preventDefault(), this.select(e));
  }
  move(t) {
    if (t.buttons == 0)
      return this.destroy();
    this.dragging === !1 && this.select(this.lastEvent = t);
  }
  up(t) {
    this.dragging == null && this.select(this.lastEvent), this.dragging || t.preventDefault(), this.destroy();
  }
  destroy() {
    let t = this.view.contentDOM.ownerDocument;
    t.removeEventListener("mousemove", this.move), t.removeEventListener("mouseup", this.up), this.view.inputState.mouseSelection = null;
  }
  select(t) {
    let e = this.style.get(t, this.extend, this.multiple);
    (this.mustSelect || !e.eq(this.view.state.selection) || e.main.assoc != this.view.state.selection.main.assoc) && this.view.dispatch({
      selection: e,
      userEvent: "select.pointer",
      scrollIntoView: !0
    }), this.mustSelect = !1;
  }
  update(t) {
    t.docChanged && this.dragging && (this.dragging = this.dragging.map(t.changes)), this.style.update(t) && setTimeout(() => this.select(this.lastEvent), 20);
  }
}
function p1(i, t) {
  let e = i.state.facet(kp);
  return e.length ? e[0](t) : j.mac ? t.metaKey : t.ctrlKey;
}
function g1(i, t) {
  let e = i.state.facet(Op);
  return e.length ? e[0](t) : j.mac ? !t.altKey : !t.ctrlKey;
}
function m1(i, t) {
  let { main: e } = i.state.selection;
  if (e.empty)
    return !1;
  let s = Er(i.root);
  if (!s || s.rangeCount == 0)
    return !0;
  let n = s.getRangeAt(0).getClientRects();
  for (let r = 0; r < n.length; r++) {
    let o = n[r];
    if (o.left <= t.clientX && o.right >= t.clientX && o.top <= t.clientY && o.bottom >= t.clientY)
      return !0;
  }
  return !1;
}
function qu(i, t) {
  if (!t.bubbles)
    return !0;
  if (t.defaultPrevented)
    return !1;
  for (let e = t.target, s; e != i.contentDOM; e = e.parentNode)
    if (!e || e.nodeType == 11 || (s = kt.get(e)) && s.ignoreEvent(t))
      return !1;
  return !0;
}
const Bt = /* @__PURE__ */ Object.create(null), wa = /* @__PURE__ */ Object.create(null), Fp = j.ie && j.ie_version < 15 || j.ios && j.webkit_version < 604;
function _1(i) {
  let t = i.dom.parentNode;
  if (!t)
    return;
  let e = t.appendChild(document.createElement("textarea"));
  e.style.cssText = "position: fixed; left: -10000px; top: 10px", e.focus(), setTimeout(() => {
    i.focus(), e.remove(), Vp(i, e.value);
  }, 50);
}
function Vp(i, t) {
  let { state: e } = i, s, n = 1, r = e.toText(t), o = r.lines == e.selection.ranges.length;
  if (va != null && e.selection.ranges.every((a) => a.empty) && va == r.toString()) {
    let a = -1;
    s = e.changeByRange((h) => {
      let u = e.doc.lineAt(h.from);
      if (u.from == a)
        return { range: h };
      a = u.from;
      let c = e.toText((o ? r.line(n++).text : t) + e.lineBreak);
      return {
        changes: { from: u.from, insert: c },
        range: R.cursor(h.from + c.length)
      };
    });
  } else
    o ? s = e.changeByRange((a) => {
      let h = r.line(n++);
      return {
        changes: { from: a.from, to: a.to, insert: h.text },
        range: R.cursor(a.from + h.length)
      };
    }) : s = e.replaceSelection(r);
  i.dispatch(s, {
    userEvent: "input.paste",
    scrollIntoView: !0
  });
}
Bt.keydown = (i, t) => {
  i.inputState.setSelectionOrigin("select"), t.keyCode == 27 ? i.inputState.lastEscPress = Date.now() : $p.indexOf(t.keyCode) < 0 && (i.inputState.lastEscPress = 0);
};
Bt.touchstart = (i, t) => {
  i.inputState.lastTouchTime = Date.now(), i.inputState.setSelectionOrigin("select.pointer");
};
Bt.touchmove = (i) => {
  i.inputState.setSelectionOrigin("select.pointer");
};
wa.touchstart = wa.touchmove = { passive: !0 };
Bt.mousedown = (i, t) => {
  if (i.observer.flush(), i.inputState.lastTouchTime > Date.now() - 2e3 && ch(t) == 1)
    return;
  let e = null;
  for (let s of i.state.facet(Sp))
    if (e = s(i, t), e)
      break;
  if (!e && t.button == 0 && (e = w1(i, t)), e) {
    let s = i.root.activeElement != i.contentDOM;
    s && i.observer.ignore(() => up(i.contentDOM)), i.inputState.startMouseSelection(new d1(i, t, e, s));
  }
};
function Ku(i, t, e, s) {
  if (s == 1)
    return R.cursor(t, e);
  if (s == 2)
    return n1(i.state, t, e);
  {
    let n = Yt.find(i.docView, t), r = i.state.doc.lineAt(n ? n.posAtEnd : t), o = n ? n.posAtStart : r.from, l = n ? n.posAtEnd : r.to;
    return l < i.state.doc.length && l == r.to && l++, R.range(o, l);
  }
}
let Up = (i, t) => i >= t.top && i <= t.bottom, Xu = (i, t, e) => Up(t, e) && i >= e.left && i <= e.right;
function b1(i, t, e, s) {
  let n = Yt.find(i.docView, t);
  if (!n)
    return 1;
  let r = t - n.posAtStart;
  if (r == 0)
    return 1;
  if (r == n.length)
    return -1;
  let o = n.coordsAt(r, -1);
  if (o && Xu(e, s, o))
    return -1;
  let l = n.coordsAt(r, 1);
  return l && Xu(e, s, l) ? 1 : o && Up(s, o) ? -1 : 1;
}
function Gu(i, t) {
  let e = i.posAtCoords({ x: t.clientX, y: t.clientY }, !1);
  return { pos: e, bias: b1(i, e, t.clientX, t.clientY) };
}
const y1 = j.ie && j.ie_version <= 11;
let Ju = null, Yu = 0, Zu = 0;
function ch(i) {
  if (!y1)
    return i.detail;
  let t = Ju, e = Zu;
  return Ju = i, Zu = Date.now(), Yu = !t || e > Date.now() - 400 && Math.abs(t.clientX - i.clientX) < 2 && Math.abs(t.clientY - i.clientY) < 2 ? (Yu + 1) % 3 : 1;
}
function w1(i, t) {
  let e = Gu(i, t), s = ch(t), n = i.state.selection, r = e, o = t;
  return {
    update(l) {
      l.docChanged && (e && (e.pos = l.changes.mapPos(e.pos)), n = n.map(l.changes), o = null);
    },
    get(l, a, h) {
      let u;
      if (o && l.clientX == o.clientX && l.clientY == o.clientY ? u = r : (u = r = Gu(i, l), o = l), !u || !e)
        return n;
      let c = Ku(i, u.pos, u.bias, s);
      if (e.pos != u.pos && !a) {
        let f = Ku(i, e.pos, e.bias, s), g = Math.min(f.from, c.from), _ = Math.max(f.to, c.to);
        c = g < c.from ? R.range(g, _) : R.range(_, g);
      }
      return a ? n.replaceRange(n.main.extend(c.from, c.to)) : h && n.ranges.length > 1 && n.ranges.some((f) => f.eq(c)) ? v1(n, c) : h ? n.addRange(c) : R.create([c]);
    }
  };
}
function v1(i, t) {
  for (let e = 0; ; e++)
    if (i.ranges[e].eq(t))
      return R.create(i.ranges.slice(0, e).concat(i.ranges.slice(e + 1)), i.mainIndex == e ? 0 : i.mainIndex - (i.mainIndex > e ? 1 : 0));
}
Bt.dragstart = (i, t) => {
  let { selection: { main: e } } = i.state, { mouseSelection: s } = i.inputState;
  s && (s.dragging = e), t.dataTransfer && (t.dataTransfer.setData("Text", i.state.sliceDoc(e.from, e.to)), t.dataTransfer.effectAllowed = "copyMove");
};
function tc(i, t, e, s) {
  if (!e)
    return;
  let n = i.posAtCoords({ x: t.clientX, y: t.clientY }, !1);
  t.preventDefault();
  let { mouseSelection: r } = i.inputState, o = s && r && r.dragging && r.dragMove ? { from: r.dragging.from, to: r.dragging.to } : null, l = { from: n, insert: e }, a = i.state.changes(o ? [o, l] : l);
  i.focus(), i.dispatch({
    changes: a,
    selection: { anchor: a.mapPos(n, -1), head: a.mapPos(n, 1) },
    userEvent: o ? "move.drop" : "input.drop"
  });
}
Bt.drop = (i, t) => {
  if (!t.dataTransfer)
    return;
  if (i.state.readOnly)
    return t.preventDefault();
  let e = t.dataTransfer.files;
  if (e && e.length) {
    t.preventDefault();
    let s = Array(e.length), n = 0, r = () => {
      ++n == e.length && tc(i, t, s.filter((o) => o != null).join(i.state.lineBreak), !1);
    };
    for (let o = 0; o < e.length; o++) {
      let l = new FileReader();
      l.onerror = r, l.onload = () => {
        /[\x00-\x08\x0e-\x1f]{2}/.test(l.result) || (s[o] = l.result), r();
      }, l.readAsText(e[o]);
    }
  } else
    tc(i, t, t.dataTransfer.getData("Text"), !0);
};
Bt.paste = (i, t) => {
  if (i.state.readOnly)
    return t.preventDefault();
  i.observer.flush();
  let e = Fp ? null : t.clipboardData;
  e ? (Vp(i, e.getData("text/plain")), t.preventDefault()) : _1(i);
};
function x1(i, t) {
  let e = i.dom.parentNode;
  if (!e)
    return;
  let s = e.appendChild(document.createElement("textarea"));
  s.style.cssText = "position: fixed; left: -10000px; top: 10px", s.value = t, s.focus(), s.selectionEnd = t.length, s.selectionStart = 0, setTimeout(() => {
    s.remove(), i.focus();
  }, 50);
}
function k1(i) {
  let t = [], e = [], s = !1;
  for (let n of i.selection.ranges)
    n.empty || (t.push(i.sliceDoc(n.from, n.to)), e.push(n));
  if (!t.length) {
    let n = -1;
    for (let { from: r } of i.selection.ranges) {
      let o = i.doc.lineAt(r);
      o.number > n && (t.push(o.text), e.push({ from: o.from, to: Math.min(i.doc.length, o.to + 1) })), n = o.number;
    }
    s = !0;
  }
  return { text: t.join(i.lineBreak), ranges: e, linewise: s };
}
let va = null;
Bt.copy = Bt.cut = (i, t) => {
  let { text: e, ranges: s, linewise: n } = k1(i.state);
  if (!e && !n)
    return;
  va = n ? e : null;
  let r = Fp ? null : t.clipboardData;
  r ? (t.preventDefault(), r.clearData(), r.setData("text/plain", e)) : x1(i, e), t.type == "cut" && !i.state.readOnly && i.dispatch({
    changes: s,
    scrollIntoView: !0,
    userEvent: "delete.cut"
  });
};
function jp(i) {
  setTimeout(() => {
    i.hasFocus != i.inputState.notifiedFocused && i.update([]);
  }, 10);
}
Bt.focus = (i) => {
  i.inputState.lastFocusTime = Date.now(), !i.scrollDOM.scrollTop && (i.inputState.lastScrollTop || i.inputState.lastScrollLeft) && (i.scrollDOM.scrollTop = i.inputState.lastScrollTop, i.scrollDOM.scrollLeft = i.inputState.lastScrollLeft), jp(i);
};
Bt.blur = (i) => {
  i.observer.clearSelectionRange(), jp(i);
};
function Hp(i, t) {
  if (i.docView.compositionDeco.size) {
    i.inputState.rapidCompositionStart = t;
    try {
      i.update([]);
    } finally {
      i.inputState.rapidCompositionStart = !1;
    }
  }
}
Bt.compositionstart = Bt.compositionupdate = (i) => {
  i.inputState.compositionFirstChange == null && (i.inputState.compositionFirstChange = !0), i.inputState.composing < 0 && (i.inputState.composing = 0, i.docView.compositionDeco.size && (i.observer.flush(), Hp(i, !0)));
};
Bt.compositionend = (i) => {
  i.inputState.composing = -1, i.inputState.compositionEndedAt = Date.now(), i.inputState.compositionFirstChange = null, setTimeout(() => {
    i.inputState.composing < 0 && Hp(i, !1);
  }, 50);
};
Bt.contextmenu = (i) => {
  i.inputState.lastContextMenu = Date.now();
};
Bt.beforeinput = (i, t) => {
  var e;
  let s;
  if (j.chrome && j.android && (s = Wp.find((n) => n.inputType == t.inputType)) && (i.observer.delayAndroidKey(s.key, s.keyCode), s.key == "Backspace" || s.key == "Delete")) {
    let n = ((e = window.visualViewport) === null || e === void 0 ? void 0 : e.height) || 0;
    setTimeout(() => {
      var r;
      (((r = window.visualViewport) === null || r === void 0 ? void 0 : r.height) || 0) > n + 10 && i.hasFocus && (i.contentDOM.blur(), i.focus());
    }, 100);
  }
};
const ec = ["pre-wrap", "normal", "pre-line", "break-spaces"];
class O1 {
  constructor() {
    this.doc = ct.empty, this.lineWrapping = !1, this.heightSamples = {}, this.lineHeight = 14, this.charWidth = 7, this.lineLength = 30, this.heightChanged = !1;
  }
  heightForGap(t, e) {
    let s = this.doc.lineAt(e).number - this.doc.lineAt(t).number + 1;
    return this.lineWrapping && (s += Math.ceil((e - t - s * this.lineLength * 0.5) / this.lineLength)), this.lineHeight * s;
  }
  heightForLine(t) {
    return this.lineWrapping ? (1 + Math.max(0, Math.ceil((t - this.lineLength) / (this.lineLength - 5)))) * this.lineHeight : this.lineHeight;
  }
  setDoc(t) {
    return this.doc = t, this;
  }
  mustRefreshForWrapping(t) {
    return ec.indexOf(t) > -1 != this.lineWrapping;
  }
  mustRefreshForHeights(t) {
    let e = !1;
    for (let s = 0; s < t.length; s++) {
      let n = t[s];
      n < 0 ? s++ : this.heightSamples[Math.floor(n * 10)] || (e = !0, this.heightSamples[Math.floor(n * 10)] = !0);
    }
    return e;
  }
  refresh(t, e, s, n, r) {
    let o = ec.indexOf(t) > -1, l = Math.round(e) != Math.round(this.lineHeight) || this.lineWrapping != o;
    if (this.lineWrapping = o, this.lineHeight = e, this.charWidth = s, this.lineLength = n, l) {
      this.heightSamples = {};
      for (let a = 0; a < r.length; a++) {
        let h = r[a];
        h < 0 ? a++ : this.heightSamples[Math.floor(h * 10)] = !0;
      }
    }
    return l;
  }
}
class S1 {
  constructor(t, e) {
    this.from = t, this.heights = e, this.index = 0;
  }
  get more() {
    return this.index < this.heights.length;
  }
}
class _i {
  constructor(t, e, s, n, r) {
    this.from = t, this.length = e, this.top = s, this.height = n, this.type = r;
  }
  get to() {
    return this.from + this.length;
  }
  get bottom() {
    return this.top + this.height;
  }
  join(t) {
    let e = (Array.isArray(this.type) ? this.type : [this]).concat(Array.isArray(t.type) ? t.type : [t]);
    return new _i(this.from, this.length + t.length, this.top, this.height + t.height, e);
  }
}
var wt = /* @__PURE__ */ function(i) {
  return i[i.ByPos = 0] = "ByPos", i[i.ByHeight = 1] = "ByHeight", i[i.ByPosNoHeight = 2] = "ByPosNoHeight", i;
}(wt || (wt = {}));
const pr = 1e-3;
class se {
  constructor(t, e, s = 2) {
    this.length = t, this.height = e, this.flags = s;
  }
  get outdated() {
    return (this.flags & 2) > 0;
  }
  set outdated(t) {
    this.flags = (t ? 2 : 0) | this.flags & -3;
  }
  setHeight(t, e) {
    this.height != e && (Math.abs(this.height - e) > pr && (t.heightChanged = !0), this.height = e);
  }
  replace(t, e, s) {
    return se.of(s);
  }
  decomposeLeft(t, e) {
    e.push(this);
  }
  decomposeRight(t, e) {
    e.push(this);
  }
  applyChanges(t, e, s, n) {
    let r = this;
    for (let o = n.length - 1; o >= 0; o--) {
      let { fromA: l, toA: a, fromB: h, toB: u } = n[o], c = r.lineAt(l, wt.ByPosNoHeight, e, 0, 0), f = c.to >= a ? c : r.lineAt(a, wt.ByPosNoHeight, e, 0, 0);
      for (u += f.to - a, a = f.to; o > 0 && c.from <= n[o - 1].toA; )
        l = n[o - 1].fromA, h = n[o - 1].fromB, o--, l < c.from && (c = r.lineAt(l, wt.ByPosNoHeight, e, 0, 0));
      h += c.from - l, l = c.from;
      let g = fh.build(s, t, h, u);
      r = r.replace(l, a, g);
    }
    return r.updateHeight(s, 0);
  }
  static empty() {
    return new ae(0, 0);
  }
  static of(t) {
    if (t.length == 1)
      return t[0];
    let e = 0, s = t.length, n = 0, r = 0;
    for (; ; )
      if (e == s)
        if (n > r * 2) {
          let l = t[e - 1];
          l.break ? t.splice(--e, 1, l.left, null, l.right) : t.splice(--e, 1, l.left, l.right), s += 1 + l.break, n -= l.size;
        } else if (r > n * 2) {
          let l = t[s];
          l.break ? t.splice(s, 1, l.left, null, l.right) : t.splice(s, 1, l.left, l.right), s += 2 + l.break, r -= l.size;
        } else
          break;
      else if (n < r) {
        let l = t[e++];
        l && (n += l.size);
      } else {
        let l = t[--s];
        l && (r += l.size);
      }
    let o = 0;
    return t[e - 1] == null ? (o = 1, e--) : t[e] == null && (o = 1, s++), new C1(se.of(t.slice(0, e)), o, se.of(t.slice(s)));
  }
}
se.prototype.size = 1;
class qp extends se {
  constructor(t, e, s) {
    super(t, e), this.type = s;
  }
  blockAt(t, e, s, n) {
    return new _i(n, this.length, s, this.height, this.type);
  }
  lineAt(t, e, s, n, r) {
    return this.blockAt(0, s, n, r);
  }
  forEachLine(t, e, s, n, r, o) {
    t <= r + this.length && e >= r && o(this.blockAt(0, s, n, r));
  }
  updateHeight(t, e = 0, s = !1, n) {
    return n && n.from <= e && n.more && this.setHeight(t, n.heights[n.index++]), this.outdated = !1, this;
  }
  toString() {
    return `block(${this.length})`;
  }
}
class ae extends qp {
  constructor(t, e) {
    super(t, e, _t.Text), this.collapsed = 0, this.widgetHeight = 0;
  }
  replace(t, e, s) {
    let n = s[0];
    return s.length == 1 && (n instanceof ae || n instanceof Lt && n.flags & 4) && Math.abs(this.length - n.length) < 10 ? (n instanceof Lt ? n = new ae(n.length, this.height) : n.height = this.height, this.outdated || (n.outdated = !1), n) : se.of(s);
  }
  updateHeight(t, e = 0, s = !1, n) {
    return n && n.from <= e && n.more ? this.setHeight(t, n.heights[n.index++]) : (s || this.outdated) && this.setHeight(t, Math.max(this.widgetHeight, t.heightForLine(this.length - this.collapsed))), this.outdated = !1, this;
  }
  toString() {
    return `line(${this.length}${this.collapsed ? -this.collapsed : ""}${this.widgetHeight ? ":" + this.widgetHeight : ""})`;
  }
}
class Lt extends se {
  constructor(t) {
    super(t, 0);
  }
  lines(t, e) {
    let s = t.lineAt(e).number, n = t.lineAt(e + this.length).number;
    return { firstLine: s, lastLine: n, lineHeight: this.height / (n - s + 1) };
  }
  blockAt(t, e, s, n) {
    let { firstLine: r, lastLine: o, lineHeight: l } = this.lines(e, n), a = Math.max(0, Math.min(o - r, Math.floor((t - s) / l))), { from: h, length: u } = e.line(r + a);
    return new _i(h, u, s + l * a, l, _t.Text);
  }
  lineAt(t, e, s, n, r) {
    if (e == wt.ByHeight)
      return this.blockAt(t, s, n, r);
    if (e == wt.ByPosNoHeight) {
      let { from: c, to: f } = s.lineAt(t);
      return new _i(c, f - c, 0, 0, _t.Text);
    }
    let { firstLine: o, lineHeight: l } = this.lines(s, r), { from: a, length: h, number: u } = s.lineAt(t);
    return new _i(a, h, n + l * (u - o), l, _t.Text);
  }
  forEachLine(t, e, s, n, r, o) {
    let { firstLine: l, lineHeight: a } = this.lines(s, r);
    for (let h = Math.max(t, r), u = Math.min(r + this.length, e); h <= u; ) {
      let c = s.lineAt(h);
      h == t && (n += a * (c.number - l)), o(new _i(c.from, c.length, n, a, _t.Text)), n += a, h = c.to + 1;
    }
  }
  replace(t, e, s) {
    let n = this.length - e;
    if (n > 0) {
      let r = s[s.length - 1];
      r instanceof Lt ? s[s.length - 1] = new Lt(r.length + n) : s.push(null, new Lt(n - 1));
    }
    if (t > 0) {
      let r = s[0];
      r instanceof Lt ? s[0] = new Lt(t + r.length) : s.unshift(new Lt(t - 1), null);
    }
    return se.of(s);
  }
  decomposeLeft(t, e) {
    e.push(new Lt(t - 1), null);
  }
  decomposeRight(t, e) {
    e.push(null, new Lt(this.length - t - 1));
  }
  updateHeight(t, e = 0, s = !1, n) {
    let r = e + this.length;
    if (n && n.from <= e + this.length && n.more) {
      let o = [], l = Math.max(e, n.from), a = -1, h = t.heightChanged;
      for (n.from > e && o.push(new Lt(n.from - e - 1).updateHeight(t, e)); l <= r && n.more; ) {
        let c = t.doc.lineAt(l).length;
        o.length && o.push(null);
        let f = n.heights[n.index++];
        a == -1 ? a = f : Math.abs(f - a) >= pr && (a = -2);
        let g = new ae(c, f);
        g.outdated = !1, o.push(g), l += c + 1;
      }
      l <= r && o.push(null, new Lt(r - l).updateHeight(t, l));
      let u = se.of(o);
      return t.heightChanged = h || a < 0 || Math.abs(u.height - this.height) >= pr || Math.abs(a - this.lines(t.doc, e).lineHeight) >= pr, u;
    } else
      (s || this.outdated) && (this.setHeight(t, t.heightForGap(e, e + this.length)), this.outdated = !1);
    return this;
  }
  toString() {
    return `gap(${this.length})`;
  }
}
class C1 extends se {
  constructor(t, e, s) {
    super(t.length + e + s.length, t.height + s.height, e | (t.outdated || s.outdated ? 2 : 0)), this.left = t, this.right = s, this.size = t.size + s.size;
  }
  get break() {
    return this.flags & 1;
  }
  blockAt(t, e, s, n) {
    let r = s + this.left.height;
    return t < r ? this.left.blockAt(t, e, s, n) : this.right.blockAt(t, e, r, n + this.left.length + this.break);
  }
  lineAt(t, e, s, n, r) {
    let o = n + this.left.height, l = r + this.left.length + this.break, a = e == wt.ByHeight ? t < o : t < l, h = a ? this.left.lineAt(t, e, s, n, r) : this.right.lineAt(t, e, s, o, l);
    if (this.break || (a ? h.to < l : h.from > l))
      return h;
    let u = e == wt.ByPosNoHeight ? wt.ByPosNoHeight : wt.ByPos;
    return a ? h.join(this.right.lineAt(l, u, s, o, l)) : this.left.lineAt(l, u, s, n, r).join(h);
  }
  forEachLine(t, e, s, n, r, o) {
    let l = n + this.left.height, a = r + this.left.length + this.break;
    if (this.break)
      t < a && this.left.forEachLine(t, e, s, n, r, o), e >= a && this.right.forEachLine(t, e, s, l, a, o);
    else {
      let h = this.lineAt(a, wt.ByPos, s, n, r);
      t < h.from && this.left.forEachLine(t, h.from - 1, s, n, r, o), h.to >= t && h.from <= e && o(h), e > h.to && this.right.forEachLine(h.to + 1, e, s, l, a, o);
    }
  }
  replace(t, e, s) {
    let n = this.left.length + this.break;
    if (e < n)
      return this.balanced(this.left.replace(t, e, s), this.right);
    if (t > this.left.length)
      return this.balanced(this.left, this.right.replace(t - n, e - n, s));
    let r = [];
    t > 0 && this.decomposeLeft(t, r);
    let o = r.length;
    for (let l of s)
      r.push(l);
    if (t > 0 && ic(r, o - 1), e < this.length) {
      let l = r.length;
      this.decomposeRight(e, r), ic(r, l);
    }
    return se.of(r);
  }
  decomposeLeft(t, e) {
    let s = this.left.length;
    if (t <= s)
      return this.left.decomposeLeft(t, e);
    e.push(this.left), this.break && (s++, t >= s && e.push(null)), t > s && this.right.decomposeLeft(t - s, e);
  }
  decomposeRight(t, e) {
    let s = this.left.length, n = s + this.break;
    if (t >= n)
      return this.right.decomposeRight(t - n, e);
    t < s && this.left.decomposeRight(t, e), this.break && t < n && e.push(null), e.push(this.right);
  }
  balanced(t, e) {
    return t.size > 2 * e.size || e.size > 2 * t.size ? se.of(this.break ? [t, null, e] : [t, e]) : (this.left = t, this.right = e, this.height = t.height + e.height, this.outdated = t.outdated || e.outdated, this.size = t.size + e.size, this.length = t.length + this.break + e.length, this);
  }
  updateHeight(t, e = 0, s = !1, n) {
    let { left: r, right: o } = this, l = e + r.length + this.break, a = null;
    return n && n.from <= e + r.length && n.more ? a = r = r.updateHeight(t, e, s, n) : r.updateHeight(t, e, s), n && n.from <= l + o.length && n.more ? a = o = o.updateHeight(t, l, s, n) : o.updateHeight(t, l, s), a ? this.balanced(r, o) : (this.height = this.left.height + this.right.height, this.outdated = !1, this);
  }
  toString() {
    return this.left + (this.break ? " " : "-") + this.right;
  }
}
function ic(i, t) {
  let e, s;
  i[t] == null && (e = i[t - 1]) instanceof Lt && (s = i[t + 1]) instanceof Lt && i.splice(t - 1, 3, new Lt(e.length + 1 + s.length));
}
const A1 = 5;
class fh {
  constructor(t, e) {
    this.pos = t, this.oracle = e, this.nodes = [], this.lineStart = -1, this.lineEnd = -1, this.covering = null, this.writtenTo = t;
  }
  get isCovered() {
    return this.covering && this.nodes[this.nodes.length - 1] == this.covering;
  }
  span(t, e) {
    if (this.lineStart > -1) {
      let s = Math.min(e, this.lineEnd), n = this.nodes[this.nodes.length - 1];
      n instanceof ae ? n.length += s - this.pos : (s > this.pos || !this.isCovered) && this.nodes.push(new ae(s - this.pos, -1)), this.writtenTo = s, e > s && (this.nodes.push(null), this.writtenTo++, this.lineStart = -1);
    }
    this.pos = e;
  }
  point(t, e, s) {
    if (t < e || s.heightRelevant) {
      let n = s.widget ? s.widget.estimatedHeight : 0;
      n < 0 && (n = this.oracle.lineHeight);
      let r = e - t;
      s.block ? this.addBlock(new qp(r, n, s.type)) : (r || n >= A1) && this.addLineDeco(n, r);
    } else
      e > t && this.span(t, e);
    this.lineEnd > -1 && this.lineEnd < this.pos && (this.lineEnd = this.oracle.doc.lineAt(this.pos).to);
  }
  enterLine() {
    if (this.lineStart > -1)
      return;
    let { from: t, to: e } = this.oracle.doc.lineAt(this.pos);
    this.lineStart = t, this.lineEnd = e, this.writtenTo < t && ((this.writtenTo < t - 1 || this.nodes[this.nodes.length - 1] == null) && this.nodes.push(this.blankContent(this.writtenTo, t - 1)), this.nodes.push(null)), this.pos > t && this.nodes.push(new ae(this.pos - t, -1)), this.writtenTo = this.pos;
  }
  blankContent(t, e) {
    let s = new Lt(e - t);
    return this.oracle.doc.lineAt(t).to == e && (s.flags |= 4), s;
  }
  ensureLine() {
    this.enterLine();
    let t = this.nodes.length ? this.nodes[this.nodes.length - 1] : null;
    if (t instanceof ae)
      return t;
    let e = new ae(0, -1);
    return this.nodes.push(e), e;
  }
  addBlock(t) {
    this.enterLine(), t.type == _t.WidgetAfter && !this.isCovered && this.ensureLine(), this.nodes.push(t), this.writtenTo = this.pos = this.pos + t.length, t.type != _t.WidgetBefore && (this.covering = t);
  }
  addLineDeco(t, e) {
    let s = this.ensureLine();
    s.length += e, s.collapsed += e, s.widgetHeight = Math.max(s.widgetHeight, t), this.writtenTo = this.pos = this.pos + e;
  }
  finish(t) {
    let e = this.nodes.length == 0 ? null : this.nodes[this.nodes.length - 1];
    this.lineStart > -1 && !(e instanceof ae) && !this.isCovered ? this.nodes.push(new ae(0, -1)) : (this.writtenTo < this.pos || e == null) && this.nodes.push(this.blankContent(this.writtenTo, this.pos));
    let s = t;
    for (let n of this.nodes)
      n instanceof ae && n.updateHeight(this.oracle, s), s += n ? n.length : 1;
    return this.nodes;
  }
  static build(t, e, s, n) {
    let r = new fh(s, t);
    return ft.spans(e, s, n, r, 0), r.finish(s);
  }
}
function T1(i, t, e) {
  let s = new P1();
  return ft.compare(i, t, e, s, 0), s.changes;
}
class P1 {
  constructor() {
    this.changes = [];
  }
  compareRange() {
  }
  comparePoint(t, e, s, n) {
    (t < e || s && s.heightRelevant || n && n.heightRelevant) && ga(t, e, this.changes, 5);
  }
}
function E1(i, t) {
  let e = i.getBoundingClientRect(), s = Math.max(0, e.left), n = Math.min(innerWidth, e.right), r = Math.max(0, e.top), o = Math.min(innerHeight, e.bottom), l = i.ownerDocument.body;
  for (let a = i.parentNode; a && a != l; )
    if (a.nodeType == 1) {
      let h = a, u = window.getComputedStyle(h);
      if ((h.scrollHeight > h.clientHeight || h.scrollWidth > h.clientWidth) && u.overflow != "visible") {
        let c = h.getBoundingClientRect();
        s = Math.max(s, c.left), n = Math.min(n, c.right), r = Math.max(r, c.top), o = Math.min(o, c.bottom);
      }
      a = u.position == "absolute" || u.position == "fixed" ? h.offsetParent : h.parentNode;
    } else if (a.nodeType == 11)
      a = a.host;
    else
      break;
  return {
    left: s - e.left,
    right: Math.max(s, n) - e.left,
    top: r - (e.top + t),
    bottom: Math.max(r, o) - (e.top + t)
  };
}
function M1(i, t) {
  let e = i.getBoundingClientRect();
  return {
    left: 0,
    right: e.right - e.left,
    top: t,
    bottom: e.bottom - (e.top + t)
  };
}
class Ko {
  constructor(t, e, s) {
    this.from = t, this.to = e, this.size = s;
  }
  static same(t, e) {
    if (t.length != e.length)
      return !1;
    for (let s = 0; s < t.length; s++) {
      let n = t[s], r = e[s];
      if (n.from != r.from || n.to != r.to || n.size != r.size)
        return !1;
    }
    return !0;
  }
  draw(t) {
    return X.replace({ widget: new R1(this.size, t) }).range(this.from, this.to);
  }
}
class R1 extends ni {
  constructor(t, e) {
    super(), this.size = t, this.vertical = e;
  }
  eq(t) {
    return t.size == this.size && t.vertical == this.vertical;
  }
  toDOM() {
    let t = document.createElement("div");
    return this.vertical ? t.style.height = this.size + "px" : (t.style.width = this.size + "px", t.style.height = "2px", t.style.display = "inline-block"), t;
  }
  get estimatedHeight() {
    return this.vertical ? this.size : -1;
  }
}
class sc {
  constructor(t) {
    this.state = t, this.pixelViewport = { left: 0, right: window.innerWidth, top: 0, bottom: 0 }, this.inView = !0, this.paddingTop = 0, this.paddingBottom = 0, this.contentDOMWidth = 0, this.contentDOMHeight = 0, this.editorHeight = 0, this.editorWidth = 0, this.heightOracle = new O1(), this.scaler = oc, this.scrollTarget = null, this.printing = !1, this.mustMeasureContent = !0, this.defaultTextDirection = St.RTL, this.visibleRanges = [], this.mustEnforceCursorAssoc = !1, this.stateDeco = t.facet(_n).filter((e) => typeof e != "function"), this.heightMap = se.empty().applyChanges(this.stateDeco, ct.empty, this.heightOracle.setDoc(t.doc), [new Ye(0, 0, 0, t.doc.length)]), this.viewport = this.getViewport(0, null), this.updateViewportLines(), this.updateForViewport(), this.lineGaps = this.ensureLineGaps([]), this.lineGapDeco = X.set(this.lineGaps.map((e) => e.draw(!1))), this.computeVisibleRanges();
  }
  updateForViewport() {
    let t = [this.viewport], { main: e } = this.state.selection;
    for (let s = 0; s <= 1; s++) {
      let n = s ? e.head : e.anchor;
      if (!t.some(({ from: r, to: o }) => n >= r && n <= o)) {
        let { from: r, to: o } = this.lineBlockAt(n);
        t.push(new Wn(r, o));
      }
    }
    this.viewports = t.sort((s, n) => s.from - n.from), this.scaler = this.heightMap.height <= 7e6 ? oc : new N1(this.heightOracle.doc, this.heightMap, this.viewports);
  }
  updateViewportLines() {
    this.viewportLines = [], this.heightMap.forEachLine(this.viewport.from, this.viewport.to, this.state.doc, 0, 0, (t) => {
      this.viewportLines.push(this.scaler.scale == 1 ? t : js(t, this.scaler));
    });
  }
  update(t, e = null) {
    this.state = t.state;
    let s = this.stateDeco;
    this.stateDeco = this.state.facet(_n).filter((h) => typeof h != "function");
    let n = t.changedRanges, r = Ye.extendWithRanges(n, T1(s, this.stateDeco, t ? t.changes : Pt.empty(this.state.doc.length))), o = this.heightMap.height;
    this.heightMap = this.heightMap.applyChanges(this.stateDeco, t.startState.doc, this.heightOracle.setDoc(this.state.doc), r), this.heightMap.height != o && (t.flags |= 2);
    let l = r.length ? this.mapViewport(this.viewport, t.changes) : this.viewport;
    (e && (e.range.head < l.from || e.range.head > l.to) || !this.viewportIsAppropriate(l)) && (l = this.getViewport(0, e));
    let a = !t.changes.empty || t.flags & 2 || l.from != this.viewport.from || l.to != this.viewport.to;
    this.viewport = l, this.updateForViewport(), a && this.updateViewportLines(), (this.lineGaps.length || this.viewport.to - this.viewport.from > 4e3) && this.updateLineGaps(this.ensureLineGaps(this.mapLineGaps(this.lineGaps, t.changes))), t.flags |= this.computeVisibleRanges(), e && (this.scrollTarget = e), !this.mustEnforceCursorAssoc && t.selectionSet && t.view.lineWrapping && t.state.selection.main.empty && t.state.selection.main.assoc && (this.mustEnforceCursorAssoc = !0);
  }
  measure(t) {
    let e = t.contentDOM, s = window.getComputedStyle(e), n = this.heightOracle, r = s.whiteSpace;
    this.defaultTextDirection = s.direction == "rtl" ? St.RTL : St.LTR;
    let o = this.heightOracle.mustRefreshForWrapping(r), l = o || this.mustMeasureContent || this.contentDOMHeight != e.clientHeight;
    this.contentDOMHeight = e.clientHeight, this.mustMeasureContent = !1;
    let a = 0, h = 0, u = parseInt(s.paddingTop) || 0, c = parseInt(s.paddingBottom) || 0;
    (this.paddingTop != u || this.paddingBottom != c) && (this.paddingTop = u, this.paddingBottom = c, a |= 10), this.editorWidth != t.scrollDOM.clientWidth && (n.lineWrapping && (l = !0), this.editorWidth = t.scrollDOM.clientWidth, a |= 8);
    let f = (this.printing ? M1 : E1)(e, this.paddingTop), g = f.top - this.pixelViewport.top, _ = f.bottom - this.pixelViewport.bottom;
    this.pixelViewport = f;
    let A = this.pixelViewport.bottom > this.pixelViewport.top && this.pixelViewport.right > this.pixelViewport.left;
    if (A != this.inView && (this.inView = A, A && (l = !0)), !this.inView)
      return 0;
    let m = e.clientWidth;
    if ((this.contentDOMWidth != m || this.editorHeight != t.scrollDOM.clientHeight) && (this.contentDOMWidth = m, this.editorHeight = t.scrollDOM.clientHeight, a |= 8), l) {
      let y = t.docView.measureVisibleLineHeights(this.viewport);
      if (n.mustRefreshForHeights(y) && (o = !0), o || n.lineWrapping && Math.abs(m - this.contentDOMWidth) > n.charWidth) {
        let { lineHeight: M, charWidth: x } = t.docView.measureTextSize();
        o = n.refresh(r, M, x, m / x, y), o && (t.docView.minWidth = 0, a |= 8);
      }
      g > 0 && _ > 0 ? h = Math.max(g, _) : g < 0 && _ < 0 && (h = Math.min(g, _)), n.heightChanged = !1;
      for (let M of this.viewports) {
        let x = M.from == this.viewport.from ? y : t.docView.measureVisibleLineHeights(M);
        this.heightMap = this.heightMap.updateHeight(n, 0, o, new S1(M.from, x));
      }
      n.heightChanged && (a |= 2);
    }
    let p = !this.viewportIsAppropriate(this.viewport, h) || this.scrollTarget && (this.scrollTarget.range.head < this.viewport.from || this.scrollTarget.range.head > this.viewport.to);
    return p && (this.viewport = this.getViewport(h, this.scrollTarget)), this.updateForViewport(), (a & 2 || p) && this.updateViewportLines(), (this.lineGaps.length || this.viewport.to - this.viewport.from > 4e3) && this.updateLineGaps(this.ensureLineGaps(o ? [] : this.lineGaps)), a |= this.computeVisibleRanges(), this.mustEnforceCursorAssoc && (this.mustEnforceCursorAssoc = !1, t.docView.enforceCursorAssoc()), a;
  }
  get visibleTop() {
    return this.scaler.fromDOM(this.pixelViewport.top);
  }
  get visibleBottom() {
    return this.scaler.fromDOM(this.pixelViewport.bottom);
  }
  getViewport(t, e) {
    let s = 0.5 - Math.max(-0.5, Math.min(0.5, t / 1e3 / 2)), n = this.heightMap, r = this.state.doc, { visibleTop: o, visibleBottom: l } = this, a = new Wn(n.lineAt(o - s * 1e3, wt.ByHeight, r, 0, 0).from, n.lineAt(l + (1 - s) * 1e3, wt.ByHeight, r, 0, 0).to);
    if (e) {
      let { head: h } = e.range;
      if (h < a.from || h > a.to) {
        let u = Math.min(this.editorHeight, this.pixelViewport.bottom - this.pixelViewport.top), c = n.lineAt(h, wt.ByPos, r, 0, 0), f;
        e.y == "center" ? f = (c.top + c.bottom) / 2 - u / 2 : e.y == "start" || e.y == "nearest" && h < a.from ? f = c.top : f = c.bottom - u, a = new Wn(n.lineAt(f - 1e3 / 2, wt.ByHeight, r, 0, 0).from, n.lineAt(f + u + 1e3 / 2, wt.ByHeight, r, 0, 0).to);
      }
    }
    return a;
  }
  mapViewport(t, e) {
    let s = e.mapPos(t.from, -1), n = e.mapPos(t.to, 1);
    return new Wn(this.heightMap.lineAt(s, wt.ByPos, this.state.doc, 0, 0).from, this.heightMap.lineAt(n, wt.ByPos, this.state.doc, 0, 0).to);
  }
  viewportIsAppropriate({ from: t, to: e }, s = 0) {
    if (!this.inView)
      return !0;
    let { top: n } = this.heightMap.lineAt(t, wt.ByPos, this.state.doc, 0, 0), { bottom: r } = this.heightMap.lineAt(e, wt.ByPos, this.state.doc, 0, 0), { visibleTop: o, visibleBottom: l } = this;
    return (t == 0 || n <= o - Math.max(10, Math.min(-s, 250))) && (e == this.state.doc.length || r >= l + Math.max(10, Math.min(s, 250))) && n > o - 2 * 1e3 && r < l + 2 * 1e3;
  }
  mapLineGaps(t, e) {
    if (!t.length || e.empty)
      return t;
    let s = [];
    for (let n of t)
      e.touchesRange(n.from, n.to) || s.push(new Ko(e.mapPos(n.from), e.mapPos(n.to), n.size));
    return s;
  }
  ensureLineGaps(t) {
    let e = [];
    if (this.defaultTextDirection != St.LTR)
      return e;
    for (let s of this.viewportLines) {
      if (s.length < 4e3)
        continue;
      let n = D1(s.from, s.to, this.stateDeco);
      if (n.total < 4e3)
        continue;
      let r, o;
      if (this.heightOracle.lineWrapping) {
        let h = 2e3 / this.heightOracle.lineLength * this.heightOracle.lineHeight;
        r = $n(n, (this.visibleTop - s.top - h) / s.height), o = $n(n, (this.visibleBottom - s.top + h) / s.height);
      } else {
        let h = n.total * this.heightOracle.charWidth, u = 2e3 * this.heightOracle.charWidth;
        r = $n(n, (this.pixelViewport.left - u) / h), o = $n(n, (this.pixelViewport.right + u) / h);
      }
      let l = [];
      r > s.from && l.push({ from: s.from, to: r }), o < s.to && l.push({ from: o, to: s.to });
      let a = this.state.selection.main;
      a.from >= s.from && a.from <= s.to && rc(l, a.from - 10, a.from + 10), !a.empty && a.to >= s.from && a.to <= s.to && rc(l, a.to - 10, a.to + 10);
      for (let { from: h, to: u } of l)
        u - h > 1e3 && e.push(B1(t, (c) => c.from >= s.from && c.to <= s.to && Math.abs(c.from - h) < 1e3 && Math.abs(c.to - u) < 1e3) || new Ko(h, u, this.gapSize(s, h, u, n)));
    }
    return e;
  }
  gapSize(t, e, s, n) {
    let r = nc(n, s) - nc(n, e);
    return this.heightOracle.lineWrapping ? t.height * r : n.total * this.heightOracle.charWidth * r;
  }
  updateLineGaps(t) {
    Ko.same(t, this.lineGaps) || (this.lineGaps = t, this.lineGapDeco = X.set(t.map((e) => e.draw(this.heightOracle.lineWrapping))));
  }
  computeVisibleRanges() {
    let t = this.stateDeco;
    this.lineGaps.length && (t = t.concat(this.lineGapDeco));
    let e = [];
    ft.spans(t, this.viewport.from, this.viewport.to, {
      span(n, r) {
        e.push({ from: n, to: r });
      },
      point() {
      }
    }, 20);
    let s = e.length != this.visibleRanges.length || this.visibleRanges.some((n, r) => n.from != e[r].from || n.to != e[r].to);
    return this.visibleRanges = e, s ? 4 : 0;
  }
  lineBlockAt(t) {
    return t >= this.viewport.from && t <= this.viewport.to && this.viewportLines.find((e) => e.from <= t && e.to >= t) || js(this.heightMap.lineAt(t, wt.ByPos, this.state.doc, 0, 0), this.scaler);
  }
  lineBlockAtHeight(t) {
    return js(this.heightMap.lineAt(this.scaler.fromDOM(t), wt.ByHeight, this.state.doc, 0, 0), this.scaler);
  }
  elementAtHeight(t) {
    return js(this.heightMap.blockAt(this.scaler.fromDOM(t), this.state.doc, 0, 0), this.scaler);
  }
  get docHeight() {
    return this.scaler.toDOM(this.heightMap.height);
  }
  get contentHeight() {
    return this.docHeight + this.paddingTop + this.paddingBottom;
  }
}
class Wn {
  constructor(t, e) {
    this.from = t, this.to = e;
  }
}
function D1(i, t, e) {
  let s = [], n = i, r = 0;
  return ft.spans(e, i, t, {
    span() {
    },
    point(o, l) {
      o > n && (s.push({ from: n, to: o }), r += o - n), n = l;
    }
  }, 20), n < t && (s.push({ from: n, to: t }), r += t - n), { total: r, ranges: s };
}
function $n({ total: i, ranges: t }, e) {
  if (e <= 0)
    return t[0].from;
  if (e >= 1)
    return t[t.length - 1].to;
  let s = Math.floor(i * e);
  for (let n = 0; ; n++) {
    let { from: r, to: o } = t[n], l = o - r;
    if (s <= l)
      return r + s;
    s -= l;
  }
}
function nc(i, t) {
  let e = 0;
  for (let { from: s, to: n } of i.ranges) {
    if (t <= n) {
      e += t - s;
      break;
    }
    e += n - s;
  }
  return e / i.total;
}
function rc(i, t, e) {
  for (let s = 0; s < i.length; s++) {
    let n = i[s];
    if (n.from < e && n.to > t) {
      let r = [];
      n.from < t && r.push({ from: n.from, to: t }), n.to > e && r.push({ from: e, to: n.to }), i.splice(s, 1, ...r), s += r.length - 1;
    }
  }
}
function B1(i, t) {
  for (let e of i)
    if (t(e))
      return e;
}
const oc = {
  toDOM(i) {
    return i;
  },
  fromDOM(i) {
    return i;
  },
  scale: 1
};
class N1 {
  constructor(t, e, s) {
    let n = 0, r = 0, o = 0;
    this.viewports = s.map(({ from: l, to: a }) => {
      let h = e.lineAt(l, wt.ByPos, t, 0, 0).top, u = e.lineAt(a, wt.ByPos, t, 0, 0).bottom;
      return n += u - h, { from: l, to: a, top: h, bottom: u, domTop: 0, domBottom: 0 };
    }), this.scale = (7e6 - n) / (e.height - n);
    for (let l of this.viewports)
      l.domTop = o + (l.top - r) * this.scale, o = l.domBottom = l.domTop + (l.bottom - l.top), r = l.bottom;
  }
  toDOM(t) {
    for (let e = 0, s = 0, n = 0; ; e++) {
      let r = e < this.viewports.length ? this.viewports[e] : null;
      if (!r || t < r.top)
        return n + (t - s) * this.scale;
      if (t <= r.bottom)
        return r.domTop + (t - r.top);
      s = r.bottom, n = r.domBottom;
    }
  }
  fromDOM(t) {
    for (let e = 0, s = 0, n = 0; ; e++) {
      let r = e < this.viewports.length ? this.viewports[e] : null;
      if (!r || t < r.domTop)
        return s + (t - n) / this.scale;
      if (t <= r.domBottom)
        return r.top + (t - r.domTop);
      s = r.bottom, n = r.domBottom;
    }
  }
}
function js(i, t) {
  if (t.scale == 1)
    return i;
  let e = t.toDOM(i.top), s = t.toDOM(i.bottom);
  return new _i(i.from, i.length, e, s - e, Array.isArray(i.type) ? i.type.map((n) => js(n, t)) : i.type);
}
const Fn = /* @__PURE__ */ q.define({ combine: (i) => i.join(" ") }), xa = /* @__PURE__ */ q.define({ combine: (i) => i.indexOf(!0) > -1 }), ka = /* @__PURE__ */ Si.newName(), Kp = /* @__PURE__ */ Si.newName(), Xp = /* @__PURE__ */ Si.newName(), Gp = { "&light": "." + Kp, "&dark": "." + Xp };
function Oa(i, t, e) {
  return new Si(t, {
    finish(s) {
      return /&/.test(s) ? s.replace(/&\w*/, (n) => {
        if (n == "&")
          return i;
        if (!e || !e[n])
          throw new RangeError(`Unsupported selector: ${n}`);
        return e[n];
      }) : i + " " + s;
    }
  });
}
const L1 = /* @__PURE__ */ Oa("." + ka, {
  "&.cm-editor": {
    position: "relative !important",
    boxSizing: "border-box",
    "&.cm-focused": {
      outline: "1px dotted #212121"
    },
    display: "flex !important",
    flexDirection: "column"
  },
  ".cm-scroller": {
    display: "flex !important",
    alignItems: "flex-start !important",
    fontFamily: "monospace",
    lineHeight: 1.4,
    height: "100%",
    overflowX: "auto",
    position: "relative",
    zIndex: 0
  },
  ".cm-content": {
    margin: 0,
    flexGrow: 2,
    flexShrink: 0,
    minHeight: "100%",
    display: "block",
    whiteSpace: "pre",
    wordWrap: "normal",
    boxSizing: "border-box",
    padding: "4px 0",
    outline: "none",
    "&[contenteditable=true]": {
      WebkitUserModify: "read-write-plaintext-only"
    }
  },
  ".cm-lineWrapping": {
    whiteSpace_fallback: "pre-wrap",
    whiteSpace: "break-spaces",
    wordBreak: "break-word",
    overflowWrap: "anywhere",
    flexShrink: 1
  },
  "&light .cm-content": { caretColor: "black" },
  "&dark .cm-content": { caretColor: "white" },
  ".cm-line": {
    display: "block",
    padding: "0 2px 0 4px"
  },
  ".cm-selectionLayer": {
    zIndex: -1,
    contain: "size style"
  },
  ".cm-selectionBackground": {
    position: "absolute"
  },
  "&light .cm-selectionBackground": {
    background: "#d9d9d9"
  },
  "&dark .cm-selectionBackground": {
    background: "#222"
  },
  "&light.cm-focused .cm-selectionBackground": {
    background: "#d7d4f0"
  },
  "&dark.cm-focused .cm-selectionBackground": {
    background: "#233"
  },
  ".cm-cursorLayer": {
    zIndex: 100,
    contain: "size style",
    pointerEvents: "none"
  },
  "&.cm-focused .cm-cursorLayer": {
    animation: "steps(1) cm-blink 1.2s infinite"
  },
  "@keyframes cm-blink": { "0%": {}, "50%": { opacity: 0 }, "100%": {} },
  "@keyframes cm-blink2": { "0%": {}, "50%": { opacity: 0 }, "100%": {} },
  ".cm-cursor, .cm-dropCursor": {
    position: "absolute",
    borderLeft: "1.2px solid black",
    marginLeft: "-0.6px",
    pointerEvents: "none"
  },
  ".cm-cursor": {
    display: "none"
  },
  "&dark .cm-cursor": {
    borderLeftColor: "#444"
  },
  "&.cm-focused .cm-cursor": {
    display: "block"
  },
  "&light .cm-activeLine": { backgroundColor: "#f3f9ff" },
  "&dark .cm-activeLine": { backgroundColor: "#223039" },
  "&light .cm-specialChar": { color: "red" },
  "&dark .cm-specialChar": { color: "#f78" },
  ".cm-gutters": {
    flexShrink: 0,
    display: "flex",
    height: "100%",
    boxSizing: "border-box",
    left: 0,
    zIndex: 200
  },
  "&light .cm-gutters": {
    backgroundColor: "#f5f5f5",
    color: "#6c6c6c",
    borderRight: "1px solid #ddd"
  },
  "&dark .cm-gutters": {
    backgroundColor: "#333338",
    color: "#ccc"
  },
  ".cm-gutter": {
    display: "flex !important",
    flexDirection: "column",
    flexShrink: 0,
    boxSizing: "border-box",
    minHeight: "100%",
    overflow: "hidden"
  },
  ".cm-gutterElement": {
    boxSizing: "border-box"
  },
  ".cm-lineNumbers .cm-gutterElement": {
    padding: "0 3px 0 5px",
    minWidth: "20px",
    textAlign: "right",
    whiteSpace: "nowrap"
  },
  "&light .cm-activeLineGutter": {
    backgroundColor: "#e2f2ff"
  },
  "&dark .cm-activeLineGutter": {
    backgroundColor: "#222227"
  },
  ".cm-panels": {
    boxSizing: "border-box",
    position: "sticky",
    left: 0,
    right: 0
  },
  "&light .cm-panels": {
    backgroundColor: "#f5f5f5",
    color: "black"
  },
  "&light .cm-panels-top": {
    borderBottom: "1px solid #ddd"
  },
  "&light .cm-panels-bottom": {
    borderTop: "1px solid #ddd"
  },
  "&dark .cm-panels": {
    backgroundColor: "#333338",
    color: "white"
  },
  ".cm-tab": {
    display: "inline-block",
    overflow: "hidden",
    verticalAlign: "bottom"
  },
  ".cm-widgetBuffer": {
    verticalAlign: "text-top",
    height: "1em",
    display: "inline"
  },
  ".cm-placeholder": {
    color: "#888",
    display: "inline-block",
    verticalAlign: "top"
  },
  ".cm-button": {
    verticalAlign: "middle",
    color: "inherit",
    fontSize: "70%",
    padding: ".2em 1em",
    borderRadius: "1px"
  },
  "&light .cm-button": {
    backgroundImage: "linear-gradient(#eff1f5, #d9d9df)",
    border: "1px solid #888",
    "&:active": {
      backgroundImage: "linear-gradient(#b4b4b4, #d0d3d6)"
    }
  },
  "&dark .cm-button": {
    backgroundImage: "linear-gradient(#393939, #111)",
    border: "1px solid #888",
    "&:active": {
      backgroundImage: "linear-gradient(#111, #333)"
    }
  },
  ".cm-textfield": {
    verticalAlign: "middle",
    color: "inherit",
    fontSize: "70%",
    border: "1px solid silver",
    padding: ".2em .5em"
  },
  "&light .cm-textfield": {
    backgroundColor: "white"
  },
  "&dark .cm-textfield": {
    border: "1px solid #555",
    backgroundColor: "inherit"
  }
}, Gp), I1 = {
  childList: !0,
  characterData: !0,
  subtree: !0,
  attributes: !0,
  characterDataOldValue: !0
}, Xo = j.ie && j.ie_version <= 11;
class Q1 {
  constructor(t, e, s) {
    this.view = t, this.onChange = e, this.onScrollChanged = s, this.active = !1, this.selectionRange = new Iy(), this.selectionChanged = !1, this.delayedFlush = -1, this.resizeTimeout = -1, this.queue = [], this.delayedAndroidKey = null, this.scrollTargets = [], this.intersection = null, this.resize = null, this.intersecting = !1, this.gapIntersection = null, this.gaps = [], this.parentCheck = -1, this.dom = t.contentDOM, this.observer = new MutationObserver((n) => {
      for (let r of n)
        this.queue.push(r);
      (j.ie && j.ie_version <= 11 || j.ios && t.composing) && n.some((r) => r.type == "childList" && r.removedNodes.length || r.type == "characterData" && r.oldValue.length > r.target.nodeValue.length) ? this.flushSoon() : this.flush();
    }), Xo && (this.onCharData = (n) => {
      this.queue.push({
        target: n.target,
        type: "characterData",
        oldValue: n.prevValue
      }), this.flushSoon();
    }), this.onSelectionChange = this.onSelectionChange.bind(this), window.addEventListener("resize", this.onResize = this.onResize.bind(this)), typeof ResizeObserver == "function" && (this.resize = new ResizeObserver(() => {
      this.view.docView.lastUpdate < Date.now() - 75 && this.onResize();
    }), this.resize.observe(t.scrollDOM)), window.addEventListener("beforeprint", this.onPrint = this.onPrint.bind(this)), this.start(), window.addEventListener("scroll", this.onScroll = this.onScroll.bind(this)), typeof IntersectionObserver == "function" && (this.intersection = new IntersectionObserver((n) => {
      this.parentCheck < 0 && (this.parentCheck = setTimeout(this.listenForScroll.bind(this), 1e3)), n.length > 0 && n[n.length - 1].intersectionRatio > 0 != this.intersecting && (this.intersecting = !this.intersecting, this.intersecting != this.view.inView && this.onScrollChanged(document.createEvent("Event")));
    }, {}), this.intersection.observe(this.dom), this.gapIntersection = new IntersectionObserver((n) => {
      n.length > 0 && n[n.length - 1].intersectionRatio > 0 && this.onScrollChanged(document.createEvent("Event"));
    }, {})), this.listenForScroll(), this.readSelectionRange(), this.dom.ownerDocument.addEventListener("selectionchange", this.onSelectionChange);
  }
  onScroll(t) {
    this.intersecting && this.flush(!1), this.onScrollChanged(t);
  }
  onResize() {
    this.resizeTimeout < 0 && (this.resizeTimeout = setTimeout(() => {
      this.resizeTimeout = -1, this.view.requestMeasure();
    }, 50));
  }
  onPrint() {
    this.view.viewState.printing = !0, this.view.measure(), setTimeout(() => {
      this.view.viewState.printing = !1, this.view.requestMeasure();
    }, 500);
  }
  updateGaps(t) {
    if (this.gapIntersection && (t.length != this.gaps.length || this.gaps.some((e, s) => e != t[s]))) {
      this.gapIntersection.disconnect();
      for (let e of t)
        this.gapIntersection.observe(e);
      this.gaps = t;
    }
  }
  onSelectionChange(t) {
    if (!this.readSelectionRange() || this.delayedAndroidKey)
      return;
    let { view: e } = this, s = this.selectionRange;
    if (e.state.facet(wo) ? e.root.activeElement != this.dom : !dr(e.dom, s))
      return;
    let n = s.anchorNode && e.docView.nearest(s.anchorNode);
    n && n.ignoreEvent(t) || ((j.ie && j.ie_version <= 11 || j.android && j.chrome) && !e.state.selection.main.empty && s.focusNode && Mr(s.focusNode, s.focusOffset, s.anchorNode, s.anchorOffset) ? this.flushSoon() : this.flush(!1));
  }
  readSelectionRange() {
    let { view: t } = this, e = j.safari && t.root.nodeType == 11 && By() == this.dom && z1(this.view) || Er(t.root);
    if (!e || this.selectionRange.eq(e))
      return !1;
    let s = dr(this.dom, e);
    return s && !this.selectionChanged && this.selectionRange.focusNode && t.inputState.lastFocusTime > Date.now() - 200 && t.inputState.lastTouchTime < Date.now() - 300 && zy(this.dom, e) ? (t.docView.updateSelection(), !1) : (this.selectionRange.setRange(e), s && (this.selectionChanged = !0), !0);
  }
  setSelectionRange(t, e) {
    this.selectionRange.set(t.node, t.offset, e.node, e.offset), this.selectionChanged = !1;
  }
  clearSelectionRange() {
    this.selectionRange.set(null, 0, null, 0);
  }
  listenForScroll() {
    this.parentCheck = -1;
    let t = 0, e = null;
    for (let s = this.dom; s; )
      if (s.nodeType == 1)
        !e && t < this.scrollTargets.length && this.scrollTargets[t] == s ? t++ : e || (e = this.scrollTargets.slice(0, t)), e && e.push(s), s = s.assignedSlot || s.parentNode;
      else if (s.nodeType == 11)
        s = s.host;
      else
        break;
    if (t < this.scrollTargets.length && !e && (e = this.scrollTargets.slice(0, t)), e) {
      for (let s of this.scrollTargets)
        s.removeEventListener("scroll", this.onScroll);
      for (let s of this.scrollTargets = e)
        s.addEventListener("scroll", this.onScroll);
    }
  }
  ignore(t) {
    if (!this.active)
      return t();
    try {
      return this.stop(), t();
    } finally {
      this.start(), this.clear();
    }
  }
  start() {
    this.active || (this.observer.observe(this.dom, I1), Xo && this.dom.addEventListener("DOMCharacterDataModified", this.onCharData), this.active = !0);
  }
  stop() {
    !this.active || (this.active = !1, this.observer.disconnect(), Xo && this.dom.removeEventListener("DOMCharacterDataModified", this.onCharData));
  }
  clear() {
    this.processRecords(), this.queue.length = 0, this.selectionChanged = !1;
  }
  delayAndroidKey(t, e) {
    this.delayedAndroidKey || requestAnimationFrame(() => {
      let s = this.delayedAndroidKey;
      this.delayedAndroidKey = null, this.delayedFlush = -1, this.flush() || en(this.dom, s.key, s.keyCode);
    }), (!this.delayedAndroidKey || t == "Enter") && (this.delayedAndroidKey = { key: t, keyCode: e });
  }
  flushSoon() {
    this.delayedFlush < 0 && (this.delayedFlush = window.setTimeout(() => {
      this.delayedFlush = -1, this.flush();
    }, 20));
  }
  forceFlush() {
    this.delayedFlush >= 0 && (window.clearTimeout(this.delayedFlush), this.delayedFlush = -1), this.flush();
  }
  processRecords() {
    let t = this.queue;
    for (let r of this.observer.takeRecords())
      t.push(r);
    t.length && (this.queue = []);
    let e = -1, s = -1, n = !1;
    for (let r of t) {
      let o = this.readMutation(r);
      !o || (o.typeOver && (n = !0), e == -1 ? { from: e, to: s } = o : (e = Math.min(o.from, e), s = Math.max(o.to, s)));
    }
    return { from: e, to: s, typeOver: n };
  }
  flush(t = !0) {
    if (this.delayedFlush >= 0 || this.delayedAndroidKey)
      return;
    t && this.readSelectionRange();
    let { from: e, to: s, typeOver: n } = this.processRecords(), r = this.selectionChanged && dr(this.dom, this.selectionRange);
    if (e < 0 && !r)
      return;
    this.view.inputState.lastFocusTime = 0, this.selectionChanged = !1;
    let o = this.view.state, l = this.onChange(e, s, n);
    return this.view.state == o && this.view.update([]), l;
  }
  readMutation(t) {
    let e = this.view.docView.nearest(t.target);
    if (!e || e.ignoreMutation(t))
      return null;
    if (e.markDirty(t.type == "attributes"), t.type == "attributes" && (e.dirty |= 4), t.type == "childList") {
      let s = lc(e, t.previousSibling || t.target.previousSibling, -1), n = lc(e, t.nextSibling || t.target.nextSibling, 1);
      return {
        from: s ? e.posAfter(s) : e.posAtStart,
        to: n ? e.posBefore(n) : e.posAtEnd,
        typeOver: !1
      };
    } else
      return t.type == "characterData" ? { from: e.posAtStart, to: e.posAtEnd, typeOver: t.target.nodeValue == t.oldValue } : null;
  }
  destroy() {
    var t, e, s;
    this.stop(), (t = this.intersection) === null || t === void 0 || t.disconnect(), (e = this.gapIntersection) === null || e === void 0 || e.disconnect(), (s = this.resize) === null || s === void 0 || s.disconnect();
    for (let n of this.scrollTargets)
      n.removeEventListener("scroll", this.onScroll);
    window.removeEventListener("scroll", this.onScroll), window.removeEventListener("resize", this.onResize), window.removeEventListener("beforeprint", this.onPrint), this.dom.ownerDocument.removeEventListener("selectionchange", this.onSelectionChange), clearTimeout(this.parentCheck), clearTimeout(this.resizeTimeout);
  }
}
function lc(i, t, e) {
  for (; t; ) {
    let s = kt.get(t);
    if (s && s.parent == i)
      return s;
    let n = t.parentNode;
    t = n != i.dom ? n : e > 0 ? t.nextSibling : t.previousSibling;
  }
  return null;
}
function z1(i) {
  let t = null;
  function e(a) {
    a.preventDefault(), a.stopImmediatePropagation(), t = a.getTargetRanges()[0];
  }
  if (i.contentDOM.addEventListener("beforeinput", e, !0), document.execCommand("indent"), i.contentDOM.removeEventListener("beforeinput", e, !0), !t)
    return null;
  let s = t.startContainer, n = t.startOffset, r = t.endContainer, o = t.endOffset, l = i.docView.domAtPos(i.state.selection.main.anchor);
  return Mr(l.node, l.offset, r, o) && ([s, n, r, o] = [r, o, s, n]), { anchorNode: s, anchorOffset: n, focusNode: r, focusOffset: o };
}
function W1(i, t, e, s) {
  let n, r, o = i.state.selection.main;
  if (t > -1) {
    let l = i.docView.domBoundsAround(t, e, 0);
    if (!l || i.state.readOnly)
      return !1;
    let { from: a, to: h } = l, u = i.docView.impreciseHead || i.docView.impreciseAnchor ? [] : F1(i), c = new Lp(u, i.state);
    c.readRange(l.startDOM, l.endDOM);
    let f = o.from, g = null;
    (i.inputState.lastKeyCode === 8 && i.inputState.lastKeyTime > Date.now() - 100 || j.android && c.text.length < h - a) && (f = o.to, g = "end");
    let _ = $1(i.state.doc.sliceString(a, h, mi), c.text, f - a, g);
    _ && (j.chrome && i.inputState.lastKeyCode == 13 && _.toB == _.from + 2 && c.text.slice(_.from, _.toB) == mi + mi && _.toB--, n = {
      from: a + _.from,
      to: a + _.toA,
      insert: ct.of(c.text.slice(_.from, _.toB).split(mi))
    }), r = V1(u, a);
  } else if (i.hasFocus || !i.state.facet(wo)) {
    let l = i.observer.selectionRange, { impreciseHead: a, impreciseAnchor: h } = i.docView, u = a && a.node == l.focusNode && a.offset == l.focusOffset || !_s(i.contentDOM, l.focusNode) ? i.state.selection.main.head : i.docView.posFromDOM(l.focusNode, l.focusOffset), c = h && h.node == l.anchorNode && h.offset == l.anchorOffset || !_s(i.contentDOM, l.anchorNode) ? i.state.selection.main.anchor : i.docView.posFromDOM(l.anchorNode, l.anchorOffset);
    (u != o.head || c != o.anchor) && (r = R.single(c, u));
  }
  if (!n && !r)
    return !1;
  if (!n && s && !o.empty && r && r.main.empty ? n = { from: o.from, to: o.to, insert: i.state.doc.slice(o.from, o.to) } : n && n.from >= o.from && n.to <= o.to && (n.from != o.from || n.to != o.to) && o.to - o.from - (n.to - n.from) <= 4 ? n = {
    from: o.from,
    to: o.to,
    insert: i.state.doc.slice(o.from, n.from).append(n.insert).append(i.state.doc.slice(n.to, o.to))
  } : (j.mac || j.android) && n && n.from == n.to && n.from == o.head - 1 && n.insert.toString() == "." && (n = { from: o.from, to: o.to, insert: ct.of([" "]) }), n) {
    let l = i.state;
    if (j.ios && i.inputState.flushIOSKey(i) || j.android && (n.from == o.from && n.to == o.to && n.insert.length == 1 && n.insert.lines == 2 && en(i.contentDOM, "Enter", 13) || n.from == o.from - 1 && n.to == o.to && n.insert.length == 0 && en(i.contentDOM, "Backspace", 8) || n.from == o.from && n.to == o.to + 1 && n.insert.length == 0 && en(i.contentDOM, "Delete", 46)))
      return !0;
    let a = n.insert.toString();
    if (i.state.facet(Ap).some((c) => c(i, n.from, n.to, a)))
      return !0;
    i.inputState.composing >= 0 && i.inputState.composing++;
    let h;
    if (n.from >= o.from && n.to <= o.to && n.to - n.from >= (o.to - o.from) / 3 && (!r || r.main.empty && r.main.from == n.from + n.insert.length) && i.inputState.composing < 0) {
      let c = o.from < n.from ? l.sliceDoc(o.from, n.from) : "", f = o.to > n.to ? l.sliceDoc(n.to, o.to) : "";
      h = l.replaceSelection(i.state.toText(c + n.insert.sliceString(0, void 0, i.state.lineBreak) + f));
    } else {
      let c = l.changes(n), f = r && !l.selection.main.eq(r.main) && r.main.to <= c.newLength ? r.main : void 0;
      if (l.selection.ranges.length > 1 && i.inputState.composing >= 0 && n.to <= o.to && n.to >= o.to - 10) {
        let g = i.state.sliceDoc(n.from, n.to), _ = Ip(i) || i.state.doc.lineAt(o.head), A = o.to - n.to, m = o.to - o.from;
        h = l.changeByRange((p) => {
          if (p.from == o.from && p.to == o.to)
            return { changes: c, range: f || p.map(c) };
          let y = p.to - A, M = y - g.length;
          if (p.to - p.from != m || i.state.sliceDoc(M, y) != g || _ && p.to >= _.from && p.from <= _.to)
            return { range: p };
          let x = l.changes({ from: M, to: y, insert: n.insert }), B = p.to - o.to;
          return {
            changes: x,
            range: f ? R.range(Math.max(0, f.anchor + B), Math.max(0, f.head + B)) : p.map(x)
          };
        });
      } else
        h = {
          changes: c,
          selection: f && l.selection.replaceRange(f)
        };
    }
    let u = "input.type";
    return i.composing && (u += ".compose", i.inputState.compositionFirstChange && (u += ".start", i.inputState.compositionFirstChange = !1)), i.dispatch(h, { scrollIntoView: !0, userEvent: u }), !0;
  } else if (r && !r.main.eq(o)) {
    let l = !1, a = "select";
    return i.inputState.lastSelectionTime > Date.now() - 50 && (i.inputState.lastSelectionOrigin == "select" && (l = !0), a = i.inputState.lastSelectionOrigin), i.dispatch({ selection: r, scrollIntoView: l, userEvent: a }), !0;
  } else
    return !1;
}
function $1(i, t, e, s) {
  let n = Math.min(i.length, t.length), r = 0;
  for (; r < n && i.charCodeAt(r) == t.charCodeAt(r); )
    r++;
  if (r == n && i.length == t.length)
    return null;
  let o = i.length, l = t.length;
  for (; o > 0 && l > 0 && i.charCodeAt(o - 1) == t.charCodeAt(l - 1); )
    o--, l--;
  if (s == "end") {
    let a = Math.max(0, r - Math.min(o, l));
    e -= o + a - r;
  }
  if (o < r && i.length < t.length) {
    let a = e <= r && e >= o ? r - e : 0;
    r -= a, l = r + (l - o), o = r;
  } else if (l < r) {
    let a = e <= r && e >= l ? r - e : 0;
    r -= a, o = r + (o - l), l = r;
  }
  return { from: r, toA: o, toB: l };
}
function F1(i) {
  let t = [];
  if (i.root.activeElement != i.contentDOM)
    return t;
  let { anchorNode: e, anchorOffset: s, focusNode: n, focusOffset: r } = i.observer.selectionRange;
  return e && (t.push(new zu(e, s)), (n != e || r != s) && t.push(new zu(n, r))), t;
}
function V1(i, t) {
  if (i.length == 0)
    return null;
  let e = i[0].pos, s = i.length == 2 ? i[1].pos : e;
  return e > -1 && s > -1 ? R.single(e + t, s + t) : null;
}
class H {
  constructor(t = {}) {
    this.plugins = [], this.pluginMap = /* @__PURE__ */ new Map(), this.editorAttrs = {}, this.contentAttrs = {}, this.bidiCache = [], this.destroyed = !1, this.updateState = 2, this.measureScheduled = -1, this.measureRequests = [], this.contentDOM = document.createElement("div"), this.scrollDOM = document.createElement("div"), this.scrollDOM.tabIndex = -1, this.scrollDOM.className = "cm-scroller", this.scrollDOM.appendChild(this.contentDOM), this.announceDOM = document.createElement("div"), this.announceDOM.style.cssText = "position: absolute; top: -10000px", this.announceDOM.setAttribute("aria-live", "polite"), this.dom = document.createElement("div"), this.dom.appendChild(this.announceDOM), this.dom.appendChild(this.scrollDOM), this._dispatch = t.dispatch || ((e) => this.update([e])), this.dispatch = this.dispatch.bind(this), this.root = t.root || Qy(t.parent) || document, this.viewState = new sc(t.state || at.create(t)), this.plugins = this.state.facet(Vs).map((e) => new jo(e));
    for (let e of this.plugins)
      e.update(this);
    this.observer = new Q1(this, (e, s, n) => W1(this, e, s, n), (e) => {
      this.inputState.runScrollHandlers(this, e), this.observer.intersecting && this.measure();
    }), this.inputState = new f1(this), this.inputState.ensureHandlers(this, this.plugins), this.docView = new Wu(this), this.mountStyles(), this.updateAttrs(), this.updateState = 0, this.requestMeasure(), t.parent && t.parent.appendChild(this.dom);
  }
  get state() {
    return this.viewState.state;
  }
  get viewport() {
    return this.viewState.viewport;
  }
  get visibleRanges() {
    return this.viewState.visibleRanges;
  }
  get inView() {
    return this.viewState.inView;
  }
  get composing() {
    return this.inputState.composing > 0;
  }
  get compositionStarted() {
    return this.inputState.composing >= 0;
  }
  dispatch(...t) {
    this._dispatch(t.length == 1 && t[0] instanceof Et ? t[0] : this.state.update(...t));
  }
  update(t) {
    if (this.updateState != 0)
      throw new Error("Calls to EditorView.update are not allowed while an update is in progress");
    let e = !1, s = !1, n, r = this.state;
    for (let l of t) {
      if (l.startState != r)
        throw new RangeError("Trying to update state with a transaction that doesn't start from the previous state.");
      r = l.state;
    }
    if (this.destroyed) {
      this.viewState.state = r;
      return;
    }
    if (this.observer.clear(), r.facet(at.phrases) != this.state.facet(at.phrases))
      return this.setState(r);
    n = Br.create(this, r, t);
    let o = this.viewState.scrollTarget;
    try {
      this.updateState = 2;
      for (let l of t) {
        if (o && (o = o.map(l.changes)), l.scrollIntoView) {
          let { main: a } = l.state.selection;
          o = new Dr(a.empty ? a : R.cursor(a.head, a.head > a.anchor ? -1 : 1));
        }
        for (let a of l.effects)
          a.is(Iu) && (o = a.value);
      }
      this.viewState.update(n, o), this.bidiCache = Nr.update(this.bidiCache, n.changes), n.empty || (this.updatePlugins(n), this.inputState.update(n)), e = this.docView.update(n), this.state.facet(Us) != this.styleModules && this.mountStyles(), s = this.updateAttrs(), this.showAnnouncements(t), this.docView.updateSelection(e, t.some((l) => l.isUserEvent("select.pointer")));
    } finally {
      this.updateState = 0;
    }
    if (n.startState.facet(Fn) != n.state.facet(Fn) && (this.viewState.mustMeasureContent = !0), (e || s || o || this.viewState.mustEnforceCursorAssoc || this.viewState.mustMeasureContent) && this.requestMeasure(), !n.empty)
      for (let l of this.state.facet(ma))
        l(n);
  }
  setState(t) {
    if (this.updateState != 0)
      throw new Error("Calls to EditorView.setState are not allowed while an update is in progress");
    if (this.destroyed) {
      this.viewState.state = t;
      return;
    }
    this.updateState = 2;
    let e = this.hasFocus;
    try {
      for (let s of this.plugins)
        s.destroy(this);
      this.viewState = new sc(t), this.plugins = t.facet(Vs).map((s) => new jo(s)), this.pluginMap.clear();
      for (let s of this.plugins)
        s.update(this);
      this.docView = new Wu(this), this.inputState.ensureHandlers(this, this.plugins), this.mountStyles(), this.updateAttrs(), this.bidiCache = [];
    } finally {
      this.updateState = 0;
    }
    e && this.focus(), this.requestMeasure();
  }
  updatePlugins(t) {
    let e = t.startState.facet(Vs), s = t.state.facet(Vs);
    if (e != s) {
      let n = [];
      for (let r of s) {
        let o = e.indexOf(r);
        if (o < 0)
          n.push(new jo(r));
        else {
          let l = this.plugins[o];
          l.mustUpdate = t, n.push(l);
        }
      }
      for (let r of this.plugins)
        r.mustUpdate != t && r.destroy(this);
      this.plugins = n, this.pluginMap.clear(), this.inputState.ensureHandlers(this, this.plugins);
    } else
      for (let n of this.plugins)
        n.mustUpdate = t;
    for (let n = 0; n < this.plugins.length; n++)
      this.plugins[n].update(this);
  }
  measure(t = !0) {
    if (this.destroyed)
      return;
    this.measureScheduled > -1 && cancelAnimationFrame(this.measureScheduled), this.measureScheduled = 0, t && this.observer.forceFlush();
    let e = null;
    try {
      for (let s = 0; ; s++) {
        this.updateState = 1;
        let n = this.viewport, r = this.viewState.measure(this);
        if (!r && !this.measureRequests.length && this.viewState.scrollTarget == null)
          break;
        if (s > 5) {
          console.warn(this.measureRequests.length ? "Measure loop restarted more than 5 times" : "Viewport failed to stabilize");
          break;
        }
        let o = [];
        r & 4 || ([this.measureRequests, o] = [o, this.measureRequests]);
        let l = o.map((c) => {
          try {
            return c.read(this);
          } catch (f) {
            return ge(this.state, f), ac;
          }
        }), a = Br.create(this, this.state, []), h = !1, u = !1;
        a.flags |= r, e ? e.flags |= r : e = a, this.updateState = 2, a.empty || (this.updatePlugins(a), this.inputState.update(a), this.updateAttrs(), h = this.docView.update(a));
        for (let c = 0; c < o.length; c++)
          if (l[c] != ac)
            try {
              let f = o[c];
              f.write && f.write(l[c], this);
            } catch (f) {
              ge(this.state, f);
            }
        if (this.viewState.scrollTarget && (this.docView.scrollIntoView(this.viewState.scrollTarget), this.viewState.scrollTarget = null, u = !0), h && this.docView.updateSelection(!0), this.viewport.from == n.from && this.viewport.to == n.to && !u && this.measureRequests.length == 0)
          break;
      }
    } finally {
      this.updateState = 0, this.measureScheduled = -1;
    }
    if (e && !e.empty)
      for (let s of this.state.facet(ma))
        s(e);
  }
  get themeClasses() {
    return ka + " " + (this.state.facet(xa) ? Xp : Kp) + " " + this.state.facet(Fn);
  }
  updateAttrs() {
    let t = hc(this, Pp, {
      class: "cm-editor" + (this.hasFocus ? " cm-focused " : " ") + this.themeClasses
    }), e = {
      spellcheck: "false",
      autocorrect: "off",
      autocapitalize: "off",
      translate: "no",
      contenteditable: this.state.facet(wo) ? "true" : "false",
      class: "cm-content",
      style: `${j.tabSize}: ${this.state.tabSize}`,
      role: "textbox",
      "aria-multiline": "true"
    };
    this.state.readOnly && (e["aria-readonly"] = "true"), hc(this, Ep, e);
    let s = this.observer.ignore(() => {
      let n = pa(this.contentDOM, this.contentAttrs, e), r = pa(this.dom, this.editorAttrs, t);
      return n || r;
    });
    return this.editorAttrs = t, this.contentAttrs = e, s;
  }
  showAnnouncements(t) {
    let e = !0;
    for (let s of t)
      for (let n of s.effects)
        if (n.is(H.announce)) {
          e && (this.announceDOM.textContent = ""), e = !1;
          let r = this.announceDOM.appendChild(document.createElement("div"));
          r.textContent = n.value;
        }
  }
  mountStyles() {
    this.styleModules = this.state.facet(Us), Si.mount(this.root, this.styleModules.concat(L1).reverse());
  }
  readMeasured() {
    if (this.updateState == 2)
      throw new Error("Reading the editor layout isn't allowed during an update");
    this.updateState == 0 && this.measureScheduled > -1 && this.measure(!1);
  }
  requestMeasure(t) {
    if (this.measureScheduled < 0 && (this.measureScheduled = requestAnimationFrame(() => this.measure())), t) {
      if (t.key != null) {
        for (let e = 0; e < this.measureRequests.length; e++)
          if (this.measureRequests[e].key === t.key) {
            this.measureRequests[e] = t;
            return;
          }
      }
      this.measureRequests.push(t);
    }
  }
  plugin(t) {
    let e = this.pluginMap.get(t);
    return (e === void 0 || e && e.spec != t) && this.pluginMap.set(t, e = this.plugins.find((s) => s.spec == t) || null), e && e.update(this).value;
  }
  get documentTop() {
    return this.contentDOM.getBoundingClientRect().top + this.viewState.paddingTop;
  }
  get documentPadding() {
    return { top: this.viewState.paddingTop, bottom: this.viewState.paddingBottom };
  }
  elementAtHeight(t) {
    return this.readMeasured(), this.viewState.elementAtHeight(t);
  }
  lineBlockAtHeight(t) {
    return this.readMeasured(), this.viewState.lineBlockAtHeight(t);
  }
  get viewportLineBlocks() {
    return this.viewState.viewportLines;
  }
  lineBlockAt(t) {
    return this.viewState.lineBlockAt(t);
  }
  get contentHeight() {
    return this.viewState.contentHeight;
  }
  moveByChar(t, e, s) {
    return qo(this, t, Hu(this, t, e, s));
  }
  moveByGroup(t, e) {
    return qo(this, t, Hu(this, t, e, (s) => u1(this, t.head, s)));
  }
  moveToLineBoundary(t, e, s = !0) {
    return h1(this, t, e, s);
  }
  moveVertically(t, e, s) {
    return qo(this, t, c1(this, t, e, s));
  }
  domAtPos(t) {
    return this.docView.domAtPos(t);
  }
  posAtDOM(t, e = 0) {
    return this.docView.posFromDOM(t, e);
  }
  posAtCoords(t, e = !0) {
    return this.readMeasured(), zp(this, t, e);
  }
  coordsAtPos(t, e = 1) {
    this.readMeasured();
    let s = this.docView.coordsAt(t, e);
    if (!s || s.left == s.right)
      return s;
    let n = this.state.doc.lineAt(t), r = this.bidiSpans(n), o = r[fs.find(r, t - n.from, -1, e)];
    return _o(s, o.dir == St.LTR == e > 0);
  }
  get defaultCharacterWidth() {
    return this.viewState.heightOracle.charWidth;
  }
  get defaultLineHeight() {
    return this.viewState.heightOracle.lineHeight;
  }
  get textDirection() {
    return this.viewState.defaultTextDirection;
  }
  textDirectionAt(t) {
    return !this.state.facet(Tp) || t < this.viewport.from || t > this.viewport.to ? this.textDirection : (this.readMeasured(), this.docView.textDirectionAt(t));
  }
  get lineWrapping() {
    return this.viewState.heightOracle.lineWrapping;
  }
  bidiSpans(t) {
    if (t.length > U1)
      return Bp(t.length);
    let e = this.textDirectionAt(t.from);
    for (let n of this.bidiCache)
      if (n.from == t.from && n.dir == e)
        return n.order;
    let s = Xy(t.text, e);
    return this.bidiCache.push(new Nr(t.from, t.to, e, s)), s;
  }
  get hasFocus() {
    var t;
    return (document.hasFocus() || j.safari && ((t = this.inputState) === null || t === void 0 ? void 0 : t.lastContextMenu) > Date.now() - 3e4) && this.root.activeElement == this.contentDOM;
  }
  focus() {
    this.observer.ignore(() => {
      up(this.contentDOM), this.docView.updateSelection();
    });
  }
  destroy() {
    for (let t of this.plugins)
      t.destroy(this);
    this.plugins = [], this.inputState.destroy(), this.dom.remove(), this.observer.destroy(), this.measureScheduled > -1 && cancelAnimationFrame(this.measureScheduled), this.destroyed = !0;
  }
  static scrollIntoView(t, e = {}) {
    return Iu.of(new Dr(typeof t == "number" ? R.cursor(t) : t, e.y, e.x, e.yMargin, e.xMargin));
  }
  static domEventHandlers(t) {
    return At.define(() => ({}), { eventHandlers: t });
  }
  static theme(t, e) {
    let s = Si.newName(), n = [Fn.of(s), Us.of(Oa(`.${s}`, t))];
    return e && e.dark && n.push(xa.of(!0)), n;
  }
  static baseTheme(t) {
    return As.lowest(Us.of(Oa("." + ka, t, Gp)));
  }
  static findFromDOM(t) {
    var e;
    let s = t.querySelector(".cm-content"), n = s && kt.get(s) || kt.get(t);
    return ((e = n == null ? void 0 : n.rootView) === null || e === void 0 ? void 0 : e.view) || null;
  }
}
H.styleModule = Us;
H.inputHandler = Ap;
H.perLineTextDirection = Tp;
H.exceptionSink = Cp;
H.updateListener = ma;
H.editable = wo;
H.mouseSelectionStyle = Sp;
H.dragMovesSelection = Op;
H.clickAddsSelectionRange = kp;
H.decorations = _n;
H.atomicRanges = Mp;
H.scrollMargins = Rp;
H.darkTheme = xa;
H.contentAttributes = Ep;
H.editorAttributes = Pp;
H.lineWrapping = /* @__PURE__ */ H.contentAttributes.of({ class: "cm-lineWrapping" });
H.announce = /* @__PURE__ */ rt.define();
const U1 = 4096, ac = {};
class Nr {
  constructor(t, e, s, n) {
    this.from = t, this.to = e, this.dir = s, this.order = n;
  }
  static update(t, e) {
    if (e.empty)
      return t;
    let s = [], n = t.length ? t[t.length - 1].dir : St.LTR;
    for (let r = Math.max(0, t.length - 10); r < t.length; r++) {
      let o = t[r];
      o.dir == n && !e.touchesRange(o.from, o.to) && s.push(new Nr(e.mapPos(o.from, 1), e.mapPos(o.to, -1), o.dir, o.order));
    }
    return s;
  }
}
function hc(i, t, e) {
  for (let s = i.state.facet(t), n = s.length - 1; n >= 0; n--) {
    let r = s[n], o = typeof r == "function" ? r(i) : r;
    o && da(o, e);
  }
  return e;
}
const j1 = j.mac ? "mac" : j.windows ? "win" : j.linux ? "linux" : "key";
function H1(i, t) {
  const e = i.split(/-(?!$)/);
  let s = e[e.length - 1];
  s == "Space" && (s = " ");
  let n, r, o, l;
  for (let a = 0; a < e.length - 1; ++a) {
    const h = e[a];
    if (/^(cmd|meta|m)$/i.test(h))
      l = !0;
    else if (/^a(lt)?$/i.test(h))
      n = !0;
    else if (/^(c|ctrl|control)$/i.test(h))
      r = !0;
    else if (/^s(hift)?$/i.test(h))
      o = !0;
    else if (/^mod$/i.test(h))
      t == "mac" ? l = !0 : r = !0;
    else
      throw new Error("Unrecognized modifier name: " + h);
  }
  return n && (s = "Alt-" + s), r && (s = "Ctrl-" + s), l && (s = "Meta-" + s), o && (s = "Shift-" + s), s;
}
function Vn(i, t, e) {
  return t.altKey && (i = "Alt-" + i), t.ctrlKey && (i = "Ctrl-" + i), t.metaKey && (i = "Meta-" + i), e !== !1 && t.shiftKey && (i = "Shift-" + i), i;
}
const q1 = /* @__PURE__ */ As.default(/* @__PURE__ */ H.domEventHandlers({
  keydown(i, t) {
    return Yp(Jp(t.state), i, t, "editor");
  }
})), vo = /* @__PURE__ */ q.define({ enables: q1 }), uc = /* @__PURE__ */ new WeakMap();
function Jp(i) {
  let t = i.facet(vo), e = uc.get(t);
  return e || uc.set(t, e = G1(t.reduce((s, n) => s.concat(n), []))), e;
}
function K1(i, t, e) {
  return Yp(Jp(i.state), t, i, e);
}
let fi = null;
const X1 = 4e3;
function G1(i, t = j1) {
  let e = /* @__PURE__ */ Object.create(null), s = /* @__PURE__ */ Object.create(null), n = (o, l) => {
    let a = s[o];
    if (a == null)
      s[o] = l;
    else if (a != l)
      throw new Error("Key binding " + o + " is used both as a regular binding and as a multi-stroke prefix");
  }, r = (o, l, a, h) => {
    let u = e[o] || (e[o] = /* @__PURE__ */ Object.create(null)), c = l.split(/ (?!$)/).map((_) => H1(_, t));
    for (let _ = 1; _ < c.length; _++) {
      let A = c.slice(0, _).join(" ");
      n(A, !0), u[A] || (u[A] = {
        preventDefault: !0,
        commands: [(m) => {
          let p = fi = { view: m, prefix: A, scope: o };
          return setTimeout(() => {
            fi == p && (fi = null);
          }, X1), !0;
        }]
      });
    }
    let f = c.join(" ");
    n(f, !1);
    let g = u[f] || (u[f] = { preventDefault: !1, commands: [] });
    g.commands.push(a), h && (g.preventDefault = !0);
  };
  for (let o of i) {
    let l = o[t] || o.key;
    if (!!l)
      for (let a of o.scope ? o.scope.split(" ") : ["editor"])
        r(a, l, o.run, o.preventDefault), o.shift && r(a, "Shift-" + l, o.shift, o.preventDefault);
  }
  return e;
}
function Yp(i, t, e, s) {
  let n = Dy(t), r = It(n, 0), o = ue(r) == n.length && n != " ", l = "", a = !1;
  fi && fi.view == e && fi.scope == s && (l = fi.prefix + " ", (a = $p.indexOf(t.keyCode) < 0) && (fi = null));
  let h = (f) => {
    if (f) {
      for (let g of f.commands)
        if (g(e))
          return !0;
      f.preventDefault && (a = !0);
    }
    return !1;
  }, u = i[s], c;
  if (u) {
    if (h(u[l + Vn(n, t, !o)]))
      return !0;
    if (o && (t.shiftKey || t.altKey || t.metaKey || r > 127) && (c = Ci[t.keyCode]) && c != n) {
      if (h(u[l + Vn(c, t, !0)]))
        return !0;
      if (t.shiftKey && ms[t.keyCode] != c && h(u[l + Vn(ms[t.keyCode], t, !1)]))
        return !0;
    } else if (o && t.shiftKey && h(u[l + Vn(n, t, !0)]))
      return !0;
  }
  return a;
}
const Zp = !j.ios, Hs = /* @__PURE__ */ q.define({
  combine(i) {
    return si(i, {
      cursorBlinkRate: 1200,
      drawRangeCursor: !0
    }, {
      cursorBlinkRate: (t, e) => Math.min(t, e),
      drawRangeCursor: (t, e) => t || e
    });
  }
});
function J1(i = {}) {
  return [
    Hs.of(i),
    Y1,
    Z1
  ];
}
class tg {
  constructor(t, e, s, n, r) {
    this.left = t, this.top = e, this.width = s, this.height = n, this.className = r;
  }
  draw() {
    let t = document.createElement("div");
    return t.className = this.className, this.adjust(t), t;
  }
  adjust(t) {
    t.style.left = this.left + "px", t.style.top = this.top + "px", this.width >= 0 && (t.style.width = this.width + "px"), t.style.height = this.height + "px";
  }
  eq(t) {
    return this.left == t.left && this.top == t.top && this.width == t.width && this.height == t.height && this.className == t.className;
  }
}
const Y1 = /* @__PURE__ */ At.fromClass(class {
  constructor(i) {
    this.view = i, this.rangePieces = [], this.cursors = [], this.measureReq = { read: this.readPos.bind(this), write: this.drawSel.bind(this) }, this.selectionLayer = i.scrollDOM.appendChild(document.createElement("div")), this.selectionLayer.className = "cm-selectionLayer", this.selectionLayer.setAttribute("aria-hidden", "true"), this.cursorLayer = i.scrollDOM.appendChild(document.createElement("div")), this.cursorLayer.className = "cm-cursorLayer", this.cursorLayer.setAttribute("aria-hidden", "true"), i.requestMeasure(this.measureReq), this.setBlinkRate();
  }
  setBlinkRate() {
    this.cursorLayer.style.animationDuration = this.view.state.facet(Hs).cursorBlinkRate + "ms";
  }
  update(i) {
    let t = i.startState.facet(Hs) != i.state.facet(Hs);
    (t || i.selectionSet || i.geometryChanged || i.viewportChanged) && this.view.requestMeasure(this.measureReq), i.transactions.some((e) => e.scrollIntoView) && (this.cursorLayer.style.animationName = this.cursorLayer.style.animationName == "cm-blink" ? "cm-blink2" : "cm-blink"), t && this.setBlinkRate();
  }
  readPos() {
    let { state: i } = this.view, t = i.facet(Hs), e = i.selection.ranges.map((n) => n.empty ? [] : tw(this.view, n)).reduce((n, r) => n.concat(r)), s = [];
    for (let n of i.selection.ranges) {
      let r = n == i.selection.main;
      if (n.empty ? !r || Zp : t.drawRangeCursor) {
        let o = ew(this.view, n, r);
        o && s.push(o);
      }
    }
    return { rangePieces: e, cursors: s };
  }
  drawSel({ rangePieces: i, cursors: t }) {
    if (i.length != this.rangePieces.length || i.some((e, s) => !e.eq(this.rangePieces[s]))) {
      this.selectionLayer.textContent = "";
      for (let e of i)
        this.selectionLayer.appendChild(e.draw());
      this.rangePieces = i;
    }
    if (t.length != this.cursors.length || t.some((e, s) => !e.eq(this.cursors[s]))) {
      let e = this.cursorLayer.children;
      if (e.length !== t.length) {
        this.cursorLayer.textContent = "";
        for (const s of t)
          this.cursorLayer.appendChild(s.draw());
      } else
        t.forEach((s, n) => s.adjust(e[n]));
      this.cursors = t;
    }
  }
  destroy() {
    this.selectionLayer.remove(), this.cursorLayer.remove();
  }
}), eg = {
  ".cm-line": {
    "& ::selection": { backgroundColor: "transparent !important" },
    "&::selection": { backgroundColor: "transparent !important" }
  }
};
Zp && (eg[".cm-line"].caretColor = "transparent !important");
const Z1 = /* @__PURE__ */ As.highest(/* @__PURE__ */ H.theme(eg));
function ig(i) {
  let t = i.scrollDOM.getBoundingClientRect();
  return { left: (i.textDirection == St.LTR ? t.left : t.right - i.scrollDOM.clientWidth) - i.scrollDOM.scrollLeft, top: t.top - i.scrollDOM.scrollTop };
}
function cc(i, t, e) {
  let s = R.cursor(t);
  return {
    from: Math.max(e.from, i.moveToLineBoundary(s, !1, !0).from),
    to: Math.min(e.to, i.moveToLineBoundary(s, !0, !0).from),
    type: _t.Text
  };
}
function fc(i, t) {
  let e = i.lineBlockAt(t);
  if (Array.isArray(e.type)) {
    for (let s of e.type)
      if (s.to > t || s.to == t && (s.to == e.to || s.type == _t.Text))
        return s;
  }
  return e;
}
function tw(i, t) {
  if (t.to <= i.viewport.from || t.from >= i.viewport.to)
    return [];
  let e = Math.max(t.from, i.viewport.from), s = Math.min(t.to, i.viewport.to), n = i.textDirection == St.LTR, r = i.contentDOM, o = r.getBoundingClientRect(), l = ig(i), a = window.getComputedStyle(r.firstChild), h = o.left + parseInt(a.paddingLeft) + Math.min(0, parseInt(a.textIndent)), u = o.right - parseInt(a.paddingRight), c = fc(i, e), f = fc(i, s), g = c.type == _t.Text ? c : null, _ = f.type == _t.Text ? f : null;
  if (i.lineWrapping && (g && (g = cc(i, e, g)), _ && (_ = cc(i, s, _))), g && _ && g.from == _.from)
    return m(p(t.from, t.to, g));
  {
    let M = g ? p(t.from, null, g) : y(c, !1), x = _ ? p(null, t.to, _) : y(f, !0), B = [];
    return (g || c).to < (_ || f).from - 1 ? B.push(A(h, M.bottom, u, x.top)) : M.bottom < x.top && i.elementAtHeight((M.bottom + x.top) / 2).type == _t.Text && (M.bottom = x.top = (M.bottom + x.top) / 2), m(M).concat(B).concat(m(x));
  }
  function A(M, x, B, v) {
    return new tg(M - l.left, x - l.top - 0.01, B - M, v - x + 0.01, "cm-selectionBackground");
  }
  function m({ top: M, bottom: x, horizontal: B }) {
    let v = [];
    for (let C = 0; C < B.length; C += 2)
      v.push(A(B[C], M, B[C + 1], x));
    return v;
  }
  function p(M, x, B) {
    let v = 1e9, C = -1e9, k = [];
    function E(T, $, K, it, Y) {
      let et = i.coordsAtPos(T, T == B.to ? -2 : 2), Z = i.coordsAtPos(K, K == B.from ? 2 : -2);
      v = Math.min(et.top, Z.top, v), C = Math.max(et.bottom, Z.bottom, C), Y == St.LTR ? k.push(n && $ ? h : et.left, n && it ? u : Z.right) : k.push(!n && it ? h : Z.left, !n && $ ? u : et.right);
    }
    let d = M != null ? M : B.from, S = x != null ? x : B.to;
    for (let T of i.visibleRanges)
      if (T.to > d && T.from < S)
        for (let $ = Math.max(T.from, d), K = Math.min(T.to, S); ; ) {
          let it = i.state.doc.lineAt($);
          for (let Y of i.bidiSpans(it)) {
            let et = Y.from + it.from, Z = Y.to + it.from;
            if (et >= K)
              break;
            Z > $ && E(Math.max(et, $), M == null && et <= d, Math.min(Z, K), x == null && Z >= S, Y.dir);
          }
          if ($ = it.to + 1, $ >= K)
            break;
        }
    return k.length == 0 && E(d, M == null, S, x == null, i.textDirection), { top: v, bottom: C, horizontal: k };
  }
  function y(M, x) {
    let B = o.top + (x ? M.top : M.bottom);
    return { top: B, bottom: B, horizontal: [] };
  }
}
function ew(i, t, e) {
  let s = i.coordsAtPos(t.head, t.assoc || 1);
  if (!s)
    return null;
  let n = ig(i);
  return new tg(s.left - n.left, s.top - n.top, -1, s.bottom - s.top, e ? "cm-cursor cm-cursor-primary" : "cm-cursor cm-cursor-secondary");
}
const sg = /* @__PURE__ */ rt.define({
  map(i, t) {
    return i == null ? null : t.mapPos(i);
  }
}), qs = /* @__PURE__ */ Vt.define({
  create() {
    return null;
  },
  update(i, t) {
    return i != null && (i = t.changes.mapPos(i)), t.effects.reduce((e, s) => s.is(sg) ? s.value : e, i);
  }
}), iw = /* @__PURE__ */ At.fromClass(class {
  constructor(i) {
    this.view = i, this.cursor = null, this.measureReq = { read: this.readPos.bind(this), write: this.drawCursor.bind(this) };
  }
  update(i) {
    var t;
    let e = i.state.field(qs);
    e == null ? this.cursor != null && ((t = this.cursor) === null || t === void 0 || t.remove(), this.cursor = null) : (this.cursor || (this.cursor = this.view.scrollDOM.appendChild(document.createElement("div")), this.cursor.className = "cm-dropCursor"), (i.startState.field(qs) != e || i.docChanged || i.geometryChanged) && this.view.requestMeasure(this.measureReq));
  }
  readPos() {
    let i = this.view.state.field(qs), t = i != null && this.view.coordsAtPos(i);
    if (!t)
      return null;
    let e = this.view.scrollDOM.getBoundingClientRect();
    return {
      left: t.left - e.left + this.view.scrollDOM.scrollLeft,
      top: t.top - e.top + this.view.scrollDOM.scrollTop,
      height: t.bottom - t.top
    };
  }
  drawCursor(i) {
    this.cursor && (i ? (this.cursor.style.left = i.left + "px", this.cursor.style.top = i.top + "px", this.cursor.style.height = i.height + "px") : this.cursor.style.left = "-100000px");
  }
  destroy() {
    this.cursor && this.cursor.remove();
  }
  setDropPos(i) {
    this.view.state.field(qs) != i && this.view.dispatch({ effects: sg.of(i) });
  }
}, {
  eventHandlers: {
    dragover(i) {
      this.setDropPos(this.view.posAtCoords({ x: i.clientX, y: i.clientY }));
    },
    dragleave(i) {
      (i.target == this.view.contentDOM || !this.view.contentDOM.contains(i.relatedTarget)) && this.setDropPos(null);
    },
    dragend() {
      this.setDropPos(null);
    },
    drop() {
      this.setDropPos(null);
    }
  }
});
function sw() {
  return [qs, iw];
}
function dc(i, t, e, s, n) {
  t.lastIndex = 0;
  for (let r = i.iterRange(e, s), o = e, l; !r.next().done; o += r.value.length)
    if (!r.lineBreak)
      for (; l = t.exec(r.value); )
        n(o + l.index, l);
}
function nw(i, t) {
  let e = i.visibleRanges;
  if (e.length == 1 && e[0].from == i.viewport.from && e[0].to == i.viewport.to)
    return e;
  let s = [];
  for (let { from: n, to: r } of e)
    n = Math.max(i.state.doc.lineAt(n).from, n - t), r = Math.min(i.state.doc.lineAt(r).to, r + t), s.length && s[s.length - 1].to >= n ? s[s.length - 1].to = r : s.push({ from: n, to: r });
  return s;
}
class rw {
  constructor(t) {
    const { regexp: e, decoration: s, decorate: n, boundary: r, maxLength: o = 1e3 } = t;
    if (!e.global)
      throw new RangeError("The regular expression given to MatchDecorator should have its 'g' flag set");
    if (this.regexp = e, n)
      this.addMatch = (l, a, h, u) => n(u, h, h + l[0].length, l, a);
    else if (s) {
      let l = typeof s == "function" ? s : () => s;
      this.addMatch = (a, h, u, c) => c(u, u + a[0].length, l(a, h, u));
    } else
      throw new RangeError("Either 'decorate' or 'decoration' should be provided to MatchDecorator");
    this.boundary = r, this.maxLength = o;
  }
  createDeco(t) {
    let e = new Oi(), s = e.add.bind(e);
    for (let { from: n, to: r } of nw(t, this.maxLength))
      dc(t.state.doc, this.regexp, n, r, (o, l) => this.addMatch(l, t, o, s));
    return e.finish();
  }
  updateDeco(t, e) {
    let s = 1e9, n = -1;
    return t.docChanged && t.changes.iterChanges((r, o, l, a) => {
      a > t.view.viewport.from && l < t.view.viewport.to && (s = Math.min(l, s), n = Math.max(a, n));
    }), t.viewportChanged || n - s > 1e3 ? this.createDeco(t.view) : n > -1 ? this.updateRange(t.view, e.map(t.changes), s, n) : e;
  }
  updateRange(t, e, s, n) {
    for (let r of t.visibleRanges) {
      let o = Math.max(r.from, s), l = Math.min(r.to, n);
      if (l > o) {
        let a = t.state.doc.lineAt(o), h = a.to < l ? t.state.doc.lineAt(l) : a, u = Math.max(r.from, a.from), c = Math.min(r.to, h.to);
        if (this.boundary) {
          for (; o > a.from; o--)
            if (this.boundary.test(a.text[o - 1 - a.from])) {
              u = o;
              break;
            }
          for (; l < h.to; l++)
            if (this.boundary.test(h.text[l - h.from])) {
              c = l;
              break;
            }
        }
        let f = [], g, _ = (A, m, p) => f.push(p.range(A, m));
        if (a == h)
          for (this.regexp.lastIndex = u - a.from; (g = this.regexp.exec(a.text)) && g.index < c - a.from; )
            this.addMatch(g, t, g.index + a.from, _);
        else
          dc(t.state.doc, this.regexp, u, c, (A, m) => this.addMatch(m, t, A, _));
        e = e.update({ filterFrom: u, filterTo: c, filter: (A, m) => A < u || m > c, add: f });
      }
    }
    return e;
  }
}
const Sa = /x/.unicode != null ? "gu" : "g", ow = /* @__PURE__ */ new RegExp(`[\0-\b
-\x7F-\x9F\xAD\u061C\u200B\u200E\u200F\u2028\u2029\u202D\u202E\u2066\u2067\u2069\uFEFF\uFFF9-\uFFFC]`, Sa), lw = {
  0: "null",
  7: "bell",
  8: "backspace",
  10: "newline",
  11: "vertical tab",
  13: "carriage return",
  27: "escape",
  8203: "zero width space",
  8204: "zero width non-joiner",
  8205: "zero width joiner",
  8206: "left-to-right mark",
  8207: "right-to-left mark",
  8232: "line separator",
  8237: "left-to-right override",
  8238: "right-to-left override",
  8294: "left-to-right isolate",
  8295: "right-to-left isolate",
  8297: "pop directional isolate",
  8233: "paragraph separator",
  65279: "zero width no-break space",
  65532: "object replacement"
};
let Go = null;
function aw() {
  var i;
  if (Go == null && typeof document != "undefined" && document.body) {
    let t = document.body.style;
    Go = ((i = t.tabSize) !== null && i !== void 0 ? i : t.MozTabSize) != null;
  }
  return Go || !1;
}
const gr = /* @__PURE__ */ q.define({
  combine(i) {
    let t = si(i, {
      render: null,
      specialChars: ow,
      addSpecialChars: null
    });
    return (t.replaceTabs = !aw()) && (t.specialChars = new RegExp("	|" + t.specialChars.source, Sa)), t.addSpecialChars && (t.specialChars = new RegExp(t.specialChars.source + "|" + t.addSpecialChars.source, Sa)), t;
  }
});
function hw(i = {}) {
  return [gr.of(i), uw()];
}
let pc = null;
function uw() {
  return pc || (pc = At.fromClass(class {
    constructor(i) {
      this.view = i, this.decorations = X.none, this.decorationCache = /* @__PURE__ */ Object.create(null), this.decorator = this.makeDecorator(i.state.facet(gr)), this.decorations = this.decorator.createDeco(i);
    }
    makeDecorator(i) {
      return new rw({
        regexp: i.specialChars,
        decoration: (t, e, s) => {
          let { doc: n } = e.state, r = It(t[0], 0);
          if (r == 9) {
            let o = n.lineAt(s), l = e.state.tabSize, a = Sn(o.text, l, s - o.from);
            return X.replace({ widget: new pw((l - a % l) * this.view.defaultCharacterWidth) });
          }
          return this.decorationCache[r] || (this.decorationCache[r] = X.replace({ widget: new dw(i, r) }));
        },
        boundary: i.replaceTabs ? void 0 : /[^]/
      });
    }
    update(i) {
      let t = i.state.facet(gr);
      i.startState.facet(gr) != t ? (this.decorator = this.makeDecorator(t), this.decorations = this.decorator.createDeco(i.view)) : this.decorations = this.decorator.updateDeco(i, this.decorations);
    }
  }, {
    decorations: (i) => i.decorations
  }));
}
const cw = "\u2022";
function fw(i) {
  return i >= 32 ? cw : i == 10 ? "\u2424" : String.fromCharCode(9216 + i);
}
class dw extends ni {
  constructor(t, e) {
    super(), this.options = t, this.code = e;
  }
  eq(t) {
    return t.code == this.code;
  }
  toDOM(t) {
    let e = fw(this.code), s = t.state.phrase("Control character") + " " + (lw[this.code] || "0x" + this.code.toString(16)), n = this.options.render && this.options.render(this.code, s, e);
    if (n)
      return n;
    let r = document.createElement("span");
    return r.textContent = e, r.title = s, r.setAttribute("aria-label", s), r.className = "cm-specialChar", r;
  }
  ignoreEvent() {
    return !1;
  }
}
class pw extends ni {
  constructor(t) {
    super(), this.width = t;
  }
  eq(t) {
    return t.width == this.width;
  }
  toDOM() {
    let t = document.createElement("span");
    return t.textContent = "	", t.className = "cm-tab", t.style.width = this.width + "px", t;
  }
  ignoreEvent() {
    return !1;
  }
}
function gw() {
  return _w;
}
const mw = /* @__PURE__ */ X.line({ class: "cm-activeLine" }), _w = /* @__PURE__ */ At.fromClass(class {
  constructor(i) {
    this.decorations = this.getDeco(i);
  }
  update(i) {
    (i.docChanged || i.selectionSet) && (this.decorations = this.getDeco(i.view));
  }
  getDeco(i) {
    let t = -1, e = [];
    for (let s of i.state.selection.ranges) {
      if (!s.empty)
        return X.none;
      let n = i.lineBlockAt(s.head);
      n.from > t && (e.push(mw.range(n.from)), t = n.from);
    }
    return X.set(e);
  }
}, {
  decorations: (i) => i.decorations
});
class bw extends ni {
  constructor(t) {
    super(), this.content = t;
  }
  toDOM() {
    let t = document.createElement("span");
    return t.className = "cm-placeholder", t.style.pointerEvents = "none", t.appendChild(typeof this.content == "string" ? document.createTextNode(this.content) : this.content), typeof this.content == "string" ? t.setAttribute("aria-label", "placeholder " + this.content) : t.setAttribute("aria-hidden", "true"), t;
  }
  ignoreEvent() {
    return !1;
  }
}
function yw(i) {
  return At.fromClass(class {
    constructor(t) {
      this.view = t, this.placeholder = X.set([X.widget({ widget: new bw(i), side: 1 }).range(0)]);
    }
    get decorations() {
      return this.view.state.doc.length ? X.none : this.placeholder;
    }
  }, { decorations: (t) => t.decorations });
}
const Ca = 2e3;
function ww(i, t, e) {
  let s = Math.min(t.line, e.line), n = Math.max(t.line, e.line), r = [];
  if (t.off > Ca || e.off > Ca || t.col < 0 || e.col < 0) {
    let o = Math.min(t.off, e.off), l = Math.max(t.off, e.off);
    for (let a = s; a <= n; a++) {
      let h = i.doc.line(a);
      h.length <= l && r.push(R.range(h.from + o, h.to + l));
    }
  } else {
    let o = Math.min(t.col, e.col), l = Math.max(t.col, e.col);
    for (let a = s; a <= n; a++) {
      let h = i.doc.line(a), u = ra(h.text, o, i.tabSize, !0);
      if (u > -1) {
        let c = ra(h.text, l, i.tabSize);
        r.push(R.range(h.from + u, h.from + c));
      }
    }
  }
  return r;
}
function vw(i, t) {
  let e = i.coordsAtPos(i.viewport.from);
  return e ? Math.round(Math.abs((e.left - t) / i.defaultCharacterWidth)) : -1;
}
function gc(i, t) {
  let e = i.posAtCoords({ x: t.clientX, y: t.clientY }, !1), s = i.state.doc.lineAt(e), n = e - s.from, r = n > Ca ? -1 : n == s.length ? vw(i, t.clientX) : Sn(s.text, i.state.tabSize, e - s.from);
  return { line: s.number, col: r, off: n };
}
function xw(i, t) {
  let e = gc(i, t), s = i.state.selection;
  return e ? {
    update(n) {
      if (n.docChanged) {
        let r = n.changes.mapPos(n.startState.doc.line(e.line).from), o = n.state.doc.lineAt(r);
        e = { line: o.number, col: e.col, off: Math.min(e.off, o.length) }, s = s.map(n.changes);
      }
    },
    get(n, r, o) {
      let l = gc(i, n);
      if (!l)
        return s;
      let a = ww(i.state, e, l);
      return a.length ? o ? R.create(a.concat(s.ranges)) : R.create(a) : s;
    }
  } : null;
}
function kw(i) {
  let t = (i == null ? void 0 : i.eventFilter) || ((e) => e.altKey && e.button == 0);
  return H.mouseSelectionStyle.of((e, s) => t(s) ? xw(e, s) : null);
}
const Ow = {
  Alt: [18, (i) => i.altKey],
  Control: [17, (i) => i.ctrlKey],
  Shift: [16, (i) => i.shiftKey],
  Meta: [91, (i) => i.metaKey]
}, Sw = { style: "cursor: crosshair" };
function Cw(i = {}) {
  let [t, e] = Ow[i.key || "Alt"], s = At.fromClass(class {
    constructor(n) {
      this.view = n, this.isDown = !1;
    }
    set(n) {
      this.isDown != n && (this.isDown = n, this.view.update([]));
    }
  }, {
    eventHandlers: {
      keydown(n) {
        this.set(n.keyCode == t || e(n));
      },
      keyup(n) {
        (n.keyCode == t || !e(n)) && this.set(!1);
      }
    }
  });
  return [
    s,
    H.contentAttributes.of((n) => {
      var r;
      return !((r = n.plugin(s)) === null || r === void 0) && r.isDown ? Sw : null;
    })
  ];
}
const Jo = "-10000px";
class ng {
  constructor(t, e, s) {
    this.facet = e, this.createTooltipView = s, this.input = t.state.facet(e), this.tooltips = this.input.filter((n) => n), this.tooltipViews = this.tooltips.map(s);
  }
  update(t) {
    let e = t.state.facet(this.facet), s = e.filter((r) => r);
    if (e === this.input) {
      for (let r of this.tooltipViews)
        r.update && r.update(t);
      return !1;
    }
    let n = [];
    for (let r = 0; r < s.length; r++) {
      let o = s[r], l = -1;
      if (!!o) {
        for (let a = 0; a < this.tooltips.length; a++) {
          let h = this.tooltips[a];
          h && h.create == o.create && (l = a);
        }
        if (l < 0)
          n[r] = this.createTooltipView(o);
        else {
          let a = n[r] = this.tooltipViews[l];
          a.update && a.update(t);
        }
      }
    }
    for (let r of this.tooltipViews)
      n.indexOf(r) < 0 && r.dom.remove();
    return this.input = e, this.tooltips = s, this.tooltipViews = n, !0;
  }
}
function Aw() {
  return { top: 0, left: 0, bottom: innerHeight, right: innerWidth };
}
const Yo = /* @__PURE__ */ q.define({
  combine: (i) => {
    var t, e, s;
    return {
      position: j.ios ? "absolute" : ((t = i.find((n) => n.position)) === null || t === void 0 ? void 0 : t.position) || "fixed",
      parent: ((e = i.find((n) => n.parent)) === null || e === void 0 ? void 0 : e.parent) || null,
      tooltipSpace: ((s = i.find((n) => n.tooltipSpace)) === null || s === void 0 ? void 0 : s.tooltipSpace) || Aw
    };
  }
}), rg = /* @__PURE__ */ At.fromClass(class {
  constructor(i) {
    var t;
    this.view = i, this.inView = !0, this.lastTransaction = 0, this.measureTimeout = -1;
    let e = i.state.facet(Yo);
    this.position = e.position, this.parent = e.parent, this.classes = i.themeClasses, this.createContainer(), this.measureReq = { read: this.readMeasure.bind(this), write: this.writeMeasure.bind(this), key: this }, this.manager = new ng(i, dh, (s) => this.createTooltip(s)), this.intersectionObserver = typeof IntersectionObserver == "function" ? new IntersectionObserver((s) => {
      Date.now() > this.lastTransaction - 50 && s.length > 0 && s[s.length - 1].intersectionRatio < 1 && this.measureSoon();
    }, { threshold: [1] }) : null, this.observeIntersection(), (t = i.dom.ownerDocument.defaultView) === null || t === void 0 || t.addEventListener("resize", this.measureSoon = this.measureSoon.bind(this)), this.maybeMeasure();
  }
  createContainer() {
    this.parent ? (this.container = document.createElement("div"), this.container.style.position = "relative", this.container.className = this.view.themeClasses, this.parent.appendChild(this.container)) : this.container = this.view.dom;
  }
  observeIntersection() {
    if (this.intersectionObserver) {
      this.intersectionObserver.disconnect();
      for (let i of this.manager.tooltipViews)
        this.intersectionObserver.observe(i.dom);
    }
  }
  measureSoon() {
    this.measureTimeout < 0 && (this.measureTimeout = setTimeout(() => {
      this.measureTimeout = -1, this.maybeMeasure();
    }, 50));
  }
  update(i) {
    i.transactions.length && (this.lastTransaction = Date.now());
    let t = this.manager.update(i);
    t && this.observeIntersection();
    let e = t || i.geometryChanged, s = i.state.facet(Yo);
    if (s.position != this.position) {
      this.position = s.position;
      for (let n of this.manager.tooltipViews)
        n.dom.style.position = this.position;
      e = !0;
    }
    if (s.parent != this.parent) {
      this.parent && this.container.remove(), this.parent = s.parent, this.createContainer();
      for (let n of this.manager.tooltipViews)
        this.container.appendChild(n.dom);
      e = !0;
    } else
      this.parent && this.view.themeClasses != this.classes && (this.classes = this.container.className = this.view.themeClasses);
    e && this.maybeMeasure();
  }
  createTooltip(i) {
    let t = i.create(this.view);
    if (t.dom.classList.add("cm-tooltip"), i.arrow && !t.dom.querySelector(".cm-tooltip > .cm-tooltip-arrow")) {
      let e = document.createElement("div");
      e.className = "cm-tooltip-arrow", t.dom.appendChild(e);
    }
    return t.dom.style.position = this.position, t.dom.style.top = Jo, this.container.appendChild(t.dom), t.mount && t.mount(this.view), t;
  }
  destroy() {
    var i, t;
    (i = this.view.dom.ownerDocument.defaultView) === null || i === void 0 || i.removeEventListener("resize", this.measureSoon);
    for (let { dom: e } of this.manager.tooltipViews)
      e.remove();
    (t = this.intersectionObserver) === null || t === void 0 || t.disconnect(), clearTimeout(this.measureTimeout);
  }
  readMeasure() {
    let i = this.view.dom.getBoundingClientRect();
    return {
      editor: i,
      parent: this.parent ? this.container.getBoundingClientRect() : i,
      pos: this.manager.tooltips.map((t, e) => {
        let s = this.manager.tooltipViews[e];
        return s.getCoords ? s.getCoords(t.pos) : this.view.coordsAtPos(t.pos);
      }),
      size: this.manager.tooltipViews.map(({ dom: t }) => t.getBoundingClientRect()),
      space: this.view.state.facet(Yo).tooltipSpace(this.view)
    };
  }
  writeMeasure(i) {
    let { editor: t, space: e } = i, s = [];
    for (let n = 0; n < this.manager.tooltips.length; n++) {
      let r = this.manager.tooltips[n], o = this.manager.tooltipViews[n], { dom: l } = o, a = i.pos[n], h = i.size[n];
      if (!a || a.bottom <= Math.max(t.top, e.top) || a.top >= Math.min(t.bottom, e.bottom) || a.right < Math.max(t.left, e.left) - 0.1 || a.left > Math.min(t.right, e.right) + 0.1) {
        l.style.top = Jo;
        continue;
      }
      let u = r.arrow ? o.dom.querySelector(".cm-tooltip-arrow") : null, c = u ? 7 : 0, f = h.right - h.left, g = h.bottom - h.top, _ = o.offset || Pw, A = this.view.textDirection == St.LTR, m = h.width > e.right - e.left ? A ? e.left : e.right - h.width : A ? Math.min(a.left - (u ? 14 : 0) + _.x, e.right - f) : Math.max(e.left, a.left - f + (u ? 14 : 0) - _.x), p = !!r.above;
      !r.strictSide && (p ? a.top - (h.bottom - h.top) - _.y < e.top : a.bottom + (h.bottom - h.top) + _.y > e.bottom) && p == e.bottom - a.bottom > a.top - e.top && (p = !p);
      let y = p ? a.top - g - c - _.y : a.bottom + c + _.y, M = m + f;
      if (o.overlap !== !0)
        for (let x of s)
          x.left < M && x.right > m && x.top < y + g && x.bottom > y && (y = p ? x.top - g - 2 - c : x.bottom + c + 2);
      this.position == "absolute" ? (l.style.top = y - i.parent.top + "px", l.style.left = m - i.parent.left + "px") : (l.style.top = y + "px", l.style.left = m + "px"), u && (u.style.left = `${a.left + (A ? _.x : -_.x) - (m + 14 - 7)}px`), o.overlap !== !0 && s.push({ left: m, top: y, right: M, bottom: y + g }), l.classList.toggle("cm-tooltip-above", p), l.classList.toggle("cm-tooltip-below", !p), o.positioned && o.positioned();
    }
  }
  maybeMeasure() {
    if (this.manager.tooltips.length && (this.view.inView && this.view.requestMeasure(this.measureReq), this.inView != this.view.inView && (this.inView = this.view.inView, !this.inView)))
      for (let i of this.manager.tooltipViews)
        i.dom.style.top = Jo;
  }
}, {
  eventHandlers: {
    scroll() {
      this.maybeMeasure();
    }
  }
}), Tw = /* @__PURE__ */ H.baseTheme({
  ".cm-tooltip": {
    zIndex: 100
  },
  "&light .cm-tooltip": {
    border: "1px solid #bbb",
    backgroundColor: "#f5f5f5"
  },
  "&light .cm-tooltip-section:not(:first-child)": {
    borderTop: "1px solid #bbb"
  },
  "&dark .cm-tooltip": {
    backgroundColor: "#333338",
    color: "white"
  },
  ".cm-tooltip-arrow": {
    height: `${7}px`,
    width: `${7 * 2}px`,
    position: "absolute",
    zIndex: -1,
    overflow: "hidden",
    "&:before, &:after": {
      content: "''",
      position: "absolute",
      width: 0,
      height: 0,
      borderLeft: `${7}px solid transparent`,
      borderRight: `${7}px solid transparent`
    },
    ".cm-tooltip-above &": {
      bottom: `-${7}px`,
      "&:before": {
        borderTop: `${7}px solid #bbb`
      },
      "&:after": {
        borderTop: `${7}px solid #f5f5f5`,
        bottom: "1px"
      }
    },
    ".cm-tooltip-below &": {
      top: `-${7}px`,
      "&:before": {
        borderBottom: `${7}px solid #bbb`
      },
      "&:after": {
        borderBottom: `${7}px solid #f5f5f5`,
        top: "1px"
      }
    }
  },
  "&dark .cm-tooltip .cm-tooltip-arrow": {
    "&:before": {
      borderTopColor: "#333338",
      borderBottomColor: "#333338"
    },
    "&:after": {
      borderTopColor: "transparent",
      borderBottomColor: "transparent"
    }
  }
}), Pw = { x: 0, y: 0 }, dh = /* @__PURE__ */ q.define({
  enables: [rg, Tw]
}), Lr = /* @__PURE__ */ q.define();
class ph {
  constructor(t) {
    this.view = t, this.mounted = !1, this.dom = document.createElement("div"), this.dom.classList.add("cm-tooltip-hover"), this.manager = new ng(t, Lr, (e) => this.createHostedView(e));
  }
  static create(t) {
    return new ph(t);
  }
  createHostedView(t) {
    let e = t.create(this.view);
    return e.dom.classList.add("cm-tooltip-section"), this.dom.appendChild(e.dom), this.mounted && e.mount && e.mount(this.view), e;
  }
  mount(t) {
    for (let e of this.manager.tooltipViews)
      e.mount && e.mount(t);
    this.mounted = !0;
  }
  positioned() {
    for (let t of this.manager.tooltipViews)
      t.positioned && t.positioned();
  }
  update(t) {
    this.manager.update(t);
  }
}
const Ew = /* @__PURE__ */ dh.compute([Lr], (i) => {
  let t = i.facet(Lr).filter((e) => e);
  return t.length === 0 ? null : {
    pos: Math.min(...t.map((e) => e.pos)),
    end: Math.max(...t.filter((e) => e.end != null).map((e) => e.end)),
    create: ph.create,
    above: t[0].above,
    arrow: t.some((e) => e.arrow)
  };
});
class Mw {
  constructor(t, e, s, n, r) {
    this.view = t, this.source = e, this.field = s, this.setHover = n, this.hoverTime = r, this.hoverTimeout = -1, this.restartTimeout = -1, this.pending = null, this.lastMove = { x: 0, y: 0, target: t.dom, time: 0 }, this.checkHover = this.checkHover.bind(this), t.dom.addEventListener("mouseleave", this.mouseleave = this.mouseleave.bind(this)), t.dom.addEventListener("mousemove", this.mousemove = this.mousemove.bind(this));
  }
  update() {
    this.pending && (this.pending = null, clearTimeout(this.restartTimeout), this.restartTimeout = setTimeout(() => this.startHover(), 20));
  }
  get active() {
    return this.view.state.field(this.field);
  }
  checkHover() {
    if (this.hoverTimeout = -1, this.active)
      return;
    let t = Date.now() - this.lastMove.time;
    t < this.hoverTime ? this.hoverTimeout = setTimeout(this.checkHover, this.hoverTime - t) : this.startHover();
  }
  startHover() {
    clearTimeout(this.restartTimeout);
    let { lastMove: t } = this, e = this.view.contentDOM.contains(t.target) ? this.view.posAtCoords(t) : null;
    if (e == null)
      return;
    let s = this.view.coordsAtPos(e);
    if (s == null || t.y < s.top || t.y > s.bottom || t.x < s.left - this.view.defaultCharacterWidth || t.x > s.right + this.view.defaultCharacterWidth)
      return;
    let n = this.view.bidiSpans(this.view.state.doc.lineAt(e)).find((l) => l.from <= e && l.to >= e), r = n && n.dir == St.RTL ? -1 : 1, o = this.source(this.view, e, t.x < s.left ? -r : r);
    if (o != null && o.then) {
      let l = this.pending = { pos: e };
      o.then((a) => {
        this.pending == l && (this.pending = null, a && this.view.dispatch({ effects: this.setHover.of(a) }));
      }, (a) => ge(this.view.state, a, "hover tooltip"));
    } else
      o && this.view.dispatch({ effects: this.setHover.of(o) });
  }
  mousemove(t) {
    var e;
    this.lastMove = { x: t.clientX, y: t.clientY, target: t.target, time: Date.now() }, this.hoverTimeout < 0 && (this.hoverTimeout = setTimeout(this.checkHover, this.hoverTime));
    let s = this.active;
    if (s && !Rw(this.lastMove.target) || this.pending) {
      let { pos: n } = s || this.pending, r = (e = s == null ? void 0 : s.end) !== null && e !== void 0 ? e : n;
      (n == r ? this.view.posAtCoords(this.lastMove) != n : !Dw(this.view, n, r, t.clientX, t.clientY, 6)) && (this.view.dispatch({ effects: this.setHover.of(null) }), this.pending = null);
    }
  }
  mouseleave() {
    clearTimeout(this.hoverTimeout), this.hoverTimeout = -1, this.active && this.view.dispatch({ effects: this.setHover.of(null) });
  }
  destroy() {
    clearTimeout(this.hoverTimeout), this.view.dom.removeEventListener("mouseleave", this.mouseleave), this.view.dom.removeEventListener("mousemove", this.mousemove);
  }
}
function Rw(i) {
  for (let t = i; t; t = t.parentNode)
    if (t.nodeType == 1 && t.classList.contains("cm-tooltip"))
      return !0;
  return !1;
}
function Dw(i, t, e, s, n, r) {
  let o = document.createRange(), l = i.domAtPos(t), a = i.domAtPos(e);
  o.setEnd(a.node, a.offset), o.setStart(l.node, l.offset);
  let h = o.getClientRects();
  o.detach();
  for (let u = 0; u < h.length; u++) {
    let c = h[u];
    if (Math.max(c.top - n, n - c.bottom, c.left - s, s - c.right) <= r)
      return !0;
  }
  return !1;
}
function Bw(i, t = {}) {
  let e = rt.define(), s = Vt.define({
    create() {
      return null;
    },
    update(n, r) {
      if (n && (t.hideOnChange && (r.docChanged || r.selection) || t.hideOn && t.hideOn(r, n)))
        return null;
      if (n && r.docChanged) {
        let o = r.changes.mapPos(n.pos, -1, Gt.TrackDel);
        if (o == null)
          return null;
        let l = Object.assign(/* @__PURE__ */ Object.create(null), n);
        l.pos = o, n.end != null && (l.end = r.changes.mapPos(n.end)), n = l;
      }
      for (let o of r.effects)
        o.is(e) && (n = o.value), o.is(Lw) && (n = null);
      return n;
    },
    provide: (n) => Lr.from(n)
  });
  return [
    s,
    At.define((n) => new Mw(n, i, s, e, t.hoverTime || 300)),
    Ew
  ];
}
function Nw(i, t) {
  let e = i.plugin(rg);
  if (!e)
    return null;
  let s = e.manager.tooltips.indexOf(t);
  return s < 0 ? null : e.manager.tooltipViews[s];
}
const Lw = /* @__PURE__ */ rt.define(), mc = /* @__PURE__ */ q.define({
  combine(i) {
    let t, e;
    for (let s of i)
      t = t || s.topContainer, e = e || s.bottomContainer;
    return { topContainer: t, bottomContainer: e };
  }
});
function bn(i, t) {
  let e = i.plugin(og), s = e ? e.specs.indexOf(t) : -1;
  return s > -1 ? e.panels[s] : null;
}
const og = /* @__PURE__ */ At.fromClass(class {
  constructor(i) {
    this.input = i.state.facet(yn), this.specs = this.input.filter((e) => e), this.panels = this.specs.map((e) => e(i));
    let t = i.state.facet(mc);
    this.top = new Un(i, !0, t.topContainer), this.bottom = new Un(i, !1, t.bottomContainer), this.top.sync(this.panels.filter((e) => e.top)), this.bottom.sync(this.panels.filter((e) => !e.top));
    for (let e of this.panels)
      e.dom.classList.add("cm-panel"), e.mount && e.mount();
  }
  update(i) {
    let t = i.state.facet(mc);
    this.top.container != t.topContainer && (this.top.sync([]), this.top = new Un(i.view, !0, t.topContainer)), this.bottom.container != t.bottomContainer && (this.bottom.sync([]), this.bottom = new Un(i.view, !1, t.bottomContainer)), this.top.syncClasses(), this.bottom.syncClasses();
    let e = i.state.facet(yn);
    if (e != this.input) {
      let s = e.filter((a) => a), n = [], r = [], o = [], l = [];
      for (let a of s) {
        let h = this.specs.indexOf(a), u;
        h < 0 ? (u = a(i.view), l.push(u)) : (u = this.panels[h], u.update && u.update(i)), n.push(u), (u.top ? r : o).push(u);
      }
      this.specs = s, this.panels = n, this.top.sync(r), this.bottom.sync(o);
      for (let a of l)
        a.dom.classList.add("cm-panel"), a.mount && a.mount();
    } else
      for (let s of this.panels)
        s.update && s.update(i);
  }
  destroy() {
    this.top.sync([]), this.bottom.sync([]);
  }
}, {
  provide: (i) => H.scrollMargins.of((t) => {
    let e = t.plugin(i);
    return e && { top: e.top.scrollMargin(), bottom: e.bottom.scrollMargin() };
  })
});
class Un {
  constructor(t, e, s) {
    this.view = t, this.top = e, this.container = s, this.dom = void 0, this.classes = "", this.panels = [], this.syncClasses();
  }
  sync(t) {
    for (let e of this.panels)
      e.destroy && t.indexOf(e) < 0 && e.destroy();
    this.panels = t, this.syncDOM();
  }
  syncDOM() {
    if (this.panels.length == 0) {
      this.dom && (this.dom.remove(), this.dom = void 0);
      return;
    }
    if (!this.dom) {
      this.dom = document.createElement("div"), this.dom.className = this.top ? "cm-panels cm-panels-top" : "cm-panels cm-panels-bottom", this.dom.style[this.top ? "top" : "bottom"] = "0";
      let e = this.container || this.view.dom;
      e.insertBefore(this.dom, this.top ? e.firstChild : null);
    }
    let t = this.dom.firstChild;
    for (let e of this.panels)
      if (e.dom.parentNode == this.dom) {
        for (; t != e.dom; )
          t = _c(t);
        t = t.nextSibling;
      } else
        this.dom.insertBefore(e.dom, t);
    for (; t; )
      t = _c(t);
  }
  scrollMargin() {
    return !this.dom || this.container ? 0 : Math.max(0, this.top ? this.dom.getBoundingClientRect().bottom - Math.max(0, this.view.scrollDOM.getBoundingClientRect().top) : Math.min(innerHeight, this.view.scrollDOM.getBoundingClientRect().bottom) - this.dom.getBoundingClientRect().top);
  }
  syncClasses() {
    if (!(!this.container || this.classes == this.view.themeClasses)) {
      for (let t of this.classes.split(" "))
        t && this.container.classList.remove(t);
      for (let t of (this.classes = this.view.themeClasses).split(" "))
        t && this.container.classList.add(t);
    }
  }
}
function _c(i) {
  let t = i.nextSibling;
  return i.remove(), t;
}
const yn = /* @__PURE__ */ q.define({
  enables: og
});
class ei extends ji {
  compare(t) {
    return this == t || this.constructor == t.constructor && this.eq(t);
  }
  eq(t) {
    return !1;
  }
  destroy(t) {
  }
}
ei.prototype.elementClass = "";
ei.prototype.toDOM = void 0;
ei.prototype.mapMode = Gt.TrackBefore;
ei.prototype.startSide = ei.prototype.endSide = -1;
ei.prototype.point = !0;
const mr = /* @__PURE__ */ q.define(), Iw = {
  class: "",
  renderEmptyElements: !1,
  elementStyle: "",
  markers: () => ft.empty,
  lineMarker: () => null,
  lineMarkerChange: null,
  initialSpacer: null,
  updateSpacer: null,
  domEventHandlers: {}
}, sn = /* @__PURE__ */ q.define();
function Qw(i) {
  return [lg(), sn.of(Object.assign(Object.assign({}, Iw), i))];
}
const Aa = /* @__PURE__ */ q.define({
  combine: (i) => i.some((t) => t)
});
function lg(i) {
  let t = [
    zw
  ];
  return i && i.fixed === !1 && t.push(Aa.of(!0)), t;
}
const zw = /* @__PURE__ */ At.fromClass(class {
  constructor(i) {
    this.view = i, this.prevViewport = i.viewport, this.dom = document.createElement("div"), this.dom.className = "cm-gutters", this.dom.setAttribute("aria-hidden", "true"), this.dom.style.minHeight = this.view.contentHeight + "px", this.gutters = i.state.facet(sn).map((t) => new yc(i, t));
    for (let t of this.gutters)
      this.dom.appendChild(t.dom);
    this.fixed = !i.state.facet(Aa), this.fixed && (this.dom.style.position = "sticky"), this.syncGutters(!1), i.scrollDOM.insertBefore(this.dom, i.contentDOM);
  }
  update(i) {
    if (this.updateGutters(i)) {
      let t = this.prevViewport, e = i.view.viewport, s = Math.min(t.to, e.to) - Math.max(t.from, e.from);
      this.syncGutters(s < (e.to - e.from) * 0.8);
    }
    i.geometryChanged && (this.dom.style.minHeight = this.view.contentHeight + "px"), this.view.state.facet(Aa) != !this.fixed && (this.fixed = !this.fixed, this.dom.style.position = this.fixed ? "sticky" : ""), this.prevViewport = i.view.viewport;
  }
  syncGutters(i) {
    let t = this.dom.nextSibling;
    i && this.dom.remove();
    let e = ft.iter(this.view.state.facet(mr), this.view.viewport.from), s = [], n = this.gutters.map((r) => new Ww(r, this.view.viewport, -this.view.documentPadding.top));
    for (let r of this.view.viewportLineBlocks) {
      let o;
      if (Array.isArray(r.type)) {
        for (let l of r.type)
          if (l.type == _t.Text) {
            o = l;
            break;
          }
      } else
        o = r.type == _t.Text ? r : void 0;
      if (!!o) {
        s.length && (s = []), ag(e, s, r.from);
        for (let l of n)
          l.line(this.view, o, s);
      }
    }
    for (let r of n)
      r.finish();
    i && this.view.scrollDOM.insertBefore(this.dom, t);
  }
  updateGutters(i) {
    let t = i.startState.facet(sn), e = i.state.facet(sn), s = i.docChanged || i.heightChanged || i.viewportChanged || !ft.eq(i.startState.facet(mr), i.state.facet(mr), i.view.viewport.from, i.view.viewport.to);
    if (t == e)
      for (let n of this.gutters)
        n.update(i) && (s = !0);
    else {
      s = !0;
      let n = [];
      for (let r of e) {
        let o = t.indexOf(r);
        o < 0 ? n.push(new yc(this.view, r)) : (this.gutters[o].update(i), n.push(this.gutters[o]));
      }
      for (let r of this.gutters)
        r.dom.remove(), n.indexOf(r) < 0 && r.destroy();
      for (let r of n)
        this.dom.appendChild(r.dom);
      this.gutters = n;
    }
    return s;
  }
  destroy() {
    for (let i of this.gutters)
      i.destroy();
    this.dom.remove();
  }
}, {
  provide: (i) => H.scrollMargins.of((t) => {
    let e = t.plugin(i);
    return !e || e.gutters.length == 0 || !e.fixed ? null : t.textDirection == St.LTR ? { left: e.dom.offsetWidth } : { right: e.dom.offsetWidth };
  })
});
function bc(i) {
  return Array.isArray(i) ? i : [i];
}
function ag(i, t, e) {
  for (; i.value && i.from <= e; )
    i.from == e && t.push(i.value), i.next();
}
class Ww {
  constructor(t, e, s) {
    this.gutter = t, this.height = s, this.localMarkers = [], this.i = 0, this.cursor = ft.iter(t.markers, e.from);
  }
  line(t, e, s) {
    this.localMarkers.length && (this.localMarkers = []), ag(this.cursor, this.localMarkers, e.from);
    let n = s.length ? this.localMarkers.concat(s) : this.localMarkers, r = this.gutter.config.lineMarker(t, e, n);
    r && n.unshift(r);
    let o = this.gutter;
    if (n.length == 0 && !o.config.renderEmptyElements)
      return;
    let l = e.top - this.height;
    if (this.i == o.elements.length) {
      let a = new hg(t, e.height, l, n);
      o.elements.push(a), o.dom.appendChild(a.dom);
    } else
      o.elements[this.i].update(t, e.height, l, n);
    this.height = e.bottom, this.i++;
  }
  finish() {
    let t = this.gutter;
    for (; t.elements.length > this.i; ) {
      let e = t.elements.pop();
      t.dom.removeChild(e.dom), e.destroy();
    }
  }
}
class yc {
  constructor(t, e) {
    this.view = t, this.config = e, this.elements = [], this.spacer = null, this.dom = document.createElement("div"), this.dom.className = "cm-gutter" + (this.config.class ? " " + this.config.class : "");
    for (let s in e.domEventHandlers)
      this.dom.addEventListener(s, (n) => {
        let r = t.lineBlockAtHeight(n.clientY - t.documentTop);
        e.domEventHandlers[s](t, r, n) && n.preventDefault();
      });
    this.markers = bc(e.markers(t)), e.initialSpacer && (this.spacer = new hg(t, 0, 0, [e.initialSpacer(t)]), this.dom.appendChild(this.spacer.dom), this.spacer.dom.style.cssText += "visibility: hidden; pointer-events: none");
  }
  update(t) {
    let e = this.markers;
    if (this.markers = bc(this.config.markers(t.view)), this.spacer && this.config.updateSpacer) {
      let n = this.config.updateSpacer(this.spacer.markers[0], t);
      n != this.spacer.markers[0] && this.spacer.update(t.view, 0, 0, [n]);
    }
    let s = t.view.viewport;
    return !ft.eq(this.markers, e, s.from, s.to) || (this.config.lineMarkerChange ? this.config.lineMarkerChange(t) : !1);
  }
  destroy() {
    for (let t of this.elements)
      t.destroy();
  }
}
class hg {
  constructor(t, e, s, n) {
    this.height = -1, this.above = 0, this.markers = [], this.dom = document.createElement("div"), this.dom.className = "cm-gutterElement", this.update(t, e, s, n);
  }
  update(t, e, s, n) {
    this.height != e && (this.dom.style.height = (this.height = e) + "px"), this.above != s && (this.dom.style.marginTop = (this.above = s) ? s + "px" : ""), $w(this.markers, n) || this.setMarkers(t, n);
  }
  setMarkers(t, e) {
    let s = "cm-gutterElement", n = this.dom.firstChild;
    for (let r = 0, o = 0; ; ) {
      let l = o, a = r < e.length ? e[r++] : null, h = !1;
      if (a) {
        let u = a.elementClass;
        u && (s += " " + u);
        for (let c = o; c < this.markers.length; c++)
          if (this.markers[c].compare(a)) {
            l = c, h = !0;
            break;
          }
      } else
        l = this.markers.length;
      for (; o < l; ) {
        let u = this.markers[o++];
        if (u.toDOM) {
          u.destroy(n);
          let c = n.nextSibling;
          n.remove(), n = c;
        }
      }
      if (!a)
        break;
      a.toDOM && (h ? n = n.nextSibling : this.dom.insertBefore(a.toDOM(t), n)), h && o++;
    }
    this.dom.className = s, this.markers = e;
  }
  destroy() {
    this.setMarkers(null, []);
  }
}
function $w(i, t) {
  if (i.length != t.length)
    return !1;
  for (let e = 0; e < i.length; e++)
    if (!i[e].compare(t[e]))
      return !1;
  return !0;
}
const Fw = /* @__PURE__ */ q.define(), rs = /* @__PURE__ */ q.define({
  combine(i) {
    return si(i, { formatNumber: String, domEventHandlers: {} }, {
      domEventHandlers(t, e) {
        let s = Object.assign({}, t);
        for (let n in e) {
          let r = s[n], o = e[n];
          s[n] = r ? (l, a, h) => r(l, a, h) || o(l, a, h) : o;
        }
        return s;
      }
    });
  }
});
class Zo extends ei {
  constructor(t) {
    super(), this.number = t;
  }
  eq(t) {
    return this.number == t.number;
  }
  toDOM() {
    return document.createTextNode(this.number);
  }
}
function tl(i, t) {
  return i.state.facet(rs).formatNumber(t, i.state);
}
const Vw = /* @__PURE__ */ sn.compute([rs], (i) => ({
  class: "cm-lineNumbers",
  renderEmptyElements: !1,
  markers(t) {
    return t.state.facet(Fw);
  },
  lineMarker(t, e, s) {
    return s.some((n) => n.toDOM) ? null : new Zo(tl(t, t.state.doc.lineAt(e.from).number));
  },
  lineMarkerChange: (t) => t.startState.facet(rs) != t.state.facet(rs),
  initialSpacer(t) {
    return new Zo(tl(t, wc(t.state.doc.lines)));
  },
  updateSpacer(t, e) {
    let s = tl(e.view, wc(e.view.state.doc.lines));
    return s == t.number ? t : new Zo(s);
  },
  domEventHandlers: i.facet(rs).domEventHandlers
}));
function Uw(i = {}) {
  return [
    rs.of(i),
    lg(),
    Vw
  ];
}
function wc(i) {
  let t = 9;
  for (; t < i; )
    t = t * 10 + 9;
  return t;
}
const jw = /* @__PURE__ */ new class extends ei {
  constructor() {
    super(...arguments), this.elementClass = "cm-activeLineGutter";
  }
}(), Hw = /* @__PURE__ */ mr.compute(["selection"], (i) => {
  let t = [], e = -1;
  for (let s of i.selection.ranges)
    if (s.empty) {
      let n = i.doc.lineAt(s.head).from;
      n > e && (e = n, t.push(jw.range(n)));
    }
  return ft.of(t);
});
function qw() {
  return Hw;
}
const ug = 1024;
let Kw = 0;
class el {
  constructor(t, e) {
    this.from = t, this.to = e;
  }
}
class ot {
  constructor(t = {}) {
    this.id = Kw++, this.perNode = !!t.perNode, this.deserialize = t.deserialize || (() => {
      throw new Error("This node type doesn't define a deserialize function");
    });
  }
  add(t) {
    if (this.perNode)
      throw new RangeError("Can't add per-node props to node types");
    return typeof t != "function" && (t = ne.match(t)), (e) => {
      let s = t(e);
      return s === void 0 ? null : [this, s];
    };
  }
}
ot.closedBy = new ot({ deserialize: (i) => i.split(" ") });
ot.openedBy = new ot({ deserialize: (i) => i.split(" ") });
ot.group = new ot({ deserialize: (i) => i.split(" ") });
ot.contextHash = new ot({ perNode: !0 });
ot.lookAhead = new ot({ perNode: !0 });
ot.mounted = new ot({ perNode: !0 });
const Xw = /* @__PURE__ */ Object.create(null);
class ne {
  constructor(t, e, s, n = 0) {
    this.name = t, this.props = e, this.id = s, this.flags = n;
  }
  static define(t) {
    let e = t.props && t.props.length ? /* @__PURE__ */ Object.create(null) : Xw, s = (t.top ? 1 : 0) | (t.skipped ? 2 : 0) | (t.error ? 4 : 0) | (t.name == null ? 8 : 0), n = new ne(t.name || "", e, t.id, s);
    if (t.props) {
      for (let r of t.props)
        if (Array.isArray(r) || (r = r(n)), r) {
          if (r[0].perNode)
            throw new RangeError("Can't store a per-node prop on a node type");
          e[r[0].id] = r[1];
        }
    }
    return n;
  }
  prop(t) {
    return this.props[t.id];
  }
  get isTop() {
    return (this.flags & 1) > 0;
  }
  get isSkipped() {
    return (this.flags & 2) > 0;
  }
  get isError() {
    return (this.flags & 4) > 0;
  }
  get isAnonymous() {
    return (this.flags & 8) > 0;
  }
  is(t) {
    if (typeof t == "string") {
      if (this.name == t)
        return !0;
      let e = this.prop(ot.group);
      return e ? e.indexOf(t) > -1 : !1;
    }
    return this.id == t;
  }
  static match(t) {
    let e = /* @__PURE__ */ Object.create(null);
    for (let s in t)
      for (let n of s.split(" "))
        e[n] = t[s];
    return (s) => {
      for (let n = s.prop(ot.group), r = -1; r < (n ? n.length : 0); r++) {
        let o = e[r < 0 ? s.name : n[r]];
        if (o)
          return o;
      }
    };
  }
}
ne.none = new ne("", /* @__PURE__ */ Object.create(null), 0, 8);
class gh {
  constructor(t) {
    this.types = t;
    for (let e = 0; e < t.length; e++)
      if (t[e].id != e)
        throw new RangeError("Node type ids should correspond to array positions when creating a node set");
  }
  extend(...t) {
    let e = [];
    for (let s of this.types) {
      let n = null;
      for (let r of t) {
        let o = r(s);
        o && (n || (n = Object.assign({}, s.props)), n[o[0].id] = o[1]);
      }
      e.push(n ? new ne(s.name, n, s.id, s.flags) : s);
    }
    return new gh(e);
  }
}
const jn = /* @__PURE__ */ new WeakMap(), vc = /* @__PURE__ */ new WeakMap();
var zt;
(function(i) {
  i[i.ExcludeBuffers = 1] = "ExcludeBuffers", i[i.IncludeAnonymous = 2] = "IncludeAnonymous", i[i.IgnoreMounts = 4] = "IgnoreMounts", i[i.IgnoreOverlays = 8] = "IgnoreOverlays";
})(zt || (zt = {}));
class Tt {
  constructor(t, e, s, n, r) {
    if (this.type = t, this.children = e, this.positions = s, this.length = n, this.props = null, r && r.length) {
      this.props = /* @__PURE__ */ Object.create(null);
      for (let [o, l] of r)
        this.props[typeof o == "number" ? o : o.id] = l;
    }
  }
  toString() {
    let t = this.prop(ot.mounted);
    if (t && !t.overlay)
      return t.tree.toString();
    let e = "";
    for (let s of this.children) {
      let n = s.toString();
      n && (e && (e += ","), e += n);
    }
    return this.type.name ? (/\W/.test(this.type.name) && !this.type.isError ? JSON.stringify(this.type.name) : this.type.name) + (e.length ? "(" + e + ")" : "") : e;
  }
  cursor(t = 0) {
    return new zr(this.topNode, t);
  }
  cursorAt(t, e = 0, s = 0) {
    let n = jn.get(this) || this.topNode, r = new zr(n);
    return r.moveTo(t, e), jn.set(this, r._tree), r;
  }
  get topNode() {
    return new Ze(this, 0, 0, null);
  }
  resolve(t, e = 0) {
    let s = ws(jn.get(this) || this.topNode, t, e, !1);
    return jn.set(this, s), s;
  }
  resolveInner(t, e = 0) {
    let s = ws(vc.get(this) || this.topNode, t, e, !0);
    return vc.set(this, s), s;
  }
  iterate(t) {
    let { enter: e, leave: s, from: n = 0, to: r = this.length } = t;
    for (let o = this.cursor((t.mode || 0) | zt.IncludeAnonymous); ; ) {
      let l = !1;
      if (o.from <= r && o.to >= n && (o.type.isAnonymous || e(o) !== !1)) {
        if (o.firstChild())
          continue;
        l = !0;
      }
      for (; l && s && !o.type.isAnonymous && s(o), !o.nextSibling(); ) {
        if (!o.parent())
          return;
        l = !0;
      }
    }
  }
  prop(t) {
    return t.perNode ? this.props ? this.props[t.id] : void 0 : this.type.prop(t);
  }
  get propValues() {
    let t = [];
    if (this.props)
      for (let e in this.props)
        t.push([+e, this.props[e]]);
    return t;
  }
  balance(t = {}) {
    return this.children.length <= 8 ? this : bh(ne.none, this.children, this.positions, 0, this.children.length, 0, this.length, (e, s, n) => new Tt(this.type, e, s, n, this.propValues), t.makeTree || ((e, s, n) => new Tt(ne.none, e, s, n)));
  }
  static build(t) {
    return Jw(t);
  }
}
Tt.empty = new Tt(ne.none, [], [], 0);
class mh {
  constructor(t, e) {
    this.buffer = t, this.index = e;
  }
  get id() {
    return this.buffer[this.index - 4];
  }
  get start() {
    return this.buffer[this.index - 3];
  }
  get end() {
    return this.buffer[this.index - 2];
  }
  get size() {
    return this.buffer[this.index - 1];
  }
  get pos() {
    return this.index;
  }
  next() {
    this.index -= 4;
  }
  fork() {
    return new mh(this.buffer, this.index);
  }
}
class Xi {
  constructor(t, e, s) {
    this.buffer = t, this.length = e, this.set = s;
  }
  get type() {
    return ne.none;
  }
  toString() {
    let t = [];
    for (let e = 0; e < this.buffer.length; )
      t.push(this.childString(e)), e = this.buffer[e + 3];
    return t.join(",");
  }
  childString(t) {
    let e = this.buffer[t], s = this.buffer[t + 3], n = this.set.types[e], r = n.name;
    if (/\W/.test(r) && !n.isError && (r = JSON.stringify(r)), t += 4, s == t)
      return r;
    let o = [];
    for (; t < s; )
      o.push(this.childString(t)), t = this.buffer[t + 3];
    return r + "(" + o.join(",") + ")";
  }
  findChild(t, e, s, n, r) {
    let { buffer: o } = this, l = -1;
    for (let a = t; a != e && !(cg(r, n, o[a + 1], o[a + 2]) && (l = a, s > 0)); a = o[a + 3])
      ;
    return l;
  }
  slice(t, e, s, n) {
    let r = this.buffer, o = new Uint16Array(e - t);
    for (let l = t, a = 0; l < e; )
      o[a++] = r[l++], o[a++] = r[l++] - s, o[a++] = r[l++] - s, o[a++] = r[l++] - t;
    return new Xi(o, n - s, this.set);
  }
}
function cg(i, t, e, s) {
  switch (i) {
    case -2:
      return e < t;
    case -1:
      return s >= t && e < t;
    case 0:
      return e < t && s > t;
    case 1:
      return e <= t && s > t;
    case 2:
      return s > t;
    case 4:
      return !0;
  }
}
function fg(i, t) {
  let e = i.childBefore(t);
  for (; e; ) {
    let s = e.lastChild;
    if (!s || s.to != e.to)
      break;
    s.type.isError && s.from == s.to ? (i = e, e = s.prevSibling) : e = s;
  }
  return i;
}
function ws(i, t, e, s) {
  for (var n; i.from == i.to || (e < 1 ? i.from >= t : i.from > t) || (e > -1 ? i.to <= t : i.to < t); ) {
    let o = !s && i instanceof Ze && i.index < 0 ? null : i.parent;
    if (!o)
      return i;
    i = o;
  }
  let r = s ? 0 : zt.IgnoreOverlays;
  if (s)
    for (let o = i, l = o.parent; l; o = l, l = o.parent)
      o instanceof Ze && o.index < 0 && ((n = l.enter(t, e, r)) === null || n === void 0 ? void 0 : n.from) != o.from && (i = l);
  for (; ; ) {
    let o = i.enter(t, e, r);
    if (!o)
      return i;
    i = o;
  }
}
class Ze {
  constructor(t, e, s, n) {
    this._tree = t, this.from = e, this.index = s, this._parent = n;
  }
  get type() {
    return this._tree.type;
  }
  get name() {
    return this._tree.type.name;
  }
  get to() {
    return this.from + this._tree.length;
  }
  nextChild(t, e, s, n, r = 0) {
    for (let o = this; ; ) {
      for (let { children: l, positions: a } = o._tree, h = e > 0 ? l.length : -1; t != h; t += e) {
        let u = l[t], c = a[t] + o.from;
        if (!!cg(n, s, c, c + u.length)) {
          if (u instanceof Xi) {
            if (r & zt.ExcludeBuffers)
              continue;
            let f = u.findChild(0, u.buffer.length, e, s - c, n);
            if (f > -1)
              return new bi(new Gw(o, u, t, c), null, f);
          } else if (r & zt.IncludeAnonymous || !u.type.isAnonymous || _h(u)) {
            let f;
            if (!(r & zt.IgnoreMounts) && u.props && (f = u.prop(ot.mounted)) && !f.overlay)
              return new Ze(f.tree, c, t, o);
            let g = new Ze(u, c, t, o);
            return r & zt.IncludeAnonymous || !g.type.isAnonymous ? g : g.nextChild(e < 0 ? u.children.length - 1 : 0, e, s, n);
          }
        }
      }
      if (r & zt.IncludeAnonymous || !o.type.isAnonymous || (o.index >= 0 ? t = o.index + e : t = e < 0 ? -1 : o._parent._tree.children.length, o = o._parent, !o))
        return null;
    }
  }
  get firstChild() {
    return this.nextChild(0, 1, 0, 4);
  }
  get lastChild() {
    return this.nextChild(this._tree.children.length - 1, -1, 0, 4);
  }
  childAfter(t) {
    return this.nextChild(0, 1, t, 2);
  }
  childBefore(t) {
    return this.nextChild(this._tree.children.length - 1, -1, t, -2);
  }
  enter(t, e, s = 0) {
    let n;
    if (!(s & zt.IgnoreOverlays) && (n = this._tree.prop(ot.mounted)) && n.overlay) {
      let r = t - this.from;
      for (let { from: o, to: l } of n.overlay)
        if ((e > 0 ? o <= r : o < r) && (e < 0 ? l >= r : l > r))
          return new Ze(n.tree, n.overlay[0].from + this.from, -1, this);
    }
    return this.nextChild(0, 1, t, e, s);
  }
  nextSignificantParent() {
    let t = this;
    for (; t.type.isAnonymous && t._parent; )
      t = t._parent;
    return t;
  }
  get parent() {
    return this._parent ? this._parent.nextSignificantParent() : null;
  }
  get nextSibling() {
    return this._parent && this.index >= 0 ? this._parent.nextChild(this.index + 1, 1, 0, 4) : null;
  }
  get prevSibling() {
    return this._parent && this.index >= 0 ? this._parent.nextChild(this.index - 1, -1, 0, 4) : null;
  }
  cursor(t = 0) {
    return new zr(this, t);
  }
  get tree() {
    return this._tree;
  }
  toTree() {
    return this._tree;
  }
  resolve(t, e = 0) {
    return ws(this, t, e, !1);
  }
  resolveInner(t, e = 0) {
    return ws(this, t, e, !0);
  }
  enterUnfinishedNodesBefore(t) {
    return fg(this, t);
  }
  getChild(t, e = null, s = null) {
    let n = Ir(this, t, e, s);
    return n.length ? n[0] : null;
  }
  getChildren(t, e = null, s = null) {
    return Ir(this, t, e, s);
  }
  toString() {
    return this._tree.toString();
  }
  get node() {
    return this;
  }
  matchContext(t) {
    return Qr(this, t);
  }
}
function Ir(i, t, e, s) {
  let n = i.cursor(), r = [];
  if (!n.firstChild())
    return r;
  if (e != null) {
    for (; !n.type.is(e); )
      if (!n.nextSibling())
        return r;
  }
  for (; ; ) {
    if (s != null && n.type.is(s))
      return r;
    if (n.type.is(t) && r.push(n.node), !n.nextSibling())
      return s == null ? r : [];
  }
}
function Qr(i, t, e = t.length - 1) {
  for (let s = i.parent; e >= 0; s = s.parent) {
    if (!s)
      return !1;
    if (!s.type.isAnonymous) {
      if (t[e] && t[e] != s.name)
        return !1;
      e--;
    }
  }
  return !0;
}
class Gw {
  constructor(t, e, s, n) {
    this.parent = t, this.buffer = e, this.index = s, this.start = n;
  }
}
class bi {
  constructor(t, e, s) {
    this.context = t, this._parent = e, this.index = s, this.type = t.buffer.set.types[t.buffer.buffer[s]];
  }
  get name() {
    return this.type.name;
  }
  get from() {
    return this.context.start + this.context.buffer.buffer[this.index + 1];
  }
  get to() {
    return this.context.start + this.context.buffer.buffer[this.index + 2];
  }
  child(t, e, s) {
    let { buffer: n } = this.context, r = n.findChild(this.index + 4, n.buffer[this.index + 3], t, e - this.context.start, s);
    return r < 0 ? null : new bi(this.context, this, r);
  }
  get firstChild() {
    return this.child(1, 0, 4);
  }
  get lastChild() {
    return this.child(-1, 0, 4);
  }
  childAfter(t) {
    return this.child(1, t, 2);
  }
  childBefore(t) {
    return this.child(-1, t, -2);
  }
  enter(t, e, s = 0) {
    if (s & zt.ExcludeBuffers)
      return null;
    let { buffer: n } = this.context, r = n.findChild(this.index + 4, n.buffer[this.index + 3], e > 0 ? 1 : -1, t - this.context.start, e);
    return r < 0 ? null : new bi(this.context, this, r);
  }
  get parent() {
    return this._parent || this.context.parent.nextSignificantParent();
  }
  externalSibling(t) {
    return this._parent ? null : this.context.parent.nextChild(this.context.index + t, t, 0, 4);
  }
  get nextSibling() {
    let { buffer: t } = this.context, e = t.buffer[this.index + 3];
    return e < (this._parent ? t.buffer[this._parent.index + 3] : t.buffer.length) ? new bi(this.context, this._parent, e) : this.externalSibling(1);
  }
  get prevSibling() {
    let { buffer: t } = this.context, e = this._parent ? this._parent.index + 4 : 0;
    return this.index == e ? this.externalSibling(-1) : new bi(this.context, this._parent, t.findChild(e, this.index, -1, 0, 4));
  }
  cursor(t = 0) {
    return new zr(this, t);
  }
  get tree() {
    return null;
  }
  toTree() {
    let t = [], e = [], { buffer: s } = this.context, n = this.index + 4, r = s.buffer[this.index + 3];
    if (r > n) {
      let o = s.buffer[this.index + 1], l = s.buffer[this.index + 2];
      t.push(s.slice(n, r, o, l)), e.push(0);
    }
    return new Tt(this.type, t, e, this.to - this.from);
  }
  resolve(t, e = 0) {
    return ws(this, t, e, !1);
  }
  resolveInner(t, e = 0) {
    return ws(this, t, e, !0);
  }
  enterUnfinishedNodesBefore(t) {
    return fg(this, t);
  }
  toString() {
    return this.context.buffer.childString(this.index);
  }
  getChild(t, e = null, s = null) {
    let n = Ir(this, t, e, s);
    return n.length ? n[0] : null;
  }
  getChildren(t, e = null, s = null) {
    return Ir(this, t, e, s);
  }
  get node() {
    return this;
  }
  matchContext(t) {
    return Qr(this, t);
  }
}
class zr {
  constructor(t, e = 0) {
    if (this.mode = e, this.buffer = null, this.stack = [], this.index = 0, this.bufferNode = null, t instanceof Ze)
      this.yieldNode(t);
    else {
      this._tree = t.context.parent, this.buffer = t.context;
      for (let s = t._parent; s; s = s._parent)
        this.stack.unshift(s.index);
      this.bufferNode = t, this.yieldBuf(t.index);
    }
  }
  get name() {
    return this.type.name;
  }
  yieldNode(t) {
    return t ? (this._tree = t, this.type = t.type, this.from = t.from, this.to = t.to, !0) : !1;
  }
  yieldBuf(t, e) {
    this.index = t;
    let { start: s, buffer: n } = this.buffer;
    return this.type = e || n.set.types[n.buffer[t]], this.from = s + n.buffer[t + 1], this.to = s + n.buffer[t + 2], !0;
  }
  yield(t) {
    return t ? t instanceof Ze ? (this.buffer = null, this.yieldNode(t)) : (this.buffer = t.context, this.yieldBuf(t.index, t.type)) : !1;
  }
  toString() {
    return this.buffer ? this.buffer.buffer.childString(this.index) : this._tree.toString();
  }
  enterChild(t, e, s) {
    if (!this.buffer)
      return this.yield(this._tree.nextChild(t < 0 ? this._tree._tree.children.length - 1 : 0, t, e, s, this.mode));
    let { buffer: n } = this.buffer, r = n.findChild(this.index + 4, n.buffer[this.index + 3], t, e - this.buffer.start, s);
    return r < 0 ? !1 : (this.stack.push(this.index), this.yieldBuf(r));
  }
  firstChild() {
    return this.enterChild(1, 0, 4);
  }
  lastChild() {
    return this.enterChild(-1, 0, 4);
  }
  childAfter(t) {
    return this.enterChild(1, t, 2);
  }
  childBefore(t) {
    return this.enterChild(-1, t, -2);
  }
  enter(t, e, s = this.mode) {
    return this.buffer ? s & zt.ExcludeBuffers ? !1 : this.enterChild(1, t, e) : this.yield(this._tree.enter(t, e, s));
  }
  parent() {
    if (!this.buffer)
      return this.yieldNode(this.mode & zt.IncludeAnonymous ? this._tree._parent : this._tree.parent);
    if (this.stack.length)
      return this.yieldBuf(this.stack.pop());
    let t = this.mode & zt.IncludeAnonymous ? this.buffer.parent : this.buffer.parent.nextSignificantParent();
    return this.buffer = null, this.yieldNode(t);
  }
  sibling(t) {
    if (!this.buffer)
      return this._tree._parent ? this.yield(this._tree.index < 0 ? null : this._tree._parent.nextChild(this._tree.index + t, t, 0, 4, this.mode)) : !1;
    let { buffer: e } = this.buffer, s = this.stack.length - 1;
    if (t < 0) {
      let n = s < 0 ? 0 : this.stack[s] + 4;
      if (this.index != n)
        return this.yieldBuf(e.findChild(n, this.index, -1, 0, 4));
    } else {
      let n = e.buffer[this.index + 3];
      if (n < (s < 0 ? e.buffer.length : e.buffer[this.stack[s] + 3]))
        return this.yieldBuf(n);
    }
    return s < 0 ? this.yield(this.buffer.parent.nextChild(this.buffer.index + t, t, 0, 4, this.mode)) : !1;
  }
  nextSibling() {
    return this.sibling(1);
  }
  prevSibling() {
    return this.sibling(-1);
  }
  atLastNode(t) {
    let e, s, { buffer: n } = this;
    if (n) {
      if (t > 0) {
        if (this.index < n.buffer.buffer.length)
          return !1;
      } else
        for (let r = 0; r < this.index; r++)
          if (n.buffer.buffer[r + 3] < this.index)
            return !1;
      ({ index: e, parent: s } = n);
    } else
      ({ index: e, _parent: s } = this._tree);
    for (; s; { index: e, _parent: s } = s)
      if (e > -1)
        for (let r = e + t, o = t < 0 ? -1 : s._tree.children.length; r != o; r += t) {
          let l = s._tree.children[r];
          if (this.mode & zt.IncludeAnonymous || l instanceof Xi || !l.type.isAnonymous || _h(l))
            return !1;
        }
    return !0;
  }
  move(t, e) {
    if (e && this.enterChild(t, 0, 4))
      return !0;
    for (; ; ) {
      if (this.sibling(t))
        return !0;
      if (this.atLastNode(t) || !this.parent())
        return !1;
    }
  }
  next(t = !0) {
    return this.move(1, t);
  }
  prev(t = !0) {
    return this.move(-1, t);
  }
  moveTo(t, e = 0) {
    for (; (this.from == this.to || (e < 1 ? this.from >= t : this.from > t) || (e > -1 ? this.to <= t : this.to < t)) && this.parent(); )
      ;
    for (; this.enterChild(1, t, e); )
      ;
    return this;
  }
  get node() {
    if (!this.buffer)
      return this._tree;
    let t = this.bufferNode, e = null, s = 0;
    if (t && t.context == this.buffer) {
      t:
        for (let n = this.index, r = this.stack.length; r >= 0; ) {
          for (let o = t; o; o = o._parent)
            if (o.index == n) {
              if (n == this.index)
                return o;
              e = o, s = r + 1;
              break t;
            }
          n = this.stack[--r];
        }
    }
    for (let n = s; n < this.stack.length; n++)
      e = new bi(this.buffer, e, this.stack[n]);
    return this.bufferNode = new bi(this.buffer, e, this.index);
  }
  get tree() {
    return this.buffer ? null : this._tree._tree;
  }
  iterate(t, e) {
    for (let s = 0; ; ) {
      let n = !1;
      if (this.type.isAnonymous || t(this) !== !1) {
        if (this.firstChild()) {
          s++;
          continue;
        }
        this.type.isAnonymous || (n = !0);
      }
      for (; n && e && e(this), n = this.type.isAnonymous, !this.nextSibling(); ) {
        if (!s)
          return;
        this.parent(), s--, n = !0;
      }
    }
  }
  matchContext(t) {
    if (!this.buffer)
      return Qr(this.node, t);
    let { buffer: e } = this.buffer, { types: s } = e.set;
    for (let n = t.length - 1, r = this.stack.length - 1; n >= 0; r--) {
      if (r < 0)
        return Qr(this.node, t, n);
      let o = s[e.buffer[this.stack[r]]];
      if (!o.isAnonymous) {
        if (t[n] && t[n] != o.name)
          return !1;
        n--;
      }
    }
    return !0;
  }
}
function _h(i) {
  return i.children.some((t) => t instanceof Xi || !t.type.isAnonymous || _h(t));
}
function Jw(i) {
  var t;
  let { buffer: e, nodeSet: s, maxBufferLength: n = ug, reused: r = [], minRepeatType: o = s.types.length } = i, l = Array.isArray(e) ? new mh(e, e.length) : e, a = s.types, h = 0, u = 0;
  function c(x, B, v, C, k) {
    let { id: E, start: d, end: S, size: T } = l, $ = u;
    for (; T < 0; )
      if (l.next(), T == -1) {
        let Z = r[E];
        v.push(Z), C.push(d - x);
        return;
      } else if (T == -3) {
        h = E;
        return;
      } else if (T == -4) {
        u = E;
        return;
      } else
        throw new RangeError(`Unrecognized record size: ${T}`);
    let K = a[E], it, Y, et = d - x;
    if (S - d <= n && (Y = A(l.pos - B, k))) {
      let Z = new Uint16Array(Y.size - Y.skip), bt = l.pos - Y.size, Ut = Z.length;
      for (; l.pos > bt; )
        Ut = m(Y.start, Z, Ut);
      it = new Xi(Z, S - Y.start, s), et = Y.start - x;
    } else {
      let Z = l.pos - T;
      l.next();
      let bt = [], Ut = [], jt = E >= o ? E : -1, re = 0, oe = S;
      for (; l.pos > Z; )
        jt >= 0 && l.id == jt && l.size >= 0 ? (l.end <= oe - n && (g(bt, Ut, d, re, l.end, oe, jt, $), re = bt.length, oe = l.end), l.next()) : c(d, Z, bt, Ut, jt);
      if (jt >= 0 && re > 0 && re < bt.length && g(bt, Ut, d, re, d, oe, jt, $), bt.reverse(), Ut.reverse(), jt > -1 && re > 0) {
        let Ps = f(K);
        it = bh(K, bt, Ut, 0, bt.length, 0, S - d, Ps, Ps);
      } else
        it = _(K, bt, Ut, S - d, $ - S);
    }
    v.push(it), C.push(et);
  }
  function f(x) {
    return (B, v, C) => {
      let k = 0, E = B.length - 1, d, S;
      if (E >= 0 && (d = B[E]) instanceof Tt) {
        if (!E && d.type == x && d.length == C)
          return d;
        (S = d.prop(ot.lookAhead)) && (k = v[E] + d.length + S);
      }
      return _(x, B, v, C, k);
    };
  }
  function g(x, B, v, C, k, E, d, S) {
    let T = [], $ = [];
    for (; x.length > C; )
      T.push(x.pop()), $.push(B.pop() + v - k);
    x.push(_(s.types[d], T, $, E - k, S - E)), B.push(k - v);
  }
  function _(x, B, v, C, k = 0, E) {
    if (h) {
      let d = [ot.contextHash, h];
      E = E ? [d].concat(E) : [d];
    }
    if (k > 25) {
      let d = [ot.lookAhead, k];
      E = E ? [d].concat(E) : [d];
    }
    return new Tt(x, B, v, C, E);
  }
  function A(x, B) {
    let v = l.fork(), C = 0, k = 0, E = 0, d = v.end - n, S = { size: 0, start: 0, skip: 0 };
    t:
      for (let T = v.pos - x; v.pos > T; ) {
        let $ = v.size;
        if (v.id == B && $ >= 0) {
          S.size = C, S.start = k, S.skip = E, E += 4, C += 4, v.next();
          continue;
        }
        let K = v.pos - $;
        if ($ < 0 || K < T || v.start < d)
          break;
        let it = v.id >= o ? 4 : 0, Y = v.start;
        for (v.next(); v.pos > K; ) {
          if (v.size < 0)
            if (v.size == -3)
              it += 4;
            else
              break t;
          else
            v.id >= o && (it += 4);
          v.next();
        }
        k = Y, C += $, E += it;
      }
    return (B < 0 || C == x) && (S.size = C, S.start = k, S.skip = E), S.size > 4 ? S : void 0;
  }
  function m(x, B, v) {
    let { id: C, start: k, end: E, size: d } = l;
    if (l.next(), d >= 0 && C < o) {
      let S = v;
      if (d > 4) {
        let T = l.pos - (d - 4);
        for (; l.pos > T; )
          v = m(x, B, v);
      }
      B[--v] = S, B[--v] = E - x, B[--v] = k - x, B[--v] = C;
    } else
      d == -3 ? h = C : d == -4 && (u = C);
    return v;
  }
  let p = [], y = [];
  for (; l.pos > 0; )
    c(i.start || 0, i.bufferStart || 0, p, y, -1);
  let M = (t = i.length) !== null && t !== void 0 ? t : p.length ? y[0] + p[0].length : 0;
  return new Tt(a[i.topID], p.reverse(), y.reverse(), M);
}
const xc = /* @__PURE__ */ new WeakMap();
function _r(i, t) {
  if (!i.isAnonymous || t instanceof Xi || t.type != i)
    return 1;
  let e = xc.get(t);
  if (e == null) {
    e = 1;
    for (let s of t.children) {
      if (s.type != i || !(s instanceof Tt)) {
        e = 1;
        break;
      }
      e += _r(i, s);
    }
    xc.set(t, e);
  }
  return e;
}
function bh(i, t, e, s, n, r, o, l, a) {
  let h = 0;
  for (let _ = s; _ < n; _++)
    h += _r(i, t[_]);
  let u = Math.ceil(h * 1.5 / 8), c = [], f = [];
  function g(_, A, m, p, y) {
    for (let M = m; M < p; ) {
      let x = M, B = A[M], v = _r(i, _[M]);
      for (M++; M < p; M++) {
        let C = _r(i, _[M]);
        if (v + C >= u)
          break;
        v += C;
      }
      if (M == x + 1) {
        if (v > u) {
          let C = _[x];
          g(C.children, C.positions, 0, C.children.length, A[x] + y);
          continue;
        }
        c.push(_[x]);
      } else {
        let C = A[M - 1] + _[M - 1].length - B;
        c.push(bh(i, _, A, x, M, B, C, null, a));
      }
      f.push(B + y - r);
    }
  }
  return g(t, e, s, n, 0), (l || a)(c, f, o);
}
class Fi {
  constructor(t, e, s, n, r = !1, o = !1) {
    this.from = t, this.to = e, this.tree = s, this.offset = n, this.open = (r ? 1 : 0) | (o ? 2 : 0);
  }
  get openStart() {
    return (this.open & 1) > 0;
  }
  get openEnd() {
    return (this.open & 2) > 0;
  }
  static addTree(t, e = [], s = !1) {
    let n = [new Fi(0, t.length, t, 0, !1, s)];
    for (let r of e)
      r.to > t.length && n.push(r);
    return n;
  }
  static applyChanges(t, e, s = 128) {
    if (!e.length)
      return t;
    let n = [], r = 1, o = t.length ? t[0] : null;
    for (let l = 0, a = 0, h = 0; ; l++) {
      let u = l < e.length ? e[l] : null, c = u ? u.fromA : 1e9;
      if (c - a >= s)
        for (; o && o.from < c; ) {
          let f = o;
          if (a >= f.from || c <= f.to || h) {
            let g = Math.max(f.from, a) - h, _ = Math.min(f.to, c) - h;
            f = g >= _ ? null : new Fi(g, _, f.tree, f.offset + h, l > 0, !!u);
          }
          if (f && n.push(f), o.to > c)
            break;
          o = r < t.length ? t[r++] : null;
        }
      if (!u)
        break;
      a = u.toA, h = u.toA - u.toB;
    }
    return n;
  }
}
class dg {
  startParse(t, e, s) {
    return typeof t == "string" && (t = new Yw(t)), s = s ? s.length ? s.map((n) => new el(n.from, n.to)) : [new el(0, 0)] : [new el(0, t.length)], this.createParse(t, e || [], s);
  }
  parse(t, e, s) {
    let n = this.startParse(t, e, s);
    for (; ; ) {
      let r = n.advance();
      if (r)
        return r;
    }
  }
}
class Yw {
  constructor(t) {
    this.string = t;
  }
  get length() {
    return this.string.length;
  }
  chunk(t) {
    return this.string.slice(t);
  }
  get lineChunks() {
    return !1;
  }
  read(t, e) {
    return this.string.slice(t, e);
  }
}
new ot({ perNode: !0 });
let Zw = 0;
class ze {
  constructor(t, e, s) {
    this.set = t, this.base = e, this.modified = s, this.id = Zw++;
  }
  static define(t) {
    if (t != null && t.base)
      throw new Error("Can not derive from a modified tag");
    let e = new ze([], null, []);
    if (e.set.push(e), t)
      for (let s of t.set)
        e.set.push(s);
    return e;
  }
  static defineModifier() {
    let t = new Wr();
    return (e) => e.modified.indexOf(t) > -1 ? e : Wr.get(e.base || e, e.modified.concat(t).sort((s, n) => s.id - n.id));
  }
}
let tv = 0;
class Wr {
  constructor() {
    this.instances = [], this.id = tv++;
  }
  static get(t, e) {
    if (!e.length)
      return t;
    let s = e[0].instances.find((l) => l.base == t && ev(e, l.modified));
    if (s)
      return s;
    let n = [], r = new ze(n, t, e);
    for (let l of e)
      l.instances.push(r);
    let o = pg(e);
    for (let l of t.set)
      for (let a of o)
        n.push(Wr.get(l, a));
    return r;
  }
}
function ev(i, t) {
  return i.length == t.length && i.every((e, s) => e == t[s]);
}
function pg(i) {
  let t = [i];
  for (let e = 0; e < i.length; e++)
    for (let s of pg(i.slice(0, e).concat(i.slice(e + 1))))
      t.push(s);
  return t;
}
function gg(i) {
  let t = /* @__PURE__ */ Object.create(null);
  for (let e in i) {
    let s = i[e];
    Array.isArray(s) || (s = [s]);
    for (let n of e.split(" "))
      if (n) {
        let r = [], o = 2, l = n;
        for (let c = 0; ; ) {
          if (l == "..." && c > 0 && c + 3 == n.length) {
            o = 1;
            break;
          }
          let f = /^"(?:[^"\\]|\\.)*?"|[^\/!]+/.exec(l);
          if (!f)
            throw new RangeError("Invalid path: " + n);
          if (r.push(f[0] == "*" ? "" : f[0][0] == '"' ? JSON.parse(f[0]) : f[0]), c += f[0].length, c == n.length)
            break;
          let g = n[c++];
          if (c == n.length && g == "!") {
            o = 0;
            break;
          }
          if (g != "/")
            throw new RangeError("Invalid path: " + n);
          l = n.slice(c);
        }
        let a = r.length - 1, h = r[a];
        if (!h)
          throw new RangeError("Invalid path: " + n);
        let u = new iv(s, o, a > 0 ? r.slice(0, a) : null);
        t[h] = u.sort(t[h]);
      }
  }
  return mg.add(t);
}
const mg = new ot();
class iv {
  constructor(t, e, s, n) {
    this.tags = t, this.mode = e, this.context = s, this.next = n;
  }
  sort(t) {
    return !t || t.depth < this.depth ? (this.next = t, this) : (t.next = this.sort(t.next), t);
  }
  get depth() {
    return this.context ? this.context.length : 0;
  }
}
function _g(i, t) {
  let e = /* @__PURE__ */ Object.create(null);
  for (let r of i)
    if (!Array.isArray(r.tag))
      e[r.tag.id] = r.class;
    else
      for (let o of r.tag)
        e[o.id] = r.class;
  let { scope: s, all: n = null } = t || {};
  return {
    style: (r) => {
      let o = n;
      for (let l of r)
        for (let a of l.set) {
          let h = e[a.id];
          if (h) {
            o = o ? o + " " + h : h;
            break;
          }
        }
      return o;
    },
    scope: s
  };
}
function sv(i, t) {
  let e = null;
  for (let s of i) {
    let n = s.style(t);
    n && (e = e ? e + " " + n : n);
  }
  return e;
}
function nv(i, t, e, s = 0, n = i.length) {
  let r = new rv(s, Array.isArray(t) ? t : [t], e);
  r.highlightRange(i.cursor(), s, n, "", r.highlighters), r.flush(n);
}
class rv {
  constructor(t, e, s) {
    this.at = t, this.highlighters = e, this.span = s, this.class = "";
  }
  startSpan(t, e) {
    e != this.class && (this.flush(t), t > this.at && (this.at = t), this.class = e);
  }
  flush(t) {
    t > this.at && this.class && this.span(this.at, t, this.class);
  }
  highlightRange(t, e, s, n, r) {
    let { type: o, from: l, to: a } = t;
    if (l >= s || a <= e)
      return;
    o.isTop && (r = this.highlighters.filter((g) => !g.scope || g.scope(o)));
    let h = n, u = o.prop(mg), c = !1;
    for (; u; ) {
      if (!u.context || t.matchContext(u.context)) {
        let g = sv(r, u.tags);
        g && (h && (h += " "), h += g, u.mode == 1 ? n += (n ? " " : "") + g : u.mode == 0 && (c = !0));
        break;
      }
      u = u.next;
    }
    if (this.startSpan(t.from, h), c)
      return;
    let f = t.tree && t.tree.prop(ot.mounted);
    if (f && f.overlay) {
      let g = t.node.enter(f.overlay[0].from + l, 1), _ = this.highlighters.filter((m) => !m.scope || m.scope(f.tree.type)), A = t.firstChild();
      for (let m = 0, p = l; ; m++) {
        let y = m < f.overlay.length ? f.overlay[m] : null, M = y ? y.from + l : a, x = Math.max(e, p), B = Math.min(s, M);
        if (x < B && A)
          for (; t.from < B && (this.highlightRange(t, x, B, n, r), this.startSpan(Math.min(s, t.to), h), !(t.to >= M || !t.nextSibling())); )
            ;
        if (!y || M > s)
          break;
        p = y.to + l, p > e && (this.highlightRange(g.cursor(), Math.max(e, y.from + l), Math.min(s, p), n, _), this.startSpan(p, h));
      }
      A && t.parent();
    } else if (t.firstChild()) {
      do
        if (!(t.to <= e)) {
          if (t.from >= s)
            break;
          this.highlightRange(t, e, s, n, r), this.startSpan(Math.min(s, t.to), h);
        }
      while (t.nextSibling());
      t.parent();
    }
  }
}
const W = ze.define, Hn = W(), ui = W(), kc = W(ui), Oc = W(ui), ci = W(), qn = W(ci), il = W(ci), Le = W(), Mi = W(Le), De = W(), Be = W(), Ta = W(), Ns = W(Ta), Kn = W(), O = {
  comment: Hn,
  lineComment: W(Hn),
  blockComment: W(Hn),
  docComment: W(Hn),
  name: ui,
  variableName: W(ui),
  typeName: kc,
  tagName: W(kc),
  propertyName: Oc,
  attributeName: W(Oc),
  className: W(ui),
  labelName: W(ui),
  namespace: W(ui),
  macroName: W(ui),
  literal: ci,
  string: qn,
  docString: W(qn),
  character: W(qn),
  attributeValue: W(qn),
  number: il,
  integer: W(il),
  float: W(il),
  bool: W(ci),
  regexp: W(ci),
  escape: W(ci),
  color: W(ci),
  url: W(ci),
  keyword: De,
  self: W(De),
  null: W(De),
  atom: W(De),
  unit: W(De),
  modifier: W(De),
  operatorKeyword: W(De),
  controlKeyword: W(De),
  definitionKeyword: W(De),
  moduleKeyword: W(De),
  operator: Be,
  derefOperator: W(Be),
  arithmeticOperator: W(Be),
  logicOperator: W(Be),
  bitwiseOperator: W(Be),
  compareOperator: W(Be),
  updateOperator: W(Be),
  definitionOperator: W(Be),
  typeOperator: W(Be),
  controlOperator: W(Be),
  punctuation: Ta,
  separator: W(Ta),
  bracket: Ns,
  angleBracket: W(Ns),
  squareBracket: W(Ns),
  paren: W(Ns),
  brace: W(Ns),
  content: Le,
  heading: Mi,
  heading1: W(Mi),
  heading2: W(Mi),
  heading3: W(Mi),
  heading4: W(Mi),
  heading5: W(Mi),
  heading6: W(Mi),
  contentSeparator: W(Le),
  list: W(Le),
  quote: W(Le),
  emphasis: W(Le),
  strong: W(Le),
  link: W(Le),
  monospace: W(Le),
  strikethrough: W(Le),
  inserted: W(),
  deleted: W(),
  changed: W(),
  invalid: W(),
  meta: Kn,
  documentMeta: W(Kn),
  annotation: W(Kn),
  processingInstruction: W(Kn),
  definition: ze.defineModifier(),
  constant: ze.defineModifier(),
  function: ze.defineModifier(),
  standard: ze.defineModifier(),
  local: ze.defineModifier(),
  special: ze.defineModifier()
};
_g([
  { tag: O.link, class: "tok-link" },
  { tag: O.heading, class: "tok-heading" },
  { tag: O.emphasis, class: "tok-emphasis" },
  { tag: O.strong, class: "tok-strong" },
  { tag: O.keyword, class: "tok-keyword" },
  { tag: O.atom, class: "tok-atom" },
  { tag: O.bool, class: "tok-bool" },
  { tag: O.url, class: "tok-url" },
  { tag: O.labelName, class: "tok-labelName" },
  { tag: O.inserted, class: "tok-inserted" },
  { tag: O.deleted, class: "tok-deleted" },
  { tag: O.literal, class: "tok-literal" },
  { tag: O.string, class: "tok-string" },
  { tag: O.number, class: "tok-number" },
  { tag: [O.regexp, O.escape, O.special(O.string)], class: "tok-string2" },
  { tag: O.variableName, class: "tok-variableName" },
  { tag: O.local(O.variableName), class: "tok-variableName tok-local" },
  { tag: O.definition(O.variableName), class: "tok-variableName tok-definition" },
  { tag: O.special(O.variableName), class: "tok-variableName2" },
  { tag: O.definition(O.propertyName), class: "tok-propertyName tok-definition" },
  { tag: O.typeName, class: "tok-typeName" },
  { tag: O.namespace, class: "tok-namespace" },
  { tag: O.className, class: "tok-className" },
  { tag: O.macroName, class: "tok-macroName" },
  { tag: O.propertyName, class: "tok-propertyName" },
  { tag: O.operator, class: "tok-operator" },
  { tag: O.comment, class: "tok-comment" },
  { tag: O.meta, class: "tok-meta" },
  { tag: O.invalid, class: "tok-invalid" },
  { tag: O.punctuation, class: "tok-punctuation" }
]);
var sl;
const wn = /* @__PURE__ */ new ot();
function ov(i) {
  return q.define({
    combine: i ? (t) => t.concat(i) : void 0
  });
}
class Ae {
  constructor(t, e, s = []) {
    this.data = t, at.prototype.hasOwnProperty("tree") || Object.defineProperty(at.prototype, "tree", { get() {
      return Ft(this);
    } }), this.parser = e, this.extension = [
      Ti.of(this),
      at.languageData.of((n, r, o) => n.facet(Sc(n, r, o)))
    ].concat(s);
  }
  isActiveAt(t, e, s = -1) {
    return Sc(t, e, s) == this.data;
  }
  findRegions(t) {
    let e = t.facet(Ti);
    if ((e == null ? void 0 : e.data) == this.data)
      return [{ from: 0, to: t.doc.length }];
    if (!e || !e.allowsNesting)
      return [];
    let s = [], n = (r, o) => {
      if (r.prop(wn) == this.data) {
        s.push({ from: o, to: o + r.length });
        return;
      }
      let l = r.prop(ot.mounted);
      if (l) {
        if (l.tree.prop(wn) == this.data) {
          if (l.overlay)
            for (let a of l.overlay)
              s.push({ from: a.from + o, to: a.to + o });
          else
            s.push({ from: o, to: o + r.length });
          return;
        } else if (l.overlay) {
          let a = s.length;
          if (n(l.tree, l.overlay[0].from + o), s.length > a)
            return;
        }
      }
      for (let a = 0; a < r.children.length; a++) {
        let h = r.children[a];
        h instanceof Tt && n(h, r.positions[a] + o);
      }
    };
    return n(Ft(t), 0), s;
  }
  get allowsNesting() {
    return !0;
  }
}
Ae.setState = /* @__PURE__ */ rt.define();
function Sc(i, t, e) {
  let s = i.facet(Ti);
  if (!s)
    return null;
  let n = s.data;
  if (s.allowsNesting)
    for (let r = Ft(i).topNode; r; r = r.enter(t, e, zt.ExcludeBuffers))
      n = r.type.prop(wn) || n;
  return n;
}
class $r extends Ae {
  constructor(t, e) {
    super(t, e), this.parser = e;
  }
  static define(t) {
    let e = ov(t.languageData);
    return new $r(e, t.parser.configure({
      props: [wn.add((s) => s.isTop ? e : void 0)]
    }));
  }
  configure(t) {
    return new $r(this.data, this.parser.configure(t));
  }
  get allowsNesting() {
    return this.parser.hasWrappers();
  }
}
function Ft(i) {
  let t = i.field(Ae.state, !1);
  return t ? t.tree : Tt.empty;
}
class lv {
  constructor(t, e = t.length) {
    this.doc = t, this.length = e, this.cursorPos = 0, this.string = "", this.cursor = t.iter();
  }
  syncTo(t) {
    return this.string = this.cursor.next(t - this.cursorPos).value, this.cursorPos = t + this.string.length, this.cursorPos - this.string.length;
  }
  chunk(t) {
    return this.syncTo(t), this.string;
  }
  get lineChunks() {
    return !0;
  }
  read(t, e) {
    let s = this.cursorPos - this.string.length;
    return t < s || e >= this.cursorPos ? this.doc.sliceString(t, e) : this.string.slice(t - s, e - s);
  }
}
let Ls = null;
class Fr {
  constructor(t, e, s = [], n, r, o, l, a) {
    this.parser = t, this.state = e, this.fragments = s, this.tree = n, this.treeLen = r, this.viewport = o, this.skipped = l, this.scheduleOn = a, this.parse = null, this.tempSkipped = [];
  }
  static create(t, e, s) {
    return new Fr(t, e, [], Tt.empty, 0, s, [], null);
  }
  startParse() {
    return this.parser.startParse(new lv(this.state.doc), this.fragments);
  }
  work(t, e) {
    return e != null && e >= this.state.doc.length && (e = void 0), this.tree != Tt.empty && this.isDone(e != null ? e : this.state.doc.length) ? (this.takeTree(), !0) : this.withContext(() => {
      var s;
      if (typeof t == "number") {
        let n = Date.now() + t;
        t = () => Date.now() > n;
      }
      for (this.parse || (this.parse = this.startParse()), e != null && (this.parse.stoppedAt == null || this.parse.stoppedAt > e) && e < this.state.doc.length && this.parse.stopAt(e); ; ) {
        let n = this.parse.advance();
        if (n)
          if (this.fragments = this.withoutTempSkipped(Fi.addTree(n, this.fragments, this.parse.stoppedAt != null)), this.treeLen = (s = this.parse.stoppedAt) !== null && s !== void 0 ? s : this.state.doc.length, this.tree = n, this.parse = null, this.treeLen < (e != null ? e : this.state.doc.length))
            this.parse = this.startParse();
          else
            return !0;
        if (t())
          return !1;
      }
    });
  }
  takeTree() {
    let t, e;
    this.parse && (t = this.parse.parsedPos) >= this.treeLen && ((this.parse.stoppedAt == null || this.parse.stoppedAt > t) && this.parse.stopAt(t), this.withContext(() => {
      for (; !(e = this.parse.advance()); )
        ;
    }), this.treeLen = t, this.tree = e, this.fragments = this.withoutTempSkipped(Fi.addTree(this.tree, this.fragments, !0)), this.parse = null);
  }
  withContext(t) {
    let e = Ls;
    Ls = this;
    try {
      return t();
    } finally {
      Ls = e;
    }
  }
  withoutTempSkipped(t) {
    for (let e; e = this.tempSkipped.pop(); )
      t = Cc(t, e.from, e.to);
    return t;
  }
  changes(t, e) {
    let { fragments: s, tree: n, treeLen: r, viewport: o, skipped: l } = this;
    if (this.takeTree(), !t.empty) {
      let a = [];
      if (t.iterChangedRanges((h, u, c, f) => a.push({ fromA: h, toA: u, fromB: c, toB: f })), s = Fi.applyChanges(s, a), n = Tt.empty, r = 0, o = { from: t.mapPos(o.from, -1), to: t.mapPos(o.to, 1) }, this.skipped.length) {
        l = [];
        for (let h of this.skipped) {
          let u = t.mapPos(h.from, 1), c = t.mapPos(h.to, -1);
          u < c && l.push({ from: u, to: c });
        }
      }
    }
    return new Fr(this.parser, e, s, n, r, o, l, this.scheduleOn);
  }
  updateViewport(t) {
    if (this.viewport.from == t.from && this.viewport.to == t.to)
      return !1;
    this.viewport = t;
    let e = this.skipped.length;
    for (let s = 0; s < this.skipped.length; s++) {
      let { from: n, to: r } = this.skipped[s];
      n < t.to && r > t.from && (this.fragments = Cc(this.fragments, n, r), this.skipped.splice(s--, 1));
    }
    return this.skipped.length >= e ? !1 : (this.reset(), !0);
  }
  reset() {
    this.parse && (this.takeTree(), this.parse = null);
  }
  skipUntilInView(t, e) {
    this.skipped.push({ from: t, to: e });
  }
  static getSkippingParser(t) {
    return new class extends dg {
      createParse(e, s, n) {
        let r = n[0].from, o = n[n.length - 1].to;
        return {
          parsedPos: r,
          advance() {
            let a = Ls;
            if (a) {
              for (let h of n)
                a.tempSkipped.push(h);
              t && (a.scheduleOn = a.scheduleOn ? Promise.all([a.scheduleOn, t]) : t);
            }
            return this.parsedPos = o, new Tt(ne.none, [], [], o - r);
          },
          stoppedAt: null,
          stopAt() {
          }
        };
      }
    }();
  }
  isDone(t) {
    t = Math.min(t, this.state.doc.length);
    let e = this.fragments;
    return this.treeLen >= t && e.length && e[0].from == 0 && e[0].to >= t;
  }
  static get() {
    return Ls;
  }
}
function Cc(i, t, e) {
  return Fi.applyChanges(i, [{ fromA: t, toA: e, fromB: t, toB: e }]);
}
class vs {
  constructor(t) {
    this.context = t, this.tree = t.tree;
  }
  apply(t) {
    if (!t.docChanged && this.tree == this.context.tree)
      return this;
    let e = this.context.changes(t.changes, t.state), s = this.context.treeLen == t.startState.doc.length ? void 0 : Math.max(t.changes.mapPos(this.context.treeLen), e.viewport.to);
    return e.work(20, s) || e.takeTree(), new vs(e);
  }
  static init(t) {
    let e = Math.min(3e3, t.doc.length), s = Fr.create(t.facet(Ti).parser, t, { from: 0, to: e });
    return s.work(20, e) || s.takeTree(), new vs(s);
  }
}
Ae.state = /* @__PURE__ */ Vt.define({
  create: vs.init,
  update(i, t) {
    for (let e of t.effects)
      if (e.is(Ae.setState))
        return e.value;
    return t.startState.facet(Ti) != t.state.facet(Ti) ? vs.init(t.state) : i.apply(t);
  }
});
let bg = (i) => {
  let t = setTimeout(() => i(), 500);
  return () => clearTimeout(t);
};
typeof requestIdleCallback != "undefined" && (bg = (i) => {
  let t = -1, e = setTimeout(() => {
    t = requestIdleCallback(i, { timeout: 500 - 100 });
  }, 100);
  return () => t < 0 ? clearTimeout(e) : cancelIdleCallback(t);
});
const nl = typeof navigator != "undefined" && ((sl = navigator.scheduling) === null || sl === void 0 ? void 0 : sl.isInputPending) ? () => navigator.scheduling.isInputPending() : null, av = /* @__PURE__ */ At.fromClass(class {
  constructor(t) {
    this.view = t, this.working = null, this.workScheduled = 0, this.chunkEnd = -1, this.chunkBudget = -1, this.work = this.work.bind(this), this.scheduleWork();
  }
  update(t) {
    let e = this.view.state.field(Ae.state).context;
    (e.updateViewport(t.view.viewport) || this.view.viewport.to > e.treeLen) && this.scheduleWork(), t.docChanged && (this.view.hasFocus && (this.chunkBudget += 50), this.scheduleWork()), this.checkAsyncSchedule(e);
  }
  scheduleWork() {
    if (this.working)
      return;
    let { state: t } = this.view, e = t.field(Ae.state);
    (e.tree != e.context.tree || !e.context.isDone(t.doc.length)) && (this.working = bg(this.work));
  }
  work(t) {
    this.working = null;
    let e = Date.now();
    if (this.chunkEnd < e && (this.chunkEnd < 0 || this.view.hasFocus) && (this.chunkEnd = e + 3e4, this.chunkBudget = 3e3), this.chunkBudget <= 0)
      return;
    let { state: s, viewport: { to: n } } = this.view, r = s.field(Ae.state);
    if (r.tree == r.context.tree && r.context.isDone(n + 1e5))
      return;
    let o = Date.now() + Math.min(this.chunkBudget, 100, t && !nl ? Math.max(25, t.timeRemaining() - 5) : 1e9), l = r.context.treeLen < n && s.doc.length > n + 1e3, a = r.context.work(() => nl && nl() || Date.now() > o, n + (l ? 0 : 1e5));
    this.chunkBudget -= Date.now() - e, (a || this.chunkBudget <= 0) && (r.context.takeTree(), this.view.dispatch({ effects: Ae.setState.of(new vs(r.context)) })), this.chunkBudget > 0 && !(a && !l) && this.scheduleWork(), this.checkAsyncSchedule(r.context);
  }
  checkAsyncSchedule(t) {
    t.scheduleOn && (this.workScheduled++, t.scheduleOn.then(() => this.scheduleWork()).catch((e) => ge(this.view.state, e)).then(() => this.workScheduled--), t.scheduleOn = null);
  }
  destroy() {
    this.working && this.working();
  }
  isWorking() {
    return !!(this.working || this.workScheduled > 0);
  }
}, {
  eventHandlers: { focus() {
    this.scheduleWork();
  } }
}), Ti = /* @__PURE__ */ q.define({
  combine(i) {
    return i.length ? i[0] : null;
  },
  enables: [Ae.state, av]
});
class hv {
  constructor(t, e = []) {
    this.language = t, this.support = e, this.extension = [t, e];
  }
}
const uv = /* @__PURE__ */ q.define(), xo = /* @__PURE__ */ q.define({
  combine: (i) => {
    if (!i.length)
      return "  ";
    if (!/^(?: +|\t+)$/.test(i[0]))
      throw new Error("Invalid indent unit: " + JSON.stringify(i[0]));
    return i[0];
  }
});
function Vr(i) {
  let t = i.facet(xo);
  return t.charCodeAt(0) == 9 ? i.tabSize * t.length : t.length;
}
function vn(i, t) {
  let e = "", s = i.tabSize;
  if (i.facet(xo).charCodeAt(0) == 9)
    for (; t >= s; )
      e += "	", t -= s;
  for (let n = 0; n < t; n++)
    e += " ";
  return e;
}
function yh(i, t) {
  i instanceof at && (i = new ko(i));
  for (let s of i.state.facet(uv)) {
    let n = s(i, t);
    if (n != null)
      return n;
  }
  let e = Ft(i.state);
  return e ? cv(i, e, t) : null;
}
class ko {
  constructor(t, e = {}) {
    this.state = t, this.options = e, this.unit = Vr(t);
  }
  lineAt(t, e = 1) {
    let s = this.state.doc.lineAt(t), { simulateBreak: n, simulateDoubleBreak: r } = this.options;
    return n != null && n >= s.from && n <= s.to ? r && n == t ? { text: "", from: t } : (e < 0 ? n < t : n <= t) ? { text: s.text.slice(n - s.from), from: n } : { text: s.text.slice(0, n - s.from), from: s.from } : s;
  }
  textAfterPos(t, e = 1) {
    if (this.options.simulateDoubleBreak && t == this.options.simulateBreak)
      return "";
    let { text: s, from: n } = this.lineAt(t, e);
    return s.slice(t - n, Math.min(s.length, t + 100 - n));
  }
  column(t, e = 1) {
    let { text: s, from: n } = this.lineAt(t, e), r = this.countColumn(s, t - n), o = this.options.overrideIndentation ? this.options.overrideIndentation(n) : -1;
    return o > -1 && (r += o - this.countColumn(s, s.search(/\S|$/))), r;
  }
  countColumn(t, e = t.length) {
    return Sn(t, this.state.tabSize, e);
  }
  lineIndent(t, e = 1) {
    let { text: s, from: n } = this.lineAt(t, e), r = this.options.overrideIndentation;
    if (r) {
      let o = r(n);
      if (o > -1)
        return o;
    }
    return this.countColumn(s, s.search(/\S|$/));
  }
  get simulatedBreak() {
    return this.options.simulateBreak || null;
  }
}
const yg = /* @__PURE__ */ new ot();
function cv(i, t, e) {
  return wg(t.resolveInner(e).enterUnfinishedNodesBefore(e), e, i);
}
function fv(i) {
  return i.pos == i.options.simulateBreak && i.options.simulateDoubleBreak;
}
function dv(i) {
  let t = i.type.prop(yg);
  if (t)
    return t;
  let e = i.firstChild, s;
  if (e && (s = e.type.prop(ot.closedBy))) {
    let n = i.lastChild, r = n && s.indexOf(n.name) > -1;
    return (o) => _v(o, !0, 1, void 0, r && !fv(o) ? n.from : void 0);
  }
  return i.parent == null ? pv : null;
}
function wg(i, t, e) {
  for (; i; i = i.parent) {
    let s = dv(i);
    if (s)
      return s(wh.create(e, t, i));
  }
  return null;
}
function pv() {
  return 0;
}
class wh extends ko {
  constructor(t, e, s) {
    super(t.state, t.options), this.base = t, this.pos = e, this.node = s;
  }
  static create(t, e, s) {
    return new wh(t, e, s);
  }
  get textAfter() {
    return this.textAfterPos(this.pos);
  }
  get baseIndent() {
    let t = this.state.doc.lineAt(this.node.from);
    for (; ; ) {
      let e = this.node.resolve(t.from);
      for (; e.parent && e.parent.from == e.from; )
        e = e.parent;
      if (gv(e, this.node))
        break;
      t = this.state.doc.lineAt(e.from);
    }
    return this.lineIndent(t.from);
  }
  continue() {
    let t = this.node.parent;
    return t ? wg(t, this.pos, this.base) : 0;
  }
}
function gv(i, t) {
  for (let e = t; e; e = e.parent)
    if (i == e)
      return !0;
  return !1;
}
function mv(i) {
  let t = i.node, e = t.childAfter(t.from), s = t.lastChild;
  if (!e)
    return null;
  let n = i.options.simulateBreak, r = i.state.doc.lineAt(e.from), o = n == null || n <= r.from ? r.to : Math.min(r.to, n);
  for (let l = e.to; ; ) {
    let a = t.childAfter(l);
    if (!a || a == s)
      return null;
    if (!a.type.isSkipped)
      return a.from < o ? e : null;
    l = a.to;
  }
}
function _v(i, t, e, s, n) {
  let r = i.textAfter, o = r.match(/^\s*/)[0].length, l = s && r.slice(o, o + s.length) == s || n == i.pos + o, a = t ? mv(i) : null;
  return a ? l ? i.column(a.from) : i.column(a.to) : i.baseIndent + (l ? 0 : i.unit * e);
}
function bv({ except: i, units: t = 1 } = {}) {
  return (e) => {
    let s = i && i.test(e.textAfter);
    return e.baseIndent + (s ? 0 : t * e.unit);
  };
}
const yv = 200;
function wv() {
  return at.transactionFilter.of((i) => {
    if (!i.docChanged || !i.isUserEvent("input.type") && !i.isUserEvent("input.complete"))
      return i;
    let t = i.startState.languageDataAt("indentOnInput", i.startState.selection.main.head);
    if (!t.length)
      return i;
    let e = i.newDoc, { head: s } = i.newSelection.main, n = e.lineAt(s);
    if (s > n.from + yv)
      return i;
    let r = e.sliceString(n.from, s);
    if (!t.some((h) => h.test(r)))
      return i;
    let { state: o } = i, l = -1, a = [];
    for (let { head: h } of o.selection.ranges) {
      let u = o.doc.lineAt(h);
      if (u.from == l)
        continue;
      l = u.from;
      let c = yh(o, u.from);
      if (c == null)
        continue;
      let f = /^\s*/.exec(u.text)[0], g = vn(o, c);
      f != g && a.push({ from: u.from, to: u.from + f.length, insert: g });
    }
    return a.length ? [i, { changes: a, sequential: !0 }] : i;
  });
}
const vv = /* @__PURE__ */ q.define(), vg = /* @__PURE__ */ new ot();
function xv(i) {
  let t = i.firstChild, e = i.lastChild;
  return t && t.to < e.from ? { from: t.to, to: e.type.isError ? i.to : e.from } : null;
}
function kv(i, t, e) {
  let s = Ft(i);
  if (s.length < e)
    return null;
  let n = s.resolveInner(e), r = null;
  for (let o = n; o; o = o.parent) {
    if (o.to <= e || o.from > e)
      continue;
    if (r && o.from < t)
      break;
    let l = o.type.prop(vg);
    if (l && (o.to < s.length - 50 || s.length == i.doc.length || !Ov(o))) {
      let a = l(o, i);
      a && a.from <= e && a.from >= t && a.to > e && (r = a);
    }
  }
  return r;
}
function Ov(i) {
  let t = i.lastChild;
  return t && t.to == i.to && t.type.isError;
}
function Ur(i, t, e) {
  for (let s of i.facet(vv)) {
    let n = s(i, t, e);
    if (n)
      return n;
  }
  return kv(i, t, e);
}
function xg(i, t) {
  let e = t.mapPos(i.from, 1), s = t.mapPos(i.to, -1);
  return e >= s ? void 0 : { from: e, to: s };
}
const Oo = /* @__PURE__ */ rt.define({ map: xg }), An = /* @__PURE__ */ rt.define({ map: xg });
function kg(i) {
  let t = [];
  for (let { head: e } of i.state.selection.ranges)
    t.some((s) => s.from <= e && s.to >= e) || t.push(i.lineBlockAt(e));
  return t;
}
const qi = /* @__PURE__ */ Vt.define({
  create() {
    return X.none;
  },
  update(i, t) {
    i = i.map(t.changes);
    for (let e of t.effects)
      e.is(Oo) && !Sv(i, e.value.from, e.value.to) ? i = i.update({ add: [Ac.range(e.value.from, e.value.to)] }) : e.is(An) && (i = i.update({
        filter: (s, n) => e.value.from != s || e.value.to != n,
        filterFrom: e.value.from,
        filterTo: e.value.to
      }));
    if (t.selection) {
      let e = !1, { head: s } = t.selection.main;
      i.between(s, s, (n, r) => {
        n < s && r > s && (e = !0);
      }), e && (i = i.update({
        filterFrom: s,
        filterTo: s,
        filter: (n, r) => r <= s || n >= s
      }));
    }
    return i;
  },
  provide: (i) => H.decorations.from(i),
  toJSON(i, t) {
    let e = [];
    return i.between(0, t.doc.length, (s, n) => {
      e.push(s, n);
    }), e;
  },
  fromJSON(i) {
    if (!Array.isArray(i) || i.length % 2)
      throw new RangeError("Invalid JSON for fold state");
    let t = [];
    for (let e = 0; e < i.length; ) {
      let s = i[e++], n = i[e++];
      if (typeof s != "number" || typeof n != "number")
        throw new RangeError("Invalid JSON for fold state");
      t.push(Ac.range(s, n));
    }
    return X.set(t, !0);
  }
});
function jr(i, t, e) {
  var s;
  let n = null;
  return (s = i.field(qi, !1)) === null || s === void 0 || s.between(t, e, (r, o) => {
    (!n || n.from > r) && (n = { from: r, to: o });
  }), n;
}
function Sv(i, t, e) {
  let s = !1;
  return i.between(t, t, (n, r) => {
    n == t && r == e && (s = !0);
  }), s;
}
function Og(i, t) {
  return i.field(qi, !1) ? t : t.concat(rt.appendConfig.of(Ag()));
}
const Cv = (i) => {
  for (let t of kg(i)) {
    let e = Ur(i.state, t.from, t.to);
    if (e)
      return i.dispatch({ effects: Og(i.state, [Oo.of(e), Sg(i, e)]) }), !0;
  }
  return !1;
}, Av = (i) => {
  if (!i.state.field(qi, !1))
    return !1;
  let t = [];
  for (let e of kg(i)) {
    let s = jr(i.state, e.from, e.to);
    s && t.push(An.of(s), Sg(i, s, !1));
  }
  return t.length && i.dispatch({ effects: t }), t.length > 0;
};
function Sg(i, t, e = !0) {
  let s = i.state.doc.lineAt(t.from).number, n = i.state.doc.lineAt(t.to).number;
  return H.announce.of(`${i.state.phrase(e ? "Folded lines" : "Unfolded lines")} ${s} ${i.state.phrase("to")} ${n}.`);
}
const Tv = (i) => {
  let { state: t } = i, e = [];
  for (let s = 0; s < t.doc.length; ) {
    let n = i.lineBlockAt(s), r = Ur(t, n.from, n.to);
    r && e.push(Oo.of(r)), s = (r ? i.lineBlockAt(r.to) : n).to + 1;
  }
  return e.length && i.dispatch({ effects: Og(i.state, e) }), !!e.length;
}, Pv = (i) => {
  let t = i.state.field(qi, !1);
  if (!t || !t.size)
    return !1;
  let e = [];
  return t.between(0, i.state.doc.length, (s, n) => {
    e.push(An.of({ from: s, to: n }));
  }), i.dispatch({ effects: e }), !0;
}, Ev = [
  { key: "Ctrl-Shift-[", mac: "Cmd-Alt-[", run: Cv },
  { key: "Ctrl-Shift-]", mac: "Cmd-Alt-]", run: Av },
  { key: "Ctrl-Alt-[", run: Tv },
  { key: "Ctrl-Alt-]", run: Pv }
], Mv = {
  placeholderDOM: null,
  placeholderText: "\u2026"
}, Cg = /* @__PURE__ */ q.define({
  combine(i) {
    return si(i, Mv);
  }
});
function Ag(i) {
  let t = [qi, Bv];
  return i && t.push(Cg.of(i)), t;
}
const Ac = /* @__PURE__ */ X.replace({ widget: /* @__PURE__ */ new class extends ni {
  toDOM(i) {
    let { state: t } = i, e = t.facet(Cg), s = (r) => {
      let o = i.lineBlockAt(i.posAtDOM(r.target)), l = jr(i.state, o.from, o.to);
      l && i.dispatch({ effects: An.of(l) }), r.preventDefault();
    };
    if (e.placeholderDOM)
      return e.placeholderDOM(i, s);
    let n = document.createElement("span");
    return n.textContent = e.placeholderText, n.setAttribute("aria-label", t.phrase("folded code")), n.title = t.phrase("unfold"), n.className = "cm-foldPlaceholder", n.onclick = s, n;
  }
}() }), Rv = {
  openText: "\u2304",
  closedText: "\u203A",
  markerDOM: null,
  domEventHandlers: {},
  foldingChanged: () => !1
};
class rl extends ei {
  constructor(t, e) {
    super(), this.config = t, this.open = e;
  }
  eq(t) {
    return this.config == t.config && this.open == t.open;
  }
  toDOM(t) {
    if (this.config.markerDOM)
      return this.config.markerDOM(this.open);
    let e = document.createElement("span");
    return e.textContent = this.open ? this.config.openText : this.config.closedText, e.title = t.state.phrase(this.open ? "Fold line" : "Unfold line"), e;
  }
}
function Dv(i = {}) {
  let t = Object.assign(Object.assign({}, Rv), i), e = new rl(t, !0), s = new rl(t, !1), n = At.fromClass(class {
    constructor(o) {
      this.from = o.viewport.from, this.markers = this.buildMarkers(o);
    }
    update(o) {
      (o.docChanged || o.viewportChanged || o.startState.facet(Ti) != o.state.facet(Ti) || o.startState.field(qi, !1) != o.state.field(qi, !1) || Ft(o.startState) != Ft(o.state) || t.foldingChanged(o)) && (this.markers = this.buildMarkers(o.view));
    }
    buildMarkers(o) {
      let l = new Oi();
      for (let a of o.viewportLineBlocks) {
        let h = jr(o.state, a.from, a.to) ? s : Ur(o.state, a.from, a.to) ? e : null;
        h && l.add(a.from, a.from, h);
      }
      return l.finish();
    }
  }), { domEventHandlers: r } = t;
  return [
    n,
    Qw({
      class: "cm-foldGutter",
      markers(o) {
        var l;
        return ((l = o.plugin(n)) === null || l === void 0 ? void 0 : l.markers) || ft.empty;
      },
      initialSpacer() {
        return new rl(t, !1);
      },
      domEventHandlers: Object.assign(Object.assign({}, r), { click: (o, l, a) => {
        if (r.click && r.click(o, l, a))
          return !0;
        let h = jr(o.state, l.from, l.to);
        if (h)
          return o.dispatch({ effects: An.of(h) }), !0;
        let u = Ur(o.state, l.from, l.to);
        return u ? (o.dispatch({ effects: Oo.of(u) }), !0) : !1;
      } })
    }),
    Ag()
  ];
}
const Bv = /* @__PURE__ */ H.baseTheme({
  ".cm-foldPlaceholder": {
    backgroundColor: "#eee",
    border: "1px solid #ddd",
    color: "#888",
    borderRadius: ".2em",
    margin: "0 1px",
    padding: "0 1px",
    cursor: "pointer"
  },
  ".cm-foldGutter span": {
    padding: "0 1px",
    cursor: "pointer"
  }
});
class Tn {
  constructor(t, e) {
    let s;
    function n(l) {
      let a = Si.newName();
      return (s || (s = /* @__PURE__ */ Object.create(null)))["." + a] = l, a;
    }
    const r = typeof e.all == "string" ? e.all : e.all ? n(e.all) : void 0, o = e.scope;
    this.scope = o instanceof Ae ? (l) => l.prop(wn) == o.data : o ? (l) => l == o : void 0, this.style = _g(t.map((l) => ({
      tag: l.tag,
      class: l.class || n(Object.assign({}, l, { tag: null }))
    })), {
      all: r
    }).style, this.module = s ? new Si(s) : null, this.themeType = e.themeType;
  }
  static define(t, e) {
    return new Tn(t, e || {});
  }
}
const Pa = /* @__PURE__ */ q.define(), Tg = /* @__PURE__ */ q.define({
  combine(i) {
    return i.length ? [i[0]] : null;
  }
});
function ol(i) {
  let t = i.facet(Pa);
  return t.length ? t : i.facet(Tg);
}
function Pg(i, t) {
  let e = [Lv], s;
  return i instanceof Tn && (i.module && e.push(H.styleModule.of(i.module)), s = i.themeType), t != null && t.fallback ? e.push(Tg.of(i)) : s ? e.push(Pa.computeN([H.darkTheme], (n) => n.facet(H.darkTheme) == (s == "dark") ? [i] : [])) : e.push(Pa.of(i)), e;
}
class Nv {
  constructor(t) {
    this.markCache = /* @__PURE__ */ Object.create(null), this.tree = Ft(t.state), this.decorations = this.buildDeco(t, ol(t.state));
  }
  update(t) {
    let e = Ft(t.state), s = ol(t.state), n = s != ol(t.startState);
    e.length < t.view.viewport.to && !n && e.type == this.tree.type ? this.decorations = this.decorations.map(t.changes) : (e != this.tree || t.viewportChanged || n) && (this.tree = e, this.decorations = this.buildDeco(t.view, s));
  }
  buildDeco(t, e) {
    if (!e || !this.tree.length)
      return X.none;
    let s = new Oi();
    for (let { from: n, to: r } of t.visibleRanges)
      nv(this.tree, e, (o, l, a) => {
        s.add(o, l, this.markCache[a] || (this.markCache[a] = X.mark({ class: a })));
      }, n, r);
    return s.finish();
  }
}
const Lv = /* @__PURE__ */ As.high(/* @__PURE__ */ At.fromClass(Nv, {
  decorations: (i) => i.decorations
})), Iv = /* @__PURE__ */ Tn.define([
  {
    tag: O.meta,
    color: "#7a757a"
  },
  {
    tag: O.link,
    textDecoration: "underline"
  },
  {
    tag: O.heading,
    textDecoration: "underline",
    fontWeight: "bold"
  },
  {
    tag: O.emphasis,
    fontStyle: "italic"
  },
  {
    tag: O.strong,
    fontWeight: "bold"
  },
  {
    tag: O.strikethrough,
    textDecoration: "line-through"
  },
  {
    tag: O.keyword,
    color: "#708"
  },
  {
    tag: [O.atom, O.bool, O.url, O.contentSeparator, O.labelName],
    color: "#219"
  },
  {
    tag: [O.literal, O.inserted],
    color: "#164"
  },
  {
    tag: [O.string, O.deleted],
    color: "#a11"
  },
  {
    tag: [O.regexp, O.escape, /* @__PURE__ */ O.special(O.string)],
    color: "#e40"
  },
  {
    tag: /* @__PURE__ */ O.definition(O.variableName),
    color: "#00f"
  },
  {
    tag: /* @__PURE__ */ O.local(O.variableName),
    color: "#30a"
  },
  {
    tag: [O.typeName, O.namespace],
    color: "#085"
  },
  {
    tag: O.className,
    color: "#167"
  },
  {
    tag: [/* @__PURE__ */ O.special(O.variableName), O.macroName],
    color: "#256"
  },
  {
    tag: /* @__PURE__ */ O.definition(O.propertyName),
    color: "#00c"
  },
  {
    tag: O.comment,
    color: "#940"
  },
  {
    tag: O.invalid,
    color: "#f00"
  }
]), Qv = /* @__PURE__ */ H.baseTheme({
  "&.cm-focused .cm-matchingBracket": { backgroundColor: "#328c8252" },
  "&.cm-focused .cm-nonmatchingBracket": { backgroundColor: "#bb555544" }
}), Eg = 1e4, Mg = "()[]{}", Rg = /* @__PURE__ */ q.define({
  combine(i) {
    return si(i, {
      afterCursor: !0,
      brackets: Mg,
      maxScanDistance: Eg,
      renderMatch: $v
    });
  }
}), zv = /* @__PURE__ */ X.mark({ class: "cm-matchingBracket" }), Wv = /* @__PURE__ */ X.mark({ class: "cm-nonmatchingBracket" });
function $v(i) {
  let t = [], e = i.matched ? zv : Wv;
  return t.push(e.range(i.start.from, i.start.to)), i.end && t.push(e.range(i.end.from, i.end.to)), t;
}
const Fv = /* @__PURE__ */ Vt.define({
  create() {
    return X.none;
  },
  update(i, t) {
    if (!t.docChanged && !t.selection)
      return i;
    let e = [], s = t.state.facet(Rg);
    for (let n of t.state.selection.ranges) {
      if (!n.empty)
        continue;
      let r = $e(t.state, n.head, -1, s) || n.head > 0 && $e(t.state, n.head - 1, 1, s) || s.afterCursor && ($e(t.state, n.head, 1, s) || n.head < t.state.doc.length && $e(t.state, n.head + 1, -1, s));
      r && (e = e.concat(s.renderMatch(r, t.state)));
    }
    return X.set(e, !0);
  },
  provide: (i) => H.decorations.from(i)
}), Vv = [
  Fv,
  Qv
];
function Uv(i = {}) {
  return [Rg.of(i), Vv];
}
function Ea(i, t, e) {
  let s = i.prop(t < 0 ? ot.openedBy : ot.closedBy);
  if (s)
    return s;
  if (i.name.length == 1) {
    let n = e.indexOf(i.name);
    if (n > -1 && n % 2 == (t < 0 ? 1 : 0))
      return [e[n + t]];
  }
  return null;
}
function $e(i, t, e, s = {}) {
  let n = s.maxScanDistance || Eg, r = s.brackets || Mg, o = Ft(i), l = o.resolveInner(t, e);
  for (let a = l; a; a = a.parent) {
    let h = Ea(a.type, e, r);
    if (h && a.from < a.to)
      return jv(i, t, e, a, h, r);
  }
  return Hv(i, t, e, o, l.type, n, r);
}
function jv(i, t, e, s, n, r) {
  let o = s.parent, l = { from: s.from, to: s.to }, a = 0, h = o == null ? void 0 : o.cursor();
  if (h && (e < 0 ? h.childBefore(s.from) : h.childAfter(s.to)))
    do
      if (e < 0 ? h.to <= s.from : h.from >= s.to) {
        if (a == 0 && n.indexOf(h.type.name) > -1 && h.from < h.to)
          return { start: l, end: { from: h.from, to: h.to }, matched: !0 };
        if (Ea(h.type, e, r))
          a++;
        else if (Ea(h.type, -e, r)) {
          if (a == 0)
            return {
              start: l,
              end: h.from == h.to ? void 0 : { from: h.from, to: h.to },
              matched: !1
            };
          a--;
        }
      }
    while (e < 0 ? h.prevSibling() : h.nextSibling());
  return { start: l, matched: !1 };
}
function Hv(i, t, e, s, n, r, o) {
  let l = e < 0 ? i.sliceDoc(t - 1, t) : i.sliceDoc(t, t + 1), a = o.indexOf(l);
  if (a < 0 || a % 2 == 0 != e > 0)
    return null;
  let h = { from: e < 0 ? t - 1 : t, to: e > 0 ? t + 1 : t }, u = i.doc.iterRange(t, e > 0 ? i.doc.length : 0), c = 0;
  for (let f = 0; !u.next().done && f <= r; ) {
    let g = u.value;
    e < 0 && (f += g.length);
    let _ = t + f * e;
    for (let A = e > 0 ? 0 : g.length - 1, m = e > 0 ? g.length : -1; A != m; A += e) {
      let p = o.indexOf(g[A]);
      if (!(p < 0 || s.resolveInner(_ + A, 1).type != n))
        if (p % 2 == 0 == e > 0)
          c++;
        else {
          if (c == 1)
            return { start: h, end: { from: _ + A, to: _ + A + 1 }, matched: p >> 1 == a >> 1 };
          c--;
        }
    }
    e > 0 && (f += g.length);
  }
  return u.done ? { start: h, matched: !1 } : null;
}
const qv = /* @__PURE__ */ Object.create(null), Tc = [ne.none], Pc = [], Kv = /* @__PURE__ */ Object.create(null);
for (let [i, t] of [
  ["variable", "variableName"],
  ["variable-2", "variableName.special"],
  ["string-2", "string.special"],
  ["def", "variableName.definition"],
  ["tag", "tagName"],
  ["attribute", "attributeName"],
  ["type", "typeName"],
  ["builtin", "variableName.standard"],
  ["qualifier", "modifier"],
  ["error", "invalid"],
  ["header", "heading"],
  ["property", "propertyName"]
])
  Kv[i] = /* @__PURE__ */ Xv(qv, t);
function ll(i, t) {
  Pc.indexOf(i) > -1 || (Pc.push(i), console.warn(t));
}
function Xv(i, t) {
  let e = null;
  for (let r of t.split(".")) {
    let o = i[r] || O[r];
    o ? typeof o == "function" ? e ? e = o(e) : ll(r, `Modifier ${r} used at start of tag`) : e ? ll(r, `Tag ${r} used as modifier`) : e = o : ll(r, `Unknown highlighting tag ${r}`);
  }
  if (!e)
    return 0;
  let s = t.replace(/ /g, "_"), n = ne.define({
    id: Tc.length,
    name: s,
    props: [gg({ [s]: e })]
  });
  return Tc.push(n), n.id;
}
const Gv = (i) => {
  let t = xh(i.state);
  return t.line ? Jv(i) : t.block ? Zv(i) : !1;
};
function vh(i, t) {
  return ({ state: e, dispatch: s }) => {
    if (e.readOnly)
      return !1;
    let n = i(t, e);
    return n ? (s(e.update(n)), !0) : !1;
  };
}
const Jv = /* @__PURE__ */ vh(ix, 0), Yv = /* @__PURE__ */ vh(Dg, 0), Zv = /* @__PURE__ */ vh((i, t) => Dg(i, t, ex(t)), 0);
function xh(i, t = i.selection.main.head) {
  let e = i.languageDataAt("commentTokens", t);
  return e.length ? e[0] : {};
}
const Is = 50;
function tx(i, { open: t, close: e }, s, n) {
  let r = i.sliceDoc(s - Is, s), o = i.sliceDoc(n, n + Is), l = /\s*$/.exec(r)[0].length, a = /^\s*/.exec(o)[0].length, h = r.length - l;
  if (r.slice(h - t.length, h) == t && o.slice(a, a + e.length) == e)
    return {
      open: { pos: s - l, margin: l && 1 },
      close: { pos: n + a, margin: a && 1 }
    };
  let u, c;
  n - s <= 2 * Is ? u = c = i.sliceDoc(s, n) : (u = i.sliceDoc(s, s + Is), c = i.sliceDoc(n - Is, n));
  let f = /^\s*/.exec(u)[0].length, g = /\s*$/.exec(c)[0].length, _ = c.length - g - e.length;
  return u.slice(f, f + t.length) == t && c.slice(_, _ + e.length) == e ? {
    open: {
      pos: s + f + t.length,
      margin: /\s/.test(u.charAt(f + t.length)) ? 1 : 0
    },
    close: {
      pos: n - g - e.length,
      margin: /\s/.test(c.charAt(_ - 1)) ? 1 : 0
    }
  } : null;
}
function ex(i) {
  let t = [];
  for (let e of i.selection.ranges) {
    let s = i.doc.lineAt(e.from), n = e.to <= s.to ? s : i.doc.lineAt(e.to), r = t.length - 1;
    r >= 0 && t[r].to > s.from ? t[r].to = n.to : t.push({ from: s.from, to: n.to });
  }
  return t;
}
function Dg(i, t, e = t.selection.ranges) {
  let s = e.map((r) => xh(t, r.from).block);
  if (!s.every((r) => r))
    return null;
  let n = e.map((r, o) => tx(t, s[o], r.from, r.to));
  if (i != 2 && !n.every((r) => r))
    return { changes: t.changes(e.map((r, o) => n[o] ? [] : [{ from: r.from, insert: s[o].open + " " }, { from: r.to, insert: " " + s[o].close }])) };
  if (i != 1 && n.some((r) => r)) {
    let r = [];
    for (let o = 0, l; o < n.length; o++)
      if (l = n[o]) {
        let a = s[o], { open: h, close: u } = l;
        r.push({ from: h.pos - a.open.length, to: h.pos + h.margin }, { from: u.pos - u.margin, to: u.pos + a.close.length });
      }
    return { changes: r };
  }
  return null;
}
function ix(i, t, e = t.selection.ranges) {
  let s = [], n = -1;
  for (let { from: r, to: o } of e) {
    let l = s.length, a = 1e9;
    for (let h = r; h <= o; ) {
      let u = t.doc.lineAt(h);
      if (u.from > n && (r == o || o > u.from)) {
        n = u.from;
        let c = xh(t, h).line;
        if (!c)
          continue;
        let f = /^\s*/.exec(u.text)[0].length, g = f == u.length, _ = u.text.slice(f, f + c.length) == c ? f : -1;
        f < u.text.length && f < a && (a = f), s.push({ line: u, comment: _, token: c, indent: f, empty: g, single: !1 });
      }
      h = u.to + 1;
    }
    if (a < 1e9)
      for (let h = l; h < s.length; h++)
        s[h].indent < s[h].line.text.length && (s[h].indent = a);
    s.length == l + 1 && (s[l].single = !0);
  }
  if (i != 2 && s.some((r) => r.comment < 0 && (!r.empty || r.single))) {
    let r = [];
    for (let { line: l, token: a, indent: h, empty: u, single: c } of s)
      (c || !u) && r.push({ from: l.from + h, insert: a + " " });
    let o = t.changes(r);
    return { changes: o, selection: t.selection.map(o, 1) };
  } else if (i != 1 && s.some((r) => r.comment >= 0)) {
    let r = [];
    for (let { line: o, comment: l, token: a } of s)
      if (l >= 0) {
        let h = o.from + l, u = h + a.length;
        o.text[u - o.from] == " " && u++, r.push({ from: h, to: u });
      }
    return { changes: r };
  }
  return null;
}
const Ma = /* @__PURE__ */ Ki.define(), sx = /* @__PURE__ */ Ki.define(), nx = /* @__PURE__ */ q.define(), Bg = /* @__PURE__ */ q.define({
  combine(i) {
    return si(i, {
      minDepth: 100,
      newGroupDelay: 500
    }, { minDepth: Math.max, newGroupDelay: Math.min });
  }
});
function rx(i) {
  let t = 0;
  return i.iterChangedRanges((e, s) => t = s), t;
}
const Ng = /* @__PURE__ */ Vt.define({
  create() {
    return Fe.empty;
  },
  update(i, t) {
    let e = t.state.facet(Bg), s = t.annotation(Ma);
    if (s) {
      let a = t.docChanged ? R.single(rx(t.changes)) : void 0, h = ie.fromTransaction(t, a), u = s.side, c = u == 0 ? i.undone : i.done;
      return h ? c = Hr(c, c.length, e.minDepth, h) : c = Qg(c, t.startState.selection), new Fe(u == 0 ? s.rest : c, u == 0 ? c : s.rest);
    }
    let n = t.annotation(sx);
    if ((n == "full" || n == "before") && (i = i.isolate()), t.annotation(Et.addToHistory) === !1)
      return t.changes.empty ? i : i.addMapping(t.changes.desc);
    let r = ie.fromTransaction(t), o = t.annotation(Et.time), l = t.annotation(Et.userEvent);
    return r ? i = i.addChanges(r, o, l, e.newGroupDelay, e.minDepth) : t.selection && (i = i.addSelection(t.startState.selection, o, l, e.newGroupDelay)), (n == "full" || n == "after") && (i = i.isolate()), i;
  },
  toJSON(i) {
    return { done: i.done.map((t) => t.toJSON()), undone: i.undone.map((t) => t.toJSON()) };
  },
  fromJSON(i) {
    return new Fe(i.done.map(ie.fromJSON), i.undone.map(ie.fromJSON));
  }
});
function ox(i = {}) {
  return [
    Ng,
    Bg.of(i),
    H.domEventHandlers({
      beforeinput(t, e) {
        let s = t.inputType == "historyUndo" ? Lg : t.inputType == "historyRedo" ? Ra : null;
        return s ? (t.preventDefault(), s(e)) : !1;
      }
    })
  ];
}
function So(i, t) {
  return function({ state: e, dispatch: s }) {
    if (!t && e.readOnly)
      return !1;
    let n = e.field(Ng, !1);
    if (!n)
      return !1;
    let r = n.pop(i, e, t);
    return r ? (s(r), !0) : !1;
  };
}
const Lg = /* @__PURE__ */ So(0, !1), Ra = /* @__PURE__ */ So(1, !1), lx = /* @__PURE__ */ So(0, !0), ax = /* @__PURE__ */ So(1, !0);
class ie {
  constructor(t, e, s, n, r) {
    this.changes = t, this.effects = e, this.mapped = s, this.startSelection = n, this.selectionsAfter = r;
  }
  setSelAfter(t) {
    return new ie(this.changes, this.effects, this.mapped, this.startSelection, t);
  }
  toJSON() {
    var t, e, s;
    return {
      changes: (t = this.changes) === null || t === void 0 ? void 0 : t.toJSON(),
      mapped: (e = this.mapped) === null || e === void 0 ? void 0 : e.toJSON(),
      startSelection: (s = this.startSelection) === null || s === void 0 ? void 0 : s.toJSON(),
      selectionsAfter: this.selectionsAfter.map((n) => n.toJSON())
    };
  }
  static fromJSON(t) {
    return new ie(t.changes && Pt.fromJSON(t.changes), [], t.mapped && Ve.fromJSON(t.mapped), t.startSelection && R.fromJSON(t.startSelection), t.selectionsAfter.map(R.fromJSON));
  }
  static fromTransaction(t, e) {
    let s = we;
    for (let n of t.startState.facet(nx)) {
      let r = n(t);
      r.length && (s = s.concat(r));
    }
    return !s.length && t.changes.empty ? null : new ie(t.changes.invert(t.startState.doc), s, void 0, e || t.startState.selection, we);
  }
  static selection(t) {
    return new ie(void 0, we, void 0, void 0, t);
  }
}
function Hr(i, t, e, s) {
  let n = t + 1 > e + 20 ? t - e - 1 : 0, r = i.slice(n, t);
  return r.push(s), r;
}
function hx(i, t) {
  let e = [], s = !1;
  return i.iterChangedRanges((n, r) => e.push(n, r)), t.iterChangedRanges((n, r, o, l) => {
    for (let a = 0; a < e.length; ) {
      let h = e[a++], u = e[a++];
      l >= h && o <= u && (s = !0);
    }
  }), s;
}
function ux(i, t) {
  return i.ranges.length == t.ranges.length && i.ranges.filter((e, s) => e.empty != t.ranges[s].empty).length === 0;
}
function Ig(i, t) {
  return i.length ? t.length ? i.concat(t) : i : t;
}
const we = [], cx = 200;
function Qg(i, t) {
  if (i.length) {
    let e = i[i.length - 1], s = e.selectionsAfter.slice(Math.max(0, e.selectionsAfter.length - cx));
    return s.length && s[s.length - 1].eq(t) ? i : (s.push(t), Hr(i, i.length - 1, 1e9, e.setSelAfter(s)));
  } else
    return [ie.selection([t])];
}
function fx(i) {
  let t = i[i.length - 1], e = i.slice();
  return e[i.length - 1] = t.setSelAfter(t.selectionsAfter.slice(0, t.selectionsAfter.length - 1)), e;
}
function al(i, t) {
  if (!i.length)
    return i;
  let e = i.length, s = we;
  for (; e; ) {
    let n = dx(i[e - 1], t, s);
    if (n.changes && !n.changes.empty || n.effects.length) {
      let r = i.slice(0, e);
      return r[e - 1] = n, r;
    } else
      t = n.mapped, e--, s = n.selectionsAfter;
  }
  return s.length ? [ie.selection(s)] : we;
}
function dx(i, t, e) {
  let s = Ig(i.selectionsAfter.length ? i.selectionsAfter.map((l) => l.map(t)) : we, e);
  if (!i.changes)
    return ie.selection(s);
  let n = i.changes.map(t), r = t.mapDesc(i.changes, !0), o = i.mapped ? i.mapped.composeDesc(r) : r;
  return new ie(n, rt.mapEffects(i.effects, t), o, i.startSelection.map(r), s);
}
const px = /^(input\.type|delete)($|\.)/;
class Fe {
  constructor(t, e, s = 0, n = void 0) {
    this.done = t, this.undone = e, this.prevTime = s, this.prevUserEvent = n;
  }
  isolate() {
    return this.prevTime ? new Fe(this.done, this.undone) : this;
  }
  addChanges(t, e, s, n, r) {
    let o = this.done, l = o[o.length - 1];
    return l && l.changes && !l.changes.empty && t.changes && (!s || px.test(s)) && (!l.selectionsAfter.length && e - this.prevTime < n && hx(l.changes, t.changes) || s == "input.type.compose") ? o = Hr(o, o.length - 1, r, new ie(t.changes.compose(l.changes), Ig(t.effects, l.effects), l.mapped, l.startSelection, we)) : o = Hr(o, o.length, r, t), new Fe(o, we, e, s);
  }
  addSelection(t, e, s, n) {
    let r = this.done.length ? this.done[this.done.length - 1].selectionsAfter : we;
    return r.length > 0 && e - this.prevTime < n && s == this.prevUserEvent && s && /^select($|\.)/.test(s) && ux(r[r.length - 1], t) ? this : new Fe(Qg(this.done, t), this.undone, e, s);
  }
  addMapping(t) {
    return new Fe(al(this.done, t), al(this.undone, t), this.prevTime, this.prevUserEvent);
  }
  pop(t, e, s) {
    let n = t == 0 ? this.done : this.undone;
    if (n.length == 0)
      return null;
    let r = n[n.length - 1];
    if (s && r.selectionsAfter.length)
      return e.update({
        selection: r.selectionsAfter[r.selectionsAfter.length - 1],
        annotations: Ma.of({ side: t, rest: fx(n) }),
        userEvent: t == 0 ? "select.undo" : "select.redo",
        scrollIntoView: !0
      });
    if (r.changes) {
      let o = n.length == 1 ? we : n.slice(0, n.length - 1);
      return r.mapped && (o = al(o, r.mapped)), e.update({
        changes: r.changes,
        selection: r.startSelection,
        effects: r.effects,
        annotations: Ma.of({ side: t, rest: o }),
        filter: !1,
        userEvent: t == 0 ? "undo" : "redo",
        scrollIntoView: !0
      });
    } else
      return null;
  }
}
Fe.empty = /* @__PURE__ */ new Fe(we, we);
const gx = [
  { key: "Mod-z", run: Lg, preventDefault: !0 },
  { key: "Mod-y", mac: "Mod-Shift-z", run: Ra, preventDefault: !0 },
  { linux: "Ctrl-Shift-z", run: Ra, preventDefault: !0 },
  { key: "Mod-u", run: lx, preventDefault: !0 },
  { key: "Alt-u", mac: "Mod-Shift-u", run: ax, preventDefault: !0 }
];
function Ts(i, t) {
  return R.create(i.ranges.map(t), i.mainIndex);
}
function qe(i, t) {
  return i.update({ selection: t, scrollIntoView: !0, userEvent: "select" });
}
function ri({ state: i, dispatch: t }, e) {
  let s = Ts(i.selection, e);
  return s.eq(i.selection) ? !1 : (t(qe(i, s)), !0);
}
function Co(i, t) {
  return R.cursor(t ? i.to : i.from);
}
function zg(i, t) {
  return ri(i, (e) => e.empty ? i.moveByChar(e, t) : Co(e, t));
}
function ve(i) {
  return i.textDirectionAt(i.state.selection.main.head) == St.LTR;
}
const Wg = (i) => zg(i, !ve(i)), $g = (i) => zg(i, ve(i));
function Fg(i, t) {
  return ri(i, (e) => e.empty ? i.moveByGroup(e, t) : Co(e, t));
}
const mx = (i) => Fg(i, !ve(i)), _x = (i) => Fg(i, ve(i));
function bx(i, t, e) {
  if (t.type.prop(e))
    return !0;
  let s = t.to - t.from;
  return s && (s > 2 || /[^\s,.;:]/.test(i.sliceDoc(t.from, t.to))) || t.firstChild;
}
function Ao(i, t, e) {
  let s = Ft(i).resolveInner(t.head), n = e ? ot.closedBy : ot.openedBy;
  for (let a = t.head; ; ) {
    let h = e ? s.childAfter(a) : s.childBefore(a);
    if (!h)
      break;
    bx(i, h, n) ? s = h : a = e ? h.to : h.from;
  }
  let r = s.type.prop(n), o, l;
  return r && (o = e ? $e(i, s.from, 1) : $e(i, s.to, -1)) && o.matched ? l = e ? o.end.to : o.end.from : l = e ? s.to : s.from, R.cursor(l, e ? -1 : 1);
}
const yx = (i) => ri(i, (t) => Ao(i.state, t, !ve(i))), wx = (i) => ri(i, (t) => Ao(i.state, t, ve(i)));
function Vg(i, t) {
  return ri(i, (e) => {
    if (!e.empty)
      return Co(e, t);
    let s = i.moveVertically(e, t);
    return s.head != e.head ? s : i.moveToLineBoundary(e, t);
  });
}
const Ug = (i) => Vg(i, !1), jg = (i) => Vg(i, !0);
function Hg(i) {
  return Math.max(i.defaultLineHeight, Math.min(i.dom.clientHeight, innerHeight) - 5);
}
function qg(i, t) {
  let { state: e } = i, s = Ts(e.selection, (l) => l.empty ? i.moveVertically(l, t, Hg(i)) : Co(l, t));
  if (s.eq(e.selection))
    return !1;
  let n = i.coordsAtPos(e.selection.main.head), r = i.scrollDOM.getBoundingClientRect(), o;
  return n && n.top > r.top && n.bottom < r.bottom && n.top - r.top <= i.scrollDOM.scrollHeight - i.scrollDOM.scrollTop - i.scrollDOM.clientHeight && (o = H.scrollIntoView(s.main.head, { y: "start", yMargin: n.top - r.top })), i.dispatch(qe(e, s), { effects: o }), !0;
}
const Ec = (i) => qg(i, !1), Da = (i) => qg(i, !0);
function To(i, t, e) {
  let s = i.lineBlockAt(t.head), n = i.moveToLineBoundary(t, e);
  if (n.head == t.head && n.head != (e ? s.to : s.from) && (n = i.moveToLineBoundary(t, e, !1)), !e && n.head == s.from && s.length) {
    let r = /^\s*/.exec(i.state.sliceDoc(s.from, Math.min(s.from + 100, s.to)))[0].length;
    r && t.head != s.from + r && (n = R.cursor(s.from + r));
  }
  return n;
}
const Mc = (i) => ri(i, (t) => To(i, t, !0)), Rc = (i) => ri(i, (t) => To(i, t, !1)), vx = (i) => ri(i, (t) => R.cursor(i.lineBlockAt(t.head).from, 1)), xx = (i) => ri(i, (t) => R.cursor(i.lineBlockAt(t.head).to, -1));
function kx(i, t, e) {
  let s = !1, n = Ts(i.selection, (r) => {
    let o = $e(i, r.head, -1) || $e(i, r.head, 1) || r.head > 0 && $e(i, r.head - 1, 1) || r.head < i.doc.length && $e(i, r.head + 1, -1);
    if (!o || !o.end)
      return r;
    s = !0;
    let l = o.start.from == r.head ? o.end.to : o.end.from;
    return e ? R.range(r.anchor, l) : R.cursor(l);
  });
  return s ? (t(qe(i, n)), !0) : !1;
}
const Ox = ({ state: i, dispatch: t }) => kx(i, t, !1);
function Ke(i, t) {
  let e = Ts(i.state.selection, (s) => {
    let n = t(s);
    return R.range(s.anchor, n.head, n.goalColumn);
  });
  return e.eq(i.state.selection) ? !1 : (i.dispatch(qe(i.state, e)), !0);
}
function Kg(i, t) {
  return Ke(i, (e) => i.moveByChar(e, t));
}
const Xg = (i) => Kg(i, !ve(i)), Gg = (i) => Kg(i, ve(i));
function Jg(i, t) {
  return Ke(i, (e) => i.moveByGroup(e, t));
}
const Sx = (i) => Jg(i, !ve(i)), Cx = (i) => Jg(i, ve(i)), Ax = (i) => Ke(i, (t) => Ao(i.state, t, !ve(i))), Tx = (i) => Ke(i, (t) => Ao(i.state, t, ve(i)));
function Yg(i, t) {
  return Ke(i, (e) => i.moveVertically(e, t));
}
const Zg = (i) => Yg(i, !1), tm = (i) => Yg(i, !0);
function em(i, t) {
  return Ke(i, (e) => i.moveVertically(e, t, Hg(i)));
}
const Dc = (i) => em(i, !1), Bc = (i) => em(i, !0), Nc = (i) => Ke(i, (t) => To(i, t, !0)), Lc = (i) => Ke(i, (t) => To(i, t, !1)), Px = (i) => Ke(i, (t) => R.cursor(i.lineBlockAt(t.head).from)), Ex = (i) => Ke(i, (t) => R.cursor(i.lineBlockAt(t.head).to)), Ic = ({ state: i, dispatch: t }) => (t(qe(i, { anchor: 0 })), !0), Qc = ({ state: i, dispatch: t }) => (t(qe(i, { anchor: i.doc.length })), !0), zc = ({ state: i, dispatch: t }) => (t(qe(i, { anchor: i.selection.main.anchor, head: 0 })), !0), Wc = ({ state: i, dispatch: t }) => (t(qe(i, { anchor: i.selection.main.anchor, head: i.doc.length })), !0), Mx = ({ state: i, dispatch: t }) => (t(i.update({ selection: { anchor: 0, head: i.doc.length }, userEvent: "select" })), !0), Rx = ({ state: i, dispatch: t }) => {
  let e = Mo(i).map(({ from: s, to: n }) => R.range(s, Math.min(n + 1, i.doc.length)));
  return t(i.update({ selection: R.create(e), userEvent: "select" })), !0;
}, Dx = ({ state: i, dispatch: t }) => {
  let e = Ts(i.selection, (s) => {
    var n;
    let r = Ft(i).resolveInner(s.head, 1);
    for (; !(r.from < s.from && r.to >= s.to || r.to > s.to && r.from <= s.from || !(!((n = r.parent) === null || n === void 0) && n.parent)); )
      r = r.parent;
    return R.range(r.to, r.from);
  });
  return t(qe(i, e)), !0;
}, Bx = ({ state: i, dispatch: t }) => {
  let e = i.selection, s = null;
  return e.ranges.length > 1 ? s = R.create([e.main]) : e.main.empty || (s = R.create([R.cursor(e.main.head)])), s ? (t(qe(i, s)), !0) : !1;
};
function Po({ state: i, dispatch: t }, e) {
  if (i.readOnly)
    return !1;
  let s = "delete.selection", n = i.changeByRange((r) => {
    let { from: o, to: l } = r;
    if (o == l) {
      let a = e(o);
      a < o ? s = "delete.backward" : a > o && (s = "delete.forward"), o = Math.min(o, a), l = Math.max(l, a);
    }
    return o == l ? { range: r } : { changes: { from: o, to: l }, range: R.cursor(o) };
  });
  return n.changes.empty ? !1 : (t(i.update(n, {
    scrollIntoView: !0,
    userEvent: s,
    effects: s == "delete.selection" ? H.announce.of(i.phrase("Selection deleted")) : void 0
  })), !0);
}
function Eo(i, t, e) {
  if (i instanceof H)
    for (let s of i.state.facet(H.atomicRanges).map((n) => n(i)))
      s.between(t, t, (n, r) => {
        n < t && r > t && (t = e ? r : n);
      });
  return t;
}
const im = (i, t) => Po(i, (e) => {
  let { state: s } = i, n = s.doc.lineAt(e), r, o;
  if (!t && e > n.from && e < n.from + 200 && !/[^ \t]/.test(r = n.text.slice(0, e - n.from))) {
    if (r[r.length - 1] == "	")
      return e - 1;
    let l = Sn(r, s.tabSize), a = l % Vr(s) || Vr(s);
    for (let h = 0; h < a && r[r.length - 1 - h] == " "; h++)
      e--;
    o = e;
  } else
    o = de(n.text, e - n.from, t, t) + n.from, o == e && n.number != (t ? s.doc.lines : 1) && (o += t ? 1 : -1);
  return Eo(i, o, t);
}), Ba = (i) => im(i, !1), sm = (i) => im(i, !0), nm = (i, t) => Po(i, (e) => {
  let s = e, { state: n } = i, r = n.doc.lineAt(s), o = n.charCategorizer(s);
  for (let l = null; ; ) {
    if (s == (t ? r.to : r.from)) {
      s == e && r.number != (t ? n.doc.lines : 1) && (s += t ? 1 : -1);
      break;
    }
    let a = de(r.text, s - r.from, t) + r.from, h = r.text.slice(Math.min(s, a) - r.from, Math.max(s, a) - r.from), u = o(h);
    if (l != null && u != l)
      break;
    (h != " " || s != e) && (l = u), s = a;
  }
  return Eo(i, s, t);
}), rm = (i) => nm(i, !1), Nx = (i) => nm(i, !0), om = (i) => Po(i, (t) => {
  let e = i.lineBlockAt(t).to;
  return Eo(i, t < e ? e : Math.min(i.state.doc.length, t + 1), !0);
}), Lx = (i) => Po(i, (t) => {
  let e = i.lineBlockAt(t).from;
  return Eo(i, t > e ? e : Math.max(0, t - 1), !1);
}), Ix = ({ state: i, dispatch: t }) => {
  if (i.readOnly)
    return !1;
  let e = i.changeByRange((s) => ({
    changes: { from: s.from, to: s.to, insert: ct.of(["", ""]) },
    range: R.cursor(s.from)
  }));
  return t(i.update(e, { scrollIntoView: !0, userEvent: "input" })), !0;
}, Qx = ({ state: i, dispatch: t }) => {
  if (i.readOnly)
    return !1;
  let e = i.changeByRange((s) => {
    if (!s.empty || s.from == 0 || s.from == i.doc.length)
      return { range: s };
    let n = s.from, r = i.doc.lineAt(n), o = n == r.from ? n - 1 : de(r.text, n - r.from, !1) + r.from, l = n == r.to ? n + 1 : de(r.text, n - r.from, !0) + r.from;
    return {
      changes: { from: o, to: l, insert: i.doc.slice(n, l).append(i.doc.slice(o, n)) },
      range: R.cursor(l)
    };
  });
  return e.changes.empty ? !1 : (t(i.update(e, { scrollIntoView: !0, userEvent: "move.character" })), !0);
};
function Mo(i) {
  let t = [], e = -1;
  for (let s of i.selection.ranges) {
    let n = i.doc.lineAt(s.from), r = i.doc.lineAt(s.to);
    if (!s.empty && s.to == r.from && (r = i.doc.lineAt(s.to - 1)), e >= n.number) {
      let o = t[t.length - 1];
      o.to = r.to, o.ranges.push(s);
    } else
      t.push({ from: n.from, to: r.to, ranges: [s] });
    e = r.number + 1;
  }
  return t;
}
function lm(i, t, e) {
  if (i.readOnly)
    return !1;
  let s = [], n = [];
  for (let r of Mo(i)) {
    if (e ? r.to == i.doc.length : r.from == 0)
      continue;
    let o = i.doc.lineAt(e ? r.to + 1 : r.from - 1), l = o.length + 1;
    if (e) {
      s.push({ from: r.to, to: o.to }, { from: r.from, insert: o.text + i.lineBreak });
      for (let a of r.ranges)
        n.push(R.range(Math.min(i.doc.length, a.anchor + l), Math.min(i.doc.length, a.head + l)));
    } else {
      s.push({ from: o.from, to: r.from }, { from: r.to, insert: i.lineBreak + o.text });
      for (let a of r.ranges)
        n.push(R.range(a.anchor - l, a.head - l));
    }
  }
  return s.length ? (t(i.update({
    changes: s,
    scrollIntoView: !0,
    selection: R.create(n, i.selection.mainIndex),
    userEvent: "move.line"
  })), !0) : !1;
}
const zx = ({ state: i, dispatch: t }) => lm(i, t, !1), Wx = ({ state: i, dispatch: t }) => lm(i, t, !0);
function am(i, t, e) {
  if (i.readOnly)
    return !1;
  let s = [];
  for (let n of Mo(i))
    e ? s.push({ from: n.from, insert: i.doc.slice(n.from, n.to) + i.lineBreak }) : s.push({ from: n.to, insert: i.lineBreak + i.doc.slice(n.from, n.to) });
  return t(i.update({ changes: s, scrollIntoView: !0, userEvent: "input.copyline" })), !0;
}
const $x = ({ state: i, dispatch: t }) => am(i, t, !1), Fx = ({ state: i, dispatch: t }) => am(i, t, !0), Vx = (i) => {
  if (i.state.readOnly)
    return !1;
  let { state: t } = i, e = t.changes(Mo(t).map(({ from: n, to: r }) => (n > 0 ? n-- : r < t.doc.length && r++, { from: n, to: r }))), s = Ts(t.selection, (n) => i.moveVertically(n, !0)).map(e);
  return i.dispatch({ changes: e, selection: s, scrollIntoView: !0, userEvent: "delete.line" }), !0;
};
function Ux(i, t) {
  if (/\(\)|\[\]|\{\}/.test(i.sliceDoc(t - 1, t + 1)))
    return { from: t, to: t };
  let e = Ft(i).resolveInner(t), s = e.childBefore(t), n = e.childAfter(t), r;
  return s && n && s.to <= t && n.from >= t && (r = s.type.prop(ot.closedBy)) && r.indexOf(n.name) > -1 && i.doc.lineAt(s.to).from == i.doc.lineAt(n.from).from ? { from: s.to, to: n.from } : null;
}
const jx = /* @__PURE__ */ hm(!1), Hx = /* @__PURE__ */ hm(!0);
function hm(i) {
  return ({ state: t, dispatch: e }) => {
    if (t.readOnly)
      return !1;
    let s = t.changeByRange((n) => {
      let { from: r, to: o } = n, l = t.doc.lineAt(r), a = !i && r == o && Ux(t, r);
      i && (r = o = (o <= l.to ? l : t.doc.lineAt(o)).to);
      let h = new ko(t, { simulateBreak: r, simulateDoubleBreak: !!a }), u = yh(h, r);
      for (u == null && (u = /^\s*/.exec(t.doc.lineAt(r).text)[0].length); o < l.to && /\s/.test(l.text[o - l.from]); )
        o++;
      a ? { from: r, to: o } = a : r > l.from && r < l.from + 100 && !/\S/.test(l.text.slice(0, r)) && (r = l.from);
      let c = ["", vn(t, u)];
      return a && c.push(vn(t, h.lineIndent(l.from, -1))), {
        changes: { from: r, to: o, insert: ct.of(c) },
        range: R.cursor(r + 1 + c[1].length)
      };
    });
    return e(t.update(s, { scrollIntoView: !0, userEvent: "input" })), !0;
  };
}
function kh(i, t) {
  let e = -1;
  return i.changeByRange((s) => {
    let n = [];
    for (let o = s.from; o <= s.to; ) {
      let l = i.doc.lineAt(o);
      l.number > e && (s.empty || s.to > l.from) && (t(l, n, s), e = l.number), o = l.to + 1;
    }
    let r = i.changes(n);
    return {
      changes: n,
      range: R.range(r.mapPos(s.anchor, 1), r.mapPos(s.head, 1))
    };
  });
}
const qx = ({ state: i, dispatch: t }) => {
  if (i.readOnly)
    return !1;
  let e = /* @__PURE__ */ Object.create(null), s = new ko(i, { overrideIndentation: (r) => {
    let o = e[r];
    return o == null ? -1 : o;
  } }), n = kh(i, (r, o, l) => {
    let a = yh(s, r.from);
    if (a == null)
      return;
    /\S/.test(r.text) || (a = 0);
    let h = /^\s*/.exec(r.text)[0], u = vn(i, a);
    (h != u || l.from < r.from + h.length) && (e[r.from] = a, o.push({ from: r.from, to: r.from + h.length, insert: u }));
  });
  return n.changes.empty || t(i.update(n, { userEvent: "indent" })), !0;
}, um = ({ state: i, dispatch: t }) => i.readOnly ? !1 : (t(i.update(kh(i, (e, s) => {
  s.push({ from: e.from, insert: i.facet(xo) });
}), { userEvent: "input.indent" })), !0), cm = ({ state: i, dispatch: t }) => i.readOnly ? !1 : (t(i.update(kh(i, (e, s) => {
  let n = /^\s*/.exec(e.text)[0];
  if (!n)
    return;
  let r = Sn(n, i.tabSize), o = 0, l = vn(i, Math.max(0, r - Vr(i)));
  for (; o < n.length && o < l.length && n.charCodeAt(o) == l.charCodeAt(o); )
    o++;
  s.push({ from: e.from + o, to: e.from + n.length, insert: l.slice(o) });
}), { userEvent: "delete.dedent" })), !0), Kx = [
  { key: "Ctrl-b", run: Wg, shift: Xg, preventDefault: !0 },
  { key: "Ctrl-f", run: $g, shift: Gg },
  { key: "Ctrl-p", run: Ug, shift: Zg },
  { key: "Ctrl-n", run: jg, shift: tm },
  { key: "Ctrl-a", run: vx, shift: Px },
  { key: "Ctrl-e", run: xx, shift: Ex },
  { key: "Ctrl-d", run: sm },
  { key: "Ctrl-h", run: Ba },
  { key: "Ctrl-k", run: om },
  { key: "Ctrl-Alt-h", run: rm },
  { key: "Ctrl-o", run: Ix },
  { key: "Ctrl-t", run: Qx },
  { key: "Ctrl-v", run: Da }
], Xx = /* @__PURE__ */ [
  { key: "ArrowLeft", run: Wg, shift: Xg, preventDefault: !0 },
  { key: "Mod-ArrowLeft", mac: "Alt-ArrowLeft", run: mx, shift: Sx },
  { mac: "Cmd-ArrowLeft", run: Rc, shift: Lc },
  { key: "ArrowRight", run: $g, shift: Gg, preventDefault: !0 },
  { key: "Mod-ArrowRight", mac: "Alt-ArrowRight", run: _x, shift: Cx },
  { mac: "Cmd-ArrowRight", run: Mc, shift: Nc },
  { key: "ArrowUp", run: Ug, shift: Zg, preventDefault: !0 },
  { mac: "Cmd-ArrowUp", run: Ic, shift: zc },
  { mac: "Ctrl-ArrowUp", run: Ec, shift: Dc },
  { key: "ArrowDown", run: jg, shift: tm, preventDefault: !0 },
  { mac: "Cmd-ArrowDown", run: Qc, shift: Wc },
  { mac: "Ctrl-ArrowDown", run: Da, shift: Bc },
  { key: "PageUp", run: Ec, shift: Dc },
  { key: "PageDown", run: Da, shift: Bc },
  { key: "Home", run: Rc, shift: Lc, preventDefault: !0 },
  { key: "Mod-Home", run: Ic, shift: zc },
  { key: "End", run: Mc, shift: Nc, preventDefault: !0 },
  { key: "Mod-End", run: Qc, shift: Wc },
  { key: "Enter", run: jx },
  { key: "Mod-a", run: Mx },
  { key: "Backspace", run: Ba, shift: Ba },
  { key: "Delete", run: sm },
  { key: "Mod-Backspace", mac: "Alt-Backspace", run: rm },
  { key: "Mod-Delete", mac: "Alt-Delete", run: Nx },
  { mac: "Mod-Backspace", run: Lx },
  { mac: "Mod-Delete", run: om }
].concat(/* @__PURE__ */ Kx.map((i) => ({ mac: i.key, run: i.run, shift: i.shift }))), Gx = /* @__PURE__ */ [
  { key: "Alt-ArrowLeft", mac: "Ctrl-ArrowLeft", run: yx, shift: Ax },
  { key: "Alt-ArrowRight", mac: "Ctrl-ArrowRight", run: wx, shift: Tx },
  { key: "Alt-ArrowUp", run: zx },
  { key: "Shift-Alt-ArrowUp", run: $x },
  { key: "Alt-ArrowDown", run: Wx },
  { key: "Shift-Alt-ArrowDown", run: Fx },
  { key: "Escape", run: Bx },
  { key: "Mod-Enter", run: Hx },
  { key: "Alt-l", mac: "Ctrl-l", run: Rx },
  { key: "Mod-i", run: Dx, preventDefault: !0 },
  { key: "Mod-[", run: cm },
  { key: "Mod-]", run: um },
  { key: "Mod-Alt-\\", run: qx },
  { key: "Shift-Mod-k", run: Vx },
  { key: "Shift-Mod-\\", run: Ox },
  { key: "Mod-/", run: Gv },
  { key: "Alt-A", run: Yv }
].concat(Xx), Jx = { key: "Tab", run: um, shift: cm };
function vt() {
  var i = arguments[0];
  typeof i == "string" && (i = document.createElement(i));
  var t = 1, e = arguments[1];
  if (e && typeof e == "object" && e.nodeType == null && !Array.isArray(e)) {
    for (var s in e)
      if (Object.prototype.hasOwnProperty.call(e, s)) {
        var n = e[s];
        typeof n == "string" ? i.setAttribute(s, n) : n != null && (i[s] = n);
      }
    t++;
  }
  for (; t < arguments.length; t++)
    fm(i, arguments[t]);
  return i;
}
function fm(i, t) {
  if (typeof t == "string")
    i.appendChild(document.createTextNode(t));
  else if (t != null)
    if (t.nodeType != null)
      i.appendChild(t);
    else if (Array.isArray(t))
      for (var e = 0; e < t.length; e++)
        fm(i, t[e]);
    else
      throw new RangeError("Unsupported child node: " + t);
}
const $c = typeof String.prototype.normalize == "function" ? (i) => i.normalize("NFKD") : (i) => i;
class xs {
  constructor(t, e, s = 0, n = t.length, r) {
    this.value = { from: 0, to: 0 }, this.done = !1, this.matches = [], this.buffer = "", this.bufferPos = 0, this.iter = t.iterRange(s, n), this.bufferStart = s, this.normalize = r ? (o) => r($c(o)) : $c, this.query = this.normalize(e);
  }
  peek() {
    if (this.bufferPos == this.buffer.length) {
      if (this.bufferStart += this.buffer.length, this.iter.next(), this.iter.done)
        return -1;
      this.bufferPos = 0, this.buffer = this.iter.value;
    }
    return It(this.buffer, this.bufferPos);
  }
  next() {
    for (; this.matches.length; )
      this.matches.pop();
    return this.nextOverlapping();
  }
  nextOverlapping() {
    for (; ; ) {
      let t = this.peek();
      if (t < 0)
        return this.done = !0, this;
      let e = nh(t), s = this.bufferStart + this.bufferPos;
      this.bufferPos += ue(t);
      let n = this.normalize(e);
      for (let r = 0, o = s; ; r++) {
        let l = n.charCodeAt(r), a = this.match(l, o);
        if (a)
          return this.value = a, this;
        if (r == n.length - 1)
          break;
        o == s && r < e.length && e.charCodeAt(r) == l && o++;
      }
    }
  }
  match(t, e) {
    let s = null;
    for (let n = 0; n < this.matches.length; n += 2) {
      let r = this.matches[n], o = !1;
      this.query.charCodeAt(r) == t && (r == this.query.length - 1 ? s = { from: this.matches[n + 1], to: e + 1 } : (this.matches[n]++, o = !0)), o || (this.matches.splice(n, 2), n -= 2);
    }
    return this.query.charCodeAt(0) == t && (this.query.length == 1 ? s = { from: e, to: e + 1 } : this.matches.push(1, e)), s;
  }
}
typeof Symbol != "undefined" && (xs.prototype[Symbol.iterator] = function() {
  return this;
});
const dm = { from: -1, to: -1, match: /* @__PURE__ */ /.*/.exec("") }, Oh = "gm" + (/x/.unicode == null ? "" : "u");
class pm {
  constructor(t, e, s, n = 0, r = t.length) {
    if (this.to = r, this.curLine = "", this.done = !1, this.value = dm, /\\[sWDnr]|\n|\r|\[\^/.test(e))
      return new gm(t, e, s, n, r);
    this.re = new RegExp(e, Oh + (s != null && s.ignoreCase ? "i" : "")), this.iter = t.iter();
    let o = t.lineAt(n);
    this.curLineStart = o.from, this.matchPos = n, this.getLine(this.curLineStart);
  }
  getLine(t) {
    this.iter.next(t), this.iter.lineBreak ? this.curLine = "" : (this.curLine = this.iter.value, this.curLineStart + this.curLine.length > this.to && (this.curLine = this.curLine.slice(0, this.to - this.curLineStart)), this.iter.next());
  }
  nextLine() {
    this.curLineStart = this.curLineStart + this.curLine.length + 1, this.curLineStart > this.to ? this.curLine = "" : this.getLine(0);
  }
  next() {
    for (let t = this.matchPos - this.curLineStart; ; ) {
      this.re.lastIndex = t;
      let e = this.matchPos <= this.to && this.re.exec(this.curLine);
      if (e) {
        let s = this.curLineStart + e.index, n = s + e[0].length;
        if (this.matchPos = n + (s == n ? 1 : 0), s == this.curLine.length && this.nextLine(), s < n || s > this.value.to)
          return this.value = { from: s, to: n, match: e }, this;
        t = this.matchPos - this.curLineStart;
      } else if (this.curLineStart + this.curLine.length < this.to)
        this.nextLine(), t = 0;
      else
        return this.done = !0, this;
    }
  }
}
const hl = /* @__PURE__ */ new WeakMap();
class ds {
  constructor(t, e) {
    this.from = t, this.text = e;
  }
  get to() {
    return this.from + this.text.length;
  }
  static get(t, e, s) {
    let n = hl.get(t);
    if (!n || n.from >= s || n.to <= e) {
      let l = new ds(e, t.sliceString(e, s));
      return hl.set(t, l), l;
    }
    if (n.from == e && n.to == s)
      return n;
    let { text: r, from: o } = n;
    return o > e && (r = t.sliceString(e, o) + r, o = e), n.to < s && (r += t.sliceString(n.to, s)), hl.set(t, new ds(o, r)), new ds(e, r.slice(e - o, s - o));
  }
}
class gm {
  constructor(t, e, s, n, r) {
    this.text = t, this.to = r, this.done = !1, this.value = dm, this.matchPos = n, this.re = new RegExp(e, Oh + (s != null && s.ignoreCase ? "i" : "")), this.flat = ds.get(t, n, this.chunkEnd(n + 5e3));
  }
  chunkEnd(t) {
    return t >= this.to ? this.to : this.text.lineAt(t).to;
  }
  next() {
    for (; ; ) {
      let t = this.re.lastIndex = this.matchPos - this.flat.from, e = this.re.exec(this.flat.text);
      if (e && !e[0] && e.index == t && (this.re.lastIndex = t + 1, e = this.re.exec(this.flat.text)), e && this.flat.to < this.to && e.index + e[0].length > this.flat.text.length - 10 && (e = null), e) {
        let s = this.flat.from + e.index, n = s + e[0].length;
        return this.value = { from: s, to: n, match: e }, this.matchPos = n + (s == n ? 1 : 0), this;
      } else {
        if (this.flat.to == this.to)
          return this.done = !0, this;
        this.flat = ds.get(this.text, this.flat.from, this.chunkEnd(this.flat.from + this.flat.text.length * 2));
      }
    }
  }
}
typeof Symbol != "undefined" && (pm.prototype[Symbol.iterator] = gm.prototype[Symbol.iterator] = function() {
  return this;
});
function Yx(i) {
  try {
    return new RegExp(i, Oh), !0;
  } catch (t) {
    return !1;
  }
}
function Na(i) {
  let t = vt("input", { class: "cm-textfield", name: "line" }), e = vt("form", {
    class: "cm-gotoLine",
    onkeydown: (n) => {
      n.keyCode == 27 ? (n.preventDefault(), i.dispatch({ effects: qr.of(!1) }), i.focus()) : n.keyCode == 13 && (n.preventDefault(), s());
    },
    onsubmit: (n) => {
      n.preventDefault(), s();
    }
  }, vt("label", i.state.phrase("Go to line"), ": ", t), " ", vt("button", { class: "cm-button", type: "submit" }, i.state.phrase("go")));
  function s() {
    let n = /^([+-])?(\d+)?(:\d+)?(%)?$/.exec(t.value);
    if (!n)
      return;
    let { state: r } = i, o = r.doc.lineAt(r.selection.main.head), [, l, a, h, u] = n, c = h ? +h.slice(1) : 0, f = a ? +a : o.number;
    if (a && u) {
      let _ = f / 100;
      l && (_ = _ * (l == "-" ? -1 : 1) + o.number / r.doc.lines), f = Math.round(r.doc.lines * _);
    } else
      a && l && (f = f * (l == "-" ? -1 : 1) + o.number);
    let g = r.doc.line(Math.max(1, Math.min(r.doc.lines, f)));
    i.dispatch({
      effects: qr.of(!1),
      selection: R.cursor(g.from + Math.max(0, Math.min(c, g.length))),
      scrollIntoView: !0
    }), i.focus();
  }
  return { dom: e };
}
const qr = /* @__PURE__ */ rt.define(), Fc = /* @__PURE__ */ Vt.define({
  create() {
    return !0;
  },
  update(i, t) {
    for (let e of t.effects)
      e.is(qr) && (i = e.value);
    return i;
  },
  provide: (i) => yn.from(i, (t) => t ? Na : null)
}), Zx = (i) => {
  let t = bn(i, Na);
  if (!t) {
    let e = [qr.of(!0)];
    i.state.field(Fc, !1) == null && e.push(rt.appendConfig.of([Fc, tk])), i.dispatch({ effects: e }), t = bn(i, Na);
  }
  return t && t.dom.querySelector("input").focus(), !0;
}, tk = /* @__PURE__ */ H.baseTheme({
  ".cm-panel.cm-gotoLine": {
    padding: "2px 6px 4px",
    "& label": { fontSize: "80%" }
  }
}), ek = {
  highlightWordAroundCursor: !1,
  minSelectionLength: 1,
  maxMatches: 100,
  wholeWords: !1
}, mm = /* @__PURE__ */ q.define({
  combine(i) {
    return si(i, ek, {
      highlightWordAroundCursor: (t, e) => t || e,
      minSelectionLength: Math.min,
      maxMatches: Math.min
    });
  }
});
function ik(i) {
  let t = [lk, ok];
  return i && t.push(mm.of(i)), t;
}
const sk = /* @__PURE__ */ X.mark({ class: "cm-selectionMatch" }), nk = /* @__PURE__ */ X.mark({ class: "cm-selectionMatch cm-selectionMatch-main" });
function Vc(i, t, e, s) {
  return (e == 0 || i(t.sliceDoc(e - 1, e)) != Jt.Word) && (s == t.doc.length || i(t.sliceDoc(s, s + 1)) != Jt.Word);
}
function rk(i, t, e, s) {
  return i(t.sliceDoc(e, e + 1)) == Jt.Word && i(t.sliceDoc(s - 1, s)) == Jt.Word;
}
const ok = /* @__PURE__ */ At.fromClass(class {
  constructor(i) {
    this.decorations = this.getDeco(i);
  }
  update(i) {
    (i.selectionSet || i.docChanged || i.viewportChanged) && (this.decorations = this.getDeco(i.view));
  }
  getDeco(i) {
    let t = i.state.facet(mm), { state: e } = i, s = e.selection;
    if (s.ranges.length > 1)
      return X.none;
    let n = s.main, r, o = null;
    if (n.empty) {
      if (!t.highlightWordAroundCursor)
        return X.none;
      let a = e.wordAt(n.head);
      if (!a)
        return X.none;
      o = e.charCategorizer(n.head), r = e.sliceDoc(a.from, a.to);
    } else {
      let a = n.to - n.from;
      if (a < t.minSelectionLength || a > 200)
        return X.none;
      if (t.wholeWords) {
        if (r = e.sliceDoc(n.from, n.to), o = e.charCategorizer(n.head), !(Vc(o, e, n.from, n.to) && rk(o, e, n.from, n.to)))
          return X.none;
      } else if (r = e.sliceDoc(n.from, n.to).trim(), !r)
        return X.none;
    }
    let l = [];
    for (let a of i.visibleRanges) {
      let h = new xs(e.doc, r, a.from, a.to);
      for (; !h.next().done; ) {
        let { from: u, to: c } = h.value;
        if ((!o || Vc(o, e, u, c)) && (n.empty && u <= n.from && c >= n.to ? l.push(nk.range(u, c)) : (u >= n.to || c <= n.from) && l.push(sk.range(u, c)), l.length > t.maxMatches))
          return X.none;
      }
    }
    return X.set(l);
  }
}, {
  decorations: (i) => i.decorations
}), lk = /* @__PURE__ */ H.baseTheme({
  ".cm-selectionMatch": { backgroundColor: "#99ff7780" },
  ".cm-searchMatch .cm-selectionMatch": { backgroundColor: "transparent" }
}), ak = ({ state: i, dispatch: t }) => {
  let { selection: e } = i, s = R.create(e.ranges.map((n) => i.wordAt(n.head) || R.cursor(n.head)), e.mainIndex);
  return s.eq(e) ? !1 : (t(i.update({ selection: s })), !0);
};
function hk(i, t) {
  let { main: e, ranges: s } = i.selection, n = i.wordAt(e.head), r = n && n.from == e.from && n.to == e.to;
  for (let o = !1, l = new xs(i.doc, t, s[s.length - 1].to); ; )
    if (l.next(), l.done) {
      if (o)
        return null;
      l = new xs(i.doc, t, 0, Math.max(0, s[s.length - 1].from - 1)), o = !0;
    } else {
      if (o && s.some((a) => a.from == l.value.from))
        continue;
      if (r) {
        let a = i.wordAt(l.value.from);
        if (!a || a.from != l.value.from || a.to != l.value.to)
          continue;
      }
      return l.value;
    }
}
const uk = ({ state: i, dispatch: t }) => {
  let { ranges: e } = i.selection;
  if (e.some((r) => r.from === r.to))
    return ak({ state: i, dispatch: t });
  let s = i.sliceDoc(e[0].from, e[0].to);
  if (i.selection.ranges.some((r) => i.sliceDoc(r.from, r.to) != s))
    return !1;
  let n = hk(i, s);
  return n ? (t(i.update({
    selection: i.selection.addRange(R.range(n.from, n.to), !1),
    effects: H.scrollIntoView(n.to)
  })), !0) : !1;
}, Sh = /* @__PURE__ */ q.define({
  combine(i) {
    var t;
    return {
      top: i.reduce((e, s) => e != null ? e : s.top, void 0) || !1,
      caseSensitive: i.reduce((e, s) => e != null ? e : s.caseSensitive, void 0) || !1,
      createPanel: ((t = i.find((e) => e.createPanel)) === null || t === void 0 ? void 0 : t.createPanel) || ((e) => new wk(e))
    };
  }
});
class _m {
  constructor(t) {
    this.search = t.search, this.caseSensitive = !!t.caseSensitive, this.regexp = !!t.regexp, this.replace = t.replace || "", this.valid = !!this.search && (!this.regexp || Yx(this.search)), this.unquoted = t.literal ? this.search : this.search.replace(/\\([nrt\\])/g, (e, s) => s == "n" ? `
` : s == "r" ? "\r" : s == "t" ? "	" : "\\");
  }
  eq(t) {
    return this.search == t.search && this.replace == t.replace && this.caseSensitive == t.caseSensitive && this.regexp == t.regexp;
  }
  create() {
    return this.regexp ? new fk(this) : new ck(this);
  }
  getCursor(t, e = 0, s = t.length) {
    return this.regexp ? ns(this, t, e, s) : ss(this, t, e, s);
  }
}
class bm {
  constructor(t) {
    this.spec = t;
  }
}
function ss(i, t, e, s) {
  return new xs(t, i.unquoted, e, s, i.caseSensitive ? void 0 : (n) => n.toLowerCase());
}
class ck extends bm {
  constructor(t) {
    super(t);
  }
  nextMatch(t, e, s) {
    let n = ss(this.spec, t, s, t.length).nextOverlapping();
    return n.done && (n = ss(this.spec, t, 0, e).nextOverlapping()), n.done ? null : n.value;
  }
  prevMatchInRange(t, e, s) {
    for (let n = s; ; ) {
      let r = Math.max(e, n - 1e4 - this.spec.unquoted.length), o = ss(this.spec, t, r, n), l = null;
      for (; !o.nextOverlapping().done; )
        l = o.value;
      if (l)
        return l;
      if (r == e)
        return null;
      n -= 1e4;
    }
  }
  prevMatch(t, e, s) {
    return this.prevMatchInRange(t, 0, e) || this.prevMatchInRange(t, s, t.length);
  }
  getReplacement(t) {
    return this.spec.replace;
  }
  matchAll(t, e) {
    let s = ss(this.spec, t, 0, t.length), n = [];
    for (; !s.next().done; ) {
      if (n.length >= e)
        return null;
      n.push(s.value);
    }
    return n;
  }
  highlight(t, e, s, n) {
    let r = ss(this.spec, t, Math.max(0, e - this.spec.unquoted.length), Math.min(s + this.spec.unquoted.length, t.length));
    for (; !r.next().done; )
      n(r.value.from, r.value.to);
  }
}
function ns(i, t, e, s) {
  return new pm(t, i.search, i.caseSensitive ? void 0 : { ignoreCase: !0 }, e, s);
}
class fk extends bm {
  nextMatch(t, e, s) {
    let n = ns(this.spec, t, s, t.length).next();
    return n.done && (n = ns(this.spec, t, 0, e).next()), n.done ? null : n.value;
  }
  prevMatchInRange(t, e, s) {
    for (let n = 1; ; n++) {
      let r = Math.max(e, s - n * 1e4), o = ns(this.spec, t, r, s), l = null;
      for (; !o.next().done; )
        l = o.value;
      if (l && (r == e || l.from > r + 10))
        return l;
      if (r == e)
        return null;
    }
  }
  prevMatch(t, e, s) {
    return this.prevMatchInRange(t, 0, e) || this.prevMatchInRange(t, s, t.length);
  }
  getReplacement(t) {
    return this.spec.replace.replace(/\$([$&\d+])/g, (e, s) => s == "$" ? "$" : s == "&" ? t.match[0] : s != "0" && +s < t.match.length ? t.match[s] : e);
  }
  matchAll(t, e) {
    let s = ns(this.spec, t, 0, t.length), n = [];
    for (; !s.next().done; ) {
      if (n.length >= e)
        return null;
      n.push(s.value);
    }
    return n;
  }
  highlight(t, e, s, n) {
    let r = ns(this.spec, t, Math.max(0, e - 250), Math.min(s + 250, t.length));
    for (; !r.next().done; )
      n(r.value.from, r.value.to);
  }
}
const xn = /* @__PURE__ */ rt.define(), Ch = /* @__PURE__ */ rt.define(), vi = /* @__PURE__ */ Vt.define({
  create(i) {
    return new ul(La(i).create(), null);
  },
  update(i, t) {
    for (let e of t.effects)
      e.is(xn) ? i = new ul(e.value.create(), i.panel) : e.is(Ch) && (i = new ul(i.query, e.value ? Ah : null));
    return i;
  },
  provide: (i) => yn.from(i, (t) => t.panel)
});
class ul {
  constructor(t, e) {
    this.query = t, this.panel = e;
  }
}
const dk = /* @__PURE__ */ X.mark({ class: "cm-searchMatch" }), pk = /* @__PURE__ */ X.mark({ class: "cm-searchMatch cm-searchMatch-selected" }), gk = /* @__PURE__ */ At.fromClass(class {
  constructor(i) {
    this.view = i, this.decorations = this.highlight(i.state.field(vi));
  }
  update(i) {
    let t = i.state.field(vi);
    (t != i.startState.field(vi) || i.docChanged || i.selectionSet || i.viewportChanged) && (this.decorations = this.highlight(t));
  }
  highlight({ query: i, panel: t }) {
    if (!t || !i.spec.valid)
      return X.none;
    let { view: e } = this, s = new Oi();
    for (let n = 0, r = e.visibleRanges, o = r.length; n < o; n++) {
      let { from: l, to: a } = r[n];
      for (; n < o - 1 && a > r[n + 1].from - 2 * 250; )
        a = r[++n].to;
      i.highlight(e.state.doc, l, a, (h, u) => {
        let c = e.state.selection.ranges.some((f) => f.from == h && f.to == u);
        s.add(h, u, c ? pk : dk);
      });
    }
    return s.finish();
  }
}, {
  decorations: (i) => i.decorations
});
function Pn(i) {
  return (t) => {
    let e = t.state.field(vi, !1);
    return e && e.query.spec.valid ? i(t, e) : ym(t);
  };
}
const Kr = /* @__PURE__ */ Pn((i, { query: t }) => {
  let { to: e } = i.state.selection.main, s = t.nextMatch(i.state.doc, e, e);
  return s ? (i.dispatch({
    selection: { anchor: s.from, head: s.to },
    scrollIntoView: !0,
    effects: Th(i, s),
    userEvent: "select.search"
  }), !0) : !1;
}), Xr = /* @__PURE__ */ Pn((i, { query: t }) => {
  let { state: e } = i, { from: s } = e.selection.main, n = t.prevMatch(e.doc, s, s);
  return n ? (i.dispatch({
    selection: { anchor: n.from, head: n.to },
    scrollIntoView: !0,
    effects: Th(i, n),
    userEvent: "select.search"
  }), !0) : !1;
}), mk = /* @__PURE__ */ Pn((i, { query: t }) => {
  let e = t.matchAll(i.state.doc, 1e3);
  return !e || !e.length ? !1 : (i.dispatch({
    selection: R.create(e.map((s) => R.range(s.from, s.to))),
    userEvent: "select.search.matches"
  }), !0);
}), _k = ({ state: i, dispatch: t }) => {
  let e = i.selection;
  if (e.ranges.length > 1 || e.main.empty)
    return !1;
  let { from: s, to: n } = e.main, r = [], o = 0;
  for (let l = new xs(i.doc, i.sliceDoc(s, n)); !l.next().done; ) {
    if (r.length > 1e3)
      return !1;
    l.value.from == s && (o = r.length), r.push(R.range(l.value.from, l.value.to));
  }
  return t(i.update({
    selection: R.create(r, o),
    userEvent: "select.search.matches"
  })), !0;
}, Uc = /* @__PURE__ */ Pn((i, { query: t }) => {
  let { state: e } = i, { from: s, to: n } = e.selection.main;
  if (e.readOnly)
    return !1;
  let r = t.nextMatch(e.doc, s, s);
  if (!r)
    return !1;
  let o = [], l, a, h = [];
  if (r.from == s && r.to == n && (a = e.toText(t.getReplacement(r)), o.push({ from: r.from, to: r.to, insert: a }), r = t.nextMatch(e.doc, r.from, r.to), h.push(H.announce.of(e.phrase("replaced match on line $", e.doc.lineAt(s).number) + "."))), r) {
    let u = o.length == 0 || o[0].from >= r.to ? 0 : r.to - r.from - a.length;
    l = { anchor: r.from - u, head: r.to - u }, h.push(Th(i, r));
  }
  return i.dispatch({
    changes: o,
    selection: l,
    scrollIntoView: !!l,
    effects: h,
    userEvent: "input.replace"
  }), !0;
}), bk = /* @__PURE__ */ Pn((i, { query: t }) => {
  if (i.state.readOnly)
    return !1;
  let e = t.matchAll(i.state.doc, 1e9).map((n) => {
    let { from: r, to: o } = n;
    return { from: r, to: o, insert: t.getReplacement(n) };
  });
  if (!e.length)
    return !1;
  let s = i.state.phrase("replaced $ matches", e.length) + ".";
  return i.dispatch({
    changes: e,
    effects: H.announce.of(s),
    userEvent: "input.replace.all"
  }), !0;
});
function Ah(i) {
  return i.state.facet(Sh).createPanel(i);
}
function La(i, t) {
  var e;
  let s = i.selection.main, n = s.empty || s.to > s.from + 100 ? "" : i.sliceDoc(s.from, s.to), r = (e = t == null ? void 0 : t.caseSensitive) !== null && e !== void 0 ? e : i.facet(Sh).caseSensitive;
  return t && !n ? t : new _m({ search: n.replace(/\n/g, "\\n"), caseSensitive: r });
}
const ym = (i) => {
  let t = i.state.field(vi, !1);
  if (t && t.panel) {
    let e = bn(i, Ah);
    if (!e)
      return !1;
    let s = e.dom.querySelector("[main-field]");
    if (s && s != i.root.activeElement) {
      let n = La(i.state, t.query.spec);
      n.valid && i.dispatch({ effects: xn.of(n) }), s.focus(), s.select();
    }
  } else
    i.dispatch({ effects: [
      Ch.of(!0),
      t ? xn.of(La(i.state, t.query.spec)) : rt.appendConfig.of(xk)
    ] });
  return !0;
}, wm = (i) => {
  let t = i.state.field(vi, !1);
  if (!t || !t.panel)
    return !1;
  let e = bn(i, Ah);
  return e && e.dom.contains(i.root.activeElement) && i.focus(), i.dispatch({ effects: Ch.of(!1) }), !0;
}, yk = [
  { key: "Mod-f", run: ym, scope: "editor search-panel" },
  { key: "F3", run: Kr, shift: Xr, scope: "editor search-panel", preventDefault: !0 },
  { key: "Mod-g", run: Kr, shift: Xr, scope: "editor search-panel", preventDefault: !0 },
  { key: "Escape", run: wm, scope: "editor search-panel" },
  { key: "Mod-Shift-l", run: _k },
  { key: "Alt-g", run: Zx },
  { key: "Mod-d", run: uk, preventDefault: !0 }
];
class wk {
  constructor(t) {
    this.view = t;
    let e = this.query = t.state.field(vi).query.spec;
    this.commit = this.commit.bind(this), this.searchField = vt("input", {
      value: e.search,
      placeholder: be(t, "Find"),
      "aria-label": be(t, "Find"),
      class: "cm-textfield",
      name: "search",
      "main-field": "true",
      onchange: this.commit,
      onkeyup: this.commit
    }), this.replaceField = vt("input", {
      value: e.replace,
      placeholder: be(t, "Replace"),
      "aria-label": be(t, "Replace"),
      class: "cm-textfield",
      name: "replace",
      onchange: this.commit,
      onkeyup: this.commit
    }), this.caseField = vt("input", {
      type: "checkbox",
      name: "case",
      checked: e.caseSensitive,
      onchange: this.commit
    }), this.reField = vt("input", {
      type: "checkbox",
      name: "re",
      checked: e.regexp,
      onchange: this.commit
    });
    function s(n, r, o) {
      return vt("button", { class: "cm-button", name: n, onclick: r, type: "button" }, o);
    }
    this.dom = vt("div", { onkeydown: (n) => this.keydown(n), class: "cm-search" }, [
      this.searchField,
      s("next", () => Kr(t), [be(t, "next")]),
      s("prev", () => Xr(t), [be(t, "previous")]),
      s("select", () => mk(t), [be(t, "all")]),
      vt("label", null, [this.caseField, be(t, "match case")]),
      vt("label", null, [this.reField, be(t, "regexp")]),
      ...t.state.readOnly ? [] : [
        vt("br"),
        this.replaceField,
        s("replace", () => Uc(t), [be(t, "replace")]),
        s("replaceAll", () => bk(t), [be(t, "replace all")]),
        vt("button", {
          name: "close",
          onclick: () => wm(t),
          "aria-label": be(t, "close"),
          type: "button"
        }, ["\xD7"])
      ]
    ]);
  }
  commit() {
    let t = new _m({
      search: this.searchField.value,
      caseSensitive: this.caseField.checked,
      regexp: this.reField.checked,
      replace: this.replaceField.value
    });
    t.eq(this.query) || (this.query = t, this.view.dispatch({ effects: xn.of(t) }));
  }
  keydown(t) {
    K1(this.view, t, "search-panel") ? t.preventDefault() : t.keyCode == 13 && t.target == this.searchField ? (t.preventDefault(), (t.shiftKey ? Xr : Kr)(this.view)) : t.keyCode == 13 && t.target == this.replaceField && (t.preventDefault(), Uc(this.view));
  }
  update(t) {
    for (let e of t.transactions)
      for (let s of e.effects)
        s.is(xn) && !s.value.eq(this.query) && this.setQuery(s.value);
  }
  setQuery(t) {
    this.query = t, this.searchField.value = t.search, this.replaceField.value = t.replace, this.caseField.checked = t.caseSensitive, this.reField.checked = t.regexp;
  }
  mount() {
    this.searchField.select();
  }
  get pos() {
    return 80;
  }
  get top() {
    return this.view.state.facet(Sh).top;
  }
}
function be(i, t) {
  return i.state.phrase(t);
}
const Xn = 30, Gn = /[\s\.,:;?!]/;
function Th(i, { from: t, to: e }) {
  let s = i.state.doc.lineAt(t), n = i.state.doc.lineAt(e).to, r = Math.max(s.from, t - Xn), o = Math.min(n, e + Xn), l = i.state.sliceDoc(r, o);
  if (r != s.from) {
    for (let a = 0; a < Xn; a++)
      if (!Gn.test(l[a + 1]) && Gn.test(l[a])) {
        l = l.slice(a);
        break;
      }
  }
  if (o != n) {
    for (let a = l.length - 1; a > l.length - Xn; a--)
      if (!Gn.test(l[a - 1]) && Gn.test(l[a])) {
        l = l.slice(0, a);
        break;
      }
  }
  return H.announce.of(`${i.state.phrase("current match")}. ${l} ${i.state.phrase("on line")} ${s.number}.`);
}
const vk = /* @__PURE__ */ H.baseTheme({
  ".cm-panel.cm-search": {
    padding: "2px 6px 4px",
    position: "relative",
    "& [name=close]": {
      position: "absolute",
      top: "0",
      right: "4px",
      backgroundColor: "inherit",
      border: "none",
      font: "inherit",
      padding: 0,
      margin: 0
    },
    "& input, & button, & label": {
      margin: ".2em .6em .2em 0"
    },
    "& input[type=checkbox]": {
      marginRight: ".2em"
    },
    "& label": {
      fontSize: "80%",
      whiteSpace: "pre"
    }
  },
  "&light .cm-searchMatch": { backgroundColor: "#ffff0054" },
  "&dark .cm-searchMatch": { backgroundColor: "#00ffff8a" },
  "&light .cm-searchMatch-selected": { backgroundColor: "#ff6a0054" },
  "&dark .cm-searchMatch-selected": { backgroundColor: "#ff00ff8a" }
}), xk = [
  vi,
  /* @__PURE__ */ As.lowest(gk),
  vk
];
class vm {
  constructor(t, e, s) {
    this.state = t, this.pos = e, this.explicit = s, this.abortListeners = [];
  }
  tokenBefore(t) {
    let e = Ft(this.state).resolveInner(this.pos, -1);
    for (; e && t.indexOf(e.name) < 0; )
      e = e.parent;
    return e ? {
      from: e.from,
      to: this.pos,
      text: this.state.sliceDoc(e.from, this.pos),
      type: e.type
    } : null;
  }
  matchBefore(t) {
    let e = this.state.doc.lineAt(this.pos), s = Math.max(e.from, this.pos - 250), n = e.text.slice(s - e.from, this.pos - e.from), r = n.search(xm(t, !1));
    return r < 0 ? null : { from: s + r, to: this.pos, text: n.slice(r) };
  }
  get aborted() {
    return this.abortListeners == null;
  }
  addEventListener(t, e) {
    t == "abort" && this.abortListeners && this.abortListeners.push(e);
  }
}
function jc(i) {
  let t = Object.keys(i).join(""), e = /\w/.test(t);
  return e && (t = t.replace(/\w/g, "")), `[${e ? "\\w" : ""}${t.replace(/[^\w\s]/g, "\\$&")}]`;
}
function kk(i) {
  let t = /* @__PURE__ */ Object.create(null), e = /* @__PURE__ */ Object.create(null);
  for (let { label: n } of i) {
    t[n[0]] = !0;
    for (let r = 1; r < n.length; r++)
      e[n[r]] = !0;
  }
  let s = jc(t) + jc(e) + "*$";
  return [new RegExp("^" + s), new RegExp(s)];
}
function Ok(i) {
  let t = i.map((n) => typeof n == "string" ? { label: n } : n), [e, s] = t.every((n) => /^\w+$/.test(n.label)) ? [/\w*$/, /\w+$/] : kk(t);
  return (n) => {
    let r = n.matchBefore(s);
    return r || n.explicit ? { from: r ? r.from : n.pos, options: t, validFor: e } : null;
  };
}
class Hc {
  constructor(t, e, s) {
    this.completion = t, this.source = e, this.match = s;
  }
}
function xi(i) {
  return i.selection.main.head;
}
function xm(i, t) {
  var e;
  let { source: s } = i, n = t && s[0] != "^", r = s[s.length - 1] != "$";
  return !n && !r ? i : new RegExp(`${n ? "^" : ""}(?:${s})${r ? "$" : ""}`, (e = i.flags) !== null && e !== void 0 ? e : i.ignoreCase ? "i" : "");
}
function Sk(i, t, e, s) {
  return Object.assign(Object.assign({}, i.changeByRange((n) => {
    if (n == i.selection.main)
      return {
        changes: { from: e, to: s, insert: t },
        range: R.cursor(e + t.length)
      };
    let r = s - e;
    return !n.empty || r && i.sliceDoc(n.from - r, n.from) != i.sliceDoc(e, s) ? { range: n } : {
      changes: { from: n.from - r, to: n.from, insert: t },
      range: R.cursor(n.from - r + t.length)
    };
  })), { userEvent: "input.complete" });
}
function km(i, t) {
  const e = t.completion.apply || t.completion.label;
  let s = t.source;
  typeof e == "string" ? i.dispatch(Sk(i.state, e, s.from, s.to)) : e(i, t.completion, s.from, s.to);
}
const qc = /* @__PURE__ */ new WeakMap();
function Ck(i) {
  if (!Array.isArray(i))
    return i;
  let t = qc.get(i);
  return t || qc.set(i, t = Ok(i)), t;
}
class Ak {
  constructor(t) {
    this.pattern = t, this.chars = [], this.folded = [], this.any = [], this.precise = [], this.byWord = [];
    for (let e = 0; e < t.length; ) {
      let s = It(t, e), n = ue(s);
      this.chars.push(s);
      let r = t.slice(e, e + n), o = r.toUpperCase();
      this.folded.push(It(o == r ? r.toLowerCase() : o, 0)), e += n;
    }
    this.astral = t.length != this.chars.length;
  }
  match(t) {
    if (this.pattern.length == 0)
      return [0];
    if (t.length < this.pattern.length)
      return null;
    let { chars: e, folded: s, any: n, precise: r, byWord: o } = this;
    if (e.length == 1) {
      let y = It(t, 0);
      return y == e[0] ? [0, 0, ue(y)] : y == s[0] ? [-200, 0, ue(y)] : null;
    }
    let l = t.indexOf(this.pattern);
    if (l == 0)
      return [0, 0, this.pattern.length];
    let a = e.length, h = 0;
    if (l < 0) {
      for (let y = 0, M = Math.min(t.length, 200); y < M && h < a; ) {
        let x = It(t, y);
        (x == e[h] || x == s[h]) && (n[h++] = y), y += ue(x);
      }
      if (h < a)
        return null;
    }
    let u = 0, c = 0, f = !1, g = 0, _ = -1, A = -1, m = /[a-z]/.test(t), p = !0;
    for (let y = 0, M = Math.min(t.length, 200), x = 0; y < M && c < a; ) {
      let B = It(t, y);
      l < 0 && (u < a && B == e[u] && (r[u++] = y), g < a && (B == e[g] || B == s[g] ? (g == 0 && (_ = y), A = y + 1, g++) : g = 0));
      let v, C = B < 255 ? B >= 48 && B <= 57 || B >= 97 && B <= 122 ? 2 : B >= 65 && B <= 90 ? 1 : 0 : (v = nh(B)) != v.toLowerCase() ? 1 : v != v.toUpperCase() ? 2 : 0;
      (!y || C == 1 && m || x == 0 && C != 0) && (e[c] == B || s[c] == B && (f = !0) ? o[c++] = y : o.length && (p = !1)), x = C, y += ue(B);
    }
    return c == a && o[0] == 0 && p ? this.result(-100 + (f ? -200 : 0), o, t) : g == a && _ == 0 ? [-200 - t.length, 0, A] : l > -1 ? [-700 - t.length, l, l + this.pattern.length] : g == a ? [-200 + -700 - t.length, _, A] : c == a ? this.result(-100 + (f ? -200 : 0) + -700 + (p ? 0 : -1100), o, t) : e.length == 2 ? null : this.result((n[0] ? -700 : 0) + -200 + -1100, n, t);
  }
  result(t, e, s) {
    let n = [t - s.length], r = 1;
    for (let o of e) {
      let l = o + (this.astral ? ue(It(s, o)) : 1);
      r > 1 && n[r - 1] == o ? n[r - 1] = l : (n[r++] = o, n[r++] = l);
    }
    return n;
  }
}
const He = /* @__PURE__ */ q.define({
  combine(i) {
    return si(i, {
      activateOnTyping: !0,
      selectOnOpen: !0,
      override: null,
      closeOnBlur: !0,
      maxRenderedOptions: 100,
      defaultKeymap: !0,
      optionClass: () => "",
      aboveCursor: !1,
      icons: !0,
      addToOptions: [],
      compareCompletions: (t, e) => t.label.localeCompare(e.label)
    }, {
      defaultKeymap: (t, e) => t && e,
      closeOnBlur: (t, e) => t && e,
      icons: (t, e) => t && e,
      optionClass: (t, e) => (s) => Tk(t(s), e(s)),
      addToOptions: (t, e) => t.concat(e)
    });
  }
});
function Tk(i, t) {
  return i ? t ? i + " " + t : i : t;
}
function Pk(i) {
  let t = i.addToOptions.slice();
  return i.icons && t.push({
    render(e) {
      let s = document.createElement("div");
      return s.classList.add("cm-completionIcon"), e.type && s.classList.add(...e.type.split(/\s+/g).map((n) => "cm-completionIcon-" + n)), s.setAttribute("aria-hidden", "true"), s;
    },
    position: 20
  }), t.push({
    render(e, s, n) {
      let r = document.createElement("span");
      r.className = "cm-completionLabel";
      let { label: o } = e, l = 0;
      for (let a = 1; a < n.length; ) {
        let h = n[a++], u = n[a++];
        h > l && r.appendChild(document.createTextNode(o.slice(l, h)));
        let c = r.appendChild(document.createElement("span"));
        c.appendChild(document.createTextNode(o.slice(h, u))), c.className = "cm-completionMatchedText", l = u;
      }
      return l < o.length && r.appendChild(document.createTextNode(o.slice(l))), r;
    },
    position: 50
  }, {
    render(e) {
      if (!e.detail)
        return null;
      let s = document.createElement("span");
      return s.className = "cm-completionDetail", s.textContent = e.detail, s;
    },
    position: 80
  }), t.sort((e, s) => e.position - s.position).map((e) => e.render);
}
function Kc(i, t, e) {
  if (i <= e)
    return { from: 0, to: i };
  if (t < 0 && (t = 0), t <= i >> 1) {
    let n = Math.floor(t / e);
    return { from: n * e, to: (n + 1) * e };
  }
  let s = Math.floor((i - t) / e);
  return { from: i - (s + 1) * e, to: i - s * e };
}
class Ek {
  constructor(t, e) {
    this.view = t, this.stateField = e, this.info = null, this.placeInfo = {
      read: () => this.measureInfo(),
      write: (l) => this.positionInfo(l),
      key: this
    };
    let s = t.state.field(e), { options: n, selected: r } = s.open, o = t.state.facet(He);
    this.optionContent = Pk(o), this.optionClass = o.optionClass, this.range = Kc(n.length, r, o.maxRenderedOptions), this.dom = document.createElement("div"), this.dom.className = "cm-tooltip-autocomplete", this.dom.addEventListener("mousedown", (l) => {
      for (let a = l.target, h; a && a != this.dom; a = a.parentNode)
        if (a.nodeName == "LI" && (h = /-(\d+)$/.exec(a.id)) && +h[1] < n.length) {
          km(t, n[+h[1]]), l.preventDefault();
          return;
        }
    }), this.list = this.dom.appendChild(this.createListBox(n, s.id, this.range)), this.list.addEventListener("scroll", () => {
      this.info && this.view.requestMeasure(this.placeInfo);
    });
  }
  mount() {
    this.updateSel();
  }
  update(t) {
    t.state.field(this.stateField) != t.startState.field(this.stateField) && this.updateSel();
  }
  positioned() {
    this.info && this.view.requestMeasure(this.placeInfo);
  }
  updateSel() {
    let t = this.view.state.field(this.stateField), e = t.open;
    if ((e.selected < this.range.from || e.selected >= this.range.to) && (this.range = Kc(e.options.length, e.selected, this.view.state.facet(He).maxRenderedOptions), this.list.remove(), this.list = this.dom.appendChild(this.createListBox(e.options, t.id, this.range)), this.list.addEventListener("scroll", () => {
      this.info && this.view.requestMeasure(this.placeInfo);
    })), this.updateSelectedOption(e.selected)) {
      this.info && (this.info.remove(), this.info = null);
      let { completion: s } = e.options[e.selected], { info: n } = s;
      if (!n)
        return;
      let r = typeof n == "string" ? document.createTextNode(n) : n(s);
      if (!r)
        return;
      "then" in r ? r.then((o) => {
        o && this.view.state.field(this.stateField, !1) == t && this.addInfoPane(o);
      }).catch((o) => ge(this.view.state, o, "completion info")) : this.addInfoPane(r);
    }
  }
  addInfoPane(t) {
    let e = this.info = document.createElement("div");
    e.className = "cm-tooltip cm-completionInfo", e.appendChild(t), this.dom.appendChild(e), this.view.requestMeasure(this.placeInfo);
  }
  updateSelectedOption(t) {
    let e = null;
    for (let s = this.list.firstChild, n = this.range.from; s; s = s.nextSibling, n++)
      n == t ? s.hasAttribute("aria-selected") || (s.setAttribute("aria-selected", "true"), e = s) : s.hasAttribute("aria-selected") && s.removeAttribute("aria-selected");
    return e && Rk(this.list, e), e;
  }
  measureInfo() {
    let t = this.dom.querySelector("[aria-selected]");
    if (!t || !this.info)
      return null;
    let e = this.dom.getBoundingClientRect(), s = this.info.getBoundingClientRect(), n = t.getBoundingClientRect();
    if (n.top > Math.min(innerHeight, e.bottom) - 10 || n.bottom < Math.max(0, e.top) + 10)
      return null;
    let r = Math.max(0, Math.min(n.top, innerHeight - s.height)) - e.top, o = this.view.textDirection == St.RTL, l = e.left, a = innerWidth - e.right;
    return o && l < Math.min(s.width, a) ? o = !1 : !o && a < Math.min(s.width, l) && (o = !0), { top: r, left: o };
  }
  positionInfo(t) {
    this.info && (this.info.style.top = (t ? t.top : -1e6) + "px", t && (this.info.classList.toggle("cm-completionInfo-left", t.left), this.info.classList.toggle("cm-completionInfo-right", !t.left)));
  }
  createListBox(t, e, s) {
    const n = document.createElement("ul");
    n.id = e, n.setAttribute("role", "listbox"), n.setAttribute("aria-expanded", "true"), n.setAttribute("aria-label", this.view.state.phrase("Completions"));
    for (let r = s.from; r < s.to; r++) {
      let { completion: o, match: l } = t[r];
      const a = n.appendChild(document.createElement("li"));
      a.id = e + "-" + r, a.setAttribute("role", "option");
      let h = this.optionClass(o);
      h && (a.className = h);
      for (let u of this.optionContent) {
        let c = u(o, this.view.state, l);
        c && a.appendChild(c);
      }
    }
    return s.from && n.classList.add("cm-completionListIncompleteTop"), s.to < t.length && n.classList.add("cm-completionListIncompleteBottom"), n;
  }
}
function Mk(i) {
  return (t) => new Ek(t, i);
}
function Rk(i, t) {
  let e = i.getBoundingClientRect(), s = t.getBoundingClientRect();
  s.top < e.top ? i.scrollTop -= e.top - s.top : s.bottom > e.bottom && (i.scrollTop += s.bottom - e.bottom);
}
function Xc(i) {
  return (i.boost || 0) * 100 + (i.apply ? 10 : 0) + (i.info ? 5 : 0) + (i.type ? 1 : 0);
}
function Dk(i, t) {
  let e = [], s = 0;
  for (let l of i)
    if (l.hasResult())
      if (l.result.filter === !1) {
        let a = l.result.getMatch;
        for (let h of l.result.options) {
          let u = [1e9 - s++];
          if (a)
            for (let c of a(h))
              u.push(c);
          e.push(new Hc(h, l, u));
        }
      } else {
        let a = new Ak(t.sliceDoc(l.from, l.to)), h;
        for (let u of l.result.options)
          (h = a.match(u.label)) && (u.boost != null && (h[0] += u.boost), e.push(new Hc(u, l, h)));
      }
  let n = [], r = null, o = t.facet(He).compareCompletions;
  for (let l of e.sort((a, h) => h.match[0] - a.match[0] || o(a.completion, h.completion)))
    !r || r.label != l.completion.label || r.detail != l.completion.detail || r.type != null && l.completion.type != null && r.type != l.completion.type || r.apply != l.completion.apply ? n.push(l) : Xc(l.completion) > Xc(r) && (n[n.length - 1] = l), r = l.completion;
  return n;
}
class nn {
  constructor(t, e, s, n, r) {
    this.options = t, this.attrs = e, this.tooltip = s, this.timestamp = n, this.selected = r;
  }
  setSelected(t, e) {
    return t == this.selected || t >= this.options.length ? this : new nn(this.options, Gc(e, t), this.tooltip, this.timestamp, t);
  }
  static build(t, e, s, n, r) {
    let o = Dk(t, e);
    if (!o.length)
      return null;
    let l = e.facet(He).selectOnOpen ? 0 : -1;
    if (n && n.selected != l && n.selected != -1) {
      let a = n.options[n.selected].completion;
      for (let h = 0; h < o.length; h++)
        if (o[h].completion == a) {
          l = h;
          break;
        }
    }
    return new nn(o, Gc(s, l), {
      pos: t.reduce((a, h) => h.hasResult() ? Math.min(a, h.from) : a, 1e8),
      create: Mk(ye),
      above: r.aboveCursor
    }, n ? n.timestamp : Date.now(), l);
  }
  map(t) {
    return new nn(this.options, this.attrs, Object.assign(Object.assign({}, this.tooltip), { pos: t.mapPos(this.tooltip.pos) }), this.timestamp, this.selected);
  }
}
class Gr {
  constructor(t, e, s) {
    this.active = t, this.id = e, this.open = s;
  }
  static start() {
    return new Gr(Lk, "cm-ac-" + Math.floor(Math.random() * 2e6).toString(36), null);
  }
  update(t) {
    let { state: e } = t, s = e.facet(He), r = (s.override || e.languageDataAt("autocomplete", xi(e)).map(Ck)).map((l) => (this.active.find((h) => h.source == l) || new ee(l, this.active.some((h) => h.state != 0) ? 1 : 0)).update(t, s));
    r.length == this.active.length && r.every((l, a) => l == this.active[a]) && (r = this.active);
    let o = t.selection || r.some((l) => l.hasResult() && t.changes.touchesRange(l.from, l.to)) || !Bk(r, this.active) ? nn.build(r, e, this.id, this.open, s) : this.open && t.docChanged ? this.open.map(t.changes) : this.open;
    !o && r.every((l) => l.state != 1) && r.some((l) => l.hasResult()) && (r = r.map((l) => l.hasResult() ? new ee(l.source, 0) : l));
    for (let l of t.effects)
      l.is(Sm) && (o = o && o.setSelected(l.value, this.id));
    return r == this.active && o == this.open ? this : new Gr(r, this.id, o);
  }
  get tooltip() {
    return this.open ? this.open.tooltip : null;
  }
  get attrs() {
    return this.open ? this.open.attrs : Nk;
  }
}
function Bk(i, t) {
  if (i == t)
    return !0;
  for (let e = 0, s = 0; ; ) {
    for (; e < i.length && !i[e].hasResult; )
      e++;
    for (; s < t.length && !t[s].hasResult; )
      s++;
    let n = e == i.length, r = s == t.length;
    if (n || r)
      return n == r;
    if (i[e++].result != t[s++].result)
      return !1;
  }
}
const Nk = {
  "aria-autocomplete": "list"
};
function Gc(i, t) {
  let e = {
    "aria-autocomplete": "list",
    "aria-haspopup": "listbox",
    "aria-controls": i
  };
  return t > -1 && (e["aria-activedescendant"] = i + "-" + t), e;
}
const Lk = [];
function Ia(i) {
  return i.isUserEvent("input.type") ? "input" : i.isUserEvent("delete.backward") ? "delete" : null;
}
class ee {
  constructor(t, e, s = -1) {
    this.source = t, this.state = e, this.explicitPos = s;
  }
  hasResult() {
    return !1;
  }
  update(t, e) {
    let s = Ia(t), n = this;
    s ? n = n.handleUserEvent(t, s, e) : t.docChanged ? n = n.handleChange(t) : t.selection && n.state != 0 && (n = new ee(n.source, 0));
    for (let r of t.effects)
      if (r.is(Ph))
        n = new ee(n.source, 1, r.value ? xi(t.state) : -1);
      else if (r.is(Jr))
        n = new ee(n.source, 0);
      else if (r.is(Om))
        for (let o of r.value)
          o.source == n.source && (n = o);
    return n;
  }
  handleUserEvent(t, e, s) {
    return e == "delete" || !s.activateOnTyping ? this.map(t.changes) : new ee(this.source, 1);
  }
  handleChange(t) {
    return t.changes.touchesRange(xi(t.startState)) ? new ee(this.source, 0) : this.map(t.changes);
  }
  map(t) {
    return t.empty || this.explicitPos < 0 ? this : new ee(this.source, this.state, t.mapPos(this.explicitPos));
  }
}
class rn extends ee {
  constructor(t, e, s, n, r) {
    super(t, 2, e), this.result = s, this.from = n, this.to = r;
  }
  hasResult() {
    return !0;
  }
  handleUserEvent(t, e, s) {
    var n;
    let r = t.changes.mapPos(this.from), o = t.changes.mapPos(this.to, 1), l = xi(t.state);
    if ((this.explicitPos < 0 ? l <= r : l < this.from) || l > o || e == "delete" && xi(t.startState) == this.from)
      return new ee(this.source, e == "input" && s.activateOnTyping ? 1 : 0);
    let a = this.explicitPos < 0 ? -1 : t.changes.mapPos(this.explicitPos), h;
    return Ik(this.result.validFor, t.state, r, o) ? new rn(this.source, a, this.result, r, o) : this.result.update && (h = this.result.update(this.result, r, o, new vm(t.state, l, a >= 0))) ? new rn(this.source, a, h, h.from, (n = h.to) !== null && n !== void 0 ? n : xi(t.state)) : new ee(this.source, 1, a);
  }
  handleChange(t) {
    return t.changes.touchesRange(this.from, this.to) ? new ee(this.source, 0) : this.map(t.changes);
  }
  map(t) {
    return t.empty ? this : new rn(this.source, this.explicitPos < 0 ? -1 : t.mapPos(this.explicitPos), this.result, t.mapPos(this.from), t.mapPos(this.to, 1));
  }
}
function Ik(i, t, e, s) {
  if (!i)
    return !1;
  let n = t.sliceDoc(e, s);
  return typeof i == "function" ? i(n, e, s, t) : xm(i, !0).test(n);
}
const Ph = /* @__PURE__ */ rt.define(), Jr = /* @__PURE__ */ rt.define(), Om = /* @__PURE__ */ rt.define({
  map(i, t) {
    return i.map((e) => e.map(t));
  }
}), Sm = /* @__PURE__ */ rt.define(), ye = /* @__PURE__ */ Vt.define({
  create() {
    return Gr.start();
  },
  update(i, t) {
    return i.update(t);
  },
  provide: (i) => [
    dh.from(i, (t) => t.tooltip),
    H.contentAttributes.from(i, (t) => t.attrs)
  ]
}), Cm = 75;
function Jn(i, t = "option") {
  return (e) => {
    let s = e.state.field(ye, !1);
    if (!s || !s.open || Date.now() - s.open.timestamp < Cm)
      return !1;
    let n = 1, r;
    t == "page" && (r = Nw(e, s.open.tooltip)) && (n = Math.max(2, Math.floor(r.dom.offsetHeight / r.dom.querySelector("li").offsetHeight) - 1));
    let { length: o } = s.open.options, l = s.open.selected > -1 ? s.open.selected + n * (i ? 1 : -1) : i ? 0 : o - 1;
    return l < 0 ? l = t == "page" ? 0 : o - 1 : l >= o && (l = t == "page" ? o - 1 : 0), e.dispatch({ effects: Sm.of(l) }), !0;
  };
}
const Qk = (i) => {
  let t = i.state.field(ye, !1);
  return i.state.readOnly || !t || !t.open || Date.now() - t.open.timestamp < Cm || t.open.selected < 0 ? !1 : (km(i, t.open.options[t.open.selected]), !0);
}, zk = (i) => i.state.field(ye, !1) ? (i.dispatch({ effects: Ph.of(!0) }), !0) : !1, Wk = (i) => {
  let t = i.state.field(ye, !1);
  return !t || !t.active.some((e) => e.state != 0) ? !1 : (i.dispatch({ effects: Jr.of(null) }), !0);
};
class $k {
  constructor(t, e) {
    this.active = t, this.context = e, this.time = Date.now(), this.updates = [], this.done = void 0;
  }
}
const Jc = 50, Fk = 50, Vk = 1e3, Uk = /* @__PURE__ */ At.fromClass(class {
  constructor(i) {
    this.view = i, this.debounceUpdate = -1, this.running = [], this.debounceAccept = -1, this.composing = 0;
    for (let t of i.state.field(ye).active)
      t.state == 1 && this.startQuery(t);
  }
  update(i) {
    let t = i.state.field(ye);
    if (!i.selectionSet && !i.docChanged && i.startState.field(ye) == t)
      return;
    let e = i.transactions.some((s) => (s.selection || s.docChanged) && !Ia(s));
    for (let s = 0; s < this.running.length; s++) {
      let n = this.running[s];
      if (e || n.updates.length + i.transactions.length > Fk && Date.now() - n.time > Vk) {
        for (let r of n.context.abortListeners)
          try {
            r();
          } catch (o) {
            ge(this.view.state, o);
          }
        n.context.abortListeners = null, this.running.splice(s--, 1);
      } else
        n.updates.push(...i.transactions);
    }
    if (this.debounceUpdate > -1 && clearTimeout(this.debounceUpdate), this.debounceUpdate = t.active.some((s) => s.state == 1 && !this.running.some((n) => n.active.source == s.source)) ? setTimeout(() => this.startUpdate(), Jc) : -1, this.composing != 0)
      for (let s of i.transactions)
        Ia(s) == "input" ? this.composing = 2 : this.composing == 2 && s.selection && (this.composing = 3);
  }
  startUpdate() {
    this.debounceUpdate = -1;
    let { state: i } = this.view, t = i.field(ye);
    for (let e of t.active)
      e.state == 1 && !this.running.some((s) => s.active.source == e.source) && this.startQuery(e);
  }
  startQuery(i) {
    let { state: t } = this.view, e = xi(t), s = new vm(t, e, i.explicitPos == e), n = new $k(i, s);
    this.running.push(n), Promise.resolve(i.source(s)).then((r) => {
      n.context.aborted || (n.done = r || null, this.scheduleAccept());
    }, (r) => {
      this.view.dispatch({ effects: Jr.of(null) }), ge(this.view.state, r);
    });
  }
  scheduleAccept() {
    this.running.every((i) => i.done !== void 0) ? this.accept() : this.debounceAccept < 0 && (this.debounceAccept = setTimeout(() => this.accept(), Jc));
  }
  accept() {
    var i;
    this.debounceAccept > -1 && clearTimeout(this.debounceAccept), this.debounceAccept = -1;
    let t = [], e = this.view.state.facet(He);
    for (let s = 0; s < this.running.length; s++) {
      let n = this.running[s];
      if (n.done === void 0)
        continue;
      if (this.running.splice(s--, 1), n.done) {
        let o = new rn(n.active.source, n.active.explicitPos, n.done, n.done.from, (i = n.done.to) !== null && i !== void 0 ? i : xi(n.updates.length ? n.updates[0].startState : this.view.state));
        for (let l of n.updates)
          o = o.update(l, e);
        if (o.hasResult()) {
          t.push(o);
          continue;
        }
      }
      let r = this.view.state.field(ye).active.find((o) => o.source == n.active.source);
      if (r && r.state == 1)
        if (n.done == null) {
          let o = new ee(n.active.source, 0);
          for (let l of n.updates)
            o = o.update(l, e);
          o.state != 1 && t.push(o);
        } else
          this.startQuery(r);
    }
    t.length && this.view.dispatch({ effects: Om.of(t) });
  }
}, {
  eventHandlers: {
    blur() {
      let i = this.view.state.field(ye, !1);
      i && i.tooltip && this.view.state.facet(He).closeOnBlur && this.view.dispatch({ effects: Jr.of(null) });
    },
    compositionstart() {
      this.composing = 1;
    },
    compositionend() {
      this.composing == 3 && setTimeout(() => this.view.dispatch({ effects: Ph.of(!1) }), 20), this.composing = 0;
    }
  }
}), jk = /* @__PURE__ */ H.baseTheme({
  ".cm-tooltip.cm-tooltip-autocomplete": {
    "& > ul": {
      fontFamily: "monospace",
      whiteSpace: "nowrap",
      overflow: "hidden auto",
      maxWidth_fallback: "700px",
      maxWidth: "min(700px, 95vw)",
      minWidth: "250px",
      maxHeight: "10em",
      listStyle: "none",
      margin: 0,
      padding: 0,
      "& > li": {
        overflowX: "hidden",
        textOverflow: "ellipsis",
        cursor: "pointer",
        padding: "1px 3px",
        lineHeight: 1.2
      }
    }
  },
  "&light .cm-tooltip-autocomplete ul li[aria-selected]": {
    background: "#17c",
    color: "white"
  },
  "&dark .cm-tooltip-autocomplete ul li[aria-selected]": {
    background: "#347",
    color: "white"
  },
  ".cm-completionListIncompleteTop:before, .cm-completionListIncompleteBottom:after": {
    content: '"\xB7\xB7\xB7"',
    opacity: 0.5,
    display: "block",
    textAlign: "center"
  },
  ".cm-tooltip.cm-completionInfo": {
    position: "absolute",
    padding: "3px 9px",
    width: "max-content",
    maxWidth: "300px"
  },
  ".cm-completionInfo.cm-completionInfo-left": { right: "100%" },
  ".cm-completionInfo.cm-completionInfo-right": { left: "100%" },
  "&light .cm-snippetField": { backgroundColor: "#00000022" },
  "&dark .cm-snippetField": { backgroundColor: "#ffffff22" },
  ".cm-snippetFieldPosition": {
    verticalAlign: "text-top",
    width: 0,
    height: "1.15em",
    margin: "0 -0.7px -.7em",
    borderLeft: "1.4px dotted #888"
  },
  ".cm-completionMatchedText": {
    textDecoration: "underline"
  },
  ".cm-completionDetail": {
    marginLeft: "0.5em",
    fontStyle: "italic"
  },
  ".cm-completionIcon": {
    fontSize: "90%",
    width: ".8em",
    display: "inline-block",
    textAlign: "center",
    paddingRight: ".6em",
    opacity: "0.6"
  },
  ".cm-completionIcon-function, .cm-completionIcon-method": {
    "&:after": { content: "'\u0192'" }
  },
  ".cm-completionIcon-class": {
    "&:after": { content: "'\u25CB'" }
  },
  ".cm-completionIcon-interface": {
    "&:after": { content: "'\u25CC'" }
  },
  ".cm-completionIcon-variable": {
    "&:after": { content: "'\u{1D465}'" }
  },
  ".cm-completionIcon-constant": {
    "&:after": { content: "'\u{1D436}'" }
  },
  ".cm-completionIcon-type": {
    "&:after": { content: "'\u{1D461}'" }
  },
  ".cm-completionIcon-enum": {
    "&:after": { content: "'\u222A'" }
  },
  ".cm-completionIcon-property": {
    "&:after": { content: "'\u25A1'" }
  },
  ".cm-completionIcon-keyword": {
    "&:after": { content: "'\u{1F511}\uFE0E'" }
  },
  ".cm-completionIcon-namespace": {
    "&:after": { content: "'\u25A2'" }
  },
  ".cm-completionIcon-text": {
    "&:after": { content: "'abc'", fontSize: "50%", verticalAlign: "middle" }
  }
}), Yr = {
  brackets: ["(", "[", "{", "'", '"'],
  before: ")]}:;>"
}, Qi = /* @__PURE__ */ rt.define({
  map(i, t) {
    let e = t.mapPos(i, -1, Gt.TrackAfter);
    return e == null ? void 0 : e;
  }
}), Eh = /* @__PURE__ */ rt.define({
  map(i, t) {
    return t.mapPos(i);
  }
}), Mh = /* @__PURE__ */ new class extends ji {
}();
Mh.startSide = 1;
Mh.endSide = -1;
const Am = /* @__PURE__ */ Vt.define({
  create() {
    return ft.empty;
  },
  update(i, t) {
    if (t.selection) {
      let e = t.state.doc.lineAt(t.selection.main.head).from, s = t.startState.doc.lineAt(t.startState.selection.main.head).from;
      e != t.changes.mapPos(s, -1) && (i = ft.empty);
    }
    i = i.map(t.changes);
    for (let e of t.effects)
      e.is(Qi) ? i = i.update({ add: [Mh.range(e.value, e.value + 1)] }) : e.is(Eh) && (i = i.update({ filter: (s) => s != e.value }));
    return i;
  }
});
function Hk() {
  return [Kk, Am];
}
const cl = "()[]{}<>";
function Tm(i) {
  for (let t = 0; t < cl.length; t += 2)
    if (cl.charCodeAt(t) == i)
      return cl.charAt(t + 1);
  return nh(i < 128 ? i : i + 1);
}
function Pm(i, t) {
  return i.languageDataAt("closeBrackets", t)[0] || Yr;
}
const qk = typeof navigator == "object" && /* @__PURE__ */ /Android\b/.test(navigator.userAgent), Kk = /* @__PURE__ */ H.inputHandler.of((i, t, e, s) => {
  if ((qk ? i.composing : i.compositionStarted) || i.state.readOnly)
    return !1;
  let n = i.state.selection.main;
  if (s.length > 2 || s.length == 2 && ue(It(s, 0)) == 1 || t != n.from || e != n.to)
    return !1;
  let r = Jk(i.state, s);
  return r ? (i.dispatch(r), !0) : !1;
}), Xk = ({ state: i, dispatch: t }) => {
  if (i.readOnly)
    return !1;
  let s = Pm(i, i.selection.main.head).brackets || Yr.brackets, n = null, r = i.changeByRange((o) => {
    if (o.empty) {
      let l = Yk(i.doc, o.head);
      for (let a of s)
        if (a == l && Ro(i.doc, o.head) == Tm(It(a, 0)))
          return {
            changes: { from: o.head - a.length, to: o.head + a.length },
            range: R.cursor(o.head - a.length),
            userEvent: "delete.backward"
          };
    }
    return { range: n = o };
  });
  return n || t(i.update(r, { scrollIntoView: !0 })), !n;
}, Gk = [
  { key: "Backspace", run: Xk }
];
function Jk(i, t) {
  let e = Pm(i, i.selection.main.head), s = e.brackets || Yr.brackets;
  for (let n of s) {
    let r = Tm(It(n, 0));
    if (t == n)
      return r == n ? eO(i, n, s.indexOf(n + n + n) > -1) : Zk(i, n, r, e.before || Yr.before);
    if (t == r && Em(i, i.selection.main.from))
      return tO(i, n, r);
  }
  return null;
}
function Em(i, t) {
  let e = !1;
  return i.field(Am).between(0, i.doc.length, (s) => {
    s == t && (e = !0);
  }), e;
}
function Ro(i, t) {
  let e = i.sliceString(t, t + 2);
  return e.slice(0, ue(It(e, 0)));
}
function Yk(i, t) {
  let e = i.sliceString(t - 2, t);
  return ue(It(e, 0)) == e.length ? e : e.slice(1);
}
function Zk(i, t, e, s) {
  let n = null, r = i.changeByRange((o) => {
    if (!o.empty)
      return {
        changes: [{ insert: t, from: o.from }, { insert: e, from: o.to }],
        effects: Qi.of(o.to + t.length),
        range: R.range(o.anchor + t.length, o.head + t.length)
      };
    let l = Ro(i.doc, o.head);
    return !l || /\s/.test(l) || s.indexOf(l) > -1 ? {
      changes: { insert: t + e, from: o.head },
      effects: Qi.of(o.head + t.length),
      range: R.cursor(o.head + t.length)
    } : { range: n = o };
  });
  return n ? null : i.update(r, {
    scrollIntoView: !0,
    userEvent: "input.type"
  });
}
function tO(i, t, e) {
  let s = null, n = i.selection.ranges.map((r) => r.empty && Ro(i.doc, r.head) == e ? R.cursor(r.head + e.length) : s = r);
  return s ? null : i.update({
    selection: R.create(n, i.selection.mainIndex),
    scrollIntoView: !0,
    effects: i.selection.ranges.map(({ from: r }) => Eh.of(r))
  });
}
function eO(i, t, e) {
  let s = null, n = i.changeByRange((r) => {
    if (!r.empty)
      return {
        changes: [{ insert: t, from: r.from }, { insert: t, from: r.to }],
        effects: Qi.of(r.to + t.length),
        range: R.range(r.anchor + t.length, r.head + t.length)
      };
    let o = r.head, l = Ro(i.doc, o);
    if (l == t) {
      if (Yc(i, o))
        return {
          changes: { insert: t + t, from: o },
          effects: Qi.of(o + t.length),
          range: R.cursor(o + t.length)
        };
      if (Em(i, o)) {
        let a = e && i.sliceDoc(o, o + t.length * 3) == t + t + t;
        return {
          range: R.cursor(o + t.length * (a ? 3 : 1)),
          effects: Eh.of(o)
        };
      }
    } else {
      if (e && i.sliceDoc(o - 2 * t.length, o) == t + t && Yc(i, o - 2 * t.length))
        return {
          changes: { insert: t + t + t + t, from: o },
          effects: Qi.of(o + t.length),
          range: R.cursor(o + t.length)
        };
      if (i.charCategorizer(o)(l) != Jt.Word) {
        let a = i.sliceDoc(o - 1, o);
        if (a != t && i.charCategorizer(o)(a) != Jt.Word && !iO(i, o, t))
          return {
            changes: { insert: t + t, from: o },
            effects: Qi.of(o + t.length),
            range: R.cursor(o + t.length)
          };
      }
    }
    return { range: s = r };
  });
  return s ? null : i.update(n, {
    scrollIntoView: !0,
    userEvent: "input.type"
  });
}
function Yc(i, t) {
  let e = Ft(i).resolveInner(t + 1);
  return e.parent && e.from == t;
}
function iO(i, t, e) {
  let s = Ft(i).resolveInner(t, -1);
  for (let n = 0; n < 5; n++) {
    if (i.sliceDoc(s.from, s.from + e.length) == e) {
      let o = s.firstChild;
      for (; o && o.from == s.from && o.to - o.from > e.length; ) {
        if (i.sliceDoc(o.to - e.length, o.to) == e)
          return !1;
        o = o.firstChild;
      }
      return !0;
    }
    let r = s.to == t && s.parent;
    if (!r)
      break;
    s = r;
  }
  return !1;
}
function sO(i = {}) {
  return [
    ye,
    He.of(i),
    Uk,
    nO,
    jk
  ];
}
const Mm = [
  { key: "Ctrl-Space", run: zk },
  { key: "Escape", run: Wk },
  { key: "ArrowDown", run: /* @__PURE__ */ Jn(!0) },
  { key: "ArrowUp", run: /* @__PURE__ */ Jn(!1) },
  { key: "PageDown", run: /* @__PURE__ */ Jn(!0, "page") },
  { key: "PageUp", run: /* @__PURE__ */ Jn(!1, "page") },
  { key: "Enter", run: Qk }
], nO = /* @__PURE__ */ As.highest(/* @__PURE__ */ vo.computeN([He], (i) => i.facet(He).defaultKeymap ? [Mm] : []));
class rO {
  constructor(t, e, s) {
    this.from = t, this.to = e, this.diagnostic = s;
  }
}
class Bi {
  constructor(t, e, s) {
    this.diagnostics = t, this.panel = e, this.selected = s;
  }
  static init(t, e, s) {
    let n = t, r = s.facet(os).markerFilter;
    r && (n = r(n));
    let o = X.set(n.map((l) => l.from == l.to || l.from == l.to - 1 && s.doc.lineAt(l.from).to == l.from ? X.widget({
      widget: new gO(l),
      diagnostic: l
    }).range(l.from) : X.mark({
      attributes: { class: "cm-lintRange cm-lintRange-" + l.severity },
      diagnostic: l
    }).range(l.from, l.to)), !0);
    return new Bi(o, e, ks(o));
  }
}
function ks(i, t = null, e = 0) {
  let s = null;
  return i.between(e, 1e9, (n, r, { spec: o }) => {
    if (!(t && o.diagnostic != t))
      return s = new rO(n, r, o.diagnostic), !1;
  }), s;
}
function oO(i, t) {
  return !!(i.effects.some((e) => e.is(Rh)) || i.changes.touchesRange(t.pos));
}
function Rm(i, t) {
  return i.field(pe, !1) ? t : t.concat(rt.appendConfig.of([
    pe,
    H.decorations.compute([pe], (e) => {
      let { selected: s, panel: n } = e.field(pe);
      return !s || !n || s.from == s.to ? X.none : X.set([
        aO.range(s.from, s.to)
      ]);
    }),
    Bw(hO, { hideOn: oO }),
    _O
  ]));
}
function lO(i, t) {
  return {
    effects: Rm(i, [Rh.of(t)])
  };
}
const Rh = /* @__PURE__ */ rt.define(), Dh = /* @__PURE__ */ rt.define(), Dm = /* @__PURE__ */ rt.define(), pe = /* @__PURE__ */ Vt.define({
  create() {
    return new Bi(X.none, null, null);
  },
  update(i, t) {
    if (t.docChanged) {
      let e = i.diagnostics.map(t.changes), s = null;
      if (i.selected) {
        let n = t.changes.mapPos(i.selected.from, 1);
        s = ks(e, i.selected.diagnostic, n) || ks(e, null, n);
      }
      i = new Bi(e, i.panel, s);
    }
    for (let e of t.effects)
      e.is(Rh) ? i = Bi.init(e.value, i.panel, t.state) : e.is(Dh) ? i = new Bi(i.diagnostics, e.value ? Do.open : null, i.selected) : e.is(Dm) && (i = new Bi(i.diagnostics, i.panel, e.value));
    return i;
  },
  provide: (i) => [
    yn.from(i, (t) => t.panel),
    H.decorations.from(i, (t) => t.diagnostics)
  ]
}), aO = /* @__PURE__ */ X.mark({ class: "cm-lintRange cm-lintRange-active" });
function hO(i, t, e) {
  let { diagnostics: s } = i.state.field(pe), n = [], r = 2e8, o = 0;
  s.between(t - (e < 0 ? 1 : 0), t + (e > 0 ? 1 : 0), (a, h, { spec: u }) => {
    t >= a && t <= h && (a == h || (t > a || e > 0) && (t < h || e < 0)) && (n.push(u.diagnostic), r = Math.min(a, r), o = Math.max(h, o));
  });
  let l = i.state.facet(os).tooltipFilter;
  return l && (n = l(n)), n.length ? {
    pos: r,
    end: o,
    above: i.state.doc.lineAt(r).to < o,
    create() {
      return { dom: uO(i, n) };
    }
  } : null;
}
function uO(i, t) {
  return vt("ul", { class: "cm-tooltip-lint" }, t.map((e) => Nm(i, e, !1)));
}
const cO = (i) => {
  let t = i.state.field(pe, !1);
  (!t || !t.panel) && i.dispatch({ effects: Rm(i.state, [Dh.of(!0)]) });
  let e = bn(i, Do.open);
  return e && e.dom.querySelector(".cm-panel-lint ul").focus(), !0;
}, Zc = (i) => {
  let t = i.state.field(pe, !1);
  return !t || !t.panel ? !1 : (i.dispatch({ effects: Dh.of(!1) }), !0);
}, fO = (i) => {
  let t = i.state.field(pe, !1);
  if (!t)
    return !1;
  let e = i.state.selection.main, s = t.diagnostics.iter(e.to + 1);
  return !s.value && (s = t.diagnostics.iter(0), !s.value || s.from == e.from && s.to == e.to) ? !1 : (i.dispatch({ selection: { anchor: s.from, head: s.to }, scrollIntoView: !0 }), !0);
}, dO = [
  { key: "Mod-Shift-m", run: cO },
  { key: "F8", run: fO }
], pO = /* @__PURE__ */ At.fromClass(class {
  constructor(i) {
    this.view = i, this.timeout = -1, this.set = !0;
    let { delay: t } = i.state.facet(os);
    this.lintTime = Date.now() + t, this.run = this.run.bind(this), this.timeout = setTimeout(this.run, t);
  }
  run() {
    let i = Date.now();
    if (i < this.lintTime - 10)
      setTimeout(this.run, this.lintTime - i);
    else {
      this.set = !1;
      let { state: t } = this.view, { sources: e } = t.facet(os);
      Promise.all(e.map((s) => Promise.resolve(s(this.view)))).then((s) => {
        let n = s.reduce((r, o) => r.concat(o));
        this.view.state.doc == t.doc && this.view.dispatch(lO(this.view.state, n));
      }, (s) => {
        ge(this.view.state, s);
      });
    }
  }
  update(i) {
    let t = i.state.facet(os);
    (i.docChanged || t != i.startState.facet(os)) && (this.lintTime = Date.now() + t.delay, this.set || (this.set = !0, this.timeout = setTimeout(this.run, t.delay)));
  }
  force() {
    this.set && (this.lintTime = Date.now(), this.run());
  }
  destroy() {
    clearTimeout(this.timeout);
  }
}), os = /* @__PURE__ */ q.define({
  combine(i) {
    return Object.assign({ sources: i.map((t) => t.source) }, si(i.map((t) => t.config), {
      delay: 750,
      markerFilter: null,
      tooltipFilter: null
    }));
  },
  enables: pO
});
function Bm(i) {
  let t = [];
  if (i)
    t:
      for (let { name: e } of i) {
        for (let s = 0; s < e.length; s++) {
          let n = e[s];
          if (/[a-zA-Z]/.test(n) && !t.some((r) => r.toLowerCase() == n.toLowerCase())) {
            t.push(n);
            continue t;
          }
        }
        t.push("");
      }
  return t;
}
function Nm(i, t, e) {
  var s;
  let n = e ? Bm(t.actions) : [];
  return vt("li", { class: "cm-diagnostic cm-diagnostic-" + t.severity }, vt("span", { class: "cm-diagnosticText" }, t.renderMessage ? t.renderMessage() : t.message), (s = t.actions) === null || s === void 0 ? void 0 : s.map((r, o) => {
    let l = (c) => {
      c.preventDefault();
      let f = ks(i.state.field(pe).diagnostics, t);
      f && r.apply(i, f.from, f.to);
    }, { name: a } = r, h = n[o] ? a.indexOf(n[o]) : -1, u = h < 0 ? a : [
      a.slice(0, h),
      vt("u", a.slice(h, h + 1)),
      a.slice(h + 1)
    ];
    return vt("button", {
      type: "button",
      class: "cm-diagnosticAction",
      onclick: l,
      onmousedown: l,
      "aria-label": ` Action: ${a}${h < 0 ? "" : ` (access key "${n[o]})"`}.`
    }, u);
  }), t.source && vt("div", { class: "cm-diagnosticSource" }, t.source));
}
class gO extends ni {
  constructor(t) {
    super(), this.diagnostic = t;
  }
  eq(t) {
    return t.diagnostic == this.diagnostic;
  }
  toDOM() {
    return vt("span", { class: "cm-lintPoint cm-lintPoint-" + this.diagnostic.severity });
  }
}
class tf {
  constructor(t, e) {
    this.diagnostic = e, this.id = "item_" + Math.floor(Math.random() * 4294967295).toString(16), this.dom = Nm(t, e, !0), this.dom.id = this.id, this.dom.setAttribute("role", "option");
  }
}
class Do {
  constructor(t) {
    this.view = t, this.items = [];
    let e = (n) => {
      if (n.keyCode == 27)
        Zc(this.view), this.view.focus();
      else if (n.keyCode == 38 || n.keyCode == 33)
        this.moveSelection((this.selectedIndex - 1 + this.items.length) % this.items.length);
      else if (n.keyCode == 40 || n.keyCode == 34)
        this.moveSelection((this.selectedIndex + 1) % this.items.length);
      else if (n.keyCode == 36)
        this.moveSelection(0);
      else if (n.keyCode == 35)
        this.moveSelection(this.items.length - 1);
      else if (n.keyCode == 13)
        this.view.focus();
      else if (n.keyCode >= 65 && n.keyCode <= 90 && this.selectedIndex >= 0) {
        let { diagnostic: r } = this.items[this.selectedIndex], o = Bm(r.actions);
        for (let l = 0; l < o.length; l++)
          if (o[l].toUpperCase().charCodeAt(0) == n.keyCode) {
            let a = ks(this.view.state.field(pe).diagnostics, r);
            a && r.actions[l].apply(t, a.from, a.to);
          }
      } else
        return;
      n.preventDefault();
    }, s = (n) => {
      for (let r = 0; r < this.items.length; r++)
        this.items[r].dom.contains(n.target) && this.moveSelection(r);
    };
    this.list = vt("ul", {
      tabIndex: 0,
      role: "listbox",
      "aria-label": this.view.state.phrase("Diagnostics"),
      onkeydown: e,
      onclick: s
    }), this.dom = vt("div", { class: "cm-panel-lint" }, this.list, vt("button", {
      type: "button",
      name: "close",
      "aria-label": this.view.state.phrase("close"),
      onclick: () => Zc(this.view)
    }, "\xD7")), this.update();
  }
  get selectedIndex() {
    let t = this.view.state.field(pe).selected;
    if (!t)
      return -1;
    for (let e = 0; e < this.items.length; e++)
      if (this.items[e].diagnostic == t.diagnostic)
        return e;
    return -1;
  }
  update() {
    let { diagnostics: t, selected: e } = this.view.state.field(pe), s = 0, n = !1, r = null;
    for (t.between(0, this.view.state.doc.length, (o, l, { spec: a }) => {
      let h = -1, u;
      for (let c = s; c < this.items.length; c++)
        if (this.items[c].diagnostic == a.diagnostic) {
          h = c;
          break;
        }
      h < 0 ? (u = new tf(this.view, a.diagnostic), this.items.splice(s, 0, u), n = !0) : (u = this.items[h], h > s && (this.items.splice(s, h - s), n = !0)), e && u.diagnostic == e.diagnostic ? u.dom.hasAttribute("aria-selected") || (u.dom.setAttribute("aria-selected", "true"), r = u) : u.dom.hasAttribute("aria-selected") && u.dom.removeAttribute("aria-selected"), s++;
    }); s < this.items.length && !(this.items.length == 1 && this.items[0].diagnostic.from < 0); )
      n = !0, this.items.pop();
    this.items.length == 0 && (this.items.push(new tf(this.view, {
      from: -1,
      to: -1,
      severity: "info",
      message: this.view.state.phrase("No diagnostics")
    })), n = !0), r ? (this.list.setAttribute("aria-activedescendant", r.id), this.view.requestMeasure({
      key: this,
      read: () => ({ sel: r.dom.getBoundingClientRect(), panel: this.list.getBoundingClientRect() }),
      write: ({ sel: o, panel: l }) => {
        o.top < l.top ? this.list.scrollTop -= l.top - o.top : o.bottom > l.bottom && (this.list.scrollTop += o.bottom - l.bottom);
      }
    })) : this.selectedIndex < 0 && this.list.removeAttribute("aria-activedescendant"), n && this.sync();
  }
  sync() {
    let t = this.list.firstChild;
    function e() {
      let s = t;
      t = s.nextSibling, s.remove();
    }
    for (let s of this.items)
      if (s.dom.parentNode == this.list) {
        for (; t != s.dom; )
          e();
        t = s.dom.nextSibling;
      } else
        this.list.insertBefore(s.dom, t);
    for (; t; )
      e();
  }
  moveSelection(t) {
    if (this.selectedIndex < 0)
      return;
    let e = this.view.state.field(pe), s = ks(e.diagnostics, this.items[t].diagnostic);
    !s || this.view.dispatch({
      selection: { anchor: s.from, head: s.to },
      scrollIntoView: !0,
      effects: Dm.of(s)
    });
  }
  static open(t) {
    return new Do(t);
  }
}
function mO(i, t = 'viewBox="0 0 40 40"') {
  return `url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" ${t}>${encodeURIComponent(i)}</svg>')`;
}
function fl(i) {
  return mO(`<path d="m0 2.5 l2 -1.5 l1 0 l2 1.5 l1 0" stroke="${i}" fill="none" stroke-width=".7"/>`, 'width="6" height="3"');
}
const _O = /* @__PURE__ */ H.baseTheme({
  ".cm-diagnostic": {
    padding: "3px 6px 3px 8px",
    marginLeft: "-1px",
    display: "block",
    whiteSpace: "pre-wrap"
  },
  ".cm-diagnostic-error": { borderLeft: "5px solid #d11" },
  ".cm-diagnostic-warning": { borderLeft: "5px solid orange" },
  ".cm-diagnostic-info": { borderLeft: "5px solid #999" },
  ".cm-diagnosticAction": {
    font: "inherit",
    border: "none",
    padding: "2px 4px",
    backgroundColor: "#444",
    color: "white",
    borderRadius: "3px",
    marginLeft: "8px"
  },
  ".cm-diagnosticSource": {
    fontSize: "70%",
    opacity: 0.7
  },
  ".cm-lintRange": {
    backgroundPosition: "left bottom",
    backgroundRepeat: "repeat-x",
    paddingBottom: "0.7px"
  },
  ".cm-lintRange-error": { backgroundImage: /* @__PURE__ */ fl("#d11") },
  ".cm-lintRange-warning": { backgroundImage: /* @__PURE__ */ fl("orange") },
  ".cm-lintRange-info": { backgroundImage: /* @__PURE__ */ fl("#999") },
  ".cm-lintRange-active": { backgroundColor: "#ffdd9980" },
  ".cm-tooltip-lint": {
    padding: 0,
    margin: 0
  },
  ".cm-lintPoint": {
    position: "relative",
    "&:after": {
      content: '""',
      position: "absolute",
      bottom: 0,
      left: "-2px",
      borderLeft: "3px solid transparent",
      borderRight: "3px solid transparent",
      borderBottom: "4px solid #d11"
    }
  },
  ".cm-lintPoint-warning": {
    "&:after": { borderBottomColor: "orange" }
  },
  ".cm-lintPoint-info": {
    "&:after": { borderBottomColor: "#999" }
  },
  ".cm-panel.cm-panel-lint": {
    position: "relative",
    "& ul": {
      maxHeight: "100px",
      overflowY: "auto",
      "& [aria-selected]": {
        backgroundColor: "#ddd",
        "& u": { textDecoration: "underline" }
      },
      "&:focus [aria-selected]": {
        background_fallback: "#bdf",
        backgroundColor: "Highlight",
        color_fallback: "white",
        color: "HighlightText"
      },
      "& u": { textDecoration: "none" },
      padding: 0,
      margin: 0
    },
    "& [name=close]": {
      position: "absolute",
      top: "0",
      right: "2px",
      background: "inherit",
      border: "none",
      font: "inherit",
      padding: 0,
      margin: 0
    }
  }
}), bO = /* @__PURE__ */ (() => [
  Uw(),
  qw(),
  hw(),
  ox(),
  Dv(),
  J1(),
  sw(),
  at.allowMultipleSelections.of(!0),
  wv(),
  Pg(Iv, { fallback: !0 }),
  Uv(),
  Hk(),
  sO(),
  kw(),
  Cw(),
  gw(),
  ik(),
  vo.of([
    ...Gk,
    ...Gx,
    ...yk,
    ...gx,
    ...Ev,
    ...Mm,
    ...dO
  ])
])();
/*!
* VueCodemirror v6.0.2
* Copyright (c) Surmon. All rights reserved.
* Released under the MIT License.
* Surmon <https://github.com/surmon-china>
*/
var yO = Object.freeze({ autofocus: !1, disabled: !1, indentWithTab: !0, tabSize: 2, placeholder: "", autoDestroy: !0, extensions: [bO] }), wO = Symbol("vue-codemirror-global-config"), Xt, vO = function(i) {
  var t = i.onUpdate, e = i.onChange, s = i.onFocus, n = i.onBlur, r = function(o, l) {
    var a = {};
    for (var h in o)
      Object.prototype.hasOwnProperty.call(o, h) && l.indexOf(h) < 0 && (a[h] = o[h]);
    if (o != null && typeof Object.getOwnPropertySymbols == "function") {
      var u = 0;
      for (h = Object.getOwnPropertySymbols(o); u < h.length; u++)
        l.indexOf(h[u]) < 0 && Object.prototype.propertyIsEnumerable.call(o, h[u]) && (a[h[u]] = o[h[u]]);
    }
    return a;
  }(i, ["onUpdate", "onChange", "onFocus", "onBlur"]);
  return at.create({ doc: r.doc, selection: r.selection, extensions: (Array.isArray(r.extensions) ? r.extensions : [r.extensions]).concat([H.updateListener.of(function(o) {
    t(o), o.docChanged && e(o.state.doc.toString(), o), o.focusChanged && (o.view.hasFocus ? s(o) : n(o));
  })]) });
}, Ks = function(i) {
  var t = new On();
  return { compartment: t, run: function(e) {
    t.get(i.state) ? i.dispatch({ effects: t.reconfigure(e) }) : i.dispatch({ effects: rt.appendConfig.of(t.of(e)) });
  } };
}, ef = function(i, t) {
  var e = Ks(i), s = e.compartment, n = e.run;
  return function(r) {
    var o = s.get(i.state);
    n((r != null ? r : o !== t) ? t : []);
  };
}, Yn = { type: Boolean, default: void 0 }, xO = { autofocus: Yn, disabled: Yn, indentWithTab: Yn, tabSize: Number, placeholder: String, style: Object, autoDestroy: Yn, root: Object, extensions: Array, selection: Object }, kO = { modelValue: { type: String, default: "" } }, OO = Object.assign(Object.assign({}, xO), kO);
(function(i) {
  i.Change = "change", i.Update = "update", i.Focus = "focus", i.Blur = "blur", i.Ready = "ready", i.ModelUpdate = "update:modelValue";
})(Xt || (Xt = {}));
var Ni = {};
Ni[Xt.Change] = function(i, t) {
  return !0;
}, Ni[Xt.Update] = function(i) {
  return !0;
}, Ni[Xt.Focus] = function(i) {
  return !0;
}, Ni[Xt.Blur] = function(i) {
  return !0;
}, Ni[Xt.Ready] = function(i) {
  return !0;
};
var Lm = {};
Lm[Xt.ModelUpdate] = Ni[Xt.Change];
var SO = Object.assign(Object.assign({}, Ni), Lm), CO = s0({ name: "VueCodemirror", props: Object.assign({}, OO), emits: Object.assign({}, SO), setup: function(i, t) {
  var e = zo(), s = zo(), n = zo(), r = Object.assign(Object.assign({}, yO), lr(wO, {})), o = Vd(function() {
    var l = {};
    return Object.keys(ut(i)).forEach(function(a) {
      var h;
      a !== "modelValue" && (l[a] = (h = i[a]) !== null && h !== void 0 ? h : r[a]);
    }), l;
  });
  return Sd(function() {
    var l;
    s.value = vO({ doc: i.modelValue, selection: o.value.selection, extensions: (l = r.extensions) !== null && l !== void 0 ? l : [], onFocus: function(h) {
      return t.emit(Xt.Focus, h);
    }, onBlur: function(h) {
      return t.emit(Xt.Blur, h);
    }, onUpdate: function(h) {
      return t.emit(Xt.Update, h);
    }, onChange: function(h, u) {
      h !== i.modelValue && (t.emit(Xt.Change, h, u), t.emit(Xt.ModelUpdate, h, u));
    } }), n.value = function(h) {
      return new H(Object.assign({}, h));
    }({ state: s.value, parent: e.value, root: o.value.root });
    var a = function(h) {
      var u = function() {
        return h.state.doc.toString();
      }, c = Ks(h).run, f = ef(h, [H.editable.of(!1), at.readOnly.of(!0)]), g = ef(h, vo.of([Jx])), _ = Ks(h).run, A = Ks(h).run, m = Ks(h).run;
      return { focus: function() {
        return h.focus();
      }, getDoc: u, setDoc: function(p) {
        p !== u() && h.dispatch({ changes: { from: 0, to: h.state.doc.length, insert: p } });
      }, reExtensions: c, toggleDisabled: f, toggleIndentWithTab: g, setTabSize: function(p) {
        _([at.tabSize.of(p), xo.of(" ".repeat(p))]);
      }, setPlaceholder: function(p) {
        A(yw(p));
      }, setStyle: function(p) {
        p === void 0 && (p = {}), m(H.theme({ "&": Object.assign({}, p) }));
      } };
    }(n.value);
    Ie(function() {
      return i.modelValue;
    }, function(h) {
      h !== a.getDoc() && a.setDoc(h);
    }), Ie(function() {
      return i.extensions;
    }, function(h) {
      return a.reExtensions(h || []);
    }, { immediate: !0 }), Ie(function() {
      return o.value.disabled;
    }, function(h) {
      return a.toggleDisabled(h);
    }, { immediate: !0 }), Ie(function() {
      return o.value.indentWithTab;
    }, function(h) {
      return a.toggleIndentWithTab(h);
    }, { immediate: !0 }), Ie(function() {
      return o.value.tabSize;
    }, function(h) {
      return a.setTabSize(h);
    }, { immediate: !0 }), Ie(function() {
      return o.value.placeholder;
    }, function(h) {
      return a.setPlaceholder(h);
    }, { immediate: !0 }), Ie(function() {
      return o.value.style;
    }, function(h) {
      return a.setStyle(h);
    }, { immediate: !0 }), o.value.autofocus && a.focus(), t.emit(Xt.Ready, { state: s.value, view: n.value, container: e.value });
  }), Cd(function() {
    o.value.autoDestroy && n.value && function(l) {
      l.destroy();
    }(n.value);
  }), function() {
    return H0("div", { class: "v-codemirror", style: { display: "contents" }, ref: e });
  };
} }), AO = CO;
class Zr {
  constructor(t, e, s, n, r, o, l, a, h, u = 0, c) {
    this.p = t, this.stack = e, this.state = s, this.reducePos = n, this.pos = r, this.score = o, this.buffer = l, this.bufferBase = a, this.curContext = h, this.lookAhead = u, this.parent = c;
  }
  toString() {
    return `[${this.stack.filter((t, e) => e % 3 == 0).concat(this.state)}]@${this.pos}${this.score ? "!" + this.score : ""}`;
  }
  static start(t, e, s = 0) {
    let n = t.parser.context;
    return new Zr(t, [], e, s, s, 0, [], 0, n ? new sf(n, n.start) : null, 0, null);
  }
  get context() {
    return this.curContext ? this.curContext.context : null;
  }
  pushState(t, e) {
    this.stack.push(this.state, e, this.bufferBase + this.buffer.length), this.state = t;
  }
  reduce(t) {
    let e = t >> 19, s = t & 65535, { parser: n } = this.p, r = n.dynamicPrecedence(s);
    if (r && (this.score += r), e == 0) {
      this.pushState(n.getGoto(this.state, s, !0), this.reducePos), s < n.minRepeatTerm && this.storeNode(s, this.reducePos, this.reducePos, 4, !0), this.reduceContext(s, this.reducePos);
      return;
    }
    let o = this.stack.length - (e - 1) * 3 - (t & 262144 ? 6 : 0), l = this.stack[o - 2], a = this.stack[o - 1], h = this.bufferBase + this.buffer.length - a;
    if (s < n.minRepeatTerm || t & 131072) {
      let u = n.stateFlag(this.state, 1) ? this.pos : this.reducePos;
      this.storeNode(s, l, u, h + 4, !0);
    }
    if (t & 262144)
      this.state = this.stack[o];
    else {
      let u = this.stack[o - 3];
      this.state = n.getGoto(u, s, !0);
    }
    for (; this.stack.length > o; )
      this.stack.pop();
    this.reduceContext(s, l);
  }
  storeNode(t, e, s, n = 4, r = !1) {
    if (t == 0 && (!this.stack.length || this.stack[this.stack.length - 1] < this.buffer.length + this.bufferBase)) {
      let o = this, l = this.buffer.length;
      if (l == 0 && o.parent && (l = o.bufferBase - o.parent.bufferBase, o = o.parent), l > 0 && o.buffer[l - 4] == 0 && o.buffer[l - 1] > -1) {
        if (e == s)
          return;
        if (o.buffer[l - 2] >= e) {
          o.buffer[l - 2] = s;
          return;
        }
      }
    }
    if (!r || this.pos == s)
      this.buffer.push(t, e, s, n);
    else {
      let o = this.buffer.length;
      if (o > 0 && this.buffer[o - 4] != 0)
        for (; o > 0 && this.buffer[o - 2] > s; )
          this.buffer[o] = this.buffer[o - 4], this.buffer[o + 1] = this.buffer[o - 3], this.buffer[o + 2] = this.buffer[o - 2], this.buffer[o + 3] = this.buffer[o - 1], o -= 4, n > 4 && (n -= 4);
      this.buffer[o] = t, this.buffer[o + 1] = e, this.buffer[o + 2] = s, this.buffer[o + 3] = n;
    }
  }
  shift(t, e, s) {
    let n = this.pos;
    if (t & 131072)
      this.pushState(t & 65535, this.pos);
    else if ((t & 262144) == 0) {
      let r = t, { parser: o } = this.p;
      (s > this.pos || e <= o.maxNode) && (this.pos = s, o.stateFlag(r, 1) || (this.reducePos = s)), this.pushState(r, n), this.shiftContext(e, n), e <= o.maxNode && this.buffer.push(e, n, s, 4);
    } else
      this.pos = s, this.shiftContext(e, n), e <= this.p.parser.maxNode && this.buffer.push(e, n, s, 4);
  }
  apply(t, e, s) {
    t & 65536 ? this.reduce(t) : this.shift(t, e, s);
  }
  useNode(t, e) {
    let s = this.p.reused.length - 1;
    (s < 0 || this.p.reused[s] != t) && (this.p.reused.push(t), s++);
    let n = this.pos;
    this.reducePos = this.pos = n + t.length, this.pushState(e, n), this.buffer.push(s, n, this.reducePos, -1), this.curContext && this.updateContext(this.curContext.tracker.reuse(this.curContext.context, t, this, this.p.stream.reset(this.pos - t.length)));
  }
  split() {
    let t = this, e = t.buffer.length;
    for (; e > 0 && t.buffer[e - 2] > t.reducePos; )
      e -= 4;
    let s = t.buffer.slice(e), n = t.bufferBase + e;
    for (; t && n == t.bufferBase; )
      t = t.parent;
    return new Zr(this.p, this.stack.slice(), this.state, this.reducePos, this.pos, this.score, s, n, this.curContext, this.lookAhead, t);
  }
  recoverByDelete(t, e) {
    let s = t <= this.p.parser.maxNode;
    s && this.storeNode(t, this.pos, e, 4), this.storeNode(0, this.pos, e, s ? 8 : 4), this.pos = this.reducePos = e, this.score -= 190;
  }
  canShift(t) {
    for (let e = new TO(this); ; ) {
      let s = this.p.parser.stateSlot(e.state, 4) || this.p.parser.hasAction(e.state, t);
      if ((s & 65536) == 0)
        return !0;
      if (s == 0)
        return !1;
      e.reduce(s);
    }
  }
  recoverByInsert(t) {
    if (this.stack.length >= 300)
      return [];
    let e = this.p.parser.nextStates(this.state);
    if (e.length > 4 << 1 || this.stack.length >= 120) {
      let n = [];
      for (let r = 0, o; r < e.length; r += 2)
        (o = e[r + 1]) != this.state && this.p.parser.hasAction(o, t) && n.push(e[r], o);
      if (this.stack.length < 120)
        for (let r = 0; n.length < 4 << 1 && r < e.length; r += 2) {
          let o = e[r + 1];
          n.some((l, a) => a & 1 && l == o) || n.push(e[r], o);
        }
      e = n;
    }
    let s = [];
    for (let n = 0; n < e.length && s.length < 4; n += 2) {
      let r = e[n + 1];
      if (r == this.state)
        continue;
      let o = this.split();
      o.pushState(r, this.pos), o.storeNode(0, o.pos, o.pos, 4, !0), o.shiftContext(e[n], this.pos), o.score -= 200, s.push(o);
    }
    return s;
  }
  forceReduce() {
    let t = this.p.parser.stateSlot(this.state, 5);
    if ((t & 65536) == 0)
      return !1;
    let { parser: e } = this.p;
    if (!e.validAction(this.state, t)) {
      let s = t >> 19, n = t & 65535, r = this.stack.length - s * 3;
      if (r < 0 || e.getGoto(this.stack[r], n, !1) < 0)
        return !1;
      this.storeNode(0, this.reducePos, this.reducePos, 4, !0), this.score -= 100;
    }
    return this.reducePos = this.pos, this.reduce(t), !0;
  }
  forceAll() {
    for (; !this.p.parser.stateFlag(this.state, 2); )
      if (!this.forceReduce()) {
        this.storeNode(0, this.pos, this.pos, 4, !0);
        break;
      }
    return this;
  }
  get deadEnd() {
    if (this.stack.length != 3)
      return !1;
    let { parser: t } = this.p;
    return t.data[t.stateSlot(this.state, 1)] == 65535 && !t.stateSlot(this.state, 4);
  }
  restart() {
    this.state = this.stack[0], this.stack.length = 0;
  }
  sameState(t) {
    if (this.state != t.state || this.stack.length != t.stack.length)
      return !1;
    for (let e = 0; e < this.stack.length; e += 3)
      if (this.stack[e] != t.stack[e])
        return !1;
    return !0;
  }
  get parser() {
    return this.p.parser;
  }
  dialectEnabled(t) {
    return this.p.parser.dialect.flags[t];
  }
  shiftContext(t, e) {
    this.curContext && this.updateContext(this.curContext.tracker.shift(this.curContext.context, t, this, this.p.stream.reset(e)));
  }
  reduceContext(t, e) {
    this.curContext && this.updateContext(this.curContext.tracker.reduce(this.curContext.context, t, this, this.p.stream.reset(e)));
  }
  emitContext() {
    let t = this.buffer.length - 1;
    (t < 0 || this.buffer[t] != -3) && this.buffer.push(this.curContext.hash, this.reducePos, this.reducePos, -3);
  }
  emitLookAhead() {
    let t = this.buffer.length - 1;
    (t < 0 || this.buffer[t] != -4) && this.buffer.push(this.lookAhead, this.reducePos, this.reducePos, -4);
  }
  updateContext(t) {
    if (t != this.curContext.context) {
      let e = new sf(this.curContext.tracker, t);
      e.hash != this.curContext.hash && this.emitContext(), this.curContext = e;
    }
  }
  setLookAhead(t) {
    t > this.lookAhead && (this.emitLookAhead(), this.lookAhead = t);
  }
  close() {
    this.curContext && this.curContext.tracker.strict && this.emitContext(), this.lookAhead > 0 && this.emitLookAhead();
  }
}
class sf {
  constructor(t, e) {
    this.tracker = t, this.context = e, this.hash = t.strict ? t.hash(e) : 0;
  }
}
var nf;
(function(i) {
  i[i.Insert = 200] = "Insert", i[i.Delete = 190] = "Delete", i[i.Reduce = 100] = "Reduce", i[i.MaxNext = 4] = "MaxNext", i[i.MaxInsertStackDepth = 300] = "MaxInsertStackDepth", i[i.DampenInsertStackDepth = 120] = "DampenInsertStackDepth";
})(nf || (nf = {}));
class TO {
  constructor(t) {
    this.start = t, this.state = t.state, this.stack = t.stack, this.base = this.stack.length;
  }
  reduce(t) {
    let e = t & 65535, s = t >> 19;
    s == 0 ? (this.stack == this.start.stack && (this.stack = this.stack.slice()), this.stack.push(this.state, 0, 0), this.base += 3) : this.base -= (s - 1) * 3;
    let n = this.start.p.parser.getGoto(this.stack[this.base - 3], e, !0);
    this.state = n;
  }
}
class to {
  constructor(t, e, s) {
    this.stack = t, this.pos = e, this.index = s, this.buffer = t.buffer, this.index == 0 && this.maybeNext();
  }
  static create(t, e = t.bufferBase + t.buffer.length) {
    return new to(t, e, e - t.bufferBase);
  }
  maybeNext() {
    let t = this.stack.parent;
    t != null && (this.index = this.stack.bufferBase - t.bufferBase, this.stack = t, this.buffer = t.buffer);
  }
  get id() {
    return this.buffer[this.index - 4];
  }
  get start() {
    return this.buffer[this.index - 3];
  }
  get end() {
    return this.buffer[this.index - 2];
  }
  get size() {
    return this.buffer[this.index - 1];
  }
  next() {
    this.index -= 4, this.pos -= 4, this.index == 0 && this.maybeNext();
  }
  fork() {
    return new to(this.stack, this.pos, this.index);
  }
}
class br {
  constructor() {
    this.start = -1, this.value = -1, this.end = -1, this.extended = -1, this.lookAhead = 0, this.mask = 0, this.context = 0;
  }
}
const rf = new br();
class PO {
  constructor(t, e) {
    this.input = t, this.ranges = e, this.chunk = "", this.chunkOff = 0, this.chunk2 = "", this.chunk2Pos = 0, this.next = -1, this.token = rf, this.rangeIndex = 0, this.pos = this.chunkPos = e[0].from, this.range = e[0], this.end = e[e.length - 1].to, this.readNext();
  }
  resolveOffset(t, e) {
    let s = this.range, n = this.rangeIndex, r = this.pos + t;
    for (; r < s.from; ) {
      if (!n)
        return null;
      let o = this.ranges[--n];
      r -= s.from - o.to, s = o;
    }
    for (; e < 0 ? r > s.to : r >= s.to; ) {
      if (n == this.ranges.length - 1)
        return null;
      let o = this.ranges[++n];
      r += o.from - s.to, s = o;
    }
    return r;
  }
  peek(t) {
    let e = this.chunkOff + t, s, n;
    if (e >= 0 && e < this.chunk.length)
      s = this.pos + t, n = this.chunk.charCodeAt(e);
    else {
      let r = this.resolveOffset(t, 1);
      if (r == null)
        return -1;
      if (s = r, s >= this.chunk2Pos && s < this.chunk2Pos + this.chunk2.length)
        n = this.chunk2.charCodeAt(s - this.chunk2Pos);
      else {
        let o = this.rangeIndex, l = this.range;
        for (; l.to <= s; )
          l = this.ranges[++o];
        this.chunk2 = this.input.chunk(this.chunk2Pos = s), s + this.chunk2.length > l.to && (this.chunk2 = this.chunk2.slice(0, l.to - s)), n = this.chunk2.charCodeAt(0);
      }
    }
    return s >= this.token.lookAhead && (this.token.lookAhead = s + 1), n;
  }
  acceptToken(t, e = 0) {
    let s = e ? this.resolveOffset(e, -1) : this.pos;
    if (s == null || s < this.token.start)
      throw new RangeError("Token end out of bounds");
    this.token.value = t, this.token.end = s;
  }
  getChunk() {
    if (this.pos >= this.chunk2Pos && this.pos < this.chunk2Pos + this.chunk2.length) {
      let { chunk: t, chunkPos: e } = this;
      this.chunk = this.chunk2, this.chunkPos = this.chunk2Pos, this.chunk2 = t, this.chunk2Pos = e, this.chunkOff = this.pos - this.chunkPos;
    } else {
      this.chunk2 = this.chunk, this.chunk2Pos = this.chunkPos;
      let t = this.input.chunk(this.pos), e = this.pos + t.length;
      this.chunk = e > this.range.to ? t.slice(0, this.range.to - this.pos) : t, this.chunkPos = this.pos, this.chunkOff = 0;
    }
  }
  readNext() {
    return this.chunkOff >= this.chunk.length && (this.getChunk(), this.chunkOff == this.chunk.length) ? this.next = -1 : this.next = this.chunk.charCodeAt(this.chunkOff);
  }
  advance(t = 1) {
    for (this.chunkOff += t; this.pos + t >= this.range.to; ) {
      if (this.rangeIndex == this.ranges.length - 1)
        return this.setDone();
      t -= this.range.to - this.pos, this.range = this.ranges[++this.rangeIndex], this.pos = this.range.from;
    }
    return this.pos += t, this.pos >= this.token.lookAhead && (this.token.lookAhead = this.pos + 1), this.readNext();
  }
  setDone() {
    return this.pos = this.chunkPos = this.end, this.range = this.ranges[this.rangeIndex = this.ranges.length - 1], this.chunk = "", this.next = -1;
  }
  reset(t, e) {
    if (e ? (this.token = e, e.start = t, e.lookAhead = t + 1, e.value = e.extended = -1) : this.token = rf, this.pos != t) {
      if (this.pos = t, t == this.end)
        return this.setDone(), this;
      for (; t < this.range.from; )
        this.range = this.ranges[--this.rangeIndex];
      for (; t >= this.range.to; )
        this.range = this.ranges[++this.rangeIndex];
      t >= this.chunkPos && t < this.chunkPos + this.chunk.length ? this.chunkOff = t - this.chunkPos : (this.chunk = "", this.chunkOff = 0), this.readNext();
    }
    return this;
  }
  read(t, e) {
    if (t >= this.chunkPos && e <= this.chunkPos + this.chunk.length)
      return this.chunk.slice(t - this.chunkPos, e - this.chunkPos);
    if (t >= this.chunk2Pos && e <= this.chunk2Pos + this.chunk2.length)
      return this.chunk2.slice(t - this.chunk2Pos, e - this.chunk2Pos);
    if (t >= this.range.from && e <= this.range.to)
      return this.input.read(t, e);
    let s = "";
    for (let n of this.ranges) {
      if (n.from >= e)
        break;
      n.to > t && (s += this.input.read(Math.max(n.from, t), Math.min(n.to, e)));
    }
    return s;
  }
}
class yr {
  constructor(t, e) {
    this.data = t, this.id = e;
  }
  token(t, e) {
    EO(this.data, t, e, this.id);
  }
}
yr.prototype.contextual = yr.prototype.fallback = yr.prototype.extend = !1;
class Bh {
  constructor(t, e = {}) {
    this.token = t, this.contextual = !!e.contextual, this.fallback = !!e.fallback, this.extend = !!e.extend;
  }
}
function EO(i, t, e, s) {
  let n = 0, r = 1 << s, { parser: o } = e.p, { dialect: l } = o;
  t:
    for (; (r & i[n]) != 0; ) {
      let a = i[n + 1];
      for (let f = n + 3; f < a; f += 2)
        if ((i[f + 1] & r) > 0) {
          let g = i[f];
          if (l.allows(g) && (t.token.value == -1 || t.token.value == g || o.overrides(g, t.token.value))) {
            t.acceptToken(g);
            break;
          }
        }
      let h = t.next, u = 0, c = i[n + 2];
      if (t.next < 0 && c > u && i[a + c * 3 - 3] == 65535) {
        n = i[a + c * 3 - 1];
        continue t;
      }
      for (; u < c; ) {
        let f = u + c >> 1, g = a + f + (f << 1), _ = i[g], A = i[g + 1];
        if (h < _)
          c = f;
        else if (h >= A)
          u = f + 1;
        else {
          n = i[g + 2], t.advance();
          continue t;
        }
      }
      break;
    }
}
function Zn(i, t = Uint16Array) {
  if (typeof i != "string")
    return i;
  let e = null;
  for (let s = 0, n = 0; s < i.length; ) {
    let r = 0;
    for (; ; ) {
      let o = i.charCodeAt(s++), l = !1;
      if (o == 126) {
        r = 65535;
        break;
      }
      o >= 92 && o--, o >= 34 && o--;
      let a = o - 32;
      if (a >= 46 && (a -= 46, l = !0), r += a, l)
        break;
      r *= 46;
    }
    e ? e[n++] = r : e = new t(r);
  }
  return e;
}
const ke = typeof process != "undefined" && process.env && /\bparse\b/.test(process.env.LOG);
let dl = null;
var of;
(function(i) {
  i[i.Margin = 25] = "Margin";
})(of || (of = {}));
function lf(i, t, e) {
  let s = i.cursor(zt.IncludeAnonymous);
  for (s.moveTo(t); ; )
    if (!(e < 0 ? s.childBefore(t) : s.childAfter(t)))
      for (; ; ) {
        if ((e < 0 ? s.to < t : s.from > t) && !s.type.isError)
          return e < 0 ? Math.max(0, Math.min(s.to - 1, t - 25)) : Math.min(i.length, Math.max(s.from + 1, t + 25));
        if (e < 0 ? s.prevSibling() : s.nextSibling())
          break;
        if (!s.parent())
          return e < 0 ? 0 : i.length;
      }
}
class MO {
  constructor(t, e) {
    this.fragments = t, this.nodeSet = e, this.i = 0, this.fragment = null, this.safeFrom = -1, this.safeTo = -1, this.trees = [], this.start = [], this.index = [], this.nextFragment();
  }
  nextFragment() {
    let t = this.fragment = this.i == this.fragments.length ? null : this.fragments[this.i++];
    if (t) {
      for (this.safeFrom = t.openStart ? lf(t.tree, t.from + t.offset, 1) - t.offset : t.from, this.safeTo = t.openEnd ? lf(t.tree, t.to + t.offset, -1) - t.offset : t.to; this.trees.length; )
        this.trees.pop(), this.start.pop(), this.index.pop();
      this.trees.push(t.tree), this.start.push(-t.offset), this.index.push(0), this.nextStart = this.safeFrom;
    } else
      this.nextStart = 1e9;
  }
  nodeAt(t) {
    if (t < this.nextStart)
      return null;
    for (; this.fragment && this.safeTo <= t; )
      this.nextFragment();
    if (!this.fragment)
      return null;
    for (; ; ) {
      let e = this.trees.length - 1;
      if (e < 0)
        return this.nextFragment(), null;
      let s = this.trees[e], n = this.index[e];
      if (n == s.children.length) {
        this.trees.pop(), this.start.pop(), this.index.pop();
        continue;
      }
      let r = s.children[n], o = this.start[e] + s.positions[n];
      if (o > t)
        return this.nextStart = o, null;
      if (r instanceof Tt) {
        if (o == t) {
          if (o < this.safeFrom)
            return null;
          let l = o + r.length;
          if (l <= this.safeTo) {
            let a = r.prop(ot.lookAhead);
            if (!a || l + a < this.fragment.to)
              return r;
          }
        }
        this.index[e]++, o + r.length >= Math.max(this.safeFrom, t) && (this.trees.push(r), this.start.push(o), this.index.push(0));
      } else
        this.index[e]++, this.nextStart = o + r.length;
    }
  }
}
class RO {
  constructor(t, e) {
    this.stream = e, this.tokens = [], this.mainToken = null, this.actions = [], this.tokens = t.tokenizers.map((s) => new br());
  }
  getActions(t) {
    let e = 0, s = null, { parser: n } = t.p, { tokenizers: r } = n, o = n.stateSlot(t.state, 3), l = t.curContext ? t.curContext.hash : 0, a = 0;
    for (let h = 0; h < r.length; h++) {
      if ((1 << h & o) == 0)
        continue;
      let u = r[h], c = this.tokens[h];
      if (!(s && !u.fallback) && ((u.contextual || c.start != t.pos || c.mask != o || c.context != l) && (this.updateCachedToken(c, u, t), c.mask = o, c.context = l), c.lookAhead > c.end + 25 && (a = Math.max(c.lookAhead, a)), c.value != 0)) {
        let f = e;
        if (c.extended > -1 && (e = this.addActions(t, c.extended, c.end, e)), e = this.addActions(t, c.value, c.end, e), !u.extend && (s = c, e > f))
          break;
      }
    }
    for (; this.actions.length > e; )
      this.actions.pop();
    return a && t.setLookAhead(a), !s && t.pos == this.stream.end && (s = new br(), s.value = t.p.parser.eofTerm, s.start = s.end = t.pos, e = this.addActions(t, s.value, s.end, e)), this.mainToken = s, this.actions;
  }
  getMainToken(t) {
    if (this.mainToken)
      return this.mainToken;
    let e = new br(), { pos: s, p: n } = t;
    return e.start = s, e.end = Math.min(s + 1, n.stream.end), e.value = s == n.stream.end ? n.parser.eofTerm : 0, e;
  }
  updateCachedToken(t, e, s) {
    if (e.token(this.stream.reset(s.pos, t), s), t.value > -1) {
      let { parser: n } = s.p;
      for (let r = 0; r < n.specialized.length; r++)
        if (n.specialized[r] == t.value) {
          let o = n.specializers[r](this.stream.read(t.start, t.end), s);
          if (o >= 0 && s.p.parser.dialect.allows(o >> 1)) {
            (o & 1) == 0 ? t.value = o >> 1 : t.extended = o >> 1;
            break;
          }
        }
    } else
      t.value = 0, t.end = Math.min(s.p.stream.end, s.pos + 1);
  }
  putAction(t, e, s, n) {
    for (let r = 0; r < n; r += 3)
      if (this.actions[r] == t)
        return n;
    return this.actions[n++] = t, this.actions[n++] = e, this.actions[n++] = s, n;
  }
  addActions(t, e, s, n) {
    let { state: r } = t, { parser: o } = t.p, { data: l } = o;
    for (let a = 0; a < 2; a++)
      for (let h = o.stateSlot(r, a ? 2 : 1); ; h += 3) {
        if (l[h] == 65535)
          if (l[h + 1] == 1)
            h = Ge(l, h + 2);
          else {
            n == 0 && l[h + 1] == 2 && (n = this.putAction(Ge(l, h + 2), e, s, n));
            break;
          }
        l[h] == e && (n = this.putAction(Ge(l, h + 1), e, s, n));
      }
    return n;
  }
}
var af;
(function(i) {
  i[i.Distance = 5] = "Distance", i[i.MaxRemainingPerStep = 3] = "MaxRemainingPerStep", i[i.MinBufferLengthPrune = 500] = "MinBufferLengthPrune", i[i.ForceReduceLimit = 10] = "ForceReduceLimit", i[i.CutDepth = 15e3] = "CutDepth", i[i.CutTo = 9e3] = "CutTo";
})(af || (af = {}));
class DO {
  constructor(t, e, s, n) {
    this.parser = t, this.input = e, this.ranges = n, this.recovering = 0, this.nextStackID = 9812, this.minStackPos = 0, this.reused = [], this.stoppedAt = null, this.stream = new PO(e, n), this.tokens = new RO(t, this.stream), this.topTerm = t.top[1];
    let { from: r } = n[0];
    this.stacks = [Zr.start(this, t.top[0], r)], this.fragments = s.length && this.stream.end - r > t.bufferLength * 4 ? new MO(s, t.nodeSet) : null;
  }
  get parsedPos() {
    return this.minStackPos;
  }
  advance() {
    let t = this.stacks, e = this.minStackPos, s = this.stacks = [], n, r;
    for (let o = 0; o < t.length; o++) {
      let l = t[o];
      for (; ; ) {
        if (this.tokens.mainToken = null, l.pos > e)
          s.push(l);
        else {
          if (this.advanceStack(l, s, t))
            continue;
          {
            n || (n = [], r = []), n.push(l);
            let a = this.tokens.getMainToken(l);
            r.push(a.value, a.end);
          }
        }
        break;
      }
    }
    if (!s.length) {
      let o = n && NO(n);
      if (o)
        return this.stackToTree(o);
      if (this.parser.strict)
        throw ke && n && console.log("Stuck with token " + (this.tokens.mainToken ? this.parser.getName(this.tokens.mainToken.value) : "none")), new SyntaxError("No parse at " + e);
      this.recovering || (this.recovering = 5);
    }
    if (this.recovering && n) {
      let o = this.stoppedAt != null && n[0].pos > this.stoppedAt ? n[0] : this.runRecovery(n, r, s);
      if (o)
        return this.stackToTree(o.forceAll());
    }
    if (this.recovering) {
      let o = this.recovering == 1 ? 1 : this.recovering * 3;
      if (s.length > o)
        for (s.sort((l, a) => a.score - l.score); s.length > o; )
          s.pop();
      s.some((l) => l.reducePos > e) && this.recovering--;
    } else if (s.length > 1) {
      t:
        for (let o = 0; o < s.length - 1; o++) {
          let l = s[o];
          for (let a = o + 1; a < s.length; a++) {
            let h = s[a];
            if (l.sameState(h) || l.buffer.length > 500 && h.buffer.length > 500)
              if ((l.score - h.score || l.buffer.length - h.buffer.length) > 0)
                s.splice(a--, 1);
              else {
                s.splice(o--, 1);
                continue t;
              }
          }
        }
    }
    this.minStackPos = s[0].pos;
    for (let o = 1; o < s.length; o++)
      s[o].pos < this.minStackPos && (this.minStackPos = s[o].pos);
    return null;
  }
  stopAt(t) {
    if (this.stoppedAt != null && this.stoppedAt < t)
      throw new RangeError("Can't move stoppedAt forward");
    this.stoppedAt = t;
  }
  advanceStack(t, e, s) {
    let n = t.pos, { parser: r } = this, o = ke ? this.stackID(t) + " -> " : "";
    if (this.stoppedAt != null && n > this.stoppedAt)
      return t.forceReduce() ? t : null;
    if (this.fragments) {
      let h = t.curContext && t.curContext.tracker.strict, u = h ? t.curContext.hash : 0;
      for (let c = this.fragments.nodeAt(n); c; ) {
        let f = this.parser.nodeSet.types[c.type.id] == c.type ? r.getGoto(t.state, c.type.id) : -1;
        if (f > -1 && c.length && (!h || (c.prop(ot.contextHash) || 0) == u))
          return t.useNode(c, f), ke && console.log(o + this.stackID(t) + ` (via reuse of ${r.getName(c.type.id)})`), !0;
        if (!(c instanceof Tt) || c.children.length == 0 || c.positions[0] > 0)
          break;
        let g = c.children[0];
        if (g instanceof Tt && c.positions[0] == 0)
          c = g;
        else
          break;
      }
    }
    let l = r.stateSlot(t.state, 4);
    if (l > 0)
      return t.reduce(l), ke && console.log(o + this.stackID(t) + ` (via always-reduce ${r.getName(l & 65535)})`), !0;
    if (t.stack.length >= 15e3)
      for (; t.stack.length > 9e3 && t.forceReduce(); )
        ;
    let a = this.tokens.getActions(t);
    for (let h = 0; h < a.length; ) {
      let u = a[h++], c = a[h++], f = a[h++], g = h == a.length || !s, _ = g ? t : t.split();
      if (_.apply(u, c, f), ke && console.log(o + this.stackID(_) + ` (via ${(u & 65536) == 0 ? "shift" : `reduce of ${r.getName(u & 65535)}`} for ${r.getName(c)} @ ${n}${_ == t ? "" : ", split"})`), g)
        return !0;
      _.pos > n ? e.push(_) : s.push(_);
    }
    return !1;
  }
  advanceFully(t, e) {
    let s = t.pos;
    for (; ; ) {
      if (!this.advanceStack(t, null, null))
        return !1;
      if (t.pos > s)
        return hf(t, e), !0;
    }
  }
  runRecovery(t, e, s) {
    let n = null, r = !1;
    for (let o = 0; o < t.length; o++) {
      let l = t[o], a = e[o << 1], h = e[(o << 1) + 1], u = ke ? this.stackID(l) + " -> " : "";
      if (l.deadEnd && (r || (r = !0, l.restart(), ke && console.log(u + this.stackID(l) + " (restarted)"), this.advanceFully(l, s))))
        continue;
      let c = l.split(), f = u;
      for (let g = 0; c.forceReduce() && g < 10 && (ke && console.log(f + this.stackID(c) + " (via force-reduce)"), !this.advanceFully(c, s)); g++)
        ke && (f = this.stackID(c) + " -> ");
      for (let g of l.recoverByInsert(a))
        ke && console.log(u + this.stackID(g) + " (via recover-insert)"), this.advanceFully(g, s);
      this.stream.end > l.pos ? (h == l.pos && (h++, a = 0), l.recoverByDelete(a, h), ke && console.log(u + this.stackID(l) + ` (via recover-delete ${this.parser.getName(a)})`), hf(l, s)) : (!n || n.score < l.score) && (n = l);
    }
    return n;
  }
  stackToTree(t) {
    return t.close(), Tt.build({
      buffer: to.create(t),
      nodeSet: this.parser.nodeSet,
      topID: this.topTerm,
      maxBufferLength: this.parser.bufferLength,
      reused: this.reused,
      start: this.ranges[0].from,
      length: t.pos - this.ranges[0].from,
      minRepeatType: this.parser.minRepeatTerm
    });
  }
  stackID(t) {
    let e = (dl || (dl = /* @__PURE__ */ new WeakMap())).get(t);
    return e || dl.set(t, e = String.fromCodePoint(this.nextStackID++)), e + t;
  }
}
function hf(i, t) {
  for (let e = 0; e < t.length; e++) {
    let s = t[e];
    if (s.pos == i.pos && s.sameState(i)) {
      t[e].score < i.score && (t[e] = i);
      return;
    }
  }
  t.push(i);
}
class BO {
  constructor(t, e, s) {
    this.source = t, this.flags = e, this.disabled = s;
  }
  allows(t) {
    return !this.disabled || this.disabled[t] == 0;
  }
}
class eo extends dg {
  constructor(t) {
    if (super(), this.wrappers = [], t.version != 14)
      throw new RangeError(`Parser version (${t.version}) doesn't match runtime version (${14})`);
    let e = t.nodeNames.split(" ");
    this.minRepeatTerm = e.length;
    for (let l = 0; l < t.repeatNodeCount; l++)
      e.push("");
    let s = Object.keys(t.topRules).map((l) => t.topRules[l][1]), n = [];
    for (let l = 0; l < e.length; l++)
      n.push([]);
    function r(l, a, h) {
      n[l].push([a, a.deserialize(String(h))]);
    }
    if (t.nodeProps)
      for (let l of t.nodeProps) {
        let a = l[0];
        typeof a == "string" && (a = ot[a]);
        for (let h = 1; h < l.length; ) {
          let u = l[h++];
          if (u >= 0)
            r(u, a, l[h++]);
          else {
            let c = l[h + -u];
            for (let f = -u; f > 0; f--)
              r(l[h++], a, c);
            h++;
          }
        }
      }
    this.nodeSet = new gh(e.map((l, a) => ne.define({
      name: a >= this.minRepeatTerm ? void 0 : l,
      id: a,
      props: n[a],
      top: s.indexOf(a) > -1,
      error: a == 0,
      skipped: t.skippedNodes && t.skippedNodes.indexOf(a) > -1
    }))), t.propSources && (this.nodeSet = this.nodeSet.extend(...t.propSources)), this.strict = !1, this.bufferLength = ug;
    let o = Zn(t.tokenData);
    if (this.context = t.context, this.specialized = new Uint16Array(t.specialized ? t.specialized.length : 0), this.specializers = [], t.specialized)
      for (let l = 0; l < t.specialized.length; l++)
        this.specialized[l] = t.specialized[l].term, this.specializers[l] = t.specialized[l].get;
    this.states = Zn(t.states, Uint32Array), this.data = Zn(t.stateData), this.goto = Zn(t.goto), this.maxTerm = t.maxTerm, this.tokenizers = t.tokenizers.map((l) => typeof l == "number" ? new yr(o, l) : l), this.topRules = t.topRules, this.dialects = t.dialects || {}, this.dynamicPrecedences = t.dynamicPrecedences || null, this.tokenPrecTable = t.tokenPrec, this.termNames = t.termNames || null, this.maxNode = this.nodeSet.types.length - 1, this.dialect = this.parseDialect(), this.top = this.topRules[Object.keys(this.topRules)[0]];
  }
  createParse(t, e, s) {
    let n = new DO(this, t, e, s);
    for (let r of this.wrappers)
      n = r(n, t, e, s);
    return n;
  }
  getGoto(t, e, s = !1) {
    let n = this.goto;
    if (e >= n[0])
      return -1;
    for (let r = n[e + 1]; ; ) {
      let o = n[r++], l = o & 1, a = n[r++];
      if (l && s)
        return a;
      for (let h = r + (o >> 1); r < h; r++)
        if (n[r] == t)
          return a;
      if (l)
        return -1;
    }
  }
  hasAction(t, e) {
    let s = this.data;
    for (let n = 0; n < 2; n++)
      for (let r = this.stateSlot(t, n ? 2 : 1), o; ; r += 3) {
        if ((o = s[r]) == 65535)
          if (s[r + 1] == 1)
            o = s[r = Ge(s, r + 2)];
          else {
            if (s[r + 1] == 2)
              return Ge(s, r + 2);
            break;
          }
        if (o == e || o == 0)
          return Ge(s, r + 1);
      }
    return 0;
  }
  stateSlot(t, e) {
    return this.states[t * 6 + e];
  }
  stateFlag(t, e) {
    return (this.stateSlot(t, 0) & e) > 0;
  }
  validAction(t, e) {
    if (e == this.stateSlot(t, 4))
      return !0;
    for (let s = this.stateSlot(t, 1); ; s += 3) {
      if (this.data[s] == 65535)
        if (this.data[s + 1] == 1)
          s = Ge(this.data, s + 2);
        else
          return !1;
      if (e == Ge(this.data, s + 1))
        return !0;
    }
  }
  nextStates(t) {
    let e = [];
    for (let s = this.stateSlot(t, 1); ; s += 3) {
      if (this.data[s] == 65535)
        if (this.data[s + 1] == 1)
          s = Ge(this.data, s + 2);
        else
          break;
      if ((this.data[s + 2] & 1) == 0) {
        let n = this.data[s + 1];
        e.some((r, o) => o & 1 && r == n) || e.push(this.data[s], n);
      }
    }
    return e;
  }
  overrides(t, e) {
    let s = uf(this.data, this.tokenPrecTable, e);
    return s < 0 || uf(this.data, this.tokenPrecTable, t) < s;
  }
  configure(t) {
    let e = Object.assign(Object.create(eo.prototype), this);
    if (t.props && (e.nodeSet = this.nodeSet.extend(...t.props)), t.top) {
      let s = this.topRules[t.top];
      if (!s)
        throw new RangeError(`Invalid top rule name ${t.top}`);
      e.top = s;
    }
    return t.tokenizers && (e.tokenizers = this.tokenizers.map((s) => {
      let n = t.tokenizers.find((r) => r.from == s);
      return n ? n.to : s;
    })), t.specializers && (e.specializers = this.specializers.map((s) => {
      let n = t.specializers.find((r) => r.from == s);
      return n ? n.to : s;
    })), t.contextTracker && (e.context = t.contextTracker), t.dialect && (e.dialect = this.parseDialect(t.dialect)), t.strict != null && (e.strict = t.strict), t.wrap && (e.wrappers = e.wrappers.concat(t.wrap)), t.bufferLength != null && (e.bufferLength = t.bufferLength), e;
  }
  hasWrappers() {
    return this.wrappers.length > 0;
  }
  getName(t) {
    return this.termNames ? this.termNames[t] : String(t <= this.maxNode && this.nodeSet.types[t].name || t);
  }
  get eofTerm() {
    return this.maxNode + 1;
  }
  get topNode() {
    return this.nodeSet.types[this.top[1]];
  }
  dynamicPrecedence(t) {
    let e = this.dynamicPrecedences;
    return e == null ? 0 : e[t] || 0;
  }
  parseDialect(t) {
    let e = Object.keys(this.dialects), s = e.map(() => !1);
    if (t)
      for (let r of t.split(" ")) {
        let o = e.indexOf(r);
        o >= 0 && (s[o] = !0);
      }
    let n = null;
    for (let r = 0; r < e.length; r++)
      if (!s[r])
        for (let o = this.dialects[e[r]], l; (l = this.data[o++]) != 65535; )
          (n || (n = new Uint8Array(this.maxTerm + 1)))[l] = 1;
    return new BO(t, s, n);
  }
  static deserialize(t) {
    return new eo(t);
  }
}
function Ge(i, t) {
  return i[t] | i[t + 1] << 16;
}
function uf(i, t, e) {
  for (let s = t, n; (n = i[s]) != 65535; s++)
    if (n == e)
      return s - t;
  return -1;
}
function NO(i) {
  let t = null;
  for (let e of i) {
    let s = e.p.stoppedAt;
    (e.pos == e.p.stream.end || s != null && e.pos > s) && e.p.parser.stateFlag(e.state, 2) && (!t || t.score < e.score) && (t = e);
  }
  return t;
}
const LO = 93, cf = 1, IO = 94, QO = 95, ff = 2, Im = [
  9,
  10,
  11,
  12,
  13,
  32,
  133,
  160,
  5760,
  8192,
  8193,
  8194,
  8195,
  8196,
  8197,
  8198,
  8199,
  8200,
  8201,
  8202,
  8232,
  8233,
  8239,
  8287,
  12288
], zO = 58, WO = 40, Qm = 95, $O = 91, wr = 45, FO = 46, VO = 35, UO = 37;
function io(i) {
  return i >= 65 && i <= 90 || i >= 97 && i <= 122 || i >= 161;
}
function jO(i) {
  return i >= 48 && i <= 57;
}
const HO = new Bh((i, t) => {
  for (let e = !1, s = 0, n = 0; ; n++) {
    let { next: r } = i;
    if (io(r) || r == wr || r == Qm || e && jO(r))
      !e && (r != wr || n > 0) && (e = !0), s === n && r == wr && s++, i.advance();
    else {
      e && i.acceptToken(r == WO ? IO : s == 2 && t.canShift(ff) ? ff : QO);
      break;
    }
  }
}), qO = new Bh((i) => {
  if (Im.includes(i.peek(-1))) {
    let { next: t } = i;
    (io(t) || t == Qm || t == VO || t == FO || t == $O || t == zO || t == wr) && i.acceptToken(LO);
  }
}), KO = new Bh((i) => {
  if (!Im.includes(i.peek(-1))) {
    let { next: t } = i;
    if (t == UO && (i.advance(), i.acceptToken(cf)), io(t)) {
      do
        i.advance();
      while (io(i.next));
      i.acceptToken(cf);
    }
  }
}), XO = gg({
  "import charset namespace keyframes": O.definitionKeyword,
  "media supports": O.controlKeyword,
  "from to selector": O.keyword,
  NamespaceName: O.namespace,
  KeyframeName: O.labelName,
  TagName: O.tagName,
  ClassName: O.className,
  PseudoClassName: O.constant(O.className),
  IdName: O.labelName,
  "FeatureName PropertyName": O.propertyName,
  AttributeName: O.attributeName,
  NumberLiteral: O.number,
  KeywordQuery: O.keyword,
  UnaryQueryOp: O.operatorKeyword,
  "CallTag ValueName": O.atom,
  VariableName: O.variableName,
  Callee: O.operatorKeyword,
  Unit: O.unit,
  "UniversalSelector NestingSelector": O.definitionOperator,
  AtKeyword: O.keyword,
  MatchOp: O.compareOperator,
  "ChildOp SiblingOp, LogicOp": O.logicOperator,
  BinOp: O.arithmeticOperator,
  Important: O.modifier,
  Comment: O.blockComment,
  ParenthesizedContent: O.special(O.name),
  ColorLiteral: O.color,
  StringLiteral: O.string,
  ":": O.punctuation,
  "PseudoOp #": O.derefOperator,
  "; ,": O.separator,
  "( )": O.paren,
  "[ ]": O.squareBracket,
  "{ }": O.brace
}), GO = { __proto__: null, lang: 32, "nth-child": 32, "nth-last-child": 32, "nth-of-type": 32, dir: 32, url: 60, "url-prefix": 60, domain: 60, regexp: 60, selector: 134 }, JO = { __proto__: null, "@import": 114, "@media": 138, "@charset": 142, "@namespace": 146, "@keyframes": 152, "@supports": 164 }, YO = { __proto__: null, not: 128, only: 128, from: 158, to: 160 }, ZO = eo.deserialize({
  version: 14,
  states: "7WOYQ[OOOOQP'#Cd'#CdOOQP'#Cc'#CcO!ZQ[O'#CfO!}QXO'#CaO#UQ[O'#ChO#aQ[O'#DPO#fQ[O'#DTOOQP'#Ec'#EcO#kQdO'#DeO$VQ[O'#DrO#kQdO'#DtO$hQ[O'#DvO$sQ[O'#DyO$xQ[O'#EPO%WQ[O'#EROOQS'#Eb'#EbOOQS'#ES'#ESQYQ[OOOOQP'#Cg'#CgOOQP,59Q,59QO!ZQ[O,59QO%_Q[O'#EVO%yQWO,58{O&RQ[O,59SO#aQ[O,59kO#fQ[O,59oO%_Q[O,59sO%_Q[O,59uO%_Q[O,59vO'bQ[O'#D`OOQS,58{,58{OOQP'#Ck'#CkOOQO'#C}'#C}OOQP,59S,59SO'iQWO,59SO'nQWO,59SOOQP'#DR'#DROOQP,59k,59kOOQO'#DV'#DVO'sQ`O,59oOOQS'#Cp'#CpO#kQdO'#CqO'{QvO'#CsO)VQtO,5:POOQO'#Cx'#CxO'iQWO'#CwO)kQWO'#CyOOQS'#Ef'#EfOOQO'#Dh'#DhO)pQ[O'#DoO*OQWO'#EiO$xQ[O'#DmO*^QWO'#DpOOQO'#Ej'#EjO%|QWO,5:^O*cQpO,5:`OOQS'#Dx'#DxO*kQWO,5:bO*pQ[O,5:bOOQO'#D{'#D{O*xQWO,5:eO*}QWO,5:kO+VQWO,5:mOOQS-E8Q-E8QOOQP1G.l1G.lO+yQXO,5:qOOQO-E8T-E8TOOQS1G.g1G.gOOQP1G.n1G.nO'iQWO1G.nO'nQWO1G.nOOQP1G/V1G/VO,WQ`O1G/ZO,qQXO1G/_O-XQXO1G/aO-oQXO1G/bO.VQXO'#CdO.zQWO'#DaOOQS,59z,59zO/PQWO,59zO/XQ[O,59zO/`QdO'#CoO/gQ[O'#DOOOQP1G/Z1G/ZO#kQdO1G/ZO/nQpO,59]OOQS,59_,59_O#kQdO,59aO/vQWO1G/kOOQS,59c,59cO/{Q!bO,59eO0TQWO'#DhO0`QWO,5:TO0eQWO,5:ZO$xQ[O,5:VO$xQ[O'#EYO0mQWO,5;TO0xQWO,5:XO%_Q[O,5:[OOQS1G/x1G/xOOQS1G/z1G/zOOQS1G/|1G/|O1ZQWO1G/|O1`QdO'#D|OOQS1G0P1G0POOQS1G0V1G0VOOQS1G0X1G0XOOQP7+$Y7+$YOOQP7+$u7+$uO#kQdO7+$uO#kQdO,59{O1nQ[O'#EXO1xQWO1G/fOOQS1G/f1G/fO1xQWO1G/fO2QQtO'#ETO2uQdO'#EeO3PQWO,59ZO3UQXO'#EhO3]QWO,59jO3bQpO7+$uOOQS1G.w1G.wOOQS1G.{1G.{OOQS7+%V7+%VO3jQWO1G/PO#kQdO1G/oOOQO1G/u1G/uOOQO1G/q1G/qO3oQWO,5:tOOQO-E8W-E8WO3}QXO1G/vOOQS7+%h7+%hO4UQYO'#CsO%|QWO'#EZO4^QdO,5:hOOQS,5:h,5:hO4lQpO<<HaO4tQtO1G/gOOQO,5:s,5:sO5XQ[O,5:sOOQO-E8V-E8VOOQS7+%Q7+%QO5cQWO7+%QOOQS-E8R-E8RO#kQdO'#EUO5kQWO,5;POOQT1G.u1G.uO5sQWO,5;SOOQP1G/U1G/UOOQP<<Ha<<HaOOQS7+$k7+$kO5{QdO7+%ZOOQO7+%b7+%bOOQS,5:u,5:uOOQS-E8X-E8XOOQS1G0S1G0SOOQPAN={AN={O6SQtO'#EWO#kQdO'#EWO6}QdO7+%ROOQO7+%R7+%ROOQO1G0_1G0_OOQS<<Hl<<HlO7_QdO,5:pOOQO-E8S-E8SOOQO<<Hu<<HuO7iQtO,5:rOOQS-E8U-E8UOOQO<<Hm<<Hm",
  stateData: "8j~O#TOSROS~OUWOXWO]TO^TOtUOxVO!Y_O!ZXO!gYO!iZO!k[O!n]O!t^O#RPO#WRO~O#RcO~O]hO^hOpfOtiOxjO|kO!PmO#PlO#WeO~O!RnO~P!`O`sO#QqO#RpO~O#RuO~O#RwO~OQ!QObzOf!QOh!QOn!PO#Q}O#RyO#Z{O~Ob!SO!b!UO!e!VO#R!RO!R#]P~Oh![On!PO#R!ZO~O#R!^O~Ob!SO!b!UO!e!VO#R!RO~O!W#]P~P$VOUWOXWO]TO^TOtUOxVO#RPO#WRO~OpfO!RnO~O`!hO#QqO#RpO~OQ!pOUWOXWO]TO^TOtUOxVO!Y_O!ZXO!gYO!iZO!k[O!n]O!t^O#R!oO#WRO~O!Q!qO~P&^Ob!tO~Ob!uO~Ov!vOz!wO~OP!yObgXjgX!WgX!bgX!egX#RgXagXQgXfgXhgXngXpgX#QgX#ZgXvgX!QgX!VgX~Ob!SOj!zO!b!UO!e!VO#R!RO!W#]P~Ob!}O~Ob!SO!b!UO!e!VO#R#OO~Op#SO!`#RO!R#]X!W#]X~Ob#VO~Oj!zO!W#XO~O!W#YO~Oh#ZOn!PO~O!R#[O~O!RnO!`#RO~O!RnO!W#_O~O]hO^hOtiOxjO|kO!PmO#PlO#WeO~Op!ya!R!yaa!ya~P+_Ov#aOz#bO~O]hO^hOtiOxjO#WeO~Op{i|{i!P{i!R{i#P{ia{i~P,`Op}i|}i!P}i!R}i#P}ia}i~P,`Op!Oi|!Oi!P!Oi!R!Oi#P!Oia!Oi~P,`O]WX]!UX^WXpWXtWXxWX|WX!PWX!RWX#PWX#WWX~O]#cO~O!Q#fO!W#dO~O!Q#fO~P&^Oa#XP~P#kOa#[P~P%_Oa#nOj!zO~O!W#pO~Oh#qOo#qO~O]!^Xa![X!`![X~O]#rO~Oa#sO!`#RO~Op#SO!R#]a!W#]a~O!`#ROp!aa!R!aa!W!aaa!aa~O!W#xO~O!Q#|O!q#zO!r#zO#Z#yO~O!Q!{X!W!{X~P&^O!Q$SO!W#dO~Oj!zOQ!wXa!wXb!wXf!wXh!wXn!wXp!wX#Q!wX#R!wX#Z!wX~Op$VOa#XX~P#kOa$XO~Oa#[X~P!`Oa$ZO~Oj!zOv$[O~Oa$]O~O!`#ROp!|a!R!|a!W!|a~Oa$_O~P+_OP!yO!RgX~O!Q$bO!q#zO!r#zO#Z#yO~Oj!zOv$cO~Oj!zOp$eO!V$gO!Q!Ti!W!Ti~P#kO!Q!{a!W!{a~P&^O!Q$iO!W#dO~Op$VOa#Xa~OpfOa#[a~Oa$lO~P#kOj!zOQ!zXb!zXf!zXh!zXn!zXp!zX!Q!zX!V!zX!W!zX#Q!zX#R!zX#Z!zX~Op$eO!V$oO!Q!Tq!W!Tq~P#kOa!xap!xa~P#kOj!zOQ!zab!zaf!zah!zan!zap!za!Q!za!V!za!W!za#Q!za#R!za#Z!za~Oo#Zj!Pj~",
  goto: ",O#_PPPPP#`P#h#vP#h$U#hPP$[PPP$b$k$kP$}P$kP$k%e%wPPP&a&g#hP&mP#hP&sP#hP#h#hPPP&y']'iPP#`PP'o'o'y'oP'oP'o'oP#`P#`P#`P'|#`P(P(SPP#`P#`(V(e(s(y)T)Z)e)kPPPPPP)q)yP*e*hP+^+a+j]`Obn!s#d$QiWObfklmn!s!u#V#d$QiQObfklmn!s!u#V#d$QQdRR!ceQrTR!ghQ!gsQ!|!OR#`!hq!QXZz!t!w!z#b#c#i#r$O$V$^$e$f$jp!QXZz!t!w!z#b#c#i#r$O$V$^$e$f$jT#z#[#{q!OXZz!t!w!z#b#c#i#r$O$V$^$e$f$jp!QXZz!t!w!z#b#c#i#r$O$V$^$e$f$jQ![[R#Z!]QtTR!ihQ!gtR#`!iQvUR!jiQxVR!kjQoSQ!fgQ#W!XQ#^!`Q#_!aR$`#zQ!rnQ#g!sQ$P#dR$h$QX!pn!s#d$Qa!WY^_|!S!U#R#SR#P!SR!][R!_]R#]!_QbOU!bb!s$QQ!snR$Q#dQ#i!tU$U#i$^$jQ$^#rR$j$VQ$W#iR$k$WQgSS!eg$YR$Y#kQ$f$OR$n$fQ#e!rS$R#e$TR$T#gQ#T!TR#v#TQ#{#[R$a#{]aObn!s#d$Q[SObn!s#d$QQ!dfQ!lkQ!mlQ!nmQ#k!uR#w#VR#j!tQ|XQ!YZQ!xz[#h!t#i#r$V$^$jQ#m!wQ#o!zQ#}#bQ$O#cS$d$O$fR$m$eR#l!uQ!XYQ!a_R!{|U!TY_|Q!`^Q#Q!SQ#U!UQ#t#RR#u#S",
  nodeNames: "\u26A0 Unit VariableName Comment StyleSheet RuleSet UniversalSelector TagSelector TagName NestingSelector ClassSelector ClassName PseudoClassSelector : :: PseudoClassName PseudoClassName ) ( ArgList ValueName ParenthesizedValue ColorLiteral NumberLiteral StringLiteral BinaryExpression BinOp CallExpression Callee CallLiteral CallTag ParenthesizedContent , PseudoClassName ArgList IdSelector # IdName ] AttributeSelector [ AttributeName MatchOp ChildSelector ChildOp DescendantSelector SiblingSelector SiblingOp } { Block Declaration PropertyName Important ; ImportStatement AtKeyword import KeywordQuery FeatureQuery FeatureName BinaryQuery LogicOp UnaryQuery UnaryQueryOp ParenthesizedQuery SelectorQuery selector MediaStatement media CharsetStatement charset NamespaceStatement namespace NamespaceName KeyframesStatement keyframes KeyframeName KeyframeList from to SupportsStatement supports AtRule",
  maxTerm: 106,
  nodeProps: [
    ["openedBy", 17, "(", 48, "{"],
    ["closedBy", 18, ")", 49, "}"]
  ],
  propSources: [XO],
  skippedNodes: [0, 3],
  repeatNodeCount: 8,
  tokenData: "Ay~R![OX$wX^%]^p$wpq%]qr(crs+}st,otu2Uuv$wvw2rwx2}xy3jyz3uz{3z{|4_|}8U}!O8a!O!P8x!P!Q9Z!Q![;e![!]<Y!]!^<x!^!_$w!_!`=T!`!a=`!a!b$w!b!c>O!c!}$w!}#O?[#O#P$w#P#Q?g#Q#R2U#R#T$w#T#U?r#U#c$w#c#d@q#d#o$w#o#pAQ#p#q2U#q#rA]#r#sAh#s#y$w#y#z%]#z$f$w$f$g%]$g#BY$w#BY#BZ%]#BZ$IS$w$IS$I_%]$I_$I|$w$I|$JO%]$JO$JT$w$JT$JU%]$JU$KV$w$KV$KW%]$KW&FU$w&FU&FV%]&FV~$wW$zQOy%Qz~%QW%VQoWOy%Qz~%Q~%bf#T~OX%QX^&v^p%Qpq&vqy%Qz#y%Q#y#z&v#z$f%Q$f$g&v$g#BY%Q#BY#BZ&v#BZ$IS%Q$IS$I_&v$I_$I|%Q$I|$JO&v$JO$JT%Q$JT$JU&v$JU$KV%Q$KV$KW&v$KW&FU%Q&FU&FV&v&FV~%Q~&}f#T~oWOX%QX^&v^p%Qpq&vqy%Qz#y%Q#y#z&v#z$f%Q$f$g&v$g#BY%Q#BY#BZ&v#BZ$IS%Q$IS$I_&v$I_$I|%Q$I|$JO&v$JO$JT%Q$JT$JU&v$JU$KV%Q$KV$KW&v$KW&FU%Q&FU&FV&v&FV~%Q^(fSOy%Qz#]%Q#]#^(r#^~%Q^(wSoWOy%Qz#a%Q#a#b)T#b~%Q^)YSoWOy%Qz#d%Q#d#e)f#e~%Q^)kSoWOy%Qz#c%Q#c#d)w#d~%Q^)|SoWOy%Qz#f%Q#f#g*Y#g~%Q^*_SoWOy%Qz#h%Q#h#i*k#i~%Q^*pSoWOy%Qz#T%Q#T#U*|#U~%Q^+RSoWOy%Qz#b%Q#b#c+_#c~%Q^+dSoWOy%Qz#h%Q#h#i+p#i~%Q^+wQ!VUoWOy%Qz~%Q~,QUOY+}Zr+}rs,ds#O+}#O#P,i#P~+}~,iOh~~,lPO~+}_,tWtPOy%Qz!Q%Q!Q![-^![!c%Q!c!i-^!i#T%Q#T#Z-^#Z~%Q^-cWoWOy%Qz!Q%Q!Q![-{![!c%Q!c!i-{!i#T%Q#T#Z-{#Z~%Q^.QWoWOy%Qz!Q%Q!Q![.j![!c%Q!c!i.j!i#T%Q#T#Z.j#Z~%Q^.qWfUoWOy%Qz!Q%Q!Q![/Z![!c%Q!c!i/Z!i#T%Q#T#Z/Z#Z~%Q^/bWfUoWOy%Qz!Q%Q!Q![/z![!c%Q!c!i/z!i#T%Q#T#Z/z#Z~%Q^0PWoWOy%Qz!Q%Q!Q![0i![!c%Q!c!i0i!i#T%Q#T#Z0i#Z~%Q^0pWfUoWOy%Qz!Q%Q!Q![1Y![!c%Q!c!i1Y!i#T%Q#T#Z1Y#Z~%Q^1_WoWOy%Qz!Q%Q!Q![1w![!c%Q!c!i1w!i#T%Q#T#Z1w#Z~%Q^2OQfUoWOy%Qz~%QY2XSOy%Qz!_%Q!_!`2e!`~%QY2lQzQoWOy%Qz~%QX2wQXPOy%Qz~%Q~3QUOY2}Zw2}wx,dx#O2}#O#P3d#P~2}~3gPO~2}_3oQbVOy%Qz~%Q~3zOa~_4RSUPjSOy%Qz!_%Q!_!`2e!`~%Q_4fUjS!PPOy%Qz!O%Q!O!P4x!P!Q%Q!Q![7_![~%Q^4}SoWOy%Qz!Q%Q!Q![5Z![~%Q^5bWoW#ZUOy%Qz!Q%Q!Q![5Z![!g%Q!g!h5z!h#X%Q#X#Y5z#Y~%Q^6PWoWOy%Qz{%Q{|6i|}%Q}!O6i!O!Q%Q!Q![6z![~%Q^6nSoWOy%Qz!Q%Q!Q![6z![~%Q^7RSoW#ZUOy%Qz!Q%Q!Q![6z![~%Q^7fYoW#ZUOy%Qz!O%Q!O!P5Z!P!Q%Q!Q![7_![!g%Q!g!h5z!h#X%Q#X#Y5z#Y~%Q_8ZQpVOy%Qz~%Q^8fUjSOy%Qz!O%Q!O!P4x!P!Q%Q!Q![7_![~%Q_8}S#WPOy%Qz!Q%Q!Q![5Z![~%Q~9`RjSOy%Qz{9i{~%Q~9nSoWOy9iyz9zz{:o{~9i~9}ROz9zz{:W{~9z~:ZTOz9zz{:W{!P9z!P!Q:j!Q~9z~:oOR~~:tUoWOy9iyz9zz{:o{!P9i!P!Q;W!Q~9i~;_QoWR~Oy%Qz~%Q^;jY#ZUOy%Qz!O%Q!O!P5Z!P!Q%Q!Q![7_![!g%Q!g!h5z!h#X%Q#X#Y5z#Y~%QX<_S]POy%Qz![%Q![!]<k!]~%QX<rQ^PoWOy%Qz~%Q_<}Q!WVOy%Qz~%QY=YQzQOy%Qz~%QX=eS|POy%Qz!`%Q!`!a=q!a~%QX=xQ|PoWOy%Qz~%QX>RUOy%Qz!c%Q!c!}>e!}#T%Q#T#o>e#o~%QX>lY!YPoWOy%Qz}%Q}!O>e!O!Q%Q!Q![>e![!c%Q!c!}>e!}#T%Q#T#o>e#o~%QX?aQxPOy%Qz~%Q^?lQvUOy%Qz~%QX?uSOy%Qz#b%Q#b#c@R#c~%QX@WSoWOy%Qz#W%Q#W#X@d#X~%QX@kQ!`PoWOy%Qz~%QX@tSOy%Qz#f%Q#f#g@d#g~%QXAVQ!RPOy%Qz~%Q_AbQ!QVOy%Qz~%QZAmS!PPOy%Qz!_%Q!_!`2e!`~%Q",
  tokenizers: [qO, KO, HO, 0, 1, 2, 3],
  topRules: { StyleSheet: [0, 4] },
  specialized: [{ term: 94, get: (i) => GO[i] || -1 }, { term: 56, get: (i) => JO[i] || -1 }, { term: 95, get: (i) => YO[i] || -1 }],
  tokenPrec: 1078
});
let pl = null;
function gl() {
  if (!pl && typeof document == "object" && document.body) {
    let i = [];
    for (let t in document.body.style)
      /[A-Z]|^-|^(item|length)$/.test(t) || i.push(t);
    pl = i.sort().map((t) => ({ type: "property", label: t }));
  }
  return pl || [];
}
const df = /* @__PURE__ */ [
  "active",
  "after",
  "before",
  "checked",
  "default",
  "disabled",
  "empty",
  "enabled",
  "first-child",
  "first-letter",
  "first-line",
  "first-of-type",
  "focus",
  "hover",
  "in-range",
  "indeterminate",
  "invalid",
  "lang",
  "last-child",
  "last-of-type",
  "link",
  "not",
  "nth-child",
  "nth-last-child",
  "nth-last-of-type",
  "nth-of-type",
  "only-of-type",
  "only-child",
  "optional",
  "out-of-range",
  "placeholder",
  "read-only",
  "read-write",
  "required",
  "root",
  "selection",
  "target",
  "valid",
  "visited"
].map((i) => ({ type: "class", label: i })), pf = /* @__PURE__ */ [
  "above",
  "absolute",
  "activeborder",
  "additive",
  "activecaption",
  "after-white-space",
  "ahead",
  "alias",
  "all",
  "all-scroll",
  "alphabetic",
  "alternate",
  "always",
  "antialiased",
  "appworkspace",
  "asterisks",
  "attr",
  "auto",
  "auto-flow",
  "avoid",
  "avoid-column",
  "avoid-page",
  "avoid-region",
  "axis-pan",
  "background",
  "backwards",
  "baseline",
  "below",
  "bidi-override",
  "blink",
  "block",
  "block-axis",
  "bold",
  "bolder",
  "border",
  "border-box",
  "both",
  "bottom",
  "break",
  "break-all",
  "break-word",
  "bullets",
  "button",
  "button-bevel",
  "buttonface",
  "buttonhighlight",
  "buttonshadow",
  "buttontext",
  "calc",
  "capitalize",
  "caps-lock-indicator",
  "caption",
  "captiontext",
  "caret",
  "cell",
  "center",
  "checkbox",
  "circle",
  "cjk-decimal",
  "clear",
  "clip",
  "close-quote",
  "col-resize",
  "collapse",
  "color",
  "color-burn",
  "color-dodge",
  "column",
  "column-reverse",
  "compact",
  "condensed",
  "contain",
  "content",
  "contents",
  "content-box",
  "context-menu",
  "continuous",
  "copy",
  "counter",
  "counters",
  "cover",
  "crop",
  "cross",
  "crosshair",
  "currentcolor",
  "cursive",
  "cyclic",
  "darken",
  "dashed",
  "decimal",
  "decimal-leading-zero",
  "default",
  "default-button",
  "dense",
  "destination-atop",
  "destination-in",
  "destination-out",
  "destination-over",
  "difference",
  "disc",
  "discard",
  "disclosure-closed",
  "disclosure-open",
  "document",
  "dot-dash",
  "dot-dot-dash",
  "dotted",
  "double",
  "down",
  "e-resize",
  "ease",
  "ease-in",
  "ease-in-out",
  "ease-out",
  "element",
  "ellipse",
  "ellipsis",
  "embed",
  "end",
  "ethiopic-abegede-gez",
  "ethiopic-halehame-aa-er",
  "ethiopic-halehame-gez",
  "ew-resize",
  "exclusion",
  "expanded",
  "extends",
  "extra-condensed",
  "extra-expanded",
  "fantasy",
  "fast",
  "fill",
  "fill-box",
  "fixed",
  "flat",
  "flex",
  "flex-end",
  "flex-start",
  "footnotes",
  "forwards",
  "from",
  "geometricPrecision",
  "graytext",
  "grid",
  "groove",
  "hand",
  "hard-light",
  "help",
  "hidden",
  "hide",
  "higher",
  "highlight",
  "highlighttext",
  "horizontal",
  "hsl",
  "hsla",
  "hue",
  "icon",
  "ignore",
  "inactiveborder",
  "inactivecaption",
  "inactivecaptiontext",
  "infinite",
  "infobackground",
  "infotext",
  "inherit",
  "initial",
  "inline",
  "inline-axis",
  "inline-block",
  "inline-flex",
  "inline-grid",
  "inline-table",
  "inset",
  "inside",
  "intrinsic",
  "invert",
  "italic",
  "justify",
  "keep-all",
  "landscape",
  "large",
  "larger",
  "left",
  "level",
  "lighter",
  "lighten",
  "line-through",
  "linear",
  "linear-gradient",
  "lines",
  "list-item",
  "listbox",
  "listitem",
  "local",
  "logical",
  "loud",
  "lower",
  "lower-hexadecimal",
  "lower-latin",
  "lower-norwegian",
  "lowercase",
  "ltr",
  "luminosity",
  "manipulation",
  "match",
  "matrix",
  "matrix3d",
  "medium",
  "menu",
  "menutext",
  "message-box",
  "middle",
  "min-intrinsic",
  "mix",
  "monospace",
  "move",
  "multiple",
  "multiple_mask_images",
  "multiply",
  "n-resize",
  "narrower",
  "ne-resize",
  "nesw-resize",
  "no-close-quote",
  "no-drop",
  "no-open-quote",
  "no-repeat",
  "none",
  "normal",
  "not-allowed",
  "nowrap",
  "ns-resize",
  "numbers",
  "numeric",
  "nw-resize",
  "nwse-resize",
  "oblique",
  "opacity",
  "open-quote",
  "optimizeLegibility",
  "optimizeSpeed",
  "outset",
  "outside",
  "outside-shape",
  "overlay",
  "overline",
  "padding",
  "padding-box",
  "painted",
  "page",
  "paused",
  "perspective",
  "pinch-zoom",
  "plus-darker",
  "plus-lighter",
  "pointer",
  "polygon",
  "portrait",
  "pre",
  "pre-line",
  "pre-wrap",
  "preserve-3d",
  "progress",
  "push-button",
  "radial-gradient",
  "radio",
  "read-only",
  "read-write",
  "read-write-plaintext-only",
  "rectangle",
  "region",
  "relative",
  "repeat",
  "repeating-linear-gradient",
  "repeating-radial-gradient",
  "repeat-x",
  "repeat-y",
  "reset",
  "reverse",
  "rgb",
  "rgba",
  "ridge",
  "right",
  "rotate",
  "rotate3d",
  "rotateX",
  "rotateY",
  "rotateZ",
  "round",
  "row",
  "row-resize",
  "row-reverse",
  "rtl",
  "run-in",
  "running",
  "s-resize",
  "sans-serif",
  "saturation",
  "scale",
  "scale3d",
  "scaleX",
  "scaleY",
  "scaleZ",
  "screen",
  "scroll",
  "scrollbar",
  "scroll-position",
  "se-resize",
  "self-start",
  "self-end",
  "semi-condensed",
  "semi-expanded",
  "separate",
  "serif",
  "show",
  "single",
  "skew",
  "skewX",
  "skewY",
  "skip-white-space",
  "slide",
  "slider-horizontal",
  "slider-vertical",
  "sliderthumb-horizontal",
  "sliderthumb-vertical",
  "slow",
  "small",
  "small-caps",
  "small-caption",
  "smaller",
  "soft-light",
  "solid",
  "source-atop",
  "source-in",
  "source-out",
  "source-over",
  "space",
  "space-around",
  "space-between",
  "space-evenly",
  "spell-out",
  "square",
  "start",
  "static",
  "status-bar",
  "stretch",
  "stroke",
  "stroke-box",
  "sub",
  "subpixel-antialiased",
  "svg_masks",
  "super",
  "sw-resize",
  "symbolic",
  "symbols",
  "system-ui",
  "table",
  "table-caption",
  "table-cell",
  "table-column",
  "table-column-group",
  "table-footer-group",
  "table-header-group",
  "table-row",
  "table-row-group",
  "text",
  "text-bottom",
  "text-top",
  "textarea",
  "textfield",
  "thick",
  "thin",
  "threeddarkshadow",
  "threedface",
  "threedhighlight",
  "threedlightshadow",
  "threedshadow",
  "to",
  "top",
  "transform",
  "translate",
  "translate3d",
  "translateX",
  "translateY",
  "translateZ",
  "transparent",
  "ultra-condensed",
  "ultra-expanded",
  "underline",
  "unidirectional-pan",
  "unset",
  "up",
  "upper-latin",
  "uppercase",
  "url",
  "var",
  "vertical",
  "vertical-text",
  "view-box",
  "visible",
  "visibleFill",
  "visiblePainted",
  "visibleStroke",
  "visual",
  "w-resize",
  "wait",
  "wave",
  "wider",
  "window",
  "windowframe",
  "windowtext",
  "words",
  "wrap",
  "wrap-reverse",
  "x-large",
  "x-small",
  "xor",
  "xx-large",
  "xx-small"
].map((i) => ({ type: "keyword", label: i })).concat(/* @__PURE__ */ [
  "aliceblue",
  "antiquewhite",
  "aqua",
  "aquamarine",
  "azure",
  "beige",
  "bisque",
  "black",
  "blanchedalmond",
  "blue",
  "blueviolet",
  "brown",
  "burlywood",
  "cadetblue",
  "chartreuse",
  "chocolate",
  "coral",
  "cornflowerblue",
  "cornsilk",
  "crimson",
  "cyan",
  "darkblue",
  "darkcyan",
  "darkgoldenrod",
  "darkgray",
  "darkgreen",
  "darkkhaki",
  "darkmagenta",
  "darkolivegreen",
  "darkorange",
  "darkorchid",
  "darkred",
  "darksalmon",
  "darkseagreen",
  "darkslateblue",
  "darkslategray",
  "darkturquoise",
  "darkviolet",
  "deeppink",
  "deepskyblue",
  "dimgray",
  "dodgerblue",
  "firebrick",
  "floralwhite",
  "forestgreen",
  "fuchsia",
  "gainsboro",
  "ghostwhite",
  "gold",
  "goldenrod",
  "gray",
  "grey",
  "green",
  "greenyellow",
  "honeydew",
  "hotpink",
  "indianred",
  "indigo",
  "ivory",
  "khaki",
  "lavender",
  "lavenderblush",
  "lawngreen",
  "lemonchiffon",
  "lightblue",
  "lightcoral",
  "lightcyan",
  "lightgoldenrodyellow",
  "lightgray",
  "lightgreen",
  "lightpink",
  "lightsalmon",
  "lightseagreen",
  "lightskyblue",
  "lightslategray",
  "lightsteelblue",
  "lightyellow",
  "lime",
  "limegreen",
  "linen",
  "magenta",
  "maroon",
  "mediumaquamarine",
  "mediumblue",
  "mediumorchid",
  "mediumpurple",
  "mediumseagreen",
  "mediumslateblue",
  "mediumspringgreen",
  "mediumturquoise",
  "mediumvioletred",
  "midnightblue",
  "mintcream",
  "mistyrose",
  "moccasin",
  "navajowhite",
  "navy",
  "oldlace",
  "olive",
  "olivedrab",
  "orange",
  "orangered",
  "orchid",
  "palegoldenrod",
  "palegreen",
  "paleturquoise",
  "palevioletred",
  "papayawhip",
  "peachpuff",
  "peru",
  "pink",
  "plum",
  "powderblue",
  "purple",
  "rebeccapurple",
  "red",
  "rosybrown",
  "royalblue",
  "saddlebrown",
  "salmon",
  "sandybrown",
  "seagreen",
  "seashell",
  "sienna",
  "silver",
  "skyblue",
  "slateblue",
  "slategray",
  "snow",
  "springgreen",
  "steelblue",
  "tan",
  "teal",
  "thistle",
  "tomato",
  "turquoise",
  "violet",
  "wheat",
  "white",
  "whitesmoke",
  "yellow",
  "yellowgreen"
].map((i) => ({ type: "constant", label: i }))), tS = /* @__PURE__ */ [
  "a",
  "abbr",
  "address",
  "article",
  "aside",
  "b",
  "bdi",
  "bdo",
  "blockquote",
  "body",
  "br",
  "button",
  "canvas",
  "caption",
  "cite",
  "code",
  "col",
  "colgroup",
  "dd",
  "del",
  "details",
  "dfn",
  "dialog",
  "div",
  "dl",
  "dt",
  "em",
  "figcaption",
  "figure",
  "footer",
  "form",
  "header",
  "hgroup",
  "h1",
  "h2",
  "h3",
  "h4",
  "h5",
  "h6",
  "hr",
  "html",
  "i",
  "iframe",
  "img",
  "input",
  "ins",
  "kbd",
  "label",
  "legend",
  "li",
  "main",
  "meter",
  "nav",
  "ol",
  "output",
  "p",
  "pre",
  "ruby",
  "section",
  "select",
  "small",
  "source",
  "span",
  "strong",
  "sub",
  "summary",
  "sup",
  "table",
  "tbody",
  "td",
  "template",
  "textarea",
  "tfoot",
  "th",
  "thead",
  "tr",
  "u",
  "ul"
].map((i) => ({ type: "type", label: i })), li = /^[\w-]*/, eS = (i) => {
  let { state: t, pos: e } = i, s = Ft(t).resolveInner(e, -1);
  if (s.name == "PropertyName")
    return { from: s.from, options: gl(), validFor: li };
  if (s.name == "ValueName")
    return { from: s.from, options: pf, validFor: li };
  if (s.name == "PseudoClassName")
    return { from: s.from, options: df, validFor: li };
  if (s.name == "TagName") {
    for (let { parent: o } = s; o; o = o.parent)
      if (o.name == "Block")
        return { from: s.from, options: gl(), validFor: li };
    return { from: s.from, options: tS, validFor: li };
  }
  if (!i.explicit)
    return null;
  let n = s.resolve(e), r = n.childBefore(e);
  return r && r.name == ":" && n.name == "PseudoClassSelector" ? { from: e, options: df, validFor: li } : r && r.name == ":" && n.name == "Declaration" || n.name == "ArgList" ? { from: e, options: pf, validFor: li } : n.name == "Block" ? { from: e, options: gl(), validFor: li } : null;
}, gf = /* @__PURE__ */ $r.define({
  parser: /* @__PURE__ */ ZO.configure({
    props: [
      /* @__PURE__ */ yg.add({
        Declaration: /* @__PURE__ */ bv()
      }),
      /* @__PURE__ */ vg.add({
        Block: xv
      })
    ]
  }),
  languageData: {
    commentTokens: { block: { open: "/*", close: "*/" } },
    indentOnInput: /^\s*\}$/,
    wordChars: "-"
  }
});
function iS() {
  return new hv(gf, gf.data.of({ autocomplete: eS }));
}
const sS = "#e5c07b", mf = "#e06c75", nS = "#56b6c2", rS = "#ffffff", vr = "#abb2bf", Qa = "#7d8799", oS = "#61afef", lS = "#98c379", _f = "#d19a66", aS = "#c678dd", hS = "#21252b", ml = "#2c313a", bf = "#282c34", _l = "#353a42", uS = "#3E4451", yf = "#528bff", cS = /* @__PURE__ */ H.theme({
  "&": {
    color: vr,
    backgroundColor: bf
  },
  ".cm-content": {
    caretColor: yf
  },
  ".cm-cursor, .cm-dropCursor": { borderLeftColor: yf },
  "&.cm-focused .cm-selectionBackground, .cm-selectionBackground, .cm-content ::selection": { backgroundColor: uS },
  ".cm-panels": { backgroundColor: hS, color: vr },
  ".cm-panels.cm-panels-top": { borderBottom: "2px solid black" },
  ".cm-panels.cm-panels-bottom": { borderTop: "2px solid black" },
  ".cm-searchMatch": {
    backgroundColor: "#72a1ff59",
    outline: "1px solid #457dff"
  },
  ".cm-searchMatch.cm-searchMatch-selected": {
    backgroundColor: "#6199ff2f"
  },
  ".cm-activeLine": { backgroundColor: ml },
  ".cm-selectionMatch": { backgroundColor: "#aafe661a" },
  "&.cm-focused .cm-matchingBracket, &.cm-focused .cm-nonmatchingBracket": {
    backgroundColor: "#bad0f847",
    outline: "1px solid #515a6b"
  },
  ".cm-gutters": {
    backgroundColor: bf,
    color: Qa,
    border: "none"
  },
  ".cm-activeLineGutter": {
    backgroundColor: ml
  },
  ".cm-foldPlaceholder": {
    backgroundColor: "transparent",
    border: "none",
    color: "#ddd"
  },
  ".cm-tooltip": {
    border: "none",
    backgroundColor: _l
  },
  ".cm-tooltip .cm-tooltip-arrow:before": {
    borderTopColor: "transparent",
    borderBottomColor: "transparent"
  },
  ".cm-tooltip .cm-tooltip-arrow:after": {
    borderTopColor: _l,
    borderBottomColor: _l
  },
  ".cm-tooltip-autocomplete": {
    "& > ul > li[aria-selected]": {
      backgroundColor: ml,
      color: vr
    }
  }
}, { dark: !0 }), fS = /* @__PURE__ */ Tn.define([
  {
    tag: O.keyword,
    color: aS
  },
  {
    tag: [O.name, O.deleted, O.character, O.propertyName, O.macroName],
    color: mf
  },
  {
    tag: [/* @__PURE__ */ O.function(O.variableName), O.labelName],
    color: oS
  },
  {
    tag: [O.color, /* @__PURE__ */ O.constant(O.name), /* @__PURE__ */ O.standard(O.name)],
    color: _f
  },
  {
    tag: [/* @__PURE__ */ O.definition(O.name), O.separator],
    color: vr
  },
  {
    tag: [O.typeName, O.className, O.number, O.changed, O.annotation, O.modifier, O.self, O.namespace],
    color: sS
  },
  {
    tag: [O.operator, O.operatorKeyword, O.url, O.escape, O.regexp, O.link, /* @__PURE__ */ O.special(O.string)],
    color: nS
  },
  {
    tag: [O.meta, O.comment],
    color: Qa
  },
  {
    tag: O.strong,
    fontWeight: "bold"
  },
  {
    tag: O.emphasis,
    fontStyle: "italic"
  },
  {
    tag: O.strikethrough,
    textDecoration: "line-through"
  },
  {
    tag: O.link,
    color: Qa,
    textDecoration: "underline"
  },
  {
    tag: O.heading,
    fontWeight: "bold",
    color: mf
  },
  {
    tag: [O.atom, O.bool, /* @__PURE__ */ O.special(O.variableName)],
    color: _f
  },
  {
    tag: [O.processingInstruction, O.string, O.inserted],
    color: lS
  },
  {
    tag: O.invalid,
    color: rS
  }
]), dS = [cS, /* @__PURE__ */ Pg(fS)];
var zm = { exports: {} }, Qs = {}, tr = { exports: {} }, bl = {}, yl = {}, wf;
function Nh() {
  if (wf)
    return yl;
  wf = 1;
  function i(s) {
    this.__parent = s, this.__character_count = 0, this.__indent_count = -1, this.__alignment_count = 0, this.__wrap_point_index = 0, this.__wrap_point_character_count = 0, this.__wrap_point_indent_count = -1, this.__wrap_point_alignment_count = 0, this.__items = [];
  }
  i.prototype.clone_empty = function() {
    var s = new i(this.__parent);
    return s.set_indent(this.__indent_count, this.__alignment_count), s;
  }, i.prototype.item = function(s) {
    return s < 0 ? this.__items[this.__items.length + s] : this.__items[s];
  }, i.prototype.has_match = function(s) {
    for (var n = this.__items.length - 1; n >= 0; n--)
      if (this.__items[n].match(s))
        return !0;
    return !1;
  }, i.prototype.set_indent = function(s, n) {
    this.is_empty() && (this.__indent_count = s || 0, this.__alignment_count = n || 0, this.__character_count = this.__parent.get_indent_size(this.__indent_count, this.__alignment_count));
  }, i.prototype._set_wrap_point = function() {
    this.__parent.wrap_line_length && (this.__wrap_point_index = this.__items.length, this.__wrap_point_character_count = this.__character_count, this.__wrap_point_indent_count = this.__parent.next_line.__indent_count, this.__wrap_point_alignment_count = this.__parent.next_line.__alignment_count);
  }, i.prototype._should_wrap = function() {
    return this.__wrap_point_index && this.__character_count > this.__parent.wrap_line_length && this.__wrap_point_character_count > this.__parent.next_line.__character_count;
  }, i.prototype._allow_wrap = function() {
    if (this._should_wrap()) {
      this.__parent.add_new_line();
      var s = this.__parent.current_line;
      return s.set_indent(this.__wrap_point_indent_count, this.__wrap_point_alignment_count), s.__items = this.__items.slice(this.__wrap_point_index), this.__items = this.__items.slice(0, this.__wrap_point_index), s.__character_count += this.__character_count - this.__wrap_point_character_count, this.__character_count = this.__wrap_point_character_count, s.__items[0] === " " && (s.__items.splice(0, 1), s.__character_count -= 1), !0;
    }
    return !1;
  }, i.prototype.is_empty = function() {
    return this.__items.length === 0;
  }, i.prototype.last = function() {
    return this.is_empty() ? null : this.__items[this.__items.length - 1];
  }, i.prototype.push = function(s) {
    this.__items.push(s);
    var n = s.lastIndexOf(`
`);
    n !== -1 ? this.__character_count = s.length - n : this.__character_count += s.length;
  }, i.prototype.pop = function() {
    var s = null;
    return this.is_empty() || (s = this.__items.pop(), this.__character_count -= s.length), s;
  }, i.prototype._remove_indent = function() {
    this.__indent_count > 0 && (this.__indent_count -= 1, this.__character_count -= this.__parent.indent_size);
  }, i.prototype._remove_wrap_indent = function() {
    this.__wrap_point_indent_count > 0 && (this.__wrap_point_indent_count -= 1);
  }, i.prototype.trim = function() {
    for (; this.last() === " "; )
      this.__items.pop(), this.__character_count -= 1;
  }, i.prototype.toString = function() {
    var s = "";
    return this.is_empty() ? this.__parent.indent_empty_lines && (s = this.__parent.get_indent_string(this.__indent_count)) : (s = this.__parent.get_indent_string(this.__indent_count, this.__alignment_count), s += this.__items.join("")), s;
  };
  function t(s, n) {
    this.__cache = [""], this.__indent_size = s.indent_size, this.__indent_string = s.indent_char, s.indent_with_tabs || (this.__indent_string = new Array(s.indent_size + 1).join(s.indent_char)), n = n || "", s.indent_level > 0 && (n = new Array(s.indent_level + 1).join(this.__indent_string)), this.__base_string = n, this.__base_string_length = n.length;
  }
  t.prototype.get_indent_size = function(s, n) {
    var r = this.__base_string_length;
    return n = n || 0, s < 0 && (r = 0), r += s * this.__indent_size, r += n, r;
  }, t.prototype.get_indent_string = function(s, n) {
    var r = this.__base_string;
    return n = n || 0, s < 0 && (s = 0, r = ""), n += s * this.__indent_size, this.__ensure_cache(n), r += this.__cache[n], r;
  }, t.prototype.__ensure_cache = function(s) {
    for (; s >= this.__cache.length; )
      this.__add_column();
  }, t.prototype.__add_column = function() {
    var s = this.__cache.length, n = 0, r = "";
    this.__indent_size && s >= this.__indent_size && (n = Math.floor(s / this.__indent_size), s -= n * this.__indent_size, r = new Array(n + 1).join(this.__indent_string)), s && (r += new Array(s + 1).join(" ")), this.__cache.push(r);
  };
  function e(s, n) {
    this.__indent_cache = new t(s, n), this.raw = !1, this._end_with_newline = s.end_with_newline, this.indent_size = s.indent_size, this.wrap_line_length = s.wrap_line_length, this.indent_empty_lines = s.indent_empty_lines, this.__lines = [], this.previous_line = null, this.current_line = null, this.next_line = new i(this), this.space_before_token = !1, this.non_breaking_space = !1, this.previous_token_wrapped = !1, this.__add_outputline();
  }
  return e.prototype.__add_outputline = function() {
    this.previous_line = this.current_line, this.current_line = this.next_line.clone_empty(), this.__lines.push(this.current_line);
  }, e.prototype.get_line_number = function() {
    return this.__lines.length;
  }, e.prototype.get_indent_string = function(s, n) {
    return this.__indent_cache.get_indent_string(s, n);
  }, e.prototype.get_indent_size = function(s, n) {
    return this.__indent_cache.get_indent_size(s, n);
  }, e.prototype.is_empty = function() {
    return !this.previous_line && this.current_line.is_empty();
  }, e.prototype.add_new_line = function(s) {
    return this.is_empty() || !s && this.just_added_newline() ? !1 : (this.raw || this.__add_outputline(), !0);
  }, e.prototype.get_code = function(s) {
    this.trim(!0);
    var n = this.current_line.pop();
    n && (n[n.length - 1] === `
` && (n = n.replace(/\n+$/g, "")), this.current_line.push(n)), this._end_with_newline && this.__add_outputline();
    var r = this.__lines.join(`
`);
    return s !== `
` && (r = r.replace(/[\n]/g, s)), r;
  }, e.prototype.set_wrap_point = function() {
    this.current_line._set_wrap_point();
  }, e.prototype.set_indent = function(s, n) {
    return s = s || 0, n = n || 0, this.next_line.set_indent(s, n), this.__lines.length > 1 ? (this.current_line.set_indent(s, n), !0) : (this.current_line.set_indent(), !1);
  }, e.prototype.add_raw_token = function(s) {
    for (var n = 0; n < s.newlines; n++)
      this.__add_outputline();
    this.current_line.set_indent(-1), this.current_line.push(s.whitespace_before), this.current_line.push(s.text), this.space_before_token = !1, this.non_breaking_space = !1, this.previous_token_wrapped = !1;
  }, e.prototype.add_token = function(s) {
    this.__add_space_before_token(), this.current_line.push(s), this.space_before_token = !1, this.non_breaking_space = !1, this.previous_token_wrapped = this.current_line._allow_wrap();
  }, e.prototype.__add_space_before_token = function() {
    this.space_before_token && !this.just_added_newline() && (this.non_breaking_space || this.set_wrap_point(), this.current_line.push(" "));
  }, e.prototype.remove_indent = function(s) {
    for (var n = this.__lines.length; s < n; )
      this.__lines[s]._remove_indent(), s++;
    this.current_line._remove_wrap_indent();
  }, e.prototype.trim = function(s) {
    for (s = s === void 0 ? !1 : s, this.current_line.trim(); s && this.__lines.length > 1 && this.current_line.is_empty(); )
      this.__lines.pop(), this.current_line = this.__lines[this.__lines.length - 1], this.current_line.trim();
    this.previous_line = this.__lines.length > 1 ? this.__lines[this.__lines.length - 2] : null;
  }, e.prototype.just_added_newline = function() {
    return this.current_line.is_empty();
  }, e.prototype.just_added_blankline = function() {
    return this.is_empty() || this.current_line.is_empty() && this.previous_line.is_empty();
  }, e.prototype.ensure_empty_line_above = function(s, n) {
    for (var r = this.__lines.length - 2; r >= 0; ) {
      var o = this.__lines[r];
      if (o.is_empty())
        break;
      if (o.item(0).indexOf(s) !== 0 && o.item(-1) !== n) {
        this.__lines.splice(r + 1, 0, new i(this)), this.previous_line = this.__lines[this.__lines.length - 2];
        break;
      }
      r--;
    }
  }, yl.Output = e, yl;
}
var wl = {}, vf;
function Wm() {
  if (vf)
    return wl;
  vf = 1;
  function i(t, e, s, n) {
    this.type = t, this.text = e, this.comments_before = null, this.newlines = s || 0, this.whitespace_before = n || "", this.parent = null, this.next = null, this.previous = null, this.opened = null, this.closed = null, this.directives = null;
  }
  return wl.Token = i, wl;
}
var vl = {}, xf;
function $m() {
  return xf || (xf = 1, function(i) {
    var t = "\\x23\\x24\\x40\\x41-\\x5a\\x5f\\x61-\\x7a", e = "\\x24\\x30-\\x39\\x41-\\x5a\\x5f\\x61-\\x7a", s = "\\xaa\\xb5\\xba\\xc0-\\xd6\\xd8-\\xf6\\xf8-\\u02c1\\u02c6-\\u02d1\\u02e0-\\u02e4\\u02ec\\u02ee\\u0370-\\u0374\\u0376\\u0377\\u037a-\\u037d\\u0386\\u0388-\\u038a\\u038c\\u038e-\\u03a1\\u03a3-\\u03f5\\u03f7-\\u0481\\u048a-\\u0527\\u0531-\\u0556\\u0559\\u0561-\\u0587\\u05d0-\\u05ea\\u05f0-\\u05f2\\u0620-\\u064a\\u066e\\u066f\\u0671-\\u06d3\\u06d5\\u06e5\\u06e6\\u06ee\\u06ef\\u06fa-\\u06fc\\u06ff\\u0710\\u0712-\\u072f\\u074d-\\u07a5\\u07b1\\u07ca-\\u07ea\\u07f4\\u07f5\\u07fa\\u0800-\\u0815\\u081a\\u0824\\u0828\\u0840-\\u0858\\u08a0\\u08a2-\\u08ac\\u0904-\\u0939\\u093d\\u0950\\u0958-\\u0961\\u0971-\\u0977\\u0979-\\u097f\\u0985-\\u098c\\u098f\\u0990\\u0993-\\u09a8\\u09aa-\\u09b0\\u09b2\\u09b6-\\u09b9\\u09bd\\u09ce\\u09dc\\u09dd\\u09df-\\u09e1\\u09f0\\u09f1\\u0a05-\\u0a0a\\u0a0f\\u0a10\\u0a13-\\u0a28\\u0a2a-\\u0a30\\u0a32\\u0a33\\u0a35\\u0a36\\u0a38\\u0a39\\u0a59-\\u0a5c\\u0a5e\\u0a72-\\u0a74\\u0a85-\\u0a8d\\u0a8f-\\u0a91\\u0a93-\\u0aa8\\u0aaa-\\u0ab0\\u0ab2\\u0ab3\\u0ab5-\\u0ab9\\u0abd\\u0ad0\\u0ae0\\u0ae1\\u0b05-\\u0b0c\\u0b0f\\u0b10\\u0b13-\\u0b28\\u0b2a-\\u0b30\\u0b32\\u0b33\\u0b35-\\u0b39\\u0b3d\\u0b5c\\u0b5d\\u0b5f-\\u0b61\\u0b71\\u0b83\\u0b85-\\u0b8a\\u0b8e-\\u0b90\\u0b92-\\u0b95\\u0b99\\u0b9a\\u0b9c\\u0b9e\\u0b9f\\u0ba3\\u0ba4\\u0ba8-\\u0baa\\u0bae-\\u0bb9\\u0bd0\\u0c05-\\u0c0c\\u0c0e-\\u0c10\\u0c12-\\u0c28\\u0c2a-\\u0c33\\u0c35-\\u0c39\\u0c3d\\u0c58\\u0c59\\u0c60\\u0c61\\u0c85-\\u0c8c\\u0c8e-\\u0c90\\u0c92-\\u0ca8\\u0caa-\\u0cb3\\u0cb5-\\u0cb9\\u0cbd\\u0cde\\u0ce0\\u0ce1\\u0cf1\\u0cf2\\u0d05-\\u0d0c\\u0d0e-\\u0d10\\u0d12-\\u0d3a\\u0d3d\\u0d4e\\u0d60\\u0d61\\u0d7a-\\u0d7f\\u0d85-\\u0d96\\u0d9a-\\u0db1\\u0db3-\\u0dbb\\u0dbd\\u0dc0-\\u0dc6\\u0e01-\\u0e30\\u0e32\\u0e33\\u0e40-\\u0e46\\u0e81\\u0e82\\u0e84\\u0e87\\u0e88\\u0e8a\\u0e8d\\u0e94-\\u0e97\\u0e99-\\u0e9f\\u0ea1-\\u0ea3\\u0ea5\\u0ea7\\u0eaa\\u0eab\\u0ead-\\u0eb0\\u0eb2\\u0eb3\\u0ebd\\u0ec0-\\u0ec4\\u0ec6\\u0edc-\\u0edf\\u0f00\\u0f40-\\u0f47\\u0f49-\\u0f6c\\u0f88-\\u0f8c\\u1000-\\u102a\\u103f\\u1050-\\u1055\\u105a-\\u105d\\u1061\\u1065\\u1066\\u106e-\\u1070\\u1075-\\u1081\\u108e\\u10a0-\\u10c5\\u10c7\\u10cd\\u10d0-\\u10fa\\u10fc-\\u1248\\u124a-\\u124d\\u1250-\\u1256\\u1258\\u125a-\\u125d\\u1260-\\u1288\\u128a-\\u128d\\u1290-\\u12b0\\u12b2-\\u12b5\\u12b8-\\u12be\\u12c0\\u12c2-\\u12c5\\u12c8-\\u12d6\\u12d8-\\u1310\\u1312-\\u1315\\u1318-\\u135a\\u1380-\\u138f\\u13a0-\\u13f4\\u1401-\\u166c\\u166f-\\u167f\\u1681-\\u169a\\u16a0-\\u16ea\\u16ee-\\u16f0\\u1700-\\u170c\\u170e-\\u1711\\u1720-\\u1731\\u1740-\\u1751\\u1760-\\u176c\\u176e-\\u1770\\u1780-\\u17b3\\u17d7\\u17dc\\u1820-\\u1877\\u1880-\\u18a8\\u18aa\\u18b0-\\u18f5\\u1900-\\u191c\\u1950-\\u196d\\u1970-\\u1974\\u1980-\\u19ab\\u19c1-\\u19c7\\u1a00-\\u1a16\\u1a20-\\u1a54\\u1aa7\\u1b05-\\u1b33\\u1b45-\\u1b4b\\u1b83-\\u1ba0\\u1bae\\u1baf\\u1bba-\\u1be5\\u1c00-\\u1c23\\u1c4d-\\u1c4f\\u1c5a-\\u1c7d\\u1ce9-\\u1cec\\u1cee-\\u1cf1\\u1cf5\\u1cf6\\u1d00-\\u1dbf\\u1e00-\\u1f15\\u1f18-\\u1f1d\\u1f20-\\u1f45\\u1f48-\\u1f4d\\u1f50-\\u1f57\\u1f59\\u1f5b\\u1f5d\\u1f5f-\\u1f7d\\u1f80-\\u1fb4\\u1fb6-\\u1fbc\\u1fbe\\u1fc2-\\u1fc4\\u1fc6-\\u1fcc\\u1fd0-\\u1fd3\\u1fd6-\\u1fdb\\u1fe0-\\u1fec\\u1ff2-\\u1ff4\\u1ff6-\\u1ffc\\u2071\\u207f\\u2090-\\u209c\\u2102\\u2107\\u210a-\\u2113\\u2115\\u2119-\\u211d\\u2124\\u2126\\u2128\\u212a-\\u212d\\u212f-\\u2139\\u213c-\\u213f\\u2145-\\u2149\\u214e\\u2160-\\u2188\\u2c00-\\u2c2e\\u2c30-\\u2c5e\\u2c60-\\u2ce4\\u2ceb-\\u2cee\\u2cf2\\u2cf3\\u2d00-\\u2d25\\u2d27\\u2d2d\\u2d30-\\u2d67\\u2d6f\\u2d80-\\u2d96\\u2da0-\\u2da6\\u2da8-\\u2dae\\u2db0-\\u2db6\\u2db8-\\u2dbe\\u2dc0-\\u2dc6\\u2dc8-\\u2dce\\u2dd0-\\u2dd6\\u2dd8-\\u2dde\\u2e2f\\u3005-\\u3007\\u3021-\\u3029\\u3031-\\u3035\\u3038-\\u303c\\u3041-\\u3096\\u309d-\\u309f\\u30a1-\\u30fa\\u30fc-\\u30ff\\u3105-\\u312d\\u3131-\\u318e\\u31a0-\\u31ba\\u31f0-\\u31ff\\u3400-\\u4db5\\u4e00-\\u9fcc\\ua000-\\ua48c\\ua4d0-\\ua4fd\\ua500-\\ua60c\\ua610-\\ua61f\\ua62a\\ua62b\\ua640-\\ua66e\\ua67f-\\ua697\\ua6a0-\\ua6ef\\ua717-\\ua71f\\ua722-\\ua788\\ua78b-\\ua78e\\ua790-\\ua793\\ua7a0-\\ua7aa\\ua7f8-\\ua801\\ua803-\\ua805\\ua807-\\ua80a\\ua80c-\\ua822\\ua840-\\ua873\\ua882-\\ua8b3\\ua8f2-\\ua8f7\\ua8fb\\ua90a-\\ua925\\ua930-\\ua946\\ua960-\\ua97c\\ua984-\\ua9b2\\ua9cf\\uaa00-\\uaa28\\uaa40-\\uaa42\\uaa44-\\uaa4b\\uaa60-\\uaa76\\uaa7a\\uaa80-\\uaaaf\\uaab1\\uaab5\\uaab6\\uaab9-\\uaabd\\uaac0\\uaac2\\uaadb-\\uaadd\\uaae0-\\uaaea\\uaaf2-\\uaaf4\\uab01-\\uab06\\uab09-\\uab0e\\uab11-\\uab16\\uab20-\\uab26\\uab28-\\uab2e\\uabc0-\\uabe2\\uac00-\\ud7a3\\ud7b0-\\ud7c6\\ud7cb-\\ud7fb\\uf900-\\ufa6d\\ufa70-\\ufad9\\ufb00-\\ufb06\\ufb13-\\ufb17\\ufb1d\\ufb1f-\\ufb28\\ufb2a-\\ufb36\\ufb38-\\ufb3c\\ufb3e\\ufb40\\ufb41\\ufb43\\ufb44\\ufb46-\\ufbb1\\ufbd3-\\ufd3d\\ufd50-\\ufd8f\\ufd92-\\ufdc7\\ufdf0-\\ufdfb\\ufe70-\\ufe74\\ufe76-\\ufefc\\uff21-\\uff3a\\uff41-\\uff5a\\uff66-\\uffbe\\uffc2-\\uffc7\\uffca-\\uffcf\\uffd2-\\uffd7\\uffda-\\uffdc", n = "\\u0300-\\u036f\\u0483-\\u0487\\u0591-\\u05bd\\u05bf\\u05c1\\u05c2\\u05c4\\u05c5\\u05c7\\u0610-\\u061a\\u0620-\\u0649\\u0672-\\u06d3\\u06e7-\\u06e8\\u06fb-\\u06fc\\u0730-\\u074a\\u0800-\\u0814\\u081b-\\u0823\\u0825-\\u0827\\u0829-\\u082d\\u0840-\\u0857\\u08e4-\\u08fe\\u0900-\\u0903\\u093a-\\u093c\\u093e-\\u094f\\u0951-\\u0957\\u0962-\\u0963\\u0966-\\u096f\\u0981-\\u0983\\u09bc\\u09be-\\u09c4\\u09c7\\u09c8\\u09d7\\u09df-\\u09e0\\u0a01-\\u0a03\\u0a3c\\u0a3e-\\u0a42\\u0a47\\u0a48\\u0a4b-\\u0a4d\\u0a51\\u0a66-\\u0a71\\u0a75\\u0a81-\\u0a83\\u0abc\\u0abe-\\u0ac5\\u0ac7-\\u0ac9\\u0acb-\\u0acd\\u0ae2-\\u0ae3\\u0ae6-\\u0aef\\u0b01-\\u0b03\\u0b3c\\u0b3e-\\u0b44\\u0b47\\u0b48\\u0b4b-\\u0b4d\\u0b56\\u0b57\\u0b5f-\\u0b60\\u0b66-\\u0b6f\\u0b82\\u0bbe-\\u0bc2\\u0bc6-\\u0bc8\\u0bca-\\u0bcd\\u0bd7\\u0be6-\\u0bef\\u0c01-\\u0c03\\u0c46-\\u0c48\\u0c4a-\\u0c4d\\u0c55\\u0c56\\u0c62-\\u0c63\\u0c66-\\u0c6f\\u0c82\\u0c83\\u0cbc\\u0cbe-\\u0cc4\\u0cc6-\\u0cc8\\u0cca-\\u0ccd\\u0cd5\\u0cd6\\u0ce2-\\u0ce3\\u0ce6-\\u0cef\\u0d02\\u0d03\\u0d46-\\u0d48\\u0d57\\u0d62-\\u0d63\\u0d66-\\u0d6f\\u0d82\\u0d83\\u0dca\\u0dcf-\\u0dd4\\u0dd6\\u0dd8-\\u0ddf\\u0df2\\u0df3\\u0e34-\\u0e3a\\u0e40-\\u0e45\\u0e50-\\u0e59\\u0eb4-\\u0eb9\\u0ec8-\\u0ecd\\u0ed0-\\u0ed9\\u0f18\\u0f19\\u0f20-\\u0f29\\u0f35\\u0f37\\u0f39\\u0f41-\\u0f47\\u0f71-\\u0f84\\u0f86-\\u0f87\\u0f8d-\\u0f97\\u0f99-\\u0fbc\\u0fc6\\u1000-\\u1029\\u1040-\\u1049\\u1067-\\u106d\\u1071-\\u1074\\u1082-\\u108d\\u108f-\\u109d\\u135d-\\u135f\\u170e-\\u1710\\u1720-\\u1730\\u1740-\\u1750\\u1772\\u1773\\u1780-\\u17b2\\u17dd\\u17e0-\\u17e9\\u180b-\\u180d\\u1810-\\u1819\\u1920-\\u192b\\u1930-\\u193b\\u1951-\\u196d\\u19b0-\\u19c0\\u19c8-\\u19c9\\u19d0-\\u19d9\\u1a00-\\u1a15\\u1a20-\\u1a53\\u1a60-\\u1a7c\\u1a7f-\\u1a89\\u1a90-\\u1a99\\u1b46-\\u1b4b\\u1b50-\\u1b59\\u1b6b-\\u1b73\\u1bb0-\\u1bb9\\u1be6-\\u1bf3\\u1c00-\\u1c22\\u1c40-\\u1c49\\u1c5b-\\u1c7d\\u1cd0-\\u1cd2\\u1d00-\\u1dbe\\u1e01-\\u1f15\\u200c\\u200d\\u203f\\u2040\\u2054\\u20d0-\\u20dc\\u20e1\\u20e5-\\u20f0\\u2d81-\\u2d96\\u2de0-\\u2dff\\u3021-\\u3028\\u3099\\u309a\\ua640-\\ua66d\\ua674-\\ua67d\\ua69f\\ua6f0-\\ua6f1\\ua7f8-\\ua800\\ua806\\ua80b\\ua823-\\ua827\\ua880-\\ua881\\ua8b4-\\ua8c4\\ua8d0-\\ua8d9\\ua8f3-\\ua8f7\\ua900-\\ua909\\ua926-\\ua92d\\ua930-\\ua945\\ua980-\\ua983\\ua9b3-\\ua9c0\\uaa00-\\uaa27\\uaa40-\\uaa41\\uaa4c-\\uaa4d\\uaa50-\\uaa59\\uaa7b\\uaae0-\\uaae9\\uaaf2-\\uaaf3\\uabc0-\\uabe1\\uabec\\uabed\\uabf0-\\uabf9\\ufb20-\\ufb28\\ufe00-\\ufe0f\\ufe20-\\ufe26\\ufe33\\ufe34\\ufe4d-\\ufe4f\\uff10-\\uff19\\uff3f", r = "(?:\\\\u[0-9a-fA-F]{4}|[" + t + s + "])", o = "(?:\\\\u[0-9a-fA-F]{4}|[" + e + s + n + "])*";
    i.identifier = new RegExp(r + o, "g"), i.identifierStart = new RegExp(r), i.identifierMatch = new RegExp("(?:\\\\u[0-9a-fA-F]{4}|[" + e + s + n + "])+"), i.newline = /[\n\r\u2028\u2029]/, i.lineBreak = new RegExp(`\r
|` + i.newline.source), i.allLineBreaks = new RegExp(i.lineBreak.source, "g");
  }(vl)), vl;
}
var xl = {}, zs = {}, kf;
function Lh() {
  if (kf)
    return zs;
  kf = 1;
  function i(s, n) {
    this.raw_options = t(s, n), this.disabled = this._get_boolean("disabled"), this.eol = this._get_characters("eol", "auto"), this.end_with_newline = this._get_boolean("end_with_newline"), this.indent_size = this._get_number("indent_size", 4), this.indent_char = this._get_characters("indent_char", " "), this.indent_level = this._get_number("indent_level"), this.preserve_newlines = this._get_boolean("preserve_newlines", !0), this.max_preserve_newlines = this._get_number("max_preserve_newlines", 32786), this.preserve_newlines || (this.max_preserve_newlines = 0), this.indent_with_tabs = this._get_boolean("indent_with_tabs", this.indent_char === "	"), this.indent_with_tabs && (this.indent_char = "	", this.indent_size === 1 && (this.indent_size = 4)), this.wrap_line_length = this._get_number("wrap_line_length", this._get_number("max_char")), this.indent_empty_lines = this._get_boolean("indent_empty_lines"), this.templating = this._get_selection_list("templating", ["auto", "none", "django", "erb", "handlebars", "php", "smarty"], ["auto"]);
  }
  i.prototype._get_array = function(s, n) {
    var r = this.raw_options[s], o = n || [];
    return typeof r == "object" ? r !== null && typeof r.concat == "function" && (o = r.concat()) : typeof r == "string" && (o = r.split(/[^a-zA-Z0-9_\/\-]+/)), o;
  }, i.prototype._get_boolean = function(s, n) {
    var r = this.raw_options[s], o = r === void 0 ? !!n : !!r;
    return o;
  }, i.prototype._get_characters = function(s, n) {
    var r = this.raw_options[s], o = n || "";
    return typeof r == "string" && (o = r.replace(/\\r/, "\r").replace(/\\n/, `
`).replace(/\\t/, "	")), o;
  }, i.prototype._get_number = function(s, n) {
    var r = this.raw_options[s];
    n = parseInt(n, 10), isNaN(n) && (n = 0);
    var o = parseInt(r, 10);
    return isNaN(o) && (o = n), o;
  }, i.prototype._get_selection = function(s, n, r) {
    var o = this._get_selection_list(s, n, r);
    if (o.length !== 1)
      throw new Error(
        "Invalid Option Value: The option '" + s + `' can only be one of the following values:
` + n + `
You passed in: '` + this.raw_options[s] + "'"
      );
    return o[0];
  }, i.prototype._get_selection_list = function(s, n, r) {
    if (!n || n.length === 0)
      throw new Error("Selection list cannot be empty.");
    if (r = r || [n[0]], !this._is_valid_selection(r, n))
      throw new Error("Invalid Default Value!");
    var o = this._get_array(s, r);
    if (!this._is_valid_selection(o, n))
      throw new Error(
        "Invalid Option Value: The option '" + s + `' can contain only the following values:
` + n + `
You passed in: '` + this.raw_options[s] + "'"
      );
    return o;
  }, i.prototype._is_valid_selection = function(s, n) {
    return s.length && n.length && !s.some(function(r) {
      return n.indexOf(r) === -1;
    });
  };
  function t(s, n) {
    var r = {};
    s = e(s);
    var o;
    for (o in s)
      o !== n && (r[o] = s[o]);
    if (n && s[n])
      for (o in s[n])
        r[o] = s[n][o];
    return r;
  }
  function e(s) {
    var n = {}, r;
    for (r in s) {
      var o = r.replace(/-/g, "_");
      n[o] = s[r];
    }
    return n;
  }
  return zs.Options = i, zs.normalizeOpts = e, zs.mergeOpts = t, zs;
}
var Of;
function Fm() {
  if (Of)
    return xl;
  Of = 1;
  var i = Lh().Options, t = ["before-newline", "after-newline", "preserve-newline"];
  function e(s) {
    i.call(this, s, "js");
    var n = this.raw_options.brace_style || null;
    n === "expand-strict" ? this.raw_options.brace_style = "expand" : n === "collapse-preserve-inline" ? this.raw_options.brace_style = "collapse,preserve-inline" : this.raw_options.braces_on_own_line !== void 0 && (this.raw_options.brace_style = this.raw_options.braces_on_own_line ? "expand" : "collapse");
    var r = this._get_selection_list("brace_style", ["collapse", "expand", "end-expand", "none", "preserve-inline"]);
    this.brace_preserve_inline = !1, this.brace_style = "collapse";
    for (var o = 0; o < r.length; o++)
      r[o] === "preserve-inline" ? this.brace_preserve_inline = !0 : this.brace_style = r[o];
    this.unindent_chained_methods = this._get_boolean("unindent_chained_methods"), this.break_chained_methods = this._get_boolean("break_chained_methods"), this.space_in_paren = this._get_boolean("space_in_paren"), this.space_in_empty_paren = this._get_boolean("space_in_empty_paren"), this.jslint_happy = this._get_boolean("jslint_happy"), this.space_after_anon_function = this._get_boolean("space_after_anon_function"), this.space_after_named_function = this._get_boolean("space_after_named_function"), this.keep_array_indentation = this._get_boolean("keep_array_indentation"), this.space_before_conditional = this._get_boolean("space_before_conditional", !0), this.unescape_strings = this._get_boolean("unescape_strings"), this.e4x = this._get_boolean("e4x"), this.comma_first = this._get_boolean("comma_first"), this.operator_position = this._get_selection("operator_position", t), this.test_output_raw = this._get_boolean("test_output_raw"), this.jslint_happy && (this.space_after_anon_function = !0);
  }
  return e.prototype = new i(), xl.Options = e, xl;
}
var Zi = {}, kl = {}, Sf;
function Ih() {
  if (Sf)
    return kl;
  Sf = 1;
  var i = RegExp.prototype.hasOwnProperty("sticky");
  function t(e) {
    this.__input = e || "", this.__input_length = this.__input.length, this.__position = 0;
  }
  return t.prototype.restart = function() {
    this.__position = 0;
  }, t.prototype.back = function() {
    this.__position > 0 && (this.__position -= 1);
  }, t.prototype.hasNext = function() {
    return this.__position < this.__input_length;
  }, t.prototype.next = function() {
    var e = null;
    return this.hasNext() && (e = this.__input.charAt(this.__position), this.__position += 1), e;
  }, t.prototype.peek = function(e) {
    var s = null;
    return e = e || 0, e += this.__position, e >= 0 && e < this.__input_length && (s = this.__input.charAt(e)), s;
  }, t.prototype.__match = function(e, s) {
    e.lastIndex = s;
    var n = e.exec(this.__input);
    return n && !(i && e.sticky) && n.index !== s && (n = null), n;
  }, t.prototype.test = function(e, s) {
    return s = s || 0, s += this.__position, s >= 0 && s < this.__input_length ? !!this.__match(e, s) : !1;
  }, t.prototype.testChar = function(e, s) {
    var n = this.peek(s);
    return e.lastIndex = 0, n !== null && e.test(n);
  }, t.prototype.match = function(e) {
    var s = this.__match(e, this.__position);
    return s ? this.__position += s[0].length : s = null, s;
  }, t.prototype.read = function(e, s, n) {
    var r = "", o;
    return e && (o = this.match(e), o && (r += o[0])), s && (o || !e) && (r += this.readUntil(s, n)), r;
  }, t.prototype.readUntil = function(e, s) {
    var n = "", r = this.__position;
    e.lastIndex = this.__position;
    var o = e.exec(this.__input);
    return o ? (r = o.index, s && (r += o[0].length)) : r = this.__input_length, n = this.__input.substring(this.__position, r), this.__position = r, n;
  }, t.prototype.readUntilAfter = function(e) {
    return this.readUntil(e, !0);
  }, t.prototype.get_regexp = function(e, s) {
    var n = null, r = "g";
    return s && i && (r = "y"), typeof e == "string" && e !== "" ? n = new RegExp(e, r) : e && (n = new RegExp(e.source, r)), n;
  }, t.prototype.get_literal_regexp = function(e) {
    return RegExp(e.replace(/[-\/\\^$*+?.()|[\]{}]/g, "\\$&"));
  }, t.prototype.peekUntilAfter = function(e) {
    var s = this.__position, n = this.readUntilAfter(e);
    return this.__position = s, n;
  }, t.prototype.lookBack = function(e) {
    var s = this.__position - 1;
    return s >= e.length && this.__input.substring(s - e.length, s).toLowerCase() === e;
  }, kl.InputScanner = t, kl;
}
var er = {}, Ol = {}, Cf;
function pS() {
  if (Cf)
    return Ol;
  Cf = 1;
  function i(t) {
    this.__tokens = [], this.__tokens_length = this.__tokens.length, this.__position = 0, this.__parent_token = t;
  }
  return i.prototype.restart = function() {
    this.__position = 0;
  }, i.prototype.isEmpty = function() {
    return this.__tokens_length === 0;
  }, i.prototype.hasNext = function() {
    return this.__position < this.__tokens_length;
  }, i.prototype.next = function() {
    var t = null;
    return this.hasNext() && (t = this.__tokens[this.__position], this.__position += 1), t;
  }, i.prototype.peek = function(t) {
    var e = null;
    return t = t || 0, t += this.__position, t >= 0 && t < this.__tokens_length && (e = this.__tokens[t]), e;
  }, i.prototype.add = function(t) {
    this.__parent_token && (t.parent = this.__parent_token), this.__tokens.push(t), this.__tokens_length += 1;
  }, Ol.TokenStream = i, Ol;
}
var Sl = {}, Cl = {}, Af;
function Bo() {
  if (Af)
    return Cl;
  Af = 1;
  function i(t, e) {
    this._input = t, this._starting_pattern = null, this._match_pattern = null, this._until_pattern = null, this._until_after = !1, e && (this._starting_pattern = this._input.get_regexp(e._starting_pattern, !0), this._match_pattern = this._input.get_regexp(e._match_pattern, !0), this._until_pattern = this._input.get_regexp(e._until_pattern), this._until_after = e._until_after);
  }
  return i.prototype.read = function() {
    var t = this._input.read(this._starting_pattern);
    return (!this._starting_pattern || t) && (t += this._input.read(this._match_pattern, this._until_pattern, this._until_after)), t;
  }, i.prototype.read_match = function() {
    return this._input.match(this._match_pattern);
  }, i.prototype.until_after = function(t) {
    var e = this._create();
    return e._until_after = !0, e._until_pattern = this._input.get_regexp(t), e._update(), e;
  }, i.prototype.until = function(t) {
    var e = this._create();
    return e._until_after = !1, e._until_pattern = this._input.get_regexp(t), e._update(), e;
  }, i.prototype.starting_with = function(t) {
    var e = this._create();
    return e._starting_pattern = this._input.get_regexp(t, !0), e._update(), e;
  }, i.prototype.matching = function(t) {
    var e = this._create();
    return e._match_pattern = this._input.get_regexp(t, !0), e._update(), e;
  }, i.prototype._create = function() {
    return new i(this._input, this);
  }, i.prototype._update = function() {
  }, Cl.Pattern = i, Cl;
}
var Tf;
function gS() {
  if (Tf)
    return Sl;
  Tf = 1;
  var i = Bo().Pattern;
  function t(e, s) {
    i.call(this, e, s), s ? this._line_regexp = this._input.get_regexp(s._line_regexp) : this.__set_whitespace_patterns("", ""), this.newline_count = 0, this.whitespace_before_token = "";
  }
  return t.prototype = new i(), t.prototype.__set_whitespace_patterns = function(e, s) {
    e += "\\t ", s += "\\n\\r", this._match_pattern = this._input.get_regexp(
      "[" + e + s + "]+",
      !0
    ), this._newline_regexp = this._input.get_regexp(
      "\\r\\n|[" + s + "]"
    );
  }, t.prototype.read = function() {
    this.newline_count = 0, this.whitespace_before_token = "";
    var e = this._input.read(this._match_pattern);
    if (e === " ")
      this.whitespace_before_token = " ";
    else if (e) {
      var s = this.__split(this._newline_regexp, e);
      this.newline_count = s.length - 1, this.whitespace_before_token = s[this.newline_count];
    }
    return e;
  }, t.prototype.matching = function(e, s) {
    var n = this._create();
    return n.__set_whitespace_patterns(e, s), n._update(), n;
  }, t.prototype._create = function() {
    return new t(this._input, this);
  }, t.prototype.__split = function(e, s) {
    e.lastIndex = 0;
    for (var n = 0, r = [], o = e.exec(s); o; )
      r.push(s.substring(n, o.index)), n = o.index + o[0].length, o = e.exec(s);
    return n < s.length ? r.push(s.substring(n, s.length)) : r.push(""), r;
  }, Sl.WhitespacePattern = t, Sl;
}
var Pf;
function so() {
  if (Pf)
    return er;
  Pf = 1;
  var i = Ih().InputScanner, t = Wm().Token, e = pS().TokenStream, s = gS().WhitespacePattern, n = {
    START: "TK_START",
    RAW: "TK_RAW",
    EOF: "TK_EOF"
  }, r = function(o, l) {
    this._input = new i(o), this._options = l || {}, this.__tokens = null, this._patterns = {}, this._patterns.whitespace = new s(this._input);
  };
  return r.prototype.tokenize = function() {
    this._input.restart(), this.__tokens = new e(), this._reset();
    for (var o, l = new t(n.START, ""), a = null, h = [], u = new e(); l.type !== n.EOF; ) {
      for (o = this._get_next_token(l, a); this._is_comment(o); )
        u.add(o), o = this._get_next_token(l, a);
      u.isEmpty() || (o.comments_before = u, u = new e()), o.parent = a, this._is_opening(o) ? (h.push(a), a = o) : a && this._is_closing(o, a) && (o.opened = a, a.closed = o, a = h.pop(), o.parent = a), o.previous = l, l.next = o, this.__tokens.add(o), l = o;
    }
    return this.__tokens;
  }, r.prototype._is_first_token = function() {
    return this.__tokens.isEmpty();
  }, r.prototype._reset = function() {
  }, r.prototype._get_next_token = function(o, l) {
    this._readWhitespace();
    var a = this._input.read(/.+/g);
    return a ? this._create_token(n.RAW, a) : this._create_token(n.EOF, "");
  }, r.prototype._is_comment = function(o) {
    return !1;
  }, r.prototype._is_opening = function(o) {
    return !1;
  }, r.prototype._is_closing = function(o, l) {
    return !1;
  }, r.prototype._create_token = function(o, l) {
    var a = new t(
      o,
      l,
      this._patterns.whitespace.newline_count,
      this._patterns.whitespace.whitespace_before_token
    );
    return a;
  }, r.prototype._readWhitespace = function() {
    return this._patterns.whitespace.read();
  }, er.Tokenizer = r, er.TOKEN = n, er;
}
var Al = {}, Ef;
function Qh() {
  if (Ef)
    return Al;
  Ef = 1;
  function i(t, e) {
    t = typeof t == "string" ? t : t.source, e = typeof e == "string" ? e : e.source, this.__directives_block_pattern = new RegExp(t + / beautify( \w+[:]\w+)+ /.source + e, "g"), this.__directive_pattern = / (\w+)[:](\w+)/g, this.__directives_end_ignore_pattern = new RegExp(t + /\sbeautify\signore:end\s/.source + e, "g");
  }
  return i.prototype.get_directives = function(t) {
    if (!t.match(this.__directives_block_pattern))
      return null;
    var e = {};
    this.__directive_pattern.lastIndex = 0;
    for (var s = this.__directive_pattern.exec(t); s; )
      e[s[1]] = s[2], s = this.__directive_pattern.exec(t);
    return e;
  }, i.prototype.readIgnored = function(t) {
    return t.readUntilAfter(this.__directives_end_ignore_pattern);
  }, Al.Directives = i, Al;
}
var Tl = {}, Mf;
function Vm() {
  if (Mf)
    return Tl;
  Mf = 1;
  var i = Bo().Pattern, t = {
    django: !1,
    erb: !1,
    handlebars: !1,
    php: !1,
    smarty: !1
  };
  function e(s, n) {
    i.call(this, s, n), this.__template_pattern = null, this._disabled = Object.assign({}, t), this._excluded = Object.assign({}, t), n && (this.__template_pattern = this._input.get_regexp(n.__template_pattern), this._excluded = Object.assign(this._excluded, n._excluded), this._disabled = Object.assign(this._disabled, n._disabled));
    var r = new i(s);
    this.__patterns = {
      handlebars_comment: r.starting_with(/{{!--/).until_after(/--}}/),
      handlebars_unescaped: r.starting_with(/{{{/).until_after(/}}}/),
      handlebars: r.starting_with(/{{/).until_after(/}}/),
      php: r.starting_with(/<\?(?:[= ]|php)/).until_after(/\?>/),
      erb: r.starting_with(/<%[^%]/).until_after(/[^%]%>/),
      django: r.starting_with(/{%/).until_after(/%}/),
      django_value: r.starting_with(/{{/).until_after(/}}/),
      django_comment: r.starting_with(/{#/).until_after(/#}/),
      smarty: r.starting_with(/{(?=[^}{\s\n])/).until_after(/[^\s\n]}/),
      smarty_comment: r.starting_with(/{\*/).until_after(/\*}/),
      smarty_literal: r.starting_with(/{literal}/).until_after(/{\/literal}/)
    };
  }
  return e.prototype = new i(), e.prototype._create = function() {
    return new e(this._input, this);
  }, e.prototype._update = function() {
    this.__set_templated_pattern();
  }, e.prototype.disable = function(s) {
    var n = this._create();
    return n._disabled[s] = !0, n._update(), n;
  }, e.prototype.read_options = function(s) {
    var n = this._create();
    for (var r in t)
      n._disabled[r] = s.templating.indexOf(r) === -1;
    return n._update(), n;
  }, e.prototype.exclude = function(s) {
    var n = this._create();
    return n._excluded[s] = !0, n._update(), n;
  }, e.prototype.read = function() {
    var s = "";
    this._match_pattern ? s = this._input.read(this._starting_pattern) : s = this._input.read(this._starting_pattern, this.__template_pattern);
    for (var n = this._read_template(); n; )
      this._match_pattern ? n += this._input.read(this._match_pattern) : n += this._input.readUntil(this.__template_pattern), s += n, n = this._read_template();
    return this._until_after && (s += this._input.readUntilAfter(this._until_pattern)), s;
  }, e.prototype.__set_templated_pattern = function() {
    var s = [];
    this._disabled.php || s.push(this.__patterns.php._starting_pattern.source), this._disabled.handlebars || s.push(this.__patterns.handlebars._starting_pattern.source), this._disabled.erb || s.push(this.__patterns.erb._starting_pattern.source), this._disabled.django || (s.push(this.__patterns.django._starting_pattern.source), s.push(this.__patterns.django_value._starting_pattern.source), s.push(this.__patterns.django_comment._starting_pattern.source)), this._disabled.smarty || s.push(this.__patterns.smarty._starting_pattern.source), this._until_pattern && s.push(this._until_pattern.source), this.__template_pattern = this._input.get_regexp("(?:" + s.join("|") + ")");
  }, e.prototype._read_template = function() {
    var s = "", n = this._input.peek();
    if (n === "<") {
      var r = this._input.peek(1);
      !this._disabled.php && !this._excluded.php && r === "?" && (s = s || this.__patterns.php.read()), !this._disabled.erb && !this._excluded.erb && r === "%" && (s = s || this.__patterns.erb.read());
    } else
      n === "{" && (!this._disabled.handlebars && !this._excluded.handlebars && (s = s || this.__patterns.handlebars_comment.read(), s = s || this.__patterns.handlebars_unescaped.read(), s = s || this.__patterns.handlebars.read()), this._disabled.django || (!this._excluded.django && !this._excluded.handlebars && (s = s || this.__patterns.django_value.read()), this._excluded.django || (s = s || this.__patterns.django_comment.read(), s = s || this.__patterns.django.read())), this._disabled.smarty || this._disabled.django && this._disabled.handlebars && (s = s || this.__patterns.smarty_comment.read(), s = s || this.__patterns.smarty_literal.read(), s = s || this.__patterns.smarty.read()));
    return s;
  }, Tl.TemplatablePattern = e, Tl;
}
var Rf;
function ir() {
  if (Rf)
    return Zi;
  Rf = 1;
  var i = Ih().InputScanner, t = so().Tokenizer, e = so().TOKEN, s = Qh().Directives, n = $m(), r = Bo().Pattern, o = Vm().TemplatablePattern;
  function l(v, C) {
    return C.indexOf(v) !== -1;
  }
  var a = {
    START_EXPR: "TK_START_EXPR",
    END_EXPR: "TK_END_EXPR",
    START_BLOCK: "TK_START_BLOCK",
    END_BLOCK: "TK_END_BLOCK",
    WORD: "TK_WORD",
    RESERVED: "TK_RESERVED",
    SEMICOLON: "TK_SEMICOLON",
    STRING: "TK_STRING",
    EQUALS: "TK_EQUALS",
    OPERATOR: "TK_OPERATOR",
    COMMA: "TK_COMMA",
    BLOCK_COMMENT: "TK_BLOCK_COMMENT",
    COMMENT: "TK_COMMENT",
    DOT: "TK_DOT",
    UNKNOWN: "TK_UNKNOWN",
    START: e.START,
    RAW: e.RAW,
    EOF: e.EOF
  }, h = new s(/\/\*/, /\*\//), u = /0[xX][0123456789abcdefABCDEF_]*n?|0[oO][01234567_]*n?|0[bB][01_]*n?|\d[\d_]*n|(?:\.\d[\d_]*|\d[\d_]*\.?[\d_]*)(?:[eE][+-]?[\d_]+)?/, c = /[0-9]/, f = /[^\d\.]/, g = ">>> === !== &&= ??= ||= << && >= ** != == <= >> || ?? |> < / - + > : & % ? ^ | *".split(" "), _ = ">>>= ... >>= <<= === >>> !== **= &&= ??= ||= => ^= :: /= << <= == && -= >= >> != -- += ** || ?? ++ %= &= *= |= |> = ! ? > < : / ^ - + * & % ~ |";
  _ = _.replace(/[-[\]{}()*+?.,\\^$|#]/g, "\\$&"), _ = "\\?\\.(?!\\d) " + _, _ = _.replace(/ /g, "|");
  var A = new RegExp(_), m = "continue,try,throw,return,var,let,const,if,switch,case,default,for,while,break,function,import,export".split(","), p = m.concat(["do", "in", "of", "else", "get", "set", "new", "catch", "finally", "typeof", "yield", "async", "await", "from", "as", "class", "extends"]), y = new RegExp("^(?:" + p.join("|") + ")$"), M, x = function(v, C) {
    t.call(this, v, C), this._patterns.whitespace = this._patterns.whitespace.matching(
      /\u00A0\u1680\u180e\u2000-\u200a\u202f\u205f\u3000\ufeff/.source,
      /\u2028\u2029/.source
    );
    var k = new r(this._input), E = new o(this._input).read_options(this._options);
    this.__patterns = {
      template: E,
      identifier: E.starting_with(n.identifier).matching(n.identifierMatch),
      number: k.matching(u),
      punct: k.matching(A),
      comment: k.starting_with(/\/\//).until(/[\n\r\u2028\u2029]/),
      block_comment: k.starting_with(/\/\*/).until_after(/\*\//),
      html_comment_start: k.matching(/<!--/),
      html_comment_end: k.matching(/-->/),
      include: k.starting_with(/#include/).until_after(n.lineBreak),
      shebang: k.starting_with(/#!/).until_after(n.lineBreak),
      xml: k.matching(/[\s\S]*?<(\/?)([-a-zA-Z:0-9_.]+|{[^}]+?}|!\[CDATA\[[^\]]*?\]\]|)(\s*{[^}]+?}|\s+[-a-zA-Z:0-9_.]+|\s+[-a-zA-Z:0-9_.]+\s*=\s*('[^']*'|"[^"]*"|{([^{}]|{[^}]+?})+?}))*\s*(\/?)\s*>/),
      single_quote: E.until(/['\\\n\r\u2028\u2029]/),
      double_quote: E.until(/["\\\n\r\u2028\u2029]/),
      template_text: E.until(/[`\\$]/),
      template_expression: E.until(/[`}\\]/)
    };
  };
  x.prototype = new t(), x.prototype._is_comment = function(v) {
    return v.type === a.COMMENT || v.type === a.BLOCK_COMMENT || v.type === a.UNKNOWN;
  }, x.prototype._is_opening = function(v) {
    return v.type === a.START_BLOCK || v.type === a.START_EXPR;
  }, x.prototype._is_closing = function(v, C) {
    return (v.type === a.END_BLOCK || v.type === a.END_EXPR) && C && (v.text === "]" && C.text === "[" || v.text === ")" && C.text === "(" || v.text === "}" && C.text === "{");
  }, x.prototype._reset = function() {
    M = !1;
  }, x.prototype._get_next_token = function(v, C) {
    var k = null;
    this._readWhitespace();
    var E = this._input.peek();
    return E === null ? this._create_token(a.EOF, "") : (k = k || this._read_non_javascript(E), k = k || this._read_string(E), k = k || this._read_word(v), k = k || this._read_singles(E), k = k || this._read_comment(E), k = k || this._read_regexp(E, v), k = k || this._read_xml(E, v), k = k || this._read_punctuation(), k = k || this._create_token(a.UNKNOWN, this._input.next()), k);
  }, x.prototype._read_word = function(v) {
    var C;
    if (C = this.__patterns.identifier.read(), C !== "")
      return C = C.replace(n.allLineBreaks, `
`), !(v.type === a.DOT || v.type === a.RESERVED && (v.text === "set" || v.text === "get")) && y.test(C) ? (C === "in" || C === "of") && (v.type === a.WORD || v.type === a.STRING) ? this._create_token(a.OPERATOR, C) : this._create_token(a.RESERVED, C) : this._create_token(a.WORD, C);
    if (C = this.__patterns.number.read(), C !== "")
      return this._create_token(a.WORD, C);
  }, x.prototype._read_singles = function(v) {
    var C = null;
    return v === "(" || v === "[" ? C = this._create_token(a.START_EXPR, v) : v === ")" || v === "]" ? C = this._create_token(a.END_EXPR, v) : v === "{" ? C = this._create_token(a.START_BLOCK, v) : v === "}" ? C = this._create_token(a.END_BLOCK, v) : v === ";" ? C = this._create_token(a.SEMICOLON, v) : v === "." && f.test(this._input.peek(1)) ? C = this._create_token(a.DOT, v) : v === "," && (C = this._create_token(a.COMMA, v)), C && this._input.next(), C;
  }, x.prototype._read_punctuation = function() {
    var v = this.__patterns.punct.read();
    if (v !== "")
      return v === "=" ? this._create_token(a.EQUALS, v) : v === "?." ? this._create_token(a.DOT, v) : this._create_token(a.OPERATOR, v);
  }, x.prototype._read_non_javascript = function(v) {
    var C = "";
    if (v === "#") {
      if (this._is_first_token() && (C = this.__patterns.shebang.read(), C))
        return this._create_token(a.UNKNOWN, C.trim() + `
`);
      if (C = this.__patterns.include.read(), C)
        return this._create_token(a.UNKNOWN, C.trim() + `
`);
      v = this._input.next();
      var k = "#";
      if (this._input.hasNext() && this._input.testChar(c)) {
        do
          v = this._input.next(), k += v;
        while (this._input.hasNext() && v !== "#" && v !== "=");
        return v === "#" || (this._input.peek() === "[" && this._input.peek(1) === "]" ? (k += "[]", this._input.next(), this._input.next()) : this._input.peek() === "{" && this._input.peek(1) === "}" && (k += "{}", this._input.next(), this._input.next())), this._create_token(a.WORD, k);
      }
      this._input.back();
    } else if (v === "<" && this._is_first_token()) {
      if (C = this.__patterns.html_comment_start.read(), C) {
        for (; this._input.hasNext() && !this._input.testChar(n.newline); )
          C += this._input.next();
        return M = !0, this._create_token(a.COMMENT, C);
      }
    } else if (M && v === "-" && (C = this.__patterns.html_comment_end.read(), C))
      return M = !1, this._create_token(a.COMMENT, C);
    return null;
  }, x.prototype._read_comment = function(v) {
    var C = null;
    if (v === "/") {
      var k = "";
      if (this._input.peek(1) === "*") {
        k = this.__patterns.block_comment.read();
        var E = h.get_directives(k);
        E && E.ignore === "start" && (k += h.readIgnored(this._input)), k = k.replace(n.allLineBreaks, `
`), C = this._create_token(a.BLOCK_COMMENT, k), C.directives = E;
      } else
        this._input.peek(1) === "/" && (k = this.__patterns.comment.read(), C = this._create_token(a.COMMENT, k));
    }
    return C;
  }, x.prototype._read_string = function(v) {
    if (v === "`" || v === "'" || v === '"') {
      var C = this._input.next();
      return this.has_char_escapes = !1, v === "`" ? C += this._read_string_recursive("`", !0, "${") : C += this._read_string_recursive(v), this.has_char_escapes && this._options.unescape_strings && (C = B(C)), this._input.peek() === v && (C += this._input.next()), C = C.replace(n.allLineBreaks, `
`), this._create_token(a.STRING, C);
    }
    return null;
  }, x.prototype._allow_regexp_or_xml = function(v) {
    return v.type === a.RESERVED && l(v.text, ["return", "case", "throw", "else", "do", "typeof", "yield"]) || v.type === a.END_EXPR && v.text === ")" && v.opened.previous.type === a.RESERVED && l(v.opened.previous.text, ["if", "while", "for"]) || l(v.type, [
      a.COMMENT,
      a.START_EXPR,
      a.START_BLOCK,
      a.START,
      a.END_BLOCK,
      a.OPERATOR,
      a.EQUALS,
      a.EOF,
      a.SEMICOLON,
      a.COMMA
    ]);
  }, x.prototype._read_regexp = function(v, C) {
    if (v === "/" && this._allow_regexp_or_xml(C)) {
      for (var k = this._input.next(), E = !1, d = !1; this._input.hasNext() && (E || d || this._input.peek() !== v) && !this._input.testChar(n.newline); )
        k += this._input.peek(), E ? E = !1 : (E = this._input.peek() === "\\", this._input.peek() === "[" ? d = !0 : this._input.peek() === "]" && (d = !1)), this._input.next();
      return this._input.peek() === v && (k += this._input.next(), k += this._input.read(n.identifier)), this._create_token(a.STRING, k);
    }
    return null;
  }, x.prototype._read_xml = function(v, C) {
    if (this._options.e4x && v === "<" && this._allow_regexp_or_xml(C)) {
      var k = "", E = this.__patterns.xml.read_match();
      if (E) {
        for (var d = E[2].replace(/^{\s+/, "{").replace(/\s+}$/, "}"), S = d.indexOf("{") === 0, T = 0; E; ) {
          var $ = !!E[1], K = E[2], it = !!E[E.length - 1] || K.slice(0, 8) === "![CDATA[";
          if (!it && (K === d || S && K.replace(/^{\s+/, "{").replace(/\s+}$/, "}")) && ($ ? --T : ++T), k += E[0], T <= 0)
            break;
          E = this.__patterns.xml.read_match();
        }
        return E || (k += this._input.match(/[\s\S]*/g)[0]), k = k.replace(n.allLineBreaks, `
`), this._create_token(a.STRING, k);
      }
    }
    return null;
  };
  function B(v) {
    for (var C = "", k = 0, E = new i(v), d = null; E.hasNext(); )
      if (d = E.match(/([\s]|[^\\]|\\\\)+/g), d && (C += d[0]), E.peek() === "\\") {
        if (E.next(), E.peek() === "x")
          d = E.match(/x([0-9A-Fa-f]{2})/g);
        else if (E.peek() === "u")
          d = E.match(/u([0-9A-Fa-f]{4})/g);
        else {
          C += "\\", E.hasNext() && (C += E.next());
          continue;
        }
        if (!d || (k = parseInt(d[1], 16), k > 126 && k <= 255 && d[0].indexOf("x") === 0))
          return v;
        if (k >= 0 && k < 32) {
          C += "\\" + d[0];
          continue;
        } else
          k === 34 || k === 39 || k === 92 ? C += "\\" + String.fromCharCode(k) : C += String.fromCharCode(k);
      }
    return C;
  }
  return x.prototype._read_string_recursive = function(v, C, k) {
    var E, d;
    v === "'" ? d = this.__patterns.single_quote : v === '"' ? d = this.__patterns.double_quote : v === "`" ? d = this.__patterns.template_text : v === "}" && (d = this.__patterns.template_expression);
    for (var S = d.read(), T = ""; this._input.hasNext(); ) {
      if (T = this._input.next(), T === v || !C && n.newline.test(T)) {
        this._input.back();
        break;
      } else
        T === "\\" && this._input.hasNext() ? (E = this._input.peek(), E === "x" || E === "u" ? this.has_char_escapes = !0 : E === "\r" && this._input.peek(1) === `
` && this._input.next(), T += this._input.next()) : k && (k === "${" && T === "$" && this._input.peek() === "{" && (T += this._input.next()), k === T && (v === "`" ? T += this._read_string_recursive("}", C, "`") : T += this._read_string_recursive("`", C, "${"), this._input.hasNext() && (T += this._input.next())));
      T += d.read(), S += T;
    }
    return S;
  }, Zi.Tokenizer = x, Zi.TOKEN = a, Zi.positionable_operators = g.slice(), Zi.line_starters = m.slice(), Zi;
}
var Df;
function mS() {
  if (Df)
    return bl;
  Df = 1;
  var i = Nh().Output, t = Wm().Token, e = $m(), s = Fm().Options, n = ir().Tokenizer, r = ir().line_starters, o = ir().positionable_operators, l = ir().TOKEN;
  function a(d, S) {
    return S.indexOf(d) !== -1;
  }
  function h(d) {
    return d.replace(/^\s+/g, "");
  }
  function u(d) {
    for (var S = {}, T = 0; T < d.length; T++)
      S[d[T].replace(/-/g, "_")] = d[T];
    return S;
  }
  function c(d, S) {
    return d && d.type === l.RESERVED && d.text === S;
  }
  function f(d, S) {
    return d && d.type === l.RESERVED && a(d.text, S);
  }
  var g = ["case", "return", "do", "if", "throw", "else", "await", "break", "continue", "async"], _ = ["before-newline", "after-newline", "preserve-newline"], A = u(_), m = [A.before_newline, A.preserve_newline], p = {
    BlockStatement: "BlockStatement",
    Statement: "Statement",
    ObjectLiteral: "ObjectLiteral",
    ArrayLiteral: "ArrayLiteral",
    ForInitializer: "ForInitializer",
    Conditional: "Conditional",
    Expression: "Expression"
  };
  function y(d, S) {
    S.multiline_frame || S.mode === p.ForInitializer || S.mode === p.Conditional || d.remove_indent(S.start_line_index);
  }
  function M(d) {
    d = d.replace(e.allLineBreaks, `
`);
    for (var S = [], T = d.indexOf(`
`); T !== -1; )
      S.push(d.substring(0, T)), d = d.substring(T + 1), T = d.indexOf(`
`);
    return d.length && S.push(d), S;
  }
  function x(d) {
    return d === p.ArrayLiteral;
  }
  function B(d) {
    return a(d, [p.Expression, p.ForInitializer, p.Conditional]);
  }
  function v(d, S) {
    for (var T = 0; T < d.length; T++) {
      var $ = d[T].trim();
      if ($.charAt(0) !== S)
        return !1;
    }
    return !0;
  }
  function C(d, S) {
    for (var T = 0, $ = d.length, K; T < $; T++)
      if (K = d[T], K && K.indexOf(S) !== 0)
        return !1;
    return !0;
  }
  function k(d, S) {
    S = S || {}, this._source_text = d || "", this._output = null, this._tokens = null, this._last_last_text = null, this._flags = null, this._previous_flags = null, this._flag_store = null, this._options = new s(S);
  }
  k.prototype.create_flags = function(d, S) {
    var T = 0;
    d && (T = d.indentation_level, !this._output.just_added_newline() && d.line_indent_level > T && (T = d.line_indent_level));
    var $ = {
      mode: S,
      parent: d,
      last_token: d ? d.last_token : new t(l.START_BLOCK, ""),
      last_word: d ? d.last_word : "",
      declaration_statement: !1,
      declaration_assignment: !1,
      multiline_frame: !1,
      inline_frame: !1,
      if_block: !1,
      else_block: !1,
      class_start_block: !1,
      do_block: !1,
      do_while: !1,
      import_block: !1,
      in_case_statement: !1,
      in_case: !1,
      case_body: !1,
      case_block: !1,
      indentation_level: T,
      alignment: 0,
      line_indent_level: d ? d.line_indent_level : T,
      start_line_index: this._output.get_line_number(),
      ternary_depth: 0
    };
    return $;
  }, k.prototype._reset = function(d) {
    var S = d.match(/^[\t ]*/)[0];
    this._last_last_text = "", this._output = new i(this._options, S), this._output.raw = this._options.test_output_raw, this._flag_store = [], this.set_mode(p.BlockStatement);
    var T = new n(d, this._options);
    return this._tokens = T.tokenize(), d;
  }, k.prototype.beautify = function() {
    if (this._options.disabled)
      return this._source_text;
    var d, S = this._reset(this._source_text), T = this._options.eol;
    this._options.eol === "auto" && (T = `
`, S && e.lineBreak.test(S || "") && (T = S.match(e.lineBreak)[0]));
    for (var $ = this._tokens.next(); $; )
      this.handle_token($), this._last_last_text = this._flags.last_token.text, this._flags.last_token = $, $ = this._tokens.next();
    return d = this._output.get_code(T), d;
  }, k.prototype.handle_token = function(d, S) {
    d.type === l.START_EXPR ? this.handle_start_expr(d) : d.type === l.END_EXPR ? this.handle_end_expr(d) : d.type === l.START_BLOCK ? this.handle_start_block(d) : d.type === l.END_BLOCK ? this.handle_end_block(d) : d.type === l.WORD ? this.handle_word(d) : d.type === l.RESERVED ? this.handle_word(d) : d.type === l.SEMICOLON ? this.handle_semicolon(d) : d.type === l.STRING ? this.handle_string(d) : d.type === l.EQUALS ? this.handle_equals(d) : d.type === l.OPERATOR ? this.handle_operator(d) : d.type === l.COMMA ? this.handle_comma(d) : d.type === l.BLOCK_COMMENT ? this.handle_block_comment(d, S) : d.type === l.COMMENT ? this.handle_comment(d, S) : d.type === l.DOT ? this.handle_dot(d) : d.type === l.EOF ? this.handle_eof(d) : d.type === l.UNKNOWN ? this.handle_unknown(d, S) : this.handle_unknown(d, S);
  }, k.prototype.handle_whitespace_and_comments = function(d, S) {
    var T = d.newlines, $ = this._options.keep_array_indentation && x(this._flags.mode);
    if (d.comments_before)
      for (var K = d.comments_before.next(); K; )
        this.handle_whitespace_and_comments(K, S), this.handle_token(K, S), K = d.comments_before.next();
    if ($)
      for (var it = 0; it < T; it += 1)
        this.print_newline(it > 0, S);
    else if (this._options.max_preserve_newlines && T > this._options.max_preserve_newlines && (T = this._options.max_preserve_newlines), this._options.preserve_newlines && T > 1) {
      this.print_newline(!1, S);
      for (var Y = 1; Y < T; Y += 1)
        this.print_newline(!0, S);
    }
  };
  var E = ["async", "break", "continue", "return", "throw", "yield"];
  return k.prototype.allow_wrap_or_preserved_newline = function(d, S) {
    if (S = S === void 0 ? !1 : S, !this._output.just_added_newline()) {
      var T = this._options.preserve_newlines && d.newlines || S, $ = a(this._flags.last_token.text, o) || a(d.text, o);
      if ($) {
        var K = a(this._flags.last_token.text, o) && a(this._options.operator_position, m) || a(d.text, o);
        T = T && K;
      }
      if (T)
        this.print_newline(!1, !0);
      else if (this._options.wrap_line_length) {
        if (f(this._flags.last_token, E))
          return;
        this._output.set_wrap_point();
      }
    }
  }, k.prototype.print_newline = function(d, S) {
    if (!S && this._flags.last_token.text !== ";" && this._flags.last_token.text !== "," && this._flags.last_token.text !== "=" && (this._flags.last_token.type !== l.OPERATOR || this._flags.last_token.text === "--" || this._flags.last_token.text === "++"))
      for (var T = this._tokens.peek(); this._flags.mode === p.Statement && !(this._flags.if_block && c(T, "else")) && !this._flags.do_block; )
        this.restore_mode();
    this._output.add_new_line(d) && (this._flags.multiline_frame = !0);
  }, k.prototype.print_token_line_indentation = function(d) {
    this._output.just_added_newline() && (this._options.keep_array_indentation && d.newlines && (d.text === "[" || x(this._flags.mode)) ? (this._output.current_line.set_indent(-1), this._output.current_line.push(d.whitespace_before), this._output.space_before_token = !1) : this._output.set_indent(this._flags.indentation_level, this._flags.alignment) && (this._flags.line_indent_level = this._flags.indentation_level));
  }, k.prototype.print_token = function(d) {
    if (this._output.raw) {
      this._output.add_raw_token(d);
      return;
    }
    if (this._options.comma_first && d.previous && d.previous.type === l.COMMA && this._output.just_added_newline() && this._output.previous_line.last() === ",") {
      var S = this._output.previous_line.pop();
      this._output.previous_line.is_empty() && (this._output.previous_line.push(S), this._output.trim(!0), this._output.current_line.pop(), this._output.trim()), this.print_token_line_indentation(d), this._output.add_token(","), this._output.space_before_token = !0;
    }
    this.print_token_line_indentation(d), this._output.non_breaking_space = !0, this._output.add_token(d.text), this._output.previous_token_wrapped && (this._flags.multiline_frame = !0);
  }, k.prototype.indent = function() {
    this._flags.indentation_level += 1, this._output.set_indent(this._flags.indentation_level, this._flags.alignment);
  }, k.prototype.deindent = function() {
    this._flags.indentation_level > 0 && (!this._flags.parent || this._flags.indentation_level > this._flags.parent.indentation_level) && (this._flags.indentation_level -= 1, this._output.set_indent(this._flags.indentation_level, this._flags.alignment));
  }, k.prototype.set_mode = function(d) {
    this._flags ? (this._flag_store.push(this._flags), this._previous_flags = this._flags) : this._previous_flags = this.create_flags(null, d), this._flags = this.create_flags(this._previous_flags, d), this._output.set_indent(this._flags.indentation_level, this._flags.alignment);
  }, k.prototype.restore_mode = function() {
    this._flag_store.length > 0 && (this._previous_flags = this._flags, this._flags = this._flag_store.pop(), this._previous_flags.mode === p.Statement && y(this._output, this._previous_flags), this._output.set_indent(this._flags.indentation_level, this._flags.alignment));
  }, k.prototype.start_of_object_property = function() {
    return this._flags.parent.mode === p.ObjectLiteral && this._flags.mode === p.Statement && (this._flags.last_token.text === ":" && this._flags.ternary_depth === 0 || f(this._flags.last_token, ["get", "set"]));
  }, k.prototype.start_of_statement = function(d) {
    var S = !1;
    return S = S || f(this._flags.last_token, ["var", "let", "const"]) && d.type === l.WORD, S = S || c(this._flags.last_token, "do"), S = S || !(this._flags.parent.mode === p.ObjectLiteral && this._flags.mode === p.Statement) && f(this._flags.last_token, E) && !d.newlines, S = S || c(this._flags.last_token, "else") && !(c(d, "if") && !d.comments_before), S = S || this._flags.last_token.type === l.END_EXPR && (this._previous_flags.mode === p.ForInitializer || this._previous_flags.mode === p.Conditional), S = S || this._flags.last_token.type === l.WORD && this._flags.mode === p.BlockStatement && !this._flags.in_case && !(d.text === "--" || d.text === "++") && this._last_last_text !== "function" && d.type !== l.WORD && d.type !== l.RESERVED, S = S || this._flags.mode === p.ObjectLiteral && (this._flags.last_token.text === ":" && this._flags.ternary_depth === 0 || f(this._flags.last_token, ["get", "set"])), S ? (this.set_mode(p.Statement), this.indent(), this.handle_whitespace_and_comments(d, !0), this.start_of_object_property() || this.allow_wrap_or_preserved_newline(
      d,
      f(d, ["do", "for", "if", "while"])
    ), !0) : !1;
  }, k.prototype.handle_start_expr = function(d) {
    this.start_of_statement(d) || this.handle_whitespace_and_comments(d);
    var S = p.Expression;
    if (d.text === "[") {
      if (this._flags.last_token.type === l.WORD || this._flags.last_token.text === ")") {
        f(this._flags.last_token, r) && (this._output.space_before_token = !0), this.print_token(d), this.set_mode(S), this.indent(), this._options.space_in_paren && (this._output.space_before_token = !0);
        return;
      }
      S = p.ArrayLiteral, x(this._flags.mode) && (this._flags.last_token.text === "[" || this._flags.last_token.text === "," && (this._last_last_text === "]" || this._last_last_text === "}")) && (this._options.keep_array_indentation || this.print_newline()), a(this._flags.last_token.type, [l.START_EXPR, l.END_EXPR, l.WORD, l.OPERATOR, l.DOT]) || (this._output.space_before_token = !0);
    } else {
      if (this._flags.last_token.type === l.RESERVED)
        this._flags.last_token.text === "for" ? (this._output.space_before_token = this._options.space_before_conditional, S = p.ForInitializer) : a(this._flags.last_token.text, ["if", "while", "switch"]) ? (this._output.space_before_token = this._options.space_before_conditional, S = p.Conditional) : a(this._flags.last_word, ["await", "async"]) ? this._output.space_before_token = !0 : this._flags.last_token.text === "import" && d.whitespace_before === "" ? this._output.space_before_token = !1 : (a(this._flags.last_token.text, r) || this._flags.last_token.text === "catch") && (this._output.space_before_token = !0);
      else if (this._flags.last_token.type === l.EQUALS || this._flags.last_token.type === l.OPERATOR)
        this.start_of_object_property() || this.allow_wrap_or_preserved_newline(d);
      else if (this._flags.last_token.type === l.WORD) {
        this._output.space_before_token = !1;
        var T = this._tokens.peek(-3);
        if (this._options.space_after_named_function && T) {
          var $ = this._tokens.peek(-4);
          f(T, ["async", "function"]) || T.text === "*" && f($, ["async", "function"]) ? this._output.space_before_token = !0 : this._flags.mode === p.ObjectLiteral ? (T.text === "{" || T.text === "," || T.text === "*" && ($.text === "{" || $.text === ",")) && (this._output.space_before_token = !0) : this._flags.parent && this._flags.parent.class_start_block && (this._output.space_before_token = !0);
        }
      } else
        this.allow_wrap_or_preserved_newline(d);
      (this._flags.last_token.type === l.RESERVED && (this._flags.last_word === "function" || this._flags.last_word === "typeof") || this._flags.last_token.text === "*" && (a(this._last_last_text, ["function", "yield"]) || this._flags.mode === p.ObjectLiteral && a(this._last_last_text, ["{", ","]))) && (this._output.space_before_token = this._options.space_after_anon_function);
    }
    this._flags.last_token.text === ";" || this._flags.last_token.type === l.START_BLOCK ? this.print_newline() : (this._flags.last_token.type === l.END_EXPR || this._flags.last_token.type === l.START_EXPR || this._flags.last_token.type === l.END_BLOCK || this._flags.last_token.text === "." || this._flags.last_token.type === l.COMMA) && this.allow_wrap_or_preserved_newline(d, d.newlines), this.print_token(d), this.set_mode(S), this._options.space_in_paren && (this._output.space_before_token = !0), this.indent();
  }, k.prototype.handle_end_expr = function(d) {
    for (; this._flags.mode === p.Statement; )
      this.restore_mode();
    this.handle_whitespace_and_comments(d), this._flags.multiline_frame && this.allow_wrap_or_preserved_newline(
      d,
      d.text === "]" && x(this._flags.mode) && !this._options.keep_array_indentation
    ), this._options.space_in_paren && (this._flags.last_token.type === l.START_EXPR && !this._options.space_in_empty_paren ? (this._output.trim(), this._output.space_before_token = !1) : this._output.space_before_token = !0), this.deindent(), this.print_token(d), this.restore_mode(), y(this._output, this._previous_flags), this._flags.do_while && this._previous_flags.mode === p.Conditional && (this._previous_flags.mode = p.Expression, this._flags.do_block = !1, this._flags.do_while = !1);
  }, k.prototype.handle_start_block = function(d) {
    this.handle_whitespace_and_comments(d);
    var S = this._tokens.peek(), T = this._tokens.peek(1);
    this._flags.last_word === "switch" && this._flags.last_token.type === l.END_EXPR ? (this.set_mode(p.BlockStatement), this._flags.in_case_statement = !0) : this._flags.case_body ? this.set_mode(p.BlockStatement) : T && (a(T.text, [":", ","]) && a(S.type, [l.STRING, l.WORD, l.RESERVED]) || a(S.text, ["get", "set", "..."]) && a(T.type, [l.WORD, l.RESERVED])) ? a(this._last_last_text, ["class", "interface"]) && !a(T.text, [":", ","]) ? this.set_mode(p.BlockStatement) : this.set_mode(p.ObjectLiteral) : this._flags.last_token.type === l.OPERATOR && this._flags.last_token.text === "=>" ? this.set_mode(p.BlockStatement) : a(this._flags.last_token.type, [l.EQUALS, l.START_EXPR, l.COMMA, l.OPERATOR]) || f(this._flags.last_token, ["return", "throw", "import", "default"]) ? this.set_mode(p.ObjectLiteral) : this.set_mode(p.BlockStatement), this._flags.last_token && f(this._flags.last_token.previous, ["class", "extends"]) && (this._flags.class_start_block = !0);
    var $ = !S.comments_before && S.text === "}", K = $ && this._flags.last_word === "function" && this._flags.last_token.type === l.END_EXPR;
    if (this._options.brace_preserve_inline) {
      var it = 0, Y = null;
      this._flags.inline_frame = !0;
      do
        if (it += 1, Y = this._tokens.peek(it - 1), Y.newlines) {
          this._flags.inline_frame = !1;
          break;
        }
      while (Y.type !== l.EOF && !(Y.type === l.END_BLOCK && Y.opened === d));
    }
    (this._options.brace_style === "expand" || this._options.brace_style === "none" && d.newlines) && !this._flags.inline_frame ? this._flags.last_token.type !== l.OPERATOR && (K || this._flags.last_token.type === l.EQUALS || f(this._flags.last_token, g) && this._flags.last_token.text !== "else") ? this._output.space_before_token = !0 : this.print_newline(!1, !0) : (x(this._previous_flags.mode) && (this._flags.last_token.type === l.START_EXPR || this._flags.last_token.type === l.COMMA) && ((this._flags.last_token.type === l.COMMA || this._options.space_in_paren) && (this._output.space_before_token = !0), (this._flags.last_token.type === l.COMMA || this._flags.last_token.type === l.START_EXPR && this._flags.inline_frame) && (this.allow_wrap_or_preserved_newline(d), this._previous_flags.multiline_frame = this._previous_flags.multiline_frame || this._flags.multiline_frame, this._flags.multiline_frame = !1)), this._flags.last_token.type !== l.OPERATOR && this._flags.last_token.type !== l.START_EXPR && (this._flags.last_token.type === l.START_BLOCK && !this._flags.inline_frame ? this.print_newline() : this._output.space_before_token = !0)), this.print_token(d), this.indent(), !$ && !(this._options.brace_preserve_inline && this._flags.inline_frame) && this.print_newline();
  }, k.prototype.handle_end_block = function(d) {
    for (this.handle_whitespace_and_comments(d); this._flags.mode === p.Statement; )
      this.restore_mode();
    var S = this._flags.last_token.type === l.START_BLOCK;
    this._flags.inline_frame && !S ? this._output.space_before_token = !0 : this._options.brace_style === "expand" ? S || this.print_newline() : S || (x(this._flags.mode) && this._options.keep_array_indentation ? (this._options.keep_array_indentation = !1, this.print_newline(), this._options.keep_array_indentation = !0) : this.print_newline()), this.restore_mode(), this.print_token(d);
  }, k.prototype.handle_word = function(d) {
    if (d.type === l.RESERVED) {
      if (a(d.text, ["set", "get"]) && this._flags.mode !== p.ObjectLiteral)
        d.type = l.WORD;
      else if (d.text === "import" && a(this._tokens.peek().text, ["(", "."]))
        d.type = l.WORD;
      else if (a(d.text, ["as", "from"]) && !this._flags.import_block)
        d.type = l.WORD;
      else if (this._flags.mode === p.ObjectLiteral) {
        var S = this._tokens.peek();
        S.text === ":" && (d.type = l.WORD);
      }
    }
    if (this.start_of_statement(d) ? f(this._flags.last_token, ["var", "let", "const"]) && d.type === l.WORD && (this._flags.declaration_statement = !0) : d.newlines && !B(this._flags.mode) && (this._flags.last_token.type !== l.OPERATOR || this._flags.last_token.text === "--" || this._flags.last_token.text === "++") && this._flags.last_token.type !== l.EQUALS && (this._options.preserve_newlines || !f(this._flags.last_token, ["var", "let", "const", "set", "get"])) ? (this.handle_whitespace_and_comments(d), this.print_newline()) : this.handle_whitespace_and_comments(d), this._flags.do_block && !this._flags.do_while)
      if (c(d, "while")) {
        this._output.space_before_token = !0, this.print_token(d), this._output.space_before_token = !0, this._flags.do_while = !0;
        return;
      } else
        this.print_newline(), this._flags.do_block = !1;
    if (this._flags.if_block)
      if (!this._flags.else_block && c(d, "else"))
        this._flags.else_block = !0;
      else {
        for (; this._flags.mode === p.Statement; )
          this.restore_mode();
        this._flags.if_block = !1, this._flags.else_block = !1;
      }
    if (this._flags.in_case_statement && f(d, ["case", "default"])) {
      this.print_newline(), !this._flags.case_block && (this._flags.case_body || this._options.jslint_happy) && this.deindent(), this._flags.case_body = !1, this.print_token(d), this._flags.in_case = !0;
      return;
    }
    if ((this._flags.last_token.type === l.COMMA || this._flags.last_token.type === l.START_EXPR || this._flags.last_token.type === l.EQUALS || this._flags.last_token.type === l.OPERATOR) && (this.start_of_object_property() || this.allow_wrap_or_preserved_newline(d)), c(d, "function")) {
      (a(this._flags.last_token.text, ["}", ";"]) || this._output.just_added_newline() && !(a(this._flags.last_token.text, ["(", "[", "{", ":", "=", ","]) || this._flags.last_token.type === l.OPERATOR)) && !this._output.just_added_blankline() && !d.comments_before && (this.print_newline(), this.print_newline(!0)), this._flags.last_token.type === l.RESERVED || this._flags.last_token.type === l.WORD ? f(this._flags.last_token, ["get", "set", "new", "export"]) || f(this._flags.last_token, E) ? this._output.space_before_token = !0 : c(this._flags.last_token, "default") && this._last_last_text === "export" ? this._output.space_before_token = !0 : this._flags.last_token.text === "declare" ? this._output.space_before_token = !0 : this.print_newline() : this._flags.last_token.type === l.OPERATOR || this._flags.last_token.text === "=" ? this._output.space_before_token = !0 : !this._flags.multiline_frame && (B(this._flags.mode) || x(this._flags.mode)) || this.print_newline(), this.print_token(d), this._flags.last_word = d.text;
      return;
    }
    var T = "NONE";
    if (this._flags.last_token.type === l.END_BLOCK ? this._previous_flags.inline_frame ? T = "SPACE" : f(d, ["else", "catch", "finally", "from"]) ? this._options.brace_style === "expand" || this._options.brace_style === "end-expand" || this._options.brace_style === "none" && d.newlines ? T = "NEWLINE" : (T = "SPACE", this._output.space_before_token = !0) : T = "NEWLINE" : this._flags.last_token.type === l.SEMICOLON && this._flags.mode === p.BlockStatement ? T = "NEWLINE" : this._flags.last_token.type === l.SEMICOLON && B(this._flags.mode) ? T = "SPACE" : this._flags.last_token.type === l.STRING ? T = "NEWLINE" : this._flags.last_token.type === l.RESERVED || this._flags.last_token.type === l.WORD || this._flags.last_token.text === "*" && (a(this._last_last_text, ["function", "yield"]) || this._flags.mode === p.ObjectLiteral && a(this._last_last_text, ["{", ","])) ? T = "SPACE" : this._flags.last_token.type === l.START_BLOCK ? this._flags.inline_frame ? T = "SPACE" : T = "NEWLINE" : this._flags.last_token.type === l.END_EXPR && (this._output.space_before_token = !0, T = "NEWLINE"), f(d, r) && this._flags.last_token.text !== ")" && (this._flags.inline_frame || this._flags.last_token.text === "else" || this._flags.last_token.text === "export" ? T = "SPACE" : T = "NEWLINE"), f(d, ["else", "catch", "finally"]))
      if ((!(this._flags.last_token.type === l.END_BLOCK && this._previous_flags.mode === p.BlockStatement) || this._options.brace_style === "expand" || this._options.brace_style === "end-expand" || this._options.brace_style === "none" && d.newlines) && !this._flags.inline_frame)
        this.print_newline();
      else {
        this._output.trim(!0);
        var $ = this._output.current_line;
        $.last() !== "}" && this.print_newline(), this._output.space_before_token = !0;
      }
    else
      T === "NEWLINE" ? f(this._flags.last_token, g) ? this._output.space_before_token = !0 : this._flags.last_token.text === "declare" && f(d, ["var", "let", "const"]) ? this._output.space_before_token = !0 : this._flags.last_token.type !== l.END_EXPR ? (this._flags.last_token.type !== l.START_EXPR || !f(d, ["var", "let", "const"])) && this._flags.last_token.text !== ":" && (c(d, "if") && c(d.previous, "else") ? this._output.space_before_token = !0 : this.print_newline()) : f(d, r) && this._flags.last_token.text !== ")" && this.print_newline() : this._flags.multiline_frame && x(this._flags.mode) && this._flags.last_token.text === "," && this._last_last_text === "}" ? this.print_newline() : T === "SPACE" && (this._output.space_before_token = !0);
    d.previous && (d.previous.type === l.WORD || d.previous.type === l.RESERVED) && (this._output.space_before_token = !0), this.print_token(d), this._flags.last_word = d.text, d.type === l.RESERVED && (d.text === "do" ? this._flags.do_block = !0 : d.text === "if" ? this._flags.if_block = !0 : d.text === "import" ? this._flags.import_block = !0 : this._flags.import_block && c(d, "from") && (this._flags.import_block = !1));
  }, k.prototype.handle_semicolon = function(d) {
    this.start_of_statement(d) ? this._output.space_before_token = !1 : this.handle_whitespace_and_comments(d);
    for (var S = this._tokens.peek(); this._flags.mode === p.Statement && !(this._flags.if_block && c(S, "else")) && !this._flags.do_block; )
      this.restore_mode();
    this._flags.import_block && (this._flags.import_block = !1), this.print_token(d);
  }, k.prototype.handle_string = function(d) {
    d.text.startsWith("`") && d.newlines === 0 && d.whitespace_before === "" && (d.previous.text === ")" || this._flags.last_token.type === l.WORD) || (this.start_of_statement(d) ? this._output.space_before_token = !0 : (this.handle_whitespace_and_comments(d), this._flags.last_token.type === l.RESERVED || this._flags.last_token.type === l.WORD || this._flags.inline_frame ? this._output.space_before_token = !0 : this._flags.last_token.type === l.COMMA || this._flags.last_token.type === l.START_EXPR || this._flags.last_token.type === l.EQUALS || this._flags.last_token.type === l.OPERATOR ? this.start_of_object_property() || this.allow_wrap_or_preserved_newline(d) : d.text.startsWith("`") && this._flags.last_token.type === l.END_EXPR && (d.previous.text === "]" || d.previous.text === ")") && d.newlines === 0 ? this._output.space_before_token = !0 : this.print_newline())), this.print_token(d);
  }, k.prototype.handle_equals = function(d) {
    this.start_of_statement(d) || this.handle_whitespace_and_comments(d), this._flags.declaration_statement && (this._flags.declaration_assignment = !0), this._output.space_before_token = !0, this.print_token(d), this._output.space_before_token = !0;
  }, k.prototype.handle_comma = function(d) {
    this.handle_whitespace_and_comments(d, !0), this.print_token(d), this._output.space_before_token = !0, this._flags.declaration_statement ? (B(this._flags.parent.mode) && (this._flags.declaration_assignment = !1), this._flags.declaration_assignment ? (this._flags.declaration_assignment = !1, this.print_newline(!1, !0)) : this._options.comma_first && this.allow_wrap_or_preserved_newline(d)) : this._flags.mode === p.ObjectLiteral || this._flags.mode === p.Statement && this._flags.parent.mode === p.ObjectLiteral ? (this._flags.mode === p.Statement && this.restore_mode(), this._flags.inline_frame || this.print_newline()) : this._options.comma_first && this.allow_wrap_or_preserved_newline(d);
  }, k.prototype.handle_operator = function(d) {
    var S = d.text === "*" && (f(this._flags.last_token, ["function", "yield"]) || a(this._flags.last_token.type, [l.START_BLOCK, l.COMMA, l.END_BLOCK, l.SEMICOLON])), T = a(d.text, ["-", "+"]) && (a(this._flags.last_token.type, [l.START_BLOCK, l.START_EXPR, l.EQUALS, l.OPERATOR]) || a(this._flags.last_token.text, r) || this._flags.last_token.text === ",");
    if (!this.start_of_statement(d)) {
      var $ = !S;
      this.handle_whitespace_and_comments(d, $);
    }
    if (d.text === "*" && this._flags.last_token.type === l.DOT) {
      this.print_token(d);
      return;
    }
    if (d.text === "::") {
      this.print_token(d);
      return;
    }
    if (this._flags.last_token.type === l.OPERATOR && a(this._options.operator_position, m) && this.allow_wrap_or_preserved_newline(d), d.text === ":" && this._flags.in_case) {
      this.print_token(d), this._flags.in_case = !1, this._flags.case_body = !0, this._tokens.peek().type !== l.START_BLOCK ? (this.indent(), this.print_newline(), this._flags.case_block = !1) : (this._flags.case_block = !0, this._output.space_before_token = !0);
      return;
    }
    var K = !0, it = !0, Y = !1;
    if (d.text === ":" ? this._flags.ternary_depth === 0 ? K = !1 : (this._flags.ternary_depth -= 1, Y = !0) : d.text === "?" && (this._flags.ternary_depth += 1), !T && !S && this._options.preserve_newlines && a(d.text, o)) {
      var et = d.text === ":", Z = et && Y, bt = et && !Y;
      switch (this._options.operator_position) {
        case A.before_newline:
          this._output.space_before_token = !bt, this.print_token(d), (!et || Z) && this.allow_wrap_or_preserved_newline(d), this._output.space_before_token = !0;
          return;
        case A.after_newline:
          this._output.space_before_token = !0, !et || Z ? this._tokens.peek().newlines ? this.print_newline(!1, !0) : this.allow_wrap_or_preserved_newline(d) : this._output.space_before_token = !1, this.print_token(d), this._output.space_before_token = !0;
          return;
        case A.preserve_newline:
          bt || this.allow_wrap_or_preserved_newline(d), K = !(this._output.just_added_newline() || bt), this._output.space_before_token = K, this.print_token(d), this._output.space_before_token = !0;
          return;
      }
    }
    if (S) {
      this.allow_wrap_or_preserved_newline(d), K = !1;
      var Ut = this._tokens.peek();
      it = Ut && a(Ut.type, [l.WORD, l.RESERVED]);
    } else if (d.text === "...")
      this.allow_wrap_or_preserved_newline(d), K = this._flags.last_token.type === l.START_BLOCK, it = !1;
    else if (a(d.text, ["--", "++", "!", "~"]) || T) {
      if ((this._flags.last_token.type === l.COMMA || this._flags.last_token.type === l.START_EXPR) && this.allow_wrap_or_preserved_newline(d), K = !1, it = !1, d.newlines && (d.text === "--" || d.text === "++" || d.text === "~")) {
        var jt = f(this._flags.last_token, g) && d.newlines;
        jt && (this._previous_flags.if_block || this._previous_flags.else_block) && this.restore_mode(), this.print_newline(jt, !0);
      }
      this._flags.last_token.text === ";" && B(this._flags.mode) && (K = !0), this._flags.last_token.type === l.RESERVED ? K = !0 : this._flags.last_token.type === l.END_EXPR ? K = !(this._flags.last_token.text === "]" && (d.text === "--" || d.text === "++")) : this._flags.last_token.type === l.OPERATOR && (K = a(d.text, ["--", "-", "++", "+"]) && a(this._flags.last_token.text, ["--", "-", "++", "+"]), a(d.text, ["+", "-"]) && a(this._flags.last_token.text, ["--", "++"]) && (it = !0)), (this._flags.mode === p.BlockStatement && !this._flags.inline_frame || this._flags.mode === p.Statement) && (this._flags.last_token.text === "{" || this._flags.last_token.text === ";") && this.print_newline();
    }
    this._output.space_before_token = this._output.space_before_token || K, this.print_token(d), this._output.space_before_token = it;
  }, k.prototype.handle_block_comment = function(d, S) {
    if (this._output.raw) {
      this._output.add_raw_token(d), d.directives && d.directives.preserve === "end" && (this._output.raw = this._options.test_output_raw);
      return;
    }
    if (d.directives) {
      this.print_newline(!1, S), this.print_token(d), d.directives.preserve === "start" && (this._output.raw = !0), this.print_newline(!1, !0);
      return;
    }
    if (!e.newline.test(d.text) && !d.newlines) {
      this._output.space_before_token = !0, this.print_token(d), this._output.space_before_token = !0;
      return;
    } else
      this.print_block_commment(d, S);
  }, k.prototype.print_block_commment = function(d, S) {
    var T = M(d.text), $, K = !1, it = !1, Y = d.whitespace_before, et = Y.length;
    if (this.print_newline(!1, S), this.print_token_line_indentation(d), this._output.add_token(T[0]), this.print_newline(!1, S), T.length > 1) {
      for (T = T.slice(1), K = v(T, "*"), it = C(T, Y), K && (this._flags.alignment = 1), $ = 0; $ < T.length; $++)
        K ? (this.print_token_line_indentation(d), this._output.add_token(h(T[$]))) : it && T[$] ? (this.print_token_line_indentation(d), this._output.add_token(T[$].substring(et))) : (this._output.current_line.set_indent(-1), this._output.add_token(T[$])), this.print_newline(!1, S);
      this._flags.alignment = 0;
    }
  }, k.prototype.handle_comment = function(d, S) {
    d.newlines ? this.print_newline(!1, S) : this._output.trim(!0), this._output.space_before_token = !0, this.print_token(d), this.print_newline(!1, S);
  }, k.prototype.handle_dot = function(d) {
    this.start_of_statement(d) || this.handle_whitespace_and_comments(d, !0), this._flags.last_token.text.match("^[0-9]+$") && (this._output.space_before_token = !0), f(this._flags.last_token, g) ? this._output.space_before_token = !1 : this.allow_wrap_or_preserved_newline(
      d,
      this._flags.last_token.text === ")" && this._options.break_chained_methods
    ), this._options.unindent_chained_methods && this._output.just_added_newline() && this.deindent(), this.print_token(d);
  }, k.prototype.handle_unknown = function(d, S) {
    this.print_token(d), d.text[d.text.length - 1] === `
` && this.print_newline(!1, S);
  }, k.prototype.handle_eof = function(d) {
    for (; this._flags.mode === p.Statement; )
      this.restore_mode();
    this.handle_whitespace_and_comments(d);
  }, bl.Beautifier = k, bl;
}
var Bf;
function _S() {
  if (Bf)
    return tr.exports;
  Bf = 1;
  var i = mS().Beautifier, t = Fm().Options;
  function e(s, n) {
    var r = new i(s, n);
    return r.beautify();
  }
  return tr.exports = e, tr.exports.defaultOptions = function() {
    return new t();
  }, tr.exports;
}
var sr = { exports: {} }, Pl = {}, El = {}, Nf;
function Um() {
  if (Nf)
    return El;
  Nf = 1;
  var i = Lh().Options;
  function t(e) {
    i.call(this, e, "css"), this.selector_separator_newline = this._get_boolean("selector_separator_newline", !0), this.newline_between_rules = this._get_boolean("newline_between_rules", !0);
    var s = this._get_boolean("space_around_selector_separator");
    this.space_around_combinator = this._get_boolean("space_around_combinator") || s;
    var n = this._get_selection_list("brace_style", ["collapse", "expand", "end-expand", "none", "preserve-inline"]);
    this.brace_style = "collapse";
    for (var r = 0; r < n.length; r++)
      n[r] !== "expand" ? this.brace_style = "collapse" : this.brace_style = n[r];
  }
  return t.prototype = new i(), El.Options = t, El;
}
var Lf;
function bS() {
  if (Lf)
    return Pl;
  Lf = 1;
  var i = Um().Options, t = Nh().Output, e = Ih().InputScanner, s = Qh().Directives, n = new s(/\/\*/, /\*\//), r = /\r\n|[\r\n]/, o = /\r\n|[\r\n]/g, l = /\s/, a = /(?:\s|\n)+/g, h = /\/\*(?:[\s\S]*?)((?:\*\/)|$)/g, u = /\/\/(?:[^\n\r\u2028\u2029]*)/g;
  function c(f, g) {
    this._source_text = f || "", this._options = new i(g), this._ch = null, this._input = null, this.NESTED_AT_RULE = {
      "@page": !0,
      "@font-face": !0,
      "@keyframes": !0,
      "@media": !0,
      "@supports": !0,
      "@document": !0
    }, this.CONDITIONAL_GROUP_RULE = {
      "@media": !0,
      "@supports": !0,
      "@document": !0
    }, this.NON_SEMICOLON_NEWLINE_PROPERTY = [
      "grid-template-areas",
      "grid-template"
    ];
  }
  return c.prototype.eatString = function(f) {
    var g = "";
    for (this._ch = this._input.next(); this._ch; ) {
      if (g += this._ch, this._ch === "\\")
        g += this._input.next();
      else if (f.indexOf(this._ch) !== -1 || this._ch === `
`)
        break;
      this._ch = this._input.next();
    }
    return g;
  }, c.prototype.eatWhitespace = function(f) {
    for (var g = l.test(this._input.peek()), _ = 0; l.test(this._input.peek()); )
      this._ch = this._input.next(), f && this._ch === `
` && (_ === 0 || _ < this._options.max_preserve_newlines) && (_++, this._output.add_new_line(!0));
    return g;
  }, c.prototype.foundNestedPseudoClass = function() {
    for (var f = 0, g = 1, _ = this._input.peek(g); _; ) {
      if (_ === "{")
        return !0;
      if (_ === "(")
        f += 1;
      else if (_ === ")") {
        if (f === 0)
          return !1;
        f -= 1;
      } else if (_ === ";" || _ === "}")
        return !1;
      g++, _ = this._input.peek(g);
    }
    return !1;
  }, c.prototype.print_string = function(f) {
    this._output.set_indent(this._indentLevel), this._output.non_breaking_space = !0, this._output.add_token(f);
  }, c.prototype.preserveSingleSpace = function(f) {
    f && (this._output.space_before_token = !0);
  }, c.prototype.indent = function() {
    this._indentLevel++;
  }, c.prototype.outdent = function() {
    this._indentLevel > 0 && this._indentLevel--;
  }, c.prototype.beautify = function() {
    if (this._options.disabled)
      return this._source_text;
    var f = this._source_text, g = this._options.eol;
    g === "auto" && (g = `
`, f && r.test(f || "") && (g = f.match(r)[0])), f = f.replace(o, `
`);
    var _ = f.match(/^[\t ]*/)[0];
    this._output = new t(this._options, _), this._input = new e(f), this._indentLevel = 0, this._nestedLevel = 0, this._ch = null;
    for (var A = 0, m = !1, p = !1, y = !1, M = !1, x = !1, B = !1, v = this._ch, C = !1, k, E, d; k = this._input.read(a), E = k !== "", d = v, this._ch = this._input.next(), this._ch === "\\" && this._input.hasNext() && (this._ch += this._input.next()), v = this._ch, this._ch; )
      if (this._ch === "/" && this._input.peek() === "*") {
        this._output.add_new_line(), this._input.back();
        var S = this._input.read(h), T = n.get_directives(S);
        T && T.ignore === "start" && (S += n.readIgnored(this._input)), this.print_string(S), this.eatWhitespace(!0), this._output.add_new_line();
      } else if (this._ch === "/" && this._input.peek() === "/")
        this._output.space_before_token = !0, this._input.back(), this.print_string(this._input.read(u)), this.eatWhitespace(!0);
      else if (this._ch === "@" || this._ch === "$")
        if (this.preserveSingleSpace(E), this._input.peek() === "{")
          this.print_string(this._ch + this.eatString("}"));
        else {
          this.print_string(this._ch);
          var $ = this._input.peekUntilAfter(/[: ,;{}()[\]\/='"]/g);
          $.match(/[ :]$/) && ($ = this.eatString(": ").replace(/\s$/, ""), this.print_string($), this._output.space_before_token = !0), $ = $.replace(/\s$/, ""), $ === "extend" ? M = !0 : $ === "import" && (x = !0), $ in this.NESTED_AT_RULE ? (this._nestedLevel += 1, $ in this.CONDITIONAL_GROUP_RULE && (y = !0)) : !m && A === 0 && $.indexOf(":") !== -1 && (p = !0, this.indent());
        }
      else if (this._ch === "#" && this._input.peek() === "{")
        this.preserveSingleSpace(E), this.print_string(this._ch + this.eatString("}"));
      else if (this._ch === "{")
        p && (p = !1, this.outdent()), y ? (y = !1, m = this._indentLevel >= this._nestedLevel) : m = this._indentLevel >= this._nestedLevel - 1, this._options.newline_between_rules && m && this._output.previous_line && this._output.previous_line.item(-1) !== "{" && this._output.ensure_empty_line_above("/", ","), this._output.space_before_token = !0, this._options.brace_style === "expand" ? (this._output.add_new_line(), this.print_string(this._ch), this.indent(), this._output.set_indent(this._indentLevel)) : (d === "(" ? this._output.space_before_token = !1 : d !== "," && this.indent(), this.print_string(this._ch)), this.eatWhitespace(!0), this._output.add_new_line();
      else if (this._ch === "}")
        this.outdent(), this._output.add_new_line(), d === "{" && this._output.trim(!0), x = !1, M = !1, p && (this.outdent(), p = !1), this.print_string(this._ch), m = !1, this._nestedLevel && this._nestedLevel--, this.eatWhitespace(!0), this._output.add_new_line(), this._options.newline_between_rules && !this._output.just_added_blankline() && this._input.peek() !== "}" && this._output.add_new_line(!0), this._input.peek() === ")" && (this._output.trim(!0), this._options.brace_style === "expand" && this._output.add_new_line(!0));
      else if (this._ch === ":") {
        for (var K = 0; K < this.NON_SEMICOLON_NEWLINE_PROPERTY.length; K++)
          if (this._input.lookBack(this.NON_SEMICOLON_NEWLINE_PROPERTY[K])) {
            C = !0;
            break;
          }
        (m || y) && !(this._input.lookBack("&") || this.foundNestedPseudoClass()) && !this._input.lookBack("(") && !M && A === 0 ? (this.print_string(":"), p || (p = !0, this._output.space_before_token = !0, this.eatWhitespace(!0), this.indent())) : (this._input.lookBack(" ") && (this._output.space_before_token = !0), this._input.peek() === ":" ? (this._ch = this._input.next(), this.print_string("::")) : this.print_string(":"));
      } else if (this._ch === '"' || this._ch === "'") {
        var it = d === '"' || d === "'";
        this.preserveSingleSpace(it || E), this.print_string(this._ch + this.eatString(this._ch)), this.eatWhitespace(!0);
      } else if (this._ch === ";")
        C = !1, A === 0 ? (p && (this.outdent(), p = !1), M = !1, x = !1, this.print_string(this._ch), this.eatWhitespace(!0), this._input.peek() !== "/" && this._output.add_new_line()) : (this.print_string(this._ch), this.eatWhitespace(!0), this._output.space_before_token = !0);
      else if (this._ch === "(")
        if (this._input.lookBack("url"))
          this.print_string(this._ch), this.eatWhitespace(), A++, this.indent(), this._ch = this._input.next(), this._ch === ")" || this._ch === '"' || this._ch === "'" ? this._input.back() : this._ch && (this.print_string(this._ch + this.eatString(")")), A && (A--, this.outdent()));
        else {
          var Y = !1;
          this._input.lookBack("with") && (Y = !0), this.preserveSingleSpace(E || Y), this.print_string(this._ch), p && d === "$" && this._options.selector_separator_newline ? (this._output.add_new_line(), B = !0) : (this.eatWhitespace(), A++, this.indent());
        }
      else if (this._ch === ")")
        A && (A--, this.outdent()), B && this._input.peek() === ";" && this._options.selector_separator_newline && (B = !1, this.outdent(), this._output.add_new_line()), this.print_string(this._ch);
      else if (this._ch === ",")
        this.print_string(this._ch), this.eatWhitespace(!0), this._options.selector_separator_newline && (!p || B) && A === 0 && !x && !M ? this._output.add_new_line() : this._output.space_before_token = !0;
      else if ((this._ch === ">" || this._ch === "+" || this._ch === "~") && !p && A === 0)
        this._options.space_around_combinator ? (this._output.space_before_token = !0, this.print_string(this._ch), this._output.space_before_token = !0) : (this.print_string(this._ch), this.eatWhitespace(), this._ch && l.test(this._ch) && (this._ch = ""));
      else if (this._ch === "]")
        this.print_string(this._ch);
      else if (this._ch === "[")
        this.preserveSingleSpace(E), this.print_string(this._ch);
      else if (this._ch === "=")
        this.eatWhitespace(), this.print_string("="), l.test(this._ch) && (this._ch = "");
      else if (this._ch === "!" && !this._input.lookBack("\\"))
        this._output.space_before_token = !0, this.print_string(this._ch);
      else {
        var et = d === '"' || d === "'";
        this.preserveSingleSpace(et || E), this.print_string(this._ch), !this._output.just_added_newline() && this._input.peek() === `
` && C && this._output.add_new_line();
      }
    var Z = this._output.get_code(g);
    return Z;
  }, Pl.Beautifier = c, Pl;
}
var If;
function yS() {
  if (If)
    return sr.exports;
  If = 1;
  var i = bS().Beautifier, t = Um().Options;
  function e(s, n) {
    var r = new i(s, n);
    return r.beautify();
  }
  return sr.exports = e, sr.exports.defaultOptions = function() {
    return new t();
  }, sr.exports;
}
var nr = { exports: {} }, Ml = {}, Rl = {}, Qf;
function jm() {
  if (Qf)
    return Rl;
  Qf = 1;
  var i = Lh().Options;
  function t(e) {
    i.call(this, e, "html"), this.templating.length === 1 && this.templating[0] === "auto" && (this.templating = ["django", "erb", "handlebars", "php"]), this.indent_inner_html = this._get_boolean("indent_inner_html"), this.indent_body_inner_html = this._get_boolean("indent_body_inner_html", !0), this.indent_head_inner_html = this._get_boolean("indent_head_inner_html", !0), this.indent_handlebars = this._get_boolean("indent_handlebars", !0), this.wrap_attributes = this._get_selection(
      "wrap_attributes",
      ["auto", "force", "force-aligned", "force-expand-multiline", "aligned-multiple", "preserve", "preserve-aligned"]
    ), this.wrap_attributes_indent_size = this._get_number("wrap_attributes_indent_size", this.indent_size), this.extra_liners = this._get_array("extra_liners", ["head", "body", "/html"]), this.inline = this._get_array("inline", [
      "a",
      "abbr",
      "area",
      "audio",
      "b",
      "bdi",
      "bdo",
      "br",
      "button",
      "canvas",
      "cite",
      "code",
      "data",
      "datalist",
      "del",
      "dfn",
      "em",
      "embed",
      "i",
      "iframe",
      "img",
      "input",
      "ins",
      "kbd",
      "keygen",
      "label",
      "map",
      "mark",
      "math",
      "meter",
      "noscript",
      "object",
      "output",
      "progress",
      "q",
      "ruby",
      "s",
      "samp",
      "select",
      "small",
      "span",
      "strong",
      "sub",
      "sup",
      "svg",
      "template",
      "textarea",
      "time",
      "u",
      "var",
      "video",
      "wbr",
      "text",
      "acronym",
      "big",
      "strike",
      "tt"
    ]), this.void_elements = this._get_array("void_elements", [
      "area",
      "base",
      "br",
      "col",
      "embed",
      "hr",
      "img",
      "input",
      "keygen",
      "link",
      "menuitem",
      "meta",
      "param",
      "source",
      "track",
      "wbr",
      "!doctype",
      "?xml",
      "basefont",
      "isindex"
    ]), this.unformatted = this._get_array("unformatted", []), this.content_unformatted = this._get_array("content_unformatted", [
      "pre",
      "textarea"
    ]), this.unformatted_content_delimiter = this._get_characters("unformatted_content_delimiter"), this.indent_scripts = this._get_selection("indent_scripts", ["normal", "keep", "separate"]);
  }
  return t.prototype = new i(), Rl.Options = t, Rl;
}
var rr = {}, zf;
function Wf() {
  if (zf)
    return rr;
  zf = 1;
  var i = so().Tokenizer, t = so().TOKEN, e = Qh().Directives, s = Vm().TemplatablePattern, n = Bo().Pattern, r = {
    TAG_OPEN: "TK_TAG_OPEN",
    TAG_CLOSE: "TK_TAG_CLOSE",
    ATTRIBUTE: "TK_ATTRIBUTE",
    EQUALS: "TK_EQUALS",
    VALUE: "TK_VALUE",
    COMMENT: "TK_COMMENT",
    TEXT: "TK_TEXT",
    UNKNOWN: "TK_UNKNOWN",
    START: t.START,
    RAW: t.RAW,
    EOF: t.EOF
  }, o = new e(/<\!--/, /-->/), l = function(a, h) {
    i.call(this, a, h), this._current_tag_name = "";
    var u = new s(this._input).read_options(this._options), c = new n(this._input);
    if (this.__patterns = {
      word: u.until(/[\n\r\t <]/),
      single_quote: u.until_after(/'/),
      double_quote: u.until_after(/"/),
      attribute: u.until(/[\n\r\t =>]|\/>/),
      element_name: u.until(/[\n\r\t >\/]/),
      handlebars_comment: c.starting_with(/{{!--/).until_after(/--}}/),
      handlebars: c.starting_with(/{{/).until_after(/}}/),
      handlebars_open: c.until(/[\n\r\t }]/),
      handlebars_raw_close: c.until(/}}/),
      comment: c.starting_with(/<!--/).until_after(/-->/),
      cdata: c.starting_with(/<!\[CDATA\[/).until_after(/]]>/),
      conditional_comment: c.starting_with(/<!\[/).until_after(/]>/),
      processing: c.starting_with(/<\?/).until_after(/\?>/)
    }, this._options.indent_handlebars && (this.__patterns.word = this.__patterns.word.exclude("handlebars")), this._unformatted_content_delimiter = null, this._options.unformatted_content_delimiter) {
      var f = this._input.get_literal_regexp(this._options.unformatted_content_delimiter);
      this.__patterns.unformatted_content_delimiter = c.matching(f).until_after(f);
    }
  };
  return l.prototype = new i(), l.prototype._is_comment = function(a) {
    return !1;
  }, l.prototype._is_opening = function(a) {
    return a.type === r.TAG_OPEN;
  }, l.prototype._is_closing = function(a, h) {
    return a.type === r.TAG_CLOSE && h && ((a.text === ">" || a.text === "/>") && h.text[0] === "<" || a.text === "}}" && h.text[0] === "{" && h.text[1] === "{");
  }, l.prototype._reset = function() {
    this._current_tag_name = "";
  }, l.prototype._get_next_token = function(a, h) {
    var u = null;
    this._readWhitespace();
    var c = this._input.peek();
    return c === null ? this._create_token(r.EOF, "") : (u = u || this._read_open_handlebars(c, h), u = u || this._read_attribute(c, a, h), u = u || this._read_close(c, h), u = u || this._read_raw_content(c, a, h), u = u || this._read_content_word(c), u = u || this._read_comment_or_cdata(c), u = u || this._read_processing(c), u = u || this._read_open(c, h), u = u || this._create_token(r.UNKNOWN, this._input.next()), u);
  }, l.prototype._read_comment_or_cdata = function(a) {
    var h = null, u = null, c = null;
    if (a === "<") {
      var f = this._input.peek(1);
      f === "!" && (u = this.__patterns.comment.read(), u ? (c = o.get_directives(u), c && c.ignore === "start" && (u += o.readIgnored(this._input))) : u = this.__patterns.cdata.read()), u && (h = this._create_token(r.COMMENT, u), h.directives = c);
    }
    return h;
  }, l.prototype._read_processing = function(a) {
    var h = null, u = null, c = null;
    if (a === "<") {
      var f = this._input.peek(1);
      (f === "!" || f === "?") && (u = this.__patterns.conditional_comment.read(), u = u || this.__patterns.processing.read()), u && (h = this._create_token(r.COMMENT, u), h.directives = c);
    }
    return h;
  }, l.prototype._read_open = function(a, h) {
    var u = null, c = null;
    return h || a === "<" && (u = this._input.next(), this._input.peek() === "/" && (u += this._input.next()), u += this.__patterns.element_name.read(), c = this._create_token(r.TAG_OPEN, u)), c;
  }, l.prototype._read_open_handlebars = function(a, h) {
    var u = null, c = null;
    return h || this._options.indent_handlebars && a === "{" && this._input.peek(1) === "{" && (this._input.peek(2) === "!" ? (u = this.__patterns.handlebars_comment.read(), u = u || this.__patterns.handlebars.read(), c = this._create_token(r.COMMENT, u)) : (u = this.__patterns.handlebars_open.read(), c = this._create_token(r.TAG_OPEN, u))), c;
  }, l.prototype._read_close = function(a, h) {
    var u = null, c = null;
    return h && (h.text[0] === "<" && (a === ">" || a === "/" && this._input.peek(1) === ">") ? (u = this._input.next(), a === "/" && (u += this._input.next()), c = this._create_token(r.TAG_CLOSE, u)) : h.text[0] === "{" && a === "}" && this._input.peek(1) === "}" && (this._input.next(), this._input.next(), c = this._create_token(r.TAG_CLOSE, "}}"))), c;
  }, l.prototype._read_attribute = function(a, h, u) {
    var c = null, f = "";
    if (u && u.text[0] === "<")
      if (a === "=")
        c = this._create_token(r.EQUALS, this._input.next());
      else if (a === '"' || a === "'") {
        var g = this._input.next();
        a === '"' ? g += this.__patterns.double_quote.read() : g += this.__patterns.single_quote.read(), c = this._create_token(r.VALUE, g);
      } else
        f = this.__patterns.attribute.read(), f && (h.type === r.EQUALS ? c = this._create_token(r.VALUE, f) : c = this._create_token(r.ATTRIBUTE, f));
    return c;
  }, l.prototype._is_content_unformatted = function(a) {
    return this._options.void_elements.indexOf(a) === -1 && (this._options.content_unformatted.indexOf(a) !== -1 || this._options.unformatted.indexOf(a) !== -1);
  }, l.prototype._read_raw_content = function(a, h, u) {
    var c = "";
    if (u && u.text[0] === "{")
      c = this.__patterns.handlebars_raw_close.read();
    else if (h.type === r.TAG_CLOSE && h.opened.text[0] === "<" && h.text[0] !== "/") {
      var f = h.opened.text.substr(1).toLowerCase();
      if (f === "script" || f === "style") {
        var g = this._read_comment_or_cdata(a);
        if (g)
          return g.type = r.TEXT, g;
        c = this._input.readUntil(new RegExp("</" + f + "[\\n\\r\\t ]*?>", "ig"));
      } else
        this._is_content_unformatted(f) && (c = this._input.readUntil(new RegExp("</" + f + "[\\n\\r\\t ]*?>", "ig")));
    }
    return c ? this._create_token(r.TEXT, c) : null;
  }, l.prototype._read_content_word = function(a) {
    var h = "";
    if (this._options.unformatted_content_delimiter && a === this._options.unformatted_content_delimiter[0] && (h = this.__patterns.unformatted_content_delimiter.read()), h || (h = this.__patterns.word.read()), h)
      return this._create_token(r.TEXT, h);
  }, rr.Tokenizer = l, rr.TOKEN = r, rr;
}
var $f;
function wS() {
  if ($f)
    return Ml;
  $f = 1;
  var i = jm().Options, t = Nh().Output, e = Wf().Tokenizer, s = Wf().TOKEN, n = /\r\n|[\r\n]/, r = /\r\n|[\r\n]/g, o = function(m, p) {
    this.indent_level = 0, this.alignment_size = 0, this.max_preserve_newlines = m.max_preserve_newlines, this.preserve_newlines = m.preserve_newlines, this._output = new t(m, p);
  };
  o.prototype.current_line_has_match = function(m) {
    return this._output.current_line.has_match(m);
  }, o.prototype.set_space_before_token = function(m, p) {
    this._output.space_before_token = m, this._output.non_breaking_space = p;
  }, o.prototype.set_wrap_point = function() {
    this._output.set_indent(this.indent_level, this.alignment_size), this._output.set_wrap_point();
  }, o.prototype.add_raw_token = function(m) {
    this._output.add_raw_token(m);
  }, o.prototype.print_preserved_newlines = function(m) {
    var p = 0;
    m.type !== s.TEXT && m.previous.type !== s.TEXT && (p = m.newlines ? 1 : 0), this.preserve_newlines && (p = m.newlines < this.max_preserve_newlines + 1 ? m.newlines : this.max_preserve_newlines + 1);
    for (var y = 0; y < p; y++)
      this.print_newline(y > 0);
    return p !== 0;
  }, o.prototype.traverse_whitespace = function(m) {
    return m.whitespace_before || m.newlines ? (this.print_preserved_newlines(m) || (this._output.space_before_token = !0), !0) : !1;
  }, o.prototype.previous_token_wrapped = function() {
    return this._output.previous_token_wrapped;
  }, o.prototype.print_newline = function(m) {
    this._output.add_new_line(m);
  }, o.prototype.print_token = function(m) {
    m.text && (this._output.set_indent(this.indent_level, this.alignment_size), this._output.add_token(m.text));
  }, o.prototype.indent = function() {
    this.indent_level++;
  }, o.prototype.get_full_indent = function(m) {
    return m = this.indent_level + (m || 0), m < 1 ? "" : this._output.get_indent_string(m);
  };
  var l = function(m) {
    for (var p = null, y = m.next; y.type !== s.EOF && m.closed !== y; ) {
      if (y.type === s.ATTRIBUTE && y.text === "type") {
        y.next && y.next.type === s.EQUALS && y.next.next && y.next.next.type === s.VALUE && (p = y.next.next.text);
        break;
      }
      y = y.next;
    }
    return p;
  }, a = function(m, p) {
    var y = null, M = null;
    return p.closed ? (m === "script" ? y = "text/javascript" : m === "style" && (y = "text/css"), y = l(p) || y, y.search("text/css") > -1 ? M = "css" : y.search(/module|((text|application|dojo)\/(x-)?(javascript|ecmascript|jscript|livescript|(ld\+)?json|method|aspect))/) > -1 ? M = "javascript" : y.search(/(text|application|dojo)\/(x-)?(html)/) > -1 ? M = "html" : y.search(/test\/null/) > -1 && (M = "null"), M) : null;
  };
  function h(m, p) {
    return p.indexOf(m) !== -1;
  }
  function u(m, p, y) {
    this.parent = m || null, this.tag = p ? p.tag_name : "", this.indent_level = y || 0, this.parser_token = p || null;
  }
  function c(m) {
    this._printer = m, this._current_frame = null;
  }
  c.prototype.get_parser_token = function() {
    return this._current_frame ? this._current_frame.parser_token : null;
  }, c.prototype.record_tag = function(m) {
    var p = new u(this._current_frame, m, this._printer.indent_level);
    this._current_frame = p;
  }, c.prototype._try_pop_frame = function(m) {
    var p = null;
    return m && (p = m.parser_token, this._printer.indent_level = m.indent_level, this._current_frame = m.parent), p;
  }, c.prototype._get_frame = function(m, p) {
    for (var y = this._current_frame; y && m.indexOf(y.tag) === -1; ) {
      if (p && p.indexOf(y.tag) !== -1) {
        y = null;
        break;
      }
      y = y.parent;
    }
    return y;
  }, c.prototype.try_pop = function(m, p) {
    var y = this._get_frame([m], p);
    return this._try_pop_frame(y);
  }, c.prototype.indent_to_tag = function(m) {
    var p = this._get_frame(m);
    p && (this._printer.indent_level = p.indent_level);
  };
  function f(m, p, y, M) {
    this._source_text = m || "", p = p || {}, this._js_beautify = y, this._css_beautify = M, this._tag_stack = null;
    var x = new i(p, "html");
    this._options = x, this._is_wrap_attributes_force = this._options.wrap_attributes.substr(0, 5) === "force", this._is_wrap_attributes_force_expand_multiline = this._options.wrap_attributes === "force-expand-multiline", this._is_wrap_attributes_force_aligned = this._options.wrap_attributes === "force-aligned", this._is_wrap_attributes_aligned_multiple = this._options.wrap_attributes === "aligned-multiple", this._is_wrap_attributes_preserve = this._options.wrap_attributes.substr(0, 8) === "preserve", this._is_wrap_attributes_preserve_aligned = this._options.wrap_attributes === "preserve-aligned";
  }
  f.prototype.beautify = function() {
    if (this._options.disabled)
      return this._source_text;
    var m = this._source_text, p = this._options.eol;
    this._options.eol === "auto" && (p = `
`, m && n.test(m) && (p = m.match(n)[0])), m = m.replace(r, `
`);
    var y = m.match(/^[\t ]*/)[0], M = {
      text: "",
      type: ""
    }, x = new g(), B = new o(this._options, y), v = new e(m, this._options).tokenize();
    this._tag_stack = new c(B);
    for (var C = null, k = v.next(); k.type !== s.EOF; )
      k.type === s.TAG_OPEN || k.type === s.COMMENT ? (C = this._handle_tag_open(B, k, x, M), x = C) : k.type === s.ATTRIBUTE || k.type === s.EQUALS || k.type === s.VALUE || k.type === s.TEXT && !x.tag_complete ? C = this._handle_inside_tag(B, k, x, v) : k.type === s.TAG_CLOSE ? C = this._handle_tag_close(B, k, x) : k.type === s.TEXT ? C = this._handle_text(B, k, x) : B.add_raw_token(k), M = C, k = v.next();
    var E = B._output.get_code(p);
    return E;
  }, f.prototype._handle_tag_close = function(m, p, y) {
    var M = {
      text: p.text,
      type: p.type
    };
    return m.alignment_size = 0, y.tag_complete = !0, m.set_space_before_token(p.newlines || p.whitespace_before !== "", !0), y.is_unformatted ? m.add_raw_token(p) : (y.tag_start_char === "<" && (m.set_space_before_token(p.text[0] === "/", !0), this._is_wrap_attributes_force_expand_multiline && y.has_wrapped_attrs && m.print_newline(!1)), m.print_token(p)), y.indent_content && !(y.is_unformatted || y.is_content_unformatted) && (m.indent(), y.indent_content = !1), !y.is_inline_element && !(y.is_unformatted || y.is_content_unformatted) && m.set_wrap_point(), M;
  }, f.prototype._handle_inside_tag = function(m, p, y, M) {
    var x = y.has_wrapped_attrs, B = {
      text: p.text,
      type: p.type
    };
    if (m.set_space_before_token(p.newlines || p.whitespace_before !== "", !0), y.is_unformatted)
      m.add_raw_token(p);
    else if (y.tag_start_char === "{" && p.type === s.TEXT)
      m.print_preserved_newlines(p) ? (p.newlines = 0, m.add_raw_token(p)) : m.print_token(p);
    else {
      if (p.type === s.ATTRIBUTE ? (m.set_space_before_token(!0), y.attr_count += 1) : (p.type === s.EQUALS || p.type === s.VALUE && p.previous.type === s.EQUALS) && m.set_space_before_token(!1), p.type === s.ATTRIBUTE && y.tag_start_char === "<" && ((this._is_wrap_attributes_preserve || this._is_wrap_attributes_preserve_aligned) && (m.traverse_whitespace(p), x = x || p.newlines !== 0), this._is_wrap_attributes_force)) {
        var v = y.attr_count > 1;
        if (this._is_wrap_attributes_force_expand_multiline && y.attr_count === 1) {
          var C = !0, k = 0, E;
          do {
            if (E = M.peek(k), E.type === s.ATTRIBUTE) {
              C = !1;
              break;
            }
            k += 1;
          } while (k < 4 && E.type !== s.EOF && E.type !== s.TAG_CLOSE);
          v = !C;
        }
        v && (m.print_newline(!1), x = !0);
      }
      m.print_token(p), x = x || m.previous_token_wrapped(), y.has_wrapped_attrs = x;
    }
    return B;
  }, f.prototype._handle_text = function(m, p, y) {
    var M = {
      text: p.text,
      type: "TK_CONTENT"
    };
    return y.custom_beautifier_name ? this._print_custom_beatifier_text(m, p, y) : y.is_unformatted || y.is_content_unformatted ? m.add_raw_token(p) : (m.traverse_whitespace(p), m.print_token(p)), M;
  }, f.prototype._print_custom_beatifier_text = function(m, p, y) {
    var M = this;
    if (p.text !== "") {
      var x = p.text, B, v = 1, C = "", k = "";
      y.custom_beautifier_name === "javascript" && typeof this._js_beautify == "function" ? B = this._js_beautify : y.custom_beautifier_name === "css" && typeof this._css_beautify == "function" ? B = this._css_beautify : y.custom_beautifier_name === "html" && (B = function(K, it) {
        var Y = new f(K, it, M._js_beautify, M._css_beautify);
        return Y.beautify();
      }), this._options.indent_scripts === "keep" ? v = 0 : this._options.indent_scripts === "separate" && (v = -m.indent_level);
      var E = m.get_full_indent(v);
      if (x = x.replace(/\n[ \t]*$/, ""), y.custom_beautifier_name !== "html" && x[0] === "<" && x.match(/^(<!--|<!\[CDATA\[)/)) {
        var d = /^(<!--[^\n]*|<!\[CDATA\[)(\n?)([ \t\n]*)([\s\S]*)(-->|]]>)$/.exec(x);
        if (!d) {
          m.add_raw_token(p);
          return;
        }
        C = E + d[1] + `
`, x = d[4], d[5] && (k = E + d[5]), x = x.replace(/\n[ \t]*$/, ""), (d[2] || d[3].indexOf(`
`) !== -1) && (d = d[3].match(/[ \t]+$/), d && (p.whitespace_before = d[0]));
      }
      if (x)
        if (B) {
          var S = function() {
            this.eol = `
`;
          };
          S.prototype = this._options.raw_options;
          var T = new S();
          x = B(E + x, T);
        } else {
          var $ = p.whitespace_before;
          $ && (x = x.replace(new RegExp(`
(` + $ + ")?", "g"), `
`)), x = E + x.replace(/\n/g, `
` + E);
        }
      C && (x ? x = C + x + `
` + k : x = C + k), m.print_newline(!1), x && (p.text = x, p.whitespace_before = "", p.newlines = 0, m.add_raw_token(p), m.print_newline(!0));
    }
  }, f.prototype._handle_tag_open = function(m, p, y, M) {
    var x = this._get_tag_open_token(p);
    return (y.is_unformatted || y.is_content_unformatted) && !y.is_empty_element && p.type === s.TAG_OPEN && p.text.indexOf("</") === 0 ? (m.add_raw_token(p), x.start_tag_token = this._tag_stack.try_pop(x.tag_name)) : (m.traverse_whitespace(p), this._set_tag_position(m, p, x, y, M), x.is_inline_element || m.set_wrap_point(), m.print_token(p)), (this._is_wrap_attributes_force_aligned || this._is_wrap_attributes_aligned_multiple || this._is_wrap_attributes_preserve_aligned) && (x.alignment_size = p.text.length + 1), !x.tag_complete && !x.is_unformatted && (m.alignment_size = x.alignment_size), x;
  };
  var g = function(m, p) {
    if (this.parent = m || null, this.text = "", this.type = "TK_TAG_OPEN", this.tag_name = "", this.is_inline_element = !1, this.is_unformatted = !1, this.is_content_unformatted = !1, this.is_empty_element = !1, this.is_start_tag = !1, this.is_end_tag = !1, this.indent_content = !1, this.multiline_content = !1, this.custom_beautifier_name = null, this.start_tag_token = null, this.attr_count = 0, this.has_wrapped_attrs = !1, this.alignment_size = 0, this.tag_complete = !1, this.tag_start_char = "", this.tag_check = "", !p)
      this.tag_complete = !0;
    else {
      var y;
      this.tag_start_char = p.text[0], this.text = p.text, this.tag_start_char === "<" ? (y = p.text.match(/^<([^\s>]*)/), this.tag_check = y ? y[1] : "") : (y = p.text.match(/^{{~?(?:[\^]|#\*?)?([^\s}]+)/), this.tag_check = y ? y[1] : "", (p.text.startsWith("{{#>") || p.text.startsWith("{{~#>")) && this.tag_check[0] === ">" && (this.tag_check === ">" && p.next !== null ? this.tag_check = p.next.text.split(" ")[0] : this.tag_check = p.text.split(">")[1])), this.tag_check = this.tag_check.toLowerCase(), p.type === s.COMMENT && (this.tag_complete = !0), this.is_start_tag = this.tag_check.charAt(0) !== "/", this.tag_name = this.is_start_tag ? this.tag_check : this.tag_check.substr(1), this.is_end_tag = !this.is_start_tag || p.closed && p.closed.text === "/>";
      var M = 2;
      this.tag_start_char === "{" && this.text.length >= 3 && this.text.charAt(2) === "~" && (M = 3), this.is_end_tag = this.is_end_tag || this.tag_start_char === "{" && (this.text.length < 3 || /[^#\^]/.test(this.text.charAt(M)));
    }
  };
  f.prototype._get_tag_open_token = function(m) {
    var p = new g(this._tag_stack.get_parser_token(), m);
    return p.alignment_size = this._options.wrap_attributes_indent_size, p.is_end_tag = p.is_end_tag || h(p.tag_check, this._options.void_elements), p.is_empty_element = p.tag_complete || p.is_start_tag && p.is_end_tag, p.is_unformatted = !p.tag_complete && h(p.tag_check, this._options.unformatted), p.is_content_unformatted = !p.is_empty_element && h(p.tag_check, this._options.content_unformatted), p.is_inline_element = h(p.tag_name, this._options.inline) || p.tag_start_char === "{", p;
  }, f.prototype._set_tag_position = function(m, p, y, M, x) {
    if (y.is_empty_element || (y.is_end_tag ? y.start_tag_token = this._tag_stack.try_pop(y.tag_name) : (this._do_optional_end_element(y) && (y.is_inline_element || m.print_newline(!1)), this._tag_stack.record_tag(y), (y.tag_name === "script" || y.tag_name === "style") && !(y.is_unformatted || y.is_content_unformatted) && (y.custom_beautifier_name = a(y.tag_check, p)))), h(y.tag_check, this._options.extra_liners) && (m.print_newline(!1), m._output.just_added_blankline() || m.print_newline(!0)), y.is_empty_element) {
      if (y.tag_start_char === "{" && y.tag_check === "else") {
        this._tag_stack.indent_to_tag(["if", "unless", "each"]), y.indent_content = !0;
        var B = m.current_line_has_match(/{{#if/);
        B || m.print_newline(!1);
      }
      y.tag_name === "!--" && x.type === s.TAG_CLOSE && M.is_end_tag && y.text.indexOf(`
`) === -1 || (y.is_inline_element || y.is_unformatted || m.print_newline(!1), this._calcluate_parent_multiline(m, y));
    } else if (y.is_end_tag) {
      var v = !1;
      v = y.start_tag_token && y.start_tag_token.multiline_content, v = v || !y.is_inline_element && !(M.is_inline_element || M.is_unformatted) && !(x.type === s.TAG_CLOSE && y.start_tag_token === M) && x.type !== "TK_CONTENT", (y.is_content_unformatted || y.is_unformatted) && (v = !1), v && m.print_newline(!1);
    } else
      y.indent_content = !y.custom_beautifier_name, y.tag_start_char === "<" && (y.tag_name === "html" ? y.indent_content = this._options.indent_inner_html : y.tag_name === "head" ? y.indent_content = this._options.indent_head_inner_html : y.tag_name === "body" && (y.indent_content = this._options.indent_body_inner_html)), !(y.is_inline_element || y.is_unformatted) && (x.type !== "TK_CONTENT" || y.is_content_unformatted) && m.print_newline(!1), this._calcluate_parent_multiline(m, y);
  }, f.prototype._calcluate_parent_multiline = function(m, p) {
    p.parent && m._output.just_added_newline() && !((p.is_inline_element || p.is_unformatted) && p.parent.is_inline_element) && (p.parent.multiline_content = !0);
  };
  var _ = ["address", "article", "aside", "blockquote", "details", "div", "dl", "fieldset", "figcaption", "figure", "footer", "form", "h1", "h2", "h3", "h4", "h5", "h6", "header", "hr", "main", "nav", "ol", "p", "pre", "section", "table", "ul"], A = ["a", "audio", "del", "ins", "map", "noscript", "video"];
  return f.prototype._do_optional_end_element = function(m) {
    var p = null;
    if (!(m.is_empty_element || !m.is_start_tag || !m.parent)) {
      if (m.tag_name === "body")
        p = p || this._tag_stack.try_pop("head");
      else if (m.tag_name === "li")
        p = p || this._tag_stack.try_pop("li", ["ol", "ul"]);
      else if (m.tag_name === "dd" || m.tag_name === "dt")
        p = p || this._tag_stack.try_pop("dt", ["dl"]), p = p || this._tag_stack.try_pop("dd", ["dl"]);
      else if (m.parent.tag_name === "p" && _.indexOf(m.tag_name) !== -1) {
        var y = m.parent.parent;
        (!y || A.indexOf(y.tag_name) === -1) && (p = p || this._tag_stack.try_pop("p"));
      } else
        m.tag_name === "rp" || m.tag_name === "rt" ? (p = p || this._tag_stack.try_pop("rt", ["ruby", "rtc"]), p = p || this._tag_stack.try_pop("rp", ["ruby", "rtc"])) : m.tag_name === "optgroup" ? p = p || this._tag_stack.try_pop("optgroup", ["select"]) : m.tag_name === "option" ? p = p || this._tag_stack.try_pop("option", ["select", "datalist", "optgroup"]) : m.tag_name === "colgroup" ? p = p || this._tag_stack.try_pop("caption", ["table"]) : m.tag_name === "thead" ? (p = p || this._tag_stack.try_pop("caption", ["table"]), p = p || this._tag_stack.try_pop("colgroup", ["table"])) : m.tag_name === "tbody" || m.tag_name === "tfoot" ? (p = p || this._tag_stack.try_pop("caption", ["table"]), p = p || this._tag_stack.try_pop("colgroup", ["table"]), p = p || this._tag_stack.try_pop("thead", ["table"]), p = p || this._tag_stack.try_pop("tbody", ["table"])) : m.tag_name === "tr" ? (p = p || this._tag_stack.try_pop("caption", ["table"]), p = p || this._tag_stack.try_pop("colgroup", ["table"]), p = p || this._tag_stack.try_pop("tr", ["table", "thead", "tbody", "tfoot"])) : (m.tag_name === "th" || m.tag_name === "td") && (p = p || this._tag_stack.try_pop("td", ["table", "thead", "tbody", "tfoot", "tr"]), p = p || this._tag_stack.try_pop("th", ["table", "thead", "tbody", "tfoot", "tr"]));
      return m.parent = this._tag_stack.get_parser_token(), p;
    }
  }, Ml.Beautifier = f, Ml;
}
var Ff;
function vS() {
  if (Ff)
    return nr.exports;
  Ff = 1;
  var i = wS().Beautifier, t = jm().Options;
  function e(s, n, r, o) {
    var l = new i(s, n, r, o);
    return l.beautify();
  }
  return nr.exports = e, nr.exports.defaultOptions = function() {
    return new t();
  }, nr.exports;
}
var Vf;
function xS() {
  if (Vf)
    return Qs;
  Vf = 1;
  var i = _S(), t = yS(), e = vS();
  function s(n, r, o, l) {
    return o = o || i, l = l || t, e(n, r, o, l);
  }
  return s.defaultOptions = e.defaultOptions, Qs.js = i, Qs.css = t, Qs.html = s, Qs;
}
(function(i) {
  function t(e, s, n) {
    var r = function(o, l) {
      return e.js_beautify(o, l);
    };
    return r.js = e.js_beautify, r.css = s.css_beautify, r.html = n.html_beautify, r.js_beautify = e.js_beautify, r.css_beautify = s.css_beautify, r.html_beautify = n.html_beautify, r;
  }
  (function(e) {
    var s = xS();
    s.js_beautify = s.js, s.css_beautify = s.css, s.html_beautify = s.html, e.exports = t(s, s, s);
  })(i);
})(zm);
const kS = zm.exports;
const OS = {
  name: "Settings",
  components: {
    Codemirror: AO
  },
  data() {
    return {
      loading: !1,
      styling: "",
      errors: [],
      messages: []
    };
  },
  mounted() {
    return _e(this, null, function* () {
      this.loading = !0, yield fetch(`${OBJ.api_url}pargo/v1/get-setting-styling`, {
        headers: {
          "Content-Type": "application/json",
          "X-WP-Nonce": OBJ.nonce
        }
      }).then((i) => i.json()).then((i) => {
        i.code === "success" ? this.styling = kS.css(i.styling, {}) : this.errors.push(i.message);
      }).catch((i) => {
        console.error(i);
      }).finally(() => {
        this.loading = !1;
      });
    });
  },
  methods: {
    saveSettingStyling() {
      return _e(this, null, function* () {
        if (this.messages = [], this.errors = [], this.styling || this.errors.push("At least leave a CSS comment..."), this.errors.length == 0) {
          this.messages.push("Saving Styling...");
          const i = new FormData();
          i.append("pargo_setting_styling", this.styling), yield fetch(`${OBJ.api_url}pargo/v1/store-setting-styling`, {
            method: "POST",
            body: i,
            headers: {
              "X-WP-Nonce": OBJ.nonce
            }
          }).then((t) => t.json()).then((t) => {
            this.messages = [], this.messages.push(t.message);
          }).catch((t) => {
            console.error(t);
          });
        }
      });
    }
  },
  setup() {
    return {
      extensions: [iS(), dS]
    };
  }
}, SS = { class: "settings" }, CS = /* @__PURE__ */ V("h2", null, "PARGO GENERAL SETTINGS", -1), AS = { key: 0 }, TS = /* @__PURE__ */ V("hr", null, null, -1), PS = { key: 0 }, ES = { class: "success" }, MS = { key: 1 }, RS = { class: "errors" }, DS = { key: 2 };
function BS(i, t, e, s, n, r) {
  const o = Fs("codemirror");
  return gt(), xt("div", SS, [
    CS,
    !n.loading && !n.errors.length ? (gt(), xt("div", AS, [
      Rt(o, {
        modelValue: n.styling,
        "onUpdate:modelValue": t[0] || (t[0] = (l) => n.styling = l),
        autofocus: !0,
        extensions: s.extensions,
        "indent-with-tab": !0,
        style: { height: "auto" },
        "tab-size": 2,
        placeholder: "Pargo Pickup Styling goes here...",
        onChange: t[1] || (t[1] = (l) => this.styling = l)
      }, null, 8, ["modelValue", "extensions"]),
      TS,
      n.messages.length ? (gt(), xt("div", PS, [
        V("ul", ES, [
          (gt(!0), xt(he, null, Sr(n.messages, (l, a) => (gt(), xt("li", { key: a }, Xs(l), 1))), 128))
        ])
      ])) : di("", !0),
      V("button", {
        class: "button button-primary",
        type: "submit",
        onClick: t[2] || (t[2] = Xl((...l) => r.saveSettingStyling && r.saveSettingStyling(...l), ["prevent"]))
      }, "Save Styles"),
      V("button", {
        class: "button button-secondary",
        type: "button",
        onClick: t[3] || (t[3] = Xl((...l) => r.saveSettingStyling && r.saveSettingStyling(...l), ["prevent"]))
      }, "Reset to Default Styles ")
    ])) : n.errors.length ? (gt(), xt("div", MS, [
      V("ul", RS, [
        (gt(!0), xt(he, null, Sr(n.errors, (l, a) => (gt(), xt("li", { key: a }, Xs(l), 1))), 128))
      ])
    ])) : (gt(), xt("div", DS, "Loading..."))
  ]);
}
const NS = /* @__PURE__ */ kn(OS, [["render", BS]]);
const LS = {
  name: "PargoInfo",
  methods: {
    doEvent: () => _e(void 0, null, function* () {
      yield fetch(`${OBJ.api_url}pargo/v1/get-signup-click-event`, {
        headers: {
          "X-WP-Nonce": OBJ.nonce
        }
      }).then((i) => console.log(i)).catch((i) => console.error(i));
    })
  }
}, IS = (i) => (yd("data-v-31572f8b"), i = i(), wd(), i), QS = { class: "p-pargo-info" }, zS = /* @__PURE__ */ IS(() => /* @__PURE__ */ V("b", null, "Login", -1)), WS = /* @__PURE__ */ Ui(" to your myPargo account"), $S = [
  zS,
  WS
], FS = /* @__PURE__ */ L0('<div class="item" data-v-31572f8b><h2 data-v-31572f8b>South Africa</h2><p data-v-31572f8b><b data-v-31572f8b>Email Pargo:</b> <a href="mailto:info@pargo.co.za" data-v-31572f8b>info@pargo.co.za</a></p><p data-v-31572f8b><b data-v-31572f8b>Call Pargo:</b> <a href="tel:+27 21 447 3636" data-v-31572f8b> +27 21 447 3636 </a></p><p data-v-31572f8b><b data-v-31572f8b>About Pargo:</b> <a href="https://pargo.co.za/faq/" target="_blank" data-v-31572f8b>Frequently Asked Questions</a></p></div><div class="item" data-v-31572f8b><h2 data-v-31572f8b>Egypt</h2><p data-v-31572f8b><b data-v-31572f8b>Email Pargo:</b> <a href="mailto:support.eg@pargo.com" data-v-31572f8b>support.eg@pargo.com</a></p><p data-v-31572f8b><b data-v-31572f8b>Call Pargo:</b> <a href="tel:+20 100 326 5919" data-v-31572f8b> +20 100 326 5919 </a></p><p data-v-31572f8b><b data-v-31572f8b>About Pargo:</b> <a href="https://eg.pargo.com/faq/" target="_blank" data-v-31572f8b>Frequently Asked Questions</a></p></div>', 2);
function VS(i, t, e, s, n, r) {
  return gt(), xt("div", QS, [
    V("p", null, [
      V("a", {
        class: "a-button",
        href: "https://mypargo.pargo.co.za/mypargo",
        style: {},
        onClick: t[0] || (t[0] = (...o) => r.doEvent && r.doEvent(...o)),
        target: "_blank"
      }, $S)
    ]),
    FS
  ]);
}
const US = /* @__PURE__ */ kn(LS, [["render", VS], ["__scopeId", "data-v-31572f8b"]]);
const jS = {
  name: "PargoMap",
  props: {
    mapToken: {
      type: String,
      required: !0
    },
    urlEndPoint: {
      type: String,
      default: "production"
    },
    selectedPargoPoint: {
      type: Function,
      default: (i) => {
        console.log("selectedPoint", i);
      }
    }
  },
  mounted() {
    window.addEventListener ? window.addEventListener("message", this.selectPargoPoint, !1) : window.attachEvent("onmessage", this.selectPargoPoint);
  },
  data() {
    return {
      loaded: !1,
      src: `https://map${this.urlEndPoint === "staging" ? ".staging" : ""}.pargo.co.za/?token=${this.mapToken}`
    };
  },
  methods: {
    load() {
      this.loaded = !0;
    },
    selectPargoPoint(i) {
      i.data && i.data.pargoPointCode && this.selectedPargoPoint(i.data);
    }
  }
}, HS = { class: "p-a-map-container" }, qS = ["src"];
function KS(i, t, e, s, n, r) {
  return gt(), xt("div", HS, [
    Zh(V("div", null, "Loading Pargo Map Locations...", 512), [
      [mu, !n.loaded]
    ]),
    Zh(V("iframe", {
      id: "thePargoPageFrameID",
      src: n.src,
      width: "100%",
      height: "100%",
      allow: "geolocation *",
      name: "thePargoPageFrame",
      onLoad: t[0] || (t[0] = (...o) => r.load && r.load(...o))
    }, null, 40, qS), [
      [mu, n.loaded]
    ])
  ]);
}
const XS = /* @__PURE__ */ kn(jS, [["render", KS], ["__scopeId", "data-v-ca2cdc2f"]]);
const GS = {
  name: "App",
  data() {
    return {
      loading: !1,
      page: "",
      logoPath: `${OBJ.asset_url}/images/pargo_logo.png`,
      testingToken: !1,
      pargoUser: {
        username: "",
        password: "",
        mapToken: "",
        urlEndPoint: "production",
        usageTrackingEnabled: "true",
        api_token: "",
        supplierId: ""
      }
    };
  },
  mounted() {
    return _e(this, null, function* () {
      const i = window.location.search, t = new URLSearchParams(i);
      this.page = t.get("page"), this.page === "pargo-wp" && (this.loading = !0, yield fetch(`${OBJ.api_url}pargo/v1/get-credentials`, {
        headers: {
          "Content-Type": "application/json",
          "X-WP-Nonce": OBJ.nonce
        }
      }).then((e) => e.json()).then((e) => {
        const { data: s } = e;
        this.pargoUser.username = s.pargo_username, this.pargoUser.password = s.pargo_password, this.pargoUser.usageTrackingEnabled = s.pargo_usage_tracking_enabled, this.pargoUser.supplierId = s.supplier_id, this.pargoUser.storeCountryCode = s.pargo_store_country_code, s.pargo_url_endpoint ? this.pargoUser.urlEndPoint = s.pargo_url_endpoint : s.pargo_url.length > 0 && s.pargo_url_endpoint.length == 0 ? s.pargo_url.match("staging") && (this.pargoUser.urlEndPoint = "staging") : this.pargoUser.urlEndPoint = "production", this.pargoUser.mapToken = s.pargo_map_token, this.pargoUser.api_token = s.api_token;
      }).catch((e) => {
        console.error(e);
      }).finally(() => {
        this.loading = !1;
      }));
    });
  },
  watch: {
    "pargoUser.usageTrackingEnabled": function(i, t) {
      i == "" && (this.pargoUser.usageTrackingEnabled = "true");
    }
  },
  methods: {
    testMapToken(i) {
      this.testingToken && (this.testingToken = !1), this.pargoUser.mapToken = i.mapToken, this.pargoUser.urlEndPoint = i.urlEndPoint, this.testingToken = !0;
    }
  },
  components: {
    Home: cy,
    Settings: NS,
    PargoInfo: US,
    PargoMap: XS
  }
}, JS = { id: "pargo-backend-app" }, YS = ["src"], ZS = {
  key: 0,
  class: "p-a-container"
}, tC = { class: "p-a-main" }, eC = { key: 1 }, iC = { class: "p-a-map" }, sC = { class: "p-a-aside" }, nC = {
  key: 1,
  class: "p-a-container"
}, rC = { class: "p-a-main" }, oC = { class: "p-a-aside" };
function lC(i, t, e, s, n, r) {
  const o = Fs("Home"), l = Fs("PargoMap"), a = Fs("PargoInfo"), h = Fs("Settings");
  return gt(), xt("div", JS, [
    V("h1", null, [
      V("img", {
        src: n.logoPath,
        alt: "Pargo"
      }, null, 8, YS)
    ]),
    n.page === "pargo-wp" ? (gt(), xt("div", ZS, [
      V("div", tC, [
        n.loading ? (gt(), xt("div", eC, "Loading...")) : (gt(), jl(o, Wd({ key: 0 }, n.pargoUser, { onTestMapToken: r.testMapToken }), null, 16, ["onTestMapToken"]))
      ]),
      V("div", iC, [
        n.testingToken ? (gt(), xt("button", {
          key: 0,
          class: "button button-secondary close-btn",
          onClick: t[0] || (t[0] = (u) => n.testingToken = !1)
        }, "Close Map ")) : di("", !0),
        n.testingToken ? (gt(), jl(l, {
          key: 1,
          mapToken: this.pargoUser.mapToken,
          urlEndPoint: this.pargoUser.urlEndPoint
        }, null, 8, ["mapToken", "urlEndPoint"])) : di("", !0)
      ]),
      V("aside", sC, [
        Rt(a)
      ])
    ])) : di("", !0),
    n.page === "pargo-wp-settings" ? (gt(), xt("div", nC, [
      V("div", rC, [
        Rt(h)
      ]),
      V("aside", oC, [
        Rt(a)
      ])
    ])) : di("", !0)
  ]);
}
const aC = /* @__PURE__ */ kn(GS, [["render", lC]]);
function hC(i) {
  var t = jQuery;
  let e = t("#toplevel_page_" + i), s = window.location.href, n = s.substr(s.indexOf("admin.php"));
  e.on("click", "a", function() {
    var r = t(this);
    t("ul.wp-submenu li", e).removeClass("current"), r.hasClass("wp-has-submenu") ? t("li.wp-first-item", e).addClass("current") : r.parents("li").addClass("current");
  }), t("ul.wp-submenu a", e).each(function(r, o) {
    if (t(o).attr("href") === n) {
      t(o).parent().addClass("current");
      return;
    }
  });
}
const uC = _b(aC);
uC.mount("#pargo-admin-app");
hC("vue-app");
