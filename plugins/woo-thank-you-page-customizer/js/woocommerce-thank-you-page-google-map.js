'use strict';
jQuery(document).ready(function ($) {
    function initMap(google_map_zoom_level) {
        if ($('#woocommerce-thank-you-page-google-map').length > 0) {
            // let center = {lat: 21.4974464, lng: 105.934848};
            var map = new google.maps.Map(document.getElementById('woocommerce-thank-you-page-google-map'), {
                zoom: google_map_zoom_level,
                // center: center,
            });

            var geocoder = new google.maps.Geocoder();
            let address = woo_thank_you_page_front_end_params.google_map_address;
            geocodeAddress(geocoder, map, address);
        }
    }

    function geocodeAddress(geocoder, resultsMap, address) {
        geocoder.geocode({'address': address}, function (results, status) {
            if (status === 'OK') {
                resultsMap.setCenter(results[0].geometry.location);
                var marker = new google.maps.Marker({
                    map: resultsMap,
                    position: results[0].geometry.location,
                    title: address,
                    icon: woo_thank_you_page_front_end_params.google_map_marker,
                });
                var infowindow = new google.maps.InfoWindow({
                    content: woo_thank_you_page_front_end_params.google_map_label.replace(/\n/g, '<\/br>')
                });
                infowindow.open(resultsMap, marker);
                marker.addListener('click', function () {
                    infowindow.open(resultsMap, marker);
                });
            }else {
                $('.woocommerce-thank-you-page-google_map__container').remove();
            }
        });
    }
    window.addEventListener('load', function () {
        initMap(parseInt(woo_thank_you_page_front_end_params.google_map_zoom_level));
    });
});
