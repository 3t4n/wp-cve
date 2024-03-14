var WpSaioApp = function () {
  var self = this;
  var $ = jQuery;

  const saio_btn_app = document.querySelectorAll(".saio-btn-app");
  const saio_fields_wrap = document.querySelector(".wp_saio_fields_wrap");
  /*
   * Init function
   */

  self.init = function () {
    const saio_sortable = new Sortable(saio_fields_wrap, {
      animation: 200,
      handle: ".handler-sort",
      ghostClass: "wpsaio-sort-ghost",
      dragClass: "wpsaio-sort-drag",
    });
    //saio_btn_app.forEach((i) => {
    $(document).on("click", ".saio-btn-app", function (event) {
      if (this.classList.contains("disabled")) {
        this.classList.remove("disabled");
        this.classList.add("active");
        const get_app = this.parentElement.getAttribute("data-appname");

        const exist_app = document.querySelector(
          `.wpsaio-app-input-wrap[data-appname="${get_app}"]`
        );

        if (!exist_app) {
          const elarray = JSON.parse(wp_saio_object.wp_saio_html_inputs);
          Object.keys(elarray).forEach((a) => {
            if (a === get_app || get_app.includes(a)) {
              get_app_target = get_app.replaceAll("-", "_");
              elarray[a] = elarray[a].replaceAll(
                'data-appname="custom-app"',
                `data-appname="${get_app}"`
              );
              elarray[a] = elarray[a].replaceAll(
                "wpsaio_button_custom_app_icon",
                `wpsaio_button_${get_app_target}_icon`
              );
              elarray[a] = elarray[a].replaceAll(
                "wpsaio__custom_app_button_color",
                `wpsaio__${get_app_target}_button_color`
              );
              elarray[a] = elarray[a].replaceAll(
                "wp-saio-icon-custom-app",
                `wp-saio-icon-${get_app}`
              );
              saio_fields_wrap.insertAdjacentHTML("beforeend", elarray[a]);
            }
          });
        } else {
          saio_fields_wrap.appendChild(exist_app);
          exist_app.classList.remove("app_off");
        }
      } else if (this.classList.contains("active")) {
        this.classList.remove("active");
        this.classList.add("disabled");
        const get_app = this.parentElement.getAttribute("data-appname");
        const app_field = document.querySelector(
          `.wpsaio-app-input-wrap[data-appname="${get_app}"]`
        );
        app_field.classList.add("app_off");
      }
    });
    $(document).on("click", ".saio-btn-app", function (event) {
      if (
        this.parentElement.getAttribute("data-appname").includes("custom-app")
      ) {
        $(".wp_saio_colorpicker").wpColorPicker();

        //Icon Handler
        $(".wp_saio_choose_image_btn").click(function (event) {
          self.renderMediaUploader($(this).data("target"));
        });
      }
    });
    //});

    //Handle Delete
    $(document).on("click", ".wpsaio-btn-move", function (event) {
      const appName = $(this).closest(".wpsaio-app-input-wrap").data("appname");

      $(`.wpsaio-app-input-wrap[data-appname="${appName}"]`).remove();

      $(`.wp-saio-app[data-appname="${appName}"]`).remove();
    });

    $(".wp_saio_colorpicker").wpColorPicker();

    //Button handler
    $(".wp_saio_choose_image_btn").click(function (event) {
      self.renderMediaUploader($(this).data("target"));
    });

    // $('.wp_saio_fields_wrap').disableSelection();

    // Setting Style radio button
    $(document).on(
      "click",
      "button[class^='button btn-style']",
      function (event) {
        const styleValue = $(this).val();
        if (styleValue === "redirect") {
          $(".btn-popup").removeClass("active");
        } else {
          $(".btn-redirect").removeClass("active");
        }
        $(this).addClass("active");
        $("#wpsaioStyle").val(styleValue);
      }
    );

    // Setting Tooltip radio button
    $(document).on(
      "click",
      "button[class^='button btn-tooltip']",
      function (event) {
        const tooltipValue = $(this).val();
        if (tooltipValue === "appname") {
          $(".btn-appcontent").removeClass("active");
        } else {
          $(".btn-appname").removeClass("active");
        }
        $(this).addClass("active");
        $("#wpsaioTooltip").val(tooltipValue);
      }
    );

    // Setting Widget Position radio button
    $(document).on(
      "click",
      "button[class^='button btn-widget-position']",
      function (event) {
        const widgetPositionValue = $(this).val();
        if (widgetPositionValue === "left") {
          $(".btn-right").removeClass("active");
        } else {
          $(".btn-left").removeClass("active");
        }
        $(this).addClass("active");
        $("#wpsaioWidgetPosition").val(widgetPositionValue);
      }
    );

    // Setting Button Image radio button
    $(document).on(
      "click",
      "button[class^='button btn-button-image']",
      function (event) {
        const buttonImageValue = $(this).val();
        if (buttonImageValue === "contain") {
          $(".btn-cover").removeClass("active");
        } else {
          $(".btn-contain").removeClass("active");
        }
        $(this).addClass("active");
        $("#wpsaioButtonImage").val(buttonImageValue);
      }
    );

    // Get choose messaging apps data and submit
    $(document).on(
      "click",
      ".button-choose-apps:not(.wpsaio-saving)",
      function (event) {
        event.preventDefault();

        const formDataArray = [];

        $(".saio-input > input").each(function () {
          const appName = $(this).data("appname");
          if (!appName) return;
          const appKey = $(this).data("appkey");
          const inputValue = $(this).val();
          const app_state = $(
            `.wpsaio-app-input-wrap[data-appname="${appName}"]`
          ).attr("class");

          let inputDataObject = {
            name: appName,
            key: appKey,
            value: inputValue,
            state: app_state
              ? app_state.replace("wpsaio-app-input-wrap", "")
              : "",
          };
          if (appName.includes("custom-app")) {
            const replaceName = appName.replaceAll("-", "_");
            const customAppTitle = $(this)
              .parents(".wpsaio-app-input-wrap")
              .find(".wp-saio-title")
              .val()
              .trim();
            const urlIcon = $(`#wpsaio_button_${replaceName}_icon`).val();
            const colorIcon = $(`#wpsaio__${replaceName}_button_color`).val();
            inputDataObject = {
              ...inputDataObject,
              customAppTitle,
              urlIcon,
              colorIcon,
            };
          }
          formDataArray.push(inputDataObject);
        });

        jQuery.ajax({
          type: "POST",
          url: ajaxurl,
          data: {
            nonce: wp_saio_object.nonce,
            action: "wpsaio_choose_apps_settings",
            data: {
              formDataArray,
            },
          },
          beforeSend: function () {
            $(".wpsaio-save").addClass("wpsaio-saving");
          },
          success: function (response) {
            $(".wpsaio-save").removeClass("wpsaio-saving");
            $(".notice-success").show();
            $(".notice-dismiss").click(function () {
              $(".notice-success").hide("slow");
            });
            setTimeout(function () {
              $(".notice-success").hide("slow");
            }, 3000);
          },
          error: function (error) {
            console.log(error);
            $(".wpsaio-save").removeClass("wpsaio-saving");
            $(".notice-success").show();
            $("notice-dismiss").click(function () {
              $(".notice-success").hide("slow");
            });
            setTimeout(function () {
              $(".settings-error").hide("slow");
            }, 3000);
          },
        });
      }
    );

    // Get design settings data and submit
    $(document).on(
      "click",
      ".button-design-settings:not(.wpsaio-saving)",
      function (event) {
        event.preventDefault();
        const enablePlugin = $("#wpsaio-enable-plugin-switch").prop("checked")
          ? 1
          : 0;
        const style = $("#wpsaioStyle").val();
        const toolTip = $("#wpsaioTooltip").val();
        const widgetPosition = $("#wpsaioWidgetPosition").val();
        const paddingFromBottom = $("#wpsaio_bottom_distance").val();
        const buttonIcon = $("#wpsaio_button_icon").val();
        const buttonImage = $("#wpsaioButtonImage").val();
        const buttonColor = $("#wpsaio_button_color").val();

        jQuery.ajax({
          type: "POST",
          url: ajaxurl,
          data: {
            nonce: wp_saio_object.nonce,
            action: "wpsaio_design_settings",
            data: {
              enablePlugin,
              style,
              toolTip,
              widgetPosition,
              paddingFromBottom,
              buttonIcon,
              buttonImage,
              buttonColor,
            },
          },
          beforeSend: function () {
            $(".wpsaio-save").addClass("wpsaio-saving");
          },
          success: function (response) {
            $(".wpsaio-save").removeClass("wpsaio-saving");
            $(".notice-success").show();
            $(".notice-dismiss").click(function () {
              $(".notice-success").hide("slow");
            });
            setTimeout(function () {
              $(".notice-success").hide("slow");
            }, 3000);
          },
          error: function (error) {
            console.log(error);
            $(".wpsaio-save").removeClass("wpsaio-saving");
            $(".notice-success").show();
            $("notice-dismiss").click(function () {
              $(".notice-success").hide("slow");
            });
            setTimeout(function () {
              $(".settings-error").hide("slow");
            }, 3000);
          },
        });
      }
    );

    // handle changing display condition
    $("#displayCondition").change(function () {
      const display = $(this).val();
      if (display == "includePages") {
        $(".nta-wa-pages-content.include-pages").show();
        $(".nta-wa-pages-content.include-pages").removeClass("hide-select");
        $(".nta-wa-pages-content.exclude-pages").addClass("hide-select");
      } else if (display === "excludePages") {
        $(".nta-wa-pages-content.exclude-pages").show();
        $(".nta-wa-pages-content.exclude-pages").removeClass("hide-select");
        $(".nta-wa-pages-content.include-pages").addClass("hide-select");
      } else {
        $(".nta-wa-pages-content").hide();
      }
    });

    // Get display settings data and submit
    $(document).on(
      "click",
      ".button-display-settings:not(.wpsaio-saving)",
      function (event) {
        event.preventDefault();
        const showOnDesktop = $("#wpsaio-show-desktop-switch").prop("checked")
          ? 1
          : 0;
        const showOnMobile = $("#wpsaio-show-mobile-switch").prop("checked")
          ? 1
          : 0;
        const displayCondition = $("#displayCondition").val();
        let includesPagesArray = [];
        let excludesPagesArray = [];

        $(".includePages").each(function (index, element) {
          $(element).prop("checked") &&
            includesPagesArray.push($(element).val());
        });

        $(".excludePages").each(function (index, element) {
          $(element).prop("checked") &&
            excludesPagesArray.push($(element).val());
        });

        jQuery.ajax({
          type: "POST",
          url: ajaxurl,
          data: {
            nonce: wp_saio_object.nonce,
            action: "wpsaio_display_settings",
            data: {
              showOnDesktop,
              showOnMobile,
              displayCondition,
              includesPagesArray,
              excludesPagesArray,
            },
          },
          beforeSend: function () {
            $(".wpsaio-save").addClass("wpsaio-saving");
          },
          success: function (response) {
            $(".wpsaio-save").removeClass("wpsaio-saving");
            $(".notice-success").show();
            $(".notice-dismiss").click(function () {
              $(".notice-success").hide("slow");
            });
            setTimeout(function () {
              $(".notice-success").hide("slow");
            }, 3000);
          },
          error: function (error) {
            console.log(error);
            $(".wpsaio-save").removeClass("wpsaio-saving");
            $(".notice-success").show();
            $("notice-dismiss").click(function () {
              $(".notice-success").hide("slow");
            });
            setTimeout(function () {
              $(".settings-error").hide("slow");
            }, 3000);
          },
        });
      }
    );

    // Changing setting tabs
    $(document).on("click", ".nav-tab", function (event) {
      event.preventDefault();
      const activeTab = $(".nav-tab-active");
      const currentTab = $(this).attr("href");

      $(this).addClass("nav-tab-active");
      activeTab.removeClass("nav-tab-active");
      $("#form-selected-account > div").hide();
      $(currentTab).show();
    });

    // check/uncheck all pages in display settings
    $("#exclude-pages-checkall").change(function () {
      $(".excludePages").prop("checked", $(this).prop("checked"));
    });

    $("#include-pages-checkall").change(function () {
      $(".includePages").prop("checked", $(this).prop("checked"));
    });
  };
  self.renderMediaUploader = function (target) {
    ("use strict");
    var file_frame;

    // If the media frame already exists, reopen it.
    if (undefined !== file_frame) {
      file_frame.open();
      return;
    }

    // Create a new media frame
    file_frame = wp.media({
      title: wp_saio_object.add_icon_text_title,
      button: {
        text: wp_saio_object.add_icon_text_button,
      },
      multiple: false,
    });
    // When an image is selected in the media frame...
    file_frame.on("select", function () {
      var selection = file_frame.state().get("selection");
      selection.map(function (attachment) {
        attachment = attachment.toJSON();
        if (attachment.id) {
          var file_choosed = attachment.url;
          $(target).val(file_choosed);
          $(".media-modal-close").click();
        }
      });
    });
  };
};

window.addEventListener("load", function () {
  const njt_nav_tabs = document.querySelector(".njt-nav-tabs"),
    njt_nav_link = document.querySelectorAll(".njt-nav-link"),
    njt_tab_panel = document.querySelectorAll(".njt-tab-panel");
  if (njt_nav_tabs) {
    njt_nav_link.forEach((a) => {
      a.addEventListener("click", function (e) {
        e.preventDefault();
        const tabid = this.getAttribute("data-njt-tab");
        const activeTab = document.querySelector(tabid);
        njt_nav_link.forEach((b) => {
          b.classList.remove("njt-nav-link-active");
        });
        njt_tab_panel.forEach((c) => {
          c.classList.remove("njt-tab-active");
        });
        this.classList.add("njt-nav-link-active");
        activeTab.classList.add("njt-tab-active");
      });
    });
  }
});

jQuery(document).ready(function ($) {
  var wp_saio_app = new WpSaioApp();
  wp_saio_app.init();
  WpSaioAddCustomApp($);
});

const WpSaioAddCustomApp = function ($) {
  jQuery("#add-new-custom-app").click(function (event) {
    var parentContainer = $(".wp_saio_panel_wrap");
    var newAppDiv = $("<div>");
    var timeStamp = Date.now();
    newAppDiv
      .addClass("wp-saio-app")
      .attr("data-appname", `custom-app-${timeStamp}`).html(`
            <button class="saio-btn-custom-app saio-btn-app wp-saio-icon wp-saio-icon-custom-app-${timeStamp} disabled" type="button">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                    <path d="M344.476 105.328L1.004 448.799 64.205 512l343.472-343.471-63.201-63.201zm-53.882 96.464l53.882-53.882 20.619 20.619-53.882 53.882-20.619-20.619zM410.885 78.818l37.657-37.656 21.29 21.29-37.656 37.657zM405.99 274.144l21.29-21.29 38.367 38.366-21.29 21.29zM198.501 66.642l21.29-21.29 38.13 38.127-21.292 21.291zM510.735 163.868h-54.289v30.111H510.996v-30.111zM317.017.018v54.289h30.111V0z"></path>
                </svg>
            </button>
        `);
    parentContainer.append(newAppDiv);
  });
};
