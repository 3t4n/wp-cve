"use strict";

(function ($) {
  const SBM_TABLE = {
    /**
     * INIT
     */
    init: function () {
      var timer,
          delay = 500,
          searchLinksTextField = $("#seo-blm-links-list-search-input"),
          searchLinksColumn = $("#seo-blm-search-column");
      /**
       * Sorting
       */

      $("#seo-backlink-monitor-links").on("click", ".seo-blm-tbl-nav-pages a, .seo-blm-sort-column.sortable a, .seo-blm-sort-column.sorted a", function (e) {
        e.preventDefault();
        var query = this.search.substring(1);
        var data = {
          paged: SBM_TABLE.__query(query, "paged") || "1",
          order: SBM_TABLE.__query(query, "order") || "asc",
          orderby: SBM_TABLE.__query(query, "orderby") || "date",
          search_field: searchLinksTextField.val() || "",
          search_column: searchLinksColumn.val() || ""
        };
        SBM_TABLE.update(data);
      });
      /**
       * Paging
       */

      $("input[name=paged]").on("keyup", function (e) {
        if ('Enter' === e.key || 13 === e.which) {
          e.preventDefault();
        }

        var data = {
          paged: parseInt($("input[name=paged]").val()) || "1",
          order: $("input[name=order]").val() || "asc",
          orderby: $("input[name=orderby]").val() || "date",
          search_field: searchLinksTextField.val() || "",
          search_column: searchLinksColumn.val() || ""
        };
        window.clearTimeout(timer);
        timer = window.setTimeout(function () {
          SBM_TABLE.update(data);
        }, delay);
      });
      /**
       * Search Field
       */

      $("#seo-blm-links-list-search-input").on("keyup", function (e) {
        if ("Enter" === e.key || 13 === e.which) {
          $("#seo-blm-links-search-btn").trigger("click");
        }
      });
      /**
       * Search Button
       */

      $("#seo-blm-links-search-btn").click(function (e) {
        e.preventDefault();
        e.stopImmediatePropagation();

        if (searchLinksTextField.val() == "") {
          searchLinksTextField.addClass("seo-blm-field-required");
          return false;
        }

        if (searchLinksColumn.val() == "") {
          searchLinksColumn.addClass("seo-blm-field-required");
          searchLinksTextField.removeClass("seo-blm-field-required");
          return false;
        }

        if (searchLinksTextField.val() !== "" && searchLinksColumn.val() !== "") {
          searchLinksTextField.removeClass("seo-blm-field-required");
          searchLinksColumn.removeClass("seo-blm-field-required");
        }

        var data = {
          paged: parseInt($("input[name=paged]").val()) || "1",
          order: $("input[name=order]").val() || "asc",
          orderby: $("input[name=orderby]").val() || "date",
          search_field: searchLinksTextField.val(),
          search_column: searchLinksColumn.val()
        };
        SBM_TABLE.update(data);
      });
      /**
       * Reset Button blm-links-reset-btn
       */

      $("#seo-blm-links-reset-btn").click(function (e) {
        e.preventDefault();
        e.stopImmediatePropagation();
        $("#seo-blm-search-column").prop("selectedIndex", 0).trigger("change");
        searchLinksTextField.val("");
        var data = {
          paged: parseInt($("input[name=paged]").val()) || "1",
          order: $("input[name=order]").val() || "asc",
          orderby: $("input[name=orderby]").val() || "date",
          search_field: "",
          search_column: ""
        };
        SBM_TABLE.update(data);
      });
      /**
       * Delete Links
       */

      $("#seo-backlink-monitor-links").on("click", ".seo-blm-delete-link", function (e) {
        e.preventDefault();
        var link_id = $(this).attr("data-id"),
            currentRow = $("#tr-" + link_id),
            data = {
          paged: parseInt($("input[name=paged]").val()) || "1",
          order: $("input[name=order]").val() || "asc",
          orderby: $("input[name=orderby]").val() || "date",
          search_field: searchLinksTextField.val() || "",
          search_column: searchLinksColumn.val() || "",
          deletion_id: link_id || ""
        };
        $.confirm({
          title: SEO_BLM_Localize.confirm_remove_title,
          content: SEO_BLM_Localize.confirm_remove_message,
          type: "red",
          container: "body",
          closeIcon: true,
          buttons: {
            removeBtn: {
              text: SEO_BLM_Localize.confirm_remove_button,
              btnClass: "btn-red",
              action: function () {
                currentRow.css({
                  backgroundColor: "red",
                  "font-weight": "bold"
                });
                currentRow.slideUp(500, function () {
                  currentRow.remove();
                });
                $("#seo-blm-search-column option[value='']").attr("selected", true);
                searchLinksTextField.val("");
                SBM_TABLE.update(data);
              }
            },
            close: function () {}
          }
        });
      });
      /**
       * Refresh Link
       */

      $("#seo-backlink-monitor-links").on("click", ".seo-blm-refresh-link", function (e) {
        e.preventDefault();
        var $this = $(this),
            $spinner = $this.find(".dashicons"),
            link_id = $this.attr("data-id"),
            $currentRow = $("#tr-" + link_id),
            data = {
          refresh_id: link_id
        };
        SBM_TABLE.refresh(data, $spinner, $currentRow);
      });
    },

    /**
     * UPDATE
     */
    update: function (data) {
      $.ajax({
        url: SEO_BLM_Localize.ajax_url,
        data: $.extend({
          seo_blm_ajax_custom_list_nonce: SEO_BLM_Localize.custom_list_ajax_nonce,
          action: "seo_blm_list_table_ajax"
        }, data),
        beforeSend: function () {
          $(".top .bulkactions").append('<div class="page-content" id="loader">' + SEO_BLM_Localize.content + '<img src="' + SEO_BLM_Localize.imgsrc + '"/></div>');
          $("#seo-blm-links-search-btn").prop("disabled", true);
        },
        success: function (successResponse) {
          var response = $.parseJSON(successResponse);
          $(".top .bulkactions #loader").remove();

          if (response.rows.length) {
            $("#seo-backlink-monitor-links").find("#the-list").html(response.rows);
            toggleNoteMoveToRightPosition();
          }

          if (response.column_headers.length) {
            $("#seo-backlink-monitor-links").find("thead tr, tfoot tr").each(function () {
              $(this).html(response.column_headers);
            });
          }

          if (response.pagination.bottom.length) {
            $("#seo-backlink-monitor-links").find(".tablenav.top .tablenav-pages").html($(response.pagination.top).html());
          }

          if (response.pagination.top.length) {
            $("#seo-backlink-monitor-links").find(".tablenav.bottom .tablenav-pages").html($(response.pagination.bottom).html());
          }

          $("#seo-blm-links-search-btn").prop("disabled", false);
        }
      });
    },

    /**
     * REFRESH
     */
    refresh: function (data, $spinner, $row) {
      $.ajax({
        url: SEO_BLM_Localize.ajax_url,
        data: $.extend({
          seo_blm_ajax_refresh_link_nonce: SEO_BLM_Localize.refresh_ajax_nonce,
          action: "seo_blm_refresh_link_ajax"
        }, data),
        beforeSend: function () {
          $spinner.addClass("spin");
        },
        success: function (successResponse) {
          var response = successResponse; //$.parseJSON(successResponse);

          $spinner.removeClass("spin").toggleClass("dashicons-update dashicons-thumbs-up");
          setTimeout(function () {
            $spinner.toggleClass("dashicons-thumbs-up dashicons-update");
          }, 2000);
          $row.find("> .dateRefresh .date").html(response.dateRefresh);
          $row.find("> .anchorText").html(response.anchorText);
          $row.find("> .follow").html(response.follow);
          $row.find("> .status").html(response.status);
        },
        error: function (errorResponse) {
          var response = errorResponse;
          $spinner.removeClass("spin").toggleClass("dashicons-update dashicons-thumbs-down");
          setTimeout(function () {
            $spinner.toggleClass("dashicons-thumbs-down dashicons-update");
          }, 2000);
          console.log("ERROR", response);
        }
      });
    },

    /**
     * HELPER: __query
     */
    __query: function (query, variable) {
      var vars = query.split("&");

      for (var i = 0; i < vars.length; i += 1) {
        var pair = vars[i].split("=");

        if (pair[0] == variable) {
          return pair[1];
        }
      }

      return false;
    }
  };
  /**
   * INIT()
   */

  SBM_TABLE.init();
  /**
   * Toggle Cards
   */

  $(document).on("click", "[data-toggle]", function (e) {
    e.preventDefault();
    var $this = $(this),
        $target = $($this.attr("data-toggle"));
    $("[data-toggle]").not($this).each(function () {
      var $t = $(this),
          $targ = $($t.attr("data-toggle"));
      if (!$targ.length) return;
      $targ.slideUp();
    });
    if (!$target.length) return;

    if ($target.is(":visible")) {
      $target.slideUp();
    } else {
      $target.slideDown();
    }
  });
  /**
   * Toggle Single Card
   */

  $(document).on("click", "[data-toggle-single]", function (e) {
    e.preventDefault();
    var $this = $(this),
        $target = $($this.attr("data-toggle-single"));
    if (!$target.length) return;

    if ($target.is(":visible")) {
      $target.slideUp();
    } else {
      $target.slideDown();
    }
  });
  /**
   * Move Note to right position
   */

  function toggleNoteMoveToRightPosition() {
    $(".toggle-note").each(function () {
      var $this = $(this),
          $parent = $this.parent();
      $this.prependTo($parent);
    });
  }

  toggleNoteMoveToRightPosition();
  /**
   * Toggle Extended handling
   */

  $(document).on("click", ".toggle-extended", function (e) {
    e.preventDefault();
    var $this = $(this);

    if (!$this.is("[data-toggle-single]")) {
      $this = $this.parents("[data-toggle-single]");
    }

    var $icon = $this.find(".dashicons"),
        $toggles = $('[data-toggle-single="' + $this.attr("data-toggle-single") + '"]');

    if ($icon.is(".dashicons-arrow-right")) {
      $toggles.find(".dashicons").removeClass("dashicons-arrow-right").addClass("dashicons-arrow-down");
    } else {
      $toggles.find(".dashicons").removeClass("dashicons-arrow-down").addClass("dashicons-arrow-right");
    }
  });
  /**
   * Search Help
   */

  $(".search-help").insertAfter(".tablenav.top");
  $(document).on("change", "#seo-blm-search-column", function () {
    var $this = $(this),
        $target = $("#search-help-" + $this.val());
    $(".search-help").slideUp();
    if (!$target.length) return;
    $target.slideDown();
  }).trigger("change");
  $(".search-help a").on("click", function (e) {
    e.preventDefault();
    var $this = $(this),
        val = $this.attr("data-search-val");
    $("#seo-blm-links-list-search-input").val(val);
    $("#seo-blm-links-search-btn").trigger("click");
  });
})(jQuery);