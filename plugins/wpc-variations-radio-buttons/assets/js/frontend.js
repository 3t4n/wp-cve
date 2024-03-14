'use strict';

(function($) {
  $(function() {
    woovr_init();
  });

  $(document).on('click touch mouseover', '.woovr-variations', function() {
    $(this).attr('data-click', 1);
  });

  $(document).on('click touch', '.woovr-variation-radio', function() {
    var _this = $(this);
    var _variations = _this.closest('.woovr-variations');
    var _click = parseInt(_variations.attr('data-click'));
    var _variations_form = _this.closest('.variations_form');

    woovr_do_select(_this, _variations, _variations_form, _click);

    _variations.find('.woovr-variation-radio').
        removeClass('woovr-variation-active');
    _this.addClass('woovr-variation-active').
        find('input[type="radio"]').
        prop('checked', true);
  });

  $(document).on('change', '.woovr-variation-select', function() {
    var _this = $(this);
    var _variations = _this.closest('.woovr-variations');
    var _click = parseInt(_variations.attr('data-click'));
    var _variations_form = _this.closest('.variations_form');
    var _selected = $('option:selected', this);

    woovr_do_select(_selected, _variations, _variations_form, _click);

    if (_selected.attr('data-imagesrc') !== '') {
      _this.closest('.woovr-variation').
          find('.woovr-variation-image').
          html('<img src="' + _selected.attr('data-imagesrc') + '"/>');
    } else {
      _this.closest('.woovr-variation').
          find('.woovr-variation-image').
          html('');
    }

    _this.closest('.woovr-variation').
        find('.woovr-variation-price').
        html(_selected.attr('data-pricehtml'));
  });

  $(document).on('found_variation', function(e, t) {
    var variation_id = t['variation_id'];
    var $variations_default = $(e['target']).
        find('.woovr-variations-default');
    var $variations_select = $(e['target']).
        find('.woovr-variations-select');
    var $variations_select2 = $(e['target']).
        find('.woovr-variations-select2');
    var $variations_ddslick = $(e['target']).
        find('.woovr-variations-ddslick');

    if ($variations_default.length &&
        !$variations_default.closest('.wpc_variations_form').length) {
      // radio
      if (parseInt($variations_default.attr('data-click')) < 1) {
        $variations_default.find(
            '.woovr-variation-radio[data-id="' + variation_id +
            '"] input[type="radio"]').prop('checked', true);
      }
    }

    if ($variations_select.length &&
        !$variations_select.closest('.wpc_variations_form').length) {
      // select
      if (parseInt($variations_select.attr('data-click')) < 1) {
        $variations_select.find('.woovr-variation-select').
            val(variation_id).
            trigger('change');
      }
    }

    if ($variations_select2.length &&
        !$variations_select2.closest('.wpc_variations_form').length) {
      // select2
      if (parseInt($variations_select2.attr('data-click')) < 1) {
        $variations_select2.find('.woovr-variation-select').
            val(variation_id).
            trigger('change');
      }
    }

    if ($variations_ddslick.length &&
        !$variations_ddslick.closest('.wpc_variations_form').length) {
      // ddslick
      if (parseInt($variations_ddslick.attr('data-click')) < 1) {
        var variation_index = $variations_ddslick.find(
            'li:has(.dd-option-value[value="' + variation_id + '"])').index();

        $variations_ddslick.find('.dd-container').
            ddslick('select', {index: variation_index});
      }
    }
  });

  $(document).on('woosq_loaded', function() {
    // wpc quick view popup
    woovr_init();
  });
})(jQuery);

function woovr_init() {
  jQuery('.woovr-variations').each(function() {
    var _variations = jQuery(this);
    var _variations_form = jQuery(this).closest('.variations_form');
    var _select = _variations.find('select');

    if (_variations.hasClass('woovr-variations-ddslick')) {
      _select.ddslick({
        width: '100%', onSelected: function(data) {
          var _click = parseInt(_variations.attr('data-click'));
          var _selected = jQuery(data.original[0].children[data.selectedIndex]);

          if (!_variations.hasClass('woovr-variations-ddslick-checked-desc')) {
            _variations.find('.dd-selected, .dd-option').each(function() {
              if (!jQuery(this).find('.dd-desc').length) {
                jQuery(this).addClass('dd-option-without-desc');
              } else {
                jQuery(this).removeClass('dd-option-without-desc');
              }
            });

            _variations.addClass('woovr-variations-ddslick-checked-desc');
          }

          woovr_do_select(_selected, _variations, _variations_form, _click);
        },
      });
    } else if (_variations.hasClass('woovr-variations-select2')) {
      _select.select2({
        templateResult: woovr_select2_state,
        width: '100%',
        containerCssClass: 'wpc-select2-container',
        dropdownCssClass: 'wpc-select2-dropdown',
      });
      _select.trigger('change');
    } else if (_variations.hasClass('woovr-variations-select')) {
      _select.trigger('change');
    } else if (_variations.hasClass('woovr-variations-default')) {
      _variations.find('input[type="radio"]').each(function() {
        if (jQuery(this).is(':checked')) {
          jQuery(this).trigger('click');
        }
      });
    }
  });
}

function woovr_select2_state(state) {
  if (!state.id) {
    return state.text;
  }

  var $state;

  if (jQuery(state.element).attr('data-imagesrc') != '') {
    $state = jQuery('<span class="image"><img src="' +
        jQuery(state.element).attr('data-imagesrc') +
        '"/></span><span class="info"><span>' + state.text + '</span> <span>' +
        jQuery(state.element).attr('data-description') + '</span></span>');
  } else {
    $state = jQuery(
        '<span class="info"><span>' + state.text + '</span> <span>' +
        jQuery(state.element).attr('data-description') + '</span></span>');
  }

  return $state;
}

function woovr_do_select(selected, variations, variations_form, click) {
  if (click > 0) {
    // compatible with woobt/woosb/woosg/woofs
    if (!variations.closest('.wpc_variations_form').length) {
      if (selected.attr('data-attrs') !== '') {
        var attrs = jQuery.parseJSON(selected.attr('data-attrs'));

        if (attrs !== null) {
          for (var key in attrs) {
            var attr = key;

            if (key.substr(0, 9) !== 'attribute') {
              attr = 'attribute_' + key;
            }

            if (jQuery.isFunction(jQuery.escapeSelector)) {
              if (!variations_form.find(
                  'select[name="' + attr + '"] option[value="' +
                  jQuery.escapeSelector(attrs[key]) + '"]').length) {
                variations_form.find('select[name="' + attr + '"]').
                    append('<option value="' + attrs[key] + '">' + attrs[key] +
                        '</option>');
              }
            } else {
              if (!variations_form.find(
                  'select[name="' + attr + '"] option[value="' + attrs[key] +
                  '"]').length) {
                variations_form.find('select[name="' + attr + '"]').
                    append('<option value="' + attrs[key] + '">' + attrs[key] +
                        '</option>');
              }
            }

            variations_form.find('select[name="' + attr + '"]').val(attrs[key]);
          }
        }
      } else {
        variations_form.find('.reset_variations').trigger('click');
      }

      variations_form.find('select:not(.woovr-variation-select)').
          trigger('change');
    }

    jQuery(document).
        trigger('woovr_selected_on_click',
            [selected, variations, variations_form]);
  }

  jQuery(document).
      trigger('woovr_selected', [selected, variations, variations_form]);
}