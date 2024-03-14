'use strict';

(function($) {
  var wpcsc_table = $('#wpcsc-table');
  var wpcsc_rows = wpcsc_table.find('tr').length - 1;
  var wpcsc_cols = wpcsc_table.find('th').length - 1;
  var wpcsc_input = $('#wpcsc-table-val');

  $(function() {
    wpcsc_select2();
    wpcsc_active();
    wpcsc_active_type();
    wpcsc_terms_init();
    wpcsc_combined_terms_init();
  });

  function wpcsc_get_table_data() {
    var wpcsc_data = [];

    wpcsc_table.find('tbody tr').each(function() {
      var wpcsc_cols = [];
      var wpcsc_all_td = $(this).find('td');

      wpcsc_all_td.each(function() {
        if (!$(this).is('.wpcsc-btns')) {
          var wpcsc_input_value = $(this).find('input').val();

          if (wpcsc_input_value != null && wpcsc_input_value != '') {
            wpcsc_cols.push(wpcsc_input_value.trim());
          } else {
            wpcsc_cols.push('&nbsp;');
          }
        }
      });

      if (wpcsc_cols.length != 0) {
        wpcsc_data.push(wpcsc_cols);
      }
    });

    wpcsc_input.val(JSON.stringify(wpcsc_data));
  }

  function wpcsc_add_row() {
    var wpcsc_row = '<tr>';

    wpcsc_row += '<td class="wpcsc-btns">';
    wpcsc_row += '<input type="button" class="wpcsc-add-row wpcsc-add button-primary" value="+">';
    wpcsc_row += '<input type="button" class="wpcsc-del-row wpcsc-del button" value="-">';
    wpcsc_row += '</td>';

    for (var i = 0; i < wpcsc_cols; i++) {
      wpcsc_row += '<td><input class="wpcsc-input-table" type="text"></td>';
    }

    wpcsc_row += '</tr>';

    return wpcsc_row;
  }

  function wpcsc_add_col(wpcsc_col_id) {
    var wpcsc_col_btns = '<th class="wpcsc-btns">';

    wpcsc_col_btns += '<input type="button" class="wpcsc-add-col wpcsc-add button-primary" value="+">';
    wpcsc_col_btns += '<input type="button" class="wpcsc-del-col wpcsc-del button" value="-">';
    wpcsc_col_btns += '</th>';

    var wpcsc_col = '<td><input class="wpcsc-input-table" type="text"></td>';

    wpcsc_table.find('thead tr').
        find('th:eq(' + wpcsc_col_id + ')').
        after(wpcsc_col_btns);

    wpcsc_table.find('tbody tr').each(function() {
      $(this).find('td:eq(' + wpcsc_col_id + ')').after(wpcsc_col);
    });
  }

  function wpcsc_remove_col(wpcsc_col_id) {
    wpcsc_table.find('thead tr').find('th:eq(' + wpcsc_col_id + ')').remove();
    wpcsc_table.find('tbody tr').each(function() {
      $(this).find('td:eq(' + wpcsc_col_id + ')').remove();
    });
  }

  $(document).on('click touch', '.wpcsc_new_combined', function(e) {
    var $combination = $(this).
        closest('.wpcsc_configuration_tr').
        find('.wpcsc_combination');
    var data = {
      action: 'wpcsc_add_combined',
      nonce: wpcsc_vars.nonce,
    };

    $.post(ajaxurl, data, function(response) {
      $combination.append(response);
      wpcsc_combined_terms_init();
    });

    e.preventDefault();
  });

  $(document).on('change', '.wpcsc_combined_selector', function() {
    wpcsc_combined_terms_init();
  });

  $(document).on('click touch', '.wpcsc_combined_remove', function() {
    $(this).closest('.wpcsc_combined').remove();
  });

  wpcsc_table.on('click', '.wpcsc-add-row', function() {
    var wpcsc_this_col = $(this).closest('td');
    var wpcsc_this_row = wpcsc_this_col.closest('tr');

    wpcsc_rows++;

    wpcsc_this_row.after(wpcsc_add_row());
    wpcsc_get_table_data();
  });

  wpcsc_table.on('click', '.wpcsc-del-row', function() {
    if (wpcsc_rows < 2)
      return;

    var r = confirm('Do you want to remove this row? This action cannot undo.');

    if (r == true) {
      var wpcsc_this_col = $(this).closest('td');
      var wpcsc_this_row = wpcsc_this_col.closest('tr');

      wpcsc_rows--;

      wpcsc_this_row.remove();
      wpcsc_get_table_data();
    }
  });

  wpcsc_table.on('click', '.wpcsc-add-col', function() {
    var wpcsc_this_col = $(this).closest('th');
    var wpcsc_col_id = wpcsc_this_col.index();
    wpcsc_cols++;

    wpcsc_add_col(wpcsc_col_id);
    wpcsc_get_table_data();
  });

  wpcsc_table.on('click', '.wpcsc-del-col', function() {
    if (wpcsc_cols < 2)
      return;

    var r = confirm(
        'Do you want to remove this column? This action cannot undo.');

    if (r == true) {
      var wpcsc_this_col = $(this).closest('th');
      var wpcsc_col_id = wpcsc_this_col.index();

      wpcsc_cols--;

      wpcsc_remove_col(wpcsc_col_id);
      wpcsc_get_table_data();
    }
  });

  wpcsc_table.on('keyup', 'input', function(e) {
    var wpcsc_this_input = $(e.target);
    var wpcsc_i_value = wpcsc_this_input.val();

    if (wpcsc_i_value.search(/<[^>]+>/ig) >= 0 || wpcsc_i_value.search('<>') >=
        0 || wpcsc_i_value.search('“') >= 0) {
      wpcsc_this_input.val(wpcsc_i_value.replace(/<[^>]+>/ig, '').
          replace('<>', '').
          replace('“', '"'));
    }

    wpcsc_get_table_data();
  });

  // active
  $(document).on('change', 'input[name="wpcsc_active"]', function() {
    wpcsc_active();
  });

  // type
  $(document).on('change', '.wpcsc_type', function() {
    wpcsc_active_type();
    wpcsc_terms_init();
  });

  // search chart
  $(document).on('change', '.wpcsc-size-chart-search', function() {
    var _val = $(this).val();

    if (Array.isArray(_val)) {
      $(this).closest('div').find('.wpcsc-size-charts-val').val(_val.join());
    } else {
      if (_val === null) {
        $(this).closest('div').find('.wpcsc-size-charts-val').val('');
      } else {
        $(this).closest('div').find('.wpcsc-size-charts-val').val(String(_val));
      }
    }
  });

  // search terms
  $(document).on('change', '.wpcsc_terms_select', function() {
    var $this = $(this);
    var val = $this.val();
    var type = $('.wpcsc_type').val();
    var $terms = $('.wpcsc_terms_val');

    if (Array.isArray(val)) {
      $terms.val(val.join()).trigger('change');
    } else {
      if (val === null) {
        $terms.val('').trigger('change');
      } else {
        $terms.val(String(val)).trigger('change');
      }
    }

    $this.data(type, val.join());
  });

  function wpcsc_select2() {
    $('.wpcsc-size-chart-search').selectWoo({
      ajax: {
        url: ajaxurl, // AJAX URL is predefined in WordPress admin
        dataType: 'json', delay: 250, // delay in ms while typing when to perform a AJAX search
        data: function(params) {
          return {
            q: params.term, // search query
            action: 'wpcsc_search_size_chart', // AJAX action for admin-ajax.php
          };
        }, processResults: function(data) {
          var options = [];

          if (data) {
            // data is the array of arrays, and each of them contains ID and the Label of the option
            $.each(data, function(index, text) { // do not forget that "index" is just auto incremented value
              options.push({id: text[0], text: text[1]});
            });
          }

          return {
            results: options,
          };
        }, cache: true,
      }, minimumInputLength: 3, // the minimum of symbols to input before perform a search
    });
  }

  function wpcsc_terms_init() {
    var $terms = $('.wpcsc_terms_select');
    var type = $('.wpcsc_type').val();

    $terms.selectWoo({
      ajax: {
        url: ajaxurl, dataType: 'json', delay: 250, data: function(params) {
          return {
            q: params.term, action: 'wpcsc_search_term', taxonomy: type,
          };
        }, processResults: function(data) {
          var options = [];
          if (data) {
            $.each(data, function(index, text) {
              options.push({id: text[0], text: text[1]});
            });
          }
          return {
            results: options,
          };
        }, cache: true,
      }, minimumInputLength: 1,
    });

    if ((typeof $terms.data(type) === 'string' || $terms.data(type) instanceof
        String) && $terms.data(type) !== '') {
      $terms.val($terms.data(type).split(',')).change();
    } else {
      $terms.val([]).change();
    }
  }

  function wpcsc_combined_terms_init() {
    $('.wpcsc_apply_terms').each(function() {
      var $this = $(this);
      var taxonomy = $this.closest('.wpcsc_combined').
          find('.wpcsc_combined_selector').
          val();

      $this.selectWoo({
        ajax: {
          url: ajaxurl, dataType: 'json', delay: 250, data: function(params) {
            return {
              q: params.term, action: 'wpcsc_search_term', taxonomy: taxonomy,
            };
          }, processResults: function(data) {
            var options = [];
            if (data) {
              $.each(data, function(index, text) {
                options.push({id: text[0], text: text[1]});
              });
            }
            return {
              results: options,
            };
          }, cache: true,
        }, minimumInputLength: 1,
      });
    });
  }

  function wpcsc_active_type() {
    var type = $('select[name="wpcsc_type"]').val();

    $('.wpcsc_type_row').hide();
    $('.wpcsc_type_' + type).show();

    if (type !== 'all' && type !== 'none' && type !== 'combined') {
      // terms
      $('.wpcsc_type_terms').show();
    }
  }

  function wpcsc_active() {
    if ($('input[name="wpcsc_active"]:checked').val() === 'overwrite') {
      $('.wpcsc-overwrite').show();
    } else {
      $('.wpcsc-overwrite').hide();
    }
  }
})(jQuery);