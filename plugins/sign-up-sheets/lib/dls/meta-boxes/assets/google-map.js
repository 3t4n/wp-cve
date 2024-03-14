/**
 * Google Map api js
 *
 * Initializes map in the admin edit area.
 * var dlsmbGoogleApiKey passed in by function addMapJs() in meta-boxes.php
 */
jQuery.getScript("https://maps.googleapis.com/maps/api/js?&key="+dlsmbGoogleApiKey+"&libraries=places", function () {

    if (jQuery('ul#geoData span#location').html()) {
        var latitude = parseFloat(document.getElementById('lat').innerHTML);
        var longitude = parseFloat(document.getElementById('lon').innerHTML);
        var pos = {lat: latitude, lng: longitude};
    } else {
        var latitude = 41.8781136;
        var longitude = -87.62979819999998; //chicago :)
        var pos = null;
    }

    var map = new google.maps.Map(document.getElementById('map'), {
        center: {lat: latitude, lng: longitude},
        zoom: 13
    });

    var input = document.getElementById('searchInput');

    var autocomplete = new google.maps.places.Autocomplete(input);
    autocomplete.bindTo('bounds', map);

    var infowindow = new google.maps.InfoWindow();
    var marker = new google.maps.Marker({
        position: pos,
        map: map,
        draggable: true,
    });

    google.maps.event.addListener(marker, 'dragend', function(event) {

        var latlon = new google.maps.LatLng(this.getPosition().lat(), this.getPosition().lng());        
        var geocoder = new google.maps.Geocoder();
        
        geocoder.geocode({ 'latLng' : latlon }, function( results, status ){

            var location = results[0];
            
            // update input
            jQuery('#searchInput').val( location.formatted_address ).trigger('change');

            //update info box
            infowindow.setContent('<div><strong>' + location.formatted_address + '</strong><br>');
            infowindow.open(map, marker);

            //update hidden fields
            document.getElementById('location').innerHTML = location.formatted_address;
            document.getElementById('lat').innerHTML = location.geometry.location.lat();
            document.getElementById('lon').innerHTML = location.geometry.location.lng();

            //update input hidden input fields
            document.getElementById('dlsmb-map-address').value = location.formatted_address;
            document.getElementById('dlsmb-map-lat').value = location.geometry.location.lat();
            document.getElementById('dlsmb-map-long').value = location.geometry.location.lng();


        });
    });

    autocomplete.addListener('place_changed', function() {
        infowindow.close();
        marker.setVisible(false);
        var place = autocomplete.getPlace();
        if (!place.geometry) {
            window.alert("Autocomplete's returned place contains no geometry");
            return;
        }
  
        // If the place has a geometry, then present it on a map.
        if (place.geometry.viewport) {
            map.fitBounds(place.geometry.viewport);
        } else {
            map.setCenter(place.geometry.location);
            map.setZoom(17);
        }
        marker.setPosition(place.geometry.location);
        marker.setVisible(true);
    
        var address = '';
        if (place.address_components) {
            address = [
              (place.address_components[0] && place.address_components[0].short_name || ''),
              (place.address_components[1] && place.address_components[1].short_name || ''),
              (place.address_components[2] && place.address_components[2].short_name || '')
            ].join(' ');
        }
    
        infowindow.setContent('<div><strong>' + place.name + '</strong><br>' + address);
        infowindow.open(map, marker);
      
        document.getElementById('location').innerHTML = place.formatted_address;
        document.getElementById('lat').innerHTML = place.geometry.location.lat();
        document.getElementById('lon').innerHTML = place.geometry.location.lng();

        //update input hidden input fields
        document.getElementById('dlsmb-map-address').value = place.formatted_address;
        document.getElementById('dlsmb-map-lat').value = place.geometry.location.lat();
        document.getElementById('dlsmb-map-long').value = place.geometry.location.lng();
    });

});