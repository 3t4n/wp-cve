jQuery(document).ready(function () {
  // GENERAL FUNCTIONS
  //-----------------------------------------------------------------------

  // Delay something
  var delay = (function () {
    var timer = 0;
    return function (callback, ms) {
      clearTimeout(timer);
      timer = setTimeout(callback, ms);
    };
  })();

  // Displays the current and the max length
  function displayLength(inputfield, outputelement, okaylength, maxlength) {
    var length = jQuery(inputfield).val().length;

    // Highlight length info in color for feedback
    if (length > okaylength && length <= maxlength) {
      jQuery(outputelement).css("color", "#27ae60");
    } else if (length <= okaylength && length > 0) {
      jQuery(outputelement).css("color", "#f39c12");
    } else {
      jQuery(outputelement).css("color", "#e74c3c");
    }

    // Write current length to output
    jQuery(outputelement).html(length + "/" + maxlength);
  }

  // SETTINGS PAGE
  //-----------------------------------------------------------------------

  // TITLE PATTERN
  // if (jQuery('#sseo_title_pattern').length) {
  //   displayLength('#sseo_title_pattern', '#sseo_title_pattern_info', 45, 60);
  //   jQuery('#sseo_title_pattern').on('input', function() {
  //     displayLength('#sseo_title_pattern', '#sseo_title_pattern_info', 45, 60);
  //   });
  // }

  jQuery(".sseo-input-placeholder").click(function () {
    var placeholder = jQuery(this).data("placeholder");
    var target = jQuery(this).data("target");
    jQuery("#" + target).val(jQuery("#" + target).val() + placeholder);
  });

  // DEFAULT METADESCRIPTION
  if (jQuery("#sseo_default_metadescription").length) {
    displayLength(
      "#sseo_default_metadescription",
      "#sseo_default_metadescription_info",
      125,
      155
    );
    jQuery("#sseo_default_metadescription").on("input", function () {
      displayLength(
        "#sseo_default_metadescription",
        "#sseo_default_metadescription_info",
        125,
        155
      );
    });
  }

  // METABOX
  //-----------------------------------------------------------------------

  // TITLE INPUT
  if (jQuery("#sseo-title").length) {
    displayLength("#sseo-title", "#sseo-title-info", 45, 60);

    jQuery("#sseo-title").on("input", function () {
      var currentVal = jQuery(this).val();

      if (!currentVal) {
        jQuery("#sseo-preview-title").html(jQuery("#sseo-title-default").val());
      } else {
        jQuery("#sseo-preview-title").html(jQuery("#sseo-title").val());

        // Render the title after 1 second
        delay(function () {
          var pageid = jQuery("#sseo-pageid").val();

          jQuery
            .ajax({
              type: "POST",
              url: "admin-ajax.php",
              data: {
                action: "generate_title",
                string: currentVal,
                pageid: pageid,
              },
            })
            .done(function (response) {
              jQuery("#sseo-preview-title").html(response);
            });
        }, 500);
      }

      displayLength("#sseo-title", "#sseo-title-info", 45, 60);
    });
  }

  // METADESCRIPTION INPUT
  if (jQuery("#sseo-metadescription").length) {
    displayLength(
      "#sseo-metadescription",
      "#sseo-metadescription-info",
      125,
      155
    );

    jQuery("#sseo-metadescription").on("input", function () {
      if (!jQuery(this).val()) {
        jQuery("#sseo-preview-metadescription").html(
          jQuery("#sseo-metadescription-default").val()
        );
      } else {
        jQuery("#sseo-preview-metadescription").html(
          jQuery("#sseo-metadescription").val()
        );
      }

      displayLength(
        "#sseo-metadescription",
        "#sseo-metadescription-info",
        125,
        155
      );
    });
  }

  jQuery("#sseo-add-to-exclude").on("click", function () {
    var items = jQuery("#sseo-page-list").val();

    jQuery.each(items, function (i, item) {
      jQuery("#sseo_sitemap_exclude_select").append(
        jQuery("<option>", {
          value: item,
          text: item,
        })
      );
      jQuery('#sseo-page-list option[value="' + item + '"]').remove();

      var text = jQuery("#sseo_sitemap_exclude").val();
      var el = text === "" ? [] : JSON.parse(text);

      el.push(item);
      jQuery("#sseo_sitemap_exclude").val(JSON.stringify(el));
    });
  });

  jQuery("#sseo-remove-from-exclude").on("click", function () {
    var items = jQuery("#sseo_sitemap_exclude_select").val();

    jQuery.each(items, function (i, item) {
      jQuery("#sseo-page-list").append(
        jQuery("<option>", {
          value: item,
          text: item,
        })
      );

      var text = jQuery("#sseo_sitemap_exclude").val();
      var el = text === "" ? [] : JSON.parse(text);
      if (el.includes(item)) {
        el.splice(el.indexOf(item), 1);
      }

      jQuery("#sseo_sitemap_exclude").val(JSON.stringify(el));

      jQuery(
        '#sseo_sitemap_exclude_select option[value="' + item + '"]'
      ).remove();
    });
  });
});
