jQuery.fn.extend({
  live: function (event, callback) {
    if (this.selector) {
      jQuery(document).on(event, this.selector, callback);
    }
    return this;
  },
});

jQuery.fn.extend({
  size: function (event, callback) {
    if (this.selector) {
      jQuery(document).on(event, this.selector, callback);
    }
    return this;
  },
});

jQuery(document).ready(function () {
  let id = jQuery(".sbs-6310-service-box").attr("sbs-6310-style-id");
  if (jQuery("#sbs_6310_fun_template_button").prop("checked") === false) {
    jQuery(".read_more_act_field").hide();
  }

  if (jQuery("#template_details_show_hide").prop("checked") === false) {
    jQuery(".details_act_field").hide();
  }

  if (jQuery(".codemirror-textarea").length) {
    var code = jQuery(".codemirror-textarea")[0];
    var editor = CodeMirror.fromTextArea(code, {
      mode: "text/html",
      tabMode: "indent",
      autoCloseTags: true,
      lineNumbers: true,
      fixedGutter: true,
      lineWrapping: true,
      autoCloseBrackets: true,
    });
  }

  jQuery(
    "#tab-2, #tab-3, #tab-4, #tab-5, #tab-6, #tab-7, #tab-8, #tab-9, #tab-10, #tab-11, #tab-12"
  ).hide();
  jQuery("body").on("click", ".sbs-6310-mytab", function () {
    jQuery(".sbs-6310-mytab").removeClass("active");
    jQuery(this).addClass("active");
    var ids = jQuery(this).attr("id");
    ids = parseInt(ids.substring(3));
    jQuery(
      "#tab-1, #tab-2, #tab-3, #tab-4, #tab-5, #tab-6, #tab-7, #tab-8, #tab-9, #tab-10, #tab-11, #tab-12"
    ).hide();
    jQuery("#tab-" + ids).show();
    jQuery("#tab6").click(function (event) {
      jQuery(".codemirror-textarea").focus();
    });
    return false;
  });

  //Color Picker Script
  if (jQuery(".sbs_6310_color_picker").length) {
    jQuery(".sbs_6310_color_picker").each(function () {
      jQuery(this).minicolors({
        control: jQuery(this).attr("data-control") || "hue",
        defaultValue: jQuery(this).attr("data-defaultValue") || "",
        format: jQuery(this).attr("data-format") || "hex",
        keywords: jQuery(this).attr("data-keywords") || "",
        inline: jQuery(this).attr("data-inline") === "true",
        letterCase: jQuery(this).attr("data-letterCase") || "lowercase",
        opacity: jQuery(this).attr("data-opacity"),
        position: jQuery(this).attr("data-position") || "bottom left",
        swatches: jQuery(this).attr("data-swatches")
          ? jQuery(this).attr("data-swatches").split("|")
          : [],
        change: function (value, opacity) {
          if (!value) return;
          if (opacity) value += ", " + opacity;
          if (typeof console === "object") {
            console.log(value);
          }
        },
        theme: "bootstrap",
      });
    });
  }

  //Font select script
  jQuery(
    "#sbs_6310_title_font_family, #sbs_6310_details_font_family, #sbs_6310_read_more_font_family"
  ).fontselect();

  //Active or inactive read more
  jQuery("body").on("change", "#sbs_6310_fun_template_button", function () {
    if (jQuery(this).prop("checked") === true) {
      jQuery(`.read_more_act_field, .sbs-6310-template-${id}-read-more`).show();
    } else {
      jQuery(`.read_more_act_field, .sbs-6310-template-${id}-read-more`).hide();
    }
  });

  //Active or inactive description
  jQuery("body").on("change", "#template_details_show_hide", function () {
    if (jQuery(this).prop("checked") === true) {
      jQuery(`.details_act_field, .sbs-6310-template-${id}-description`).show();
    } else {
      jQuery(`.details_act_field, .sbs-6310-template-${id}-description`).hide();
    }
  });

  //Choose background type start
  if (jQuery("#background_type").length) {
    jQuery(".background-type-2, .background-type-3, .background-type-4").hide();
    jQuery(".background-type-" + jQuery("#background_type").val()).show();

    jQuery("body").on("change", "#background_type", function () {
      var val = jQuery(this).val();
      jQuery(
        ".background-type-2, .background-type-3, .background-type-4"
      ).hide();
      jQuery(`.background-type-${val}`).show();
    });

    jQuery("body").on(
      "click",
      "#sbs_6310_box_background_image_button",
      function (e) {
        e.preventDefault();
        var image = wp
          .media({
            title: "Upload Image",
            multiple: false,
          })
          .open()
          .on("select", function (e) {
            var uploaded_image = image.state().get("selection").first();
            var image_url = uploaded_image.toJSON().url;
            jQuery(`#sbs_6310_box_background_image`).val(image_url);
          });

        jQuery("#wpm_6310_add_new_media").css({
          "overflow-x": "hidden",
          "overflow-y": "auto",
        });
      }
    );
  }
  //Choose background type end

  //Search Start
  if (jQuery("#sbs_6310_search_activation").prop("checked") === false) {
    jQuery(".search_act_field, .sbs-6310-search").hide();
  }
  jQuery(".sbs-6310-search-box").on("keyup", function () {
    var value = jQuery(this).val().toLowerCase();
    var ids = jQuery(this)
      .closest(".sbs-6310-service-box")
      .attr("sbs-6310-style-id");
    jQuery(`.sbs-6310-noslider .sbs-6310-row .sbs-6310-col-list`).filter(
      function () {
        var title = jQuery(this)
          .find(`.sbs-6310-template-${ids}-title`)
          .text()
          .toLowerCase();
        var designation = jQuery(this)
          .find(`.sbs-6310-template-${ids}-description`)
          .text()
          .toLowerCase();
        let status =
          title.indexOf(value) > -1 || designation.indexOf(value) > -1;
        if (status) {
          jQuery(this).show(300);
        } else {
          jQuery(this).hide(300);
        }
      }
    );
  });

  jQuery("body").on("click", "#sbs_6310_search_activation", function () {
    if (jQuery(this).prop("checked") == true) {
      jQuery(".sbs-6310-search-box").val("");
      jQuery(".sbs-6310-search-container").show();
      jQuery(".search_act_field, .sbs-6310-search").show();
    } else {
      jQuery(".sbs-6310-search-container").hide();
      jQuery(".search_act_field, .sbs-6310-search").hide();
    }
  });

  //Manage item page start
  jQuery("#profile_details, #effect-appearance").hide();
  jQuery("body").on("click", "#add-accordion", function () {
    jQuery("#sbs-6310-modal-add-item").fadeIn(500);
    jQuery("body").css({
      overflow: "hidden",
    });
    return false;
  });

  //Manage icon Start
  jQuery(".custom-icon-new").hide();
  jQuery("body").on("change", ".icontype_new", function () {
    let val = Number(jQuery(this).val());
    jQuery(".custom-icon-new, .font-awesome-icon-new").hide();
    val
      ? jQuery(".font-awesome-icon-new").show()
      : jQuery(".custom-icon-new").show();
  });
  jQuery("#icon-filter").on("keyup", function () {
    var value = jQuery(this).val().toLowerCase();
    jQuery(".sbs-6310-choose-icon li").filter(function () {
      jQuery(this).toggle(
        jQuery(this).attr(`data-icon-name`).toLowerCase().indexOf(value) > -1
      );
    });
  });

  jQuery("body").on(
    "click",
    "#sbs-6310-font-icon-close, .sbs-6310-font-awesome-close",
    function () {
      jQuery("#sbs_6310_social_icon").fadeOut(500);
    }
  );

  jQuery("body").on("click", ".sbs-6310-plus-icons i", function () {
    let selIds = jQuery(this)
      .closest(".sbs-6310-plus-icons")
      .siblings(".sbs-6310-form-input")
      .attr("id");
    jQuery("ul.sbs-6310-choose-icon").attr("data-current-id", selIds);
    if (jQuery("#icon-filter").val()) {
      jQuery("#icon-filter").val("");
      jQuery(".sbs-6310-choose-icon li").filter(function () {
        jQuery(this).toggle();
      });
    }
    jQuery("#sbs_6310_social_icon").fadeIn(500);
    jQuery("body").css({
      overflow: "hidden",
    });
    jQuery("#icon-filter").focus();
    return false;
  });

  jQuery("body").on("click", "ul.sbs-6310-choose-icon li", function () {
    let cls = jQuery(this).find("i").attr("class");
    jQuery(`#` + jQuery("ul.sbs-6310-choose-icon").attr("data-current-id")).val(
      cls
    );
    jQuery("#sbs_6310_social_icon").fadeOut(500);
  });
  //Manage icon End

  /* ######### Custom Icon Media Start ########### */
  jQuery("body").on("click", ".sbs-6310-icon-upload", function (e) {
    e.preventDefault();
    let dataId = jQuery(this).attr("data-id");
    console.log(dataId);
    var image = wp
      .media({
        title: "Upload Image",
        multiple: false,
      })
      .open()
      .on("select", function (e) {
        var uploaded_image = image.state().get("selection").first();
        var image_url = uploaded_image.toJSON().url;
        jQuery(`#${dataId}`).val(image_url);
      });

    jQuery("#wpm_6310_add_new_media").css({
      "overflow-x": "hidden",
      "overflow-y": "auto",
    });
  });
  /* ######### Custom Icon Media End ########### */

  /* Modal Close Start */
  jQuery("body").on(
    "click",
    ".sbs-6310-close, #sbs-6310-from-close",
    function () {
      jQuery(
        "#sbs-6310-modal-add, #sbs-6310-modal-edit, #sbs_6310_social_icon, #sbs-6310-modal-add-item, #sbs-6310-modal-edit-item"
      ).fadeOut(500);
      jQuery("body").css({
        overflow: "initial",
      });
    }
  );
  jQuery("body").on("click", ".sbs-6310-close-2", function () {
    jQuery("#sbs_6310_social_icon").fadeOut(500);
    jQuery("body").css({
      overflow: "initial",
    });
  });
  jQuery(window).click(function (event) {
    if (event.target == document.getElementById("sbs-6310-modal-edit-item")) {
      jQuery("#sbs-6310-modal-edit-item").fadeOut(500);
      jQuery("body").css({
        overflow: "initial",
      });
    } else if (
      event.target == document.getElementById("sbs-6310-modal-add-item")
    ) {
      jQuery("#sbs-6310-modal-add-item").fadeOut(500);
      jQuery("body").css({
        overflow: "initial",
      });
    } else if (event.target == document.getElementById("sbs-6310-modal-add")) {
      jQuery("#sbs-6310-modal-add").fadeOut(500);
      jQuery("body").css({
        overflow: "initial",
      });
    } else if (event.target == document.getElementById("sbs-6310-modal-edit")) {
      jQuery("#sbs-6310-modal-edit").fadeOut(500);
      jQuery("body").css({
        overflow: "initial",
      });
    } else if (
      event.target == document.getElementById("sbs_6310_social_icon")
    ) {
      jQuery("#sbs_6310_social_icon").fadeOut(500);
      jQuery("body").css({
        overflow: "initial",
      });
    }
  });
  /* Modal Close End */

  //Manage item page end
});
