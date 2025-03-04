/**
 * Makes a clone of the promotion template, fills it with the promotion data and appends it to the promotions group area.
 *
 * @param promotionId
 */
function wpppfm_addPromotionElement( promotionId ) {
	var promotionTemplate = jQuery('#wpppfm-promotion-wrapper-template');
	var newPromotionElement = promotionTemplate.clone(); // Clone the new element

	wpppfm_changePromotionElementIds(newPromotionElement, 'template', promotionId); // Change the id's of the new element

	// And now append the new promotion element to the promotions group area
	jQuery('.wpppfm-promotions-group-area').append(newPromotionElement);
	newPromotionElement.show();

	wpppfm_activateSelect2Elements(promotionId); // Activate the select2 elements
	wpppfm_initiateDatePickers(promotionId); // Activate the date pickers
}

/**
 * Changes the id's of the new element.
 *
 * @param promotionElement
 * @param oldElementId
 * @param newElementId
 */
function wpppfm_changePromotionElementIds( promotionElement, oldElementId, newElementId ) {
	promotionElement.attr('id', `wpppfm-promotion-wrapper-${newElementId}`); // Set the new element's id

	// Find the section elements and replace the template id with the correct id related to the newElementId
	promotionElement.find('section').each(function () {
		var originalId = jQuery(this).attr('id');
		var newId = originalId.replace(oldElementId, newElementId);
		jQuery(this).attr('id', newId);
	});

	// Find the div elements and replace the template id with the correct id related to the newElementId
	promotionElement.find('div').each(function () {
		var originalDivId = jQuery(this).attr('id');
		if ( originalDivId ) {
			var newDivId = originalDivId.replace(oldElementId, newElementId);
			jQuery(this).attr('id', newDivId);
		}
	});

	// Find the div elements and replace the template name with the correct name related to the newElementId
	promotionElement.find('div').each(function () {
		var originalDivName = jQuery(this).attr('name');
		if ( originalDivName ) {
			var newDivId = originalDivName.replace(oldElementId, newElementId);
			jQuery(this).attr('name', newDivId);
		}
	});

	// Find the table row elements and replace the template id with the correct id related to the newElementId
	promotionElement.find('tr').each(function () {
		var originalTableRowId = jQuery(this).attr('id');
		var newTableRowId = originalTableRowId.replace(oldElementId, newElementId);
		jQuery(this).attr('id', newTableRowId);
	});

	// Find the input fields and replace the template id with the correct id related to the newElementId
	promotionElement.find('input').each(function () {
		var originalInputId = jQuery(this).attr('id');
		var newInputId = originalInputId.replace(oldElementId, newElementId);
		var attributeKey = jQuery(this).attr('data-attribute-key');
		jQuery(this).attr('id', newInputId);
		jQuery(this).attr('onChange', `wpppfm_promotionsInputChanged('${attributeKey}', '${newElementId}')`);
	});

	// Find the select elements and replace the template id with the correct id related to the newElementId
	promotionElement.find('select').each(function () {
		var originalSelectId = jQuery(this).attr('id');
		var newSelectId = originalSelectId.replace(oldElementId, newElementId);
		var attributeKey = jQuery(this).attr('data-attribute-key');
		jQuery(this).attr('id', newSelectId);
		jQuery(this).attr('data-select2-id', 'select2-data-' + newSelectId);
		jQuery(this).attr('onChange', `wpppfm_promotionsInputChanged('${attributeKey}', '${newElementId}')`);
	});

	// Find the label elements and replace the template id with the correct id related to the newElementId
	promotionElement.find('label').each(function () {
		var originalLabelId = jQuery(this).attr('for');
		if ( originalLabelId ) {
			var newLabelId = originalLabelId.replace(oldElementId, newElementId);
			jQuery(this).attr('for', newLabelId);
		}
	});

	// Find the link elements and replace the template id with the correct id related to the newElementId. Also add the correct newElementId to the onClick event.
	promotionElement.find('a').each(function () {
		var originalHref = jQuery(this).attr('href');
		var newHref = originalHref.replace(oldElementId, newElementId);
		jQuery(this).attr('href', newHref);

		var originalButtonsId = jQuery(this).attr('id');
		if ( originalButtonsId ) {
			var newButtonsId = originalButtonsId.replace(oldElementId, newElementId);
			jQuery(this).attr('id', newButtonsId);
		}

		var originalOnClick = jQuery(this).attr('onClick');
		if ( originalOnClick ) {
			var newOnClick = originalOnClick.replace(oldElementId, newElementId);
			jQuery(this).attr('onClick', newOnClick);
		}
	});
}

function wpppfm_activateSelect2Elements( promotionId ) {
	jQuery(`#wpppfm-promotion-destination-input-field-${promotionId}`).select2({
		data: _promotionsFeedHolder['promotion_destination_options']
	});
	jQuery(`#wpppfm-product-filter-selector-include-input-field-${promotionId}`).select2({
		data: _promotionsFeedHolder['promotion_filter_options']
	});
	jQuery(`#wpppfm-product-filter-selector-exclude-input-field-${promotionId}`).select2({
		data: _promotionsFeedHolder['promotion_filter_options']
	});
}

function wpppfm_tunePromotionElements() {
	if ( 1 >= _promotionsFeedHolder['promotions'].length ) {
		jQuery('#wpppfm-promotion-delete-button-0').hide();
	} else {
		jQuery('#wpppfm-promotion-delete-button-0').show();
	}
}

function wpppfm_reorderPromotionElements() {
	var index = 0;

	// Loop through all the promotion elements and reorder the id's
	jQuery( '.wpppfm-promotion-wrapper' ).each( function() {
		var promotionWrapperId = jQuery(this).attr('id');
		var promotionWrapperNr = promotionWrapperId.split('-')[3];
		if ( 'template' !== promotionWrapperNr ) {
			wpppfm_changePromotionElementIds( jQuery(this), promotionWrapperNr, index );
			index++;
		}
	} );
}

function wpppfm_removePromotionElement( promotionId ) {
	jQuery(`#wpppfm-promotion-wrapper-${promotionId}`).remove();
}
