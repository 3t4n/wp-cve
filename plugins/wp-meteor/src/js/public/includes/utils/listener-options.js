const listenerOptions = {};
((w, p) => {
  try {
    const opts = Object.defineProperty({}, p, {
      get: function() {
        return listenerOptions[p] = true;
      }
    });
    w.addEventListener(p, null, opts);
    w.removeEventListener(p, null, opts);
  } catch (e) {
  }
})(window, "passive");
export default listenerOptions;
//# sourceMappingURL=listener-options.js.map
