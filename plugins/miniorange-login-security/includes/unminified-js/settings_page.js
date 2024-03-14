jQuery(document).ready(function () {
  $ = jQuery;

  //show and hide instructions
  $("#auth_help").click(function () {
    $("#auth_troubleshoot").toggle();
  });
  $("#conn_help").click(function () {
    $("#conn_troubleshoot").toggle();
  });

  $("#conn_help_user_mapping").click(function () {
    $("#conn_user_mapping_troubleshoot").toggle();
  });

  //show and hide attribute mapping instructions
  $("#toggle_am_content").click(function () {
    $("#show_am_content").toggle();
  });

  //Instructions
  $("#momls_wpns_help_curl_title").click(function () {
    $("#momls_wpns_help_curl_desc").slideToggle(400);
  });

  $("#momls_wpns_help_mobile_auth_title").click(function () {
    $("#momls_wpns_help_mobile_auth_desc").slideToggle(400);
  });

  $("#momls_wpns_help_disposable_title").click(function () {
    $("#momls_wpns_help_disposable_desc").slideToggle(400);
  });

  $("#momls_wpns_help_strong_pass_title").click(function () {
    $("#momls_wpns_help_strong_pass_desc").slideToggle(400);
  });

  $("#momls_wpns_help_adv_user_ver_title").click(function () {
    $("#momls_wpns_help_adv_user_ver_desc").slideToggle(400);
  });

  $("#momls_wpns_help_social_login_title").click(function () {
    $("#momls_wpns_help_social_login_desc").slideToggle(400);
  });

  $("#momls_wpns_help_custom_template_title").click(function () {
    $("#momls_wpns_help_custom_template_desc").slideToggle(400);
  });

  $(".feedback").click(function () {
    ajaxCall("dissmissfeedback", ".feedback-notice", true);
  });
});

function ajaxCall(option, element, hide) {
  var data = {
    action: "momls_two_factor_ajax",
    momls_2f_two_factor_ajax: "momls_dismiss_button",
  };

  jQuery.post(ajaxurl, data, function (response) {
    var response = response.replace(/\s+/g, " ").trim();
    if (response) {
      jQuery(".feedback-notice").hide();
    }
  });
}
