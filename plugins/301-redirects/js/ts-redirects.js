/*
 * 301 Redirects
 * (c) WebFactory Ltd, 2015 - 2022
 */

jQuery(function ($) {
  var rowId = 0;

  if (location.hash) {
    $("a[href='" + location.hash + "']").tab('show');
  } else {
    var activeTab = localStorage.getItem('301-activeTab');
    if (activeTab) {
      $('a[href="' + activeTab + '"]').tab('show');
    }
  }

  $('body').on('click', "a[data-toggle='tab']", function (e) {
    e.preventDefault();
    var tab_name = this.getAttribute('href');
    if (history.pushState) {
      history.pushState(null, null, tab_name);
    } else {
      location.hash = tab_name;
    }
    localStorage.setItem('301-activeTab', tab_name);

    $(this).tab('show');
    return false;
  });
  $(window).on('popstate', function () {
    var anchor = location.hash || $("a[data-toggle='tab']").first().attr('href');
    $("a[href='" + anchor + "']").tab('show');
  });

  jQuery('#addRowBtn').on('click', function (e) {
    e.preventDefault();

    var newRow =
      '<tr id="row' +
      rowId +
      '">' +
      '<td><input type="text" class="form-control" placeholder="Redirect name (for internal use)" name="title[]" /></td>' +
      '<td><input type="text" class="form-control" placeholder="Section (for internal use)" name="section[]" /></td>' +
      '<td><input name="old_link[]" class="pull-left form-control" placeholder="Old URL" /></td>' +
      '<td>' +
      '<input type="text" class="form-control new-link short-field" placeholder="New URL" name="new_link[]" /></td>' +
      '</td>' +
      '<td><a title="Go PRO to access stats" class="open-301-pro-dialog pro-feature" data-pro-feature="redirect-rules-chart-icon-new" href="#"><span class="dashicons dashicons-chart-area"></span></a> <a title="Remove redirect rule" class="remove-row" href="#" data-id="' +
      rowId +
      '"><span class="dashicons dashicons-trash"></span></a></td>' +
      '</tr>';

    jQuery('#addRow').before(newRow);

    rowId++;

    jQuery('.remove-row').on('click', function (e) {
      e.preventDefault();
      var id = jQuery(this).data('id');
      jQuery('#row' + id).fadeOut(function () {
        jQuery(this).remove();
      });
    });
  });

  jQuery('.remove-custom').on('click', function (e) {
    e.preventDefault();

    var confirmDelete = confirm('Are you sure you want to delete this redirect rule? There is NO undo.');

    if (confirmDelete) {
      var id = jQuery(this).data('id');

      jQuery
        .ajax({
          type: 'POST',
          url: ajaxurl,
          data: {
            action: 'redirect_delete_rule',
            _ajax_nonce: redirect.nonce_delete_rule,
            custom_id: id,
          },
        })
        .done(function (html) {
          jQuery('#customRow' + id).fadeOut(function () {
            jQuery(this).remove();
          });
        });
    }
  });

  jQuery('#deleteAllRules').on('click', function (e) {
    e.preventDefault();

    var confirmDelete = confirm('Are you sure you want to delete ALL redirect rules? There is NO undo.');

    if (confirmDelete) {
      jQuery
        .ajax({
          type: 'POST',
          url: ajaxurl,
          data: {
            action: 'redirect_delete_rule',
            _ajax_nonce: redirect.nonce_delete_rule,
            custom_id: 'all',
          },
        })
        .done(function (html) {
          jQuery('.redirect-row').fadeOut(function () {
            jQuery(this).remove();
          });
        });
    }
  });

  $('#pro-tab-link').on('click', function (e) {
    e.preventDefault();
    pro_feature = '301-tab';

    $('#wp301-pro-dialog').dialog('open');

    $('#wp301-pro-table .button-buy').each(function (ind, el) {
      tmp = $(el).data('href-org');
      tmp = tmp.replace('pricing-table', pro_feature);
      $(el).attr('href', tmp);
    });

    return false;
  });

  $('#wpwrap').on('click', '.open-301-pro-dialog', function (e) {
    e.preventDefault();

    $('#wp301-pro-dialog').dialog('open');

    pro_feature = $(this).data('pro-feature');
    if (!pro_feature) {
      pro_feature = 'unknown';
    }
    pro_feature = '301-' + pro_feature;

    $('#wp301-pro-table .button-buy').each(function (ind, el) {
      tmp = $(el).data('href-org');
      tmp = tmp.replace('pricing-table', pro_feature);
      $(el).attr('href', tmp);
    });

    return false;
  });

  $('#wp301-pro-dialog').dialog({
    dialogClass: 'wp-dialog wp301-pro-dialog',
    modal: true,
    resizable: false,
    width: 800,
    height: 'auto',
    show: 'fade',
    hide: 'fade',
    close: function (event, ui) {},
    open: function (event, ui) {
      $(this).siblings().find('span.ui-dialog-title').html('WP 301 Redirects PRO is here!');
      wp301_fix_dialog_close(event, ui);
    },
    autoOpen: false,
    closeOnEscape: true,
  });

  if (redirect.auto_open_pro_dialog) {
    $('#wp301-pro-dialog').dialog('open');
  }

  if (location.hash == '#get-pro') {
    location.hash = '';
    pro_feature = '301-plugins-table';

    $('#wp301-pro-dialog').dialog('open');

    $('#wp301-pro-table .button-buy').each(function (ind, el) {
      tmp = $(el).data('href-org');
      tmp = tmp.replace('pricing-table', pro_feature);
      $(el).attr('href', tmp);
    });
  }
}); // on ready

function wp301_fix_dialog_close(event, ui) {
  jQuery('.ui-widget-overlay').bind('click', function () {
    jQuery('#' + event.target.id).dialog('close');
  });
} // wp301_fix_dialog_close
