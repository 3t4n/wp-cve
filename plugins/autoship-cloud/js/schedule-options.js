jQuery( function ($) {

  var init_autoship_schedule_options = function($) {

    /*
    * Initializes and defaults the array global autoshipTemplateData if undefined.
    */
    var initautoshipTemplateData = function ( ){
      if ( window.autoshipTemplateData == undefined ){
        window.autoshipTemplateData = {
          cartBtn             : '.add_to_cart_button',
          yesBtn              : '.autoship-yes-radio',
          noBtn               : '.autoship-no-radio',
          optionsCls          : '.autoship-schedule-options',
          discountPriceCls    : '.autoship-percent-discount',
          checkoutPriceCls    : '.autoship-checkout-price',
          discountStringCls   : '.autoship-custom-percent-discount-str',
          frequencyCls        : '.autoship-frequency',
          frequencySelectCls  : '.autoship-frequency-select',
          frequencyTypeValCls : '.autoship-frequency-type-value',
          frequencyValCls     : '.autoship-frequency-value',
          productCls          : '.product',
          cartItemCls         : '.cart_item',
          variationFormCls    : '.variations_form',
          variationIdCls      : '.variation_id',
          findProductFn       : undefined, // Pluggable Function
          findAutoshipOptions : undefined, // Pluggable Function
          retrieveProductIdFn : undefined, // Pluggable Function
          setVariationIdFn    : undefined, // Pluggable Function
          getVariationIdFn    : undefined, // Pluggable Function
          isSimpleProductFn   : undefined, // Pluggable Function
          isCartPageFn        : undefined, // Pluggable Function
          isCartPage          : false
        };
      }
    }();

    var isSimpleProduct = function( $thisProduct ){

      if ( window.autoshipTemplateData.isSimpleProductFn != undefined ){
        var func = window.autoshipTemplateData.isSimpleProductFn;
        return window[func]( $thisProduct );
      } else {
        return $thisProduct.hasClass('product-type-simple') || $(this).hasClass('autoship-schedule-options-simple');
      }
    }

    var retrieveProductId = function( $thisProduct ){

      if ( window.autoshipTemplateData.retrieveProductIdFn != undefined ){
        var func = window.autoshipTemplateData.retrieveProductIdFn;
        return window[func]( $thisProduct );
      } else {
        var $id = ( isSimpleProduct( $thisProduct ) ) ?
        $thisProduct.find(window.autoshipTemplateData.optionsCls).attr( 'data-autoship-id' ) :
        $thisProduct.find(window.autoshipTemplateData.optionsCls).attr( 'data-autoship-variation-id' );
      }

      return $id;

    }

    var setVariationId = function( target, id ){

      if ( window.autoshipTemplateData.setVariationIdFn != undefined ){
        var func = window.autoshipTemplateData.setVariationIdFn;
        return window[func]( target, id );
      } else {
        $( target ).find(window.autoshipTemplateData.optionsCls).attr( 'data-autoship-variation-id', id );
      }

    }

    var getVariationId = function( target ){

      if ( window.autoshipTemplateData.setVariationIdFn != undefined ){
        var func = window.autoshipTemplateData.getVariationIdFn;
        return window[func]( target );
      }

      var $id = $( target ).find(window.autoshipTemplateData.variationIdCls);
      return ( $id != undefined && $id.val() != undefined ) ? $id.val() : 0;

    }

    var findAutoshipOptions = function( target ){

      if ( window.autoshipTemplateData.findAutoshipOptions != undefined ){
        var func = window.autoshipTemplateData.findAutoshipOptions;
        return window[func]( target );
      }

      return $( target ).closest(window.autoshipTemplateData.optionsCls);

    }

    var findProduct = function( target ){

      if ( window.autoshipTemplateData.findProductFn != undefined ){
        var func = window.autoshipTemplateData.findProductFn;
        return window[func]( target );
      } else if ( window.autoshipTemplateData.isCartPage ) {
        return $( target ).closest(window.autoshipTemplateData.cartItemCls);
      } else {
        return $( target ).closest(window.autoshipTemplateData.productCls);
      }
    }

    var isCartPage = function( $autoshipOptions ){

      if ( window.autoshipTemplateData.isCartPageFn != undefined ){
        var func = window.autoshipTemplateData.isCartPageFn;
        window.autoshipTemplateData.isCartPage = window[func]( $autoshipOptions );
      } else {
        window.autoshipTemplateData.isCartPage = $autoshipOptions.hasClass('autoship-cart-schedule-options');
      }

      return window.autoshipTemplateData.isCartPage;

    }

    $( 'body ' + window.autoshipTemplateData.optionsCls ).each( function () {

      isCartPage( $( this ) );

      var $thisProduct      = findProduct( this );
      var $autoshipOptions  = $( this );
      var priceData         = [];
      var $autoshipYesRadio = $autoshipOptions.find( window.autoshipTemplateData.yesBtn );
      var $autoshipNoRadio  = $autoshipOptions.find( window.autoshipTemplateData.noBtn );
      var $addToCartBtn     = $thisProduct.find( window.autoshipTemplateData.cartBtn );

      /*
       * Core Data Retrieval
       */

      /*
       * Retrieves the Autoship Data from the autoship data attribute
       * Initializes the array global autoshipProductData if undefined.
       * Also missing data to the global for later use.
       */
      var autoshipData = function ( id, target ){

        if ( window.autoshipProductData == undefined )
        window.autoshipProductData = [];

        if ( window.autoshipProductData[id] == undefined ){

          var $thisProduct = findProduct( target );
          var $autoshipOptions = $thisProduct.find(window.autoshipTemplateData.optionsCls);
          var autoshipProductData = $autoshipOptions.data('autoship');

          if ( autoshipProductData == undefined )
          return false;

          if ( isSimpleProduct( $thisProduct ) ){
            window.autoshipProductData[id] = autoshipProductData;
          } else if ( autoshipProductData[id] != undefined ){
            window.autoshipProductData[id] = autoshipProductData[id];
          } else {
            return false;
          }

        }

        return window.autoshipProductData[id];

      }

      /*
       * Display Toggle Methods
       */

      // Toggle Price
      var togglePriceDisplay = function( $thisProduct, resetPrice ){

        var priceInfo = priceData[ retrieveProductId( $thisProduct ) ];

        if ( priceInfo != undefined )
        displayCustomPrice( $thisProduct, priceInfo.customized_price_enabled && !resetPrice ? priceInfo.autoship_price : priceInfo.original_price, priceInfo.original_price_selector );

      }

      // Displays the Custom Autoship Price HTML
      var displayCustomPrice = function ( $thisProduct, $priceHTML, $containerSelector ){

        var $priceWrapper = $thisProduct.find( $containerSelector );

        if ( $priceWrapper.length )
        $priceWrapper.html( $priceHTML );

      }

      // Displays the Select Schedule Drop down & displays the custom Price HTML
      var selectDisplay = function( $thisProduct, $autoshipOptions ){

        if ( selectFrequency( $thisProduct, $autoshipOptions ) )
        $autoshipOptions.find(window.autoshipTemplateData.frequencyCls).addClass('active');

      };

      // Shows/Hides the Autoship Labels
      var toggleAutoshipLabels = function ( $autoshipOptions, show ){

        if ( show ) {
            $autoshipOptions.find('.autoship-discount-label').show();
            $autoshipOptions.find('.autoship-no-discount-label').hide();
        } else {
            $autoshipOptions.find('.autoship-discount-label').hide();
            $autoshipOptions.find('.autoship-no-discount-label').show();
        }

      }

      /*
       * Hide Methods
       */

      // Hides the Select Schedule Drop down & displays the original Price HTML
      var selectHide = function( $thisProduct, $autoshipOptions ){

        clearFrequency( $thisProduct, $autoshipOptions );
        $autoshipOptions.find(window.autoshipTemplateData.frequencyCls).removeClass('active');

      };

      /*
       * Value Setters
       */

      // Retrieves the selected Autoship Schedule Options & adds them to the btn and input
      var selectFrequency = function( $thisProduct, $autoshipOptions ) {

        var option = JSON.parse( $autoshipOptions.find(window.autoshipTemplateData.frequencySelectCls).val());

        if ( option == undefined )
        return false;

        $autoshipOptions.find(window.autoshipTemplateData.frequencyTypeValCls).val(option.frequency_type);
        $autoshipOptions.find(window.autoshipTemplateData.frequencyValCls).val(option.frequency);

        //Add the values to the add to cart button data attr for ajax
        $thisProduct.find(window.autoshipTemplateData.cartBtn).attr( 'data-autoship_frequency_type', option.frequency_type );
        $thisProduct.find(window.autoshipTemplateData.cartBtn).attr( 'data-autoship_frequency', option.frequency );
        return true;

      }

      // Removes the currently assigned Schedule values
      var clearFrequency = function( $thisProduct, $autoshipOptions ) {

        $autoshipOptions.find(window.autoshipTemplateData.frequencyTypeValCls).val('');
        $autoshipOptions.find(window.autoshipTemplateData.frequencyValCls).val('');

        //Remove the values from the add to cart button data attr for ajax
        $thisProduct.find(window.autoshipTemplateData.cartBtn).attr( 'data-autoship_frequency_type', '' );
        $thisProduct.find(window.autoshipTemplateData.cartBtn).attr( 'data-autoship_frequency', '' );

      }

      // Resets, Clears, and Hides all Autoship for Product
      var clearAllAutoship = function( $thisProduct, $autoshipOptions ){

        // Hide the options and reset to no.
        $autoshipOptions.addClass('hidden');
        var resetVal = $autoshipOptions.find(window.autoshipTemplateData.noBtn);
        $( resetVal ).prop("checked", true);

        selectHide( $thisProduct, $autoshipOptions );
        clearFrequency( $thisProduct, $autoshipOptions );
        $thisProduct.removeClass('autoship-active').addClass('autoship-not-active');

      }

      /*
       * Init Methods
       */

      // Populates the Autoship Schedule Drop down options.
      var selectPopulate = function( $thisProduct, scheduleOptions ){

        var $autoshipOptions = $thisProduct.find(window.autoshipTemplateData.optionsCls);
        var $selectFrequency = $autoshipOptions.find(window.autoshipTemplateData.frequencySelectCls);
        $selectFrequency.empty();

        $.each( scheduleOptions, function(e){
          var $option = $('<option></option>');
          $option.attr('value', JSON.stringify(this));
          $option.text(this.display_name);
          $selectFrequency.append($option);
        });

      }

      // Sets up the Price Data from the Autoship Data
      var initAutoshipPriceData = function( $thisProduct, autoshipData ){

        if ( autoshipData == undefined )
        return;

        // Grab the selector for the price field.
        $custompriceHTML = $thisProduct.find( autoshipData.discount_display_price_selector );

        // If the selector can't be found us the default for autoship.
        priceData[autoshipData.product_id] = [];
        if ( $custompriceHTML.length ) {
          priceData[autoshipData.product_id].original_price = $custompriceHTML[0].innerHTML;
          priceData[autoshipData.product_id].original_price_selector = autoshipData.discount_display_price_selector;
        } else {
          priceData[autoshipData.product_id].original_price = '';
          priceData[autoshipData.product_id].original_price_selector = autoshipData.original_display_price_selector;
        }

        priceData[autoshipData.product_id].autoship_price = autoshipData.discount_display_price;
        priceData[autoshipData.product_id].customized_price_enabled = autoshipData.discount_display_price_enable;

      }

      // Main Setup function
      var initAutoshipData = function( target, autoshipData ){

        var $thisProduct              = findProduct( target );
        var $autoshipOptions          = $thisProduct.find(window.autoshipTemplateData.optionsCls);
        var $percentDiscount          = $autoshipOptions.find(window.autoshipTemplateData.discountPriceCls);
        var $discountedPrice          = $autoshipOptions.find(window.autoshipTemplateData.checkoutPriceCls);
        var $customPercentDiscountStr = $autoshipOptions.find(window.autoshipTemplateData.discountStringCls);
        var productId                 = retrieveProductId( $thisProduct );

        $percentDiscount.html('');
        $discountedPrice.html('');

        // Check if autoship is enabled
        if ( 'no' === autoshipData.schedule_options_enabled || 'yes' === autoshipData.dissable_schedule_order_options ){
          clearAllAutoship( $thisProduct, $autoshipOptions );
          return;
        }

        $autoshipOptions.addClass('loading');

        $percentDiscount.html(autoshipData.percent_discount_html);
        $discountedPrice.html(autoshipData.discounted_price_html);
        $customPercentDiscountStr.html(autoshipData.custom_percent_discount_str);

        // Setup the Price Data
        initAutoshipPriceData( $thisProduct, autoshipData );

        // Populate the Autoship Schedule Drop
        selectPopulate( $thisProduct, autoshipData.frequency_options );

        // If Autoship is selected display the drop down and set the submission values.
        var $autoshipYes = $autoshipOptions.find(window.autoshipTemplateData.yesBtn);
        if ($autoshipYes.length > 0 && $autoshipYes[0].checked) {

          selectDisplay( $thisProduct, $autoshipOptions );
          selectFrequency( $thisProduct, $autoshipOptions );

        }

        // Toggle the labels
        toggleAutoshipLabels( $autoshipOptions, autoshipData.show_discount_str );

        $autoshipOptions.removeClass('hidden').removeClass('loading');

      }

      /*
       * Variation Specific Handlers
       */

      // Sets up the display and options for the Selected Variation
      var triggerVariation = function( variationForm, variationId ){

        if( ( '' == variationId ) || ( variationId == 0 ) )
        return;

        var $autoshipOptions = $( variationForm ).find(window.autoshipTemplateData.optionsCls);
        var autoshipDataValues = autoshipData( variationId, variationForm );

        if ( autoshipDataValues ){

          initAutoshipData( variationForm, autoshipDataValues );
          setVariationId( variationForm, variationId );

          // Retrigger the click on the yes to refresh html prices etc.
          var $autoshipYesRadio = $autoshipOptions.find( window.autoshipTemplateData.yesBtn );

          // Tag the Event Triggers for Future Prevention
          $autoshipYesRadio.addClass('active');

          if ( 'yes' == autoshipDataValues.default_autoship_option )
          $autoshipYesRadio.prop('checked', true);

          if ( $autoshipYesRadio.is(':checked') )
          $autoshipYesRadio.trigger('click');

        } else {
          setVariationId( variationForm, 0 );
          $autoshipOptions.addClass('hidden');
        }

      }

      // Setup Event Handlers
      var initEventHandlers = function( $autoshipOptions ){

        var selectToggle = function(e){

          var $thisProduct              = findProduct( e.target );
          var $autoshipOptions          = $thisProduct.find(window.autoshipTemplateData.optionsCls);
          selectFrequency( $thisProduct, $autoshipOptions );
          $(document).trigger( 'frequency_select_change', [ $thisProduct, $autoshipOptions ] );

        }

        var selectDisplayChange = function(e){

          var $thisProduct              = findProduct( e.target ),
              $autoshipOptions          = $thisProduct.find(window.autoshipTemplateData.optionsCls),
              removeClass               = e.data.autoship ? 'autoship-not-active' : 'autoship-active',
              addClass                  = e.data.autoship ? 'autoship-active' : 'autoship-not-active';

              $thisProduct.removeClass( removeClass ).addClass( addClass );

              if ( e.data.autoship ){ selectDisplay( $thisProduct, $autoshipOptions ); }
              else { selectHide( $thisProduct, $autoshipOptions ); }

              togglePriceDisplay( $thisProduct, !e.data.autoship );
              $(document).trigger( 'autoship_option_change', [ $( this ), $thisProduct, $autoshipOptions ] );

        }

        $autoshipOptions.find(window.autoshipTemplateData.frequencySelectCls).on( 'change' , selectToggle );
        $autoshipOptions.find(window.autoshipTemplateData.yesBtn).on( 'click' , { autoship: true },selectDisplayChange );
        $autoshipOptions.find(window.autoshipTemplateData.noBtn).on( 'click' , { autoship: false },selectDisplayChange );

        $('body').on( 'reset_data', window.autoshipTemplateData.variationFormCls, function( e ){
          setVariationId( e.target, 0 );
          $(this).find(window.autoshipTemplateData.optionsCls).addClass('hidden');
        });
        $('body').on( 'found_variation', window.autoshipTemplateData.variationFormCls, function ( e, variation ){
          triggerVariation( e.target, variation.variation_id );
        });

      }

      // Sets up the values for Autoship Defaulted setups
      var defaultSelected = function(){

        var variantionId = getVariationId( $thisProduct[0] );

        if ( variantionId ){
          setVariationId( $thisProduct[0], variantionId );
          triggerVariation( $thisProduct[0], variantionId );
        }

      }();

      /*
       *  Process Page Options & View
       */

      if ( $autoshipOptions.hasClass('is-init') )
      return;

      $autoshipOptions.addClass('is-init');

      // Setup and Attach all handlers
      initEventHandlers( $autoshipOptions );

      if ( isSimpleProduct( $thisProduct ) ){

        var productId = retrieveProductId( $thisProduct );

        if ( productId != undefined )
        var autoshipDatavalues = autoshipData( productId, $thisProduct );

        if ( !autoshipDatavalues )
        return;

        initAutoshipPriceData( $thisProduct, autoshipDatavalues );

      }

      // Tag the Event Triggers for Future Prevention
      $autoshipYesRadio.addClass('active');
      $autoshipNoRadio.addClass('active');

      // Now check for the default and trigger click..
      if ( $autoshipYesRadio.is(':checked') ){
        $autoshipYesRadio.trigger('click');
      } else {
        $autoshipNoRadio.trigger('click');
      }

    })

  };

  $('body').on( 'updated_cart_totals', function(){
    init_autoship_schedule_options($);
  });

  // Catch Clicks on Dynamically Loaded Autoship Options ( i.e. QuickViews )
  $(document).on( 'click' , window.autoshipTemplateData.yesBtn + ':not(.active)', function(e){
    $(document).trigger( 'refresh_autoship_data', $ );});
  $(document).on( 'click' , window.autoshipTemplateData.noBtn + ':not(.active)', function(e){
    $(document).trigger( 'refresh_autoship_data', $ );});
  $(document).on( 'found_variation', window.autoshipTemplateData.variationFormCls, function ( e ){
    $(document).trigger( 'refresh_autoship_data', $ );});

  // Catch Custom Trigger
  $( document ).on( 'refresh_autoship_data', function( event, $ ){
    init_autoship_schedule_options($);
  } ).trigger( 'refresh_autoship_data', $ );

  // Modal Info Dialog
  function autoship_modal_toggle( target_modal ){

    var targetModal = $( target_modal );
    targetModal.toggleClass('open');
    $( 'body' ).toggleClass('autoship-modal-open');

  }

  $(document).on('click', ".autoship-modal.customer-modal", function( e ){

    if( !$(event.target).closest('.autoship-modal.customer-modal .autoship-modal-content').length && !$(event.target).is('.autoship-modal.customer-modal .autoship-modal-content')) {
      $(this).removeClass('open');
      $( 'body' ).removeClass('autoship-modal-open');
   }

  });

  $(document).on('click', ".autoship-modal-trigger", function( e ){
    e.preventDefault();
    var modal = $(this).attr('data-modal-toggle');
    autoship_modal_toggle ( modal );
  });

  $(document).on('click', ".autoship-modal .close, .autoship-modal .cancel", function( e ) {
    e.preventDefault();
    $(this).closest('.autoship-modal').removeClass('open');
    $( 'body' ).removeClass('autoship-modal-open');
  });

  var browserWidth = $(window).width();

  // Prevent Any Clicks on the Tooltip
  $(document).on('click', ".autoship-tooltip-trigger", function( e ){
    e.preventDefault();

    // If Mobile bypass tooltip and use modal
    if ( browserWidth <	AUTOSHIP_DIALOG_TOOLTIP_MIN_WIDTH ){
      var modal = $(this).attr('data-modal-toggle');
      autoship_modal_toggle ( modal );
    }

  });

  // Only load tooltip feaure if being used.
  if ( 'tooltip' == AUTOSHIP_DIALOG_TYPE && browserWidth > AUTOSHIP_DIALOG_TOOLTIP_MIN_WIDTH ){

    // Populate Tooltip Content
    $(document).on('mouseenter', '.autoship-tooltip-trigger', function( event ) {

      var modal = $(this).attr('data-modal-toggle');
      var $modalContent = $( modal ).find( '.autoship-modal-content' ).clone();
      $('#tiptip_content').addClass('autoship-toolip-content').html( $modalContent );

    }).on('mouseleave', '.autoship-tooltip-trigger', function( event ) {

      $('#tiptip_content').removeClass('autoship-toolip-content').html();

    });

    // TipTip Options to Use
    var tipTipOptions = {
      'maxWidth': AUTOSHIP_DIALOG_SIZES[AUTOSHIP_DIALOG_SIZE],
      'attribute': 'data-tip',
      'fadeIn': 50,
      'fadeOut': 50,
      'delay': 200
    };

    // Initialize any TipTips Not already Set
    function tipTipInit(){

      var $tipTips = $('body .autoship-tooltip-trigger:not(.tip-init)');
      $tipTips.each( function( index, el) {

        // Initialize for tipTip & tag
        $( this ).addClass('tip-init').tipTip( tipTipOptions );

      });

    };

    // Init any TipTips on dynamic content
    $( document ).ajaxComplete(function( event, xhr, settings ) {
      tipTipInit();
    });

    // Init any TipTips
    tipTipInit();

  }

});
