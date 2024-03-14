/*Admin options pannal data value*/
function weblizar_option_data_save(name) {
  var weblizar_settings_save = "#weblizar_settings_save_" + name;
  var weblizar_theme_options = "#weblizar_theme_options_" + name;
  var weblizar_settings_save_success = weblizar_settings_save + "_success";
  var loding_image = "#weblizar_loding_" + name;
  var weblizar_loding_image = loding_image + "_image";

  jQuery(weblizar_loding_image).show();
  jQuery(weblizar_settings_save).val("1");
  jQuery.ajax({
    url: "themes.php?page=weblizar",
    type: "post",
    data: jQuery(weblizar_theme_options).serialize(),
    success: function (data) {
      jQuery(weblizar_loding_image).fadeOut();
      jQuery(weblizar_settings_save_success).show();
      jQuery(weblizar_settings_save_success).fadeOut(5000);
    },
  });
}
/*Admin options value reset */
function weblizar_option_data_reset(name) {
  var r = confirm("Do you want reset your theme setting!");
  if (r == true) {
    var weblizar_settings_save = "#weblizar_settings_save_" + name;
    var weblizar_theme_options = "#weblizar_theme_options_" + name;
    var weblizar_settings_save_reset = weblizar_settings_save + "_reset";
    jQuery(weblizar_settings_save).val("2");
    jQuery.ajax({
      url: "themes.php?page=weblizar",
      type: "post",
      data: jQuery(weblizar_theme_options).serialize(),
      success: function (data) {
        jQuery(weblizar_settings_save_reset).show();
        jQuery(weblizar_settings_save_reset).fadeOut(5000);
      },
    });
  } else {
    alert("Cancel! reset theme setting process");
  }
}
// js to active the link of option pannel
jQuery(document).ready(function () {
  jQuery("ul li.active ul").slideDown();
  // menu click
  jQuery("#nav > li > a").click(function () {
    if (jQuery(this).attr("class") != "active") {
      jQuery("#nav li ul").slideUp(350);
      jQuery(this).next().slideToggle(350);
      jQuery("#nav li a").removeClass("active");
      jQuery(this).addClass("active");

      jQuery("ul.options_tabs li").removeClass("active");
      jQuery(this).parent().addClass("active");
      var divid = jQuery(this).attr("id");
      var add = "div#option-" + divid;
      var strlenght = add.length;

      if (strlenght < 17) {
        var add = "div#option-ui-id-" + divid;
        var ulid = "#ui-id-" + divid;
        jQuery("ul.options_tabs li li ").removeClass("currunt");
        jQuery(ulid).parent().addClass("currunt");
      }
      jQuery("div.ui-tabs-panel").addClass("deactive").fadeIn(1000);
      jQuery("div.ui-tabs-panel").removeClass("active");
      jQuery(add).removeClass("deactive");
      jQuery(add).addClass("active");
    }
  });

  // child submenu click
  jQuery("ul.options_tabs li li ").click(function () {
    jQuery("ul.options_tabs li li ").removeClass("currunt");
    jQuery(this).addClass("currunt");
    var option_name = jQuery(this).children("a").attr("id");
    var option_add = "div#option-" + option_name;
    jQuery("div.ui-tabs-panel").addClass("deactive").fadeIn(1000);
    jQuery("div.ui-tabs-panel").removeClass("active");
    jQuery(option_add).removeClass("deactive");
    jQuery(option_add).addClass("active");
  });

  /********media-upload******/
  // media upload js
  var uploadID = ""; /*setup the var*/
  var showImg = "";
  jQuery(".upload_image_button").click(function () {
    uploadID = jQuery(this).prev("input"); /*grab the specific input*/
    showImg = jQuery(this).nextAll("img");
    formfield = jQuery(".upload").attr("name");
    tb_show("", "media-upload.php?type=image&amp;TB_iframe=true");

    window.send_to_editor = function (html) {
      imgurl = jQuery("img", html).attr("src");
      showImg.attr("src", imgurl);
      uploadID.val(imgurl); /*assign the value to the input*/
      tb_remove();
    };
    return false;
  });
});

/****  For Option panle facebook Like ******/
(function (d, s, id) {
  var js,
    fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s);
  js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.7";
  fjs.parentNode.insertBefore(js, fjs);
})(document, "script", "facebook-jssdk");

/****  For Option panle twitter follower and Like ******/

!(function (d, s, id) {
  var js,
    fjs = d.getElementsByTagName(s)[0];
  if (!d.getElementById(id)) {
    js = d.createElement(s);
    js.id = id;
    js.src = "//platform.twitter.com/widgets.js";
    fjs.parentNode.insertBefore(js, fjs);
  }
})(document, "script", "twitter-wjs");

/* feed-script-start*/
jQuery(document).ready(function ($) {
  if (jQuery("#ffp_content_timeline").is(":checked")) {
    jQuery(".timeline_content").show();
    jQuery(".specific_content").hide();
  } else {
    jQuery(".timeline_content").hide();
    jQuery(".timeline_content").hide();
  }

  if (jQuery("#ffp_content_specific").is(":checked")) {
    jQuery(".specific_content").show();
    jQuery(".timeline_content").hide();
  } else {
    jQuery(".specific_content").hide();
  }

  if (jQuery("select#feed_type option:checked").val() == "page") {
    ffp_page_id;
    jQuery(".ffp_page_url").show();
    jQuery(".ffp_page_id").show();
    jQuery("#ffp_type_group").hide();
    jQuery("#ffp_type_group_token").hide();
  }
  if (jQuery("select#feed_type option:checked").val() == "group") {
    jQuery(".ffp_page_url").hide();
    jQuery(".ffp_page_id").hide();
    jQuery("#ffp_type_group").show();
    jQuery("#ffp_type_group_token").show();
  }
  if (jQuery("select#feed_type option:checked").val() == "profile") {
    jQuery(".ffp_page_url").hide();
    jQuery(".ffp_page_id").hide();
    jQuery("#ffp_type_group").hide();
    jQuery("#ffp_type_group_token").show();
  }

  if (jQuery("#ffp_specific_videos").is(":checked")) {
    jQuery(".video_light_boxcontents").show();
    jQuery(".photo_light_boxcontents").hide();
  }
  if (jQuery("#ffp_specific_photos").is(":checked")) {
    jQuery(".video_light_boxcontents").hide();
    jQuery(".photo_light_boxcontents").show();
  }
  if (jQuery("#ffp_specific_albums").is(":checked")) {
    jQuery(".video_light_boxcontents").hide();
    jQuery(".photo_light_boxcontents").show();
  }
  if (jQuery("#ffp_specific_events").is(":checked")) {
    jQuery(".video_light_boxcontents").show();
    jQuery(".photo_light_boxcontents").hide();
  }

  if (jQuery("#ffp_gallery_effect_name").val() == "image_move_effect") {
    jQuery(".image_move_effect").show();
    jQuery(".border_effect").hide();
    jQuery(".overlay_slide").hide();
  }
  if (jQuery("#ffp_gallery_effect_name").val() == "border_effect") {
    jQuery(".image_move_effect").hide();
    jQuery(".border_effect").show();
    jQuery(".overlay_slide").hide();
  }
  if (jQuery("#ffp_gallery_effect_name").val() == "overlay_slide") {
    jQuery(".image_move_effect").hide();
    jQuery(".border_effect").hide();
    jQuery(".overlay_slide").show();
  }
});

function feed_timelineChanged() {
  if (jQuery("#ffp_content_timeline").is(":checked")) {
    jQuery(".timeline_content").show();
    jQuery(".specific_content").hide();
  } else {
    jQuery(".timeline_content").hide();
  }
}

function feed_specificChanged() {
  if (jQuery("#ffp_content_specific").is(":checked")) {
    jQuery(".specific_content").show();
    jQuery(".timeline_content").hide();
  } else {
    jQuery(".specific_content").hide();
  }
}

function feed_type_change_function() {
  console.log(jQuery("#ffp_type").val());
  if (jQuery("#feed_type").val() == "page") {
    jQuery(".ffp_page_url").show();
    jQuery(".ffp_page_id").show();
    jQuery("#ffp_type_group").hide();
    jQuery("#ffp_type_group_token").hide();
  }
  if (jQuery("#feed_type").val() == "group") {
    jQuery(".ffp_page_url").hide();
    jQuery(".ffp_page_id").hide();
    jQuery("#ffp_type_group").show();
    jQuery("#ffp_type_group_token").show();
  }
  if (jQuery("#feed_type").val() == "profile") {
    jQuery(".ffp_page_url").hide();
    jQuery(".ffp_page_id").hide();
    jQuery("#ffp_type_group").hide();
    jQuery("#ffp_type_group_token").show();
  }
}

function ffp_effect_change() {
  if (jQuery("#ffp_gallery_effect_name").val() == "image_move_effect") {
    jQuery("#ffp_gallery_effect").find("option").removeAttr("selected");
    jQuery("#ffp_gallery_effect option[value='imghvr-shutter-in-horiz']").attr(
      "selected",
      "selected"
    );
    jQuery(".image_move_effect").show();
    jQuery(".border_effect").hide();
    jQuery(".overlay_slide").hide();
  }
  if (jQuery("#ffp_gallery_effect_name").val() == "border_effect") {
    jQuery("#ffp_gallery_effect").find("option").removeAttr("selected");
    jQuery("#ffp_gallery_effect option[value='outline-border']").attr(
      "selected",
      "selected"
    );
    jQuery(".image_move_effect").hide();
    jQuery(".border_effect").show();
    jQuery(".overlay_slide").hide();
  }
  if (jQuery("#ffp_gallery_effect_name").val() == "overlay_slide") {
    jQuery("#ffp_gallery_effect").find("option").removeAttr("selected");
    jQuery(
      "#ffp_gallery_effect option[value='effct-veraison-1 content--c3']"
    ).attr("selected", "selected");
    jQuery(".image_move_effect").hide();
    jQuery(".border_effect").hide();
    jQuery(".overlay_slide").show();
  }
}

function ffp_video_light_box_func() {
  if (jQuery("#ffp_specific_videos").is(":checked")) {
    jQuery(".video_light_boxcontents").show();
    jQuery(".photo_light_boxcontents").hide();
  }
  if (jQuery("#ffp_specific_photos").is(":checked")) {
    jQuery(".video_light_boxcontents").hide();
    jQuery(".photo_light_boxcontents").show();
  }
  if (jQuery("#ffp_specific_albums").is(":checked")) {
    jQuery(".video_light_boxcontents").hide();
    jQuery(".photo_light_boxcontents").show();
  }
  if (jQuery("#ffp_specific_events").is(":checked")) {
    jQuery(".video_light_boxcontents").show();
    jQuery(".photo_light_boxcontents").hide();
  }
}

jQuery(document).ready(function () {
  jQuery(".video_light_box").click(function () {
    if (
      jQuery("#ffp_timeline_videos").is(":checked") ||
      jQuery("#ffp_timeline_events").is(":checked")
    ) {
      jQuery(".photo_light_boxcontents").hide();
      jQuery(".video_light_boxcontents").show();
    } else {
      jQuery(".photo_light_boxcontents").show();
      jQuery(".video_light_boxcontents").hide();
    }
  });

  if (jQuery("#ffp_content_timeline").is(":checked")) {
    if (
      jQuery("#ffp_timeline_videos").is(":checked") ||
      jQuery("#ffp_timeline_events").is(":checked")
    ) {
      jQuery(".photo_light_boxcontents").hide();
      jQuery(".video_light_boxcontents").show();
    } else {
      jQuery(".photo_light_boxcontents").show();
      jQuery(".video_light_boxcontents").hide();
    }
  }

  jQuery("#ffp_content_timeline").click(function () {
    if (
      jQuery("#ffp_timeline_videos").is(":checked") ||
      jQuery("#ffp_timeline_events").is(":checked")
    ) {
      jQuery(".photo_light_boxcontents").hide();
      jQuery(".video_light_boxcontents").show();
    } else {
      jQuery(".photo_light_boxcontents").show();
      jQuery(".video_light_boxcontents").hide();
    }
  });

  jQuery("#ffp_content_specific").click(function () {
    if (jQuery("#ffp_specific_videos").is(":checked")) {
      jQuery(".video_light_boxcontents").show();
      jQuery(".photo_light_boxcontents").hide();
    }
    if (jQuery("#ffp_specific_photos").is(":checked")) {
      jQuery(".video_light_boxcontents").hide();
      jQuery(".photo_light_boxcontents").show();
    }
    if (jQuery("#ffp_specific_albums").is(":checked")) {
      jQuery(".video_light_boxcontents").hide();
      jQuery(".photo_light_boxcontents").show();
    }
    if (jQuery("#ffp_specific_events").is(":checked")) {
      jQuery(".video_light_boxcontents").show();
      jQuery(".photo_light_boxcontents").hide();
    }
  });
});
function save_feed_general(security) {
  jQuery("img.admin_loading_css").show();
  jQuery.ajax({
    url: location.href,
    type: "POST",
    //data: jQuery("form#weblizar_feed_setting_option").serialize(),
    data:
      jQuery("form#weblizar_feed_setting_option").serialize() +
      "&security=" +
      security,
    dataType: "html",
    //Do not cache the page
    cache: false,
    //success
    success: function (html) {
      jQuery("img.admin_loading_css").hide();
      jQuery("div.success-msg").show();
      jQuery("div.success-msg").hide(4000);
    },
  });
}
/* feed-script-end*/

jQuery(document).ready(function () {
  // Add minus icon for collapse element which is open by default
  jQuery(".collapse.in").each(function () {
    jQuery(this)
      .siblings(".panel-heading")
      .find(".glyphicon")
      .addClass("glyphicon-minus")
      .removeClass("glyphicon-plus");
  });

  // Toggle plus minus icon on show hide of collapse element
  jQuery(".collapse")
    .on("show.bs.collapse", function () {
      jQuery(this)
        .parent()
        .find(".glyphicon")
        .removeClass("glyphicon-plus")
        .addClass("glyphicon-minus");
    })
    .on("hide.bs.collapse", function () {
      jQuery(this)
        .parent()
        .find(".glyphicon")
        .removeClass("glyphicon-minus")
        .addClass("glyphicon-plus");
    });
});
function SaveSettings() {
  var FacebookPageUrl = jQuery("#facebook-page-url").val();
  var ColorScheme = jQuery("#show-widget-header").val();
  var Header = jQuery("#show-widget-header").val();
  var Stream = jQuery("#show-live-stream").val();
  var Width = jQuery("#widget-width").val();
  var Height = jQuery("#widget-height").val();
  var FbAppId = jQuery("#fb-app-id").val();
  var weblizar_locale_fb = jQuery("#weblizar_locale_fb").val();
  if (!FacebookPageUrl) {
    jQuery("#facebook-page-url").focus();
    return false;
  }
  if (!FbAppId) {
    jQuery("#fb-app-id").focus();
    return false;
  }
  jQuery("#fb-save-settings").hide();
  jQuery("#fb-img").show();
  jQuery.ajax({
    url: location.href,
    type: "POST",
    data: jQuery("form#fb-form").serialize(),
    dataType: "html",
    //Do not cache the page
    cache: false,
    //success
    success: function (html) {
      jQuery("#fb-img").hide();
      jQuery("#fb-msg").show();

      setTimeout(function () {
        location.reload(true);
      }, 2000);
    },
  });
}
