(function ($) {
  $(document).ready(function () {
    $(document).on("click", ".fpdf_import_data", function (e) {
      e.preventDefault();
      $.ajax({
        url: fpdfAdmin.ajaxUrl,
        data: {
          action: "fpdf_import_data",
        },
        success: function (data) {
          const result = JSON.parse(data);
          if (result.success === true) {
            location.href = location.href + "?fpdf-import=success";
          }
        },
      });
    });

    $(".fpdf_import_notice").on("click", function () {
      setCookie("fpdf_import_notice", "1", 17280000);
    });

    function setCookie(cookieName, cookieValue, expiryInSeconds) {
      var expiry = new Date();
      expiry.setTime(expiry.getTime() + 1000 * expiryInSeconds);
      document.cookie = cookieName + "=" + escape(cookieValue) + ";expires=" + expiry.toGMTString() + ";path=/";
    }
  });
})(jQuery);
