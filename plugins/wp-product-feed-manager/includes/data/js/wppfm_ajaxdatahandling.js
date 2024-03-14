// noinspection JSUnresolvedReference

var myAjaxNonces;

function wppfm_getFeedList( callback ) {

	jQuery.post(
		myAjaxNonces.ajaxurl,
		{
			action: 'myajax-get-list-of-feeds',
			postFeedsListNonce: myAjaxNonces.postFeedsListNonce,

		},
		function( response ) {

			callback( wppfm_validateResponse( response ) );
		}
	);
}

function wppfm_getBackupsList( callback ) {
	jQuery.post(
		myAjaxNonces.ajaxurl,
		{
			action: 'myajax-get-list-of-backups',
			postBackupListNonce: myAjaxNonces.postBackupListNonce,

		},
		function( response ) {

			callback( wppfm_validateResponse( response ) );
		}
	);
}

function wppfm_getSettingsOptions( callback ) {

	jQuery.post(
		myAjaxNonces.ajaxurl,
		{
			action: 'myajax-get-settings-options',
			postSetupOptionsNonce: myAjaxNonces.postSetupOptionsNonce,

		},
		function( response ) {

			callback( wppfm_validateResponse( response ) );
		}
	);
}

/**
 * Reads and returns all possible output fields from the selected merchant
 *
 * @param {int} feedId
 * @param {int} channelId
 * @param {string} feedType
 * @param callback
 * @returns list with output fields
 */
function wppfm_getOutputFields( feedId, channelId, feedType, callback ) {

	jQuery.post(
		myAjaxNonces.ajaxurl,
		{
			action: 'myajax-get-output-fields',
			feedId: feedId,
			channelId: channelId,
			feedType: feedType,
			outputFieldsNonce: myAjaxNonces.outputFieldsNonce,

		},
		function( response ) {

			callback( wppfm_validateResponse( response ) );
		}
	);
}

/**
 * Reads and returns all possible source fields from the selected source
 *
 * @param {int} sourceId
 * @param callback
 * @returns list with input fields
 */
function wppfm_getSourceFields( sourceId, callback ) {

	jQuery.post(
		myAjaxNonces.ajaxurl,
		{
			action: 'myajax-get-input-fields',
			sourceId: sourceId,
			inputFieldsNonce: myAjaxNonces.inputFieldsNonce,

		},
		function( response ) {

			callback( wppfm_validateResponse( response ) );
		}
	);
}

function wppfm_getMainFeedFilters( feedId, callback ) {

	jQuery.post(
		myAjaxNonces.ajaxurl,
		{
			action: 'myajax-get-main-feed-filters',
			feedId: feedId,
			inputFeedFiltersNonce: myAjaxNonces.inputFeedFiltersNonce,

		},
		function( response ) {

			callback( wppfm_validateResponse( response ) );
		}
	);
}

function wppfm_getNextCategories( channelId, requestedLevel, parentCategory, language, callback ) {

	jQuery.post(
		myAjaxNonces.ajaxurl,
		{
			action: 'myajax-get-next-categories',
			channelId: channelId,
			requestedLevel: requestedLevel,
			parentCategory: parentCategory,
			fileLanguage: language,
			nextCategoryNonce: myAjaxNonces.nextCategoryNonce,

		},
		function( response ) {

			response = response.trim();

			if ( response.substring( response.length - 1 ) === '0' ) {
				response = response.substring( 0, response.length - 1 );
			}

			callback( wppfm_validateResponse( response ) );
		}
	);
}

function wppfm_getCategoryListsFromString( channelId, mainCategoriesString, language, callback ) {

	jQuery.post(
		myAjaxNonces.ajaxurl,
		{
			action: 'myajax-get-category-lists',
			channelId: channelId,
			mainCategories: mainCategoriesString,
			fileLanguage: language,
			categoryListsNonce: myAjaxNonces.categoryListsNonce,

		},
		function( response ) {

			callback( wppfm_validateResponse( response ) );
		}
	);
}

function wppfm_updateFeedToDb( feedData, metaData, feedFilter, callback ) {

	jQuery.post(
		myAjaxNonces.ajaxurl,
		{
			action: 'myajax-update-feed-data',
			feed: JSON.stringify( feedData ),
			feedFilter: feedFilter && feedFilter.length > 0 ? feedFilter[ 0 ][ 'meta_value' ] : '',
			metaData: JSON.stringify( metaData ),
			updateFeedDataNonce: myAjaxNonces.updateFeedDataNonce,
		},
		function( response ) {

			callback( wppfm_validateResponse( response ) );
		}
	);
}

function wppfm_updateFeedFile( feed_id, callback ) {
	jQuery.post(
		myAjaxNonces.ajaxurl,
		{
			action: 'myajax-update-feed-file',
			dataType: 'text',
			feedId: feed_id,
			updateFeedFileNonce: myAjaxNonces.updateFeedFileNonce,

		},
		function( response ) {

			callback( wppfm_validateResponse( response ) );
		}
	);
}

function wppfm_getCurrentFeedStatus( feedId, callback ) {
	jQuery.post(
		myAjaxNonces.ajaxurl,
		{
			action: 'myajax-get-feed-status',
			sourceId: feedId,
			feedStatusNonce: myAjaxNonces.feedStatusNonce,

		},
		function( response ) {
			callback( wppfm_validateResponse( response ) );
		}
	);
}

function wppfm_getFeedData( feedId, callback ) {

	jQuery.post(
		myAjaxNonces.ajaxurl,
		{
			action: 'myajax-get-feed-data',
			sourceId: feedId,
			feedDataNonce: myAjaxNonces.feedDataNonce,

		},
		function( response ) {

			callback( wppfm_validateResponse( response ) );
		}
	);
}

function wppfm_switchFeedStatus( feedId, callback ) {

	jQuery.post(
		myAjaxNonces.ajaxurl,
		{
			action: 'myajax-switch-feed-status',
			feedId: feedId,
			switchFeedStatusNonce: myAjaxNonces.switchFeedStatusNonce,

		},
		function( response ) {

			wppfm_switchStatusAction( feedId, response );
			callback( wppfm_validateResponse( response ) );
		}
	);
}

function wppfm_duplicateExistingFeed( feedId, callback ) {

	jQuery.post(
		myAjaxNonces.ajaxurl,
		{
			action: 'myajax-duplicate-existing-feed',
			feedId: feedId,
			duplicateFeedNonce: myAjaxNonces.duplicateFeedNonce,

		},
		function( response ) {

			if ( response.trim() ) {
				wppfm_resetFeedList();
			}

			callback( wppfm_validateResponse( response ) );
		}
	);
}

function wppfm_logMessageOnServer( message, fileName, callback ) {

	jQuery.post(
		myAjaxNonces.ajaxurl,
		{
			action: 'myajax-log-message',
			messageList: message,
			fileName: fileName,
			logMessageNonce: myAjaxNonces.logMessageNonce,

		},
		function( result ) {

			callback( result.trim() );
		}
	);
}

function wppfm_auto_feed_fix_mode( selection, callback ) {

	jQuery.post(
		myAjaxNonces.ajaxurl,
		{
			action: 'myajax-auto-feed-fix-mode-selection',
			fix_selection: selection,
			updateAutoFeedFixNonce: myAjaxNonces.setAutoFeedFixNonce,

		},
		function( response ) {

			callback( response.trim() );
		}
	);
}

function wppfm_background_processing_mode( selection, callback ) {

	jQuery.post(
		myAjaxNonces.ajaxurl,
		{
			action: 'myajax-background-processing-mode-selection',
			mode_selection: selection,
			backgroundModeNonce: myAjaxNonces.setBackgroundModeNonce,

		},
		function( response ) {

			callback( response.trim() );
		}
	);
}

function wppfm_feed_logger_status( selection, callback ) {

	jQuery.post(
		myAjaxNonces.ajaxurl,
		{
			action: 'myajax-feed-logger-status-selection',
			statusSelection: selection,
			feedLoggerStatusNonce: myAjaxNonces.setFeedLoggerStatusNonce,

		},
		function( response ) {

			callback( response.trim() );
		}
	);
}

/**
 * Sets the Show Product Identifiers option.
 *
 * @since 2.10.0.
 *
 * @param selection
 * @param callback
 */
function wppfm_show_pi_status( selection, callback ) {

	jQuery.post(
		myAjaxNonces.ajaxurl,
		{
			action: 'myajax-show-product-identifiers-selection',
			showPiSelection: selection,
			showPINonce: myAjaxNonces.setShowPINonce,

		},
		function( response ) {

			callback( response.trim() );
		}
	);
}

/**
 * Stores the WPML Use full URL resolution option.
 *
 * @since 2.15.0
 *
 * @param selection
 * @param callback
 */
function wppfm_wpml_use_full_url_resolution( selection, callback ) {

	jQuery.post(
		myAjaxNonces.ajaxurl,
		{
			action: 'myajax-wpml-use-full-url-resolution-selection',
			urlResolutionSelection: selection,
			urlResolutionNonce: myAjaxNonces.setUseFullResolutionNonce,

		},
		function( response ) {

			callback( response.trim() );
		}
	);
}

function wppfm_change_third_party_attribute_keywords( keywords, callback ) {

	jQuery.post(
		myAjaxNonces.ajaxurl,
		{
			action: 'myajax-third-party-attribute-keywords',
			keywords: keywords,
			thirdPartyKeywordsNonce: myAjaxNonces.setThirdPartyKeywordsNonce,

		},
		function( response ) {

			callback( response.trim() );
		}
	);
}

function wppfm_change_notice_mailaddress( mailAddress, callback ) {

	jQuery.post(
		myAjaxNonces.ajaxurl,
		{
			action: 'myajax-set-notice-mailaddress',
			mailaddress: mailAddress,
			noticeMailaddressNonce: myAjaxNonces.setNoticeMailaddressNonce,

		},
		function( response ) {

			callback( response.trim() );
		}
	);
}

function wppfm_change_background_processing_time_limit( limit, callback ) {

	jQuery.post(
		myAjaxNonces.ajaxurl,
		{
			action: 'myajax-background-processing-time-limit',
			limit: limit,
			batchProcessingLimitNonce: myAjaxNonces.setBatchProcessingLimitNonce,

		},
		function( response ) {

			callback( response.trim() );
		}
	);
}

function wppfm_clear_feed_process_data( callback ) {
	jQuery.post(
		myAjaxNonces.ajaxurl,
		{
			action: 'myajax-clear-feed-process-data',
			clearFeedNonce: myAjaxNonces.setClearFeedProcessNonce,

		},
		function( response ) {

			callback( response );
		}
	);
}

function wppfm_reinitiate_plugin( callback ) {
	jQuery.post(
		myAjaxNonces.ajaxurl,
		{
			action: 'myajax-reinitiate-plugin',
			reInitiateNonce: myAjaxNonces.setReInitiateNonce,

		},
		function( response ) {

			callback( response );
		}
	);
}

/**
 * Takes the response of an ajax call and checks if it's ok. When not, it will display the error and return
 * an empty list.
 *
 * @param {String} response
 *
 * @returns {String}
 */
function wppfm_validateResponse( response ) {

	response = response.trim(); // remove php ajax response white spaces

	// when the response contains no error message
	if ( response.indexOf( '<div id=\'error\'>' ) < 0 && response.indexOf( '<b>Fatal error</b>' ) < 0 && response.indexOf( '<b>Notice</b>' ) < 0 && response.indexOf( '<b>Warning</b>' ) < 0 && response.indexOf( '<b>Catchable fatal error</b>' ) < 0 && response.indexOf( '<div id="error">' ) < 0 ) {

		if ( response.indexOf( '[]' ) < 0 ) {

			if ( response !== '' ) {

				return (
					response
				);
			} else {

				return (
					'1'
				);
			}
		} else { // if it has an error message

			// return an empty list
			return (
				'0'
			);
		}
	} else {

		wppfm_showErrorMessage( response.replace( '[]', '' ) );
		wppfm_hideWorkingSpinner();

		wppfm_logMessageOnServer(
			response,
			'error',
			function( result ) {

				// return an empty list
				return (
					'0'
				);
			}
		);
	}
}

/**
 * Deletes a specific feed file
 *
 * This function first removes the file from the server and then from the feed database.
 * After that it will refresh the Feed List.
 *
 * @param {int} id
 * @param {string} feedTitle
 * @returns nothing
 */
function wppfm_deleteFeed( id, feedTitle ) {
	var feedListMessageElement = jQuery( '#feed-list-message' );

	// clear old messages
	feedListMessageElement.empty();

	// remove the file
	wppfm_removeFeedFile(
		function() {
			wppfm_showWorkingSpinner();

			// delete the file entry in the database
			wppfm_deleteFeedFromDb(
				id,
				function( response ) {
					wppfm_showWorkingSpinner();

					response = response.trim();

					if ( response === '1' ) {
						// reset the feed list
						wppfm_resetFeedList();
						wppfm_showSuccessMessage( wppfm_feed_list_form_vars.feed_removed.replace( '%feedname%', feedTitle ) );
						wppfm_hideWorkingSpinner();
					} else {
						// report the result to the user
						feedListMessageElement.append( response );
						wppfm_hideWorkingSpinner();
					}
				},
				id
			);
		},
		feedTitle
	);
}

function wppfm_removeFeedFile( callback, feedTitle ) {

	jQuery.post(
		myAjaxNonces.ajaxurl,
		{
			action: 'myajax-delete-feed-file',
			fileTitle: feedTitle,
			deleteFeedNonce: myAjaxNonces.deleteFeedNonce,

		},
		function( response ) {

			callback( wppfm_validateResponse( response ) );
		}
	);
}

function wppfm_deleteFeedFromDb( feedId, callback ) {

	jQuery.post(
		myAjaxNonces.ajaxurl,
		{
			action: 'myajax-delete-feed',
			feedId: feedId,
			deleteFeedNonce: myAjaxNonces.deleteFeedNonce,

		},
		function( response ) {

			callback( wppfm_validateResponse( response ) );
		}
	);
}

function wppfm_checkNextFeedInQueue( callback ) {
	jQuery.post(
		myAjaxNonces.ajaxurl,
		{
			action: 'myajax-get-next-feed-in-queue',
			nextFeedInQueueNonce: myAjaxNonces.nextFeedInQueueNonce,

		},
		function( response ) {

			callback( wppfm_validateResponse( response ) );
		}
	);
}

function wppfm_initiateBackup( fileName, callback ) {

	jQuery.post(
		myAjaxNonces.ajaxurl,
		{
			action: 'myajax-backup-current-data',
			fileName: fileName,
			backupNonce: myAjaxNonces.backupNonce,

		},
		function( response ) {

			callback( wppfm_validateResponse( response ) );
		}
	);
}

function wppfm_deleteBackup( fileName, callback ) {

	jQuery.post(
		myAjaxNonces.ajaxurl,
		{
			action: 'myajax-delete-backup-file',
			fileName: fileName,
			deleteBackupNonce: myAjaxNonces.deleteBackupNonce,

		},
		function( response ) {

			callback( wppfm_validateResponse( response ) );
		}
	);
}

function wppfm_restoreBackup( fileName, callback ) {

	jQuery.post(
		myAjaxNonces.ajaxurl,
		{
			action: 'myajax-restore-backup-file',
			fileName: fileName,
			restoreBackupNonce: myAjaxNonces.restoreBackupNonce,

		},
		function( response ) {

			callback( wppfm_validateResponse( response ) );
		}
	);
}

function wppfm_duplicateBackup( fileName, callback ) {

	jQuery.post(
		myAjaxNonces.ajaxurl,
		{
			action: 'myajax-duplicate-backup-file',
			fileName: fileName,
			duplicateBackupNonce: myAjaxNonces.duplicateBackupNonce,

		},
		function( response ) {

			callback( wppfm_validateResponse( response ) );
		}
	);
}
