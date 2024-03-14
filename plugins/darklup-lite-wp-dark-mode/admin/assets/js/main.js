(function ($) {
  "use strict";

  let a = {};

  a = {
    darkEnabledClass: "darkluplite-admin-dark-mode-enabled",
    init: function () {
      // Color Picker
      $(".color-picker").wpColorPicker();

      // Select 2 support
      $(".darkluplite-select2").select2();
      //
      this.proFeaturePopup();
      this.proPopupClose();
      this.niceSelect();
      this.SettingsPageTab();
      this.MagnificPopup();
      this.customCSSEditor();
      this.fieldCondition();
      this.dynamicPresetsCondition();
      this.switchStylePreview();
      this.repeaterField();
      this.mediaUploader();
      this.darklupliteAnalyticsChart();
      this.previewLivePresets();
      this.previewAdminLivePresets();
      this.sliderValue();
      this.imageEffects();
      this.saveFormValue();
    },
    windowOnLoad: function () {
      //
      let getStorageData = localStorage.getItem("adminDarklupModeEnabled"),
        getTriggerChecked = localStorage.getItem("adminTriggerChecked"),
        $darkIcon = $(".admin-dark-icon"),
        $lightIcon = $(".admin-light-icon");
      //

      if (getStorageData && getTriggerChecked && typeof isBackendDarkLiteModeSettingsEnabled != "undefined") {
        $("html").toggleClass(this.darkEnabledClass);
        $(".switch-trigger").attr("checked", true);
        $(".darkluplite-mode-switcher").addClass("darkluplite-admin-dark-ignore");
        $darkIcon.show();
        $lightIcon.hide();
        $("html").show();
      } else {
        $("html").show();
      }
    },
    handleKeyShortcut: function () {
      let $that = this;
      if (isKeyShortDarkModeEnabled) {
        var ctrlDown = false;
        $(document).keydown(function (e) {
          if (e.which === 17) ctrlDown = true;
        });
        $(document).keyup(function (e) {
          if (e.which === 17) ctrlDown = false;
        });
        $(document).keydown(function (event) {
          if (ctrlDown && event.altKey && event.which === 68) {
            $("html").toggleClass($that.darkEnabledClass);

            if ($($that.switchTrigger).is(":checked")) {
              localStorage.removeItem("adminDarklupModeEnabled");
              localStorage.removeItem("adminTriggerChecked");
              $($that.switchTrigger).prop("checked", false);
              $(".darkluplite-mode-switcher").removeClass("darkluplite-admin-dark-ignore");
              $(".admin-dark-icon").hide();
              $(".admin-light-icon").show();
            } else {
              localStorage.setItem("adminDarklupModeEnabled", $that.darkEnabledClass);
              localStorage.setItem("adminTriggerChecked", "checked");
              $($that.switchTrigger).prop("checked", true);
              $(".darkluplite-mode-switcher").addClass("darkluplite-admin-dark-ignore");
              $(".admin-dark-icon").show();
              $(".admin-light-icon").hide();
            }
          }
        });
      }
    },
    configureToast: function(){
      toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": false,
        "progressBar": true,
        "positionClass": "toast-bottom-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "2000",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
      }  
    },
    
    saveFormValue: function(){
      this.configureToast();
      let formSelector = '.admin-darklup';
      let submitBtn = jQuery('.darkluplite-ajax-save');
      let spinner = jQuery('.dashicons-darkluplite-ajax-save');
      
      function serializeFormValues() {
        return $(formSelector).serializeArray();
      }
      $(document).ready(function() {
        $(formSelector).submit(function(e) {
          e.preventDefault(); // Prevent form submission
          
          submitBtn.val('Please Wait..');
          submitBtn.prop('disabled', true);
          spinner.addClass('darkluplite--spin');
          
          var formValues = serializeFormValues();
          
          let darklupData = {
            'action': 'darkluplite_save_admin_settings',
            'data': formValues
          };
          // console.log(darklupData);
          
          jQuery.ajax({
            url: darklupPresets.ajaxurl, type: "POST", data: darklupData,
            success: function (response) {
            
              // console.log(response);
              if(response == 'update_success'){
                Command: toastr["success"]("Settings Saved Successfully!");
                submitBtn.val('Save Settings');
                submitBtn.prop('disabled', false);
                spinner.removeClass('darkluplite--spin');
              }else if(response == 'same'){
                Command: toastr["warning"]("Nothing updated");
                submitBtn.val('Save Settings');
                submitBtn.prop('disabled', false);
                spinner.removeClass('darkluplite--spin');
              }else{
                Command: toastr["error"]("Failed, Please try again!");
                submitBtn.val('Save Settings');
                submitBtn.prop('disabled', false);
                spinner.removeClass('darkluplite--spin');
              }
            
            },
            error: function(response) {
              console.log(response);
            }
        });
          
        });
      });
    },
    previewLivePresets: function () {
      var presets = $(".settings-color-preset.front-end-dark--presets .rect-design input");
      presets.on("click", (e) => {
        let bgPicker = $(".wpc_wrap_custom_bg_color .color-picker");
        let secondaryBgPicker = $(".wpc_wrap_custom_secondary_bg_color .color-picker");
        let tertiaryBgPicker = $(".wpc_wrap_custom_tertiary_bg_color .color-picker");
        let textColPicker = $(".wpc_wrap_custom_text_color .color-picker");
        let linkPicker = $(".wpc_wrap_custom_link_color .color-picker");
        let linkHoverPicker = $(".wpc_wrap_custom_link_hover_color .color-picker");
        let borderPicker = $(".wpc_wrap_custom_border_color .color-picker");
        let btnBgPicker = $(".wpc_wrap_custom_btn_bg_color .color-picker");
        let btnTextPicker = $(".wpc_wrap_custom_btn_text_color .color-picker");
        let inputBgPicker = $(".wpc_wrap_custom_input_bg_color .color-picker");
        let inputPlacePicker = $(".wpc_wrap_custom_input_place_color .color-picker");
        let inputTextPicker = $(".wpc_wrap_custom_input_text_color .color-picker");

        let ThisPreset;

        var preset = e.target;
        var value = $(preset).val();
        ThisPreset = darklupPresets[value];

        this.setColorPickerValue(bgPicker, ThisPreset["background-color"]);
        this.setColorPickerValue(secondaryBgPicker, ThisPreset["secondary_bg"]);
        this.setColorPickerValue(tertiaryBgPicker, ThisPreset["tertiary_bg"]);
        this.setColorPickerValue(textColPicker, ThisPreset["color"]);
        this.setColorPickerValue(linkPicker, ThisPreset["anchor-color"]);
        this.setColorPickerValue(linkHoverPicker, ThisPreset["anchor-hover-color"]);
        this.setColorPickerValue(borderPicker, ThisPreset["border-color"]);
        this.setColorPickerValue(btnBgPicker, ThisPreset["btn-bg-color"]);
        this.setColorPickerValue(btnTextPicker, ThisPreset["btn-color"]);
        this.setColorPickerValue(inputBgPicker, ThisPreset["input-bg-color"]);
        this.setColorPickerValue(inputTextPicker, ThisPreset["color"]);
        // this.setColorPickerValue(inputPlacePicker, ThisPreset["tertiary_bg"]);
      });
    },
    previewAdminLivePresets: function () {
      var presets = $(".settings-color-preset.dashboard-dark--presets .rect-design input");
      presets.on("click", (e) => {
        let bgPicker = $(".wpc_wrap_admin_custom_bg_color .color-picker");
        let secondaryBgPicker = $(".wpc_wrap_admin_custom_secondary_bg_color .color-picker");
        let tertiaryBgPicker = $(".wpc_wrap_admin_custom_tertiary_bg_color .color-picker");
        let textColPicker = $(".wpc_wrap_admin_custom_text_color .color-picker");
        let linkPicker = $(".wpc_wrap_admin_custom_link_color .color-picker");
        let linkHoverPicker = $(".wpc_wrap_admin_custom_link_hover_color .color-picker");
        let borderPicker = $(".wpc_wrap_admin_custom_border_color .color-picker");
        let btnBgPicker = $(".wpc_wrap_admin_custom_btn_bg_color .color-picker");
        let btnTextPicker = $(".wpc_wrap_admin_custom_btn_text_color .color-picker");
        let inputBgPicker = $(".wpc_wrap_admin_custom_input_bg_color .color-picker");
        let inputPlacePicker = $(".wpc_wrap_admin_custom_input_place_color .color-picker");
        let inputTextPicker = $(".wpc_wrap_admin_custom_input_text_color .color-picker");

        let ThisPreset;

        var preset = e.target;
        var value = $(preset).val();
        ThisPreset = darklupPresets[value];

        this.setColorPickerValue(bgPicker, ThisPreset["background-color"]);
        this.setColorPickerValue(secondaryBgPicker, ThisPreset["secondary_bg"]);
        this.setColorPickerValue(tertiaryBgPicker, ThisPreset["tertiary_bg"]);
        this.setColorPickerValue(textColPicker, ThisPreset["color"]);
        this.setColorPickerValue(linkPicker, ThisPreset["anchor-color"]);
        this.setColorPickerValue(linkHoverPicker, ThisPreset["anchor-hover-color"]);
        this.setColorPickerValue(borderPicker, ThisPreset["border-color"]);
        this.setColorPickerValue(btnBgPicker, ThisPreset["btn-bg-color"]);
        this.setColorPickerValue(btnTextPicker, ThisPreset["btn-color"]);
        this.setColorPickerValue(inputBgPicker, ThisPreset["input-bg-color"]);
        this.setColorPickerValue(inputTextPicker, ThisPreset["color"]);
        // this.setColorPickerValue(inputPlacePicker, ThisPreset["tertiary_bg"]);
      });
    },
    XYZpreviewLivePresets: function () {
      console.log("Init");
      // let presetBox = document.querySelectorAll(
      //   ".settings-color-preset.front-end-dark--presets .rect-design input"
      // );
      var presets = $(".settings-color-preset.front-end-dark--presets .rect-design input");
      // let color1 = "yellow";
      let color1 = "#ED1111";
      let color2 = "#E5F812";
      let color3 = "#12F836";
      let colorPresets = darklupPresets;
      console.log(colorPresets);
      // let bgBtn = $(".wpc_wrap_custom_bg_color button.wp-color-result");

      // let secondaryBg =  $(".wpc_wrap_custom_secondary_bg_color button.wp-color-result");

      presets.on("click", function (e) {
        let bgPicker = $(".wpc_wrap_custom_bg_color .color-picker");
        let secondaryBgPicker = $(".wpc_wrap_custom_secondary_bg_color .color-picker");
        let ThisPreset, myBgColor;
        // let bgBtn;
        let bgBtn = bgPicker.closest(".wp-picker-container").find("button.wp-color-result");

        // console.log(bgPicker, bgBtn);
        var preset = e.target;
        var value = $(preset).val();

        if (value == 1) {
          ThisPreset = darklupPresets[value];
          console.log(ThisPreset);

          myBgColor = ThisPreset["background-color"];
          console.log(myBgColor);

          $(bgPicker).val(color1);
          bgBtn.css("background-color", color1);

          $(secondaryBgPicker).val(color1);
          bgBtn.css("background-color", color1);

          // console.log(`My value ${value}`);
        } else if (value == 2) {
          bgBtn.css("background-color", color2);
          $(bgPicker).val(color2);
          // console.log(`My value ${value}`);
        } else if (value == 3) {
          bgBtn.css("background-color", color3);
          $(bgPicker).val(color3);
          // console.log(`My value ${value}`);
        }

        // console.log(preset.attr("value"));
      });
    },

    setColorPickerValue: function (element, color) {
      let bgBtn = element.closest(".wp-picker-container").find("button.wp-color-result");
      bgBtn.css("background-color", color);
      $(element).val(color);
    },
    darkModeSwitchEvent: function () {
      let $that = this;

      //
      $(".switch-trigger").on("click", function () {
        let $this = $(this),
          $switcher = $this.closest(".darkluplite-mode-switcher"),
          $darkIcon = $(".admin-dark-icon"),
          $lightIcon = $(".admin-light-icon");

        $("html").toggleClass($that.darkEnabledClass);

        // Storage data
        if ($this.is(":checked")) {
          localStorage.setItem("adminDarklupModeEnabled", $that.darkEnabledClass);
          localStorage.setItem("adminTriggerChecked", "checked");
          $switcher.addClass("darkluplite-admin-dark-ignore");
          $darkIcon.show();
          $lightIcon.hide();
        } else {
          $switcher.removeClass("darkluplite-admin-dark-ignore");
          localStorage.removeItem("adminDarklupModeEnabled");
          localStorage.removeItem("adminTriggerChecked");
          $darkIcon.hide();
          $lightIcon.show();
        }
      });
    },
    niceSelect: function () {
      if ($(".nice-select-active").length) {
        $(".nice-select-active").niceSelect();
      }
    },
    SettingsPageTab: function () {
      if ($(".darkluplite-menu-inner")[0]) {
        // Settings page tab
        $("[data-target-id]").on("click", function (e) {
          e.preventDefault();
          var $this = $(this),
            getId = $this.data("target-id");

          localStorage.setItem("tabActivationDarklupLite", getId);

          $(".active").removeClass("active");
          $this.addClass("active");
          $(".darkluplite-d-show").removeClass("darkluplite-d-show").addClass("darkluplite-d-hide");
          $("#" + getId)
            .removeClass("darkluplite-d-hide")
            .addClass("darkluplite-d-show");
        });

        // Check active tab
        let activateTab = localStorage.getItem("tabActivationDarklupLite");
        if (activateTab == "darkluplite_general_settings") {
          activateTab = "darkluplite_color_settings";
        }

        if (activateTab) {
          $(".active").removeClass("active");
          $('[data-target-id="' + activateTab + '"]').addClass("active");
          $(".darkluplite-d-show").removeClass("darkluplite-d-show").addClass("darkluplite-d-hide");
          $("#" + activateTab)
            .removeClass("darkluplite-d-hide")
            .addClass("darkluplite-d-show");
        }
      }
    },
    SettingsInnerPageTab: function () {
      if ($(".darkluplite-menu-inner")[0]) {
        // Settings page tab
        $("[data-target-id]").on("click", function (e) {
          e.preventDefault();
          var $this = $(this),
            getId = $this.data("target-id");

          localStorage.setItem("tabActivationDarklupLite", getId);

          $(".active").removeClass("active");
          $this.addClass("active");

          $(".darkluplite-d-show").removeClass("darkluplite-d-show").addClass("darkluplite-d-hide");
          $("#" + getId)
            .removeClass("darkluplite-d-hide")
            .addClass("darkluplite-d-show");
        });

        // Check active tab
        let activateTab = localStorage.getItem("tabActivationDarklupLite");
        if (activateTab) {
          $(".active").removeClass("active");
          $('[data-target-id="' + activateTab + '"]').addClass("active");
          $(".darkluplite-d-show").removeClass("darkluplite-d-show").addClass("darkluplite-d-hide");
          $("#" + activateTab)
            .removeClass("darkluplite-d-hide")
            .addClass("darkluplite-d-show");
        }
      }
    },
    MagnificPopup: function () {
      /* -------------------------------------------------
                Magnific JS
            ------------------------------------------------- */
      $(".video-play-btn").magnificPopup({
        type: "iframe",
        removalDelay: 260,
        mainClass: "mfp-zoom-in",
      });
      $.extend(true, "", {
        iframe: {
          patterns: {
            youtube: {
              index: "youtube.com/",
              id: "v=",
              src: "",
            },
          },
        },
      });
    },
    customCSSEditor: function () {
      //
      var isEditor = document.getElementById("darklupEditor");

      if (isEditor != null) {
        // Css Editor
        var cssEditor = ace.edit("darklupEditor");
        cssEditor.setTheme("ace/theme/monokai");
        cssEditor.session.setMode("ace/mode/css");

        $("form").on("submit", function (e) {
          document.getElementById("editortext").value = cssEditor.getValue();
        });
      }
    },
    dynamicPresetsCondition(){
      let thisClass = this;
      // console.log($(this));
      // console.log(`Nested Try`);
      let condition = $("[data-extra_condition]");
      condition.each(function () {
        let $this = $(this);
        // console.log($this.getAttr());
        let i = $(this).data("extra_condition");

        if (!i) return;

        let o = $("." + i.key);
        if (o.length == 0) {
          // console.log(`O length 0)`);
          // let btnCondition = $this.data("btncondition");

          let btnCondition = $this.data("extra_condition");
          // console.log(btnCondition);

          var radio = 'input[name="darkluplite_settings[' + i.key + ']"]';
          // console.log(radio);
          
          var radioChecked = 'input[name="darkluplite_settings[' + i.key + ']"]:checked';
          // console.log(radioChecked);
          
          
          if ($(radioChecked).val() == i.value) {
            $this.show();
            thisClass.fieldCondition();


            // let thisSwitcher = $(`.${btnCondition.key}`);
            // if (thisSwitcher.is(":checked")) {
            //   $this.show();
            // } else {
            //   $this.hide();
            // }

            // if (btnCondition) {
            //   console.log(btnCondition);
            //   let thisSwitcher = $(`.${btnCondition.key}`);
            //   console.log(thisSwitcher);
            //   if (thisSwitcher.is(":checked")) {
            //     $this.show();
            //   } else {
            //     $this.hide();
            //   }
            // } else {
            //   $this.show();
            // }
          } else {
            $this.hide();
          }

          $(radio).click(function () {
            if ($(this).val() == i.value) {
              if (btnCondition) {
                let thisSwitcher = $(`.${btnCondition.key}`);
                if (thisSwitcher.is(":checked")) {
                  $this.show();
                } else {
                  $this.hide();
                  thisClass.fieldCondition();
                }
              } else {
                $this.show();
              }
            } else {
              // if (btnCondition) {
              //   console.log(btnCondition);
              //   console.log(`Hide`);
              //   console.log($this);
              // }

              $this.hide();
            }
          });
        } else {
          console.log(`O length recorded)`);
          o.on("click", function () {
            if ($(this).is(":checked")) {
              $this.show();
            } else {
              $this.hide();
            }
          });

          // On load event
          if (o.is(":checked")) {
            let nestedCheck = $this.data("extra_condition");
            if(nestedCheck){
              console.log(`Nested Detected`);
            }
            $this.show();
          } else {
            $this.hide();
          }
        }
      });
    },
    fieldCondition: function () {
      /**
       *  Condition field
       */

      let condition = $("[data-condition]");

      condition.each(function () {
        let $this = $(this);
        // console.log($this.getAttr());
        let i = $(this).data("condition");

        if (!i) {
          return;
        }

        let o = $("." + i.key);
        if (o.length == 0) {
          var radio = 'input[name="darkluplite_settings[' + i.key + ']"]';
          var radioChecked = 'input[name="darkluplite_settings[' + i.key + ']"]:checked';
          if ($(radioChecked).val() == i.value) {
            $this.show();
          } else {
            $this.hide();
          }
          $(radio).click(function () {
            if ($(this).val() == i.value) {
              $this.show();
            } else {
              $this.hide();
            }
          });
        } else {
          o.on("click", function () {
            if ($(this).is(":checked")) {
              $this.show();
            } else {
              $this.hide();
            }
          });

          // On load event
          if (o.is(":checked")) {
            $this.show();
          } else {
            $this.hide();
          }
        }
      });
    },
    switchStylePreview: function () {
      this.switchStylePreviewEvent("switch_style");
      this.switchStylePreviewEvent("switch_style_mobile");
      this.switchStylePreviewEvent("switch_style_menu");
    },
    switchStylePreviewEvent: function ($field_name) {
      this.switchStylePreviewDo($field_name);
      let clickedSwitch = $('input[name="darkluplite_settings[' + $field_name + ']"]');
      clickedSwitch.on("click", () => {
        this.switchStylePreviewDo($field_name);
      });
    },
    switchStylePreviewDo: function ($field_name) {
      for (var x = 1; x <= 15; x++) {
        let switcher = $('input[name="darkluplite_settings[' + $field_name + ']"][value="' + x + '"]');
        if (switcher.is(":checked")) {
          let previewInner = switcher.closest(".darkluplite-row").find(".darkluplite-switch-preview-inner");
          previewInner.find(".darkluplite-switch-preview").hide();
          previewInner.find(".darkluplite-switch-preview-" + x).show();
          previewInner
            .find(".darkluplite-switch-preview-" + x + " .toggle-checkbox")
            .delay(1000)
            .animate(
              {
                checked: true,
              },
              600
            )
            .delay(500)
            .animate(
              {
                checked: false,
              },
              600
            );
        }
      }
    },
    mediaUploader: function () {
      // Media Upload
      var mediaUploader, t;

      $(".darkluplite_image_upload_btn").on("click", function (e) {
        e.preventDefault();

        t = $(this).parent().find(".darkluplite_image_uploader");

        if (mediaUploader) {
          mediaUploader.open();
          return;
        }
        mediaUploader = wp.media.frames.file_frame = wp.media({
          title: "Choose Image",
          button: {
            text: "Choose Image",
          },
          multiple: false,
        });
        mediaUploader.on("select", function () {
          var attachment = mediaUploader.state().get("selection").first().toJSON();

          t.val(attachment.url);
        });
        mediaUploader.open();
      });
    },
    repeaterField: function () {
      $(document).on("click", ".addtime", function (e) {
        console.log("say hello");

        e.preventDefault();

        var $this = $(this);

        var inner = $this.parent().find(".field-wrapper");

        var $new_repeater = "";
        $new_repeater += '<div class="single-field">';
        $new_repeater += '<input type="text" name="darkluplite_settings[light_img][]" placeholder="Light Image Url" />';
        $new_repeater += '<input type="text" name="darkluplite_settings[dark_img][]" placeholder="Dark Image Url" />';
        $new_repeater += '<span class="removetime fb-admin-btn">Remove</span>';
        $new_repeater += "</div>";

        inner.append($new_repeater);
      });

      //
      $(document).on("click", ".removetime", function () {
        var $this = $(this);

        $this.parent().remove();
      });
    },
    proFeaturePopup: function () {
      $(".pro-feature").on("click", function () {
        $(".darklup-admin-popup-wrapper").fadeIn("4000");
        $(".darklup-single-popup-wrapper").show();
      });
    },
    proPopupClose: function () {
      $(".darklup-admin-close").on("click", function (e) {
        e.preventDefault();

        $(".darklup-admin-popup-wrapper").fadeOut();
        $(".darklup-single-popup-wrapper").hide();
      });
    },
    darklupliteAnalyticsChart: function () {
      const labels = $("#darklup_analytics_Chart").attr("data-labels");
      const data_values = $("#darklup_analytics_Chart").attr("data-values");

      if (labels != null) {
        const data = {
          labels: JSON.parse(labels),
          datasets: [
            {
              label: "Dark Mode Usages",
              backgroundColor: "#fff",
              borderColor: "rgb(255, 99, 132)",
              data: JSON.parse(data_values),
            },
          ],
        };

        const config = {
          type: "line",
          data: data,
          options: {
            plugins: {
              legend: {
                display: false,
              },
            },
          },
        };

        const darklupAnalyticsChart = new Chart(document.getElementById("darklup_analytics_Chart"), config);
      }
    },
    sliderValue: function () {
      var grayscaleVal = $("#darkluplite_image_grayscale").val();
      var brightnessVal = $("#darkluplite_image_brightness").val();
      var contrastVal = $("#darkluplite_image_contrast").val();
      var opacityVal = $("#darkluplite_image_opacity").val();
      var sepiaVal = $("#darkluplite_image_sepia").val();

      var darkmode_level = $("#darkluplite_darkmode_level").val();

      $("#darkluplite_slider_darkmode_level").text(darkmode_level);

      $("#darkluplite_slider_image_grayscale").text(grayscaleVal);
      $("#darkluplite_slider_image_brightness").text(brightnessVal);
      $("#darkluplite_slider_image_contrast").text(contrastVal);
      $("#darkluplite_slider_image_opacity").text(opacityVal);
      $("#darkluplite_slider_image_sepia").text(sepiaVal);

      // $("#darkluplite_darkmode_level").on("input", function () {
      //   var ChangeVal = $(this).val();
      //   $("#darkluplite_slider_darkmode_level").text(ChangeVal);
      // });

      $("#darkluplite_image_grayscale").on("input", function () {
        var ChangeVal = $(this).val();
        var grayscale = `grayscale(${ChangeVal})`;
        preview_image_filter("grayscale", grayscale);
        $("#darkluplite_slider_image_grayscale").text(ChangeVal);
      });

      $("#darkluplite_image_brightness").on("input", function () {
        var ChangeVal = $(this).val();
        var brightness = `brightness(${ChangeVal})`;
        preview_image_filter("brightness", brightness);
        $("#darkluplite_slider_image_brightness").text(ChangeVal);
      });

      $("#darkluplite_image_contrast").on("input", function () {
        var ChangeVal = $(this).val();
        var contrast = `contrast(${ChangeVal})`;
        preview_image_filter("contrast", contrast);
        $("#darkluplite_slider_image_contrast").text(ChangeVal);
      });

      $("#darkluplite_image_opacity").on("input", function () {
        var ChangeVal = $(this).val();
        var opacity = `opacity(${ChangeVal})`;
        preview_image_filter("opacity", opacity);
        $("#darkluplite_slider_image_opacity").text(ChangeVal);
      });

      $("#darkluplite_image_sepia").on("input", function () {
        var ChangeVal = $(this).val();
        var sepia = `sepia(${ChangeVal})`;
        preview_image_filter("sepia", sepia);
        $("#darkluplite_slider_image_sepia").text(ChangeVal);
      });

      var grayscaleCss = `grayscale(${grayscaleVal})`;
      var brightnessCss = `brightness(${brightnessVal})`;
      var contrastCss = `contrast(${contrastVal})`;
      var opacityCss = `opacity(${opacityVal})`;
      var sepiaCss = `sepia(${sepiaVal})`;

      function preview_image_filter(filterName, filterVal) {
        var inlineCss = " ";

        if ("grayscale" === filterName) {
          grayscaleCss = filterVal;
        } else if ("brightness" === filterName) {
          brightnessCss = filterVal;
        } else if ("contrast" === filterName) {
          contrastCss = filterVal;
        } else if ("opacity" === filterName) {
          opacityCss = filterVal;
        } else if ("sepia" === filterName) {
          sepiaCss = filterVal;
        }
        inlineCss = inlineCss.concat(grayscaleCss);
        inlineCss = inlineCss.concat(brightnessCss);
        inlineCss = inlineCss.concat(contrastCss);
        inlineCss = inlineCss.concat(opacityCss);
        inlineCss = inlineCss.concat(sepiaCss);
        // $(".darkluplite-image-effects-preview img").css({ filter: inlineCss });
        $('.darkluplite-image-effects-preview img').css('cssText', `filter: ${inlineCss} !important;`);

      }
    },
    imageEffects: function () {
      let $preview_image_effects = $(".darkluplite-image-preview-inner").attr("data-settings");

      if ("no" === $preview_image_effects) {
        $(".darkluplite-image-preview-inner").hide();
      }
      $('input.image-effects-on-off[type="checkbox"]').click(function () {
        if ($(this).prop("checked") == true) {
          $(".darkluplite-image-preview-inner").show();
        } else if ($(this).prop("checked") == false) {
          $(".darkluplite-image-preview-inner").hide();
        }
      });
    },
  };

  a.init();
})(jQuery);

// Document on Ready
document.addEventListener("DOMContentLoaded", function () {});
