jQuery(document).ready(function ($) {
  $(function () {
    var hash = window.location.href.split("#")[1];
    hash && $(".mega-elements-tab-content").removeClass("active");
    $(".mega-elements-content-wrap")
      .children("." + hash + "-content")
      .addClass("active");
    $("button.mega-elements-tab").removeClass("active");
    $("button.mega-elements-tab." + hash).addClass("active");
  });
  $(
    "a[href='admin.php?page=mega-elements#mega-elements-general'],a[href='admin.php?page=mega-elements#mega-elements-widgets']"
  ).on("click", function (e) {
    e.preventDefault();
    hash = $(this).attr("href").split("#")[1];
    hash && $(".mega-elements-tab-content").removeClass("active");
    $(".mega-elements-content-wrap")
      .children("." + hash + "-content")
      .addClass("active");
    $("button.mega-elements-tab").removeClass("active");
    $("button.mega-elements-tab." + hash).addClass("active");
  });
  $(".mega-elements-tab").on("click", function () {
    var elementoUC = $(this).attr("class").split(" ")[1];

    $(".mega-elements-tab").removeClass("active");
    $(this).addClass("active");

    $(".mega-elements-tab-content").removeClass("active");
    $(".mega-elements-content-wrap")
      .children("." + elementoUC + "-content")
      .addClass("active");
  });
  $(".mega-elements-btn.btn-enable").on("click", function (event) {
    event.preventDefault();
    $(".mega-elements-widget-list .mega-elements-widget-chckbx").prop(
      "checked",
      true
    );
  });
  $(".mega-elements-btn.btn-orange").on("click", function (event) {
    event.preventDefault();
    $(".mega-elements-widget-list .mega-elements-widget-chckbx").prop(
      "checked",
      false
    );
  });

  $("#mega-elements-elem-all-widgtsdata").on("submit", function (e) {
    e.preventDefault();
    var selectedWidgets = new Array();
    $("#mega-elements-elem-all-widgtsdata input:checked").each(function () {
      selectedWidgets.push($(this).val());
    });
    var variables = {
      data: selectedWidgets,
      nonce: MegaElementsAddons.nonce,
      action: "ewfe_save_dashboard",
    };
    $.ajax({
      url: MegaElementsAddons.ajaxUrl,
      data: variables,
      type: "post",
      dataType: "json",
      success: function (response) {
        if (response.success) {
          Swal.fire({
            title: MegaElementsAddons.settings_success.title,
            text: MegaElementsAddons.settings_success.message,
            icon: "success",
            confirmButtonText: "Cool",
            customClass: {
              confirmButton: "mega-elements-btn",
            },
          });
        } else {
          Swal.fire(
            MegaElementsAddons.settings_fail.title,
            MegaElementsAddons.settings_fail.message,
            "error"
          );
        }
      },
    });
  });
}); //document close
