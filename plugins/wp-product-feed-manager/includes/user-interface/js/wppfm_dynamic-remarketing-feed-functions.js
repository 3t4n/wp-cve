// noinspection DuplicatedCode

function wppfm_showDynamicRemarketingFeedInputs() {
  document.getElementById( 'wppfm-merchants-selector' ).style.display                               = 'none';
  document.getElementById( 'selected-merchant' ).style.display                                      = 'block';
  document.getElementById( 'selected-merchant' ).textContent                                        = 'Google Merchant Center';
  document.getElementById( 'wppfm-feed-types-list-row' ).style.display                              = 'table-row'
  document.getElementById( 'wppfm-feed-types-selector' ).style.display                              = 'none'
  document.getElementById( 'wppfm-selected-google-feed-type' ).style.display                        = 'block'
  document.getElementById( 'wppfm-selected-google-feed-type' ).textContent                          = 'Dynamic Remarketing Feed'
  document.getElementById( 'wppfm-country-list-row' ).style.display                                 = 'none'
  document.getElementById( 'category-list-row' ).style.display                                      = 'none'
  document.getElementById( 'add-product-variations-row' ).style.display                             = 'table-row'
  document.getElementById( 'google-feed-title-row' ).style.display                                  = 'table-row'
  document.getElementById( 'google-feed-description-row' ).style.display                            = 'table-row'
  document.getElementById( 'aggregator-selector-row' ).style.display                                = 'none'
  document.getElementById( 'update-schedule-row' ).style.display                                    = 'table-row'
  document.getElementById( 'wppfm-feed-dynamic-remarketing-business-types-list-row' ).style.display = 'table-row'
}

function wppfm_dynamicRemarketingFeedSelected() {
  // now clear the product feed form and place the correct Local Product Feed elements
  wppfm_initializeStandardProductFeedForm( wppfm_getFileNameFromForm(), 'google-dynamic-remarketing-feed' );
}

/**
 * Activates the correct attributes for the selected DRM feed type
 *
 * @param selectedDrmFeedType
 */
function wppfm_setDrmFeedTypeAttributes( selectedDrmFeedType ) {
  var attributes;

  switch ( selectedDrmFeedType ) {
    case 'Eduction':
      attributes = wppfm_educationAttributes();
      break;

    case 'Flights':
      attributes = wppfm_flightsAttributes();
      break;

    case 'Hotels and rentals':
      attributes = wppfm_hotelsAndRentalsAttributes();
      break;

    case 'Jobs':
      attributes = wppfm_jobAttributes();
      break;

    case 'Local deals':
      attributes = wppfm_localDealsAttributes();
      break;

    case 'Real estate':
      attributes = wppfm_realEstateAttributes();
      break;

    case 'Travel':
      attributes = wppfm_travelAttributes();
      break;

    case 'Custom':
      attributes = wppfm_customAttributes();
      break;

    default:
      attributes = wppfm_educationAttributes();
      break;
  }

  wppfm_showRequiredAttributes( attributes )
}

function wppfm_showRequiredAttributes( requiredAttributes ) {
  // hide all required attributes
  wppfm_hideAllRequiredAttributes();

  // show correct required attributes
  wppfm_showRequiredAttributesForType( requiredAttributes );
}

function wppfm_hideAllRequiredAttributes() {
  for ( var h = 0; h < 14; h ++ ) {
    document.getElementById( 'row-' + h ).style.display = 'none';
  }
}

function wppfm_showRequiredAttributesForType( requiredAttributes ) {
  for ( var s = 0; s < requiredAttributes.length; s ++ ) {
    document.getElementById( 'row-' + requiredAttributes[ s ] ).style.display = 'block';
  }
}

function wppfm_educationAttributes() {
  return [ '0', '1' ];
}

function wppfm_flightsAttributes() {
  return [ '2', '3' ];
}

function wppfm_hotelsAndRentalsAttributes() {
  return [ '4', '5' ];
}

function wppfm_jobAttributes() {
  return [ '6', '7' ];
}

function wppfm_localDealsAttributes() {
  return [ '8', '9' ];
}

function wppfm_realEstateAttributes() {
  return [ '10', '11' ];
}

function wppfm_travelAttributes() {
  return [ '2', '7' ];
}

function wppfm_customAttributes() {
  return [ '12', '13' ];
}
