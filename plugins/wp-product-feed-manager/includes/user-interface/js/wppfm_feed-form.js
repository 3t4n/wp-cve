/*global wppfm_feed_settings_form_vars */
// noinspection JSUnresolvedReference

var _mandatoryFields             = [];
var _highlyRecommendedFields     = [];
var _recommendedFields           = [];
var _undefinedRecommendedOutputs = [];
var _definedRecommendedOutputs   = [];
var _optionalFields              = [];
var _undefinedOptionalOutputs    = [];
var _definedOptionalOutputs      = [];
var _undefinedCustomOutputs      = [];
var _customFields                = [];
var _inputFields                 = [];
var _feedHolder                  = [];

function wppfm_initializeStandardProductFeedForm( feedFileName, feedType = 'product-feed' ) {
	// clear the previous form
	jQuery( '#wppfm-main-input-map' ).empty();

	wppfm_showWorkingSpinner();

	// now add the correct elements for a product review feed
	window.location = encodeURI( window.location.href + '&feed-type=' + feedType + '&feed-name=' + feedFileName );
}

/**
 * Gets triggered when one of the main inputs on the edit feed page has changed. This function starts a new feed when all
 * main inputs are given or updates the existing feed if required, based on the changed main input.
 *
 * @param {boolean}   categoryChanged     True if the Default Category input changed.
 * @param {boolean}   nameChanged         True if the Feed Name input changed.
 */
function wppfm_mainInputChanged( categoryChanged, nameChanged = false ) {
	var channel = jQuery( '#wppfm-merchants-selector' ).val();
	var feedId  = _feedHolder[ 'feedId' ];

	wppfm_reactOnChannelInputChanged( channel, feedId, categoryChanged, nameChanged );
}

/**
 * Makes sure the feed name does not contain any special characters or already exists.
 *
 * @param {string}          fileNameElement     The feed name.
 *
 * @return {void|boolean}   Returns false when the file name is not valid.
 */
function wppfm_validateFileName( fileNameElement ) {
	if ( ! wppfm_contains_special_characters( fileNameElement ) ) {
		var existingFileNames = jQuery( '#wppfm-feed-editor-page-data-storage' ).data( 'wppfmAllFeedNames' ).split(';;');

		// test if file name already exists
		if ( -1 === jQuery.inArray( fileNameElement, existingFileNames ) ) {
			_feedHolder[ 'title' ] = fileNameElement;
		} else {
			//noinspection JSUnresolvedVariable
			alert( wppfm_feed_settings_form_vars.feed_name_exists );
			return false;
		}
	} else {
		//noinspection JSUnresolvedVariable
		alert( wppfm_feed_settings_form_vars.prohibited_feed_name_characters );
		return false;
	}
}

/**
 * Validates the promotion id. It is not allowed to contain spaces or symbols. Max length is 50 characters.
 * @param promotionId
 */
function wppfm_validatePromotionId( promotionId ) {
	var re = '^[a-zA-Z0-9_]{0,50}$';
	if ( ! promotionId.match(re) ) {
		//noinspection JSUnresolvedVariable
		alert( wppfm_feed_settings_form_vars.promotion_id_invalid );
	}
}

/**
 * Validates the generic redemption code. Max length is 20 characters.
 * @param code
 */
function wppfm_validateGenericRedemptionCode( code ) {
	if ( code.length > 20 ) {
		//noinspection JSUnresolvedVariable
		alert( wppfm_feed_settings_form_vars.generic_redemption_code_invalid );
	}
}

/**
 * Validates the promotion title. Max length is 60 characters.
 * @param title
 */
function wppfm_validatePromotionTitle( title ) {
	if ( title.length > 60 ) {
		//noinspection JSUnresolvedVariable
		alert( wppfm_feed_settings_form_vars.promotion_title_invalid );
	}
}

function wppfm_freeCategoryChanged( type, id ) {
	if ( type === 'default' ) { // default category selection changed
		if ( id ) {
			if ( id > 0 ) {
				_feedHolder.setCustomCategory( undefined, jQuery( '#free-category-text-input' ).val() );
			} else {
				wppfm_mainInputChanged( true );
			}
		} else {

			wppfm_mainInputChanged( true );
		}

	} else { // category mapping selection changed
		//wppfm_setChildCategories( id, jQuery( '#feed-category-' + id + ' :input' ).val() ); // TODO: would be better if I could reuse this function
		_feedHolder.changeCustomFeedCategoryMap( id, jQuery( '#feed-category-' + id + ' :input' ).val() );
	}
}

/**
 * Generates a new feed and opens the feed table.
 */
function wppfm_constructNewFeed() {
	var daysIntervalElement            = document.getElementById( 'days-interval' );
	var updateScheduleHourElement      = document.getElementById( 'update-schedule-hours' );
	var updateScheduleMinutesElement   = document.getElementById( 'update-schedule-minutes' );
	var updateScheduleFrequencyElement = document.getElementById( 'update-schedule-frequency' );
	var mainCategorySelectElement      = document.getElementById( 'lvl_0' );
	var mainCategorySelectedIndex      = mainCategorySelectElement && mainCategorySelectElement.selectedIndex ? mainCategorySelectElement.selectedIndex : 0;

	// get all the data from the input fields
	var fileName        = document.getElementById( 'wppfm-feed-file-name' ).value;
	var source          = '1';
	var mainCategory    = mainCategorySelectElement && mainCategorySelectElement.options[ mainCategorySelectedIndex ] ? mainCategorySelectElement.options[ mainCategorySelectedIndex ].text : 'dummy_category_for_supplemental_feeds';
	var categoryMapping = [];
	var channel         = document.getElementById( 'wppfm-merchants-selector' ).value;
	var variations      = document.getElementById( 'variations' ).checked ? 1 : 0;
	var aggregator      = document.getElementById( 'aggregator' ).checked ? 1 : 0;
	var country         = document.getElementById( 'wppfm-countries-selector' ).value;
	var language        = document.getElementById( 'language' ) === null ? '' : document.getElementById( 'language' ).value;
	var currency        = document.getElementById( 'currency' ) === null ? '' : document.getElementById( 'currency' ).value;
	var feedTitle       = document.getElementById( 'google-feed-title-selector' ).value;
	var feedDescription = document.getElementById( 'google-feed-description-selector' ).value;
	var daysInterval    = daysIntervalElement.value !== '' ? daysIntervalElement.value : '1';
	var hours           = updateScheduleHourElement.value !== '' ? updateScheduleHourElement.value : '00';
	var minutes         = updateScheduleMinutesElement.value !== '' ? updateScheduleMinutesElement.value : '00';
	var frequency       = updateScheduleFrequencyElement.value !== '' ? updateScheduleFrequencyElement.value : '1';
	var feedFilter      = [];
	var status          = 2;
	var channelFeedType = wppfm_getChannelFeedType( channel );
	var feedType        = document.getElementById( 'wppfm-feed-types-selector' ) === null ? '1' : document.getElementById( 'wppfm-feed-types-selector' ).value;

	// make the url to the feed file
	var url     = jQuery( '#wppfm-feed-editor-page-data-storage' ).data( 'wppfmFeedUrl' );
	var updates = daysInterval + ':' + hours + ':' + minutes + ':' + frequency;

	wppfm_setScheduleSelector( daysInterval, frequency );

	// make a new feed object
	_feedHolder = new Feed( - 1, fileName, variations, aggregator, parseInt( channel ), mainCategory, categoryMapping, url, source, country, language, currency, feedTitle, feedDescription,
		updates, feedFilter, status, feedType );
}

/**
 * This function can be used by special feed add-on plugins to handle construction of a new non-standard feed like the Google Review Feed.
 *
 * @param {object}  specialFeedFeedHolder
 */
function wppfm_constructNewSpecialFeed( specialFeedFeedHolder ) {
	_feedHolder = specialFeedFeedHolder;
}

/**
 * This function allows to set a feed property that are to be used for special feeds like Google Product Review Feeds.
 *
 * @param {string}           key    name of the property
 * @param {string|int|array} value  value the property should get
 * @param {string}           type   type of the property, like string, int or array
 */
function wppfm_setSpecialFeedProperty( key, value, type ) {
	if ( _feedHolder.hasOwnProperty( key ) ) {
		if ( 'object' === typeof _feedHolder[ key ] ) {
			_feedHolder[ key ].push( value );
		} else {
			_feedHolder[ key ] = value;
		}
	} else {
		if ( 'array' === type ) {
			_feedHolder[ key ] = [];
			_feedHolder[ key ].push( value );
		} else {
			_feedHolder[ key ] = value;
		}
	}
}

function wppfm_finishOrUpdateFeedPage( categoryChanged ) {
	var selectedChannelElement = jQuery( '#wppfm-merchants-selector' );
	var lvl0Element            = jQuery( '#lvl_0' );
	var selectedChannelValue   = selectedChannelElement.val().toString();

	wppfm_showWorkingSpinner();

	// make sure the data is correct
	_feedHolder[ 'title' ]             = jQuery( '#wppfm-feed-file-name' ).val()
	_feedHolder[ 'channel' ]           = selectedChannelElement.val();
	_feedHolder[ 'feedType' ]          = jQuery( '#wppfm-feed-types-selector' ).val();
	_feedHolder[ 'includeVariations' ] = jQuery( '#variations' ).is( ':checked' ) ? '1' : '0';
	_feedHolder[ 'isAggregator' ]      = jQuery( '#aggregator' ).is( ':checked' ) ? '1' : '0';
	_feedHolder[ 'feedTitle' ]         = jQuery( '#google-feed-title-selector' ).val();
	_feedHolder[ 'feedDescription' ]   = jQuery( '#google-feed-description-selector' ).val();
	_feedHolder[ 'language' ]          = document.getElementById( 'language' ) === null ? '' : jQuery( '#wppfm-feed-language-selector' ).val();
	_feedHolder[ 'currency' ]          = document.getElementById( 'currency' ) === null ? '' : jQuery( '#wppfm-feed-currency-selector' ).val();

	// get the output fields that can be used with the selected channel
	wppfm_getFeedAttributes( - 1, selectedChannelValue, _feedHolder[ 'feedType' ], function( outputs ) {

		// store the feed specification url
		_feedHolder[ 'feedSpecificationUrl' ] = outputs[ 'feed_specification_url' ];

		_feedHolder.setUpdateSchedule( jQuery( '#days-interval' ).val(), jQuery( '#update-schedule-hours' ).val(),
		jQuery( '#update-schedule-minutes' ).val(), jQuery( '#update-schedule-frequency' ).val() );

		// add the default attributes if attributes are not set
		if ( undefined === _feedHolder[ 'attributes' ] || 0 === _feedHolder[ 'attributes' ].length ) {
			wppfm_addFeedAttributes( outputs, selectedChannelValue );
		}

		wppfm_customSourceFields( _feedHolder[ 'dataSource' ], function( customFields ) {

			_feedHolder[ 'country' ]  = jQuery( '#wppfm-countries-selector' ).val();
			_feedHolder[ 'language' ] = jQuery( '#wppfm-feed-language-selector' ).val();
			_feedHolder[ 'currency' ] = jQuery( '#wppfm-feed-currency-selector' ).val();

			wppfm_fillSourcesList( customFields );

			wppfm_mainFeedFilters( _feedHolder[ 'feedId' ], function( feedFilters ) {

				// get the master feed filter
				var mainFeedFilter     = feedFilters !== 1 ? feedFilters : null;
				var attributeLevelArgs = '0' !== _feedHolder[ 'country' ] ? _feedHolder[ 'country' ] : _feedHolder[ 'language' ];

				_feedHolder.setFeedFilter( mainFeedFilter );

				// set the correct level of the attributes
				_feedHolder = wppfm_setOutputAttributeLevels( selectedChannelValue, _feedHolder, attributeLevelArgs );

				wppfm_makeFeedFilterWrapper( _feedHolder[ 'feedId' ], _feedHolder[ 'feedFilter' ] );

				if ( categoryChanged ) {
					_feedHolder[ 'mainCategory' ] = lvl0Element.val() ? lvl0Element.val() : jQuery( '#free-category-text-input' ).val();
					_feedHolder.setMainCategory( 'lvl_0', lvl0Element.val(), selectedChannelValue );
				}

				// draws the attribute mapping section on the form
				wppfm_drawAttributeMappingSection();

				if ( _feedHolder !== 0 ) {
					var isNew = _feedHolder[ 'feedId' ] === - 1;
					wppfm_fillFeedFields( isNew, categoryChanged );
				}

				// TODO: somewhere between the initialization of channel in _feedHolder the channel id is changed
				// to an integer in stead of the required string. For now I just reset the variable to
				// a string again, but I need to figure out why this is happening. Before the wppfm_addFeedAttributes
				// it is still a string. When it's an int, the static menus of a new feed will not work anymore.
				_feedHolder[ 'channel' ] = selectedChannelValue;

				// show the buttons again
				jQuery( 'section' ).filter( '#page-center-buttons' ).show();
				jQuery( '#page-bottom-buttons' ).show();

				wppfm_hideWorkingSpinner();
			} );
		} );
	} );
}

/**
 * This function can be used by special feed add-ons to handle special feed updates.
 *
 * @param {array} specialFeedFeedHolder
 */
function wppfm_finishOrUpdateSpecialFeedPage( specialFeedFeedHolder ) {
	_feedHolder = specialFeedFeedHolder;

	// draws the attribute mapping section on the form
	wppfm_drawAttributeMappingSection();

	if ( _feedHolder ) {
		var isNew = _feedHolder[ 'feedId' ] === - 1;
		wppfm_fillFeedFields( isNew, false );
	}

	// show the buttons again
	jQuery( 'section' ).filter( '#page-center-buttons' ).show();
	jQuery( '#page-bottom-buttons' ).show();
}


// function wppfm_handleSupportFeedSelection( supportFeedId ) {
// 	alert( wppfm_feed_settings_form_vars.support_feeds_only_for_premium );
// 	var googleFeedType   = jQuery( '#wppfm-feed-types-selector' );
// 	googleFeedType.val( '1' );

/**
 * Handles the selection of a Google Support Feed from an Edit Feed form.
 *
 * @param supportFeedId
 */
function wppfm_handleSupportFeedSelection( supportFeedId ) {
	switch( supportFeedId ) {
		case '1':
			console.log( 'Standard Product Feed selected' );
			wppfm_standardProductFeedSelected();
			break;

		case '2':
			console.log( 'Product Review Feed selected' );
			wpprfm_productReviewFeedSelected();
			break;

		case '3':
			console.log( 'Merchant Promotions Feed selected' );
			wpppfm_merchantPromotionsFeedSelected();
			break;

		case '4':
			console.log( 'Local Product Inventory Feed selected' );
			wppfm_localProductInventoryFeedSelected();
			break;

		case '5':
			console.log( 'Dynamic Remarketing Feed selected' );
			wppfm_dynamicRemarketingFeedSelected();
			break;

		case '6':
			console.log( 'Vehicle Ads Feed selected' );
      wppfm_vehicleAdsFeedSelected();
			break;

		case '7':
			console.log( 'Dynamic Search Ads Feed selected' )
      wppfm_dynamicSearchAdsFeedSelected();
			break;

		case '8':
			console.log( 'Product Inventory Feed selected' )
			wppfm_localProductFeedSelected();
			break;

		case '9':
			console.log( 'Buy on Google Feed selected' )
			break;
	}
}

function wppfm_initiateFeed() {
	var feedData = jQuery("#wppfm-feed-editor-page-data-storage").data("wppfmFeedData");

	if ( ! feedData ) { return; }

	// make a _feedHolder
	if ( feedData ) {
		_feedHolder = new Feed(
			feedData['feed_id'],
			feedData['feed_file_name'],
			feedData['include_variations'],
			feedData['is_aggregator'],
			feedData['channel_id'],
			feedData['main_category'],
			feedData['category_mapping'],
			feedData['url'],
			feedData['source'],
			feedData['target_country'],
			feedData['language'],
			feedData['currency'],
			feedData['feed_title'],
			feedData['feed_description'],
			feedData['schedule'],
			[],
			feedData['status_id'],
			feedData['feed_type_id'],
		);

		wppfm_addFeedAttributes( feedData['attribute_data'], feedData['channel_id'] );

		_feedHolder.setFeedFilter( feedData['feed_filter'] );

		_feedHolder['source_fields']  = feedData['source_fields'];
		_feedHolder['aggregatorName'] = feedData['aggregator_name'];

		console.log( _feedHolder );
	}
}

/**
 * Handles JSON parse errors and tries to report the position of the error in the JSON string to the console.
 *
 * @param error
 * @param failingString
 */
function wppfm_handleJsonParseError(error, failingString) {
	if( error.message.includes('at position') ) {
		console.log(error.message);

		var messageBreakup = error.message.split(' ');
		var errorLocation = messageBreakup[messageBreakup.length - 1].match(/\d+/g);

		if ( null != errorLocation ) {
			var failingPartOfJSONString = failingString.substring(errorLocation - 20, 40);
			console.log('The incorrect part in the JSON string is on position 20 of this part: "' + failingPartOfJSONString + '"');
		} else {
			console.log(error);
		}
	} else {
		console.log(error);
	}
}

/**
 * Builds up the Edit Feed page for an already existing feed.
 *
 * @param   {string}    feedId  The id of the feed.
 */
function wppfm_editExistingFeed( feedId ) {

	// exit if data is not available
	if ( feedId !== _feedHolder[ 'feedId' ] ) {
		return;
	}

	var categoryString = _feedHolder[ 'mainCategory' ];
	var mainCategory   = categoryString && categoryString.indexOf( ' > ' ) > - 1 ? categoryString.substring( 0, categoryString.indexOf( ' > ' ) ) : categoryString;

	wppfm_fillCategoryVariables( _feedHolder[ 'channel' ], mainCategory, '0' ); // make sure the category values are set correctly

	wppfm_fillSourcesList( _feedHolder[ 'source_fields' ] );

	var attributeLevelArgs = ! wppfm_requiresLanguageInput( _feedHolder[ 'channel' ] ) ? _feedHolder[ 'country' ] : _feedHolder[ 'language' ];

	// set the correct level of the attributes
	_feedHolder = wppfm_setOutputAttributeLevels( _feedHolder[ 'channel' ], _feedHolder, attributeLevelArgs );

	wppfm_makeFeedFilterWrapper( _feedHolder[ 'feedId' ], _feedHolder[ 'feedFilter' ] );

	// draws the attribute mapping section on the form
	wppfm_drawAttributeMappingSection();

	if ( _feedHolder !== 0 ) {
		wppfm_fillFeedFields( false, false );
	}

	if ( _feedHolder[ 'categoryMapping' ] ) {
		wppfm_setCategoryMap( _feedHolder[ 'categoryMapping' ] );
	}

	// enable the Generate and Save buttons
	wppfm_enableFeedActionButtons();

	jQuery( '#wppfm-countries-selector' ).prop( 'disabled', false );
	jQuery( '#wppfm-feed-types-selector' ).prop( 'disabled', false );

	// set the default categories select fields in the background
	wppfm_fillDefaultCategorySelectors();

	// set the identifier_exists layout
	wppfm_setIdentifierExistsDependencies();

	wppfm_setMainInputsLayoutDependingOnFeedTypeId( _feedHolder[ 'channel' ], _feedHolder[ 'feedType' ] );

	// show the buttons again
	jQuery( 'section' ).filter( '#page-center-buttons' ).show();
	jQuery( '#page-bottom-buttons' ).show();
}

function wppfm_fillSourcesList( customFields ) {
	_inputFields = wppfm_woocommerceSourceOptions();
	wppfm_addCustomFieldsToInputFields( _inputFields, customFields );
	_inputFields.sort( function( a, b ) {
		return (
					'' + a.label
				).toUpperCase() < (
					'' + b.label
				).toUpperCase() ? - 1 : 1;
		}
	);
}

function wppfm_saveUpdateSchedule() {

	// get the values
	var days    = jQuery( '#days-interval' ).val();
	var hours   = jQuery( '#update-schedule-hours' ).val();
	var minutes = jQuery( '#update-schedule-minutes' ).val();
	var freq    = jQuery( '#update-schedule-frequency' ).val();

	// change the form selector if required
	if ( days !== '1' ) {
		freq = '1';
	}

	if ( freq === 1 ) {
		days = '1';
	}

	wppfm_setScheduleSelector( days, freq );

	// store the selection in the feed
	_feedHolder.setUpdateSchedule( days, hours, minutes, freq );
}

function wppfm_setScheduleSelector( days, freq ) {
	var updateFrequencyWrapperElement = jQuery( '#wppfm-update-frequency-wrapper' );
	var updateDayWrapperElement       = jQuery( '#wppfm-update-day-wrapper' );
	var updateEveryDayWrapperElement  = jQuery( '#wppfm-update-every-day-wrapper' );

	// change the form selector if required
	if ( days === '1' ) {
		updateFrequencyWrapperElement.show();
	} else {
		updateFrequencyWrapperElement.hide();
	}

	if ( freq > 1 ) {
		updateDayWrapperElement.hide();
		updateEveryDayWrapperElement.show();
	} else {
		updateDayWrapperElement.show();
		updateEveryDayWrapperElement.hide();
	}
}

function wppfm_setMainInputsLayoutDependingOnFeedTypeId( channelId, feedTypeId ) {
	switch( channelId ) {
		case '1': // Google
			switch( feedTypeId ) {
				case '4': // Google Local Product Inventory Feed
					wppfm_showLocalProductInventoryFeedInputs();
					break;

				case '5': // Google Dynamic Remarketing Feed
					wppfm_showDynamicRemarketingFeedInputs();
					break;

				case '6': // Google Vehicle Ads Feed
          wppfm_showVehicleAdsFeedInputs();
					break;

				case '7': // Dynamic Search Ads Feed
          wppfm_showDynamicSearchAdsFeedInputs();
					break;

				case '8': // Google Local Product Feed
					wppfm_showLocalProductFeedInputs();
					break;

				default:
					break;
			}
	}
}

function wppfm_addCustomFieldsToInputFields( inputFields, customFields ) {

	if ( customFields !== '0' ) {
		for ( var i = 0; i < customFields.length; i ++ ) {

			var field = {
				value: customFields[ i ].attribute_name,
				label: customFields[ i ].attribute_label,
				prop: 'custom',
			};
			inputFields.push( field );
		}
	}
}

/**
 * Gets the correct categories from the category file and fills the category selector with them
 */
function wppfm_fillDefaultCategorySelectors() {
	var mainCategoriesString = _feedHolder[ 'mainCategory' ];
	var channel              = _feedHolder[ 'channel' ];
	var language             = wppfm_channelCountryCode( channel );

	wppfm_getCategoryListsFromString( channel, mainCategoriesString, language, function( categories ) {
		var lists = JSON.parse( categories );

		if ( lists && lists.length > 0 && mainCategoriesString !== undefined ) {
			var categoriesArray = mainCategoriesString.split( ' > ' );

			for ( var i = 0; i < lists.length; i ++ ) {
				jQuery( '#lvl_' + i ).append( wppfm_categorySelectCntrl( lists[ i ] ) );

				var element   = document.getElementById( 'lvl_' + i );
				element.value = categoriesArray[ i ];
			}
		} else {
			jQuery( '#lvl_0' ).prop( 'disabled', false );
		}
	} );
}

function wppfm_setGoogleFeedTitle( value ) {
	_feedHolder[ 'feedTitle' ] = value;
}

function wppfm_setGoogleFeedDescription( value ) {
	_feedHolder[ 'feedDescription' ] = value;
}

function wppfm_setGoogleFeedType( value ) {
	_feedHolder[ 'feedType '] = value;
}

function wppfm_setGoogleFeedLanguage( value ) {
	_feedHolder[ 'language' ] = value;
}

function wppfm_setDrmBusinessType( value ) {
	_feedHolder[ 'aggregatorName' ] = value;
}

// @since 2.28.0
function wppfm_setGoogleFeedCurrency( value ) {
	_feedHolder[ 'currency' ] = value;
}

function wppfm_setCategoryMap( mapping, mode ) {

	if( undefined === mode ) {
		mode = 'mapping'; // default setting
	}

	var map = JSON.parse( mapping );

	for ( var i = 0; i < map.length; i ++ ) {

		var categoryId = map[ i ].shopCategoryId;
		var mapString  = '';

		if ( 'mapping' === mode ) { // only show the category mapping column when in mapping mode
			switch (map[ i ].feedCategories) {

				case 'wp_mainCategory':
					mapString = wppfm_mapToDefaultCategoryElement(categoryId, 'default');
					break;

				case 'wp_ownCategory':
					mapString = wppfm_mapToDefaultCategoryElement(categoryId, 'shopCategory');
					break;

				default:
					mapString = wppfm_mapToCategoryElement(categoryId, map[ i ].feedCategories);
					break;
			}
		}

		jQuery( '#feed-selector-' + categoryId ).prop( 'checked', true );
		jQuery( '#feed-category-' + categoryId ).html( mapString );
	}
}

function wppfm_generateAndSaveFeed() {
	wppfm_showWorkingSpinner();

	//noinspection JSUnresolvedVariable
	_feedHolder[ 'mainCategory' ] = ! wppfm_channelUsesOwnCategories(
		_feedHolder[ 'channel' ] ) ? _feedHolder[ 'mainCategory' ] : wppfm_feed_settings_form_vars.no_category_required;

	// save the feed data to the database
	wppfm_saveFeedToDb( _feedHolder, function( dbResult ) {

		var newFeed = _feedHolder[ 'feedId' ] === - 1;

		wppfm_handleSaveFeedToDbActionResult( dbResult, newFeed );

		// convert the data to xml or csv and save the code to a feed file
		wppfm_updateFeedFile( _feedHolder[ 'feedId' ], function( xmlResult ) {

			wppfm_handleUpdateFeedFileActionResult( xmlResult );

			wppfm_hideWorkingSpinner();
		} );
	} );
}

function wppfm_handleSaveFeedToDbActionResult( dbResult, newFeed ) {

	// the wppfm_saveFeedToDb returns the entered feed id
	if ( 0 === dbResult || '0' === dbResult ) {
		wppfm_handleSaveFeedToDbFailedAction();
	} else {

		// insert the feed id in the _feed
		_feedHolder[ 'feedId' ] = dbResult;

		if ( newFeed ) {
			// reset the url to implement the feed id so the user can reset the form if he wants
			wppfm_resetUrlForNewFeed( _feedHolder[ 'feedId' ], 'product-feed' );
			wppfm_resetFormElementIds( _feedHolder[ 'feedId' ] );
			wppfm_storeFeedUrlInSourceData( _feedHolder[ 'url' ] );
		}
	}
}

/**
 * Handles a callback error from the function that should save the feed data to the db.
 */
function wppfm_handleSaveFeedToDbFailedAction() {
	console.log( 'Saving the data to the data base has failed!' );
	//noinspection JSUnresolvedVariable
	wppfm_showErrorMessage( wppfm_feed_settings_form_vars.save_data_failed );
	wppfm_hideWorkingSpinner();
}

/**
 * Handles the callback from the function that updates the feed.
 *
 * @param   {string}    xmlResult
 */
function wppfm_handleUpdateFeedFileActionResult( xmlResult ) {
	var errorMessageElement = jQuery( '#wppfm-error-message' );

	wppfm_disableViewFeedButtons();

	switch ( xmlResult ) {
		case 'started_processing':
			errorMessageElement.hide();
			//noinspection JSUnresolvedVariable
			wppfm_showSuccessMessage( wppfm_feed_settings_form_vars.feed_started );
			wppfm_alert_update_finished( _feedHolder[ 'feedId' ], 10000 );
			break;

		case 'pushed_to_queue':
			errorMessageElement.hide();
			//noinspection JSUnresolvedVariable
			wppfm_showSuccessMessage( wppfm_feed_settings_form_vars.feed_queued );
			wppfm_alert_update_finished( _feedHolder[ 'feedId' ], 10000 );
			break;

		case 'writing_error':
			//noinspection JSUnresolvedVariable
			wppfm_showErrorMessage( wppfm_feed_settings_form_vars.feed_writing_error );
			wppfm_hideWorkingSpinner();
			break;

		case 'foreground_processing_complete':
			wppfm_alert_update_finished( _feedHolder[ 'feedId' ], 0 );
			wppfm_hideWorkingSpinner();
			break;

		case 'activation_error':
			//noinspection JSUnresolvedVariable
			wppfm_showErrorMessage( wppfm_feed_settings_form_vars.feed_initiation_error );
			wppfm_hideWorkingSpinner();
			break;

		case '1':
			//noinspection JSUnresolvedVariable
			wppfm_showErrorMessage( wppfm_feed_settings_form_vars.feed_general_error.replace( '%xmlResult%', xmlResult ) );
			wppfm_hideWorkingSpinner();
			break;

		default:
			wppfm_hideWorkingSpinner();
	}
}

/**
 * Checks the status of the feed generation process every 10 seconds and shows a message when the feed is ready.
 *
 * @param   {int}   feedId
 * @param   {int}   repeatTime  Time to repeat the status check, default 10.
 */
function wppfm_alert_update_finished( feedId, repeatTime ) {
	if ( undefined === repeatTime || 0 === repeatTime ) {
		repeatTime = 10000;
	} // default value

	var wppfmStatusCheck           = window.setInterval( wppfm_checkAndSetStatus, repeatTime, feedId );
	var successErrorMessageElement = jQuery( '#wppfm-success-message' );

	if ( ! feedId ) {
		return false;
	}

	function wppfm_checkAndSetStatus( feedId ) {
		wppfm_getCurrentFeedStatus( feedId, function( result ) {
			var status = JSON.parse( result );
			var statusId = status[ 'status_id' ];
			var statusText = [ 'unknown', 'on hold', 'active', 'processing', 'in queue', 'error', 'failed'];
			var feedTypes = [ 'products', 'reviews', 'promotions' ];
			var itemName = feedTypes[ parseInt( status[ 'feed_type_id' ] ) - 1 ];

			console.log( 'Feed status changed to status ' + statusId + ' (' + statusText[ parseInt(statusId) ] + ')' );

			wppfm_enableFeedActionButtons( wppfm_getUrlParameter( 'feed-type' ) );

			switch ( status[ 'status_id' ] ) {
				case '0': // unknown
					//noinspection JSUnresolvedVariable
					wppfm_showSuccessMessage( wppfm_feed_settings_form_vars.feed_status_unknown.replace( '%feedname%', status[ 'title' ] ) );
					window.clearInterval( wppfmStatusCheck );
					break;

				case '1': // on hold
				case '2': // active
					// Get the feed url and store it in the wppfm-feed-url storage so the View Feed button gets the correct url to open.
					wppfm_storeFeedUrlInSourceData( status[ 'url' ] )
					wppfm_enableViewFeedButtons();

					//noinspection JSUnresolvedVariable
					wppfm_showSuccessMessage(
						wppfm_feed_settings_form_vars.feed_status_ready.replace( '%feedname%', status[ 'title' ] ).replace( '%prodnr%', status[ 'products' ] ).replace( '%feedtype%', itemName ) );
					window.clearInterval( wppfmStatusCheck );
					break;

				case '3': // processing
					//noinspection JSUnresolvedVariable
					wppfm_showSuccessMessage( wppfm_feed_settings_form_vars.feed_status_still_processing );
					break;

				case '4': // in queue
					//noinspection JSUnresolvedVariable
					wppfm_showSuccessMessage( wppfm_feed_settings_form_vars.feed_status_added_to_queue );
					break;

				case '5': // error
					successErrorMessageElement.hide();
					//noinspection JSUnresolvedVariable
					wppfm_showErrorMessage( wppfm_feed_settings_form_vars.feed_status_error.replace( '%feedname%', status[ 'title' ] ) );
					window.clearInterval( wppfmStatusCheck );
					break;

				case '6': // failed
					successErrorMessageElement.hide();
					//noinspection JSUnresolvedVariable
					wppfm_showErrorMessage( wppfm_feed_settings_form_vars.feed_status_failed.replace( '%feedname%', status[ 'title' ] ) );
					window.clearInterval( wppfmStatusCheck );
					break;
			}
		} );
	}
}

function wppfm_saveFeed() {

	wppfm_showWorkingSpinner();

	var newFeed = _feedHolder[ 'feedId' ] === - 1;

	//noinspection JSUnresolvedVariable
	_feedHolder[ 'mainCategory' ] = ! wppfm_channelUsesOwnCategories( _feedHolder[ 'channel' ] ) ? _feedHolder[ 'mainCategory' ] : wppfm_feed_settings_form_vars.no_category_required;

	if ( newFeed ) {
		//noinspection JSUnresolvedVariable
		_feedHolder[ 'url' ] = wppfm_feed_settings_form_vars.no_feed_generated;
	}

	// save the feed data to the database
	wppfm_saveFeedToDb(	_feedHolder,function( dbResult ) {

			// the wppfm_saveFeedToDb returns the entered feed id
			if ( dbResult === 0 ) {
				console.log( 'Saving the data to the data base has failed!' );
				//noinspection JSUnresolvedVariable
				wppfm_showErrorMessage( wppfm_feed_settings_form_vars.save_data_failed );
			} else {

				// insert the feed id in the _feed
				_feedHolder[ 'feedId' ] = dbResult;

				if ( newFeed ) {
					// reset the url to implement the feed id so the user can reset the form if he wants
					var currentUrl       = window.location.href;
					window.location.href = currentUrl + '&id=' + _feedHolder[ 'feedId' ];
				}

				wppfm_showSuccessMessage( wppfm_feed_settings_form_vars.feed_changes_saved );
			}

			wppfm_enableFeedActionButtons();
			wppfm_hideWorkingSpinner();
		}
	);
}

/**
 * Gets the next category list and fills the selector with it
 *
 * @param {string} currentLevelId
 */
function wppfm_nextCategory( currentLevelId ) {
	var selectedCategory   = jQuery( '#' + currentLevelId ).val();

	if ( 'cat_number' === selectedCategory ) { // User selected the Select by category number option.
		var isCategoryMappingSelection = currentLevelId.includes('catmap');
		var shopCategoryId = isCategoryMappingSelection ? currentLevelId.split('-')[1].slice(0, -2) : _feedHolder['feedId'] ;
		var currentValue = !isNaN(_feedHolder['mainCategory']) ? _feedHolder['mainCategory'] : '';
		var categorySelectorType = isCategoryMappingSelection ? 'mapping' : 'default';
		var cat_selector = isCategoryMappingSelection ? jQuery( '#feed-category-' + shopCategoryId ) : jQuery( '#category-selector-lvl' );

		cat_selector.html( wppfm_freeCategoryInputCntrl( categorySelectorType, shopCategoryId, currentValue ) );
		jQuery( '#category-selector-catmap-' + shopCategoryId ).hide();
		return;
	}

	var nextLevelId        = wppfm_incrementLast( currentLevelId );
	var nextLevel          = nextLevelId.match( /(\d+)$/ )[ 0 ]; // get the number on the end of the nextLevelId
	var channel            = _feedHolder[ 'channel' ] ? _feedHolder[ 'channel' ].toString() : jQuery( '#wppfm-merchants-selector' ).val();
	var language           = wppfm_channelCountryCode( channel );
	var nextLevelIdElement = jQuery( '#' + nextLevelId );

	if ( nextLevel > 1 ) {
		wppfm_showWorkingSpinner();
	}

	// show the correct sublevel selectors, hide the others
	wppfm_hideSubs( currentLevelId );

	// fill the special filter variables
	wppfm_fillCategoryVariables( channel, selectedCategory, currentLevelId );

	// get the next level selector
	wppfm_getNextCategories( channel, nextLevel, selectedCategory, language, function( categories ) {
		var list = JSON.parse( categories );

		if ( list.length > 0 ) {
			nextLevelIdElement.append( wppfm_categorySelectCntrl( list ) );
			nextLevelIdElement.show();
		}

		if ( currentLevelId.indexOf( 'catmap' ) > -1 ) { // the selection is from the category map
			wppfm_setChildCategories( currentLevelId, selectedCategory );
		} else { // the selection is from the default category
			_feedHolder.setMainCategory( currentLevelId, selectedCategory, channel );

			if ( _feedHolder[ 'attributes' ].length > 0 && _feedHolder[ 'attributes' ][ 3 ][ 'value' ] !== undefined && _feedHolder[ 'attributes' ][ 3 ][ 'value' ] !== '' ) {
				jQuery( '#category-source-string' ).html( JSON.parse( _feedHolder[ 'attributes' ][ 3 ][ 'value' ] ).t );
			} else {
				jQuery( '#category-source-string' ).html( _feedHolder[ 'mainCategory' ] );
			}
		}

		if ( nextLevel > 1 ) {
			wppfm_hideWorkingSpinner();
		}
	} );
}

function wppfm_setChildCategories( categorySelectorId, selectedCategory ) {
	var selectedLevel    = categorySelectorId.match( /(\d+)$/ )[ 0 ]; // next level
	var sc               = categorySelectorId.replace( '_' + selectedLevel, '' );
	var parentCategoryId = sc.match( /(\d+)$/ )[ 0 ];
	var children         = wppfm_getCategoryChildren( parentCategoryId );

	for ( var i = 0; i < children.length; i ++ ) {
		wppfm_setChildrenToParentCategory( parentCategoryId, children[ i ], selectedLevel, selectedCategory );
	}

	_feedHolder.mapCategory( categorySelectorId, selectedCategory );
}

function wppfm_getCategoryChildren( parentCategoryId ) {
	var feedSelectorElement = jQuery( '#feed-selector-' + parentCategoryId );

	return feedSelectorElement.attr( 'data-children' ) ? JSON.parse( feedSelectorElement.attr( 'data-children' ) ) : [];
}

function wppfm_variation_selection_changed() {
	alert( wppfm_feed_settings_form_vars.variation_only_for_premium );
	_feedHolder.changeIncludeVariations( false );
	jQuery( '#variations' ).prop( 'checked', false );
}

function wppfm_aggregatorChanged() {
	if ( jQuery( '#aggregator' ).is( ':checked' ) ) {
		_feedHolder.changeAggregator( true );
		_feedHolder.attributes[ 8 ][ 'fieldLevel' ] = '1';
	} else {
		_feedHolder.changeAggregator( false );
		_feedHolder.attributes[ 8 ][ 'fieldLevel' ] = '0';
		_feedHolder.deactivateAttribute( '8' );
	}
}

function wppfm_setChildrenToParentCategory( parentId, childId, level, selectedCategory ) {
	var currentChildCategory   = wppfm_getCategorySelectorValue( childId );
	var curCatStr              = '';
	var newCategory            = wppfm_addNewItemToCategoryString( level, currentChildCategory, selectedCategory, ' > ' );
	var categorySelectorId     = 'catmap-' + childId + '_' + level;
	var catmapTextSpanSelector = jQuery( '#category-text-span-' + childId );

	for ( var i = 0; i < level; i ++ ) {
		curCatStr += jQuery( '#catmap-' + parentId + '_' + i ).val() + ' > ';
	}

	var preSelectionParentCategory = curCatStr.substring( 0, curCatStr.length - 3 );

	//noinspection JSUnresolvedVariable
	if ( currentChildCategory === preSelectionParentCategory || currentChildCategory === wppfm_feed_settings_form_vars.map_to_default_category ) {
		if ( catmapTextSpanSelector.length === 0 ) {
			wppfm_mapToDefaultCategoryElement( childId, newCategory );
		}

		catmapTextSpanSelector.text( newCategory );

		var feedSelectorElement = jQuery( '#feed-selector-' + childId );
		var children            = feedSelectorElement.attr( 'data-children' ) ? JSON.parse( feedSelectorElement.attr( 'data-children' ) ) : [];

		for ( var j = 0; j < children.length; j ++ ) {
			wppfm_setChildrenToParentCategory( childId, children[ j ], level, selectedCategory );
		}

		_feedHolder.mapCategory( categorySelectorId, selectedCategory );
	} else if ( catmapTextSpanSelector.length === 0 ) {
		jQuery( '#feed-category-' + childId ).html( wppfm_mapToDefaultCategoryElement( childId, currentChildCategory ) );
		jQuery( '#category-selector-catmap-' + childId ).hide();
	}
}

function wppfm_getCategorySelectorValue( selectorId ) {
	var val = jQuery( '#category-text-span-' + selectorId ).text();

	if ( val === '' ) {
		var catString = '';

		for ( var i = 0; i < 7; i ++ ) {
			var catMapSelectorElement = jQuery( '#catmap-' + selectorId + '_' + i );
			var cat                   = catMapSelectorElement.val();

			if ( cat !== null && cat !== '0' ) {
				catString += catMapSelectorElement.val() + ' > ';
			}
		}

		val = catString.substring( 0, catString.length - 3 );
	}

	return val;
}

function wppfm_fillFeedFields( isNew, categoryChanged ) {
	// if the category attribute has a value
	if ( _feedHolder[ 'mainCategory' ] && isNew === false ) {
		// and display the category in the Default Category input field unless only the category has been changed
		if ( ! categoryChanged ) {
			// make the category string
			var categoryString = _feedHolder[ 'mainCategory' ] +
			                     ' (<a class="edit-categories wppfm-btn wppfm-btn-small" href="javascript:void(0)" ' +
			                     'id="wppfm-edit-categories" onclick="wppfm_editCategories()">' + wppfm_feed_settings_form_vars.edit + '</a>)';

			jQuery( '#lvl_0' ).hide();
			jQuery( '#selected-categories' ).html( categoryString );

			if ( '1' === _feedHolder[ 'channel' ]) {
				jQuery('#wppfm-country-list-row' ).show();
			}
		}
	} else {
		jQuery( '#lvl_0' ).css( 'display', 'initial' );
	}

	var schedule = _feedHolder[ 'updateSchedule' ] ? _feedHolder[ 'updateSchedule' ].split( ':' ) : [];

	if ( ! isNew ) {
		wppfm_showChannelInputs( _feedHolder[ 'channel' ], isNew );
	} else {
		jQuery( '#wppfm-category-map' ).show();
	}

	wppfm_setMerchantSelector( isNew, _feedHolder[ 'channel' ] );
	if ( '1' === _feedHolder[ 'channel' ].toString() ) {
		wppfm_setGoogleFeedTypeSelector( isNew, _feedHolder['feedType'] );

		if ( '5' === _feedHolder['feedType'] ) { // to handle a Google Dynamic Remarketing feed
			jQuery('#wppfm-feed-drm-types-selector').val( _feedHolder['aggregatorName'] );
			wppfm_setDrmFeedTypeAttributes( _feedHolder['aggregatorName'] );
		}
	}

	jQuery( '#wppfm-feed-file-name' ).val( _feedHolder[ 'title' ] );
	jQuery( '#variations' ).prop( 'checked', _feedHolder[ 'includeVariations' ] > 0 );
	jQuery( '#aggregator' ).prop( 'checked', _feedHolder[ 'isAggregator' ] > 0 );
	jQuery( '#wppfm-countries-selector' ).val( _feedHolder[ 'country' ] );
	jQuery( '#google-feed-title-selector' ).val( _feedHolder[ 'feedTitle' ] );
	jQuery( '#google-feed-description-selector' ).val( _feedHolder[ 'feedDescription' ] );
	jQuery( '#days-interval' ).val( schedule[ 0 ] );

	if ( document.getElementById( 'wppfm-feed-language-selector' ) !== null ) {
		var langVal = _feedHolder[ 'language' ] !== '' ? _feedHolder[ 'language' ] : '0';
		jQuery( '#wppfm-feed-language-selector' ).val( langVal );
	}

	if ( document.getElementById( 'wppfm-feed-currency-selector' ) !== null ) {
		var currencyVal = _feedHolder[ 'currency' ] ? _feedHolder[ 'currency' ] : '0';
		jQuery( '#wppfm-feed-currency-selector' ).val( currencyVal );
	}

	// get the link to the update schedule selectors
	var hoursSelector  = document.getElementById( 'update-schedule-hours' );
	var minutesSelector = document.getElementById( 'update-schedule-minutes' );
	var freqSelector = document.getElementById( 'update-schedule-frequency' );

	// set the values of the update schedule selectors
	hoursSelector.value  = schedule[ 1 ];
	minutesSelector.value = schedule[ 2 ];
	freqSelector.value = schedule[ 3 ] ? schedule[ 3 ] : '1'; // standard setting is once a day

	// set the layout of the update schedule selectors
	wppfm_setScheduleSelector( schedule[ 0 ], schedule[ 3 ] );
}

/**
 * Category Selector Control.
 *
 * Generates the html for a category selector.
 *
 * @since    0.1.1
 * @access    public
 *
 * @param {array} categories with strings of categories to be placed in the selector
 *
 * @returns {string} Html for a category selector
 */
function wppfm_categorySelectCntrl( categories ) {
	//noinspection JSUnresolvedVariable
	var htmlCode = '<option value="0">' + wppfm_feed_settings_form_vars.select_a_sub_category + '</option>';
	if ( '1' === _feedHolder['channel'] ) {
		//noinspection JSUnresolvedVariable
		htmlCode += '<option value="cat_number">' + wppfm_feed_settings_form_vars.select_by_category_number + '</option>';
	}

	for ( var i = 0; i < categories.length; i ++ ) {
		htmlCode += '<option value="' + categories[ i ] + '">' + categories[ i ] + '</option>';
	}

	return htmlCode;
}

function wppfm_setMerchantSelector( isNew, channel ) {
	var merchantsSelectorElement = jQuery( '#wppfm-merchants-selector' );
	var merchantsTextElement     = jQuery( '#selected-merchant' );

	if ( isNew ) { // if the feed is new, show the selector
		merchantsSelectorElement.show();
		merchantsSelectorElement.val( channel );
		merchantsTextElement.hide();
		wppfm_showMerchantSpecificationLink();
	} else { // if the feed is not new, show the text
		merchantsSelectorElement.hide();
		merchantsTextElement.html( jQuery( '#wppfm-merchants-selector option[value=\'' + channel + '\']' ).text() );
		merchantsTextElement.show();
	}
}

function wppfm_setGoogleFeedTypeSelector( isNew, feedType ) {
	var feedTypeSelectorElement = jQuery( '#wppfm-feed-types-selector' );
	var feedTypeTextElement     = jQuery( '#wppfm-selected-google-feed-type' );

	if ( isNew ) { // if the feed is show the selector
		feedTypeSelectorElement.show();
		feedTypeSelectorElement.val( feedType );
		feedTypeTextElement.hide();
	} else { // if the feed is not new show the text
		feedTypeSelectorElement.hide();
		feedTypeTextElement.html( jQuery( '#wppfm-feed-types-selector option[value=\'' + feedType + '\']' ).text() );
		feedTypeTextElement.show();

		if ( '5' === feedType ) { // if the feed is a Dynamic Remarketing feed, start with all required attributes hidden.
			wppfm_hideAllRequiredAttributes();
		}
	}
}

/**
 * Edits the mapping of the feed categories to the shop categories
 *
 * @param {string} id
 */
function wppfm_editCategoryMapping( id ) {
	wppfm_showWorkingSpinner();
	var channel               = _feedHolder[ 'channel' ];
	var language              = wppfm_channelCountryCode( channel );
	var currentCategoryString = jQuery( '#category-text-span-' + id ).text();
	var currentCategoryArray  = currentCategoryString.split( ' > ' );

	//noinspection JSUnresolvedVariable
	if ( currentCategoryString && currentCategoryString !== wppfm_feed_settings_form_vars.map_to_default_category ) {
		// the current Shop Category already has a category set. So keep that category as the default setting
		wppfm_getCategoryListsFromString( channel, currentCategoryString, language, function( categories ) {
			wppfm_showCategoryMappingSelectors( id, JSON.parse( categories ), currentCategoryArray );
			wppfm_hideWorkingSpinner();
		} );
	} else {
		if ( ! wppfm_isCustomChannel( channel ) ) {
			wppfm_getCategoryListsFromString( channel, '', language, function( categories ) {
				wppfm_showCategoryMappingSelectors( id, JSON.parse( categories ), '' );
				wppfm_hideWorkingSpinner();
			} );
			//			wppfm_getCategoryListsFromString( channel, _feedHolder['mainCategory'], language, function ( categories ) {
			//				wppfm_showCategoryMappingSelectors( id, JSON.parse( categories ), _feedHolder['mainCategory'].split( ' > ' ) );
			//				wppfm_hideFeedSpinner();
			//			} );
		} else {
			jQuery( '#feed-category-' + id ).html( wppfm_freeCategoryInputCntrl( 'mapping', id, _feedHolder[ 'mainCategory' ] ) );
			jQuery( '#category-selector-catmap-' + id ).hide();
			wppfm_hideWorkingSpinner();
		}
	}
}

/**
 * Adds category selectors to the Category Mapping section
 */
function wppfm_showCategoryMappingSelectors( id, catList, currentCategoryArray ) {
	for ( var i = 0; i < catList.length; i ++ ) {
		var catMapSelectorElement = jQuery( '#catmap-' + id + '_' + i );

		if ( catList[ i ] && catList[ i ].length > 0 ) {
			// if no category is the selected category, select the first item in the list
			var selectedCategory = currentCategoryArray[ i ] ? currentCategoryArray[ i ] : '0';
			// append the category selector code to the select item
			catMapSelectorElement.append( wppfm_categorySelectCntrl( catList[ i ] ) );
			catMapSelectorElement.val( selectedCategory );
			catMapSelectorElement.show();
		}

		catMapSelectorElement.prop( 'disabled', false );
	}

	jQuery( '#feed-category-' + id ).html( '' ); // remove the category string, so it can be replaced by the selector
	jQuery( '#category-selector-catmap-' + id ).show(); // now show the category selectors
}

function wppfm_activateOptionalFieldRow( level, name ) {
	var attributeId = _feedHolder.getAttributeIdByName( name );

	// register the new optional field as an active input
	_feedHolder.activateAttribute( attributeId );

	if ( _feedHolder[ 'attributes' ][ attributeId ][ 'advisedSource' ] ) { // when the selected input has an advised value,
		_feedHolder.setSourceValue( attributeId, level, _feedHolder[ 'attributes' ][ attributeId ][ 'advisedSource' ] );
	}

	// get the html code for the new source row that needs to be added to the form
	var code = wppfm_fieldRow( _feedHolder[ 'attributes' ][ attributeId ], true );
	//noinspection DuplicatedCode
	var ind  = - 1;

	// find the index of the selected item in the correct undefined outputs list
	if ( level === 3 ) {
		ind = _undefinedRecommendedOutputs.indexOf( name );
	} else if ( level === 4 ) {
		ind = _undefinedOptionalOutputs.indexOf( name );
	}

	// if it can not be used more than once, remove it from the undefined output list
	if ( ind > - 1 ) {
		if ( level === 3 ) {
			_undefinedRecommendedOutputs.splice( ind, 1 );
		} else if ( level === 4 ) {
			_undefinedOptionalOutputs.splice( ind, 1 );
		}
	}

	// store the removed item in the defined outputs list
	if ( level === 3 ) {
		_definedRecommendedOutputs.push( _feedHolder[ 'attributes' ][ attributeId ][ 'fieldName' ] );
		_definedRecommendedOutputs.sort();
	} else if ( level === 4 ) {
		_definedOptionalOutputs.push( _feedHolder[ 'attributes' ][ attributeId ][ 'fieldName' ] );
		_definedOptionalOutputs.sort();
	}

	jQuery( '#output-field-cntrl-' + level + ' option[value=\'' + name + '\']' ).remove();

	jQuery( '#output-field-cntrl-' + level ).val( 'no-value' );

	if ( level === 3 ) {
		var newRecommendedRowElement = jQuery( '#new-recommended-row' );
		newRecommendedRowElement.append( code );
		newRecommendedRowElement.show();
	} else if ( level === 4 ) {
		jQuery( '#new-optional-row' ).append( code );
	}
}

function wppfm_activateCustomFieldRow( fieldName ) {
	if ( ! _feedHolder.checkIfCustomNameExists( fieldName ) ) { // prevent doubles
		var attributeId      = _feedHolder.getAttributeIdByName( fieldName );
		var newCustomRowItem = jQuery( '#new-custom-row' );

		// register the new custom field
		_feedHolder.addAttribute( attributeId, fieldName, '', undefined, '5', true, 0, 0, 0, 0 );

		newCustomRowItem.append( wppfm_fieldRow( _feedHolder[ 'attributes' ][ attributeId ], true ) );
		newCustomRowItem.show();
	} else {
		//noinspection JSUnresolvedVariable
		alert( wppfm_feed_settings_form_vars.duplicated_field.replace( '%fieldname%', fieldName ) );
	}

	// clear the input field
	jQuery( '#custom-output-title-input' ).val( '' );
}

function wppfm_setStaticValue( attributeId, conditionLevel, combinationLevel ) {
	var staticValue = jQuery( '#static-input-field-' + attributeId + '-' + conditionLevel + '-' + combinationLevel ).val();

	if ( staticValue === undefined ) {

		//staticValue = jQuery( '#static-condition-input-' + attributeId + '-' + conditionLevel + '-' + combinationLevel + ' option:selected' ).text();
		staticValue = jQuery( '#static-condition-input-' + attributeId + '-' + conditionLevel + '-' + combinationLevel + ' option:selected' ).val();
	}

	// store the changed static value in the feed
	_feedHolder.setStaticAttributeValue( attributeId, conditionLevel, combinationLevel, staticValue );
}

function wppfm_setIdentifierExistsDependencies() {

	var staticValue = jQuery( '#static-input-field-34-0' ).val();

	if ( staticValue === undefined ) {
		staticValue = jQuery( '#static-condition-input-34-0 option:selected' ).val();
	}

	switch ( staticValue ) {

		case 'true':
			wppfm_resetOutputField( 12, 1, true );
			wppfm_resetOutputField( 13, 1, true );
			break;

		case 'false':
			wppfm_resetOutputField( 12, 3, false );
			wppfm_resetOutputField( 13, 3, false );
			break;

		default:
			break;
	}
}

function wppfm_resetOutputField( fieldId, level, isActive ) {

	var oldLevel = _feedHolder[ 'attributes' ][ fieldId ][ 'fieldLevel' ];

	// set the attribute status
	_feedHolder[ 'attributes' ][ fieldId ][ 'fieldLevel' ] = level;
	_feedHolder[ 'attributes' ][ fieldId ][ 'isActive' ]   = isActive;

	//show or hide the output field
	var outputField = wppfm_fieldRow( _feedHolder[ 'attributes' ][ fieldId ], false );
	var fieldName   = _feedHolder[ 'attributes' ][ fieldId ][ 'fieldName' ];

	if ( isActive ) {

		jQuery( '#wppfm-required-field-table' ).append( outputField );
		jQuery( '#output-field-cntrl-' + oldLevel + ' option[value=\'' + fieldName + '\']' ).remove();
		jQuery( '#wppfm-recommended-field-table #row-' + fieldId ).remove();

	} else {

		jQuery( '#row-' + fieldId ).replaceWith( '' );
		jQuery( '#output-field-cntrl-' + level ).append( '<option value="' + fieldName + '">' + fieldName + '</option>' );
	}
}

/**
 * Returns the html code for a single field table row
 *
 * @param {array} attributeData
 * @param {boolean} removable
 * @returns {String} containing the html
 */
function wppfm_fieldRow( attributeData, removable ) {
	var rowId          = attributeData[ 'rowId' ];
	//noinspection DuplicatedCode
	var sourceRowsData = _feedHolder.getSourceObject( rowId );
	var nrOfSources    = countSources( sourceRowsData.mapping );

	// add an extra row when there is a condition in the last source row and an advised source but no user set source
	if ( sourceRowsData.advisedSource && wppfm_hasExtraSourceRow( nrOfSources, sourceRowsData.mapping ) ) {
		nrOfSources ++;
	}

	// row wrapper
	var htmlCode = '<div class="wppfm-attribute-row-wrapper wppfm-attribute-' + sourceRowsData.fieldName + '-row" id="row-' + rowId + '">';

	for ( var i = 0; i < nrOfSources; i ++ ) {
		htmlCode += wppfm_addFeedSourceRow( rowId, i, sourceRowsData, _feedHolder.channel, removable );
	}

	for ( var j = 0; j < sourceRowsData.changeValues.length; j ++ ) {
		if ( sourceRowsData.changeValues[ 0 ] ) {
			// add the change value editor fields
			htmlCode += wppfm_valueEditor( sourceRowsData.rowId, j, j, sourceRowsData.changeValues );
		}
	}

	// end the row and start a new one
	htmlCode += '</div>';

	return htmlCode;
}

function wppfm_addSourceDataAndQueriesColumn( sourceLevel, sourceRowsData ) {

	var htmlCode                   = '';
	var levelString                = sourceRowsData.rowId + '-' + sourceLevel;
	var sourceValue                = wppfm_getMappingSourceValue( sourceRowsData.mapping, sourceLevel );
	var combinedValue              = wppfm_getMappingCombinedValue( sourceRowsData.mapping, sourceLevel );
	var staticValue                = wppfm_getMappingStaticValue( sourceRowsData.mapping, sourceLevel );
	var sourceIsCategory           = ! ! sourceRowsData.customCondition;
	var sourceMappingHasSourceData = ! ! (
		sourceValue || combinedValue || staticValue
	);
	var channel                    = _feedHolder[ 'channel' ].toString();
	var conditions                 = wppfm_getMappingConditions( sourceRowsData.mapping, sourceLevel );
	var conditionsCounter          = wppfm_countObjectItems( conditions );

	// wrap the source controls
	htmlCode += '<div class="wppfm-attribute-column source-selector wppfm-colw wppfm-col30w" id="source-select-' + levelString + '">';

	if ( sourceIsCategory ) {
		htmlCode += wppfm_categorySource();
	} else if (( sourceRowsData.advisedSource
		&& ! sourceMappingHasSourceData
		&& sourceRowsData.advisedSource !== wppfm_feed_settings_form_vars.fill_with_static_value )
		|| sourceRowsData.advisedSource === sourceValue ) {

		// remove underscore where applicable
		//var advisedSourceWithoutUnderscore = sourceRowsData.advisedSource.charAt(0) === '_' ? sourceRowsData.advisedSource.substr(1) : sourceRowsData.advisedSource;
		var advisedSourceLabel = jQuery.grep( _inputFields, function( e ) {
			return e.value === sourceRowsData.advisedSource;
		} );
		advisedSourceLabel = advisedSourceLabel[ 0 ] ? advisedSourceLabel[ 0 ].label : sourceRowsData.advisedSource;

		htmlCode += wppfm_advisedSourceSelector( sourceRowsData.rowId, sourceLevel, advisedSourceLabel, channel, sourceRowsData.mapping );
	} else if ( ( ! sourceRowsData.advisedSource && ! sourceValue )
		|| sourceMappingHasSourceData
		|| sourceRowsData.advisedSource === wppfm_feed_settings_form_vars.fill_with_static_value ) {

		//noinspection DuplicatedCode
		htmlCode += wppfm_inputFieldCntrl( sourceRowsData.rowId, sourceLevel, sourceValue, staticValue, sourceRowsData.advisedSource, combinedValue, wppfm_isCustomChannel( channel ) );

		if ( staticValue ) {
			htmlCode += wppfm_feedStaticValueSelector( sourceRowsData.fieldName, sourceRowsData.rowId, sourceLevel, 0, staticValue, channel );
		}

		if ( combinedValue ) {
			htmlCode += wppfm_combinedField( sourceRowsData.rowId, sourceLevel, 0, combinedValue, false, channel );
		}
	}

	// end the source control wrapper
	htmlCode += '</div>';

	htmlCode += '<div class="wppfm-attribute-column condition-selector wppfm-colw" id="condition-data-' + levelString + '">';

	// and now fill the condition controls
	if ( ! sourceIsCategory && ! conditions ) {

		htmlCode += '<div class="condition-wrapper" id="condition-' + levelString + '-0">';
		htmlCode += wppfm_forAllProductsCondition( sourceRowsData.rowId, sourceLevel, 'initial' );
		htmlCode += '</div>';
	} else if ( conditions ) {
		for ( var i = 0; i < conditionsCounter; i ++ ) {
			htmlCode += wppfm_conditionSelectorCode( sourceRowsData.rowId, sourceLevel, i, conditionsCounter, conditions[ i ] );
		}
	}

	htmlCode += '</div>';

	return htmlCode;
}

function wppfm_addCombinedField( id, sourceLevel, combinedLevel ) {

	var allFilled                    = true;
	var combinedFieldsControlElement = jQuery( '#combined-input-field-cntrl-' + id + '-' + sourceLevel + '-' + combinedLevel );

	for ( var i = 0; i < combinedLevel; i ++ ) {

		// do all fields have a selected value?
		if ( combinedFieldsControlElement.val() === 'select' ) {

			allFilled = false;
			i         = combinedLevel;
		}

		if ( combinedFieldsControlElement.val() === 'static'
		     && jQuery( '#static-input-field-' + id + '-' + sourceLevel + '-' + combinedLevel ).val() === '' ) {

			allFilled = false;
			i         = combinedLevel;
		}
	}

	if ( allFilled ) {
		var combinedValueObject = _feedHolder[ 'attributes' ][ id ][ 'value' ] ? JSON.parse( _feedHolder[ 'attributes' ][ id ][ 'value' ] ) : {};
		var combinedValuePart   = 'm' in combinedValueObject ? combinedValueObject.m : {};
		var combinedValue       = wppfm_getMappingCombinedValue( combinedValuePart, sourceLevel );
		var manualAdd           = combinedLevel > 0;

		jQuery( '#source-select-' + id + '-' + sourceLevel ).append( wppfm_combinedField( id, sourceLevel, combinedLevel, combinedValue, manualAdd ) );

		jQuery( '#add-combined-field-' + id + '-' + sourceLevel ).hide();
	} else {
		//noinspection JSUnresolvedVariable
		alert( wppfm_feed_settings_form_vars.select_all_source_fields_warning );
	}
}

function wppfm_combinedField( rowId, sourceLevel, combinedLevel, fields, manualAdd ) {

	jQuery( '#combined-wrapper-' + rowId + '-' + sourceLevel ).remove(); // reset the combined wrapper field

	var fieldsArray = wppfm_splitCombinedFieldElements( fields );

	var fieldsLength = fields ? fieldsArray.length : 2; // if no field data is given, then show two empty input selectors

	if ( manualAdd ) {
		fieldsLength ++;
	}

	var htmlCode = '<div class="combined-wrapper" id="combined-wrapper-' + rowId + '-' + sourceLevel + '">';

	// loop through the field items in the fields string
	for ( var i = 0; i < fieldsLength; i ++ ) {
		var str = fieldsArray[ i ];
		var ind = str ? str.indexOf( '#' ) : 0;
		var fieldSplit;

		if ( str ) {
			fieldSplit = str.substring( 0, ind ) !== 'static' ? [ str.substring( 0, ind ), str.substring( ind + 1 ) ] : [ '0', fieldsArray[ i ] ];
		} else {
			fieldSplit = [];
		}

		htmlCode += wppfm_combinedCntrl( rowId, sourceLevel, i + 1, fieldsLength, 'delete', fieldSplit, _feedHolder[ 'channel' ] );
	}

	htmlCode += '</div>';

	return htmlCode;
}

function wppfm_combinedCntrl( rowId, sourceLevel, combinationLevel, nrQueries, type, valueArray, channel ) {

	var htmlCode  = '';
	var fieldName = _feedHolder[ 'attributes' ][ rowId ][ 'fieldName' ];

	htmlCode += '<span class="combined-field-row" id="combined-field-row-' + rowId + '-' + sourceLevel + '-' + combinationLevel + '">';

	if ( combinationLevel > 1 ) {

		htmlCode += wppfm_combinedSeparatorCntrl( rowId, sourceLevel, combinationLevel, valueArray[ 0 ] );
	}

	// draw the control
	htmlCode += wppfm_combinedInputFieldCntrl( rowId, sourceLevel, combinationLevel, valueArray[ 1 ], fieldName, channel );

	if ( type === 'initialize' ) {

		// add an extra control
		htmlCode += '<span class="combined-field-row" id="combined-field-row">';
		htmlCode += wppfm_combinedSeparatorCntrl( rowId, sourceLevel, combinationLevel, valueArray[ 0 ] );
		htmlCode += wppfm_combinedInputFieldCntrl( rowId, sourceLevel, 2, '', fieldName, channel );
		//        htmlCode += '<span id="static-input-combined-field-' + rowId + '-' + sourceLevel + '-2"></span>';
		htmlCode += '<div class="wppfm-select-control wppfm-static-value-control" id="static-value-control-' + rowId + '-' + sourceLevel + '-2"></div>';
	}

	if ( combinationLevel > 2 ) {

		// draw the "remove" button
		htmlCode += '<span id="remove-combined-field-' + rowId + '-' + sourceLevel + '-' + combinationLevel + '" style="display:initial">';
		htmlCode += '(<a class="remove-combined-field wppfm-btn wppfm-btn-small" href="javascript:void(0)"';
		htmlCode += ' onclick="wppfm_removeCombinedField(' + rowId + ', ' + sourceLevel + ', ' + combinationLevel + ')">' + wppfm_feed_settings_form_vars.remove + '</a>)</span>';
	}

	if ( combinationLevel === 2 || combinationLevel >= nrQueries ) {

		if ( combinationLevel === 1 ) {
			combinationLevel ++;
		}

		// draw the "add" button
		if ( combinationLevel >= nrQueries ) {
			htmlCode += '<span id="add-combined-field-' + rowId + '-' + sourceLevel + '-' + combinationLevel + '" style="display:initial">';
			htmlCode += '(<a class="add-combined-field wppfm-btn wppfm-btn-small" href="javascript:void(0)"';
			htmlCode += ' onclick="wppfm_addCombinedField(' + rowId + ', ' + sourceLevel + ', ' + combinationLevel + ')">' + wppfm_feed_settings_form_vars.add + '</a>)</span>';
		}
	}

	htmlCode += '</span>';

	return htmlCode;
}

// TODO: this function looks like the wppfm_ifValueQuerySelector() function. Maybe I could combine these two?
function wppfm_ifConditionSelector( rowId, sourceLevel, conditionLevel, numberOfQueries, queryObject ) {

	var htmlCode          = '';
	var query             = wppfm_isEmptyQueryObject( queryObject ) ? wppfm_makeCleanQueryObject() : queryObject;
	//noinspection JSUnresolvedVariable
	var conditionSelector = wppfm_feed_settings_form_vars.if_pref + ' ';

	if ( conditionLevel > 1 ) {

		conditionSelector = query.preCondition === '1'
			? '<select id="sub-condition-' + rowId + '-' + sourceLevel + '-' + conditionLevel + '" onchange="wppfm_storeCondition(' + rowId + ', ' + sourceLevel + ', ' + conditionLevel + ')"><option value="2">' + wppfm_feed_settings_form_vars.or + '</option><option value="1" selected>' + wppfm_feed_settings_form_vars.and + '</option></select>'
			: '<select id="sub-condition-' + rowId + '-' + sourceLevel + '-' + conditionLevel + '" onchange="wppfm_storeCondition(' + rowId + ', ' + sourceLevel + ', ' + conditionLevel + ')"><option value="2" selected>' + wppfm_feed_settings_form_vars.or + '</option><option value="1">' + wppfm_feed_settings_form_vars.and + '</option></select>';
	}

	var storeConditionFunctionString = 'wppfm_storeCondition(' + rowId + ', ' + sourceLevel + ', ' + conditionLevel + ')';

	htmlCode += '<div class="condition-wrapper" id="condition-' + rowId + '-' + sourceLevel + '-' + conditionLevel + '">';
	htmlCode += conditionSelector;
	htmlCode += wppfm_conditionFieldCntrl( rowId, sourceLevel, conditionLevel, - 1, 'input-field-cntrl', query.source, storeConditionFunctionString );
	htmlCode += wppfm_conditionQueryCntrl( rowId, sourceLevel, conditionLevel, - 1, 'query-condition', 'wppfm_queryConditionChanged', query.condition );

	htmlCode += '<input type="text" onchange="wppfm_storeCondition(' + rowId + ', ' + sourceLevel + ', ' + conditionLevel + ')" name="condition-value" id="condition-value-';
	htmlCode += rowId + '-' + sourceLevel + '-' + conditionLevel + '" value="' + query.value + '"';

	if ( queryObject.condition !== '4' && queryObject.condition !== '5' ) {

		htmlCode += ' style="display:initial">';
	} else {

		htmlCode += ' style="display:none;">';
	}

	if ( queryObject.condition !== '14' ) {

		htmlCode += '<span id="condition-and-value-' + rowId + '-' + sourceLevel + '-' + conditionLevel
		            + '" style="display:none;"> ' + wppfm_feed_settings_form_vars.and + ' <input type="text" name="condition-and-value" onchange="wppfm_storeCondition('
		            + rowId + ', ' + sourceLevel + ', ' + conditionLevel + ')" id="condition-and-value-input-' + rowId + '-' + sourceLevel + '-' + conditionLevel + '"></span>';
	} else {

		htmlCode += '<span id="condition-and-value-' + rowId + '-' + sourceLevel + '-' + conditionLevel
		            + '" style="display:initial"> ' + wppfm_feed_settings_form_vars.and + ' <input type="text" name="condition-and-value" onchange="wppfm_storeCondition('
		            + rowId + ', ' + sourceLevel + ', ' + conditionLevel + ')" id="condition-and-value-input-' + rowId + '-' + sourceLevel + '-' + conditionLevel + '" value="' + queryObject.endValue + '"></span>';
	}

	htmlCode += '(<a class="remove-edit-condition wppfm-btn wppfm-btn-small" href="javascript:void(0)" id="wppfm-remove-attribute-query-' + rowId + '-' + sourceLevel + '-' + conditionLevel;
	htmlCode += '" onclick="wppfm_removeCondition(' + rowId + ', ' + sourceLevel + ', ' + (
		conditionLevel - 1
	) + ')">' + wppfm_feed_settings_form_vars.remove + '</a>)';

	if ( conditionLevel >= numberOfQueries ) {

		htmlCode += '<span id="add-edit-condition-' + rowId + '-' + sourceLevel + '-' + conditionLevel + '" style="display:initial"> (<a class="add-edit-condition wppfm-btn wppfm-btn-small" href="javascript:void(0)';
		htmlCode += '" id="wppfm-add-attribute-query-row-' + rowId +'-' + sourceLevel + '-' + conditionLevel + '"  onclick="wppfm_addCondition(' + rowId + ', ' + sourceLevel + ', ' + conditionLevel + ', \'\')">' + wppfm_feed_settings_form_vars.add + '</a>)</span>';
	}

	htmlCode += '</div>';

	return htmlCode;
}

function wppfm_orConditionSelector( rowId, inputsObject ) {
	var inputSplit  = inputsObject.split( '#' );
	var staticField = '';
	var html        = '';

	if ( inputSplit[ 0 ] === 'static' ) {

		staticField  = wppfm_displayCorrectStaticField( rowId, '1', '0', _feedHolder[ 'channel' ], _feedHolder[ 'attributes' ][ rowId ][ 'fieldName' ], inputSplit[ 1 ] );
		inputsObject = 'static';
	}

	html += 'or ';
	html += wppfm_alternativeInputFieldCntrl( rowId, inputsObject );
	html += '<span id="alternative-static-input-' + rowId + '">' + staticField + '</span>';
	html += ' for all other products ';
	html += '(<a class="edit-prod-source wppfm-btn wppfm-btn-small" href="javascript:void(0)" id="edit-prod-source-' + rowId + '" onclick="addSource(' + rowId + ', 0, \'\')">';
	html += wppfm_feed_settings_form_vars.edit + '</a>)';

	return html;
}

function wppfm_ifValueQuerySelector( rowId, sourceLevel, queryLevel, queryObject, lastValue ) {

	var htmlCode                                 = '';
	var query                                    = wppfm_isEmptyQueryObject( queryObject ) ? wppfm_makeCleanQueryObject() : queryObject;
	var querySelector                            = 'if ';
	var queryValueConditionChangedFunctionString = 'wppfm_queryValueConditionChanged(' + rowId + ', ' + sourceLevel + ', ' + queryLevel + ', 0)';

	if ( queryLevel > 1 ) {

		querySelector = query.preCondition === '1'
			? '<select id="value-options-sub-query-' + rowId + '-' + sourceLevel + '-' + queryLevel + '" onchange="wppfm_storeValueCondition(' + rowId + ', ' + sourceLevel + ', ' + queryLevel + ')"><option value="2">' + wppfm_feed_settings_form_vars.or + '</option><option value="1" selected>' + wppfm_feed_settings_form_vars.and + '</option></select>'
			: '<select id="value-options-sub-query-' + rowId + '-' + sourceLevel + '-' + queryLevel + '" onchange="wppfm_storeValueCondition(' + rowId + ', ' + sourceLevel + ', ' + queryLevel + ')"><option value="2" selected>' + wppfm_feed_settings_form_vars.or + '</option><option value="1">' + wppfm_feed_settings_form_vars.and + '</option></select>';
	}

	htmlCode += '<div class="value-options-query-selector" id="value-options-condition-' + rowId + '-' + sourceLevel + '-' + queryLevel + '">';
	htmlCode += querySelector;
	htmlCode += wppfm_conditionFieldCntrl( rowId, sourceLevel, queryLevel, - 1, 'value-options-input-field-cntrl', query.source, queryValueConditionChangedFunctionString );
	htmlCode += wppfm_conditionQueryCntrl( rowId, sourceLevel, queryLevel, 0, 'value-query-condition', 'wppfm_queryValueConditionChanged', query.condition );

	if ( queryObject.condition !== '4' && queryObject.condition !== '5' ) {
		htmlCode += '<input type="text" onchange="wppfm_storeValueCondition(' + rowId + ', ' + sourceLevel + ', ' + queryLevel + ')" name="value-options-condition-value" id="value-options-condition-value-';
		htmlCode += rowId + '-' + sourceLevel + '-' + queryLevel + '" value="' + query.value + '" style="display:initial">';
	}

	if ( queryObject.condition !== '14' ) {

		htmlCode += '<span id="value-options-condition-and-value-' + rowId + '-' + sourceLevel + '-' + queryLevel + '" style="display:none;"> ' + wppfm_feed_settings_form_vars.and + ' ';
		htmlCode += '<input id="value-options-condition-and-value-input-' + rowId + '-' + sourceLevel + '-' + queryLevel + '" ';
		htmlCode += 'type="text" onchange="wppfm_storeValueCondition(' + rowId + ', ' + sourceLevel + ', ' + queryLevel + ')" name="value-condition-and-value"></span>';
	} else {

		htmlCode += '<span id="value-options-condition-and-value-' + rowId + '-' + sourceLevel + '-' + queryLevel + '" style="display:initial"> ' + wppfm_feed_settings_form_vars.and + ' ';
		htmlCode += '<input id="value-options-condition-and-value-input-' + rowId + '-' + sourceLevel + '-' + queryLevel + '" ';
		htmlCode += 'type="text" onchange="wppfm_storeValueCondition(' + rowId + ', ' + sourceLevel + ', ' + queryLevel + ')" name="value-condition-and-value" value="' + queryObject.endValue + '"></span>';
	}

	htmlCode += '(<a class="remove-edit-condition wppfm-btn wppfm-btn-small" href="javascript:void(0)" id="wppfm-remove-attribute-query-' + rowId + '-' + sourceLevel + '-' + queryLevel;
	htmlCode += '" onclick="wppfm_removeValueQuery(' + rowId + ', ' + sourceLevel + ', ' + queryLevel + ')">' + wppfm_feed_settings_form_vars.remove + '</a>)';

	if ( lastValue ) {

		htmlCode += '<span id="value-options-add-edit-condition-' + rowId + '-' + sourceLevel + '-' + queryLevel + '" style="display:initial">(<a class="add-edit-condition wppfm-btn wppfm-btn-small" href="javascript:void(0)';
		htmlCode += '" id="wppfm-add-attribute-query-row-' + rowId +'-' + sourceLevel + '-' + queryLevel + '"  onclick="wppfm_addValueQuery(' + rowId + ', ' + sourceLevel + ', ' + queryLevel + ', \'\')">' + wppfm_feed_settings_form_vars.add + '</a>)</span>';
	}

	htmlCode += '</div>';

	return htmlCode;
}

function wppfm_addOptionalFieldRow( level ) {

	var htmlCode = '';
	var addRow   = false;

	if ( level === 3 ) {

		addRow = _undefinedRecommendedOutputs.length > 0;
	} else if ( level === 4 ) {

		addRow = _undefinedOptionalOutputs.length > 0;
	} else if ( level === 5 ) {

		//addRow = _undefinedCustomOutputs.length > 0 ? true : false;
		addRow = true;
	}

	if ( addRow === true ) {

		//noinspection DuplicatedCode
		if ( level === 3 ) {
			htmlCode += '<div class="wppfm-attribute-row-wrapper" id="new-recommended-row" style="display:none;"></div>';
		} else if ( level === 4 ) {
			htmlCode += '<div class="wppfm-attribute-row-wrapper" id="new-optional-row"></div>';
		} else if ( level === 5 ) {
			htmlCode += '<div class="wppfm-attribute-row-wrapper" id="new-custom-row"></div>';
		}

		htmlCode += '<div class="field-table-row-top">';
		htmlCode += '<div class="col2w" id="output-select-' + level;

		htmlCode += level !== 5 ? '" onchange="wppfm_changedOutputSelection(' + level + ')">' + wppfm_outputFieldCntrl( level ) :
			'">' + wppfm_customOutputFieldCntrl();

		htmlCode += '</div></div>';
	}

	return htmlCode;
}

//noinspection JSUnusedGlobalSymbols
/**
 * Is called when the user wants to edit an advised source. Changes the advised output string to a source selector
 *
 * @param {string} rowId string containing the id of the feed row
 * @param {string} sourceLevel string containing the source counter
 */
function wppfm_editOutput( rowId, sourceLevel ) {

	var htmlCode = '';

	htmlCode += wppfm_inputFieldCntrl( rowId, sourceLevel, '', '', _feedHolder[ 'attributes' ][ rowId ][ 'advisedSource' ], '', false );
	htmlCode += '<div class="field-table-row-combined-selection" id="combined-selectors-' + rowId + '" style="display:none;"></div>';

	jQuery( '#source-select-' + rowId + '-' + sourceLevel ).html( htmlCode );
}

//noinspection JSUnusedGlobalSymbols
function wppfm_removeCondition( rowId, sourceLevel, conditionLevel ) {

	// remove the selected query
	_feedHolder.removeValueConditionValue( rowId, sourceLevel, conditionLevel );

	var queries = _feedHolder.getAttributesQueriesObject( rowId, sourceLevel );

	var nrQueries = wppfm_countObjectItems( queries );

	// remove the old condition-wrappers
	for ( var t = 1; t < nrQueries + 2; t ++ ) {

		jQuery( '#condition-' + rowId + '-' + sourceLevel + '-' + t ).remove();
		//        jQuery( '#dummy-source-' + rowId + '-' + sourceLevel + '-' + t ).remove();
	}

	// update the form
	if ( nrQueries > 0 ) {

		for ( var i = 0; i < nrQueries; i ++ ) {

			wppfm_conditionCode( rowId, sourceLevel, i, queries[ i ], nrQueries );
		}

		jQuery( '#edit-conditions-' + rowId ).append( wppfm_orSelectorCode( rowId, '' ) );
	} else {

		var forAllProductsCode = '<div class="condition-wrapper" id="condition-' + rowId + '-' + sourceLevel + '-0">'
		                         + wppfm_forAllProductsCondition( rowId, sourceLevel, 'initial' ) + '</div>';

		//jQuery( forAllProductsCode ).insertAfter( '#source-select-' + rowId + '-' + sourceLevel );
		jQuery( '#condition-data-' + rowId + '-' + sourceLevel ).html( forAllProductsCode );

		// also remove the for all other products row
		jQuery( '#source-' + rowId + '-' + (
		        sourceLevel + 1
		) ).remove();
	}
}

//noinspection JSUnusedGlobalSymbols
function wppfm_removeValueQuery( rowId, sourceLevel, queryLevel ) {

	// remove the selected query
	_feedHolder.removeValueQueryValue( rowId, sourceLevel, queryLevel );

	var queries               = _feedHolder.getValueQueryValue( rowId, sourceLevel );
	var editConditionsElement = jQuery( '#edit-conditions-' + rowId );
	var nrQueries             = wppfm_countObjectItems( queries );

	// when the value is fully empty after removing the condition, deactivate the attribute
	if ( ! _feedHolder[ 'attributes' ][ rowId ][ 'value' ] ) {

		_feedHolder.deactivateAttribute( rowId );
	}

	// clean the old queries html
	jQuery( '#value-editor-queries-' + rowId + '-' + sourceLevel + '-0' ).empty();
	editConditionsElement.empty();

	// remove the condition and update the form
	if ( nrQueries > 0 ) {

		for ( var i = 0; i < nrQueries; i ++ ) {

			wppfm_addValueQuery( rowId, sourceLevel, i, queries[ i ] );
		}

		editConditionsElement.append( wppfm_orSelectorCode( rowId, '' ) );
	} else {

		jQuery( '#value-editor-input-query-span-' + rowId + '-' + sourceLevel + '-0' ).show();

		// as there are no queries, the other change value needs to be removed from the meta
		_feedHolder.removeEditValueValue( rowId, (
			sourceLevel + 1
		), 0 );

		// also remove the "for all other products" change value line
		jQuery( '#edit-value-span-' + rowId + '-' + (
		        sourceLevel + 1
		) + '-0' ).remove();
	}
}

//noinspection JSUnusedGlobalSymbols
function wppfm_removeRow( rowId, fieldName ) {
	// find the index of the selected item in the correct undefined outputs list
	var ind   = _definedRecommendedOutputs.indexOf( fieldName );
	var level = 3;

	if ( ind < 0 ) {
		ind   = _definedOptionalOutputs.indexOf( fieldName );
		level = 4;
	}

	// deactivate the attribute
	_feedHolder.deactivateAttribute( rowId );

	// remove the source row from the form
	jQuery( '#row-' + rowId ).remove();

	// reset the lists that contain the selected and non-selected output fields
	if ( level === 3 ) {
		_definedRecommendedOutputs.splice( ind, 1 );
		_undefinedRecommendedOutputs.push( fieldName );
		_undefinedRecommendedOutputs.sort();
	} else if ( level === 4 ) {
		_definedOptionalOutputs.splice( ind, 1 );
		_undefinedOptionalOutputs.push( fieldName );
		_undefinedOptionalOutputs.sort();
	}

	var outputFieldControlElement = jQuery( '#output-field-cntrl-' + level );
	outputFieldControlElement.empty();
	outputFieldControlElement.html( wppfm_outputFieldCntrl( level ) );
}

/**
 * Adds the condition controls to the attribute mapping rows.
 *
 * @param   {string}    rowId           Id of the row of the attribute.
 * @param   {string}    sourceLevel     Level of the source.
 * @param   {string}    conditionLevel  Level of the condition.
 * @param   {string}    query           String containing the query
 */
function wppfm_addCondition( rowId, sourceLevel, conditionLevel, query ) {

	if ( sourceLevel > 0 ) {
		if ( ! wppfm_sourceIsFilled( rowId, sourceLevel - 1, 1 ) ) {
			alert( wppfm_feed_settings_form_vars.fill_current_condition_warning );
			return;
		}
	}

	// show the condition controls
	var condition = wppfm_conditionCode( rowId, sourceLevel, conditionLevel, query, 0 );

	if ( condition ) {
		// and if it's the first condition level
		if ( '0' === String( conditionLevel ) ) {

			// add a "for all other products" row
			jQuery( wppfm_addFeedSourceRow( rowId, sourceLevel + 1, _feedHolder.getSourceObject( rowId ) ) ).insertAfter( '#source-' + rowId + '-' + sourceLevel );
		}

		// if this input has an advised value, then store this advised value in the metadata
		if ( _feedHolder[ 'attributes' ][ rowId ][ 'advisedSource' ] ) {

			_feedHolder.setSourceValue( rowId, (
				sourceLevel + 1
			), _feedHolder[ 'attributes' ][ rowId ][ 'advisedSource' ] );
		}
	}
}

function wppfm_conditionCode( rowId, sourceLevel, conditionLevel, query, nrQueries ) {

	if ( query || wppfm_sourceIsFilled( rowId, sourceLevel, conditionLevel ) ) {

		// get the data from the selection fields (only used when query is not empty)
		var queryCondition = jQuery( '#query-condition-' + rowId + '-' + sourceLevel + '-' + conditionLevel ).val();
		var inputField     = jQuery( '#input-field-cntrl-' + rowId + '-' + sourceLevel + '-' + conditionLevel ).val();
		var conditionValue = jQuery( '#condition-value-' + rowId + '-' + sourceLevel + '-' + conditionLevel ).val();
		var wait           = false;

		if ( queryCondition !== '4' && queryCondition !== '5' && queryCondition !== '14' ) {

			if ( inputField === 'select' || conditionValue === '' ) {

				wait = true;
			}
		} else if ( queryCondition === 4 || queryCondition === 5 ) {

			if ( inputField === 'select' ) {

				wait = true;
			}
		}

		if ( wait === false || conditionLevel === 0 ) {

			_feedHolder.incrNrQueries( rowId );

			var queryObject = wppfm_queryStringToQueryObject( query );

			if ( conditionLevel === 0 ) {
				jQuery( '#condition-' + rowId + '-' + sourceLevel + '-0' ).remove();
			}

			// replaces the "for all products" with a new empty condition row
			jQuery( '#condition-data-' + rowId + '-' + sourceLevel ).append( wppfm_ifConditionSelector( rowId, sourceLevel, conditionLevel + 1, nrQueries, queryObject ) );

			if ( conditionLevel > 0 ) {
				// remove the "add" button from the previous condition so only the last condition has an "add" button
				jQuery( '#add-edit-condition-' + rowId + '-' + sourceLevel + '-' + conditionLevel ).remove();
			}

			return true;
		} else {
			//noinspection JSUnresolvedVariable
			alert( wppfm_feed_settings_form_vars.fill_current_condition_warning );

			return false;
		}
	} else {
		//noinspection JSUnresolvedVariable
		alert( wppfm_feed_settings_form_vars.select_a_source_field_warning );

		return false;
	}
}

function wppfm_addValueQuery( rowId, sourceLevel, queryLevel, query ) {

	if ( wppfm_sourceIsFilled( rowId, sourceLevel, queryLevel ) ) {

		// get the data from the selection fields (only used when query is not empty)
		var queryCondition = jQuery( '#value-query-condition-' + rowId + '-' + sourceLevel + '-' + queryLevel + '-0' ).val();
		var inputField     = jQuery( '#value-options-input-field-cntrl-' + rowId + '-' + sourceLevel + '-' + queryLevel ).val();
		var conditionValue = jQuery( '#value-options-condition-value-' + rowId + '-' + sourceLevel + '-' + queryLevel ).val();
		var wait           = false;

		if ( queryCondition !== '4' && queryCondition !== '5' && queryCondition !== '14' ) {

			if ( inputField === 'select' || conditionValue === '' ) {

				wait = true;
			}
		} else if ( queryCondition === 4 || queryCondition === 5 ) {

			if ( inputField === 'select' ) {

				wait = true;
			}
		}

		if ( wait === false || sourceLevel === 0 ) {

			//_feed.incrNrQueries( id );

			var queryArray = wppfm_queryStringToQueryObject( query );

			jQuery( '#value-options-add-edit-condition-' + rowId + '-' + sourceLevel + '-' + queryLevel ).hide(); // hides the add button on this condition level
			jQuery( '#value-editor-queries-' + rowId + '-' + sourceLevel + '-0' ).append( wppfm_ifValueQuerySelector( rowId, sourceLevel, queryLevel + 1, queryArray, true ) );
		} else {
			//noinspection JSUnresolvedVariable
			alert( wppfm_feed_settings_form_vars.fill_current_condition_warning );
		}
	} else {
		//noinspection JSUnresolvedVariable
		alert( wppfm_feed_settings_form_vars.elect_a_source_field_warning );
	}
}

function wppfm_conditionSelectorCode( id, sourceLevel, level, nrQueries, query ) {

	var queryArray = wppfm_queryStringToQueryObject( query );

	return wppfm_ifConditionSelector( id, sourceLevel, level + 1, nrQueries, queryArray );
}

function wppfm_orSelectorCode( id, alternativeInputs ) {

	let alternative = '';

	if ( alternativeInputs ) {

		for ( let altKey in alternativeInputs ) {

			alternative = alternativeInputs[ altKey ];
		}
	}

	return '<div class="or-selector" id="or-selector-' + id + '">' + wppfm_orConditionSelector( id, alternative ) + '</div>';
}

function wppfm_showEditValueQuery( rowId, sourceLevel, queryLevel, query ) {
	var emptyQueryObject = {};

	// add a query selector
	jQuery( '#value-editor-queries-' + rowId + '-' + sourceLevel + '-' + queryLevel ).
		html( wppfm_ifValueQuerySelector( rowId, sourceLevel, queryLevel + 1, emptyQueryObject, query ) );

	// hide the 'for all products' text
	jQuery( '#value-editor-input-query-span-' + rowId + '-' + sourceLevel + '-0' ).hide();

	if ( queryLevel === 0 && query ) {

		// if it's the first query for this change value, then also add a "for all other products" row
		wppfm_addRowValueEditor( rowId, sourceLevel + 1, 0, '' );
	}
}

/**
 * Gets called when the user selects a source from the source selector
 *
 * @param {string} rowId
 * @param {string} sourceLevel
 * @param {string} advisedSource
 * @returns nothing
 */
function wppfm_changedOutput( rowId, sourceLevel, advisedSource ) {

	var selectedValue               = jQuery( '#input-field-cntrl-' + rowId + '-' + sourceLevel ).val();
	var combinationLevel            = 0;
	var sourceSelectorElement       = jQuery( '#source-select-' + rowId + '-' + sourceLevel );
	var combinedWrapperElement      = jQuery( '#combined-wrapper-' + rowId + '-' + sourceLevel );
	var staticInputFieldElement     = jQuery( '#static-input-field-' + rowId + '-' + sourceLevel + '-' + combinationLevel );
	var staticConditionInputElement = jQuery( '#static-condition-input-' + rowId + '-' + sourceLevel + '-' + combinationLevel );

	switch ( selectedValue ) {
		case 'advised':

			if ( rowId !== '3' ) {

				var htmlCode = '';

				if ( advisedSource !== 'Use the settings in the Merchant Center' ) {

					var label = wppfm_sourceOptionsConverter( advisedSource );

					// reset the row to display an advised input row
					htmlCode = '<div class="advised-source">' + label + wppfm_editSourceSelector( rowId, sourceLevel ) + '</div>'; // <span id="static-input-' + id + '"></span>';
				} else {

					htmlCode = '<div class="advised-source">Use the settings in the Merchant Center ' + wppfm_editSourceSelector( rowId, sourceLevel ) + '</div>';
				}

				sourceSelectorElement.html( htmlCode );

				// clear the attribute value
				_feedHolder.clearSourceValue( rowId, sourceLevel );
				_feedHolder.deactivateAttribute( rowId );
			} else {

				htmlCode = '<span id="category-source-string">' + _feedHolder.mainCategory
				           + '</span>' + wppfm_editSourceSelector( rowId, sourceLevel );

				_feedHolder.setCategoryValue( rowId, _feedHolder.mainCategory );
				sourceSelectorElement.html( htmlCode );
			}

			break;

		case 'static':

			sourceSelectorElement.
				append( wppfm_displayCorrectStaticField( rowId, sourceLevel, '0', _feedHolder[ 'channel' ], _feedHolder[ 'attributes' ][ rowId ][ 'fieldName' ], '' ) );
			combinedWrapperElement.remove();

			var staticValue = staticInputFieldElement.val() ? staticInputFieldElement.val() :
				jQuery( '#static-condition-input-' + rowId + '-' + sourceLevel + '-' + combinationLevel + ' option:selected' ).val();

			// set the attribute value in the feed on the standard value of the input field for when the user leaves it there
			_feedHolder.setStaticAttributeValue( rowId, sourceLevel, combinationLevel, staticValue );

			if ( staticValue || sourceLevel > 0 ) {
				_feedHolder.activateAttribute( rowId );
			} else {
				_feedHolder.deactivateAttribute( rowId );
			}

			break;

		case 'select':

			// reset the source value
			_feedHolder.clearSourceValue( rowId, sourceLevel );
			if ( sourceLevel === '0' ) {
				_feedHolder.deactivateAttribute( rowId );
			}

			staticInputFieldElement.remove();
			staticConditionInputElement.remove();
			combinedWrapperElement.remove();

			wppfm_clearStaticField( rowId );

			break;

		case 'combined':

			_feedHolder.setSourceValue( rowId, sourceLevel, selectedValue );
			if ( sourceLevel === '0' ) {
				_feedHolder.deactivateAttribute( rowId );
			}

			staticInputFieldElement.remove();
			staticConditionInputElement.remove();

			wppfm_addCombinedField( rowId, sourceLevel, 0 );

			break;

		case 'category_mapping':

			_feedHolder.setCustomCategory( rowId, _feedHolder.mainCategory );          // This option generates an error message, but it should be correct!!!!!!!!!
			//            _feedHolder.setCategoryValue( rowId, _feedHolder.mainCategory );    // Generate a new feed, make some outputs, link one of the outputs to the category map
			// The feed wil now be generated, but the value of this attribute will not be a 't' value, but will remain a
			// 'm' with 's' value

			staticInputFieldElement.remove();
			staticConditionInputElement.remove();
			combinedWrapperElement.remove();

			// remove the condition and edit values options
			jQuery( '#condition-' + rowId + '-' + sourceLevel + '-0' ).remove();
			jQuery( '#value-editor-input-query-add-span-' + rowId + '-' + sourceLevel + '-0' ).hide();

			// display the standard category text message
			sourceSelectorElement.html( wppfm_categorySource() );

			break;

		default:

			jQuery( '#static-input-' + rowId ).html( '' );

			if ( selectedValue === advisedSource && sourceLevel === '0' && jQuery( '#input-field-cntrl-' + rowId + '-' + sourceLevel + '-0' ).val() === 'select' ) {

				_feedHolder.clearSourceValue( rowId, sourceLevel );
			} else {

				_feedHolder.setSourceValue( rowId, sourceLevel, selectedValue );
			}

			_feedHolder.activateAttribute( rowId );
			_feedHolder[ 'attributes' ][ rowId ][ 'isActive' ] = true;

			staticInputFieldElement.remove();
			staticConditionInputElement.remove();
			combinedWrapperElement.remove();

			break;
	}
}

function wppfm_changedAlternativeSource( rowId ) {
	var sourceLevel   = '1'; // Only works as long as only the WooCommerce "source" is used by the plugin. Needs to be updated as soon as more sources are added.
	var selectedValue = jQuery( '#alternative-input-field-cntrl-' + rowId ).val();

	if ( selectedValue === 'static' ) {
		var htmlCode = wppfm_displayCorrectStaticField( rowId, sourceLevel, '0', _feedHolder[ 'channel' ], _feedHolder[ 'attributes' ][ rowId ][ 'fieldName' ], '' );

		jQuery( '#alternative-static-input-' + rowId ).html( htmlCode );

	} else {

		jQuery( '#alternative-static-input-' + rowId ).empty();

		if ( selectedValue !== 'select' && selectedValue !== 'empty' ) {

			_feedHolder.setAlternativeSourceValue( rowId, sourceLevel, selectedValue );
		} else {

			_feedHolder.removeAlternativeSourceValue( rowId, sourceLevel );
		}
	}
}

function wppfm_changedCombinationSeparator( rowId, sourceLevel, combinationLevel ) {
	wppfm_changedCombinedOutput( rowId, sourceLevel, combinationLevel );
}

function wppfm_changedCombinedOutput( rowId, sourceLevel, combinationLevel ) {
	var selectedValue      = jQuery( '#combined-input-field-cntrl-' + rowId + '-' + sourceLevel + '-' + combinationLevel ).val();
	var staticValueControl = jQuery( '#wppfm-static-value-control-' + rowId + '-' + sourceLevel + '-' + combinationLevel );

	switch ( selectedValue ) {
		case 'static':
			if ( ! jQuery( '#static-input-field-' + rowId + '-' + sourceLevel + '-' + combinationLevel ).val() ) {
				var htmlCode = wppfm_displayCorrectStaticField( rowId, sourceLevel, combinationLevel, _feedHolder[ 'channel' ], _feedHolder[ 'attributes' ][ rowId ][ 'fieldName' ],
					'' );
				staticValueControl.html( htmlCode );
			}

			break;

		default:
			staticValueControl.empty();

			break;
	}

	var combinedOutput = getCombinedValue( rowId, sourceLevel );

	if ( combinedOutput ) {
		_feedHolder.setCombinedOutputValue( rowId, sourceLevel, combinedOutput );
		_feedHolder.activateAttribute( rowId );
	} else {
		_feedHolder.removeCombinedOutputValue( rowId, sourceLevel, combinationLevel );
		if ( sourceLevel === '0' ) {
			_feedHolder.deactivateAttribute( rowId );
		} // only deactivate when on the first (0) level
	}
}

//noinspection JSUnusedGlobalSymbols
function wppfm_removeCombinedField( id, sourceLevel, combinedLevel ) {

	_feedHolder.removeCombinedOutputValue( id, sourceLevel, combinedLevel );

	var combinedValueObject = JSON.parse( _feedHolder[ 'attributes' ][ id ][ 'value' ] );
	var combinedValuePart   = 'm' in combinedValueObject ? combinedValueObject.m : {};
	var combinedValue       = wppfm_getMappingCombinedValue( combinedValuePart, sourceLevel );

	jQuery( '#source-select-' + id + '-' + sourceLevel ).append( wppfm_combinedField( id, sourceLevel, combinedLevel, combinedValue, false ) );
}

function wppfm_clearStaticField( id ) {
	jQuery( '#static-input-' + id ).html( '' );
}

function wppfm_storeValueCondition( rowId, conditionLevel, queryLevel ) {

	var source = jQuery( '#value-options-input-field-cntrl-' + rowId + '-' + conditionLevel + '-' + queryLevel ).val();

	if ( source !== 'select' ) {

		var doStore;

		// get the data required to build the query string
		var subCondition = queryLevel > 1 ? jQuery( '#value-options-sub-query-' + rowId + '-' + conditionLevel + '-' + queryLevel + ' option:selected' ).val() : '0';
		var condition    = jQuery( '#value-query-condition-' + rowId + '-' + conditionLevel + '-' + queryLevel + '-0 option:selected' ).val();
		var value        = condition !== '4' && condition !== '5' ? jQuery( '#value-options-condition-value-' + rowId + '-' + conditionLevel + '-' + queryLevel ).val() : '';
		var betweenValue = condition === '14' ? jQuery( '#value-options-condition-and-value-input-' + rowId + '-' + conditionLevel + '-' + queryLevel ).val() : '';

		if ( condition === '4' || condition === '5' ) {

			doStore = true;
		} else if ( condition === '14' ) {

			doStore = ! ! (
				value && betweenValue
			);
		} else {

			doStore = ! ! value;
		}

		if ( doStore === true ) {

			var subConditionString = subCondition !== '' ? subCondition + '#' : '0#';

			// build the query string
			var query = subConditionString + source + '#' + condition;

			if ( value ) {
				query += '#' + value;
			}

			if ( betweenValue ) {
				query += '#0#' + betweenValue;
			}

			// store the query string in the feed object
			_feedHolder.addValueQueryValue( rowId, conditionLevel, queryLevel, query );
		}
	} else {
		console.log( 'Query ' + rowId + '-' + conditionLevel + '-' + queryLevel + ' removed!' );

		_feedHolder.removeValueQueryValue( rowId, conditionLevel, queryLevel );
	}
}

function wppfm_storeCondition( rowId, sourceLevel, conditionLevel ) {

	var source = jQuery( '#input-field-cntrl-' + rowId + '-' + sourceLevel + '-' + conditionLevel ).val();
	var result = false;

	if ( wppfm_validSourceSelected( rowId, sourceLevel ) ) {

		// only store the condition if the user has selected a condition option
		if ( source !== 'select' ) {

			var doStore;

			// get the data from the input fields
			var subCondition = conditionLevel > 1 ? jQuery( '#sub-condition-' + rowId + '-' + sourceLevel + '-' + conditionLevel + ' option:selected' ).val() : '0';
			var condition    = jQuery( '#query-condition-' + rowId + '-' + sourceLevel + '-' + conditionLevel + ' option:selected' ).val();
			var value        = condition !== '4' && condition !== '5' ? jQuery( '#condition-value-' + rowId + '-' + sourceLevel + '-' + conditionLevel ).val() : '';
			var secondValue  = condition === '14' ? jQuery( '#condition-and-value-input-' + rowId + '-' + sourceLevel + '-' + conditionLevel ).val() : '';

			if ( condition === '4' || condition === '5' ) {

				doStore = true;
			} else if ( condition === '14' ) { // is between condition

				doStore = ! ! (
					value && secondValue
				);
			} else {

				doStore = ! ! (
					value
				);
			}

			// only store the data when it is complete
			if ( doStore === true ) {

				var subConditionString = subCondition !== '' ? subCondition + '#' : '0#';

				// build the query string
				var query = subConditionString + source + '#' + condition;

				if ( value ) {
					query += '#' + value;
				}

				if ( secondValue ) {
					query += '#0#' + secondValue;
				}

				// store the query string in the feed object
				_feedHolder.addConditionValue( rowId, query, sourceLevel, conditionLevel );

				result = true;
			}
		} else {

			_feedHolder.removeValueConditionValue( rowId, sourceLevel, conditionLevel );
		}
	} else {

		jQuery( '#input-field-cntrl-' + rowId + '-' + sourceLevel + '-1' ).prop( 'selectedIndex', 0 );
		jQuery( '#condition-value-' + rowId + '-' + sourceLevel + '-1' ).val( '' );

		//noinspection JSUnresolvedVariable
		alert( wppfm_feed_settings_form_vars.select_a_valid_source_warning );
	}

	return result;
}

function wppfm_validSourceSelected( rowId, sourceLevel ) {

	var mainSelection = jQuery( '#input-field-cntrl-' + rowId + '-' + sourceLevel ).val();

	switch ( mainSelection ) {

		case 'select':
			return false;

		case 'static':
			var staticConditionInputElement = jQuery( '#static-condition-input-' + rowId + '-' + sourceLevel + '-0' );
			var staticInputFieldElement     = jQuery( '#static-input-field-' + rowId + '-' + sourceLevel + '-0' );

			// TODO: check why I use the static-condition-input and the static-input-field as id sometimes
			if ( staticConditionInputElement.val() ) {
				return ! ! staticConditionInputElement.val();
			} else if ( staticInputFieldElement.val() ) {
				return ! ! staticInputFieldElement.val();
			} else {
				return false;
			}

		case 'combined':
			return jQuery( '#combined-input-field-cntrl-' + rowId + '-' + sourceLevel + '-2' ).val() !== 'select';

		default:
			return true;
	}
}

function wppfm_valueInputOptionsChanged( rowId, sourceLevel, valueEditorLevel ) {
	var option = jQuery( '#value-options-' + rowId + '-' + sourceLevel + '-' + valueEditorLevel + ' option:selected' ).text();

	if ( ! option ) {
		return;
	}

	var value  = jQuery( '#value-options-input-' + rowId + '-' + sourceLevel + '-' + valueEditorLevel ).val();
	var store  = '';
	var pre    = sourceLevel > 0 ? 'and' : 'change';

	if ( option !== 'replace' && option !== 'recalculate' ) {

		store = pre + '#' + option + '#';
		store += option === 'change nothing' || option === 'strip tags' || option === 'html entity decode' ? 'blank' : value;

		_feedHolder.addChangeValue( rowId, sourceLevel, valueEditorLevel, store );
	} else if ( option === 'replace' ) {

		var withValue = jQuery( '#value-options-input-with-' + rowId + '-' + sourceLevel + '-' + valueEditorLevel ).val();

		if ( value && withValue ) {

			store = pre + '#' + option + '#' + value + '#' + withValue;
			_feedHolder.addChangeValue( rowId, sourceLevel, valueEditorLevel, store );
		}

	} else if ( option === 'recalculate' ) {

		var calculation = jQuery( '#value-options-recalculate-options-' + rowId + '-' + sourceLevel + '-' + valueEditorLevel + ' option:selected' ).text();

		if ( value ) {

			store = pre + '#' + option + '#' + calculation + '#' + value;
			_feedHolder.addChangeValue( rowId, sourceLevel, valueEditorLevel, store );
		}
	}
}

function wppfm_removeValueEditor( rowId, sourceLevel, valueEditorLevel ) {

	_feedHolder.removeEditValueValue( rowId, sourceLevel, valueEditorLevel );

	var values   = _feedHolder.getAttributesValueObject( rowId );
	var nrValues = wppfm_countObjectItems( values );

	// when the value is fully empty after removing the condition, deactivate the attribute
	if ( ! _feedHolder[ 'attributes' ][ rowId ][ 'value' ] ) {

		_feedHolder.deactivateAttribute( rowId );
	}

	jQuery( '#edit-value-span-' + rowId + '-' + sourceLevel + '-0' ).remove();

	if ( nrValues > 0 ) {

		for ( var i = 1; i < nrValues; i ++ ) {

			wppfm_addRowValueEditor( rowId, sourceLevel, i, values[ i ] );
		}
	} else {

		jQuery( '#value-editor-' + rowId + '-' + sourceLevel + '-' + valueEditorLevel ).remove();
		jQuery( '#source-' + rowId + '-' + sourceLevel ).append( wppfm_editValueSpan( rowId, sourceLevel, valueEditorLevel ) );
	}
}

function wppfm_drawAttributeMappingSection() {

	var channel = _feedHolder.channel ? _feedHolder.channel.toString() : '';

	// reset the fields
	wppfm_resetFields();

	for ( var i = 0; i < _feedHolder[ 'attributes' ].length; i ++ ) {

		switch ( _feedHolder[ 'attributes' ][ i ][ 'fieldLevel' ] ) {

			case '1':
				_mandatoryFields += wppfm_fieldRow( _feedHolder[ 'attributes' ][ i ], false );
				break;

			case '2':
				_highlyRecommendedFields += wppfm_fieldRow( _feedHolder[ 'attributes' ][ i ], false );
				break;

			case '3':
				if ( _feedHolder[ 'attributes' ][ i ][ 'isActive' ] ) {
					_recommendedFields += wppfm_fieldRow( _feedHolder[ 'attributes' ][ i ], true );
					_definedRecommendedOutputs.push( _feedHolder[ 'attributes' ][ i ][ 'fieldName' ] );
				} else {
					_undefinedRecommendedOutputs.push( _feedHolder[ 'attributes' ][ i ][ 'fieldName' ] );
				}
				break;

			case '4':
				if ( _feedHolder[ 'attributes' ][ i ][ 'isActive' ] ) {
					_optionalFields += wppfm_fieldRow( _feedHolder[ 'attributes' ][ i ], true );
					_definedOptionalOutputs.push( _feedHolder[ 'attributes' ][ i ][ 'fieldName' ] );
				} else {
					_undefinedOptionalOutputs.push( _feedHolder[ 'attributes' ][ i ][ 'fieldName' ] );
				}
				break;

			case '5':
				if ( _feedHolder[ 'attributes' ][ i ][ 'isActive' ] ) {
					_customFields += wppfm_fieldRow( _feedHolder[ 'attributes' ][ i ], true );
				} else {
					_undefinedCustomOutputs.push( _feedHolder[ 'attributes' ][ i ][ 'fieldName' ] );
				}
				break;

			default:
				break;
		}
	}

	_definedRecommendedOutputs.sort();
	_undefinedRecommendedOutputs.sort();

	_recommendedFields += wppfm_addOptionalFieldRow( 3 );
	_optionalFields += wppfm_addOptionalFieldRow( 4 );

	if ( wppfm_isCustomChannel( channel ) ) { // this means the user has selected a free format feed
		_customFields += wppfm_addOptionalFieldRow( 5 );
	}

	if ( _mandatoryFields.length > 0 ) {
		jQuery( '#wppfm-required-field-table' ).html( _mandatoryFields );
		jQuery( '#wppfm-required-fields' ).show();
	} else {
		jQuery( '#wppfm-required-fields' ).hide();
	}

	if ( _highlyRecommendedFields.length > 0 ) {
		jQuery( '#wppfm-highly-recommended-field-table' ).html( _highlyRecommendedFields );
		jQuery( '#wppfm-highly-recommended-fields' ).show();
	} else {
		jQuery( '#wppfm-highly-recommended-fields' ).hide();
	}

	if ( _recommendedFields.length > 0 ) {
		jQuery( '#wppfm-recommended-field-table' ).html( _recommendedFields );
		jQuery( '#wppfm-recommended-fields' ).show();
	} else {
		jQuery( '#wppfm-recommended-fields' ).hide();
	}

	if ( _optionalFields.length > 0 ) {
		jQuery( '#wppfm-optional-field-table' ).html( _optionalFields );
		jQuery( '#wppfm-optional-fields' ).show();
	} else {
		jQuery( '#wppfm-optional-fields' ).hide();
	}

	if ( _customFields.length > 0 ) {
		jQuery( '#wppfm-custom-field-table' ).html( _customFields );
		jQuery( '#wppfm-custom-fields' ).show();
	} else {
		jQuery( '#wppfm-custom-fields' ).hide();
	}

	jQuery( '#wppfm-attribute-map' ).show( 300 );
}

//noinspection DuplicatedCode
function wppfm_resetFields() {

	_mandatoryFields             = [];
	_highlyRecommendedFields     = [];
	_recommendedFields           = [];
	_undefinedRecommendedOutputs = [];
	_optionalFields              = [];
	_undefinedOptionalOutputs    = [];
	_customFields                = [];
	_undefinedCustomOutputs      = [];
}

function wppfm_queryConditionChanged( id, sourceLevel, conditionLevel ) {

	// TODO: wppfm_queryConditionChanged and wppfm_queryValueConditionChanged and other functions that are related to the conditions
	// can probably be combined by adding a unique subQuery number to the query-condition levels (for example a letter combination)
	// so that they can be separated from the value-query-condition levels.
	// After that combining the two condition options is possible.

	// get the selected query option
	var value = jQuery( '#query-condition-' + id + '-' + sourceLevel + '-' + conditionLevel ).val();

	// if the "is empty" or "is not empty" condition is selected
	if ( value === '4' || value === '5' ) {

		jQuery( '#condition-value-' + id + '-' + sourceLevel + '-' + conditionLevel ).hide();
	} else {

		jQuery( '#condition-value-' + id + '-' + sourceLevel + '-' + conditionLevel ).show();
	}

	// if the "is between" condition is selected
	if ( value === '14' ) {

		jQuery( '#condition-value-' + id + '-' + sourceLevel + '-' + conditionLevel ).show();
		jQuery( '#condition-and-value-' + id + '-' + sourceLevel + '-' + conditionLevel ).show();
	} else {

		jQuery( '#condition-and-value-' + id + '-' + sourceLevel + '-' + conditionLevel ).hide();
	}

	var conditionStored = wppfm_storeCondition( id, sourceLevel, conditionLevel );

	if ( conditionStored && ! jQuery( '#source-' + id + '-' + (
	                                  sourceLevel + 1
	) ).length ) {

		// add a new source row to the fieldRow
		jQuery( wppfm_addFeedSourceRow( id, sourceLevel + 1, _feedHolder.getSourceObject( id ) ) ).insertAfter( '#source-' + id + '-' + sourceLevel );
	}
}

function wppfm_queryValueConditionChanged( rowId, sourceLevel, queryLevel, querySublevel ) {

	var value = jQuery( '#value-query-condition-' + rowId + '-' + sourceLevel + '-' + queryLevel + '-' + querySublevel ).val();

	if ( value === '4' || value === '5' ) {

		jQuery( '#value-options-condition-value-' + rowId + '-' + sourceLevel + '-' + queryLevel ).hide();
	} else {

		jQuery( '#value-options-condition-value-' + rowId + '-' + sourceLevel + '-' + queryLevel ).show();
	}

	if ( value === '14' ) {

		jQuery( '#value-options-condition-value-' + rowId + '-' + sourceLevel + '-' + queryLevel ).show();
		jQuery( '#value-options-condition-and-value-' + rowId + '-' + sourceLevel + '-' + queryLevel ).show();
	} else {

		jQuery( '#value-options-condition-and-value-' + rowId + '-' + sourceLevel + '-' + queryLevel ).hide();
	}

	wppfm_storeValueCondition( rowId, sourceLevel, queryLevel );
}

function wppfm_getOutputFieldsList( level ) {
	var htmlCode = '';
	var list     = [];

	switch ( level ) {
		case 3:
			list = _undefinedRecommendedOutputs;
			break;

		case 4:
			list = _undefinedOptionalOutputs;
			break;

		default:
			break;
	}

	list.sort();
	for ( var i = 0; i < list.length; i ++ ) {
		htmlCode += '<option value="' + list[ i ] + '">' + list[ i ] + '</option>';
	}

	return htmlCode;
}

function wppfm_fixedSourcesList( selectedValue ) {

	var htmlCode     = '';
	var selectStatus = '';

	for ( var i = 0; i < _inputFields.length; i ++ ) {

		selectStatus = null !== selectedValue && selectedValue === _inputFields[ i ].value ? ' selected' : '';

		htmlCode += '<option value = "' + _inputFields[ i ].value + '" itemprop="' + _inputFields[ i ].prop + '" ' + selectStatus + '>' + _inputFields[ i ].label + '</option>';
	}

	return htmlCode;
}

function wppfm_hideFeedFormMainInputs() {

	jQuery( '#wppfm-country-list-row' ).hide();
	jQuery( '#category-list-row' ).hide();
	jQuery( '#aggregator-selector-row' ).hide();
	jQuery( '#add-product-variations-row' ).hide();
}


function wppfm_editFeedFilter( ) {
	alert( wppfm_feed_settings_form_vars.advanced_filter_only_for_premium );
}

function wppfm_makeFeedFilterWrapper( feedId, filter ) {
	var	htmlCode = wppfm_feed_settings_form_vars.all_products_included;

	htmlCode += '<span id="filter-edit-text" style="display:initial;"> (<a class="edit-feed-filter wppfm-btn wppfm-btn-small" href="javascript:void(0)" id="wppfm-edit-feed-filters';
	htmlCode += '" onclick="wppfm_editFeedFilter()">' + wppfm_feed_settings_form_vars.edit + '</a>)</span>';

	jQuery( '.wppfm-product-filter-condition-wrapper' ).html( htmlCode );
//	jQuery( '.wppfm-main-product-filter-wrapper' ).show();
}

function wppfm_getCombinedSeparatorList( selectedValue ) {

	// ALERT These options have to be the same as in the make_combined_result_string() array
	//noinspection JSUnresolvedVariable
	var separatorOptions = {
		'0': '-- ' + wppfm_feed_settings_form_vars.no_separator + ' --',
		'1': wppfm_feed_settings_form_vars.space,
		'2': wppfm_feed_settings_form_vars.comma,
		'3': wppfm_feed_settings_form_vars.point,
		'4': wppfm_feed_settings_form_vars.semicolon,
		'5': wppfm_feed_settings_form_vars.colon,
		'6': wppfm_feed_settings_form_vars.dash,
		'7': wppfm_feed_settings_form_vars.slash,
		'8': wppfm_feed_settings_form_vars.backslash,
		'9': wppfm_feed_settings_form_vars.double_pipe,
		'10': wppfm_feed_settings_form_vars.underscore,
		'11': wppfm_feed_settings_form_vars.greater_than, // @since 2.42.0
	};

	var htmlCode = '';

	for ( var field in separatorOptions ) {

		if ( field !== selectedValue ) {

			htmlCode += '<option value="' + field + '">' + separatorOptions[ field ] + '</option>';
		} else {

			htmlCode += '<option value="' + field + '" selected>' + separatorOptions[ field ] + '</option>';

		}
	}

	return htmlCode;
}

function updateFeedFormAfterInputChanged( feedId, categoryChanged ) {

	// enable the Generate and Save button
	wppfm_enableFeedActionButtons();
	wppfm_finishOrUpdateFeedPage( categoryChanged );

	// make a new feed object if it has not been already
	if ( feedId === undefined || feedId < 1 ) {
		wppfm_constructNewFeed();
	}
}

/**
 * For a new feed, some html elements need to be updated to include the new created feed id.
 *
 * @param feedId
 */
function wppfm_resetFormElementIds( feedId ) {
	var filterSelector = document.getElementById( 'wppfm-edit-feed-filters' );
	filterSelector.setAttribute( 'onclick', 'wppfm_editFeedFilter(' + feedId + ')' );
}

function wppfm_showCorrectInputElementsForFeedType( feedType ) {
	document.getElementById( 'wppfm-merchants-selector' ).value  = '1';

	switch ( feedType ) {
		case 'google-local-product-inventory-feed':
			wppfm_showLocalProductInventoryFeedInputs();
			document.getElementById( 'wppfm-feed-types-selector' ).value = '4';
			break;

		case 'google-dynamic-remarketing-feed':
			wppfm_showDynamicRemarketingFeedInputs();
			document.getElementById( 'wppfm-feed-types-selector' ).value = '5';
			break;

    case 'google-vehicle-ads-feed':
      wppfm_showVehicleAdsFeedInputs();
      document.getElementById( 'wppfm-feed-types-selector' ).value = '6';
      break;

    case 'google-dynamic-search-ads-feed':
      wppfm_showLocalProductFeedInputs();
      document.getElementById( 'wppfm-feed-types-selector' ).value = '7';
      break;

		case 'google-local-product-feed':
			wppfm_showLocalProductFeedInputs();
			document.getElementById( 'wppfm-feed-types-selector' ).value = '8';
			break;

		default:
			break;
	}
}

/**
 * Adds a feed specification link to the header of a new Edit Feed page for non Google feeds.
 */
function wppfm_showMerchantSpecificationLink() {
	if ( _feedHolder[ 'feedSpecificationUrl' ] ) {
		var specificationLinkElement = document.getElementById( 'wppfm-tab-page-sub-title').querySelector('a');
		specificationLinkElement.setAttribute('href',	_feedHolder['feedSpecificationUrl']);
		specificationLinkElement.textContent = 'Click here to view the feed specifications.';
	}

}

/**
 * hook the document actions
 */
jQuery(
	function() {
		var feedId   = wppfm_getUrlParameter( 'id' );
		var feedName = wppfm_getUrlParameter( 'feed-name' );
		var feedType = wppfm_getUrlParameter( 'feed-type' );

		if ( ! wppfm_isValidGoogleFeedType( feedType ) ) {
			return;
		}

		wppfm_showWorkingSpinner();

		if ( '' !== feedId ) { // existing feed
			wppfm_initiateFeed();
			wppfm_editExistingFeed( feedId );
			wppfm_hideWorkingSpinner();
		} else if ( '' !== feedName && '' !== feedType ) { // new feed
			document.getElementById( 'wppfm-feed-file-name' ).value = feedName;
			wppfm_showCorrectInputElementsForFeedType( feedType );
			updateFeedFormAfterInputChanged( '-1', false );
		}
	}
);
