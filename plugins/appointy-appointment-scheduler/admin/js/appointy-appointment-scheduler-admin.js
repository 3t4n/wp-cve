jQuery(document).ready(function () {
  jQuery("#setup_btn").click(function () {
    jQuery("#pluginSetupSection").hide();
    jQuery("#formPage").show();
  });
  jQuery("#changeButton").click(function () {
    if (jQuery("#AppointyUrlInput").prop("disabled")) {
      jQuery("#AppointyUrlInput").prop("disabled", false);
      jQuery("#changeButton").html("Save");
    } else {
      jQuery("#form-submit").click();
      UpdateAdvanceUrl();
      jQuery("#AppointyUrlInput").prop("disabled", true);
      jQuery("#changeButton").html("Change");
    }
  });

  jQuery(function () {
    if (jQuery("#bookingWidgetSelect").val() != "website") {
      jQuery("#heightWidthInput").hide();
    }
    jQuery("#bookingWidgetSelect").change(function () {
      var pluginurl = jQuery("#websiteGif").attr("srcurl");

      if (jQuery("#bookingWidgetSelect").val() == "website") {
        jQuery("#heightWidthInput").show();
        jQuery("#bookingPageGif").show();
        jQuery("#websiteGif").prop("src", pluginurl + "/img/overlay-demo.gif");
        // jQuery("#websiteGif").hide();
        jQuery("#instruction-sec").hide(); // change

      } else {
        jQuery("#heightWidthInput").hide();
        jQuery("#websiteGif").show();
        jQuery("#bookingPageGif").hide();
        jQuery("#websiteGif").prop("src", pluginurl + "/img/website.gif");
        jQuery("#instruction-sec").show(); // change
      }
    });
  });

  window.addEventListener("message", (e) => {
    // console
    const d = e.data || {};
    // console.log(d)
    if (d.type === "appointy_signup") {
      // console.log(d)
      handlePostMessage(d.data);
      jQuery("#click-to-setup-page").click();
      jQuery("#AppointyUrlInput").val(d.data);
      jQuery("#form-submit").click();
      jQuery("#myModal").modal("toggle");
      showSignupSetupStatus(true);
      setTimeout(function () {
        showSignupSetupStatus(true);
      }, 1000);
    }
  });

  function checkpage() {
    if (showConfigurationPage) {
      jQuery("#plugin-setup-page").show();
      jQuery("#signup-page").hide();
    } else {
      jQuery("#signup-page").show();
      jQuery("#plugin-setup-page").hide();
    }
  }

  function showSignupSetupStatus(flag) {
    if (flag) {
      jQuery("#signup-setup-warning").show();
    } else {
      jQuery("#signup-setup-warning").hide();
    }
  }

  showSignupSetupStatus(flag);

  checkpage();

  jQuery("#click-to-setup-page").click(function () {
    jQuery("#signup-page").hide();
    jQuery("#plugin-setup-page").show();
  });

  jQuery("#form-submit").click(function () {
    let url = jQuery("#AppointyUrlInput").val();
    let lang = jQuery("#languageSelect").val();
    let maxWidth = jQuery("#max-width").val() + jQuery("#maxWidthUnit").val();
    let maxHeight =
      jQuery("#max-height").val() + jQuery("#maxHeightUnit").val();
    let widget = jQuery("#bookingWidgetSelect").val();

    jQuery.ajax({
      url: ajaxurl,
      type: "post",
      data: {
        action: "handle_setting_change_callback",
        code: url,
        lang: lang,
        maxWidth: maxWidth,
        maxHeight: maxHeight,
        widget: widget,
      },
      success: function (data) {
        // This outputs the result of the ajax request
        showSuccessMsge();
        console.log(data);
      },
      error: function (errorThrown) {
        console.log(errorThrown);
      },
    });
  });

  var handleUrlChange = function () {
    let url = jQuery("#AppointyUrlInput").val();

    jQuery.ajax({
      url: ajaxurl,
      type: "post",
      data: {
        action: "handle_url_update",
        code: url,
      },
      success: function (data) {
        // This outputs the result of the ajax request
        console.log(data);
      },
      error: function (errorThrown) {
        console.log(errorThrown);
      },
    });
  };


  jQuery("#myModalOpen").click(function () {
    
    // jQuery("#signupFram").attr('src', 'https://business.appointy.com/account/register?isgadget=1&wordpress=1&utm_source=wp_plugin_dashboard&utm_medium=btn_setup_plugin&utm_campaign=wp_plugin_dashboard_signups');
    jQuery('#signupFram').html('<iframe  style="width: 100%; border: 0px; height: 390px" src="https://business.appointy.com/account/register?isgadget=1&wordpress=1&utm_source=wp_plugin_dashboard&utm_medium=btn_setup_plugin&utm_campaign=wp_plugin_dashboard_signups"></iframe>');
  });
});

function handlePostMessage(url) {
  //alert(url)
  jQuery.ajax({
    url: ajaxurl,
    type: "post",
    data: {
      action: "handle_appointy_post_message_callback",
      code: url,
    },
    success: function (data) {
      // This outputs the result of the ajax request
      console.log(data);
    },
    error: function (errorThrown) {
      console.log(errorThrown);
    },
  });
}
function UpdateAdvanceUrl() {
  var url = jQuery("#AppointyUrlInput").val();
  var lang = jQuery("#languageSelect").val();
  var wd = jQuery("#max-width").val() + jQuery("#maxWidthUnit").val();
  var ht = jQuery("#max-height").val() + jQuery("#maxHeightUnit").val();
  var dis = jQuery("#bookingWidgetSelect").val();

  // jQuery("#collapseOne").collapse("show");
  jQuery("#iframeCodeArea").html(
    `${url}?lang=${lang}&maxWidth=${wd}&maxHeight=${ht}&widget=${dis}`
  );
}

function showSuccessMsge() {
  jQuery("#successfullyMsg").show();
  setTimeout(function () {
    jQuery("#successfullyMsg").hide();
  }, 5000);
}
