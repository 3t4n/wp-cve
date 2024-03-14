(function name($, window, document) {
  var yayVariationForm = function ($form) {
    var self = this;
    // variable
    self.$form = $form;
    self.initStock = {};
    self.variantWrapperClass = '.yay-variant-wrapper';
    self.termClass = '.yay-swatches-attribute-term';
    self.swatchActive = 'yay-swatches-active';
    self.swatchActiveClass = '.yay-swatches-active';
    self.swatchRadioClass = 'yay-swatches-swatch-radio';
    self.attrPreviewWrapper = 'span.yay-swatches-attribute-preview-wrapper';
    self.unavailableClass = 'disabled wc-variation-is-unavailable';
    self.$swatches = $form.find(self.termClass);
    self.$variantions = $form.find('.variations');
    self.$swatchWrapper = $form.find(self.variantWrapperClass);
    self.$attributeFields = $form.find('.variations select');
    self.$singleVariation = $form.find('.single_variation');
    self.variationData = $form.data('product_variations');
    self.useAjax = false === self.variationData;
    self.currentOptions = {};
    self.globalAvailableOptions = [];
    self.termSelected = {};
    self.inStockVariants = {};

    // function
    self.getAvailableOptions = self.getAvailableOptions.bind(self);
    self.resetSwatches = self.resetSwatches.bind(self);
    self.getChosenAttributes = self.getChosenAttributes.bind(self);
    self.soldOutClass = self.getSoldOutClasessName.bind(self);
    self.getLableName = self.getLableName.bind(self);
    self.reloadPage = self.reloadPage.bind(self);
    self.lockSwatch = self.lockSwatch.bind(self);
    self.doSelect = self.doSelect.bind(self);
    self.variantAvailableToObj = self.variantAvailableToObj.bind(self);
    self.handleSoldOutSwatch = self.handleSoldOutSwatch.bind(self);
    self.getAttributesFromVariation =
      self.getAttributesFromVariation.bind(self);
    self.onChangeSelect = self.onChangeSelect.bind(self);
    self.autoResetSwatches = self.autoResetSwatches.bind(self);
    // event
    $form.on('click', self.termClass, { yayVariationForm: self }, self.onClick);
    $form.on(
      'change',
      '.variations select',
      { yayVariationForm: self },
      self.onSelect
    );
    $form.on(
      'check_variations',
      { yayVariationForm: self },
      self.onFindVariation
    );

    $form.on(
      'found_variation',
      { yayVariationForm: self },
      self.onFoundVariation
    );

    $form.find('.reset_variations').on(
      'click',
      {
        yayVariationForm: self,
      },
      self.resetSwatches
    );

    self.reloadPage(self);
  };

  yayVariationForm.prototype.doSelect = function (selectKey, value) {
    const { currentOptions, globalAvailableOptions, inStockVariants } = this;
    let stock = inStockVariants;
    const varis = globalAvailableOptions;
    if (!selectKey || !value) {
      return;
    }
    currentOptions[selectKey] = value;
    const newStock = JSON.parse(JSON.stringify(this.initStock));
    newStock[selectKey] = stock[selectKey];
    stock = newStock;
    const stockKeys = Object.keys(stock);
    varis.forEach((vari) => {
      stockKeys.forEach((checkKey) => {
        if (selectKey === checkKey) {
          return;
        }
        for (let index = 0; index < stockKeys.length; index++) {
          const compareKey = stockKeys[index];
          if (compareKey === checkKey) {
            continue;
          }
          if (
            currentOptions[compareKey] &&
            vari[compareKey] !== currentOptions[compareKey]
          ) {
            return;
          }
        }
        stock[checkKey][vari[checkKey]] = true;
      });
    });
    this.inStockVariants = stock;
  };

  yayVariationForm.prototype.reloadPage = function (form) {
    const { variationData, getAvailableOptions, $form } = form;
    if (form.useAjax) {
      $form.block({
        message: null,
        overlayCSS: { background: '#fff', opacity: 0.6 },
      });
      $.ajax({
        url: yaySwatches.ajaxurl,
        type: 'POST',
        data: {
          action: 'get_available_variation',
          product_id: $form.data('product_id'),
          _wpnonce: yaySwatches.nonce,
        },
        success: function success(res) {
          form.handleSoldOutSwatch(res);
        },
        complete: function complete() {
          $form.unblock();
          $form.css({ visibility: 'visible', height: 'auto', opacity: 1 });
        },
      });
    } else {
      const availableVariations = getAvailableOptions(variationData);
      form.handleSoldOutSwatch(availableVariations);
      $form.css({ visibility: 'visible', height: 'auto', opacity: 1 });
    }
  };

  yayVariationForm.prototype.handleSoldOutSwatch = function (args_available) {
    this.globalAvailableOptions = args_available;
    this.variantAvailableToObj(args_available);
    const attributes_data = this.getChosenAttributes().data;
    const attributes = this.getAttributesFromVariation(attributes_data); // current option selected
    for (const [key, value] of Object.entries(attributes)) {
      this.doSelect(key, value);
    }
    if (yay_swatch_is_soldout_hide_interact(yaySwatches)) {
      if (Object.keys(this.currentOptions).length) {
        this.lockSwatch();
      }
    } else {
      this.lockSwatch();
    }
    this.autoResetSwatches();
  };

  yayVariationForm.prototype.variantAvailableToObj = function (res) {
    if (!res.length) return;
    let stock = {};
    Object.keys(res[0]).forEach((k) => {
      stock[k] = {};
    });
    this.initStock = JSON.parse(JSON.stringify(stock));
    res.forEach((vari) => {
      for (const [attr, val] of Object.entries(vari)) {
        stock[attr][val] = true;
      }
    });
    this.inStockVariants = stock;
  };

  yayVariationForm.prototype.lockSwatch = function () {
    const { $swatchWrapper, soldOutClass, termClass } = this;
    const yaySoldOutClass = soldOutClass();
    $swatchWrapper.each((index, swatchWrap) => {
      const $swatch = $(swatchWrap).find(termClass);
      $swatch.each((idx, swatch) => {
        const swatchAttr = swatch.dataset.attribute;
        const swatchValue = swatch.dataset.term;
        if (this?.inStockVariants[swatchAttr]?.[swatchValue]) {
          swatch.classList.remove(yaySoldOutClass);
        } else {
          swatch.classList.add(yaySoldOutClass);
        }
      });
    });
  };

  yayVariationForm.prototype.onClick = function (event) {
    const yayVariationForm = event.data.yayVariationForm;
    const {
      $form,
      $attributeFields,
      swatchActive,
      variantWrapperClass,
      swatchActiveClass,
      unavailableClass,
      getLableName,
    } = yayVariationForm;
    let _this = event.target,
      no_matching_txt =
        wc_add_to_cart_variation_params.i18n_no_matching_variations_text,
      dataset = _this.dataset;
    if (!dataset.attribute && $(this)[0]) {
      _this = $(this)[0];
      dataset = _this.dataset;
    }

    if ('radio' !== $(_this).data('type')) {
      event.stopPropagation();
      event.preventDefault();
    }
    const field = yay_swatch_get_field_by_term_selected($form, dataset, $attributeFields, yaySwatches);
    if (field && field.find(`option[value="${dataset.term}"]`).length) {
      yayVariationForm.termSelected = {
        key: dataset.attribute,
        value: dataset.term,
      };
      field.val(dataset.term).change();
    } else {
      if ($attributeFields.length > 1) {
        alert(no_matching_txt);
        return;
      }
      if (yay_swatch_is_soldout_hide_interact(yaySwatches)) {
        yayVariationForm.lockSwatch();
      }
      $form.find('.reset_variations').css('visibility', 'visible');
      $form
        .find('.single_variation')
        .show()
        .html('<p>' + no_matching_txt + '</p>');
      $('.single_add_to_cart_button').addClass(unavailableClass);
    }
    $(swatchActiveClass, _this.closest(variantWrapperClass)).removeClass(
      swatchActive
    );
    _this.classList.add(swatchActive);
    // get Label name
    getLableName($(this));
  };

  yayVariationForm.prototype.resetSwatches = function (event) {
    const {
      $form,
      $swatches,
      $variantions,
      attrPreviewWrapper,
      swatchRadioClass,
      soldOutClass,
      swatchActive,
      variantAvailableToObj,
      globalAvailableOptions,
    } = this;
    const sold_out_class = soldOutClass();
    $swatches.removeClass(`${sold_out_class} ${swatchActive}`);
    // Hide Clear button ( fix conflict plugin )
    yay_swatch_product_hide_clear_button_compatibles($form, yaySwatches);
    // set prop checked to false if type radio
    if ($swatches.hasClass(swatchRadioClass)) {
      $swatches.prop('checked', false);
    }
    // remove Label name
    $variantions.find(attrPreviewWrapper).remove();

    this.termSelected = { key: '', value: '' };
    variantAvailableToObj(globalAvailableOptions);
  };

  yayVariationForm.prototype.getChosenAttributes = function () {
    var data = {};
    var count = 0;
    var chosen = 0;
    this.$attributeFields.each(function () {
      var attribute_name =
        $(this).data('attribute_name') || $(this).attr('name');
      var value = $(this).val() || '';
      if (value.length > 0) {
        chosen++;
      }
      count++;
      data[attribute_name] = value;
    });

    return {
      count: count,
      chosenCount: chosen,
      data: data,
    };
  };

  yayVariationForm.prototype.onSelect = function (event) {
    const { yayVariationForm } = event.data;
    const key_selected = yay_swatch_get_key_by_term_selected(event, yaySwatches);
    yayVariationForm.termSelected = {
      key: key_selected,
      value: event.target.value,
    };
    if (
      !$(event.target).parent().hasClass('yay-swatch-variant-default-wrapper')
    ) {
      if (event.target.value === '') {
        $(event.target)
          .closest('tr')
          .find(yayVariationForm.attrPreviewWrapper)
          .remove(); // remove Label Name

        yayVariationForm.termSelected = {
          key: key_selected,
          value: '',
        };
      }
    }
    yayVariationForm.onChangeSelect();
  };

  yayVariationForm.prototype.onChangeSelect = function () {
    const {
      getAttributesFromVariation,
      getChosenAttributes,
      termSelected,
      doSelect,
      lockSwatch,
    } = this;
    const attributes_data = getChosenAttributes().data;
    currentOptions = getAttributesFromVariation(attributes_data);
    this.currentOptions = currentOptions;
    doSelect(termSelected.key, termSelected.value);
    if (yay_swatch_is_soldout_hide_interact(yaySwatches)) {
      if (termSelected.key != '' && termSelected.value != '') {
        lockSwatch();
      }
    } else {
      lockSwatch();
    }
  };

  yayVariationForm.prototype.autoResetSwatches = function () {
    for (const [key, value] of Object.entries(this.currentOptions)) {
      if (!this?.inStockVariants[key]?.[value]) {
        setTimeout(() => {
          this.$form.find('.reset_variations').trigger('click');
        }, 0);
        break;
      }
    }
  };

  yayVariationForm.prototype.onFindVariation = function (event) {
    const {
      getLableName,
      getChosenAttributes,
      $form,
      getAttributesFromVariation,
    } = event.data.yayVariationForm;
    const attributes = getChosenAttributes();
    const current_attributes = getAttributesFromVariation(attributes.data);
    yay_swatch_get_label_by_term_selected($form, current_attributes, getLableName, yaySwatches);
  };

  yayVariationForm.prototype.onFoundVariation = function (
    event,
    variation,
    purchasable
  ) {
    yay_swatch_product_change_image_compatibles(yaySwatches.is_theme_active, variation);
  };

  yayVariationForm.prototype.getAvailableOptions = function (variationData) {
    const options = [];
    variationData.forEach((variation) => {
      if (variation.is_in_stock) {
        options.push(this.getAttributesFromVariation(variation.attributes));
      }
    });

    return options;
  };

  // get Class Sold out
  yayVariationForm.prototype.getSoldOutClasessName = function () {
    let classes_name = 'yay-swatches-no-effect';
    switch (yaySwatches.sold_out.soldOutShowHideOptions) {
      case 'show':
        switch (yaySwatches.sold_out.soldOutShowStyle) {
          case 'cross':
            classes_name = 'yay-swatches-disabled';
            break;
          case 'gray_out':
            classes_name = 'yay-swatches-disabled-grayout';
            break;
          case 'opacity':
            classes_name = 'yay-swatches-disabled-opacity';
            break;
          default:
            break;
        }
        break;
      default:
        classes_name = 'yay-swatches-disabled-hide';
        break;
    }
    return classes_name;
  };

  // get Label name
  yayVariationForm.prototype.getLableName = function (_this, autoSet = false) {
    const label_html = '.label strong.yay-swatches-attribute-preview',
      _label_text = !autoSet ? _this.data('label-text') : autoSet.text,
      _parent = '.variations tr';

    if (_this.closest(_parent).find(label_html).html()) {
      _this.closest(_parent).find(label_html).text(_label_text);
    } else {
      const html = `<span class="yay-swatches-attribute-preview-wrapper">: <strong class="yay-swatches-attribute-preview">${_label_text}</strong></span>`;
      _this.closest(_parent).find('.label label').append(html);
    }
  };

  yayVariationForm.prototype.getAttributesFromVariation = function (
    attributes
  ) {
    const currentOptions = {};
    const AttributePrefix = 'attribute_';
    for (const [attr, val] of Object.entries(attributes)) {
      const haveAttributePrefix = attr.indexOf(AttributePrefix);
      if (val !== '') {
        currentOptions[
          haveAttributePrefix !== -1
            ? attr.substring(AttributePrefix.length)
            : attr
        ] = val;
      }
    }
    return currentOptions;
  };

  $.fn.yay_variation_form = function () {
    return new yayVariationForm(this);
  };

  $(function () {
    if (typeof wc_add_to_cart_variation_params !== 'undefined') {
      yay_swatch_frontend_start(yaySwatches);
      // YaySwatches compatibles
      yay_swatch_compatibles(yaySwatches);
    }
  });
})(jQuery, window, document);
