let simpleAutocomplete;
let getInputValues = simple_address_autocomplete_settings_vars.simple_address_autocomplete_form_field_ids;
let inputSplit = getInputValues.trim().split(/\s*\n\s*/);
let inputValues = new Array();
let country_select = simple_address_autocomplete_settings_vars.simple_address_autocomplete_country_selected;
let biasCoordinates = simple_address_autocomplete_settings_vars.simple_address_autocomplete_bias_coordinates;
let restrictionType = simple_address_autocomplete_settings_vars.simple_address_autocomplete_restriction_type;

if (biasCoordinates !== '' && biasCoordinates.includes(',')) {
  var searchBounds = biasCoordinates.split(',');
  var lat = parseFloat(searchBounds[0]);
  var lng = parseFloat(searchBounds[1]);
  var searchBounds = new google.maps.LatLngBounds(
    new google.maps.LatLng(lat, lng)
  );
}

function initAutocomplete() {
	if (restrictionType == 'biased' && biasCoordinates !== '') {
    options = (country_select == 'WW')
      ? { types: ['geocode', 'establishment'] }
      : {
        bounds: searchBounds,
        types: ['geocode', 'establishment'],
        componentRestrictions: { country: country_select }
      };
  } else if (restrictionType == 'restricted' && biasCoordinates !== '') {
    var circle = new google.maps.Circle({ center: new google.maps.LatLng(lat, lng), radius: 10000 });
    options = {
      types: ['geocode', 'establishment'],
      bounds: circle.getBounds(),
      strictBounds: true
    };
  } else {
    options = (country_select == 'WW')
      ? { types: ['geocode', 'establishment'] }
      : { types: ['geocode', 'establishment'], componentRestrictions: { country: country_select } };
  }

  for (let i = 0; i < inputSplit.length; i++) {
    inputValues[i] = inputSplit[i];
    // Select the fields with the prefix using the starts with (^=) attribute selector
    fields = document.querySelector(`[id^="${inputValues[i]}"]`);
    if (fields) {
      simpleAutocomplete = new google.maps.places.Autocomplete(fields, options);
    }
  }
}

window.addEventListener('load', function () {
  for (let i = 0; i < inputSplit.length; i++) {
    inputValues[i] = inputSplit[i];
    // Select the fields with the prefix using the starts with (^=) attribute selector
    const fields = document.querySelectorAll(`[id^="${inputValues[i]}"]`);
    fields.forEach(field => {
      field.setAttribute("onfocus", "initAutocomplete()");
    });
  }
});

