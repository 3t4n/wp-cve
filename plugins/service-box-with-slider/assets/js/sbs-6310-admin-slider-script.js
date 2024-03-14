jQuery(window).on("load", function () {
  if (jQuery("#sbs_6310_fun_template_slider").prop("checked") === false) {
    jQuery(".sbs-6310-slider, .sbs_6310_carousel_field").hide();
    jQuery(".sbs-6310-noslider").show();
  } else {
    jQuery(".sbs-6310-slider, .sbs_6310_carousel_field").show();
    jQuery(".sbs-6310-noslider").hide();
  }
  if (jQuery("#prev_next_active").prop("checked") === false) {
    jQuery(".sbs_6310_prev_next_act, .sbs-6310-owl-nav").hide();
  }
  if (jQuery("#indicator_activation").prop("checked") === false) {
    jQuery(".sbs_6310_indicator_act, .sbs-6310-owl-dots").hide();
  }

  //Active or inactive slider
  jQuery("body").on("change", "#sbs_6310_fun_template_slider", function () {
    if (jQuery(this).prop("checked") === true) {
      jQuery(".sbs-6310-slider, .sbs_6310_carousel_field").show();
      jQuery(".sbs-6310-noslider").hide();
    } else {
      jQuery(".sbs-6310-slider, .sbs_6310_carousel_field").hide();
      jQuery(".sbs-6310-noslider").show();
    }
  });

  //Active or inactive Previous/Next
  jQuery("body").on("change", "#prev_next_active", function () {
    if (jQuery(this).prop("checked") === true) {
      jQuery(".sbs_6310_prev_next_act, .sbs-6310-owl-nav").show();
    } else {
      jQuery(".sbs_6310_prev_next_act, .sbs-6310-owl-nav").hide();
    }
  });

  //Active or inactive Previous/Next
  jQuery("body").on("change", "#indicator_activation", function () {
    if (jQuery(this).prop("checked") === true) {
      jQuery(".sbs_6310_indicator_act, .sbs-6310-owl-dots").show();
    } else {
      jQuery(".sbs_6310_indicator_act, .sbs-6310-owl-dots").hide();
    }
  });

  // jQuery("body").on("change", "#sbs_6310_background_preview", function() {
  //    var value = jQuery(this).val();
  //    jQuery(".sbs_6310_tabs_panel_preview").css({
  //       "background": value
  //    });
  // });

  let serviceList = jQuery(".sbs-6310-service-box");
  if (serviceList.length) {
    serviceList.each(function () {
      let id = jQuery(this).attr("sbs-6310-style-id");
      let desktop = parseInt(jQuery(this).attr("sbs-6310-style-desktop"));
      let tablet = parseInt(jQuery(this).attr("sbs-6310-style-tablet"));
      let mobile = parseInt(jQuery(this).attr("sbs-6310-style-mobile"));
      let duration = Number(jQuery(this).attr("sbs-6310-slider-duration"));
      let nav = parseInt(jQuery(this).attr("sbs-6310-slider-nav"));
      let dot = parseInt(jQuery(this).attr("sbs-6310-slider-dot"));
      let margin = parseInt(jQuery(this).attr("sbs-6310-slider-margin"));
      let navText = jQuery(this).attr("sbs-6310-slider-navText");

      var owl = jQuery(`.sbs-6310-slider-${id}`);
      owl.sbs6310OwlCarousel({
        stagePadding: margin,
        autoplay: true,
        lazyLoad: true,
        loop: true,
        margin: margin * 2,
        autoplayTimeout: duration,
        autoplayHoverPause: true,
        responsiveClass: true,
        autoHeight: true,
        nav: nav ? true : false,
        dots: dot ? true : false,
        navText: [
          `<i class='${navText}-left'></i>`,
          `<i class='${navText}-right'></i>`,
        ],
        responsive: {
          0: {
            items: mobile,
          },
          768: {
            items: tablet,
          },
          1024: {
            items: desktop,
          },
          1366: {
            items: desktop,
          },
        },
      });

      owl.on("mouseleave", function () {
        owl.trigger("stop.owl.autoplay"); //this is main line to fix it
        owl.trigger("play.owl.autoplay", [duration]);
      });

      setTimeout(function () {
        let allSlider = jQuery(`.sbs-6310-slider-${id} .sbs-6310-owl-item`);
        let maxHeight = 0;
        if (allSlider.length) {
          for (let ii = 0; ii < allSlider.length; ii++) {
            maxHeight = Math.max(maxHeight, allSlider[ii].offsetHeight);
          }
        }
        if (maxHeight > 0) {
          jQuery(`.sbs-6310-slider-${id} .sbs-6310-owl-height`).css({
            height: maxHeight,
          });
        }
      }, 500);
      setTimeout(function () {
        let allSlider = jQuery(`.sbs-6310-slider-${id} .sbs-6310-owl-item`);
        let maxHeight = 0;
        if (allSlider.length) {
          for (let ii = 0; ii < allSlider.length; ii++) {
            maxHeight = Math.max(maxHeight, allSlider[ii].offsetHeight);
          }
        }
        if (maxHeight > 0) {
          jQuery(`.sbs-6310-slider-${id} .sbs-6310-owl-height`).css({
            height: maxHeight,
          });
        }
      }, 1000);
      setTimeout(function () {
        let allSlider = jQuery(`.sbs-6310-slider-${id} .sbs-6310-owl-item`);
        let maxHeight = 0;
        if (allSlider.length) {
          for (let ii = 0; ii < allSlider.length; ii++) {
            maxHeight = Math.max(maxHeight, allSlider[ii].offsetHeight);
          }
        }
        if (maxHeight > 0) {
          jQuery(`.sbs-6310-slider-${id} .sbs-6310-owl-height`).css({
            height: maxHeight,
          });
        }
      }, 1500);
      setTimeout(function () {
        let allSlider = jQuery(`.sbs-6310-slider-${id} .sbs-6310-owl-item`);
        let maxHeight = 0;
        if (allSlider.length) {
          for (let ii = 0; ii < allSlider.length; ii++) {
            maxHeight = Math.max(maxHeight, allSlider[ii].offsetHeight);
          }
        }
        if (maxHeight > 0) {
          jQuery(`.sbs-6310-slider-${id} .sbs-6310-owl-height`).css({
            height: maxHeight,
          });
        }
      }, 2000);
      setTimeout(function () {
        let allSlider = jQuery(`.sbs-6310-slider-${id} .sbs-6310-owl-item`);
        let maxHeight = 0;
        if (allSlider.length) {
          for (let ii = 0; ii < allSlider.length; ii++) {
            maxHeight = Math.max(maxHeight, allSlider[ii].offsetHeight);
          }
        }
        if (maxHeight > 0) {
          jQuery(`.sbs-6310-slider-${id} .sbs-6310-owl-height`).css({
            height: maxHeight,
          });
        }
      }, 3000);
    });
  }

  /* Slider form settings Start */

  jQuery("body").on("change", "#effect_duration", function () {
    jQuery(".sbs-6310-slider").data("owl.carousel").options.autoplayTimeout =
      jQuery("#effect_duration").val();
    jQuery(".sbs-6310-slider").trigger("refresh.owl.carousel");
  });

  jQuery("body").on("change", "#slider_icon_style", function () {
    jQuery(".sbs-6310-slider .sbs-6310-owl-nav div.sbs-6310-owl-prev i").attr(
      "class",
      "" + jQuery(this).val() + "-left"
    );
    jQuery(".sbs-6310-slider .sbs-6310-owl-nav div.sbs-6310-owl-next i").attr(
      "class",
      "" + jQuery(this).val() + "-right"
    );
  });

  jQuery("body").on("change", "#slider_prev_next_icon_size", function () {
    jQuery(
      "<style type='text/css'>.sbs-6310-slider .sbs-6310-owl-nav div { font-size:" +
        parseInt(jQuery(this).val()) +
        "px !important; line-height:" +
        (parseInt(jQuery(this).val()) + 15) +
        "px !important; height:" +
        (parseInt(jQuery(this).val()) + 15) +
        "px !important; width:" +
        (parseInt(jQuery(this).val()) + 15) +
        "px !important;} </style>"
    ).appendTo(".sbs-6310-preview");
  });

  jQuery("body").on(
    "change",
    "#slider_prev_next_icon_border_radius",
    function () {
      jQuery(
        "<style type='text/css'>.sbs-6310-slider .sbs-6310-owl-nav div.sbs-6310-owl-prev { border-radius:" +
          "0 " +
          parseInt(jQuery(this).val()) +
          "% " +
          parseInt(jQuery(this).val()) +
          "% 0" +
          " !important;} .sbs-6310-slider .sbs-6310-owl-nav div.sbs-6310-owl-next { border-radius:" +
          parseInt(jQuery(this).val()) +
          "% 0 0 " +
          parseInt(jQuery(this).val()) +
          "%" +
          " !important;}</style>"
      ).appendTo(".sbs-6310-preview");
    }
  );

  jQuery("body").on("change", "#slider_prev_next_bgcolor", function () {
    jQuery(
      "<style type='text/css'>.sbs-6310-slider .sbs-6310-owl-nav div { background:" +
        jQuery(this).val() +
        " !important;} </style>"
    ).appendTo(".sbs-6310-preview");
  });

  jQuery("body").on("change", "#slider_prev_next_color", function () {
    jQuery(
      "<style type='text/css'>.sbs-6310-slider .sbs-6310-owl-nav div { color:" +
        jQuery(this).val() +
        " !important;} </style>"
    ).appendTo(".sbs-6310-preview");
  });

  jQuery("body").on("change", "#slider_prev_next_hover_bgcolor", function () {
    jQuery(
      "<style type='text/css'>.sbs-6310-slider .sbs-6310-owl-nav div:hover { background:" +
        jQuery(this).val() +
        " !important;} </style>"
    ).appendTo(".sbs-6310-preview");
  });

  jQuery("body").on("change", "#slider_prev_next_hover_color", function () {
    jQuery(
      "<style type='text/css'>.sbs-6310-slider .sbs-6310-owl-nav div:hover { color:" +
        jQuery(this).val() +
        " !important;} </style>"
    ).appendTo(".sbs-6310-preview");
  });

  jQuery("body").on("change", "#slider_indicator_width", function () {
    jQuery(
      "<style type='text/css'>.sbs-6310-slider .sbs-6310-owl-dots div { width:" +
        parseInt(jQuery(this).val()) +
        "px !important;} </style>"
    ).appendTo(".sbs-6310-preview");
  });

  jQuery("body").on("change", "#slider_indicator_height", function () {
    jQuery(
      "<style type='text/css'>.sbs-6310-slider .sbs-6310-owl-dots div { height:" +
        parseInt(jQuery(this).val()) +
        "px !important;} </style>"
    ).appendTo(".sbs-6310-preview");
  });

  jQuery("body").on("change", "#slider_indicator_active_color", function () {
    jQuery(
      "<style type='text/css'>.sbs-6310-slider .sbs-6310-owl-dots div.active{ background-color:" +
        jQuery(this).val() +
        " !important;} </style>"
    ).appendTo(".sbs-6310-preview");
  });

  jQuery("body").on("change", "#slider_indicator_color", function () {
    jQuery(
      "<style type='text/css'>.sbs-6310-slider .sbs-6310-owl-dots div { background-color:" +
        jQuery(this).val() +
        " !important;} </style>"
    ).appendTo(".sbs-6310-preview");
  });

  jQuery("body").on("change", "#slider_indicator_border_radius", function () {
    jQuery(
      "<style type='text/css'>.sbs-6310-slider .sbs-6310-owl-dots div { border-radius:" +
        parseInt(jQuery(this).val()) +
        "% !important;} </style>"
    ).appendTo(".sbs-6310-preview");
  });

  jQuery("body").on("change", "#slider_indicator_margin", function () {
    jQuery(
      "<style type='text/css'>.sbs-6310-slider .sbs-6310-owl-dots div{ margin: 0 " +
        parseInt(jQuery(this).val()) +
        "px !important;} </style>"
    ).appendTo(".sbs-6310-preview");
  });

  /*
  jQuery("body").on("click", ".prev_next_active", function () {
    var val = jQuery(this).val();
    jQuery(".prev_next_active").removeClass("active");
    jQuery(this).addClass("active");
    jQuery("#prev_next_active").val(val);
    if (val == "true") {
      jQuery(".wpm_6310_prev_next_act, #wpm_6310_prev, #wpm_6310_next, #wpm_6310_prev_font_icon, #wpm_6310_next_font_icon").show();
      jQuery('.sbs-6310-slider').data('owl.carousel').options.nav = true;
      jQuery('.sbs-6310-slider').trigger('refresh.owl.carousel');
    } else {
      jQuery(".wpm_6310_prev_next_act, #wpm_6310_prev, #wpm_6310_next, #wpm_6310_prev_font_icon, #wpm_6310_next_font_icon").hide();
      jQuery('.sbs-6310-slider').data('owl.carousel').options.nav = false;
      jQuery('.sbs-6310-slider').trigger('refresh.owl.carousel');
    }
  });

  

  jQuery("body").on("click", ".indicator_activation", function () {
    var val = jQuery(this).val();
    jQuery(".indicator_activation").removeClass("active");
    jQuery(this).addClass("active");
    jQuery("#indicator_activation").val(val);
    if (val == "true") {
      jQuery(".wpm_6310_indicator_act, #wpm_6310_carousel_indicators").show();
      jQuery('.sbs-6310-slider').data('owl.carousel').options.dots = true;
      jQuery('.sbs-6310-slider').trigger('refresh.owl.carousel');
    } else {
      jQuery(".wpm_6310_indicator_act, #wpm_6310_carousel_indicators").hide();
      jQuery('.sbs-6310-slider').data('owl.carousel').options.dots = false;
      jQuery('.sbs-6310-slider').trigger('refresh.owl.carousel');
    }
  });

  
  */
});
