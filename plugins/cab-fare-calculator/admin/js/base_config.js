function initialize() {

	geocoder = new google.maps.Geocoder();

	var mapOptions = {
		zoom: 7,
		center: new google.maps.LatLng( 0, 0 ),
		mapTypeId: google.maps.MapTypeId.ROADMAP
	};

	map = new google.maps.Map( document.getElementById( 'map-canvas' ), mapOptions );

	var bounds = new google.maps.LatLngBounds();
	for (var i = 0; i < selectedAreaVerticesArr.length; i++) {
		var myLatLng = new google.maps.LatLng( selectedAreaVerticesArr[i][0],selectedAreaVerticesArr[i][1] );
		triangleCoords.push( myLatLng );
		bounds.extend( myLatLng );
	}
	map.fitBounds( bounds );

	// Construct the polygon.
	areaOfOperation = new google.maps.Polygon(
		{
			paths: triangleCoords,
			strokeColor: '#FF0000',
			strokeOpacity: 0.8,
			strokeWeight: 2,
			fillColor: '#FF0000',
			fillOpacity: 0.35,
			draggable: true,
			editable: true
		}
	);

	areaOfOperation.setMap( map );

	 // Add a listener for the click event.
	google.maps.event.addListener( areaOfOperation, 'click', showArrays );
	google.maps.event.addListener( areaOfOperation, 'dragend', showArrays );
	google.maps.event.addListener( areaOfOperation, 'mouseup', showArrays );
	google.maps.event.addListener( areaOfOperation, 'mouseout', showArrays );
}

function showArrays(event) {

	// Since this polygon has only one path, we can call getPath()
	// to return the MVCArray of LatLngs.
	var vertices = this.getPath();
	// var contentString = '<b>Bermuda Triangle polygon</b><br>Clicked location: <br>' + event.latLng.lat() + ',' + event.latLng.lng() +'<br>';

	// Iterate over the vertices.
	var newAreaVertices = new Array();
	for (var i = 0; i < vertices.getLength(); i++) {
		var xy = vertices.getAt( i );
		newAreaVertices.push( new Array( xy.lat(),xy.lng() ) );
		// contentString += '<br>' + 'Coordinate ' + i + ':<br>' + xy.lat() + ',' + xy.lng();
	}

	window.parent.document.getElementById( 'operation_area_vertices' ).value = JSON.stringify( newAreaVertices );
}

function resetArea() {
	var triangleCoords = new Array();

	var mapOptions = {
		zoom: 7,
		center: new google.maps.LatLng( 0, 0 ),
		mapTypeId: google.maps.MapTypeId.ROADMAP
	};

	map = new google.maps.Map( document.getElementById( 'map-canvas' ), mapOptions );

	var bounds = new google.maps.LatLngBounds();
	for (var i = 0; i < defaultAreaVerticesArr.length; i++) {
		var myLatLng = new google.maps.LatLng( defaultAreaVerticesArr[i][0],defaultAreaVerticesArr[i][1] );
		triangleCoords.push( myLatLng );
		bounds.extend( myLatLng );
	}
	map.fitBounds( bounds );

	// Construct the polygon.
	areaOfOperation = new google.maps.Polygon(
		{
			paths: triangleCoords,
			strokeColor: '#FF0000',
			strokeOpacity: 0.8,
			strokeWeight: 2,
			fillColor: '#FF0000',
			fillOpacity: 0.35,
			draggable: true,
			editable: true
		}
	);

	areaOfOperation.setMap( map );
	window.parent.document.getElementById( 'operation_area_vertices' ).value = JSON.stringify( defaultAreaVerticesArr );
}

google.maps.event.addDomListener( window, 'load', initialize );

jQuery( document ).ready(
	function(){
		jQuery( '.btn-group-yesno label.btn' ).click(
			function () {
				// if (jQuery(this).prop("checked")) {
				// return;
				// }
				jQuery( this ).siblings( 'label.btn' ).removeClass( 'active' );
				jQuery( this ).addClass( 'active' );
				jQuery( this ).parent( '.btn-group-yesno' ).children( 'input' ).prop( 'checked', false );
				jQuery( this ).prev( 'input' ).prop( 'checked', true );
			}
		);
		jQuery( 'label.has-child' ).click(
			function(){
				if (jQuery( this ).hasClass( 'btn-yes' )) {
					jQuery( this ).closest( 'div.form-group' ).next( 'div.has-parent' ).show( 'slow' );
				} else {
					jQuery( this ).closest( 'div.form-group' ).next( 'div.has-parent' ).hide( 'slow' );
				}
			}
		);
		jQuery( '.drop_off_base_calculation_key label.btn' ).click(
			function(){
				if (jQuery( this ).text() == 'No') {
					jQuery( this ).closest( '.drop_off_base_calculation_key' ).siblings( '.drop_off_base_calculation_option' ).hide( 'slow' );
				} else {
					jQuery( this ).closest( '.drop_off_base_calculation_key' ).siblings( '.drop_off_base_calculation_option' ).show( 'slow' );
				}
			}
		);
		jQuery( '.pick_up_base_calculation_key label.btn' ).click(
			function(){
				if (jQuery( this ).text() == 'No') {
					jQuery( this ).closest( '.pick_up_base_calculation_key' ).siblings( '.pick_up_base_calculation_option' ).hide( 'slow' );
				} else {
					jQuery( this ).closest( '.pick_up_base_calculation_key' ).siblings( '.pick_up_base_calculation_option' ).show( 'slow' );
				}
			}
		);
		jQuery( '.show_map_key label.btn' ).click(
			function(){
				if (jQuery( this ).text() == 'Yes') {
					jQuery( this ).closest( '.show_map_key' ).siblings( '.show_map_option' ).hide( 'slow' );
				} else {
					jQuery( this ).closest( '.show_map_key' ).siblings( '.show_map_option' ).show( 'slow' );
				}
			}
		);

		jQuery( '#default_country,#default_city' ).change(
			function(){

				// if country is changed, clear out city
				if (jQuery( this ).attr( 'id' ) == 'default_country') {
					jQuery( '#default_city' ).val( "" );
				}
				var address = jQuery( '#default_country :selected' ).text();

				if (jQuery( '#default_city' ).val() != "") {
					address += ' ' + jQuery( '#default_city' ).val();
				}
				geocoder.geocode(
					{ 'address': address},
					function(results, status) {
						if (status == google.maps.GeocoderStatus.OK) {

							jQuery( '#company_location_lat' ).val( results[0].geometry.location.lat() );
							jQuery( '#company_location_lng' ).val( results[0].geometry.location.lng() );

							map.setCenter( results[0].geometry.location );
							// Move the 2nd polygon to a new location
							// @note: when geodesic is set to false it's recommened to use setTimeout() in order to have the getProjection() function available on the map
							areaOfOperation.moveTo( results[0].geometry.location );

							var newAreaVertices = new Array();
							areaOfOperation.getPath().forEach(
								function(elem, index){
									newAreaVertices.push( new Array( elem.lat(),elem.lng() ) );
								}
							);
							document.getElementById( 'operation_area_vertices' ).value = JSON.stringify( newAreaVertices );
						} else {
							  // alert("Geocode was not successful for the following reason: " + status);
						}
					}
				);
			}
		);

		jQuery( 'a.get_base_coords' ).click(
			function(){
				jQuery( 'html, body' ).animate( { scrollTop: jQuery( '#map-canvas' ).offset().top }, 'fast' );
				if (base_lat != "" && base_lng != "") {
					var base_marker = new google.maps.Marker(
						{
							position: new google.maps.LatLng( base_lat,base_lng ),
							map: map,
							draggable:true,
							animation: google.maps.Animation.DROP,
							title: 'Base'
						}
					);
				} else {
					var base_marker = new google.maps.Marker(
						{
							position: new google.maps.LatLng( selectedAreaVerticesArr[0][0],selectedAreaVerticesArr[0][1] ),
							map: map,
							draggable:true,
							animation: google.maps.Animation.DROP,
							title: 'Base'
						}
					);
				}
				google.maps.event.addListener(
					base_marker,
					'dragend',
					function (event) {
						document.getElementById( "base_lat" ).value  = this.getPosition().lat();
						document.getElementById( "base_long" ).value = this.getPosition().lng();
					}
				);
			}
		)
	}
);
