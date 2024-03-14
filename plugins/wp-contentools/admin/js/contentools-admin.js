(function ($) {
  "use strict";

  /**
   * All of the code for your admin-facing JavaScript source
   * should reside in this file.
   *
   * Note: It has been assumed you will write jQuery code here, so the
   * $ function reference has been prepared for usage within the scope
   * of this function.
   *
   * This enables you to define handlers, for when the DOM is ready:
   *
   * $(function() {
   *
   * });
   *
   * When the window is loaded:
   *
   * $( window ).load(function() {
   *
   * });
   *
   * ...and/or other possibilities.
   *
   * Ideally, it is not considered best practise to attach more than a
   * single DOM-ready or window-load handler for a particular page.
   * Although scripts in the WordPress core, Plugins and Themes may be
   * practising this, we should strive to set a better example in our own work.
   */

  function generateHash(s) {
    return rstr2b64(hex_md5(hex_md5(s)));
  }

  $(document).on("click", "#contentools-generate-token", function () {
    var random = Math.random(11111111111111111111, 99999999999999999999);
    var d = new Date();
    var token = "";
    token = token.concat(
      random,
      d.getHours(),
      d.getMinutes(),
      d.getSeconds(),
      d.getHours(),
      random,
      d.getMinutes(),
      d.getDay(),
      d.getSeconds(),
      random
    );
    $("#contentools-token").val(generateHash(token));
  });

  $(document).on("click", "#contentools-clear-token", function () {
    $("#contentools-token").val("");
  });

  $(document).on("click", "#contentools-copy-token", function () {
    navigator.clipboard.writeText($("#contentools-token").val());
  });
})(jQuery);
