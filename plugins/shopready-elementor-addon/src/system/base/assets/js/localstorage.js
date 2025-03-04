"use strict";
window.XdUtils = window.XdUtils || function () {
  function a(a, b) {
    var c, d = b || {};
    for (c in a) a.hasOwnProperty(c) && (d[c] = a[c]);
    return d
  }
  return {
    extend: a
  }
}(), window.xdLocalStorage = window.xdLocalStorage || function () {
  function a(a) {
    k[a.id] && (k[a.id](a), delete k[a.id])
  }

  function b(b) {
    var c;
    try {
      c = JSON.parse(b.data)
    } catch (a) {}
    c && c.namespace === h && ("iframe-ready" === c.id ? (m = !0, i.initCallback()) : a(c))
  }

  function c(a, b, c, d) {
    j++, k[j] = d;
    var e = {
      namespace: h,
      id: j,
      action: a,
      key: b,
      value: c
    };
    
    g.contentWindow.postMessage(JSON.stringify(e), "*")
  }

  function d(a) {
    i = XdUtils.extend(a, i);
    var c = document.createElement("div");
    window.addEventListener ? window.addEventListener("message", b, !1) : window.attachEvent("onmessage", b), c.innerHTML = '<iframe id="' + i.iframeId + '" src=' + i.iframeUrl + ' style="display: none;"></iframe>', document.body.appendChild(c), g = document.getElementById(i.iframeId)
  }

  function e() {
    return l ? !!m || (console.log("You must wait for iframe ready message before using the api."), !1) : (console.log("You must call xdLocalStorage.init() before using it."), !1)
  }

  function f() {
    return "complete" === document.readyState
  }
  var g, h = "cross-domain-element",
    i = {
      iframeId: "cross-domain-iframe",
      iframeUrl: void 0,
      initCallback: function () {}
    },
    j = -1,
    k = {},
    l = !1,
    m = !0;
  return {
    init: function (a) {
      if (!a.iframeUrl) throw "You must specify iframeUrl";
      if (l) return void console.log(" was already initialized!");
      l = !0, f() ? d(a) : document.addEventListener ? document.addEventListener("readystatechange", function () {
        f() && d(a)
      }) : document.attachEvent("readystatechange", function () {
        f() && d(a)
      })
    },
    setItem: function (a, b, d) {
      e() && c("set", a, b, d)
    },
    getItem: function (a, b) {
      e() && c("get", a, null, b)
    },
    removeItem: function (a, b) {
      e() && c("remove", a, null, b)
    },
    key: function (a, b) {
      e() && c("key", a, null, b)
    },
    getSize: function (a) {
      e() && c("size", null, null, a)
    },
    getLength: function (a) {
      e() && c("length", null, null, a)
    },
    clear: function (a) {
      e() && c("clear", null, null, a)
    },
    wasInit: function () {
      return l
    }
  }
}();