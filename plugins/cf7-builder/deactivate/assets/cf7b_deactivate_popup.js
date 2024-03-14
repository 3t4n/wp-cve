var deactivated = false;
var additionalInfo = "";
var btnVal = 3;

function cf7bReady() {
  var agree_with_pp = false;
  reset_popup();
  jQuery(document).on("click", "#" + cf7b_WDDeactivateVars.deactivate_class, function () {
    agree_with_pp = false;
    if (!jQuery('#cf7b-submit-and-deactivate').hasClass('button-primary-disabled')) {
      jQuery('#cf7b-submit-and-deactivate').addClass('button-primary-disabled')
    }
    jQuery(".cf7b-opacity").show();
    jQuery(".cf7b-deactivate-popup").show();
    if (jQuery(this).attr("data-uninstall") == "1") {
      btnVal = 2;
    }
    return false;
  });
  jQuery(document).on("change", "[name=cf7b_reasons]", function () {
    jQuery(".cf7b_additional_details_wrap").html("");
    jQuery(".cf7b-deactivate-popup").removeClass("cf7b-popup-active1 cf7b-popup-active2 cf7b-popup-active4");
    if (jQuery(this).val() == "reason_plugin_is_hard_to_use_technical_problems") {
      additionalInfo = '<div class="cf7b-additional-active"><div><strong>Please describe your issue.</strong></div><br>' +
        '<textarea name="cf7b_additional_details" rows = "4"></textarea><br>' +
        '<div>Our support will contact <input type="text" name="cf7b_email" value="' + cf7b_WDDeactivateVars.email + '"> shortly.</div></div>';
      jQuery(".cf7b_additional_details_wrap").append(additionalInfo);
      jQuery(".cf7b-deactivate-popup").addClass("cf7b-popup-active1");
    }
    else {
      jQuery(".cf7b-deactivate-popup").addClass("cf7b-popup-active4");
    }
    jQuery("#cf7b-submit-and-deactivate").show();
    jQuery("#cf7b-submit-and-deactivate").removeClass('button-primary-disabled');
  });
  jQuery(document).on("keyup", "[name=cf7b_additional_details]", function () {
    if (jQuery(this).val().trim() || jQuery("[name=cf7b_reasons]:checked").length > 0) {
      jQuery("#cf7b-submit-and-deactivate").show();
      jQuery("#cf7b-submit-and-deactivate").removeClass('button-primary-disabled');
    }
    else {
      jQuery("#cf7b-submit-and-deactivate").hide();
    }
  });
  jQuery(document).on("click", ".cf7b-deactivate", function (e) {
    jQuery(".cf7b-deactivate-popup-opacity-cf7b").show();
    if (jQuery(this).hasClass("cf7b-clicked") == false) {
      jQuery(this).addClass("cf7b-clicked");
      jQuery("[name=cf7b_submit_and_deactivate]").val(jQuery(this).attr("data-val"));
      jQuery("#cf7b_deactivate_form").submit();
    }
    return false;
  });
  jQuery(document).on("click", ".cf7b-cancel, .cf7b-opacity, .cf7b-deactivate-popup-close-btn", function () {
    jQuery(".cf7b-opacity").hide();
    jQuery(".cf7b-deactivate-popup").hide();
    reset_popup();
    return false;
  });

  function reset_popup() {
    jQuery(".cf7b_additional_details_wrap").html("");
    jQuery(".cf7b-deactivate-popup").removeClass("cf7b-popup-active1 cf7b-popup-active2 cf7b-popup-active4");
    jQuery("#cf7b-submit-and-deactivate").hide();
    jQuery('#cf7b_deactivate_form input[name="cf7b_reasons"]').prop('checked', false);
  }
}