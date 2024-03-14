/**
 * 360 Product Rotation Plugin by www.yofla.com
 *
 * Support script for woocommerce integration
 */

;(function ($, window, document, undefined) {


  /**
   * Determine which product with 360 view was selected.
   *
   * Loops through variations and checks if any matches the provided selectedValues
   *
   * @param selectedValues Object, example:  {'color': 'red'}  ({attributeName: attributeValue})
   * @param variations array, The product data of all variations
   */
  var findSelectedProduct = function (selectedValues, variations) {
    var productId = undefined;

    //loop through variants
    $.each(variations, function (index, variationData) {

      //counters
      var attributesCount = 0, matches = 0;

      //handle
      var attributes = variationData['attributes'];

      //loop through variant attributes
      $.each(attributes, function (attributeName, attributeValue) {
        var isAttributeSelected = checkAttributeSelected(selectedValues, attributeName, attributeValue)
        if (isAttributeSelected) {
          matches += 1;
        }
        attributesCount += 1;
      });

      //we have found a match - a variant product those attributes match the selectedValues
      if (matches == attributesCount) {
        productId = variationData['id'] || variationData['variation_id']  //variation_id is 2.x woocommerce
      }

    });

    return productId;
  }

  /**
   * Returns some varation productId (used when no pre-defined default variaiton is set)
   *
   * @param selectedValues Object, example:  {'color': 'red'}  ({attributeName: attributeValue})
   * @param variations array, The product data of all variations
   */
  var findSomeProduct = function (variations) {
    var index = window.Yofla360_defaultVarationViewIndex || 0;
    return variations[index]['id'] || variations[index]['variation_id']
  }

  /**
   * Returns true, if attributeName of attributeValue is presented in selectedValues
   * @param selectedValues Object, example:  {'color': 'red'}  ({attributeName: attributeValue})
   * @param attributeName
   * @param attributeValue
   */
  var checkAttributeSelected = function (selectedValues, attributeName, attributeValue) {

    if (selectedValues[attributeName] && selectedValues[attributeName] == attributeValue) {
      return true;
    } else {
      return false;
    }
  }

  var VariationForm = function ($form) {
    this.$form = $form;
    this.$product = $form.closest('.product');
    this.variationData = $form.data('product_variations');
    $form.on('show_variation', {variationForm: this}, this.onShowVariation);
  };

  var showVariantByProductId = function (productId) {
    if (productId) {

      //hide all
      $('div.y360_variable_products div.y360_variant').hide();

      //show selected
      var classId = 'y360_variant_content_' + productId;
      $('div.' + classId).show();
    }
  }

  /**
   * Triggered when an attribute field changes.
   */
  VariationForm.prototype.onShowVariation = function (event) {
    //vars
    var selectedValues = {};

    //references
    var form = event.data.variationForm;
    var variationData = form.variationData; //available variations with all data

    //loop thorough all variation groups, save selected variants
    var $selectFields = form.$form.find('select')
    $selectFields.each(function (index, selectElement) {
      var attributeName = $(selectElement).data('attribute_name')
      var selectedVariant = $(selectElement).find(":selected").val()
      selectedValues[attributeName] = selectedVariant;
    });

    var productId = findSelectedProduct(selectedValues, variationData)

    if (productId) {
      //hide all
      showVariantByProductId(productId)
    }
  };

  var fixThumb = function () {

    var _scope = this;

    var element = $('div.yofla_360_has_360_view  ol > li:nth-child(1) > img');

    if (element.length == 0) {
      setTimeout(function () {
        fixThumb.apply(_scope)
      }, 1000)
      return;
    } else {
      // default thumb
      var data = 'data:image/png;base64,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'

      // custom thumb, if set
      if (window.Yofla360_woocommerce_360thumb_url) {
        data = window.Yofla360_woocommerce_360thumb_url;
      }

      var replacementWaiting = 0;

      $.each(element, function (key, elementItem) {

        elementItem = $(elementItem)

        // set thumb image
        if (elementItem.attr('src') == data) {
          // all set
          return;
        } else {
          // set src
          elementItem.attr('src', data);
          // request check
          replacementWaiting += 1;
        }
      }) // each

      if(replacementWaiting > 0){
        setTimeout(function () {
          fixThumb.apply(_scope)
        }, 1000)
      }

    }
  };

  /**
   * On page laoded function
   */
  $(function () {

    // insert 360 views
    var yofla360Content = '';
    $.each(window.yofla360html, function (key, value) {
      if(value){
        var parser = new DOMParser().parseFromString(value, "text/html");
        yofla360Content += parser.documentElement.textContent;
      }
    })


    if(yofla360Content){
      $('figure.woocommerce-product-gallery__wrapper')
      // add 360 content
        .prepend(yofla360Content);
    }


    // variants
    if (typeof wc_add_to_cart_variation_params !== 'undefined') {


      var checkIfVariantInDom = setInterval(function() {


        if ($('div.y360_variant').length) {
          // variants 360 view code found

          var some_form = undefined

          this.variationData =
            $('.variations_form').each(function () {
              //init form object
              var $form = $(this);
              new VariationForm($form);
              some_form = some_form || $form.data('product_variations');
            });

          var someProductId = some_form && some_form[0] && some_form[0]['variation_id']
          if(someProductId){
            showVariantByProductId(someProductId)
          }


          // remove interval
          clearInterval(checkIfVariantInDom);
        }
      }, 500);
    }

    //add 360 thumbs
    fixThumb();

  });

})(jQuery, window, document);


