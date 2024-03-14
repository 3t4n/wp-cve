function wpppfm_initializeMerchantPromotionsFeedForm( productPromotionsFileName ) {
	// clear the previous form
	jQuery( '#wppfm-main-input-map' ).empty();

	wppfm_showWorkingSpinner();

	// now add the correct elements for a merchant promotions feed
	window.location = encodeURI( window.location.href + '&feed-type=google-merchant-promotions-feed&feed-name=' + productPromotionsFileName );
}

/**
 * Gets the data from the feed data holder element that is stored in the feeds html code and uses it to make a new _promotionsFeedHolder that
 * will be used to fill the Merchant Promotions Feed form.
 */
function wpppfm_initiatePromotionsFeedHolder( feedName ) {
	var feedData = jQuery("#wppfm-feed-editor-page-data-storage").data("wppfmFeedData");

	if ( ! feedData ) { return; }

	feedData['feed_file_name'] = '' === feedData['feed_file_name'] ? feedName : feedData['feed_file_name'];
	feedData['feed_type_id'] = '3' // Merchant Promotions Feed.
	feedData['status_id'] = '2'; // Status on hold.
	feedData['url'] = jQuery( '#wppfm-feed-editor-page-data-storage' ).data( 'wppfmFeedUrl' ) + '/wppfm-feeds/' + feedData['feed_file_name'] + '.xml';

	// make a new _promotionsFeedHolder
	if (feedData) {
		_promotionsFeedHolder = new Feed(
			feedData['feed_id'],
			feedData['feed_file_name'],
			0,
			0,
			1,
			'',
			[],
			feedData['url'],
			'1',
			'US', // Dummy data.
			'',
			'',
			feedData['feed_title'],
			feedData['feed_description'],
			'',
			[],
			feedData['status_id'],
			'3',
		);

		var promotions = wpppfm_constructPromotionFromFeedData( feedData['attribute_data'] );

		promotions.forEach( function( promotion ) {
			_promotionsFeedHolder.addPromotion(promotion);
		});

		_promotionsFeedHolder['promotion_destination_options'] = feedData['promotion_destination_options'];
		_promotionsFeedHolder['promotion_filter_options'] = feedData['promotion_filter_options'];

		// update the _feedHolder variable in the wppfm_feed-form.js file
		wppfm_constructNewSpecialFeed( _promotionsFeedHolder );

		console.log(_promotionsFeedHolder);
	}
}

function wpppfm_fillPromotionFields() {
	var isNew =_feedHolder[ 'feedId' ] === -1;

	if ( isNew ) { return; }

	jQuery( '#wppfm-feed-file-name' ).val( _promotionsFeedHolder[ 'title' ] );
	wppfm_setMerchantSelector( isNew, _feedHolder[ 'channel' ] );
	wppfm_setGoogleFeedTypeSelector( isNew, _feedHolder[ 'feedType' ]);

	var promotions = wpppfm_getPromotionData( _promotionsFeedHolder[ 'promotions' ] );

	// Loop through the promotions and fill the promotion fields
	for ( var promotionId = 0; promotionId < promotions.length; promotionId++ ) {
		// If Coupon Code Required is set to 'generic_code' then show the generic redemption code input field
		if( 'generic_code' === _promotionsFeedHolder.getPromotionCouponCodeRequiredValue( promotionId ) ) {
			var genericRedemption = _promotionsFeedHolder.promotions[promotionId].find( obj => obj.meta_key === 'generic_redemption_code' );
			if ( genericRedemption ) {
				jQuery(`#wpppfm-generic-redemption-code-input-field-${promotionId}` ).val(genericRedemption.meta_value);
			}

			jQuery('#wpppfm-generic-redemption-code-input-row').show();
		}

		// If the Products Eligible for Promotion is set to 'specific_products' then show the Product Filter Selector
		if ( 'specific_products' === _promotionsFeedHolder.getPromotionProductsEligibleForPromotionValue( promotionId ) ) {
			jQuery( `#wppfm-product-filter-map-${promotionId}` ).show();
		}

		// If all required fields are filled in then show the Promotion Details Selector
		if ( wpppfm_requiredDataIsFilledIn( promotionId ) ) {
			jQuery( `#wpppfm-product-details-map-${promotionId}` ).show();
			wppfm_enableFeedActionButtons( 'google-merchant-promotions-feed' );
		}

		wpppfm_fillInTheFormFields( promotionId );
	}
}

/**
 * Activates the date picker for the promotion effective start and end dates
 */
function wpppfm_initiateDatePickers( promotionId ) {
	// Reference: https://www.jqueryscript.net/time-clock/jQuery-Date-Time-Picke-Plugin-Simple-Datetimepicker.html
	// noinspection JSUnresolvedReference
	var dtPickerSettings = {
		'dateFormat': 'DD-MM-YYYY hh:mm',
		'firstDayOfWeek': '1',
		'closeOnSelected': true,
		'autodateOnStart': false,
		'locale': my_script_vars.language.slice(0, 2),
	}

	jQuery( `#wpppfm-promotion-effective-start-date-input-field-${promotionId}` ).appendDtpicker( dtPickerSettings );
	jQuery( `#wpppfm-promotion-effective-end-date-input-field-${promotionId}` ).appendDtpicker( dtPickerSettings );
	jQuery( `#wpppfm-promotion-display-start-date-input-field-${promotionId}` ).appendDtpicker( dtPickerSettings );
	jQuery( `#wpppfm-promotion-display-end-date-input-field-${promotionId}` ).appendDtpicker( dtPickerSettings );
}

function wpppfm_promotionsInputChanged( key, promotionNr ) {
	if ( 'input not set' === wpppfm_storePresetInputsInPromotions( key, promotionNr ) ) { // if the set input is one of the preset inputs, they are now already set by the wpppfm_storePresetInputsInPromotions() function
		var elementId = key.replaceAll('_', '-');
		var value = jQuery(`#wpppfm-${elementId}-input-field-${promotionNr}`).val();
		_promotionsFeedHolder.addPromotionElement(promotionNr, key, value);
	}

	if ('generic_code' === jQuery(`#wpppfm-offer-type-input-field-${promotionNr}`).val()) {
		jQuery(`#wpppfm-generic-redemption-code-input-row-${promotionNr}`).show();
	} else {
		jQuery(`#wpppfm-generic-redemption-code-input-row-${promotionNr}`).hide();
	}

	if ('specific_products' === jQuery(`#wpppfm-product-applicability-input-field-${promotionNr}`).val()) {
		jQuery(`#wppfm-product-filter-map-${promotionNr}`).show();
	} else {
		jQuery(`#wppfm-product-filter-map-${promotionNr}`).hide();
	}

	if (wpppfm_requiredDataIsFilledIn( promotionNr )) {
		jQuery(`#wpppfm-product-details-map-${promotionNr}`).show();
		// enable the Generate and Save buttons and the target country selection
		wppfm_enableFeedActionButtons( 'google-merchant-promotions-feed' );
	}
}

/**
 * Makes sure that the preset inputs are also stored, even if the user doesn't change them.
 *
 * @param key
 * @param promotionNr
 * @returns {string}
 */
function wpppfm_storePresetInputsInPromotions( key, promotionNr ) {
	var presetInputs = [
		'offer_type',
		'product_applicability',
		'redemption_channel',
		'promotion_destination'];

	presetInputs.forEach(function(input) {
		var elementId = input.replaceAll('_', '-');
		var value = jQuery(`#wpppfm-${elementId}-input-field-${promotionNr}`).val();
		_promotionsFeedHolder.addPromotionElement(promotionNr, input, value);
	});

	if (presetInputs.includes(key)) {
		return 'input set';
	} else {
		return 'input not set';
	}
}

function wpppfm_initiateSaveAndGeneratePromotionsFeed() {

	wppfm_showWorkingSpinner();
	disableFeedActionButtons( 'google-merchant-promotions-feed' );

	// save the feed data to the database
	wppfm_saveFeedToDb( _promotionsFeedHolder, function( dbResult ) {

		var newFeed = ! _promotionsFeedHolder[ 'feedId' ];

		wpppfm_handleSavePromotionsFeedToDbResult( dbResult, newFeed );

		// convert the data to xml and save the code to a feed file
		wppfm_updateFeedFile( _promotionsFeedHolder[ 'feedId' ], function( xmlResult ) {

			wppfm_handleUpdateFeedFileActionResult( xmlResult );
			wppfm_hideWorkingSpinner();
		} );
	} );
}

function wpppfm_handleSavePromotionsFeedToDbResult( dbResult, newFeed ) {

	// the wppfm_saveFeedToDb returns the entered feed id
	if ( 0 === dbResult || '0' === dbResult ) {
		wppfm_handleSaveFeedToDbFailedAction();
	} else {

		// insert the feed id in the _feed
		_promotionsFeedHolder[ 'feedId' ] = dbResult;

		if ( newFeed ) {
			// reset the url to implement the feed id so the user can reset the form if he wants
			wppfm_resetUrlForNewFeed( _promotionsFeedHolder[ 'feedId' ], 'google-merchant-promotions-feed' );
			wppfm_storeFeedUrlInSourceData( _promotionsFeedHolder[ 'url' ] );
		}
	}

}

/**
 * Fills the attribute fields with the values of the promotion object
 */
function wpppfm_fillInTheFormFields( promotionNr ) {
	// I need to make a clone of the promotion object because the trigger() function on row 251 will change the order of the attributes in the original object
	// Because of that reordering, some attributes will not be filled in the selector fields
	// Cloning the object first, will prevent that
	var promotionClone = JSON.parse(JSON.stringify(_promotionsFeedHolder.promotions[promotionNr]));

	promotionClone.forEach( function( promotionAttribute ) {
		if ( promotionAttribute.meta_key === 'genericRedemptionCode' ) { return; }

		// Generate the element id from the meta key
		var elementId = promotionAttribute.meta_key.replaceAll('_', '-');

		if ( promotionAttribute.meta_key === 'free_shipping' ||  promotionAttribute.meta_key === 'coupon_value_type' ) { // these are select fields
			jQuery(`#wpppfm-${elementId}-input-field-${promotionNr} option[value="${promotionAttribute.meta_value}"]`).prop('selected', true);
		} else {
			if ( promotionAttribute.meta_key === 'product_filter_selector_include' || promotionAttribute.meta_key === 'product_filter_selector_exclude' || promotionAttribute.meta_key === 'promotion_destination' ) { // these are multiselect fields
				jQuery( `#wpppfm-${elementId}-input-field-${promotionNr}` ).val( promotionAttribute.meta_value ).trigger( 'change');
			} else {
				jQuery(`#wpppfm-${elementId}-input-field-${promotionNr}`).	val(promotionAttribute.meta_value); // these are input fields
			}
		}
	} );
}

function wpppfm_constructPromotionFromFeedData( attributeData ) {
	if ( attributeData.length === 0 ) {
		return [[]]; // return an empty promotion
	}

	var promotions = [];
	var promotionsData = JSON.parse( attributeData[0].meta_value );
	promotionsData.forEach( function ( promotionData ) {
		var promotion = [];
		promotionData.forEach( function ( promotionElement ) {
			if ( 'promotion_effective_dates' === promotionElement.meta_key ) {
				var promotionDates = promotionElement.meta_value.split('/');
				promotion.push({meta_key: 'promotion_effective_start_date', meta_value: promotionDates[0]});
				promotion.push({meta_key: 'promotion_effective_end_date', meta_value: promotionDates[1]});
			} else {
				promotion.push(promotionElement);
			}
		});
		promotions.push(promotion);
	});

	return promotions;
}

/**
 * hook the document actions
 */
jQuery(function() {
	var feedName = wppfm_getUrlParameter( 'feed-name' );
	var feedTpe    = wppfm_getUrlParameter( 'feed-type' );

	if ( 'google-merchant-promotions-feed' !== feedTpe ) { return; }

	wppfm_showWorkingSpinner();

	// Set the _feedHolder
	wpppfm_initiatePromotionsFeedHolder( feedName );

	jQuery( '#wppfm-feed-types-list-row').show();
	jQuery( '.wppfm-top-buttons-wrapper' ).show();

	_feedHolder['promotions'].forEach(function(promotion, index) {
		wpppfm_addPromotionElement(index);
	})

	wpppfm_tunePromotionElements();

	wpppfm_fillPromotionFields();

	// If there are more than 2 promotions, show the center buttons
	if ( _feedHolder['promotions'].length > 2 ) {
		jQuery('.wppfm-center-buttons-wrapper').show();
	}

	jQuery( '.wppfm-bottom-buttons-wrapper' ).show();

	wppfm_hideWorkingSpinner()
} );
