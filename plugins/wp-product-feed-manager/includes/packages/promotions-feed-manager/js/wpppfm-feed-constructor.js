var _promotionsFeedHolder = [];

/**
 * Generates a new merchant promotions feed and then fills the attribute mapping.
 */
function wpppfm_constructNewPromotionsFeed() {
	// get all the data from the input fields
	var title = jQuery( '#wppfm-feed-file-name' ).val();

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
	var feedType            = '3'; // Google Merchant Promotions Feed

	var url = jQuery( '#wppfm-feed-editor-page-data-storage' ).data( 'wppfmFeedUrl' ) + '/wppfm-feeds/' + title + '.xml';
	var updates = '';

	_promotionsFeedHolder = new Feed( feedId, title, includeVariations, aggregator, channel, mainCategory, mainCategoryMapping, url, dataSource, country, language, currency, feedTitle, feedDescription, updates, feedFilter, status, feedType );

	// update the _feedHolder variable in the wppfm_feed-form.js file
	wppfm_constructNewSpecialFeed( _promotionsFeedHolder );
}

function wpppfm_finishOrUpdatePromotionsFeed() {
	var promotionDestinationString = wpppfm_getSelect2SelectorDataString( 'wpppfm-promotion-destination-input-field' );
	var promotionEffectiveStartDate = jQuery( '#wpppfm-promotion-effective-start-date-input-field' ).val();
	var promotionEffectiveEndDate = jQuery( '#wpppfm-promotion-effective-end-date-input-field' ).val();
	var promotionDisplayStartDate = jQuery( '#wpppfm-promotion-display-start-date-input-field' ).val();
	var promotionDisplayEndDate = jQuery( '#wpppfm-promotion-display-end-date-input-field' ).val();

	// get all the data from the input fields
	_promotionsFeedHolder[ 'title' ]                        = jQuery( '#wppfm-feed-file-name' ).val();
	_promotionsFeedHolder[ 'promotionEffectiveDate' ]       = wpppfm_makePromotionDatesString( promotionEffectiveStartDate, promotionEffectiveEndDate );
	_promotionsFeedHolder[ 'promotionDisplayDate' ]         = wpppfm_makePromotionDatesString( promotionDisplayStartDate, promotionDisplayEndDate );
	_promotionsFeedHolder[ 'promotionDestination' ]         = promotionDestinationString;

	// make the url to the feed file
	_promotionsFeedHolder[ 'url' ] = jQuery( '#wppfm-feed-editor-page-data-storage' ).data( 'wppfmFeedUrl' ) + '/wppfm-feeds/' + _reviewFeedHolder[ 'title' ] + '.xml';
}

function wpppfm_getSelect2SelectorDataString( elementId ) {
	var dataString = '';

	jQuery( '#' + elementId ).select2( 'data' ).forEach( function( item ) {
		dataString += item.id + ',';
	} );

	return dataString.slice( 0, -1 );
}
