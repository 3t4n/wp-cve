jQuery(document).ready(function () {
  "use strict";
  jQuery(document).on("click", "#subscribe_home_btn", function (e) {
    e.preventDefault();
    var email = jQuery("#subscribe_mail").val();
    var nounce = ajax_subscribe.subscribe_nonce;
    jQuery.ajax({
      url: ajax_subscribe.ajax_url,
      type: "POST",
      data: {
        action: "wlc_subscribe_form",
        email: email,
        nounce: nounce,
      },
      success: function (response) {
        if (response) {
          if (response.status == "success") {
            alert(response.message);
          } else {
            alert(response.message);
          }
        }
      },
    });
  });
});
