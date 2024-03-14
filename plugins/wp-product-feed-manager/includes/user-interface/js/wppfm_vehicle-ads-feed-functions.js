function wppfm_showVehicleAdsFeedInputs() {
  document.getElementById( 'wppfm-merchants-selector' ).style.display        = 'none';
  document.getElementById( 'selected-merchant' ).style.display               = 'block';
  document.getElementById( 'selected-merchant' ).textContent                 = 'Google Merchant Center';
  document.getElementById( 'wppfm-feed-types-list-row' ).style.display       = 'table-row'
  document.getElementById( 'wppfm-feed-types-selector' ).style.display       = 'none'
  document.getElementById( 'wppfm-selected-google-feed-type' ).style.display = 'block'
  document.getElementById( 'wppfm-selected-google-feed-type' ).textContent   = 'Vehicle Ads Feed'
  document.getElementById( 'wppfm-country-list-row' ).style.display          = 'none'
  document.getElementById( 'category-list-row' ).style.display               = 'none'
  document.getElementById( 'add-product-variations-row' ).style.display      = 'table-row'
  document.getElementById( 'google-feed-title-row' ).style.display           = 'table-row'
  document.getElementById( 'google-feed-description-row' ).style.display     = 'table-row'
  document.getElementById( 'aggregator-selector-row' ).style.display         = 'none'
  document.getElementById( 'update-schedule-row' ).style.display             = 'table-row'
}

function wppfm_vehicleAdsFeedSelected() {
  // now clear the product feed form and place the correct Vehicle Ads Feed elements
  wppfm_initializeStandardProductFeedForm( wppfm_getFileNameFromForm(), 'google-vehicle-ads-feed' );
}
