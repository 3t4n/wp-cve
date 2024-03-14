function wpppfm_updateFeedFormAfterInputChanged( feedId ) {
	wpppfm_finishOrUpdatePromotionsFeed();

	// make a new feed object if it has not been already
	if ( feedId === undefined || feedId < 1 ) {
		wpppfm_constructNewPromotionsFeed();
	}
}

function wpppfm_startPromotionsFeedGeneration() {
	if ( wpppfm_requiredDataIsFilledIn() ) {
		wpppfm_initiateSaveAndGeneratePromotionsFeed();
	} else {

		//noinspection JSUnresolvedVariable
		var userInput = confirm(
			wppfm_feed_settings_form_vars.not_all_required_field_filled // Warn the user that not all required fields are filled in.
		);

		if ( userInput === true ) { // if the user really insists on proceeding.
			wpppfm_initiateSaveAndGeneratePromotionsFeed();
		}
	}
}

function wpppfm_savePromotionsFeed() {
	if ( wpppfm_requiredDataIsFilledIn() ) {
		wppfm_saveFeed();

	} else {
		//noinspection JSUnresolvedVariable
		wppfm_showWarningMessage( wppfm_feed_settings_form_vars.not_all_required_field_filled );
	}

}

/**
 * Adds a new promotion element to the feed form and a new promotion to the promotionsFeedHolder.
 */
function wpppfm_addPromotion() {
	var nextPromotionId = _promotionsFeedHolder['promotions'].length;

	// Add the promotion element to the feed form
	wpppfm_addPromotionElement( nextPromotionId );

	// Add the promotion to the feed holder
	var nrOfPromotions = _promotionsFeedHolder.addPromotion();
	// Add the promotion_element_nr to the new promotion
	_promotionsFeedHolder.addPromotionElement( nrOfPromotions - 1, 'promotion_element_nr', nextPromotionId );
	// Activate the Promotion Details Selector tabs of the new promotion element
	jQuery( document.body ).trigger( 'wppfm-init-tabbed-panels' );
	// Tune the promotion elements, hide the delete button if there is only one promotion element
	wpppfm_tunePromotionElements();
}

/**
 * Removes a promotion element from the feed form and a promotion from the promotionsFeedHolder.
 *
 * @param promotionNr
 */
function wpppfm_deletePromotion( promotionNr ) {
	// Remove the promotion from the feed holder
	_promotionsFeedHolder.deletePromotion( promotionNr );
	// Remove the promotion element from the feed form
	wpppfm_removePromotionElement( promotionNr );
	// Reorder the promotion elements
	wpppfm_reorderPromotionElements();
	// Tune the promotion elements, hide the delete button if there is only one promotion element
	wpppfm_tunePromotionElements();
}

/**
 * Duplicates a promotion element in the feed form and a promotion in the promotionsFeedHolder.
 *
 * @param promotionNr
 */
function wpppfm_duplicatePromotion( promotionNr ) {
	var nextPromotionId = _promotionsFeedHolder['promotions'].length;
	// Add a copy of the selected promotion element to feed holder
	_promotionsFeedHolder.duplicatePromotion( promotionNr );
	// Add an empty promotion element to the feed form
	wpppfm_addPromotionElement( nextPromotionId );
	// Fill the promotion element with the data from the feed holder
	wpppfm_fillPromotionFields();
}
