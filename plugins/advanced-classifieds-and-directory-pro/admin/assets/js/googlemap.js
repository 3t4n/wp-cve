(function( $ ) {
	'use strict';	
	
	/**
     * Init map.
	 */
	const initMap = () => {
		document.querySelectorAll( '.acadp-map:not(.acadp-map-loaded)' ).forEach(( mapEl ) => {		
			createMap( mapEl );
		});
	}

	/**
     * Create map.
	 */
	const createMap = ( mapEl ) => {		
		mapEl.classList.add( 'acadp-map-loaded' );

		// Vars
		let latitude  = document.querySelector( '#acadp-form-control-latitude' ).value;
		let longitude = document.querySelector( '#acadp-form-control-longitude' ).value;

		// Create map
		const options = {
			zoom: parseInt( acadp_admin.zoom_level ),
			center: new google.maps.LatLng( 0, 0 ),
			mapTypeId: google.maps.MapTypeId.ROADMAP,
			zoomControl: true,
			scrollwheel: false
		};

		let map = new google.maps.Map( mapEl, options );
	
		// Add marker.
		map.marker = null;

		addMarker( map, latitude, longitude );			

		// Center map.
		centerMap( map );
		
		// Update map when the contact fields are edited.
		const contactDetailsEl = document.querySelector( '#acadp-contact-details' );
		const geoCoder = new google.maps.Geocoder();
		
		const onAddressChange = () => {
			let query = [];
			
			let address = document.querySelector( '#acadp-form-control-address' ).value;
			if ( address ) {
				address = address.replace( /(?:\r\n|\r|\n)/g, ',' );
				address = address.replace( ',,', ',' );
				address = address.replace( ', ', ',' );

				query.push( address );
			}

			const regionEl = contactDetailsEl.querySelector( 'input[name=acadp_location]:checked' );
			if ( regionEl !== null ) {
				const region = regionEl.closest( '.acadp-term-label' ).querySelector( '.acadp-term-name' ).innerHTML;
				query.push( region );
			}					

			const zipcode = document.querySelector( '#acadp-form-control-zipcode' ).value;
			if ( zipcode ) {
				query.push( zipcode );
			}

			query = query.filter( function( v ) { return v !== '' } );
			query = query.join();
		
			geoCoder.geocode({ 'address': query }, function( results, status ) {															
				if ( status == google.maps.GeocoderStatus.OK ) {						
					const point = results[0].geometry.location;

					map.marker.setPosition( point );
					centerMap( map );
					updateLatLng( point.lat(), point.lng() );						
				};				
			});
		}
		
		$( contactDetailsEl ).on( 'blur', '.acadp-form-control-map', function() {
			onAddressChange();
		});
			
		if ( ! latitude || ! longitude ) {
			onAddressChange();
		}
	}	
	
	/**
	 * Add Marker.
	 */
	const addMarker = ( map, latitude, longitude ) => {
		// Vars
		let latLng = new google.maps.LatLng( latitude, longitude );

		// Create marker.
		const marker = new google.maps.Marker({
			position: latLng,
			map: map,
			draggable: true
		});

		map.marker = marker;
		
		// Update latitude and longitude values when the marker is moved.
		google.maps.event.addListener( marker, 'dragend', function() {																  
			const point = marker.getPosition();

			map.panTo( point );
			updateLatLng( point.lat(), point.lng() );			
		});	
	}

	/**
	 * Center the map.
	 */
	const centerMap = ( map ) => {
		// Vars
		let bounds = new google.maps.LatLngBounds();

		// Create bounds.
		if ( map.marker != null ) {	
			const latLng = new google.maps.LatLng( map.marker.position.lat(), map.marker.position.lng() );
			bounds.extend( latLng );
		}
		
		// Set center of map.
		map.setCenter( bounds.getCenter() );
		map.setZoom( parseInt( acadp_admin.zoom_level ) );			
	}
	
	/**
	 * Update the latitude and longitude field values.
	 */
	const updateLatLng = ( latitude, longitude ) => {		
		document.querySelector( '#acadp-form-control-latitude' ).value  = latitude;
		document.querySelector( '#acadp-form-control-longitude' ).value = longitude;
	}	
	
	/**
	 * Called when the page has loaded.
	 */
	$(function() {		
		
		// Init map.
		initMap();			
		
	});

})( jQuery );
