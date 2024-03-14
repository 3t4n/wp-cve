"use strict";

var $j = jQuery.noConflict();

$j(document).bind("gform_post_render", function (event, form_id) {
    var gfaacData = window["gfaacMainJsVars_" + form_id];

    if (!gfaacData) {
        return;
    }

    var getFieldsData = gfaacData.elements;

    $j.each(getFieldsData, function (index, name) {
        var ajaxdata = jQuery.parseJSON(name);

        var options;

        if (ajaxdata["restrictCountryGField"]) {
            options = {
                types: ["geocode"],
                componentRestrictions: {
                    country: ajaxdata["restrictCountryGField"],
                },
            };
        } else {
            options = {
                types: ["geocode"],
            };
        }

        var inputId = "input_" + ajaxdata["formId"] + "_" + ajaxdata["id"];

        if (ajaxdata["type"] == "address") {
            var inputFull = inputId + "_1";
        } else {
            var inputFull = inputId;
        }

        var prevent_enter = document.getElementById(inputFull);
        google.maps.event.addDomListener(
            prevent_enter,
            "keydown",
            function (event) {
                if (event.keyCode === 13) {
                    event.preventDefault();
                }
            }
        );

        var autocomplete = new google.maps.places.Autocomplete(
            document.getElementById(inputFull),
            {
                options,
            }
        );

        google.maps.event.addListener(
            autocomplete,
            "place_changed",
            function () {
                var addressLine1 = "";
                var addressLine2 = "";
                var addressLine3 = "";
                var city = "";
                var state = "";
                var country = "";
                var postal_code = "";
                var place = autocomplete.getPlace();

                for (var i = 0; i < place.address_components?.length; i++) {
                    var addressType = place.address_components[i].types[0];
                    var val = place.address_components[i].long_name;

                    switch (addressType) {
                        case "subpremise":
                            addressLine1 += val + "/";
                            break;

                        case "street_number":
                        case "route":
                            addressLine1 += val + " ";
                            break;

                        case "sublocality_level_1":
                        case "sublocality_level_2":
                            addressLine2 = val;
                            break;

                        case "locality":
                            //case "administrative_area_level_2":
                            city += val + " ";
                            break;

                        case "administrative_area_level_1":
                            state = val;
                            break;

                        case "country":
                            country = val;
                            break;

                        case "postal_code":
                            postal_code = val;
                            break;

                        default:
                    }
                }

                if (ajaxdata["singleAutofillGField"] === true) {
                    jQuery("#" + inputId + "_1").val(place.formatted_address);
                } else if (ajaxdata["textAutocompleteGField"] === true) {
                    jQuery("#" + inputId).val(place.formatted_address);
                } else {
                    jQuery("#" + inputId + "_1")
                        .val(addressLine1)
                        .trigger("change");
                    jQuery("#" + inputId + "_2")
                        .val(addressLine2)
                        .trigger("change");
                    jQuery("#" + inputId + "_3")
                        .val(city)
                        .trigger("change");
                    jQuery("#" + inputId + "_4")
                        .val(state)
                        .trigger("change");
                    jQuery("#" + inputId + "_5")
                        .val(postal_code)
                        .trigger("change");
                    jQuery("#" + inputId + "_6")
                        .val(country)
                        .trigger("change");
                }
            }
        );
    });
});
