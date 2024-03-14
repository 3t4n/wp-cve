function wpprfm_saveFeedData() {
	if ( '' !== jQuery( '#wppfm-feed-file-name' ).val() && '' !== jQuery( '#wpprfm-publisher-name' ).val() ) {
		wppfm_saveFeed();
	} else {
		//noinspection JSUnresolvedVariable
		jQuery( '#alert-message' ).
			html( '<p>' + wppfm_feed_settings_form_vars.file_name_required + '</p>' );
		jQuery( '#wppfm-success-message' ).show();
	}
}

/**
 * Gets called when an input on an edit form for a Google Review Feed is changed by the user and the edit form needs to be updated.
 * Starts the wpprfm_finishOrUpdateReviewFeed() function to update the feed edit form and creates a new feed if required.
 *
 * @param {string} feedId
 */
function wpprfm_updateFeedFormAfterInputChanged( feedId ) {

	wpprfm_finishOrUpdateReviewFeed();

	// make a new feed object if it has not been already
	if ( feedId === undefined || feedId < 1 ) {
		wpprfm_constructNewReviewFeed();
	}
}

/**
 * Gets triggered when the user clicks on the Save & Generate Feed button.
 */
function wpprfm_startReviewFeedGeneration() {
	if ( jQuery( '#wppfm-feed-file-name' ).val() !== '' ) { // A file name is required.
		if ( _reviewFeedHolder[ 'categoryMapping' ] && _reviewFeedHolder[ 'categoryMapping' ].length > 0 ) { // And at least one Shop Category needs to be selected.
			wpprfm_initiateSaveAndGenerateReviewFeed();
		} else {

			//noinspection JSUnresolvedVariable
			var userInput = confirm(
				wppfm_feed_settings_form_vars.no_category_selected
			);

			if ( userInput === true ) { // if the user really insists on proceeding.
				wpprfm_initiateSaveAndGenerateReviewFeed();
			}
		}
	} else {
		//noinspection JSUnresolvedVariable
		jQuery( '#alert-message' ).
			html( '<p>' + wppfm_feed_settings_form_vars.file_name_required + '</p>' );
		jQuery( '#wppfm-success-message' ).show();
	}
}
