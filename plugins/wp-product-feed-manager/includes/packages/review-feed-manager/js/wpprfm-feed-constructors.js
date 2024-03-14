var _reviewFeedHolder = [];

/**
 * Generates a new review feed and then fills the attribute mapping.
 * This function is the equivalent of the wppfm_constructNewFeed() function for standard product feeds.
 */
function wpprfm_constructNewReviewFeed() {
	var daysIntervalElement            = jQuery( '#days-interval' );
	var updateScheduleHourElement      = jQuery( '#update-schedule-hours' );
	var updateScheduleMinutesElement   = jQuery( '#update-schedule-minutes' );
	var updateScheduleFrequencyElement = jQuery( '#update-schedule-frequency' );

	// get all the data from the input fields
	var title            = jQuery( '#wppfm-feed-file-name' ).val();
	var aggregatorName   = jQuery( '#wpprfm-aggregator-name' ).val();
	var publisherName    = jQuery( '#wpprfm-publisher-name' ).val();
	var publisherFavicon = jQuery( '#wpprfm-publisher-favicon' ).val();
	var daysInterval     = daysIntervalElement.val() !== '' ? daysIntervalElement.val() : '1';
	var hours            = updateScheduleHourElement.val() !== '' ? updateScheduleHourElement.val() : '00';
	var minutes          = updateScheduleMinutesElement.val() !== '' ? updateScheduleMinutesElement.val() : '00';
	var frequency        = updateScheduleFrequencyElement.val() !== '' ? updateScheduleFrequencyElement.val() : '1';

	// set all new feed data parameters
	var feedId              = -1;
	var includeVariations   = 0;
	var aggregator          = 0;
	var mainCategory        = '';
	var mainCategoryMapping = [];
	var dataSource          = '1';
	var country             = 'US'; // dummy data
	var language            = '';
	var currency            = '';
	var feedTitle           = '';
	var feedDescription     = '';
	var channel             = 1;
	var feedFilter          = [];
	var status              = 2;
	var feedType            = '2'; // Google Product Review Feed

	// make the url to the feed file
	var url     = jQuery( '#wppfm-feed-editor-page-data-storage' ).data( 'wppfmFeedUrl' ) + '/wppfm-feeds/' + title + '.xml';
	var updates = daysInterval + ':' + hours + ':' + minutes + ':' + frequency;

	_reviewFeedHolder = new Feed( feedId, title, includeVariations, aggregator, channel, mainCategory, mainCategoryMapping, url, dataSource, country, language, currency, feedTitle, feedDescription, updates, feedFilter, status, feedType );

	// add the Google Review Feed specific properties to the Feed object
	_reviewFeedHolder[ 'aggregatorName' ]   = aggregatorName;
	_reviewFeedHolder[ 'publisherName' ]    = publisherName;
	_reviewFeedHolder[ 'publisherFavicon' ] = publisherFavicon;

	// update the _feedHolder variable in the wppfm_feed-form.js file
	wppfm_constructNewSpecialFeed( _reviewFeedHolder );

	console.log( _reviewFeedHolder );
}

/**
 * Updates an existing feed or finishes a new one.
 * This function is the equivalent of the wppfm_finishOrUpdateFeedPage() function for the standard product feeds.
 */
function wpprfm_finishOrUpdateReviewFeed() {
	wppfm_showWorkingSpinner();

	// get all the data from the input fields
	_reviewFeedHolder[ 'title' ]            = jQuery( '#wppfm-feed-file-name' ).val();
	_reviewFeedHolder[ 'aggregatorName' ]   = jQuery( '#wpprfm-aggregator-name' ).val();
	_reviewFeedHolder[ 'publisherName' ]    = jQuery( '#wpprfm-publisher-name' ).val();
	_reviewFeedHolder[ 'publisherFavicon' ] = jQuery( '#wpprfm-publisher-favicon' ).val();
	_reviewFeedHolder[ 'feedFilter' ]       = [];
	_reviewFeedHolder[ 'status' ]           = '2';
	_reviewFeedHolder[ 'country' ]          = 'US';

	// make the url to the feed file
	_reviewFeedHolder[ 'url' ]              = jQuery( '#wppfm-feed-editor-page-data-storage' ).data( 'wppfmFeedUrl' ) + '/wppfm-feeds/' + _reviewFeedHolder[ 'title' ] + '.xml';

	// Retrieve the feeds attributes from the database
	wpprfm_getFeedAttributes( -1, function( response ) {

		var attributes = JSON.parse( response );

		// store the schedule in the reviewFeedHolder object
		_reviewFeedHolder.setUpdateSchedule( jQuery( '#days-interval' ).val(), jQuery( '#update-schedule-hours' ).val(),
			jQuery( '#update-schedule-minutes' ).val(), jQuery( '#update-schedule-frequency' ).val() );

		// add the default attributes if attributes are not set
		if ( undefined === _reviewFeedHolder[ 'attributes' ] || 0 === _reviewFeedHolder[ 'attributes' ].length ) {
			wpprfm_setDefaultReviewFeedAttributes( attributes );
		}

		wppfm_customSourceFields( _reviewFeedHolder[ 'dataSource' ], function( customFields) {

			wpprfm_fillSourcesList( customFields );

			wppfm_mainFeedFilters( _reviewFeedHolder[ 'feedId' ], function ( feedFilters ) {

				// get the master feed filter
				var mainFeedFilter = feedFilters !== 1 ? feedFilters : null;

				_reviewFeedHolder.setFeedFilter( mainFeedFilter );

				wppfm_makeFeedFilterWrapper( _reviewFeedHolder[ 'feedId' ], _reviewFeedHolder[ 'feedFilter' ] );

				wppfm_finishOrUpdateSpecialFeedPage( _reviewFeedHolder );

				// show the buttons again
				jQuery( 'section' ).filter( '#page-center-buttons' ).show();

				wppfm_hideWorkingSpinner();
			} );
		} );
	} );
}

/**
 * Gets called when the edit feed page is opened with an existing Google Review Feed. Is the review feed equivalent of the wppfm_editExistingFeed() function.
 *
 * @param {string} feedId
 */
function wpprfm_editExistingReviewFeed( feedId ) {
	// exit if the incorrect data is loaded
	if ( feedId !== _reviewFeedHolder[ 'feedId' ] ) {
		return;
	}

	// make sure the file name field is on the page before moving on
	if ( ! jQuery( '#wppfm-feed-file-name' ).length ) {
		return false;
	}

	wpprfm_fillSourcesList( _reviewFeedHolder['sourceFields'] );

	// get the master feed filter
	var mainFeedFilter = _reviewFeedHolder['feedFilters'] !== 1 ? _reviewFeedHolder['feedFilters'] : null;

	_reviewFeedHolder.setFeedFilter( mainFeedFilter );

	if ( _reviewFeedHolder['categoryMapping'].length ) {
		wppfm_setCategoryMap( _reviewFeedHolder['categoryMapping'], 'selector' );
	}

	wppfm_makeFeedFilterWrapper( _reviewFeedHolder['feedId'], _reviewFeedHolder['feedFilter'] );

	wppfm_finishOrUpdateSpecialFeedPage( _reviewFeedHolder );

	wpprfm_fillFeedFields( _reviewFeedHolder );

	// enable the Generate and Save buttons and the target country selection
	wppfm_enableFeedActionButtons( 'google-product-review-feed' );

	// show the buttons again
	jQuery( 'section' ).filter( '#page-center-buttons' ).show();
}

/**
 * Set the Publisher Icon url
 *
 * @param {string} faviconValue
 */
function wpprfm_setPublisherFavicon( faviconValue ) {
	wppfm_setSpecialFeedProperty( 'publisherFavicon', faviconValue, 'string' );
}
