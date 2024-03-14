//noinspection JSUnusedGlobalSymbols,JSUnusedLocalSymbols
/**
 * Gets called from the wppfm_reactOnChannelInputChanged() function of the WPPFM plugin. Validates the file name, checks if at least the required file name and
 * publisher name are filled in before enabling the feed buttons and activate the update of the feed data.
 *
 * @param {string}  feedId
 * @param {boolean} categoryChanged
 * @param {boolean} nameChanged
 */
function wppfm_googleProductReviewFeedChanged( feedId, categoryChanged, nameChanged ) {
	var productReviewFileNameElement = jQuery( '#wppfm-feed-file-name' );

	// verify if the filename is valid
	if ( nameChanged && false === wppfm_validateFileName( productReviewFileNameElement.val() ) ) {
		productReviewFileNameElement.val( '' );
	}

	// verify if the mandatory main inputs are filled in and activate the feed update
	if ( productReviewFileNameElement.val() && jQuery( '#wpprfm-publisher-name' ).val() ) {

		wpprfm_updateFeedFormAfterInputChanged( feedId );

		// show the category selector, product filter and attribute mapping wrappers
		wpprfm_showCategoryAndAttributeMappingWrappers();

		// show and activate the feed buttons
		wppfm_enableFeedActionButtons( 'google-product-review-feed' );
	} else {
		// hide the category selector, product filter and attribute mapping wrappers
		wpprfm_hideCategoryAndAttributeMappingWrappers();

		// deactivate the feed buttons
		disableFeedActionButtons( 'google-product-review-feed' );
	}
}

function wpprfm_productReviewFeedSelected() {
	// now redirect the user to the correct Review Feed page
	wpprfm_initializeProductReviewFeedForm( wppfm_getFileNameFromForm() );
}
