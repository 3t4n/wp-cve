var isEditMode = !1,
    breakpoints = eae.breakpoints;
 ! function(e) {
    e(window).on("elementor/frontend/init", (function() {
      elementorFrontend.hooks.addAction("frontend/element_ready/global", (function(e, a) {
          isEditMode || e.data("ecsl-url") && "yes" == e.data("ecsl-link") && e.on("click", (function(a) {
              e.data("ecsl-url") && "yes" == e.data("ecsl-new-window") ? window.open(e.data("ecsl-url")) : location.href = e.data("ecsl-url")
          }))
      }))


  }))
}(jQuery);
