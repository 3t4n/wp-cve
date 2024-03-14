'use strict';

(function($) {
  var woofs_timeout = null;

  $(function() {
    woofs_options();
    woofs_arrange();
    woofs_count();
  });

  $(document).on('change', '.woofs_change_price', function() {
    woofs_options();
  });

  // add text
  $(document).on('click touch', '.woofs_add_text', function(e) {
    e.preventDefault();

    var data = {
      action: 'woofs_add_text',
    };

    $.post(ajaxurl, data, function(response) {
      $('#woofs_selected ul').append(response);
    });
  });

  // search input
  $(document).on('keyup', '#woofs_keyword', function() {
    if ($('#woofs_keyword').val() != '') {
      $('#woofs_loading').show();

      if (woofs_timeout != null) {
        clearTimeout(woofs_timeout);
      }

      woofs_timeout = setTimeout(woofs_ajax_get_data, 300);
      return false;
    }
  });

  // actions on search result items
  $(document).on('click touch', '#woofs_results li', function() {
    $(this).children('.woofs-remove').html('×');
    $('#woofs_selected ul').append($(this));
    $('#woofs_results').html('').hide();
    $('#woofs_keyword').val('');
    woofs_arrange();
    woofs_count();
    return false;
  });

  $(document).on('click touch', '#woofs_search_settings_btn', function(e) {
    // open search settings popup
    e.preventDefault();

    var title = $('#woofs_search_settings').attr('data-title');

    $('#woofs_search_settings').
        dialog({
          minWidth: 540,
          title: title,
          modal: true,
          dialogClass: 'wpc-dialog',
          open: function() {
            $('.ui-widget-overlay').bind('click', function() {
              $('#woofs_search_settings').dialog('close');
            });
          },
        });
  });

  $(document).on('click touch', '#woofs_search_settings_update', function(e) {
    // save search settings
    e.preventDefault();

    $('#woofs_search_settings').addClass('woofs_search_settings_updating');

    var data = {
      action: 'woofs_update_search_settings',
      nonce: woofs_vars.nonce,
      limit: $('.woofs_search_limit').val(),
      sku: $('.woofs_search_sku').val(),
      id: $('.woofs_search_id').val(),
      exact: $('.woofs_search_exact').val(),
      sentence: $('.woofs_search_sentence').val(),
      same: $('.woofs_search_same').val(),
      types: $('.woofs_search_types').val(),
    };

    $.post(ajaxurl, data, function(response) {
      $('#woofs_search_settings').removeClass('woofs_search_settings_updating');
    });
  });

  // actions on selected items
  $(document).on('click touch', '#woofs_selected .woofs-remove', function() {
    $(this).parent().remove();
    woofs_count();
    return false;
  });

  // hide search result box if click outside
  $(document).on('click touch', function(e) {
    if ($(e.target).closest($('#woofs_results')).length == 0) {
      $('#woofs_results').html('').hide();
    }
  });

  function woofs_options() {
    if ($('.woofs_change_price').val() == 'yes_custom') {
      $('.woofs_change_price_custom').show();
    } else {
      $('.woofs_change_price_custom').hide();
    }
  }

  function woofs_arrange() {
    $('#woofs_selected ul').sortable({
      handle: '.woofs-move',
    });
  }

  function woofs_count() {
    if ($('li.woofs_options').length && $('#woofs_selected').length) {
      var count = $('#woofs_selected .woofs-li-product').length;

      if ($('li.woofs_options a span.count').length) {
        $('li.woofs_options a span.count').html('(' + count + ')');
      } else {
        $('<span class="count">(' + count + ')</span>').
            appendTo($('li.woofs_options a'));
      }
    }
  }

  function woofs_ajax_get_data() {
    // ajax search product
    woofs_timeout = null;

    var ids = [];

    $('#woofs_selected').find('.woofs-li-product').each(function() {
      ids.push($(this).attr('data-id'));
    });

    var data = {
      action: 'woofs_get_search_results',
      woofs_keyword: $('#woofs_keyword').val(),
      woofs_id: $('#post_ID').val(),
      woofs_ids: ids.join(),
    };

    $.post(ajaxurl, data, function(response) {
      $('#woofs_results').show();
      $('#woofs_results').html(response);
      $('#woofs_loading').hide();
    });
  }
})(jQuery);