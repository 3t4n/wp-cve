yay_swatch_frontend_init = function (yaySwatches, is_ajax = false) {
  jQuery('.variations_form:not(.yay-swatch-init)').each(function (index, element) {
    var _form = jQuery(this);
    if (is_ajax) {
      _form.wc_variation_form();
    }
    _form.addClass('yay-swatch-init');
    const yay_product = _form.yay_variation_form();
    if ('yes' === yaySwatches.is_product_page) {
      window.yay_product = yay_product;
    }
    // add tooltip
    if ('yes' === _form.find('.yay-variant-wrapper').data('show-tooltip')) {
      tippy('[data-tippy-content]');
    }
  });
}

yay_swatch_frontend_start = function (yaySwatches) {
  yay_swatch_frontend_init(yaySwatches);
  var variations_form_selector = '.variations_form:not(.yay-swatch-init)';
  // When Ajax called
  jQuery(document).ajaxComplete(function (event, xhr, request) {
    setTimeout(() => {
      if (jQuery(variations_form_selector).length) {
        yay_swatch_frontend_init(yaySwatches, true);
      }
    }, 100);
  });
  // When Ajax called
  const send = XMLHttpRequest.prototype.send;
  XMLHttpRequest.prototype.send = function () {
    this.addEventListener('load', function () {
      if (jQuery(variations_form_selector).length) {
        yay_swatch_frontend_init(yaySwatches, true);
      }
    });
    return send.apply(this, arguments);
  };

}

yay_swatch_archive_change_image = function (form, variation) {
  if (
    variation &&
    variation.image &&
    variation.image.src &&
    variation.image.thumb_src &&
    variation.image.thumb_src.length > 1
  ) {
    // Click variant end
    form._imageWrapper.wc_set_variation_attr('alt', variation.image.alt);
    form._imageWrapper.wc_set_variation_attr(
      'sizes',
      variation.image.thumb_sizes
    );
    form._imageWrapper.wc_set_variation_attr('src', variation.image.src);
    form._imageWrapper.wc_set_variation_attr(
      'height',
      variation.image.thumb_src_h
    );
    form._imageWrapper.wc_set_variation_attr(
      'width',
      variation.image.thumb_src_w
    );
    form._imageWrapper.wc_set_variation_attr(
      'srcset',
      variation.image.thumb_src
    );
    form._imageWrapper.wc_set_variation_attr('title', variation.image.title);
  }
}

yay_swatch_is_soldout_hide_interact = function (yaySwatches) {
  const soldOutShowHide = yaySwatches.sold_out.soldOutShowHideOptions;
  const hideStyle = yaySwatches.sold_out.soldOutHideStyle;
  return 'hide' === soldOutShowHide && 'interactive' === hideStyle;
};

yay_swatch_get_field_by_term_selected = function (form, dataset, attribute_fields, yaySwatches) {
  var field = attribute_fields.filter((index, attribute) => attribute.id === dataset.attribute);
  if (yaySwatches.wc_product_bundles_active) {
    form.find('.reset_bundled_variations_fixed').show();
    field = attribute_fields.filter(
      (index, attribute) => jQuery(attribute).data('attribute_name') === 'attribute_' + dataset.attribute
    );
  }
  return field;
}

yay_swatch_get_key_by_term_selected = function (event, yaySwatches) {
  var get_attribute_name = jQuery(event.target).data('attribute_name');
  get_attribute_name = get_attribute_name.split('attribute_');
  var key_selected = undefined != get_attribute_name[1] ? get_attribute_name[1] : event.target.id;
  return key_selected;
}

yay_swatch_get_label_by_term_selected = function (form, current_attributes, getLableName, yaySwatches) {
  for (const [key, value] of Object.entries(current_attributes)) {
    var form_element = form.find('select[data-attribute_name="attribute_' + key + '"]');
    const text = form_element.find(`option[value="${value}"]`).text();
    getLableName(form_element, { text: text });
  }
}

// Compatibles 

yay_swatch_product_change_image_compatibles = function (theme, variation) {
  if ('oxygen' === theme) {
    var img_selector = '.yay-swatches-product-details-wrapper .st-product-container .st-product-image img';
    jQuery(img_selector).attr('src', variation.image.url);
  }
}

yay_swatch_product_hide_clear_button_compatibles = function (form, yaySwatches) {
  if (yaySwatches.wc_product_bundles_active) {
    form.find('.reset_bundled_variations_fixed').hide();
  }
}

// YAY SWATCHES COMPATIBLES
yay_swatch_compatibles = function () {
  yay_swatch_wc_composite_products_compatibles();
}

// WooCommerce Product Bundles
yay_swatch_product_bundle_compatibles = function (yaySwatches) {
  if (jQuery('.bundle_form .bundle_data').length > 0) {
    yay_swatch_frontend_init(yaySwatches);
  }
  jQuery(document.body).on('click', 'input.bundled_product_checkbox', function (event) {
    var _input = jQuery(this),
      is_checked = _input.is(':checked'),
      _content = _input.closest('.details').find('.bundled_item_cart_content.variations_form.yay-swatch-init');
    if (is_checked) {
      _content.addClass('yay-swatch-clicked');
    } else {
      _content.removeClass('yay-swatch-clicked');
    }
  });
}

// WooCommerce Composite Products plugin
yay_swatch_wc_composite_products_compatibles = function () {

  if (yaySwatches.wc_composite_products_active) {
    jQuery(document.body).on('wc-composite-initializing', function (event, composite) {
      if (typeof (jQuery.fn.yay_variation_form) === 'function') {
        composite.actions.add_action('component_scripts_initialized', function (step) {
          if ('variable' === step.get_selected_product_type()) {
            step.$component_summary_content.yay_variation_form();
          }
        }, 10, this);
      }
    });
  }

}