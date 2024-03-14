var _googleClothingAndAccessories = false;
var _googleNeedsProductCat        = false;
var _googleRequiresBrand          = true;

// ALERT! This function is equivalent for the woocommerce_to_feed_fields() function in class-data.php
function woocommerceToGoogleFields() {
	return {
		'id': '_sku',
		'title': 'post_title',
		'google_product_category': 'category',
		'description': 'post_content',
		'link': 'permalink',
		'image_link': 'attachment_url',
		'additional_image_link': '_wp_attachement_metadata',
		'item_group_id': 'item_group_id',
		'mpn': 'ID',
		'product_type': 'product_cat_string',
		'tax': 'Use the settings in the Merchant Center',
		'shipping': 'Use the settings in the Merchant Center',
		'rank': 'post_id',
		'product_item_id': '_sku',
		'item_url': 'permalink',
		'image_url': 'attachment_url',
	};
}

// ALERT! This function is equivalent for the set_google_output_attribute_levels() function in class-data.php
function setGoogleOutputAttributeLevels( feedHolder, targetCountry ) {
	for ( var i = 0; i < feedHolder[ 'attributes' ].length; i ++ ) {

		if ( feedHolder[ 'attributes' ][ i ][ 'fieldLevel' ] === '0' ) {

			switch ( feedHolder[ 'attributes' ][ i ][ 'fieldName' ] ) {

				case 'google_product_category':

					if ( _googleNeedsProductCat === true ) {
						feedHolder[ 'attributes' ][ i ][ 'fieldLevel' ] = '1';
					} else {
						feedHolder[ 'attributes' ][ i ][ 'fieldLevel' ] = '4';
					}

					break;

				case 'is_bundle':
				case 'multipack':

					if ( jQuery.inArray( targetCountry, googleSpecialProductCountries() ) < 0 ) {
						feedHolder[ 'attributes' ][ i ][ 'fieldLevel' ] = '4';
					} else {
						feedHolder[ 'attributes' ][ i ][ 'fieldLevel' ] = '1';
					}

					break;

				case 'brand':

					if ( _googleRequiresBrand === true ) {
						feedHolder[ 'attributes' ][ i ][ 'fieldLevel' ] = '1';
					} else {
						feedHolder[ 'attributes' ][ i ][ 'fieldLevel' ] = '4';
					}

					break;

				case 'item_group_id':

					if ( jQuery.inArray( targetCountry, googleSpecialClothingGroupCountries() ) > - 1 ) {
						feedHolder[ 'attributes' ][ i ][ 'fieldLevel' ] = '1';
					} else {
						feedHolder[ 'attributes' ][ i ][ 'fieldLevel' ] = '4';
					}

					break;

				case 'gender':
				case 'age_group':
				case 'color':
				case 'size':

					if ( jQuery.inArray( targetCountry, googleSpecialClothingGroupCountries() ) > - 1 && _googleClothingAndAccessories === true ) {
						feedHolder[ 'attributes' ][ i ][ 'fieldLevel' ] = '1';
					} else {
						feedHolder[ 'attributes' ][ i ][ 'fieldLevel' ] = '4';
					}

					break;

				case 'tax':

					// In accordance with the Google Feed Specifications update of september 2015
					if ( targetCountry === 'US' ) {

						feedHolder[ 'attributes' ][ i ][ 'fieldLevel' ] = '1';
					} else {

						feedHolder[ 'attributes' ][ i ][ 'fieldLevel' ] = '4';
					}

					break;

				case 'shipping':

					// In accordance with the Google Feed Specifications update of september 2015
					if ( jQuery.inArray( targetCountry, googleSpecialShippingCountries() ) > - 1 ) {

						feedHolder[ 'attributes' ][ i ][ 'fieldLevel' ] = '1';
					} else {

						feedHolder[ 'attributes' ][ i ][ 'fieldLevel' ] = '4';
					}

					break;

				case 'subscription_costs':
				case 'subscription_cost-period':
				case 'subscription_cost-period_length':
				case 'subscription_cost-amount':

					if ( jQuery.inArray( targetCountry, googleSpecialSubscriptionCountries()) > - 1 ) {
						feedHolder[ 'attributes' ][ i ][ 'fieldLevel' ] = '4';
					} else {
						feedHolder[ 'attributes' ][ i ][ 'fieldLevel' ] = '0';
					}

					break;

				default:
					break;

			}

			// set the attribute to active if it's a recommended or highly recommended attribute, or if it has a value
			feedHolder[ 'attributes' ][ i ][ 'isActive' ] = setAttributeStatus( parseInt( feedHolder[ 'attributes' ][ i ][ 'fieldLevel' ] ), feedHolder[ 'attributes' ][ i ][ 'value' ] );
		}
	}

	return feedHolder;
}

// ALERT! Make sure that if you add or remove an attribute from this list, you also change the attribute in the wppfm_setChannelRelatedPresets() function
function setGooglePresets( field ) {
	switch ( field ) {
		case 'condition':
			return '{"m":[{"s":{"static":"new"}}]}';

		case 'availability':
			return '{"m":[{"s":{"static":"in_stock"},"c":[{"1":"0#_stock_status#0#instock"}]},{"s":{"static":"out_of_stock"}}]}';

		case 'identifier_exists':
			return '{"m":[{"s":{"static":"yes"}}]}';

		case 'adult':
			return '{"m":[{"s":{"static":"no"}}]}';

		case 'is_bundle':
			return '{"m":[{"s":{"static":"no"}}]}';

		case 'gender':
			return '{"m":[{"s":{"static":"unisex"}}]}';

		case 'store_code':
			return '{"m":[{"s":{"static":"Replace by your store code"}}]}';

		case 'price':
    case 'vehicle_all_in_price':
			return '{"m":[{"s":{"source":"combined","f":"_regular_price|1#wc_currency"}}]}';

		case 'sale_price':
      return '{"m":[{"s":{"source":"combined","f":"_sale_price|1#wc_currency"}}]}';

    case 'sell_on_google_minimum_advertised_price':
    case 'sell_on_google_price':
    case 'auto_pricing_min_price':
			return '{"m":[{"s":{"source":"combined","f":"|1#wc_currency"}}]}';

		case 'sale_price_effective_date':
			return '{"m":[{"s":{"source":"combined","f":"_sale_price_dates_from|7#_sale_price_dates_to"},"c":[{"1":"0#_sale_price#5"}]},{"s":{"source":"empty"}}]}';

    case 'vehicle_fullfillment':
      return '{"m":[{"s":{"static":"in_store"}}]}';

    case 'vehicle_price_type':
      return '{"m":[{"s":{"static":"all_in_price"}}]}';

		default:
			break;
	}
}

function fillGoogleCategoryVariables( selectedCategory, currentLevel ) {
	switch ( currentLevel ) {
		case 'lvl_0':
		case 'lvl_1':
			_googleClothingAndAccessories = false;
			_googleNeedsProductCat        = false;
			_googleRequiresBrand          = true;
			break;
	}

	switch ( selectedCategory ) {
		case 'Clothing':
			_googleClothingAndAccessories = true;
			_googleNeedsProductCat        = true;
			_googleRequiresBrand          = true;
			break;

		case 'Software':
		case 'Apparel & Accessories':
			_googleClothingAndAccessories = true;
			_googleNeedsProductCat        = true;
			_googleRequiresBrand          = true;
			break;

		case 'Media':
			_googleClothingAndAccessories = false;
			_googleNeedsProductCat        = true;
			_googleRequiresBrand          = false;
			break;

		default:
			break;
	}
}

function googleStaticFieldOptions( fieldName ) {
	var options = [];

	switch ( fieldName ) {
		case 'condition':
			options = [ 'new', 'used', 'refurbished' ];
			break;

		case 'availability':
			options = [ 'in_stock', 'out_of_stock', 'preorder', 'backorder', 'limited_availability', 'on_display_to_order' ];
			break;

		case 'identifier_exists':
		case 'adult':
		case 'signature_required':
		case 'is_bundle':
    case 'certified_pre-owned':
			options = [ 'yes', 'no' ];
			break;

		case 'gender':
			options = [ 'unisex', 'male', 'female' ];
			break;

		case 'age_group':
			options = [ 'adult', 'newborn', 'infant', 'toddler', 'kids' ];
			break;

		case 'size_type':
			options = [ 'regular', 'petite', 'plus', 'big and tall', 'maternity' ];
			break;

		case 'size_system':
			options = [ 'EU', 'US', 'UK', 'DE', 'FR', 'JP', 'CN', 'IT', 'BR', 'MEX', 'AU' ];
			break;

		case 'energy_efficiency_class':
		case 'min_energy_efficiency_class':
		case 'max_energy_efficiency_class':
			options = [ 'A', 'A+', 'A++', 'A+++', 'B', 'C', 'D', 'E', 'F', 'G' ];
			break;

		case 'excluded_destination':
		case 'included_destination':
			options = [ 'Shopping_ads', 'Buy_on_Google_listings', 'Display_ads', 'Local_inventory_ads', 'Free_listings', 'Free_local_listings' ];
			break;

		case 'consumer_notice-notice_type':
			options = [ 'prop_65', 'safety_warning', 'legal_disclaimer' ];
			break;

		case 'pickup_method':
			options = [ 'buy', 'reserve', 'ship_to_store', 'not_supported' ];
			break;

		case 'pickup_sla':
			options = [ 'same_day', 'next_day', '2-day', '3-day', '4-day', '5-day', '6-day', '7-day', 'multi-week' ];
			break;

		case 'vehicle_fulfillment':
			options = [ 'in_store', 'ship_to_store', 'online' ];
			break;

		case 'vehicle_price_type':
			options = [ 'all_in_price', 'drive_away_price', 'estimated_drive_away_price', 'excluding_government_charges_price' ];
			break;

		case 'body_style':
			options = [ 'convertible', 'coupe', 'crossover', 'full_size_van', 'hatchback', 'minivan', 'sedan', 'station_wagon', 'suv', 'truck', 'ute' ];
			break;

		case 'engine':
			options = [ 'gasoline', 'petrol', 'diesel', 'electric', 'hybrid' ];
			break;
	}

	return options;
}

function switchToGoogleFeedFormMainInputs( isNew, channel ) {
	jQuery( '#wppfm-feed-types-list-row' ).show()
	jQuery( '#wppfm-feed-types-selector' ).prop( 'disabled', false );
	jQuery( '#wppfm-country-list-row' ).show()
	jQuery( '#wppfm-countries-selector' ).prop( 'disabled', false );
	jQuery( '#category-list-row' ).show();
	jQuery( '#lvl_0' ).prop( 'disabled', false );
	jQuery( '#google-feed-title-row' ).show();
	jQuery( '#google-feed-description-row' ).show();
	jQuery( '#aggregator-selector-row' ).hide();

	// For backwards compatibility. Remove after plugin version 3.0.0 is common.
	jQuery( '#country-list-row' ).show()
	jQuery( '#countries-selector' ).prop( 'disabled', false );

	appendCategoryLists( parseInt( channel ), 'en-US', isNew );
}

function verifyMinPluginVersion( requiredVersion ) {
	var versionNr = jQuery( '#wppfm-plugin-version-nr').text()
	console.log( versionNr );
}

function googleInputChanged( feedId, categoryChanged ) {
	var fileName             = jQuery( '#wppfm-feed-file-name' ).val();

	if ( ! fileName ) {
		fileName             = jQuery( '#file-name' ).val();
	}

	var selectedCountry      = jQuery( '#wppfm-countries-selector' ).val();
	var selectedFeedType     = jQuery( '#wppfm-feed-types-selector' ).val();
	var selectedMainCategory = '';

	var categorySelectors       = jQuery( '#lvl_0' );
	var categorySelectedDisplay = jQuery( '#selected-categories' );
	var categoryFreeInput       = jQuery( '#free-category-text-input' );

	// Get the correct selected category value depending on the situation.
	if ( categorySelectors.is( ':visible' ) ) {
		selectedMainCategory = categorySelectors.val();
	} else if ( categoryFreeInput.is( ':visible' ) ) {
		selectedMainCategory = categoryFreeInput.val();
	} else {
		selectedMainCategory = categorySelectedDisplay.text();
	}

	// enable or disable the correct buttons for the Google channel
	if ( fileName && selectedCountry !== '0' && ( selectedMainCategory !== '' && selectedMainCategory !== '0' ) ) {
		updateFeedFormAfterInputChanged( feedId, categoryChanged );
	} else {
		// keep the Generate and Save buttons disabled
		disableFeedActionButtons();
	}

	jQuery( '#wppfm-feed-types-list-row' ).show();
}

// ALERT! This function is equivalent to the special_clothing_group_countries() function in class-feed.php in the Google channels folder
function googleSpecialClothingGroupCountries() {
	return [ 'US', 'GB', 'DE', 'FR', 'JP', 'BR' ]; // Brazil added based on the new Feed Specifications from september 2015
}

// ALERT! This function is equivalent to the special_shipping_countries() function in class-feed.php in the Google channels folder
function googleSpecialShippingCountries() {
	return [ 'US', 'GB', 'DE', 'AU', 'FR', 'CH', 'CZ', 'NL', 'IT', 'ES', 'JP' ];
}

// ALERT! This function is equivalent to the special_product_countries() function in class-feed.php in the Google channels folder
function googleSpecialProductCountries() {
	return [ 'US', 'GB', 'DE', 'AU', 'FR', 'CH', 'CZ', 'NL', 'IT', 'ES', 'JP', 'BR' ];
}

// ALERT! This function is equivalent to the special_subscription_countries() function in class-feed.php in the Google channels folder
function googleSpecialSubscriptionCountries() {
	return [ 'ZA', 'HK', 'IN', 'JP', 'MY', 'NZ', 'SG', 'KR', 'TW', 'TH', 'AT', 'BE', 'CZ', 'DK', 'FI', 'DE', 'FR', 'GR', 'HU', 'IE',
		'IT', 'NO', 'PL', 'PT', 'RO', 'SK', 'ES', 'SE', 'CH', 'TR', 'GB', 'IL', 'SA', 'AE', 'CA' ];
}
