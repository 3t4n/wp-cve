/*
* Add autocomplete
*/
var autocomplete1, autocomplete2;
function initialize() {
    autocomplete1 = new google.maps.places.Autocomplete(
                  /** @type {HTMLInputElement} */(document.getElementById('mkgd_origin')),
        { types: ['geocode'] });
    google.maps.event.addListener(autocomplete1, 'place_changed', function () {
    });

    autocomplete2 = new google.maps.places.Autocomplete(
        /** @type {HTMLInputElement} */(document.getElementById('mkgd_destination')),
        { types: ['geocode'] });
    google.maps.event.addListener(autocomplete2, 'place_changed', function () {
    });
}

window.onload = function () {
    initialize();
};