const ADDRESSES_SEPARATOR = '^^';
const DATA_SEPARATOR = '!!';
const CITY = 'CITY';
const GEO_LOCATION_MARKER_TEMPLATE = '';
const GEO_INPUT_TEMPLATE = '<span class="specific-location">{LOCATION}</span>';
const GEO_INPUT_REMOVE_FIELD_TEMPLATE = '<button class="remove-autocomplete" data-pos="{DATA_POS}"><i class="fa fa-times-circle-o" aria-hidden="true"></i></button>';
const GEO_INPUT_TEMPLATE_CONTAINER = '<div class="locationField">'+GEO_LOCATION_MARKER_TEMPLATE+GEO_INPUT_TEMPLATE+GEO_INPUT_REMOVE_FIELD_TEMPLATE+'</div>';

// Page Visit HTML Templates
const PAGE_VISIT_OPERATOR_TEMPLATE = '<span class="ifso-page-visit-operator-field">{OPERATOR}: </span>'
const PAGE_VISIT_INPUT_TEMPLATE_CONTAINER = '<div class="locationField">'+PAGE_VISIT_OPERATOR_TEMPLATE+"</br>"+GEO_INPUT_TEMPLATE+GEO_INPUT_REMOVE_FIELD_TEMPLATE+'</div>';

// data
const continents =  [{"name" : "Africa", "code" : "AF"}, {"name" : "Antarctica", "code" : "AN"}, {"name" : "Asia", "code" : "AS"}, {"name" : "Europe", "code" : "EU"}, {"name" : "North America", "code" : "NA"}, {"name" : "Oceania", "code" : "OC"}, {"name" : "South America", "code" : "SA"} ];

const countries = [{"name": "Afghanistan", "code": "AF"}, {"name": "Albania", "code": "AL"}, {"name": "Algeria", "code": "DZ"}, {"name": "American Samoa", "code": "AS"}, {"name": "AndorrA", "code": "AD"}, {"name": "Angola", "code": "AO"}, {"name": "Anguilla", "code": "AI"}, {"name": "Antarctica", "code": "AQ"}, {"name": "Antigua and Barbuda", "code": "AG"}, {"name": "Argentina", "code": "AR"}, {"name": "Armenia", "code": "AM"}, {"name": "Aruba", "code": "AW"}, {"name": "Australia", "code": "AU"}, {"name": "Austria", "code": "AT"}, {"name": "Azerbaijan", "code": "AZ"}, {"name": "Bahamas", "code": "BS"}, {"name": "Bahrain", "code": "BH"}, {"name": "Bangladesh", "code": "BD"}, {"name": "Barbados", "code": "BB"}, {"name": "Belarus", "code": "BY"}, {"name": "Belgium", "code": "BE"}, {"name": "Belize", "code": "BZ"}, {"name": "Benin", "code": "BJ"}, {"name": "Bermuda", "code": "BM"}, {"name": "Bhutan", "code": "BT"}, {"name": "Bolivia", "code": "BO"}, {"name": "Bosnia and Herzegovina", "code": "BA"}, {"name": "Botswana", "code": "BW"}, {"name": "Bouvet Island", "code": "BV"}, {"name": "Brazil", "code": "BR"}, {"name": "British Indian Ocean Territory", "code": "IO"}, {"name": "Brunei Darussalam", "code": "BN"}, {"name": "Bulgaria", "code": "BG"}, {"name": "Burkina Faso", "code": "BF"}, {"name": "Burundi", "code": "BI"}, {"name": "Cambodia", "code": "KH"}, {"name": "Cameroon", "code": "CM"}, {"name": "Canada", "code": "CA"}, {"name": "Cape Verde", "code": "CV"}, {"name": "Cayman Islands", "code": "KY"}, {"name": "Central African Republic", "code": "CF"}, {"name": "Chad", "code": "TD"}, {"name": "Chile", "code": "CL"}, {"name": "China", "code": "CN"}, {"name": "Christmas Island", "code": "CX"}, {"name": "Cocos (Keeling) Islands", "code": "CC"}, {"name": "Colombia", "code": "CO"}, {"name": "Comoros", "code": "KM"}, {"name": "Congo", "code": "CG"}, {"name": "Congo, The Democratic Republic of the", "code": "CD"}, {"name": "Cook Islands", "code": "CK"}, {"name": "Costa Rica", "code": "CR"}, {"name": "Cote D^Ivoire", "code": "CI"}, {"name": "Croatia", "code": "HR"}, {"name": "Cuba", "code": "CU"}, {"name": "Cyprus", "code": "CY"}, {"name": "Czech Republic", "code": "CZ"},{"name": "Curacao", "code": "CW"}, {"name": "Denmark", "code": "DK"}, {"name": "Djibouti", "code": "DJ"}, {"name": "Dominica", "code": "DM"}, {"name": "Dominican Republic", "code": "DO"}, {"name": "Ecuador", "code": "EC"}, {"name": "Egypt", "code": "EG"}, {"name": "El Salvador", "code": "SV"}, {"name": "Equatorial Guinea", "code": "GQ"}, {"name": "Eritrea", "code": "ER"}, {"name": "Estonia", "code": "EE"}, {"name": "Ethiopia", "code": "ET"}, {"name": "Falkland Islands (Malvinas)", "code": "FK"}, {"name": "Faroe Islands", "code": "FO"}, {"name": "Fiji", "code": "FJ"}, {"name": "Finland", "code": "FI"}, {"name": "France", "code": "FR"}, {"name": "French Guiana", "code": "GF"}, {"name": "French Polynesia", "code": "PF"}, {"name": "French Southern Territories", "code": "TF"}, {"name": "Gabon", "code": "GA"}, {"name": "Gambia", "code": "GM"}, {"name": "Georgia", "code": "GE"}, {"name": "Germany", "code": "DE"}, {"name": "Ghana", "code": "GH"}, {"name": "Gibraltar", "code": "GI"}, {"name": "Greece", "code": "GR"}, {"name": "Greenland", "code": "GL"}, {"name": "Grenada", "code": "GD"}, {"name": "Guadeloupe", "code": "GP"}, {"name": "Guam", "code": "GU"}, {"name": "Guatemala", "code": "GT"}, {"name": "Guernsey", "code": "GG"}, {"name": "Guinea", "code": "GN"}, {"name": "Guinea-Bissau", "code": "GW"}, {"name": "Guyana", "code": "GY"}, {"name": "Haiti", "code": "HT"}, {"name": "Heard Island and Mcdonald Islands", "code": "HM"}, {"name": "Holy See (Vatican City State)", "code": "VA"}, {"name": "Honduras", "code": "HN"}, {"name": "Hong Kong", "code": "HK"}, {"name": "Hungary", "code": "HU"}, {"name": "Iceland", "code": "IS"}, {"name": "India", "code": "IN"}, {"name": "Indonesia", "code": "ID"}, {"name": "Iran, Islamic Republic Of", "code": "IR"}, {"name": "Iraq", "code": "IQ"}, {"name": "Ireland", "code": "IE"}, {"name": "Isle of Man", "code": "IM"}, {"name": "Israel", "code": "IL"}, {"name": "Italy", "code": "IT"}, {"name": "Jamaica", "code": "JM"}, {"name": "Japan", "code": "JP"}, {"name": "Jersey", "code": "JE"}, {"name": "Jordan", "code": "JO"}, {"name": "Kazakhstan", "code": "KZ"}, {"name": "Kenya", "code": "KE"}, {"name": "Kiribati", "code": "KI"}, {"name": "Korea, Democratic People^s Republic of", "code": "KP"}, {"name": "Korea, Republic of", "code": "KR"}, {"name": "Kuwait", "code": "KW"}, {"name": "Kyrgyzstan", "code": "KG"}, {"name": "Lao People^s Democratic Republic", "code": "LA"}, {"name": "Latvia", "code": "LV"}, {"name": "Lebanon", "code": "LB"}, {"name": "Lesotho", "code": "LS"}, {"name": "Liberia", "code": "LR"}, {"name": "Libyan Arab Jamahiriya", "code": "LY"}, {"name": "Liechtenstein", "code": "LI"}, {"name": "Lithuania", "code": "LT"}, {"name": "Luxembourg", "code": "LU"}, {"name": "Macao", "code": "MO"}, {"name": "Macedonia, The Former Yugoslav Republic of", "code": "MK"}, {"name": "Madagascar", "code": "MG"}, {"name": "Malawi", "code": "MW"}, {"name": "Malaysia", "code": "MY"}, {"name": "Maldives", "code": "MV"}, {"name": "Mali", "code": "ML"}, {"name": "Malta", "code": "MT"}, {"name": "Marshall Islands", "code": "MH"}, {"name": "Martinique", "code": "MQ"}, {"name": "Mauritania", "code": "MR"}, {"name": "Mauritius", "code": "MU"}, {"name": "Mayotte", "code": "YT"}, {"name": "Mexico", "code": "MX"}, {"name": "Micronesia, Federated States of", "code": "FM"}, {"name": "Moldova, Republic of", "code": "MD"}, {"name": "Monaco", "code": "MC"}, {"name": "Mongolia", "code": "MN"}, {"name": "Montserrat", "code": "MS"}, {"name": "Morocco", "code": "MA"}, {"name": "Mozambique", "code": "MZ"}, {"name": "Myanmar", "code": "MM"}, {"name": "Namibia", "code": "NA"}, {"name": "Nauru", "code": "NR"}, {"name": "Nepal", "code": "NP"}, {"name": "Netherlands", "code": "NL"}, {"name": "Netherlands Antilles", "code": "AN"}, {"name": "New Caledonia", "code": "NC"}, {"name": "New Zealand", "code": "NZ"}, {"name": "Nicaragua", "code": "NI"}, {"name": "Niger", "code": "NE"}, {"name": "Nigeria", "code": "NG"}, {"name": "Niue", "code": "NU"}, {"name": "Norfolk Island", "code": "NF"}, {"name": "Northern Mariana Islands", "code": "MP"}, {"name": "Norway", "code": "NO"}, {"name": "Oman", "code": "OM"}, {"name": "Pakistan", "code": "PK"}, {"name": "Palau", "code": "PW"}, {"name": "Palestinian Territory, Occupied", "code": "PS"}, {"name": "Panama", "code": "PA"}, {"name": "Papua New Guinea", "code": "PG"}, {"name": "Paraguay", "code": "PY"}, {"name": "Peru", "code": "PE"}, {"name": "Philippines", "code": "PH"}, {"name": "Pitcairn", "code": "PN"}, {"name": "Poland", "code": "PL"}, {"name": "Portugal", "code": "PT"}, {"name": "Puerto Rico", "code": "PR"}, {"name": "Qatar", "code": "QA"}, {"name": "Reunion", "code": "RE"}, {"name": "Romania", "code": "RO"}, {"name": "Russian Federation", "code": "RU"}, {"name": "RWANDA", "code": "RW"}, {"name": "Saint Helena", "code": "SH"}, {"name": "Saint Kitts and Nevis", "code": "KN"}, {"name": "Saint Lucia", "code": "LC"}, {"name": "Saint Pierre and Miquelon", "code": "PM"}, {"name": "Saint Vincent and the Grenadines", "code": "VC"}, {"name": "Samoa", "code": "WS"}, {"name": "San Marino", "code": "SM"}, {"name": "Sao Tome and Principe", "code": "ST"}, {"name": "Saudi Arabia", "code": "SA"}, {"name": "Senegal", "code": "SN"}, {"name": "Serbia", "code": "RS"},{"name": "Montenegro", "code": "ME"}, {"name": "Seychelles", "code": "SC"}, {"name": "Sierra Leone", "code": "SL"}, {"name": "Singapore", "code": "SG"}, {"name": "Slovakia", "code": "SK"}, {"name": "Slovenia", "code": "SI"}, {"name": "Solomon Islands", "code": "SB"}, {"name": "Somalia", "code": "SO"}, {"name": "South Africa", "code": "ZA"}, {"name": "South Georgia and the South Sandwich Islands", "code": "GS"}, {"name": "Spain", "code": "ES"}, {"name": "Sri Lanka", "code": "LK"}, {"name": "Sudan", "code": "SD"}, {"name": "Suri", "code": "SR"}, {"name": "Svalbard and Jan Mayen", "code": "SJ"}, {"name": "Swaziland", "code": "SZ"}, {"name": "Sweden", "code": "SE"}, {"name": "Switzerland", "code": "CH"}, {"name": "Syrian Arab Republic", "code": "SY"}, {"name": "Taiwan, Province of China", "code": "TW"}, {"name": "Tajikistan", "code": "TJ"}, {"name": "Tanzania, United Republic of", "code": "TZ"}, {"name": "Thailand", "code": "TH"}, {"name": "Timor-Leste", "code": "TL"}, {"name": "Togo", "code": "TG"}, {"name": "Tokelau", "code": "TK"}, {"name": "Tonga", "code": "TO"}, {"name": "Trinidad and Tobago", "code": "TT"}, {"name": "Tunisia", "code": "TN"}, {"name": "Turkey", "code": "TR"}, {"name": "Turkmenistan", "code": "TM"}, {"name": "Turks and Caicos Islands", "code": "TC"}, {"name": "Tuvalu", "code": "TV"}, {"name": "Uganda", "code": "UG"}, {"name": "Ukraine", "code": "UA"}, {"name": "United Arab Emirates", "code": "AE"}, {"name": "United Kingdom", "code": "GB"}, {"name": "United States", "code": "US"}, {"name": "United States Minor Outlying Islands", "code": "UM"}, {"name": "Uruguay", "code": "UY"}, {"name": "Uzbekistan", "code": "UZ"}, {"name": "Vanuatu", "code": "VU"}, {"name": "Venezuela", "code": "VE"}, {"name": "Viet Nam", "code": "VN"}, {"name": "Virgin Islands, British", "code": "VG"}, {"name": "Virgin Islands, U.S.", "code": "VI"}, {"name": "Wallis and Futuna", "code": "WF"}, {"name": "Western Sahara", "code": "EH"}, {"name": "Yemen", "code": "YE"}, {"name": "Zambia", "code": "ZM"}, {"name": "Zimbabwe", "code": "ZW"} ];

const COUNTRY_AUTOCOMPLETE_OPTIONS = {
      data: countries,
      getValue: "name",
      list: {
        match: {
          enabled: true
        },
        maxNumberOfElements: 10
      },
      template: {
        type: "custom",
        method: function(value, item) {
          return "<span class='flag flag-" + (item.code).toLowerCase() + "' ></span>" + value;
        }
      }
};

const CONTINENT_AUTOCOMPLETE_OPTIONS = {
      data: continents,
      getValue: "name",
      list: {
        match: {
          enabled: true
        },
        maxNumberOfElements: 10
      },
      template: {
        type: "custom",
        method: function(value, item) {
          return value;
        }
      }
};

function createNewLocation(locationType, behindSceneLocationData, visualLocationData) {
  var data = [locationType, visualLocationData, behindSceneLocationData];
  return data.join(DATA_SEPARATOR);
}

function initAutocompleteForElem($elem, options) {
  $elem.easyAutocomplete(options);
}

function initEasyAutocompletes() {
  if (jQuery != undefined) {
    initAutocompleteForElem( jQuery(".countries-autocomplete"), COUNTRY_AUTOCOMPLETE_OPTIONS );
    initAutocompleteForElem( jQuery(".continents-autocomplete"), CONTINENT_AUTOCOMPLETE_OPTIONS );

    jQuery('.select-manual-city-container, .select-manual-state-container').on("keydown", function(e) {   //Manual city selection stuff
      if (e.keyCode === 13) {
        e.preventDefault();
        var theWrap =  jQuery(this).closest('.ifso-autocomplete-selection-display')[0];
        var input = jQuery(this).find('input');
        var inputData = input.val().toLowerCase();
        var containerType = (typeof(input.attr('cond_type'))!=='undefined') ? input.attr('cond_type') : 'city';
        inputData = inputData.trim();
        inputData = titleCase(inputData);

        if (!inputData || inputData.length === 0)
          return false;

        var dataStr = containerType.toUpperCase() +'!!' + inputData + '!!' + inputData;
        newLocationSelected(theWrap,dataStr,inputData);
        input.val('');
      }
    });
  }
}

jQuery(document).ready(function(jQuery){
  if(!jQuery(".countries-autocomplete").length) return false;     //We're in the wrong page!
  // Enable EasyAutocomplete
  initEasyAutocompletes();

  jQuery("html").on("change", ".ifso-input-autocomplete", function() {
    var $elem = jQuery(this);
    var wholeGeolocationContainer = $elem.closest('.ifso-autocomplete-selection-display');
    var selectedCountry = $elem.getSelectedItemData();

    if (!selectedCountry || selectedCountry == -1) return;

    $elem.val(""); // Clear the input

    const selectedCountryName = selectedCountry.name;
    const selectedCountryCode = selectedCountry.code;
    const symbol = jQuery(this).data('symbol');

    var newLocationData = createNewLocation(symbol, selectedCountryCode, selectedCountryName);
    newLocationSelected(wholeGeolocationContainer[0], newLocationData, selectedCountryName);
  });

  jQuery("html").on("keypress focusout", ".page-visit-autocomplete", function(e) {
      if (e.keyCode === 13 || e.type === "focusout") {
          event.preventDefault();

          const $this = jQuery(this);
          const pageUrl = $this.val();
          if (pageUrl.length == 0)
            return;

          const operator = $this.closest('.selection-inputs-container').find('.ifso-page-visit-operator').val();
          const symbol = "PAGEURL";
          const wholeGeolocationContainer = $this.closest('.ifso-autocomplete-selection-display')[0];

          if(typeof(operator)!='undefined' && typeof(wholeGeolocationContainer)!= 'undefined'){
            // Add to the list
            const newLocationData = createNewLocation(symbol, operator, pageUrl);
            const position = createNewPositionForLocationSelection(wholeGeolocationContainer, newLocationData);

            // Add to the DOM
            const newFieldHTML = getAutocompletePageVisitFieldHTML(position, pageUrl, operator);
            const closestGeolocationFieldsContainer = wholeGeolocationContainer.querySelector('.ifso-autocomplete-fields-container');
            appendNewFieldToAutocompleteSection(closestGeolocationFieldsContainer, newFieldHTML);

            // Clear input
            $this.val("");
          }
          return false;
      }
  });

  jQuery("html").on("change", ".geo-timezone-selection", function() {
    if (this.selectedIndex > 0) {
      const timezoneName = this.value;
      const symbol = "TIMEZONE";
      const $elem = jQuery(this);
      const wholeGeolocationContainer = $elem.closest('.ifso-autocomplete-selection-display');
      const newLocationData = createNewLocation(symbol, timezoneName, timezoneName);
      newLocationSelected(wholeGeolocationContainer[0], newLocationData, timezoneName);

      this.selectedIndex = 0;
    }
  });

  jQuery("html").on("click", ".countries-autocomplete", function(e){
    e.stopPropagation();
    e.preventDefault();
  });

  jQuery("html").on("click", ".remove-autocomplete", function(e) {
    e.stopPropagation();
    e.preventDefault();

    $elem = jQuery(this);
    dataPos = $elem.data('pos');
    var $closestLocationField = $elem.closest('.locationField');
    var $geolocationFieldsContainer = $elem.closest('.ifso-autocomplete-fields-container');
    var $closestContainerParent = $elem.closest(".ifso-autocomplete-selection-display");
    var $locationsDescriptionElem = $closestContainerParent.find('.locations-description');
    var $nonSelectedTitle = $closestContainerParent.find('.none-selected');
    var $multipleSelectedTitle = $closestContainerParent.find('.multiple-selected');
    var $hiddenGeoField = $geolocationFieldsContainer.find('.ifso-autocomplete-data-field');
    var hiddenGeoVal = $hiddenGeoField.val();
    var normalizedAdrs = hiddenGeoVal.split(ADDRESSES_SEPARATOR);
    normalizedAdrs.splice(dataPos, 1); // Remove that autocomplete

    if (normalizedAdrs.length > 1)
      hiddenGeoVal = normalizedAdrs.join(ADDRESSES_SEPARATOR);
    else
      hiddenGeoVal = normalizedAdrs.join('')

    if (!hiddenGeoVal) {
      $geolocationFieldsContainer.removeClass("shown");
      $nonSelectedTitle.removeClass('hide-field');
      $multipleSelectedTitle.addClass('hide-field');
      $locationsDescriptionElem.addClass('hide-field');
    }

    // Update hidden field val's to current geolocations data
    $hiddenGeoField.val(hiddenGeoVal);

    // remove the selected item
    $closestLocationField.remove();

    // Update all elements data-poses
    $geolocationFieldsContainer.find(".locationField").each(function(indx, currLocationField) {
      var currSelectedLocation = currLocationField.querySelector('.specific-location');
      var currRemoveAutocompleteBtn = currLocationField.querySelector('.remove-autocomplete');
      currSelectedLocation.setAttribute('data-pos', indx);
      currRemoveAutocompleteBtn.setAttribute('data-pos', indx);
    });
  });

});


function initCityAutocomplete(elem,alt_cb=false) {
  var autoCompleteSettings = {types: ['(cities)']};
  initGoogleAutocomplete(autoCompleteSettings,elem,alt_cb);
}

function initStateAutocomplete(elem,alt_cb=false) {
  var autoCompleteSettings = {types: ['administrative_area_level_1']};
  var cb = alt_cb ? alt_cb : fillInState;
  initGoogleAutocomplete(autoCompleteSettings,elem,cb);
}

function initGoogleAutocomplete(settings,elem,alt_cb=false){
  // Create the autocomplete object, restricting the search to geographical location types.
  var autocomplete = new google.maps.places.Autocomplete(elem, settings);
  google.maps.event.addDomListener(elem, 'keydown', function(event) {
    if (event.keyCode === 13) {
      event.preventDefault();
    }
  });
  var place_changed_cb = alt_cb!==false ? alt_cb : fillInAddress;
  // When the user selects an address from the dropdown, populate the address fields in the form.
  autocomplete.addListener('place_changed', function() {place_changed_cb(elem, this);});
}

function initAutocomplete() {
  var autocompletes = document.querySelectorAll(".autocomplete, .states-autocomplete.ifso-input-autocomplete");
  for (var i = 0; i < autocompletes.length; i++) {
    var elem = autocompletes[i];
    var locationType = elem.classList.contains('states-autocomplete') ? 'state' : 'city';
    if(locationType==='state')
      initStateAutocomplete(elem);
    if(locationType==='city')
      initCityAutocomplete(elem);
  }
}


// Helper funcs encaplusates the updates to the hidden geo input field
function getHiddenGeoField(elem) {
  var closestContainerParent = getClosest(elem, '.ifso-autocomplete-fields-container');
  var hiddenField = closestContainerParent.querySelector('.ifso-autocomplete-data-field');

  return hiddenField;
}

function getHiddenGeoVal(elem) {
  var hiddenFieldVal = getHiddenGeoField(elem).value;
  return hiddenFieldVal;
}

function updateHiddenGeoVal(elem, newInputVals) {
  var hiddenField = getHiddenGeoField(elem);
  hiddenField.value = newInputVals;
}

function appendHiddenGeoVal(elem, appendText) {
  updateHiddenGeoVal(elem, getHiddenGeoVal(elem) + appendText);
}

function getRemoveButtonField(elem) {
  var closestContainerParent = getClosest(elem, '.locationField');
  var removeAutocompleteBtn = closestContainerParent.querySelector('.remove-autocomplete');

  return removeAutocompleteBtn;
}

function getAutocompleteGeoFieldHTML(position, location) {
  var newField = GEO_INPUT_TEMPLATE_CONTAINER;
  newField = newField.replace("{DATA_POS}", position);
  newField = newField.replace("{LOCATION}", location);

  return newField;
}

function getAutocompletePageVisitFieldHTML(position, location, operator) {
  var newField = PAGE_VISIT_INPUT_TEMPLATE_CONTAINER;
  newField = newField.replace("{DATA_POS}", position);
  newField = newField.replace("{LOCATION}", location);
  newField = newField.replace("{OPERATOR}", operator);

  return newField;
}

function appendNewFieldToAutocompleteSection(container, field) {
  container.insertAdjacentHTML( 'beforeend', field);
}

function createNewPositionForLocationSelection(wholeGeolocationContainer, newLocationData) { // refactor this method
  if(wholeGeolocationContainer && typeof(wholeGeolocationContainer)!='undefined'){
    var locationsDescriptionElem = wholeGeolocationContainer.querySelector('.locations-description');
    var closestGeolocationFieldsContainer = wholeGeolocationContainer.querySelector('.ifso-autocomplete-fields-container');
    var selectionTitleContainer = wholeGeolocationContainer.querySelector('.selection-title');
    var nonSelectedTitle = selectionTitleContainer.querySelector('.none-selected');
    var multipleSelectedTitle = selectionTitleContainer.querySelector('.multiple-selected');
    var hiddenField = closestGeolocationFieldsContainer.querySelector('.ifso-autocomplete-data-field');
    var hiddenFieldVal = hiddenField.value;
    var position = -1;

    if (hiddenFieldVal) {
      // Adding
      hiddenFieldVal += ADDRESSES_SEPARATOR + newLocationData;
      position = hiddenFieldVal.split(ADDRESSES_SEPARATOR).length - 1; // Minus this iteration addition
    } else {
      // Creating
      hiddenFieldVal = newLocationData;
      position = 0;
      // Making all the relevant components visible with their appropriate classes
      closestGeolocationFieldsContainer.className = "ifso-autocomplete-fields-container shown"; // showing
      multipleSelectedTitle.className = "multiple-selected"; // showing (by removing 'hide-field')
      nonSelectedTitle.className = "none-selected hide-field"; // hiding
      locationsDescriptionElem.className = "locations-description"; // showing (by removing 'hide-field')
    }
    hiddenField.value = hiddenFieldVal;

    return position;
  }
}

function newLocationSelected(wholeGeolocationContainer, newLocationData, newLocationGUI) {
  const position = createNewPositionForLocationSelection(wholeGeolocationContainer, newLocationData);

  // Add to the DOM
  const newFieldHTML = getAutocompleteGeoFieldHTML(position, newLocationGUI);
  const closestGeolocationFieldsContainer = wholeGeolocationContainer.querySelector('.ifso-autocomplete-fields-container');
  appendNewFieldToAutocompleteSection(closestGeolocationFieldsContainer, newFieldHTML);
}

function fillInAddress(elem, autocomplete,type='city') {
  // Get the place details from the autocomplete object.
  // TODO: proper docs
  var place = autocomplete.getPlace();
  var closestContainerParent = getClosest(elem, '.ifso-autocomplete-selection-display');
  if(type==='city'){
    var selectedLocation = (place.vicinity) ? place.vicinity : "";
    var formattedAddress = place.formatted_address;
  }
  if(type==='state'){
    var selectedLocation = place.address_components.length>0 && place.address_components[0].long_name ? place.address_components[0].long_name : "";
    var formattedAddress = selectedLocation;
  }
  elem.value = "";
  var newCityData = createNewLocation(CITY, selectedLocation, formattedAddress);
  newLocationSelected(closestContainerParent, newCityData, formattedAddress);
}

function fillInState(elem,autocomplete){
  fillInAddress(elem,autocomplete,'state');
}

function titleCase(str) {
  var splitStr = str.toLowerCase().split(' ');
  for (var i = 0; i < splitStr.length; i++) {
    // You do not need to check if i is larger than splitStr length, as your for does that for you
    // Assign it back to the array
    splitStr[i] = splitStr[i].charAt(0).toUpperCase() + splitStr[i].substring(1);
  }
  // Directly return the joined string
  return splitStr.join(' ');
}