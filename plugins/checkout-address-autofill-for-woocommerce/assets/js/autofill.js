/* *
* Author: zetamatic
* @package checkout_address_autofill_for_woocommerce
*/

// for image upload
jQuery(document).ready(function($) {
  if ($('.wc_gaa_countries').length) {
    $('.wc_gaa_countries').select2();
  }
  $('.image_logo_upload').click(function(e) {
    e.preventDefault();

    var custom_uploader = wp.media({
        title: 'Location Image',
        button: {
          text: 'Upload Image'
        },
        multiple: false
      })
      .on('select', function() {
        var attachment = custom_uploader.state().get('selection').first().toJSON();
        $('.image_logo').attr('src', attachment.url);
        $('.image_logo_url').val(attachment.url);
      })
      .open();
  });
});

// Getting data from autocomplete field
var autofill, place;

function initAutocomplete() {

  if (jQuery('#shipping_autofill_checkout_field').length > 0) {

    if (wcaf.autofill_for_shipping) {
      autofill_for_shipping = new google.maps.places.Autocomplete(document.getElementById('shipping_autofill_checkout_field'));

      if (wcaf.selectedCountry.length > 0 && wcaf.selectedCountry !== undefined) {
        autofill_for_shipping.setComponentRestrictions({
          'country': wcaf.selectedCountry
        });
      }
      autofill_for_shipping.addListener('place_changed', fillInShippingAddress);
    }

  }

  autofill = new google.maps.places.Autocomplete(document.getElementById('autofill_checkout_field'));

  if (wcaf.selectedCountry.length > 0 && wcaf.selectedCountry !== undefined) {
    autofill.setComponentRestrictions({
      'country': wcaf.selectedCountry
    });
  }

  autofill.addListener('place_changed', fillInBillingAddress);
}


//Auto Fill the Shipping address
function fillInShippingAddress() {

  if (!wcaf.autofill_for_shipping) {
    return;
  }

  place = autofill_for_shipping.getPlace();

  jQuery('#shipping_postcode').val('');
  jQuery('#shipping_address_1').val('');
  jQuery('#shipping_address_2').val('');
  jQuery('#shipping_city').val('');
  // jQuery('#shipping_company').val('');

  const addressComponent = autoFillParseAddress(place.address_components);;

  jQuery('#shipping_country').val(addressComponent.country);
  jQuery('#shipping_country').trigger('change');
  jQuery('#shipping_address_1').val(addressComponent.complete_address_1);
  jQuery('#shipping_address_2').val(addressComponent.complete_address_2);
  jQuery('#shipping_city').val(addressComponent.district);
  jQuery('#shipping_postcode').val(addressComponent.postal_code);
  setTimeout(function() {
    jQuery('#shipping_state').val(addressComponent.state);
    jQuery('#shipping_state').trigger('change');
  }, 1500);

  if (wcaf.enable_shipping_company_name) {
    if (place.hasOwnProperty("name") && place.name) {
      jQuery('#shipping_company').val(place.name);
    }
  }
}

//Auto Fill the Billing address
function fillInBillingAddress() {
  place = autofill.getPlace();
  jQuery('#billing_postcode').val('');
  jQuery('#billing_address_2').val('');
  jQuery('#billing_address_1').val('');
  jQuery('#billing_city').val('');
  // jQuery('#billing_phone').val('');
  // jQuery('#billing_company').val('');

  const addressComponent = autoFillParseAddress(place.address_components);

  jQuery('#billing_country').val(addressComponent.country);
  jQuery('#billing_country').trigger('change');
  jQuery('#billing_address_1').val(addressComponent.complete_address_1);
  jQuery('#billing_address_2').val(addressComponent.complete_address_2);
  jQuery('#billing_city').val(addressComponent.district);
  jQuery('#billing_postcode').val(addressComponent.postal_code);
  setTimeout(function() {
    jQuery('#billing_state').val(addressComponent.state);
    jQuery('#billing_state').trigger('change');
  }, 1500);

  if (wcaf.enable_billing_phone) {
    if (place.hasOwnProperty("international_phone_number") && place.international_phone_number) {
      jQuery('#billing_phone').val(place.international_phone_number);
    }
  }

  if (wcaf.enable_billing_company_name) {
    if (place.hasOwnProperty("name") && place.name) {
      jQuery('#billing_company').val(place.name);
    }
  }

}

// Getting for geolocation support
function shipping_geolocate() {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(shipping_geoSuccess, geoError);
  } else {
    alert("Geolocation is not supported by this browser.");
  }
}

function billing_geolocate() {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(billing_geoSuccess, geoError);
  } else {
    alert("Geolocation is not supported by this browser.");
  }
}

// Funtion for error
function geoError() {
  console.log("Geocoder failed.");
}

// Function for success and getting coordinates
function billing_geoSuccess(position) {
  var lat = position.coords.latitude;
  var lng = position.coords.longitude;
  billing_codeLatLng(lat, lng);
}

// Function for success and getting coordinates
function shipping_geoSuccess(position) {
  var lat = position.coords.latitude;
  var lng = position.coords.longitude;
  shipping_codeLatLng(lat, lng);
}

// Function to fill address
var ship_geocoder;

function shipping_codeLatLng(lat, lng) {
  ship_geocoder = new google.maps.Geocoder();
  var latlng = new google.maps.LatLng(lat, lng);
  ship_geocoder.geocode({
    'latLng': latlng
  }, function(results, status) {

    if (status == google.maps.GeocoderStatus.OK) {
      if (results[0]) {
        var address = results[0].address_components;

        jQuery('#shipping_postcode').val('');
        jQuery('#shipping_address_2').val('');
        jQuery('#shipping_city').val('');
        // jQuery('#shipping_company').val('');
        const addressComponent = autoFillParseAddress(address);

        jQuery('#shipping_country').val(addressComponent.country);
        jQuery('#shipping_country').trigger('change');
        jQuery('#shipping_address_1').val(addressComponent.complete_address_1);
        jQuery('#shipping_address_2').val(addressComponent.complete_address_2);
        jQuery('#shipping_city').val(addressComponent.district);
        jQuery('#shipping_postcode').val(addressComponent.postal_code);
        setTimeout(function() {
          jQuery('#shipping_state').val(addressComponent.state);
          jQuery('#shipping_state').trigger('change');
        }, 1500);

        if (wcaf.enable_shipping_company_name) {
          if (results[0].hasOwnProperty("name") && results[0].name) {
            jQuery('#shipping_company').val(results[0].name);
          }
        }

      } else {
        alert("No results found"); // alerting if no results found
      }
    } else {
      console.log("Geocoder failed due to: " + status);
    }
  });
}

// Function to fill address
var geocoder;

function billing_codeLatLng(lat, lng) {
  geocoder = new google.maps.Geocoder();
  var latlng = new google.maps.LatLng(lat, lng);
  geocoder.geocode({
    'latLng': latlng
  }, function(results, status) {

    if (status == google.maps.GeocoderStatus.OK) {
      if (results[0]) {
        var address = results[0].address_components;

        jQuery('#billing_postcode').val('');
        jQuery('#billing_address_2').val('');
        jQuery('#billing_city').val('');
        // jQuery('#billing_company').val('');

        const addressComponent = autoFillParseAddress(address);

        jQuery('#billing_country').val(addressComponent.country);
        jQuery('#billing_country').trigger('change');
        jQuery('#billing_address_1').val(addressComponent.complete_address_1);
        jQuery('#billing_address_2').val(addressComponent.complete_address_2);
        jQuery('#billing_city').val(addressComponent.district);
        jQuery('#billing_postcode').val(addressComponent.postal_code);
        setTimeout(function() {
          jQuery('#billing_state').val(addressComponent.state);
          jQuery('#billing_state').trigger('change');
        }, 1500);

        if (wcaf.enable_billing_phone) {
          if (results[0].hasOwnProperty("international_phone_number") && results[0].international_phone_number) {
            jQuery('#billing_phone').val(results[0].international_phone_number);
          }
        }

        if (wcaf.enable_billing_company_name) {
          if (results[0].hasOwnProperty("name") && results[0].name) {
            jQuery('#billing_company').val(results[0].name);
          }
        }

      } else {
        alert("No results found"); // alerting if no results found
      }
    } else {
      console.log("Geocoder failed due to: " + status);
    }
  });
}

//function to parse the autofill value
function autoFillParseAddress(address) {
  let contents = {};
  let address_type = '';
  address.forEach((it, ind) => {
    address_type = it.types[0];
    if (address_type == 'country') {
      contents.country = it.short_name;
      contents.country_long = it.long_name;
    }
    if (address_type == 'premise') {
      contents.premise = it.long_name;
    }
    if (address_type == 'street_number') {
      contents.street_number = it.long_name;
    }
    if (it.types.includes('sublocality')) {
      if (it.types.includes('sublocality_level_1')) {
        contents.sublocality_level_1 = it.long_name;
      } else if (it.types.includes('sublocality_level_2')) {
        contents.sublocality_level_2 = it.long_name;
      } else if (it.types.includes('sublocality_level_3')) {
        contents.sublocality_level_3 = it.long_name;
      } else if (it.types.includes('neighborhood')) {
        contents.neighborhood = it.long_name;
      }
    }
    if (address_type == 'route') {
      contents.route = it.long_name;
    }
    if (address_type == 'administrative_area_level_1') {
      contents.state = it.short_name;
      contents.state_long = it.long_name;
    }
    if (address_type == 'administrative_area_level_2') {
      contents.district = it.long_name;
    }
    if (address_type == 'neighborhood') {
      contents.neighborhood = it.long_name;
    }
    if (address_type == 'locality') {
      contents.city = it.long_name;
    }
    if (address_type == 'postal_code') {
      contents.postal_code = it.long_name;
    }
  });

  let address1 = [];
  if (contents.hasOwnProperty("premise")) {
    address1.push(contents.premise);
  }
  if (contents.hasOwnProperty("street_number")) {
    address1.push(contents.street_number);
  }
  if (contents.hasOwnProperty("neighborhood")) {
    address1.push(contents.neighborhood);
  }
  if (contents.hasOwnProperty("sublocality_level_3")) {
    address1.push(contents.sublocality_level_3);
  }
  if (contents.hasOwnProperty("sublocality_level_2")) {
    address1.push(contents.sublocality_level_2);
  }
  if (contents.hasOwnProperty("sublocality_level_1")) {
    address1.push(contents.sublocality_level_1);
  }

  const complete_address_1 = address1.join(", ");

  let address2 = [];
  if (contents.hasOwnProperty("route")) {
    address2.push(contents.route);
  }
  if (contents.hasOwnProperty("city")) {
    address2.push(contents.city);
  }
  const complete_address_2 = address2.join(", ");

  let returnAddress = {};
  returnAddress.complete_address_1 = complete_address_1;
  returnAddress.complete_address_2 = complete_address_2;
  returnAddress.district = contents.hasOwnProperty("district") ? contents.district : "";
  returnAddress.state = contents.hasOwnProperty("state") ? contents.state : "";
  returnAddress.state_long = contents.hasOwnProperty("state_long") ? contents.state_long : "";
  returnAddress.country = contents.hasOwnProperty("country") ? contents.country : "";
  returnAddress.country_long = contents.hasOwnProperty("country_long") ? contents.country_long : "";
  returnAddress.postal_code = contents.hasOwnProperty("postal_code") ? contents.postal_code : "";
  console.log(returnAddress);
  return returnAddress;
}

// Testing Google API key in setting page
let testing_field_check = document.getElementById('autofill_checkout_field_testing');

if(testing_field_check){
  google.maps.event.addDomListener(window, 'load', initAutocompleteTest);
}

function initAutocompleteTest(){
  autofill_testing = new google.maps.places.Autocomplete(document.getElementById('autofill_checkout_field_testing'));

  test = autofill_testing.addListener('place_changed', fillInTestAddress);

  // Show the error in alert box if occured-------------------------------
  console.defaultError = console.error.bind(console);
  console.errors = [];
  console.error = function(){
    // default &  console.error()
    console.defaultError.apply(console, arguments);
    // new & array data
    console.errors.push(Array.from(arguments));
    // console.log('your error is : '+console.errors);

    alert(console.errors);
  }

}

function fillInTestAddress(){
  

  place = autofill_testing.getPlace();

  const addressComponent = autoFillParseAddress(place.address_components);
  // console.log('These are the fields',addressComponent);
  
  alert(
    'complete_address_1 : ' +addressComponent.complete_address_1+'\n'+
    'complete_address_2 : ' +addressComponent.complete_address_2+'\n'+
    'district : ' +addressComponent.district+'\n'+
    'state : ' +addressComponent.state+'\n'+
    'state_long : ' +addressComponent.state_long+'\n'+
    'country : ' +addressComponent.country+'\n'+
    'country_long : ' +addressComponent.country_long+'\n'+
    'postal_code : ' +addressComponent.postal_code+'\n'+
    'city : ' +addressComponent.city+'\n'+
    'street_number : ' +addressComponent.street_number+'\n'+
    'neighborhood : ' +addressComponent.neighborhood+'\n'+
    'premise : ' +addressComponent.premise+'\n'+
    'sublocality_level_1 : ' +addressComponent.sublocality_level_1+'\n'+
    'sublocality_level_2 : ' +addressComponent.sublocality_level_2+'\n'+
    'sublocality_level_3 : ' +addressComponent.sublocality_level_3+'\n'+
    'route : ' +addressComponent.route+'\n'
  );
}

// testing Geolocation
jQuery("body").on('click', '#testing_current_location', function() {

// alert('you clicked the current location');
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(testing_geoSuccess, geoError);
  } else {
    alert("Geolocation is not supported by this browser.");
  }

});

// Function for success and getting coordinates
function testing_geoSuccess(position) {
var lat = position.coords.latitude;
var lng = position.coords.longitude;
testing_codeLatLng(lat, lng);
}

// Function to fill address
var geocoder;

function testing_codeLatLng(lat, lng) {
geocoder = new google.maps.Geocoder();
var latlng = new google.maps.LatLng(lat, lng);
geocoder.geocode({
  'latLng': latlng
}, function(results, status) {

  if (status == google.maps.GeocoderStatus.OK) {
    if (results[0]) {
      var address = results[0].address_components;
      const addressComponent = autoFillParseAddress(address);
      // console.log('These are the fields',addressComponent);

      alert(
        'complete_address_1 : ' +addressComponent.complete_address_1+'\n'+
        'complete_address_2 : ' +addressComponent.complete_address_2+'\n'+
        'district : ' +addressComponent.district+'\n'+
        'state : ' +addressComponent.state+'\n'+
        'state_long : ' +addressComponent.state_long+'\n'+
        'country : ' +addressComponent.country+'\n'+
        'country_long : ' +addressComponent.country_long+'\n'+
        'postal_code : ' +addressComponent.postal_code+'\n'+
        'city : ' +addressComponent.city+'\n'+
        'street_number : ' +addressComponent.street_number+'\n'+
        'neighborhood : ' +addressComponent.neighborhood+'\n'+
        'premise : ' +addressComponent.premise+'\n'+
        'sublocality_level_1 : ' +addressComponent.sublocality_level_1+'\n'+
        'sublocality_level_2 : ' +addressComponent.sublocality_level_2+'\n'+
        'sublocality_level_3 : ' +addressComponent.sublocality_level_3+'\n'+
        'route : ' +addressComponent.route+'\n'
      );
    } else {
      alert("No results found"); // alerting if no results found
      }
  } else {
      console.log("Geocoder failed due to: " + status);
    }
});
}

// Testing google api key ends here---------

// upgrade to pro link
jQuery(document).ready(function(){
  var ProHtml = '<tr valign="top" class = "wpr-pro-block"><th class = "update-pro-link"><a href = "https://zetamatic.com/downloads/checkout-address-autofill-for-woocommerce-pro/" target = "_blank">Upgrade to PRO to use this option</a></th></tr>';

  jQuery('.pro-feature').append(ProHtml);

  jQuery('.wpr-pro-block').hide();

  // jQuery('.pro-feature').hover(function() {
	// 	jQuery(this).find('.wpr-pro-block').show();
  // });
  jQuery(".pro-feature").mouseenter(function(){
    jQuery(this).find('.wpr-pro-block').show();
  });

  jQuery(".pro-feature").mouseleave(function(){
    jQuery('.wpr-pro-block').hide();
  });

});


if(jQuery('.wc-af-zeta-plugin-explore-product').length > 0){
  jQuery('.wcaf_settings_tab_content_save_button #submit').hide();
}

