try {
  (() => {
  })();
  new MutationObserver(function() {
  });
  new PerformanceObserver(function() {
  });
  Object.assign({}, {});
  document.fonts.ready.then(function() {
  });
} catch (e) {
  var replacement = "wpmeteordisable=1";
  var href = document.location.href;
  if (!href.match(/[?&]wpmeteordisable/)) {
    var nhref = "";
    if (href.indexOf("?") == -1) {
      if (href.indexOf("#") == -1) {
        nhref = href + "?" + replacement;
      } else {
        nhref = href.replace("#", "?" + replacement + "#");
      }
    } else {
      if (href.indexOf("#") == -1) {
        nhref = href + "&" + replacement;
      } else {
        nhref = href.replace("#", "&" + replacement + "#");
      }
    }
    document.location.href = nhref;
  }
}
//# sourceMappingURL=ie-redirect.js.map
