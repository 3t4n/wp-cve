'use strict';

(function($) {
  $(document).on('woovr_selected', function(e, selected) {
    let id = selected.attr('data-id');
    let pid = selected.attr('data-pid');
    let purchasable = selected.attr('data-purchasable');

    if (purchasable === 'yes' && id >= 0) {
      $('.wpcbn-btn[data-product_id="' + pid + '"]').
          removeClass('wpcbn-disabled');
    } else {
      $('.wpcbn-btn[data-product_id="' + pid + '"]').addClass('wpcbn-disabled');
    }

    $(document).trigger('wpcbn_woovr_selected', [selected]);
  });

  $(document).on('found_variation', function(e, t) {
    let pid = $(e['target']).
        closest('.variations_form').
        attr('data-product_id');

    if (t['is_in_stock'] && t['is_purchasable']) {
      $('.wpcbn-btn[data-product_id="' + pid + '"]').
          removeClass('wpcbn-disabled');
    } else {
      $('.wpcbn-btn[data-product_id="' + pid + '"]').addClass('wpcbn-disabled');
    }

    $(document).trigger('wpcbn_found_variation', [t]);
  });

  $(document).on('reset_data', function(e) {
    let pid = $(e['target']).
        closest('.variations_form').
        attr('data-product_id');

    // disable button
    $('.wpcbn-btn[data-product_id="' + pid + '"]').addClass('wpcbn-disabled');

    $(document).trigger('wpcbn_reset_data');
  });
})(jQuery);
