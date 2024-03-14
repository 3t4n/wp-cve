(function ($) {
  $(document).on("ready", function (e) {
    $(document).on("click", ".ssgsw_notice_dismiss", function (e) {
      e.preventDefault();
      e.stopPropagation(); // Prevent this click from being propagated to the document
      $(".ssgsw_list_option").toggle();
    });

    $(document).on("click", function (event) {
      if (
        !$(event.target).closest(".ssgsw_list_option").length &&
        !$(event.target).is(".ssgsw_notice_dismiss")
      ) {
        $(".ssgsw_list_option").hide();
      }
    });

    $(document).on("click", ".ssgsw_remove_text_dec", function (e) {
      var self = $(this);
      e.preventDefault();
      $.ajax({
        url: ssgsw_notice_data.ajax_url,
        type: "POST",
        data: {
          action: "sssgw_appscript_improved",
          nonce: ssgsw_notice_data.nonce,
        },
        beforeSend: function (response) {
          self.html("Waiting....");
        },
        complete: function (response) {
          self.html("Waiting....");
        },
        success: function (response) {
          if (response.url) {
            window.location.href = response.url;
          }
        },
      });
    });
    $(document).on("click", ".ssgsw_skip_next_time", function (e) {
      var self = $(this);
      e.preventDefault();
      $.ajax({
        url: ssgsw_notice_data.ajax_url,
        type: "POST",
        data: {
          action: "sssgw_notice_skip",
          nonce: ssgsw_notice_data.nonce,
        },
        beforeSend: function (response) {
          self.html("Skipping...");
        },
        complete: function (response) {
          self.html("Not now, skip");
        },
        success: function (response) {
          self.html("Not now, skip");
          $(".ssgsw_list_option").hide();
          $(".ssgsw_appscript_notice3").fadeOut(300);
          $(".ssgsw_appscript_notice").show();
        },
      });
    });
    $(document).on("click", ".ssgsw_dismiss_notice", function (e) {
      e.preventDefault();
      var self = $(this);
      $.ajax({
        url: ssgsw_notice_data.ajax_url,
        type: "POST",
        data: {
          action: "sssgw_already_updated",
          nonce: ssgsw_notice_data.nonce,
        },
        beforeSend: function (response) {
          self.html("Dismissing...");
        },
        complete: function (response) {
          self.html("Dismiss, already updated");
        },
        success: function (response) {
          self.html("Dismiss, already updated");
          $(".ssgsw_appscript_notice3").fadeOut(300);
          $(".ssgsw_appscript_notice").hide();
        },
      });
    });

    $(document).on("click", ".ssgsw_appscript_notice", function (e) {
      $(this).hide();
      $(".ssgsw_appscript_notice3").fadeIn(300);
    });

    //changing the banner position
    //license notice
    const ssgwPage = document.querySelector(".ssgsw-wrapper");
    const licenseNotice = document.querySelector(".ssgsw-license-notice");
    const appScriptNotice = document.querySelector(".ssgsw_appscript_notice3");
    const ratingNotice = document.querySelector(".ssgs-rating-banner");
    const upgradeNotice = document.querySelector(".ssgs-upgrade-banner");
    const influencerNotice = document.querySelector(".ssgs-influencer-banner");
    const alreadyRated = localStorage.getItem("already_rated");
    const upgradeClose = localStorage.getItem("upgrade_button");
    const influencerClose = localStorage.getItem("influencer_button");
    if (ssgwPage) {
      var wpBody = document.querySelector("#wpcontent #wpbody-content");
    } else {
      var wpBody = document.querySelector("#wpcontent .wrap");
      if (wpBody && (ratingNotice || upgradeClose || influencerNotice)) {
        console.log(wpBody);
        wpBody.style.margin = "40px 20px 0 2px";
      }
    }
    if (licenseNotice) {
      // Remove banner from its current position
      licenseNotice.remove();
      wpBody.insertBefore(licenseNotice, wpBody.firstChild);
    }
    // appscript notice
    if (appScriptNotice) {
      appScriptNotice.remove();
      appScriptNotice.style.display = "block";
      if (licenseNotice) {
        wpBody.insertBefore(appScriptNotice, licenseNotice.nextSibling);
      } else {
        wpBody.insertBefore(appScriptNotice, wpBody.firstChild);
      }
    }

    if (ratingNotice && !alreadyRated) {
      ratingNotice.remove();
      ratingNotice.style.display = "flex";
      if (appScriptNotice) {
        wpBody.insertBefore(ratingNotice, appScriptNotice.nextSibling);
      } else {
        wpBody.insertBefore(ratingNotice, wpBody.firstChild);
      }
    }

    if (upgradeNotice && !upgradeClose) {
      // upgradeNotice.remove();
      upgradeNotice.style.display = "flex";
      if (appScriptNotice) {
        wpBody.insertBefore(upgradeNotice, appScriptNotice.nextSibling);
      } else {
        wpBody.insertBefore(upgradeNotice, wpBody.firstChild);
      }
    }

    if (influencerNotice && !influencerClose) {
      // influencerNotice.remove();
      influencerNotice.style.display = "flex";
      if (appScriptNotice) {
        wpBody.insertBefore(influencerNotice, appScriptNotice.nextSibling);
      } else {
        wpBody.insertBefore(influencerNotice, wpBody.firstChild);
      }
    }

    // Rating Star

    const grayIcons = document.querySelectorAll(".ssgs-yellow-icon");

    grayIcons.forEach((icon, index) => {
      icon.addEventListener("mouseover", () => {
        for (let i = index + 1; i < grayIcons.length; i++) {
          grayIcons[i].classList.remove("ssgs-yellow-icon");
          grayIcons[i].classList.add("ssgs-gray-icon");
        }

        // Add 'ssgs-orange-icon' class to icons on the left side
        for (let i = 0; i <= index; i++) {
          grayIcons[i].classList.add("ssgs-orange-icon");
        }

        icon.addEventListener("mouseout", () => {
          for (let i = 0; i <= index; i++) {
            grayIcons[i].classList.remove("ssgs-orange-icon");
            grayIcons[i].classList.remove("ssgs-gray-icon");
          }
          $(".rating-container").each(function () {
            $(this).children().removeClass("ssgs-gray-icon");
            $(this).children().addClass("ssgs-yellow-icon");
          });
        });
        // for (let i = 0; i <= index; i++) {
        //   grayIcons[i].classList.add("ssgs-yellow-icon");
        // }
      });
    });

    //rating popup js
    $(document).on("click", ".ssgs-rating-close", function (e) {
      e.preventDefault();
      var $this = $(this);
      $this.addClass("ssgsw_second_close_button");
      $(".ssgsw_popup-container").css("display", "flex");
      $(".ssgsw_popup-content").css("display", "block");
      $(".ssgsw_first_section2").css("display", "block");
    });

    // dropdown selection of days
    $(".selected-option").on("click", function () {
      $(this).siblings(".options").toggle();
    });

    $(".options li").on("click", function () {
      var selectedValue = $(this).data("value");
      var selectedText = $(this).text();
      // Update the data-days attribute of the selected-option div
      $(".selected-option").data("days", selectedValue).text(selectedText);

      $(".options").hide();
      // You can perform any necessary actions with the selected value here
    });

    $(document).on("click", function (e) {
      var container = $(".ssgw-days-dropdown");
      if (!container.is(e.target) && container.has(e.target).length === 0) {
        $(".options").hide();
      }
    });

    //upgrade popup close
    $(document).on("click", ".ssgs-upgrade-close", function (e) {
      e.preventDefault();
      localStorage.setItem("upgrade_button", true);
      $(this).parent().fadeOut();
    });

    //upgrade popup close
    $(document).on("click", ".ssgs-influencer-close", function (e) {
      e.preventDefault();
      localStorage.setItem("influencer_button", true);
      $(this).parent().fadeOut();
    });

    //rating star onClick
    $(".rating-container .ssgs-yellow-icon").on("click", function () {
      $(".rating-container .ssgs-orange-icon").removeClass("ssgs-orange-icon");
      $(".rating-container .ssgs-gray-icon").removeClass("ssgs-gray-icon");
      $(".rating-container").each(function () {
        $(this).children().addClass("ssgs-yellow-icon");
      });
    });

    // When the 5th (last) span is clicked
    $(".rating-container .ssgs-yellow-icon:last-child").on(
      "click",
      function () {
        // Redirect to a particular hyperlink safely
        const link =
          "https://wordpress.org/support/plugin/stock-sync-with-google-sheet-for-woocommerce/reviews/?filter=5#postform";
        window.open(link, "_blank");
      }
    );

    // When the first 4 spans are clicked
    $(".rating-container .ssgs-yellow-icon:not(:last-child)").on(
      "click",
      function () {
        const supportLink = "https://wppool.dev/contact/";
        window.open(supportLink, "_blank");
      }
    );

    $(document).on("click", ".ssgs-already-rated", function (e) {
      e.preventDefault();
      localStorage.setItem("already_rated", true);
      $(".ssgs-rating-banner").fadeOut();
    });

    //popup js rating
    $(document).on("click", ".ssgsw_close_button", function (e) {
      e.preventDefault();
      var $this = $(this);
      console.log("Close button clicked");
      $this.removeClass("ssgsw_close_button");
      $this.addClass("ssgsw_second_close_button");
      $(".ssgsw_first_section").fadeOut();
      $(".ssgsw_popup-content").fadeOut();
      window.location.reload();
    });
    $(document).on("click", ".ssgsw_submit_button2", function (e) {
      e.preventDefault();
      var $this = $(this);
      var values = $(".selected-option").data("days");
      var data = {
        action: "ssgsw_popup_handle",
        nonce: ssgsw_notice_data.nonce,
        value: values,
      };
      console.log(data);
      $.ajax({
        type: "post",
        url: ssgsw_notice_data.ajax_url,
        data: data,
        beforeSend: function (response) {
          $this.html("Loading...");
        },
        complete: function (response) {
          $this.html("Ok");
        },
        success: function (response) {
          console.log(response);
          if (1 == response.data.days_count) {
            localStorage.setItem("already_rated", true);
          }
          window.location.reload();
        },
      });
    });
  });
})(jQuery);
