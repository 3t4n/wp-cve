"use strict";

(function ($) {
  'use strict';

  /**
   * All of the code for your Dashboard-specific JavaScript source
   * should reside in this file.
   *
   * Note that this assume you're going to use jQuery, so it prepares
   * the $ function reference to be used within the scope of this
   * function.
   *
   * From here, you're able to define handlers for when the DOM is
   * ready:
   *
   * $(function() {
   *
   * });
   *
   * Or when the window is loaded:
   *
   * $( window ).load(function() {
   *
   * });
   *
   * ...and so on.
   *
   * Remember that ideally, we should not attach any more than a single DOM-ready or window-load handler
   * for any particular page. Though other scripts in WordPress core, other plugins, and other themes may
   * be doing this, we should try to minimize doing that in our own work.
   */
  $(document).ready(function () {
    // Content editable column ajax function
    $('body').on('focusout', '.edit-column-content', function (event) {
      event.preventDefault();
      var that = $(this);
      var value = $(this).text();
      var type = $(this).data('content-type');
      var image_id = $(this).data('image-id');
      // console.log(fd);
      var data = {
        action: 'image_metadata',
        type: type,
        image_id: image_id,
        value: value,
        cx_nonce: cdxn_mlh_script.ajx_nonce
      };
      jQuery.ajax({
        type: 'POST',
        url: cdxn_mlh_script.admin_ajax,
        data: data,
        beforeSend: function beforeSend() {
          that.closest('td').addClass('spiner-add');
          that.append('<div class="loder-wrapper"><span class="ajaxloader"></span></div>');
        },
        success: function success(response) {
          that.text(response);
        },
        error: function error() {
          // alert('Error Loading Data...');
          console.log('Error Found');
        },
        complete: function complete() {
          that.closest('td').removeClass('spiner-add');
        }
      });
    });
    $('#posts-filter').on('click', '.table-editable', function (event) {
      event.preventDefault();
      var editable = String($(this).attr('data-editable'));
      var drag = '';
      var link = $('.title-link');
      if ($('.wp-list-table tr').hasClass('ui-draggable')) {
        drag = 'drag-enabled';
      }
      if ('false' === editable) {
        $('.edit-column-content').addClass('add-bg');
        $('.table-editable').attr('data-editable', 'true');
        if (drag === 'drag-enabled') {
          $('.wp-list-table tr').draggable({
            disabled: true
          });
        }
        $('.edit-column-content').attr('contenteditable', 'true');
        $("a.cdxn-title-link").on('click', function (e) {
          var link = $(this);
          if (!link.is(event.target) && link.has(event.target).length === 0) {
            e.preventDefault();
          }
        });
      }
      if ('true' === editable) {
        $('.table-editable').attr('data-editable', 'false');
        $('.edit-column-content').attr('contenteditable', 'false');
        $('.edit-column-content').removeClass('add-bg');
        if (drag === 'drag-enabled') {
          $('.wp-list-table tr').draggable({
            disabled: false
          });
        }
        $("a.cdxn-title-link").off('click');
      }
    });
  });
})(jQuery);
jQuery(function ($) {
  // Bulk edit action 
  $(document).ready(function () {
    var bulk_edit_row = $('tr#bulk-edit.bulk-edit-attachment'),
      wpVersion = cdxn_mlh_script.wordpress_version;
    bulk_edit_row.find('input[name="alt"]').val(cdxn_mlh_script.text_no_change);
    bulk_edit_row.find('input[name="caption"]').val(cdxn_mlh_script.text_no_change);
    bulk_edit_row.find('input[name="description"]').val(cdxn_mlh_script.text_no_change);
    bulk_edit_row.find('input[name="title"]').val(cdxn_mlh_script.text_no_change);
    $('body').on('click', '.bulk-edit-attachment  input[name="bulk_edit"]', function (event) {
      event.preventDefault();
      // let's add the WordPress default spinner just before the button
      $(this).after('<span class="spinner is-active"></span>');
      // define: prices, featured products and the bulk edit table row
      var bulk_edit_row = $('tr#bulk-edit'),
        post_ids = new Array();
      var title = bulk_edit_row.find('input[name="title"]').val();
      var alt = bulk_edit_row.find('input[name="alt"]').val();
      var caption = bulk_edit_row.find('input[name="caption"]').val();
      var description = bulk_edit_row.find('input[name="description"]').val();
      var referer = bulk_edit_row.find('input[name="_wp_http_referer"]').val();
      if ('6 or higher' === wpVersion) {
        bulk_edit_row.find('#bulk-titles-list').children().find('button').each(function () {
          post_ids.push($(this).attr('id').replace(/^(_)/i, ''));
        });
      } else if ('below 6' === wpVersion) {
        bulk_edit_row.find('#bulk-titles').children().each(function () {
          post_ids.push($(this).attr('id').replace(/^(ttle)/i, ''));
        });
      }

      //save the data with AJAX
      $.ajax({
        url: cdxn_mlh_script.admin_ajax,
        // WordPress has already defined the AJAX url for us (at least in admin area)
        type: 'POST',
        data: {
          action: 'cdxn_mlh_attachment_save_bulk',
          // wp_ajax action hook
          post_ids: post_ids.toString(),
          // array of post IDs
          title: title,
          // new title
          alt: alt,
          // new alt
          caption: caption,
          // new caption
          description: description,
          // new description
          text_change: cdxn_mlh_script.text_no_change,
          cx_nonce: cdxn_mlh_script.ajx_nonce
        },
        beforeSend: function beforeSend() {},
        success: function success(response) {
          if (response.status) {
            window.location.href = referer;
          }
        },
        error: function error() {
          alert('Error Loading Data...');
        },
        complete: function complete() {}
      });
    });
  });
  $(document).ready(function () {
    /**
     * Admin code for dismissing notifications.
     *
     */
    $('.cdxn-mlh-notice').on('click', '.notice-dismiss, .cdxn-mlh-notice-action', function () {
      var $this = $(this);
      var admin_ajax = cdxn_mlh_script.admin_ajax;
      var parents = $(this).parents('.cdxn-mlh-notice');
      var dismiss_type = $(this).data('dismiss');
      var notice_type = parents.data('notice');
      if (!dismiss_type) {
        dismiss_type = '';
      }
      var data = {
        action: 'rate_the_plugin',
        dismiss_type: dismiss_type,
        notice_type: notice_type,
        cx_nonce: cdxn_mlh_script.ajx_nonce
      };
      jQuery.ajax({
        type: 'POST',
        url: admin_ajax,
        data: data,
        success: function success(response) {
          if (response) {
            $this.parents('.cdxn-mlh-notice').remove();
          }
        }
      });
    });
  });

  // hiding 'Edit Mode Locked/Unlocked' button on bulk edit mode
  $(document).ready(function () {
    $('.media-bulk-action').on('click', '.action', function (event) {
      var selectedCountry = $(this).parent().find('select').children("option:selected").val();
      selectedCountry = selectedCountry.toString();
      if ('edit' === selectedCountry) {
        var buld_edit_found = $('#the-list').find('#bulk-edit');
        if (buld_edit_found.length > 0) {
          $('.button.table-editable').addClass('lock-hide');
        } else {
          $('.button.table-editable').removeClass('lock-hide');
        }
      }
    });
  });
});