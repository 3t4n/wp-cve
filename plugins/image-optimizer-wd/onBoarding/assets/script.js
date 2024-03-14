jQuery(document).ready(function(){
  /* Add first gallery click */
  jQuery(document).on("click", "#iowd_add_first_gallery", function() {
    iowd_add_first_gallery(this);
  });

  /* Step change click which is worked from "Got it" or "Skip sign up" */
  jQuery(document).on("click", '.iowd_onboarding_step_change', function() {
    iowd_onboarding_step_change(this);
  });

  /* Sign up click */
  jQuery(document).on("click", '.iowd_onboarding_signup_button', function() {
    iowd_install_booster_plugin(this);
  });
  jQuery(document).on("click",'.iowd_locked_features_signup_button', function() {
    iowd_install_booster_plugin(this, 'locked_features');
  });
});

/* Step change */
function iowd_onboarding_step_change(that) {
  var onboarding_step = jQuery(that).data("onboarding_step");
  jQuery(".iowd_onboarding_button.iowd_onboarding_step_change").empty().append("<span></span>").addClass("iowd_onboarding_button_loading");
  jQuery.ajax( {
    url: ajaxurl,
    type: "POST",
    data: {
      action: "onboarding_step_change",
      task: "onboarding_step_change",
      onboarding_step: onboarding_step,
      iowd_nonce: onboarding.iowd_nonce
    },
    success: function (response) {
      if ( onboarding_step == 'skipped' ) {
        window.location.href = onboarding.dashboard_page;
        return;
      } else if ( onboarding_step == 'done' && response['data']['status'] == 'success' ) {
        window.location.href = response['data']['booster_connect_url'];
        return;
      }
      if ( response.indexOf('two-container') > -1 ) {
        iowd_onboarding_step_change(that);
        return;
      }
      jQuery(document).find(".iowd_onboarding_container").replaceWith(response);
    },
    error: function() {
      window.location.href = onboarding.onboarding_page;
    },
  });
}

/**
 * Install/activate the plugin.
 *
 * @param that object
 */
function iowd_install_booster_plugin( that, $flow = '' ) {
  var iowd_error = jQuery(".iowd_error");
  iowd_error.addClass("iowd_hidden");
  if ( jQuery(that).hasClass("_iowd-disable-link") ) {
    return;
  }

  jQuery(".iowd_onboarding_button").empty().append("<span></span>").addClass("iowd_onboarding_button_loading");

  jQuery.ajax( {
    url: ajaxurl,
    type: "POST",
    data: {
      action: "iowd_install_booster",
      task: "iowd_install_booster",
      speed_ajax_nonce: onboarding.speed_ajax_nonce
    },
    success: function() {
      if ( $flow != '' ) {
      window.location.href = onboarding.booster_connect_page;
      } else {
        iowd_onboarding_step_change(jQuery(".iowd_onboarding_button"));
      }
    },
    error: function() {
      jQuery(that).removeClass('iowd-disable-link');
      jQuery(".iowd_error").text(onboarding.something_wrong).removeClass("iowd_hidden");
      jQuery(".iowd_onboarding_button").removeClass("iowd_onboarding_button_loading");
      if ( $flow != '' ) {
        jQuery(".iowd_onboarding_button").text(onboarding.optimize);
      } else {
        jQuery(".iowd_onboarding_button").text(onboarding.install);
      }
    },
  });
}