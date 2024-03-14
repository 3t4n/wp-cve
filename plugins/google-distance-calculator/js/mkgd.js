var source, destination;
var directionsDisplay;
var directionsService = new google.maps.DirectionsService();
// google.maps.event.addDomListener(window, 'load', function () {
//     new google.maps.places.SearchBox(document.getElementById('txtSource-12'));
//     new google.maps.places.SearchBox(document.getElementById('txtDestination'));
//     directionsDisplay = new google.maps.DirectionsRenderer({ 'draggable': true });
// });

function GetRoute(id, unit_system) {
    var mumbai = new google.maps.LatLng(18.9750, 72.8258);
    var mapOptions = {
        zoom: 7,
        center: mumbai
    };
    map = new google.maps.Map(document.getElementById('dvMap-' + id), mapOptions);
    directionsDisplay.setMap(map);
    directionsDisplay.setPanel(null);
    directionsDisplay.setPanel(document.getElementById('dvPanel-' + id));


    //*********DIRECTIONS AND ROUTE**********************//
    source = document.getElementById("txtSource-" + id).value;
    destination = document.getElementById("txtDestination-" + id).value;
console.log(unit_system);
    var request = {
        origin: source,
        destination: destination,
        travelMode: google.maps.TravelMode.DRIVING,
        unitSystem: unit_system == 'metric' ? google.maps.UnitSystem.METRIC : google.maps.UnitSystem.IMPERIAL,
    };
    directionsService.route(request, function (response, status) {
        if (status == google.maps.DirectionsStatus.OK) {
            directionsDisplay.setDirections(response);
        }
    });

    //*********DISTANCE AND DURATION**********************//
    var service = new google.maps.DistanceMatrixService();
    service.getDistanceMatrix({
        origins: [source],
        destinations: [destination],
        travelMode: google.maps.TravelMode.DRIVING,
        unitSystem: unit_system == 'metric' ? google.maps.UnitSystem.METRIC : google.maps.UnitSystem.IMPERIAL,
        avoidHighways: false,
        avoidTolls: false
    }, function (response, status) {
        if (status == google.maps.DistanceMatrixStatus.OK && response.rows[0].elements[0].status != "ZERO_RESULTS") {
            var distance = response.rows[0].elements[0].distance.text;
            var duration = response.rows[0].elements[0].duration.text;
            var dvDistance = document.getElementById("dvDistance-" + id);
            dvDistance.innerHTML = "";
            dvDistance.innerHTML += "Distance: " + distance + "<br />";
            dvDistance.innerHTML += "Duration:" + duration;

        } else {
            alert("Unable to find the distance via road.");
        }
    });
}
