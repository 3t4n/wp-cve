var Cm = Object.defineProperty, Im = Object.defineProperties;
var Nm = Object.getOwnPropertyDescriptors;
var Fi = Object.getOwnPropertySymbols;
var Bu = Object.prototype.hasOwnProperty, Uu = Object.prototype.propertyIsEnumerable;
var Fu = (r, e, t) => e in r ? Cm(r, e, { enumerable: !0, configurable: !0, writable: !0, value: t }) : r[e] = t, Ht = (r, e) => {
  for (var t in e || (e = {}))
    Bu.call(e, t) && Fu(r, t, e[t]);
  if (Fi)
    for (var t of Fi(e))
      Uu.call(e, t) && Fu(r, t, e[t]);
  return r;
}, Fs = (r, e) => Im(r, Nm(e));
var _a = (r, e) => {
  var t = {};
  for (var s in r)
    Bu.call(r, s) && e.indexOf(s) < 0 && (t[s] = r[s]);
  if (r != null && Fi)
    for (var s of Fi(r))
      e.indexOf(s) < 0 && Uu.call(r, s) && (t[s] = r[s]);
  return t;
};
var Bi = (r, e, t) => new Promise((s, i) => {
  var n = (l) => {
    try {
      o(t.next(l));
    } catch (u) {
      i(u);
    }
  }, a = (l) => {
    try {
      o(t.throw(l));
    } catch (u) {
      i(u);
    }
  }, o = (l) => l.done ? s(l.value) : Promise.resolve(l.value).then(n, a);
  o((t = t.apply(r, e)).next());
});
function ol(r, e) {
  const t = /* @__PURE__ */ Object.create(null), s = r.split(",");
  for (let i = 0; i < s.length; i++)
    t[s[i]] = !0;
  return e ? (i) => !!t[i.toLowerCase()] : (i) => !!t[i];
}
const Om = "itemscope,allowfullscreen,formnovalidate,ismap,nomodule,novalidate,readonly", km = /* @__PURE__ */ ol(Om);
function Sh(r) {
  return !!r || r === "";
}
function ll(r) {
  if (he(r)) {
    const e = {};
    for (let t = 0; t < r.length; t++) {
      const s = r[t], i = Ke(s) ? Dm(s) : ll(s);
      if (i)
        for (const n in i)
          e[n] = i[n];
    }
    return e;
  } else {
    if (Ke(r))
      return r;
    if (je(r))
      return r;
  }
}
const Mm = /;(?![^(]*\))/g, Lm = /:(.+)/;
function Dm(r) {
  const e = {};
  return r.split(Mm).forEach((t) => {
    if (t) {
      const s = t.split(Lm);
      s.length > 1 && (e[s[0].trim()] = s[1].trim());
    }
  }), e;
}
function ul(r) {
  let e = "";
  if (Ke(r))
    e = r;
  else if (he(r))
    for (let t = 0; t < r.length; t++) {
      const s = ul(r[t]);
      s && (e += s + " ");
    }
  else if (je(r))
    for (const t in r)
      r[t] && (e += t + " ");
  return e.trim();
}
const hi = (r) => Ke(r) ? r : r == null ? "" : he(r) || je(r) && (r.toString === Th || !de(r.toString)) ? JSON.stringify(r, wh, 2) : String(r), wh = (r, e) => e && e.__v_isRef ? wh(r, e.value) : cs(e) ? {
  [`Map(${e.size})`]: [...e.entries()].reduce((t, [s, i]) => (t[`${s} =>`] = i, t), {})
} : Ph(e) ? {
  [`Set(${e.size})`]: [...e.values()]
} : je(e) && !he(e) && !Ah(e) ? String(e) : e, Me = {}, us = [], It = () => {
}, Rm = () => !1, Fm = /^on[^a-z]/, Qn = (r) => Fm.test(r), cl = (r) => r.startsWith("onUpdate:"), He = Object.assign, hl = (r, e) => {
  const t = r.indexOf(e);
  t > -1 && r.splice(t, 1);
}, Bm = Object.prototype.hasOwnProperty, we = (r, e) => Bm.call(r, e), he = Array.isArray, cs = (r) => Xn(r) === "[object Map]", Ph = (r) => Xn(r) === "[object Set]", de = (r) => typeof r == "function", Ke = (r) => typeof r == "string", fl = (r) => typeof r == "symbol", je = (r) => r !== null && typeof r == "object", Eh = (r) => je(r) && de(r.then) && de(r.catch), Th = Object.prototype.toString, Xn = (r) => Th.call(r), Um = (r) => Xn(r).slice(8, -1), Ah = (r) => Xn(r) === "[object Object]", pl = (r) => Ke(r) && r !== "NaN" && r[0] !== "-" && "" + parseInt(r, 10) === r, nn = /* @__PURE__ */ ol(
  ",key,ref,ref_for,ref_key,onVnodeBeforeMount,onVnodeMounted,onVnodeBeforeUpdate,onVnodeUpdated,onVnodeBeforeUnmount,onVnodeUnmounted"
), Zn = (r) => {
  const e = /* @__PURE__ */ Object.create(null);
  return (t) => e[t] || (e[t] = r(t));
}, $m = /-(\w)/g, qt = Zn((r) => r.replace($m, (e, t) => t ? t.toUpperCase() : "")), jm = /\B([A-Z])/g, Cs = Zn((r) => r.replace(jm, "-$1").toLowerCase()), ea = Zn((r) => r.charAt(0).toUpperCase() + r.slice(1)), Ca = Zn((r) => r ? `on${ea(r)}` : ""), gn = (r, e) => !Object.is(r, e), Ia = (r, e) => {
  for (let t = 0; t < r.length; t++)
    r[t](e);
}, vn = (r, e, t) => {
  Object.defineProperty(r, e, {
    configurable: !0,
    enumerable: !1,
    value: t
  });
}, _h = (r) => {
  const e = parseFloat(r);
  return isNaN(e) ? r : e;
};
let $u;
const qm = () => $u || ($u = typeof globalThis != "undefined" ? globalThis : typeof self != "undefined" ? self : typeof window != "undefined" ? window : typeof global != "undefined" ? global : {});
let Dt;
class Vm {
  constructor(e = !1) {
    this.active = !0, this.effects = [], this.cleanups = [], !e && Dt && (this.parent = Dt, this.index = (Dt.scopes || (Dt.scopes = [])).push(this) - 1);
  }
  run(e) {
    if (this.active) {
      const t = Dt;
      try {
        return Dt = this, e();
      } finally {
        Dt = t;
      }
    }
  }
  on() {
    Dt = this;
  }
  off() {
    Dt = this.parent;
  }
  stop(e) {
    if (this.active) {
      let t, s;
      for (t = 0, s = this.effects.length; t < s; t++)
        this.effects[t].stop();
      for (t = 0, s = this.cleanups.length; t < s; t++)
        this.cleanups[t]();
      if (this.scopes)
        for (t = 0, s = this.scopes.length; t < s; t++)
          this.scopes[t].stop(!0);
      if (this.parent && !e) {
        const i = this.parent.scopes.pop();
        i && i !== this && (this.parent.scopes[this.index] = i, i.index = this.index);
      }
      this.active = !1;
    }
  }
}
function zm(r, e = Dt) {
  e && e.active && e.effects.push(r);
}
const dl = (r) => {
  const e = new Set(r);
  return e.w = 0, e.n = 0, e;
}, Ch = (r) => (r.w & Pr) > 0, Ih = (r) => (r.n & Pr) > 0, Wm = ({ deps: r }) => {
  if (r.length)
    for (let e = 0; e < r.length; e++)
      r[e].w |= Pr;
}, Hm = (r) => {
  const { deps: e } = r;
  if (e.length) {
    let t = 0;
    for (let s = 0; s < e.length; s++) {
      const i = e[s];
      Ch(i) && !Ih(i) ? i.delete(r) : e[t++] = i, i.w &= ~Pr, i.n &= ~Pr;
    }
    e.length = t;
  }
}, ao = /* @__PURE__ */ new WeakMap();
let Ks = 0, Pr = 1;
const oo = 30;
let Et;
const Wr = Symbol(""), lo = Symbol("");
class ml {
  constructor(e, t = null, s) {
    this.fn = e, this.scheduler = t, this.active = !0, this.deps = [], this.parent = void 0, zm(this, s);
  }
  run() {
    if (!this.active)
      return this.fn();
    let e = Et, t = xr;
    for (; e; ) {
      if (e === this)
        return;
      e = e.parent;
    }
    try {
      return this.parent = Et, Et = this, xr = !0, Pr = 1 << ++Ks, Ks <= oo ? Wm(this) : ju(this), this.fn();
    } finally {
      Ks <= oo && Hm(this), Pr = 1 << --Ks, Et = this.parent, xr = t, this.parent = void 0, this.deferStop && this.stop();
    }
  }
  stop() {
    Et === this ? this.deferStop = !0 : this.active && (ju(this), this.onStop && this.onStop(), this.active = !1);
  }
}
function ju(r) {
  const { deps: e } = r;
  if (e.length) {
    for (let t = 0; t < e.length; t++)
      e[t].delete(r);
    e.length = 0;
  }
}
let xr = !0;
const Nh = [];
function Is() {
  Nh.push(xr), xr = !1;
}
function Ns() {
  const r = Nh.pop();
  xr = r === void 0 ? !0 : r;
}
function pt(r, e, t) {
  if (xr && Et) {
    let s = ao.get(r);
    s || ao.set(r, s = /* @__PURE__ */ new Map());
    let i = s.get(t);
    i || s.set(t, i = dl()), Oh(i);
  }
}
function Oh(r, e) {
  let t = !1;
  Ks <= oo ? Ih(r) || (r.n |= Pr, t = !Ch(r)) : t = !r.has(Et), t && (r.add(Et), Et.deps.push(r));
}
function rr(r, e, t, s, i, n) {
  const a = ao.get(r);
  if (!a)
    return;
  let o = [];
  if (e === "clear")
    o = [...a.values()];
  else if (t === "length" && he(r))
    a.forEach((l, u) => {
      (u === "length" || u >= s) && o.push(l);
    });
  else
    switch (t !== void 0 && o.push(a.get(t)), e) {
      case "add":
        he(r) ? pl(t) && o.push(a.get("length")) : (o.push(a.get(Wr)), cs(r) && o.push(a.get(lo)));
        break;
      case "delete":
        he(r) || (o.push(a.get(Wr)), cs(r) && o.push(a.get(lo)));
        break;
      case "set":
        cs(r) && o.push(a.get(Wr));
        break;
    }
  if (o.length === 1)
    o[0] && uo(o[0]);
  else {
    const l = [];
    for (const u of o)
      u && l.push(...u);
    uo(dl(l));
  }
}
function uo(r, e) {
  const t = he(r) ? r : [...r];
  for (const s of t)
    s.computed && qu(s);
  for (const s of t)
    s.computed || qu(s);
}
function qu(r, e) {
  (r !== Et || r.allowRecurse) && (r.scheduler ? r.scheduler() : r.run());
}
const Km = /* @__PURE__ */ ol("__proto__,__v_isRef,__isVue"), kh = new Set(
  /* @__PURE__ */ Object.getOwnPropertyNames(Symbol).filter((r) => r !== "arguments" && r !== "caller").map((r) => Symbol[r]).filter(fl)
), Gm = /* @__PURE__ */ yl(), Ym = /* @__PURE__ */ yl(!1, !0), Jm = /* @__PURE__ */ yl(!0), Vu = /* @__PURE__ */ Qm();
function Qm() {
  const r = {};
  return ["includes", "indexOf", "lastIndexOf"].forEach((e) => {
    r[e] = function(...t) {
      const s = Ce(this);
      for (let n = 0, a = this.length; n < a; n++)
        pt(s, "get", n + "");
      const i = s[e](...t);
      return i === -1 || i === !1 ? s[e](...t.map(Ce)) : i;
    };
  }), ["push", "pop", "shift", "unshift", "splice"].forEach((e) => {
    r[e] = function(...t) {
      Is();
      const s = Ce(this)[e].apply(this, t);
      return Ns(), s;
    };
  }), r;
}
function yl(r = !1, e = !1) {
  return function(s, i, n) {
    if (i === "__v_isReactive")
      return !r;
    if (i === "__v_isReadonly")
      return r;
    if (i === "__v_isShallow")
      return e;
    if (i === "__v_raw" && n === (r ? e ? py : Fh : e ? Rh : Dh).get(s))
      return s;
    const a = he(s);
    if (!r && a && we(Vu, i))
      return Reflect.get(Vu, i, n);
    const o = Reflect.get(s, i, n);
    return (fl(i) ? kh.has(i) : Km(i)) || (r || pt(s, "get", i), e) ? o : et(o) ? a && pl(i) ? o : o.value : je(o) ? r ? Bh(o) : bl(o) : o;
  };
}
const Xm = /* @__PURE__ */ Mh(), Zm = /* @__PURE__ */ Mh(!0);
function Mh(r = !1) {
  return function(t, s, i, n) {
    let a = t[s];
    if (fi(a) && et(a) && !et(i))
      return !1;
    if (!r && !fi(i) && (co(i) || (i = Ce(i), a = Ce(a)), !he(t) && et(a) && !et(i)))
      return a.value = i, !0;
    const o = he(t) && pl(s) ? Number(s) < t.length : we(t, s), l = Reflect.set(t, s, i, n);
    return t === Ce(n) && (o ? gn(i, a) && rr(t, "set", s, i) : rr(t, "add", s, i)), l;
  };
}
function ey(r, e) {
  const t = we(r, e);
  r[e];
  const s = Reflect.deleteProperty(r, e);
  return s && t && rr(r, "delete", e, void 0), s;
}
function ty(r, e) {
  const t = Reflect.has(r, e);
  return (!fl(e) || !kh.has(e)) && pt(r, "has", e), t;
}
function ry(r) {
  return pt(r, "iterate", he(r) ? "length" : Wr), Reflect.ownKeys(r);
}
const Lh = {
  get: Gm,
  set: Xm,
  deleteProperty: ey,
  has: ty,
  ownKeys: ry
}, sy = {
  get: Jm,
  set(r, e) {
    return !0;
  },
  deleteProperty(r, e) {
    return !0;
  }
}, iy = /* @__PURE__ */ He({}, Lh, {
  get: Ym,
  set: Zm
}), gl = (r) => r, ta = (r) => Reflect.getPrototypeOf(r);
function Ui(r, e, t = !1, s = !1) {
  r = r.__v_raw;
  const i = Ce(r), n = Ce(e);
  t || (e !== n && pt(i, "get", e), pt(i, "get", n));
  const { has: a } = ta(i), o = s ? gl : t ? wl : Sl;
  if (a.call(i, e))
    return o(r.get(e));
  if (a.call(i, n))
    return o(r.get(n));
  r !== i && r.get(e);
}
function $i(r, e = !1) {
  const t = this.__v_raw, s = Ce(t), i = Ce(r);
  return e || (r !== i && pt(s, "has", r), pt(s, "has", i)), r === i ? t.has(r) : t.has(r) || t.has(i);
}
function ji(r, e = !1) {
  return r = r.__v_raw, !e && pt(Ce(r), "iterate", Wr), Reflect.get(r, "size", r);
}
function zu(r) {
  r = Ce(r);
  const e = Ce(this);
  return ta(e).has.call(e, r) || (e.add(r), rr(e, "add", r, r)), this;
}
function Wu(r, e) {
  e = Ce(e);
  const t = Ce(this), { has: s, get: i } = ta(t);
  let n = s.call(t, r);
  n || (r = Ce(r), n = s.call(t, r));
  const a = i.call(t, r);
  return t.set(r, e), n ? gn(e, a) && rr(t, "set", r, e) : rr(t, "add", r, e), this;
}
function Hu(r) {
  const e = Ce(this), { has: t, get: s } = ta(e);
  let i = t.call(e, r);
  i || (r = Ce(r), i = t.call(e, r)), s && s.call(e, r);
  const n = e.delete(r);
  return i && rr(e, "delete", r, void 0), n;
}
function Ku() {
  const r = Ce(this), e = r.size !== 0, t = r.clear();
  return e && rr(r, "clear", void 0, void 0), t;
}
function qi(r, e) {
  return function(s, i) {
    const n = this, a = n.__v_raw, o = Ce(a), l = e ? gl : r ? wl : Sl;
    return !r && pt(o, "iterate", Wr), a.forEach((u, c) => s.call(i, l(u), l(c), n));
  };
}
function Vi(r, e, t) {
  return function(...s) {
    const i = this.__v_raw, n = Ce(i), a = cs(n), o = r === "entries" || r === Symbol.iterator && a, l = r === "keys" && a, u = i[r](...s), c = t ? gl : e ? wl : Sl;
    return !e && pt(n, "iterate", l ? lo : Wr), {
      next() {
        const { value: h, done: f } = u.next();
        return f ? { value: h, done: f } : {
          value: o ? [c(h[0]), c(h[1])] : c(h),
          done: f
        };
      },
      [Symbol.iterator]() {
        return this;
      }
    };
  };
}
function cr(r) {
  return function(...e) {
    return r === "delete" ? !1 : this;
  };
}
function ny() {
  const r = {
    get(n) {
      return Ui(this, n);
    },
    get size() {
      return ji(this);
    },
    has: $i,
    add: zu,
    set: Wu,
    delete: Hu,
    clear: Ku,
    forEach: qi(!1, !1)
  }, e = {
    get(n) {
      return Ui(this, n, !1, !0);
    },
    get size() {
      return ji(this);
    },
    has: $i,
    add: zu,
    set: Wu,
    delete: Hu,
    clear: Ku,
    forEach: qi(!1, !0)
  }, t = {
    get(n) {
      return Ui(this, n, !0);
    },
    get size() {
      return ji(this, !0);
    },
    has(n) {
      return $i.call(this, n, !0);
    },
    add: cr("add"),
    set: cr("set"),
    delete: cr("delete"),
    clear: cr("clear"),
    forEach: qi(!0, !1)
  }, s = {
    get(n) {
      return Ui(this, n, !0, !0);
    },
    get size() {
      return ji(this, !0);
    },
    has(n) {
      return $i.call(this, n, !0);
    },
    add: cr("add"),
    set: cr("set"),
    delete: cr("delete"),
    clear: cr("clear"),
    forEach: qi(!0, !0)
  };
  return ["keys", "values", "entries", Symbol.iterator].forEach((n) => {
    r[n] = Vi(n, !1, !1), t[n] = Vi(n, !0, !1), e[n] = Vi(n, !1, !0), s[n] = Vi(n, !0, !0);
  }), [
    r,
    t,
    e,
    s
  ];
}
const [ay, oy, ly, uy] = /* @__PURE__ */ ny();
function vl(r, e) {
  const t = e ? r ? uy : ly : r ? oy : ay;
  return (s, i, n) => i === "__v_isReactive" ? !r : i === "__v_isReadonly" ? r : i === "__v_raw" ? s : Reflect.get(we(t, i) && i in s ? t : s, i, n);
}
const cy = {
  get: /* @__PURE__ */ vl(!1, !1)
}, hy = {
  get: /* @__PURE__ */ vl(!1, !0)
}, fy = {
  get: /* @__PURE__ */ vl(!0, !1)
}, Dh = /* @__PURE__ */ new WeakMap(), Rh = /* @__PURE__ */ new WeakMap(), Fh = /* @__PURE__ */ new WeakMap(), py = /* @__PURE__ */ new WeakMap();
function dy(r) {
  switch (r) {
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
function my(r) {
  return r.__v_skip || !Object.isExtensible(r) ? 0 : dy(Um(r));
}
function bl(r) {
  return fi(r) ? r : xl(r, !1, Lh, cy, Dh);
}
function yy(r) {
  return xl(r, !1, iy, hy, Rh);
}
function Bh(r) {
  return xl(r, !0, sy, fy, Fh);
}
function xl(r, e, t, s, i) {
  if (!je(r) || r.__v_raw && !(e && r.__v_isReactive))
    return r;
  const n = i.get(r);
  if (n)
    return n;
  const a = my(r);
  if (a === 0)
    return r;
  const o = new Proxy(r, a === 2 ? s : t);
  return i.set(r, o), o;
}
function hs(r) {
  return fi(r) ? hs(r.__v_raw) : !!(r && r.__v_isReactive);
}
function fi(r) {
  return !!(r && r.__v_isReadonly);
}
function co(r) {
  return !!(r && r.__v_isShallow);
}
function Uh(r) {
  return hs(r) || fi(r);
}
function Ce(r) {
  const e = r && r.__v_raw;
  return e ? Ce(e) : r;
}
function $h(r) {
  return vn(r, "__v_skip", !0), r;
}
const Sl = (r) => je(r) ? bl(r) : r, wl = (r) => je(r) ? Bh(r) : r;
function gy(r) {
  xr && Et && (r = Ce(r), Oh(r.dep || (r.dep = dl())));
}
function vy(r, e) {
  r = Ce(r), r.dep && uo(r.dep);
}
function et(r) {
  return !!(r && r.__v_isRef === !0);
}
function by(r) {
  return et(r) ? r.value : r;
}
const xy = {
  get: (r, e, t) => by(Reflect.get(r, e, t)),
  set: (r, e, t, s) => {
    const i = r[e];
    return et(i) && !et(t) ? (i.value = t, !0) : Reflect.set(r, e, t, s);
  }
};
function jh(r) {
  return hs(r) ? r : new Proxy(r, xy);
}
class Sy {
  constructor(e, t, s, i) {
    this._setter = t, this.dep = void 0, this.__v_isRef = !0, this._dirty = !0, this.effect = new ml(e, () => {
      this._dirty || (this._dirty = !0, vy(this));
    }), this.effect.computed = this, this.effect.active = this._cacheable = !i, this.__v_isReadonly = s;
  }
  get value() {
    const e = Ce(this);
    return gy(e), (e._dirty || !e._cacheable) && (e._dirty = !1, e._value = e.effect.run()), e._value;
  }
  set value(e) {
    this._setter(e);
  }
}
function wy(r, e, t = !1) {
  let s, i;
  const n = de(r);
  return n ? (s = r, i = It) : (s = r.get, i = r.set), new Sy(s, i, n || !i, t);
}
function Sr(r, e, t, s) {
  let i;
  try {
    i = s ? r(...s) : r();
  } catch (n) {
    ra(n, e, t);
  }
  return i;
}
function bt(r, e, t, s) {
  if (de(r)) {
    const n = Sr(r, e, t, s);
    return n && Eh(n) && n.catch((a) => {
      ra(a, e, t);
    }), n;
  }
  const i = [];
  for (let n = 0; n < r.length; n++)
    i.push(bt(r[n], e, t, s));
  return i;
}
function ra(r, e, t, s = !0) {
  const i = e ? e.vnode : null;
  if (e) {
    let n = e.parent;
    const a = e.proxy, o = t;
    for (; n; ) {
      const u = n.ec;
      if (u) {
        for (let c = 0; c < u.length; c++)
          if (u[c](r, a, o) === !1)
            return;
      }
      n = n.parent;
    }
    const l = e.appContext.config.errorHandler;
    if (l) {
      Sr(l, null, 10, [r, a, o]);
      return;
    }
  }
  Py(r, t, i, s);
}
function Py(r, e, t, s = !0) {
  console.error(r);
}
let bn = !1, ho = !1;
const ct = [];
let Gt = 0;
const Xs = [];
let Gs = null, Xr = 0;
const Zs = [];
let mr = null, Zr = 0;
const qh = /* @__PURE__ */ Promise.resolve();
let Pl = null, fo = null;
function Ey(r) {
  const e = Pl || qh;
  return r ? e.then(this ? r.bind(this) : r) : e;
}
function Ty(r) {
  let e = Gt + 1, t = ct.length;
  for (; e < t; ) {
    const s = e + t >>> 1;
    pi(ct[s]) < r ? e = s + 1 : t = s;
  }
  return e;
}
function Vh(r) {
  (!ct.length || !ct.includes(r, bn && r.allowRecurse ? Gt + 1 : Gt)) && r !== fo && (r.id == null ? ct.push(r) : ct.splice(Ty(r.id), 0, r), zh());
}
function zh() {
  !bn && !ho && (ho = !0, Pl = qh.then(Kh));
}
function Ay(r) {
  const e = ct.indexOf(r);
  e > Gt && ct.splice(e, 1);
}
function Wh(r, e, t, s) {
  he(r) ? t.push(...r) : (!e || !e.includes(r, r.allowRecurse ? s + 1 : s)) && t.push(r), zh();
}
function _y(r) {
  Wh(r, Gs, Xs, Xr);
}
function Cy(r) {
  Wh(r, mr, Zs, Zr);
}
function sa(r, e = null) {
  if (Xs.length) {
    for (fo = e, Gs = [...new Set(Xs)], Xs.length = 0, Xr = 0; Xr < Gs.length; Xr++)
      Gs[Xr]();
    Gs = null, Xr = 0, fo = null, sa(r, e);
  }
}
function Hh(r) {
  if (sa(), Zs.length) {
    const e = [...new Set(Zs)];
    if (Zs.length = 0, mr) {
      mr.push(...e);
      return;
    }
    for (mr = e, mr.sort((t, s) => pi(t) - pi(s)), Zr = 0; Zr < mr.length; Zr++)
      mr[Zr]();
    mr = null, Zr = 0;
  }
}
const pi = (r) => r.id == null ? 1 / 0 : r.id;
function Kh(r) {
  ho = !1, bn = !0, sa(r), ct.sort((t, s) => pi(t) - pi(s));
  const e = It;
  try {
    for (Gt = 0; Gt < ct.length; Gt++) {
      const t = ct[Gt];
      t && t.active !== !1 && Sr(t, null, 14);
    }
  } finally {
    Gt = 0, ct.length = 0, Hh(), bn = !1, Pl = null, (ct.length || Xs.length || Zs.length) && Kh(r);
  }
}
function Iy(r, e, ...t) {
  if (r.isUnmounted)
    return;
  const s = r.vnode.props || Me;
  let i = t;
  const n = e.startsWith("update:"), a = n && e.slice(7);
  if (a && a in s) {
    const c = `${a === "modelValue" ? "model" : a}Modifiers`, { number: h, trim: f } = s[c] || Me;
    f && (i = t.map((p) => p.trim())), h && (i = t.map(_h));
  }
  let o, l = s[o = Ca(e)] || s[o = Ca(qt(e))];
  !l && n && (l = s[o = Ca(Cs(e))]), l && bt(l, r, 6, i);
  const u = s[o + "Once"];
  if (u) {
    if (!r.emitted)
      r.emitted = {};
    else if (r.emitted[o])
      return;
    r.emitted[o] = !0, bt(u, r, 6, i);
  }
}
function Gh(r, e, t = !1) {
  const s = e.emitsCache, i = s.get(r);
  if (i !== void 0)
    return i;
  const n = r.emits;
  let a = {}, o = !1;
  if (!de(r)) {
    const l = (u) => {
      const c = Gh(u, e, !0);
      c && (o = !0, He(a, c));
    };
    !t && e.mixins.length && e.mixins.forEach(l), r.extends && l(r.extends), r.mixins && r.mixins.forEach(l);
  }
  return !n && !o ? (s.set(r, null), null) : (he(n) ? n.forEach((l) => a[l] = null) : He(a, n), s.set(r, a), a);
}
function ia(r, e) {
  return !r || !Qn(e) ? !1 : (e = e.slice(2).replace(/Once$/, ""), we(r, e[0].toLowerCase() + e.slice(1)) || we(r, Cs(e)) || we(r, e));
}
let Qe = null, Yh = null;
function xn(r) {
  const e = Qe;
  return Qe = r, Yh = r && r.type.__scopeId || null, e;
}
function ei(r, e = Qe, t) {
  if (!e || r._n)
    return r;
  const s = (...i) => {
    s._d && nc(-1);
    const n = xn(e), a = r(...i);
    return xn(n), s._d && nc(1), a;
  };
  return s._n = !0, s._c = !0, s._d = !0, s;
}
function Na(r) {
  const { type: e, vnode: t, proxy: s, withProxy: i, props: n, propsOptions: [a], slots: o, attrs: l, emit: u, render: c, renderCache: h, data: f, setupState: p, ctx: x, inheritAttrs: d } = r;
  let m, y;
  const _ = xn(r);
  try {
    if (t.shapeFlag & 4) {
      const C = i || s;
      m = Ft(c.call(C, C, h, n, p, f, x)), y = l;
    } else {
      const C = e;
      m = Ft(C.length > 1 ? C(n, { attrs: l, slots: o, emit: u }) : C(n, null)), y = e.props ? l : Ny(l);
    }
  } catch (C) {
    ri.length = 0, ra(C, r, 1), m = st(xt);
  }
  let T = m;
  if (y && d !== !1) {
    const C = Object.keys(y), { shapeFlag: v } = T;
    C.length && v & 7 && (a && C.some(cl) && (y = Oy(y, a)), T = Er(T, y));
  }
  return t.dirs && (T = Er(T), T.dirs = T.dirs ? T.dirs.concat(t.dirs) : t.dirs), t.transition && (T.transition = t.transition), m = T, xn(_), m;
}
const Ny = (r) => {
  let e;
  for (const t in r)
    (t === "class" || t === "style" || Qn(t)) && ((e || (e = {}))[t] = r[t]);
  return e;
}, Oy = (r, e) => {
  const t = {};
  for (const s in r)
    (!cl(s) || !(s.slice(9) in e)) && (t[s] = r[s]);
  return t;
};
function ky(r, e, t) {
  const { props: s, children: i, component: n } = r, { props: a, children: o, patchFlag: l } = e, u = n.emitsOptions;
  if (e.dirs || e.transition)
    return !0;
  if (t && l >= 0) {
    if (l & 1024)
      return !0;
    if (l & 16)
      return s ? Gu(s, a, u) : !!a;
    if (l & 8) {
      const c = e.dynamicProps;
      for (let h = 0; h < c.length; h++) {
        const f = c[h];
        if (a[f] !== s[f] && !ia(u, f))
          return !0;
      }
    }
  } else
    return (i || o) && (!o || !o.$stable) ? !0 : s === a ? !1 : s ? a ? Gu(s, a, u) : !0 : !!a;
  return !1;
}
function Gu(r, e, t) {
  const s = Object.keys(e);
  if (s.length !== Object.keys(r).length)
    return !0;
  for (let i = 0; i < s.length; i++) {
    const n = s[i];
    if (e[n] !== r[n] && !ia(t, n))
      return !0;
  }
  return !1;
}
function My({ vnode: r, parent: e }, t) {
  for (; e && e.subTree === r; )
    (r = e.vnode).el = t, e = e.parent;
}
const Ly = (r) => r.__isSuspense;
function Dy(r, e) {
  e && e.pendingBranch ? he(r) ? e.effects.push(...r) : e.effects.push(r) : Cy(r);
}
function Ry(r, e) {
  if (ze) {
    let t = ze.provides;
    const s = ze.parent && ze.parent.provides;
    s === t && (t = ze.provides = Object.create(s)), t[r] = e;
  }
}
function Oa(r, e, t = !1) {
  const s = ze || Qe;
  if (s) {
    const i = s.parent == null ? s.vnode.appContext && s.vnode.appContext.provides : s.parent.provides;
    if (i && r in i)
      return i[r];
    if (arguments.length > 1)
      return t && de(e) ? e.call(s.proxy) : e;
  }
}
const Yu = {};
function ka(r, e, t) {
  return Jh(r, e, t);
}
function Jh(r, e, { immediate: t, deep: s, flush: i, onTrack: n, onTrigger: a } = Me) {
  const o = ze;
  let l, u = !1, c = !1;
  if (et(r) ? (l = () => r.value, u = co(r)) : hs(r) ? (l = () => r, s = !0) : he(r) ? (c = !0, u = r.some((y) => hs(y) || co(y)), l = () => r.map((y) => {
    if (et(y))
      return y.value;
    if (hs(y))
      return $r(y);
    if (de(y))
      return Sr(y, o, 2);
  })) : de(r) ? e ? l = () => Sr(r, o, 2) : l = () => {
    if (!(o && o.isUnmounted))
      return h && h(), bt(r, o, 3, [f]);
  } : l = It, e && s) {
    const y = l;
    l = () => $r(y());
  }
  let h, f = (y) => {
    h = m.onStop = () => {
      Sr(y, o, 4);
    };
  };
  if (yi)
    return f = It, e ? t && bt(e, o, 3, [
      l(),
      c ? [] : void 0,
      f
    ]) : l(), It;
  let p = c ? [] : Yu;
  const x = () => {
    if (!!m.active)
      if (e) {
        const y = m.run();
        (s || u || (c ? y.some((_, T) => gn(_, p[T])) : gn(y, p))) && (h && h(), bt(e, o, 3, [
          y,
          p === Yu ? void 0 : p,
          f
        ]), p = y);
      } else
        m.run();
  };
  x.allowRecurse = !!e;
  let d;
  i === "sync" ? d = x : i === "post" ? d = () => tt(x, o && o.suspense) : d = () => _y(x);
  const m = new ml(l, d);
  return e ? t ? x() : p = m.run() : i === "post" ? tt(m.run.bind(m), o && o.suspense) : m.run(), () => {
    m.stop(), o && o.scope && hl(o.scope.effects, m);
  };
}
function Fy(r, e, t) {
  const s = this.proxy, i = Ke(r) ? r.includes(".") ? Qh(s, r) : () => s[r] : r.bind(s, s);
  let n;
  de(e) ? n = e : (n = e.handler, t = e);
  const a = ze;
  bs(this);
  const o = Jh(i, n.bind(s), t);
  return a ? bs(a) : Hr(), o;
}
function Qh(r, e) {
  const t = e.split(".");
  return () => {
    let s = r;
    for (let i = 0; i < t.length && s; i++)
      s = s[t[i]];
    return s;
  };
}
function $r(r, e) {
  if (!je(r) || r.__v_skip || (e = e || /* @__PURE__ */ new Set(), e.has(r)))
    return r;
  if (e.add(r), et(r))
    $r(r.value, e);
  else if (he(r))
    for (let t = 0; t < r.length; t++)
      $r(r[t], e);
  else if (Ph(r) || cs(r))
    r.forEach((t) => {
      $r(t, e);
    });
  else if (Ah(r))
    for (const t in r)
      $r(r[t], e);
  return r;
}
function By() {
  const r = {
    isMounted: !1,
    isLeaving: !1,
    isUnmounting: !1,
    leavingVNodes: /* @__PURE__ */ new Map()
  };
  return rf(() => {
    r.isMounted = !0;
  }), sf(() => {
    r.isUnmounting = !0;
  }), r;
}
const dt = [Function, Array], Uy = {
  name: "BaseTransition",
  props: {
    mode: String,
    appear: Boolean,
    persisted: Boolean,
    onBeforeEnter: dt,
    onEnter: dt,
    onAfterEnter: dt,
    onEnterCancelled: dt,
    onBeforeLeave: dt,
    onLeave: dt,
    onAfterLeave: dt,
    onLeaveCancelled: dt,
    onBeforeAppear: dt,
    onAppear: dt,
    onAfterAppear: dt,
    onAppearCancelled: dt
  },
  setup(r, { slots: e }) {
    const t = wg(), s = By();
    let i;
    return () => {
      const n = e.default && ef(e.default(), !0);
      if (!n || !n.length)
        return;
      let a = n[0];
      if (n.length > 1) {
        for (const d of n)
          if (d.type !== xt) {
            a = d;
            break;
          }
      }
      const o = Ce(r), { mode: l } = o;
      if (s.isLeaving)
        return Ma(a);
      const u = Ju(a);
      if (!u)
        return Ma(a);
      const c = po(u, o, s, t);
      mo(u, c);
      const h = t.subTree, f = h && Ju(h);
      let p = !1;
      const { getTransitionKey: x } = u.type;
      if (x) {
        const d = x();
        i === void 0 ? i = d : d !== i && (i = d, p = !0);
      }
      if (f && f.type !== xt && (!Rr(u, f) || p)) {
        const d = po(f, o, s, t);
        if (mo(f, d), l === "out-in")
          return s.isLeaving = !0, d.afterLeave = () => {
            s.isLeaving = !1, t.update();
          }, Ma(a);
        l === "in-out" && u.type !== xt && (d.delayLeave = (m, y, _) => {
          const T = Zh(s, f);
          T[String(f.key)] = f, m._leaveCb = () => {
            y(), m._leaveCb = void 0, delete c.delayedLeave;
          }, c.delayedLeave = _;
        });
      }
      return a;
    };
  }
}, Xh = Uy;
function Zh(r, e) {
  const { leavingVNodes: t } = r;
  let s = t.get(e.type);
  return s || (s = /* @__PURE__ */ Object.create(null), t.set(e.type, s)), s;
}
function po(r, e, t, s) {
  const { appear: i, mode: n, persisted: a = !1, onBeforeEnter: o, onEnter: l, onAfterEnter: u, onEnterCancelled: c, onBeforeLeave: h, onLeave: f, onAfterLeave: p, onLeaveCancelled: x, onBeforeAppear: d, onAppear: m, onAfterAppear: y, onAppearCancelled: _ } = e, T = String(r.key), C = Zh(t, r), v = (P, g) => {
    P && bt(P, s, 9, g);
  }, w = (P, g) => {
    const E = g[1];
    v(P, g), he(P) ? P.every((O) => O.length <= 1) && E() : P.length <= 1 && E();
  }, N = {
    mode: n,
    persisted: a,
    beforeEnter(P) {
      let g = o;
      if (!t.isMounted)
        if (i)
          g = d || o;
        else
          return;
      P._leaveCb && P._leaveCb(!0);
      const E = C[T];
      E && Rr(r, E) && E.el._leaveCb && E.el._leaveCb(), v(g, [P]);
    },
    enter(P) {
      let g = l, E = u, O = c;
      if (!t.isMounted)
        if (i)
          g = m || l, E = y || u, O = _ || c;
        else
          return;
      let S = !1;
      const W = P._enterCb = (Q) => {
        S || (S = !0, Q ? v(O, [P]) : v(E, [P]), N.delayedLeave && N.delayedLeave(), P._enterCb = void 0);
      };
      g ? w(g, [P, W]) : W();
    },
    leave(P, g) {
      const E = String(r.key);
      if (P._enterCb && P._enterCb(!0), t.isUnmounting)
        return g();
      v(h, [P]);
      let O = !1;
      const S = P._leaveCb = (W) => {
        O || (O = !0, g(), W ? v(x, [P]) : v(p, [P]), P._leaveCb = void 0, C[E] === r && delete C[E]);
      };
      C[E] = r, f ? w(f, [P, S]) : S();
    },
    clone(P) {
      return po(P, e, t, s);
    }
  };
  return N;
}
function Ma(r) {
  if (na(r))
    return r = Er(r), r.children = null, r;
}
function Ju(r) {
  return na(r) ? r.children ? r.children[0] : void 0 : r;
}
function mo(r, e) {
  r.shapeFlag & 6 && r.component ? mo(r.component.subTree, e) : r.shapeFlag & 128 ? (r.ssContent.transition = e.clone(r.ssContent), r.ssFallback.transition = e.clone(r.ssFallback)) : r.transition = e;
}
function ef(r, e = !1, t) {
  let s = [], i = 0;
  for (let n = 0; n < r.length; n++) {
    let a = r[n];
    const o = t == null ? a.key : String(t) + String(a.key != null ? a.key : n);
    a.type === lt ? (a.patchFlag & 128 && i++, s = s.concat(ef(a.children, e, o))) : (e || a.type !== xt) && s.push(o != null ? Er(a, { key: o }) : a);
  }
  if (i > 1)
    for (let n = 0; n < s.length; n++)
      s[n].patchFlag = -2;
  return s;
}
const ti = (r) => !!r.type.__asyncLoader, na = (r) => r.type.__isKeepAlive;
function $y(r, e) {
  tf(r, "a", e);
}
function jy(r, e) {
  tf(r, "da", e);
}
function tf(r, e, t = ze) {
  const s = r.__wdc || (r.__wdc = () => {
    let i = t;
    for (; i; ) {
      if (i.isDeactivated)
        return;
      i = i.parent;
    }
    return r();
  });
  if (aa(e, s, t), t) {
    let i = t.parent;
    for (; i && i.parent; )
      na(i.parent.vnode) && qy(s, e, t, i), i = i.parent;
  }
}
function qy(r, e, t, s) {
  const i = aa(e, r, s, !0);
  nf(() => {
    hl(s[e], i);
  }, t);
}
function aa(r, e, t = ze, s = !1) {
  if (t) {
    const i = t[r] || (t[r] = []), n = e.__weh || (e.__weh = (...a) => {
      if (t.isUnmounted)
        return;
      Is(), bs(t);
      const o = bt(e, t, r, a);
      return Hr(), Ns(), o;
    });
    return s ? i.unshift(n) : i.push(n), n;
  }
}
const ar = (r) => (e, t = ze) => (!yi || r === "sp") && aa(r, e, t), Vy = ar("bm"), rf = ar("m"), zy = ar("bu"), Wy = ar("u"), sf = ar("bum"), nf = ar("um"), Hy = ar("sp"), Ky = ar("rtg"), Gy = ar("rtc");
function Yy(r, e = ze) {
  aa("ec", r, e);
}
function ss(r, e) {
  const t = Qe;
  if (t === null)
    return r;
  const s = la(t) || t.proxy, i = r.dirs || (r.dirs = []);
  for (let n = 0; n < e.length; n++) {
    let [a, o, l, u = Me] = e[n];
    de(a) && (a = {
      mounted: a,
      updated: a
    }), a.deep && $r(o), i.push({
      dir: a,
      instance: s,
      value: o,
      oldValue: void 0,
      arg: l,
      modifiers: u
    });
  }
  return r;
}
function Nr(r, e, t, s) {
  const i = r.dirs, n = e && e.dirs;
  for (let a = 0; a < i.length; a++) {
    const o = i[a];
    n && (o.oldValue = n[a].value);
    let l = o.dir[s];
    l && (Is(), bt(l, t, 8, [
      r.el,
      o,
      r,
      e
    ]), Ns());
  }
}
const af = "components";
function Qu(r, e) {
  return Qy(af, r, !0, e) || r;
}
const Jy = Symbol();
function Qy(r, e, t = !0, s = !1) {
  const i = Qe || ze;
  if (i) {
    const n = i.type;
    if (r === af) {
      const o = _g(n, !1);
      if (o && (o === e || o === qt(e) || o === ea(qt(e))))
        return n;
    }
    const a = Xu(i[r] || n[r], e) || Xu(i.appContext[r], e);
    return !a && s ? n : a;
  }
}
function Xu(r, e) {
  return r && (r[e] || r[qt(e)] || r[ea(qt(e))]);
}
function La(r, e, t = {}, s, i) {
  if (Qe.isCE || Qe.parent && ti(Qe.parent) && Qe.parent.isCE)
    return st("slot", e === "default" ? null : { name: e }, s && s());
  let n = r[e];
  n && n._c && (n._d = !1), Tt();
  const a = n && of(n(t)), o = fs(lt, { key: t.key || `_${e}` }, a || (s ? s() : []), a && r._ === 1 ? 64 : -2);
  return !i && o.scopeId && (o.slotScopeIds = [o.scopeId + "-s"]), n && n._c && (n._d = !0), o;
}
function of(r) {
  return r.some((e) => Pn(e) ? !(e.type === xt || e.type === lt && !of(e.children)) : !0) ? r : null;
}
const yo = (r) => r ? bf(r) ? la(r) || r.proxy : yo(r.parent) : null, Sn = /* @__PURE__ */ He(/* @__PURE__ */ Object.create(null), {
  $: (r) => r,
  $el: (r) => r.vnode.el,
  $data: (r) => r.data,
  $props: (r) => r.props,
  $attrs: (r) => r.attrs,
  $slots: (r) => r.slots,
  $refs: (r) => r.refs,
  $parent: (r) => yo(r.parent),
  $root: (r) => yo(r.root),
  $emit: (r) => r.emit,
  $options: (r) => uf(r),
  $forceUpdate: (r) => r.f || (r.f = () => Vh(r.update)),
  $nextTick: (r) => r.n || (r.n = Ey.bind(r.proxy)),
  $watch: (r) => Fy.bind(r)
}), Xy = {
  get({ _: r }, e) {
    const { ctx: t, setupState: s, data: i, props: n, accessCache: a, type: o, appContext: l } = r;
    let u;
    if (e[0] !== "$") {
      const p = a[e];
      if (p !== void 0)
        switch (p) {
          case 1:
            return s[e];
          case 2:
            return i[e];
          case 4:
            return t[e];
          case 3:
            return n[e];
        }
      else {
        if (s !== Me && we(s, e))
          return a[e] = 1, s[e];
        if (i !== Me && we(i, e))
          return a[e] = 2, i[e];
        if ((u = r.propsOptions[0]) && we(u, e))
          return a[e] = 3, n[e];
        if (t !== Me && we(t, e))
          return a[e] = 4, t[e];
        go && (a[e] = 0);
      }
    }
    const c = Sn[e];
    let h, f;
    if (c)
      return e === "$attrs" && pt(r, "get", e), c(r);
    if ((h = o.__cssModules) && (h = h[e]))
      return h;
    if (t !== Me && we(t, e))
      return a[e] = 4, t[e];
    if (f = l.config.globalProperties, we(f, e))
      return f[e];
  },
  set({ _: r }, e, t) {
    const { data: s, setupState: i, ctx: n } = r;
    return i !== Me && we(i, e) ? (i[e] = t, !0) : s !== Me && we(s, e) ? (s[e] = t, !0) : we(r.props, e) || e[0] === "$" && e.slice(1) in r ? !1 : (n[e] = t, !0);
  },
  has({ _: { data: r, setupState: e, accessCache: t, ctx: s, appContext: i, propsOptions: n } }, a) {
    let o;
    return !!t[a] || r !== Me && we(r, a) || e !== Me && we(e, a) || (o = n[0]) && we(o, a) || we(s, a) || we(Sn, a) || we(i.config.globalProperties, a);
  },
  defineProperty(r, e, t) {
    return t.get != null ? r._.accessCache[e] = 0 : we(t, "value") && this.set(r, e, t.value, null), Reflect.defineProperty(r, e, t);
  }
};
let go = !0;
function Zy(r) {
  const e = uf(r), t = r.proxy, s = r.ctx;
  go = !1, e.beforeCreate && Zu(e.beforeCreate, r, "bc");
  const {
    data: i,
    computed: n,
    methods: a,
    watch: o,
    provide: l,
    inject: u,
    created: c,
    beforeMount: h,
    mounted: f,
    beforeUpdate: p,
    updated: x,
    activated: d,
    deactivated: m,
    beforeDestroy: y,
    beforeUnmount: _,
    destroyed: T,
    unmounted: C,
    render: v,
    renderTracked: w,
    renderTriggered: N,
    errorCaptured: P,
    serverPrefetch: g,
    expose: E,
    inheritAttrs: O,
    components: S,
    directives: W,
    filters: Q
  } = e;
  if (u && eg(u, s, null, r.appContext.config.unwrapInjectedRef), a)
    for (const J in a) {
      const ce = a[J];
      de(ce) && (s[J] = ce.bind(t));
    }
  if (i) {
    const J = i.call(t, t);
    je(J) && (r.data = bl(J));
  }
  if (go = !0, n)
    for (const J in n) {
      const ce = n[J], qe = de(ce) ? ce.bind(t, t) : de(ce.get) ? ce.get.bind(t, t) : It, G = !de(ce) && de(ce.set) ? ce.set.bind(t) : It, H = Ig({
        get: qe,
        set: G
      });
      Object.defineProperty(s, J, {
        enumerable: !0,
        configurable: !0,
        get: () => H.value,
        set: (K) => H.value = K
      });
    }
  if (o)
    for (const J in o)
      lf(o[J], s, t, J);
  if (l) {
    const J = de(l) ? l.call(t) : l;
    Reflect.ownKeys(J).forEach((ce) => {
      Ry(ce, J[ce]);
    });
  }
  c && Zu(c, r, "c");
  function re(J, ce) {
    he(ce) ? ce.forEach((qe) => J(qe.bind(t))) : ce && J(ce.bind(t));
  }
  if (re(Vy, h), re(rf, f), re(zy, p), re(Wy, x), re($y, d), re(jy, m), re(Yy, P), re(Gy, w), re(Ky, N), re(sf, _), re(nf, C), re(Hy, g), he(E))
    if (E.length) {
      const J = r.exposed || (r.exposed = {});
      E.forEach((ce) => {
        Object.defineProperty(J, ce, {
          get: () => t[ce],
          set: (qe) => t[ce] = qe
        });
      });
    } else
      r.exposed || (r.exposed = {});
  v && r.render === It && (r.render = v), O != null && (r.inheritAttrs = O), S && (r.components = S), W && (r.directives = W);
}
function eg(r, e, t = It, s = !1) {
  he(r) && (r = vo(r));
  for (const i in r) {
    const n = r[i];
    let a;
    je(n) ? "default" in n ? a = Oa(n.from || i, n.default, !0) : a = Oa(n.from || i) : a = Oa(n), et(a) && s ? Object.defineProperty(e, i, {
      enumerable: !0,
      configurable: !0,
      get: () => a.value,
      set: (o) => a.value = o
    }) : e[i] = a;
  }
}
function Zu(r, e, t) {
  bt(he(r) ? r.map((s) => s.bind(e.proxy)) : r.bind(e.proxy), e, t);
}
function lf(r, e, t, s) {
  const i = s.includes(".") ? Qh(t, s) : () => t[s];
  if (Ke(r)) {
    const n = e[r];
    de(n) && ka(i, n);
  } else if (de(r))
    ka(i, r.bind(t));
  else if (je(r))
    if (he(r))
      r.forEach((n) => lf(n, e, t, s));
    else {
      const n = de(r.handler) ? r.handler.bind(t) : e[r.handler];
      de(n) && ka(i, n, r);
    }
}
function uf(r) {
  const e = r.type, { mixins: t, extends: s } = e, { mixins: i, optionsCache: n, config: { optionMergeStrategies: a } } = r.appContext, o = n.get(e);
  let l;
  return o ? l = o : !i.length && !t && !s ? l = e : (l = {}, i.length && i.forEach((u) => wn(l, u, a, !0)), wn(l, e, a)), n.set(e, l), l;
}
function wn(r, e, t, s = !1) {
  const { mixins: i, extends: n } = e;
  n && wn(r, n, t, !0), i && i.forEach((a) => wn(r, a, t, !0));
  for (const a in e)
    if (!(s && a === "expose")) {
      const o = tg[a] || t && t[a];
      r[a] = o ? o(r[a], e[a]) : e[a];
    }
  return r;
}
const tg = {
  data: ec,
  props: Dr,
  emits: Dr,
  methods: Dr,
  computed: Dr,
  beforeCreate: Ze,
  created: Ze,
  beforeMount: Ze,
  mounted: Ze,
  beforeUpdate: Ze,
  updated: Ze,
  beforeDestroy: Ze,
  beforeUnmount: Ze,
  destroyed: Ze,
  unmounted: Ze,
  activated: Ze,
  deactivated: Ze,
  errorCaptured: Ze,
  serverPrefetch: Ze,
  components: Dr,
  directives: Dr,
  watch: sg,
  provide: ec,
  inject: rg
};
function ec(r, e) {
  return e ? r ? function() {
    return He(de(r) ? r.call(this, this) : r, de(e) ? e.call(this, this) : e);
  } : e : r;
}
function rg(r, e) {
  return Dr(vo(r), vo(e));
}
function vo(r) {
  if (he(r)) {
    const e = {};
    for (let t = 0; t < r.length; t++)
      e[r[t]] = r[t];
    return e;
  }
  return r;
}
function Ze(r, e) {
  return r ? [...new Set([].concat(r, e))] : e;
}
function Dr(r, e) {
  return r ? He(He(/* @__PURE__ */ Object.create(null), r), e) : e;
}
function sg(r, e) {
  if (!r)
    return e;
  if (!e)
    return r;
  const t = He(/* @__PURE__ */ Object.create(null), r);
  for (const s in e)
    t[s] = Ze(r[s], e[s]);
  return t;
}
function ig(r, e, t, s = !1) {
  const i = {}, n = {};
  vn(n, oa, 1), r.propsDefaults = /* @__PURE__ */ Object.create(null), cf(r, e, i, n);
  for (const a in r.propsOptions[0])
    a in i || (i[a] = void 0);
  t ? r.props = s ? i : yy(i) : r.type.props ? r.props = i : r.props = n, r.attrs = n;
}
function ng(r, e, t, s) {
  const { props: i, attrs: n, vnode: { patchFlag: a } } = r, o = Ce(i), [l] = r.propsOptions;
  let u = !1;
  if ((s || a > 0) && !(a & 16)) {
    if (a & 8) {
      const c = r.vnode.dynamicProps;
      for (let h = 0; h < c.length; h++) {
        let f = c[h];
        if (ia(r.emitsOptions, f))
          continue;
        const p = e[f];
        if (l)
          if (we(n, f))
            p !== n[f] && (n[f] = p, u = !0);
          else {
            const x = qt(f);
            i[x] = bo(l, o, x, p, r, !1);
          }
        else
          p !== n[f] && (n[f] = p, u = !0);
      }
    }
  } else {
    cf(r, e, i, n) && (u = !0);
    let c;
    for (const h in o)
      (!e || !we(e, h) && ((c = Cs(h)) === h || !we(e, c))) && (l ? t && (t[h] !== void 0 || t[c] !== void 0) && (i[h] = bo(l, o, h, void 0, r, !0)) : delete i[h]);
    if (n !== o)
      for (const h in n)
        (!e || !we(e, h) && !0) && (delete n[h], u = !0);
  }
  u && rr(r, "set", "$attrs");
}
function cf(r, e, t, s) {
  const [i, n] = r.propsOptions;
  let a = !1, o;
  if (e)
    for (let l in e) {
      if (nn(l))
        continue;
      const u = e[l];
      let c;
      i && we(i, c = qt(l)) ? !n || !n.includes(c) ? t[c] = u : (o || (o = {}))[c] = u : ia(r.emitsOptions, l) || (!(l in s) || u !== s[l]) && (s[l] = u, a = !0);
    }
  if (n) {
    const l = Ce(t), u = o || Me;
    for (let c = 0; c < n.length; c++) {
      const h = n[c];
      t[h] = bo(i, l, h, u[h], r, !we(u, h));
    }
  }
  return a;
}
function bo(r, e, t, s, i, n) {
  const a = r[t];
  if (a != null) {
    const o = we(a, "default");
    if (o && s === void 0) {
      const l = a.default;
      if (a.type !== Function && de(l)) {
        const { propsDefaults: u } = i;
        t in u ? s = u[t] : (bs(i), s = u[t] = l.call(null, e), Hr());
      } else
        s = l;
    }
    a[0] && (n && !o ? s = !1 : a[1] && (s === "" || s === Cs(t)) && (s = !0));
  }
  return s;
}
function hf(r, e, t = !1) {
  const s = e.propsCache, i = s.get(r);
  if (i)
    return i;
  const n = r.props, a = {}, o = [];
  let l = !1;
  if (!de(r)) {
    const c = (h) => {
      l = !0;
      const [f, p] = hf(h, e, !0);
      He(a, f), p && o.push(...p);
    };
    !t && e.mixins.length && e.mixins.forEach(c), r.extends && c(r.extends), r.mixins && r.mixins.forEach(c);
  }
  if (!n && !l)
    return s.set(r, us), us;
  if (he(n))
    for (let c = 0; c < n.length; c++) {
      const h = qt(n[c]);
      tc(h) && (a[h] = Me);
    }
  else if (n)
    for (const c in n) {
      const h = qt(c);
      if (tc(h)) {
        const f = n[c], p = a[h] = he(f) || de(f) ? { type: f } : f;
        if (p) {
          const x = ic(Boolean, p.type), d = ic(String, p.type);
          p[0] = x > -1, p[1] = d < 0 || x < d, (x > -1 || we(p, "default")) && o.push(h);
        }
      }
    }
  const u = [a, o];
  return s.set(r, u), u;
}
function tc(r) {
  return r[0] !== "$";
}
function rc(r) {
  const e = r && r.toString().match(/^\s*function (\w+)/);
  return e ? e[1] : r === null ? "null" : "";
}
function sc(r, e) {
  return rc(r) === rc(e);
}
function ic(r, e) {
  return he(e) ? e.findIndex((t) => sc(t, r)) : de(e) && sc(e, r) ? 0 : -1;
}
const ff = (r) => r[0] === "_" || r === "$stable", El = (r) => he(r) ? r.map(Ft) : [Ft(r)], ag = (r, e, t) => {
  if (e._n)
    return e;
  const s = ei((...i) => El(e(...i)), t);
  return s._c = !1, s;
}, pf = (r, e, t) => {
  const s = r._ctx;
  for (const i in r) {
    if (ff(i))
      continue;
    const n = r[i];
    if (de(n))
      e[i] = ag(i, n, s);
    else if (n != null) {
      const a = El(n);
      e[i] = () => a;
    }
  }
}, df = (r, e) => {
  const t = El(e);
  r.slots.default = () => t;
}, og = (r, e) => {
  if (r.vnode.shapeFlag & 32) {
    const t = e._;
    t ? (r.slots = Ce(e), vn(e, "_", t)) : pf(e, r.slots = {});
  } else
    r.slots = {}, e && df(r, e);
  vn(r.slots, oa, 1);
}, lg = (r, e, t) => {
  const { vnode: s, slots: i } = r;
  let n = !0, a = Me;
  if (s.shapeFlag & 32) {
    const o = e._;
    o ? t && o === 1 ? n = !1 : (He(i, e), !t && o === 1 && delete i._) : (n = !e.$stable, pf(e, i)), a = e;
  } else
    e && (df(r, e), a = { default: 1 });
  if (n)
    for (const o in i)
      !ff(o) && !(o in a) && delete i[o];
};
function mf() {
  return {
    app: null,
    config: {
      isNativeTag: Rm,
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
let ug = 0;
function cg(r, e) {
  return function(s, i = null) {
    de(s) || (s = Object.assign({}, s)), i != null && !je(i) && (i = null);
    const n = mf(), a = /* @__PURE__ */ new Set();
    let o = !1;
    const l = n.app = {
      _uid: ug++,
      _component: s,
      _props: i,
      _container: null,
      _context: n,
      _instance: null,
      version: Og,
      get config() {
        return n.config;
      },
      set config(u) {
      },
      use(u, ...c) {
        return a.has(u) || (u && de(u.install) ? (a.add(u), u.install(l, ...c)) : de(u) && (a.add(u), u(l, ...c))), l;
      },
      mixin(u) {
        return n.mixins.includes(u) || n.mixins.push(u), l;
      },
      component(u, c) {
        return c ? (n.components[u] = c, l) : n.components[u];
      },
      directive(u, c) {
        return c ? (n.directives[u] = c, l) : n.directives[u];
      },
      mount(u, c, h) {
        if (!o) {
          const f = st(s, i);
          return f.appContext = n, c && e ? e(f, u) : r(f, u, h), o = !0, l._container = u, u.__vue_app__ = l, la(f.component) || f.component.proxy;
        }
      },
      unmount() {
        o && (r(null, l._container), delete l._container.__vue_app__);
      },
      provide(u, c) {
        return n.provides[u] = c, l;
      }
    };
    return l;
  };
}
function xo(r, e, t, s, i = !1) {
  if (he(r)) {
    r.forEach((f, p) => xo(f, e && (he(e) ? e[p] : e), t, s, i));
    return;
  }
  if (ti(s) && !i)
    return;
  const n = s.shapeFlag & 4 ? la(s.component) || s.component.proxy : s.el, a = i ? null : n, { i: o, r: l } = r, u = e && e.r, c = o.refs === Me ? o.refs = {} : o.refs, h = o.setupState;
  if (u != null && u !== l && (Ke(u) ? (c[u] = null, we(h, u) && (h[u] = null)) : et(u) && (u.value = null)), de(l))
    Sr(l, o, 12, [a, c]);
  else {
    const f = Ke(l), p = et(l);
    if (f || p) {
      const x = () => {
        if (r.f) {
          const d = f ? c[l] : l.value;
          i ? he(d) && hl(d, n) : he(d) ? d.includes(n) || d.push(n) : f ? (c[l] = [n], we(h, l) && (h[l] = c[l])) : (l.value = [n], r.k && (c[r.k] = l.value));
        } else
          f ? (c[l] = a, we(h, l) && (h[l] = a)) : p && (l.value = a, r.k && (c[r.k] = a));
      };
      a ? (x.id = -1, tt(x, t)) : x();
    }
  }
}
const tt = Dy;
function hg(r) {
  return fg(r);
}
function fg(r, e) {
  const t = qm();
  t.__VUE__ = !0;
  const { insert: s, remove: i, patchProp: n, createElement: a, createText: o, createComment: l, setText: u, setElementText: c, parentNode: h, nextSibling: f, setScopeId: p = It, cloneNode: x, insertStaticContent: d } = r, m = (b, A, M, R = null, L = null, B = null, z = !1, $ = null, q = !!A.dynamicChildren) => {
    if (b === A)
      return;
    b && !Rr(b, A) && (R = Ee(b), k(b, L, B, !0), b = null), A.patchFlag === -2 && (q = !1, A.dynamicChildren = null);
    const { type: U, ref: ee, shapeFlag: X } = A;
    switch (U) {
      case Tl:
        y(b, A, M, R);
        break;
      case xt:
        _(b, A, M, R);
        break;
      case Da:
        b == null && T(A, M, R, z);
        break;
      case lt:
        W(b, A, M, R, L, B, z, $, q);
        break;
      default:
        X & 1 ? w(b, A, M, R, L, B, z, $, q) : X & 6 ? Q(b, A, M, R, L, B, z, $, q) : (X & 64 || X & 128) && U.process(b, A, M, R, L, B, z, $, q, ae);
    }
    ee != null && L && xo(ee, b && b.ref, B, A || b, !A);
  }, y = (b, A, M, R) => {
    if (b == null)
      s(A.el = o(A.children), M, R);
    else {
      const L = A.el = b.el;
      A.children !== b.children && u(L, A.children);
    }
  }, _ = (b, A, M, R) => {
    b == null ? s(A.el = l(A.children || ""), M, R) : A.el = b.el;
  }, T = (b, A, M, R) => {
    [b.el, b.anchor] = d(b.children, A, M, R, b.el, b.anchor);
  }, C = ({ el: b, anchor: A }, M, R) => {
    let L;
    for (; b && b !== A; )
      L = f(b), s(b, M, R), b = L;
    s(A, M, R);
  }, v = ({ el: b, anchor: A }) => {
    let M;
    for (; b && b !== A; )
      M = f(b), i(b), b = M;
    i(A);
  }, w = (b, A, M, R, L, B, z, $, q) => {
    z = z || A.type === "svg", b == null ? N(A, M, R, L, B, z, $, q) : E(b, A, L, B, z, $, q);
  }, N = (b, A, M, R, L, B, z, $) => {
    let q, U;
    const { type: ee, props: X, shapeFlag: te, transition: ue, patchFlag: ge, dirs: Ae } = b;
    if (b.el && x !== void 0 && ge === -1)
      q = b.el = x(b.el);
    else {
      if (q = b.el = a(b.type, B, X && X.is, X), te & 8 ? c(q, b.children) : te & 16 && g(b.children, q, null, R, L, B && ee !== "foreignObject", z, $), Ae && Nr(b, null, R, "created"), X) {
        for (const Oe in X)
          Oe !== "value" && !nn(Oe) && n(q, Oe, null, X[Oe], B, b.children, R, L, oe);
        "value" in X && n(q, "value", null, X.value), (U = X.onVnodeBeforeMount) && Lt(U, R, b);
      }
      P(q, b, b.scopeId, z, R);
    }
    Ae && Nr(b, null, R, "beforeMount");
    const _e = (!L || L && !L.pendingBranch) && ue && !ue.persisted;
    _e && ue.beforeEnter(q), s(q, A, M), ((U = X && X.onVnodeMounted) || _e || Ae) && tt(() => {
      U && Lt(U, R, b), _e && ue.enter(q), Ae && Nr(b, null, R, "mounted");
    }, L);
  }, P = (b, A, M, R, L) => {
    if (M && p(b, M), R)
      for (let B = 0; B < R.length; B++)
        p(b, R[B]);
    if (L) {
      let B = L.subTree;
      if (A === B) {
        const z = L.vnode;
        P(b, z, z.scopeId, z.slotScopeIds, L.parent);
      }
    }
  }, g = (b, A, M, R, L, B, z, $, q = 0) => {
    for (let U = q; U < b.length; U++) {
      const ee = b[U] = $ ? vr(b[U]) : Ft(b[U]);
      m(null, ee, A, M, R, L, B, z, $);
    }
  }, E = (b, A, M, R, L, B, z) => {
    const $ = A.el = b.el;
    let { patchFlag: q, dynamicChildren: U, dirs: ee } = A;
    q |= b.patchFlag & 16;
    const X = b.props || Me, te = A.props || Me;
    let ue;
    M && Or(M, !1), (ue = te.onVnodeBeforeUpdate) && Lt(ue, M, A, b), ee && Nr(A, b, M, "beforeUpdate"), M && Or(M, !0);
    const ge = L && A.type !== "foreignObject";
    if (U ? O(b.dynamicChildren, U, $, M, R, ge, B) : z || qe(b, A, $, null, M, R, ge, B, !1), q > 0) {
      if (q & 16)
        S($, A, X, te, M, R, L);
      else if (q & 2 && X.class !== te.class && n($, "class", null, te.class, L), q & 4 && n($, "style", X.style, te.style, L), q & 8) {
        const Ae = A.dynamicProps;
        for (let _e = 0; _e < Ae.length; _e++) {
          const Oe = Ae[_e], Pt = X[Oe], Jr = te[Oe];
          (Jr !== Pt || Oe === "value") && n($, Oe, Pt, Jr, L, b.children, M, R, oe);
        }
      }
      q & 1 && b.children !== A.children && c($, A.children);
    } else
      !z && U == null && S($, A, X, te, M, R, L);
    ((ue = te.onVnodeUpdated) || ee) && tt(() => {
      ue && Lt(ue, M, A, b), ee && Nr(A, b, M, "updated");
    }, R);
  }, O = (b, A, M, R, L, B, z) => {
    for (let $ = 0; $ < A.length; $++) {
      const q = b[$], U = A[$], ee = q.el && (q.type === lt || !Rr(q, U) || q.shapeFlag & 70) ? h(q.el) : M;
      m(q, U, ee, null, R, L, B, z, !0);
    }
  }, S = (b, A, M, R, L, B, z) => {
    if (M !== R) {
      for (const $ in R) {
        if (nn($))
          continue;
        const q = R[$], U = M[$];
        q !== U && $ !== "value" && n(b, $, U, q, z, A.children, L, B, oe);
      }
      if (M !== Me)
        for (const $ in M)
          !nn($) && !($ in R) && n(b, $, M[$], null, z, A.children, L, B, oe);
      "value" in R && n(b, "value", M.value, R.value);
    }
  }, W = (b, A, M, R, L, B, z, $, q) => {
    const U = A.el = b ? b.el : o(""), ee = A.anchor = b ? b.anchor : o("");
    let { patchFlag: X, dynamicChildren: te, slotScopeIds: ue } = A;
    ue && ($ = $ ? $.concat(ue) : ue), b == null ? (s(U, M, R), s(ee, M, R), g(A.children, M, ee, L, B, z, $, q)) : X > 0 && X & 64 && te && b.dynamicChildren ? (O(b.dynamicChildren, te, M, L, B, z, $), (A.key != null || L && A === L.subTree) && yf(b, A, !0)) : qe(b, A, M, ee, L, B, z, $, q);
  }, Q = (b, A, M, R, L, B, z, $, q) => {
    A.slotScopeIds = $, b == null ? A.shapeFlag & 512 ? L.ctx.activate(A, M, R, z, q) : xe(A, M, R, L, B, z, q) : re(b, A, q);
  }, xe = (b, A, M, R, L, B, z) => {
    const $ = b.component = Sg(b, R, L);
    if (na(b) && ($.ctx.renderer = ae), Pg($), $.asyncDep) {
      if (L && L.registerDep($, J), !b.el) {
        const q = $.subTree = st(xt);
        _(null, q, A, M);
      }
      return;
    }
    J($, b, A, M, L, B, z);
  }, re = (b, A, M) => {
    const R = A.component = b.component;
    if (ky(b, A, M))
      if (R.asyncDep && !R.asyncResolved) {
        ce(R, A, M);
        return;
      } else
        R.next = A, Ay(R.update), R.update();
    else
      A.el = b.el, R.vnode = A;
  }, J = (b, A, M, R, L, B, z) => {
    const $ = () => {
      if (b.isMounted) {
        let { next: ee, bu: X, u: te, parent: ue, vnode: ge } = b, Ae = ee, _e;
        Or(b, !1), ee ? (ee.el = ge.el, ce(b, ee, z)) : ee = ge, X && Ia(X), (_e = ee.props && ee.props.onVnodeBeforeUpdate) && Lt(_e, ue, ee, ge), Or(b, !0);
        const Oe = Na(b), Pt = b.subTree;
        b.subTree = Oe, m(
          Pt,
          Oe,
          h(Pt.el),
          Ee(Pt),
          b,
          L,
          B
        ), ee.el = Oe.el, Ae === null && My(b, Oe.el), te && tt(te, L), (_e = ee.props && ee.props.onVnodeUpdated) && tt(() => Lt(_e, ue, ee, ge), L);
      } else {
        let ee;
        const { el: X, props: te } = A, { bm: ue, m: ge, parent: Ae } = b, _e = ti(A);
        if (Or(b, !1), ue && Ia(ue), !_e && (ee = te && te.onVnodeBeforeMount) && Lt(ee, Ae, A), Or(b, !0), X && Be) {
          const Oe = () => {
            b.subTree = Na(b), Be(X, b.subTree, b, L, null);
          };
          _e ? A.type.__asyncLoader().then(
            () => !b.isUnmounted && Oe()
          ) : Oe();
        } else {
          const Oe = b.subTree = Na(b);
          m(null, Oe, M, R, b, L, B), A.el = Oe.el;
        }
        if (ge && tt(ge, L), !_e && (ee = te && te.onVnodeMounted)) {
          const Oe = A;
          tt(() => Lt(ee, Ae, Oe), L);
        }
        (A.shapeFlag & 256 || Ae && ti(Ae.vnode) && Ae.vnode.shapeFlag & 256) && b.a && tt(b.a, L), b.isMounted = !0, A = M = R = null;
      }
    }, q = b.effect = new ml(
      $,
      () => Vh(U),
      b.scope
    ), U = b.update = () => q.run();
    U.id = b.uid, Or(b, !0), U();
  }, ce = (b, A, M) => {
    A.component = b;
    const R = b.vnode.props;
    b.vnode = A, b.next = null, ng(b, A.props, R, M), lg(b, A.children, M), Is(), sa(void 0, b.update), Ns();
  }, qe = (b, A, M, R, L, B, z, $, q = !1) => {
    const U = b && b.children, ee = b ? b.shapeFlag : 0, X = A.children, { patchFlag: te, shapeFlag: ue } = A;
    if (te > 0) {
      if (te & 128) {
        H(U, X, M, R, L, B, z, $, q);
        return;
      } else if (te & 256) {
        G(U, X, M, R, L, B, z, $, q);
        return;
      }
    }
    ue & 8 ? (ee & 16 && oe(U, L, B), X !== U && c(M, X)) : ee & 16 ? ue & 16 ? H(U, X, M, R, L, B, z, $, q) : oe(U, L, B, !0) : (ee & 8 && c(M, ""), ue & 16 && g(X, M, R, L, B, z, $, q));
  }, G = (b, A, M, R, L, B, z, $, q) => {
    b = b || us, A = A || us;
    const U = b.length, ee = A.length, X = Math.min(U, ee);
    let te;
    for (te = 0; te < X; te++) {
      const ue = A[te] = q ? vr(A[te]) : Ft(A[te]);
      m(b[te], ue, M, null, L, B, z, $, q);
    }
    U > ee ? oe(b, L, B, !0, !1, X) : g(A, M, R, L, B, z, $, q, X);
  }, H = (b, A, M, R, L, B, z, $, q) => {
    let U = 0;
    const ee = A.length;
    let X = b.length - 1, te = ee - 1;
    for (; U <= X && U <= te; ) {
      const ue = b[U], ge = A[U] = q ? vr(A[U]) : Ft(A[U]);
      if (Rr(ue, ge))
        m(ue, ge, M, null, L, B, z, $, q);
      else
        break;
      U++;
    }
    for (; U <= X && U <= te; ) {
      const ue = b[X], ge = A[te] = q ? vr(A[te]) : Ft(A[te]);
      if (Rr(ue, ge))
        m(ue, ge, M, null, L, B, z, $, q);
      else
        break;
      X--, te--;
    }
    if (U > X) {
      if (U <= te) {
        const ue = te + 1, ge = ue < ee ? A[ue].el : R;
        for (; U <= te; )
          m(null, A[U] = q ? vr(A[U]) : Ft(A[U]), M, ge, L, B, z, $, q), U++;
      }
    } else if (U > te)
      for (; U <= X; )
        k(b[U], L, B, !0), U++;
    else {
      const ue = U, ge = U, Ae = /* @__PURE__ */ new Map();
      for (U = ge; U <= te; U++) {
        const nt = A[U] = q ? vr(A[U]) : Ft(A[U]);
        nt.key != null && Ae.set(nt.key, U);
      }
      let _e, Oe = 0;
      const Pt = te - ge + 1;
      let Jr = !1, Lu = 0;
      const Rs = new Array(Pt);
      for (U = 0; U < Pt; U++)
        Rs[U] = 0;
      for (U = ue; U <= X; U++) {
        const nt = b[U];
        if (Oe >= Pt) {
          k(nt, L, B, !0);
          continue;
        }
        let Mt;
        if (nt.key != null)
          Mt = Ae.get(nt.key);
        else
          for (_e = ge; _e <= te; _e++)
            if (Rs[_e - ge] === 0 && Rr(nt, A[_e])) {
              Mt = _e;
              break;
            }
        Mt === void 0 ? k(nt, L, B, !0) : (Rs[Mt - ge] = U + 1, Mt >= Lu ? Lu = Mt : Jr = !0, m(nt, A[Mt], M, null, L, B, z, $, q), Oe++);
      }
      const Du = Jr ? pg(Rs) : us;
      for (_e = Du.length - 1, U = Pt - 1; U >= 0; U--) {
        const nt = ge + U, Mt = A[nt], Ru = nt + 1 < ee ? A[nt + 1].el : R;
        Rs[U] === 0 ? m(null, Mt, M, Ru, L, B, z, $, q) : Jr && (_e < 0 || U !== Du[_e] ? K(Mt, M, Ru, 2) : _e--);
      }
    }
  }, K = (b, A, M, R, L = null) => {
    const { el: B, type: z, transition: $, children: q, shapeFlag: U } = b;
    if (U & 6) {
      K(b.component.subTree, A, M, R);
      return;
    }
    if (U & 128) {
      b.suspense.move(A, M, R);
      return;
    }
    if (U & 64) {
      z.move(b, A, M, ae);
      return;
    }
    if (z === lt) {
      s(B, A, M);
      for (let X = 0; X < q.length; X++)
        K(q[X], A, M, R);
      s(b.anchor, A, M);
      return;
    }
    if (z === Da) {
      C(b, A, M);
      return;
    }
    if (R !== 2 && U & 1 && $)
      if (R === 0)
        $.beforeEnter(B), s(B, A, M), tt(() => $.enter(B), L);
      else {
        const { leave: X, delayLeave: te, afterLeave: ue } = $, ge = () => s(B, A, M), Ae = () => {
          X(B, () => {
            ge(), ue && ue();
          });
        };
        te ? te(B, ge, Ae) : Ae();
      }
    else
      s(B, A, M);
  }, k = (b, A, M, R = !1, L = !1) => {
    const { type: B, props: z, ref: $, children: q, dynamicChildren: U, shapeFlag: ee, patchFlag: X, dirs: te } = b;
    if ($ != null && xo($, null, M, b, !0), ee & 256) {
      A.ctx.deactivate(b);
      return;
    }
    const ue = ee & 1 && te, ge = !ti(b);
    let Ae;
    if (ge && (Ae = z && z.onVnodeBeforeUnmount) && Lt(Ae, A, b), ee & 6)
      D(b.component, M, R);
    else {
      if (ee & 128) {
        b.suspense.unmount(M, R);
        return;
      }
      ue && Nr(b, null, A, "beforeUnmount"), ee & 64 ? b.type.remove(b, A, M, L, ae, R) : U && (B !== lt || X > 0 && X & 64) ? oe(U, A, M, !1, !0) : (B === lt && X & 384 || !L && ee & 16) && oe(q, A, M), R && V(b);
    }
    (ge && (Ae = z && z.onVnodeUnmounted) || ue) && tt(() => {
      Ae && Lt(Ae, A, b), ue && Nr(b, null, A, "unmounted");
    }, M);
  }, V = (b) => {
    const { type: A, el: M, anchor: R, transition: L } = b;
    if (A === lt) {
      le(M, R);
      return;
    }
    if (A === Da) {
      v(b);
      return;
    }
    const B = () => {
      i(M), L && !L.persisted && L.afterLeave && L.afterLeave();
    };
    if (b.shapeFlag & 1 && L && !L.persisted) {
      const { leave: z, delayLeave: $ } = L, q = () => z(M, B);
      $ ? $(b.el, B, q) : q();
    } else
      B();
  }, le = (b, A) => {
    let M;
    for (; b !== A; )
      M = f(b), i(b), b = M;
    i(A);
  }, D = (b, A, M) => {
    const { bum: R, scope: L, update: B, subTree: z, um: $ } = b;
    R && Ia(R), L.stop(), B && (B.active = !1, k(z, b, A, M)), $ && tt($, A), tt(() => {
      b.isUnmounted = !0;
    }, A), A && A.pendingBranch && !A.isUnmounted && b.asyncDep && !b.asyncResolved && b.suspenseId === A.pendingId && (A.deps--, A.deps === 0 && A.resolve());
  }, oe = (b, A, M, R = !1, L = !1, B = 0) => {
    for (let z = B; z < b.length; z++)
      k(b[z], A, M, R, L);
  }, Ee = (b) => b.shapeFlag & 6 ? Ee(b.component.subTree) : b.shapeFlag & 128 ? b.suspense.next() : f(b.anchor || b.el), ye = (b, A, M) => {
    b == null ? A._vnode && k(A._vnode, null, null, !0) : m(A._vnode || null, b, A, null, null, null, M), Hh(), A._vnode = b;
  }, ae = {
    p: m,
    um: k,
    m: K,
    r: V,
    mt: xe,
    mc: g,
    pc: qe,
    pbc: O,
    n: Ee,
    o: r
  };
  let Te, Be;
  return e && ([Te, Be] = e(ae)), {
    render: ye,
    hydrate: Te,
    createApp: cg(ye, Te)
  };
}
function Or({ effect: r, update: e }, t) {
  r.allowRecurse = e.allowRecurse = t;
}
function yf(r, e, t = !1) {
  const s = r.children, i = e.children;
  if (he(s) && he(i))
    for (let n = 0; n < s.length; n++) {
      const a = s[n];
      let o = i[n];
      o.shapeFlag & 1 && !o.dynamicChildren && ((o.patchFlag <= 0 || o.patchFlag === 32) && (o = i[n] = vr(i[n]), o.el = a.el), t || yf(a, o));
    }
}
function pg(r) {
  const e = r.slice(), t = [0];
  let s, i, n, a, o;
  const l = r.length;
  for (s = 0; s < l; s++) {
    const u = r[s];
    if (u !== 0) {
      if (i = t[t.length - 1], r[i] < u) {
        e[s] = i, t.push(s);
        continue;
      }
      for (n = 0, a = t.length - 1; n < a; )
        o = n + a >> 1, r[t[o]] < u ? n = o + 1 : a = o;
      u < r[t[n]] && (n > 0 && (e[s] = t[n - 1]), t[n] = s);
    }
  }
  for (n = t.length, a = t[n - 1]; n-- > 0; )
    t[n] = a, a = e[a];
  return t;
}
const dg = (r) => r.__isTeleport, lt = Symbol(void 0), Tl = Symbol(void 0), xt = Symbol(void 0), Da = Symbol(void 0), ri = [];
let At = null;
function Tt(r = !1) {
  ri.push(At = r ? null : []);
}
function mg() {
  ri.pop(), At = ri[ri.length - 1] || null;
}
let di = 1;
function nc(r) {
  di += r;
}
function gf(r) {
  return r.dynamicChildren = di > 0 ? At || us : null, mg(), di > 0 && At && At.push(r), r;
}
function mi(r, e, t, s, i, n) {
  return gf(Je(r, e, t, s, i, n, !0));
}
function fs(r, e, t, s, i) {
  return gf(st(r, e, t, s, i, !0));
}
function Pn(r) {
  return r ? r.__v_isVNode === !0 : !1;
}
function Rr(r, e) {
  return r.type === e.type && r.key === e.key;
}
const oa = "__vInternal", vf = ({ key: r }) => r != null ? r : null, an = ({ ref: r, ref_key: e, ref_for: t }) => r != null ? Ke(r) || et(r) || de(r) ? { i: Qe, r, k: e, f: !!t } : r : null;
function Je(r, e = null, t = null, s = 0, i = null, n = r === lt ? 0 : 1, a = !1, o = !1) {
  const l = {
    __v_isVNode: !0,
    __v_skip: !0,
    type: r,
    props: e,
    key: e && vf(e),
    ref: e && an(e),
    scopeId: Yh,
    slotScopeIds: null,
    children: t,
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
    shapeFlag: n,
    patchFlag: s,
    dynamicProps: i,
    dynamicChildren: null,
    appContext: null
  };
  return o ? (Al(l, t), n & 128 && r.normalize(l)) : t && (l.shapeFlag |= Ke(t) ? 8 : 16), di > 0 && !a && At && (l.patchFlag > 0 || n & 6) && l.patchFlag !== 32 && At.push(l), l;
}
const st = yg;
function yg(r, e = null, t = null, s = 0, i = null, n = !1) {
  if ((!r || r === Jy) && (r = xt), Pn(r)) {
    const o = Er(r, e, !0);
    return t && Al(o, t), di > 0 && !n && At && (o.shapeFlag & 6 ? At[At.indexOf(r)] = o : At.push(o)), o.patchFlag |= -2, o;
  }
  if (Cg(r) && (r = r.__vccOpts), e) {
    e = gg(e);
    let { class: o, style: l } = e;
    o && !Ke(o) && (e.class = ul(o)), je(l) && (Uh(l) && !he(l) && (l = He({}, l)), e.style = ll(l));
  }
  const a = Ke(r) ? 1 : Ly(r) ? 128 : dg(r) ? 64 : je(r) ? 4 : de(r) ? 2 : 0;
  return Je(r, e, t, s, i, a, n, !0);
}
function gg(r) {
  return r ? Uh(r) || oa in r ? He({}, r) : r : null;
}
function Er(r, e, t = !1) {
  const { props: s, ref: i, patchFlag: n, children: a } = r, o = e ? vg(s || {}, e) : s;
  return {
    __v_isVNode: !0,
    __v_skip: !0,
    type: r.type,
    props: o,
    key: o && vf(o),
    ref: e && e.ref ? t && i ? he(i) ? i.concat(an(e)) : [i, an(e)] : an(e) : i,
    scopeId: r.scopeId,
    slotScopeIds: r.slotScopeIds,
    children: a,
    target: r.target,
    targetAnchor: r.targetAnchor,
    staticCount: r.staticCount,
    shapeFlag: r.shapeFlag,
    patchFlag: e && r.type !== lt ? n === -1 ? 16 : n | 16 : n,
    dynamicProps: r.dynamicProps,
    dynamicChildren: r.dynamicChildren,
    appContext: r.appContext,
    dirs: r.dirs,
    transition: r.transition,
    component: r.component,
    suspense: r.suspense,
    ssContent: r.ssContent && Er(r.ssContent),
    ssFallback: r.ssFallback && Er(r.ssFallback),
    el: r.el,
    anchor: r.anchor
  };
}
function Mi(r = " ", e = 0) {
  return st(Tl, null, r, e);
}
function Ys(r = "", e = !1) {
  return e ? (Tt(), fs(xt, null, r)) : st(xt, null, r);
}
function Ft(r) {
  return r == null || typeof r == "boolean" ? st(xt) : he(r) ? st(
    lt,
    null,
    r.slice()
  ) : typeof r == "object" ? vr(r) : st(Tl, null, String(r));
}
function vr(r) {
  return r.el === null || r.memo ? r : Er(r);
}
function Al(r, e) {
  let t = 0;
  const { shapeFlag: s } = r;
  if (e == null)
    e = null;
  else if (he(e))
    t = 16;
  else if (typeof e == "object")
    if (s & 65) {
      const i = e.default;
      i && (i._c && (i._d = !1), Al(r, i()), i._c && (i._d = !0));
      return;
    } else {
      t = 32;
      const i = e._;
      !i && !(oa in e) ? e._ctx = Qe : i === 3 && Qe && (Qe.slots._ === 1 ? e._ = 1 : (e._ = 2, r.patchFlag |= 1024));
    }
  else
    de(e) ? (e = { default: e, _ctx: Qe }, t = 32) : (e = String(e), s & 64 ? (t = 16, e = [Mi(e)]) : t = 8);
  r.children = e, r.shapeFlag |= t;
}
function vg(...r) {
  const e = {};
  for (let t = 0; t < r.length; t++) {
    const s = r[t];
    for (const i in s)
      if (i === "class")
        e.class !== s.class && (e.class = ul([e.class, s.class]));
      else if (i === "style")
        e.style = ll([e.style, s.style]);
      else if (Qn(i)) {
        const n = e[i], a = s[i];
        a && n !== a && !(he(n) && n.includes(a)) && (e[i] = n ? [].concat(n, a) : a);
      } else
        i !== "" && (e[i] = s[i]);
  }
  return e;
}
function Lt(r, e, t, s = null) {
  bt(r, e, 7, [
    t,
    s
  ]);
}
const bg = mf();
let xg = 0;
function Sg(r, e, t) {
  const s = r.type, i = (e ? e.appContext : r.appContext) || bg, n = {
    uid: xg++,
    vnode: r,
    type: s,
    parent: e,
    appContext: i,
    root: null,
    next: null,
    subTree: null,
    effect: null,
    update: null,
    scope: new Vm(!0),
    render: null,
    proxy: null,
    exposed: null,
    exposeProxy: null,
    withProxy: null,
    provides: e ? e.provides : Object.create(i.provides),
    accessCache: null,
    renderCache: [],
    components: null,
    directives: null,
    propsOptions: hf(s, i),
    emitsOptions: Gh(s, i),
    emit: null,
    emitted: null,
    propsDefaults: Me,
    inheritAttrs: s.inheritAttrs,
    ctx: Me,
    data: Me,
    props: Me,
    attrs: Me,
    slots: Me,
    refs: Me,
    setupState: Me,
    setupContext: null,
    suspense: t,
    suspenseId: t ? t.pendingId : 0,
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
  return n.ctx = { _: n }, n.root = e ? e.root : n, n.emit = Iy.bind(null, n), r.ce && r.ce(n), n;
}
let ze = null;
const wg = () => ze || Qe, bs = (r) => {
  ze = r, r.scope.on();
}, Hr = () => {
  ze && ze.scope.off(), ze = null;
};
function bf(r) {
  return r.vnode.shapeFlag & 4;
}
let yi = !1;
function Pg(r, e = !1) {
  yi = e;
  const { props: t, children: s } = r.vnode, i = bf(r);
  ig(r, t, i, e), og(r, s);
  const n = i ? Eg(r, e) : void 0;
  return yi = !1, n;
}
function Eg(r, e) {
  const t = r.type;
  r.accessCache = /* @__PURE__ */ Object.create(null), r.proxy = $h(new Proxy(r.ctx, Xy));
  const { setup: s } = t;
  if (s) {
    const i = r.setupContext = s.length > 1 ? Ag(r) : null;
    bs(r), Is();
    const n = Sr(s, r, 0, [r.props, i]);
    if (Ns(), Hr(), Eh(n)) {
      if (n.then(Hr, Hr), e)
        return n.then((a) => {
          ac(r, a, e);
        }).catch((a) => {
          ra(a, r, 0);
        });
      r.asyncDep = n;
    } else
      ac(r, n, e);
  } else
    xf(r, e);
}
function ac(r, e, t) {
  de(e) ? r.type.__ssrInlineRender ? r.ssrRender = e : r.render = e : je(e) && (r.setupState = jh(e)), xf(r, t);
}
let oc;
function xf(r, e, t) {
  const s = r.type;
  if (!r.render) {
    if (!e && oc && !s.render) {
      const i = s.template;
      if (i) {
        const { isCustomElement: n, compilerOptions: a } = r.appContext.config, { delimiters: o, compilerOptions: l } = s, u = He(He({
          isCustomElement: n,
          delimiters: o
        }, a), l);
        s.render = oc(i, u);
      }
    }
    r.render = s.render || It;
  }
  bs(r), Is(), Zy(r), Ns(), Hr();
}
function Tg(r) {
  return new Proxy(r.attrs, {
    get(e, t) {
      return pt(r, "get", "$attrs"), e[t];
    }
  });
}
function Ag(r) {
  const e = (s) => {
    r.exposed = s || {};
  };
  let t;
  return {
    get attrs() {
      return t || (t = Tg(r));
    },
    slots: r.slots,
    emit: r.emit,
    expose: e
  };
}
function la(r) {
  if (r.exposed)
    return r.exposeProxy || (r.exposeProxy = new Proxy(jh($h(r.exposed)), {
      get(e, t) {
        if (t in e)
          return e[t];
        if (t in Sn)
          return Sn[t](r);
      }
    }));
}
function _g(r, e = !0) {
  return de(r) ? r.displayName || r.name : r.name || e && r.__name;
}
function Cg(r) {
  return de(r) && "__vccOpts" in r;
}
const Ig = (r, e) => wy(r, e, yi);
function Ng(r, e, t) {
  const s = arguments.length;
  return s === 2 ? je(e) && !he(e) ? Pn(e) ? st(r, null, [e]) : st(r, e) : st(r, null, e) : (s > 3 ? t = Array.prototype.slice.call(arguments, 2) : s === 3 && Pn(t) && (t = [t]), st(r, e, t));
}
const Og = "3.2.37", kg = "http://www.w3.org/2000/svg", Fr = typeof document != "undefined" ? document : null, lc = Fr && /* @__PURE__ */ Fr.createElement("template"), Mg = {
  insert: (r, e, t) => {
    e.insertBefore(r, t || null);
  },
  remove: (r) => {
    const e = r.parentNode;
    e && e.removeChild(r);
  },
  createElement: (r, e, t, s) => {
    const i = e ? Fr.createElementNS(kg, r) : Fr.createElement(r, t ? { is: t } : void 0);
    return r === "select" && s && s.multiple != null && i.setAttribute("multiple", s.multiple), i;
  },
  createText: (r) => Fr.createTextNode(r),
  createComment: (r) => Fr.createComment(r),
  setText: (r, e) => {
    r.nodeValue = e;
  },
  setElementText: (r, e) => {
    r.textContent = e;
  },
  parentNode: (r) => r.parentNode,
  nextSibling: (r) => r.nextSibling,
  querySelector: (r) => Fr.querySelector(r),
  setScopeId(r, e) {
    r.setAttribute(e, "");
  },
  cloneNode(r) {
    const e = r.cloneNode(!0);
    return "_value" in r && (e._value = r._value), e;
  },
  insertStaticContent(r, e, t, s, i, n) {
    const a = t ? t.previousSibling : e.lastChild;
    if (i && (i === n || i.nextSibling))
      for (; e.insertBefore(i.cloneNode(!0), t), !(i === n || !(i = i.nextSibling)); )
        ;
    else {
      lc.innerHTML = s ? `<svg>${r}</svg>` : r;
      const o = lc.content;
      if (s) {
        const l = o.firstChild;
        for (; l.firstChild; )
          o.appendChild(l.firstChild);
        o.removeChild(l);
      }
      e.insertBefore(o, t);
    }
    return [
      a ? a.nextSibling : e.firstChild,
      t ? t.previousSibling : e.lastChild
    ];
  }
};
function Lg(r, e, t) {
  const s = r._vtc;
  s && (e = (e ? [e, ...s] : [...s]).join(" ")), e == null ? r.removeAttribute("class") : t ? r.setAttribute("class", e) : r.className = e;
}
function Dg(r, e, t) {
  const s = r.style, i = Ke(t);
  if (t && !i) {
    for (const n in t)
      So(s, n, t[n]);
    if (e && !Ke(e))
      for (const n in e)
        t[n] == null && So(s, n, "");
  } else {
    const n = s.display;
    i ? e !== t && (s.cssText = t) : e && r.removeAttribute("style"), "_vod" in r && (s.display = n);
  }
}
const uc = /\s*!important$/;
function So(r, e, t) {
  if (he(t))
    t.forEach((s) => So(r, e, s));
  else if (t == null && (t = ""), e.startsWith("--"))
    r.setProperty(e, t);
  else {
    const s = Rg(r, e);
    uc.test(t) ? r.setProperty(Cs(s), t.replace(uc, ""), "important") : r[s] = t;
  }
}
const cc = ["Webkit", "Moz", "ms"], Ra = {};
function Rg(r, e) {
  const t = Ra[e];
  if (t)
    return t;
  let s = qt(e);
  if (s !== "filter" && s in r)
    return Ra[e] = s;
  s = ea(s);
  for (let i = 0; i < cc.length; i++) {
    const n = cc[i] + s;
    if (n in r)
      return Ra[e] = n;
  }
  return e;
}
const hc = "http://www.w3.org/1999/xlink";
function Fg(r, e, t, s, i) {
  if (s && e.startsWith("xlink:"))
    t == null ? r.removeAttributeNS(hc, e.slice(6, e.length)) : r.setAttributeNS(hc, e, t);
  else {
    const n = km(e);
    t == null || n && !Sh(t) ? r.removeAttribute(e) : r.setAttribute(e, n ? "" : t);
  }
}
function Bg(r, e, t, s, i, n, a) {
  if (e === "innerHTML" || e === "textContent") {
    s && a(s, i, n), r[e] = t == null ? "" : t;
    return;
  }
  if (e === "value" && r.tagName !== "PROGRESS" && !r.tagName.includes("-")) {
    r._value = t;
    const l = t == null ? "" : t;
    (r.value !== l || r.tagName === "OPTION") && (r.value = l), t == null && r.removeAttribute(e);
    return;
  }
  let o = !1;
  if (t === "" || t == null) {
    const l = typeof r[e];
    l === "boolean" ? t = Sh(t) : t == null && l === "string" ? (t = "", o = !0) : l === "number" && (t = 0, o = !0);
  }
  try {
    r[e] = t;
  } catch (l) {
  }
  o && r.removeAttribute(e);
}
const [Sf, Ug] = /* @__PURE__ */ (() => {
  let r = Date.now, e = !1;
  if (typeof window != "undefined") {
    Date.now() > document.createEvent("Event").timeStamp && (r = performance.now.bind(performance));
    const t = navigator.userAgent.match(/firefox\/(\d+)/i);
    e = !!(t && Number(t[1]) <= 53);
  }
  return [r, e];
})();
let wo = 0;
const $g = /* @__PURE__ */ Promise.resolve(), jg = () => {
  wo = 0;
}, qg = () => wo || ($g.then(jg), wo = Sf());
function Vg(r, e, t, s) {
  r.addEventListener(e, t, s);
}
function zg(r, e, t, s) {
  r.removeEventListener(e, t, s);
}
function Wg(r, e, t, s, i = null) {
  const n = r._vei || (r._vei = {}), a = n[e];
  if (s && a)
    a.value = s;
  else {
    const [o, l] = Hg(e);
    if (s) {
      const u = n[e] = Kg(s, i);
      Vg(r, o, u, l);
    } else
      a && (zg(r, o, a, l), n[e] = void 0);
  }
}
const fc = /(?:Once|Passive|Capture)$/;
function Hg(r) {
  let e;
  if (fc.test(r)) {
    e = {};
    let t;
    for (; t = r.match(fc); )
      r = r.slice(0, r.length - t[0].length), e[t[0].toLowerCase()] = !0;
  }
  return [Cs(r.slice(2)), e];
}
function Kg(r, e) {
  const t = (s) => {
    const i = s.timeStamp || Sf();
    (Ug || i >= t.attached - 1) && bt(Gg(s, t.value), e, 5, [s]);
  };
  return t.value = r, t.attached = qg(), t;
}
function Gg(r, e) {
  if (he(e)) {
    const t = r.stopImmediatePropagation;
    return r.stopImmediatePropagation = () => {
      t.call(r), r._stopped = !0;
    }, e.map((s) => (i) => !i._stopped && s && s(i));
  } else
    return e;
}
const pc = /^on[a-z]/, Yg = (r, e, t, s, i = !1, n, a, o, l) => {
  e === "class" ? Lg(r, s, i) : e === "style" ? Dg(r, t, s) : Qn(e) ? cl(e) || Wg(r, e, t, s, a) : (e[0] === "." ? (e = e.slice(1), !0) : e[0] === "^" ? (e = e.slice(1), !1) : Jg(r, e, s, i)) ? Bg(r, e, s, n, a, o, l) : (e === "true-value" ? r._trueValue = s : e === "false-value" && (r._falseValue = s), Fg(r, e, s, i));
};
function Jg(r, e, t, s) {
  return s ? !!(e === "innerHTML" || e === "textContent" || e in r && pc.test(e) && de(t)) : e === "spellcheck" || e === "draggable" || e === "translate" || e === "form" || e === "list" && r.tagName === "INPUT" || e === "type" && r.tagName === "TEXTAREA" || pc.test(e) && Ke(t) ? !1 : e in r;
}
const hr = "transition", Bs = "animation", _l = (r, { slots: e }) => Ng(Xh, Qg(r), e);
_l.displayName = "Transition";
const wf = {
  name: String,
  type: String,
  css: {
    type: Boolean,
    default: !0
  },
  duration: [String, Number, Object],
  enterFromClass: String,
  enterActiveClass: String,
  enterToClass: String,
  appearFromClass: String,
  appearActiveClass: String,
  appearToClass: String,
  leaveFromClass: String,
  leaveActiveClass: String,
  leaveToClass: String
};
_l.props = /* @__PURE__ */ He({}, Xh.props, wf);
const kr = (r, e = []) => {
  he(r) ? r.forEach((t) => t(...e)) : r && r(...e);
}, dc = (r) => r ? he(r) ? r.some((e) => e.length > 1) : r.length > 1 : !1;
function Qg(r) {
  const e = {};
  for (const S in r)
    S in wf || (e[S] = r[S]);
  if (r.css === !1)
    return e;
  const { name: t = "v", type: s, duration: i, enterFromClass: n = `${t}-enter-from`, enterActiveClass: a = `${t}-enter-active`, enterToClass: o = `${t}-enter-to`, appearFromClass: l = n, appearActiveClass: u = a, appearToClass: c = o, leaveFromClass: h = `${t}-leave-from`, leaveActiveClass: f = `${t}-leave-active`, leaveToClass: p = `${t}-leave-to` } = r, x = Xg(i), d = x && x[0], m = x && x[1], { onBeforeEnter: y, onEnter: _, onEnterCancelled: T, onLeave: C, onLeaveCancelled: v, onBeforeAppear: w = y, onAppear: N = _, onAppearCancelled: P = T } = e, g = (S, W, Q) => {
    Mr(S, W ? c : o), Mr(S, W ? u : a), Q && Q();
  }, E = (S, W) => {
    S._isLeaving = !1, Mr(S, h), Mr(S, p), Mr(S, f), W && W();
  }, O = (S) => (W, Q) => {
    const xe = S ? N : _, re = () => g(W, S, Q);
    kr(xe, [W, re]), mc(() => {
      Mr(W, S ? l : n), fr(W, S ? c : o), dc(xe) || yc(W, s, d, re);
    });
  };
  return He(e, {
    onBeforeEnter(S) {
      kr(y, [S]), fr(S, n), fr(S, a);
    },
    onBeforeAppear(S) {
      kr(w, [S]), fr(S, l), fr(S, u);
    },
    onEnter: O(!1),
    onAppear: O(!0),
    onLeave(S, W) {
      S._isLeaving = !0;
      const Q = () => E(S, W);
      fr(S, h), t0(), fr(S, f), mc(() => {
        !S._isLeaving || (Mr(S, h), fr(S, p), dc(C) || yc(S, s, m, Q));
      }), kr(C, [S, Q]);
    },
    onEnterCancelled(S) {
      g(S, !1), kr(T, [S]);
    },
    onAppearCancelled(S) {
      g(S, !0), kr(P, [S]);
    },
    onLeaveCancelled(S) {
      E(S), kr(v, [S]);
    }
  });
}
function Xg(r) {
  if (r == null)
    return null;
  if (je(r))
    return [Fa(r.enter), Fa(r.leave)];
  {
    const e = Fa(r);
    return [e, e];
  }
}
function Fa(r) {
  return _h(r);
}
function fr(r, e) {
  e.split(/\s+/).forEach((t) => t && r.classList.add(t)), (r._vtc || (r._vtc = /* @__PURE__ */ new Set())).add(e);
}
function Mr(r, e) {
  e.split(/\s+/).forEach((s) => s && r.classList.remove(s));
  const { _vtc: t } = r;
  t && (t.delete(e), t.size || (r._vtc = void 0));
}
function mc(r) {
  requestAnimationFrame(() => {
    requestAnimationFrame(r);
  });
}
let Zg = 0;
function yc(r, e, t, s) {
  const i = r._endId = ++Zg, n = () => {
    i === r._endId && s();
  };
  if (t)
    return setTimeout(n, t);
  const { type: a, timeout: o, propCount: l } = e0(r, e);
  if (!a)
    return s();
  const u = a + "end";
  let c = 0;
  const h = () => {
    r.removeEventListener(u, f), n();
  }, f = (p) => {
    p.target === r && ++c >= l && h();
  };
  setTimeout(() => {
    c < l && h();
  }, o + 1), r.addEventListener(u, f);
}
function e0(r, e) {
  const t = window.getComputedStyle(r), s = (x) => (t[x] || "").split(", "), i = s(hr + "Delay"), n = s(hr + "Duration"), a = gc(i, n), o = s(Bs + "Delay"), l = s(Bs + "Duration"), u = gc(o, l);
  let c = null, h = 0, f = 0;
  e === hr ? a > 0 && (c = hr, h = a, f = n.length) : e === Bs ? u > 0 && (c = Bs, h = u, f = l.length) : (h = Math.max(a, u), c = h > 0 ? a > u ? hr : Bs : null, f = c ? c === hr ? n.length : l.length : 0);
  const p = c === hr && /\b(transform|all)(,|$)/.test(t[hr + "Property"]);
  return {
    type: c,
    timeout: h,
    propCount: f,
    hasTransform: p
  };
}
function gc(r, e) {
  for (; r.length < e.length; )
    r = r.concat(r);
  return Math.max(...e.map((t, s) => vc(t) + vc(r[s])));
}
function vc(r) {
  return Number(r.slice(0, -1).replace(",", ".")) * 1e3;
}
function t0() {
  return document.body.offsetHeight;
}
const is = {
  beforeMount(r, { value: e }, { transition: t }) {
    r._vod = r.style.display === "none" ? "" : r.style.display, t && e ? t.beforeEnter(r) : Us(r, e);
  },
  mounted(r, { value: e }, { transition: t }) {
    t && e && t.enter(r);
  },
  updated(r, { value: e, oldValue: t }, { transition: s }) {
    !e != !t && (s ? e ? (s.beforeEnter(r), Us(r, !0), s.enter(r)) : s.leave(r, () => {
      Us(r, !1);
    }) : Us(r, e));
  },
  beforeUnmount(r, { value: e }) {
    Us(r, e);
  }
};
function Us(r, e) {
  r.style.display = e ? r._vod : "none";
}
const r0 = /* @__PURE__ */ He({ patchProp: Yg }, Mg);
let bc;
function s0() {
  return bc || (bc = hg(r0));
}
const Pf = (...r) => {
  const e = s0().createApp(...r), { mount: t } = e;
  return e.mount = (s) => {
    const i = i0(s);
    if (!i)
      return;
    const n = e._component;
    !de(n) && !n.render && !n.template && (n.template = i.innerHTML), i.innerHTML = "";
    const a = t(i, !1, i instanceof SVGElement);
    return i instanceof Element && (i.removeAttribute("v-cloak"), i.setAttribute("data-v-app", "")), a;
  }, e;
};
function i0(r) {
  return Ke(r) ? document.querySelector(r) : r;
}
function or(r, e) {
  const t = /* @__PURE__ */ Object.create(null), s = r.split(",");
  for (let i = 0; i < s.length; i++)
    t[s[i]] = !0;
  return e ? (i) => !!t[i.toLowerCase()] : (i) => !!t[i];
}
const wr = {
  [1]: "TEXT",
  [2]: "CLASS",
  [4]: "STYLE",
  [8]: "PROPS",
  [16]: "FULL_PROPS",
  [32]: "HYDRATE_EVENTS",
  [64]: "STABLE_FRAGMENT",
  [128]: "KEYED_FRAGMENT",
  [256]: "UNKEYED_FRAGMENT",
  [512]: "NEED_PATCH",
  [1024]: "DYNAMIC_SLOTS",
  [2048]: "DEV_ROOT_FRAGMENT",
  [-1]: "HOISTED",
  [-2]: "BAIL"
}, n0 = {
  [1]: "STABLE",
  [2]: "DYNAMIC",
  [3]: "FORWARDED"
}, a0 = "Infinity,undefined,NaN,isFinite,isNaN,parseFloat,parseInt,decodeURI,decodeURIComponent,encodeURI,encodeURIComponent,Math,Number,Date,Array,Object,Boolean,String,RegExp,Map,Set,JSON,Intl,BigInt", Ef = /* @__PURE__ */ or(a0), o0 = /;(?![^(]*\))/g, l0 = /:(.+)/;
function u0(r) {
  const e = {};
  return r.split(o0).forEach((t) => {
    if (t) {
      const s = t.split(l0);
      s.length > 1 && (e[s[0].trim()] = s[1].trim());
    }
  }), e;
}
Object.freeze({});
Object.freeze([]);
const c0 = /^on[^a-z]/, Tf = (r) => c0.test(r), h0 = Object.assign, f0 = Object.prototype.hasOwnProperty, p0 = (r, e) => f0.call(r, e), d0 = Array.isArray, St = (r) => typeof r == "string", Af = (r) => typeof r == "symbol", _f = (r) => r !== null && typeof r == "object", xc = /* @__PURE__ */ or(
  ",key,ref,ref_for,ref_key,onVnodeBeforeMount,onVnodeMounted,onVnodeBeforeUpdate,onVnodeUpdated,onVnodeBeforeUnmount,onVnodeUnmounted"
), m0 = /* @__PURE__ */ or("bind,cloak,else-if,else,for,html,if,model,on,once,pre,show,slot,text,memo"), ua = (r) => {
  const e = /* @__PURE__ */ Object.create(null);
  return (t) => e[t] || (e[t] = r(t));
}, y0 = /-(\w)/g, xs = ua((r) => r.replace(y0, (e, t) => t ? t.toUpperCase() : "")), g0 = /\B([A-Z])/g, v0 = ua((r) => r.replace(g0, "-$1").toLowerCase()), ca = ua((r) => r.charAt(0).toUpperCase() + r.slice(1)), b0 = ua((r) => r ? `on${ca(r)}` : ""), x0 = /^[_$a-zA-Z\xA0-\uFFFF][_$a-zA-Z0-9\xA0-\uFFFF]*$/;
function Sc(r) {
  return x0.test(r) ? `__props.${r}` : `__props[${JSON.stringify(r)}]`;
}
function Fe(r, e, t, s) {
  const i = (t || S0)[r] + (s || ""), n = new SyntaxError(String(i));
  return n.code = r, n.loc = e, n;
}
const S0 = {
  [0]: "Illegal comment.",
  [1]: "CDATA section is allowed only in XML context.",
  [2]: "Duplicate attribute.",
  [3]: "End tag cannot have attributes.",
  [4]: "Illegal '/' in tags.",
  [5]: "Unexpected EOF in tag.",
  [6]: "Unexpected EOF in CDATA section.",
  [7]: "Unexpected EOF in comment.",
  [8]: "Unexpected EOF in script.",
  [9]: "Unexpected EOF in tag.",
  [10]: "Incorrectly closed comment.",
  [11]: "Incorrectly opened comment.",
  [12]: "Illegal tag name. Use '&lt;' to print '<'.",
  [13]: "Attribute value was expected.",
  [14]: "End tag name was expected.",
  [15]: "Whitespace was expected.",
  [16]: "Unexpected '<!--' in comment.",
  [17]: `Attribute name cannot contain U+0022 ("), U+0027 ('), and U+003C (<).`,
  [18]: "Unquoted attribute value cannot contain U+0022 (\"), U+0027 ('), U+003C (<), U+003D (=), and U+0060 (`).",
  [19]: "Attribute name cannot start with '='.",
  [21]: "'<?' is allowed only in XML context.",
  [20]: "Unexpected null character.",
  [22]: "Illegal '/' in tags.",
  [23]: "Invalid end tag.",
  [24]: "Element is missing end tag.",
  [25]: "Interpolation end sign was not found.",
  [27]: "End bracket for dynamic directive argument was not found. Note that dynamic directive argument cannot contain spaces.",
  [26]: "Legal directive name was expected.",
  [28]: "v-if/v-else-if is missing expression.",
  [29]: "v-if/else branches must use unique keys.",
  [30]: "v-else/v-else-if has no adjacent v-if or v-else-if.",
  [31]: "v-for is missing expression.",
  [32]: "v-for has invalid expression.",
  [33]: "<template v-for> key should be placed on the <template> tag.",
  [34]: "v-bind is missing expression.",
  [35]: "v-on is missing expression.",
  [36]: "Unexpected custom directive on <slot> outlet.",
  [37]: "Mixed v-slot usage on both the component and nested <template>.When there are multiple named slots, all slots should use <template> syntax to avoid scope ambiguity.",
  [38]: "Duplicate slot names found. ",
  [39]: "Extraneous children found when component already has explicitly named default slot. These children will be ignored.",
  [40]: "v-slot can only be used on components or <template> tags.",
  [41]: "v-model is missing expression.",
  [42]: "v-model value must be a valid JavaScript member expression.",
  [43]: "v-model cannot be used on v-for or v-slot scope variables because they are not writable.",
  [44]: "Error parsing JavaScript expression: ",
  [45]: "<KeepAlive> expects exactly one child component.",
  [46]: '"prefixIdentifiers" option is not supported in this build of compiler.',
  [47]: "ES module mode is not supported in this build of compiler.",
  [48]: '"cacheHandlers" option is only supported when the "prefixIdentifiers" option is enabled.',
  [49]: '"scopeId" option is only supported in module mode.',
  [50]: ""
}, En = Symbol("Fragment"), si = Symbol("Teleport"), Cl = Symbol("Suspense"), Tn = Symbol("KeepAlive"), Cf = Symbol("BaseTransition"), Ss = Symbol("openBlock"), If = Symbol("createBlock"), Nf = Symbol("createElementBlock"), Of = Symbol("createVNode"), kf = Symbol("createElementVNode"), Il = Symbol("createCommentVNode"), Mf = Symbol("createTextVNode"), w0 = Symbol("createStaticVNode"), Po = Symbol("resolveComponent"), An = Symbol("resolveDynamicComponent"), Lf = Symbol("resolveDirective"), P0 = Symbol("resolveFilter"), Df = Symbol("withDirectives"), Nl = Symbol("renderList"), Rf = Symbol("renderSlot"), Ff = Symbol("createSlots"), Ol = Symbol("toDisplayString"), _n = Symbol("mergeProps"), kl = Symbol("normalizeClass"), Ml = Symbol("normalizeStyle"), gi = Symbol("normalizeProps"), Li = Symbol("guardReactiveProps"), Ll = Symbol("toHandlers"), Eo = Symbol("camelize"), E0 = Symbol("capitalize"), To = Symbol("toHandlerKey"), Bf = Symbol("setBlockTracking"), T0 = Symbol("pushScopeId"), A0 = Symbol("popScopeId"), Uf = Symbol("withCtx"), Cn = Symbol("unref"), In = Symbol("isRef"), Dl = Symbol("withMemo"), $f = Symbol("isMemoSame"), _0 = {
  [En]: "Fragment",
  [si]: "Teleport",
  [Cl]: "Suspense",
  [Tn]: "KeepAlive",
  [Cf]: "BaseTransition",
  [Ss]: "openBlock",
  [If]: "createBlock",
  [Nf]: "createElementBlock",
  [Of]: "createVNode",
  [kf]: "createElementVNode",
  [Il]: "createCommentVNode",
  [Mf]: "createTextVNode",
  [w0]: "createStaticVNode",
  [Po]: "resolveComponent",
  [An]: "resolveDynamicComponent",
  [Lf]: "resolveDirective",
  [P0]: "resolveFilter",
  [Df]: "withDirectives",
  [Nl]: "renderList",
  [Rf]: "renderSlot",
  [Ff]: "createSlots",
  [Ol]: "toDisplayString",
  [_n]: "mergeProps",
  [kl]: "normalizeClass",
  [Ml]: "normalizeStyle",
  [gi]: "normalizeProps",
  [Li]: "guardReactiveProps",
  [Ll]: "toHandlers",
  [Eo]: "camelize",
  [E0]: "capitalize",
  [To]: "toHandlerKey",
  [Bf]: "setBlockTracking",
  [T0]: "pushScopeId",
  [A0]: "popScopeId",
  [Uf]: "withCtx",
  [Cn]: "unref",
  [In]: "isRef",
  [Dl]: "withMemo",
  [$f]: "isMemoSame"
};
function jf(r) {
  Object.getOwnPropertySymbols(r).forEach((e) => {
    _0[e] = r[e];
  });
}
const Ot = {
  source: "",
  start: { line: 1, column: 1, offset: 0 },
  end: { line: 1, column: 1, offset: 0 }
};
function Nn(r, e, t, s, i, n, a, o = !1, l = !1, u = !1, c = Ot) {
  return r && (o ? (r.helper(Ss), r.helper(Pi(r.inSSR, u))) : r.helper(wi(r.inSSR, u)), a && r.helper(Df)), {
    type: 13,
    tag: e,
    props: t,
    children: s,
    patchFlag: i,
    dynamicProps: n,
    directives: a,
    isBlock: o,
    disableTracking: l,
    isComponent: u,
    loc: c
  };
}
function ha(r, e = Ot) {
  return {
    type: 17,
    loc: e,
    elements: r
  };
}
function gt(r, e = Ot) {
  return {
    type: 15,
    loc: e,
    properties: r
  };
}
function Ue(r, e) {
  return {
    type: 16,
    loc: Ot,
    key: St(r) ? fe(r, !0) : r,
    value: e
  };
}
function fe(r, e = !1, t = Ot, s = 0) {
  return {
    type: 4,
    loc: t,
    content: r,
    isStatic: e,
    constType: e ? 3 : s
  };
}
function rt(r, e = Ot) {
  return {
    type: 8,
    loc: e,
    children: r
  };
}
function $e(r, e = [], t = Ot) {
  return {
    type: 14,
    loc: t,
    callee: r,
    arguments: e
  };
}
function ws(r, e = void 0, t = !1, s = !1, i = Ot) {
  return {
    type: 18,
    params: r,
    returns: e,
    newline: t,
    isSlot: s,
    loc: i
  };
}
function Ao(r, e, t, s = !0) {
  return {
    type: 19,
    test: r,
    consequent: e,
    alternate: t,
    newline: s,
    loc: Ot
  };
}
function C0(r) {
  return {
    type: 21,
    body: r,
    loc: Ot
  };
}
const Rl = /\r\n?|[\n\u2028\u2029]/, jr = new RegExp(Rl.source, "g");
function Br(r) {
  switch (r) {
    case 10:
    case 13:
    case 8232:
    case 8233:
      return !0;
    default:
      return !1;
  }
}
const Ba = /(?:\s|\/\/.*|\/\*[^]*?\*\/)*/g, I0 = /(?:[^\S\n\r\u2028\u2029]|\/\/.*|\/\*.*?\*\/)*/y, wc = new RegExp("(?=(" + I0.source + "))\\1" + /(?=[\n\r\u2028\u2029]|\/\*(?!.*?\*\/)|$)/.source, "y");
function N0(r) {
  switch (r) {
    case 9:
    case 11:
    case 12:
    case 32:
    case 160:
    case 5760:
    case 8192:
    case 8193:
    case 8194:
    case 8195:
    case 8196:
    case 8197:
    case 8198:
    case 8199:
    case 8200:
    case 8201:
    case 8202:
    case 8239:
    case 8287:
    case 12288:
    case 65279:
      return !0;
    default:
      return !1;
  }
}
class vi {
  constructor(e, t) {
    this.line = void 0, this.column = void 0, this.line = e, this.column = t;
  }
}
class On {
  constructor(e, t) {
    this.start = void 0, this.end = void 0, this.filename = void 0, this.identifierName = void 0, this.start = e, this.end = t;
  }
}
function O0(r, e) {
  let t = 1, s = 0, i;
  for (jr.lastIndex = 0; (i = jr.exec(r)) && i.index < e; )
    t++, s = jr.lastIndex;
  return new vi(t, e - s);
}
class k0 {
  constructor() {
    this.sawUnambiguousESM = !1, this.ambiguousScriptDifferentAst = !1;
  }
  hasPlugin(e) {
    return this.plugins.has(e);
  }
  getPluginOption(e, t) {
    if (this.hasPlugin(e))
      return this.plugins.get(e)[t];
  }
}
function qf(r, e) {
  r.trailingComments === void 0 ? r.trailingComments = e : r.trailingComments.unshift(...e);
}
function M0(r, e) {
  r.leadingComments === void 0 ? r.leadingComments = e : r.leadingComments.unshift(...e);
}
function bi(r, e) {
  r.innerComments === void 0 ? r.innerComments = e : r.innerComments.unshift(...e);
}
function $s(r, e, t) {
  let s = null, i = e.length;
  for (; s === null && i > 0; )
    s = e[--i];
  s === null || s.start > t.start ? bi(r, t.comments) : qf(s, t.comments);
}
class L0 extends k0 {
  addComment(e) {
    this.filename && (e.loc.filename = this.filename), this.state.comments.push(e);
  }
  processComment(e) {
    const {
      commentStack: t
    } = this.state, s = t.length;
    if (s === 0)
      return;
    let i = s - 1;
    const n = t[i];
    n.start === e.end && (n.leadingNode = e, i--);
    const {
      start: a
    } = e;
    for (; i >= 0; i--) {
      const o = t[i], l = o.end;
      if (l > a)
        o.containingNode = e, this.finalizeComment(o), t.splice(i, 1);
      else {
        l === a && (o.trailingNode = e);
        break;
      }
    }
  }
  finalizeComment(e) {
    const {
      comments: t
    } = e;
    if (e.leadingNode !== null || e.trailingNode !== null)
      e.leadingNode !== null && qf(e.leadingNode, t), e.trailingNode !== null && M0(e.trailingNode, t);
    else {
      const {
        containingNode: s,
        start: i
      } = e;
      if (this.input.charCodeAt(i - 1) === 44)
        switch (s.type) {
          case "ObjectExpression":
          case "ObjectPattern":
          case "RecordExpression":
            $s(s, s.properties, e);
            break;
          case "CallExpression":
          case "OptionalCallExpression":
            $s(s, s.arguments, e);
            break;
          case "FunctionDeclaration":
          case "FunctionExpression":
          case "ArrowFunctionExpression":
          case "ObjectMethod":
          case "ClassMethod":
          case "ClassPrivateMethod":
            $s(s, s.params, e);
            break;
          case "ArrayExpression":
          case "ArrayPattern":
          case "TupleExpression":
            $s(s, s.elements, e);
            break;
          case "ExportNamedDeclaration":
          case "ImportDeclaration":
            $s(s, s.specifiers, e);
            break;
          default:
            bi(s, t);
        }
      else
        bi(s, t);
    }
  }
  finalizeRemainingComments() {
    const {
      commentStack: e
    } = this.state;
    for (let t = e.length - 1; t >= 0; t--)
      this.finalizeComment(e[t]);
    this.state.commentStack = [];
  }
  resetPreviousNodeTrailingComments(e) {
    const {
      commentStack: t
    } = this.state, {
      length: s
    } = t;
    if (s === 0)
      return;
    const i = t[s - 1];
    i.leadingNode === e && (i.leadingNode = null);
  }
  takeSurroundingComments(e, t, s) {
    const {
      commentStack: i
    } = this.state, n = i.length;
    if (n === 0)
      return;
    let a = n - 1;
    for (; a >= 0; a--) {
      const o = i[a], l = o.end;
      if (o.start === s)
        o.leadingNode = e;
      else if (l === t)
        o.trailingNode = e;
      else if (l < t)
        break;
    }
  }
}
const Zt = Object.freeze({
  SyntaxError: "BABEL_PARSER_SYNTAX_ERROR",
  SourceTypeModuleError: "BABEL_PARSER_SOURCETYPE_MODULE_REQUIRED"
}), I = Os({
  AccessorIsGenerator: "A %0ter cannot be a generator.",
  ArgumentsInClass: "'arguments' is only allowed in functions and class methods.",
  AsyncFunctionInSingleStatementContext: "Async functions can only be declared at the top level or inside a block.",
  AwaitBindingIdentifier: "Can not use 'await' as identifier inside an async function.",
  AwaitBindingIdentifierInStaticBlock: "Can not use 'await' as identifier inside a static block.",
  AwaitExpressionFormalParameter: "'await' is not allowed in async function parameters.",
  AwaitNotInAsyncContext: "'await' is only allowed within async functions and at the top levels of modules.",
  AwaitNotInAsyncFunction: "'await' is only allowed within async functions.",
  BadGetterArity: "A 'get' accesor must not have any formal parameters.",
  BadSetterArity: "A 'set' accesor must have exactly one formal parameter.",
  BadSetterRestParameter: "A 'set' accesor function argument must not be a rest parameter.",
  ConstructorClassField: "Classes may not have a field named 'constructor'.",
  ConstructorClassPrivateField: "Classes may not have a private field named '#constructor'.",
  ConstructorIsAccessor: "Class constructor may not be an accessor.",
  ConstructorIsAsync: "Constructor can't be an async function.",
  ConstructorIsGenerator: "Constructor can't be a generator.",
  DeclarationMissingInitializer: "'%0' require an initialization value.",
  DecoratorBeforeExport: "Decorators must be placed *before* the 'export' keyword. You can set the 'decoratorsBeforeExport' option to false to use the 'export @decorator class {}' syntax.",
  DecoratorConstructor: "Decorators can't be used with a constructor. Did you mean '@dec class { ... }'?",
  DecoratorExportClass: "Using the export keyword between a decorator and a class is not allowed. Please use `export @dec class` instead.",
  DecoratorSemicolon: "Decorators must not be followed by a semicolon.",
  DecoratorStaticBlock: "Decorators can't be used with a static block.",
  DeletePrivateField: "Deleting a private field is not allowed.",
  DestructureNamedImport: "ES2015 named imports do not destructure. Use another statement for destructuring after the import.",
  DuplicateConstructor: "Duplicate constructor in the same class.",
  DuplicateDefaultExport: "Only one default export allowed per module.",
  DuplicateExport: "`%0` has already been exported. Exported identifiers must be unique.",
  DuplicateProto: "Redefinition of __proto__ property.",
  DuplicateRegExpFlags: "Duplicate regular expression flag.",
  ElementAfterRest: "Rest element must be last element.",
  EscapedCharNotAnIdentifier: "Invalid Unicode escape.",
  ExportBindingIsString: "A string literal cannot be used as an exported binding without `from`.\n- Did you mean `export { '%0' as '%1' } from 'some-module'`?",
  ExportDefaultFromAsIdentifier: "'from' is not allowed as an identifier after 'export default'.",
  ForInOfLoopInitializer: "'%0' loop variable declaration may not have an initializer.",
  ForOfAsync: "The left-hand side of a for-of loop may not be 'async'.",
  ForOfLet: "The left-hand side of a for-of loop may not start with 'let'.",
  GeneratorInSingleStatementContext: "Generators can only be declared at the top level or inside a block.",
  IllegalBreakContinue: "Unsyntactic %0.",
  IllegalLanguageModeDirective: "Illegal 'use strict' directive in function with non-simple parameter list.",
  IllegalReturn: "'return' outside of function.",
  ImportBindingIsString: 'A string literal cannot be used as an imported binding.\n- Did you mean `import { "%0" as foo }`?',
  ImportCallArgumentTrailingComma: "Trailing comma is disallowed inside import(...) arguments.",
  ImportCallArity: "`import()` requires exactly %0.",
  ImportCallNotNewExpression: "Cannot use new with import(...).",
  ImportCallSpreadArgument: "`...` is not allowed in `import()`.",
  InvalidBigIntLiteral: "Invalid BigIntLiteral.",
  InvalidCodePoint: "Code point out of bounds.",
  InvalidDecimal: "Invalid decimal.",
  InvalidDigit: "Expected number in radix %0.",
  InvalidEscapeSequence: "Bad character escape sequence.",
  InvalidEscapeSequenceTemplate: "Invalid escape sequence in template.",
  InvalidEscapedReservedWord: "Escape sequence in keyword %0.",
  InvalidIdentifier: "Invalid identifier %0.",
  InvalidLhs: "Invalid left-hand side in %0.",
  InvalidLhsBinding: "Binding invalid left-hand side in %0.",
  InvalidNumber: "Invalid number.",
  InvalidOrMissingExponent: "Floating-point numbers require a valid exponent after the 'e'.",
  InvalidOrUnexpectedToken: "Unexpected character '%0'.",
  InvalidParenthesizedAssignment: "Invalid parenthesized assignment pattern.",
  InvalidPrivateFieldResolution: "Private name #%0 is not defined.",
  InvalidPropertyBindingPattern: "Binding member expression.",
  InvalidRecordProperty: "Only properties and spread elements are allowed in record definitions.",
  InvalidRestAssignmentPattern: "Invalid rest operator's argument.",
  LabelRedeclaration: "Label '%0' is already declared.",
  LetInLexicalBinding: "'let' is not allowed to be used as a name in 'let' or 'const' declarations.",
  LineTerminatorBeforeArrow: "No line break is allowed before '=>'.",
  MalformedRegExpFlags: "Invalid regular expression flag.",
  MissingClassName: "A class name is required.",
  MissingEqInAssignment: "Only '=' operator can be used for specifying default value.",
  MissingSemicolon: "Missing semicolon.",
  MissingUnicodeEscape: "Expecting Unicode escape sequence \\uXXXX.",
  MixingCoalesceWithLogical: "Nullish coalescing operator(??) requires parens when mixing with logical operators.",
  ModuleAttributeDifferentFromType: "The only accepted module attribute is `type`.",
  ModuleAttributeInvalidValue: "Only string literals are allowed as module attribute values.",
  ModuleAttributesWithDuplicateKeys: 'Duplicate key "%0" is not allowed in module attributes.',
  ModuleExportNameHasLoneSurrogate: "An export name cannot include a lone surrogate, found '\\u%0'.",
  ModuleExportUndefined: "Export '%0' is not defined.",
  MultipleDefaultsInSwitch: "Multiple default clauses.",
  NewlineAfterThrow: "Illegal newline after throw.",
  NoCatchOrFinally: "Missing catch or finally clause.",
  NumberIdentifier: "Identifier directly after number.",
  NumericSeparatorInEscapeSequence: "Numeric separators are not allowed inside unicode escape sequences or hex escape sequences.",
  ObsoleteAwaitStar: "'await*' has been removed from the async functions proposal. Use Promise.all() instead.",
  OptionalChainingNoNew: "Constructors in/after an Optional Chain are not allowed.",
  OptionalChainingNoTemplate: "Tagged Template Literals are not allowed in optionalChain.",
  OverrideOnConstructor: "'override' modifier cannot appear on a constructor declaration.",
  ParamDupe: "Argument name clash.",
  PatternHasAccessor: "Object pattern can't contain getter or setter.",
  PatternHasMethod: "Object pattern can't contain methods.",
  PipeBodyIsTighter: "Unexpected %0 after pipeline body; any %0 expression acting as Hack-style pipe body must be parenthesized due to its loose operator precedence.",
  PipeTopicRequiresHackPipes: 'Topic reference is used, but the pipelineOperator plugin was not passed a "proposal": "hack" or "smart" option.',
  PipeTopicUnbound: "Topic reference is unbound; it must be inside a pipe body.",
  PipeTopicUnconfiguredToken: 'Invalid topic token %0. In order to use %0 as a topic reference, the pipelineOperator plugin must be configured with { "proposal": "hack", "topicToken": "%0" }.',
  PipeTopicUnused: "Hack-style pipe body does not contain a topic reference; Hack-style pipes must use topic at least once.",
  PipeUnparenthesizedBody: "Hack-style pipe body cannot be an unparenthesized %0 expression; please wrap it in parentheses.",
  PipelineBodyNoArrow: 'Unexpected arrow "=>" after pipeline body; arrow function in pipeline body must be parenthesized.',
  PipelineBodySequenceExpression: "Pipeline body may not be a comma-separated sequence expression.",
  PipelineHeadSequenceExpression: "Pipeline head should not be a comma-separated sequence expression.",
  PipelineTopicUnused: "Pipeline is in topic style but does not use topic reference.",
  PrimaryTopicNotAllowed: "Topic reference was used in a lexical context without topic binding.",
  PrimaryTopicRequiresSmartPipeline: 'Topic reference is used, but the pipelineOperator plugin was not passed a "proposal": "hack" or "smart" option.',
  PrivateInExpectedIn: "Private names are only allowed in property accesses (`obj.#%0`) or in `in` expressions (`#%0 in obj`).",
  PrivateNameRedeclaration: "Duplicate private name #%0.",
  RecordExpressionBarIncorrectEndSyntaxType: "Record expressions ending with '|}' are only allowed when the 'syntaxType' option of the 'recordAndTuple' plugin is set to 'bar'.",
  RecordExpressionBarIncorrectStartSyntaxType: "Record expressions starting with '{|' are only allowed when the 'syntaxType' option of the 'recordAndTuple' plugin is set to 'bar'.",
  RecordExpressionHashIncorrectStartSyntaxType: "Record expressions starting with '#{' are only allowed when the 'syntaxType' option of the 'recordAndTuple' plugin is set to 'hash'.",
  RecordNoProto: "'__proto__' is not allowed in Record expressions.",
  RestTrailingComma: "Unexpected trailing comma after rest element.",
  SloppyFunction: "In non-strict mode code, functions can only be declared at top level, inside a block, or as the body of an if statement.",
  StaticPrototype: "Classes may not have static property named prototype.",
  StrictDelete: "Deleting local variable in strict mode.",
  StrictEvalArguments: "Assigning to '%0' in strict mode.",
  StrictEvalArgumentsBinding: "Binding '%0' in strict mode.",
  StrictFunction: "In strict mode code, functions can only be declared at top level or inside a block.",
  StrictNumericEscape: "The only valid numeric escape in strict mode is '\\0'.",
  StrictOctalLiteral: "Legacy octal literals are not allowed in strict mode.",
  StrictWith: "'with' in strict mode.",
  SuperNotAllowed: "`super()` is only valid inside a class constructor of a subclass. Maybe a typo in the method name ('constructor') or not extending another class?",
  SuperPrivateField: "Private fields can't be accessed on super.",
  TrailingDecorator: "Decorators must be attached to a class element.",
  TupleExpressionBarIncorrectEndSyntaxType: "Tuple expressions ending with '|]' are only allowed when the 'syntaxType' option of the 'recordAndTuple' plugin is set to 'bar'.",
  TupleExpressionBarIncorrectStartSyntaxType: "Tuple expressions starting with '[|' are only allowed when the 'syntaxType' option of the 'recordAndTuple' plugin is set to 'bar'.",
  TupleExpressionHashIncorrectStartSyntaxType: "Tuple expressions starting with '#[' are only allowed when the 'syntaxType' option of the 'recordAndTuple' plugin is set to 'hash'.",
  UnexpectedArgumentPlaceholder: "Unexpected argument placeholder.",
  UnexpectedAwaitAfterPipelineBody: 'Unexpected "await" after pipeline body; await must have parentheses in minimal proposal.',
  UnexpectedDigitAfterHash: "Unexpected digit after hash token.",
  UnexpectedImportExport: "'import' and 'export' may only appear at the top level.",
  UnexpectedKeyword: "Unexpected keyword '%0'.",
  UnexpectedLeadingDecorator: "Leading decorators must be attached to a class declaration.",
  UnexpectedLexicalDeclaration: "Lexical declaration cannot appear in a single-statement context.",
  UnexpectedNewTarget: "`new.target` can only be used in functions or class properties.",
  UnexpectedNumericSeparator: "A numeric separator is only allowed between two digits.",
  UnexpectedPrivateField: `Private names can only be used as the name of a class element (i.e. class C { #p = 42; #m() {} } )
 or a property of member expression (i.e. this.#p).`,
  UnexpectedReservedWord: "Unexpected reserved word '%0'.",
  UnexpectedSuper: "'super' is only allowed in object methods and classes.",
  UnexpectedToken: "Unexpected token '%0'.",
  UnexpectedTokenUnaryExponentiation: "Illegal expression. Wrap left hand side or entire exponentiation in parentheses.",
  UnsupportedBind: "Binding should be performed on object property.",
  UnsupportedDecoratorExport: "A decorated export must export a class declaration.",
  UnsupportedDefaultExport: "Only expressions, functions or classes are allowed as the `default` export.",
  UnsupportedImport: "`import` can only be used in `import()` or `import.meta`.",
  UnsupportedMetaProperty: "The only valid meta property for %0 is %0.%1.",
  UnsupportedParameterDecorator: "Decorators cannot be used to decorate parameters.",
  UnsupportedPropertyDecorator: "Decorators cannot be used to decorate object literal properties.",
  UnsupportedSuper: "'super' can only be used with function calls (i.e. super()) or in property accesses (i.e. super.prop or super[prop]).",
  UnterminatedComment: "Unterminated comment.",
  UnterminatedRegExp: "Unterminated regular expression.",
  UnterminatedString: "Unterminated string constant.",
  UnterminatedTemplate: "Unterminated template.",
  VarRedeclaration: "Identifier '%0' has already been declared.",
  YieldBindingIdentifier: "Can not use 'yield' as identifier inside a generator.",
  YieldInParameter: "Yield expression is not allowed in formal parameters.",
  ZeroDigitNumericSeparator: "Numeric separator can not be used after leading 0."
}, Zt.SyntaxError), Vf = Os({
  ImportMetaOutsideModule: `import.meta may appear only with 'sourceType: "module"'`,
  ImportOutsideModule: `'import' and 'export' may appear only with 'sourceType: "module"'`
}, Zt.SourceTypeModuleError);
function D0(r, e) {
  return e === "flow" && r === "PatternIsOptional" ? "OptionalBindingPattern" : r;
}
function Os(r, e, t) {
  const s = {};
  return Object.keys(r).forEach((i) => {
    s[i] = Object.freeze({
      code: e,
      reasonCode: D0(i, t),
      template: r[i]
    });
  }), Object.freeze(s);
}
class R0 extends L0 {
  getLocationForPosition(e) {
    let t;
    return e === this.state.start ? t = this.state.startLoc : e === this.state.lastTokStart ? t = this.state.lastTokStartLoc : e === this.state.end ? t = this.state.endLoc : e === this.state.lastTokEnd ? t = this.state.lastTokEndLoc : t = O0(this.input, e), t;
  }
  raise(e, {
    code: t,
    reasonCode: s,
    template: i
  }, ...n) {
    return this.raiseWithData(e, {
      code: t,
      reasonCode: s
    }, i, ...n);
  }
  raiseOverwrite(e, {
    code: t,
    template: s
  }, ...i) {
    const n = this.getLocationForPosition(e), a = s.replace(/%(\d+)/g, (o, l) => i[l]) + ` (${n.line}:${n.column})`;
    if (this.options.errorRecovery) {
      const o = this.state.errors;
      for (let l = o.length - 1; l >= 0; l--) {
        const u = o[l];
        if (u.pos === e)
          return Object.assign(u, {
            message: a
          });
        if (u.pos < e)
          break;
      }
    }
    return this._raise({
      code: t,
      loc: n,
      pos: e
    }, a);
  }
  raiseWithData(e, t, s, ...i) {
    const n = this.getLocationForPosition(e), a = s.replace(/%(\d+)/g, (o, l) => i[l]) + ` (${n.line}:${n.column})`;
    return this._raise(Object.assign({
      loc: n,
      pos: e
    }, t), a);
  }
  _raise(e, t) {
    const s = new SyntaxError(t);
    if (Object.assign(s, e), this.options.errorRecovery)
      return this.isLookahead || this.state.errors.push(s), s;
    throw s;
  }
}
var F0 = (r) => class extends r {
  parseRegExpLiteral({
    pattern: e,
    flags: t
  }) {
    let s = null;
    try {
      s = new RegExp(e, t);
    } catch (n) {
    }
    const i = this.estreeParseLiteral(s);
    return i.regex = {
      pattern: e,
      flags: t
    }, i;
  }
  parseBigIntLiteral(e) {
    let t;
    try {
      t = BigInt(e);
    } catch (i) {
      t = null;
    }
    const s = this.estreeParseLiteral(t);
    return s.bigint = String(s.value || e), s;
  }
  parseDecimalLiteral(e) {
    const s = this.estreeParseLiteral(null);
    return s.decimal = String(s.value || e), s;
  }
  estreeParseLiteral(e) {
    return this.parseLiteral(e, "Literal");
  }
  parseStringLiteral(e) {
    return this.estreeParseLiteral(e);
  }
  parseNumericLiteral(e) {
    return this.estreeParseLiteral(e);
  }
  parseNullLiteral() {
    return this.estreeParseLiteral(null);
  }
  parseBooleanLiteral(e) {
    return this.estreeParseLiteral(e);
  }
  directiveToStmt(e) {
    const t = e.value, s = this.startNodeAt(e.start, e.loc.start), i = this.startNodeAt(t.start, t.loc.start);
    return i.value = t.extra.expressionValue, i.raw = t.extra.raw, s.expression = this.finishNodeAt(i, "Literal", t.end, t.loc.end), s.directive = t.extra.raw.slice(1, -1), this.finishNodeAt(s, "ExpressionStatement", e.end, e.loc.end);
  }
  initFunction(e, t) {
    super.initFunction(e, t), e.expression = !1;
  }
  checkDeclaration(e) {
    e != null && this.isObjectProperty(e) ? this.checkDeclaration(e.value) : super.checkDeclaration(e);
  }
  getObjectOrClassMethodParams(e) {
    return e.value.params;
  }
  isValidDirective(e) {
    var t;
    return e.type === "ExpressionStatement" && e.expression.type === "Literal" && typeof e.expression.value == "string" && !((t = e.expression.extra) != null && t.parenthesized);
  }
  parseBlockBody(e, ...t) {
    super.parseBlockBody(e, ...t);
    const s = e.directives.map((i) => this.directiveToStmt(i));
    e.body = s.concat(e.body), delete e.directives;
  }
  pushClassMethod(e, t, s, i, n, a) {
    this.parseMethod(t, s, i, n, a, "ClassMethod", !0), t.typeParameters && (t.value.typeParameters = t.typeParameters, delete t.typeParameters), e.body.push(t);
  }
  parsePrivateName() {
    const e = super.parsePrivateName();
    return this.getPluginOption("estree", "classFeatures") ? this.convertPrivateNameToPrivateIdentifier(e) : e;
  }
  convertPrivateNameToPrivateIdentifier(e) {
    const t = super.getPrivateNameSV(e);
    return e = e, delete e.id, e.name = t, e.type = "PrivateIdentifier", e;
  }
  isPrivateName(e) {
    return this.getPluginOption("estree", "classFeatures") ? e.type === "PrivateIdentifier" : super.isPrivateName(e);
  }
  getPrivateNameSV(e) {
    return this.getPluginOption("estree", "classFeatures") ? e.name : super.getPrivateNameSV(e);
  }
  parseLiteral(e, t) {
    const s = super.parseLiteral(e, t);
    return s.raw = s.extra.raw, delete s.extra, s;
  }
  parseFunctionBody(e, t, s = !1) {
    super.parseFunctionBody(e, t, s), e.expression = e.body.type !== "BlockStatement";
  }
  parseMethod(e, t, s, i, n, a, o = !1) {
    let l = this.startNode();
    return l.kind = e.kind, l = super.parseMethod(l, t, s, i, n, a, o), l.type = "FunctionExpression", delete l.kind, e.value = l, a === "ClassPrivateMethod" && (e.computed = !1), a = "MethodDefinition", this.finishNode(e, a);
  }
  parseClassProperty(...e) {
    const t = super.parseClassProperty(...e);
    return this.getPluginOption("estree", "classFeatures") && (t.type = "PropertyDefinition"), t;
  }
  parseClassPrivateProperty(...e) {
    const t = super.parseClassPrivateProperty(...e);
    return this.getPluginOption("estree", "classFeatures") && (t.type = "PropertyDefinition", t.computed = !1), t;
  }
  parseObjectMethod(e, t, s, i, n) {
    const a = super.parseObjectMethod(e, t, s, i, n);
    return a && (a.type = "Property", a.kind === "method" && (a.kind = "init"), a.shorthand = !1), a;
  }
  parseObjectProperty(e, t, s, i, n) {
    const a = super.parseObjectProperty(e, t, s, i, n);
    return a && (a.kind = "init", a.type = "Property"), a;
  }
  isAssignable(e, t) {
    return e != null && this.isObjectProperty(e) ? this.isAssignable(e.value, t) : super.isAssignable(e, t);
  }
  toAssignable(e, t = !1) {
    return e != null && this.isObjectProperty(e) ? (this.toAssignable(e.value, t), e) : super.toAssignable(e, t);
  }
  toAssignableObjectExpressionProp(e, ...t) {
    e.kind === "get" || e.kind === "set" ? this.raise(e.key.start, I.PatternHasAccessor) : e.method ? this.raise(e.key.start, I.PatternHasMethod) : super.toAssignableObjectExpressionProp(e, ...t);
  }
  finishCallExpression(e, t) {
    if (super.finishCallExpression(e, t), e.callee.type === "Import") {
      if (e.type = "ImportExpression", e.source = e.arguments[0], this.hasPlugin("importAssertions")) {
        var s;
        e.attributes = (s = e.arguments[1]) != null ? s : null;
      }
      delete e.arguments, delete e.callee;
    }
    return e;
  }
  toReferencedArguments(e) {
    e.type !== "ImportExpression" && super.toReferencedArguments(e);
  }
  parseExport(e) {
    switch (super.parseExport(e), e.type) {
      case "ExportAllDeclaration":
        e.exported = null;
        break;
      case "ExportNamedDeclaration":
        e.specifiers.length === 1 && e.specifiers[0].type === "ExportNamespaceSpecifier" && (e.type = "ExportAllDeclaration", e.exported = e.specifiers[0].exported, delete e.specifiers);
        break;
    }
    return e;
  }
  parseSubscript(e, t, s, i, n) {
    const a = super.parseSubscript(e, t, s, i, n);
    if (n.optionalChainMember) {
      if ((a.type === "OptionalMemberExpression" || a.type === "OptionalCallExpression") && (a.type = a.type.substring(8)), n.stop) {
        const o = this.startNodeAtNode(a);
        return o.expression = a, this.finishNode(o, "ChainExpression");
      }
    } else
      (a.type === "MemberExpression" || a.type === "CallExpression") && (a.optional = !1);
    return a;
  }
  hasPropertyAsPrivateName(e) {
    return e.type === "ChainExpression" && (e = e.expression), super.hasPropertyAsPrivateName(e);
  }
  isOptionalChain(e) {
    return e.type === "ChainExpression";
  }
  isObjectProperty(e) {
    return e.type === "Property" && e.kind === "init" && !e.method;
  }
  isObjectMethod(e) {
    return e.method || e.kind === "get" || e.kind === "set";
  }
};
class xi {
  constructor(e, t) {
    this.token = void 0, this.preserveSpace = void 0, this.token = e, this.preserveSpace = !!t;
  }
}
const ke = {
  brace: new xi("{"),
  template: new xi("`", !0)
}, me = !0, Y = !0, Ua = !0, js = !0, pr = !0, B0 = !0;
class zf {
  constructor(e, t = {}) {
    this.label = void 0, this.keyword = void 0, this.beforeExpr = void 0, this.startsExpr = void 0, this.rightAssociative = void 0, this.isLoop = void 0, this.isAssign = void 0, this.prefix = void 0, this.postfix = void 0, this.binop = void 0, this.label = e, this.keyword = t.keyword, this.beforeExpr = !!t.beforeExpr, this.startsExpr = !!t.startsExpr, this.rightAssociative = !!t.rightAssociative, this.isLoop = !!t.isLoop, this.isAssign = !!t.isAssign, this.prefix = !!t.prefix, this.postfix = !!t.postfix, this.binop = t.binop != null ? t.binop : null, this.updateContext = null;
  }
}
const Fl = /* @__PURE__ */ new Map();
function ve(r, e = {}) {
  e.keyword = r;
  const t = ie(r, e);
  return Fl.set(r, t), t;
}
function at(r, e) {
  return ie(r, {
    beforeExpr: me,
    binop: e
  });
}
let ii = -1;
const Kt = [], Bl = [], Ul = [], $l = [], jl = [], ql = [];
function ie(r, e = {}) {
  var t, s, i, n;
  return ++ii, Bl.push(r), Ul.push((t = e.binop) != null ? t : -1), $l.push((s = e.beforeExpr) != null ? s : !1), jl.push((i = e.startsExpr) != null ? i : !1), ql.push((n = e.prefix) != null ? n : !1), Kt.push(new zf(r, e)), ii;
}
function be(r, e = {}) {
  var t, s, i, n;
  return ++ii, Fl.set(r, ii), Bl.push(r), Ul.push((t = e.binop) != null ? t : -1), $l.push((s = e.beforeExpr) != null ? s : !1), jl.push((i = e.startsExpr) != null ? i : !1), ql.push((n = e.prefix) != null ? n : !1), Kt.push(new zf("name", e)), ii;
}
const U0 = {
  bracketL: ie("[", {
    beforeExpr: me,
    startsExpr: Y
  }),
  bracketHashL: ie("#[", {
    beforeExpr: me,
    startsExpr: Y
  }),
  bracketBarL: ie("[|", {
    beforeExpr: me,
    startsExpr: Y
  }),
  bracketR: ie("]"),
  bracketBarR: ie("|]"),
  braceL: ie("{", {
    beforeExpr: me,
    startsExpr: Y
  }),
  braceBarL: ie("{|", {
    beforeExpr: me,
    startsExpr: Y
  }),
  braceHashL: ie("#{", {
    beforeExpr: me,
    startsExpr: Y
  }),
  braceR: ie("}", {
    beforeExpr: me
  }),
  braceBarR: ie("|}"),
  parenL: ie("(", {
    beforeExpr: me,
    startsExpr: Y
  }),
  parenR: ie(")"),
  comma: ie(",", {
    beforeExpr: me
  }),
  semi: ie(";", {
    beforeExpr: me
  }),
  colon: ie(":", {
    beforeExpr: me
  }),
  doubleColon: ie("::", {
    beforeExpr: me
  }),
  dot: ie("."),
  question: ie("?", {
    beforeExpr: me
  }),
  questionDot: ie("?."),
  arrow: ie("=>", {
    beforeExpr: me
  }),
  template: ie("template"),
  ellipsis: ie("...", {
    beforeExpr: me
  }),
  backQuote: ie("`", {
    startsExpr: Y
  }),
  dollarBraceL: ie("${", {
    beforeExpr: me,
    startsExpr: Y
  }),
  at: ie("@"),
  hash: ie("#", {
    startsExpr: Y
  }),
  interpreterDirective: ie("#!..."),
  eq: ie("=", {
    beforeExpr: me,
    isAssign: js
  }),
  assign: ie("_=", {
    beforeExpr: me,
    isAssign: js
  }),
  slashAssign: ie("_=", {
    beforeExpr: me,
    isAssign: js
  }),
  xorAssign: ie("_=", {
    beforeExpr: me,
    isAssign: js
  }),
  moduloAssign: ie("_=", {
    beforeExpr: me,
    isAssign: js
  }),
  incDec: ie("++/--", {
    prefix: pr,
    postfix: B0,
    startsExpr: Y
  }),
  bang: ie("!", {
    beforeExpr: me,
    prefix: pr,
    startsExpr: Y
  }),
  tilde: ie("~", {
    beforeExpr: me,
    prefix: pr,
    startsExpr: Y
  }),
  pipeline: at("|>", 0),
  nullishCoalescing: at("??", 1),
  logicalOR: at("||", 1),
  logicalAND: at("&&", 2),
  bitwiseOR: at("|", 3),
  bitwiseXOR: at("^", 4),
  bitwiseAND: at("&", 5),
  equality: at("==/!=/===/!==", 6),
  lt: at("</>/<=/>=", 7),
  gt: at("</>/<=/>=", 7),
  relational: at("</>/<=/>=", 7),
  bitShift: at("<</>>/>>>", 8),
  plusMin: ie("+/-", {
    beforeExpr: me,
    binop: 9,
    prefix: pr,
    startsExpr: Y
  }),
  modulo: ie("%", {
    binop: 10,
    startsExpr: Y
  }),
  star: ie("*", {
    binop: 10
  }),
  slash: at("/", 10),
  exponent: ie("**", {
    beforeExpr: me,
    binop: 11,
    rightAssociative: !0
  }),
  _in: ve("in", {
    beforeExpr: me,
    binop: 7
  }),
  _instanceof: ve("instanceof", {
    beforeExpr: me,
    binop: 7
  }),
  _break: ve("break"),
  _case: ve("case", {
    beforeExpr: me
  }),
  _catch: ve("catch"),
  _continue: ve("continue"),
  _debugger: ve("debugger"),
  _default: ve("default", {
    beforeExpr: me
  }),
  _else: ve("else", {
    beforeExpr: me
  }),
  _finally: ve("finally"),
  _function: ve("function", {
    startsExpr: Y
  }),
  _if: ve("if"),
  _return: ve("return", {
    beforeExpr: me
  }),
  _switch: ve("switch"),
  _throw: ve("throw", {
    beforeExpr: me,
    prefix: pr,
    startsExpr: Y
  }),
  _try: ve("try"),
  _var: ve("var"),
  _const: ve("const"),
  _with: ve("with"),
  _new: ve("new", {
    beforeExpr: me,
    startsExpr: Y
  }),
  _this: ve("this", {
    startsExpr: Y
  }),
  _super: ve("super", {
    startsExpr: Y
  }),
  _class: ve("class", {
    startsExpr: Y
  }),
  _extends: ve("extends", {
    beforeExpr: me
  }),
  _export: ve("export"),
  _import: ve("import", {
    startsExpr: Y
  }),
  _null: ve("null", {
    startsExpr: Y
  }),
  _true: ve("true", {
    startsExpr: Y
  }),
  _false: ve("false", {
    startsExpr: Y
  }),
  _typeof: ve("typeof", {
    beforeExpr: me,
    prefix: pr,
    startsExpr: Y
  }),
  _void: ve("void", {
    beforeExpr: me,
    prefix: pr,
    startsExpr: Y
  }),
  _delete: ve("delete", {
    beforeExpr: me,
    prefix: pr,
    startsExpr: Y
  }),
  _do: ve("do", {
    isLoop: Ua,
    beforeExpr: me
  }),
  _for: ve("for", {
    isLoop: Ua
  }),
  _while: ve("while", {
    isLoop: Ua
  }),
  _as: be("as", {
    startsExpr: Y
  }),
  _assert: be("assert", {
    startsExpr: Y
  }),
  _async: be("async", {
    startsExpr: Y
  }),
  _await: be("await", {
    startsExpr: Y
  }),
  _from: be("from", {
    startsExpr: Y
  }),
  _get: be("get", {
    startsExpr: Y
  }),
  _let: be("let", {
    startsExpr: Y
  }),
  _meta: be("meta", {
    startsExpr: Y
  }),
  _of: be("of", {
    startsExpr: Y
  }),
  _sent: be("sent", {
    startsExpr: Y
  }),
  _set: be("set", {
    startsExpr: Y
  }),
  _static: be("static", {
    startsExpr: Y
  }),
  _yield: be("yield", {
    startsExpr: Y
  }),
  _asserts: be("asserts", {
    startsExpr: Y
  }),
  _checks: be("checks", {
    startsExpr: Y
  }),
  _exports: be("exports", {
    startsExpr: Y
  }),
  _global: be("global", {
    startsExpr: Y
  }),
  _implements: be("implements", {
    startsExpr: Y
  }),
  _intrinsic: be("intrinsic", {
    startsExpr: Y
  }),
  _infer: be("infer", {
    startsExpr: Y
  }),
  _is: be("is", {
    startsExpr: Y
  }),
  _mixins: be("mixins", {
    startsExpr: Y
  }),
  _proto: be("proto", {
    startsExpr: Y
  }),
  _require: be("require", {
    startsExpr: Y
  }),
  _keyof: be("keyof", {
    startsExpr: Y
  }),
  _readonly: be("readonly", {
    startsExpr: Y
  }),
  _unique: be("unique", {
    startsExpr: Y
  }),
  _abstract: be("abstract", {
    startsExpr: Y
  }),
  _declare: be("declare", {
    startsExpr: Y
  }),
  _enum: be("enum", {
    startsExpr: Y
  }),
  _module: be("module", {
    startsExpr: Y
  }),
  _namespace: be("namespace", {
    startsExpr: Y
  }),
  _interface: be("interface", {
    startsExpr: Y
  }),
  _type: be("type", {
    startsExpr: Y
  }),
  _opaque: be("opaque", {
    startsExpr: Y
  }),
  name: ie("name", {
    startsExpr: Y
  }),
  string: ie("string", {
    startsExpr: Y
  }),
  num: ie("num", {
    startsExpr: Y
  }),
  bigint: ie("bigint", {
    startsExpr: Y
  }),
  decimal: ie("decimal", {
    startsExpr: Y
  }),
  regexp: ie("regexp", {
    startsExpr: Y
  }),
  privateName: ie("#name", {
    startsExpr: Y
  }),
  eof: ie("eof"),
  jsxName: ie("jsxName"),
  jsxText: ie("jsxText", {
    beforeExpr: !0
  }),
  jsxTagStart: ie("jsxTagStart", {
    startsExpr: !0
  }),
  jsxTagEnd: ie("jsxTagEnd"),
  placeholder: ie("%%", {
    startsExpr: !0
  })
};
function Se(r) {
  return r >= 87 && r <= 122;
}
function $0(r) {
  return r <= 86;
}
function er(r) {
  return r >= 52 && r <= 122;
}
function Wf(r) {
  return r >= 52 && r <= 126;
}
function j0(r) {
  return $l[r];
}
function Pc(r) {
  return jl[r];
}
function q0(r) {
  return r >= 27 && r <= 31;
}
function Ec(r) {
  return r >= 119 && r <= 121;
}
function V0(r) {
  return r >= 84 && r <= 86;
}
function Vl(r) {
  return r >= 52 && r <= 86;
}
function z0(r) {
  return r >= 35 && r <= 53;
}
function W0(r) {
  return r === 32;
}
function H0(r) {
  return ql[r];
}
function K0(r) {
  return r >= 111 && r <= 113;
}
function G0(r) {
  return r >= 114 && r <= 120;
}
function Tr(r) {
  return Bl[r];
}
function on(r) {
  return Ul[r];
}
function Y0(r) {
  return r === 51;
}
function ln(r) {
  return Kt[r];
}
function J0(r) {
  return typeof r == "number";
}
Kt[8].updateContext = (r) => {
  r.pop();
}, Kt[5].updateContext = Kt[7].updateContext = Kt[23].updateContext = (r) => {
  r.push(ke.brace);
}, Kt[22].updateContext = (r) => {
  r[r.length - 1] === ke.template ? r.pop() : r.push(ke.template);
}, Kt[132].updateContext = (r) => {
  r.push(ke.j_expr, ke.j_oTag);
};
let zl = "\xAA\xB5\xBA\xC0-\xD6\xD8-\xF6\xF8-\u02C1\u02C6-\u02D1\u02E0-\u02E4\u02EC\u02EE\u0370-\u0374\u0376\u0377\u037A-\u037D\u037F\u0386\u0388-\u038A\u038C\u038E-\u03A1\u03A3-\u03F5\u03F7-\u0481\u048A-\u052F\u0531-\u0556\u0559\u0560-\u0588\u05D0-\u05EA\u05EF-\u05F2\u0620-\u064A\u066E\u066F\u0671-\u06D3\u06D5\u06E5\u06E6\u06EE\u06EF\u06FA-\u06FC\u06FF\u0710\u0712-\u072F\u074D-\u07A5\u07B1\u07CA-\u07EA\u07F4\u07F5\u07FA\u0800-\u0815\u081A\u0824\u0828\u0840-\u0858\u0860-\u086A\u0870-\u0887\u0889-\u088E\u08A0-\u08C9\u0904-\u0939\u093D\u0950\u0958-\u0961\u0971-\u0980\u0985-\u098C\u098F\u0990\u0993-\u09A8\u09AA-\u09B0\u09B2\u09B6-\u09B9\u09BD\u09CE\u09DC\u09DD\u09DF-\u09E1\u09F0\u09F1\u09FC\u0A05-\u0A0A\u0A0F\u0A10\u0A13-\u0A28\u0A2A-\u0A30\u0A32\u0A33\u0A35\u0A36\u0A38\u0A39\u0A59-\u0A5C\u0A5E\u0A72-\u0A74\u0A85-\u0A8D\u0A8F-\u0A91\u0A93-\u0AA8\u0AAA-\u0AB0\u0AB2\u0AB3\u0AB5-\u0AB9\u0ABD\u0AD0\u0AE0\u0AE1\u0AF9\u0B05-\u0B0C\u0B0F\u0B10\u0B13-\u0B28\u0B2A-\u0B30\u0B32\u0B33\u0B35-\u0B39\u0B3D\u0B5C\u0B5D\u0B5F-\u0B61\u0B71\u0B83\u0B85-\u0B8A\u0B8E-\u0B90\u0B92-\u0B95\u0B99\u0B9A\u0B9C\u0B9E\u0B9F\u0BA3\u0BA4\u0BA8-\u0BAA\u0BAE-\u0BB9\u0BD0\u0C05-\u0C0C\u0C0E-\u0C10\u0C12-\u0C28\u0C2A-\u0C39\u0C3D\u0C58-\u0C5A\u0C5D\u0C60\u0C61\u0C80\u0C85-\u0C8C\u0C8E-\u0C90\u0C92-\u0CA8\u0CAA-\u0CB3\u0CB5-\u0CB9\u0CBD\u0CDD\u0CDE\u0CE0\u0CE1\u0CF1\u0CF2\u0D04-\u0D0C\u0D0E-\u0D10\u0D12-\u0D3A\u0D3D\u0D4E\u0D54-\u0D56\u0D5F-\u0D61\u0D7A-\u0D7F\u0D85-\u0D96\u0D9A-\u0DB1\u0DB3-\u0DBB\u0DBD\u0DC0-\u0DC6\u0E01-\u0E30\u0E32\u0E33\u0E40-\u0E46\u0E81\u0E82\u0E84\u0E86-\u0E8A\u0E8C-\u0EA3\u0EA5\u0EA7-\u0EB0\u0EB2\u0EB3\u0EBD\u0EC0-\u0EC4\u0EC6\u0EDC-\u0EDF\u0F00\u0F40-\u0F47\u0F49-\u0F6C\u0F88-\u0F8C\u1000-\u102A\u103F\u1050-\u1055\u105A-\u105D\u1061\u1065\u1066\u106E-\u1070\u1075-\u1081\u108E\u10A0-\u10C5\u10C7\u10CD\u10D0-\u10FA\u10FC-\u1248\u124A-\u124D\u1250-\u1256\u1258\u125A-\u125D\u1260-\u1288\u128A-\u128D\u1290-\u12B0\u12B2-\u12B5\u12B8-\u12BE\u12C0\u12C2-\u12C5\u12C8-\u12D6\u12D8-\u1310\u1312-\u1315\u1318-\u135A\u1380-\u138F\u13A0-\u13F5\u13F8-\u13FD\u1401-\u166C\u166F-\u167F\u1681-\u169A\u16A0-\u16EA\u16EE-\u16F8\u1700-\u1711\u171F-\u1731\u1740-\u1751\u1760-\u176C\u176E-\u1770\u1780-\u17B3\u17D7\u17DC\u1820-\u1878\u1880-\u18A8\u18AA\u18B0-\u18F5\u1900-\u191E\u1950-\u196D\u1970-\u1974\u1980-\u19AB\u19B0-\u19C9\u1A00-\u1A16\u1A20-\u1A54\u1AA7\u1B05-\u1B33\u1B45-\u1B4C\u1B83-\u1BA0\u1BAE\u1BAF\u1BBA-\u1BE5\u1C00-\u1C23\u1C4D-\u1C4F\u1C5A-\u1C7D\u1C80-\u1C88\u1C90-\u1CBA\u1CBD-\u1CBF\u1CE9-\u1CEC\u1CEE-\u1CF3\u1CF5\u1CF6\u1CFA\u1D00-\u1DBF\u1E00-\u1F15\u1F18-\u1F1D\u1F20-\u1F45\u1F48-\u1F4D\u1F50-\u1F57\u1F59\u1F5B\u1F5D\u1F5F-\u1F7D\u1F80-\u1FB4\u1FB6-\u1FBC\u1FBE\u1FC2-\u1FC4\u1FC6-\u1FCC\u1FD0-\u1FD3\u1FD6-\u1FDB\u1FE0-\u1FEC\u1FF2-\u1FF4\u1FF6-\u1FFC\u2071\u207F\u2090-\u209C\u2102\u2107\u210A-\u2113\u2115\u2118-\u211D\u2124\u2126\u2128\u212A-\u2139\u213C-\u213F\u2145-\u2149\u214E\u2160-\u2188\u2C00-\u2CE4\u2CEB-\u2CEE\u2CF2\u2CF3\u2D00-\u2D25\u2D27\u2D2D\u2D30-\u2D67\u2D6F\u2D80-\u2D96\u2DA0-\u2DA6\u2DA8-\u2DAE\u2DB0-\u2DB6\u2DB8-\u2DBE\u2DC0-\u2DC6\u2DC8-\u2DCE\u2DD0-\u2DD6\u2DD8-\u2DDE\u3005-\u3007\u3021-\u3029\u3031-\u3035\u3038-\u303C\u3041-\u3096\u309B-\u309F\u30A1-\u30FA\u30FC-\u30FF\u3105-\u312F\u3131-\u318E\u31A0-\u31BF\u31F0-\u31FF\u3400-\u4DBF\u4E00-\uA48C\uA4D0-\uA4FD\uA500-\uA60C\uA610-\uA61F\uA62A\uA62B\uA640-\uA66E\uA67F-\uA69D\uA6A0-\uA6EF\uA717-\uA71F\uA722-\uA788\uA78B-\uA7CA\uA7D0\uA7D1\uA7D3\uA7D5-\uA7D9\uA7F2-\uA801\uA803-\uA805\uA807-\uA80A\uA80C-\uA822\uA840-\uA873\uA882-\uA8B3\uA8F2-\uA8F7\uA8FB\uA8FD\uA8FE\uA90A-\uA925\uA930-\uA946\uA960-\uA97C\uA984-\uA9B2\uA9CF\uA9E0-\uA9E4\uA9E6-\uA9EF\uA9FA-\uA9FE\uAA00-\uAA28\uAA40-\uAA42\uAA44-\uAA4B\uAA60-\uAA76\uAA7A\uAA7E-\uAAAF\uAAB1\uAAB5\uAAB6\uAAB9-\uAABD\uAAC0\uAAC2\uAADB-\uAADD\uAAE0-\uAAEA\uAAF2-\uAAF4\uAB01-\uAB06\uAB09-\uAB0E\uAB11-\uAB16\uAB20-\uAB26\uAB28-\uAB2E\uAB30-\uAB5A\uAB5C-\uAB69\uAB70-\uABE2\uAC00-\uD7A3\uD7B0-\uD7C6\uD7CB-\uD7FB\uF900-\uFA6D\uFA70-\uFAD9\uFB00-\uFB06\uFB13-\uFB17\uFB1D\uFB1F-\uFB28\uFB2A-\uFB36\uFB38-\uFB3C\uFB3E\uFB40\uFB41\uFB43\uFB44\uFB46-\uFBB1\uFBD3-\uFD3D\uFD50-\uFD8F\uFD92-\uFDC7\uFDF0-\uFDFB\uFE70-\uFE74\uFE76-\uFEFC\uFF21-\uFF3A\uFF41-\uFF5A\uFF66-\uFFBE\uFFC2-\uFFC7\uFFCA-\uFFCF\uFFD2-\uFFD7\uFFDA-\uFFDC", Hf = "\u200C\u200D\xB7\u0300-\u036F\u0387\u0483-\u0487\u0591-\u05BD\u05BF\u05C1\u05C2\u05C4\u05C5\u05C7\u0610-\u061A\u064B-\u0669\u0670\u06D6-\u06DC\u06DF-\u06E4\u06E7\u06E8\u06EA-\u06ED\u06F0-\u06F9\u0711\u0730-\u074A\u07A6-\u07B0\u07C0-\u07C9\u07EB-\u07F3\u07FD\u0816-\u0819\u081B-\u0823\u0825-\u0827\u0829-\u082D\u0859-\u085B\u0898-\u089F\u08CA-\u08E1\u08E3-\u0903\u093A-\u093C\u093E-\u094F\u0951-\u0957\u0962\u0963\u0966-\u096F\u0981-\u0983\u09BC\u09BE-\u09C4\u09C7\u09C8\u09CB-\u09CD\u09D7\u09E2\u09E3\u09E6-\u09EF\u09FE\u0A01-\u0A03\u0A3C\u0A3E-\u0A42\u0A47\u0A48\u0A4B-\u0A4D\u0A51\u0A66-\u0A71\u0A75\u0A81-\u0A83\u0ABC\u0ABE-\u0AC5\u0AC7-\u0AC9\u0ACB-\u0ACD\u0AE2\u0AE3\u0AE6-\u0AEF\u0AFA-\u0AFF\u0B01-\u0B03\u0B3C\u0B3E-\u0B44\u0B47\u0B48\u0B4B-\u0B4D\u0B55-\u0B57\u0B62\u0B63\u0B66-\u0B6F\u0B82\u0BBE-\u0BC2\u0BC6-\u0BC8\u0BCA-\u0BCD\u0BD7\u0BE6-\u0BEF\u0C00-\u0C04\u0C3C\u0C3E-\u0C44\u0C46-\u0C48\u0C4A-\u0C4D\u0C55\u0C56\u0C62\u0C63\u0C66-\u0C6F\u0C81-\u0C83\u0CBC\u0CBE-\u0CC4\u0CC6-\u0CC8\u0CCA-\u0CCD\u0CD5\u0CD6\u0CE2\u0CE3\u0CE6-\u0CEF\u0D00-\u0D03\u0D3B\u0D3C\u0D3E-\u0D44\u0D46-\u0D48\u0D4A-\u0D4D\u0D57\u0D62\u0D63\u0D66-\u0D6F\u0D81-\u0D83\u0DCA\u0DCF-\u0DD4\u0DD6\u0DD8-\u0DDF\u0DE6-\u0DEF\u0DF2\u0DF3\u0E31\u0E34-\u0E3A\u0E47-\u0E4E\u0E50-\u0E59\u0EB1\u0EB4-\u0EBC\u0EC8-\u0ECD\u0ED0-\u0ED9\u0F18\u0F19\u0F20-\u0F29\u0F35\u0F37\u0F39\u0F3E\u0F3F\u0F71-\u0F84\u0F86\u0F87\u0F8D-\u0F97\u0F99-\u0FBC\u0FC6\u102B-\u103E\u1040-\u1049\u1056-\u1059\u105E-\u1060\u1062-\u1064\u1067-\u106D\u1071-\u1074\u1082-\u108D\u108F-\u109D\u135D-\u135F\u1369-\u1371\u1712-\u1715\u1732-\u1734\u1752\u1753\u1772\u1773\u17B4-\u17D3\u17DD\u17E0-\u17E9\u180B-\u180D\u180F-\u1819\u18A9\u1920-\u192B\u1930-\u193B\u1946-\u194F\u19D0-\u19DA\u1A17-\u1A1B\u1A55-\u1A5E\u1A60-\u1A7C\u1A7F-\u1A89\u1A90-\u1A99\u1AB0-\u1ABD\u1ABF-\u1ACE\u1B00-\u1B04\u1B34-\u1B44\u1B50-\u1B59\u1B6B-\u1B73\u1B80-\u1B82\u1BA1-\u1BAD\u1BB0-\u1BB9\u1BE6-\u1BF3\u1C24-\u1C37\u1C40-\u1C49\u1C50-\u1C59\u1CD0-\u1CD2\u1CD4-\u1CE8\u1CED\u1CF4\u1CF7-\u1CF9\u1DC0-\u1DFF\u203F\u2040\u2054\u20D0-\u20DC\u20E1\u20E5-\u20F0\u2CEF-\u2CF1\u2D7F\u2DE0-\u2DFF\u302A-\u302F\u3099\u309A\uA620-\uA629\uA66F\uA674-\uA67D\uA69E\uA69F\uA6F0\uA6F1\uA802\uA806\uA80B\uA823-\uA827\uA82C\uA880\uA881\uA8B4-\uA8C5\uA8D0-\uA8D9\uA8E0-\uA8F1\uA8FF-\uA909\uA926-\uA92D\uA947-\uA953\uA980-\uA983\uA9B3-\uA9C0\uA9D0-\uA9D9\uA9E5\uA9F0-\uA9F9\uAA29-\uAA36\uAA43\uAA4C\uAA4D\uAA50-\uAA59\uAA7B-\uAA7D\uAAB0\uAAB2-\uAAB4\uAAB7\uAAB8\uAABE\uAABF\uAAC1\uAAEB-\uAAEF\uAAF5\uAAF6\uABE3-\uABEA\uABEC\uABED\uABF0-\uABF9\uFB1E\uFE00-\uFE0F\uFE20-\uFE2F\uFE33\uFE34\uFE4D-\uFE4F\uFF10-\uFF19\uFF3F";
const Q0 = new RegExp("[" + zl + "]"), X0 = new RegExp("[" + zl + Hf + "]");
zl = Hf = null;
const Kf = [0, 11, 2, 25, 2, 18, 2, 1, 2, 14, 3, 13, 35, 122, 70, 52, 268, 28, 4, 48, 48, 31, 14, 29, 6, 37, 11, 29, 3, 35, 5, 7, 2, 4, 43, 157, 19, 35, 5, 35, 5, 39, 9, 51, 13, 10, 2, 14, 2, 6, 2, 1, 2, 10, 2, 14, 2, 6, 2, 1, 68, 310, 10, 21, 11, 7, 25, 5, 2, 41, 2, 8, 70, 5, 3, 0, 2, 43, 2, 1, 4, 0, 3, 22, 11, 22, 10, 30, 66, 18, 2, 1, 11, 21, 11, 25, 71, 55, 7, 1, 65, 0, 16, 3, 2, 2, 2, 28, 43, 28, 4, 28, 36, 7, 2, 27, 28, 53, 11, 21, 11, 18, 14, 17, 111, 72, 56, 50, 14, 50, 14, 35, 349, 41, 7, 1, 79, 28, 11, 0, 9, 21, 43, 17, 47, 20, 28, 22, 13, 52, 58, 1, 3, 0, 14, 44, 33, 24, 27, 35, 30, 0, 3, 0, 9, 34, 4, 0, 13, 47, 15, 3, 22, 0, 2, 0, 36, 17, 2, 24, 85, 6, 2, 0, 2, 3, 2, 14, 2, 9, 8, 46, 39, 7, 3, 1, 3, 21, 2, 6, 2, 1, 2, 4, 4, 0, 19, 0, 13, 4, 159, 52, 19, 3, 21, 2, 31, 47, 21, 1, 2, 0, 185, 46, 42, 3, 37, 47, 21, 0, 60, 42, 14, 0, 72, 26, 38, 6, 186, 43, 117, 63, 32, 7, 3, 0, 3, 7, 2, 1, 2, 23, 16, 0, 2, 0, 95, 7, 3, 38, 17, 0, 2, 0, 29, 0, 11, 39, 8, 0, 22, 0, 12, 45, 20, 0, 19, 72, 264, 8, 2, 36, 18, 0, 50, 29, 113, 6, 2, 1, 2, 37, 22, 0, 26, 5, 2, 1, 2, 31, 15, 0, 328, 18, 190, 0, 80, 921, 103, 110, 18, 195, 2637, 96, 16, 1070, 4050, 582, 8634, 568, 8, 30, 18, 78, 18, 29, 19, 47, 17, 3, 32, 20, 6, 18, 689, 63, 129, 74, 6, 0, 67, 12, 65, 1, 2, 0, 29, 6135, 9, 1237, 43, 8, 8936, 3, 2, 6, 2, 1, 2, 290, 46, 2, 18, 3, 9, 395, 2309, 106, 6, 12, 4, 8, 8, 9, 5991, 84, 2, 70, 2, 1, 3, 0, 3, 1, 3, 3, 2, 11, 2, 0, 2, 6, 2, 64, 2, 3, 3, 7, 2, 6, 2, 27, 2, 3, 2, 4, 2, 0, 4, 6, 2, 339, 3, 24, 2, 24, 2, 30, 2, 24, 2, 30, 2, 24, 2, 30, 2, 24, 2, 30, 2, 24, 2, 7, 1845, 30, 482, 44, 11, 6, 17, 0, 322, 29, 19, 43, 1269, 6, 2, 3, 2, 1, 2, 14, 2, 196, 60, 67, 8, 0, 1205, 3, 2, 26, 2, 1, 2, 0, 3, 0, 2, 9, 2, 3, 2, 0, 2, 0, 7, 0, 5, 0, 2, 0, 2, 0, 2, 2, 2, 1, 2, 0, 3, 0, 2, 0, 2, 0, 2, 0, 2, 0, 2, 1, 2, 0, 3, 3, 2, 6, 2, 3, 2, 3, 2, 0, 2, 9, 2, 16, 6, 2, 2, 4, 2, 16, 4421, 42719, 33, 4152, 8, 221, 3, 5761, 15, 7472, 3104, 541, 1507, 4938], Z0 = [509, 0, 227, 0, 150, 4, 294, 9, 1368, 2, 2, 1, 6, 3, 41, 2, 5, 0, 166, 1, 574, 3, 9, 9, 370, 1, 154, 10, 50, 3, 123, 2, 54, 14, 32, 10, 3, 1, 11, 3, 46, 10, 8, 0, 46, 9, 7, 2, 37, 13, 2, 9, 6, 1, 45, 0, 13, 2, 49, 13, 9, 3, 2, 11, 83, 11, 7, 0, 161, 11, 6, 9, 7, 3, 56, 1, 2, 6, 3, 1, 3, 2, 10, 0, 11, 1, 3, 6, 4, 4, 193, 17, 10, 9, 5, 0, 82, 19, 13, 9, 214, 6, 3, 8, 28, 1, 83, 16, 16, 9, 82, 12, 9, 9, 84, 14, 5, 9, 243, 14, 166, 9, 71, 5, 2, 1, 3, 3, 2, 0, 2, 1, 13, 9, 120, 6, 3, 6, 4, 0, 29, 9, 41, 6, 2, 3, 9, 0, 10, 10, 47, 15, 406, 7, 2, 7, 17, 9, 57, 21, 2, 13, 123, 5, 4, 0, 2, 1, 2, 6, 2, 0, 9, 9, 49, 4, 2, 1, 2, 4, 9, 9, 330, 3, 19306, 9, 87, 9, 39, 4, 60, 6, 26, 9, 1014, 0, 2, 54, 8, 3, 82, 0, 12, 1, 19628, 1, 4706, 45, 3, 22, 543, 4, 4, 5, 9, 7, 3, 6, 31, 3, 149, 2, 1418, 49, 513, 54, 5, 49, 9, 0, 15, 0, 23, 4, 2, 14, 1361, 6, 2, 16, 3, 6, 2, 1, 2, 4, 262, 6, 10, 9, 357, 0, 62, 13, 1495, 6, 110, 6, 6, 9, 4759, 9, 787719, 239];
function _o(r, e) {
  let t = 65536;
  for (let s = 0, i = e.length; s < i; s += 2) {
    if (t += e[s], t > r)
      return !1;
    if (t += e[s + 1], t >= r)
      return !0;
  }
  return !1;
}
function br(r) {
  return r < 65 ? r === 36 : r <= 90 ? !0 : r < 97 ? r === 95 : r <= 122 ? !0 : r <= 65535 ? r >= 170 && Q0.test(String.fromCharCode(r)) : _o(r, Kf);
}
function ps(r) {
  return r < 48 ? r === 36 : r < 58 ? !0 : r < 65 ? !1 : r <= 90 ? !0 : r < 97 ? r === 95 : r <= 122 ? !0 : r <= 65535 ? r >= 170 && X0.test(String.fromCharCode(r)) : _o(r, Kf) || _o(r, Z0);
}
const Wl = {
  keyword: ["break", "case", "catch", "continue", "debugger", "default", "do", "else", "finally", "for", "function", "if", "return", "switch", "throw", "try", "var", "const", "while", "with", "new", "this", "super", "class", "extends", "export", "import", "null", "true", "false", "in", "instanceof", "typeof", "void", "delete"],
  strict: ["implements", "interface", "let", "package", "private", "protected", "public", "static", "yield"],
  strictBind: ["eval", "arguments"]
}, e1 = new Set(Wl.keyword), t1 = new Set(Wl.strict), r1 = new Set(Wl.strictBind);
function Gf(r, e) {
  return e && r === "await" || r === "enum";
}
function Yf(r, e) {
  return Gf(r, e) || t1.has(r);
}
function Jf(r) {
  return r1.has(r);
}
function Qf(r, e) {
  return Yf(r, e) || Jf(r);
}
function s1(r) {
  return e1.has(r);
}
function i1(r, e) {
  return r === 64 && e === 64;
}
const n1 = /* @__PURE__ */ new Set(["break", "case", "catch", "continue", "debugger", "default", "do", "else", "finally", "for", "function", "if", "return", "switch", "throw", "try", "var", "const", "while", "with", "new", "this", "super", "class", "extends", "export", "import", "null", "true", "false", "in", "instanceof", "typeof", "void", "delete", "implements", "interface", "let", "package", "private", "protected", "public", "static", "yield", "eval", "arguments", "enum", "await"]);
function a1(r) {
  return n1.has(r);
}
const ns = 0, ni = 1, Yt = 2, Hl = 4, Xf = 8, kn = 16, Zf = 32, qr = 64, ep = 128, un = 256, zi = ni | Yt | un, sr = 1, Ps = 2, tp = 4, ds = 8, cn = 16, rp = 64, Mn = 128, Co = 256, Io = 512, Kl = 1024, No = 2048, sp = sr | Ps | ds | Mn, yt = sr | 0 | ds | 0, Ln = sr | 0 | tp | 0, ip = sr | 0 | cn | 0, o1 = 0 | Ps | 0 | Mn, l1 = 0 | Ps | 0 | 0, np = sr | Ps | ds | Co, Tc = 0 | Kl, qs = 0 | rp, u1 = sr | 0 | 0 | rp, c1 = np | Io, h1 = 0 | Kl, f1 = No, Dn = 4, Gl = 2, Yl = 1, $a = Gl | Yl, p1 = Gl | Dn, d1 = Yl | Dn, m1 = Gl, y1 = Yl, Ac = 0;
class Jl {
  constructor(e) {
    this.var = /* @__PURE__ */ new Set(), this.lexical = /* @__PURE__ */ new Set(), this.functions = /* @__PURE__ */ new Set(), this.flags = e;
  }
}
class Ql {
  constructor(e, t) {
    this.scopeStack = [], this.undefinedExports = /* @__PURE__ */ new Map(), this.undefinedPrivateNames = /* @__PURE__ */ new Map(), this.raise = e, this.inModule = t;
  }
  get inFunction() {
    return (this.currentVarScopeFlags() & Yt) > 0;
  }
  get allowSuper() {
    return (this.currentThisScopeFlags() & kn) > 0;
  }
  get allowDirectSuper() {
    return (this.currentThisScopeFlags() & Zf) > 0;
  }
  get inClass() {
    return (this.currentThisScopeFlags() & qr) > 0;
  }
  get inClassAndNotInNonArrowFunction() {
    const e = this.currentThisScopeFlags();
    return (e & qr) > 0 && (e & Yt) === 0;
  }
  get inStaticBlock() {
    for (let e = this.scopeStack.length - 1; ; e--) {
      const {
        flags: t
      } = this.scopeStack[e];
      if (t & ep)
        return !0;
      if (t & (zi | qr))
        return !1;
    }
  }
  get inNonArrowFunction() {
    return (this.currentThisScopeFlags() & Yt) > 0;
  }
  get treatFunctionsAsVar() {
    return this.treatFunctionsAsVarInScope(this.currentScope());
  }
  createScope(e) {
    return new Jl(e);
  }
  enter(e) {
    this.scopeStack.push(this.createScope(e));
  }
  exit() {
    this.scopeStack.pop();
  }
  treatFunctionsAsVarInScope(e) {
    return !!(e.flags & Yt || !this.inModule && e.flags & ni);
  }
  declareName(e, t, s) {
    let i = this.currentScope();
    if (t & ds || t & cn)
      this.checkRedeclarationInScope(i, e, t, s), t & cn ? i.functions.add(e) : i.lexical.add(e), t & ds && this.maybeExportDefined(i, e);
    else if (t & tp)
      for (let n = this.scopeStack.length - 1; n >= 0 && (i = this.scopeStack[n], this.checkRedeclarationInScope(i, e, t, s), i.var.add(e), this.maybeExportDefined(i, e), !(i.flags & zi)); --n)
        ;
    this.inModule && i.flags & ni && this.undefinedExports.delete(e);
  }
  maybeExportDefined(e, t) {
    this.inModule && e.flags & ni && this.undefinedExports.delete(t);
  }
  checkRedeclarationInScope(e, t, s, i) {
    this.isRedeclaredInScope(e, t, s) && this.raise(i, I.VarRedeclaration, t);
  }
  isRedeclaredInScope(e, t, s) {
    return s & sr ? s & ds ? e.lexical.has(t) || e.functions.has(t) || e.var.has(t) : s & cn ? e.lexical.has(t) || !this.treatFunctionsAsVarInScope(e) && e.var.has(t) : e.lexical.has(t) && !(e.flags & Xf && e.lexical.values().next().value === t) || !this.treatFunctionsAsVarInScope(e) && e.functions.has(t) : !1;
  }
  checkLocalExport(e) {
    const {
      name: t
    } = e, s = this.scopeStack[0];
    !s.lexical.has(t) && !s.var.has(t) && !s.functions.has(t) && this.undefinedExports.set(t, e.start);
  }
  currentScope() {
    return this.scopeStack[this.scopeStack.length - 1];
  }
  currentVarScopeFlags() {
    for (let e = this.scopeStack.length - 1; ; e--) {
      const {
        flags: t
      } = this.scopeStack[e];
      if (t & zi)
        return t;
    }
  }
  currentThisScopeFlags() {
    for (let e = this.scopeStack.length - 1; ; e--) {
      const {
        flags: t
      } = this.scopeStack[e];
      if (t & (zi | qr) && !(t & Hl))
        return t;
    }
  }
}
class g1 extends Jl {
  constructor(...e) {
    super(...e), this.declareFunctions = /* @__PURE__ */ new Set();
  }
}
class v1 extends Ql {
  createScope(e) {
    return new g1(e);
  }
  declareName(e, t, s) {
    const i = this.currentScope();
    if (t & No) {
      this.checkRedeclarationInScope(i, e, t, s), this.maybeExportDefined(i, e), i.declareFunctions.add(e);
      return;
    }
    super.declareName(...arguments);
  }
  isRedeclaredInScope(e, t, s) {
    return super.isRedeclaredInScope(...arguments) ? !0 : s & No ? !e.declareFunctions.has(t) && (e.lexical.has(t) || e.functions.has(t)) : !1;
  }
  checkLocalExport(e) {
    this.scopeStack[0].declareFunctions.has(e.name) || super.checkLocalExport(e);
  }
}
class Xl {
  constructor() {
    this.strict = void 0, this.curLine = void 0, this.lineStart = void 0, this.startLoc = void 0, this.endLoc = void 0, this.errors = [], this.potentialArrowAt = -1, this.noArrowAt = [], this.noArrowParamsConversionAt = [], this.maybeInArrowParameters = !1, this.inType = !1, this.noAnonFunctionType = !1, this.hasFlowComment = !1, this.isAmbientContext = !1, this.inAbstractClass = !1, this.topicContext = {
      maxNumOfResolvableTopics: 0,
      maxTopicIndex: null
    }, this.soloAwait = !1, this.inFSharpPipelineDirectBody = !1, this.labels = [], this.decoratorStack = [[]], this.comments = [], this.commentStack = [], this.pos = 0, this.type = 129, this.value = null, this.start = 0, this.end = 0, this.lastTokEndLoc = null, this.lastTokStartLoc = null, this.lastTokStart = 0, this.lastTokEnd = 0, this.context = [ke.brace], this.canStartJSXElement = !0, this.containsEsc = !1, this.strictErrors = /* @__PURE__ */ new Map(), this.tokensLength = 0;
  }
  init({
    strictMode: e,
    sourceType: t,
    startLine: s,
    startColumn: i
  }) {
    this.strict = e === !1 ? !1 : e === !0 ? !0 : t === "module", this.curLine = s, this.lineStart = -i, this.startLoc = this.endLoc = new vi(s, i);
  }
  curPosition() {
    return new vi(this.curLine, this.pos - this.lineStart);
  }
  clone(e) {
    const t = new Xl(), s = Object.keys(this);
    for (let i = 0, n = s.length; i < n; i++) {
      const a = s[i];
      let o = this[a];
      !e && Array.isArray(o) && (o = o.slice()), t[a] = o;
    }
    return t;
  }
}
var b1 = function(e) {
  return e >= 48 && e <= 57;
};
const x1 = /* @__PURE__ */ new Set([103, 109, 115, 105, 121, 117, 100]), _c = {
  decBinOct: [46, 66, 69, 79, 95, 98, 101, 111],
  hex: [46, 88, 95, 120]
}, _t = {};
_t.bin = [48, 49];
_t.oct = [..._t.bin, 50, 51, 52, 53, 54, 55];
_t.dec = [..._t.oct, 56, 57];
_t.hex = [..._t.dec, 65, 66, 67, 68, 69, 70, 97, 98, 99, 100, 101, 102];
class Oo {
  constructor(e) {
    this.type = e.type, this.value = e.value, this.start = e.start, this.end = e.end, this.loc = new On(e.startLoc, e.endLoc);
  }
}
class S1 extends R0 {
  constructor(e, t) {
    super(), this.isLookahead = void 0, this.tokens = [], this.state = new Xl(), this.state.init(e), this.input = t, this.length = t.length, this.isLookahead = !1;
  }
  pushToken(e) {
    this.tokens.length = this.state.tokensLength, this.tokens.push(e), ++this.state.tokensLength;
  }
  next() {
    this.checkKeywordEscapes(), this.options.tokens && this.pushToken(new Oo(this.state)), this.state.lastTokEnd = this.state.end, this.state.lastTokStart = this.state.start, this.state.lastTokEndLoc = this.state.endLoc, this.state.lastTokStartLoc = this.state.startLoc, this.nextToken();
  }
  eat(e) {
    return this.match(e) ? (this.next(), !0) : !1;
  }
  match(e) {
    return this.state.type === e;
  }
  createLookaheadState(e) {
    return {
      pos: e.pos,
      value: null,
      type: e.type,
      start: e.start,
      end: e.end,
      lastTokEnd: e.end,
      context: [this.curContext()],
      inType: e.inType
    };
  }
  lookahead() {
    const e = this.state;
    this.state = this.createLookaheadState(e), this.isLookahead = !0, this.nextToken(), this.isLookahead = !1;
    const t = this.state;
    return this.state = e, t;
  }
  nextTokenStart() {
    return this.nextTokenStartSince(this.state.pos);
  }
  nextTokenStartSince(e) {
    return Ba.lastIndex = e, Ba.test(this.input) ? Ba.lastIndex : e;
  }
  lookaheadCharCode() {
    return this.input.charCodeAt(this.nextTokenStart());
  }
  codePointAtPos(e) {
    let t = this.input.charCodeAt(e);
    if ((t & 64512) === 55296 && ++e < this.input.length) {
      const s = this.input.charCodeAt(e);
      (s & 64512) === 56320 && (t = 65536 + ((t & 1023) << 10) + (s & 1023));
    }
    return t;
  }
  setStrict(e) {
    this.state.strict = e, e && (this.state.strictErrors.forEach((t, s) => this.raise(s, t)), this.state.strictErrors.clear());
  }
  curContext() {
    return this.state.context[this.state.context.length - 1];
  }
  nextToken() {
    const e = this.curContext();
    if (e.preserveSpace || this.skipSpace(), this.state.start = this.state.pos, this.isLookahead || (this.state.startLoc = this.state.curPosition()), this.state.pos >= this.length) {
      this.finishToken(129);
      return;
    }
    e === ke.template ? this.readTmplToken() : this.getTokenFromCode(this.codePointAtPos(this.state.pos));
  }
  skipBlockComment() {
    let e;
    this.isLookahead || (e = this.state.curPosition());
    const t = this.state.pos, s = this.input.indexOf("*/", t + 2);
    if (s === -1)
      throw this.raise(t, I.UnterminatedComment);
    for (this.state.pos = s + 2, jr.lastIndex = t + 2; jr.test(this.input) && jr.lastIndex <= s; )
      ++this.state.curLine, this.state.lineStart = jr.lastIndex;
    if (this.isLookahead)
      return;
    const i = {
      type: "CommentBlock",
      value: this.input.slice(t + 2, s),
      start: t,
      end: s + 2,
      loc: new On(e, this.state.curPosition())
    };
    return this.options.tokens && this.pushToken(i), i;
  }
  skipLineComment(e) {
    const t = this.state.pos;
    let s;
    this.isLookahead || (s = this.state.curPosition());
    let i = this.input.charCodeAt(this.state.pos += e);
    if (this.state.pos < this.length)
      for (; !Br(i) && ++this.state.pos < this.length; )
        i = this.input.charCodeAt(this.state.pos);
    if (this.isLookahead)
      return;
    const n = this.state.pos, o = {
      type: "CommentLine",
      value: this.input.slice(t + e, n),
      start: t,
      end: n,
      loc: new On(s, this.state.curPosition())
    };
    return this.options.tokens && this.pushToken(o), o;
  }
  skipSpace() {
    const e = this.state.pos, t = [];
    e:
      for (; this.state.pos < this.length; ) {
        const s = this.input.charCodeAt(this.state.pos);
        switch (s) {
          case 32:
          case 160:
          case 9:
            ++this.state.pos;
            break;
          case 13:
            this.input.charCodeAt(this.state.pos + 1) === 10 && ++this.state.pos;
          case 10:
          case 8232:
          case 8233:
            ++this.state.pos, ++this.state.curLine, this.state.lineStart = this.state.pos;
            break;
          case 47:
            switch (this.input.charCodeAt(this.state.pos + 1)) {
              case 42: {
                const i = this.skipBlockComment();
                i !== void 0 && (this.addComment(i), this.options.attachComment && t.push(i));
                break;
              }
              case 47: {
                const i = this.skipLineComment(2);
                i !== void 0 && (this.addComment(i), this.options.attachComment && t.push(i));
                break;
              }
              default:
                break e;
            }
            break;
          default:
            if (N0(s))
              ++this.state.pos;
            else if (s === 45 && !this.inModule) {
              const i = this.state.pos;
              if (this.input.charCodeAt(i + 1) === 45 && this.input.charCodeAt(i + 2) === 62 && (e === 0 || this.state.lineStart > e)) {
                const n = this.skipLineComment(3);
                n !== void 0 && (this.addComment(n), this.options.attachComment && t.push(n));
              } else
                break e;
            } else if (s === 60 && !this.inModule) {
              const i = this.state.pos;
              if (this.input.charCodeAt(i + 1) === 33 && this.input.charCodeAt(i + 2) === 45 && this.input.charCodeAt(i + 3) === 45) {
                const n = this.skipLineComment(4);
                n !== void 0 && (this.addComment(n), this.options.attachComment && t.push(n));
              } else
                break e;
            } else
              break e;
        }
      }
    if (t.length > 0) {
      const s = this.state.pos, i = {
        start: e,
        end: s,
        comments: t,
        leadingNode: null,
        trailingNode: null,
        containingNode: null
      };
      this.state.commentStack.push(i);
    }
  }
  finishToken(e, t) {
    this.state.end = this.state.pos;
    const s = this.state.type;
    this.state.type = e, this.state.value = t, this.isLookahead || (this.state.endLoc = this.state.curPosition(), this.updateContext(s));
  }
  replaceToken(e) {
    this.state.type = e, this.updateContext();
  }
  readToken_numberSign() {
    if (this.state.pos === 0 && this.readToken_interpreter())
      return;
    const e = this.state.pos + 1, t = this.codePointAtPos(e);
    if (t >= 48 && t <= 57)
      throw this.raise(this.state.pos, I.UnexpectedDigitAfterHash);
    if (t === 123 || t === 91 && this.hasPlugin("recordAndTuple")) {
      if (this.expectPlugin("recordAndTuple"), this.getPluginOption("recordAndTuple", "syntaxType") !== "hash")
        throw this.raise(this.state.pos, t === 123 ? I.RecordExpressionHashIncorrectStartSyntaxType : I.TupleExpressionHashIncorrectStartSyntaxType);
      this.state.pos += 2, t === 123 ? this.finishToken(7) : this.finishToken(1);
    } else
      br(t) ? (++this.state.pos, this.finishToken(128, this.readWord1(t))) : t === 92 ? (++this.state.pos, this.finishToken(128, this.readWord1())) : this.finishOp(25, 1);
  }
  readToken_dot() {
    const e = this.input.charCodeAt(this.state.pos + 1);
    if (e >= 48 && e <= 57) {
      this.readNumber(!0);
      return;
    }
    e === 46 && this.input.charCodeAt(this.state.pos + 2) === 46 ? (this.state.pos += 3, this.finishToken(21)) : (++this.state.pos, this.finishToken(16));
  }
  readToken_slash() {
    this.input.charCodeAt(this.state.pos + 1) === 61 ? this.finishOp(29, 2) : this.finishOp(50, 1);
  }
  readToken_interpreter() {
    if (this.state.pos !== 0 || this.length < 2)
      return !1;
    let e = this.input.charCodeAt(this.state.pos + 1);
    if (e !== 33)
      return !1;
    const t = this.state.pos;
    for (this.state.pos += 1; !Br(e) && ++this.state.pos < this.length; )
      e = this.input.charCodeAt(this.state.pos);
    const s = this.input.slice(t + 2, this.state.pos);
    return this.finishToken(26, s), !0;
  }
  readToken_mult_modulo(e) {
    let t = e === 42 ? 49 : 48, s = 1, i = this.input.charCodeAt(this.state.pos + 1);
    e === 42 && i === 42 && (s++, i = this.input.charCodeAt(this.state.pos + 2), t = 51), i === 61 && !this.state.inType && (s++, t = e === 37 ? 31 : 28), this.finishOp(t, s);
  }
  readToken_pipe_amp(e) {
    const t = this.input.charCodeAt(this.state.pos + 1);
    if (t === e) {
      this.input.charCodeAt(this.state.pos + 2) === 61 ? this.finishOp(28, 3) : this.finishOp(e === 124 ? 37 : 38, 2);
      return;
    }
    if (e === 124) {
      if (t === 62) {
        this.finishOp(35, 2);
        return;
      }
      if (this.hasPlugin("recordAndTuple") && t === 125) {
        if (this.getPluginOption("recordAndTuple", "syntaxType") !== "bar")
          throw this.raise(this.state.pos, I.RecordExpressionBarIncorrectEndSyntaxType);
        this.state.pos += 2, this.finishToken(9);
        return;
      }
      if (this.hasPlugin("recordAndTuple") && t === 93) {
        if (this.getPluginOption("recordAndTuple", "syntaxType") !== "bar")
          throw this.raise(this.state.pos, I.TupleExpressionBarIncorrectEndSyntaxType);
        this.state.pos += 2, this.finishToken(4);
        return;
      }
    }
    if (t === 61) {
      this.finishOp(28, 2);
      return;
    }
    this.finishOp(e === 124 ? 39 : 41, 1);
  }
  readToken_caret() {
    this.input.charCodeAt(this.state.pos + 1) === 61 && !this.state.inType ? this.finishOp(30, 2) : this.finishOp(40, 1);
  }
  readToken_plus_min(e) {
    const t = this.input.charCodeAt(this.state.pos + 1);
    if (t === e) {
      this.finishOp(32, 2);
      return;
    }
    t === 61 ? this.finishOp(28, 2) : this.finishOp(47, 1);
  }
  readToken_lt() {
    const {
      pos: e
    } = this.state, t = this.input.charCodeAt(e + 1);
    if (t === 60) {
      if (this.input.charCodeAt(e + 2) === 61) {
        this.finishOp(28, 3);
        return;
      }
      this.finishOp(46, 2);
      return;
    }
    if (t === 61) {
      this.finishOp(45, 2);
      return;
    }
    this.finishOp(43, 1);
  }
  readToken_gt() {
    const {
      pos: e
    } = this.state, t = this.input.charCodeAt(e + 1);
    if (t === 62) {
      const s = this.input.charCodeAt(e + 2) === 62 ? 3 : 2;
      if (this.input.charCodeAt(e + s) === 61) {
        this.finishOp(28, s + 1);
        return;
      }
      this.finishOp(46, s);
      return;
    }
    if (t === 61) {
      this.finishOp(45, 2);
      return;
    }
    this.finishOp(44, 1);
  }
  readToken_eq_excl(e) {
    const t = this.input.charCodeAt(this.state.pos + 1);
    if (t === 61) {
      this.finishOp(42, this.input.charCodeAt(this.state.pos + 2) === 61 ? 3 : 2);
      return;
    }
    if (e === 61 && t === 62) {
      this.state.pos += 2, this.finishToken(19);
      return;
    }
    this.finishOp(e === 61 ? 27 : 33, 1);
  }
  readToken_question() {
    const e = this.input.charCodeAt(this.state.pos + 1), t = this.input.charCodeAt(this.state.pos + 2);
    e === 63 ? t === 61 ? this.finishOp(28, 3) : this.finishOp(36, 2) : e === 46 && !(t >= 48 && t <= 57) ? (this.state.pos += 2, this.finishToken(18)) : (++this.state.pos, this.finishToken(17));
  }
  getTokenFromCode(e) {
    switch (e) {
      case 46:
        this.readToken_dot();
        return;
      case 40:
        ++this.state.pos, this.finishToken(10);
        return;
      case 41:
        ++this.state.pos, this.finishToken(11);
        return;
      case 59:
        ++this.state.pos, this.finishToken(13);
        return;
      case 44:
        ++this.state.pos, this.finishToken(12);
        return;
      case 91:
        if (this.hasPlugin("recordAndTuple") && this.input.charCodeAt(this.state.pos + 1) === 124) {
          if (this.getPluginOption("recordAndTuple", "syntaxType") !== "bar")
            throw this.raise(this.state.pos, I.TupleExpressionBarIncorrectStartSyntaxType);
          this.state.pos += 2, this.finishToken(2);
        } else
          ++this.state.pos, this.finishToken(0);
        return;
      case 93:
        ++this.state.pos, this.finishToken(3);
        return;
      case 123:
        if (this.hasPlugin("recordAndTuple") && this.input.charCodeAt(this.state.pos + 1) === 124) {
          if (this.getPluginOption("recordAndTuple", "syntaxType") !== "bar")
            throw this.raise(this.state.pos, I.RecordExpressionBarIncorrectStartSyntaxType);
          this.state.pos += 2, this.finishToken(6);
        } else
          ++this.state.pos, this.finishToken(5);
        return;
      case 125:
        ++this.state.pos, this.finishToken(8);
        return;
      case 58:
        this.hasPlugin("functionBind") && this.input.charCodeAt(this.state.pos + 1) === 58 ? this.finishOp(15, 2) : (++this.state.pos, this.finishToken(14));
        return;
      case 63:
        this.readToken_question();
        return;
      case 96:
        ++this.state.pos, this.finishToken(22);
        return;
      case 48: {
        const t = this.input.charCodeAt(this.state.pos + 1);
        if (t === 120 || t === 88) {
          this.readRadixNumber(16);
          return;
        }
        if (t === 111 || t === 79) {
          this.readRadixNumber(8);
          return;
        }
        if (t === 98 || t === 66) {
          this.readRadixNumber(2);
          return;
        }
      }
      case 49:
      case 50:
      case 51:
      case 52:
      case 53:
      case 54:
      case 55:
      case 56:
      case 57:
        this.readNumber(!1);
        return;
      case 34:
      case 39:
        this.readString(e);
        return;
      case 47:
        this.readToken_slash();
        return;
      case 37:
      case 42:
        this.readToken_mult_modulo(e);
        return;
      case 124:
      case 38:
        this.readToken_pipe_amp(e);
        return;
      case 94:
        this.readToken_caret();
        return;
      case 43:
      case 45:
        this.readToken_plus_min(e);
        return;
      case 60:
        this.readToken_lt();
        return;
      case 62:
        this.readToken_gt();
        return;
      case 61:
      case 33:
        this.readToken_eq_excl(e);
        return;
      case 126:
        this.finishOp(34, 1);
        return;
      case 64:
        ++this.state.pos, this.finishToken(24);
        return;
      case 35:
        this.readToken_numberSign();
        return;
      case 92:
        this.readWord();
        return;
      default:
        if (br(e)) {
          this.readWord(e);
          return;
        }
    }
    throw this.raise(this.state.pos, I.InvalidOrUnexpectedToken, String.fromCodePoint(e));
  }
  finishOp(e, t) {
    const s = this.input.slice(this.state.pos, this.state.pos + t);
    this.state.pos += t, this.finishToken(e, s);
  }
  readRegexp() {
    const e = this.state.start + 1;
    let t, s, {
      pos: i
    } = this.state;
    for (; ; ++i) {
      if (i >= this.length)
        throw this.raise(e, I.UnterminatedRegExp);
      const o = this.input.charCodeAt(i);
      if (Br(o))
        throw this.raise(e, I.UnterminatedRegExp);
      if (t)
        t = !1;
      else {
        if (o === 91)
          s = !0;
        else if (o === 93 && s)
          s = !1;
        else if (o === 47 && !s)
          break;
        t = o === 92;
      }
    }
    const n = this.input.slice(e, i);
    ++i;
    let a = "";
    for (; i < this.length; ) {
      const o = this.codePointAtPos(i), l = String.fromCharCode(o);
      if (x1.has(o))
        a.includes(l) && this.raise(i + 1, I.DuplicateRegExpFlags);
      else if (ps(o) || o === 92)
        this.raise(i + 1, I.MalformedRegExpFlags);
      else
        break;
      ++i, a += l;
    }
    this.state.pos = i, this.finishToken(127, {
      pattern: n,
      flags: a
    });
  }
  readInt(e, t, s, i = !0) {
    const n = this.state.pos, a = e === 16 ? _c.hex : _c.decBinOct, o = e === 16 ? _t.hex : e === 10 ? _t.dec : e === 8 ? _t.oct : _t.bin;
    let l = !1, u = 0;
    for (let c = 0, h = t == null ? 1 / 0 : t; c < h; ++c) {
      const f = this.input.charCodeAt(this.state.pos);
      let p;
      if (f === 95) {
        const x = this.input.charCodeAt(this.state.pos - 1), d = this.input.charCodeAt(this.state.pos + 1);
        o.indexOf(d) === -1 ? this.raise(this.state.pos, I.UnexpectedNumericSeparator) : (a.indexOf(x) > -1 || a.indexOf(d) > -1 || Number.isNaN(d)) && this.raise(this.state.pos, I.UnexpectedNumericSeparator), i || this.raise(this.state.pos, I.NumericSeparatorInEscapeSequence), ++this.state.pos;
        continue;
      }
      if (f >= 97 ? p = f - 97 + 10 : f >= 65 ? p = f - 65 + 10 : b1(f) ? p = f - 48 : p = 1 / 0, p >= e)
        if (this.options.errorRecovery && p <= 9)
          p = 0, this.raise(this.state.start + c + 2, I.InvalidDigit, e);
        else if (s)
          p = 0, l = !0;
        else
          break;
      ++this.state.pos, u = u * e + p;
    }
    return this.state.pos === n || t != null && this.state.pos - n !== t || l ? null : u;
  }
  readRadixNumber(e) {
    const t = this.state.pos;
    let s = !1;
    this.state.pos += 2;
    const i = this.readInt(e);
    i == null && this.raise(this.state.start + 2, I.InvalidDigit, e);
    const n = this.input.charCodeAt(this.state.pos);
    if (n === 110)
      ++this.state.pos, s = !0;
    else if (n === 109)
      throw this.raise(t, I.InvalidDecimal);
    if (br(this.codePointAtPos(this.state.pos)))
      throw this.raise(this.state.pos, I.NumberIdentifier);
    if (s) {
      const a = this.input.slice(t, this.state.pos).replace(/[_n]/g, "");
      this.finishToken(125, a);
      return;
    }
    this.finishToken(124, i);
  }
  readNumber(e) {
    const t = this.state.pos;
    let s = !1, i = !1, n = !1, a = !1, o = !1;
    !e && this.readInt(10) === null && this.raise(t, I.InvalidNumber);
    const l = this.state.pos - t >= 2 && this.input.charCodeAt(t) === 48;
    if (l) {
      const f = this.input.slice(t, this.state.pos);
      if (this.recordStrictModeErrors(t, I.StrictOctalLiteral), !this.state.strict) {
        const p = f.indexOf("_");
        p > 0 && this.raise(p + t, I.ZeroDigitNumericSeparator);
      }
      o = l && !/[89]/.test(f);
    }
    let u = this.input.charCodeAt(this.state.pos);
    if (u === 46 && !o && (++this.state.pos, this.readInt(10), s = !0, u = this.input.charCodeAt(this.state.pos)), (u === 69 || u === 101) && !o && (u = this.input.charCodeAt(++this.state.pos), (u === 43 || u === 45) && ++this.state.pos, this.readInt(10) === null && this.raise(t, I.InvalidOrMissingExponent), s = !0, a = !0, u = this.input.charCodeAt(this.state.pos)), u === 110 && ((s || l) && this.raise(t, I.InvalidBigIntLiteral), ++this.state.pos, i = !0), u === 109 && (this.expectPlugin("decimal", this.state.pos), (a || l) && this.raise(t, I.InvalidDecimal), ++this.state.pos, n = !0), br(this.codePointAtPos(this.state.pos)))
      throw this.raise(this.state.pos, I.NumberIdentifier);
    const c = this.input.slice(t, this.state.pos).replace(/[_mn]/g, "");
    if (i) {
      this.finishToken(125, c);
      return;
    }
    if (n) {
      this.finishToken(126, c);
      return;
    }
    const h = o ? parseInt(c, 8) : parseFloat(c);
    this.finishToken(124, h);
  }
  readCodePoint(e) {
    const t = this.input.charCodeAt(this.state.pos);
    let s;
    if (t === 123) {
      const i = ++this.state.pos;
      if (s = this.readHexChar(this.input.indexOf("}", this.state.pos) - this.state.pos, !0, e), ++this.state.pos, s !== null && s > 1114111)
        if (e)
          this.raise(i, I.InvalidCodePoint);
        else
          return null;
    } else
      s = this.readHexChar(4, !1, e);
    return s;
  }
  readString(e) {
    let t = "", s = ++this.state.pos;
    for (; ; ) {
      if (this.state.pos >= this.length)
        throw this.raise(this.state.start, I.UnterminatedString);
      const i = this.input.charCodeAt(this.state.pos);
      if (i === e)
        break;
      if (i === 92)
        t += this.input.slice(s, this.state.pos), t += this.readEscapedChar(!1), s = this.state.pos;
      else if (i === 8232 || i === 8233)
        ++this.state.pos, ++this.state.curLine, this.state.lineStart = this.state.pos;
      else {
        if (Br(i))
          throw this.raise(this.state.start, I.UnterminatedString);
        ++this.state.pos;
      }
    }
    t += this.input.slice(s, this.state.pos++), this.finishToken(123, t);
  }
  readTmplToken() {
    let e = "", t = this.state.pos, s = !1;
    for (; ; ) {
      if (this.state.pos >= this.length)
        throw this.raise(this.state.start, I.UnterminatedTemplate);
      const i = this.input.charCodeAt(this.state.pos);
      if (i === 96 || i === 36 && this.input.charCodeAt(this.state.pos + 1) === 123) {
        if (this.state.pos === this.state.start && this.match(20))
          if (i === 36) {
            this.state.pos += 2, this.finishToken(23);
            return;
          } else {
            ++this.state.pos, this.finishToken(22);
            return;
          }
        e += this.input.slice(t, this.state.pos), this.finishToken(20, s ? null : e);
        return;
      }
      if (i === 92) {
        e += this.input.slice(t, this.state.pos);
        const n = this.readEscapedChar(!0);
        n === null ? s = !0 : e += n, t = this.state.pos;
      } else if (Br(i)) {
        switch (e += this.input.slice(t, this.state.pos), ++this.state.pos, i) {
          case 13:
            this.input.charCodeAt(this.state.pos) === 10 && ++this.state.pos;
          case 10:
            e += `
`;
            break;
          default:
            e += String.fromCharCode(i);
            break;
        }
        ++this.state.curLine, this.state.lineStart = this.state.pos, t = this.state.pos;
      } else
        ++this.state.pos;
    }
  }
  recordStrictModeErrors(e, t) {
    this.state.strict && !this.state.strictErrors.has(e) ? this.raise(e, t) : this.state.strictErrors.set(e, t);
  }
  readEscapedChar(e) {
    const t = !e, s = this.input.charCodeAt(++this.state.pos);
    switch (++this.state.pos, s) {
      case 110:
        return `
`;
      case 114:
        return "\r";
      case 120: {
        const i = this.readHexChar(2, !1, t);
        return i === null ? null : String.fromCharCode(i);
      }
      case 117: {
        const i = this.readCodePoint(t);
        return i === null ? null : String.fromCodePoint(i);
      }
      case 116:
        return "	";
      case 98:
        return "\b";
      case 118:
        return "\v";
      case 102:
        return "\f";
      case 13:
        this.input.charCodeAt(this.state.pos) === 10 && ++this.state.pos;
      case 10:
        this.state.lineStart = this.state.pos, ++this.state.curLine;
      case 8232:
      case 8233:
        return "";
      case 56:
      case 57:
        if (e)
          return null;
        this.recordStrictModeErrors(this.state.pos - 1, I.StrictNumericEscape);
      default:
        if (s >= 48 && s <= 55) {
          const i = this.state.pos - 1;
          let a = this.input.substr(this.state.pos - 1, 3).match(/^[0-7]+/)[0], o = parseInt(a, 8);
          o > 255 && (a = a.slice(0, -1), o = parseInt(a, 8)), this.state.pos += a.length - 1;
          const l = this.input.charCodeAt(this.state.pos);
          if (a !== "0" || l === 56 || l === 57) {
            if (e)
              return null;
            this.recordStrictModeErrors(i, I.StrictNumericEscape);
          }
          return String.fromCharCode(o);
        }
        return String.fromCharCode(s);
    }
  }
  readHexChar(e, t, s) {
    const i = this.state.pos, n = this.readInt(16, e, t, !1);
    return n === null && (s ? this.raise(i, I.InvalidEscapeSequence) : this.state.pos = i - 1), n;
  }
  readWord1(e) {
    this.state.containsEsc = !1;
    let t = "";
    const s = this.state.pos;
    let i = this.state.pos;
    for (e !== void 0 && (this.state.pos += e <= 65535 ? 1 : 2); this.state.pos < this.length; ) {
      const n = this.codePointAtPos(this.state.pos);
      if (ps(n))
        this.state.pos += n <= 65535 ? 1 : 2;
      else if (n === 92) {
        this.state.containsEsc = !0, t += this.input.slice(i, this.state.pos);
        const a = this.state.pos, o = this.state.pos === s ? br : ps;
        if (this.input.charCodeAt(++this.state.pos) !== 117) {
          this.raise(this.state.pos, I.MissingUnicodeEscape), i = this.state.pos - 1;
          continue;
        }
        ++this.state.pos;
        const l = this.readCodePoint(!0);
        l !== null && (o(l) || this.raise(a, I.EscapedCharNotAnIdentifier), t += String.fromCodePoint(l)), i = this.state.pos;
      } else
        break;
    }
    return t + this.input.slice(i, this.state.pos);
  }
  readWord(e) {
    const t = this.readWord1(e), s = Fl.get(t);
    s !== void 0 ? this.finishToken(s, Tr(s)) : this.finishToken(122, t);
  }
  checkKeywordEscapes() {
    const {
      type: e
    } = this.state;
    Vl(e) && this.state.containsEsc && this.raise(this.state.start, I.InvalidEscapedReservedWord, Tr(e));
  }
  updateContext(e) {
    const {
      context: t,
      type: s
    } = this.state;
    switch (s) {
      case 8:
        t.pop();
        break;
      case 5:
      case 7:
      case 23:
        t.push(ke.brace);
        break;
      case 22:
        t[t.length - 1] === ke.template ? t.pop() : t.push(ke.template);
        break;
    }
  }
}
class w1 {
  constructor() {
    this.privateNames = /* @__PURE__ */ new Set(), this.loneAccessors = /* @__PURE__ */ new Map(), this.undefinedPrivateNames = /* @__PURE__ */ new Map();
  }
}
class P1 {
  constructor(e) {
    this.stack = [], this.undefinedPrivateNames = /* @__PURE__ */ new Map(), this.raise = e;
  }
  current() {
    return this.stack[this.stack.length - 1];
  }
  enter() {
    this.stack.push(new w1());
  }
  exit() {
    const e = this.stack.pop(), t = this.current();
    for (const [s, i] of Array.from(e.undefinedPrivateNames))
      t ? t.undefinedPrivateNames.has(s) || t.undefinedPrivateNames.set(s, i) : this.raise(i, I.InvalidPrivateFieldResolution, s);
  }
  declarePrivateName(e, t, s) {
    const i = this.current();
    let n = i.privateNames.has(e);
    if (t & $a) {
      const a = n && i.loneAccessors.get(e);
      if (a) {
        const o = a & Dn, l = t & Dn, u = a & $a, c = t & $a;
        n = u === c || o !== l, n || i.loneAccessors.delete(e);
      } else
        n || i.loneAccessors.set(e, t);
    }
    n && this.raise(s, I.PrivateNameRedeclaration, e), i.privateNames.add(e), i.undefinedPrivateNames.delete(e);
  }
  usePrivateName(e, t) {
    let s;
    for (s of this.stack)
      if (s.privateNames.has(e))
        return;
    s ? s.undefinedPrivateNames.set(e, t) : this.raise(t, I.InvalidPrivateFieldResolution, e);
  }
}
const E1 = 0, ap = 1, Zl = 2, op = 3;
class fa {
  constructor(e = E1) {
    this.type = void 0, this.type = e;
  }
  canBeArrowParameterDeclaration() {
    return this.type === Zl || this.type === ap;
  }
  isCertainlyParameterDeclaration() {
    return this.type === op;
  }
}
class lp extends fa {
  constructor(e) {
    super(e), this.errors = /* @__PURE__ */ new Map();
  }
  recordDeclarationError(e, t) {
    this.errors.set(e, t);
  }
  clearDeclarationError(e) {
    this.errors.delete(e);
  }
  iterateErrors(e) {
    this.errors.forEach(e);
  }
}
class T1 {
  constructor(e) {
    this.stack = [new fa()], this.raise = e;
  }
  enter(e) {
    this.stack.push(e);
  }
  exit() {
    this.stack.pop();
  }
  recordParameterInitializerError(e, t) {
    const {
      stack: s
    } = this;
    let i = s.length - 1, n = s[i];
    for (; !n.isCertainlyParameterDeclaration(); ) {
      if (n.canBeArrowParameterDeclaration())
        n.recordDeclarationError(e, t);
      else
        return;
      n = s[--i];
    }
    this.raise(e, t);
  }
  recordParenthesizedIdentifierError(e, t) {
    const {
      stack: s
    } = this, i = s[s.length - 1];
    if (i.isCertainlyParameterDeclaration())
      this.raise(e, t);
    else if (i.canBeArrowParameterDeclaration())
      i.recordDeclarationError(e, t);
    else
      return;
  }
  recordAsyncArrowParametersError(e, t) {
    const {
      stack: s
    } = this;
    let i = s.length - 1, n = s[i];
    for (; n.canBeArrowParameterDeclaration(); )
      n.type === Zl && n.recordDeclarationError(e, t), n = s[--i];
  }
  validateAsPattern() {
    const {
      stack: e
    } = this, t = e[e.length - 1];
    !t.canBeArrowParameterDeclaration() || t.iterateErrors((s, i) => {
      this.raise(i, s);
      let n = e.length - 2, a = e[n];
      for (; a.canBeArrowParameterDeclaration(); )
        a.clearDeclarationError(i), a = e[--n];
    });
  }
}
function A1() {
  return new fa(op);
}
function _1() {
  return new lp(ap);
}
function C1() {
  return new lp(Zl);
}
function up() {
  return new fa();
}
const ms = 0, cp = 1, pa = 2, hp = 4, es = 8;
class I1 {
  constructor() {
    this.stacks = [];
  }
  enter(e) {
    this.stacks.push(e);
  }
  exit() {
    this.stacks.pop();
  }
  currentFlags() {
    return this.stacks[this.stacks.length - 1];
  }
  get hasAwait() {
    return (this.currentFlags() & pa) > 0;
  }
  get hasYield() {
    return (this.currentFlags() & cp) > 0;
  }
  get hasReturn() {
    return (this.currentFlags() & hp) > 0;
  }
  get hasIn() {
    return (this.currentFlags() & es) > 0;
  }
}
function hn(r, e) {
  return (r ? pa : 0) | (e ? cp : 0);
}
class N1 extends S1 {
  addExtra(e, t, s) {
    if (!e)
      return;
    const i = e.extra = e.extra || {};
    i[t] = s;
  }
  isContextual(e) {
    return this.state.type === e && !this.state.containsEsc;
  }
  isUnparsedContextual(e, t) {
    const s = e + t.length;
    if (this.input.slice(e, s) === t) {
      const i = this.input.charCodeAt(s);
      return !(ps(i) || (i & 64512) === 55296);
    }
    return !1;
  }
  isLookaheadContextual(e) {
    const t = this.nextTokenStart();
    return this.isUnparsedContextual(t, e);
  }
  eatContextual(e) {
    return this.isContextual(e) ? (this.next(), !0) : !1;
  }
  expectContextual(e, t) {
    this.eatContextual(e) || this.unexpected(null, t);
  }
  canInsertSemicolon() {
    return this.match(129) || this.match(8) || this.hasPrecedingLineBreak();
  }
  hasPrecedingLineBreak() {
    return Rl.test(this.input.slice(this.state.lastTokEnd, this.state.start));
  }
  hasFollowingLineBreak() {
    return wc.lastIndex = this.state.end, wc.test(this.input);
  }
  isLineTerminator() {
    return this.eat(13) || this.canInsertSemicolon();
  }
  semicolon(e = !0) {
    (e ? this.isLineTerminator() : this.eat(13)) || this.raise(this.state.lastTokEnd, I.MissingSemicolon);
  }
  expect(e, t) {
    this.eat(e) || this.unexpected(t, e);
  }
  assertNoSpace(e = "Unexpected space.") {
    this.state.start > this.state.lastTokEnd && this.raise(this.state.lastTokEnd, {
      code: Zt.SyntaxError,
      reasonCode: "UnexpectedSpace",
      template: e
    });
  }
  unexpected(e, t = {
    code: Zt.SyntaxError,
    reasonCode: "UnexpectedToken",
    template: "Unexpected token"
  }) {
    throw J0(t) && (t = {
      code: Zt.SyntaxError,
      reasonCode: "UnexpectedToken",
      template: `Unexpected token, expected "${Tr(t)}"`
    }), this.raise(e != null ? e : this.state.start, t);
  }
  expectPlugin(e, t) {
    if (!this.hasPlugin(e))
      throw this.raiseWithData(t != null ? t : this.state.start, {
        missingPlugin: [e]
      }, `This experimental syntax requires enabling the parser plugin: '${e}'`);
    return !0;
  }
  expectOnePlugin(e, t) {
    if (!e.some((s) => this.hasPlugin(s)))
      throw this.raiseWithData(t != null ? t : this.state.start, {
        missingPlugin: e
      }, `This experimental syntax requires enabling one of the following parser plugin(s): '${e.join(", ")}'`);
  }
  tryParse(e, t = this.state.clone()) {
    const s = {
      node: null
    };
    try {
      const i = e((n = null) => {
        throw s.node = n, s;
      });
      if (this.state.errors.length > t.errors.length) {
        const n = this.state;
        return this.state = t, this.state.tokensLength = n.tokensLength, {
          node: i,
          error: n.errors[t.errors.length],
          thrown: !1,
          aborted: !1,
          failState: n
        };
      }
      return {
        node: i,
        error: null,
        thrown: !1,
        aborted: !1,
        failState: null
      };
    } catch (i) {
      const n = this.state;
      if (this.state = t, i instanceof SyntaxError)
        return {
          node: null,
          error: i,
          thrown: !0,
          aborted: !1,
          failState: n
        };
      if (i === s)
        return {
          node: s.node,
          error: null,
          thrown: !1,
          aborted: !0,
          failState: n
        };
      throw i;
    }
  }
  checkExpressionErrors(e, t) {
    if (!e)
      return !1;
    const {
      shorthandAssign: s,
      doubleProto: i,
      optionalParameters: n
    } = e, a = s + i + n > -3;
    if (t)
      a && (s >= 0 && this.unexpected(s), i >= 0 && this.raise(i, I.DuplicateProto), n >= 0 && this.unexpected(n));
    else
      return a;
  }
  isLiteralPropertyName() {
    return Wf(this.state.type);
  }
  isPrivateName(e) {
    return e.type === "PrivateName";
  }
  getPrivateNameSV(e) {
    return e.id.name;
  }
  hasPropertyAsPrivateName(e) {
    return (e.type === "MemberExpression" || e.type === "OptionalMemberExpression") && this.isPrivateName(e.property);
  }
  isOptionalChain(e) {
    return e.type === "OptionalMemberExpression" || e.type === "OptionalCallExpression";
  }
  isObjectProperty(e) {
    return e.type === "ObjectProperty";
  }
  isObjectMethod(e) {
    return e.type === "ObjectMethod";
  }
  initializeScopes(e = this.options.sourceType === "module") {
    const t = this.state.labels;
    this.state.labels = [];
    const s = this.exportedIdentifiers;
    this.exportedIdentifiers = /* @__PURE__ */ new Set();
    const i = this.inModule;
    this.inModule = e;
    const n = this.scope, a = this.getScopeHandler();
    this.scope = new a(this.raise.bind(this), this.inModule);
    const o = this.prodParam;
    this.prodParam = new I1();
    const l = this.classScope;
    this.classScope = new P1(this.raise.bind(this));
    const u = this.expressionScope;
    return this.expressionScope = new T1(this.raise.bind(this)), () => {
      this.state.labels = t, this.exportedIdentifiers = s, this.inModule = i, this.scope = n, this.prodParam = o, this.classScope = l, this.expressionScope = u;
    };
  }
  enterInitialScopes() {
    let e = ms;
    this.inModule && (e |= pa), this.scope.enter(ni), this.prodParam.enter(e);
  }
}
class fn {
  constructor() {
    this.shorthandAssign = -1, this.doubleProto = -1, this.optionalParameters = -1;
  }
}
class Rn {
  constructor(e, t, s) {
    this.type = "", this.start = t, this.end = 0, this.loc = new On(s), e != null && e.options.ranges && (this.range = [t, 0]), e != null && e.filename && (this.loc.filename = e.filename);
  }
}
const eu = Rn.prototype;
eu.__clone = function() {
  const r = new Rn(), e = Object.keys(this);
  for (let t = 0, s = e.length; t < s; t++) {
    const i = e[t];
    i !== "leadingComments" && i !== "trailingComments" && i !== "innerComments" && (r[i] = this[i]);
  }
  return r;
};
function O1(r) {
  return tr(r);
}
function tr(r) {
  const {
    type: e,
    start: t,
    end: s,
    loc: i,
    range: n,
    extra: a,
    name: o
  } = r, l = Object.create(eu);
  return l.type = e, l.start = t, l.end = s, l.loc = i, l.range = n, l.extra = a, l.name = o, e === "Placeholder" && (l.expectedNode = r.expectedNode), l;
}
function k1(r) {
  const {
    type: e,
    start: t,
    end: s,
    loc: i,
    range: n,
    extra: a
  } = r;
  if (e === "Placeholder")
    return O1(r);
  const o = Object.create(eu);
  return o.type = "StringLiteral", o.start = t, o.end = s, o.loc = i, o.range = n, o.extra = a, o.value = r.value, o;
}
class M1 extends N1 {
  startNode() {
    return new Rn(this, this.state.start, this.state.startLoc);
  }
  startNodeAt(e, t) {
    return new Rn(this, e, t);
  }
  startNodeAtNode(e) {
    return this.startNodeAt(e.start, e.loc.start);
  }
  finishNode(e, t) {
    return this.finishNodeAt(e, t, this.state.lastTokEnd, this.state.lastTokEndLoc);
  }
  finishNodeAt(e, t, s, i) {
    return e.type = t, e.end = s, e.loc.end = i, this.options.ranges && (e.range[1] = s), this.options.attachComment && this.processComment(e), e;
  }
  resetStartLocation(e, t, s) {
    e.start = t, e.loc.start = s, this.options.ranges && (e.range[0] = t);
  }
  resetEndLocation(e, t = this.state.lastTokEnd, s = this.state.lastTokEndLoc) {
    e.end = t, e.loc.end = s, this.options.ranges && (e.range[1] = t);
  }
  resetStartLocationFromNode(e, t) {
    this.resetStartLocation(e, t.start, t.loc.start);
  }
}
const L1 = /* @__PURE__ */ new Set(["_", "any", "bool", "boolean", "empty", "extends", "false", "interface", "mixed", "null", "number", "static", "string", "true", "typeof", "void"]), se = Os({
  AmbiguousConditionalArrow: "Ambiguous expression: wrap the arrow functions in parentheses to disambiguate.",
  AmbiguousDeclareModuleKind: "Found both `declare module.exports` and `declare export` in the same module. Modules can only have 1 since they are either an ES module or they are a CommonJS module.",
  AssignReservedType: "Cannot overwrite reserved type %0.",
  DeclareClassElement: "The `declare` modifier can only appear on class fields.",
  DeclareClassFieldInitializer: "Initializers are not allowed in fields with the `declare` modifier.",
  DuplicateDeclareModuleExports: "Duplicate `declare module.exports` statement.",
  EnumBooleanMemberNotInitialized: "Boolean enum members need to be initialized. Use either `%0 = true,` or `%0 = false,` in enum `%1`.",
  EnumDuplicateMemberName: "Enum member names need to be unique, but the name `%0` has already been used before in enum `%1`.",
  EnumInconsistentMemberValues: "Enum `%0` has inconsistent member initializers. Either use no initializers, or consistently use literals (either booleans, numbers, or strings) for all member initializers.",
  EnumInvalidExplicitType: "Enum type `%1` is not valid. Use one of `boolean`, `number`, `string`, or `symbol` in enum `%0`.",
  EnumInvalidExplicitTypeUnknownSupplied: "Supplied enum type is not valid. Use one of `boolean`, `number`, `string`, or `symbol` in enum `%0`.",
  EnumInvalidMemberInitializerPrimaryType: "Enum `%0` has type `%2`, so the initializer of `%1` needs to be a %2 literal.",
  EnumInvalidMemberInitializerSymbolType: "Symbol enum members cannot be initialized. Use `%1,` in enum `%0`.",
  EnumInvalidMemberInitializerUnknownType: "The enum member initializer for `%1` needs to be a literal (either a boolean, number, or string) in enum `%0`.",
  EnumInvalidMemberName: "Enum member names cannot start with lowercase 'a' through 'z'. Instead of using `%0`, consider using `%1`, in enum `%2`.",
  EnumNumberMemberNotInitialized: "Number enum members need to be initialized, e.g. `%1 = 1` in enum `%0`.",
  EnumStringMemberInconsistentlyInitailized: "String enum members need to consistently either all use initializers, or use no initializers, in enum `%0`.",
  GetterMayNotHaveThisParam: "A getter cannot have a `this` parameter.",
  ImportTypeShorthandOnlyInPureImport: "The `type` and `typeof` keywords on named imports can only be used on regular `import` statements. It cannot be used with `import type` or `import typeof` statements.",
  InexactInsideExact: "Explicit inexact syntax cannot appear inside an explicit exact object type.",
  InexactInsideNonObject: "Explicit inexact syntax cannot appear in class or interface definitions.",
  InexactVariance: "Explicit inexact syntax cannot have variance.",
  InvalidNonTypeImportInDeclareModule: "Imports within a `declare module` body must always be `import type` or `import typeof`.",
  MissingTypeParamDefault: "Type parameter declaration needs a default, since a preceding type parameter declaration has a default.",
  NestedDeclareModule: "`declare module` cannot be used inside another `declare module`.",
  NestedFlowComment: "Cannot have a flow comment inside another flow comment.",
  PatternIsOptional: "A binding pattern parameter cannot be optional in an implementation signature.",
  SetterMayNotHaveThisParam: "A setter cannot have a `this` parameter.",
  SpreadVariance: "Spread properties cannot have variance.",
  ThisParamAnnotationRequired: "A type annotation is required for the `this` parameter.",
  ThisParamBannedInConstructor: "Constructors cannot have a `this` parameter; constructors don't bind `this` like other functions.",
  ThisParamMayNotBeOptional: "The `this` parameter cannot be optional.",
  ThisParamMustBeFirst: "The `this` parameter must be the first function parameter.",
  ThisParamNoDefault: "The `this` parameter may not have a default value.",
  TypeBeforeInitializer: "Type annotations must come before default assignments, e.g. instead of `age = 25: number` use `age: number = 25`.",
  TypeCastInPattern: "The type cast expression is expected to be wrapped with parenthesis.",
  UnexpectedExplicitInexactInObject: "Explicit inexact syntax must appear at the end of an inexact object.",
  UnexpectedReservedType: "Unexpected reserved type %0.",
  UnexpectedReservedUnderscore: "`_` is only allowed as a type argument to call or new.",
  UnexpectedSpaceBetweenModuloChecks: "Spaces between `%` and `checks` are not allowed here.",
  UnexpectedSpreadType: "Spread operator cannot appear in class or interface definitions.",
  UnexpectedSubtractionOperand: 'Unexpected token, expected "number" or "bigint".',
  UnexpectedTokenAfterTypeParameter: "Expected an arrow function after this type parameter declaration.",
  UnexpectedTypeParameterBeforeAsyncArrowFunction: "Type parameters must come after the async keyword, e.g. instead of `<T> async () => {}`, use `async <T>() => {}`.",
  UnsupportedDeclareExportKind: "`declare export %0` is not supported. Use `%1` instead.",
  UnsupportedStatementInDeclareModule: "Only declares and type imports are allowed inside declare module.",
  UnterminatedFlowComment: "Unterminated flow-comment."
}, Zt.SyntaxError, "flow");
function D1(r) {
  return r.type === "DeclareExportAllDeclaration" || r.type === "DeclareExportDeclaration" && (!r.declaration || r.declaration.type !== "TypeAlias" && r.declaration.type !== "InterfaceDeclaration");
}
function ja(r) {
  return r.importKind === "type" || r.importKind === "typeof";
}
function Cc(r) {
  return er(r) && r !== 91;
}
const R1 = {
  const: "declare export var",
  let: "declare export var",
  type: "export type",
  interface: "export interface"
};
function F1(r, e) {
  const t = [], s = [];
  for (let i = 0; i < r.length; i++)
    (e(r[i], i, r) ? t : s).push(r[i]);
  return [t, s];
}
const B1 = /\*?\s*@((?:no)?flow)\b/;
var U1 = (r) => class extends r {
  constructor(...e) {
    super(...e), this.flowPragma = void 0;
  }
  getScopeHandler() {
    return v1;
  }
  shouldParseTypes() {
    return this.getPluginOption("flow", "all") || this.flowPragma === "flow";
  }
  shouldParseEnums() {
    return !!this.getPluginOption("flow", "enums");
  }
  finishToken(e, t) {
    return e !== 123 && e !== 13 && e !== 26 && this.flowPragma === void 0 && (this.flowPragma = null), super.finishToken(e, t);
  }
  addComment(e) {
    if (this.flowPragma === void 0) {
      const t = B1.exec(e.value);
      if (t)
        if (t[1] === "flow")
          this.flowPragma = "flow";
        else if (t[1] === "noflow")
          this.flowPragma = "noflow";
        else
          throw new Error("Unexpected flow pragma");
    }
    return super.addComment(e);
  }
  flowParseTypeInitialiser(e) {
    const t = this.state.inType;
    this.state.inType = !0, this.expect(e || 14);
    const s = this.flowParseType();
    return this.state.inType = t, s;
  }
  flowParsePredicate() {
    const e = this.startNode(), t = this.state.start;
    return this.next(), this.expectContextual(101), this.state.lastTokStart > t + 1 && this.raise(t, se.UnexpectedSpaceBetweenModuloChecks), this.eat(10) ? (e.value = this.parseExpression(), this.expect(11), this.finishNode(e, "DeclaredPredicate")) : this.finishNode(e, "InferredPredicate");
  }
  flowParseTypeAndPredicateInitialiser() {
    const e = this.state.inType;
    this.state.inType = !0, this.expect(14);
    let t = null, s = null;
    return this.match(48) ? (this.state.inType = e, s = this.flowParsePredicate()) : (t = this.flowParseType(), this.state.inType = e, this.match(48) && (s = this.flowParsePredicate())), [t, s];
  }
  flowParseDeclareClass(e) {
    return this.next(), this.flowParseInterfaceish(e, !0), this.finishNode(e, "DeclareClass");
  }
  flowParseDeclareFunction(e) {
    this.next();
    const t = e.id = this.parseIdentifier(), s = this.startNode(), i = this.startNode();
    this.match(43) ? s.typeParameters = this.flowParseTypeParameterDeclaration() : s.typeParameters = null, this.expect(10);
    const n = this.flowParseFunctionTypeParams();
    return s.params = n.params, s.rest = n.rest, s.this = n._this, this.expect(11), [s.returnType, e.predicate] = this.flowParseTypeAndPredicateInitialiser(), i.typeAnnotation = this.finishNode(s, "FunctionTypeAnnotation"), t.typeAnnotation = this.finishNode(i, "TypeAnnotation"), this.resetEndLocation(t), this.semicolon(), this.scope.declareName(e.id.name, f1, e.id.start), this.finishNode(e, "DeclareFunction");
  }
  flowParseDeclare(e, t) {
    if (this.match(74))
      return this.flowParseDeclareClass(e);
    if (this.match(62))
      return this.flowParseDeclareFunction(e);
    if (this.match(68))
      return this.flowParseDeclareVariable(e);
    if (this.eatContextual(117))
      return this.match(16) ? this.flowParseDeclareModuleExports(e) : (t && this.raise(this.state.lastTokStart, se.NestedDeclareModule), this.flowParseDeclareModule(e));
    if (this.isContextual(120))
      return this.flowParseDeclareTypeAlias(e);
    if (this.isContextual(121))
      return this.flowParseDeclareOpaqueType(e);
    if (this.isContextual(119))
      return this.flowParseDeclareInterface(e);
    if (this.match(76))
      return this.flowParseDeclareExportDeclaration(e, t);
    throw this.unexpected();
  }
  flowParseDeclareVariable(e) {
    return this.next(), e.id = this.flowParseTypeAnnotatableIdentifier(!0), this.scope.declareName(e.id.name, Ln, e.id.start), this.semicolon(), this.finishNode(e, "DeclareVariable");
  }
  flowParseDeclareModule(e) {
    this.scope.enter(ns), this.match(123) ? e.id = this.parseExprAtom() : e.id = this.parseIdentifier();
    const t = e.body = this.startNode(), s = t.body = [];
    for (this.expect(5); !this.match(8); ) {
      let a = this.startNode();
      this.match(77) ? (this.next(), !this.isContextual(120) && !this.match(81) && this.raise(this.state.lastTokStart, se.InvalidNonTypeImportInDeclareModule), this.parseImport(a)) : (this.expectContextual(115, se.UnsupportedStatementInDeclareModule), a = this.flowParseDeclare(a, !0)), s.push(a);
    }
    this.scope.exit(), this.expect(8), this.finishNode(t, "BlockStatement");
    let i = null, n = !1;
    return s.forEach((a) => {
      D1(a) ? (i === "CommonJS" && this.raise(a.start, se.AmbiguousDeclareModuleKind), i = "ES") : a.type === "DeclareModuleExports" && (n && this.raise(a.start, se.DuplicateDeclareModuleExports), i === "ES" && this.raise(a.start, se.AmbiguousDeclareModuleKind), i = "CommonJS", n = !0);
    }), e.kind = i || "CommonJS", this.finishNode(e, "DeclareModule");
  }
  flowParseDeclareExportDeclaration(e, t) {
    if (this.expect(76), this.eat(59))
      return this.match(62) || this.match(74) ? e.declaration = this.flowParseDeclare(this.startNode()) : (e.declaration = this.flowParseType(), this.semicolon()), e.default = !0, this.finishNode(e, "DeclareExportDeclaration");
    if (this.match(69) || this.isLet() || (this.isContextual(120) || this.isContextual(119)) && !t) {
      const s = this.state.value, i = R1[s];
      throw this.raise(this.state.start, se.UnsupportedDeclareExportKind, s, i);
    }
    if (this.match(68) || this.match(62) || this.match(74) || this.isContextual(121))
      return e.declaration = this.flowParseDeclare(this.startNode()), e.default = !1, this.finishNode(e, "DeclareExportDeclaration");
    if (this.match(49) || this.match(5) || this.isContextual(119) || this.isContextual(120) || this.isContextual(121))
      return e = this.parseExport(e), e.type === "ExportNamedDeclaration" && (e.type = "ExportDeclaration", e.default = !1, delete e.exportKind), e.type = "Declare" + e.type, e;
    throw this.unexpected();
  }
  flowParseDeclareModuleExports(e) {
    return this.next(), this.expectContextual(102), e.typeAnnotation = this.flowParseTypeAnnotation(), this.semicolon(), this.finishNode(e, "DeclareModuleExports");
  }
  flowParseDeclareTypeAlias(e) {
    return this.next(), this.flowParseTypeAlias(e), e.type = "DeclareTypeAlias", e;
  }
  flowParseDeclareOpaqueType(e) {
    return this.next(), this.flowParseOpaqueType(e, !0), e.type = "DeclareOpaqueType", e;
  }
  flowParseDeclareInterface(e) {
    return this.next(), this.flowParseInterfaceish(e), this.finishNode(e, "DeclareInterface");
  }
  flowParseInterfaceish(e, t = !1) {
    if (e.id = this.flowParseRestrictedIdentifier(!t, !0), this.scope.declareName(e.id.name, t ? ip : yt, e.id.start), this.match(43) ? e.typeParameters = this.flowParseTypeParameterDeclaration() : e.typeParameters = null, e.extends = [], e.implements = [], e.mixins = [], this.eat(75))
      do
        e.extends.push(this.flowParseInterfaceExtends());
      while (!t && this.eat(12));
    if (this.isContextual(108)) {
      this.next();
      do
        e.mixins.push(this.flowParseInterfaceExtends());
      while (this.eat(12));
    }
    if (this.isContextual(104)) {
      this.next();
      do
        e.implements.push(this.flowParseInterfaceExtends());
      while (this.eat(12));
    }
    e.body = this.flowParseObjectType({
      allowStatic: t,
      allowExact: !1,
      allowSpread: !1,
      allowProto: t,
      allowInexact: !1
    });
  }
  flowParseInterfaceExtends() {
    const e = this.startNode();
    return e.id = this.flowParseQualifiedTypeIdentifier(), this.match(43) ? e.typeParameters = this.flowParseTypeParameterInstantiation() : e.typeParameters = null, this.finishNode(e, "InterfaceExtends");
  }
  flowParseInterface(e) {
    return this.flowParseInterfaceish(e), this.finishNode(e, "InterfaceDeclaration");
  }
  checkNotUnderscore(e) {
    e === "_" && this.raise(this.state.start, se.UnexpectedReservedUnderscore);
  }
  checkReservedType(e, t, s) {
    !L1.has(e) || this.raise(t, s ? se.AssignReservedType : se.UnexpectedReservedType, e);
  }
  flowParseRestrictedIdentifier(e, t) {
    return this.checkReservedType(this.state.value, this.state.start, t), this.parseIdentifier(e);
  }
  flowParseTypeAlias(e) {
    return e.id = this.flowParseRestrictedIdentifier(!1, !0), this.scope.declareName(e.id.name, yt, e.id.start), this.match(43) ? e.typeParameters = this.flowParseTypeParameterDeclaration() : e.typeParameters = null, e.right = this.flowParseTypeInitialiser(27), this.semicolon(), this.finishNode(e, "TypeAlias");
  }
  flowParseOpaqueType(e, t) {
    return this.expectContextual(120), e.id = this.flowParseRestrictedIdentifier(!0, !0), this.scope.declareName(e.id.name, yt, e.id.start), this.match(43) ? e.typeParameters = this.flowParseTypeParameterDeclaration() : e.typeParameters = null, e.supertype = null, this.match(14) && (e.supertype = this.flowParseTypeInitialiser(14)), e.impltype = null, t || (e.impltype = this.flowParseTypeInitialiser(27)), this.semicolon(), this.finishNode(e, "OpaqueType");
  }
  flowParseTypeParameter(e = !1) {
    const t = this.state.start, s = this.startNode(), i = this.flowParseVariance(), n = this.flowParseTypeAnnotatableIdentifier();
    return s.name = n.name, s.variance = i, s.bound = n.typeAnnotation, this.match(27) ? (this.eat(27), s.default = this.flowParseType()) : e && this.raise(t, se.MissingTypeParamDefault), this.finishNode(s, "TypeParameter");
  }
  flowParseTypeParameterDeclaration() {
    const e = this.state.inType, t = this.startNode();
    t.params = [], this.state.inType = !0, this.match(43) || this.match(132) ? this.next() : this.unexpected();
    let s = !1;
    do {
      const i = this.flowParseTypeParameter(s);
      t.params.push(i), i.default && (s = !0), this.match(44) || this.expect(12);
    } while (!this.match(44));
    return this.expect(44), this.state.inType = e, this.finishNode(t, "TypeParameterDeclaration");
  }
  flowParseTypeParameterInstantiation() {
    const e = this.startNode(), t = this.state.inType;
    e.params = [], this.state.inType = !0, this.expect(43);
    const s = this.state.noAnonFunctionType;
    for (this.state.noAnonFunctionType = !1; !this.match(44); )
      e.params.push(this.flowParseType()), this.match(44) || this.expect(12);
    return this.state.noAnonFunctionType = s, this.expect(44), this.state.inType = t, this.finishNode(e, "TypeParameterInstantiation");
  }
  flowParseTypeParameterInstantiationCallOrNew() {
    const e = this.startNode(), t = this.state.inType;
    for (e.params = [], this.state.inType = !0, this.expect(43); !this.match(44); )
      e.params.push(this.flowParseTypeOrImplicitInstantiation()), this.match(44) || this.expect(12);
    return this.expect(44), this.state.inType = t, this.finishNode(e, "TypeParameterInstantiation");
  }
  flowParseInterfaceType() {
    const e = this.startNode();
    if (this.expectContextual(119), e.extends = [], this.eat(75))
      do
        e.extends.push(this.flowParseInterfaceExtends());
      while (this.eat(12));
    return e.body = this.flowParseObjectType({
      allowStatic: !1,
      allowExact: !1,
      allowSpread: !1,
      allowProto: !1,
      allowInexact: !1
    }), this.finishNode(e, "InterfaceTypeAnnotation");
  }
  flowParseObjectPropertyKey() {
    return this.match(124) || this.match(123) ? this.parseExprAtom() : this.parseIdentifier(!0);
  }
  flowParseObjectTypeIndexer(e, t, s) {
    return e.static = t, this.lookahead().type === 14 ? (e.id = this.flowParseObjectPropertyKey(), e.key = this.flowParseTypeInitialiser()) : (e.id = null, e.key = this.flowParseType()), this.expect(3), e.value = this.flowParseTypeInitialiser(), e.variance = s, this.finishNode(e, "ObjectTypeIndexer");
  }
  flowParseObjectTypeInternalSlot(e, t) {
    return e.static = t, e.id = this.flowParseObjectPropertyKey(), this.expect(3), this.expect(3), this.match(43) || this.match(10) ? (e.method = !0, e.optional = !1, e.value = this.flowParseObjectTypeMethodish(this.startNodeAt(e.start, e.loc.start))) : (e.method = !1, this.eat(17) && (e.optional = !0), e.value = this.flowParseTypeInitialiser()), this.finishNode(e, "ObjectTypeInternalSlot");
  }
  flowParseObjectTypeMethodish(e) {
    for (e.params = [], e.rest = null, e.typeParameters = null, e.this = null, this.match(43) && (e.typeParameters = this.flowParseTypeParameterDeclaration()), this.expect(10), this.match(72) && (e.this = this.flowParseFunctionTypeParam(!0), e.this.name = null, this.match(11) || this.expect(12)); !this.match(11) && !this.match(21); )
      e.params.push(this.flowParseFunctionTypeParam(!1)), this.match(11) || this.expect(12);
    return this.eat(21) && (e.rest = this.flowParseFunctionTypeParam(!1)), this.expect(11), e.returnType = this.flowParseTypeInitialiser(), this.finishNode(e, "FunctionTypeAnnotation");
  }
  flowParseObjectTypeCallProperty(e, t) {
    const s = this.startNode();
    return e.static = t, e.value = this.flowParseObjectTypeMethodish(s), this.finishNode(e, "ObjectTypeCallProperty");
  }
  flowParseObjectType({
    allowStatic: e,
    allowExact: t,
    allowSpread: s,
    allowProto: i,
    allowInexact: n
  }) {
    const a = this.state.inType;
    this.state.inType = !0;
    const o = this.startNode();
    o.callProperties = [], o.properties = [], o.indexers = [], o.internalSlots = [];
    let l, u, c = !1;
    for (t && this.match(6) ? (this.expect(6), l = 9, u = !0) : (this.expect(5), l = 8, u = !1), o.exact = u; !this.match(l); ) {
      let f = !1, p = null, x = null;
      const d = this.startNode();
      if (i && this.isContextual(109)) {
        const y = this.lookahead();
        y.type !== 14 && y.type !== 17 && (this.next(), p = this.state.start, e = !1);
      }
      if (e && this.isContextual(98)) {
        const y = this.lookahead();
        y.type !== 14 && y.type !== 17 && (this.next(), f = !0);
      }
      const m = this.flowParseVariance();
      if (this.eat(0))
        p != null && this.unexpected(p), this.eat(0) ? (m && this.unexpected(m.start), o.internalSlots.push(this.flowParseObjectTypeInternalSlot(d, f))) : o.indexers.push(this.flowParseObjectTypeIndexer(d, f, m));
      else if (this.match(10) || this.match(43))
        p != null && this.unexpected(p), m && this.unexpected(m.start), o.callProperties.push(this.flowParseObjectTypeCallProperty(d, f));
      else {
        let y = "init";
        if (this.isContextual(92) || this.isContextual(97)) {
          const T = this.lookahead();
          Wf(T.type) && (y = this.state.value, this.next());
        }
        const _ = this.flowParseObjectTypeProperty(d, f, p, m, y, s, n != null ? n : !u);
        _ === null ? (c = !0, x = this.state.lastTokStart) : o.properties.push(_);
      }
      this.flowObjectTypeSemicolon(), x && !this.match(8) && !this.match(9) && this.raise(x, se.UnexpectedExplicitInexactInObject);
    }
    this.expect(l), s && (o.inexact = c);
    const h = this.finishNode(o, "ObjectTypeAnnotation");
    return this.state.inType = a, h;
  }
  flowParseObjectTypeProperty(e, t, s, i, n, a, o) {
    if (this.eat(21))
      return this.match(12) || this.match(13) || this.match(8) || this.match(9) ? (a ? o || this.raise(this.state.lastTokStart, se.InexactInsideExact) : this.raise(this.state.lastTokStart, se.InexactInsideNonObject), i && this.raise(i.start, se.InexactVariance), null) : (a || this.raise(this.state.lastTokStart, se.UnexpectedSpreadType), s != null && this.unexpected(s), i && this.raise(i.start, se.SpreadVariance), e.argument = this.flowParseType(), this.finishNode(e, "ObjectTypeSpreadProperty"));
    {
      e.key = this.flowParseObjectPropertyKey(), e.static = t, e.proto = s != null, e.kind = n;
      let l = !1;
      return this.match(43) || this.match(10) ? (e.method = !0, s != null && this.unexpected(s), i && this.unexpected(i.start), e.value = this.flowParseObjectTypeMethodish(this.startNodeAt(e.start, e.loc.start)), (n === "get" || n === "set") && this.flowCheckGetterSetterParams(e), !a && e.key.name === "constructor" && e.value.this && this.raise(e.value.this.start, se.ThisParamBannedInConstructor)) : (n !== "init" && this.unexpected(), e.method = !1, this.eat(17) && (l = !0), e.value = this.flowParseTypeInitialiser(), e.variance = i), e.optional = l, this.finishNode(e, "ObjectTypeProperty");
    }
  }
  flowCheckGetterSetterParams(e) {
    const t = e.kind === "get" ? 0 : 1, s = e.start, i = e.value.params.length + (e.value.rest ? 1 : 0);
    e.value.this && this.raise(e.value.this.start, e.kind === "get" ? se.GetterMayNotHaveThisParam : se.SetterMayNotHaveThisParam), i !== t && (e.kind === "get" ? this.raise(s, I.BadGetterArity) : this.raise(s, I.BadSetterArity)), e.kind === "set" && e.value.rest && this.raise(s, I.BadSetterRestParameter);
  }
  flowObjectTypeSemicolon() {
    !this.eat(13) && !this.eat(12) && !this.match(8) && !this.match(9) && this.unexpected();
  }
  flowParseQualifiedTypeIdentifier(e, t, s) {
    e = e || this.state.start, t = t || this.state.startLoc;
    let i = s || this.flowParseRestrictedIdentifier(!0);
    for (; this.eat(16); ) {
      const n = this.startNodeAt(e, t);
      n.qualification = i, n.id = this.flowParseRestrictedIdentifier(!0), i = this.finishNode(n, "QualifiedTypeIdentifier");
    }
    return i;
  }
  flowParseGenericType(e, t, s) {
    const i = this.startNodeAt(e, t);
    return i.typeParameters = null, i.id = this.flowParseQualifiedTypeIdentifier(e, t, s), this.match(43) && (i.typeParameters = this.flowParseTypeParameterInstantiation()), this.finishNode(i, "GenericTypeAnnotation");
  }
  flowParseTypeofType() {
    const e = this.startNode();
    return this.expect(81), e.argument = this.flowParsePrimaryType(), this.finishNode(e, "TypeofTypeAnnotation");
  }
  flowParseTupleType() {
    const e = this.startNode();
    for (e.types = [], this.expect(0); this.state.pos < this.length && !this.match(3) && (e.types.push(this.flowParseType()), !this.match(3)); )
      this.expect(12);
    return this.expect(3), this.finishNode(e, "TupleTypeAnnotation");
  }
  flowParseFunctionTypeParam(e) {
    let t = null, s = !1, i = null;
    const n = this.startNode(), a = this.lookahead(), o = this.state.type === 72;
    return a.type === 14 || a.type === 17 ? (o && !e && this.raise(n.start, se.ThisParamMustBeFirst), t = this.parseIdentifier(o), this.eat(17) && (s = !0, o && this.raise(n.start, se.ThisParamMayNotBeOptional)), i = this.flowParseTypeInitialiser()) : i = this.flowParseType(), n.name = t, n.optional = s, n.typeAnnotation = i, this.finishNode(n, "FunctionTypeParam");
  }
  reinterpretTypeAsFunctionTypeParam(e) {
    const t = this.startNodeAt(e.start, e.loc.start);
    return t.name = null, t.optional = !1, t.typeAnnotation = e, this.finishNode(t, "FunctionTypeParam");
  }
  flowParseFunctionTypeParams(e = []) {
    let t = null, s = null;
    for (this.match(72) && (s = this.flowParseFunctionTypeParam(!0), s.name = null, this.match(11) || this.expect(12)); !this.match(11) && !this.match(21); )
      e.push(this.flowParseFunctionTypeParam(!1)), this.match(11) || this.expect(12);
    return this.eat(21) && (t = this.flowParseFunctionTypeParam(!1)), {
      params: e,
      rest: t,
      _this: s
    };
  }
  flowIdentToTypeAnnotation(e, t, s, i) {
    switch (i.name) {
      case "any":
        return this.finishNode(s, "AnyTypeAnnotation");
      case "bool":
      case "boolean":
        return this.finishNode(s, "BooleanTypeAnnotation");
      case "mixed":
        return this.finishNode(s, "MixedTypeAnnotation");
      case "empty":
        return this.finishNode(s, "EmptyTypeAnnotation");
      case "number":
        return this.finishNode(s, "NumberTypeAnnotation");
      case "string":
        return this.finishNode(s, "StringTypeAnnotation");
      case "symbol":
        return this.finishNode(s, "SymbolTypeAnnotation");
      default:
        return this.checkNotUnderscore(i.name), this.flowParseGenericType(e, t, i);
    }
  }
  flowParsePrimaryType() {
    const e = this.state.start, t = this.state.startLoc, s = this.startNode();
    let i, n, a = !1;
    const o = this.state.noAnonFunctionType;
    switch (this.state.type) {
      case 5:
        return this.flowParseObjectType({
          allowStatic: !1,
          allowExact: !1,
          allowSpread: !0,
          allowProto: !1,
          allowInexact: !0
        });
      case 6:
        return this.flowParseObjectType({
          allowStatic: !1,
          allowExact: !0,
          allowSpread: !0,
          allowProto: !1,
          allowInexact: !1
        });
      case 0:
        return this.state.noAnonFunctionType = !1, n = this.flowParseTupleType(), this.state.noAnonFunctionType = o, n;
      case 43:
        return s.typeParameters = this.flowParseTypeParameterDeclaration(), this.expect(10), i = this.flowParseFunctionTypeParams(), s.params = i.params, s.rest = i.rest, s.this = i._this, this.expect(11), this.expect(19), s.returnType = this.flowParseType(), this.finishNode(s, "FunctionTypeAnnotation");
      case 10:
        if (this.next(), !this.match(11) && !this.match(21))
          if (Se(this.state.type) || this.match(72)) {
            const l = this.lookahead().type;
            a = l !== 17 && l !== 14;
          } else
            a = !0;
        if (a) {
          if (this.state.noAnonFunctionType = !1, n = this.flowParseType(), this.state.noAnonFunctionType = o, this.state.noAnonFunctionType || !(this.match(12) || this.match(11) && this.lookahead().type === 19))
            return this.expect(11), n;
          this.eat(12);
        }
        return n ? i = this.flowParseFunctionTypeParams([this.reinterpretTypeAsFunctionTypeParam(n)]) : i = this.flowParseFunctionTypeParams(), s.params = i.params, s.rest = i.rest, s.this = i._this, this.expect(11), this.expect(19), s.returnType = this.flowParseType(), s.typeParameters = null, this.finishNode(s, "FunctionTypeAnnotation");
      case 123:
        return this.parseLiteral(this.state.value, "StringLiteralTypeAnnotation");
      case 79:
      case 80:
        return s.value = this.match(79), this.next(), this.finishNode(s, "BooleanLiteralTypeAnnotation");
      case 47:
        if (this.state.value === "-") {
          if (this.next(), this.match(124))
            return this.parseLiteralAtNode(-this.state.value, "NumberLiteralTypeAnnotation", s);
          if (this.match(125))
            return this.parseLiteralAtNode(-this.state.value, "BigIntLiteralTypeAnnotation", s);
          throw this.raise(this.state.start, se.UnexpectedSubtractionOperand);
        }
        throw this.unexpected();
      case 124:
        return this.parseLiteral(this.state.value, "NumberLiteralTypeAnnotation");
      case 125:
        return this.parseLiteral(this.state.value, "BigIntLiteralTypeAnnotation");
      case 82:
        return this.next(), this.finishNode(s, "VoidTypeAnnotation");
      case 78:
        return this.next(), this.finishNode(s, "NullLiteralTypeAnnotation");
      case 72:
        return this.next(), this.finishNode(s, "ThisTypeAnnotation");
      case 49:
        return this.next(), this.finishNode(s, "ExistsTypeAnnotation");
      case 81:
        return this.flowParseTypeofType();
      default:
        if (Vl(this.state.type)) {
          const l = Tr(this.state.type);
          return this.next(), super.createIdentifier(s, l);
        } else if (Se(this.state.type))
          return this.isContextual(119) ? this.flowParseInterfaceType() : this.flowIdentToTypeAnnotation(e, t, s, this.parseIdentifier());
    }
    throw this.unexpected();
  }
  flowParsePostfixType() {
    const e = this.state.start, t = this.state.startLoc;
    let s = this.flowParsePrimaryType(), i = !1;
    for (; (this.match(0) || this.match(18)) && !this.canInsertSemicolon(); ) {
      const n = this.startNodeAt(e, t), a = this.eat(18);
      i = i || a, this.expect(0), !a && this.match(3) ? (n.elementType = s, this.next(), s = this.finishNode(n, "ArrayTypeAnnotation")) : (n.objectType = s, n.indexType = this.flowParseType(), this.expect(3), i ? (n.optional = a, s = this.finishNode(n, "OptionalIndexedAccessType")) : s = this.finishNode(n, "IndexedAccessType"));
    }
    return s;
  }
  flowParsePrefixType() {
    const e = this.startNode();
    return this.eat(17) ? (e.typeAnnotation = this.flowParsePrefixType(), this.finishNode(e, "NullableTypeAnnotation")) : this.flowParsePostfixType();
  }
  flowParseAnonFunctionWithoutParens() {
    const e = this.flowParsePrefixType();
    if (!this.state.noAnonFunctionType && this.eat(19)) {
      const t = this.startNodeAt(e.start, e.loc.start);
      return t.params = [this.reinterpretTypeAsFunctionTypeParam(e)], t.rest = null, t.this = null, t.returnType = this.flowParseType(), t.typeParameters = null, this.finishNode(t, "FunctionTypeAnnotation");
    }
    return e;
  }
  flowParseIntersectionType() {
    const e = this.startNode();
    this.eat(41);
    const t = this.flowParseAnonFunctionWithoutParens();
    for (e.types = [t]; this.eat(41); )
      e.types.push(this.flowParseAnonFunctionWithoutParens());
    return e.types.length === 1 ? t : this.finishNode(e, "IntersectionTypeAnnotation");
  }
  flowParseUnionType() {
    const e = this.startNode();
    this.eat(39);
    const t = this.flowParseIntersectionType();
    for (e.types = [t]; this.eat(39); )
      e.types.push(this.flowParseIntersectionType());
    return e.types.length === 1 ? t : this.finishNode(e, "UnionTypeAnnotation");
  }
  flowParseType() {
    const e = this.state.inType;
    this.state.inType = !0;
    const t = this.flowParseUnionType();
    return this.state.inType = e, t;
  }
  flowParseTypeOrImplicitInstantiation() {
    if (this.state.type === 122 && this.state.value === "_") {
      const e = this.state.start, t = this.state.startLoc, s = this.parseIdentifier();
      return this.flowParseGenericType(e, t, s);
    } else
      return this.flowParseType();
  }
  flowParseTypeAnnotation() {
    const e = this.startNode();
    return e.typeAnnotation = this.flowParseTypeInitialiser(), this.finishNode(e, "TypeAnnotation");
  }
  flowParseTypeAnnotatableIdentifier(e) {
    const t = e ? this.parseIdentifier() : this.flowParseRestrictedIdentifier();
    return this.match(14) && (t.typeAnnotation = this.flowParseTypeAnnotation(), this.resetEndLocation(t)), t;
  }
  typeCastToParameter(e) {
    return e.expression.typeAnnotation = e.typeAnnotation, this.resetEndLocation(e.expression, e.typeAnnotation.end, e.typeAnnotation.loc.end), e.expression;
  }
  flowParseVariance() {
    let e = null;
    return this.match(47) && (e = this.startNode(), this.state.value === "+" ? e.kind = "plus" : e.kind = "minus", this.next(), this.finishNode(e, "Variance")), e;
  }
  parseFunctionBody(e, t, s = !1) {
    return t ? this.forwardNoArrowParamsConversionAt(e, () => super.parseFunctionBody(e, !0, s)) : super.parseFunctionBody(e, !1, s);
  }
  parseFunctionBodyAndFinish(e, t, s = !1) {
    if (this.match(14)) {
      const i = this.startNode();
      [i.typeAnnotation, e.predicate] = this.flowParseTypeAndPredicateInitialiser(), e.returnType = i.typeAnnotation ? this.finishNode(i, "TypeAnnotation") : null;
    }
    super.parseFunctionBodyAndFinish(e, t, s);
  }
  parseStatement(e, t) {
    if (this.state.strict && this.isContextual(119)) {
      const i = this.lookahead();
      if (er(i.type)) {
        const n = this.startNode();
        return this.next(), this.flowParseInterface(n);
      }
    } else if (this.shouldParseEnums() && this.isContextual(116)) {
      const i = this.startNode();
      return this.next(), this.flowParseEnumDeclaration(i);
    }
    const s = super.parseStatement(e, t);
    return this.flowPragma === void 0 && !this.isValidDirective(s) && (this.flowPragma = null), s;
  }
  parseExpressionStatement(e, t) {
    if (t.type === "Identifier") {
      if (t.name === "declare") {
        if (this.match(74) || Se(this.state.type) || this.match(62) || this.match(68) || this.match(76))
          return this.flowParseDeclare(e);
      } else if (Se(this.state.type)) {
        if (t.name === "interface")
          return this.flowParseInterface(e);
        if (t.name === "type")
          return this.flowParseTypeAlias(e);
        if (t.name === "opaque")
          return this.flowParseOpaqueType(e, !1);
      }
    }
    return super.parseExpressionStatement(e, t);
  }
  shouldParseExportDeclaration() {
    const {
      type: e
    } = this.state;
    return Ec(e) || this.shouldParseEnums() && e === 116 ? !this.state.containsEsc : super.shouldParseExportDeclaration();
  }
  isExportDefaultSpecifier() {
    const {
      type: e
    } = this.state;
    return Ec(e) || this.shouldParseEnums() && e === 116 ? this.state.containsEsc : super.isExportDefaultSpecifier();
  }
  parseExportDefaultExpression() {
    if (this.shouldParseEnums() && this.isContextual(116)) {
      const e = this.startNode();
      return this.next(), this.flowParseEnumDeclaration(e);
    }
    return super.parseExportDefaultExpression();
  }
  parseConditional(e, t, s, i) {
    if (!this.match(17))
      return e;
    if (this.state.maybeInArrowParameters) {
      const f = this.lookaheadCharCode();
      if (f === 44 || f === 61 || f === 58 || f === 41)
        return this.setOptionalParametersError(i), e;
    }
    this.expect(17);
    const n = this.state.clone(), a = this.state.noArrowAt, o = this.startNodeAt(t, s);
    let {
      consequent: l,
      failed: u
    } = this.tryParseConditionalConsequent(), [c, h] = this.getArrowLikeExpressions(l);
    if (u || h.length > 0) {
      const f = [...a];
      if (h.length > 0) {
        this.state = n, this.state.noArrowAt = f;
        for (let p = 0; p < h.length; p++)
          f.push(h[p].start);
        ({
          consequent: l,
          failed: u
        } = this.tryParseConditionalConsequent()), [c, h] = this.getArrowLikeExpressions(l);
      }
      u && c.length > 1 && this.raise(n.start, se.AmbiguousConditionalArrow), u && c.length === 1 && (this.state = n, f.push(c[0].start), this.state.noArrowAt = f, {
        consequent: l,
        failed: u
      } = this.tryParseConditionalConsequent());
    }
    return this.getArrowLikeExpressions(l, !0), this.state.noArrowAt = a, this.expect(14), o.test = e, o.consequent = l, o.alternate = this.forwardNoArrowParamsConversionAt(o, () => this.parseMaybeAssign(void 0, void 0)), this.finishNode(o, "ConditionalExpression");
  }
  tryParseConditionalConsequent() {
    this.state.noArrowParamsConversionAt.push(this.state.start);
    const e = this.parseMaybeAssignAllowIn(), t = !this.match(14);
    return this.state.noArrowParamsConversionAt.pop(), {
      consequent: e,
      failed: t
    };
  }
  getArrowLikeExpressions(e, t) {
    const s = [e], i = [];
    for (; s.length !== 0; ) {
      const n = s.pop();
      n.type === "ArrowFunctionExpression" ? (n.typeParameters || !n.returnType ? this.finishArrowValidation(n) : i.push(n), s.push(n.body)) : n.type === "ConditionalExpression" && (s.push(n.consequent), s.push(n.alternate));
    }
    return t ? (i.forEach((n) => this.finishArrowValidation(n)), [i, []]) : F1(i, (n) => n.params.every((a) => this.isAssignable(a, !0)));
  }
  finishArrowValidation(e) {
    var t;
    this.toAssignableList(e.params, (t = e.extra) == null ? void 0 : t.trailingComma, !1), this.scope.enter(Yt | Hl), super.checkParams(e, !1, !0), this.scope.exit();
  }
  forwardNoArrowParamsConversionAt(e, t) {
    let s;
    return this.state.noArrowParamsConversionAt.indexOf(e.start) !== -1 ? (this.state.noArrowParamsConversionAt.push(this.state.start), s = t(), this.state.noArrowParamsConversionAt.pop()) : s = t(), s;
  }
  parseParenItem(e, t, s) {
    if (e = super.parseParenItem(e, t, s), this.eat(17) && (e.optional = !0, this.resetEndLocation(e)), this.match(14)) {
      const i = this.startNodeAt(t, s);
      return i.expression = e, i.typeAnnotation = this.flowParseTypeAnnotation(), this.finishNode(i, "TypeCastExpression");
    }
    return e;
  }
  assertModuleNodeAllowed(e) {
    e.type === "ImportDeclaration" && (e.importKind === "type" || e.importKind === "typeof") || e.type === "ExportNamedDeclaration" && e.exportKind === "type" || e.type === "ExportAllDeclaration" && e.exportKind === "type" || super.assertModuleNodeAllowed(e);
  }
  parseExport(e) {
    const t = super.parseExport(e);
    return (t.type === "ExportNamedDeclaration" || t.type === "ExportAllDeclaration") && (t.exportKind = t.exportKind || "value"), t;
  }
  parseExportDeclaration(e) {
    if (this.isContextual(120)) {
      e.exportKind = "type";
      const t = this.startNode();
      return this.next(), this.match(5) ? (e.specifiers = this.parseExportSpecifiers(!0), this.parseExportFrom(e), null) : this.flowParseTypeAlias(t);
    } else if (this.isContextual(121)) {
      e.exportKind = "type";
      const t = this.startNode();
      return this.next(), this.flowParseOpaqueType(t, !1);
    } else if (this.isContextual(119)) {
      e.exportKind = "type";
      const t = this.startNode();
      return this.next(), this.flowParseInterface(t);
    } else if (this.shouldParseEnums() && this.isContextual(116)) {
      e.exportKind = "value";
      const t = this.startNode();
      return this.next(), this.flowParseEnumDeclaration(t);
    } else
      return super.parseExportDeclaration(e);
  }
  eatExportStar(e) {
    return super.eatExportStar(...arguments) ? !0 : this.isContextual(120) && this.lookahead().type === 49 ? (e.exportKind = "type", this.next(), this.next(), !0) : !1;
  }
  maybeParseExportNamespaceSpecifier(e) {
    const t = this.state.start, s = super.maybeParseExportNamespaceSpecifier(e);
    return s && e.exportKind === "type" && this.unexpected(t), s;
  }
  parseClassId(e, t, s) {
    super.parseClassId(e, t, s), this.match(43) && (e.typeParameters = this.flowParseTypeParameterDeclaration());
  }
  parseClassMember(e, t, s) {
    const i = this.state.start;
    if (this.isContextual(115)) {
      if (this.parseClassMemberFromModifier(e, t))
        return;
      t.declare = !0;
    }
    super.parseClassMember(e, t, s), t.declare && (t.type !== "ClassProperty" && t.type !== "ClassPrivateProperty" && t.type !== "PropertyDefinition" ? this.raise(i, se.DeclareClassElement) : t.value && this.raise(t.value.start, se.DeclareClassFieldInitializer));
  }
  isIterator(e) {
    return e === "iterator" || e === "asyncIterator";
  }
  readIterator() {
    const e = super.readWord1(), t = "@@" + e;
    (!this.isIterator(e) || !this.state.inType) && this.raise(this.state.pos, I.InvalidIdentifier, t), this.finishToken(122, t);
  }
  getTokenFromCode(e) {
    const t = this.input.charCodeAt(this.state.pos + 1);
    return e === 123 && t === 124 ? this.finishOp(6, 2) : this.state.inType && (e === 62 || e === 60) ? this.finishOp(e === 62 ? 44 : 43, 1) : this.state.inType && e === 63 ? t === 46 ? this.finishOp(18, 2) : this.finishOp(17, 1) : i1(e, t) ? (this.state.pos += 2, this.readIterator()) : super.getTokenFromCode(e);
  }
  isAssignable(e, t) {
    return e.type === "TypeCastExpression" ? this.isAssignable(e.expression, t) : super.isAssignable(e, t);
  }
  toAssignable(e, t = !1) {
    return e.type === "TypeCastExpression" ? super.toAssignable(this.typeCastToParameter(e), t) : super.toAssignable(e, t);
  }
  toAssignableList(e, t, s) {
    for (let i = 0; i < e.length; i++) {
      const n = e[i];
      (n == null ? void 0 : n.type) === "TypeCastExpression" && (e[i] = this.typeCastToParameter(n));
    }
    return super.toAssignableList(e, t, s);
  }
  toReferencedList(e, t) {
    for (let i = 0; i < e.length; i++) {
      var s;
      const n = e[i];
      n && n.type === "TypeCastExpression" && !((s = n.extra) != null && s.parenthesized) && (e.length > 1 || !t) && this.raise(n.typeAnnotation.start, se.TypeCastInPattern);
    }
    return e;
  }
  parseArrayLike(e, t, s, i) {
    const n = super.parseArrayLike(e, t, s, i);
    return t && !this.state.maybeInArrowParameters && this.toReferencedList(n.elements), n;
  }
  checkLVal(e, ...t) {
    if (e.type !== "TypeCastExpression")
      return super.checkLVal(e, ...t);
  }
  parseClassProperty(e) {
    return this.match(14) && (e.typeAnnotation = this.flowParseTypeAnnotation()), super.parseClassProperty(e);
  }
  parseClassPrivateProperty(e) {
    return this.match(14) && (e.typeAnnotation = this.flowParseTypeAnnotation()), super.parseClassPrivateProperty(e);
  }
  isClassMethod() {
    return this.match(43) || super.isClassMethod();
  }
  isClassProperty() {
    return this.match(14) || super.isClassProperty();
  }
  isNonstaticConstructor(e) {
    return !this.match(14) && super.isNonstaticConstructor(e);
  }
  pushClassMethod(e, t, s, i, n, a) {
    if (t.variance && this.unexpected(t.variance.start), delete t.variance, this.match(43) && (t.typeParameters = this.flowParseTypeParameterDeclaration()), super.pushClassMethod(e, t, s, i, n, a), t.params && n) {
      const o = t.params;
      o.length > 0 && this.isThisParam(o[0]) && this.raise(t.start, se.ThisParamBannedInConstructor);
    } else if (t.type === "MethodDefinition" && n && t.value.params) {
      const o = t.value.params;
      o.length > 0 && this.isThisParam(o[0]) && this.raise(t.start, se.ThisParamBannedInConstructor);
    }
  }
  pushClassPrivateMethod(e, t, s, i) {
    t.variance && this.unexpected(t.variance.start), delete t.variance, this.match(43) && (t.typeParameters = this.flowParseTypeParameterDeclaration()), super.pushClassPrivateMethod(e, t, s, i);
  }
  parseClassSuper(e) {
    if (super.parseClassSuper(e), e.superClass && this.match(43) && (e.superTypeParameters = this.flowParseTypeParameterInstantiation()), this.isContextual(104)) {
      this.next();
      const t = e.implements = [];
      do {
        const s = this.startNode();
        s.id = this.flowParseRestrictedIdentifier(!0), this.match(43) ? s.typeParameters = this.flowParseTypeParameterInstantiation() : s.typeParameters = null, t.push(this.finishNode(s, "ClassImplements"));
      } while (this.eat(12));
    }
  }
  checkGetterSetterParams(e) {
    super.checkGetterSetterParams(e);
    const t = this.getObjectOrClassMethodParams(e);
    if (t.length > 0) {
      const s = t[0];
      this.isThisParam(s) && e.kind === "get" ? this.raise(s.start, se.GetterMayNotHaveThisParam) : this.isThisParam(s) && this.raise(s.start, se.SetterMayNotHaveThisParam);
    }
  }
  parsePropertyNamePrefixOperator(e) {
    e.variance = this.flowParseVariance();
  }
  parseObjPropValue(e, t, s, i, n, a, o, l) {
    e.variance && this.unexpected(e.variance.start), delete e.variance;
    let u;
    this.match(43) && !o && (u = this.flowParseTypeParameterDeclaration(), this.match(10) || this.unexpected()), super.parseObjPropValue(e, t, s, i, n, a, o, l), u && ((e.value || e).typeParameters = u);
  }
  parseAssignableListItemTypes(e) {
    return this.eat(17) && (e.type !== "Identifier" && this.raise(e.start, se.PatternIsOptional), this.isThisParam(e) && this.raise(e.start, se.ThisParamMayNotBeOptional), e.optional = !0), this.match(14) ? e.typeAnnotation = this.flowParseTypeAnnotation() : this.isThisParam(e) && this.raise(e.start, se.ThisParamAnnotationRequired), this.match(27) && this.isThisParam(e) && this.raise(e.start, se.ThisParamNoDefault), this.resetEndLocation(e), e;
  }
  parseMaybeDefault(e, t, s) {
    const i = super.parseMaybeDefault(e, t, s);
    return i.type === "AssignmentPattern" && i.typeAnnotation && i.right.start < i.typeAnnotation.start && this.raise(i.typeAnnotation.start, se.TypeBeforeInitializer), i;
  }
  shouldParseDefaultImport(e) {
    return ja(e) ? Cc(this.state.type) : super.shouldParseDefaultImport(e);
  }
  parseImportSpecifierLocal(e, t, s, i) {
    t.local = ja(e) ? this.flowParseRestrictedIdentifier(!0, !0) : this.parseIdentifier(), this.checkLVal(t.local, i, yt), e.specifiers.push(this.finishNode(t, s));
  }
  maybeParseDefaultImportSpecifier(e) {
    e.importKind = "value";
    let t = null;
    if (this.match(81) ? t = "typeof" : this.isContextual(120) && (t = "type"), t) {
      const s = this.lookahead(), {
        type: i
      } = s;
      t === "type" && i === 49 && this.unexpected(s.start), (Cc(i) || i === 5 || i === 49) && (this.next(), e.importKind = t);
    }
    return super.maybeParseDefaultImportSpecifier(e);
  }
  parseImportSpecifier(e, t, s, i) {
    const n = e.imported;
    let a = null;
    n.type === "Identifier" && (n.name === "type" ? a = "type" : n.name === "typeof" && (a = "typeof"));
    let o = !1;
    if (this.isContextual(87) && !this.isLookaheadContextual("as")) {
      const u = this.parseIdentifier(!0);
      a !== null && !er(this.state.type) ? (e.imported = u, e.importKind = a, e.local = tr(u)) : (e.imported = n, e.importKind = null, e.local = this.parseIdentifier());
    } else {
      if (a !== null && er(this.state.type))
        e.imported = this.parseIdentifier(!0), e.importKind = a;
      else {
        if (t)
          throw this.raise(e.start, I.ImportBindingIsString, n.value);
        e.imported = n, e.importKind = null;
      }
      this.eatContextual(87) ? e.local = this.parseIdentifier() : (o = !0, e.local = tr(e.imported));
    }
    const l = ja(e);
    return s && l && this.raise(e.start, se.ImportTypeShorthandOnlyInPureImport), (s || l) && this.checkReservedType(e.local.name, e.local.start, !0), o && !s && !l && this.checkReservedWord(e.local.name, e.start, !0, !0), this.checkLVal(e.local, "import specifier", yt), this.finishNode(e, "ImportSpecifier");
  }
  parseBindingAtom() {
    switch (this.state.type) {
      case 72:
        return this.parseIdentifier(!0);
      default:
        return super.parseBindingAtom();
    }
  }
  parseFunctionParams(e, t) {
    const s = e.kind;
    s !== "get" && s !== "set" && this.match(43) && (e.typeParameters = this.flowParseTypeParameterDeclaration()), super.parseFunctionParams(e, t);
  }
  parseVarId(e, t) {
    super.parseVarId(e, t), this.match(14) && (e.id.typeAnnotation = this.flowParseTypeAnnotation(), this.resetEndLocation(e.id));
  }
  parseAsyncArrowFromCallExpression(e, t) {
    if (this.match(14)) {
      const s = this.state.noAnonFunctionType;
      this.state.noAnonFunctionType = !0, e.returnType = this.flowParseTypeAnnotation(), this.state.noAnonFunctionType = s;
    }
    return super.parseAsyncArrowFromCallExpression(e, t);
  }
  shouldParseAsyncArrow() {
    return this.match(14) || super.shouldParseAsyncArrow();
  }
  parseMaybeAssign(e, t) {
    var s;
    let i = null, n;
    if (this.hasPlugin("jsx") && (this.match(132) || this.match(43))) {
      if (i = this.state.clone(), n = this.tryParse(() => super.parseMaybeAssign(e, t), i), !n.error)
        return n.node;
      const {
        context: l
      } = this.state, u = l[l.length - 1];
      u === ke.j_oTag ? l.length -= 2 : u === ke.j_expr && (l.length -= 1);
    }
    if ((s = n) != null && s.error || this.match(43)) {
      var a, o;
      i = i || this.state.clone();
      let l;
      const u = this.tryParse((h) => {
        var f;
        l = this.flowParseTypeParameterDeclaration();
        const p = this.forwardNoArrowParamsConversionAt(l, () => {
          const d = super.parseMaybeAssign(e, t);
          return this.resetStartLocationFromNode(d, l), d;
        });
        (f = p.extra) != null && f.parenthesized && h();
        const x = this.maybeUnwrapTypeCastExpression(p);
        return x.type !== "ArrowFunctionExpression" && h(), x.typeParameters = l, this.resetStartLocationFromNode(x, l), p;
      }, i);
      let c = null;
      if (u.node && this.maybeUnwrapTypeCastExpression(u.node).type === "ArrowFunctionExpression") {
        if (!u.error && !u.aborted)
          return u.node.async && this.raise(l.start, se.UnexpectedTypeParameterBeforeAsyncArrowFunction), u.node;
        c = u.node;
      }
      if ((a = n) != null && a.node)
        return this.state = n.failState, n.node;
      if (c)
        return this.state = u.failState, c;
      throw (o = n) != null && o.thrown ? n.error : u.thrown ? u.error : this.raise(l.start, se.UnexpectedTokenAfterTypeParameter);
    }
    return super.parseMaybeAssign(e, t);
  }
  parseArrow(e) {
    if (this.match(14)) {
      const t = this.tryParse(() => {
        const s = this.state.noAnonFunctionType;
        this.state.noAnonFunctionType = !0;
        const i = this.startNode();
        return [i.typeAnnotation, e.predicate] = this.flowParseTypeAndPredicateInitialiser(), this.state.noAnonFunctionType = s, this.canInsertSemicolon() && this.unexpected(), this.match(19) || this.unexpected(), i;
      });
      if (t.thrown)
        return null;
      t.error && (this.state = t.failState), e.returnType = t.node.typeAnnotation ? this.finishNode(t.node, "TypeAnnotation") : null;
    }
    return super.parseArrow(e);
  }
  shouldParseArrow(e) {
    return this.match(14) || super.shouldParseArrow(e);
  }
  setArrowFunctionParameters(e, t) {
    this.state.noArrowParamsConversionAt.indexOf(e.start) !== -1 ? e.params = t : super.setArrowFunctionParameters(e, t);
  }
  checkParams(e, t, s) {
    if (!(s && this.state.noArrowParamsConversionAt.indexOf(e.start) !== -1)) {
      for (let i = 0; i < e.params.length; i++)
        this.isThisParam(e.params[i]) && i > 0 && this.raise(e.params[i].start, se.ThisParamMustBeFirst);
      return super.checkParams(...arguments);
    }
  }
  parseParenAndDistinguishExpression(e) {
    return super.parseParenAndDistinguishExpression(e && this.state.noArrowAt.indexOf(this.state.start) === -1);
  }
  parseSubscripts(e, t, s, i) {
    if (e.type === "Identifier" && e.name === "async" && this.state.noArrowAt.indexOf(t) !== -1) {
      this.next();
      const n = this.startNodeAt(t, s);
      n.callee = e, n.arguments = this.parseCallExpressionArguments(11, !1), e = this.finishNode(n, "CallExpression");
    } else if (e.type === "Identifier" && e.name === "async" && this.match(43)) {
      const n = this.state.clone(), a = this.tryParse((l) => this.parseAsyncArrowWithTypeParameters(t, s) || l(), n);
      if (!a.error && !a.aborted)
        return a.node;
      const o = this.tryParse(() => super.parseSubscripts(e, t, s, i), n);
      if (o.node && !o.error)
        return o.node;
      if (a.node)
        return this.state = a.failState, a.node;
      if (o.node)
        return this.state = o.failState, o.node;
      throw a.error || o.error;
    }
    return super.parseSubscripts(e, t, s, i);
  }
  parseSubscript(e, t, s, i, n) {
    if (this.match(18) && this.isLookaheadToken_lt()) {
      if (n.optionalChainMember = !0, i)
        return n.stop = !0, e;
      this.next();
      const a = this.startNodeAt(t, s);
      return a.callee = e, a.typeArguments = this.flowParseTypeParameterInstantiation(), this.expect(10), a.arguments = this.parseCallExpressionArguments(11, !1), a.optional = !0, this.finishCallExpression(a, !0);
    } else if (!i && this.shouldParseTypes() && this.match(43)) {
      const a = this.startNodeAt(t, s);
      a.callee = e;
      const o = this.tryParse(() => (a.typeArguments = this.flowParseTypeParameterInstantiationCallOrNew(), this.expect(10), a.arguments = this.parseCallExpressionArguments(11, !1), n.optionalChainMember && (a.optional = !1), this.finishCallExpression(a, n.optionalChainMember)));
      if (o.node)
        return o.error && (this.state = o.failState), o.node;
    }
    return super.parseSubscript(e, t, s, i, n);
  }
  parseNewArguments(e) {
    let t = null;
    this.shouldParseTypes() && this.match(43) && (t = this.tryParse(() => this.flowParseTypeParameterInstantiationCallOrNew()).node), e.typeArguments = t, super.parseNewArguments(e);
  }
  parseAsyncArrowWithTypeParameters(e, t) {
    const s = this.startNodeAt(e, t);
    if (this.parseFunctionParams(s), !!this.parseArrow(s))
      return this.parseArrowExpression(s, void 0, !0);
  }
  readToken_mult_modulo(e) {
    const t = this.input.charCodeAt(this.state.pos + 1);
    if (e === 42 && t === 47 && this.state.hasFlowComment) {
      this.state.hasFlowComment = !1, this.state.pos += 2, this.nextToken();
      return;
    }
    super.readToken_mult_modulo(e);
  }
  readToken_pipe_amp(e) {
    const t = this.input.charCodeAt(this.state.pos + 1);
    if (e === 124 && t === 125) {
      this.finishOp(9, 2);
      return;
    }
    super.readToken_pipe_amp(e);
  }
  parseTopLevel(e, t) {
    const s = super.parseTopLevel(e, t);
    return this.state.hasFlowComment && this.raise(this.state.pos, se.UnterminatedFlowComment), s;
  }
  skipBlockComment() {
    if (this.hasPlugin("flowComments") && this.skipFlowComment()) {
      this.state.hasFlowComment && this.unexpected(null, se.NestedFlowComment), this.hasFlowCommentCompletion(), this.state.pos += this.skipFlowComment(), this.state.hasFlowComment = !0;
      return;
    }
    if (this.state.hasFlowComment) {
      const e = this.input.indexOf("*-/", this.state.pos += 2);
      if (e === -1)
        throw this.raise(this.state.pos - 2, I.UnterminatedComment);
      this.state.pos = e + 3;
      return;
    }
    return super.skipBlockComment();
  }
  skipFlowComment() {
    const {
      pos: e
    } = this.state;
    let t = 2;
    for (; [32, 9].includes(this.input.charCodeAt(e + t)); )
      t++;
    const s = this.input.charCodeAt(t + e), i = this.input.charCodeAt(t + e + 1);
    return s === 58 && i === 58 ? t + 2 : this.input.slice(t + e, t + e + 12) === "flow-include" ? t + 12 : s === 58 && i !== 58 ? t : !1;
  }
  hasFlowCommentCompletion() {
    if (this.input.indexOf("*/", this.state.pos) === -1)
      throw this.raise(this.state.pos, I.UnterminatedComment);
  }
  flowEnumErrorBooleanMemberNotInitialized(e, {
    enumName: t,
    memberName: s
  }) {
    this.raise(e, se.EnumBooleanMemberNotInitialized, s, t);
  }
  flowEnumErrorInvalidMemberName(e, {
    enumName: t,
    memberName: s
  }) {
    const i = s[0].toUpperCase() + s.slice(1);
    this.raise(e, se.EnumInvalidMemberName, s, i, t);
  }
  flowEnumErrorDuplicateMemberName(e, {
    enumName: t,
    memberName: s
  }) {
    this.raise(e, se.EnumDuplicateMemberName, s, t);
  }
  flowEnumErrorInconsistentMemberValues(e, {
    enumName: t
  }) {
    this.raise(e, se.EnumInconsistentMemberValues, t);
  }
  flowEnumErrorInvalidExplicitType(e, {
    enumName: t,
    suppliedType: s
  }) {
    return this.raise(e, s === null ? se.EnumInvalidExplicitTypeUnknownSupplied : se.EnumInvalidExplicitType, t, s);
  }
  flowEnumErrorInvalidMemberInitializer(e, {
    enumName: t,
    explicitType: s,
    memberName: i
  }) {
    let n = null;
    switch (s) {
      case "boolean":
      case "number":
      case "string":
        n = se.EnumInvalidMemberInitializerPrimaryType;
        break;
      case "symbol":
        n = se.EnumInvalidMemberInitializerSymbolType;
        break;
      default:
        n = se.EnumInvalidMemberInitializerUnknownType;
    }
    return this.raise(e, n, t, i, s);
  }
  flowEnumErrorNumberMemberNotInitialized(e, {
    enumName: t,
    memberName: s
  }) {
    this.raise(e, se.EnumNumberMemberNotInitialized, t, s);
  }
  flowEnumErrorStringMemberInconsistentlyInitailized(e, {
    enumName: t
  }) {
    this.raise(e, se.EnumStringMemberInconsistentlyInitailized, t);
  }
  flowEnumMemberInit() {
    const e = this.state.start, t = () => this.match(12) || this.match(8);
    switch (this.state.type) {
      case 124: {
        const s = this.parseNumericLiteral(this.state.value);
        return t() ? {
          type: "number",
          pos: s.start,
          value: s
        } : {
          type: "invalid",
          pos: e
        };
      }
      case 123: {
        const s = this.parseStringLiteral(this.state.value);
        return t() ? {
          type: "string",
          pos: s.start,
          value: s
        } : {
          type: "invalid",
          pos: e
        };
      }
      case 79:
      case 80: {
        const s = this.parseBooleanLiteral(this.match(79));
        return t() ? {
          type: "boolean",
          pos: s.start,
          value: s
        } : {
          type: "invalid",
          pos: e
        };
      }
      default:
        return {
          type: "invalid",
          pos: e
        };
    }
  }
  flowEnumMemberRaw() {
    const e = this.state.start, t = this.parseIdentifier(!0), s = this.eat(27) ? this.flowEnumMemberInit() : {
      type: "none",
      pos: e
    };
    return {
      id: t,
      init: s
    };
  }
  flowEnumCheckExplicitTypeMismatch(e, t, s) {
    const {
      explicitType: i
    } = t;
    i !== null && i !== s && this.flowEnumErrorInvalidMemberInitializer(e, t);
  }
  flowEnumMembers({
    enumName: e,
    explicitType: t
  }) {
    const s = /* @__PURE__ */ new Set(), i = {
      booleanMembers: [],
      numberMembers: [],
      stringMembers: [],
      defaultedMembers: []
    };
    let n = !1;
    for (; !this.match(8); ) {
      if (this.eat(21)) {
        n = !0;
        break;
      }
      const a = this.startNode(), {
        id: o,
        init: l
      } = this.flowEnumMemberRaw(), u = o.name;
      if (u === "")
        continue;
      /^[a-z]/.test(u) && this.flowEnumErrorInvalidMemberName(o.start, {
        enumName: e,
        memberName: u
      }), s.has(u) && this.flowEnumErrorDuplicateMemberName(o.start, {
        enumName: e,
        memberName: u
      }), s.add(u);
      const c = {
        enumName: e,
        explicitType: t,
        memberName: u
      };
      switch (a.id = o, l.type) {
        case "boolean": {
          this.flowEnumCheckExplicitTypeMismatch(l.pos, c, "boolean"), a.init = l.value, i.booleanMembers.push(this.finishNode(a, "EnumBooleanMember"));
          break;
        }
        case "number": {
          this.flowEnumCheckExplicitTypeMismatch(l.pos, c, "number"), a.init = l.value, i.numberMembers.push(this.finishNode(a, "EnumNumberMember"));
          break;
        }
        case "string": {
          this.flowEnumCheckExplicitTypeMismatch(l.pos, c, "string"), a.init = l.value, i.stringMembers.push(this.finishNode(a, "EnumStringMember"));
          break;
        }
        case "invalid":
          throw this.flowEnumErrorInvalidMemberInitializer(l.pos, c);
        case "none":
          switch (t) {
            case "boolean":
              this.flowEnumErrorBooleanMemberNotInitialized(l.pos, c);
              break;
            case "number":
              this.flowEnumErrorNumberMemberNotInitialized(l.pos, c);
              break;
            default:
              i.defaultedMembers.push(this.finishNode(a, "EnumDefaultedMember"));
          }
      }
      this.match(8) || this.expect(12);
    }
    return {
      members: i,
      hasUnknownMembers: n
    };
  }
  flowEnumStringMembers(e, t, {
    enumName: s
  }) {
    if (e.length === 0)
      return t;
    if (t.length === 0)
      return e;
    if (t.length > e.length) {
      for (const i of e)
        this.flowEnumErrorStringMemberInconsistentlyInitailized(i.start, {
          enumName: s
        });
      return t;
    } else {
      for (const i of t)
        this.flowEnumErrorStringMemberInconsistentlyInitailized(i.start, {
          enumName: s
        });
      return e;
    }
  }
  flowEnumParseExplicitType({
    enumName: e
  }) {
    if (this.eatContextual(95)) {
      if (!Se(this.state.type))
        throw this.flowEnumErrorInvalidExplicitType(this.state.start, {
          enumName: e,
          suppliedType: null
        });
      const {
        value: t
      } = this.state;
      return this.next(), t !== "boolean" && t !== "number" && t !== "string" && t !== "symbol" && this.flowEnumErrorInvalidExplicitType(this.state.start, {
        enumName: e,
        suppliedType: t
      }), t;
    }
    return null;
  }
  flowEnumBody(e, {
    enumName: t,
    nameLoc: s
  }) {
    const i = this.flowEnumParseExplicitType({
      enumName: t
    });
    this.expect(5);
    const {
      members: n,
      hasUnknownMembers: a
    } = this.flowEnumMembers({
      enumName: t,
      explicitType: i
    });
    switch (e.hasUnknownMembers = a, i) {
      case "boolean":
        return e.explicitType = !0, e.members = n.booleanMembers, this.expect(8), this.finishNode(e, "EnumBooleanBody");
      case "number":
        return e.explicitType = !0, e.members = n.numberMembers, this.expect(8), this.finishNode(e, "EnumNumberBody");
      case "string":
        return e.explicitType = !0, e.members = this.flowEnumStringMembers(n.stringMembers, n.defaultedMembers, {
          enumName: t
        }), this.expect(8), this.finishNode(e, "EnumStringBody");
      case "symbol":
        return e.members = n.defaultedMembers, this.expect(8), this.finishNode(e, "EnumSymbolBody");
      default: {
        const o = () => (e.members = [], this.expect(8), this.finishNode(e, "EnumStringBody"));
        e.explicitType = !1;
        const l = n.booleanMembers.length, u = n.numberMembers.length, c = n.stringMembers.length, h = n.defaultedMembers.length;
        if (!l && !u && !c && !h)
          return o();
        if (!l && !u)
          return e.members = this.flowEnumStringMembers(n.stringMembers, n.defaultedMembers, {
            enumName: t
          }), this.expect(8), this.finishNode(e, "EnumStringBody");
        if (!u && !c && l >= h) {
          for (const f of n.defaultedMembers)
            this.flowEnumErrorBooleanMemberNotInitialized(f.start, {
              enumName: t,
              memberName: f.id.name
            });
          return e.members = n.booleanMembers, this.expect(8), this.finishNode(e, "EnumBooleanBody");
        } else if (!l && !c && u >= h) {
          for (const f of n.defaultedMembers)
            this.flowEnumErrorNumberMemberNotInitialized(f.start, {
              enumName: t,
              memberName: f.id.name
            });
          return e.members = n.numberMembers, this.expect(8), this.finishNode(e, "EnumNumberBody");
        } else
          return this.flowEnumErrorInconsistentMemberValues(s, {
            enumName: t
          }), o();
      }
    }
  }
  flowParseEnumDeclaration(e) {
    const t = this.parseIdentifier();
    return e.id = t, e.body = this.flowEnumBody(this.startNode(), {
      enumName: t.name,
      nameLoc: t.start
    }), this.finishNode(e, "EnumDeclaration");
  }
  isLookaheadToken_lt() {
    const e = this.nextTokenStart();
    if (this.input.charCodeAt(e) === 60) {
      const t = this.input.charCodeAt(e + 1);
      return t !== 60 && t !== 61;
    }
    return !1;
  }
  maybeUnwrapTypeCastExpression(e) {
    return e.type === "TypeCastExpression" ? e.expression : e;
  }
};
const $1 = {
  quot: '"',
  amp: "&",
  apos: "'",
  lt: "<",
  gt: ">",
  nbsp: "\xA0",
  iexcl: "\xA1",
  cent: "\xA2",
  pound: "\xA3",
  curren: "\xA4",
  yen: "\xA5",
  brvbar: "\xA6",
  sect: "\xA7",
  uml: "\xA8",
  copy: "\xA9",
  ordf: "\xAA",
  laquo: "\xAB",
  not: "\xAC",
  shy: "\xAD",
  reg: "\xAE",
  macr: "\xAF",
  deg: "\xB0",
  plusmn: "\xB1",
  sup2: "\xB2",
  sup3: "\xB3",
  acute: "\xB4",
  micro: "\xB5",
  para: "\xB6",
  middot: "\xB7",
  cedil: "\xB8",
  sup1: "\xB9",
  ordm: "\xBA",
  raquo: "\xBB",
  frac14: "\xBC",
  frac12: "\xBD",
  frac34: "\xBE",
  iquest: "\xBF",
  Agrave: "\xC0",
  Aacute: "\xC1",
  Acirc: "\xC2",
  Atilde: "\xC3",
  Auml: "\xC4",
  Aring: "\xC5",
  AElig: "\xC6",
  Ccedil: "\xC7",
  Egrave: "\xC8",
  Eacute: "\xC9",
  Ecirc: "\xCA",
  Euml: "\xCB",
  Igrave: "\xCC",
  Iacute: "\xCD",
  Icirc: "\xCE",
  Iuml: "\xCF",
  ETH: "\xD0",
  Ntilde: "\xD1",
  Ograve: "\xD2",
  Oacute: "\xD3",
  Ocirc: "\xD4",
  Otilde: "\xD5",
  Ouml: "\xD6",
  times: "\xD7",
  Oslash: "\xD8",
  Ugrave: "\xD9",
  Uacute: "\xDA",
  Ucirc: "\xDB",
  Uuml: "\xDC",
  Yacute: "\xDD",
  THORN: "\xDE",
  szlig: "\xDF",
  agrave: "\xE0",
  aacute: "\xE1",
  acirc: "\xE2",
  atilde: "\xE3",
  auml: "\xE4",
  aring: "\xE5",
  aelig: "\xE6",
  ccedil: "\xE7",
  egrave: "\xE8",
  eacute: "\xE9",
  ecirc: "\xEA",
  euml: "\xEB",
  igrave: "\xEC",
  iacute: "\xED",
  icirc: "\xEE",
  iuml: "\xEF",
  eth: "\xF0",
  ntilde: "\xF1",
  ograve: "\xF2",
  oacute: "\xF3",
  ocirc: "\xF4",
  otilde: "\xF5",
  ouml: "\xF6",
  divide: "\xF7",
  oslash: "\xF8",
  ugrave: "\xF9",
  uacute: "\xFA",
  ucirc: "\xFB",
  uuml: "\xFC",
  yacute: "\xFD",
  thorn: "\xFE",
  yuml: "\xFF",
  OElig: "\u0152",
  oelig: "\u0153",
  Scaron: "\u0160",
  scaron: "\u0161",
  Yuml: "\u0178",
  fnof: "\u0192",
  circ: "\u02C6",
  tilde: "\u02DC",
  Alpha: "\u0391",
  Beta: "\u0392",
  Gamma: "\u0393",
  Delta: "\u0394",
  Epsilon: "\u0395",
  Zeta: "\u0396",
  Eta: "\u0397",
  Theta: "\u0398",
  Iota: "\u0399",
  Kappa: "\u039A",
  Lambda: "\u039B",
  Mu: "\u039C",
  Nu: "\u039D",
  Xi: "\u039E",
  Omicron: "\u039F",
  Pi: "\u03A0",
  Rho: "\u03A1",
  Sigma: "\u03A3",
  Tau: "\u03A4",
  Upsilon: "\u03A5",
  Phi: "\u03A6",
  Chi: "\u03A7",
  Psi: "\u03A8",
  Omega: "\u03A9",
  alpha: "\u03B1",
  beta: "\u03B2",
  gamma: "\u03B3",
  delta: "\u03B4",
  epsilon: "\u03B5",
  zeta: "\u03B6",
  eta: "\u03B7",
  theta: "\u03B8",
  iota: "\u03B9",
  kappa: "\u03BA",
  lambda: "\u03BB",
  mu: "\u03BC",
  nu: "\u03BD",
  xi: "\u03BE",
  omicron: "\u03BF",
  pi: "\u03C0",
  rho: "\u03C1",
  sigmaf: "\u03C2",
  sigma: "\u03C3",
  tau: "\u03C4",
  upsilon: "\u03C5",
  phi: "\u03C6",
  chi: "\u03C7",
  psi: "\u03C8",
  omega: "\u03C9",
  thetasym: "\u03D1",
  upsih: "\u03D2",
  piv: "\u03D6",
  ensp: "\u2002",
  emsp: "\u2003",
  thinsp: "\u2009",
  zwnj: "\u200C",
  zwj: "\u200D",
  lrm: "\u200E",
  rlm: "\u200F",
  ndash: "\u2013",
  mdash: "\u2014",
  lsquo: "\u2018",
  rsquo: "\u2019",
  sbquo: "\u201A",
  ldquo: "\u201C",
  rdquo: "\u201D",
  bdquo: "\u201E",
  dagger: "\u2020",
  Dagger: "\u2021",
  bull: "\u2022",
  hellip: "\u2026",
  permil: "\u2030",
  prime: "\u2032",
  Prime: "\u2033",
  lsaquo: "\u2039",
  rsaquo: "\u203A",
  oline: "\u203E",
  frasl: "\u2044",
  euro: "\u20AC",
  image: "\u2111",
  weierp: "\u2118",
  real: "\u211C",
  trade: "\u2122",
  alefsym: "\u2135",
  larr: "\u2190",
  uarr: "\u2191",
  rarr: "\u2192",
  darr: "\u2193",
  harr: "\u2194",
  crarr: "\u21B5",
  lArr: "\u21D0",
  uArr: "\u21D1",
  rArr: "\u21D2",
  dArr: "\u21D3",
  hArr: "\u21D4",
  forall: "\u2200",
  part: "\u2202",
  exist: "\u2203",
  empty: "\u2205",
  nabla: "\u2207",
  isin: "\u2208",
  notin: "\u2209",
  ni: "\u220B",
  prod: "\u220F",
  sum: "\u2211",
  minus: "\u2212",
  lowast: "\u2217",
  radic: "\u221A",
  prop: "\u221D",
  infin: "\u221E",
  ang: "\u2220",
  and: "\u2227",
  or: "\u2228",
  cap: "\u2229",
  cup: "\u222A",
  int: "\u222B",
  there4: "\u2234",
  sim: "\u223C",
  cong: "\u2245",
  asymp: "\u2248",
  ne: "\u2260",
  equiv: "\u2261",
  le: "\u2264",
  ge: "\u2265",
  sub: "\u2282",
  sup: "\u2283",
  nsub: "\u2284",
  sube: "\u2286",
  supe: "\u2287",
  oplus: "\u2295",
  otimes: "\u2297",
  perp: "\u22A5",
  sdot: "\u22C5",
  lceil: "\u2308",
  rceil: "\u2309",
  lfloor: "\u230A",
  rfloor: "\u230B",
  lang: "\u2329",
  rang: "\u232A",
  loz: "\u25CA",
  spades: "\u2660",
  clubs: "\u2663",
  hearts: "\u2665",
  diams: "\u2666"
}, j1 = /^[\da-fA-F]+$/, q1 = /^\d+$/, Lr = Os({
  AttributeIsEmpty: "JSX attributes must only be assigned a non-empty expression.",
  MissingClosingTagElement: "Expected corresponding JSX closing tag for <%0>.",
  MissingClosingTagFragment: "Expected corresponding JSX closing tag for <>.",
  UnexpectedSequenceExpression: "Sequence expressions cannot be directly nested inside JSX. Did you mean to wrap it in parentheses (...)?",
  UnsupportedJsxValue: "JSX value should be either an expression or a quoted JSX text.",
  UnterminatedJsxContent: "Unterminated JSX contents.",
  UnwrappedAdjacentJSXElements: "Adjacent JSX elements must be wrapped in an enclosing tag. Did you want a JSX fragment <>...</>?"
}, Zt.SyntaxError, "jsx");
ke.j_oTag = new xi("<tag");
ke.j_cTag = new xi("</tag");
ke.j_expr = new xi("<tag>...</tag>", !0);
function dr(r) {
  return r ? r.type === "JSXOpeningFragment" || r.type === "JSXClosingFragment" : !1;
}
function as(r) {
  if (r.type === "JSXIdentifier")
    return r.name;
  if (r.type === "JSXNamespacedName")
    return r.namespace.name + ":" + r.name.name;
  if (r.type === "JSXMemberExpression")
    return as(r.object) + "." + as(r.property);
  throw new Error("Node had unexpected type: " + r.type);
}
var V1 = (r) => class extends r {
  jsxReadToken() {
    let e = "", t = this.state.pos;
    for (; ; ) {
      if (this.state.pos >= this.length)
        throw this.raise(this.state.start, Lr.UnterminatedJsxContent);
      const s = this.input.charCodeAt(this.state.pos);
      switch (s) {
        case 60:
        case 123:
          return this.state.pos === this.state.start ? s === 60 && this.state.canStartJSXElement ? (++this.state.pos, this.finishToken(132)) : super.getTokenFromCode(s) : (e += this.input.slice(t, this.state.pos), this.finishToken(131, e));
        case 38:
          e += this.input.slice(t, this.state.pos), e += this.jsxReadEntity(), t = this.state.pos;
          break;
        case 62:
        case 125:
        default:
          Br(s) ? (e += this.input.slice(t, this.state.pos), e += this.jsxReadNewLine(!0), t = this.state.pos) : ++this.state.pos;
      }
    }
  }
  jsxReadNewLine(e) {
    const t = this.input.charCodeAt(this.state.pos);
    let s;
    return ++this.state.pos, t === 13 && this.input.charCodeAt(this.state.pos) === 10 ? (++this.state.pos, s = e ? `
` : `\r
`) : s = String.fromCharCode(t), ++this.state.curLine, this.state.lineStart = this.state.pos, s;
  }
  jsxReadString(e) {
    let t = "", s = ++this.state.pos;
    for (; ; ) {
      if (this.state.pos >= this.length)
        throw this.raise(this.state.start, I.UnterminatedString);
      const i = this.input.charCodeAt(this.state.pos);
      if (i === e)
        break;
      i === 38 ? (t += this.input.slice(s, this.state.pos), t += this.jsxReadEntity(), s = this.state.pos) : Br(i) ? (t += this.input.slice(s, this.state.pos), t += this.jsxReadNewLine(!1), s = this.state.pos) : ++this.state.pos;
    }
    return t += this.input.slice(s, this.state.pos++), this.finishToken(123, t);
  }
  jsxReadEntity() {
    let e = "", t = 0, s, i = this.input[this.state.pos];
    const n = ++this.state.pos;
    for (; this.state.pos < this.length && t++ < 10; ) {
      if (i = this.input[this.state.pos++], i === ";") {
        e[0] === "#" ? e[1] === "x" ? (e = e.substr(2), j1.test(e) && (s = String.fromCodePoint(parseInt(e, 16)))) : (e = e.substr(1), q1.test(e) && (s = String.fromCodePoint(parseInt(e, 10)))) : s = $1[e];
        break;
      }
      e += i;
    }
    return s || (this.state.pos = n, "&");
  }
  jsxReadWord() {
    let e;
    const t = this.state.pos;
    do
      e = this.input.charCodeAt(++this.state.pos);
    while (ps(e) || e === 45);
    return this.finishToken(130, this.input.slice(t, this.state.pos));
  }
  jsxParseIdentifier() {
    const e = this.startNode();
    return this.match(130) ? e.name = this.state.value : Vl(this.state.type) ? e.name = Tr(this.state.type) : this.unexpected(), this.next(), this.finishNode(e, "JSXIdentifier");
  }
  jsxParseNamespacedName() {
    const e = this.state.start, t = this.state.startLoc, s = this.jsxParseIdentifier();
    if (!this.eat(14))
      return s;
    const i = this.startNodeAt(e, t);
    return i.namespace = s, i.name = this.jsxParseIdentifier(), this.finishNode(i, "JSXNamespacedName");
  }
  jsxParseElementName() {
    const e = this.state.start, t = this.state.startLoc;
    let s = this.jsxParseNamespacedName();
    if (s.type === "JSXNamespacedName")
      return s;
    for (; this.eat(16); ) {
      const i = this.startNodeAt(e, t);
      i.object = s, i.property = this.jsxParseIdentifier(), s = this.finishNode(i, "JSXMemberExpression");
    }
    return s;
  }
  jsxParseAttributeValue() {
    let e;
    switch (this.state.type) {
      case 5:
        return e = this.startNode(), this.next(), e = this.jsxParseExpressionContainer(e), e.expression.type === "JSXEmptyExpression" && this.raise(e.start, Lr.AttributeIsEmpty), e;
      case 132:
      case 123:
        return this.parseExprAtom();
      default:
        throw this.raise(this.state.start, Lr.UnsupportedJsxValue);
    }
  }
  jsxParseEmptyExpression() {
    const e = this.startNodeAt(this.state.lastTokEnd, this.state.lastTokEndLoc);
    return this.finishNodeAt(e, "JSXEmptyExpression", this.state.start, this.state.startLoc);
  }
  jsxParseSpreadChild(e) {
    return this.next(), e.expression = this.parseExpression(), this.expect(8), this.finishNode(e, "JSXSpreadChild");
  }
  jsxParseExpressionContainer(e) {
    if (this.match(8))
      e.expression = this.jsxParseEmptyExpression();
    else {
      const t = this.parseExpression();
      e.expression = t;
    }
    return this.expect(8), this.finishNode(e, "JSXExpressionContainer");
  }
  jsxParseAttribute() {
    const e = this.startNode();
    return this.eat(5) ? (this.expect(21), e.argument = this.parseMaybeAssignAllowIn(), this.expect(8), this.finishNode(e, "JSXSpreadAttribute")) : (e.name = this.jsxParseNamespacedName(), e.value = this.eat(27) ? this.jsxParseAttributeValue() : null, this.finishNode(e, "JSXAttribute"));
  }
  jsxParseOpeningElementAt(e, t) {
    const s = this.startNodeAt(e, t);
    return this.match(133) ? (this.expect(133), this.finishNode(s, "JSXOpeningFragment")) : (s.name = this.jsxParseElementName(), this.jsxParseOpeningElementAfterName(s));
  }
  jsxParseOpeningElementAfterName(e) {
    const t = [];
    for (; !this.match(50) && !this.match(133); )
      t.push(this.jsxParseAttribute());
    return e.attributes = t, e.selfClosing = this.eat(50), this.expect(133), this.finishNode(e, "JSXOpeningElement");
  }
  jsxParseClosingElementAt(e, t) {
    const s = this.startNodeAt(e, t);
    return this.match(133) ? (this.expect(133), this.finishNode(s, "JSXClosingFragment")) : (s.name = this.jsxParseElementName(), this.expect(133), this.finishNode(s, "JSXClosingElement"));
  }
  jsxParseElementAt(e, t) {
    const s = this.startNodeAt(e, t), i = [], n = this.jsxParseOpeningElementAt(e, t);
    let a = null;
    if (!n.selfClosing) {
      e:
        for (; ; )
          switch (this.state.type) {
            case 132:
              if (e = this.state.start, t = this.state.startLoc, this.next(), this.eat(50)) {
                a = this.jsxParseClosingElementAt(e, t);
                break e;
              }
              i.push(this.jsxParseElementAt(e, t));
              break;
            case 131:
              i.push(this.parseExprAtom());
              break;
            case 5: {
              const o = this.startNode();
              this.next(), this.match(21) ? i.push(this.jsxParseSpreadChild(o)) : i.push(this.jsxParseExpressionContainer(o));
              break;
            }
            default:
              throw this.unexpected();
          }
      dr(n) && !dr(a) ? this.raise(a.start, Lr.MissingClosingTagFragment) : !dr(n) && dr(a) ? this.raise(a.start, Lr.MissingClosingTagElement, as(n.name)) : !dr(n) && !dr(a) && as(a.name) !== as(n.name) && this.raise(a.start, Lr.MissingClosingTagElement, as(n.name));
    }
    if (dr(n) ? (s.openingFragment = n, s.closingFragment = a) : (s.openingElement = n, s.closingElement = a), s.children = i, this.match(43))
      throw this.raise(this.state.start, Lr.UnwrappedAdjacentJSXElements);
    return dr(n) ? this.finishNode(s, "JSXFragment") : this.finishNode(s, "JSXElement");
  }
  jsxParseElement() {
    const e = this.state.start, t = this.state.startLoc;
    return this.next(), this.jsxParseElementAt(e, t);
  }
  parseExprAtom(e) {
    return this.match(131) ? this.parseLiteral(this.state.value, "JSXText") : this.match(132) ? this.jsxParseElement() : this.match(43) && this.input.charCodeAt(this.state.pos) !== 33 ? (this.replaceToken(132), this.jsxParseElement()) : super.parseExprAtom(e);
  }
  getTokenFromCode(e) {
    const t = this.curContext();
    if (t === ke.j_expr)
      return this.jsxReadToken();
    if (t === ke.j_oTag || t === ke.j_cTag) {
      if (br(e))
        return this.jsxReadWord();
      if (e === 62)
        return ++this.state.pos, this.finishToken(133);
      if ((e === 34 || e === 39) && t === ke.j_oTag)
        return this.jsxReadString(e);
    }
    return e === 60 && this.state.canStartJSXElement && this.input.charCodeAt(this.state.pos + 1) !== 33 ? (++this.state.pos, this.finishToken(132)) : super.getTokenFromCode(e);
  }
  updateContext(e) {
    super.updateContext(e);
    const {
      context: t,
      type: s
    } = this.state;
    if (s === 50 && e === 132)
      t.splice(-2, 2, ke.j_cTag), this.state.canStartJSXElement = !1;
    else if (s === 132)
      t.push(ke.j_expr, ke.j_oTag);
    else if (s === 133) {
      const i = t.pop();
      i === ke.j_oTag && e === 50 || i === ke.j_cTag ? (t.pop(), this.state.canStartJSXElement = t[t.length - 1] === ke.j_expr) : this.state.canStartJSXElement = !0;
    } else
      this.state.canStartJSXElement = j0(s);
  }
};
class z1 extends Jl {
  constructor(...e) {
    super(...e), this.types = /* @__PURE__ */ new Set(), this.enums = /* @__PURE__ */ new Set(), this.constEnums = /* @__PURE__ */ new Set(), this.classes = /* @__PURE__ */ new Set(), this.exportOnlyBindings = /* @__PURE__ */ new Set();
  }
}
class W1 extends Ql {
  createScope(e) {
    return new z1(e);
  }
  declareName(e, t, s) {
    const i = this.currentScope();
    if (t & Kl) {
      this.maybeExportDefined(i, e), i.exportOnlyBindings.add(e);
      return;
    }
    super.declareName(...arguments), t & Ps && (t & sr || (this.checkRedeclarationInScope(i, e, t, s), this.maybeExportDefined(i, e)), i.types.add(e)), t & Co && i.enums.add(e), t & Io && i.constEnums.add(e), t & Mn && i.classes.add(e);
  }
  isRedeclaredInScope(e, t, s) {
    if (e.enums.has(t)) {
      if (s & Co) {
        const i = !!(s & Io), n = e.constEnums.has(t);
        return i !== n;
      }
      return !0;
    }
    return s & Mn && e.classes.has(t) ? e.lexical.has(t) ? !!(s & sr) : !1 : s & Ps && e.types.has(t) ? !0 : super.isRedeclaredInScope(...arguments);
  }
  checkLocalExport(e) {
    const t = this.scopeStack[0], {
      name: s
    } = e;
    !t.types.has(s) && !t.exportOnlyBindings.has(s) && super.checkLocalExport(e);
  }
}
function H1(r) {
  if (r == null)
    throw new Error(`Unexpected ${r} value.`);
  return r;
}
function Ic(r) {
  if (!r)
    throw new Error("Assert fail");
}
const ne = Os({
  AbstractMethodHasImplementation: "Method '%0' cannot have an implementation because it is marked abstract.",
  AbstractPropertyHasInitializer: "Property '%0' cannot have an initializer because it is marked abstract.",
  AccesorCannotDeclareThisParameter: "'get' and 'set' accessors cannot declare 'this' parameters.",
  AccesorCannotHaveTypeParameters: "An accessor cannot have type parameters.",
  ClassMethodHasDeclare: "Class methods cannot have the 'declare' modifier.",
  ClassMethodHasReadonly: "Class methods cannot have the 'readonly' modifier.",
  ConstructorHasTypeParameters: "Type parameters cannot appear on a constructor declaration.",
  DeclareAccessor: "'declare' is not allowed in %0ters.",
  DeclareClassFieldHasInitializer: "Initializers are not allowed in ambient contexts.",
  DeclareFunctionHasImplementation: "An implementation cannot be declared in ambient contexts.",
  DuplicateAccessibilityModifier: "Accessibility modifier already seen.",
  DuplicateModifier: "Duplicate modifier: '%0'.",
  EmptyHeritageClauseType: "'%0' list cannot be empty.",
  EmptyTypeArguments: "Type argument list cannot be empty.",
  EmptyTypeParameters: "Type parameter list cannot be empty.",
  ExpectedAmbientAfterExportDeclare: "'export declare' must be followed by an ambient declaration.",
  ImportAliasHasImportType: "An import alias can not use 'import type'.",
  IncompatibleModifiers: "'%0' modifier cannot be used with '%1' modifier.",
  IndexSignatureHasAbstract: "Index signatures cannot have the 'abstract' modifier.",
  IndexSignatureHasAccessibility: "Index signatures cannot have an accessibility modifier ('%0').",
  IndexSignatureHasDeclare: "Index signatures cannot have the 'declare' modifier.",
  IndexSignatureHasOverride: "'override' modifier cannot appear on an index signature.",
  IndexSignatureHasStatic: "Index signatures cannot have the 'static' modifier.",
  InvalidModifierOnTypeMember: "'%0' modifier cannot appear on a type member.",
  InvalidModifiersOrder: "'%0' modifier must precede '%1' modifier.",
  InvalidTupleMemberLabel: "Tuple members must be labeled with a simple identifier.",
  MissingInterfaceName: "'interface' declarations must be followed by an identifier.",
  MixedLabeledAndUnlabeledElements: "Tuple members must all have names or all not have names.",
  NonAbstractClassHasAbstractMethod: "Abstract methods can only appear within an abstract class.",
  NonClassMethodPropertyHasAbstractModifer: "'abstract' modifier can only appear on a class, method, or property declaration.",
  OptionalTypeBeforeRequired: "A required element cannot follow an optional element.",
  OverrideNotInSubClass: "This member cannot have an 'override' modifier because its containing class does not extend another class.",
  PatternIsOptional: "A binding pattern parameter cannot be optional in an implementation signature.",
  PrivateElementHasAbstract: "Private elements cannot have the 'abstract' modifier.",
  PrivateElementHasAccessibility: "Private elements cannot have an accessibility modifier ('%0').",
  ReadonlyForMethodSignature: "'readonly' modifier can only appear on a property declaration or index signature.",
  ReservedArrowTypeParam: "This syntax is reserved in files with the .mts or .cts extension. Add a trailing comma, as in `<T,>() => ...`.",
  ReservedTypeAssertion: "This syntax is reserved in files with the .mts or .cts extension. Use an `as` expression instead.",
  SetAccesorCannotHaveOptionalParameter: "A 'set' accessor cannot have an optional parameter.",
  SetAccesorCannotHaveRestParameter: "A 'set' accessor cannot have rest parameter.",
  SetAccesorCannotHaveReturnType: "A 'set' accessor cannot have a return type annotation.",
  StaticBlockCannotHaveModifier: "Static class blocks cannot have any modifier.",
  TypeAnnotationAfterAssign: "Type annotations must come before default assignments, e.g. instead of `age = 25: number` use `age: number = 25`.",
  TypeImportCannotSpecifyDefaultAndNamed: "A type-only import can specify a default import or named bindings, but not both.",
  TypeModifierIsUsedInTypeExports: "The 'type' modifier cannot be used on a named export when 'export type' is used on its export statement.",
  TypeModifierIsUsedInTypeImports: "The 'type' modifier cannot be used on a named import when 'import type' is used on its import statement.",
  UnexpectedParameterModifier: "A parameter property is only allowed in a constructor implementation.",
  UnexpectedReadonly: "'readonly' type modifier is only permitted on array and tuple literal types.",
  UnexpectedTypeAnnotation: "Did not expect a type annotation here.",
  UnexpectedTypeCastInParameter: "Unexpected type cast in parameter position.",
  UnsupportedImportTypeArgument: "Argument in a type import must be a string literal.",
  UnsupportedParameterPropertyKind: "A parameter property may not be declared using a binding pattern.",
  UnsupportedSignatureParameterKind: "Name in a signature must be an Identifier, ObjectPattern or ArrayPattern, instead got %0."
}, Zt.SyntaxError, "typescript");
function K1(r) {
  switch (r) {
    case "any":
      return "TSAnyKeyword";
    case "boolean":
      return "TSBooleanKeyword";
    case "bigint":
      return "TSBigIntKeyword";
    case "never":
      return "TSNeverKeyword";
    case "number":
      return "TSNumberKeyword";
    case "object":
      return "TSObjectKeyword";
    case "string":
      return "TSStringKeyword";
    case "symbol":
      return "TSSymbolKeyword";
    case "undefined":
      return "TSUndefinedKeyword";
    case "unknown":
      return "TSUnknownKeyword";
    default:
      return;
  }
}
function Nc(r) {
  return r === "private" || r === "public" || r === "protected";
}
var G1 = (r) => class extends r {
  getScopeHandler() {
    return W1;
  }
  tsIsIdentifier() {
    return Se(this.state.type);
  }
  tsTokenCanFollowModifier() {
    return (this.match(0) || this.match(5) || this.match(49) || this.match(21) || this.match(128) || this.isLiteralPropertyName()) && !this.hasPrecedingLineBreak();
  }
  tsNextTokenCanFollowModifier() {
    return this.next(), this.tsTokenCanFollowModifier();
  }
  tsParseModifier(e, t) {
    if (!Se(this.state.type))
      return;
    const s = this.state.value;
    if (e.indexOf(s) !== -1) {
      if (t && this.tsIsStartOfStaticBlocks())
        return;
      if (this.tsTryParse(this.tsNextTokenCanFollowModifier.bind(this)))
        return s;
    }
  }
  tsParseModifiers(e, t, s, i, n) {
    const a = (l, u, c, h) => {
      u === c && e[h] && this.raise(l, ne.InvalidModifiersOrder, c, h);
    }, o = (l, u, c, h) => {
      (e[c] && u === h || e[h] && u === c) && this.raise(l, ne.IncompatibleModifiers, c, h);
    };
    for (; ; ) {
      const l = this.state.start, u = this.tsParseModifier(t.concat(s != null ? s : []), n);
      if (!u)
        break;
      Nc(u) ? e.accessibility ? this.raise(l, ne.DuplicateAccessibilityModifier) : (a(l, u, u, "override"), a(l, u, u, "static"), a(l, u, u, "readonly"), e.accessibility = u) : (Object.hasOwnProperty.call(e, u) ? this.raise(l, ne.DuplicateModifier, u) : (a(l, u, "static", "readonly"), a(l, u, "static", "override"), a(l, u, "override", "readonly"), a(l, u, "abstract", "override"), o(l, u, "declare", "override"), o(l, u, "static", "abstract")), e[u] = !0), s != null && s.includes(u) && this.raise(l, i, u);
    }
  }
  tsIsListTerminator(e) {
    switch (e) {
      case "EnumMembers":
      case "TypeMembers":
        return this.match(8);
      case "HeritageClauseElement":
        return this.match(5);
      case "TupleElementTypes":
        return this.match(3);
      case "TypeParametersOrArguments":
        return this.match(44);
    }
    throw new Error("Unreachable");
  }
  tsParseList(e, t) {
    const s = [];
    for (; !this.tsIsListTerminator(e); )
      s.push(t());
    return s;
  }
  tsParseDelimitedList(e, t, s) {
    return H1(this.tsParseDelimitedListWorker(e, t, !0, s));
  }
  tsParseDelimitedListWorker(e, t, s, i) {
    const n = [];
    let a = -1;
    for (; !this.tsIsListTerminator(e); ) {
      a = -1;
      const o = t();
      if (o == null)
        return;
      if (n.push(o), this.eat(12)) {
        a = this.state.lastTokStart;
        continue;
      }
      if (this.tsIsListTerminator(e))
        break;
      s && this.expect(12);
      return;
    }
    return i && (i.value = a), n;
  }
  tsParseBracketedList(e, t, s, i, n) {
    i || (s ? this.expect(0) : this.expect(43));
    const a = this.tsParseDelimitedList(e, t, n);
    return s ? this.expect(3) : this.expect(44), a;
  }
  tsParseImportType() {
    const e = this.startNode();
    return this.expect(77), this.expect(10), this.match(123) || this.raise(this.state.start, ne.UnsupportedImportTypeArgument), e.argument = this.parseExprAtom(), this.expect(11), this.eat(16) && (e.qualifier = this.tsParseEntityName(!0)), this.match(43) && (e.typeParameters = this.tsParseTypeArguments()), this.finishNode(e, "TSImportType");
  }
  tsParseEntityName(e) {
    let t = this.parseIdentifier();
    for (; this.eat(16); ) {
      const s = this.startNodeAtNode(t);
      s.left = t, s.right = this.parseIdentifier(e), t = this.finishNode(s, "TSQualifiedName");
    }
    return t;
  }
  tsParseTypeReference() {
    const e = this.startNode();
    return e.typeName = this.tsParseEntityName(!1), !this.hasPrecedingLineBreak() && this.match(43) && (e.typeParameters = this.tsParseTypeArguments()), this.finishNode(e, "TSTypeReference");
  }
  tsParseThisTypePredicate(e) {
    this.next();
    const t = this.startNodeAtNode(e);
    return t.parameterName = e, t.typeAnnotation = this.tsParseTypeAnnotation(!1), t.asserts = !1, this.finishNode(t, "TSTypePredicate");
  }
  tsParseThisTypeNode() {
    const e = this.startNode();
    return this.next(), this.finishNode(e, "TSThisType");
  }
  tsParseTypeQuery() {
    const e = this.startNode();
    return this.expect(81), this.match(77) ? e.exprName = this.tsParseImportType() : e.exprName = this.tsParseEntityName(!0), this.finishNode(e, "TSTypeQuery");
  }
  tsParseTypeParameter() {
    const e = this.startNode();
    return e.name = this.tsParseTypeParameterName(), e.constraint = this.tsEatThenParseType(75), e.default = this.tsEatThenParseType(27), this.finishNode(e, "TSTypeParameter");
  }
  tsTryParseTypeParameters() {
    if (this.match(43))
      return this.tsParseTypeParameters();
  }
  tsParseTypeParameters() {
    const e = this.startNode();
    this.match(43) || this.match(132) ? this.next() : this.unexpected();
    const t = {
      value: -1
    };
    return e.params = this.tsParseBracketedList("TypeParametersOrArguments", this.tsParseTypeParameter.bind(this), !1, !0, t), e.params.length === 0 && this.raise(e.start, ne.EmptyTypeParameters), t.value !== -1 && this.addExtra(e, "trailingComma", t.value), this.finishNode(e, "TSTypeParameterDeclaration");
  }
  tsTryNextParseConstantContext() {
    return this.lookahead().type === 69 ? (this.next(), this.tsParseTypeReference()) : null;
  }
  tsFillSignature(e, t) {
    const s = e === 19;
    t.typeParameters = this.tsTryParseTypeParameters(), this.expect(10), t.parameters = this.tsParseBindingListForSignature(), s ? t.typeAnnotation = this.tsParseTypeOrTypePredicateAnnotation(e) : this.match(e) && (t.typeAnnotation = this.tsParseTypeOrTypePredicateAnnotation(e));
  }
  tsParseBindingListForSignature() {
    return this.parseBindingList(11, 41).map((e) => (e.type !== "Identifier" && e.type !== "RestElement" && e.type !== "ObjectPattern" && e.type !== "ArrayPattern" && this.raise(e.start, ne.UnsupportedSignatureParameterKind, e.type), e));
  }
  tsParseTypeMemberSemicolon() {
    !this.eat(12) && !this.isLineTerminator() && this.expect(13);
  }
  tsParseSignatureMember(e, t) {
    return this.tsFillSignature(14, t), this.tsParseTypeMemberSemicolon(), this.finishNode(t, e);
  }
  tsIsUnambiguouslyIndexSignature() {
    return this.next(), Se(this.state.type) ? (this.next(), this.match(14)) : !1;
  }
  tsTryParseIndexSignature(e) {
    if (!(this.match(0) && this.tsLookAhead(this.tsIsUnambiguouslyIndexSignature.bind(this))))
      return;
    this.expect(0);
    const t = this.parseIdentifier();
    t.typeAnnotation = this.tsParseTypeAnnotation(), this.resetEndLocation(t), this.expect(3), e.parameters = [t];
    const s = this.tsTryParseTypeAnnotation();
    return s && (e.typeAnnotation = s), this.tsParseTypeMemberSemicolon(), this.finishNode(e, "TSIndexSignature");
  }
  tsParsePropertyOrMethodSignature(e, t) {
    this.eat(17) && (e.optional = !0);
    const s = e;
    if (this.match(10) || this.match(43)) {
      t && this.raise(e.start, ne.ReadonlyForMethodSignature);
      const i = s;
      if (i.kind && this.match(43) && this.raise(this.state.pos, ne.AccesorCannotHaveTypeParameters), this.tsFillSignature(14, i), this.tsParseTypeMemberSemicolon(), i.kind === "get")
        i.parameters.length > 0 && (this.raise(this.state.pos, I.BadGetterArity), this.isThisParam(i.parameters[0]) && this.raise(this.state.pos, ne.AccesorCannotDeclareThisParameter));
      else if (i.kind === "set") {
        if (i.parameters.length !== 1)
          this.raise(this.state.pos, I.BadSetterArity);
        else {
          const n = i.parameters[0];
          this.isThisParam(n) && this.raise(this.state.pos, ne.AccesorCannotDeclareThisParameter), n.type === "Identifier" && n.optional && this.raise(this.state.pos, ne.SetAccesorCannotHaveOptionalParameter), n.type === "RestElement" && this.raise(this.state.pos, ne.SetAccesorCannotHaveRestParameter);
        }
        i.typeAnnotation && this.raise(i.typeAnnotation.start, ne.SetAccesorCannotHaveReturnType);
      } else
        i.kind = "method";
      return this.finishNode(i, "TSMethodSignature");
    } else {
      const i = s;
      t && (i.readonly = !0);
      const n = this.tsTryParseTypeAnnotation();
      return n && (i.typeAnnotation = n), this.tsParseTypeMemberSemicolon(), this.finishNode(i, "TSPropertySignature");
    }
  }
  tsParseTypeMember() {
    const e = this.startNode();
    if (this.match(10) || this.match(43))
      return this.tsParseSignatureMember("TSCallSignatureDeclaration", e);
    if (this.match(71)) {
      const s = this.startNode();
      return this.next(), this.match(10) || this.match(43) ? this.tsParseSignatureMember("TSConstructSignatureDeclaration", e) : (e.key = this.createIdentifier(s, "new"), this.tsParsePropertyOrMethodSignature(e, !1));
    }
    this.tsParseModifiers(e, ["readonly"], ["declare", "abstract", "private", "protected", "public", "static", "override"], ne.InvalidModifierOnTypeMember);
    const t = this.tsTryParseIndexSignature(e);
    return t || (this.parsePropertyName(e), !e.computed && e.key.type === "Identifier" && (e.key.name === "get" || e.key.name === "set") && this.tsTokenCanFollowModifier() && (e.kind = e.key.name, this.parsePropertyName(e)), this.tsParsePropertyOrMethodSignature(e, !!e.readonly));
  }
  tsParseTypeLiteral() {
    const e = this.startNode();
    return e.members = this.tsParseObjectTypeMembers(), this.finishNode(e, "TSTypeLiteral");
  }
  tsParseObjectTypeMembers() {
    this.expect(5);
    const e = this.tsParseList("TypeMembers", this.tsParseTypeMember.bind(this));
    return this.expect(8), e;
  }
  tsIsStartOfMappedType() {
    return this.next(), this.eat(47) ? this.isContextual(112) : (this.isContextual(112) && this.next(), !this.match(0) || (this.next(), !this.tsIsIdentifier()) ? !1 : (this.next(), this.match(52)));
  }
  tsParseMappedTypeParameter() {
    const e = this.startNode();
    return e.name = this.tsParseTypeParameterName(), e.constraint = this.tsExpectThenParseType(52), this.finishNode(e, "TSTypeParameter");
  }
  tsParseMappedType() {
    const e = this.startNode();
    return this.expect(5), this.match(47) ? (e.readonly = this.state.value, this.next(), this.expectContextual(112)) : this.eatContextual(112) && (e.readonly = !0), this.expect(0), e.typeParameter = this.tsParseMappedTypeParameter(), e.nameType = this.eatContextual(87) ? this.tsParseType() : null, this.expect(3), this.match(47) ? (e.optional = this.state.value, this.next(), this.expect(17)) : this.eat(17) && (e.optional = !0), e.typeAnnotation = this.tsTryParseType(), this.semicolon(), this.expect(8), this.finishNode(e, "TSMappedType");
  }
  tsParseTupleType() {
    const e = this.startNode();
    e.elementTypes = this.tsParseBracketedList("TupleElementTypes", this.tsParseTupleElementType.bind(this), !0, !1);
    let t = !1, s = null;
    return e.elementTypes.forEach((i) => {
      var n;
      let {
        type: a
      } = i;
      t && a !== "TSRestType" && a !== "TSOptionalType" && !(a === "TSNamedTupleMember" && i.optional) && this.raise(i.start, ne.OptionalTypeBeforeRequired), t = t || a === "TSNamedTupleMember" && i.optional || a === "TSOptionalType", a === "TSRestType" && (i = i.typeAnnotation, a = i.type);
      const o = a === "TSNamedTupleMember";
      s = (n = s) != null ? n : o, s !== o && this.raise(i.start, ne.MixedLabeledAndUnlabeledElements);
    }), this.finishNode(e, "TSTupleType");
  }
  tsParseTupleElementType() {
    const {
      start: e,
      startLoc: t
    } = this.state, s = this.eat(21);
    let i = this.tsParseType();
    const n = this.eat(17);
    if (this.eat(14)) {
      const o = this.startNodeAtNode(i);
      o.optional = n, i.type === "TSTypeReference" && !i.typeParameters && i.typeName.type === "Identifier" ? o.label = i.typeName : (this.raise(i.start, ne.InvalidTupleMemberLabel), o.label = i), o.elementType = this.tsParseType(), i = this.finishNode(o, "TSNamedTupleMember");
    } else if (n) {
      const o = this.startNodeAtNode(i);
      o.typeAnnotation = i, i = this.finishNode(o, "TSOptionalType");
    }
    if (s) {
      const o = this.startNodeAt(e, t);
      o.typeAnnotation = i, i = this.finishNode(o, "TSRestType");
    }
    return i;
  }
  tsParseParenthesizedType() {
    const e = this.startNode();
    return this.expect(10), e.typeAnnotation = this.tsParseType(), this.expect(11), this.finishNode(e, "TSParenthesizedType");
  }
  tsParseFunctionOrConstructorType(e, t) {
    const s = this.startNode();
    return e === "TSConstructorType" && (s.abstract = !!t, t && this.next(), this.next()), this.tsFillSignature(19, s), this.finishNode(s, e);
  }
  tsParseLiteralTypeNode() {
    const e = this.startNode();
    return e.literal = (() => {
      switch (this.state.type) {
        case 124:
        case 125:
        case 123:
        case 79:
        case 80:
          return this.parseExprAtom();
        default:
          throw this.unexpected();
      }
    })(), this.finishNode(e, "TSLiteralType");
  }
  tsParseTemplateLiteralType() {
    const e = this.startNode();
    return e.literal = this.parseTemplate(!1), this.finishNode(e, "TSLiteralType");
  }
  parseTemplateSubstitution() {
    return this.state.inType ? this.tsParseType() : super.parseTemplateSubstitution();
  }
  tsParseThisTypeOrThisTypePredicate() {
    const e = this.tsParseThisTypeNode();
    return this.isContextual(107) && !this.hasPrecedingLineBreak() ? this.tsParseThisTypePredicate(e) : e;
  }
  tsParseNonArrayType() {
    switch (this.state.type) {
      case 123:
      case 124:
      case 125:
      case 79:
      case 80:
        return this.tsParseLiteralTypeNode();
      case 47:
        if (this.state.value === "-") {
          const e = this.startNode(), t = this.lookahead();
          if (t.type !== 124 && t.type !== 125)
            throw this.unexpected();
          return e.literal = this.parseMaybeUnary(), this.finishNode(e, "TSLiteralType");
        }
        break;
      case 72:
        return this.tsParseThisTypeOrThisTypePredicate();
      case 81:
        return this.tsParseTypeQuery();
      case 77:
        return this.tsParseImportType();
      case 5:
        return this.tsLookAhead(this.tsIsStartOfMappedType.bind(this)) ? this.tsParseMappedType() : this.tsParseTypeLiteral();
      case 0:
        return this.tsParseTupleType();
      case 10:
        return this.tsParseParenthesizedType();
      case 22:
        return this.tsParseTemplateLiteralType();
      default: {
        const {
          type: e
        } = this.state;
        if (Se(e) || e === 82 || e === 78) {
          const t = e === 82 ? "TSVoidKeyword" : e === 78 ? "TSNullKeyword" : K1(this.state.value);
          if (t !== void 0 && this.lookaheadCharCode() !== 46) {
            const s = this.startNode();
            return this.next(), this.finishNode(s, t);
          }
          return this.tsParseTypeReference();
        }
      }
    }
    throw this.unexpected();
  }
  tsParseArrayTypeOrHigher() {
    let e = this.tsParseNonArrayType();
    for (; !this.hasPrecedingLineBreak() && this.eat(0); )
      if (this.match(3)) {
        const t = this.startNodeAtNode(e);
        t.elementType = e, this.expect(3), e = this.finishNode(t, "TSArrayType");
      } else {
        const t = this.startNodeAtNode(e);
        t.objectType = e, t.indexType = this.tsParseType(), this.expect(3), e = this.finishNode(t, "TSIndexedAccessType");
      }
    return e;
  }
  tsParseTypeOperator() {
    const e = this.startNode(), t = this.state.value;
    return this.next(), e.operator = t, e.typeAnnotation = this.tsParseTypeOperatorOrHigher(), t === "readonly" && this.tsCheckTypeAnnotationForReadOnly(e), this.finishNode(e, "TSTypeOperator");
  }
  tsCheckTypeAnnotationForReadOnly(e) {
    switch (e.typeAnnotation.type) {
      case "TSTupleType":
      case "TSArrayType":
        return;
      default:
        this.raise(e.start, ne.UnexpectedReadonly);
    }
  }
  tsParseInferType() {
    const e = this.startNode();
    this.expectContextual(106);
    const t = this.startNode();
    return t.name = this.tsParseTypeParameterName(), e.typeParameter = this.finishNode(t, "TSTypeParameter"), this.finishNode(e, "TSInferType");
  }
  tsParseTypeOperatorOrHigher() {
    return K0(this.state.type) && !this.state.containsEsc ? this.tsParseTypeOperator() : this.isContextual(106) ? this.tsParseInferType() : this.tsParseArrayTypeOrHigher();
  }
  tsParseUnionOrIntersectionType(e, t, s) {
    const i = this.startNode(), n = this.eat(s), a = [];
    do
      a.push(t());
    while (this.eat(s));
    return a.length === 1 && !n ? a[0] : (i.types = a, this.finishNode(i, e));
  }
  tsParseIntersectionTypeOrHigher() {
    return this.tsParseUnionOrIntersectionType("TSIntersectionType", this.tsParseTypeOperatorOrHigher.bind(this), 41);
  }
  tsParseUnionTypeOrHigher() {
    return this.tsParseUnionOrIntersectionType("TSUnionType", this.tsParseIntersectionTypeOrHigher.bind(this), 39);
  }
  tsIsStartOfFunctionType() {
    return this.match(43) ? !0 : this.match(10) && this.tsLookAhead(this.tsIsUnambiguouslyStartOfFunctionType.bind(this));
  }
  tsSkipParameterStart() {
    if (Se(this.state.type) || this.match(72))
      return this.next(), !0;
    if (this.match(5)) {
      let e = 1;
      for (this.next(); e > 0; )
        this.match(5) ? ++e : this.match(8) && --e, this.next();
      return !0;
    }
    if (this.match(0)) {
      let e = 1;
      for (this.next(); e > 0; )
        this.match(0) ? ++e : this.match(3) && --e, this.next();
      return !0;
    }
    return !1;
  }
  tsIsUnambiguouslyStartOfFunctionType() {
    return this.next(), !!(this.match(11) || this.match(21) || this.tsSkipParameterStart() && (this.match(14) || this.match(12) || this.match(17) || this.match(27) || this.match(11) && (this.next(), this.match(19))));
  }
  tsParseTypeOrTypePredicateAnnotation(e) {
    return this.tsInType(() => {
      const t = this.startNode();
      this.expect(e);
      const s = this.startNode(), i = !!this.tsTryParse(this.tsParseTypePredicateAsserts.bind(this));
      if (i && this.match(72)) {
        let o = this.tsParseThisTypeOrThisTypePredicate();
        return o.type === "TSThisType" ? (s.parameterName = o, s.asserts = !0, s.typeAnnotation = null, o = this.finishNode(s, "TSTypePredicate")) : (this.resetStartLocationFromNode(o, s), o.asserts = !0), t.typeAnnotation = o, this.finishNode(t, "TSTypeAnnotation");
      }
      const n = this.tsIsIdentifier() && this.tsTryParse(this.tsParseTypePredicatePrefix.bind(this));
      if (!n)
        return i ? (s.parameterName = this.parseIdentifier(), s.asserts = i, s.typeAnnotation = null, t.typeAnnotation = this.finishNode(s, "TSTypePredicate"), this.finishNode(t, "TSTypeAnnotation")) : this.tsParseTypeAnnotation(!1, t);
      const a = this.tsParseTypeAnnotation(!1);
      return s.parameterName = n, s.typeAnnotation = a, s.asserts = i, t.typeAnnotation = this.finishNode(s, "TSTypePredicate"), this.finishNode(t, "TSTypeAnnotation");
    });
  }
  tsTryParseTypeOrTypePredicateAnnotation() {
    return this.match(14) ? this.tsParseTypeOrTypePredicateAnnotation(14) : void 0;
  }
  tsTryParseTypeAnnotation() {
    return this.match(14) ? this.tsParseTypeAnnotation() : void 0;
  }
  tsTryParseType() {
    return this.tsEatThenParseType(14);
  }
  tsParseTypePredicatePrefix() {
    const e = this.parseIdentifier();
    if (this.isContextual(107) && !this.hasPrecedingLineBreak())
      return this.next(), e;
  }
  tsParseTypePredicateAsserts() {
    if (this.state.type !== 100)
      return !1;
    const e = this.state.containsEsc;
    return this.next(), !Se(this.state.type) && !this.match(72) ? !1 : (e && this.raise(this.state.lastTokStart, I.InvalidEscapedReservedWord, "asserts"), !0);
  }
  tsParseTypeAnnotation(e = !0, t = this.startNode()) {
    return this.tsInType(() => {
      e && this.expect(14), t.typeAnnotation = this.tsParseType();
    }), this.finishNode(t, "TSTypeAnnotation");
  }
  tsParseType() {
    Ic(this.state.inType);
    const e = this.tsParseNonConditionalType();
    if (this.hasPrecedingLineBreak() || !this.eat(75))
      return e;
    const t = this.startNodeAtNode(e);
    return t.checkType = e, t.extendsType = this.tsParseNonConditionalType(), this.expect(17), t.trueType = this.tsParseType(), this.expect(14), t.falseType = this.tsParseType(), this.finishNode(t, "TSConditionalType");
  }
  isAbstractConstructorSignature() {
    return this.isContextual(114) && this.lookahead().type === 71;
  }
  tsParseNonConditionalType() {
    return this.tsIsStartOfFunctionType() ? this.tsParseFunctionOrConstructorType("TSFunctionType") : this.match(71) ? this.tsParseFunctionOrConstructorType("TSConstructorType") : this.isAbstractConstructorSignature() ? this.tsParseFunctionOrConstructorType("TSConstructorType", !0) : this.tsParseUnionTypeOrHigher();
  }
  tsParseTypeAssertion() {
    this.getPluginOption("typescript", "disallowAmbiguousJSXLike") && this.raise(this.state.start, ne.ReservedTypeAssertion);
    const e = this.startNode(), t = this.tsTryNextParseConstantContext();
    return e.typeAnnotation = t || this.tsNextThenParseType(), this.expect(44), e.expression = this.parseMaybeUnary(), this.finishNode(e, "TSTypeAssertion");
  }
  tsParseHeritageClause(e) {
    const t = this.state.start, s = this.tsParseDelimitedList("HeritageClauseElement", this.tsParseExpressionWithTypeArguments.bind(this));
    return s.length || this.raise(t, ne.EmptyHeritageClauseType, e), s;
  }
  tsParseExpressionWithTypeArguments() {
    const e = this.startNode();
    return e.expression = this.tsParseEntityName(!1), this.match(43) && (e.typeParameters = this.tsParseTypeArguments()), this.finishNode(e, "TSExpressionWithTypeArguments");
  }
  tsParseInterfaceDeclaration(e) {
    Se(this.state.type) ? (e.id = this.parseIdentifier(), this.checkLVal(e.id, "typescript interface declaration", o1)) : (e.id = null, this.raise(this.state.start, ne.MissingInterfaceName)), e.typeParameters = this.tsTryParseTypeParameters(), this.eat(75) && (e.extends = this.tsParseHeritageClause("extends"));
    const t = this.startNode();
    return t.body = this.tsInType(this.tsParseObjectTypeMembers.bind(this)), e.body = this.finishNode(t, "TSInterfaceBody"), this.finishNode(e, "TSInterfaceDeclaration");
  }
  tsParseTypeAliasDeclaration(e) {
    return e.id = this.parseIdentifier(), this.checkLVal(e.id, "typescript type alias", l1), e.typeParameters = this.tsTryParseTypeParameters(), e.typeAnnotation = this.tsInType(() => {
      if (this.expect(27), this.isContextual(105) && this.lookahead().type !== 16) {
        const t = this.startNode();
        return this.next(), this.finishNode(t, "TSIntrinsicKeyword");
      }
      return this.tsParseType();
    }), this.semicolon(), this.finishNode(e, "TSTypeAliasDeclaration");
  }
  tsInNoContext(e) {
    const t = this.state.context;
    this.state.context = [t[0]];
    try {
      return e();
    } finally {
      this.state.context = t;
    }
  }
  tsInType(e) {
    const t = this.state.inType;
    this.state.inType = !0;
    try {
      return e();
    } finally {
      this.state.inType = t;
    }
  }
  tsEatThenParseType(e) {
    return this.match(e) ? this.tsNextThenParseType() : void 0;
  }
  tsExpectThenParseType(e) {
    return this.tsDoThenParseType(() => this.expect(e));
  }
  tsNextThenParseType() {
    return this.tsDoThenParseType(() => this.next());
  }
  tsDoThenParseType(e) {
    return this.tsInType(() => (e(), this.tsParseType()));
  }
  tsParseEnumMember() {
    const e = this.startNode();
    return e.id = this.match(123) ? this.parseExprAtom() : this.parseIdentifier(!0), this.eat(27) && (e.initializer = this.parseMaybeAssignAllowIn()), this.finishNode(e, "TSEnumMember");
  }
  tsParseEnumDeclaration(e, t) {
    return t && (e.const = !0), e.id = this.parseIdentifier(), this.checkLVal(e.id, "typescript enum declaration", t ? c1 : np), this.expect(5), e.members = this.tsParseDelimitedList("EnumMembers", this.tsParseEnumMember.bind(this)), this.expect(8), this.finishNode(e, "TSEnumDeclaration");
  }
  tsParseModuleBlock() {
    const e = this.startNode();
    return this.scope.enter(ns), this.expect(5), this.parseBlockOrModuleBlockBody(e.body = [], void 0, !0, 8), this.scope.exit(), this.finishNode(e, "TSModuleBlock");
  }
  tsParseModuleOrNamespaceDeclaration(e, t = !1) {
    if (e.id = this.parseIdentifier(), t || this.checkLVal(e.id, "module or namespace declaration", h1), this.eat(16)) {
      const s = this.startNode();
      this.tsParseModuleOrNamespaceDeclaration(s, !0), e.body = s;
    } else
      this.scope.enter(un), this.prodParam.enter(ms), e.body = this.tsParseModuleBlock(), this.prodParam.exit(), this.scope.exit();
    return this.finishNode(e, "TSModuleDeclaration");
  }
  tsParseAmbientExternalModuleDeclaration(e) {
    return this.isContextual(103) ? (e.global = !0, e.id = this.parseIdentifier()) : this.match(123) ? e.id = this.parseExprAtom() : this.unexpected(), this.match(5) ? (this.scope.enter(un), this.prodParam.enter(ms), e.body = this.tsParseModuleBlock(), this.prodParam.exit(), this.scope.exit()) : this.semicolon(), this.finishNode(e, "TSModuleDeclaration");
  }
  tsParseImportEqualsDeclaration(e, t) {
    e.isExport = t || !1, e.id = this.parseIdentifier(), this.checkLVal(e.id, "import equals declaration", yt), this.expect(27);
    const s = this.tsParseModuleReference();
    return e.importKind === "type" && s.type !== "TSExternalModuleReference" && this.raise(s.start, ne.ImportAliasHasImportType), e.moduleReference = s, this.semicolon(), this.finishNode(e, "TSImportEqualsDeclaration");
  }
  tsIsExternalModuleReference() {
    return this.isContextual(110) && this.lookaheadCharCode() === 40;
  }
  tsParseModuleReference() {
    return this.tsIsExternalModuleReference() ? this.tsParseExternalModuleReference() : this.tsParseEntityName(!1);
  }
  tsParseExternalModuleReference() {
    const e = this.startNode();
    if (this.expectContextual(110), this.expect(10), !this.match(123))
      throw this.unexpected();
    return e.expression = this.parseExprAtom(), this.expect(11), this.finishNode(e, "TSExternalModuleReference");
  }
  tsLookAhead(e) {
    const t = this.state.clone(), s = e();
    return this.state = t, s;
  }
  tsTryParseAndCatch(e) {
    const t = this.tryParse((s) => e() || s());
    if (!(t.aborted || !t.node))
      return t.error && (this.state = t.failState), t.node;
  }
  tsTryParse(e) {
    const t = this.state.clone(), s = e();
    if (s !== void 0 && s !== !1)
      return s;
    this.state = t;
  }
  tsTryParseDeclare(e) {
    if (this.isLineTerminator())
      return;
    let t = this.state.type, s;
    return this.isContextual(93) && (t = 68, s = "let"), this.tsInAmbientContext(() => {
      switch (t) {
        case 62:
          return e.declare = !0, this.parseFunctionStatement(e, !1, !0);
        case 74:
          return e.declare = !0, this.parseClass(e, !0, !1);
        case 69:
          if (this.match(69) && this.isLookaheadContextual("enum"))
            return this.expect(69), this.expectContextual(116), this.tsParseEnumDeclaration(e, !0);
        case 68:
          return s = s || this.state.value, this.parseVarStatement(e, s);
        case 103:
          return this.tsParseAmbientExternalModuleDeclaration(e);
        default:
          if (Se(t))
            return this.tsParseDeclaration(e, this.state.value, !0);
      }
    });
  }
  tsTryParseExportDeclaration() {
    return this.tsParseDeclaration(this.startNode(), this.state.value, !0);
  }
  tsParseExpressionStatement(e, t) {
    switch (t.name) {
      case "declare": {
        const s = this.tsTryParseDeclare(e);
        if (s)
          return s.declare = !0, s;
        break;
      }
      case "global":
        if (this.match(5)) {
          this.scope.enter(un), this.prodParam.enter(ms);
          const s = e;
          return s.global = !0, s.id = t, s.body = this.tsParseModuleBlock(), this.scope.exit(), this.prodParam.exit(), this.finishNode(s, "TSModuleDeclaration");
        }
        break;
      default:
        return this.tsParseDeclaration(e, t.name, !1);
    }
  }
  tsParseDeclaration(e, t, s) {
    switch (t) {
      case "abstract":
        if (this.tsCheckLineTerminator(s) && (this.match(74) || Se(this.state.type)))
          return this.tsParseAbstractDeclaration(e);
        break;
      case "enum":
        if (s || Se(this.state.type))
          return s && this.next(), this.tsParseEnumDeclaration(e, !1);
        break;
      case "interface":
        if (this.tsCheckLineTerminator(s) && Se(this.state.type))
          return this.tsParseInterfaceDeclaration(e);
        break;
      case "module":
        if (this.tsCheckLineTerminator(s)) {
          if (this.match(123))
            return this.tsParseAmbientExternalModuleDeclaration(e);
          if (Se(this.state.type))
            return this.tsParseModuleOrNamespaceDeclaration(e);
        }
        break;
      case "namespace":
        if (this.tsCheckLineTerminator(s) && Se(this.state.type))
          return this.tsParseModuleOrNamespaceDeclaration(e);
        break;
      case "type":
        if (this.tsCheckLineTerminator(s) && Se(this.state.type))
          return this.tsParseTypeAliasDeclaration(e);
        break;
    }
  }
  tsCheckLineTerminator(e) {
    return e ? this.hasFollowingLineBreak() ? !1 : (this.next(), !0) : !this.isLineTerminator();
  }
  tsTryParseGenericAsyncArrowFunction(e, t) {
    if (!this.match(43))
      return;
    const s = this.state.maybeInArrowParameters;
    this.state.maybeInArrowParameters = !0;
    const i = this.tsTryParseAndCatch(() => {
      const n = this.startNodeAt(e, t);
      return n.typeParameters = this.tsParseTypeParameters(), super.parseFunctionParams(n), n.returnType = this.tsTryParseTypeOrTypePredicateAnnotation(), this.expect(19), n;
    });
    if (this.state.maybeInArrowParameters = s, !!i)
      return this.parseArrowExpression(i, null, !0);
  }
  tsParseTypeArguments() {
    const e = this.startNode();
    return e.params = this.tsInType(() => this.tsInNoContext(() => (this.expect(43), this.tsParseDelimitedList("TypeParametersOrArguments", this.tsParseType.bind(this))))), e.params.length === 0 && this.raise(e.start, ne.EmptyTypeArguments), this.expect(44), this.finishNode(e, "TSTypeParameterInstantiation");
  }
  tsIsDeclarationStart() {
    return G0(this.state.type);
  }
  isExportDefaultSpecifier() {
    return this.tsIsDeclarationStart() ? !1 : super.isExportDefaultSpecifier();
  }
  parseAssignableListItem(e, t) {
    const s = this.state.start, i = this.state.startLoc;
    let n, a = !1, o = !1;
    if (e !== void 0) {
      const c = {};
      this.tsParseModifiers(c, ["public", "private", "protected", "override", "readonly"]), n = c.accessibility, o = c.override, a = c.readonly, e === !1 && (n || a || o) && this.raise(s, ne.UnexpectedParameterModifier);
    }
    const l = this.parseMaybeDefault();
    this.parseAssignableListItemTypes(l);
    const u = this.parseMaybeDefault(l.start, l.loc.start, l);
    if (n || a || o) {
      const c = this.startNodeAt(s, i);
      return t.length && (c.decorators = t), n && (c.accessibility = n), a && (c.readonly = a), o && (c.override = o), u.type !== "Identifier" && u.type !== "AssignmentPattern" && this.raise(c.start, ne.UnsupportedParameterPropertyKind), c.parameter = u, this.finishNode(c, "TSParameterProperty");
    }
    return t.length && (l.decorators = t), u;
  }
  parseFunctionBodyAndFinish(e, t, s = !1) {
    this.match(14) && (e.returnType = this.tsParseTypeOrTypePredicateAnnotation(14));
    const i = t === "FunctionDeclaration" ? "TSDeclareFunction" : t === "ClassMethod" || t === "ClassPrivateMethod" ? "TSDeclareMethod" : void 0;
    if (i && !this.match(5) && this.isLineTerminator()) {
      this.finishNode(e, i);
      return;
    }
    if (i === "TSDeclareFunction" && this.state.isAmbientContext && (this.raise(e.start, ne.DeclareFunctionHasImplementation), e.declare)) {
      super.parseFunctionBodyAndFinish(e, i, s);
      return;
    }
    super.parseFunctionBodyAndFinish(e, t, s);
  }
  registerFunctionStatementId(e) {
    !e.body && e.id ? this.checkLVal(e.id, "function name", Tc) : super.registerFunctionStatementId(...arguments);
  }
  tsCheckForInvalidTypeCasts(e) {
    e.forEach((t) => {
      (t == null ? void 0 : t.type) === "TSTypeCastExpression" && this.raise(t.typeAnnotation.start, ne.UnexpectedTypeAnnotation);
    });
  }
  toReferencedList(e, t) {
    return this.tsCheckForInvalidTypeCasts(e), e;
  }
  parseArrayLike(...e) {
    const t = super.parseArrayLike(...e);
    return t.type === "ArrayExpression" && this.tsCheckForInvalidTypeCasts(t.elements), t;
  }
  parseSubscript(e, t, s, i, n) {
    if (!this.hasPrecedingLineBreak() && this.match(33)) {
      this.state.canStartJSXElement = !1, this.next();
      const o = this.startNodeAt(t, s);
      return o.expression = e, this.finishNode(o, "TSNonNullExpression");
    }
    let a = !1;
    if (this.match(18) && this.lookaheadCharCode() === 60) {
      if (i)
        return n.stop = !0, e;
      n.optionalChainMember = a = !0, this.next();
    }
    if (this.match(43)) {
      let o;
      const l = this.tsTryParseAndCatch(() => {
        if (!i && this.atPossibleAsyncArrow(e)) {
          const h = this.tsTryParseGenericAsyncArrowFunction(t, s);
          if (h)
            return h;
        }
        const u = this.startNodeAt(t, s);
        u.callee = e;
        const c = this.tsParseTypeArguments();
        if (c) {
          if (a && !this.match(10) && (o = this.state.pos, this.unexpected()), !i && this.eat(10))
            return u.arguments = this.parseCallExpressionArguments(11, !1), this.tsCheckForInvalidTypeCasts(u.arguments), u.typeParameters = c, n.optionalChainMember && (u.optional = a), this.finishCallExpression(u, n.optionalChainMember);
          if (this.match(22)) {
            const h = this.parseTaggedTemplateExpression(e, t, s, n);
            return h.typeParameters = c, h;
          }
        }
        this.unexpected();
      });
      if (o && this.unexpected(o, 10), l)
        return l;
    }
    return super.parseSubscript(e, t, s, i, n);
  }
  parseNewArguments(e) {
    if (this.match(43)) {
      const t = this.tsTryParseAndCatch(() => {
        const s = this.tsParseTypeArguments();
        return this.match(10) || this.unexpected(), s;
      });
      t && (e.typeParameters = t);
    }
    super.parseNewArguments(e);
  }
  parseExprOp(e, t, s, i) {
    if (on(52) > i && !this.hasPrecedingLineBreak() && this.isContextual(87)) {
      const n = this.startNodeAt(t, s);
      n.expression = e;
      const a = this.tsTryNextParseConstantContext();
      return a ? n.typeAnnotation = a : n.typeAnnotation = this.tsNextThenParseType(), this.finishNode(n, "TSAsExpression"), this.reScan_lt_gt(), this.parseExprOp(n, t, s, i);
    }
    return super.parseExprOp(e, t, s, i);
  }
  checkReservedWord(e, t, s, i) {
  }
  checkDuplicateExports() {
  }
  parseImport(e) {
    if (e.importKind = "value", Se(this.state.type) || this.match(49) || this.match(5)) {
      let s = this.lookahead();
      if (this.isContextual(120) && s.type !== 12 && s.type !== 91 && s.type !== 27 && (e.importKind = "type", this.next(), s = this.lookahead()), Se(this.state.type) && s.type === 27)
        return this.tsParseImportEqualsDeclaration(e);
    }
    const t = super.parseImport(e);
    return t.importKind === "type" && t.specifiers.length > 1 && t.specifiers[0].type === "ImportDefaultSpecifier" && this.raise(t.start, ne.TypeImportCannotSpecifyDefaultAndNamed), t;
  }
  parseExport(e) {
    if (this.match(77))
      return this.next(), this.isContextual(120) && this.lookaheadCharCode() !== 61 ? (e.importKind = "type", this.next()) : e.importKind = "value", this.tsParseImportEqualsDeclaration(e, !0);
    if (this.eat(27)) {
      const t = e;
      return t.expression = this.parseExpression(), this.semicolon(), this.finishNode(t, "TSExportAssignment");
    } else if (this.eatContextual(87)) {
      const t = e;
      return this.expectContextual(118), t.id = this.parseIdentifier(), this.semicolon(), this.finishNode(t, "TSNamespaceExportDeclaration");
    } else
      return this.isContextual(120) && this.lookahead().type === 5 ? (this.next(), e.exportKind = "type") : e.exportKind = "value", super.parseExport(e);
  }
  isAbstractClass() {
    return this.isContextual(114) && this.lookahead().type === 74;
  }
  parseExportDefaultExpression() {
    if (this.isAbstractClass()) {
      const e = this.startNode();
      return this.next(), e.abstract = !0, this.parseClass(e, !0, !0), e;
    }
    if (this.match(119)) {
      const e = this.startNode();
      this.next();
      const t = this.tsParseInterfaceDeclaration(e);
      if (t)
        return t;
    }
    return super.parseExportDefaultExpression();
  }
  parseStatementContent(e, t) {
    if (this.state.type === 69 && this.lookahead().type === 116) {
      const i = this.startNode();
      return this.next(), this.expectContextual(116), this.tsParseEnumDeclaration(i, !0);
    }
    return super.parseStatementContent(e, t);
  }
  parseAccessModifier() {
    return this.tsParseModifier(["public", "protected", "private"]);
  }
  tsHasSomeModifiers(e, t) {
    return t.some((s) => Nc(s) ? e.accessibility === s : !!e[s]);
  }
  tsIsStartOfStaticBlocks() {
    return this.isContextual(98) && this.lookaheadCharCode() === 123;
  }
  parseClassMember(e, t, s) {
    const i = ["declare", "private", "public", "protected", "override", "abstract", "readonly", "static"];
    this.tsParseModifiers(t, i, void 0, void 0, !0);
    const n = () => {
      this.tsIsStartOfStaticBlocks() ? (this.next(), this.next(), this.tsHasSomeModifiers(t, i) && this.raise(this.state.pos, ne.StaticBlockCannotHaveModifier), this.parseClassStaticBlock(e, t)) : this.parseClassMemberWithIsStatic(e, t, s, !!t.static);
    };
    t.declare ? this.tsInAmbientContext(n) : n();
  }
  parseClassMemberWithIsStatic(e, t, s, i) {
    const n = this.tsTryParseIndexSignature(t);
    if (n) {
      e.body.push(n), t.abstract && this.raise(t.start, ne.IndexSignatureHasAbstract), t.accessibility && this.raise(t.start, ne.IndexSignatureHasAccessibility, t.accessibility), t.declare && this.raise(t.start, ne.IndexSignatureHasDeclare), t.override && this.raise(t.start, ne.IndexSignatureHasOverride);
      return;
    }
    !this.state.inAbstractClass && t.abstract && this.raise(t.start, ne.NonAbstractClassHasAbstractMethod), t.override && (s.hadSuperClass || this.raise(t.start, ne.OverrideNotInSubClass)), super.parseClassMemberWithIsStatic(e, t, s, i);
  }
  parsePostMemberNameModifiers(e) {
    this.eat(17) && (e.optional = !0), e.readonly && this.match(10) && this.raise(e.start, ne.ClassMethodHasReadonly), e.declare && this.match(10) && this.raise(e.start, ne.ClassMethodHasDeclare);
  }
  parseExpressionStatement(e, t) {
    return (t.type === "Identifier" ? this.tsParseExpressionStatement(e, t) : void 0) || super.parseExpressionStatement(e, t);
  }
  shouldParseExportDeclaration() {
    return this.tsIsDeclarationStart() ? !0 : super.shouldParseExportDeclaration();
  }
  parseConditional(e, t, s, i) {
    if (!this.state.maybeInArrowParameters || !this.match(17))
      return super.parseConditional(e, t, s, i);
    const n = this.tryParse(() => super.parseConditional(e, t, s));
    return n.node ? (n.error && (this.state = n.failState), n.node) : (n.error && super.setOptionalParametersError(i, n.error), e);
  }
  parseParenItem(e, t, s) {
    if (e = super.parseParenItem(e, t, s), this.eat(17) && (e.optional = !0, this.resetEndLocation(e)), this.match(14)) {
      const i = this.startNodeAt(t, s);
      return i.expression = e, i.typeAnnotation = this.tsParseTypeAnnotation(), this.finishNode(i, "TSTypeCastExpression");
    }
    return e;
  }
  parseExportDeclaration(e) {
    const t = this.state.start, s = this.state.startLoc, i = this.eatContextual(115);
    if (i && (this.isContextual(115) || !this.shouldParseExportDeclaration()))
      throw this.raise(this.state.start, ne.ExpectedAmbientAfterExportDeclare);
    let n;
    return Se(this.state.type) && (n = this.tsTryParseExportDeclaration()), n || (n = super.parseExportDeclaration(e)), n && (n.type === "TSInterfaceDeclaration" || n.type === "TSTypeAliasDeclaration" || i) && (e.exportKind = "type"), n && i && (this.resetStartLocation(n, t, s), n.declare = !0), n;
  }
  parseClassId(e, t, s) {
    if ((!t || s) && this.isContextual(104))
      return;
    super.parseClassId(e, t, s, e.declare ? Tc : sp);
    const i = this.tsTryParseTypeParameters();
    i && (e.typeParameters = i);
  }
  parseClassPropertyAnnotation(e) {
    !e.optional && this.eat(33) && (e.definite = !0);
    const t = this.tsTryParseTypeAnnotation();
    t && (e.typeAnnotation = t);
  }
  parseClassProperty(e) {
    if (this.parseClassPropertyAnnotation(e), this.state.isAmbientContext && this.match(27) && this.raise(this.state.start, ne.DeclareClassFieldHasInitializer), e.abstract && this.match(27)) {
      const {
        key: t
      } = e;
      this.raise(this.state.start, ne.AbstractPropertyHasInitializer, t.type === "Identifier" && !e.computed ? t.name : `[${this.input.slice(t.start, t.end)}]`);
    }
    return super.parseClassProperty(e);
  }
  parseClassPrivateProperty(e) {
    return e.abstract && this.raise(e.start, ne.PrivateElementHasAbstract), e.accessibility && this.raise(e.start, ne.PrivateElementHasAccessibility, e.accessibility), this.parseClassPropertyAnnotation(e), super.parseClassPrivateProperty(e);
  }
  pushClassMethod(e, t, s, i, n, a) {
    const o = this.tsTryParseTypeParameters();
    o && n && this.raise(o.start, ne.ConstructorHasTypeParameters), t.declare && (t.kind === "get" || t.kind === "set") && this.raise(t.start, ne.DeclareAccessor, t.kind), o && (t.typeParameters = o), super.pushClassMethod(e, t, s, i, n, a);
  }
  pushClassPrivateMethod(e, t, s, i) {
    const n = this.tsTryParseTypeParameters();
    n && (t.typeParameters = n), super.pushClassPrivateMethod(e, t, s, i);
  }
  declareClassPrivateMethodInScope(e, t) {
    e.type !== "TSDeclareMethod" && (e.type === "MethodDefinition" && !e.value.body || super.declareClassPrivateMethodInScope(e, t));
  }
  parseClassSuper(e) {
    super.parseClassSuper(e), e.superClass && this.match(43) && (e.superTypeParameters = this.tsParseTypeArguments()), this.eatContextual(104) && (e.implements = this.tsParseHeritageClause("implements"));
  }
  parseObjPropValue(e, ...t) {
    const s = this.tsTryParseTypeParameters();
    s && (e.typeParameters = s), super.parseObjPropValue(e, ...t);
  }
  parseFunctionParams(e, t) {
    const s = this.tsTryParseTypeParameters();
    s && (e.typeParameters = s), super.parseFunctionParams(e, t);
  }
  parseVarId(e, t) {
    super.parseVarId(e, t), e.id.type === "Identifier" && this.eat(33) && (e.definite = !0);
    const s = this.tsTryParseTypeAnnotation();
    s && (e.id.typeAnnotation = s, this.resetEndLocation(e.id));
  }
  parseAsyncArrowFromCallExpression(e, t) {
    return this.match(14) && (e.returnType = this.tsParseTypeAnnotation()), super.parseAsyncArrowFromCallExpression(e, t);
  }
  parseMaybeAssign(...e) {
    var t, s, i, n, a, o, l;
    let u, c, h;
    if (this.hasPlugin("jsx") && (this.match(132) || this.match(43))) {
      if (u = this.state.clone(), c = this.tryParse(() => super.parseMaybeAssign(...e), u), !c.error)
        return c.node;
      const {
        context: x
      } = this.state;
      x[x.length - 1] === ke.j_oTag ? x.length -= 2 : x[x.length - 1] === ke.j_expr && (x.length -= 1);
    }
    if (!((t = c) != null && t.error) && !this.match(43))
      return super.parseMaybeAssign(...e);
    let f;
    u = u || this.state.clone();
    const p = this.tryParse((x) => {
      var d, m;
      f = this.tsParseTypeParameters();
      const y = super.parseMaybeAssign(...e);
      return (y.type !== "ArrowFunctionExpression" || (d = y.extra) != null && d.parenthesized) && x(), ((m = f) == null ? void 0 : m.params.length) !== 0 && this.resetStartLocationFromNode(y, f), y.typeParameters = f, y;
    }, u);
    if (!p.error && !p.aborted)
      return f && this.reportReservedArrowTypeParam(f), p.node;
    if (!c && (Ic(!this.hasPlugin("jsx")), h = this.tryParse(() => super.parseMaybeAssign(...e), u), !h.error))
      return h.node;
    if ((s = c) != null && s.node)
      return this.state = c.failState, c.node;
    if (p.node)
      return this.state = p.failState, f && this.reportReservedArrowTypeParam(f), p.node;
    if ((i = h) != null && i.node)
      return this.state = h.failState, h.node;
    throw (n = c) != null && n.thrown ? c.error : p.thrown ? p.error : (a = h) != null && a.thrown ? h.error : ((o = c) == null ? void 0 : o.error) || p.error || ((l = h) == null ? void 0 : l.error);
  }
  reportReservedArrowTypeParam(e) {
    var t;
    e.params.length === 1 && !((t = e.extra) != null && t.trailingComma) && this.getPluginOption("typescript", "disallowAmbiguousJSXLike") && this.raise(e.start, ne.ReservedArrowTypeParam);
  }
  parseMaybeUnary(e) {
    return !this.hasPlugin("jsx") && this.match(43) ? this.tsParseTypeAssertion() : super.parseMaybeUnary(e);
  }
  parseArrow(e) {
    if (this.match(14)) {
      const t = this.tryParse((s) => {
        const i = this.tsParseTypeOrTypePredicateAnnotation(14);
        return (this.canInsertSemicolon() || !this.match(19)) && s(), i;
      });
      if (t.aborted)
        return;
      t.thrown || (t.error && (this.state = t.failState), e.returnType = t.node);
    }
    return super.parseArrow(e);
  }
  parseAssignableListItemTypes(e) {
    this.eat(17) && (e.type !== "Identifier" && !this.state.isAmbientContext && !this.state.inType && this.raise(e.start, ne.PatternIsOptional), e.optional = !0);
    const t = this.tsTryParseTypeAnnotation();
    return t && (e.typeAnnotation = t), this.resetEndLocation(e), e;
  }
  isAssignable(e, t) {
    switch (e.type) {
      case "TSTypeCastExpression":
        return this.isAssignable(e.expression, t);
      case "TSParameterProperty":
        return !0;
      default:
        return super.isAssignable(e, t);
    }
  }
  toAssignable(e, t = !1) {
    switch (e.type) {
      case "TSTypeCastExpression":
        return super.toAssignable(this.typeCastToParameter(e), t);
      case "TSParameterProperty":
        return super.toAssignable(e, t);
      case "ParenthesizedExpression":
        return this.toAssignableParenthesizedExpression(e, t);
      case "TSAsExpression":
      case "TSNonNullExpression":
      case "TSTypeAssertion":
        return e.expression = this.toAssignable(e.expression, t), e;
      default:
        return super.toAssignable(e, t);
    }
  }
  toAssignableParenthesizedExpression(e, t) {
    switch (e.expression.type) {
      case "TSAsExpression":
      case "TSNonNullExpression":
      case "TSTypeAssertion":
      case "ParenthesizedExpression":
        return e.expression = this.toAssignable(e.expression, t), e;
      default:
        return super.toAssignable(e, t);
    }
  }
  checkLVal(e, t, ...s) {
    var i;
    switch (e.type) {
      case "TSTypeCastExpression":
        return;
      case "TSParameterProperty":
        this.checkLVal(e.parameter, "parameter property", ...s);
        return;
      case "TSAsExpression":
      case "TSTypeAssertion":
        if (!s[0] && t !== "parenthesized expression" && !((i = e.extra) != null && i.parenthesized)) {
          this.raise(e.start, I.InvalidLhs, t);
          break;
        }
        this.checkLVal(e.expression, "parenthesized expression", ...s);
        return;
      case "TSNonNullExpression":
        this.checkLVal(e.expression, t, ...s);
        return;
      default:
        super.checkLVal(e, t, ...s);
        return;
    }
  }
  parseBindingAtom() {
    switch (this.state.type) {
      case 72:
        return this.parseIdentifier(!0);
      default:
        return super.parseBindingAtom();
    }
  }
  parseMaybeDecoratorArguments(e) {
    if (this.match(43)) {
      const t = this.tsParseTypeArguments();
      if (this.match(10)) {
        const s = super.parseMaybeDecoratorArguments(e);
        return s.typeParameters = t, s;
      }
      this.unexpected(this.state.start, 10);
    }
    return super.parseMaybeDecoratorArguments(e);
  }
  checkCommaAfterRest(e) {
    this.state.isAmbientContext && this.match(12) && this.lookaheadCharCode() === e ? this.next() : super.checkCommaAfterRest(e);
  }
  isClassMethod() {
    return this.match(43) || super.isClassMethod();
  }
  isClassProperty() {
    return this.match(33) || this.match(14) || super.isClassProperty();
  }
  parseMaybeDefault(...e) {
    const t = super.parseMaybeDefault(...e);
    return t.type === "AssignmentPattern" && t.typeAnnotation && t.right.start < t.typeAnnotation.start && this.raise(t.typeAnnotation.start, ne.TypeAnnotationAfterAssign), t;
  }
  getTokenFromCode(e) {
    if (this.state.inType) {
      if (e === 62)
        return this.finishOp(44, 1);
      if (e === 60)
        return this.finishOp(43, 1);
    }
    return super.getTokenFromCode(e);
  }
  reScan_lt_gt() {
    const {
      type: e
    } = this.state;
    e === 43 ? (this.state.pos -= 1, this.readToken_lt()) : e === 44 && (this.state.pos -= 1, this.readToken_gt());
  }
  toAssignableList(e) {
    for (let t = 0; t < e.length; t++) {
      const s = e[t];
      if (!!s)
        switch (s.type) {
          case "TSTypeCastExpression":
            e[t] = this.typeCastToParameter(s);
            break;
          case "TSAsExpression":
          case "TSTypeAssertion":
            this.state.maybeInArrowParameters ? this.raise(s.start, ne.UnexpectedTypeCastInParameter) : e[t] = this.typeCastToParameter(s);
            break;
        }
    }
    return super.toAssignableList(...arguments);
  }
  typeCastToParameter(e) {
    return e.expression.typeAnnotation = e.typeAnnotation, this.resetEndLocation(e.expression, e.typeAnnotation.end, e.typeAnnotation.loc.end), e.expression;
  }
  shouldParseArrow(e) {
    return this.match(14) ? e.every((t) => this.isAssignable(t, !0)) : super.shouldParseArrow(e);
  }
  shouldParseAsyncArrow() {
    return this.match(14) || super.shouldParseAsyncArrow();
  }
  canHaveLeadingDecorator() {
    return super.canHaveLeadingDecorator() || this.isAbstractClass();
  }
  jsxParseOpeningElementAfterName(e) {
    if (this.match(43)) {
      const t = this.tsTryParseAndCatch(() => this.tsParseTypeArguments());
      t && (e.typeParameters = t);
    }
    return super.jsxParseOpeningElementAfterName(e);
  }
  getGetterSetterExpectedParamCount(e) {
    const t = super.getGetterSetterExpectedParamCount(e), i = this.getObjectOrClassMethodParams(e)[0];
    return i && this.isThisParam(i) ? t + 1 : t;
  }
  parseCatchClauseParam() {
    const e = super.parseCatchClauseParam(), t = this.tsTryParseTypeAnnotation();
    return t && (e.typeAnnotation = t, this.resetEndLocation(e)), e;
  }
  tsInAmbientContext(e) {
    const t = this.state.isAmbientContext;
    this.state.isAmbientContext = !0;
    try {
      return e();
    } finally {
      this.state.isAmbientContext = t;
    }
  }
  parseClass(e, ...t) {
    const s = this.state.inAbstractClass;
    this.state.inAbstractClass = !!e.abstract;
    try {
      return super.parseClass(e, ...t);
    } finally {
      this.state.inAbstractClass = s;
    }
  }
  tsParseAbstractDeclaration(e) {
    if (this.match(74))
      return e.abstract = !0, this.parseClass(e, !0, !1);
    if (this.isContextual(119)) {
      if (!this.hasFollowingLineBreak())
        return e.abstract = !0, this.raise(e.start, ne.NonClassMethodPropertyHasAbstractModifer), this.next(), this.tsParseInterfaceDeclaration(e);
    } else
      this.unexpected(null, 74);
  }
  parseMethod(...e) {
    const t = super.parseMethod(...e);
    if (t.abstract && (this.hasPlugin("estree") ? !!t.value.body : !!t.body)) {
      const {
        key: i
      } = t;
      this.raise(t.start, ne.AbstractMethodHasImplementation, i.type === "Identifier" && !t.computed ? i.name : `[${this.input.slice(i.start, i.end)}]`);
    }
    return t;
  }
  tsParseTypeParameterName() {
    return this.parseIdentifier().name;
  }
  shouldParseAsAmbientContext() {
    return !!this.getPluginOption("typescript", "dts");
  }
  parse() {
    return this.shouldParseAsAmbientContext() && (this.state.isAmbientContext = !0), super.parse();
  }
  getExpression() {
    return this.shouldParseAsAmbientContext() && (this.state.isAmbientContext = !0), super.getExpression();
  }
  parseExportSpecifier(e, t, s, i) {
    return !t && i ? (this.parseTypeOnlyImportExportSpecifier(e, !1, s), this.finishNode(e, "ExportSpecifier")) : (e.exportKind = "value", super.parseExportSpecifier(e, t, s, i));
  }
  parseImportSpecifier(e, t, s, i) {
    return !t && i ? (this.parseTypeOnlyImportExportSpecifier(e, !0, s), this.finishNode(e, "ImportSpecifier")) : (e.importKind = "value", super.parseImportSpecifier(e, t, s, i));
  }
  parseTypeOnlyImportExportSpecifier(e, t, s) {
    const i = t ? "imported" : "local", n = t ? "local" : "exported";
    let a = e[i], o, l = !1, u = !0;
    const c = a.start;
    if (this.isContextual(87)) {
      const f = this.parseIdentifier();
      if (this.isContextual(87)) {
        const p = this.parseIdentifier();
        er(this.state.type) ? (l = !0, a = f, o = this.parseIdentifier(), u = !1) : (o = p, u = !1);
      } else
        er(this.state.type) ? (u = !1, o = this.parseIdentifier()) : (l = !0, a = f);
    } else
      er(this.state.type) && (l = !0, a = this.parseIdentifier());
    l && s && this.raise(c, t ? ne.TypeModifierIsUsedInTypeImports : ne.TypeModifierIsUsedInTypeExports), e[i] = a, e[n] = o;
    const h = t ? "importKind" : "exportKind";
    e[h] = l ? "type" : "value", u && this.eatContextual(87) && (e[n] = t ? this.parseIdentifier() : this.parseModuleExportName()), e[n] || (e[n] = tr(e[i])), t && this.checkLVal(e[n], "import specifier", yt);
  }
};
const Y1 = Os({
  ClassNameIsRequired: "A class name is required."
}, Zt.SyntaxError);
var J1 = (r) => class extends r {
  parsePlaceholder(e) {
    if (this.match(134)) {
      const t = this.startNode();
      return this.next(), this.assertNoSpace("Unexpected space in placeholder."), t.name = super.parseIdentifier(!0), this.assertNoSpace("Unexpected space in placeholder."), this.expect(134), this.finishPlaceholder(t, e);
    }
  }
  finishPlaceholder(e, t) {
    const s = !!(e.expectedNode && e.type === "Placeholder");
    return e.expectedNode = t, s ? e : this.finishNode(e, "Placeholder");
  }
  getTokenFromCode(e) {
    return e === 37 && this.input.charCodeAt(this.state.pos + 1) === 37 ? this.finishOp(134, 2) : super.getTokenFromCode(...arguments);
  }
  parseExprAtom() {
    return this.parsePlaceholder("Expression") || super.parseExprAtom(...arguments);
  }
  parseIdentifier() {
    return this.parsePlaceholder("Identifier") || super.parseIdentifier(...arguments);
  }
  checkReservedWord(e) {
    e !== void 0 && super.checkReservedWord(...arguments);
  }
  parseBindingAtom() {
    return this.parsePlaceholder("Pattern") || super.parseBindingAtom(...arguments);
  }
  checkLVal(e) {
    e.type !== "Placeholder" && super.checkLVal(...arguments);
  }
  toAssignable(e) {
    return e && e.type === "Placeholder" && e.expectedNode === "Expression" ? (e.expectedNode = "Pattern", e) : super.toAssignable(...arguments);
  }
  isLet(e) {
    return super.isLet(e) ? !0 : !this.isContextual(93) || e ? !1 : this.lookahead().type === 134;
  }
  verifyBreakContinue(e) {
    e.label && e.label.type === "Placeholder" || super.verifyBreakContinue(...arguments);
  }
  parseExpressionStatement(e, t) {
    if (t.type !== "Placeholder" || t.extra && t.extra.parenthesized)
      return super.parseExpressionStatement(...arguments);
    if (this.match(14)) {
      const s = e;
      return s.label = this.finishPlaceholder(t, "Identifier"), this.next(), s.body = this.parseStatement("label"), this.finishNode(s, "LabeledStatement");
    }
    return this.semicolon(), e.name = t.name, this.finishPlaceholder(e, "Statement");
  }
  parseBlock() {
    return this.parsePlaceholder("BlockStatement") || super.parseBlock(...arguments);
  }
  parseFunctionId() {
    return this.parsePlaceholder("Identifier") || super.parseFunctionId(...arguments);
  }
  parseClass(e, t, s) {
    const i = t ? "ClassDeclaration" : "ClassExpression";
    this.next(), this.takeDecorators(e);
    const n = this.state.strict, a = this.parsePlaceholder("Identifier");
    if (a)
      if (this.match(75) || this.match(134) || this.match(5))
        e.id = a;
      else {
        if (s || !t)
          return e.id = null, e.body = this.finishPlaceholder(a, "ClassBody"), this.finishNode(e, i);
        this.unexpected(null, Y1.ClassNameIsRequired);
      }
    else
      this.parseClassId(e, t, s);
    return this.parseClassSuper(e), e.body = this.parsePlaceholder("ClassBody") || this.parseClassBody(!!e.superClass, n), this.finishNode(e, i);
  }
  parseExport(e) {
    const t = this.parsePlaceholder("Identifier");
    if (!t)
      return super.parseExport(...arguments);
    if (!this.isContextual(91) && !this.match(12))
      return e.specifiers = [], e.source = null, e.declaration = this.finishPlaceholder(t, "Declaration"), this.finishNode(e, "ExportNamedDeclaration");
    this.expectPlugin("exportDefaultFrom");
    const s = this.startNode();
    return s.exported = t, e.specifiers = [this.finishNode(s, "ExportDefaultSpecifier")], super.parseExport(e);
  }
  isExportDefaultSpecifier() {
    if (this.match(59)) {
      const e = this.nextTokenStart();
      if (this.isUnparsedContextual(e, "from") && this.input.startsWith(Tr(134), this.nextTokenStartSince(e + 4)))
        return !0;
    }
    return super.isExportDefaultSpecifier();
  }
  maybeParseExportDefaultSpecifier(e) {
    return e.specifiers && e.specifiers.length > 0 ? !0 : super.maybeParseExportDefaultSpecifier(...arguments);
  }
  checkExport(e) {
    const {
      specifiers: t
    } = e;
    t != null && t.length && (e.specifiers = t.filter((s) => s.exported.type === "Placeholder")), super.checkExport(e), e.specifiers = t;
  }
  parseImport(e) {
    const t = this.parsePlaceholder("Identifier");
    if (!t)
      return super.parseImport(...arguments);
    if (e.specifiers = [], !this.isContextual(91) && !this.match(12))
      return e.source = this.finishPlaceholder(t, "StringLiteral"), this.semicolon(), this.finishNode(e, "ImportDeclaration");
    const s = this.startNodeAtNode(t);
    return s.local = t, this.finishNode(s, "ImportDefaultSpecifier"), e.specifiers.push(s), this.eat(12) && (this.maybeParseStarImportSpecifier(e) || this.parseNamedImportSpecifiers(e)), this.expectContextual(91), e.source = this.parseImportSource(), this.semicolon(), this.finishNode(e, "ImportDeclaration");
  }
  parseImportSource() {
    return this.parsePlaceholder("StringLiteral") || super.parseImportSource(...arguments);
  }
}, Q1 = (r) => class extends r {
  parseV8Intrinsic() {
    if (this.match(48)) {
      const e = this.state.start, t = this.startNode();
      if (this.next(), Se(this.state.type)) {
        const s = this.parseIdentifierName(this.state.start), i = this.createIdentifier(t, s);
        if (i.type = "V8IntrinsicIdentifier", this.match(10))
          return i;
      }
      this.unexpected(e);
    }
  }
  parseExprAtom() {
    return this.parseV8Intrinsic() || super.parseExprAtom(...arguments);
  }
};
function Ye(r, e) {
  return r.some((t) => Array.isArray(t) ? t[0] === e : t === e);
}
function Qr(r, e, t) {
  const s = r.find((i) => Array.isArray(i) ? i[0] === e : i === e);
  return s && Array.isArray(s) ? s[1][t] : null;
}
const Oc = ["minimal", "fsharp", "hack", "smart"], kc = ["^", "%", "#"], Mc = ["hash", "bar"];
function X1(r) {
  if (Ye(r, "decorators")) {
    if (Ye(r, "decorators-legacy"))
      throw new Error("Cannot use the decorators and decorators-legacy plugin together");
    const e = Qr(r, "decorators", "decoratorsBeforeExport");
    if (e == null)
      throw new Error("The 'decorators' plugin requires a 'decoratorsBeforeExport' option, whose value must be a boolean. If you are migrating from Babylon/Babel 6 or want to use the old decorators proposal, you should use the 'decorators-legacy' plugin instead of 'decorators'.");
    if (typeof e != "boolean")
      throw new Error("'decoratorsBeforeExport' must be a boolean.");
  }
  if (Ye(r, "flow") && Ye(r, "typescript"))
    throw new Error("Cannot combine flow and typescript plugins.");
  if (Ye(r, "placeholders") && Ye(r, "v8intrinsic"))
    throw new Error("Cannot combine placeholders and v8intrinsic plugins.");
  if (Ye(r, "pipelineOperator")) {
    const e = Qr(r, "pipelineOperator", "proposal");
    if (!Oc.includes(e)) {
      const s = Oc.map((i) => `"${i}"`).join(", ");
      throw new Error(`"pipelineOperator" requires "proposal" option whose value must be one of: ${s}.`);
    }
    const t = Ye(r, "recordAndTuple") && Qr(r, "recordAndTuple", "syntaxType") === "hash";
    if (e === "hack") {
      if (Ye(r, "placeholders"))
        throw new Error("Cannot combine placeholders plugin and Hack-style pipes.");
      if (Ye(r, "v8intrinsic"))
        throw new Error("Cannot combine v8intrinsic plugin and Hack-style pipes.");
      const s = Qr(r, "pipelineOperator", "topicToken");
      if (!kc.includes(s)) {
        const i = kc.map((n) => `"${n}"`).join(", ");
        throw new Error(`"pipelineOperator" in "proposal": "hack" mode also requires a "topicToken" option whose value must be one of: ${i}.`);
      }
      if (s === "#" && t)
        throw new Error('Plugin conflict between `["pipelineOperator", { proposal: "hack", topicToken: "#" }]` and `["recordAndtuple", { syntaxType: "hash"}]`.');
    } else if (e === "smart" && t)
      throw new Error('Plugin conflict between `["pipelineOperator", { proposal: "smart" }]` and `["recordAndtuple", { syntaxType: "hash"}]`.');
  }
  if (Ye(r, "moduleAttributes")) {
    if (Ye(r, "importAssertions"))
      throw new Error("Cannot combine importAssertions and moduleAttributes plugins.");
    if (Qr(r, "moduleAttributes", "version") !== "may-2020")
      throw new Error("The 'moduleAttributes' plugin requires a 'version' option, representing the last proposal update. Currently, the only supported value is 'may-2020'.");
  }
  if (Ye(r, "recordAndTuple") && !Mc.includes(Qr(r, "recordAndTuple", "syntaxType")))
    throw new Error("'recordAndTuple' requires 'syntaxType' option whose value should be one of: " + Mc.map((e) => `'${e}'`).join(", "));
  if (Ye(r, "asyncDoExpressions") && !Ye(r, "doExpressions")) {
    const e = new Error("'asyncDoExpressions' requires 'doExpressions', please add 'doExpressions' to parser plugins.");
    throw e.missingPlugins = "doExpressions", e;
  }
}
const fp = {
  estree: F0,
  jsx: V1,
  flow: U1,
  typescript: G1,
  v8intrinsic: Q1,
  placeholders: J1
}, Z1 = Object.keys(fp), Lc = {
  sourceType: "script",
  sourceFilename: void 0,
  startColumn: 0,
  startLine: 1,
  allowAwaitOutsideFunction: !1,
  allowReturnOutsideFunction: !1,
  allowImportExportEverywhere: !1,
  allowSuperOutsideMethod: !1,
  allowUndeclaredExports: !1,
  plugins: [],
  strictMode: null,
  ranges: !1,
  tokens: !1,
  createParenthesizedExpressions: !1,
  errorRecovery: !1,
  attachComment: !0
};
function ev(r) {
  const e = {};
  for (const t of Object.keys(Lc))
    e[t] = r && r[t] != null ? r[t] : Lc[t];
  return e;
}
const ko = (r) => r.type === "ParenthesizedExpression" ? ko(r.expression) : r;
class tv extends M1 {
  toAssignable(e, t = !1) {
    var s, i;
    let n;
    switch ((e.type === "ParenthesizedExpression" || (s = e.extra) != null && s.parenthesized) && (n = ko(e), t ? n.type === "Identifier" ? this.expressionScope.recordParenthesizedIdentifierError(e.start, I.InvalidParenthesizedAssignment) : n.type !== "MemberExpression" && this.raise(e.start, I.InvalidParenthesizedAssignment) : this.raise(e.start, I.InvalidParenthesizedAssignment)), e.type) {
      case "Identifier":
      case "ObjectPattern":
      case "ArrayPattern":
      case "AssignmentPattern":
      case "RestElement":
        break;
      case "ObjectExpression":
        e.type = "ObjectPattern";
        for (let o = 0, l = e.properties.length, u = l - 1; o < l; o++) {
          var a;
          const c = e.properties[o], h = o === u;
          this.toAssignableObjectExpressionProp(c, h, t), h && c.type === "RestElement" && (a = e.extra) != null && a.trailingComma && this.raiseRestNotLast(e.extra.trailingComma);
        }
        break;
      case "ObjectProperty":
        this.toAssignable(e.value, t);
        break;
      case "SpreadElement": {
        this.checkToRestConversion(e), e.type = "RestElement";
        const o = e.argument;
        this.toAssignable(o, t);
        break;
      }
      case "ArrayExpression":
        e.type = "ArrayPattern", this.toAssignableList(e.elements, (i = e.extra) == null ? void 0 : i.trailingComma, t);
        break;
      case "AssignmentExpression":
        e.operator !== "=" && this.raise(e.left.end, I.MissingEqInAssignment), e.type = "AssignmentPattern", delete e.operator, this.toAssignable(e.left, t);
        break;
      case "ParenthesizedExpression":
        this.toAssignable(n, t);
        break;
    }
    return e;
  }
  toAssignableObjectExpressionProp(e, t, s) {
    if (e.type === "ObjectMethod") {
      const i = e.kind === "get" || e.kind === "set" ? I.PatternHasAccessor : I.PatternHasMethod;
      this.raise(e.key.start, i);
    } else
      e.type === "SpreadElement" && !t ? this.raiseRestNotLast(e.start) : this.toAssignable(e, s);
  }
  toAssignableList(e, t, s) {
    let i = e.length;
    if (i) {
      const n = e[i - 1];
      if ((n == null ? void 0 : n.type) === "RestElement")
        --i;
      else if ((n == null ? void 0 : n.type) === "SpreadElement") {
        n.type = "RestElement";
        let a = n.argument;
        this.toAssignable(a, s), a = ko(a), a.type !== "Identifier" && a.type !== "MemberExpression" && a.type !== "ArrayPattern" && a.type !== "ObjectPattern" && this.unexpected(a.start), t && this.raiseTrailingCommaAfterRest(t), --i;
      }
    }
    for (let n = 0; n < i; n++) {
      const a = e[n];
      a && (this.toAssignable(a, s), a.type === "RestElement" && this.raiseRestNotLast(a.start));
    }
    return e;
  }
  isAssignable(e, t) {
    switch (e.type) {
      case "Identifier":
      case "ObjectPattern":
      case "ArrayPattern":
      case "AssignmentPattern":
      case "RestElement":
        return !0;
      case "ObjectExpression": {
        const s = e.properties.length - 1;
        return e.properties.every((i, n) => i.type !== "ObjectMethod" && (n === s || i.type !== "SpreadElement") && this.isAssignable(i));
      }
      case "ObjectProperty":
        return this.isAssignable(e.value);
      case "SpreadElement":
        return this.isAssignable(e.argument);
      case "ArrayExpression":
        return e.elements.every((s) => s === null || this.isAssignable(s));
      case "AssignmentExpression":
        return e.operator === "=";
      case "ParenthesizedExpression":
        return this.isAssignable(e.expression);
      case "MemberExpression":
      case "OptionalMemberExpression":
        return !t;
      default:
        return !1;
    }
  }
  toReferencedList(e, t) {
    return e;
  }
  toReferencedListDeep(e, t) {
    this.toReferencedList(e, t);
    for (const s of e)
      (s == null ? void 0 : s.type) === "ArrayExpression" && this.toReferencedListDeep(s.elements);
  }
  parseSpread(e, t) {
    const s = this.startNode();
    return this.next(), s.argument = this.parseMaybeAssignAllowIn(e, void 0, t), this.finishNode(s, "SpreadElement");
  }
  parseRestBinding() {
    const e = this.startNode();
    return this.next(), e.argument = this.parseBindingAtom(), this.finishNode(e, "RestElement");
  }
  parseBindingAtom() {
    switch (this.state.type) {
      case 0: {
        const e = this.startNode();
        return this.next(), e.elements = this.parseBindingList(3, 93, !0), this.finishNode(e, "ArrayPattern");
      }
      case 5:
        return this.parseObjectLike(8, !0);
    }
    return this.parseIdentifier();
  }
  parseBindingList(e, t, s, i) {
    const n = [];
    let a = !0;
    for (; !this.eat(e); )
      if (a ? a = !1 : this.expect(12), s && this.match(12))
        n.push(null);
      else {
        if (this.eat(e))
          break;
        if (this.match(21)) {
          n.push(this.parseAssignableListItemTypes(this.parseRestBinding())), this.checkCommaAfterRest(t), this.expect(e);
          break;
        } else {
          const o = [];
          for (this.match(24) && this.hasPlugin("decorators") && this.raise(this.state.start, I.UnsupportedParameterDecorator); this.match(24); )
            o.push(this.parseDecorator());
          n.push(this.parseAssignableListItem(i, o));
        }
      }
    return n;
  }
  parseBindingRestProperty(e) {
    return this.next(), e.argument = this.parseIdentifier(), this.checkCommaAfterRest(125), this.finishNode(e, "RestElement");
  }
  parseBindingProperty() {
    const e = this.startNode(), {
      type: t,
      start: s,
      startLoc: i
    } = this.state;
    return t === 21 ? this.parseBindingRestProperty(e) : (this.parsePropertyName(e), e.method = !1, this.parseObjPropValue(e, s, i, !1, !1, !0, !1), e);
  }
  parseAssignableListItem(e, t) {
    const s = this.parseMaybeDefault();
    this.parseAssignableListItemTypes(s);
    const i = this.parseMaybeDefault(s.start, s.loc.start, s);
    return t.length && (s.decorators = t), i;
  }
  parseAssignableListItemTypes(e) {
    return e;
  }
  parseMaybeDefault(e, t, s) {
    var i, n, a;
    if (t = (i = t) != null ? i : this.state.startLoc, e = (n = e) != null ? n : this.state.start, s = (a = s) != null ? a : this.parseBindingAtom(), !this.eat(27))
      return s;
    const o = this.startNodeAt(e, t);
    return o.left = s, o.right = this.parseMaybeAssignAllowIn(), this.finishNode(o, "AssignmentPattern");
  }
  checkLVal(e, t, s = qs, i, n, a = !1) {
    switch (e.type) {
      case "Identifier": {
        const {
          name: o
        } = e;
        this.state.strict && (a ? Qf(o, this.inModule) : Jf(o)) && this.raise(e.start, s === qs ? I.StrictEvalArguments : I.StrictEvalArgumentsBinding, o), i && (i.has(o) ? this.raise(e.start, I.ParamDupe) : i.add(o)), n && o === "let" && this.raise(e.start, I.LetInLexicalBinding), s & qs || this.scope.declareName(o, s, e.start);
        break;
      }
      case "MemberExpression":
        s !== qs && this.raise(e.start, I.InvalidPropertyBindingPattern);
        break;
      case "ObjectPattern":
        for (let o of e.properties) {
          if (this.isObjectProperty(o))
            o = o.value;
          else if (this.isObjectMethod(o))
            continue;
          this.checkLVal(o, "object destructuring pattern", s, i, n);
        }
        break;
      case "ArrayPattern":
        for (const o of e.elements)
          o && this.checkLVal(o, "array destructuring pattern", s, i, n);
        break;
      case "AssignmentPattern":
        this.checkLVal(e.left, "assignment pattern", s, i);
        break;
      case "RestElement":
        this.checkLVal(e.argument, "rest element", s, i);
        break;
      case "ParenthesizedExpression":
        this.checkLVal(e.expression, "parenthesized expression", s, i);
        break;
      default:
        this.raise(e.start, s === qs ? I.InvalidLhs : I.InvalidLhsBinding, t);
    }
  }
  checkToRestConversion(e) {
    e.argument.type !== "Identifier" && e.argument.type !== "MemberExpression" && this.raise(e.argument.start, I.InvalidRestAssignmentPattern);
  }
  checkCommaAfterRest(e) {
    this.match(12) && (this.lookaheadCharCode() === e ? this.raiseTrailingCommaAfterRest(this.state.start) : this.raiseRestNotLast(this.state.start));
  }
  raiseRestNotLast(e) {
    throw this.raise(e, I.ElementAfterRest);
  }
  raiseTrailingCommaAfterRest(e) {
    this.raise(e, I.RestTrailingComma);
  }
}
const Dc = /* @__PURE__ */ new Map([["ArrowFunctionExpression", "arrow function"], ["AssignmentExpression", "assignment"], ["ConditionalExpression", "conditional"], ["YieldExpression", "yield"]]);
class rv extends tv {
  checkProto(e, t, s, i) {
    if (e.type === "SpreadElement" || this.isObjectMethod(e) || e.computed || e.shorthand)
      return;
    const n = e.key;
    if ((n.type === "Identifier" ? n.name : n.value) === "__proto__") {
      if (t) {
        this.raise(n.start, I.RecordNoProto);
        return;
      }
      s.used && (i ? i.doubleProto === -1 && (i.doubleProto = n.start) : this.raise(n.start, I.DuplicateProto)), s.used = !0;
    }
  }
  shouldExitDescending(e, t) {
    return e.type === "ArrowFunctionExpression" && e.start === t;
  }
  getExpression() {
    this.enterInitialScopes(), this.nextToken();
    const e = this.parseExpression();
    return this.match(129) || this.unexpected(), this.finalizeRemainingComments(), e.comments = this.state.comments, e.errors = this.state.errors, this.options.tokens && (e.tokens = this.tokens), e;
  }
  parseExpression(e, t) {
    return e ? this.disallowInAnd(() => this.parseExpressionBase(t)) : this.allowInAnd(() => this.parseExpressionBase(t));
  }
  parseExpressionBase(e) {
    const t = this.state.start, s = this.state.startLoc, i = this.parseMaybeAssign(e);
    if (this.match(12)) {
      const n = this.startNodeAt(t, s);
      for (n.expressions = [i]; this.eat(12); )
        n.expressions.push(this.parseMaybeAssign(e));
      return this.toReferencedList(n.expressions), this.finishNode(n, "SequenceExpression");
    }
    return i;
  }
  parseMaybeAssignDisallowIn(e, t) {
    return this.disallowInAnd(() => this.parseMaybeAssign(e, t));
  }
  parseMaybeAssignAllowIn(e, t) {
    return this.allowInAnd(() => this.parseMaybeAssign(e, t));
  }
  setOptionalParametersError(e, t) {
    var s;
    e.optionalParameters = (s = t == null ? void 0 : t.pos) != null ? s : this.state.start;
  }
  parseMaybeAssign(e, t) {
    const s = this.state.start, i = this.state.startLoc;
    if (this.isContextual(99) && this.prodParam.hasYield) {
      let l = this.parseYield();
      return t && (l = t.call(this, l, s, i)), l;
    }
    let n;
    e ? n = !1 : (e = new fn(), n = !0);
    const {
      type: a
    } = this.state;
    (a === 10 || Se(a)) && (this.state.potentialArrowAt = this.state.start);
    let o = this.parseMaybeConditional(e);
    if (t && (o = t.call(this, o, s, i)), q0(this.state.type)) {
      const l = this.startNodeAt(s, i), u = this.state.value;
      return l.operator = u, this.match(27) ? (l.left = this.toAssignable(o, !0), e.doubleProto >= s && (e.doubleProto = -1), e.shorthandAssign >= s && (e.shorthandAssign = -1)) : l.left = o, this.checkLVal(o, "assignment expression"), this.next(), l.right = this.parseMaybeAssign(), this.finishNode(l, "AssignmentExpression");
    } else
      n && this.checkExpressionErrors(e, !0);
    return o;
  }
  parseMaybeConditional(e) {
    const t = this.state.start, s = this.state.startLoc, i = this.state.potentialArrowAt, n = this.parseExprOps(e);
    return this.shouldExitDescending(n, i) ? n : this.parseConditional(n, t, s, e);
  }
  parseConditional(e, t, s, i) {
    if (this.eat(17)) {
      const n = this.startNodeAt(t, s);
      return n.test = e, n.consequent = this.parseMaybeAssignAllowIn(), this.expect(14), n.alternate = this.parseMaybeAssign(), this.finishNode(n, "ConditionalExpression");
    }
    return e;
  }
  parseMaybeUnaryOrPrivate(e) {
    return this.match(128) ? this.parsePrivateName() : this.parseMaybeUnary(e);
  }
  parseExprOps(e) {
    const t = this.state.start, s = this.state.startLoc, i = this.state.potentialArrowAt, n = this.parseMaybeUnaryOrPrivate(e);
    return this.shouldExitDescending(n, i) ? n : this.parseExprOp(n, t, s, -1);
  }
  parseExprOp(e, t, s, i) {
    if (this.isPrivateName(e)) {
      const a = this.getPrivateNameSV(e), {
        start: o
      } = e;
      (i >= on(52) || !this.prodParam.hasIn || !this.match(52)) && this.raise(o, I.PrivateInExpectedIn, a), this.classScope.usePrivateName(a, o);
    }
    const n = this.state.type;
    if (z0(n) && (this.prodParam.hasIn || !this.match(52))) {
      let a = on(n);
      if (a > i) {
        if (n === 35) {
          if (this.expectPlugin("pipelineOperator"), this.state.inFSharpPipelineDirectBody)
            return e;
          this.checkPipelineAtInfixOperator(e, t);
        }
        const o = this.startNodeAt(t, s);
        o.left = e, o.operator = this.state.value;
        const l = n === 37 || n === 38, u = n === 36;
        if (u && (a = on(38)), this.next(), n === 35 && this.getPluginOption("pipelineOperator", "proposal") === "minimal" && this.state.type === 90 && this.prodParam.hasAwait)
          throw this.raise(this.state.start, I.UnexpectedAwaitAfterPipelineBody);
        o.right = this.parseExprOpRightExpr(n, a), this.finishNode(o, l || u ? "LogicalExpression" : "BinaryExpression");
        const c = this.state.type;
        if (u && (c === 37 || c === 38) || l && c === 36)
          throw this.raise(this.state.start, I.MixingCoalesceWithLogical);
        return this.parseExprOp(o, t, s, i);
      }
    }
    return e;
  }
  parseExprOpRightExpr(e, t) {
    const s = this.state.start, i = this.state.startLoc;
    switch (e) {
      case 35:
        switch (this.getPluginOption("pipelineOperator", "proposal")) {
          case "hack":
            return this.withTopicBindingContext(() => this.parseHackPipeBody());
          case "smart":
            return this.withTopicBindingContext(() => {
              if (this.prodParam.hasYield && this.isContextual(99))
                throw this.raise(this.state.start, I.PipeBodyIsTighter, this.state.value);
              return this.parseSmartPipelineBodyInStyle(this.parseExprOpBaseRightExpr(e, t), s, i);
            });
          case "fsharp":
            return this.withSoloAwaitPermittingContext(() => this.parseFSharpPipelineBody(t));
        }
      default:
        return this.parseExprOpBaseRightExpr(e, t);
    }
  }
  parseExprOpBaseRightExpr(e, t) {
    const s = this.state.start, i = this.state.startLoc;
    return this.parseExprOp(this.parseMaybeUnaryOrPrivate(), s, i, Y0(e) ? t - 1 : t);
  }
  parseHackPipeBody() {
    var e;
    const {
      start: t
    } = this.state, s = this.parseMaybeAssign();
    return Dc.has(s.type) && !((e = s.extra) != null && e.parenthesized) && this.raise(t, I.PipeUnparenthesizedBody, Dc.get(s.type)), this.topicReferenceWasUsedInCurrentContext() || this.raise(t, I.PipeTopicUnused), s;
  }
  checkExponentialAfterUnary(e) {
    this.match(51) && this.raise(e.argument.start, I.UnexpectedTokenUnaryExponentiation);
  }
  parseMaybeUnary(e, t) {
    const s = this.state.start, i = this.state.startLoc, n = this.isContextual(90);
    if (n && this.isAwaitAllowed()) {
      this.next();
      const u = this.parseAwait(s, i);
      return t || this.checkExponentialAfterUnary(u), u;
    }
    const a = this.match(32), o = this.startNode();
    if (H0(this.state.type)) {
      o.operator = this.state.value, o.prefix = !0, this.match(66) && this.expectPlugin("throwExpressions");
      const u = this.match(83);
      if (this.next(), o.argument = this.parseMaybeUnary(null, !0), this.checkExpressionErrors(e, !0), this.state.strict && u) {
        const c = o.argument;
        c.type === "Identifier" ? this.raise(o.start, I.StrictDelete) : this.hasPropertyAsPrivateName(c) && this.raise(o.start, I.DeletePrivateField);
      }
      if (!a)
        return t || this.checkExponentialAfterUnary(o), this.finishNode(o, "UnaryExpression");
    }
    const l = this.parseUpdate(o, a, e);
    if (n) {
      const {
        type: u
      } = this.state;
      if ((this.hasPlugin("v8intrinsic") ? Pc(u) : Pc(u) && !this.match(48)) && !this.isAmbiguousAwait())
        return this.raiseOverwrite(s, I.AwaitNotInAsyncContext), this.parseAwait(s, i);
    }
    return l;
  }
  parseUpdate(e, t, s) {
    if (t)
      return this.checkLVal(e.argument, "prefix operation"), this.finishNode(e, "UpdateExpression");
    const i = this.state.start, n = this.state.startLoc;
    let a = this.parseExprSubscripts(s);
    if (this.checkExpressionErrors(s, !1))
      return a;
    for (; W0(this.state.type) && !this.canInsertSemicolon(); ) {
      const o = this.startNodeAt(i, n);
      o.operator = this.state.value, o.prefix = !1, o.argument = a, this.checkLVal(a, "postfix operation"), this.next(), a = this.finishNode(o, "UpdateExpression");
    }
    return a;
  }
  parseExprSubscripts(e) {
    const t = this.state.start, s = this.state.startLoc, i = this.state.potentialArrowAt, n = this.parseExprAtom(e);
    return this.shouldExitDescending(n, i) ? n : this.parseSubscripts(n, t, s);
  }
  parseSubscripts(e, t, s, i) {
    const n = {
      optionalChainMember: !1,
      maybeAsyncArrow: this.atPossibleAsyncArrow(e),
      stop: !1
    };
    do
      e = this.parseSubscript(e, t, s, i, n), n.maybeAsyncArrow = !1;
    while (!n.stop);
    return e;
  }
  parseSubscript(e, t, s, i, n) {
    if (!i && this.eat(15))
      return this.parseBind(e, t, s, i, n);
    if (this.match(22))
      return this.parseTaggedTemplateExpression(e, t, s, n);
    let a = !1;
    if (this.match(18)) {
      if (i && this.lookaheadCharCode() === 40)
        return n.stop = !0, e;
      n.optionalChainMember = a = !0, this.next();
    }
    if (!i && this.match(10))
      return this.parseCoverCallAndAsyncArrowHead(e, t, s, n, a);
    {
      const o = this.eat(0);
      return o || a || this.eat(16) ? this.parseMember(e, t, s, n, o, a) : (n.stop = !0, e);
    }
  }
  parseMember(e, t, s, i, n, a) {
    const o = this.startNodeAt(t, s);
    o.object = e, o.computed = n;
    const l = !n && this.match(128) && this.state.value, u = n ? this.parseExpression() : l ? this.parsePrivateName() : this.parseIdentifier(!0);
    return l !== !1 && (o.object.type === "Super" && this.raise(t, I.SuperPrivateField), this.classScope.usePrivateName(l, u.start)), o.property = u, n && this.expect(3), i.optionalChainMember ? (o.optional = a, this.finishNode(o, "OptionalMemberExpression")) : this.finishNode(o, "MemberExpression");
  }
  parseBind(e, t, s, i, n) {
    const a = this.startNodeAt(t, s);
    return a.object = e, a.callee = this.parseNoCallExpr(), n.stop = !0, this.parseSubscripts(this.finishNode(a, "BindExpression"), t, s, i);
  }
  parseCoverCallAndAsyncArrowHead(e, t, s, i, n) {
    const a = this.state.maybeInArrowParameters;
    let o = null;
    this.state.maybeInArrowParameters = !0, this.next();
    let l = this.startNodeAt(t, s);
    return l.callee = e, i.maybeAsyncArrow && (this.expressionScope.enter(C1()), o = new fn()), i.optionalChainMember && (l.optional = n), n ? l.arguments = this.parseCallExpressionArguments(11) : l.arguments = this.parseCallExpressionArguments(11, e.type === "Import", e.type !== "Super", l, o), this.finishCallExpression(l, i.optionalChainMember), i.maybeAsyncArrow && this.shouldParseAsyncArrow() && !n ? (i.stop = !0, this.expressionScope.validateAsPattern(), this.expressionScope.exit(), l = this.parseAsyncArrowFromCallExpression(this.startNodeAt(t, s), l)) : (i.maybeAsyncArrow && (this.checkExpressionErrors(o, !0), this.expressionScope.exit()), this.toReferencedArguments(l)), this.state.maybeInArrowParameters = a, l;
  }
  toReferencedArguments(e, t) {
    this.toReferencedListDeep(e.arguments, t);
  }
  parseTaggedTemplateExpression(e, t, s, i) {
    const n = this.startNodeAt(t, s);
    return n.tag = e, n.quasi = this.parseTemplate(!0), i.optionalChainMember && this.raise(t, I.OptionalChainingNoTemplate), this.finishNode(n, "TaggedTemplateExpression");
  }
  atPossibleAsyncArrow(e) {
    return e.type === "Identifier" && e.name === "async" && this.state.lastTokEnd === e.end && !this.canInsertSemicolon() && e.end - e.start === 5 && e.start === this.state.potentialArrowAt;
  }
  finishCallExpression(e, t) {
    if (e.callee.type === "Import")
      if (e.arguments.length === 2 && (this.hasPlugin("moduleAttributes") || this.expectPlugin("importAssertions")), e.arguments.length === 0 || e.arguments.length > 2)
        this.raise(e.start, I.ImportCallArity, this.hasPlugin("importAssertions") || this.hasPlugin("moduleAttributes") ? "one or two arguments" : "one argument");
      else
        for (const s of e.arguments)
          s.type === "SpreadElement" && this.raise(s.start, I.ImportCallSpreadArgument);
    return this.finishNode(e, t ? "OptionalCallExpression" : "CallExpression");
  }
  parseCallExpressionArguments(e, t, s, i, n) {
    const a = [];
    let o = !0;
    const l = this.state.inFSharpPipelineDirectBody;
    for (this.state.inFSharpPipelineDirectBody = !1; !this.eat(e); ) {
      if (o)
        o = !1;
      else if (this.expect(12), this.match(e)) {
        t && !this.hasPlugin("importAssertions") && !this.hasPlugin("moduleAttributes") && this.raise(this.state.lastTokStart, I.ImportCallArgumentTrailingComma), i && this.addExtra(i, "trailingComma", this.state.lastTokStart), this.next();
        break;
      }
      a.push(this.parseExprListItem(!1, n, s));
    }
    return this.state.inFSharpPipelineDirectBody = l, a;
  }
  shouldParseAsyncArrow() {
    return this.match(19) && !this.canInsertSemicolon();
  }
  parseAsyncArrowFromCallExpression(e, t) {
    var s;
    return this.resetPreviousNodeTrailingComments(t), this.expect(19), this.parseArrowExpression(e, t.arguments, !0, (s = t.extra) == null ? void 0 : s.trailingComma), t.innerComments && bi(e, t.innerComments), t.callee.trailingComments && bi(e, t.callee.trailingComments), e;
  }
  parseNoCallExpr() {
    const e = this.state.start, t = this.state.startLoc;
    return this.parseSubscripts(this.parseExprAtom(), e, t, !0);
  }
  parseExprAtom(e) {
    let t;
    const {
      type: s
    } = this.state;
    switch (s) {
      case 73:
        return this.parseSuper();
      case 77:
        return t = this.startNode(), this.next(), this.match(16) ? this.parseImportMetaProperty(t) : (this.match(10) || this.raise(this.state.lastTokStart, I.UnsupportedImport), this.finishNode(t, "Import"));
      case 72:
        return t = this.startNode(), this.next(), this.finishNode(t, "ThisExpression");
      case 84:
        return this.parseDo(this.startNode(), !1);
      case 50:
      case 29:
        return this.readRegexp(), this.parseRegExpLiteral(this.state.value);
      case 124:
        return this.parseNumericLiteral(this.state.value);
      case 125:
        return this.parseBigIntLiteral(this.state.value);
      case 126:
        return this.parseDecimalLiteral(this.state.value);
      case 123:
        return this.parseStringLiteral(this.state.value);
      case 78:
        return this.parseNullLiteral();
      case 79:
        return this.parseBooleanLiteral(!0);
      case 80:
        return this.parseBooleanLiteral(!1);
      case 10: {
        const i = this.state.potentialArrowAt === this.state.start;
        return this.parseParenAndDistinguishExpression(i);
      }
      case 2:
      case 1:
        return this.parseArrayLike(this.state.type === 2 ? 4 : 3, !1, !0);
      case 0:
        return this.parseArrayLike(3, !0, !1, e);
      case 6:
      case 7:
        return this.parseObjectLike(this.state.type === 6 ? 9 : 8, !1, !0);
      case 5:
        return this.parseObjectLike(8, !1, !1, e);
      case 62:
        return this.parseFunctionOrFunctionSent();
      case 24:
        this.parseDecorators();
      case 74:
        return t = this.startNode(), this.takeDecorators(t), this.parseClass(t, !1);
      case 71:
        return this.parseNewOrNewTarget();
      case 22:
        return this.parseTemplate(!1);
      case 15: {
        t = this.startNode(), this.next(), t.object = null;
        const i = t.callee = this.parseNoCallExpr();
        if (i.type === "MemberExpression")
          return this.finishNode(t, "BindExpression");
        throw this.raise(i.start, I.UnsupportedBind);
      }
      case 128:
        return this.raise(this.state.start, I.PrivateInExpectedIn, this.state.value), this.parsePrivateName();
      case 31:
        return this.parseTopicReferenceThenEqualsSign(48, "%");
      case 30:
        return this.parseTopicReferenceThenEqualsSign(40, "^");
      case 40:
      case 48:
      case 25: {
        const i = this.getPluginOption("pipelineOperator", "proposal");
        if (i)
          return this.parseTopicReference(i);
        throw this.unexpected();
      }
      case 43: {
        const i = this.input.codePointAt(this.nextTokenStart());
        if (br(i) || i === 62) {
          this.expectOnePlugin(["jsx", "flow", "typescript"]);
          break;
        } else
          throw this.unexpected();
      }
      default:
        if (Se(s)) {
          if (this.isContextual(117) && this.lookaheadCharCode() === 123 && !this.hasFollowingLineBreak())
            return this.parseModuleExpression();
          const i = this.state.potentialArrowAt === this.state.start, n = this.state.containsEsc, a = this.parseIdentifier();
          if (!n && a.name === "async" && !this.canInsertSemicolon()) {
            const {
              type: o
            } = this.state;
            if (o === 62)
              return this.resetPreviousNodeTrailingComments(a), this.next(), this.parseFunction(this.startNodeAtNode(a), void 0, !0);
            if (Se(o))
              return this.lookaheadCharCode() === 61 ? this.parseAsyncArrowUnaryFunction(this.startNodeAtNode(a)) : a;
            if (o === 84)
              return this.resetPreviousNodeTrailingComments(a), this.parseDo(this.startNodeAtNode(a), !0);
          }
          return i && this.match(19) && !this.canInsertSemicolon() ? (this.next(), this.parseArrowExpression(this.startNodeAtNode(a), [a], !1)) : a;
        } else
          throw this.unexpected();
    }
  }
  parseTopicReferenceThenEqualsSign(e, t) {
    const s = this.getPluginOption("pipelineOperator", "proposal");
    if (s)
      return this.state.type = e, this.state.value = t, this.state.pos--, this.state.end--, this.state.endLoc.column--, this.parseTopicReference(s);
    throw this.unexpected();
  }
  parseTopicReference(e) {
    const t = this.startNode(), s = this.state.start, i = this.state.type;
    return this.next(), this.finishTopicReference(t, s, e, i);
  }
  finishTopicReference(e, t, s, i) {
    if (this.testTopicReferenceConfiguration(s, t, i)) {
      let n;
      return s === "smart" ? n = "PipelinePrimaryTopicReference" : n = "TopicReference", this.topicReferenceIsAllowedInCurrentContext() || (s === "smart" ? this.raise(t, I.PrimaryTopicNotAllowed) : this.raise(t, I.PipeTopicUnbound)), this.registerTopicReference(), this.finishNode(e, n);
    } else
      throw this.raise(t, I.PipeTopicUnconfiguredToken, Tr(i));
  }
  testTopicReferenceConfiguration(e, t, s) {
    switch (e) {
      case "hack": {
        const i = this.getPluginOption("pipelineOperator", "topicToken");
        return Tr(s) === i;
      }
      case "smart":
        return s === 25;
      default:
        throw this.raise(t, I.PipeTopicRequiresHackPipes);
    }
  }
  parseAsyncArrowUnaryFunction(e) {
    this.prodParam.enter(hn(!0, this.prodParam.hasYield));
    const t = [this.parseIdentifier()];
    return this.prodParam.exit(), this.hasPrecedingLineBreak() && this.raise(this.state.pos, I.LineTerminatorBeforeArrow), this.expect(19), this.parseArrowExpression(e, t, !0), e;
  }
  parseDo(e, t) {
    this.expectPlugin("doExpressions"), t && this.expectPlugin("asyncDoExpressions"), e.async = t, this.next();
    const s = this.state.labels;
    return this.state.labels = [], t ? (this.prodParam.enter(pa), e.body = this.parseBlock(), this.prodParam.exit()) : e.body = this.parseBlock(), this.state.labels = s, this.finishNode(e, "DoExpression");
  }
  parseSuper() {
    const e = this.startNode();
    return this.next(), this.match(10) && !this.scope.allowDirectSuper && !this.options.allowSuperOutsideMethod ? this.raise(e.start, I.SuperNotAllowed) : !this.scope.allowSuper && !this.options.allowSuperOutsideMethod && this.raise(e.start, I.UnexpectedSuper), !this.match(10) && !this.match(0) && !this.match(16) && this.raise(e.start, I.UnsupportedSuper), this.finishNode(e, "Super");
  }
  parsePrivateName() {
    const e = this.startNode(), t = this.startNodeAt(this.state.start + 1, new vi(this.state.curLine, this.state.start + 1 - this.state.lineStart)), s = this.state.value;
    return this.next(), e.id = this.createIdentifier(t, s), this.finishNode(e, "PrivateName");
  }
  parseFunctionOrFunctionSent() {
    const e = this.startNode();
    if (this.next(), this.prodParam.hasYield && this.match(16)) {
      const t = this.createIdentifier(this.startNodeAtNode(e), "function");
      return this.next(), this.match(96) ? this.expectPlugin("functionSent") : this.hasPlugin("functionSent") || this.unexpected(), this.parseMetaProperty(e, t, "sent");
    }
    return this.parseFunction(e);
  }
  parseMetaProperty(e, t, s) {
    e.meta = t;
    const i = this.state.containsEsc;
    return e.property = this.parseIdentifier(!0), (e.property.name !== s || i) && this.raise(e.property.start, I.UnsupportedMetaProperty, t.name, s), this.finishNode(e, "MetaProperty");
  }
  parseImportMetaProperty(e) {
    const t = this.createIdentifier(this.startNodeAtNode(e), "import");
    return this.next(), this.isContextual(94) && (this.inModule || this.raise(t.start, Vf.ImportMetaOutsideModule), this.sawUnambiguousESM = !0), this.parseMetaProperty(e, t, "meta");
  }
  parseLiteralAtNode(e, t, s) {
    return this.addExtra(s, "rawValue", e), this.addExtra(s, "raw", this.input.slice(s.start, this.state.end)), s.value = e, this.next(), this.finishNode(s, t);
  }
  parseLiteral(e, t) {
    const s = this.startNode();
    return this.parseLiteralAtNode(e, t, s);
  }
  parseStringLiteral(e) {
    return this.parseLiteral(e, "StringLiteral");
  }
  parseNumericLiteral(e) {
    return this.parseLiteral(e, "NumericLiteral");
  }
  parseBigIntLiteral(e) {
    return this.parseLiteral(e, "BigIntLiteral");
  }
  parseDecimalLiteral(e) {
    return this.parseLiteral(e, "DecimalLiteral");
  }
  parseRegExpLiteral(e) {
    const t = this.parseLiteral(e.value, "RegExpLiteral");
    return t.pattern = e.pattern, t.flags = e.flags, t;
  }
  parseBooleanLiteral(e) {
    const t = this.startNode();
    return t.value = e, this.next(), this.finishNode(t, "BooleanLiteral");
  }
  parseNullLiteral() {
    const e = this.startNode();
    return this.next(), this.finishNode(e, "NullLiteral");
  }
  parseParenAndDistinguishExpression(e) {
    const t = this.state.start, s = this.state.startLoc;
    let i;
    this.next(), this.expressionScope.enter(_1());
    const n = this.state.maybeInArrowParameters, a = this.state.inFSharpPipelineDirectBody;
    this.state.maybeInArrowParameters = !0, this.state.inFSharpPipelineDirectBody = !1;
    const o = this.state.start, l = this.state.startLoc, u = [], c = new fn();
    let h = !0, f, p;
    for (; !this.match(11); ) {
      if (h)
        h = !1;
      else if (this.expect(12, c.optionalParameters === -1 ? null : c.optionalParameters), this.match(11)) {
        p = this.state.start;
        break;
      }
      if (this.match(21)) {
        const _ = this.state.start, T = this.state.startLoc;
        f = this.state.start, u.push(this.parseParenItem(this.parseRestBinding(), _, T)), this.checkCommaAfterRest(41);
        break;
      } else
        u.push(this.parseMaybeAssignAllowIn(c, this.parseParenItem));
    }
    const x = this.state.lastTokEnd, d = this.state.lastTokEndLoc;
    this.expect(11), this.state.maybeInArrowParameters = n, this.state.inFSharpPipelineDirectBody = a;
    let m = this.startNodeAt(t, s);
    if (e && this.shouldParseArrow(u) && (m = this.parseArrow(m)))
      return this.expressionScope.validateAsPattern(), this.expressionScope.exit(), this.parseArrowExpression(m, u, !1), m;
    if (this.expressionScope.exit(), u.length || this.unexpected(this.state.lastTokStart), p && this.unexpected(p), f && this.unexpected(f), this.checkExpressionErrors(c, !0), this.toReferencedListDeep(u, !0), u.length > 1 ? (i = this.startNodeAt(o, l), i.expressions = u, this.finishNode(i, "SequenceExpression"), this.resetEndLocation(i, x, d)) : i = u[0], !this.options.createParenthesizedExpressions)
      return this.addExtra(i, "parenthesized", !0), this.addExtra(i, "parenStart", t), this.takeSurroundingComments(i, t, this.state.lastTokEnd), i;
    const y = this.startNodeAt(t, s);
    return y.expression = i, this.finishNode(y, "ParenthesizedExpression"), y;
  }
  shouldParseArrow(e) {
    return !this.canInsertSemicolon();
  }
  parseArrow(e) {
    if (this.eat(19))
      return e;
  }
  parseParenItem(e, t, s) {
    return e;
  }
  parseNewOrNewTarget() {
    const e = this.startNode();
    if (this.next(), this.match(16)) {
      const t = this.createIdentifier(this.startNodeAtNode(e), "new");
      this.next();
      const s = this.parseMetaProperty(e, t, "target");
      return !this.scope.inNonArrowFunction && !this.scope.inClass && this.raise(s.start, I.UnexpectedNewTarget), s;
    }
    return this.parseNew(e);
  }
  parseNew(e) {
    return e.callee = this.parseNoCallExpr(), e.callee.type === "Import" ? this.raise(e.callee.start, I.ImportCallNotNewExpression) : this.isOptionalChain(e.callee) ? this.raise(this.state.lastTokEnd, I.OptionalChainingNoNew) : this.eat(18) && this.raise(this.state.start, I.OptionalChainingNoNew), this.parseNewArguments(e), this.finishNode(e, "NewExpression");
  }
  parseNewArguments(e) {
    if (this.eat(10)) {
      const t = this.parseExprList(11);
      this.toReferencedList(t), e.arguments = t;
    } else
      e.arguments = [];
  }
  parseTemplateElement(e) {
    const t = this.startNode();
    return this.state.value === null && (e || this.raise(this.state.start + 1, I.InvalidEscapeSequenceTemplate)), t.value = {
      raw: this.input.slice(this.state.start, this.state.end).replace(/\r\n?/g, `
`),
      cooked: this.state.value
    }, this.next(), t.tail = this.match(22), this.finishNode(t, "TemplateElement");
  }
  parseTemplate(e) {
    const t = this.startNode();
    this.next(), t.expressions = [];
    let s = this.parseTemplateElement(e);
    for (t.quasis = [s]; !s.tail; )
      this.expect(23), t.expressions.push(this.parseTemplateSubstitution()), this.expect(8), t.quasis.push(s = this.parseTemplateElement(e));
    return this.next(), this.finishNode(t, "TemplateLiteral");
  }
  parseTemplateSubstitution() {
    return this.parseExpression();
  }
  parseObjectLike(e, t, s, i) {
    s && this.expectPlugin("recordAndTuple");
    const n = this.state.inFSharpPipelineDirectBody;
    this.state.inFSharpPipelineDirectBody = !1;
    const a = /* @__PURE__ */ Object.create(null);
    let o = !0;
    const l = this.startNode();
    for (l.properties = [], this.next(); !this.match(e); ) {
      if (o)
        o = !1;
      else if (this.expect(12), this.match(e)) {
        this.addExtra(l, "trailingComma", this.state.lastTokStart);
        break;
      }
      let c;
      t ? c = this.parseBindingProperty() : (c = this.parsePropertyDefinition(i), this.checkProto(c, s, a, i)), s && !this.isObjectProperty(c) && c.type !== "SpreadElement" && this.raise(c.start, I.InvalidRecordProperty), c.shorthand && this.addExtra(c, "shorthand", !0), l.properties.push(c);
    }
    this.next(), this.state.inFSharpPipelineDirectBody = n;
    let u = "ObjectExpression";
    return t ? u = "ObjectPattern" : s && (u = "RecordExpression"), this.finishNode(l, u);
  }
  maybeAsyncOrAccessorProp(e) {
    return !e.computed && e.key.type === "Identifier" && (this.isLiteralPropertyName() || this.match(0) || this.match(49));
  }
  parsePropertyDefinition(e) {
    let t = [];
    if (this.match(24))
      for (this.hasPlugin("decorators") && this.raise(this.state.start, I.UnsupportedPropertyDecorator); this.match(24); )
        t.push(this.parseDecorator());
    const s = this.startNode();
    let i = !1, n = !1, a, o;
    if (this.match(21))
      return t.length && this.unexpected(), this.parseSpread();
    t.length && (s.decorators = t, t = []), s.method = !1, e && (a = this.state.start, o = this.state.startLoc);
    let l = this.eat(49);
    this.parsePropertyNamePrefixOperator(s);
    const u = this.state.containsEsc, c = this.parsePropertyName(s);
    if (!l && !u && this.maybeAsyncOrAccessorProp(s)) {
      const h = c.name;
      h === "async" && !this.hasPrecedingLineBreak() && (i = !0, this.resetPreviousNodeTrailingComments(c), l = this.eat(49), this.parsePropertyName(s)), (h === "get" || h === "set") && (n = !0, this.resetPreviousNodeTrailingComments(c), s.kind = h, this.match(49) && (l = !0, this.raise(this.state.pos, I.AccessorIsGenerator, h), this.next()), this.parsePropertyName(s));
    }
    return this.parseObjPropValue(s, a, o, l, i, !1, n, e), s;
  }
  getGetterSetterExpectedParamCount(e) {
    return e.kind === "get" ? 0 : 1;
  }
  getObjectOrClassMethodParams(e) {
    return e.params;
  }
  checkGetterSetterParams(e) {
    var t;
    const s = this.getGetterSetterExpectedParamCount(e), i = this.getObjectOrClassMethodParams(e), n = e.start;
    i.length !== s && (e.kind === "get" ? this.raise(n, I.BadGetterArity) : this.raise(n, I.BadSetterArity)), e.kind === "set" && ((t = i[i.length - 1]) == null ? void 0 : t.type) === "RestElement" && this.raise(n, I.BadSetterRestParameter);
  }
  parseObjectMethod(e, t, s, i, n) {
    if (n)
      return this.parseMethod(e, t, !1, !1, !1, "ObjectMethod"), this.checkGetterSetterParams(e), e;
    if (s || t || this.match(10))
      return i && this.unexpected(), e.kind = "method", e.method = !0, this.parseMethod(e, t, s, !1, !1, "ObjectMethod");
  }
  parseObjectProperty(e, t, s, i, n) {
    if (e.shorthand = !1, this.eat(14))
      return e.value = i ? this.parseMaybeDefault(this.state.start, this.state.startLoc) : this.parseMaybeAssignAllowIn(n), this.finishNode(e, "ObjectProperty");
    if (!e.computed && e.key.type === "Identifier")
      return this.checkReservedWord(e.key.name, e.key.start, !0, !1), i ? e.value = this.parseMaybeDefault(t, s, tr(e.key)) : this.match(27) && n ? (n.shorthandAssign === -1 && (n.shorthandAssign = this.state.start), e.value = this.parseMaybeDefault(t, s, tr(e.key))) : e.value = tr(e.key), e.shorthand = !0, this.finishNode(e, "ObjectProperty");
  }
  parseObjPropValue(e, t, s, i, n, a, o, l) {
    const u = this.parseObjectMethod(e, i, n, a, o) || this.parseObjectProperty(e, t, s, a, l);
    return u || this.unexpected(), u;
  }
  parsePropertyName(e) {
    if (this.eat(0))
      e.computed = !0, e.key = this.parseMaybeAssignAllowIn(), this.expect(3);
    else {
      const {
        type: t,
        value: s
      } = this.state;
      let i;
      if (er(t))
        i = this.parseIdentifier(!0);
      else
        switch (t) {
          case 124:
            i = this.parseNumericLiteral(s);
            break;
          case 123:
            i = this.parseStringLiteral(s);
            break;
          case 125:
            i = this.parseBigIntLiteral(s);
            break;
          case 126:
            i = this.parseDecimalLiteral(s);
            break;
          case 128: {
            const n = this.state.start + 1;
            this.raise(n, I.UnexpectedPrivateField), i = this.parsePrivateName();
            break;
          }
          default:
            throw this.unexpected();
        }
      e.key = i, t !== 128 && (e.computed = !1);
    }
    return e.key;
  }
  initFunction(e, t) {
    e.id = null, e.generator = !1, e.async = !!t;
  }
  parseMethod(e, t, s, i, n, a, o = !1) {
    this.initFunction(e, s), e.generator = !!t;
    const l = i;
    return this.scope.enter(Yt | kn | (o ? qr : 0) | (n ? Zf : 0)), this.prodParam.enter(hn(s, e.generator)), this.parseFunctionParams(e, l), this.parseFunctionBodyAndFinish(e, a, !0), this.prodParam.exit(), this.scope.exit(), e;
  }
  parseArrayLike(e, t, s, i) {
    s && this.expectPlugin("recordAndTuple");
    const n = this.state.inFSharpPipelineDirectBody;
    this.state.inFSharpPipelineDirectBody = !1;
    const a = this.startNode();
    return this.next(), a.elements = this.parseExprList(e, !s, i, a), this.state.inFSharpPipelineDirectBody = n, this.finishNode(a, s ? "TupleExpression" : "ArrayExpression");
  }
  parseArrowExpression(e, t, s, i) {
    this.scope.enter(Yt | Hl);
    let n = hn(s, !1);
    !this.match(0) && this.prodParam.hasIn && (n |= es), this.prodParam.enter(n), this.initFunction(e, s);
    const a = this.state.maybeInArrowParameters;
    return t && (this.state.maybeInArrowParameters = !0, this.setArrowFunctionParameters(e, t, i)), this.state.maybeInArrowParameters = !1, this.parseFunctionBody(e, !0), this.prodParam.exit(), this.scope.exit(), this.state.maybeInArrowParameters = a, this.finishNode(e, "ArrowFunctionExpression");
  }
  setArrowFunctionParameters(e, t, s) {
    e.params = this.toAssignableList(t, s, !1);
  }
  parseFunctionBodyAndFinish(e, t, s = !1) {
    this.parseFunctionBody(e, !1, s), this.finishNode(e, t);
  }
  parseFunctionBody(e, t, s = !1) {
    const i = t && !this.match(5);
    if (this.expressionScope.enter(up()), i)
      e.body = this.parseMaybeAssign(), this.checkParams(e, !1, t, !1);
    else {
      const n = this.state.strict, a = this.state.labels;
      this.state.labels = [], this.prodParam.enter(this.prodParam.currentFlags() | hp), e.body = this.parseBlock(!0, !1, (o) => {
        const l = !this.isSimpleParamList(e.params);
        if (o && l) {
          const c = (e.kind === "method" || e.kind === "constructor") && !!e.key ? e.key.end : e.start;
          this.raise(c, I.IllegalLanguageModeDirective);
        }
        const u = !n && this.state.strict;
        this.checkParams(e, !this.state.strict && !t && !s && !l, t, u), this.state.strict && e.id && this.checkLVal(e.id, "function name", u1, void 0, void 0, u);
      }), this.prodParam.exit(), this.state.labels = a;
    }
    this.expressionScope.exit();
  }
  isSimpleParamList(e) {
    for (let t = 0, s = e.length; t < s; t++)
      if (e[t].type !== "Identifier")
        return !1;
    return !0;
  }
  checkParams(e, t, s, i = !0) {
    const n = /* @__PURE__ */ new Set();
    for (const a of e.params)
      this.checkLVal(a, "function parameter list", Ln, t ? null : n, void 0, i);
  }
  parseExprList(e, t, s, i) {
    const n = [];
    let a = !0;
    for (; !this.eat(e); ) {
      if (a)
        a = !1;
      else if (this.expect(12), this.match(e)) {
        i && this.addExtra(i, "trailingComma", this.state.lastTokStart), this.next();
        break;
      }
      n.push(this.parseExprListItem(t, s));
    }
    return n;
  }
  parseExprListItem(e, t, s) {
    let i;
    if (this.match(12))
      e || this.raise(this.state.pos, I.UnexpectedToken, ","), i = null;
    else if (this.match(21)) {
      const n = this.state.start, a = this.state.startLoc;
      i = this.parseParenItem(this.parseSpread(t), n, a);
    } else if (this.match(17)) {
      this.expectPlugin("partialApplication"), s || this.raise(this.state.start, I.UnexpectedArgumentPlaceholder);
      const n = this.startNode();
      this.next(), i = this.finishNode(n, "ArgumentPlaceholder");
    } else
      i = this.parseMaybeAssignAllowIn(t, this.parseParenItem);
    return i;
  }
  parseIdentifier(e) {
    const t = this.startNode(), s = this.parseIdentifierName(t.start, e);
    return this.createIdentifier(t, s);
  }
  createIdentifier(e, t) {
    return e.name = t, e.loc.identifierName = t, this.finishNode(e, "Identifier");
  }
  parseIdentifierName(e, t) {
    let s;
    const {
      start: i,
      type: n
    } = this.state;
    if (er(n))
      s = this.state.value;
    else
      throw this.unexpected();
    const a = $0(n);
    return t ? a && this.replaceToken(122) : this.checkReservedWord(s, i, a, !1), this.next(), s;
  }
  checkReservedWord(e, t, s, i) {
    if (e.length > 10 || !a1(e))
      return;
    if (e === "yield") {
      if (this.prodParam.hasYield) {
        this.raise(t, I.YieldBindingIdentifier);
        return;
      }
    } else if (e === "await")
      if (this.prodParam.hasAwait) {
        this.raise(t, I.AwaitBindingIdentifier);
        return;
      } else if (this.scope.inStaticBlock) {
        this.raise(t, I.AwaitBindingIdentifierInStaticBlock);
        return;
      } else
        this.expressionScope.recordAsyncArrowParametersError(t, I.AwaitBindingIdentifier);
    else if (e === "arguments" && this.scope.inClassAndNotInNonArrowFunction) {
      this.raise(t, I.ArgumentsInClass);
      return;
    }
    if (s && s1(e)) {
      this.raise(t, I.UnexpectedKeyword, e);
      return;
    }
    (this.state.strict ? i ? Qf : Yf : Gf)(e, this.inModule) && this.raise(t, I.UnexpectedReservedWord, e);
  }
  isAwaitAllowed() {
    return !!(this.prodParam.hasAwait || this.options.allowAwaitOutsideFunction && !this.scope.inFunction);
  }
  parseAwait(e, t) {
    const s = this.startNodeAt(e, t);
    return this.expressionScope.recordParameterInitializerError(s.start, I.AwaitExpressionFormalParameter), this.eat(49) && this.raise(s.start, I.ObsoleteAwaitStar), !this.scope.inFunction && !this.options.allowAwaitOutsideFunction && (this.isAmbiguousAwait() ? this.ambiguousScriptDifferentAst = !0 : this.sawUnambiguousESM = !0), this.state.soloAwait || (s.argument = this.parseMaybeUnary(null, !0)), this.finishNode(s, "AwaitExpression");
  }
  isAmbiguousAwait() {
    return this.hasPrecedingLineBreak() || this.match(47) || this.match(10) || this.match(0) || this.match(22) || this.match(127) || this.match(50) || this.hasPlugin("v8intrinsic") && this.match(48);
  }
  parseYield() {
    const e = this.startNode();
    this.expressionScope.recordParameterInitializerError(e.start, I.YieldInParameter), this.next();
    let t = !1, s = null;
    if (!this.hasPrecedingLineBreak())
      switch (t = this.eat(49), this.state.type) {
        case 13:
        case 129:
        case 8:
        case 11:
        case 3:
        case 9:
        case 14:
        case 12:
          if (!t)
            break;
        default:
          s = this.parseMaybeAssign();
      }
    return e.delegate = t, e.argument = s, this.finishNode(e, "YieldExpression");
  }
  checkPipelineAtInfixOperator(e, t) {
    this.getPluginOption("pipelineOperator", "proposal") === "smart" && e.type === "SequenceExpression" && this.raise(t, I.PipelineHeadSequenceExpression);
  }
  checkHackPipeBodyEarlyErrors(e) {
    this.topicReferenceWasUsedInCurrentContext() || this.raise(e, I.PipeTopicUnused);
  }
  parseSmartPipelineBodyInStyle(e, t, s) {
    const i = this.startNodeAt(t, s);
    return this.isSimpleReference(e) ? (i.callee = e, this.finishNode(i, "PipelineBareFunction")) : (this.checkSmartPipeTopicBodyEarlyErrors(t), i.expression = e, this.finishNode(i, "PipelineTopicExpression"));
  }
  isSimpleReference(e) {
    switch (e.type) {
      case "MemberExpression":
        return !e.computed && this.isSimpleReference(e.object);
      case "Identifier":
        return !0;
      default:
        return !1;
    }
  }
  checkSmartPipeTopicBodyEarlyErrors(e) {
    if (this.match(19))
      throw this.raise(this.state.start, I.PipelineBodyNoArrow);
    this.topicReferenceWasUsedInCurrentContext() || this.raise(e, I.PipelineTopicUnused);
  }
  withTopicBindingContext(e) {
    const t = this.state.topicContext;
    this.state.topicContext = {
      maxNumOfResolvableTopics: 1,
      maxTopicIndex: null
    };
    try {
      return e();
    } finally {
      this.state.topicContext = t;
    }
  }
  withSmartMixTopicForbiddingContext(e) {
    if (this.getPluginOption("pipelineOperator", "proposal") === "smart") {
      const s = this.state.topicContext;
      this.state.topicContext = {
        maxNumOfResolvableTopics: 0,
        maxTopicIndex: null
      };
      try {
        return e();
      } finally {
        this.state.topicContext = s;
      }
    } else
      return e();
  }
  withSoloAwaitPermittingContext(e) {
    const t = this.state.soloAwait;
    this.state.soloAwait = !0;
    try {
      return e();
    } finally {
      this.state.soloAwait = t;
    }
  }
  allowInAnd(e) {
    const t = this.prodParam.currentFlags();
    if (es & ~t) {
      this.prodParam.enter(t | es);
      try {
        return e();
      } finally {
        this.prodParam.exit();
      }
    }
    return e();
  }
  disallowInAnd(e) {
    const t = this.prodParam.currentFlags();
    if (es & t) {
      this.prodParam.enter(t & ~es);
      try {
        return e();
      } finally {
        this.prodParam.exit();
      }
    }
    return e();
  }
  registerTopicReference() {
    this.state.topicContext.maxTopicIndex = 0;
  }
  topicReferenceIsAllowedInCurrentContext() {
    return this.state.topicContext.maxNumOfResolvableTopics >= 1;
  }
  topicReferenceWasUsedInCurrentContext() {
    return this.state.topicContext.maxTopicIndex != null && this.state.topicContext.maxTopicIndex >= 0;
  }
  parseFSharpPipelineBody(e) {
    const t = this.state.start, s = this.state.startLoc;
    this.state.potentialArrowAt = this.state.start;
    const i = this.state.inFSharpPipelineDirectBody;
    this.state.inFSharpPipelineDirectBody = !0;
    const n = this.parseExprOp(this.parseMaybeUnaryOrPrivate(), t, s, e);
    return this.state.inFSharpPipelineDirectBody = i, n;
  }
  parseModuleExpression() {
    this.expectPlugin("moduleBlocks");
    const e = this.startNode();
    this.next(), this.eat(5);
    const t = this.initializeScopes(!0);
    this.enterInitialScopes();
    const s = this.startNode();
    try {
      e.body = this.parseProgram(s, 8, "module");
    } finally {
      t();
    }
    return this.eat(8), this.finishNode(e, "ModuleExpression");
  }
  parsePropertyNamePrefixOperator(e) {
  }
}
const qa = {
  kind: "loop"
}, sv = {
  kind: "switch"
}, iv = 0, Va = 1, Rc = 2, Fc = 4, nv = /[\uD800-\uDFFF]/u, za = /in(?:stanceof)?/y;
function av(r) {
  for (let e = 0; e < r.length; e++) {
    const t = r[e], {
      type: s
    } = t;
    if (s === 128) {
      const {
        loc: i,
        start: n,
        value: a,
        end: o
      } = t, l = n + 1, u = new vi(i.start.line, i.start.column + 1);
      r.splice(e, 1, new Oo({
        type: ln(25),
        value: "#",
        start: n,
        end: l,
        startLoc: i.start,
        endLoc: u
      }), new Oo({
        type: ln(122),
        value: a,
        start: l,
        end: o,
        startLoc: u,
        endLoc: i.end
      })), e++;
      continue;
    }
    typeof s == "number" && (t.type = ln(s));
  }
  return r;
}
class ov extends rv {
  parseTopLevel(e, t) {
    return e.program = this.parseProgram(t), e.comments = this.state.comments, this.options.tokens && (e.tokens = av(this.tokens)), this.finishNode(e, "File");
  }
  parseProgram(e, t = 129, s = this.options.sourceType) {
    if (e.sourceType = s, e.interpreter = this.parseInterpreterDirective(), this.parseBlockBody(e, !0, !0, t), this.inModule && !this.options.allowUndeclaredExports && this.scope.undefinedExports.size > 0)
      for (const [i] of Array.from(this.scope.undefinedExports)) {
        const n = this.scope.undefinedExports.get(i);
        this.raise(n, I.ModuleExportUndefined, i);
      }
    return this.finishNode(e, "Program");
  }
  stmtToDirective(e) {
    const t = e;
    t.type = "Directive", t.value = t.expression, delete t.expression;
    const s = t.value, i = s.value, n = this.input.slice(s.start, s.end), a = s.value = n.slice(1, -1);
    return this.addExtra(s, "raw", n), this.addExtra(s, "rawValue", a), this.addExtra(s, "expressionValue", i), s.type = "DirectiveLiteral", t;
  }
  parseInterpreterDirective() {
    if (!this.match(26))
      return null;
    const e = this.startNode();
    return e.value = this.state.value, this.next(), this.finishNode(e, "InterpreterDirective");
  }
  isLet(e) {
    return this.isContextual(93) ? this.isLetKeyword(e) : !1;
  }
  isLetKeyword(e) {
    const t = this.nextTokenStart(), s = this.codePointAtPos(t);
    if (s === 92 || s === 91)
      return !0;
    if (e)
      return !1;
    if (s === 123)
      return !0;
    if (br(s)) {
      if (za.lastIndex = t, za.test(this.input)) {
        const i = this.codePointAtPos(za.lastIndex);
        if (!ps(i) && i !== 92)
          return !1;
      }
      return !0;
    }
    return !1;
  }
  parseStatement(e, t) {
    return this.match(24) && this.parseDecorators(!0), this.parseStatementContent(e, t);
  }
  parseStatementContent(e, t) {
    let s = this.state.type;
    const i = this.startNode();
    let n;
    switch (this.isLet(e) && (s = 68, n = "let"), s) {
      case 54:
        return this.parseBreakContinueStatement(i, !0);
      case 57:
        return this.parseBreakContinueStatement(i, !1);
      case 58:
        return this.parseDebuggerStatement(i);
      case 84:
        return this.parseDoStatement(i);
      case 85:
        return this.parseForStatement(i);
      case 62:
        if (this.lookaheadCharCode() === 46)
          break;
        return e && (this.state.strict ? this.raise(this.state.start, I.StrictFunction) : e !== "if" && e !== "label" && this.raise(this.state.start, I.SloppyFunction)), this.parseFunctionStatement(i, !1, !e);
      case 74:
        return e && this.unexpected(), this.parseClass(i, !0);
      case 63:
        return this.parseIfStatement(i);
      case 64:
        return this.parseReturnStatement(i);
      case 65:
        return this.parseSwitchStatement(i);
      case 66:
        return this.parseThrowStatement(i);
      case 67:
        return this.parseTryStatement(i);
      case 69:
      case 68:
        return n = n || this.state.value, e && n !== "var" && this.raise(this.state.start, I.UnexpectedLexicalDeclaration), this.parseVarStatement(i, n);
      case 86:
        return this.parseWhileStatement(i);
      case 70:
        return this.parseWithStatement(i);
      case 5:
        return this.parseBlock();
      case 13:
        return this.parseEmptyStatement(i);
      case 77: {
        const l = this.lookaheadCharCode();
        if (l === 40 || l === 46)
          break;
      }
      case 76: {
        !this.options.allowImportExportEverywhere && !t && this.raise(this.state.start, I.UnexpectedImportExport), this.next();
        let l;
        return s === 77 ? (l = this.parseImport(i), l.type === "ImportDeclaration" && (!l.importKind || l.importKind === "value") && (this.sawUnambiguousESM = !0)) : (l = this.parseExport(i), (l.type === "ExportNamedDeclaration" && (!l.exportKind || l.exportKind === "value") || l.type === "ExportAllDeclaration" && (!l.exportKind || l.exportKind === "value") || l.type === "ExportDefaultDeclaration") && (this.sawUnambiguousESM = !0)), this.assertModuleNodeAllowed(i), l;
      }
      default:
        if (this.isAsyncFunction())
          return e && this.raise(this.state.start, I.AsyncFunctionInSingleStatementContext), this.next(), this.parseFunctionStatement(i, !0, !e);
    }
    const a = this.state.value, o = this.parseExpression();
    return Se(s) && o.type === "Identifier" && this.eat(14) ? this.parseLabeledStatement(i, a, o, e) : this.parseExpressionStatement(i, o);
  }
  assertModuleNodeAllowed(e) {
    !this.options.allowImportExportEverywhere && !this.inModule && this.raise(e.start, Vf.ImportOutsideModule);
  }
  takeDecorators(e) {
    const t = this.state.decoratorStack[this.state.decoratorStack.length - 1];
    t.length && (e.decorators = t, this.resetStartLocationFromNode(e, t[0]), this.state.decoratorStack[this.state.decoratorStack.length - 1] = []);
  }
  canHaveLeadingDecorator() {
    return this.match(74);
  }
  parseDecorators(e) {
    const t = this.state.decoratorStack[this.state.decoratorStack.length - 1];
    for (; this.match(24); ) {
      const s = this.parseDecorator();
      t.push(s);
    }
    if (this.match(76))
      e || this.unexpected(), this.hasPlugin("decorators") && !this.getPluginOption("decorators", "decoratorsBeforeExport") && this.raise(this.state.start, I.DecoratorExportClass);
    else if (!this.canHaveLeadingDecorator())
      throw this.raise(this.state.start, I.UnexpectedLeadingDecorator);
  }
  parseDecorator() {
    this.expectOnePlugin(["decorators-legacy", "decorators"]);
    const e = this.startNode();
    if (this.next(), this.hasPlugin("decorators")) {
      this.state.decoratorStack.push([]);
      const t = this.state.start, s = this.state.startLoc;
      let i;
      if (this.eat(10))
        i = this.parseExpression(), this.expect(11);
      else
        for (i = this.parseIdentifier(!1); this.eat(16); ) {
          const n = this.startNodeAt(t, s);
          n.object = i, n.property = this.parseIdentifier(!0), n.computed = !1, i = this.finishNode(n, "MemberExpression");
        }
      e.expression = this.parseMaybeDecoratorArguments(i), this.state.decoratorStack.pop();
    } else
      e.expression = this.parseExprSubscripts();
    return this.finishNode(e, "Decorator");
  }
  parseMaybeDecoratorArguments(e) {
    if (this.eat(10)) {
      const t = this.startNodeAtNode(e);
      return t.callee = e, t.arguments = this.parseCallExpressionArguments(11, !1), this.toReferencedList(t.arguments), this.finishNode(t, "CallExpression");
    }
    return e;
  }
  parseBreakContinueStatement(e, t) {
    return this.next(), this.isLineTerminator() ? e.label = null : (e.label = this.parseIdentifier(), this.semicolon()), this.verifyBreakContinue(e, t), this.finishNode(e, t ? "BreakStatement" : "ContinueStatement");
  }
  verifyBreakContinue(e, t) {
    let s;
    for (s = 0; s < this.state.labels.length; ++s) {
      const i = this.state.labels[s];
      if ((e.label == null || i.name === e.label.name) && (i.kind != null && (t || i.kind === "loop") || e.label && t))
        break;
    }
    s === this.state.labels.length && this.raise(e.start, I.IllegalBreakContinue, t ? "break" : "continue");
  }
  parseDebuggerStatement(e) {
    return this.next(), this.semicolon(), this.finishNode(e, "DebuggerStatement");
  }
  parseHeaderExpression() {
    this.expect(10);
    const e = this.parseExpression();
    return this.expect(11), e;
  }
  parseDoStatement(e) {
    return this.next(), this.state.labels.push(qa), e.body = this.withSmartMixTopicForbiddingContext(() => this.parseStatement("do")), this.state.labels.pop(), this.expect(86), e.test = this.parseHeaderExpression(), this.eat(13), this.finishNode(e, "DoWhileStatement");
  }
  parseForStatement(e) {
    this.next(), this.state.labels.push(qa);
    let t = -1;
    if (this.isAwaitAllowed() && this.eatContextual(90) && (t = this.state.lastTokStart), this.scope.enter(ns), this.expect(10), this.match(13))
      return t > -1 && this.unexpected(t), this.parseFor(e, null);
    const s = this.isContextual(93), i = s && this.isLetKeyword();
    if (this.match(68) || this.match(69) || i) {
      const u = this.startNode(), c = i ? "let" : this.state.value;
      return this.next(), this.parseVar(u, !0, c), this.finishNode(u, "VariableDeclaration"), (this.match(52) || this.isContextual(95)) && u.declarations.length === 1 ? this.parseForIn(e, u, t) : (t > -1 && this.unexpected(t), this.parseFor(e, u));
    }
    const n = this.isContextual(89), a = new fn(), o = this.parseExpression(!0, a), l = this.isContextual(95);
    if (l && (s ? this.raise(o.start, I.ForOfLet) : t === -1 && n && o.type === "Identifier" && this.raise(o.start, I.ForOfAsync)), l || this.match(52)) {
      this.toAssignable(o, !0);
      const u = l ? "for-of statement" : "for-in statement";
      return this.checkLVal(o, u), this.parseForIn(e, o, t);
    } else
      this.checkExpressionErrors(a, !0);
    return t > -1 && this.unexpected(t), this.parseFor(e, o);
  }
  parseFunctionStatement(e, t, s) {
    return this.next(), this.parseFunction(e, Va | (s ? 0 : Rc), t);
  }
  parseIfStatement(e) {
    return this.next(), e.test = this.parseHeaderExpression(), e.consequent = this.parseStatement("if"), e.alternate = this.eat(60) ? this.parseStatement("if") : null, this.finishNode(e, "IfStatement");
  }
  parseReturnStatement(e) {
    return !this.prodParam.hasReturn && !this.options.allowReturnOutsideFunction && this.raise(this.state.start, I.IllegalReturn), this.next(), this.isLineTerminator() ? e.argument = null : (e.argument = this.parseExpression(), this.semicolon()), this.finishNode(e, "ReturnStatement");
  }
  parseSwitchStatement(e) {
    this.next(), e.discriminant = this.parseHeaderExpression();
    const t = e.cases = [];
    this.expect(5), this.state.labels.push(sv), this.scope.enter(ns);
    let s;
    for (let i; !this.match(8); )
      if (this.match(55) || this.match(59)) {
        const n = this.match(55);
        s && this.finishNode(s, "SwitchCase"), t.push(s = this.startNode()), s.consequent = [], this.next(), n ? s.test = this.parseExpression() : (i && this.raise(this.state.lastTokStart, I.MultipleDefaultsInSwitch), i = !0, s.test = null), this.expect(14);
      } else
        s ? s.consequent.push(this.parseStatement(null)) : this.unexpected();
    return this.scope.exit(), s && this.finishNode(s, "SwitchCase"), this.next(), this.state.labels.pop(), this.finishNode(e, "SwitchStatement");
  }
  parseThrowStatement(e) {
    return this.next(), this.hasPrecedingLineBreak() && this.raise(this.state.lastTokEnd, I.NewlineAfterThrow), e.argument = this.parseExpression(), this.semicolon(), this.finishNode(e, "ThrowStatement");
  }
  parseCatchClauseParam() {
    const e = this.parseBindingAtom(), t = e.type === "Identifier";
    return this.scope.enter(t ? Xf : 0), this.checkLVal(e, "catch clause", yt), e;
  }
  parseTryStatement(e) {
    if (this.next(), e.block = this.parseBlock(), e.handler = null, this.match(56)) {
      const t = this.startNode();
      this.next(), this.match(10) ? (this.expect(10), t.param = this.parseCatchClauseParam(), this.expect(11)) : (t.param = null, this.scope.enter(ns)), t.body = this.withSmartMixTopicForbiddingContext(() => this.parseBlock(!1, !1)), this.scope.exit(), e.handler = this.finishNode(t, "CatchClause");
    }
    return e.finalizer = this.eat(61) ? this.parseBlock() : null, !e.handler && !e.finalizer && this.raise(e.start, I.NoCatchOrFinally), this.finishNode(e, "TryStatement");
  }
  parseVarStatement(e, t) {
    return this.next(), this.parseVar(e, !1, t), this.semicolon(), this.finishNode(e, "VariableDeclaration");
  }
  parseWhileStatement(e) {
    return this.next(), e.test = this.parseHeaderExpression(), this.state.labels.push(qa), e.body = this.withSmartMixTopicForbiddingContext(() => this.parseStatement("while")), this.state.labels.pop(), this.finishNode(e, "WhileStatement");
  }
  parseWithStatement(e) {
    return this.state.strict && this.raise(this.state.start, I.StrictWith), this.next(), e.object = this.parseHeaderExpression(), e.body = this.withSmartMixTopicForbiddingContext(() => this.parseStatement("with")), this.finishNode(e, "WithStatement");
  }
  parseEmptyStatement(e) {
    return this.next(), this.finishNode(e, "EmptyStatement");
  }
  parseLabeledStatement(e, t, s, i) {
    for (const a of this.state.labels)
      a.name === t && this.raise(s.start, I.LabelRedeclaration, t);
    const n = V0(this.state.type) ? "loop" : this.match(65) ? "switch" : null;
    for (let a = this.state.labels.length - 1; a >= 0; a--) {
      const o = this.state.labels[a];
      if (o.statementStart === e.start)
        o.statementStart = this.state.start, o.kind = n;
      else
        break;
    }
    return this.state.labels.push({
      name: t,
      kind: n,
      statementStart: this.state.start
    }), e.body = this.parseStatement(i ? i.indexOf("label") === -1 ? i + "label" : i : "label"), this.state.labels.pop(), e.label = s, this.finishNode(e, "LabeledStatement");
  }
  parseExpressionStatement(e, t) {
    return e.expression = t, this.semicolon(), this.finishNode(e, "ExpressionStatement");
  }
  parseBlock(e = !1, t = !0, s) {
    const i = this.startNode();
    return e && this.state.strictErrors.clear(), this.expect(5), t && this.scope.enter(ns), this.parseBlockBody(i, e, !1, 8, s), t && this.scope.exit(), this.finishNode(i, "BlockStatement");
  }
  isValidDirective(e) {
    return e.type === "ExpressionStatement" && e.expression.type === "StringLiteral" && !e.expression.extra.parenthesized;
  }
  parseBlockBody(e, t, s, i, n) {
    const a = e.body = [], o = e.directives = [];
    this.parseBlockOrModuleBlockBody(a, t ? o : void 0, s, i, n);
  }
  parseBlockOrModuleBlockBody(e, t, s, i, n) {
    const a = this.state.strict;
    let o = !1, l = !1;
    for (; !this.match(i); ) {
      const u = this.parseStatement(null, s);
      if (t && !l) {
        if (this.isValidDirective(u)) {
          const c = this.stmtToDirective(u);
          t.push(c), !o && c.value.value === "use strict" && (o = !0, this.setStrict(!0));
          continue;
        }
        l = !0, this.state.strictErrors.clear();
      }
      e.push(u);
    }
    n && n.call(this, o), a || this.setStrict(!1), this.next();
  }
  parseFor(e, t) {
    return e.init = t, this.semicolon(!1), e.test = this.match(13) ? null : this.parseExpression(), this.semicolon(!1), e.update = this.match(11) ? null : this.parseExpression(), this.expect(11), e.body = this.withSmartMixTopicForbiddingContext(() => this.parseStatement("for")), this.scope.exit(), this.state.labels.pop(), this.finishNode(e, "ForStatement");
  }
  parseForIn(e, t, s) {
    const i = this.match(52);
    return this.next(), i ? s > -1 && this.unexpected(s) : e.await = s > -1, t.type === "VariableDeclaration" && t.declarations[0].init != null && (!i || this.state.strict || t.kind !== "var" || t.declarations[0].id.type !== "Identifier") ? this.raise(t.start, I.ForInOfLoopInitializer, i ? "for-in" : "for-of") : t.type === "AssignmentPattern" && this.raise(t.start, I.InvalidLhs, "for-loop"), e.left = t, e.right = i ? this.parseExpression() : this.parseMaybeAssignAllowIn(), this.expect(11), e.body = this.withSmartMixTopicForbiddingContext(() => this.parseStatement("for")), this.scope.exit(), this.state.labels.pop(), this.finishNode(e, i ? "ForInStatement" : "ForOfStatement");
  }
  parseVar(e, t, s) {
    const i = e.declarations = [], n = this.hasPlugin("typescript");
    for (e.kind = s; ; ) {
      const a = this.startNode();
      if (this.parseVarId(a, s), this.eat(27) ? a.init = t ? this.parseMaybeAssignDisallowIn() : this.parseMaybeAssignAllowIn() : (s === "const" && !(this.match(52) || this.isContextual(95)) ? n || this.raise(this.state.lastTokEnd, I.DeclarationMissingInitializer, "Const declarations") : a.id.type !== "Identifier" && !(t && (this.match(52) || this.isContextual(95))) && this.raise(this.state.lastTokEnd, I.DeclarationMissingInitializer, "Complex binding patterns"), a.init = null), i.push(this.finishNode(a, "VariableDeclarator")), !this.eat(12))
        break;
    }
    return e;
  }
  parseVarId(e, t) {
    e.id = this.parseBindingAtom(), this.checkLVal(e.id, "variable declaration", t === "var" ? Ln : yt, void 0, t !== "var");
  }
  parseFunction(e, t = iv, s = !1) {
    const i = t & Va, n = t & Rc, a = !!i && !(t & Fc);
    this.initFunction(e, s), this.match(49) && n && this.raise(this.state.start, I.GeneratorInSingleStatementContext), e.generator = this.eat(49), i && (e.id = this.parseFunctionId(a));
    const o = this.state.maybeInArrowParameters;
    return this.state.maybeInArrowParameters = !1, this.scope.enter(Yt), this.prodParam.enter(hn(s, e.generator)), i || (e.id = this.parseFunctionId()), this.parseFunctionParams(e, !1), this.withSmartMixTopicForbiddingContext(() => {
      this.parseFunctionBodyAndFinish(e, i ? "FunctionDeclaration" : "FunctionExpression");
    }), this.prodParam.exit(), this.scope.exit(), i && !n && this.registerFunctionStatementId(e), this.state.maybeInArrowParameters = o, e;
  }
  parseFunctionId(e) {
    return e || Se(this.state.type) ? this.parseIdentifier() : null;
  }
  parseFunctionParams(e, t) {
    this.expect(10), this.expressionScope.enter(A1()), e.params = this.parseBindingList(11, 41, !1, t), this.expressionScope.exit();
  }
  registerFunctionStatementId(e) {
    !e.id || this.scope.declareName(e.id.name, this.state.strict || e.generator || e.async ? this.scope.treatFunctionsAsVar ? Ln : yt : ip, e.id.start);
  }
  parseClass(e, t, s) {
    this.next(), this.takeDecorators(e);
    const i = this.state.strict;
    return this.state.strict = !0, this.parseClassId(e, t, s), this.parseClassSuper(e), e.body = this.parseClassBody(!!e.superClass, i), this.finishNode(e, t ? "ClassDeclaration" : "ClassExpression");
  }
  isClassProperty() {
    return this.match(27) || this.match(13) || this.match(8);
  }
  isClassMethod() {
    return this.match(10);
  }
  isNonstaticConstructor(e) {
    return !e.computed && !e.static && (e.key.name === "constructor" || e.key.value === "constructor");
  }
  parseClassBody(e, t) {
    this.classScope.enter();
    const s = {
      hadConstructor: !1,
      hadSuperClass: e
    };
    let i = [];
    const n = this.startNode();
    if (n.body = [], this.expect(5), this.withSmartMixTopicForbiddingContext(() => {
      for (; !this.match(8); ) {
        if (this.eat(13)) {
          if (i.length > 0)
            throw this.raise(this.state.lastTokEnd, I.DecoratorSemicolon);
          continue;
        }
        if (this.match(24)) {
          i.push(this.parseDecorator());
          continue;
        }
        const a = this.startNode();
        i.length && (a.decorators = i, this.resetStartLocationFromNode(a, i[0]), i = []), this.parseClassMember(n, a, s), a.kind === "constructor" && a.decorators && a.decorators.length > 0 && this.raise(a.start, I.DecoratorConstructor);
      }
    }), this.state.strict = t, this.next(), i.length)
      throw this.raise(this.state.start, I.TrailingDecorator);
    return this.classScope.exit(), this.finishNode(n, "ClassBody");
  }
  parseClassMemberFromModifier(e, t) {
    const s = this.parseIdentifier(!0);
    if (this.isClassMethod()) {
      const i = t;
      return i.kind = "method", i.computed = !1, i.key = s, i.static = !1, this.pushClassMethod(e, i, !1, !1, !1, !1), !0;
    } else if (this.isClassProperty()) {
      const i = t;
      return i.computed = !1, i.key = s, i.static = !1, e.body.push(this.parseClassProperty(i)), !0;
    }
    return this.resetPreviousNodeTrailingComments(s), !1;
  }
  parseClassMember(e, t, s) {
    const i = this.isContextual(98);
    if (i) {
      if (this.parseClassMemberFromModifier(e, t))
        return;
      if (this.eat(5)) {
        this.parseClassStaticBlock(e, t);
        return;
      }
    }
    this.parseClassMemberWithIsStatic(e, t, s, i);
  }
  parseClassMemberWithIsStatic(e, t, s, i) {
    const n = t, a = t, o = t, l = t, u = n, c = n;
    if (t.static = i, this.parsePropertyNamePrefixOperator(t), this.eat(49)) {
      u.kind = "method";
      const d = this.match(128);
      if (this.parseClassElementName(u), d) {
        this.pushClassPrivateMethod(e, a, !0, !1);
        return;
      }
      this.isNonstaticConstructor(n) && this.raise(n.key.start, I.ConstructorIsGenerator), this.pushClassMethod(e, n, !0, !1, !1, !1);
      return;
    }
    const h = Se(this.state.type) && !this.state.containsEsc, f = this.match(128), p = this.parseClassElementName(t), x = this.state.start;
    if (this.parsePostMemberNameModifiers(c), this.isClassMethod()) {
      if (u.kind = "method", f) {
        this.pushClassPrivateMethod(e, a, !1, !1);
        return;
      }
      const d = this.isNonstaticConstructor(n);
      let m = !1;
      d && (n.kind = "constructor", s.hadConstructor && !this.hasPlugin("typescript") && this.raise(p.start, I.DuplicateConstructor), d && this.hasPlugin("typescript") && t.override && this.raise(p.start, I.OverrideOnConstructor), s.hadConstructor = !0, m = s.hadSuperClass), this.pushClassMethod(e, n, !1, !1, d, m);
    } else if (this.isClassProperty())
      f ? this.pushClassPrivateProperty(e, l) : this.pushClassProperty(e, o);
    else if (h && p.name === "async" && !this.isLineTerminator()) {
      this.resetPreviousNodeTrailingComments(p);
      const d = this.eat(49);
      c.optional && this.unexpected(x), u.kind = "method";
      const m = this.match(128);
      this.parseClassElementName(u), this.parsePostMemberNameModifiers(c), m ? this.pushClassPrivateMethod(e, a, d, !0) : (this.isNonstaticConstructor(n) && this.raise(n.key.start, I.ConstructorIsAsync), this.pushClassMethod(e, n, d, !0, !1, !1));
    } else if (h && (p.name === "get" || p.name === "set") && !(this.match(49) && this.isLineTerminator())) {
      this.resetPreviousNodeTrailingComments(p), u.kind = p.name;
      const d = this.match(128);
      this.parseClassElementName(n), d ? this.pushClassPrivateMethod(e, a, !1, !1) : (this.isNonstaticConstructor(n) && this.raise(n.key.start, I.ConstructorIsAccessor), this.pushClassMethod(e, n, !1, !1, !1, !1)), this.checkGetterSetterParams(n);
    } else
      this.isLineTerminator() ? f ? this.pushClassPrivateProperty(e, l) : this.pushClassProperty(e, o) : this.unexpected();
  }
  parseClassElementName(e) {
    const {
      type: t,
      value: s,
      start: i
    } = this.state;
    if ((t === 122 || t === 123) && e.static && s === "prototype" && this.raise(i, I.StaticPrototype), t === 128) {
      s === "constructor" && this.raise(i, I.ConstructorClassPrivateField);
      const n = this.parsePrivateName();
      return e.key = n, n;
    }
    return this.parsePropertyName(e);
  }
  parseClassStaticBlock(e, t) {
    var s;
    this.scope.enter(qr | ep | kn);
    const i = this.state.labels;
    this.state.labels = [], this.prodParam.enter(ms);
    const n = t.body = [];
    this.parseBlockOrModuleBlockBody(n, void 0, !1, 8), this.prodParam.exit(), this.scope.exit(), this.state.labels = i, e.body.push(this.finishNode(t, "StaticBlock")), (s = t.decorators) != null && s.length && this.raise(t.start, I.DecoratorStaticBlock);
  }
  pushClassProperty(e, t) {
    !t.computed && (t.key.name === "constructor" || t.key.value === "constructor") && this.raise(t.key.start, I.ConstructorClassField), e.body.push(this.parseClassProperty(t));
  }
  pushClassPrivateProperty(e, t) {
    const s = this.parseClassPrivateProperty(t);
    e.body.push(s), this.classScope.declarePrivateName(this.getPrivateNameSV(s.key), Ac, s.key.start);
  }
  pushClassMethod(e, t, s, i, n, a) {
    e.body.push(this.parseMethod(t, s, i, n, a, "ClassMethod", !0));
  }
  pushClassPrivateMethod(e, t, s, i) {
    const n = this.parseMethod(t, s, i, !1, !1, "ClassPrivateMethod", !0);
    e.body.push(n);
    const a = n.kind === "get" ? n.static ? p1 : m1 : n.kind === "set" ? n.static ? d1 : y1 : Ac;
    this.declareClassPrivateMethodInScope(n, a);
  }
  declareClassPrivateMethodInScope(e, t) {
    this.classScope.declarePrivateName(this.getPrivateNameSV(e.key), t, e.key.start);
  }
  parsePostMemberNameModifiers(e) {
  }
  parseClassPrivateProperty(e) {
    return this.parseInitializer(e), this.semicolon(), this.finishNode(e, "ClassPrivateProperty");
  }
  parseClassProperty(e) {
    return this.parseInitializer(e), this.semicolon(), this.finishNode(e, "ClassProperty");
  }
  parseInitializer(e) {
    this.scope.enter(qr | kn), this.expressionScope.enter(up()), this.prodParam.enter(ms), e.value = this.eat(27) ? this.parseMaybeAssignAllowIn() : null, this.expressionScope.exit(), this.prodParam.exit(), this.scope.exit();
  }
  parseClassId(e, t, s, i = sp) {
    Se(this.state.type) ? (e.id = this.parseIdentifier(), t && this.checkLVal(e.id, "class name", i)) : s || !t ? e.id = null : this.unexpected(null, I.MissingClassName);
  }
  parseClassSuper(e) {
    e.superClass = this.eat(75) ? this.parseExprSubscripts() : null;
  }
  parseExport(e) {
    const t = this.maybeParseExportDefaultSpecifier(e), s = !t || this.eat(12), i = s && this.eatExportStar(e), n = i && this.maybeParseExportNamespaceSpecifier(e), a = s && (!n || this.eat(12)), o = t || i;
    if (i && !n)
      return t && this.unexpected(), this.parseExportFrom(e, !0), this.finishNode(e, "ExportAllDeclaration");
    const l = this.maybeParseExportNamedSpecifiers(e);
    if (t && s && !i && !l || n && a && !l)
      throw this.unexpected(null, 5);
    let u;
    if (o || l ? (u = !1, this.parseExportFrom(e, o)) : u = this.maybeParseExportDeclaration(e), o || l || u)
      return this.checkExport(e, !0, !1, !!e.source), this.finishNode(e, "ExportNamedDeclaration");
    if (this.eat(59))
      return e.declaration = this.parseExportDefaultExpression(), this.checkExport(e, !0, !0), this.finishNode(e, "ExportDefaultDeclaration");
    throw this.unexpected(null, 5);
  }
  eatExportStar(e) {
    return this.eat(49);
  }
  maybeParseExportDefaultSpecifier(e) {
    if (this.isExportDefaultSpecifier()) {
      this.expectPlugin("exportDefaultFrom");
      const t = this.startNode();
      return t.exported = this.parseIdentifier(!0), e.specifiers = [this.finishNode(t, "ExportDefaultSpecifier")], !0;
    }
    return !1;
  }
  maybeParseExportNamespaceSpecifier(e) {
    if (this.isContextual(87)) {
      e.specifiers || (e.specifiers = []);
      const t = this.startNodeAt(this.state.lastTokStart, this.state.lastTokStartLoc);
      return this.next(), t.exported = this.parseModuleExportName(), e.specifiers.push(this.finishNode(t, "ExportNamespaceSpecifier")), !0;
    }
    return !1;
  }
  maybeParseExportNamedSpecifiers(e) {
    if (this.match(5)) {
      e.specifiers || (e.specifiers = []);
      const t = e.exportKind === "type";
      return e.specifiers.push(...this.parseExportSpecifiers(t)), e.source = null, e.declaration = null, this.hasPlugin("importAssertions") && (e.assertions = []), !0;
    }
    return !1;
  }
  maybeParseExportDeclaration(e) {
    return this.shouldParseExportDeclaration() ? (e.specifiers = [], e.source = null, this.hasPlugin("importAssertions") && (e.assertions = []), e.declaration = this.parseExportDeclaration(e), !0) : !1;
  }
  isAsyncFunction() {
    if (!this.isContextual(89))
      return !1;
    const e = this.nextTokenStart();
    return !Rl.test(this.input.slice(this.state.pos, e)) && this.isUnparsedContextual(e, "function");
  }
  parseExportDefaultExpression() {
    const e = this.startNode(), t = this.isAsyncFunction();
    if (this.match(62) || t)
      return this.next(), t && this.next(), this.parseFunction(e, Va | Fc, t);
    if (this.match(74))
      return this.parseClass(e, !0, !0);
    if (this.match(24))
      return this.hasPlugin("decorators") && this.getPluginOption("decorators", "decoratorsBeforeExport") && this.raise(this.state.start, I.DecoratorBeforeExport), this.parseDecorators(!1), this.parseClass(e, !0, !0);
    if (this.match(69) || this.match(68) || this.isLet())
      throw this.raise(this.state.start, I.UnsupportedDefaultExport);
    {
      const s = this.parseMaybeAssignAllowIn();
      return this.semicolon(), s;
    }
  }
  parseExportDeclaration(e) {
    return this.parseStatement(null);
  }
  isExportDefaultSpecifier() {
    const {
      type: e
    } = this.state;
    if (Se(e)) {
      if (e === 89 && !this.state.containsEsc || e === 93)
        return !1;
      if ((e === 120 || e === 119) && !this.state.containsEsc) {
        const {
          type: i
        } = this.lookahead();
        if (Se(i) && i !== 91 || i === 5)
          return this.expectOnePlugin(["flow", "typescript"]), !1;
      }
    } else if (!this.match(59))
      return !1;
    const t = this.nextTokenStart(), s = this.isUnparsedContextual(t, "from");
    if (this.input.charCodeAt(t) === 44 || Se(this.state.type) && s)
      return !0;
    if (this.match(59) && s) {
      const i = this.input.charCodeAt(this.nextTokenStartSince(t + 4));
      return i === 34 || i === 39;
    }
    return !1;
  }
  parseExportFrom(e, t) {
    if (this.eatContextual(91)) {
      e.source = this.parseImportSource(), this.checkExport(e);
      const s = this.maybeParseImportAssertions();
      s && (e.assertions = s);
    } else
      t && this.unexpected();
    this.semicolon();
  }
  shouldParseExportDeclaration() {
    const {
      type: e
    } = this.state;
    if (e === 24 && (this.expectOnePlugin(["decorators", "decorators-legacy"]), this.hasPlugin("decorators")))
      if (this.getPluginOption("decorators", "decoratorsBeforeExport"))
        this.unexpected(this.state.start, I.DecoratorBeforeExport);
      else
        return !0;
    return e === 68 || e === 69 || e === 62 || e === 74 || this.isLet() || this.isAsyncFunction();
  }
  checkExport(e, t, s, i) {
    if (t) {
      if (s) {
        if (this.checkDuplicateExports(e, "default"), this.hasPlugin("exportDefaultFrom")) {
          var n;
          const o = e.declaration;
          o.type === "Identifier" && o.name === "from" && o.end - o.start === 4 && !((n = o.extra) != null && n.parenthesized) && this.raise(o.start, I.ExportDefaultFromAsIdentifier);
        }
      } else if (e.specifiers && e.specifiers.length)
        for (const o of e.specifiers) {
          const {
            exported: l
          } = o, u = l.type === "Identifier" ? l.name : l.value;
          if (this.checkDuplicateExports(o, u), !i && o.local) {
            const {
              local: c
            } = o;
            c.type !== "Identifier" ? this.raise(o.start, I.ExportBindingIsString, c.value, u) : (this.checkReservedWord(c.name, c.start, !0, !1), this.scope.checkLocalExport(c));
          }
        }
      else if (e.declaration) {
        if (e.declaration.type === "FunctionDeclaration" || e.declaration.type === "ClassDeclaration") {
          const o = e.declaration.id;
          if (!o)
            throw new Error("Assertion failure");
          this.checkDuplicateExports(e, o.name);
        } else if (e.declaration.type === "VariableDeclaration")
          for (const o of e.declaration.declarations)
            this.checkDeclaration(o.id);
      }
    }
    if (this.state.decoratorStack[this.state.decoratorStack.length - 1].length)
      throw this.raise(e.start, I.UnsupportedDecoratorExport);
  }
  checkDeclaration(e) {
    if (e.type === "Identifier")
      this.checkDuplicateExports(e, e.name);
    else if (e.type === "ObjectPattern")
      for (const t of e.properties)
        this.checkDeclaration(t);
    else if (e.type === "ArrayPattern")
      for (const t of e.elements)
        t && this.checkDeclaration(t);
    else
      e.type === "ObjectProperty" ? this.checkDeclaration(e.value) : e.type === "RestElement" ? this.checkDeclaration(e.argument) : e.type === "AssignmentPattern" && this.checkDeclaration(e.left);
  }
  checkDuplicateExports(e, t) {
    this.exportedIdentifiers.has(t) && this.raise(e.start, t === "default" ? I.DuplicateDefaultExport : I.DuplicateExport, t), this.exportedIdentifiers.add(t);
  }
  parseExportSpecifiers(e) {
    const t = [];
    let s = !0;
    for (this.expect(5); !this.eat(8); ) {
      if (s)
        s = !1;
      else if (this.expect(12), this.eat(8))
        break;
      const i = this.isContextual(120), n = this.match(123), a = this.startNode();
      a.local = this.parseModuleExportName(), t.push(this.parseExportSpecifier(a, n, e, i));
    }
    return t;
  }
  parseExportSpecifier(e, t, s, i) {
    return this.eatContextual(87) ? e.exported = this.parseModuleExportName() : t ? e.exported = k1(e.local) : e.exported || (e.exported = tr(e.local)), this.finishNode(e, "ExportSpecifier");
  }
  parseModuleExportName() {
    if (this.match(123)) {
      const e = this.parseStringLiteral(this.state.value), t = e.value.match(nv);
      return t && this.raise(e.start, I.ModuleExportNameHasLoneSurrogate, t[0].charCodeAt(0).toString(16)), e;
    }
    return this.parseIdentifier(!0);
  }
  parseImport(e) {
    if (e.specifiers = [], !this.match(123)) {
      const i = !this.maybeParseDefaultImportSpecifier(e) || this.eat(12), n = i && this.maybeParseStarImportSpecifier(e);
      i && !n && this.parseNamedImportSpecifiers(e), this.expectContextual(91);
    }
    e.source = this.parseImportSource();
    const t = this.maybeParseImportAssertions();
    if (t)
      e.assertions = t;
    else {
      const s = this.maybeParseModuleAttributes();
      s && (e.attributes = s);
    }
    return this.semicolon(), this.finishNode(e, "ImportDeclaration");
  }
  parseImportSource() {
    return this.match(123) || this.unexpected(), this.parseExprAtom();
  }
  shouldParseDefaultImport(e) {
    return Se(this.state.type);
  }
  parseImportSpecifierLocal(e, t, s, i) {
    t.local = this.parseIdentifier(), this.checkLVal(t.local, i, yt), e.specifiers.push(this.finishNode(t, s));
  }
  parseAssertEntries() {
    const e = [], t = /* @__PURE__ */ new Set();
    do {
      if (this.match(8))
        break;
      const s = this.startNode(), i = this.state.value;
      if (t.has(i) && this.raise(this.state.start, I.ModuleAttributesWithDuplicateKeys, i), t.add(i), this.match(123) ? s.key = this.parseStringLiteral(i) : s.key = this.parseIdentifier(!0), this.expect(14), !this.match(123))
        throw this.unexpected(this.state.start, I.ModuleAttributeInvalidValue);
      s.value = this.parseStringLiteral(this.state.value), this.finishNode(s, "ImportAttribute"), e.push(s);
    } while (this.eat(12));
    return e;
  }
  maybeParseModuleAttributes() {
    if (this.match(70) && !this.hasPrecedingLineBreak())
      this.expectPlugin("moduleAttributes"), this.next();
    else
      return this.hasPlugin("moduleAttributes") ? [] : null;
    const e = [], t = /* @__PURE__ */ new Set();
    do {
      const s = this.startNode();
      if (s.key = this.parseIdentifier(!0), s.key.name !== "type" && this.raise(s.key.start, I.ModuleAttributeDifferentFromType, s.key.name), t.has(s.key.name) && this.raise(s.key.start, I.ModuleAttributesWithDuplicateKeys, s.key.name), t.add(s.key.name), this.expect(14), !this.match(123))
        throw this.unexpected(this.state.start, I.ModuleAttributeInvalidValue);
      s.value = this.parseStringLiteral(this.state.value), this.finishNode(s, "ImportAttribute"), e.push(s);
    } while (this.eat(12));
    return e;
  }
  maybeParseImportAssertions() {
    if (this.isContextual(88) && !this.hasPrecedingLineBreak())
      this.expectPlugin("importAssertions"), this.next();
    else
      return this.hasPlugin("importAssertions") ? [] : null;
    this.eat(5);
    const e = this.parseAssertEntries();
    return this.eat(8), e;
  }
  maybeParseDefaultImportSpecifier(e) {
    return this.shouldParseDefaultImport(e) ? (this.parseImportSpecifierLocal(e, this.startNode(), "ImportDefaultSpecifier", "default import specifier"), !0) : !1;
  }
  maybeParseStarImportSpecifier(e) {
    if (this.match(49)) {
      const t = this.startNode();
      return this.next(), this.expectContextual(87), this.parseImportSpecifierLocal(e, t, "ImportNamespaceSpecifier", "import namespace specifier"), !0;
    }
    return !1;
  }
  parseNamedImportSpecifiers(e) {
    let t = !0;
    for (this.expect(5); !this.eat(8); ) {
      if (t)
        t = !1;
      else {
        if (this.eat(14))
          throw this.raise(this.state.start, I.DestructureNamedImport);
        if (this.expect(12), this.eat(8))
          break;
      }
      const s = this.startNode(), i = this.match(123), n = this.isContextual(120);
      s.imported = this.parseModuleExportName();
      const a = this.parseImportSpecifier(s, i, e.importKind === "type" || e.importKind === "typeof", n);
      e.specifiers.push(a);
    }
  }
  parseImportSpecifier(e, t, s, i) {
    if (this.eatContextual(87))
      e.local = this.parseIdentifier();
    else {
      const {
        imported: n
      } = e;
      if (t)
        throw this.raise(e.start, I.ImportBindingIsString, n.value);
      this.checkReservedWord(n.name, e.start, !0, !0), e.local || (e.local = tr(n));
    }
    return this.checkLVal(e.local, "import specifier", yt), this.finishNode(e, "ImportSpecifier");
  }
  isThisParam(e) {
    return e.type === "Identifier" && e.name === "this";
  }
}
class pp extends ov {
  constructor(e, t) {
    e = ev(e), super(e, t), this.options = e, this.initializeScopes(), this.plugins = lv(this.options.plugins), this.filename = e.sourceFilename;
  }
  getScopeHandler() {
    return Ql;
  }
  parse() {
    this.enterInitialScopes();
    const e = this.startNode(), t = this.startNode();
    return this.nextToken(), e.errors = null, this.parseTopLevel(e, t), e.errors = this.state.errors, e;
  }
}
function lv(r) {
  const e = /* @__PURE__ */ new Map();
  for (const t of r) {
    const [s, i] = Array.isArray(t) ? t : [t, {}];
    e.has(s) || e.set(s, i || {});
  }
  return e;
}
function uv(r, e) {
  var t;
  if (((t = e) == null ? void 0 : t.sourceType) === "unambiguous") {
    e = Object.assign({}, e);
    try {
      e.sourceType = "module";
      const s = Js(e, r), i = s.parse();
      if (s.sawUnambiguousESM)
        return i;
      if (s.ambiguousScriptDifferentAst)
        try {
          return e.sourceType = "script", Js(e, r).parse();
        } catch (n) {
        }
      else
        i.program.sourceType = "script";
      return i;
    } catch (s) {
      try {
        return e.sourceType = "script", Js(e, r).parse();
      } catch (i) {
      }
      throw s;
    }
  } else
    return Js(e, r).parse();
}
function cv(r, e) {
  const t = Js(e, r);
  return t.options.strictMode && (t.state.strict = !0), t.getExpression();
}
function hv(r) {
  const e = {};
  for (const t of Object.keys(r))
    e[t] = ln(r[t]);
  return e;
}
hv(U0);
function Js(r, e) {
  let t = pp;
  return r != null && r.plugins && (X1(r.plugins), t = fv(r.plugins)), new t(r, e);
}
const Bc = {};
function fv(r) {
  const e = Z1.filter((i) => Ye(r, i)), t = e.join("/");
  let s = Bc[t];
  if (!s) {
    s = pp;
    for (const i of e)
      s = fp[i](s);
    Bc[t] = s;
  }
  return s;
}
var pv = uv, dv = cv;
const ht = (r) => r.type === 4 && r.isStatic, Qs = (r, e) => r === e || r === v0(e);
function mv(r) {
  if (Qs(r, "Teleport"))
    return si;
  if (Qs(r, "Suspense"))
    return Cl;
  if (Qs(r, "KeepAlive"))
    return Tn;
  if (Qs(r, "BaseTransition"))
    return Cf;
}
const yv = /^\d|[^\$\w]/, Fn = (r) => !yv.test(r), gv = (r, e) => {
  try {
    let t = dv(r, {
      plugins: e.expressionPlugins
    });
    return (t.type === "TSAsExpression" || t.type === "TSTypeAssertion") && (t = t.expression), t.type === "MemberExpression" || t.type === "OptionalMemberExpression" || t.type === "Identifier";
  } catch (t) {
    return !1;
  }
}, dp = gv;
function mp(r, e, t) {
  const i = {
    source: r.source.slice(e, e + t),
    start: Bn(r.start, r.source, e),
    end: r.end
  };
  return t != null && (i.end = Bn(r.start, r.source, e + t)), i;
}
function Bn(r, e, t = e.length) {
  return vv(h0({}, r), e, t);
}
function vv(r, e, t = e.length) {
  let s = 0, i = -1;
  for (let n = 0; n < t; n++)
    e.charCodeAt(n) === 10 && (s++, i = n);
  return r.offset += t, r.line += s, r.column = i === -1 ? r.column + t : t - i, r;
}
function ut(r, e, t = !1) {
  for (let s = 0; s < r.props.length; s++) {
    const i = r.props[s];
    if (i.type === 7 && (t || i.exp) && (St(e) ? i.name === e : e.test(i.name)))
      return i;
  }
}
function Es(r, e, t = !1, s = !1) {
  for (let i = 0; i < r.props.length; i++) {
    const n = r.props[i];
    if (n.type === 6) {
      if (t)
        continue;
      if (n.name === e && (n.value || s))
        return n;
    } else if (n.name === "bind" && (n.exp || s) && os(n.arg, e))
      return n;
  }
}
function os(r, e) {
  return !!(r && ht(r) && r.content === e);
}
function bv(r) {
  return r.props.some(
    (e) => e.type === 7 && e.name === "bind" && (!e.arg || e.arg.type !== 4 || !e.arg.isStatic)
  );
}
function Wa(r) {
  return r.type === 5 || r.type === 2;
}
function yp(r) {
  return r.type === 7 && r.name === "slot";
}
function Si(r) {
  return r.type === 1 && r.tagType === 3;
}
function Mo(r) {
  return r.type === 1 && r.tagType === 2;
}
function wi(r, e) {
  return r || e ? Of : kf;
}
function Pi(r, e) {
  return r || e ? If : Nf;
}
const xv = /* @__PURE__ */ new Set([gi, Li]);
function gp(r, e = []) {
  if (r && !St(r) && r.type === 14) {
    const t = r.callee;
    if (!St(t) && xv.has(t))
      return gp(r.arguments[0], e.concat(r));
  }
  return [r, e];
}
function Un(r, e, t) {
  let s, i = r.type === 13 ? r.props : r.arguments[2], n = [], a;
  if (i && !St(i) && i.type === 14) {
    const o = gp(i);
    i = o[0], n = o[1], a = n[n.length - 1];
  }
  if (i == null || St(i))
    s = gt([e]);
  else if (i.type === 14) {
    const o = i.arguments[0];
    !St(o) && o.type === 15 ? o.properties.unshift(e) : i.callee === Ll ? s = $e(t.helper(_n), [
      gt([e]),
      i
    ]) : i.arguments.unshift(gt([e])), !s && (s = i);
  } else if (i.type === 15) {
    let o = !1;
    if (e.key.type === 4) {
      const l = e.key.content;
      o = i.properties.some((u) => u.key.type === 4 && u.key.content === l);
    }
    o || i.properties.unshift(e), s = i;
  } else
    s = $e(t.helper(_n), [
      gt([e]),
      i
    ]), a && a.callee === Li && (a = n[n.length - 2]);
  r.type === 13 ? a ? a.arguments[0] = s : r.props = s : a ? a.arguments[0] = s : r.arguments[2] = s;
}
function Lo(r, e) {
  return `_${e}_${r.replace(/[^\w]/g, (t, s) => t === "-" ? "_" : r.charCodeAt(s).toString())}`;
}
function ot(r, e) {
  if (!r || Object.keys(e).length === 0)
    return !1;
  switch (r.type) {
    case 1:
      for (let t = 0; t < r.props.length; t++) {
        const s = r.props[t];
        if (s.type === 7 && (ot(s.arg, e) || ot(s.exp, e)))
          return !0;
      }
      return r.children.some((t) => ot(t, e));
    case 11:
      return ot(r.source, e) ? !0 : r.children.some((t) => ot(t, e));
    case 9:
      return r.branches.some((t) => ot(t, e));
    case 10:
      return ot(r.condition, e) ? !0 : r.children.some((t) => ot(t, e));
    case 4:
      return !r.isStatic && Fn(r.content) && !!e[r.content];
    case 8:
      return r.children.some((t) => _f(t) && ot(t, e));
    case 5:
    case 12:
      return ot(r.content, e);
    case 2:
    case 3:
      return !1;
    default:
      return !1;
  }
}
function Sv(r) {
  return r.type === 14 && r.callee === Dl ? r.arguments[1].returns : r;
}
function vp(r, { helper: e, removeHelper: t, inSSR: s }) {
  r.isBlock || (r.isBlock = !0, t(wi(s, r.isComponent)), e(Ss), e(Pi(s, r.isComponent)));
}
function Ct(r, e) {
  const { constantCache: t } = e;
  switch (r.type) {
    case 1:
      if (r.tagType !== 0)
        return 0;
      const s = t.get(r);
      if (s !== void 0)
        return s;
      const i = r.codegenNode;
      if (i.type !== 13 || i.isBlock && r.tag !== "svg" && r.tag !== "foreignObject")
        return 0;
      if (Tv(i))
        return t.set(r, 0), 0;
      {
        let o = 3;
        const l = Pv(r, e);
        if (l === 0)
          return t.set(r, 0), 0;
        l < o && (o = l);
        for (let u = 0; u < r.children.length; u++) {
          const c = Ct(r.children[u], e);
          if (c === 0)
            return t.set(r, 0), 0;
          c < o && (o = c);
        }
        if (o > 1)
          for (let u = 0; u < r.props.length; u++) {
            const c = r.props[u];
            if (c.type === 7 && c.name === "bind" && c.exp) {
              const h = Ct(c.exp, e);
              if (h === 0)
                return t.set(r, 0), 0;
              h < o && (o = h);
            }
          }
        if (i.isBlock) {
          for (let u = 0; u < r.props.length; u++)
            if (r.props[u].type === 7)
              return t.set(r, 0), 0;
          e.removeHelper(Ss), e.removeHelper(Pi(e.inSSR, i.isComponent)), i.isBlock = !1, e.helper(wi(e.inSSR, i.isComponent));
        }
        return t.set(r, o), o;
      }
    case 2:
    case 3:
      return 3;
    case 9:
    case 11:
    case 10:
      return 0;
    case 5:
    case 12:
      return Ct(r.content, e);
    case 4:
      return r.constType;
    case 8:
      let a = 3;
      for (let o = 0; o < r.children.length; o++) {
        const l = r.children[o];
        if (St(l) || Af(l))
          continue;
        const u = Ct(l, e);
        if (u === 0)
          return 0;
        u < a && (a = u);
      }
      return a;
    default:
      return 0;
  }
}
const wv = /* @__PURE__ */ new Set([
  kl,
  Ml,
  gi,
  Li
]);
function bp(r, e) {
  if (r.type === 14 && !St(r.callee) && wv.has(r.callee)) {
    const t = r.arguments[0];
    if (t.type === 4)
      return Ct(t, e);
    if (t.type === 14)
      return bp(t, e);
  }
  return 0;
}
function Pv(r, e) {
  let t = 3;
  const s = Ev(r);
  if (s && s.type === 15) {
    const { properties: i } = s;
    for (let n = 0; n < i.length; n++) {
      const { key: a, value: o } = i[n], l = Ct(a, e);
      if (l === 0)
        return l;
      l < t && (t = l);
      let u;
      if (o.type === 4 ? u = Ct(o, e) : o.type === 14 ? u = bp(o, e) : u = 0, u === 0)
        return u;
      u < t && (t = u);
    }
  }
  return t;
}
function Ev(r) {
  const e = r.codegenNode;
  if (e.type === 13)
    return e.props;
}
function Tv(r) {
  const e = r.patchFlag;
  return e ? parseInt(e, 10) : void 0;
}
function Av(r, e) {
  let t = 0;
  const s = () => {
    t--;
  };
  for (; t < r.children.length; t++) {
    const i = r.children[t];
    St(i) || (e.parent = r, e.childIndex = t, e.onNodeRemoved = s, tu(i, e));
  }
}
function tu(r, e) {
  e.currentNode = r;
  const { nodeTransforms: t } = e, s = [];
  for (let n = 0; n < t.length; n++) {
    const a = t[n](r, e);
    if (a && (d0(a) ? s.push(...a) : s.push(a)), e.currentNode)
      r = e.currentNode;
    else
      return;
  }
  switch (r.type) {
    case 3:
      e.ssr || e.helper(Il);
      break;
    case 5:
      e.ssr || e.helper(Ol);
      break;
    case 9:
      for (let n = 0; n < r.branches.length; n++)
        tu(r.branches[n], e);
      break;
    case 10:
    case 11:
    case 1:
    case 0:
      Av(r, e);
      break;
  }
  e.currentNode = r;
  let i = s.length;
  for (; i--; )
    s[i]();
}
function xp(r, e) {
  const t = St(r) ? (s) => s === r : (s) => r.test(s);
  return (s, i) => {
    if (s.type === 1) {
      const { props: n } = s;
      if (s.tagType === 3 && n.some(yp))
        return;
      const a = [];
      for (let o = 0; o < n.length; o++) {
        const l = n[o];
        if (l.type === 7 && t(l.name)) {
          n.splice(o, 1), o--;
          const u = e(s, l, i);
          u && a.push(u);
        }
      }
      return a;
    }
  };
}
var Uc = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/".split(""), _v = function(r) {
  if (0 <= r && r < Uc.length)
    return Uc[r];
  throw new TypeError("Must be between 0 and 63: " + r);
}, Cv = function(r) {
  var e = 65, t = 90, s = 97, i = 122, n = 48, a = 57, o = 43, l = 47, u = 26, c = 52;
  return e <= r && r <= t ? r - e : s <= r && r <= i ? r - s + u : n <= r && r <= a ? r - n + c : r == o ? 62 : r == l ? 63 : -1;
}, Sp = {
  encode: _v,
  decode: Cv
}, ru = 5, wp = 1 << ru, Pp = wp - 1, Ep = wp;
function Iv(r) {
  return r < 0 ? (-r << 1) + 1 : (r << 1) + 0;
}
function Nv(r) {
  var e = (r & 1) === 1, t = r >> 1;
  return e ? -t : t;
}
var Ov = function(e) {
  var t = "", s, i = Iv(e);
  do
    s = i & Pp, i >>>= ru, i > 0 && (s |= Ep), t += Sp.encode(s);
  while (i > 0);
  return t;
}, kv = function(e, t, s) {
  var i = e.length, n = 0, a = 0, o, l;
  do {
    if (t >= i)
      throw new Error("Expected more digits in base 64 VLQ value.");
    if (l = Sp.decode(e.charCodeAt(t++)), l === -1)
      throw new Error("Invalid base64 digit: " + e.charAt(t - 1));
    o = !!(l & Ep), l &= Pp, n = n + (l << a), a += ru;
  } while (o);
  s.value = Nv(n), s.rest = t;
}, Mv = {
  encode: Ov,
  decode: kv
};
function Di(r) {
  if (r.__esModule)
    return r;
  var e = Object.defineProperty({}, "__esModule", { value: !0 });
  return Object.keys(r).forEach(function(t) {
    var s = Object.getOwnPropertyDescriptor(r, t);
    Object.defineProperty(e, t, s.get ? s : {
      enumerable: !0,
      get: function() {
        return r[t];
      }
    });
  }), e;
}
function Pe(r) {
  var e = { exports: {} };
  return r(e, e.exports), e.exports;
}
var Z = Pe(function(r, e) {
  function t(v, w, N) {
    if (w in v)
      return v[w];
    if (arguments.length === 3)
      return N;
    throw new Error('"' + w + '" is a required argument.');
  }
  e.getArg = t;
  var s = /^(?:([\w+\-.]+):)?\/\/(?:(\w+:\w+)@)?([\w.-]*)(?::(\d+))?(.*)$/, i = /^data:.+\,.+$/;
  function n(v) {
    var w = v.match(s);
    return w ? {
      scheme: w[1],
      auth: w[2],
      host: w[3],
      port: w[4],
      path: w[5]
    } : null;
  }
  e.urlParse = n;
  function a(v) {
    var w = "";
    return v.scheme && (w += v.scheme + ":"), w += "//", v.auth && (w += v.auth + "@"), v.host && (w += v.host), v.port && (w += ":" + v.port), v.path && (w += v.path), w;
  }
  e.urlGenerate = a;
  function o(v) {
    var w = v, N = n(v);
    if (N) {
      if (!N.path)
        return v;
      w = N.path;
    }
    for (var P = e.isAbsolute(w), g = w.split(/\/+/), E, O = 0, S = g.length - 1; S >= 0; S--)
      E = g[S], E === "." ? g.splice(S, 1) : E === ".." ? O++ : O > 0 && (E === "" ? (g.splice(S + 1, O), O = 0) : (g.splice(S, 2), O--));
    return w = g.join("/"), w === "" && (w = P ? "/" : "."), N ? (N.path = w, a(N)) : w;
  }
  e.normalize = o;
  function l(v, w) {
    v === "" && (v = "."), w === "" && (w = ".");
    var N = n(w), P = n(v);
    if (P && (v = P.path || "/"), N && !N.scheme)
      return P && (N.scheme = P.scheme), a(N);
    if (N || w.match(i))
      return w;
    if (P && !P.host && !P.path)
      return P.host = w, a(P);
    var g = w.charAt(0) === "/" ? w : o(v.replace(/\/+$/, "") + "/" + w);
    return P ? (P.path = g, a(P)) : g;
  }
  e.join = l, e.isAbsolute = function(v) {
    return v.charAt(0) === "/" || s.test(v);
  };
  function u(v, w) {
    v === "" && (v = "."), v = v.replace(/\/$/, "");
    for (var N = 0; w.indexOf(v + "/") !== 0; ) {
      var P = v.lastIndexOf("/");
      if (P < 0 || (v = v.slice(0, P), v.match(/^([^\/]+:\/)?\/*$/)))
        return w;
      ++N;
    }
    return Array(N + 1).join("../") + w.substr(v.length + 1);
  }
  e.relative = u;
  var c = function() {
    var v = /* @__PURE__ */ Object.create(null);
    return !("__proto__" in v);
  }();
  function h(v) {
    return v;
  }
  function f(v) {
    return x(v) ? "$" + v : v;
  }
  e.toSetString = c ? h : f;
  function p(v) {
    return x(v) ? v.slice(1) : v;
  }
  e.fromSetString = c ? h : p;
  function x(v) {
    if (!v)
      return !1;
    var w = v.length;
    if (w < 9 || v.charCodeAt(w - 1) !== 95 || v.charCodeAt(w - 2) !== 95 || v.charCodeAt(w - 3) !== 111 || v.charCodeAt(w - 4) !== 116 || v.charCodeAt(w - 5) !== 111 || v.charCodeAt(w - 6) !== 114 || v.charCodeAt(w - 7) !== 112 || v.charCodeAt(w - 8) !== 95 || v.charCodeAt(w - 9) !== 95)
      return !1;
    for (var N = w - 10; N >= 0; N--)
      if (v.charCodeAt(N) !== 36)
        return !1;
    return !0;
  }
  function d(v, w, N) {
    var P = y(v.source, w.source);
    return P !== 0 || (P = v.originalLine - w.originalLine, P !== 0) || (P = v.originalColumn - w.originalColumn, P !== 0 || N) || (P = v.generatedColumn - w.generatedColumn, P !== 0) || (P = v.generatedLine - w.generatedLine, P !== 0) ? P : y(v.name, w.name);
  }
  e.compareByOriginalPositions = d;
  function m(v, w, N) {
    var P = v.generatedLine - w.generatedLine;
    return P !== 0 || (P = v.generatedColumn - w.generatedColumn, P !== 0 || N) || (P = y(v.source, w.source), P !== 0) || (P = v.originalLine - w.originalLine, P !== 0) || (P = v.originalColumn - w.originalColumn, P !== 0) ? P : y(v.name, w.name);
  }
  e.compareByGeneratedPositionsDeflated = m;
  function y(v, w) {
    return v === w ? 0 : v === null ? 1 : w === null ? -1 : v > w ? 1 : -1;
  }
  function _(v, w) {
    var N = v.generatedLine - w.generatedLine;
    return N !== 0 || (N = v.generatedColumn - w.generatedColumn, N !== 0) || (N = y(v.source, w.source), N !== 0) || (N = v.originalLine - w.originalLine, N !== 0) || (N = v.originalColumn - w.originalColumn, N !== 0) ? N : y(v.name, w.name);
  }
  e.compareByGeneratedPositionsInflated = _;
  function T(v) {
    return JSON.parse(v.replace(/^\)]}'[^\n]*\n/, ""));
  }
  e.parseSourceMapInput = T;
  function C(v, w, N) {
    if (w = w || "", v && (v[v.length - 1] !== "/" && w[0] !== "/" && (v += "/"), w = v + w), N) {
      var P = n(N);
      if (!P)
        throw new Error("sourceMapURL could not be parsed");
      if (P.path) {
        var g = P.path.lastIndexOf("/");
        g >= 0 && (P.path = P.path.substring(0, g + 1));
      }
      w = l(a(P), w);
    }
    return o(w);
  }
  e.computeSourceURL = C;
}), su = Object.prototype.hasOwnProperty, Kr = typeof Map != "undefined";
function ir() {
  this._array = [], this._set = Kr ? /* @__PURE__ */ new Map() : /* @__PURE__ */ Object.create(null);
}
ir.fromArray = function(e, t) {
  for (var s = new ir(), i = 0, n = e.length; i < n; i++)
    s.add(e[i], t);
  return s;
};
ir.prototype.size = function() {
  return Kr ? this._set.size : Object.getOwnPropertyNames(this._set).length;
};
ir.prototype.add = function(e, t) {
  var s = Kr ? e : Z.toSetString(e), i = Kr ? this.has(e) : su.call(this._set, s), n = this._array.length;
  (!i || t) && this._array.push(e), i || (Kr ? this._set.set(e, n) : this._set[s] = n);
};
ir.prototype.has = function(e) {
  if (Kr)
    return this._set.has(e);
  var t = Z.toSetString(e);
  return su.call(this._set, t);
};
ir.prototype.indexOf = function(e) {
  if (Kr) {
    var t = this._set.get(e);
    if (t >= 0)
      return t;
  } else {
    var s = Z.toSetString(e);
    if (su.call(this._set, s))
      return this._set[s];
  }
  throw new Error('"' + e + '" is not in the set.');
};
ir.prototype.at = function(e) {
  if (e >= 0 && e < this._array.length)
    return this._array[e];
  throw new Error("No element indexed by " + e);
};
ir.prototype.toArray = function() {
  return this._array.slice();
};
var Lv = ir, Dv = {
  ArraySet: Lv
}, iu = Pe(function(r, e) {
  e.GREATEST_LOWER_BOUND = 1, e.LEAST_UPPER_BOUND = 2;
  function t(s, i, n, a, o, l) {
    var u = Math.floor((i - s) / 2) + s, c = o(n, a[u], !0);
    return c === 0 ? u : c > 0 ? i - u > 1 ? t(u, i, n, a, o, l) : l == e.LEAST_UPPER_BOUND ? i < a.length ? i : -1 : u : u - s > 1 ? t(s, u, n, a, o, l) : l == e.LEAST_UPPER_BOUND ? u : s < 0 ? -1 : s;
  }
  e.search = function(i, n, a, o) {
    if (n.length === 0)
      return -1;
    var l = t(
      -1,
      n.length,
      i,
      n,
      a,
      o || e.GREATEST_LOWER_BOUND
    );
    if (l < 0)
      return -1;
    for (; l - 1 >= 0 && a(n[l], n[l - 1], !0) === 0; )
      --l;
    return l;
  };
});
function Ha(r, e, t) {
  var s = r[e];
  r[e] = r[t], r[t] = s;
}
function Rv(r, e) {
  return Math.round(r + Math.random() * (e - r));
}
function Do(r, e, t, s) {
  if (t < s) {
    var i = Rv(t, s), n = t - 1;
    Ha(r, i, s);
    for (var a = r[s], o = t; o < s; o++)
      e(r[o], a) <= 0 && (n += 1, Ha(r, n, o));
    Ha(r, n + 1, o);
    var l = n + 1;
    Do(r, e, t, l - 1), Do(r, e, l + 1, s);
  }
}
var Fv = function(r, e) {
  Do(r, e, 0, r.length - 1);
}, Bv = {
  quickSort: Fv
}, Ts = Dv.ArraySet, Ei = Bv.quickSort;
function Re(r, e) {
  var t = r;
  return typeof r == "string" && (t = Z.parseSourceMapInput(r)), t.sections != null ? new Wt(t, e) : new Xe(t, e);
}
Re.fromSourceMap = function(r, e) {
  return Xe.fromSourceMap(r, e);
};
Re.prototype._version = 3;
Re.prototype.__generatedMappings = null;
Object.defineProperty(Re.prototype, "_generatedMappings", {
  configurable: !0,
  enumerable: !0,
  get: function() {
    return this.__generatedMappings || this._parseMappings(this._mappings, this.sourceRoot), this.__generatedMappings;
  }
});
Re.prototype.__originalMappings = null;
Object.defineProperty(Re.prototype, "_originalMappings", {
  configurable: !0,
  enumerable: !0,
  get: function() {
    return this.__originalMappings || this._parseMappings(this._mappings, this.sourceRoot), this.__originalMappings;
  }
});
Re.prototype._charIsMappingSeparator = function(e, t) {
  var s = e.charAt(t);
  return s === ";" || s === ",";
};
Re.prototype._parseMappings = function(e, t) {
  throw new Error("Subclasses must implement _parseMappings");
};
Re.GENERATED_ORDER = 1;
Re.ORIGINAL_ORDER = 2;
Re.GREATEST_LOWER_BOUND = 1;
Re.LEAST_UPPER_BOUND = 2;
Re.prototype.eachMapping = function(e, t, s) {
  var i = t || null, n = s || Re.GENERATED_ORDER, a;
  switch (n) {
    case Re.GENERATED_ORDER:
      a = this._generatedMappings;
      break;
    case Re.ORIGINAL_ORDER:
      a = this._originalMappings;
      break;
    default:
      throw new Error("Unknown order of iteration.");
  }
  var o = this.sourceRoot;
  a.map(function(l) {
    var u = l.source === null ? null : this._sources.at(l.source);
    return u = Z.computeSourceURL(o, u, this._sourceMapURL), {
      source: u,
      generatedLine: l.generatedLine,
      generatedColumn: l.generatedColumn,
      originalLine: l.originalLine,
      originalColumn: l.originalColumn,
      name: l.name === null ? null : this._names.at(l.name)
    };
  }, this).forEach(e, i);
};
Re.prototype.allGeneratedPositionsFor = function(e) {
  var t = Z.getArg(e, "line"), s = {
    source: Z.getArg(e, "source"),
    originalLine: t,
    originalColumn: Z.getArg(e, "column", 0)
  };
  if (s.source = this._findSourceIndex(s.source), s.source < 0)
    return [];
  var i = [], n = this._findMapping(
    s,
    this._originalMappings,
    "originalLine",
    "originalColumn",
    Z.compareByOriginalPositions,
    iu.LEAST_UPPER_BOUND
  );
  if (n >= 0) {
    var a = this._originalMappings[n];
    if (e.column === void 0)
      for (var o = a.originalLine; a && a.originalLine === o; )
        i.push({
          line: Z.getArg(a, "generatedLine", null),
          column: Z.getArg(a, "generatedColumn", null),
          lastColumn: Z.getArg(a, "lastGeneratedColumn", null)
        }), a = this._originalMappings[++n];
    else
      for (var l = a.originalColumn; a && a.originalLine === t && a.originalColumn == l; )
        i.push({
          line: Z.getArg(a, "generatedLine", null),
          column: Z.getArg(a, "generatedColumn", null),
          lastColumn: Z.getArg(a, "lastGeneratedColumn", null)
        }), a = this._originalMappings[++n];
  }
  return i;
};
function Xe(r, e) {
  var t = r;
  typeof r == "string" && (t = Z.parseSourceMapInput(r));
  var s = Z.getArg(t, "version"), i = Z.getArg(t, "sources"), n = Z.getArg(t, "names", []), a = Z.getArg(t, "sourceRoot", null), o = Z.getArg(t, "sourcesContent", null), l = Z.getArg(t, "mappings"), u = Z.getArg(t, "file", null);
  if (s != this._version)
    throw new Error("Unsupported version: " + s);
  a && (a = Z.normalize(a)), i = i.map(String).map(Z.normalize).map(function(c) {
    return a && Z.isAbsolute(a) && Z.isAbsolute(c) ? Z.relative(a, c) : c;
  }), this._names = Ts.fromArray(n.map(String), !0), this._sources = Ts.fromArray(i, !0), this._absoluteSources = this._sources.toArray().map(function(c) {
    return Z.computeSourceURL(a, c, e);
  }), this.sourceRoot = a, this.sourcesContent = o, this._mappings = l, this._sourceMapURL = e, this.file = u;
}
Xe.prototype = Object.create(Re.prototype);
Xe.prototype.consumer = Re;
Xe.prototype._findSourceIndex = function(r) {
  var e = r;
  if (this.sourceRoot != null && (e = Z.relative(this.sourceRoot, e)), this._sources.has(e))
    return this._sources.indexOf(e);
  var t;
  for (t = 0; t < this._absoluteSources.length; ++t)
    if (this._absoluteSources[t] == r)
      return t;
  return -1;
};
Xe.fromSourceMap = function(e, t) {
  var s = Object.create(Xe.prototype), i = s._names = Ts.fromArray(e._names.toArray(), !0), n = s._sources = Ts.fromArray(e._sources.toArray(), !0);
  s.sourceRoot = e._sourceRoot, s.sourcesContent = e._generateSourcesContent(
    s._sources.toArray(),
    s.sourceRoot
  ), s.file = e._file, s._sourceMapURL = t, s._absoluteSources = s._sources.toArray().map(function(p) {
    return Z.computeSourceURL(s.sourceRoot, p, t);
  });
  for (var a = e._mappings.toArray().slice(), o = s.__generatedMappings = [], l = s.__originalMappings = [], u = 0, c = a.length; u < c; u++) {
    var h = a[u], f = new Tp();
    f.generatedLine = h.generatedLine, f.generatedColumn = h.generatedColumn, h.source && (f.source = n.indexOf(h.source), f.originalLine = h.originalLine, f.originalColumn = h.originalColumn, h.name && (f.name = i.indexOf(h.name)), l.push(f)), o.push(f);
  }
  return Ei(s.__originalMappings, Z.compareByOriginalPositions), s;
};
Xe.prototype._version = 3;
Object.defineProperty(Xe.prototype, "sources", {
  get: function() {
    return this._absoluteSources.slice();
  }
});
function Tp() {
  this.generatedLine = 0, this.generatedColumn = 0, this.source = null, this.originalLine = null, this.originalColumn = null, this.name = null;
}
Xe.prototype._parseMappings = function(e, t) {
  for (var s = 1, i = 0, n = 0, a = 0, o = 0, l = 0, u = e.length, c = 0, h = {}, f = {}, p = [], x = [], d, m, y, _, T; c < u; )
    if (e.charAt(c) === ";")
      s++, c++, i = 0;
    else if (e.charAt(c) === ",")
      c++;
    else {
      for (d = new Tp(), d.generatedLine = s, _ = c; _ < u && !this._charIsMappingSeparator(e, _); _++)
        ;
      if (m = e.slice(c, _), y = h[m], y)
        c += m.length;
      else {
        for (y = []; c < _; )
          Mv.decode(e, c, f), T = f.value, c = f.rest, y.push(T);
        if (y.length === 2)
          throw new Error("Found a source, but no line and column");
        if (y.length === 3)
          throw new Error("Found a source and line, but no column");
        h[m] = y;
      }
      d.generatedColumn = i + y[0], i = d.generatedColumn, y.length > 1 && (d.source = o + y[1], o += y[1], d.originalLine = n + y[2], n = d.originalLine, d.originalLine += 1, d.originalColumn = a + y[3], a = d.originalColumn, y.length > 4 && (d.name = l + y[4], l += y[4])), x.push(d), typeof d.originalLine == "number" && p.push(d);
    }
  Ei(x, Z.compareByGeneratedPositionsDeflated), this.__generatedMappings = x, Ei(p, Z.compareByOriginalPositions), this.__originalMappings = p;
};
Xe.prototype._findMapping = function(e, t, s, i, n, a) {
  if (e[s] <= 0)
    throw new TypeError("Line must be greater than or equal to 1, got " + e[s]);
  if (e[i] < 0)
    throw new TypeError("Column must be greater than or equal to 0, got " + e[i]);
  return iu.search(e, t, n, a);
};
Xe.prototype.computeColumnSpans = function() {
  for (var e = 0; e < this._generatedMappings.length; ++e) {
    var t = this._generatedMappings[e];
    if (e + 1 < this._generatedMappings.length) {
      var s = this._generatedMappings[e + 1];
      if (t.generatedLine === s.generatedLine) {
        t.lastGeneratedColumn = s.generatedColumn - 1;
        continue;
      }
    }
    t.lastGeneratedColumn = 1 / 0;
  }
};
Xe.prototype.originalPositionFor = function(e) {
  var t = {
    generatedLine: Z.getArg(e, "line"),
    generatedColumn: Z.getArg(e, "column")
  }, s = this._findMapping(
    t,
    this._generatedMappings,
    "generatedLine",
    "generatedColumn",
    Z.compareByGeneratedPositionsDeflated,
    Z.getArg(e, "bias", Re.GREATEST_LOWER_BOUND)
  );
  if (s >= 0) {
    var i = this._generatedMappings[s];
    if (i.generatedLine === t.generatedLine) {
      var n = Z.getArg(i, "source", null);
      n !== null && (n = this._sources.at(n), n = Z.computeSourceURL(this.sourceRoot, n, this._sourceMapURL));
      var a = Z.getArg(i, "name", null);
      return a !== null && (a = this._names.at(a)), {
        source: n,
        line: Z.getArg(i, "originalLine", null),
        column: Z.getArg(i, "originalColumn", null),
        name: a
      };
    }
  }
  return {
    source: null,
    line: null,
    column: null,
    name: null
  };
};
Xe.prototype.hasContentsOfAllSources = function() {
  return this.sourcesContent ? this.sourcesContent.length >= this._sources.size() && !this.sourcesContent.some(function(e) {
    return e == null;
  }) : !1;
};
Xe.prototype.sourceContentFor = function(e, t) {
  if (!this.sourcesContent)
    return null;
  var s = this._findSourceIndex(e);
  if (s >= 0)
    return this.sourcesContent[s];
  var i = e;
  this.sourceRoot != null && (i = Z.relative(this.sourceRoot, i));
  var n;
  if (this.sourceRoot != null && (n = Z.urlParse(this.sourceRoot))) {
    var a = i.replace(/^file:\/\//, "");
    if (n.scheme == "file" && this._sources.has(a))
      return this.sourcesContent[this._sources.indexOf(a)];
    if ((!n.path || n.path == "/") && this._sources.has("/" + i))
      return this.sourcesContent[this._sources.indexOf("/" + i)];
  }
  if (t)
    return null;
  throw new Error('"' + i + '" is not in the SourceMap.');
};
Xe.prototype.generatedPositionFor = function(e) {
  var t = Z.getArg(e, "source");
  if (t = this._findSourceIndex(t), t < 0)
    return {
      line: null,
      column: null,
      lastColumn: null
    };
  var s = {
    source: t,
    originalLine: Z.getArg(e, "line"),
    originalColumn: Z.getArg(e, "column")
  }, i = this._findMapping(
    s,
    this._originalMappings,
    "originalLine",
    "originalColumn",
    Z.compareByOriginalPositions,
    Z.getArg(e, "bias", Re.GREATEST_LOWER_BOUND)
  );
  if (i >= 0) {
    var n = this._originalMappings[i];
    if (n.source === s.source)
      return {
        line: Z.getArg(n, "generatedLine", null),
        column: Z.getArg(n, "generatedColumn", null),
        lastColumn: Z.getArg(n, "lastGeneratedColumn", null)
      };
  }
  return {
    line: null,
    column: null,
    lastColumn: null
  };
};
function Wt(r, e) {
  var t = r;
  typeof r == "string" && (t = Z.parseSourceMapInput(r));
  var s = Z.getArg(t, "version"), i = Z.getArg(t, "sections");
  if (s != this._version)
    throw new Error("Unsupported version: " + s);
  this._sources = new Ts(), this._names = new Ts();
  var n = {
    line: -1,
    column: 0
  };
  this._sections = i.map(function(a) {
    if (a.url)
      throw new Error("Support for url field in sections not implemented.");
    var o = Z.getArg(a, "offset"), l = Z.getArg(o, "line"), u = Z.getArg(o, "column");
    if (l < n.line || l === n.line && u < n.column)
      throw new Error("Section offsets must be ordered and non-overlapping.");
    return n = o, {
      generatedOffset: {
        generatedLine: l + 1,
        generatedColumn: u + 1
      },
      consumer: new Re(Z.getArg(a, "map"), e)
    };
  });
}
Wt.prototype = Object.create(Re.prototype);
Wt.prototype.constructor = Re;
Wt.prototype._version = 3;
Object.defineProperty(Wt.prototype, "sources", {
  get: function() {
    for (var r = [], e = 0; e < this._sections.length; e++)
      for (var t = 0; t < this._sections[e].consumer.sources.length; t++)
        r.push(this._sections[e].consumer.sources[t]);
    return r;
  }
});
Wt.prototype.originalPositionFor = function(e) {
  var t = {
    generatedLine: Z.getArg(e, "line"),
    generatedColumn: Z.getArg(e, "column")
  }, s = iu.search(
    t,
    this._sections,
    function(n, a) {
      var o = n.generatedLine - a.generatedOffset.generatedLine;
      return o || n.generatedColumn - a.generatedOffset.generatedColumn;
    }
  ), i = this._sections[s];
  return i ? i.consumer.originalPositionFor({
    line: t.generatedLine - (i.generatedOffset.generatedLine - 1),
    column: t.generatedColumn - (i.generatedOffset.generatedLine === t.generatedLine ? i.generatedOffset.generatedColumn - 1 : 0),
    bias: e.bias
  }) : {
    source: null,
    line: null,
    column: null,
    name: null
  };
};
Wt.prototype.hasContentsOfAllSources = function() {
  return this._sections.every(function(e) {
    return e.consumer.hasContentsOfAllSources();
  });
};
Wt.prototype.sourceContentFor = function(e, t) {
  for (var s = 0; s < this._sections.length; s++) {
    var i = this._sections[s], n = i.consumer.sourceContentFor(e, !0);
    if (n)
      return n;
  }
  if (t)
    return null;
  throw new Error('"' + e + '" is not in the SourceMap.');
};
Wt.prototype.generatedPositionFor = function(e) {
  for (var t = 0; t < this._sections.length; t++) {
    var s = this._sections[t];
    if (s.consumer._findSourceIndex(Z.getArg(e, "source")) !== -1) {
      var i = s.consumer.generatedPositionFor(e);
      if (i) {
        var n = {
          line: i.line + (s.generatedOffset.generatedLine - 1),
          column: i.column + (s.generatedOffset.generatedLine === i.line ? s.generatedOffset.generatedColumn - 1 : 0)
        };
        return n;
      }
    }
  }
  return {
    line: null,
    column: null
  };
};
Wt.prototype._parseMappings = function(e, t) {
  this.__generatedMappings = [], this.__originalMappings = [];
  for (var s = 0; s < this._sections.length; s++)
    for (var i = this._sections[s], n = i.consumer._generatedMappings, a = 0; a < n.length; a++) {
      var o = n[a], l = i.consumer._sources.at(o.source);
      l = Z.computeSourceURL(i.consumer.sourceRoot, l, this._sourceMapURL), this._sources.add(l), l = this._sources.indexOf(l);
      var u = null;
      o.name && (u = i.consumer._names.at(o.name), this._names.add(u), u = this._names.indexOf(u));
      var c = {
        source: l,
        generatedLine: o.generatedLine + (i.generatedOffset.generatedLine - 1),
        generatedColumn: o.generatedColumn + (i.generatedOffset.generatedLine === o.generatedLine ? i.generatedOffset.generatedColumn - 1 : 0),
        originalLine: o.originalLine,
        originalColumn: o.originalColumn,
        name: u
      };
      this.__generatedMappings.push(c), typeof c.originalLine == "number" && this.__originalMappings.push(c);
    }
  Ei(this.__generatedMappings, Z.compareByGeneratedPositionsDeflated), Ei(this.__originalMappings, Z.compareByOriginalPositions);
};
class Uv {
  constructor() {
    this.should_skip = !1, this.should_remove = !1, this.replacement = null, this.context = {
      skip: () => this.should_skip = !0,
      remove: () => this.should_remove = !0,
      replace: (e) => this.replacement = e
    };
  }
  replace(e, t, s, i) {
    e && (s !== null ? e[t][s] = i : e[t] = i);
  }
  remove(e, t, s) {
    e && (s !== null ? e[t].splice(s, 1) : delete e[t]);
  }
}
class $v extends Uv {
  constructor(e, t) {
    super(), this.enter = e, this.leave = t;
  }
  visit(e, t, s, i) {
    if (e) {
      if (this.enter) {
        const n = this.should_skip, a = this.should_remove, o = this.replacement;
        this.should_skip = !1, this.should_remove = !1, this.replacement = null, this.enter.call(this.context, e, t, s, i), this.replacement && (e = this.replacement, this.replace(t, s, i, e)), this.should_remove && this.remove(t, s, i);
        const l = this.should_skip, u = this.should_remove;
        if (this.should_skip = n, this.should_remove = a, this.replacement = o, l)
          return e;
        if (u)
          return null;
      }
      for (const n in e) {
        const a = e[n];
        if (typeof a == "object")
          if (Array.isArray(a))
            for (let o = 0; o < a.length; o += 1)
              a[o] !== null && typeof a[o].type == "string" && (this.visit(a[o], e, n, o) || o--);
          else
            a !== null && typeof a.type == "string" && this.visit(a, e, n, null);
      }
      if (this.leave) {
        const n = this.replacement, a = this.should_remove;
        this.replacement = null, this.should_remove = !1, this.leave.call(this.context, e, t, s, i), this.replacement && (e = this.replacement, this.replace(t, s, i, e)), this.should_remove && this.remove(t, s, i);
        const o = this.should_remove;
        if (this.replacement = n, this.should_remove = a, o)
          return null;
      }
    }
    return e;
  }
}
function jv(r, { enter: e, leave: t }) {
  return new $v(e, t).visit(r, null);
}
function qv(r, e, t = !1, s = [], i = /* @__PURE__ */ Object.create(null)) {
  const n = r.type === "Program" && r.body[0].type === "ExpressionStatement" && r.body[0].expression;
  jv(r, {
    enter(a, o) {
      if (o && s.push(o), o && o.type.startsWith("TS") && o.type !== "TSAsExpression" && o.type !== "TSNonNullExpression" && o.type !== "TSTypeAssertion")
        return this.skip();
      if (a.type === "Identifier") {
        const l = !!i[a.name], u = Vv(a, o, s);
        (t || u && !l) && e(a, o, s, u, l);
      } else
        a.type === "ObjectProperty" && o.type === "ObjectPattern" ? a.inPattern = !0 : Hv(a) ? zv(a, (l) => $c(a, l, i)) : a.type === "BlockStatement" && Wv(a, (l) => $c(a, l, i));
    },
    leave(a, o) {
      if (o && s.pop(), a !== n && a.scopeIds)
        for (const l of a.scopeIds)
          i[l]--, i[l] === 0 && delete i[l];
    }
  });
}
function Vv(r, e, t) {
  if (!e)
    return !0;
  if (r.name === "arguments")
    return !1;
  if (Gv(r, e))
    return !0;
  switch (e.type) {
    case "AssignmentExpression":
    case "AssignmentPattern":
      return !0;
    case "ObjectPattern":
    case "ArrayPattern":
      return Ap(e, t);
  }
  return !1;
}
function Ap(r, e) {
  if (r && (r.type === "ObjectProperty" || r.type === "ArrayPattern")) {
    let t = e.length;
    for (; t--; ) {
      const s = e[t];
      if (s.type === "AssignmentExpression")
        return !0;
      if (s.type !== "ObjectProperty" && !s.type.endsWith("Pattern"))
        break;
    }
  }
  return !1;
}
function zv(r, e) {
  for (const t of r.params)
    for (const s of Ur(t))
      e(s);
}
function Wv(r, e) {
  for (const t of r.body)
    if (t.type === "VariableDeclaration") {
      if (t.declare)
        continue;
      for (const s of t.declarations)
        for (const i of Ur(s.id))
          e(i);
    } else if (t.type === "FunctionDeclaration" || t.type === "ClassDeclaration") {
      if (t.declare || !t.id)
        continue;
      e(t.id);
    }
}
function Ur(r, e = []) {
  switch (r.type) {
    case "Identifier":
      e.push(r);
      break;
    case "MemberExpression":
      let t = r;
      for (; t.type === "MemberExpression"; )
        t = t.object;
      e.push(t);
      break;
    case "ObjectPattern":
      for (const s of r.properties)
        s.type === "RestElement" ? Ur(s.argument, e) : Ur(s.value, e);
      break;
    case "ArrayPattern":
      r.elements.forEach((s) => {
        s && Ur(s, e);
      });
      break;
    case "RestElement":
      Ur(r.argument, e);
      break;
    case "AssignmentPattern":
      Ur(r.left, e);
      break;
  }
  return e;
}
function $c(r, e, t) {
  const { name: s } = e;
  r.scopeIds && r.scopeIds.has(s) || (s in t ? t[s]++ : t[s] = 1, (r.scopeIds || (r.scopeIds = /* @__PURE__ */ new Set())).add(s));
}
const Hv = (r) => /Function(?:Expression|Declaration)$|Method$/.test(r.type), _p = (r) => r && (r.type === "ObjectProperty" || r.type === "ObjectMethod") && !r.computed, Kv = (r, e) => _p(e) && e.key === r;
function Gv(r, e, t) {
  switch (e.type) {
    case "MemberExpression":
    case "OptionalMemberExpression":
      return e.property === r ? !!e.computed : e.object === r;
    case "JSXMemberExpression":
      return e.object === r;
    case "VariableDeclarator":
      return e.init === r;
    case "ArrowFunctionExpression":
      return e.body === r;
    case "PrivateName":
      return !1;
    case "ClassMethod":
    case "ClassPrivateMethod":
    case "ObjectMethod":
      return e.key === r ? !!e.computed : !1;
    case "ObjectProperty":
      return e.key === r ? !!e.computed : !t || t.type !== "ObjectPattern";
    case "ClassProperty":
      return e.key === r ? !!e.computed : !0;
    case "ClassPrivateProperty":
      return e.key !== r;
    case "ClassDeclaration":
    case "ClassExpression":
      return e.superClass === r;
    case "AssignmentExpression":
      return e.right === r;
    case "AssignmentPattern":
      return e.right === r;
    case "LabeledStatement":
      return !1;
    case "CatchClause":
      return !1;
    case "RestElement":
      return !1;
    case "BreakStatement":
    case "ContinueStatement":
      return !1;
    case "FunctionDeclaration":
    case "FunctionExpression":
      return !1;
    case "ExportNamespaceSpecifier":
    case "ExportDefaultSpecifier":
      return !1;
    case "ExportSpecifier":
      return t != null && t.source ? !1 : e.local === r;
    case "ImportDefaultSpecifier":
    case "ImportNamespaceSpecifier":
    case "ImportSpecifier":
      return !1;
    case "ImportAttribute":
      return !1;
    case "JSXAttribute":
      return !1;
    case "ObjectPattern":
    case "ArrayPattern":
      return !1;
    case "MetaProperty":
      return !1;
    case "ObjectTypeProperty":
      return e.key !== r;
    case "TSEnumMember":
      return e.id !== r;
    case "TSPropertySignature":
      return e.key === r ? !!e.computed : !0;
  }
  return !0;
}
const Yv = /* @__PURE__ */ or("true,false,null,this"), Jv = (r, e) => {
  if (r.type === 5)
    r.content = vt(r.content, e);
  else if (r.type === 1)
    for (let t = 0; t < r.props.length; t++) {
      const s = r.props[t];
      if (s.type === 7 && s.name !== "for") {
        const i = s.exp, n = s.arg;
        i && i.type === 4 && !(s.name === "on" && n) && (s.exp = vt(
          i,
          e,
          s.name === "slot"
        )), n && n.type === 4 && !n.isStatic && (s.arg = vt(n, e));
      }
    }
};
function vt(r, e, t = !1, s = !1, i = Object.create(e.identifiers)) {
  if (!e.prefixIdentifiers || !r.content.trim())
    return r;
  const { inline: n, bindingMetadata: a } = e, o = (y, _, T) => {
    const C = p0(a, y) && a[y];
    if (n) {
      const v = _ && _.type === "AssignmentExpression" && _.left === T, w = _ && _.type === "UpdateExpression" && _.argument === T, N = _ && Ap(_, p);
      if (C === "setup-const" || C === "setup-reactive-const" || i[y])
        return y;
      if (C === "setup-ref")
        return `${y}.value`;
      if (C === "setup-maybe-ref")
        return v || w || N ? `${y}.value` : `${e.helperString(Cn)}(${y})`;
      if (C === "setup-let")
        if (v) {
          const { right: P, operator: g } = _, E = l.slice(P.start - 1, P.end - 1), O = Cp(vt(fe(E, !1), e, !1, !1, x));
          return `${e.helperString(In)}(${y})${e.isTS ? ` //@ts-ignore
` : ""} ? ${y}.value ${g} ${O} : ${y}`;
        } else if (w) {
          T.start = _.start, T.end = _.end;
          const { prefix: P, operator: g } = _, E = P ? g : "", O = P ? "" : g;
          return `${e.helperString(In)}(${y})${e.isTS ? ` //@ts-ignore
` : ""} ? ${E}${y}.value${O} : ${E}${y}${O}`;
        } else
          return N ? y : `${e.helperString(Cn)}(${y})`;
      else {
        if (C === "props")
          return Sc(y);
        if (C === "props-aliased")
          return Sc(a.__propsAliases[y]);
      }
    } else {
      if (C && C.startsWith("setup"))
        return `$setup.${y}`;
      if (C === "props-aliased")
        return `$props['${a.__propsAliases[y]}']`;
      if (C)
        return `$${C}.${y}`;
    }
    return `_ctx.${y}`;
  }, l = r.content, u = l.indexOf("(") > -1 || l.indexOf(".") > 0;
  if (Fn(l)) {
    const y = e.identifiers[l], _ = Ef(l), T = Yv(l);
    return !t && !y && !_ && !T ? (a[r.content] === "setup-const" && (r.constType = 1), r.content = o(l)) : y || (T ? r.constType = 3 : r.constType = 2), r;
  }
  let c;
  const h = s ? ` ${l} ` : `(${l})${t ? "=>{}" : ""}`;
  try {
    c = pv(h, {
      plugins: e.expressionPlugins
    }).program;
  } catch (y) {
    return e.onError(Fe(44, r.loc, void 0, y.message)), r;
  }
  const f = [], p = [], x = Object.create(e.identifiers);
  qv(
    c,
    (y, _, T, C, v) => {
      if (Kv(y, _))
        return;
      const w = C && Qv(y);
      w && !v ? (_p(_) && _.shorthand && (y.prefix = `${y.name}: `), y.name = o(y.name, _, y), f.push(y)) : (!(w && v) && !u && (y.isConstant = !0), f.push(y));
    },
    !0,
    p,
    x
  );
  const d = [];
  f.sort((y, _) => y.start - _.start), f.forEach((y, _) => {
    const T = y.start - 1, C = y.end - 1, v = f[_ - 1], w = l.slice(v ? v.end - 1 : 0, T);
    (w.length || y.prefix) && d.push(w + (y.prefix || ""));
    const N = l.slice(T, C);
    d.push(fe(y.name, !1, {
      source: N,
      start: Bn(r.loc.start, N, T),
      end: Bn(r.loc.start, N, C)
    }, y.isConstant ? 3 : 0)), _ === f.length - 1 && C < l.length && d.push(l.slice(C));
  });
  let m;
  return d.length ? m = rt(d, r.loc) : (m = r, m.constType = u ? 0 : 3), m.identifiers = Object.keys(x), m;
}
function Qv(r) {
  return !(Ef(r.name) || r.name === "require");
}
function Cp(r) {
  return St(r) ? r : r.type === 4 ? r.content : r.children.map(Cp).join("");
}
const Xv = xp(/^(if|else|else-if)$/, (r, e, t) => Zv(r, e, t, (s, i, n) => {
  const a = t.parent.children;
  let o = a.indexOf(s), l = 0;
  for (; o-- >= 0; ) {
    const u = a[o];
    u && u.type === 9 && (l += u.branches.length);
  }
  return () => {
    if (n)
      s.codegenNode = qc(i, l, t);
    else {
      const u = tb(s.codegenNode);
      u.alternate = qc(i, l + s.branches.length - 1, t);
    }
  };
}));
function Zv(r, e, t, s) {
  if (e.name !== "else" && (!e.exp || !e.exp.content.trim())) {
    const i = e.exp ? e.exp.loc : r.loc;
    t.onError(Fe(28, e.loc)), e.exp = fe("true", !1, i);
  }
  if (t.prefixIdentifiers && e.exp && (e.exp = vt(e.exp, t)), e.name === "if") {
    const i = jc(r, e), n = {
      type: 9,
      loc: r.loc,
      branches: [i]
    };
    if (t.replaceNode(n), s)
      return s(n, i, !0);
  } else {
    const i = t.parent.children, n = [];
    let a = i.indexOf(r);
    for (; a-- >= -1; ) {
      const o = i[a];
      if (o && o.type === 3) {
        t.removeNode(o), n.unshift(o);
        continue;
      }
      if (o && o.type === 2 && !o.content.trim().length) {
        t.removeNode(o);
        continue;
      }
      if (o && o.type === 9) {
        e.name === "else-if" && o.branches[o.branches.length - 1].condition === void 0 && t.onError(Fe(30, r.loc)), t.removeNode();
        const l = jc(r, e);
        n.length && !(t.parent && t.parent.type === 1 && Qs(t.parent.tag, "transition")) && (l.children = [...n, ...l.children]);
        {
          const c = l.userKey;
          c && o.branches.forEach(({ userKey: h }) => {
            eb(h, c) && t.onError(Fe(29, l.userKey.loc));
          });
        }
        o.branches.push(l);
        const u = s && s(o, l, !1);
        tu(l, t), u && u(), t.currentNode = null;
      } else
        t.onError(Fe(30, r.loc));
      break;
    }
  }
}
function jc(r, e) {
  const t = r.tagType === 3;
  return {
    type: 10,
    loc: r.loc,
    condition: e.name === "else" ? void 0 : e.exp,
    children: t && !ut(r, "for") ? r.children : [r],
    userKey: Es(r, "key"),
    isTemplateIf: t
  };
}
function qc(r, e, t) {
  return r.condition ? Ao(
    r.condition,
    Vc(r, e, t),
    $e(t.helper(Il), [
      '"v-if"',
      "true"
    ])
  ) : Vc(r, e, t);
}
function Vc(r, e, t) {
  const { helper: s } = t, i = Ue("key", fe(`${e}`, !1, Ot, 2)), { children: n } = r, a = n[0];
  if (n.length !== 1 || a.type !== 1)
    if (n.length === 1 && a.type === 11) {
      const l = a.codegenNode;
      return Un(l, i, t), l;
    } else {
      let l = 64, u = wr[64];
      return !r.isTemplateIf && n.filter((c) => c.type !== 3).length === 1 && (l |= 2048, u += `, ${wr[2048]}`), Nn(t, s(En), gt([i]), n, l + ` /* ${u} */`, void 0, void 0, !0, !1, !1, r.loc);
    }
  else {
    const l = a.codegenNode, u = Sv(l);
    return u.type === 13 && vp(u, t), Un(u, i, t), l;
  }
}
function eb(r, e) {
  if (!r || r.type !== e.type)
    return !1;
  if (r.type === 6) {
    if (r.value.content !== e.value.content)
      return !1;
  } else {
    const t = r.exp, s = e.exp;
    if (t.type !== s.type || t.type !== 4 || t.isStatic !== s.isStatic || t.content !== s.content)
      return !1;
  }
  return !0;
}
function tb(r) {
  for (; ; )
    if (r.type === 19)
      if (r.alternate.type === 19)
        r = r.alternate;
      else
        return r;
    else
      r.type === 20 && (r = r.value);
}
const rb = xp("for", (r, e, t) => {
  const { helper: s, removeHelper: i } = t;
  return sb(r, e, t, (n) => {
    const a = $e(s(Nl), [
      n.source
    ]), o = Si(r), l = ut(r, "memo"), u = Es(r, "key"), c = u && (u.type === 6 ? fe(u.value.content, !0) : u.exp), h = u ? Ue("key", c) : null;
    o && (l && (l.exp = vt(l.exp, t)), h && u.type !== 6 && (h.value = vt(h.value, t)));
    const f = n.source.type === 4 && n.source.constType > 0, p = f ? 64 : u ? 128 : 256;
    return n.codegenNode = Nn(t, s(En), void 0, a, p + ` /* ${wr[p]} */`, void 0, void 0, !0, !f, !1, r.loc), () => {
      let x;
      const { children: d } = n;
      o && r.children.some((_) => {
        if (_.type === 1) {
          const T = Es(_, "key");
          if (T)
            return t.onError(Fe(33, T.loc)), !0;
        }
      });
      const m = d.length !== 1 || d[0].type !== 1, y = Mo(r) ? r : o && r.children.length === 1 && Mo(r.children[0]) ? r.children[0] : null;
      if (y ? (x = y.codegenNode, o && h && Un(x, h, t)) : m ? x = Nn(t, s(En), h ? gt([h]) : void 0, r.children, 64 + ` /* ${wr[64]} */`, void 0, void 0, !0, void 0, !1) : (x = d[0].codegenNode, o && h && Un(x, h, t), x.isBlock !== !f && (x.isBlock ? (i(Ss), i(Pi(t.inSSR, x.isComponent))) : i(wi(t.inSSR, x.isComponent))), x.isBlock = !f, x.isBlock ? (s(Ss), s(Pi(t.inSSR, x.isComponent))) : s(wi(t.inSSR, x.isComponent))), l) {
        const _ = ws(Ro(n.parseResult, [
          fe("_cached")
        ]));
        _.body = C0([
          rt(["const _memo = (", l.exp, ")"]),
          rt([
            "if (_cached",
            ...c ? [" && _cached.key === ", c] : [],
            ` && ${t.helperString($f)}(_cached, _memo)) return _cached`
          ]),
          rt(["const _item = ", x]),
          fe("_item.memo = _memo"),
          fe("return _item")
        ]), a.arguments.push(_, fe("_cache"), fe(String(t.cached++)));
      } else
        a.arguments.push(ws(Ro(n.parseResult), x, !0));
    };
  });
});
function sb(r, e, t, s) {
  if (!e.exp) {
    t.onError(Fe(31, e.loc));
    return;
  }
  const i = nu(
    e.exp,
    t
  );
  if (!i) {
    t.onError(Fe(32, e.loc));
    return;
  }
  const { addIdentifiers: n, removeIdentifiers: a, scopes: o } = t, { source: l, value: u, key: c, index: h } = i, f = {
    type: 11,
    loc: e.loc,
    source: l,
    valueAlias: u,
    keyAlias: c,
    objectIndexAlias: h,
    parseResult: i,
    children: Si(r) ? r.children : [r]
  };
  t.replaceNode(f), o.vFor++, t.prefixIdentifiers && (u && n(u), c && n(c), h && n(h));
  const p = s && s(f);
  return () => {
    o.vFor--, t.prefixIdentifiers && (u && a(u), c && a(c), h && a(h)), p && p();
  };
}
const ib = /([\s\S]*?)\s+(?:in|of)\s+([\s\S]*)/, zc = /,([^,\}\]]*)(?:,([^,\}\]]*))?$/, nb = /^\(|\)$/g;
function nu(r, e) {
  const t = r.loc, s = r.content, i = s.match(ib);
  if (!i)
    return;
  const [, n, a] = i, o = {
    source: Wi(t, a.trim(), s.indexOf(a, n.length)),
    value: void 0,
    key: void 0,
    index: void 0
  };
  e.prefixIdentifiers && (o.source = vt(o.source, e));
  let l = n.trim().replace(nb, "").trim();
  const u = n.indexOf(l), c = l.match(zc);
  if (c) {
    l = l.replace(zc, "").trim();
    const h = c[1].trim();
    let f;
    if (h && (f = s.indexOf(h, u + l.length), o.key = Wi(t, h, f), e.prefixIdentifiers && (o.key = vt(o.key, e, !0))), c[2]) {
      const p = c[2].trim();
      p && (o.index = Wi(t, p, s.indexOf(p, o.key ? f + h.length : u + l.length)), e.prefixIdentifiers && (o.index = vt(o.index, e, !0)));
    }
  }
  return l && (o.value = Wi(t, l, u), e.prefixIdentifiers && (o.value = vt(o.value, e, !0))), o;
}
function Wi(r, e, t) {
  return fe(e, !1, mp(r, t, e.length));
}
function Ro({ value: r, key: e, index: t }, s = []) {
  return ab([r, e, t, ...s]);
}
function ab(r) {
  let e = r.length;
  for (; e-- && !r[e]; )
    ;
  return r.slice(0, e + 1).map((t, s) => t || fe("_".repeat(s + 1), !1));
}
const Wc = fe("undefined", !1), ob = (r, e) => {
  if (r.type === 1 && (r.tagType === 1 || r.tagType === 3)) {
    const t = ut(r, "slot");
    if (t) {
      const s = t.exp;
      return e.prefixIdentifiers && s && e.addIdentifiers(s), e.scopes.vSlot++, () => {
        e.prefixIdentifiers && s && e.removeIdentifiers(s), e.scopes.vSlot--;
      };
    }
  }
}, lb = (r, e) => {
  let t;
  if (Si(r) && r.props.some(yp) && (t = ut(r, "for"))) {
    const s = t.parseResult = nu(t.exp, e);
    if (s) {
      const { value: i, key: n, index: a } = s, { addIdentifiers: o, removeIdentifiers: l } = e;
      return i && o(i), n && o(n), a && o(a), () => {
        i && l(i), n && l(n), a && l(a);
      };
    }
  }
}, ub = (r, e, t) => ws(r, e, !1, !0, e.length ? e[0].loc : t);
function cb(r, e, t = ub) {
  e.helper(Uf);
  const { children: s, loc: i } = r, n = [], a = [];
  let o = e.scopes.vSlot > 0 || e.scopes.vFor > 0;
  !e.ssr && e.prefixIdentifiers && (o = ot(r, e.identifiers));
  const l = ut(r, "slot", !0);
  if (l) {
    const { arg: d, exp: m } = l;
    d && !ht(d) && (o = !0), n.push(Ue(d || fe("default", !0), t(m, s, i)));
  }
  let u = !1, c = !1;
  const h = [], f = /* @__PURE__ */ new Set();
  for (let d = 0; d < s.length; d++) {
    const m = s[d];
    let y;
    if (!Si(m) || !(y = ut(m, "slot", !0))) {
      m.type !== 3 && h.push(m);
      continue;
    }
    if (l) {
      e.onError(Fe(37, y.loc));
      break;
    }
    u = !0;
    const { children: _, loc: T } = m, { arg: C = fe("default", !0), exp: v, loc: w } = y;
    let N;
    ht(C) ? N = C ? C.content : "default" : o = !0;
    const P = t(v, _, T);
    let g, E, O;
    if (g = ut(m, "if"))
      o = !0, a.push(Ao(g.exp, Hi(C, P), Wc));
    else if (E = ut(m, /^else(-if)?$/, !0)) {
      let S = d, W;
      for (; S-- && (W = s[S], W.type === 3); )
        ;
      if (W && Si(W) && ut(W, "if")) {
        s.splice(d, 1), d--;
        let Q = a[a.length - 1];
        for (; Q.alternate.type === 19; )
          Q = Q.alternate;
        Q.alternate = E.exp ? Ao(E.exp, Hi(C, P), Wc) : Hi(C, P);
      } else
        e.onError(Fe(30, E.loc));
    } else if (O = ut(m, "for")) {
      o = !0;
      const S = O.parseResult || nu(O.exp, e);
      S ? a.push($e(e.helper(Nl), [
        S.source,
        ws(Ro(S), Hi(C, P), !0)
      ])) : e.onError(Fe(32, O.loc));
    } else {
      if (N) {
        if (f.has(N)) {
          e.onError(Fe(38, w));
          continue;
        }
        f.add(N), N === "default" && (c = !0);
      }
      n.push(Ue(C, P));
    }
  }
  if (!l) {
    const d = (m, y) => {
      const _ = t(m, y, i);
      return Ue("default", _);
    };
    u ? h.length && h.some((m) => Ip(m)) && (c ? e.onError(Fe(39, h[0].loc)) : n.push(d(void 0, h))) : n.push(d(void 0, s));
  }
  const p = o ? 2 : pn(r.children) ? 3 : 1;
  let x = gt(n.concat(Ue(
    "_",
    fe(p + ` /* ${n0[p]} */`, !1)
  )), i);
  return a.length && (x = $e(e.helper(Ff), [
    x,
    ha(a)
  ])), {
    slots: x,
    hasDynamicSlots: o
  };
}
function Hi(r, e) {
  return gt([
    Ue("name", r),
    Ue("fn", e)
  ]);
}
function pn(r) {
  for (let e = 0; e < r.length; e++) {
    const t = r[e];
    switch (t.type) {
      case 1:
        if (t.tagType === 2 || pn(t.children))
          return !0;
        break;
      case 9:
        if (pn(t.branches))
          return !0;
        break;
      case 10:
      case 11:
        if (pn(t.children))
          return !0;
        break;
    }
  }
  return !1;
}
function Ip(r) {
  return r.type !== 2 && r.type !== 12 ? !0 : r.type === 2 ? !!r.content.trim() : Ip(r.content);
}
const Np = /* @__PURE__ */ new WeakMap(), hb = (r, e) => function() {
  if (r = e.currentNode, !(r.type === 1 && (r.tagType === 0 || r.tagType === 1)))
    return;
  const { tag: s, props: i } = r, n = r.tagType === 1;
  let a = n ? fb(r, e) : `"${s}"`;
  const o = _f(a) && a.callee === An;
  let l, u, c, h = 0, f, p, x, d = o || a === si || a === Cl || !n && (s === "svg" || s === "foreignObject");
  if (i.length > 0) {
    const m = Op(r, e, void 0, n, o);
    l = m.props, h = m.patchFlag, p = m.dynamicPropNames;
    const y = m.directives;
    x = y && y.length ? ha(y.map((_) => db(_, e))) : void 0, m.shouldUseBlock && (d = !0);
  }
  if (r.children.length > 0)
    if (a === Tn && (d = !0, h |= 1024, r.children.length > 1 && e.onError(Fe(45, {
      start: r.children[0].loc.start,
      end: r.children[r.children.length - 1].loc.end,
      source: ""
    }))), n && a !== si && a !== Tn) {
      const { slots: y, hasDynamicSlots: _ } = cb(r, e);
      u = y, _ && (h |= 1024);
    } else if (r.children.length === 1 && a !== si) {
      const y = r.children[0], _ = y.type, T = _ === 5 || _ === 8;
      T && Ct(y, e) === 0 && (h |= 1), T || _ === 2 ? u = y : u = r.children;
    } else
      u = r.children;
  if (h !== 0) {
    if (h < 0)
      c = h + ` /* ${wr[h]} */`;
    else {
      const m = Object.keys(wr).map(Number).filter((y) => y > 0 && h & y).map((y) => wr[y]).join(", ");
      c = h + ` /* ${m} */`;
    }
    p && p.length && (f = mb(p));
  }
  r.codegenNode = Nn(e, a, l, u, c, f, x, !!d, !1, n, r.loc);
};
function fb(r, e, t = !1) {
  let { tag: s } = r;
  const i = Bo(s), n = Es(r, "is");
  if (n)
    if (i) {
      const l = n.type === 6 ? n.value && fe(n.value.content, !0) : n.exp;
      if (l)
        return $e(e.helper(An), [
          l
        ]);
    } else
      n.type === 6 && n.value.content.startsWith("vue:") && (s = n.value.content.slice(4));
  const a = !i && ut(r, "is");
  if (a && a.exp)
    return $e(e.helper(An), [
      a.exp
    ]);
  const o = mv(s) || e.isBuiltInComponent(s);
  if (o)
    return t || e.helper(o), o;
  {
    const l = Fo(s, e);
    if (l)
      return l;
    const u = s.indexOf(".");
    if (u > 0) {
      const c = Fo(s.slice(0, u), e);
      if (c)
        return c + s.slice(u);
    }
  }
  return e.selfName && ca(xs(s)) === e.selfName ? (e.helper(Po), e.components.add(s + "__self"), Lo(s, "component")) : (e.helper(Po), e.components.add(s), Lo(s, "component"));
}
function Fo(r, e) {
  const t = e.bindingMetadata;
  if (!t || t.__isScriptSetup === !1)
    return;
  const s = xs(r), i = ca(s), n = (l) => {
    if (t[r] === l)
      return r;
    if (t[s] === l)
      return s;
    if (t[i] === l)
      return i;
  }, a = n("setup-const") || n("setup-reactive-const");
  if (a)
    return e.inline ? a : `$setup[${JSON.stringify(a)}]`;
  const o = n("setup-let") || n("setup-ref") || n("setup-maybe-ref");
  if (o)
    return e.inline ? `${e.helperString(Cn)}(${o})` : `$setup[${JSON.stringify(o)}]`;
}
function Op(r, e, t = r.props, s, i, n = !1) {
  const { tag: a, loc: o, children: l } = r;
  let u = [];
  const c = [], h = [], f = l.length > 0;
  let p = !1, x = 0, d = !1, m = !1, y = !1, _ = !1, T = !1, C = !1;
  const v = [], w = ({ key: P, value: g }) => {
    if (ht(P)) {
      const E = P.content, O = Tf(E);
      if (O && (!s || i) && E.toLowerCase() !== "onclick" && E !== "onUpdate:modelValue" && !xc(E) && (_ = !0), O && xc(E) && (C = !0), g.type === 20 || (g.type === 4 || g.type === 8) && Ct(g, e) > 0)
        return;
      E === "ref" ? d = !0 : E === "class" ? m = !0 : E === "style" ? y = !0 : E !== "key" && !v.includes(E) && v.push(E), s && (E === "class" || E === "style") && !v.includes(E) && v.push(E);
    } else
      T = !0;
  };
  for (let P = 0; P < t.length; P++) {
    const g = t[P];
    if (g.type === 6) {
      const { loc: E, name: O, value: S } = g;
      let W = !0;
      if (O === "ref" && (d = !0, e.scopes.vFor > 0 && u.push(Ue(fe("ref_for", !0), fe("true"))), S && e.inline && e.bindingMetadata[S.content] && (W = !1, u.push(Ue(fe("ref_key", !0), fe(S.content, !0, S.loc))))), O === "is" && (Bo(a) || S && S.content.startsWith("vue:") || !1))
        continue;
      u.push(Ue(fe(O, !0, mp(E, 0, O.length)), fe(S ? S.content : "", W, S ? S.loc : E)));
    } else {
      const { name: E, arg: O, exp: S, loc: W } = g, Q = E === "bind", xe = E === "on";
      if (E === "slot") {
        s || e.onError(Fe(40, W));
        continue;
      }
      if (E === "once" || E === "memo" || E === "is" || Q && os(O, "is") && (Bo(a) || !1) || xe && n)
        continue;
      if ((Q && os(O, "key") || xe && f && os(O, "vue:before-update")) && (p = !0), Q && os(O, "ref") && e.scopes.vFor > 0 && u.push(Ue(fe("ref_for", !0), fe("true"))), !O && (Q || xe)) {
        T = !0, S ? (u.length && (c.push(gt(Ka(u), o)), u = []), Q ? c.push(S) : c.push({
          type: 14,
          loc: W,
          callee: e.helper(Ll),
          arguments: [S]
        })) : e.onError(Fe(Q ? 34 : 35, W));
        continue;
      }
      const re = e.directiveTransforms[E];
      if (re) {
        const { props: J, needRuntime: ce } = re(g, r, e);
        !n && J.forEach(w), u.push(...J), ce && (h.push(g), Af(ce) && Np.set(g, ce));
      } else
        m0(E) || (h.push(g), f && (p = !0));
    }
  }
  let N;
  if (c.length ? (u.length && c.push(gt(Ka(u), o)), c.length > 1 ? N = $e(e.helper(_n), c, o) : N = c[0]) : u.length && (N = gt(Ka(u), o)), T ? x |= 16 : (m && !s && (x |= 2), y && !s && (x |= 4), v.length && (x |= 8), _ && (x |= 32)), !p && (x === 0 || x === 32) && (d || C || h.length > 0) && (x |= 512), !e.inSSR && N)
    switch (N.type) {
      case 15:
        let P = -1, g = -1, E = !1;
        for (let W = 0; W < N.properties.length; W++) {
          const Q = N.properties[W].key;
          ht(Q) ? Q.content === "class" ? P = W : Q.content === "style" && (g = W) : Q.isHandlerKey || (E = !0);
        }
        const O = N.properties[P], S = N.properties[g];
        E ? N = $e(e.helper(gi), [N]) : (O && !ht(O.value) && (O.value = $e(e.helper(kl), [O.value])), S && (y || S.value.type === 4 && S.value.content.trim()[0] === "[" || S.value.type === 17) && (S.value = $e(e.helper(Ml), [S.value])));
        break;
      case 14:
        break;
      default:
        N = $e(e.helper(gi), [
          $e(e.helper(Li), [
            N
          ])
        ]);
        break;
    }
  return {
    props: N,
    directives: h,
    patchFlag: x,
    dynamicPropNames: v,
    shouldUseBlock: p
  };
}
function Ka(r) {
  const e = /* @__PURE__ */ new Map(), t = [];
  for (let s = 0; s < r.length; s++) {
    const i = r[s];
    if (i.key.type === 8 || !i.key.isStatic) {
      t.push(i);
      continue;
    }
    const n = i.key.content, a = e.get(n);
    a ? (n === "style" || n === "class" || Tf(n)) && pb(a, i) : (e.set(n, i), t.push(i));
  }
  return t;
}
function pb(r, e) {
  r.value.type === 17 ? r.value.elements.push(e.value) : r.value = ha([r.value, e.value], r.loc);
}
function db(r, e) {
  const t = [], s = Np.get(r);
  if (s)
    t.push(e.helperString(s));
  else {
    const n = Fo("v-" + r.name, e);
    n ? t.push(n) : (e.helper(Lf), e.directives.add(r.name), t.push(Lo(r.name, "directive")));
  }
  const { loc: i } = r;
  if (r.exp && t.push(r.exp), r.arg && (r.exp || t.push("void 0"), t.push(r.arg)), Object.keys(r.modifiers).length) {
    r.arg || (r.exp || t.push("void 0"), t.push("void 0"));
    const n = fe("true", !1, i);
    t.push(gt(r.modifiers.map((a) => Ue(a, n)), i));
  }
  return ha(t, r.loc);
}
function mb(r) {
  let e = "[";
  for (let t = 0, s = r.length; t < s; t++)
    e += JSON.stringify(r[t]), t < s - 1 && (e += ", ");
  return e + "]";
}
function Bo(r) {
  return r === "component" || r === "Component";
}
const yb = (r, e) => {
  if (Mo(r)) {
    const { children: t, loc: s } = r, { slotName: i, slotProps: n } = gb(r, e), a = [
      e.prefixIdentifiers ? "_ctx.$slots" : "$slots",
      i,
      "{}",
      "undefined",
      "true"
    ];
    let o = 2;
    n && (a[2] = n, o = 3), t.length && (a[3] = ws([], t, !1, !1, s), o = 4), e.scopeId && !e.slotted && (o = 5), a.splice(o), r.codegenNode = $e(e.helper(Rf), a, s);
  }
};
function gb(r, e) {
  let t = '"default"', s;
  const i = [];
  for (let n = 0; n < r.props.length; n++) {
    const a = r.props[n];
    a.type === 6 ? a.value && (a.name === "name" ? t = JSON.stringify(a.value.content) : (a.name = xs(a.name), i.push(a))) : a.name === "bind" && os(a.arg, "name") ? a.exp && (t = a.exp) : (a.name === "bind" && a.arg && ht(a.arg) && (a.arg.content = xs(a.arg.content)), i.push(a));
  }
  if (i.length > 0) {
    const { props: n, directives: a } = Op(r, e, i, !1, !1);
    s = n, a.length && e.onError(Fe(36, a[0].loc));
  }
  return {
    slotName: t,
    slotProps: s
  };
}
const vb = /^\s*([\w$_]+|(async\s*)?\([^)]*?\))\s*=>|^\s*(async\s+)?function(?:\s+[\w$]+)?\s*\(/, kp = (r, e, t, s) => {
  const { loc: i, modifiers: n, arg: a } = r;
  !r.exp && !n.length && t.onError(Fe(35, i));
  let o;
  if (a.type === 4)
    if (a.isStatic) {
      let h = a.content;
      h.startsWith("vue:") && (h = `vnode-${h.slice(4)}`), o = fe(b0(xs(h)), !0, a.loc);
    } else
      o = rt([
        `${t.helperString(To)}(`,
        a,
        ")"
      ]);
  else
    o = a, o.children.unshift(`${t.helperString(To)}(`), o.children.push(")");
  let l = r.exp;
  l && !l.content.trim() && (l = void 0);
  let u = t.cacheHandlers && !l && !t.inVOnce;
  if (l) {
    const h = dp(l.content, t), f = !(h || vb.test(l.content)), p = l.content.includes(";");
    t.prefixIdentifiers && (f && t.addIdentifiers("$event"), l = r.exp = vt(l, t, !1, p), f && t.removeIdentifiers("$event"), u = t.cacheHandlers && !t.inVOnce && !(l.type === 4 && l.constType > 0) && !(h && e.tagType === 1) && !ot(l, t.identifiers), u && h && (l.type === 4 ? l.content = `${l.content} && ${l.content}(...args)` : l.children = [...l.children, " && ", ...l.children, "(...args)"])), (f || u && h) && (l = rt([
      `${f ? t.isTS ? "($event: any)" : "$event" : `${t.isTS ? `
//@ts-ignore
` : ""}(...args)`} => ${p ? "{" : "("}`,
      l,
      p ? "}" : ")"
    ]));
  }
  let c = {
    props: [
      Ue(o, l || fe("() => {}", !1, i))
    ]
  };
  return s && (c = s(c)), u && (c.props[0].value = t.cache(c.props[0].value)), c.props.forEach((h) => h.key.isHandlerKey = !0), c;
}, bb = (r, e, t) => {
  const { exp: s, modifiers: i, loc: n } = r, a = r.arg;
  return a.type !== 4 ? (a.children.unshift("("), a.children.push(') || ""')) : a.isStatic || (a.content = `${a.content} || ""`), i.includes("camel") && (a.type === 4 ? a.isStatic ? a.content = xs(a.content) : a.content = `${t.helperString(Eo)}(${a.content})` : (a.children.unshift(`${t.helperString(Eo)}(`), a.children.push(")"))), t.inSSR || (i.includes("prop") && Hc(a, "."), i.includes("attr") && Hc(a, "^")), !s || s.type === 4 && !s.content.trim() ? (t.onError(Fe(34, n)), {
    props: [Ue(a, fe("", !0, n))]
  }) : {
    props: [Ue(a, s)]
  };
}, Hc = (r, e) => {
  r.type === 4 ? r.isStatic ? r.content = e + r.content : r.content = `\`${e}\${${r.content}}\`` : (r.children.unshift(`'${e}' + (`), r.children.push(")"));
}, xb = (r, e) => {
  if (r.type === 0 || r.type === 1 || r.type === 11 || r.type === 10)
    return () => {
      const t = r.children;
      let s, i = !1;
      for (let n = 0; n < t.length; n++) {
        const a = t[n];
        if (Wa(a)) {
          i = !0;
          for (let o = n + 1; o < t.length; o++) {
            const l = t[o];
            if (Wa(l))
              s || (s = t[n] = rt([a], a.loc)), s.children.push(" + ", l), t.splice(o, 1), o--;
            else {
              s = void 0;
              break;
            }
          }
        }
      }
      if (!(!i || t.length === 1 && (r.type === 0 || r.type === 1 && r.tagType === 0 && !r.props.find((n) => n.type === 7 && !e.directiveTransforms[n.name]) && !0)))
        for (let n = 0; n < t.length; n++) {
          const a = t[n];
          if (Wa(a) || a.type === 8) {
            const o = [];
            (a.type !== 2 || a.content !== " ") && o.push(a), !e.ssr && Ct(a, e) === 0 && o.push(1 + ` /* ${wr[1]} */`), t[n] = {
              type: 12,
              content: a,
              loc: a.loc,
              codegenNode: $e(e.helper(Mf), o)
            };
          }
        }
    };
}, Kc = /* @__PURE__ */ new WeakSet(), Sb = (r, e) => {
  if (r.type === 1 && ut(r, "once", !0))
    return Kc.has(r) || e.inVOnce ? void 0 : (Kc.add(r), e.inVOnce = !0, e.helper(Bf), () => {
      e.inVOnce = !1;
      const t = e.currentNode;
      t.codegenNode && (t.codegenNode = e.cache(t.codegenNode, !0));
    });
}, Mp = (r, e, t) => {
  const { exp: s, arg: i } = r;
  if (!s)
    return t.onError(Fe(41, r.loc)), Ki();
  const n = s.loc.source, a = s.type === 4 ? s.content : n, o = t.bindingMetadata[n], l = t.inline && o && o !== "setup-const";
  if (!a.trim() || !dp(a, t) && !l)
    return t.onError(Fe(42, s.loc)), Ki();
  if (t.prefixIdentifiers && Fn(a) && t.identifiers[a])
    return t.onError(Fe(43, s.loc)), Ki();
  const u = i || fe("modelValue", !0), c = i ? ht(i) ? `onUpdate:${i.content}` : rt(['"onUpdate:" + ', i]) : "onUpdate:modelValue";
  let h;
  const f = t.isTS ? "($event: any)" : "$event";
  if (l)
    if (o === "setup-ref")
      h = rt([
        `${f} => ((`,
        fe(n, !1, s.loc),
        ").value = $event)"
      ]);
    else {
      const x = o === "setup-let" ? `${n} = $event` : "null";
      h = rt([
        `${f} => (${t.helperString(In)}(${n}) ? (`,
        fe(n, !1, s.loc),
        `).value = $event : ${x})`
      ]);
    }
  else
    h = rt([
      `${f} => ((`,
      s,
      ") = $event)"
    ]);
  const p = [
    Ue(u, r.exp),
    Ue(c, h)
  ];
  if (t.prefixIdentifiers && !t.inVOnce && t.cacheHandlers && !ot(s, t.identifiers) && (p[1].value = t.cache(p[1].value)), r.modifiers.length && e.tagType === 1) {
    const x = r.modifiers.map((m) => (Fn(m) ? m : JSON.stringify(m)) + ": true").join(", "), d = i ? ht(i) ? `${i.content}Modifiers` : rt([i, ' + "Modifiers"']) : "modelModifiers";
    p.push(Ue(d, fe(`{ ${x} }`, !1, r.loc, 2)));
  }
  return Ki(p);
};
function Ki(r = []) {
  return { props: r };
}
const Gc = /* @__PURE__ */ new WeakSet(), wb = (r, e) => {
  if (r.type === 1) {
    const t = ut(r, "memo");
    return !t || Gc.has(r) ? void 0 : (Gc.add(r), () => {
      const s = r.codegenNode || e.currentNode.codegenNode;
      s && s.type === 13 && (r.tagType !== 1 && vp(s, e), r.codegenNode = $e(e.helper(Dl), [
        t.exp,
        ws(void 0, s),
        "_cache",
        String(e.cached++)
      ]));
    });
  }
};
function Pb(r) {
  return [
    [
      Sb,
      Xv,
      wb,
      rb,
      ...r ? [
        lb,
        Jv
      ] : [],
      yb,
      hb,
      ob,
      xb
    ],
    {
      on: kp,
      bind: bb,
      model: Mp
    }
  ];
}
const Eb = () => ({ props: [] }), Lp = Symbol("vModelRadio"), Dp = Symbol("vModelCheckbox"), Rp = Symbol("vModelText"), Fp = Symbol("vModelSelect"), Uo = Symbol("vModelDynamic"), Bp = Symbol("vOnModifiersGuard"), Up = Symbol("vOnKeysGuard"), $p = Symbol("vShow"), jp = Symbol("Transition"), Tb = Symbol("TransitionGroup");
jf({
  [Lp]: "vModelRadio",
  [Dp]: "vModelCheckbox",
  [Rp]: "vModelText",
  [Fp]: "vModelSelect",
  [Uo]: "vModelDynamic",
  [Bp]: "withModifiers",
  [Up]: "withKeys",
  [$p]: "vShow",
  [jp]: "Transition",
  [Tb]: "TransitionGroup"
});
const Ab = (r) => {
  r.type === 1 && r.props.forEach((e, t) => {
    e.type === 6 && e.name === "style" && e.value && (r.props[t] = {
      type: 7,
      name: "bind",
      arg: fe("style", !0, e.loc),
      exp: _b(e.value.content, e.loc),
      modifiers: [],
      loc: e.loc
    });
  });
}, _b = (r, e) => {
  const t = u0(r);
  return fe(JSON.stringify(t), !1, e, 3);
};
function Ut(r, e) {
  return Fe(r, e, Cb);
}
const Cb = {
  [50]: "v-html is missing expression.",
  [51]: "v-html will override element children.",
  [52]: "v-text is missing expression.",
  [53]: "v-text will override element children.",
  [54]: "v-model can only be used on <input>, <textarea> and <select> elements.",
  [55]: "v-model argument is not supported on plain elements.",
  [56]: "v-model cannot be used on file inputs since they are read-only. Use a v-on:change listener instead.",
  [57]: "Unnecessary value binding used alongside v-model. It will interfere with v-model's behavior.",
  [58]: "v-show is missing expression.",
  [59]: "<Transition> expects exactly one child element or component.",
  [60]: "Tags with side effect (<script> and <style>) are ignored in client component templates."
}, Ib = (r, e, t) => {
  const { exp: s, loc: i } = r;
  return s || t.onError(Ut(50, i)), e.children.length && (t.onError(Ut(51, i)), e.children.length = 0), {
    props: [
      Ue(fe("innerHTML", !0, i), s || fe("", !0))
    ]
  };
}, Nb = (r, e, t) => {
  const { exp: s, loc: i } = r;
  return s || t.onError(Ut(52, i)), e.children.length && (t.onError(Ut(53, i)), e.children.length = 0), {
    props: [
      Ue(fe("textContent", !0), s ? Ct(s, t) > 0 ? s : $e(t.helperString(Ol), [s], i) : fe("", !0))
    ]
  };
}, Ob = (r, e, t) => {
  const s = Mp(r, e, t);
  if (!s.props.length || e.tagType === 1)
    return s;
  r.arg && t.onError(Ut(55, r.arg.loc));
  function i() {
    const o = Es(e, "value");
    o && t.onError(Ut(57, o.loc));
  }
  const { tag: n } = e, a = t.isCustomElement(n);
  if (n === "input" || n === "textarea" || n === "select" || a) {
    let o = Rp, l = !1;
    if (n === "input" || a) {
      const u = Es(e, "type");
      if (u) {
        if (u.type === 7)
          o = Uo;
        else if (u.value)
          switch (u.value.content) {
            case "radio":
              o = Lp;
              break;
            case "checkbox":
              o = Dp;
              break;
            case "file":
              l = !0, t.onError(Ut(56, r.loc));
              break;
            default:
              i();
              break;
          }
      } else
        bv(e) ? o = Uo : i();
    } else
      n === "select" ? o = Fp : i();
    l || (s.needRuntime = t.helper(o));
  } else
    t.onError(Ut(54, r.loc));
  return s.props = s.props.filter((o) => !(o.key.type === 4 && o.key.content === "modelValue")), s;
}, kb = /* @__PURE__ */ or("passive,once,capture"), Mb = /* @__PURE__ */ or(
  "stop,prevent,self,ctrl,shift,alt,meta,exact,middle"
), Lb = /* @__PURE__ */ or("left,right"), qp = /* @__PURE__ */ or("onkeyup,onkeydown,onkeypress", !0), Db = (r, e, t, s) => {
  const i = [], n = [], a = [];
  for (let o = 0; o < e.length; o++) {
    const l = e[o];
    kb(l) ? a.push(l) : Lb(l) ? ht(r) ? qp(r.content) ? i.push(l) : n.push(l) : (i.push(l), n.push(l)) : Mb(l) ? n.push(l) : i.push(l);
  }
  return {
    keyModifiers: i,
    nonKeyModifiers: n,
    eventOptionModifiers: a
  };
}, Yc = (r, e) => ht(r) && r.content.toLowerCase() === "onclick" ? fe(e, !0) : r.type !== 4 ? rt([
  "(",
  r,
  `) === "onClick" ? "${e}" : (`,
  r,
  ")"
]) : r, Rb = (r, e, t) => kp(r, e, t, (s) => {
  const { modifiers: i } = r;
  if (!i.length)
    return s;
  let { key: n, value: a } = s.props[0];
  const { keyModifiers: o, nonKeyModifiers: l, eventOptionModifiers: u } = Db(n, i, t, r.loc);
  if (l.includes("right") && (n = Yc(n, "onContextmenu")), l.includes("middle") && (n = Yc(n, "onMouseup")), l.length && (a = $e(t.helper(Bp), [
    a,
    JSON.stringify(l)
  ])), o.length && (!ht(n) || qp(n.content)) && (a = $e(t.helper(Up), [
    a,
    JSON.stringify(o)
  ])), u.length) {
    const c = u.map(ca).join("");
    n = ht(n) ? fe(`${n.content}${c}`, !0) : rt(["(", n, `) + "${c}"`]);
  }
  return {
    props: [Ue(n, a)]
  };
}), Fb = (r, e, t) => {
  const { exp: s, loc: i } = r;
  return s || t.onError(Ut(58, i)), {
    props: [],
    needRuntime: t.helper($p)
  };
}, Bb = (r, e) => {
  if (r.type === 1 && r.tagType === 1 && e.isBuiltInComponent(r.tag) === jp)
    return () => {
      if (!r.children.length)
        return;
      Vp(r) && e.onError(Ut(59, {
        start: r.children[0].loc.start,
        end: r.children[r.children.length - 1].loc.end,
        source: ""
      }));
      const s = r.children[0];
      if (s.type === 1)
        for (const i of s.props)
          i.type === 7 && i.name === "show" && r.props.push({
            type: 6,
            name: "persisted",
            value: void 0,
            loc: r.loc
          });
    };
};
function Vp(r) {
  const e = r.children = r.children.filter((s) => s.type !== 3 && !(s.type === 2 && !s.content.trim())), t = e[0];
  return e.length !== 1 || t.type === 11 || t.type === 9 && t.branches.some(Vp);
}
const Ub = [
  Ab,
  Bb
], $b = {
  cloak: Eb,
  html: Ib,
  text: Nb,
  model: Ob,
  on: Rb,
  show: Fb
};
var Ar = typeof Ar != "undefined" ? Ar : typeof self != "undefined" ? self : typeof window != "undefined" ? window : {}, Bt = [], mt = [], jb = typeof Uint8Array != "undefined" ? Uint8Array : Array, au = !1;
function zp() {
  au = !0;
  for (var r = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/", e = 0, t = r.length; e < t; ++e)
    Bt[e] = r[e], mt[r.charCodeAt(e)] = e;
  mt["-".charCodeAt(0)] = 62, mt["_".charCodeAt(0)] = 63;
}
function qb(r) {
  au || zp();
  var e, t, s, i, n, a, o = r.length;
  if (o % 4 > 0)
    throw new Error("Invalid string. Length must be a multiple of 4");
  n = r[o - 2] === "=" ? 2 : r[o - 1] === "=" ? 1 : 0, a = new jb(o * 3 / 4 - n), s = n > 0 ? o - 4 : o;
  var l = 0;
  for (e = 0, t = 0; e < s; e += 4, t += 3)
    i = mt[r.charCodeAt(e)] << 18 | mt[r.charCodeAt(e + 1)] << 12 | mt[r.charCodeAt(e + 2)] << 6 | mt[r.charCodeAt(e + 3)], a[l++] = i >> 16 & 255, a[l++] = i >> 8 & 255, a[l++] = i & 255;
  return n === 2 ? (i = mt[r.charCodeAt(e)] << 2 | mt[r.charCodeAt(e + 1)] >> 4, a[l++] = i & 255) : n === 1 && (i = mt[r.charCodeAt(e)] << 10 | mt[r.charCodeAt(e + 1)] << 4 | mt[r.charCodeAt(e + 2)] >> 2, a[l++] = i >> 8 & 255, a[l++] = i & 255), a;
}
function Vb(r) {
  return Bt[r >> 18 & 63] + Bt[r >> 12 & 63] + Bt[r >> 6 & 63] + Bt[r & 63];
}
function zb(r, e, t) {
  for (var s, i = [], n = e; n < t; n += 3)
    s = (r[n] << 16) + (r[n + 1] << 8) + r[n + 2], i.push(Vb(s));
  return i.join("");
}
function Jc(r) {
  au || zp();
  for (var e, t = r.length, s = t % 3, i = "", n = [], a = 16383, o = 0, l = t - s; o < l; o += a)
    n.push(zb(r, o, o + a > l ? l : o + a));
  return s === 1 ? (e = r[t - 1], i += Bt[e >> 2], i += Bt[e << 4 & 63], i += "==") : s === 2 && (e = (r[t - 2] << 8) + r[t - 1], i += Bt[e >> 10], i += Bt[e >> 4 & 63], i += Bt[e << 2 & 63], i += "="), n.push(i), n.join("");
}
function da(r, e, t, s, i) {
  var n, a, o = i * 8 - s - 1, l = (1 << o) - 1, u = l >> 1, c = -7, h = t ? i - 1 : 0, f = t ? -1 : 1, p = r[e + h];
  for (h += f, n = p & (1 << -c) - 1, p >>= -c, c += o; c > 0; n = n * 256 + r[e + h], h += f, c -= 8)
    ;
  for (a = n & (1 << -c) - 1, n >>= -c, c += s; c > 0; a = a * 256 + r[e + h], h += f, c -= 8)
    ;
  if (n === 0)
    n = 1 - u;
  else {
    if (n === l)
      return a ? NaN : (p ? -1 : 1) * (1 / 0);
    a = a + Math.pow(2, s), n = n - u;
  }
  return (p ? -1 : 1) * a * Math.pow(2, n - s);
}
function Wp(r, e, t, s, i, n) {
  var a, o, l, u = n * 8 - i - 1, c = (1 << u) - 1, h = c >> 1, f = i === 23 ? Math.pow(2, -24) - Math.pow(2, -77) : 0, p = s ? 0 : n - 1, x = s ? 1 : -1, d = e < 0 || e === 0 && 1 / e < 0 ? 1 : 0;
  for (e = Math.abs(e), isNaN(e) || e === 1 / 0 ? (o = isNaN(e) ? 1 : 0, a = c) : (a = Math.floor(Math.log(e) / Math.LN2), e * (l = Math.pow(2, -a)) < 1 && (a--, l *= 2), a + h >= 1 ? e += f / l : e += f * Math.pow(2, 1 - h), e * l >= 2 && (a++, l /= 2), a + h >= c ? (o = 0, a = c) : a + h >= 1 ? (o = (e * l - 1) * Math.pow(2, i), a = a + h) : (o = e * Math.pow(2, h - 1) * Math.pow(2, i), a = 0)); i >= 8; r[t + p] = o & 255, p += x, o /= 256, i -= 8)
    ;
  for (a = a << i | o, u += i; u > 0; r[t + p] = a & 255, p += x, a /= 256, u -= 8)
    ;
  r[t + p - x] |= d * 128;
}
var Wb = {}.toString, Hp = Array.isArray || function(r) {
  return Wb.call(r) == "[object Array]";
};
/*!
 * The buffer module from node.js, for the browser.
 *
 * @author   Feross Aboukhadijeh <feross@feross.org> <http://feross.org>
 * @license  MIT
 */
var Hb = 50;
F.TYPED_ARRAY_SUPPORT = Ar.TYPED_ARRAY_SUPPORT !== void 0 ? Ar.TYPED_ARRAY_SUPPORT : !0;
function $o() {
  return F.TYPED_ARRAY_SUPPORT ? 2147483647 : 1073741823;
}
function Jt(r, e) {
  if ($o() < e)
    throw new RangeError("Invalid typed array length");
  return F.TYPED_ARRAY_SUPPORT ? (r = new Uint8Array(e), r.__proto__ = F.prototype) : (r === null && (r = new F(e)), r.length = e), r;
}
function F(r, e, t) {
  if (!F.TYPED_ARRAY_SUPPORT && !(this instanceof F))
    return new F(r, e, t);
  if (typeof r == "number") {
    if (typeof e == "string")
      throw new Error(
        "If encoding is specified then the first argument must be a string"
      );
    return ou(this, r);
  }
  return Kp(this, r, e, t);
}
F.poolSize = 8192;
F._augment = function(r) {
  return r.__proto__ = F.prototype, r;
};
function Kp(r, e, t, s) {
  if (typeof e == "number")
    throw new TypeError('"value" argument must not be a number');
  return typeof ArrayBuffer != "undefined" && e instanceof ArrayBuffer ? Yb(r, e, t, s) : typeof e == "string" ? Gb(r, e, t) : Jb(r, e);
}
F.from = function(r, e, t) {
  return Kp(null, r, e, t);
};
F.TYPED_ARRAY_SUPPORT && (F.prototype.__proto__ = Uint8Array.prototype, F.__proto__ = Uint8Array);
function Gp(r) {
  if (typeof r != "number")
    throw new TypeError('"size" argument must be a number');
  if (r < 0)
    throw new RangeError('"size" argument must not be negative');
}
function Kb(r, e, t, s) {
  return Gp(e), e <= 0 ? Jt(r, e) : t !== void 0 ? typeof s == "string" ? Jt(r, e).fill(t, s) : Jt(r, e).fill(t) : Jt(r, e);
}
F.alloc = function(r, e, t) {
  return Kb(null, r, e, t);
};
function ou(r, e) {
  if (Gp(e), r = Jt(r, e < 0 ? 0 : lu(e) | 0), !F.TYPED_ARRAY_SUPPORT)
    for (var t = 0; t < e; ++t)
      r[t] = 0;
  return r;
}
F.allocUnsafe = function(r) {
  return ou(null, r);
};
F.allocUnsafeSlow = function(r) {
  return ou(null, r);
};
function Gb(r, e, t) {
  if ((typeof t != "string" || t === "") && (t = "utf8"), !F.isEncoding(t))
    throw new TypeError('"encoding" must be a valid string encoding');
  var s = Yp(e, t) | 0;
  r = Jt(r, s);
  var i = r.write(e, t);
  return i !== s && (r = r.slice(0, i)), r;
}
function jo(r, e) {
  var t = e.length < 0 ? 0 : lu(e.length) | 0;
  r = Jt(r, t);
  for (var s = 0; s < t; s += 1)
    r[s] = e[s] & 255;
  return r;
}
function Yb(r, e, t, s) {
  if (e.byteLength, t < 0 || e.byteLength < t)
    throw new RangeError("'offset' is out of bounds");
  if (e.byteLength < t + (s || 0))
    throw new RangeError("'length' is out of bounds");
  return t === void 0 && s === void 0 ? e = new Uint8Array(e) : s === void 0 ? e = new Uint8Array(e, t) : e = new Uint8Array(e, t, s), F.TYPED_ARRAY_SUPPORT ? (r = e, r.__proto__ = F.prototype) : r = jo(r, e), r;
}
function Jb(r, e) {
  if (Vt(e)) {
    var t = lu(e.length) | 0;
    return r = Jt(r, t), r.length === 0 || e.copy(r, 0, 0, t), r;
  }
  if (e) {
    if (typeof ArrayBuffer != "undefined" && e.buffer instanceof ArrayBuffer || "length" in e)
      return typeof e.length != "number" || mx(e.length) ? Jt(r, 0) : jo(r, e);
    if (e.type === "Buffer" && Hp(e.data))
      return jo(r, e.data);
  }
  throw new TypeError("First argument must be a string, Buffer, ArrayBuffer, Array, or array-like object.");
}
function lu(r) {
  if (r >= $o())
    throw new RangeError("Attempt to allocate Buffer larger than maximum size: 0x" + $o().toString(16) + " bytes");
  return r | 0;
}
F.isBuffer = yx;
function Vt(r) {
  return !!(r != null && r._isBuffer);
}
F.compare = function(e, t) {
  if (!Vt(e) || !Vt(t))
    throw new TypeError("Arguments must be Buffers");
  if (e === t)
    return 0;
  for (var s = e.length, i = t.length, n = 0, a = Math.min(s, i); n < a; ++n)
    if (e[n] !== t[n]) {
      s = e[n], i = t[n];
      break;
    }
  return s < i ? -1 : i < s ? 1 : 0;
};
F.isEncoding = function(e) {
  switch (String(e).toLowerCase()) {
    case "hex":
    case "utf8":
    case "utf-8":
    case "ascii":
    case "latin1":
    case "binary":
    case "base64":
    case "ucs2":
    case "ucs-2":
    case "utf16le":
    case "utf-16le":
      return !0;
    default:
      return !1;
  }
};
F.concat = function(e, t) {
  if (!Hp(e))
    throw new TypeError('"list" argument must be an Array of Buffers');
  if (e.length === 0)
    return F.alloc(0);
  var s;
  if (t === void 0)
    for (t = 0, s = 0; s < e.length; ++s)
      t += e[s].length;
  var i = F.allocUnsafe(t), n = 0;
  for (s = 0; s < e.length; ++s) {
    var a = e[s];
    if (!Vt(a))
      throw new TypeError('"list" argument must be an Array of Buffers');
    a.copy(i, n), n += a.length;
  }
  return i;
};
function Yp(r, e) {
  if (Vt(r))
    return r.length;
  if (typeof ArrayBuffer != "undefined" && typeof ArrayBuffer.isView == "function" && (ArrayBuffer.isView(r) || r instanceof ArrayBuffer))
    return r.byteLength;
  typeof r != "string" && (r = "" + r);
  var t = r.length;
  if (t === 0)
    return 0;
  for (var s = !1; ; )
    switch (e) {
      case "ascii":
      case "latin1":
      case "binary":
        return t;
      case "utf8":
      case "utf-8":
      case void 0:
        return $n(r).length;
      case "ucs2":
      case "ucs-2":
      case "utf16le":
      case "utf-16le":
        return t * 2;
      case "hex":
        return t >>> 1;
      case "base64":
        return rd(r).length;
      default:
        if (s)
          return $n(r).length;
        e = ("" + e).toLowerCase(), s = !0;
    }
}
F.byteLength = Yp;
function Qb(r, e, t) {
  var s = !1;
  if ((e === void 0 || e < 0) && (e = 0), e > this.length || ((t === void 0 || t > this.length) && (t = this.length), t <= 0) || (t >>>= 0, e >>>= 0, t <= e))
    return "";
  for (r || (r = "utf8"); ; )
    switch (r) {
      case "hex":
        return ox(this, e, t);
      case "utf8":
      case "utf-8":
        return Xp(this, e, t);
      case "ascii":
        return nx(this, e, t);
      case "latin1":
      case "binary":
        return ax(this, e, t);
      case "base64":
        return sx(this, e, t);
      case "ucs2":
      case "ucs-2":
      case "utf16le":
      case "utf-16le":
        return lx(this, e, t);
      default:
        if (s)
          throw new TypeError("Unknown encoding: " + r);
        r = (r + "").toLowerCase(), s = !0;
    }
}
F.prototype._isBuffer = !0;
function Vr(r, e, t) {
  var s = r[e];
  r[e] = r[t], r[t] = s;
}
F.prototype.swap16 = function() {
  var e = this.length;
  if (e % 2 !== 0)
    throw new RangeError("Buffer size must be a multiple of 16-bits");
  for (var t = 0; t < e; t += 2)
    Vr(this, t, t + 1);
  return this;
};
F.prototype.swap32 = function() {
  var e = this.length;
  if (e % 4 !== 0)
    throw new RangeError("Buffer size must be a multiple of 32-bits");
  for (var t = 0; t < e; t += 4)
    Vr(this, t, t + 3), Vr(this, t + 1, t + 2);
  return this;
};
F.prototype.swap64 = function() {
  var e = this.length;
  if (e % 8 !== 0)
    throw new RangeError("Buffer size must be a multiple of 64-bits");
  for (var t = 0; t < e; t += 8)
    Vr(this, t, t + 7), Vr(this, t + 1, t + 6), Vr(this, t + 2, t + 5), Vr(this, t + 3, t + 4);
  return this;
};
F.prototype.toString = function() {
  var e = this.length | 0;
  return e === 0 ? "" : arguments.length === 0 ? Xp(this, 0, e) : Qb.apply(this, arguments);
};
F.prototype.equals = function(e) {
  if (!Vt(e))
    throw new TypeError("Argument must be a Buffer");
  return this === e ? !0 : F.compare(this, e) === 0;
};
F.prototype.inspect = function() {
  var e = "", t = Hb;
  return this.length > 0 && (e = this.toString("hex", 0, t).match(/.{2}/g).join(" "), this.length > t && (e += " ... ")), "<Buffer " + e + ">";
};
F.prototype.compare = function(e, t, s, i, n) {
  if (!Vt(e))
    throw new TypeError("Argument must be a Buffer");
  if (t === void 0 && (t = 0), s === void 0 && (s = e ? e.length : 0), i === void 0 && (i = 0), n === void 0 && (n = this.length), t < 0 || s > e.length || i < 0 || n > this.length)
    throw new RangeError("out of range index");
  if (i >= n && t >= s)
    return 0;
  if (i >= n)
    return -1;
  if (t >= s)
    return 1;
  if (t >>>= 0, s >>>= 0, i >>>= 0, n >>>= 0, this === e)
    return 0;
  for (var a = n - i, o = s - t, l = Math.min(a, o), u = this.slice(i, n), c = e.slice(t, s), h = 0; h < l; ++h)
    if (u[h] !== c[h]) {
      a = u[h], o = c[h];
      break;
    }
  return a < o ? -1 : o < a ? 1 : 0;
};
function Jp(r, e, t, s, i) {
  if (r.length === 0)
    return -1;
  if (typeof t == "string" ? (s = t, t = 0) : t > 2147483647 ? t = 2147483647 : t < -2147483648 && (t = -2147483648), t = +t, isNaN(t) && (t = i ? 0 : r.length - 1), t < 0 && (t = r.length + t), t >= r.length) {
    if (i)
      return -1;
    t = r.length - 1;
  } else if (t < 0)
    if (i)
      t = 0;
    else
      return -1;
  if (typeof e == "string" && (e = F.from(e, s)), Vt(e))
    return e.length === 0 ? -1 : Qc(r, e, t, s, i);
  if (typeof e == "number")
    return e = e & 255, F.TYPED_ARRAY_SUPPORT && typeof Uint8Array.prototype.indexOf == "function" ? i ? Uint8Array.prototype.indexOf.call(r, e, t) : Uint8Array.prototype.lastIndexOf.call(r, e, t) : Qc(r, [e], t, s, i);
  throw new TypeError("val must be string, number or Buffer");
}
function Qc(r, e, t, s, i) {
  var n = 1, a = r.length, o = e.length;
  if (s !== void 0 && (s = String(s).toLowerCase(), s === "ucs2" || s === "ucs-2" || s === "utf16le" || s === "utf-16le")) {
    if (r.length < 2 || e.length < 2)
      return -1;
    n = 2, a /= 2, o /= 2, t /= 2;
  }
  function l(p, x) {
    return n === 1 ? p[x] : p.readUInt16BE(x * n);
  }
  var u;
  if (i) {
    var c = -1;
    for (u = t; u < a; u++)
      if (l(r, u) === l(e, c === -1 ? 0 : u - c)) {
        if (c === -1 && (c = u), u - c + 1 === o)
          return c * n;
      } else
        c !== -1 && (u -= u - c), c = -1;
  } else
    for (t + o > a && (t = a - o), u = t; u >= 0; u--) {
      for (var h = !0, f = 0; f < o; f++)
        if (l(r, u + f) !== l(e, f)) {
          h = !1;
          break;
        }
      if (h)
        return u;
    }
  return -1;
}
F.prototype.includes = function(e, t, s) {
  return this.indexOf(e, t, s) !== -1;
};
F.prototype.indexOf = function(e, t, s) {
  return Jp(this, e, t, s, !0);
};
F.prototype.lastIndexOf = function(e, t, s) {
  return Jp(this, e, t, s, !1);
};
function Xb(r, e, t, s) {
  t = Number(t) || 0;
  var i = r.length - t;
  s ? (s = Number(s), s > i && (s = i)) : s = i;
  var n = e.length;
  if (n % 2 !== 0)
    throw new TypeError("Invalid hex string");
  s > n / 2 && (s = n / 2);
  for (var a = 0; a < s; ++a) {
    var o = parseInt(e.substr(a * 2, 2), 16);
    if (isNaN(o))
      return a;
    r[t + a] = o;
  }
  return a;
}
function Zb(r, e, t, s) {
  return ga($n(e, r.length - t), r, t, s);
}
function Qp(r, e, t, s) {
  return ga(px(e), r, t, s);
}
function ex(r, e, t, s) {
  return Qp(r, e, t, s);
}
function tx(r, e, t, s) {
  return ga(rd(e), r, t, s);
}
function rx(r, e, t, s) {
  return ga(dx(e, r.length - t), r, t, s);
}
F.prototype.write = function(e, t, s, i) {
  if (t === void 0)
    i = "utf8", s = this.length, t = 0;
  else if (s === void 0 && typeof t == "string")
    i = t, s = this.length, t = 0;
  else if (isFinite(t))
    t = t | 0, isFinite(s) ? (s = s | 0, i === void 0 && (i = "utf8")) : (i = s, s = void 0);
  else
    throw new Error(
      "Buffer.write(string, encoding, offset[, length]) is no longer supported"
    );
  var n = this.length - t;
  if ((s === void 0 || s > n) && (s = n), e.length > 0 && (s < 0 || t < 0) || t > this.length)
    throw new RangeError("Attempt to write outside buffer bounds");
  i || (i = "utf8");
  for (var a = !1; ; )
    switch (i) {
      case "hex":
        return Xb(this, e, t, s);
      case "utf8":
      case "utf-8":
        return Zb(this, e, t, s);
      case "ascii":
        return Qp(this, e, t, s);
      case "latin1":
      case "binary":
        return ex(this, e, t, s);
      case "base64":
        return tx(this, e, t, s);
      case "ucs2":
      case "ucs-2":
      case "utf16le":
      case "utf-16le":
        return rx(this, e, t, s);
      default:
        if (a)
          throw new TypeError("Unknown encoding: " + i);
        i = ("" + i).toLowerCase(), a = !0;
    }
};
F.prototype.toJSON = function() {
  return {
    type: "Buffer",
    data: Array.prototype.slice.call(this._arr || this, 0)
  };
};
function sx(r, e, t) {
  return e === 0 && t === r.length ? Jc(r) : Jc(r.slice(e, t));
}
function Xp(r, e, t) {
  t = Math.min(r.length, t);
  for (var s = [], i = e; i < t; ) {
    var n = r[i], a = null, o = n > 239 ? 4 : n > 223 ? 3 : n > 191 ? 2 : 1;
    if (i + o <= t) {
      var l, u, c, h;
      switch (o) {
        case 1:
          n < 128 && (a = n);
          break;
        case 2:
          l = r[i + 1], (l & 192) === 128 && (h = (n & 31) << 6 | l & 63, h > 127 && (a = h));
          break;
        case 3:
          l = r[i + 1], u = r[i + 2], (l & 192) === 128 && (u & 192) === 128 && (h = (n & 15) << 12 | (l & 63) << 6 | u & 63, h > 2047 && (h < 55296 || h > 57343) && (a = h));
          break;
        case 4:
          l = r[i + 1], u = r[i + 2], c = r[i + 3], (l & 192) === 128 && (u & 192) === 128 && (c & 192) === 128 && (h = (n & 15) << 18 | (l & 63) << 12 | (u & 63) << 6 | c & 63, h > 65535 && h < 1114112 && (a = h));
      }
    }
    a === null ? (a = 65533, o = 1) : a > 65535 && (a -= 65536, s.push(a >>> 10 & 1023 | 55296), a = 56320 | a & 1023), s.push(a), i += o;
  }
  return ix(s);
}
var Xc = 4096;
function ix(r) {
  var e = r.length;
  if (e <= Xc)
    return String.fromCharCode.apply(String, r);
  for (var t = "", s = 0; s < e; )
    t += String.fromCharCode.apply(
      String,
      r.slice(s, s += Xc)
    );
  return t;
}
function nx(r, e, t) {
  var s = "";
  t = Math.min(r.length, t);
  for (var i = e; i < t; ++i)
    s += String.fromCharCode(r[i] & 127);
  return s;
}
function ax(r, e, t) {
  var s = "";
  t = Math.min(r.length, t);
  for (var i = e; i < t; ++i)
    s += String.fromCharCode(r[i]);
  return s;
}
function ox(r, e, t) {
  var s = r.length;
  (!e || e < 0) && (e = 0), (!t || t < 0 || t > s) && (t = s);
  for (var i = "", n = e; n < t; ++n)
    i += fx(r[n]);
  return i;
}
function lx(r, e, t) {
  for (var s = r.slice(e, t), i = "", n = 0; n < s.length; n += 2)
    i += String.fromCharCode(s[n] + s[n + 1] * 256);
  return i;
}
F.prototype.slice = function(e, t) {
  var s = this.length;
  e = ~~e, t = t === void 0 ? s : ~~t, e < 0 ? (e += s, e < 0 && (e = 0)) : e > s && (e = s), t < 0 ? (t += s, t < 0 && (t = 0)) : t > s && (t = s), t < e && (t = e);
  var i;
  if (F.TYPED_ARRAY_SUPPORT)
    i = this.subarray(e, t), i.__proto__ = F.prototype;
  else {
    var n = t - e;
    i = new F(n, void 0);
    for (var a = 0; a < n; ++a)
      i[a] = this[a + e];
  }
  return i;
};
function We(r, e, t) {
  if (r % 1 !== 0 || r < 0)
    throw new RangeError("offset is not uint");
  if (r + e > t)
    throw new RangeError("Trying to access beyond buffer length");
}
F.prototype.readUIntLE = function(e, t, s) {
  e = e | 0, t = t | 0, s || We(e, t, this.length);
  for (var i = this[e], n = 1, a = 0; ++a < t && (n *= 256); )
    i += this[e + a] * n;
  return i;
};
F.prototype.readUIntBE = function(e, t, s) {
  e = e | 0, t = t | 0, s || We(e, t, this.length);
  for (var i = this[e + --t], n = 1; t > 0 && (n *= 256); )
    i += this[e + --t] * n;
  return i;
};
F.prototype.readUInt8 = function(e, t) {
  return t || We(e, 1, this.length), this[e];
};
F.prototype.readUInt16LE = function(e, t) {
  return t || We(e, 2, this.length), this[e] | this[e + 1] << 8;
};
F.prototype.readUInt16BE = function(e, t) {
  return t || We(e, 2, this.length), this[e] << 8 | this[e + 1];
};
F.prototype.readUInt32LE = function(e, t) {
  return t || We(e, 4, this.length), (this[e] | this[e + 1] << 8 | this[e + 2] << 16) + this[e + 3] * 16777216;
};
F.prototype.readUInt32BE = function(e, t) {
  return t || We(e, 4, this.length), this[e] * 16777216 + (this[e + 1] << 16 | this[e + 2] << 8 | this[e + 3]);
};
F.prototype.readIntLE = function(e, t, s) {
  e = e | 0, t = t | 0, s || We(e, t, this.length);
  for (var i = this[e], n = 1, a = 0; ++a < t && (n *= 256); )
    i += this[e + a] * n;
  return n *= 128, i >= n && (i -= Math.pow(2, 8 * t)), i;
};
F.prototype.readIntBE = function(e, t, s) {
  e = e | 0, t = t | 0, s || We(e, t, this.length);
  for (var i = t, n = 1, a = this[e + --i]; i > 0 && (n *= 256); )
    a += this[e + --i] * n;
  return n *= 128, a >= n && (a -= Math.pow(2, 8 * t)), a;
};
F.prototype.readInt8 = function(e, t) {
  return t || We(e, 1, this.length), this[e] & 128 ? (255 - this[e] + 1) * -1 : this[e];
};
F.prototype.readInt16LE = function(e, t) {
  t || We(e, 2, this.length);
  var s = this[e] | this[e + 1] << 8;
  return s & 32768 ? s | 4294901760 : s;
};
F.prototype.readInt16BE = function(e, t) {
  t || We(e, 2, this.length);
  var s = this[e + 1] | this[e] << 8;
  return s & 32768 ? s | 4294901760 : s;
};
F.prototype.readInt32LE = function(e, t) {
  return t || We(e, 4, this.length), this[e] | this[e + 1] << 8 | this[e + 2] << 16 | this[e + 3] << 24;
};
F.prototype.readInt32BE = function(e, t) {
  return t || We(e, 4, this.length), this[e] << 24 | this[e + 1] << 16 | this[e + 2] << 8 | this[e + 3];
};
F.prototype.readFloatLE = function(e, t) {
  return t || We(e, 4, this.length), da(this, e, !0, 23, 4);
};
F.prototype.readFloatBE = function(e, t) {
  return t || We(e, 4, this.length), da(this, e, !1, 23, 4);
};
F.prototype.readDoubleLE = function(e, t) {
  return t || We(e, 8, this.length), da(this, e, !0, 52, 8);
};
F.prototype.readDoubleBE = function(e, t) {
  return t || We(e, 8, this.length), da(this, e, !1, 52, 8);
};
function it(r, e, t, s, i, n) {
  if (!Vt(r))
    throw new TypeError('"buffer" argument must be a Buffer instance');
  if (e > i || e < n)
    throw new RangeError('"value" argument is out of bounds');
  if (t + s > r.length)
    throw new RangeError("Index out of range");
}
F.prototype.writeUIntLE = function(e, t, s, i) {
  if (e = +e, t = t | 0, s = s | 0, !i) {
    var n = Math.pow(2, 8 * s) - 1;
    it(this, e, t, s, n, 0);
  }
  var a = 1, o = 0;
  for (this[t] = e & 255; ++o < s && (a *= 256); )
    this[t + o] = e / a & 255;
  return t + s;
};
F.prototype.writeUIntBE = function(e, t, s, i) {
  if (e = +e, t = t | 0, s = s | 0, !i) {
    var n = Math.pow(2, 8 * s) - 1;
    it(this, e, t, s, n, 0);
  }
  var a = s - 1, o = 1;
  for (this[t + a] = e & 255; --a >= 0 && (o *= 256); )
    this[t + a] = e / o & 255;
  return t + s;
};
F.prototype.writeUInt8 = function(e, t, s) {
  return e = +e, t = t | 0, s || it(this, e, t, 1, 255, 0), F.TYPED_ARRAY_SUPPORT || (e = Math.floor(e)), this[t] = e & 255, t + 1;
};
function ma(r, e, t, s) {
  e < 0 && (e = 65535 + e + 1);
  for (var i = 0, n = Math.min(r.length - t, 2); i < n; ++i)
    r[t + i] = (e & 255 << 8 * (s ? i : 1 - i)) >>> (s ? i : 1 - i) * 8;
}
F.prototype.writeUInt16LE = function(e, t, s) {
  return e = +e, t = t | 0, s || it(this, e, t, 2, 65535, 0), F.TYPED_ARRAY_SUPPORT ? (this[t] = e & 255, this[t + 1] = e >>> 8) : ma(this, e, t, !0), t + 2;
};
F.prototype.writeUInt16BE = function(e, t, s) {
  return e = +e, t = t | 0, s || it(this, e, t, 2, 65535, 0), F.TYPED_ARRAY_SUPPORT ? (this[t] = e >>> 8, this[t + 1] = e & 255) : ma(this, e, t, !1), t + 2;
};
function ya(r, e, t, s) {
  e < 0 && (e = 4294967295 + e + 1);
  for (var i = 0, n = Math.min(r.length - t, 4); i < n; ++i)
    r[t + i] = e >>> (s ? i : 3 - i) * 8 & 255;
}
F.prototype.writeUInt32LE = function(e, t, s) {
  return e = +e, t = t | 0, s || it(this, e, t, 4, 4294967295, 0), F.TYPED_ARRAY_SUPPORT ? (this[t + 3] = e >>> 24, this[t + 2] = e >>> 16, this[t + 1] = e >>> 8, this[t] = e & 255) : ya(this, e, t, !0), t + 4;
};
F.prototype.writeUInt32BE = function(e, t, s) {
  return e = +e, t = t | 0, s || it(this, e, t, 4, 4294967295, 0), F.TYPED_ARRAY_SUPPORT ? (this[t] = e >>> 24, this[t + 1] = e >>> 16, this[t + 2] = e >>> 8, this[t + 3] = e & 255) : ya(this, e, t, !1), t + 4;
};
F.prototype.writeIntLE = function(e, t, s, i) {
  if (e = +e, t = t | 0, !i) {
    var n = Math.pow(2, 8 * s - 1);
    it(this, e, t, s, n - 1, -n);
  }
  var a = 0, o = 1, l = 0;
  for (this[t] = e & 255; ++a < s && (o *= 256); )
    e < 0 && l === 0 && this[t + a - 1] !== 0 && (l = 1), this[t + a] = (e / o >> 0) - l & 255;
  return t + s;
};
F.prototype.writeIntBE = function(e, t, s, i) {
  if (e = +e, t = t | 0, !i) {
    var n = Math.pow(2, 8 * s - 1);
    it(this, e, t, s, n - 1, -n);
  }
  var a = s - 1, o = 1, l = 0;
  for (this[t + a] = e & 255; --a >= 0 && (o *= 256); )
    e < 0 && l === 0 && this[t + a + 1] !== 0 && (l = 1), this[t + a] = (e / o >> 0) - l & 255;
  return t + s;
};
F.prototype.writeInt8 = function(e, t, s) {
  return e = +e, t = t | 0, s || it(this, e, t, 1, 127, -128), F.TYPED_ARRAY_SUPPORT || (e = Math.floor(e)), e < 0 && (e = 255 + e + 1), this[t] = e & 255, t + 1;
};
F.prototype.writeInt16LE = function(e, t, s) {
  return e = +e, t = t | 0, s || it(this, e, t, 2, 32767, -32768), F.TYPED_ARRAY_SUPPORT ? (this[t] = e & 255, this[t + 1] = e >>> 8) : ma(this, e, t, !0), t + 2;
};
F.prototype.writeInt16BE = function(e, t, s) {
  return e = +e, t = t | 0, s || it(this, e, t, 2, 32767, -32768), F.TYPED_ARRAY_SUPPORT ? (this[t] = e >>> 8, this[t + 1] = e & 255) : ma(this, e, t, !1), t + 2;
};
F.prototype.writeInt32LE = function(e, t, s) {
  return e = +e, t = t | 0, s || it(this, e, t, 4, 2147483647, -2147483648), F.TYPED_ARRAY_SUPPORT ? (this[t] = e & 255, this[t + 1] = e >>> 8, this[t + 2] = e >>> 16, this[t + 3] = e >>> 24) : ya(this, e, t, !0), t + 4;
};
F.prototype.writeInt32BE = function(e, t, s) {
  return e = +e, t = t | 0, s || it(this, e, t, 4, 2147483647, -2147483648), e < 0 && (e = 4294967295 + e + 1), F.TYPED_ARRAY_SUPPORT ? (this[t] = e >>> 24, this[t + 1] = e >>> 16, this[t + 2] = e >>> 8, this[t + 3] = e & 255) : ya(this, e, t, !1), t + 4;
};
function Zp(r, e, t, s, i, n) {
  if (t + s > r.length)
    throw new RangeError("Index out of range");
  if (t < 0)
    throw new RangeError("Index out of range");
}
function ed(r, e, t, s, i) {
  return i || Zp(r, e, t, 4), Wp(r, e, t, s, 23, 4), t + 4;
}
F.prototype.writeFloatLE = function(e, t, s) {
  return ed(this, e, t, !0, s);
};
F.prototype.writeFloatBE = function(e, t, s) {
  return ed(this, e, t, !1, s);
};
function td(r, e, t, s, i) {
  return i || Zp(r, e, t, 8), Wp(r, e, t, s, 52, 8), t + 8;
}
F.prototype.writeDoubleLE = function(e, t, s) {
  return td(this, e, t, !0, s);
};
F.prototype.writeDoubleBE = function(e, t, s) {
  return td(this, e, t, !1, s);
};
F.prototype.copy = function(e, t, s, i) {
  if (s || (s = 0), !i && i !== 0 && (i = this.length), t >= e.length && (t = e.length), t || (t = 0), i > 0 && i < s && (i = s), i === s || e.length === 0 || this.length === 0)
    return 0;
  if (t < 0)
    throw new RangeError("targetStart out of bounds");
  if (s < 0 || s >= this.length)
    throw new RangeError("sourceStart out of bounds");
  if (i < 0)
    throw new RangeError("sourceEnd out of bounds");
  i > this.length && (i = this.length), e.length - t < i - s && (i = e.length - t + s);
  var n = i - s, a;
  if (this === e && s < t && t < i)
    for (a = n - 1; a >= 0; --a)
      e[a + t] = this[a + s];
  else if (n < 1e3 || !F.TYPED_ARRAY_SUPPORT)
    for (a = 0; a < n; ++a)
      e[a + t] = this[a + s];
  else
    Uint8Array.prototype.set.call(
      e,
      this.subarray(s, s + n),
      t
    );
  return n;
};
F.prototype.fill = function(e, t, s, i) {
  if (typeof e == "string") {
    if (typeof t == "string" ? (i = t, t = 0, s = this.length) : typeof s == "string" && (i = s, s = this.length), e.length === 1) {
      var n = e.charCodeAt(0);
      n < 256 && (e = n);
    }
    if (i !== void 0 && typeof i != "string")
      throw new TypeError("encoding must be a string");
    if (typeof i == "string" && !F.isEncoding(i))
      throw new TypeError("Unknown encoding: " + i);
  } else
    typeof e == "number" && (e = e & 255);
  if (t < 0 || this.length < t || this.length < s)
    throw new RangeError("Out of range index");
  if (s <= t)
    return this;
  t = t >>> 0, s = s === void 0 ? this.length : s >>> 0, e || (e = 0);
  var a;
  if (typeof e == "number")
    for (a = t; a < s; ++a)
      this[a] = e;
  else {
    var o = Vt(e) ? e : $n(new F(e, i).toString()), l = o.length;
    for (a = 0; a < s - t; ++a)
      this[a + t] = o[a % l];
  }
  return this;
};
var ux = /[^+\/0-9A-Za-z-_]/g;
function cx(r) {
  if (r = hx(r).replace(ux, ""), r.length < 2)
    return "";
  for (; r.length % 4 !== 0; )
    r = r + "=";
  return r;
}
function hx(r) {
  return r.trim ? r.trim() : r.replace(/^\s+|\s+$/g, "");
}
function fx(r) {
  return r < 16 ? "0" + r.toString(16) : r.toString(16);
}
function $n(r, e) {
  e = e || 1 / 0;
  for (var t, s = r.length, i = null, n = [], a = 0; a < s; ++a) {
    if (t = r.charCodeAt(a), t > 55295 && t < 57344) {
      if (!i) {
        if (t > 56319) {
          (e -= 3) > -1 && n.push(239, 191, 189);
          continue;
        } else if (a + 1 === s) {
          (e -= 3) > -1 && n.push(239, 191, 189);
          continue;
        }
        i = t;
        continue;
      }
      if (t < 56320) {
        (e -= 3) > -1 && n.push(239, 191, 189), i = t;
        continue;
      }
      t = (i - 55296 << 10 | t - 56320) + 65536;
    } else
      i && (e -= 3) > -1 && n.push(239, 191, 189);
    if (i = null, t < 128) {
      if ((e -= 1) < 0)
        break;
      n.push(t);
    } else if (t < 2048) {
      if ((e -= 2) < 0)
        break;
      n.push(
        t >> 6 | 192,
        t & 63 | 128
      );
    } else if (t < 65536) {
      if ((e -= 3) < 0)
        break;
      n.push(
        t >> 12 | 224,
        t >> 6 & 63 | 128,
        t & 63 | 128
      );
    } else if (t < 1114112) {
      if ((e -= 4) < 0)
        break;
      n.push(
        t >> 18 | 240,
        t >> 12 & 63 | 128,
        t >> 6 & 63 | 128,
        t & 63 | 128
      );
    } else
      throw new Error("Invalid code point");
  }
  return n;
}
function px(r) {
  for (var e = [], t = 0; t < r.length; ++t)
    e.push(r.charCodeAt(t) & 255);
  return e;
}
function dx(r, e) {
  for (var t, s, i, n = [], a = 0; a < r.length && !((e -= 2) < 0); ++a)
    t = r.charCodeAt(a), s = t >> 8, i = t % 256, n.push(i), n.push(s);
  return n;
}
function rd(r) {
  return qb(cx(r));
}
function ga(r, e, t, s) {
  for (var i = 0; i < s && !(i + t >= e.length || i >= r.length); ++i)
    e[i + t] = r[i];
  return i;
}
function mx(r) {
  return r !== r;
}
function yx(r) {
  return r != null && (!!r._isBuffer || sd(r) || gx(r));
}
function sd(r) {
  return !!r.constructor && typeof r.constructor.isBuffer == "function" && r.constructor.isBuffer(r);
}
function gx(r) {
  return typeof r.readFloatLE == "function" && typeof r.slice == "function" && sd(r.slice(0, 0));
}
function id(r, e) {
  for (var t = 0, s = r.length - 1; s >= 0; s--) {
    var i = r[s];
    i === "." ? r.splice(s, 1) : i === ".." ? (r.splice(s, 1), t++) : t && (r.splice(s, 1), t--);
  }
  if (e)
    for (; t--; t)
      r.unshift("..");
  return r;
}
var vx = /^(\/?|)([\s\S]*?)((?:\.{1,2}|[^\/]+?|)(\.[^.\/]*|))(?:[\/]*)$/, uu = function(r) {
  return vx.exec(r).slice(1);
};
function jn() {
  for (var r = "", e = !1, t = arguments.length - 1; t >= -1 && !e; t--) {
    var s = t >= 0 ? arguments[t] : "/";
    if (typeof s != "string")
      throw new TypeError("Arguments to path.resolve must be strings");
    if (!s)
      continue;
    r = s + "/" + r, e = s.charAt(0) === "/";
  }
  return r = id(fu(r.split("/"), function(i) {
    return !!i;
  }), !e).join("/"), (e ? "/" : "") + r || ".";
}
function cu(r) {
  var e = hu(r), t = xx(r, -1) === "/";
  return r = id(fu(r.split("/"), function(s) {
    return !!s;
  }), !e).join("/"), !r && !e && (r = "."), r && t && (r += "/"), (e ? "/" : "") + r;
}
function hu(r) {
  return r.charAt(0) === "/";
}
function nd() {
  var r = Array.prototype.slice.call(arguments, 0);
  return cu(fu(r, function(e, t) {
    if (typeof e != "string")
      throw new TypeError("Arguments to path.join must be strings");
    return e;
  }).join("/"));
}
function ad(r, e) {
  r = jn(r).substr(1), e = jn(e).substr(1);
  function t(u) {
    for (var c = 0; c < u.length && u[c] === ""; c++)
      ;
    for (var h = u.length - 1; h >= 0 && u[h] === ""; h--)
      ;
    return c > h ? [] : u.slice(c, h - c + 1);
  }
  for (var s = t(r.split("/")), i = t(e.split("/")), n = Math.min(s.length, i.length), a = n, o = 0; o < n; o++)
    if (s[o] !== i[o]) {
      a = o;
      break;
    }
  for (var l = [], o = a; o < s.length; o++)
    l.push("..");
  return l = l.concat(i.slice(a)), l.join("/");
}
var od = "/", ld = ":";
function ud(r) {
  var e = uu(r), t = e[0], s = e[1];
  return !t && !s ? "." : (s && (s = s.substr(0, s.length - 1)), t + s);
}
function cd(r, e) {
  var t = uu(r)[2];
  return e && t.substr(-1 * e.length) === e && (t = t.substr(0, t.length - e.length)), t;
}
function hd(r) {
  return uu(r)[3];
}
var bx = {
  extname: hd,
  basename: cd,
  dirname: ud,
  sep: od,
  delimiter: ld,
  relative: ad,
  join: nd,
  isAbsolute: hu,
  normalize: cu,
  resolve: jn
};
function fu(r, e) {
  if (r.filter)
    return r.filter(e);
  for (var t = [], s = 0; s < r.length; s++)
    e(r[s], s, r) && t.push(r[s]);
  return t;
}
var xx = "ab".substr(-1) === "b" ? function(r, e, t) {
  return r.substr(e, t);
} : function(r, e, t) {
  return e < 0 && (e = r.length + e), r.substr(e, t);
}, Sx = /* @__PURE__ */ Object.freeze({
  __proto__: null,
  resolve: jn,
  normalize: cu,
  isAbsolute: hu,
  join: nd,
  relative: ad,
  sep: od,
  delimiter: ld,
  dirname: ud,
  basename: cd,
  extname: hd,
  default: bx
});
/*! https://mths.be/punycode v1.4.1 by @mathias */
var Ga = 2147483647, ai = 36, fd = 1, qo = 26, wx = 38, Px = 700, Ex = 72, Tx = 128, Ax = "-", _x = /[^\x20-\x7E]/, Cx = /[\x2E\u3002\uFF0E\uFF61]/g, Ix = {
  overflow: "Overflow: input needs wider integers to process",
  "not-basic": "Illegal input >= 0x80 (not a basic code point)",
  "invalid-input": "Invalid input"
}, Ya = ai - fd, ls = Math.floor, Ja = String.fromCharCode;
function Zc(r) {
  throw new RangeError(Ix[r]);
}
function Nx(r, e) {
  for (var t = r.length, s = []; t--; )
    s[t] = e(r[t]);
  return s;
}
function Ox(r, e) {
  var t = r.split("@"), s = "";
  t.length > 1 && (s = t[0] + "@", r = t[1]), r = r.replace(Cx, ".");
  var i = r.split("."), n = Nx(i, e).join(".");
  return s + n;
}
function kx(r) {
  for (var e = [], t = 0, s = r.length, i, n; t < s; )
    i = r.charCodeAt(t++), i >= 55296 && i <= 56319 && t < s ? (n = r.charCodeAt(t++), (n & 64512) == 56320 ? e.push(((i & 1023) << 10) + (n & 1023) + 65536) : (e.push(i), t--)) : e.push(i);
  return e;
}
function eh(r, e) {
  return r + 22 + 75 * (r < 26) - ((e != 0) << 5);
}
function Mx(r, e, t) {
  var s = 0;
  for (r = t ? ls(r / Px) : r >> 1, r += ls(r / e); r > Ya * qo >> 1; s += ai)
    r = ls(r / Ya);
  return ls(s + (Ya + 1) * r / (r + wx));
}
function Lx(r) {
  var e, t, s, i, n, a, o, l, u, c, h, f = [], p, x, d, m;
  for (r = kx(r), p = r.length, e = Tx, t = 0, n = Ex, a = 0; a < p; ++a)
    h = r[a], h < 128 && f.push(Ja(h));
  for (s = i = f.length, i && f.push(Ax); s < p; ) {
    for (o = Ga, a = 0; a < p; ++a)
      h = r[a], h >= e && h < o && (o = h);
    for (x = s + 1, o - e > ls((Ga - t) / x) && Zc("overflow"), t += (o - e) * x, e = o, a = 0; a < p; ++a)
      if (h = r[a], h < e && ++t > Ga && Zc("overflow"), h == e) {
        for (l = t, u = ai; c = u <= n ? fd : u >= n + qo ? qo : u - n, !(l < c); u += ai)
          m = l - c, d = ai - c, f.push(
            Ja(eh(c + m % d, 0))
          ), l = ls(m / d);
        f.push(Ja(eh(l, 0))), n = Mx(t, x, s == i), t = 0, ++s;
      }
    ++t, ++e;
  }
  return f.join("");
}
function Dx(r) {
  return Ox(r, function(e) {
    return _x.test(e) ? "xn--" + Lx(e) : e;
  });
}
function pd() {
  throw new Error("setTimeout has not been defined");
}
function dd() {
  throw new Error("clearTimeout has not been defined");
}
var yr = pd, gr = dd;
typeof Ar.setTimeout == "function" && (yr = setTimeout);
typeof Ar.clearTimeout == "function" && (gr = clearTimeout);
function md(r) {
  if (yr === setTimeout)
    return setTimeout(r, 0);
  if ((yr === pd || !yr) && setTimeout)
    return yr = setTimeout, setTimeout(r, 0);
  try {
    return yr(r, 0);
  } catch (e) {
    try {
      return yr.call(null, r, 0);
    } catch (t) {
      return yr.call(this, r, 0);
    }
  }
}
function Rx(r) {
  if (gr === clearTimeout)
    return clearTimeout(r);
  if ((gr === dd || !gr) && clearTimeout)
    return gr = clearTimeout, clearTimeout(r);
  try {
    return gr(r);
  } catch (e) {
    try {
      return gr.call(null, r);
    } catch (t) {
      return gr.call(this, r);
    }
  }
}
var Qt = [], ys = !1, zr, dn = -1;
function Fx() {
  !ys || !zr || (ys = !1, zr.length ? Qt = zr.concat(Qt) : dn = -1, Qt.length && yd());
}
function yd() {
  if (!ys) {
    var r = md(Fx);
    ys = !0;
    for (var e = Qt.length; e; ) {
      for (zr = Qt, Qt = []; ++dn < e; )
        zr && zr[dn].run();
      dn = -1, e = Qt.length;
    }
    zr = null, ys = !1, Rx(r);
  }
}
function Bx(r) {
  var e = new Array(arguments.length - 1);
  if (arguments.length > 1)
    for (var t = 1; t < arguments.length; t++)
      e[t - 1] = arguments[t];
  Qt.push(new gd(r, e)), Qt.length === 1 && !ys && md(yd);
}
function gd(r, e) {
  this.fun = r, this.array = e;
}
gd.prototype.run = function() {
  this.fun.apply(null, this.array);
};
var Ux = "browser", $x = "browser", jx = !0, qx = {}, Vx = [], zx = "", Wx = {}, Hx = {}, Kx = {};
function Yr() {
}
var Gx = Yr, Yx = Yr, Jx = Yr, Qx = Yr, Xx = Yr, Zx = Yr, eS = Yr;
function tS(r) {
  throw new Error("process.binding is not supported");
}
function rS() {
  return "/";
}
function sS(r) {
  throw new Error("process.chdir is not supported");
}
function iS() {
  return 0;
}
var ts = Ar.performance || {}, nS = ts.now || ts.mozNow || ts.msNow || ts.oNow || ts.webkitNow || function() {
  return new Date().getTime();
};
function aS(r) {
  var e = nS.call(ts) * 1e-3, t = Math.floor(e), s = Math.floor(e % 1 * 1e9);
  return r && (t = t - r[0], s = s - r[1], s < 0 && (t--, s += 1e9)), [t, s];
}
var oS = new Date();
function lS() {
  var r = new Date(), e = r - oS;
  return e / 1e3;
}
var gs = {
  nextTick: Bx,
  title: Ux,
  browser: jx,
  env: qx,
  argv: Vx,
  version: zx,
  versions: Wx,
  on: Gx,
  addListener: Yx,
  once: Jx,
  off: Qx,
  removeListener: Xx,
  removeAllListeners: Zx,
  emit: eS,
  binding: tS,
  cwd: rS,
  chdir: sS,
  umask: iS,
  hrtime: aS,
  platform: $x,
  release: Hx,
  config: Kx,
  uptime: lS
}, Vo;
typeof Object.create == "function" ? Vo = function(e, t) {
  e.super_ = t, e.prototype = Object.create(t.prototype, {
    constructor: {
      value: e,
      enumerable: !1,
      writable: !0,
      configurable: !0
    }
  });
} : Vo = function(e, t) {
  e.super_ = t;
  var s = function() {
  };
  s.prototype = t.prototype, e.prototype = new s(), e.prototype.constructor = e;
};
var vd = Vo, uS = /%[sdj%]/g;
function va(r) {
  if (!Cr(r)) {
    for (var e = [], t = 0; t < arguments.length; t++)
      e.push(jt(arguments[t]));
    return e.join(" ");
  }
  for (var t = 1, s = arguments, i = s.length, n = String(r).replace(uS, function(o) {
    if (o === "%%")
      return "%";
    if (t >= i)
      return o;
    switch (o) {
      case "%s":
        return String(s[t++]);
      case "%d":
        return Number(s[t++]);
      case "%j":
        try {
          return JSON.stringify(s[t++]);
        } catch (l) {
          return "[Circular]";
        }
      default:
        return o;
    }
  }), a = s[t]; t < i; a = s[++t])
    Xt(a) || !lr(a) ? n += " " + a : n += " " + jt(a);
  return n;
}
function pu(r, e) {
  if ($t(Ar.process))
    return function() {
      return pu(r, e).apply(this, arguments);
    };
  if (gs.noDeprecation === !0)
    return r;
  var t = !1;
  function s() {
    if (!t) {
      if (gs.throwDeprecation)
        throw new Error(e);
      gs.traceDeprecation ? console.trace(e) : console.error(e), t = !0;
    }
    return r.apply(this, arguments);
  }
  return s;
}
var Gi = {}, Qa;
function bd(r) {
  if ($t(Qa) && (Qa = gs.env.NODE_DEBUG || ""), r = r.toUpperCase(), !Gi[r])
    if (new RegExp("\\b" + r + "\\b", "i").test(Qa)) {
      var e = 0;
      Gi[r] = function() {
        var t = va.apply(null, arguments);
        console.error("%s %d: %s", r, e, t);
      };
    } else
      Gi[r] = function() {
      };
  return Gi[r];
}
function jt(r, e) {
  var t = {
    seen: [],
    stylize: hS
  };
  return arguments.length >= 3 && (t.depth = arguments[2]), arguments.length >= 4 && (t.colors = arguments[3]), ba(e) ? t.showHidden = e : e && vu(t, e), $t(t.showHidden) && (t.showHidden = !1), $t(t.depth) && (t.depth = 2), $t(t.colors) && (t.colors = !1), $t(t.customInspect) && (t.customInspect = !0), t.colors && (t.stylize = cS), qn(t, r, t.depth);
}
jt.colors = {
  bold: [1, 22],
  italic: [3, 23],
  underline: [4, 24],
  inverse: [7, 27],
  white: [37, 39],
  grey: [90, 39],
  black: [30, 39],
  blue: [34, 39],
  cyan: [36, 39],
  green: [32, 39],
  magenta: [35, 39],
  red: [31, 39],
  yellow: [33, 39]
};
jt.styles = {
  special: "cyan",
  number: "yellow",
  boolean: "yellow",
  undefined: "grey",
  null: "bold",
  string: "green",
  date: "magenta",
  regexp: "red"
};
function cS(r, e) {
  var t = jt.styles[e];
  return t ? "\x1B[" + jt.colors[t][0] + "m" + r + "\x1B[" + jt.colors[t][1] + "m" : r;
}
function hS(r, e) {
  return r;
}
function fS(r) {
  var e = {};
  return r.forEach(function(t, s) {
    e[t] = !0;
  }), e;
}
function qn(r, e, t) {
  if (r.customInspect && e && ui(e.inspect) && e.inspect !== jt && !(e.constructor && e.constructor.prototype === e)) {
    var s = e.inspect(t, r);
    return Cr(s) || (s = qn(r, s, t)), s;
  }
  var i = pS(r, e);
  if (i)
    return i;
  var n = Object.keys(e), a = fS(n);
  if (r.showHidden && (n = Object.getOwnPropertyNames(e)), li(e) && (n.indexOf("message") >= 0 || n.indexOf("description") >= 0))
    return Xa(e);
  if (n.length === 0) {
    if (ui(e)) {
      var o = e.name ? ": " + e.name : "";
      return r.stylize("[Function" + o + "]", "special");
    }
    if (oi(e))
      return r.stylize(RegExp.prototype.toString.call(e), "regexp");
    if (Vn(e))
      return r.stylize(Date.prototype.toString.call(e), "date");
    if (li(e))
      return Xa(e);
  }
  var l = "", u = !1, c = ["{", "}"];
  if (du(e) && (u = !0, c = ["[", "]"]), ui(e)) {
    var h = e.name ? ": " + e.name : "";
    l = " [Function" + h + "]";
  }
  if (oi(e) && (l = " " + RegExp.prototype.toString.call(e)), Vn(e) && (l = " " + Date.prototype.toUTCString.call(e)), li(e) && (l = " " + Xa(e)), n.length === 0 && (!u || e.length == 0))
    return c[0] + l + c[1];
  if (t < 0)
    return oi(e) ? r.stylize(RegExp.prototype.toString.call(e), "regexp") : r.stylize("[Object]", "special");
  r.seen.push(e);
  var f;
  return u ? f = dS(r, e, t, a, n) : f = n.map(function(p) {
    return zo(r, e, t, a, p, u);
  }), r.seen.pop(), mS(f, l, c);
}
function pS(r, e) {
  if ($t(e))
    return r.stylize("undefined", "undefined");
  if (Cr(e)) {
    var t = "'" + JSON.stringify(e).replace(/^"|"$/g, "").replace(/'/g, "\\'").replace(/\\"/g, '"') + "'";
    return r.stylize(t, "string");
  }
  if (yu(e))
    return r.stylize("" + e, "number");
  if (ba(e))
    return r.stylize("" + e, "boolean");
  if (Xt(e))
    return r.stylize("null", "null");
}
function Xa(r) {
  return "[" + Error.prototype.toString.call(r) + "]";
}
function dS(r, e, t, s, i) {
  for (var n = [], a = 0, o = e.length; a < o; ++a)
    Ed(e, String(a)) ? n.push(zo(
      r,
      e,
      t,
      s,
      String(a),
      !0
    )) : n.push("");
  return i.forEach(function(l) {
    l.match(/^\d+$/) || n.push(zo(
      r,
      e,
      t,
      s,
      l,
      !0
    ));
  }), n;
}
function zo(r, e, t, s, i, n) {
  var a, o, l;
  if (l = Object.getOwnPropertyDescriptor(e, i) || { value: e[i] }, l.get ? l.set ? o = r.stylize("[Getter/Setter]", "special") : o = r.stylize("[Getter]", "special") : l.set && (o = r.stylize("[Setter]", "special")), Ed(s, i) || (a = "[" + i + "]"), o || (r.seen.indexOf(l.value) < 0 ? (Xt(t) ? o = qn(r, l.value, null) : o = qn(r, l.value, t - 1), o.indexOf(`
`) > -1 && (n ? o = o.split(`
`).map(function(u) {
    return "  " + u;
  }).join(`
`).substr(2) : o = `
` + o.split(`
`).map(function(u) {
    return "   " + u;
  }).join(`
`))) : o = r.stylize("[Circular]", "special")), $t(a)) {
    if (n && i.match(/^\d+$/))
      return o;
    a = JSON.stringify("" + i), a.match(/^"([a-zA-Z_][a-zA-Z_0-9]*)"$/) ? (a = a.substr(1, a.length - 2), a = r.stylize(a, "name")) : (a = a.replace(/'/g, "\\'").replace(/\\"/g, '"').replace(/(^"|"$)/g, "'"), a = r.stylize(a, "string"));
  }
  return a + ": " + o;
}
function mS(r, e, t) {
  var s = r.reduce(function(i, n) {
    return n.indexOf(`
`) >= 0, i + n.replace(/\u001b\[\d\d?m/g, "").length + 1;
  }, 0);
  return s > 60 ? t[0] + (e === "" ? "" : e + `
 `) + " " + r.join(`,
  `) + " " + t[1] : t[0] + e + " " + r.join(", ") + " " + t[1];
}
function du(r) {
  return Array.isArray(r);
}
function ba(r) {
  return typeof r == "boolean";
}
function Xt(r) {
  return r === null;
}
function mu(r) {
  return r == null;
}
function yu(r) {
  return typeof r == "number";
}
function Cr(r) {
  return typeof r == "string";
}
function xd(r) {
  return typeof r == "symbol";
}
function $t(r) {
  return r === void 0;
}
function oi(r) {
  return lr(r) && gu(r) === "[object RegExp]";
}
function lr(r) {
  return typeof r == "object" && r !== null;
}
function Vn(r) {
  return lr(r) && gu(r) === "[object Date]";
}
function li(r) {
  return lr(r) && (gu(r) === "[object Error]" || r instanceof Error);
}
function ui(r) {
  return typeof r == "function";
}
function Sd(r) {
  return r === null || typeof r == "boolean" || typeof r == "number" || typeof r == "string" || typeof r == "symbol" || typeof r == "undefined";
}
function wd(r) {
  return F.isBuffer(r);
}
function gu(r) {
  return Object.prototype.toString.call(r);
}
function Za(r) {
  return r < 10 ? "0" + r.toString(10) : r.toString(10);
}
var yS = [
  "Jan",
  "Feb",
  "Mar",
  "Apr",
  "May",
  "Jun",
  "Jul",
  "Aug",
  "Sep",
  "Oct",
  "Nov",
  "Dec"
];
function gS() {
  var r = new Date(), e = [
    Za(r.getHours()),
    Za(r.getMinutes()),
    Za(r.getSeconds())
  ].join(":");
  return [r.getDate(), yS[r.getMonth()], e].join(" ");
}
function Pd() {
  console.log("%s - %s", gS(), va.apply(null, arguments));
}
function vu(r, e) {
  if (!e || !lr(e))
    return r;
  for (var t = Object.keys(e), s = t.length; s--; )
    r[t[s]] = e[t[s]];
  return r;
}
function Ed(r, e) {
  return Object.prototype.hasOwnProperty.call(r, e);
}
var vS = {
  inherits: vd,
  _extend: vu,
  log: Pd,
  isBuffer: wd,
  isPrimitive: Sd,
  isFunction: ui,
  isError: li,
  isDate: Vn,
  isObject: lr,
  isRegExp: oi,
  isUndefined: $t,
  isSymbol: xd,
  isString: Cr,
  isNumber: yu,
  isNullOrUndefined: mu,
  isNull: Xt,
  isBoolean: ba,
  isArray: du,
  inspect: jt,
  deprecate: pu,
  format: va,
  debuglog: bd
}, bS = /* @__PURE__ */ Object.freeze({
  __proto__: null,
  format: va,
  deprecate: pu,
  debuglog: bd,
  inspect: jt,
  isArray: du,
  isBoolean: ba,
  isNull: Xt,
  isNullOrUndefined: mu,
  isNumber: yu,
  isString: Cr,
  isSymbol: xd,
  isUndefined: $t,
  isRegExp: oi,
  isObject: lr,
  isDate: Vn,
  isError: li,
  isFunction: ui,
  isPrimitive: Sd,
  isBuffer: wd,
  log: Pd,
  inherits: vd,
  _extend: vu,
  default: vS
});
function xS(r, e) {
  return Object.prototype.hasOwnProperty.call(r, e);
}
var Td = Array.isArray || function(r) {
  return Object.prototype.toString.call(r) === "[object Array]";
};
function Vs(r) {
  switch (typeof r) {
    case "string":
      return r;
    case "boolean":
      return r ? "true" : "false";
    case "number":
      return isFinite(r) ? r : "";
    default:
      return "";
  }
}
function SS(r, e, t, s) {
  return e = e || "&", t = t || "=", r === null && (r = void 0), typeof r == "object" ? th(wS(r), function(i) {
    var n = encodeURIComponent(Vs(i)) + t;
    return Td(r[i]) ? th(r[i], function(a) {
      return n + encodeURIComponent(Vs(a));
    }).join(e) : n + encodeURIComponent(Vs(r[i]));
  }).join(e) : s ? encodeURIComponent(Vs(s)) + t + encodeURIComponent(Vs(r)) : "";
}
function th(r, e) {
  if (r.map)
    return r.map(e);
  for (var t = [], s = 0; s < r.length; s++)
    t.push(e(r[s], s));
  return t;
}
var wS = Object.keys || function(r) {
  var e = [];
  for (var t in r)
    Object.prototype.hasOwnProperty.call(r, t) && e.push(t);
  return e;
};
function rh(r, e, t, s) {
  e = e || "&", t = t || "=";
  var i = {};
  if (typeof r != "string" || r.length === 0)
    return i;
  var n = /\+/g;
  r = r.split(e);
  var a = 1e3;
  s && typeof s.maxKeys == "number" && (a = s.maxKeys);
  var o = r.length;
  a > 0 && o > a && (o = a);
  for (var l = 0; l < o; ++l) {
    var u = r[l].replace(n, "%20"), c = u.indexOf(t), h, f, p, x;
    c >= 0 ? (h = u.substr(0, c), f = u.substr(c + 1)) : (h = u, f = ""), p = decodeURIComponent(h), x = decodeURIComponent(f), xS(i, p) ? Td(i[p]) ? i[p].push(x) : i[p] = [i[p], x] : i[p] = x;
  }
  return i;
}
var PS = {
  parse: Ri,
  resolve: Cd,
  resolveObject: Id,
  format: _d,
  Url: Nt
};
function Nt() {
  this.protocol = null, this.slashes = null, this.auth = null, this.host = null, this.port = null, this.hostname = null, this.hash = null, this.search = null, this.query = null, this.pathname = null, this.path = null, this.href = null;
}
var ES = /^([a-z0-9.+-]+:)/i, TS = /:[0-9]*$/, AS = /^(\/\/?(?!\/)[^\?\s]*)(\?[^\s]*)?$/, _S = ["<", ">", '"', "`", " ", "\r", `
`, "	"], CS = ["{", "}", "|", "\\", "^", "`"].concat(_S), Wo = ["'"].concat(CS), sh = ["%", "/", "?", ";", "#"].concat(Wo), ih = ["/", "?", "#"], IS = 255, nh = /^[+a-z0-9A-Z_-]{0,63}$/, NS = /^([+a-z0-9A-Z_-]{0,63})(.*)$/, OS = {
  javascript: !0,
  "javascript:": !0
}, Ho = {
  javascript: !0,
  "javascript:": !0
}, vs = {
  http: !0,
  https: !0,
  ftp: !0,
  gopher: !0,
  file: !0,
  "http:": !0,
  "https:": !0,
  "ftp:": !0,
  "gopher:": !0,
  "file:": !0
};
function Ri(r, e, t) {
  if (r && lr(r) && r instanceof Nt)
    return r;
  var s = new Nt();
  return s.parse(r, e, t), s;
}
Nt.prototype.parse = function(r, e, t) {
  return Ad(this, r, e, t);
};
function Ad(r, e, t, s) {
  if (!Cr(e))
    throw new TypeError("Parameter 'url' must be a string, not " + typeof e);
  var i = e.indexOf("?"), n = i !== -1 && i < e.indexOf("#") ? "?" : "#", a = e.split(n), o = /\\/g;
  a[0] = a[0].replace(o, "/"), e = a.join(n);
  var l = e;
  if (l = l.trim(), !s && e.split("#").length === 1) {
    var u = AS.exec(l);
    if (u)
      return r.path = l, r.href = l, r.pathname = u[1], u[2] ? (r.search = u[2], t ? r.query = rh(r.search.substr(1)) : r.query = r.search.substr(1)) : t && (r.search = "", r.query = {}), r;
  }
  var c = ES.exec(l);
  if (c) {
    c = c[0];
    var h = c.toLowerCase();
    r.protocol = h, l = l.substr(c.length);
  }
  if (s || c || l.match(/^\/\/[^@\/]+@[^@\/]+/)) {
    var f = l.substr(0, 2) === "//";
    f && !(c && Ho[c]) && (l = l.substr(2), r.slashes = !0);
  }
  var p, x, d, m;
  if (!Ho[c] && (f || c && !vs[c])) {
    var y = -1;
    for (p = 0; p < ih.length; p++)
      x = l.indexOf(ih[p]), x !== -1 && (y === -1 || x < y) && (y = x);
    var _, T;
    for (y === -1 ? T = l.lastIndexOf("@") : T = l.lastIndexOf("@", y), T !== -1 && (_ = l.slice(0, T), l = l.slice(T + 1), r.auth = decodeURIComponent(_)), y = -1, p = 0; p < sh.length; p++)
      x = l.indexOf(sh[p]), x !== -1 && (y === -1 || x < y) && (y = x);
    y === -1 && (y = l.length), r.host = l.slice(0, y), l = l.slice(y), Nd(r), r.hostname = r.hostname || "";
    var C = r.hostname[0] === "[" && r.hostname[r.hostname.length - 1] === "]";
    if (!C) {
      var v = r.hostname.split(/\./);
      for (p = 0, d = v.length; p < d; p++) {
        var w = v[p];
        if (!!w && !w.match(nh)) {
          for (var N = "", P = 0, g = w.length; P < g; P++)
            w.charCodeAt(P) > 127 ? N += "x" : N += w[P];
          if (!N.match(nh)) {
            var E = v.slice(0, p), O = v.slice(p + 1), S = w.match(NS);
            S && (E.push(S[1]), O.unshift(S[2])), O.length && (l = "/" + O.join(".") + l), r.hostname = E.join(".");
            break;
          }
        }
      }
    }
    r.hostname.length > IS ? r.hostname = "" : r.hostname = r.hostname.toLowerCase(), C || (r.hostname = Dx(r.hostname)), m = r.port ? ":" + r.port : "";
    var W = r.hostname || "";
    r.host = W + m, r.href += r.host, C && (r.hostname = r.hostname.substr(1, r.hostname.length - 2), l[0] !== "/" && (l = "/" + l));
  }
  if (!OS[h])
    for (p = 0, d = Wo.length; p < d; p++) {
      var Q = Wo[p];
      if (l.indexOf(Q) !== -1) {
        var xe = encodeURIComponent(Q);
        xe === Q && (xe = escape(Q)), l = l.split(Q).join(xe);
      }
    }
  var re = l.indexOf("#");
  re !== -1 && (r.hash = l.substr(re), l = l.slice(0, re));
  var J = l.indexOf("?");
  if (J !== -1 ? (r.search = l.substr(J), r.query = l.substr(J + 1), t && (r.query = rh(r.query)), l = l.slice(0, J)) : t && (r.search = "", r.query = {}), l && (r.pathname = l), vs[h] && r.hostname && !r.pathname && (r.pathname = "/"), r.pathname || r.search) {
    m = r.pathname || "";
    var ce = r.search || "";
    r.path = m + ce;
  }
  return r.href = bu(r), r;
}
function _d(r) {
  return Cr(r) && (r = Ad({}, r)), bu(r);
}
function bu(r) {
  var e = r.auth || "";
  e && (e = encodeURIComponent(e), e = e.replace(/%3A/i, ":"), e += "@");
  var t = r.protocol || "", s = r.pathname || "", i = r.hash || "", n = !1, a = "";
  r.host ? n = e + r.host : r.hostname && (n = e + (r.hostname.indexOf(":") === -1 ? r.hostname : "[" + this.hostname + "]"), r.port && (n += ":" + r.port)), r.query && lr(r.query) && Object.keys(r.query).length && (a = SS(r.query));
  var o = r.search || a && "?" + a || "";
  return t && t.substr(-1) !== ":" && (t += ":"), r.slashes || (!t || vs[t]) && n !== !1 ? (n = "//" + (n || ""), s && s.charAt(0) !== "/" && (s = "/" + s)) : n || (n = ""), i && i.charAt(0) !== "#" && (i = "#" + i), o && o.charAt(0) !== "?" && (o = "?" + o), s = s.replace(/[?#]/g, function(l) {
    return encodeURIComponent(l);
  }), o = o.replace("#", "%23"), t + n + s + o + i;
}
Nt.prototype.format = function() {
  return bu(this);
};
function Cd(r, e) {
  return Ri(r, !1, !0).resolve(e);
}
Nt.prototype.resolve = function(r) {
  return this.resolveObject(Ri(r, !1, !0)).format();
};
function Id(r, e) {
  return r ? Ri(r, !1, !0).resolveObject(e) : e;
}
Nt.prototype.resolveObject = function(r) {
  if (Cr(r)) {
    var e = new Nt();
    e.parse(r, !1, !0), r = e;
  }
  for (var t = new Nt(), s = Object.keys(this), i = 0; i < s.length; i++) {
    var n = s[i];
    t[n] = this[n];
  }
  if (t.hash = r.hash, r.href === "")
    return t.href = t.format(), t;
  if (r.slashes && !r.protocol) {
    for (var a = Object.keys(r), o = 0; o < a.length; o++) {
      var l = a[o];
      l !== "protocol" && (t[l] = r[l]);
    }
    return vs[t.protocol] && t.hostname && !t.pathname && (t.path = t.pathname = "/"), t.href = t.format(), t;
  }
  var u;
  if (r.protocol && r.protocol !== t.protocol) {
    if (!vs[r.protocol]) {
      for (var c = Object.keys(r), h = 0; h < c.length; h++) {
        var f = c[h];
        t[f] = r[f];
      }
      return t.href = t.format(), t;
    }
    if (t.protocol = r.protocol, !r.host && !Ho[r.protocol]) {
      for (u = (r.pathname || "").split("/"); u.length && !(r.host = u.shift()); )
        ;
      r.host || (r.host = ""), r.hostname || (r.hostname = ""), u[0] !== "" && u.unshift(""), u.length < 2 && u.unshift(""), t.pathname = u.join("/");
    } else
      t.pathname = r.pathname;
    if (t.search = r.search, t.query = r.query, t.host = r.host || "", t.auth = r.auth, t.hostname = r.hostname || r.host, t.port = r.port, t.pathname || t.search) {
      var p = t.pathname || "", x = t.search || "";
      t.path = p + x;
    }
    return t.slashes = t.slashes || r.slashes, t.href = t.format(), t;
  }
  var d = t.pathname && t.pathname.charAt(0) === "/", m = r.host || r.pathname && r.pathname.charAt(0) === "/", y = m || d || t.host && r.pathname, _ = y, T = t.pathname && t.pathname.split("/") || [], C = t.protocol && !vs[t.protocol];
  u = r.pathname && r.pathname.split("/") || [], C && (t.hostname = "", t.port = null, t.host && (T[0] === "" ? T[0] = t.host : T.unshift(t.host)), t.host = "", r.protocol && (r.hostname = null, r.port = null, r.host && (u[0] === "" ? u[0] = r.host : u.unshift(r.host)), r.host = null), y = y && (u[0] === "" || T[0] === ""));
  var v;
  if (m)
    t.host = r.host || r.host === "" ? r.host : t.host, t.hostname = r.hostname || r.hostname === "" ? r.hostname : t.hostname, t.search = r.search, t.query = r.query, T = u;
  else if (u.length)
    T || (T = []), T.pop(), T = T.concat(u), t.search = r.search, t.query = r.query;
  else if (!mu(r.search))
    return C && (t.hostname = t.host = T.shift(), v = t.host && t.host.indexOf("@") > 0 ? t.host.split("@") : !1, v && (t.auth = v.shift(), t.host = t.hostname = v.shift())), t.search = r.search, t.query = r.query, (!Xt(t.pathname) || !Xt(t.search)) && (t.path = (t.pathname ? t.pathname : "") + (t.search ? t.search : "")), t.href = t.format(), t;
  if (!T.length)
    return t.pathname = null, t.search ? t.path = "/" + t.search : t.path = null, t.href = t.format(), t;
  for (var w = T.slice(-1)[0], N = (t.host || r.host || T.length > 1) && (w === "." || w === "..") || w === "", P = 0, g = T.length; g >= 0; g--)
    w = T[g], w === "." ? T.splice(g, 1) : w === ".." ? (T.splice(g, 1), P++) : P && (T.splice(g, 1), P--);
  if (!y && !_)
    for (; P--; P)
      T.unshift("..");
  y && T[0] !== "" && (!T[0] || T[0].charAt(0) !== "/") && T.unshift(""), N && T.join("/").substr(-1) !== "/" && T.push("");
  var E = T[0] === "" || T[0] && T[0].charAt(0) === "/";
  return C && (t.hostname = t.host = E ? "" : T.length ? T.shift() : "", v = t.host && t.host.indexOf("@") > 0 ? t.host.split("@") : !1, v && (t.auth = v.shift(), t.host = t.hostname = v.shift())), y = y || t.host && T.length, y && !E && T.unshift(""), T.length ? t.pathname = T.join("/") : (t.pathname = null, t.path = null), (!Xt(t.pathname) || !Xt(t.search)) && (t.path = (t.pathname ? t.pathname : "") + (t.search ? t.search : "")), t.auth = r.auth || t.auth, t.slashes = t.slashes || r.slashes, t.href = t.format(), t;
};
Nt.prototype.parseHost = function() {
  return Nd(this);
};
function Nd(r) {
  var e = r.host, t = TS.exec(e);
  t && (t = t[0], t !== ":" && (r.port = t.substr(1)), e = e.substr(0, e.length - t.length)), e && (r.hostname = e);
}
var kS = /* @__PURE__ */ Object.freeze({
  __proto__: null,
  parse: Ri,
  resolve: Cd,
  resolveObject: Id,
  format: _d,
  default: PS,
  Url: Nt
});
const MS = Symbol("ssrInterpolate"), LS = Symbol("ssrRenderVNode"), DS = Symbol("ssrRenderComponent"), RS = Symbol("ssrRenderSlot"), FS = Symbol("ssrRenderSlotInner"), BS = Symbol("ssrRenderClass"), US = Symbol("ssrRenderStyle"), $S = Symbol("ssrRenderAttrs"), jS = Symbol("ssrRenderAttr"), qS = Symbol("ssrRenderDynamicAttr"), VS = Symbol("ssrRenderList"), zS = Symbol("ssrIncludeBooleanAttr"), WS = Symbol("ssrLooseEqual"), HS = Symbol("ssrLooseContain"), KS = Symbol("ssrRenderDynamicModel"), GS = Symbol("ssrGetDynamicModelProps"), YS = Symbol("ssrRenderTeleport"), JS = Symbol("ssrRenderSuspense"), QS = Symbol("ssrGetDirectiveProps"), XS = {
  [MS]: "ssrInterpolate",
  [LS]: "ssrRenderVNode",
  [DS]: "ssrRenderComponent",
  [RS]: "ssrRenderSlot",
  [FS]: "ssrRenderSlotInner",
  [BS]: "ssrRenderClass",
  [US]: "ssrRenderStyle",
  [$S]: "ssrRenderAttrs",
  [jS]: "ssrRenderAttr",
  [qS]: "ssrRenderDynamicAttr",
  [VS]: "ssrRenderList",
  [zS]: "ssrIncludeBooleanAttr",
  [WS]: "ssrLooseEqual",
  [HS]: "ssrLooseContain",
  [KS]: "ssrRenderDynamicModel",
  [GS]: "ssrGetDynamicModelProps",
  [YS]: "ssrRenderTeleport",
  [JS]: "ssrRenderSuspense",
  [QS]: "ssrGetDirectiveProps"
};
jf(XS);
const [ZS, ew] = Pb(!0);
[...ZS, ...Ub];
Object.assign(Object.assign({}, ew), $b);
var tw = {}, rw = /* @__PURE__ */ Object.freeze({
  __proto__: null,
  default: tw
}), sw = /* @__PURE__ */ Di(rw), xu = /* @__PURE__ */ Di(Sx), iw = /* @__PURE__ */ Di(bS);
or("once,memo,if,for,else,else-if,slot,text,html,on,bind,model,show,cloak,is");
function Od() {
  return !1;
}
function kd() {
  throw new Error("tty.ReadStream is not implemented");
}
function Md() {
  throw new Error("tty.ReadStream is not implemented");
}
var nw = {
  isatty: Od,
  ReadStream: kd,
  WriteStream: Md
}, aw = /* @__PURE__ */ Object.freeze({
  __proto__: null,
  isatty: Od,
  ReadStream: kd,
  WriteStream: Md,
  default: nw
}), ow = /* @__PURE__ */ Di(aw);
let lw = !("NO_COLOR" in {} || gs.argv.includes("--no-color")) && ("FORCE_COLOR" in {} || gs.argv.includes("--color") || !1 || ow.isatty(1) && {}.TERM !== "dumb" || "CI" in {}), De = (r, e, t = r) => (s) => {
  let i = "" + s, n = i.indexOf(e, r.length);
  return ~n ? r + Ld(i, e, t, n) + e : r + i + e;
}, Ld = (r, e, t, s) => {
  let i = r.substring(0, s) + t, n = r.substring(s + e.length), a = n.indexOf(e);
  return ~a ? i + Ld(n, e, t, a) : i + n;
}, Dd = (r = lw) => ({
  isColorSupported: r,
  reset: r ? (e) => `\x1B[0m${e}\x1B[0m` : String,
  bold: r ? De("\x1B[1m", "\x1B[22m", "\x1B[22m\x1B[1m") : String,
  dim: r ? De("\x1B[2m", "\x1B[22m", "\x1B[22m\x1B[2m") : String,
  italic: r ? De("\x1B[3m", "\x1B[23m") : String,
  underline: r ? De("\x1B[4m", "\x1B[24m") : String,
  inverse: r ? De("\x1B[7m", "\x1B[27m") : String,
  hidden: r ? De("\x1B[8m", "\x1B[28m") : String,
  strikethrough: r ? De("\x1B[9m", "\x1B[29m") : String,
  black: r ? De("\x1B[30m", "\x1B[39m") : String,
  red: r ? De("\x1B[31m", "\x1B[39m") : String,
  green: r ? De("\x1B[32m", "\x1B[39m") : String,
  yellow: r ? De("\x1B[33m", "\x1B[39m") : String,
  blue: r ? De("\x1B[34m", "\x1B[39m") : String,
  magenta: r ? De("\x1B[35m", "\x1B[39m") : String,
  cyan: r ? De("\x1B[36m", "\x1B[39m") : String,
  white: r ? De("\x1B[37m", "\x1B[39m") : String,
  gray: r ? De("\x1B[90m", "\x1B[39m") : String,
  bgBlack: r ? De("\x1B[40m", "\x1B[49m") : String,
  bgRed: r ? De("\x1B[41m", "\x1B[49m") : String,
  bgGreen: r ? De("\x1B[42m", "\x1B[49m") : String,
  bgYellow: r ? De("\x1B[43m", "\x1B[49m") : String,
  bgBlue: r ? De("\x1B[44m", "\x1B[49m") : String,
  bgMagenta: r ? De("\x1B[45m", "\x1B[49m") : String,
  bgCyan: r ? De("\x1B[46m", "\x1B[49m") : String,
  bgWhite: r ? De("\x1B[47m", "\x1B[49m") : String
});
var Ve = Dd(), uw = Dd;
Ve.createColors = uw;
const eo = "'".charCodeAt(0), ah = '"'.charCodeAt(0), Yi = "\\".charCodeAt(0), oh = "/".charCodeAt(0), Ji = `
`.charCodeAt(0), zs = " ".charCodeAt(0), Qi = "\f".charCodeAt(0), Xi = "	".charCodeAt(0), Zi = "\r".charCodeAt(0), cw = "[".charCodeAt(0), hw = "]".charCodeAt(0), fw = "(".charCodeAt(0), pw = ")".charCodeAt(0), dw = "{".charCodeAt(0), mw = "}".charCodeAt(0), yw = ";".charCodeAt(0), gw = "*".charCodeAt(0), vw = ":".charCodeAt(0), bw = "@".charCodeAt(0), en = /[\t\n\f\r "#'()/;[\\\]{}]/g, tn = /[\t\n\f\r !"#'():;@[\\\]{}]|\/(?=\*)/g, xw = /.[\n"'(/\\]/, lh = /[\da-f]/i;
var Rd = function(e, t = {}) {
  let s = e.css.valueOf(), i = t.ignoreErrors, n, a, o, l, u, c, h, f, p, x, d = s.length, m = 0, y = [], _ = [];
  function T() {
    return m;
  }
  function C(P) {
    throw e.error("Unclosed " + P, m);
  }
  function v() {
    return _.length === 0 && m >= d;
  }
  function w(P) {
    if (_.length)
      return _.pop();
    if (m >= d)
      return;
    let g = P ? P.ignoreUnclosed : !1;
    switch (n = s.charCodeAt(m), n) {
      case Ji:
      case zs:
      case Xi:
      case Zi:
      case Qi: {
        a = m;
        do
          a += 1, n = s.charCodeAt(a);
        while (n === zs || n === Ji || n === Xi || n === Zi || n === Qi);
        x = ["space", s.slice(m, a)], m = a - 1;
        break;
      }
      case cw:
      case hw:
      case dw:
      case mw:
      case vw:
      case yw:
      case pw: {
        let E = String.fromCharCode(n);
        x = [E, E, m];
        break;
      }
      case fw: {
        if (f = y.length ? y.pop()[1] : "", p = s.charCodeAt(m + 1), f === "url" && p !== eo && p !== ah && p !== zs && p !== Ji && p !== Xi && p !== Qi && p !== Zi) {
          a = m;
          do {
            if (c = !1, a = s.indexOf(")", a + 1), a === -1)
              if (i || g) {
                a = m;
                break;
              } else
                C("bracket");
            for (h = a; s.charCodeAt(h - 1) === Yi; )
              h -= 1, c = !c;
          } while (c);
          x = ["brackets", s.slice(m, a + 1), m, a], m = a;
        } else
          a = s.indexOf(")", m + 1), l = s.slice(m, a + 1), a === -1 || xw.test(l) ? x = ["(", "(", m] : (x = ["brackets", l, m, a], m = a);
        break;
      }
      case eo:
      case ah: {
        o = n === eo ? "'" : '"', a = m;
        do {
          if (c = !1, a = s.indexOf(o, a + 1), a === -1)
            if (i || g) {
              a = m + 1;
              break;
            } else
              C("string");
          for (h = a; s.charCodeAt(h - 1) === Yi; )
            h -= 1, c = !c;
        } while (c);
        x = ["string", s.slice(m, a + 1), m, a], m = a;
        break;
      }
      case bw: {
        en.lastIndex = m + 1, en.test(s), en.lastIndex === 0 ? a = s.length - 1 : a = en.lastIndex - 2, x = ["at-word", s.slice(m, a + 1), m, a], m = a;
        break;
      }
      case Yi: {
        for (a = m, u = !0; s.charCodeAt(a + 1) === Yi; )
          a += 1, u = !u;
        if (n = s.charCodeAt(a + 1), u && n !== oh && n !== zs && n !== Ji && n !== Xi && n !== Zi && n !== Qi && (a += 1, lh.test(s.charAt(a)))) {
          for (; lh.test(s.charAt(a + 1)); )
            a += 1;
          s.charCodeAt(a + 1) === zs && (a += 1);
        }
        x = ["word", s.slice(m, a + 1), m, a], m = a;
        break;
      }
      default: {
        n === oh && s.charCodeAt(m + 1) === gw ? (a = s.indexOf("*/", m + 2) + 1, a === 0 && (i || g ? a = s.length : C("comment")), x = ["comment", s.slice(m, a + 1), m, a], m = a) : (tn.lastIndex = m + 1, tn.test(s), tn.lastIndex === 0 ? a = s.length - 1 : a = tn.lastIndex - 2, x = ["word", s.slice(m, a + 1), m, a], y.push(x), m = a);
        break;
      }
    }
    return m++, x;
  }
  function N(P) {
    _.push(P);
  }
  return {
    back: N,
    nextToken: w,
    endOfFile: v,
    position: T
  };
};
let Fd;
function Sw(r) {
  Fd = r;
}
const ww = {
  brackets: Ve.cyan,
  "at-word": Ve.cyan,
  comment: Ve.gray,
  string: Ve.green,
  class: Ve.yellow,
  hash: Ve.magenta,
  call: Ve.cyan,
  "(": Ve.cyan,
  ")": Ve.cyan,
  "{": Ve.yellow,
  "}": Ve.yellow,
  "[": Ve.yellow,
  "]": Ve.yellow,
  ":": Ve.yellow,
  ";": Ve.yellow
};
function Pw([r, e], t) {
  if (r === "word") {
    if (e[0] === ".")
      return "class";
    if (e[0] === "#")
      return "hash";
  }
  if (!t.endOfFile()) {
    let s = t.nextToken();
    if (t.back(s), s[0] === "brackets" || s[0] === "(")
      return "call";
  }
  return r;
}
function Bd(r) {
  let e = Rd(new Fd(r), { ignoreErrors: !0 }), t = "";
  for (; !e.endOfFile(); ) {
    let s = e.nextToken(), i = ww[Pw(s, e)];
    i ? t += s[1].split(/\r?\n/).map((n) => i(n)).join(`
`) : t += s[1];
  }
  return t;
}
Bd.registerInput = Sw;
var ci = Bd;
class Ti extends Error {
  constructor(e, t, s, i, n, a) {
    super(e), this.name = "CssSyntaxError", this.reason = e, n && (this.file = n), i && (this.source = i), a && (this.plugin = a), typeof t != "undefined" && typeof s != "undefined" && (typeof t == "number" ? (this.line = t, this.column = s) : (this.line = t.line, this.column = t.column, this.endLine = s.line, this.endColumn = s.column)), this.setMessage(), Error.captureStackTrace && Error.captureStackTrace(this, Ti);
  }
  setMessage() {
    this.message = this.plugin ? this.plugin + ": " : "", this.message += this.file ? this.file : "<css input>", typeof this.line != "undefined" && (this.message += ":" + this.line + ":" + this.column), this.message += ": " + this.reason;
  }
  showSourceCode(e) {
    if (!this.source)
      return "";
    let t = this.source;
    e == null && (e = Ve.isColorSupported), ci && e && (t = ci(t));
    let s = t.split(/\r?\n/), i = Math.max(this.line - 3, 0), n = Math.min(this.line + 2, s.length), a = String(n).length, o, l;
    if (e) {
      let { bold: u, red: c, gray: h } = Ve.createColors(!0);
      o = (f) => u(c(f)), l = (f) => h(f);
    } else
      o = l = (u) => u;
    return s.slice(i, n).map((u, c) => {
      let h = i + 1 + c, f = " " + (" " + h).slice(-a) + " | ";
      if (h === this.line) {
        let p = l(f.replace(/\d/g, " ")) + u.slice(0, this.column - 1).replace(/[^\t]/g, " ");
        return o(">") + l(f) + u + `
 ` + p + o("^");
      }
      return " " + l(f) + u;
    }).join(`
`);
  }
  toString() {
    let e = this.showSourceCode();
    return e && (e = `

` + e + `
`), this.name + ": " + this.message + e;
  }
}
var zn = Ti;
Ti.default = Ti;
var Ew = Symbol("isClean"), Tw = Symbol("my"), Su = {
  isClean: Ew,
  my: Tw
};
const uh = {
  colon: ": ",
  indent: "    ",
  beforeDecl: `
`,
  beforeRule: `
`,
  beforeOpen: " ",
  beforeClose: `
`,
  beforeComment: `
`,
  after: `
`,
  emptyBody: "",
  commentLeft: " ",
  commentRight: " ",
  semicolon: !1
};
function Aw(r) {
  return r[0].toUpperCase() + r.slice(1);
}
class Ko {
  constructor(e) {
    this.builder = e;
  }
  stringify(e, t) {
    if (!this[e.type])
      throw new Error(
        "Unknown AST node type " + e.type + ". Maybe you need to change PostCSS stringifier."
      );
    this[e.type](e, t);
  }
  document(e) {
    this.body(e);
  }
  root(e) {
    this.body(e), e.raws.after && this.builder(e.raws.after);
  }
  comment(e) {
    let t = this.raw(e, "left", "commentLeft"), s = this.raw(e, "right", "commentRight");
    this.builder("/*" + t + e.text + s + "*/", e);
  }
  decl(e, t) {
    let s = this.raw(e, "between", "colon"), i = e.prop + s + this.rawValue(e, "value");
    e.important && (i += e.raws.important || " !important"), t && (i += ";"), this.builder(i, e);
  }
  rule(e) {
    this.block(e, this.rawValue(e, "selector")), e.raws.ownSemicolon && this.builder(e.raws.ownSemicolon, e, "end");
  }
  atrule(e, t) {
    let s = "@" + e.name, i = e.params ? this.rawValue(e, "params") : "";
    if (typeof e.raws.afterName != "undefined" ? s += e.raws.afterName : i && (s += " "), e.nodes)
      this.block(e, s + i);
    else {
      let n = (e.raws.between || "") + (t ? ";" : "");
      this.builder(s + i + n, e);
    }
  }
  body(e) {
    let t = e.nodes.length - 1;
    for (; t > 0 && e.nodes[t].type === "comment"; )
      t -= 1;
    let s = this.raw(e, "semicolon");
    for (let i = 0; i < e.nodes.length; i++) {
      let n = e.nodes[i], a = this.raw(n, "before");
      a && this.builder(a), this.stringify(n, t !== i || s);
    }
  }
  block(e, t) {
    let s = this.raw(e, "between", "beforeOpen");
    this.builder(t + s + "{", e, "start");
    let i;
    e.nodes && e.nodes.length ? (this.body(e), i = this.raw(e, "after")) : i = this.raw(e, "after", "emptyBody"), i && this.builder(i), this.builder("}", e, "end");
  }
  raw(e, t, s) {
    let i;
    if (s || (s = t), t && (i = e.raws[t], typeof i != "undefined"))
      return i;
    let n = e.parent;
    if (s === "before" && (!n || n.type === "root" && n.first === e || n && n.type === "document"))
      return "";
    if (!n)
      return uh[s];
    let a = e.root();
    if (a.rawCache || (a.rawCache = {}), typeof a.rawCache[s] != "undefined")
      return a.rawCache[s];
    if (s === "before" || s === "after")
      return this.beforeAfter(e, s);
    {
      let o = "raw" + Aw(s);
      this[o] ? i = this[o](a, e) : a.walk((l) => {
        if (i = l.raws[t], typeof i != "undefined")
          return !1;
      });
    }
    return typeof i == "undefined" && (i = uh[s]), a.rawCache[s] = i, i;
  }
  rawSemicolon(e) {
    let t;
    return e.walk((s) => {
      if (s.nodes && s.nodes.length && s.last.type === "decl" && (t = s.raws.semicolon, typeof t != "undefined"))
        return !1;
    }), t;
  }
  rawEmptyBody(e) {
    let t;
    return e.walk((s) => {
      if (s.nodes && s.nodes.length === 0 && (t = s.raws.after, typeof t != "undefined"))
        return !1;
    }), t;
  }
  rawIndent(e) {
    if (e.raws.indent)
      return e.raws.indent;
    let t;
    return e.walk((s) => {
      let i = s.parent;
      if (i && i !== e && i.parent && i.parent === e && typeof s.raws.before != "undefined") {
        let n = s.raws.before.split(`
`);
        return t = n[n.length - 1], t = t.replace(/\S/g, ""), !1;
      }
    }), t;
  }
  rawBeforeComment(e, t) {
    let s;
    return e.walkComments((i) => {
      if (typeof i.raws.before != "undefined")
        return s = i.raws.before, s.includes(`
`) && (s = s.replace(/[^\n]+$/, "")), !1;
    }), typeof s == "undefined" ? s = this.raw(t, null, "beforeDecl") : s && (s = s.replace(/\S/g, "")), s;
  }
  rawBeforeDecl(e, t) {
    let s;
    return e.walkDecls((i) => {
      if (typeof i.raws.before != "undefined")
        return s = i.raws.before, s.includes(`
`) && (s = s.replace(/[^\n]+$/, "")), !1;
    }), typeof s == "undefined" ? s = this.raw(t, null, "beforeRule") : s && (s = s.replace(/\S/g, "")), s;
  }
  rawBeforeRule(e) {
    let t;
    return e.walk((s) => {
      if (s.nodes && (s.parent !== e || e.first !== s) && typeof s.raws.before != "undefined")
        return t = s.raws.before, t.includes(`
`) && (t = t.replace(/[^\n]+$/, "")), !1;
    }), t && (t = t.replace(/\S/g, "")), t;
  }
  rawBeforeClose(e) {
    let t;
    return e.walk((s) => {
      if (s.nodes && s.nodes.length > 0 && typeof s.raws.after != "undefined")
        return t = s.raws.after, t.includes(`
`) && (t = t.replace(/[^\n]+$/, "")), !1;
    }), t && (t = t.replace(/\S/g, "")), t;
  }
  rawBeforeOpen(e) {
    let t;
    return e.walk((s) => {
      if (s.type !== "decl" && (t = s.raws.between, typeof t != "undefined"))
        return !1;
    }), t;
  }
  rawColon(e) {
    let t;
    return e.walkDecls((s) => {
      if (typeof s.raws.between != "undefined")
        return t = s.raws.between.replace(/[^\s:]/g, ""), !1;
    }), t;
  }
  beforeAfter(e, t) {
    let s;
    e.type === "decl" ? s = this.raw(e, null, "beforeDecl") : e.type === "comment" ? s = this.raw(e, null, "beforeComment") : t === "before" ? s = this.raw(e, null, "beforeRule") : s = this.raw(e, null, "beforeClose");
    let i = e.parent, n = 0;
    for (; i && i.type !== "root"; )
      n += 1, i = i.parent;
    if (s.includes(`
`)) {
      let a = this.raw(e, null, "indent");
      if (a.length)
        for (let o = 0; o < n; o++)
          s += a;
    }
    return s;
  }
  rawValue(e, t) {
    let s = e[t], i = e.raws[t];
    return i && i.value === s ? i.raw : s;
  }
}
var Ud = Ko;
Ko.default = Ko;
function Go(r, e) {
  new Ud(e).stringify(r);
}
var xa = Go;
Go.default = Go;
let { isClean: rn, my: _w } = Su;
function Yo(r, e) {
  let t = new r.constructor();
  for (let s in r) {
    if (!Object.prototype.hasOwnProperty.call(r, s) || s === "proxyCache")
      continue;
    let i = r[s], n = typeof i;
    s === "parent" && n === "object" ? e && (t[s] = e) : s === "source" ? t[s] = i : Array.isArray(i) ? t[s] = i.map((a) => Yo(a, t)) : (n === "object" && i !== null && (i = Yo(i)), t[s] = i);
  }
  return t;
}
class Jo {
  constructor(e = {}) {
    this.raws = {}, this[rn] = !1, this[_w] = !0;
    for (let t in e)
      if (t === "nodes") {
        this.nodes = [];
        for (let s of e[t])
          typeof s.clone == "function" ? this.append(s.clone()) : this.append(s);
      } else
        this[t] = e[t];
  }
  error(e, t = {}) {
    if (this.source) {
      let { start: s, end: i } = this.rangeBy(t);
      return this.source.input.error(
        e,
        { line: s.line, column: s.column },
        { line: i.line, column: i.column },
        t
      );
    }
    return new zn(e);
  }
  warn(e, t, s) {
    let i = { node: this };
    for (let n in s)
      i[n] = s[n];
    return e.warn(t, i);
  }
  remove() {
    return this.parent && this.parent.removeChild(this), this.parent = void 0, this;
  }
  toString(e = xa) {
    e.stringify && (e = e.stringify);
    let t = "";
    return e(this, (s) => {
      t += s;
    }), t;
  }
  assign(e = {}) {
    for (let t in e)
      this[t] = e[t];
    return this;
  }
  clone(e = {}) {
    let t = Yo(this);
    for (let s in e)
      t[s] = e[s];
    return t;
  }
  cloneBefore(e = {}) {
    let t = this.clone(e);
    return this.parent.insertBefore(this, t), t;
  }
  cloneAfter(e = {}) {
    let t = this.clone(e);
    return this.parent.insertAfter(this, t), t;
  }
  replaceWith(...e) {
    if (this.parent) {
      let t = this, s = !1;
      for (let i of e)
        i === this ? s = !0 : s ? (this.parent.insertAfter(t, i), t = i) : this.parent.insertBefore(t, i);
      s || this.remove();
    }
    return this;
  }
  next() {
    if (!this.parent)
      return;
    let e = this.parent.index(this);
    return this.parent.nodes[e + 1];
  }
  prev() {
    if (!this.parent)
      return;
    let e = this.parent.index(this);
    return this.parent.nodes[e - 1];
  }
  before(e) {
    return this.parent.insertBefore(this, e), this;
  }
  after(e) {
    return this.parent.insertAfter(this, e), this;
  }
  root() {
    let e = this;
    for (; e.parent && e.parent.type !== "document"; )
      e = e.parent;
    return e;
  }
  raw(e, t) {
    return new Ud().raw(this, e, t);
  }
  cleanRaws(e) {
    delete this.raws.before, delete this.raws.after, e || delete this.raws.between;
  }
  toJSON(e, t) {
    let s = {}, i = t == null;
    t = t || /* @__PURE__ */ new Map();
    let n = 0;
    for (let a in this) {
      if (!Object.prototype.hasOwnProperty.call(this, a) || a === "parent" || a === "proxyCache")
        continue;
      let o = this[a];
      if (Array.isArray(o))
        s[a] = o.map((l) => typeof l == "object" && l.toJSON ? l.toJSON(null, t) : l);
      else if (typeof o == "object" && o.toJSON)
        s[a] = o.toJSON(null, t);
      else if (a === "source") {
        let l = t.get(o.input);
        l == null && (l = n, t.set(o.input, n), n++), s[a] = {
          inputId: l,
          start: o.start,
          end: o.end
        };
      } else
        s[a] = o;
    }
    return i && (s.inputs = [...t.keys()].map((a) => a.toJSON())), s;
  }
  positionInside(e) {
    let t = this.toString(), s = this.source.start.column, i = this.source.start.line;
    for (let n = 0; n < e; n++)
      t[n] === `
` ? (s = 1, i += 1) : s += 1;
    return { line: i, column: s };
  }
  positionBy(e) {
    let t = this.source.start;
    if (e.index)
      t = this.positionInside(e.index);
    else if (e.word) {
      let s = this.toString().indexOf(e.word);
      s !== -1 && (t = this.positionInside(s));
    }
    return t;
  }
  rangeBy(e) {
    let t = {
      line: this.source.start.line,
      column: this.source.start.column
    }, s = this.source.end ? {
      line: this.source.end.line,
      column: this.source.end.column + 1
    } : {
      line: t.line,
      column: t.column + 1
    };
    if (e.word) {
      let i = this.toString().indexOf(e.word);
      i !== -1 && (t = this.positionInside(i), s = this.positionInside(i + e.word.length));
    } else
      e.start ? t = {
        line: e.start.line,
        column: e.start.column
      } : e.index && (t = this.positionInside(e.index)), e.end ? s = {
        line: e.end.line,
        column: e.end.column
      } : e.endIndex ? s = this.positionInside(e.endIndex) : e.index && (s = this.positionInside(e.index + 1));
    return (s.line < t.line || s.line === t.line && s.column <= t.column) && (s = { line: t.line, column: t.column + 1 }), { start: t, end: s };
  }
  getProxyProcessor() {
    return {
      set(e, t, s) {
        return e[t] === s || (e[t] = s, (t === "prop" || t === "value" || t === "name" || t === "params" || t === "important" || t === "text") && e.markDirty()), !0;
      },
      get(e, t) {
        return t === "proxyOf" ? e : t === "root" ? () => e.root().toProxy() : e[t];
      }
    };
  }
  toProxy() {
    return this.proxyCache || (this.proxyCache = new Proxy(this, this.getProxyProcessor())), this.proxyCache;
  }
  addToError(e) {
    if (e.postcssNode = this, e.stack && this.source && /\n\s{4}at /.test(e.stack)) {
      let t = this.source;
      e.stack = e.stack.replace(
        /\n\s{4}at /,
        `$&${t.input.from}:${t.start.line}:${t.start.column}$&`
      );
    }
    return e;
  }
  markDirty() {
    if (this[rn]) {
      this[rn] = !1;
      let e = this;
      for (; e = e.parent; )
        e[rn] = !1;
    }
  }
  get proxyOf() {
    return this;
  }
}
var Sa = Jo;
Jo.default = Jo;
class Qo extends Sa {
  constructor(e) {
    e && typeof e.value != "undefined" && typeof e.value != "string" && (e = Fs(Ht({}, e), { value: String(e.value) })), super(e), this.type = "decl";
  }
  get variable() {
    return this.prop.startsWith("--") || this.prop[0] === "$";
  }
}
var ks = Qo;
Qo.default = Qo;
var ch = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/".split(""), Cw = function(r) {
  if (0 <= r && r < ch.length)
    return ch[r];
  throw new TypeError("Must be between 0 and 63: " + r);
}, Iw = function(r) {
  var e = 65, t = 90, s = 97, i = 122, n = 48, a = 57, o = 43, l = 47, u = 26, c = 52;
  return e <= r && r <= t ? r - e : s <= r && r <= i ? r - s + u : n <= r && r <= a ? r - n + c : r == o ? 62 : r == l ? 63 : -1;
}, $d = {
  encode: Cw,
  decode: Iw
}, wu = 5, jd = 1 << wu, qd = jd - 1, Vd = jd;
function Nw(r) {
  return r < 0 ? (-r << 1) + 1 : (r << 1) + 0;
}
function Ow(r) {
  var e = (r & 1) === 1, t = r >> 1;
  return e ? -t : t;
}
var kw = function(e) {
  var t = "", s, i = Nw(e);
  do
    s = i & qd, i >>>= wu, i > 0 && (s |= Vd), t += $d.encode(s);
  while (i > 0);
  return t;
}, Mw = function(e, t, s) {
  var i = e.length, n = 0, a = 0, o, l;
  do {
    if (t >= i)
      throw new Error("Expected more digits in base 64 VLQ value.");
    if (l = $d.decode(e.charCodeAt(t++)), l === -1)
      throw new Error("Invalid base64 digit: " + e.charAt(t - 1));
    o = !!(l & Vd), l &= qd, n = n + (l << a), a += wu;
  } while (o);
  s.value = Ow(n), s.rest = t;
}, rs = {
  encode: kw,
  decode: Mw
}, j = Pe(function(r, e) {
  function t(g, E, O) {
    if (E in g)
      return g[E];
    if (arguments.length === 3)
      return O;
    throw new Error('"' + E + '" is a required argument.');
  }
  e.getArg = t;
  var s = /^(?:([\w+\-.]+):)?\/\/(?:(\w+:\w+)@)?([\w.-]*)(?::(\d+))?(.*)$/, i = /^data:.+\,.+$/;
  function n(g) {
    var E = g.match(s);
    return E ? {
      scheme: E[1],
      auth: E[2],
      host: E[3],
      port: E[4],
      path: E[5]
    } : null;
  }
  e.urlParse = n;
  function a(g) {
    var E = "";
    return g.scheme && (E += g.scheme + ":"), E += "//", g.auth && (E += g.auth + "@"), g.host && (E += g.host), g.port && (E += ":" + g.port), g.path && (E += g.path), E;
  }
  e.urlGenerate = a;
  var o = 32;
  function l(g) {
    var E = [];
    return function(O) {
      for (var S = 0; S < E.length; S++)
        if (E[S].input === O) {
          var W = E[0];
          return E[0] = E[S], E[S] = W, E[0].result;
        }
      var Q = g(O);
      return E.unshift({
        input: O,
        result: Q
      }), E.length > o && E.pop(), Q;
    };
  }
  var u = l(function(E) {
    var O = E, S = n(E);
    if (S) {
      if (!S.path)
        return E;
      O = S.path;
    }
    for (var W = e.isAbsolute(O), Q = [], xe = 0, re = 0; ; )
      if (xe = re, re = O.indexOf("/", xe), re === -1) {
        Q.push(O.slice(xe));
        break;
      } else
        for (Q.push(O.slice(xe, re)); re < O.length && O[re] === "/"; )
          re++;
    for (var J, ce = 0, re = Q.length - 1; re >= 0; re--)
      J = Q[re], J === "." ? Q.splice(re, 1) : J === ".." ? ce++ : ce > 0 && (J === "" ? (Q.splice(re + 1, ce), ce = 0) : (Q.splice(re, 2), ce--));
    return O = Q.join("/"), O === "" && (O = W ? "/" : "."), S ? (S.path = O, a(S)) : O;
  });
  e.normalize = u;
  function c(g, E) {
    g === "" && (g = "."), E === "" && (E = ".");
    var O = n(E), S = n(g);
    if (S && (g = S.path || "/"), O && !O.scheme)
      return S && (O.scheme = S.scheme), a(O);
    if (O || E.match(i))
      return E;
    if (S && !S.host && !S.path)
      return S.host = E, a(S);
    var W = E.charAt(0) === "/" ? E : u(g.replace(/\/+$/, "") + "/" + E);
    return S ? (S.path = W, a(S)) : W;
  }
  e.join = c, e.isAbsolute = function(g) {
    return g.charAt(0) === "/" || s.test(g);
  };
  function h(g, E) {
    g === "" && (g = "."), g = g.replace(/\/$/, "");
    for (var O = 0; E.indexOf(g + "/") !== 0; ) {
      var S = g.lastIndexOf("/");
      if (S < 0 || (g = g.slice(0, S), g.match(/^([^\/]+:\/)?\/*$/)))
        return E;
      ++O;
    }
    return Array(O + 1).join("../") + E.substr(g.length + 1);
  }
  e.relative = h;
  var f = function() {
    var g = /* @__PURE__ */ Object.create(null);
    return !("__proto__" in g);
  }();
  function p(g) {
    return g;
  }
  function x(g) {
    return m(g) ? "$" + g : g;
  }
  e.toSetString = f ? p : x;
  function d(g) {
    return m(g) ? g.slice(1) : g;
  }
  e.fromSetString = f ? p : d;
  function m(g) {
    if (!g)
      return !1;
    var E = g.length;
    if (E < 9 || g.charCodeAt(E - 1) !== 95 || g.charCodeAt(E - 2) !== 95 || g.charCodeAt(E - 3) !== 111 || g.charCodeAt(E - 4) !== 116 || g.charCodeAt(E - 5) !== 111 || g.charCodeAt(E - 6) !== 114 || g.charCodeAt(E - 7) !== 112 || g.charCodeAt(E - 8) !== 95 || g.charCodeAt(E - 9) !== 95)
      return !1;
    for (var O = E - 10; O >= 0; O--)
      if (g.charCodeAt(O) !== 36)
        return !1;
    return !0;
  }
  function y(g, E, O) {
    var S = v(g.source, E.source);
    return S !== 0 || (S = g.originalLine - E.originalLine, S !== 0) || (S = g.originalColumn - E.originalColumn, S !== 0 || O) || (S = g.generatedColumn - E.generatedColumn, S !== 0) || (S = g.generatedLine - E.generatedLine, S !== 0) ? S : v(g.name, E.name);
  }
  e.compareByOriginalPositions = y;
  function _(g, E, O) {
    var S;
    return S = g.originalLine - E.originalLine, S !== 0 || (S = g.originalColumn - E.originalColumn, S !== 0 || O) || (S = g.generatedColumn - E.generatedColumn, S !== 0) || (S = g.generatedLine - E.generatedLine, S !== 0) ? S : v(g.name, E.name);
  }
  e.compareByOriginalPositionsNoSource = _;
  function T(g, E, O) {
    var S = g.generatedLine - E.generatedLine;
    return S !== 0 || (S = g.generatedColumn - E.generatedColumn, S !== 0 || O) || (S = v(g.source, E.source), S !== 0) || (S = g.originalLine - E.originalLine, S !== 0) || (S = g.originalColumn - E.originalColumn, S !== 0) ? S : v(g.name, E.name);
  }
  e.compareByGeneratedPositionsDeflated = T;
  function C(g, E, O) {
    var S = g.generatedColumn - E.generatedColumn;
    return S !== 0 || O || (S = v(g.source, E.source), S !== 0) || (S = g.originalLine - E.originalLine, S !== 0) || (S = g.originalColumn - E.originalColumn, S !== 0) ? S : v(g.name, E.name);
  }
  e.compareByGeneratedPositionsDeflatedNoLine = C;
  function v(g, E) {
    return g === E ? 0 : g === null ? 1 : E === null ? -1 : g > E ? 1 : -1;
  }
  function w(g, E) {
    var O = g.generatedLine - E.generatedLine;
    return O !== 0 || (O = g.generatedColumn - E.generatedColumn, O !== 0) || (O = v(g.source, E.source), O !== 0) || (O = g.originalLine - E.originalLine, O !== 0) || (O = g.originalColumn - E.originalColumn, O !== 0) ? O : v(g.name, E.name);
  }
  e.compareByGeneratedPositionsInflated = w;
  function N(g) {
    return JSON.parse(g.replace(/^\)]}'[^\n]*\n/, ""));
  }
  e.parseSourceMapInput = N;
  function P(g, E, O) {
    if (E = E || "", g && (g[g.length - 1] !== "/" && E[0] !== "/" && (g += "/"), E = g + E), O) {
      var S = n(O);
      if (!S)
        throw new Error("sourceMapURL could not be parsed");
      if (S.path) {
        var W = S.path.lastIndexOf("/");
        W >= 0 && (S.path = S.path.substring(0, W + 1));
      }
      E = c(a(S), E);
    }
    return u(E);
  }
  e.computeSourceURL = P;
}), Pu = Object.prototype.hasOwnProperty, Gr = typeof Map != "undefined";
function nr() {
  this._array = [], this._set = Gr ? /* @__PURE__ */ new Map() : /* @__PURE__ */ Object.create(null);
}
nr.fromArray = function(e, t) {
  for (var s = new nr(), i = 0, n = e.length; i < n; i++)
    s.add(e[i], t);
  return s;
};
nr.prototype.size = function() {
  return Gr ? this._set.size : Object.getOwnPropertyNames(this._set).length;
};
nr.prototype.add = function(e, t) {
  var s = Gr ? e : j.toSetString(e), i = Gr ? this.has(e) : Pu.call(this._set, s), n = this._array.length;
  (!i || t) && this._array.push(e), i || (Gr ? this._set.set(e, n) : this._set[s] = n);
};
nr.prototype.has = function(e) {
  if (Gr)
    return this._set.has(e);
  var t = j.toSetString(e);
  return Pu.call(this._set, t);
};
nr.prototype.indexOf = function(e) {
  if (Gr) {
    var t = this._set.get(e);
    if (t >= 0)
      return t;
  } else {
    var s = j.toSetString(e);
    if (Pu.call(this._set, s))
      return this._set[s];
  }
  throw new Error('"' + e + '" is not in the set.');
};
nr.prototype.at = function(e) {
  if (e >= 0 && e < this._array.length)
    return this._array[e];
  throw new Error("No element indexed by " + e);
};
nr.prototype.toArray = function() {
  return this._array.slice();
};
var Lw = nr, zd = {
  ArraySet: Lw
};
function Dw(r, e) {
  var t = r.generatedLine, s = e.generatedLine, i = r.generatedColumn, n = e.generatedColumn;
  return s > t || s == t && n >= i || j.compareByGeneratedPositionsInflated(r, e) <= 0;
}
function wa() {
  this._array = [], this._sorted = !0, this._last = { generatedLine: -1, generatedColumn: 0 };
}
wa.prototype.unsortedForEach = function(e, t) {
  this._array.forEach(e, t);
};
wa.prototype.add = function(e) {
  Dw(this._last, e) ? (this._last = e, this._array.push(e)) : (this._sorted = !1, this._array.push(e));
};
wa.prototype.toArray = function() {
  return this._sorted || (this._array.sort(j.compareByGeneratedPositionsInflated), this._sorted = !0), this._array;
};
var Rw = wa, Fw = {
  MappingList: Rw
}, Wn = zd.ArraySet, Bw = Fw.MappingList;
function wt(r) {
  r || (r = {}), this._file = j.getArg(r, "file", null), this._sourceRoot = j.getArg(r, "sourceRoot", null), this._skipValidation = j.getArg(r, "skipValidation", !1), this._sources = new Wn(), this._names = new Wn(), this._mappings = new Bw(), this._sourcesContents = null;
}
wt.prototype._version = 3;
wt.fromSourceMap = function(e) {
  var t = e.sourceRoot, s = new wt({
    file: e.file,
    sourceRoot: t
  });
  return e.eachMapping(function(i) {
    var n = {
      generated: {
        line: i.generatedLine,
        column: i.generatedColumn
      }
    };
    i.source != null && (n.source = i.source, t != null && (n.source = j.relative(t, n.source)), n.original = {
      line: i.originalLine,
      column: i.originalColumn
    }, i.name != null && (n.name = i.name)), s.addMapping(n);
  }), e.sources.forEach(function(i) {
    var n = i;
    t !== null && (n = j.relative(t, i)), s._sources.has(n) || s._sources.add(n);
    var a = e.sourceContentFor(i);
    a != null && s.setSourceContent(i, a);
  }), s;
};
wt.prototype.addMapping = function(e) {
  var t = j.getArg(e, "generated"), s = j.getArg(e, "original", null), i = j.getArg(e, "source", null), n = j.getArg(e, "name", null);
  this._skipValidation || this._validateMapping(t, s, i, n), i != null && (i = String(i), this._sources.has(i) || this._sources.add(i)), n != null && (n = String(n), this._names.has(n) || this._names.add(n)), this._mappings.add({
    generatedLine: t.line,
    generatedColumn: t.column,
    originalLine: s != null && s.line,
    originalColumn: s != null && s.column,
    source: i,
    name: n
  });
};
wt.prototype.setSourceContent = function(e, t) {
  var s = e;
  this._sourceRoot != null && (s = j.relative(this._sourceRoot, s)), t != null ? (this._sourcesContents || (this._sourcesContents = /* @__PURE__ */ Object.create(null)), this._sourcesContents[j.toSetString(s)] = t) : this._sourcesContents && (delete this._sourcesContents[j.toSetString(s)], Object.keys(this._sourcesContents).length === 0 && (this._sourcesContents = null));
};
wt.prototype.applySourceMap = function(e, t, s) {
  var i = t;
  if (t == null) {
    if (e.file == null)
      throw new Error(
        `SourceMapGenerator.prototype.applySourceMap requires either an explicit source file, or the source map's "file" property. Both were omitted.`
      );
    i = e.file;
  }
  var n = this._sourceRoot;
  n != null && (i = j.relative(n, i));
  var a = new Wn(), o = new Wn();
  this._mappings.unsortedForEach(function(l) {
    if (l.source === i && l.originalLine != null) {
      var u = e.originalPositionFor({
        line: l.originalLine,
        column: l.originalColumn
      });
      u.source != null && (l.source = u.source, s != null && (l.source = j.join(s, l.source)), n != null && (l.source = j.relative(n, l.source)), l.originalLine = u.line, l.originalColumn = u.column, u.name != null && (l.name = u.name));
    }
    var c = l.source;
    c != null && !a.has(c) && a.add(c);
    var h = l.name;
    h != null && !o.has(h) && o.add(h);
  }, this), this._sources = a, this._names = o, e.sources.forEach(function(l) {
    var u = e.sourceContentFor(l);
    u != null && (s != null && (l = j.join(s, l)), n != null && (l = j.relative(n, l)), this.setSourceContent(l, u));
  }, this);
};
wt.prototype._validateMapping = function(e, t, s, i) {
  if (t && typeof t.line != "number" && typeof t.column != "number")
    throw new Error(
      "original.line and original.column are not numbers -- you probably meant to omit the original mapping entirely and only map the generated position. If so, pass null for the original mapping instead of an object with empty or null values."
    );
  if (!(e && "line" in e && "column" in e && e.line > 0 && e.column >= 0 && !t && !s && !i)) {
    if (e && "line" in e && "column" in e && t && "line" in t && "column" in t && e.line > 0 && e.column >= 0 && t.line > 0 && t.column >= 0 && s)
      return;
    throw new Error("Invalid mapping: " + JSON.stringify({
      generated: e,
      source: s,
      original: t,
      name: i
    }));
  }
};
wt.prototype._serializeMappings = function() {
  for (var e = 0, t = 1, s = 0, i = 0, n = 0, a = 0, o = "", l, u, c, h, f = this._mappings.toArray(), p = 0, x = f.length; p < x; p++) {
    if (u = f[p], l = "", u.generatedLine !== t)
      for (e = 0; u.generatedLine !== t; )
        l += ";", t++;
    else if (p > 0) {
      if (!j.compareByGeneratedPositionsInflated(u, f[p - 1]))
        continue;
      l += ",";
    }
    l += rs.encode(u.generatedColumn - e), e = u.generatedColumn, u.source != null && (h = this._sources.indexOf(u.source), l += rs.encode(h - a), a = h, l += rs.encode(u.originalLine - 1 - i), i = u.originalLine - 1, l += rs.encode(u.originalColumn - s), s = u.originalColumn, u.name != null && (c = this._names.indexOf(u.name), l += rs.encode(c - n), n = c)), o += l;
  }
  return o;
};
wt.prototype._generateSourcesContent = function(e, t) {
  return e.map(function(s) {
    if (!this._sourcesContents)
      return null;
    t != null && (s = j.relative(t, s));
    var i = j.toSetString(s);
    return Object.prototype.hasOwnProperty.call(this._sourcesContents, i) ? this._sourcesContents[i] : null;
  }, this);
};
wt.prototype.toJSON = function() {
  var e = {
    version: this._version,
    sources: this._sources.toArray(),
    names: this._names.toArray(),
    mappings: this._serializeMappings()
  };
  return this._file != null && (e.file = this._file), this._sourceRoot != null && (e.sourceRoot = this._sourceRoot), this._sourcesContents && (e.sourcesContent = this._generateSourcesContent(e.sources, e.sourceRoot)), e;
};
wt.prototype.toString = function() {
  return JSON.stringify(this.toJSON());
};
var Uw = wt, Wd = {
  SourceMapGenerator: Uw
}, Eu = Pe(function(r, e) {
  e.GREATEST_LOWER_BOUND = 1, e.LEAST_UPPER_BOUND = 2;
  function t(s, i, n, a, o, l) {
    var u = Math.floor((i - s) / 2) + s, c = o(n, a[u], !0);
    return c === 0 ? u : c > 0 ? i - u > 1 ? t(u, i, n, a, o, l) : l == e.LEAST_UPPER_BOUND ? i < a.length ? i : -1 : u : u - s > 1 ? t(s, u, n, a, o, l) : l == e.LEAST_UPPER_BOUND ? u : s < 0 ? -1 : s;
  }
  e.search = function(i, n, a, o) {
    if (n.length === 0)
      return -1;
    var l = t(
      -1,
      n.length,
      i,
      n,
      a,
      o || e.GREATEST_LOWER_BOUND
    );
    if (l < 0)
      return -1;
    for (; l - 1 >= 0 && a(n[l], n[l - 1], !0) === 0; )
      --l;
    return l;
  };
});
function $w(r) {
  function e(i, n, a) {
    var o = i[n];
    i[n] = i[a], i[a] = o;
  }
  function t(i, n) {
    return Math.round(i + Math.random() * (n - i));
  }
  function s(i, n, a, o) {
    if (a < o) {
      var l = t(a, o), u = a - 1;
      e(i, l, o);
      for (var c = i[o], h = a; h < o; h++)
        n(i[h], c, !1) <= 0 && (u += 1, e(i, u, h));
      e(i, u + 1, h);
      var f = u + 1;
      s(i, n, a, f - 1), s(i, n, f + 1, o);
    }
  }
  return s;
}
function jw(r) {
  let e = $w.toString();
  return new Function(`return ${e}`)()(r);
}
let hh = /* @__PURE__ */ new WeakMap();
var qw = function(r, e, t = 0) {
  let s = hh.get(e);
  s === void 0 && (s = jw(e), hh.set(e, s)), s(r, e, t, r.length - 1);
}, Vw = {
  quickSort: qw
}, As = zd.ArraySet, Ai = Vw.quickSort;
function Le(r, e) {
  var t = r;
  return typeof r == "string" && (t = j.parseSourceMapInput(r)), t.sections != null ? new kt(t, e) : new Ge(t, e);
}
Le.fromSourceMap = function(r, e) {
  return Ge.fromSourceMap(r, e);
};
Le.prototype._version = 3;
Le.prototype.__generatedMappings = null;
Object.defineProperty(Le.prototype, "_generatedMappings", {
  configurable: !0,
  enumerable: !0,
  get: function() {
    return this.__generatedMappings || this._parseMappings(this._mappings, this.sourceRoot), this.__generatedMappings;
  }
});
Le.prototype.__originalMappings = null;
Object.defineProperty(Le.prototype, "_originalMappings", {
  configurable: !0,
  enumerable: !0,
  get: function() {
    return this.__originalMappings || this._parseMappings(this._mappings, this.sourceRoot), this.__originalMappings;
  }
});
Le.prototype._charIsMappingSeparator = function(e, t) {
  var s = e.charAt(t);
  return s === ";" || s === ",";
};
Le.prototype._parseMappings = function(e, t) {
  throw new Error("Subclasses must implement _parseMappings");
};
Le.GENERATED_ORDER = 1;
Le.ORIGINAL_ORDER = 2;
Le.GREATEST_LOWER_BOUND = 1;
Le.LEAST_UPPER_BOUND = 2;
Le.prototype.eachMapping = function(e, t, s) {
  var i = t || null, n = s || Le.GENERATED_ORDER, a;
  switch (n) {
    case Le.GENERATED_ORDER:
      a = this._generatedMappings;
      break;
    case Le.ORIGINAL_ORDER:
      a = this._originalMappings;
      break;
    default:
      throw new Error("Unknown order of iteration.");
  }
  for (var o = this.sourceRoot, l = e.bind(i), u = this._names, c = this._sources, h = this._sourceMapURL, f = 0, p = a.length; f < p; f++) {
    var x = a[f], d = x.source === null ? null : c.at(x.source);
    d = j.computeSourceURL(o, d, h), l({
      source: d,
      generatedLine: x.generatedLine,
      generatedColumn: x.generatedColumn,
      originalLine: x.originalLine,
      originalColumn: x.originalColumn,
      name: x.name === null ? null : u.at(x.name)
    });
  }
};
Le.prototype.allGeneratedPositionsFor = function(e) {
  var t = j.getArg(e, "line"), s = {
    source: j.getArg(e, "source"),
    originalLine: t,
    originalColumn: j.getArg(e, "column", 0)
  };
  if (s.source = this._findSourceIndex(s.source), s.source < 0)
    return [];
  var i = [], n = this._findMapping(
    s,
    this._originalMappings,
    "originalLine",
    "originalColumn",
    j.compareByOriginalPositions,
    Eu.LEAST_UPPER_BOUND
  );
  if (n >= 0) {
    var a = this._originalMappings[n];
    if (e.column === void 0)
      for (var o = a.originalLine; a && a.originalLine === o; )
        i.push({
          line: j.getArg(a, "generatedLine", null),
          column: j.getArg(a, "generatedColumn", null),
          lastColumn: j.getArg(a, "lastGeneratedColumn", null)
        }), a = this._originalMappings[++n];
    else
      for (var l = a.originalColumn; a && a.originalLine === t && a.originalColumn == l; )
        i.push({
          line: j.getArg(a, "generatedLine", null),
          column: j.getArg(a, "generatedColumn", null),
          lastColumn: j.getArg(a, "lastGeneratedColumn", null)
        }), a = this._originalMappings[++n];
  }
  return i;
};
var zw = Le;
function Ge(r, e) {
  var t = r;
  typeof r == "string" && (t = j.parseSourceMapInput(r));
  var s = j.getArg(t, "version"), i = j.getArg(t, "sources"), n = j.getArg(t, "names", []), a = j.getArg(t, "sourceRoot", null), o = j.getArg(t, "sourcesContent", null), l = j.getArg(t, "mappings"), u = j.getArg(t, "file", null);
  if (s != this._version)
    throw new Error("Unsupported version: " + s);
  a && (a = j.normalize(a)), i = i.map(String).map(j.normalize).map(function(c) {
    return a && j.isAbsolute(a) && j.isAbsolute(c) ? j.relative(a, c) : c;
  }), this._names = As.fromArray(n.map(String), !0), this._sources = As.fromArray(i, !0), this._absoluteSources = this._sources.toArray().map(function(c) {
    return j.computeSourceURL(a, c, e);
  }), this.sourceRoot = a, this.sourcesContent = o, this._mappings = l, this._sourceMapURL = e, this.file = u;
}
Ge.prototype = Object.create(Le.prototype);
Ge.prototype.consumer = Le;
Ge.prototype._findSourceIndex = function(r) {
  var e = r;
  if (this.sourceRoot != null && (e = j.relative(this.sourceRoot, e)), this._sources.has(e))
    return this._sources.indexOf(e);
  var t;
  for (t = 0; t < this._absoluteSources.length; ++t)
    if (this._absoluteSources[t] == r)
      return t;
  return -1;
};
Ge.fromSourceMap = function(e, t) {
  var s = Object.create(Ge.prototype), i = s._names = As.fromArray(e._names.toArray(), !0), n = s._sources = As.fromArray(e._sources.toArray(), !0);
  s.sourceRoot = e._sourceRoot, s.sourcesContent = e._generateSourcesContent(
    s._sources.toArray(),
    s.sourceRoot
  ), s.file = e._file, s._sourceMapURL = t, s._absoluteSources = s._sources.toArray().map(function(p) {
    return j.computeSourceURL(s.sourceRoot, p, t);
  });
  for (var a = e._mappings.toArray().slice(), o = s.__generatedMappings = [], l = s.__originalMappings = [], u = 0, c = a.length; u < c; u++) {
    var h = a[u], f = new Hd();
    f.generatedLine = h.generatedLine, f.generatedColumn = h.generatedColumn, h.source && (f.source = n.indexOf(h.source), f.originalLine = h.originalLine, f.originalColumn = h.originalColumn, h.name && (f.name = i.indexOf(h.name)), l.push(f)), o.push(f);
  }
  return Ai(s.__originalMappings, j.compareByOriginalPositions), s;
};
Ge.prototype._version = 3;
Object.defineProperty(Ge.prototype, "sources", {
  get: function() {
    return this._absoluteSources.slice();
  }
});
function Hd() {
  this.generatedLine = 0, this.generatedColumn = 0, this.source = null, this.originalLine = null, this.originalColumn = null, this.name = null;
}
const to = j.compareByGeneratedPositionsDeflatedNoLine;
function fh(r, e) {
  let t = r.length, s = r.length - e;
  if (!(s <= 1))
    if (s == 2) {
      let i = r[e], n = r[e + 1];
      to(i, n) > 0 && (r[e] = n, r[e + 1] = i);
    } else if (s < 20)
      for (let i = e; i < t; i++)
        for (let n = i; n > e; n--) {
          let a = r[n - 1], o = r[n];
          if (to(a, o) <= 0)
            break;
          r[n - 1] = o, r[n] = a;
        }
    else
      Ai(r, to, e);
}
Ge.prototype._parseMappings = function(e, t) {
  var s = 1, i = 0, n = 0, a = 0, o = 0, l = 0, u = e.length, c = 0, h = {}, f = [], p = [], x, d, m, y;
  let _ = 0;
  for (; c < u; )
    if (e.charAt(c) === ";")
      s++, c++, i = 0, fh(p, _), _ = p.length;
    else if (e.charAt(c) === ",")
      c++;
    else {
      for (x = new Hd(), x.generatedLine = s, m = c; m < u && !this._charIsMappingSeparator(e, m); m++)
        ;
      for (e.slice(c, m), d = []; c < m; )
        rs.decode(e, c, h), y = h.value, c = h.rest, d.push(y);
      if (d.length === 2)
        throw new Error("Found a source, but no line and column");
      if (d.length === 3)
        throw new Error("Found a source and line, but no column");
      if (x.generatedColumn = i + d[0], i = x.generatedColumn, d.length > 1 && (x.source = o + d[1], o += d[1], x.originalLine = n + d[2], n = x.originalLine, x.originalLine += 1, x.originalColumn = a + d[3], a = x.originalColumn, d.length > 4 && (x.name = l + d[4], l += d[4])), p.push(x), typeof x.originalLine == "number") {
        let C = x.source;
        for (; f.length <= C; )
          f.push(null);
        f[C] === null && (f[C] = []), f[C].push(x);
      }
    }
  fh(p, _), this.__generatedMappings = p;
  for (var T = 0; T < f.length; T++)
    f[T] != null && Ai(f[T], j.compareByOriginalPositionsNoSource);
  this.__originalMappings = [].concat(...f);
};
Ge.prototype._findMapping = function(e, t, s, i, n, a) {
  if (e[s] <= 0)
    throw new TypeError("Line must be greater than or equal to 1, got " + e[s]);
  if (e[i] < 0)
    throw new TypeError("Column must be greater than or equal to 0, got " + e[i]);
  return Eu.search(e, t, n, a);
};
Ge.prototype.computeColumnSpans = function() {
  for (var e = 0; e < this._generatedMappings.length; ++e) {
    var t = this._generatedMappings[e];
    if (e + 1 < this._generatedMappings.length) {
      var s = this._generatedMappings[e + 1];
      if (t.generatedLine === s.generatedLine) {
        t.lastGeneratedColumn = s.generatedColumn - 1;
        continue;
      }
    }
    t.lastGeneratedColumn = 1 / 0;
  }
};
Ge.prototype.originalPositionFor = function(e) {
  var t = {
    generatedLine: j.getArg(e, "line"),
    generatedColumn: j.getArg(e, "column")
  }, s = this._findMapping(
    t,
    this._generatedMappings,
    "generatedLine",
    "generatedColumn",
    j.compareByGeneratedPositionsDeflated,
    j.getArg(e, "bias", Le.GREATEST_LOWER_BOUND)
  );
  if (s >= 0) {
    var i = this._generatedMappings[s];
    if (i.generatedLine === t.generatedLine) {
      var n = j.getArg(i, "source", null);
      n !== null && (n = this._sources.at(n), n = j.computeSourceURL(this.sourceRoot, n, this._sourceMapURL));
      var a = j.getArg(i, "name", null);
      return a !== null && (a = this._names.at(a)), {
        source: n,
        line: j.getArg(i, "originalLine", null),
        column: j.getArg(i, "originalColumn", null),
        name: a
      };
    }
  }
  return {
    source: null,
    line: null,
    column: null,
    name: null
  };
};
Ge.prototype.hasContentsOfAllSources = function() {
  return this.sourcesContent ? this.sourcesContent.length >= this._sources.size() && !this.sourcesContent.some(function(e) {
    return e == null;
  }) : !1;
};
Ge.prototype.sourceContentFor = function(e, t) {
  if (!this.sourcesContent)
    return null;
  var s = this._findSourceIndex(e);
  if (s >= 0)
    return this.sourcesContent[s];
  var i = e;
  this.sourceRoot != null && (i = j.relative(this.sourceRoot, i));
  var n;
  if (this.sourceRoot != null && (n = j.urlParse(this.sourceRoot))) {
    var a = i.replace(/^file:\/\//, "");
    if (n.scheme == "file" && this._sources.has(a))
      return this.sourcesContent[this._sources.indexOf(a)];
    if ((!n.path || n.path == "/") && this._sources.has("/" + i))
      return this.sourcesContent[this._sources.indexOf("/" + i)];
  }
  if (t)
    return null;
  throw new Error('"' + i + '" is not in the SourceMap.');
};
Ge.prototype.generatedPositionFor = function(e) {
  var t = j.getArg(e, "source");
  if (t = this._findSourceIndex(t), t < 0)
    return {
      line: null,
      column: null,
      lastColumn: null
    };
  var s = {
    source: t,
    originalLine: j.getArg(e, "line"),
    originalColumn: j.getArg(e, "column")
  }, i = this._findMapping(
    s,
    this._originalMappings,
    "originalLine",
    "originalColumn",
    j.compareByOriginalPositions,
    j.getArg(e, "bias", Le.GREATEST_LOWER_BOUND)
  );
  if (i >= 0) {
    var n = this._originalMappings[i];
    if (n.source === s.source)
      return {
        line: j.getArg(n, "generatedLine", null),
        column: j.getArg(n, "generatedColumn", null),
        lastColumn: j.getArg(n, "lastGeneratedColumn", null)
      };
  }
  return {
    line: null,
    column: null,
    lastColumn: null
  };
};
var Ww = Ge;
function kt(r, e) {
  var t = r;
  typeof r == "string" && (t = j.parseSourceMapInput(r));
  var s = j.getArg(t, "version"), i = j.getArg(t, "sections");
  if (s != this._version)
    throw new Error("Unsupported version: " + s);
  this._sources = new As(), this._names = new As();
  var n = {
    line: -1,
    column: 0
  };
  this._sections = i.map(function(a) {
    if (a.url)
      throw new Error("Support for url field in sections not implemented.");
    var o = j.getArg(a, "offset"), l = j.getArg(o, "line"), u = j.getArg(o, "column");
    if (l < n.line || l === n.line && u < n.column)
      throw new Error("Section offsets must be ordered and non-overlapping.");
    return n = o, {
      generatedOffset: {
        generatedLine: l + 1,
        generatedColumn: u + 1
      },
      consumer: new Le(j.getArg(a, "map"), e)
    };
  });
}
kt.prototype = Object.create(Le.prototype);
kt.prototype.constructor = Le;
kt.prototype._version = 3;
Object.defineProperty(kt.prototype, "sources", {
  get: function() {
    for (var r = [], e = 0; e < this._sections.length; e++)
      for (var t = 0; t < this._sections[e].consumer.sources.length; t++)
        r.push(this._sections[e].consumer.sources[t]);
    return r;
  }
});
kt.prototype.originalPositionFor = function(e) {
  var t = {
    generatedLine: j.getArg(e, "line"),
    generatedColumn: j.getArg(e, "column")
  }, s = Eu.search(
    t,
    this._sections,
    function(n, a) {
      var o = n.generatedLine - a.generatedOffset.generatedLine;
      return o || n.generatedColumn - a.generatedOffset.generatedColumn;
    }
  ), i = this._sections[s];
  return i ? i.consumer.originalPositionFor({
    line: t.generatedLine - (i.generatedOffset.generatedLine - 1),
    column: t.generatedColumn - (i.generatedOffset.generatedLine === t.generatedLine ? i.generatedOffset.generatedColumn - 1 : 0),
    bias: e.bias
  }) : {
    source: null,
    line: null,
    column: null,
    name: null
  };
};
kt.prototype.hasContentsOfAllSources = function() {
  return this._sections.every(function(e) {
    return e.consumer.hasContentsOfAllSources();
  });
};
kt.prototype.sourceContentFor = function(e, t) {
  for (var s = 0; s < this._sections.length; s++) {
    var i = this._sections[s], n = i.consumer.sourceContentFor(e, !0);
    if (n)
      return n;
  }
  if (t)
    return null;
  throw new Error('"' + e + '" is not in the SourceMap.');
};
kt.prototype.generatedPositionFor = function(e) {
  for (var t = 0; t < this._sections.length; t++) {
    var s = this._sections[t];
    if (s.consumer._findSourceIndex(j.getArg(e, "source")) !== -1) {
      var i = s.consumer.generatedPositionFor(e);
      if (i) {
        var n = {
          line: i.line + (s.generatedOffset.generatedLine - 1),
          column: i.column + (s.generatedOffset.generatedLine === i.line ? s.generatedOffset.generatedColumn - 1 : 0)
        };
        return n;
      }
    }
  }
  return {
    line: null,
    column: null
  };
};
kt.prototype._parseMappings = function(e, t) {
  this.__generatedMappings = [], this.__originalMappings = [];
  for (var s = 0; s < this._sections.length; s++)
    for (var i = this._sections[s], n = i.consumer._generatedMappings, a = 0; a < n.length; a++) {
      var o = n[a], l = i.consumer._sources.at(o.source);
      l = j.computeSourceURL(i.consumer.sourceRoot, l, this._sourceMapURL), this._sources.add(l), l = this._sources.indexOf(l);
      var u = null;
      o.name && (u = i.consumer._names.at(o.name), this._names.add(u), u = this._names.indexOf(u));
      var c = {
        source: l,
        generatedLine: o.generatedLine + (i.generatedOffset.generatedLine - 1),
        generatedColumn: o.generatedColumn + (i.generatedOffset.generatedLine === o.generatedLine ? i.generatedOffset.generatedColumn - 1 : 0),
        originalLine: o.originalLine,
        originalColumn: o.originalColumn,
        name: u
      };
      this.__generatedMappings.push(c), typeof c.originalLine == "number" && this.__originalMappings.push(c);
    }
  Ai(this.__generatedMappings, j.compareByGeneratedPositionsDeflated), Ai(this.__originalMappings, j.compareByOriginalPositions);
};
var Hw = kt, Kw = {
  SourceMapConsumer: zw,
  BasicSourceMapConsumer: Ww,
  IndexedSourceMapConsumer: Hw
}, Gw = Wd.SourceMapGenerator, Yw = /(\r?\n)/, Jw = 10, Ms = "$$$isSourceNode$$$";
function ft(r, e, t, s, i) {
  this.children = [], this.sourceContents = {}, this.line = r == null ? null : r, this.column = e == null ? null : e, this.source = t == null ? null : t, this.name = i == null ? null : i, this[Ms] = !0, s != null && this.add(s);
}
ft.fromStringWithSourceMap = function(e, t, s) {
  var i = new ft(), n = e.split(Yw), a = 0, o = function() {
    var f = x(), p = x() || "";
    return f + p;
    function x() {
      return a < n.length ? n[a++] : void 0;
    }
  }, l = 1, u = 0, c = null;
  return t.eachMapping(function(f) {
    if (c !== null)
      if (l < f.generatedLine)
        h(c, o()), l++, u = 0;
      else {
        var p = n[a] || "", x = p.substr(0, f.generatedColumn - u);
        n[a] = p.substr(f.generatedColumn - u), u = f.generatedColumn, h(c, x), c = f;
        return;
      }
    for (; l < f.generatedLine; )
      i.add(o()), l++;
    if (u < f.generatedColumn) {
      var p = n[a] || "";
      i.add(p.substr(0, f.generatedColumn)), n[a] = p.substr(f.generatedColumn), u = f.generatedColumn;
    }
    c = f;
  }, this), a < n.length && (c && h(c, o()), i.add(n.splice(a).join(""))), t.sources.forEach(function(f) {
    var p = t.sourceContentFor(f);
    p != null && (s != null && (f = j.join(s, f)), i.setSourceContent(f, p));
  }), i;
  function h(f, p) {
    if (f === null || f.source === void 0)
      i.add(p);
    else {
      var x = s ? j.join(s, f.source) : f.source;
      i.add(new ft(
        f.originalLine,
        f.originalColumn,
        x,
        p,
        f.name
      ));
    }
  }
};
ft.prototype.add = function(e) {
  if (Array.isArray(e))
    e.forEach(function(t) {
      this.add(t);
    }, this);
  else if (e[Ms] || typeof e == "string")
    e && this.children.push(e);
  else
    throw new TypeError(
      "Expected a SourceNode, string, or an array of SourceNodes and strings. Got " + e
    );
  return this;
};
ft.prototype.prepend = function(e) {
  if (Array.isArray(e))
    for (var t = e.length - 1; t >= 0; t--)
      this.prepend(e[t]);
  else if (e[Ms] || typeof e == "string")
    this.children.unshift(e);
  else
    throw new TypeError(
      "Expected a SourceNode, string, or an array of SourceNodes and strings. Got " + e
    );
  return this;
};
ft.prototype.walk = function(e) {
  for (var t, s = 0, i = this.children.length; s < i; s++)
    t = this.children[s], t[Ms] ? t.walk(e) : t !== "" && e(t, {
      source: this.source,
      line: this.line,
      column: this.column,
      name: this.name
    });
};
ft.prototype.join = function(e) {
  var t, s, i = this.children.length;
  if (i > 0) {
    for (t = [], s = 0; s < i - 1; s++)
      t.push(this.children[s]), t.push(e);
    t.push(this.children[s]), this.children = t;
  }
  return this;
};
ft.prototype.replaceRight = function(e, t) {
  var s = this.children[this.children.length - 1];
  return s[Ms] ? s.replaceRight(e, t) : typeof s == "string" ? this.children[this.children.length - 1] = s.replace(e, t) : this.children.push("".replace(e, t)), this;
};
ft.prototype.setSourceContent = function(e, t) {
  this.sourceContents[j.toSetString(e)] = t;
};
ft.prototype.walkSourceContents = function(e) {
  for (var t = 0, s = this.children.length; t < s; t++)
    this.children[t][Ms] && this.children[t].walkSourceContents(e);
  for (var i = Object.keys(this.sourceContents), t = 0, s = i.length; t < s; t++)
    e(j.fromSetString(i[t]), this.sourceContents[i[t]]);
};
ft.prototype.toString = function() {
  var e = "";
  return this.walk(function(t) {
    e += t;
  }), e;
};
ft.prototype.toStringWithSourceMap = function(e) {
  var t = {
    code: "",
    line: 1,
    column: 0
  }, s = new Gw(e), i = !1, n = null, a = null, o = null, l = null;
  return this.walk(function(u, c) {
    t.code += u, c.source !== null && c.line !== null && c.column !== null ? ((n !== c.source || a !== c.line || o !== c.column || l !== c.name) && s.addMapping({
      source: c.source,
      original: {
        line: c.line,
        column: c.column
      },
      generated: {
        line: t.line,
        column: t.column
      },
      name: c.name
    }), n = c.source, a = c.line, o = c.column, l = c.name, i = !0) : i && (s.addMapping({
      generated: {
        line: t.line,
        column: t.column
      }
    }), n = null, i = !1);
    for (var h = 0, f = u.length; h < f; h++)
      u.charCodeAt(h) === Jw ? (t.line++, t.column = 0, h + 1 === f ? (n = null, i = !1) : i && s.addMapping({
        source: c.source,
        original: {
          line: c.line,
          column: c.column
        },
        generated: {
          line: t.line,
          column: t.column
        },
        name: c.name
      })) : t.column++;
  }), this.walkSourceContents(function(u, c) {
    s.setSourceContent(u, c);
  }), { code: t.code, map: s };
};
var Qw = ft, Xw = {
  SourceNode: Qw
}, Zw = Wd.SourceMapGenerator, eP = Kw.SourceMapConsumer, tP = Xw.SourceNode, Tu = {
  SourceMapGenerator: Zw,
  SourceMapConsumer: eP,
  SourceNode: tP
};
let rP = "useandom-26T198340PX75pxJACKVERYMINDBUSHWOLF_GQZbfghjklqvwyzrict", sP = (r, e) => () => {
  let t = "", s = e;
  for (; s--; )
    t += r[Math.random() * r.length | 0];
  return t;
}, iP = (r = 21) => {
  let e = "", t = r;
  for (; t--; )
    e += rP[Math.random() * 64 | 0];
  return e;
};
var nP = { nanoid: iP, customAlphabet: sP };
let { SourceMapConsumer: ph, SourceMapGenerator: dh } = Tu, { existsSync: aP, readFileSync: oP } = sw, { dirname: ro, join: lP } = xu;
function uP(r) {
  return F ? F.from(r, "base64").toString() : window.atob(r);
}
class Xo {
  constructor(e, t) {
    if (t.map === !1)
      return;
    this.loadAnnotation(e), this.inline = this.startWith(this.annotation, "data:");
    let s = t.map ? t.map.prev : void 0, i = this.loadMap(t.from, s);
    !this.mapFile && t.from && (this.mapFile = t.from), this.mapFile && (this.root = ro(this.mapFile)), i && (this.text = i);
  }
  consumer() {
    return this.consumerCache || (this.consumerCache = new ph(this.text)), this.consumerCache;
  }
  withContent() {
    return !!(this.consumer().sourcesContent && this.consumer().sourcesContent.length > 0);
  }
  startWith(e, t) {
    return e ? e.substr(0, t.length) === t : !1;
  }
  getAnnotationURL(e) {
    return e.replace(/^\/\*\s*# sourceMappingURL=/, "").trim();
  }
  loadAnnotation(e) {
    let t = e.match(/\/\*\s*# sourceMappingURL=/gm);
    if (!t)
      return;
    let s = e.lastIndexOf(t.pop()), i = e.indexOf("*/", s);
    s > -1 && i > -1 && (this.annotation = this.getAnnotationURL(e.substring(s, i)));
  }
  decodeInline(e) {
    let t = /^data:application\/json;charset=utf-?8;base64,/, s = /^data:application\/json;base64,/, i = /^data:application\/json;charset=utf-?8,/, n = /^data:application\/json,/;
    if (i.test(e) || n.test(e))
      return decodeURIComponent(e.substr(RegExp.lastMatch.length));
    if (t.test(e) || s.test(e))
      return uP(e.substr(RegExp.lastMatch.length));
    let a = e.match(/data:application\/json;([^,]+),/)[1];
    throw new Error("Unsupported source map encoding " + a);
  }
  loadFile(e) {
    if (this.root = ro(e), aP(e))
      return this.mapFile = e, oP(e, "utf-8").toString().trim();
  }
  loadMap(e, t) {
    if (t === !1)
      return !1;
    if (t) {
      if (typeof t == "string")
        return t;
      if (typeof t == "function") {
        let s = t(e);
        if (s) {
          let i = this.loadFile(s);
          if (!i)
            throw new Error(
              "Unable to load previous source map: " + s.toString()
            );
          return i;
        }
      } else {
        if (t instanceof ph)
          return dh.fromSourceMap(t).toString();
        if (t instanceof dh)
          return t.toString();
        if (this.isMap(t))
          return JSON.stringify(t);
        throw new Error(
          "Unsupported previous source map format: " + t.toString()
        );
      }
    } else {
      if (this.inline)
        return this.decodeInline(this.annotation);
      if (this.annotation) {
        let s = this.annotation;
        return e && (s = lP(ro(e), s)), this.loadFile(s);
      }
    }
  }
  isMap(e) {
    return typeof e != "object" ? !1 : typeof e.mappings == "string" || typeof e._mappings == "string" || Array.isArray(e.sections);
  }
}
var Kd = Xo;
Xo.default = Xo;
var Gd = /* @__PURE__ */ Di(kS);
let { SourceMapConsumer: cP, SourceMapGenerator: hP } = Tu, { fileURLToPath: mh, pathToFileURL: sn } = Gd, { resolve: Zo, isAbsolute: el } = xu, { nanoid: fP } = nP, so = Symbol("fromOffsetCache"), pP = Boolean(cP && hP), yh = Boolean(Zo && el);
class Hn {
  constructor(e, t = {}) {
    if (e === null || typeof e == "undefined" || typeof e == "object" && !e.toString)
      throw new Error(`PostCSS received ${e} instead of CSS string`);
    if (this.css = e.toString(), this.css[0] === "\uFEFF" || this.css[0] === "\uFFFE" ? (this.hasBOM = !0, this.css = this.css.slice(1)) : this.hasBOM = !1, t.from && (!yh || /^\w+:\/\//.test(t.from) || el(t.from) ? this.file = t.from : this.file = Zo(t.from)), yh && pP) {
      let s = new Kd(this.css, t);
      if (s.text) {
        this.map = s;
        let i = s.consumer().file;
        !this.file && i && (this.file = this.mapResolve(i));
      }
    }
    this.file || (this.id = "<input css " + fP(6) + ">"), this.map && (this.map.file = this.from);
  }
  fromOffset(e) {
    let t, s;
    if (this[so])
      s = this[so];
    else {
      let n = this.css.split(`
`);
      s = new Array(n.length);
      let a = 0;
      for (let o = 0, l = n.length; o < l; o++)
        s[o] = a, a += n[o].length + 1;
      this[so] = s;
    }
    t = s[s.length - 1];
    let i = 0;
    if (e >= t)
      i = s.length - 1;
    else {
      let n = s.length - 2, a;
      for (; i < n; )
        if (a = i + (n - i >> 1), e < s[a])
          n = a - 1;
        else if (e >= s[a + 1])
          i = a + 1;
        else {
          i = a;
          break;
        }
    }
    return {
      line: i + 1,
      col: e - s[i] + 1
    };
  }
  error(e, t, s, i = {}) {
    let n, a, o;
    if (t && typeof t == "object") {
      let u = t, c = s;
      if (typeof t.offset == "number") {
        let h = this.fromOffset(u.offset);
        t = h.line, s = h.col;
      } else
        t = u.line, s = u.column;
      if (typeof c.offset == "number") {
        let h = this.fromOffset(c.offset);
        a = h.line, o = h.col;
      } else
        a = c.line, o = c.column;
    } else if (!s) {
      let u = this.fromOffset(t);
      t = u.line, s = u.col;
    }
    let l = this.origin(t, s, a, o);
    return l ? n = new zn(
      e,
      l.endLine === void 0 ? l.line : { line: l.line, column: l.column },
      l.endLine === void 0 ? l.column : { line: l.endLine, column: l.endColumn },
      l.source,
      l.file,
      i.plugin
    ) : n = new zn(
      e,
      a === void 0 ? t : { line: t, column: s },
      a === void 0 ? s : { line: a, column: o },
      this.css,
      this.file,
      i.plugin
    ), n.input = { line: t, column: s, endLine: a, endColumn: o, source: this.css }, this.file && (sn && (n.input.url = sn(this.file).toString()), n.input.file = this.file), n;
  }
  origin(e, t, s, i) {
    if (!this.map)
      return !1;
    let n = this.map.consumer(), a = n.originalPositionFor({ line: e, column: t });
    if (!a.source)
      return !1;
    let o;
    typeof s == "number" && (o = n.originalPositionFor({ line: s, column: i }));
    let l;
    el(a.source) ? l = sn(a.source) : l = new URL(
      a.source,
      this.map.consumer().sourceRoot || sn(this.map.mapFile)
    );
    let u = {
      url: l.toString(),
      line: a.line,
      column: a.column,
      endLine: o && o.line,
      endColumn: o && o.column
    };
    if (l.protocol === "file:")
      if (mh)
        u.file = mh(l);
      else
        throw new Error("file: protocol is not available in this PostCSS build");
    let c = n.sourceContentFor(a.source);
    return c && (u.source = c), u;
  }
  mapResolve(e) {
    return /^\w+:\/\//.test(e) ? e : Zo(this.map.consumer().sourceRoot || this.map.root || ".", e);
  }
  get from() {
    return this.file || this.id;
  }
  toJSON() {
    let e = {};
    for (let t of ["hasBOM", "css", "file", "id"])
      this[t] != null && (e[t] = this[t]);
    return this.map && (e.map = Ht({}, this.map), e.map.consumerCache && (e.map.consumerCache = void 0)), e;
  }
}
var Pa = Hn;
Hn.default = Hn;
ci && ci.registerInput && ci.registerInput(Hn);
let { SourceMapConsumer: Yd, SourceMapGenerator: mn } = Tu, { dirname: yn, resolve: Jd, relative: Qd, sep: Xd } = xu, { pathToFileURL: gh } = Gd, dP = Boolean(Yd && mn), mP = Boolean(yn && Jd && Qd && Xd);
class yP {
  constructor(e, t, s, i) {
    this.stringify = e, this.mapOpts = s.map || {}, this.root = t, this.opts = s, this.css = i;
  }
  isMap() {
    return typeof this.opts.map != "undefined" ? !!this.opts.map : this.previous().length > 0;
  }
  previous() {
    if (!this.previousMaps)
      if (this.previousMaps = [], this.root)
        this.root.walk((e) => {
          if (e.source && e.source.input.map) {
            let t = e.source.input.map;
            this.previousMaps.includes(t) || this.previousMaps.push(t);
          }
        });
      else {
        let e = new Pa(this.css, this.opts);
        e.map && this.previousMaps.push(e.map);
      }
    return this.previousMaps;
  }
  isInline() {
    if (typeof this.mapOpts.inline != "undefined")
      return this.mapOpts.inline;
    let e = this.mapOpts.annotation;
    return typeof e != "undefined" && e !== !0 ? !1 : this.previous().length ? this.previous().some((t) => t.inline) : !0;
  }
  isSourcesContent() {
    return typeof this.mapOpts.sourcesContent != "undefined" ? this.mapOpts.sourcesContent : this.previous().length ? this.previous().some((e) => e.withContent()) : !0;
  }
  clearAnnotation() {
    if (this.mapOpts.annotation !== !1)
      if (this.root) {
        let e;
        for (let t = this.root.nodes.length - 1; t >= 0; t--)
          e = this.root.nodes[t], e.type === "comment" && e.text.indexOf("# sourceMappingURL=") === 0 && this.root.removeChild(t);
      } else
        this.css && (this.css = this.css.replace(/(\n)?\/\*#[\S\s]*?\*\/$/gm, ""));
  }
  setSourcesContent() {
    let e = {};
    if (this.root)
      this.root.walk((t) => {
        if (t.source) {
          let s = t.source.input.from;
          s && !e[s] && (e[s] = !0, this.map.setSourceContent(
            this.toUrl(this.path(s)),
            t.source.input.css
          ));
        }
      });
    else if (this.css) {
      let t = this.opts.from ? this.toUrl(this.path(this.opts.from)) : "<no source>";
      this.map.setSourceContent(t, this.css);
    }
  }
  applyPrevMaps() {
    for (let e of this.previous()) {
      let t = this.toUrl(this.path(e.file)), s = e.root || yn(e.file), i;
      this.mapOpts.sourcesContent === !1 ? (i = new Yd(e.text), i.sourcesContent && (i.sourcesContent = i.sourcesContent.map(() => null))) : i = e.consumer(), this.map.applySourceMap(i, t, this.toUrl(this.path(s)));
    }
  }
  isAnnotation() {
    return this.isInline() ? !0 : typeof this.mapOpts.annotation != "undefined" ? this.mapOpts.annotation : this.previous().length ? this.previous().some((e) => e.annotation) : !0;
  }
  toBase64(e) {
    return F ? F.from(e).toString("base64") : window.btoa(unescape(encodeURIComponent(e)));
  }
  addAnnotation() {
    let e;
    this.isInline() ? e = "data:application/json;base64," + this.toBase64(this.map.toString()) : typeof this.mapOpts.annotation == "string" ? e = this.mapOpts.annotation : typeof this.mapOpts.annotation == "function" ? e = this.mapOpts.annotation(this.opts.to, this.root) : e = this.outputFile() + ".map";
    let t = `
`;
    this.css.includes(`\r
`) && (t = `\r
`), this.css += t + "/*# sourceMappingURL=" + e + " */";
  }
  outputFile() {
    return this.opts.to ? this.path(this.opts.to) : this.opts.from ? this.path(this.opts.from) : "to.css";
  }
  generateMap() {
    if (this.root)
      this.generateString();
    else if (this.previous().length === 1) {
      let e = this.previous()[0].consumer();
      e.file = this.outputFile(), this.map = mn.fromSourceMap(e);
    } else
      this.map = new mn({ file: this.outputFile() }), this.map.addMapping({
        source: this.opts.from ? this.toUrl(this.path(this.opts.from)) : "<no source>",
        generated: { line: 1, column: 0 },
        original: { line: 1, column: 0 }
      });
    return this.isSourcesContent() && this.setSourcesContent(), this.root && this.previous().length > 0 && this.applyPrevMaps(), this.isAnnotation() && this.addAnnotation(), this.isInline() ? [this.css] : [this.css, this.map];
  }
  path(e) {
    if (e.indexOf("<") === 0 || /^\w+:\/\//.test(e) || this.mapOpts.absolute)
      return e;
    let t = this.opts.to ? yn(this.opts.to) : ".";
    return typeof this.mapOpts.annotation == "string" && (t = yn(Jd(t, this.mapOpts.annotation))), e = Qd(t, e), e;
  }
  toUrl(e) {
    return Xd === "\\" && (e = e.replace(/\\/g, "/")), encodeURI(e).replace(/[#?]/g, encodeURIComponent);
  }
  sourcePath(e) {
    if (this.mapOpts.from)
      return this.toUrl(this.mapOpts.from);
    if (this.mapOpts.absolute) {
      if (gh)
        return gh(e.source.input.from).toString();
      throw new Error(
        "`map.absolute` option is not available in this PostCSS build"
      );
    } else
      return this.toUrl(this.path(e.source.input.from));
  }
  generateString() {
    this.css = "", this.map = new mn({ file: this.outputFile() });
    let e = 1, t = 1, s = "<no source>", i = {
      source: "",
      generated: { line: 0, column: 0 },
      original: { line: 0, column: 0 }
    }, n, a;
    this.stringify(this.root, (o, l, u) => {
      if (this.css += o, l && u !== "end" && (i.generated.line = e, i.generated.column = t - 1, l.source && l.source.start ? (i.source = this.sourcePath(l), i.original.line = l.source.start.line, i.original.column = l.source.start.column - 1, this.map.addMapping(i)) : (i.source = s, i.original.line = 1, i.original.column = 0, this.map.addMapping(i))), n = o.match(/\n/g), n ? (e += n.length, a = o.lastIndexOf(`
`), t = o.length - a) : t += o.length, l && u !== "start") {
        let c = l.parent || { raws: {} };
        (l.type !== "decl" || l !== c.last || c.raws.semicolon) && (l.source && l.source.end ? (i.source = this.sourcePath(l), i.original.line = l.source.end.line, i.original.column = l.source.end.column - 1, i.generated.line = e, i.generated.column = t - 2, this.map.addMapping(i)) : (i.source = s, i.original.line = 1, i.original.column = 0, i.generated.line = e, i.generated.column = t - 1, this.map.addMapping(i)));
      }
    });
  }
  generate() {
    if (this.clearAnnotation(), mP && dP && this.isMap())
      return this.generateMap();
    {
      let e = "";
      return this.stringify(this.root, (t) => {
        e += t;
      }), [e];
    }
  }
}
var Zd = yP;
class tl extends Sa {
  constructor(e) {
    super(e), this.type = "comment";
  }
}
var Ls = tl;
tl.default = tl;
let { isClean: em, my: tm } = Su, rm, Au, _u;
function sm(r) {
  return r.map((e) => (e.nodes && (e.nodes = sm(e.nodes)), delete e.source, e));
}
function im(r) {
  if (r[em] = !1, r.proxyOf.nodes)
    for (let e of r.proxyOf.nodes)
      im(e);
}
class zt extends Sa {
  push(e) {
    return e.parent = this, this.proxyOf.nodes.push(e), this;
  }
  each(e) {
    if (!this.proxyOf.nodes)
      return;
    let t = this.getIterator(), s, i;
    for (; this.indexes[t] < this.proxyOf.nodes.length && (s = this.indexes[t], i = e(this.proxyOf.nodes[s], s), i !== !1); )
      this.indexes[t] += 1;
    return delete this.indexes[t], i;
  }
  walk(e) {
    return this.each((t, s) => {
      let i;
      try {
        i = e(t, s);
      } catch (n) {
        throw t.addToError(n);
      }
      return i !== !1 && t.walk && (i = t.walk(e)), i;
    });
  }
  walkDecls(e, t) {
    return t ? e instanceof RegExp ? this.walk((s, i) => {
      if (s.type === "decl" && e.test(s.prop))
        return t(s, i);
    }) : this.walk((s, i) => {
      if (s.type === "decl" && s.prop === e)
        return t(s, i);
    }) : (t = e, this.walk((s, i) => {
      if (s.type === "decl")
        return t(s, i);
    }));
  }
  walkRules(e, t) {
    return t ? e instanceof RegExp ? this.walk((s, i) => {
      if (s.type === "rule" && e.test(s.selector))
        return t(s, i);
    }) : this.walk((s, i) => {
      if (s.type === "rule" && s.selector === e)
        return t(s, i);
    }) : (t = e, this.walk((s, i) => {
      if (s.type === "rule")
        return t(s, i);
    }));
  }
  walkAtRules(e, t) {
    return t ? e instanceof RegExp ? this.walk((s, i) => {
      if (s.type === "atrule" && e.test(s.name))
        return t(s, i);
    }) : this.walk((s, i) => {
      if (s.type === "atrule" && s.name === e)
        return t(s, i);
    }) : (t = e, this.walk((s, i) => {
      if (s.type === "atrule")
        return t(s, i);
    }));
  }
  walkComments(e) {
    return this.walk((t, s) => {
      if (t.type === "comment")
        return e(t, s);
    });
  }
  append(...e) {
    for (let t of e) {
      let s = this.normalize(t, this.last);
      for (let i of s)
        this.proxyOf.nodes.push(i);
    }
    return this.markDirty(), this;
  }
  prepend(...e) {
    e = e.reverse();
    for (let t of e) {
      let s = this.normalize(t, this.first, "prepend").reverse();
      for (let i of s)
        this.proxyOf.nodes.unshift(i);
      for (let i in this.indexes)
        this.indexes[i] = this.indexes[i] + s.length;
    }
    return this.markDirty(), this;
  }
  cleanRaws(e) {
    if (super.cleanRaws(e), this.nodes)
      for (let t of this.nodes)
        t.cleanRaws(e);
  }
  insertBefore(e, t) {
    e = this.index(e);
    let s = e === 0 ? "prepend" : !1, i = this.normalize(t, this.proxyOf.nodes[e], s).reverse();
    for (let a of i)
      this.proxyOf.nodes.splice(e, 0, a);
    let n;
    for (let a in this.indexes)
      n = this.indexes[a], e <= n && (this.indexes[a] = n + i.length);
    return this.markDirty(), this;
  }
  insertAfter(e, t) {
    e = this.index(e);
    let s = this.normalize(t, this.proxyOf.nodes[e]).reverse();
    for (let n of s)
      this.proxyOf.nodes.splice(e + 1, 0, n);
    let i;
    for (let n in this.indexes)
      i = this.indexes[n], e < i && (this.indexes[n] = i + s.length);
    return this.markDirty(), this;
  }
  removeChild(e) {
    e = this.index(e), this.proxyOf.nodes[e].parent = void 0, this.proxyOf.nodes.splice(e, 1);
    let t;
    for (let s in this.indexes)
      t = this.indexes[s], t >= e && (this.indexes[s] = t - 1);
    return this.markDirty(), this;
  }
  removeAll() {
    for (let e of this.proxyOf.nodes)
      e.parent = void 0;
    return this.proxyOf.nodes = [], this.markDirty(), this;
  }
  replaceValues(e, t, s) {
    return s || (s = t, t = {}), this.walkDecls((i) => {
      t.props && !t.props.includes(i.prop) || t.fast && !i.value.includes(t.fast) || (i.value = i.value.replace(e, s));
    }), this.markDirty(), this;
  }
  every(e) {
    return this.nodes.every(e);
  }
  some(e) {
    return this.nodes.some(e);
  }
  index(e) {
    return typeof e == "number" ? e : (e.proxyOf && (e = e.proxyOf), this.proxyOf.nodes.indexOf(e));
  }
  get first() {
    if (!!this.proxyOf.nodes)
      return this.proxyOf.nodes[0];
  }
  get last() {
    if (!!this.proxyOf.nodes)
      return this.proxyOf.nodes[this.proxyOf.nodes.length - 1];
  }
  normalize(e, t) {
    if (typeof e == "string")
      e = sm(rm(e).nodes);
    else if (Array.isArray(e)) {
      e = e.slice(0);
      for (let i of e)
        i.parent && i.parent.removeChild(i, "ignore");
    } else if (e.type === "root" && this.type !== "document") {
      e = e.nodes.slice(0);
      for (let i of e)
        i.parent && i.parent.removeChild(i, "ignore");
    } else if (e.type)
      e = [e];
    else if (e.prop) {
      if (typeof e.value == "undefined")
        throw new Error("Value field is missed in node creation");
      typeof e.value != "string" && (e.value = String(e.value)), e = [new ks(e)];
    } else if (e.selector)
      e = [new Au(e)];
    else if (e.name)
      e = [new _u(e)];
    else if (e.text)
      e = [new Ls(e)];
    else
      throw new Error("Unknown node type in node creation");
    return e.map((i) => (i[tm] || zt.rebuild(i), i = i.proxyOf, i.parent && i.parent.removeChild(i), i[em] && im(i), typeof i.raws.before == "undefined" && t && typeof t.raws.before != "undefined" && (i.raws.before = t.raws.before.replace(/\S/g, "")), i.parent = this, i));
  }
  getProxyProcessor() {
    return {
      set(e, t, s) {
        return e[t] === s || (e[t] = s, (t === "name" || t === "params" || t === "selector") && e.markDirty()), !0;
      },
      get(e, t) {
        return t === "proxyOf" ? e : e[t] ? t === "each" || typeof t == "string" && t.startsWith("walk") ? (...s) => e[t](
          ...s.map((i) => typeof i == "function" ? (n, a) => i(n.toProxy(), a) : i)
        ) : t === "every" || t === "some" ? (s) => e[t](
          (i, ...n) => s(i.toProxy(), ...n)
        ) : t === "root" ? () => e.root().toProxy() : t === "nodes" ? e.nodes.map((s) => s.toProxy()) : t === "first" || t === "last" ? e[t].toProxy() : e[t] : e[t];
      }
    };
  }
  getIterator() {
    this.lastEach || (this.lastEach = 0), this.indexes || (this.indexes = {}), this.lastEach += 1;
    let e = this.lastEach;
    return this.indexes[e] = 0, e;
  }
}
zt.registerParse = (r) => {
  rm = r;
};
zt.registerRule = (r) => {
  Au = r;
};
zt.registerAtRule = (r) => {
  _u = r;
};
var ur = zt;
zt.default = zt;
zt.rebuild = (r) => {
  r.type === "atrule" ? Object.setPrototypeOf(r, _u.prototype) : r.type === "rule" ? Object.setPrototypeOf(r, Au.prototype) : r.type === "decl" ? Object.setPrototypeOf(r, ks.prototype) : r.type === "comment" && Object.setPrototypeOf(r, Ls.prototype), r[tm] = !0, r.nodes && r.nodes.forEach((e) => {
    zt.rebuild(e);
  });
};
let nm, am;
class _i extends ur {
  constructor(e) {
    super(Ht({ type: "document" }, e)), this.nodes || (this.nodes = []);
  }
  toResult(e = {}) {
    return new nm(new am(), this, e).stringify();
  }
}
_i.registerLazyResult = (r) => {
  nm = r;
};
_i.registerProcessor = (r) => {
  am = r;
};
var Ea = _i;
_i.default = _i;
let vh = {};
var om = function(e) {
  vh[e] || (vh[e] = !0, typeof console != "undefined" && console.warn && console.warn(e));
};
class rl {
  constructor(e, t = {}) {
    if (this.type = "warning", this.text = e, t.node && t.node.source) {
      let s = t.node.rangeBy(t);
      this.line = s.start.line, this.column = s.start.column, this.endLine = s.end.line, this.endColumn = s.end.column;
    }
    for (let s in t)
      this[s] = t[s];
  }
  toString() {
    return this.node ? this.node.error(this.text, {
      plugin: this.plugin,
      index: this.index,
      word: this.word
    }).message : this.plugin ? this.plugin + ": " + this.text : this.text;
  }
}
var lm = rl;
rl.default = rl;
class sl {
  constructor(e, t, s) {
    this.processor = e, this.messages = [], this.root = t, this.opts = s, this.css = void 0, this.map = void 0;
  }
  toString() {
    return this.css;
  }
  warn(e, t = {}) {
    t.plugin || this.lastPlugin && this.lastPlugin.postcssPlugin && (t.plugin = this.lastPlugin.postcssPlugin);
    let s = new lm(e, t);
    return this.messages.push(s), s;
  }
  warnings() {
    return this.messages.filter((e) => e.type === "warning");
  }
  get content() {
    return this.css;
  }
}
var Kn = sl;
sl.default = sl;
class Gn extends ur {
  constructor(e) {
    super(e), this.type = "atrule";
  }
  append(...e) {
    return this.proxyOf.nodes || (this.nodes = []), super.append(...e);
  }
  prepend(...e) {
    return this.proxyOf.nodes || (this.nodes = []), super.prepend(...e);
  }
}
var Ta = Gn;
Gn.default = Gn;
ur.registerAtRule(Gn);
let um, cm;
class Ci extends ur {
  constructor(e) {
    super(e), this.type = "root", this.nodes || (this.nodes = []);
  }
  removeChild(e, t) {
    let s = this.index(e);
    return !t && s === 0 && this.nodes.length > 1 && (this.nodes[1].raws.before = this.nodes[s].raws.before), super.removeChild(e);
  }
  normalize(e, t, s) {
    let i = super.normalize(e);
    if (t) {
      if (s === "prepend")
        this.nodes.length > 1 ? t.raws.before = this.nodes[1].raws.before : delete t.raws.before;
      else if (this.first !== t)
        for (let n of i)
          n.raws.before = t.raws.before;
    }
    return i;
  }
  toResult(e = {}) {
    return new um(new cm(), this, e).stringify();
  }
}
Ci.registerLazyResult = (r) => {
  um = r;
};
Ci.registerProcessor = (r) => {
  cm = r;
};
var Ds = Ci;
Ci.default = Ci;
let Ii = {
  split(r, e, t) {
    let s = [], i = "", n = !1, a = 0, o = !1, l = !1;
    for (let u of r)
      l ? l = !1 : u === "\\" ? l = !0 : o ? u === o && (o = !1) : u === '"' || u === "'" ? o = u : u === "(" ? a += 1 : u === ")" ? a > 0 && (a -= 1) : a === 0 && e.includes(u) && (n = !0), n ? (i !== "" && s.push(i.trim()), i = "", n = !1) : i += u;
    return (t || i !== "") && s.push(i.trim()), s;
  },
  space(r) {
    let e = [" ", `
`, "	"];
    return Ii.split(r, e);
  },
  comma(r) {
    return Ii.split(r, [","], !0);
  }
};
var hm = Ii;
Ii.default = Ii;
class Yn extends ur {
  constructor(e) {
    super(e), this.type = "rule", this.nodes || (this.nodes = []);
  }
  get selectors() {
    return hm.comma(this.selector);
  }
  set selectors(e) {
    let t = this.selector ? this.selector.match(/,\s*/) : null, s = t ? t[0] : "," + this.raw("between", "beforeOpen");
    this.selector = e.join(s);
  }
}
var Ni = Yn;
Yn.default = Yn;
ur.registerRule(Yn);
class gP {
  constructor(e) {
    this.input = e, this.root = new Ds(), this.current = this.root, this.spaces = "", this.semicolon = !1, this.customProperty = !1, this.createTokenizer(), this.root.source = { input: e, start: { offset: 0, line: 1, column: 1 } };
  }
  createTokenizer() {
    this.tokenizer = Rd(this.input);
  }
  parse() {
    let e;
    for (; !this.tokenizer.endOfFile(); )
      switch (e = this.tokenizer.nextToken(), e[0]) {
        case "space":
          this.spaces += e[1];
          break;
        case ";":
          this.freeSemicolon(e);
          break;
        case "}":
          this.end(e);
          break;
        case "comment":
          this.comment(e);
          break;
        case "at-word":
          this.atrule(e);
          break;
        case "{":
          this.emptyRule(e);
          break;
        default:
          this.other(e);
          break;
      }
    this.endFile();
  }
  comment(e) {
    let t = new Ls();
    this.init(t, e[2]), t.source.end = this.getPosition(e[3] || e[2]);
    let s = e[1].slice(2, -2);
    if (/^\s*$/.test(s))
      t.text = "", t.raws.left = s, t.raws.right = "";
    else {
      let i = s.match(/^(\s*)([^]*\S)(\s*)$/);
      t.text = i[2], t.raws.left = i[1], t.raws.right = i[3];
    }
  }
  emptyRule(e) {
    let t = new Ni();
    this.init(t, e[2]), t.selector = "", t.raws.between = "", this.current = t;
  }
  other(e) {
    let t = !1, s = null, i = !1, n = null, a = [], o = e[1].startsWith("--"), l = [], u = e;
    for (; u; ) {
      if (s = u[0], l.push(u), s === "(" || s === "[")
        n || (n = u), a.push(s === "(" ? ")" : "]");
      else if (o && i && s === "{")
        n || (n = u), a.push("}");
      else if (a.length === 0)
        if (s === ";")
          if (i) {
            this.decl(l, o);
            return;
          } else
            break;
        else if (s === "{") {
          this.rule(l);
          return;
        } else if (s === "}") {
          this.tokenizer.back(l.pop()), t = !0;
          break;
        } else
          s === ":" && (i = !0);
      else
        s === a[a.length - 1] && (a.pop(), a.length === 0 && (n = null));
      u = this.tokenizer.nextToken();
    }
    if (this.tokenizer.endOfFile() && (t = !0), a.length > 0 && this.unclosedBracket(n), t && i) {
      for (; l.length && (u = l[l.length - 1][0], !(u !== "space" && u !== "comment")); )
        this.tokenizer.back(l.pop());
      this.decl(l, o);
    } else
      this.unknownWord(l);
  }
  rule(e) {
    e.pop();
    let t = new Ni();
    this.init(t, e[0][2]), t.raws.between = this.spacesAndCommentsFromEnd(e), this.raw(t, "selector", e), this.current = t;
  }
  decl(e, t) {
    let s = new ks();
    this.init(s, e[0][2]);
    let i = e[e.length - 1];
    for (i[0] === ";" && (this.semicolon = !0, e.pop()), s.source.end = this.getPosition(i[3] || i[2]); e[0][0] !== "word"; )
      e.length === 1 && this.unknownWord(e), s.raws.before += e.shift()[1];
    for (s.source.start = this.getPosition(e[0][2]), s.prop = ""; e.length; ) {
      let l = e[0][0];
      if (l === ":" || l === "space" || l === "comment")
        break;
      s.prop += e.shift()[1];
    }
    s.raws.between = "";
    let n;
    for (; e.length; )
      if (n = e.shift(), n[0] === ":") {
        s.raws.between += n[1];
        break;
      } else
        n[0] === "word" && /\w/.test(n[1]) && this.unknownWord([n]), s.raws.between += n[1];
    (s.prop[0] === "_" || s.prop[0] === "*") && (s.raws.before += s.prop[0], s.prop = s.prop.slice(1));
    let a = this.spacesAndCommentsFromStart(e);
    this.precheckMissedSemicolon(e);
    for (let l = e.length - 1; l >= 0; l--) {
      if (n = e[l], n[1].toLowerCase() === "!important") {
        s.important = !0;
        let u = this.stringFrom(e, l);
        u = this.spacesFromEnd(e) + u, u !== " !important" && (s.raws.important = u);
        break;
      } else if (n[1].toLowerCase() === "important") {
        let u = e.slice(0), c = "";
        for (let h = l; h > 0; h--) {
          let f = u[h][0];
          if (c.trim().indexOf("!") === 0 && f !== "space")
            break;
          c = u.pop()[1] + c;
        }
        c.trim().indexOf("!") === 0 && (s.important = !0, s.raws.important = c, e = u);
      }
      if (n[0] !== "space" && n[0] !== "comment")
        break;
    }
    let o = e.some((l) => l[0] !== "space" && l[0] !== "comment");
    this.raw(s, "value", e), o ? s.raws.between += a : s.value = a + s.value, s.value.includes(":") && !t && this.checkMissedSemicolon(e);
  }
  atrule(e) {
    let t = new Ta();
    t.name = e[1].slice(1), t.name === "" && this.unnamedAtrule(t, e), this.init(t, e[2]);
    let s, i, n, a = !1, o = !1, l = [], u = [];
    for (; !this.tokenizer.endOfFile(); ) {
      if (e = this.tokenizer.nextToken(), s = e[0], s === "(" || s === "[" ? u.push(s === "(" ? ")" : "]") : s === "{" && u.length > 0 ? u.push("}") : s === u[u.length - 1] && u.pop(), u.length === 0)
        if (s === ";") {
          t.source.end = this.getPosition(e[2]), this.semicolon = !0;
          break;
        } else if (s === "{") {
          o = !0;
          break;
        } else if (s === "}") {
          if (l.length > 0) {
            for (n = l.length - 1, i = l[n]; i && i[0] === "space"; )
              i = l[--n];
            i && (t.source.end = this.getPosition(i[3] || i[2]));
          }
          this.end(e);
          break;
        } else
          l.push(e);
      else
        l.push(e);
      if (this.tokenizer.endOfFile()) {
        a = !0;
        break;
      }
    }
    t.raws.between = this.spacesAndCommentsFromEnd(l), l.length ? (t.raws.afterName = this.spacesAndCommentsFromStart(l), this.raw(t, "params", l), a && (e = l[l.length - 1], t.source.end = this.getPosition(e[3] || e[2]), this.spaces = t.raws.between, t.raws.between = "")) : (t.raws.afterName = "", t.params = ""), o && (t.nodes = [], this.current = t);
  }
  end(e) {
    this.current.nodes && this.current.nodes.length && (this.current.raws.semicolon = this.semicolon), this.semicolon = !1, this.current.raws.after = (this.current.raws.after || "") + this.spaces, this.spaces = "", this.current.parent ? (this.current.source.end = this.getPosition(e[2]), this.current = this.current.parent) : this.unexpectedClose(e);
  }
  endFile() {
    this.current.parent && this.unclosedBlock(), this.current.nodes && this.current.nodes.length && (this.current.raws.semicolon = this.semicolon), this.current.raws.after = (this.current.raws.after || "") + this.spaces;
  }
  freeSemicolon(e) {
    if (this.spaces += e[1], this.current.nodes) {
      let t = this.current.nodes[this.current.nodes.length - 1];
      t && t.type === "rule" && !t.raws.ownSemicolon && (t.raws.ownSemicolon = this.spaces, this.spaces = "");
    }
  }
  getPosition(e) {
    let t = this.input.fromOffset(e);
    return {
      offset: e,
      line: t.line,
      column: t.col
    };
  }
  init(e, t) {
    this.current.push(e), e.source = {
      start: this.getPosition(t),
      input: this.input
    }, e.raws.before = this.spaces, this.spaces = "", e.type !== "comment" && (this.semicolon = !1);
  }
  raw(e, t, s) {
    let i, n, a = s.length, o = "", l = !0, u, c, h = /^([#.|])?(\w)+/i;
    for (let f = 0; f < a; f += 1) {
      if (i = s[f], n = i[0], n === "comment" && e.type === "rule") {
        c = s[f - 1], u = s[f + 1], c[0] !== "space" && u[0] !== "space" && h.test(c[1]) && h.test(u[1]) ? o += i[1] : l = !1;
        continue;
      }
      n === "comment" || n === "space" && f === a - 1 ? l = !1 : o += i[1];
    }
    if (!l) {
      let f = s.reduce((p, x) => p + x[1], "");
      e.raws[t] = { value: o, raw: f };
    }
    e[t] = o;
  }
  spacesAndCommentsFromEnd(e) {
    let t, s = "";
    for (; e.length && (t = e[e.length - 1][0], !(t !== "space" && t !== "comment")); )
      s = e.pop()[1] + s;
    return s;
  }
  spacesAndCommentsFromStart(e) {
    let t, s = "";
    for (; e.length && (t = e[0][0], !(t !== "space" && t !== "comment")); )
      s += e.shift()[1];
    return s;
  }
  spacesFromEnd(e) {
    let t, s = "";
    for (; e.length && (t = e[e.length - 1][0], t === "space"); )
      s = e.pop()[1] + s;
    return s;
  }
  stringFrom(e, t) {
    let s = "";
    for (let i = t; i < e.length; i++)
      s += e[i][1];
    return e.splice(t, e.length - t), s;
  }
  colon(e) {
    let t = 0, s, i, n;
    for (let [a, o] of e.entries()) {
      if (s = o, i = s[0], i === "(" && (t += 1), i === ")" && (t -= 1), t === 0 && i === ":")
        if (!n)
          this.doubleColon(s);
        else {
          if (n[0] === "word" && n[1] === "progid")
            continue;
          return a;
        }
      n = s;
    }
    return !1;
  }
  unclosedBracket(e) {
    throw this.input.error(
      "Unclosed bracket",
      { offset: e[2] },
      { offset: e[2] + 1 }
    );
  }
  unknownWord(e) {
    throw this.input.error(
      "Unknown word",
      { offset: e[0][2] },
      { offset: e[0][2] + e[0][1].length }
    );
  }
  unexpectedClose(e) {
    throw this.input.error(
      "Unexpected }",
      { offset: e[2] },
      { offset: e[2] + 1 }
    );
  }
  unclosedBlock() {
    let e = this.current.source.start;
    throw this.input.error("Unclosed block", e.line, e.column);
  }
  doubleColon(e) {
    throw this.input.error(
      "Double colon",
      { offset: e[2] },
      { offset: e[2] + e[1].length }
    );
  }
  unnamedAtrule(e, t) {
    throw this.input.error(
      "At-rule without name",
      { offset: t[2] },
      { offset: t[2] + t[1].length }
    );
  }
  precheckMissedSemicolon() {
  }
  checkMissedSemicolon(e) {
    let t = this.colon(e);
    if (t === !1)
      return;
    let s = 0, i;
    for (let n = t - 1; n >= 0 && (i = e[n], !(i[0] !== "space" && (s += 1, s === 2))); n--)
      ;
    throw this.input.error(
      "Missed semicolon",
      i[0] === "word" ? i[3] + 1 : i[2]
    );
  }
}
var vP = gP;
function Jn(r, e) {
  let t = new Pa(r, e), s = new vP(t);
  try {
    s.parse();
  } catch (i) {
    throw i.name === "CssSyntaxError" && e && e.from && (/\.scss$/i.test(e.from) ? i.message += `
You tried to parse SCSS with the standard CSS parser; try again with the postcss-scss parser` : /\.sass/i.test(e.from) ? i.message += `
You tried to parse Sass with the standard CSS parser; try again with the postcss-sass parser` : /\.less$/i.test(e.from) && (i.message += `
You tried to parse Less with the standard CSS parser; try again with the postcss-less parser`)), i;
  }
  return s.root;
}
var Cu = Jn;
Jn.default = Jn;
ur.registerParse(Jn);
let { isClean: Rt, my: bP } = Su;
const xP = {
  document: "Document",
  root: "Root",
  atrule: "AtRule",
  rule: "Rule",
  decl: "Declaration",
  comment: "Comment"
}, SP = {
  postcssPlugin: !0,
  prepare: !0,
  Once: !0,
  Document: !0,
  Root: !0,
  Declaration: !0,
  Rule: !0,
  AtRule: !0,
  Comment: !0,
  DeclarationExit: !0,
  RuleExit: !0,
  AtRuleExit: !0,
  CommentExit: !0,
  RootExit: !0,
  DocumentExit: !0,
  OnceExit: !0
}, wP = {
  postcssPlugin: !0,
  prepare: !0,
  Once: !0
}, _s = 0;
function Ws(r) {
  return typeof r == "object" && typeof r.then == "function";
}
function fm(r) {
  let e = !1, t = xP[r.type];
  return r.type === "decl" ? e = r.prop.toLowerCase() : r.type === "atrule" && (e = r.name.toLowerCase()), e && r.append ? [
    t,
    t + "-" + e,
    _s,
    t + "Exit",
    t + "Exit-" + e
  ] : e ? [t, t + "-" + e, t + "Exit", t + "Exit-" + e] : r.append ? [t, _s, t + "Exit"] : [t, t + "Exit"];
}
function bh(r) {
  let e;
  return r.type === "document" ? e = ["Document", _s, "DocumentExit"] : r.type === "root" ? e = ["Root", _s, "RootExit"] : e = fm(r), {
    node: r,
    events: e,
    eventIndex: 0,
    visitors: [],
    visitorIndex: 0,
    iterator: 0
  };
}
function il(r) {
  return r[Rt] = !1, r.nodes && r.nodes.forEach((e) => il(e)), r;
}
let nl = {};
class _r {
  constructor(e, t, s) {
    this.stringified = !1, this.processed = !1;
    let i;
    if (typeof t == "object" && t !== null && (t.type === "root" || t.type === "document"))
      i = il(t);
    else if (t instanceof _r || t instanceof Kn)
      i = il(t.root), t.map && (typeof s.map == "undefined" && (s.map = {}), s.map.inline || (s.map.inline = !1), s.map.prev = t.map);
    else {
      let n = Cu;
      s.syntax && (n = s.syntax.parse), s.parser && (n = s.parser), n.parse && (n = n.parse);
      try {
        i = n(t, s);
      } catch (a) {
        this.processed = !0, this.error = a;
      }
      i && !i[bP] && ur.rebuild(i);
    }
    this.result = new Kn(e, i, s), this.helpers = Fs(Ht({}, nl), { result: this.result, postcss: nl }), this.plugins = this.processor.plugins.map((n) => typeof n == "object" && n.prepare ? Ht(Ht({}, n), n.prepare(this.result)) : n);
  }
  get [Symbol.toStringTag]() {
    return "LazyResult";
  }
  get processor() {
    return this.result.processor;
  }
  get opts() {
    return this.result.opts;
  }
  get css() {
    return this.stringify().css;
  }
  get content() {
    return this.stringify().content;
  }
  get map() {
    return this.stringify().map;
  }
  get root() {
    return this.sync().root;
  }
  get messages() {
    return this.sync().messages;
  }
  warnings() {
    return this.sync().warnings();
  }
  toString() {
    return this.css;
  }
  then(e, t) {
    return "from" in this.opts || om(
      "Without `from` option PostCSS could generate wrong source map and will not find Browserslist config. Set it to CSS file path or to `undefined` to prevent this warning."
    ), this.async().then(e, t);
  }
  catch(e) {
    return this.async().catch(e);
  }
  finally(e) {
    return this.async().then(e, e);
  }
  async() {
    return this.error ? Promise.reject(this.error) : this.processed ? Promise.resolve(this.result) : (this.processing || (this.processing = this.runAsync()), this.processing);
  }
  sync() {
    if (this.error)
      throw this.error;
    if (this.processed)
      return this.result;
    if (this.processed = !0, this.processing)
      throw this.getAsyncError();
    for (let e of this.plugins) {
      let t = this.runOnRoot(e);
      if (Ws(t))
        throw this.getAsyncError();
    }
    if (this.prepareVisitors(), this.hasListener) {
      let e = this.result.root;
      for (; !e[Rt]; )
        e[Rt] = !0, this.walkSync(e);
      if (this.listeners.OnceExit)
        if (e.type === "document")
          for (let t of e.nodes)
            this.visitSync(this.listeners.OnceExit, t);
        else
          this.visitSync(this.listeners.OnceExit, e);
    }
    return this.result;
  }
  stringify() {
    if (this.error)
      throw this.error;
    if (this.stringified)
      return this.result;
    this.stringified = !0, this.sync();
    let e = this.result.opts, t = xa;
    e.syntax && (t = e.syntax.stringify), e.stringifier && (t = e.stringifier), t.stringify && (t = t.stringify);
    let i = new Zd(t, this.result.root, this.result.opts).generate();
    return this.result.css = i[0], this.result.map = i[1], this.result;
  }
  walkSync(e) {
    e[Rt] = !0;
    let t = fm(e);
    for (let s of t)
      if (s === _s)
        e.nodes && e.each((i) => {
          i[Rt] || this.walkSync(i);
        });
      else {
        let i = this.listeners[s];
        if (i && this.visitSync(i, e.toProxy()))
          return;
      }
  }
  visitSync(e, t) {
    for (let [s, i] of e) {
      this.result.lastPlugin = s;
      let n;
      try {
        n = i(t, this.helpers);
      } catch (a) {
        throw this.handleError(a, t.proxyOf);
      }
      if (t.type !== "root" && t.type !== "document" && !t.parent)
        return !0;
      if (Ws(n))
        throw this.getAsyncError();
    }
  }
  runOnRoot(e) {
    this.result.lastPlugin = e;
    try {
      if (typeof e == "object" && e.Once) {
        if (this.result.root.type === "document") {
          let t = this.result.root.nodes.map(
            (s) => e.Once(s, this.helpers)
          );
          return Ws(t[0]) ? Promise.all(t) : t;
        }
        return e.Once(this.result.root, this.helpers);
      } else if (typeof e == "function")
        return e(this.result.root, this.result);
    } catch (t) {
      throw this.handleError(t);
    }
  }
  getAsyncError() {
    throw new Error("Use process(css).then(cb) to work with async plugins");
  }
  handleError(e, t) {
    let s = this.result.lastPlugin;
    try {
      if (t && t.addToError(e), this.error = e, e.name === "CssSyntaxError" && !e.plugin)
        e.plugin = s.postcssPlugin, e.setMessage();
      else if (s.postcssVersion && {}.NODE_ENV !== "production") {
        let i = s.postcssPlugin, n = s.postcssVersion, a = this.result.processor.version, o = n.split("."), l = a.split(".");
        (o[0] !== l[0] || parseInt(o[1]) > parseInt(l[1])) && console.error(
          "Unknown error from PostCSS plugin. Your current PostCSS version is " + a + ", but " + i + " uses " + n + ". Perhaps this is the source of the error below."
        );
      }
    } catch (i) {
      console && console.error && console.error(i);
    }
    return e;
  }
  runAsync() {
    return Bi(this, null, function* () {
      this.plugin = 0;
      for (let e = 0; e < this.plugins.length; e++) {
        let t = this.plugins[e], s = this.runOnRoot(t);
        if (Ws(s))
          try {
            yield s;
          } catch (i) {
            throw this.handleError(i);
          }
      }
      if (this.prepareVisitors(), this.hasListener) {
        let e = this.result.root;
        for (; !e[Rt]; ) {
          e[Rt] = !0;
          let t = [bh(e)];
          for (; t.length > 0; ) {
            let s = this.visitTick(t);
            if (Ws(s))
              try {
                yield s;
              } catch (i) {
                let n = t[t.length - 1].node;
                throw this.handleError(i, n);
              }
          }
        }
        if (this.listeners.OnceExit)
          for (let [t, s] of this.listeners.OnceExit) {
            this.result.lastPlugin = t;
            try {
              if (e.type === "document") {
                let i = e.nodes.map(
                  (n) => s(n, this.helpers)
                );
                yield Promise.all(i);
              } else
                yield s(e, this.helpers);
            } catch (i) {
              throw this.handleError(i);
            }
          }
      }
      return this.processed = !0, this.stringify();
    });
  }
  prepareVisitors() {
    this.listeners = {};
    let e = (t, s, i) => {
      this.listeners[s] || (this.listeners[s] = []), this.listeners[s].push([t, i]);
    };
    for (let t of this.plugins)
      if (typeof t == "object")
        for (let s in t) {
          if (!SP[s] && /^[A-Z]/.test(s))
            throw new Error(
              `Unknown event ${s} in ${t.postcssPlugin}. Try to update PostCSS (${this.processor.version} now).`
            );
          if (!wP[s])
            if (typeof t[s] == "object")
              for (let i in t[s])
                i === "*" ? e(t, s, t[s][i]) : e(
                  t,
                  s + "-" + i.toLowerCase(),
                  t[s][i]
                );
            else
              typeof t[s] == "function" && e(t, s, t[s]);
        }
    this.hasListener = Object.keys(this.listeners).length > 0;
  }
  visitTick(e) {
    let t = e[e.length - 1], { node: s, visitors: i } = t;
    if (s.type !== "root" && s.type !== "document" && !s.parent) {
      e.pop();
      return;
    }
    if (i.length > 0 && t.visitorIndex < i.length) {
      let [a, o] = i[t.visitorIndex];
      t.visitorIndex += 1, t.visitorIndex === i.length && (t.visitors = [], t.visitorIndex = 0), this.result.lastPlugin = a;
      try {
        return o(s.toProxy(), this.helpers);
      } catch (l) {
        throw this.handleError(l, s);
      }
    }
    if (t.iterator !== 0) {
      let a = t.iterator, o;
      for (; o = s.nodes[s.indexes[a]]; )
        if (s.indexes[a] += 1, !o[Rt]) {
          o[Rt] = !0, e.push(bh(o));
          return;
        }
      t.iterator = 0, delete s.indexes[a];
    }
    let n = t.events;
    for (; t.eventIndex < n.length; ) {
      let a = n[t.eventIndex];
      if (t.eventIndex += 1, a === _s) {
        s.nodes && s.nodes.length && (s[Rt] = !0, t.iterator = s.getIterator());
        return;
      } else if (this.listeners[a]) {
        t.visitors = this.listeners[a];
        return;
      }
    }
    e.pop();
  }
}
_r.registerPostcss = (r) => {
  nl = r;
};
var pm = _r;
_r.default = _r;
Ds.registerLazyResult(_r);
Ea.registerLazyResult(_r);
class al {
  constructor(e, t, s) {
    t = t.toString(), this.stringified = !1, this._processor = e, this._css = t, this._opts = s, this._map = void 0;
    let i, n = xa;
    this.result = new Kn(this._processor, i, this._opts), this.result.css = t;
    let a = this;
    Object.defineProperty(this.result, "root", {
      get() {
        return a.root;
      }
    });
    let o = new Zd(n, i, this._opts, t);
    if (o.isMap()) {
      let [l, u] = o.generate();
      l && (this.result.css = l), u && (this.result.map = u);
    }
  }
  get [Symbol.toStringTag]() {
    return "NoWorkResult";
  }
  get processor() {
    return this.result.processor;
  }
  get opts() {
    return this.result.opts;
  }
  get css() {
    return this.result.css;
  }
  get content() {
    return this.result.css;
  }
  get map() {
    return this.result.map;
  }
  get root() {
    if (this._root)
      return this._root;
    let e, t = Cu;
    try {
      e = t(this._css, this._opts);
    } catch (s) {
      this.error = s;
    }
    return this._root = e, e;
  }
  get messages() {
    return [];
  }
  warnings() {
    return [];
  }
  toString() {
    return this._css;
  }
  then(e, t) {
    return "from" in this._opts || om(
      "Without `from` option PostCSS could generate wrong source map and will not find Browserslist config. Set it to CSS file path or to `undefined` to prevent this warning."
    ), this.async().then(e, t);
  }
  catch(e) {
    return this.async().catch(e);
  }
  finally(e) {
    return this.async().then(e, e);
  }
  async() {
    return this.error ? Promise.reject(this.error) : Promise.resolve(this.result);
  }
  sync() {
    if (this.error)
      throw this.error;
    return this.result;
  }
}
var PP = al;
al.default = al;
class Oi {
  constructor(e = []) {
    this.version = "8.4.4", this.plugins = this.normalize(e);
  }
  use(e) {
    return this.plugins = this.plugins.concat(this.normalize([e])), this;
  }
  process(e, t = {}) {
    return this.plugins.length === 0 && typeof t.parser == "undefined" && typeof t.stringifier == "undefined" && typeof t.syntax == "undefined" ? new PP(this, e, t) : new pm(this, e, t);
  }
  normalize(e) {
    let t = [];
    for (let s of e)
      if (s.postcss === !0 ? s = s() : s.postcss && (s = s.postcss), typeof s == "object" && Array.isArray(s.plugins))
        t = t.concat(s.plugins);
      else if (typeof s == "object" && s.postcssPlugin)
        t.push(s);
      else if (typeof s == "function")
        t.push(s);
      else
        throw typeof s == "object" && (s.parse || s.stringify) ? new Error(
          "PostCSS syntaxes cannot be used as plugins. Instead, please use one of the syntax/parser/stringifier options as outlined in your PostCSS runner documentation."
        ) : new Error(s + " is not a PostCSS plugin");
    return t;
  }
}
var Iu = Oi;
Oi.default = Oi;
Ds.registerProcessor(Oi);
Ea.registerProcessor(Oi);
function ki(r, e) {
  if (Array.isArray(r))
    return r.map((a) => ki(a));
  let i = r, { inputs: t } = i, s = _a(i, ["inputs"]);
  if (t) {
    e = [];
    for (let a of t) {
      let o = Fs(Ht({}, a), { __proto__: Pa.prototype });
      o.map && (o.map = Fs(Ht({}, o.map), {
        __proto__: Kd.prototype
      })), e.push(o);
    }
  }
  if (s.nodes && (s.nodes = r.nodes.map((a) => ki(a, e))), s.source) {
    let n = s.source, { inputId: a } = n, o = _a(n, ["inputId"]);
    s.source = o, a != null && (s.source.input = e[a]);
  }
  if (s.type === "root")
    return new Ds(s);
  if (s.type === "decl")
    return new ks(s);
  if (s.type === "rule")
    return new Ni(s);
  if (s.type === "comment")
    return new Ls(s);
  if (s.type === "atrule")
    return new Ta(s);
  throw new Error("Unknown node type: " + r.type);
}
var EP = ki;
ki.default = ki;
function Ne(...r) {
  return r.length === 1 && Array.isArray(r[0]) && (r = r[0]), new Iu(r);
}
Ne.plugin = function(e, t) {
  console && console.warn && console.warn(
    e + `: postcss.plugin was deprecated. Migration guide:
https://evilmartians.com/chronicles/postcss-8-plugin-migration`
  );
  function s(...n) {
    let a = t(...n);
    return a.postcssPlugin = e, a.postcssVersion = new Iu().version, a;
  }
  let i;
  return Object.defineProperty(s, "postcss", {
    get() {
      return i || (i = s()), i;
    }
  }), s.process = function(n, a, o) {
    return Ne([s(o)]).process(n, a);
  }, s;
};
Ne.stringify = xa;
Ne.parse = Cu;
Ne.fromJSON = EP;
Ne.list = hm;
Ne.comment = (r) => new Ls(r);
Ne.atRule = (r) => new Ta(r);
Ne.decl = (r) => new ks(r);
Ne.rule = (r) => new Ni(r);
Ne.root = (r) => new Ds(r);
Ne.document = (r) => new Ea(r);
Ne.CssSyntaxError = zn;
Ne.Declaration = ks;
Ne.Container = ur;
Ne.Processor = Iu;
Ne.Document = Ea;
Ne.Comment = Ls;
Ne.Warning = lm;
Ne.AtRule = Ta;
Ne.Result = Kn;
Ne.Input = Pa;
Ne.Rule = Ni;
Ne.Root = Ds;
Ne.Node = Sa;
pm.registerPostcss(Ne);
Ne.default = Ne;
var dm = Pe(function(r, e) {
  e.__esModule = !0, e.default = i;
  function t(n) {
    for (var a = n.toLowerCase(), o = "", l = !1, u = 0; u < 6 && a[u] !== void 0; u++) {
      var c = a.charCodeAt(u), h = c >= 97 && c <= 102 || c >= 48 && c <= 57;
      if (l = c === 32, !h)
        break;
      o += a[u];
    }
    if (o.length !== 0) {
      var f = parseInt(o, 16), p = f >= 55296 && f <= 57343;
      return p || f === 0 || f > 1114111 ? ["\uFFFD", o.length + (l ? 1 : 0)] : [String.fromCodePoint(f), o.length + (l ? 1 : 0)];
    }
  }
  var s = /\\/;
  function i(n) {
    var a = s.test(n);
    if (!a)
      return n;
    for (var o = "", l = 0; l < n.length; l++) {
      if (n[l] === "\\") {
        var u = t(n.slice(l + 1, l + 7));
        if (u !== void 0) {
          o += u[0], l += u[1];
          continue;
        }
        if (n[l + 1] === "\\") {
          o += "\\", l++;
          continue;
        }
        n.length === l + 1 && (o += n[l]);
        continue;
      }
      o += n[l];
    }
    return o;
  }
  r.exports = e.default;
}), TP = Pe(function(r, e) {
  e.__esModule = !0, e.default = t;
  function t(s) {
    for (var i = arguments.length, n = new Array(i > 1 ? i - 1 : 0), a = 1; a < i; a++)
      n[a - 1] = arguments[a];
    for (; n.length > 0; ) {
      var o = n.shift();
      if (!s[o])
        return;
      s = s[o];
    }
    return s;
  }
  r.exports = e.default;
}), AP = Pe(function(r, e) {
  e.__esModule = !0, e.default = t;
  function t(s) {
    for (var i = arguments.length, n = new Array(i > 1 ? i - 1 : 0), a = 1; a < i; a++)
      n[a - 1] = arguments[a];
    for (; n.length > 0; ) {
      var o = n.shift();
      s[o] || (s[o] = {}), s = s[o];
    }
  }
  r.exports = e.default;
}), _P = Pe(function(r, e) {
  e.__esModule = !0, e.default = t;
  function t(s) {
    for (var i = "", n = s.indexOf("/*"), a = 0; n >= 0; ) {
      i = i + s.slice(a, n);
      var o = s.indexOf("*/", n + 2);
      if (o < 0)
        return i;
      a = o + 2, n = s.indexOf("/*", a);
    }
    return i = i + s.slice(a), i;
  }
  r.exports = e.default;
}), Ie = Pe(function(r, e) {
  e.__esModule = !0, e.stripComments = e.ensureObject = e.getProp = e.unesc = void 0;
  var t = a(dm);
  e.unesc = t.default;
  var s = a(TP);
  e.getProp = s.default;
  var i = a(AP);
  e.ensureObject = i.default;
  var n = a(_P);
  e.stripComments = n.default;
  function a(o) {
    return o && o.__esModule ? o : { default: o };
  }
}), Ir = Pe(function(r, e) {
  e.__esModule = !0, e.default = void 0;
  function t(a, o) {
    for (var l = 0; l < o.length; l++) {
      var u = o[l];
      u.enumerable = u.enumerable || !1, u.configurable = !0, "value" in u && (u.writable = !0), Object.defineProperty(a, u.key, u);
    }
  }
  function s(a, o, l) {
    return o && t(a.prototype, o), l && t(a, l), a;
  }
  var i = function a(o, l) {
    if (typeof o != "object" || o === null)
      return o;
    var u = new o.constructor();
    for (var c in o)
      if (!!o.hasOwnProperty(c)) {
        var h = o[c], f = typeof h;
        c === "parent" && f === "object" ? l && (u[c] = l) : h instanceof Array ? u[c] = h.map(function(p) {
          return a(p, u);
        }) : u[c] = a(h, u);
      }
    return u;
  }, n = /* @__PURE__ */ function() {
    function a(l) {
      l === void 0 && (l = {}), Object.assign(this, l), this.spaces = this.spaces || {}, this.spaces.before = this.spaces.before || "", this.spaces.after = this.spaces.after || "";
    }
    var o = a.prototype;
    return o.remove = function() {
      return this.parent && this.parent.removeChild(this), this.parent = void 0, this;
    }, o.replaceWith = function() {
      if (this.parent) {
        for (var u in arguments)
          this.parent.insertBefore(this, arguments[u]);
        this.remove();
      }
      return this;
    }, o.next = function() {
      return this.parent.at(this.parent.index(this) + 1);
    }, o.prev = function() {
      return this.parent.at(this.parent.index(this) - 1);
    }, o.clone = function(u) {
      u === void 0 && (u = {});
      var c = i(this);
      for (var h in u)
        c[h] = u[h];
      return c;
    }, o.appendToPropertyAndEscape = function(u, c, h) {
      this.raws || (this.raws = {});
      var f = this[u], p = this.raws[u];
      this[u] = f + c, p || h !== c ? this.raws[u] = (p || f) + h : delete this.raws[u];
    }, o.setPropertyAndEscape = function(u, c, h) {
      this.raws || (this.raws = {}), this[u] = c, this.raws[u] = h;
    }, o.setPropertyWithoutEscape = function(u, c) {
      this[u] = c, this.raws && delete this.raws[u];
    }, o.isAtPosition = function(u, c) {
      if (this.source && this.source.start && this.source.end)
        return !(this.source.start.line > u || this.source.end.line < u || this.source.start.line === u && this.source.start.column > c || this.source.end.line === u && this.source.end.column < c);
    }, o.stringifyProperty = function(u) {
      return this.raws && this.raws[u] || this[u];
    }, o.valueToString = function() {
      return String(this.stringifyProperty("value"));
    }, o.toString = function() {
      return [this.rawSpaceBefore, this.valueToString(), this.rawSpaceAfter].join("");
    }, s(a, [{
      key: "rawSpaceBefore",
      get: function() {
        var u = this.raws && this.raws.spaces && this.raws.spaces.before;
        return u === void 0 && (u = this.spaces && this.spaces.before), u || "";
      },
      set: function(u) {
        (0, Ie.ensureObject)(this, "raws", "spaces"), this.raws.spaces.before = u;
      }
    }, {
      key: "rawSpaceAfter",
      get: function() {
        var u = this.raws && this.raws.spaces && this.raws.spaces.after;
        return u === void 0 && (u = this.spaces.after), u || "";
      },
      set: function(u) {
        (0, Ie.ensureObject)(this, "raws", "spaces"), this.raws.spaces.after = u;
      }
    }]), a;
  }();
  e.default = n, r.exports = e.default;
}), pe = Pe(function(r, e) {
  e.__esModule = !0, e.UNIVERSAL = e.ATTRIBUTE = e.CLASS = e.COMBINATOR = e.COMMENT = e.ID = e.NESTING = e.PSEUDO = e.ROOT = e.SELECTOR = e.STRING = e.TAG = void 0;
  var t = "tag";
  e.TAG = t;
  var s = "string";
  e.STRING = s;
  var i = "selector";
  e.SELECTOR = i;
  var n = "root";
  e.ROOT = n;
  var a = "pseudo";
  e.PSEUDO = a;
  var o = "nesting";
  e.NESTING = o;
  var l = "id";
  e.ID = l;
  var u = "comment";
  e.COMMENT = u;
  var c = "combinator";
  e.COMBINATOR = c;
  var h = "class";
  e.CLASS = h;
  var f = "attribute";
  e.ATTRIBUTE = f;
  var p = "universal";
  e.UNIVERSAL = p;
}), Nu = Pe(function(r, e) {
  e.__esModule = !0, e.default = void 0;
  var t = a(Ir), s = n(pe);
  function i() {
    if (typeof WeakMap != "function")
      return null;
    var d = /* @__PURE__ */ new WeakMap();
    return i = function() {
      return d;
    }, d;
  }
  function n(d) {
    if (d && d.__esModule)
      return d;
    if (d === null || typeof d != "object" && typeof d != "function")
      return { default: d };
    var m = i();
    if (m && m.has(d))
      return m.get(d);
    var y = {}, _ = Object.defineProperty && Object.getOwnPropertyDescriptor;
    for (var T in d)
      if (Object.prototype.hasOwnProperty.call(d, T)) {
        var C = _ ? Object.getOwnPropertyDescriptor(d, T) : null;
        C && (C.get || C.set) ? Object.defineProperty(y, T, C) : y[T] = d[T];
      }
    return y.default = d, m && m.set(d, y), y;
  }
  function a(d) {
    return d && d.__esModule ? d : { default: d };
  }
  function o(d, m) {
    var y;
    if (typeof Symbol == "undefined" || d[Symbol.iterator] == null) {
      if (Array.isArray(d) || (y = l(d)) || m && d && typeof d.length == "number") {
        y && (d = y);
        var _ = 0;
        return function() {
          return _ >= d.length ? { done: !0 } : { done: !1, value: d[_++] };
        };
      }
      throw new TypeError(`Invalid attempt to iterate non-iterable instance.
In order to be iterable, non-array objects must have a [Symbol.iterator]() method.`);
    }
    return y = d[Symbol.iterator](), y.next.bind(y);
  }
  function l(d, m) {
    if (!!d) {
      if (typeof d == "string")
        return u(d, m);
      var y = Object.prototype.toString.call(d).slice(8, -1);
      if (y === "Object" && d.constructor && (y = d.constructor.name), y === "Map" || y === "Set")
        return Array.from(d);
      if (y === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(y))
        return u(d, m);
    }
  }
  function u(d, m) {
    (m == null || m > d.length) && (m = d.length);
    for (var y = 0, _ = new Array(m); y < m; y++)
      _[y] = d[y];
    return _;
  }
  function c(d, m) {
    for (var y = 0; y < m.length; y++) {
      var _ = m[y];
      _.enumerable = _.enumerable || !1, _.configurable = !0, "value" in _ && (_.writable = !0), Object.defineProperty(d, _.key, _);
    }
  }
  function h(d, m, y) {
    return m && c(d.prototype, m), y && c(d, y), d;
  }
  function f(d, m) {
    d.prototype = Object.create(m.prototype), d.prototype.constructor = d, p(d, m);
  }
  function p(d, m) {
    return p = Object.setPrototypeOf || function(_, T) {
      return _.__proto__ = T, _;
    }, p(d, m);
  }
  var x = /* @__PURE__ */ function(d) {
    f(m, d);
    function m(_) {
      var T;
      return T = d.call(this, _) || this, T.nodes || (T.nodes = []), T;
    }
    var y = m.prototype;
    return y.append = function(T) {
      return T.parent = this, this.nodes.push(T), this;
    }, y.prepend = function(T) {
      return T.parent = this, this.nodes.unshift(T), this;
    }, y.at = function(T) {
      return this.nodes[T];
    }, y.index = function(T) {
      return typeof T == "number" ? T : this.nodes.indexOf(T);
    }, y.removeChild = function(T) {
      T = this.index(T), this.at(T).parent = void 0, this.nodes.splice(T, 1);
      var C;
      for (var v in this.indexes)
        C = this.indexes[v], C >= T && (this.indexes[v] = C - 1);
      return this;
    }, y.removeAll = function() {
      for (var T = o(this.nodes), C; !(C = T()).done; ) {
        var v = C.value;
        v.parent = void 0;
      }
      return this.nodes = [], this;
    }, y.empty = function() {
      return this.removeAll();
    }, y.insertAfter = function(T, C) {
      C.parent = this;
      var v = this.index(T);
      this.nodes.splice(v + 1, 0, C), C.parent = this;
      var w;
      for (var N in this.indexes)
        w = this.indexes[N], v <= w && (this.indexes[N] = w + 1);
      return this;
    }, y.insertBefore = function(T, C) {
      C.parent = this;
      var v = this.index(T);
      this.nodes.splice(v, 0, C), C.parent = this;
      var w;
      for (var N in this.indexes)
        w = this.indexes[N], w <= v && (this.indexes[N] = w + 1);
      return this;
    }, y._findChildAtPosition = function(T, C) {
      var v = void 0;
      return this.each(function(w) {
        if (w.atPosition) {
          var N = w.atPosition(T, C);
          if (N)
            return v = N, !1;
        } else if (w.isAtPosition(T, C))
          return v = w, !1;
      }), v;
    }, y.atPosition = function(T, C) {
      if (this.isAtPosition(T, C))
        return this._findChildAtPosition(T, C) || this;
    }, y._inferEndPosition = function() {
      this.last && this.last.source && this.last.source.end && (this.source = this.source || {}, this.source.end = this.source.end || {}, Object.assign(this.source.end, this.last.source.end));
    }, y.each = function(T) {
      this.lastEach || (this.lastEach = 0), this.indexes || (this.indexes = {}), this.lastEach++;
      var C = this.lastEach;
      if (this.indexes[C] = 0, !!this.length) {
        for (var v, w; this.indexes[C] < this.length && (v = this.indexes[C], w = T(this.at(v), v), w !== !1); )
          this.indexes[C] += 1;
        if (delete this.indexes[C], w === !1)
          return !1;
      }
    }, y.walk = function(T) {
      return this.each(function(C, v) {
        var w = T(C, v);
        if (w !== !1 && C.length && (w = C.walk(T)), w === !1)
          return !1;
      });
    }, y.walkAttributes = function(T) {
      var C = this;
      return this.walk(function(v) {
        if (v.type === s.ATTRIBUTE)
          return T.call(C, v);
      });
    }, y.walkClasses = function(T) {
      var C = this;
      return this.walk(function(v) {
        if (v.type === s.CLASS)
          return T.call(C, v);
      });
    }, y.walkCombinators = function(T) {
      var C = this;
      return this.walk(function(v) {
        if (v.type === s.COMBINATOR)
          return T.call(C, v);
      });
    }, y.walkComments = function(T) {
      var C = this;
      return this.walk(function(v) {
        if (v.type === s.COMMENT)
          return T.call(C, v);
      });
    }, y.walkIds = function(T) {
      var C = this;
      return this.walk(function(v) {
        if (v.type === s.ID)
          return T.call(C, v);
      });
    }, y.walkNesting = function(T) {
      var C = this;
      return this.walk(function(v) {
        if (v.type === s.NESTING)
          return T.call(C, v);
      });
    }, y.walkPseudos = function(T) {
      var C = this;
      return this.walk(function(v) {
        if (v.type === s.PSEUDO)
          return T.call(C, v);
      });
    }, y.walkTags = function(T) {
      var C = this;
      return this.walk(function(v) {
        if (v.type === s.TAG)
          return T.call(C, v);
      });
    }, y.walkUniversals = function(T) {
      var C = this;
      return this.walk(function(v) {
        if (v.type === s.UNIVERSAL)
          return T.call(C, v);
      });
    }, y.split = function(T) {
      var C = this, v = [];
      return this.reduce(function(w, N, P) {
        var g = T.call(C, N);
        return v.push(N), g ? (w.push(v), v = []) : P === C.length - 1 && w.push(v), w;
      }, []);
    }, y.map = function(T) {
      return this.nodes.map(T);
    }, y.reduce = function(T, C) {
      return this.nodes.reduce(T, C);
    }, y.every = function(T) {
      return this.nodes.every(T);
    }, y.some = function(T) {
      return this.nodes.some(T);
    }, y.filter = function(T) {
      return this.nodes.filter(T);
    }, y.sort = function(T) {
      return this.nodes.sort(T);
    }, y.toString = function() {
      return this.map(String).join("");
    }, h(m, [{
      key: "first",
      get: function() {
        return this.at(0);
      }
    }, {
      key: "last",
      get: function() {
        return this.at(this.length - 1);
      }
    }, {
      key: "length",
      get: function() {
        return this.nodes.length;
      }
    }]), m;
  }(t.default);
  e.default = x, r.exports = e.default;
}), mm = Pe(function(r, e) {
  e.__esModule = !0, e.default = void 0;
  var t = s(Nu);
  function s(u) {
    return u && u.__esModule ? u : { default: u };
  }
  function i(u, c) {
    for (var h = 0; h < c.length; h++) {
      var f = c[h];
      f.enumerable = f.enumerable || !1, f.configurable = !0, "value" in f && (f.writable = !0), Object.defineProperty(u, f.key, f);
    }
  }
  function n(u, c, h) {
    return c && i(u.prototype, c), h && i(u, h), u;
  }
  function a(u, c) {
    u.prototype = Object.create(c.prototype), u.prototype.constructor = u, o(u, c);
  }
  function o(u, c) {
    return o = Object.setPrototypeOf || function(f, p) {
      return f.__proto__ = p, f;
    }, o(u, c);
  }
  var l = /* @__PURE__ */ function(u) {
    a(c, u);
    function c(f) {
      var p;
      return p = u.call(this, f) || this, p.type = pe.ROOT, p;
    }
    var h = c.prototype;
    return h.toString = function() {
      var p = this.reduce(function(x, d) {
        return x.push(String(d)), x;
      }, []).join(",");
      return this.trailingComma ? p + "," : p;
    }, h.error = function(p, x) {
      return this._error ? this._error(p, x) : new Error(p);
    }, n(c, [{
      key: "errorGenerator",
      set: function(p) {
        this._error = p;
      }
    }]), c;
  }(t.default);
  e.default = l, r.exports = e.default;
}), ym = Pe(function(r, e) {
  e.__esModule = !0, e.default = void 0;
  var t = s(Nu);
  function s(o) {
    return o && o.__esModule ? o : { default: o };
  }
  function i(o, l) {
    o.prototype = Object.create(l.prototype), o.prototype.constructor = o, n(o, l);
  }
  function n(o, l) {
    return n = Object.setPrototypeOf || function(c, h) {
      return c.__proto__ = h, c;
    }, n(o, l);
  }
  var a = /* @__PURE__ */ function(o) {
    i(l, o);
    function l(u) {
      var c;
      return c = o.call(this, u) || this, c.type = pe.SELECTOR, c;
    }
    return l;
  }(t.default);
  e.default = a, r.exports = e.default;
});
/*! https://mths.be/cssesc v3.0.0 by @mathias */
var CP = {}, IP = CP.hasOwnProperty, NP = function(e, t) {
  if (!e)
    return t;
  var s = {};
  for (var i in t)
    s[i] = IP.call(e, i) ? e[i] : t[i];
  return s;
}, OP = /[ -,\.\/:-@\[-\^`\{-~]/, kP = /[ -,\.\/:-@\[\]\^`\{-~]/, MP = /(^|\\+)?(\\[A-F0-9]{1,6})\x20(?![a-fA-F0-9\x20])/g, Ou = function r(e, t) {
  t = NP(t, r.options), t.quotes != "single" && t.quotes != "double" && (t.quotes = "single");
  for (var s = t.quotes == "double" ? '"' : "'", i = t.isIdentifier, n = e.charAt(0), a = "", o = 0, l = e.length; o < l; ) {
    var u = e.charAt(o++), c = u.charCodeAt(), h = void 0;
    if (c < 32 || c > 126) {
      if (c >= 55296 && c <= 56319 && o < l) {
        var f = e.charCodeAt(o++);
        (f & 64512) == 56320 ? c = ((c & 1023) << 10) + (f & 1023) + 65536 : o--;
      }
      h = "\\" + c.toString(16).toUpperCase() + " ";
    } else
      t.escapeEverything ? OP.test(u) ? h = "\\" + u : h = "\\" + c.toString(16).toUpperCase() + " " : /[\t\n\f\r\x0B]/.test(u) ? h = "\\" + c.toString(16).toUpperCase() + " " : u == "\\" || !i && (u == '"' && s == u || u == "'" && s == u) || i && kP.test(u) ? h = "\\" + u : h = u;
    a += h;
  }
  return i && (/^-[-\d]/.test(a) ? a = "\\-" + a.slice(1) : /\d/.test(n) && (a = "\\3" + n + " " + a.slice(1))), a = a.replace(MP, function(p, x, d) {
    return x && x.length % 2 ? p : (x || "") + d;
  }), !i && t.wrap ? s + a + s : a;
};
Ou.options = {
  escapeEverything: !1,
  isIdentifier: !1,
  quotes: "single",
  wrap: !1
};
Ou.version = "3.0.0";
var ku = Ou, gm = Pe(function(r, e) {
  e.__esModule = !0, e.default = void 0;
  var t = i(ku), s = i(Ir);
  function i(c) {
    return c && c.__esModule ? c : { default: c };
  }
  function n(c, h) {
    for (var f = 0; f < h.length; f++) {
      var p = h[f];
      p.enumerable = p.enumerable || !1, p.configurable = !0, "value" in p && (p.writable = !0), Object.defineProperty(c, p.key, p);
    }
  }
  function a(c, h, f) {
    return h && n(c.prototype, h), f && n(c, f), c;
  }
  function o(c, h) {
    c.prototype = Object.create(h.prototype), c.prototype.constructor = c, l(c, h);
  }
  function l(c, h) {
    return l = Object.setPrototypeOf || function(p, x) {
      return p.__proto__ = x, p;
    }, l(c, h);
  }
  var u = /* @__PURE__ */ function(c) {
    o(h, c);
    function h(p) {
      var x;
      return x = c.call(this, p) || this, x.type = pe.CLASS, x._constructed = !0, x;
    }
    var f = h.prototype;
    return f.valueToString = function() {
      return "." + c.prototype.valueToString.call(this);
    }, a(h, [{
      key: "value",
      get: function() {
        return this._value;
      },
      set: function(x) {
        if (this._constructed) {
          var d = (0, t.default)(x, {
            isIdentifier: !0
          });
          d !== x ? ((0, Ie.ensureObject)(this, "raws"), this.raws.value = d) : this.raws && delete this.raws.value;
        }
        this._value = x;
      }
    }]), h;
  }(s.default);
  e.default = u, r.exports = e.default;
}), vm = Pe(function(r, e) {
  e.__esModule = !0, e.default = void 0;
  var t = s(Ir);
  function s(o) {
    return o && o.__esModule ? o : { default: o };
  }
  function i(o, l) {
    o.prototype = Object.create(l.prototype), o.prototype.constructor = o, n(o, l);
  }
  function n(o, l) {
    return n = Object.setPrototypeOf || function(c, h) {
      return c.__proto__ = h, c;
    }, n(o, l);
  }
  var a = /* @__PURE__ */ function(o) {
    i(l, o);
    function l(u) {
      var c;
      return c = o.call(this, u) || this, c.type = pe.COMMENT, c;
    }
    return l;
  }(t.default);
  e.default = a, r.exports = e.default;
}), bm = Pe(function(r, e) {
  e.__esModule = !0, e.default = void 0;
  var t = s(Ir);
  function s(o) {
    return o && o.__esModule ? o : { default: o };
  }
  function i(o, l) {
    o.prototype = Object.create(l.prototype), o.prototype.constructor = o, n(o, l);
  }
  function n(o, l) {
    return n = Object.setPrototypeOf || function(c, h) {
      return c.__proto__ = h, c;
    }, n(o, l);
  }
  var a = /* @__PURE__ */ function(o) {
    i(l, o);
    function l(c) {
      var h;
      return h = o.call(this, c) || this, h.type = pe.ID, h;
    }
    var u = l.prototype;
    return u.valueToString = function() {
      return "#" + o.prototype.valueToString.call(this);
    }, l;
  }(t.default);
  e.default = a, r.exports = e.default;
}), Mu = Pe(function(r, e) {
  e.__esModule = !0, e.default = void 0;
  var t = i(ku), s = i(Ir);
  function i(c) {
    return c && c.__esModule ? c : { default: c };
  }
  function n(c, h) {
    for (var f = 0; f < h.length; f++) {
      var p = h[f];
      p.enumerable = p.enumerable || !1, p.configurable = !0, "value" in p && (p.writable = !0), Object.defineProperty(c, p.key, p);
    }
  }
  function a(c, h, f) {
    return h && n(c.prototype, h), f && n(c, f), c;
  }
  function o(c, h) {
    c.prototype = Object.create(h.prototype), c.prototype.constructor = c, l(c, h);
  }
  function l(c, h) {
    return l = Object.setPrototypeOf || function(p, x) {
      return p.__proto__ = x, p;
    }, l(c, h);
  }
  var u = /* @__PURE__ */ function(c) {
    o(h, c);
    function h() {
      return c.apply(this, arguments) || this;
    }
    var f = h.prototype;
    return f.qualifiedName = function(x) {
      return this.namespace ? this.namespaceString + "|" + x : x;
    }, f.valueToString = function() {
      return this.qualifiedName(c.prototype.valueToString.call(this));
    }, a(h, [{
      key: "namespace",
      get: function() {
        return this._namespace;
      },
      set: function(x) {
        if (x === !0 || x === "*" || x === "&") {
          this._namespace = x, this.raws && delete this.raws.namespace;
          return;
        }
        var d = (0, t.default)(x, {
          isIdentifier: !0
        });
        this._namespace = x, d !== x ? ((0, Ie.ensureObject)(this, "raws"), this.raws.namespace = d) : this.raws && delete this.raws.namespace;
      }
    }, {
      key: "ns",
      get: function() {
        return this._namespace;
      },
      set: function(x) {
        this.namespace = x;
      }
    }, {
      key: "namespaceString",
      get: function() {
        if (this.namespace) {
          var x = this.stringifyProperty("namespace");
          return x === !0 ? "" : x;
        } else
          return "";
      }
    }]), h;
  }(s.default);
  e.default = u, r.exports = e.default;
}), xm = Pe(function(r, e) {
  e.__esModule = !0, e.default = void 0;
  var t = s(Mu);
  function s(o) {
    return o && o.__esModule ? o : { default: o };
  }
  function i(o, l) {
    o.prototype = Object.create(l.prototype), o.prototype.constructor = o, n(o, l);
  }
  function n(o, l) {
    return n = Object.setPrototypeOf || function(c, h) {
      return c.__proto__ = h, c;
    }, n(o, l);
  }
  var a = /* @__PURE__ */ function(o) {
    i(l, o);
    function l(u) {
      var c;
      return c = o.call(this, u) || this, c.type = pe.TAG, c;
    }
    return l;
  }(t.default);
  e.default = a, r.exports = e.default;
}), Sm = Pe(function(r, e) {
  e.__esModule = !0, e.default = void 0;
  var t = s(Ir);
  function s(o) {
    return o && o.__esModule ? o : { default: o };
  }
  function i(o, l) {
    o.prototype = Object.create(l.prototype), o.prototype.constructor = o, n(o, l);
  }
  function n(o, l) {
    return n = Object.setPrototypeOf || function(c, h) {
      return c.__proto__ = h, c;
    }, n(o, l);
  }
  var a = /* @__PURE__ */ function(o) {
    i(l, o);
    function l(u) {
      var c;
      return c = o.call(this, u) || this, c.type = pe.STRING, c;
    }
    return l;
  }(t.default);
  e.default = a, r.exports = e.default;
}), wm = Pe(function(r, e) {
  e.__esModule = !0, e.default = void 0;
  var t = s(Nu);
  function s(o) {
    return o && o.__esModule ? o : { default: o };
  }
  function i(o, l) {
    o.prototype = Object.create(l.prototype), o.prototype.constructor = o, n(o, l);
  }
  function n(o, l) {
    return n = Object.setPrototypeOf || function(c, h) {
      return c.__proto__ = h, c;
    }, n(o, l);
  }
  var a = /* @__PURE__ */ function(o) {
    i(l, o);
    function l(c) {
      var h;
      return h = o.call(this, c) || this, h.type = pe.PSEUDO, h;
    }
    var u = l.prototype;
    return u.toString = function() {
      var h = this.length ? "(" + this.map(String).join(",") + ")" : "";
      return [this.rawSpaceBefore, this.stringifyProperty("value"), h, this.rawSpaceAfter].join("");
    }, l;
  }(t.default);
  e.default = a, r.exports = e.default;
}), Hs = iw.deprecate, Pm = Pe(function(r, e) {
  e.__esModule = !0, e.unescapeValue = d, e.default = void 0;
  var t = a(ku), s = a(dm), i = a(Mu), n;
  function a(C) {
    return C && C.__esModule ? C : { default: C };
  }
  function o(C, v) {
    for (var w = 0; w < v.length; w++) {
      var N = v[w];
      N.enumerable = N.enumerable || !1, N.configurable = !0, "value" in N && (N.writable = !0), Object.defineProperty(C, N.key, N);
    }
  }
  function l(C, v, w) {
    return v && o(C.prototype, v), w && o(C, w), C;
  }
  function u(C, v) {
    C.prototype = Object.create(v.prototype), C.prototype.constructor = C, c(C, v);
  }
  function c(C, v) {
    return c = Object.setPrototypeOf || function(N, P) {
      return N.__proto__ = P, N;
    }, c(C, v);
  }
  var h = /^('|")([^]*)\1$/, f = Hs(function() {
  }, "Assigning an attribute a value containing characters that might need to be escaped is deprecated. Call attribute.setValue() instead."), p = Hs(function() {
  }, "Assigning attr.quoted is deprecated and has no effect. Assign to attr.quoteMark instead."), x = Hs(function() {
  }, "Constructing an Attribute selector with a value without specifying quoteMark is deprecated. Note: The value should be unescaped now.");
  function d(C) {
    var v = !1, w = null, N = C, P = N.match(h);
    return P && (w = P[1], N = P[2]), N = (0, s.default)(N), N !== C && (v = !0), {
      deprecatedUsage: v,
      unescaped: N,
      quoteMark: w
    };
  }
  function m(C) {
    if (C.quoteMark !== void 0 || C.value === void 0)
      return C;
    x();
    var v = d(C.value), w = v.quoteMark, N = v.unescaped;
    return C.raws || (C.raws = {}), C.raws.value === void 0 && (C.raws.value = C.value), C.value = N, C.quoteMark = w, C;
  }
  var y = /* @__PURE__ */ function(C) {
    u(v, C);
    function v(N) {
      var P;
      return N === void 0 && (N = {}), P = C.call(this, m(N)) || this, P.type = pe.ATTRIBUTE, P.raws = P.raws || {}, Object.defineProperty(P.raws, "unquoted", {
        get: Hs(function() {
          return P.value;
        }, "attr.raws.unquoted is deprecated. Call attr.value instead."),
        set: Hs(function() {
          return P.value;
        }, "Setting attr.raws.unquoted is deprecated and has no effect. attr.value is unescaped by default now.")
      }), P._constructed = !0, P;
    }
    var w = v.prototype;
    return w.getQuotedValue = function(P) {
      P === void 0 && (P = {});
      var g = this._determineQuoteMark(P), E = _[g], O = (0, t.default)(this._value, E);
      return O;
    }, w._determineQuoteMark = function(P) {
      return P.smart ? this.smartQuoteMark(P) : this.preferredQuoteMark(P);
    }, w.setValue = function(P, g) {
      g === void 0 && (g = {}), this._value = P, this._quoteMark = this._determineQuoteMark(g), this._syncRawValue();
    }, w.smartQuoteMark = function(P) {
      var g = this.value, E = g.replace(/[^']/g, "").length, O = g.replace(/[^"]/g, "").length;
      if (E + O === 0) {
        var S = (0, t.default)(g, {
          isIdentifier: !0
        });
        if (S === g)
          return v.NO_QUOTE;
        var W = this.preferredQuoteMark(P);
        if (W === v.NO_QUOTE) {
          var Q = this.quoteMark || P.quoteMark || v.DOUBLE_QUOTE, xe = _[Q], re = (0, t.default)(g, xe);
          if (re.length < S.length)
            return Q;
        }
        return W;
      } else
        return O === E ? this.preferredQuoteMark(P) : O < E ? v.DOUBLE_QUOTE : v.SINGLE_QUOTE;
    }, w.preferredQuoteMark = function(P) {
      var g = P.preferCurrentQuoteMark ? this.quoteMark : P.quoteMark;
      return g === void 0 && (g = P.preferCurrentQuoteMark ? P.quoteMark : this.quoteMark), g === void 0 && (g = v.DOUBLE_QUOTE), g;
    }, w._syncRawValue = function() {
      var P = (0, t.default)(this._value, _[this.quoteMark]);
      P === this._value ? this.raws && delete this.raws.value : this.raws.value = P;
    }, w._handleEscapes = function(P, g) {
      if (this._constructed) {
        var E = (0, t.default)(g, {
          isIdentifier: !0
        });
        E !== g ? this.raws[P] = E : delete this.raws[P];
      }
    }, w._spacesFor = function(P) {
      var g = {
        before: "",
        after: ""
      }, E = this.spaces[P] || {}, O = this.raws.spaces && this.raws.spaces[P] || {};
      return Object.assign(g, E, O);
    }, w._stringFor = function(P, g, E) {
      g === void 0 && (g = P), E === void 0 && (E = T);
      var O = this._spacesFor(g);
      return E(this.stringifyProperty(P), O);
    }, w.offsetOf = function(P) {
      var g = 1, E = this._spacesFor("attribute");
      if (g += E.before.length, P === "namespace" || P === "ns")
        return this.namespace ? g : -1;
      if (P === "attributeNS" || (g += this.namespaceString.length, this.namespace && (g += 1), P === "attribute"))
        return g;
      g += this.stringifyProperty("attribute").length, g += E.after.length;
      var O = this._spacesFor("operator");
      g += O.before.length;
      var S = this.stringifyProperty("operator");
      if (P === "operator")
        return S ? g : -1;
      g += S.length, g += O.after.length;
      var W = this._spacesFor("value");
      g += W.before.length;
      var Q = this.stringifyProperty("value");
      if (P === "value")
        return Q ? g : -1;
      g += Q.length, g += W.after.length;
      var xe = this._spacesFor("insensitive");
      return g += xe.before.length, P === "insensitive" && this.insensitive ? g : -1;
    }, w.toString = function() {
      var P = this, g = [this.rawSpaceBefore, "["];
      return g.push(this._stringFor("qualifiedAttribute", "attribute")), this.operator && (this.value || this.value === "") && (g.push(this._stringFor("operator")), g.push(this._stringFor("value")), g.push(this._stringFor("insensitiveFlag", "insensitive", function(E, O) {
        return E.length > 0 && !P.quoted && O.before.length === 0 && !(P.spaces.value && P.spaces.value.after) && (O.before = " "), T(E, O);
      }))), g.push("]"), g.push(this.rawSpaceAfter), g.join("");
    }, l(v, [{
      key: "quoted",
      get: function() {
        var P = this.quoteMark;
        return P === "'" || P === '"';
      },
      set: function(P) {
        p();
      }
    }, {
      key: "quoteMark",
      get: function() {
        return this._quoteMark;
      },
      set: function(P) {
        if (!this._constructed) {
          this._quoteMark = P;
          return;
        }
        this._quoteMark !== P && (this._quoteMark = P, this._syncRawValue());
      }
    }, {
      key: "qualifiedAttribute",
      get: function() {
        return this.qualifiedName(this.raws.attribute || this.attribute);
      }
    }, {
      key: "insensitiveFlag",
      get: function() {
        return this.insensitive ? "i" : "";
      }
    }, {
      key: "value",
      get: function() {
        return this._value;
      },
      set: function(P) {
        if (this._constructed) {
          var g = d(P), E = g.deprecatedUsage, O = g.unescaped, S = g.quoteMark;
          if (E && f(), O === this._value && S === this._quoteMark)
            return;
          this._value = O, this._quoteMark = S, this._syncRawValue();
        } else
          this._value = P;
      }
    }, {
      key: "attribute",
      get: function() {
        return this._attribute;
      },
      set: function(P) {
        this._handleEscapes("attribute", P), this._attribute = P;
      }
    }]), v;
  }(i.default);
  e.default = y, y.NO_QUOTE = null, y.SINGLE_QUOTE = "'", y.DOUBLE_QUOTE = '"';
  var _ = (n = {
    "'": {
      quotes: "single",
      wrap: !0
    },
    '"': {
      quotes: "double",
      wrap: !0
    }
  }, n[null] = {
    isIdentifier: !0
  }, n);
  function T(C, v) {
    return "" + v.before + C + v.after;
  }
}), Em = Pe(function(r, e) {
  e.__esModule = !0, e.default = void 0;
  var t = s(Mu);
  function s(o) {
    return o && o.__esModule ? o : { default: o };
  }
  function i(o, l) {
    o.prototype = Object.create(l.prototype), o.prototype.constructor = o, n(o, l);
  }
  function n(o, l) {
    return n = Object.setPrototypeOf || function(c, h) {
      return c.__proto__ = h, c;
    }, n(o, l);
  }
  var a = /* @__PURE__ */ function(o) {
    i(l, o);
    function l(u) {
      var c;
      return c = o.call(this, u) || this, c.type = pe.UNIVERSAL, c.value = "*", c;
    }
    return l;
  }(t.default);
  e.default = a, r.exports = e.default;
}), Tm = Pe(function(r, e) {
  e.__esModule = !0, e.default = void 0;
  var t = s(Ir);
  function s(o) {
    return o && o.__esModule ? o : { default: o };
  }
  function i(o, l) {
    o.prototype = Object.create(l.prototype), o.prototype.constructor = o, n(o, l);
  }
  function n(o, l) {
    return n = Object.setPrototypeOf || function(c, h) {
      return c.__proto__ = h, c;
    }, n(o, l);
  }
  var a = /* @__PURE__ */ function(o) {
    i(l, o);
    function l(u) {
      var c;
      return c = o.call(this, u) || this, c.type = pe.COMBINATOR, c;
    }
    return l;
  }(t.default);
  e.default = a, r.exports = e.default;
}), Am = Pe(function(r, e) {
  e.__esModule = !0, e.default = void 0;
  var t = s(Ir);
  function s(o) {
    return o && o.__esModule ? o : { default: o };
  }
  function i(o, l) {
    o.prototype = Object.create(l.prototype), o.prototype.constructor = o, n(o, l);
  }
  function n(o, l) {
    return n = Object.setPrototypeOf || function(c, h) {
      return c.__proto__ = h, c;
    }, n(o, l);
  }
  var a = /* @__PURE__ */ function(o) {
    i(l, o);
    function l(u) {
      var c;
      return c = o.call(this, u) || this, c.type = pe.NESTING, c.value = "&", c;
    }
    return l;
  }(t.default);
  e.default = a, r.exports = e.default;
}), LP = Pe(function(r, e) {
  e.__esModule = !0, e.default = t;
  function t(s) {
    return s.sort(function(i, n) {
      return i - n;
    });
  }
  r.exports = e.default;
}), _m = Pe(function(r, e) {
  e.__esModule = !0, e.combinator = e.word = e.comment = e.str = e.tab = e.newline = e.feed = e.cr = e.backslash = e.bang = e.slash = e.doubleQuote = e.singleQuote = e.space = e.greaterThan = e.pipe = e.equals = e.plus = e.caret = e.tilde = e.dollar = e.closeSquare = e.openSquare = e.closeParenthesis = e.openParenthesis = e.semicolon = e.colon = e.comma = e.at = e.asterisk = e.ampersand = void 0;
  var t = 38;
  e.ampersand = t;
  var s = 42;
  e.asterisk = s;
  var i = 64;
  e.at = i;
  var n = 44;
  e.comma = n;
  var a = 58;
  e.colon = a;
  var o = 59;
  e.semicolon = o;
  var l = 40;
  e.openParenthesis = l;
  var u = 41;
  e.closeParenthesis = u;
  var c = 91;
  e.openSquare = c;
  var h = 93;
  e.closeSquare = h;
  var f = 36;
  e.dollar = f;
  var p = 126;
  e.tilde = p;
  var x = 94;
  e.caret = x;
  var d = 43;
  e.plus = d;
  var m = 61;
  e.equals = m;
  var y = 124;
  e.pipe = y;
  var _ = 62;
  e.greaterThan = _;
  var T = 32;
  e.space = T;
  var C = 39;
  e.singleQuote = C;
  var v = 34;
  e.doubleQuote = v;
  var w = 47;
  e.slash = w;
  var N = 33;
  e.bang = N;
  var P = 92;
  e.backslash = P;
  var g = 13;
  e.cr = g;
  var E = 12;
  e.feed = E;
  var O = 10;
  e.newline = O;
  var S = 9;
  e.tab = S;
  var W = C;
  e.str = W;
  var Q = -1;
  e.comment = Q;
  var xe = -2;
  e.word = xe;
  var re = -3;
  e.combinator = re;
}), DP = Pe(function(r, e) {
  e.__esModule = !0, e.default = d, e.FIELDS = void 0;
  var t = a(_m), s, i;
  function n() {
    if (typeof WeakMap != "function")
      return null;
    var m = /* @__PURE__ */ new WeakMap();
    return n = function() {
      return m;
    }, m;
  }
  function a(m) {
    if (m && m.__esModule)
      return m;
    if (m === null || typeof m != "object" && typeof m != "function")
      return { default: m };
    var y = n();
    if (y && y.has(m))
      return y.get(m);
    var _ = {}, T = Object.defineProperty && Object.getOwnPropertyDescriptor;
    for (var C in m)
      if (Object.prototype.hasOwnProperty.call(m, C)) {
        var v = T ? Object.getOwnPropertyDescriptor(m, C) : null;
        v && (v.get || v.set) ? Object.defineProperty(_, C, v) : _[C] = m[C];
      }
    return _.default = m, y && y.set(m, _), _;
  }
  for (var o = (s = {}, s[t.tab] = !0, s[t.newline] = !0, s[t.cr] = !0, s[t.feed] = !0, s), l = (i = {}, i[t.space] = !0, i[t.tab] = !0, i[t.newline] = !0, i[t.cr] = !0, i[t.feed] = !0, i[t.ampersand] = !0, i[t.asterisk] = !0, i[t.bang] = !0, i[t.comma] = !0, i[t.colon] = !0, i[t.semicolon] = !0, i[t.openParenthesis] = !0, i[t.closeParenthesis] = !0, i[t.openSquare] = !0, i[t.closeSquare] = !0, i[t.singleQuote] = !0, i[t.doubleQuote] = !0, i[t.plus] = !0, i[t.pipe] = !0, i[t.tilde] = !0, i[t.greaterThan] = !0, i[t.equals] = !0, i[t.dollar] = !0, i[t.caret] = !0, i[t.slash] = !0, i), u = {}, c = "0123456789abcdefABCDEF", h = 0; h < c.length; h++)
    u[c.charCodeAt(h)] = !0;
  function f(m, y) {
    var _ = y, T;
    do {
      if (T = m.charCodeAt(_), l[T])
        return _ - 1;
      T === t.backslash ? _ = p(m, _) + 1 : _++;
    } while (_ < m.length);
    return _ - 1;
  }
  function p(m, y) {
    var _ = y, T = m.charCodeAt(_ + 1);
    if (!o[T])
      if (u[T]) {
        var C = 0;
        do
          _++, C++, T = m.charCodeAt(_ + 1);
        while (u[T] && C < 6);
        C < 6 && T === t.space && _++;
      } else
        _++;
    return _;
  }
  var x = {
    TYPE: 0,
    START_LINE: 1,
    START_COL: 2,
    END_LINE: 3,
    END_COL: 4,
    START_POS: 5,
    END_POS: 6
  };
  e.FIELDS = x;
  function d(m) {
    var y = [], _ = m.css.valueOf(), T = _, C = T.length, v = -1, w = 1, N = 0, P = 0, g, E, O, S, W, Q, xe, re, J, ce, qe, G, H;
    function K(k, V) {
      if (m.safe)
        _ += V, J = _.length - 1;
      else
        throw m.error("Unclosed " + k, w, N - v, N);
    }
    for (; N < C; ) {
      switch (g = _.charCodeAt(N), g === t.newline && (v = N, w += 1), g) {
        case t.space:
        case t.tab:
        case t.newline:
        case t.cr:
        case t.feed:
          J = N;
          do
            J += 1, g = _.charCodeAt(J), g === t.newline && (v = J, w += 1);
          while (g === t.space || g === t.newline || g === t.tab || g === t.cr || g === t.feed);
          H = t.space, S = w, O = J - v - 1, P = J;
          break;
        case t.plus:
        case t.greaterThan:
        case t.tilde:
        case t.pipe:
          J = N;
          do
            J += 1, g = _.charCodeAt(J);
          while (g === t.plus || g === t.greaterThan || g === t.tilde || g === t.pipe);
          H = t.combinator, S = w, O = N - v, P = J;
          break;
        case t.asterisk:
        case t.ampersand:
        case t.bang:
        case t.comma:
        case t.equals:
        case t.dollar:
        case t.caret:
        case t.openSquare:
        case t.closeSquare:
        case t.colon:
        case t.semicolon:
        case t.openParenthesis:
        case t.closeParenthesis:
          J = N, H = g, S = w, O = N - v, P = J + 1;
          break;
        case t.singleQuote:
        case t.doubleQuote:
          G = g === t.singleQuote ? "'" : '"', J = N;
          do
            for (W = !1, J = _.indexOf(G, J + 1), J === -1 && K("quote", G), Q = J; _.charCodeAt(Q - 1) === t.backslash; )
              Q -= 1, W = !W;
          while (W);
          H = t.str, S = w, O = N - v, P = J + 1;
          break;
        default:
          g === t.slash && _.charCodeAt(N + 1) === t.asterisk ? (J = _.indexOf("*/", N + 2) + 1, J === 0 && K("comment", "*/"), E = _.slice(N, J + 1), re = E.split(`
`), xe = re.length - 1, xe > 0 ? (ce = w + xe, qe = J - re[xe].length) : (ce = w, qe = v), H = t.comment, w = ce, S = ce, O = J - qe) : g === t.slash ? (J = N, H = g, S = w, O = N - v, P = J + 1) : (J = f(_, N), H = t.word, S = w, O = J - v), P = J + 1;
          break;
      }
      y.push([
        H,
        w,
        N - v,
        S,
        O,
        N,
        P
      ]), qe && (v = qe, qe = null), N = P;
    }
    return y;
  }
}), RP = Pe(function(r, e) {
  e.__esModule = !0, e.default = void 0;
  var t = w(mm), s = w(ym), i = w(gm), n = w(vm), a = w(bm), o = w(xm), l = w(Sm), u = w(wm), c = v(Pm), h = w(Em), f = w(Tm), p = w(Am), x = w(LP), d = v(DP), m = v(_m), y = v(pe), _, T;
  function C() {
    if (typeof WeakMap != "function")
      return null;
    var G = /* @__PURE__ */ new WeakMap();
    return C = function() {
      return G;
    }, G;
  }
  function v(G) {
    if (G && G.__esModule)
      return G;
    if (G === null || typeof G != "object" && typeof G != "function")
      return { default: G };
    var H = C();
    if (H && H.has(G))
      return H.get(G);
    var K = {}, k = Object.defineProperty && Object.getOwnPropertyDescriptor;
    for (var V in G)
      if (Object.prototype.hasOwnProperty.call(G, V)) {
        var le = k ? Object.getOwnPropertyDescriptor(G, V) : null;
        le && (le.get || le.set) ? Object.defineProperty(K, V, le) : K[V] = G[V];
      }
    return K.default = G, H && H.set(G, K), K;
  }
  function w(G) {
    return G && G.__esModule ? G : { default: G };
  }
  function N(G, H) {
    for (var K = 0; K < H.length; K++) {
      var k = H[K];
      k.enumerable = k.enumerable || !1, k.configurable = !0, "value" in k && (k.writable = !0), Object.defineProperty(G, k.key, k);
    }
  }
  function P(G, H, K) {
    return H && N(G.prototype, H), K && N(G, K), G;
  }
  var g = (_ = {}, _[m.space] = !0, _[m.cr] = !0, _[m.feed] = !0, _[m.newline] = !0, _[m.tab] = !0, _), E = Object.assign({}, g, (T = {}, T[m.comment] = !0, T));
  function O(G) {
    return {
      line: G[d.FIELDS.START_LINE],
      column: G[d.FIELDS.START_COL]
    };
  }
  function S(G) {
    return {
      line: G[d.FIELDS.END_LINE],
      column: G[d.FIELDS.END_COL]
    };
  }
  function W(G, H, K, k) {
    return {
      start: {
        line: G,
        column: H
      },
      end: {
        line: K,
        column: k
      }
    };
  }
  function Q(G) {
    return W(G[d.FIELDS.START_LINE], G[d.FIELDS.START_COL], G[d.FIELDS.END_LINE], G[d.FIELDS.END_COL]);
  }
  function xe(G, H) {
    if (!!G)
      return W(G[d.FIELDS.START_LINE], G[d.FIELDS.START_COL], H[d.FIELDS.END_LINE], H[d.FIELDS.END_COL]);
  }
  function re(G, H) {
    var K = G[H];
    if (typeof K == "string")
      return K.indexOf("\\") !== -1 && ((0, Ie.ensureObject)(G, "raws"), G[H] = (0, Ie.unesc)(K), G.raws[H] === void 0 && (G.raws[H] = K)), G;
  }
  function J(G, H) {
    for (var K = -1, k = []; (K = G.indexOf(H, K + 1)) !== -1; )
      k.push(K);
    return k;
  }
  function ce() {
    var G = Array.prototype.concat.apply([], arguments);
    return G.filter(function(H, K) {
      return K === G.indexOf(H);
    });
  }
  var qe = /* @__PURE__ */ function() {
    function G(K, k) {
      k === void 0 && (k = {}), this.rule = K, this.options = Object.assign({
        lossy: !1,
        safe: !1
      }, k), this.position = 0, this.css = typeof this.rule == "string" ? this.rule : this.rule.selector, this.tokens = (0, d.default)({
        css: this.css,
        error: this._errorGenerator(),
        safe: this.options.safe
      });
      var V = xe(this.tokens[0], this.tokens[this.tokens.length - 1]);
      this.root = new t.default({
        source: V
      }), this.root.errorGenerator = this._errorGenerator();
      var le = new s.default({
        source: {
          start: {
            line: 1,
            column: 1
          }
        }
      });
      this.root.append(le), this.current = le, this.loop();
    }
    var H = G.prototype;
    return H._errorGenerator = function() {
      var k = this;
      return function(V, le) {
        return typeof k.rule == "string" ? new Error(V) : k.rule.error(V, le);
      };
    }, H.attribute = function() {
      var k = [], V = this.currToken;
      for (this.position++; this.position < this.tokens.length && this.currToken[d.FIELDS.TYPE] !== m.closeSquare; )
        k.push(this.currToken), this.position++;
      if (this.currToken[d.FIELDS.TYPE] !== m.closeSquare)
        return this.expected("closing square bracket", this.currToken[d.FIELDS.START_POS]);
      var le = k.length, D = {
        source: W(V[1], V[2], this.currToken[3], this.currToken[4]),
        sourceIndex: V[d.FIELDS.START_POS]
      };
      if (le === 1 && !~[m.word].indexOf(k[0][d.FIELDS.TYPE]))
        return this.expected("attribute", k[0][d.FIELDS.START_POS]);
      for (var oe = 0, Ee = "", ye = "", ae = null, Te = !1; oe < le; ) {
        var Be = k[oe], b = this.content(Be), A = k[oe + 1];
        switch (Be[d.FIELDS.TYPE]) {
          case m.space:
            if (Te = !0, this.options.lossy)
              break;
            if (ae) {
              (0, Ie.ensureObject)(D, "spaces", ae);
              var M = D.spaces[ae].after || "";
              D.spaces[ae].after = M + b;
              var R = (0, Ie.getProp)(D, "raws", "spaces", ae, "after") || null;
              R && (D.raws.spaces[ae].after = R + b);
            } else
              Ee = Ee + b, ye = ye + b;
            break;
          case m.asterisk:
            if (A[d.FIELDS.TYPE] === m.equals)
              D.operator = b, ae = "operator";
            else if ((!D.namespace || ae === "namespace" && !Te) && A) {
              Ee && ((0, Ie.ensureObject)(D, "spaces", "attribute"), D.spaces.attribute.before = Ee, Ee = ""), ye && ((0, Ie.ensureObject)(D, "raws", "spaces", "attribute"), D.raws.spaces.attribute.before = Ee, ye = ""), D.namespace = (D.namespace || "") + b;
              var L = (0, Ie.getProp)(D, "raws", "namespace") || null;
              L && (D.raws.namespace += b), ae = "namespace";
            }
            Te = !1;
            break;
          case m.dollar:
            if (ae === "value") {
              var B = (0, Ie.getProp)(D, "raws", "value");
              D.value += "$", B && (D.raws.value = B + "$");
              break;
            }
          case m.caret:
            A[d.FIELDS.TYPE] === m.equals && (D.operator = b, ae = "operator"), Te = !1;
            break;
          case m.combinator:
            if (b === "~" && A[d.FIELDS.TYPE] === m.equals && (D.operator = b, ae = "operator"), b !== "|") {
              Te = !1;
              break;
            }
            A[d.FIELDS.TYPE] === m.equals ? (D.operator = b, ae = "operator") : !D.namespace && !D.attribute && (D.namespace = !0), Te = !1;
            break;
          case m.word:
            if (A && this.content(A) === "|" && k[oe + 2] && k[oe + 2][d.FIELDS.TYPE] !== m.equals && !D.operator && !D.namespace)
              D.namespace = b, ae = "namespace";
            else if (!D.attribute || ae === "attribute" && !Te) {
              Ee && ((0, Ie.ensureObject)(D, "spaces", "attribute"), D.spaces.attribute.before = Ee, Ee = ""), ye && ((0, Ie.ensureObject)(D, "raws", "spaces", "attribute"), D.raws.spaces.attribute.before = ye, ye = ""), D.attribute = (D.attribute || "") + b;
              var z = (0, Ie.getProp)(D, "raws", "attribute") || null;
              z && (D.raws.attribute += b), ae = "attribute";
            } else if (!D.value && D.value !== "" || ae === "value" && !Te) {
              var $ = (0, Ie.unesc)(b), q = (0, Ie.getProp)(D, "raws", "value") || "", U = D.value || "";
              D.value = U + $, D.quoteMark = null, ($ !== b || q) && ((0, Ie.ensureObject)(D, "raws"), D.raws.value = (q || U) + b), ae = "value";
            } else {
              var ee = b === "i" || b === "I";
              (D.value || D.value === "") && (D.quoteMark || Te) ? (D.insensitive = ee, (!ee || b === "I") && ((0, Ie.ensureObject)(D, "raws"), D.raws.insensitiveFlag = b), ae = "insensitive", Ee && ((0, Ie.ensureObject)(D, "spaces", "insensitive"), D.spaces.insensitive.before = Ee, Ee = ""), ye && ((0, Ie.ensureObject)(D, "raws", "spaces", "insensitive"), D.raws.spaces.insensitive.before = ye, ye = "")) : (D.value || D.value === "") && (ae = "value", D.value += b, D.raws.value && (D.raws.value += b));
            }
            Te = !1;
            break;
          case m.str:
            if (!D.attribute || !D.operator)
              return this.error("Expected an attribute followed by an operator preceding the string.", {
                index: Be[d.FIELDS.START_POS]
              });
            var X = (0, c.unescapeValue)(b), te = X.unescaped, ue = X.quoteMark;
            D.value = te, D.quoteMark = ue, ae = "value", (0, Ie.ensureObject)(D, "raws"), D.raws.value = b, Te = !1;
            break;
          case m.equals:
            if (!D.attribute)
              return this.expected("attribute", Be[d.FIELDS.START_POS], b);
            if (D.value)
              return this.error('Unexpected "=" found; an operator was already defined.', {
                index: Be[d.FIELDS.START_POS]
              });
            D.operator = D.operator ? D.operator + b : b, ae = "operator", Te = !1;
            break;
          case m.comment:
            if (ae)
              if (Te || A && A[d.FIELDS.TYPE] === m.space || ae === "insensitive") {
                var ge = (0, Ie.getProp)(D, "spaces", ae, "after") || "", Ae = (0, Ie.getProp)(D, "raws", "spaces", ae, "after") || ge;
                (0, Ie.ensureObject)(D, "raws", "spaces", ae), D.raws.spaces[ae].after = Ae + b;
              } else {
                var _e = D[ae] || "", Oe = (0, Ie.getProp)(D, "raws", ae) || _e;
                (0, Ie.ensureObject)(D, "raws"), D.raws[ae] = Oe + b;
              }
            else
              ye = ye + b;
            break;
          default:
            return this.error('Unexpected "' + b + '" found.', {
              index: Be[d.FIELDS.START_POS]
            });
        }
        oe++;
      }
      re(D, "attribute"), re(D, "namespace"), this.newNode(new c.default(D)), this.position++;
    }, H.parseWhitespaceEquivalentTokens = function(k) {
      k < 0 && (k = this.tokens.length);
      var V = this.position, le = [], D = "", oe = void 0;
      do
        if (g[this.currToken[d.FIELDS.TYPE]])
          this.options.lossy || (D += this.content());
        else if (this.currToken[d.FIELDS.TYPE] === m.comment) {
          var Ee = {};
          D && (Ee.before = D, D = ""), oe = new n.default({
            value: this.content(),
            source: Q(this.currToken),
            sourceIndex: this.currToken[d.FIELDS.START_POS],
            spaces: Ee
          }), le.push(oe);
        }
      while (++this.position < k);
      if (D) {
        if (oe)
          oe.spaces.after = D;
        else if (!this.options.lossy) {
          var ye = this.tokens[V], ae = this.tokens[this.position - 1];
          le.push(new l.default({
            value: "",
            source: W(ye[d.FIELDS.START_LINE], ye[d.FIELDS.START_COL], ae[d.FIELDS.END_LINE], ae[d.FIELDS.END_COL]),
            sourceIndex: ye[d.FIELDS.START_POS],
            spaces: {
              before: D,
              after: ""
            }
          }));
        }
      }
      return le;
    }, H.convertWhitespaceNodesToSpace = function(k, V) {
      var le = this;
      V === void 0 && (V = !1);
      var D = "", oe = "";
      k.forEach(function(ye) {
        var ae = le.lossySpace(ye.spaces.before, V), Te = le.lossySpace(ye.rawSpaceBefore, V);
        D += ae + le.lossySpace(ye.spaces.after, V && ae.length === 0), oe += ae + ye.value + le.lossySpace(ye.rawSpaceAfter, V && Te.length === 0);
      }), oe === D && (oe = void 0);
      var Ee = {
        space: D,
        rawSpace: oe
      };
      return Ee;
    }, H.isNamedCombinator = function(k) {
      return k === void 0 && (k = this.position), this.tokens[k + 0] && this.tokens[k + 0][d.FIELDS.TYPE] === m.slash && this.tokens[k + 1] && this.tokens[k + 1][d.FIELDS.TYPE] === m.word && this.tokens[k + 2] && this.tokens[k + 2][d.FIELDS.TYPE] === m.slash;
    }, H.namedCombinator = function() {
      if (this.isNamedCombinator()) {
        var k = this.content(this.tokens[this.position + 1]), V = (0, Ie.unesc)(k).toLowerCase(), le = {};
        V !== k && (le.value = "/" + k + "/");
        var D = new f.default({
          value: "/" + V + "/",
          source: W(this.currToken[d.FIELDS.START_LINE], this.currToken[d.FIELDS.START_COL], this.tokens[this.position + 2][d.FIELDS.END_LINE], this.tokens[this.position + 2][d.FIELDS.END_COL]),
          sourceIndex: this.currToken[d.FIELDS.START_POS],
          raws: le
        });
        return this.position = this.position + 3, D;
      } else
        this.unexpected();
    }, H.combinator = function() {
      var k = this;
      if (this.content() === "|")
        return this.namespace();
      var V = this.locateNextMeaningfulToken(this.position);
      if (V < 0 || this.tokens[V][d.FIELDS.TYPE] === m.comma) {
        var le = this.parseWhitespaceEquivalentTokens(V);
        if (le.length > 0) {
          var D = this.current.last;
          if (D) {
            var oe = this.convertWhitespaceNodesToSpace(le), Ee = oe.space, ye = oe.rawSpace;
            ye !== void 0 && (D.rawSpaceAfter += ye), D.spaces.after += Ee;
          } else
            le.forEach(function(q) {
              return k.newNode(q);
            });
        }
        return;
      }
      var ae = this.currToken, Te = void 0;
      V > this.position && (Te = this.parseWhitespaceEquivalentTokens(V));
      var Be;
      if (this.isNamedCombinator() ? Be = this.namedCombinator() : this.currToken[d.FIELDS.TYPE] === m.combinator ? (Be = new f.default({
        value: this.content(),
        source: Q(this.currToken),
        sourceIndex: this.currToken[d.FIELDS.START_POS]
      }), this.position++) : g[this.currToken[d.FIELDS.TYPE]] || Te || this.unexpected(), Be) {
        if (Te) {
          var b = this.convertWhitespaceNodesToSpace(Te), A = b.space, M = b.rawSpace;
          Be.spaces.before = A, Be.rawSpaceBefore = M;
        }
      } else {
        var R = this.convertWhitespaceNodesToSpace(Te, !0), L = R.space, B = R.rawSpace;
        B || (B = L);
        var z = {}, $ = {
          spaces: {}
        };
        L.endsWith(" ") && B.endsWith(" ") ? (z.before = L.slice(0, L.length - 1), $.spaces.before = B.slice(0, B.length - 1)) : L.startsWith(" ") && B.startsWith(" ") ? (z.after = L.slice(1), $.spaces.after = B.slice(1)) : $.value = B, Be = new f.default({
          value: " ",
          source: xe(ae, this.tokens[this.position - 1]),
          sourceIndex: ae[d.FIELDS.START_POS],
          spaces: z,
          raws: $
        });
      }
      return this.currToken && this.currToken[d.FIELDS.TYPE] === m.space && (Be.spaces.after = this.optionalSpace(this.content()), this.position++), this.newNode(Be);
    }, H.comma = function() {
      if (this.position === this.tokens.length - 1) {
        this.root.trailingComma = !0, this.position++;
        return;
      }
      this.current._inferEndPosition();
      var k = new s.default({
        source: {
          start: O(this.tokens[this.position + 1])
        }
      });
      this.current.parent.append(k), this.current = k, this.position++;
    }, H.comment = function() {
      var k = this.currToken;
      this.newNode(new n.default({
        value: this.content(),
        source: Q(k),
        sourceIndex: k[d.FIELDS.START_POS]
      })), this.position++;
    }, H.error = function(k, V) {
      throw this.root.error(k, V);
    }, H.missingBackslash = function() {
      return this.error("Expected a backslash preceding the semicolon.", {
        index: this.currToken[d.FIELDS.START_POS]
      });
    }, H.missingParenthesis = function() {
      return this.expected("opening parenthesis", this.currToken[d.FIELDS.START_POS]);
    }, H.missingSquareBracket = function() {
      return this.expected("opening square bracket", this.currToken[d.FIELDS.START_POS]);
    }, H.unexpected = function() {
      return this.error("Unexpected '" + this.content() + "'. Escaping special characters with \\ may help.", this.currToken[d.FIELDS.START_POS]);
    }, H.namespace = function() {
      var k = this.prevToken && this.content(this.prevToken) || !0;
      if (this.nextToken[d.FIELDS.TYPE] === m.word)
        return this.position++, this.word(k);
      if (this.nextToken[d.FIELDS.TYPE] === m.asterisk)
        return this.position++, this.universal(k);
    }, H.nesting = function() {
      if (this.nextToken) {
        var k = this.content(this.nextToken);
        if (k === "|") {
          this.position++;
          return;
        }
      }
      var V = this.currToken;
      this.newNode(new p.default({
        value: this.content(),
        source: Q(V),
        sourceIndex: V[d.FIELDS.START_POS]
      })), this.position++;
    }, H.parentheses = function() {
      var k = this.current.last, V = 1;
      if (this.position++, k && k.type === y.PSEUDO) {
        var le = new s.default({
          source: {
            start: O(this.tokens[this.position - 1])
          }
        }), D = this.current;
        for (k.append(le), this.current = le; this.position < this.tokens.length && V; )
          this.currToken[d.FIELDS.TYPE] === m.openParenthesis && V++, this.currToken[d.FIELDS.TYPE] === m.closeParenthesis && V--, V ? this.parse() : (this.current.source.end = S(this.currToken), this.current.parent.source.end = S(this.currToken), this.position++);
        this.current = D;
      } else {
        for (var oe = this.currToken, Ee = "(", ye; this.position < this.tokens.length && V; )
          this.currToken[d.FIELDS.TYPE] === m.openParenthesis && V++, this.currToken[d.FIELDS.TYPE] === m.closeParenthesis && V--, ye = this.currToken, Ee += this.parseParenthesisToken(this.currToken), this.position++;
        k ? k.appendToPropertyAndEscape("value", Ee, Ee) : this.newNode(new l.default({
          value: Ee,
          source: W(oe[d.FIELDS.START_LINE], oe[d.FIELDS.START_COL], ye[d.FIELDS.END_LINE], ye[d.FIELDS.END_COL]),
          sourceIndex: oe[d.FIELDS.START_POS]
        }));
      }
      if (V)
        return this.expected("closing parenthesis", this.currToken[d.FIELDS.START_POS]);
    }, H.pseudo = function() {
      for (var k = this, V = "", le = this.currToken; this.currToken && this.currToken[d.FIELDS.TYPE] === m.colon; )
        V += this.content(), this.position++;
      if (!this.currToken)
        return this.expected(["pseudo-class", "pseudo-element"], this.position - 1);
      if (this.currToken[d.FIELDS.TYPE] === m.word)
        this.splitWord(!1, function(D, oe) {
          V += D, k.newNode(new u.default({
            value: V,
            source: xe(le, k.currToken),
            sourceIndex: le[d.FIELDS.START_POS]
          })), oe > 1 && k.nextToken && k.nextToken[d.FIELDS.TYPE] === m.openParenthesis && k.error("Misplaced parenthesis.", {
            index: k.nextToken[d.FIELDS.START_POS]
          });
        });
      else
        return this.expected(["pseudo-class", "pseudo-element"], this.currToken[d.FIELDS.START_POS]);
    }, H.space = function() {
      var k = this.content();
      this.position === 0 || this.prevToken[d.FIELDS.TYPE] === m.comma || this.prevToken[d.FIELDS.TYPE] === m.openParenthesis || this.current.nodes.every(function(V) {
        return V.type === "comment";
      }) ? (this.spaces = this.optionalSpace(k), this.position++) : this.position === this.tokens.length - 1 || this.nextToken[d.FIELDS.TYPE] === m.comma || this.nextToken[d.FIELDS.TYPE] === m.closeParenthesis ? (this.current.last.spaces.after = this.optionalSpace(k), this.position++) : this.combinator();
    }, H.string = function() {
      var k = this.currToken;
      this.newNode(new l.default({
        value: this.content(),
        source: Q(k),
        sourceIndex: k[d.FIELDS.START_POS]
      })), this.position++;
    }, H.universal = function(k) {
      var V = this.nextToken;
      if (V && this.content(V) === "|")
        return this.position++, this.namespace();
      var le = this.currToken;
      this.newNode(new h.default({
        value: this.content(),
        source: Q(le),
        sourceIndex: le[d.FIELDS.START_POS]
      }), k), this.position++;
    }, H.splitWord = function(k, V) {
      for (var le = this, D = this.nextToken, oe = this.content(); D && ~[m.dollar, m.caret, m.equals, m.word].indexOf(D[d.FIELDS.TYPE]); ) {
        this.position++;
        var Ee = this.content();
        if (oe += Ee, Ee.lastIndexOf("\\") === Ee.length - 1) {
          var ye = this.nextToken;
          ye && ye[d.FIELDS.TYPE] === m.space && (oe += this.requiredSpace(this.content(ye)), this.position++);
        }
        D = this.nextToken;
      }
      var ae = J(oe, ".").filter(function(A) {
        var M = oe[A - 1] === "\\", R = /^\d+\.\d+%$/.test(oe);
        return !M && !R;
      }), Te = J(oe, "#").filter(function(A) {
        return oe[A - 1] !== "\\";
      }), Be = J(oe, "#{");
      Be.length && (Te = Te.filter(function(A) {
        return !~Be.indexOf(A);
      }));
      var b = (0, x.default)(ce([0].concat(ae, Te)));
      b.forEach(function(A, M) {
        var R = b[M + 1] || oe.length, L = oe.slice(A, R);
        if (M === 0 && V)
          return V.call(le, L, b.length);
        var B, z = le.currToken, $ = z[d.FIELDS.START_POS] + b[M], q = W(z[1], z[2] + A, z[3], z[2] + (R - 1));
        if (~ae.indexOf(A)) {
          var U = {
            value: L.slice(1),
            source: q,
            sourceIndex: $
          };
          B = new i.default(re(U, "value"));
        } else if (~Te.indexOf(A)) {
          var ee = {
            value: L.slice(1),
            source: q,
            sourceIndex: $
          };
          B = new a.default(re(ee, "value"));
        } else {
          var X = {
            value: L,
            source: q,
            sourceIndex: $
          };
          re(X, "value"), B = new o.default(X);
        }
        le.newNode(B, k), k = null;
      }), this.position++;
    }, H.word = function(k) {
      var V = this.nextToken;
      return V && this.content(V) === "|" ? (this.position++, this.namespace()) : this.splitWord(k);
    }, H.loop = function() {
      for (; this.position < this.tokens.length; )
        this.parse(!0);
      return this.current._inferEndPosition(), this.root;
    }, H.parse = function(k) {
      switch (this.currToken[d.FIELDS.TYPE]) {
        case m.space:
          this.space();
          break;
        case m.comment:
          this.comment();
          break;
        case m.openParenthesis:
          this.parentheses();
          break;
        case m.closeParenthesis:
          k && this.missingParenthesis();
          break;
        case m.openSquare:
          this.attribute();
          break;
        case m.dollar:
        case m.caret:
        case m.equals:
        case m.word:
          this.word();
          break;
        case m.colon:
          this.pseudo();
          break;
        case m.comma:
          this.comma();
          break;
        case m.asterisk:
          this.universal();
          break;
        case m.ampersand:
          this.nesting();
          break;
        case m.slash:
        case m.combinator:
          this.combinator();
          break;
        case m.str:
          this.string();
          break;
        case m.closeSquare:
          this.missingSquareBracket();
        case m.semicolon:
          this.missingBackslash();
        default:
          this.unexpected();
      }
    }, H.expected = function(k, V, le) {
      if (Array.isArray(k)) {
        var D = k.pop();
        k = k.join(", ") + " or " + D;
      }
      var oe = /^[aeiou]/.test(k[0]) ? "an" : "a";
      return le ? this.error("Expected " + oe + " " + k + ', found "' + le + '" instead.', {
        index: V
      }) : this.error("Expected " + oe + " " + k + ".", {
        index: V
      });
    }, H.requiredSpace = function(k) {
      return this.options.lossy ? " " : k;
    }, H.optionalSpace = function(k) {
      return this.options.lossy ? "" : k;
    }, H.lossySpace = function(k, V) {
      return this.options.lossy ? V ? " " : "" : k;
    }, H.parseParenthesisToken = function(k) {
      var V = this.content(k);
      return k[d.FIELDS.TYPE] === m.space ? this.requiredSpace(V) : V;
    }, H.newNode = function(k, V) {
      return V && (/^ +$/.test(V) && (this.options.lossy || (this.spaces = (this.spaces || "") + V), V = !0), k.namespace = V, re(k, "namespace")), this.spaces && (k.spaces.before = this.spaces, this.spaces = ""), this.current.append(k);
    }, H.content = function(k) {
      return k === void 0 && (k = this.currToken), this.css.slice(k[d.FIELDS.START_POS], k[d.FIELDS.END_POS]);
    }, H.locateNextMeaningfulToken = function(k) {
      k === void 0 && (k = this.position + 1);
      for (var V = k; V < this.tokens.length; )
        if (E[this.tokens[V][d.FIELDS.TYPE]]) {
          V++;
          continue;
        } else
          return V;
      return -1;
    }, P(G, [{
      key: "currToken",
      get: function() {
        return this.tokens[this.position];
      }
    }, {
      key: "nextToken",
      get: function() {
        return this.tokens[this.position + 1];
      }
    }, {
      key: "prevToken",
      get: function() {
        return this.tokens[this.position - 1];
      }
    }]), G;
  }();
  e.default = qe, r.exports = e.default;
}), FP = Pe(function(r, e) {
  e.__esModule = !0, e.default = void 0;
  var t = s(RP);
  function s(n) {
    return n && n.__esModule ? n : { default: n };
  }
  var i = /* @__PURE__ */ function() {
    function n(o, l) {
      this.func = o || function() {
      }, this.funcRes = null, this.options = l;
    }
    var a = n.prototype;
    return a._shouldUpdateSelector = function(l, u) {
      u === void 0 && (u = {});
      var c = Object.assign({}, this.options, u);
      return c.updateSelector === !1 ? !1 : typeof l != "string";
    }, a._isLossy = function(l) {
      l === void 0 && (l = {});
      var u = Object.assign({}, this.options, l);
      return u.lossless === !1;
    }, a._root = function(l, u) {
      u === void 0 && (u = {});
      var c = new t.default(l, this._parseOptions(u));
      return c.root;
    }, a._parseOptions = function(l) {
      return {
        lossy: this._isLossy(l)
      };
    }, a._run = function(l, u) {
      var c = this;
      return u === void 0 && (u = {}), new Promise(function(h, f) {
        try {
          var p = c._root(l, u);
          Promise.resolve(c.func(p)).then(function(x) {
            var d = void 0;
            return c._shouldUpdateSelector(l, u) && (d = p.toString(), l.selector = d), {
              transform: x,
              root: p,
              string: d
            };
          }).then(h, f);
        } catch (x) {
          f(x);
          return;
        }
      });
    }, a._runSync = function(l, u) {
      u === void 0 && (u = {});
      var c = this._root(l, u), h = this.func(c);
      if (h && typeof h.then == "function")
        throw new Error("Selector processor returned a promise to a synchronous call.");
      var f = void 0;
      return u.updateSelector && typeof l != "string" && (f = c.toString(), l.selector = f), {
        transform: h,
        root: c,
        string: f
      };
    }, a.ast = function(l, u) {
      return this._run(l, u).then(function(c) {
        return c.root;
      });
    }, a.astSync = function(l, u) {
      return this._runSync(l, u).root;
    }, a.transform = function(l, u) {
      return this._run(l, u).then(function(c) {
        return c.transform;
      });
    }, a.transformSync = function(l, u) {
      return this._runSync(l, u).transform;
    }, a.process = function(l, u) {
      return this._run(l, u).then(function(c) {
        return c.string || c.root.toString();
      });
    }, a.processSync = function(l, u) {
      var c = this._runSync(l, u);
      return c.string || c.root.toString();
    }, n;
  }();
  e.default = i, r.exports = e.default;
}), io = Pe(function(r, e) {
  e.__esModule = !0, e.universal = e.tag = e.string = e.selector = e.root = e.pseudo = e.nesting = e.id = e.comment = e.combinator = e.className = e.attribute = void 0;
  var t = x(Pm), s = x(gm), i = x(Tm), n = x(vm), a = x(bm), o = x(Am), l = x(wm), u = x(mm), c = x(ym), h = x(Sm), f = x(xm), p = x(Em);
  function x(O) {
    return O && O.__esModule ? O : { default: O };
  }
  var d = function(S) {
    return new t.default(S);
  };
  e.attribute = d;
  var m = function(S) {
    return new s.default(S);
  };
  e.className = m;
  var y = function(S) {
    return new i.default(S);
  };
  e.combinator = y;
  var _ = function(S) {
    return new n.default(S);
  };
  e.comment = _;
  var T = function(S) {
    return new a.default(S);
  };
  e.id = T;
  var C = function(S) {
    return new o.default(S);
  };
  e.nesting = C;
  var v = function(S) {
    return new l.default(S);
  };
  e.pseudo = v;
  var w = function(S) {
    return new u.default(S);
  };
  e.root = w;
  var N = function(S) {
    return new c.default(S);
  };
  e.selector = N;
  var P = function(S) {
    return new h.default(S);
  };
  e.string = P;
  var g = function(S) {
    return new f.default(S);
  };
  e.tag = g;
  var E = function(S) {
    return new p.default(S);
  };
  e.universal = E;
}), no = Pe(function(r, e) {
  e.__esModule = !0, e.isNode = i, e.isPseudoElement = _, e.isPseudoClass = T, e.isContainer = C, e.isNamespace = v, e.isUniversal = e.isTag = e.isString = e.isSelector = e.isRoot = e.isPseudo = e.isNesting = e.isIdentifier = e.isComment = e.isCombinator = e.isClassName = e.isAttribute = void 0;
  var t, s = (t = {}, t[pe.ATTRIBUTE] = !0, t[pe.CLASS] = !0, t[pe.COMBINATOR] = !0, t[pe.COMMENT] = !0, t[pe.ID] = !0, t[pe.NESTING] = !0, t[pe.PSEUDO] = !0, t[pe.ROOT] = !0, t[pe.SELECTOR] = !0, t[pe.STRING] = !0, t[pe.TAG] = !0, t[pe.UNIVERSAL] = !0, t);
  function i(w) {
    return typeof w == "object" && s[w.type];
  }
  function n(w, N) {
    return i(N) && N.type === w;
  }
  var a = n.bind(null, pe.ATTRIBUTE);
  e.isAttribute = a;
  var o = n.bind(null, pe.CLASS);
  e.isClassName = o;
  var l = n.bind(null, pe.COMBINATOR);
  e.isCombinator = l;
  var u = n.bind(null, pe.COMMENT);
  e.isComment = u;
  var c = n.bind(null, pe.ID);
  e.isIdentifier = c;
  var h = n.bind(null, pe.NESTING);
  e.isNesting = h;
  var f = n.bind(null, pe.PSEUDO);
  e.isPseudo = f;
  var p = n.bind(null, pe.ROOT);
  e.isRoot = p;
  var x = n.bind(null, pe.SELECTOR);
  e.isSelector = x;
  var d = n.bind(null, pe.STRING);
  e.isString = d;
  var m = n.bind(null, pe.TAG);
  e.isTag = m;
  var y = n.bind(null, pe.UNIVERSAL);
  e.isUniversal = y;
  function _(w) {
    return f(w) && w.value && (w.value.startsWith("::") || w.value.toLowerCase() === ":before" || w.value.toLowerCase() === ":after");
  }
  function T(w) {
    return f(w) && !_(w);
  }
  function C(w) {
    return !!(i(w) && w.walk);
  }
  function v(w) {
    return a(w) || m(w);
  }
}), BP = Pe(function(r, e) {
  e.__esModule = !0, Object.keys(pe).forEach(function(t) {
    t === "default" || t === "__esModule" || t in e && e[t] === pe[t] || (e[t] = pe[t]);
  }), Object.keys(io).forEach(function(t) {
    t === "default" || t === "__esModule" || t in e && e[t] === io[t] || (e[t] = io[t]);
  }), Object.keys(no).forEach(function(t) {
    t === "default" || t === "__esModule" || t in e && e[t] === no[t] || (e[t] = no[t]);
  });
});
Pe(function(r, e) {
  e.__esModule = !0, e.default = void 0;
  var t = a(FP), s = n(BP);
  function i() {
    if (typeof WeakMap != "function")
      return null;
    var u = /* @__PURE__ */ new WeakMap();
    return i = function() {
      return u;
    }, u;
  }
  function n(u) {
    if (u && u.__esModule)
      return u;
    if (u === null || typeof u != "object" && typeof u != "function")
      return { default: u };
    var c = i();
    if (c && c.has(u))
      return c.get(u);
    var h = {}, f = Object.defineProperty && Object.getOwnPropertyDescriptor;
    for (var p in u)
      if (Object.prototype.hasOwnProperty.call(u, p)) {
        var x = f ? Object.getOwnPropertyDescriptor(u, p) : null;
        x && (x.get || x.set) ? Object.defineProperty(h, p, x) : h[p] = u[p];
      }
    return h.default = u, c && c.set(u, h), h;
  }
  function a(u) {
    return u && u.__esModule ? u : { default: u };
  }
  var o = function(c) {
    return new t.default(c);
  };
  Object.assign(o, s), delete o.__esModule;
  var l = o;
  e.default = l, r.exports = e.default;
});
const Aa = (r, e) => {
  const t = r.__vccOpts || r;
  for (const [s, i] of e)
    t[s] = i;
  return t;
}, UP = {
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
      default: (r) => {
        console.log("selectedPoint", r);
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
    selectPargoPoint(r) {
      r.data && r.data.pargoPointCode && this.selectedPargoPoint(r.data);
    }
  }
}, $P = { class: "p-a-map-container" }, jP = ["src"];
function qP(r, e, t, s, i, n) {
  return Tt(), mi("div", $P, [
    ss(Je("div", null, "Loading Pargo Map Locations...", 512), [
      [is, !i.loaded]
    ]),
    ss(Je("iframe", {
      id: "thePargoPageFrameID",
      src: i.src,
      width: "100%",
      height: "100%",
      allow: "geolocation *",
      name: "thePargoPageFrame",
      onLoad: e[0] || (e[0] = (...a) => n.load && n.load(...a))
    }, null, 40, jP), [
      [is, i.loaded]
    ])
  ]);
}
const VP = /* @__PURE__ */ Aa(UP, [["render", qP], ["__scopeId", "data-v-ca2cdc2f"]]);
const zP = {
  name: "PargoModal",
  methods: {
    close() {
      this.$emit("close");
    }
  }
}, WP = { class: "p-a-modal-backdrop" }, HP = {
  class: "p-a-modal",
  role: "dialog",
  "aria-labelledby": "modalTitle",
  "aria-describedby": "modalDescription"
}, KP = {
  class: "p-a-modal-header",
  id: "modalTitle"
}, GP = /* @__PURE__ */ Mi(" This is the default title! "), YP = {
  class: "p-a-modal-body",
  id: "modalDescription"
}, JP = /* @__PURE__ */ Mi(" This is the default body! "), QP = { class: "p-a-modal-footer" }, XP = /* @__PURE__ */ Mi(" This is the default footer! ");
function ZP(r, e, t, s, i, n) {
  return Tt(), fs(_l, { name: "p-a-modal-fade" }, {
    default: ei(() => [
      Je("div", WP, [
        Je("div", HP, [
          Je("header", KP, [
            La(r.$slots, "header", {}, () => [
              GP
            ]),
            Je("button", {
              type: "button",
              class: "btn-close",
              onClick: e[0] || (e[0] = (...a) => n.close && n.close(...a)),
              "aria-label": "Close modal"
            }, " x ")
          ]),
          Je("section", YP, [
            La(r.$slots, "body", {}, () => [
              JP
            ])
          ]),
          Je("footer", QP, [
            La(r.$slots, "footer", {}, () => [
              XP
            ])
          ])
        ])
      ])
    ]),
    _: 3
  });
}
const eE = /* @__PURE__ */ Aa(zP, [["render", ZP]]), tE = {
  name: "PargoStore",
  props: {
    point: {
      type: Object,
      default: {}
    }
  }
}, rE = { class: "pargo_style_title" }, sE = ["src"], iE = { class: "pargo_style_desc" };
function nE(r, e, t, s, i, n) {
  return Tt(), mi("div", null, [
    Je("p", rE, hi(`Selected Pickup Point: ${t.point.storeName}`), 1),
    t.point.photo ? (Tt(), mi("img", {
      key: 0,
      class: "pargo_style_image",
      src: t.point.photo
    }, null, 8, sE)) : Ys("", !0),
    Je("p", iE, hi(t.point.addressSms), 1)
  ]);
}
const xh = /* @__PURE__ */ Aa(tE, [["render", nE]]);
const aE = {
  name: "App",
  props: {
    type: ""
  },
  data() {
    return {
      renderMap: !0,
      status: "",
      token: "",
      urlEndPoint: "",
      selectedPoint: {},
      isModalVisible: !0
    };
  },
  mounted() {
    return Bi(this, null, function* () {
      yield fetch(`${OBJ.api_url}pargo/v1/get-pargo-settings`, {
        headers: {
          "Content-Type": "application/json",
          "X-WP-Nonce": OBJ.nonce
        }
      }).then((r) => r.json()).then((r) => {
        const { data: e } = r;
        e.pargo_url_endpoint ? this.urlEndPoint = e.pargo_url_endpoint : e.pargo_url.length > 0 && e.pargo_url_endpoint.length === 0 ? e.pargo_url.match("staging") && (this.urlEndPoint = "staging") : this.urlEndPoint = "production", this.token = e.pargo_map_token;
      });
    });
  },
  methods: {
    pointSelected(r) {
      return Bi(this, null, function* () {
        if (r.address1) {
          this.selectedPoint = r, this.status = "Setting Pargo Pickup Point", this.renderMap = !1;
          const e = new FormData();
          e.append("pargoshipping", JSON.stringify(this.selectedPoint)), e.append("action", "set_pick_up_point"), yield fetch(`${OBJ.ajax_url}`, {
            method: "POST",
            body: e,
            headers: {
              "X-WP-Nonce": OBJ.nonce
            }
          }).then((t) => t.json()).then((t) => {
            this.status = t.message, t.code === "error" && (this.selectedPoint = {}, this.status = t.message);
          }).finally(() => {
            if (this.isModalVisible = !1, Object.keys(this.selectedPoint).length > 0) {
              const t = Pf(xh, { point: this.selectedPoint }), s = document.getElementById("pargo-after-cart");
              s.innerHtml = "", t.mount(s);
            }
          });
        }
      });
    },
    closeModal() {
      this.isModalVisible = !this.isModalVisible;
    }
  },
  components: {
    PargoMap: VP,
    PargoModal: eE,
    PargoStore: xh
  }
}, oE = { class: "pargo_style_title" }, lE = { class: "pmap__renderMap" }, uE = /* @__PURE__ */ Mi(/* @__PURE__ */ hi(null)), cE = { key: 1 }, hE = { class: "pmap__inlineMap" };
function fE(r, e, t, s, i, n) {
  const a = Qu("PargoMap"), o = Qu("PargoModal");
  return Tt(), mi(lt, null, [
    ss(Je("div", { style: { padding: "0.5rem" } }, hi(i.status), 513), [
      [is, i.status]
    ]),
    t.type === "modal" ? ss((Tt(), fs(o, {
      key: 0,
      onClose: n.closeModal
    }, {
      header: ei(() => [
        Je("span", oE, hi(i.renderMap ? "Select a Pickup Point" : `Selected Pickup Point: ${i.selectedPoint.storeName}`), 1)
      ]),
      body: ei(() => [
        ss(Je("div", lE, [
          this.token ? (Tt(), fs(a, {
            key: 0,
            mapToken: this.token,
            urlEndPoint: this.urlEndPoint,
            selectedPargoPoint: this.pointSelected
          }, null, 8, ["mapToken", "urlEndPoint", "selectedPargoPoint"])) : Ys("", !0)
        ], 512), [
          [is, i.renderMap]
        ])
      ]),
      footer: ei(() => [
        uE
      ]),
      _: 1
    }, 8, ["onClose"])), [
      [is, i.isModalVisible]
    ]) : Ys("", !0),
    t.type ? Ys("", !0) : (Tt(), mi("div", cE, [
      ss(Je("div", hE, [
        this.token ? (Tt(), fs(a, {
          key: 0,
          mapToken: this.token,
          urlEndPoint: this.urlEndPoint,
          selectedPargoPoint: this.pointSelected
        }, null, 8, ["mapToken", "urlEndPoint", "selectedPargoPoint"])) : Ys("", !0)
      ], 512), [
        [is, i.renderMap]
      ])
    ]))
  ], 64);
}
const pE = /* @__PURE__ */ Aa(aE, [["render", fE]]);
jQuery(document).ready(function(r) {
  function e(i) {
    const n = Pf(pE, { type: i });
    let a = document.getElementById("pargo-modal");
    i !== "modal" && (a = document.getElementById("pargo-after-cart")), a.innerHTML = "";
    let o = document.createElement("div");
    a.appendChild(o), n.mount(o);
  }
  window.mountPargoApp = e;
  let t = !1;
  r("form.checkout").on("change", 'input[name^="shipping_method"]', function() {
    const i = r(this).val();
    (i == "wp_pargo" || i == "wp_pargo_home") && (t = !0);
  });
  const s = r('input[name^="shipping_method"]');
  for (let i = 0; i < s.length; i++)
    s[i].checked === !0 && s[i].value === "wp_pargo" && r("#ship-to-different-address").hide();
  r("body").on("updated_checkout", function() {
    t && location.reload();
  });
});
