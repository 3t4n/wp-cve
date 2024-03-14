'use strict';

(function($) {
  $(function() {
    if (!$('.woofs-wrap').length) {
      return;
    }

    $('.woofs-wrap').each(function() {
      woofs_init($(this));
    });
  });

  $(document).on('woosq_loaded', function() {
    if ($('#woosq-popup').find('.woofs-wrap').length) {
      woofs_init($('#woosq-popup').find('.woofs-wrap'));
    }
  });

  $(document).on('woovr_selected', function(e, selected, variations) {
    var $wrap = variations.closest('.woofs-wrap');
    var $product = variations.closest('.woofs-product');
    var $products = variations.closest('.woofs-products');
    var purchasable = selected.attr('data-purchasable');
    var price = selected.attr('data-price');
    var regular_price = selected.attr('data-regular-price');
    var id = selected.attr('data-id');
    var sku = selected.attr('data-sku');
    var weight = selected.attr('data-weight');
    var dimensions = selected.attr('data-dimensions');
    var image_src = selected.attr('data-imagesrc');

    if ($product.length) {
      if (purchasable === 'yes') {
        $product.attr('data-id', id);
        $product.attr('data-price-ori', price);
        $product.attr('data-regular-price', regular_price);

        // change image
        if (image_src !== undefined && image_src !== '') {
          $product.find('.woofs-thumb-ori').hide();
          $product.find('.woofs-thumb-new').
              html('<img src="' + image_src + '"/>').
              show();
        }

        // change price
        var new_price = $product.attr('data-price');

        if (isNaN(new_price)) {
          new_price = price * parseFloat(new_price) / 100;
        }

        $product.find('.woofs-price-ori').hide();
        $product.find('.woofs-price-new').
            html(woofs_price_html(price, new_price)).
            show();

        // change attributes
        var attrs = {};

        $product.find('select[name^="attribute_"]').each(function() {
          var attr_name = $(this).attr('name');

          attrs[attr_name] = $(this).val();
        });

        $product.attr('data-attrs', JSON.stringify(attrs));
      } else {
        // reset data
        $product.attr('data-id', 0);
        $product.attr('data-attrs', '');
        $product.attr('data-price-ori', 0);
        $product.attr('data-regular-price', 0);

        // reset image
        $product.find('.woofs-thumb-ori').show();
        $product.find('.woofs-thumb-new').html('').hide();

        // reset price
        $product.find('.woofs-price-ori').show();
        $product.find('.woofs-price-new').html('').hide();
      }

      // prevent changing SKU / weight / dimensions
      $('.product_meta .sku').html($products.attr('data-product-sku'));
      $('.product_weight, .woocommerce-product-attributes-item--weight .woocommerce-product-attributes-item__value').
          html($products.attr('data-product-weight'));
      $('.product_dimensions, .woocommerce-product-attributes-item--dimensions .woocommerce-product-attributes-item__value').
          html($products.attr('data-product-dimensions'));
    } else {
      var pid = variations.closest('.variations_form').attr('data-product_id');

      $wrap = $('.woofs-wrap-' + pid);
      $products = $wrap.find('.woofs-products');

      if ($products.length &&
          !variations.closest('.woosb-product').length &&
          !variations.closest('.woobt-product').length &&
          !variations.closest('.woosg-product').length) {
        if (id > 0) {
          $products.attr('data-product-price', price);
          $products.attr('data-product-regular-price', regular_price);
          $products.attr('data-product-sku', sku);
          $products.attr('data-product-weight', weight);
          $products.attr('data-product-dimensions', dimensions);
        } else {
          // reset
          $products.attr('data-product-price', 0);
          $products.attr('data-product-regular-price', 0);
          $products.attr('data-product-sku',
              $products.attr('data-product-o_sku'));
          $products.attr('data-product-weight',
              $products.attr('data-product-o_weight'));
          $products.attr('data-product-dimensions',
              $products.attr('data-product-o_dimensions'));
        }
      }
    }

    if ($wrap.length) {
      woofs_init($wrap);
    }
  });

  $(document).on('found_variation', function(e, t) {
    var $wrap = $(e['target']).closest('.woofs-wrap');
    var $product = $(e['target']).closest('.woofs-product');
    var $products = $(e['target']).closest('.woofs-products');

    if ($product.length) {
      var _price = $product.attr('data-price');

      if (isNaN(_price)) {
        _price = t['display_price'] *
            parseInt($product.attr('data-price')) / 100;
      }

      $product.find('.woofs-price-ori').hide();
      $product.find('.woofs-price-new').
          html(woofs_format_price(_price)).
          show();

      $product.attr('data-price-ori', t['display_price']);
      $product.attr('data-regular-price', t['display_regular_price']);

      if (t['is_purchasable'] && t['is_in_stock']) {
        $product.attr('data-id', t['variation_id']);

        // change attributes
        var attrs = {};

        $product.find('select[name^="attribute_"]').each(function() {
          var attr_name = $(this).attr('name');

          attrs[attr_name] = $(this).val();
        });

        $product.attr('data-attrs', JSON.stringify(attrs));
      } else {
        $product.attr('data-id', 0);
        $product.attr('data-attrs', '');
      }

      // change availability
      if (t['availability_html'] && t['availability_html'] !== '') {
        $product.find('.woofs-availability').
            html(t['availability_html']).
            show();
      } else {
        $product.find('.woofs-availability').html('').hide();
      }

      if (t['woofs_image'] !== undefined && t['woofs_image'] !== '') {
        // change image
        $product.find('.woofs-thumb-ori').hide();
        $product.find('.woofs-thumb-new').html(t['woofs_image']).show();
      } else {
        $product.find('.woofs-thumb-ori').show();
        $product.find('.woofs-thumb-new').html('').hide();
      }

      if (woofs_vars.change_image === 'no') {
        // prevent changing the main image
        $(e['target']).
            closest('.variations_form').
            trigger('reset_image');
      }

      // prevent changing SKU / weight / dimensions
      $('.product_meta .sku').html($products.attr('data-product-sku'));
      $('.product_weight, .woocommerce-product-attributes-item--weight .woocommerce-product-attributes-item__value').
          html($products.attr('data-product-weight'));
      $('.product_dimensions, .woocommerce-product-attributes-item--dimensions .woocommerce-product-attributes-item__value').
          html($products.attr('data-product-dimensions'));
    } else {
      var pid = $(e['target']).
          closest('.variations_form').
          attr('data-product_id');

      $wrap = $('.woofs-wrap-' + pid);
      $products = $wrap.find('.woofs-products');

      if ($products.length &&
          !$(e['target']).closest('.woosb-product').length &&
          !$(e['target']).closest('.woobt-product').length &&
          !$(e['target']).closest('.woosg-product').length) {
        $products.attr('data-product-price', t['display_price']);
        $products.attr('data-product-regular-price',
            t['display_regular_price']);

        $products.attr('data-product-sku', t['sku']);
        $products.attr('data-product-weight', t['weight_html']);
        $products.attr('data-product-dimensions', t['dimensions_html']);
      }
    }

    if ($wrap.length) {
      woofs_init($wrap);
    }
  });

  $(document).on('reset_data', function(e) {
    var $wrap = $(e['target']).closest('.woofs-wrap');
    var $product = $(e['target']).closest('.woofs-product');
    var $products = $(e['target']).closest('.woofs-products');

    if ($product.length) {
      $product.attr('data-id', 0);
      $product.attr('data-attrs', '');
      $product.attr('data-price-ori', 0);
      $product.attr('data-regular-price', 0);

      // reset availability
      $product.find('.woofs-availability').html('').hide();

      // reset thumb
      $product.find('.woofs-thumb-new').hide();
      $product.find('.woofs-thumb-ori').show();

      // reset SKU / weight / dimensions
      $('.product_meta .sku').html($products.attr('data-product-sku'));
      $('.product_weight, .woocommerce-product-attributes-item--weight .woocommerce-product-attributes-item__value').
          html($products.attr('data-product-weight'));
      $('.product_dimensions, .woocommerce-product-attributes-item--dimensions .woocommerce-product-attributes-item__value').
          html($products.attr('data-product-dimensions'));
    } else {
      var pid = $(e['target']).
          closest('.variations_form').
          attr('data-product_id');

      $wrap = $('.woofs-wrap-' + pid);
      $products = $wrap.find('.woofs-products');

      if ($products.length &&
          !$(e['target']).closest('.woosb-product').length &&
          !$(e['target']).closest('.woobt-product').length &&
          !$(e['target']).closest('.woosg-product').length) {
        $products.attr('data-product-price', 0);
        $products.attr('data-product-regular-price', 0);
        $products.attr('data-product-sku',
            $products.attr('data-product-o_sku'));
        $products.attr('data-product-weight',
            $products.attr('data-product-o_weight'));
        $products.attr('data-product-dimensions',
            $products.attr('data-product-o_dimensions'));
      }
    }

    if ($wrap.length) {
      woofs_init($wrap);
    }
  });

  $(document).on('change', 'form.cart input[name="quantity"]', function() {
    var $this = $(this);
    var qty = $this.val();

    if ($this.closest('form.cart').find('input[name="woofs_ids"]').length) {
      var wid = $this.closest('form.cart').
          find('input[name="woofs_ids"]').
          attr('data-id');
      var $wrap = $('.woofs-wrap-' + wid);
      var sync_quantity = $wrap.find('.woofs-products').
          attr('data-sync-quantity');

      if (sync_quantity === 'on') {
        $wrap.find('.woofs-product').each(function() {
          var $product = $(this);
          var sync_qty = $product.attr('data-qty-ori') * qty;

          $product.attr('data-qty', sync_qty);
          $product.find('.woofs-qty-num').html(sync_qty);
        });

        woofs_init($wrap);
      }
    }
  });

  $(document).on('click touch', '.single_add_to_cart_button', function(e) {
    if ($(this).hasClass('woofs-disabled')) {
      e.preventDefault();
    }
  });
})(jQuery);

function woofs_init($wrap) {
  var wid = $wrap.attr('data-id');
  var $products = $wrap.find('.woofs-products');
  var $ids = jQuery('.woofs-ids-' + wid);

  if ((woofs_vars.position === 'before') &&
      ($products.attr('data-product-type') === 'variable') &&
      (($products.attr('data-variables') === 'no') ||
          (woofs_vars.variations_selector === 'woovr'))) {
    $wrap.insertAfter($ids);
  }

  woofs_check_ready($wrap);
  woofs_calc_price($wrap);
  woofs_save_ids($wrap);

  jQuery(document).trigger('woofs_init', [$wrap]);
}

function woofs_check_ready($wrap) {
  var wid = $wrap.attr('data-id');
  var $products = $wrap.find('.woofs-products');
  var separately = $products.attr('data-separately');
  var ready = true;
  var is_selection = false;
  var selection_name = '';
  var $alert = $wrap.find('.woofs-alert');
  var $btn = jQuery('.woofs-ids-' + wid).
      closest('form.cart').
      find('.single_add_to_cart_button');

  if (separately !== 'on') {
    $wrap.find('.woofs-product').each(function() {
      var $this = jQuery(this);

      if (parseFloat($this.attr('data-qty')) === 0) {
        $this.addClass('woofs-hide');
      } else {
        $this.removeClass('woofs-hide');
      }

      if ((
          parseFloat($this.attr('data-qty')) > 0
      ) && (
          parseInt($this.attr('data-id')) === 0
      )) {
        is_selection = true;

        if (selection_name === '') {
          selection_name = $this.attr('data-name');
        }
      }
    });
  }

  if (is_selection) {
    ready = false;
    $btn.addClass('woofs-disabled');
    $alert.html(woofs_vars.alert_selection.replace('[name]',
        '<strong>' + selection_name + '</strong>')).slideDown();
  } else {
    $alert.html('').slideUp();
    $btn.removeClass('woofs-disabled');
  }

  jQuery(document).trigger('woofs_check_ready', [ready, is_selection, $wrap]);
}

function woofs_calc_price($wrap) {
  var wid = $wrap.attr('data-id');
  var $products = $wrap.find('.woofs-products');
  var separately = $products.attr('data-separately');
  var $additional = $wrap.find('.woofs-additional');
  var $total = $wrap.find('.woofs-total');
  var $ids = jQuery('.woofs-ids-' + wid);
  var $price = jQuery('.woofs-price-' + wid);
  var $woobt = jQuery('.woobt-wrap-' + wid);
  var additional = 0, additional_regular = 0, total = 0, total_regular = 0;
  var additional_html = '', total_html = '';
  var price = parseFloat($products.attr('data-product-price'));
  var regular_price = parseFloat($products.attr('data-product-regular-price'));
  var qty = parseInt($ids.closest('form.cart').find('input.qty').val());

  $wrap.find('.woofs-product').each(function() {
    var $this = jQuery(this);
    var _price = 0;
    var _regular_price = 0;

    if (parseFloat($this.attr('data-qty')) > 0 &&
        parseInt($this.attr('data-id')) > 0) {
      _regular_price = parseFloat($this.attr('data-regular-price')) *
          parseFloat($this.attr('data-qty'));

      if (separately === 'on') {
        _price = parseFloat($this.attr('data-price-ori')) *
            parseFloat($this.attr('data-qty'));
      } else {
        if (isNaN($this.attr('data-price'))) {
          // is percentage
          _price = (
              parseFloat($this.attr('data-price-ori')) *
              parseInt($this.attr('data-price')) / 100
          ) * parseFloat($this.attr('data-qty'));
        } else {
          _price = parseFloat($this.attr('data-price')) *
              parseFloat($this.attr('data-qty'));
        }
      }

      if (woofs_vars.show_price === 'total') {
        $this.find('.woofs-price-ori').hide();
        $this.find('.woofs-price-new').
            html(woofs_price_html(_regular_price, _price)).
            show();
      }

      // calc total
      additional += _price;
      additional_regular += _regular_price;
    } else {
      $this.find('.woofs-price-new').html('').hide();
      $this.find('.woofs-price-ori').show();
    }
  });

  total = price * qty;
  total_regular = regular_price * qty;

  if (additional > 0) {
    total += additional;
    total_regular += additional_regular;
  }

  additional_html = woofs_price_html(additional_regular, additional);
  total_html = woofs_price_html(total_regular, total);

  if (additional > 0) {
    // additional
    $additional.html(woofs_vars.additional_text + ' ' + additional_html).
        slideDown();

    // total
    $total.html(woofs_vars.total_text + ' ' + total_html).
        slideDown();
  } else {
    $additional.html('').slideUp();
    $total.html('').slideUp();
  }

  // change the main price
  if (woofs_vars.change_price !== 'no') {
    if ((woofs_vars.change_price === 'yes_custom') &&
        (woofs_vars.price_selector != null) &&
        (woofs_vars.price_selector !== '')) {
      $price = jQuery(woofs_vars.price_selector);
    }

    $price.html(total_html);
  }

  if ($woobt.length) {
    $woobt.find('.woobt-products').
        attr('data-product-price-html', total_html);
    $woobt.find('.woobt-product-this').
        attr('data-price', total).
        attr('data-regular-price', total_regular);

    woobt_init($woobt);
  }

  jQuery(document).
      trigger('woofs_calc_price',
          [additional, additional_html, total, total_html, $wrap]);
}

function woofs_save_ids($wrap) {
  var wid = $wrap.attr('data-id');
  var $ids = jQuery('.woofs-ids-' + wid);
  var ids = [];

  $wrap.find('.woofs-product').each(function() {
    var $this = jQuery(this);
    var key = $this.data('key');
    var id = parseInt($this.attr('data-id'));
    var attrs = $this.attr('data-attrs');

    if (attrs !== undefined) {
      attrs = encodeURIComponent(attrs);
    } else {
      attrs = '';
    }

    ids.push(key + '/' + id + '/' + attrs);
  });

  if (ids.length > 0) {
    $ids.val(ids.join(','));
  } else {
    $ids.val('');
  }

  jQuery(document).trigger('woofs_save_ids', [ids, $wrap]);
}

function woofs_format_money(number, places, symbol, thousand, decimal) {
  number = number || 0;
  places = !isNaN(places = Math.abs(places)) ? places : 2;
  symbol = symbol !== undefined ? symbol : '$';
  thousand = thousand || ',';
  decimal = decimal || '.';
  var negative = number < 0 ? '-' : '',
      i = parseInt(number = Math.abs(+number || 0).toFixed(places), 10) +
          '',
      j = 0;
  if (i.length > 3) {
    j = i.length % 3;
  }
  return symbol + negative + (
      j ? i.substr(0, j) + thousand : ''
  ) + i.substr(j).replace(/(\d{3})(?=\d)/g, '$1' + thousand) + (
      places ? decimal + Math.abs(number - i).toFixed(places).slice(2) : ''
  );
}

function woofs_format_price(total, prefix) {
  var total_html = '<span class="woocommerce-Price-amount amount">';
  var total_formatted = woofs_format_money(total, woofs_vars.price_decimals,
      '', woofs_vars.price_thousand_separator,
      woofs_vars.price_decimal_separator);

  switch (woofs_vars.price_format) {
    case '%1$s%2$s':
      //left
      total_html += '<span class="woocommerce-Price-currencySymbol">' +
          woofs_vars.currency_symbol + '</span>' + total_formatted;
      break;
    case '%1$s %2$s':
      //left with space
      total_html += '<span class="woocommerce-Price-currencySymbol">' +
          woofs_vars.currency_symbol + '</span> ' + total_formatted;
      break;
    case '%2$s%1$s':
      //right
      total_html += total_formatted +
          '<span class="woocommerce-Price-currencySymbol">' +
          woofs_vars.currency_symbol + '</span>';
      break;
    case '%2$s %1$s':
      //right with space
      total_html += total_formatted +
          ' <span class="woocommerce-Price-currencySymbol">' +
          woofs_vars.currency_symbol + '</span>';
      break;
    default:
      //default
      total_html += '<span class="woocommerce-Price-currencySymbol">' +
          woofs_vars.currency_symbol + '</span> ' + total_formatted;
  }

  total_html += '</span>';

  if (prefix != null) {
    return prefix + total_html;
  }

  return total_html;
}

function woofs_price_html(regular_price, sale_price) {
  var price_html = '';

  if (sale_price < regular_price) {
    price_html = '<del>' + woofs_format_price(regular_price) +
        '</del> <ins>' +
        woofs_format_price(sale_price) + '</ins>';
  } else {
    price_html = woofs_format_price(regular_price);
  }

  return price_html;
}
