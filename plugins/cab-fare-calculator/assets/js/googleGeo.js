var GoogleGeoCore = new function ()
{
	this.MapStep1 = null;
	this.DirectionsServiceStep1 = null;
	this.DirectionRendererStep1 = null;
	this.AddressService = null;
	this.PlacesService = null;
	this.MapZoomSize = 14;
	this.GeocodeRadius = 32000;
	this.LocationString = "";
	this.Geogoder = null;
	this.Pushpins = [];
	this.UserLocationLat = 0;
	this.UserLocationLng = 0;
	this.ClientIP = null;
	this.LatestDirtectionMarkers = null;
	// Each marker is labeled with a single alphabetical character.
	this.markerLabels = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

	this.Init = function ()
	{
		GoogleGeoCore.InitMapWithCurrentPosition(function(lat, lng) { GoogleGeoCore.InitMap(lat, lng); });
	};
	this.InitMapWithCurrentPosition = function (initMapFunction) {

		/***DESCRIPTION OF GEOCODING ALGORITHM:

			- Attempt to define location via HTML5 geolocation
				if user shared location (share now OR always), call "successCallback", init Map and set location defined flag as "true"
				if user NOT share location (disallow now OR alays), call "errorCallback"  define location by IP address, init Map and set location defined flag as "true"
				if user do nothing (ignore suggestion of share location) - call "errorCallback" after timeout 5 seconds. 
				!!! This case with timeout also works if user press "Not Now" in firefox, because firefox have next bug: https://bugzilla.mozilla.org/show_bug.cgi?id=675533 
		***/

		var locationDefined = false;
		
		//locationDefined = true;
		initMapFunction(TBFSettings.defaultMapLat,TBFSettings.defaultMapLng); // center to London by default
		
		//if (navigator.geolocation) {
			//navigator.geolocation.getCurrentPosition(successCallback,
								 //errorCallback,
								 //{ enableHighAccuracy: false, timeout: 1000, maximumAge: 2592000000 } // cache for 30 days
								 //);
			//setTimeout(errorCallback, 3000);
		//} else {
			//errorCallback();
		//}
		
		//function successCallback(position) {
			//locationDefined = true;
			//initMapFunction(position.coords.latitude, position.coords.longitude);
		//}
		//function errorCallback() {
			//if (!locationDefined) {
				//getLocationByIp();
				//getLocationByCity();
			//}
		//}
		function getLocationByIp() {
			//determine geolocation by "freegeoip.net"
			jQuery.get("https://freegeoip.net/json/", function (data) {
				locationDefined = true;
				initMapFunction(data.latitude, data.longitude);
			}).fail(function() {
				//determine geolocation by "ipinfo.io"
				jQuery.getJSON('http://ipinfo.io/' + clientIP  + '/json', function(data){
					//console.log(data);
					var locArray = data.loc.split(",");
					var lat = locArray[0];
					var lng = locArray[1];
					locationDefined = true;
					initMapFunction(lat, lng);
				})
				.error(function(){
					//determine geolocation by "ip-api.com"
					jQuery.getJSON('http://ip-api.com/json', function(data){
						//console.log(data);
						locationDefined = true;
						initMapFunction(data.lat, data.lon);
					})
					.error(function(){
						//determine geolocation by "ip.pycox.com"
						jQuery.getJSON('http://ip.pycox.com/json/', function(data){
							//console.log(data);
							locationDefined = true;
							initMapFunction(data.latitude, data.longitude);
						})
						.error(function(){
						})
					})
				})
			}); 
		}
		// center the map to the default city set in backend settings
		function getLocationByCity()
		{
			var address = TBFSettings.defaultCity+', '+TBFSettings.defaultCountry;
			new google.maps.Geocoder().geocode({'address': address}, function(results, status) {
				if (status == google.maps.GeocoderStatus.OK) {
					locationDefined = true;
					//console.log(results[0].geometry.location);
					//GoogleGeoCore.MapStep1.setCenter(results[0].geometry.location);
					TBFSettings.defaultMapLat = results[0].geometry.location.lat();
					TBFSettings.defaultMapLng = results[0].geometry.location.lng();
					//console.log(TBFSettings.defaultMapLat+' '+TBFSettings.defaultMapLng);
					initMapFunction(results[0].geometry.location.lat(), results[0].geometry.location.lng());
				} else {
					console.log('Error on Geocoder.geocode: '+ status);
					//alert('Geocoder failed due to: ' + status);
				}
			})
		}
	};
	this.InitMap = function (lat, lng)
	{
		//save geolocation coordinates
		GoogleGeoCore.LocationString = lat + "," + lng;
		console.log(GoogleGeoCore.LocationString);
		GoogleGeoCore.UserLocationLat = lat;
		GoogleGeoCore.UserLocationLng = lng;

		GoogleGeoCore.Geolocation = new google.maps.LatLng(lat, lng);
		
		//init Map for Step1
		var mapOptions = { center: new google.maps.LatLng(lat, lng),
				zoom: GoogleGeoCore.MapZoomSize,
				disableDefaultUI: false,
				scrollwheel: true,
				disableDoubleClickZoom: false};
				
		// disable map dragging on mobile
		if(jQuery(window).width() < 768) {
			mapOptions.draggable = false;
		}
		else {
			mapOptions.draggable = true;
		}
		if(TBFSettings.defaultCountry!=""){
			mapOptions.componentRestrictions = {
				country: TBFSettings.defaultCountry
			};
		}
		
		if(TBFSettings.showMapOnDesktop){
			this.MapStep1 = new google.maps.Map(document.getElementById("map-canvas-step1"), mapOptions);
		}
		GoogleGeoCore.DirectionsService1 = new google.maps.DirectionsService();
		GoogleGeoCore.DirectionRenderer1 = new google.maps.DirectionsRenderer();
		//GoogleGeoCore.DirectionRenderer1.setMap(GoogleGeoCore.MapStep1);
     
		GoogleGeoCore.AddressService = new google.maps.places.AutocompleteService();
		if(TBFSettings.showMapOnDesktop){
			GoogleGeoCore.PlacesService = new google.maps.places.PlacesService(GoogleGeoCore.MapStep1);
		}

		// UPDATE on Sep11.2018 - PlaceService will be initialized on an empty DIV element
		// as Maps JAVASCRIPT API is still not be triggered and hence map is still not be visible
		if(TBFSettings.showMapInPopupOnly){
			GoogleGeoCore.PlacesService = new google.maps.places.PlacesService(document.createElement('div'));
		}		
		    
		GoogleGeoCore.Geocoder = new google.maps.Geocoder();
		
		// generate pickup autocomplete place list
		if(TBFSettings.addressBookingEnabled)
		{
			this.getGooglePlaceAutocompleteList('address_from', mapOptions);
			this.getGooglePlaceAutocompleteList('address_to', mapOptions);
			if(TBFSettings.stopsEnabled){
				this.getGooglePlaceAutocompleteList('tmp_waypoint', mapOptions);
			}
		}
	};
	//clear pushpins from map
	this.ClearPushpins = function ()
	{
		for (var i = 0; i < GoogleGeoCore.Pushpins.length; i++)
		{
		    GoogleGeoCore.Pushpins[i].setMap(null);
		}
	}
	//render pushpin on map
	this.DisplayPushpinOnMap = function (location, fieldObj)
	{
		if (location != undefined && location.Latitude != null && location.Longitude != null)
		{
			var myLatLng = new google.maps.LatLng(location.Latitude, location.Longitude);
			
			var pushpin = new google.maps.Marker({
			    position: myLatLng,
			    map: GoogleGeoCore.MapStep1,
			    draggable:true
			});
			
			GoogleGeoCore.Pushpins.push(pushpin);
			
			//center map to pushpin
			GoogleGeoCore.MapStep1.panTo(pushpin.getPosition());
			
			google.maps.event.addListener(pushpin, 'dragend', function (event) {
				
				if(jQuery(fieldObj).attr('id')=='address_from') {
					jQuery('#address_from_lat').val(this.getPosition().lat());
					jQuery('#address_from_lng').val(this.getPosition().lng());
					
					var latlng = new google.maps.LatLng(this.getPosition().lat(), this.getPosition().lng());
					GoogleGeoCore.Geocoder.geocode({'latLng': latlng}, function(results, status) {
						if (status == google.maps.GeocoderStatus.OK) {
							if (results[0]) {
								jQuery('#address_from').val(results[0].formatted_address);
							}
						}
					})
				}
			});
		}
	};
	//this mehtod could be used not only to display routing but to calculate distance
	this.RenderDirections = function ()
	{
		//init Map for Step1 if Show Map in Popup = YES
		if(TBFSettings.showMapInPopupOnly)
		{
			var mapOptions = { center: new google.maps.LatLng(jQuery('#address_from_lat').val(), jQuery('#address_from_lng').val()),
					zoom: GoogleGeoCore.MapZoomSize,
					disableDefaultUI: false,
					scrollwheel: true,
					disableDoubleClickZoom: false};
					
			// disable map dragging on mobile
			if(jQuery(window).width() < 768) {
				mapOptions.draggable = false;
			}
			else {
				mapOptions.draggable = true
			}
			if(TBFSettings.defaultCountry!=""){
				mapOptions.componentRestrictions = {
					country: TBFSettings.defaultCountry
				};
			}
			
			this.MapStep1 = new google.maps.Map(document.getElementById("map-canvas-step1"), mapOptions);
		}

		this.labelIndex = 0;
		jQuery("#estimatedDistance,#estimatedDuration").text("");//clear calculated distance
		GoogleGeoCore.DirectionRenderer1.setMap(GoogleGeoCore.MapStep1);
		
		//clear pushpins
		GoogleGeoCore.ClearPushpins();
		
		var stops = [];
		
		if(jQuery('#booking_type').val()=='address'){
			if(jQuery('#address_from_lat').val()!="" && jQuery('#address_from_lng').val()!=""){
				var puLocation =
				{
					Latitude: jQuery('#address_from_lat').val(),
					Longitude: jQuery('#address_from_lng').val()
				};
			}
			if(jQuery('#address_to_lat').val()!="" && jQuery('#address_to_lng').val()!=""){
				var doLocation =
				{
					Latitude: jQuery('#address_to_lat').val(),
					Longitude: jQuery('#address_to_lng').val()
				};
			}
			
			var tmpStops = jQuery('div#stops_data_wrapper').children('.stoprow');
			jQuery('div#stops_data_wrapper').children('.stoprow').each(function(){
				stops.push({
					Latitude: jQuery(this).find('.waypoints_lat').val(),
					Longitude: jQuery(this).find('.waypoints_lng').val()
				});
			})
		}
		
		//According to requirment we need to display PU pushpin when DO not filled yet
		if (puLocation != undefined && doLocation == undefined)
		{
			GoogleGeoCore.DisplayPushpinOnMap(puLocation, jQuery('#address_from'));
		}
		
		if (puLocation != undefined && doLocation != undefined
			&& puLocation.Latitude != null && puLocation.Longitude != null && doLocation.Latitude != null && doLocation.Longitude != null)
		{

			var waypoints = [];
			var i;
			
			for (i = 0; i < stops.length; i++) {
				waypoints.push({
		        	location: new google.maps.LatLng(stops[i].Latitude, stops[i].Longitude),
		        	stopover: true
				});
			}

			var origin = new google.maps.LatLng(puLocation.Latitude, puLocation.Longitude);
			var destination = new google.maps.LatLng(doLocation.Latitude, doLocation.Longitude);
			
			var distance_unit = google.maps.UnitSystem.IMPERIAL;
			if(TBFSettings.distanceUnit == "km") {
			    distance_unit = google.maps.UnitSystem.METRIC;  
			}

			var request =
			{
				origin: origin,
				destination: destination,
				waypoints: waypoints,
				optimizeWaypoints: TBFSettings.optimizeStops,
				travelMode: google.maps.TravelMode.DRIVING,
				unitSystem: distance_unit,
				avoidFerries: TBFSettings.GAPIavoidFerries,
				avoidHighways: TBFSettings.GAPIavoidHighways,
				avoidTolls: TBFSettings.GAPIavoidTolls
			};

			var originalRoute = [puLocation];	//create original route
			for (i = 0; i < stops.length; i++) {
				originalRoute.push(stops[i]);
			}
			originalRoute.push(doLocation);

			var originalLegs = [];	//create original Legs
			var pointsCount = originalRoute.length;
			for (i = 0; i < pointsCount - 1; i++) {
				originalLegs.push({ from: originalRoute[i], to: originalRoute[i+1] });
			}

			GoogleGeoCore.DirectionsService1.route(request, function (result, status)
			{
				if (status == google.maps.DirectionsStatus.OK)
				{
					GoogleGeoCore.DirectionRenderer1.setDirections(result);

					var legs = result.routes[0].legs;
					var stopIndex = 0, distanceMeters = 0, durationSeconds = 0;
					GoogleGeoCore.LatestDirtectionMarkers = [];

					var legsThatNeedRecalc = [];	

					// display original route, and calculate distance
					for (var j = 0; j < legs.length; j++) {

						distanceMeters += legs[j].distance.value;
						durationSeconds += legs[j].duration.value;
						//console.log(legs[j]);
						//pu icon
						if (j == 0) {
							GoogleGeoCore.ApplyMarkerIcon(legs[j].start_location, 'address_from', legs[j].start_address);
							GoogleGeoCore.LatestDirtectionMarkers.push(legs[j].start_location);
						}	//stop icon
						if (legs.length > 1 && j > 0) {   //get start location of leg that is not PU leg

							GoogleGeoCore.LatestDirtectionMarkers.push(legs[j].start_location);
							//stop icon
							GoogleGeoCore.ApplyMarkerIcon(legs[j].start_location, 'waypoint', legs[j].start_address);
							stopIndex++;
						}
						if (j == legs.length - 1) {		//do icon
							GoogleGeoCore.LatestDirtectionMarkers.push(legs[j].end_location);
							GoogleGeoCore.ApplyMarkerIcon(legs[j].end_location, 'address_to', legs[j].end_address);
						}
					}

					GoogleGeoCore.ApplyDistance(distanceMeters, 'outbound');
					GoogleGeoCore.ApplyDuration(durationSeconds, 'outbound');
					
				} else if (status == google.maps.DirectionsStatus.ZERO_RESULTS) {
					jQuery("#estimatedDistance").text(TBTranslations.ERR_MESSAGE_ESTIMATED_DISTANCE_CALCULATE);
					jQuery("#estimatedDuration").text("");
					GoogleGeoCore.DirectionRenderer1.setMap(null);	// clear route on the map
					//if (callback && typeof (callback) === "function") {
						//callback();
					//}
				}
			});
		}
		else
		{
			GoogleGeoCore.DirectionRenderer1.setMap();
		}
	};
	this.ApplyDistance = function (distanceMeters, tripMode)
	{
		if (distanceMeters > 0)
		{
			var coefficient = (TBFSettings.distanceUnit == 'mile') ? 0.000621371192 : 0.001; //coefficient to convert meters to miles or kilometers.
			var distance = Math.round(distanceMeters * coefficient * 100) / 100;

			if (tripMode == 'outbound') {
				var distanceUnitLabel = (TBFSettings.distanceUnit == 'mile') ? TBTranslations.BOOKING_FORM_DISTANCE_UNIT_MILES_LABEL : TBTranslations.BOOKING_FORM_DISTANCE_UNIT_KM_LABEL;
				jQuery("#estimatedDistance").text(TBTranslations.BOOKING_FORM_ESTIMATED_DISTANCE_LBL+": "+distance+" "+distanceUnitLabel);
			}
			else {
			}
		}
		else{
			jQuery("#estimatedDistance").text(TBTranslations.BOOKING_FORM_ESTIMATED_DISTANCE_LBL+": 0");
		}
	}
	this.ApplyDuration = function (durationSeconds, tripMode)
	{
		if (durationSeconds > 0)
		{
			d = Number(durationSeconds);
			var h = Math.floor(d / 3600);
			var m = Math.floor(d % 3600 / 60);
			var s = Math.floor(d % 3600 % 60);
		    
			var hDisplay = h > 0 ? h + TBTranslations.CAR_LIST_ESTIMATED_TIME_HR.replace("%s",""): "";
			var mDisplay = m > 0 ? m + TBTranslations.CAR_LIST_ESTIMATED_TIME_MIN.replace("%s","") : "";
			var sDisplay = s > 0 ? s + (s == 1 ? " second" : " seconds") : "";

			if (tripMode == 'outbound') {
				jQuery("#estimatedDuration").text(TBTranslations.BOOKING_FORM_ESTIMATED_DURATION_LBL+": " + hDisplay + mDisplay);
			}
			else {
			}
		}
		else {
			jQuery("#estimatedDuration").text(TBTranslations.BOOKING_FORM_ESTIMATED_DURATION_LBL+": 0");
		}
	}
	this.ApplyMarkerIcon = function (position, target, title)
	{
		if(target=='waypoint'){ // for now, stop is not draggable
			var marker = new google.maps.Marker({
				position: position,
				map: GoogleGeoCore.MapStep1,
				title: title,
				label: this.markerLabels[this.labelIndex++ % this.markerLabels.length],
				draggable:false
			});
		}
		else {
			// marker icon will be draggable only in Address booking
			if(jQuery('#booking_type').val()=='address'){
				var marker = new google.maps.Marker({
					position: position,
					map: GoogleGeoCore.MapStep1,
					title: title,
					label: this.markerLabels[this.labelIndex++ % this.markerLabels.length],
					draggable: true
				});
			}
			else {
				var marker = new google.maps.Marker({
					position: position,
					map: GoogleGeoCore.MapStep1,
					title: title,
					label: this.markerLabels[this.labelIndex++ % this.markerLabels.length],
					draggable: false
				});
			}
		}
		
		GoogleGeoCore.Pushpins.push(marker);
		
		if(target!='waypoint'){
			google.maps.event.addListener(marker, 'dragend', function (event) {
				jQuery('#'+target+'_lat').val(this.getPosition().lat());
				jQuery('#'+target+'_lng').val(this.getPosition().lng());
				jQuery('#booking_type').val('address');
				
				if(TBFSettings.showMapOnDesktop){
					GoogleGeoCore.RenderDirections();
				}
				
				var latlng = new google.maps.LatLng(this.getPosition().lat(), this.getPosition().lng());
				GoogleGeoCore.Geocoder.geocode({'latLng': latlng}, function(results, status) {
					if (status == google.maps.GeocoderStatus.OK) {
						if (results[0]) {
							jQuery('#'+target).val(results[0].formatted_address);
						}
					}
				})
			});
		}
	};
	this.ResizeMap = function()
	{
		google.maps.event.trigger(GoogleGeoCore.MapStep1, "resize");
	}
	this.getGooglePlaceAutocompleteList = function(target, mapOptions, checkArea)
	{
		if (typeof checkArea == 'undefined') {
			checkArea = true;
		}
		var input = document.getElementById(target);
		var autocompleteObj = new google.maps.places.Autocomplete(input, mapOptions);
		
		google.maps.event.addListener(autocompleteObj, 'place_changed', function() {
			var place = autocompleteObj.getPlace();
			if (place.geometry) {
				jQuery('#'+target+'_lat').val(place.geometry.location.lat());
				jQuery('#'+target+'_lng').val(place.geometry.location.lng());
				jQuery("#"+target).closest('div.step1-inputWrap').removeClass('has-error');
				
				if(checkArea && TBFEngine.checkAreaOperation(target))
				{
					jQuery('#booking_type').val('address');
					if(target=='address_to'){
						TBFEngine.showViewMapBtn();
					}
					if(target!='waypoint'){
						if(TBFSettings.showMapOnDesktop){
							GoogleGeoCore.RenderDirections();
						}
					}
				}
			}
		});
	};
	this.getUserLocation = function(target)
	{
		if(navigator.geolocation)
		{
			navigator.geolocation.getCurrentPosition(function(position) {
				jQuery('#'+target+'_lat').val(position.coords.latitude);
				jQuery('#'+target+'_lng').val(position.coords.longitude);
				//console.log(position.coords.latitude+','+position.coords.longitude);
				jQuery("#"+target).closest('div.step1-inputWrap').removeClass('has-error');
				
				if(TBFEngine.checkAreaOperation(target))
				{
					jQuery('#booking_type').val('address');
					if(target=='address_to'){
						TBFEngine.showViewMapBtn();
					}
					if(TBFSettings.showMapOnDesktop){
						GoogleGeoCore.RenderDirections();
					}
					var latlng = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
					GoogleGeoCore.Geocoder.geocode({'latLng': latlng}, function(results, status) {
						if (status == google.maps.GeocoderStatus.OK) {
							if (results[0]) {
								jQuery('#'+target).val(results[0].formatted_address);
							}
						}
					});
				}
			}, function() {
			});
		}
	};
}