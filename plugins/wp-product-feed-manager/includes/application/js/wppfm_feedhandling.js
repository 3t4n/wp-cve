var _feedHolder;

function wppfm_getFeedAttributes( feedId, channel, feedType, callback ) {

	wppfm_getOutputFields(
		feedId,
		channel,
		feedType,
		function( fields ) {

			if ( fields !== '0' ) {
				callback( JSON.parse( fields ) );
			} else {
				callback( [] ); // free feed format selected
			}
		}
	);
}

function wppfm_customSourceFields( sourceId, callback ) {

	wppfm_getSourceFields(
		sourceId,
		function( fields ) {

			callback( fields && fields !== '0' ? JSON.parse( fields ) : '0' );
		}
	);
}

function wppfm_mainFeedFilters( feedId, callback ) {

	wppfm_getMainFeedFilters(
		feedId,
		function( filters ) {

			callback( filters !== '0' ? JSON.parse( filters ) : null );
		}
	);
}

/**
 * Fills the attributes of the current _feed object with data from the outputs var
 *
 * @param {array} outputs containing output strings outputs
 * @param {string} channel id
 */
function wppfm_addFeedAttributes( outputs, channel ) {

	var inputs = wppfm_getAdvisedInputs( channel );
	var i      = 0;

	_feedHolder.clearAllAttributes();

	for ( var field in outputs ) {

		var outputTitle = outputs[ field ][ 'field_label' ];
		var activity    = true;

		// deactivate if this attribute is not required and has no value
		if ( parseInt( outputs[ field ][ 'category_id' ] ) > 2 && outputs[ field ][ 'value' ] === '' ) {
			activity = false;
		} else if ( outputs[ field ][ 'category_id' ] === '0' ) {
			activity = false;
		} else if ( parseInt( outputs[ field ][ 'category_id' ] ) > 2 && outputs[ field ][ 'value' ] === undefined ) {
			activity = false;
		}

		wppfm_setChannelRelatedPresets( outputs[ field ], channel );

		_feedHolder.addAttribute( i, outputTitle, inputs[ outputTitle ], outputs[ field ][ 'value' ], outputs[ field ][ 'category_id' ], activity, 0, 0, 0 );

		i ++;
	}
}

function wppfm_saveFeedToDb( feed, callback ) {

	// store the feed in a local variable
	_feedHolder = feed;

	var feedDataSelectorTable = jQuery('#wppfm-feed-editor-page-data-storage').data('wppfmAjaxFeedDataToDatabaseConversionArray');
	var feedDataToStore       = wppfm_getFeedDataToStore( feedDataSelectorTable );
	var metaToStore           = '3' !== _feedHolder[ 'feedType' ] && _feedHolder[ 'promotions' ]
		? wppfm_filterActiveMetaData( _feedHolder[ 'attributes' ], _feedHolder[ 'categoryMapping' ] )
		: wpppfm_convertPromotionToAttribute( _feedHolder[ 'promotions' ] );
	var feedFilter            = _feedHolder[ 'feedFilter' ];

	wppfm_updateFeedToDb(
		feedDataToStore,
		metaToStore,
		feedFilter,
		function( response ) {
			callback( response.trim() );
		}
	);
}

/**
 * Gets the full metaData array from the _feedHolder and returns a wppfm_attributeMeta object with keys and values from only the active ones.
 * Also stores the category mapping array in a wppfm_attributeMeta object.
 *
 * @param {Array}   metaData
 * @param {Array}   categoryMapping
 * @returns {Array} array with wppfm_attributeMeta objects containing meta keys and meta values
 */
function wppfm_filterActiveMetaData( metaData, categoryMapping ) {

	// make a storage place to store the changed attributes
	var activeMeta = [];

	for ( var i = 0; i < metaData.length; i ++ ) {

		// if the advised source is not equal to the advised inputs, the user has selected his own input so this needs to be stored
		if ( metaData[ i ][ 'value' ] !== undefined && metaData[ i ][ 'value' ] !== '' && metaData[ i ][ 'isActive' ] === true ) {

			// store the metadata in a Wppfm_AttributeMeta object
			activeMeta.push( new Wppfm_AttributeMeta( metaData[ i ][ 'fieldName' ], metaData[ i ][ 'value' ] ) );
		}
	}

	// also store the category mapping as meta data
	if ( categoryMapping.length > 0 ) {

		activeMeta.push( new Wppfm_AttributeMeta( 'category_mapping', categoryMapping ) );
	}

	return activeMeta;
}

function wppfm_getFeedDataToStore( feedData ) {
	var result   = [];

	for (var i = 0; i < feedData.length; i++) {
		var dataItem = {
			'name' : feedData[i]['db'],
			'value' : undefined !== _feedHolder[ feedData[i]['feed'] ] ? _feedHolder[ feedData[i]['feed'] ] : '',
			'type' : feedData[i]['type']
		};

		result.push( dataItem );
	}

	return result;
}

function wppfm_getFileNameFromForm() {
	var standardFileNameElement = jQuery( '#wppfm-feed-file-name' );

	// verify if the filename is valid
	if ( false === wppfm_validateFileName( standardFileNameElement.val() ) ) {
		standardFileNameElement.val( '' );
	}

	return standardFileNameElement.val();
}

function wppfm_standardProductFeedSelected() {
	// now clear the product feed form and place the correct Feed elements for a standard product feed
	wppfm_initializeStandardProductFeedForm( wppfm_getFileNameFromForm() );
}
