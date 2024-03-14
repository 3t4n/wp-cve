/**
 * 
 * @summary Loads map for given coordinates.
 * 
 * @description Show map with markers. Add marker on click  and update the input field automatically
 * Facilitates auto complete seach for locations.
 * 
 * @param {String} container_id Container for Map
 * @param {String} element_id HTML element for address input
 * @param {String} type_selector_id Search type (ie. addresses,Establishment,Lat Long)
 * @param {Array} addresses List of address to show markers while map loading
 */
var em_gmap_loaded= false; 
function em_initMap(container_id, element_id, type_selector_id, addresses) { 
    container_id = container_id || "map";
    element_id = element_id || "em-pac-input";
    type_selector_id = type_selector_id || "type-selector";
    addresses = addresses || false;
    /*
     * To store all the marker object for further operations
     * @type Array
     */
    var allMarkers = [];

    /*
     * Map object with default location and zoom level
     * @type google.maps.Map
     */
    
    var map = new google.maps.Map(document.getElementById(container_id), {
        center: {lat: -34.397, lng: 150.644},
        zoom: 10,
    });

    /*
     * Textbox to contain formatted address. Same input box can be used to search location either 
     * by lat long or by address.
     * @type Element
     */
    var input = /** @type {!HTMLInputElement} */(
            document.getElementById(element_id));
    
    var geocoder = new google.maps.Geocoder;
    var infowindow = new google.maps.InfoWindow;

    /*
     * Options to select map search criterian (Address,Establishment and LatLong)
     * As of now we don't need such options hence it is hidden on Map
     * @type Element
     */
    var types = document.getElementById(type_selector_id);
    map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
    map.controls[google.maps.ControlPosition.TOP_LEFT].push(types);
    
    var autocomplete = new google.maps.places.SearchBox(input);
    autocomplete.bindTo('bounds', map);

    var infowindow = new google.maps.InfoWindow();
    var marker = new google.maps.Marker({
        map: map,
        anchorPoint: new google.maps.Point(0, -29)
    });

    // Adding marker on map for multiple addresses
    if (addresses) { 
        geocodeAddress(geocoder, map,addresses);
    }


    // Pusging marker object into array for further operations
    allMarkers.push(marker);
    

    autocomplete.addListener('place_changed', function () {
        allMarkers.push(marker);
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
            map.setZoom(17);  // Why 17? Because it looks good.
        }
        marker.setIcon(/** @type {google.maps.Icon} */({
            url: place.icon,
            size: new google.maps.Size(71, 71),
            origin: new google.maps.Point(0, 0),
            anchor: new google.maps.Point(17, 34),
            scaledSize: new google.maps.Size(35, 35)
        }));
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
        //document.getElementById(element_id).value=place.name + " " + address;
        em_event_dispatcher('change',element_id);
        
    });

    // Sets a listener on a radio button to change the filter type on Places
    // Autocomplete.
    function setupClickListener(id, types) {
        var radioButton = document.getElementById(id);
        if(radioButton!=null){
            radioButton.addEventListener('click', function () {
                autocomplete.setTypes(types);
            }); 
            clearInterval(geocode_listner);
        }
    }

    var geocode_listner= setInterval(function(){setupClickListener('changetype-geocode', ['geocode']);},3000);

    /*
     * Listener to handle  click event on Map. 
     */
    map.addListener('click', function (e) {
        // Removing all the previous markers
        setMapOnAll(null);

        // Andding new marker on Map
        placeMarkerAndPanTo(e.latLng, map);
    });

    /*
     * Function to add marker whenever user clicks on Map. Function also sets 
     * formatted address into the search box
     */
    function placeMarkerAndPanTo(latLng, map) {
        var latlng = {lat: parseFloat(latLng.lat()), lng: parseFloat(latLng.lng())};
        geocoder.geocode({'location': latlng}, function (results, status) {
            if (status === google.maps.GeocoderStatus.OK) {
                if (results[1]) {
                    map.setZoom(13);
                    var marker = new google.maps.Marker({
                        position: latlng,
                        map: map
                    });
                    allMarkers.push(marker);
                    /*
                     * Updating the address location in textbox. 
                     * Dispatching on change event on the element for Angular module to identify the model changes.
                     */
                    document.getElementById(element_id).value = results[1].formatted_address;
                    em_event_dispatcher('change', 'em-pac-input');


                    infowindow.setContent(results[1].formatted_address);
                    infowindow.open(map, marker);
                } else {
                    window.alert('No results found');
                }
            } else {
                window.alert('Geocoder failed due to: ' + status);
            }
        });
    }

    // Sets the map on all markers in the array.
    function setMapOnAll(map) {
        for (var i = 0; i < allMarkers.length; i++) {
            allMarkers[i].setMap(map);
        }
    }

    /**
     * @summary Add markers from array of addresses.
     * @param {google.maps.Geocoder} geocoder
     * @param {google.maps.Map} resultsMap
     * @param {String Array} addresses
     * 
     */
     function geocodeAddress(geocoder, resultsMap,addresses) { 
        var infowindow = new google.maps.InfoWindow;   
        for(var i=0;i<addresses.length;i++) {  

            var address= addresses[i];
            if(address){
                geocoder.geocode({'address': address}, function (results, status) {
                    if (status === google.maps.GeocoderStatus.OK) {
                        resultsMap.setCenter(results[0].geometry.location);
                        var marker = new google.maps.Marker({
                            map: resultsMap,
                            position: results[0].geometry.location,
                            icon: em_map_info.gmarker
                        });
                        allMarkers.push(marker);
                        infowindow.setContent(address);
                        infowindow.open(map, marker);
                    } 
                    else if (status === google.maps.GeocoderStatus.OVER_QUERY_LIMIT) {
                        setTimeout( 1000);
                    }  
                    else {
                        alert('Geocode was not successful for the following reason: ' + status);
                    }
                });
            }
        }
    }
}