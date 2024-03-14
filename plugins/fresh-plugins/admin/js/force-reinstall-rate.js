(function ($) {
  "use strict";

  $(document).ready(function () {
    $(
      "." +
        window.force_reinstall.name +
        " a.btn-rate, ." +
        window.force_reinstall.name +
        " a.btn-remind, ." +
        window.force_reinstall.name +
        " a.btn-cancel"
    ).click(function (e) {
      e.preventDefault();

      $.post({
        url: window.force_reinstall.ajax_url,
        data: {
          _ajax_nonce: $(this).data("nonce"),
          action: window.force_reinstall.name + "-" + $(this).data("action"),
        },
        async: false,
        success: function (response) {
          if (response.redirect) {
            window.open(response.redirect, "_blank").focus();
          }
          $("." + window.force_reinstall.name + " .notice-dismiss").click();
        },
      });
    });
  });
})(jQuery);
