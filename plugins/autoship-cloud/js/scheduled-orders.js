
/**
 * Scheduled Order Data Container
 */
if ( typeof window.autoshipSchedulerData === 'undefined' )
window.autoshipSchedulerData = {

  // Activate & Deactivate Handler
  activationHandler : undefined, // Pluggable Function

  // Main Add Item Handler
  addItemHandler : undefined, // Pluggable Function

  // Main Delete Item Handler ( Ajax - not currently used )
  deleteItemHandler : undefined, // Pluggable Function

  // Cancel Action Handler
  cancelAction : undefined, // Pluggable Function

  // Add Item Success & Failure functions
  addItemSuccessCallback : undefined, // Pluggable Function
  addItemFailureCallback : undefined, // Pluggable Function

  // Currently Not Used
  removeItemSuccessCallback : undefined, // Pluggable Function
  removeItemFailureCallback : undefined, // Pluggable Function

  // Shipping Address Country Change Handler
  refreshCountryShipping : undefined, // Pluggable Function

  // Shipping Label Change Handler
  refreshShippingLabels : undefined, // Pluggable Function

  // Shipping Form
  shippingAddressFormClass : '.autoship-edit-scheduled-order-shipping-address-form',

  // Shipping Country
  shippingCountryFieldIdentifier : '#country',

  // Shipping State
  shippingStateFieldIdentifier : '#state',

  // Shipping field map
  shippingFieldsPrefix : 'shipping_',
  shippingFieldsLabelRequired : '<abbr class="required" title="required">*</abbr>',

};

jQuery(function ($) {

  var autoship_schdule_added_items = {};

  /**
   * Activate / Deactivate Action Handler.
   */
  var activationHandler = function(e){

    // Check for a custom override function
    if ( typeof window.autoshipSchedulerData.activationHandler !== 'undefined' ){
      var func = window.autoshipSchedulerData.activationHandler;
      return window[func](e);
    }

    e.preventDefault();

    var target = $(this).attr('data-target');
    var toggle_text = $(this).attr('data-toggle-text');
    $(this).attr('data-toggle-text', $(this).html() ).html(toggle_text);
    $( target ).toggleClass('activated');

  }

	/**
	 * Check if a node is blocked for processing.
	 *
	 * @param {JQuery Object} $node
	 * @return {bool} True if the DOM Element is UI Blocked, false if not.
	 */
	var is_locked = function( $node ) {
		return $node.is( '.processing' ) || $node.parents( '.processing' ).length;
	};

	/**
	 * Block a node visually for processing.
	 *
	 * @param {JQuery Object} $node
	 */
	var lock = function( $node ) {
		if ( ! is_locked( $node ) ) {
			$node.addClass( 'processing' );
		}
	};

	/**
	 * Unblock a node after processing is complete.
	 *
	 * @param {JQuery Object} $node
	 */
	var unlock = function( $node ) {
		$node.removeClass( 'processing' );
	};

	/**
	 * Unblock a node after processing is complete.
	 */
  var hide_totals = function(){

    var target_totals = $(".autoship-order-totals");

    if ( target_totals.length )
    target_totals.find('td').fadeOut();
  }

	/**
	 * Unblock a node after processing is complete.
	 */
  var show_totals = function(){

    var target_totals = $(".autoship-order-totals");

    if ( target_totals.length )
    target_totals.find('td').opacity(0);

  }

  /**
   * Check if the form is locked
   */
  var chck_locked = function(e){
    var target_form = $( this ).closest('form');
    if ( is_locked( target_form ) ){
      e.preventDefault();
      return false;
    }
  }

  /**
   * Callback for when an item is added successfully
   */
  var addItemSuccess = function( itemAction ){

    // Check for a custom override function
    if ( typeof window.autoshipSchedulerData.addItemSuccessCallback !== 'undefined' ){
      var func = window.autoshipSchedulerData.addItemSuccessCallback;
      return window[func]( itemAction );
    }

    itemAction.target_placeholder.fadeOut('400', function() {
      $(itemAction.response.content).insertBefore('.scheduled-order-add-ons');
      itemAction.target_option.prop("selected", false).attr('disabled','disabled');
      $('.autoship-msg').remove();
      $(itemAction.response.notice_target).prepend( $( itemAction.response.notice )).fadeIn();
      hide_totals();
      itemAction.target_form.removeClass('empty-order');
      unlock( $(itemAction.target_form) );
    });

    return;

  }

  /**
   * Callback for when an item is added unsuccessfully
   */
  var addItemFailure = function( itemAction ){

    // Check for a custom override function
    if ( typeof window.autoshipSchedulerData.addItemFailureCallback !== 'undefined' ){
      var func = window.autoshipSchedulerData.addItemFailureCallback;
      return window[func]( itemAction );
    }

    itemAction.target_placeholder.fadeOut('400', function() {
      $(itemAction.response.content).insertBefore('.scheduled-order-add-ons');
      itemAction.target_option.prop("selected", false).attr('disabled','disabled');
      $('.autoship-msg').remove();
      $(itemAction.response.notice_target).prepend( $( itemAction.response.notice )).fadeIn();
      hide_totals();
    });

    return;

  }

  /**
   * Main Handler for Adding an Item
   */
  var addItemHandler = function(e){

    // Check for a custom override function
    if ( typeof window.autoshipSchedulerData.addItemHandler !== 'undefined' ){
      var func = window.autoshipSchedulerData.addItemHandler;
      return window[func](e);
    }

    e.preventDefault();

    var itemAction = {};
        itemAction.target              = $('#autoship_add_scheduled_order_item');
        itemAction.target_option       = $("#autoship_add_scheduled_order_item option:selected");
        itemAction.target_placeholder  = $(".scheduled-order-add-ons-placeholder");
        itemAction.target_val          = itemAction.target_option.val();
        itemAction.target_form         = $( this ).closest('form');

    if ( is_locked( itemAction.target_form ) )
    return false;

    if ( typeof itemAction.target_val !== undefined && itemAction.target_val.length ){

      // Clear any lingering WC Notices.
			$( '.woocommerce-error, .woocommerce-message, .woocommerce-info' ).remove();

      // Lock the form to prevent other form actions.
      lock( itemAction.target_form );

      // Bring in the item placeholder
      itemAction.target_placeholder.fadeIn();

      $.ajax({
        type: 'POST',
        dataType: 'json',
        url: AUTOSHIP_AJAX_URL,
        data: {
            action: "autoship_get_add_item_html",
            product_id: itemAction.target_val,
            metadata: autoshipSchedulerData
        },
        success: function(response) {

          itemAction.response = response;
          addItemSuccess( itemAction );

        },
        failure: function(response) {

          itemAction.response = response;
          addItemFailure( itemAction );

        }
      });
    }

  };

  /**
   * Main Handler for Deleting an Item
   */
  var deleteItemHandler = function(e){

    // Check for a custom override function
    if ( typeof window.autoshipSchedulerData.deleteItemHandler !== 'undefined' ){
      var func = window.autoshipSchedulerData.deleteItemHandler;
      return window[func](e);
    }

    e.preventDefault();

    var itemAction = {};
        itemAction.target         = $(this).attr('data-autoship-order'),
        itemAction.target_action  = $(this).attr('data-autoship-action'),
        itemAction.target_view    = $(this).attr('data-autoship-view');
        itemAction.target_form         = $( this ).closest('form');

    if ( is_locked( itemAction.target_form ) )
    return false;

    if ( typeof itemAction.target !== undefined && itemAction.target ){

      $.ajax({
        type: 'POST',
        dataType: 'json',
        url: AUTOSHIP_AJAX_URL,
        data: {
            action: "autoship_" + itemAction.target_action + "_ajax_action",
            order_id: itemAction.target,
            order_view: itemAction.target_view
        },
        success: function(response) {
          $( response.notice ).insertAfter( $( response.notice_target )).fadeIn();

        },
        failure: function(response) {
          $( response.notice ).insertAfter( $( response.notice_target )).fadeIn();
        }
      });

    }

  }

  /**
   * Main Handler for Removing Newly Added Items
   */
  var removeNewItemHandler = function(e){

    // Check for a custom override function
    if ( typeof window.autoshipSchedulerData.removeNewItemHandler !== 'undefined' ){
      var func = window.autoshipSchedulerData.removeNewItemHandler;
      return window[func](e);
    }

    e.preventDefault();

    var target = $(this).attr('data-scheduled-order-item-id'),
        select = $('#autoship_add_scheduled_order_item'),
        option = $('#autoship_add_scheduled_order_item option[value="' + target.replace( 'item-', '' ) * 1 + '"]');
        $("#" + target ).remove();
        option.removeAttr('disabled');

  };

  /**
   * Main Handler for Canceling an Action
   */
  var cancelAction = function(e){

    // Check for a custom override function
    if ( typeof window.autoshipSchedulerData.cancelAction !== 'undefined' ){
      var func = window.autoshipSchedulerData.cancelAction;
      return window[func](e);
    }

    e.preventDefault();
    var wrapper = $(this).closest( $(this).attr('data-notice-wrapper') );

    if ( wrapper )
    wrapper.fadeOut('400', function() { $(this).remove(); });

  }

  /**
   * Callback for when the country drop down is changed
   */
  var refreshCountryShipping = function( e ){

    // Check for a custom override function
    if ( typeof window.autoshipSchedulerData.refreshCountryShipping !== 'undefined' ){
      var func = window.autoshipSchedulerData.refreshCountryShipping;
      return window[func](e);
    }

    var states            = e.data.states,
        locale            = e.data.locale,
        locale_fields     = e.data.locale_fields;
        select_state_text = e.data.select_state_text;

    var $countrySelect    = $( e.target ),
        country           = $countrySelect.val(),
        $form             = $countrySelect.closest( 'form' ),

        // Get the current State Fields value and attributes
  			$statefield       = $form.find( window.autoshipSchedulerData.shippingStateFieldIdentifier ),
  			$statefieldparent = $statefield.closest( '.form-row' ),
  			iname             = $statefield.attr( 'name' ),
  			iid               = $statefield.attr( 'id' ),
  			iclasses          = $statefield.attr( 'data-input-class' ) || '',
  			ivalue            = $statefield.val(),
  			iplaceholder      = $statefield.attr( 'placeholder' ) || $statefield.attr( 'data-placeholder' ) || '',
  			$newstatefield;

    var thislocale = locale['default'];

    // Check if the country is in our country list
    // if not default it
		if ( typeof locale[ country ] !== 'undefined' )
		thislocale = locale[ country ];

    // Check if country is in our states obj
		if ( states[ country ] ) {

      // Check if the country is in our state obj but an empty ojbect
      if ( $.isEmptyObject( states[ country ] ) ) {

        // Create a new hidden State Field for Countries with invalid objects
				$newstatefield = $( '<input type="hidden" />' )
					.prop( 'id', iid )
					.prop( 'name', iname )
					.prop( 'placeholder', iplaceholder )
					.attr( 'data-input-classes', iclasses )
					.addClass( 'hidden ' + iclasses );
				$statefieldparent.hide();
				$statefield.replaceWith( $newstatefield );

      // Else our counrty has states
			} else {

        // Get the country's states
        var avail_states   = states[ country ],
					  $defaultOption = $( '<option value=""></option>' ).text( select_state_text );

        // If there isn't a placeholder use the default
				if ( ! iplaceholder )
				iplaceholder = select_state_text;

        // If the state field is currently an input we need
        // to update it to a select field
				if ( $statefield.is( 'input' ) ) {

          $newstatefield = $( '<select></select>' )
						.prop( 'id', iid )
						.prop( 'name', iname )
						.data( 'placeholder', iplaceholder )
						.attr( 'data-input-classes', iclasses )
						.addClass( 'state_select ' + iclasses );

					$statefield.replaceWith( $newstatefield );
					$statefield = $form.find( window.autoshipSchedulerData.shippingStateFieldIdentifier );

        }

        // Add the default select option
				$statefield.empty().append( $defaultOption );

        // Add each of the states as an option
				$.each( avail_states, function( index ) {
					var $option = $( '<option></option>' )
						.prop( 'value', index )
						.text( avail_states[ index ] );
					$statefield.append( $option );
				} );

        // Show the state select field
				$statefieldparent.show();

        // Select the option and trigger a change
				$statefield.val( ivalue ).trigger( 'change' );

			}

    // Else the country isn't in our state obj
    // so let's convert it to a text field
		} else {

			if ( $statefield.is( 'select, input[type="hidden"]' ) ) {

      	$newstatefield = $( '<input type="text" />' )
					.prop( 'id', iid )
					.prop( 'name', iname )
					.prop('placeholder', iplaceholder)
					.attr('data-input-classes', iclasses )
					.addClass( 'input-text  ' + iclasses );

				$statefieldparent.show();
				$statefield.replaceWith( $newstatefield );

      }

		}

    // itterate through the shipping fields and adjust labels
		$.each( locale_fields, function( key, name ) {

      // We adjust the field name to match our form i.e. first_name ( ours ) vs shipping_first_name ( wc )
      var adjname = name.replace( window.autoshipSchedulerData.shippingFieldsPrefix , '' );

      // Grab the current field on the form & default any missing adjustments
      var $curField       = shippingAddressForm.find( adjname ),
          $label          = $curField.find( 'label' ),
          fieldLocale     = $.extend( true, {}, locale['default'][ key ], thislocale[ key ] );

      // Check for a label adjustment
      refreshShippingLabels( $curField, fieldLocale.label, typeof fieldLocale.required === 'undefined' ? false : fieldLocale.required );

    });

  }

  /**
   * Callback for refreshing the shipping address labels
   */
  var refreshShippingLabels = function( $curField, label, required ){

    if ( typeof required === 'undefined' )
    required = false;

    // Check for a custom override function
    if ( typeof window.autoshipSchedulerData.refreshShippingLabels !== 'undefined' ){
      var func = window.autoshipSchedulerData.refreshShippingLabels;
      return window[func]( $curField, label, required = false );
    }

    // get the current fields label
    if ( typeof $curField.find( 'label' ) !== 'undefined' && typeof label !== 'undefined' )
    $curField.find( 'label' ).html( required ? label + window.autoshipSchedulerData.shippingFieldsLabelRequired : label );

  }

  // wc_country_select_params is required to continue, ensure the object exists
	if ( typeof wc_country_select_params !== 'undefined' &&
       typeof wc_address_i18n_params !== 'undefined' ) {

    // Find the Shipping Country drop down to monitor
    var shippingAddressForm = $( window.autoshipSchedulerData.shippingAddressFormClass ),
        shippingCountry     = shippingAddressForm.find( window.autoshipSchedulerData.shippingCountryFieldIdentifier );

    // Remove the WC Classes to prevent the WC Handler from firing
    shippingCountry.removeClass('country_to_state');

    /**
    * When the Country drop down is changed update the shipping fields
    */
    shippingCountry.on( 'change refresh',
    {
      states            : JSON.parse( wc_country_select_params.countries.replace( /&quot;/g, '"' ) ),
      locale            : JSON.parse( wc_address_i18n_params.locale.replace( /&quot;/g, '"' ) ),
      locale_fields     : JSON.parse( wc_address_i18n_params.locale_fields ),
      select_state_text : wc_country_select_params.i18n_select_state_text
    }, refreshCountryShipping );

	}

  // Init all dismissible features
  var dismissibles = $(document).find('.autoship-tips' );

  // itterate through the dismissibles and show/hide accordingly
  $.each( dismissibles, function( key, name ) {

    var cookieName = $( this ).attr('data-feature-id');

  	if ( typeof Cookies !== 'undefined' && 'hidden' === Cookies.get( cookieName ) ) {
  		$( this ).hide();
  	} else {
  		$( this ).show();
  	}

  });

  var dismissibleHandler = function(e){

    if ( typeof Cookies === 'undefined' )
    return;

    var cookieName = $( this ).attr('data-feature-id');
		Cookies.set( cookieName, 'hidden', { path: '/' } );
		$( this ).hide();

  }

  $(document).on( 'click', dismissibles, dismissibleHandler );

  // Init all Tooltips
  $(document).find('.autoship-tips' ).tipTip( {
		'attribute': 'data-tip',
		'fadeIn': 50,
		'fadeOut': 50,
		'delay': 200
	} );

  // Hooked Event Handlers
  $(document).on( 'click', '.activate-action, .deactivate-action', activationHandler );
  $(document).on( 'click', 'form a,form button', chck_locked );
  $(document).on( 'click', '.add-item-action', addItemHandler );
  $(document).on( 'click', '.remove-new-item-action', removeNewItemHandler );
  $(document).on( 'click', 'a[data-autoship-action="Deleted"]', deleteItemHandler );
  $(document).on( 'click', 'a.cancel-action', cancelAction );

});

/**
* Adjusts the Size of the Scheduled Orders iframe
*/
document.addEventListener("DOMContentLoaded", function () {
  // Check if the iframe exists and only if it does run the interval
  var iframes = document.getElementsByClassName("autoship-scheduled-orders-iframe");
  if ( iframes !== undefined && iframes.length )
  setInterval(function () {
      if (iframes.length > 0) {
          Array.from(iframes).forEach(function (scheduledOrder) {
              var bodyHeight = '';
              try {
                  bodyHeight = Math.max(500, scheduledOrder.contentWindow.document.body.scrollHeight) + 'px';
              } catch (e) {
                  bodyHeight = '100vh';
              }
              if (bodyHeight != scheduledOrder.style.height) {
                  scheduledOrder.style.height = bodyHeight;
              }
          });
      }
  }, 500);
});

/**
* Custom Implementation of the from function for support in Edge/IE
*/
if (!Array.from) {
  Array.from = (function () {
    var toStr = Object.prototype.toString;
    var isCallable = function (fn) {
      return typeof fn === 'function' || toStr.call(fn) === '[object Function]';
    };
    var toInteger = function (value) {
      var number = Number(value);
      if (isNaN(number)) { return 0; }
      if (number === 0 || !isFinite(number)) { return number; }
      return (number > 0 ? 1 : -1) * Math.floor(Math.abs(number));
    };
    var maxSafeInteger = Math.pow(2, 53) - 1;
    var toLength = function (value) {
      var len = toInteger(value);
      return Math.min(Math.max(len, 0), maxSafeInteger);
    };

    // The length property of the from method is 1.
    return function from(arrayLike/*, mapFn, thisArg */) {
      // 1. Let C be the this value.
      var C = this;

      // 2. Let items be ToObject(arrayLike).
      var items = Object(arrayLike);

      // 3. ReturnIfAbrupt(items).
      if (arrayLike == null) {
        throw new TypeError("Array.from requires an array-like object - not null or undefined");
      }

      // 4. If mapfn is undefined, then let mapping be false.
      var mapFn = arguments.length > 1 ? arguments[1] : void undefined;
      var T;
      if (typeof mapFn !== 'undefined') {
        // 5. else
        // 5. a If IsCallable(mapfn) is false, throw a TypeError exception.
        if (!isCallable(mapFn)) {
          throw new TypeError('Array.from: when provided, the second argument must be a function');
        }

        // 5. b. If thisArg was supplied, let T be thisArg; else let T be undefined.
        if (arguments.length > 2) {
          T = arguments[2];
        }
      }

      // 10. Let lenValue be Get(items, "length").
      // 11. Let len be ToLength(lenValue).
      var len = toLength(items.length);

      // 13. If IsConstructor(C) is true, then
      // 13. a. Let A be the result of calling the [[Construct]] internal method of C with an argument list containing the single item len.
      // 14. a. Else, Let A be ArrayCreate(len).
      var A = isCallable(C) ? Object(new C(len)) : new Array(len);

      // 16. Let k be 0.
      var k = 0;
      // 17. Repeat, while k < len… (also steps a - h)
      var kValue;
      while (k < len) {
        kValue = items[k];
        if (mapFn) {
          A[k] = typeof T === 'undefined' ? mapFn(kValue, k) : mapFn.call(T, kValue, k);
        } else {
          A[k] = kValue;
        }
        k += 1;
      }
      // 18. Let putStatus be Put(A, "length", len, true).
      A.length = len;
      // 20. Return A.
      return A;
    };
  }());
}
