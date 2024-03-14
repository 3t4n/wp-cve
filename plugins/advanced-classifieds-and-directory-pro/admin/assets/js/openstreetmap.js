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

		// Creating map options.
		const mapOptions = {
			center: [ latitude, longitude ],
			zoom: acadp_admin.zoom_level
		}

		// Creating a map object.        	
		let map = new L.map( mapEl, mapOptions );	

		// Creating a Layer object.
		const layer = new L.TileLayer( 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
			attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
		});

		// Adding layer to the map.
		map.addLayer( layer );

		// Creating marker options.
		const markerOptions = {
			clickable: true,
			draggable: true
		}

		// Creating a marker.
		let marker = L.marker( [ latitude, longitude ], markerOptions );

		// Adding marker to the map.
		marker.addTo( map );
				
		// Update latitude and longitude values in the form when marker is moved
		marker.addEventListener( 'dragend', function( event ) {
			const position = event.target.getLatLng();

			map.panTo( new L.LatLng( position.lat, position.lng ) );
			updateLatLng( position.lat, position.lng );
		});

		// Update map when the contact fields are edited.
		const contactDetailsEl = document.querySelector( '#acadp-contact-details' );
		
		const onAddressChange = () => {
			let query = [];					

			const regionEl = contactDetailsEl.querySelector( 'input[name=acadp_location]:checked' );
			if ( regionEl !== null ) {
				const region = regionEl.closest( '.acadp-term-label' ).querySelector( '.acadp-term-name' ).innerHTML;
				query.push( region );
			}			

			const zipcode = document.querySelector( '#acadp-form-control-zipcode' ).value;
			if ( zipcode ) {
				query.push( zipcode );
			}

			if ( 0 == query.length ) {
				let address = document.querySelector( '#acadp-form-control-address' ).value;

				if ( address ) {
					address = address.replace( /(?:\r\n|\r|\n)/g, ',' );
					address = address.replace( ',,', ',' );
					address = address.replace( ', ', ',' );

					query.push( address );
				}
			}

			query = query.filter( function( v ) { return v !== '' } );
			query = query.join();
			
			$.get( 'https://nominatim.openstreetmap.org/search.php?q=' + encodeURIComponent( query ) +'&polygon_geojson=1&format=jsonv2', function( response ) {
				if ( response.length > 0 ) {
					const latLng = new L.LatLng( response[0].lat, response[0].lon );

					marker.setLatLng( latLng );
					map.panTo( latLng );
					updateLatLng( response[0].lat, response[0].lon );
				}
			}, 'json' );
		}

		$( contactDetailsEl).on( 'blur', '.acadp-form-control-map', function() {
			onAddressChange();
		});

		if ( ! latitude || ! longitude ) {
			onAddressChange();
		}
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
