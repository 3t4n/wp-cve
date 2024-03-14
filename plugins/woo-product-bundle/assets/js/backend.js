'use strict';

(function($) {
  var woosb_timeout = null;

  $(function() {
    // options page
    woosb_active_options();

    // product page
    woosb_active_settings();

    // total price
    if ($('#product-type').val() == 'woosb') {
      woosb_change_price();
    }

    // arrange
    woosb_arrange();
  });

  $(document).on('click touch', '#woosb_search_settings_btn', function(e) {
    // open search settings popup
    e.preventDefault();

    var title = $('#woosb_search_settings').attr('data-title');

    $('#woosb_search_settings').dialog({
      minWidth: 540,
      title: title,
      modal: true,
      dialogClass: 'wpc-dialog',
      open: function() {
        $('.ui-widget-overlay').bind('click', function() {
          $('#woosb_search_settings').dialog('close');
        });
      },
    });
  });

  $(document).
      on('click touch', '.woosb_selected .woosb-li-product span.settings',
          function(e) {
            var $this = $(this);
            var $this_product = $this.closest('.woosb-li-product');
            var key = $this_product.data('key');
            var name = $this_product.data('name');

            if (!$('#woosb_item_settings_' + key).length) {
              $('body').
                  append('<div id="woosb_item_settings_' + key + '"></div>');
            }

            $('.woosb_item_settings_' + key + ' select').selectWoo();

            $('.woosb_item_settings_' + key).
                appendTo('#woosb_item_settings_' + key);

            $('#woosb_item_settings_' + key).dialog({
              minWidth: 540,
              title: 'Config terms for "' + name + '"',
              modal: true,
              dialogClass: 'wpc-dialog',
              open: function() {
                $('.ui-widget-overlay').bind('click', function() {
                  $('#woosb_item_settings_' + key).dialog('close');
                });

                $('.woosb_item_settings_' + key).
                    find('.woosb_item_settings_save_changes button').
                    bind('click', function() {
                      $('#woosb_item_settings_' + key).dialog('close');
                    });
              },
              close: function() {
                $('.woosb_item_settings_' + key + ' select').
                    selectWoo('destroy');

                $('.woosb_item_settings_' + key).
                    appendTo(
                        $('.woosb_selected .woosb-li-product[data-key="' + key +
                            '"]'));
              },
            });
          });

  $(document).on('click touch', '#woosb_search_settings_update', function(e) {
    // save search settings
    e.preventDefault();

    $('#woosb_search_settings').addClass('woosb_search_settings_updating');

    var data = {
      action: 'woosb_update_search_settings',
      nonce: woosb_vars.nonce,
      limit: $('.woosb_search_limit').val(),
      sku: $('.woosb_search_sku').val(),
      id: $('.woosb_search_id').val(),
      exact: $('.woosb_search_exact').val(),
      sentence: $('.woosb_search_sentence').val(),
      same: $('.woosb_search_same').val(),
      show_image: $('.woosb_search_show_image').val(),
      types: $('.woosb_search_types').val(),
    };

    $.post(ajaxurl, data, function(response) {
      $('#woosb_search_settings').removeClass('woosb_search_settings_updating');
    });
  });

  $(document).
      on('change',
          '.woosb_change_price, .woosb_price_format, .woosb_variations_selector',
          function() {
            woosb_active_options();
          });

  $(document).on('change', '#product-type', function() {
    woosb_active_settings();
  });

  $(document).
      on('change', '#woosb_discount, #woosb_discount_amount', function() {
        woosb_change_price();
      });

  // set regular price
  $(document).on('click touch', '#woosb_set_regular_price', function() {
    if ($('#woosb_disable_auto_price').is(':checked')) {
      $('li.general_tab a').trigger('click');
      $('#_regular_price').focus();
    } else {
      alert('You must disable auto calculate price first!');
    }
  });

  // set optional
  $(document).on('click touch', '#woosb_optional_products', function() {
    if ($(this).is(':checked')) {
      $('.woosb_tr_show_if_optional_products').show();
    } else {
      $('.woosb_tr_show_if_optional_products').hide();
    }
  });

  // set total limits
  $(document).on('click touch', '#woosb_total_limits', function() {
    if ($(this).is(':checked')) {
      $('.woosb_show_if_total_limits').show();
    } else {
      $('.woosb_show_if_total_limits').hide();
    }
  });

  // checkbox
  $(document).on('change', '#woosb_disable_auto_price', function() {
    if ($(this).is(':checked')) {
      $('#_regular_price').prop('readonly', false);
      $('#_sale_price').prop('readonly', false);
      $('.woosb_tr_show_if_auto_price').hide();
    } else {
      $('#_regular_price').prop('readonly', true);
      $('#_sale_price').prop('readonly', true);
      $('.woosb_tr_show_if_auto_price').show();
    }

    if ($('#product-type').val() == 'woosb') {
      woosb_change_price();
    }
  });

  // add text
  $(document).on('click touch', '.woosb_add_text', function(e) {
    e.preventDefault();

    var data = {
      action: 'woosb_add_text',
      nonce: woosb_vars.nonce,
    };

    $.post(ajaxurl, data, function(response) {
      $('#woosb_selected ul').append(response);
    });
  });

  // search input
  $(document).on('keyup', '#woosb_keyword', function() {
    if ($('#woosb_keyword').val() != '') {
      $('#woosb_loading').show();

      if (woosb_timeout != null) {
        clearTimeout(woosb_timeout);
      }

      woosb_timeout = setTimeout(woosb_ajax_get_data, 300);
      return false;
    }
  });

  // actions on search result items
  $(document).on('click touch', '#woosb_results li', function() {
    $(this).children('.woosb-remove').attr('aria-label', 'Remove').html('×');
    $('#woosb_selected ul').append($(this));
    $('#woosb_results').html('').hide();
    $('#woosb_keyword').val('');
    woosb_change_price();
    woosb_arrange();
    return false;
  });

  // change qty of each item
  $(document).on('keyup change', '#woosb_selected .qty input', function() {
    woosb_change_price();
    return false;
  });

  // actions on selected items
  $(document).on('click touch', '#woosb_selected .woosb-remove', function() {
    $(this).parent().remove();
    woosb_change_price();
    return false;
  });

  // hide search result box if click outside
  $(document).on('click touch', function(e) {
    if ($(e.target).closest($('#woosb_results')).length == 0) {
      $('#woosb_results').html('').hide();
    }
  });

  function woosb_arrange() {
    $('#woosb_selected ul').sortable({
      handle: '.move',
    });
  }

  function woosb_active_options() {
    if ($('.woosb_price_format').val() === 'custom') {
      $('.woosb_tr_show_if_price_format_custom').show();
    } else {
      $('.woosb_tr_show_if_price_format_custom').hide();
    }

    if ($('.woosb_change_price').val() === 'yes_custom') {
      $('.woosb_change_price_custom').show();
    } else {
      $('.woosb_change_price_custom').hide();
    }

    if ($('.woosb_variations_selector').val() === 'woovr') {
      $('.woosb_show_if_woovr').show();
    } else {
      $('.woosb_show_if_woovr').hide();
    }
  }

  function woosb_active_settings() {
    if ($('#product-type').val() == 'woosb') {
      $('li.general_tab').addClass('show_if_woosb');
      $('#general_product_data .pricing').addClass('show_if_woosb');
      $('._tax_status_field').
          closest('.options_group').
          addClass('show_if_woosb');
      $('#_downloadable').
          closest('label').
          addClass('show_if_woosb').
          removeClass('show_if_simple');
      $('#_virtual').
          closest('label').
          addClass('show_if_woosb').
          removeClass('show_if_simple');

      $('.show_if_external').hide();
      $('.show_if_simple').show();
      $('.show_if_woosb').show();

      $('.product_data_tabs li').removeClass('active');
      $('.product_data_tabs li.woosb_tab').addClass('active');

      $('.panel-wrap .panel').hide();
      $('#woosb_settings').show();

      if ($('#woosb_optional_products').is(':checked')) {
        $('.woosb_tr_show_if_optional_products').show();
      } else {
        $('.woosb_tr_show_if_optional_products').hide();
      }

      if ($('#woosb_total_limits').is(':checked')) {
        $('.woosb_show_if_total_limits').show();
      } else {
        $('.woosb_show_if_total_limits').hide();
      }

      if ($('#woosb_disable_auto_price').is(':checked')) {
        $('.woosb_tr_show_if_auto_price').hide();
      } else {
        $('.woosb_tr_show_if_auto_price').show();
      }

      woosb_change_price();
    } else {
      $('li.general_tab').removeClass('show_if_woosb');
      $('#general_product_data .pricing').removeClass('show_if_woosb');
      $('._tax_status_field').
          closest('.options_group').
          removeClass('show_if_woosb');
      $('#_downloadable').
          closest('label').
          removeClass('show_if_woosb').
          addClass('show_if_simple');
      $('#_virtual').
          closest('label').
          removeClass('show_if_woosb').
          addClass('show_if_simple');

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

  function woosb_round(value, decimals) {
    return Number(Math.round(value + 'e' + decimals) + 'e-' + decimals);
  }

  function woosb_format_money(number, places, symbol, thousand, decimal) {
    number = number || 0;
    places = !isNaN((places = Math.abs(places))) ? places : 2;
    symbol = symbol !== undefined ? symbol : '$';
    thousand = thousand || ',';
    decimal = decimal || '.';

    var negative = number < 0 ? '-' : '', i = parseInt(
        (number = woosb_round(Math.abs(+number || 0), places).toFixed(places)),
        10) + '', j = 0;

    if (i.length > 3) {
      j = i.length % 3;
    }

    return (symbol + negative + (j ? i.substr(0, j) + thousand : '') +
        i.substr(j).replace(/(\d{3})(?=\d)/g, '$1' + thousand) + (places ?
            decimal +
            woosb_round(Math.abs(number - i), places).toFixed(places).slice(2) :
            ''));
  }

  function woosb_change_price() {
    if ($('#product-type').val() == 'woosb') {
      var total = 0;
      var total_max = 0;
      var sale = 0;

      $('#woosb_selected li.woosb-li-product').each(function() {
        total += $(this).data('price') * $(this).find('.qty input').val();
        total_max += $(this).data('price-max') *
            $(this).find('.qty input').val();
      });

      if (total == total_max) {
        $('#woosb_regular_price').
            html(woosb_format_money(total, woosb_vars.price_decimals, '',
                woosb_vars.price_thousand_separator,
                woosb_vars.price_decimal_separator));
      } else {
        $('#woosb_regular_price').
            html(woosb_format_money(total, woosb_vars.price_decimals, '',
                    woosb_vars.price_thousand_separator,
                    woosb_vars.price_decimal_separator) + ' - ' +
                woosb_format_money(total_max, woosb_vars.price_decimals, '',
                    woosb_vars.price_thousand_separator,
                    woosb_vars.price_decimal_separator));
      }

      if (!$('#woosb_disable_auto_price').is(':checked')) {
        if ($('#woosb_discount_amount').val()) {
          sale = parseFloat(total) -
              parseFloat($('#woosb_discount_amount').val());
        } else if ($('#woosb_discount').val()) {
          sale = (parseFloat(total) *
              (100 - parseFloat($('#woosb_discount').val()))) / 100;
        }

        $('#_regular_price').
            prop('readonly', true).
            val(woosb_format_money(total, woosb_vars.price_decimals, '',
                woosb_vars.price_thousand_separator,
                woosb_vars.price_decimal_separator)).
            trigger('change');

        if (sale > 0) {
          $('#_sale_price').
              prop('readonly', true).
              val(woosb_format_money(sale, woosb_vars.price_decimals, '',
                  woosb_vars.price_thousand_separator,
                  woosb_vars.price_decimal_separator)).
              trigger('change');
        } else {
          $('#_sale_price').prop('readonly', true).val('').trigger('change');
        }
      }
    }
  }

  function woosb_ajax_get_data() {
    // ajax search product
    woosb_timeout = null;

    var ids = [];

    $('#woosb_selected').find('.woosb-li-product').each(function() {
      ids.push($(this).attr('data-id'));
    });

    var data = {
      action: 'woosb_get_search_results',
      nonce: woosb_vars.nonce,
      keyword: $('#woosb_keyword').val(),
      ids: ids.join(),
    };

    $.post(ajaxurl, data, function(response) {
      $('#woosb_results').show();
      $('#woosb_results').html(response);
      $('#woosb_loading').hide();
    });
  }
})(jQuery);
