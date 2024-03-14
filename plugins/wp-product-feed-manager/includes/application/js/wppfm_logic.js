/*global wppfm_feed_settings_form_vars, wppfm_manage_channels_vars, wppfm_feed_list_form_vars */
var _feedHolder;

function wppfm_editCategories() {
	var cat_lvl_selector = jQuery( '#category-selector-lvl' );

	if ( ! wppfm_isCustomChannel( _feedHolder[ 'channel' ] ) ) {
		var currentCategories = _feedHolder[ 'mainCategory' ].split( ' > ' );
		var cat_length        = currentCategories.length;
		var cat_selectors     = jQuery( '#lvl_' + ( cat_length ) ).html() !== '' ? cat_length + 1 : cat_length;

		jQuery( '#selected-categories' ).hide();
		jQuery( '#lvl_0' ).prop( 'disabled', false );

		for ( var i = 0; i < cat_selectors; i ++ ) {
			var levelElement = jQuery( '#lvl_' + i );

			if ( ! currentCategories[ i ] ) {
				levelElement.val( '0' );
			}
			levelElement.show();
		}
	} else {
		// as the user selected a free format, just show a text input control
		cat_lvl_selector.html(
			 wppfm_freeCategoryInputCntrl(
				'default',
				_feedHolder[ 'feedId' ],
				_feedHolder[ 'mainCategory' ]
			)
		);
		cat_lvl_selector.prop( 'disabled', false );
	}
}

function wppfm_generateFeed() {
	if ( jQuery( '#wppfm-feed-file-name' ).val() !== '' ) {
		if ( _feedHolder[ 'categoryMapping' ] && _feedHolder[ 'categoryMapping' ].length > 0 ) {
			disableFeedActionButtons();
			wppfm_generateAndSaveFeed();
		} else {
			var userInput = confirm(
				wppfm_feed_settings_form_vars.no_category_selected
			);

			if ( userInput === true ) {
				disableFeedActionButtons();
				wppfm_generateAndSaveFeed();
			}
		}
	} else {
		jQuery( '#alert-message' ).
			html( '<p>' + wppfm_feed_settings_form_vars.file_name_required + '</p>' );
		jQuery( '#wppfm-success-message' ).show();
	}
}

function wppfm_saveFeedData() {
	if ( jQuery( '#wppfm-feed-file-name' ).val() !== '' ) {
		wppfm_saveFeed();
	} else {
		jQuery( '#alert-message' ).
			html( '<p>' + wppfm_feed_settings_form_vars.file_name_required + '</p>' );
		jQuery( '#wppfm-success-message' ).show();
	}
}

function getCombinedValue( rowId, sourceLevel ) {
	var c             = 1;
	var combinedValue = '';
	var oldValue      = _feedHolder.getCombinedOutputValue( rowId, sourceLevel );

	while ( jQuery( '#combined-input-field-cntrl-' + rowId + '-' + sourceLevel + '-' + c ).
		val() ) {
		var idString = rowId + '-' + sourceLevel + '-' + c;

		var selectedValue = jQuery( '#combined-input-field-cntrl-' + idString ).
			val();

		combinedValue += c > 1 ?
			jQuery( '#combined-separator-cntrl-' + idString ).val() + '#' :
			'';

		if ( selectedValue !== 'static' ) {
			combinedValue += selectedValue !== 'select' ?
				selectedValue + '|' :
				'';
		} else if ( jQuery( '#static-input-field-' + idString ).val() ) {
			combinedValue += selectedValue + '#' + jQuery( '#static-input-field-' + idString ).val() + '|';
		} else {
			combinedValue = oldValue + '|';
			break; // if one of the static input fields is still empty, return the old value
		}

		c ++;
	}

	combinedValue = combinedValue.substring( 0, combinedValue.length - 1 ); // remove the last |

	return c > 1 ? combinedValue : false; // need at least two fields to be valid
}

function wppfm_staticValueChanged( id, level, combinationLevel ) {
	if ( combinationLevel > 0 ) { // the static field resides in a combination source
		wppfm_changedCombinedOutput( id, level, combinationLevel );
	} else {
		// store the change in the feed
		wppfm_setStaticValue( id, level, combinationLevel );

		// when the identifier_exists static value has changed, the level of a few attributes should be changed
		if ( id === 34 ) {
			wppfm_setIdentifierExistsDependencies();
		}
	}
}

function wppfm_changedOutputSelection( level ) {
	var outputFieldControlElement = jQuery( '#output-field-cntrl-' + level );

	if ( outputFieldControlElement.val() !== 'no-value' ) {
		wppfm_activateOptionalFieldRow( level, outputFieldControlElement.val() );
	}
}

function wppfm_hasExtraSourceRow( nrOfSources, value ) {
	if ( value && value[ nrOfSources - 1 ] ) {
		return value[ nrOfSources - 1 ].hasOwnProperty( 'c' );
	} else {
		return false;
	}
}

function wppfm_changedCustomOutputTitle() {
	var title = jQuery( '#custom-output-title-input' ).val();

	if ( title ) {
		wppfm_activateCustomFieldRow( title );
	}
}

function wppfm_deleteSpecificFeed( id, title ) {
	var userInput = confirm( wppfm_feed_list_form_vars.confirm_delete_feed.replace( '%feedname%', title ) );

	if ( userInput === true ) {
		wppfm_deleteFeed( id, title );
		console.log( 'File ' + title + ' removed from server.' );
	}
}

function wppfm_valueOptionChanged( rowId, sourceLevel, valueEditorLevel ) {
	var type = jQuery( '#value-options-' + rowId + '-' + sourceLevel + '-' + valueEditorLevel ).val();

	//var selectorCode = wppfm_getCorrectValueSelector( rowId, sourceLevel, valueEditorLevel, type, '', '' );
	var selectorCode = wppfm_getCorrectValueSelector( rowId, sourceLevel, 0, type, '', '' );

	jQuery( '#value-editor-input-span-' + rowId + '-' + sourceLevel + '-' + valueEditorLevel ).html( selectorCode );
}

function wppfm_getCorrectValueSelector(
	rowId, sourceLevel, valueEditorLevel, type, value, endValue ) {
	var selectorCode = '';

	// TODO: the type is now based on the value and on the text. Should be only value as this makes it
	// easier to work with different languages
	switch ( type ) {
		case '0':
		case 'change nothing':
		// @since 2.22.0.
		case '8':
		case 'strip tags':
		// @since 2.34.0.
		case '9':
		case 'html entity decode':
			wppfm_valueInputOptionsChanged( rowId, sourceLevel, valueEditorLevel ); // save the value in meta now as there is no second input field required.
			selectorCode = '';
			break;

		case '1':
		case 'overwrite':
			selectorCode = wppfm_valueOptionsSingleInput( rowId, sourceLevel, valueEditorLevel, value );
			break;

		case '2':
		case 'replace':
			selectorCode = wppfm_valueOptionsReplaceInput( rowId, sourceLevel, valueEditorLevel, value, endValue );
			break;

		case '3':
		case 'remove':
		case '4':
		case 'add prefix':
		case '5':
		case 'add suffix':
			selectorCode = wppfm_valueOptionsSingleInputValue( rowId, sourceLevel, valueEditorLevel, value );
			break;

		case '6':
		case 'recalculate':
			selectorCode = wppfm_valueOptionsRecalculate( rowId, sourceLevel, valueEditorLevel, value, endValue );
			break;

		case '7':
		case 'convert to child-element':
			selectorCode = wppfm_valueOptionsElementInput( rowId, sourceLevel, valueEditorLevel, value );
			break;

		// @since 2.22.0.
		case '10':
		case 'limit characters':
			selectorCode = wppfm_valueOptionsSingleInput( rowId, sourceLevel, valueEditorLevel, value );
			break;

		default:
			selectorCode = wppfm_valueOptionsSingleInput( rowId, sourceLevel, valueEditorLevel, value );
			break;
	}

	return selectorCode;
}

function wppfm_deactivateFeed( id ) {
	wppfm_switchFeedStatus(
		id,
		function( result ) {
			wppfm_updateFeedRowStatus( id, parseInt( result ) );
		}
	);
}

function wppfm_duplicateFeed( id, feedName ) {
	wppfm_showWorkingSpinner();
	wppfm_duplicateExistingFeed(
		id,
		function( result ) {
			if ( result ) {
				wppfm_showSuccessMessage( wppfm_feed_list_form_vars.added_feed_copy.replace( '%feedname%', feedName ) );
				wppfm_hideWorkingSpinner();
			}
		}
	);
}

function wppfm_regenerateFeed( feedId ) {
	// when there's already a feed processing, then the status should be "in queue", else status should set to "processing"
	var feedStatus = wppfmQueueStringIsEmpty() ? 3 : 4;

	wppfmAddToQueueString( feedId );

	wppfm_showWorkingSpinner();

	wppfm_updateFeedRowStatus( feedId, feedStatus );

	console.log( 'Started regenerating feed ' + feedId );

	wppfm_updateFeedFile( feedId, function( xmlResult ) {

		wppfm_hideWorkingSpinner();

		if ( xmlResult.includes('channel_not_installed') ) {
			var channelName = xmlResult.split(':')[1];
			alert( wppfm_feed_list_form_vars.missing_channel.replaceAll( '%channelname%', channelName ) );
			wppfm_updateFeedRowStatus( feedId, 7);
			wppfmRemoveFromQueueString( feedId );
			return;
		}

		console.log(xmlResult);

		// activate the feed list status checker to update the feed list when a status changes
		var checkStatus = setInterval( function(){
			wppfm_getCurrentFeedStatus( feedId, function( statResult ) {
				var data = JSON.parse( statResult );
				if ('3' !== data[ 'status_id' ] && '4' !== data[ 'status_id' ]) {
					console.log( data );
					wppfm_resetFeedStatus( data );
					wppfm_resetFeedList();
					clearInterval( checkStatus );
					wppfmRemoveFromQueueString( feedId );
				}
			} );
		}, 10000 );
	})
}

/**
 * Opens the feed in a new window
 *
 * @since 2.39.0 Fixed an issue where the "View feed" functions would not work if the user has the "Relative URL" plugin active.
 * @param url
 */
function wppfm_viewFeed( url ) {
	if ( -1 !== url.indexOf( 'wp-content/uploads/' ) ) { // Filter out duplicate feeds that have not been generated yet.
		window.open(url);
	} else {
		alert( wppfm_feed_list_form_vars.feed_not_generated );
	}

	// deselect the view feed button
	wppfm_enableViewFeedButtons()
}

function wppfm_addRowValueEditor(
	rowId, sourceLevel, valueEditorLevel, values ) {
	// add the change values controls
	jQuery( '#row-' + rowId ).
		append( wppfm_valueEditor( rowId, sourceLevel, valueEditorLevel, values ) );

	// and remove the edit values control
	jQuery( '#value-editor-input-query-add-span-' + rowId + '-' + sourceLevel + '-' + valueEditorLevel ).remove();
}

/**
 * Takes an array of words and puts them together in a camel structure way.
 *
 * @param {array}    stringArray     contains the words from which the string should be generated
 *
 * @returns {string} camel structured string
 */
function wppfm_convertToCamelCase( stringArray ) {
	// first word should remain lowercase
	var result = stringArray[ 0 ].toLowerCase();

	for ( var i = 1; i < stringArray.length; i++ ) {
		result += stringArray[ i ].charAt( 0 ).toUpperCase() + stringArray[ i ].slice( 1 );
	}

	return result;
}

function wppfm_addValueEditorQuery( rowId, sourceLevel, conditionLevel ) {
	if ( wppfm_changeValueIsFilled( rowId, sourceLevel, conditionLevel ) ) {
		if ( wppfm_queryIsFilled(
			rowId,
			(
				sourceLevel - 1
			),
			1
		)
		) {
			wppfm_showEditValueQuery( rowId, sourceLevel, conditionLevel, true );
		} else {
			alert( wppfm_feed_settings_form_vars.query_requirements );
		}
	} else {
		alert( wppfm_feed_settings_form_vars.first_fill_in_change_value );
	}
}

function wppfm_queryStringToQueryObject( queryString ) {
	var queryObject = {};

	if ( queryString ) {
		for ( var key in queryString ) {
			queryObject = wppfm_convertQueryStringToQueryObject( queryString[ key ] );
		}
	}

	return queryObject;
}

function wppfm_valueStringToValueObject( valueString ) {
	var valueObject = {};

	if ( valueString ) {
		for ( var key in valueString ) {
			// do not process the query part of the string
			if ( key !== 'q' ) {
				valueObject = wppfm_convertValueStringToValueObject( valueString[ key ] );
			}
		}
	}

	return valueObject;
}

function wppfm_convertQueryStringToQueryObject( queryString ) {
	var queryObject = {};

	var stringSplit = queryString.split( '#' );

	if ( stringSplit[ 0 ] === '1' || stringSplit[ 0 ] === '2' ) {
		queryObject.preCondition = stringSplit[ 0 ];
	} else {
		queryObject.preCondition = '0';
	}

	queryObject.source    = stringSplit[ 1 ];
	queryObject.condition = stringSplit[ 2 ];
	queryObject.value     = stringSplit[ 3 ] ? stringSplit[ 3 ] : '';
	queryObject.endValue  = stringSplit[ 5 ] ? stringSplit[ 5 ] : '';

	return queryObject;
}

function wppfm_resortObject( object ) {
	var result = [];
	var i      = 1;

	// re-sort the conditions
	for ( var element in object ) {
		var o = {};
		for ( var key in object[ element ] ) {
			if ( key !== 'q' ) { // exclude q as key
				o[ i ] = object[ element ][ key ];
				result.push( o );
			} else {
				result[ i - 1 ].q = object[ element ][ key ];
			}
		}

		i ++;
	}

	// don't return an empty {} string
	return i > 1 ? result : '';
}

function wppfm_convertValueStringToValueObject( valueString ) {
	var valueObject = {};
	var valueSplit  = valueString.split( '#' );

	valueObject.preCondition = valueSplit[ 0 ];
	valueObject.condition    = valueSplit[ 1 ];
	valueObject.value        = valueSplit[ 2 ];
	valueObject.endValue     = valueSplit[ 3 ] ? valueSplit[ 3 ] : '';

	return valueObject;
}

function wppfm_makeCleanQueryObject() {
	var queryObject = {};

	queryObject.preCondition = 'if';
	queryObject.source       = 'select';
	queryObject.condition    = '';
	queryObject.value        = '';
	queryObject.endValue     = '';

	return queryObject;
}

function wppfm_makeCleanValueObject() {
	var valueObject = {};

	valueObject.preCondition = 'change';
	valueObject.condition    = 'overwrite';
	valueObject.value        = '';
	valueObject.endValue     = '';

	return valueObject;
}

function wppfm_addNewItemToCategoryString(
	level, oldString, newValue, separator ) {
	var categoryLevel = oldString.split( separator ).length;

	if ( oldString === wppfm_feed_settings_form_vars.map_to_default_category || level === '0' ) {
		return newValue;
	} else {
		if ( categoryLevel <= level ) {
			return oldString + separator + newValue;
		} else {
			var pos = 0;

			for ( var i = 0; i < level; i ++ ) {
				pos         = oldString.indexOf( separator, pos + 1 );
				var oldPart = oldString.substring( 0, pos );
			}

			return oldPart + separator + newValue;
		}
	}
}

function wppfm_snakeToCamel(str) {
	var words = str.split('_');
	var camelWords = words.map((word, index) => {
		if (index === 0) {
			return word;
		}
		return word.charAt(0).toUpperCase() + word.slice(1);
	});
	return camelWords.join('');
}
