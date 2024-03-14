'use strict';

(function($) {
  var woosg_timeout = null;

  $(function() {
    // ready
    // options page
    woosg_active_options();

    woosg_active_settings();

    // total price
    if ($('#product-type').val() == 'woosg') {
      woosg_change_price();
    }

    // arrange
    woosg_arrange();
  });

  $(document).on('click touch', '#woosg_search_settings_btn', function(e) {
    // open search settings popup
    e.preventDefault();

    var title = $('#woosg_search_settings').attr('data-title');

    $('#woosg_search_settings').
        dialog({
          minWidth: 540,
          title: title,
          modal: true,
          dialogClass: 'wpc-dialog',
          open: function() {
            $('.ui-widget-overlay').bind('click', function() {
              $('#woosg_search_settings').dialog('close');
            });
          },
        });
  });

  $(document).on('click touch', '#woosg_search_settings_update', function(e) {
    // save search settings
    e.preventDefault();

    $('#woosg_search_settings').addClass('woosg_search_settings_updating');

    var data = {
      action: 'woosg_update_search_settings',
      nonce: woosg_vars.nonce,
      limit: $('.woosg_search_limit').val(),
      sku: $('.woosg_search_sku').val(),
      id: $('.woosg_search_id').val(),
      exact: $('.woosg_search_exact').val(),
      sentence: $('.woosg_search_sentence').val(),
      same: $('.woosg_search_same').val(),
      types: $('.woosg_search_types').val(),
    };

    $.post(ajaxurl, data, function(response) {
      $('#woosg_search_settings').removeClass('woosg_search_settings_updating');
    });
  });

  $(document).on('change', '#product-type', function() {
    woosg_active_settings();
  });

  $(document).on('change', '.woosg_change_price', function() {
    woosg_active_options();
  });

  // add text
  $(document).on('click touch', '.woosg_add_text', function(e) {
    e.preventDefault();

    var data = {
      action: 'woosg_add_text', nonce: woosg_vars.nonce,
    };

    $.post(ajaxurl, data, function(response) {
      $('#woosg_selected ul').append(response);
    });
  });

  // search input
  $(document).on('keyup', '#woosg_keyword', function() {
    if ($('#woosg_keyword').val() != '') {
      $('#woosg_loading').show();
      if (woosg_timeout != null) {
        clearTimeout(woosg_timeout);
      }
      woosg_timeout = setTimeout(woosg_ajax_get_data, 300);
      return false;
    }
  });

  // actions on search result items
  $(document).on('click touch', '#woosg_results li', function() {
    $(this).children('.woosg-remove').attr('aria-label', 'Remove').html('×');
    $('#woosg_selected ul').append($(this));
    $('#woosg_results').html('').hide();
    $('#woosg_keyword').val('');
    woosg_change_price();
    woosg_arrange();
    return false;
  });

  // change qty of each item
  $(document).on('keyup change', '#woosg_selected .qty input', function() {
    woosg_change_price();
    return false;
  });

  // actions on selected items
  $(document).on('click touch', '#woosg_selected .woosg-remove', function() {
    $(this).parent().remove();
    woosg_change_price();
    return false;
  });

  // hide search result box if click outside
  $(document).on('click touch', function(e) {
    if ($(e.target).closest($('#woosg_results')).length == 0) {
      $('#woosg_results').html('').hide();
    }
  });

  function woosg_arrange() {
    $('#woosg_selected ul').sortable({
      handle: '.move',
    });
  }

  function woosg_active_options() {
    if ($('.woosg_change_price').val() == 'yes_custom') {
      $('.woosg_change_price_custom').show();
    } else {
      $('.woosg_change_price_custom').hide();
    }
  }

  function woosg_active_settings() {
    if ($('#product-type').val() == 'woosg') {
      $('li.general_tab').addClass('show_if_woosg');
      $('#general_product_data .pricing').addClass('show_if_woosg');

      $('.show_if_external').hide();
      $('.show_if_simple').show();
      $('.show_if_woosg').show();

      $('.product_data_tabs li').removeClass('active');
      $('.product_data_tabs li.woosg_tab').addClass('active');

      $('.panel-wrap .panel').hide();
      $('#woosg_settings').show();

      if ($('#woosg_optional_products').is(':checked')) {
        $('.woosg_tr_show_if_optional_products').show();
      } else {
        $('.woosg_tr_show_if_optional_products').hide();
      }

      if ($('#woosg_disable_auto_price').is(':checked')) {
        $('.woosg_tr_show_if_auto_price').hide();
      } else {
        $('.woosg_tr_show_if_auto_price').show();
      }
    } else {
      $('li.general_tab').removeClass('show_if_woosg');
      $('#general_product_data .pricing').removeClass('show_if_woosg');

      $('#_regular_price').prop('readonly', false);
      $('#_sale_price').prop('readonly', false);

      if ($('#product-type').val() != 'grouped') {
        $('.general_tab').show();
      }

      if ($('#product-type').val() == 'simple') {
        $('#_downloadable').closest('label').show();
        $('#_virtual').closest('label').show();
      }
    }
  }

  function woosg_round(value, decimals) {
    return Number(Math.round(value + 'e' + decimals) + 'e-' + decimals);
  }

  function woosg_format_money(number, places, symbol, thousand, decimal) {
    number = number || 0;
    places = !isNaN(places = Math.abs(places)) ? places : 2;
    symbol = symbol !== undefined ? symbol : '$';
    thousand = thousand || ',';
    decimal = decimal || '.';
    var negative = number < 0 ? '-' : '',
        i = parseInt(number = woosg_round(Math.abs(+number || 0), places).
            toFixed(places), 10) + '', j = 0;
    if (i.length > 3) {
      j = i.length % 3;
    }
    return symbol + negative + (j ? i.substr(0, j) + thousand : '') +
        i.substr(j).replace(/(\d{3})(?=\d)/g, '$1' + thousand) +
        (places ? decimal + woosg_round(Math.abs(number - i), places).
            toFixed(places).
            slice(2) : '');
  }

  function woosg_change_price() {
    var total = 0;

    $('#woosg_selected li.woosg-li-product').each(function() {
      total += parseFloat($(this).data('price')) *
          parseFloat($(this).find('.qty input').val());
    });

    total = woosg_format_money(total, woosg_vars.price_decimals, '',
        woosg_vars.price_thousand_separator,
        woosg_vars.price_decimal_separator);

    $('#woosg_regular_price').html(total);
    //$('#_regular_price').val(total).trigger('change');
  }

  function woosg_ajax_get_data() {
    // ajax search product
    woosg_timeout = null;

    var ids = [];

    $('#woosg_selected').find('.woosg-li-product').each(function() {
      ids.push($(this).attr('data-id'));
    });

    var data = {
      action: 'woosg_get_search_results',
      nonce: woosg_vars.nonce,
      keyword: $('#woosg_keyword').val(),
      ids: ids.join(),
    };

    jQuery.post(ajaxurl, data, function(response) {
      $('#woosg_results').show();
      $('#woosg_results').html(response);
      $('#woosg_loading').hide();
    });
  }
})(jQuery);