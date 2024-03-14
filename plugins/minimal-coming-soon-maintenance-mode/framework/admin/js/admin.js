/**
 * application.js
 * -----------------------------------------------------
 * all the js magic we need!
 */

var csmm_chat_loaded = false;

function reloadFont($fontValue) {
  WebFont.load({
    bunny: {
      families: [$fontValue],
    },
  });
}

function changeFont($font) {
  var $fontValue = $font.val();

  reloadFont($fontValue);
  $font.parent().find("h3").css("font-family", $fontValue);
}

// upload function
function getUploader($text, $target) {
  var custom_uploader;

  // If the uploader object has already been created, reopen the dialog
  if (custom_uploader) {
    custom_uploader.open();
    return;
  }

  // Extend the wp.media object
  custom_uploader = wp.media.frames.file_frame = wp.media({
    title: $text,
    button: {
      text: $text,
    },
    multiple: false,
  });

  // When a file is selected, grab the URL and set it as the text field's value
  custom_uploader.on("select", function () {
    var attachment = custom_uploader.state().get("selection").first().toJSON();

    $target.parent().find("input").val(attachment.url);
    $target
      .parent()
      .find(".signals-preview-area")
      .html('<img src="' + attachment.url + '" />');
    $target
      .parent()
      .find(".signals-upload-append")
      .html(
        '&nbsp;<a href="javascript: void(0);" class="signals-remove-image">Remove</a>'
      );
  });

  // Open the uploader dialog
  custom_uploader.open();
}

function csmm_change_tab(tab_name) {
  tab_name = "#" + tab_name.replace("#", "");

  jQuery('.signals-main-menu li a[href="' + tab_name + '"]').trigger("click");
  window.scrollTo(0, 0);
} // csmm_change_tab

function getMMUploader($text, $target) {
  var html =
    '<div class="csmm-image-picker"><div class="csmm-image-picker-title"><h1>' +
    $text +
    '</h1></div><button type="button" class="media-modal-close"><span class="media-modal-icon"><span class="screen-reader-text">Close media panel</span></span></button>';
  html += '<div class="csmm-image-picker-content">';
  for (i in mm_js_vars.mm_images) {
    if (mm_js_vars.mm_images[i].substring(0, 3) == "ad_") {
      html +=
        '<div class="csmm-image-picker-image_ad"><img src="' +
        mm_js_vars.mm_base_url +
        "/framework/admin/img/" +
        mm_js_vars.mm_images[i] +
        '" /></div>';
    } else {
      html +=
        '<div class="csmm-image-picker-image" data-image="' +
        mm_js_vars.mm_url +
        mm_js_vars.mm_images[i] +
        '"><img src="' +
        mm_js_vars.mm_url +
        "thumbs/" +
        mm_js_vars.mm_images[i] +
        '" /></div>';
    }
  } // for
  html += "</div>";
  html += "</div>";
  jQuery(".csmm-image-picker").remove();
  jQuery("body").append(html);

  jQuery("body").on("click", ".csmm-image-picker-image", function () {
    var image_url = jQuery(this).data("image");
    $target.parent().find("input").val(image_url);
    $target
      .parent()
      .find(".signals-preview-area")
      .html('<img src="' + image_url + '" />');
    $target
      .parent()
      .find(".signals-upload-append")
      .html(
        '&nbsp;<a href="javascript: void(0);" class="signals-remove-image">Remove</a>'
      );
    jQuery(".csmm-image-picker").remove();
  });
} // getMMuploader

(function ($) {
  $('#csmm-search-templates').on('change mouseup keyup focus blur search', function(e) {
    e.preventDefault();

    if (!$(this).val()) {
      $('#csmm-themes-wrapper').find('.theme-thumb').css('display','inline-block');
      return;
    }

    search_string = new RegExp($(this).val(), 'i');

    $('#csmm-themes-wrapper').find('.theme-thumb').each(function(){
      if( search_string.test($(this).data('theme-name')) ){
        $(this).css('display','inline-block');
      } else {
        $(this).hide();
      }
    });
  }).trigger('search');

  $("body").on("click", ".media-modal-close", function () {
    $(".csmm-image-picker").remove();
  });

  $("body").on("click", ".csmm-image-picker-image_ad", function (e) {
    $(".csmm-image-picker").remove();
    csmm_change_tab("pro");
    e.preventDefault();
  });


  // css and html editor
  function getEditor($editorID, $textareaID, $mode) {
    if ($("#" + $editorID).length > 0) {
      var editor = ace.edit($editorID),
        $textarea = $("#" + $textareaID).hide();

      editor.getSession().setValue($textarea.val());

      editor.getSession().on("change", function () {
        $textarea.val(editor.getSession().getValue());
      });

      editor.getSession().setMode("ace/mode/" + $mode);
      //editor.setTheme( 'ace/theme/xcode' );
      editor.getSession().setUseWrapMode(true);
      editor.getSession().setWrapLimitRange(null, null);
      editor.renderer.setShowPrintMargin(null);

      editor.session.setUseSoftTabs(null);
    }
  }

  // auto remove notices
  window.setTimeout(function () {
    $(".signals-alert").fadeOut();
  }, 1000 * 15);

  // WP native uploader
  $(document).on("click", ".signals-upload", function (e) {
    e.preventDefault();
    if ($(this).hasClass("signals-upload-bg")) {
      getMMUploader("Select Image", $(this));
    } else {
      getUploader("Select Image", $(this));
    }
  });

  // Removing photo from the canvas and emptying the text field
  $(document).on("click", ".signals-remove-image", function (e) {
    e.preventDefault();

    $(this).parent().parent().find("input").val("");
    $(this)
      .parent()
      .parent()
      .find(".signals-preview-area")
      .html("Select an image");
    $(this).hide();
  });

  function block_ui(message) {
    tmp = csmm_swal.fire({
      text: message,
      type: false,
      imageUrl: mm_js_vars.loader_image,
      imageWidth: 100,
      imageHeight: 100,
      imageAlt: message,
      allowOutsideClick: false,
      allowEscapeKey: false,
      allowEnterKey: false,
      showConfirmButton: false,
      timerProgressBar: true,
    });

    return tmp;
  } // block_ui

  // on dom ready
  $(document).ready(function () {
    // hide nags from other plugins
    $(
      "#wpbody-content .notice-warning, #wpbody-content .update-nag, #wpbody-content .notice-error, #wpbody-content .notice-info, #wpbody-content .error, #wpbody-content .updated"
    ).hide();

    $(document).on("click", "#mm_rate_cancel", function (e) {
      e.preventDefault();

      $("#rating-notice").fadeOut();
      $.get(ajaxurl, {
        action: "csmm_rate_hide",
        _ajax_nonce: mm_js_vars.mm_notice_nonce,
      });

      return false;
    });

    $(document).on("click", "#mm_welcome_cancel", function (e) {
      e.preventDefault();

      $("#upsell-notice").fadeOut();
      $.get(ajaxurl, {
        action: "csmm_welcome_hide",
        _ajax_nonce: mm_js_vars.mm_notice_nonce,
      });

      return false;
    });

    $(document).on("click", "#mm_olduser_cancel", function (e) {
      e.preventDefault();

      $("#upsell-notice").fadeOut();
      $.get(ajaxurl, {
        action: "csmm_olduser_hide",
        _ajax_nonce: mm_js_vars.mm_notice_nonce,
      });

      return false;
    });

    // bunny fonts
    $(".signals-bunny-fonts").each(function () {
      var $font = $(this);
      changeFont($font);
    });

    $(document).on("change", ".signals-bunny-fonts", function () {
      var $font = $(this);
      changeFont($font);
    });

    // helper for scrolling to anchor
    $(".settings_page_maintenance_mode_options").on(
      "click",
      ".scrollto",
      function (e) {
        e.preventDefault();

        // get the link anchor and scroll to it
        target = this.href.split("#")[1];
        if (target) {
            console.log('Scroll to '+target);
            $.scrollTo("#" + target, 500, { offset: { top: -50, left: 0 } });
        }

        $(this).blur();
        return false;
      }
    ); // scroll to anchor helper

    $("#csmm_save_license").on("click", function (e) {
      e.preventDefault();
      block_ui("Validating. Please wait...");
      button = this;
      safe_refresh = true;

      wf_csmm_licensing_verify_licence_ajax(
        "csmm",
        $("#signals_csmm_license_key").val(),
        button
      );
    });

    $("#csmm_keyless_activation").on("click", function (e) {
      e.preventDefault();
      block_ui("Validating. Please wait...");
      button = this;
      safe_refresh = true;

      wf_csmm_licensing_verify_licence_ajax("csmm", "keyless", button);
      return;
    });

    $("#csmm_deactivate_license").on("click", function (e) {
      e.preventDefault();
      block_ui("Deactivating. Please wait...");
      button = this;
      safe_refresh = true;

      wf_csmm_licensing_deactivate_licence_ajax(
        "csmm",
        $("#signals_csmm_license_key").val(),
        button
      );
      return;
    });

    $("#signals_csmm_license_key").on("keypress", function (e) {
      if (e.keyCode == 13) {
        e.preventDefault();
        $("#csmm_save_license").trigger("click");
      }
    });

    $(".pro-option").on("click change", function (e) {
      if ($(this).is("select") && $(this).val() != "-1") {
        return true;
      }

      if ($(this).is("select")) {
        $(this).find("option").attr("selected", "");
        $(this).find("option").first().attr("selected", "selected");
      }
      $(this).blur();

      csmm_change_tab("pro");
      e.preventDefault();

      return false;
    });

    $("#mail_system_to_use").on("change", function (e) {
      var mailsystem = $(this).val();
      if (mailsystem == "mc") {
        $("#mailchimp-wrapper").show();
      } else if (mailsystem == "mo") {
        $("#mailchimp-wrapper").hide();
      }
    });

    function csmm_fix_dialog_close(event, ui) {
      jQuery(".ui-widget-overlay").bind("click", function () {
        jQuery("#" + event.target.id).dialog("close");
      });
    } // csmm_fix_dialog_close

    $("#background_image_filter")
      .on("change", function (e) {
        filter = $(this).val();
        image = $("#background-preview img");
        if (!image.length) {
          return;
        }

        $(image).removeClass();
        $(image).addClass(filter);
      })
      .trigger("change");

    $("#mail_system_to_use")
      .on("change", function (e) {
        if ($(this).val() == "mc") {
          $("#csmm-setting-form-mo").hide();
          $("#csmm-setting-form-mc").show();
        } else if ($(this).val() == "mo") {
          $("#csmm-setting-form-mc").hide();
          $("#csmm-setting-form-mo").show();
        }
      })
      .trigger("change");

    $("#header-status").on("click", function (e) {
      e.preventDefault();
      window.location = $(this).data("action-url");
    });

    $("#arrange-items2").on("click", function (e) {
      e.preventDefault();
      csmm_change_tab("pro");

      return false;
    });

    // zebra on pricing table, per column
    $("#pricing-table")
      .find("tr")
      .each(function (index) {
        $(this).find("td").eq(1).addClass("hover");
        if (index == 0 || index == 12) {
          return true;
        }
        $(this)
          .find("td:not(.not-available)")
          .eq(1)
          .html(
            '<span class="dashicons dashicons-yes"></span> ' +
              $(this).find("td").eq(1).html()
          );
      });

    $("#signals_csmm_title, #signals_csmm_description")
      .on("change keyup", function () {
        var title_lenght = $("#signals_csmm_title").val().length;
        var title_bar_width = Math.round((title_lenght / 60) * 100);
        if (title_bar_width > 100) title_bar_width = 100;
        $("#mm-seo-progress-title .mm-seo-progress-bar").css(
          "width",
          title_bar_width + "%"
        );

        if (title_bar_width == 100) {
          $("#mm-seo-progress-title").removeClass("mm-seo-progress-good");
          $("#mm-seo-progress-title").addClass("mm-seo-progress-warning");
        } else if (title_bar_width < 80) {
          $("#mm-seo-progress-title").removeClass("mm-seo-progress-good");
          $("#mm-seo-progress-title").addClass("mm-seo-progress-warning");
        } else {
          $("#mm-seo-progress-title").removeClass("mm-seo-progress-warning");
          $("#mm-seo-progress-title").addClass("mm-seo-progress-good");
        }

        var description_lenght = $("#signals_csmm_description").val().length;
        var description_bar_width = Math.round(
          (description_lenght / 300) * 100
        );
        if (description_bar_width > 100) description_bar_width = 100;
        $("#mm-seo-progress-description .mm-seo-progress-bar").css(
          "width",
          description_bar_width + "%"
        );

        if (description_bar_width == 100) {
          $("#mm-seo-progress-description").removeClass("mm-seo-progress-good");
          $("#mm-seo-progress-description").addClass("mm-seo-progress-warning");
        } else if (description_bar_width < 36) {
          $("#mm-seo-progress-description").removeClass("mm-seo-progress-good");
          $("#mm-seo-progress-description").addClass("mm-seo-progress-warning");
        } else {
          $("#mm-seo-progress-description").removeClass(
            "mm-seo-progress-warning"
          );
          $("#mm-seo-progress-description").addClass("mm-seo-progress-good");
        }
      })
      .trigger("change");

    // reposition main on/off button on window resize and load
    $(window)
      .on("resize", function (e) {
        if (false && $(".signals-float-right").width() >= 1200) {
          position = 1113;
        } else {
          position = parseInt($(".signals-float-right").width() - 7, 10);
        }
        $("#header-right").css("left", position + "px");
      })
      .trigger("resize");

    $(".signals-cnt-fix").on("click", ".switchery", function (e) {
      if ($(this).prev("input.pro-option").length != 0) {
        csmm_change_tab("pro");

        e.preventDefault();
        return false;
      }
    });

    // ios switches
    var elements = Array.prototype.slice.call(
      document.querySelectorAll(".signals-form-ios")
    );
    elements.forEach(function (html) {
      var switchery = new Switchery(html);
    });

    // sortable
    var el = document.getElementById("arrange-items");
    var sortable = Sortable.create(el, {
      animation: 150,
      dataIdAttr: "data-id",
      store: {
        get: function (sortable) {
          var order = localStorage.getItem(sortable.options.group);
          return order ? order.split("|") : [];
        },
        set: function (sortable) {
          var order = sortable.toArray();
          $("#signals_csmm_arrange").val(order);
        },
      },
    });

    // css and html editor
    getEditor("signals_csmm_html_editor", "signals_csmm_html", "html");
    getEditor("signals_csmm_css_editor", "signals_csmm_css", "css");

    $(".csmm-change-tab").on("click", function (e) {
      e.preventDefault();

      tab_name = $(this).attr("href");
      csmm_change_tab(tab_name);

      if ($(this).data("anchor")) {
        $("html,body").animate(
          { scrollTop: $("#" + $(this).data("anchor")).offset().top },
          "slow"
        );
      }

      return false;
    });

    // tabs
    var $state = $.cookie("signals_csmm_menu");

    if ($state) {
      $(".signals-main-menu li a").removeClass("active");
      $('a[href="' + $state + '"]').addClass("active");
      $($state).show();
    } else {
      $(".signals-main-menu li:last a").addClass("active");
      $(".signals-tile:last").show();
    }

    $(".signals-main-menu li a").click(function (e) {
      e.preventDefault();

      $.removeCookie("signals_csmm_menu", { path: "/" });

      var $selector = $(this);
      var $tab = $selector.attr("href");

      $(".signals-main-menu li a").removeClass("active");
      $selector.addClass("active");

      $(".signals-tile").hide();
      $($tab).show();
      $.cookie("signals_csmm_menu", $tab, { path: "/" });
    });

    $(".signals-mobile-menu a").click(function () {
      $(".signals-main-menu").slideToggle();
    });

    // dismiss notice
    $(".signals-alert .notice-dismiss").on("click", function (e) {
      e.preventDefault();

      $(this).parents(".signals-alert").fadeOut();

      return false;
    });

    // helper for linking anchors in different tabs
    $(".signals-cnt-fix").on("click", ".confirm-action", function (e) {
      message = $(this).data("confirm");

      if (!message || confirm(message)) {
        return true;
      } else {
        e.preventDefault();
        return false;
      }
    }); // confirm action before link click

    // alert user of unsaved changes when doing preview
    old_settings = $("form.signals-admin-form *").not(".skip-save").serialize();
    $("#csmm-preview").on("click", function (e) {
      if (
        $("form.signals-admin-form *").not(".skip-save").serialize() !=
        old_settings
      ) {
        if (
          !confirm(
            "There are unsaved changes that will not be visible in the preview. Please save changes first.\nContinue?"
          )
        ) {
          e.preventDefault();
          return false;
        }
      }

      return true;
    });

    if (!Date.now) {
      Date.now = function () {
        return new Date().getTime();
      };
    }

    function mm_update_timer() {
      out = "";
      timer = jQuery(".mm-countdown");

      if (timer.length == 0) {
        clearInterval(mm_countdown_interval);
      }

      now = Math.round(new Date().getTime() / 1000);
      timer_end = jQuery(timer).data("endtime");
      delta = timer_end - now;
      seconds = Math.floor(delta % 60);
      minutes = Math.floor((delta / 60) % 60);
      hours = Math.floor((delta / (60 * 60)) % 24);

      if (delta <= 0) {
        clearInterval(mm_countdown_interval);
      }

      if (hours) {
        out += hours + "h ";
      }
      if (minutes || out) {
        out += minutes + "min ";
      }
      if (seconds || out) {
        out += seconds + "sec";
      }
      if (delta <= 0 || !out) {
        out = " - no longer available";
      }

      jQuery(timer).html(out);

      return true;
    } // mm_update_timer

    if (jQuery(".mm-countdown").length) {
      mm_countdown_interval = setInterval(mm_update_timer, 1000);
    }
  }); // on ready
})(jQuery);
