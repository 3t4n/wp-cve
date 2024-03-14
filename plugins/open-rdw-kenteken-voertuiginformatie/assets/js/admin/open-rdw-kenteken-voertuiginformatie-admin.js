(function ($) {
  "use strict";

  $(window).load(function () {
    /**
     * Enables us to toggle the field categories
     */
    $(document).on("click", ".rdw-expand-fields a", function (e) {
      e.preventDefault();
      // $(this).parent().siblings().find('ul').slideUp();
      $(this).next().slideToggle("fast", "linear");
    });

    /**
     * Switches to clicked tab in the back-end when clicked.
     */
    $(document).on("click", ".van-tabs li", function (e) {
      e.preventDefault();
      var target = this;

      if (!$(this).hasClass("active")) {
        $($(".active").find("a").attr("href")).fadeOut(
          "fast",
          "linear",
          function () {
            $($(target).find("a").attr("href")).fadeIn("fast", "linear");
            $(".active").removeClass("active");
            $(target).addClass("active");
          }
        );
      }
    });

    /**
     * Makes an ajax call to the backend, letting it know the open-rdw-notice
     * is dismissed and should be saved as dismissed.
     */
    $(document).on("click", ".open-rdw-notice .notice-dismiss", function () {
      $.ajax({
        url: ajaxurl,
        data: {
          action: "open-rdw-notice-dismiss",
        },
      });
    });

    $(document).on(
      "click",
      ".rdw-shortcode-box-content .rdw-sort-fields .checkbox-field",
      function () {
        var content = "";
        var rdw_fields = [];

        $(".rdw-shortcode-box-content .rdw-sort-fields input:checked").map(
          function () {
            var value = $(this).attr("name");
            if (typeof value !== "undefined") {
              rdw_fields.push(value);
            }
          }
        );
        if (rdw_fields.length > 0) {
          content = '[open_rdw_check "' + rdw_fields.join('" "') + '"]';
        } else {
          content = "[open_rdw_check]";
        }

        $(".generated-shortcode-text").val(content);
      }
    );

    $(document).on("click", ".generated-shortcode-text", function (e) {
      var copyText = $(".generated-shortcode-text");
      copyText.select();
      copyText[0].setSelectionRange(0, 99999);
      navigator.clipboard.writeText(copyText.val());

      $(".copy-tooltip").show(100);
      setTimeout(function () {
        $(".copy-tooltip").hide(100);
      }, 1000);
    });
  });
})(jQuery);
