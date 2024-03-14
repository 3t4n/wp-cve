/*global wppfm_channel_manager_form_vars */
function wppfm_activateFeedCategoryMapping( id ) {
	var feedSelectorElement = jQuery( '#feed-selector-' + id );
	var children            = feedSelectorElement.attr( 'data-children' ) ? JSON.parse( feedSelectorElement.attr( 'data-children' ) ) : [];

	feedSelectorElement.prop( 'checked', true );

	wppfm_activateFeedCategorySelector( id );

	for ( var i = 0; i < children.length; i ++ ) {
		wppfm_activateFeedCategorySelector( children[ i ] );
	}
}

function wppfm_activateFeedCategorySelection( id ) {
	var feedSelectorElement = jQuery( '#feed-selector-' + id );
	var children            = feedSelectorElement.attr( 'data-children' ) ? JSON.parse( feedSelectorElement.attr( 'data-children' ) ) : [];

	feedSelectorElement.prop( 'checked', true );

	_feedHolder.activateCategory( id, true );

	for ( var i = 0; i < children.length; i ++ ) {
		wppfm_activateFeedCategorySelection( children[ i ] );
	}
}

function wppfm_activateAllFeedCategoryMapping() {
	var tableType = 0 !== document.getElementsByClassName( 'wppfm-category-mapping-selector' ).length ? 'category_mapping_table' : 'category_selection_table';
	var idCollection = 'category_mapping_table' === tableType
		? document.getElementsByClassName( 'wppfm-category-mapping-selector' ) // category mapping table
		: document.getElementsByClassName( 'wppfm-category-selector' ); // category selection table

	for ( var j = 0; j < idCollection.length; j ++ ) {
		if ( 'category_mapping_table' === tableType ) {
			wppfm_activateFeedCategorySelector(idCollection[ j ].value);
		} else {
			wppfm_activateFeedCategorySelection(idCollection[ j ].value);
		}
	}
}

function wppfm_activateFeedCategorySelector( id ) {

	// some channels use your own shop's categories
	var usesOwnCategories   = wppfm_channelUsesOwnCategories( _feedHolder[ 'channel' ] );
	var feedCategoryText    = usesOwnCategories ? 'shopCategory' : 'default';
	var feedSelectorElement = jQuery( '#feed-selector-' + id );
	var feedCategoryElement = jQuery( '#feed-category-' + id );

	// activate the category in the feedHolder
	_feedHolder.activateCategory( id, usesOwnCategories );

	// get the children of this selector if any
	var children = feedSelectorElement.attr( 'data-children' ) ? JSON.parse( feedSelectorElement.attr( 'data-children' ) ) : [];

	if ( feedCategoryElement.html() === '' ) {
		feedCategoryElement.html( wppfm_mapToDefaultCategoryElement( id, feedCategoryText ) );
	}

	feedSelectorElement.prop( 'checked', true );

	for ( var i = 0; i < children.length; i ++ ) {
		wppfm_activateFeedCategorySelector( children[ i ] );
	}
}

function wppfm_deactivateFeedCategorySelection( id ) {
	var feedSelectorElement = jQuery( '#feed-selector-' + id );
	var children            = feedSelectorElement.attr( 'data-children' ) ? JSON.parse( feedSelectorElement.attr( 'data-children' ) ) : [];

	feedSelectorElement.prop( 'checked', false );

	_feedHolder.deactivateCategory( id );

	for ( var i = 0; i < children.length; i ++ ) {
		wppfm_deactivateFeedCategorySelection( children[ i ] );
	}
}

function wppfm_deactivateFeedCategoryMapping( id ) {
	var feedSelectorElement = jQuery( '#feed-selector-' + id );

	wppfm_deactivateFeedCategorySelector( id, true );

	var children = feedSelectorElement.attr( 'data-children' ) ? JSON.parse( feedSelectorElement.attr( 'data-children' ) ) : [];

	for ( var i = 0; i < children.length; i ++ ) {
		wppfm_deactivateFeedCategorySelector( children[ i ], false );
	}
}

function wppfm_deactivateAllFeedCategoryMapping() {
	var idCollection = 0 !== document.getElementsByClassName( 'wppfm-category-mapping-selector' ).length
		? document.getElementsByClassName( 'wppfm-category-mapping-selector' ) // category mapping table
		: document.getElementsByClassName( 'wppfm-category-selector' ); // category selection table

	for ( var j = 0; j < idCollection.length; j ++ ) {
		wppfm_deactivateFeedCategorySelector( idCollection[j].value );
	}
}

function wppfm_contains_special_characters( string ) {
	var specialChars = '%^#<>\\{}[]\/~`@?:;=&';

	for ( var i = 0; i < specialChars.length; i ++ ) {
		if ( string.indexOf( specialChars[ i ] ) > - 1 ) {
			return true;
		}
	}

	return false;
}

function wppfm_deactivateFeedCategorySelector( id, parent ) {
	var feedSelectorElement = jQuery( '#feed-selector-' + id );

	_feedHolder.deactivateCategory( id );

	jQuery( '#feed-category-' + id ).html( '' );
	jQuery( '#category-selector-catmap-' + id ).hide();

	feedSelectorElement.prop( 'checked', false );

	if ( ! parent ) {
		var children = feedSelectorElement.attr( 'data-children' ) ? JSON.parse( feedSelectorElement.attr( 'data-children' ) ) : [];
		for ( var i = 0; i < children.length; i ++ ) {
			wppfm_deactivateFeedCategorySelector( children[ i ], false );
		}
	}
}

/**
 * Shows and hides the category sublevel selectors depending on the selected level
 *
 * @param {string} currentLevelId
 */
function wppfm_hideSubs( currentLevelId ) {

	// identify the level from the level id
	var level    = currentLevelId.match( /(\d+)$/ )[ 0 ];
	var idString = currentLevelId.substring( 0, currentLevelId.length - level.length );

	// only show subfields that are at or before the selected level. Hide the rest
	for ( var i = 7; i > level; i -- ) {
		var categorySubLevelSelector = jQuery( '#' + idString + i );
		categorySubLevelSelector.css( 'display', 'none' );
		categorySubLevelSelector.empty();
	}
}

/**
 * Replaces special html characters to HTML entities.
 *
 * @param text
 * @returns {string}
 */
function wppfm_escapeHtml( text ) {
	text = text || '';
	text = text.replace( /&([^#])(?![a-z1-4]{1,8};)/gi, '&#038;$1' );
	return text.replace( /</g, '&lt;' ).replace( />/g, '&gt;' ).replace( /"/g, '&quot;' ).replace( /'/g, '&#039;' );
}

/**
 * Sanitizes a given input string by removing invalid characters.
 *
 * @since 3.3.0
 * @param {string} fileName - The original file name to be sanitized.
 * @return {string} - The sanitized file name without any invalid characters.
 */
function wppfm_sanitizeInputString( fileName ) {
	return fileName.trim().replace(/[<>:"\/\\|?*]/g, '_');
}

//noinspection DuplicatedCode
/**
 * Cleans and validates an email address.
 *
 * @param {string} email - The email address to sanitize and validate.
 * @return {string|boolean} - The sanitized email address if it is valid, otherwise false.
 */
function wppfm_sanitizeEmail( email ) {
	// noinspection RegExpRedundantEscape
	const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	if (re.test(String(email).toLowerCase())) {
		return email;
	} else {
		return false;
	}
}

//noinspection DuplicatedCode
/**
 * Takes a field string from a source input string and splits it up even when a pipe character
 * is used in a combined source input string
 *
 * @since 2.3.0
 * @param {string} fieldString
 * @returns {array}
 */
function wppfm_splitCombinedFieldElements( fieldString ) {
	if ( ! fieldString ) {
		return [];
	}

	var reg        = /\|[0-9]/; // pipe splitter plus a number directly after it
	var result     = [];
	var sliceStart = 0;
	var match;

	// fetch the separate field strings and put them in the result array
	while (( match = reg.exec(fieldString) ) !== null) {
		var ind = match.index;
		result.push(fieldString.substring(sliceStart, ind));
		fieldString = fieldString.slice(ind + 1);
	}

	// then add the final field string to the result array
	result.push( fieldString );

	return result;
}

function wppfm_showWorkingSpinner() {
	jQuery( '#wppfm-working-spinner' ).show();
	jQuery( 'body' ).css( 'cursor', 'wait' );
}

function wppfm_hideWorkingSpinner() {
	jQuery( '#wppfm-working-spinner' ).hide();
	jQuery( 'body' ).css( 'cursor', 'default' );
}

function wppfm_getButtonIds() {
	return {
		'google-product-review-feed': {
			generate: 'wpprfm-generate-review-feed-button',
			save: 'wpprfm-save-review-feed-button'
		},
		'google-merchant-promotions-feed': {
			generate: 'wpppfm-generate-merchant-promotions-feed-button',
			save: 'wpppfm-save-merchant-promotions-feed-button'
		},
		'product-feed': {
			generate: 'wppfm-generate-feed-button',
			save: 'wppfm-save-feed-button'
		}
	}
}

function wppfm_enableFeedActionButtons( feedType = 'product-feed' ) {
	let buttonIds = wppfm_getButtonIds()[feedType];

	// enable the Generate and Save button
	jQuery( `#${buttonIds.generate}-top` ).removeClass( 'wppfm-disabled-button' ).blur();
	jQuery( `#${buttonIds.generate}-bottom` ).removeClass( 'wppfm-disabled-button' ).blur();
	jQuery( `#${buttonIds.save}-top` ).removeClass( 'wppfm-disabled-button' ).blur();
	jQuery( `#${buttonIds.save}-bottom` ).removeClass( 'wppfm-disabled-button' ).blur();

	if ( '' !== jQuery( '#wppfm-feed-editor-page-data-storage' ).data( 'wppfmFeedUrl' ) ) {
		wppfm_enableViewFeedButtons();
	}
}

function disableFeedActionButtons( feedType = 'product-feed' ) {
	let buttonIds = wppfm_getButtonIds()[feedType];

	// enable the Generate and Save button
	jQuery( `#${buttonIds.generate}-top` ).addClass( 'wppfm-disabled-button' );
	jQuery( `#${buttonIds.generate}-bottom` ).addClass( 'wppfm-disabled-button' );
	jQuery( `#${buttonIds.save}-top` ).addClass( 'wppfm-disabled-button' );
	jQuery( `#${buttonIds.save}-bottom` ).addClass( 'wppfm-disabled-button' );

	wppfm_disableViewFeedButtons();
}

function wppfm_enableViewFeedButtons() {
	jQuery('#wppfm-view-feed-button-top').removeClass( 'wppfm-disabled-button' ).blur();
	jQuery('#wppfm-view-feed-button-bottom').removeClass( 'wppfm-disabled-button' ).blur();
}

function wppfm_disableViewFeedButtons() {
	jQuery( '#wppfm-view-feed-button-top' ).addClass( 'wppfm-disabled-button' );
	jQuery( '#wppfm-view-feed-button-bottom' ).addClass( 'wppfm-disabled-button' );
}

/**
 * Converts a date time input value in the DD-MM-YYYY hh:mm format to an ISO date time string
 *
 * @since 2.40.0
 * @param dtInputValue
 * @returns string
 */
function wppfm_convertDtInputDateTimeToIsoDateTime( dtInputValue ) {
	const dateParts = dtInputValue.split(" "); // splits the date and time
	const dateElements = dateParts[0].split("-"); // splits date into day, month and year
	const timeElements = dateParts[1].split(":"); // splits time into hours, minutes and seconds

	return new Date( dateElements[2], dateElements[1], dateElements[0], timeElements[0], timeElements[1] ).toISOString();
}

/**
 * Converts an ISO date time string to a date time input value in the DD-MM-YYYY hh:mm format
 *
 * @since 2.40.0
 * @param isoDateTime
 * @returns string
 */
function wppfm_convertIsoDateTimeToDtInputDateTime( isoDateTime ) {
	const date = new Date( isoDateTime );

	const day = date.getDate();
	const month = date.getMonth();
	const year = date.getFullYear();
	const hours = (date.getHours() < 10 ? '0' : '') + date.getHours();
	const minutes = (date.getMinutes() < 10 ? '0' : '') + date.getMinutes();

	return `${day}-${month}-${year} ${hours}:${minutes}`;
}

function wppfm_showErrorMessage( message ) {
	var errorMessageSelector = jQuery( '#wppfm-error-message' );
	errorMessageSelector.empty();
	errorMessageSelector.append( '<p>' + message + '</p>' );
	errorMessageSelector.show();
}

function wppfm_showSuccessMessage( message ) {
	var successMessageSelector = jQuery( '#wppfm-success-message' );
	successMessageSelector.empty();
	successMessageSelector.append( '<p>' + message + '</p>' );
	successMessageSelector.show();
}

function wppfm_showWarningMessage( message ) {
	var warningMessageSelector = jQuery( '#wppfm-warning-message' );
	warningMessageSelector.empty();
	warningMessageSelector.append( '<p>' + message + '</p>' );
	warningMessageSelector.show();
}

/**
 * Fills and then shows the channel info popup
 *
 * @since 3.4.0
 * @param channel_short_name
 */
function wppfm_showChannelInfoPopup( channel_short_name ) {
	var channelInfoDataElement = jQuery( '#wppfm-' + channel_short_name + '-channel-data' );
	var name = channelInfoDataElement.data( 'channel-name' );
	var status = channelInfoDataElement.data( 'status' );
	var installedVersion = channelInfoDataElement.data( 'installed-version' );
	var version = channelInfoDataElement.data( 'version' );
	var infoLink = channelInfoDataElement.data( 'info-link' );
	var specificationsLink = channelInfoDataElement.data( 'specifications-link' );

	var installedVersionElement = jQuery( '#wppfm-channel-info-popup__installed-version' );
	var infoLinkElement = jQuery( '#wppfm-channel-info-popup__info-link' );
	var specificationsLinkElement = jQuery( '#wppfm-channel-info-popup__feed-specifications-link' );

	jQuery( '#wppfm-channel-info-popup__name' ).html( name );
	jQuery( '#wppfm-channel-info-popup__status' ).html( 'Status: ' + status );

	if ( 'installed' === status ) {
		installedVersionElement.html('Installed version: ' + installedVersion);
		installedVersionElement.show();
	} else {
		installedVersionElement.hide();
	}

	jQuery( '#wppfm-channel-info-popup__latest-version' ).html( 'Latest version: ' + version );

	if ( '' !== infoLink ) {
		infoLinkElement.html( '<a href="' + infoLink + '" target="_blank">More about selling on this channel</a>' );
		infoLinkElement.show();
	} else {
		infoLinkElement.hide();

	}

	if ( '' !== specificationsLink ) {
		specificationsLinkElement.html( '<a href="' + specificationsLink + '" target="_blank">Channels feed specifications</a>' );
		specificationsLinkElement.show();
	} else {
		specificationsLinkElement.hide();
	}

	jQuery( '#wppfm-channel-info-popup' ).show();
}