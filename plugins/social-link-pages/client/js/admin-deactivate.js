jQuery(function ($) {
  $(social_link_pages.form_deactivate).appendTo("body");

  var $a = $("#deactivate-social-link-pages");

  var url = $a.attr("href") + "";

  $a.attr(
    "href",
    social_link_pages.url_plugins +
      "#TB_inline?inlineId=social_link_pages-deactivate-modal&modal=true"
  ).addClass("thickbox");

  $a.on("click", function () {
    setTimeout(function () {
      $("#TB_ajaxContent").css({
        height: "auto",
        width: "auto",
      });
      $("#TB_window").css({
        height: "auto",
      });
    }, 300);
  });

  $("body").on("click", ".social_link_pages-deactivate-remove", function () {
    tb_remove();
  });

  $("body").on("click", "#social_link_pages-deactivate-skip", function () {
    window.location = url;
  });

  $("body").on("click", "#social_link_pages-deactivate-submit", function () {
    var data = $("#social_link_pages-deactivate-form").serializeArray();

    $.ajax({
      method: "POST",
      url: ajaxurl,
      data: data,
    }).always(function (response) {
      window.location = url;
    });
  });

  $('[name="reason"]').on("change", function () {
    $(".social_link_pages-deactivate-textarea").hide();
    $(this)
      .closest(".social_link_pages-deactivate-choice")
      .find(".social_link_pages-deactivate-textarea")
      .show()
      .find("textarea")
      .focus();
  });
});
