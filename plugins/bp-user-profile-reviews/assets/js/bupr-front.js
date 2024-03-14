jQuery(document).ready(function () {
  jQuery(".add_review_msg").hide();
  jQuery(document).on("click", "#bupr-add-review-btn a", function (e) {
    var show_content = jQuery(this).attr("show");
    localStorage.setItem("bupr_show_form", show_content);
  });
  /*----------------------------------------
   * Add Placeholder in search box
   *-----------------------------------------*/
  jQuery('.dataTables_filter input[type="search"]').attr(
    "placeholder",
    "Enter Keywords...."
  );

  /*----------------------------------------
   * Select star on mouse enter
   *-----------------------------------------*/
  var reviews_pluginurl = jQuery("#reviews_pluginurl").val();

  jQuery(".member_stars").mouseenter(function () {
    jQuery(this).parent().children().eq(0).val("not_clicked");
    var id = jQuery(this).attr("data-attr");
    var parent_id = jQuery(this).parent().attr("id");
    for (i = 1; i <= id; i++) {
      console.log(jQuery("#" + parent_id).children("." + i));
      jQuery("#" + parent_id)
        .children("." + i)
        .addClass("fas")
        .removeClass("far");
    }
  });

  /*----------------------------------------
   * Remove Color on stars
   *-----------------------------------------*/
  jQuery(".member_stars").mouseleave(function () {
    var clicked_id = jQuery(this).parent().children().eq(1).val();
    var id = jQuery(this).attr("data-attr");
    var parent_id = jQuery(this).parent().attr("id");
    if (jQuery(this).parent().children().eq(0).val() !== "clicked") {
      var j = parseInt(clicked_id) + 1;
      for (i = j; i <= 5; i++) {
        jQuery("#" + parent_id)
          .children("." + i)
          .addClass("far")
          .removeClass("fas");
      }
    }
  });

  /*----------------------------------------
   * Color the stars on click
   *-----------------------------------------*/
  jQuery(".member_stars").on("click", function () {
    var attr = jQuery(this).attr("data-attr");
    var clicked_id = attr;
    var parent_id = jQuery(this).parent().attr("id");
    jQuery(this).parent().children().eq(1).val(attr);
    jQuery(this).parent().children().eq(0).val("clicked");
    for (i = 1; i <= attr; i++) {
      jQuery("#" + parent_id)
        .children("." + i)
        .addClass("fas")
        .removeClass("far");
    }

    var k = parseInt(attr) + 1;
    for (j = k; j <= 5; j++) {
      jQuery("#" + parent_id)
        .children("." + j)
        .addClass("far")
        .removeClass("fas");
    }
  });

  jQuery("#bupr_member_review_id").on("change", function () {
    jQuery(this).siblings(".bupr-error-fields").hide();
  });

  jQuery("#review_desc").on("keydown", function () {
    jQuery(this).siblings(".bupr-error-fields").hide();
  });

  jQuery(".member_stars").on("click", function () {
    jQuery(this).parent().next(".bupr-error-fields").hide();
  });

  /*----------------------------------------
   * Add new review in member profiles
   *-----------------------------------------*/
  jQuery(document).on("click", "#bupr_save_review", function (event) {
    event.preventDefault();
    var rating_exist = [];
    var bupr_member_id = jQuery("#bupr_member_review_id").val();
    var bupr_current_user = jQuery("#bupr_current_user_id").val();
    var bupr_review_title =
      mail_title.cur_username + " received a " + mail_title.review_title;
    var bupr_review_desc = jQuery("#review_desc").val();
    var bupr_review_count = jQuery("#member_rating_field_counter").val();
    var bupr_review_rating = {};
    // var bupr_review_rating_text = {};
    var empty_rate = 0;

    /* Send anonymous review. */
    var bupr_anonymous_review = "";
    if (jQuery("#bupr_anonymous_review").is(":checked")) {
      bupr_anonymous_review = "yes";
    } else {
      bupr_anonymous_review = "no";
    }

    jQuery(".bupr-star-member-rating").each(function (index) {
      bupr_review_rating[index] = jQuery(this).val();
    });

    // jQuery( '.member_rating_text' ).each(
    // function(index) {
    // bupr_review_rating_text[jQuery( this ).data('id')] = jQuery( this ).val();
    // }
    // );
    jQuery(".bupr-star-member-rating").each(function () {
      var rate_val = jQuery(this).val();
      if (rate_val > 0) {
        empty_rate = empty_rate + 1;
      } else {
        jQuery(this).parent().next(".bupr-error-fields").show();
      }
      rating_exist.push(rate_val);
    });

    if (bupr_review_count > 0) {
      if (bupr_member_id == "") {
        jQuery("#bupr_member_review_id").siblings(".bupr-error-fields").show();
      } else {
        if (jQuery.inArray("0", rating_exist) == -1) {
          jQuery(".bupr-save-reivew-spinner").show();
          jQuery.post(
            ajaxurl,
            {
              action: "allow_bupr_member_review_update",
              bupr_member_id: bupr_member_id,
              bupr_current_user: bupr_current_user,
              bupr_review_title: bupr_review_title,
              bupr_review_desc: bupr_review_desc,
              bupr_review_rating: bupr_review_rating,
              bupr_field_counter: bupr_review_count,
              bupr_anonymous_review: bupr_anonymous_review,
              nonce: mail_title.review_nonce,
            },
            function (response) {
              jQuery(".bupr-save-reivew-spinner").hide();
              sessionStorage.reloadAfterPageLoad = true;
              var date = new Date();
              date.setTime(date.getTime() + 20 * 1000);
              jQuery.cookie("response", response, {
                expires: date,
              });
              window.location.reload();
            }
          );
        }
      }
    } else {
      if (bupr_member_id == "" || bupr_review_desc == "") {
        if (bupr_member_id == "") {
          jQuery("#bupr_member_review_id")
            .siblings(".bupr-error-fields")
            .show();
          event.preventDefault();
        }

        if (bupr_review_desc == "") {
          jQuery("#review_desc").siblings(".bupr-error-fields").show();
          event.preventDefault();
        }
      } else {
        jQuery(".bupr-save-reivew-spinner").show();
        jQuery.post(
          ajaxurl,
          {
            action: "allow_bupr_member_review_update",
            bupr_member_id: bupr_member_id,
            bupr_current_user: bupr_current_user,
            bupr_review_title: bupr_review_title,
            bupr_review_desc: bupr_review_desc,
            bupr_review_rating: bupr_review_rating,
            bupr_field_counter: bupr_review_count,
            bupr_anonymous_review: bupr_anonymous_review,
          },
          function (response) {
            jQuery(".bupr-save-reivew-spinner").hide();
            sessionStorage.reloadAfterPageLoad = true;
            var date = new Date();
            date.setTime(date.getTime() + 20 * 1000);
            jQuery.cookie("response", response, {
              expires: date,
            });
            window.location.reload();
          }
        );
      }
    }
    // }
  });

  // Ratings Widget filter.
  jQuery(document).on(
    "click",
    "#bp-member-rating-list-options > a",
    function (event) {
      event.preventDefault();
      var jQ = jQuery(this);
      jQ.siblings("a").removeClass("selected");
      var filter = jQ.attr("attr-val");
      var limit = jQ.parent().siblings(".member-rating-limit").val();
      jQuery.post(
        ajaxurl,
        {
          action: "bupr_filter_ratings",
          filter: filter,
          limit: limit,
        },
        function (response) {
          var obj = JSON.parse(response);
          jQ.addClass("selected");
          jQ.parent().siblings("#bp-member-rating").html(obj.html);
        }
      );
    }
  );

  // Reviews tab filter.
  jQuery("#bp-reviews-filter-by").change(function () {
    var jQ = "";
    jQuery("#bp-reviews-filter-by option:selected").each(function () {
      var jQ = jQuery(this);
      var filter = jQ.val();
      jQuery.post(
        ajaxurl,
        {
          action: "bupr_reviews_filter",
          filter: filter,
        },
        function (response) {
          jQuery("#request-review-list").html(response);
        }
      );
    });
  });

  /*----------------------------------------
   * Edit Review Pop form
   *-----------------------------------------*/
  jQuery(document).on("click", "#bupr-reiew-edit", function (event) {
    event.preventDefault();
    var reviewwrapper = document.getElementById("bupr-edit-review");
    console.log(reviewwrapper);
    var modal = document.querySelector(".bupr-review-modal");
    var button = jQuery(this);
    var review_id = button.data("review");
    if (review_id) {
      jQuery.post(
        ajaxurl,
        {
          action: "bupr_edit_review",
          review: review_id,
          nonce: mail_title.review_nonce,
        },
        function (response) {
          if (response.success && "" != response.data.review) {
            if ("" !== reviewwrapper.innerHTML) {
              reviewwrapper.innerHTML = "";
            }
            let review = response.data.review;
            jQuery("#bupr-edit-review").append(review);
            modal.classList.add("bupr-show-modal");
          }
        }
      );
    }
  });

  jQuery(document).on(
    "click",
    ".bupr-review-modal-close-button",
    function (event) {
      event.preventDefault();
      var modal = document.querySelector(".bupr-review-modal");
      modal.classList.remove("bupr-show-modal");
    }
  );

  jQuery(window).click(function (e) {
    var modal = document.querySelector(".bupr-review-modal");
    if (e.target === modal) {
      modal.classList.remove("bupr-show-modal");
    }
  });

  /*----------------------------------------
   * Play with starts on review edit popup form
   *-----------------------------------------*/
  jQuery(document).on("mouseenter", ".member-edit-stars", function () {
    jQuery(this).parent().children().eq(0).val("not_clicked");
    var id = jQuery(this).attr("data-attr");
    var parent_id = jQuery(this).parent().attr("id");
    for (i = 1; i <= id; i++) {
      jQuery("#" + parent_id)
        .children("." + i)
        .addClass("fas")
        .removeClass("far");
    }
  });

  jQuery(document).on("mouseleave", ".member-edit-stars", function () {
    var clicked_id = jQuery(this).parent().children().eq(1).val();
    var id = jQuery(this).attr("data-attr");
    var parent_id = jQuery(this).parent().attr("id");
    if (jQuery(this).parent().children().eq(0).val() !== "clicked") {
      var j = parseInt(clicked_id) + 1;
      for (i = j; i <= 5; i++) {
        jQuery("#" + parent_id)
          .children("." + i)
          .addClass("far")
          .removeClass("fas");
      }
    }
  });

  jQuery(document).on("click", ".member-edit-stars", function () {
    var attr = jQuery(this).attr("data-attr");
    var clicked_id = attr;
    var parent_id = jQuery(this).parent().attr("id");
    jQuery(this).parent().children().eq(1).val(attr);
    jQuery(this).parent().children().eq(0).val("clicked");
    for (i = 1; i <= attr; i++) {
      jQuery("#" + parent_id)
        .children("." + i)
        .addClass("fas")
        .removeClass("far");
    }

    var k = parseInt(attr) + 1;
    for (j = k; j <= 5; j++) {
      jQuery("#" + parent_id)
        .children("." + j)
        .addClass("far")
        .removeClass("fas");
    }
  });

  /*----------------------------------------
   * Update review
   *-----------------------------------------*/
  jQuery(document).on("click", "#bupr_upodate_review", function (event) {
    event.preventDefault();
    var modal = document.querySelector(".bupr-review-modal");
    var review_id = jQuery("#bupr-edit-review-field-wrapper").data("review");
    var bupr_review_desc = jQuery("#review_desc").val();
    var bupr_review_rating = {};
    jQuery(".bupr-star-member-rating").each(function (index, item) {
      let critaria = jQuery(this).data("critaria");
      bupr_review_rating[critaria] = jQuery(this).val();
    });

    jQuery(".bupr-save-reivew-spinner").show();
    jQuery.post(
      ajaxurl,
      {
        action: "bupr_update_review",
        review_id: review_id,
        bupr_review_desc: bupr_review_desc,
        bupr_review_rating: bupr_review_rating,
        nonce: mail_title.review_nonce,
      },
      function (response) {
        jQuery(".bupr-save-reivew-spinner").hide();

        if (response.success) {
          modal.classList.remove("bupr-show-modal");
          window.location.reload();
        }
      }
    );
  });
});

/*----------------------------------------
 * Display message after review submit
 *-----------------------------------------*/
jQuery(function () {
  if (jQuery.cookie("response") && jQuery.cookie("response") !== "") {
    jQuery(".add_review_msg p").html(jQuery.cookie("response"));
    jQuery(".add_review_msg").show();
    jQuery.cookie("response", "", -1);
    jQuery("#review_subject").val("");
    jQuery("#review_desc").val("");
  }
});
