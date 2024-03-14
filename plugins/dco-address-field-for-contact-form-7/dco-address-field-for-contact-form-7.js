if (typeof ymaps !== 'undefined') {
    ymaps.ready(dco_af_cf7_init_yandex);
} else {
    console.log(dco_af_cf7.yandex_maps_api_not_loaded);
}

function dco_af_cf7_init_yandex() {
    var x = document.getElementsByClassName('wpcf7-dco_address');
    var i;
    for (i = 0; i < x.length; i++) {
        if (x[i].getAttribute('data-search-restriction')) {
            var suggestView = new ymaps.SuggestView(x[i], {
                provider: {
                    suggest: (function (request, options) {
                        return ymaps.suggest(document.activeElement.getAttribute('data-search-restriction') + ', ' + request);
                    })}

            });
        } else {
            var suggestView = new ymaps.SuggestView(x[i]);
        }
    }
}

document.addEventListener("DOMContentLoaded", dco_af_cf7_init_google);

function dco_af_cf7_init_google() {
    if (typeof google === 'undefined') {
        console.log(dco_af_cf7.google_maps_api_not_loaded);
        return false;
    }

    var x = document.getElementsByClassName('wpcf7-dco_address_gmaps');
    var i;
    for (i = 0; i < x.length; i++) {
        var search_restriction = x[i].getAttribute('data-search-restriction');
        var options = {};
        if (search_restriction !== null) {
            options = {
                componentRestrictions: {country: search_restriction}
            };
        }

        new google.maps.places.Autocomplete(x[i], options);
    }
}