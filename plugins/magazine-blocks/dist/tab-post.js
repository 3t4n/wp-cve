/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
function _createForOfIteratorHelper(o, allowArrayLike) { var it = typeof Symbol !== "undefined" && o[Symbol.iterator] || o["@@iterator"]; if (!it) { if (Array.isArray(o) || (it = _unsupportedIterableToArray(o)) || allowArrayLike && o && typeof o.length === "number") { if (it) o = it; var i = 0; var F = function F() {}; return { s: F, n: function n() { if (i >= o.length) return { done: true }; return { done: false, value: o[i++] }; }, e: function e(_e) { throw _e; }, f: F }; } throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); } var normalCompletion = true, didErr = false, err; return { s: function s() { it = it.call(o); }, n: function n() { var step = it.next(); normalCompletion = step.done; return step; }, e: function e(_e2) { didErr = true; err = _e2; }, f: function f() { try { if (!normalCompletion && it["return"] != null) it["return"](); } finally { if (didErr) throw err; } } }; }
function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }
function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) arr2[i] = arr[i]; return arr2; }
var siblings = function siblings(el) {
  if (el.parentNode === null) return [];
  return Array.prototype.filter.call(el.parentNode.children, function (child) {
    return child !== el;
  });
};
var initTab = function initTab() {
  var magazineTabs = document.querySelectorAll(".mzb-tab-post");
  if (!(magazineTabs !== null && magazineTabs !== void 0 && magazineTabs.length)) {
    return;
  }
  var _iterator = _createForOfIteratorHelper(magazineTabs),
    _step;
  try {
    var _loop = function _loop() {
        var magazineTab = _step.value;
        var tabs = magazineTab && magazineTab.querySelectorAll(".mzb-tab-title");
        if (!(tabs !== null && tabs !== void 0 && tabs.length)) {
          return {
            v: void 0
          };
        }
        var _iterator2 = _createForOfIteratorHelper(tabs),
          _step2;
        try {
          var _loop2 = function _loop2() {
            var tab = _step2.value;
            tab.addEventListener("click", function (e) {
              var _siblings, _sibling$classList;
              var sibling = (_siblings = siblings(tab)) === null || _siblings === void 0 ? void 0 : _siblings[0];
              magazineTab.setAttribute("data-active-tab", e.target.getAttribute("data-tab") || "");
              tab.classList.add("active");
              sibling === null || sibling === void 0 || (_sibling$classList = sibling.classList) === null || _sibling$classList === void 0 || _sibling$classList.remove("active");
            });
          };
          for (_iterator2.s(); !(_step2 = _iterator2.n()).done;) {
            _loop2();
          }
        } catch (err) {
          _iterator2.e(err);
        } finally {
          _iterator2.f();
        }
      },
      _ret;
    for (_iterator.s(); !(_step = _iterator.n()).done;) {
      _ret = _loop();
      if (_ret) return _ret.v;
    }
  } catch (err) {
    _iterator.e(err);
  } finally {
    _iterator.f();
  }
};
initTab();
/******/ })()
;