'use strict';

(function($) {
  var woosb_timeout = null;

  $(function() {
    if ($('.woosb-wrap').length) {
      $('.woosb-wrap').each(function() {
        woosb_init($(this), 'ready');
      });
    }
  });

  $(document).on('woosq_loaded', function() {
    // product bundles in quick view popup
    woosb_init($('#woosq-popup .woosb-wrap'), 'woosq');
  });

  $(document).on('woovr_selected', function(e, selected, variations) {
    var $wrap = variations.closest('.woosb-wrap');
    var $product = variations.closest('.woosb-product');

    if ($product.length) {
      var _id = selected.attr('data-id');
      var _price = selected.attr('data-price');
      var _regular_price = selected.attr('data-regular-price');
      var _price_html = selected.attr('data-pricehtml');
      var _image_src = selected.attr('data-imagesrc');
      var _purchasable = selected.attr('data-purchasable');
      var _attrs = selected.attr('data-attrs');
      var _availability = selected.attr('data-availability');

      if (_purchasable === 'yes' && _id >= 0) {
        // change data
        $product.attr('data-id', _id);
        $product.attr('data-price', _price);

        // change price
        woosb_change_price($product, _price, _regular_price, _price_html);

        // change attributes
        $product.attr('data-attrs', _attrs.replace(/\/$/, ''));
      } else {
        // reset data
        $product.attr('data-id', 0);
        $product.attr('data-price', 0);
        $product.attr('data-attrs', '');

        // reset price
        $product.find('.woosb-price-ori').show();
        $product.find('.woosb-price-new').html('').hide();
      }

      // change image
      if (_image_src && _image_src !== '') {
        $product.find('.woosb-thumb-ori').hide();
        $product.find('.woosb-thumb-new').
            html('<img src="' + _image_src + '"/>').
            show();
      } else {
        $product.find('.woosb-thumb-ori').show();
        $product.find('.woosb-thumb-new').html('').hide();
      }

      // change availability
      if (_availability && _availability !== '') {
        $product.find('.woosb-availability').html(_availability).show();
      } else {
        $product.find('.woosb-availability').html('').hide();
      }

      // reset sku, weight & dimensions
      if (typeof wc_reset_content === 'function') {
        $('.product_meta .sku').wc_reset_content();
        $('.product_weight, .woocommerce-product-attributes-item--weight .woocommerce-product-attributes-item__value').
            wc_reset_content();
        $('.product_dimensions, .woocommerce-product-attributes-item--dimensions .woocommerce-product-attributes-item__value').
            wc_reset_content();
      }
    }

    woosb_init($wrap, 'woovr_selected');
  });

  $(document).on('found_variation', function(e, t) {
    var $wrap = $(e['target']).closest('.woosb-wrap');
    var $product = $(e['target']).closest('.woosb-product');

    if ($product.length) {
      if (t['woosb_image'] !== undefined && t['woosb_image'] !== '') {
        // change image
        $product.find('.woosb-thumb-ori').hide();
        $product.find('.woosb-thumb-new').html(t['woosb_image']).show();
      } else {
        $product.find('.woosb-thumb-ori').show();
        $product.find('.woosb-thumb-new').html('').hide();
      }

      if (t['price_html'] !== undefined && t['price_html'] !== '' &&
          t['display_price'] !== undefined && t['display_price'] !== '') {
        woosb_change_price($product, t['display_price'],
            t['display_regular_price'], t['price_html']);
      }

      if (t['variation_description'] !== undefined) {
        $product.find('.woosb-variation-description').
            html(t['variation_description']).
            show();
      } else {
        $product.find('.woosb-variation-description').html('').hide();
      }

      if (t['is_purchasable']) {
        // change the price
        if (woosb_vars.bundled_price_from === 'regular_price' &&
            t['display_regular_price'] !== undefined) {
          $product.attr('data-price', t['display_regular_price']);
        } else {
          $product.attr('data-price', t['display_price']);
        }

        // change stock notice
        if (t['is_in_stock']) {
          $wrap.next('p.stock').show();
          $product.attr('data-id', t['variation_id']);
        } else {
          $wrap.next('p.stock').hide();
          $product.attr('data-id', 0);
        }

        // change availability
        if (t['availability_html'] && t['availability_html'] !== '') {
          $product.find('.woosb-availability').
              html(t['availability_html']).
              show();
        } else {
          $product.find('.woosb-availability').html('').hide();
        }

        // change attributes
        var attrs = {};

        $product.find('select[name^="attribute_"]').each(function() {
          var attr_name = $(this).attr('name');

          attrs[attr_name] = $(this).val();
        });

        $product.attr('data-attrs', JSON.stringify(attrs));
      }

      if (woosb_vars.change_image === 'no') {
        // prevent changing the main image
        $(e['target']).closest('.variations_form').trigger('reset_image');
      }

      // reset sku, weight & dimensions
      if (typeof wc_reset_content === 'function') {
        $('.product_meta .sku').wc_reset_content();
        $('.product_weight, .woocommerce-product-attributes-item--weight .woocommerce-product-attributes-item__value').
            wc_reset_content();
        $('.product_dimensions, .woocommerce-product-attributes-item--dimensions .woocommerce-product-attributes-item__value').
            wc_reset_content();
      }

      $(document).trigger('woosb_found_variation', [$product, t]);

      woosb_init($wrap, 'found_variation');
    }
  });

  $(document).on('reset_data', function(e) {
    var $wrap = $(e['target']).closest('.woosb-wrap');
    var $product = $(e['target']).closest('.woosb-product');

    if ($product.length) {
      // reset thumb
      $product.find('.woosb-thumb-new').hide();
      $product.find('.woosb-thumb-ori').show();

      // reset price
      $product.find('.woosb-price-new').hide();
      $product.find('.woosb-price-ori').show();

      // reset availability
      $product.find('.woosb-availability').html('').hide();

      // reset desc
      $product.find('.woosb-variation-description').html('').hide();

      // reset data
      $product.attr('data-id', 0);
      $product.attr('data-price', 0);
      $product.attr('data-attrs', '');

      $(document).trigger('woosb_reset_data', [$product]);

      woosb_init($wrap, 'reset_data');
    }
  });

  $(document).
      on('click touch',
          '.woosb-quantity-input-plus, .woosb-quantity-input-minus',
          function() {
            // get values
            var $qty = $(this).closest('.woosb-quantity').find('.woosb-qty');

            if (!$qty.length) {
              $qty = $(this).closest('.woosb-quantity').find('.qty');
            }

            var val = parseFloat($qty.val()),
                max = parseFloat($qty.attr('max')),
                min = parseFloat($qty.attr('min')), step = $qty.attr('step');

            // format values
            if (!val || val === '' || val === 'NaN') {
              val = 0;
            }

            if (max === '' || max === 'NaN') {
              max = '';
            }

            if (min === '' || min === 'NaN') {
              min = 0;
            }

            if (step === 'any' || step === '' || step === undefined ||
                parseFloat(step) === 'NaN') {
              step = 1;
            } else {
              step = parseFloat(step);
            }

            // change the value
            if ($(this).is('.woosb-quantity-input-plus')) {
              if (max && (max == val || val > max)) {
                $qty.val(max);
              } else {
                $qty.val((val + step).toFixed(woosb_decimal_places(step)));
              }
            } else {
              if (min && (min == val || val < min)) {
                $qty.val(min);
              } else if (val > 0) {
                $qty.val((val - step).toFixed(woosb_decimal_places(step)));
              }
            }

            // trigger change event
            $qty.trigger('change');
          });

  $(document).on('click touch', '.single_add_to_cart_button', function(e) {
    var $this = $(this);

    if ($this.hasClass('woosb-disabled')) {
      e.preventDefault();
    }
  });

  if ($('.woosb-qty').length) {
    $(document).on('change', '.woosb-qty', function() {
      var $this = $(this);

      woosb_check_qty($this);
    });

    $(document).on('keyup', '.woosb-qty', function() {
      var $this = $(this);

      if (woosb_timeout != null) clearTimeout(woosb_timeout);
      woosb_timeout = setTimeout(woosb_check_qty, 1000, $this);
    });
  } else {
    $(document).on('change', '.woosb-quantity .qty', function() {
      var $this = $(this);

      woosb_check_qty($this);
    });

    $(document).on('keyup', '.woosb-quantity .qty', function() {
      var $this = $(this);

      if (woosb_timeout != null) clearTimeout(woosb_timeout);
      woosb_timeout = setTimeout(woosb_check_qty, 1000, $this);
    });
  }
})(jQuery);

function woosb_init($wrap, context = null) {
  woosb_check_ready($wrap, context);
  woosb_save_ids($wrap, context);

  jQuery(document).trigger('woosb_init', [$wrap, context]);
}

function woosb_check_ready($wrap) {
  var qty = 0;
  var total = 0;
  var total_sale = 0;
  var is_selection = false;
  var selection_name = '';
  var is_unpurchasable = false;
  var unpurchasable_name = '';
  var is_empty = true;
  var is_min = false;
  var is_max = false;
  var is_total_min = false;
  var is_total_max = false;
  var wid = $wrap.attr('data-id');
  var $products = $wrap.find('.woosb-products');
  var $alert = $wrap.find('.woosb-alert');
  var $ids = jQuery('.woosb-ids-' + wid);
  var $btn = $ids.closest('form.cart').find('.single_add_to_cart_button');
  var price_suffix = $products.attr('data-price-suffix');
  var $total = $wrap.find('.woosb-total');
  var $price = jQuery('.woosb-price-' + wid);
  var $woobt = jQuery('.woobt-wrap-' + wid);
  var total_woobt = parseFloat($woobt.length ? $woobt.attr('data-total') : 0);
  var discount = parseFloat($products.attr('data-discount'));
  var discount_amount = parseFloat($products.attr('data-discount-amount'));
  var fixed_price = $products.attr('data-fixed-price') === 'yes';
  var is_optional = $products.attr('data-optional') === 'yes';
  var has_variables = $products.attr('data-variables') === 'yes';
  var exclude_unpurchasable = $products.attr('data-exclude-unpurchasable') ===
      'yes';
  var saved = '';
  var is_discount = discount > 0 && discount < 100;
  var is_discount_amount = discount_amount > 0;
  var qty_min = parseFloat($products.attr('data-min'));
  var qty_max = parseFloat($products.attr('data-max'));
  var total_min = parseFloat($products.attr('data-total-min'));
  var total_max = parseFloat($products.attr('data-total-max'));
  var fix = Math.pow(10, Number(woosb_vars.price_decimals) + 1);

  if (!$products.length || (!has_variables && !is_optional)) {
    // don't need to do anything - already calculated in PHP
    return;
  }

  // calculate price

  if (!fixed_price) {
    $products.find('.woosb-product').each(function() {
      var $this = jQuery(this);

      if ($this.hasClass('woosb-product-unpurchasable') &&
          exclude_unpurchasable) {
        // don't count this product
        return;
      }

      if (parseFloat($this.attr('data-price')) > 0) {
        var _qty = parseFloat($this.attr('data-qty'));
        var _price = parseFloat($this.attr('data-price'));

        total += woosb_round(_price, woosb_vars.price_decimals) * _qty;

        if (!is_discount_amount && is_discount) {
          _price *= (100 - discount) / 100;
        }

        _price = Math.round(_price * fix) / fix;
        total_sale += woosb_round(_price, woosb_vars.price_decimals) * _qty;
      }
    });

    if (is_discount_amount && discount_amount < total) {
      total_sale = total - discount_amount;
      saved = woosb_format_price(discount_amount);
    } else if (is_discount) {
      saved = woosb_round(discount, 2) + '%';
    } else {
      total_sale = total;
    }

    var total_html = woosb_price_html(total, total_sale);
    var total_all_html = woosb_price_html(total + total_woobt,
        total_sale + total_woobt);

    if (saved !== '') {
      total_html += ' <small class="woocommerce-price-suffix">' +
          woosb_vars.saved_text.replace('[d]', saved) + '</small>';
    }

    // change the bundle total
    $total.html(woosb_vars.price_text + ' ' + total_html + price_suffix).
        slideDown();

    if (woosb_vars.change_price !== 'no') {
      // change the main price

      if (woosb_vars.change_price === 'yes_custom' &&
          woosb_vars.price_selector != null && woosb_vars.price_selector !==
          '') {
        $price = jQuery(woosb_vars.price_selector);
      }

      if ($woobt.length) {
        // woobt
        $price.html(total_all_html + price_suffix);
      } else {
        if (typeof $price.attr('data-o_price') === 'undefined') {
          $price.attr('data-o_price', woosb_encode_entities($price.html()));
        }

        $price.html(total_html + price_suffix);
      }
    }

    if ($woobt.length) {
      // woobt
      $woobt.find('.woobt-products').
          attr('data-product-price-html', total_html);
      $woobt.find('.woobt-product-this').
          attr('data-price', total_sale).
          attr('data-regular-price', total);

      woobt_init($woobt);
    }

    jQuery(document).
        trigger('woosb_calc_price',
            [total_sale, total, total_html, price_suffix, $wrap]);
  }

  // check ready

  $products.find('.woosb-product').each(function() {
    var $this = jQuery(this);

    if (parseFloat($this.attr('data-qty')) > 0) {
      is_empty = false;
      qty += parseFloat($this.attr('data-qty'));

      if (parseInt($this.attr('data-id')) === 0) {
        is_selection = true;

        if (selection_name === '') {
          selection_name = $this.attr('data-name');
        }
      }

      if (is_optional && $this.hasClass('woosb-product-unpurchasable')) {
        is_unpurchasable = true;

        if (unpurchasable_name === '') {
          unpurchasable_name = $this.attr('data-name');
        }
      }
    }
  });

  if (is_optional) {
    // check min
    if (qty_min > 0 && qty < qty_min) {
      is_min = true;
    }

    // check max
    if (qty_max > 0 && qty > qty_max) {
      is_max = true;
    }
  }

  if (!fixed_price) {
    // check total min
    if (total_min > 0 && total_sale < total_min) {
      is_total_min = true;
    }

    // check total max
    if (total_max > 0 && total_sale > total_max) {
      is_total_max = true;
    }
  }

  if (is_selection || is_unpurchasable || is_empty || is_min || is_max ||
      is_total_min || is_total_max) {
    $btn.addClass('woosb-disabled');

    if (is_selection) {
      $alert.html(woosb_vars.alert_selection.replace('[name]',
          '<strong>' + selection_name + '</strong>')).slideDown();
    } else if (is_unpurchasable) {
      $alert.html(woosb_vars.alert_unpurchasable.replace('[name]',
          '<strong>' + unpurchasable_name + '</strong>')).slideDown();
    } else if (is_empty) {
      $alert.html(woosb_vars.alert_empty).slideDown();
    } else if (is_min) {
      $alert.html(woosb_vars.alert_min.replace('[min]', qty_min).
          replace('[selected]', qty)).slideDown();
    } else if (is_max) {
      $alert.html(woosb_vars.alert_max.replace('[max]', qty_max).
          replace('[selected]', qty)).slideDown();
    } else if (is_total_min) {
      $alert.html(woosb_vars.alert_total_min.replace('[min]',
          woosb_format_price(total_min)).
          replace('[total]', woosb_format_price(total_sale))).slideDown();
    } else if (is_total_max) {
      $alert.html(woosb_vars.alert_total_max.replace('[max]',
          woosb_format_price(total_max)).
          replace('[total]', woosb_format_price(total_sale))).slideDown();
    }

    jQuery(document).trigger('woosb_check_ready', [
      false,
      is_selection,
      is_unpurchasable,
      is_empty,
      is_min,
      is_max,
      is_total_min,
      is_total_max,
      $wrap]);
  } else {
    $alert.html('').slideUp();
    $btn.removeClass('woosb-disabled');

    // ready
    jQuery(document).trigger('woosb_check_ready', [
      true,
      is_selection,
      is_unpurchasable,
      is_empty,
      is_min,
      is_max,
      is_total_min,
      is_total_max,
      $wrap]);
  }
}

function woosb_save_ids($wrap) {
  var ids = Array();
  var wid = $wrap.attr('data-id');
  var $ids = jQuery('.woosb-ids-' + wid);
  var $products = $wrap.find('.woosb-products');

  $products.find('.woosb-product').each(function() {
    var $this = jQuery(this);
    var id = parseInt($this.attr('data-id'));
    var qty = parseFloat($this.attr('data-qty'));
    var attrs = $this.attr('data-attrs');

    if (id > 0 && qty > 0) {
      if (attrs != undefined) {
        attrs = encodeURIComponent(attrs);
      } else {
        attrs = '';
      }

      ids.push(id + '/' + qty + '/' + attrs);
    }
  });

  $ids.val(ids.join(','));

  jQuery(document).trigger('woosb_save_ids', [ids, $wrap]);
}

function woosb_check_qty($qty) {
  var $wrap = $qty.closest('.woosb-wrap');
  var qty = parseFloat($qty.val());
  var min = parseFloat($qty.attr('min'));
  var max = parseFloat($qty.attr('max'));

  if (qty === '' || isNaN(qty)) {
    qty = 0;
  }

  if (!isNaN(min) && qty < min) {
    qty = min;
  }

  if (!isNaN(max) && qty > max) {
    qty = max;
  }

  $qty.val(qty);
  $qty.closest('.woosb-product').attr('data-qty', qty);

  // change subtotal
  if (woosb_vars.bundled_price === 'subtotal') {
    var $products = $wrap.find('.woosb-products');
    var $product = $qty.closest('.woosb-product');
    var price_suffix = $product.attr('data-price-suffix');
    var ori_price = parseFloat($product.attr('data-price'));
    var qty = parseFloat($product.attr('data-qty'));

    $product.find('.woosb-price-ori').hide();

    if (parseFloat($products.attr('data-discount')) > 0 &&
        $products.attr('data-fixed-price') === 'no') {
      var new_price = woosb_round(
          (ori_price * (100 - parseFloat($products.attr('data-discount')))) /
          100, woosb_vars.price_decimals);

      $product.find('.woosb-price-new').
          html(woosb_price_html(ori_price * qty, new_price * qty) +
              price_suffix).
          show();
    } else {
      $product.find('.woosb-price-new').
          html(woosb_price_html(ori_price * qty) + price_suffix).
          show();
    }
  }

  jQuery(document).trigger('woosb_check_qty', [qty, $qty]);

  woosb_init($wrap, 'woosb_check_qty');
}

function woosb_change_price($product, price, regular_price, price_html) {
  var $products = $product.closest('.woosb-products');
  var price_suffix = $product.attr('data-price-suffix');
  var qty = parseFloat($product.attr('data-qty'));

  // hide ori price
  $product.find('.woosb-price-ori').hide();

  // calculate new price
  if (woosb_vars.bundled_price === 'subtotal') {
    var ori_price = parseFloat(price);

    if (woosb_vars.bundled_price_from === 'regular_price' && regular_price !==
        undefined) {
      ori_price = parseFloat(regular_price);
    }

    var new_price = ori_price;

    if (parseFloat($products.attr('data-discount')) > 0) {
      new_price = woosb_round(
          (ori_price * (100 - parseFloat($products.attr('data-discount')))) /
          100, woosb_vars.price_decimals);
    }

    $product.find('.woosb-price-new').
        html(woosb_price_html(ori_price * qty, new_price * qty) + price_suffix).
        show();
  } else {
    if (parseFloat($products.attr('data-discount')) > 0) {
      var ori_price = parseFloat(price);

      if (woosb_vars.bundled_price_from === 'regular_price' && regular_price !==
          undefined) {
        ori_price = parseFloat(regular_price);
      }

      var new_price = woosb_round(
          (ori_price * (100 - parseFloat($products.attr('data-discount')))) /
          100, woosb_vars.price_decimals);
      $product.find('.woosb-price-new').
          html(woosb_price_html(ori_price, new_price) + price_suffix).
          show();
    } else {
      if (woosb_vars.bundled_price_from === 'regular_price' && regular_price !==
          undefined) {
        $product.find('.woosb-price-new').
            html(woosb_price_html(regular_price) + price_suffix).
            show();
      } else if (price_html !== '') {
        $product.find('.woosb-price-new').html(price_html).show();
      }
    }
  }
}

function woosb_round(value, decimals) {
  return Number(Math.round(value + 'e' + decimals) + 'e-' + decimals);
}

function woosb_format_money(number, places, symbol, thousand, decimal) {
  number = number || 0;
  places = !isNaN(places = Math.abs(places)) ? places : 2;
  symbol = symbol !== undefined ? symbol : '$';
  thousand = thousand !== undefined ? thousand : ',';
  decimal = decimal !== undefined ? decimal : '.';

  var negative = number < 0 ? '-' : '',
      i = parseInt(number = Math.abs(+number || 0).toFixed(places), 10) + '',
      j = 0;

  if (i.length > 3) {
    j = i.length % 3;
  }

  if (woosb_vars.trim_zeros === '1') {
    return symbol + negative + (j ? i.substr(0, j) + thousand : '') +
        i.substr(j).replace(/(\d{3})(?=\d)/g, '$1' + thousand) +
        (places && (parseFloat(number) > parseFloat(i)) ? decimal +
            Math.abs(number - i).
                toFixed(places).
                slice(2).
                replace(/(\d*?[1-9])0+$/g, '$1') : '');
  } else {
    return symbol + negative + (j ? i.substr(0, j) + thousand : '') +
        i.substr(j).replace(/(\d{3})(?=\d)/g, '$1' + thousand) +
        (places ? decimal + Math.abs(number - i).toFixed(places).slice(2) : '');
  }
}

function woosb_format_price(price) {
  var price_html = '<span class="woocommerce-Price-amount amount">';
  var price_formatted = woosb_format_money(price, woosb_vars.wc_price_decimals,
      '', woosb_vars.wc_price_thousand_separator,
      woosb_vars.wc_price_decimal_separator);

  switch (woosb_vars.wc_price_format) {
    case '%1$s%2$s':
      //left
      price_html += '<span class="woocommerce-Price-currencySymbol">' +
          woosb_vars.wc_currency_symbol + '</span>' + price_formatted;
      break;
    case '%1$s %2$s':
      //left with space
      price_html += '<span class="woocommerce-Price-currencySymbol">' +
          woosb_vars.wc_currency_symbol + '</span> ' + price_formatted;
      break;
    case '%2$s%1$s':
      //right
      price_html += price_formatted +
          '<span class="woocommerce-Price-currencySymbol">' +
          woosb_vars.wc_currency_symbol + '</span>';
      break;
    case '%2$s %1$s':
      //right with space
      price_html += price_formatted +
          ' <span class="woocommerce-Price-currencySymbol">' +
          woosb_vars.wc_currency_symbol + '</span>';
      break;
    default:
      //default
      price_html += '<span class="woocommerce-Price-currencySymbol">' +
          woosb_vars.wc_currency_symbol + '</span> ' + price_formatted;
  }

  price_html += '</span>';

  return price_html;
}

function woosb_price_html(regular_price, sale_price) {
  var price_html = '';

  if (parseFloat(woosb_round(sale_price, woosb_vars.wc_price_decimals)) <
      parseFloat(woosb_round(regular_price, woosb_vars.wc_price_decimals))) {
    price_html = '<del>' + woosb_format_price(regular_price) + '</del> <ins>' +
        woosb_format_price(sale_price) + '</ins>';
  } else {
    price_html = woosb_format_price(regular_price);
  }

  return price_html;
}

function woosb_decimal_places(num) {
  var match = ('' + num).match(/(?:\.(\d+))?(?:[eE]([+-]?\d+))?$/);

  if (!match) {
    return 0;
  }

  return Math.max(0, // Number of digits right of decimal point.
      (match[1] ? match[1].length : 0) - // Adjust for scientific notation.
      (match[2] ? +match[2] : 0));
}

function woosb_encode_entities(str) {
  return String(str).
      replace(/&/g, '&amp;').
      replace(/</g, '&lt;').
      replace(/>/g, '&gt;').
      replace(/"/g, '&quot;');
}

function woosb_decode_entities(str) {
  var textArea = document.createElement('textarea');
  textArea.innerHTML = str;
  return textArea.value;
}
